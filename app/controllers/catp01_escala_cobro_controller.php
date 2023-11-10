<?php
class Catp01EscalaCobroController extends AppController{
    var $uses = array('ccfd04_cierre_mes','catd01_escala_cobro','catd01_ano_ordenanza');
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
            $cantidad_reg=$this->catd01_escala_cobro->findCount($this->SQLCA()." and ano_ordenanza=".$ano_actual." and cod_tipo_inmueble=99");
            $this->set("cantidad_reg",$cantidad_reg);
	        $rs=$this->catd01_escala_cobro->findAll($this->SQLCA()." and ano_ordenanza=".$ano_actual." and cod_tipo_inmueble=99",null,'escala ASC');
	        $this->set("data_tipo",$rs);
	        $this->set("modelo","catd01_escala_cobro");

	        $cx=$this->catd01_escala_cobro->findCount($this->SQLCA()." and ano_ordenanza=".$ano_actual." and cod_tipo_inmueble=99");
            if($cx==0){
            	$this->set('escala',1);
            }else{
               $rs_e=$this->catd01_escala_cobro->findAll($this->SQLCA()." and ano_ordenanza=".$ano_actual." and cod_tipo_inmueble=99",null,'escala DESC',1);
               $this->set('escala',$rs_e[0]['catd01_escala_cobro']['escala']+1);
            }


}//index

