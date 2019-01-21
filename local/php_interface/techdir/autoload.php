<?php

/**
 * @author tech-director.ru <help@tech-director.ru>
 * */

spl_autoload_register(function($className)
{
	static $namespace = 'techdir\\phpinterface\\';
	static $namespaceLength = null;

	$classNameLowerCase = strtolower($className);

	if (!isset($namespaceLength))
	{
		$namespaceLength = strlen($namespace);
	}

	if (substr($classNameLowerCase, 0, $namespaceLength) === $namespace)
	{
		$classNameRelative = substr($classNameLowerCase, $namespaceLength);
		$classRelativePath =  str_replace('\\', '/', $classNameRelative) . '.php';
		$classFullPath = __DIR__ . '/lib/' . $classRelativePath;

		if (file_exists($classFullPath))
		{
			require_once $classFullPath;
		}
	}
});

require_once __DIR__ . '/functions/index.php';