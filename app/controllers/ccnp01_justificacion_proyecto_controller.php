<?php


 class ccnp01JustificacionProyectoController extends AppController {
    var $name    = 'ccnp01_justificacion_proyecto';
	var $uses    = array('ccnd01_tipo_directivo', 'ccnd02_proyectos_alternativas', 'ccnd02_proyectos');
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



function index($id1=null, $id2=null){
	$this->layout="ajax";


	    $cod_republica     = $this->Session->read('CC_republica');
 	 	$cod_estado        = $this->Session->read('CC_estado');
 	 	$cod_municipio     = $this->Session->read('CC_municipio');
 	 	$cod_parroquia     = $this->Session->read('CC_parroquia');
  	    $cod_centro        = $this->Session->read('CC_centro');
 	    $cod_concejo       = $this->Session->read('CC_concejo');
 	    $ano               = $this->Session->read('concejos_comunal_id1');
        $cod_proyecto      = $this->Session->read('concejos_comunal_id2');

         $this->Session->delete("i");
	    $this->Session->delete("items2");
	    $this->Session->delete("contador");


 	    $sql_a   = "    cod_republica                                 = '".$cod_republica."'     and
					    cod_estado                                    = '".$cod_estado."'        and
					    cod_municipio               				  = '".$cod_municipio."'     and
					    cod_parroquia             				      = '".$cod_parroquia."'     and
					    cod_centro                				      = '".$cod_centro."'        and
					    cod_concejo                                   = '".$cod_concejo."'       and
					    ano                                           = '".$ano."'               and
					    cod_proyecto                                  = '".$cod_proyecto."'      and (identificacion_problema!='0' or diagnostico_situacion!='0' or formulacion_alternativa!='0')      ";



           if($this->ccnd02_proyectos->findCount($sql_a)!=0){

	    		$this->consulta();
	    		$this->render("consulta");

	        }//fin if







}//index

















function consulta(){
	$this->layout="ajax";

	$cod_republica     = $this->Session->read('CC_republica');
 	 	$cod_estado        = $this->Session->read('CC_estado');
 	 	$cod_municipio     = $this->Session->read('CC_municipio');
 	 	$cod_parroquia     = $this->Session->read('CC_parroquia');
  	    $cod_centro        = $this->Session->read('CC_centro');
 	    $cod_concejo       = $this->Session->read('CC_concejo');
 	    $ano               = $this->Session->read('concejos_comunal_id1');
        $cod_proyecto      = $this->Session->read('concejos_comunal_id2');


 	    $sql_a   = "    cod_republica                                 = '".$cod_republica."'     and
					    cod_estado                                    = '".$cod_estado."'        and
					    cod_municipio               				  = '".$cod_municipio."'     and
					    cod_parroquia             				      = '".$cod_parroquia."'     and
					    cod_centro                				      = '".$cod_centro."'        and
					    cod_concejo                                   = '".$cod_concejo."'       and
					    ano                                           = '".$ano."'               and
					    cod_proyecto                                  = '".$cod_proyecto."'           ";








	$datos_filas                          =  $this->ccnd02_proyectos->findAll($sql_a);
	$datos_ccnd02_proyectos_alternativas  =  $this->ccnd02_proyectos_alternativas->findAll($sql_a);


$this->set("identificacion_problema", $datos_filas[0]["ccnd02_proyectos"]["identificacion_problema"]);
$this->set("diagnostico_situacion",   $datos_filas[0]["ccnd02_proyectos"]["diagnostico_situacion"]);
$this->set("formulacion_alternativa", $datos_filas[0]["ccnd02_proyectos"]["formulacion_alternativa"]);

$this->set("datos_ccnd02_proyectos_alternativas", $datos_ccnd02_proyectos_alternativas);







}//index









function modificar(){
	$this->layout="ajax";

	    $this->Session->delete("i");
	    $this->Session->delete("items2");
	    $this->Session->delete("contador");

	$cod_republica     = $this->Session->read('CC_republica');
 	 	$cod_estado        = $this->Session->read('CC_estado');
 	 	$cod_municipio     = $this->Session->read('CC_municipio');
 	 	$cod_parroquia     = $this->Session->read('CC_parroquia');
  	    $cod_centro        = $this->Session->read('CC_centro');
 	    $cod_concejo       = $this->Session->read('CC_concejo');
 	    $ano               = $this->Session->read('concejos_comunal_id1');
        $cod_proyecto      = $this->Session->read('concejos_comunal_id2');


 	    $sql_a   = "    cod_republica                                 = '".$cod_republica."'     and
					    cod_estado                                    = '".$cod_estado."'        and
					    cod_municipio               				  = '".$cod_municipio."'     and
					    cod_parroquia             				      = '".$cod_parroquia."'     and
					    cod_centro                				      = '".$cod_centro."'        and
					    cod_concejo                                   = '".$cod_concejo."'       and
					    ano                                           = '".$ano."'               and
					    cod_proyecto                                  = '".$cod_proyecto."'           ";








	$datos_filas                          =  $this->ccnd02_proyectos->findAll($sql_a);
	$datos_ccnd02_proyectos_alternativas  =  $this->ccnd02_proyectos_alternativas->findAll($sql_a);


$this->set("identificacion_problema", $datos_filas[0]["ccnd02_proyectos"]["identificacion_problema"]);
$this->set("diagnostico_situacion",   $datos_filas[0]["ccnd02_proyectos"]["diagnostico_situacion"]);
$this->set("formulacion_alternativa", $datos_filas[0]["ccnd02_proyectos"]["formulacion_alternativa"]);

$this->set("datos_ccnd02_proyectos_alternativas", $datos_ccnd02_proyectos_alternativas);


$xx = $datos_ccnd02_proyectos_alternativas;


	for($i=0; $i<count($xx); $i++){
             $vec2[$i][0]    = $xx[$i]["ccnd02_proyectos_alternativas"]['numero_renglon'];
		     $vec2[$i][1]    = $xx[$i]["ccnd02_proyectos_alternativas"]['formulacion_solucion'];
			 $vec2[$i][2]    = $xx[$i]["ccnd02_proyectos_alternativas"]['descripcion'];
			 $vec2[$i][3]    = $this->Formato2($xx[$i]["ccnd02_proyectos_alternativas"]['costo']);
			 $vec2[$i][4]    = $xx[$i]["ccnd02_proyectos_alternativas"]['ventajas'];
			 $vec2[$i][5]    = $xx[$i]["ccnd02_proyectos_alternativas"]['desventajas'];
			 $vec2[$i][6]    = "si";
			 $vec2[$i]["id"] = $i;
	}//fin for



   $_SESSION["items2"]=$vec2;
   $this->Session->write("i",$i);
   $this->Session->write("contador", ($i+1));



}//index












function funcion(){
	$this->layout="ajax";
}//index










function agregar_grilla($var=null) {
	$this->layout="ajax";


	if(empty($this->data['ccnp01_justificacion_proyecto']['numero_renglon']) || empty($this->data['ccnp01_justificacion_proyecto']['formulacion_solucion']) || empty($this->data['ccnp01_justificacion_proyecto']['descripcion']) || empty($this->data['ccnp01_justificacion_proyecto']['costo']) || empty($this->data['ccnp01_justificacion_proyecto']['ventajas']) || empty($this->data['ccnp01_justificacion_proyecto']['desventajas'])){
		$this->set('errorMessage', 'Debe ingresar los datos requeridos');
		if(!isset($_SESSION["contador"])){
 			$this->set('vacio','');
 		}
		return;
	}//fin if


	    $numero_renglon          =  $this->data['ccnp01_justificacion_proyecto']['numero_renglon'];
	    $formulacion_solucion    =  $this->data['ccnp01_justificacion_proyecto']['formulacion_solucion'];
	    $descripcion             =  $this->data['ccnp01_justificacion_proyecto']['descripcion'];
	    $costo                   =  $this->data['ccnp01_justificacion_proyecto']['costo'];
	    $ventajas                =  $this->data['ccnp01_justificacion_proyecto']['ventajas'];
	    $desventajas             =  $this->data['ccnp01_justificacion_proyecto']['desventajas'];


	if(isset($_SESSION["contador"])){
        $_SESSION["contador"]=$_SESSION["contador"]+1;
	}else{
		$_SESSION["contador"]=1;
	}//fin else

	if(isset($var) && !empty($var)){

			$cod[0]=$numero_renglon;
			$cod[1]=$formulacion_solucion;
			$cod[2]=$descripcion;
			$cod[3]=$costo;
			$cod[4]=$ventajas;
			$cod[5]=$desventajas;

		    if(isset($_SESSION["i"])){
				$i=$this->Session->read("i")+1;
				$this->Session->write("i",$i);
	   		 }else{
			   $this->Session->write("i",0);
				$i=0;
			}

			        switch($var){
			        	case 'normal':
							     $vec[$i][0]=$numero_renglon;
							     $vec[$i][1]=$formulacion_solucion;
								 $vec[$i][2]=$descripcion;
								 $vec[$i][3]=$costo;
								 $vec[$i][4]=$ventajas;
								 $vec[$i][5]=$desventajas;
								 $vec[$i]["id"]=$i;
								if(isset($_SESSION["items2"])){
									foreach($_SESSION["items2"] as $codi){
			            	           if($codi[0]==$cod[0]){
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
			                        	$_SESSION["items2"]=$_SESSION["items2"]+$vec;
			                        }
								 }else{
									$_SESSION["items2"]=$vec;
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
	$this->Session->delete("items2");
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
 	    $ano           = $this->Session->read('concejos_comunal_id1');
        $cod_proyecto  = $this->Session->read('concejos_comunal_id2');


     $cont=0;
	 foreach($_SESSION ["items2"] as $in => $codigos){
	 	 if($codigos!=null){
			       if($id==$codigos["id"]){
			       	     if(isset($codigos[6])){

                                   $sql_b  = "cod_republica                = '".$cod_republica."'     and
											  cod_estado                   = '".$cod_estado."'        and
											  cod_municipio                = '".$cod_municipio."'     and
											  cod_parroquia                = '".$cod_parroquia."'     and
											  cod_centro                   = '".$cod_centro."'        and
											  cod_concejo                  = '".$cod_concejo."'       and
											  ano                          = '".$ano."'               and
											  cod_proyecto                 = '".$cod_proyecto."'      and
											  numero_renglon               = '".$codigos[0]."'            ";




					                $x2 = $this->ccnd02_proyectos_alternativas->execute("DELETE FROM ccnd02_proyectos_alternativas  WHERE ".$sql_b." ");
                                    $this->set('errorMessage','registro eliminado con exito');


			             }
			       	    $_SESSION["items2"][$in]=null;
			      }//fin if
			      $cont++;
	 	 }//fin if
	}//fin foreach


	$NL=0;
	$codigos1=array();
	foreach($_SESSION ["items2"] as $codigos){
       if($codigos!=null){
       		$codigos1[$NL][0]=$codigos[0];
       		$codigos1[$NL][1]=$codigos[1];
       		$codigos1[$NL][2]=$codigos[2];
       		$codigos1[$NL][3]=$codigos[3];
       		$codigos1[$NL][4]=$codigos[4];
       		$codigos1[$NL][5]=$codigos[5];
       		if(isset($codigos[6])){$codigos1[$NL][6]=$codigos[6];}//fin if
       		$codigos1[$NL]['id']=$codigos["id"];
       		$NL++;
       }//fin if
	}//fin foreach

    $_SESSION["contador"]=$_SESSION["contador"]-1;


    if($NL==0){
        $this->Session->delete("items2");
	    $this->Session->delete("i");
	    $this->Session->delete("contador");
    }else{
    	$_SESSION["items2"]=array();
        $_SESSION["items2"]=$codigos1;
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
 	    $ano           = $this->Session->read('concejos_comunal_id1');
        $cod_proyecto  = $this->Session->read('concejos_comunal_id2');




$guardar[]=0;
$i=0;

if(!empty($this->data['ccnp01_justificacion_proyecto']['identificacion_problema'])){
if(!empty($this->data['ccnp01_justificacion_proyecto']['diagnostico_situacion'])){
if(!empty($this->data['ccnp01_justificacion_proyecto']['formulacion_alternativa'])){


	   if(!empty($this->data) && isset($_SESSION ["items2"]) && ($_SESSION ["items2"]!=null || $_SESSION ["items2"]!=array())){


                                $identificacion_problema      =  $this->data['ccnp01_justificacion_proyecto']['identificacion_problema'];
							 	$diagnostico_situacion        =  $this->data['ccnp01_justificacion_proyecto']['diagnostico_situacion'];
							 	$formulacion_alternativa      =  $this->data['ccnp01_justificacion_proyecto']['formulacion_alternativa'];



									  $sql  = "UPDATE ccnd02_proyectos SET identificacion_problema='".$identificacion_problema."', diagnostico_situacion='".$diagnostico_situacion."', formulacion_alternativa='".$formulacion_alternativa."' ";

									  $sql .= " WHERE   cod_republica = '".$cod_republica."'  and
												        cod_estado    = '".$cod_estado."'     and
												        cod_municipio = '".$cod_municipio."'  and
												        cod_parroquia = '".$cod_parroquia."'  and
												        cod_centro    = '".$cod_centro."'     and
												        cod_concejo   = '".$cod_concejo."'    and
												        ano           = '".$ano."'            and
												        cod_proyecto  = '".$cod_proyecto."';  ";

							          $sw = $this->ccnd02_proyectos->execute($sql);


							          foreach($_SESSION ["items2"] as $guardar){
										if($guardar!=null){


											$campos_b =  "    cod_republica,
															  cod_estado,
															  cod_municipio,
															  cod_parroquia,
															  cod_centro,
															  cod_concejo,
															  ano,
															  cod_proyecto,

															  numero_renglon,
															  formulacion_solucion,
															  descripcion,
															  costo,
															  ventajas,
															  desventajas ";

											 $values_b =  "   '".$cod_republica."',
															  '".$cod_estado."',
															  '".$cod_municipio."',
															  '".$cod_parroquia."',
															  '".$cod_centro."',
															  '".$cod_concejo."',
															  '".$ano."',
															  '".$cod_proyecto."',

															  '".$guardar[0]."',
															  '".$guardar[1]."',
															  '".$guardar[2]."',
															  '".$this->Formato1($guardar[3])."',
															  '".$guardar[4]."',
															  '".$guardar[5]."' ";

											$sql_insert = "INSERT INTO ccnd02_proyectos_alternativas (".$campos_b.") VALUES(".$values_b."); ";
											$sw2 = $this->ccnd02_proyectos_alternativas->execute($sql_insert);
										}
									   $i++;
								     }



							    if($sw>1 && $sw2>1){


									$this->ccnd02_proyectos->execute("COMMIT;");
									$this->set('Message_existe', 'REGISTRO GUARDADO CON EXITO');

                                    echo" <script> ver_documento('/ccnp01_justificacion_proyecto/','tab_pestana_3'); </script>";


						   		}else{


						   			$this->ccnd02_proyectos->execute("ROLLBACK;");
						   			$this->set('errorMessage', 'LOS DATOS no fueron guardados');

						   		}//fin else






	   	}else{
		        $this->set('errorMessage', 'DEBE INGRESAR TODOS LOS DATOS');
	    }//fin else


}else{ $this->set('errorMessage', 'DEBE INGRESAR LA FORMULACIÓN ALTERNATIVA'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR EL DIAGNOSTICO DE LA SITUACIÓN'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR LA IDENTIFICACIÓN DEL PROBLEMA'); }




	$this->funcion();
	$this->render("funcion");




}//fin function

















function guardar_modificar(){

	$this->layout="ajax";


	 	$cod_republica = $this->Session->read('CC_republica');
 	 	$cod_estado    = $this->Session->read('CC_estado');
 	 	$cod_municipio = $this->Session->read('CC_municipio');
 	 	$cod_parroquia = $this->Session->read('CC_parroquia');
  	    $cod_centro    = $this->Session->read('CC_centro');
 	    $cod_concejo   = $this->Session->read('CC_concejo');
 	    $ano           = $this->Session->read('concejos_comunal_id1');
        $cod_proyecto  = $this->Session->read('concejos_comunal_id2');




$guardar[]=0;
$i=0;

if(!empty($this->data['ccnp01_justificacion_proyecto']['identificacion_problema'])){
if(!empty($this->data['ccnp01_justificacion_proyecto']['diagnostico_situacion'])){
if(!empty($this->data['ccnp01_justificacion_proyecto']['formulacion_alternativa'])){


	   if(!empty($this->data) && isset($_SESSION ["items2"]) && ($_SESSION ["items2"]!=null || $_SESSION ["items2"]!=array())){


                                $identificacion_problema      =  $this->data['ccnp01_justificacion_proyecto']['identificacion_problema'];
							 	$diagnostico_situacion        =  $this->data['ccnp01_justificacion_proyecto']['diagnostico_situacion'];
							 	$formulacion_alternativa      =  $this->data['ccnp01_justificacion_proyecto']['formulacion_alternativa'];



									  $sql  = "UPDATE ccnd02_proyectos SET identificacion_problema='".$identificacion_problema."', diagnostico_situacion='".$diagnostico_situacion."', formulacion_alternativa='".$formulacion_alternativa."' ";

									  $sql .= " WHERE   cod_republica = '".$cod_republica."'  and
												        cod_estado    = '".$cod_estado."'     and
												        cod_municipio = '".$cod_municipio."'  and
												        cod_parroquia = '".$cod_parroquia."'  and
												        cod_centro    = '".$cod_centro."'     and
												        cod_concejo   = '".$cod_concejo."'    and
												        ano           = '".$ano."'            and
												        cod_proyecto  = '".$cod_proyecto."';  ";

							          $sw = $this->ccnd02_proyectos->execute($sql);


							          foreach($_SESSION ["items2"] as $guardar){
										if($guardar!=null){


											 $sql_b  = "  cod_republica                = '".$cod_republica."'     and
														  cod_estado                   = '".$cod_estado."'        and
														  cod_municipio                = '".$cod_municipio."'     and
														  cod_parroquia                = '".$cod_parroquia."'     and
														  cod_centro                   = '".$cod_centro."'        and
														  cod_concejo                  = '".$cod_concejo."'       and
														  ano                          = '".$ano."'               and
														  cod_proyecto                 = '".$cod_proyecto."'      and
														  numero_renglon               = '".$guardar[0]."'  ";


														  if($this->ccnd02_proyectos_alternativas->findCount($sql_b)==0){

																				    $campos_b =  "    cod_republica,
																									  cod_estado,
																									  cod_municipio,
																									  cod_parroquia,
																									  cod_centro,
																									  cod_concejo,
																									  ano,
																									  cod_proyecto,

																									  numero_renglon,
																									  formulacion_solucion,
																									  descripcion,
																									  costo,
																									  ventajas,
																									  desventajas ";

																					 $values_b =  "   '".$cod_republica."',
																									  '".$cod_estado."',
																									  '".$cod_municipio."',
																									  '".$cod_parroquia."',
																									  '".$cod_centro."',
																									  '".$cod_concejo."',
																									  '".$ano."',
																									  '".$cod_proyecto."',

																									  '".$guardar[0]."',
																									  '".$guardar[1]."',
																									  '".$guardar[2]."',
																									  '".$this->Formato1($guardar[3])."',
																									  '".$guardar[4]."',
																									  '".$guardar[5]."' ";

																					$sql_insert = "INSERT INTO ccnd02_proyectos_alternativas (".$campos_b.") VALUES(".$values_b."); ";



																				}else{

																			   	   $values_b =  "   formulacion_solucion = '".$guardar[1]."',
																								    descripcion          = '".$guardar[2]."',
																								    costo                = '".$this->Formato1($guardar[3])."',
																								    ventajas             = '".$guardar[4]."',
																								    desventajas          = '".$guardar[5]."' ";

																			   	   $sql_insert = "UPDATE ccnd02_proyectos_alternativas SET ".$values_b." where ".$sql_b."; ";

																			   }//fin else






													$sw2 = $this->ccnd02_proyectos_alternativas->execute($sql_insert);
												}
											   $i++;




								     }//fin foreach



							    if($sw>1 && $sw2>1){


									$this->ccnd02_proyectos->execute("COMMIT;");
									$this->set('Message_existe', 'REGISTRO GUARDADO CON EXITO');

                                    echo" <script> ver_documento('/ccnp01_justificacion_proyecto/','tab_pestana_3'); </script>";


						   		}else{


						   			$this->ccnd02_proyectos->execute("ROLLBACK;");
						   			$this->set('errorMessage', 'LOS DATOS no fueron guardados');

						   		}//fin else






	   	}else{
		        $this->set('errorMessage', 'DEBE INGRESAR TODOS LOS DATOS');
	    }//fin else


}else{ $this->set('errorMessage', 'DEBE INGRESAR LA FORMULACIÓN ALTERNATIVA'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR EL DIAGNOSTICO DE LA SITUACIÓN'); }
}else{ $this->set('errorMessage', 'DEBE INGRESAR LA IDENTIFICACIÓN DEL PROBLEMA'); }




	$this->funcion();
	$this->render("funcion");




}//fin function











function eliminar($var1=null, $var2=null, $var3=null, $var4=null){

	  $this->layout = "ajax";

	    $cod_republica = $this->Session->read('CC_republica');
 	 	$cod_estado    = $this->Session->read('CC_estado');
 	 	$cod_municipio = $this->Session->read('CC_municipio');
 	 	$cod_parroquia = $this->Session->read('CC_parroquia');
  	    $cod_centro    = $this->Session->read('CC_centro');
 	    $cod_concejo   = $this->Session->read('CC_concejo');
	    $ano           = $this->Session->read('concejos_comunal_id1');
        $cod_proyecto  = $this->Session->read('concejos_comunal_id2');

 	    $sql_concejo = " cod_republica='".$cod_republica."' and cod_estado='".$cod_estado."' and  cod_municipio='".$cod_municipio."' and  cod_parroquia='".$cod_parroquia."' and cod_centro='".$cod_centro."' and cod_concejo='".$cod_concejo."'  ";

				$x2 = $this->ccnd02_proyectos_alternativas->execute("DELETE FROM ccnd02_proyectos_alternativas                                                                             WHERE ".$sql_concejo." and ano='".$ano."' and cod_proyecto='".$cod_proyecto."';    ");
				$x  = $this->ccnd02_proyectos->execute("             UPDATE      ccnd02_proyectos SET identificacion_problema='0', diagnostico_situacion='0', formulacion_alternativa='0'  WHERE ".$sql_concejo." and ano='".$ano."' and cod_proyecto='".$cod_proyecto."';    ");

		 $this->set('Message_existe','registro eliminado con exito');


		 	    $this->index();
		        $this->render("index");








}//fin function





























}//fin class

?>