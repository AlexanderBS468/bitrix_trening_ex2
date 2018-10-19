<?php
/**
 * Created by PhpStorm.
 * User: AlexanderBS
 * Date: 19.10.2018
 * Time: 18:26
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


if ($arParams['ID_IBLOCK_REL_CANONICAL']) {

	$arSelect = ["NAME"];
	$arFilter = [
		"IBLOCK_ID"=>$arParams['ID_IBLOCK_REL_CANONICAL'],
		"ACTIVE_DATE"=>"Y",
		"ACTIVE"=>"Y",
		"PROPERTY_NEWS_CANONICAL" => $arResult["ID"],
	];
	$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect)->fetch();
	if ($res) {
		$arResult['CANONICAL'] = $res['NAME'];
	}
	$this->__component->SetResultCacheKeys(["CANONICAL"]);
}
?>
