<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("user.php");
?><?$APPLICATION->IncludeComponent(
	"bitrix:main.profile",
	".default",
	Array(
		"CHECK_RIGHTS" => "N",
		"COMPONENT_TEMPLATE" => ".default",
		"SEND_INFO" => "Y",
		"SET_TITLE" => "Y",
		"USER_PROPERTY" => array(),
		"USER_PROPERTY_NAME" => "",
"EVENT_MESSAGE_ID" => array(0=>"84",),
	)
);?><br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>