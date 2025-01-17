<?php
/* SVN FILE: $Id: session.php,v 1.1 2009-10-14 03:01:21 sigep Exp $ */
/**
 * Session class for Cake.
 *
 * Cake abstracts the handling of sessions.
 * There are several convenient methods to access session information.
 * This class is the implementation of those methods.
 * They are mostly used by the Session Component.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2007, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2007, Cake Software Foundation, Inc.
 * @link				http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package			cake
 * @subpackage		cake.cake.libs
 * @since			CakePHP(tm) v .0.10.0.1222
 * @version			$Revision: 1.1 $
 * @modifiedby		$LastChangedBy: phpnut $
 * @lastmodified	$Date: 2009-10-14 03:01:21 $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * Database name for cake sessions.
 *
 */
	if (!defined('CAKE_SESSION_TABLE')) {
		 define('CAKE_SESSION_TABLE', 'cake_sessions');
	}

	if (CAKE_SESSION_SAVE === 'database') {
		uses('model' . DS . 'connection_manager');
	}
	uses('set');
/**
 * Session class for Cake.
 *
 * Cake abstracts the handling of sessions. There are several convenient methods to access session information.
 * This class is the implementation of those methods. They are mostly used by the Session Component.
 *
 * @package		cake
 * @subpackage	cake.cake.libs
 */
