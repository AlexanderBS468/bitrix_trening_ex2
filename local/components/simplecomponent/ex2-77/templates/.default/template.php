<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<p><b><?=GetMessage("SIMPLECOMP_EXAM2_CAT_TITLE")?></b></p>
<ul>
	<?foreach ($arResult['CLASSIFIER_SECTION'] as $item):?>
		<li>
			<b><?=$item['NAME']?></b> (<?=$item['NAMES_SECTION']?>)
			<ul>
				<?foreach ($item['LINK_ELEMENTS'] as $valueElem):?>
					<li>
						<?=$valueElem['NAME'] . ' - '
							. $valueElem['PROPERTY_PRICE_VALUE'] . ' - '
							. $valueElem['PROPERTY_MATERIAL_VALUE'] . ' - '
							. $valueElem['PROPERTY_ARTNUMBER_VALUE']?>
					</li>
				<?endforeach?>
			</ul>
		</li>
	<?endforeach;?>
</ul>
