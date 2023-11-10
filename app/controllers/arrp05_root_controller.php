<?php
/*
 * Fecha: 06/07/2007
 *
 * Por Erisk Aragol
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */

 class Arrp05RootController extends AppController{

	var $name = 'arrp05_root';
	var $uses = array('arrd01','arrd02','arrd03','arrd04','arrd05','Usuario');
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

	$republica=$this->arrd01->generateList(null,'cod_presi ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.denominacion');
	if($republica!=null){
		$this->concatena($republica,'republica');
	}else{
		$this->set('republica',array());
	}

}


function select3($opcion=null,$var=null){
	$this->layout="ajax";
	if($var!=''){
		switch($opcion){
			case 'estado':
				$this->set('no','');
				$this->set('SELECT','tipo');
				$this->set('codigo','estado');
				$this->set('seleccion','');
				$this->set('n',2);
				$this->Session->write('presi',$var);
				$cond =" cod_presi=".$var;
				$lista=  $this->arrd02->generateList($cond, 'cod_entidad ASC', null, '{n}.arrd02.cod_entidad', '{n}.arrd02.denominacion');
				$this->concatena($lista, 'vector');
			break;
			case 'tipo':
				$this->set('no','');
				$this->set('SELECT','inst');
				$this->set('codigo','tipo');
				$this->set('seleccion','');
				$this->set('n',3);
				$this->Session->write('entidad',$var);
				$cod_presi=$this->Session->read('presi');
				$cond =" cod_presi=".$cod_presi;
				$lista=  $this->arrd03->generateList($cond, 'cod_tipo_inst ASC', null, '{n}.arrd03.cod_tipo_inst', '{n}.arrd03.denominacion');
				$this->concatena($lista, 'vector');
			break;
			case 'inst':
				$this->set('no','no');
				$this->set('SELECT','inst');
				$this->set('codigo','inst');
				$this->set('seleccion','');
				$this->set('n',4);
				$this->Session->write('tipo',$var);
				$cod_presi=$this->Session->read('presi');
				$cod_entidad=$this->Session->read('entidad');
				$cond =" cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$var";
				$lista=  $this->arrd04->generateList($cond, 'cod_inst ASC', null, '{n}.arrd04.cod_inst', '{n}.arrd04.denominacion');
				$this->concatena($lista, 'vector');
			break;
		}//fin switch
	}
}//fin select3



function mostrar($opcion=null,$var=null){
	$this->layout="ajax";
	$cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	if($var!=''){
		switch($opcion){
			case 'republica':
				$this->set('si','si');
				$this->set('codigo','republica');
				$this->set('valor',$var);
			break;
			case 'estado':
				$this->set('si','si');
				$this->set('codigo','estado');
				$this->set('valor',$var);
			break;
			case 'tipo':
				$this->set('si','si');
				$this->set('codigo','tipo');
				$this->set('valor',$var);
			break;
			case 'inst':
				$this->set('si','si');
				$this->set('codigo','inst');
				$this->set('valor',$var);
			break;
			case 'deno_republica':
				$deno_estado = $this->arrd01->field('denominacion', $conditions = "cod_presi=".$var, $order ="cod_presi ASC");
				$this->set('denomi', $deno_estado);
				$this->set('denominacion',$opcion);
				 echo "<script>";
				 	echo "document.getElementById('cod_estadox').value='';";
					echo "document.getElementById('deno_estadox').value='';";
					echo "document.getElementById('cod_tipox').value='';";
					echo "document.getElementById('deno_tipox').value='';";
					echo "document.getElementById('codigo').value='';";
					echo "document.getElementById('denominacion').value='';";
				 echo "</script>";
			break;
			case 'deno_estado':
				$cod_presi=$this->Session->read('presi');
				$deno_estado = $this->arrd02->field('denominacion', $conditions = "cod_presi=".$cod_presi." and cod_entidad='$var'", $order ="cod_entidad ASC");
				$this->set('denomi', $deno_estado);
				$this->set('denominacion',$opcion);
				  echo "<script>";
					echo "document.getElementById('cod_tipox').value='';";
					echo "document.getElementById('deno_tipox').value='';";
					echo "document.getElementById('codigo').value='';";
					echo "document.getElementById('denominacion').value='';";
				 echo "</script>";
			break;
			case 'deno_tipo':
				$cod_presi=$this->Session->read('presi');
				$deno_municipio = $this->arrd03->field('denominacion', $conditions = "cod_presi=".$cod_presi." and cod_tipo_inst='$var'", $order ="cod_tipo_inst ASC");
				$this->set('denomi', $deno_municipio);
				$this->set('denominacion',$opcion);
				 echo "<script>";
				    echo "document.getElementById('cod_instx').value='';";
					echo "document.getElementById('deno_instx').value='';";
					echo "document.getElementById('codigo').value='';";
					echo "document.getElementById('denominacion').value='';";
				 echo "</script>";
			break;
			case 'deno_inst':
				$cod_presi=$this->Session->read('presi');
				$cod_entidad=$this->Session->read('entidad');
				$cod_tipo_inst=$this->Session->read('tipo');
				$deno_inst = $this->arrd04->field('denominacion', $conditions = "cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$var", $order ="cod_inst ASC");
				$this->set('denomi', $deno_inst);
				$this->set('denominacion',$opcion);
				 echo "<script>";
					echo "document.getElementById('codigo').value='01';";
					echo "document.getElementById('denominacion').value='';";
				 echo "</script>";
			break;
		}// fin switch
	}else{
		$this->set('si','no');
	}
}// fin mostrar


function vacio(){
	$this->layout="ajax";
}

