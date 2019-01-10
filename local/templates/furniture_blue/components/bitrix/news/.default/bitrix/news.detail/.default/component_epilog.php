<?php
/**
 * Created by PhpStorm.
 * User: AlexanderBS
 * Date: 19.10.2018
 * Time: 18:28
 */
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Loader;
global $USER;

if ($arResult['CANONICAL']) {
	global $APPLICATION;
	$APPLICATION->SetPageProperty("canonical", $arResult["CANONICAL"]);
}

define("IBLOCK_ID_NEWS_COMPARE", 9);

if(!$USER->IsAuthorized())
{
	CJSCore::Init();
}

if(isset($_GET["ID"]))
{
	if(Loader::includeModule("iblock"))
	{
		if($USER->IsAuthorized())
		{
			$user = $USER->GetID() . " " . $USER->GetLogin() . " " . $USER->GetFullName();
		}
		else
		{
			$user = "Не авторизован";
		}

		$el = new CIBlockElement();
		$arFields = [
			"IBLOCK_ID" => IBLOCK_ID_NEWS_COMPARE,
			"NAME" => "Жалоба на новость " . $_GET["ID"],
			"ACTIVE" => "Y",
			"PROPERTY_VALUES" => [
				"USER" => $user,
				"NEWS" => $_GET["ID"]
			],
			"ACTIVE_FROM" => date("d.m.Y H:i:s", strtotime("now"))
		];
	}
	if($PRODUCT_ID = $el->Add($arFields))
	{
		if($_GET["TYPE"] == "AJAX")
		{
			$GLOBALS["APPLICATION"]->RestartBuffer();
			echo json_encode(["ID" => $PRODUCT_ID]);
			die();
		}
		elseif($_GET['TYPE'] == 'GET')
		{
			if(isset($_GET["ID"])){?>
				<script>
					var textEl = document.getElementById('report_responce');
					textEl.innerText = "Ваше мнение учтено, № " + <?=$PRODUCT_ID?>;
				</script>
			<?}else{?>
				<script>
					var textEl = document.getElementById('report_responce');
					textEl.innerText = "Ошибка!";
				</script>
				<?
			}
		}
	}
}

?>
