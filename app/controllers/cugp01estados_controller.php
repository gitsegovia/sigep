<?php

 class Cugp01estadosController extends AppController{

	var $name = 'Cugp01estados';
	var $uses = array('cugd01_republica', 'cugd01_estados', 'v_cugd01_verificacion');
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


}


function index2(){
    $this->layout = "ajax";
	$this->Session->delete('cod_presi_geografico');
    $this->index();
    $this->render("index");
}//fin function





function index($selet=null, $boton=null){

	$this->layout = "ajax";
	$opcion = 'no';
	$data = '';
	$denominacion='';


     if($selet=="republica"){$_SESSION["cod_presi_geografico"] = $boton; $selet =null; $boton = null;}
	 if(empty($_SESSION["cod_presi_geografico"])){$_SESSION["cod_presi_geografico"] = $this->Session->read('SScodpresi'); }


	       if($selet!=null && $selet!='otros'){$this->set('selecion', $selet);
	 }else if($selet=='otros'){
	 	   $this->set('selecion',$selet); $selet='0';
	 }else{ $selet="0"; $this->set('selecion',$selet);}



	 $republica = $this->cugd01_republica->findAll("cod_republica=".$this->Session->read('cod_presi_geografico')."", null, null, null);
	 $this->set('var', $republica);

	    $datos_aux = $this->cugd01_estados->findAll("cod_republica=".$this->Session->read('cod_presi_geografico')."", null, "cod_estado desc", null);
        $this->set('cod_estado', isset($datos_aux[0]["cugd01_estados"]["cod_estado"])?$datos_aux[0]["cugd01_estados"]["cod_estado"] + 1:1);


		$sql_re = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$selet." ";
 		if($this->cugd01_estados->findCount($sql_re) != 0){
 			$data = $this->cugd01_estados->findAll($sql_re, null, null, null);
 			$denominacion =  $this->cugd01_estados->generateList("cod_republica=".$this->Session->read('cod_presi_geografico'), 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
		}else{

			if($this->cugd01_estados->findCount("cod_republica=".$this->Session->read('cod_presi_geografico')."") != 0){
				$denominacion =  $this->cugd01_estados->generateList("cod_republica=".$this->Session->read('cod_presi_geografico')."", 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
			 }

				$opcion = 'si';


			}


      $lista_republicas =  $this->cugd01_republica->generateList(null, 'denominacion ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
 	  $this->set('lista', $lista_republicas);


	$this->set('denominacion', $denominacion);
	$this->set('data', $data);
	$this->set('agregar', $opcion);
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$this->set('cod_presi', $this->Session->read('cod_presi_geografico'));
	$this->set('cod_entidad', $this->Session->read('SScodentidad'));
	$this->set('boton', $boton);
}





function grabar($selet=null, $boton=null){

	$this->layout = "ajax";





	$cod_republica   = $this->data['cugp01estados']["cod_republica"];
	$cod_estado      = $this->data['cugp01estados']["cod_estado"];
	$denominacion    = $this->data['cugp01estados']["denominacion"];
	$caracteristicas = $this->data['cugp01estados']["caracteristicas"];
	$economia        = $this->data['cugp01estados']["economia"];
	$poblacion       = $this->data['cugp01estados']["poblacion"];
	$orientacion     = $this->data['cugp01estados']["orientacion"];
	$dimension       = $this->data['cugp01estados']["dimension"];
	$limites         = $this->data['cugp01estados']["limites"];



  if($this->cugd01_estados->findCount(" cod_republica='".$cod_republica."' and  cod_estado='".$cod_estado."' ")==0){


    $sql = "INSERT INTO cugd01_estados (cod_republica, cod_estado, denominacion, caracteristicas, economia, poblacion, orientacion, limites, dimension)   VALUES  ( '".$this->data['cugp01estados']['cod_republica']."',  '".$this->data['cugp01estados']['cod_estado']."',  '".$this->data['cugp01estados']['denominacion']."',  '".$this->data['cugp01estados']['caracteristicas']."',  '".$this->data['cugp01estados']['economia']."',  '".$this->data['cugp01estados']['poblacion']."',  '".$this->data['cugp01estados']['orientacion']."',  '".$this->data['cugp01estados']['limites']."',  '".$this->data['cugp01estados']['dimension']."')";


  }else{

    $sql  =  "UPDATE cugd01_estados SET  denominacion = '".$denominacion."', caracteristicas = '".$caracteristicas."', economia = '".$economia."', poblacion = '".$poblacion."',  orientacion = '".$orientacion."',  dimension = '".$dimension."',  limites = '".$limites."' WHERE ";
    $sql .= " cod_republica='".$cod_republica."' and  cod_estado='".$cod_estado."' ";

   }//fin else


   	$this->cugd01_estados->execute($sql);
	$opcion = 'no';
	$data = '';
	$denominacion='';

	 $republica = $this->cugd01_republica->findAll("cod_republica=".$this->Session->read('cod_presi_geografico')."", null, null, null);
	 $this->set('var', $republica);

	$selet = $this->data['cugp01estados']['cod_estado'];

	       if($selet!=null && $selet!='otros'){$this->set('selecion', $selet);

	 }else if($selet=='otros'){$this->set('selecion',$selet); $selet='0';

	 }else{ $selet=$this->Session->read('SScodentidad'); $this->set('selecion',$selet);}

	    $datos_aux = $this->cugd01_estados->findAll("cod_republica=".$this->Session->read('cod_presi_geografico')."", null, "cod_estado desc", null);
        $this->set('cod_estado', isset($datos_aux[0]["cugd01_estados"]["cod_estado"])?$datos_aux[0]["cugd01_estados"]["cod_estado"] + 1:1);

		$sql_re = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$selet." ";
 		if($this->cugd01_estados->findCount($sql_re) != 0){
 			$data = $this->cugd01_estados->findAll($sql_re, null, null, null);
 			$denominacion =  $this->cugd01_estados->generateList("cod_republica=".$this->Session->read('cod_presi_geografico'), 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
		}else{

			if($this->cugd01_estados->findCount("cod_republica=".$this->Session->read('cod_presi_geografico')."") != 0){
				$denominacion =  $this->cugd01_estados->generateList("cod_republica=".$this->Session->read('cod_presi_geografico')."", 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
				//$data = $this->cugd01_estados->findAll("cod_republica=".$this->Session->read('cod_presi_geografico')."", null, null, null);
			    $opcion = 'si';
			}else{$opcion = 'si';}


			}

    $lista_republicas =  $this->cugd01_republica->generateList(null, 'denominacion ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
 	$this->set('lista', $lista_republicas);

	$this->set('data', $data);
	$this->set('denominacion', $denominacion);
	$this->set('agregar', $opcion);
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$this->set('cod_presi', $this->Session->read('cod_presi_geografico'));
	$this->set('cod_entidad', $this->Session->read('SScodentidad'));
	$this->set('boton', $boton);




}



function eliminar($selet=null, $boton=null){

	$this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$opcion = 'no';
	$data = '';

	 $republica = $this->cugd01_republica->findAll("cod_republica=".$this->Session->read('cod_presi_geografico')."", null, null, null);
	 $this->set('var', $republica);


	$sql_re = "cod_presi=".$cod_presi."  and cod_entidad=".$cod_entidad."  and cod_tipo_inst=".$cod_tipo_inst."  and cod_estado=".$selet."";
    if($this->v_cugd01_verificacion->findCount($sql_re)==0){
		$sql= "DELETE  FROM  cugd01_estados  WHERE cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$selet." ";
		$this->cugd01_estados->execute($sql);
		$denominacion='';
		$this->set('Message_existe', "REGISTRO ELIMINADO");
    }else{
    	$this->set('errorMessage', "NO PUEDE ELIMINAR ESTE REGISTRO.....ESTA EN USO");
    }


        $_SESSION["cod_presi_geografico"] = $this->Session->read('SScodpresi');
		$selet=$this->Session->read('SScodentidad');
		$this->set('selecion', "no");

		$datos_aux = $this->cugd01_estados->findAll("cod_republica=".$this->Session->read('cod_presi_geografico')."", null, "cod_estado desc", null);
        $this->set('cod_estado', isset($datos_aux[0]["cugd01_estados"]["cod_estado"])?$datos_aux[0]["cugd01_estados"]["cod_estado"] + 1:1);

		$sql_re = "cod_republica=".$this->Session->read('cod_presi_geografico')." and cod_estado=".$selet." ";
 		if($this->cugd01_estados->findCount("cod_republica=".$this->Session->read('cod_presi_geografico')) != 0){
 			//$data = $this->cugd01_estados->findAll("cod_republica=".$this->Session->read('cod_presi_geografico'), null, null, null);
 			$denominacion =  $this->cugd01_estados->generateList("cod_republica=".$this->Session->read('cod_presi_geografico'), 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
		}else{
			if($this->cugd01_estados->findCount("cod_republica=".$this->Session->read('cod_presi_geografico')."") != 0){
				  $denominacion =  $this->cugd01_estados->generateList("cod_republica=".$this->Session->read('cod_presi_geografico')."", 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
				//$data         =  $this->cugd01_estados->findAll("cod_republica=".$this->Session->read('cod_presi_geografico')."", null, null, null);
                 $opcion = 'si';
			}else{$opcion = 'si';}
         }//fin if

$opcion = 'si';

	$lista_republicas =  $this->cugd01_republica->generateList(null, 'denominacion ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
 	$this->set('lista', $lista_republicas);

	$this->set('data', $data);
	$this->set('agregar', $opcion);
	$this->set('denominacion', $denominacion);
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$this->set('cod_presi', $this->Session->read('cod_presi_geografico'));
	$this->set('cod_entidad', $this->Session->read('SScodentidad'));
	$this->set('boton', null);
}







function consulta($pag_num=null) {

 		$this->layout = "ajax";

 		 $republica = $this->cugd01_republica->findAll(null, null, null, null);
	 	 $this->set('var_republica', $republica);

		 $data = $this->cugd01_estados->findAll(null, null, 'cod_republica, cod_estado ASC', null, null, null);
         $this->set('data',$data);
		 $this->set('entidad_federal', $this->Session->read('entidad_federal'));
		 $this->set('cod_presi', $this->Session->read('cod_presi_geografico'));
		 $this->set('cod_entidad', $this->Session->read('SScodentidad'));

	  $lista_republicas =  $this->cugd01_republica->generateList(null, 'denominacion ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
 	  $this->set('lista', $lista_republicas);


if($pag_num!=null){$this->set('pagina_actual', $pag_num);  }

}










function carga_codigo_republica($var1=null, $var2=null, $var3=null){

$this->layout = "ajax";
$this->Session->write('cod_presi_geografico', $var1);

	$denominacion =  $this->cugd01_estados->generateList("cod_republica=".$var1."", 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
	$this->set('denominacion', $denominacion);
	$this->set('var_controlador', $var2);


}//fin function


function funcion(){$this->layout = "ajax";}//fin function







}//fin class



?>
