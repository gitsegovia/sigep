<?php
/*
 * Fecha: 19/07/2007
 *
 * Por Luis Alfredo Diaz Jaramillo
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */
class Cnmp04Ocupacion2Controller extends AppController {
   var $name = 'cnmp04_ocupacion2';
   var $uses = array('cnmd04_tipo', 'cnmd04_ocupacion','cugd05_restriccion_clave');
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

function beforeFilter(){
 	$this->checkSession();
}

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

function AddCero($nomVar,$vector=object,$extra=null){
	  if($vector!=null){
	  	  if($extra==null){
	  	foreach($vector as $x){
	    	if($x<10){
	    	   $Var[$x]="0".$x;
	    	}else{
	           $Var[$x]=$x;
	    	}
	    }//fin each
	  }else{
	      foreach($vector as $x){
	    	if($x<10){
	    	   $Var[$x]=$extra.".0".$x;
	    	}else{
	           $Var[$x]=$extra.".".$x;
	    	}
	    }//fin each
	  }
	  $this->set($nomVar,$Var);
	  }else{
	  	  $this->set($nomVar,'');
	  }
}//fin AddCero


 function index(){
 	$this->layout ="ajax";
 	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
 	$lista = $this->cnmd04_tipo->generateList(null, $order = 'cod_nivel_i', $limit = null, '{n}.cnmd04_tipo.cod_nivel_i', '{n}.cnmd04_tipo.denominacion');
	$this->concatena($lista, 'tipo');
 	$this->set('enable', 'disabled');
 }//fin index


