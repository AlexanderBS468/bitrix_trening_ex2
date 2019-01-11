<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;

if(!Loader::includeModule("iblock"))
{
	ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
	return;
}

if(intval($arParams["PRODUCTS_IBLOCK_ID"]) > 0)
{
	if($this->startResultCache())
	{
		//iblock sections CLASSIFIER
		$arSelectSect = array (
			"ID",
			"IBLOCK_ID",
			"NAME",
		);
		$arFilterSect = array (
			"IBLOCK_ID" => $arParams["CLASSIFIER_IBLOCK_ID"],
			"ACTIVE" => "Y"
		);

		$arResult["CLASSIFIER_SECTION"] = array();
		$rsSections = CIBlockSection::GetList(false, $arFilterSect, false, $arSelectSect, false);
		while($arSection = $rsSections->GetNext(false, false))
		{
			$arResult["CLASSIFIER_SECTION"][$arSection['ID']] = $arSection;
		}
		$arResult["COUNT_CLASSIFIER"] = count($arResult["CLASSIFIER_SECTION"]);

		//iblock sections
		$arSelectSect = array (
			"ID",
			"IBLOCK_ID",
			"NAME",
			$arParams["CODE_CLASSIFIER"],
		);
		$arFilterSect = array (
			"IBLOCK_ID" => $arParams["PRODUCTS_IBLOCK_ID"],
			"ACTIVE" => "Y"
		);
		$arSortSect = array (
			"ID" => "ASC"
		);

		$arResult["SECTIONS"] = array();
		$rsSections = CIBlockSection::GetList($arSortSect, $arFilterSect, false, $arSelectSect, false);
		while($arSection = $rsSections->GetNext(false, false))
		{
			if($arSection[$arParams["CODE_CLASSIFIER"]] > 0)
			{
				$arResult["CLASSIFIER_SECTION"][$arSection[$arParams["CODE_CLASSIFIER"]]]["AR_NAMES_SECTION"][$arSection['ID']] = $arSection["NAME"];
			}
			$arResult["SECTIONS"][$arSection["ID"]] = $arSection;

			$arResult["CLASSIFIER_SECTION"][$arSection[$arParams["CODE_CLASSIFIER"]]]["NAMES_SECTION"] = implode(', ', $arResult["CLASSIFIER_SECTION"][$arSection[$arParams["CODE_CLASSIFIER"]]]["AR_NAMES_SECTION"]);
		}

		//iblock elements
		$arSelectElems = array (
			"ID",
			"IBLOCK_ID",
			"IBLOCK_SECTION_ID",
			"NAME",
			"PREVIEW_TEXT",
			"PROPERTY_PRICE",
			"PROPERTY_MATERIAL",
			"PROPERTY_ARTNUMBER"
		);
		$arFilterElems = array (
			"IBLOCK_ID" => $arParams["PRODUCTS_IBLOCK_ID"],
			"ACTIVE" => "Y"
		);
		$arSortElems = array (
			"NAME" => "ASC"
		);

		$rsElementElement = CIBlockElement::GetList($arSortElems, $arFilterElems, false, false, $arSelectElems);
		while($arElement = $rsElementElement->GetNext(false, false))
		{
			if($arResult["CLASSIFIER_SECTION"][$arResult["SECTIONS"][$arElement["IBLOCK_SECTION_ID"]][$arParams["CODE_CLASSIFIER"]]] > 0)
			{
				$arResult["CLASSIFIER_SECTION"][$arResult["SECTIONS"][$arElement["IBLOCK_SECTION_ID"]][$arParams["CODE_CLASSIFIER"]]]["LINK_ELEMENTS"][] = $arElement;
			}
		}

		$this->SetResultCacheKeys(array(
			"COUNT_CLASSIFIER",
		));

		$this->includeComponentTemplate();
	} else
	{
		$this->abortResultCache();
	}
}
$APPLICATION->SetTitle(GetMessage("SECTIONS").$arResult["COUNT_CLASSIFIER"]);
?>
