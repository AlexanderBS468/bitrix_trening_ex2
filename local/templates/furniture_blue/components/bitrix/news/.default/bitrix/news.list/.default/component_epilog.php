<?php
/**
 * Created by PhpStorm.
 * User: AlexanderBS
 * Date: 19.10.2018
 * Time: 16:37
 */
if ($arParams['DISPLAY_SPECIAL_DATE'] == 'Y') {
	global $APPLICATION;
	$APPLICATION->SetPageProperty('specialdate', $arResult['SPECIAL_DATE']);
}
