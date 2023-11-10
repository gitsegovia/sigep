<?php
/*
 * Fecha: 06/07/2007
 *
 * Por Erisk Aragol
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */

 class ModulosSistemaRootController extends AppController{

	var $name = 'modulos_sistema_root';
	var $uses = array('arrd01','arrd02','arrd03','arrd04','arrd05','Usuario','modulos_sistema');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');

function checkSession(){

				if (!$this->Session->check('Root_session')){
						$this->redirect('/root/salir/');
						exit();
				}else{
					if($this->Session->read('Root_session')!="VISION_INTEGRAL"){
						$this->redirect('/root/salir/');
						 exit();
					}
				}
}//fin checksession




function beforeFilter(){
    $this->checkSession();

 }



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




function index() {
   	    $this->layout = "ajax";
        $ultimo=$this->arrd05->execute("select orden_ubicacion from modulos_sistema order by orden_ubicacion desc limit 1");
	    $this->set('orden_ubicacion',$ultimo[0][0]['orden_ubicacion']);
	    $datos=$this->arrd05->execute("select * from modulos_sistema order by orden_ubicacion asc");
		if($datos!=null){
			$this->set('datos',$datos);
		}else{
			$this->set('datos',null);
		}
}


function vacio(){
	$this->layout="ajax";
}

function datos(){
	    $this->layout="ajax";
        $ultimo=$this->arrd05->execute("select orden_ubicacion from modulos_sistema order by orden_ubicacion desc limit 1");
		$this->set('orden_ubicacion',$ultimo[0][0]['orden_ubicacion']);
		$datos=$this->arrd05->execute("select * from modulos_sistema order by orden_ubicacion asc");
		if($datos!=null){
			$this->set('datos',$datos);
		}else{
			$this->set('datos',null);
		}

}

function guardar(){
	$this->layout="ajax";
	if(empty($this->data['modulos_sistema']['cod_modulo'])){
		$this->set('errorMessage', 'Debe ingresar el código del módulo');
	}else if(empty($this->data['modulos_sistema']['denominacion'])){
		$this->set('errorMessage', 'Debe ingresar la denominación del módulo');
	}else{
		$c=$this->modulos_sistema->findCount("cod_modulo='".$this->data['modulos_sistema']['cod_modulo']."'");
        if($c==0){
			if($this->modulos_sistema->save($this->data)){
				$this->set('Message_existe', 'REGISTRO EXITOSO');
		   	}else{
		   		$this->set('errorMessage', 'INSERCI&Oacute;N FALLIDA DE LOS DATOS');
		   	}
        }
	}
	$this->datos();
	$this->render('datos');
}

function orden ($tipo,$cod_modulo,$orden) {
   $this->layout="ajax";
   if($tipo=="subir"){
   	    $sql="UPDATE modulos_sistema SET orden_ubicacion=$orden WHERE orden_ubicacion=$orden-1;";
        $sql.="UPDATE modulos_sistema SET orden_ubicacion=$orden-1 WHERE cod_modulo='$cod_modulo';";
        $this->modulos_sistema->execute($sql);
   }else if($tipo=="bajar"){
        $sql="UPDATE modulos_sistema SET orden_ubicacion=$orden WHERE orden_ubicacion=$orden+1;";
        $sql.="UPDATE modulos_sistema SET orden_ubicacion=$orden+1 WHERE cod_modulo='$cod_modulo';";
        $this->modulos_sistema->execute($sql);
   }
    $this->datos();
	$this->render('datos');
}//fin funcion orden



function modificar($cod_modulo=null,$i=null){
	$this->layout="ajax";

	$datos=$this->arrd05->execute("select * from modulos_sistema where cod_modulo='$cod_modulo'");
	$this->set('datos',$datos);
	$this->set('k',$i);
}



function guardar_modificar($cod_modulo=null,$i=null){
	$this->layout="ajax";

	if(empty($this->data['modulos_sistema']['denominacion'.$i])){
		$this->set('errorMessage', 'Debe ingresar la denominación');
	}else{
		$codigo=$this->data['modulos_sistema']['cod_modulo'.$i];
		$denominacion=$this->data['modulos_sistema']['denominacion'.$i];

		 $sql = "UPDATE modulos_sistema set denominacion='".$denominacion."' where cod_modulo='$cod_modulo'";
	   	 $sw=$this->arrd05->execute($sql);
	   	 if($sw>1){
			$this->set('Message_existe', 'EL REGISTRO SE MODIFICO CON EXITO');
			echo" <script> ver_documento('/modulos_sistema_root/datos','grilla'); </script>";
   		}else{
   			$this->set('errorMessage', 'EL DATO NO PUDO SER MODIFICADO');
   		}
	}

}

function eliminar($cod_presi=null,$cod_estado=null,$cod_tipo=null,$cod_inst=null,$cod_dep=null){
	$this->layout="ajax";

	 $sql = "delete from arrd05 where cod_presi='$cod_presi' and cod_entidad='$cod_estado' and cod_tipo_inst='$cod_tipo' and cod_inst='$cod_inst' and cod_dep=$cod_dep";
   	 $sw=$this->arrd03->execute($sql);
   	 if($sw>1){
		$this->set('Message_existe', 'EL REGISTRO SE ELIMINO CON EXITO');
		echo" <script> ver_documento('/arrp05_root/datos2/$cod_presi/$cod_estado/$cod_tipo/$cod_inst','grilla'); </script>";
	}else{
		$this->set('errorMessage', 'EL DATO NO PUDO SER ELIMINADO');
	}
}

function cancelar($cod_presi=null,$cod_estado=null,$cod_tipo=null,$cod_inst=null){
	$this->layout="ajax";
	echo" <script> ver_documento('/arrp05_root/datos2/$cod_presi/$cod_estado/$cod_tipo/$cod_inst','grilla'); </script>";
}



function crear_usuario ($cod_presi=null,$cod_estado=null,$cod_tipo=null,$cod_inst=null,$cod_dep=null) {
   $this->layout="ajax";
   $this->set('cod_presi',$cod_presi);
   $this->set('cod_entidad',$cod_estado);
   $this->set('cod_tipo_inst',$cod_tipo);
   $this->set('cod_inst',$cod_inst);
   $this->set('cod_dep',$cod_dep);
   $c=$this->Usuario->findCount("cod_presi=$cod_presi and cod_entidad=$cod_estado and cod_tipo_inst=$cod_tipo and cod_inst=$cod_inst and cod_dep=$cod_dep and modulo='0'");
   if($c!=0){
   	   $r=$this->Usuario->findAll("cod_presi=$cod_presi and cod_entidad=$cod_estado and cod_tipo_inst=$cod_tipo and cod_inst=$cod_inst and cod_dep=$cod_dep and modulo='0'");
   	   $this->set('data',$r);
   }
}//fin funcion crear_usuario

function verificar_usuario ($username=null) {
   $this->layout="ajax";
   $username=up($username);
   $c=$this->Usuario->findCount("username='$username'");
   if($c!=0){
   	  echo '<div style="color:red;font-size:14pt;text-align:center;">El usuario indicado ya existe, intente con otro</div>';
   	  echo '<script>$("cambiar_clave_id").disabled="disabled";</script>';
   }else{
   	 echo '<script>$("cambiar_clave_id").disabled="";</script>';
   }
}//fin funcion verificar_usuario

function guardar_usuario () {
   $this->layout="ajax";
     extract($this->data['usuarios']);
       $username=up($usuario);
       if(strlen($username)>=6){
		   $c=$this->Usuario->findCount("username='$username'");
		   if($c==0){
		   	  if(strlen($clave1)>=6){
			   	  if(up($clave1)==up($clave2)){
		              if($cedula_identidad!=''){
		                 if($responsable!=''){
		                     $sql="INSERT INTO usuarios(username, password, cod_presi, cod_entidad, cod_tipo_inst,cod_inst, cod_dep, modulo, funcionario, cedula_identidad, cod_dep_original,condicion_actividad) VALUES ('$username', '$clave1', $cod_presi, $cod_entidad, $cod_tipo_inst,$cod_inst, $cod_dep, '0','$responsable', $cedula_identidad, 1,1);";
		                     $sw=$this->Usuario->execute($sql);
		                     if($sw>1){
		                        $this->set('exito','Usuario registrado exitosamente');
		                     }else{
		                     	echo '<div style="color:red;font-size:14pt;text-align:center;">Disculpe, No se puedo registrar el usuario</div>';
		                     }
		                 }else{
		              	    echo '<div style="color:red;font-size:14pt;text-align:center;">Ingrese el responsable</div>';
		                 }
		              }else{
		              	 echo '<div style="color:red;font-size:14pt;text-align:center;">Ingrese la Cédula de Identidad</div>';
		              }
			   	  }else{
			   	  	 echo '<div style="color:red;font-size:14pt;text-align:center;">Las claves ingresadas no coinciden, por favor verifique</div>';
			   	  }
		      }else{
                  echo '<div style="color:red;font-size:14pt;text-align:center;">Longitud mínima para la clave es de 6 caracteres, por favor verifique</div>';
		      }
		   }

       }else{
           echo '<div style="color:red;font-size:14pt;text-align:center;">Longitud mínima del usuario es de 6 caracteres, por favor verifique</div>';
       }
	   $this->render('verificar_usuario');
}//fin funcion guardar_usuario


function modificar_usuario ($usuario1) {
   $this->layout="ajax";
     extract($this->data['usuarios']);
       $username=up($usuario);
       $usuario1=up($usuario1);
       if(strlen($username)>=6 && $usuario1==$username){
		   $c=$this->Usuario->findCount("username='$username'");
		   if($c!=0){
		   	  if(strlen($clave1)>=6){
			   	  if(up($clave1)==up($clave2)){
		              if($cedula_identidad!=''){
		                 if($responsable!=''){
		                     $sql="UPDATE usuarios SET password='$clave1', funcionario='$responsable', cedula_identidad=$cedula_identidad WHERE username='$username'";
		                     $sw=$this->Usuario->execute($sql);
		                     if($sw>1){
		                        $this->set('exito','Usuario actualizado exitosamente');
		                     }else{
		                     	echo '<div style="color:red;font-size:14pt;text-align:center;">Disculpe, No se puedo actualizar el usuario</div>';
		                     }
		                 }else{
		              	    echo '<div style="color:red;font-size:14pt;text-align:center;">Ingrese el responsable</div>';
		                 }
		              }else{
		              	 echo '<div style="color:red;font-size:14pt;text-align:center;">Ingrese la Cédula de Identidad</div>';
		              }
			   	  }else{
			   	  	 echo '<div style="color:red;font-size:14pt;text-align:center;">Las claves ingresadas no coinciden, por favor verifique</div>';
			   	  }
		      }else{
                  echo '<div style="color:red;font-size:14pt;text-align:center;">Longitud mínima para la clave es de 6 caracteres, por favor verifique</div>';
		      }
		   }

       }else{
           echo '<div style="color:red;font-size:14pt;text-align:center;">Longitud mínima del usuario es de 6 caracteres, por favor verifique</div>';
       }
	   $this->render('verificar_usuario');
}//fin funcion guardar_usuario


}//FIN DEL CONTROLADOR
?>
