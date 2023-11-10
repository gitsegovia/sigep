<?php
/*
 * Created on 02/02/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class Affiliate extends AppModel{
        var $useDbConfig   = 'test_otra_conex';///conexion creada en databases.php
        var $useTable      = 'tabla_x';
        var $primaryKey    = 'clavepk_x';
        var $name          = 'nombre_modelo' ;

}

/**y dentro de otro controlador
public $uses = array('Affiliate')
y dentro de un metodo cualquiera pongo esto

$data = $this->nombre_modelo->find("all",array('conditions' => array('nombre_modelo.campo_x' => 'xxxxxx'),
                                               'fields' => array('nombre_modelo.campo_x')));
*/
?>
