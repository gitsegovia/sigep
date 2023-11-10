<?php


 class ccnp01IdentificacionProyectoController extends AppController {
    var $name    = 'ccnp01_identificacion_proyecto';
	var $uses    = array('ccnd01_tipo_directivo', 'ccnd02_proyectos_profesionales', 'ccnd02_proyectos','cnmd06_profesiones',
						'ccnd02_requerimientos','ccnd02_tipo_requerimiento');
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



function concatena_familia($vector1=null, $nomVar=null, $extra=null){
	$cod = array();
	if($vector1 != null){
		foreach($vector1 as $x => $y){
			if($x<10){
				$cod[$x] = '00'.$x;
			}else if($x>=10 && $x<=99){
				$cod[$x] = '0'.$x;
			}else if($x>=100 && $x<=999){
				$cod[$x] = $x;
			}
		}
		//print_r($cod);
	}

	$this->set($nomVar, $cod);
}//fin function


 function filtro(){
	$this->layout="ajax";
	$cod_republica     = $this->Session->read('CC_republica');
	$cod_estado        = $this->Session->read('CC_estado');
	$cod_municipio     = $this->Session->read('CC_municipio');
	$cod_parroquia     = $this->Session->read('CC_parroquia');
	$cod_centro        = $this->Session->read('CC_centro');
	$cod_concejo       = $this->Session->read('CC_concejo');


	return $conditions  = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro." and cod_concejo=".$cod_concejo;

 }


function index($id1=null, $id2=null){
	$this->layout="ajax";


	    $cod_republica     = $this->Session->read('CC_republica');
 	 	$cod_estado        = $this->Session->read('CC_estado');
 	 	$cod_municipio     = $this->Session->read('CC_municipio');
 	 	$cod_parroquia     = $this->Session->read('CC_parroquia');
  	    $cod_centro        = $this->Session->read('CC_centro');
 	    $cod_concejo       = $this->Session->read('CC_concejo');



 	    $sql_a   = "    cod_republica                                 = '".$cod_republica."'     and
					    cod_estado                                    = '".$cod_estado."'        and
					    cod_municipio               				  = '".$cod_municipio."'     and
					    cod_parroquia             				      = '".$cod_parroquia."'     and
					    cod_centro                				      = '".$cod_centro."'        and
					    cod_concejo                                   = '".$cod_concejo."'          ";



	if(!isset($_SESSION["contar_grilla"])){$_SESSION["contar_grilla"] = 1;}


 						echo"<script>";
				          	    echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_2').style.display='none'; ";
					            echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_3').style.display='none'; ";
					            echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_4').style.display='none'; ";
					            echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_5').style.display='none'; ";
					     echo"</script>";

    if($id1!=null && $id2!=null){



    }else{

            $this->Session->delete("items1");
			$this->Session->delete("i");
			$this->Session->delete("contador");

			$this->Session->write("nuevo_year_index", date("Y"));

			if($this->Session->read('concejos_comunal_id1')==""){$id1=0;}else{$id1=$this->Session->read('concejos_comunal_id1');}
			if($this->Session->read('concejos_comunal_id2')==""){$id2=0;}else{$id2=$this->Session->read('concejos_comunal_id2');}

                 $sql_b   = $sql_a."   and  ano                                          = '".$id1."'
					            	   and  quitar_acentos(mayus_acentos(cod_proyecto))  = quitar_acentos(mayus_acentos('".$id2."'))       ";


	    	if($this->ccnd02_proyectos->findCount($sql_b)!=0){

	    		$this->consulta_especifica($id1, $id2);

	        }//fin if


	}//fin else

	$requerimiento=$this->ccnd02_requerimientos->generateList($this->filtro()." and status=1",'cod_requerimiento ASC', null, '{n}.ccnd02_requerimientos.cod_requerimiento', '{n}.ccnd02_requerimientos.cod_requerimiento');
	if($requerimiento!=null){
//		$this->concatena($requerimiento,'requerimiento');
		$this->set('requerimiento',$requerimiento);
	}else{
		$this->set('requerimiento',array());
	}


/*
$conditions  = "     cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." ";
 $conditions .= " and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo')." and ano=".$this->Session->read("nuevo_year_index");


$proyectos=$this->ccnd02_proyectos->generateList($conditions,'cod_proyecto ASC', null, '{n}.ccnd02_proyectos.cod_proyecto','{n}.ccnd02_proyectos.cod_proyecto');
	if($proyectos!=null){
//		$this->concatenaN($familia,'familia');
//		$this->concatena_familia($proyectos,'proyectos');
		$this->set('proyectos',$proyectos);
	}else{
		$this->set('proyectos',array());
	}
*/

$this->concatena($this->cnmd06_profesiones->generateList(null,'cod_profesion ASC', null, '{n}.cnmd06_profesiones.cod_profesion', '{n}.cnmd06_profesiones.denominacion'),'profesion');


}//index


function muestra($var=null){
	$this->layout="ajax";
	if($var!=''){
	   	$ver=$this->ccnd02_requerimientos->execute("select * from ccnd02_requerimientos where ".$this->filtro()." and cod_requerimiento='$var' order by cod_requerimiento");

		$ver2=$this->ccnd02_tipo_requerimiento->execute("select * from ccnd02_tipo_requerimiento where cod_tipo_requerimiento=".$ver[0][0]['cod_tipo_requerimiento']);
		$this->set('cod_requerimiento',$var);
		$this->set('deno_requerimieno',$ver[0][0]['denominacion']);
		$this->set('deno_tipo_requerimieno',$ver2[0][0]['denominacion']);

	}else{
		$this->set('cod_requerimiento','');
		$this->set('deno_requerimieno','');
		$this->set('deno_tipo_requerimieno','');
	}
		$requerimiento=$this->ccnd02_requerimientos->generateList($this->filtro()." and status=1",'cod_requerimiento ASC', null, '{n}.ccnd02_requerimientos.cod_requerimiento', '{n}.ccnd02_requerimientos.cod_requerimiento');
		if($requerimiento!=null){
	//		$this->concatena($requerimiento,'requerimiento');
			$this->set('requerimiento',$requerimiento);
		}else{
			$this->set('requerimiento',array());
		}


}

function seleccion_proyecto($var=null){
	$this->layout="ajax";

    $cod_republica     = $this->Session->read('CC_republica');
 	$cod_estado        = $this->Session->read('CC_estado');
 	$cod_municipio     = $this->Session->read('CC_municipio');
 	$cod_parroquia     = $this->Session->read('CC_parroquia');
    $cod_centro        = $this->Session->read('CC_centro');
    $cod_concejo       = $this->Session->read('CC_concejo');

    $conditions  = "     cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." ";
 	$conditions .= " and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo')." and ano=".$this->Session->read("nuevo_year_index")." and cod_proyecto='".$var."'";



    if($var!=''){
    	if($var!='agregar'){

	       $x=$this->ccnd02_proyectos->findAll($conditions,null,"ano, cod_proyecto ASC",1,null,null);
	       $ano           = $x[0]["ccnd02_proyectos"]["ano"];
			$cod_proyecto  = $x[0]["ccnd02_proyectos"]["cod_proyecto"];


			$sql_1     = "SELECT * from ccnd02_proyectos_profesionales where ".$conditions."' order by cedula_identidad asc; ";
			$result_1  = $this->ccnd02_proyectos_profesionales->execute($sql_1);

			$this->set('grilla',$result_1);


			$this->Session->write('concejos_comunal_id1', $ano);
			$this->Session->write('concejos_comunal_id2', $cod_proyecto);



			echo"<script>";
			    echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_2').style.display='block'; ";
			    echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_3').style.display='block'; ";
			    echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_4').style.display='block'; ";
			    echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_5').style.display='block'; ";
			echo"</script>";




    	}else{

    	}

    }else{

    }

}







function funcion(){
	$this->layout="ajax";
}//index







function nuevo_year_index($var1=null){
	$this->layout="ajax";

       $this->Session->write("nuevo_year_index", $var1);

	$this->funcion();
	$this->render("funcion");
}//index





function comprobar_cod_proyecto_index($var1=null){
	$this->layout="ajax";

	    $cod_republica     = $this->Session->read('CC_republica');
 	 	$cod_estado        = $this->Session->read('CC_estado');
 	 	$cod_municipio     = $this->Session->read('CC_municipio');
 	 	$cod_parroquia     = $this->Session->read('CC_parroquia');
  	    $cod_centro        = $this->Session->read('CC_centro');
 	    $cod_concejo       = $this->Session->read('CC_concejo');
 	    $nuevo_year_index  = $this->Session->read("nuevo_year_index");

 	    $sql_a   = "    cod_republica                                 = '".$cod_republica."'     and
					    cod_estado                                    = '".$cod_estado."'        and
					    cod_municipio               				  = '".$cod_municipio."'     and
					    cod_parroquia             				      = '".$cod_parroquia."'     and
					    cod_centro                				      = '".$cod_centro."'        and
					    cod_concejo                                   = '".$cod_concejo."'       and
					    ano                                           = '".$nuevo_year_index."'  and
					    quitar_acentos(mayus_acentos(cod_proyecto))   = quitar_acentos(mayus_acentos('".$var1."'))       ";


		if($this->ccnd02_proyectos->findCount($sql_a)==0){

           echo" <script> document.getElementById('guardar').disabled=false; </script>";

		}else{
           $this->set("errorMessage", "El proyecto ".$var1." ya esta registrado en el".$nuevo_year_index);

           echo" <script> document.getElementById('guardar').disabled=true; </script>";

		}//fin else








	$this->funcion();
	$this->render("funcion");
}//index





function buscar_vista_1($var1=null){

	$this->layout="ajax";
	$this->Session->delete('pista');

}//fin function





function buscar_vista_2($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
$sql_like = "";

        $cod_republica = $this->Session->read('CC_republica');
 	 	$cod_estado    = $this->Session->read('CC_estado');
 	 	$cod_municipio = $this->Session->read('CC_municipio');
 	 	$cod_parroquia = $this->Session->read('CC_parroquia');
  	    $cod_centro    = $this->Session->read('CC_centro');
 	    $cod_concejo   = $this->Session->read('CC_concejo');

 	    $sql_concejo = " cod_republica='".$cod_republica."' and cod_estado='".$cod_estado."' and  cod_municipio='".$cod_municipio."' and  cod_parroquia='".$cod_parroquia."' and cod_centro='".$cod_centro."' and cod_concejo='".$cod_concejo."'  ";

	if(isset($var2)){

		            $var1 = $this->Session->read('pista');
					$var1 = strtoupper_sisap($var1);
					$pagina = $var2;
					$sql     =" (".$this->busca_separado(array("ano", "cod_proyecto", "nombre_proyecto", "responsable_proyecto"), $var1).") ";


		$Tfilas=$this->ccnd02_proyectos->findCount($sql_concejo." and ".$sql);
        if($Tfilas!=0){

					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->ccnd02_proyectos->findAll($sql_concejo." and ".$sql,null,"ano, cod_proyecto, nombre_proyecto, responsable_proyecto ASC",100,$pagina,null);
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
					$sql     =" (".$this->busca_separado(array("ano", "cod_proyecto", "nombre_proyecto", "responsable_proyecto"), $var1).") ";

		$Tfilas=$this->ccnd02_proyectos->findCount($sql_concejo." and ".$sql);
        if($Tfilas!=0){
	        					$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->ccnd02_proyectos->findAll($sql_concejo." and ".$sql,null,"ano, cod_proyecto, nombre_proyecto, responsable_proyecto ASC",100,$pagina,null);
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











function guardar(){

	$this->layout="ajax";

	 	$cod_republica = $this->Session->read('CC_republica');
 	 	$cod_estado    = $this->Session->read('CC_estado');
 	 	$cod_municipio = $this->Session->read('CC_municipio');
 	 	$cod_parroquia = $this->Session->read('CC_parroquia');
  	    $cod_centro    = $this->Session->read('CC_centro');
 	    $cod_concejo   = $this->Session->read('CC_concejo');

 	    $guardar[]=0;
	    $i=0;

if(!empty($this->data['ccnp01_identificacion_proyecto']['ano'])){
if(!empty($this->data['ccnp01_identificacion_proyecto']['cod_proyecto'])){
if(!empty($this->data['ccnp01_identificacion_proyecto']['nombre_proyecto'])){
if(!empty($this->data['ccnp01_identificacion_proyecto']['fecha_proyecto'])){
if(!empty($this->data['ccnp01_identificacion_proyecto']['responsable_proyecto'])){
if(!empty($this->data['ccnp01_identificacion_proyecto']['cedula_identidad'])){
if(!empty($this->data['ccnp01_identificacion_proyecto']['cargo'])){
if(!empty($this->data['ccnp01_identificacion_proyecto']['lugar_ejecucion'])){
if(!empty($this->data['ccnp01_identificacion_proyecto']['duracion_proyecto'])){
if(!empty($this->data['ccnp01_identificacion_proyecto']['costo_proyecto'])){
if(!empty($this->data['ccnp01_identificacion_proyecto']['cod_requerimiento'])){



	   if(!empty($this->data) && isset($_SESSION ["items1"]) && ($_SESSION ["items1"]!=null || $_SESSION ["items1"]!=array())){


						    if(!empty($this->data['ccnp01_identificacion_proyecto']['cod_proyecto'])){

							 	$ano                    =  $this->data['ccnp01_identificacion_proyecto']['ano'];
							 	$cod_proyecto           =  $this->data['ccnp01_identificacion_proyecto']['cod_proyecto'];
							 	$nombre_proyecto        =  $this->data['ccnp01_identificacion_proyecto']['nombre_proyecto'];
							 	$fecha_proyecto         =  $this->data['ccnp01_identificacion_proyecto']['fecha_proyecto'];
							 	$responsable_proyecto   =  $this->data['ccnp01_identificacion_proyecto']['responsable_proyecto'];
							 	$cedula_identidad       =  $this->data['ccnp01_identificacion_proyecto']['cedula_identidad'];
							 	$cargo                  =  $this->data['ccnp01_identificacion_proyecto']['cargo'];
							 	$lugar_ejecucion        =  $this->data['ccnp01_identificacion_proyecto']['lugar_ejecucion'];
							 	$duracion_proyecto      =  $this->data['ccnp01_identificacion_proyecto']['duracion_proyecto'];
							 	$costo_proyecto         =  $this->Formato1($this->data['ccnp01_identificacion_proyecto']['costo_proyecto']);
							 	$cod_requerimiento      = $this->data['ccnp01_identificacion_proyecto']['cod_requerimiento'];
							 	$status=1;

					 	        $identificacion_problema   =  "0";
							    $diagnostico_situacion     =  "0";
							    $formulacion_alternativa   =  "0";
							    $sintesis_propuesta        =  "0";
							    $objetivo_general          =  "0";
							    $objectivos_especificos    =  "0";
							    $metas_fisicas             =  "0";
							    $plan_ejecucion            =  "0";
							    $plan_desembolso           =  "0";
							    $rendimiento_proyecto      =  "0";
							    $resultados_inmediatos     =  "0";
							    $resultados_mediano_plazo  =  "0";
							    $impacto_economico         =  "0";
							    $impacto_social            =  "0";
							    $impacto_ambiental         =  "0";
							    $beneficiarios             =  "0";
							    $empleos_generados         =  "0";



					 $campos_a =  "   cod_republica,
									  cod_estado,
									  cod_municipio,
									  cod_parroquia,
									  cod_centro,
									  cod_concejo,
									  ano,
									  cod_proyecto,
									  fecha_proyecto,
									  responsable_proyecto,
									  cedula_identidad,
									  cargo,
									  nombre_proyecto,
									  lugar_ejecucion,
									  duracion_proyecto,
									  costo_proyecto,
									  identificacion_problema,
									  diagnostico_situacion,
									  formulacion_alternativa,
									  sintesis_propuesta,
									  objetivo_general,
									  objetivos_especificos,
									  metas_fisicas,
									  plan_ejecucion,
									  plan_desembolso,
									  rendimiento_proyecto,
									  resultados_inmediatos,
									  resultados_mediano_plazo,
									  impacto_economico,
									  impacto_social,
									  impacto_ambiental,
									  beneficiarios,
									  empleos_generados,
									  cod_requerimiento,
									  status	";


					$values_a = "     '".$cod_republica."',
									  '".$cod_estado."',
									  '".$cod_municipio."',
									  '".$cod_parroquia."',
									  '".$cod_centro."',
									  '".$cod_concejo."',
									  '".$ano."',
									  '".$cod_proyecto."',
									  '".$fecha_proyecto."',
									  '".$responsable_proyecto."',
									  '".$cedula_identidad."',
									  '".$cargo."',
									  '".$nombre_proyecto."',
									  '".$lugar_ejecucion."',
									  '".$duracion_proyecto."',
									  '".$costo_proyecto."',
									  '".$identificacion_problema."',
									  '".$diagnostico_situacion."',
									  '".$formulacion_alternativa."',
									  '".$sintesis_propuesta."',
									  '".$objetivo_general."',
									  '".$objectivos_especificos."',
									  '".$metas_fisicas."',
									  '".$plan_ejecucion."',
									  '".$plan_desembolso."',
									  '".$rendimiento_proyecto."',
									  '".$resultados_inmediatos."',
									  '".$resultados_mediano_plazo."',
									  '".$impacto_economico."',
									  '".$impacto_social."',
									  '".$impacto_ambiental."',
									  '".$beneficiarios."',
									  '".$empleos_generados."',
									  '".$cod_requerimiento."',
									  '".$status."'";




							 	$sql = "BEGIN; INSERT INTO ccnd02_proyectos (".$campos_a.") VALUES (".$values_a."); ";
						   		$sw=$this->ccnd02_proyectos->execute($sql);

									foreach($_SESSION ["items1"] as $guardar){
										if($guardar!=null){


											$campos_b =  "    cod_republica,
															  cod_estado,
															  cod_municipio,
															  cod_parroquia,
															  cod_centro,
															  cod_concejo,
															  ano,
															  cod_proyecto,
															  cedula_identidad,
															  apellidos_nombres,
															  profesion,
															  numero_colegio ";

											 $values_b =  "   '".$cod_republica."',
															  '".$cod_estado."',
															  '".$cod_municipio."',
															  '".$cod_parroquia."',
															  '".$cod_centro."',
															  '".$cod_concejo."',
															  '".$ano."',
															  '".$cod_proyecto."',

															  '".$guardar[1]."',
															  '".$guardar[0]."',
															  '".$guardar[2]."',
															  '".$guardar[3]."' ";

											$sql_insert = "INSERT INTO ccnd02_proyectos_profesionales (".$campos_b.") VALUES(".$values_b."); ";
											$sw2 = $this->ccnd02_proyectos_profesionales->execute($sql_insert);
										}
									   $i++;
								     }


								if($sw>1 && $sw2>1){
									$sql2 = "update ccnd02_requerimientos set cod_proyecto='".$cod_proyecto."',status=2 where ".$this->filtro()." and cod_requerimiento='$cod_requerimiento'";
	   	 							$sw4=$this->ccnd02_tipo_requerimiento->execute($sql2);
	   	 							if($sw4>1){
										$this->ccnd02_proyectos_profesionales->execute("COMMIT;");
										$this->set('Message_existe', 'REGISTRO GUARDADO CON EXITO');
										echo" <script> ver_documento('/ccnp01_identificacion_proyecto/','tab_pestana_2'); </script>";
										echo"<script>";
								          	    echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_2').style.display='block'; ";
									            echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_3').style.display='block'; ";
									            echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_4').style.display='block'; ";
									            echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_5').style.display='block'; ";
									     echo"</script>";

									     $this->Session->write('concejos_comunal_id1', $ano);
		   								 $this->Session->write('concejos_comunal_id2', $cod_proyecto);
	   	 							}else{
										echo"<script>";
							          	    echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_2').style.display='none'; ";
								            echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_3').style.display='none'; ";
								            echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_4').style.display='none'; ";
								            echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_5').style.display='none'; ";
									     echo"</script>";

									     $this->Session->write('concejos_comunal_id1', "");
		   								 $this->Session->write('concejos_comunal_id2', "");

							   			$this->ccnd02_proyectos_profesionales->execute("ROLLBACK;");
							   			$this->set('errorMessage', 'LOS DATOS no fueron guardados');
	   	 							}


						   		}else{
						   			echo"<script>";
							          	    echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_2').style.display='none'; ";
								            echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_3').style.display='none'; ";
								            echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_4').style.display='none'; ";
								            echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_5').style.display='none'; ";
								     echo"</script>";

								     $this->Session->write('concejos_comunal_id1', "");
	   								 $this->Session->write('concejos_comunal_id2', "");

						   			$this->ccnd02_proyectos_profesionales->execute("ROLLBACK;");
						   			$this->set('errorMessage', 'LOS DATOS no fueron guardados');
						   		}

						    }else{
							$this->set('errorMessage', 'DEBE INGRESAR TODOS LOS DATOS');
							}

	}else{
		$this->set('errorMessage', 'DEBE INGRESAR TODOS LOS DATOS');
	}


}else{ $this->set('errorMessage', 'DEBE seleccionar el código de requerimiento'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR el costo del proyecto'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR la duración del proyecto'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR el lugar de ejecución'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR el cargo'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR la cédula de identidad'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR el responsale del proyecto'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR la fecha del proyecto'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR el nombre del proyecto'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR el código del proyecto'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR el año del proyecto'); }


	$this->funcion();
	$this->render("funcion");



}//fin function













function guardar_modificar($var1=null, $var2=null, $var3=null, $var4=null){

	$this->layout="ajax";


	 	$cod_republica = $this->Session->read('CC_republica');
 	 	$cod_estado    = $this->Session->read('CC_estado');
 	 	$cod_municipio = $this->Session->read('CC_municipio');
 	 	$cod_parroquia = $this->Session->read('CC_parroquia');
  	    $cod_centro    = $this->Session->read('CC_centro');
 	    $cod_concejo   = $this->Session->read('CC_concejo');

 	    $guardar[]=0;
	    $i=0;

if(!empty($this->data['ccnp01_identificacion_proyecto']['ano'])){
if(!empty($this->data['ccnp01_identificacion_proyecto']['cod_proyecto'])){
if(!empty($this->data['ccnp01_identificacion_proyecto']['nombre_proyecto'])){
if(!empty($this->data['ccnp01_identificacion_proyecto']['fecha_proyecto'])){
if(!empty($this->data['ccnp01_identificacion_proyecto']['responsable_proyecto'])){
if(!empty($this->data['ccnp01_identificacion_proyecto']['cedula_identidad'])){
if(!empty($this->data['ccnp01_identificacion_proyecto']['cargo'])){
if(!empty($this->data['ccnp01_identificacion_proyecto']['lugar_ejecucion'])){
if(!empty($this->data['ccnp01_identificacion_proyecto']['duracion_proyecto'])){
if(!empty($this->data['ccnp01_identificacion_proyecto']['costo_proyecto'])){



	   if(!empty($this->data) && isset($_SESSION ["items1"]) && ($_SESSION ["items1"]!=null || $_SESSION ["items1"]!=array())){


						    if(!empty($this->data['ccnp01_identificacion_proyecto']['cod_proyecto'])){

							 	$ano                    =  $this->data['ccnp01_identificacion_proyecto']['ano'];
							 	$cod_proyecto           =  $this->data['ccnp01_identificacion_proyecto']['cod_proyecto'];
							 	$nombre_proyecto        =  $this->data['ccnp01_identificacion_proyecto']['nombre_proyecto'];
							 	$fecha_proyecto         =  $this->data['ccnp01_identificacion_proyecto']['fecha_proyecto'];
							 	$responsable_proyecto   =  $this->data['ccnp01_identificacion_proyecto']['responsable_proyecto'];
							 	$cedula_identidad       =  $this->data['ccnp01_identificacion_proyecto']['cedula_identidad'];
							 	$cargo                  =  $this->data['ccnp01_identificacion_proyecto']['cargo'];
							 	$lugar_ejecucion        =  $this->data['ccnp01_identificacion_proyecto']['lugar_ejecucion'];
							 	$duracion_proyecto      =  $this->data['ccnp01_identificacion_proyecto']['duracion_proyecto'];
							 	$costo_proyecto         =  $this->Formato1($this->data['ccnp01_identificacion_proyecto']['costo_proyecto']);




					 $sql_a   = "   cod_republica                = '".$cod_republica."'     and
								    cod_estado                   = '".$cod_estado."'        and
								    cod_municipio                = '".$cod_municipio."'     and
								    cod_parroquia                = '".$cod_parroquia."'     and
								    cod_centro                   = '".$cod_centro."'        and
								    cod_concejo                  = '".$cod_concejo."'       and
								    ano                          = '".$ano."'               and
								    cod_proyecto                 = '".$cod_proyecto."'       ";


					$values_a = "     fecha_proyecto             = '".$fecha_proyecto."',
									  responsable_proyecto       = '".$responsable_proyecto."',
									  cedula_identidad           = '".$cedula_identidad."',
									  cargo                      = '".$cargo."',
									  nombre_proyecto            = '".$nombre_proyecto."',
									  lugar_ejecucion            = '".$lugar_ejecucion."',
									  duracion_proyecto          = '".$duracion_proyecto."',
									  costo_proyecto             = '".$costo_proyecto."'";



							 	$sql = " BEGIN; UPDATE ccnd02_proyectos SET ".$values_a." where ".$sql_a."; ";
						   		$sw=$this->ccnd02_proyectos->execute($sql);

									foreach($_SESSION ["items1"] as $guardar){
										if($guardar!=null){


											 $sql_b  = "  cod_republica                = '".$cod_republica."'     and
														  cod_estado                   = '".$cod_estado."'        and
														  cod_municipio                = '".$cod_municipio."'     and
														  cod_parroquia                = '".$cod_parroquia."'     and
														  cod_centro                   = '".$cod_centro."'        and
														  cod_concejo                  = '".$cod_concejo."'       and
														  ano                          = '".$ano."'               and
														  cod_proyecto                 = '".$cod_proyecto."'      and
														  cedula_identidad             = '".$guardar[1]."'  ";


														  if($this->ccnd02_proyectos_profesionales->findCount($sql_b)==0){


																			   	 $campos_b =  "   cod_republica,
																								  cod_estado,
																								  cod_municipio,
																								  cod_parroquia,
																								  cod_centro,
																								  cod_concejo,
																								  ano,
																								  cod_proyecto,
																								  cedula_identidad,
																								  apellidos_nombres,
																								  profesion,
																								  numero_colegio ";

																				 $values_b =  "   '".$cod_republica."',
																								  '".$cod_estado."',
																								  '".$cod_municipio."',
																								  '".$cod_parroquia."',
																								  '".$cod_centro."',
																								  '".$cod_concejo."',
																								  '".$ano."',
																								  '".$cod_proyecto."',
																								  '".$guardar[1]."',
																								  '".$guardar[0]."',
																								  '".$guardar[2]."',
																								  '".$guardar[3]."' ";

																				   $sql_insert = "INSERT INTO ccnd02_proyectos_profesionales (".$campos_b.") VALUES(".$values_b."); "  ;

																			   }else{

																			   	   $values_b =  "   apellidos_nombres = '".$guardar[0]."',
																								    profesion         = '".$guardar[2]."',
																								    numero_colegio    = '".$guardar[3]."' ";

																			   	   $sql_insert = "UPDATE ccnd02_proyectos_profesionales SET ".$values_b." where ".$sql_b."; ";

																			   }//fin else


													$sw2 = $this->ccnd02_proyectos_profesionales->execute($sql_insert);

												}//fin if
									   $i++;
								     }


								if($sw>1 && $sw2>1){
									$this->ccnd02_proyectos_profesionales->execute("COMMIT;");
									$this->set('Message_existe', 'REGISTRO GUARDADO CON EXITO');

									if($var4==1){
								        echo" <script> ver_documento('/ccnp01_identificacion_proyecto/consulta_especifica/".$var1."/".$var2."/".$var3."/".$var4."','tab_pestana_2'); </script>";
								 	}else{
								        echo" <script> ver_documento('/ccnp01_identificacion_proyecto/consulta/".$var3."','tab_pestana_2'); </script>";
								 	}//fin else

								 	echo"<script>";
							          	    echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_2').style.display='block'; ";
								            echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_3').style.display='block'; ";
								            echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_4').style.display='block'; ";
								            echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_5').style.display='block'; ";
								     echo"</script>";

						   		}else{

						   			 echo"<script>";
							          	    echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_2').style.display='none'; ";
								            echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_3').style.display='none'; ";
								            echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_4').style.display='none'; ";
								            echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_5').style.display='none'; ";
								     echo"</script>";

								     $this->Session->write('concejos_comunal_id1', "");
	   			                     $this->Session->write('concejos_comunal_id2', "");

						   			$this->ccnd02_proyectos_profesionales->execute("ROLLBACK;");
						   			$this->set('errorMessage', 'LOS DATOS no fueron guardados');

						   		}//fin else

						    }else{
							$this->set('errorMessage', 'DEBE INGRESAR TODOS LOS DATOS');
							}

	}else{
		$this->set('errorMessage', 'DEBE INGRESAR TODOS LOS DATOS');
	}



}else{ $this->set('errorMessage', 'DEBE INGRESAR el costo del proyecto'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR la duración del proyecto'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR el lugar de ejecución'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR el cargo'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR la cédula de identidad'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR el responsale del proyecto'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR la fecha del proyecto'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR el nombre del proyecto'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR el código del proyecto'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR el año del proyecto'); }


	$this->funcion();
	$this->render("funcion");



}//fin function




function guardar_items($ano=null, $proyecto=null, $pagina=null, $opcion=null){

	$this->layout="ajax";

	$cod_republica = $this->Session->read('CC_republica');
 	$cod_estado    = $this->Session->read('CC_estado');
 	$cod_municipio = $this->Session->read('CC_municipio');
 	$cod_parroquia = $this->Session->read('CC_parroquia');
    $cod_centro    = $this->Session->read('CC_centro');
    $cod_concejo   = $this->Session->read('CC_concejo');

	$pr_apellidos_nombres   =  $this->data['ccnp01_identificacion_proyecto']['pr_apellidos_nombres'];
    $pr_cedula_identidad    =  $this->data['ccnp01_identificacion_proyecto']['pr_cedula_identidad'];
    $pr_profesion           =  $this->data['ccnp01_identificacion_proyecto']['pr_profesion'];
    $pr_numero_colegio      =  $this->data['ccnp01_identificacion_proyecto']['pr_numero_colegio'];

	$campos_b =  "    cod_republica,
					  cod_estado,
					  cod_municipio,
					  cod_parroquia,
					  cod_centro,
					  cod_concejo,
					  ano,
					  cod_proyecto,
					  cedula_identidad,
					  apellidos_nombres,
					  profesion,
					  numero_colegio ";

	 $values_b =  "   '".$cod_republica."',
					  '".$cod_estado."',
					  '".$cod_municipio."',
					  '".$cod_parroquia."',
					  '".$cod_centro."',
					  '".$cod_concejo."',
					  '".$ano."',
					  '".$proyecto."',
					  '".$pr_cedula_identidad."',
					  '".$pr_apellidos_nombres."',
					  '".$pr_profesion."',
					  '".$pr_numero_colegio."' ";

	$sql_insert = "INSERT INTO ccnd02_proyectos_profesionales (".$campos_b.") VALUES(".$values_b."); ";
	$sw2 = $this->ccnd02_proyectos_profesionales->execute($sql_insert);

	if($sw2>1){
		echo" <script> ver_documento('/ccnp01_identificacion_proyecto/modificar/$ano/$proyecto/$pagina/$opcion','tab_pestana_2'); </script>";
	}


}









function agregar_grilla($var=null) {
	$this->layout="ajax";
	$paren=$this->cnmd06_profesiones->findAll();
	$this->set('paren',$paren);


	if(empty($this->data['ccnp01_identificacion_proyecto']['pr_apellidos_nombres']) || empty($this->data['ccnp01_identificacion_proyecto']['pr_cedula_identidad']) || empty($this->data['ccnp01_identificacion_proyecto']['pr_profesion']) || empty($this->data['ccnp01_identificacion_proyecto']['pr_numero_colegio'])){
		$this->set('errorMessage', 'Debe ingresar los datos requeridos');
		if(!isset($_SESSION["contador"])){
 			$this->set('vacio','');
 		}
		return;
	}


	    $pr_apellidos_nombres   =  $this->data['ccnp01_identificacion_proyecto']['pr_apellidos_nombres'];
	    $pr_cedula_identidad    =  $this->data['ccnp01_identificacion_proyecto']['pr_cedula_identidad'];
	    $pr_profesion           =  $this->data['ccnp01_identificacion_proyecto']['pr_profesion'];
	    $pr_numero_colegio      =  $this->data['ccnp01_identificacion_proyecto']['pr_numero_colegio'];


	if(isset($_SESSION["contador"])){
        $_SESSION["contador"]=$_SESSION["contador"]+1;
	}else{
		$_SESSION["contador"]=1;
	}

	if(isset($var) && !empty($var)){

			$cod[0]=$pr_apellidos_nombres;
			$cod[1]=$pr_cedula_identidad;
			$cod[2]=$pr_profesion;
			$cod[3]=$pr_numero_colegio;

		    if(isset($_SESSION["i"])){
				$i=$this->Session->read("i")+1;
				$this->Session->write("i",$i);
	   		 }else{
			   $this->Session->write("i",0);
				$i=0;
			}

			        switch($var){
			        	case 'normal':
							     $vec[$i][0]=$pr_apellidos_nombres;
							     $vec[$i][1]=$pr_cedula_identidad;
								 $vec[$i][2]=$pr_profesion;
								 $vec[$i][3]=$pr_numero_colegio;
								 $vec[$i]["id"]=$i;
								if(isset($_SESSION["items1"])){
									foreach($_SESSION["items1"] as $codi){
			            	           if($codi[1]==$cod[1]){
			                              $est=true;
			                              break;
			            	          }else{
			            	          	 $est=false;
			            	          }
			                        }//fin foreach
			                        if($est==true){
			            	          	$i=$this->Session->read("i")-1;
							            $this->Session->write("i",$i);
							            $this->set('errorMessage', 'Esta persona ya existe en la lista');
			                        }else{
			                        	$_SESSION["items1"]=$_SESSION["items1"]+$vec;
			                        }
								 }else{
									$_SESSION["items1"]=$vec;
								 }

			        	break;

			        }//fin switch

		}//fin if

		echo "<script>";
			echo "document.getElementById('agregar').disabled=false;";
		echo "</script>";



}//fin funcion







function limpiar_lista() {
	$this->layout = "ajax";
	$this->Session->delete("items1");
	$this->Session->delete("i");
	$this->Session->delete("contador");
}







function eliminar_items($id) {
	$this->layout = "ajax";


        $cod_republica = $this->Session->read('CC_republica');
 	 	$cod_estado    = $this->Session->read('CC_estado');
 	 	$cod_municipio = $this->Session->read('CC_municipio');
 	 	$cod_parroquia = $this->Session->read('CC_parroquia');
  	    $cod_centro    = $this->Session->read('CC_centro');
 	    $cod_concejo   = $this->Session->read('CC_concejo');


     $cont=0;
	 foreach($_SESSION ["items1"] as $in => $codigos){
	 	 if($codigos!=null){
			       if($id==$codigos["id"]){
			       	     if(isset($codigos[4])){

			           	           $sql_b  = "cod_republica                = '".$cod_republica."'     and
											  cod_estado                   = '".$cod_estado."'        and
											  cod_municipio                = '".$cod_municipio."'     and
											  cod_parroquia                = '".$cod_parroquia."'     and
											  cod_centro                   = '".$cod_centro."'        and
											  cod_concejo                  = '".$cod_concejo."'       and
											  ano                          = '".$codigos[5]."'        and
											  cod_proyecto                 = '".$codigos[6]."'        and
											  cedula_identidad             = '".$codigos[1]."'            ";


					                $x2 = $this->ccnd02_proyectos_profesionales->execute("DELETE FROM ccnd02_proyectos_profesionales  WHERE ".$sql_b." ");
			                        $this->set('errorMessage','registro eliminado con exito');

			             }
			       	    $_SESSION["items1"][$in]=null;
			      }//fin if
			      $cont++;
	 	 }//fin if
	}//fin foreach


	$NL=0;
	$codigos1=array();
	foreach($_SESSION ["items1"] as $codigos){
       if($codigos!=null){
       		$codigos1[$NL][0]=$codigos[0];
       		$codigos1[$NL][1]=$codigos[1];
       		$codigos1[$NL][2]=$codigos[2];
       		$codigos1[$NL][3]=$codigos[3];
       		if(isset($codigos[4])){$codigos1[$NL][4]=$codigos[4];}//fin if
       		if(isset($codigos[5])){$codigos1[$NL][5]=$codigos[5];}//fin if
       		if(isset($codigos[6])){$codigos1[$NL][6]=$codigos[6];}//fin if
       		$codigos1[$NL]['id']=$codigos["id"];
       		$NL++;
       }//fin if
	}//fin foreach

    $_SESSION["contador"]=$_SESSION["contador"]-1;


    if($NL==0){
        $this->Session->delete("items1");
	    $this->Session->delete("i");
	    $this->Session->delete("contador");
    }else{
    	$_SESSION["items1"]=array();
        $_SESSION["items1"]=$codigos1;
    }



}//fin function













function consulta($pagina=null) {
	$this->layout="ajax";
	$paren=$this->cnmd06_profesiones->findAll();
	$this->set('paren',$paren);

	$this->set('opcion', 2);

	    $cod_republica = $this->Session->read('CC_republica');
 	 	$cod_estado    = $this->Session->read('CC_estado');
 	 	$cod_municipio = $this->Session->read('CC_municipio');
 	 	$cod_parroquia = $this->Session->read('CC_parroquia');
  	    $cod_centro    = $this->Session->read('CC_centro');
 	    $cod_concejo   = $this->Session->read('CC_concejo');

 	    if(isset($_SESSION['nuevo_year_index'])){
 	    	$ano=$this->Session->read("nuevo_year_index");
 	    }else{
			$ano=date("Y");
 	    }

 	    $sql_concejo = " cod_republica='".$cod_republica."' and cod_estado='".$cod_estado."' and  cod_municipio='".$cod_municipio."' and  cod_parroquia='".$cod_parroquia."' and cod_centro='".$cod_centro."' and cod_concejo='".$cod_concejo."' and ano='$ano' ";

	if(isset($pagina)){
		$Tfilas=$this->ccnd02_proyectos->findCount($sql_concejo);
        if($Tfilas!=0){
	        	$x=$this->ccnd02_proyectos->findAll($sql_concejo,null,"ano, cod_proyecto ASC",1,$pagina,null);
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
	 	        $this->index();
			    $this->render("index");

			return;
        }

	}else{
		$pagina=1;
		$Tfilas=$this->ccnd02_proyectos->findCount($sql_concejo);

        if($Tfilas!=0){
	        	$x=$this->ccnd02_proyectos->findAll($sql_concejo, null,"ano, cod_proyecto ASC",1,$pagina,null);
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
	 	        $this->index();
			    $this->render("index");

			 return;
        }
	}




$ano           = $x[0]["ccnd02_proyectos"]["ano"];
$cod_proyecto  = $x[0]["ccnd02_proyectos"]["cod_proyecto"];


$sql_1     = "SELECT * from ccnd02_proyectos_profesionales where ".$sql_concejo." and ano='".$ano."' and cod_proyecto='".$cod_proyecto."' order by cedula_identidad asc; ";
$result_1  = $this->ccnd02_proyectos_profesionales->execute($sql_1);

$this->set('grilla',$result_1);


$this->Session->write('concejos_comunal_id1', $ano);
$this->Session->write('concejos_comunal_id2', $cod_proyecto);


$ver=$this->ccnd02_requerimientos->execute("select * from ccnd02_requerimientos where ".$this->filtro()." and cod_requerimiento=".$x[0]['ccnd02_proyectos']['cod_requerimiento']);

$ver2=$this->ccnd02_tipo_requerimiento->execute("select * from ccnd02_tipo_requerimiento where cod_tipo_requerimiento=".$ver[0][0]['cod_tipo_requerimiento']);
$this->set('cod_requerimiento',$ver[0][0]['cod_requerimiento']);
$this->set('deno_requerimieno',$ver[0][0]['denominacion']);
$this->set('deno_tipo_requerimieno',$ver2[0][0]['denominacion']);

$requerimiento=$this->ccnd02_requerimientos->generateList($this->filtro()." and status=1",'cod_requerimiento ASC', null, '{n}.ccnd02_requerimientos.cod_requerimiento', '{n}.ccnd02_requerimientos.cod_requerimiento');
if($requerimiento!=null){
//		$this->concatena($requerimiento,'requerimiento');
	$this->set('requerimiento',$requerimiento);
}else{
	$this->set('requerimiento',array());
}


echo"<script>";
    echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_2').style.display='block'; ";
    echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_3').style.display='block'; ";
    echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_4').style.display='block'; ";
    echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_5').style.display='block'; ";
echo"</script>";


}//consultar










function eliminar($var1=null, $var2=null, $var3=null, $var4=null){

	  $this->layout = "ajax";

	    $cod_republica = $this->Session->read('CC_republica');
 	 	$cod_estado    = $this->Session->read('CC_estado');
 	 	$cod_municipio = $this->Session->read('CC_municipio');
 	 	$cod_parroquia = $this->Session->read('CC_parroquia');
  	    $cod_centro    = $this->Session->read('CC_centro');
 	    $cod_concejo   = $this->Session->read('CC_concejo');
	    $ano           = $var1;
	    $cod_proyecto  = $var2;

 	    $sql_concejo = " cod_republica='".$cod_republica."' and cod_estado='".$cod_estado."' and  cod_municipio='".$cod_municipio."' and  cod_parroquia='".$cod_parroquia."' and cod_centro='".$cod_centro."' and cod_concejo='".$cod_concejo."'  ";

				$x2 = $this->ccnd02_proyectos_profesionales->execute("DELETE FROM ccnd02_proyectos_profesionales  WHERE ".$sql_concejo." and ano='".$ano."' and cod_proyecto='".$cod_proyecto."';    ");
				$x  = $this->ccnd02_proyectos->execute("              DELETE FROM ccnd02_proyectos                WHERE ".$sql_concejo." and ano='".$ano."' and cod_proyecto='".$cod_proyecto."';    ");

		 $this->set('Message_existe','registro eliminado con exito');


		 	    $Tfilas=$this->ccnd02_proyectos->findCount($sql_concejo);

		        if($Tfilas!=0){

			        	     if($var4==1){
			 	                 $this->consulta();
							 }else{
							 	 $this->consulta($var3);
							 }//fin function

					   $this->render("consulta");

		        }else{

		                $this->index();
		                $this->render("index");

		        }//fin else



		        $this->Session->write('concejos_comunal_id1', "");
	   			$this->Session->write('concejos_comunal_id2', "");



echo"<script>";
  	    echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_2').style.display='none'; ";
        echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_3').style.display='none'; ";
        echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_4').style.display='none'; ";
        echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_5').style.display='none'; ";
 echo"</script>";







}//fin function
















function modificar($var1=null, $var2=null, $var3=null, $var4=null, $var5=null) {


	$this->layout="ajax";

	$this->concatena($this->cnmd06_profesiones->generateList(null,'cod_profesion ASC', null, '{n}.cnmd06_profesiones.cod_profesion', '{n}.cnmd06_profesiones.denominacion'),'profesion');
    $paren=$this->cnmd06_profesiones->findAll();
    $this->set('paren',$paren);


	    $this->Session->delete("i");
	    $this->Session->delete("items1");
	    $this->Session->delete("contador");

	   				    echo"<script>";
				          	    echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_2').style.display='none'; ";
					            echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_3').style.display='none'; ";
					            echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_4').style.display='none'; ";
					            echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_5').style.display='none'; ";
					     echo"</script>";

	    $cod_republica = $this->Session->read('CC_republica');
 	 	$cod_estado    = $this->Session->read('CC_estado');
 	 	$cod_municipio = $this->Session->read('CC_municipio');
 	 	$cod_parroquia = $this->Session->read('CC_parroquia');
  	    $cod_centro    = $this->Session->read('CC_centro');
 	    $cod_concejo   = $this->Session->read('CC_concejo');


 	    $this->Session->write('concejos_comunal_id1', "");
	   	$this->Session->write('concejos_comunal_id2', "");

	    $ano           = $var1;
	    $cod_proyecto  = $var2;
	    $pagina        = $var3;
	    $opcion        = $var4;


       $sql_concejo = " cod_republica='".$cod_republica."' and cod_estado='".$cod_estado."' and  cod_municipio='".$cod_municipio."' and  cod_parroquia='".$cod_parroquia."' and cod_centro='".$cod_centro."' and cod_concejo='".$cod_concejo."' and ano='".$ano."' and cod_proyecto='".$cod_proyecto."' ";

       $x  = $this->ccnd02_proyectos->findAll($sql_concejo);
       $xx = $this->ccnd02_proyectos_profesionales->findAll($sql_concejo);

       $this->set('DATA',$x);
       $this->set('DATA2',$xx);


		$this->set('pagina',$pagina);
		$this->set('opcion',$opcion);


	for($i=0; $i<count($xx); $i++){
             $vec2[$i][0]    = $xx[$i]["ccnd02_proyectos_profesionales"]['apellidos_nombres'];
		     $vec2[$i][1]    = $xx[$i]["ccnd02_proyectos_profesionales"]['cedula_identidad'];
			 $vec2[$i][2]    = $xx[$i]["ccnd02_proyectos_profesionales"]['profesion'];
			 $vec2[$i][3]    = $xx[$i]["ccnd02_proyectos_profesionales"]['numero_colegio'];
			 $vec2[$i][4]    = "si";
			 $vec2[$i][5]    = $var1;
			 $vec2[$i][6]    = $var2;
			 $vec2[$i]["id"] = $i;
	}//fin for



   $_SESSION["items1"]=$vec2;
   $this->Session->write("i",$i);
   $this->Session->write("contador", ($i+1));


   $ver=$this->ccnd02_requerimientos->execute("select * from ccnd02_requerimientos where ".$this->filtro()." and cod_requerimiento=".$x[0]['ccnd02_proyectos']['cod_requerimiento']);

$ver2=$this->ccnd02_tipo_requerimiento->execute("select * from ccnd02_tipo_requerimiento where cod_tipo_requerimiento=".$ver[0][0]['cod_tipo_requerimiento']);
$this->set('cod_requerimiento',$ver[0][0]['cod_requerimiento']);
$this->set('deno_requerimieno',$ver[0][0]['denominacion']);
$this->set('deno_tipo_requerimieno',$ver2[0][0]['denominacion']);

$requerimiento=$this->ccnd02_requerimientos->generateList($this->filtro()." and status=1",'cod_requerimiento ASC', null, '{n}.ccnd02_requerimientos.cod_requerimiento', '{n}.ccnd02_requerimientos.cod_requerimiento');
if($requerimiento!=null){
//		$this->concatena($requerimiento,'requerimiento');
	$this->set('requerimiento',$requerimiento);
}else{
	$this->set('requerimiento',array());
}




 }//consultar





















function consulta_especifica($var1=null, $var2=null, $var3=null, $var4=null, $var5=null){

	$this->layout="ajax";

	    $cod_republica = $this->Session->read('CC_republica');
 	 	$cod_estado    = $this->Session->read('CC_estado');
 	 	$cod_municipio = $this->Session->read('CC_municipio');
 	 	$cod_parroquia = $this->Session->read('CC_parroquia');
  	    $cod_centro    = $this->Session->read('CC_centro');
 	    $cod_concejo   = $this->Session->read('CC_concejo');

 	    $ano           = $var1;
	    $cod_proyecto  = $var2;


       $sql_concejo = " cod_republica='".$cod_republica."' and cod_estado='".$cod_estado."' and  cod_municipio='".$cod_municipio."' and  cod_parroquia='".$cod_parroquia."' and cod_centro='".$cod_centro."' and cod_concejo='".$cod_concejo."' and ano='".$ano."' and cod_proyecto='".$cod_proyecto."' ";


       $pagina=1;

			    $Tfilas=$this->ccnd02_proyectos->findCount($sql_concejo);
	        	$x=$this->ccnd02_proyectos->findAll($sql_concejo, null,"ano, cod_proyecto ASC",1,$pagina,null);
				$this->set('DATA',$x);
				$this->set('pagina',$pagina);
	          	$this->set('siguiente',$pagina+1);
	          	$this->set('anterior',$pagina-1);
	          	$this->bt_nav($Tfilas,$pagina);
	          	$this->set('numT',$Tfilas);
				$this->set('numP',$pagina);



       $sql_1     = "SELECT * from ccnd02_proyectos_profesionales where ".$sql_concejo." and ano='".$ano."' and cod_proyecto='".$cod_proyecto."' order by cedula_identidad asc; ";
       $result_1  = $this->ccnd02_proyectos_profesionales->execute($sql_1);

       $this->set('grilla',$result_1);


       $this->Session->write('concejos_comunal_id1', $ano);
	   $this->Session->write('concejos_comunal_id2', $cod_proyecto);



       $this->set('opcion', 1);

       echo"<script>";
      	    echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_2').style.display='block'; ";
            echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_3').style.display='block'; ";
            echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_4').style.display='block'; ";
            echo"document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_concejos_comunal"]."_5').style.display='block'; ";
      echo"</script>";

      $paren=$this->cnmd06_profesiones->findAll();
	  $this->set('paren',$paren);


	$ver=$this->ccnd02_requerimientos->execute("select * from ccnd02_requerimientos where ".$this->filtro()." and cod_requerimiento=".$x[0]['ccnd02_proyectos']['cod_requerimiento']);

	$ver2=$this->ccnd02_tipo_requerimiento->execute("select * from ccnd02_tipo_requerimiento where cod_tipo_requerimiento=".$ver[0][0]['cod_tipo_requerimiento']);
	$this->set('cod_requerimiento',$ver[0][0]['cod_requerimiento']);
	$this->set('deno_requerimieno',$ver[0][0]['denominacion']);
	$this->set('deno_tipo_requerimieno',$ver2[0][0]['denominacion']);

	$requerimiento=$this->ccnd02_requerimientos->generateList($this->filtro()." and status=1",'cod_requerimiento ASC', null, '{n}.ccnd02_requerimientos.cod_requerimiento', '{n}.ccnd02_requerimientos.cod_requerimiento');
	if($requerimiento!=null){
//		$this->concatena($requerimiento,'requerimiento');
		$this->set('requerimiento',$requerimiento);
	}else{
		$this->set('requerimiento',array());
	}

  $this->render("consulta");




}//consultar


}//fin class

?>