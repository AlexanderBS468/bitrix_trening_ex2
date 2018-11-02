<?php
/**
 * Created by PhpStorm.
 * User: AlexanderBS
 * Date: 30.10.2018
 * Time: 13:30
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\Loader;

Loc::loadMessages(__FILE__);

class Simplecomponetn_ex2_97 extends CBitrixComponent {

	protected $authors;
	protected $userInfo;

	protected function handlerArParams() {
		$this->arParams['IBLOCKS_NEWS_ID'] = filter_var($this->arParams['IBLOCKS_NEWS_ID'], FILTER_VALIDATE_INT);
		$this->arParams['IBLOCKS_NEWS_CODE_PROPERTY'] = trim($this->arParams['IBLOCKS_NEWS_CODE_PROPERTY']);
		$this->arParams['USER_CODE_PROPERTY'] = trim($this->arParams['USER_CODE_PROPERTY']);
		$this->arParams['CACHE_TIME'] = filter_var($this->arParams['CACHE_TIME'], FILTER_VALIDATE_INT);
		if(!$this->arParams['CACHE_TIME'])
			$this->arParams['CACHE_TIME'] = 36000000;
	}

	protected function validateParams()	{
		return (
			$this->arParams['IBLOCKS_NEWS_ID']
			&& $this->arParams['IBLOCKS_NEWS_CODE_PROPERTY']
			&& $this->arParams['USER_CODE_PROPERTY']
		);
	}

	protected function validateUser()
	{
		global $USER;

		if ($USER !== null && $USER instanceof CUser && $USER->IsAuthorized()) {
			return true;
		}

		return false;
	}

	protected function getCurrentUserInfo()
	{
		global $USER;

		$userId = $USER->GetID();

		$filter = [
			'ID' => $userId
		];

		$params = [
			'SELECT' => [ $this->arParams['USER_CODE_PROPERTY'] ],
			'FIELDS' => [ 'ID', 'LOGIN' ]
		];

		$res = CUser::GetList(
			$by = "id",
			$order = "asc",
			$filter,
			$params
		);

		if (!$userData = $res->fetch()) {
			throw new Exception('EX2_97_SIMPLECOMP_EXAM_CLASS__ERROR_USER_NOT_FOUND');
		}
		return [
			'ID' => $userId,
			$this->arParams['USER_CODE_PROPERTY'] => $userData[$this->arParams['USER_CODE_PROPERTY']]
		];
	}

	protected function getNews() { 		//iblock elements_news
		if (!$this->authors) {
			return [];
		}
		$refPropCode = $this->arParams['IBLOCKS_NEWS_CODE_PROPERTY'];
		$filterAuthor = 'PROPERTY_' . $this->arParams['IBLOCKS_NEWS_CODE_PROPERTY'];
		$arSelectElems = array (
			"ID",
			"IBLOCK_ID",
			"NAME",
			'PROPERTY_' . $refPropCode,
		);
		$arFilterElems = array (
			"IBLOCK_ID" => $this->arParams["IBLOCKS_NEWS_ID"],
			"ACTIVE" => "Y",
			$filterAuthor => array_column($this->authors, 'ID'),
		);
		$arSortElems = array (
			"NAME" => "ASC"
		);

		$rsElements = CIBlockElement::GetList($arSortElems, $arFilterElems, false, false, $arSelectElems);

		$items = [];
		while($res = $rsElements->GetNextElement(false,false)) {
			$item = $res->GetFields();
			$propAuthor = $res->GetProperty($this->arParams['IBLOCKS_NEWS_CODE_PROPERTY']);
			if (in_array($this->userInfo['ID'], $propAuthor['VALUE'])) {
				continue;
			}

			$item[$this->arParams['IBLOCKS_NEWS_CODE_PROPERTY']] = $propAuthor;

			$items[] = $item;
		}
		return $items;
	}

	protected function getAuthors() {	// user
		$arOrderUser = "id";
		$sortOrder = "asc";
		$arFilterUser = array(
			$this->arParams['USER_CODE_PROPERTY'] => $this->userInfo[$this->arParams['USER_CODE_PROPERTY']],
			'!ID' => $this->userInfo['ID'],
		);
		$arParametersUsers = array (
			'SELECT' => [$this->arParams['USER_CODE_PROPERTY']],
			'FIELDS' => ['ID', 'LOGIN']
		);

		$rsUsers = CUser::GetList($arOrderUser, $sortOrder, $arFilterUser, $arParametersUsers); // выбираем пользователей

		if (!$rsUsers) {
			return [];
		}

		$result = [];
		while($arUser = $rsUsers->fetch())
		{
			$result[] = $arUser;
		}

		return $result;
	}

	private function setPageTitle($title) {
		global $APPLICATION;
		$APPLICATION->SetTitle($title);
	}

	protected function getUserCacheId()
	{
		return ($this->userInfo) ? $this->userInfo['ID'] : false;
	}


	public function executeComponent() {
		try
		{
			if (!$this->validateUser())
			{
				throw new \Exception(Loc::getMessage('EX2_97_SIMPLECOMP_EXAM_CLASS__ERROR_ACCESS_DENY'));
			}

			$this->handlerArParams();

			if (!$this->validateParams()) {
				throw new \Exception(Loc::getMessage('EX2_97_SIMPLECOMP_EXAM_CLASS__ERROR_PARAMS_EMPTY'));
			}

			$this->userInfo = $this->getCurrentUserInfo();

			$result = [];

			if ($this->startResultCache(false, $this->getUserCacheId()))
			{
				if (!Loader::includeModule("iblock"))
				{
					throw new Exception(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'));
				}

				$this->authors = $this->getAuthors();

				$result['AUTHORS'] = $this->authors;
				$result['ITEMS'] = $this->getNews();
			}
			$this->arResult = array_merge($this->arResult, $result);

			$this->includeComponentTemplate();

		}
		catch (Exception $e)
		{
			$this->abortResultCache();
			ShowError($e->getMessage());
			return;
		}

		$this->setPageTitle(Loc::getMessage('MSG_COUNTER_TITLE_EX2_97', [ '#COUNT#' => count($this->arResult['ITEMS']) ] ));
	}
}
