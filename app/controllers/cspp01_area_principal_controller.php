<?php
class Cspp01AreaPrincipalController extends AppController{
    var $uses = array('catd01_ano_ordenanza','cugd90_municipio_defecto','cugd01_republica','cugd01_estados','cugd01_municipios','ccfd04_cierre_mes','catd01_valor_construccion','cspd01','cspd01_area_derivada','v_cspd03_planteamientos');
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

      $this->set("modelo","cspd01");

    $rs=$this->cspd01->findAll(null,null,'cod_principal DESC');

	foreach($rs as $x){
		$var1=$x['cspd01']['cod_principal']+1;
		break;
	}

	if(empty($rs))$var1=1;

    $this->set("cod_siguiente",$var1);
    $rs=$this->cspd01->findAll(null,null,'cod_principal ASC');
    $this->set("data_tipo",$rs);

}//index


function guardar () {
   $this->layout="ajax";
   $modelo_form="cspp01_area_principal";
   if(isset($this->data[$modelo_form])){
      if(!empty($this->data[$modelo_form]["cod_principal"])  && !empty($this->data[$modelo_form]["denominacion"])){
            $cod[0]=$this->data[$modelo_form]["cod_principal"];
			$cod[1]=$this->data[$modelo_form]["denominacion"];

	        if($this->cspd01->findCount("cod_principal='".$cod[0]."'")==0){
	            $rs=$this->catd01_valor_construccion->execute("INSERT INTO cspd01_area_principal VALUES (".$cod[0].",'".$cod[1]."');");
                if($rs>1){
           	        $this->set("Message_existe","Los Datos Fuerón Guardados Exitosamente");
                }else{
           	        $this->set("errorMessage","Los Datos No Fuerón Guardados");
                }
	        }else{
	        	 $this->set("errorMessage","El Código Tipo ya se encuentra registrado");
	        }//coun
      $this->set("modelo","cspd01");

    $rs=$this->cspd01->findAll(null,null,'cod_principal DESC');

	foreach($rs as $x){
		$var1=$x['cspd01']['cod_principal']+1;
		break;
	}

	if(empty($rs))$var1=1;

    $this->set("cod_siguiente",$var1);
    $rs=$this->cspd01->findAll(null,null,'cod_principal ASC');
    $this->set("data_tipo",$rs);
      }//fin if empty
   }//if isset
}//fin guardar



function eliminar_items ($cod_principal) {
	$this->layout = "ajax";

		if($this->v_cspd03_planteamientos->findCount($this->SQLCA()." and cod_principal=".$cod_principal)==0){

	    	$rs=$this->cspd01->execute("DELETE FROM cspd01_area_principal WHERE "."cod_principal=".$cod_principal);
    		if($rs>1){
           	        $this->set("Message_existe","El Dato Fu&eacute; Eliminado Exitosamente");
    		}else{
           	        $this->set("errorMessage","El Dato No Fu&eacute; Eliminado");

			}
		}else $this->set("errorMessage","El Dato No Fu&eacute; Eliminado, Se encuentra registrado en una solicitud");

}//fin eliminar_items

function editar ($cod_principal,$id_up,$id_fila) {
	$this->layout = "ajax";
    $rs=$this->cspd01->findAll("cod_principal=".$cod_principal);
    $this->set("cod_principal",$rs[0]["cspd01"]["cod_principal"]);
    $this->set("denominacion",$rs[0]["cspd01"]["denominacion"]);
    $this->set("i",$id_up);
    $this->set("id_fila",$id_fila);
}


function guardar_editar ($cod_principal,$id_up,$id_fila) {
   $this->layout="ajax";
   $modelo_form="cspp01_area_principal";




             $xc=$this->cspd01->findCount("cod_principal=".$cod_principal);
	        if($xc!=0){
	            $rs=$this->cspd01->execute("UPDATE cspd01_area_principal SET denominacion='".$this->data[$modelo_form]["denominacion"]."' WHERE cod_principal=".$cod_principal);
                if($rs>1){
           	        $this->set("Message_existe","Los Datos Fueron actualizado Exitosamente");
                }else{
           	        $this->set("errorMessage","Los Datos No Fueron actualizado");
                }
	        }
	        $rando = rand();
	        $rs=$this->cspd01->findAll("cod_principal=".$cod_principal);

	   $this->set("i",$id_up);
   	   $this->set("id_fila",$id_fila);
       $this->set("cod_principal",$cod_principal);
       $this->set("denominacion",$rs[0]['cspd01']['denominacion']);




}//fin guardar editar

function cancelar_editar ($cod_principal,$id_up,$id_fila) {
   $this->layout="ajax";
    $rs=$this->cspd01->findAll("cod_principal=".$cod_principal);
    $this->set("cod_principal",$rs[0]["cspd01"]["cod_principal"]);
    $this->set("denominacion",$rs[0]["cspd01"]["denominacion"]);





    $this->set("i",$id_up);
    $this->set("id_fila",$id_fila);
    $this->set("modelo","cspd01");

}//fin cancelar



}//fin class
?>
