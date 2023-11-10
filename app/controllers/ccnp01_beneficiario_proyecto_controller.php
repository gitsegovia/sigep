<?php


 class ccnp01BeneficiarioProyectoController extends AppController {
    var $name    = 'ccnp01_beneficiario_proyecto';
	var $uses    = array('ccnd01_tipo_directivo', 'ccnd02_proyectos_profesionales', 'ccnd02_proyectos');
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

	$datos=$this->ccnd02_proyectos->execute("select * from ccnd02_proyectos where cod_republica='$cod_republica' and cod_estado='$cod_estado' and cod_municipio='$cod_municipio' and cod_parroquia='$cod_parroquia' and cod_centro='$cod_centro' and cod_concejo='$cod_concejo' and ano='$ano' and cod_proyecto='$cod_proyecto'");
	if($datos[0][0]['resultados_inmediatos']!='0'){
		$this->datos();
		$this->render('datos');
	}
}//index



function guardar(){
	$this->layout="ajax";
//	pr($this->data);
	$cod_republica     = $this->Session->read('CC_republica');
	$cod_estado        = $this->Session->read('CC_estado');
	$cod_municipio     = $this->Session->read('CC_municipio');
	$cod_parroquia     = $this->Session->read('CC_parroquia');
	$cod_centro        = $this->Session->read('CC_centro');
	$cod_concejo       = $this->Session->read('CC_concejo');
	$ano               = $this->Session->read('concejos_comunal_id1');
	$cod_proyecto      = $this->Session->read('concejos_comunal_id2');

	if(empty($this->data['ccnp01_justificacion_proyecto']['resultado_inmediato'])){
		$this->set('errorMessage', 'Debe ingresar los resultados inmediatos');

	}else if(empty($this->data['ccnp01_justificacion_proyecto']['mediano_plazo'])){
		$this->set('errorMessage', 'Debe ingresar los resultados a mediano plazo');

	}else if(empty($this->data['ccnp01_justificacion_proyecto']['impacto_economico'])){
		$this->set('errorMessage', 'ingrese el impacto economico');

	}else if(empty($this->data['ccnp01_justificacion_proyecto']['impacto_social'])){
		$this->set('errorMessage', 'ingrese el impacto social');

	}else if(empty($this->data['ccnp01_justificacion_proyecto']['impacto_ambiental'])){
		$this->set('errorMessage', 'ingrese el impacto ambiental');

	}else if(empty($this->data['ccnp01_justificacion_proyecto']['personas_beneficiadas'])){
		$this->set('errorMessage', 'ingrese el número de personas beneficiadas');

	}else if(empty($this->data['ccnp01_justificacion_proyecto']['directos_indirectos'])){
		$this->set('errorMessage', 'ingrese el número de empleos directos e indirectos');

	}else{

		$resultado_inmediato=$this->data['ccnp01_justificacion_proyecto']['resultado_inmediato'];
		$mediano_plazo=$this->data['ccnp01_justificacion_proyecto']['mediano_plazo'];
		$impacto_economico=$this->data['ccnp01_justificacion_proyecto']['impacto_economico'];
		$impacto_social=$this->data['ccnp01_justificacion_proyecto']['impacto_social'];
		$impacto_ambiental=$this->data['ccnp01_justificacion_proyecto']['impacto_ambiental'];
		$personas_beneficiadas=$this->data['ccnp01_justificacion_proyecto']['personas_beneficiadas'];
		$directos_indirectos=$this->data['ccnp01_justificacion_proyecto']['directos_indirectos'];

		$datos=$this->ccnd02_proyectos->execute("select * from ccnd02_proyectos where cod_republica='$cod_republica' and cod_estado='$cod_estado' and cod_municipio='$cod_municipio' and cod_parroquia='$cod_parroquia' and cod_centro='$cod_centro' and cod_concejo='$cod_concejo' and ano='$ano' and cod_proyecto='$cod_proyecto'");
		$update=$this->ccnd02_proyectos->execute("update ccnd02_proyectos set resultados_inmediatos='$resultado_inmediato',resultados_mediano_plazo='$mediano_plazo',impacto_economico='$impacto_economico',impacto_social='$impacto_social',impacto_ambiental='$impacto_ambiental',beneficiarios='$personas_beneficiadas',empleos_generados='$directos_indirectos' where cod_republica='$cod_republica' and cod_estado='$cod_estado' and cod_municipio='$cod_municipio' and cod_parroquia='$cod_parroquia' and cod_centro='$cod_centro' and cod_concejo='$cod_concejo' and ano='$ano' and cod_proyecto='$cod_proyecto'");

		if($datos[0][0]['resultados_inmediatos']=='0'){//aqui para que guarde los datos
			if($update>1){
				$this->set('Message_existe', 'registro exitoso');
				echo" <script> ver_documento('/ccnp01_beneficiario_proyecto/datos','tab_pestana_5'); </script>";
			}else{
				$this->set('errorMessage', 'los datos no pudieron ser registrados');
			}
		}else{//aqui para que modifique los datos
			if($update>1){
				$this->set('Message_existe', 'los datos se actualizaron correctamente');
				echo" <script> ver_documento('/ccnp01_beneficiario_proyecto/datos','tab_pestana_5'); </script>";
			}else{
				$this->set('errorMessage', 'los datos no pudieron ser modificados');
			}
		}



	}


}//fin guardar

function datos($var=null){
	$this->layout="ajax";
	$cod_republica     = $this->Session->read('CC_republica');
	$cod_estado        = $this->Session->read('CC_estado');
	$cod_municipio     = $this->Session->read('CC_municipio');
	$cod_parroquia     = $this->Session->read('CC_parroquia');
	$cod_centro        = $this->Session->read('CC_centro');
	$cod_concejo       = $this->Session->read('CC_concejo');
	$ano               = $this->Session->read('concejos_comunal_id1');
	$cod_proyecto      = $this->Session->read('concejos_comunal_id2');

	$datos=$this->ccnd02_proyectos->execute("select * from ccnd02_proyectos where cod_republica='$cod_republica' and cod_estado='$cod_estado' and cod_municipio='$cod_municipio' and cod_parroquia='$cod_parroquia' and cod_centro='$cod_centro' and cod_concejo='$cod_concejo' and ano='$ano' and cod_proyecto='$cod_proyecto'");
	$this->set('datos',$datos);

	if(isset($var)){
		$this->set('modificar','');
		$this->set('Message_existe', 'Proceda a modificar los datos');
	}
}//fin datos




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

	$update=$this->ccnd02_proyectos->execute("update ccnd02_proyectos set resultados_inmediatos='0',resultados_mediano_plazo='0',impacto_economico='0',impacto_social='0',impacto_ambiental='0',beneficiarios='0',empleos_generados='0' where cod_republica='$cod_republica' and cod_estado='$cod_estado' and cod_municipio='$cod_municipio' and cod_parroquia='$cod_parroquia' and cod_centro='$cod_centro' and cod_concejo='$cod_concejo' and ano='$ano' and cod_proyecto='$cod_proyecto'");
	$this->set('errorMessage', 'los datos fueron eliminados con exito');

	$this->index();
	$this->render('index');


}

}//fin class

?>