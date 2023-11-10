<?php

 class Cepp02ContratoservicioController extends AppController{

// cepp02_contratoservicio
 	var $uses = array('cfpd21_numero_asiento_compromiso', 'v_cfpd05_denominaciones', 'cugd03_acta_anulacion_numero', 'ccfd04_cierre_mes',
                       'v_cscd01_catalogo_deno_und', 'ccfd03_instalacion', 'cfpd21_numero_asiento_compromiso', 'cugd03_acta_anulacion_cuerpo',
                       'v_cfpd05_disponibilidad','cugd04','cpcd02','cscd01_catalogo','cepd01_tipo_compromiso','cepd02_contratoservicio_cuerpo',
                       'cepd02_contratoservicio_partidas','cfpd05','cfpd05_requerimiento','cfpd05_2032_tmp','cfpd05_auxiliar','cfpp05auxiliar',
                       'cfpd02_sector', 'cfpd02_programa', 'cfpd02_sub_prog', 'cfpd02_proyecto', 'cfpd02_activ_obra', 'arrd05','cfpd01_ano_grupo',
                       'cfpd01_ano_partida', 'cfpd01_ano_generica', 'cfpd01_ano_especifica', 'cfpd01_ano_sub_espec', 'cfpd01_ano_auxiliar',
                       'cfpd01_formulacion','cugd02_direccionsuperior', 'cugd02_coordinacion', 'cugd02_secretaria', 'cugd02_direccion',
                       'cfpd21', 'cobd01_contratoobras_cuerpo', 'cobd01_contratoobras_partidas', 'cfpd07_obras_cuerpo', 'v_cfpd05_disponibilidad'
                       ,'cfpd07_obras_partidas', 'v_cepd02_contratoservicio_cuerpo', 'cscd04_ordencompra_parametros'

                       ,    'cstd01_entidades_bancarias', 'ccfd10_descripcion', 'ccfd10_detalles', 'ccfd02', 'ccfd05_numero_asiento',
                            'ccfd04_cuentas_enlace', 'cpcd02', 'cepd01_compromiso_cuerpo', 'cscd04_ordencompra_anticipo_cuerpo',
                            'cscd04_ordencompra_encabezado', 'cscd04_ordencompra_autorizacion_cuerpo',
						    'cscd04_ordencompra_encabezado', 'cobd01_contratoobras_anticipo_cuerpo', 'cobd01_contratoobras_valuacion_cuerpo',
						    'cobd01_contratoobras_retencion_cuerpo', 'cepd02_contratoservicio_anticipo_cuerpo', 'cepd02_contratoservicio_valuacion_cuerpo',
						    'cepd02_contratoservicio_retencion_cuerpo','cobd01_contratoobras_cuerpo', 'cepd02_contratoservicio_cuerpo','cugd05_restriccion_clave'
                       );
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

}













function selecion_obra($ano=null, $var=null){

  $this->layout = "ajax";
  $cod_presi                =       $this->Session->read('SScodpresi');
  $cod_entidad              =       $this->Session->read('SScodentidad');
  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
  $cod_inst                 =       $this->Session->read('SScodinst');
  $cod_dep                  =       $this->Session->read('SScoddep');
  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'  ';


$cfpd07_obras_cuerpo   =  $this->cfpd07_obras_cuerpo->findAll($condicion." and ano_estimacion=".$ano." and cod_obra='".$var."' ");

foreach($cfpd07_obras_cuerpo as $aux){
	$codigo_prod_serv    = $aux['cfpd07_obras_cuerpo']['codigo_prod_serv'];
	$estimado_presu      = $aux['cfpd07_obras_cuerpo']['estimado_presu'];
	$denominacion_obra   = $aux['cfpd07_obras_cuerpo']['denominacion'];
}//fin foreach



if($var!=null){

    $this->Session->write('cod_obra', $var);
    $this->Session->write('ano_obra', $ano);
    $this->Session->write('codigo_prod_serv', $codigo_prod_serv);

    echo'<script>';
       echo "document.getElementById('input_catalogo').disabled=true; ";
       echo "document.getElementById('servicio').disabled=true; ";
       echo "document.getElementById('codigo_servicio').disabled=true; ";
       echo "document.getElementById('denominacion_servicio').disabled=true; ";
       echo"   document.getElementById('input_cod_obra').value= \"$denominacion_obra\";  ";
    echo'</script>';


}else{

   $this->Session->delete('cod_obra');
   $this->Session->delete('ano_obra');

   echo'<script>';
         echo"   document.getElementById('input_cod_obra').value='';  ";
    echo'</script>';

}//fin else




echo'<script>';




   echo" if(document.getElementById('select_1').options[1].text == '----'){";
   echo"   document.getElementById('select_1').options[1].selected = true; ";
   echo" } ";

   echo"   document.getElementById('st_select_2').innerHTML='<select></select>';  ";
   echo"   document.getElementById('st_select_3').innerHTML='<select></select>';  ";
   echo"   document.getElementById('st_select_4').innerHTML='<select></select>';  ";

   echo"   document.getElementById('codigo_select_1').innerHTML='<br>';  ";
   echo"   document.getElementById('codigo_select_2').innerHTML='<br>';  ";
   echo"   document.getElementById('codigo_select_3').innerHTML='<br>';  ";
   echo"   document.getElementById('codigo_select_4').innerHTML='<br>';  ";

   echo"   document.getElementById('deno_select_1').innerHTML='<br>';  ";
   echo"   document.getElementById('deno_select_2').innerHTML='<br>';  ";
   echo"   document.getElementById('deno_select_3').innerHTML='<br>';  ";
   echo"   document.getElementById('deno_select_4').innerHTML='<br>';  ";

   echo"   document.getElementById('ImputacionPresupuestaria').innerHTML='<br>';  ";
   echo"   document.getElementById('ListaPresupuestaria').innerHTML='<br>';  ";
 echo'</script>';



}//fin funtion








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

function concatenaRif($vector1=null, $nomVar=null, $extra=null){
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
					$cod[$x] = $x.' - '.$y;
				}else if($x>=10 && $x<=99){
					$cod[$x] = $x.' - '.$y;
				}
			}
		}
		//print_r($cod);
	}

	$this->set($nomVar, $cod);
}//fin function



