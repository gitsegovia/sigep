<?php
/*
 * Fecha: 19/07/2007
 *
 * Por Luis Alfredo Diaz Jaramillo
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */
class Cnmp02EmpleadosPuestosController extends AppController {
   var $name = 'Cnmp02_empleados_puestos';
   var $uses = array('cnmd02_empleados_ramos', 'cnmd02_empleados_grupos', 'cnmd02_empleados_series', 'cnmd02_empleados_puestos');
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
 	//this->set('mensaje', 'Inserte los datos de la clase');


		$this->Session->delete('cod_1');
		$this->Session->delete('cod_2');
		$this->Session->delete('cod_3');

    $this->concatena_sin_cero($this->cnmd02_empleados_ramos->generateList(null, 'cod_ramo ASC', null, '{n}.Cnmd02_empleados_ramos.cod_ramo', '{n}.Cnmd02_empleados_ramos.denominacion'), 'lista');
 	$this->data = null;

 }

 function principal($var1 = null, $var2=null){
 	$this->layout = "ajax";
 	//echo "var1 = ".$var1." el var 2 es: ".$var2;
 	$this->set('action', $var2);
 	$this->set('tipo', $this->cnmd02_empleados_ramos->generateList(null, 'cod_ramo ASC', null, '{n}.Cnmd02_empleados_ramos.cod_ramo', '{n}.Cnmd02_empleados_ramos.cod_ramo'));

 	if($var2 != 'otros'){

		$this->set('datos1', $this->cnmd02_empleados_ramos->findAll('cod_ramo = '.$var1));
		$this->set('datos2', $this->cnmd02_empleados_grupos->findAll('cod_ramo = '.$var1.' and cod_grupo = '.$var2));
		$this->set('area', $this->cnmd02_empleados_grupos->generateList('cod_ramo = '.$var1, 'cod_grupo ASC', null, '{n}.Cnmd02_empleados_grupos.cod_grupo', '{n}.Cnmd02_empleados_grupos.cod_grupo'));

 	}else{

 		$this->set('var1', $var1);
 		$this->set('datos1', $this->cnmd02_empleados_ramos->findAll('cod_ramo = '.$var1));
 		$this->set('area', $this->cnmd02_empleados_grupos->generateList('cod_ramo = '.$var1, 'cod_grupo ASC', null, '{n}.Cnmd02_empleados_grupos.cod_grupo', '{n}.Cnmd02_empleados_grupos.cod_grupo'));

 		$this->data['cnmp02_empleados_grupos'] = array();
 		$this->set('mensaje', 'POR FAVOR INGRESE EL CODIGO');
 		$this->set('enable', 'disabled');


 	}
	$this->set('enable', 'disabled');
 }


 function guardar(){

 	$this->layout ="ajax";
 	$this->set('enable', '');

 	if(!empty($this->data['cnmp02_empleados_puestos'])){

 		$cod_puesto = $this->data['cnmp02_empleados_puestos']['cod_puesto'];
 		$this->set('cod_puesto', $cod_puesto);
		$cod_ramo = $cod_puesto[0];
		$cod_grupo = $cod_puesto[1];
		$cod_serie = $cod_puesto[2];
		$titulo = strtoupper($this->data['cnmp02_empleados_puestos']['titulo']);
		$grado = $this->data['cnmp02_empleados_puestos']['grado'];
		$labor_general = $this->data['cnmp02_empleados_puestos']['labor_general'];
		$labor_especifica = $this->data['cnmp02_empleados_puestos']['labor_especifica'];
		$nivel_educativo = $this->data['cnmp02_empleados_puestos']['nivel_educativo'];
		$experiencia = $this->data['cnmp02_empleados_puestos']['experiencia'];
		$habilidades_destrezas = $this->data['cnmp02_empleados_puestos']['habilidades_destrezas'];
		$licencias = $this->data['cnmp02_empleados_puestos']['licencias_certificados'];

		$existe = $this->cnmd02_empleados_puestos->findCount('cod_puesto = '.$cod_puesto);

		if($existe == 0){
		$this->set('existe', false);
		$sql = "INSERT INTO cnmd02_empleados_puestos VALUES('$cod_puesto', '$titulo', '$grado' , '$labor_general', '$labor_especifica', '$nivel_educativo', '$experiencia', '$licencias', '$habilidades_destrezas')";

		$var = $this->cnmd02_empleados_puestos->execute($sql);
		//$this->set('datos1', $this->cnmd02_empleados_ramos->findAll('cod_ramo = '.$cod_ramo));
		//$this->set('datos2', $this->cnmd02_empleados_grupos->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo));
		//$this->set('datos3', $this->cnmd02_empleados_series->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo.' and cod_serie ='.$cod_serie));
		//echo "este es el var: ".$var;
		$this->set('exito', 'El registro fue guardado');
		//$this->set('datos1', $this->cnmd02_empleados_ramos->findAll('cod_ramo = '.$cod_ramo));
		//$this->set('datos2', $this->cnmd02_empleados_grupos->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo));
		//$this->set('datos3', $this->cnmd02_empleados_series->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo.' and cod_serie ='.$cod_serie));

$cod_1 = $this->Session->read('cod_1');
$cod_2 = $this->Session->read('cod_2');
$cod_3 = $this->Session->read('cod_3');
$aux = $cod_1.$cod_2.$cod_3;
echo "<script>$('grado').value='';</script>";
echo "<script>$('bt_guardar').disabled=false;</script>";
if($cod_1==9){
    $datos = $this->cnmd02_empleados_puestos->findAll("cod_puesto::text LIKE '%".$aux."%' ", null, "cod_puesto DESC");
    if(isset($datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"])){
    	$aux2 = $datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"];
    	$aux3 = $aux2[3].$aux2[4];
    	if($aux3==99){
            $this->set('error','Para el manual descriptivo '.$aux."  fueron terminados los códigos de clase");
            echo "<script>$('bt_guardar').disabled=true;</script>";
    	}else{
    		$datos = isset($datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"])?$datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"]+1:$aux."01";
            $aux   = $datos;
    	}
    }else{
           $aux = $aux."01";
    }
echo "<script>$('grado').value='99';</script>";
}//fin function

       echo "<script>$('cod_puesto').value='".$aux."';</script>";
       echo "<script>$('title').value='';</script>";

       echo "<script>$('text1').value='';</script>";
       echo "<script>$('text2').value='';</script>";
       echo "<script>$('text3').value='';</script>";
       echo "<script>$('text4').value='';</script>";
       echo "<script>$('text5').value='';</script>";
       echo "<script>$('text6').value='';</script>";

       $this->render("funcion");



		}else $this->set('error', 'YA EXISTE UN REGISTRO CON EL CODIGO INSERTADO');


 	}else{
 		$this->set('error', 'DATOS INCORRECTOS, Campos sin llenar');
 	}
 }






function valida($var1=null){
$this->layout = "ajax";
$cod_1 = $this->Session->read('cod_1');
$cod_2 = $this->Session->read('cod_2');
$cod_3 = $this->Session->read('cod_3');
$aux = $cod_1.$cod_2.$cod_3;
if(strlen($var1)>=3){
	$aux2=$var1[0].$var1[1].$var1[2];
}else{
	$aux2="";
}
if($aux==$aux2){

			if($this->cnmd02_empleados_puestos->findCount('cod_puesto = '.$var1) == 0){
		         echo "<script>$('bt_guardar').disabled=false;</script>";
		                        if($cod_1==9){
								    $datos = $this->cnmd02_empleados_puestos->findAll("cod_puesto::text LIKE '%".$aux."%' ", null, "cod_puesto DESC");
					                if(isset($datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"])){
				                    	$aux2 = $datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"];
				                    	$aux3 = $aux2[3].$aux2[4];
				                    	if($aux3==99){
				                            $this->set('error','Para el manual descriptivo '.$aux."  fueron terminados los códigos de clase");
				                            $activador = "readonly";

				                    	}else{
				                    		$datos = isset($datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"])?$datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"]+1:$aux."01";
						                    $aux   = $datos;
				                    	}
				                    }else{
		                    	           $aux = $aux."01";
		                            }
				                     echo "<script>$('cod_puesto').value='".$aux."';</script>";
		          	            }//fin function

			}else{

			                 if($cod_1==9){
								    $datos = $this->cnmd02_empleados_puestos->findAll("cod_puesto::text LIKE '%".$aux."%' ", null, "cod_puesto DESC");
					                if(isset($datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"])){
				                    	$aux2 = $datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"];
				                    	$aux3 = $aux2[3].$aux2[4];
				                    	if($aux3==99){
				                            $this->set('error','Para el manual descriptivo '.$aux."  fueron terminados los códigos de clase");
				                            $activador = "readonly";

				                    	}else{
				                    		$datos = isset($datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"])?$datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"]+1:$aux."01";
						                    $aux   = $datos;
				                    	}
				                    }else{
		                    	          $aux = $aux."01";
		                            }
				                    echo "<script>$('bt_guardar').disabled=false;</script>";
		          	            }else{
		          	            	echo "<script>$('bt_guardar').disabled=true;</script>";
		          	            	$this->set('error','El código '.$var1.' se encuentra registrado ');
		          	            }
		       echo "<script>$('cod_puesto').value='".$aux."';</script>";

		    }
}else{
$this->set('error','ingrese un código valido ');
echo "<script>$('cod_puesto').value='".$aux."';</script>";
echo "<script>$('bt_guardar').disabled=true;</script>";
}
	$this->render("funcion");
}//fin function
function select($var1=null, $var2_aux=null){
$this->layout = "ajax";
$activador = "";
if($var2_aux==null){$var2_aux=0;}

          if($var1==1){ $var2 = $var1+1;
          	            $var3 = $var1+2;
          	            $this->Session->write('cod_1', $var2_aux);
          	            $this->Session->delete('cod_2');
		                $this->Session->delete('cod_3');
                        $sql_re = "cod_ramo=".$var2_aux;
          	            $this->concatena_sin_cero($this->cnmd02_empleados_grupos->generateList($sql_re, 'cod_grupo ASC', null, '{n}.Cnmd02_empleados_grupos.cod_grupo', '{n}.Cnmd02_empleados_grupos.denominacion'), 'lista');
          	            $this->set("codigo",  "cod_grupo");
          	            $this->set("cod_ramo", $var2_aux);

          	            $datos = $this->cnmd02_empleados_ramos->findAll($sql_re);
          	            $deno  = javascript_encode($datos[0]["Cnmd02_empleados_ramos"]["denominacion"], 1);
                        echo "<script>$('denominacion_ramo').value=\"$deno\";</script>";
                        echo "<script>$('denominacion_grupo').value='';</script>";
                        echo "<script>$('denominacion_serie').value='';</script>";

    }else if($var1==2){ $var2  = $var1+1;
          	            $var3  = $var1+2;
                        $cod_1 = $this->Session->read('cod_1');
          	            $this->Session->write('cod_2', $var2_aux);
          	            $this->Session->delete('cod_3');
          	            $sql_re = "cod_ramo=".$cod_1." and cod_grupo=".$var2_aux;
          	            $this->concatena_sin_cero($this->cnmd02_empleados_series->generateList($sql_re, 'cod_serie ASC', null, '{n}.Cnmd02_empleados_series.cod_serie', '{n}.Cnmd02_empleados_series.denominacion'), 'lista');
          	            $this->set("codigo",  "cod_serie");

          	            $datos = $this->cnmd02_empleados_grupos->findAll($sql_re);
          	            $deno  = javascript_encode($datos[0]["Cnmd02_empleados_grupos"]["denominacion"], 1);
                        echo "<script>$('denominacion_grupo').value=\"$deno\";</script>";
                        echo "<script>$('denominacion_serie').value='';</script>";

    }else if($var1==3){ $var2  = $var1+1;
          	            $var3  = $var1+2;
                        $cod_1 = $this->Session->read('cod_1');
                        $cod_2 = $this->Session->read('cod_2');
          	            $this->Session->write('cod_3', $var2_aux);

          	            $sql_re = "cod_ramo=".$cod_1." and cod_grupo=".$cod_2." and cod_serie=".$var2_aux;

          	            $datos = $this->cnmd02_empleados_series->findAll($sql_re);
          	            $deno  = javascript_encode($datos[0]["Cnmd02_empleados_series"]["denominacion"], 1);
                        echo "<script>$('denominacion_serie').value=\"$deno\";</script>";

          	            $aux = $cod_1.$cod_2.$var2_aux;
//          	            echo $aux."<br>";
          	            if($cod_1==9){
						    //$datos = $this->cnmd02_empleados_puestos->findAll("cod_puesto::text LIKE '%".$aux."%' ", null, "cod_puesto DESC");
						    $datos = $this->cnmd02_empleados_puestos->findAll("cod_puesto::text LIKE '".$aux."%' ", null, "cod_puesto DESC");
			                //print_r($datos[0]);
			                if(isset($datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"])){
		                    	$aux2 = $datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"];
		                    	$aux3 = $aux2[3].$aux2[4];
		                    	if($aux3==99){
		                            $this->set('error','Para el manual descriptivo '.$aux."  fueron terminados los códigos de clase");
		                            $activador = "readonly";
		                    	}else{
		                    		$datos = isset($datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"])?$datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"]+1:$aux."01";
				                    $aux   = $datos;
		                    	}
		                    }else{
		                    	$aux = $aux."01";
		                    }
          	            }//fin function
          	            //echo $aux;
          	            $this->set("codigo", $aux);
    }
if($var2_aux==0){$var2_aux=null;}
$this->set("n1", $var2);
$this->set("n2", $var3);
$this->set("activador", $activador);
$this->set("opcion",  $var1);
$this->set("opcion2", $var2_aux);
}//fin function
























 function mostrar($var = null){
 	$this->layout ="ajax";
 	if($var != null){
 		$this->set('opc', strlen($var));
 	}

 	if($var != null && strlen($var)==1)	{
 		$cod_ramo = $var[0];
 		if($this->cnmd02_empleados_ramos->findCount('cod_ramo = '.$cod_ramo) != 0){
 			$this->set('datos1', $this->cnmd02_empleados_ramos->findAll('cod_ramo = '.$cod_ramo));
 			$this->set('aux', 'ok');
 		}else{
 			$this->set('error','No existe el ramo');
 			$this->set('aux', '');
 		}
 	}else if($var != null && strlen($var)==2){
 		$cod_ramo = $var[0];
 		$cod_grupo = $var[1];
 		if($this->cnmd02_empleados_grupos->findCount('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo) != 0){
 			$this->set('datos1', $this->cnmd02_empleados_ramos->findAll('cod_ramo = '.$cod_ramo));
			$this->set('datos2', $this->cnmd02_empleados_grupos->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo));
		}else{
			$this->set('opc', 1);
 			$this->set('aux', 'ok');
			$this->set('datos1', $this->cnmd02_empleados_ramos->findAll('cod_ramo = '.$cod_ramo));
			$this->set('error','No existe el grupo indicado');
			$this->set('codigo_ingresado',$var[0]);
		}
 	}else if($var != null && strlen($var)==3) {
 		$cod_ramo = $var[0];
 		$cod_grupo = $var[1];
 		$cod_serie = $var[2];
 		if($this->cnmd02_empleados_series->findCount('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo.' and cod_serie ='.$cod_serie) != 0){
			$this->set('datos1', $this->cnmd02_empleados_ramos->findAll('cod_ramo = '.$cod_ramo));
			$this->set('datos2', $this->cnmd02_empleados_grupos->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo));
			$this->set('datos3', $this->cnmd02_empleados_series->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo.' and cod_serie ='.$cod_serie));
			if($cod_ramo==9){
           	    $aux   = $cod_ramo.$cod_grupo.$cod_serie;
			    $datos = $this->cnmd02_empleados_puestos->findAll("cod_puesto::text LIKE '%".$aux."%' ", null, "cod_puesto DESC");
			    $aux_datos3 = $this->cnmd02_empleados_series->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo.' and cod_serie ='.$cod_serie);
	            if(isset($datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"]) && !empty($aux_datos3)){
                    	$aux2 = $datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"];
                    	$aux3 = $aux2[3].$aux2[4];
                    	if($aux3==99){
                            $this->set('error','Para el manual descriptivo '.$aux."  fueron terminadas las clases");
                    	}else{
                    		$datos = isset($datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"])?$datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"]+1:$aux."01";
		                    $aux   = $datos;
		                    $this->set('enceder_save',true);
                    	}
                    }else if(!empty($aux_datos3)){
                            $datos = isset($datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"])?$datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"]+1:$aux."01";
		                    $aux   = $datos;
                    }
	            $this->set('codigo_ingresado',$aux);
             }//fin if
		    $this->set('enceder_save',true);
		}else{
			$this->set('opc', 2);
			$this->set('datos1', $this->cnmd02_empleados_ramos->findAll('cod_ramo = '.$cod_ramo));
			$this->set('datos2', $this->cnmd02_empleados_grupos->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo));
		    $this->set('error','No existe la serie indicada');
			$this->set('codigo_ingresado',$var[0].$var[1]);
		}
 	}else if($var != null && strlen($var)>3) {
            $cod_ramo = $var[0];
 		    $cod_grupo = $var[1];
 		    $cod_serie = $var[2];
            $this->set('datos1', $this->cnmd02_empleados_ramos->findAll('cod_ramo = '.$cod_ramo));
			$this->set('datos2', $this->cnmd02_empleados_grupos->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo));
			$this->set('datos3', $this->cnmd02_empleados_series->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo.' and cod_serie ='.$cod_serie));
			        if(strlen($var)==5){
			        	if($this->cnmd02_empleados_puestos->findCount('cod_puesto = '.$var) == 0){
					    	$aux_datos3 = $this->cnmd02_empleados_series->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo.' and cod_serie ='.$cod_serie);
                            if($cod_ramo==9){
				        	    $aux = $cod_ramo.$cod_grupo.$cod_serie;
							    $datos = $this->cnmd02_empleados_puestos->findAll("cod_puesto::text LIKE '%".$aux."%' ", null, "cod_puesto DESC");
			                    $aux_datos3 = $this->cnmd02_empleados_series->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo.' and cod_serie ='.$cod_serie);
				                if(isset($datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"]) && !empty($aux_datos3)){
			                    	$aux2 = $datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"];
			                    	$aux3 = $aux2[3].$aux2[4];
			                    	if($aux3==99){
			                            $this->set('error','Para el manual descriptivo '.$aux."  fueron terminadas las clases");
			                    	}else{
			                    		$datos = isset($datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"])?$datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"]+1:$aux."01";
					                    $aux   = $datos;
					                    $this->set('enceder_save',true);
			                    	}
			                     }else if(!empty($aux_datos3)){
			                            $datos = isset($datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"])?$datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"]+1:$aux."01";
					                    $aux   = $datos;
			                     }
								$this->set('codigo_ingresado',$aux);
			                }else if(empty($aux_datos3)){
	                             $this->set('error','No existe la serie indicada');
				                 $this->set('codigo_ingresado',$var[0].$var[1]);
                            }
						}else{
							$aux_datos3 = $this->cnmd02_empleados_series->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo.' and cod_serie ='.$cod_serie);
                            if(empty($aux_datos3)){
	                             $this->set('error','No existe la serie indicada');
				                 $this->set('codigo_ingresado',$var[0].$var[1]);
                            }else{
                            	if($cod_ramo==9){
                            		$this->set('error','El código '.$var.' se encuentra registrado ');
                            	}else{
                            		$this->set('error','El código '.$var.' se encuentra registrado ');
                            	}
							    $this->set('codigo_ingresado',$var[0].$var[1].$var[2]);
                            }
						}
	                }else{
	                	    $aux_datos3 = $this->cnmd02_empleados_series->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo.' and cod_serie ='.$cod_serie);
                            if(empty($aux_datos3)){
	                             $this->set('error','No existe la serie indicada');
				                 $this->set('codigo_ingresado',$var[0].$var[1]);
                            }
	                }
 	}
 }






  function mostrar_ultimo($var = null){
 	$this->layout ="ajax";
 	if($var != null){
 		$this->set('opc', strlen($var));
 	}


 	if($var != null && strlen($var)==3) {
 		$cod_ramo = $var[0];
 		$cod_grupo = $var[1];
 		$cod_serie = $var[2];
 		if($this->cnmd02_empleados_series->findCount('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo.' and cod_serie ='.$cod_serie) != 0){
			$this->set('datos1', $this->cnmd02_empleados_ramos->findAll('cod_ramo = '.$cod_ramo));
			$this->set('datos2', $this->cnmd02_empleados_grupos->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo));
			$this->set('datos3', $this->cnmd02_empleados_series->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo.' and cod_serie ='.$cod_serie));
            if($cod_ramo==9){
           	    $aux   = $cod_ramo.$cod_grupo.$cod_serie;
			    $datos = $this->cnmd02_empleados_puestos->findAll("cod_puesto::text LIKE '%".$aux."%' ", null, "cod_puesto DESC");

			    $aux_datos3 = $this->cnmd02_empleados_series->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo.' and cod_serie ='.$cod_serie);
	            if(isset($datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"]) && !empty($aux_datos3)){
                    	$aux2 = $datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"];
                    	$aux3 = $aux2[3].$aux2[4];
                    	if($aux3==99){
                            $this->set('error','Para el manual descriptivo '.$aux."  fueron terminadas las clases");
                    	}else{
                    		$datos = isset($datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"])?$datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"]+1:$aux."01";
		                    $aux   = $datos;
		                    $this->set('enceder_save',true);
                    	}
                    }else if(!empty($aux_datos3)){
                            $datos = isset($datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"])?$datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"]+1:$aux."01";
		                    $aux   = $datos;
                    }
	            $this->set('codigo_ingresado',$aux);
             }//fin if

		    $this->set('enceder_save',true);
		}else{
			$this->set('opc', 2);
			$this->set('datos1', $this->cnmd02_empleados_ramos->findAll('cod_ramo = '.$cod_ramo));
			$this->set('datos2', $this->cnmd02_empleados_grupos->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo));
		    $this->set('error','No existe la serie indicada');
			$this->set('codigo_ingresado',$var[0].$var[1]);
		}
 	}else if($var != null && strlen($var)>3) {
            $cod_ramo = $var[0];
 		    $cod_grupo = $var[1];
 		    $cod_serie = $var[2];
            $this->set('datos1', $this->cnmd02_empleados_ramos->findAll('cod_ramo = '.$cod_ramo));
			$this->set('datos2', $this->cnmd02_empleados_grupos->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo));
			$this->set('datos3', $this->cnmd02_empleados_series->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo.' and cod_serie ='.$cod_serie));

	        if($cod_ramo==9){
	        	    $aux = $cod_ramo.$cod_grupo.$cod_serie;
				    $datos = $this->cnmd02_empleados_puestos->findAll("cod_puesto::text LIKE '%".$aux."%' ", null, "cod_puesto DESC");
                    $aux_datos3 = $this->cnmd02_empleados_series->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo.' and cod_serie ='.$cod_serie);
	                if(isset($datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"]) && !empty($aux_datos3)){
                    	$aux2 = $datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"];
                    	$aux3 = $aux2[3].$aux2[4];
                    	if($aux3==99){
                            $this->set('error','Para el manual descriptivo '.$aux."  fueron terminadas las clases");
                    	}else{
                    		$datos = isset($datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"])?$datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"]+1:$aux."01";
		                    $aux   = $datos;
		                    $this->set('enceder_save',true);
                    	}
                     }else if(!empty($aux_datos3)){
                            $datos = isset($datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"])?$datos[0]["Cnmd02_empleados_puestos"]["cod_puesto"]+1:$aux."01";
		                    $aux   = $datos;
                     }
					$this->set('codigo_ingresado',$aux);
	         }//fin
 	}
 }//fin function











 function editar($cod_puesto = null,$pag=null,$tipo=null,$pista=null){
 	$this->layout ="ajax";
 	$this->set('enable', 'disabled');
 	$cod_ramo = $cod_puesto[0];
 	$cod_grupo = $cod_puesto[1];
 	$cod_serie = $cod_puesto[2];
 	$this->set('datos1', $this->cnmd02_empleados_ramos->findAll('cod_ramo = '.$cod_ramo));
 	$this->set('datos2', $this->cnmd02_empleados_grupos->findAll('cod_ramo = '.$cod_ramo." and cod_grupo = ".$cod_grupo));
 	$this->set('datos3', $this->cnmd02_empleados_series->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo.' and cod_serie ='.$cod_serie));
 	$this->set('datos4', $this->cnmd02_empleados_puestos->findAll('cod_puesto= '.$cod_puesto));

 	$this->set('mensaje', 'INGRESE LOS DATOS A MODIFICAR');
 	$this->set('pagina_actual',$pag);
 	$this->set('tipo',$tipo);
 	$this->set('pista',$pista);
 }

 function guardarEditar($cod_puesto = null,$pag=null,$tipo=null,$pista=null){
 	$this->layout ="ajax";

 	if($cod_puesto != null ){
 		$cod_puesto = $this->data['cnmp02_empleados_puestos']['cod_puesto'];
		$cod_ramo = $cod_puesto[0];
		$cod_grupo = $cod_puesto[1];
		$cod_serie = $cod_puesto[2];
		$titulo = $this->data['cnmp02_empleados_puestos']['titulo'];
		$grado = $this->data['cnmp02_empleados_puestos']['grado'];
		$labor_general = $this->data['cnmp02_empleados_puestos']['labor_general'];
		$labor_especifica = $this->data['cnmp02_empleados_puestos']['labor_especifica'];
		$nivel_educativo = $this->data['cnmp02_empleados_puestos']['nivel_educativo'];
		$experiencia = $this->data['cnmp02_empleados_puestos']['experiencia'];
		$habilidades_destrezas = $this->data['cnmp02_empleados_puestos']['habilidades_destrezas'];
		//$condiciones_fisicas = $this->data['cnmp02_empleados_puestos']['condiciones_fisicas'];
		//$condiciones_ambientales = $this->data['cnmp02_empleados_puestos']['condiciones_ambientales'];
		$licencias = $this->data['cnmp02_empleados_puestos']['licencias_certificados'];

 		$sql = "UPDATE cnmd02_empleados_puestos SET denominacion_clase = '".$titulo."', grado = '".$grado."', caracteristicas_trabajo = '".$labor_general."', tareas_tipicas = '".$labor_especifica."', requisitos_minimos = '".$nivel_educativo."', educacion = '".$experiencia."', clase_cargo = '".$habilidades_destrezas."', conocimientos_habilidades ='".$licencias."' WHERE cod_puesto = ".$cod_puesto;
 		$this->cnmd02_empleados_puestos->execute($sql);
		$this->set('datos1', $this->cnmd02_empleados_ramos->findAll('cod_ramo = '.$cod_ramo));
 		$this->set('datos2', $this->cnmd02_empleados_grupos->findAll('cod_ramo = '.$cod_ramo." and cod_grupo = ".$cod_grupo));
 		$this->set('datos3', $this->cnmd02_empleados_series->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo.' and cod_serie ='.$cod_serie));
 		$this->set('datos', $this->cnmd02_empleados_puestos->findAll('cod_puesto = '.$cod_puesto));
 		$this->set('enable', '');
		$this->set('mensaje', 'EL DATO FUE MODIFICADO EXITOSAMENTE');
 	}
 	$this->consulta($pag,$tipo,$pista);
 	$this->render('consulta');

 }

 function eliminar($cod_puesto = null, $valor = null){

	$this->layout ="ajax";

	if($cod_puesto != null){

		$sql = "DELETE FROM cnmd02_empleados_puestos WHERE cod_puesto = ".$cod_puesto;
		$this->cnmd02_empleados_grupos->execute($sql);
		$this->set('mensaje', 'EL REGISTRO FUE ELIMINADO');
		$c = $this->cnmd02_obreros_puestos->findCount();
 		if($c!=0){
			$this->consulta($valor);
			$this->render('consulta');
 		}else{
 			$this->index();
		    $this->render('index');
 		}
		$this->set('mensaje', 'EL REGISTRO FUE ELIMINADO');
		$this->set('enable', 'disabled');


	}

 }



  function eliminar_ventana($cod_puesto = null, $valor = null){

	$this->layout ="ajax";

	if($cod_puesto != null){

		$sql = "DELETE FROM cnmd02_empleados_puestos WHERE cod_puesto = ".$cod_puesto;
		$this->cnmd02_empleados_grupos->execute($sql);
		$this->set('mensaje', 'EL REGISTRO FUE ELIMINADO');
		$this->index();
		$this->render('index');
		$this->set('mensaje', 'EL REGISTRO FUE ELIMINADO');
		$this->set('enable', 'disabled');


	}

 }



