<?php
namespace Techdir\PhpInterface;

use Bitrix\Main\Config\Option;

// register constants here

class Config
{
	private static $serializedOptionPrefix = '__TECHDIR__CONFIG__:';

	/**
	 * Shortcut for Bitrix\Main\Config\Option::get
	 * Returns a value of an option (uses techdir.options as moduleId).
	 *
	 * @param string      $name         The option name.
	 * @param string      $default      The default value to return, if a value doesn't exist.
	 * @param bool|string $siteId       The site ID, if the option differs for sites.
	 * @param bool        $isSerialized The flag for use serialize
	 *
	 * @return string
	 * @throws \Bitrix\Main\ArgumentNullException
	 * @throws \Bitrix\Main\ArgumentOutOfRangeException
	 * */
	public static function getOption($name, $default = "", $siteId = false)
	{
		$optionValue = Option::get('techdir.phpinterface', $name, null, $siteId);

		if (strpos($optionValue, static::$serializedOptionPrefix) === 0)
		{
			$unserializedValue = unserialize(substr($optionValue, strlen(static::$serializedOptionPrefix)));
			$optionValue = ($unserializedValue !== false ? $unserializedValue : null);
		}

		if (!isset($optionValue))
		{
			$optionValue = $default;
		}

		return $optionValue;
	}

	/**
	 * Проверяем является ли установка тестовой
	 *
	 * @return bool
	 * */
	public static function isBeta()
	{
		$result = false;

		if (defined('ENV') && ENV === 'dev')
		{
			$result = true;
		}
		else if (Option::get('main', 'update_devsrv', 'N') === 'Y')
		{
			$result = true;
		}
		else if (strpos($_SERVER['HTTP_HOST'], 't-dir') !== false)
		{
			$result = true;
		}

		return $result;
	}

	/**
	 * Shortcut for Bitrix\Main\Config\Option::set
	 * Sets an option value and saves it into a DB (uses techdir.options as moduleId).
	 * After saving the OnAfterSetOption event is triggered.
	 *
	 * @param string $name         The option name.
	 * @param string $value        The option value.
	 * @param string $siteId       The site ID, if the option depends on a site.
	 * @param bool   $isSerialized The flag for use serialize
	 *
	 * @throws \Bitrix\Main\ArgumentOutOfRangeException
	 */
	public static function setOption($name, $value = "", $siteId = "")
	{
		if (!is_scalar($value))
		{
			$value = static::$serializedOptionPrefix . serialize($value);
		}

		Option::set('techdir.phpinterface', $name, $value, $siteId);
	}
}
