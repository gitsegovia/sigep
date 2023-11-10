<?php

class shp002CobranzaEstadoCuentaController extends AppController{

    var $name    = "shp002_cobranza_estado_cuenta";
    var $uses    = array('v_relacion_coradores', 'shd002_cobradores', 'v_shd002_cobranza_realizada',
                         'shd002_cobranza_pendiente', "v_shd002_cobranza_pendiente", 'shd002_cobranza_realizada',
                         'v_shp002_cobranza_estado_cuenta_1', "v_shp002_cobranza_estado_cuenta_2");


    var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap');


function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
}//fin checksession



function beforeFilter(){$this->checkSession();}






function ventana_cobradores_3($var1=null, $pagina=null, $pista=null){
$this->layout="ajax";
$cod_presi                =       $this->Session->read('SScodpresi');
$cod_entidad              =       $this->Session->read('SScodentidad');
$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
$cod_inst                 =       $this->Session->read('SScodinst');
$cod_dep                  =       $this->Session->read('SScoddep');
       if($var1==1){
        $this->set("datos",'');
 }else if($var1==2){


    	            if(isset($pagina)){$pagina=$pagina;}else{$pagina=1;}
					if($pista!=null){
						  $this->Session->write('pista_buscar_cobranza_pendiente_hacienda', $pista);
					}else{
					      $pista = $this->Session->read('pista_buscar_cobranza_pendiente_hacienda');
					}//fin else
					$condicion = $this->condicion()." and (".$this->busca_separado(array("rif_ci","nombre_razon"), $pista).")  ";
		            $Tfilas=$this->v_shp002_cobranza_estado_cuenta_2->findCount($condicion." GROUP BY cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_ci, personalidad, nombre_razon", "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_ci, personalidad, nombre_razon");
				        if($Tfilas!=0){
				        	$Tfilas=(int)ceil($Tfilas/50);
				        	$this->set('total_paginas',$Tfilas);
							$this->set('pagina_actual',$pagina);
							$this->set('pag_cant',$pagina.'/'.$Tfilas);
							$this->set('ultimo',$Tfilas);
				     	    $datos_filas=$this->v_shp002_cobranza_estado_cuenta_2->findAll($condicion." GROUP BY cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_ci, personalidad, nombre_razon", "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_ci, personalidad, nombre_razon","rif_ci ASC",50,$pagina,null);
					        $this->set("datos",$datos_filas);
					        $this->set('siguiente',$pagina+1);
							$this->set('anterior',$pagina-1);
							$this->bt_nav($Tfilas,$pagina);
				        }else{
				        	$this->set("datos",'');
				        }
					$this->set("pista",$pista);
 }//fin else
$this->set("opcion",$var1);
}//fin function



function consulta($rif_cedula=null, $pagina=null){
    $this->layout="ajax";
    if(isset($pagina)){ $pagina=$pagina; }else{ $pagina=1; }
	$condicion       = $this->condicion();
	if($rif_cedula!=null){$condicion .=" and rif_ci='".$rif_cedula."'  ";}


						$Tfilas=$this->v_shp002_cobranza_estado_cuenta_2->findCount($condicion);
						    if($Tfilas!=0){
						    	$Tfilas=(int)ceil($Tfilas/1);
						    	$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('ultimo',$Tfilas);
						 	    $datos_filas=$this->v_shp002_cobranza_estado_cuenta_2->findAll($condicion,null,"rif_ci, ano_cobranza ASC",1,$pagina,null);
						        $this->set("datos",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
						    }else{
						    	$this->set("datos",'');
						    }
					      $condicion_actividad_1 = $datos_filas[0]["v_shp002_cobranza_estado_cuenta_2"]["condicion_actividad"];

					            if($condicion_actividad_1==1){$condicion_actividad="Activo";
					      }else if($condicion_actividad_1==2){$condicion_actividad="Retirado";}

					      $this->set("personalidad", $datos_filas[0]["v_shp002_cobranza_estado_cuenta_2"]["personalidad"]);
						  $this->set("nombre_razon", $datos_filas[0]["v_shp002_cobranza_estado_cuenta_2"]["nombre_razon"]);
						  $this->set("fecha_ingreso", $datos_filas[0]["v_shp002_cobranza_estado_cuenta_2"]["fecha_ingreso"]);
						  $this->set("recurso_cobro", $datos_filas[0]["v_shp002_cobranza_estado_cuenta_2"]["recurso_cobro"]);
						  $this->set("condicion_actividad", $condicion_actividad);
						  $this->set("ano_cobranza", $datos_filas[0]["v_shp002_cobranza_estado_cuenta_2"]["ano_cobranza"]);
						  $this->set("rif_ci",       $datos_filas[0]["v_shp002_cobranza_estado_cuenta_2"]["rif_ci"]);

						  $ano_cobranza = $datos_filas[0]["v_shp002_cobranza_estado_cuenta_2"]["ano_cobranza"];
						  $rif_ci       = $datos_filas[0]["v_shp002_cobranza_estado_cuenta_2"]["rif_ci"];
					      $condicion       = $this->condicion()." and rif_ci='".$rif_ci."' and ano='".$ano_cobranza."' ";
						  $datos_filas_aux_1 = $this->shd002_cobranza_realizada->findAll($condicion);
						  $datos_filas_aux_2 = $this->shd002_cobranza_pendiente->findAll($condicion);

						  $this->set("datos_1", $datos_filas_aux_1);
						  $this->set("datos_2", $datos_filas_aux_2);




}//fin function




function index(){
$this->layout="ajax";
$this->Session->delete('pista_buscar_cobrador_hacienda');
$this->Session->delete('pista_buscar_cobranza_pendiente_hacienda');
}//fin function





}//fin class

?>