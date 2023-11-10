<?php
/**
 *  Get Cake's root directory
 */
	define('APP_DIR', 'app');
	define('DS', DIRECTORY_SEPARATOR);
	define('ROOT', dirname(__FILE__));
	define('WEBROOT_DIR', 'webroot');
	define('WWW_ROOT', ROOT . DS . APP_DIR . DS . WEBROOT_DIR . DS);
/**
 * This only needs to be changed if the cake installed libs are located
 * outside of the distributed directory structure.
 */
	if (!defined('CAKE_CORE_INCLUDE_PATH')) {
		//define ('CAKE_CORE_INCLUDE_PATH', FULL PATH TO DIRECTORY WHERE CAKE CORE IS INSTALLED DO NOT ADD A TRAILING DIRECTORY SEPARATOR';
		define('CAKE_CORE_INCLUDE_PATH', ROOT);
	}
	if (function_exists('ini_set')) {
		ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . CAKE_CORE_INCLUDE_PATH . PATH_SEPARATOR . ROOT . DS . APP_DIR . DS);
		define('APP_PATH', null);
		define('CORE_PATH', null);
	} else {
		define('APP_PATH', ROOT . DS . APP_DIR . DS);
		define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
	}
	require CORE_PATH . 'cake' . DS . 'basics.php';
	require APP_PATH . 'config' . DS . 'core.php';
	require CORE_PATH . 'cake' . DS . 'config' . DS . 'paths.php';
	$bootstrap=true;
	$uri      =setUri();
/**
 * As mod_rewrite (or .htaccess files) is not working, we need to take care
 * of what would normally be rewritten, i.e. the static files in app/webroot/
 */
	if ($uri === '/' || $uri === '/index.php') {
		$_GET['url'] = '/';
		require APP_DIR . DS . WEBROOT_DIR . DS . 'index.php';
	} else {
		$elements=explode('/index.php', $uri);

		if (!empty($elements[1])) {
			$path = $elements[1];
		} else {
			$path = '/';
		}
		$_GET['url']=$path;
		require APP_DIR . DS . WEBROOT_DIR . DS . 'index.php';
	}
?>