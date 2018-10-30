<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("ex2");
?>
<b>[ex2-70] Разработать простой компонент «Каталог товаров»</b><br>
<?$APPLICATION->IncludeComponent(
	"simplecomp.exam",
	"",
	Array(
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"IBLOCKS_CATALOG_ID" => "2",
		"IBLOCKS_NEWS_ID" => "1",
		"USER_PROPERTY" => "UF_NEWS_LINK"
	)
);?>
<br>======================================================================<br>
<b>[ex2-71] Разработать простой компонент «Каталог товаров»</b><br>
<?$APPLICATION->IncludeComponent(
	"simplecomp_71.exam",
	"",
	Array(
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CODE_PROPERTY_PRODUCT" => "19",
		"IBLOCKS_CATALOG_ID" => "2",
		"IBLOCKS_CLASSIFIER_ID" => "8",
		"TEMPLATE_DETAIL" => "1"
	)
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
