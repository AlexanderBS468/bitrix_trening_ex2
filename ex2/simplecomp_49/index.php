<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("simplecomp_49");
?>
<?$APPLICATION->IncludeComponent(
	"simplecomp_49.exam",
	"",
	Array(
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",

		'IBLOCKS_CATALOG_ID' => '2',
		'IBLOCKS_CLASSIFIER_ID' => '8',
		'TEMPLATE_DETAIL' => 'catalog_exam/#SECTION_ID#/#ELEMENT_CODE#',
		"CODE_PROPERTY_PRODUCT" => "COMPANY",
	)
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
