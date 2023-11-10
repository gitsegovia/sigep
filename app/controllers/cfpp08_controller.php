<?php
/*
 * Fecha: 15/06/2007
 *
 * Por Luis Alfredo Diaz Jaramillo
 *
 * Herramienta utilizada: easyEclipse
 * cake
 */

 class Cfpp08Controller extends AppController{


 	var $uses = array('cfpd08', 'cfpd01_formulacion');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf');


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



function index(){
    $this->layout = "ajax";

	 //A partir de aqui esta el codiog para bajar el año presupuestario por defecto
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

	$year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
	$dato = null;
	foreach($year as $year){
		$dato = $year['cfpd01_formulacion']['ano_formular'];
	}

	if(!empty($dato)){

		$this->set('year', $dato);

	}else{
		$this->set('year', '');
	}

// fin del codigo

	$this->set('entidad_federal', $this->Session->read('entidad_federal'));


}


function cancelar(){


$this->layout = "ajax";


}



function ejercicio_presupuestario($ejercicio_fiscal=null){

                $this->layout = "ajax";
   if($ejercicio_fiscal!=null){$year= $ejercicio_fiscal;}else if($this->data['cfpp08']['ano']){$year = $this->data['cfpp08']['ano'];}

$data = "";
$sql_re = "cod_presi=".$this->Session->read('SScodpresi')."  and    cod_entidad=".$this->Session->read('SScodentidad')."  and  cod_tipo_inst=".$this->Session->read('SScodtipoinst')."  and cod_inst=".$this->Session->read('SScodinst')."  and ejercicio_fiscal=".$year."  ";

 if($this->cfpd08->findCount($sql_re) != 0){
	$data = $this->cfpd08->findAll($sql_re, null, null, null);
	$this->set('data', $data);
}

$this->set('ejercicio_fiscal', $year);

$this->set('Message', 'Este ejercico presupuestario esta vacio');




}








function guardar($ejercicio_fiscal=null){


$this->layout = "ajax";


$sql = "INSERT INTO  cfpd08 (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, ejercicio_fiscal, domicilio, ciudad, telefonos, dir_internet, fax, cod_postal, gobernador, contralor, presi_consejo_legisla, director_presu) ";
$sql .= "VALUES ('".$this->Session->read('SScodpresi')."', '".$this->Session->read('SScodentidad')."', '".$this->Session->read('SScodtipoinst')."', '".$this->Session->read('SScodinst')."',  '".$this->data['cfpp08']['ano']."', '".$this->data['cfpp08']['domicilio_legal']."',  '".$this->data['cfpp08']['ciudad']."',  '".$this->data['cfpp08']['telefono']."',  '".$this->data['cfpp08']['email']."',  '".$this->data['cfpp08']['fax']."',  '".$this->data['cfpp08']['cod_postal']."',  ";
$sql .= "'".$this->data['cfpp08']['nombre_gobernador']."', '".$this->data['cfpp08']['nombre_contralor']."',  '".$this->data['cfpp08']['nombre_presidente_consejo_legislativo']."',  '".$this->data['cfpp08']['director_presupuesto']."') ";

 $var_ve = $this->cfpd08->execute($sql);

if($var_ve>1){$this->set('Message_existe', 'La Identificaci&oacute;n de la Entidad Federal a sido realizada con exito');}else{$this->set('errorMessage', 'Los datos no fueron guardados');}


 $sql_re = "cod_presi=".$this->Session->read('SScodpresi')."  and    cod_entidad=".$this->Session->read('SScodentidad')."  and  cod_tipo_inst=".$this->Session->read('SScodtipoinst')."  and cod_inst=".$this->Session->read('SScodinst')."  and ejercicio_fiscal=".$ejercicio_fiscal."  ";
$data = $this->cfpd08->findAll($sql_re, null, null, null);

if($this->cfpd08->findCount($sql_re) != 0){
	$data = $this->cfpd08->findAll($sql_re, null, null, null);
				  $this->set('data', $data);
	}

$this->set('ejercicio_fiscal', $ejercicio_fiscal);



}







function consulta($ejercicio_fiscal=null){

$this->layout = "ajax";


     /**   //$this->Session->read('Usuario');
    	$this->Session->read('SScodpresi');
    	$this->Session->read('SScodentidad');
    	$this->Session->read('SScodtipoinst');
    	$this->Session->read('SScodinst');
    	//$this->Session->read('SScoddep');*/



  $data = "";
  $year = "";

   if($ejercicio_fiscal!=null){$year= $ejercicio_fiscal; }else if($this->data['cfpp08']['ano']){$year = $this->data['cfpp08']['ano'];}



	if($year!=""){


		   $sql_re = "cod_presi=".$this->Session->read('SScodpresi')."  and    cod_entidad=".$this->Session->read('SScodentidad')."  and  cod_tipo_inst=".$this->Session->read('SScodtipoinst')."  and cod_inst=".$this->Session->read('SScodinst')."  and ejercicio_fiscal=".$year."  ";
		   $this->set('ejercicio_fiscal', $year);

	}else{

		 $sql_re = "cod_presi=".$this->Session->read('SScodpresi')."  and    cod_entidad=".$this->Session->read('SScodentidad')."  and  cod_tipo_inst=".$this->Session->read('SScodtipoinst')."  and cod_inst=".$this->Session->read('SScodinst')." ";

	}


 $sql_aux = "cod_presi=".$this->Session->read('SScodpresi')."  and    cod_entidad=".$this->Session->read('SScodentidad')."  and  cod_tipo_inst=".$this->Session->read('SScodtipoinst')."  and cod_inst=".$this->Session->read('SScodinst')." ";


 if($this->cfpd08->findCount($sql_re) != 0){
	$aux = $this->cfpd08->findAll($sql_aux, null, 'ejercicio_fiscal  ASC', null);
	$data = $this->cfpd08->findAll($sql_re, null, 'ejercicio_fiscal  DESC', null);
				  $this->set('data', $data);

				  $siguiente = "";
				  $anterior = "";
				  $i = 0;

				   foreach ($aux as $datos){$i++;

	 					 	$aux_ejercicio_fiscal[$i] = $datos['cfpd08']['ejercicio_fiscal'];



				}//fin for

				for($a=1; $a<=$i; $a++){

							if($ejercicio_fiscal == $aux_ejercicio_fiscal[$a]){

										if(($a-1)>=1){$anterior = $aux_ejercicio_fiscal[$a-1]; }
										if(($a+1)<=$i){$siguiente = $aux_ejercicio_fiscal[$a+1]; }

							}else if($ejercicio_fiscal==null && $siguiente==""){

							        if(isset($aux_ejercicio_fiscal[$a + 1])){$siguiente = $aux_ejercicio_fiscal[$a + 1];}

							}


				}


				  if($siguiente!=""){$this->set('siguiente', $siguiente); }
	    		  if($anterior!=""){$this->set('anterior', $anterior);}



	}else{$this->set('Message', 'No Existen Datos');  $this->set('data', '');}







}






function eliminar($ejercicio_fiscal=null){

$this->layout = "ajax";

 /*$this->Session->read('cod_presi');
	 $this->Session->read('cod_entidad');
	 $this->Session->read('cod_tipo_inst');
	 $this->Session->read('cod_inst');*/

$sql = "DELETE  FROM cfpd08  WHERE  cod_presi=".$this->Session->read('SScodpresi')."  and    cod_entidad=".$this->Session->read('SScodentidad')."  and  cod_tipo_inst=".$this->Session->read('SScodtipoinst')."  and cod_inst=".$this->Session->read('SScodinst')."  and ejercicio_fiscal=".$ejercicio_fiscal."  ";

$this->cfpd08->execute($sql);
$this->set('Message', 'La Identificaci&oacute;n de la Entidad Federal a sido Eliminada');


    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;


$year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
	$dato = null;
	foreach($year as $year){
		$dato = $year['cfpd01_formulacion']['ano_formular'];
	}

	if(!empty($dato)){

		$this->set('year', $dato);

	}else{
		$this->set('year', '');
	}

}//fin function












function modificar($ejercicio_fiscal=null){

$this->layout = "ajax";

    $data = "";
      /*$this->Session->read('cod_presi');
	 $this->Session->read('cod_entidad');
	 $this->Session->read('cod_tipo_inst');
	 $this->Session->read('cod_inst');*/

    //$sql_re = "cod_presi=".."  and    cod_entidad=".."  and  cod_tipo_inst=".."  and cod_inst=".."  and ejercicio_fiscal=".."  ";

$sql_re = "cod_presi=".$this->Session->read('SScodpresi')."  and    cod_entidad=".$this->Session->read('SScodentidad')."  and  cod_tipo_inst=".$this->Session->read('SScodtipoinst')."  and cod_inst=".$this->Session->read('SScodinst')."  and ejercicio_fiscal=".$ejercicio_fiscal."  ";
if($this->cfpd08->findCount($sql_re) != 0){
	$data = $this->cfpd08->findAll($sql_re, null, null, null);
				  $this->set('data', $data);
	}

$this->set('ejercicio_fiscal', $ejercicio_fiscal);

}









function guardar_modificacion($ejercicio_fiscal=null){


             $this->layout = "ajax";

  /*$this->Session->read('cod_presi');
	 $this->Session->read('cod_entidad');
	 $this->Session->read('cod_tipo_inst');
	 $this->Session->read('cod_inst');*/


$sql = "UPDATE cfpd08  SET  domicilio ='".$this->data['cfpp08']['domicilio_legal']."', ciudad ='".$this->data['cfpp08']['ciudad']."', telefonos ='".$this->data['cfpp08']['telefono']."', dir_internet ='".$this->data['cfpp08']['email']."', fax ='".$this->data['cfpp08']['fax']."', cod_postal ='".$this->data['cfpp08']['cod_postal']."',  gobernador ='".$this->data['cfpp08']['nombre_gobernador']."', contralor ='".$this->data['cfpp08']['nombre_contralor']."', presi_consejo_legisla ='".$this->data['cfpp08']['nombre_presidente_consejo_legislativo']."',  director_presu ='".$this->data['cfpp08']['director_presupuesto']."'  WHERE   ";
$sql .= "cod_presi=".$this->Session->read('SScodpresi')."  and    cod_entidad=".$this->Session->read('SScodentidad')."  and  cod_tipo_inst=".$this->Session->read('SScodtipoinst')."  and cod_inst=".$this->Session->read('SScodinst')."  and ejercicio_fiscal=".$ejercicio_fiscal."  ";
 //$sql .= "cod_presi='".."  and    cod_entidad='".."  and  cod_tipo_inst='".."  and cod_inst='".."  and ejercicio_fiscal=".."  ";

 $var_ve = $this->cfpd08->execute($sql);
if($var_ve>1){$this->set('Message_existe', 'Los datos fueron modificados');}else{$this->set('errorMessage', 'Los datos no fueron guardados');}

$sql_re = "cod_presi=".$this->Session->read('SScodpresi')."  and    cod_entidad=".$this->Session->read('SScodentidad')."  and  cod_tipo_inst=".$this->Session->read('SScodtipoinst')."  and cod_inst=".$this->Session->read('SScodinst')."  and ejercicio_fiscal=".$ejercicio_fiscal."  ";
if($this->cfpd08->findCount($sql_re) != 0){
	$data = $this->cfpd08->findAll($sql_re, null, null, null);
				  $this->set('data', $data);
	}

$this->set('ejercicio_fiscal', $ejercicio_fiscal);

}







 function reporte($var=null)
        {
            $this->layout = 'pdf';
            if($var==null){
            	$vector_cfpp08=$this->cfpd08->findAll();
            }else{
            	$vector_cfpp08=$this->cfpd08->findAll('ejercicio_fiscal='.$var);
            }
            $this->set('DATOS',$vector_cfpp08);
            //$this->render();
        }



 }//FIN CLASS


?>
