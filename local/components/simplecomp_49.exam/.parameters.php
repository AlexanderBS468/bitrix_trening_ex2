<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(

	"PARAMETERS" => array(
		"IBLOCKS_CATALOG_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCKS_CATALOG_ID"),
		),
		"IBLOCKS_CLASSIFIER_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCKS_CLASSIFIER_ID"),
		),
		"TEMPLATE_DETAIL" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("TEMPLATE_DETAIL"),
		),
		"CODE_PROPERTY_PRODUCT" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("CODE_PROPERTY_PRODUCT"),
		),
		"COUNT_ELEMENTS_ON_PAGE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("COUNT_ELEMENTS_ON_PAGE"),
		),
		"CACHE_TIME"  =>  Array("DEFAULT"=>36000000),
	),
);
?>
