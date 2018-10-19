<?php
/**
 * Created by PhpStorm.
 * User: AlexanderBS
 * Date: 19.10.2018
 * Time: 18:56
 */

define("IBLOCK_PRODUCTS_ID", 2);

AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("MyClass", "OnBeforeIBlockElementUpdateHandler"));

class MyClass
{
	// создаем обработчик события "OnBeforeIBlockElementUpdate"
	function OnBeforeIBlockElementUpdateHandler(&$arFields)
	{
		if ($arFields['IBLOCK_ID'] == IBLOCK_PRODUCTS_ID && $arFields["ACTIVE"] == "N") {
			$arSelect = [
				"ID",
				"NAME",
				"SHOW_COUNTER",
			];
			$arFilter = [
				"IBLOCK_ID" => $arFields['IBLOCK_ID'],
				"ACTIVE_DATE" => "Y",
				"ACTIVE" => "Y",
				"ID" => $arFields["ID"],
				">SHOW_COUNTER" => 2,
			];
			$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect)->fetch();
			if ($res) {
				global $APPLICATION;
				$APPLICATION->throwException("Товар невозможно деактивировать, у него " . $res["SHOW_COUNTER"] ." просмотров");
				return false;
			}
		}
	}
}
