<?php



 class ccnp01DescripcionProyectoController extends AppController {
    var $name    = 'ccnp01_descripcion_proyecto';
	var $uses    = array('ccnd01_tipo_directivo','ccnd02_proyectos');
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
	if($datos[0][0]['sintesis_propuesta']!='0'){
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

	if(empty($this->data['ccnp01_justificacion_proyecto']['sintesis_propuesta'])){
		$this->set('errorMessage', 'ingrese la sintesis de la propuesta');

	}else if(empty($this->data['ccnp01_justificacion_proyecto']['objetivo_general'])){
		$this->set('errorMessage', 'ingrese el objetivo general');

	}else if(empty($this->data['ccnp01_justificacion_proyecto']['objetivo_especifico'])){
		$this->set('errorMessage', 'ingrese los objetivos especificos');

	}else if(empty($this->data['ccnp01_justificacion_proyecto']['metas_fisicas'])){
		$this->set('errorMessage', 'ingrese las metas fisicas');

	}else{

		$sintesis_propuesta=$this->data['ccnp01_justificacion_proyecto']['sintesis_propuesta'];
		$objetivo_general=$this->data['ccnp01_justificacion_proyecto']['objetivo_general'];
		$objetivo_especifico=$this->data['ccnp01_justificacion_proyecto']['objetivo_especifico'];
		$metas_fisicas=$this->data['ccnp01_justificacion_proyecto']['metas_fisicas'];

		$datos=$this->ccnd02_proyectos->execute("select * from ccnd02_proyectos where cod_republica='$cod_republica' and cod_estado='$cod_estado' and cod_municipio='$cod_municipio' and cod_parroquia='$cod_parroquia' and cod_centro='$cod_centro' and cod_concejo='$cod_concejo' and ano='$ano' and cod_proyecto='$cod_proyecto'");
		$update=$this->ccnd02_proyectos->execute("update ccnd02_proyectos set sintesis_propuesta='$sintesis_propuesta',objetivo_general='$objetivo_general',objetivos_especificos='$objetivo_especifico',metas_fisicas='$metas_fisicas' where cod_republica='$cod_republica' and cod_estado='$cod_estado' and cod_municipio='$cod_municipio' and cod_parroquia='$cod_parroquia' and cod_centro='$cod_centro' and cod_concejo='$cod_concejo' and ano='$ano' and cod_proyecto='$cod_proyecto'");

		if($datos[0][0]['sintesis_propuesta']=='0'){//aqui para que guarde los datos
			if($update>1){
				$this->set('Message_existe', 'registro exitoso');
				echo" <script> ver_documento('/ccnp01_descripcion_proyecto/datos','tab_pestana_descripcion_proyecto_1'); </script>";
			}else{
				$this->set('errorMessage', 'los datos no pudieron ser registrados');
			}
		}else{//aqui para que modifique los datos
			if($update>1){
				$this->set('Message_existe', 'los datos se actualizaron correctamente');
				echo" <script> ver_documento('/ccnp01_descripcion_proyecto/datos','tab_pestana_descripcion_proyecto_1'); </script>";
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

	$update=$this->ccnd02_proyectos->execute("update ccnd02_proyectos set sintesis_propuesta='0',objetivo_general='0',objetivos_especificos='0',metas_fisicas='0' where cod_republica='$cod_republica' and cod_estado='$cod_estado' and cod_municipio='$cod_municipio' and cod_parroquia='$cod_parroquia' and cod_centro='$cod_centro' and cod_concejo='$cod_concejo' and ano='$ano' and cod_proyecto='$cod_proyecto'");
	$this->set('errorMessage', 'los datos fueron eliminados con exito');

	$this->index();
	$this->render('index');


}

}//fin class

?>