<?php
/* SVN FILE: $Id: autocomplete.php,v 1.2 2007-11-02 22:15:33 sigep Exp $ */

/**
 * Automagically handles requests for autocomplete fields
 *
 * CakePHP :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright (c)    2006, Cake Software Foundation, Inc.
 *                                1785 E. Sahara Avenue, Suite 490-204
 *                                Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright        Copyright (c) 2006, Cake Software Foundation, Inc.
 * @link                http://www.cakefoundation.org/projects/info/cakephp CakePHP Project
 * @package            cake
 * @subpackage        cake.cake.libs.controller.components
 * @since            CakePHP v 0.10.4.1076
 * @version            $Revision: 1.2 $
 * @modifiedby        $LastChangedBy: nate $
 * @lastmodified    $Date: 2007-11-02 22:15:33 $
 * @license            http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Autocomplete Handler
 *
 * @package        cake
 * @subpackage    cake.cake.libs.controller.components
 *
 */
class AutocompleteComponent extends Object {

    var $layout = 'ajax';

    var $enabled = true;

    var $components = array('RequestHandler');

    var $handles = array();

/**
 * Startup
 *
 * @param object A reference to the controller
 * @return null
 */
    function startup(&$controller) {

        if (!$this->enabled || !$this->RequestHandler->isAjax() || !$this->RequestHandler->isPost()) {
            return true;
        }

        $data = $controller->data;
        if (empty($data) || count($data) != 1) {
            return false;
        }

        list($model) = array_keys($data);
        if (!is_array($data[$model]) || count($data[$model]) != 1 || !is_object($controller->{$model})) {
            return false;
        }

        list($field) = array_keys($data[$model]);
        $conditions = array();

        if (!empty($this->handles)) {

            $handled = false;
            $fields = array();

            foreach ($this->handles as $key => $val) {
                if (is_int($key)) {
                    $key = $val;
                    $val = array();
                }
                if ($key == $model.'.'.$field || $key == $field || $key == $model.'.*') {
                    $handled = true;
                    $conditions = $val;
                    break;
                }
            }
            if (!$handled) {
                return true;
            }
        }

        $base = array($model.'.'.$field => 'LIKE %'.$data[$model][$field].'%');
        if (!empty($conditions)) {
            $conditions = array($base, $conditions);
        } else {
            $conditions = $base;
        }

        $results = $controller->{$model}->findAll($conditions);

        if (is_array($results) && !empty($results)) {
            e("<ul>\n");
            foreach ($results as $rec) {
                if (isset($rec[$model][$field])) {
                    e("\t<li>".$rec[$model]['cod_sector']." - ".$rec[$model][$field]."</li>\n");
                }
            }
            e("</ul>\n");
        }
        exit();
    }
}
?>