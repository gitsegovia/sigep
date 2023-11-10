<?php
class SalirController extends AppController

{
    var $uses = array('Usuario');
    var $helpers = array('Html', 'Javascript', 'Ajax','Sisap');

	function index(){
		$this->layout  = "salir";
		$this->render("salida","salir");
		}

	function salida(){
		$this->layout="index_usuario";

	}


	function usuarios(){



	}

}
?>
