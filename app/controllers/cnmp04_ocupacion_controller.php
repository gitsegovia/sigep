<?php
/*
 * Fecha: 19/07/2007
 *
 * Por Luis Alfredo Diaz Jaramillo
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */
class Cnmp04ocupacionController extends AppController {
   var $name = 'cnmp04_ocupacion';
   var $uses = array('cnmd04_tipo', 'cnmd04_ocupacion');
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


  function AddCero($nomVar,$vector=object,$extra=null){
   	  if($vector!=null){
   	  	  if($extra==null){
   	  	foreach($vector as $x){
        	if($x<10){
        	   $Var[$x]="0".$x;
        	}else{
	           $Var[$x]=$x;
        	}
	    }//fin each
   	  }else{
          foreach($vector as $x){
        	if($x<10){
        	   $Var[$x]=$extra.".0".$x;
        	}else{
	           $Var[$x]=$extra.".".$x;
        	}
	    }//fin each
   	  }
   	  $this->set($nomVar,$Var);
   	  }else{
   	  	  $this->set($nomVar,'');
   	  }



}//fin AddCero



 function index($var1=null, $var2=null){
 	$this->layout ="ajax";
 	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
 	$this->set('action', $var2);
 	$this->set('enable', 'disabled');

 	$this->AddCero('tipo', $this->cnmd04_tipo->generateList(null, 'cod_nivel_i ASC', null, '{n}.cnmd04_tipo.cod_nivel_i', '{n}.cnmd04_tipo.cod_nivel_i'));

 	if($var1 != null){
 		$this->set('seleccion', $var1);
 		$this->set('datos', $this->cnmd04_tipo->findAll('cod_nivel_i = '.$var1));
 	    $this->AddCero('area', $this->cnmd04_ocupacion->generateList('cod_nivel_i = '.$var1, 'cod_nivel_ii ASC', null, '{n}.cnmd04_ocupacion.cod_nivel_ii', '{n}.cnmd04_ocupacion.cod_nivel_ii'));
 	}

 if ($var1 != null && $var2 != null){
 		$this->set('selec2', $var1);

 		if($var2 == 'otros'){
 			$this->set('var1', $var1);
 		$this->set('datos1', $this->cnmd04_tipo->findAll('cod_nivel_i = '.$var1));
 		$this->AddCero('area', $this->cnmd04_ocupacion->generateList('cod_nivel_i = '.$var1, 'cod_nivel_ii ASC', null, '{n}.cnmd04_ocupacion.cod_nivel_ii', '{n}.cnmd04_ocupacion.cod_nivel_ii'));

 		$this->data['cnmp04_ocupacion'] = array();
 		$this->set('mensaje', 'POR FAVOR INGRESE EL CODIGO');
 		}else{

 		$this->set('datos', $this->cnmd04_tipo->findAll('cod_nivel_i = '.$var1));

 		$this->AddCero('area', $this->cnmd04_ocupacion->generateList('cod_nivel_i = '.$var1, 'cod_nivel_ii ASC', null, '{n}.cnmd04_ocupacion.cod_nivel_ii', '{n}.cnmd04_ocupacion.cod_nivel_ii'));
 		$this->set('datos2', $this->cnmd04_ocupacion->findAll('cod_nivel_i = '.$var1.' and cod_nivel_ii = '.$var2));
 		}



 	}






 }

 function selec_tipo($var1 = null){
 	$this->layout ="ajax";
 	$this->set('action', $var1);
 	$this->AddCero('tipo', $this->cnmd04_tipo->generateList(null, 'cod_nivel_i ASC', null, '{n}.cnmd04_tipo.cod_nivel_i', '{n}.cnmd04_tipo.cod_nivel_i'));


 	if($var1 != null){

		$this->set('datos', $this->cnmd04_tipo->findAll('cod_nivel_i = '.$var1));
		$this->AddCero('area', $this->cnmd04_ocupacion->generateList('cod_nivel_i = '.$var1, 'cod_nivel_ii ASC', null, '{n}.cnmd04_ocupacion.cod_nivel_ii', '{n}.cnmd04_ocupacion.cod_nivel_ii'));

 	}else{

 		$this->data['cnmp04_ocupacion'] = array();
 	}
	$this->set('enable', 'disabled');


 }



