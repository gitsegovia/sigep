<?php

 class Cepp01CompromisoController extends AppController{


 	var $uses = array('ccfd04_cierre_mes','v_cfpd05_denominaciones','cfpd21','cfpd21_numero_asiento_compromiso',
                      'cepd01_compromiso_poremitir','cepd01_compromiso_beneficiario_cedula','cepd01_compromiso_beneficiario_rif',
                      'cugd03_acta_anulacion_numero','cugd03_acta_anulacion_cuerpo','v_cfpd05_disponibilidad','cugd04',
                      'cpcd02','cscd01_catalogo','cepd01_tipo_compromiso','cepd01_compromiso_cuerpo','cepd01_compromiso_numero',
                      'cepd01_compromiso_partidas','cfpd05','cfpd05_requerimiento','cfpd05_2032_tmp','cfpd05_auxiliar',
                      'cfpp05auxiliar','cfpd02_sector', 'cfpd02_programa', 'cfpd02_sub_prog', 'cfpd02_proyecto', 'cfpd02_activ_obra',
                      'arrd05','cfpd01_ano_grupo', 'cfpd01_ano_partida', 'cfpd01_ano_generica', 'cfpd01_ano_especifica', 'cfpd01_ano_sub_espec',
                      'cfpd01_ano_auxiliar', 'ccfd03_instalacion','cugd02_direccionsuperior', 'cugd02_coordinacion', 'cugd02_secretaria',
                      'cepd03_ordenpago_cuerpo', 'cugd02_direccion', 'v_cscd01_catalogo_deno_und',
					  'cobd01_co_modificacion_partidas', 'cstd01_entidades_bancarias', 'ccfd10_descripcion', 'ccfd10_detalles', 'ccfd02', 'ccfd05_numero_asiento',
                            'ccfd04_cuentas_enlace', 'cpcd02', 'cepd01_compromiso_cuerpo', 'cscd04_ordencompra_anticipo_cuerpo',
                            'cscd04_ordencompra_encabezado', 'cscd04_ordencompra_autorizacion_cuerpo',
						    'cscd04_ordencompra_encabezado', 'cobd01_contratoobras_anticipo_cuerpo', 'cobd01_contratoobras_valuacion_cuerpo',
						    'cobd01_contratoobras_retencion_cuerpo', 'cepd02_contratoservicio_anticipo_cuerpo', 'cepd02_contratoservicio_valuacion_cuerpo',
						    'cepd02_contratoservicio_retencion_cuerpo','cobd01_contratoobras_cuerpo', 'cepd02_contratoservicio_cuerpo','cugd05_restriccion_clave');



 	var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf','Form');


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
					if($this->ano_ejecucion()!=""){
						return;
					}else{
						echo "<h3>Por Favor, Registre el Año de Ejecuci&oacute;n de Presupuesto<br>Ingrese al M&oacute;dulo de Uso General</h3>";
						exit();
					}
}

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

function SQLCA_S($ano=null){//sql para busqueda de codigos de arranque con y sin año
				 $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
				 $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
				 $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
				 $sql_re .= "cod_inst=".$this->verifica_SS(4)."   ";
				 return $sql_re;
		}//fin funcion SQLCA

		function SQLCAIN($ano=null){//sql para busqueda de codigos de arranque con y sin año
				 $sql_re = $this->verifica_SS(1).",";
				 $sql_re .= $this->verifica_SS(2).",";
				 $sql_re .=  $this->verifica_SS(3).",";
				 $sql_re .= $this->verifica_SS(4).",";
				 if($ano!=null){
					 $sql_re .= $this->verifica_SS(5).",";
						$sql_re .= $ano."";
				 }else{
					 $sql_re .=  $this->verifica_SS(5)."";
				 }
				 return $sql_re;
		}//fin funcion SQLCAIN
		function SQLCA_admin($ano=null){//sql para busqueda de codigos de arranque con y sin año
				 $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
				 $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
				 $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
				 $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
				 if($ano!=null){
					if($this->verifica_SS(5)!=1){
					 $sql_re .= "cod_dep=".$this->verifica_SS(5)."  and  ";
								}
								$sql_re .= "ano=".$ano."  ";
				 }else{
					 if($this->verifica_SS(5)!=1){
					 $sql_re .= "cod_dep=".$this->verifica_SS(5)."  ";
								}
				 }
				 return $sql_re;
		}//fin funcion SQLCA
		function SQLCA_reque($ano=null){//sql para busqueda de codigos de arranque con y sin año
				 $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
				 $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
				 $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
				 $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
				 if($ano!=null){
					 $sql_re .= "ano=".$ano."  ";
				 }else{

				 }
				 return $sql_re;
		}//fin funcion SQLCA

		function SQLCA_report($pre=null){
				 $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
				 $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
				 $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
				 if($pre!=null && $pre==1){
				 $sql_re .= "cod_inst=".$this->verifica_SS(4)." ";
				 //$sql_re .= "cod_dep=0";
				 }else{
					 $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
						$sql_re .= "cod_dep=".$this->verifica_SS(5)." ";
				 }

				 return $sql_re;
		}//fin funcion SQLCA
		function SQLCA_report_in($pre=null){
				 $sql_re = $this->verifica_SS(1).",";
				 $sql_re .= $this->verifica_SS(2).",";
				 $sql_re .= $this->verifica_SS(3).",";
				 if($pre!=null && $pre==1){
				 $sql_re .= $this->verifica_SS(4).",";
				 $sql_re .= 0;
				 }else{
					 $sql_re .= $this->verifica_SS(4).",";
						$sql_re .= $this->verifica_SS(5)." ";
				 }

				 return $sql_re;
		}//fin funcion SQLCA
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
		}
function AddCerPartida($vector=object){
			 if($vector!=null){
					foreach($vector as $x){
					if($x<10){
						 $Var[$x]="4.0".$x;
					}else{
						 $Var[$x]="4.".$x;
					}
			}//fin each
					return $Var;
			 }else{
					return "";
			 }
	 }//fin AddCero
		function AddCeroR($n,$extra=null){
			 if($n!=null){
					 if($extra==null){
					if($n<10){
						 $Var="0".$n;
					}else{
						 $Var=$n;
					}
			 }else{
					if($n<10){
						 $Var=$extra.".0".$n;
					}else{
						 $Var=$extra.".".$n;
					}
			 }
		 return $Var;
			 }else{
					 //return $Var;
			 }
	 }//fin AddCero
function concatena4($vector1=null, $nomVar=null, $extra=null){
	$cod = array();
	if($vector1 != null){
		foreach($vector1 as $x => $y){
			if($extra!=null){
				if($x<10){
					$cod[$x] = $extra.'.0'.$x.' - '.$y;
				}else if($x>=10 && $x<=99){
					$cod[$x] = $extra.'.'.$x.' - '.$y;
				}
			}else{

				if($x<10){
					$cod[$x] = '0'.$x.' - '.$y;
				}else if($x>=10 && $x<=99){
					$cod[$x] = $x.' - '.$y;
				}
			}
		}
		//print_r($cod);
	}

	$this->set($nomVar, $cod);
}//fin function

function concatena($vector1=null, $nomVar=null, $extra=null){
	$cod = array();
	if($vector1 != null){
		foreach($vector1 as $x => $y){
			if($extra!=null){
				if($x<10){
					$cod[$x] = $extra.'.0'.$x.' - '.$y;
				}else if($x>=10 && $x<=99){
					$cod[$x] = $extra.'.'.$x.' - '.$y;
				}
			}else{
				if($x<10){
					$y=($y!="")?$y:"N/A";
					$cod[$x] = '0'.$x.' - '.$y;
				}else if($x>=10){
					$y=($y!="")?$y:"N/A";
					$cod[$x] = $x.' - '.$y;
				}
			}
		}
		//print_r($cod);
	}

	$this->set($nomVar, $cod);
}//fin function
function borrar_cugd04(){
	$condicion = $this->condicion();
	$username = $this->Session->read('nom_usuario');
	$username = strtoupper($username);

	$c = $this->cugd04->findCount($condicion." and username='$username'");

	if ($c!=0){
		$this->cugd04->execute("DELETE FROM cugd04 WHERE ".$condicion." and username='$username'");
	}

}





function buscar_por_pista_year($var1=null){

	 $this->layout = "ajax";
	 $this->Session->write('year_buscar', $var1);

	 echo "<script>";
	    echo 'document.getElementById("select_obra_cod_obra").value="";';
	 echo "</script>";

}//fin function









function cargar_input_beneficiario($var1=null){

	 $this->layout = "ajax";
		$beneficiario_buscar = $this->Session->read('beneficiario_buscar');



if($beneficiario_buscar==1){

	$tabla = "cepd01_compromiso_beneficiario_cedula";
	$campo = "cedula";

	$datos = $this->$tabla->findAll($campo."='".$var1."'  ");



	 echo "<script>";
	     echo "document.getElementById('cedula').value='".$var1."';";
	     echo "document.getElementById('bene').value='".$datos[0][$tabla]["beneficiario"]."';";
	     echo "document.getElementById('rif').value='0';";
	     echo "document.getElementById('rif').disabled=true;";
	     echo "document.getElementById('cedula').disabled=false;";
	     echo "document.getElementById('condicion_juridica_1').checked=true;";

	 echo "</script>";

}else{

    $tabla = "cepd01_compromiso_beneficiario_rif";
    $campo = "rif";

    $datos = $this->$tabla->findAll($campo."='".$var1."'  ");

     echo "<script>";
	    echo "document.getElementById('rif').value='".$var1."';";
	    echo "document.getElementById('cedula').value='0';";
	    echo "document.getElementById('bene').value='".$datos[0][$tabla]["beneficiario"]."';";
	    echo "document.getElementById('cedula').disabled=true;";
	    echo "document.getElementById('rif').disabled=false;";
	    echo "document.getElementById('condicion_juridica_2').checked=true;";
	 echo "</script>";


}//fin else



}//fin function




function buscar_por_pista_beneficiario($var1=null){

	 $this->layout = "ajax";
	 $this->Session->write('beneficiario_buscar',$var1);

	 echo "<script>";
	    echo 'document.getElementById("select_obra_cod_obra").value="";';
	 echo "</script>";

}//fin function




function buscar_beneficiario_1($var1=null, $var2=null, $var3=null){
$this->layout="ajax";

     $this->Session->write('beneficiario_buscar',1);

}//fin function





