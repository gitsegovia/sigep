<?php
/*
 * Creado el 07/02/2008 a las 04:03:45 PM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */
 class Cugp04EntradaModuloConectadosController extends AppController{
 	var $name="cugp04_entrada_modulo_conectados";
 	var $uses=array('cugd04_entrada_modulo_conectados');
 	var $helpers=array('Html','Ajax','Javascript','Sisap');

function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					//$this->set('userTable', $this->requestAction('/cnmp03partidas/', array('return')));
					//echo "H".$this->requestAction('/usuarios/actualizar_user',array('return'));
					$this->requestAction('/usuarios/actualizar_user');
				}
}//fin checksession


 function verifica_SS($i){
    	/**
    	 * Funcion que permite leer las varibles de session. la cual permite capturar los codigos del usuario
    	 * para ser insertados en todas las tablas.
    	 * */
    	switch ($i){
    		case 1:return $this->Session->read('SScodpresi');break;
    		case 2:return $this->Session->read('SScodentidad');break;
    		case 3:return $this->Session->read('SScodtipoinst');break;
    		case 4:return $this->Session->read('SScodinst');break;
    		case 5:return $this->Session->read('SScoddep');break;
    		case 6:return $this->Session->read('entidad_federal');break;
    		default:
    		   return "NULO";

    	}//fin switch
 }//fin verifica_SS


 function beforeFilter(){
 	$this->checkSession();
 	 echo'<script>
							 document.getElementById("valida_codigo").innerHTML = "";
							 document.getElementById("valida_codigo").style.display = "none";
							 if(document.getElementById("registro")){document.getElementById("registro").style.visibility = "hidden";}
                             if(document.getElementById("nomina")){document.getElementById("nomina").style.visibility = "hidden";}
                             if(document.getElementById("dependencia")){document.getElementById("dependencia").style.visibility = "hidden";}
                             if(document.getElementById("sistema_general")){document.getElementById("sistema_general").style.visibility = "hidden";}
                          </script>';
 }


 function index(){
 	$this->layout ="ajax";
 	$this->set('entidad_federal', $this->Session->read('entidad_federal'));

	//$num=$this->cugd05_restriccion_tipo->findCount();
	//$tipo_restric = $this->cugd05_restriccion_tipo->generateList(null,'cod_tipo ASC', null, '{n}.cugd05_restriccion_tipo.cod_tipo', '{n}.cugd05_restriccion_tipo.denominacion');
	//$tipo_restric = $tipo_restric != null ? $tipo_restric : array();
	//$this->concatena($tipo_restric, 'tipo');
	//$this->data["cugp05_restriccion_tipo"]=null;
 	//$this->set('enable', 'disabled');
 	//$this->set('num',$num);

 	$datos=$this->cugd04_entrada_modulo_conectados->findAll(null,null,'cod_dep ASC');
 	$this->set('datos',$datos);
 }


 }//fin clase
?>
