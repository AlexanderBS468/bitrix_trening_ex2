<?php
/**
 * Created by PhpStorm.
 * User: AlexanderBS
 * Date: 19.10.2018
 * Time: 18:28
 */
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if ($arResult['CANONICAL']) {
	global $APPLICATION;
	$APPLICATION->SetPageProperty("canonical", $arResult["CANONICAL"]);
}
?>
