<?php
/**
 * Created by PhpStorm.
 * User: AlexanderBS
 * Date: 02.11.2018
 * Time: 11:49
 */
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

foreach ($arResult['AUTHORS'] as $key => $author)
{
	$authorId = $author['ID'];
	$propAuthor = $arParams['IBLOCKS_NEWS_CODE_PROPERTY'];

	$author['ITEMS'] = array_filter($arResult['ITEMS'], function ($el) use ($authorId, $propAuthor) {
		return (in_array($authorId, $el[$propAuthor]['VALUE']));
	});
	$arResult['AUTHORS'][$key] = $author;
}
