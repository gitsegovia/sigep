<?php
 class Cscp03CotizacionTraspasoController extends AppController{
	 var $name='cscp03_cotizacion_traspaso';
     var $uses = array('ccfd04_cierre_mes','v_cscd03_cotizacion','cscd01_catalogo','cugd02_direccionsuperior',
                       'cpcd02','ccfd03_instalacion','cugd02_direccion','cugd02_coordinacion','cugd02_secretaria',
                       'cugd02_division','cugd02_departamento','cugd02_oficina','cscd02_solicitud_numero','cscd02_solicitud_encabezado',
                       'cscd02_solicitud_cuerpo','cscd03_cotizacion_encabezado','cscd03_cotizacion_cuerpo', 'cscd01_unidad_medida',
                       'cscd02_solicitud_encabezado_anulado', 'v_cscd04_rif', 'cscd03_cotizacion_cuerpo_tmp', 'cscd03_cotizacion_encabezado_tmp');
     var $helpers = array('Html','Ajax','Javascript', 'Sisap');



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
}//fin function







function index(){

   $this->layout = "ajax";

}//fin class










function traspaso(){

	$this->layout = "ajax";


$var = $this->cscd03_cotizacion_encabezado_tmp->findAll(null, $fields = null, $order = null, $limit = null, $page = null, $recursive = null);





			foreach($var as $aux){


			           $sql_a ="";
			           $sql_b ="";
			           $sw = $this->cepd03_ordenpago_cuerpo->execute($sql_a);
			           $sw = $this->cepd03_ordenpago_cuerpo->execute($sql_b);



			}//fin foerach






}//fin function



}//fin class