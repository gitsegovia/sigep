<?php


 class ccnp01AnexosController extends AppController {
    var $name    = 'ccnp01_anexos';
	var $uses    = array('ccnd01_tipo_directivo', 'ccnd02_proyectos_profesionales', 'ccnd02_proyectos','ccnd00_imagenes');
	var $helpers = array('Html','Ajax','Javascript', 'Sisap');





function checkSession(){
				if (!$this->Session->check('concejo_comunal')){
						$this->redirect('/salir');
						exit();
				}
}//fin checksession



 function beforeFilter(){
 	$this->checkSession();

 }



function index($id1=null, $id2=null){
	$this->layout="ajax";
    $cod_republica     = $this->Session->read('CC_republica');
	$cod_estado        = $this->Session->read('CC_estado');
	$cod_municipio     = $this->Session->read('CC_municipio');
	$cod_parroquia     = $this->Session->read('CC_parroquia');
	$cod_centro        = $this->Session->read('CC_centro');
	$cod_concejo       = $this->Session->read('CC_concejo');
	$ano               = $this->Session->read('concejos_comunal_id1');
	$cod_proyecto      = $this->Session->read('concejos_comunal_id2');
	$this->data=null;

	$this->set('cod_proyecto',$cod_proyecto);

	$cond="cod_republica='$cod_republica' and cod_estado='$cod_estado' and cod_municipio='$cod_municipio' and cod_parroquia='$cod_parroquia' and cod_centro='$cod_centro' and cod_concejo='$cod_concejo' and ano='$ano' and identificacion='$cod_proyecto'";

	$a=$this->ccnd00_imagenes->execute("select * from ccnd00_imagenes where ".$cond);
	if($a!=null){
		$this->set('eliminar','');
	}

	$vec1=$this->ccnd00_imagenes->findCount($cond." and cod_campo=1");
	if($vec1!=0){
		$this->set('existe_imagen1',true);
	}else{
		$this->set('existe_imagen1',false);
	}


	$vec2=$this->ccnd00_imagenes->findCount($cond." and cod_campo=2");
	if($vec2!=0){
		$this->set('existe_imagen2',true);
	}else{
		$this->set('existe_imagen2',false);
	}


	$vec3=$this->ccnd00_imagenes->findCount($cond." and cod_campo=3");
	if($vec3!=0){
		$this->set('existe_imagen3',true);
	}else{
		$this->set('existe_imagen3',false);
	}

	$vec4=$this->ccnd00_imagenes->findCount($cond." and cod_campo=4");
	if($vec4!=0){
		$this->set('existe_imagen4',true);
	}else{
		$this->set('existe_imagen4',false);
	}

	$vec5=$this->ccnd00_imagenes->findCount($cond." and cod_campo=5");
	if($vec5!=0){
		$this->set('existe_imagen5',true);
	}else{
		$this->set('existe_imagen5',false);
	}

	$vec6=$this->ccnd00_imagenes->findCount($cond." and cod_campo=6");
	if($vec6!=0){
		$this->set('existe_imagen6',true);
	}else{
		$this->set('existe_imagen6',false);
	}


	$vec7=$this->ccnd00_imagenes->findCount($cond." and cod_campo=7");
	if($vec7!=0){
		$this->set('existe_imagen7',true);
	}else{
		$this->set('existe_imagen7',false);
	}

	$vec8=$this->ccnd00_imagenes->findCount($cond." and cod_campo=8");
	if($vec8!=0){
		$this->set('existe_imagen8',true);
	}else{
		$this->set('existe_imagen8',false);
	}

	$vec9=$this->ccnd00_imagenes->findCount($cond." and cod_campo=9");
	if($vec9!=0){
		$this->set('existe_imagen9',true);
	}else{
		$this->set('existe_imagen9',false);
	}


	$vec10=$this->ccnd00_imagenes->findCount($cond." and cod_campo=10");
	if($vec10!=0){
		$this->set('existe_imagen10',true);
	}else{
		$this->set('existe_imagen10',false);
	}


	$vec11=$this->ccnd00_imagenes->findCount($cond." and cod_campo=11");
	if($vec11!=0){
		$this->set('existe_imagen11',true);
	}else{
		$this->set('existe_imagen11',false);
	}

	$vec12=$this->ccnd00_imagenes->findCount($cond." and cod_campo=12");
	if($vec12!=0){
		$this->set('existe_imagen12',true);
	}else{
		$this->set('existe_imagen12',false);
	}

	$vec13=$this->ccnd00_imagenes->findCount($cond." and cod_campo=13");
	if($vec13!=0){
		$this->set('existe_imagen13',true);
	}else{
		$this->set('existe_imagen13',false);
	}

	$vec14=$this->ccnd00_imagenes->findCount($cond." and cod_campo=14");
	if($vec14!=0){
		$this->set('existe_imagen14',true);
	}else{
		$this->set('existe_imagen14',false);
	}


}//index



function modificar(){
	$this->layout="ajax";

	 $cod_republica     = $this->Session->read('CC_republica');
	$cod_estado        = $this->Session->read('CC_estado');
	$cod_municipio     = $this->Session->read('CC_municipio');
	$cod_parroquia     = $this->Session->read('CC_parroquia');
	$cod_centro        = $this->Session->read('CC_centro');
	$cod_concejo       = $this->Session->read('CC_concejo');
	$ano               = $this->Session->read('concejos_comunal_id1');
	$cod_proyecto      = $this->Session->read('concejos_comunal_id2');
	$this->data=null;

	$this->set('cod_proyecto',$cod_proyecto);

	$cond="cod_republica='$cod_republica' and cod_estado='$cod_estado' and cod_municipio='$cod_municipio' and cod_parroquia='$cod_parroquia' and cod_centro='$cod_centro' and cod_concejo='$cod_concejo' and ano='$ano' and identificacion='$cod_proyecto'";

	$vec1=$this->ccnd00_imagenes->findCount($cond." and cod_campo=1");
	if($vec1!=0){
		$this->set('existe_imagen1',true);
	}else{
		$this->set('existe_imagen1',false);
	}


	$vec2=$this->ccnd00_imagenes->findCount($cond." and cod_campo=2");
	if($vec2!=0){
		$this->set('existe_imagen2',true);
	}else{
		$this->set('existe_imagen2',false);
	}


	$vec3=$this->ccnd00_imagenes->findCount($cond." and cod_campo=3");
	if($vec3!=0){
		$this->set('existe_imagen3',true);
	}else{
		$this->set('existe_imagen3',false);
	}

	$vec4=$this->ccnd00_imagenes->findCount($cond." and cod_campo=4");
	if($vec4!=0){
		$this->set('existe_imagen4',true);
	}else{
		$this->set('existe_imagen4',false);
	}

	$vec5=$this->ccnd00_imagenes->findCount($cond." and cod_campo=5");
	if($vec5!=0){
		$this->set('existe_imagen5',true);
	}else{
		$this->set('existe_imagen5',false);
	}

	$vec6=$this->ccnd00_imagenes->findCount($cond." and cod_campo=6");
	if($vec6!=0){
		$this->set('existe_imagen6',true);
	}else{
		$this->set('existe_imagen6',false);
	}


	$vec7=$this->ccnd00_imagenes->findCount($cond." and cod_campo=7");
	if($vec7!=0){
		$this->set('existe_imagen7',true);
	}else{
		$this->set('existe_imagen7',false);
	}

	$vec8=$this->ccnd00_imagenes->findCount($cond." and cod_campo=8");
	if($vec8!=0){
		$this->set('existe_imagen8',true);
	}else{
		$this->set('existe_imagen8',false);
	}

	$vec9=$this->ccnd00_imagenes->findCount($cond." and cod_campo=9");
	if($vec9!=0){
		$this->set('existe_imagen9',true);
	}else{
		$this->set('existe_imagen9',false);
	}


	$vec10=$this->ccnd00_imagenes->findCount($cond." and cod_campo=10");
	if($vec10!=0){
		$this->set('existe_imagen10',true);
	}else{
		$this->set('existe_imagen10',false);
	}


	$vec11=$this->ccnd00_imagenes->findCount($cond." and cod_campo=11");
	if($vec11!=0){
		$this->set('existe_imagen11',true);
	}else{
		$this->set('existe_imagen11',false);
	}

	$vec12=$this->ccnd00_imagenes->findCount($cond." and cod_campo=12");
	if($vec12!=0){
		$this->set('existe_imagen12',true);
	}else{
		$this->set('existe_imagen12',false);
	}

	$vec13=$this->ccnd00_imagenes->findCount($cond." and cod_campo=13");
	if($vec13!=0){
		$this->set('existe_imagen13',true);
	}else{
		$this->set('existe_imagen13',false);
	}

	$vec14=$this->ccnd00_imagenes->findCount($cond." and cod_campo=14");
	if($vec14!=0){
		$this->set('existe_imagen14',true);
	}else{
		$this->set('existe_imagen14',false);
	}



}



function eliminar(){
	$this->layout="ajax";
	$cod_republica     = $this->Session->read('CC_republica');
	$cod_estado        = $this->Session->read('CC_estado');
	$cod_municipio     = $this->Session->read('CC_municipio');
	$cod_parroquia     = $this->Session->read('CC_parroquia');
	$cod_centro        = $this->Session->read('CC_centro');
	$cod_concejo       = $this->Session->read('CC_concejo');
	$ano               = $this->Session->read('concejos_comunal_id1');
	$cod_proyecto      = $this->Session->read('concejos_comunal_id2');


	$cond="ano='$ano' and identificacion='$cod_proyecto'";

	$update=$this->ccnd02_proyectos->execute("delete from ccnd00_imagenes where cod_republica='$cod_republica' and cod_estado='$cod_estado' and cod_municipio='$cod_municipio' and cod_parroquia='$cod_parroquia' and cod_centro='$cod_centro' and cod_concejo='$cod_concejo' and ano='$ano' and identificacion='$cod_proyecto'");
	$this->set('errorMessage', 'los datos fueron eliminados con exito');

	$this->index();
	$this->render('index');


}

}//fin class

?>