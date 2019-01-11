<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentParameters = array(
	"PARAMETERS" => array(
		"PRODUCTS_IBLOCK_ID" => array(
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_CAT_IBLOCK_ID"),
			"TYPE" => "STRING",
		),
		"CLASSIFIER_IBLOCK_ID" => array(
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_CLASSIFIER_IBLOCK_ID"),
			"TYPE" => "STRING",
		),
		"CODE_CLASSIFIER" => array(
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_CODE_CLASSIFIER"),
			"TYPE" => "STRING",
		),
		"CACHE_TIME"  =>  Array("DEFAULT"=>36000000),
	),
);
