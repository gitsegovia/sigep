<?php
class Casp01FusionTiposAyudasController extends AppController {
   var $name = 'casp01_fusion_tipos_ayudas';
   var $uses = array('casd01_datos_personales','casd01_datos_familiares','cugd02_institucion','cugd02_dependencia',
   					'casd01_ayudas_cuerpo','casd01_ayuda_detalles','casd01_evaluacion_ayuda','casd01_solicitud_ayuda',
   					'v_historia_solicitud_ayudas','casd01_tipo_ayuda');
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

 function verifica_SS($i){
    	/**
    	 * Funcion que permite leer las varibles de session. la cual permite capturar los codigos del usuario
    	 * para ser insertados en todas las tablas.
    	 * */
    	switch ($i){
    		case 1:return $this->Session->read('SScodpresi');break;
    		case 2:return $this->Session->read('SScodentidad');break;
    		case 3:return $this->Session->read('SScodtipoinst');break;
    		case 4:return $this->Session->read('SScodinst');break;
    		case 5:return $this->Session->read('SScoddep');break;
    		case 6:return $this->Session->read('entidad_federal');break;
    		default:
    		   return "NULO";

    	}//fin switch
 }//fin verifica_SS





		function SQLCA($ano=null){//sql para busqueda de codigos de arranque con y sin año
				 $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
				 $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
				 $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
				 $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
				 if($ano!=null){
					 $sql_re .= "cod_dep=".$this->verifica_SS(5)."  and  ";
						$sql_re .= "ano=".$ano."  ";
				 }else{
					 $sql_re .= "cod_dep=".$this->verifica_SS(5)." ";
				 }
				 return $sql_re;
		}//fin funcion SQLCA




 function beforeFilter(){
 	$this->checkSession();

 }



function concatena11($vector1=null, $nomVar=null){

	if($vector1 != null){

		foreach($vector1 as $x => $y){

			$cod[$x] = $this->zero($x).' - '.$this->denominacion1($y);
		}

		$this->set($nomVar, $cod);

	}
}//fin concatena



 function index(){
 	$this->layout ="ajax";

 	$tipo_ayuda=$this->casd01_tipo_ayuda->generateList(null,'denominacion ASC', null, '{n}.casd01_tipo_ayuda.cod_tipo_ayuda', '{n}.casd01_tipo_ayuda.denominacion');
	$this->set('tipo_ayuda',$tipo_ayuda);
	$this->concatena($tipo_ayuda,'tipo_ayuda');


 }// fin index



  function select($var=null){
 	$this->layout ="ajax";
	if($var!=null){
		$tipo_ayuda=$this->casd01_tipo_ayuda->generateList('cod_tipo_ayuda!='.$var,'denominacion ASC', null, '{n}.casd01_tipo_ayuda.cod_tipo_ayuda', '{n}.casd01_tipo_ayuda.denominacion');
		$this->set('tipo_ayuda',$tipo_ayuda);
		$this->concatena($tipo_ayuda,'tipo_ayuda');
	}else{
		$this->set('tipo_ayuda',array());
	}

 }// fin index


