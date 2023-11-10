<?php
 class Cepp01CompromisoBeneficiarioRifController extends AppController{
	var $name = 'cepp01_compromiso_beneficiario_rif';
	var $uses = array('cepd01_compromiso_beneficiario_rif','cepd01_compromiso_cuerpo');
	var $helpers = array('Html','Ajax','Javascript', 'Sisap');



 function checkSession(){
				if (!$this->Session->check('Usuario')){
					$this->redirect('/salir/');
					exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
 }//fin checksession


function concatenarif($vector1=null, $nomVar=null, $extra=null){
	$cod = array();
	if($vector1 != null){
		foreach($vector1 as $x => $y){
		$cod[$x] = $x.' - '.$y;
		}
	}
	$this->set($nomVar, $cod);
}//fin function

function concatenarif_b14($vector1=null, $nomVar=null, $extra=null){
	$cod = array();
	$caespa="";
	if($vector1 != null){
		foreach($vector1 as $x => $y){
					$canti= strlen($x);
					$canti= $canti==14 ? 0 : abs((int)14-$canti);
					for($e=1;$e<=$canti;$e++){
						$caespae=".";
						$caespa.=$caespae;
					}
					$cod[$x] = $x.' '.$caespa.' '.$y;
					$caespa="";
		}
	}
	$this->set($nomVar, $cod);
}//fin function

 function index($pagina=null){
	$this->layout="ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$beneficiarios = $this->cepd01_compromiso_beneficiario_rif->generateList(null,'beneficiario ASC', null, '{n}.cepd01_compromiso_beneficiario_rif.rif', '{n}.cepd01_compromiso_beneficiario_rif.beneficiario');
	$beneficiarios = $beneficiarios != null ? $beneficiarios : array();
	$this->concatenarif_b14($beneficiarios, 'beneficiarios');

	if(isset($pagina)){
		$pagina=$pagina;
	}else{
		$pagina=1;
	}//fin else
	$Tfilas=$this->cepd01_compromiso_beneficiario_rif->findCount();
    if($Tfilas!=0){
    	$Tfilas=(int)ceil($Tfilas/1000);
    	$this->set('total_paginas',$Tfilas);
		$this->set('pagina_actual',$pagina);
		$this->set('pag_cant',$pagina.'/'.$Tfilas);
		$this->set('ultimo',$Tfilas);
 	    $datos_filas=$this->cepd01_compromiso_beneficiario_rif->findAll(null,null,'beneficiario ASC',1000,$pagina,null);
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
		$cond="rif='$select'";
		$dato=$this->cepd01_compromiso_beneficiario_rif->findAll($cond);
		$this->set('id_fila',$id_fila);
		$this->set('rif',$dato[0]['cepd01_compromiso_beneficiario_rif']['rif']);
		$this->set('beneficiario',$dato[0]['cepd01_compromiso_beneficiario_rif']['beneficiario']);
	}else{
		$this->set('rif','');
		$this->set('id_fila','');
		$this->set('beneficiario','');
		$this->set('mensajeError','No ha seleccionado nigun tipo de Beneficiario');
	}
 }//mostrar1


 function guardar(){
	$this->layout="ajax";
	if($this->data['cepp01_compromiso_beneficiario_rif']['rif'] !="" && $this->data['cepp01_compromiso_beneficiario_rif']['denominacion'] !=""){
		$rif=$this->data['cepp01_compromiso_beneficiario_rif']['rif'];
		$cond="rif='$rif'";
		if($this->cepd01_compromiso_beneficiario_rif->findAll($cond)){
			$this->set('mensajeError','LO SIENTO EL RIF ('.$this->data['cepp01_compromiso_beneficiario_rif']['rif'].') YA SE ENCUENTRA REGISTRADO EN EL SISTEMA');
			$datos=$this->cepd01_compromiso_beneficiario_rif->findAll(null,null,'beneficiario ASC');
		    $this->set('datos',$datos);
		}else{
		   $sql="INSERT INTO cepd01_compromiso_beneficiario_rif VALUES ('".$this->data['cepp01_compromiso_beneficiario_rif']['rif']."','".$this->data['cepp01_compromiso_beneficiario_rif']['denominacion']."')";
		   if($this->cepd01_compromiso_beneficiario_rif->execute($sql)>1){
		      $this->set('mensaje','EL BENEFICIARIO FUE AGREGADO CORRECTAMENTE');
		      $datos=$this->cepd01_compromiso_beneficiario_rif->findAll(null,null,'beneficiario ASC');
		      $this->set('datos',$datos);
		   }else{
		      $this->set('mensajeError','LO SIENTO, EL BENEFICIARIO NO PUDO SER AGREGADO');
		      $datos=$this->cepd01_compromiso_beneficiario_rif->findAll(null,null,'beneficiario ASC');
		   	  $this->set('datos',$datos);
		   }
		}//fin else consulta
	}else{
		$this->set('mensajeError','ATENCI&Oacute;N, DEBE INGRESAR LA rif Y EL NOMBRE DEL BENEFICIARIO');
		$datos=$this->cepd01_compromiso_beneficiario_rif->findAll(null,null,'beneficiario ASC');
		$this->set('datos',$datos);
	}

	echo'<script>';
  	  echo"document.getElementById('b_guardar').disabled='';";
  	echo'</script>';
 }//guardar


 function guardar_modificar($var_rif=null,$id_fila=null){
	$this->layout="ajax";

	$rif = strtoupper($var_rif);
	if($this->data['cepp01_compromiso_beneficiario_rif']['rif'] !="" && $this->data['cepp01_compromiso_beneficiario_rif']['denominacion'] !=""){
		//$rif = strtoupper($var_rif);
		$deno= strtoupper($this->data['cepp01_compromiso_beneficiario_rif']['denominacion']);
		$sql_update="UPDATE cepd01_compromiso_beneficiario_rif SET beneficiario='".$this->data['cepp01_compromiso_beneficiario_rif']['denominacion']."' WHERE UPPER(rif)='$rif'";

		if($this->cepd01_compromiso_beneficiario_rif->execute($sql_update)>0){
			$sql_update_compromiso = "UPDATE cepd01_compromiso_cuerpo SET beneficiario='".$this->data['cepp01_compromiso_beneficiario_rif']['denominacion']."' WHERE UPPER(rif)='$rif'";
			$sql_update_ordenpago  = "UPDATE cepd03_ordenpago_cuerpo SET beneficiario='".$this->data['cepp01_compromiso_beneficiario_rif']['denominacion']."' WHERE UPPER(rif)='$rif'";
			$this->cepd01_compromiso_beneficiario_rif->execute($sql_update_compromiso);
			$this->cepd01_compromiso_beneficiario_rif->execute($sql_update_ordenpago);

			$this->set('mensaje','EL BENEFICIARIO FUE MODIFICADO CORRECTAMENTE');
			$beneficiario = $this->cepd01_compromiso_beneficiario_rif->findAll("UPPER(rif)='$rif'");
			$this->set('rif',$beneficiario[0]['cepd01_compromiso_beneficiario_rif']['rif']);
			$this->set('deno',$beneficiario[0]['cepd01_compromiso_beneficiario_rif']['beneficiario']);
			$this->set('j',$id_fila);
		}else{
			$this->set('mensajeError','LO SIENTO, EL  BENEFICIARIO NO PUDO SER MODIFICADO');
			$beneficiario = $this->cepd01_compromiso_beneficiario_rif->findAll("UPPER(rif)='$rif'");
			$this->set('rif',$beneficiario[0]['cepd01_compromiso_beneficiario_rif']['rif']);
			$this->set('deno',$beneficiario[0]['cepd01_compromiso_beneficiario_rif']['beneficiario']);
			$this->set('j',$id_fila);
		}
	}else{
		$this->set('mensajeError','ATENCI&Oacute;N, DEBE INGRESAR EL NOMBRE DEL BENEFICIARIO');
		$beneficiario = $this->cepd01_compromiso_beneficiario_rif->findAll("UPPER(rif)='$rif'");
		$this->set('rif',$beneficiario[0]['cepd01_compromiso_beneficiario_rif']['rif']);
		$this->set('deno',$beneficiario[0]['cepd01_compromiso_beneficiario_rif']['beneficiario']);
		$this->set('j',$id_fila);
	}
 }//guardar modificar




 function eliminar($rif=null){
	$this->layout="ajax";
    if($rif!=null){
       if($this->cepd01_compromiso_cuerpo->findByrif($rif)){
	   $this->set('mensajeError','EL BENEFICIARIO NO PUEDE SER ELIMINADO, YA SE ENCUENTRA PRESENTE EN UN COMPROMISO');
	   }else{
		   $sql="DELETE FROM cepd01_compromiso_beneficiario_rif WHERE rif='$rif'";
		   if($this->cepd01_compromiso_beneficiario_rif->execute($sql)>1){
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
 			$datos=$this->cepd01_compromiso_beneficiario_rif->findAll(null,null,'rif ASC');
			$this->set('datos',$datos);
 		}elseif($var==2){
 			$datos=$this->cepd01_compromiso_beneficiario_rif->findAll(null,null,'beneficiario ASC');
			$this->set('datos',$datos);
 		}
 	}else{
 		$this->set('mensajeError','LO SIENTO, NO LLEGO INFORMACI&Oacute;N PARA PROCESAR');
 	}
 }//mostrar_datos



function consulta($pagina=null,$orden=null){
 	$this->layout="ajax";

		/*if(!isset($orden)){
 			$cond_order="rif ASC";
		}elseif($orden==2){
			$cond_order="beneficiario ASC";
		}else{
			$cond_order="rif ASC";
		}*/
        switch($orden){
			case 1:$cond_order="rif ASC";break;
			case 2:$cond_order="beneficiario ASC";break;
			default:$cond_order="beneficiario ASC";break;
		}

		if(isset($pagina)){
			$pagina=$pagina;
		}else{
			$pagina=1;
		}//fin else

 		$Tfilas=$this->cepd01_compromiso_beneficiario_rif->findCount();
	    if($Tfilas!=0){
	    	$Tfilas=(int)ceil($Tfilas/1000);
	    	$this->set('total_paginas',$Tfilas);
			$this->set('pagina_actual',$pagina);
			$this->set('pag_cant',$pagina.'/'.$Tfilas);
			$this->set('ultimo',$Tfilas);
	 	    $datos_filas=$this->cepd01_compromiso_beneficiario_rif->findAll(null,null,$cond_order,1000,$pagina,null);
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