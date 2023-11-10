<?php
 class Cnmp02TablasTipoController extends AppController {
 	var $name = 'cnmp02_tablas_tipo';
	var $uses = array('cnmd02_tablas_tipo','cnmd02_tablas_grado_paso','cnmd02_deno_grado');
	var $helpers = array('Html','Ajax','Javascript', 'Sisap');


 function checkSession(){
				if (!$this->Session->check('Usuario')){
					$this->redirect('/salir/');
					exit();
				}else{
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
		$v=$this->cnmd02_tablas_tipo->execute("SELECT * FROM cnmd02_tablas_tipo ORDER BY cod_tabla DESC");

		if($v!=null){
			$cod_tipo=$v[0][0]["cod_tabla"];
			$cod_tipo = $cod_tipo =="" ? 1 : $cod_tipo+1;
		}else{
			$cod_tipo=1;
		}
		$this->set('cod_tipo',$cod_tipo);
		$datos=$this->cnmd02_tablas_tipo->FindAll('ORDER BY cod_tabla ASC');
		if($datos){
			$this->set('datos',$datos);
		}else{
			$this->set('datos','');
		}



 }//index


 function guardar(){
	$this->layout="ajax";
    $cod_tipo=$this->data['cnmp02_tablas_tipo']['cod_tipo'];
	$deno=$this->data['cnmp02_tablas_tipo']['denominacion'];
if(!empty($deno)){

	if($this->cnmd02_tablas_tipo->FindCount("cod_tabla=".$cod_tipo)==0){
		 $sql="INSERT INTO cnmd02_tablas_tipo VALUES ('$cod_tipo','".$deno."')";
		   $sw=$this->cnmd02_tablas_tipo->execute($sql);
		   if($sw>1){
		   		$this->set('Message_existe','Registro exitoso');
		   		$this->index();
		   		$this->render('index');
		   }else{
		   		$this->set('errorMessage','no pudo registrarse, intente nuevamente');
		   }
	}else{
		$this->set('errorMessage','el código ya se encuentra registrado');
		$this->index();
		$this->render('index');
	}

}else{
	$this->set('errorMessage','DEBE INGRESAR UNA DENOMINACION');
	$this->index();
	$this->render('index');
}


 }//guardar



 function modificar($cod=null,$id=null){
 	$this->layout="ajax";

    $v=$this->cnmd02_tablas_tipo->execute("SELECT denominacion FROM cnmd02_tablas_tipo where cod_tabla=".$cod);
 	$this->set('cod',$cod);
 	$this->set('i',$id);
 	$this->set('denominacion',$v[0][0]["denominacion"]);

 }//fin modificar


 function guardar_modificar($cod=null,$id=null){
	$this->layout="ajax";
	$cod_tipo=$this->data['cnmp02_tablas_tipo']['cod_tipo'];
	$deno=$this->data['cnmp02_tablas_tipo']['denominacion'.$id];

if(!empty($deno)){
	$v=$this->cnmd02_tablas_tipo->execute("update cnmd02_tablas_tipo set denominacion='".$deno."' where cod_tabla=".$cod);
	if($v > 0){
		$this->set('Message_existe','EL REGISTRO SE MODIFICO EXITOSAMENTE');
	}else{
		$this->set('errorMessage','NO SE PUDO MODIFICAR');
	}
}else{
	$this->set('errorMessage','debe ingresar la denominacion');
}
	$this->index();
	$this->render('index');
 }//guardar modificar




 function eliminar($cod_tipo=null){
	$this->layout="ajax";
	if(!$this->cnmd02_tablas_grado_paso->FindAll("cod_tabla=".$cod_tipo)){
			   $sql="DELETE FROM cnmd02_tablas_tipo WHERE cod_tabla=".$cod_tipo;
			   if($this->cnmd02_tablas_tipo->execute($sql)>1){
			  		 $this->set('Message_existe','EL REGISTRO SE ELIMINO CON EXITO');
			   }else{
			   		$this->set('errorMessage','LO SIENTO, EL REGISTRO NO PUDO SER ELIMINADO');
			   }
	}else{
		$this->set('errorMessage','NO PUEDE SER ELIMINADO, TIENE REGISTRADO GRADOS Y PASOS');
	}
	$v=$this->cnmd02_tablas_tipo->execute("SELECT * FROM cnmd02_tablas_tipo ORDER BY cod_tabla DESC");

	if($v!=null){
		$cod_tipo=$v[0][0]["cod_tabla"];
		$cod_tipo = $cod_tipo =="" ? 1 : $cod_tipo+1;
	}else{
		$cod_tipo=1;
	}

	$this->set('num',$cod_tipo);
//		   $this->index();
//		   $this->render("index");

 }//eliminar




 function cancelar(){
 	$this->layout="ajax";
    $this->index();
	$this->render("index");

 }//fin cancelar










 function denominacion_grado($g = null){
	$this->layout="ajax";
		$v=$this->cnmd02_deno_grado->execute("SELECT grado FROM cnmd02_deno_grado ORDER BY grado DESC LIMIT 1;");

		if($v!=null){
			$cod_tipo=$v[0][0]["grado"];
			$cod_tipo = $cod_tipo =="" ? 1 : $cod_tipo+1;
		}else{
			$cod_tipo=1;
		}
		$this->set('cod_tipo',$cod_tipo);
		$datos=$this->cnmd02_deno_grado->findAll('ORDER BY grado ASC');
		if($datos){
			$this->set('datos',$datos);
		}else{
			$this->set('datos','');
		}

		if($g == 'g' && $cod_tipo > 8){
			$this->set('errorMessage','LO SIENTO NO SE PUEDE REGISTRAR. EL LIMITE PARA LA DENOMINACI&Oacute;N DE GRADOS HA SIDO ALCANZADO.');
		}
 }//fin denominacion_grado


function guardar2(){
	$this->layout="ajax";
    $cod_tipo=$this->data['cnmp02_tablas_tipo']['cod_tipo'];
	$deno=$this->data['cnmp02_tablas_tipo']['denominacion'];
if(!empty($deno)){
	if((int)$cod_tipo < 9){
	if($this->cnmd02_deno_grado->findCount("grado=".$cod_tipo)==0){
		 $sql="INSERT INTO cnmd02_deno_grado VALUES ('$cod_tipo','".$deno."')";
		   $sw=$this->cnmd02_deno_grado->execute($sql);
		   if($sw>1){
		   		$this->set('Message_existe','Registro exitoso');
        		echo "<script>
        				document.getElementById('denominacion').value='';
	        	</script>";
		   		$this->denominacion_grado();
		   		$this->render('denominacion_grado');
		   }else{
		   		$this->set('errorMessage','no pudo registrarse, intente nuevamente');
		   }
	}else{
		$this->set('errorMessage','el código ya se encuentra registrado');
		$this->denominacion_grado();
		$this->render('denominacion_grado');
	}
	}else{
		$this->set('errorMessage','LO SIENTO EL LIMITE PARA LA DENOMINACI&Oacute;N DE GRADOS HA SIDO ALCANZADO.');
		$this->denominacion_grado();
		$this->render('denominacion_grado');
	}
}else{
	$this->set('errorMessage','DEBE INGRESAR UNA DENOMINACI&Oacute;N');
	$this->denominacion_grado('g');
	$this->render('denominacion_grado');
}
}//guardar



 function modificar2($cod=null,$id=null){
 	$this->layout="ajax";

    $v=$this->cnmd02_deno_grado->execute("SELECT denominacion FROM cnmd02_deno_grado where grado=".$cod);
 	$this->set('cod',$cod);
 	$this->set('i',$id);
 	$this->set('denominacion',$v[0][0]["denominacion"]);

 }//fin modificar


 function guardar_modificar2($cod=null,$id=null){
	$this->layout="ajax";
	$cod_tipo=$this->data['cnmp02_tablas_tipo']['cod_tipo'];
	$deno=$this->data['cnmp02_tablas_tipo']['denominacion'.$id];

if(!empty($deno)){
	$v=$this->cnmd02_deno_grado->execute("update cnmd02_deno_grado set denominacion='".$deno."' where grado=".$cod);
	if($v > 0){
		$this->set('Message_existe','EL REGISTRO SE MODIFICO EXITOSAMENTE');
	}else{
		$this->set('errorMessage','NO SE PUDO MODIFICAR');
	}
}else{
	$this->set('errorMessage','debe ingresar la denominaci&oacute;n');
}
	$this->denominacion_grado();
	$this->render('denominacion_grado');
 }//guardar modificar


 function eliminar2($cod_tipo=null){
	$this->layout="ajax";
	$v=$this->cnmd02_deno_grado->execute("SELECT grado FROM cnmd02_deno_grado ORDER BY grado DESC;");
	if(!$this->cnmd02_tablas_grado_paso->findAll("grado=".$cod_tipo, null, null, 1)){
	if($cod_tipo < count($v)){
		$this->set('errorMessage','LO SIENTO, NO SE PUEDE ELIMINAR DEBIDO A QUE HAY GRADOS SUPERIORES A ESTE.');
	}else{
			   $sql="DELETE FROM cnmd02_deno_grado WHERE grado=".$cod_tipo;
			   if($this->cnmd02_deno_grado->execute($sql)>1){
			  		 $this->set('Message_existe','EL REGISTRO SE ELIMINO CON EXITO');
			   }else{
			   		$this->set('errorMessage','LO SIENTO, EL REGISTRO NO PUDO SER ELIMINADO');
			   }
	}
	}else{
		$this->set('errorMessage','NO PUEDE SER ELIMINADO, TIENE REGISTRADO GRADOS Y PASOS');
	}

	if($v!=null){
		$cod_tipo=$v[0][0]["grado"];
		$cod_tipo = $cod_tipo =="" ? 1 : $cod_tipo+1;
	}else{
		$cod_tipo=1;
	}

	$this->set('num',$cod_tipo);
    $this->denominacion_grado();
    $this->render("denominacion_grado");
 }//eliminar


 function cancelar2(){
 	$this->layout="ajax";
    $this->denominacion_grado();
	$this->render("denominacion_grado");

 }//fin cancelar

} // FIN CLASS
?>
