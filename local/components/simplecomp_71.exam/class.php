<?php
/**
 * Created by PhpStorm.
 * User: AlexanderBS
 * Date: 29.10.2018
 * Time: 11:11
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class Simplecomponetn2 extends CBitrixComponent {

	private function handlerArParams() {
		$this->arParams['IBLOCKS_CATALOG_ID'] = (int) $this->arParams['IBLOCKS_CATALOG_ID'];
		$this->arParams['IBLOCKS_CLASSIFIER_ID'] = (int) $this->arParams['IBLOCKS_CLASSIFIER_ID'];
		$this->arParams['URL_TEMPLATE_DETAIL'] = $this->arParams['TEMPLATE_DETAIL'];
		$this->arParams['CODE_PROPERTY_PRODUCT'] = trim($this->arParams['CODE_PROPERTY_PRODUCT']);
		if(!$this->arParams['CACHE_TIME'])
			$this->arParams['CACHE_TIME'] = 36000000;
	}

	private function setarResult() {
		$refPropCode = $this->arParams['CODE_PROPERTY_PRODUCT'];
		$notEmptyRef = '!PROPERTY_' . $this->arParams['CODE_PROPERTY_PRODUCT'];

		$arFilter = Array("IBLOCK_ID" => $this->arParams['IBLOCKS_CATALOG_ID'], "ACTIVE" => "Y", "CHECK_PERMISSIONS"
		=> "Y", $notEmptyRef => false);
		$arSelect = Array("ID", "IBLOCK_ID", "NAME");
		$resEl = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
		$props = ['PRICE', 'MATERIAL',	$refPropCode,];

		while($res = $resEl->GetNextElement(false,false)) {
			$item = $res->GetFields();
			foreach ($props as $propCode) {
				$arraaay = $res->GetProperty($propCode);
				$item['PROPERTIES'][$propCode] = array_filter($arraaay, function($key) {
					return in_array($key, ['ID', 'NAME', 'VALUE']);
				}, ARRAY_FILTER_USE_KEY);
			}
			$mixedlist[$item['ID']] = $item;
		}
		$arFilter = Array("IBLOCK_ID" => $this->arParams['IBLOCKS_CLASSIFIER_ID'], "ACTIVE" => "Y", "CHECK_PERMISSIONS" => "Y");
		$arSelect = Array("ID", "IBLOCK_ID", "NAME");
		$count = 0;
		$resClass = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
		while($res = $resClass->Fetch()) {
			foreach ($mixedlist as $keyEl => $items) {
				foreach ($items['PROPERTIES'][$refPropCode]['VALUE'] as $key => &$item) {
					if ($item == $res['ID']) {
						$newMixed['ITEMS'][$res['ID']]['NAME'] = $res['NAME'];
						$newMixed['ITEMS'][$res['ID']]['ELEM'][] = $items;
						$this->arResult = $newMixed;
					}
					$count = count($this->arResult['ITEMS']);
				}
			}
		}
		$this->arResult['COUNT'] = $count;
	}

	public function executeComponent() {
		$this->handlerArParams();
		if (!Bitrix\Main\Loader::includeModule('iblock'))
			return;
		if ($this->StartResultCache()) {
			$this->setarResult();
			$this->IncludeComponentTemplate();
		}
		global $APPLICATION;
		$APPLICATION->SetTitle(GetMessage("MSG_COUNTER_TITLE") . $this->arResult['COUNT'] . GetMessage("MSG_COUNTER_TITLE_END"));
	}
}
