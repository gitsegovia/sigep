<?php





 class ccnp01ResumenDeActividadesController extends AppController {
    var $name    = 'ccnp01_resumen_de_actividades';
	var $uses    = array('ccnd01_tipo_directivo','ccnd02_proyectos_actividades');
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



function index(){
	$this->layout="ajax";
    $cod_republica     = $this->Session->read('CC_republica');
	$cod_estado        = $this->Session->read('CC_estado');
	$cod_municipio     = $this->Session->read('CC_municipio');
	$cod_parroquia     = $this->Session->read('CC_parroquia');
	$cod_centro        = $this->Session->read('CC_centro');
	$cod_concejo       = $this->Session->read('CC_concejo');
	$ano               = $this->Session->read('concejos_comunal_id1');
	$cod_proyecto      = $this->Session->read('concejos_comunal_id2');

	$conditions  = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo." and ano=".$ano." and cod_proyecto='".$cod_proyecto."'";


	$sql="SELECT
			a.cod_actividad,
			a.denominacion,
			a.cantidad,
			a.porcentaje_iva,
			(select sum(b.costo_unitario) from ccnd02_proyectos_actividad_equipos b where ".$conditions." and b.cod_actividad=a.cod_actividad) as total_equipos,
			(select sum(c.costo_unitario) from ccnd02_proyectos_actividad_materiales c where ".$conditions." and c.cod_actividad=a.cod_actividad) as total_materiales,
			(select sum(d.costo_unitario) from ccnd02_proyectos_actividad_manoobra d where ".$conditions." and d.cod_actividad=a.cod_actividad) as total_manoobra
			from ccnd02_proyectos_actividades a where ".$conditions." order by a.cod_actividad asc";

	$datos=$this->ccnd02_proyectos_actividades->execute($sql);
	if($datos!=null){
		$this->set('datos',$datos);
	}else{
		$this->set('datos',null);
	}

	///////I.V.A//////////
	$iva=$this->ccnd02_proyectos_actividades->execute("select porcentaje_iva from cscd04_ordencompra_parametros where cod_dep=1 limit 1");
	$this->set('iva',$iva[0][0]['porcentaje_iva']);
	//////////////////////




}//index




}//fin function

?>