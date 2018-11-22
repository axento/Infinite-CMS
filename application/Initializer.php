<?php

set_time_limit(0);

define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
define('APPLICATION_ROOT', realpath(dirname(__FILE__) . '/../'));

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
	realpath(APPLICATION_PATH . '/../www/js/tiny_mce'),
    get_include_path(),
)));

date_default_timezone_set('Europe/Brussels');

require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('Axento_')
	->registerNamespace('Infinite_')
	->setFallbackAutoloader(true);
