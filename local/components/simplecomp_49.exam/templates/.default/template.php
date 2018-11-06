<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?#========================================================
global $USER;
if ($USER->IsAdmin()) {
    echo '<pre id="counter__ID" style="display:none;">';
        var_dump($arResult);
    echo '</pre>';
}
#========================================================?>
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
							<li>
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
