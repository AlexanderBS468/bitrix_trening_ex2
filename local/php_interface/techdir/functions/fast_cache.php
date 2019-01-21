<?php
/**
 * Выполняет функцию $callback и кэширует результат на $cache_time секунд с ид $cache_id
 *
 * @example
 * $userId = ...;
 * $arResult = fastCache('project_by_user' . $userId, 3600, function($obCache) use ($userId) {
 *    ...
 *
 *    if ($somethingWrong) {
 *       $obCache->AbortDataCache();
 *       return false;
 *    }
 *
 *    return $list;
 * });
 *
 * @param          $cache_id
 * @param int      $cache_time
 * @param callable $callback
 *
 * @return array
 */
function fastCache($cache_id, $cache_time = 3600, callable $callback)
{
	$obCache = new \CPHPCache();
	if ($obCache->InitCache($cache_time, $cache_id, '/techdir/fast-cache'))
	{
		return $obCache->GetVars();
	}

	$obCache->StartDataCache();
	$arResult = $callback($obCache);
	$obCache->EndDataCache($arResult);

	return $arResult;
}