function guardar () {
   $this->layout="ajax";
   $modelo_form="catp01_escala_cobro";
   if(isset($this->data[$modelo_form])){
      if(!empty($this->data[$modelo_form]["ano_ordenanza"]) && !empty($this->data[$modelo_form]["tipo_inmueble"]) && !empty($this->data[$modelo_form]["escala"])  && !empty($this->data[$modelo_form]["monto_desde"])  && !empty($this->data[$modelo_form]["monto_hasta"])  && !empty($this->data[$modelo_form]["porcentaje"])  && !empty($this->data[$modelo_form]["sustraendo"])){
           $ano_ordenanza=$this->data[$modelo_form]["ano_ordenanza"];
           $tipo_inmueble=$this->data[$modelo_form]["tipo_inmueble"];

           $cod[0]=$this->data[$modelo_form]["escala"];
		   $cod[1]=$this->Formato1($this->data[$modelo_form]["monto_desde"]);
		   $cod[2]=$this->Formato1($this->data[$modelo_form]["monto_hasta"]);
		   $cod[3]=$this->Formato1($this->data[$modelo_form]["porcentaje"]);
		   $cod[4]=$this->Formato1($this->data[$modelo_form]["sustraendo"]);

	        if($this->catd01_escala_cobro->findCount($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo_inmueble=".$tipo_inmueble." and escala=".$cod[0])==0){
	            $rs=$this->catd01_escala_cobro->execute("INSERT INTO catd01_escala_cobro VALUES (".$this->SQLCAIN().",".$ano_ordenanza.",".$tipo_inmueble.",".$cod[0].",".$cod[1].",".$cod[2].",".$cod[3].",".$cod[4].");");
                if($rs>1){
           	        $this->set("Message_existe","Los Datos Fueron Guardados Exitosamente");
                }else{
           	        $this->set("errorMessage","Los Datos No Fueron Guardados");
                }
	        }else{
	        	 $this->set("errorMessage","El Codigo Tipo ya se encuentra registrado");
	        }//count
	        $cantidad_reg=$this->catd01_escala_cobro->findCount($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo_inmueble=".$tipo_inmueble);
            $this->set("cantidad_reg",$cantidad_reg);
	        $rs=$this->catd01_escala_cobro->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo_inmueble=".$tipo_inmueble,null,'escala ASC');
	        $this->set("data_tipo",$rs);
	        $this->set("modelo","catd01_escala_cobro");


	        $cx=$this->catd01_escala_cobro->findCount($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo_inmueble=".$tipo_inmueble);
            if($cx==0){
            	$this->set('escala',1);
            }else{
               $rs_e=$this->catd01_escala_cobro->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo_inmueble=".$tipo_inmueble,null,'escala DESC',1);
               $this->set('escala',$rs_e[0]['catd01_escala_cobro']['escala']+1);
            }



      }//fin if empty
   }//if isset
}//fin guardar


function mostrar_tipos ($ano_ordenanza=null, $tipo_inmueble=null) {

   $this->layout="ajax";

            if(!isset($ano_ordenanza) || empty($ano_ordenanza)){
				$ano_ordenanza=date("Y");
            }else if(isset($this->data["catp01_escala_cobro"]["ano_ordenanza"])){
            	$ano_ordenanza=$this->data["catp01_escala_cobro"]["ano_ordenanza"];
            }else{
            	$ano_ordenanza=$ano_ordenanza;
            }

            if($tipo_inmueble!=null){
            	$tipo_inmueble = $tipo_inmueble;
            }else if(isset($this->data["catp01_escala_cobro"]["tipo_inmueble"])){
            	$tipo_inmueble=$this->data["catp01_escala_cobro"]["tipo_inmueble"];
            }

	        $this->set("modelo","catd01_escala_cobro");

            $cantidad_reg=$this->catd01_escala_cobro->findCount($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo_inmueble=".$tipo_inmueble);
            $this->set("cantidad_reg",$cantidad_reg);
	        $rs=$this->catd01_escala_cobro->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo_inmueble=".$tipo_inmueble,null,'escala ASC');
	        $this->set("data_tipo",$rs);

	        $cx=$this->catd01_escala_cobro->findCount($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo_inmueble=".$tipo_inmueble);
            if($cx==0){
            	$this->set('escala',1);
            }else{
               $rs_e=$this->catd01_escala_cobro->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo_inmueble=".$tipo_inmueble,null,'escala DESC',1);
               $this->set('escala',$rs_e[0]['catd01_escala_cobro']['escala']+1);
            }
}//fin mostrar tipos


function eliminar_items ($escala,$ano_ordenanza,$tipo_inmueble) {

	$this->layout = "ajax";
    $rs=$this->catd01_escala_cobro->execute("DELETE FROM catd01_escala_cobro WHERE ".$this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo_inmueble=".$tipo_inmueble." and  escala=".$escala);
    if($rs>1){
           	        $this->set("Message_existe","El Dato Fu&eacute; Eliminado Exitosamente");
    }else{
           	        $this->set("errorMessage","El Dato No Fu&eacute; Eliminado");
    }
    $cantidad_reg=$this->catd01_escala_cobro->findCount($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo_inmueble=".$tipo_inmueble);
            $this->set("cantidad_reg",$cantidad_reg);
}//fin eliminar_items


function editar_tipo ($escala,$ano_ordenanza,$tipo_inmueble,$id_up,$id_fila) {
    $this->layout = "ajax";

    $rs=$this->catd01_escala_cobro->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo_inmueble=".$tipo_inmueble." and escala=".$escala);

    $this->set("ano_ordenanza",$rs[0]["catd01_escala_cobro"]["ano_ordenanza"]);
    $this->set("cod_tipo_inmueble",$rs[0]["catd01_escala_cobro"]["cod_tipo_inmueble"]);
    $this->set("escala",$rs[0]["catd01_escala_cobro"]["escala"]);
    $this->set("monto_desde",$rs[0]["catd01_escala_cobro"]["monto_desde"]);
    $this->set("monto_hasta",$rs[0]["catd01_escala_cobro"]["monto_hasta"]);
    $this->set("porcentaje",$rs[0]["catd01_escala_cobro"]["porcentaje"]);
    $this->set("sustraendo",$rs[0]["catd01_escala_cobro"]["sustraendo"]);
    $this->set("i",$id_up);
    $this->set("id_fila",$id_fila);
}

function guardar_editar ($escala,$id_up,$id_fila) {
   $this->layout="ajax";
   $modelo_form="catp01_escala_cobro";
   if(isset($this->data[$modelo_form])){
      if(!empty($this->data[$modelo_form]["ano_ordenanza"]) && !empty($this->data[$modelo_form]["tipo_inmueble"]) && !empty($this->data[$modelo_form]["escala_edt"]) && !empty($this->data[$modelo_form]["monto_desde_edt"])  && !empty($this->data[$modelo_form]["monto_hasta_edt"])  && !empty($this->data[$modelo_form]["porcentaje_edt"])   && !empty($this->data[$modelo_form]["sustraendo_edt"])){
            $ano_ordenanza=$this->data[$modelo_form]["ano_ordenanza"];
            $tipo_inmueble=$this->data[$modelo_form]["tipo_inmueble"];
            $cod[0]=$this->data[$modelo_form]["escala_edt"];
			$cod[1]=$this->Formato1($this->data[$modelo_form]["monto_desde_edt"]);
			$cod[2]=$this->Formato1($this->data[$modelo_form]["monto_hasta_edt"]);
			$cod[3]=$this->Formato1($this->data[$modelo_form]["porcentaje_edt"]);
			$cod[4]=$this->Formato1($this->data[$modelo_form]["sustraendo_edt"]);

	        if($this->catd01_escala_cobro->findCount($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo_inmueble=".$tipo_inmueble." and escala=".$escala)!=0){
	            $rs=$this->catd01_escala_cobro->execute("UPDATE catd01_escala_cobro SET  monto_desde=".$cod[1]." , monto_hasta=".$cod[2].", porcentaje=".$cod[3]." , sustraendo=".$cod[4]." WHERE ".$this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo_inmueble=".$tipo_inmueble." and escala=".$escala."");
                if($rs>1){
           	        $this->set("Message_existe","Los Datos Fueron Guardados Exitosamente");
                }else{
           	        $this->set("errorMessage","Los Datos No Fueron Guardados");
                }
	        }else{
	        	 $this->set("errorMessage","El Codigo Tipo ya se encuentra registrado");

	        }//coun
	        $rs=$this->catd01_escala_cobro->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo_inmueble=".$tipo_inmueble." and escala=".$escala);

	        $this->set("data_tipo",$rs);
	        $this->set("modelo","catd01_escala_cobro");
	        $this->set("modelo_form","catd01_escala_cobro");

      }//fin if empty
   }//if isset
   $this->set("i",$id_up);
   $this->set("id_fila",$id_fila);

}//fin guardar

function cancelar_editar ($escala,$id_up,$id_fila) {
   $this->layout="ajax";
   $modelo_form="catp01_escala_cobro";
   if(isset($this->data[$modelo_form])){
      if(!empty($this->data[$modelo_form]["ano_ordenanza"])){
           $ano_ordenanza=$this->data[$modelo_form]["ano_ordenanza"];
           $tipo_inmueble=$this->data[$modelo_form]["tipo_inmueble"];
	        $rs=$this->catd01_escala_cobro->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo_inmueble=".$tipo_inmueble." and escala=".$escala,null,'escala ASC');
	        $this->set("data_tipo",$rs);
	        $this->set("modelo","catd01_escala_cobro");
      }//fin if empty
   }//if isset
   $this->set("i",$id_up);
   $this->set("id_fila",$id_fila);

}//fin guardar



function reporte_escala_ordenanza($var=null, $tipo_inmueble=null){
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
			$this->set('var',$var);

			$ano_ordenanza = $this->data['catp01_escala_cobro']['ano_ordenanza'];
			$tipo_inmueble = $this->data['catp01_escala_cobro']['tipo_inmueble'];

			if($tipo_inmueble=='0' || $tipo_inmueble==''){
				$sql_tipo_inmueble = "";
			}else{
				$sql_tipo_inmueble = " and cod_tipo_inmueble=".$tipo_inmueble;
			}

			$ano_ordenanza == '' ? $ano_ordenanza = date('Y') : $ano_ordenanza;
			$datos=$this->catd01_escala_cobro->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza.$sql_tipo_inmueble,null,'escala ASC');
			$_SESSION['ano_ordenanza_report']= $ano_ordenanza;
			$this->set('datos',$datos);
			$this->set('var',$var);
		}
	}
}//reporte_escala_ordenanza


}//fin class
?>
