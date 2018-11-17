<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оценка производительности");
?>
<table border="1" cellpadding="0" cellspacing="0" class="internal" width="100%">
	<tbody><tr class="heading">
		<td width="40%" align="center">Страница</td>
		<td width="20%" align="center">Нагрузка</td>
		<td width="20%" align="center">Количество хитов</td>
		<td width="20%" align="center">Среднее время (сек.)</td>
	</tr>
	<tr>
		<td><a href="perfmon_hit_list.php?lang=ru&amp;set_filter=Y&amp;find_script_name=%2Fex2%2Fsimplecomp_49%2Findex.php">/ex2/simplecomp_49/index.php</a></td>
		
<td class="bx-digit-cell">97.86%</td>
		
<td class="bx-digit-cell">2</td>
		
<td class="bx-digit-cell">0.3762</td>
	</tr>
	</tbody>
</table>
<br>
<p>При работе компонента по умолчанию</p>
<table border="1" cellpadding="0" cellspacing="0" class="internal" width="100%">
	<thead>
	<tr >
		<td>
			<div>ID</div>
		</td>
		<td>
			<div>Хит</div>
		</td>
		<td>
			<div>Страница-время</div>
		</td>
		<td>
			<div>Компоненты</div>
		</td>
		<td>
			<div>Компоненты-время</div>
		</td>
		<td>
			<div>Запросы</div>
		</td>
		<td>
			<div>Запросы-время</div>
		</td>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td>1</td>
		<td>/ex2/simplecomp_49/</td>
		<td>0.3045</td>
		<td>12</td>
		<td>0.1971</td>
		<td>43</td>
		<td>0.0267</td>
	</tr>
	<tr>
		<td>2</td><td>/ex2/simplecomp_49/?clear_cache=Y</td>
		<td>0.4480</td>
		<td>15</td>
		<td>0.4104</td>
		<td>127</td>
		<td>0.1986</td></tr>
	</tbody>
</table><br>
<p>При работе компонента с КЭШЕМ $arResult['IBLOCK_TYPE_ID']</p>
<table border="1" cellpadding="0" cellspacing="0" class="internal" width="100%">
	<thead>
	<tr >
		<td>
			<div>ID</div>
		</td>
		<td>
			<div>Хит</div>
		</td>
		<td>
			<div>Страница-время</div>
		</td>
		<td>
			<div>Компоненты</div>
		</td>
		<td>
			<div>Компоненты-время</div>
		</td>
		<td>
			<div>Запросы</div>
		</td>
		<td>
			<div>Запросы-время</div>
		</td>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td>1</td>
		<td>/ex2/simplecomp_49/</td>
		<td>0.1083</td>
		<td>12</td>
		<td>0.0088</td>
		<td>30</td>
		<td>0.0040</td>
	</tr>
	<tr>
		<td>2</td><td>/ex2/simplecomp_49/?clear_cache=Y</td>
		<td>0.2177</td>
		<td>15</td>
		<td>0.1137</td>
		<td>122</td>
		<td>0.0304</td></tr>
	</tbody>
</table><br>
<table border="1" cellpadding="0" cellspacing="0" class="internal" width="100%">
	<tbody><tr class="heading">
		<td width="40%" align="center">Страница</td>
		<td width="20%" align="center">Размер кеша при работе компонента по умолчанию</td>
		<td width="20%" align="center">Размер кеша, необходимый в некешируемой части</td>
	</tr>
	<tr>
		<td><a href="perfmon_hit_list.php?lang=ru&amp;set_filter=Y&amp;find_script_name=%2Fex2%2Fsimplecomp_49%2Findex.php">/ex2/simplecomp_49/index.php</a></td>
		
<td class="bx-digit-cell">кеш: 15 КБ</td>
		
<td class="bx-digit-cell">кеш: 7 КБ</td>
	</tr>
	</tbody>
</table>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
