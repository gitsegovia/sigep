<?php
/* SVN FILE: $Id: sanitize.php,v 1.1 2009-10-14 03:01:21 sigep Exp $ */
/**
 * Washes strings from unwanted noise.
 *
 * Helpful methods to make unsafe strings usable.
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
 * @since			CakePHP(tm) v 0.10.0.1076
 * @version			$Revision: 1.1 $
 * @modifiedby		$LastChangedBy: phpnut $
 * @lastmodified	$Date: 2009-10-14 03:01:21 $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * Data Sanitization.
 *
 * Removal of alpahnumeric characters, SQL-safe slash-added strings, HTML-friendly strings,
 * and all of the above on arrays.
 *
 * @package		cake
 * @subpackage	cake.cake.libs
 */
class Sanitize{
/**
 * Removes any non-alphanumeric characters.
 *
 * @param string $string
 * @return string
 * @access public
 */
	function paranoid($string, $allowed = array()) {
		$allow = null;
		if (!empty($allowed)) {
			foreach($allowed as $value) {
				$allow .= "\\$value";
			}
		}

		if (is_array($string)) {
			foreach($string as $key => $clean) {
				$cleaned[$key] = preg_replace("/[^{$allow}a-zA-Z0-9]/", "", $clean);
			}
		} else {
			$cleaned = preg_replace("/[^{$allow}a-zA-Z0-9]/", "", $string);
		}
		return $cleaned;
	}
/**
 * Makes a string SQL-safe by adding slashes (if needed).
 *
 * @param string $string
 * @return string
 * @access public
 */
	function sql($string) {
		if (!ini_get('magic_quotes_gpc')) {
			$string = addslashes($string);
		}
		return $string;
	}
/**
 * Returns given string safe for display as HTML. Renders entities.
 *
 * @param string $string
 * @param boolean $remove If true, the string is stripped of all HTML tags
 * @return string
 * @access public
 */
	function html($string, $remove = false) {
		if ($remove) {
			$string = strip_tags($string);
		} else {
			$patterns = array("/\&/", "/%/", "/</", "/>/", '/"/', "/'/", "/\(/", "/\)/", "/\+/", "/-/");
			$replacements = array("&amp;", "&#37;", "&lt;", "&gt;", "&quot;", "&#39;", "&#40;", "&#41;", "&#43;", "&#45;");
			$string = preg_replace($patterns, $replacements, $string);
		}
		return $string;
	}
/**
 * Recursively sanitizes given array of data for safe input.
 *
 * @param mixed $toClean
 * @return mixed
 * @access public
 */
	function cleanArray(&$toClean) {
		return $this->cleanArrayR($toClean);
	}
/**
 * Method used for recursively sanitizing arrays of data
 * for safe input
 *
 * @param array $toClean
 * @return array The clean array
 * @access public
 */
	function cleanArrayR(&$toClean) {
		if (is_array($toClean)) {
			while(list($k, $v) = each($toClean)) {
				if (is_array($toClean[$k])) {
					$this->cleanArray($toClean[$k]);
				} else {
					$toClean[$k] = $this->cleanValue($v);
				}
			}
		} else {
			return null;
		}
	}
/**
 * Do we really need to sanitize array keys? If so, we can use this code...
	function cleanKey($key) {
		if ($key == "")
		{
			return "";
		}
		//URL decode and convert chars to HTML entities
		$key = htmlspecialchars(urldecode($key));
		//Remove ..
		$key = preg_replace( "/\.\./", "", $key );
		//Remove __FILE__, etc.
		$key = preg_replace( "/\_\_(.+?)\_\_/", "", $key );
		//Trim word chars, '.', '-', '_'
		$key = preg_replace( "/^([\w\.\-\_]+)$/", "$1", $key );
		return $key;
	}
 */

/**
 * Method used by cleanArray() to sanitize array nodes.
 *
 * @param string $val
 * @return string
 * @access public
 */
	function cleanValue($val) {
		if ($val == "") {
			return "";
		}
		//Replace odd spaces with safe ones
		$val = str_replace(" ", " ", $val);
		$val = str_replace(chr(0xCA), "", $val);
		//Encode any HTML to entities.
		$val = $this->html($val);
		//Double-check special chars and replace carriage returns with new lines
		$val = preg_replace("/\\\$/", "$", $val);
		$val = preg_replace("/\r\n/", "\n", $val);
		$val = str_replace("!", "!", $val);
		$val = str_replace("'", "'", $val);
		//Allow unicode (?)
		$val = preg_replace("/&amp;#([0-9]+);/s", "&#\\1;", $val);
		//Add slashes for SQL
		$val = $this->sql($val);
		//Swap user-inputted backslashes (?)
		$val = preg_replace("/\\\(?!&amp;#|\?#)/", "\\", $val);
		return $val;
	}

/**
 * Formats column data from definition in DBO's $columns array
 *
 * @param Model $model The model containing the data to be formatted
 * @return void
 * @access public
 */
	function formatColumns(&$model) {
		foreach($model->data as $name => $values) {
			if ($name == $model->name) {
				$curModel =& $model;
			} elseif (isset($model->{$name}) && is_object($model->{$name}) && is_subclass_of($model->{$name}, 'Model')) {
				$curModel =& $model->{$name};
			} else {
				$curModel = null;
			}

			if ($curModel != null) {
				foreach($values as $column => $data) {
					$colType = $curModel->getColumnType($column);

					if ($colType != null) {
						$db =& ConnectionManager::getDataSource($curModel->useDbConfig);
						$colData = $db->columns[$colType];

						if (isset($colData['limit']) && strlen(strval($data)) > $colData['limit']) {
							$data = substr(strval($data), 0, $colData['limit']);
						}

						if (isset($colData['formatter']) || isset($colData['format'])) {

							switch(strtolower($colData['formatter'])) {
								case 'date':
									$data = date($colData['format'], strtotime($data));
								break;
								case 'sprintf':
									$data = sprintf($colData['format'], $data);
								break;
								case 'intval':
									$data = intval($data);
								break;
								case 'floatval':
									$data = floatval($data);
								break;
							}
						}
						$model->data[$name][$column]=$data;
						/*
						switch($colType) {
							case 'integer':
							case 'int':
								return  $data;
							break;
							case 'string':
							case 'text':
							case 'binary':
							case 'date':
							case 'time':
							case 'datetime':
							case 'timestamp':
							case 'date':
								return "'" . $data . "'";
							break;
						}
						*/
					}
				}
			}
		}
	}
}
?>