<?php
class Catp01DepreciacionEdificacionesController extends AppController{
    var $uses = array('ccfd04_cierre_mes','catd01_depreciacion_edificaciones','catd01_ano_ordenanza');
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

function SQLCA_S($ano=null){//sql para busqueda de codigos de arranque con y sin año
				 $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
				 $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
				 $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
				 $sql_re .= "cod_inst=".$this->verifica_SS(4)."   ";
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

function index(){
	$this->layout  = "ajax";
	$ano_actual = $this->catd01_ano_ordenanza->ano_actual($this->SQLCA());
	$this->set('ano_actual',$ano_actual);

	if(!isset($ano_actual) || empty($ano_actual)){
                 $ano_actual=date("Y");
            }
	         $rs=$this->catd01_depreciacion_edificaciones->findAll($this->SQLCA()." and ano_ordenanza=".$ano_actual,null,'edad ASC');
	        $this->set("data_tipo",$rs);
	        $this->set("modelo","catd01_depreciacion_edificaciones");

	        $datos           = $this->catd01_depreciacion_edificaciones->findAll($this->SQLCA()." and ano_ordenanza=".$ano_actual,null,'edad DESC');
            $ultima_edad_aux = !isset($datos[0]["catd01_depreciacion_edificaciones"]["edad"])?1:$datos[0]["catd01_depreciacion_edificaciones"]["edad"]+1;
	        $this->set("ultima_edad", $ultima_edad_aux);


}//index

function guardar () {
   $this->layout="ajax";
   $modelo_form="catp01_depreciacion_edificaciones";
   if(isset($this->data[$modelo_form])){
      if(!empty($this->data[$modelo_form]["ano_ordenanza"]) && !empty($this->data[$modelo_form]["edad"])  && !empty($this->data[$modelo_form]["factor_excelente"])  && !empty($this->data[$modelo_form]["factor_bueno"])  && !empty($this->data[$modelo_form]["factor_regular"])  && !empty($this->data[$modelo_form]["factor_malo"])){
           $ano_ordenanza=$this->data[$modelo_form]["ano_ordenanza"];
            $cod[0]=$this->data[$modelo_form]["edad"];
			$cod[1]=$this->Formato1($this->data[$modelo_form]["factor_excelente"]);
			$cod[2]=$this->Formato1($this->data[$modelo_form]["factor_bueno"]);
			$cod[3]=$this->Formato1($this->data[$modelo_form]["factor_regular"]);
			$cod[4]=$this->Formato1($this->data[$modelo_form]["factor_malo"]);

	        if($this->catd01_depreciacion_edificaciones->findCount($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and edad=".$cod[0])==0){
	            $rs=$this->catd01_depreciacion_edificaciones->execute("INSERT INTO catd01_depreciacion_edificaciones VALUES (".$this->SQLCAIN().",".$ano_ordenanza.",".$cod[0].",".$cod[1].",".$cod[2].",".$cod[3].",".$cod[4].");");
                if($rs>1){
           	        $this->set("Message_existe","Los Datos Fueron Guardados Exitosamente");
                }else{
           	        $this->set("errorMessage","Los Datos No Fueron Guardados");
                }
	        }else{
	        	 $this->set("errorMessage","El Código Tipo ya se encuentra registrado");
	        }//coun
	        $rs=$this->catd01_depreciacion_edificaciones->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza,null,'edad ASC');
	        $this->set("data_tipo",$rs);
	        $this->set("modelo","catd01_depreciacion_edificaciones");
      }else{
      	    $ano_actual = $this->catd01_ano_ordenanza->ano_actual($this->SQLCA());
			$this->set('ano_actual',$ano_actual);
			if(!isset($ano_actual) || empty($ano_actual)){
		                 $ano_actual=date("Y");
            }
            if(!empty($this->data[$modelo_form]["ano_ordenanza"])){
            	 $ano_ordenanza = $this->data[$modelo_form]["ano_ordenanza"];
            }
            $rs=$this->catd01_depreciacion_edificaciones->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza,null,'edad ASC');
	        $this->set("data_tipo",$rs);
	        $this->set("modelo","catd01_depreciacion_edificaciones");
            $this->set("errorMessage","Faltan datos");
      }
   }//if isset
}//fin guardar

function mostrar_tipos ($ano_ordenanza=null) {
   $this->layout="ajax";
     if(!isset($ano_ordenanza) || empty($ano_ordenanza)){
                 $ano_ordenanza=date("Y");
            }
	         $rs=$this->catd01_depreciacion_edificaciones->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza,null,'edad ASC');
	        $this->set("data_tipo",$rs);
	        $this->set("modelo","catd01_depreciacion_edificaciones");

}//fin mostrar tipos


function eliminar_items ($edad,$ano_ordenanza) {
	$this->layout = "ajax";
    $rs=$this->catd01_depreciacion_edificaciones->execute("DELETE FROM catd01_depreciacion_edificaciones WHERE ".$this->SQLCA()." and  edad=".$edad." and ano_ordenanza=".$ano_ordenanza);
    if($rs>1){
           	        $this->set("Message_existe","El Dato Fu&eacute; Eliminado Exitosamente");
    }else{
           	        $this->set("errorMessage","El Dato No Fu&eacute; Eliminado");
    }
}//fin eliminar_items

