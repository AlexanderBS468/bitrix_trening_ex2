<?php
/**
 * Created by PhpStorm.
 * User: AlexanderBS
 * Date: 2.11.2018
 * Time: 14:40
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\Loader;

Loc::loadMessages(__FILE__);

class Simplecomponetn_ex2_81 extends CBitrixComponent {
	protected $items;
	protected $categories;


	protected function handlerArParams() {
		$this->arParams['IBLOCKS_CATALOG_ID'] = filter_var($this->arParams['IBLOCKS_CATALOG_ID'], FILTER_VALIDATE_INT);
		$this->arParams['IBLOCKS_CLASSIFIER_ID'] = filter_var($this->arParams['IBLOCKS_CLASSIFIER_ID'] , FILTER_VALIDATE_INT);
		$this->arParams['TEMPLATE_DETAIL'] = trim($this->arParams['TEMPLATE_DETAIL']);
		$this->arParams['CODE_PROPERTY_PRODUCT'] = trim($this->arParams['CODE_PROPERTY_PRODUCT']);
		$this->arParams['CACHE_TIME'] = filter_var($this->arParams['CACHE_TIME'], FILTER_VALIDATE_INT);
		if(!$this->arParams['CACHE_TIME'])
			$this->arParams['CACHE_TIME'] = 36000000;
	}

	protected function validateParams()	{
		return (
			$this->arParams['IBLOCKS_CATALOG_ID']
			&& $this->arParams['IBLOCKS_CLASSIFIER_ID']
			&& $this->arParams['TEMPLATE_DETAIL']
			&& $this->arParams['CODE_PROPERTY_PRODUCT']
		);
	}

	protected function getProducts()
	{
		$refPropCode = $this->arParams['CODE_PROPERTY_PRODUCT'];
		$notEmptyRef = '!PROPERTY_' . $this->arParams['CODE_PROPERTY_PRODUCT'];

		$sort = ['NAME' => 'ASC', 'SORT' => 'ASC'];

		$arFilter = [
			"IBLOCK_ID" => $this->arParams['IBLOCKS_CATALOG_ID'],
			"ACTIVE" => "Y",
			"CHECK_PERMISSIONS" => "Y",
			$notEmptyRef => false
		];
		$arSelect = ["ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL"];

		$resEl = CIBlockElement::GetList($sort, $arFilter, false, false, $arSelect);
		$props = ['PRICE', 'MATERIAL', $refPropCode,];

		if ($this->arParams['TEMPLATE_DETAIL'])
		{
			$resEl->SetUrlTemplates($this->arParams['TEMPLATE_DETAIL']);
		}


		while ($res = $resEl->GetNextElement(false, false))
		{
			$item = $res->GetFields();
			foreach ($props as $propCode)
			{
				$arrayProps = $res->GetProperty($propCode);
				$item['PROPERTIES'][$propCode] = array_filter($arrayProps, function($key)
				{
					return in_array($key, ['ID', 'NAME', 'VALUE']);
				}, ARRAY_FILTER_USE_KEY);
			}
			$items[$item['ID']] = $item;
		}

		return $items;
	}

	protected function getCategoriesForItems() {
		$sort = [];

		$ids = $this->collectCategoryIds();

		$filter = [
			'IBLOCK_ID' => $this->arParams['IBLOCKS_CLASSIFIER_ID'],
			'ID' => $ids,
			'CHECK_PERMISSIONS' => 'Y',
		];

		$select = ['ID', 'NAME'];

		$res = CIBlockElement::GetList($sort, $filter, false, false, $select);

		$items = [];

		while ($ob = $res->fetch())
		{
			$items[$ob['ID']] = $ob;
		}

		return $items;
	}

	protected function collectCategoryIds()
	{
		$result = [];

		foreach ($this->items as $item)
		{
			$categoryPropValue = $item['PROPERTIES'][$this->arParams['CODE_PROPERTY_PRODUCT']]['VALUE'];

			foreach ($categoryPropValue as $categoryId)
			{
				if (isset($result[$categoryId]))
				{
					continue;
				}
				$result[$categoryId] = $categoryId;
			}
		}

		return $result;
	}

//	protected function getCompany() {
//		$arFilter = [
//			"IBLOCK_ID" => $this->arParams['IBLOCKS_CLASSIFIER_ID'],
//			"ACTIVE" => "Y",
//			"CHECK_PERMISSIONS" => "Y",
//		];
//		$arSelect = ['ID', "NAME"];
//
//		$resClass = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
//
//		while ($ob = $resClass->fetch())
//		{
//			$item[$ob['ID']] = $ob;
//		}
//		return $item;
//	}

	protected function setTitle($title)
	{
		global $APPLICATION;
		$APPLICATION->SetTitle($title);
	}

	public function executeComponent() {
		try
		{
			$this->handlerArParams();

			if (!$this->validateParams()) {
				throw new \Exception(Loc::getMessage('EX2_71_SIMPLECOMP_EXAM_CLASS__ERROR_PARAMS_EMPTY'));
			}

			if (!Loader::includeModule("iblock"))
			{
				throw new Exception(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'));
			}

			if ($this->startResultCache())
			{
				$this->items = $this->getProducts();
				$this->categories = $this->getCategoriesForItems();

				$result = [
					'ITEMS' => $this->items,
					'COMPANY' => $this->categories
				];

				$this->arResult = array_merge($this->arResult, $result);

				$this->includeComponentTemplate();
			}

			$this->setTitle(Loc::getMessage(
				'EX2_71_SIMPLECOMP2_EXAM__TITLE',
				['#COUNT#' => count($this->arResult['COMPANY'])]
			));
		}
		catch (Exception $e)
		{
			$this->abortResultCache();
			ShowError($e->getMessage());
			return;
		}
	}
}
