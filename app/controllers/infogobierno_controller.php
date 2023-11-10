<?php

class InfogobiernoController extends AppController
{
    var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap', 'Fpdf', 'Infogob');
    var $uses = array('cugd_usuarios', 'cnmd06_datos_personales', 'cnmd06_fichas', 'cpcd02', 'v_relacion_orden_compra_infogobierno', 'v_relacion_obras_infogobierno', 'v_relacion_obras_infogobierno');

    function beforeFilter(){

    }


	function index(){
	    	$this->layout = "infogobierno";
	    	$msj=isset($_SESSION['msj'])?$_SESSION['msj']:'';
	    	$this->set('msj',$msj);
	    	$_SESSION['msj']="";
	}//fin

    function registro () {
		$this->layout="ajax";
	}

    function entrar () {
		$this->layout="infogobierno";
		if(isset($this->data)){
           if(!empty($this->data['cugd_usuarios']['correo_electronico']) && !empty($this->data['cugd_usuarios']['password'])){
               $correo=$this->data['cugd_usuarios']['correo_electronico'];
	           $pass=$this->data['cugd_usuarios']['password'];
	           $count_usuarios=$this->cugd_usuarios->findCount(" upper(correo_electronico)=upper('".$correo."') AND upper(password)=upper('".$pass."')");
	           if($count_usuarios!=0){
	           	  $datos=$this->cugd_usuarios->findAll(" upper(correo_electronico)=upper('".$correo."') AND upper(password)=upper('".$pass."')");
                  $this->set('entrada_exitosa','verdadero');
	              $this->set('correo_electronico',$correo);
	              $this->Session->write('infogobierno',array('usuario'=>$correo,'entrada'=>date('d/m/Y'),'cedula_identidad'=>$datos[0]['cugd_usuarios']['cedula_identidad'],'nombres'=>$datos[0]['cugd_usuarios']['nombres'],'apellidos'=>$datos[0]['cugd_usuarios']['apellidos']));
                  $datos4=$this->cnmd06_fichas->findCount("cedula_identidad='".$datos[0]['cugd_usuarios']['cedula_identidad']."' and condicion_actividad!=6");

                  if($datos4==0){
                      $this->Session->write('pertenece',0);
                  }else{
                      $this->Session->write('pertenece',1);
                  }

                    $rif_cedula = $datos[0]['cugd_usuarios']['cedula_identidad'];
					$datos1     = $this->v_relacion_orden_compra_infogobierno->execute(" SELECT DISTINCT ano_orden_compra FROM v_relacion_orden_compra_infogobierno WHERE condicion_actividad = 1 and  upper(rif)=upper('".$rif_cedula."') ORDER BY ano_orden_compra ASC");
					if(count($datos1)!=0){
						$this->Session->write('OC_EXISTEN',1);
					}else{
						$this->Session->write('OC_EXISTEN',0);
					}


					$rif_cedula = $datos[0]['cugd_usuarios']['cedula_identidad'];
					$datos2     = $this->v_relacion_obras_infogobierno->execute(" SELECT DISTINCT ano_contrato_obra FROM v_relacion_obras_infogobierno WHERE condicion_actividad = 1 and upper(rif)=upper('".$rif_cedula."') ORDER BY ano_contrato_obra ASC");
					if(count($datos2)!=0){
						$this->Session->write('OB_EXISTEN',1);
					}else{
						$this->Session->write('OB_EXISTEN',0);
					}

					$rif_cedula = $datos[0]['cugd_usuarios']['cedula_identidad'];
					$datos3     = $this->v_relacion_obras_infogobierno->execute(" SELECT DISTINCT ano_contrato_servicio FROM v_relacion_servicio_infogobierno WHERE condicion_actividad = 1 and upper(rif)=upper('".$rif_cedula."') ORDER BY ano_contrato_servicio ASC");
					if(count($datos3)!=0){
						$this->Session->write('SR_EXISTEN',1);
					}else{
						$this->Session->write('SR_EXISTEN',0);
					}


	           }else{
	           	  $this->Session->write('msj',array('Datos Incorrectos!','error'));
	           	  $this->Session->write('pertenece',0);
	           	  $this->set('datos_incorrectos','verdadero');
	           }
           }else{
           	  $this->set('datos_incorrectos','verdadero');
           	  $this->Session->write('pertenece',0);
           }
		}else if($this->Session->check('infogobierno')){

		}
		$this->index();
		$this->render('index');
	}
	function cerrar_sesion () {
		$this->layout="infogobierno";
		$this->Session->delete('infogobierno');
		$this->Session->write('msj',array('Sesión cerrada!','exito'));
		$this->index();
        $this->render('index');
	}

	function vacio ($var=null) {
		$this->layout="ajax";
		if(isset($var) && $var !=null){
			$msj=array($var,'exito');
		}else{$msj="";}
		$this->set('msj',$msj);
	}

	function iniciar_sesion () {
		$this->layout="ajax";
			$msj=array('Inicie Sesión para acceder al contenido','error');
		$this->set('msj',$msj);
		$this->render('vacio');
	}