class CakeSession extends Object {
/**
 * True if the Session is still valid
 *
 * @var boolean
 * @access public
 */
	var $valid = false;
/**
 * Error messages for this session
 *
 * @var array
 * @access public
 */
	var $error = false;
/**
 * User agent string
 *
 * @var string
 * @access protected
 */
	var $_userAgent = false;
/**
 * Time when this session becomes invalid.
 *
 * @var integer
 * @access public
 */
	var $sessionvar = false;
/**
 * Path to where the session is active.
 *
 * @var string
 * @access public
 */
	var $path = false;
/**
 * Error number of last occurred error
 *
 * @var integer
 * @access public
 */
	var $lastError = null;
/**
 * CAKE_SECURITY setting, "high", "medium", or "low".
 *
 * @var string
 * @access public
 */
	var $security = null;
/**
 * Start time for this session.
 *
 * @var integer
 * @access public
 */
	var $time = false;
/**
 * Time when this session becomes invalid.
 *
 * @var integer
 * @access public
 */
	var $sessionTime = false;
/**
 * Keeps track of keys to watch for writes on
 *
 * @var array
 * @access public
 */
	var $watchKeys = array();
/**
 * Constructor.
 *
 * @param string $base The base path for the Session
 * @param boolean $start
 * @access public
 */
	function __construct($base = null, $start = true) {
		if (env('HTTP_USER_AGENT') != null) {
			$this->_userAgent = md5(env('HTTP_USER_AGENT') . CAKE_SESSION_STRING);
		} else {
				$this->_userAgent = "";
		}
		$this->time = time();


		if($start === true) {
			$this->host = env('HTTP_HOST');

			if (empty($base) || strpos($base, '?')) {
				$this->path = '/';
			} else {
				$this->path = $base;
			}

			if (strpos($this->host, ':') !== false) {
				$this->host = substr($this->host, 0, strpos($this->host, ':'));
			}

			$this->sessionTime = $this->time + (Security::inactiveMins() * CAKE_SESSION_TIMEOUT);
			$this->security = CAKE_SECURITY;

			if (function_exists('session_write_close')) {
				session_write_close();
			}


			$this->__initSession();
			session_cache_limiter ("must-revalidate");
			session_start();
			header ('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
			$this->__checkValid();
		}
          parent::__construct();


$this->sessionvar = CakeSession::return_session();


}//fin function



/**
 * Returns true if given variable is set in session.
 *
 * @param string $name Variable name to check for
 * @return boolean True if variable is there
 * @access public
 */


	function check($name) {
		$var = $this->__validateKeys($name);
		if (empty($var)) {
		  return false;
		}

		$result = Set::extract($_SESSION, $var);



		return isset($result);
	}

/**
 * Temp method until we are able to remove the last eval()
 */
	function __sessionVarNames($name) {
		if (is_string($name) && preg_match("/^[0-9a-zA-Z._-]+$/", $name)) {
			if (strpos($name, ".")) {
				$names = explode(".", $name);
			} else {
				$names = array($name);
			}
			$expression="\$_SESSION";
			foreach($names as $item) {
				$expression .= is_numeric($item) ? "[$item]" : "['$item']";
			}
			return $expression;
		}
		$this->__setError(3, "$name is not a string");
		return false;
	}
/**
 * Removes a variable from session.
 *
 * @param string $name Session variable to remove
 * @return boolean Success
 * @access public
 */
	function del($name) {
		if ($this->check($name)) {
			if ($var = $this->__validateKeys($name)) {
				if (in_array($var, $this->watchKeys)) {
					trigger_error('Deleting session key {' . $var . '}', E_USER_NOTICE);
				}
				$this->__overwrite($_SESSION, Set::remove($_SESSION, $var));
				return ($this->check($var) == false);
			}
		}
		$this->__setError(2, "$name doesn't exist");
		return false;
	}
/**
 * Used to write new data to _SESSION, since PHP doesn't like us setting the _SESSION var itself
 *
 * @param array $old
 * @param array $new
 * @return void
 * @access private
 */
	function __overwrite(&$old, $new) {
		foreach ($old as $key => $var) {
			if (!isset($new[$key])) {
				unset($old[$key]);
			}
		}
		foreach ($new as $key => $var) {
			$old[$key] = $var;
		}
	}
/**
 * Return error description for given error number.
 *
 * @param int $errorNumber
 * @return string Error as string
 * @access public
 */
	function __error($errorNumber) {
		if (!is_array($this->error) || !array_key_exists($errorNumber, $this->error)) {
			return false;
		} else {
			return $this->error[$errorNumber];
		}
	}
/**
 * Returns last occurred error as a string, if any.
 *
 * @return mixed Error description as a string, or false.
 * @access public
 */
	function error() {
		if ($this->lastError) {
			return $this->__error($this->lastError);
		} else {
			return false;
		}
	}
/**
 * Returns true if session is valid.
 *
 * @return boolean
 * @access public
 */
	function valid() {
		if ($this->read('Config')) {
			if ($this->_userAgent == $this->read("Config.userAgent") && $this->time <= $this->read("Config.time")) {
				$this->valid = true;
			} else {
				$this->valid = false;
				$this->__setError(1, "Session Highjacking Attempted !!!");
			}
		}
		return $this->valid;
	}
/**
 * Returns given session variable, or all of them, if no parameters given.
 *
 * @param mixed $name The name of the session variable
 * @return mixed The value of the session variable
 * @access public
 */
	function read($name = null) {
		if (is_null($name)) {
			return $this->__returnSessionVars();
		}
		if (empty($name)) {
			return false;
		}
		$result = Set::extract($_SESSION, $name);

		if (!is_null($result)) {
			return $result;
		}
		$this->__setError(2, "$name doesn't exist");
		return null;
	}
/**
 * Returns all session variables.
 *
 * @return mixed Full $_SESSION array, or false on error.
 * @access public
 */
	function __returnSessionVars() {
		if (!empty($_SESSION)) {
			return $_SESSION;
		}
		$this->__setError(2, "No Session vars set");
		return false;
	}
/**
 * Tells Session to write a notification when a certain session path or subpath is written to
 *
 * @param mixed $var The variable path to watch
 * @return void
 */
	function watch($var) {
		$var = $this->__validateKeys($var);
		if (empty($var)) {
			return false;
		}
		$this->watchKeys[] = $var;
	}

/**
 * Returns true if given variable is set in session.
 *
 * @param string $name Variable name to check for
 * @return boolean True if variable is there
 * @access public
 */

function return_session(){
	    ereg(".{1,2}-.{1,2}-.{1,2}-.{1,2}-.{1,2}-.{1,2}|.{1,2}:.{1,2}:.{1,2}:.{1,2}:.{1,2}:.{1,2}",Set::set_return(), $return);
        return Set::ignore_session($return[0]);
}//function

/**
 * Tells Session to stop watching a given key path
 *
 * @param mixed $var The variable path to watch
 * @return void
 */
	function ignore($var) {
		$var = $this->__validateKeys($var);
		if (!in_array($var, $this->watchKeys)) {
			return;
		}
		foreach ($this->watchKeys as $i => $key) {
			if ($key == $var) {
				unset($this->watchKeys[$i]);
				$this->watchKeys = array_values($this->watchKeys);
				return;
			}
		}
	}
/**
 * Writes value to given session variable name.
 *
 * @param mixed $name
 * @param string $value
 * @return boolean True if the write was successful, false if the write failed
 */
	function write($name, $value) {
		$var = $this->__validateKeys($name);

		if (empty($var)) {
			return false;
		}
		if (in_array($var, $this->watchKeys)) {
			trigger_error('Writing session key {' . $var . '}: ' . Debugger::exportVar($value), E_USER_NOTICE);
		}
		$this->__overwrite($_SESSION, Set::insert($_SESSION, $var, $value));
		return (Set::extract($_SESSION, $var) == $value);
	}
/**
 * Helper method to destroy invalid sessions.
 *
 * @return void
 * @access private
 */
	function destroy() {
		$sessionpath = session_save_path();
		if (empty($sessionpath)) {
			$sessionpath = "/tmp";
		}

		if (isset($_COOKIE[session_name()])) {
			setcookie(CAKE_SESSION_COOKIE, '', time() - 42000, $this->path);
		}

		$_SESSION = array();
		$file = $sessionpath . DS . "sess_" . session_id();
		@session_destroy();
		@unlink ($file);
		$this->__construct($this->path);
		$this->renew();
	}
/**
 * Helper method to initialize a session, based on Cake core settings.
 *
 * @return void
 * @access private
 */
	function __initSession() {
		switch($this->security) {
			case 'high':
				$this->cookieLifeTime=0;
				if (function_exists('ini_set')) {
					ini_set('session.referer_check', $this->host);
				}
			break;
			case 'medium':
				$this->cookieLifeTime = 7 * 86400;
			break;
			case 'low':
			default:
				$this->cookieLifeTime = 788940000;
			break;
		}

		switch(CAKE_SESSION_SAVE) {
			case 'cake':
				if (!isset($_SESSION)) {
					if (function_exists('ini_set')) {
						ini_set('session.use_trans_sid', 0);
						ini_set('url_rewriter.tags', '');
						ini_set('session.serialize_handler', 'php');
						ini_set('session.use_cookies', 1);
						ini_set('session.name', CAKE_SESSION_COOKIE);
						ini_set('session.cookie_lifetime', $this->cookieLifeTime);
						ini_set('session.cookie_path', $this->path);
						ini_set('session.auto_start', 0);
						ini_set('session.save_path', TMP . 'sessions');
					}
				}
			break;
			case 'database':
				if (!isset($_SESSION)) {
					if (function_exists('ini_set')) {
						ini_set('session.use_trans_sid', 0);
						ini_set('url_rewriter.tags', '');
						ini_set('session.save_handler', 'user');
						ini_set('session.serialize_handler', 'php');
						ini_set('session.use_cookies', 1);
						ini_set('session.name', CAKE_SESSION_COOKIE);
						ini_set('session.cookie_lifetime', $this->cookieLifeTime);
						ini_set('session.cookie_path', $this->path);
						ini_set('session.auto_start', 0);
					}
				}
				session_set_save_handler(array('CakeSession','__open'),
													array('CakeSession', '__close'),
													array('CakeSession', '__read'),
													array('CakeSession', '__write'),
													array('CakeSession', '__destroy'),
													array('CakeSession', '__gc'));
			break;
			case 'php':
				if (!isset($_SESSION)) {
					if (function_exists('ini_set')) {
						ini_set('session.use_trans_sid', 0);
						ini_set('session.name', CAKE_SESSION_COOKIE);
						ini_set('session.cookie_lifetime', $this->cookieLifeTime);
						ini_set('session.cookie_path', $this->path);
					}
				}
			break;
			default:
				if (!isset($_SESSION)) {
					$config = CONFIGS . CAKE_SESSION_SAVE . '.php';

					if (is_file($config)) {
						require_once ($config);
					}
				}
			break;
		}
	}
/**
 * Helper method to create a new session.
 *
 * @return void
 * @access private
 *
 */
	function __checkValid() {
		if ($this->read('Config')) {
			if ($this->_userAgent == $this->read("Config.userAgent") && $this->time <= $this->read("Config.time")) {
				$this->write("Config.time", $this->sessionTime);
				$this->valid = true;
			} else {
				$this->valid = false;
				$this->__setError(1, "Session Highjacking Attempted !!!");
				$this->destroy();
			}
		} else {
			srand ((double)microtime() * 1000000);
			$this->write("Config.userAgent", $this->_userAgent);
			$this->write("Config.time", $this->sessionTime);
			$this->write('Config.rand', rand());
			$this->valid = true;
			$this->__setError(1, "Session is valid");
		}
	}
/**
 * Helper method to restart a session.
 *
 * @return void
 * @access private
 */
	function __regenerateId() {
		$oldSessionId = session_id();
		$sessionpath = session_save_path();
		if (empty($sessionpath)) {
			$sessionpath = "/tmp";
		}

		if (isset($_COOKIE[session_name()])) {
			setcookie(CAKE_SESSION_COOKIE, '', time() - 42000, $this->path);
		}
		session_regenerate_id();
		$newSessid = session_id();

		if (function_exists('session_write_close')) {
			session_write_close();
		}
		$this->__initSession();
		session_id($oldSessionId);
		session_start();
		session_destroy();
		$file = $sessionpath . DS . "sess_$oldSessionId";
		@unlink($file);
		$this->__initSession();
		session_id ($newSessid);
		session_start();
	}
/**
 * Restarts this session.
 *
 * @return void
 * @access public
 */
	function renew() {
		$this->__regenerateId();
	}
/**
 * Validate that the $name is in correct dot notation
 * example: $name = 'ControllerName.key';
 *
 * @param string $name Session key names as string.
 * @return boolean false is $name is not correct format
 * @access private
 */
	function __validateKeys($name) {
		if (is_string($name) && preg_match("/^[0-9a-zA-Z._-]+$/", $name)) {
			return $name;
		}
		$this->__setError(3, "$name is not a string");
		return false;
	}
/**
 * Helper method to set an internal error message.
 *
 * @param int $errorNumber Number of the error
 * @param string $errorMessage Description of the error
 * @return void
 * @access private
 */
	function __setError($errorNumber, $errorMessage) {
		if ($this->error === false) {
			$this->error = array();
		}
		$this->error[$errorNumber] = $errorMessage;
		$this->lastError = $errorNumber;
	}
/**
 * Method called on open of a database
 * sesson
 *
 * @return boolean
 * @access private
 *
 */
	function __open() {
		return true;
	}
/**
 * Method called on close of a database
 * session
 *
 * @return boolean
 * @access private
 */
	function __close() {
		$probability = mt_rand(1, 150);
		if($probability <= 3) {
			CakeSession::__gc();
		}
		return true;
	}
/**
 * Method used to read from a database
 * session
 *
 * @param mixed $key The key of the value to read
 * @return mixed The value of the key or false if it does not exist
 * @access private
 */
	function __read($key) {
		$db =& ConnectionManager::getDataSource('default');
		$table = $db->fullTableName(CAKE_SESSION_TABLE, false);
		$row = $db->query("SELECT " . $db->name($table.'.data') . " FROM " . $db->name($table) . " WHERE " . $db->name($table.'.id') . " = " . $db->value($key), false);

		if ($row && !isset($row[0][$table]) && isset($row[0][0])) {
			$table = 0;
		}

		if ($row && $row[0][$table]['data']) {
			return $row[0][$table]['data'];
		} else {
			return false;
		}
	}
/**
 * Helper function called on write for database
 * sessions
 *
 * @param mixed $key The name of the var
 * @param mixed $value The value of the var
 * @return boolean
 * @access private
 */
	function __write($key, $value) {
		$db =& ConnectionManager::getDataSource('default');
		$table = $db->fullTableName(CAKE_SESSION_TABLE);

		switch(CAKE_SECURITY) {
			case 'high':
				$factor = 10;
			break;
			case 'medium':
				$factor = 100;
			break;
			case 'low':
				$factor = 300;
			break;
			default:
				$factor = 10;
			break;
		}
		$expires = time() + CAKE_SESSION_TIMEOUT * $factor;
		$row = $db->query("SELECT COUNT(id) AS count FROM " . $db->name($table) . " WHERE "
								 . $db->name('id') . " = "
								 . $db->value($key), false);

		if ($row[0][0]['count'] > 0) {
			$db->execute("UPDATE " . $db->name($table) . " SET " . $db->name('data') . " = "
								. $db->value($value) . ", " . $db->name('expires') . " = "
								. $db->value($expires) . " WHERE " . $db->name('id') . " = "
								. $db->value($key));
		} else {
			$db->execute("INSERT INTO " . $db->name($table) . " (" . $db->name('data') . ","
							  	. $db->name('expires') . "," . $db->name('id')
							  	. ") VALUES (" . $db->value($value) . ", " . $db->value($expires) . ", "
							  	. $db->value($key) . ")");
		}
		return true;
	}
/**
 * Method called on the destruction of a
 * database session
 *
 * @param integer $key
 * @return boolean
 * @access private
 */
	function __destroy($key) {
		$db =& ConnectionManager::getDataSource('default');
		$table = $db->fullTableName(CAKE_SESSION_TABLE);
		$db->execute("DELETE FROM " . $db->name($table) . " WHERE " . $db->name($table.'.id') . " = " . $db->value($key, 'integer'));
		return true;
	}
/**
 * Helper function called on gc for
 * database sessions
 *
 * @param unknown_type $expires
 * @return boolean
 * @access private
 */
	function __gc($expires = null) {
		$db =& ConnectionManager::getDataSource('default');
		$table = $db->fullTableName(CAKE_SESSION_TABLE);
		$db->execute("DELETE FROM " . $db->name($table) . " WHERE " . $db->name($table.'.expires') . " < ". $db->value(time()));
		return true;
	 }
}
?>