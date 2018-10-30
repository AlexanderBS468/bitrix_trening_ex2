<?php
/**
 * Created by PhpStorm.
 * User: AlexanderBS
 * Date: 27.10.2018
 * Time: 16:42
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class Simplecomponetn extends CBitrixComponent {

	private function handlerArParams() {
		$this->arParams['IBLOCKS_CATALOG_ID'] = (int) $this->arParams['IBLOCKS_CATALOG_ID'];
		$this->arParams['IBLOCKS_NEWS_ID'] = (int) $this->arParams['IBLOCKS_NEWS_ID'];
		if(!$this->arParams['CACHE_TIME'])
			$this->arParams['CACHE_TIME'] = 36000000;
	}

	private function setarResult() {
		$arSelect = Array("ID", "IBLOCK_ID", "NAME", $this->arParams['USER_PROPERTY']);
		$arFilter = Array("IBLOCK_ID" => $this->arParams['IBLOCKS_CATALOG_ID'], "ACTIVE" => "Y", '!' .
			$this->arParams['USER_PROPERTY'] => false);
		$resSec = CIBlockSection::GetList(Array(), $arFilter, false, $arSelect);
		while($ob = $resSec->Fetch()) {
			$mixed[] = $ob;
		}
		$arSectionid = array_column($mixed, 'ID');

		$arFilter = Array("IBLOCK_ID" => $this->arParams['IBLOCKS_CATALOG_ID'], "ACTIVE" => "Y", 'SECTION_ID' => $arSectionid);
		$arSelect = Array("ID", "IBLOCK_ID", "NAME", 'PROPERTY_PRICE', 'PROPERTY_ARTNUMBER', 'PROPERTY_MATERIAL', 'IBLOCK_SECTION_ID');
		$resEl = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
		$count =0;
		while($res = $resEl->Fetch()) {
			foreach ($mixed as &$item) {
				if($res['IBLOCK_SECTION_ID'] == $item['ID']) {
					$item['ITEMS'][] = $res;
				}
			}
			$count++;
		}

		$this->arResult['COUNT'] = $count;
		$arFilter = Array("IBLOCK_ID" => $this->arParams['IBLOCKS_NEWS_ID'], "ACTIVE" => "Y");
		$arSelect = Array("ID", "IBLOCK_ID", "NAME", 'DATE_ACTIVE_FROM');
		$resEl = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
		$i = 0;
		while($res = $resEl->Fetch()) {
			$this->arResult['ITEMS'][$i] = $res;
			foreach ($mixed as &$item1) {
				foreach ($item1['UF_NEWS_LINK'] as $item2) {
					if ($item2 == $res['ID']) {
						$this->arResult['ITEMS'][$i]['ITEMS'][] = $item1;
					}
				}
			}
			$i++;
		}
//		echo '<pre>' . print_r($this->arResult, true) . '</pre>';
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
		$APPLICATION->SetTitle(GetMessage("MSG_COUNTER_TITLE") . $this->arResult['COUNT'].']');
	}
}
