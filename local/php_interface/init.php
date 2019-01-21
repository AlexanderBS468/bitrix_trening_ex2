<?php
/**
 * Created by PhpStorm.
 * User: AlexanderBS
 * Date: 19.10.2018
 * Time: 18:56
 */
require_once __DIR__ . '/techdir/autoload.php';

if (file_exists(__DIR__ . '/const.php'))
	require_once __DIR__ . '/const.php';

if (file_exists(__DIR__ . '/agents.php'))
	require_once  __DIR__ . '/agents.php';

if (file_exists(__DIR__ . '/events.php'))
	require_once  __DIR__ . '/events.php';
