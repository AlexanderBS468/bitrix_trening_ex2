<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
---<br><br>
<b><?=GetMessage("MSG_CATALOG_EX81")?></b><br>
<?#========================================================
global $USER;
if ($USER->IsAdmin()) {
    echo '<pre id="counter__ID">';
        var_dump($arResult);
    echo '</pre>';
}
#========================================================?>
