<?php
/**
 * List of helpers to include
 */
	uses('router', DS.'controller'.DS.'controller');
/**
 * Dispatcher translates URLs to controller-action-paramter triads.
 *
 * Dispatches the request, creating appropriate models and controllers.
 *
 * @package		cake
 * @subpackage	cake.cake
 */
class Dispatcher extends Object {
/**
 * Base URL
 * @var string
 */
	var $base = false;
/**
 * @var string
 */
   var $dispatch_return = null;
/**
 * @var string
 */
	var $admin = false;
/**
 * @var string
 */
	var $webservices = null;
/**
 * @var string
 */
	var $plugin = null;

/**
 * Constructor.
 */

	function __construct() {
		parent::__construct();
	}
/**
 * Dispatches and invokes given URL, handing over control to the involved controllers, and then renders the results (if autoRender is set).
 *
 * If no controller of given name can be found, invoke() shows error messages in
 * the form of Missing Controllers information. It does the same with Actions (methods of Controllers are called
 * Actions).
 *
 * @param string $url	URL information to work on.
 * @param array $additionalParams	Settings array ("bare", "return"),
 * which is melded with the GET and POST params.
 * @return boolean		Success
 */
	function dispatch($url, $additionalParams=array()) {
		$params = array_merge($this->parseParams($url), $additionalParams);
		$missingController = false;
		$missingAction = false;
		$missingView = false;
		$privateAction = false;
		$this->base = $this->baseUrl();

		if (empty($params['controller'])) {
			$missingController = true;
		} else {
			$ctrlName = Inflector::camelize($params['controller']);
			$ctrlClass = $ctrlName.'Controller';

			if (!loadController($ctrlName)) {
				$pluginName = Inflector::camelize($params['action']);
				if (!loadPluginController(Inflector::underscore($ctrlName), $pluginName)) {
					if(preg_match('/([\\.]+)/', $ctrlName)) {
						return $this->cakeError('error404', array(
														array('url' => strtolower($ctrlName),
																'message' => 'Was not found on this server',
																'base' => $this->base)));
					} elseif(!class_exists($ctrlClass)) {
						$missingController = true;
					} else {
						$params['plugin'] = null;
						$this->plugin = null;
					}
				} else {
					$params['plugin'] = Inflector::underscore($ctrlName);
				}
			} else {
				$params['plugin'] = null;
				$this->plugin = null;
			}
		}

		if(isset($params['plugin'])){
			$plugin = $params['plugin'];
			$pluginName = Inflector::camelize($params['action']);
			$pluginClass = $pluginName.'Controller';
			$ctrlClass = $pluginClass;
			$oldAction = $params['action'];
			$params = $this->_restructureParams($params);
			$this->plugin = $plugin;
			loadPluginModels($plugin);
			$this->base = $this->base.'/'.Inflector::underscore($ctrlName);

			if(empty($params['controller']) || !class_exists($pluginClass)) {
				$params['controller'] = Inflector::underscore($ctrlName);
				$ctrlClass = $ctrlName.'Controller';
				if (!is_null($params['action'])) {
					array_unshift($params['pass'], $params['action']);
				}
				$params['action'] = $oldAction;
			}
		}

		if (empty($params['action'])) {
			$params['action'] = 'index';
		}

		if(defined('CAKE_ADMIN')) {
			if(isset($params[CAKE_ADMIN])) {
				$this->admin = '/'.CAKE_ADMIN ;
				$url = preg_replace('/'.CAKE_ADMIN.'\//', '', $url);
				$params['action'] = CAKE_ADMIN.'_'.$params['action'];
			} elseif (strpos($params['action'], CAKE_ADMIN) === 0) {
					$privateAction = true;
			}
		}

		if ($missingController) {
			return $this->cakeError('missingController', array(
											array('className' => Inflector::camelize($params['controller']."Controller"),
													'webroot' => $this->webroot,
													'url' => $url,
													'base' => $this->base)));
		} else {
			$controller =& new $ctrlClass();
		}

		$classMethods = get_class_methods($controller);
		$classVars = get_object_vars($controller);

		if((in_array($params['action'], $classMethods) || in_array(strtolower($params['action']), $classMethods)) && strpos($params['action'], '_', 0) === 0) {
			$privateAction = true;
		}

		if(!in_array($params['action'], $classMethods) && !in_array(strtolower($params['action']), $classMethods)) {
			$missingAction = true;
		}

		if (in_array(strtolower($params['action']), array('tostring', 'requestaction', 'log',
																			'cakeerror', 'constructclasses', 'redirect',
																			'set', 'setaction', 'validate', 'validateerrors',
																			'render', 'referer', 'flash', 'flashout',
																			'generatefieldnames', 'postconditions', 'cleanupfields',
																			'beforefilter', 'beforerender', 'afterfilter'))) {
			$missingAction = true;
		}

		if(in_array('return', array_keys($params)) && $params['return'] == 1) {
			$controller->autoRender = false;
		}

		$controller->base = $this->base;
		$base = strip_plugin($this->base, $this->plugin);
		if(defined("BASE_URL")){
			$controller->here = $base . $this->admin . $url;
		} else {
			$controller->here = $base . $this->admin . '/' . $url;
		}
		$controller->webroot = $this->webroot;
		$controller->params = $params;
		$controller->action = $params['action'];
		$this->dispatch_return = Dispatcher::return_session_dispatcher();
		if (!empty($controller->params['data'])) {
			$controller->data =& $controller->params['data'];
			foreach($controller->data as $Kdata =>$Vdata){
				if(is_array($Vdata)){
					foreach($Vdata as $KD =>$VD){
						  $AUX[$Kdata][$KD] = Object::strsession($VD,$this->http_use);
						  //$AUX[$Kdata][$KD] = addslashes(Object::strsession($VD,$this->http_use));
						  $AUX[$Kdata][$KD] = strtoupper_sigep($AUX[$Kdata][$KD]);
						  $AUX[$Kdata][$KD] = str_replace("'","&rsquo;",        $AUX[$Kdata][$KD]);
						  $AUX[$Kdata][$KD] = str_replace(">","&gt;",           $AUX[$Kdata][$KD]);
						  $AUX[$Kdata][$KD] = str_replace("<","&lt;",           $AUX[$Kdata][$KD]);
						  $AUX[$Kdata][$KD] = str_replace("&RSQUO;","&rsquo;",  $AUX[$Kdata][$KD]);
						  $AUX[$Kdata][$KD] = str_replace("&GT;","&gt;",        $AUX[$Kdata][$KD]);
						  $AUX[$Kdata][$KD] = str_replace("&LT;","&lt;",        $AUX[$Kdata][$KD]);
						  $AUX[$Kdata][$KD] = str_replace('"',"&ldquo;",        $AUX[$Kdata][$KD]);
						  $AUX[$Kdata][$KD] = str_replace("&LDQUO;","&ldquo;",  $AUX[$Kdata][$KD]);
						  $AUX[$Kdata][$KD] = str_replace("\\",   "",           $AUX[$Kdata][$KD]);


					}
				}else{
					      $AUX[$Kdata] = Object::strsession($Vdata, $this->http_use);
					      $AUX[$Kdata] = strtoupper_sigep($AUX[$Kdata]);
					      //$AUX[$Kdata] = addslashes(Object::strsession($Vdata,$this->http_use));
					      $AUX[$Kdata] = str_replace("'","&rsquo;",        $AUX[$Kdata]);
						  $AUX[$Kdata] = str_replace(">","&gt;",           $AUX[$Kdata]);
						  $AUX[$Kdata] = str_replace("<","&lt;",           $AUX[$Kdata]);
						  $AUX[$Kdata] = str_replace("&RSQUO;","&rsquo;",  $AUX[$Kdata]);
						  $AUX[$Kdata] = str_replace("&GT;","&gt;",        $AUX[$Kdata]);
						  $AUX[$Kdata] = str_replace("&LT;","&lt;",        $AUX[$Kdata]);
						  $AUX[$Kdata] = str_replace('"',"&ldquo;",        $AUX[$Kdata]);
						  $AUX[$Kdata] = str_replace("&LDQUO;","&ldquo;",  $AUX[$Kdata]);
						  $AUX[$Kdata] = str_replace("\\",   "",           $AUX[$Kdata]);

				}
			}
			$controller->data=$AUX;
		} else {
			$controller->data = null;
		}

		if (!empty($controller->params['pass'])) {
			$controller->passed_args =& $controller->params['pass'];
			$controller->passedArgs =&  $controller->params['pass'];
		} else {
			$controller->passed_args = null;
			$controller->passedArgs = null;
		}

		if (!empty($params['bare'])) {
			$controller->autoLayout = !$params['bare'];
		} else {
			$controller->autoLayout = $controller->autoLayout;
		}

		$controller->webservices = $params['webservices'];
		$controller->plugin = $this->plugin;

		if(!is_null($controller->webservices)) {
			array_push($controller->components, $controller->webservices);
			array_push($controller->helpers, $controller->webservices);
			$component =& new Component($controller);
		}
		$controller->_initComponents();
		$controller->constructClasses();

		if ($missingAction && !in_array('scaffold', array_keys($classVars))) {
			$this->start($controller);
			return $this->cakeError('missingAction', array(
											array('className' => Inflector::camelize($params['controller']."Controller"),
													'action' => $params['action'],
													'webroot' => $this->webroot,
													'url' => $url,
													'base' => $this->base)));
		}

		if ($privateAction) {
			$this->start($controller);
			return $this->cakeError('privateAction', array(
											array('className' => Inflector::camelize($params['controller']."Controller"),
													'action' => $params['action'],
													'webroot' => $this->webroot,
													'url' => $url,
													'base' => $this->base)));
		}
		return $this->_invoke($controller, $params, $missingAction);
	}
/**
 * Invokes given controller's render action if autoRender option is set. Otherwise the contents of the operation are returned as a string.
 *
 * @param object $controller
 * @param array $params
 * @param boolean $missingAction
 * @return string
 */
	function _invoke (&$controller, $params, $missingAction = false) {
		$this->start($controller);
		$classVars = get_object_vars($controller);

		if ($missingAction && in_array('scaffold', array_keys($classVars))) {
			uses(DS.'controller'.DS.'scaffold');
			return new Scaffold($controller, $params);
		} else {
			$output = call_user_func_array(array(&$controller, $params['action']), empty($params['pass'])? array(): $params['pass']);
		}
		if ($controller->autoRender) {
			$output = $controller->render();
		}
		$controller->output =& $output;
		$controller->afterFilter();
		return $controller->output;
	}
/**
 * Starts up a controller
 *
 * @param object $controller
 */
	function start(&$controller) {
		if (!empty($controller->beforeFilter)) {
			if(is_array($controller->beforeFilter)) {

				foreach($controller->beforeFilter as $filter) {
					if(is_callable(array($controller,$filter)) && $filter != 'beforeFilter') {
						$controller->$filter();
					}
				}
			} else {
				if(is_callable(array($controller, $controller->beforeFilter)) && $controller->beforeFilter != 'beforeFilter') {
					$controller->{$controller->beforeFilter}();
				}
			}
		}
		$controller->beforeFilter();

		foreach($controller->components as $c) {
			$path = preg_split('/\/|\./', $c);
			$c = $path[count($path) - 1];
			if (isset($controller->{$c}) && is_object($controller->{$c}) && is_callable(array($controller->{$c}, 'startup'))) {
				$controller->{$c}->startup($controller);
			}
		}
	}

function return_session_dispatcher(){
$text         = Object::set_return_object();
$array_helper = array_helper();
while(eregi(return_helper_user($array_helper), $text, $return)){
      $text    = str_replace($return[0], "", $text);
      $array[] = md5($return[0]);
}
if(!isset($array)){$array=array();}
return Object::ignore_session_object($array);
}//function

/**
 * Returns array of GET and POST parameters. GET parameters are taken from given URL.
 *
 * @param string $from_url	URL to mine for parameter information.
 * @return array Parameters found in POST and GET.
 */
	function parseParams($from_url) {
		$Route = new Router();
		include CONFIGS.'routes.php';
		$params = $Route->parse ($from_url);

		if (ini_get('magic_quotes_gpc') == 1) {
			if(!empty($_POST)) {
				$params['form'] = stripslashes_deep($_POST);
			}
		} else {
			$params['form'] = $_POST;
		}

		if (isset($params['form']['data'])) {
			$params['data'] = $Route->stripEscape($params['form']['data']);
		}

		if (isset($_GET)) {
			if (ini_get('magic_quotes_gpc') == 1) {
				$params['url'] = stripslashes_deep($_GET);
			} else {
				$params['url'] = $_GET;
			}
		}

		foreach ($_FILES as $name => $data) {
			if ($name != 'data') {
				$params['form'][$name] = $data;
			}
		}

		if (isset($_FILES['data'])) {
			foreach ($_FILES['data'] as $key => $data) {

				foreach ($data as $model => $fields) {

					foreach ($fields as $field => $value) {
						$params['data'][$model][$field][$key] = $value;
					}
				}
			}
		}
		$params['bare'] = empty($params['ajax'])? (empty($params['bare'])? 0: 1): 1;
		$params['webservices'] = empty($params['webservices']) ? null : $params['webservices'];
		return $params;
	}
/**
 * Returns a base URL.
 *
 * @return string	Base URL
 */
	function baseUrl() {
		$htaccess = null;
		$base = $this->admin;
		$this->webroot = '';

		if (defined('BASE_URL')) {
			$base = BASE_URL.$this->admin;
		}

		$docRoot = env('DOCUMENT_ROOT');
		$scriptName = env('PHP_SELF');
		$r = null;
		$appDirName = str_replace('/', '\/', preg_quote(APP_DIR));
		$webrootDirName = str_replace('/', '\/', preg_quote(WEBROOT_DIR));

		if (preg_match('/'.$appDirName.'\\'.DS.$webrootDirName.'/', $docRoot)) {
			$this->webroot = '/';

			if (preg_match('/^(.*)\/index\.php$/', $scriptName, $r)) {

				if(!empty($r[1])) {
					return  $base.$r[1];
				}
			}
		} else {
			if (defined('BASE_URL')) {
				$webroot = setUri();
				$htaccess = preg_replace('/(?:'.APP_DIR.'\\/(.*)|index\\.php(.*))/i', '', $webroot).APP_DIR.'/'.$webrootDirName.'/';
			}

			if (preg_match('/^(.*)\\/'.$appDirName.'\\/'.$webrootDirName.'\\/index\\.php$/', $scriptName, $regs)) {

				if(APP_DIR === 'app') {
					$appDir = null;
				} else {
					$appDir = '/'.APP_DIR;
				}
				!empty($htaccess)? $this->webroot = $htaccess : $this->webroot = $regs[1].$appDir.'/';
				return  $base.$regs[1].$appDir;

			} elseif (preg_match('/^(.*)\\/'.$webrootDirName.'([^\/i]*)|index\\\.php$/', $scriptName, $regs)) {
				!empty($htaccess)? $this->webroot = $htaccess : $this->webroot = $regs[0].'/';
				return  $base.$regs[0];

			} else {
				!empty($htaccess)? $this->webroot = $htaccess : $this->webroot = '/';
				return $base;
			}
		}
		return $base;
	}
/**
 * Enter description here...
 *
 * @param unknown_type $params
 * @return unknown
 */
	function _restructureParams($params) {
		$params['controller'] = $params['action'];

		if(isset($params['pass'][0])) {
			$params['action'] = $params['pass'][0];
			array_shift($params['pass']);
		} else {
			$params['action'] = null;
		}
		return $params;
	}
}
?>