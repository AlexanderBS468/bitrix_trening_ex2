<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
---<br><br>
<b><?=GetMessage("MSG_CATALOG")?></b><br>
<ul>
	<?foreach ($arResult['ITEMS'] as $arItems):?>
		<li>
			<b><?=$arItems['NAME'];?></b>
			<?foreach ($arItems['ELEM'] as $Items):?>
				<ul>
					<li>
						<?echo ($Items['NAME']);?> - <?=$Items['PROPERTIES']['PRICE']['VALUE']?> - <?=$Items['PROPERTIES']['MATERIAL']['VALUE']?>
					</li>
				</ul>
			<?endforeach;?>
		</li>
	<?endforeach?>
</ul>