function preconsulta(){
	$this->layout ="ajax";
	$this->set('enable', 'disabled');
	$opciones = array('cod_puesto'=>'Codigo Puesto', 'deno_puesto'=>'Denominacion Puesto');
	$this->set('opcion', $opciones);
}

function query($var=null){
	$this->layout ="ajax";
	$this->set('tipo', $var);
	if($var=='cod_puesto'){
		$this->set('fun', 'return solonumeros(event);');
	}else{
		$this->set('fun', '');
	}
}

function consulta2($tipo=null, $pista=null){
	$this->layout ="ajax";


 //   $this->consulta(null, $sql);
   // $this->render('consulta');

}

 function consulta($pag_num=null, $tipo=null, $pista=null){
 	$this->layout ="ajax";
 	$this->set('enable', '');

 		$c = $this->cnmd02_empleados_puestos->findCount();
 		if($c!=0){
 			$data = $this->cnmd02_empleados_puestos->findAll(null, null, 'cod_puesto ASC', null, $pag_num, null);
 		}else{
 			$data = array();
 			$this->set('error','No existen datos');
 			$this->index();
			$this->render('index');
 		}

    $this->set('datos',$data);
    $this->set('tipo',$tipo);
    $this->set('pista',$pista);

    if($pag_num!=null){
    	$this->set('pagina_actual', $pag_num);
    }else{
    	$this->set('pagina_actual', $pag_num);
    }


 }//fin function

