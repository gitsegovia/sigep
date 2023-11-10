<?php


class ccnp01ConcejoComunalesEntradaController extends AppController
{
    var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap');
    var $uses = array('ccnd00');


function checkSession(){
				if (!$this->Session->check('concejo_comunal')){
						$this->redirect('/salir');
						exit();
				}
}//fin checksession





function beforeFilter(){
     $this->checkSession();
}




function index(){
    	$this->layout = "ccnp00";
}//fin



function vacio(){
    	$this->layout = "ajax";
    	echo"<script>menu_activo();</script>";
}//fin





}//fin en construcion
?>
