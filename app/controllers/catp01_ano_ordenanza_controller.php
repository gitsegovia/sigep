<?php
class Catp01AnoOrdenanzaController extends AppController{
    var $uses = array('ccfd04_cierre_mes','catd01_ano_ordenanza');
    var $helpers = array('Html', 'Javascript', 'Ajax','Sisap','Form');

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
					return;
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
	if($this->catd01_ano_ordenanza->findCount($this->SQLCA())!=0){
	  $this->set('data',$this->catd01_ano_ordenanza->findAll($this->SQLCA()));
      $this->render('index2');
	}



}//index

function guardar () {
   $this->layout="ajax";
   $modelo_form="catp01_ano_ordenanza";
   if(isset($this->data[$modelo_form])){
      if(!empty($this->data[$modelo_form]["ano_actual"]) && !empty($this->data[$modelo_form]["ano_anterior"])){
           $ano_actual  =$this->data[$modelo_form]["ano_actual"];
           $ano_anterior=$this->data[$modelo_form]["ano_anterior"];
	       if($this->catd01_ano_ordenanza->findCount($this->SQLCA())==0){
	            $rs=$this->catd01_ano_ordenanza->execute("INSERT INTO catd01_ano_ordenanza VALUES (".$this->SQLCAIN().",".$ano_actual.",".$ano_anterior.");");
                if($rs>1){
           	        $this->set("Message_existe","Los Datos Fueron Guardados Exitosamente");
                }else{
           	        $this->set("errorMessage","Los Datos No Fueron Guardados");
                }
	        }else{
	        	 $this->set("errorMessage","el año de la ordenanza ya se encuentra registrado");
	        }//count
      }else{
          if(empty($this->data[$modelo_form]["ano_actual"])){
          	$this->set("errorMessage","Ingrese el año de la ordenanza actual");
          }else{
          	$this->set("errorMessage","Ingrese el año de la ordenanza anterior");
          }
      }
   }//if isset
}//fin guardar


function guardar_modificar () {
   $this->layout="ajax";
   $modelo_form="catp01_ano_ordenanza";
   if(isset($this->data[$modelo_form])){
      if(!empty($this->data[$modelo_form]["ano_actual"]) && !empty($this->data[$modelo_form]["ano_anterior"])){
           $ano_actual  =$this->data[$modelo_form]["ano_actual"];
           $ano_anterior=$this->data[$modelo_form]["ano_anterior"];
	       if($this->catd01_ano_ordenanza->findCount($this->SQLCA())!=0){
	             //$rs=$this->catd01_ano_ordenanza->execute("INSERT INTO catd01_ano_ordenanza VALUES (".$this->SQLCAIN().",".$ano_actual.",".$ano_anterior.");");
                 $rs=$this->catd01_ano_ordenanza->execute("UPDATE catd01_ano_ordenanza SET  ano_actual=".$ano_actual." , ano_anterior=".$ano_anterior." WHERE ".$this->SQLCA());
                if($rs>1){
           	        $this->set("Message_existe","Los Datos Fuerón actualizado Exitosamente");
                }else{
           	        $this->set("errorMessage","Los Datos No Fuerón actualizado");
                }
	        }else{
	        	 $this->set("errorMessage","El año de la ordenanza ya se encuentra registrado");
	        }//count
      }else{
          if(empty($this->data[$modelo_form]["ano_actual"])){
          	$this->set("errorMessage","Ingrese el año de la ordenanza actual");
          }else{
          	$this->set("errorMessage","Ingrese el año de la ordenanza anterior");
          }
      }
   }//if isset
   $this->render('guardar');
}//fin guardar



}//fin class
?>
