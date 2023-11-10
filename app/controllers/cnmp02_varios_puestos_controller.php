<?php
/*
 * Fecha: 19/07/2007
 *
 * Por Luis Alfredo Diaz Jaramillo
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */
class Cnmp02VariosPuestosController extends AppController {
   var $name = 'Cnmp02_varios_puestos';
   var $uses = array('cnmd02_varios_puestos');
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
 	$this->data=null;
 	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
 	$this->set('enable', 'disabled');

   $cod_puesto_num = $this->cnmd02_varios_puestos->findAll(null, null, "cod_puesto DESC");
   $cod_puesto_num = isset($cod_puesto_num[0]["cnmd02_varios_puestos"]["cod_puesto"])?$cod_puesto_num[0]["cnmd02_varios_puestos"]["cod_puesto"]+1:1;
   $this->set('cod_puesto_num', $cod_puesto_num);
 }

 function principal($var1 = null, $var2=null){
 	$this->layout = "ajax";
 	//echo "var1 = ".$var1." el var 2 es: ".$var2;
 	$this->set('action', $var2);
 	$this->set('tipo', $this->cnmd02_varios_ramos->generateList(null, 'cod_ramo ASC', null, '{n}.Cnmd02_varios_ramos.cod_ramo', '{n}.Cnmd02_varios_ramos.cod_ramo'));

 	if($var2 != 'otros'){

		$this->set('datos1', $this->cnmd02_varios_ramos->findAll('cod_ramo = '.$var1));
		$this->set('datos2', $this->cnmd02_varios_grupos->findAll('cod_ramo = '.$var1.' and cod_grupo = '.$var2));
		$this->set('area', $this->cnmd02_varios_grupos->generateList('cod_ramo = '.$var1, 'cod_grupo ASC', null, '{n}.Cnmd02_varios_grupos.cod_grupo', '{n}.Cnmd02_varios_grupos.cod_grupo'));

 	}else{

 		$this->set('var1', $var1);
 		$this->set('datos1', $this->cnmd02_varios_ramos->findAll('cod_ramo = '.$var1));
 		$this->set('area', $this->cnmd02_varios_grupos->generateList('cod_ramo = '.$var1, 'cod_grupo ASC', null, '{n}.Cnmd02_varios_grupos.cod_grupo', '{n}.Cnmd02_varios_grupos.cod_grupo'));

 		$this->data['cnmp02_varios_grupos'] = array();
 		$this->set('mensaje', 'POR FAVOR INGRESE EL CODIGO');
 		$this->set('enable', 'disabled');


 	}
	$this->set('enable', 'disabled');
 }


 function guardar(){

 	$this->layout ="ajax";
 	$this->set('enable', '');

 	if(!empty($this->data['cnmp02_varios_puestos'])){

 		$cod_puesto = $this->data['cnmp02_varios_puestos']['cod_puesto'];
 		$this->set('cod_puesto', $cod_puesto);
		$cod_ramo = $cod_puesto[0];
		$cod_grupo = $cod_puesto[1];
		$cod_serie = $cod_puesto[2];
		$titulo = strtoupper($this->data['cnmp02_varios_puestos']['titulo']);
		$grado = $this->data['cnmp02_varios_puestos']['grado'];
		$labor_general = $this->data['cnmp02_varios_puestos']['labor_general'];
		$labor_especifica = $this->data['cnmp02_varios_puestos']['labor_especifica'];
		$nivel_educativo = $this->data['cnmp02_varios_puestos']['nivel_educativo'];
		$experiencia = $this->data['cnmp02_varios_puestos']['experiencia'];
		$habilidades_destrezas = $this->data['cnmp02_varios_puestos']['habilidades_destrezas'];
		$licencias = $this->data['cnmp02_varios_puestos']['licencias_certificados'];
    $sueldo = $this->Formato1($this->data['cnmp02_varios_puestos']['sueldo_basico']);

		$existe = $this->cnmd02_varios_puestos->findCount('cod_puesto = '.$cod_puesto);

		if($existe == 0){
		$this->set('existe', false);
		$sql = "INSERT INTO cnmd02_varios_puestos VALUES('$cod_puesto', '$titulo', '$grado' , '$labor_general', '$labor_especifica', '$nivel_educativo', '$experiencia', '$licencias', '$habilidades_destrezas', '$sueldo')";

		$var = $this->cnmd02_varios_puestos->execute($sql);

		$this->set('mensaje', 'EL DATO FUE GUARDADO CORRECTAMENTE');
		//$this->set('datos1', $this->cnmd02_varios_ramos->findAll('cod_ramo = '.$cod_ramo));
		//$this->set('datos2', $this->cnmd02_varios_grupos->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo));
		//$this->set('datos3', $this->cnmd02_varios_series->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo.' and cod_serie ='.$cod_serie));
		}else{$this->set('mensajeError', 'YA EXISTE UN REGISTRO CON EL CODIGO INSERTADO');}


 	}else{
 		$this->set('mensajeError', 'DATOS INCORRECTOS');
 	}


   $this->index();
   $this->render("index");

 }

 function mostrar($var = null){
 	$this->layout ="ajax";
 	if($var != null){
 		$this->set('opc', strlen($var));
 	}

 	if($var != null && strlen($var)==1)	{
 		$cod_ramo = $var[0];
 		if($this->cnmd02_varios_ramos->findCount('cod_ramo = '.$cod_ramo) != 0){
 			$this->set('datos1', $this->cnmd02_varios_ramos->findAll('cod_ramo = '.$cod_ramo));
 			$this->set('aux', 'ok');
 		}else{
 			$this->set('aux', '');
 		}
 	}else if($var != null && strlen($var)==2){
 		$cod_ramo = $var[0];
 		$cod_grupo = $var[1];
 		if($this->cnmd02_varios_grupos->findCount('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo) != 0){
 			$this->set('datos1', $this->cnmd02_varios_ramos->findAll('cod_ramo = '.$cod_ramo));
			$this->set('datos2', $this->cnmd02_varios_grupos->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo));
		}else{
			$this->set('opc', 1);
 			$this->set('aux', 'ok');
			$this->set('datos1', $this->cnmd02_varios_ramos->findAll('cod_ramo = '.$cod_ramo));
		}
 	}else if($var != null && strlen($var)>=3) {
 		$cod_ramo = $var[0];
 		$cod_grupo = $var[1];
 		$cod_serie = $var[2];
 		if($this->cnmd02_varios_series->findCount('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo.' and cod_serie ='.$cod_serie) != 0){
			$this->set('datos1', $this->cnmd02_varios_ramos->findAll('cod_ramo = '.$cod_ramo));
			$this->set('datos2', $this->cnmd02_varios_grupos->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo));
			$this->set('datos3', $this->cnmd02_varios_series->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo.' and cod_serie ='.$cod_serie));
		}else{
			$this->set('opc', 2);
			$this->set('datos1', $this->cnmd02_varios_ramos->findAll('cod_ramo = '.$cod_ramo));
			$this->set('datos2', $this->cnmd02_varios_grupos->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo));
		}
 	}





 }

 function editar($cod_puesto = null,$pagina=null){
 	$this->layout ="ajax";
 	$this->set('enable', 'disabled');
 	$this->set('pagina',$pagina);
 	$this->set('datos4', $this->cnmd02_varios_puestos->findAll('cod_puesto= '.$cod_puesto));

 	$this->set('mensaje', 'INGRESE LOS DATOS A MODIFICAR');
 }

 function guardarEditar($cod_puesto = null,$pagina=null){
 	$this->layout ="ajax";

 	if($cod_puesto != null ){
 		$cod_puesto = $this->data['cnmp02_varios_puestos']['cod_puesto'];
		$titulo = $this->data['cnmp02_varios_puestos']['titulo'];
		$grado = $this->data['cnmp02_varios_puestos']['grado'];
		$labor_general = $this->data['cnmp02_varios_puestos']['labor_general'];
		$labor_especifica = $this->data['cnmp02_varios_puestos']['labor_especifica'];
		$nivel_educativo = $this->data['cnmp02_varios_puestos']['nivel_educativo'];
		$experiencia = $this->data['cnmp02_varios_puestos']['experiencia'];
		$habilidades_destrezas = $this->data['cnmp02_varios_puestos']['habilidades_destrezas'];
		$licencias = $this->data['cnmp02_varios_puestos']['licencias_certificados'];
    $sueldo = $this->Formato1($this->data['cnmp02_varios_puestos']['sueldo_basico']);

 		$sql = "UPDATE cnmd02_varios_puestos SET denominacion_clase = '".$titulo."', grado = '".$grado."', caracteristicas_trabajo = '".$labor_general."', tareas_tipicas = '".$labor_especifica."', requisitos_minimos = '".$nivel_educativo."', educacion = '".$experiencia."', clase_cargo = '".$habilidades_destrezas."', conocimientos_habilidades ='".$licencias."', sueldo='".$sueldo."' WHERE cod_puesto = ".$cod_puesto;
 		$this->cnmd02_varios_puestos->execute($sql);
		$this->set('datos', $this->cnmd02_varios_puestos->findAll('cod_puesto = '.$cod_puesto));
 		$this->set('enable', '');
		$this->set('mensaje', 'EL DATO FUE MODIFICADO EXITOSAMENTE');
 	}

	if(isset($pagina)){
		$this->consulta($pagina);
		$this->render('consulta');
 	}else{
		$this->busqueda($cod_puesto);
		$this->render('busqueda');
 	}

 }

 function eliminar($cod_puesto = null, $pagina = null){

	$this->layout ="ajax";

	if($cod_puesto != null){

		$sql = "DELETE FROM cnmd02_varios_puestos WHERE cod_puesto = ".$cod_puesto;
		$this->cnmd02_varios_puestos->execute($sql);
		$this->set('mensaje', 'EL REGISTRO FUE ELIMINADO EXITOSAMENTE');
		$this->set('enable', 'disabled');
	}

	if(isset($pagina)){
		$this->consulta($pagina);
		$this->render('consulta');
 	}else{
		$this->index();
		$this->render('index');
 	}

 }




