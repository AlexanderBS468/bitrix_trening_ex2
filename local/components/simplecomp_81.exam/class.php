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

class Simplecomponetn_ex2_97 extends CBitrixComponent {

	protected function handlerArParams() {
		$this->arParams['CACHE_TIME'] = filter_var($this->arParams['CACHE_TIME'], FILTER_VALIDATE_INT);
		if(!$this->arParams['CACHE_TIME'])
			$this->arParams['CACHE_TIME'] = 36000000;
	}

	protected function validateParams()	{

	}


	public function executeComponent() {
		try
		{

			$result = [];

			if ($this->startResultCache())
			{
				if (!Loader::includeModule("iblock"))
				{
					throw new Exception(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'));
				}
			}

			$this->includeComponentTemplate();

		}
		catch (Exception $e)
		{
			$this->abortResultCache();
			ShowError($e->getMessage());
			return;
		}
	}
}