function ventana () {
   $this->layout="ajax";

}//fin funcion ventana

function buscar_pista_clase ($pista=null,$pagina=null) {
   $this->layout="ajax";

  if($pagina!=null){
  	   $pagina = $pagina;
  	   $pista = $this->Session->read('pista_puesto');
  	   $pista = up(trim($pista));
  }else{
  	   $pagina = 1;
  	   $pista = up(trim($pista));
  	   $this->Session->write('pista_puesto', $pista);
  }
  if($pista!=null){
       $pista = up(trim($pista));
       $sql = "upper(cod_puesto::text) like '%$pista%' OR upper(denominacion_clase::text) like '%$pista%'";
	   $c= $this->cnmd02_empleados_puestos->findCount($sql);
	   if($c!=0){
          $data = $this->cnmd02_empleados_puestos->findAll($sql, null, 'cod_puesto ASC', null, null, null);
	   }
	    $Tfilas=$this->cnmd02_empleados_puestos->findCount($sql);
		if($Tfilas!=0){
			$Tfilas=(int)ceil($Tfilas/50);
			$this->set('pag_cant',$pagina.'/'.$Tfilas);
			$this->set('total_paginas',$Tfilas);
			$this->set('pagina_actual',$pagina);
			$this->set('ultimo',$Tfilas);
		    $datos_filas=$this->cnmd02_empleados_puestos->findAll($sql,null,"cod_puesto ASC",50,$pagina,null);
		    $this->set("datosFILAS",$datos_filas);
		    $this->set('siguiente',$pagina+1);
			$this->set('anterior',$pagina-1);
			$this->bt_nav($Tfilas,$pagina);
		  }else{
			$this->set("datosFILAS",'');
		  }
		  $this->set('pista',$pista);
  }

}//fin funcion buscar_pista_clase


 function mostrar_registro($cod_puesto = null){
 	$this->layout ="ajax";
 	$this->set('enable', 'disabled');
 	$cod_ramo = $cod_puesto[0];
 	$cod_grupo = $cod_puesto[1];
 	$cod_serie = $cod_puesto[2];
 	$this->set('datos1', $this->cnmd02_empleados_ramos->findAll('cod_ramo = '.$cod_ramo));
 	$this->set('datos2', $this->cnmd02_empleados_grupos->findAll('cod_ramo = '.$cod_ramo." and cod_grupo = ".$cod_grupo));
 	$this->set('datos3', $this->cnmd02_empleados_series->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo.' and cod_serie ='.$cod_serie));
 	$this->set('datos4', $this->cnmd02_empleados_puestos->findAll('cod_puesto= '.$cod_puesto));

 	$this->set('pagina_actual',null);
 	$this->set('tipo',null);
 	$this->set('pista',null);
 }

  function editar_ventana($cod_puesto = null,$pag=null,$tipo=null,$pista=null){
 	$this->layout ="ajax";
 	$this->set('enable', 'disabled');
 	$cod_ramo = $cod_puesto[0];
 	$cod_grupo = $cod_puesto[1];
 	$cod_serie = $cod_puesto[2];
 	$this->set('datos1', $this->cnmd02_empleados_ramos->findAll('cod_ramo = '.$cod_ramo));
 	$this->set('datos2', $this->cnmd02_empleados_grupos->findAll('cod_ramo = '.$cod_ramo." and cod_grupo = ".$cod_grupo));
 	$this->set('datos3', $this->cnmd02_empleados_series->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo.' and cod_serie ='.$cod_serie));
 	$this->set('datos4', $this->cnmd02_empleados_puestos->findAll('cod_puesto= '.$cod_puesto));

 	$this->set('mensaje', 'INGRESE LOS DATOS A MODIFICAR');
 	$this->set('pagina_actual',$pag);
 	$this->set('tipo',$tipo);
 	$this->set('pista',$pista);
 }

  function guardar_editar_ventana($cod_puesto = null,$pag=null,$tipo=null,$pista=null){
 	$this->layout ="ajax";

 	if($cod_puesto != null ){
 		$cod_puesto = $this->data['cnmp02_empleados_puestos']['cod_puesto'];
		$cod_ramo = $cod_puesto[0];
		$cod_grupo = $cod_puesto[1];
		$cod_serie = $cod_puesto[2];
		$titulo = $this->data['cnmp02_empleados_puestos']['titulo'];
		$grado = $this->data['cnmp02_empleados_puestos']['grado'];
		$labor_general = $this->data['cnmp02_empleados_puestos']['labor_general'];
		$labor_especifica = $this->data['cnmp02_empleados_puestos']['labor_especifica'];
		$nivel_educativo = $this->data['cnmp02_empleados_puestos']['nivel_educativo'];
		$experiencia = $this->data['cnmp02_empleados_puestos']['experiencia'];
		$habilidades_destrezas = $this->data['cnmp02_empleados_puestos']['habilidades_destrezas'];
		//$condiciones_fisicas = $this->data['cnmp02_empleados_puestos']['condiciones_fisicas'];
		//$condiciones_ambientales = $this->data['cnmp02_empleados_puestos']['condiciones_ambientales'];
		$licencias = $this->data['cnmp02_empleados_puestos']['licencias_certificados'];

 		$sql = "UPDATE cnmd02_empleados_puestos SET denominacion_clase = '".$titulo."', grado = '".$grado."', caracteristicas_trabajo = '".$labor_general."', tareas_tipicas = '".$labor_especifica."', requisitos_minimos = '".$nivel_educativo."', educacion = '".$experiencia."', clase_cargo = '".$habilidades_destrezas."', conocimientos_habilidades ='".$licencias."' WHERE cod_puesto = ".$cod_puesto;
 		$this->cnmd02_empleados_puestos->execute($sql);
		$this->set('datos1', $this->cnmd02_empleados_ramos->findAll('cod_ramo = '.$cod_ramo));
 		$this->set('datos2', $this->cnmd02_empleados_grupos->findAll('cod_ramo = '.$cod_ramo." and cod_grupo = ".$cod_grupo));
 		$this->set('datos3', $this->cnmd02_empleados_series->findAll('cod_ramo = '.$cod_ramo.' and cod_grupo = '.$cod_grupo.' and cod_serie ='.$cod_serie));
 		$this->set('datos', $this->cnmd02_empleados_puestos->findAll('cod_puesto = '.$cod_puesto));
 		$this->set('enable', '');
		$this->set('mensaje', 'EL DATO FUE MODIFICADO EXITOSAMENTE');
 	}
 	$this->mostrar_registro($cod_puesto);
 	$this->render('mostrar_registro');

 }

 }//fin de la clase
 ?>