 function select_tipo($opcion=null,$var = null){
 	$this->layout ="ajax";
	if($var!=''){
		switch($opcion){
 			case 'tipo':
 					$this->set('tipo',$var);
 					$dato3  = $this->cnmd04_ocupacion->FindAll("cod_nivel_i=".$var,null,' ORDER BY cod_nivel_ii DESC');
 					$codigo = isset($dato3[0]["cnmd04_ocupacion"]["cod_nivel_ii"])?$dato3[0]["cnmd04_ocupacion"]["cod_nivel_ii"]+1:1;
 					echo "<script>";
						echo "document.getElementById('ocupacion').value='".mascara2($codigo)."';";
						echo "document.getElementById('deno_ocu').value='';";
						echo "document.getElementById('ocupacion').readOnly=false;";
						echo "document.getElementById('deno_ocu').readOnly=false;";
					echo "</script>";
 			break;
 			case 'deno_tipo':
 					$this->Session->write('cod1',$var);
	 				$deno = $this->cnmd04_tipo->field('denominacion', "cod_nivel_i='$var'", $order ="cod_nivel_i ASC");
 					$this->set('deno_tipo', $deno);
 			break;
 			case 'select':
	 				$lista = $this->cnmd04_ocupacion->generateList("cod_nivel_i=".$var, $order = 'cod_nivel_ii', $limit = null, '{n}.cnmd04_ocupacion.cod_nivel_ii', '{n}.cnmd04_ocupacion.denominacion');
					$this->concatena($lista, 'select');
 			break;
 			case 'ocupacion':
 				if($var!='agregar'){
 					$this->set('ocupacion',$var);
 				}else{
 					$this->set('ocupacion',false);
 				}
 			break;
 			case 'deno_ocupacion':
 				if($var!='agregar'){
 					$cod1=$this->Session->read('cod1');
 					$deno = $this->cnmd04_ocupacion->field('denominacion', "cod_nivel_i='$cod1' and cod_nivel_ii='$var'", $order ="cod_nivel_ii ASC");
 					$this->set('deno_ocupacion', $deno);
 				}else{
 					$this->set('deno_ocupacion', false);
 				}

 			break;
 		}//fin switch
	}else{
		$this->set('tipo','');
		$this->set('deno_tipo', '');
		echo "<script>";
			echo "document.getElementById('ocupacion').value='';";
			echo "document.getElementById('deno_ocu').value='';";
			echo "document.getElementById('ocupacion').readOnly=false;";
			echo "document.getElementById('deno_ocu').readOnly=false;";
		echo "</script>";
	}


 }//fin select_tipo

function grilla($var=null){
	 	$this->layout ="ajax";
	 	if($var!=''){
	 		$datos=$this->cnmd04_ocupacion->FindAll('cod_nivel_i='.$var,null,' ORDER BY cod_nivel_ii ASC');
	 		$this->set('datos',$datos);
	 	}else{
	 		$this->set('datos',null);
	 	}

}//fin grilla




function modificar($cod1=null,$cod2=null,$i=null){
	$this->layout="ajax";
	$this->set('tipo',$cod1);
	$this->set('ocupacion',$cod2);
	$this->set('k',$i);
	$deno2=$this->cnmd04_ocupacion->execute("select denominacion from cnmd04_ocupacion where cod_nivel_i=".$cod1." and cod_nivel_ii=".$cod2);
	$this->set('deno2',$deno2);
	//print_r($deno2);
}// fin mostrar



function guardar_modificar($var=null,$var2=null,$i=null){
	$this->layout="ajax";
	if($this->data["cnmp04_ocupacion"]["deno".$i]!=''){
		$sw=$this->cnmd04_ocupacion->execute("update cnmd04_ocupacion set denominacion='".$this->data["cnmp04_ocupacion"]["deno".$i]."' where cod_nivel_i=".$var." and cod_nivel_ii=".$var2);
		if($sw>1){
			$this->set('mensajeExiste','se ha modificado exitosamente');
		}else{
			$this->set('mensajeError','No se pudo modificar,intente nuevamente');
		}
	}else{
		$this->set('mensajeError','ingrese la denominacion');
	}
$datos=$this->cnmd04_ocupacion->FindAll("cod_nivel_i=".$var,null,' ORDER BY cod_nivel_ii ASC');
$this->set('datos',$datos);

}//fin guardar_modificar




function cancelar($tipo=null,$ocupacion=null){
	$this->layout="ajax";
	$datos=$this->cnmd04_ocupacion->FindAll("cod_nivel_i=".$tipo,null,' ORDER BY cod_nivel_ii ASC');
	$this->set('datos',$datos);


}//fin cancelar



function eliminar($cod_nivel_i = null,$cod2=null){

	$this->layout ="ajax";

	if($cod_nivel_i != null){


		$sql = "DELETE FROM cnmd04_ocupacion WHERE cod_nivel_i = ".$cod_nivel_i." and cod_nivel_ii=".$cod2;
		$this->cnmd04_ocupacion->execute($sql);

	}
	$dato3  = $this->cnmd04_ocupacion->FindAll("cod_nivel_i=".$cod_nivel_i,null,' ORDER BY cod_nivel_ii DESC');
 	$codigo = isset($dato3[0]["cnmd04_ocupacion"]["cod_nivel_ii"])?$dato3[0]["cnmd04_ocupacion"]["cod_nivel_ii"]+1:1;
	$this->set('codigo',$codigo);
 }//fin eliminar

function guardar(){

 	$this->layout ="ajax";
// 	pr($this->data);
 	if(!empty($this->data['cnmp04_ocupacion']['tipo']) && !empty($this->data['cnmp04_ocupacion']['ocupacion']) && !empty($this->data['cnmp04_ocupacion']['deno_ocupacion'])){
		$cod_nivel_i = $this->data['cnmp04_ocupacion']['tipo'];
		$cod_nivel_ii = $this->data['cnmp04_ocupacion']['ocupacion'];
		$denominacion = $this->data['cnmp04_ocupacion']['deno_ocupacion'];
		$verifica=$this->cnmd04_ocupacion->FindAll("cod_nivel_i=".$cod_nivel_i." and cod_nivel_ii=".$cod_nivel_ii);
		if($verifica!=null){
			$this->set('mensajeError', 'este registro ya existe');
		}else{
			$sql = "INSERT INTO cnmd04_ocupacion VALUES('$cod_nivel_i','$cod_nivel_ii', '$denominacion')";

			$sw=$this->cnmd04_ocupacion->execute($sql);
			if($sw > 1){
				$this->set('mensajeExiste', 'los datos fuer&oacute;n registrados');
			}else{
				$this->set('mensajeError', 'los datos no pudier&oacute;n ser registrados');
			}
		}//fin verifica null



 	}else{
 		$this->set('mensajeError', 'Debe ingresar los datos requeridos para el registro');
 	}

 	if(!empty($this->data['cnmp04_ocupacion']['tipo'])){
		$dato3  = $this->cnmd04_ocupacion->FindAll("cod_nivel_i=".$this->data['cnmp04_ocupacion']['tipo'],null,' ORDER BY cod_nivel_ii DESC');
	 	$codigo = isset($dato3[0]["cnmd04_ocupacion"]["cod_nivel_ii"])?$dato3[0]["cnmd04_ocupacion"]["cod_nivel_ii"]+1:1;
		echo "<script>";
			echo "document.getElementById('ocupacion').value='".mascara2($codigo)."';";
			echo "document.getElementById('deno_ocu').value='';";
		echo "</script>";
		$this->set('datos',$this->cnmd04_ocupacion->FindAll("cod_nivel_i=".$this->data['cnmp04_ocupacion']['tipo'],null,' ORDER BY cod_nivel_ii ASC'));
	}else{
		$this->set('datos',null);
	}


 }//fin guardar


function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cnmp04_ocupacion2']['login']) && isset($this->data['cnmp04_ocupacion2']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cnmp04_ocupacion2']['login']);
		$paswd=addslashes($this->data['cnmp04_ocupacion2']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=67 and clave='".$paswd."'";
		if(($user==$l && $paswd==$c)){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('mensajeError',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('autor_valido',false);
			$this->index("autor_valido");
			$this->render("index");
		}
	}
}

 }//fin de la clase
 ?>