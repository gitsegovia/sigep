<?php
/* Created on 28/08/2012*/

	class Catp02ReporteFichaDatosController extends AppController{
	var $name = 'catp02_reporte_ficha_datos';
    var $uses = array('catd02_ficha_datos','catd02_ficha_tipologia','catd01_ano_ordenanza','v_catd02_ficha_datos','cugd07_firmas_oficio_anulacion','catd01_escala_cobro','catd01_recargos_catastrales','ccfd04_cierre_mes','v_catd02_valor_tierra','v_catd02_complementos_construccion');
    var $helpers = array('Html', 'Javascript', 'Ajax','Sisap');


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

function verifica_SS($i){
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

function reporte_complemento_construccion($var=null){

	if($var!=null){
		if($var=='si'){// Se muestra la vista del formulario.
			$this->layout="ajax";
			$this->set('var',$var);
			$ano_ejec = $this->catd01_ano_ordenanza->ano_actual($this->SQLCA());
			if($ano_ejec==null){
				$ano_ejec = $this->ano_ejecucion();
			}
			$this->set('anio_ej',$ano_ejec);


		}elseif($var=='no'){// Se muestra la vista del reporte.
			$this->layout = "pdf";
			ini_set("memory_limit", "2048M");
			$datos = $this->v_catd02_complementos_construccion->findAll($this->SQLCA()." and ano_ordenanza=". $this->data['catp02_reporte_ficha_datos']['ano_ordenanza']);
			$_SESSION['ano_ordenanza_report'] = $this->data['catp02_reporte_ficha_datos']['ano_ordenanza'];
			$this->set('datos',$datos);
			$this->set('var',$var);

		}
	}
}

function calculo_impuesto_anual_trim($var_mtc=null,$anoo=null) {


$campitos="escala, monto_desde, monto_hasta, porcentaje, sustraendo";
	$escalas_catastrales = $this->catd02_ficha_datos->execute("SELECT escala, monto_desde, monto_hasta, porcentaje, sustraendo FROM catd01_escala_cobro WHERE ".$this->SQLCA()." AND ano_ordenanza='$anoo'");
	$icount=$this->catd01_escala_cobro->findcount($this->SQLCA()." AND ano_ordenanza=".$anoo,$campitos);
	$this->set("escalas_catastrales",$escalas_catastrales);
	$this->set("var_mtc",$var_mtc);
	$this->set("icount",$icount);

} // fin function calculo_impuesto_anual_trimestral


function iniciar_busqueda($vari=null) {
	$this->layout="ajax";
$url                  =  "/catp02_reporte_ficha_datos/buscar_datos_ficha/$vari";
$width_aux            =  "750px";
$height_aux           =  "370px";
$title_aux            =  "Buscar";
$resizable_aux        =  false;
$maximizable_aux      =  false;
$minimizable_aux      =  false;
$closable_aux         =  false;

			 echo "<script>";
	           echo "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
	         echo "</script>";
} // fin funcion

function tipo_documento($var){

	switch ($var){
		case '1': $var_1=20000; break;//BOLETIN INFORMACION
		case '2': $var_1=20001; break;//CONSTANCIA CATASTRAL
		case '3': $var_1=20002; break;//FICHA DE INSCRIPCION
		case '4': $var_1=20003; break;//CEDULA CATASTRAL
		case '5': $var_1=20004; break;//CALCULO IMPUESTOS
		default: $var_1=0; break;

	}
return $var_1;
}

function constancia_catastro($codigo_ficha,$cedula,$var=null){

	if($var!=null){
		if($var=='si'){// Se muestra la vista del formulario.
			$this->layout="ajax";
			$this->set('var',$var);
			$this->envia_form_firmas(20001);

		if($codigo_ficha!=0){
		$this->set('cedula',$cedula);
		$this->set('codigo_ficha',$codigo_ficha);
		echo "<script>document.getElementById('enviar').disabled=false; </script>";
		$campos = "cod_act_edo,cod_act_mun,cod_act_prr,cod_act_amb_t,cod_act_amb,cod_act_sec,cod_act_man,cod_act_par,cod_act_sbp,cod_act_niv,cod_act_und";
		$datosgrilla=$this->v_catd02_ficha_datos->findAll($this->SQLCA()." and cod_ficha=".$codigo_ficha,$campos);
		$this->set('datosgrilla',$datosgrilla);
		}else{
		$this->set('cedula',"");
		$this->set('codigo_ficha',"");
		$this->iniciar_busqueda(2);
		}

		}elseif($var=='no'){// Se muestra la vista del reporte.
			$this->layout = "pdf";
			$this->set('var',$var);
			$condicion=" and cod_ficha='".$this->data[catp02_reporte_ficha_datos][codigo_ficha]."'";
			$datos=$this->v_catd02_ficha_datos->findAll($this->SQLCA().$condicion,null);
			$this->set("datos",$datos);
			$this->Session->write('nombre_firma', $this->data['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
			//$_SESSION('nombre_firma') = $this->data['cugd07_firmas_oficio_anulacion']['nombre_primera_firma'];
			$_SESSION['cargo_firma'] = $this->data['cugd07_firmas_oficio_anulacion']['cargo_primera_firma'];


		}
	}
}


function planta_valores_tierra($var=null){
if($var!=null){
		if($var=='si'){// Se muestra la vista del formulario.
			$this->layout="ajax";

			$this->set('var',$var);

			$ano_ejec = $this->catd01_ano_ordenanza->ano_actual($this->SQLCA());
			if($ano_ejec==null){
				$ano_ejec = $this->ano_ejecucion();
			}
			$this->set('anio_ej',$ano_ejec);

		}elseif($var=='no'){// Se muestra la vista del reporte.
			$this->layout = "pdf";
			$this->set('var',$var);
			$ano_ordenanza = $this->data['catp02_reporte_ficha_datos']['ano_ordenanza'];
			$this->set("ano_ordenanza",$ano_ordenanza);
			$datos=$this->v_catd02_valor_tierra->findAll($this->SQLCA()." AND ano_ordenanza=".$ano_ordenanza,'cod_parroquia,denominacion,cod_zona,denominacion_zona,numero_variable,plus,valor_ut_m2, valor_ut, valor_m2,valor_arrend_m2,parcelas');
			$this->set("datos",$datos);
			$_SESSION['ano_pvt'] =$ano_ordenanza;
		}
	}
}


function cedula_catastro($var=null){
$this->layout="ajax";



if($var!=null){
		if($var=='si'){// Se muestra la vista del formulario.
			$this->layout="ajax";
			$this->set('var',$var);
			$this->envia_form_firmas(20003);
			$this->iniciar_busqueda(4);

		}elseif($var=='no'){// Se muestra la vista del reporte.
			$this->layout = "pdf";
			$ano_ordenanza = $this->data['catp02_reporte_ficha_datos']['ano_ordenanza'];

			$ano_ordenanza == '' ? $ano_ordenanza = date('Y') : $ano_ordenanza;
			$_SESSION['ano_ordenanza_report']= $ano_ordenanza;
			$this->set('var',$var);
			$condicion=" and cod_ficha='".$this->data['catd02_ficha_datos']['codigo_ficha']."'"." and cedula_rif_repre='".$this->data['catd02_ficha_datos']['cedula_rif_repre']."'";
			$datos=$this->v_catd02_ficha_datos->findAll($this->SQLCA().$condicion,'cod_ficha, cod_inscripcion, fecha_inscripcion, cod_control_archivo, cod_act_edo, cod_act_mun, cod_act_prr, cod_act_amb_t, cod_act_amb, cod_act_sec, cod_act_man, cod_act_par, cod_act_sbp, cod_act_niv,cod_act_und, cedula_rif_repre, nombre_repre, ciudad, lindero_norte, lindero_sur, lindero_este , lindero_oeste');
			$this->set("datos",$datos);
			$this->set('nombre_firma',$this->data['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
			$this->set('cargo_firma',$this->data['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
			// pr($datos);
		}
	}
}

function grilla_ambito($codigo_ficha=null,$cedula_rif_repre=null){
		$this->layout="ajax";
		$this->set('codigo_ficha',$codigo_ficha);
		$campos = "cod_act_edo,cod_act_mun,cod_act_prr,cod_act_amb_t,cod_act_amb,cod_act_sec,cod_act_man,cod_act_par,cod_act_sbp,cod_act_niv,cod_act_und";
		$datosgrilla=$this->v_catd02_ficha_datos->findAll($this->SQLCA()." and cod_ficha=".$codigo_ficha." and cedula_rif_repre='$cedula_rif_repre'",$campos);
		$this->set('datosgrilla',$datosgrilla);
		$this->set('codigo_ficha',$codigo_ficha);
		$this->set('cedula_rif_repre',$cedula_rif_repre);
}


function reporte_valorizacion($var=null){
$_SESSION['opcion_radio']=1;
	if($var!=null){
		if($var=='si'){// Se muestra la vista del formulario.
			$this->layout="ajax";
			$this->set('var',$var);


		}elseif($var=='no'){// Se muestra la vista del reporte.
$this->layout = "pdf";

			if($this->data['catp02_reporte_ficha_datos']['opcion_filtro']==1){
			$condicion="";
			$_SESSION['opcion_radio']=1;


			}else{
			$_SESSION['opcion_radio']=2;
			$select0 = $this->data['catp02_reporte_ficha_datos']['select_tipo0'];
			$select1 = $this->data['catp02_reporte_ficha_datos']['select_tipo1'];
			$select2 = $this->data['catp02_reporte_ficha_datos']['select_tipo2'];

				if($_SESSION['selectv1']==1){//selesct terreno

					$this->set('select_prim','Terreno');
					$this->set('select_seg',$_SESSION['selectv2']);
					$this->set('select_ter',$select2);
				//$condicion=" and substr(tilde_uso::text,1,1)::text ='1'";
				$campo_terreno=array(0=>'radio_topo',1=>'radio_acceso',2=>'radio_forma',3=>'radio_ubica',4=>'radio_entorno',5=>'tilde_mejora',6=>'radio_tenencia',7=>'radio_regimen',8=>'tilde_uso',9=>'tilde_servicio');

					if( $_SESSION['selectv2']==5 || $_SESSION['selectv2']==8 || $_SESSION['selectv2']==9) $condicion=" and substr(".$campo_terreno[$_SESSION['selectv2']]."::text,".($select2+1).",1)::text ='1'";
						//$condicion=" and substr(tilde_uso::text,1,1)::text ='1'";
					else $condicion=" and ".$campo_terreno[$_SESSION['selectv2']]."=".($select2+1);
				}else{
					$this->set('select_prim','Construcción');
					$this->set('select_seg',$_SESSION['selectv2']);
					$this->set('select_ter',$select2);
					$campo_construccionr=array(0=>'radio_tipo',1=>'radio_descripcionuso',2=>'radio_tenencia_const',3=>'radio_regi_prop',4=>'tilde_soporte',5=>'tilde_pared_tipo',6=>'tilde_pared_acaba',7=>'tilde_techo',8=>'tilde_cubierta',9=>'tilde_piso',10=>'radio_conserva');

					if( $_SESSION['selectv2']==0 || $_SESSION['selectv2']==1 || $_SESSION['selectv2']==2 || $_SESSION['selectv2']==3 || $_SESSION['selectv2']==10)$condicion=" and ".$campo_construccionr[$_SESSION['selectv2']]."=".($select2+1);
						//$condicion=" and substr(tilde_uso::text,1,1)::text ='1'";
					else $condicion=" and substr(".$campo_construccionr[$_SESSION['selectv2']]."::text,".($select2+1).",1)::text ='1'";
				}

			}


			$campos = "cod_ficha,nombre_repre,terreno_valor_total,construccion_valor_total,valor_total_inmueble,impuesto_anual,cod_act_edo,cod_act_mun,cod_act_prr,cod_act_amb_t,cod_act_amb,cod_act_sec,cod_act_man,cod_act_par,cod_act_sbp,cod_act_niv,cod_act_und,tilde_uso";
			//$datosgrilla=$this->v_catd02_ficha_datos->execute("select ".$campos." from v_catd02_ficha_datos where ".$this->SQLCA().$condicion);
			$datosgrilla=$this->v_catd02_ficha_datos->findAll($this->SQLCA().$condicion,$campos);

			$this->set('datosgrilla',$datosgrilla);

			$this->set('var',$var);
		}
	}
}

function radio_valorizacion($var=null){
$this->layout="ajax";

	$this->set('opcion_radio',$var);
$_SESSION['opcion_radio']=$var;

}


function opcion_select($opc=null,$patron=null,$var=null){
$this->layout="ajax";
	$this->set('opc',$opc);
	$this->set('patron',$patron);
	$this->set('opcion_select',$var);
	if($opc==1){
		$_SESSION['selectv1']= $var;
	}
	if($opc==2){
		$_SESSION['selectv2']= $var;
	}

}


function reporte_ficha_insc_catastral($codigo_ficha,$cedula,$var=null){

	if($var!=null){
		if($var=='si'){// Se muestra la vista del formulario.
			$this->layout="ajax";
			$this->set('var',$var);
			$this->envia_form_firmas(20002);

		if($codigo_ficha!=0){
		$this->set('cedula',$cedula);
		$this->set('codigo_ficha',$codigo_ficha);
		echo "<script>document.getElementById('enviar').disabled=false; </script>";
		$campos = "cod_act_edo,cod_act_mun,cod_act_prr,cod_act_amb_t,cod_act_amb,cod_act_sec,cod_act_man,cod_act_par,cod_act_sbp,cod_act_niv,cod_act_und";
		$datosgrilla=$this->v_catd02_ficha_datos->findAll($this->SQLCA()." and cod_ficha=".$codigo_ficha,$campos);
		$this->set('datosgrilla',$datosgrilla);
		}else{
		$this->set('cedula',"");
		$this->set('codigo_ficha',"");
		$this->iniciar_busqueda(3);
		}

		}elseif($var=='no'){// Se muestra la vista del reporte.
			$this->layout = "pdf";
			$this->set('var',$var);
			$condicion=" and cod_ficha='".$this->data[catp02_reporte_ficha_datos][codigo_ficha]."'";
			$datos=$this->v_catd02_ficha_datos->findAll($this->SQLCA().$condicion,null);
			$this->set("datos",$datos);
			$_SESSION['nombre_primera_firma']=$this->data['cugd07_firmas_oficio_anulacion']['nombre_primera_firma'];
			$_SESSION['cargo_primera_firma']=$this->data['cugd07_firmas_oficio_anulacion']['cargo_primera_firma'];


		}
	}
}


function reporte_calculos_impuesto($codigo_ficha,$cedula,$var=null){

	if($var!=null){
		if($var=='si'){// Se muestra la vista del formulario.
			$this->layout="ajax";
			$this->set('var',$var);
			$this->envia_form_firmas(20004);

			if($codigo_ficha!=0){
				$this->set('cedula',$cedula);
				$this->set('codigo_ficha',$codigo_ficha);
				echo "<script>document.getElementById('enviar').disabled=false; </script>";
				$campos = "cod_act_edo,cod_act_mun,cod_act_prr,cod_act_amb_t,cod_act_amb,cod_act_sec,cod_act_man,cod_act_par,cod_act_sbp,cod_act_niv,cod_act_und";
				$datosgrilla=$this->v_catd02_ficha_datos->findAll($this->SQLCA()." and cod_ficha=".$codigo_ficha,$campos);
				$this->set('datosgrilla',$datosgrilla);
			}else{
				$this->set('cedula',"");
				$this->set('codigo_ficha',"");
				$this->iniciar_busqueda(5);
			}


		}elseif($var=='no'){// Se muestra la vista del reporte.
			$this->layout = "pdf";
			$this->set('var',$var);
			$condicion=" and cod_ficha='".$this->data[catp02_reporte_ficha_datos][codigo_ficha]."'";
			$datos=$this->v_catd02_ficha_datos->findAll($this->SQLCA().$condicion,null);
			$this->set("datos",$datos);
			$datostipologia=$this->catd02_ficha_tipologia->findAll($this->SQLCA().$condicion,null);
			$cantidadtipologia=$this->catd02_ficha_tipologia->findcount($this->SQLCA().$condicion,null);
			$this->set("datostipologia",$datostipologia);
			$this->set("cantidadtipologia",$cantidadtipologia);
			$total_valor=$datos[0]['v_catd02_ficha_datos']['terreno_valor_total']+$datos[0]['v_catd02_ficha_datos']['construccion_valor_total'];
			$this->calculo_impuesto_anual_trim($total_valor,$datos[0]['v_catd02_ficha_datos']['ano_ordenanza']);
			$_SESSION['nombre_primera_firma']=$this->data['cugd07_firmas_oficio_anulacion']['nombre_primera_firma'];
			$_SESSION['cargo_primera_firma']=$this->data['cugd07_firmas_oficio_anulacion']['cargo_primera_firma'];

		}
	}
}

function reporte_info_catastral($var=null){

	if($var!=null){
		if($var=='si'){// Se muestra la vista del formulario.
			$this->layout="ajax";
			$this->set('var',$var);
			$this->envia_form_firmas(20000);



		}elseif($var=='no'){// Se muestra la vista del reporte.
			$this->layout = "pdf";
			$this->set('var',$var);

			if(isset($this->data['catp02_reporte_ficha_datos']['fecha_desde'])){
				$desde = $this->data['catp02_reporte_ficha_datos']['fecha_desde'];
				$hasta = $this->data['catp02_reporte_ficha_datos']['fecha_hasta'];
				$condicion = " and cod_ficha between '$desde' and '$hasta'";

			}elseif(isset($this->data['catp02_reporte_ficha_datos']['cedula'])){

				$condicion=" and cod_ficha='".$this->data['catp02_reporte_ficha_datos']['codigo_ficha']."'";

			}else $condicion="";

			$_SESSION['nombre_primera_firma'] = $this->data['cugd07_firmas_oficio_anulacion']['nombre_primera_firma'];
			$_SESSION['cargo_primera_firma']  = $this->data['cugd07_firmas_oficio_anulacion']['cargo_primera_firma'];
			$_SESSION['nombre_segunda_firma'] = $this->data['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma'];
			$_SESSION['cargo_segunda_firma']  = $this->data['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma'];
			$_SESSION['nombre_tercera_firma'] = $this->data['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma'];
			$_SESSION['cargo_tercera_firma']  = $this->data['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma'];


//			$sql="select * from v_busqueda_catd02_ficha_datos where ".$this->SQLCA().$condicion;
//			$datos=$this->catd01_recargos_catastrales->execute($sql);
			$datos=$this->v_catd02_ficha_datos->findAll($this->SQLCA().$condicion,null);
			$this->set("datos",$datos);

		}//fin reporte pdf
	}
}


function radio_info_catastral($cedula,$codigo_ficha,$var=null){
$this->layout="ajax";



	if($var!=null){
		if($var==3){
			$this->iniciar_busqueda(1);
			echo "<script>document.getElementById('enviar').disabled=true; </script>";
		}
		else echo "<script>document.getElementById('enviar').disabled=false; </script>";
		$this->set('opcion_radio',$var);

	}else{
		$this->set('opcion_radio',3);
		$this->set('cedula',$cedula);
		$this->set('codigo_ficha',$codigo_ficha);
		echo "<script>document.getElementById('enviar').disabled=false; </script>";
		$campos = "cod_act_edo,cod_act_mun,cod_act_prr,cod_act_amb_t,cod_act_amb,cod_act_sec,cod_act_man,cod_act_par,cod_act_sbp,cod_act_niv,cod_act_und";
		$datosgrilla=$this->v_catd02_ficha_datos->findAll($this->SQLCA()." and cod_ficha=".$codigo_ficha,$campos);
		$this->set('datosgrilla',$datosgrilla);

	}

}


//funciones delas firmas
function envia_form_firmas($num_tipo_doc=null){
    $this->layout="ajax";

	if($num_tipo_doc!=null){

	$firmantes = $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=".$num_tipo_doc);

	if($firmantes!=null){
		$this->set('firma_existe','si');
		$this->set('b_readonly','readonly');
		$this->set('tipo_documento',$firmantes[0]['cugd07_firmas_oficio_anulacion']['tipo_documento']);
		$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
	}else{
		$this->set('Message_existe','POR FAVOR, INGRESE LOS NOMBRES Y CARGO DE LOS FIRMANTES');
		$this->set('firma_existe','no');
		$this->set('b_readonly','');
		$this->set('tipo_documento',$num_tipo_doc);
		$this->set('nombre_primera_firma','');
		$this->set('cargo_primera_firma','');
		$this->set('nombre_segunda_firma','');
		$this->set('cargo_segunda_firma','');
		$this->set('nombre_tercera_firma','');
		$this->set('cargo_tercera_firma','');
	}

	}else{
		$this->set('errorMessage','Disculpe, No llego el N&uacute;mero del Tipo de Documento para realizar el proceso de firmas');
	} // fin else num_tipo_doc dif. null

} // fin funcion envia_form_firmas



function guardar_editar_firmas($reporte_tipo=null){
	$this->layout="ajax";

	$cp  = $this->Session->read('SScodpresi');
	$ce  = $this->Session->read('SScodentidad');
	$cti = $this->Session->read('SScodtipoinst');
	$ci  = $this->Session->read('SScodinst');
	$cd  = $this->Session->read('SScoddep');


	$tipo_doc = $this->tipo_documento($reporte_tipo);

	$nombre_primera_firma = $this->data['cugd07_firmas_oficio_anulacion']['nombre_primera_firma'];
	$cargo_primera_firma  = $this->data['cugd07_firmas_oficio_anulacion']['cargo_primera_firma'];
	$nombre_segunda_firma = isset($this->data['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']) ? $this->data['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma'] : "n/a";
	$cargo_segunda_firma  = isset($this->data['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']) ? $this->data['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma'] : "n/a";
	$nombre_tercera_firma = isset($this->data['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']) ? $this->data['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma'] : "n/a";
	$cargo_tercera_firma  = isset($this->data['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']) ? $this->data['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma'] : "n/a";
	$nombre_cuarta_firma =  'n/a';
	$cargo_cuarta_firma  =  'n/a';
	$nombre_quinta_firma =  'n/a';
	$cargo_quinta_firma  =  'n/a';
	$nombre_sexta_firma  =  'n/a';
	$cargo_sexta_firma   =  'n/a';
	$nombre_septima_firma =  'n/a';
	$cargo_septima_firma  =  'n/a';
	$nombre_octava_firma  =  'n/a';
	$cargo_octava_firma   = 'n/a';

	$primera_cc = isset($this->data['cugd07_firmas_oficio_anulacion']['primera_copia'])? $this->data['cugd07_firmas_oficio_anulacion']['primera_copia'] : 'n/a';
	$segunda_cc = isset($this->data['cugd07_firmas_oficio_anulacion']['segunda_copia'])? $this->data['cugd07_firmas_oficio_anulacion']['segunda_copia'] : 'n/a';
	$tercera_cc = isset($this->data['cugd07_firmas_oficio_anulacion']['tercera_copia'])? $this->data['cugd07_firmas_oficio_anulacion']['tercera_copia'] : 'n/a';
	$cuarta_cc  = isset($this->data['cugd07_firmas_oficio_anulacion']['cuarta_copia'])? $this->data['cugd07_firmas_oficio_anulacion']['cuarta_copia'] : 'n/a';
	$quinta_cc  = isset($this->data['cugd07_firmas_oficio_anulacion']['quinta_copia'])? $this->data['cugd07_firmas_oficio_anulacion']['quinta_copia'] : 'n/a';
	$sexta_cc   = isset($this->data['cugd07_firmas_oficio_anulacion']['sexta_copia'])? $this->data['cugd07_firmas_oficio_anulacion']['sexta_copia'] : 'n/a';
	$septima_cc = isset($this->data['cugd07_firmas_oficio_anulacion']['septima_copia'])? $this->data['cugd07_firmas_oficio_anulacion']['septima_copia'] : 'n/a';
	$octava_cc  = isset($this->data['cugd07_firmas_oficio_anulacion']['octava_copia'])? $this->data['cugd07_firmas_oficio_anulacion']['octava_copia'] : 'n/a';
	$pie_pagina = 'n/a';

  /*
	$pie_pagina = $this->data['cugd07_firmas_oficio_anulacion']['pie_pagina'];
	$pie_pagina = str_replace("\t"," ",$pie_pagina);
	$pie_pagina = str_replace("\n"," ",$pie_pagina);
*/
	$enc_td_firma = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA()." and tipo_documento=".$tipo_doc);

	if($enc_td_firma==0){
		$muestr_accion = 'Registradas';
		$sql_ejecutar = "INSERT INTO cugd07_firmas_oficio_anulacion VALUES ($cp, $ce, $cti, $ci, $cd, $tipo_doc,'$nombre_primera_firma', '$cargo_primera_firma', '$nombre_segunda_firma', '$cargo_segunda_firma', '$nombre_tercera_firma', '$cargo_tercera_firma', '$nombre_cuarta_firma', '$cargo_cuarta_firma', '$primera_cc', '$segunda_cc', '$tercera_cc', '$cuarta_cc', '$quinta_cc', '$sexta_cc', '$septima_cc', '$octava_cc');";
	}else{
		$muestr_accion = 'Modificadas';
		$sql_ejecutar = "UPDATE cugd07_firmas_oficio_anulacion SET nombre_primera_firma='$nombre_primera_firma', cargo_primera_firma='$cargo_primera_firma', nombre_segunda_firma='$nombre_segunda_firma', cargo_segunda_firma='$cargo_segunda_firma', nombre_tercera_firma='$nombre_tercera_firma', cargo_tercera_firma='$cargo_tercera_firma', nombre_cuarta_firma='$nombre_cuarta_firma', cargo_cuarta_firma='$cargo_cuarta_firma', primera_copia='$primera_cc', segunda_copia='$segunda_cc', tercera_copia='$tercera_cc', cuarta_copia='$cuarta_cc', quinta_copia='$quinta_cc', sexta_copia='$sexta_cc', septima_copia='$septima_cc', octava_copia='$octava_cc' WHERE ".$this->SQLCA()." and tipo_documento=".$tipo_doc;
	}

	$swi = $this->cugd07_firmas_oficio_anulacion->execute($sql_ejecutar);

	if($swi>1){
		$this->set('Message_existe','Las firmas fueron '.$muestr_accion.' correctamente');
	}else{
		$this->set('errorMessage','Las firmas no fueron '.$muestr_accion.'');
	}

	if($reporte_tipo==1){
		$this->reporte_info_catastral('si');
		$this->render('reporte_info_catastral');
	}else if($reporte_tipo==2){
		$this->constancia_catastro(0,0,'si');
		$this->render('constancia_catastro');
	}else if($reporte_tipo==3){
		$this->reporte_ficha_insc_catastral(0,0,'si');
		$this->render('reporte_ficha_insc_catastral');
	}else if($reporte_tipo==4){
		$this->cedula_catastro('si');
		$this->render('cedula_catastro');

	}else if($reporte_tipo==5){
		$this->reporte_calculos_impuesto(0,0,'si');
		$this->render('reporte_calculos_impuesto');
	}

} // fin funcion guardar_editar_firmas

function modificar_firmas_form($reportem_tipo=null){
	$this->layout="ajax";
	$this->set('reportem_tipo',$reportem_tipo);
	$this->set('Message_existe','Puede modificar los nombres y cargos de los firmantes');
}
//fin funciones firmas


//busqueda por pista

function buscar_datos_ficha($var1=null, $cod=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
	echo "<script>$('campo_pista_ficha').focus();</script>";
}//fin function


function buscar_datos_porpista($var1=null, $var2=null, $var3=null){
	$this->layout="ajax";
	$modelo='v_catd02_ficha_datos';
    if($var3==null){ $var2 = strtoupper($var2);
					 $this->Session->write('pista', $var2);
					 $Tfilas=$this->$modelo->findCount($this->SQLCA()." and ((cod_ficha::text LIKE '%$var2%') or (cedula_rif_repre::text LIKE '%$var2%') or (quitar_acentos(nombre_repre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(terreno_sector) LIKE quitar_acentos('%$var2%')))");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
								$datos_filas=$this->$modelo->findAll($this->SQLCA()." and ((cod_ficha::text LIKE '%$var2%') or (cedula_rif_repre::text LIKE '%$var2%') or (quitar_acentos(nombre_repre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(terreno_sector) LIKE quitar_acentos('%$var2%')))","cod_ficha, cedula_rif_repre, nombre_repre, terreno_sector","cod_ficha, cedula_rif_repre, nombre_repre ASC",50,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
								$this->set('total_paginas','');
								$this->set('pagina_actual','');
							    $this->set('siguiente','');
								$this->set('anterior','');
								$this->set('ultimo','');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);
						$Tfilas=$this->$modelo->findCount($this->SQLCA()." and ((cod_ficha::text LIKE '%$var2%') or (cedula_rif_repre::text LIKE '%$var2%') or (quitar_acentos(nombre_repre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(terreno_sector) LIKE quitar_acentos('%$var2%')))");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
									$datos_filas=$this->$modelo->findAll($this->SQLCA()." and ((cod_ficha::text LIKE '%$var2%') or (cedula_rif_repre::text LIKE '%$var2%') or (quitar_acentos(nombre_repre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(terreno_sector) LIKE quitar_acentos('%$var2%')))","cod_ficha, cedula_rif_repre, nombre_repre, terreno_sector","cod_ficha, cedula_rif_repre, nombre_repre ASC",50,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
									$this->set('total_paginas','');
									$this->set('pagina_actual','');
							    	$this->set('siguiente','');
									$this->set('anterior','');
									$this->set('ultimo','');
						          }
   		}//fin else
$this->set("opcion",$var1);

} //fin funcion



}//fin clase
?>
