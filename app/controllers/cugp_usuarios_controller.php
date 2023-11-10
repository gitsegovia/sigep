<?php
 class CugpUsuariosController extends AppController{
 	var $name = "cugp_usuarios";
	var $uses = array('cugd_usuarios');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap','Infogob');

function beforeFilter(){
}//fin before filter

function registro($var=null){

	if(isset($var) && $var=='index'){
		$this->layout = "infogobierno";
	}else{
		$this->layout = "ajax";
	}

}//fin index

function guardar(){
	$this->layout = "ajax";
	if(!empty($this->data['cugp_usuarios']['email']) && !empty($this->data['cugp_usuarios']['password']) && !empty($this->data['cugp_usuarios']['cedula']) && !empty($this->data['cugp_usuarios']['repetir_password']) && !empty($this->data['cugp_usuarios']['apellidos'])){
		$email		= $this->data['cugp_usuarios']['email'];
		$password	= $this->data['cugp_usuarios']['password'];
		$password2	= $this->data['cugp_usuarios']['repetir_password'];
		$apellidos	= $this->data['cugp_usuarios']['apellidos'];
		$nombres	= $this->data['cugp_usuarios']['nombres'];
		$cedula		= $this->data['cugp_usuarios']['cedula'];
		$pregunta_secreta  = $this->data['cugp_usuarios']['pregunta_secreta'];
		$respuesta_secreta = $this->data['cugp_usuarios']['respuesta_secreta'];
		$cedula = str_replace(".","",$cedula);
		$cedula = str_replace(" ","",trim($cedula));
		$cedula = str_replace(",","",$cedula);


	if($pregunta_secreta!='' && $pregunta_secreta=='00-OTRA'){ // el usuario escribio una pregunta
		$otra_pregunta_secreta = $this->data['cugp_usuarios']['otra_pregunta_secreta'];
	}else{
		$otra_pregunta_secreta = isset($this->data['cugp_usuarios']['otra_pregunta_secreta']) ? $this->data['cugp_usuarios']['otra_pregunta_secreta'] : null;
	}

	if(!empty($otra_pregunta_secreta) && isset($this->data['cugp_usuarios']['otra_pregunta_secreta'])){ // validando y asignando la pregunta secreta escrita
		$pregunta_secreta = $otra_pregunta_secreta;
	}

		$enc_usua = $this->cugd_usuarios->findCount("cedula_identidad='$cedula'");
		if($enc_usua==0){

		$sql="insert into cugd_usuarios (correo_electronico,password,apellidos,nombres,cedula_identidad,pregunta_secreta,respuesta_secreta)";
		$sql.="values ('".$email."','".$password."','".$apellidos."','".$nombres."','$cedula','".$pregunta_secreta."','".$respuesta_secreta."')";
		if(up($password)==up($password2)){
            $r=$this->cugd_usuarios->execute($sql);
			if($r>1){
				$this->set('msj', array('Datos registrados exitosamente','exito'));
			}else{
				$this->set('msj', array('Registro no procesado - intente mas tarde','error'));
			}

	        $this->registro('index');
	        $this->render("registro");
	        $this->set('mostrar',true);
	        $_SESSION['REGISTRO_INFOGOBIERNO']=$this->data['cugp_usuarios']=array('email'=>'','password'=>'','repetir_password'=>'','cedula'=>'','apellidos'=>'','nombres'=>'');


		}else{
			$this->set('mostrar',true);
			$_SESSION['REGISTRO_INFOGOBIERNO']=$this->data['cugp_usuarios'];
			$this->set('msj', array('Las Claves No coinciden','error'));
        	$this->registro('index');
        	$this->render("registro");
		}

	}else{
		$this->set('mostrar',true);
		$_SESSION['REGISTRO_INFOGOBIERNO']=$this->data['cugp_usuarios'];
		$this->set('msj', array('Ya este usuario se encuentra registrado','error'));
        $this->registro('index');
        $this->render("registro");
	}

	}else{
		$this->set('mostrar',true);
		$_SESSION['REGISTRO_INFOGOBIERNO']=$this->data['cugp_usuarios'];
		$this->set('msj', array('Faltan campos por llenar','error'));
        $this->registro('index');
        $this->render("registro");
	}


}

function verificar($email){
	$this->layout = "ajax";
	$c=$this->cugd_usuarios->findCount("correo_electronico=upper('".$email."')");
	if($c != 0){
		$this->set('msj', array('Disculpe el E-mail ingresado ya se encuentra registrado','error'));
		echo'<script>';
        	echo "document.getElementById('email').value=''   ;";
     	echo'</script>';
	}else{

	}
	$this->nada();
	$this->render("nada");
}
function nada(){
	$this->layout = "ajax";
}


function seleccion_pregunta($pregunta=null){
	$this->layout = "ajax";
	if($pregunta!=null && $pregunta!='' && $pregunta=='00-OTRA'){

		$this->set('escribir',true);

	}else{

		$this->set('escribir',false);

	}
}

}