 function selec_area($var1 = null){
 	$this->layout ="ajax";

 	$this->set('action', $var1);

 	if($var1 != 'otros'){
		$this->AddCero('area', $this->cnmd04_ocupacion->generateList('cod_nivel_i = '.$var1, 'cod_nivel_ii ASC', null, '{n}.cnmd04_ocupacion.cod_nivel_ii', '{n}.cnmd04_ocupacion.cod_nivel_ii'));
 	}else if($var1 != null){
		$this->AddCero('area', $this->cnmd04_ocupacion->generateList('cod_nivel_i = '.$var1, 'cod_nivel_ii ASC', null, '{n}.cnmd04_ocupacion.cod_nivel_ii', '{n}.cnmd04_ocupacion.cod_nivel_ii'));
 	}

 }

 function principal($var1 = null, $var2=null){
 	$this->layout = "ajax";
 	//echo "var1 = ".$var1." el var 2 es: ".$var2;
 	$this->set('action', $var2);
 	$this->AddCero('tipo', $this->cnmd04_tipo->generateList(null, 'cod_nivel_i ASC', null, '{n}.cnmd04_tipo.cod_nivel_i', '{n}.cnmd04_tipo.cod_nivel_i'));

 	if($var2 != 'otros'){

		$this->set('datos1', $this->cnmd04_tipo->findAll('cod_nivel_i = '.$var1));
		$this->set('datos2', $this->cnmd04_ocupacion->findAll('cod_nivel_i = '.$var1.' and cod_nivel_ii = '.$var2));
		$this->AddCero('area', $this->cnmd04_ocupacion->generateList('cod_nivel_i = '.$var1, 'cod_nivel_ii ASC', null, '{n}.cnmd04_ocupacion.cod_nivel_ii', '{n}.cnmd04_ocupacion.cod_nivel_ii'));

 	}else{

 		$this->set('var1', $var1);
 		$this->set('datos1', $this->cnmd04_tipo->findAll('cod_nivel_i = '.$var1));
 		$this->AddCero('area', $this->cnmd04_ocupacion->generateList('cod_nivel_i = '.$var1, 'cod_nivel_ii ASC', null, '{n}.cnmd04_ocupacion.cod_nivel_ii', '{n}.cnmd04_ocupacion.cod_nivel_ii'));

 		$this->data['cnmp04_ocupacion'] = array();
 		$this->set('mensaje', 'POR FAVOR INGRESE EL CODIGO');
 		$this->set('enable', 'disabled');


 	}
	$this->set('enable', 'disabled');
 }


 function guardar(){

 	$this->layout ="ajax";

 	if(!empty($this->data['cnmp04_ocupacion'])){


		$cod_nivel_i = $this->data['cnmp04_ocupacion']['cod_nivel_i'];
		$cod_nivel_ii = $this->data['cnmp04_ocupacion']['cod_nivel_ii'];
		$denominacion = $this->data['cnmp04_ocupacion']['denominacion'];
		$sql = "INSERT INTO cnmd04_ocupacion VALUES('$cod_nivel_i', '$cod_nivel_ii' , '$denominacion')";

		$this->cnmd04_ocupacion->execute($sql);

		$this->set('datos1', $this->cnmd04_tipo->findAll('cod_nivel_i = '.$cod_nivel_i));
		$this->AddCero('tipo', $this->cnmd04_tipo->generateList(null, 'cod_nivel_i'));
		$this->AddCero('area', $this->cnmd04_ocupacion->generateList('cod_nivel_i = '.$cod_nivel_i, 'cod_nivel_ii ASC', null, '{n}.cnmd04_ocupacion.cod_nivel_ii', '{n}.cnmd04_ocupacion.cod_nivel_ii'));
		$this->set('datos2', $this->cnmd04_ocupacion->findAll('cod_nivel_i = '.$cod_nivel_i.' and cod_nivel_ii = '.$cod_nivel_ii));

		$this->set('mensaje', 'EL DATO FUE GUARDADO CORRECTAMENTE');


 	}else{
 		$this->set('mensajeError', 'DATOS INCORRECTOS');
 	}

 }

