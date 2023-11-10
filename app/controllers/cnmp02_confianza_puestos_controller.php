<?php
/*
 * Fecha: 19/07/2007
 *
 * Por Luis Alfredo Diaz Jaramillo
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */
class Cnmp02ConfianzaPuestosController extends AppController {
   var $name = 'Cnmp02_confianza_puestos';
   var $uses = array('cnmd02_confianza_puestos');
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



 function index($var1=null, $var2=null, $var3= null){
 	$this->layout ="ajax";
 	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
 	$this->set('enable', 'disabled');
 	$this->set('mensaje', 'Inserte los datos de la clase');


 	//echo "el var1 es: ".$var1." el var2 es: ".$var2." el var3 es: ".$var3;

 }

 function principal($var1 = null, $var2=null){
 	$this->layout = "ajax";
 	//echo "var1 = ".$var1." el var 2 es: ".$var2;
 	$this->set('action', $var2);
 	$this->set('tipo', $this->cnmd02_confianza_ramos->generateList(null, 'cod_ramo ASC', null, '{n}.Cnmd02_confianza_ramos.cod_ramo', '{n}.Cnmd02_confianza_ramos.cod_ramo'));

 	if($var2 != 'otros'){

		$this->set('datos1', $this->cnmd02_confianza_ramos->findAll('cod_ramo = '.$var1));
		$this->set('datos2', $this->cnmd02_confianza_grupos->findAll('cod_ramo = '.$var1.' and cod_grupo = '.$var2));
		$this->set('area', $this->cnmd02_confianza_grupos->generateList('cod_ramo = '.$var1, 'cod_grupo ASC', null, '{n}.Cnmd02_confianza_grupos.cod_grupo', '{n}.Cnmd02_confianza_grupos.cod_grupo'));

 	}else{

 		$this->set('var1', $var1);
 		$this->set('datos1', $this->cnmd02_confianza_ramos->findAll('cod_ramo = '.$var1));
 		$this->set('area', $this->cnmd02_confianza_grupos->generateList('cod_ramo = '.$var1, 'cod_grupo ASC', null, '{n}.Cnmd02_confianza_grupos.cod_grupo', '{n}.Cnmd02_confianza_grupos.cod_grupo'));

 		$this->data['cnmp02_confianza_grupos'] = array();
 		$this->set('mensaje', 'POR FAVOR INGRESE EL CODIGO');
 		$this->set('enable', 'disabled');


 	}
	$this->set('enable', 'disabled');
 }


 function guardar(){

 	$this->layout ="ajax";
 	$this->set('enable', '');

 	if(!empty($this->data['cnmp02_confianza_puestos'])){

 		$cod_puesto = $this->data['cnmp02_confianza_puestos']['cod_puesto'];
 		$this->set('cod_puesto', $cod_puesto);
		$cod_ramo = $cod_puesto[0];
		$cod_grupo = $cod_puesto[1];
		$cod_serie = $cod_puesto[2];
		$titulo = $this->data['cnmp02_confianza_puestos']['titulo'];
		$grado = $this->data['cnmp02_confianza_puestos']['grado'];
		$labor_general = $this->data['cnmp02_confianza_puestos']['labor_general'];
		$labor_especifica = $this->data['cnmp02_confianza_puestos']['labor_especifica'];
		$nivel_educativo = $this->data['cnmp02_confianza_puestos']['nivel_educativo'];
		$experiencia = $this->data['cnmp02_confianza_puestos']['experiencia'];
		$habilidades_destrezas = $this->data['cnmp02_confianza_puestos']['habilidades_destrezas'];
		$licencias = $this->data['cnmp02_confianza_puestos']['licencias_certificados'];

		$existe = $this->cnmd02_confianza_puestos->findCount('cod_puesto = '.$cod_puesto);

		if($existe == 0){
		$this->set('existe', false);
		$sql = "INSERT INTO cnmd02_confianza_puestos VALUES('$cod_puesto', '$titulo', '$grado' , '$labor_general', '$labor_especifica', '$nivel_educativo', '$experiencia', '$licencias', '$habilidades_destrezas')";

		$var = $this->cnmd02_confianza_puestos->execute($sql);

		$this->set('mensaje', 'EL DATO FUE GUARDADO CORRECTAMENTE');
		//$this->set('datos1', $this->cnmd02_confianza_ramos->findAll('cod_ramo = '.$cod_ramo));
		//$this->set('datos2', $this->cnmd02_confianza_grupos->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo));
		//$this->set('datos3', $this->cnmd02_confianza_series->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo.' and cod_serie ='.$cod_serie));
		}else $this->set('mensajeError', 'YA EXISTE UN REGISTRO CON EL CODIGO INSERTADO');


 	}else{
 		$this->set('mensajeError', 'DATOS INCORRECTOS');
 	}

 }

