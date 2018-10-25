<?php
/**
 * Created by PhpStorm.
 * User: AlexanderBS
 * Date: 19.10.2018
 * Time: 18:56
 */

define("IBLOCK_PRODUCTS_ID", 2);
define("ELEM_SHOW_COUNTER", 2);

// [ex2-50] Проверка при деактивации товара
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("MyClass", "OnBeforeIBlockElementUpdateHandler"));
// ex2-51 Изменение данных в письме
AddEventHandler('main', 'OnBeforeEventSend', Array("MyClass", "my_OnBeforeEventSend"));


class MyClass
{
	// [ex2-50] Проверка при деактивации товара
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
				">SHOW_COUNTER" => ELEM_SHOW_COUNTER,
			];
			$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect)->fetch();
			if ($res) {
				global $APPLICATION;
				$APPLICATION->throwException("Товар невозможно деактивировать, у него " . $res["SHOW_COUNTER"] ." просмотров");
				return false;
			}
		}
	}

	// ex2-51 Изменение данных в письме
	function my_OnBeforeEventSend(&$arFields, &$arTemplate)
	{
		global $USER;
		$rsUser = CUser::GetByID($USER->GetID());
		$arUser = $rsUser->Fetch();
			file_put_contents($_SERVER['DOCUMENT_ROOT'].'/test.txt', json_encode($arUser) . "\n", FILE_APPEND);
		if ($USER->IsAuthorized()) {
			$arFields['AUTHOR'] = 'Пользователь авторизован: ' . $arUser['ID'] . ' ('. $arUser['LOGIN'] .') '
				. $arUser['NAME'] . ', данные из формы: ' .	$arFields["AUTHOR"];
		} else {
			$arFields['AUTHOR'] = 'Пользователь не авторизован, данные из формы: ' . $arFields["AUTHOR"];
		}
		// ex2-51 Добавлять запись в журнал событий
		CEventLog::Add(array(
			"SEVERITY" => "INFO",
			"AUDIT_TYPE_ID" => "MY_TYPE_LOG",
			"MODULE_ID" => "main",
			"ITEM_ID" => $arFields['ID'],
			"DESCRIPTION" => "Замена данных в отсылаемом письме – " . $arFields['AUTHOR'],
		));
	}
}
