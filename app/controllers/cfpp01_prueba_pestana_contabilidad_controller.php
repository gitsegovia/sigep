<?php

 class Cfpp01PruebaPestanaContabilidadController extends AppController{


 	var $uses = array('cepd01_compromiso_numero','ccfd04_cierre_mes');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf','Form');


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

		function SQLCAIN($ano=null){//sql para busqueda de codigos de arranque con y sin año
				 $sql_re = $this->verifica_SS(1).",";
				 $sql_re .= $this->verifica_SS(2).",";
				 $sql_re .=  $this->verifica_SS(3).",";
				 $sql_re .= $this->verifica_SS(4).",";
				 if($ano!=null){
					 $sql_re .= $this->verifica_SS(5).",";
						$sql_re .= $ano."";
				 }else{
					 $sql_re .=  $this->verifica_SS(5)."";
				 }
				 return $sql_re;
		}//fin funcion SQLCAIN

	function index($var=null){
	 $this->layout = "ajax";
	 //$this->set("H",$this->requestAction('/usuarios/actualizar_user',array('return')));
	 $cod_presi = $this->Session->read('SScodpresi');
	 $cod_entidad = $this->Session->read('SScodentidad');
	 $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	 $cod_inst = $this->Session->read('SScodinst');
	 $cod_dep = $this->Session->read('SScoddep');
	 $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
	 $condicion_dir_sup = "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia=".$cod_dep;
    /* $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
	 $dato = null;
	 foreach($year as $year){
		$dato = $year['cfpd01_formulacion']['ano_formular'];
	 }*/
	 $dato=$this->ano_ejecucion();
	 $this->set('year', $dato);
	 $this->Session->write('year_pago',$dato);

	 $c=$this->cepd01_compromiso_numero->findCount();
	 $max=$this->cepd01_compromiso_numero->execute("SELECT MAX(numero_compromiso) as numero_mayor FROM cepd01_compromiso_numero WHERE ".$this->SQLCA()." and ano_compromiso=".$dato);
     if($max[0][0]["numero_mayor"]==""){
     	$this->set("ultimo",0);
     	$this->set("crear_desde",1);
     }else{
     	$this->set("ultimo",$max[0][0]["numero_mayor"]);
     	$this->set("crear_desde",$max[0][0]["numero_mayor"]+1);
     }
     if(isset($var)){
     	if($var=="guardado"){
     		 $this->set("Message_existe","Números Creados con exito");
     	}else if($var=="up"){
             $this->set("Message_existe","Situacion del compromiso actualizada con exito");
        }else if($var=="no"){
		     $this->set("errorMessage","Situacion del compromiso actualizada sin exito");
	    }
	}
     	$datos_filas=$this->cepd01_compromiso_numero->findAll($this->SQLCA()." and ano_compromiso=".$dato." order by numero_compromiso ASC");
	    $this->set("datosFILAS",$datos_filas);

}//fin index
function guardar(){
	$this->layout="ajax";
	if(isset($this->data["cepp01_compromiso_numero"])){
			 $ano=$this->data["cepp01_compromiso_numero"]["ano"];
			 $ultimo=$this->data["cepp01_compromiso_numero"]["ultimo"];
			 $crear_desde=$this->data["cepp01_compromiso_numero"]["crear_desde"];
			 $crear_hasta=$this->data["cepp01_compromiso_numero"]["crear_hasta"];

			 $camposT3="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_compromiso,numero_compromiso,situacion";
			 $values="";
			 $monto=0;
			 for($z=$crear_desde;$z<=$crear_hasta;$z++){
				  $values ="(".$this->SQLCAIN($ano).",".$z.",1) ";
				  $R3=$this->cepd01_compromiso_numero->execute("INSERT INTO cepd01_compromiso_numero (".$camposT3.") VALUES ".$values."");
				 /*if(($z)==$crear_hasta){
						$values .="(".$this->SQLCAIN($ano).",".$z.",1) ";
				 }else{
                       $values .=" (".$this->SQLCAIN($ano).",".$z.",1),";
				 }*/
			 }

             if($R3 > 1){
             	$msg="guardado";
             }else{
                 $msg=false;
             }
             $this->index($msg);
             $this->render("index");
              //echo "INSERT INTO cepd01_compromiso_numero (".$camposT3.") VALUES ".$values."";
 	}else{
		echo "Hay campos sin llenar";
	}
}//fin funcion guardar
 function cambiar_situacion ($codigo,$ano,$var) {
	$this->layout="ajax";
	//echo $codigo." - ".$var;
    $resultado=$this->cepd01_compromiso_numero->execute("UPDATE  cepd01_compromiso_numero SET situacion=".$var." WHERE ".$this->SQLCA()." and numero_compromiso=".$codigo." and ano_compromiso=".$ano." and (situacion!=3 AND situacion!=4) ");
	if($resultado>1){
        $m="up";
	}else{
		$m="no";
	}
    $this->index($m);
    $this->render("index");
	//$datos_filas=$this->cepd01_compromiso_numero->findAll($this->SQLCA()." and ano_compromiso=".$ano);
	//$this->set("datosFILAS",$datos_filas);

}

}//fin class
?>
