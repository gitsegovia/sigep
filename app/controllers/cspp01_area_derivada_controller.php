<?php
class Cspp01AreaDerivadaController extends AppController{
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

    $lista=  $this->cspd01->findAll(null,null,'cod_principal ASC');

	 $i=0;
	if($lista!=null){
	 foreach($lista as $x){
		 $i++;
		 $x1[]=$x['cspd01']['cod_principal'];
		 $x2[]=mascara($x['cspd01']['cod_principal'],2).' - '.$x['cspd01']['denominacion'];
		 $x3=array_combine($x1,$x2);
	 }
	 $this->set('datos',$x3);
	}else{
		 $this->set('datos',array());
	}
    $this->set("modelo","cspd01_area_derivada");



}//index


function select_cod_prin ($var=null) {
   $this->layout="ajax";
       	  $this->set('tipo','select');
       	  $this->set('seleccion',$var);
   	  $lista=  $this->cspd01->findAll(null,null,'cod_principal ASC');

	 	  $i=0;
			if($lista!=null){

	 		foreach($lista as $x){
		 		$i++;
		 		$x1[]=$x['cspd01']['cod_principal'];
		 		$x2[]=mascara($x['cspd01']['cod_principal'],2).' - '.$x['cspd01']['denominacion'];
		 		$x3=array_combine($x1,$x2);
			 }
	 			$this->set('datos',$x3);
			}else{
		 		$this->set('datos',array());
			}

	  	  $rs_u=$this->cspd01->findAll("cod_principal=".$var);
          $this->set('cod_principal',$rs_u[0]['cspd01']['cod_principal']);
          $this->set('deno_principal',$rs_u[0]['cspd01']['denominacion']);
         // echo $rs_u[0]['catd01_valor_construccion']['cod_tipo_caracteristica'];



}//fin funcion select_cod_tipo

function index2($var){
	$this->layout  = "ajax";

    $rs=  $this->cspd01_area_derivada->findAll('cod_principal='.$var,null,'cod_derivada DESC');






	if(empty($rs))$var1=1;
	else $var1=$rs[0]['cspd01_area_derivada']['cod_derivada']+1;

    $this->set("cod_siguiente",$var1);
    $rs=$this->cspd01_area_derivada->findAll('cod_principal='.$var,null,'cod_derivada ASC');
    $this->set("data_tipo",$rs);
    $this->set("modelo","cspd01_area_derivada");

    //$rs=$this->catd01_valor_construccion->findAll($this->SQLCA()." and ano_ordenanza=".$ano_actual,null,'cod_tipo_construccion,cod_tipo_caracteristica ASC');
    //$this->set("data_tipo",$rs);


}//index

function guardar () {
   $this->layout="ajax";
   $modelo_form="cspp01_area_derivada";
   if(isset($this->data[$modelo_form])){
      if(!empty($this->data[$modelo_form]["cod_derivada"])  && !empty($this->data[$modelo_form]["deno_derivada"])){
            $cod[0]=$this->data[$modelo_form]["cod_principal"];
			$cod[1]=$this->data[$modelo_form]["cod_derivada"];
			$cod[2]=$this->data[$modelo_form]["deno_derivada"];

	        if($this->cspd01_area_derivada->findCount("cod_principal=".$cod[0]." and cod_derivada=".$cod[1])==0){
	            $rs=$this->cspd01_area_derivada->execute("INSERT INTO cspd01_area_derivada VALUES (".$cod[0].",".$cod[1].",'".$cod[2]."');");
                if($rs>1){
           	        $this->set("Message_existe","Los Datos Fuerón Guardados Exitosamente");
                }else{
           	        $this->set("errorMessage","Los Datos No Fuerón Guardados");
                }
	        }else{
	        	 $this->set("errorMessage","El Código Tipo ya se encuentra registrado");
	        }//coun
      $this->set("modelo","cspd01_area_derivada");

    $rs=$this->cspd01_area_derivada->findAll("cod_principal=".$cod[0],null,'cod_derivada DESC',1);



	if(empty($rs))$var1=1;
	else $var1=$rs[0]['cspd01_area_derivada']['cod_derivada']+1;

    $this->set("cod_siguiente",$var1);
    $rs=$this->cspd01_area_derivada->findAll("cod_principal=".$cod[0],null,'cod_principal ASC');
	$this->set("data_tipo",$rs);
      }//fin if empty
   }//if isset
}//fin guardar