function index($var=null){

$this->verifica_entrada('80');

	 $this->layout = "ajax";

  $cod_presi                =       $this->Session->read('SScodpresi');
  $cod_entidad              =       $this->Session->read('SScodentidad');
  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
  $cod_inst                 =       $this->Session->read('SScodinst');
  $cod_dep                  =       $this->Session->read('SScoddep');
  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'  ';
  $condicion_dir_sup = "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia=".$cod_dep;
  $this->Session->delete('cod_obra');

	 $ano = $this->ano_ejecucion();

     $this->set('year_inicio', $ano);

    $dato = $this->ano_ejecucion();

	 if(!empty($dato)){
			$this->set('year', $dato);
			$this->Session->write('year_pago',$dato);
			}else{
				$this->set('year', '');
				$this->Session->write('year_pago','');
				}//fin else


		$lista =  $this->v_cepd02_contratoservicio_cuerpo->generateList($condicion.' and ano_contrato_servicio='.$ano, 'ano_contrato_servicio, numero_contrato_servicio ASC', null, '{n}.v_cepd02_contratoservicio_cuerpo.numero_contrato_servicio', '{n}.v_cepd02_contratoservicio_cuerpo.deno_numero_contrato_con_rif');
		$this->set('tipo', $lista);


		$dir_sup=$this->cugd02_direccionsuperior->generateList($condicion_dir_sup,'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
		$dir_sup = $dir_sup != null ? $dir_sup : array();
		$this->concatena($dir_sup, 'dir_superior');
		$DV=$this->cfpd02_sector->execute("SELECT ano FROM cfpd02_sector WHERE ".$this->SQLCA()." GROUP BY ano");
		foreach($DV as $dv){
			foreach($dv as $d)
				 $ve_ano[$d['ano']]=$d['ano'];
			}
			$this->set('anos',$ve_ano);

	  $this->borrar_cugd04();
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
      $this->Session->delete("items");
      $this->Session->delete("i");

}//fin index




function catProg($dirs=null, $coor=null, $secr=null, $dir=null){
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$cod_sector = $this->cugd02_direccion->field('cugd02_direccion.cod_sector', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$dirs' and cod_coordinacion='$coor' and cod_secretaria='$secr' and cod_direccion='$dir'", $order =null);
	$cod_programa = $this->cugd02_direccion->field('cugd02_direccion.cod_programa', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$dirs' and cod_coordinacion='$coor' and cod_secretaria='$secr' and cod_direccion='$dir' and cod_sector='$cod_sector'", $order =null);
	$cod_sub_prog = $this->cugd02_direccion->field('cugd02_direccion.cod_sub_prog', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$dirs' and cod_coordinacion='$coor' and cod_secretaria='$secr' and cod_direccion='$dir' and cod_sector='$cod_sector' and cod_programa='$cod_programa'", $order =null);
	$cod_proyecto = $this->cugd02_direccion->field('cugd02_direccion.cod_proyecto', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$dirs' and cod_coordinacion='$coor' and cod_secretaria='$secr' and cod_direccion='$dir' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog'", $order =null);

	$categoria = $this->zero($cod_sector).'.'.$this->zero($cod_programa).'.'.$this->zero($cod_sub_prog).'.'.$this->zero($cod_proyecto);

	echo "<script>";
	echo "document.getElementById('partida_producto').innerHTML='$categoria';";
	echo "</script>";

}




function show_rif($pista=null){
	$this->layout = "ajax";
	if($pista != null){
		$pista = strtoupper($pista);
		if($this->cpcd02->findCount("upper(denominacion) LIKE '%$pista%' OR upper(rif) LIKE '%$pista%'") > 0){
			$proveedor= $this->cpcd02->generateList($conditions = "condicion_actividad=1 and upper(denominacion) LIKE '%$pista%' OR condicion_actividad=1 and upper(rif) LIKE '%$pista%'", $order = null, $limit = null, '{n}.cpcd02.rif', '{n}.cpcd02.denominacion');
			$this->concatenaRif($proveedor, 'proveedor');
		}else{
			$this->set('msgError', 'NO SE ENCONTRO NINGUN PROVEEDOR REGISTRADO');
		}
	}
}

function show_denominacion($rif=null){
	$this->layout = "ajax";
	if($rif!= null){
		$rif = strtoupper($rif);
		$deno_rif = $this->cpcd02->field('cpcd02.denominacion', $conditions = "upper(cpcd02.rif)='$rif'", $order =null);
		$this->set('deno_rif', strtoupper($deno_rif));
	}
}



function selecion($var=null){

	$this->layout = "ajax";


  $cod_presi                =       $this->Session->read('SScodpresi');
  $cod_entidad              =       $this->Session->read('SScodentidad');
  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
  $cod_inst                 =       $this->Session->read('SScodinst');
  $cod_dep                  =       $this->Session->read('SScoddep');
  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'  ';
  $ano='';
  $pag_num = 0;
  $ano = $this->ano_ejecucion();
  $dato = $ano;
  $this->set('year_inicio', $ano);


if($var!=null && $var!='otros'){


	 $condicion_dir_sup = "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia=".$cod_dep;

    if(!empty($dato)){
			$this->set('year', $dato);
			$this->Session->write('year_pago',$dato);
			}else{
				$this->set('year', '');
				$this->Session->write('year_pago','');
				}

	        $dir_sup=$this->cugd02_direccionsuperior->generateList($condicion_dir_sup,'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
			$dir_sup = $dir_sup != null ? $dir_sup : array();
		    $this->concatena($dir_sup, 'dir_superior');

		$DV=$this->cfpd02_sector->execute("SELECT ano FROM cfpd02_sector WHERE ".$this->SQLCA()." GROUP BY ano");
		foreach($DV as $dv){
			foreach($dv as $d)
				 $ve_ano[$d['ano']]=$d['ano'];
			}


	  $lista =  $this->cscd01_catalogo->generateList('cod_tipo=2', 'codigo_prod_serv ASC', null, '{n}.cscd01_catalogo.codigo_prod_serv', '{n}.cscd01_catalogo.denominacion');
      $this->concatena($lista, 'tipo');

	  $this->set('anos',$ve_ano);
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
      $this->Session->delete("cod_catalogo");
      $this->Session->delete("items");
      $this->Session->delete("i");



if($this->cepd02_contratoservicio_cuerpo->findCount("cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_contrato_servicio=".$ano." and numero_contrato_servicio='".$var."' ")!=0){

   $array = $this->cepd02_contratoservicio_cuerpo->findAll($condicion. ' and ano_contrato_servicio = '.$ano, null,   'ano_contrato_servicio, numero_contrato_servicio  ASC', null);
  $i = 0;
   foreach($array as $aux){
 	$numero[$i]['ano_contrato_servicio']    = $aux['cepd02_contratoservicio_cuerpo']['ano_contrato_servicio'];
 	$numero[$i]['numero_contrato_servicio'] = $aux['cepd02_contratoservicio_cuerpo']['numero_contrato_servicio'];
 	$i++;
} $i--;



  for($a=0; $a<=$i; $a++){

    if($var == $numero[$a]['numero_contrato_servicio']){$pag_num = $a; $opcion='si';  break;}else{$opcion='no';}
   }//fin for

     $pag_num++;
    if($opcion=='si'){$this->consultar($ano, $pag_num);$this->render('consultar');}

}//fin if







}else if($var=='otros'){


$condicion_dir_sup = "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia=".$cod_dep;

    if(!empty($dato)){
			$this->set('year', $dato);
			$this->Session->write('year_pago',$dato);
			}else{
				$this->set('year', '');
				$this->Session->write('year_pago','');
				}

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



      $lista = $this->cfpd07_obras_cuerpo->generateList($condicion.' and ano_estimacion='.$ano, ' cod_obra ASC', null, '{n}.cfpd07_obras_cuerpo.cod_obra', '{n}.cfpd07_obras_cuerpo.denominacion');
      $this->concatena_sin_cero($lista, 'lista_numero');


	  $lista =  $this->cscd01_catalogo->generateList('cod_tipo=2', 'codigo_prod_serv ASC', null, '{n}.cscd01_catalogo.codigo_prod_serv', '{n}.cscd01_catalogo.denominacion');
      $this->concatena($lista, 'tipo');

	  $this->set('anos',$ve_ano);
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
      $this->Session->delete("cod_catalogo");
      $this->Session->delete("items");


      $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' ';
      $parametros_datos = $this->cscd04_ordencompra_parametros->findAll($condicion);
	   foreach($parametros_datos as $aux_22){
	      $anticipo_incluye_iva = $aux_22['cscd04_ordencompra_parametros']['anticipo_incluye_iva'];
	      $porcentaje_anticipo  = $aux_22['cscd04_ordencompra_parametros']['porcentaje_anticipo'];
	      $porcentaje_iva       = $aux_22['cscd04_ordencompra_parametros']['porcentaje_iva'];
	   }//fin foreach
      $this->set('porcentaje_iva_parametro', $porcentaje_iva);





}else{
$this->index();
$this->render('index');
}//fin else




}//fin function




function select_catalogo($var=null){

        $this->layout = "ajax";

	    $a = $this->cscd01_catalogo->findAll("codigo_prod_serv=".$var,array('denominacion','cod_snc'));
		$denominacion=$a[0]['cscd01_catalogo']['denominacion'];
		$cod_snc=$a[0]['cscd01_catalogo']['cod_snc'];

		$this->Session->write("cod_catalogo", $var);


		echo'<script>';
		echo"   document.getElementById('codigo_servicio2').value= \"$cod_snc\";  ";
		echo"   document.getElementById('codigo_servicio').value= \"$var\";  ";
		echo"   document.getElementById('denominacion_servicio').value= \"$denominacion\";  ";
		echo'</script>';


if( $var!=null && !empty($var)){

$cod_snc = $this->cscd01_catalogo->findAll($conditions = "cscd01_catalogo.codigo_prod_serv='$var'", $order =null);
foreach($cod_snc as $ve){$partida = $this->zero($ve['cscd01_catalogo']['cod_partida']).'.'.$this->AddCeroR2($ve['cscd01_catalogo']['cod_generica']).'.'.$this->AddCeroR2($ve['cscd01_catalogo']['cod_especifica']).'.'.$this->AddCeroR2($ve['cscd01_catalogo']['cod_sub_espec']).'.'.$this->mascara_cuatro($ve['cscd01_catalogo']['cod_auxiliar']);}//fin foreach

echo'<script>';
      echo' document.getElementById("partida_producto2").innerHTML = ".'.$partida.'"; ';
echo'</script>';


	}else{

		$this->set('cod_snc', '');
echo'<script>';
      echo' document.getElementById("partida_producto").innerHTML = ""; ';
echo'</script>';

	}//fin else


}//fin function





function selecion_despues_guardar($var=null){

  $this->layout = "ajax";
  $cod_presi                =       $this->Session->read('SScodpresi');
  $cod_entidad              =       $this->Session->read('SScodentidad');
  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
  $cod_inst                 =       $this->Session->read('SScodinst');
  $cod_dep                  =       $this->Session->read('SScoddep');
  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'  ';
  $condicion_dir_sup = "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia=".$cod_dep;

	 $ano = $this->ano_ejecucion();
     $dato = $this->ano_ejecucion();


     $this->set('year_inicio', $ano);


	 if(!empty($dato)){
			$this->set('year', $dato);
			$this->Session->write('year_pago',$dato);
			}else{
				$this->set('year', '');
				$this->Session->write('year_pago','');
				}//fin else


		$lista =  $this->cepd02_contratoservicio_cuerpo->generateList($condicion.' and ano_contrato_servicio='.$ano, 'ano_contrato_servicio, numero_contrato_servicio ASC', null, '{n}.cepd02_contratoservicio_cuerpo.numero_contrato_servicio', '{n}.cepd02_contratoservicio_cuerpo.numero_contrato_servicio');
		$this->AddCero('tipo', $lista);

  $this->set('ano_contrato_servicio', $this->data["cepp02_contratoservicio"]["ano"]);
  $this->set('numero_contrato_servicio', $this->data["cepp02_contratoservicio"]["numero_contrato"]);




}//fin function







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
		$cond  ="cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$this->verifica_SS(5);
		$cond2 ="cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$this->verifica_SS(5);
	switch($select){
		case 'coordinacion':
			$this->set('SELECT','secretaria');
			$this->set('codigo','coordinacion');
			$this->set('seleccion','');
			$this->set('n',2);
			$this->Session->write('cod_1',$var);
			$this->Session->delete('cod_2');
            $this->Session->delete('cod_3');
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
            $this->Session->delete('cod_3');
			$cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$var;
			$lista=  $this->cugd02_secretaria->generateList($cond, 'cod_secretaria ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
			$this->concatena($lista, 'vector');
		break;
		case 'direccion':
			$this->set('SELECT','catalogo');
			$this->set('codigo','direccion');
			$this->set('seleccion','');
			$this->set('n',4);
			$cod_1 =  $this->Session->read('cod_1');
			$cod_2 =  $this->Session->read('cod_2');
	    	$this->Session->write('cod_3',$var);
			$this->set('no', 'no');
			$this->set('otro', 'otro');
			$cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$var;
			$lista = $this->cugd02_direccion->generateList($cond, 'cod_direccion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
			$this->concatena($lista, 'vector');

		break;
		/*case 'catalogo':

		   $this->Session->write('cod_3',$var);

		break; */
	}
	}else{
		echo "";
	}
}//fin select ubicacion administrativa





function input_para_catalogo($var=null){
    $this->layout="ajax";

	$this->Session->write('cod_4', $var);

	$cod_1 =  $this->Session->read('cod_1');
	$cod_2 =  $this->Session->read('cod_2');
	$cod_3 =  $this->Session->read('cod_3');
	$this->catProg($cod_1, $cod_2, $cod_3, $var);


}//fin function


















function ver_disponibilidad($i=null, $a=null, $b=null){
	$this->layout="ajax";

// echo $_SESSION["items"][$i][11].'<br>';

$b = $this->Formato1($b);
$a = $this->Formato1($a);

if($b>$a){
       $this->set('msg_error', 'El monto es mayor al saldo');
       $this->set('i', $i);
         $_SESSION["items"][$i][11] = '0.00';
    }else{
    	 $_SESSION["items"][$i][11] = $b;
}//fin else


$vec=$_SESSION["items"];
$monto=0;
$opcion = "si";


for($z=0;$z<count($vec);$z++){
  if($vec[$z]['id']=="no" && $vec[$z]['id']!="0"){}else{
       $monto    +=     $this->Formato1($vec[$z][11]);
  	}//fin else
}//fin for

echo "<script>cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', ".$monto.")</script>";



}//fin function







function agregar_partidas2(){

$this->layout="ajax";


  $cod_presi                =       $this->Session->read('SScodpresi');
  $cod_entidad              =       $this->Session->read('SScodentidad');
  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
  $cod_inst                 =       $this->Session->read('SScodinst');
  $cod_dep                  =       $this->Session->read('SScoddep');
  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'  ';


$var = $this->Session->read('cod_obra');
$ano = $this->Session->read('ano_obra');

  $this->Session->delete("items");
  $this->Session->delete("i");

$opcion = "si";
$cfpd07_obras_partidas =  $this->cfpd07_obras_partidas->findAll($condicion." and ano_estimacion=".$ano." and cod_obra='".$var."' ");

foreach($cfpd07_obras_partidas  as  $aux_cfpd07_obras_partidas){

  $cod_presi           =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_presi'];
  $cod_entidad         =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_entidad'];
  $cod_tipo_inst       =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_tipo_inst'];
  $cod_inst            =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_inst'];
  $cod_dep             =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_dep'];
  $ano_estimacion      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['ano_estimacion'];
  $cod_obra            =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_obra'];
  $cod_sector          =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sector'];
  $cod_programa        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_programa'];
  $cod_sub_prog        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_prog'];
  $cod_proyecto        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_proyecto'];
  $cod_activ_obra      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_activ_obra'];
  $cod_partida         =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_partida'];
  $cod_generica        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_generica'];
  $cod_especifica      =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_especifica'];
  $cod_sub_espec       =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_espec'];
  $cod_auxiliar        =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_auxiliar'];
  $monto               =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['monto'];
  $monto_contratado    =   $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['monto_contratado'];


 $sql_verificar  = " cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=1 and ano=".$ano_estimacion;
 $sql_verificar .= " and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra;
 $sql_verificar .= " and cod_partida=".$this->AddCeroR($cod_partida)." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."";

$concate  = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_partida'].'.'.$this->AddCeroR($aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_generica']).'.'.$this->AddCeroR($aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_especifica']).'.'.$this->AddCeroR($aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_espec']);
$concate2 = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_partida'];

//echo $concate.'-----'.$concate2.'<br>';

if($concate!="403.18.01.00" && $concate2=="403"){

//echo $sql_verificar.'<br><br>';

  if($this->cfpd05->findCount($sql_verificar)!=0){

    $disponibilidad = $this->disponibilidad($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
    $monto2 = $monto - $monto_contratado;
	          if($disponibilidad=="0.00" || $disponibilidad<$monto2){$opcion="no";


}else{



	        $cod[0]=$ano_estimacion;
			$cod[1]=$cod_sector;
			$cod[2]=$cod_programa;
			$cod[3]=$cod_sub_prog;
			$cod[4]=$cod_proyecto;
			$cod[5]=$cod_activ_obra;
			$cod[6]=$cod_partida;
			if($cod[6]<9){
				$cod[6]="40".$cod[6];
			}else if($cod[6]<100){
				$cod[6]="4".$cod[6];
			}else{
				$cod[6]=$cod[6];
			}

			$cod[7]=$cod_generica;
			$cod[8]=$cod_especifica;
			$cod[9]=$cod_sub_espec;
			$cod[10]=$cod_auxiliar;//
			$cod[10]=$cod[10]<9?str_replace("0","",$cod[10]):$cod[10];
			$cod[10]=$cod[10]<9?"0".$cod[10]:$cod[10];
			$cod[11]= $this->Formato2( $monto - $monto_contratado);
			$cod[12]= $this->Formato2( $monto - $monto_contratado);
		    if(isset($_SESSION["i"])){
			$i=$this->Session->read("i")+1;
			$this->Session->write("i",$i);
	    }else{
		   $this->Session->write("i",0);
			$i=0;
		}

   $this->trafico($ano_estimacion, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);

                     $vec[$i][0]=$ano_estimacion;
					 $vec[$i][1]=$cod_sector;
					 $vec[$i][2]=$cod_programa;
					 $vec[$i][3]=$cod_sub_prog;
					 $vec[$i][4]=$cod_proyecto;
					 $vec[$i][5]=$cod_activ_obra;
					 $vec[$i][6]=$cod_partida;//<9 ? "4.0".$cod_partida : "4.".$cod_partida;
					 $vec[$i][7]=$cod_generica;
					 $vec[$i][8]=$cod_especifica;
					 $vec[$i][9]=$cod_sub_espec;
					 $vec[$i][10]=$this->mascara_cuatro($cod_auxiliar);
					 $vec[$i][11]=$this->Formato2($monto - $monto_contratado);
					 $vec[$i][12]=$this->Formato2($monto - $monto_contratado);
					 $vec[$i][13]="obra";
					 $vec[$i]["id"]=$i;
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




	          }//fin else
	}//fin if contador
  }//fin if para solo 403
}//fin foreach











if($opcion=="no"){
	      $this->set('msg_error', 'Lo siento una de las partidas no tiene disponibilidad');
	      echo'<script>';
	    	echo"document.getElementById('guardar').disabled=true;";
	    	echo"document.getElementById('consultar').disabled=false;";
		  echo'</script>';
}//fin if




}//fin function








function mostrar_catalogo($var=null){
 	$this->layout="ajax";
 	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');



 	$dirs =  $this->Session->read('cod_1');
	$coor =  $this->Session->read('cod_2');
	$secr =  $this->Session->read('cod_3');
	$dir =  $this->Session->read('cod_4');

	$cod_sector = $this->cugd02_direccion->field('cugd02_direccion.cod_sector', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$dirs' and cod_coordinacion='$coor' and cod_secretaria='$secr' and cod_direccion='$dir'", $order =null);
	$cod_programa = $this->cugd02_direccion->field('cugd02_direccion.cod_programa', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$dirs' and cod_coordinacion='$coor' and cod_secretaria='$secr' and cod_direccion='$dir' and cod_sector='$cod_sector'", $order =null);
	$cod_sub_prog = $this->cugd02_direccion->field('cugd02_direccion.cod_sub_prog', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$dirs' and cod_coordinacion='$coor' and cod_secretaria='$secr' and cod_direccion='$dir' and cod_sector='$cod_sector' and cod_programa='$cod_programa'", $order =null);
	$cod_proyecto = $this->cugd02_direccion->field('cugd02_direccion.cod_proyecto', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$dirs' and cod_coordinacion='$coor' and cod_secretaria='$secr' and cod_direccion='$dir' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog'", $order =null);
	//echo $conditions;
	//echo 'cod_sector = '.$cod_sector.' and cod_programa = '.$cod_programa.' and cod_sub_prog = '.$cod_sub_prog.' and cod_proyecto = '.$cod_proyecto;
 	//$Lista = $this->Cnmd01->generateList($this->SQLCA(), 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
 	if($var != null){
 		$var = strtoupper($var);
 		//echo 'la pista es: '.$var;
 		if($this->v_cscd01_catalogo_deno_und->findCount($this->condicion()."   and ano='".$this->ano_ejecucion()."'  and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and upper(denominacion) LIKE '%$var%'") != 0){
 			$this->set('deno', $var);
 			$catalogo= $this->v_cscd01_catalogo_deno_und->generateList($this->condicion()."   and ano='".$this->ano_ejecucion()."'  and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and upper(denominacion) LIKE '%$var%'", 'cod_snc ASC', null, '{n}.v_cscd01_catalogo_deno_und.codigo_prod_serv', '{n}.v_cscd01_catalogo_deno_und.denominacion');
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
 			$this->set('notfound', 'LA PARTIDA A LA QUE PERTENECE ESTE PRODUCTO NO FUE PRESUPUESTADA - POR FAVOR INTENTE DE NUEVO');

 		}
 		//print_r($catalogo);
 	}


echo'<script>';
       echo "if(document.getElementById('num_1')){document.getElementById('num_1').disabled=true;} ";
       echo "if(document.getElementById('input_cod_obra')){document.getElementById('input_cod_obra').disabled=true;} ";
echo'</script>';




 }///fin else




















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
			break;
			case 'catalogo':
		$a=  $this->cscd01_catalogo->findAll("codigo_prod_serv=".$var);
				$x= $a[0]['cscd01_catalogo']['denominacion'];
				$this->set("deno",$x);
			break;
	}//fin switch
		}else{
//			echo "";
			$this->set("deno","&nbsp;");
		}

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
				 $this->set("codigo",$var);
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
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_sector ASC', null, '{n}.v_cfpd05_denominaciones.cod_sector', '{n}.v_cfpd05_denominaciones.deno_sector');
			$this->concatena($lista, 'vector');
		break;
		case 'programa':

			$this->set('SELECT','subprograma');
			$this->set('codigo','programa');
			$this->set('seleccion','');
			$this->set('n',2);
			$year_pago=$this->ano_ejecucion();
			$this->Session->write('ano',$year_pago);
			$this->Session->write('sec',$var);
			$cond .=" and ano=".$year_pago." and cod_sector=".$var;
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
			$this->set('seleccion',null);
			$this->set('n',10);
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
			$cond2 =$cond." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$activ." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond." and ".$cond2, 'cod_auxiliar ASC', null, '{n}.v_cfpd05_denominaciones.cod_auxiliar', '{n}.v_cfpd05_denominaciones.deno_auxiliar');

						if($lista!=null){
							$this->concatena_auxiliar($lista, 'vector');
						}else{
							$this->set('vector',array('0'=>'00'));

						}
         //echo "muestra";
		break;
		case 'auxiliar2':

			$this->set('SELECT','escribir_aux');
			$this->set('codigo','auxiliar');
			$this->set('seleccion','');
			$this->set('n',10);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy =  $this->Session->read('proy');
			$cpar =  $this->Session->read('cpar');
			$cgen =  $this->Session->read('cgen');
			$cesp =  $this->Session->read('cesp');
			$this->Session->write('actividad',$var);
			$f=$this->Session->read('CodigosDireccion');
			$p=$this->Session->read('partidas');

			$cond2 =$cond." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$var." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$p[0]["cscd01_catalogo"]["cod_sub_espec"];
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_auxiliar ASC', null, '{n}.v_cfpd05_denominaciones.cod_auxiliar', '{n}.v_cfpd05_denominaciones.deno_auxiliar');

						if($lista!=null){
							$this->concatena_auxiliar($lista, 'vector');

						}else{
							$this->set('vector',array('0'=>'00'));
							$disponibilidad = $this->disponibilidad($_SESSION["ano"], $_SESSION["sec"], $_SESSION["prog"], $_SESSION["subp"], $_SESSION["proy"], $_SESSION["actividad"], $_SESSION["cpar"], $_SESSION["cgen"], $_SESSION["cesp"], $_SESSION["csesp"], 0);

				       echo "<script>" .
				 		"document.getElementById('td_disponibilidad').innerHTML='".$this->Formato2($disponibilidad)."'; " .
				 		"</script>";
						}
		break;
		case 'escribir_aux':
				 $this->Session->write('auxiliar', $var);
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
		$v=$this->cepd01_compromiso_numero->execute("SELECT numero_compromiso FROM cepd01_compromiso_numero WHERE ".$this->SQLCA()." and ano_compromiso=".$this->ano_ejecucion()." ORDER BY numero_compromiso DESC");
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








function valida_numero($year=null, $var=null){
   $this->layout="ajax";

  $cod_presi                =       $this->Session->read('SScodpresi');
  $cod_entidad              =       $this->Session->read('SScodentidad');
  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
  $cod_inst                 =       $this->Session->read('SScodinst');
  $cod_dep                  =       $this->Session->read('SScoddep');
  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'  ';

$var = str_replace(" ", "", $var);
$var = trim($var);
$var = str_replace(",", "-", $var);
$var = str_replace(";", "-", $var);
$var = strtoupper($var);

if($this->cepd02_contratoservicio_cuerpo->findCount("cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_contrato_servicio=".$year." and numero_contrato_servicio='".$var."' ")>=1){
   $this->set('errorMessage', 'El N&uacute;mero de servicio  ya existe');
}//fin


   echo "<script>document.getElementById('numero_contrato').value='".$var."'</script>";




}//fin function





function agregar_partidas($var=null) {
	$this->layout="ajax";
	if(isset($var) && !empty($var)){
            $cod[0]=$this->data["cepp02_contratoservicio"]["ano_partidas"];
			$cod[1]=$this->data["cepp02_contratoservicio"]["cod_sector"];
			$cod[2]=$this->data["cepp02_contratoservicio"]["cod_programa"];
			$cod[3]=$this->data["cepp02_contratoservicio"]["cod_subprograma"];
			$cod[4]=$this->data["cepp02_contratoservicio"]["cod_proyecto"];
			$cod[5]=$this->data["cepp02_contratoservicio"]["cod_actividad"];
			$cod[6]=$this->data["cepp02_contratoservicio"]["cod_partida"];
			if($cod[6]<9){
				$cod[6]="40".$cod[6];
			}else if($cod[6]<100){
				$cod[6]="4".$cod[6];
			}else{
				$cod[6]=$cod[6];
			}

			$cod[7]=$this->data["cepp02_contratoservicio"]["cod_generica"];
			$cod[8]=$this->data["cepp02_contratoservicio"]["cod_especifica"];
			$cod[9]=$this->data["cepp02_contratoservicio"]["cod_subespecifica"];
			$cod[10]=$this->data["cepp02_contratoservicio"]["cod_auxiliar"];//
			$cod[10]=$cod[10]<9?str_replace("0","",$cod[10]):$cod[10];
			$cod[10]=$cod[10]<9?"0".$cod[10]:$cod[10];
			$cod[11]=$this->data["cepp02_contratoservicio"]["monto_partidas"];
		    if(isset($_SESSION["i"])){
			$i=$this->Session->read("i")+1;
			$this->Session->write("i",$i);
	    }else{
		   $this->Session->write("i",0);
			$i=0;
		}
        switch($var){
        	case 'normal':
					 $vec[$i][0]=$this->data["cepp02_contratoservicio"]["ano_partidas"];
					 $vec[$i][1]=$this->data["cepp02_contratoservicio"]["cod_sector"];
					 $vec[$i][2]=$this->data["cepp02_contratoservicio"]["cod_programa"];
					 $vec[$i][3]=$this->data["cepp02_contratoservicio"]["cod_subprograma"];
					 $vec[$i][4]=$this->data["cepp02_contratoservicio"]["cod_proyecto"];
					 $vec[$i][5]=$this->data["cepp02_contratoservicio"]["cod_actividad"];
					 $vec[$i][6]=$this->data["cepp02_contratoservicio"]["cod_partida"];//<9 ? "4.0".$this->data["cepp02_contratoservicio"]["cod_partida"] : "4.".$this->data["cepp02_contratoservicio"]["cod_partida"];
					 $vec[$i][7]=$this->data["cepp02_contratoservicio"]["cod_generica"];
					 $vec[$i][8]=$this->data["cepp02_contratoservicio"]["cod_especifica"];
					 $vec[$i][9]=$this->data["cepp02_contratoservicio"]["cod_subespecifica"];
					 $vec[$i][10]=$this->mascara_cuatro($this->data["cepp02_contratoservicio"]["cod_auxiliar"]);
					 $vec[$i][11]=$this->data["cepp02_contratoservicio"]["monto_partidas"];
                     $vec[$i][12]=$i;
                     $vec[$i][13]="servicio";
					 $vec[$i]["id"]=$i;
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
				            $this->set('errorMessage', 'Los códigos seleccionados ya existen en la lista');
                        }else{
                        	$_SESSION["items"]=$_SESSION["items"]+$vec;
                          //  echo "si";
                        }
					 }else{
						$_SESSION["items"]=$vec;
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
					 $vec[$i][12]=$i;
					 $vec[$i][13]="servicio";
					 $vec[$i]["id"]=$i;
					 if(isset($_SESSION["items"])){
						foreach($_SESSION["items"] as $codi){
							//echo $codi[0].$cod[0].$codi[1].$cod[1].$codi[2].$cod[2].$codi[3].$cod[3].$codi[4].$cod[4].$codi[5].$cod[5].$codi[6].$cod[6].$codi[7].$cod[7]. $codi[8].$cod[8].$codi[9].$cod[9].$codi[10].$cod[10];

            	           if( $codi[0]==$cod[0] && $codi[1]==$cod[1] && $codi[2]==$cod[2] && $codi[3]==$cod[3] && $codi[4]==$cod[4] && $codi[5]==$cod[5] && $codi[6]==$cod[6] && $codi[7]==$cod[7] && $codi[8]==$cod[8] && $codi[9]==$cod[9] && $codi[10]==$cod[10]){
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
        	break;

        }//fin switch


		}//
}//fin funcu¡ions








function eliminar_items ($id) {
	$this->layout = "ajax";
	$_SESSION["items"][$id]=null;

$vec=$_SESSION["items"];
$monto=0;

for($z=0;$z<count($vec);$z++){if($vec[$z][0]!=null){$monto +=  $this->Formato1($vec[$z][11]);}}//fin for


   echo "<script>cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', ".$monto.")</script>";


}//fin else





function limpiar_lista () {
	$this->layout = "ajax";
	$this->Session->delete("items");
	$this->Session->delete("i");
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





function buscar_year($var1=null){
  $this->layout = "ajax";
  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');

if($SScoddep==1 && $SScoddeporig==1){
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
}else{$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';}


  $lista = $this->cepd02_contratoservicio_cuerpo->generateList($condicion.' and ano_contrato_servicio='.$var1, ' numero_contrato_servicio ASC', null, '{n}.cepd02_contratoservicio_cuerpo.numero_contrato_servicio', '{n}.cepd02_contratoservicio_cuerpo.numero_contrato_servicio');
   if($lista==""){$lista = array(''=>'');}
   $this->set('obras', $lista);
}//fin function






function consulta_form($var1=null){

  $this->layout = "ajax";
  $pag_num = 0;
  $opcion = 'no';
  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');
  $ano = $this->ano_ejecucion();


if(!empty($this->data['cepp02_contratoservicio']['ano_contrato'])){	$_SESSION['ano_contrato_servicio'] = $this->data['cepp02_contratoservicio']['ano_contrato'];}else{$_SESSION['ano_contrato_servicio'] = $this->ano_ejecucion();}


if($var1!=null){

  if($var1=='si'){

 $ano = $_SESSION['ano_contrato_servicio'];

  if($SScoddep==1 && $SScoddeporig==1){
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
}else{
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
}//fin else


if(!empty($this->data['cepp02_contratoservicio']['numero_contrato_servicio'])){


      	                $this->consulta_form_2($ano, $this->data['cepp02_contratoservicio']['numero_contrato_servicio']);
      	                $this->render('consultar');



 }else{

	   $array = $this->cepd02_contratoservicio_cuerpo->findAll($condicion. "  and ano_contrato_servicio = ".$ano , null, 'ano_contrato_servicio, numero_contrato_servicio ASC', null);
  $i = 0;
   foreach($array as $aux){
	 	$numero[$i]['ano_orden_compra'] = $aux['cepd02_contratoservicio_cuerpo']['ano_contrato_servicio'];
	 	$numero[$i]['numero_orden_compra'] = $aux['cepd02_contratoservicio_cuerpo']['numero_contrato_servicio'];
	 	//$numero[$i]['numero_modificacion'] = $aux['cepd02_contratoservicio_cuerpo']['numero_modificacion'];
	 	$i++;
	} $i--;


	}

  }//fin if
}//fin if

$this->set('entidad_federal', $this->Session->read('entidad_federal'));




if($SScoddep==1 && $SScoddeporig==1){
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
}else{$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';}


 $lista = $this->v_cepd02_contratoservicio_cuerpo->generateList($condicion.' and ano_contrato_servicio='.$_SESSION['ano_contrato_servicio'], ' numero_contrato_servicio ASC', null, '{n}.v_cepd02_contratoservicio_cuerpo.numero_contrato_servicio', '{n}.v_cepd02_contratoservicio_cuerpo.deno_numero_contrato_con_rif');
 if($lista==""){$lista = array(''=>'');}
 $this->set('lista_numero', $lista);
 $this->set('ano_contrato_servicio', $_SESSION['ano_contrato_servicio']);





}//fin function















function consulta_form_2($ano=null, $var1=null) {
	$this->layout="ajax";
  $cod_presi                =       $this->Session->read('SScodpresi');
  $cod_entidad              =       $this->Session->read('SScodentidad');
  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
  $cod_inst                 =       $this->Session->read('SScodinst');
  $cod_dep                  =       $this->Session->read('SScoddep');
  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');


if($SScoddep==1 && $SScoddeporig==1){
    $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
}else{$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';}


	if(isset($pagina)){
				$pagina=$pagina;
				$this->Session->delete('MSJ');
		}else{
				 $pagina=1;
		}//fin else

$Ano = $ano;

						 $this->set('ano',$Ano);
						 $this->set('ejercicio', $this->ano_ejecucion());
						 $Tfilas=$this->cepd02_contratoservicio_cuerpo->findCount($condicion." and ano_contrato_servicio=".$Ano." and  numero_contrato_servicio='".$var1."' ");
						 //echo $Tfilas;

						 $this->set('pag_cant',$pagina.'/'.$Tfilas);
						 $this->set('ultimo',$Tfilas);
						 $dataCompromiso=$this->cepd02_contratoservicio_cuerpo->findAll($condicion." and ano_contrato_servicio=".$Ano." and  numero_contrato_servicio='".$var1."' " ,null,'ano_contrato_servicio,numero_contrato_servicio ASC',1,$pagina,null);
						 foreach ($dataCompromiso as $YYY){

						 $a = $this->cscd01_catalogo->findAll("codigo_prod_serv=".$YYY['cepd02_contratoservicio_cuerpo']['codigo_prod_serv'],array('denominacion'));
						 $denominacion=$a[0]['cscd01_catalogo']['denominacion'];
								 //$YYY['cfpd05']['cod_sector'];
							 //////////////////////////////////////////////////////////////////////////
								$cond = "cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$YYY['cepd02_contratoservicio_cuerpo']['cod_dep'];
								$cond1=" and cod_dir_superior=".$YYY['cepd02_contratoservicio_cuerpo']['cod_dir_superior'];
						$a1=  $this->cugd02_direccionsuperior->findAll($cond.$cond1);
								$x1= $a1[0]['cugd02_direccionsuperior']['denominacion'];
								$this->set('dir_sup',$x1);
								$cond2=" and cod_dir_superior=".$YYY['cepd02_contratoservicio_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$YYY['cepd02_contratoservicio_cuerpo']['cod_coordinacion'];
						$a2=  $this->cugd02_coordinacion->findAll($cond.$cond2);
								$x2= $a2[0]['cugd02_coordinacion']['denominacion'];
								$this->set('coordinacion',$x2);
 				$cond3=" and cod_dir_superior=".$YYY['cepd02_contratoservicio_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$YYY['cepd02_contratoservicio_cuerpo']['cod_coordinacion']." and cod_secretaria=".$YYY['cepd02_contratoservicio_cuerpo']['cod_secretaria'];
						$a3=  $this->cugd02_secretaria->findAll($cond.$cond3);
								$x3= $a3[0]['cugd02_secretaria']['denominacion'];
								$this->set('secretaria',$x3);
								$cond4=" and cod_dir_superior=".$YYY['cepd02_contratoservicio_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$YYY['cepd02_contratoservicio_cuerpo']['cod_coordinacion']." and  cod_secretaria=".$YYY['cepd02_contratoservicio_cuerpo']['cod_secretaria']." and cod_direccion=".$YYY['cepd02_contratoservicio_cuerpo']['cod_direccion'];
						$a4=  $this->cugd02_direccion->findAll($cond.$cond4);
								$x4= $a4[0]['cugd02_direccion']['denominacion'];
								$this->set('direccion',$x4);
								//$tipo_doc=$this->cepd01_tipo_compromiso->findAll("cod_tipo_compromiso=".$YYY['cepd02_contratoservicio_cuerpo']['cod_tipo_compromiso']);

								//$this->set("tipo_doc",$tipo_doc[0]["cepd01_tipo_compromiso"]["denominacion"]);
								$this->traer_beneficiario($YYY['cepd02_contratoservicio_cuerpo']['rif']);
								$compromiso_partidas=$this->cepd02_contratoservicio_partidas->findAll($condicion." and ano_contrato_servicio=".$Ano." and numero_contrato_servicio='".$YYY['cepd02_contratoservicio_cuerpo']['numero_contrato_servicio']."' ");


								//$C_A=$this->cugd03_acta_anulacion_cuerpo->findByNumero_acta_anulacion($YYY['cepd02_contratoservicio_cuerpo']['numero_anulacion']);
								$C_A=$this->cugd03_acta_anulacion_cuerpo->findAll($condicion." and numero_acta_anulacion=".$YYY['cepd02_contratoservicio_cuerpo']['numero_anulacion']." and ano_acta_anulacion=".$Ano);
                                //print_r($C_A);
                                if($C_A!=null){
                                	$this->set("concepto_anulacion",$C_A[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"]);
                                }else{
                                	$this->set("concepto_anulacion","");
                                }



                                $C_B=$this->cscd01_catalogo->findAll("codigo_prod_serv=".$YYY['cepd02_contratoservicio_cuerpo']['codigo_prod_serv']);
                                if($C_B!=null){
                                	$this->set("snc",$C_B[0]["cscd01_catalogo"]["cod_snc"]);
                                }else{
                                	$this->set("snc","");
                                }

						 }



								$this->set('COMPROMISO_PARTIDA',$compromiso_partidas);



								$this->set('COMPROMISO',$dataCompromiso);
								$this->set('denominacion',$denominacion);
								$this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);









}//fin consultar










function guardar($pagina=null){
	$this->layout="ajax";


	if(isset($this->data["cepp02_contratoservicio"])){

$R2 = 0;
$R3 = 0;



  $cod_presi                               =       $this->Session->read('SScodpresi');
  $cod_entidad                             =       $this->Session->read('SScodentidad');
  $cod_tipo_inst                           =       $this->Session->read('SScodtipoinst');
  $cod_inst                                =       $this->Session->read('SScodinst');
  $cod_dep                                 =       $this->Session->read('SScoddep');


  $ano_contrato_servicio                   =     $this->data["cepp02_contratoservicio"]["ano"];
  $numero_contrato_servicio                =     $this->data["cepp02_contratoservicio"]["numero_contrato"];
 if(isset($this->data["cepp02_contratoservicio"]["codigo_servicio"])){
  if($this->data["cepp02_contratoservicio"]["codigo_servicio"]==""){
  	  $this->data["cepp02_contratoservicio"]["codigo_servicio"] = $this->Session->read('codigo_prod_serv');
  }//fin if
 }else{
  	  $this->data["cepp02_contratoservicio"]["codigo_servicio"] = $this->Session->read('codigo_prod_serv');
 }///fin else

  $codigo_prod_serv                        =     $this->data["cepp02_contratoservicio"]["codigo_servicio"];
  $cod_dir_superior                        =     $this->data["cepp02_contratoservicio"]["cod_dir_superior"];
  $cod_coordinacion                        =     $this->data["cepp02_contratoservicio"]["cod_coordinacion"];
  $cod_secretaria                          =     $this->data["cepp02_contratoservicio"]["cod_secretaria"];
  $cod_direccion                           =     $this->data["cepp02_contratoservicio"]["cod_direccion"];
  $fecha_contrato_servicio                 =     $this->Cfecha($this->data["cepp02_contratoservicio"]["fecha_contrato"], 'A-M-D');
  $fecha_inicio_contrato                   =     $this->Cfecha($this->data["cepp02_contratoservicio"]["fecha_inicio"], 'A-M-D');
  $fecha_terminacion_contrato              =     $this->Cfecha($this->data["cepp02_contratoservicio"]["fecha_terminacion"], 'A-M-D');
  $monto_original_contrato                 =     "";
  $fecha_contrato_servicio2                =     $this->data["cepp02_contratoservicio"]["fecha_contrato"];
  $tiene_iva                               =     $this->data["cepp02_contratoservicio"]["tiene_iva"];
  $porcentaje_iva_parametro                =     $this->Formato1($this->data["cepp02_contratoservicio"]["porcentaje_iva_parametro"]);

  $rif                                     =     $this->data["cepp02_contratoservicio"]["rif"];
  $concepto                                =     $this->data["cepp02_contratoservicio"]["concepto"];

    $resultado = strpos("CONTRATISTA:", $concepto);
	if($resultado==FALSE){
		$rif = strtoupper($rif);
  		$contratista = $this->cpcd02->field('cpcd02.denominacion', $conditions = "upper(cpcd02.rif)='$rif'", $order =null);
  		$concepto="CONTRATISTA: ".$contratista." DENOMINACIÓN DEL SERVICIO: ".$concepto;
	}


if($tiene_iva==2){
  $porcentaje_iva_parametro = 0;
}


if(isset($ano_contrato_servicio)){
if(isset($numero_contrato_servicio)){
if(isset($codigo_prod_serv)){
if(isset($cod_dir_superior)){
if(isset($cod_coordinacion)){
if(isset($cod_secretaria)){
if(isset($cod_direccion)){
if(isset($rif)){
if(isset($concepto)){
if(isset($fecha_contrato_servicio)){
if(isset($fecha_inicio_contrato)){
if(isset($fecha_terminacion_contrato)){
if(isset($fecha_contrato_servicio2)){






  $aux = $fecha_contrato_servicio2[6].$fecha_contrato_servicio2[7].$fecha_contrato_servicio2[8].$fecha_contrato_servicio2[9];
  if($aux!=$ano_contrato_servicio){$fecha_contrato_servicio2 = date("d/m/Y"); }

  $aumento                                 =     "0";
  $disminucion                             =     "0";
  $monto_anticipo                          =     "0";
  $monto_amortizacion                      =     "0";
  $monto_retencion_laboral                 =     "0";
  $monto_retencion_fielcumplimiento        =     "0";
  $monto_cancelado                         =     "0";
  $porcentaje_iva                          =     "0";
  $porcentaje_anticipo                     =     "0";
  $anticipo_con_iva                        =     "0";



  $fecha_proceso_registro                 =   date("d/m/Y");
  $dia_asiento_registro                   =   0;
  $mes_asiento_registro                   =   0;
  $ano_asiento_registro                   =   0;
  $numero_asiento_registro                =   "0";
  $username_registro                      =   $_SESSION['nom_usuario'];
  $condicion_actividad                    =   "1";
  $ano_anulacion                          =   "0";
  $numero_anulacion                       =   "0";
  $dia_asiento_anulacion                  =   "0";
  $mes_asiento_anulacion                  =   "0";
  $ano_asiento_anulacion                  =   "0";
  $numero_asiento_anulacion               =   "0";
  $fecha_proceso_anulacion                =   $this->Cfecha("01/01/1900", 'A-M-D');
  $username_anulacion                     =   "0";


  $laboral_cancelado                      =     "0";
  $fielcumplimiento_cancelado             =     "0";


  $anticipo                                =           "0";
  $amortizacion                            =           "0";
  $retencion_laboral                       =           "0";
  $retencion_fielcumplimiento              =           "0";
  $cancelacion                             =           "0";
  $numero_control_compromiso               =           "0";


$sw1 = 0;
$sw2 = 0;
$sw3 = 0;
$sw4 = 0;
$sw5 = 0;


  $camposT3="cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_contrato_servicio, numero_contrato_servicio, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, monto, aumento, disminucion, anticipo, amortizacion, retencion_laboral, retencion_fielcumplimiento, cancelacion, numero_control_compromiso";
  $values="";


  $vec=$_SESSION["items"];
  $monto  = 0;
  $monto2 = 0;
  $values = "";
  $monto_original_contrato_aux = 0;

for($z=0;$z<count($vec);$z++){
if($vec[$z][0]!=null){
  $ano                                     =           $vec[$z][0];
  $cod_sector                              =           $vec[$z][1];
  $cod_programa                            =           $vec[$z][2];
  $cod_sub_prog                            =           $vec[$z][3];
  $cod_proyecto                            =           $vec[$z][4];
  $cod_activ_obra                          =           $vec[$z][5];
  $cod_partida                             =           $vec[$z][6];
  $cod_generica                            =           $vec[$z][7];
  $cod_especifica                          =           $vec[$z][8];
  $cod_sub_espec                           =           $vec[$z][9];
  $cod_auxiliar                            =           $vec[$z][10];
  $monto                                   =           $this->Formato1($vec[$z][11]);

$monto_original_contrato_aux += $monto;

	}//fin for
}//fin for


$pregunta_ejercicio       =       $this->data['cepp02_contratoservicio']['pregunta_ejercicio'];




//////////////////////////////////////////GUARDAR CUERPO/////////////////////////////////////////////////
$monto_original_contrato = $monto_original_contrato_aux;
$camposT2="cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_contrato_servicio, numero_contrato_servicio, codigo_prod_serv, cod_dir_superior, cod_coordinacion, cod_secretaria, cod_direccion, rif, concepto, fecha_contrato_servicio, fecha_inicio_contrato, fecha_terminacion_contrato, monto_original_contrato, aumento, disminucion, monto_anticipo, monto_amortizacion, monto_retencion_laboral, monto_retencion_fielcumplimiento, monto_cancelado, porcentaje_iva, porcentaje_anticipo, anticipo_con_iva, fecha_proceso_registro, dia_asiento_registro, mes_asiento_registro, ano_asiento_registro, numero_asiento_registro, username_registro, condicion_actividad, ano_anulacion, numero_anulacion, dia_asiento_anulacion, mes_asiento_anulacion, ano_asiento_anulacion, numero_asiento_anulacion, fecha_proceso_anulacion, username_anulacion, laboral_cancelado, fielcumplimiento_cancelado, saldo_ano_anterior";
if($this->cepd02_contratoservicio_cuerpo->findCount("cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_contrato_servicio=".$ano_contrato_servicio." and numero_contrato_servicio='".$numero_contrato_servicio."' ")==0){
 $R2=$this->cepd02_contratoservicio_cuerpo->execute("  BEGIN;  INSERT INTO cepd02_contratoservicio_cuerpo (".$camposT2.") VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$ano_contrato_servicio."', '".strtoupper($numero_contrato_servicio)."', '".$codigo_prod_serv."', '".$cod_dir_superior."', '".$cod_coordinacion."', '".$cod_secretaria."', '".$cod_direccion."', '".$rif."', '".$concepto."', '".$fecha_contrato_servicio."', '".$fecha_inicio_contrato."', '".$fecha_terminacion_contrato."', '".$monto_original_contrato."', '".$aumento."', '".$disminucion."', '".$monto_anticipo."', '".$monto_amortizacion."', '".$monto_retencion_laboral."', '".$monto_retencion_fielcumplimiento."', '".$monto_cancelado."', '".$porcentaje_iva_parametro."', '".$porcentaje_anticipo."', '".$anticipo_con_iva."', '".$fecha_proceso_registro."', '".$dia_asiento_registro."', '".$mes_asiento_registro."', '".$ano_asiento_registro."', '".$numero_asiento_registro."', '".$username_registro."', '".$condicion_actividad."', '".$ano_anulacion."', '".$numero_anulacion."', '".$dia_asiento_anulacion."', '".$mes_asiento_anulacion."', '".$ano_asiento_anulacion."', '".$numero_asiento_anulacion."', '".$fecha_proceso_anulacion."', '".$username_anulacion."', '".$laboral_cancelado."', '".$fielcumplimiento_cancelado."', '".$pregunta_ejercicio."'); ");
}//fin if
/////////////////////////////////////////FIN GUARDAR CUERPO//////////////////////////////////////////////



if($R2>=1){



$cont=0;
for($z=0;$z<count($vec);$z++){if($vec[$z][0]!=null){$cont++; $ano = $vec[$z][0];}}//fin for


$ann=$ano;


  				$j = 0;

				$numero_compromiso= $this->cfpd21_numero_asiento_compromiso->field('cfpd21_numero_asiento_compromiso.numero_compromiso', $conditions = $this->condicionNDEP()." and ano_compromiso='$ann'", $order =null);
			if(!empty($numero_compromiso)){
				$numero_compromiso ++;
				$sql_numero_compromiso = "UPDATE cfpd21_numero_asiento_compromiso SET numero_compromiso='$numero_compromiso' WHERE ano_compromiso='$ann' and ".$this->condicionNDEP().";";
			}else{
				$numero_compromiso = 1;
				$sql_numero_compromiso = "INSERT INTO cfpd21_numero_asiento_compromiso VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$ano', '$numero_compromiso'); ";
			}
				$sw_numero_compromiso = $this->cfpd21_numero_asiento_compromiso->query($sql_numero_compromiso);


  $vec=$_SESSION["items"];
  $monto  = 0;
  $monto2 = 0;
  $values = "";
  $monto_original_contrato_aux = 0;

for($z=0;$z<count($vec);$z++){


	if($vec[$z][0]!=null){
  		$ano                                     =           $vec[$z][0];
  		$cod_sector                              =           $vec[$z][1];
  		$cod_programa                            =           $vec[$z][2];
  		$cod_sub_prog                            =           $vec[$z][3];
  		$cod_proyecto                            =           $vec[$z][4];
  		$cod_activ_obra                          =           $vec[$z][5];
  		$cod_partida                             =           $vec[$z][6];
  		$cod_generica                            =           $vec[$z][7];
  		$cod_especifica                          =           $vec[$z][8];
  		$cod_sub_espec                           =           $vec[$z][9];
  		$cod_auxiliar                            =           $vec[$z][10];
  		$monto                                   =           $this->Formato1($vec[$z][11]);

	 					   $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida,$cod_generica, $cod_especifica, $cod_sub_espec,$cod_auxiliar);
						   $to   = 1;
						   $td   = 3;
						   $ta   = 4;
						   $mt   = $monto;
						   $ccp  = $concepto;
						   $rnco = $numero_compromiso;

						   $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, $fecha_contrato_servicio2, $mt, $ccp, $ano, $numero_contrato_servicio, null, null, null, null, null, null, null, $rnco, null, null, null, $j);

if($values==""){
     $values .="('".$cod_presi."', '".$cod_entidad."',  '".$cod_tipo_inst."',  '".$cod_inst."',  '".$cod_dep."',  '".$ano_contrato_servicio."',  '".strtoupper($numero_contrato_servicio)."',  '".$ano."',  '".$cod_sector."',  '".$cod_programa."',  '".$cod_sub_prog."',  '".$cod_proyecto."',  '".$cod_activ_obra."',  '".$cod_partida."',  '".$cod_generica."',  '".$cod_especifica."',  '".$cod_sub_espec."',  '".$cod_auxiliar."',  '".$monto."',  '".$aumento."',  '".$disminucion."',  '".$anticipo."',  '".$amortizacion."',  '".$retencion_laboral."',  '".$retencion_fielcumplimiento."',  '".$cancelacion."', '".$rnco."')";//el cero que aparece es de numero_control_compromiso, por los momentos es cero
}else{
     $values .=", ('".$cod_presi."', '".$cod_entidad."',  '".$cod_tipo_inst."',  '".$cod_inst."',  '".$cod_dep."',  '".$ano_contrato_servicio."',  '".strtoupper($numero_contrato_servicio)."',  '".$ano."',  '".$cod_sector."',  '".$cod_programa."',  '".$cod_sub_prog."',  '".$cod_proyecto."',  '".$cod_activ_obra."',  '".$cod_partida."',  '".$cod_generica."',  '".$cod_especifica."',  '".$cod_sub_espec."',  '".$cod_auxiliar."',  '".$monto."',  '".$aumento."',  '".$disminucion."',  '".$anticipo."',  '".$amortizacion."',  '".$retencion_laboral."',  '".$retencion_fielcumplimiento."',  '".$cancelacion."', '".$rnco."')";//el cero que aparece es de numero_control_compromiso, por los momentos es cero
}//fin else


$monto_original_contrato_aux += $monto;
$monto2=$monto2+$this->Formato1($vec[$z][11]);

	}//fin for
}//fin for


//////////////////////////////////////////GUARDAR PARTIDAS/////////////////////////////////////////////////
$R3=$this->cepd02_contratoservicio_partidas->execute("INSERT INTO cepd02_contratoservicio_partidas (".$camposT3.") VALUES ".$values.";");
/////////////////////////////////////////FIN GUARDAR PARTIDAS//////////////////////////////////////////////


		if($R3>1){

			$valor_motor_contabilidad =  $this->motor_contabilidad_fiscal($to=1, $td=8, $rif_doc=$rif, $ano_dc=$ano, $n_dc=$numero_contrato_servicio, $f_dc=$fecha_contrato_servicio2, $cpt_dc=$ccp, $ben_dc=null, $mon_dc=array("monto"=>$monto_original_contrato), $ano_op=null, $n_op=null, $f_op=null, $a_adj_op=null, $n_adj_op=null, $f_adj_op=null, $tp_op=null, $deno_ban_pago=null, $ano_movimiento  = null, $cod_ent_pago=null, $cod_suc_pago=null, $cod_cta_pago=null, $num_che_o_debi=null, $fec_che_o_debi=null, $clas_che_o_debi=null);

		}else{

			$valor_motor_contabilidad = false;

		}//fin


				if($valor_motor_contabilidad==true){

				    $this->set('Message_existe', 'Los datos fueron almacenados correctamente');
				    $this->cepd02_contratoservicio_partidas->execute("COMMIT;");
				    echo'<script>';echo"   document.getElementById('guardar').disabled = true;  ";echo'</script>';

				}else{
					$this->cepd02_contratoservicio_partidas->execute("ROLLBACK;");
					$this->set('errorMessage', 'Los datos no fueron almacenados');
				}//fin else


}else{
	 $this->cepd02_contratoservicio_cuerpo->execute("ROLLBACK;");
	$this->set('errorMessage', 'Los datos no fueron almacenados');
}//fin else



			 $this->Session->delete("items");
		     $this->Session->delete("i");



}else{$this->set('errorMessage', 'Los datos no fueron almacenados');}//fin else
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}


    $this->Session->delete('cod_obra');
    $this->borrar_cugd04();
	$this->index();
	$this->render('index');


}//fin funcion guardar

















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




function trafico($ano=null, $cod_sector=null, $cod_programa=null, $cod_sub_prog=null, $cod_proyecto=null, $cod_activ_obra=null, $cod_partida=null, $cod_generica=null, $cod_especifica=null, $cod_sub_espec=null, $monto=null, $cod_auxiliar=null){
	$this->layout="ajax";
	$username = strtoupper($this->Session->read('nom_usuario'));
	//echo $ano.'-'.$cod_sector.'-'.$cod_programa.'-'.$cod_sub_prog.'-'.$cod_proyecto.'-'.$cod_activ_obra.'-'.$cod_partida.'-'.$cod_generica.'-'.$cod_especifica.'-'.$cod_sub_espec.'-'.$cod_auxiliar.'-'.$monto;
	if ($ano!=null && $cod_sector!=null && $cod_programa!=null && $cod_sub_prog!=null && $cod_proyecto!=null && $cod_activ_obra!=null && $cod_partida!=null && $cod_generica!=null && $cod_especifica!=null && $cod_sub_espec!=null && $monto!=null && $cod_auxiliar!=null){

		$trafico = $this->semaforo($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);

		//echo "el username es: ".$trafico['username']." y el color es: ".$trafico['color'];

		if($trafico['color'] == 'rojo' && $trafico['username']!= $username){
			$this->set('msg', $trafico['mensaje']);
			$this->set('remote', 'luis');
			$partida = $ano.'/'.$cod_sector.'/'.$cod_programa.'/'.$cod_sub_prog.'/'.$cod_proyecto.'/'.$cod_activ_obra.'/'.$cod_partida.'/'.$cod_generica.'/'.$cod_especifica.'/'.$cod_sub_espec.'/'.$cod_auxiliar.'/'.$monto;
			$this->set('partida', $partida);
		}else{
			$this->guardar_cugd04($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar, $username);
			$disponibilidad = $this->disponibilidad($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
			//echo "la disponibilidad es de: ".$disponibilidad;

			if(empty($disponibilidad)){
				$this->set('msg', 'ESTA PARTIDA NO ESTA REGISTRADA EN LA DISTRIBUCCION DE GASTO');
			}else{
				if ($disponibilidad < $monto) {
					$this->set('msg', 'EL MONTO DISPONIBLE PARA ESTA PARTIDA ES DE '.$this->Formato2($disponibilidad).' '.MONEDA2);
				}
			}
		}
	}
}//fin function



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
			$this->cugd04->execute($sql_insert_cugd04);
			$time = date('U');
			$this->cugd04->execute("UPDATE cugd04_entrada_modulo SET hora_captura_partida='$time'");
		}else{
			$sql_update_cugd04="UPDATE cugd04 set cod_auxiliar='$cod_auxiliar' WHERE ".$this->condicion().$partida;
			$this->cugd04->execute($sql_update_cugd04);
		}

	}
}



function borrar_cugd04(){
	$condicion = $this->condicion();
	$username = $this->Session->read('nom_usuario');
	$username = strtoupper($username);

	$c = $this->cugd04->findCount($condicion." and username='$username'");

	if ($c!=0){
		$this->cugd04->execute("DELETE FROM cugd04 WHERE ".$condicion." and username='$username'");
	}

}//fin borrar





function ver_trafico($ano=null, $cod_sector=null, $cod_programa=null, $cod_sub_prog=null, $cod_proyecto=null, $cod_activ_obra=null, $cod_partida=null, $cod_generica=null, $cod_especifica=null, $cod_sub_espec=null, $cod_auxiliar=null, $monto=null){
	$this->layout="ajax";

	$this->trafico($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $monto, $cod_auxiliar);

	$this->render('trafico');

}













function guardar_anulacion1($var=null) {
	$this->layout="ajax";


echo'<script>';
    echo'document.getElementById("guardar").disabled = false; ';
    echo'document.getElementById("anular").disabled = true; ';
echo'</script>';


}//fin function





function guardar_anulacion2($var=null) {

$this->layout="ajax";



if(isset($this->data["cepp02_contratoservicio"])){


		     $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' ';

		     $tipo_documento           =  234;

			 $concepto_anulacion       =  $this->data["cepp02_contratoservicio"]["concepto_anulacion"];
			 $fecha_proceso_anulacion  =  date("d/m/Y");

			 $condicion_documento      =  2;//cuando se guarda es Activo=1

			 $ano_contrato_servicio    = $this->data["cepp02_contratoservicio"]["ano_contrato_servicio"];
			 $numero_contrato_servicio = $this->data["cepp02_contratoservicio"]["numero_contrato_servicio"];
			 $fecha_contrato_servicio  = $this->data["cepp02_contratoservicio"]["fecha_contrato_servicio"];
			 $fecha_proceso_registro   = $this->data["cepp02_contratoservicio"]["fecha_proceso_registro"];

			 $rif                      = $this->data["cepp02_contratoservicio"]["rif"];
			 $monto_original_contrato  = $this->data["cepp02_contratoservicio"]["monto_original_contrato"];


			 $resultado = strpos("CONTRATISTA:", $concepto_anulacion);
		if($resultado==FALSE){
			 $rif = strtoupper($rif);
  			 $contratista = $this->cpcd02->field('cpcd02.denominacion', $conditions = "upper(cpcd02.rif)='$rif'", $order =null);
  		     $concepto_anulacion="CONTRATISTA: ".$contratista." ANULADO POR: ".$concepto_anulacion;
			}

			 $fd = $fecha_contrato_servicio;


if(isset($concepto_anulacion)){
if(isset($ano_contrato_servicio)){
if(isset($numero_contrato_servicio)){
if(isset($fecha_contrato_servicio)){
if(isset($fecha_proceso_registro)){



                $aux = $fecha_contrato_servicio[6].$fecha_contrato_servicio[7].$fecha_contrato_servicio[8].$fecha_contrato_servicio[9];
             if($aux!=$ano_contrato_servicio){$fd = $fecha_proceso_registro[8].$fecha_proceso_registro[9].$fecha_proceso_registro[7].$fecha_proceso_registro[5].$fecha_proceso_registro[6].$fecha_proceso_registro[4].$fecha_proceso_registro[0].$fecha_proceso_registro[1].$fecha_proceso_registro[2].$fecha_proceso_registro[3];}//fin


			 //$numero_anulacion=$this->cugd03_acta_anulacion_numero->execute($condicion);
             $v=$this->cugd03_acta_anulacion_numero->execute("SELECT numero_acta_anulacion FROM cugd03_acta_anulacion_numero WHERE ".$this->SQLCA()." and ano_acta_anulacion=".$ano_contrato_servicio." ORDER BY numero_acta_anulacion DESC");

            $cepd02_contratoservicio_partidas_aux = $this->cepd02_contratoservicio_partidas->findAll("ano_contrato_servicio='".$ano_contrato_servicio."' and upper(numero_contrato_servicio)='".strtoupper($numero_contrato_servicio)."' ");

		     if($v!=null){
				$numero=$v[0][0]["numero_acta_anulacion"];
				$numero = $numero =="" ? 1 : $numero+1;
				$this->cugd03_acta_anulacion_numero->execute("UPDATE cugd03_acta_anulacion_numero SET numero_acta_anulacion=".$numero." where ".$this->SQLCA()." and ano_acta_anulacion=".$ano_contrato_servicio."");
		     }else{
			    $v=$this->cugd03_acta_anulacion_numero->execute("INSERT INTO cugd03_acta_anulacion_numero(cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion) VALUES (".$this->SQLCAIN().",".$ano_contrato_servicio.",1)");
			    $numero=1;
		     }//fin else
			 $R1 = $this->cepd02_contratoservicio_cuerpo->execute("UPDATE cepd02_contratoservicio_cuerpo SET ano_anulacion=".$this->ano_ejecucion().", numero_anulacion=".$numero.", condicion_actividad=".$condicion_documento.",  fecha_proceso_anulacion='".$fecha_proceso_anulacion."', username_anulacion='".$_SESSION['nom_usuario']."'  WHERE ".$this->SQLCA()." and ano_contrato_servicio=".$ano_contrato_servicio." and upper(numero_contrato_servicio)='".strtoupper($numero_contrato_servicio)."' ");
		     $v  = $this->cugd03_acta_anulacion_cuerpo->execute("INSERT INTO cugd03_acta_anulacion_cuerpo  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion,tipo_operacion,ano_documento,numero_documento,fecha_documento,motivo_anulacion) VALUES (".$this->SQLCAIN().",".$ano_contrato_servicio.",".$numero.",".$tipo_documento.",".$ano_contrato_servicio.",'".$numero_contrato_servicio."','".$this->Cfecha($fecha_contrato_servicio, 'A-M-D')."','".$concepto_anulacion."')");



  foreach($cepd02_contratoservicio_partidas_aux as $cepd02_contratoservicio_partidas_aux2){

            $ano_contrato_servicio     = $cepd02_contratoservicio_partidas_aux2['cepd02_contratoservicio_partidas']['ano_contrato_servicio'];
			$numero_contrato_servicio  = $cepd02_contratoservicio_partidas_aux2['cepd02_contratoservicio_partidas']['numero_contrato_servicio'];
			$ano                       = $cepd02_contratoservicio_partidas_aux2['cepd02_contratoservicio_partidas']['ano'];
			$cod_sector                = $cepd02_contratoservicio_partidas_aux2['cepd02_contratoservicio_partidas']['cod_sector'];
			$cod_programa              = $cepd02_contratoservicio_partidas_aux2['cepd02_contratoservicio_partidas']['cod_programa'];
			$cod_sub_prog              = $cepd02_contratoservicio_partidas_aux2['cepd02_contratoservicio_partidas']['cod_sub_prog'];
			$cod_proyecto              = $cepd02_contratoservicio_partidas_aux2['cepd02_contratoservicio_partidas']['cod_proyecto'];
			$cod_activ_obra            = $cepd02_contratoservicio_partidas_aux2['cepd02_contratoservicio_partidas']['cod_activ_obra'];
			$cod_partida               = $cepd02_contratoservicio_partidas_aux2['cepd02_contratoservicio_partidas']['cod_partida'];
			$cod_generica              = $cepd02_contratoservicio_partidas_aux2['cepd02_contratoservicio_partidas']['cod_generica'];
			$cod_especifica            = $cepd02_contratoservicio_partidas_aux2['cepd02_contratoservicio_partidas']['cod_especifica'];
			$cod_sub_espec             = $cepd02_contratoservicio_partidas_aux2['cepd02_contratoservicio_partidas']['cod_sub_espec'];
			$cod_auxiliar              = $cepd02_contratoservicio_partidas_aux2['cepd02_contratoservicio_partidas']['cod_auxiliar'];
			$monto2                    = $cepd02_contratoservicio_partidas_aux2['cepd02_contratoservicio_partidas']['monto'];
			$rnco                      = $cepd02_contratoservicio_partidas_aux2['cepd02_contratoservicio_partidas']['numero_control_compromiso'];

			$cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);

			$cod_cp = "ano='$ano' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";

			$num_asiento_compromiso = $this->motor_presupuestario($cp, 2, 3, 4, date("d/m/Y"), $monto2, $concepto_anulacion, $ano_contrato_servicio, $numero_contrato_servicio, null, null, null, null, null, null, null, $rnco, null, null, null, null);


	}//fin del for




			    $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal($to=2, $td=8, $rif_doc=$rif, $ano_dc=$ano_contrato_servicio, $n_dc=$numero_contrato_servicio, $f_dc=date("d/m/Y"), $cpt_dc=$concepto_anulacion, $ben_dc=null, $mon_dc=array("monto"=>$monto_original_contrato), $ano_op=null, $n_op=null, $f_op=null, $a_adj_op=null, $n_adj_op=null, $f_adj_op=null, $tp_op=null, $deno_ban_pago=null, $ano_movimiento  = null, $cod_ent_pago=null, $cod_suc_pago=null, $cod_cta_pago=null, $num_che_o_debi=null, $fec_che_o_debi=null, $clas_che_o_debi=null);

               $this->set('Message_existe', 'El registro fue eliminado correctamente');

		    }else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
		   }else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
		  }else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
		 }else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
		}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}

}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}





$this->index();
$this->render('index');



/*
echo'<script>';
    echo'document.getElementById("guardar").disabled = true; ';
    echo'document.getElementById("anular").disabled = true; ';

    echo'document.getElementById("condicion_actividad_1").checked = false;';
  	echo'document.getElementById("condicion_actividad_2").checked = true;';

    echo'document.getElementById("a").innerHTML = "'.$ano_contrato_servicio.'"; ';
    echo'document.getElementById("b").innerHTML = "'.$numero.'"; ';
    echo'document.getElementById("c").innerHTML = "'.$fecha_proceso_anulacion.'"; ';
    echo'document.getElementById("d").innerHTML = "'.date("Y").'"; ';
    echo'document.getElementById("e").innerHTML = "'.date("m").'"; ';
    echo'document.getElementById("f").innerHTML = "'.$numero.'"; ';  ///AQUI VA EL NUMERO DE ASIENTO PERO HAY QUE ESPERAR EL DE EL MOTOR
    echo'document.getElementById("g").innerHTML = "'.$_SESSION['nom_usuario'].'"; ';

echo'</script>';
*/


}//fin function







function SQLCA_S($ano=null){//sql para busqueda de codigos de arranque con y sin año
				 $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
				 $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
				 $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
				 $sql_re .= "cod_inst=".$this->verifica_SS(4)."   ";
				 return $sql_re;
		}//fin funcion SQLCA








function traer_beneficiario ($var=null) {
	$this->layout="ajax";
	$resultado=$this->cpcd02->findByRif($var);

	$beneficiario=$resultado["cpcd02"]["denominacion"];
	if($beneficiario!=""){
				 $this->set('beneficiario',$beneficiario);
	}else{
		$this->set('beneficiario',"No existe, ".$var);
	}

}//fin function




function imputacion_presupuestaria ($var=null) {
	$this->layout="ajax";

	$cod_1 =  "";//dir sup
	$cod_2 =  "";//coor
	$cod_3 =  "";//secre

	$cod_1 =  $this->Session->read('cod_1');//dir sup
	$cod_2 =  $this->Session->read('cod_2');//coor
	$cod_3 =  $this->Session->read('cod_3');//secre
	$cod_4 =  $this->Session->read('cod_4');//secre






	if($cod_3!= "" &&  $cod_4!=null){

	$cond  ="cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$this->verifica_SS(5);
	$cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$cod_3." and cod_direccion=".$cod_4;
	$resultado=$this->cugd02_direccion->findAll($cond,array('cod_sector','cod_programa','cod_sub_prog','cod_proyecto'));
	$this->Session->write('CodigosDireccion',$resultado);
	$cod_4 = $this->Session->read("cod_catalogo");



		$ano=$this->ano_ejecucion();
		$this->set('ano', $ano);
		$var=$var==null ? 0:$var;
		$a=  $this->cscd01_catalogo->findAll("codigo_prod_serv=".$var,array('cod_partida','cod_generica','cod_especifica','cod_sub_espec','cod_auxiliar'));
		$this->set("partidas",$a);
		$this->Session->write('partidas',$a);
		$part= $a[0]['cscd01_catalogo']['cod_partida']<9 ? "40".$a[0]['cscd01_catalogo']['cod_partida']:$a[0]['cscd01_catalogo']['cod_partida'];
		$part= $part <400 ? "4".$part : $part;
		$gen=$a[0]['cscd01_catalogo']['cod_generica'];
		$espec=$a[0]['cscd01_catalogo']['cod_especifica'];
		$sube=$a[0]['cscd01_catalogo']['cod_sub_espec'];

		$f=$this->Session->read('CodigosDireccion');
		$resultado=$this->cfpd05->findAll($this->SQLCA($ano)." and cod_sector=".$f[0]["cugd02_direccion"]["cod_sector"]." and cod_programa=".$f[0]["cugd02_direccion"]["cod_programa"]." and cod_sub_prog=".$f[0]["cugd02_direccion"]["cod_sub_prog"]." and cod_proyecto=".$f[0]["cugd02_direccion"]["cod_proyecto"]." and cod_partida=".$part." and cod_generica=".$gen." and cod_especifica=".$espec." and cod_sub_espec=".$sube,array('cod_activ_obra','asignacion_anual'));



        $this->ver_otro_actividad_auxiliar();

	}//fin if

}//fin function





function ver_otro_actividad_auxiliar(){

  $this->layout="ajax";
  $cod_presi                =       $this->Session->read('SScodpresi');
  $cod_entidad              =       $this->Session->read('SScodentidad');
  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
  $cod_inst                 =       $this->Session->read('SScodinst');
  $cod_dep                  =       $this->Session->read('SScoddep');
  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'  ';

	 $ano = $this->ano_ejecucion();


$codigosDireccion_aux = $_SESSION['CodigosDireccion'];
$partidas_aux         = $_SESSION['partidas'];

$f = $codigosDireccion_aux;
$a = $partidas_aux;
$part= $a[0]['cscd01_catalogo']['cod_partida']<9 ? "40".$a[0]['cscd01_catalogo']['cod_partida']:$a[0]['cscd01_catalogo']['cod_partida'];
$part= $part <400 ? "4".$part : $part;
$gen=$a[0]['cscd01_catalogo']['cod_generica'];
$espec=$a[0]['cscd01_catalogo']['cod_especifica'];
$sube=$a[0]['cscd01_catalogo']['cod_sub_espec'];

  $array = $this->cfpd05->findAll($this->SQLCA($ano)." and cod_sector=".$f[0]["cugd02_direccion"]["cod_sector"]." and cod_programa=".$f[0]["cugd02_direccion"]["cod_programa"]." and cod_sub_prog=".$f[0]["cugd02_direccion"]["cod_sub_prog"]." and cod_proyecto=".$f[0]["cugd02_direccion"]["cod_proyecto"]." and cod_partida=".$part." and cod_generica=".$gen." and cod_especifica=".$espec." and cod_sub_espec=".$sube  , 'DISTINCT  cod_activ_obra', 'cod_activ_obra ASC', null);
  $i = 0;
   foreach($array as $aux){
 	$numero[$i]['cod_activ_obra']    =  $aux['cfpd05']['cod_activ_obra'];
 	$i++;
} $i--;







if($i>=1){

   $lista =  $this->v_cfpd05_denominaciones->generateList($this->SQLCA($ano)." and cod_sector=".$f[0]["cugd02_direccion"]["cod_sector"]." and cod_programa=".$f[0]["cugd02_direccion"]["cod_programa"]." and cod_sub_prog=".$f[0]["cugd02_direccion"]["cod_sub_prog"]." and cod_proyecto=".$f[0]["cugd02_direccion"]["cod_proyecto"]."  and cod_partida=".$part." and cod_generica=".$gen." and cod_especifica=".$espec." and cod_sub_espec=".$sube, 'cod_activ_obra ASC', null, '{n}.v_cfpd05_denominaciones.cod_activ_obra', '{n}.v_cfpd05_denominaciones.deno_activ_obra');
   $this->concatena($lista, 'actividad');

}else{


 if(isset($numero[0]['cod_activ_obra'])){

  $this->set('actividad',$numero[0]['cod_activ_obra']);
  $this->set('cont_activ', 'si');


  $array2 = $this->cfpd05->findAll($this->SQLCA($ano)." and cod_sector=".$f[0]["cugd02_direccion"]["cod_sector"]." and cod_programa=".$f[0]["cugd02_direccion"]["cod_programa"]." and cod_sub_prog=".$f[0]["cugd02_direccion"]["cod_sub_prog"]." and cod_proyecto=".$f[0]["cugd02_direccion"]["cod_proyecto"]."  and  cod_activ_obra=".$numero[0]['cod_activ_obra']."  and cod_partida=".$part." and cod_generica=".$gen." and cod_especifica=".$espec." and cod_sub_espec=".$sube  , 'DISTINCT  cod_auxiliar', 'cod_auxiliar ASC', null);
  $i = 0;
  foreach($array2 as $aux2){$numero2[$i]['cod_auxiliar']   =  $aux2['cfpd05']['cod_auxiliar'];$i++;} $i--;

            if($i>=1){

               $lista=  $this->cfpd05_auxiliar->generateList($this->SQLCA($ano)." and cod_sector=".$f[0]["cugd02_direccion"]["cod_sector"]." and cod_programa=".$f[0]["cugd02_direccion"]["cod_programa"]." and cod_sub_prog=".$f[0]["cugd02_direccion"]["cod_sub_prog"]." and cod_proyecto=".$f[0]["cugd02_direccion"]["cod_proyecto"]."  and  cod_activ_obra=".$numero[0]['cod_activ_obra']."  and cod_partida=".$part." and cod_generica=".$gen." and cod_especifica=".$espec." and cod_sub_espec=".$sube, 'cod_auxiliar ASC', null, '{n}.cfpd05_auxiliar.cod_auxiliar', '{n}.cfpd05_auxiliar.denominacion');
               $this->concatena($lista, 'auxiliar');

            }else{

             	$this->set('auxiliar', $numero2[0]['cod_auxiliar']);
             	$this->set('cont_auxiliar', 'si');

                }//fin segundo else


 }//fin



     }//fin primer else



}//fin function




function codigos_diferentes(){
	$this->layout="ajax";
	$ano=$this->ano_ejecucion();

		$sector=$this->cfpd02_sector->generateList($this->SQLCA($ano),'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.denominacion');
			$sector = $sector != null ? $sector : array();
		$this->concatena($sector, 'sector');
}//fin function





function consultar ($ano=null,$pagina=null,$msj=null) {
	$this->layout="ajax";
  $cod_presi                =       $this->Session->read('SScodpresi');
  $cod_entidad              =       $this->Session->read('SScodentidad');
  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
  $cod_inst                 =       $this->Session->read('SScodinst');
  $cod_dep                  =       $this->Session->read('SScoddep');
  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'  ';




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
						 $this->set('ejercicio', $this->ano_ejecucion());
						 $Tfilas=$this->cepd02_contratoservicio_cuerpo->findCount($this->SQLCA()." and ano_contrato_servicio=".$Ano);
						 //echo $Tfilas;
						 if($Tfilas!=0){
						 $this->set('pag_cant',$pagina.'/'.$Tfilas);
						 $this->set('ultimo',$Tfilas);
						 $dataCompromiso=$this->cepd02_contratoservicio_cuerpo->findAll($this->SQLCA()." and ano_contrato_servicio=".$Ano,null,'ano_contrato_servicio,numero_contrato_servicio ASC',1,$pagina,null);
						 foreach ($dataCompromiso as $YYY);

						 $a = $this->cscd01_catalogo->findAll("codigo_prod_serv=".$YYY['cepd02_contratoservicio_cuerpo']['codigo_prod_serv'],array('denominacion'));
						 $denominacion=$a[0]['cscd01_catalogo']['denominacion'];
								 //$YYY['cfpd05']['cod_sector'];
							 //////////////////////////////////////////////////////////////////////////
								$cond = "cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$this->verifica_SS(5);
								$cond1=" and cod_dir_superior=".$YYY['cepd02_contratoservicio_cuerpo']['cod_dir_superior'];
						$a1=  $this->cugd02_direccionsuperior->findAll($cond.$cond1);
								$x1= $a1[0]['cugd02_direccionsuperior']['denominacion'];
								$this->set('dir_sup',$x1);
								$cond2=" and cod_dir_superior=".$YYY['cepd02_contratoservicio_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$YYY['cepd02_contratoservicio_cuerpo']['cod_coordinacion'];
						$a2=  $this->cugd02_coordinacion->findAll($cond.$cond2);
								$x2= $a2[0]['cugd02_coordinacion']['denominacion'];
								$this->set('coordinacion',$x2);
 				$cond3=" and cod_dir_superior=".$YYY['cepd02_contratoservicio_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$YYY['cepd02_contratoservicio_cuerpo']['cod_coordinacion']." and cod_secretaria=".$YYY['cepd02_contratoservicio_cuerpo']['cod_secretaria'];
						$a3=  $this->cugd02_secretaria->findAll($cond.$cond3);
								$x3= $a3[0]['cugd02_secretaria']['denominacion'];
								$this->set('secretaria',$x3);
								$cond4=" and cod_dir_superior=".$YYY['cepd02_contratoservicio_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$YYY['cepd02_contratoservicio_cuerpo']['cod_coordinacion']." and  cod_secretaria=".$YYY['cepd02_contratoservicio_cuerpo']['cod_secretaria']." and cod_direccion=".$YYY['cepd02_contratoservicio_cuerpo']['cod_direccion'];
						$a4=  $this->cugd02_direccion->findAll($cond.$cond4);
								$x4= $a4[0]['cugd02_direccion']['denominacion'];
								$this->set('direccion',$x4);
								//$tipo_doc=$this->cepd01_tipo_compromiso->findAll("cod_tipo_compromiso=".$YYY['cepd02_contratoservicio_cuerpo']['cod_tipo_compromiso']);

								//$this->set("tipo_doc",$tipo_doc[0]["cepd01_tipo_compromiso"]["denominacion"]);
								$this->traer_beneficiario($YYY['cepd02_contratoservicio_cuerpo']['rif']);
								$compromiso_partidas=$this->cepd02_contratoservicio_partidas->findAll($this->SQLCA()." and ano_contrato_servicio=".$Ano." and numero_contrato_servicio='".$YYY['cepd02_contratoservicio_cuerpo']['numero_contrato_servicio']."' ");


								//$C_A=$this->cugd03_acta_anulacion_cuerpo->findByNumero_acta_anulacion($YYY['cepd02_contratoservicio_cuerpo']['numero_anulacion']);
								$C_A=$this->cugd03_acta_anulacion_cuerpo->findAll($condicion." and numero_acta_anulacion=".$YYY['cepd02_contratoservicio_cuerpo']['numero_anulacion']." and ano_acta_anulacion=".$Ano);
                                //print_r($C_A);
                                if($C_A!=null){
                                	$this->set("concepto_anulacion",$C_A[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"]);
                                }else{
                                	$this->set("concepto_anulacion","");
                                }



                                $C_B=$this->cscd01_catalogo->findAll("codigo_prod_serv=".$YYY['cepd02_contratoservicio_cuerpo']['codigo_prod_serv']);
                                if($C_B!=null){
                                	$this->set("snc",$C_B[0]["cscd01_catalogo"]["cod_snc"]);
                                }else{
                                	$this->set("snc","");
                                }




								$this->set('COMPROMISO_PARTIDA',$compromiso_partidas);



								$this->set('COMPROMISO',$dataCompromiso);
								$this->set('denominacion',$denominacion);
								$this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
						}else{
						$this->set('COMPROMISO','');
						$this->set('errorMessage', 'No se encontrar&oacute;n datos');

					 }///fin else  del if-else que compara las Tfilas










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





function prueba ($var=null) {
	$this->layout="ajax";
	if(isset($var)){
		$datos=$this->data["cepp01_compromiso"]["campo1"].",";
		$datos .=$this->data["cepp01_compromiso"]["campo2"].",";
		$datos .=$this->data["cepp01_compromiso"]["campo3"].",";
		$datos .=$this->data["cepp01_compromiso"]["campo4"].",";
		$datos .=$this->data["cepp01_compromiso"]["campo5"];
		$sql="insert into prueba (campo) values ('{".$datos."}')";
		$this->prueba->execute($sql);
		$c=$this->prueba->findAll("id=1");
		echo $c[0]["prueba"]["campo"];
		print_r($c);

	}
}


function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cepp02_contratoservicio']['login']) && isset($this->data['cepp02_contratoservicio']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cepp02_contratoservicio']['login']);
		$paswd=addslashes($this->data['cepp02_contratoservicio']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=80 and clave='".$paswd."'";
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


/*


  cepd02_contratoservicio_cuerpo

  cod_presi
  cod_entidad
  cod_tipo_inst
  cod_inst
  cod_dep
  ano_contrato_servicio
  numero_contrato_servicio
  codigo_prod_serv
  cod_dir_superior
  cod_coordinacion
  cod_secretaria
  cod_direccion
  rif
  concepto
  fecha_contrato_servicio
  fecha_inicio_contrato
  fecha_terminacion_contrato
  monto_original_contrato
  aumento
  disminucion
  monto_anticipo
  monto_amortizacion
  monto_retencion_laboral
  monto_retencion_fielcumplimiento
  monto_cancelado
  porcentaje_iva
  porcentaje_anticipo
  anticipo_con_iva
  fecha_proceso_registro
  dia_asiento_registro
  mes_asiento_registro
  ano_asiento_registro
  numero_asiento_registro
  username_registro
  condicion_actividad
  ano_anulacion
  numero_anulacion
  dia_asiento_anulacion
  mes_asiento_anulacion
  ano_asiento_anulacion
  numero_asiento_anulacion
  fecha_proceso_anulacion
  username_anulacion
  laboral_cancelado
  fielcumplimiento_cancelado















 cepd02_contratoservicio_partidas

  cod_presi
  cod_entidad
  cod_tipo_inst
  cod_inst
  cod_dep
  ano_contrato_servicio
  numero_contrato_servicio
  ano
  cod_sector
  cod_programa
  cod_sub_prog
  cod_proyecto
  cod_activ_obra
  cod_partida
  cod_generica
  cod_especifica
  cod_sub_espec
  cod_auxiliar
  monto
  aumento
  disminucion
  anticipo
  amortizacion
  retencion_laboral
  retencion_fielcumplimiento
  cancelacion
  numero_control_compromiso









 */

}//fin class
?>
