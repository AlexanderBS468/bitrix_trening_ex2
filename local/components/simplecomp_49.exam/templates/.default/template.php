<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
$arItemsProducts = [];
?>
<?#========================================================
global $USER;
if ($USER->IsAdmin()) {
    echo '<pre id="counter__ID" style="display:none;">';
        print_r($arResult);
    echo '</pre>';
}
#========================================================?>
<div>
	<a href="<?=$arResult['FILTER']['URL']?>"><?=$arResult['FILTER']['TITLE']?></a>
</div>
<?if ($arResult['COMPANY']):?>
	---<br><br>
	<b><?=GetMessage("MSG_CATALOG_EX81")?></b><br>
	<ul>
		<?foreach ($arResult['COMPANY'] as $itemCompany):?>
			<li>
				<strong><?echo $itemCompany['NAME']?></strong>
				<?if ($itemCompany['ITEMS']):?>
					<ul>
						<?foreach ($itemCompany['ITEMS'] as $item):?>
							<?if(!in_array($item, $arItemsProducts)):?>
								<?$arItemsProducts[] = $item;?>
								<?
								$this->AddEditAction($item, $arResult['ITEMS'][$item]['ADD_LINK'], CIBlock::GetArrayByID($arResult['ITEMS'][$item]["IBLOCK_ID"], "ELEMENT_ADD"));
								$this->AddEditAction($item, $arResult['ITEMS'][$item]['EDIT_LINK'], CIBlock::GetArrayByID($arResult['ITEMS'][$item]["IBLOCK_ID"], "ELEMENT_EDIT"));
								$this->AddDeleteAction($item, $arResult['ITEMS'][$item]['DELETE_LINK'], CIBlock::GetArrayByID($arResult['ITEMS'][$item]["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" =>Loc::getMessage('ELEM_DELETE_CONFIRM')));
								?>
							<?endif?>
							<li id="<?=$this->GetEditAreaId($item);?>" class="<?=$item?>">
								<?echo $arResult['ITEMS'][$item]['NAME'] . ' - ' .
									$arResult['ITEMS'][$item]['PROPERTIES']['PRICE']['VALUE']  . ' - ' .
									$arResult['ITEMS'][$item]['PROPERTIES']['MATERIAL']['VALUE'] . ' (' .
									$arResult['ITEMS'][$item]['DETAIL_PAGE_URL'] . '.php)'?>
							</li>
						<?endforeach?>
					</ul>
				<?endif?>
			</li>
		<?endforeach?>
	</ul>
<?endif?>
