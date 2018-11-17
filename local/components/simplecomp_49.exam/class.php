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
	protected $resources = ['CLASSIFIERS' => []];
	protected $cachePath = '/ex2-107';

	protected function handlerArParams() {
		$this->arParams['IBLOCKS_CATALOG_ID'] = filter_var($this->arParams['IBLOCKS_CATALOG_ID'], FILTER_VALIDATE_INT);
		$this->arParams['IBLOCKS_CLASSIFIER_ID'] = filter_var($this->arParams['IBLOCKS_CLASSIFIER_ID'] , FILTER_VALIDATE_INT);
		$this->arParams['TEMPLATE_DETAIL'] = trim($this->arParams['TEMPLATE_DETAIL']);
		$this->arParams['CODE_PROPERTY_PRODUCT'] = trim($this->arParams['CODE_PROPERTY_PRODUCT']);
		$this->arParams['COUNT_ELEMENTS_ON_PAGE'] = filter_var($this->arParams['COUNT_ELEMENTS_ON_PAGE'], FILTER_VALIDATE_INT);

		$this->arParams['NAVIGATION'] = CDBResult::GetNavParams(array('nPageSize' => $this->arParams['COUNT_ELEMENTS_ON_PAGE']));

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

//		$sort = ['NAME' => 'ASC', 'SORT' => 'ASC'];
		$sort = ['PROPERTY_PRICE' => 'ASC'];

		$arFilter = [
			"IBLOCK_ID" => $this->arParams['IBLOCKS_CATALOG_ID'],
			"ACTIVE" => "Y",
			"CHECK_PERMISSIONS" => "Y",
			$notEmptyRef => false
		];

		if($this->itemsFilter) {
			$arFilter[] = $this->itemsFilter;
		}

		$navigation = array(
			'nPageSize' => $this->arParams['COUNT_ELEMENTS_ON_PAGE'],
		);

		$arSelect = ["ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL"];
		$resEl = CIBlockElement::GetList($sort, $arFilter, false, $navigation, $arSelect);
		$props = ['PRICE', 'MATERIAL', $refPropCode,];

		if ($this->arParams['TEMPLATE_DETAIL'])
		{
			$resEl->SetUrlTemplates($this->arParams['TEMPLATE_DETAIL']);
		}

		$result = [];

		while ($res = $resEl->GetNextElement(false, false))
		{

			$item = $res->GetFields();

			$arButtons = CIBlock::GetPanelButtons(
				$item["IBLOCK_ID"],
				$item["ID"],
				0,
				array("SECTION_BUTTONS"=>false, "SESSID"=>false)
			);
			$item["ADD_LINK"] = $arButtons["edit"]["add_element"]["ACTION_URL"];
			$item["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
			$item["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];

			foreach ($props as $propCode)
			{
				$arrayProps = $res->GetProperty($propCode);
				$item['PROPERTIES'][$propCode] = array_filter($arrayProps, function($key)
				{
					return in_array($key, ['ID', 'NAME', 'VALUE']);
				}, ARRAY_FILTER_USE_KEY);
			}
			$result['IBLOCK_TYPE_ID'] = $item['IBLOCK_TYPE_ID'];
			$result['ITEMS'][$item['ID']] = $item;
		}
		$this->resources['CLASSIFIERS'] = $resEl;
		$first_el = reset($result['ITEMS']);
		$end_el = end($result['ITEMS']);
		$result['MIN_PRICE'] = $first_el['PROPERTIES']['PRICE']['VALUE'];
		$result['MAX_PRICE'] = $end_el['PROPERTIES']['PRICE']['VALUE'];

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
	protected function setNavPageClassification() {
		$navStr = $this->resources["CLASSIFIERS"]->GetPageNavString(Loc::getMessage('EX2_60_CLASS_NAV_CLASSIFIERS'));
		$this->arResult["NAV"] = $navStr;

	}

	public function executeComponent() {
		global $APPLICATION;
		global $CACHE_MANAGER;
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

			if ($this->itemsFilter || $this->startResultCache(false, [$this->arParams["NAVIGATION"]], $this->cachePath))
			{
				$CACHE_MANAGER->RegisterTag('iblock_id_3');
				$this->items = $this->getProducts();
				$this->categories = $this->getCategoriesForItems();
				$result = [
					'IBLOCK_TYPE_ID' => $this->items['IBLOCK_TYPE_ID'],
					'ITEMS' => $this->items['ITEMS'],
					'COMPANY' => $this->categories,
					'MAX_PRICE' => $this->items['MAX_PRICE'],
					'MIN_PRICE' => $this->items['MIN_PRICE'],
				];
				$result['FILTER'] = $this->getFilterLink();
				$this->setNavPageClassification();
				$this->arResult = array_merge($this->arResult, $result);
//				$this->SetResultCacheKeys(array('IBLOCK_TYPE_ID'));
				$this->includeComponentTemplate();
			}
// Oki doci      https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=3852&LESSON_PATH=3913.4776.5052.3852
			if ($APPLICATION->GetShowIncludeAreas())
			{
				$this->AddIncludeAreaIcons(
					Array( //массив кнопок toolbar'a
						Array(
							"ID" => "SIMPLECOMP_49_ex2-100",
							"TITLE" => Loc::getMessage('EX2_100_MESS_TOOLBAR_ICON'),
							"URL" => '/bitrix/admin/iblock_element_admin.php?IBLOCK_ID=' . $this->arParams['IBLOCKS_CATALOG_ID'] .'&type=' .
								$this->arResult['IBLOCK_TYPE_ID']. '&lang='. LANGUAGE_ID .'&find_el_y=Y',
							//или
							// javascript:MyJSFunction ()
							//CSS-класс с иконкой "ICON" => "menu-delete",
							//массив пунктов контекстного меню "MENU" => Array(),
							//тултип кнопки "HINT" => array(
							//	"TITLE" => "Заголовок тултипа",
							//	"TEXT" => "Текст тултипа" //HTML допускается
							//),
							//тултип кнопки контекстного меню "HINT_MENU" => array (
							//	"TITLE" => "Заголовок тултипа",
							//	"TEXT" => "Текст тултипа" //HTML допускается
							//),
				            "IN_PARAMS_MENU" => true, //показать в контекстном меню
				            //"IN_MENU" => true //показать в подменю компонента
				        )
				    )
				);
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
