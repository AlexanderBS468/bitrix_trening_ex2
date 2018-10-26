<?php
/**
 * Created by PhpStorm.
 * User: AlexanderBS
 * Date: 26.10.2018
 * Time: 13:53
 */
define("IBLOCK_SEOTOOLS_ID", 7);
global $APPLICATION;

$arSelect = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_title", "PROPERTY_description"); //IBLOCK_ID и ID обязательно должны быть
// указаны, см.
// описание arSelectFields выше
$arFilter = Array("IBLOCK_ID"=>IBLOCK_SEOTOOLS_ID, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while ($ob = $res->fetch()) {
	$page = $APPLICATION->GetCurPage(false);
	if($ob["NAME"] == $page) {
		$APPLICATION->SetPageProperty("description", $ob["PROPERTY_DESCRIPTION_VALUE"]);
		$APPLICATION->SetPageProperty("title", $ob["PROPERTY_TITLE_VALUE"]);
	}
}
