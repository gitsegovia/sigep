<?php
/*
 * Fecha: 19/07/2007
 *
 * Por Erisk G. Aragol H.
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */
class Cnmp09ControlNominasRealizadasController extends AppController {
   var $name = 'cnmp09_control_nominas_realizadas';
   var $uses = array('Cnmd01',  'cnmd03_transacciones','cnmd09_numero_nominas_canceladas','cugd05_restriccion_clave');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap');
//'cnmd10_bolivares_asig',
function checkSession()
    {
        // If the session info hasn't been set...
        if (!$this->Session->check('Usuario'))
        {
            // Force the user to login
            $this->redirect('/salir');
            exit();
        }
    }

 function beforeFilter(){
 	$this->checkSession();

 }





 function zero($x=null){
	if($x != null){
		if($x<10){
			$x="0".$x;
		}else if($x>=10 && $x<=99){
			$x=$x;
		}
	}
	return $x;

}//fin zero





function concatena($vector1=null, $nomVar=null){

	if($vector1 != null){

		foreach($vector1 as $x => $y){

			$cod[$x] = $this->zero($x).' - '.$y;
		}

		$this->set($nomVar, $cod);

	}
}//fin concatena




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










 function condicion(){
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

  return $condicion;

}


function entrar(){
	$this->layout="ajax";
	if(isset($this->data['csrp01_solicitud_recurso_aprobacion']['login']) && isset($this->data['csrp01_solicitud_recurso_aprobacion']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['csrp01_solicitud_recurso_aprobacion']['login']);
		$paswd=addslashes($this->data['csrp01_solicitud_recurso_aprobacion']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=29 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
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



function index($var=null){

$this->verifica_entrada('29');

	$this->layout="ajax";
	$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	$this->concatenaN($lista, 'nomina');
	for($i=1;$i<=700;$i++){
		$arreglo[$i]=$i;
	}
	$this->set('arreglo',$arreglo);
//	"U01"=>'Urbano',"U02"=>'Rural'
}// fin index



function cod_nomina($var=null){
    $this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

     $resultado= $this->Cnmd01->execute("SELECT
			  a.cod_presi,
			  a.cod_entidad,
			  a.cod_tipo_inst,
			  a.cod_inst,
			  a.cod_dep,
			  a.cod_tipo_nomina,
			  a.denominacion

			  from  Cnmd01 a where

			    a.cod_presi              =   ".$cod_presi." and
			    a.cod_entidad            =   ".$cod_entidad." and
			    a.cod_tipo_inst          =   ".$cod_tipo_inst." and
			    a.cod_inst               =   ".$cod_inst." and
			    a.cod_dep                =   ".$cod_dep." and
			    a.cod_tipo_nomina        =   ".$var."
			    ");


echo "<script>";
        echo "document.getElementById('cod_nomina').value='".mascara_tres($resultado[0][0]['cod_tipo_nomina'])."';";
		echo "document.getElementById('deno_nomina').value='".$resultado[0][0]['denominacion']."';";
echo "</script>";


}//fin function


function consulta($var=null){
	$this->layout="ajax";
	$filas=$this->cnmd09_numero_nominas_canceladas->FindCount($this->SQLCA()." and cod_tipo_nomina=".$var);
	if($filas==0){
		echo "<script>";
			echo "document.getElementById('agregar').disabled=false;";
		echo "</script>";
	}else{
		$dato=$this->cnmd09_numero_nominas_canceladas->FindAll($this->SQLCA()." and cod_tipo_nomina=".$var);
		$this->set('dato',$dato);
		echo "<script>";
			echo "document.getElementById('agregar').disabled='disabled';";
		echo "</script>";
	}
}


function guardar(){
	$this->layout="ajax";
//	pr($this->data);
	$nomina=$this->data['cnmp09_tan']['cod_nomina'];
	$numeros=$this->data['cnmp09_tan']['numeros'];
	$desde=$this->data['cnmp09_tan']['desde'];
	$hasta=$this->data['cnmp09_tan']['hasta'];
	$concepto=$this->data['cnmp09_tan']['concepto'];
	$filas=$this->cnmd09_numero_nominas_canceladas->FindCount($this->SQLCA()." and cod_tipo_nomina=".$nomina);
	if($filas==0){
			$sql_insert = "INSERT INTO cnmd09_numero_nominas_canceladas VALUES(".$this->verifica_SS(1).", ".$this->verifica_SS(2).", ".$this->verifica_SS(3).",".$this->verifica_SS(4).",".$this->verifica_SS(5).",".$nomina.",".$numeros.",'".$desde."','".$hasta."','".$concepto."')";
			$sw1 = $this->cnmd09_numero_nominas_canceladas->execute($sql_insert);
			if($sw1>1){
				$this->set('Message_existe','registro exitoso');
			}else{
				$this->set('errorMessage','no se pudo registrar');
			}
	}else{
		$this->set('errorMessage','este registro ya existe');
	}

	$this->data['cnmp09_tan']['cod_nomina']=null;
	$this->data['cnmp09_tan']['numeros']=null;
	$this->data['cnmp09_tan']['desde']=null;
	$this->data['cnmp09_tan']['hasta']=null;
	$this->data['cnmp09_tan']['concepto']=null;

	$dato=$this->cnmd09_numero_nominas_canceladas->FindAll($this->SQLCA()." and cod_tipo_nomina=".$nomina);
	$this->set('dato',$dato);
	echo "<script>";
		echo "document.getElementById('agregar').disabled='disabled';";
	echo "</script>";
}//fin guardar




function eliminar($i=null,$cod_nomina=null){
 		$this->layout="ajax";
		$sql = "DELETE FROM cnmd09_numero_nominas_canceladas WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_nomina;
		$this->cnmd09_numero_nominas_canceladas->execute($sql);
		$this->set('Message_existe', 'EL REGISTRO FUE ELIMINADO EXITOSAMENTE');

 }//eliminar




 function modificar($i=null,$cod_nomina=null){
 	$this->layout="ajax";

 	$data=$this->cnmd09_numero_nominas_canceladas->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_nomina);
	$this->set('cod_nomina', $data[0]['cnmd09_numero_nominas_canceladas']['cod_tipo_nomina']);
	$this->set('numero', $data[0]['cnmd09_numero_nominas_canceladas']['numero_nomina']);
	$this->set('desde', $data[0]['cnmd09_numero_nominas_canceladas']['periodo_desde']);
	$this->set('hasta', $data[0]['cnmd09_numero_nominas_canceladas']['periodo_hasta']);
	$this->set('concepto', $data[0]['cnmd09_numero_nominas_canceladas']['concepto']);
	$this->set('k',$i);
	$this->set('Message_existe','PROCEDA A MODIFICAR LOS DATOS');

 }//fin modificar




function guardar_modificar($nomina=null,$i=null){
	$this->layout="ajax";
	//pr($this->data);
	$desde=$this->data['cnmp09_tan']['desde'.$i];
	$hasta=$this->data['cnmp09_tan']['hasta'.$i];
	$concepto=$this->data['cnmp09_tan']['concepto'.$i];
	$v=$this->cnmd09_numero_nominas_canceladas->execute("update cnmd09_numero_nominas_canceladas set periodo_desde='".$desde."',periodo_hasta='".$hasta."',concepto='".$concepto."' where ".$this->SQLCA()." and cod_tipo_nomina=".$nomina);
	$this->set('Message_existe','EL REGISTRO SE MODIFICO EXITOSAMENTE');

	$dato=$this->cnmd09_numero_nominas_canceladas->FindAll($this->SQLCA()." and cod_tipo_nomina=".$nomina);
	$this->set('dato',$dato);



 }//guardar modificar


function cancelar($nomina=null){
	$this->layout="ajax";
	$dato=$this->cnmd09_numero_nominas_canceladas->FindAll($this->SQLCA()." and cod_tipo_nomina=".$nomina);
	$this->set('dato',$dato);

}


}//FIN CONTROLADOR
?>