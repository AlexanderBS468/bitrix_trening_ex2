<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
---<br>
<b><?=GetMessage("MSG_CATALOG")?></b><br>
<ul>
	<?#========================================================
	global $USER;
	if ($USER->IsAdmin()) {
	    echo '<pre id="counter__ID" style="display:none;">';
	        print_r($arResult);
	    echo '</pre>';
	}
	#========================================================?>
	<?foreach ($arResult['ITEMS'] as $arItems):?>
		<li>
			<b><?=$arItems['NAME'];?></b> - <?=$arItems['DATE_ACTIVE_FROM']?>
				<?$count_el = count($arItems['ITEMS'])?>
				<?foreach ($arItems['ITEMS'] as $key => $arItemSec):?>
					<?if ($key == 0) {
						echo '(' . $arItemSec['NAME'] . ', ';
					} elseif ($count_el-1 != $key) { echo $arItemSec['NAME'] . ', ';} else {echo $arItemSec['NAME'].')
					';}?>
				<?endforeach;?>
			<?foreach ($arItems['ITEMS'] as $Items):?>
				<ul>
					<?foreach ($Items['ITEMS'] as $arElem):?>
						<li>
							<?echo ($arElem['NAME']);?> - <?=$arElem['PROPERTY_PRICE_VALUE']?> - <?=$arElem['PROPERTY_MATERIAL_VALUE']?> - <?=$arElem['PROPERTY_ARTNUMBER_VALUE']?>
						</li>
					<?endforeach;?>
				</ul>
			<?endforeach;?>
		</li>
	<?endforeach?>
</ul>
