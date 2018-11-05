<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("simplecomp_71");
?>
<?$APPLICATION->IncludeComponent(
	"simplecomp_71.exam",
	"",
	Array(
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",

		"CODE_PROPERTY_PRODUCT" => "COMPANY",
		"IBLOCKS_CATALOG_ID" => "2",
		"IBLOCKS_CLASSIFIER_ID" => "8",
		"TEMPLATE_DETAIL" => "?"
	)
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
