<?php
/**
 * Created by PhpStorm.
 * User: AlexanderBS
 * Date: 2.11.2018
 * Time: 14:40
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\Loader,
	Bitrix\Main\Context;

Loc::loadMessages(__FILE__);

class Simplecomponetn_ex2_81 extends CBitrixComponent {
	protected $items;
	protected $categories;
	protected $itemsFilter;

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

		if($this->itemsFilter) {
			$arFilter[] = $this->itemsFilter;
		}

		$arSelect = ["ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL"];
		$resEl = CIBlockElement::GetList($sort, $arFilter, false, false, $arSelect);
		$props = ['PRICE', 'MATERIAL', $refPropCode,];

		if ($this->arParams['TEMPLATE_DETAIL'])
		{
			$resEl->SetUrlTemplates($this->arParams['TEMPLATE_DETAIL']);
		}

		$result = [];

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
			$result[$item['ID']] = $item;
		}

		return $result;
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

		$result = [];

		while ($ob = $res->fetch())
		{
			$result[$ob['ID']] = $ob;
		}

		return $result;
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

	protected function getFilterLink() {
		$request = Context::getCurrent()->getRequest();
		$uri = new Bitrix\Main\Web\Uri($request->getRequestUri());

		if (!$this->itemsFilter) {
			$messageID = 'Фильтровать';
			$uri->addParams(['F' => 'Y']);
		}
		else {
			$messageID = 'Очистить фильтр';
			$uri->deleteParams(['F']);
		}
		return [
			'URL' => $uri->getUri(),
			'TITLE' => $uri->getUri() . ' = ' . $messageID,
		];
	}

	protected function initFilter() {
		$request = Context::getCurrent()->getRequest();

		$this->itemsFilter = null;

		if ($request->get('F')) {
			$this->itemsFilter = [
				'LOGIC' => 'OR',
				[
					'<=PROPERTY_PRICE' => 1700,
					'PROPERTY_MATERIAL' => 'Дерево, ткань',
				],
				[
					'<=PROPERTY_PRICE' => 1500,
					'PROPERTY_MATERIAL' => 'Металл, пластик',
				]
			];
		}
	}

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

			$this->initFilter();


			if ($this->itemsFilter || $this->startResultCache())
			{
				$this->items = $this->getProducts();
				$this->categories = $this->getCategoriesForItems();
				$result = [
					'ITEMS' => $this->items,
					'COMPANY' => $this->categories
				];
				$result['FILTER'] = $this->getFilterLink();

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
