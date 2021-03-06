<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
$arItemsProducts = [];
?>
<p><?echo 'Метка времени: ' . time();?></p>
<?/*#========================================================
global $USER;
if ($USER->IsAdmin()) {
    echo '<pre id="counter__ID" style="display:none;">';
        print_r($arResult);
    echo '</pre>';
}
#========================================================*/?>
<?if ($arResult):?>
	<div>
		<a href="<?=$arResult['FILTER']['URL']?>"><?=$arResult['FILTER']['TITLE']?></a>
	</div>
	---<br><br>
	<b><?=Loc::getMessage("MSG_CATALOG_EX81")?></b><br>
	<ul>
		<?foreach ($arResult['COMPANY'] as $itemCompany):?>
			<?if (($itemCompany['ITEMS'])):?>
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
			<?endif?>
		<?endforeach?>
	</ul>
	<?=$arResult['NAV']?>
	<?$this->SetViewTarget('max_min_price');?>
		<div style="color:red; margin: 34px 15px 35px 15px">
			<?=Loc::getMessage('MSG_MIN_PRICE')?><?=$arResult['MIN_PRICE']?><br><br>
			<?=Loc::getMessage('MSG_MAX_PRICE')?><?=$arResult['MAX_PRICE']?>
		</div>
	<?$this->EndViewTarget();?>

<?endif?>
