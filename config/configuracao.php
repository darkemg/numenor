<?php
// Define as constantes relativas a diretórios do sistema
define('DIR_ROOT', 		$_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR);
define('DIR_CLASSES', 	DIR_ROOT . 'classes' . DIRECTORY_SEPARATOR);
define('DIR_CONFIG', 	DIR_ROOT . 'config' . DIRECTORY_SEPARATOR);
// Define o nível de erro exibido na tela
error_reporting(E_ALL & ~E_NOTICE);
// Autoloader das classes
$loader = new Zend\Loader\StandardAutoloader(array('autoregister_zf' => true));
$loader->registerNamespace('Numenor', DIR_CLASSES . 'Numenor');
$loader->register();