function consulta($pagina=null){
	$this->layout = "ajax";

	if(isset($pagina)){
		$Tfilas=$this->cnmd02_varios_puestos->findCount();
        if($Tfilas!=0){
        	$x=$this->cnmd02_varios_puestos->findAll(null,null,"cod_puesto ASC",1,$pagina,null);

            $this->set('DATA',$x);
            $this->set('pagina',$pagina);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
            $this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	 	       $this->index('autor_valido',true);
			   $this->render("index");
			   return;
        }

	}else{
		$pagina=1;
		$Tfilas=$this->cnmd02_varios_puestos->findCount();

        if($Tfilas!=0){
        	$x=$this->cnmd02_varios_puestos->findAll(null,null,"cod_puesto ASC",1,$pagina,null);
			$this->set('DATA',$x);
			$this->set('pagina',$pagina);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	$this->bt_nav($Tfilas,$pagina);
          	$this->set('numT',$Tfilas);
			$this->set('numP',$pagina);

        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	 	       $this->index('autor_valido',true);
			   $this->render("index");
			   return;
        }
	}

	$sql="select * from cnmd02_varios_puestos where cod_puesto=".$x[0]["cnmd02_varios_puestos"]["cod_puesto"];
	$datos=$this->cnmd02_varios_puestos->execute($sql);
	$this->set('datos',$datos);

}//fin