 function denominacion($ir=null,$var=null){
 	$this->layout ="ajax";
 	$this->set('ir',$ir);
	if($var!=null){
		$tipo_ayuda=$this->casd01_tipo_ayuda->execute("select * from casd01_tipo_ayuda where cod_tipo_ayuda=".$var);
		$this->set('tipo_ayuda',$tipo_ayuda[0][0]['denominacion']);
	}else{
		$this->set('tipo_ayuda','');
	}

 }// fin index




function procesar(){
	$this->layout = "ajax";

	$cod_fusion=$this->data['casp01']['cod_fusion'];
	$cod_reemplazo=$this->data['casp01']['cod_reemplazo'];

	$this->casd01_solicitud_ayuda->execute("BEGIN");
	$datos=$this->casd01_solicitud_ayuda->execute("select * from casd01_solicitud_ayuda where cod_tipo_ayuda=".$cod_fusion." order by cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cedula_identidad,cod_tipo_ayuda,numero_ocacion");
	$datos2=$this->casd01_evaluacion_ayuda->execute("select * from casd01_evaluacion_ayuda where cod_tipo_ayuda='$cod_fusion' order by cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cedula_identidad,cod_tipo_ayuda,numero_ocacion");
	$datos3=$this->casd01_ayudas_cuerpo->execute("select * from casd01_ayudas_cuerpo where cod_tipo_ayuda='$cod_fusion' order by cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cedula_identidad,cod_tipo_ayuda,numero_ocacion");
	$datos4=$this->casd01_ayuda_detalles->execute("select * from casd01_ayuda_detalles where cod_tipo_ayuda='$cod_fusion' order by cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cedula_identidad,cod_tipo_ayuda,numero_ocacion");
	$k=0;
	for($i=0;$i<count($datos);$i++){
		$cod_presi = $datos[$i][0]['cod_presi'];
	    $cod_entidad = $datos[$i][0]['cod_entidad'];
	    $cod_tipo_inst = $datos[$i][0]['cod_tipo_inst'];
	    $cod_inst = $datos[$i][0]['cod_inst'];
	    $cod_dep = $datos[$i][0]['cod_dep'];
	    $cedula = $datos[$i][0]['cedula_identidad'];
	    $tipo_ayuda = $cod_reemplazo;
	    $numero_ocacion = $datos[$i][0]['numero_ocacion'];
		$veri=$this->casd01_solicitud_ayuda->execute("select * from casd01_solicitud_ayuda where cod_presi='$cod_presi' and cod_entidad='$cod_entidad' and cod_tipo_inst='$cod_tipo_inst' and cod_inst='$cod_inst' and cod_dep='$cod_dep' and cedula_identidad='$cedula' and cod_tipo_ayuda='$tipo_ayuda' and numero_ocacion='$numero_ocacion'");
		if($veri!=null){
			$k++;
			break;
		}else{
			$this->casd01_solicitud_ayuda->execute("UPDATE casd01_solicitud_ayuda set cod_tipo_ayuda='$tipo_ayuda' where cod_presi='$cod_presi' and cod_entidad='$cod_entidad' and cod_tipo_inst='$cod_tipo_inst' and cod_inst='$cod_inst' and cod_dep='$cod_dep' and cedula_identidad='$cedula' and cod_tipo_ayuda='$cod_fusion' and numero_ocacion='$numero_ocacion'");
		}
	}

		if($k!=0){
//			echo "conflictos de claves primarias solicitudes ayudas <br>";
			$this->casd01_solicitud_ayuda->execute("ROLLBACK");
		}else{
//			echo "proceso perfecto solicitudes ayudas<br><br>";
			$s=0;
			for($i=0;$i<count($datos2);$i++){
				$cod_presi = $datos2[$i][0]['cod_presi'];
			    $cod_entidad = $datos2[$i][0]['cod_entidad'];
			    $cod_tipo_inst = $datos2[$i][0]['cod_tipo_inst'];
			    $cod_inst = $datos2[$i][0]['cod_inst'];
			    $cod_dep = $datos2[$i][0]['cod_dep'];
			    $cedula = $datos2[$i][0]['cedula_identidad'];
			    $tipo_ayuda = $cod_reemplazo;
			    $numero_ocacion = $datos2[$i][0]['numero_ocacion'];
			    $numero_evaluacion = $datos2[$i][0]['numero_documento_evaluacion'];
				$veri1=$this->casd01_evaluacion_ayuda->execute("select * from casd01_evaluacion_ayuda where cod_presi='$cod_presi' and cod_entidad='$cod_entidad' and cod_tipo_inst='$cod_tipo_inst' and cod_inst='$cod_inst' and cod_dep='$cod_dep' and cedula_identidad='$cedula' and cod_tipo_ayuda='$tipo_ayuda' and numero_ocacion='$numero_ocacion' and numero_documento_evaluacion='$numero_evaluacion'");
				if($veri1!=null){
					$s++;
					break;
				}else{
					$this->casd01_evaluacion_ayuda->execute("UPDATE casd01_evaluacion_ayuda set cod_tipo_ayuda='$tipo_ayuda' where cod_presi='$cod_presi' and cod_entidad='$cod_entidad' and cod_tipo_inst='$cod_tipo_inst' and cod_inst='$cod_inst' and cod_dep='$cod_dep' and cedula_identidad='$cedula' and cod_tipo_ayuda='$cod_fusion' and numero_ocacion='$numero_ocacion' and numero_documento_evaluacion='$numero_evaluacion'");
				}
			}

			if($s!=0){
//				echo "conflictos de claves primarias evaluacion ayudas <br>";
				$this->casd01_solicitud_ayuda->execute("ROLLBACK");
			}else{
//					echo "proceso perfecto evaluacion ayudas<br><br>";
					$this->casd01_solicitud_ayuda->execute("ALTER TABLE casd01_ayuda_detalles DROP CONSTRAINT cscd01_ayuda_detalles_1;");

					$c=0;
					for($i=0;$i<count($datos3);$i++){
						$cod_presi = $datos3[$i][0]['cod_presi'];
					    $cod_entidad = $datos3[$i][0]['cod_entidad'];
					    $cod_tipo_inst = $datos3[$i][0]['cod_tipo_inst'];
					    $cod_inst = $datos3[$i][0]['cod_inst'];
					    $cod_dep = $datos3[$i][0]['cod_dep'];
					    $cedula = $datos3[$i][0]['cedula_identidad'];
					    $tipo_ayuda = $cod_reemplazo;
					    $numero_ocacion = $datos3[$i][0]['numero_ocacion'];
					    $numero_evaluacion = $datos3[$i][0]['numero_documento_evaluacion'];
					    $numero_ayuda = $datos3[$i][0]['numero_documento_ayuda'];
						$veri2=$this->casd01_ayudas_cuerpo->execute("select * from casd01_ayudas_cuerpo where cod_presi='$cod_presi' and cod_entidad='$cod_entidad' and cod_tipo_inst='$cod_tipo_inst' and cod_inst='$cod_inst' and cod_dep='$cod_dep' and cedula_identidad='$cedula' and cod_tipo_ayuda='$tipo_ayuda' and numero_ocacion='$numero_ocacion' and numero_documento_evaluacion='$numero_evaluacion' and numero_documento_ayuda='$numero_ayuda'");
						if($veri2!=null){
							$c++;
							break;
						}else{
							$this->casd01_ayudas_cuerpo->execute("UPDATE casd01_ayudas_cuerpo set cod_tipo_ayuda='$tipo_ayuda' where cod_presi='$cod_presi' and cod_entidad='$cod_entidad' and cod_tipo_inst='$cod_tipo_inst' and cod_inst='$cod_inst' and cod_dep='$cod_dep' and cedula_identidad='$cedula' and cod_tipo_ayuda='$cod_fusion' and numero_ocacion='$numero_ocacion' and numero_documento_evaluacion='$numero_evaluacion' and numero_documento_ayuda='$numero_ayuda'");
						}

					}


					if($c!=0){
//						echo "conflictos de claves primarias ayudas <br>";
						$this->casd01_solicitud_ayuda->execute("ROLLBACK");
					}else{
//						echo "proceso perfecto ayudas<br><br>";

							$d=0;
							for($i=0;$i<count($datos4);$i++){
								$cod_presi = $datos4[$i][0]['cod_presi'];
							    $cod_entidad = $datos4[$i][0]['cod_entidad'];
							    $cod_tipo_inst = $datos4[$i][0]['cod_tipo_inst'];
							    $cod_inst = $datos4[$i][0]['cod_inst'];
							    $cod_dep = $datos4[$i][0]['cod_dep'];
							    $cedula = $datos4[$i][0]['cedula_identidad'];
							    $tipo_ayuda = $cod_reemplazo;
							    $numero_ocacion = $datos4[$i][0]['numero_ocacion'];
							    $numero_evaluacion = $datos4[$i][0]['numero_documento_evaluacion'];
							    $numero_ayuda = $datos4[$i][0]['numero_documento_ayuda'];
							    $renglon = $datos4[$i][0]['numero_renglon'];
								$veri3=$this->casd01_ayudas_cuerpo->execute("select * from casd01_ayuda_detalles where cod_presi='$cod_presi' and cod_entidad='$cod_entidad' and cod_tipo_inst='$cod_tipo_inst' and cod_inst='$cod_inst' and cod_dep='$cod_dep' and cedula_identidad='$cedula' and cod_tipo_ayuda='$tipo_ayuda' and numero_ocacion='$numero_ocacion' and numero_documento_evaluacion='$numero_evaluacion' and numero_documento_ayuda='$numero_ayuda' and numero_renglon='$renglon'");
								if($veri3!=null){
									$d++;
									break;
								}else{
									$this->casd01_ayuda_detalles->execute("UPDATE casd01_ayuda_detalles set cod_tipo_ayuda='$tipo_ayuda' where cod_presi='$cod_presi' and cod_entidad='$cod_entidad' and cod_tipo_inst='$cod_tipo_inst' and cod_inst='$cod_inst' and cod_dep='$cod_dep' and cedula_identidad='$cedula' and cod_tipo_ayuda='$cod_fusion' and numero_ocacion='$numero_ocacion' and numero_documento_evaluacion='$numero_evaluacion' and numero_documento_ayuda='$numero_ayuda' and numero_renglon='$renglon'");
								}

							}


							if($d!=0){
//								echo "conflictos de claves primarias ayudas detalles<br>";
								$this->casd01_solicitud_ayuda->execute("ROLLBACK");
							}else{
//								echo "proceso perfecto ayudas detalles<br><br>";

								$this->casd01_solicitud_ayuda->execute("ALTER TABLE ONLY casd01_ayuda_detalles
    																	ADD CONSTRAINT cscd01_ayuda_detalles_1 FOREIGN KEY (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cedula_identidad, cod_tipo_ayuda, numero_ocacion, numero_documento_evaluacion, numero_documento_ayuda)
    																	REFERENCES casd01_ayudas_cuerpo(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cedula_identidad, cod_tipo_ayuda, numero_ocacion, numero_documento_evaluacion, numero_documento_ayuda) ON DELETE CASCADE;");


								$delete=$this->casd01_ayuda_detalles->execute("DELETE FROM casd01_tipo_ayuda where cod_tipo_ayuda='$cod_fusion'");
									if($delete>1){
										$this->casd01_solicitud_ayuda->execute("COMMIT");
										$this->set('Message_existe', 'EL PROCESO SE EJECUTO CON EXITO');
//										echo "TODOS LOS PROCESOS SE EJECUTARON CORRECTAMENTE<br><br>";
										$this->set('guardado','si');
									}else{
										$this->casd01_solicitud_ayuda->execute("ROLLBACK");
										$this->set('errorMessage', 'EL PROCESO NO PUDO REALIZARSE');
//										echo "PROCESO FALLIDO<br><br>";
									}
							}


					}




				}




		}




}// fin guardar




 }//Fin de la clase controller
 ?>