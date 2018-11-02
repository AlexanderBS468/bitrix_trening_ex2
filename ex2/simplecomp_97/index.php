<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("simplecomp_97");
?>
<?$APPLICATION->IncludeComponent(
	"simplecomp_97.exam",
	"",
	Array(
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CODE_PROPERTY_PRODUCT" => "COMPANY",
		"IBLOCKS_CATALOG_ID" => "2",
		"IBLOCKS_CLASSIFIER_ID" => "8",
		"TEMPLATE_DETAIL" => "1"
	)
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
