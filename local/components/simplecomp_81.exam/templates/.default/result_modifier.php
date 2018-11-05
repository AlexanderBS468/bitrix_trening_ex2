<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$propCode = $arParams['CODE_PROPERTY_PRODUCT'];

foreach ($arResult['COMPANY'] as $key => $category)
{
	$items = array_keys(array_filter($arResult['ITEMS'],
		function($el) use ($key, $propCode) {
			return (in_array($key, $el['PROPERTIES'][$propCode]['VALUE']));
		}));

	$arResult['COMPANY'][$key]['ITEMS'] = $items;
}
