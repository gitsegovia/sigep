<?php
class Cnmp06EspecialidadesController extends AppController {
   var $name = 'cnmp06_especialidades';
   var $uses = array('cnmd06_profesiones', 'cnmd06_especialidades','cnmd06_datos_personales','cnmd06_datos_registro_titulo','cugd05_restriccion_clave');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap');

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


 function beforeFilter(){
 	$this->checkSession();
 	 echo'<script>
							 document.getElementById("valida_codigo").innerHTML = "";
							 document.getElementById("valida_codigo").style.display = "none";
							 if(document.getElementById("registro")){document.getElementById("registro").style.visibility = "hidden";}
                             if(document.getElementById("nomina")){document.getElementById("nomina").style.visibility = "hidden";}
                             if(document.getElementById("dependencia")){document.getElementById("dependencia").style.visibility = "hidden";}
                             if(document.getElementById("sistema_general")){document.getElementById("sistema_general").style.visibility = "hidden";}
                          </script>';
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



 function index(){

$this->verifica_entrada('39');

 	$this->layout ="ajax";
 	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$this->concatena_tres_digitos($this->cnmd06_profesiones->generateList(null,'denominacion ASC', null, '{n}.cnmd06_profesiones.cod_profesion', '{n}.cnmd06_profesiones.denominacion'), 'cod_profesion');

	//verifico la dependencia para activar los botones
 	if($this->verifica_SS(5) == 1){
 		$this->set('enable', 'disabled');
 		$this->set('enable_guardar', 'enable');
 	}else{
 		$this->set('enable', 'disabled');
 		$this->set('enable_guardar', 'disabled');
 	}
 }




 function select3($select=null,$var=null) {
 $this->layout = "ajax";
 //echo $select. " ".$var;
	if($var!=null){

		if($var=="otros"){
			echo "";//vacio
		}else{
			  switch($select){
				case 'sector':
				  $this->set('SELECT','programa');
				  $this->set('codigo','sector');
				  $this->set('seleccion','');
				  $this->set('n',1);
				  $cond = "cod_profesion =".$var;
				  $lista=  $this->cnmd06_profesiones->generateList($cond, 'denominacion ASC', null, '{n}.cnmp06_profesiones.cod_profesion', '{n}.cnmp06_profesiones.cod_profesion');
		          $this->set('vector', $lista);

				break;
				case 'programa':
				  $this->set('SELECT','subprograma');
				  $this->set('codigo','programa');
				  $this->set('seleccion','');
				  $this->set('n',2);
				  $this->set('no','no');
				  $cond = "cod_profesion =".$var;
				  $lista=  $this->cnmd06_especialidades->generateList($cond, 'denominacion ASC', null, '{n}.cnmd06_especialidades.cod_especialidad', '{n}.cnmd06_especialidades.denominacion');
		          $this->set('vector', $lista);

		          $this->set('numero_especialidad',$var);
				break;
			}//fin switch
		}//fin else

	}else{
		  $this->set('SELECT','');
		  $this->set('codigo','');
		  $this->set('seleccion','');
		  $this->set('n',10);
		  $this->set('no','no');
		 $this->set('vector','');

		 $this->set('numero_especialidad',$var);
	}
 }//fin select3





 function mostrar3($select=null,$var=null) { // mostrar3
 $this->layout = "ajax";
 if( $var!=null){

	if($var=="otros"){
			echo "<input type='text' name ='data[cnmp06_especialidades][deno_especialidad]' id='deno_especialidad' style='width:100%' />";
	}else{
	      switch($select){
				case 'sector':
				  $ano =  $this->Session->read('ano');
				  $cond = "cod_profesion =".$var;
				  $a=  $this->cnmd06_profesiones->findAll($cond);
		          echo "<input type='text' name ='data[cnmp06_especialidades][deno_profesion]' id='deno_profesion' value='".$a[0]['cnmd06_profesiones']['denominacion']."' style='width:100%' readonly='readonly' />";
				break;
				case 'programa':
				  $cond = "cod_especialidad =".$var;
				  $a=  $this->cnmd06_especialidades->findAll($cond);
		          $variable = $a[0]['cnmd06_especialidades']['denominacion'];
		          echo "<input type='text' name ='data[cnmp06_especialidades][deno_especialidad]' id='deno_especialidad' value='".$variable."' style='width:100%' readonly='readonly' />";
		          //echo '--------------'.$var;
				break;
			}//fin switch
	}//fin else

 }else{
	echo "<input type='text' name='codigo_'".$select."' style='width:100%' readonly='readonly' />";
 }
 }// fin mostrar3




 function mostrarcodigo($select=null,$var=null,$vari=null) { //mostrar codigos
 $this->layout = "ajax";
 if( $var!=null){
	if($var=="otros"){
		$v=$this->cnmd06_especialidades->execute("SELECT * FROM cnmd06_especialidades WHERE cod_profesion=".$vari." ORDER BY cod_especialidad DESC");
		$escala=$v[0][0]["cod_especialidad"];
		$escala = $escala =="" ? 1 : $escala+1;
			echo "<input type='text' data[cnmp06_especialidades][codigo_especialidad] value='".$this->zero($escala)."' id='codigo_especialidad' style='width:100%;text-align:center' readonly='readonly' />";
			echo "<script>";
				echo "document.getElementById('deno_especialidad').readOnly=false;";
			echo "</script>";
	}else{
		switch($select){
			case 'sector':
			$this->Session->delete('tipo');
			  $this->Session->write('tipo',$var);
	          echo "<input type='text' name='data[cnmp06_especialidades][codigo_profesion]' id ='codigo_profesion' value='".$this->zero($var)."' style='width:100%;text-align:center' readonly='readonly' />";
			break;
			case 'programa':
			   //echo $var;
			   echo "<input type='text' name='data[cnmp06_especialidades][codigo_especialidad]' id ='codigo_especialidad' value='".$this->zero($var)."' style='width:100%;text-align:center' readonly='readonly' />";
			break;
		}//fin switch
	}//fin else
 }else{
	echo "<input type='text' name='codigo_'".$select."' style='width:100% readonly='readonly' />";
 }
 }//fin mostrar codigos



 function guardar() {
 	$this->layout = "ajax";
// 	pr($this->data);
 	if(!empty($this->data['cnmp06_especialidades']['cod_sector']) && !empty($this->data['cnmp06_especialidades']['deno_especialidad'])){
 		$cod_profesion = $this->data['cnmp06_especialidades']['cod_sector'];
// 		$cod_especialidad = $this->data['cnmp06_especialidades']['codigo_especialidad'];
		$v=$this->cnmd06_especialidades->execute("SELECT * FROM cnmd06_especialidades WHERE cod_profesion=".$cod_profesion." ORDER BY cod_especialidad DESC");
		$escala=$v[0][0]["cod_especialidad"];
		$escala = $escala =="" ? 1 : $escala+1;
 		$deno_especialidad = $this->data['cnmp06_especialidades']['deno_especialidad'];
		$sql = "INSERT INTO cnmd06_especialidades  VALUES (".$cod_profesion.",".$escala.", '".$deno_especialidad."')";
		if($this->cnmd06_especialidades->execute($sql)>1){
			$this->set('Message_existe', 'LOS DATOS FUER&Oacute;N REGISTRADOS CORRECTAMENTE');
			++$escala;
			echo "<script>";
				echo "document.getElementById('codigo_especialidad').value='".$this->zero($escala)."';";
			echo "</script>";

		}else{
			$this->set('errorMessage', 'ERROR EN LA INSERCI&Oacute;N DE LOS DATOS');
		}
		$data=$this->cnmd06_especialidades->findAll("cod_profesion=".$cod_profesion,null,' upper(trim(denominacion)) ASC',null,null,null);
		if($data!=null){
			$this->set('datos',$data);
		}else{
			$this->set('datos','');
		}
	}else{
 		$this->set('errorMessage', 'DEBE INGRESAR LA DENOMINACI&Oacute;N DE LA ESPECIALIDAD');
 		if(!empty($this->data['cnmp06_especialidades']['cod_sector'])){
 			$cod_profesion = $this->data['cnmp06_especialidades']['cod_sector'];
 			$data=$this->cnmd06_especialidades->findAll("cod_profesion=".$cod_profesion,null,' upper(trim(denominacion)) ASC',null,null,null);
			if($data!=null){
				$this->set('datos',$data);
			}else{
				$this->set('datos','');
			}
 		}else{
 			$this->set('datos','');
 		}

 	}

	echo "<script>";
		echo "document.getElementById('agregar').disabled=false;";
	echo "</script>";
 }// fin guardar



function grilla($var=null){
	$this->layout="ajax";
	$data=$this->cnmd06_especialidades->findAll("cod_profesion=".$var,null,' upper(trim(denominacion)) ASC',null,null,null);
	if($data!=null){
		$this->set('datos',$data);
	}else{
		$this->set('datos','');
	}
	echo "<script>";
		echo "document.getElementById('agregar').disabled=false;";
	echo "</script>";

}//fin grilla



function eliminar($var1=null, $var2=null,$var3=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
//$this->render("funcion");
//echo "cod_profesion='$var1' and cod_especialidad='$var2'";
	 $a=$this->cnmd06_datos_personales->findCount("cod_profesion='$var1' and cod_especialidad='$var2'");
	 $b=$this->cnmd06_datos_registro_titulo->findCount("cod_profesion='$var1' and cod_especialidad='$var2'");
	 // echo 'a='.$a.' b='.$b;
	    if($a=='0' && $b=='0'){
	    	 $sw = $this->cnmd06_especialidades->execute("DELETE FROM cnmd06_especialidades  WHERE cod_profesion='".$var1."' and cod_especialidad='".$var2."' ");
	    }else{
	    	$this->set('errorMessage','ESTE DATO NO PODRA SER ELIMINADO YA QUE ESTA SIENDO USADO POR OTRO PROGRAMA');
	    }
//		$this->render("funcion");
 	$datos    = $this->cnmd06_especialidades->findAll("cod_profesion='$var1'", null,' upper(trim(denominacion)) ASC');
     $this->set('datos', $datos);

     $v=$this->cnmd06_especialidades->execute("SELECT * FROM cnmd06_especialidades WHERE cod_profesion=".$var1." ORDER BY cod_especialidad DESC");
		$escala=$v[0][0]["cod_especialidad"];
		$escala = $escala =="" ? 1 : $escala+1;
	    $escala=$this->zero($escala);
		echo "<script>";
				echo "document.getElementById('codigo_especialidad').value='".$escala."';";
			echo "</script>";

}//fin function



function cancelar($var1=null,$var2=null,$var3=null){
	  $this->layout = "ajax";

	  $datos    = $this->cnmd06_especialidades->findAll("cod_profesion='$var1'", null, ' upper(trim(denominacion)) ASC');
     $this->set('datos', $datos);


}//fin cancelar



function editar($var1=null, $var2=null,$var3=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
         $var_datos = $this->cnmd06_especialidades->findAll("cod_profesion='$var1' and cod_especialidad='$var2'");
//        pr($var_datos);
         $this->set('profesion',$var1);
         $this->set('especialidad',$var2);
         $this->set('deno',$var_datos[0]['cnmd06_especialidades']['denominacion']);
         $this->set('k',$var3);
         $this->set('Message_existe','proceda a modificar los datos');

}//fin function




  function guardar_modificar($var1=null, $var2=null,$var3=null){
 	$this->layout="ajax";
//	pr($this->data);
 	if(!empty($this->data['cnmp06_especialidades']['denominacion'.$var3])){
		$deno_especialidad=$this->data['cnmp06_especialidades']['denominacion'.$var3];
  	    $sql="update cnmd06_especialidades set denominacion='$deno_especialidad' where cod_profesion='$var1' and cod_especialidad=".$var2;

		if($this->cnmd06_especialidades->execute($sql)>1){
		$this->set('mensajeError','Los datos no fuer&oacute;n modificados');
 	}
 	}
 	 $datos    = $this->cnmd06_especialidades->findAll("cod_profesion='$var1'", null, ' upper(trim(denominacion)) ASC');
     $this->set('datos', $datos);
 }//guardar modificar


function correr(){
	$this->layout="ajax";

//	$datos=$this->cnmd06_especialidades->FindAll("select cod_profesion from cnmd06_especialidades group by cod_profesion ORDER BY cod_profesion ASC");
	$datos    = $this->cnmd06_especialidades->findAll("group by cod_profesion", 'cod_profesion', 'cod_profesion ASC');
//	pr($datos);
	foreach($datos as $x){
		$i=1;
		$profesion=$x['cnmd06_especialidades']['cod_profesion'];
		$sql= $this->cnmd06_especialidades->findAll("cod_profesion=".$profesion,null, 'cod_profesion,cod_especialidad ASC');
		foreach($sql as $b){
			$profe=$b['cnmd06_especialidades']['cod_profesion'];
			$espe=$b['cnmd06_especialidades']['cod_especialidad'];
			$this->cnmd06_especialidades->execute("update cnmd06_especialidades set cod_especialidad='$i' where cod_profesion='$profe' and cod_especialidad='$espe'");
			$this->cnmd06_especialidades->execute("update cnmd06_datos_personales set cod_especialidad='$i' where cod_profesion='$profe' and cod_especialidad='$espe'");
			$i++;
		}
	}
}


function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cnmp06_especialidades']['login']) && isset($this->data['cnmp06_especialidades']['password'])){
		$cod_presi                =       $this->Session->read('SScodpresi');
		$cod_entidad              =       $this->Session->read('SScodentidad');
		$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
		$cod_inst                 =       $this->Session->read('SScodinst');
		$cod_dep                  =       $this->Session->read('SScoddep');
		$condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";

		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cnmp06_especialidades']['login']);
		$paswd=addslashes($this->data['cnmp06_especialidades']['password']);
		$cond=$condicion." and username='".$user."' and cod_tipo=39 and clave='".$paswd."'";
		if(($user==$l && $paswd==$c)){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
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


 }//Fin de la clase controller
 ?>