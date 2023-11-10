<?php

class CambiarDatosRinfogobiernoController extends AppController {
 	var $name = 'cambiar_datos_rinfogobierno';
 	var $uses = array ("v_cugd_usuarios",'ccfd04_cierre_mes');
 	var $helpers = array ('Html','Ajax','Javascript','Sisap');


function beforeFilter(){
	$this->checkSession();
}


function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					//$this->set('userTable', $this->requestAction('/cnmp03partidas/', array('return')));
					//echo "H".$this->requestAction('/usuarios/actualizar_user',array('return'));
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


function index($var1 = '2'){
	$this->layout ="ajax";
		$this->set("opcion",$var1);
		$url                  =  "/cambiar_datos_rinfogobierno/buscar_persona/";
		$width_aux            =  "750px";
		$height_aux           =  "450px";
		$title_aux            =  "Buscar Persona";
		$resizable_aux        =  false;
		$maximizable_aux      =  false;
		$minimizable_aux      =  false;
		$closable_aux         =  false;

			 echo "<script>";
	           echo "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
	         echo "</script>";
	         echo "<script>$('campo_pista').focus();</script>";
}


function buscar_persona($var1 = '2'){
	$this->layout ="ajax";
		$this->set("opcion",$var1);
		$url                  =  "/cambiar_datos_rinfogobierno/buscar_persona_pista/1";
		$width_aux            =  "750px";
		$height_aux           =  "450px";
		$title_aux            =  "Buscar Persona";
		$resizable_aux        =  false;
		$maximizable_aux      =  false;
		$minimizable_aux      =  false;
		$closable_aux         =  false;

			 echo "<script>";
	           echo "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
	         echo "</script>";
	         echo "<script>$('campo_pista').focus();</script>";
}



function buscar_persona_pista($var1=null){
	$this->layout="ajax";
	$this->Session->delete('pista');
	$this->Session->write('pista_opcion', 2);
}//fin function



function buscar_por_pista($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
$sql_like = "";

	    if($var3==null){
	    	            $var2 = strtoupper($var2);
						$this->Session->write('pista', $var2);
						$var_like = $var2;
						$sql_like = " (correo_electronico LIKE '%".$var2."%' OR apellidos LIKE '%".$var2."%' OR nombres LIKE '%".$var2."%' OR cedula_identidad LIKE '%".$var2."%')";
						$Tfilas=$this->v_cugd_usuarios->findCount($sql_like);
						        if($Tfilas!=0){
						        	$pagina=1;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_cugd_usuarios->findAll($sql_like,null,"nombres, apellidos ASC",100,1,null);
                                    $sql = "";
                                    $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
						          //$this->set("dato_a",$dato_a);
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);
						$var_like = $var22;
						$sql_like = " (correo_electronico LIKE '%".$var2."%' OR apellidos LIKE '%".$var2."%' OR nombres LIKE '%".$var2."%' OR cedula_identidad LIKE '%".$var2."%')";
						$Tfilas=$this->v_cugd_usuarios->findCount($sql_like);
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_cugd_usuarios->findAll($sql_like,null,"nombres, apellidos ASC",100,$pagina,null);
							        $sql = "";
					                $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
									//$this->set("dato_a",$dato_a);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else
$this->set("opcion",$var1);
}//fin function


function llenar_pista_opcion($var1=null){

    $this->layout="ajax";
	$this->Session->write('pista_opcion', $var1);

}//fin fucntion



function mostrar_datos($correo = null, $cedulai = null){
    $this->layout="ajax";
	$datos_trab = $this->v_cugd_usuarios->findAll("correo_electronico = '$correo' and cedula_identidad = '$cedulai'", null, null,1,1,null);

	echo "<script>
    		document.getElementById('correo_electronico').readOnly=false;
		    document.getElementById('upassword').readOnly=false;
		    document.getElementById('apellidos').readOnly=false;
		    document.getElementById('nombres').readOnly=false;
		    document.getElementById('cedula_identidad').readOnly=false;
    		document.getElementById('correo_electronico').value='".$datos_trab[0]['v_cugd_usuarios']['correo_electronico']."';
		    document.getElementById('upassword').value='".$datos_trab[0]['v_cugd_usuarios']['password']."';
		    document.getElementById('apellidos').value='".$datos_trab[0]['v_cugd_usuarios']['apellidos']."';
		    document.getElementById('nombres').value='".$datos_trab[0]['v_cugd_usuarios']['nombres']."';
		    document.getElementById('cedula_identidad').value='".$datos_trab[0]['v_cugd_usuarios']['cedula_identidad']."';
		    document.getElementById('correo_elect_aux').value='".$datos_trab[0]['v_cugd_usuarios']['correo_electronico']."';
		    document.getElementById('ced_identidad_aux').value='".$datos_trab[0]['v_cugd_usuarios']['cedula_identidad']."';
			document.getElementById('guardar').disabled=false;
		</script>";
}//fin fucntion


function guardar_datos(){
	$this->layout="ajax";
	$correo_elect = $this->data['datos_rinfogobierno']['correo_electronico'];
	$upassword = addslashes($this->data['datos_rinfogobierno']['upassword']);
	$apellidos = $this->data['datos_rinfogobierno']['apellidos'];
	$nombres = $this->data['datos_rinfogobierno']['nombres'];
	$cedula_identidad = $this->data['datos_rinfogobierno']['cedula_identidad'];

	$correo_elect_aux = $this->data['datos_rinfogobierno']['correo_elect_aux'];
	$ced_identidad_aux = $this->data['datos_rinfogobierno']['ced_identidad_aux'];

	if($correo_elect_aux != null && $ced_identidad_aux != null){

		if($correo_elect != null && $upassword != null && $apellidos != null && $nombres != null && $cedula_identidad != null){

	$sql = "UPDATE cugd_usuarios SET correo_electronico = '$correo_elect', password = '$upassword', apellidos = '$apellidos', nombres = '$nombres', cedula_identidad = '$cedula_identidad' WHERE correo_electronico = '$correo_elect_aux' AND cedula_identidad = '$ced_identidad_aux';";
    $sws = $this->v_cugd_usuarios->execute($sql);
    if($sws > 1){
    	$this->set('Message_existe', "Los datos fueron guardados correctamente.");
	echo "<script>
    		document.getElementById('correo_electronico').value='';
		    document.getElementById('upassword').value='';
		    document.getElementById('apellidos').value='';
		    document.getElementById('nombres').value='';
		    document.getElementById('cedula_identidad').value='';
    		document.getElementById('correo_electronico').readOnly=true;
		    document.getElementById('upassword').readOnly=true;
		    document.getElementById('apellidos').readOnly=true;
		    document.getElementById('nombres').readOnly=true;
		    document.getElementById('cedula_identidad').readOnly=true;
		    document.getElementById('guardar').disabled=true;
		</script>";
    }else{
    	$this->set('errorMessage', "No se pudo guardar los datos - Intente nuevamente...");
    }

		}else{
    		$this->set('errorMessage', "Debe ingresar todos los datos...");
    	}
	}else{
    	$this->set('errorMessage', "No se puede guardar los datos - faltan datos originales - Intente nuevamente...");
    }
}

} // FIN CLASS
?>