 function mostrar($var = null){
 	$this->layout ="ajax";
 	if($var != null){
 		$this->set('opc', strlen($var));
 	}

 	if($var != null && strlen($var)==1)	{
 		$cod_ramo = $var[0];
 		if($this->cnmd02_confianza_ramos->findCount('cod_ramo = '.$cod_ramo) != 0){
 			$this->set('datos1', $this->cnmd02_confianza_ramos->findAll('cod_ramo = '.$cod_ramo));
 			$this->set('aux', 'ok');
 		}else{
 			$this->set('aux', '');
 		}
 	}else if($var != null && strlen($var)==2){
 		$cod_ramo = $var[0];
 		$cod_grupo = $var[1];
 		if($this->cnmd02_confianza_grupos->findCount('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo) != 0){
 			$this->set('datos1', $this->cnmd02_confianza_ramos->findAll('cod_ramo = '.$cod_ramo));
			$this->set('datos2', $this->cnmd02_confianza_grupos->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo));
		}else{
			$this->set('opc', 1);
 			$this->set('aux', 'ok');
			$this->set('datos1', $this->cnmd02_confianza_ramos->findAll('cod_ramo = '.$cod_ramo));
		}
 	}else if($var != null && strlen($var)>=3) {
 		$cod_ramo = $var[0];
 		$cod_grupo = $var[1];
 		$cod_serie = $var[2];
 		if($this->cnmd02_confianza_series->findCount('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo.' and cod_serie ='.$cod_serie) != 0){
			$this->set('datos1', $this->cnmd02_confianza_ramos->findAll('cod_ramo = '.$cod_ramo));
			$this->set('datos2', $this->cnmd02_confianza_grupos->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo));
			$this->set('datos3', $this->cnmd02_confianza_series->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo.' and cod_serie ='.$cod_serie));
		}else{
			$this->set('opc', 2);
			$this->set('datos1', $this->cnmd02_confianza_ramos->findAll('cod_ramo = '.$cod_ramo));
			$this->set('datos2', $this->cnmd02_confianza_grupos->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo));
		}
 	}





 }

 function editar($cod_puesto = null){
 	$this->layout ="ajax";
 	$this->set('enable', 'disabled');
 	$cod_ramo = $cod_puesto[0];
 	$cod_grupo = $cod_puesto[1];
 	$cod_serie = $cod_puesto[2];
 	$this->set('datos4', $this->cnmd02_confianza_puestos->findAll('cod_puesto= '.$cod_puesto));

 	$this->set('mensaje', 'INGRESE LOS DATOS A MODIFICAR');
 }

 function guardarEditar($cod_puesto = null){
 	$this->layout ="ajax";

 	if($cod_puesto != null ){
 		$cod_puesto = $this->data['cnmp02_confianza_puestos']['cod_puesto'];
		$cod_ramo = $cod_puesto[0];
		$cod_grupo = $cod_puesto[1];
		$cod_serie = $cod_puesto[2];
		$titulo = $this->data['cnmp02_confianza_puestos']['titulo'];
		$grado = $this->data['cnmp02_confianza_puestos']['grado'];
		$labor_general = $this->data['cnmp02_confianza_puestos']['labor_general'];
		$labor_especifica = $this->data['cnmp02_confianza_puestos']['labor_especifica'];
		$nivel_educativo = $this->data['cnmp02_confianza_puestos']['nivel_educativo'];
		$experiencia = $this->data['cnmp02_confianza_puestos']['experiencia'];
		$habilidades_destrezas = $this->data['cnmp02_confianza_puestos']['habilidades_destrezas'];
		//$condiciones_fisicas = $this->data['cnmp02_confianza_puestos']['condiciones_fisicas'];
		//$condiciones_ambientales = $this->data['cnmp02_confianza_puestos']['condiciones_ambientales'];
		$licencias = $this->data['cnmp02_confianza_puestos']['licencias_certificados'];

 		$sql = "UPDATE cnmd02_confianza_puestos SET denominacion_clase = '".$titulo."', grado = '".$grado."', caracteristicas_trabajo = '".$labor_general."', tareas_tipicas = '".$labor_especifica."', requisitos_minimos = '".$nivel_educativo."', educacion = '".$experiencia."', clase_cargo = '".$habilidades_destrezas."', conocimientos_habilidades ='".$licencias."' WHERE cod_puesto = ".$cod_puesto;
 		$this->cnmd02_confianza_puestos->execute($sql);
		$this->set('datos', $this->cnmd02_confianza_puestos->findAll('cod_puesto = '.$cod_puesto));
 		$this->set('enable', '');
		$this->set('mensaje', 'EL DATO FUE MODIFICADO EXITOSAMENTE');
 	}

 }

 function eliminar($cod_ramo = null, $valor = null){

	$this->layout ="ajax";

	if($cod_ramo != null){

		$sql = "DELETE FROM cnmd02_confianza_puestos WHERE cod_puesto = ".$cod_ramo;
		$this->cnmd02_confianza_puestos->execute($sql);
		$this->set('mensaje', 'EL REGISTRO FUE ELIMINADO EXITOSAMENTE');
		$this->consulta($valor);
		$this->render('consulta');
		$this->set('mensaje', 'EL REGISTRO FUE ELIMINADO EXITOSAMENTE');
		$this->set('enable', 'disabled');


	}

 }


 function consulta($pag_num=null){
 	$this->layout ="ajax";
 	$this->set('enable', '');

    $data = $this->cnmd02_confianza_puestos->findAll(null, null, 'cod_puesto ASC', null, null, null);


    $this->set('datos',$data);

    if($pag_num!=null){
    	$this->set('pagina_actual', $pag_num);
    }


 }




 }//fin de la clase
 ?>
