<?php
/*
 * Fecha: 19/07/2007
 *
 * Por Luis Alfredo Diaz Jaramillo
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */
class Cnmp04tipoController extends AppController {
   var $name = 'Cnmp04_tipo';
   var $uses = array('cnmd04_tipo');
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

 function index(){
 	$this->layout ="ajax";
 	$this->set('entidad_federal', $this->Session->read('entidad_federal'));

 	$this->AddCero('tipo', $this->cnmd04_tipo->generateList(null, 'cod_nivel_i'));

 	$this->set('enable', 'disabled');



 }

 function selec_tipo($var = null){
 	$this->layout ="ajax";
 	$this->set('action', $var);
 	$this->AddCero('tipo', $this->cnmd04_tipo->generateList(null, 'cod_nivel_i'));

 	if($var != 'otros'){

		$this->set('datos', $this->cnmd04_tipo->findAll('cod_nivel_i = '.$var));

 	}else{

 		$this->data['cnmp04_tipo'] = array();
 		$this->set('mensaje', 'POR FAVOR INGRESE EL CODIGO');


 	}
	$this->set('enable', 'disabled');


 }

 function guardar(){

 	$this->layout ="ajax";

 	if(!empty($this->data['cnmp04_tipo'])){


		$cod_nivel_i = $this->data['cnmp04_tipo']['cod_nivel_i'];
		$denominacion = $this->data['cnmp04_tipo']['denominacion'];
		$sql = "INSERT INTO cnmd04_tipo VALUES('$cod_nivel_i', '$denominacion')";

		$this->cnmd04_tipo->execute($sql);

		$this->set('datos', $this->cnmd04_tipo->findAll('cod_nivel_i = '.$cod_nivel_i));

		$this->AddCero('tipo', $this->cnmd04_tipo->generateList(null, 'cod_nivel_i'));

		$this->set('mensaje', 'EL DATO FUE GUARDADO CORRECTAMENTE');


 	}else{
 		$this->set('mensajeError', 'DATOS INCORRECTOS');
 	}

 }

 function editar($cod_nivel_i = null){
 	$this->layout ="ajax";
 	$this->AddCero('tipo', $this->cnmd04_tipo->generateList(null, 'cod_nivel_i'));
 	$this->set('datos', $this->cnmd04_tipo->findAll('cod_nivel_i = '.$cod_nivel_i));
 	$this->set('mensaje', 'INGRESE LOS DATOS A MODIFICAR');
 }

 function guardarEditar($cod_nivel_i = null){
 	$this->layout ="ajax";

 	if($cod_nivel_i != null){
 		$denominacion = $this->data['cnmp04_tipo']['denominacion'];
 		$sql = "UPDATE cnmd04_tipo SET denominacion = '".$denominacion."' WHERE cod_nivel_i = ".$cod_nivel_i;
 		$this->cnmd04_tipo->execute($sql);

 		$this->set('datos', $this->cnmd04_tipo->findAll('cod_nivel_i = '.$cod_nivel_i));
		$this->set('tipo', $this->cnmd04_tipo->generateList(null, 'cod_nivel_i'));

		$this->set('mensaje', 'EL DATO FUE MODIFICADO EXITOSAMENTE');
 	}

 }

 function eliminar($cod_nivel_i = null){

	$this->layout ="ajax";

	if($cod_nivel_i != null){

		$sql = "DELETE FROM cnmd04_tipo WHERE cod_nivel_i = ".$cod_nivel_i;
		$this->cnmd04_tipo->execute($sql);
		$this->AddCero('tipo', $this->cnmd04_tipo->generateList(null, 'cod_nivel_i'));

		$this->set('mensaje', 'EL REGISTRO FUE ELIMINADO EXITOSAMENTE');
		$this->set('enable', 'disabled');

	}

 }


 function consulta($pag_num=null){
 	$this->layout ="ajax";

    $data = $this->cnmd04_tipo->findAll(null, null, 'cod_nivel_i ASC', null, null, null);
    $this->set('datos',$data);

    if($pag_num!=null){$this->set('pagina_actual', $pag_num); }


 }




 }//fin de la clase
 ?>
