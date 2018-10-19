<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/**
 * Created by PhpStorm.
 * User: AlexanderBS
 * Date: 19.10.2018
 * Time: 16:10
 */
$arResult['SPECIAL_DATE'] = $arResult['ITEMS'][0]['ACTIVE_FROM'];
$obj_comp = $this->GetComponent();
$obj_comp->SetResultCacheKeys(array(
	"SPECIAL_DATE",
));
