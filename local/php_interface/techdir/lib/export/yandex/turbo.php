<?php

namespace Techdir\PhpInterface\Export\Yandex;

use Bitrix\Main\Loader;

class Turbo
{
	static $iblockIds = array('2');

	public static function printRss()
	{
		if (!Loader::includeModule('iblock'))
		{
			return false;
		}

		$iblocks = array();

		$res = \Bitrix\Iblock\IblockTable::getList(array(
			'select' => array('ID', 'NAME'),
			'filter' => array('ID' => self::$iblockIds),
		));
		while ($item = $res->fetch())
		{
			$item['ITEMS'] = array();
			$iblocks[$item['ID']] = $item;
		}

		$res = \CIBlockElement::GetList(Array(
			'DATE_ACTIVE_FROM' => 'DESC',
			'SORT' => 'ASC',
		), array('IBLOCK_ID' => self::$iblockIds, 'ACTIVE' => 'Y'), false, false, array(
			'ID',
			'IBLOCK_ID',
			'NAME',
			'DATE_ACTIVE_FROM',
			'DETAIL_PAGE_URL',
			'DETAIL_TEXT',
			'DETAIL_PICTURE',
//			'PROPERTY_PRICE',
//			'PROPERTY_SIZE',
//			'PROPERTY_S_SIZE',
//			'PROPERTY_ARTNUMBER',
//			'PROPERTY_MATERIAL',
//			'PROPERTY_MANUFACTURER',
		));
		while ($ob = $res->GetNextElement(false, false))
		{
			$arFields = $ob->getFields();
			$arProps = $ob->GetProperties();
			if (isset($iblocks[$arFields['IBLOCK_ID']]['ITEMS'][$arFields['ID']]))
			{
				foreach ($arFields as $key => $value)
				{
					if ($iblocks[$arFields['IBLOCK_ID']]['ITEMS'][$arFields['ID']][$key] != $value)
					{
						if (!is_array($iblocks[$arFields['IBLOCK_ID']]['ITEMS'][$arFields['ID']][$key]))
						{
							$iblocks[$arFields['IBLOCK_ID']]['ITEMS'][$arFields['ID']][$key] = array($iblocks[$arFields['IBLOCK_ID']]['ITEMS'][$arFields['ID']][$key]);
						}
						$iblocks[$arFields['IBLOCK_ID']]['ITEMS'][$arFields['ID']][$key][] = $value;
						$iblocks[$arFields['IBLOCK_ID']]['ITEMS'][$arFields['ID']][$key]['PROPERTY'][] = $arProps;
					}
				}
			}
			else
			{
				$iblocks[$arFields['IBLOCK_ID']]['ITEMS'][$arFields['ID']] = $arFields;
				$iblocks[$arFields['IBLOCK_ID']]['ITEMS'][$arFields['ID']]['PROPERTY'] = $arProps;
			}
		}
#pr($iblocks);
		$iblocks = array_filter($iblocks, function($iblock) {
			return !empty($iblock['ITEMS']);
		});

		?><rss
		xmlns:yandex="http://news.yandex.ru"
		xmlns:media="http://search.yahoo.com/mrss/"
		xmlns:turbo="http://turbo.yandex.ru"
		version="2.0"
	><?
		foreach ($iblocks as $iblock)
		{
			?>
			<channel>
				<title><?=$iblock['NAME']?></title>
				<?
				foreach ($iblock['ITEMS'] as $item) { ?>

					<item turbo="true">
						<link>
						https://<?=$_SERVER['SERVER_NAME']?><?=$item['DETAIL_PAGE_URL']?></link>
						<pubDate><?=date(DATE_RFC822, \MakeTimeStamp($item['DATE_ACTIVE_FROM']))?></pubDate>
						<turbo:content>
							<![CDATA[
							<header>
								<h1><?=$item['NAME']?></h1>
							</header>
							<?
							if (!empty($item['DETAIL_PICTURE']))
							{
								if (is_array($item['DETAIL_PICTURE']))
								{
									?>
									<div data-block="slider" data-view="landscape">
										<?
										foreach ($item['DETAIL_PICTURE'] as $fileId)
										{
											$arFile = \CFile::GetFileArray($fileId);
											?>
											<figure>
												<img src="http://<?=$_SERVER['SERVER_NAME']?><?=$arFile['SRC']?>"/>
											</figure>
											<?
										}
										?>
									</div>
									<?
								}
								else
								{
									$arFile = \CFile::GetFileArray($item['DETAIL_PICTURE']);
									?>
									<figure>
										<img src="http://<?=$_SERVER['SERVER_NAME']?><?=$arFile['SRC']?>"/>
									</figure>
									<?
								}
								?>
								<?
							} ?>
							<?=$item['DETAIL_TEXT']?>
							<?foreach ($item['PROPERTY'] as $arprops):?>
								<p><b><?=$arprops['NAME']?></b>:
								<?if (is_array($arprops['VALUE'])):?>
									<ul>
										<?foreach ($arprops['VALUE'] as $valueProp):?>
											<li>
												<?=$valueProp?>
											</li>
										<?endforeach?>
									</ul>
								<?else:?>
									<?=$arprops['VALUE']?>
								<?endif?>
								</p>
							<?endforeach;?>
							]]>
						</turbo:content>
					</item>
					<?
				} ?>
			</channel>

			<?
		} ?>
		</rss><?

	}
}
