<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
---<br><br>
<b><?=GetMessage("MSG_AUTHIRS_AND_NEWS")?></b><br>
<ul>
	<?/*
	#========================================================
	global $USER;
	if ($USER->IsAdmin()) {
	    echo '<pre id="counter__ID" style="display:none;">';
	        var_dump($arResult);
	    echo '</pre>';
	}
	#========================================================
	*/?>
	<?foreach ($arResult["AUTHORS"] as $arItems):?>
		<li>
			<div><?echo ('[' . $arItems['ID'] . '] - ' . $arItems['LOGIN']);?></div>
			<?foreach ($arItems["ITEMS"] as $Items):?>
				<ul>
					<li>
						<?echo (' - ' . $Items['NAME']);?>
					</li>
				</ul>
			<?endforeach;?>
		</li>
	<?endforeach?>
</ul>
