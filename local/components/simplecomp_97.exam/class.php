<?php
/**
 * Created by PhpStorm.
 * User: AlexanderBS
 * Date: 30.10.2018
 * Time: 13:30
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class Simplecomponetn_ex2_97 extends CBitrixComponent {

	private function handlerArParams() {
		$this->arParams['IBLOCKS_NEWS_ID'] = (int) $this->arParams['IBLOCKS_NEWS_ID'];
		$this->arParams['IBLOCKS_NEWS_CODE_PROPERTY'] = $this->arParams['IBLOCKS_NEWS_CODE_PROPERTY'];
		$this->arParams['USER_CODE_PROPERTY'] = $this->arParams['USER_CODE_PROPERTY'];
		if(!$this->arParams['CACHE_TIME'])
			$this->arParams['CACHE_TIME'] = 36000000;
	}

	private function setarResult() {
//		$refPropCode = $this->arParams['CODE_PROPERTY_PRODUCT'];
//		$notEmptyRef = '!PROPERTY_' . $this->arParams['CODE_PROPERTY_PRODUCT'];
//
//		$arFilter = Array("IBLOCK_ID" => $this->arParams['IBLOCKS_CATALOG_ID'], "ACTIVE" => "Y", "CHECK_PERMISSIONS"
//		=> "Y", $notEmptyRef => false);
//		$arSelect = Array("ID", "IBLOCK_ID", "NAME");
//		$resEl = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
//		$props = ['PRICE', 'MATERIAL',	$refPropCode,];
//
//		while($res = $resEl->GetNextElement(false,false)) {
//			$item = $res->GetFields();
//			foreach ($props as $propCode) {
//				$arraaay = $res->GetProperty($propCode);
//				$item['PROPERTIES'][$propCode] = array_filter($arraaay, function($key) {
//					return in_array($key, ['ID', 'NAME', 'VALUE']);
//				}, ARRAY_FILTER_USE_KEY);
//			}
//			$mixedlist[$item['ID']] = $item;
//		}
//		$arFilter = Array("IBLOCK_ID" => $this->arParams['IBLOCKS_CLASSIFIER_ID'], "ACTIVE" => "Y", "CHECK_PERMISSIONS" => "Y");
//		$arSelect = Array("ID", "IBLOCK_ID", "NAME");
//		$count = 0;
//		$resClass = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
//		while($res = $resClass->Fetch()) {
//			foreach ($mixedlist as $keyEl => $items) {
//				foreach ($items['PROPERTIES'][$refPropCode]['VALUE'] as $key => &$item) {
//					if ($item == $res['ID']) {
//						$newMixed['ITEMS'][$res['ID']]['NAME'] = $res['NAME'];
//						$newMixed['ITEMS'][$res['ID']]['ELEM'][] = $items;
//						$this->arResult = $newMixed;
//					}
//					$count = count($this->arResult['ITEMS']);
//				}
//			}
//		}
//		$this->arResult['COUNT'] = $count;

		//iblock elements_news
		$refPropCode = $this->arParams['IBLOCKS_NEWS_CODE_PROPERTY'];
		$notEmptyRef = '!PROPERTY_' . $this->arParams['IBLOCKS_NEWS_CODE_PROPERTY'];
		$arSelectElems = array (
			"ID",
			"IBLOCK_ID",
			"NAME",
			'PROPERTY_' . $refPropCode,
		);
		$arFilterElems = array (
			"IBLOCK_ID" => $this->arParams["IBLOCKS_NEWS_ID"],
			"ACTIVE" => "Y",
			$notEmptyRef => false,
		);
		$arSortElems = array (
			"NAME" => "ASC"
		);

		$mixed["ELEMENTS"] = array();
		$rsElements = CIBlockElement::GetList($arSortElems, $arFilterElems, false, false, $arSelectElems);

		$props = [$refPropCode];
		while($res = $rsElements->GetNextElement(false,false)) {
			$item = $res->GetFields();
			foreach ($props as $propCode) {
				$arraaay = $res->GetProperty($propCode);
				$item['PROPERTIES'][$propCode] = array_filter($arraaay, function($key) {
					return in_array($key, ['ID', 'NAME', 'VALUE']);
				}, ARRAY_FILTER_USE_KEY);
			}
			$mixed["ELEMENTS"][$item['ID']] = $item;
		}

		// user
		$arOrderUser = "id";
		$sortOrder = "asc";
		$arFilterUser = array(
			"ACTIVE" => "Y"
		);
		$arParametersUsers = array ('SELECT' => [$this->arParams['USER_CODE_PROPERTY']], 'FIELDS' => ['ID', 'LOGIN']);

		$mixed["USERS"] = array();
		$rsUsers = CUser::GetList($arOrderUser, $sortOrder, $arFilterUser, $arParametersUsers); // выбираем пользователей
		while($arUser = $rsUsers->GetNext())
		{
			$mixed["USERS"][] = $arUser;
			foreach ($mixed["ELEMENTS"] as $key => $arElem) {
				foreach ($arElem['PROPERTIES'][$refPropCode]['VALUE'] as $keys => $arPropUser) {
					echo '<p>'.$keys . ' - ' . $arPropUser.'</p>';
					echo '<p>'. $arUser['ID'] . ' - ' . $key .'</p>';
					if ($arPropUser == $arUser['ID']) {
						echo '<p>Add ' . $arElem['ID'] . '</p>';
						$arResult['ITEMS'][$arUser['ID']] = $arUser;
						$arResult['ITEMS'][$arUser['ID']]['NEWS_ELEM'][$key] = $arElem;
						echo '<pre>';
						var_dump($arResult['ITEMS'][$arUser['ID']]['NEWS_ELEM']);
						echo '</pre>';
					}
				}
				echo '<br>';
			}
			$this->arResult = $arResult;
		}

		#========================================================
		global $USER;
		if ($USER->IsAdmin()) {
			echo '<pre id="counter__ID" style="display:none;">';
			var_dump($mixed['ELEMENTS']);
			var_dump($arResult);
			echo '</pre>';
		}
		#========================================================
	}

	private function setPageTitle($title) {
		global $APPLICATION;
		$APPLICATION->SetTitle($title);
	}

	public function executeComponent() {
		$this->handlerArParams();
		if (!Bitrix\Main\Loader::includeModule('iblock'))
			return;
		if ($this->StartResultCache()) {
			$this->setarResult();
			$this->IncludeComponentTemplate();
		}
		$this->setPageTitle(Loc::getMessage('MSG_COUNTER_TITLE_EX2_97', [ '#COUNT#' => $this->arResult['COUNT'] ] ));
	}
}
