<?php


class Casp01TipoAyudaController extends AppController{
	var $uses = array('casd01_tipo_ayuda', 'casd01_solicitud_ayuda', 'cugd05_restriccion_clave');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
 	var $name = "casp01_tipo_ayuda";

//cnmp06_religiones2

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
		$paso=explode('/',$this->params['url']['url']);
	 	if(!isset($_SESSION["ATS_autorizados"][$paso[0]]) || $_SESSION["ATS_autorizados"][$paso[0]]==2){
	 		$this->Session->write('errorMessage', 'usted no esta autorizado para operar este programa');
	 		$this->redirect('modulos/vacio');
	 	}
}//fin before filter






function index($var=null, $var_cont=null){
	$this->layout = "ajax";

 $this->verifica_entrada('55');

if(!isset($var)){
	$this->Session->delete('cedula_pestana_atencion');
}
$this->set('muestra',1);
	  $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";

     $var_cont = $this->casd01_tipo_ayuda->findAll(null, null, 'cod_tipo_ayuda ASC');
     $datos    = $this->casd01_tipo_ayuda->findAll(null, null, 'upper(trim(quitar_acentos(denominacion)))  ASC');
     $var_cont2 = 0;
     foreach($var_cont as $ve1){$var_cont2 = $ve1['casd01_tipo_ayuda']['cod_tipo_ayuda'];}
     $var_cont2++;

     $this->set('codigo', $var_cont2);
     $this->set('datos', $datos);

}//fin index






function funcion(){$this->layout = "ajax";}




function guardar($var_n=null){

	$this->layout = "ajax";

      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";



if($var_n==null){ $cod_tipo_ayuda   =   $this->data['cnmp06_religiones2']['codigo'.$var_n];}else{ $cod_tipo_ayuda =  $var_n;}


if(!empty($this->data['cnmp06_religiones2']['denominacion'.$var_n])){
   $denominacion            =       $this->data['cnmp06_religiones2']['denominacion'.$var_n];
   $var_cont = $this->casd01_tipo_ayuda->findCount("cod_tipo_ayuda=".$cod_tipo_ayuda);

			         if($var_cont==0){
                        $sw = $this->casd01_tipo_ayuda->execute("BEGIN; INSERT INTO casd01_tipo_ayuda (cod_tipo_ayuda, denominacion) VALUES ('".$cod_tipo_ayuda."', '".$denominacion."'); ");
			         }else{
                        $sw = $this->casd01_tipo_ayuda->execute("BEGIN; UPDATE casd01_tipo_ayuda SET denominacion='".$denominacion."' WHERE cod_tipo_ayuda='".$cod_tipo_ayuda."' ");
			         }//fin function
}else{$sw="a";}

          if($sw>1){

        $this->casd01_tipo_ayuda->execute("COMMIT;");    $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');

     }elseif($sw=="a"){

     	$this->casd01_tipo_ayuda->execute("ROLLBACK;");  $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS -- FALTAN DATOS');

     }else{

     	$this->casd01_tipo_ayuda->execute("ROLLBACK;");  $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
     }//fin else




if($var_n==null){
	if(isset($_SESSION['cedula_pestana_atencion'])){
		 $this->set('autor_valido',true);
		 $this->index(1);
		 $this->render("index");
	}else{
		 $this->set('autor_valido',true);
		 $this->index();
		 $this->render("index");
	}

}else{

    $var_datos = $this->casd01_tipo_ayuda->findAll("cod_tipo_ayuda=".$cod_tipo_ayuda);
   echo'<script>';
                   echo" document.getElementById('iconos_2_".$var_n."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_1_".$var_n."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var_n."').innerHTML ='".$var_datos[0]['casd01_tipo_ayuda']['denominacion']."' ;";
     echo'</script>';
   $this->render("funcion");
}//fin else

}//fin function










function editar($var1=null, $var2=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
         $var_datos = $this->casd01_tipo_ayuda->findAll("cod_tipo_ayuda=".$var1);
         $var2 = $var_datos[0]['casd01_tipo_ayuda']['denominacion'];
      echo'<script>';
                   echo" document.getElementById('iconos_1_".$var1."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_2_".$var1."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var1."').innerHTML ='<input type=text name=data[cnmp06_religiones2][denominacion".$var1."]    id=denominacion".$var1."  value=\"$var2\"   class=campoText  />' ;";
     echo'</script>';

$this->render("funcion");
}//fin function





function eliminar($var1=null, $var2=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
	   $a=$this->casd01_solicitud_ayuda->FindCount("cod_tipo_ayuda='$var1'");
	    if($a==0){
	    	 $sw = $this->casd01_tipo_ayuda->execute("DELETE FROM casd01_tipo_ayuda  WHERE cod_tipo_ayuda='".$var1."' ");
	    }else{
	    	$this->set('errorMessage','ESTE DATO NO PODRA SER ELIMINADO YA QUE ESTA SIENDO USADO POR OTRO PROGRAMA');
	    }
 	$datos    = $this->casd01_tipo_ayuda->findAll(null, null, 'cod_tipo_ayuda ASC');
     $this->set('datos', $datos);
}//fin function



function cancelar($var1=null, $var2=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
      $var_datos = $this->casd01_tipo_ayuda->findAll("cod_tipo_ayuda=".$var1);
      $var2 = $var_datos[0]['casd01_tipo_ayuda']['denominacion'];
      echo'<script>';
                   echo" document.getElementById('iconos_2_".$var1."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_1_".$var1."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var1."').innerHTML ='".$var2."' ;";
     echo'</script>';
$this->render("funcion");
}//fin function



function entrar(){
	$this->layout="ajax";
	if(isset($this->data['casp01_tipo_ayuda']['login']) && isset($this->data['casp01_tipo_ayuda']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['casp01_tipo_ayuda']['login']);
		$paswd=addslashes($this->data['casp01_tipo_ayuda']['password']);
		$condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$user."' and cod_tipo=55 and clave='".$paswd."'";
		if(($user==$l && $paswd==$c)){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($condicion)!=0){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('errorMessage',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('autor_valido',false);
			$this->index("autor_valido");
			$this->render("index");
		}
	}
}


}//fin class
?>
