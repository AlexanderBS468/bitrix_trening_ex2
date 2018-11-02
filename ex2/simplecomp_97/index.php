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
		'IBLOCKS_NEWS_ID' => '1',
		'IBLOCKS_NEWS_CODE_PROPERTY' => 'AUTHOR',
		'USER_CODE_PROPERTY' => 'UF_AUTHOR_TYPE',
	)
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
