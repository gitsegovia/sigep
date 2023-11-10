<?php
class Catp01RecargosCatastralesController extends AppController{
    var $uses = array('ccfd04_cierre_mes','catd01_recargos_catastrales','catd01_ano_ordenanza');
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
	        $cantidad_reg=$this->catd01_recargos_catastrales->findCount($this->SQLCA()." and ano_ordenanza=".$ano_actual);
            $this->set("cantidad_reg",$cantidad_reg);
	        $rs=$this->catd01_recargos_catastrales->findAll($this->SQLCA()." and ano_ordenanza=".$ano_actual,null,'porcentaje_industria ASC');
	        $this->set("data_tipo",$rs);
	        $this->set("modelo","catd01_recargos_catastrales");


}//index

function guardar () {
   $this->layout="ajax";
   $modelo_form="catp01_recargos_catastrales";
   if(isset($this->data[$modelo_form])){
      if(!empty($this->data[$modelo_form]["ano_ordenanza"]) && !empty($this->data[$modelo_form]["porcentaje_industria"])  && !empty($this->data[$modelo_form]["porcentaje_servicios"])  && !empty($this->data[$modelo_form]["porcentaje_comercial"])  && !empty($this->data[$modelo_form]["porcentaje_arrendado"])  && !empty($this->data[$modelo_form]["porcentaje_otro"])){
           $ano_ordenanza=$this->data[$modelo_form]["ano_ordenanza"];
            $cod[0]=$this->Formato1($this->data[$modelo_form]["porcentaje_industria"]);
			$cod[1]=$this->Formato1($this->data[$modelo_form]["porcentaje_servicios"]);
			$cod[2]=$this->Formato1($this->data[$modelo_form]["porcentaje_comercial"]);
			$cod[3]=$this->Formato1($this->data[$modelo_form]["porcentaje_arrendado"]);
			$cod[4]=$this->Formato1($this->data[$modelo_form]["porcentaje_otro"]);

	        if($this->catd01_recargos_catastrales->findCount($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and porcentaje_industria=".$cod[0])==0){
	            $rs=$this->catd01_recargos_catastrales->execute("INSERT INTO catd01_recargos_catastrales VALUES (".$this->SQLCAIN().",".$ano_ordenanza.",".$cod[0].",".$cod[1].",".$cod[2].",".$cod[3].",".$cod[4].");");
                if($rs>1){
           	        $this->set("Message_existe","Los Datos Fueron Guardados Exitosamente");
                }else{
           	        $this->set("errorMessage","Los Datos No Fueron Guardados");
                }
	        }else{
	        	 $this->set("errorMessage","El Codigo Tipo ya se encuentra registrado");
	        }//count
	        $cantidad_reg=$this->catd01_recargos_catastrales->findCount($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza);
            $this->set("cantidad_reg",$cantidad_reg);
	        $rs=$this->catd01_recargos_catastrales->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza,null,'porcentaje_industria ASC');
	        $this->set("data_tipo",$rs);
	        $this->set("modelo","catd01_recargos_catastrales");
      }//fin if empty
   }//if isset
}//fin guardar

function mostrar_tipos ($ano_ordenanza=null) {
   $this->layout="ajax";
            if(!isset($ano_ordenanza) || empty($ano_ordenanza)){
                 $ano_ordenanza=date("Y");
            }
            $cantidad_reg=$this->catd01_recargos_catastrales->findCount($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza);
            $this->set("cantidad_reg",$cantidad_reg);
	        $rs=$this->catd01_recargos_catastrales->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza,null,'porcentaje_industria ASC');
	        $this->set("data_tipo",$rs);
	        $this->set("modelo","catd01_recargos_catastrales");

}//fin mostrar tipos


function eliminar_items ($porcentaje_industria,$ano_ordenanza) {
	$this->layout = "ajax";
    $rs=$this->catd01_recargos_catastrales->execute("DELETE FROM catd01_recargos_catastrales WHERE ".$this->SQLCA()." and  porcentaje_industria=".$porcentaje_industria." and ano_ordenanza=".$ano_ordenanza);
    if($rs>1){
           	        $this->set("Message_existe","El Dato Fu&eacute; Eliminado Exitosamente");
    }else{
           	        $this->set("errorMessage","El Dato No Fu&eacute; Eliminado");
    }
    $cantidad_reg=$this->catd01_recargos_catastrales->findCount($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza);
            $this->set("cantidad_reg",$cantidad_reg);
}//fin eliminar_items