function eliminar_items ($cod_principal,$cod_derivada) {
	$this->layout = "ajax";
$this->set('cod_principal',$cod_principal);
if($this->v_cspd03_planteamientos->findCount($this->SQLCA()." and cod_principal=".$cod_principal." and cod_derivada=".$cod_derivada)==0){

	    $rs=$this->cspd01->execute("DELETE FROM cspd01_area_derivada WHERE "."cod_principal=".$cod_principal." and cod_derivada=".$cod_derivada);
    	if($rs>1){
           	        $this->set("Message_existe","El Dato Fu&eacute; Eliminado Exitosamente");
    	}else{
           	        $this->set("errorMessage","El Dato No Fu&eacute; Eliminado");

		}
}else $this->set("errorMessage","El Dato No Fu&eacute; Eliminado, Se encuentra registrado en una solicitud");

}//fin eliminar_items

function editar ($cod_principal,$cod_derivada,$id_up,$id_fila) {
	$this->layout = "ajax";
    $rs=$this->cspd01_area_derivada->findAll("cod_principal=".$cod_principal." and cod_derivada=".$cod_derivada);
    $this->set("cod_principal",$cod_principal);
    $this->set("cod_derivada",$cod_derivada);
    $this->set("denominacion",$rs[0]["cspd01_area_derivada"]["denominacion"]);
       $this->set("i",$id_up);
    $this->set("id_fila",$id_fila);
}

function guardar_editar ($cod_principal,$cod_derivada,$id_up,$id_fila) {
   $this->layout="ajax";
   $modelo_form="cspp01_area_derivada";




             $xc=$this->cspd01_area_derivada->findCount("cod_principal=".$cod_principal." and cod_derivada=".$cod_derivada);
	        if($xc!=0){
	            $rs=$this->cspd01_area_derivada->execute("UPDATE cspd01_area_derivada SET denominacion='".$this->data[$modelo_form]["deno_derivada1"]."' WHERE cod_principal=".$cod_principal." and cod_derivada=".$cod_derivada);
                if($rs>1){
           	        $this->set("Message_existe","Los Datos Fueron actualizado Exitosamente");
                }else{
           	        $this->set("errorMessage","Los Datos No Fueron actualizado");
                }
	        }
	        $rando = rand();
	        $rs=$this->cspd01_area_derivada->findAll("cod_principal=".$cod_principal." and cod_derivada=".$cod_derivada);

	   $this->set("i",$id_up);
   	   $this->set("id_fila",$id_fila);
       $this->set("cod_derivada",$cod_derivada);
       $this->set("denominacion",$rs[0]['cspd01_area_derivada']['denominacion']);




}//fin guardar editar

function cancelar_editar ($cod_principal,$cod_derivada,$id_up,$id_fila) {
   $this->layout="ajax";
    $rs=$this->cspd01_area_derivada->findAll("cod_principal=".$cod_principal." and cod_derivada=".$cod_derivada);
    $this->set("cod_derivada",$rs[0]["cspd01_area_derivada"]["cod_derivada"]);
    $this->set("denominacion",$rs[0]["cspd01_area_derivada"]["denominacion"]);
    $this->set("cod_principal",$cod_principal);





    $this->set("i",$id_up);
    $this->set("id_fila",$id_fila);
    $this->set("modelo","cspd01");

}//fin cancelar




}//fin class

?>
