<?php

ini_set('display_errors', 1); error_reporting(E_ALL);
date_default_timezone_set('Europe/Moscow');
mb_internal_encoding('UTF-8');

define('APP_PATH', dirname(__FILE__));


use app\core\router;


spl_autoload_register(function ($class) {
	$file = str_replace('\\', '/', $class.'.php');
	if(is_readable($file)) { require($file); }
});

session_start();

$Router = new Router;
$Router->run();