 function editar($cod_nivel_i = null, $cod_nivel_ii = null){
 	$this->layout ="ajax";
 	$this->set('enable', 'disabled');
 	$this->AddCero('tipo', $this->cnmd04_tipo->generateList(null, 'cod_nivel_i ASC', null, '{n}.cnmd04_tipo.cod_nivel_i', '{n}.cnmd04_tipo.cod_nivel_i'));
 	$this->set('datos', $this->cnmd04_tipo->findAll('cod_nivel_i = '.$cod_nivel_i));
 	$this->AddCero('area', $this->cnmd04_ocupacion->generateList('cod_nivel_i = '.$cod_nivel_i, 'cod_nivel_ii ASC', null, '{n}.cnmd04_ocupacion.cod_nivel_ii', '{n}.cnmd04_ocupacion.cod_nivel_ii'));
 	$this->set('datos2', $this->cnmd04_ocupacion->findAll('cod_nivel_i = '.$cod_nivel_i." and cod_nivel_ii = ".$cod_nivel_ii));
 	$this->set('mensaje', 'INGRESE LOS DATOS A MODIFICAR');
 }

 function guardarEditar($cod_nivel_i = null, $cod_nivel_ii = null){
 	$this->layout ="ajax";

 	if($cod_nivel_i != null && $cod_nivel_ii != null){
 		$denominacion = $this->data['cnmp04_ocupacion']['denominacion'];
 		$sql = "UPDATE cnmd04_ocupacion SET denominacion = '".$denominacion."' WHERE cod_nivel_i = ".$cod_nivel_i." and cod_nivel_ii = ".$cod_nivel_ii;
 		$this->cnmd04_ocupacion->execute($sql);

 		$this->AddCero('tipo', $this->cnmd04_tipo->generateList(null, 'cod_nivel_i ASC', null, '{n}.cnmd04_tipo.cod_nivel_i', '{n}.cnmd04_tipo.cod_nivel_i'));
 		$this->set('datos', $this->cnmd04_tipo->findAll('cod_nivel_i = '.$cod_nivel_i));
 		$this->AddCero('area', $this->cnmd04_ocupacion->generateList('cod_nivel_i = '.$cod_nivel_i, 'cod_nivel_ii ASC', null, '{n}.cnmd04_ocupacion.cod_nivel_ii', '{n}.cnmd04_ocupacion.cod_nivel_ii'));
 		$this->set('datos2', $this->cnmd04_ocupacion->findAll('cod_nivel_i = '.$cod_nivel_i." and cod_nivel_ii = ".$cod_nivel_ii));

		$this->set('mensaje', 'EL DATO FUE MODIFICADO EXITOSAMENTE');
 	}

 }

 function eliminar($cod_nivel_i = null, $cod_nivel_ii = null){

	$this->layout ="ajax";

	if($cod_nivel_i != null){

		$sql = "DELETE FROM cnmd04_ocupacion WHERE cod_nivel_i = ".$cod_nivel_i." and cod_nivel_ii = ".$cod_nivel_ii;
		$this->cnmd04_ocupacion->execute($sql);
		$this->AddCero('tipo', $this->cnmd04_tipo->generateList(null, 'cod_nivel_i ASC', null, '{n}.cnmd04_tipo.cod_nivel_i', '{n}.cnmd04_tipo.cod_nivel_i'));
		$this->set('datos', $this->cnmd04_tipo->findAll('cod_nivel_i = '.$cod_nivel_i));
		$this->AddCero('area', $this->cnmd04_ocupacion->generateList('cod_nivel_i = '.$cod_nivel_i, 'cod_nivel_ii ASC', null, '{n}.cnmd04_ocupacion.cod_nivel_ii', '{n}.cnmd04_ocupacion.cod_nivel_ii'));

		$this->set('mensaje', 'EL REGISTRO FUE ELIMINADO EXITOSAMENTE');
		$this->set('enable', 'disabled');

	}

 }


 function consulta($pag_num=null){
 	$this->layout ="ajax";

    $datos1 = $this->cnmd04_tipo->findAll(null, null, 'cod_nivel_i ASC', null, null, null);
    $this->set('datos1',$datos1);

    $datos2 = $this->cnmd04_ocupacion->findAll(null, null, 'cod_nivel_i, cod_nivel_ii ASC', null, null, null);
    $this->set('datos2',$datos2);

    if($pag_num!=null){$this->set('pagina_actual', $pag_num); }


 }




 }//fin de la clase
 ?>