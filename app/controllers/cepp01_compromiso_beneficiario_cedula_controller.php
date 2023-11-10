<?php
 class Cepp01CompromisoBeneficiarioCedulaController extends AppController{
	var $name = 'cepp01_compromiso_beneficiario_cedula';
	var $uses = array('cepd01_compromiso_beneficiario_cedula','cepd01_compromiso_cuerpo');
	var $helpers = array('Html','Ajax','Javascript', 'Sisap');



 function checkSession(){
				if (!$this->Session->check('Usuario')){
					$this->redirect('/salir/');
					exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
 }//fin checksession

function concatena_b14($vector1=null, $nomVar=null, $extra=null){
	$cod = array();
	$caespa="";
	if($vector1 != null){
		foreach($vector1 as $x => $y){
			if($extra!=null){
				if($x<10){
					$cod[$x] = $extra.'.0'.$x.' - '.$y;
				}else if($x>=10 && $x<=99){
					$cod[$x] = $extra.'.'.$x.' - '.$y;
				}
			}else{
				if($x<10){
					$y=($y!="")?$y:"N/A";
					$cod[$x] = '0000000000'.$x.' - '.$y;
				}else if($x<100){
					$y=($y!="")?$y:"N/A";
					$cod[$x] = '000000000'.$x.' - '.$y;
				}else if($x<1000){
					$y=($y!="")?$y:"N/A";
					$cod[$x] = '00000000'.$x.' - '.$y;
				}else if($x<10000){
					$y=($y!="")?$y:"N/A";
					$cod[$x] = '0000000'.$x.' - '.$y;
				}else if($x<100000){
					$y=($y!="")?$y:"N/A";
					$cod[$x] = '000000'.$x.' - '.$y;
				}else if($x<1000000){
					$y=($y!="")?$y:"N/A";
					$cod[$x] = '00000'.$x.' - '.$y;
				}else if($x<10000000){
					$y=($y!="")?$y:"N/A";
					$cod[$x] = '0000'.$x.' - '.$y;
				}else if($x<100000000){
					$y=($y!="")?$y:"N/A";
					$cod[$x] = '000'.$x.' - '.$y;
				}else if($x<1000000000){
					$y=($y!="")?$y:"N/A";
					$cod[$x] = '00'.$x.' - '.$y;
				}else if($x<10000000000){
					$y=($y!="")?$y:"N/A";
					$cod[$x] = '0'.$x.' - '.$y;
				}
			}
		}
		//print_r($cod);
	}

	$this->set($nomVar, $cod);
}//fin function


 function index($pagina=null){
	$this->layout="ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$beneficiarios = $this->cepd01_compromiso_beneficiario_cedula->generateList(null,'beneficiario ASC', null, '{n}.cepd01_compromiso_beneficiario_cedula.cedula', '{n}.cepd01_compromiso_beneficiario_cedula.beneficiario');
	$beneficiarios = $beneficiarios != null ? $beneficiarios : array();
	$this->concatena_b14($beneficiarios, 'beneficiarios');

	if(isset($pagina)){
		$pagina=$pagina;
	}else{
		$pagina=1;
	}//fin else
	$Tfilas=$this->cepd01_compromiso_beneficiario_cedula->findCount();
    if($Tfilas!=0){
    	$Tfilas=(int)ceil($Tfilas/1000);
    	$this->set('total_paginas',$Tfilas);
		$this->set('pagina_actual',$pagina);
		$this->set('pag_cant',$pagina.'/'.$Tfilas);
		$this->set('ultimo',$Tfilas);
 	    $datos_filas=$this->cepd01_compromiso_beneficiario_cedula->findAll(null,null,'beneficiario ASC',1000,$pagina,null);
        $this->set("datos",$datos_filas);
        $this->set('siguiente',$pagina+1);
		$this->set('anterior',$pagina-1);
		$this->bt_nav($Tfilas,$pagina);
    }else{
    	$this->set("datos",'');
    }
 }//index


 function mostrar1($select=null,$id_fila=null){
	$this->layout="ajax";
	if($select!=null){
		$cond="cedula='$select'";
		$dato=$this->cepd01_compromiso_beneficiario_cedula->findAll($cond);
		$this->set('id_fila',$id_fila);
		$this->set('cedula',$dato[0]['cepd01_compromiso_beneficiario_cedula']['cedula']);
		$this->set('beneficiario',$dato[0]['cepd01_compromiso_beneficiario_cedula']['beneficiario']);
	}else{
		$this->set('id_fila','');
		$this->set('cedula','');
		$this->set('beneficiario','');
		$this->set('mensajeError','No ha seleccionado nigun tipo de Beneficiario');
	}
 }//mostrar1


 function guardar(){
	$this->layout="ajax";
	if($this->data['cepp01_compromiso_beneficiario_cedula']['cedula'] !="" && $this->data['cepp01_compromiso_beneficiario_cedula']['denominacion'] !=""){
		if($this->cepd01_compromiso_beneficiario_cedula->findAll("cedula='".$this->data['cepp01_compromiso_beneficiario_cedula']['cedula']."'")){
			$this->set('mensajeError','LO SIENTO LA CEDULA ('.$this->data['cepp01_compromiso_beneficiario_cedula']['cedula'].') YA SE ENCUENTRA REGISTRADA EN EL SISTEMA');
			$datos=$this->cepd01_compromiso_beneficiario_cedula->findAll(null,null,'beneficiario ASC');
		    $this->set('datos',$datos);
		}else{
		   $sql="INSERT INTO cepd01_compromiso_beneficiario_cedula VALUES ('".$this->data['cepp01_compromiso_beneficiario_cedula']['cedula']."','".$this->data['cepp01_compromiso_beneficiario_cedula']['denominacion']."')";
		   if($this->cepd01_compromiso_beneficiario_cedula->execute($sql)>1){
		      $this->set('mensaje','EL BENEFICIARIO FUE AGREGADO CORRECTAMENTE');
		      $datos=$this->cepd01_compromiso_beneficiario_cedula->findAll(null,null,'beneficiario ASC');
		      $this->set('datos',$datos);
		   }else{
		      $this->set('mensajeError','LO SIENTO, EL BENEFICIARIO NO PUDO SER AGREGADO');
		      $datos=$this->cepd01_compromiso_beneficiario_cedula->findAll(null,null,'beneficiario ASC');
		   	  $this->set('datos',$datos);
		   }
		}//fin else consulta
	}else{
		$this->set('mensajeError','ATENCI&Oacute;N, DEBE INGRESAR LA CEDULA Y EL NOMBRE DEL BENEFICIARIO');
		$datos=$this->cepd01_compromiso_beneficiario_cedula->findAll(null,null,'beneficiario ASC');
		$this->set('datos',$datos);
	}

	echo'<script>';
  	  echo"document.getElementById('b_guardar').disabled='';";
  	echo'</script>';
 }//guardar


 function guardar_modificar($var_cedula=null,$id_fila=null){
	$this->layout="ajax";

	$cedula=strtoupper($var_cedula);
	if($this->data['cepp01_compromiso_beneficiario_cedula']['cedula'] !="" && $this->data['cepp01_compromiso_beneficiario_cedula']['denominacion'] !=""){
		//$cedula=strtoupper($var_cedula);
		$deno=strtoupper($this->data['cepp01_compromiso_beneficiario_cedula']['denominacion']);
		$sql_update="UPDATE cepd01_compromiso_beneficiario_cedula SET beneficiario='".$this->data['cepp01_compromiso_beneficiario_cedula']['denominacion']."' WHERE UPPER(cedula)='$cedula'";

		if($this->cepd01_compromiso_beneficiario_cedula->execute($sql_update)>0){
			$sql_update_compromiso = "UPDATE cepd01_compromiso_cuerpo SET beneficiario='".$this->data['cepp01_compromiso_beneficiario_cedula']['denominacion']."' WHERE UPPER(cedula_identidad)='$cedula'";
			$sql_update_ordenpago  = "UPDATE cepd03_ordenpago_cuerpo SET beneficiario='".$this->data['cepp01_compromiso_beneficiario_cedula']['denominacion']."' WHERE UPPER(rif)='$cedula'";
			$this->cepd01_compromiso_beneficiario_cedula->execute($sql_update_compromiso);
			$this->cepd01_compromiso_beneficiario_cedula->execute($sql_update_ordenpago);

			$this->set('mensaje','EL BENEFICIARIO FUE MODIFICADO CORRECTAMENTE');
			$beneficiario = $this->cepd01_compromiso_beneficiario_cedula->findAll("UPPER(cedula)='$cedula'");
			$this->set('cedula',$beneficiario[0]['cepd01_compromiso_beneficiario_cedula']['cedula']);
			$this->set('deno',$beneficiario[0]['cepd01_compromiso_beneficiario_cedula']['beneficiario']);
			$this->set('j',$id_fila);
		}else{
			$this->set('mensajeError','LO SIENTO, EL  BENEFICIARIO NO PUDO SER MODIFICADO');
			$beneficiario = $this->cepd01_compromiso_beneficiario_cedula->findAll("UPPER(cedula)='$cedula'");
			$this->set('cedula',$beneficiario[0]['cepd01_compromiso_beneficiario_cedula']['cedula']);
			$this->set('deno',$beneficiario[0]['cepd01_compromiso_beneficiario_cedula']['beneficiario']);
			$this->set('j',$id_fila);
		}
	}else{
		$this->set('mensajeError','ATENCI&Oacute;N, DEBE INGRESAR EL NOMBRE DEL BENEFICIARIO');
		$beneficiario = $this->cepd01_compromiso_beneficiario_cedula->findAll("UPPER(cedula)='$cedula'");
		$this->set('cedula',$beneficiario[0]['cepd01_compromiso_beneficiario_cedula']['cedula']);
		$this->set('deno',$beneficiario[0]['cepd01_compromiso_beneficiario_cedula']['beneficiario']);
		$this->set('j',$id_fila);
	}
 }//guardar modificar




 function eliminar($cedula=null){
	$this->layout="ajax";
    if($cedula!=null){
       if($this->cepd01_compromiso_cuerpo->findBycedula_identidad($cedula)){
	   $this->set('mensajeError','EL BENEFICIARIO NO PUEDE SER ELIMINADO, YA SE ENCUENTRA PRESENTE EN UN COMPROMISO');
	   }else{
		   $sql="DELETE FROM cepd01_compromiso_beneficiario_cedula WHERE cedula='".$cedula."'";
		   if($this->cepd01_compromiso_beneficiario_cedula->execute($sql)>1){
		   $this->set('mensaje','EL BENEFICIARIO FUE ELIMINADO CORRECTAMENTE');
		   }else{
		   $this->set('mensajeError','LO SIENTO, EL BENEFICIARIO NO PUDO SER ELIMINADO');
		   }
       }
    }else{
        $this->set('mensajeError','LO SIENTO, LOS DATOS NO LLEGARON CORRECTAMENTE Y NO SE PUDO PROCESAR LA ELIMINACI&Oacute;N');
    }
    $this->index();
	$this->render("index");
 }//eliminar


 function mostrar_datos($var=null){
 	$this->layout="ajax";

 	if($var!=null){
 		if($var==1){
 			$datos=$this->cepd01_compromiso_beneficiario_cedula->findAll(null,null,'cedula ASC');
			$this->set('datos',$datos);
 		}elseif($var==2){
 			$datos=$this->cepd01_compromiso_beneficiario_cedula->findAll(null,null,'beneficiario ASC');
			$this->set('datos',$datos);
 		}
 	}else{
 		$this->set('mensajeError','LO SIENTO, NO LLEGO INFORMACI&Oacute;N PARA PROCESAR');
 	}
 }//mostrar_datos


 function consulta($pagina=null,$orden=null){
 	$this->layout="ajax";

		/*if(!isset($orden)){
 			$cond_order="cedula ASC";
		}elseif($orden==2){
			$cond_order="beneficiario ASC";
		}else{
			$cond_order="cedula ASC";
		}*/
		switch($orden){
			case 1:$cond_order="cedula ASC";break;
			case 2:$cond_order="beneficiario ASC";break;
			default:$cond_order="beneficiario ASC";break;
		}

		if(isset($pagina)){
			$pagina=$pagina;
		}else{
			$pagina=1;
		}//fin else

 		$Tfilas=$this->cepd01_compromiso_beneficiario_cedula->findCount();
	    if($Tfilas!=0){
	    	$Tfilas=(int)ceil($Tfilas/1000);
	    	$this->set('total_paginas',$Tfilas);
			$this->set('pagina_actual',$pagina);
			$this->set('pag_cant',$pagina.'/'.$Tfilas);
			$this->set('ultimo',$Tfilas);
	 	    $datos_filas=$this->cepd01_compromiso_beneficiario_cedula->findAll(null,null,$cond_order,1000,$pagina,null);
	        $this->set("datos",$datos_filas);
	        $this->set('siguiente',$pagina+1);
			$this->set('anterior',$pagina-1);
			$this->bt_nav($Tfilas,$pagina);
			$this->set('actual',$pagina);
	    }else{
	    	$this->set("datos",'');
	    }
 }

function bt_nav($Tfilas,$pagina){
		if($Tfilas==1){
				$this->set('mostrarS',false);
				$this->set('mostrarA',false);
		}else if($Tfilas==2){
			if($pagina==2){
					 $this->set('mostrarS',false);
					 $this->set('mostrarA',true);
			}else{
				 $this->set('mostrarS',true);
					 $this->set('mostrarA',false);
			}
		}else if($Tfilas>=3){
			if($pagina==$Tfilas){
						 $this->set('mostrarS',false);
						 $this->set('mostrarA',true);
			}else if($pagina==1){
				 $this->set('mostrarS',true);
						 $this->set('mostrarA',false);
			}else{
				 $this->set('mostrarS',true);
						 $this->set('mostrarA',true);
			}
		}
}//fin navegacion


}
?>