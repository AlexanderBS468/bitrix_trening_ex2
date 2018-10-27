<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(

	"PARAMETERS" => array(
		"IBLOCKS_CATALOG_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCKS_CATALOG_ID"),
		),
		"IBLOCKS_NEWS_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCKS_NEWS_ID"),
		),
		"USER_PROPERTY" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("USER_PROPERTY"),
		),
		"CACHE_TIME"  =>  Array("DEFAULT"=>36000000),
	),
);
?>
