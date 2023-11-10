<?php
/*
 * Creado el 11/07/2008 a las 11:11:54 PM
 * Erisk German Arsgol Hernandez
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */
 class Casp01AutorizacionAtencionSocialController extends AppController{
	var $name = 'casp01_autorizacion_atencion_social';
    var $uses = array('casd00_autorizacion','Usuario');
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


 function SQLCA($ano=null){//sql para busqueda de codigos de arranque con y sin año
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



 function index(){
 	$this->layout="ajax";

 }//index


function busqueda_usuario($login=null,$var=null){
	$this->layout="ajax";
	$login=strtoupper($login);
	$ver=$this->casd00_autorizacion->FindAll("username='$login'");
	$ver1=$this->Usuario->FindAll("username='$login'");
	if($ver!=null){
		if($var==1){
			$this->set('Message_existe', 'el registro fue modificado con exito');
		}else if($var==2){
			$this->set('Message_existe', 'registro exitoso');
		}else if($var==null){
			$this->set('Message_existe', 'este usuario ya posee permisos registrados');
		}
		$this->set('autorizado',$ver);
		$dato=$this->casd00_autorizacion->execute("select * from usuarios where username='$login'");
		$this->set('dato',$dato);
	}else if($ver1!=null){
		$this->set('usuario',$ver1);
		$this->set('Message_existe', 'Proceda a registrar los permisos del usuario');
	}else{
		$this->set('errorMessage',"no existe el usuario");
		$this->set('registrar','');
	}

}// fin busqueda_usuario

function guardar($var=null,$usuario=null,$pagina=null){
	$this->layout="ajax";
	$datos_personales=$this->data['ccnp00']['datos_personales'];
	$solicitudes=$this->data['ccnp00']['solicitudes'];
	$evaluaciones=$this->data['ccnp00']['evaluaciones'];
	$ayudas=$this->data['ccnp00']['ayudas'];
	$tipo_ayudas=$this->data['ccnp00']['tipo_ayudas'];
	$graficos=$this->data['ccnp00']['graficos'];
	$reportes=$this->data['ccnp00']['reportes'];

	if($var==1 || $var==3){
			$sql="update casd00_autorizacion set datos_personales='$datos_personales',solicitudes='$solicitudes',evaluaciones='$evaluaciones',ayudas='$ayudas',tipo_ayuda='$tipo_ayudas',graficos='$graficos',reportes='$reportes' where username='$usuario'";
			$sw=$this->casd00_autorizacion->execute($sql);
			$this->set('Message_existe', 'el registro fue modificado con exito');
	}else{
			$sql = "BEGIN;INSERT INTO casd00_autorizacion VALUES ('$usuario', '$datos_personales', '$solicitudes', '$evaluaciones', '$ayudas', '$tipo_ayudas','$graficos','$reportes')";
		   	$sw=$this->casd00_autorizacion->execute($sql);
		   	if($sw>1){
				$this->casd00_autorizacion->execute("COMMIT");
				$this->set('Message_existe', 'REGISTRO EXITOSO');
	   		}else{
	   			$this->ccnd00->execute("ROLLBACK");
	   			$this->set('errorMessage', 'INSERCI&Oacute;N FALLIDA DE LOS DATOS');
	   		}
	}

if($var!=3){
	$this->busqueda_usuario($usuario,$var);
	$this->render('busqueda_usuario');
}else{
	$this->consulta($pagina,1);
	$this->render('consulta');
}



}// fin guardar


function consulta($pagina=null,$mensaje=null) {
	$this->layout="ajax";

	if(isset($pagina)){
		$Tfilas=$this->casd00_autorizacion->findCount();
        if($Tfilas!=0){
        	$x=$this->casd00_autorizacion->findAll(null,null,"username ASC",1,$pagina,null);

            $this->set('DATA',$x);
            $this->set('pagina',$pagina);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
            $this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	 	       $this->index();
			   $this->render("index");
			   return;
        }

	}else{
		$pagina=1;
		$Tfilas=$this->casd00_autorizacion->findCount();

        if($Tfilas!=0){
        	$x=$this->casd00_autorizacion->findAll(null,null,"username ASC",1,$pagina,null);
			$this->set('DATA',$x);
			$this->set('pagina',$pagina);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	$this->bt_nav($Tfilas,$pagina);
          	$this->set('numT',$Tfilas);
			$this->set('numP',$pagina);

        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	 	       $this->index();
			   $this->render("index");
			   return;
        }
	}
	if($mensaje==1){
		$this->set('Message_existe', 'el registro fue modificado con exito');
	}

	$dato=$this->casd00_autorizacion->execute("select * from usuarios where username='".$x[0]['casd00_autorizacion']['username']."'");
	$this->set('dato',$dato);

	$this->set('autorizado',$x);

 }//consultar


 function seleccion_busqueda($var2=null,$var=null){
 		$this->layout="ajax";

	$x=$this->ccnd00->findAll("username='$var'",null,null,null,null,null);
	$this->set('estado',$this->cugd01_estados->field('denominacion', $conditions ="cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$x[0]["ccnd00"]["cod_estado"], $order ="cod_estado ASC"));
	$this->set('municipio',$this->cugd01_municipios->field('denominacion', $conditions ="cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$x[0]["ccnd00"]["cod_estado"]." and cod_municipio=".$x[0]["ccnd00"]["cod_municipio"], $order ="cod_municipio ASC"));
	$this->set('parroquia',$this->cugd01_parroquias->field('denominacion', $conditions ="cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$x[0]["ccnd00"]["cod_estado"]." and cod_municipio=".$x[0]["ccnd00"]["cod_municipio"]." and cod_parroquia=".$x[0]["ccnd00"]["cod_parroquia"], $order ="cod_parroquia ASC"));
	$this->set('centro',$this->cugd01_centropoblados->field('denominacion', $conditions ="cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$x[0]["ccnd00"]["cod_estado"]." and cod_municipio=".$x[0]["ccnd00"]["cod_municipio"]." and cod_parroquia=".$x[0]["ccnd00"]["cod_parroquia"]." and cod_centro=".$x[0]["ccnd00"]["cod_centro"], $order ="cod_centro ASC"));

	$this->set('datos',$x);

 }


function buscar_datos($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin buscar_ficha

 function buscar_por_pista($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
    if($var3==null){
    	$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					$Tfilas=$this->ccnd00->findCount("upper(username::text) LIKE upper('%".$var2."%') ");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->ccnd00->findAll("upper(username::text) LIKE upper('%".$var2."%')",null,"username ASC",100,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);
						$Tfilas=$this->ccnd00->findCount("upper(username::text) LIKE upper('%".$var22."%')");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
									$datos_filas=$this->ccnd00->findAll("upper(username::text) LIKE upper('%".$var22."%')",null,"username ASC",100,1,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else


$this->set("opcion",$var1);
}//fin function



function eliminar($var=null,$pagina=null){
	$this->layout="ajax";

	$sw=$this->ccnd00->execute("delete from ccnd00 where username='$var'");

	if($sw > 1){
		$this->set('Message_existe','registro eliminado con exito');
	}else{
		$this->set('errorMessage', 'EL DATO NO PUDO SER ELIMINADO');
	}

	 if($pagina!=null){
	  		$this->consulta($pagina);
	  		$this->render('consulta');
	  }else{
		  	$this->index();
  			$this->render('index');
	  }

}// fin eliminar




 }//fin class
?>
