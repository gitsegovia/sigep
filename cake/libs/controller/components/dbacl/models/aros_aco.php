<?php
/* SVN FILE: $Id: aros_aco.php,v 1.1 2009-10-14 03:01:46 sigep Exp $ */
/**
 * Short description for file.
 *
 * Long description for file
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
 * @subpackage		cake.cake.libs.controller.components.dbacl.models
 * @since			CakePHP(tm) v 0.10.0.1232
 * @version			$Revision: 1.1 $
 * @modifiedby		$LastChangedBy: phpnut $
 * @lastmodified	$Date: 2009-10-14 03:01:46 $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * Short description for file.
 *
 * Long description for file
 *
 * @package		cake
 * @subpackage	cake.cake.libs.controller.components.dbacl.models
 */
class ArosAco extends AppModel {
/**
 * Cache Queries
 *
 * @var boolean
 */
	var $cacheQueries = false;
/**
 * Model name
 *
 * @var string
 */
	 var $name = 'ArosAco';
/**
 * Table this model uses
 *
 * @var string
 */
	 var $useTable = 'aros_acos';
/**
 * Belongs to association
 *
 * @var array
 */
	 var $belongsTo = array('Aro', 'Aco');
}
?>