function editar_tipo ($edad,$ano_ordenanza,$id_up,$id_fila) {
	$this->layout = "ajax";
    $rs=$this->catd01_depreciacion_edificaciones->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and edad=".$edad);
    $this->set("edad",$rs[0]["catd01_depreciacion_edificaciones"]["edad"]);
    $this->set("factor_excelente",$rs[0]["catd01_depreciacion_edificaciones"]["factor_excelente"]);
    $this->set("factor_bueno",$rs[0]["catd01_depreciacion_edificaciones"]["factor_bueno"]);
    $this->set("factor_regular",$rs[0]["catd01_depreciacion_edificaciones"]["factor_regular"]);
    $this->set("factor_malo",$rs[0]["catd01_depreciacion_edificaciones"]["factor_malo"]);
    $this->set("i",$id_up);
    $this->set("id_fila",$id_fila);


}

function guardar_editar ($id_up,$id_fila) {
   $this->layout="ajax";
   $modelo_form="catp01_depreciacion_edificaciones";
   if(isset($this->data[$modelo_form])){
      if(!empty($this->data[$modelo_form]["ano_ordenanza"]) && !empty($this->data[$modelo_form]["edad_edt"])  && !empty($this->data[$modelo_form]["factor_excelente_edt"])  && !empty($this->data[$modelo_form]["factor_bueno_edt"])  && !empty($this->data[$modelo_form]["factor_regular_edt"])   && !empty($this->data[$modelo_form]["factor_malo_edt"])){
           $ano_ordenanza=$this->data[$modelo_form]["ano_ordenanza"];
            $cod[0]=$this->data[$modelo_form]["edad_edt"];
			$cod[1]=$this->Formato1($this->data[$modelo_form]["factor_excelente_edt"]);
			$cod[2]=$this->Formato1($this->data[$modelo_form]["factor_bueno_edt"]);
			$cod[3]=$this->Formato1($this->data[$modelo_form]["factor_regular_edt"]);
			$cod[4]=$this->Formato1($this->data[$modelo_form]["factor_malo_edt"]);

	        if($this->catd01_depreciacion_edificaciones->findCount($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and edad=".$cod[0])!=0){
	            $rs=$this->catd01_depreciacion_edificaciones->execute("UPDATE catd01_depreciacion_edificaciones SET factor_excelente='".$cod[1]."' , factor_bueno=".$cod[2].", factor_regular=".$cod[3]." , factor_malo=".$cod[4]." WHERE ".$this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and edad=".$cod[0]);
                if($rs>1){
           	        $this->set("Message_existe","Los Datos Fueron Guardados Exitosamente");
                }else{
           	        $this->set("errorMessage","Los Datos No Fueron Guardados");
                }
	        }else{
	        	 $this->set("errorMessage","El Codigo Tipo ya se encuentra registrado");

	        }//coun
	        $rs=$this->catd01_depreciacion_edificaciones->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and edad=".$cod[0],null,'edad ASC');
	        $this->set("data_tipo",$rs);
	        $this->set("modelo","catd01_depreciacion_edificaciones");
      }//fin if empty
   }//if isset
    $this->set("i",$id_up);
    $this->set("id_fila",$id_fila);

}//fin guardar


function cancelar_editar ($id_up,$id_fila) {
   $this->layout="ajax";
   $modelo_form="catp01_depreciacion_edificaciones";
   if(isset($this->data[$modelo_form])){
      if(!empty($this->data[$modelo_form]["ano_ordenanza"]) && !empty($this->data[$modelo_form]["edad_edt"])){
           $ano_ordenanza=$this->data[$modelo_form]["ano_ordenanza"];
            $cod[0]=$this->data[$modelo_form]["edad_edt"];
	        $rs=$this->catd01_depreciacion_edificaciones->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and edad=".$cod[0],null,'edad ASC');
	        $this->set("data_tipo",$rs);
	        $this->set("modelo","catd01_depreciacion_edificaciones");
      }//fin if empty
   }//if isset
    $this->set("i",$id_up);
    $this->set("id_fila",$id_fila);

}//fin cancelar


function reporte_tabla_depreciacion_edif($var=null){

	if($var!=null){
		if($var=='si'){// Se muestra la vista del formulario.
			$this->layout="ajax";
			$this->set('var',$var);
			$ano_ejec = $this->catd01_ano_ordenanza->ano_actual($this->SQLCA());
			if($ano_ejec==null){
				$ano_ejec = $this->ano_ejecucion();
			}
			$this->set('anio_ej',$ano_ejec);
		}elseif($var=='no'){// Se muestra la vista del reporte.
			$this->layout = "pdf";
			$ano_ordenanza = $this->data['catp01_depreciacion_edificaciones']['ano_ordenanza'];
			$ano_ordenanza == '' ? $ano_ordenanza = date('Y') : $ano_ordenanza;
			$datos=$this->catd01_depreciacion_edificaciones->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza,null,'edad ASC');
			$_SESSION['ano_ordenanza_report']= $ano_ordenanza;
			$this->set('datos',$datos);
			$this->set('var',$var);
		}
	}
}//reporte_tabla_depreciacion_edif

}//fin class
?>
