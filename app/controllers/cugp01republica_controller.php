<?php

 class Cugp01republicaController extends AppController{

	var $name = 'Cugp01republica';
	var $uses = array('cugd01_republica');
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

		  $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');

}



function index2(){
    $this->layout = "ajax";
	$this->Session->delete('cod_presi_geografico');
    $this->index();
    $this->render("index");
}//fin function


function index($boton=null, $var1=null){

	$this->layout = "ajax";
	$opcion = 'no';
	$data = '';


  if($var1!="otros"){

	    if($var1!=null){
               $this->Session->write('cod_republica_geografia',$var1 );
	    }else{
	    	   $this->Session->write('cod_republica_geografia',$this->Session->read('SScodpresi') );
	    }
		$sql_re = "cod_republica=".$this->Session->read('cod_republica_geografia')." ";
 		if($this->cugd01_republica->findCount($sql_re) != 0){
 			  $data = $this->cugd01_republica->findAll($sql_re, null, null, null);
 	    }else{$opcion = 'si';}

   }else{

         $datos_aux = $this->cugd01_republica->findAll(null, null, "cod_republica desc", null);
         $this->set('cod_republica', $datos_aux[0]["cugd01_republica"]["cod_republica"] + 1);
         $opcion = "si";

   }//fin else



    $denominacion =  $this->cugd01_republica->generateList(null, 'denominacion ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');

	$this->set('data', $data);
	$this->set('agregar', $opcion);
	$this->set('lista', $denominacion);
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$this->set('cod_presi', $this->Session->read('SScodpresi'));
	$this->set('boton', $boton);

}//fin if





function grabar($boton=null){

	$this->layout = "ajax";

   $this->cugd01_republica->save($this->data['cugp01republica']);

	$opcion = 'no';
	$data = '';



	    $this->Session->write('cod_republica_geografia',$this->data['cugp01republica']["cod_republica"] );


		$sql_re = "cod_republica=".$this->Session->read('cod_republica_geografia')." ";
 		if($this->cugd01_republica->findCount($sql_re) != 0){
 			$data = $this->cugd01_republica->findAll($sql_re, null, null, null);
 	  }else{$opcion = 'si';}

 	  $denominacion =  $this->cugd01_republica->generateList(null, 'denominacion ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
 	  $this->set('lista', $denominacion);


	$this->set('data', $data);
	$this->set('agregar', $opcion);
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$this->set('cod_presi', $this->Session->read('SScodpresi'));
	$this->set('boton', $boton);




}



function eliminar($boton=null, $var1=null){

	$this->layout = "ajax";
	$opcion = 'no';
	$data = '';

	$sql= "DELETE  FROM  cugd01_republica  WHERE cod_republica=".$var1." ";
	$this->cugd01_republica->execute($sql);

		$sql_re = "cod_republica=".$this->Session->read('SScodpresi')." ";
 		if($this->cugd01_republica->findCount($sql_re) != 0){
 			$data = $this->cugd01_republica->findAll($sql_re, null, null, null);
        }else{$opcion = 'si';}

      $denominacion =  $this->cugd01_republica->generateList(null, 'denominacion ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
 	  $this->set('lista', $denominacion);


	$this->set('data', $data);
	$this->set('agregar', $opcion);
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$this->set('cod_presi', $this->Session->read('SScodpresi'));
	$this->set('boton', null);
}



function consulta($pag_num=null) {

 		$this->layout = "ajax";
 		$this->set('entidadFederal',$this->Session->read('entidad_federal'));

		 $data = $this->cugd01_republica->findAll(null, null, 'cod_republica ASC', null, null, null);
         $this->set('data',$data);
		 $this->set('entidad_federal', $this->Session->read('entidad_federal'));



if($pag_num!=null){$this->set('pagina_actual', $pag_num);  }

}




}//fin class



?>
