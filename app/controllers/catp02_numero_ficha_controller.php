<?php
class Catp02NumeroFichaController extends AppController{
    var $uses = array('catd02_numero_ficha');
    var $helpers = array('Html', 'Javascript', 'Ajax','Sisap');

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
	 $modelo="catd02_numero_ficha";
	 $modelo_url="catp02_numero_ficha";
	 $this->set("modelo_url",$modelo_url);
	 $this->set("modelo",$modelo);
	 $this->set("url_ver_nro",'modulos/vacio');
	 $c=$this->$modelo->findCount();
	 $max=$this->$modelo->execute("SELECT MAX(numero) as numero_mayor FROM ".$modelo." WHERE ".$this->SQLCA());
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
             $this->set("Message_existe","Situacion del Número actualizada con exito");
        }else if($var=="no"){
		     $this->set("errorMessage","Situacion del Número sin exito");
	    }
	}
     	$datos_filas=$this->$modelo->findAll($this->SQLCA()." order by numero ASC");
	    $this->set("datosFILAS",$datos_filas);

}//fin index
function guardar(){
	$this->layout="ajax";
	$modelo="catd02_numero_ficha";
	 $modelo_url="catp02_numero_ficha";
	 $this->set("modelo_url",$modelo_url);
	 $this->set("modelo",$modelo);
	 $this->set("url_ver_nro",'modulos/vacio');
	if(isset($this->data[$modelo_url])){
			 $ultimo=$this->data[$modelo_url]["ultimo"];
			 $crear_desde=$this->data[$modelo_url]["crear_desde"];
			 $crear_hasta=$this->data[$modelo_url]["crear_hasta"];

			 $camposT3="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,numero,situacion";
			 $values="";
			 $monto=0;
			 for($z=$crear_desde;$z<=$crear_hasta;$z++){
				  $values ="(".$this->SQLCAIN().",".$z.",1) ";
				  $R3=$this->$modelo->execute("INSERT INTO ".$modelo." (".$camposT3.") VALUES ".$values."");
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
 function cambiar_situacion ($codigo,$var,$id_row) {
	$this->layout="ajax";
	$modelo="catd02_numero_ficha";
	 $modelo_url="catp02_numero_ficha";
	 $this->set("modelo_url",$modelo_url);
	 $this->set("modelo",$modelo);
	 $this->set("url_ver_nro",'modulos/vacio');
    $resultado=$this->$modelo->execute("UPDATE  ".$modelo." SET situacion=".$var." WHERE ".$this->SQLCA()." and numero=".$codigo."  and (situacion=1 OR situacion=2)");
	if($resultado>1){
        $m="up";
	}else{
		$m="no";
	}

	if($m=="guardado"){
     		 $this->set("Message_existe","Números Creados con exito");
     	}else if($m=="up"){
             $this->set("Message_existe","Situacion del Número actualizada con exito");
        }else if($m=="no"){
		     $this->set("errorMessage","Situacion del Número actualizada sin exito");
	    }
        $datos_filas=$this->$modelo->findAll($this->SQLCA()." and numero=".$codigo." order by numero ASC");
	    $this->set("datosFILAS",$datos_filas);
	    $this->set('id_row',$id_row);

}

}//fin class
?>