function buscar_beneficiario_2($var1=null, $var2=null, $var3=null){
$this->layout="ajax";

	$year_buscar         = $this->Session->read('year_buscar');
	$beneficiario_buscar = $this->Session->read('beneficiario_buscar');



if($beneficiario_buscar==1){

	$tabla = "cepd01_compromiso_beneficiario_cedula";
	$campo = "cedula";

}else{

    $tabla = "cepd01_compromiso_beneficiario_rif";
    $campo = "rif";

}//fin else



$year_buscar         = $this->Session->read('year_buscar');



            if($var2==null){$var1 = strtoupper_sisap($var1);

					$this->Session->write('pista', $var1);
                    $ordena = "beneficiario";
					$Tfilas=$this->$tabla->findCount($this->busca_separado(array("beneficiario"), $var1));
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->$tabla->findAll($this->busca_separado(array("beneficiario"), $var1),null,$ordena." ASC",100,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }

            }else{

						$var22  = $this->Session->read('pista');
						$var22  = strtoupper_sisap($var22);
						$ordena = "beneficiario";
						$Tfilas=$this->$tabla->findCount($this->busca_separado(array("beneficiario"), $var22));

						        if($Tfilas!=0){
						        	$pagina=$var2;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->$tabla->findAll($this->busca_separado(array("beneficiario"), $var22),null,$ordena." ASC",100,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }

                 }//fin else




$this->set("tabla", $tabla);
$this->set("campo", $campo);


}//fin function



function buscar_tipo_compromiso($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
     //$this->Session->write('beneficiario_buscar',1);

}//fin function


function buscar_pista_tipo_compromiso($var1=null, $var2=null, $var3=null){
	$this->layout="ajax";
	$tabla = "cepd01_tipo_compromiso";
	$campo = "cod_tipo_compromiso";


            if($var1!=null){
            	$var1 = strtoupper_sisap($var1);
				$this->Session->write('pista', $var1);
            }else{
						$var1  = $this->Session->read('pista');
            }//fin else
                            $ordena = "cod_tipo_compromiso";
                            $Tfilas=$this->$tabla->findCount($this->busca_separado(array("cod_tipo_compromiso","denominacion"), $var1));
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->$tabla->findAll($this->busca_separado(array("cod_tipo_compromiso","denominacion"), $var1),null,$ordena." ASC",100,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }


$this->set("tabla", $tabla);
$this->set("campo", $campo);


}//fin function





















function buscar_por_pista_1($var1=null, $var2=null, $var3=null){
$this->layout="ajax";

     $this->Session->write('year_buscar', $this->ano_ejecucion());


}//fin function









function buscar_por_pista_2($var1=null, $var2=null, $var3=null){
$this->layout="ajax";


	$year_buscar         = $this->Session->read('year_buscar');

    $sql = $this->SQLCA()." and ano_documento=".$year_buscar." and (cod_obra='' OR cod_obra is null)";



            if($var2==null){$var1 = strtoupper_sisap($var1);

					$this->Session->write('pista', $var1);
					$ordena = " beneficiario, fecha_documento, numero_documento";
					 $sql .="  and (numero_documento::text = '".$var1."'  or ";
					$Tfilas=$this->cepd01_compromiso_cuerpo->findCount($sql."  (".$this->busca_separado(array("beneficiario"), $var1).")      )    ");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->cepd01_compromiso_cuerpo->findAll($sql."  (".$this->busca_separado(array("beneficiario"), $var1)."))   ",null,$ordena." ASC",100,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }

            }else{

						$var22  = $this->Session->read('pista');
						$var22  = strtoupper_sisap($var22);
						$ordena = " beneficiario, fecha_documento, numero_documento";
						 $sql  .= "   and (numero_documento::text='".$var22."'  or ";
						$Tfilas=$this->cepd01_compromiso_cuerpo->findCount($sql."   (".$this->busca_separado(array("beneficiario"), $var22)."))   ");

						        if($Tfilas!=0){
						        	$pagina=$var2;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->cepd01_compromiso_cuerpo->findAll($sql."  (".$this->busca_separado(array("beneficiario"), $var22)."))   ",null,$ordena." ASC",100,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }

                 }//fin else










}//fin function















function index($var=null){///////////////<<--INDEX

$this->verifica_entrada('78');

	 $this->layout = "ajax";
	 $ano=$this->ano_ejecucion();

      $maxi=$this->cepd01_compromiso_numero->findCount($this->SQLCA()." and ano_compromiso=".$ano." and situacion=1");
      //$max=$this->cepd01_compromiso_numero->execute("SELECT numero_compromiso FROM cepd01_compromiso_numero WHERE ".$this->SQLCA()."  ORDER BY numero_compromiso ASC LIMIT 1");
      if($maxi==0){
         $this->set("errorMessage","Verifique el n&uacute;mero de control de compromisos");
      	 $this->set("numero_compromiso","");
      	 $this->redirect("/cepp01_compromiso_numero/");
      }
      if(isset($_SESSION["MSJ"])){
					$a=$_SESSION["MSJ"];
					if($a["tipo_msj"]=="exito")$this->set('Message_existe', $a["msj"]);
					else if($a["tipo_msj"]=="error")$this->set('errorMessage', $a["msj"]);
					$this->Session->delete("MSJ");
	  }
}//fin index



function index2($var=null){///////////////<<--INDEX 2
	 $this->layout = "ajax";
	 $this->borrar_cugd04();
	 $ano=$this->ano_ejecucion();
     $dato=$this->ano_ejecucion();
	 if(isset($var)){
	 	$this->set("mostrar",true);
	 	echo '<script>' .
					 'document.getElementById("concepto").value="";' .
					 'document.getElementById("nombre_id_select").options[0].selected=true;' .
					 '</script>';
	 }
	 $condicion_dir_sup = "cod_tipo_institucion = ".$this->verifica_SS(3)." and cod_institucion = ".$this->verifica_SS(4)." and cod_dependencia=".$this->verifica_SS(5);


		//$tipo_documento = $this->cepd01_tipo_compromiso->generateList(null,'cod_tipo_compromiso ASC', null, '{n}.cepd01_tipo_compromiso.cod_tipo_compromiso', '{n}.cepd01_tipo_compromiso.denominacion');
		//$tipo_documento = $tipo_documento != null ? $tipo_documento : array();
		//$this->concatena($tipo_documento, 'tipo');
		    $rs=$this->cepd01_tipo_compromiso->execute("SELECT cod_tipo_compromiso,denominacion,sujeto_retencion FROM cepd01_tipo_compromiso ORDER BY cod_tipo_compromiso ASC");
		    foreach($rs as $l){
				$v[]=$l[0]["cod_tipo_compromiso"];
				$retiene=$l[0]["sujeto_retencion"]==1?" <<SUJETO A RETENCION>>":"";
				$d[]=$l[0]["denominacion"].$retiene;
			}
			if(isset($v) && count($v)!=0){
				$lista = array_combine($v, $d);
			}else{
				$v[]="";
				$lista = array_combine($v, $v);
			}
			$this->concatena($lista, 'tipo');
		$dir_sup=$this->cugd02_direccionsuperior->generateList($condicion_dir_sup,'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
		$dir_sup = $dir_sup != null ? $dir_sup : array();
		$this->concatena($dir_sup, 'dir_superior');

	// *** DIRECCION SUPERIOR ***
	if($dir_sup!=null){
		foreach($dir_sup as $p => $aux_cs){
			$codigo_ds = $p;
			break;
		}
		$this->set('seleccion_ds',$codigo_ds);
		$this->Session->write('cod_1',$codigo_ds);
		$this->Session->write('dirsup',$codigo_ds);
			echo "<script>
					document.getElementById('select_1').value='$aux_cs';
					document.getElementById('codigo_select_1').innerHTML='".$this->zero($codigo_ds)."';
					document.getElementById('deno_select_1').innerHTML='".strtoupper($aux_cs)."';
				</script>";

	// *** COORDINACION ****
		$lista = $this->cugd02_coordinacion->generateList($condicion_dir_sup." and cod_dir_superior=".$codigo_ds, 'cod_coordinacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
    	if($lista!=null){
          	$this->concatena($lista, 'vector_coord');
			foreach($lista as $p => $aux_cs){
				$codigo_ds1 = $p;
				break;
			}
			$this->set('seleccion_ds1',$codigo_ds1);
			$this->Session->write('cod_2',$codigo_ds1);
			$this->Session->write('coor',$codigo_ds1);
			echo "<script>
					document.getElementById('select_2').value='$aux_cs';
					document.getElementById('codigo_select_2').innerHTML='".$this->zero($codigo_ds1)."';
					document.getElementById('deno_select_2').innerHTML='".strtoupper($aux_cs)."';
				</script>";

	// *** SECRETARIA ****
		  $lista = $this->cugd02_secretaria->generateList($condicion_dir_sup." and cod_dir_superior=".$codigo_ds." and cod_coordinacion=".$codigo_ds1, 'cod_secretaria ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector_sec');
			foreach($lista as $p => $aux_cs){
				$codigo_ds2 = $p;
				break;
			}
			$this->set('seleccion_ds2',$codigo_ds2);
			$this->Session->write('cod_3',$codigo_ds2);
			$this->Session->write('secr',$codigo_ds2);
			echo "<script>
					document.getElementById('select_3').value='$aux_cs';
					document.getElementById('codigo_select_3').innerHTML='".$this->zero($codigo_ds2)."';
					document.getElementById('deno_select_3').innerHTML='".strtoupper($aux_cs)."';
				</script>";

			// *** DIRECCION ***
				$lista = $this->cugd02_direccion->generateList($condicion_dir_sup." and cod_dir_superior=".$codigo_ds." and cod_coordinacion=".$codigo_ds1." and cod_secretaria=".$codigo_ds2, 'cod_direccion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
          		if($lista!=null){
          			$this->concatena($lista, 'vector_direcc');
          		}else{
          			$this->set('vector_direcc',array());
          		}

          }else{
          	$this->set('vector_sec',array());
          	$this->Session->write('cod_3',0);
          	$this->Session->write('secr',0);
          }

		}else{
			$this->set('vector_coord',array());
			$this->Session->write('cod_2',0);
			$this->Session->write('coor',0);
			$this->Session->write('cod_3',0);
			$this->Session->write('secr',0);
		}

	}else{
		$this->Session->write('cod_1',0);
		$this->Session->write('dirsup',0);
		$this->Session->write('cod_2',0);
		$this->Session->write('coor',0);
		$this->Session->write('cod_3',0);
		$this->Session->write('secr',0);
	}

		$DV=$this->cfpd02_sector->execute("SELECT ano FROM cfpd02_sector WHERE ".$this->SQLCA()." GROUP BY ano");
		foreach($DV as $dv){
			foreach($dv as $d)
				 $ve_ano[$d['ano']]=$d['ano'];
			}
	  $this->set('anos',$ano);
      $this->Session->delete("ano");
      $this->Session->delete("sec");
      $this->Session->delete("prog");
      $this->Session->delete("subp");
      $this->Session->delete("proy");
      $this->Session->delete("actividad");
      $this->Session->delete("cpar");
      $this->Session->delete("cgen");
      $this->Session->delete("cesp");
      $this->Session->delete("csesp");
      $this->Session->delete("auxiliar");
      $maxi=$this->cepd01_compromiso_numero->findCount($this->SQLCA());
      $max=$this->cepd01_compromiso_numero->execute("SELECT numero_compromiso FROM cepd01_compromiso_numero WHERE ".$this->SQLCA()." and ano_compromiso=".$dato." and situacion=1 ORDER BY numero_compromiso ASC LIMIT 1");



      $numero_documento_anterior  = $this->cepd01_compromiso_numero->field('cepd01_compromiso_numero.numero_compromiso',   $conditions = $this->condicion()." and ano_compromiso='$dato' and situacion=3 and numero_compromiso<='".$max[0][0]["numero_compromiso"]."'",                                         $order ="cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_compromiso DESC");
      if($numero_documento_anterior!=null){
      	$fecha_documento_anterior   = $this->cepd01_compromiso_cuerpo->field('cepd01_compromiso_cuerpo.fecha_documento',     $conditions = $this->condicion()." and ano_documento='$dato' and numero_documento='".$numero_documento_anterior."' ",   $order ="cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_documento DESC");
      	$this->set("fecha_documento_anterior",  $fecha_documento_anterior);
      	$this->set("numero_documento_anterior", $numero_documento_anterior);
      }else{
      	$this->set("fecha_documento_anterior",  0);
      	$this->set("numero_documento_anterior", 0);
      }

      if($max!=null){
      	    $codigo=$max[0][0]["numero_compromiso"];
            $resultado=$this->cepd01_compromiso_numero->execute("UPDATE  cepd01_compromiso_numero SET situacion=2 WHERE ".$this->SQLCA()." and numero_compromiso=".$codigo." and ano_compromiso=".$dato);
	         if($resultado>1){
               // $this->set("Message_existe","Situacion del compromiso actualizada con exito");
               echo "<script>" .
               		"habilita_compromiso();" .
               		"</script>";

               $this->set("numero_compromiso",$codigo);
	         }else{
		        $this->set("errorMessage","Por favor Verifique el n&uacute;mero de control de compromisos");
		        $this->set("numero_compromiso","");
		        $this->redirect("/cepp01_compromiso_numero/");
	      }
      }else{
      	 $this->set("errorMessage","Verifique el n&uacute;mero de control de compromisos");
      	 $this->set("numero_compromiso","");
      	 $this->redirect("/cepp01_compromiso_numero/");
      }

      if(isset($var)){
      	  if($var==true) $this->set("Message_existe","El Compromiso fue registrado exitosamente");
      	  else $this->set("errorMessage","Disculpe, El Compromiso no fue registrado");
     }//fin isset var
    $this->Session->delete("items");
	$this->Session->delete("i");
	$this->Session->delete("contador");


}//fin index




function select($select=null,$var=null) { //select de ubicacion administrativa
	$this->layout = "ajax";
	/**
	 * cod_1 : direccion superior
	 * cod_2 : coordinacion
	 * cod_3 : secretaria
	 * cod_4 : direccion
	 * cod_5 : division
	 * cod_6 : departamento
	 */
if(isset($var) && !empty($var) && $var!=''){
		$cond ="cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$this->verifica_SS(5);
	switch($select){
		case 'coordinacion':
			$this->set('SELECT','secretaria');
			$this->set('codigo','coordinacion');
			$this->set('seleccion','');
			$this->set('n',2);
			$this->Session->write('cod_1',$var);
			$cond .=" and cod_dir_superior=".$var;
			$lista=  $this->cugd02_coordinacion->generateList($cond, 'cod_coordinacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
					$this->concatena($lista, 'vector');
		break;
		case 'secretaria':
			$this->set('SELECT','direccion');
			$this->set('codigo','secretaria');
			$this->set('seleccion','');
			$this->set('n',3);
			$cod_1 =  $this->Session->read('cod_1');
			$this->Session->write('cod_2',$var);
			$cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$var;
			$lista=  $this->cugd02_secretaria->generateList($cond, 'cod_secretaria ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
					$this->concatena($lista, 'vector');
		break;
		case 'direccion':
			$this->set('SELECT','catalogo');
			$this->set('codigo','direccion');
			$this->set('seleccion','');
			 $this->set('n',4);
			 //$this->set('no','no');
			$cod_1 =  $this->Session->read('cod_1');
			$cod_2 =  $this->Session->read('cod_2');
			$this->Session->write('cod_3',$var);
			$cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$var;
			$lista=  $this->cugd02_direccion->generateList($cond, 'cod_direccion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
					$this->concatena($lista, 'vector');
		break;
		case 'catalogo':
			$cod_1 =  $this->Session->read('cod_1');//dir sup
			$cod_2 =  $this->Session->read('cod_2');//coor
			$cod_3 =  $this->Session->read('cod_3');//secre
			$cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$cod_3." and cod_direccion=".$var;
			$resultado=$this->cugd02_direccion->findAll($cond,array('cod_sector','cod_programa','cod_sub_prog','cod_proyecto'));
			//print_r($resultado);
			$this->Session->write('CodigosDireccion',$resultado);
			$this->set('SELECT','catalogo');
			$this->set('codigo','catalogo');
			$this->set('seleccion','');
			$this->set('n',5);
			$this->set('no','no');
			$this->set('otro','otro');
		//	$lista=  $this->cscd01_catalogo->generateList(null, 'codigo_prod_serv ASC', null, '{n}.cscd01_catalogo.codigo_prod_serv', '{n}.cscd01_catalogo.denominacion');
		   // $this->concatena($lista, 'vector');
    $codigos_direccion=$this->Session->read('CodigosDireccion');
 	$cod_sector=$codigos_direccion[0]["cugd02_direccion"]["cod_sector"];
 	$cod_programa=$codigos_direccion[0]["cugd02_direccion"]["cod_programa"];
 	$cod_sub_prog=$codigos_direccion[0]["cugd02_direccion"]["cod_sub_prog"];
 	$cod_proyecto=$codigos_direccion[0]["cugd02_direccion"]["cod_proyecto"];
 	$catalogo= $this->v_cscd01_catalogo_deno_und->generateList($this->condicion()." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto, 'cod_snc ASC', null, '{n}.v_cscd01_catalogo_deno_und.codigo_prod_serv', '{n}.v_cscd01_catalogo_deno_und.denominacion');
 		$this->concatena($catalogo, 'vector');


		break;
	}
	}else{
		echo "";
	}
}//fin select ubicacion administrativa

function mostrar($select=null,$var=null) {
		$this->layout = "ajax";
if(isset($var) && !empty($var) && $var!=''){
	//$dirsup =  $this->Session->read('dirsup');
	$cond = "cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$this->verifica_SS(5);
		switch($select){
			case 'dirsuperior':
			$this->Session->write('dirsup',$var);
			$cond .=" and cod_dir_superior=".$var;
			$a=  $this->cugd02_direccionsuperior->findAll($cond);
				$x= $a[0]['cugd02_direccionsuperior']['denominacion'];
				$this->set("deno",$x);
 		break;
		case 'coordinacion':
			$dirsup= $this->Session->read('dirsup');
			$this->Session->write('coor',$var);
			$cond .=" and cod_dir_superior=".$dirsup." and cod_coordinacion=".$var;
			$a=  $this->cugd02_coordinacion->findAll($cond);
				$x= $a[0]['cugd02_coordinacion']['denominacion'];
				$this->set("deno",$x);
 		break;
		case 'secretaria':
			$dirsup= $this->Session->read('dirsup');
			$coor =  $this->Session->read('coor');
			$this->Session->write('secr',$var);
			$cond .=" and cod_dir_superior=".$dirsup." and cod_coordinacion=".$coor." and cod_secretaria=".$var;
			$a=  $this->cugd02_secretaria->findAll($cond);
				$x= $a[0]['cugd02_secretaria']['denominacion'];
				$this->set("deno",$x);
 		break;
		case 'direccion':
			$dirsup= $this->Session->read('dirsup');
			$coor =  $this->Session->read('coor');
			$secr =  $this->Session->read('secr');
			$this->Session->write('dir',$var);
			$cond .=" and cod_dir_superior=".$dirsup." and cod_coordinacion=".$coor." and  cod_secretaria=".$secr." and cod_direccion=".$var;
			$a=  $this->cugd02_direccion->findAll($cond);
				$x= $a[0]['cugd02_direccion']['denominacion'];
				$this->set("deno",$x);
				$cod_sector   = mascara($a[0]['cugd02_direccion']['cod_sector'],2);
				$cod_programa = mascara($a[0]['cugd02_direccion']['cod_programa'],2);
				$cod_sub_prog = mascara($a[0]['cugd02_direccion']['cod_sub_prog'],2);
				$cod_proyecto = mascara($a[0]['cugd02_direccion']['cod_proyecto'],2);
				$aux          = $cod_sector.".".$cod_programa.".".$cod_sub_prog.".".$cod_proyecto;
				echo"<script>$('partida_producto').innerHTML='".$aux."'</script>";
			break;
			case 'catalogo':
		        $a=  $this->cscd01_catalogo->findAll("codigo_prod_serv=".$var,array('denominacion'));
				$x= $a[0]['cscd01_catalogo']['denominacion'];
				$this->set("deno",$x);
			break;
	}//fin switch
		}else{
			echo "";
			$this->set("deno","");
		}
//$oart=$var<9?CE."0".$var:CE.$var;
}//fin mostrar cod dir superior

function mostrarcodigo($select=null,$var=null) {
		$this->layout = "ajax";
if(isset($var) && !empty($var) && $var!=''){
			switch($select){
			case 'dirsuperior':
					$this->set("codigo",$var);
 		break;
		case 'coordinacion':
			 $this->set("codigo",$var);
 		break;
		case 'secretaria':
			 $this->set("codigo",$var);
 		break;
		case 'direccion':
			 $this->set("codigo",$var);
			break;
			case 'catalogo':
			     $a=  $this->cscd01_catalogo->findAll("codigo_prod_serv=".$var);
				 $x= $a[0]['cscd01_catalogo']['cod_snc'];
				 $this->set("codigo",$x);
				 $this->set("catalogo","si");
				 break;
	}//fin switch
		}else{
			echo "";
			 $this->set("codigo","");
		}

}//fin mostrar los codigos de los select



function select3($select=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";
if($select!=null && $var!=null){
		$cond =$this->SQLCA();
	switch($select){
		case 'sector':
			$this->set('SELECT','programa');
			$this->set('codigo','sector');
			$this->set('seleccion','');
			$this->set('n',1);
			$this->Session->write('ano',$var);
			$cond .=" and ano=".$var;
			//$lista=  $this->cfpd02_sector->generateList($cond, 'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.denominacion');
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_sector ASC', null, '{n}.v_cfpd05_denominaciones.cod_sector', '{n}.v_cfpd05_denominaciones.deno_sector');
			$this->concatena($lista, 'vector');
		break;
		case 'programa':

			$this->set('SELECT','subprograma');
			$this->set('codigo','programa');
			$this->set('seleccion','');
			$this->set('n',2);
			$ano=$this->ano_ejecucion();
			$this->Session->write('ano',$ano);
			$this->Session->write('sec',$var);
			$cond .=" and ano=".$ano." and cod_sector=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_programa ASC', null, '{n}.v_cfpd05_denominaciones.cod_programa', '{n}.v_cfpd05_denominaciones.deno_programa');
					$this->concatena($lista, 'vector');
		break;
		case 'subprograma':
			$this->set('SELECT','proyecto');
			$this->set('codigo','subprograma');
			$this->set('seleccion','');
			$this->set('n',3);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$this->Session->write('prog',$var);
			$cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_sub_prog ASC', null, '{n}.v_cfpd05_denominaciones.cod_sub_prog', '{n}.v_cfpd05_denominaciones.deno_sub_prog');
					$this->concatena($lista, 'vector');
		break;
		case 'proyecto':
			$this->set('SELECT','actividad');
			$this->set('codigo','proyecto');
			$this->set('seleccion','');
			 $this->set('n',4);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$this->Session->write('subp',$var);
			$cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_proyecto ASC', null, '{n}.v_cfpd05_denominaciones.cod_proyecto', '{n}.v_cfpd05_denominaciones.deno_proyecto');
					$this->concatena($lista, 'vector');
		break;
		case 'actividad':
			$this->set('SELECT','partida');
			$this->set('codigo','actividad');
			$this->set('seleccion','');
			$this->set('n',5);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$this->Session->write('proy',$var);
			$cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_activ_obra ASC', null, '{n}.v_cfpd05_denominaciones.cod_activ_obra', '{n}.v_cfpd05_denominaciones.deno_activ_obra');
			$this->concatena($lista, 'vector');
		break;
		case 'partida':
			$this->set('SELECT','generica');
			$this->set('codigo','partida');
			$this->set('seleccion','');
			$this->set('n',6);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy =  $this->Session->read('proy');
			$this->Session->write('actividad',$var);
			$cond2 =$cond." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_partida ASC', null, '{n}.v_cfpd05_denominaciones.cod_partida', '{n}.v_cfpd05_denominaciones.deno_partida');
			$this->concatena($lista, 'vector');

		break;
		case 'generica':
			$this->set('SELECT','especifica');
			$this->set('codigo','generica');
			$this->set('seleccion','');
			$this->set('n',7);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy =  $this->Session->read('proy');
			$activ=$this->Session->read('actividad');
			$this->Session->write('cpar',$var);
			$cond2 =$cond." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$activ." and cod_partida=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_generica ASC', null, '{n}.v_cfpd05_denominaciones.cod_generica', '{n}.v_cfpd05_denominaciones.deno_generica');
					$this->concatena($lista, 'vector');
 		break;
		case 'especifica':
			$this->set('SELECT','subespecifica');
			$this->set('codigo','especifica');
			$this->set('seleccion','');
			$this->set('n',8);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy =  $this->Session->read('proy');
			$activ=$this->Session->read('actividad');
			$cpar =  $this->Session->read('cpar');
			$this->Session->write('cgen',$var);
			$cond2 =$cond." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$activ." and cod_partida=".$cpar." and cod_generica=".$var;
			$lista = $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_especifica ASC', null, '{n}.v_cfpd05_denominaciones.cod_especifica', '{n}.v_cfpd05_denominaciones.deno_especifica');
					$this->concatena($lista, 'vector');
		break;
		case 'subespecifica':
			$this->set('SELECT','auxiliar');
			$this->set('codigo','subespecifica');
			$this->set('seleccion','');
			$this->set('n',9);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy =  $this->Session->read('proy');
			$activ=$this->Session->read('actividad');
			$cpar =  $this->Session->read('cpar');
			$cgen =  $this->Session->read('cgen');
			$this->Session->write('cesp',$var);
			$cond2 =$cond." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$activ." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_sub_espec ASC', null, '{n}.v_cfpd05_denominaciones.cod_sub_espec', '{n}.v_cfpd05_denominaciones.deno_sub_espec');
		    $this->concatena($lista, 'vector');
		break;
		case 'auxiliar':

			$this->set('SELECT','escribir_aux');
			$this->set('codigo','auxiliar');
			$this->set('seleccion','');
			$this->set('n',10);
			//$this->set('no','no');
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy =  $this->Session->read('proy');
			$activ=$this->Session->read('actividad');
			$cpar =  $this->Session->read('cpar');
			$cgen =  $this->Session->read('cgen');
			$cesp =  $this->Session->read('cesp');
			$this->Session->write('csesp',$var);
			//$cpar=$cpar<9 ? "40".$cpar  : "4".$cpar;
			//$cond2 ="ano=".$ano." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$var;
			//echo "AUX1".$cond2;
			$cond2 =$cond." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$activ." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$var;
			//echo $cond2;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond." and ".$cond2, 'cod_auxiliar ASC', null, '{n}.v_cfpd05_denominaciones.cod_auxiliar', '{n}.v_cfpd05_denominaciones.deno_auxiliar');
						if($lista!=null){
							$this->concatena($lista, 'vector');
						}else{
							$this->set('vector',array('0'=>'00'));

						}
         //echo "muestra";
		break;
		case 'auxiliar2':
		 //echo "hola auxiliar 2";
			$this->set('SELECT','escribir_aux');
			$this->set('codigo','auxiliar');
			$this->set('seleccion','');
			$this->set('n',10);
			//$this->set('no','no');
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy =  $this->Session->read('proy');
			//$activ=$this->Session->read('actividad');
			$cpar =  $this->Session->read('cpar');
			$cgen =  $this->Session->read('cgen');
			$cesp =  $this->Session->read('cesp');
			$this->Session->write('actividad',$var);
			$f=$this->Session->read('CodigosDireccion');
			$p=$this->Session->read('partidas');
			 //print_r($p);

			$cond2 =$cond." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$var." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$p[0]["cscd01_catalogo"]["cod_sub_espec"];
			//echo $cond2;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_auxiliar ASC', null, '{n}.v_cfpd05_denominaciones.cod_auxiliar', '{n}.v_cfpd05_denominaciones.deno_auxiliar');
			/**			if($lista!=null){
							$this->AddCero('vector',$lista);
						}else{
							$this->set('vector',array('0'=>'00'));
						}*/
						if($lista!=null){
							$this->concatena($lista, 'vector');
							//echo count($lista);
						}else{
							$this->set('vector',array('0'=>'00'));
							//echo "cero";
							$disponibilidad = $this->disponibilidad($_SESSION["ano"], $_SESSION["sec"], $_SESSION["prog"], $_SESSION["subp"], $_SESSION["proy"], $_SESSION["actividad"], $_SESSION["cpar"], $_SESSION["cgen"], $_SESSION["cesp"], $_SESSION["csesp"], 0);

				       echo "<script>" .
				 		"document.getElementById('td_disponibilidad').innerHTML='".$this->Formato2($disponibilidad)."'; " .
				 		"</script>";
						}
		break;
		case 'escribir_aux':
       /// echo "saaaaaaaaaaa";
				 $this->Session->write('auxiliar',$var);
				 $disponibilidad = $this->disponibilidad($_SESSION["ano"], $_SESSION["sec"], $_SESSION["prog"], $_SESSION["subp"], $_SESSION["proy"], $_SESSION["actividad"], $_SESSION["cpar"], $_SESSION["cgen"], $_SESSION["cesp"], $_SESSION["csesp"], $_SESSION["auxiliar"]);

				 echo "<script>" .
				 		"document.getElementById('td_disponibilidad').innerHTML='".$this->Formato2($disponibilidad)."';" .
				 		"</script>";
				 $this->set("ocultar",true);
		break;
	}//fin wsitch
	}else{
			$this->set('SELECT','');
			$this->set('codigo','');
			$this->set('seleccion','');
			$this->set('n',12);
			$this->set('no','no');
		 $this->set('vector',array('0'=>'00'));
	}
}//fin select codigos presupuestarios


function num_auto ($var=null,$ano=2008) {
	$this->layout="ajax";
	echo '<script>' .
			'habilita_compromiso();' .
			'</script>';
	if(isset($var) && $var==1){
		//buscar para que el codigo sea automatico
		$v=$this->cepd01_compromiso_numero->execute("SELECT numero_compromiso FROM cepd01_compromiso_numero WHERE ".$this->SQLCA()." and ano_compromiso=".date("Y")." ORDER BY numero_compromiso DESC");
		//print_r($v);
		if($v!=null){
			$numero=$v[0][0]["numero_compromiso"];
			$numero = $numero =="" ? 1 : $numero+1;
		}else{
			$numero=1;
		}
	}else{
		$numero="";
	}
		$this->set("numero",$numero);
}


function agregar_partidas($var=null) {
	$this->layout="ajax";
	if(isset($_SESSION["contador"])){
        $_SESSION["contador"]=$_SESSION["contador"]+1;
	}else{
		$_SESSION["contador"]=1;
	}
	if(isset($var) && !empty($var)){
            $cod[0]=$this->data["cepp01_compromiso_partidas"]["ano_partidas"];
			$cod[1]=$this->data["cepp01_compromiso_partidas"]["cod_sector"];
			$cod[2]=$this->data["cepp01_compromiso_partidas"]["cod_programa"];
			$cod[3]=$this->data["cepp01_compromiso_partidas"]["cod_subprograma"];
			$cod[4]=$this->data["cepp01_compromiso_partidas"]["cod_proyecto"];
			$cod[5]=$this->data["cepp01_compromiso_partidas"]["cod_actividad"];
			$cod[6]=$this->data["cepp01_compromiso_partidas"]["cod_partida"];
			if($cod[6]<9){
				$cod[6]="40".$cod[6];
			}else if($cod[6]<100){
				$cod[6]="4".$cod[6];
			}else{
				$cod[6]=$cod[6];
			}

			$cod[7]=$this->data["cepp01_compromiso_partidas"]["cod_generica"];
			$cod[8]=$this->data["cepp01_compromiso_partidas"]["cod_especifica"];
			$cod[9]=$this->data["cepp01_compromiso_partidas"]["cod_subespecifica"];
			$cod[10]=$this->data["cepp01_compromiso_partidas"]["cod_auxiliar"];//
			$cod[10]=$cod[10]<9?str_replace("0","",$cod[10]):$cod[10];
			$cod[10]=$cod[10]<9?"0".$cod[10]:$cod[10];
			$cod[11]=$this->data["cepp01_compromiso_partidas"]["monto_partidas"];
		    if(isset($_SESSION["i"])){
			$i=$this->Session->read("i")+1;
			$this->Session->write("i",$i);
	    }else{
		   $this->Session->write("i",0);
			$i=0;
		}
        switch($var){
        	case 'normal':
					 $vec[$i][0]=$this->data["cepp01_compromiso_partidas"]["ano_partidas"];
					 $vec[$i][1]=$this->data["cepp01_compromiso_partidas"]["cod_sector"];
					 $vec[$i][2]=$this->data["cepp01_compromiso_partidas"]["cod_programa"];
					 $vec[$i][3]=$this->data["cepp01_compromiso_partidas"]["cod_subprograma"];
					 $vec[$i][4]=$this->data["cepp01_compromiso_partidas"]["cod_proyecto"];
					 $vec[$i][5]=$this->data["cepp01_compromiso_partidas"]["cod_actividad"];
					 $vec[$i][6]=$this->data["cepp01_compromiso_partidas"]["cod_partida"];//<9 ? "4.0".$this->data["cepp01_compromiso_partidas"]["cod_partida"] : "4.".$this->data["cepp01_compromiso_partidas"]["cod_partida"];
					 $vec[$i][7]=$this->data["cepp01_compromiso_partidas"]["cod_generica"];
					 $vec[$i][8]=$this->data["cepp01_compromiso_partidas"]["cod_especifica"];
					 $vec[$i][9]=$this->data["cepp01_compromiso_partidas"]["cod_subespecifica"];
					 $vec[$i][10]=$this->mascara_cuatro($this->data["cepp01_compromiso_partidas"]["cod_auxiliar"]);
					 $vec[$i][11]=$this->data["cepp01_compromiso_partidas"]["monto_partidas"];
					 $vec[$i]["id"]=$i;
					         $disponible_partida = $this->disponibilidad($vec[$i][0], $vec[$i][1], $vec[$i][2], $vec[$i][3], $vec[$i][4], $vec[$i][5], $vec[$i][6],$vec[$i][7], $vec[$i][8], $vec[$i][9],$vec[$i][10]);
					         $monto_partida_array = $this->Formato1($vec[$i][11]);
					if(sprintf("%01.2f",$disponible_partida)>=sprintf("%01.2f",$monto_partida_array)){
						 if(isset($_SESSION["items"])){
							foreach($_SESSION["items"] as $codi){
								//echo $codi[0].$cod[0].$codi[1].$cod[1].$codi[2].$cod[2].$codi[3].$cod[3].$codi[4].$cod[4].$codi[5].$cod[5].$codi[6].$cod[6].$codi[7].$cod[7]. $codi[8].$cod[8].$codi[9].$cod[9].$codi[10].$cod[10];
	            	           if($codi[0]==$cod[0] && $codi[1]==$cod[1] && $codi[2]==$cod[2] && $codi[3]==$cod[3] && $codi[4]==$cod[4] && $codi[5]==$cod[5] && $codi[6]==$cod[6] && $codi[7]==$cod[7] && $codi[8]==$cod[8] && $codi[9]==$cod[9] && $codi[10]==$cod[10]){
	                              $est=true;
	                              break;
	            	          }else{
	            	          	 $est=false;
	            	          }
	                        }//fin foreach
	                        if($est==true){
	                           //	echo "no";
	                            $_SESSION["contador"]=$_SESSION["contador"]-1;
	            	          	$i=$this->Session->read("i")-1;
					            $this->Session->write("i",$i);
					            $this->set('errorMessage', 'Los codigos seleccionados ya existen en la lista');
	                        }else{
	                        	$_SESSION["items"]=$_SESSION["items"]+$vec;
	                          //  echo "si";
	                        }
						 }else{
							$_SESSION["items"]=$vec;
						 }
					}else{
						        $_SESSION["contador"]=$_SESSION["contador"]-1;
						        $iJ=$this->Session->read("i")-1;
						        $i=$iJ>=0?0:$iJ;
					            $this->Session->write("i",$i);
					            $this->set('errorMessage', 'Disculpe, Esta partida solo cuenta con Bs. '.$this->Formato2($disponible_partida));
					}

        	break;
        	case 'nuevos':
                     $vec[$i][0]=$cod[0];
					 $vec[$i][1]=$this->AddCeroR($cod[1]);
					 $vec[$i][2]=$this->AddCeroR($cod[2]);
					 $vec[$i][3]=$this->AddCeroR($cod[3]);
					 $vec[$i][4]=$this->AddCeroR($cod[4]);
					 $vec[$i][5]=$this->AddCeroR($cod[5]);
					 $vec[$i][6]=$cod[6];
					 $vec[$i][7]=$this->AddCeroR($cod[7]);
					 $vec[$i][8]=$this->AddCeroR($cod[8]);
					 $vec[$i][9]=$this->AddCeroR($cod[9]);
					 $vec[$i][10]=$this->mascara_cuatro($cod[10]);
					 $vec[$i][11]=$cod[11];
					 $vec[$i]["id"]=$i;
					 $disponible_partida = $this->disponibilidad($vec[$i][0], $vec[$i][1], $vec[$i][2], $vec[$i][3], $vec[$i][4], $vec[$i][5], $vec[$i][6],$vec[$i][7], $vec[$i][8], $vec[$i][9],$vec[$i][10]);
					 $monto_partida_array = $this->Formato1($vec[$i][11]);
					if(sprintf("%01.2f",$disponible_partida)>=sprintf("%01.2f",$monto_partida_array)){
					 if(isset($_SESSION["items"])){
						foreach($_SESSION["items"] as $codi){
							//echo $codi[0].$cod[0].$codi[1].$cod[1].$codi[2].$cod[2].$codi[3].$cod[3].$codi[4].$cod[4].$codi[5].$cod[5].$codi[6].$cod[6].$codi[7].$cod[7]. $codi[8].$cod[8].$codi[9].$cod[9].$codi[10].$cod[10];
            	           if($codi[0]==$cod[0] && $codi[1]==$cod[1] && $codi[2]==$cod[2] && $codi[3]==$cod[3] && $codi[4]==$cod[4] && $codi[5]==$cod[5] && $codi[6]==$cod[6] && $codi[7]==$cod[7] && $codi[8]==$cod[8] && $codi[9]==$cod[9] && $codi[10]==$cod[10]){
                              $est=true;
                              break;
            	          }else{
            	          	 $est=false;
            	          }
                        }//fin foreach
                        if($est==true){
                           //	echo "no";
            	          	$i=$this->Session->read("i")-1;
				            $this->Session->write("i",$i);
				            $this->set('errorMessage', 'Los codigos seleccionados ya existen en la lista');
                        }else{
                        	$_SESSION["items"]=$_SESSION["items"]+$vec;
                          //  echo "si";
                        }
					 }else{
						$_SESSION["items"]=$vec;
					 }
					 }else{
						        $_SESSION["contador"]=$_SESSION["contador"]-1;
						        $iJ=$this->Session->read("i")-1;
						        $i=$iJ>=0?0:$iJ;
					            $this->Session->write("i",$i);
					            $this->set('errorMessage', 'Disculpe, Esta partida solo cuenta con Bs. '.$this->Formato2($disponible_partida));
					}
        	break;

        }//fin switch
		}//

}//fin funcu¡ions

function eliminar_items ($id) {
	$this->layout = "ajax";
	$_SESSION["items"][$id]=null;
	$monto_total=0;
	foreach($_SESSION ["items"] as $codigos){
       $monto_total=$monto_total+$this->Formato1($codigos[11]);
	}
	$this->set('total_partidas_rc',$monto_total);
    $_SESSION["contador"]=$_SESSION["contador"]-1;
}

function limpiar_lista () {
	$this->layout = "ajax";
	$this->Session->delete("items");
	$this->Session->delete("i");
	$this->Session->delete("contador");
}

function Formato1($monto) {
		$monto = preg_replace("/[^0-9\.]/", "", str_replace(',','.',$monto));
		if (substr($monto,-3,1)=='.') {
				$sents = '.'.substr($monto,-2);
				$monto = substr($monto,0,strlen($monto)-3);
		} elseif (substr($monto,-2,1)=='.') {
				$sents = '.'.substr($monto,-1);
				$monto = substr($monto,0,strlen($monto)-2);
		} else {
				$sents = '.00';
		}
		$monto = preg_replace("/[^0-9]/", "", $monto);
		return number_format($monto.$sents,2,'.','');
		}

function Formato2($monto){
			return number_format($monto,2,",",".");
}

function guardar($pagina=null){
	$this->layout="ajax";
	$cod_presi=$this->Session->read('SScodpresi');
	$cod_entidad=$this->Session->read('SScodentidad');
	$cod_tipo_inst=$this->Session->read('SScodtipoinst');
	$cod_inst=$this->Session->read('SScodinst');

	if(isset($this->data["cepp01_compromiso"])){
			 $ano=$this->data["cepp01_compromiso"]["ano"];
			 $numero_documento=$this->data["cepp01_compromiso"]["numero_compromiso"];
			 $tipo_documento=$this->data["cepp01_compromiso"]["tipo_compromiso"];
			 $fecha_documento=$this->data["cepp01_compromiso"]["fecha_documento"];
			 $tipo_recurso=$this->data["cepp01_compromiso"]["tipo_recurso"];
			 $condicion_juridica=$this->data["cepp01_compromiso"]["condicion_juridica"];
			 $concepto=$this->data["cepp01_compromiso"]["concepto"];
			 $beneficiario=$this->data["cepp01_compromiso"]["beneficiario"];
			 $concepto="BENEFICIARIO: ".$beneficiario." POR CONCEPTO DE: ".$concepto;
			 $condicion_documento=1;//cuando se guarda es Activo=1
			 $num_asiento=0;
			 $fecha_proceso_registro=date("Y-m-d");
			 $fecha_proceso_anulacion="1900-01-01";
			 $username=$this->Session->read('nom_usuario');

			 if(isset($this->data["cepp01_compromiso"]["rif"]) && $this->data["cepp01_compromiso"]["rif"]!="0"){
			 	$rif=$this->data["cepp01_compromiso"]["rif"]==""?0:$this->data["cepp01_compromiso"]["rif"];
                $rif=strtoupper($rif);
                $cedula="0";
			 	$cant_br=$this->cepd01_compromiso_beneficiario_rif->findCount("upper(rif)='".$rif."'");

                  if($cant_br==0){
                  	$Y=$this->cepd01_compromiso_beneficiario_rif->execute("INSERT INTO cepd01_compromiso_beneficiario_rif VALUES ('".$rif."','".$beneficiario."')");
                  }
                  //echo "PASO 1";
			 }else{
			 	$rif="0";
			 	if(isset($this->data["cepp01_compromiso"]["cedula"]) && $this->data["cepp01_compromiso"]["cedula"]!="0"){
			 	$cedula=$this->data["cepp01_compromiso"]["cedula"]==""?"0":$this->data["cepp01_compromiso"]["cedula"];
			 	$cant_bc=$this->cepd01_compromiso_beneficiario_cedula->findCount("cedula='".$cedula."'");
                  if($cant_bc==0){
                  	$z=$this->cepd01_compromiso_beneficiario_cedula->execute("INSERT INTO cepd01_compromiso_beneficiario_cedula VALUES ('".$cedula."','".$beneficiario."')");
                  }
				 }else{
				 	$cedula="0";
				 }
			 }

			 if($rif=="0" && $cedula=="0"){
                    $this->cepd01_compromiso_numero->execute("UPDATE cepd01_compromiso_numero SET situacion=1 WHERE ".$this->SQLCA()." and ano_compromiso=".$ano." and numero_compromiso=".$numero_documento." and (situacion!=3 AND situacion!=4)");
			        $Guardados_exito=false;
			        $MSJ=array("msj"=>"No se puede guardar el Registro falta el rif o cedula","tipo_msj"=>"error");
					$this->Session->write("MSJ",$MSJ);
					$this->borrar_cugd04();
					 $this->Session->delete("items");
				     $this->Session->delete("i");
				     $this->Session->delete("contador");
					 $this->index($Guardados_exito);
					 $this->render("index");
			 }else{
             $camposT2="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_documento,numero_documento,cod_tipo_compromiso,fecha_documento,tipo_recurso,rif,cedula_identidad,cod_dir_superior,cod_coordinacion,cod_secretaria,cod_direccion,concepto,monto,condicion_actividad,dia_asiento_registro,mes_asiento_registro,ano_asiento_registro, numero_asiento_registro,username_registro,ano_anulacion,numero_anulacion,dia_asiento_anulacion,mes_asiento_anulacion,ano_asiento_anulacion,numero_asiento_anulacion,username_anulacion,ano_orden_pago,numero_orden_pago,beneficiario,condicion_juridica,fecha_proceso_registro,fecha_proceso_anulacion";
			 $camposT3="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_documento,numero_documento,ano,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,monto,numero_control_compromiso";
			 $values="";
			 $vec=$_SESSION["items"];
			 $monto=0;
			 $i=0;
             $ann=$ano;
             $C_RC=$this->cepd01_compromiso_cuerpo->findCount($this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$numero_documento);
             $R2=$this->cepd01_compromiso_cuerpo->execute("INSERT INTO cepd01_compromiso_cuerpo (".$camposT2.") VALUES (".$this->SQLCAIN($ano).",".$numero_documento.",".$tipo_documento.",'".$fecha_documento."',".$tipo_recurso.",'".$rif."','".$cedula."',".$this->data["cepp01_compromiso"]["cod_dir_superior"].",".$this->data["cepp01_compromiso"]["cod_coordinacion"].",".$this->data["cepp01_compromiso"]["cod_secretaria"].",".$this->data["cepp01_compromiso"]["cod_direccion"].",'".$concepto."',".$monto.",1,0,0,0,0,'".$username."',0,0,'0',0,0,0,0,0,0,'".$beneficiario."',".$condicion_juridica.",'".$fecha_proceso_registro."','".$fecha_proceso_anulacion."')");

			 if($R2>1 && $C_RC==0){

			foreach($_SESSION["items"] as $nss){
						if($nss!=null){
						   $new_array[$i][0]=$nss[0];
				           $new_array[$i][1]=$nss[1];
				           $new_array[$i][2]=$nss[2];
				           $new_array[$i][3]=$nss[3];
				           $new_array[$i][4]=$nss[4];
				           $new_array[$i][5]=$nss[5];
				           $new_array[$i][6]=$nss[6];
				           $new_array[$i][7]=$nss[7];
				           $new_array[$i][8]=$nss[8];
				           $new_array[$i][9]=$nss[9];
				           $new_array[$i][10]=$nss[10];
				           $new_array[$i][11]=$nss[11];

						$i++;
						}//null
					}//fin foreach
					//$disponibilidad = $this->disponibilidad($ano, $ss["cod_sector"], $ss["cod_programa"], $ss["cod_sub_prog"], $ss["cod_proyecto"],$resultado[0]["cfpd05"]["cod_activ_obra"], $rr["cod_partida"], $rr["cod_generica"], $rr["cod_especifica"], $rr["cod_sub_espec"], $resultado_aux[0]["cfpd05"]["cod_auxiliar"]);
					$contar_no_disp=0;
					$i_no_d=0;
					$_SESSION["partidas_no_disp"]=array();
					$new_array_no_d=array();
					foreach($new_array as $vec){
					     	 $disponible_partida = $this->disponibilidad($vec[0], $vec[1], $vec[2], $vec[3], $vec[4], $vec[5], $vec[6],$vec[7], $vec[8], $vec[9],$vec[10]);
					         $monto_partida_array = $this->Formato1($vec[11]);
					         if(sprintf("%01.2f",$disponible_partida)>=sprintf("%01.2f",$monto_partida_array)){
                                  $contar_no_disp=$contar_no_disp+0;

					         }else{
                                  $contar_no_disp=$contar_no_disp+1;
                                   $new_array_no_d[$i_no_d][0]=$vec[0];
						           $new_array_no_d[$i_no_d][1]=$vec[1];
						           $new_array_no_d[$i_no_d][2]=$vec[2];
						           $new_array_no_d[$i_no_d][3]=$vec[3];
						           $new_array_no_d[$i_no_d][4]=$vec[4];
						           $new_array_no_d[$i_no_d][5]=$vec[5];
						           $new_array_no_d[$i_no_d][6]=$vec[6];
						           $new_array_no_d[$i_no_d][7]=$vec[7];
						           $new_array_no_d[$i_no_d][8]=$vec[8];
						           $new_array_no_d[$i_no_d][9]=$vec[9];
						           $new_array_no_d[$i_no_d][10]=$vec[10];
						           $new_array_no_d[$i_no_d][11]=$this->Formato2($disponible_partida);

								$i_no_d++;
					         }

					}
					$_SESSION["partidas_no_disp"]=$new_array_no_d;
					$PASAR=$contar_no_disp;


										if($PASAR==0){
															  $j=1;
										                      $x=0;

					  $parametros=$cod_presi.",".$cod_entidad.",".$cod_tipo_inst.",".$cod_inst.",".$ano;
	  				  $sql_numero_compromiso=$this->cfpd21_numero_asiento_compromiso->execute("SELECT cfpd21_compromiso(".$parametros.") as numero_compromiso;");
	  				  $numero_compromiso=$sql_numero_compromiso[0][0]['numero_compromiso'];


															     foreach($new_array as $vec){
															     	       $cp = $this->crear_partida($vec[0], $vec[1], $vec[2], $vec[3], $vec[4], $vec[5], $vec[6],$vec[7], $vec[8], $vec[9],$vec[10]);
																		   $to = 1;
																		   $td = 3;
																		   $ta = 1;
																		   $mt = $this->Formato1($vec[11]);
																		   $ccp = $concepto;
																		   $vec[12] = $numero_compromiso;

																		   $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, $fecha_documento, $mt, $ccp,$ann,$numero_documento,null, null, null,null, null, null,null, $numero_compromiso, null, null, null, $x);

																			if($dnco == true){
																		   		$values_a[]="(".$this->SQLCAIN($ano).",".$numero_documento.",".$ano.",".$vec[1].",".$vec[2].",".$vec[3].",".$vec[4].",".$vec[5].",".$vec[6].",".$vec[7].",".$vec[8].",".$vec[9].",".$vec[10].",".$this->Formato1($vec[11]).",".$vec[12].")";
																			}else{
																		   		break;
																				}



															         $monto=$monto+$this->Formato1($vec[11]);
															         $j++;
															         $x++;

															     }//fin for

															     $values=implode(',',$values_a);




										                       $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
										                                                                                      $to=1,
										                                                                                      $td=6,
										                                                                                      $rif_doc = $rif,
										                                                                                      $ano_dc  = $ano,
										                                                                                      $n_dc    = basic_mascara_ocho($numero_documento),
										                                                                                      $f_dc    = $fecha_documento,
										                                                                                      $cpt_dc  = $concepto,
										                                                                                      $ben_dc  = $beneficiario,

										                                                                                      $mon_dc=array("monto"=>$monto),

										                                                                                      $ano_op               = null,
										                                                                                      $n_op                 = null,
										                                                                                      $f_op                 = null,
										                                                                                      $a_adj_op             = null,
										                                                                                      $n_adj_op             = null,
										                                                                                      $f_adj_op             = null,
										                                                                                      $tp_op                = null,
										                                                                                      $deno_ban_pago        = null,
										                                                                                      $ano_movimiento       = null,
										                                                                                      $cod_ent_pago         = null,
										                                                                                      $cod_suc_pago         = null,
										                                                                                      $cod_cta_pago         = null,
										                                                                                      $num_che_o_debi       = null,
										                                                                                      $fec_che_o_debi       = null,
										                                                                                      $clas_che_o_debi      = null,
										                                                                                      $tipo_che_o_debi      = null,
																														      $ano_dc_array_pago    = array(),
																														      $n_dc_array_pago      = array(),
																														      $n_dc_adj_array_pago  = array(),
																														      $f_dc_array_pago      = array(),

																														      $ano_op_array_pago  = array(),
																														      $n_op_array_pago    = array(),
																														      $f_op_array_pago    = array(),
																														      $tipo_op_array_pago = array(),
																														      $tipo_modificacion  = null);



															     $this->cepd01_compromiso_cuerpo->execute("UPDATE cepd01_compromiso_cuerpo SET monto=".$monto."  where ".$this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$numero_documento."");
															     $R3=$this->cepd01_compromiso_partidas->execute("INSERT INTO cepd01_compromiso_partidas (".$camposT3.") VALUES ".$values."");

															     if($R3>1){
															     	 $this->cepd01_compromiso_numero->execute("UPDATE cepd01_compromiso_numero SET situacion=3 WHERE ".$this->SQLCA()." and ano_compromiso=".$ano." and numero_compromiso=".$numero_documento);
															         $this->cepd01_compromiso_poremitir->execute("INSERT INTO cepd01_compromiso_poremitir (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,username,ano_documento,numero_documento) VALUES (".$this->SQLCAIN().",'".$this->Session->read('nom_usuario')."',".$ano.",".$numero_documento.")");
															         $Guardados_exito=true;
															         $MSJ=array("msj"=>"Registro de Compromiso Guardado con exito","tipo_msj"=>"exito");
																	 $this->Session->write("MSJ",$MSJ);
																	 $this->Session->write("ERROR_PARTIDAS_MSJ",false);
																	 $this->borrar_cugd04();
																	 $this->Session->delete("items");
																     $this->Session->delete("i");
																     $this->Session->delete("contador");
																	 $this->index($Guardados_exito);
																	 $this->render("index");
															     }else{
															 	    $this->cepd01_compromiso_cuerpo->execute("DELETE FROM cepd01_compromiso_cuerpo WHERE ".$this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$numero_documento);
															        $this->cepd01_compromiso_numero->execute("UPDATE cepd01_compromiso_numero SET situacion=1 WHERE ".$this->SQLCA()." and ano_compromiso=".$ano." and numero_compromiso=".$numero_documento);
															        $Guardados_exito=false;
															        $MSJ=array("msj"=>"Registro de Compromiso sin exito","tipo_msj"=>"error");
																	$this->Session->write("MSJ",$MSJ);
																	$this->Session->write("ERROR_PARTIDAS_MSJ",false);
																	$this->borrar_cugd04();
																	 $this->Session->delete("items");
																     $this->Session->delete("i");
																     $this->Session->delete("contador");
																	 $this->index($Guardados_exito);
																	 $this->render("index");
															     }
								 }else{
											 	            $this->cepd01_compromiso_cuerpo->execute("DELETE FROM cepd01_compromiso_cuerpo WHERE ".$this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$numero_documento);
													        $this->cepd01_compromiso_numero->execute("UPDATE cepd01_compromiso_numero SET situacion=1 WHERE ".$this->SQLCA()." and ano_compromiso=".$ano." and numero_compromiso=".$numero_documento);
													        $Guardados_exito=false;
													        $MSJ=array("msj"=>"Registro de Compromiso sin exito - Partida(s) exceden disponibilidad","tipo_msj"=>"error");
															$this->Session->write("MSJ",$MSJ);
															$this->Session->write("ERROR_PARTIDAS_MSJ",true);
															$a=$_SESSION["MSJ"];
													        if($a["tipo_msj"]=="exito")$this->set('Message_existe', $a["msj"]);
													        else if($a["tipo_msj"]=="error")$this->set('errorMessage', $a["msj"]);
													        $this->Session->delete("MSJ");
								 }



			 }else{
			 	    //$this->cepd01_compromiso_cuerpo->execute("DELETE FROM cepd01_compromiso_cuerpo WHERE ".$this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$numero_documento);
			        $this->cepd01_compromiso_numero->execute("UPDATE cepd01_compromiso_numero SET situacion=1 WHERE ".$this->SQLCA()." and ano_compromiso=".$ano." and numero_compromiso=".$numero_documento." and (situacion!=3 AND situacion!=4)");
			        $Guardados_exito=false;
			        $MSJ=array("msj"=>"Registro de Compromiso sin exito","tipo_msj"=>"error");
					$this->Session->write("MSJ",$MSJ);
					$this->borrar_cugd04();
					 $this->Session->delete("items");
				     $this->Session->delete("i");
				     $this->Session->delete("contador");
					 $this->index($Guardados_exito);
					 $this->render("index");
			     }

			 }//fin de validar que el rif y la cedula sean iguales a cero
//       print_r($this->data);
			$this->set('autor_valido',true);
	}else{
		//echo "Hay campos sin llenar";
	}
}//fin funcion guardar
function mostrar_catalogo($var=null){
 	$this->layout="ajax";
 	$codigos_direccion=$this->Session->read('CodigosDireccion');
 	$cod_sector=$codigos_direccion[0]["cugd02_direccion"]["cod_sector"];
 	$cod_programa=$codigos_direccion[0]["cugd02_direccion"]["cod_programa"];
 	$cod_sub_prog=$codigos_direccion[0]["cugd02_direccion"]["cod_sub_prog"];
 	$cod_proyecto=$codigos_direccion[0]["cugd02_direccion"]["cod_proyecto"];
 	/**
 	 * [cugd02_direccion][cod_sector][cod_programa][cod_sub_prog][cod_proyecto])
 	 */
 	//$Lista = $this->Cnmd01->generateList($this->SQLCA(), 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
 	if($var != null || $var!=""){
 		$var = strtoupper($var);

 		if($this->v_cscd01_catalogo_deno_und->findCount($this->condicion()." and ano=".$this->ano_ejecucion()." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and upper(denominacion) LIKE '%$var%'") != 0){
 			$this->set('deno', $var);
 			$catalogo= $this->v_cscd01_catalogo_deno_und->generateList($this->condicion()." and ano=".$this->ano_ejecucion()."  and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto."  and upper(denominacion) LIKE '%$var%'", 'cod_snc ASC', null, '{n}.v_cscd01_catalogo_deno_und.codigo_prod_serv', '{n}.v_cscd01_catalogo_deno_und.denominacion');
 			//$catalogo=null;
 			if(!empty($catalogo)){
		 		//$this->concatena3($catalogo, 'catalogo');
		 		$this->set('catalogo', $catalogo);
		 	}else{
		 		$this->set('catalogo', array());
		 	}
 		}else{
 			//$catalogo= $this->cscd01_catalogo->generateList(null, 'cod_snc ASC', null, '{n}.cscd01_catalogo.cod_snc', '{n}.cscd01_catalogo.denominacion');
 			$this->set('catalogo', array());
 			$this->set('deno', '');
 			$this->set('notfound', 'NO SE ENCONTRO NINGUN DATO - POR FAVOR INTENTE DE NUEVO');

 		}
 		//print_r($catalogo);

 	}
    if($var==""){
    	$this->set('catalogo', array());
 			$this->set('deno', '');
 			$this->set('notfound', 'NO SE ENCONTRO NINGUN DATO - POR FAVOR INTENTE DE NUEVO');
    }

 }

function guardar_anulacion ($var=null,$ano = null,$pagina_actual = null) {
	$this->layout="ajax";
  $cod_dep=$this->verifica_SS(5);
	if(isset($this->data["cepp01_compromiso"])){
		     $tipo_documento=231; //el numero 1 pertence al tipo de documento compromispo


			 $ano                =  $this->data["cepp01_compromiso"]["ano"];
			 $numero_documento   =  $this->data["cepp01_compromiso"]["numero_compromiso"];
			 $fecha_documento    =  $this->data["cepp01_compromiso"]["fecha_documento"];

			 $rif                =  $this->data["cepp01_compromiso"]["rif"];
			 $beneficiario       =  $this->data["cepp01_compromiso"]["beneficiario"];
			 $concepto_anulacion =  $this->data["cepp01_compromiso"]["concepto_anulacion"];

			 $concepto_anulacion="BENEFICIARIO: ".$beneficiario." ANULADO POR: ".$concepto_anulacion;

			 $check_nomina=$this->cepd01_compromiso_partidas->execute("SELECT numero_documento_secuencia FROM cepd01_compromiso_cuerpo WHERE ".$this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$numero_documento);
			 $isNomina = $check_nomina[0][0]["numero_documento_secuencia"] !=='0';


			 $condicion_documento=2;//cuando se guarda es Activo=1
             $partidas_compromiso = $this->cepd01_compromiso_partidas->findAll($conditions = $this->condicion()." and ano='$ano' and numero_documento='$numero_documento'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
             $v=$this->cugd03_acta_anulacion_numero->execute("SELECT numero_acta_anulacion FROM cugd03_acta_anulacion_numero WHERE ".$this->SQLCA()." and ano_acta_anulacion=".$ano." ORDER BY numero_acta_anulacion DESC");
		     if($v!=null){
				$numero=$v[0][0]["numero_acta_anulacion"];
				$numero = $numero =="" ? 1 : $numero+1;
				$this->cugd03_acta_anulacion_numero->execute("UPDATE cugd03_acta_anulacion_numero SET numero_acta_anulacion=".$numero." where ".$this->SQLCA()." and ano_acta_anulacion=".$ano."");
		     }else{
			    $v=$this->cugd03_acta_anulacion_numero->execute("INSERT INTO cugd03_acta_anulacion_numero  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion) VALUES (".$this->SQLCAIN().",".$ano.",1)");
			    $numero=1;
		     }
		     $v=$this->cugd03_acta_anulacion_cuerpo->execute("INSERT INTO cugd03_acta_anulacion_cuerpo  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion,tipo_operacion,ano_documento,numero_documento,fecha_documento,motivo_anulacion) VALUES (".$this->SQLCAIN().",".$ano.",".$numero.",".$tipo_documento.",".$ano.",".$numero_documento.",'".date("Y-m-d")."','".$concepto_anulacion."')");
			    if($v>1){
			    	$R1=$this->cepd01_compromiso_cuerpo->execute("UPDATE cepd01_compromiso_cuerpo SET  ano_anulacion=".date("Y").", numero_anulacion=".$numero.", condicion_actividad=".$condicion_documento.", fecha_proceso_anulacion='".date("Y-m-d")."', username_anulacion='".$this->Session->read('nom_usuario')."' WHERE ".$this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$numero_documento);
			        if($R1>1){
                        $this->cepd01_compromiso_numero->execute("UPDATE cepd01_compromiso_numero SET situacion=4 WHERE ".$this->SQLCA()." and ano_compromiso=".$ano." and numero_compromiso=".$numero_documento);
			        }
			    }
               $msj=isset($R1) && $R1>1?true:false;
                   if($msj==true){
                   	    $partidas_compromiso = $this->cepd01_compromiso_partidas->findAll($conditions = $this->condicion()." and ano=".$ano." and numero_documento=".$numero_documento, $fields = null, $order = null, $limit = null, $page = null, $recursive = null);

                        $monto = 0;

                        foreach($partidas_compromiso as $vec){
					     	       $cp   = $this->crear_partida($vec["cepd01_compromiso_partidas"]["ano"], $vec["cepd01_compromiso_partidas"]["cod_sector"], $vec["cepd01_compromiso_partidas"]["cod_programa"], $vec["cepd01_compromiso_partidas"]["cod_sub_prog"], $vec["cepd01_compromiso_partidas"]["cod_proyecto"], $vec["cepd01_compromiso_partidas"]["cod_activ_obra"], $vec["cepd01_compromiso_partidas"]["cod_partida"],$vec["cepd01_compromiso_partidas"]["cod_generica"], $vec["cepd01_compromiso_partidas"]["cod_especifica"], $vec["cepd01_compromiso_partidas"]["cod_sub_espec"],$vec["cepd01_compromiso_partidas"]["cod_auxiliar"]);
								   $to   = 2;
								   $td   = 3;
								   $ta   = 1;
								   $rnco = $vec["cepd01_compromiso_partidas"]["numero_control_compromiso"];
								   $mt   = $vec["cepd01_compromiso_partidas"]["monto"];
								   $ccp  = $concepto_anulacion;

								    //if($isNomina==false){
								    	$dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, date("d/m/Y"), $mt, $ccp, $ano, $numero_documento, null, null, null, null, null, null, null, $rnco, null, null, null, null);
									/*}else{
										$sqlCheck = 'SELECT * FROM cfpd40_dependencias_presupuesto WHERE cod_dep='.$cod_dep;
								        $checkDep = $this->cepd03_ordenpago_partidas->execute($sqlCheck);
								        // if($cod_dep==9999 || $cod_dep==1028){
								        if(count($checkDep)>0){
											$dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, date("d/m/Y"), $mt, $ccp, $ano, $numero_documento, null, null, null, null, null, null, null, $rnco, null, null, null, null);
										}else{
											$dnco=true;											
										}
									}*/

									if($dnco == true){
										$msj2="Compromiso Anulado con exito";
										$MSJ=array("msj"=>$msj2,"tipo_msj"=>"exito");
										$para_msj = true;
									}else{
										$msj2="Compromiso no Anulado ";
										$MSJ=array("msj"=>$msj2,"tipo_msj"=>"error");
										$para_msj = false;
										break;
									}
                             $monto=$monto+$mt;
					     }//fin foreach


					     $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal($to=2, $td=6, $rif_doc=$rif, $ano_dc=$ano, $n_dc=$numero_documento, $f_dc=$fecha_documento, $cpt_dc=$ccp, $ben_dc=$beneficiario, $mon_dc=array("monto"=>$monto), $ano_op =null, $n_op=null, $f_op=null, $a_adj_op=null, $n_adj_op=null, $f_adj_op=null, $tp_op=null, $deno_ban_pago=null, $ano_movimiento  = null, $cod_ent_pago=null, $cod_suc_pago=null, $cod_cta_pago=null, $num_che_o_debi=null, $fec_che_o_debi=null, $clas_che_o_debi=null);


                   }else{
                   	$msj2="Anulaci&oacute;n del Compromiso sin exito";
                    $MSJ=array("msj"=>$msj2,"tipo_msj"=>"exito");
					$para_msj = false;
                   }
             if(isset($ano) && isset($pagina_actual)){
                 $this->consultar($ano,$pagina_actual,$MSJ);
		         $this->render("consultar");
             }else{
             	$this->lista_busqueda($ano,$numero_documento,$para_msj);
		        $this->render("lista_busqueda");
             }
	}
}

function traer_beneficiario ($tipo,$var=null) {
	$this->layout="ajax";
	if(isset($tipo) && $tipo=="rif"){

		if($var!="")$c=$this->cepd01_compromiso_beneficiario_rif->findCount("upper(rif)='".strtoupper($var)."'");
		else $c=0;
		if($c!=0){
			$resultado=$this->cepd01_compromiso_beneficiario_rif->findAll("upper(rif)='".strtoupper($var)."'");
			//print_r($resultado);
			$this->set("beneficiario",$resultado[0]["cepd01_compromiso_beneficiario_rif"]["beneficiario"]);
		}else{
			$this->set("beneficiario","");
		}
		//echo "rif:".$var;
	}else{
		//echo "C.I:".$var;
        if($var!="")$c=$this->cepd01_compromiso_beneficiario_cedula->findCount("cedula='".$var."'");
		else $c=0;
		if($c!=0){
			$resultado=$this->cepd01_compromiso_beneficiario_cedula->findAll("cedula='".$var."'");
			//print_r($resultado);
			$this->set("beneficiario",$resultado[0]["cepd01_compromiso_beneficiario_cedula"]["beneficiario"]);
		}else{
			$this->set("beneficiario","");
		}
	}
	/*$resultado=$this->cpcd02->findByRif($var);
	$beneficiario=$resultado["cpcd02"]["denominacion"];
	if($beneficiario!=""){
				 $this->set('beneficiario',$beneficiario);
	}else{
		$this->set('beneficiario',"No existe, ".$var);
	}*/

}

function imputacion_presupuestaria ($var=null) {
	$this->layout="ajax";
        $ano=$this->ano_ejecucion();
        $this->set('ano',$ano);
		/**if($this->Session->read('year_pago')>date("Y")){
								$ano= 1+date("Y");
			}else{
							$ano=date("Y");
			}*/
			//echo "<h1>".$var."</h1><br>";
		$var=$var==null ? 0:$var;
        //echo "<h3>".$var."</h3><br>";
		$a=  $this->cscd01_catalogo->findAll("codigo_prod_serv=".$var,array('cod_partida','cod_generica','cod_especifica','cod_sub_espec','cod_auxiliar'));
		$this->set("partidas",$a);
		//print_r($a);
		$this->Session->write('partidas',$a);
		$part= $a[0]['cscd01_catalogo']['cod_partida'];//<9 ? "40".$a[0]['cscd01_catalogo']['cod_partida']:$a[0]['cscd01_catalogo']['cod_partida'];
		//$part= $part <400 ? "4".$part : $part;
		$gen=$a[0]['cscd01_catalogo']['cod_generica'];
		$espec=$a[0]['cscd01_catalogo']['cod_especifica'];
		$sube=$a[0]['cscd01_catalogo']['cod_sub_espec'];
		$f=$this->Session->read('CodigosDireccion');
		$ss=$f[0]["cugd02_direccion"];
		$rr=$a[0]["cscd01_catalogo"];
		//echo $this->SQLCA($ano)." and cod_sector=".$f[0]["cugd02_direccion"]["cod_sector"]." and cod_programa=".$f[0]["cugd02_direccion"]["cod_programa"]." and cod_sub_prog=".$f[0]["cugd02_direccion"]["cod_sub_prog"]." and cod_proyecto=".$f[0]["cugd02_direccion"]["cod_proyecto"]." and cod_partida=".$part." and cod_generica=".$gen." and cod_especifica=".$espec." and cod_sub_espec=".$sube;
		$resultado=$this->cfpd05->findAll($this->SQLCA($ano)." and cod_sector=".$f[0]["cugd02_direccion"]["cod_sector"]." and cod_programa=".$f[0]["cugd02_direccion"]["cod_programa"]." and cod_sub_prog=".$f[0]["cugd02_direccion"]["cod_sub_prog"]." and cod_proyecto=".$f[0]["cugd02_direccion"]["cod_proyecto"]." and cod_partida=".$part." and cod_generica=".$gen." and cod_especifica=".$espec." and cod_sub_espec=".$sube." GROUP BY cod_activ_obra",array('cod_activ_obra'));

		$lista=  $this->cfpd05->generateList($this->SQLCA($ano)." and cod_sector=".$f[0]["cugd02_direccion"]["cod_sector"]." and cod_programa=".$f[0]["cugd02_direccion"]["cod_programa"]." and cod_sub_prog=".$f[0]["cugd02_direccion"]["cod_sub_prog"]." and cod_proyecto=".$f[0]["cugd02_direccion"]["cod_proyecto"]." and cod_partida=".$part." and cod_generica=".$gen." and cod_especifica=".$espec." and cod_sub_espec=".$sube, 'cod_activ_obra ASC', null, '{n}.cfpd05.cod_activ_obra', '{n}.cfpd05.cod_activ_obra');
		//$this->set('actividad',$lista);
		//echo "<h1>LISTA".count($resultado)."</h1>";
		//echo $this->SQLCA($ano)." and cod_sector=".$f[0]["cugd02_direccion"]["cod_sector"]." and cod_programa=".$f[0]["cugd02_direccion"]["cod_programa"]." and cod_sub_prog=".$f[0]["cugd02_direccion"]["cod_sub_prog"]." and cod_proyecto=".$f[0]["cugd02_direccion"]["cod_proyecto"]." and cod_partida=".$part." and cod_generica=".$gen." and cod_especifica=".$espec;
		//print_r($resultado);
		if(count($resultado)==1){
            $this->set("cod_actividad",$resultado[0]["cfpd05"]["cod_activ_obra"]);
            $this->Session->write("actividad",$resultado[0]["cfpd05"]["cod_activ_obra"]);
			$t_aux=$this->cfpd05->findCount($this->SQLCA($ano)." and cod_sector=".$f[0]["cugd02_direccion"]["cod_sector"]." and cod_programa=".$f[0]["cugd02_direccion"]["cod_programa"]." and cod_sub_prog=".$f[0]["cugd02_direccion"]["cod_sub_prog"]." and cod_proyecto=".$f[0]["cugd02_direccion"]["cod_proyecto"]." and cod_activ_obra=".$resultado[0]["cfpd05"]["cod_activ_obra"]." and cod_partida=".$part." and cod_generica=".$gen." and cod_especifica=".$espec." and cod_sub_espec=".$sube,array('cod_auxiliar'));
			$resultado_aux=$this->cfpd05->findAll($this->SQLCA($ano)." and cod_sector=".$f[0]["cugd02_direccion"]["cod_sector"]." and cod_programa=".$f[0]["cugd02_direccion"]["cod_programa"]." and cod_sub_prog=".$f[0]["cugd02_direccion"]["cod_sub_prog"]." and cod_proyecto=".$f[0]["cugd02_direccion"]["cod_proyecto"]." and cod_activ_obra=".$resultado[0]["cfpd05"]["cod_activ_obra"]." and cod_partida=".$part." and cod_generica=".$gen." and cod_especifica=".$espec." and cod_sub_espec=".$sube,array('cod_auxiliar'));
			if($t_aux>1){
                $lista=  $this->v_cfpd05_denominaciones->generateList($this->SQLCA($ano)." and cod_sector=".$f[0]["cugd02_direccion"]["cod_sector"]." and cod_programa=".$f[0]["cugd02_direccion"]["cod_programa"]." and cod_sub_prog=".$f[0]["cugd02_direccion"]["cod_sub_prog"]." and cod_proyecto=".$f[0]["cugd02_direccion"]["cod_proyecto"]." and cod_activ_obra=".$resultado[0]["cfpd05"]["cod_activ_obra"]." and cod_partida=".$part." and cod_generica=".$gen." and cod_especifica=".$espec." and cod_sub_espec=".$sube, 'cod_auxiliar ASC', null, '{n}.v_cfpd05_denominaciones.cod_auxiliar', '{n}.v_cfpd05_denominaciones.deno_auxiliar');
		        $this->concatena($lista, 'auxiliar');
			}else{
               $this->set("cod_auxiliar",$resultado_aux[0]["cfpd05"]["cod_auxiliar"]);
               $this->Session->write("auxiliar",$resultado_aux[0]["cfpd05"]["cod_auxiliar"]);
               $disponibilidad = $this->disponibilidad($ano, $ss["cod_sector"], $ss["cod_programa"], $ss["cod_sub_prog"], $ss["cod_proyecto"],$resultado[0]["cfpd05"]["cod_activ_obra"], $rr["cod_partida"], $rr["cod_generica"], $rr["cod_especifica"], $rr["cod_sub_espec"], $resultado_aux[0]["cfpd05"]["cod_auxiliar"]);

				 echo "<script>" .
				 		"document.getElementById('td_disponibilidad').innerHTML='".$this->Formato2($disponibilidad)."';" .
				 "</script>";
			}
		}else if(count($resultado)>1){
			//echo "hollla";
			$this->set('actividad',$lista);
		}else{
			$this->set("ocultar",true);
		}

		//print_r($resultado);
		//echo $this->SQLCA($ano)." and cod_sector=".$f[0]["cugd02_direccion"]["cod_sector"]." and cod_programa=".$f[0]["cugd02_direccion"]["cod_programa"]." and cod_sub_prog=".$f[0]["cugd02_direccion"]["cod_sub_prog"]." and cod_proyecto=".$f[0]["cugd02_direccion"]["cod_proyecto"]." and cod_partida=".$part." and cod_generica=".$gen." and cod_especifica=".$espec." and cod_sub_espec=".$sube;

}//fin imputacion presupuestaria


function codigos_diferentes () {
	$this->layout="ajax";
	  $this->Session->delete("sec");
      $this->Session->delete("prog");
      $this->Session->delete("subp");
      $this->Session->delete("proy");
      $this->Session->delete("actividad");
      $this->Session->delete("cpar");
      $this->Session->delete("cgen");
      $this->Session->delete("cesp");
      $this->Session->delete("csesp");
      $this->Session->delete("auxiliar");
	  /*if($this->Session->read('year_pago')>date("Y")){
								$ano= 1+date("Y");
			}else{
							$ano=date("Y");
			}*/
		$ano=$this->ano_ejecucion();
		$this->set('ano',$ano);
		$sector=$this->v_cfpd05_denominaciones->generateList($this->SQLCA($ano),'cod_sector ASC', null, '{n}.v_cfpd05_denominaciones.cod_sector', '{n}.v_cfpd05_denominaciones.deno_sector');
			$sector = $sector != null ? $sector : array();
		$this->concatena($sector, 'sector');
}

function consulta_form () {
	$this->layout="ajax";
	$this->set('ANO',$this->ano_ejecucion());
}

 function consultar ($ano=null,$pagina=null,$msj=array()) {
	$this->layout="ajax";
		if(isset($pagina)){
				$pagina=$pagina;
				$this->Session->delete('MSJ');
		}else{
				 $pagina=1;
		}//fin else

				/*	if($this->Session->read('year_pago')>date("Y")){
							$Ano= 1+date("Y");
			}else{
							$Ano=date("Y");
			}*/
            if(isset($ano) && $ano!=null){
            $Ano=$ano;
		}else{
			if(isset($this->data['cepp01_compromiso']['ano_consulta'])){
                $Ano=$this->data['cepp01_compromiso']['ano_consulta'];
			}else{
				$Ano=$this->ano_ejecucion();
			}

		}
						 $this->set('ano',$Ano);
						 $this->set('ejercicio', $Ano);
						 $Tfilas=$this->cepd01_compromiso_cuerpo->findCount($this->SQLCA()." and ano_documento=".$Ano." and (cod_obra='' OR cod_obra is null)");
						 //echo $Tfilas;
						 if($Tfilas!=0){
						 $this->set('pag_cant',$pagina.'/'.$Tfilas);
						 $this->set('ultimo',$Tfilas);
						 $dataCompromiso=$this->cepd01_compromiso_cuerpo->findAll($this->SQLCA()." and ano_documento=".$Ano." and (cod_obra='' OR cod_obra is null)",null,'ano_documento,numero_documento ASC',1,$pagina,null);
						 foreach ($dataCompromiso as $YYY);
								 //$YYY['cfpd05']['cod_sector'];
							    //////////////////////////////////////////////////////////////////////////
								$cond = "cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$this->verifica_SS(5);
								$cond1=" and cod_dir_superior=".$YYY['cepd01_compromiso_cuerpo']['cod_dir_superior'];
						        $a1=  $this->cugd02_direccionsuperior->findAll($cond.$cond1);
								$x1= $a1[0]['cugd02_direccionsuperior']['denominacion'];
								$this->set('dir_sup',$x1);
								$cond2=" and cod_dir_superior=".$YYY['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$YYY['cepd01_compromiso_cuerpo']['cod_coordinacion'];
						        $a2=  $this->cugd02_coordinacion->findAll($cond.$cond2);
								$x2= $a2[0]['cugd02_coordinacion']['denominacion'];
								$this->set('coordinacion',$x2);
 				                $cond3=" and cod_dir_superior=".$YYY['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$YYY['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$YYY['cepd01_compromiso_cuerpo']['cod_secretaria'];
						        $a3=  $this->cugd02_secretaria->findAll($cond.$cond3);
								$x3= $a3[0]['cugd02_secretaria']['denominacion'];
								$this->set('secretaria',$x3);
								$cond4=" and cod_dir_superior=".$YYY['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$YYY['cepd01_compromiso_cuerpo']['cod_coordinacion']." and  cod_secretaria=".$YYY['cepd01_compromiso_cuerpo']['cod_secretaria']." and cod_direccion=".$YYY['cepd01_compromiso_cuerpo']['cod_direccion'];
						        $a4=  $this->cugd02_direccion->findAll($cond.$cond4);
								$x4= $a4[0]['cugd02_direccion']['denominacion'];
								$this->set('direccion',$x4);
								$tipo_doc=$this->cepd01_tipo_compromiso->findAll("cod_tipo_compromiso=".$YYY['cepd01_compromiso_cuerpo']['cod_tipo_compromiso']);

								$this->set("tipo_doc",$tipo_doc[0]["cepd01_tipo_compromiso"]["denominacion"]);
								//$this->traer_beneficiario($YYY['cepd01_compromiso_cuerpo']['rif']);
								$compromiso_partidas=$this->cepd01_compromiso_partidas->findAll($this->SQLCA()." and ano_documento=".$Ano." and numero_documento=".$YYY['cepd01_compromiso_cuerpo']['numero_documento']);

								//$C_A=$this->cugd03_acta_anulacion_cuerpo->findByNumero_acta_anulacion($YYY['cepd01_compromiso_cuerpo']['numero_anulacion']);
								$C_A=$this->cugd03_acta_anulacion_cuerpo->findAll($this->SQLCA()." and numero_acta_anulacion=".$YYY['cepd01_compromiso_cuerpo']['numero_anulacion']." and ano_acta_anulacion=".$Ano);
                                //print_r($C_A);
                                if($C_A!=null){
                                	$this->set("concepto_anulacion",$C_A[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"]);
                                }else{
                                	$this->set("concepto_anulacion","");
                                }
                                if($YYY['cepd01_compromiso_cuerpo']['ano_orden_pago']!=0 && $YYY['cepd01_compromiso_cuerpo']['numero_orden_pago']!=0){
                                    $dataOdenpago=$this->cepd03_ordenpago_cuerpo->findAll($this->SQLCA()." and ano_orden_pago=".$YYY['cepd01_compromiso_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$YYY['cepd01_compromiso_cuerpo']['numero_orden_pago'],array("fecha_orden_pago","cuenta_bancaria","numero_cheque","fecha_cheque","cod_entidad_bancaria"),'ano_orden_pago,numero_orden_pago ASC',1,null,null);
						            $this->set("numero_orden_pago",$YYY['cepd01_compromiso_cuerpo']['numero_orden_pago']);
						            $this->set("fecha_orden_pago",$dataOdenpago[0]['cepd03_ordenpago_cuerpo']['fecha_orden_pago']);
                                    $this->set("tiene_ordenpago",true);
                                    //print_r($dataOdenpago);
	                                if($dataOdenpago[0]['cepd03_ordenpago_cuerpo']['cod_entidad_bancaria']!=0  && $dataOdenpago[0]['cepd03_ordenpago_cuerpo']['cuenta_bancaria']!=0 && $dataOdenpago[0]['cepd03_ordenpago_cuerpo']['numero_cheque']!=0){
	                                  //'cstd01_entidades_bancarias','cstd01_sucursales_bancarias'
	                                  $ceb=$this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$dataOdenpago[0]['cepd03_ordenpago_cuerpo']['cod_entidad_bancaria']);
	                                  $this->set("denominacion_bancaria",$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]);
	                                  $this->set("nro_cta",$dataOdenpago[0]['cepd03_ordenpago_cuerpo']['cuenta_bancaria']);
	                                  $this->set("nro_cheque",$dataOdenpago[0]['cepd03_ordenpago_cuerpo']['numero_cheque']);
	                                  $this->set("fecha_cheque",$dataOdenpago[0]['cepd03_ordenpago_cuerpo']['fecha_cheque']);
	                                  $this->set("tiene_cheque",true);
									}else{
										$this->set("denominacion_bancaria","--");
	                                    $this->set("nro_cta","--");
	                                    $this->set("nro_cheque","--");
	                                    $this->set("fecha_cheque","1900-01-01");
	                                    $this->set("tiene_cheque",false);
									}
	                                }else{
	                                	$this->set("numero_orden_pago","--");
						                $this->set("fecha_orden_pago","1900-01-01");
	                                    $this->set("tiene_ordenpago",false);
	                                }


								$this->set('COMPROMISO_PARTIDA',$compromiso_partidas);
								$this->set('COMPROMISO',$dataCompromiso);
								$this->set('pagina_actual',$pagina);
								$this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
						}else{
						$this->set('COMPROMISO','');
						$this->set('errorMessage', 'No se encontrar&oacute;n datos');

					 }///fin else  del if-else que compara las Tfilas
                if(isset($_SESSION["MSJ"])){
					$a=$_SESSION["MSJ"];
					if($a["tipo_msj"]=="exito")$this->set('Message_existe', $a["msj"]);
					else if($a["tipo_msj"]=="error")$this->set('errorMessage', $a["msj"]);

					//print_r($_SESSION["MSJ"]);
				}else if(isset($msj) && count($msj)>0){
					$tt_msj = $msj['tipo_msj']=='exito'?'Message_existe':'errorMessage';
					$this->set($tt_msj, $msj['msj']);
				}
}//fin consultar

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


function ver_semaforo ($var=null) {
	$this->layout="ajax";
		//echo $this->Formato2($this->Formato1($var));
		$monto=$this->Formato1($var);
		//echo   $_SESSION["ano"],$_SESSION["sec"], $_SESSION["pro"],$_SESSION["subp"],$_SESSION["proy"],$_SESSION["actividad"],$_SESSION["cpar"],$_SESSION["cgen"],$_SESSION["cesp"],$_SESSION["csesp"],$_SESSION["auxiliar"];
        if($_SESSION["cpar"]<400){
           $partida=$_SESSION["cpar"]<9?CE."0".$_SESSION["cpar"]:CE.$_SESSION["cpar"];
        }else{
        	$partida=$_SESSION["cpar"];//<9?CE."0".$_SESSION["cpar"]:CE.$_SESSION["cpar"];
        }

        //echo $partida;
        //echo $_SESSION["ano"], $_SESSION["sec"], $_SESSION["prog"], $_SESSION["subp"], $_SESSION["proy"], $_SESSION["actividad"], $partida, $_SESSION["cgen"], $_SESSION["cesp"], $_SESSION["csesp"], $_SESSION["auxiliar"];
        $username = strtoupper($this->Session->read('nom_usuario'));
	if ($_SESSION["ano"]!=null && $_SESSION["sec"]!=null && $_SESSION["prog"]!=null && $_SESSION["subp"]!=null && $_SESSION["proy"]!=null && $_SESSION["actividad"]!=null && $partida!=null && $_SESSION["cgen"]!=null && $_SESSION["cesp"]!=null && $_SESSION["csesp"]!=null && $monto!=null && $_SESSION["auxiliar"]!=null){
		$trafico = $this->semaforo($_SESSION["ano"], $_SESSION["sec"], $_SESSION["prog"], $_SESSION["subp"], $_SESSION["proy"], $_SESSION["actividad"], $partida, $_SESSION["cgen"], $_SESSION["cesp"], $_SESSION["csesp"], $_SESSION["auxiliar"]);
        //echo "entrar uno";
		//echo "el username es: ".$trafico['username']." y el color es: ".$trafico['color'];

		if($trafico['color'] == 'rojo' && $trafico['username']!= $username){
			//echo  "entra dos";
			$this->set('msg', $trafico['mensaje']);
		}else{
			//echo "entrar tres";
			$this->guardar_cugd04($_SESSION["ano"], $_SESSION["sec"], $_SESSION["prog"], $_SESSION["subp"], $_SESSION["proy"], $_SESSION["actividad"], $partida, $_SESSION["cgen"], $_SESSION["cesp"], $_SESSION["csesp"], $_SESSION["auxiliar"], $username);
			$disponibilidad = $this->disponibilidad($_SESSION["ano"], $_SESSION["sec"], $_SESSION["prog"], $_SESSION["subp"], $_SESSION["proy"], $_SESSION["actividad"], $partida, $_SESSION["cgen"], $_SESSION["cesp"], $_SESSION["csesp"], $_SESSION["auxiliar"]);
			if(empty($disponibilidad)){
				$this->set('msg', 'ESTA PARTIDA NO ESTA REGISTRADA EN LA DISTRIBUCCION DE GASTO');
				$this->set('MostrarTime',false);
				//echo "entrar cuatro";
			}else{
				//echo "entrar cinco";
				if ($disponibilidad < $monto) {
					$this->set('msg', 'EL MONTO DISPONIBLE PARA ESTA PARTIDA ES DE '.$this->Formato2($disponibilidad).' '.MONEDA2);
					$this->set('hide',true);
					$this->set('MostrarTime',false);
				//echo "entrar seis";
				}
			}
		}
	}

}


function guardar_cugd04($ano=null, $cod_sector=null, $cod_programa=null, $cod_sub_prog=null, $cod_proyecto=null, $cod_activ_obra=null, $cod_partida=null, $cod_generica=null, $cod_especifica=null, $cod_sub_espec=null, $cod_auxiliar=null, $username=null){

	$cod_presi = $this->Session->read('SScodpresi');
  	$cod_entidad = $this->Session->read('SScodentidad');
  	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
  	$cod_inst = $this->Session->read('SScodinst');
  	$cod_dep = $this->Session->read('SScoddep');

	if ($ano!=null && $cod_sector!=null && $cod_programa!=null && $cod_sub_prog!=null && $cod_proyecto!=null && $cod_activ_obra!=null && $cod_partida!=null && $cod_generica!=null && $cod_especifica!=null && $cod_sub_espec!=null && $username!=null && $cod_auxiliar!=null){

		$partida = " and ano='$ano' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and username='$username'";
		$cont_partida = $this->cugd04->findCount($this->condicion().$partida);
		if($cont_partida==0){
			$sql_insert_cugd04="INSERT INTO cugd04 VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano', '$cod_sector', '$cod_programa', '$cod_sub_prog', '$cod_proyecto', '$cod_activ_obra', '$cod_partida', '$cod_generica', '$cod_especifica', '$cod_sub_espec', '$cod_auxiliar', '$username')";
			//-$this->cugd04->execute($sql_insert_cugd04);
			$time = date('U');
			//-$this->cugd04->execute("UPDATE cugd04_entrada_modulo SET hora_captura_partida='$time' WHERE username='$username'");
		}else{
			$sql_update_cugd04="UPDATE cugd04 set cod_auxiliar='$cod_auxiliar' WHERE ".$this->condicion().$partida;
			//-$this->cugd04->execute($sql_update_cugd04);
		}

	}
}



function condicion(){
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

  return $condicion;

}

function trafico($ano=null, $cod_sector=null, $cod_programa=null, $cod_sub_prog=null, $cod_proyecto=null, $cod_activ_obra=null, $cod_partida=null, $cod_generica=null, $cod_especifica=null, $cod_sub_espec=null, $monto=null, $cod_auxiliar=null){
	$this->layout="ajax";
	$username = strtoupper($this->Session->read('nom_usuario'));
	if ($ano!=null && $cod_sector!=null && $cod_programa!=null && $cod_sub_prog!=null && $cod_proyecto!=null && $cod_activ_obra!=null && $cod_partida!=null && $cod_generica!=null && $cod_especifica!=null && $cod_sub_espec!=null && $monto!=null && $cod_auxiliar!=null){
		$trafico = $this->semaforo($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);

		//echo "el username es: ".$trafico['username']." y el color es: ".$trafico['color'];

		if($trafico['color'] == 'rojo' && $trafico['username']!= $username){
			$this->set('msg', $trafico['mensaje']);
		}else{
			$this->guardar_cugd04($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar, $username);
			$disponibilidad = $this->disponibilidad($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
			if ($disponibilidad < $monto) {
				$this->set('msg', 'EL MONTO DISPONIBLE PARA ESTA PARTIDA ES DE '.$this->Formato2($disponibilidad).' '.MONEDA2);
			}
		}
	}
}

function buscar_compromiso ($var=null) {
	$this->layout="ajax";
	//$this->cepd01_compromiso_cuerpo->getColumnType();
	print_r($this->cepd01_compromiso_cuerpo->getColumnType("cepd01_compromiso_cuerpo"));

}//
function lista_busqueda ($ano=null, $var=null,$msj = null) {
	$this->layout="ajax";


       if(isset($var)){
				$cod=$var;
		}else{
				 $cod=1;
		}//fin else
        if(isset($ano) && $ano!=null){
        	$Ano = $ano;
        }else{
        	$Ano=$this->ano_ejecucion();
        }


						 $this->set('ano',$Ano);
						 $this->set('ejercicio', $Ano);
						 $Tfilas=$this->cepd01_compromiso_cuerpo->findCount($this->SQLCA()." and ano_documento=".$Ano." and numero_documento=".$cod);
						 //echo $Tfilas;
						 if($Tfilas!=0){
						 //$this->set('pag_cant',$pagina.'/'.$Tfilas);
						 //$this->set('ultimo',$Tfilas);
						 $dataCompromiso=$this->cepd01_compromiso_cuerpo->findAll($this->SQLCA()." and ano_documento=".$Ano." and numero_documento=".$cod,null,'ano_documento,numero_documento ASC',1,null,null);
						 foreach ($dataCompromiso as $YYY);
								 //$YYY['cfpd05']['cod_sector'];
							    //////////////////////////////////////////////////////////////////////////
								$cond = "cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$this->verifica_SS(5);
								$cond1=" and cod_dir_superior=".$YYY['cepd01_compromiso_cuerpo']['cod_dir_superior'];
						        $a1=  $this->cugd02_direccionsuperior->findAll($cond.$cond1);
								$x1= $a1[0]['cugd02_direccionsuperior']['denominacion'];
								$this->set('dir_sup',$x1);
								$cond2=" and cod_dir_superior=".$YYY['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$YYY['cepd01_compromiso_cuerpo']['cod_coordinacion'];
						        $a2=  $this->cugd02_coordinacion->findAll($cond.$cond2);
								$x2= $a2[0]['cugd02_coordinacion']['denominacion'];
								$this->set('coordinacion',$x2);
 				                $cond3=" and cod_dir_superior=".$YYY['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$YYY['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$YYY['cepd01_compromiso_cuerpo']['cod_secretaria'];
						        $a3=  $this->cugd02_secretaria->findAll($cond.$cond3);
								$x3= $a3[0]['cugd02_secretaria']['denominacion'];
								$this->set('secretaria',$x3);
								$cond4=" and cod_dir_superior=".$YYY['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$YYY['cepd01_compromiso_cuerpo']['cod_coordinacion']." and  cod_secretaria=".$YYY['cepd01_compromiso_cuerpo']['cod_secretaria']." and cod_direccion=".$YYY['cepd01_compromiso_cuerpo']['cod_direccion'];
						        $a4=  $this->cugd02_direccion->findAll($cond.$cond4);
								$x4= $a4[0]['cugd02_direccion']['denominacion'];
								$this->set('direccion',$x4);
								$tipo_doc=$this->cepd01_tipo_compromiso->findAll("cod_tipo_compromiso=".$YYY['cepd01_compromiso_cuerpo']['cod_tipo_compromiso']);

								$this->set("tipo_doc",$tipo_doc[0]["cepd01_tipo_compromiso"]["denominacion"]);
								//$this->traer_beneficiario($YYY['cepd01_compromiso_cuerpo']['rif']);
								$compromiso_partidas=$this->cepd01_compromiso_partidas->findAll($this->SQLCA()." and ano_documento=".$Ano." and numero_documento=".$YYY['cepd01_compromiso_cuerpo']['numero_documento']);

								//$C_A=$this->cugd03_acta_anulacion_cuerpo->findByNumero_acta_anulacion($YYY['cepd01_compromiso_cuerpo']['numero_anulacion']);
								$C_A=$this->cugd03_acta_anulacion_cuerpo->findAll($this->SQLCA()." and numero_acta_anulacion=".$YYY['cepd01_compromiso_cuerpo']['numero_anulacion']." and ano_acta_anulacion=".$Ano);
                                //print_r($C_A);
                                if($C_A!=null){
                                	$this->set("concepto_anulacion",$C_A[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"]);
                                }else{
                                	$this->set("concepto_anulacion","");
                                }
                                if($YYY['cepd01_compromiso_cuerpo']['ano_orden_pago']!=0 && $YYY['cepd01_compromiso_cuerpo']['numero_orden_pago']!=0){
                                    $dataOdenpago=$this->cepd03_ordenpago_cuerpo->findAll($this->SQLCA()." and ano_orden_pago=".$YYY['cepd01_compromiso_cuerpo']['ano_orden_pago']." and numero_orden_pago=".$YYY['cepd01_compromiso_cuerpo']['numero_orden_pago'],array("fecha_orden_pago","cuenta_bancaria","numero_cheque","fecha_cheque","cod_entidad_bancaria"),'ano_orden_pago,numero_orden_pago ASC',1,null,null);
						            $this->set("numero_orden_pago",$YYY['cepd01_compromiso_cuerpo']['numero_orden_pago']);
						            $this->set("fecha_orden_pago",$dataOdenpago[0]['cepd03_ordenpago_cuerpo']['fecha_orden_pago']);
                                    $this->set("tiene_ordenpago",true);
                                    //print_r($dataOdenpago);
	                                if($dataOdenpago[0]['cepd03_ordenpago_cuerpo']['cod_entidad_bancaria']!=0  && $dataOdenpago[0]['cepd03_ordenpago_cuerpo']['cuenta_bancaria']!=0 && $dataOdenpago[0]['cepd03_ordenpago_cuerpo']['numero_cheque']!=0){
	                                  //'cstd01_entidades_bancarias','cstd01_sucursales_bancarias'
	                                  $ceb=$this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$dataOdenpago[0]['cepd03_ordenpago_cuerpo']['cod_entidad_bancaria']);
	                                  $this->set("denominacion_bancaria",$ceb[0]["cstd01_entidades_bancarias"]["denominacion"]);
	                                  $this->set("nro_cta",$dataOdenpago[0]['cepd03_ordenpago_cuerpo']['cuenta_bancaria']);
	                                  $this->set("nro_cheque",$dataOdenpago[0]['cepd03_ordenpago_cuerpo']['numero_cheque']);
	                                  $this->set("fecha_cheque",$dataOdenpago[0]['cepd03_ordenpago_cuerpo']['fecha_cheque']);
	                                  $this->set("tiene_cheque",true);
									}else{
										$this->set("denominacion_bancaria","--");
	                                    $this->set("nro_cta","--");
	                                    $this->set("nro_cheque","--");
	                                    $this->set("fecha_cheque","1900-01-01");
	                                    $this->set("tiene_cheque",false);
									}
	                                }else{
	                                	$this->set("numero_orden_pago","--");
						                $this->set("fecha_orden_pago","1900-01-01");
	                                    $this->set("tiene_ordenpago",false);
	                                }



								$this->set('COMPROMISO_PARTIDA',$compromiso_partidas);
								$this->set('COMPROMISO',$dataCompromiso);

						}else{
						$this->set('COMPROMISO','');
						$this->set('errorMessage', 'No se encontrar&oacute;n datos para el n&uacute;mero de compromiso '.$var);
						$this->consultar(1,'No se encontrar&oacute;n datos para el n&uacute;mero de compromiso '.$var);
						$this->render("consultar");

					 }///fin else  del if-else que compara las Tfilas
        if(isset($msj)){
        	if($msj==true)$this->set('Message_existe', 'Compromiso Anulado con exito');
        	else$this->set('errorMessage', 'Anulaci&oacute;n del Compromiso sin exito');
        }

}//
 function salir ($var=null) {
	$this->layout="ajax";
	echo"<script>menu_activo();</script>";

}

function salir_compromiso($num_rc=null){
	$this->layout="ajax";
	$ano=$this->ano_ejecucion();
	$resultado=$this->cepd01_compromiso_numero->execute("UPDATE  cepd01_compromiso_numero SET situacion=1 WHERE ".$this->SQLCA()." and numero_compromiso=".$num_rc." and ano_compromiso=".$ano);

     echo"<script>menu_activo();</script>";
}

function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cepp01_compromiso']['login']) && isset($this->data['cepp01_compromiso']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cepp01_compromiso']['login']);
		$paswd=addslashes($this->data['cepp01_compromiso']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=78 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('errorMessage',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('autor_valido',false);
			$this->index("autor_valido");
			$this->render("index");
		}
	}
}

}//fin class
?>