function editar_tipo ($porcentaje_industria,$ano_ordenanza,$id_up) {
	$this->layout = "ajax";
    $rs=$this->catd01_recargos_catastrales->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and porcentaje_industria=".$porcentaje_industria);
    $this->set("porcentaje_industria",$rs[0]["catd01_recargos_catastrales"]["porcentaje_industria"]);
    $this->set("porcentaje_servicios",$rs[0]["catd01_recargos_catastrales"]["porcentaje_servicios"]);
    $this->set("porcentaje_comercial",$rs[0]["catd01_recargos_catastrales"]["porcentaje_comercial"]);
    $this->set("porcentaje_arrendado",$rs[0]["catd01_recargos_catastrales"]["porcentaje_arrendado"]);
    $this->set("porcentaje_otro",$rs[0]["catd01_recargos_catastrales"]["porcentaje_otro"]);
   $this->set("i",$id_up);


}

function guardar_editar ($id_up) {
   $this->layout="ajax";
   $modelo_form="catp01_recargos_catastrales";
   if(isset($this->data[$modelo_form])){
      if(!empty($this->data[$modelo_form]["ano_ordenanza"]) && !empty($this->data[$modelo_form]["porcentaje_industria_edt"])  && !empty($this->data[$modelo_form]["porcentaje_servicios_edt"])  && !empty($this->data[$modelo_form]["porcentaje_comercial_edt"])  && !empty($this->data[$modelo_form]["porcentaje_arrendado_edt"])   && !empty($this->data[$modelo_form]["porcentaje_otro_edt"])){
           $ano_ordenanza=$this->data[$modelo_form]["ano_ordenanza"];
            $cod[0]=$this->Formato1($this->data[$modelo_form]["porcentaje_industria_edt"]);
			$cod[1]=$this->Formato1($this->data[$modelo_form]["porcentaje_servicios_edt"]);
			$cod[2]=$this->Formato1($this->data[$modelo_form]["porcentaje_comercial_edt"]);
			$cod[3]=$this->Formato1($this->data[$modelo_form]["porcentaje_arrendado_edt"]);
			$cod[4]=$this->Formato1($this->data[$modelo_form]["porcentaje_otro_edt"]);

	        if($this->catd01_recargos_catastrales->findCount($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza)!=0){
	            $rs=$this->catd01_recargos_catastrales->execute("UPDATE catd01_recargos_catastrales SET porcentaje_industria=".$cod[0].", porcentaje_servicios=".$cod[1]." , porcentaje_comercial=".$cod[2].", porcentaje_arrendado=".$cod[3]." , porcentaje_otro=".$cod[4]." WHERE ".$this->SQLCA()." and ano_ordenanza=".$ano_ordenanza);
                if($rs>1){
           	        $this->set("Message_existe","Los Datos Fueron Guardados Exitosamente");
                }else{
           	        $this->set("errorMessage","Los Datos No Fueron Guardados");
                }
	        }else{
	        	 $this->set("errorMessage","El Codigo Tipo ya se encuentra registrado");

	        }//coun
	        $rs=$this->catd01_recargos_catastrales->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza,null,'porcentaje_industria ASC');
	        $this->set("data_tipo",$rs);
	        $this->set("modelo","catd01_recargos_catastrales");
      }//fin if empty
   }//if isset
   $this->set("i",$id_up);

}//fin guardar

function cancelar_editar ($id_up) {
   $this->layout="ajax";
   $modelo_form="catp01_recargos_catastrales";
   if(isset($this->data[$modelo_form])){
      if(!empty($this->data[$modelo_form]["ano_ordenanza"])){
           $ano_ordenanza=$this->data[$modelo_form]["ano_ordenanza"];
	        $rs=$this->catd01_recargos_catastrales->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza,null,'porcentaje_industria ASC');
	        $this->set("data_tipo",$rs);
	        $this->set("modelo","catd01_recargos_catastrales");
      }//fin if empty
   }//if isset
   $this->set("i",$id_up);

}//fin guardar


function reporte_recargos_catastrales($var=null){

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
			$ano_ordenanza = $this->data['catp01_recargos_catastrales']['ano_ordenanza'];
			$ano_ordenanza == '' ? $ano_ordenanza = date('Y') : $ano_ordenanza;
			$datos=$this->catd01_recargos_catastrales->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza,null,'porcentaje_industria ASC');
			$_SESSION['ano_ordenanza_report']= $ano_ordenanza;
			$this->set('datos',$datos);
			$this->set('var',$var);
		}
	}
}


}//fin class
?>
