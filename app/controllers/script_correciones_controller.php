<?php

class ScriptCorrecionesController extends AppController {

    var $name    = "script_correciones";
    var $uses    = array('cugd04', 'Usuario', 'ccfd04_cierre_mes', 'v_restaurar_causados_op', 'v_restaurar_compromisos', 'v_restaurar_pagados','a_control_panel');
    var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap');
    var $layout  = "script_correciones";







function index(){


 $this->Session->delete('sesion_cerrada');

}//fin functio




function entrar ($var=null) {


   if(isset($_SESSION["sesion_cerrada"])){$sesion_cerrada = $_SESSION["sesion_cerrada"]; }else{ $sesion_cerrada = true;}

    if(isset($this->params['form']['login']) && isset($this->params['form']['password']) && $sesion_cerrada==true){
    	$u=strtoupper($this->params['form']['login']);
    	$p=strtoupper($this->params['form']['password']);
        if(defined('CLAVE_SISAP2')){
              if(CLAVE_SISAP2==md5($p) && $u=='ADMIN'){

				              	    $someone = $this->Usuario->findByUsername(strtoupper("ADMIN"));
				              	    $_SESSION["Usuario"]=$someone['Usuario'];
					                $this->Session->write('Usuario', $someone['Usuario']);
					                $this->Session->write('nom_usuario', $someone['Usuario']['username']);
					                $data_control_pane = $this->a_control_panel->findAll(CODIGOSCONDICION);

					                extract($data_control_pane[0]['a_control_panel']);
					                $this->Session->write('SScodpresi', $cod_presi);
					                $this->Session->write('SScodentidad', $cod_entidad);
					                $this->Session->write('SScodtipoinst', $cod_tipo_inst);
					                $this->Session->write('SScodinst', $cod_inst);
					                $this->Session->write('SScoddep', 1);

									$codigos_panel = $cod_presi.", ".$cod_entidad.", ".$cod_tipo_inst.", ".$cod_inst." ";
									$codigos_panel_filtro = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." ";
									if(!defined('CODIGOSINSTREACTUALIZAR')){
										define('CODIGOSINSTREACTUALIZAR',$codigos_panel);
									}

									if(!defined('CODINSTREACTUALIZARFILTRO')){
										define('CODINSTREACTUALIZARFILTRO',$codigos_panel_filtro);
									}

                                    $this->Session->write('CONTABILIDAD_FISCAL', $contabilidad);
                                    $this->Session->write('YEAR_REACTUALIZACION', $ano_reactualizacion);

					                $this->Session->write('ano_ejecucion', $ano_reactualizacion);
					                $ano_ejecucion=$ano_reactualizacion;



							if(!defined('CONTABILIDAD_FISCAL')){
                                    define('CONTABILIDAD_FISCAL',$contabilidad);
							}if(!defined('YEAR_REACTUALIZACION')){
                                    define('YEAR_REACTUALIZACION',$ano_reactualizacion);
							}



	                if($ano_ejecucion==null){
	                	$this->set('ESTOY_LOGUEADO',false);

	                }else{
	                	$_SESSION["sesion_cerrada"] = false;
              	        $this->Session->write('Root_session', "VISION_INTEGRAL");
	                	$this->set('ESTOY_LOGUEADO',true);
	                	$this->redirect('/script_correciones_panel/');
	                }


              }else{
              	  $this->set('ESTOY_LOGUEADO',false);

              }

        }else{
              	  $this->set('ESTOY_LOGUEADO',false);

              }
    }else{
          $this->set('ESTOY_LOGUEADO',false);
         }
    $this->render("entrar");

}//entrar




function salir(){

$this->layout="ajax";

		 $this->Session->delete('Root_session');
		 $this->Session->delete('sesion_cerrada');
		 $this->Session->delete('Usuario');
         $this->Session->delete('nom_usuario');
         $this->Session->delete('SScodpresi');
    	 $this->Session->delete('SScodentidad');
    	 $this->Session->delete('SScodtipoinst');
    	 $this->Session->delete('SScodinst');
    	 $this->Session->delete('SScoddep');
    	 $this->Session->delete('Modulo');

}//fin function



function salida_panel(){

$this->layout="script_correciones";


}//fin function



}//fin class

?>