<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("simplecomp_49");
?><?$APPLICATION->IncludeComponent(
	"simplecomp_49.exam", 
	".default", 
	array(
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CODE_PROPERTY_PRODUCT" => "COMPANY",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"COUNT_ELEMENTS_ON_PAGE" => "1",
		"IBLOCKS_CATALOG_ID" => "2",
		"IBLOCKS_CLASSIFIER_ID" => "8",
		"TEMPLATE_DETAIL" => "catalog_exam/#SECTION_ID#/#ELEMENT_CODE#",
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>