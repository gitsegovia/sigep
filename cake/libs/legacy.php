<?php
/* SVN FILE: $Id: legacy.php,v 1.1 2009-10-14 03:01:22 sigep Exp $ */
/**
 * Backwards compatibility functions.
 *
 * With this hack you can use clone() in PHP4 code
 * use "clone($object)" not "clone $object"! the former works in both PHP4 and PHP5
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
 * @since			CakePHP(tm) v 0.2.9
 * @version			$Revision: 1.1 $
 * @modifiedby		$LastChangedBy: phpnut $
 * @lastmodified	$Date: 2009-10-14 03:01:22 $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
	if (version_compare(phpversion(), '5.0') < 0) {
		if (!function_exists("clone")) {
			eval ('
			function clone($object) {
			return $object;
			}');
		}
	}
/**
 * Replace file_get_contents()
 *
 * @internal	resource_context is not supported
 * @since		PHP 5
 * require PHP 4.0.0 (user_error)
 *
 * @param unknown_type $filename
 * @param unknown_type $incpath
 * @return unknown
 */
	if (!function_exists('file_get_contents')) {
		function file_get_contents($filename, $incpath = false) {
			if (false === $fh = fopen($filename, 'rb', $incpath)) {
				user_error('file_get_contents() failed to open stream: No such file or directory', E_USER_WARNING);
				return false;
			}
			clearstatcache();

			if ($fsize = @filesize($filename)) {
				$data = fread($fh, $fsize);
			} else {
				$data='';

				while(!feof($fh)) {
					$data .= fread($fh, 8192);
				}
			}
			fclose ($fh);
			return $data;
		}
}
?>