	function salir_todo () {
	   $this->layout="ajax";

	}

	function vision () {
	   $this->layout="ajax";

	}
	function mision () {
	   $this->layout="ajax";

	}

	function olvido_usuario ($var_accion = null) {
		$this->layout="ajax";

		if(isset($this->data['cugd_usuarios']['tipo_personalidad'])){
			$var_accion = $this->data['cugd_usuarios']['tipo_personalidad'];
		}

		$this->set('var_accion',$var_accion);


		if((int)$var_accion == 2){ // Proceso de recuperacion usuario natural

			$cedula = $this->data['cugd_usuarios']['cedula_identidad'];
			$fecha_nac = $this->data['cugd_usuarios']['fecha_nacimiento'];

			if(!empty($cedula) && !empty($fecha_nac)){
				$existe_ced = $this->cnmd06_datos_personales->findCount("cedula_identidad='$cedula'");
				if($existe_ced!=0){

					$existe_persona = $this->cnmd06_datos_personales->findCount("cedula_identidad='$cedula' and fecha_nacimiento='$fecha_nac'");
					if($existe_persona!=0){



						if(isset($this->data['cugd_usuarios']['respuesta_secreta'])){ // Verificando si esta la pregunta y respuesta secreta

							if(!empty($this->data['cugd_usuarios']['respuesta_secreta']) && $this->data['cugd_usuarios']['respuesta_secreta']!=""){

								if($this->data['cugd_usuarios']['respuesta_secreta'] == $this->data['cugd_usuarios']['respuesta_correcta']){ // Si la respuesta secreta es correcta


						$datos_usuario = $this->cugd_usuarios->findAll("cedula_identidad='$cedula'", null, null, 1);
						if(!empty($datos_usuario)){
							$msj=array(''.$datos_usuario[0]["cugd_usuarios"]["nombres"].' fue verificado correctamente. Conserve sus datos!','exito');
							$this->set('msj',$msj);
							$this->set('datos_usuario',$datos_usuario);
						}else{
							$msj=array('No se encuentra registrado como usuario en infogobierno. Registrese!','error');
							$this->set('msj',$msj);
						}



							}else{
								$msj=array('Respuesta Incorrecta!','error');
								$this->set('msj',$msj);
							}


							}else{
								$msj=array('Ingrese la Respuesta!','error');
								$this->set('msj',$msj);
							}




						}else{ // Buscando la pregunta y rspta secreta

							$pregunta_usuario = $this->cugd_usuarios->findAll("cedula_identidad='$cedula'", null, null, 1);
							if(!empty($pregunta_usuario[0]['cugd_usuarios']['pregunta_secreta']) && $pregunta_usuario[0]['cugd_usuarios']['pregunta_secreta']!=''){
								$this->set('pregunta_secreta',$pregunta_usuario[0]['cugd_usuarios']['pregunta_secreta']);
								$this->set('respuesta_secreta',$pregunta_usuario[0]['cugd_usuarios']['respuesta_secreta']);

							}else{ // El usuario No tiene pregunta registrada


								if(!empty($pregunta_usuario)){
									$msj=array(''.$pregunta_usuario[0]["cugd_usuarios"]["nombres"].' fue verificado correctamente. Conserve sus datos!','exito');
									$this->set('msj',$msj);
									$this->set('datos_usuario',$pregunta_usuario);
								}else{
									$msj=array('No se encuentra registrado como usuario en infogobierno. Registrese!','error');
									$this->set('msj',$msj);
								}

							}
						}



					}else{
						$msj=array('Datos Incorrectos . . .','error');
						$this->set('msj',$msj);
					}

				}else{
					$msj=array('La c&eacute;dula de identidad no existe en los registros','error');
					$this->set('msj',$msj);
				}
			}else{
				$msj=array('Ingrese la C&eacute;dula de Identidad y Fecha de nacimiento','error');
				$this->set('msj',$msj);
			}



		}else if((int)$var_accion == 3){ // Proceso de recuperacion usuario juridico




		}

		echo "<script>document.getElementById('bt_procesar').disabled=false;</script>";

	}

	function eliminar_cuenta ($var_accion = null, $correo = null) {
		$this->layout="ajax";

		if($correo != null){
			$swdelu = $this->cugd_usuarios->execute("DELETE FROM cugd_usuarios WHERE correo_electronico = '$correo';");
			if($swdelu >= 1){
				$msj=array('La cuenta '. $correo .' fue eliminada correctamente. Registre la nueva cuenta!','exito');
				$this->set('msj',$msj);
				echo "<script>ver_documento('/cugp_usuarios/registro','contenido');</script>";
			}else{
				$msj=array('No se pudo eliminar la cuenta de usuario. Intente nuevamente!','error');
				$this->set('msj',$msj);
				echo "<script>ver_documento('/infogobierno/olvido_usuario/1','contenido');</script>";
			}
		}
	}


}//fin class
?>