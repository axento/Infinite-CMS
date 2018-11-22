<?php
ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);

define('APPLICATION_ENV', 'laptop');
//define('APPLICATION_ENV', 'test');
//define('APPLICATION_ENV', 'production');

require_once '../application/Initializer.php';

$front = Zend_Controller_Front::getInstance();
if (APPLICATION_ENV == 'production') {
    $front  ->registerPlugin(new Infinite_Controller_Plugin_Bootstrap(APPLICATION_ENV))
            ->throwExceptions( false )
            ->dispatch();
} else {
    $front  ->registerPlugin(new Infinite_Controller_Plugin_Bootstrap(APPLICATION_ENV))
            ->throwExceptions( true )
            ->dispatch();
}
