<?php
class AdminController extends AppController
{
    var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap');
    var $uses = array('cnmd04_tipo','cnmd04_ocupacion','cugd01_estados',
                      'cugd01_municipios', 'cugd01_parroquias', 'cugd01_centropoblados',
                      'cugd01_vialidad', 'cugd01_vereda', 'cugd02_institucion', 'cugd02_dependencia',
                      'cugd02_direccionsuperior', 'cugd02_coordinacion', 'cugd02_secretaria',
                      'cugd02_direccion', 'cugd02_division', 'cugd02_departamento', 'cugd02_oficina',
                      'cfpd01_grupo', 'cfpd01_partida', 'cfpd01_generica', 'cfpd01_especifica',
                      'cfpd01_sub_espec', 'cfpd01_auxiliar', 'cfpd01_ano_grupo', 'cfpd01_ano_partida',
                      'cfpd01_ano_generica', 'cfpd01_ano_especifica', 'cfpd01_ano_sub_espec',
                      'cfpd01_ano_auxiliar', 'cfpd01_grupo', 'cfpd01_partida', 'cfpd01_generica',
                      'cfpd01_especifica', 'cfpd01_sub_espec', 'cfpd01_auxiliar', 'Cnmd02_obreros_ramos', 'Cnmd02_obreros_grupos','Cnmd02_obreros_series', 'Cnmd02_obreros_puestos',
                      'Cnmd02_empleados_ramos', 'Cnmd02_empleados_grupos','Cnmd02_empleados_series', 'Cnmd02_empleados_puestos', 'Usuario','arrd05', 'cfpd07', 'Cnmd01', 'cnmd02_varios_puestos','usuario_panel');
	//var $layout =  "administradors";



    function checkSession(){
        if (!$this->Session->check('Admin')){
            $this->redirect('/salir/');
            exit();
        }
    }

    function beforeFilter(){
     $this->checkSession();
    }

	function vacio(){
		$this->layout = "ajax";

	}

	function index(){
	    	$this->layout = "admin";
	    	$this->checkSession();
	}



}//finclass
?>
