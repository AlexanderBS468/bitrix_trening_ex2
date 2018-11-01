<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
---<br><br>
<b><?=GetMessage("MSG_AUTHIRS_AND_NEWS")?></b><br>
<ul>
	<?#var_dump($arResult)?>
	<?foreach ($arResult["ITEMS"] as $arItems):?>
		<li>
			<div><?echo ('[' . $arItems['ID'] . '] - ' . $arItems['LOGIN']);?></div>
			<?foreach ($arItems["NEWS_ELEM"] as $Items):?>
				<ul>
					<li>
						<?echo (' - ' . $Items['NAME']);?>
					</li>
				</ul>
			<?endforeach;?>
		</li>
	<?endforeach?>
</ul>
