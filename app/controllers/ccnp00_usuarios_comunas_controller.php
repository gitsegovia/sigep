<?php
/*
 * Creado el 11/07/2008 a las 11:11:54 PM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */
 class Ccnp00UsuariosComunasController extends AppController{
	var $name = 'ccnp00_usuarios_comunas';
    var $uses = array('ccnd00','Usuario','cugd01_estados','cugd01_municipios','cugd01_parroquias','cugd01_centropoblados', 'ccnd01_concejo_comunal');
	var $helpers = array('Html','Ajax','Javascript', 'Sisap');


function checkSession(){
				if (!$this->Session->check('concejo_comunal')){
						$this->redirect('/salir');
						exit();
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

	$lista=  $this->cugd01_estados->generateList(" cod_republica=".$this->Session->read('SScodpresi'), 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
	$this->concatena($lista, 'estado');
 }//index


 function select3($opcion=null,$var=null){
	$this->layout="ajax";
	$cod_presi                =       $this->Session->read('SScodpresi');
	if($var!=''){
		switch($opcion){
			case 'municipio':
				$this->set('no','');
				$this->set('SELECT','parroquia');
				$this->set('codigo','municipio');
				$this->set('seleccion','');
				$this->set('n',3);
				$this->Session->write('cod1',$var);
				$cond =" cod_republica=".$cod_presi." and cod_estado=".$var;
				$lista=  $this->cugd01_municipios->generateList($cond, 'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
				$this->concatena($lista, 'vector');
				$this->set('anula','otros');
			break;
			case 'parroquia':
				$this->set('no','');
				$this->set('SELECT','centro_poblado');
				$this->set('codigo','parroquia');
				$this->set('seleccion','');
				$this->set('n',4);
				$this->Session->write('cod2',$var);
				$cod1=$this->Session->read('cod1');
				$cond =" cod_republica=".$cod_presi." and cod_estado=".$cod1." and cod_municipio=".$var;
				$lista=  $this->cugd01_parroquias->generateList($cond, 'cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
				$this->concatena($lista, 'vector');
				$this->set('anula','otros');
			break;
			case 'centro_poblado':
				$this->set('anula','otros');
				$this->set('no','');
				$this->set('SELECT','concejo_comunal');
				$this->set('codigo','centro_poblado');
				$this->set('seleccion','');
				$this->set('n',5);
				$this->Session->write('cod3',$var);
				$cod1=$this->Session->read('cod1');
				$cod2=$this->Session->read('cod2');
				$cond =" cod_republica=".$cod_presi." and cod_estado=".$cod1." and cod_municipio=".$cod2." and cod_parroquia=".$var;
				$lista=  $this->cugd01_centropoblados->generateList($cond, 'cod_centro ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
				$this->concatena($lista, 'vector');
			break;
			case 'concejo_comunal':
				$this->set('anula','otros');
				$this->set('no','');
				$this->set('SELECT','institucion');
				$this->set('codigo','concejo_comunal');
				$this->set('seleccion','');
				$this->set('n',6);
				$cod1=$this->Session->read('cod1');
				$cod2=$this->Session->read('cod2');
				$cod3=$this->Session->read('cod3');
				$this->Session->write('cod4',$var);
				$cond =" cod_republica=".$cod_presi." and cod_estado=".$cod1." and cod_municipio=".$cod2." and cod_parroquia=".$cod3." and cod_centro=".$var;
				$lista=  $this->ccnd01_concejo_comunal->generateList($cond, 'cod_concejo ASC', null, '{n}.ccnd01_concejo_comunal.cod_concejo', '{n}.ccnd01_concejo_comunal.denominacion');
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
			case 'deno_estado':
				$deno_estado = $this->cugd01_estados->field('denominacion', $conditions = "cod_republica=".$cod_presi." and cod_estado='$var'", $order ="cod_estado ASC");
				$this->set('denomi', $deno_estado);
				$this->set('denominacion',$opcion);
				 echo "<script>";
					echo "document.getElementById('deno_municipiox').value='';";
					echo "document.getElementById('deno_parroquiax').value='';";
					echo "document.getElementById('deno_centro_pobladox').value='';";
					echo "document.getElementById('deno_concejo_comunalx').value='';";
				 echo "</script>";
			break;
			case 'deno_municipio':
				$cod1=$this->Session->read('cod1');
				$deno_municipio = $this->cugd01_municipios->field('denominacion', $conditions = "cod_republica=".$cod_presi." and cod_estado='$cod1' and cod_municipio='$var'", $order ="cod_municipio ASC");
				$this->set('denomi', $deno_municipio);
				$this->set('denominacion',$opcion);
				echo "<script>";
					echo "document.getElementById('deno_parroquiax').value='';";
					echo "document.getElementById('deno_centro_pobladox').value='';";
					echo "document.getElementById('deno_concejo_comunalx').value='';";
				 echo "</script>";
			break;
			case 'deno_parroquia':
				$cod1=$this->Session->read('cod1');
				$cod2=$this->Session->read('cod2');
				$deno_parroquia = $this->cugd01_parroquias->field('denominacion', $conditions = "cod_republica=".$cod_presi." and cod_estado='$cod1' and cod_municipio='$cod2' and cod_parroquia='$var'", $order ="cod_parroquia ASC");
				$this->set('denomi', $deno_parroquia);
				$this->set('denominacion',$opcion);
				echo "<script>";
					echo "document.getElementById('deno_centro_pobladox').value='';";
					echo "document.getElementById('deno_concejo_comunalx').value='';";
				 echo "</script>";
			break;
			case 'deno_centro_poblado':
				$estado=$this->Session->read('estado');
				$cod1=$this->Session->read('cod1');
				$cod2=$this->Session->read('cod2');
				$cod3=$this->Session->read('cod3');
				$cond =" cod_republica=".$cod_presi." and cod_estado=".$cod1." and cod_municipio=".$cod2." and cod_parroquia=".$cod3." and cod_centro=".$var;
				$deno_banco = $this->cugd01_centropoblados->field('denominacion', $cond, $order ="cod_centro ASC");
				$this->set('denomi', $deno_banco);
				$this->set('denominacion',$opcion);
				echo "<script>";
					echo "document.getElementById('deno_concejo_comunalx').value='';";
				 echo "</script>";
			break;
			case 'deno_concejo_comunal':
				$estado=$this->Session->read('estado');
				$cod1=$this->Session->read('cod1');
				$cod2=$this->Session->read('cod2');
				$cod3=$this->Session->read('cod3');
				$cod4=$this->Session->read('cod4');
				$cond =" cod_republica=".$cod_presi." and cod_estado=".$cod1." and cod_municipio=".$cod2." and cod_parroquia=".$cod3." and cod_centro=".$cod4." and cod_concejo=".$var;
				$deno_banco = $this->ccnd01_concejo_comunal->field('denominacion', $cond, $order ="cod_concejo ASC");
				$this->set('denomi', $deno_banco);
				$this->set('denominacion',$opcion);
			break;
		}// fin switch
	}else{
		$this->set('si','no');
	}
}// fin mostrar


function busqueda_usuario($login=null){
	$this->layout="ajax";
	$login=strtoupper($login);
	$ver  = $this->ccnd00->findCount("  quitar_acentos(mayus_acentos(username::text)) = quitar_acentos(mayus_acentos('$login')) ");
	$ver1 = $this->Usuario->findCount(" quitar_acentos(mayus_acentos(username::text)) = quitar_acentos(mayus_acentos('$login')) ");

	if($ver!=0 || $ver1!=0){
		$this->set('errorMessage',"Este nombre de usuario ya existe registrado, intente con otro");

			   echo "<script>";
				                echo " document.getElementById('save').disabled='disabled'; ";
					            echo " document.getElementById('login').value=''; ";
					            echo " document.getElementById('login').focus();  ";
			   echo "</script>";

	}else{

		       echo "<script>document.getElementById('save').disabled=false;</script>";
	}

}// fin busqueda_usuario

function mensaje_clave(){
	$this->layout = "ajax";
}

function validar_cclave($var_cc=null) {
 	if($var_cc != null){

	/**
		$hash = password_hash($var_cc);
		// $hash = crypt($var_cc);
		// $hash = password_hash($var_cc, $algorithm, $options); cost=>10

    if (!password_verify($var_cc, $hash)) {
        //if (password_needs_rehash($hash)) {
            //$hash = password_hash($var_cc);
        //}

      $error_clave[0] = "No se pudo verificar la clave... Intente con otra!";
      $error_clave[1] = false;
    }else
    */

	$username = $this->Session->read('nom_usuario');

	if($var_cc == $username){
      $error_clave[0] = "La clave debe de ser diferente al nombre de usuario (Login)";
      $error_clave[1] = false;

	}else if(strlen($var_cc) < 6){
      $error_clave[0] = "La clave debe tener al menos 6 caracteres";
      $error_clave[1] = false;
   }

   else if(strlen($var_cc) > 25){
      $error_clave[0] = "La clave no puede tener m&aacute;s de 25 caracteres";
      $error_clave[1] = false;
   }

   else if (!preg_match('`[A-Za-z]`',$var_cc)){
      $error_clave[0] = "La clave debe tener al menos una letra (a-z)";
      $error_clave[1] = false;
   }

   else if (!preg_match('`[0-9]`',$var_cc)){
      $error_clave[0] = "La clave debe tener al menos un d&iacute;gito (0-9)";
      $error_clave[1] = false;
   }

	else if(!preg_match('`[!@#)$%&*<,>;:(-._]`', $var_cc)) {
      $error_clave[0] = "La clave es incorrecta debe contener una combinaci&oacute;n entre letras(a-z), n&uacute;meros(0-9) y s&iacute;mbolos especiales como: <br><span style='color:#840000;font-size:14px;'>".'! @ # $ _ % & * ( ) < > . , ; : -'."</span>";
      $error_clave[1] = false;
    }

   else{
   		$error_clave[0] = "";
   		$error_clave[1] = true;
   }
	}
	return $error_clave;
}

function cambiar_clave ($var=null, $vadv=null) {
   $this->layout="ajax";
   $this->set('var_adv', $var);
   $this->set('vadv', $vadv);
   if(isset($var) && $var!=null && $var=='form'){
       //recibir datos
       $clave_acutal = $this->data['usuarios']['clave_actual'];

       if($clave_acutal!=$this->data['usuarios']['clave_nueva1']){

		$vali = $this->validar_cclave($this->data['usuarios']['clave_nueva1']);
		if($vali[1] === true){

       $nueva_clave1 = md5($this->data['usuarios']['clave_nueva1']);
       $nueva_clave2 = md5($this->data['usuarios']['clave_nueva2']);

       if(!empty($this->data['usuarios']['clave_actual']) && !empty($this->data['usuarios']['clave_nueva1']) && !empty($this->data['usuarios']['clave_nueva2'])){
       if($nueva_clave1!=$nueva_clave2){
       	   echo "<span style='color:red;font-size:14px;'><center><b><i>Las claves nuevas no coinciden, por favor verifique.</i></b></center></span>";
       }else{
       	  $CU=$this->ccnd00->findCount("upper(username)='".strtoupper($this->Session->read('nom_usuario'))."'");
       	  if($CU!=0){
              $pass=$this->ccnd00->findAll("upper(username)='".strtoupper($this->Session->read('nom_usuario'))."'");
              // if(strtoupper($pass[0]['Usuario']['password']) == strtoupper($clave_acutal)){
              if(strtoupper($pass[0]['ccnd00']['password']) == strtoupper(md5($clave_acutal))) {
              	  $rs_update=$this->ccnd00->execute("UPDATE ccnd00 SET password='".$nueva_clave1."' WHERE upper(username)='".$this->Session->read('nom_usuario')."'");
              	  if($rs_update>1){
              	  		$_SESSION["passw_usuario"] = $this->data['usuarios']['clave_nueva1'];
              	  		if($vadv!=null){
              	  			echo "<script>Control.Modal.close(true);</script>";
              	  		}
              	  	   echo "<span style='color:green;font-size:14px;'><center><b><i>Cambio de clave realizado exitosamente!</i></b></center></span>";
              	  }else{
              	  	   echo "<span style='color:red;font-size:14px;'><center><b><i>Actualizaci&oacute;n de clave sin exito.</i></b></center></span>";
              	  }
              }else{
              	     echo "<span style='color:red;font-size:14px;'><center><b><i>La Clave actual no es correcta, por favor verifique.</i></b></center></span>";
              }
       	  }
       }

       }else{
           echo "<span style='color:red;font-size:14px;'><center><b><i>Campos sin llenar, por favor verifique.</i></b></center></span>";
       }



		}else{
				echo "<span style='color:#002300;font-size:12px;'><center><b><i>".$vali[0]."</i></b></center></span>";
		} // else: sino cumple condiciones en function validar_cclave


       }else{
			echo "<span style='color:red;font-size:14px;'><center><b><i>Ingrese una clave nueva distinta a la actual.</i></b></center></span>";
       }


       echo "<script>document.getElementById('cambiar_clave_id').disabled = false;</script>";
   }else{
   	  $this->set('var_form',true);
   }
}

function guardar(){
	$this->layout="ajax";
//	pr($this->data);
	if(empty($this->data['ccnp00']['login']) || empty($this->data['ccnp00']['password']) || empty($this->data['ccnp00']['password1']) || empty($this->data['ccnp00']['cedula']) || empty($this->data['ccnp00']['apellido_nombre']) || empty($this->data['ccnp00']['cod_estado']) || empty($this->data['ccnp00']['cod_municipio']) || empty($this->data['ccnp00']['cod_parroquia']) || empty($this->data['ccnp00']['cod_centro_poblado'])){
		$this->set('errorMessage',"Debe ingresar todos los datos requeridos");
	}else{
		$login=$this->data['ccnp00']['login'];
		$password=$this->data['ccnp00']['password'];
		$password1=$this->data['ccnp00']['password1'];
		$cedula=$this->data['ccnp00']['cedula'];
		$apellido_nombre=$this->data['ccnp00']['apellido_nombre'];
		$cod_presi=$this->Session->read('SScodpresi');
		$cod_estado=$this->data['ccnp00']['cod_estado'];
		$cod_municipio=$this->data['ccnp00']['cod_municipio'];
		$cod_parroquia=$this->data['ccnp00']['cod_parroquia'];
		$cod_centro=$this->data['ccnp00']['cod_centro_poblado'];
		$cod_comuna=$this->data['ccnp00']['cod_concejo_comunal'];

		$login=strtoupper($login);
		$ver  = $this->ccnd00->findCount("  quitar_acentos(mayus_acentos(username::text)) = quitar_acentos(mayus_acentos('$login')) ");
		$ver1 = $this->Usuario->findCount(" quitar_acentos(mayus_acentos(username::text)) = quitar_acentos(mayus_acentos('$login')) ");

		if($ver==0 && $ver1==0){
//		$ver=$this->ccnd00->execute("select * from ccnd00 where username='".$login."'");
//		if($ver==null){


		$vali = $this->validar_cclave($password);
		if($vali[1] === true){


			$password = md5($password);
			$password1 = md5($password1);

				if($password==$password1){
					$sql = "BEGIN;INSERT INTO ccnd00 VALUES ('$login', '$password', '$cedula', '$apellido_nombre', '$cod_presi', '$cod_estado',$cod_municipio,$cod_parroquia,'$cod_centro','1')";
				   	$sw=$this->ccnd00->execute($sql);
				   	if($sw>1){
						$this->ccnd00->execute("COMMIT");
						$this->set('Message_existe', 'REGISTRO EXITOSO');
		//				echo "<script>ver_documento('/ccnp00_usuarios_comunas/index/mensaje','principal');</script>";
						 echo "<script>";
//							echo "document.getElementById('select_6').value='';";
//							echo "document.getElementById('deno_concejo_comunalx').value='';";
							echo "document.getElementById('login').value='';";
							echo "document.getElementById('password').value='';";
							echo "document.getElementById('password1').value='';";
							echo "document.getElementById('cedula').value='';";
							echo "document.getElementById('ape_nom').value='';";
						 echo "</script>";
			   		}else{
			   			$this->ccnd00->execute("ROLLBACK");
			   			$this->set('errorMessage', 'INSERCI&Oacute;N FALLIDA DE LOS DATOS');
			   		}
				}else{
					$this->set('errorMessage', 'compruebe que las contraseñas ingresadas sean identicas');
				}

		}else{
				$this->set('errorMessage', "".$vali[0]);
		} // else: sino cumple condiciones en function validar_cclave


		}else{
			$this->set('errorMessage', 'este usuario ya existe registrado');
		}

	}

}// fin guardar


function consulta($pagina=null) {
	$this->layout="ajax";

	if(isset($pagina)){
		$Tfilas=$this->ccnd00->findCount();
        if($Tfilas!=0){
        	$x=$this->ccnd00->findAll(null,null,"username ASC",1,$pagina,null);

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
		$Tfilas=$this->ccnd00->findCount();

        if($Tfilas!=0){
        	$x=$this->ccnd00->findAll(null,null,"username ASC",1,$pagina,null);
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

   if(isset($x[0]["ccnd00"]["cod_republica"])){
	            $cond =" cod_republica=".$x[0]["ccnd00"]["cod_republica"]." and cod_estado=".$x[0]["ccnd00"]["cod_estado"]." and cod_municipio=".$x[0]["ccnd00"]["cod_municipio"]." and cod_parroquia=".$x[0]["ccnd00"]["cod_parroquia"]." and cod_centro=".$x[0]["ccnd00"]["cod_centro"]." and cod_concejo=".$x[0]["ccnd00"]["cod_concejo"];
				$deno_banco = $this->ccnd01_concejo_comunal->field('denominacion', $cond, $order ="cod_concejo ASC");
   }else{
               $deno_banco = "";
   }

	$this->set('estado',$this->cugd01_estados->field('denominacion', $conditions ="cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$x[0]["ccnd00"]["cod_estado"], $order ="cod_estado ASC"));
	$this->set('municipio',$this->cugd01_municipios->field('denominacion', $conditions ="cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$x[0]["ccnd00"]["cod_estado"]." and cod_municipio=".$x[0]["ccnd00"]["cod_municipio"], $order ="cod_municipio ASC"));
	$this->set('parroquia',$this->cugd01_parroquias->field('denominacion', $conditions ="cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$x[0]["ccnd00"]["cod_estado"]." and cod_municipio=".$x[0]["ccnd00"]["cod_municipio"]." and cod_parroquia=".$x[0]["ccnd00"]["cod_parroquia"], $order ="cod_parroquia ASC"));
	$this->set('centro',$this->cugd01_centropoblados->field('denominacion', $conditions ="cod_republica=".$this->Session->read('SScodpresi')." and cod_estado=".$x[0]["ccnd00"]["cod_estado"]." and cod_municipio=".$x[0]["ccnd00"]["cod_municipio"]." and cod_parroquia=".$x[0]["ccnd00"]["cod_parroquia"]." and cod_centro=".$x[0]["ccnd00"]["cod_centro"], $order ="cod_centro ASC"));

	$this->set('datos',$x);
	$this->set('denominacion_concejo',$deno_banco);

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
//					if(is_int($var2)){$sql   = " (cod_tipo_nomina='$nomina' and denominacion_busqueda LIKE '%$var2%')  or   ";}else{ $sql = "";}
					$Tfilas=$this->ccnd00->findCount("upper(username::text) LIKE upper('%".$var2."%') ");
//					        echo "cod_tipo_nomina='$nomina' and denominacion_busqueda LIKE '%$var2%'";
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
//						     	    $datos_filas=$this->cscd01_catalogo->findAll($sql." (denominacion LIKE '%$var22%')  OR  (cod_snc LIKE '%$var22%')   ",null,"codigo_prod_serv ASC",100,$pagina,null);
									$datos_filas=$this->ccnd00->findAll("upper(username::text) LIKE upper('%".$var22."%')",null,"username ASC",100,1,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else


//$this->set("cscd01_unidad_medida",$this->cscd01_unidad_medida->findAll());
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

	 if($pagina!=null){echo "a";
	  		$this->consulta($pagina);
	  		$this->render('consulta');
	  }else{echo "b";
		  	$this->index();
  			$this->render('index');
	  }

}// fin eliminar




 }//fin class
?>
