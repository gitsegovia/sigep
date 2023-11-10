<?php
/*
 * Created on 03/05/2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

 class Cnmp00RelacionNominasController extends AppController {
   var $name = 'cnmp00_relacion_nominas';
   var $uses = array('Cnmd01', 'arrd05', 'cugd01_estados', 'v_cnmd06_fichas_vision', 'cugd05_restriccion_clave', 'ccfd04_cierre_mes');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap');

function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
}//fin checksession

 function beforeFilter(){
 	$this->checkSession();
 }



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



function SQLCA($ano=null){ //sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
         if($ano!=null){
         	$sql_re .= "cod_dep=".$this->verifica_SS(5)."  and  ";
            $sql_re .= "ano=".$ano."  ";
         }else{
         	$sql_re .= "cod_dep=".$this->verifica_SS(5)." ";
         }
         return $sql_re;
}//fin funcion SQLCA


function SQLCA_noDEP(){
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

  return $condicion;
}//fin funcion SQLCA_noDEP


function index(){
 	$this->layout ="ajax";
 	$this->Session->delete('conso_tipo_reporte');
 	$this->Session->delete('conso_tipo_dependenci');
 	$this->set('cod_verifica', $this->verifica_SS(5)!=1 ? $this->Session->read('SScoddep') : null);
}


function tipo_reporte($opcion=null){
	$this->layout ="ajax";
	$this->Session->write('conso_tipo_reporte', $opcion);
	if($opcion==2 || $opcion=='2'){
				$url                  =  "/cnmp00_relacion_nominas/buscar_datos_dependencia/$opcion";
				$width_aux            =  "750px";
				$height_aux           =  "450px";
				$title_aux            =  "Buscar Dependencia";
				$resizable_aux        =  false;
				$maximizable_aux      =  false;
				$minimizable_aux      =  false;
				$closable_aux         =  false;

			 echo "<script>";
	           echo "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
	         echo "</script>";
	}
}


function seleccion_dep($opcionom=null, $codigo_depe=null){
	$this->layout ="ajax";
	if($codigo_depe!=null && ($opcionom==2 || $opcionom=='2')){
		$this->Session->write('conso_tipo_dependenci', $codigo_depe);
	}else{
		$this->Session->write('conso_tipo_dependenci', 0);
	}
}


function salir_busqueda($opcionom=null){
	$this->layout ="ajax";
	$this->Session->delete('conso_tipo_dependenci');
}


function buscar_datos_dependencia($var1=null, $cod=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
	echo "<script>$('inp_cod_bus').focus();</script>";
}//fin function


function buscar_datos_porpista($var1=null, $var2=null, $var3=null){
	$this->layout="ajax";
	$modelo='arrd05';
    if($var3==null){ $var2 = strtoupper($var2);
					 $this->Session->write('pista', $var2);
					 $Tfilas=$this->$modelo->findCount($this->SQLCA_noDEP()." and ((cod_dep::text LIKE '%$var2%') or (quitar_acentos(denominacion) LIKE quitar_acentos('%$var2%')))");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
								$datos_filas=$this->$modelo->findAll($this->SQLCA_noDEP()." and ((cod_dep::text LIKE '%$var2%') or (quitar_acentos(denominacion) LIKE quitar_acentos('%$var2%')))",'cod_dep, denominacion',"denominacion ASC",50,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
								$this->set('total_paginas','');
								$this->set('pagina_actual','');
							    $this->set('siguiente','');
								$this->set('anterior','');
								$this->set('ultimo','');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);
						$Tfilas=$this->$modelo->findCount($this->SQLCA_noDEP()." and ((cod_dep::text LIKE '%$var2%') or (quitar_acentos(denominacion) LIKE quitar_acentos('%$var2%')))");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
									$datos_filas=$this->$modelo->findAll($this->SQLCA_noDEP()." and ((cod_dep::text LIKE '%$var2%') or (quitar_acentos(denominacion) LIKE quitar_acentos('%$var2%')))",'cod_dep, denominacion',"denominacion ASC",50,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
									$this->set('total_paginas','');
									$this->set('pagina_actual','');
							    	$this->set('siguiente','');
									$this->set('anterior','');
									$this->set('ultimo','');
						          }
   		}//fin else
$this->set("opcion",$var1);
} //fin funcion



function relacion_nomina($propio=null){
	set_time_limit(0);
	ini_set("memory_limit","2560M");
	$this->layout = "pdf";

if($propio!=null){
	$condi_rep = " and cod_dep='$propio'";
}else{

	if(isset($this->data["cnmp00_relacion_nominas"]["opcion_reporte"]) && $this->data["cnmp00_relacion_nominas"]["opcion_reporte"]!=null){
		$opcion_reporte = $this->data["cnmp00_relacion_nominas"]["opcion_reporte"];
	}else{
		$opcion_reporte = $this->Session->read('conso_tipo_reporte');
	}

	$tipo_depend = $this->Session->read('conso_tipo_dependenci');

	if($opcion_reporte==1 || $opcion_reporte=='1'){
		$condi_rep = "";
	}else if($tipo_depend!=0 && ($opcion_reporte==2 || $opcion_reporte=='2')){
		$condi_rep = " and cod_dep='$tipo_depend'";
	}else{
		$condi_rep = " and cod_dep=0";
	}
}

	$this->set("tipe_reporte",$opcion_reporte);

	$estado = $this->cugd01_estados->execute("SELECT denominacion FROM cugd01_estados WHERE cod_republica='".$this->verifica_SS(1)."' and cod_estado='".$this->verifica_SS(2)."';");
	$_SESSION['estado'] = $estado[0][0]['denominacion'];
	$institucion = $this->cugd01_estados->execute("SELECT denominacion FROM cugd02_institucion WHERE cod_tipo_institucion='".$this->verifica_SS(3)."' and cod_institucion='".$this->verifica_SS(4)."';");
	$_SESSION['institucion'] = $institucion[0][0]['denominacion'];
	$dependencia = $this->cugd01_estados->execute("SELECT denominacion FROM cugd02_dependencias WHERE cod_tipo_institucion='".$this->verifica_SS(3)."' and cod_institucion='".$this->verifica_SS(4)."' and cod_dependencia='".$this->verifica_SS(5)."';");
	$_SESSION['dependencia'] = $dependencia[0][0]['denominacion'];

	$datos_noms = $this->v_cnmd06_fichas_vision->findAll($this->SQLCA_noDEP().$condi_rep,null,"cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina ASC",null,null,null);
	if(!empty($datos_noms))
		$this->set("datos_dnomina",$datos_noms);
	else
		$this->set("datos_dnomina",array());
} // fin funcion reporte: relacion_nomina


 }

?>