function bt_nav($Tfilas,$pagina){
    if($Tfilas==1){
                $this->set('mostrarS',false);
                $this->set('mostrarA',false);
          	}else if($Tfilas==2){
          		if($pagina==2){
                   $this->set('mostrarS',false);
                   $this->set('mostrarA',true);
          		}else{
          		   $this->set('mostrarS',true);
                   $this->set('mostrarA',false);
          		}
          	}else if($Tfilas>=3){
          		if($pagina==$Tfilas){
                     $this->set('mostrarS',false);
                     $this->set('mostrarA',true);
          		}else if($pagina==1){
          			 $this->set('mostrarS',true);
                     $this->set('mostrarA',false);
          		}else{
          			 $this->set('mostrarS',true);
                     $this->set('mostrarA',true);
          		}
          	}
}//fin navegacion




 function buscar_vista_1($var1=null){

	$this->layout="ajax";
	$this->Session->delete('pista');

}//fin function




function buscar_vista_2($var1=null, $var2=null, $var3=null){
	$this->layout="ajax";
	$sql_like = "";
	if(isset($var2)){

		            $var1 = $this->Session->read('pista');
					$var1 = strtoupper_sisap($var1);
					$pagina = $var2;
					$sql     =" (".$this->busca_separado(array("cod_puesto", "denominacion_clase"), $var1).") ";

		$Tfilas=$this->cnmd02_varios_puestos->findCount($sql);
        if($Tfilas!=0){

					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->cnmd02_varios_puestos->findAll($sql,null,"cod_puesto ASC",100,$pagina,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);


        }else{
	 	        $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	        $this->set("datosFILAS",'');

			return;
        }

	}else{
	              	$pagina=1;
	       	        $this->Session->write('pista', $var1);
					$var1 = strtoupper_sisap($var1);
					$sql     =" (".$this->busca_separado(array("cod_puesto", "denominacion_clase"), $var1).") ";

		$Tfilas=$this->cnmd02_varios_puestos->findCount($sql);
        if($Tfilas!=0){
	        					$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->cnmd02_varios_puestos->findAll($sql,null,"cod_puesto ASC",100,$pagina,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);

        }else{
	 	        $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	         $this->set("datosFILAS",'');

			 return;
        }
	}



}//fin function




function busqueda($cod_puesto=null){
	$this->layout="ajax";

	$sql="select * from cnmd02_varios_puestos where cod_puesto=".$cod_puesto;
	$datos=$this->cnmd02_varios_puestos->execute($sql);
	$this->set('datos',$datos);

}//fin




 }//fin de la clase
 ?>