function datos($cod_inst=null){
	$this->layout="ajax";

	if(isset($_SESSION['presi']) && isset($_SESSION['entidad']) && isset($_SESSION['tipo']) && $cod_inst!=''){
		$cod_presi=$this->Session->read('presi');
		$cod_entidad=$this->Session->read('entidad');
		$cod_tipo_inst=$this->Session->read('tipo');
		$datos=$this->arrd05->execute("select * from arrd05 where cod_presi='$cod_presi' and cod_entidad='$cod_entidad' and cod_tipo_inst='$cod_tipo_inst' and cod_inst=$cod_inst order by cod_dep asc");
		if($datos!=null){
			$this->set('datos',$datos);
		}else{
			$this->set('datos',null);
		}

		echo "<script>document.getElementById('codigo').value='';</script>";
		echo "<script>document.getElementById('denominacion').value='';</script>";
	}else{
		$this->set('datos',null);
	}
}



function datos2($cod_presi=null,$entidad=null,$cod_tipo_inst=null,$cod_inst=null){
	$this->layout="ajax";

	if($cod_tipo_inst!=''){
		$datos=$this->arrd05->execute("select * from arrd05 where cod_presi='$cod_presi' and cod_entidad='$entidad' and cod_tipo_inst='$cod_tipo_inst' and cod_inst=$cod_inst order by cod_dep asc");
		if($datos!=null){
			$this->set('datos',$datos);
		}else{
			$this->set('datos',null);
		}

		echo "<script>document.getElementById('codigo').value='';</script>";
		echo "<script>document.getElementById('denominacion').value='';</script>";
	}else{
		$this->set('datos',null);
	}
}



function guardar(){
	$this->layout="ajax";
	if(empty($this->data['arrp00']['cod_republica'])){
		$this->set('errorMessage', 'Debe seleccionar la república');
	}else if(empty($this->data['arrp00']['cod_estado'])){
		$this->set('errorMessage', 'Debe seleccionar el estado');
	}else if(empty($this->data['arrp00']['cod_tipo'])){
		$this->set('errorMessage', 'Debe seleccionar el tipo de institución');
	}else if(empty($this->data['arrp00']['cod_inst'])){
		$this->set('errorMessage', 'Debe seleccionar la institución');
	}else if(empty($this->data['arrp00']['codigo'])){
		$this->set('errorMessage', 'Debe ingresar el código de la dependencia');
	}else if(empty($this->data['arrp00']['denominacion'])){
		$this->set('errorMessage', 'Debe ingresar la denominación de la institución');
	}else{
		$cod_presi=$this->data['arrp00']['cod_republica'];
		$cod_estado=$this->data['arrp00']['cod_estado'];
		$cod_tipo=$this->data['arrp00']['cod_tipo'];
		$cod_inst=$this->data['arrp00']['cod_inst'];
		$codigo=1;//$this->data['arrp00']['codigo'];
		$denominacion=$this->data['arrp00']['denominacion'];
		$datos=$this->arrd05->execute("select * from arrd05 where cod_presi='$cod_presi' and cod_entidad='$cod_estado' and cod_tipo_inst='$cod_tipo' and cod_inst='$cod_inst' and cod_dep=$codigo order by cod_dep asc");
		if($datos==null){
			 $sql = "INSERT INTO arrd05 VALUES ('$cod_presi','$cod_estado','$cod_tipo','$cod_inst','$codigo','$denominacion','','',1,1);";
		   	 $sw=$this->arrd05->execute($sql);
		   	 if($sw>1){
		   	 	$this->arrd05->execute("SELECT crear_modulos_institucion($cod_presi, $cod_estado, $cod_tipo, $cod_inst);");
				$this->set('Message_existe', 'REGISTRO EXITOSO');
				echo" <script> ver_documento('/arrp05_root/datos2/$cod_presi/$cod_estado/$cod_tipo/$cod_inst','grilla'); </script>";
	   		}else{
	   			$this->set('errorMessage', 'INSERCI&Oacute;N FALLIDA DE LOS DATOS');
	   		}
		}else{
			$this->set('errorMessage', 'El registro ya existe');
		}
	}
}




function modificar($cod_presi=null,$cod_estado=null,$cod_tipo=null,$cod_inst=null,$cod_dep=null,$i=null){
	$this->layout="ajax";

	$datos=$this->arrd05->execute("select * from arrd05 where cod_presi='$cod_presi' and cod_entidad='$cod_estado' and cod_tipo_inst='$cod_tipo' and cod_inst='$cod_inst' and cod_dep=$cod_dep order by cod_dep asc");
	$this->set('datos',$datos);

	$this->set('k',$i);


}



function guardar_modificar($cod_presi=null,$cod_estado=null,$cod_tipo=null,$cod_inst=null,$cod_dep=null,$i=null){
	$this->layout="ajax";

	if(empty($this->data['arrp00']['denominacion'.$i])){
		$this->set('errorMessage', 'Debe ingresar la denominación de la institución');

	}else{
		$codigo=$this->data['arrp00']['codigo'.$i];
		$denominacion=$this->data['arrp00']['denominacion'.$i];

		 $sql = "update arrd05 set denominacion='".$denominacion."' where cod_presi='$cod_presi' and cod_entidad='$cod_estado' and cod_tipo_inst='$cod_tipo' and cod_inst='$cod_inst' and cod_dep=$cod_dep";
	   	 $sw=$this->arrd05->execute($sql);
	   	 if($sw>1){
			$this->set('Message_existe', 'EL REGISTRO SE MODIFICO CON EXITO');
			echo" <script> ver_documento('/arrp05_root/datos2/$cod_presi/$cod_estado/$cod_tipo/$cod_inst','grilla'); </script>";
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
