<?php
 class Casp01AtencionSocialControlController extends AppController {
   var $name = 'casp01_atencion_social_control';
	 var $uses = array('casd01_datos_personales','casd01_datos_familiares','cnmd06_profesiones','cnmd06_oficio','cnmd06_parentesco',
   					'cugd01_estados','cugd01_municipios','cugd01_parroquias','cugd01_centropoblados','cugd02_institucion','cugd02_dependencia',
   					'casd01_ayudas_cuerpo','casd01_tipo_ayuda','casd01_ayuda_detalles','casd01_evaluacion_ayuda','casd01_solicitud_ayuda','cugd10_imagenes',
   					'v_historia_solicitud_ayudas');
	var $helpers = array('Html','Ajax','Javascript', 'Sisap');



 function checkSession(){
				if (!$this->Session->check('Usuario')){
					$this->redirect('/salir/');
					exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
 }//fin checksession

 function index(){

	$this->layout="ajax";

      $this->Session->delete('cedula_pestana_atencion');


 }//index
}
?>