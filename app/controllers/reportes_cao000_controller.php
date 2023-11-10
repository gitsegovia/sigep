<?php


class ReportesCao000Controller extends AppController{

    var $uses = array( 'cfpd97','arrd05','cfpd07_plan_inversion','cfpd09_metas_sector', 'cnmd04_tipo','cnmd04_ocupacion','cugd01_estados',
                      'cfpd08', 'cfpd01_formulacion','cfpd03', 'cnmd05_clasificacion','cnmd04_ocupacion', 'cnmd04_tipo',
                      'cfpd07_clasificacion_recurso','cugd01_municipios', 'cugd01_parroquias', 'cugd01_centropoblados','cfpd05',
                       'cfpd05_2032_tmp','cfpd05_distribucion_tmp', 'ccfd03_instalacion', 'cugd06_oficios_poremitir_comun', 'cstd06_comprobante_poremitir_egreso',
                      'cugd01_vialidad', 'cugd01_vereda', 'cugd02_institucion', 'cugd02_dependencia','cfpd05_auxiliar', 'cstd01_entidades_bancarias',
                      'cugd02_direccionsuperior', 'cugd02_coordinacion', 'cugd02_secretaria', 'cfpd05_requerimiento', 'cepd01_compromiso_cuerpo',
                      'cugd02_direccion', 'cugd02_division', 'cugd02_departamento', 'cugd02_oficina', 'cnmd01','cnmd05_escala_salario',
                      'cnmd05_escala_sueldo', 'v_cscd04_ordencompra', 'v_cscd03_cotizacion', 'cscd04_ordencompra_numero', 'ccfd04_cierre_mes',
                      'cfpd01_grupo', 'cfpd01_partida', 'cfpd01_generica', 'cfpd01_especifica', 'cobd01_contratoobras_partidas',
                      'cfpd01_sub_espec', 'cfpd01_auxiliar', 'cfpd01_ano_grupo', 'cfpd01_ano_partida','cfpd02_sector', 'cfpd02_programa',
                       'cfpd02_sub_prog', 'cfpd02_proyecto', 'cfpd02_activ_obra', 'cfpd01_formulacion', 'cscd02_solicitud_numero_poremitir',
                      'cfpd01_ano_generica', 'cfpd01_ano_especifica', 'cfpd01_ano_sub_espec', 'cnmd05_clasificacion','cfpd07_obras_cuerpo',
                       'cfpd07_obras_partidas', 'cscd04_ordencompra_poremitir_servicio', 'cscd04_ordencompra_poremitir_bienes',
                      'cfpd01_ano_auxiliar', 'cfpd01_grupo', 'cfpd01_partida', 'cfpd01_generica','cfpd09_metas_actividad', 'cstd02_cuentas_bancarias',
                      'cfpd01_especifica', 'cfpd01_sub_espec', 'cfpd01_auxiliar', 'Cnmd02_obreros_ramos', 'Cnmd02_obreros_grupos',
                      'Cnmd02_obreros_series', 'Cnmd02_obreros_puestos', 'cepd03_ordenpago_poremitir', 'cepd01_compromiso_poremitir',
                      'Cnmd02_empleados_ramos', 'Cnmd02_empleados_grupos','Cnmd02_empleados_series', 'Cnmd02_empleados_puestos', 'cugd02_dependencia',
                      'Usuario','cfpd07','distribucion','distribucion_institucional', 'cepd03_ordenpago_numero', 'cepd03_ordenpago_facturas',
                      'cscd04_ordencompra_encabezado', 'cscd03_cotizacion_encabezado', 'cpcd02', 'cscd02_solicitud_encabezado', 'cugd02_direccion',
                      'cscd03_cotizacion_cuerpo', 'cscd04_ordencompra_partidas','cepd03_ordenpago_cuerpo','cepd03_ordenpago_partidas','cepd03_ordenpago_tipopago',
                      'cepd03_tipo_documento', 'cepd01_compromiso_partidas', 'cepd01_tipo_compromiso', 'cepd01_compromiso_numero', 'cscd01_catalogo', 'cscd01_unidad_medida',
                      'cscd02_solicitud_cuerpo', 'cstd03_cheque_poremitir', 'cstd01_sucursales_bancarias',  'cstd02_cuentas_bancarias', 'cstd06_comprobante_cuerpo_egreso',
	                  'cstd03_cheque_cuerpo', 'cstd06_comprobante_cuerpo_iva',  'cstd06_comprobante_poremitir_iva' , 'cugd06_oficios_poremitir_comun'
	                  , 'cstd06_comprobante_poremitir_islr', 'cstd06_comprobante_poremitir_timbre',  'cstd06_comprobante_poremitir_municipal',  'cstd06_comprobante_cuerpo_iva',
	                    'cstd06_comprobante_poremitir_iva' , 'cugd06_oficios_poremitir_comun','csrd01_solicitud_recurso_cuerpo',
	                  'csrd01_solicitud_recurso_partidas','Usuario','cugd05_restriccion_tipo','cfpd10_reformulacion_tipo','cugd05_restriccion_clave','cugd05_restriccion_tipo',
	                  'cstd07_retenciones_cuerpo_islr','cstd07_retenciones_cuerpo_iva','cstd07_retenciones_cuerpo_timbre','cstd07_retenciones_cuerpo_municipal',
	                  'cscd04_ordencompra_parametros','cpcd02','cobd01_contratoobras_cuerpo','cepd02_contratoservicio_cuerpo','csrd01_tipo_solicitud',
	                  "v_cobp01_cfpd07_partidas", "v_cobp01_cfpd07_cuerpo","cugd07_firmas_oficio_anulacion", 'cstd07_retenciones_cuerpo_multa', 'cstd07_retenciones_cuerpo_responsabilidad', 'cstd07_retenciones_partidas_multa', 'cstd07_retenciones_partidas_responsabilidad',
                      'cstd06_comprobante_poremitir_multa', 'cstd06_comprobante_poremitir_responsabilidad', 'cstd06_comprobante_numero_multa', 'cstd06_comprobante_numero_responsabilidad',
                      'cstd06_comprobante_cuerpo_multa', 'cstd06_comprobante_cuerpo_responsabilidad','cscd04_ordencompra_autorizacion_cuerpo','v_cao_reporte_1','v_cao_reporte_1_fecha','v_cao_reporte_2','v_cao_reporte_detallado','v_cao_reporte_detallado_fecha'
	                  );

	var $helpers = array('Html','Ajax','Javascript', 'Sisap', 'Fpdf');


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

function add_c_c($var){
		if($var<=9 && strlen($var)==1){
				$codigo = '0'.$var;
			}else{$codigo = ''.$var;}
		return $codigo;
}//fin AddCero


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

    }


function limpia_menu(){

    	 echo'<script>
							 document.getElementById("valida_codigo").innerHTML = "";
							 document.getElementById("valida_codigo").style.display = "none";
							 if(document.getElementById("registro")){document.getElementById("registro").style.visibility = "hidden";}
                             if(document.getElementById("nomina")){document.getElementById("nomina").style.visibility = "hidden";}
                             if(document.getElementById("dependencia")){document.getElementById("dependencia").style.visibility = "hidden";}
                             if(document.getElementById("sistema_general")){document.getElementById("sistema_general").style.visibility = "hidden";}
               </script>';


}







function cscp02_solicitud_cotizacion($year=null, $opcion=null){

		   $this->layout = "ajax";

		    $cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
			$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;

		$this->Session->delete('FIN');



		if($year == "vista"){

		  $ano = $this->ano_ejecucion();


		$this->set('year', $ano);
		$this->set('vista', $opcion);


		}else{



		if(!$year){


		    $this->limpia_menu();
		    $this->set('ir', 'no');



		    $ano = $this->ano_ejecucion();

			if(!empty($dato)){

				$this->set('year', $dato);

			}else{
				$this->set('year', '');
			}




		$sql = "delete from cugd06_oficios_poremitir_comun  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=5  ";
		$this->cugd06_oficios_poremitir_comun->execute($sql);



		$datos_cscd02_solicitud_numero_poremitir = $this->cscd02_solicitud_numero_poremitir->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' ");
		$this->set('datos_cscd02_solicitud_numero_poremitir', $datos_cscd02_solicitud_numero_poremitir);
		$this->set('usuario', $this->Session->read('nom_usuario'));



		}else{


		  	$this->layout = "pdf";
		  	$radio = 1;
		  	$numero_solicitud_a  = "";
		  	$numero_solicitud_b  = "";


		         if(isset($this->data['cscp02_solicitud_cotizacion']['radio'])){   $radio              =  $this->data['cscp02_solicitud_cotizacion']['radio'];}
		  		 if(isset($this->data['cscp02_solicitud_cotizacion']['ano'])){     $ano_solicitudo_a   =  $this->data['cscp02_solicitud_cotizacion']['ano'];}
		  		 if(isset($this->data['cscp02_solicitud_cotizacion']['numero_a'])){$numero_solicitud_a =  $this->data['cscp02_solicitud_cotizacion']['numero_a'];}
		  		 if(isset($this->data['cscp02_solicitud_cotizacion']['numero_b'])){$numero_solicitud_b =  $this->data['cscp02_solicitud_cotizacion']['numero_b'];}






		     if($radio=="3" && $numero_solicitud_a!=""){////////////////////////////////////////////////OPCION 1


		    $datos_cscd02_solicitud_cuerpo     = $this->cscd02_solicitud_cuerpo->findAll(    $condicion." and ano_solicitud=".$ano_solicitudo_a."  and numero_solicitud='$numero_solicitud_a' ", null, 'codigo_prod_serv DESC' );
			$datos_cscd02_solicitud_encabezado = $this->cscd02_solicitud_encabezado->findAll($condicion." and ano_solicitud=".$ano_solicitudo_a."  and numero_solicitud='$numero_solicitud_a' ");

		$sql_rif_a = "";

		 $aux= 0;
		foreach($datos_cscd02_solicitud_encabezado as $aux_cscd02_solicitud_encabezado){$aux++;
		        $cugd02_secretaria    =  $this->cugd02_secretaria->findAll("       cod_tipo_institucion=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_tipo_inst']." and cod_institucion=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_inst']." and cod_dependencia=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_dep']." and cod_dir_superior=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_dir_superior']." and cod_coordinacion=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_coordinacion']." and cod_secretaria=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_secretaria'],array('denominacion'));
				$cugd02_direccion     =  $this->cugd02_direccion->findAll("        cod_tipo_institucion=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_tipo_inst']." and cod_institucion=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_inst']." and cod_dependencia=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_dep']." and cod_dir_superior=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_dir_superior']." and cod_coordinacion=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_coordinacion']." and cod_secretaria=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_secretaria']." and cod_direccion=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_direccion'],array('denominacion'));
				$secretaria[$aux]   = $cugd02_secretaria[0]['cugd02_secretaria']['denominacion'];
				$direccion[$aux]    = $cugd02_direccion[0]['cugd02_direccion']['denominacion'];
				$cod_obra[$aux]     = $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_obra'];
		}//fin foreach


		$sql_1="";
		$ii = 0;
		foreach($datos_cscd02_solicitud_cuerpo as $aux_cscd02_solicitud_cuerpo){ $ii++;
		  $ano_solicitudo_1[$ii]     =   $aux_cscd02_solicitud_cuerpo['cscd02_solicitud_cuerpo']['ano_solicitud'];
		  $numero_solicitud_1[$ii]   =   $aux_cscd02_solicitud_cuerpo['cscd02_solicitud_cuerpo']['numero_solicitud'];
		  $cod_medida[$ii]   =   $aux_cscd02_solicitud_cuerpo['cscd02_solicitud_cuerpo']['cod_medida'];

		if($sql_1==""){$sql_1 .= "cod_medida='".$cod_medida[$ii]."'  ";}else{$sql_1 .= " or cod_medida='".$cod_medida[$ii]."'  ";}

		$sql_rif_a ="kjabsdvas";
		}//fin for

		if($sql_rif_a==""){
			echo'<script>history.back(1);</script>';
		}else{


		$y          =  $this->cscd01_unidad_medida->findAll($sql_1);

		}//fin script




		}else if($radio=="2" && $numero_solicitud_a!="" && $numero_solicitud_b!=""){///////////////////////////////////////OPCION 2


		    $datos_cscd02_solicitud_cuerpo     = $this->cscd02_solicitud_cuerpo->findAll(    $condicion." and ano_solicitud=".$ano_solicitudo_a."  and (numero_solicitud>='$numero_solicitud_a'  and  numero_solicitud<='$numero_solicitud_b') ", null, 'codigo_prod_serv DESC' );
			$datos_cscd02_solicitud_encabezado = $this->cscd02_solicitud_encabezado->findAll($condicion." and ano_solicitud=".$ano_solicitudo_a."  and (numero_solicitud>='$numero_solicitud_a'  and  numero_solicitud<='$numero_solicitud_b') ");

		$sql_rif_a="";


		 $aux= 0;
		foreach($datos_cscd02_solicitud_encabezado as $aux_cscd02_solicitud_encabezado){$aux++;
		        $cugd02_secretaria    =  $this->cugd02_secretaria->findAll("       cod_tipo_institucion=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_tipo_inst']." and cod_institucion=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_inst']." and cod_dependencia=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_dep']." and cod_dir_superior=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_dir_superior']." and cod_coordinacion=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_coordinacion']." and cod_secretaria=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_secretaria'],array('denominacion'));
				$cugd02_direccion     =  $this->cugd02_direccion->findAll("        cod_tipo_institucion=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_tipo_inst']." and cod_institucion=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_inst']." and cod_dependencia=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_dep']." and cod_dir_superior=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_dir_superior']." and cod_coordinacion=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_coordinacion']." and cod_secretaria=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_secretaria']." and cod_direccion=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_direccion'],array('denominacion'));
				$secretaria[$aux]     =  $cugd02_secretaria[0]['cugd02_secretaria']['denominacion'];
				$direccion[$aux]      =  $cugd02_direccion[0]['cugd02_direccion']['denominacion'];
				$cod_obra[$aux]       = $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_obra'];
		}//fin foreach



		$sql_1="";
		$ii = 0;
		foreach($datos_cscd02_solicitud_cuerpo as $aux_cscd02_solicitud_cuerpo){ $ii++;
		  $ano_solicitudo_1[$ii]     =   $aux_cscd02_solicitud_cuerpo['cscd02_solicitud_cuerpo']['ano_solicitud'];
		  $numero_solicitud_1[$ii]   =   $aux_cscd02_solicitud_cuerpo['cscd02_solicitud_cuerpo']['numero_solicitud'];
		  $cod_medida[$ii]           =   $aux_cscd02_solicitud_cuerpo['cscd02_solicitud_cuerpo']['cod_medida'];

		if($sql_1==""){$sql_1 .= "cod_medida='".$cod_medida[$ii]."'  ";}else{$sql_1 .= " or cod_medida='".$cod_medida[$ii]."'  ";}


		$sql_rif_a="jbadfa";
		}//fin for


		if($sql_rif_a==""){
			echo'<script>history.back(1);</script>';
		}else{
		$y          =  $this->cscd01_unidad_medida->findAll($sql_1);

		}//finscript



		}else if($radio=="1"){//////////////////////////////////////////////////////////////////////////////////OPCIOPN 3




		$datos_cscd02_solicitud_numero_poremitir     =   $this->cscd02_solicitud_numero_poremitir->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' " );

		$ii=0;
		$sql_1 = "";

		foreach($datos_cscd02_solicitud_numero_poremitir as $aux_cscd02_solicitud_numero_poremitir){ $ii++;

		  $ano_solicitudo_1[$ii]     =   $aux_cscd02_solicitud_numero_poremitir['cscd02_solicitud_numero_poremitir']['ano_solicitud'];
		  $numero_solicitud_1[$ii]   =   $aux_cscd02_solicitud_numero_poremitir['cscd02_solicitud_numero_poremitir']['numero_solicitud'];


		if($sql_1==""){$sql_1   .= "   ano_solicitud='".$ano_solicitudo_1[$ii]."' and  numero_solicitud='".$numero_solicitud_1[$ii]."'  ";
		         }else{$sql_1   .= " or  (ano_solicitud='".$ano_solicitudo_1[$ii]."' and  numero_solicitud='".$numero_solicitud_1[$ii]."')  ";}

		}//fin for



		if($sql_1==""){


			echo'<script>history.back(1);</script>';

		}else{

			$datos_cscd02_solicitud_cuerpo     = $this->cscd02_solicitud_cuerpo->findAll(    $condicion." and (".$sql_1.") ", null, 'codigo_prod_serv DESC' );
		    $datos_cscd02_solicitud_encabezado = $this->cscd02_solicitud_encabezado->findAll($condicion." and (".$sql_1.")");


		 $aux= 0;
		foreach($datos_cscd02_solicitud_encabezado as $aux_cscd02_solicitud_encabezado){$aux++;
		        $cugd02_secretaria    =  $this->cugd02_secretaria->findAll("       cod_tipo_institucion=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_tipo_inst']." and cod_institucion=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_inst']." and cod_dependencia=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_dep']." and cod_dir_superior=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_dir_superior']." and cod_coordinacion=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_coordinacion']." and cod_secretaria=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_secretaria'],array('denominacion'));
				$cugd02_direccion     =  $this->cugd02_direccion->findAll("        cod_tipo_institucion=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_tipo_inst']." and cod_institucion=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_inst']." and cod_dependencia=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_dep']." and cod_dir_superior=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_dir_superior']." and cod_coordinacion=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_coordinacion']." and cod_secretaria=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_secretaria']." and cod_direccion=".$aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_direccion'],array('denominacion'));
				$secretaria[$aux]   = $cugd02_secretaria[0]['cugd02_secretaria']['denominacion'];
				$direccion[$aux]    = $cugd02_direccion[0]['cugd02_direccion']['denominacion'];
				$cod_obra[$aux]     = $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_obra'];
		}//fin foreach



		$sql_1="";
		$ii = 0;
		foreach($datos_cscd02_solicitud_cuerpo as $aux_cscd02_solicitud_cuerpo){ $ii++;
		  $ano_solicitudo_1[$ii]     =   $aux_cscd02_solicitud_cuerpo['cscd02_solicitud_cuerpo']['ano_solicitud'];
		  $numero_solicitud_1[$ii]   =   $aux_cscd02_solicitud_cuerpo['cscd02_solicitud_cuerpo']['numero_solicitud'];
		  $cod_medida[$ii]   =   $aux_cscd02_solicitud_cuerpo['cscd02_solicitud_cuerpo']['cod_medida'];

		if($sql_1==""){$sql_1 .= "cod_medida='".$cod_medida[$ii]."'  ";}else{$sql_1 .= " or cod_medida='".$cod_medida[$ii]."'  ";}

		}//fin for

		$y          =  $this->cscd01_unidad_medida->findAll($sql_1);



		$sql = "delete from cscd02_solicitud_numero_poremitir  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
		$this->cscd02_solicitud_numero_poremitir->execute($sql);


		}//fin else


		                  }//fin else



		  $this->set('secretaria',$secretaria);
		  $this->set('direccion',$direccion);
		  $this->set('cod_obra',$cod_obra);
		  $this->set('unidad_medida',$y);
		  $this->set('datos_cscd02_solicitud_cuerpo',    $datos_cscd02_solicitud_cuerpo);
		  $this->set('datos_cscd02_solicitud_encabezado',$datos_cscd02_solicitud_encabezado);

		  $sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=5  ";
		  $this->set('cugd06_oficios_poremitir_comun', $this->cugd06_oficios_poremitir_comun->findAll($sql, null, 'numero ASC'));


		}//fin else





		$this->set('titulo_a', $this->Session->read('dependencia'));
		$this->set('entidad_federal', $this->Session->read('entidad_federal'));






		}//fin else

}//fin funtion




//////////////////////////////////////////////////////////////////////////////////////////////




function cscp04_ordencompra_bienes($year=null, $opcion=null, $cont=null){

   $this->layout = "ajax";

    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $sql_para_por_emitir = "";


$this->Session->delete('FIN');

if($year == "vista"){

 $ano = $this->ano_ejecucion();


$this->set('year', $ano);
$this->set('vista', $opcion);

$url                  =  "/caop00_select_ventana_generar_reporte/v_reporte_emision_compra_bienes_1";
$width_aux            =  "750px";
$height_aux           =  "400px";
$title_aux            =  "Buscar";
$resizable_aux        =  false;
$maximizable_aux      =  false;
$minimizable_aux      =  false;
$closable_aux         =  false;

	  if($opcion==3){
	         echo"<script>";
	           echo  "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
	         echo"</script>";
    	}else{
              echo"<script>";
               echo  " Windows.close(document.getElementById('capa_ventana').value)";
              echo"</script>";

	}//fin else




}else{

if(!$year){


    $this->limpia_menu();
    $this->set('ir', 'no');



    $ano = $this->ano_ejecucion();
    $dato = $this->ano_ejecucion();

	if(!empty($dato)){

		$this->set('year', $dato);

	}else{
		$this->set('year', '');
	}




$sql = "delete from cugd06_oficios_poremitir_comun  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=1  ";
$this->cugd06_oficios_poremitir_comun->execute($sql);


$datos_cscd04_ordencompra_poremitir_bienes = $this->cscd04_ordencompra_poremitir_bienes->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' ");

$this->set('datos_cscd04_ordencompra_poremitir_bienes', $datos_cscd04_ordencompra_poremitir_bienes);
$this->set('usuario', $this->Session->read('nom_usuario'));


}else{


  	$this->layout = "pdf";
  	$radio = 1;
  	$ordencompra_encabezado = "";
  	$datos_cugd02_direccion = "";
  	$numero_orden_compra_a  = "";
  	$numero_orden_compra_b  = "";


         if(isset($this->data['cscp04_ordencompra_bienes']['radio'])){            $radio                 = $this->data['cscp04_ordencompra_bienes']['radio'];}
  		 if(isset($this->data['cscp04_ordencompra_bienes']['ano'.$cont.''])){     $ano_orden_compra_a    = $this->data['cscp04_ordencompra_bienes']['ano'.$cont.''];}
  		 if(isset($this->data['cscp04_ordencompra_bienes']['numero_a'.$cont.''])){$numero_orden_compra_a = $this->data['cscp04_ordencompra_bienes']['numero_a'.$cont.''];}
  		 if(isset($this->data['cscp04_ordencompra_bienes']['numero_b'])){         $numero_orden_compra_b = $this->data['cscp04_ordencompra_bienes']['numero_b'];}

//montar direccion en el footer de la orden de pago - alcaldia san fernando
		$sql_direccion_pie_pagina = "SELECT direccion FROM cugd02_dependencias WHERE cod_tipo_institucion=$cod_tipo_inst AND cod_institucion=$cod_inst AND cod_dependencia=$cod_dep";
        $data_direccion_pie_pagina = $this->cepd03_ordenpago_cuerpo->execute($sql_direccion_pie_pagina);
        $this->set('direccion_pie_pagina',$data_direccion_pie_pagina[0][0]['direccion']);

        $sql_unidad_tributaria = "SELECT unidad_tributaria FROM cscd04_ordencompra_parametros WHERE ".$this->SQLCA();
        $data_unidad_tributaria = $this->cepd03_ordenpago_cuerpo->execute($sql_unidad_tributaria);
        if(count($data_unidad_tributaria)>0){
        	$this->set('unidad_tributaria_pie',$data_unidad_tributaria[0][0]['unidad_tributaria']);
        }else{
        	$this->set('unidad_tributaria_pie',0);
        }


if($radio=="3" && $numero_orden_compra_a!=""){


    $ordencompra_encabezado = $this->cscd04_ordencompra_encabezado->findAll($condicion." and ano_orden_compra=".$ano_orden_compra_a."  and numero_orden_compra='$numero_orden_compra_a'  and  tipo_orden=1 and condicion_actividad=1", null , 'numero_orden_compra ASC');
	$ordencompra_partidas   = $this->cscd04_ordencompra_partidas->findAll($condicion."       and ano_orden_compra=".$ano_orden_compra_a."  and numero_orden_compra='$numero_orden_compra_a' ");

$sql_para_por_emitir = " and ano=".$ano_orden_compra_a."  and numero='$numero_orden_compra_a'";

$sql_rif_a = "";

foreach($ordencompra_encabezado as $aux_cscd04_ordencompra_encabezado){
  $ano_orden_compra     =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_orden_compra'];
  $numero_orden_compra  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_orden_compra'];
  $tipo_orden           =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['tipo_orden'];
  $rif                  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['rif'];
  $ano_cotizacion       =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_cotizacion'];
  $numero_cotizacion    =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_cotizacion'];
  $monto_orden          =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['monto_orden'];



$resultado=$this->cscd04_ordencompra_numero->execute("UPDATE cscd04_ordencompra_numero  SET  situacion = '3'  WHERE ".$condicion." and ano_orden_compra='".$ano_orden_compra."' and  numero_orden_compra='".$numero_orden_compra."' and situacion='2'  ");


  $sql_rif_a = "asdasd";
}//fin


//echo $condicion." and rif='".$rif."' and ano_cotizacion='".$ano_cotizacion."' and numero_cotizacion='".$numero_cotizacion."'  ";


if($sql_rif_a==""){


	echo'<script>history.back(1);</script>';

}else{




$datos_cscd03_cotizacion_encabezado     =   $this->cscd03_cotizacion_encabezado->findAll($condicion." and rif='".$rif."' and ano_cotizacion='".$ano_cotizacion."' and numero_cotizacion='".$numero_cotizacion."'  ");
$datos_cscd03_cotizacion_cuerpo         =   $this->cscd03_cotizacion_cuerpo->findAll($condicion."     and rif='".$rif."' and ano_cotizacion='".$ano_cotizacion."' and numero_cotizacion='".$numero_cotizacion."'  ", null , 'codigo_prod_serv DESC' );
$lista                                  =   $this->v_cscd03_cotizacion->findAll($condicion."          and rif='".$rif."' and ano_cotizacion='".$ano_cotizacion."' and numero_cotizacion='".$numero_cotizacion."' and numero_ordencompra='$numero_orden_compra' and ano_ordencompra='$ano_orden_compra'",null , 'codigo_prod_serv DESC' );
$datos_cpcd02                           =   $this->cpcd02->findAll(" rif='".$rif."' ");







foreach($ordencompra_encabezado as $aux_cscd04_ordencompra_encabezado){
  $ano_orden_compra     =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_orden_compra'];
  $numero_orden_compra  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_orden_compra'];
  $tipo_orden           =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['tipo_orden'];
  $rif                  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['rif'];
  $ano_cotizacion       =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_cotizacion'];
  $numero_cotizacion    =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_cotizacion'];
  $monto_orden          =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['monto_orden'];



foreach($datos_cpcd02 as $aux_cpcd02){
     if($rif == $aux_cpcd02['cpcd02']['rif']){
     	if($this->cugd06_oficios_poremitir_comun->findCount($sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=1  and ano='".$ano_orden_compra."'  and numero='".$numero_orden_compra."' ")==0){
         $sql_por_emiter  = "INSERT INTO cugd06_oficios_poremitir_comun (cod_presi, cod_entidad , cod_tipo_inst, cod_inst, cod_dep, username, documento, ano, numero, beneficiario, monto)";
         $sql_por_emiter .= " VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$this->Session->read('nom_usuario')."', '1', '".$ano_orden_compra."', '".$numero_orden_compra."', '".$aux_cpcd02['cpcd02']['denominacion']."', '".$monto_orden."')";
         $R3=$this->cugd06_oficios_poremitir_comun->execute($sql_por_emiter);
     	}//fin if
     	break;
    }//fin
  }//fin foreach



}//fin







foreach($datos_cscd03_cotizacion_encabezado as $aux_cscd03_cotizacion_encabezado){
  $ano_cotizacion          =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['ano_cotizacion'];
  $numero_cotizacion       =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['numero_cotizacion'];
  $ano_solicitud           =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['ano_solicitud'];
  $numero_solicitud        =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['numero_solicitud'];
}//fin



$datos_cscd02_solicitud_encabezado      =   $this->cscd02_solicitud_encabezado->findAll($condicion." and ano_solicitud='".$ano_solicitud."' and numero_solicitud='".$numero_solicitud."'  ");

foreach($datos_cscd02_solicitud_encabezado as $aux_cscd02_solicitud_encabezado){
  $cod_dir_superior     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_dir_superior'];
  $cod_coordinacion     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_coordinacion'];
  $cod_secretaria       =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_secretaria'];
  $cod_direccion        =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_direccion'];
  $cod_division         =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_division'];
  $cod_departamento     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_departamento'];
  $cod_oficina          =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_oficina'];
}//fin

$sql_cugd02_direccion  = " cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia = ".$cod_dep." and cod_dir_superior = ".$cod_dir_superior." ";
$sql_cugd02_direccion .= " and  cod_coordinacion=".$cod_coordinacion." and  cod_secretaria=".$cod_secretaria." and cod_direccion=".$cod_direccion." ";
$datos_cugd02_direccion      =   $this->cugd02_direccion->findAll($sql_cugd02_direccion);


}//fin de script








}else if($radio=="2" && $numero_orden_compra_a!="" && $numero_orden_compra_b!=""){






    $ordencompra_encabezado = $this->cscd04_ordencompra_encabezado->findAll($condicion." and ano_orden_compra=".$ano_orden_compra_a."  and (numero_orden_compra>='$numero_orden_compra_a' and numero_orden_compra<='$numero_orden_compra_b')   and  tipo_orden=1   and condicion_actividad=1 ", null , 'numero_orden_compra ASC');
	$ordencompra_partidas   = $this->cscd04_ordencompra_partidas->findAll(  $condicion." and ano_orden_compra=".$ano_orden_compra_a."  and (numero_orden_compra>='$numero_orden_compra_a'  and numero_orden_compra<='$numero_orden_compra_b')  ");

$sql_para_por_emitir = " and ano=".$ano_orden_compra_a."  and (numero>='$numero_orden_compra_a' and numero<='$numero_orden_compra_b')";


$i=0;
$sql = "";
$sql_rif = "";

foreach($ordencompra_encabezado as $aux_cscd04_ordencompra_encabezado){ $i++;
  $ano_orden_compra[$i]     =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_orden_compra'];
  $numero_orden_compra[$i]  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_orden_compra'];
  $tipo_orden[$i]           =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['tipo_orden'];
  $rif[$i]                  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['rif'];
  $ano_cotizacion[$i]       =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_cotizacion'];
  $numero_cotizacion[$i]    =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_cotizacion'];


if($sql==""){$sql   .= "    ano_cotizacion='".$ano_cotizacion[$i]."' and  numero_cotizacion='".$numero_cotizacion[$i]."'  ";
       }else{$sql   .= " or  (ano_cotizacion='".$ano_cotizacion[$i]."' and  numero_cotizacion='".$numero_cotizacion[$i]."')  ";}


if($sql_rif==""){$sql_rif .= "rif='".$rif[$i]."'  ";}else{$sql_rif .= " or rif='".$rif[$i]."'  ";}


  $resultado=$this->cscd04_ordencompra_numero->execute("UPDATE cscd04_ordencompra_numero SET situacion = '3'  WHERE ".$condicion." and ano_orden_compra='".$ano_orden_compra[$i]."' and  numero_orden_compra='".$numero_orden_compra[$i]."' and situacion='2'  ");

}//fin


if($sql_rif==""){


	echo'<script>history.back(1);</script>';

}else{



//echo $sql.'<br>';

$datos_cscd03_cotizacion_encabezado     =   $this->cscd03_cotizacion_encabezado->findAll($condicion." and (".$sql_rif.") and (".$sql.")");
$datos_cscd03_cotizacion_cuerpo         =   $this->cscd03_cotizacion_cuerpo->findAll($condicion." and (".$sql_rif.") and (".$sql.")", null , 'codigo_prod_serv DESC' );
$lista                                  =   $this->v_cscd03_cotizacion->findAll($condicion." and (".$sql_rif.") and (".$sql.")", null , 'codigo_prod_serv DESC' );
$datos_cpcd02                           =   $this->cpcd02->findAll($sql_rif);





$i=0;
foreach($ordencompra_encabezado as $aux_cscd04_ordencompra_encabezado){$i++;
  $ano_orden_compra[$i]     =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_orden_compra'];
  $numero_orden_compra[$i]  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_orden_compra'];
  $tipo_orden[$i]           =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['tipo_orden'];
  $rif[$i]                  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['rif'];
  $ano_cotizacion[$i]       =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_cotizacion'];
  $numero_cotizacion[$i]    =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_cotizacion'];
  $monto_orden[$i]          =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['monto_orden'];

foreach($datos_cpcd02 as $aux_cpcd02){
     if($rif[$i] == $aux_cpcd02['cpcd02']['rif']){
     	if($this->cugd06_oficios_poremitir_comun->findCount($sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=1  and ano='".$ano_orden_compra[$i]."'  and numero='".$numero_orden_compra[$i]."' ")==0){
         $sql_por_emiter  = "INSERT INTO cugd06_oficios_poremitir_comun (cod_presi, cod_entidad , cod_tipo_inst, cod_inst, cod_dep, username, documento, ano, numero, beneficiario, monto)";
         $sql_por_emiter .= " VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$this->Session->read('nom_usuario')."', '1', '".$ano_orden_compra[$i]."', '".$numero_orden_compra[$i]."', '".$aux_cpcd02['cpcd02']['denominacion']."', '".$monto_orden[$i]."')";
         $R3=$this->cugd06_oficios_poremitir_comun->execute($sql_por_emiter);
     }//fin if
     break;
    }//fin
  }//fin foreach
}//fin



//echo'<pre>';
//print_r($lista);
//echo'</pre>';


//print_r($datos_cpcd02);

$sql_2 = "";
$j=0;

foreach($datos_cscd03_cotizacion_encabezado as $aux_cscd03_cotizacion_encabezado){$j++;
  $ano_cotizacion[$j]          =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['ano_cotizacion'];
  $numero_cotizacion[$j]       =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['numero_cotizacion'];
  $ano_solicitud[$j]           =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['ano_solicitud'];
  $numero_solicitud[$j]        =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['numero_solicitud'];

 if($sql_2==""){ $sql_2 .= "   ano_solicitud='".$ano_solicitud[$j]."' and numero_solicitud='".$numero_solicitud[$j]."'  ";
          }else{ $sql_2 .= "  or (ano_solicitud='".$ano_solicitud[$j]."' and  numero_solicitud='".$numero_solicitud[$j]."')  ";}//fin else

}//fin


$datos_cscd02_solicitud_encabezado     =   $this->cscd02_solicitud_encabezado->findAll($condicion." and (".$sql_2.")");

$sql_cugd02_direccion = "";
$x = 0;

foreach($datos_cscd02_solicitud_encabezado as $aux_cscd02_solicitud_encabezado){$x++;

  $cod_dir_superior[$x]     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_dir_superior'];
  $cod_coordinacion[$x]     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_coordinacion'];
  $cod_secretaria[$x]       =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_secretaria'];
  $cod_direccion[$x]        =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_direccion'];
  $cod_division[$x]         =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_division'];
  $cod_departamento[$x]     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_departamento'];
  $cod_oficina[$x]          =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_oficina'];


if($sql_cugd02_direccion==""){
$sql_cugd02_direccion  .= "  cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia = ".$cod_dep." and cod_dir_superior = ".$cod_dir_superior[$x]." ";
$sql_cugd02_direccion  .= " and  cod_coordinacion=".$cod_coordinacion[$x]." and  cod_secretaria=".$cod_secretaria[$x]." and cod_direccion=".$cod_direccion[$x]." ";

}else{

$sql_cugd02_direccion  .= " or (cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia = ".$cod_dep." and cod_dir_superior = ".$cod_dir_superior[$x]." ";
$sql_cugd02_direccion  .= " and  cod_coordinacion=".$cod_coordinacion[$x]." and  cod_secretaria=".$cod_secretaria[$x]." and cod_direccion=".$cod_direccion[$x].") ";


}//fin else

}//fin


//echo $sql_cugd02_direccion;

$datos_cugd02_direccion      =   $this->cugd02_direccion->findAll($sql_cugd02_direccion);

}//fin para el script




}else if($radio=="1"){




$datos_cscd04_ordencompra_poremitir_bienes      =   $this->cscd04_ordencompra_poremitir_bienes->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' " );

$ii=0;
$sql_1 = "";

foreach($datos_cscd04_ordencompra_poremitir_bienes as $aux_cscd04_ordencompra_poremitir_bienes){ $ii++;

  $ano_orden_compra_1[$ii]     =   $aux_cscd04_ordencompra_poremitir_bienes['cscd04_ordencompra_poremitir_bienes']['ano_orden_compra'];
  $numero_orden_compra_1[$ii]  =   $aux_cscd04_ordencompra_poremitir_bienes['cscd04_ordencompra_poremitir_bienes']['numero_orden_compra'];


if($sql_1==""){$sql_1   .= "    ano_orden_compra='".$ano_orden_compra_1[$ii]."' and  numero_orden_compra='".$numero_orden_compra_1[$ii]."'  ";
         }else{$sql_1   .= " or  (ano_orden_compra='".$ano_orden_compra_1[$ii]."' and  numero_orden_compra='".$numero_orden_compra_1[$ii]."')  ";  }

}//fin or



if($sql_1==""){

	echo'<script>history.back(1);</script>';

}else{

//echo $sql_1;

    $ordencompra_encabezado = $this->cscd04_ordencompra_encabezado->findAll($condicion." and (".$sql_1.") and  tipo_orden=1 and username_registro='".$this->Session->read('nom_usuario')."' and condicion_actividad=1 ", null , 'numero_orden_compra ASC');
	$ordencompra_partidas=$this->cscd04_ordencompra_partidas->findAll($condicion." and (".$sql_1.")");

///print_r($ordencompra_encabezado);


$i=0;
$sql = "";
$sql_rif = "";

foreach($ordencompra_encabezado as $aux_cscd04_ordencompra_encabezado){ $i++;
  $ano_orden_compra[$i]     =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_orden_compra'];
  $numero_orden_compra[$i]  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_orden_compra'];
  $tipo_orden[$i]           =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['tipo_orden'];
  $rif[$i]                  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['rif'];
  $ano_cotizacion[$i]       =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_cotizacion'];
  $numero_cotizacion[$i]    =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_cotizacion'];


if($sql==""){$sql   .= "    ano_cotizacion='".$ano_cotizacion[$i]."' and  numero_cotizacion='".$numero_cotizacion[$i]."'  ";     $sql_para_por_emitir .= "   and     ano='".$ano_orden_compra[$i]."' and  numero='".$numero_orden_compra[$i]."'  ";
       }else{$sql   .= " or  (ano_cotizacion='".$ano_cotizacion[$i]."' and  numero_cotizacion='".$numero_cotizacion[$i]."')  ";  $sql_para_por_emitir .= " or  (ano='".$ano_orden_compra[$i]."' and  numero='".$numero_orden_compra[$i]."')  ";     }


  if($sql_rif==""){$sql_rif .= "rif='".$rif[$i]."'  ";}else{$sql_rif .= " or rif='".$rif[$i]."'  ";}

$resultado=$this->cscd04_ordencompra_numero->execute("UPDATE cscd04_ordencompra_numero  SET situacion = '3'  WHERE ".$condicion." and ano_orden_compra='".$ano_orden_compra[$i]."' and  numero_orden_compra='".$numero_orden_compra[$i]."' and situacion='2'  ");

}//fin


if($sql==""){

	echo'<script>history.back(1);</script>';

}else{


//echo $sql.'<br>';

$datos_cscd03_cotizacion_encabezado     =   $this->cscd03_cotizacion_encabezado->findAll($condicion." and (".$sql_rif.") and (".$sql.")");
$datos_cscd03_cotizacion_cuerpo         =   $this->cscd03_cotizacion_cuerpo->findAll($condicion." and (".$sql_rif.") and (".$sql.")", null , 'codigo_prod_serv DESC' );
$lista                                  =   $this->v_cscd03_cotizacion->findAll($condicion." and (".$sql_rif.") and (".$sql.")", null , 'codigo_prod_serv DESC' );
$datos_cpcd02                           =   $this->cpcd02->findAll($sql_rif);


$i = 0;

foreach($ordencompra_encabezado as $aux_cscd04_ordencompra_encabezado){$i++;
  $ano_orden_compra[$i]     =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_orden_compra'];
  $numero_orden_compra[$i]  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_orden_compra'];
  $tipo_orden[$i]           =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['tipo_orden'];
  $rif[$i]                  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['rif'];
  $ano_cotizacion[$i]       =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_cotizacion'];
  $numero_cotizacion[$i]    =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_cotizacion'];
  $monto_orden[$i]          =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['monto_orden'];

foreach($datos_cpcd02 as $aux_cpcd02){
     if($rif[$i] == $aux_cpcd02['cpcd02']['rif']){
     	if($this->cugd06_oficios_poremitir_comun->findCount($sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=1  and ano='".$ano_orden_compra[$i]."'  and numero='".$numero_orden_compra[$i]."' ")==0){
         $sql_por_emiter  = "INSERT INTO cugd06_oficios_poremitir_comun (cod_presi, cod_entidad , cod_tipo_inst, cod_inst, cod_dep, username, documento, ano, numero, beneficiario, monto)";
         $sql_por_emiter .= " VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$this->Session->read('nom_usuario')."', '1', '".$ano_orden_compra[$i]."', '".$numero_orden_compra[$i]."', '".$aux_cpcd02['cpcd02']['denominacion']."', '".$monto_orden[$i]."')";
         $R3=$this->cugd06_oficios_poremitir_comun->execute($sql_por_emiter);
     }//fin if
      break;
    }//fin
  }//fin foreach
}//fin


//echo'<pre>';
//print_r($lista);
//echo'</pre>';


//print_r($datos_cpcd02);

$sql_2 = "";
$j=0;

foreach($datos_cscd03_cotizacion_encabezado as $aux_cscd03_cotizacion_encabezado){$j++;
  $ano_cotizacion[$j]          =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['ano_cotizacion'];
  $numero_cotizacion[$j]       =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['numero_cotizacion'];
  $ano_solicitud[$j]           =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['ano_solicitud'];
  $numero_solicitud[$j]        =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['numero_solicitud'];

 if($sql_2==""){ $sql_2 .= "   ano_solicitud='".$ano_solicitud[$j]."' and numero_solicitud='".$numero_solicitud[$j]."'  ";
          }else{ $sql_2 .= "  or (ano_solicitud='".$ano_solicitud[$j]."' and  numero_solicitud='".$numero_solicitud[$j]."')  ";}//fin else

}//fin


$datos_cscd02_solicitud_encabezado     =   $this->cscd02_solicitud_encabezado->findAll($condicion." and (".$sql_2.")");

$sql_cugd02_direccion = "";
$x = 0;

foreach($datos_cscd02_solicitud_encabezado as $aux_cscd02_solicitud_encabezado){$x++;

  $cod_dir_superior[$x]     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_dir_superior'];
  $cod_coordinacion[$x]     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_coordinacion'];
  $cod_secretaria[$x]       =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_secretaria'];
  $cod_direccion[$x]        =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_direccion'];
  $cod_division[$x]         =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_division'];
  $cod_departamento[$x]     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_departamento'];
  $cod_oficina[$x]          =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_oficina'];


if($sql_cugd02_direccion==""){
$sql_cugd02_direccion  .= " cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia = ".$cod_dep." and cod_dir_superior = ".$cod_dir_superior[$x]." ";
$sql_cugd02_direccion  .= " and  cod_coordinacion=".$cod_coordinacion[$x]." and  cod_secretaria=".$cod_secretaria[$x]." and cod_direccion=".$cod_direccion[$x]." ";

}else{

$sql_cugd02_direccion  .= " or (cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia = ".$cod_dep." and cod_dir_superior = ".$cod_dir_superior[$x]." ";
$sql_cugd02_direccion  .= " and  cod_coordinacion=".$cod_coordinacion[$x]." and  cod_secretaria=".$cod_secretaria[$x]." and cod_direccion=".$cod_direccion[$x].") ";


}//fin else


}//fin



$datos_cugd02_direccion      =   $this->cugd02_direccion->findAll($sql_cugd02_direccion);


$sql = "delete from cscd04_ordencompra_poremitir_bienes  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
$this->cscd04_ordencompra_poremitir_bienes->execute($sql);

}//fin else


}//fin else


}//fin else





if($ordencompra_encabezado!=""){
    $this->set('lista_cscd02_solicitud_cuerpo', $lista);
    $this->set('datos_cscd03_cotizacion_encabezado', $datos_cscd03_cotizacion_encabezado);
    $this->set('datos_cpcd02', $datos_cpcd02);
    $this->set('datos_cscd02_solicitud_encabezado', $datos_cscd02_solicitud_encabezado);
    $this->set('datos_cscd03_cotizacion_cuerpo', $datos_cscd03_cotizacion_cuerpo);
    $this->set('datos_cugd02_direccion', $datos_cugd02_direccion);
    $this->set('ordencompra_partidas', $ordencompra_partidas);
	$this->set('ordencompra_encabezado', $ordencompra_encabezado);
}//fin if



$sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=1  ".$sql_para_por_emitir;
$this->set('cugd06_oficios_poremitir_comun', $this->cugd06_oficios_poremitir_comun->findAll($sql, null, 'numero ASC'));


 if($datos_cugd02_direccion!=""){$this->layout = "pdf"; }else{$this->layout = "ajax"; $this->set('errorMessage', "No existe alguna de la orden selecionada"); }


}//fin else









$this->set('titulo_a', $this->Session->read('dependencia'));
$this->set('entidad_federal', $this->Session->read('entidad_federal'));


}//fin opcion vista



}//fin function




//////////////////////////////////////////////////////////////////////////////////////////////////////









function cscp04_ordencompra_servicio($year=null, $opcion=null, $cont=null){

   $this->layout = "ajax";

    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $sql_para_por_emitir = "";

$this->Session->delete('FIN');

if($year == "vista"){

 $ano = $this->ano_ejecucion();


$this->set('year', $ano);
$this->set('vista', $opcion);


$url                  =  "/caop00_select_ventana_generar_reporte/v_reporte_emision_compra_servicio_1";
$width_aux            =  "750px";
$height_aux           =  "400px";
$title_aux            =  "Buscar";
$resizable_aux        =  false;
$maximizable_aux      =  false;
$minimizable_aux      =  false;
$closable_aux         =  false;

	  if($opcion==3){
	         echo"<script>";
	           echo  "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
	         echo"</script>";
    	}else{
              echo"<script>";
               echo  " Windows.close(document.getElementById('capa_ventana').value)";
              echo"</script>";

	}//fin else


}else{




if(!$year){


    $this->limpia_menu();
    $this->set('ir', 'no');



    $ano = $this->ano_ejecucion();
    $dato = $this->ano_ejecucion();

	if(!empty($dato)){

		$this->set('year', $dato);

	}else{
		$this->set('year', '');
	}



$sql = "delete from cugd06_oficios_poremitir_comun  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=2  ";
$this->cugd06_oficios_poremitir_comun->execute($sql);



$datos_cscd04_ordencompra_poremitir_servicio = $this->cscd04_ordencompra_poremitir_servicio->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' ");
$this->set('datos_cscd04_ordencompra_poremitir_servicio', $datos_cscd04_ordencompra_poremitir_servicio);
$this->set('usuario', $this->Session->read('nom_usuario'));


}else{


  	$this->layout = "pdf";
  	$radio = 1;
  	$ordencompra_encabezado = "";
  	$datos_cugd02_direccion = "";
  	$numero_orden_compra_a  = "";
  	$numero_orden_compra_b  = "";


         if(isset($this->data['cscp04_ordencompra_servicio']['radio'])){$radio = $this->data['cscp04_ordencompra_servicio']['radio'];}
  		 if(isset($this->data['cscp04_ordencompra_servicio']['ano'.$cont.''])){$ano_orden_compra_a = $this->data['cscp04_ordencompra_servicio']['ano'.$cont.''];}
  		 if(isset($this->data['cscp04_ordencompra_servicio']['numero_a'.$cont.''])){$numero_orden_compra_a = $this->data['cscp04_ordencompra_servicio']['numero_a'.$cont.''];}
  		 if(isset($this->data['cscp04_ordencompra_servicio']['numero_b'])){$numero_orden_compra_b = $this->data['cscp04_ordencompra_servicio']['numero_b'];}

//montar direccion en el footer de la orden de pago - alcaldia san fernando
		$sql_direccion_pie_pagina = "SELECT direccion FROM cugd02_dependencias WHERE cod_tipo_institucion=$cod_tipo_inst AND cod_institucion=$cod_inst AND cod_dependencia=$cod_dep";
        $data_direccion_pie_pagina = $this->cepd03_ordenpago_cuerpo->execute($sql_direccion_pie_pagina);
        $this->set('direccion_pie_pagina',$data_direccion_pie_pagina[0][0]['direccion']);

        $sql_unidad_tributaria = "SELECT unidad_tributaria FROM cscd04_ordencompra_parametros WHERE ".$this->SQLCA();
        $data_unidad_tributaria = $this->cepd03_ordenpago_cuerpo->execute($sql_unidad_tributaria);
        if(count($data_unidad_tributaria)>0){
        	$this->set('unidad_tributaria_pie',$data_unidad_tributaria[0][0]['unidad_tributaria']);
        }else{
        	$this->set('unidad_tributaria_pie',0);
        }



if($radio=="3" && $numero_orden_compra_a!=""){


    $ordencompra_encabezado = $this->cscd04_ordencompra_encabezado->findAll($condicion." and ano_orden_compra=".$ano_orden_compra_a."  and numero_orden_compra='$numero_orden_compra_a'  and  tipo_orden=2   and  condicion_actividad=1 ", null , 'numero_orden_compra ASC');
	$ordencompra_partidas=$this->cscd04_ordencompra_partidas->findAll($condicion." and ano_orden_compra=".$ano_orden_compra_a."  and numero_orden_compra='$numero_orden_compra_a' ");


$sql_para_por_emitir = " and ano=".$ano_orden_compra_a."  and numero='$numero_orden_compra_a'";

$sql_rif_a = "";

foreach($ordencompra_encabezado as $aux_cscd04_ordencompra_encabezado){
  $ano_orden_compra     =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_orden_compra'];
  $numero_orden_compra  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_orden_compra'];
  $tipo_orden           =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['tipo_orden'];
  $rif                  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['rif'];
  $ano_cotizacion       =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_cotizacion'];
  $numero_cotizacion    =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_cotizacion'];

$resultado=$this->cscd04_ordencompra_numero->execute("UPDATE cscd04_ordencompra_numero  SET  situacion = '3'  WHERE ".$condicion." and ano_orden_compra='".$ano_orden_compra."' and  numero_orden_compra='".$numero_orden_compra."' and situacion='2'  ");

$sql_rif_a = "asd";

}//fin




if($sql_rif_a==""){

	echo'<script>history.back(1);</script>';

}else{


$datos_cscd03_cotizacion_encabezado     =   $this->cscd03_cotizacion_encabezado->findAll($condicion." and rif='".$rif."'  and ano_cotizacion='".$ano_cotizacion."' and numero_cotizacion='".$numero_cotizacion."'  ");
$datos_cscd03_cotizacion_cuerpo         =   $this->cscd03_cotizacion_cuerpo->findAll($condicion."     and rif='".$rif."'  and ano_cotizacion='".$ano_cotizacion."' and numero_cotizacion='".$numero_cotizacion."'  ", null , 'codigo_prod_serv DESC' );
$lista                                  =   $this->v_cscd03_cotizacion->findAll($condicion."          and rif='".$rif."'  and ano_cotizacion='".$ano_cotizacion."' and numero_cotizacion='".$numero_cotizacion."'  ", null , 'codigo_prod_serv DESC' );
$datos_cpcd02                           =   $this->cpcd02->findAll(" rif='".$rif."' ");



foreach($ordencompra_encabezado as $aux_cscd04_ordencompra_encabezado){
  $ano_orden_compra     =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_orden_compra'];
  $numero_orden_compra  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_orden_compra'];
  $tipo_orden           =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['tipo_orden'];
  $rif                  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['rif'];
  $ano_cotizacion       =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_cotizacion'];
  $numero_cotizacion    =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_cotizacion'];
  $monto_orden          =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['monto_orden'];



foreach($datos_cpcd02 as $aux_cpcd02){
     if($rif == $aux_cpcd02['cpcd02']['rif']){
     	if($this->cugd06_oficios_poremitir_comun->findCount($sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=2  and ano='".$ano_orden_compra."'  and numero='".$numero_orden_compra."' ")==0){
         $sql_por_emiter  = "INSERT INTO cugd06_oficios_poremitir_comun (cod_presi, cod_entidad , cod_tipo_inst, cod_inst, cod_dep, username, documento, ano, numero, beneficiario, monto)";
         $sql_por_emiter .= " VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$this->Session->read('nom_usuario')."', '2', '".$ano_orden_compra."', '".$numero_orden_compra."', '".$aux_cpcd02['cpcd02']['denominacion']."', '".$monto_orden."')";
         $R3=$this->cugd06_oficios_poremitir_comun->execute($sql_por_emiter);
        }//fin
        break;
     }//fin if
  }//fin foreach
}//fin



foreach($datos_cscd03_cotizacion_encabezado as $aux_cscd03_cotizacion_encabezado){
  $ano_cotizacion          =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['ano_cotizacion'];
  $numero_cotizacion       =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['numero_cotizacion'];
  $ano_solicitud           =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['ano_solicitud'];
  $numero_solicitud        =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['numero_solicitud'];
}//fin



$datos_cscd02_solicitud_encabezado      =   $this->cscd02_solicitud_encabezado->findAll($condicion." and ano_solicitud='".$ano_solicitud."' and numero_solicitud='".$numero_solicitud."'  ");



foreach($datos_cscd02_solicitud_encabezado as $aux_cscd02_solicitud_encabezado){
  $cod_dir_superior     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_dir_superior'];
  $cod_coordinacion     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_coordinacion'];
  $cod_secretaria       =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_secretaria'];
  $cod_direccion        =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_direccion'];
  $cod_division         =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_division'];
  $cod_departamento     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_departamento'];
  $cod_oficina          =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_oficina'];
}//fin



$sql_cugd02_direccion  = " cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia = ".$cod_dep." and cod_dir_superior = ".$cod_dir_superior." ";
$sql_cugd02_direccion .= " and  cod_coordinacion=".$cod_coordinacion." and  cod_secretaria=".$cod_secretaria." and cod_direccion=".$cod_direccion." ";



$datos_cugd02_direccion      =   $this->cugd02_direccion->findAll($sql_cugd02_direccion);




}//fin script







}else if($radio=="2" && $numero_orden_compra_a!="" && $numero_orden_compra_b!=""){






    $ordencompra_encabezado = $this->cscd04_ordencompra_encabezado->findAll($condicion." and ano_orden_compra=".$ano_orden_compra_a."  and (numero_orden_compra>='$numero_orden_compra_a' and numero_orden_compra<='$numero_orden_compra_b')   and  tipo_orden=2  and condicion_actividad=1 ", null , 'numero_orden_compra ASC');
	$ordencompra_partidas   = $this->cscd04_ordencompra_partidas->findAll($condicion." and ano_orden_compra=".$ano_orden_compra_a."  and (numero_orden_compra>='$numero_orden_compra_a'  and numero_orden_compra<='$numero_orden_compra_b')  ");


$sql_para_por_emitir = " and ano=".$ano_orden_compra_a."  and (numero>='$numero_orden_compra_a' and numero<='$numero_orden_compra_b')";

$i=0;
$sql = "";
$sql_rif = "";
$sql_rif_a ="";

foreach($ordencompra_encabezado as $aux_cscd04_ordencompra_encabezado){ $i++;
  $ano_orden_compra[$i]     =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_orden_compra'];
  $numero_orden_compra[$i]  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_orden_compra'];
  $tipo_orden[$i]           =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['tipo_orden'];
  $rif[$i]                  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['rif'];
  $ano_cotizacion[$i]       =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_cotizacion'];
  $numero_cotizacion[$i]    =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_cotizacion'];


if($sql==""){$sql   .= "    ano_cotizacion='".$ano_cotizacion[$i]."' and  numero_cotizacion='".$numero_cotizacion[$i]."'  ";
       }else{$sql   .= " or  (ano_cotizacion='".$ano_cotizacion[$i]."' and  numero_cotizacion='".$numero_cotizacion[$i]."')  ";}


  if($sql_rif==""){$sql_rif .= "rif='".$rif[$i]."'  ";}else{$sql_rif .= " or (rif='".$rif[$i]."')  ";}


$resultado=$this->cscd04_ordencompra_numero->execute("UPDATE cscd04_ordencompra_numero SET  situacion = '3'  WHERE ".$condicion." and ano_orden_compra='".$ano_orden_compra[$i]."' and  numero_orden_compra='".$numero_orden_compra[$i]."' and situacion='2'  ");

$sql_rif_a = "asdasd";

}//fin


if($sql_rif_a==""){


	echo'<script>history.back(1);</script>';

}else{



$datos_cscd03_cotizacion_encabezado     =   $this->cscd03_cotizacion_encabezado->findAll($condicion." and (".$sql_rif.") and (".$sql.")");
$datos_cscd03_cotizacion_cuerpo         =   $this->cscd03_cotizacion_cuerpo->findAll($condicion." and (".$sql_rif.") and (".$sql.")", null , 'codigo_prod_serv DESC' );
$lista                                  =   $this->v_cscd03_cotizacion->findAll($condicion." and (".$sql_rif.") and (".$sql.")", null, 'codigo_prod_serv DESC' );
$datos_cpcd02                           =   $this->cpcd02->findAll($sql_rif);





$i = 0;

foreach($ordencompra_encabezado as $aux_cscd04_ordencompra_encabezado){$i++;
  $ano_orden_compra[$i]     =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_orden_compra'];
  $numero_orden_compra[$i]  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_orden_compra'];
  $tipo_orden[$i]           =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['tipo_orden'];
  $rif[$i]                  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['rif'];
  $ano_cotizacion[$i]       =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_cotizacion'];
  $numero_cotizacion[$i]    =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_cotizacion'];
  $monto_orden[$i]          =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['monto_orden'];

foreach($datos_cpcd02 as $aux_cpcd02){
     if($rif[$i] == $aux_cpcd02['cpcd02']['rif']){
     	if($this->cugd06_oficios_poremitir_comun->findCount($sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=2  and ano='".$ano_orden_compra[$i]."'  and numero='".$numero_orden_compra[$i]."' ")==0){
         $sql_por_emiter  = "INSERT INTO cugd06_oficios_poremitir_comun (cod_presi, cod_entidad , cod_tipo_inst, cod_inst, cod_dep, username, documento, ano, numero, beneficiario, monto)";
         $sql_por_emiter .= " VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$this->Session->read('nom_usuario')."', '2', '".$ano_orden_compra[$i]."', '".$numero_orden_compra[$i]."', '".$aux_cpcd02['cpcd02']['denominacion']."', '".$monto_orden[$i]."')";
         $R3=$this->cugd06_oficios_poremitir_comun->execute($sql_por_emiter);
          }//fin if
        break;
      }//fin
  }//fin foreach
}//fin


//echo'<pre>';
//print_r($lista);
//echo'</pre>';


//print_r($datos_cpcd02);

$sql_2 = "";
$j=0;

foreach($datos_cscd03_cotizacion_encabezado as $aux_cscd03_cotizacion_encabezado){$j++;
  $ano_cotizacion[$j]          =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['ano_cotizacion'];
  $numero_cotizacion[$j]       =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['numero_cotizacion'];
  $ano_solicitud[$j]           =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['ano_solicitud'];
  $numero_solicitud[$j]        =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['numero_solicitud'];

 if($sql_2==""){ $sql_2 .= "   ano_solicitud='".$ano_solicitud[$j]."' and numero_solicitud='".$numero_solicitud[$j]."'  ";
          }else{ $sql_2 .= "  or (ano_solicitud='".$ano_solicitud[$j]."' and  numero_solicitud='".$numero_solicitud[$j]."')  ";}//fin else

}//fin


$datos_cscd02_solicitud_encabezado     =   $this->cscd02_solicitud_encabezado->findAll($condicion." and (".$sql_2.")");

$sql_cugd02_direccion = "";
$x = 0;

foreach($datos_cscd02_solicitud_encabezado as $aux_cscd02_solicitud_encabezado){$x++;

  $cod_dir_superior[$x]     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_dir_superior'];
  $cod_coordinacion[$x]     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_coordinacion'];
  $cod_secretaria[$x]       =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_secretaria'];
  $cod_direccion[$x]        =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_direccion'];
  $cod_division[$x]         =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_division'];
  $cod_departamento[$x]     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_departamento'];
  $cod_oficina[$x]          =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_oficina'];


if($sql_cugd02_direccion==""){
$sql_cugd02_direccion  .= " cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia = ".$cod_dep." and cod_dir_superior = ".$cod_dir_superior[$x]." ";
$sql_cugd02_direccion  .= " and  cod_coordinacion=".$cod_coordinacion[$x]." and  cod_secretaria=".$cod_secretaria[$x]." and cod_direccion=".$cod_direccion[$x]." ";

}else{

$sql_cugd02_direccion  .= " or (cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia = ".$cod_dep." and cod_dir_superior = ".$cod_dir_superior[$x]." ";
$sql_cugd02_direccion  .= " and  cod_coordinacion=".$cod_coordinacion[$x]." and  cod_secretaria=".$cod_secretaria[$x]." and cod_direccion=".$cod_direccion[$x].") ";


}//fin else


}//fin



$datos_cugd02_direccion      =   $this->cugd02_direccion->findAll($sql_cugd02_direccion);


}//fin script

}else if($radio=="1"){




$datos_cscd04_ordencompra_poremitir_servicio      =   $this->cscd04_ordencompra_poremitir_servicio->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' " );

$ii=0;
$sql_1 = "";

foreach($datos_cscd04_ordencompra_poremitir_servicio as $aux_cscd04_ordencompra_poremitir_servicio){ $ii++;

  $ano_orden_compra_1[$ii]     =   $aux_cscd04_ordencompra_poremitir_servicio['cscd04_ordencompra_poremitir_servicio']['ano_orden_compra'];
  $numero_orden_compra_1[$ii]  =   $aux_cscd04_ordencompra_poremitir_servicio['cscd04_ordencompra_poremitir_servicio']['numero_orden_compra'];



if($sql_1==""){$sql_1   .= "    ano_orden_compra='".$ano_orden_compra_1[$ii]."' and  numero_orden_compra='".$numero_orden_compra_1[$ii]."'  ";
         }else{$sql_1   .= " or  (ano_orden_compra='".$ano_orden_compra_1[$ii]."' and  numero_orden_compra='".$numero_orden_compra_1[$ii]."')  ";  }


}//fin or


//echo $sql_1;


if($sql_1==""){


	echo'<script>history.back(1);</script>';

}else{

    $ordencompra_encabezado = $this->cscd04_ordencompra_encabezado->findAll($condicion." and (".$sql_1.") and  tipo_orden=2 and username_registro='".$this->Session->read('nom_usuario')."' and condicion_actividad=1   ", null , 'numero_orden_compra ASC');
	$ordencompra_partidas=$this->cscd04_ordencompra_partidas->findAll($condicion." and (".$sql_1.")");

///print_r($ordencompra_encabezado);


$i=0;
$sql = "";
$sql_rif = "";

foreach($ordencompra_encabezado as $aux_cscd04_ordencompra_encabezado){ $i++;
  $ano_orden_compra[$i]     =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_orden_compra'];
  $numero_orden_compra[$i]  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_orden_compra'];
  $tipo_orden[$i]           =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['tipo_orden'];
  $rif[$i]                  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['rif'];
  $ano_cotizacion[$i]       =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_cotizacion'];
  $numero_cotizacion[$i]    =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_cotizacion'];


if($sql==""){$sql   .= "    ano_cotizacion='".$ano_cotizacion[$i]."' and  numero_cotizacion='".$numero_cotizacion[$i]."'   ";   $sql_para_por_emitir .= "   and     ano='".$ano_orden_compra[$i]."' and  numero='".$numero_orden_compra[$i]."'  ";
       }else{$sql   .= " or  (ano_cotizacion='".$ano_cotizacion[$i]."' and  numero_cotizacion='".$numero_cotizacion[$i]."')  "; $sql_para_por_emitir .= " or  (ano='".$ano_orden_compra[$i]."' and  numero='".$numero_orden_compra[$i]."')  ";  }


  if($sql_rif==""){$sql_rif .= "rif='".$rif[$i]."'  ";}else{$sql_rif .= " or rif='".$rif[$i]."'  ";}


$resultado=$this->cscd04_ordencompra_numero->execute("UPDATE cscd04_ordencompra_numero SET  situacion = '3' WHERE ".$condicion." and ano_orden_compra='".$ano_orden_compra[$i]."' and  numero_orden_compra='".$numero_orden_compra[$i]."' and situacion='2'  ");

}//fin


//echo $sql.'<br>';

$datos_cscd03_cotizacion_encabezado     =   $this->cscd03_cotizacion_encabezado->findAll($condicion." and (".$sql_rif.") and (".$sql.")");
$datos_cscd03_cotizacion_cuerpo         =   $this->cscd03_cotizacion_cuerpo->findAll($condicion." and (".$sql_rif.") and (".$sql.")", null , 'codigo_prod_serv DESC' );
$lista                                  =   $this->v_cscd03_cotizacion->findAll($condicion." and (".$sql_rif.") and (".$sql.")", null , 'codigo_prod_serv DESC' );
$datos_cpcd02                           =   $this->cpcd02->findAll($sql_rif);




$i = 0;

foreach($ordencompra_encabezado as $aux_cscd04_ordencompra_encabezado){$i++;
  $ano_orden_compra[$i]     =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_orden_compra'];
  $numero_orden_compra[$i]  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_orden_compra'];
  $tipo_orden[$i]           =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['tipo_orden'];
  $rif[$i]                  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['rif'];
  $ano_cotizacion[$i]       =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_cotizacion'];
  $numero_cotizacion[$i]    =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_cotizacion'];
  $monto_orden[$i]          =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['monto_orden'];

foreach($datos_cpcd02 as $aux_cpcd02){
     if($rif[$i] == $aux_cpcd02['cpcd02']['rif']){
     	if($this->cugd06_oficios_poremitir_comun->findCount($sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=2  and ano='".$ano_orden_compra[$i]."'  and numero='".$numero_orden_compra[$i]."' ")==0){
         $sql_por_emiter  = "INSERT INTO cugd06_oficios_poremitir_comun (cod_presi, cod_entidad , cod_tipo_inst, cod_inst, cod_dep, username, documento, ano, numero, beneficiario, monto)";
         $sql_por_emiter .= " VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$this->Session->read('nom_usuario')."', '2', '".$ano_orden_compra[$i]."', '".$numero_orden_compra[$i]."', '".$aux_cpcd02['cpcd02']['denominacion']."', '".$monto_orden[$i]."')";
         $R3=$this->cugd06_oficios_poremitir_comun->execute($sql_por_emiter);
          }//fin
       break;
    }//fin
  }//fin foreach
}//fin




//echo'<pre>';
//print_r($lista);
//echo'</pre>';
//print_r($datos_cpcd02);


$sql_2 = "";
$j=0;

foreach($datos_cscd03_cotizacion_encabezado as $aux_cscd03_cotizacion_encabezado){$j++;
  $ano_cotizacion[$j]          =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['ano_cotizacion'];
  $numero_cotizacion[$j]       =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['numero_cotizacion'];
  $ano_solicitud[$j]           =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['ano_solicitud'];
  $numero_solicitud[$j]        =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['numero_solicitud'];

 if($sql_2==""){ $sql_2 .= "   ano_solicitud='".$ano_solicitud[$j]."' and numero_solicitud='".$numero_solicitud[$j]."'  ";
          }else{ $sql_2 .= "  or (ano_solicitud='".$ano_solicitud[$j]."' and  numero_solicitud='".$numero_solicitud[$j]."')  ";}//fin else

}//fin


$datos_cscd02_solicitud_encabezado     =   $this->cscd02_solicitud_encabezado->findAll($condicion." and (".$sql_2.")");

$sql_cugd02_direccion = "";
$x = 0;

foreach($datos_cscd02_solicitud_encabezado as $aux_cscd02_solicitud_encabezado){$x++;

  $cod_dir_superior[$x]     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_dir_superior'];
  $cod_coordinacion[$x]     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_coordinacion'];
  $cod_secretaria[$x]       =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_secretaria'];
  $cod_direccion[$x]        =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_direccion'];
  $cod_division[$x]         =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_division'];
  $cod_departamento[$x]     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_departamento'];
  $cod_oficina[$x]          =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_oficina'];


if($sql_cugd02_direccion==""){
$sql_cugd02_direccion  .= " cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia = ".$cod_dep." and cod_dir_superior = ".$cod_dir_superior[$x]." ";
$sql_cugd02_direccion  .= " and  cod_coordinacion=".$cod_coordinacion[$x]." and  cod_secretaria=".$cod_secretaria[$x]." and cod_direccion=".$cod_direccion[$x]." ";

}else{

$sql_cugd02_direccion  .= " or (cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia = ".$cod_dep." and cod_dir_superior = ".$cod_dir_superior[$x]." ";
$sql_cugd02_direccion  .= " and  cod_coordinacion=".$cod_coordinacion[$x]." and  cod_secretaria=".$cod_secretaria[$x]." and cod_direccion=".$cod_direccion[$x].") ";


}//fin else


}//fin



$datos_cugd02_direccion      =   $this->cugd02_direccion->findAll($sql_cugd02_direccion);


$sql = "delete from cscd04_ordencompra_poremitir_servicio  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
$this->cscd04_ordencompra_poremitir_servicio->execute($sql);

}//fin else


}//fin else


if($ordencompra_encabezado!=""){
    $this->set('lista_cscd02_solicitud_cuerpo', $lista);
    $this->set('datos_cscd03_cotizacion_encabezado', $datos_cscd03_cotizacion_encabezado);
    $this->set('datos_cpcd02', $datos_cpcd02);
    $this->set('datos_cscd02_solicitud_encabezado', $datos_cscd02_solicitud_encabezado);
    $this->set('datos_cscd03_cotizacion_cuerpo', $datos_cscd03_cotizacion_cuerpo);
    $this->set('datos_cugd02_direccion', $datos_cugd02_direccion);
    $this->set('ordencompra_partidas', $ordencompra_partidas);
	$this->set('ordencompra_encabezado', $ordencompra_encabezado);
}//fin if


 if($datos_cugd02_direccion!=""){$this->layout = "pdf"; }else{$this->layout = "ajax"; $this->set('errorMessage', "No existe alguna de la orden selecionada"); }


$sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=2  ".$sql_para_por_emitir;
$this->set('cugd06_oficios_poremitir_comun', $this->cugd06_oficios_poremitir_comun->findAll($sql, null, 'numero ASC'));


}//fin else





$this->set('titulo_a', $this->Session->read('dependencia'));
$this->set('entidad_federal', $this->Session->read('entidad_federal'));


}//fin opcion vista



}//fin function






//////////////////////////////////////////////////////////////////////////////////////////////////////////







/*
 * Reporte de orden de compra formato actual, es decir, pre impreso.
 */
function cscp04_ordencompra_bienes_formato_actual($year=null, $opcion=null, $cont=null){

   $this->layout = "ajax";

    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $sql_para_por_emitir = "";


$this->Session->delete('FIN');

if($year == "vista"){

 $ano = $this->ano_ejecucion();


$this->set('year', $ano);
$this->set('vista', $opcion);

$url                  =  "/caop00_select_ventana_generar_reporte/v_reporte_emision_compra_bienes_1_actual";
$width_aux            =  "750px";
$height_aux           =  "400px";
$title_aux            =  "Buscar";
$resizable_aux        =  false;
$maximizable_aux      =  false;
$minimizable_aux      =  false;
$closable_aux         =  false;

	  if($opcion==3){
	         echo"<script>";
	           echo  "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
	         echo"</script>";
    	}else{
              echo"<script>";
               echo  " Windows.close(document.getElementById('capa_ventana').value)";
              echo"</script>";

	}//fin else




}else{

if(!$year){


    $this->limpia_menu();
    $this->set('ir', 'no');



    $ano = $this->ano_ejecucion();
    $dato = $this->ano_ejecucion();

	if(!empty($dato)){

		$this->set('year', $dato);

	}else{
		$this->set('year', '');
	}




$sql = "delete from cugd06_oficios_poremitir_comun  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=1  ";
$this->cugd06_oficios_poremitir_comun->execute($sql);


$datos_cscd04_ordencompra_poremitir_bienes = $this->cscd04_ordencompra_poremitir_bienes->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' ");

$this->set('datos_cscd04_ordencompra_poremitir_bienes', $datos_cscd04_ordencompra_poremitir_bienes);
$this->set('usuario', $this->Session->read('nom_usuario'));


}else{


  	$this->layout = "pdf";
  	$radio = 1;
  	$ordencompra_encabezado = "";
  	$datos_cugd02_direccion = "";
  	$numero_orden_compra_a  = "";
  	$numero_orden_compra_b  = "";


         if(isset($this->data['cscp04_ordencompra_bienes']['radio'])){            $radio                 = $this->data['cscp04_ordencompra_bienes']['radio'];}
  		 if(isset($this->data['cscp04_ordencompra_bienes']['ano'.$cont.''])){     $ano_orden_compra_a    = $this->data['cscp04_ordencompra_bienes']['ano'.$cont.''];}
  		 if(isset($this->data['cscp04_ordencompra_bienes']['numero_a'.$cont.''])){$numero_orden_compra_a = $this->data['cscp04_ordencompra_bienes']['numero_a'.$cont.''];}
  		 if(isset($this->data['cscp04_ordencompra_bienes']['numero_b'])){         $numero_orden_compra_b = $this->data['cscp04_ordencompra_bienes']['numero_b'];}


if($radio=="3" && $numero_orden_compra_a!=""){


    $ordencompra_encabezado = $this->cscd04_ordencompra_encabezado->findAll($condicion." and ano_orden_compra=".$ano_orden_compra_a."  and numero_orden_compra='$numero_orden_compra_a'  and  tipo_orden=1 and condicion_actividad=1", null , 'numero_orden_compra ASC');
	$ordencompra_partidas   = $this->cscd04_ordencompra_partidas->findAll($condicion."       and ano_orden_compra=".$ano_orden_compra_a."  and numero_orden_compra='$numero_orden_compra_a' ");

$sql_para_por_emitir = " and ano=".$ano_orden_compra_a."  and numero='$numero_orden_compra_a'";

$sql_rif_a = "";

foreach($ordencompra_encabezado as $aux_cscd04_ordencompra_encabezado){
  $ano_orden_compra     =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_orden_compra'];
  $numero_orden_compra  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_orden_compra'];
  $tipo_orden           =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['tipo_orden'];
  $rif                  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['rif'];
  $ano_cotizacion       =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_cotizacion'];
  $numero_cotizacion    =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_cotizacion'];
  $monto_orden          =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['monto_orden'];



$resultado=$this->cscd04_ordencompra_numero->execute("UPDATE cscd04_ordencompra_numero  SET  situacion = '3'  WHERE ".$condicion." and ano_orden_compra='".$ano_orden_compra."' and  numero_orden_compra='".$numero_orden_compra."' and situacion='2'  ");


  $sql_rif_a = "asdasd";
}//fin


//echo $condicion." and rif='".$rif."' and ano_cotizacion='".$ano_cotizacion."' and numero_cotizacion='".$numero_cotizacion."'  ";


if($sql_rif_a==""){


	echo'<script>history.back(1);</script>';

}else{




$datos_cscd03_cotizacion_encabezado     =   $this->cscd03_cotizacion_encabezado->findAll($condicion." and rif='".$rif."' and ano_cotizacion='".$ano_cotizacion."' and numero_cotizacion='".$numero_cotizacion."'  ");
$datos_cscd03_cotizacion_cuerpo         =   $this->cscd03_cotizacion_cuerpo->findAll($condicion."     and rif='".$rif."' and ano_cotizacion='".$ano_cotizacion."' and numero_cotizacion='".$numero_cotizacion."'  ", null , 'codigo_prod_serv DESC' );
$lista                                  =   $this->v_cscd03_cotizacion->findAll($condicion."          and rif='".$rif."' and ano_cotizacion='".$ano_cotizacion."' and numero_cotizacion='".$numero_cotizacion."' and numero_ordencompra='$numero_orden_compra' and ano_ordencompra='$ano_orden_compra'",null , 'codigo_prod_serv DESC' );
$datos_cpcd02                           =   $this->cpcd02->findAll(" rif='".$rif."' ");







foreach($ordencompra_encabezado as $aux_cscd04_ordencompra_encabezado){
  $ano_orden_compra     =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_orden_compra'];
  $numero_orden_compra  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_orden_compra'];
  $tipo_orden           =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['tipo_orden'];
  $rif                  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['rif'];
  $ano_cotizacion       =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_cotizacion'];
  $numero_cotizacion    =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_cotizacion'];
  $monto_orden          =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['monto_orden'];



foreach($datos_cpcd02 as $aux_cpcd02){
     if($rif == $aux_cpcd02['cpcd02']['rif']){
     	if($this->cugd06_oficios_poremitir_comun->findCount($sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=1  and ano='".$ano_orden_compra."'  and numero='".$numero_orden_compra."' ")==0){
         $sql_por_emiter  = "INSERT INTO cugd06_oficios_poremitir_comun (cod_presi, cod_entidad , cod_tipo_inst, cod_inst, cod_dep, username, documento, ano, numero, beneficiario, monto)";
         $sql_por_emiter .= " VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$this->Session->read('nom_usuario')."', '1', '".$ano_orden_compra."', '".$numero_orden_compra."', '".$aux_cpcd02['cpcd02']['denominacion']."', '".$monto_orden."')";
         $R3=$this->cugd06_oficios_poremitir_comun->execute($sql_por_emiter);
     	}//fin if
     	break;
    }//fin
  }//fin foreach



}//fin







foreach($datos_cscd03_cotizacion_encabezado as $aux_cscd03_cotizacion_encabezado){
  $ano_cotizacion          =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['ano_cotizacion'];
  $numero_cotizacion       =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['numero_cotizacion'];
  $ano_solicitud           =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['ano_solicitud'];
  $numero_solicitud        =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['numero_solicitud'];
}//fin



$datos_cscd02_solicitud_encabezado      =   $this->cscd02_solicitud_encabezado->findAll($condicion." and ano_solicitud='".$ano_solicitud."' and numero_solicitud='".$numero_solicitud."'  ");

foreach($datos_cscd02_solicitud_encabezado as $aux_cscd02_solicitud_encabezado){
  $cod_dir_superior     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_dir_superior'];
  $cod_coordinacion     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_coordinacion'];
  $cod_secretaria       =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_secretaria'];
  $cod_direccion        =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_direccion'];
  $cod_division         =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_division'];
  $cod_departamento     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_departamento'];
  $cod_oficina          =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_oficina'];
}//fin

$sql_cugd02_direccion  = " cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia = ".$cod_dep." and cod_dir_superior = ".$cod_dir_superior." ";
$sql_cugd02_direccion .= " and  cod_coordinacion=".$cod_coordinacion." and  cod_secretaria=".$cod_secretaria." and cod_direccion=".$cod_direccion." ";
$datos_cugd02_direccion      =   $this->cugd02_direccion->findAll($sql_cugd02_direccion);


}//fin de script








}else if($radio=="2" && $numero_orden_compra_a!="" && $numero_orden_compra_b!=""){






    $ordencompra_encabezado = $this->cscd04_ordencompra_encabezado->findAll($condicion." and ano_orden_compra=".$ano_orden_compra_a."  and (numero_orden_compra>='$numero_orden_compra_a' and numero_orden_compra<='$numero_orden_compra_b')   and  tipo_orden=1   and condicion_actividad=1 ", null , 'numero_orden_compra ASC');
	$ordencompra_partidas   = $this->cscd04_ordencompra_partidas->findAll(  $condicion." and ano_orden_compra=".$ano_orden_compra_a."  and (numero_orden_compra>='$numero_orden_compra_a'  and numero_orden_compra<='$numero_orden_compra_b')  ");

$sql_para_por_emitir = " and ano=".$ano_orden_compra_a."  and (numero>='$numero_orden_compra_a' and numero<='$numero_orden_compra_b')";


$i=0;
$sql = "";
$sql_rif = "";

foreach($ordencompra_encabezado as $aux_cscd04_ordencompra_encabezado){ $i++;
  $ano_orden_compra[$i]     =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_orden_compra'];
  $numero_orden_compra[$i]  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_orden_compra'];
  $tipo_orden[$i]           =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['tipo_orden'];
  $rif[$i]                  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['rif'];
  $ano_cotizacion[$i]       =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_cotizacion'];
  $numero_cotizacion[$i]    =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_cotizacion'];


if($sql==""){$sql   .= "    ano_cotizacion='".$ano_cotizacion[$i]."' and  numero_cotizacion='".$numero_cotizacion[$i]."'  ";
       }else{$sql   .= " or  (ano_cotizacion='".$ano_cotizacion[$i]."' and  numero_cotizacion='".$numero_cotizacion[$i]."')  ";}


if($sql_rif==""){$sql_rif .= "rif='".$rif[$i]."'  ";}else{$sql_rif .= " or rif='".$rif[$i]."'  ";}


  $resultado=$this->cscd04_ordencompra_numero->execute("UPDATE cscd04_ordencompra_numero SET situacion = '3'  WHERE ".$condicion." and ano_orden_compra='".$ano_orden_compra[$i]."' and  numero_orden_compra='".$numero_orden_compra[$i]."' and situacion='2'  ");

}//fin


if($sql_rif==""){


	echo'<script>history.back(1);</script>';

}else{



//echo $sql.'<br>';

$datos_cscd03_cotizacion_encabezado     =   $this->cscd03_cotizacion_encabezado->findAll($condicion." and (".$sql_rif.") and (".$sql.")");
$datos_cscd03_cotizacion_cuerpo         =   $this->cscd03_cotizacion_cuerpo->findAll($condicion." and (".$sql_rif.") and (".$sql.")", null , 'codigo_prod_serv DESC' );
$lista                                  =   $this->v_cscd03_cotizacion->findAll($condicion." and (".$sql_rif.") and (".$sql.")", null , 'codigo_prod_serv DESC' );
$datos_cpcd02                           =   $this->cpcd02->findAll($sql_rif);





$i=0;
foreach($ordencompra_encabezado as $aux_cscd04_ordencompra_encabezado){$i++;
  $ano_orden_compra[$i]     =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_orden_compra'];
  $numero_orden_compra[$i]  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_orden_compra'];
  $tipo_orden[$i]           =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['tipo_orden'];
  $rif[$i]                  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['rif'];
  $ano_cotizacion[$i]       =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_cotizacion'];
  $numero_cotizacion[$i]    =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_cotizacion'];
  $monto_orden[$i]          =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['monto_orden'];

foreach($datos_cpcd02 as $aux_cpcd02){
     if($rif[$i] == $aux_cpcd02['cpcd02']['rif']){
     	if($this->cugd06_oficios_poremitir_comun->findCount($sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=1  and ano='".$ano_orden_compra[$i]."'  and numero='".$numero_orden_compra[$i]."' ")==0){
         $sql_por_emiter  = "INSERT INTO cugd06_oficios_poremitir_comun (cod_presi, cod_entidad , cod_tipo_inst, cod_inst, cod_dep, username, documento, ano, numero, beneficiario, monto)";
         $sql_por_emiter .= " VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$this->Session->read('nom_usuario')."', '1', '".$ano_orden_compra[$i]."', '".$numero_orden_compra[$i]."', '".$aux_cpcd02['cpcd02']['denominacion']."', '".$monto_orden[$i]."')";
         $R3=$this->cugd06_oficios_poremitir_comun->execute($sql_por_emiter);
     }//fin if
     break;
    }//fin
  }//fin foreach
}//fin



//echo'<pre>';
//print_r($lista);
//echo'</pre>';


//print_r($datos_cpcd02);

$sql_2 = "";
$j=0;

foreach($datos_cscd03_cotizacion_encabezado as $aux_cscd03_cotizacion_encabezado){$j++;
  $ano_cotizacion[$j]          =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['ano_cotizacion'];
  $numero_cotizacion[$j]       =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['numero_cotizacion'];
  $ano_solicitud[$j]           =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['ano_solicitud'];
  $numero_solicitud[$j]        =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['numero_solicitud'];

 if($sql_2==""){ $sql_2 .= "   ano_solicitud='".$ano_solicitud[$j]."' and numero_solicitud='".$numero_solicitud[$j]."'  ";
          }else{ $sql_2 .= "  or (ano_solicitud='".$ano_solicitud[$j]."' and  numero_solicitud='".$numero_solicitud[$j]."')  ";}//fin else

}//fin


$datos_cscd02_solicitud_encabezado     =   $this->cscd02_solicitud_encabezado->findAll($condicion." and (".$sql_2.")");

$sql_cugd02_direccion = "";
$x = 0;

foreach($datos_cscd02_solicitud_encabezado as $aux_cscd02_solicitud_encabezado){$x++;

  $cod_dir_superior[$x]     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_dir_superior'];
  $cod_coordinacion[$x]     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_coordinacion'];
  $cod_secretaria[$x]       =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_secretaria'];
  $cod_direccion[$x]        =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_direccion'];
  $cod_division[$x]         =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_division'];
  $cod_departamento[$x]     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_departamento'];
  $cod_oficina[$x]          =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_oficina'];


if($sql_cugd02_direccion==""){
$sql_cugd02_direccion  .= "  cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia = ".$cod_dep." and cod_dir_superior = ".$cod_dir_superior[$x]." ";
$sql_cugd02_direccion  .= " and  cod_coordinacion=".$cod_coordinacion[$x]." and  cod_secretaria=".$cod_secretaria[$x]." and cod_direccion=".$cod_direccion[$x]." ";

}else{

$sql_cugd02_direccion  .= " or (cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia = ".$cod_dep." and cod_dir_superior = ".$cod_dir_superior[$x]." ";
$sql_cugd02_direccion  .= " and  cod_coordinacion=".$cod_coordinacion[$x]." and  cod_secretaria=".$cod_secretaria[$x]." and cod_direccion=".$cod_direccion[$x].") ";


}//fin else

}//fin


//echo $sql_cugd02_direccion;

$datos_cugd02_direccion      =   $this->cugd02_direccion->findAll($sql_cugd02_direccion);

}//fin para el script




}else if($radio=="1"){




$datos_cscd04_ordencompra_poremitir_bienes      =   $this->cscd04_ordencompra_poremitir_bienes->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' " );

$ii=0;
$sql_1 = "";

foreach($datos_cscd04_ordencompra_poremitir_bienes as $aux_cscd04_ordencompra_poremitir_bienes){ $ii++;

  $ano_orden_compra_1[$ii]     =   $aux_cscd04_ordencompra_poremitir_bienes['cscd04_ordencompra_poremitir_bienes']['ano_orden_compra'];
  $numero_orden_compra_1[$ii]  =   $aux_cscd04_ordencompra_poremitir_bienes['cscd04_ordencompra_poremitir_bienes']['numero_orden_compra'];


if($sql_1==""){$sql_1   .= "    ano_orden_compra='".$ano_orden_compra_1[$ii]."' and  numero_orden_compra='".$numero_orden_compra_1[$ii]."'  ";
         }else{$sql_1   .= " or  (ano_orden_compra='".$ano_orden_compra_1[$ii]."' and  numero_orden_compra='".$numero_orden_compra_1[$ii]."')  ";  }

}//fin or



if($sql_1==""){

	echo'<script>history.back(1);</script>';

}else{

//echo $sql_1;

    $ordencompra_encabezado = $this->cscd04_ordencompra_encabezado->findAll($condicion." and (".$sql_1.") and  tipo_orden=1 and username_registro='".$this->Session->read('nom_usuario')."' and condicion_actividad=1 ", null , 'numero_orden_compra ASC');
	$ordencompra_partidas=$this->cscd04_ordencompra_partidas->findAll($condicion." and (".$sql_1.")");

///print_r($ordencompra_encabezado);


$i=0;
$sql = "";
$sql_rif = "";

foreach($ordencompra_encabezado as $aux_cscd04_ordencompra_encabezado){ $i++;
  $ano_orden_compra[$i]     =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_orden_compra'];
  $numero_orden_compra[$i]  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_orden_compra'];
  $tipo_orden[$i]           =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['tipo_orden'];
  $rif[$i]                  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['rif'];
  $ano_cotizacion[$i]       =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_cotizacion'];
  $numero_cotizacion[$i]    =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_cotizacion'];


if($sql==""){$sql   .= "    ano_cotizacion='".$ano_cotizacion[$i]."' and  numero_cotizacion='".$numero_cotizacion[$i]."'  ";     $sql_para_por_emitir .= "   and     ano='".$ano_orden_compra[$i]."' and  numero='".$numero_orden_compra[$i]."'  ";
       }else{$sql   .= " or  (ano_cotizacion='".$ano_cotizacion[$i]."' and  numero_cotizacion='".$numero_cotizacion[$i]."')  ";  $sql_para_por_emitir .= " or  (ano='".$ano_orden_compra[$i]."' and  numero='".$numero_orden_compra[$i]."')  ";     }


  if($sql_rif==""){$sql_rif .= "rif='".$rif[$i]."'  ";}else{$sql_rif .= " or rif='".$rif[$i]."'  ";}

$resultado=$this->cscd04_ordencompra_numero->execute("UPDATE cscd04_ordencompra_numero  SET situacion = '3'  WHERE ".$condicion." and ano_orden_compra='".$ano_orden_compra[$i]."' and  numero_orden_compra='".$numero_orden_compra[$i]."' and situacion='2'  ");

}//fin


if($sql==""){

	echo'<script>history.back(1);</script>';

}else{


//echo $sql.'<br>';

$datos_cscd03_cotizacion_encabezado     =   $this->cscd03_cotizacion_encabezado->findAll($condicion." and (".$sql_rif.") and (".$sql.")");
$datos_cscd03_cotizacion_cuerpo         =   $this->cscd03_cotizacion_cuerpo->findAll($condicion." and (".$sql_rif.") and (".$sql.")", null , 'codigo_prod_serv DESC' );
$lista                                  =   $this->v_cscd03_cotizacion->findAll($condicion." and (".$sql_rif.") and (".$sql.")", null , 'codigo_prod_serv DESC' );
$datos_cpcd02                           =   $this->cpcd02->findAll($sql_rif);


$i = 0;

foreach($ordencompra_encabezado as $aux_cscd04_ordencompra_encabezado){$i++;
  $ano_orden_compra[$i]     =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_orden_compra'];
  $numero_orden_compra[$i]  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_orden_compra'];
  $tipo_orden[$i]           =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['tipo_orden'];
  $rif[$i]                  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['rif'];
  $ano_cotizacion[$i]       =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_cotizacion'];
  $numero_cotizacion[$i]    =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_cotizacion'];
  $monto_orden[$i]          =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['monto_orden'];

foreach($datos_cpcd02 as $aux_cpcd02){
     if($rif[$i] == $aux_cpcd02['cpcd02']['rif']){
     	if($this->cugd06_oficios_poremitir_comun->findCount($sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=1  and ano='".$ano_orden_compra[$i]."'  and numero='".$numero_orden_compra[$i]."' ")==0){
         $sql_por_emiter  = "INSERT INTO cugd06_oficios_poremitir_comun (cod_presi, cod_entidad , cod_tipo_inst, cod_inst, cod_dep, username, documento, ano, numero, beneficiario, monto)";
         $sql_por_emiter .= " VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$this->Session->read('nom_usuario')."', '1', '".$ano_orden_compra[$i]."', '".$numero_orden_compra[$i]."', '".$aux_cpcd02['cpcd02']['denominacion']."', '".$monto_orden[$i]."')";
         $R3=$this->cugd06_oficios_poremitir_comun->execute($sql_por_emiter);
     }//fin if
      break;
    }//fin
  }//fin foreach
}//fin


//echo'<pre>';
//print_r($lista);
//echo'</pre>';


//print_r($datos_cpcd02);

$sql_2 = "";
$j=0;

foreach($datos_cscd03_cotizacion_encabezado as $aux_cscd03_cotizacion_encabezado){$j++;
  $ano_cotizacion[$j]          =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['ano_cotizacion'];
  $numero_cotizacion[$j]       =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['numero_cotizacion'];
  $ano_solicitud[$j]           =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['ano_solicitud'];
  $numero_solicitud[$j]        =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['numero_solicitud'];

 if($sql_2==""){ $sql_2 .= "   ano_solicitud='".$ano_solicitud[$j]."' and numero_solicitud='".$numero_solicitud[$j]."'  ";
          }else{ $sql_2 .= "  or (ano_solicitud='".$ano_solicitud[$j]."' and  numero_solicitud='".$numero_solicitud[$j]."')  ";}//fin else

}//fin


$datos_cscd02_solicitud_encabezado     =   $this->cscd02_solicitud_encabezado->findAll($condicion." and (".$sql_2.")");

$sql_cugd02_direccion = "";
$x = 0;

foreach($datos_cscd02_solicitud_encabezado as $aux_cscd02_solicitud_encabezado){$x++;

  $cod_dir_superior[$x]     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_dir_superior'];
  $cod_coordinacion[$x]     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_coordinacion'];
  $cod_secretaria[$x]       =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_secretaria'];
  $cod_direccion[$x]        =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_direccion'];
  $cod_division[$x]         =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_division'];
  $cod_departamento[$x]     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_departamento'];
  $cod_oficina[$x]          =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_oficina'];


if($sql_cugd02_direccion==""){
$sql_cugd02_direccion  .= " cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia = ".$cod_dep." and cod_dir_superior = ".$cod_dir_superior[$x]." ";
$sql_cugd02_direccion  .= " and  cod_coordinacion=".$cod_coordinacion[$x]." and  cod_secretaria=".$cod_secretaria[$x]." and cod_direccion=".$cod_direccion[$x]." ";

}else{

$sql_cugd02_direccion  .= " or (cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia = ".$cod_dep." and cod_dir_superior = ".$cod_dir_superior[$x]." ";
$sql_cugd02_direccion  .= " and  cod_coordinacion=".$cod_coordinacion[$x]." and  cod_secretaria=".$cod_secretaria[$x]." and cod_direccion=".$cod_direccion[$x].") ";


}//fin else


}//fin



$datos_cugd02_direccion      =   $this->cugd02_direccion->findAll($sql_cugd02_direccion);


$sql = "delete from cscd04_ordencompra_poremitir_bienes  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
$this->cscd04_ordencompra_poremitir_bienes->execute($sql);

}//fin else


}//fin else


}//fin else





if($ordencompra_encabezado!=""){
    $this->set('lista_cscd02_solicitud_cuerpo', $lista);
    $this->set('datos_cscd03_cotizacion_encabezado', $datos_cscd03_cotizacion_encabezado);
    $this->set('datos_cpcd02', $datos_cpcd02);
    $this->set('datos_cscd02_solicitud_encabezado', $datos_cscd02_solicitud_encabezado);
    $this->set('datos_cscd03_cotizacion_cuerpo', $datos_cscd03_cotizacion_cuerpo);
    $this->set('datos_cugd02_direccion', $datos_cugd02_direccion);
    $this->set('ordencompra_partidas', $ordencompra_partidas);
	$this->set('ordencompra_encabezado', $ordencompra_encabezado);
}//fin if



$sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=1  ".$sql_para_por_emitir;
$this->set('cugd06_oficios_poremitir_comun', $this->cugd06_oficios_poremitir_comun->findAll($sql, null, 'numero ASC'));


 if($datos_cugd02_direccion!=""){$this->layout = "pdf"; }else{$this->layout = "ajax"; $this->set('errorMessage', "No existe alguna de la orden selecionada"); }


}//fin else



$this->set('titulo_a', $this->Session->read('dependencia'));
$this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin opcion vista

}//fin function






////////////////////////////////////////////////////////////////////////////////////////////////////







/*
 * Reporte de orden de servivio formato actual, es decir, formato preimpreso.
 */
function cscp04_ordencompra_servicio_formato_actual($year=null, $opcion=null, $cont=null){

   $this->layout = "ajax";

    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $sql_para_por_emitir = "";

$this->Session->delete('FIN');

if($year == "vista"){

 $ano = $this->ano_ejecucion();


$this->set('year', $ano);
$this->set('vista', $opcion);


$url                  =  "/caop00_select_ventana_generar_reporte/v_reporte_emision_compra_servicio_1_actual";
$width_aux            =  "750px";
$height_aux           =  "400px";
$title_aux            =  "Buscar";
$resizable_aux        =  false;
$maximizable_aux      =  false;
$minimizable_aux      =  false;
$closable_aux         =  false;

	  if($opcion==3){
	         echo"<script>";
	           echo  "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
	         echo"</script>";
    	}else{
              echo"<script>";
               echo  " Windows.close(document.getElementById('capa_ventana').value)";
              echo"</script>";

	}//fin else


}else{




if(!$year){


    $this->limpia_menu();
    $this->set('ir', 'no');



    $ano = $this->ano_ejecucion();
    $dato = $this->ano_ejecucion();

	if(!empty($dato)){

		$this->set('year', $dato);

	}else{
		$this->set('year', '');
	}



$sql = "delete from cugd06_oficios_poremitir_comun  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=2  ";
$this->cugd06_oficios_poremitir_comun->execute($sql);



$datos_cscd04_ordencompra_poremitir_servicio = $this->cscd04_ordencompra_poremitir_servicio->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' ");
$this->set('datos_cscd04_ordencompra_poremitir_servicio', $datos_cscd04_ordencompra_poremitir_servicio);
$this->set('usuario', $this->Session->read('nom_usuario'));


}else{


  	$this->layout = "pdf";
  	$radio = 1;
  	$ordencompra_encabezado = "";
  	$datos_cugd02_direccion = "";
  	$numero_orden_compra_a  = "";
  	$numero_orden_compra_b  = "";


         if(isset($this->data['cscp04_ordencompra_servicio']['radio'])){$radio = $this->data['cscp04_ordencompra_servicio']['radio'];}
  		 if(isset($this->data['cscp04_ordencompra_servicio']['ano'.$cont.''])){$ano_orden_compra_a = $this->data['cscp04_ordencompra_servicio']['ano'.$cont.''];}
  		 if(isset($this->data['cscp04_ordencompra_servicio']['numero_a'.$cont.''])){$numero_orden_compra_a = $this->data['cscp04_ordencompra_servicio']['numero_a'.$cont.''];}
  		 if(isset($this->data['cscp04_ordencompra_servicio']['numero_b'])){$numero_orden_compra_b = $this->data['cscp04_ordencompra_servicio']['numero_b'];}




if($radio=="3" && $numero_orden_compra_a!=""){


    $ordencompra_encabezado = $this->cscd04_ordencompra_encabezado->findAll($condicion." and ano_orden_compra=".$ano_orden_compra_a."  and numero_orden_compra='$numero_orden_compra_a'  and  tipo_orden=2   and  condicion_actividad=1 ", null , 'numero_orden_compra ASC');
	$ordencompra_partidas=$this->cscd04_ordencompra_partidas->findAll($condicion." and ano_orden_compra=".$ano_orden_compra_a."  and numero_orden_compra='$numero_orden_compra_a' ");


$sql_para_por_emitir = " and ano=".$ano_orden_compra_a."  and numero='$numero_orden_compra_a'";

$sql_rif_a = "";

foreach($ordencompra_encabezado as $aux_cscd04_ordencompra_encabezado){
  $ano_orden_compra     =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_orden_compra'];
  $numero_orden_compra  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_orden_compra'];
  $tipo_orden           =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['tipo_orden'];
  $rif                  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['rif'];
  $ano_cotizacion       =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_cotizacion'];
  $numero_cotizacion    =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_cotizacion'];

$resultado=$this->cscd04_ordencompra_numero->execute("UPDATE cscd04_ordencompra_numero  SET  situacion = '3'  WHERE ".$condicion." and ano_orden_compra='".$ano_orden_compra."' and  numero_orden_compra='".$numero_orden_compra."' and situacion='2'  ");

$sql_rif_a = "asd";

}//fin




if($sql_rif_a==""){

	echo'<script>history.back(1);</script>';

}else{


$datos_cscd03_cotizacion_encabezado     =   $this->cscd03_cotizacion_encabezado->findAll($condicion." and rif='".$rif."'  and ano_cotizacion='".$ano_cotizacion."' and numero_cotizacion='".$numero_cotizacion."'  ");
$datos_cscd03_cotizacion_cuerpo         =   $this->cscd03_cotizacion_cuerpo->findAll($condicion."     and rif='".$rif."'  and ano_cotizacion='".$ano_cotizacion."' and numero_cotizacion='".$numero_cotizacion."'  ", null , 'codigo_prod_serv DESC' );
$lista                                  =   $this->v_cscd03_cotizacion->findAll($condicion."          and rif='".$rif."'  and ano_cotizacion='".$ano_cotizacion."' and numero_cotizacion='".$numero_cotizacion."'  ", null , 'codigo_prod_serv DESC' );
$datos_cpcd02                           =   $this->cpcd02->findAll(" rif='".$rif."' ");



foreach($ordencompra_encabezado as $aux_cscd04_ordencompra_encabezado){
  $ano_orden_compra     =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_orden_compra'];
  $numero_orden_compra  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_orden_compra'];
  $tipo_orden           =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['tipo_orden'];
  $rif                  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['rif'];
  $ano_cotizacion       =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_cotizacion'];
  $numero_cotizacion    =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_cotizacion'];
  $monto_orden          =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['monto_orden'];



foreach($datos_cpcd02 as $aux_cpcd02){
     if($rif == $aux_cpcd02['cpcd02']['rif']){
     	if($this->cugd06_oficios_poremitir_comun->findCount($sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=2  and ano='".$ano_orden_compra."'  and numero='".$numero_orden_compra."' ")==0){
         $sql_por_emiter  = "INSERT INTO cugd06_oficios_poremitir_comun (cod_presi, cod_entidad , cod_tipo_inst, cod_inst, cod_dep, username, documento, ano, numero, beneficiario, monto)";
         $sql_por_emiter .= " VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$this->Session->read('nom_usuario')."', '2', '".$ano_orden_compra."', '".$numero_orden_compra."', '".$aux_cpcd02['cpcd02']['denominacion']."', '".$monto_orden."')";
         $R3=$this->cugd06_oficios_poremitir_comun->execute($sql_por_emiter);
        }//fin
        break;
     }//fin if
  }//fin foreach
}//fin



foreach($datos_cscd03_cotizacion_encabezado as $aux_cscd03_cotizacion_encabezado){
  $ano_cotizacion          =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['ano_cotizacion'];
  $numero_cotizacion       =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['numero_cotizacion'];
  $ano_solicitud           =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['ano_solicitud'];
  $numero_solicitud        =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['numero_solicitud'];
}//fin



$datos_cscd02_solicitud_encabezado      =   $this->cscd02_solicitud_encabezado->findAll($condicion." and ano_solicitud='".$ano_solicitud."' and numero_solicitud='".$numero_solicitud."'  ");



foreach($datos_cscd02_solicitud_encabezado as $aux_cscd02_solicitud_encabezado){
  $cod_dir_superior     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_dir_superior'];
  $cod_coordinacion     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_coordinacion'];
  $cod_secretaria       =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_secretaria'];
  $cod_direccion        =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_direccion'];
  $cod_division         =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_division'];
  $cod_departamento     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_departamento'];
  $cod_oficina          =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_oficina'];
}//fin



$sql_cugd02_direccion  = " cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia = ".$cod_dep." and cod_dir_superior = ".$cod_dir_superior." ";
$sql_cugd02_direccion .= " and  cod_coordinacion=".$cod_coordinacion." and  cod_secretaria=".$cod_secretaria." and cod_direccion=".$cod_direccion." ";



$datos_cugd02_direccion      =   $this->cugd02_direccion->findAll($sql_cugd02_direccion);




}//fin script







}else if($radio=="2" && $numero_orden_compra_a!="" && $numero_orden_compra_b!=""){






    $ordencompra_encabezado = $this->cscd04_ordencompra_encabezado->findAll($condicion." and ano_orden_compra=".$ano_orden_compra_a."  and (numero_orden_compra>='$numero_orden_compra_a' and numero_orden_compra<='$numero_orden_compra_b')   and  tipo_orden=2  and condicion_actividad=1 ", null , 'numero_orden_compra ASC');
	$ordencompra_partidas   = $this->cscd04_ordencompra_partidas->findAll($condicion." and ano_orden_compra=".$ano_orden_compra_a."  and (numero_orden_compra>='$numero_orden_compra_a'  and numero_orden_compra<='$numero_orden_compra_b')  ");


$sql_para_por_emitir = " and ano=".$ano_orden_compra_a."  and (numero>='$numero_orden_compra_a' and numero<='$numero_orden_compra_b')";

$i=0;
$sql = "";
$sql_rif = "";
$sql_rif_a ="";

foreach($ordencompra_encabezado as $aux_cscd04_ordencompra_encabezado){ $i++;
  $ano_orden_compra[$i]     =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_orden_compra'];
  $numero_orden_compra[$i]  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_orden_compra'];
  $tipo_orden[$i]           =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['tipo_orden'];
  $rif[$i]                  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['rif'];
  $ano_cotizacion[$i]       =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_cotizacion'];
  $numero_cotizacion[$i]    =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_cotizacion'];


if($sql==""){$sql   .= "    ano_cotizacion='".$ano_cotizacion[$i]."' and  numero_cotizacion='".$numero_cotizacion[$i]."'  ";
       }else{$sql   .= " or  (ano_cotizacion='".$ano_cotizacion[$i]."' and  numero_cotizacion='".$numero_cotizacion[$i]."')  ";}


  if($sql_rif==""){$sql_rif .= "rif='".$rif[$i]."'  ";}else{$sql_rif .= " or (rif='".$rif[$i]."')  ";}


$resultado=$this->cscd04_ordencompra_numero->execute("UPDATE cscd04_ordencompra_numero SET  situacion = '3'  WHERE ".$condicion." and ano_orden_compra='".$ano_orden_compra[$i]."' and  numero_orden_compra='".$numero_orden_compra[$i]."' and situacion='2'  ");

$sql_rif_a = "asdasd";

}//fin


if($sql_rif_a==""){


	echo'<script>history.back(1);</script>';

}else{



$datos_cscd03_cotizacion_encabezado     =   $this->cscd03_cotizacion_encabezado->findAll($condicion." and (".$sql_rif.") and (".$sql.")");
$datos_cscd03_cotizacion_cuerpo         =   $this->cscd03_cotizacion_cuerpo->findAll($condicion." and (".$sql_rif.") and (".$sql.")", null , 'codigo_prod_serv DESC' );
$lista                                  =   $this->v_cscd03_cotizacion->findAll($condicion." and (".$sql_rif.") and (".$sql.")", null, 'codigo_prod_serv DESC' );
$datos_cpcd02                           =   $this->cpcd02->findAll($sql_rif);





$i = 0;

foreach($ordencompra_encabezado as $aux_cscd04_ordencompra_encabezado){$i++;
  $ano_orden_compra[$i]     =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_orden_compra'];
  $numero_orden_compra[$i]  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_orden_compra'];
  $tipo_orden[$i]           =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['tipo_orden'];
  $rif[$i]                  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['rif'];
  $ano_cotizacion[$i]       =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_cotizacion'];
  $numero_cotizacion[$i]    =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_cotizacion'];
  $monto_orden[$i]          =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['monto_orden'];

foreach($datos_cpcd02 as $aux_cpcd02){
     if($rif[$i] == $aux_cpcd02['cpcd02']['rif']){
     	if($this->cugd06_oficios_poremitir_comun->findCount($sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=2  and ano='".$ano_orden_compra[$i]."'  and numero='".$numero_orden_compra[$i]."' ")==0){
         $sql_por_emiter  = "INSERT INTO cugd06_oficios_poremitir_comun (cod_presi, cod_entidad , cod_tipo_inst, cod_inst, cod_dep, username, documento, ano, numero, beneficiario, monto)";
         $sql_por_emiter .= " VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$this->Session->read('nom_usuario')."', '2', '".$ano_orden_compra[$i]."', '".$numero_orden_compra[$i]."', '".$aux_cpcd02['cpcd02']['denominacion']."', '".$monto_orden[$i]."')";
         $R3=$this->cugd06_oficios_poremitir_comun->execute($sql_por_emiter);
          }//fin if
        break;
      }//fin
  }//fin foreach
}//fin


//echo'<pre>';
//print_r($lista);
//echo'</pre>';


//print_r($datos_cpcd02);

$sql_2 = "";
$j=0;

foreach($datos_cscd03_cotizacion_encabezado as $aux_cscd03_cotizacion_encabezado){$j++;
  $ano_cotizacion[$j]          =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['ano_cotizacion'];
  $numero_cotizacion[$j]       =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['numero_cotizacion'];
  $ano_solicitud[$j]           =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['ano_solicitud'];
  $numero_solicitud[$j]        =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['numero_solicitud'];

 if($sql_2==""){ $sql_2 .= "   ano_solicitud='".$ano_solicitud[$j]."' and numero_solicitud='".$numero_solicitud[$j]."'  ";
          }else{ $sql_2 .= "  or (ano_solicitud='".$ano_solicitud[$j]."' and  numero_solicitud='".$numero_solicitud[$j]."')  ";}//fin else

}//fin


$datos_cscd02_solicitud_encabezado     =   $this->cscd02_solicitud_encabezado->findAll($condicion." and (".$sql_2.")");

$sql_cugd02_direccion = "";
$x = 0;

foreach($datos_cscd02_solicitud_encabezado as $aux_cscd02_solicitud_encabezado){$x++;

  $cod_dir_superior[$x]     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_dir_superior'];
  $cod_coordinacion[$x]     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_coordinacion'];
  $cod_secretaria[$x]       =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_secretaria'];
  $cod_direccion[$x]        =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_direccion'];
  $cod_division[$x]         =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_division'];
  $cod_departamento[$x]     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_departamento'];
  $cod_oficina[$x]          =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_oficina'];


if($sql_cugd02_direccion==""){
$sql_cugd02_direccion  .= " cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia = ".$cod_dep." and cod_dir_superior = ".$cod_dir_superior[$x]." ";
$sql_cugd02_direccion  .= " and  cod_coordinacion=".$cod_coordinacion[$x]." and  cod_secretaria=".$cod_secretaria[$x]." and cod_direccion=".$cod_direccion[$x]." ";

}else{

$sql_cugd02_direccion  .= " or (cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia = ".$cod_dep." and cod_dir_superior = ".$cod_dir_superior[$x]." ";
$sql_cugd02_direccion  .= " and  cod_coordinacion=".$cod_coordinacion[$x]." and  cod_secretaria=".$cod_secretaria[$x]." and cod_direccion=".$cod_direccion[$x].") ";


}//fin else


}//fin



$datos_cugd02_direccion      =   $this->cugd02_direccion->findAll($sql_cugd02_direccion);


}//fin script

}else if($radio=="1"){




$datos_cscd04_ordencompra_poremitir_servicio      =   $this->cscd04_ordencompra_poremitir_servicio->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' " );

$ii=0;
$sql_1 = "";

foreach($datos_cscd04_ordencompra_poremitir_servicio as $aux_cscd04_ordencompra_poremitir_servicio){ $ii++;

  $ano_orden_compra_1[$ii]     =   $aux_cscd04_ordencompra_poremitir_servicio['cscd04_ordencompra_poremitir_servicio']['ano_orden_compra'];
  $numero_orden_compra_1[$ii]  =   $aux_cscd04_ordencompra_poremitir_servicio['cscd04_ordencompra_poremitir_servicio']['numero_orden_compra'];



if($sql_1==""){$sql_1   .= "    ano_orden_compra='".$ano_orden_compra_1[$ii]."' and  numero_orden_compra='".$numero_orden_compra_1[$ii]."'  ";
         }else{$sql_1   .= " or  (ano_orden_compra='".$ano_orden_compra_1[$ii]."' and  numero_orden_compra='".$numero_orden_compra_1[$ii]."')  ";  }


}//fin or


//echo $sql_1;


if($sql_1==""){


	echo'<script>history.back(1);</script>';

}else{

    $ordencompra_encabezado = $this->cscd04_ordencompra_encabezado->findAll($condicion." and (".$sql_1.") and  tipo_orden=2 and username_registro='".$this->Session->read('nom_usuario')."' and condicion_actividad=1   ", null , 'numero_orden_compra ASC');
	$ordencompra_partidas=$this->cscd04_ordencompra_partidas->findAll($condicion." and (".$sql_1.")");

///print_r($ordencompra_encabezado);


$i=0;
$sql = "";
$sql_rif = "";

foreach($ordencompra_encabezado as $aux_cscd04_ordencompra_encabezado){ $i++;
  $ano_orden_compra[$i]     =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_orden_compra'];
  $numero_orden_compra[$i]  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_orden_compra'];
  $tipo_orden[$i]           =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['tipo_orden'];
  $rif[$i]                  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['rif'];
  $ano_cotizacion[$i]       =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_cotizacion'];
  $numero_cotizacion[$i]    =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_cotizacion'];


if($sql==""){$sql   .= "    ano_cotizacion='".$ano_cotizacion[$i]."' and  numero_cotizacion='".$numero_cotizacion[$i]."'   ";   $sql_para_por_emitir .= "   and     ano='".$ano_orden_compra[$i]."' and  numero='".$numero_orden_compra[$i]."'  ";
       }else{$sql   .= " or  (ano_cotizacion='".$ano_cotizacion[$i]."' and  numero_cotizacion='".$numero_cotizacion[$i]."')  "; $sql_para_por_emitir .= " or  (ano='".$ano_orden_compra[$i]."' and  numero='".$numero_orden_compra[$i]."')  ";  }


  if($sql_rif==""){$sql_rif .= "rif='".$rif[$i]."'  ";}else{$sql_rif .= " or rif='".$rif[$i]."'  ";}


$resultado=$this->cscd04_ordencompra_numero->execute("UPDATE cscd04_ordencompra_numero SET  situacion = '3' WHERE ".$condicion." and ano_orden_compra='".$ano_orden_compra[$i]."' and  numero_orden_compra='".$numero_orden_compra[$i]."' and situacion='2'  ");

}//fin


//echo $sql.'<br>';

$datos_cscd03_cotizacion_encabezado     =   $this->cscd03_cotizacion_encabezado->findAll($condicion." and (".$sql_rif.") and (".$sql.")");
$datos_cscd03_cotizacion_cuerpo         =   $this->cscd03_cotizacion_cuerpo->findAll($condicion." and (".$sql_rif.") and (".$sql.")", null , 'codigo_prod_serv DESC' );
$lista                                  =   $this->v_cscd03_cotizacion->findAll($condicion." and (".$sql_rif.") and (".$sql.")", null , 'codigo_prod_serv DESC' );
$datos_cpcd02                           =   $this->cpcd02->findAll($sql_rif);




$i = 0;

foreach($ordencompra_encabezado as $aux_cscd04_ordencompra_encabezado){$i++;
  $ano_orden_compra[$i]     =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_orden_compra'];
  $numero_orden_compra[$i]  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_orden_compra'];
  $tipo_orden[$i]           =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['tipo_orden'];
  $rif[$i]                  =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['rif'];
  $ano_cotizacion[$i]       =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['ano_cotizacion'];
  $numero_cotizacion[$i]    =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['numero_cotizacion'];
  $monto_orden[$i]          =   $aux_cscd04_ordencompra_encabezado['cscd04_ordencompra_encabezado']['monto_orden'];

foreach($datos_cpcd02 as $aux_cpcd02){
     if($rif[$i] == $aux_cpcd02['cpcd02']['rif']){
     	if($this->cugd06_oficios_poremitir_comun->findCount($sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=2  and ano='".$ano_orden_compra[$i]."'  and numero='".$numero_orden_compra[$i]."' ")==0){
         $sql_por_emiter  = "INSERT INTO cugd06_oficios_poremitir_comun (cod_presi, cod_entidad , cod_tipo_inst, cod_inst, cod_dep, username, documento, ano, numero, beneficiario, monto)";
         $sql_por_emiter .= " VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$this->Session->read('nom_usuario')."', '2', '".$ano_orden_compra[$i]."', '".$numero_orden_compra[$i]."', '".$aux_cpcd02['cpcd02']['denominacion']."', '".$monto_orden[$i]."')";
         $R3=$this->cugd06_oficios_poremitir_comun->execute($sql_por_emiter);
          }//fin
       break;
    }//fin
  }//fin foreach
}//fin




//echo'<pre>';
//print_r($lista);
//echo'</pre>';
//print_r($datos_cpcd02);


$sql_2 = "";
$j=0;

foreach($datos_cscd03_cotizacion_encabezado as $aux_cscd03_cotizacion_encabezado){$j++;
  $ano_cotizacion[$j]          =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['ano_cotizacion'];
  $numero_cotizacion[$j]       =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['numero_cotizacion'];
  $ano_solicitud[$j]           =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['ano_solicitud'];
  $numero_solicitud[$j]        =   $aux_cscd03_cotizacion_encabezado['cscd03_cotizacion_encabezado']['numero_solicitud'];

 if($sql_2==""){ $sql_2 .= "   ano_solicitud='".$ano_solicitud[$j]."' and numero_solicitud='".$numero_solicitud[$j]."'  ";
          }else{ $sql_2 .= "  or (ano_solicitud='".$ano_solicitud[$j]."' and  numero_solicitud='".$numero_solicitud[$j]."')  ";}//fin else

}//fin


$datos_cscd02_solicitud_encabezado     =   $this->cscd02_solicitud_encabezado->findAll($condicion." and (".$sql_2.")");

$sql_cugd02_direccion = "";
$x = 0;

foreach($datos_cscd02_solicitud_encabezado as $aux_cscd02_solicitud_encabezado){$x++;

  $cod_dir_superior[$x]     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_dir_superior'];
  $cod_coordinacion[$x]     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_coordinacion'];
  $cod_secretaria[$x]       =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_secretaria'];
  $cod_direccion[$x]        =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_direccion'];
  $cod_division[$x]         =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_division'];
  $cod_departamento[$x]     =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_departamento'];
  $cod_oficina[$x]          =   $aux_cscd02_solicitud_encabezado['cscd02_solicitud_encabezado']['cod_oficina'];


if($sql_cugd02_direccion==""){
$sql_cugd02_direccion  .= " cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia = ".$cod_dep." and cod_dir_superior = ".$cod_dir_superior[$x]." ";
$sql_cugd02_direccion  .= " and  cod_coordinacion=".$cod_coordinacion[$x]." and  cod_secretaria=".$cod_secretaria[$x]." and cod_direccion=".$cod_direccion[$x]." ";

}else{

$sql_cugd02_direccion  .= " or (cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia = ".$cod_dep." and cod_dir_superior = ".$cod_dir_superior[$x]." ";
$sql_cugd02_direccion  .= " and  cod_coordinacion=".$cod_coordinacion[$x]." and  cod_secretaria=".$cod_secretaria[$x]." and cod_direccion=".$cod_direccion[$x].") ";


}//fin else


}//fin



$datos_cugd02_direccion      =   $this->cugd02_direccion->findAll($sql_cugd02_direccion);


$sql = "delete from cscd04_ordencompra_poremitir_servicio  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
$this->cscd04_ordencompra_poremitir_servicio->execute($sql);

}//fin else


}//fin else


if($ordencompra_encabezado!=""){
    $this->set('lista_cscd02_solicitud_cuerpo', $lista);
    $this->set('datos_cscd03_cotizacion_encabezado', $datos_cscd03_cotizacion_encabezado);
    $this->set('datos_cpcd02', $datos_cpcd02);
    $this->set('datos_cscd02_solicitud_encabezado', $datos_cscd02_solicitud_encabezado);
    $this->set('datos_cscd03_cotizacion_cuerpo', $datos_cscd03_cotizacion_cuerpo);
    $this->set('datos_cugd02_direccion', $datos_cugd02_direccion);
    $this->set('ordencompra_partidas', $ordencompra_partidas);
	$this->set('ordencompra_encabezado', $ordencompra_encabezado);
}//fin if


 if($datos_cugd02_direccion!=""){$this->layout = "pdf"; }else{$this->layout = "ajax"; $this->set('errorMessage', "No existe alguna de la orden selecionada"); }


$sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=2  ".$sql_para_por_emitir;
$this->set('cugd06_oficios_poremitir_comun', $this->cugd06_oficios_poremitir_comun->findAll($sql, null, 'numero ASC'));


}//fin else





$this->set('titulo_a', $this->Session->read('dependencia'));
$this->set('entidad_federal', $this->Session->read('entidad_federal'));


}//fin opcion vista



}//fin function






//////////////////////////////////////////////////////////////////////////////////////////////////////










function ventana_3($var=null){
	$this->layout="ajax";

	if($var==1){

	}else{
		$url                  =  "/reportes_cao000/buscar_cao000_3";
		$width_aux            =  "750px";
		$height_aux           =  "400px";
		$title_aux            =  "Buscar";
		$resizable_aux        =  false;
		$maximizable_aux      =  false;
		$minimizable_aux      =  false;
		$closable_aux         =  false;

		 echo"<script>";
//		 	echo "document.getElementById('peticion_1').checked=false;";
//		 	echo "document.getElementById('peticion_2').checked=false;";
           echo  "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
         echo"</script>";
	}


}






function buscar_cao000_3(){
$this->layout="ajax";
}




function buscar_pista_obras_3($var1=null, $var2=null, $var3=null){
	$this->layout="ajax";

	$year_buscar         = $this->Session->read('ano_compra_consulta');
	$tabla = "cfpd07_obras_cuerpo";
	$campo = "cod_obra";

        $SScoddeporig             =       $this->Session->read('SScoddeporig');
	    $SScoddep                 =       $this->Session->read('SScoddep');
	    $Modulo                   =       $this->Session->read('Modulo');
		$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$SScoddep.' and ano_estimacion='.$year_buscar;
	    //$lista_ob = $this->cfpd07_obras_cuerpo->generateList($condicion.' and ano_estimacion='.$year_buscar.'   and (estimado_presu - ((monto_contratado+ aumento_obras) - disminucion_obras)) != 0     ', ' cod_obra ASC', null, '{n}.cfpd07_obras_cuerpo.cod_obra', '{n}.cfpd07_obras_cuerpo.denominacion');
		//$this->set('lista_codigos_obras', $lista_ob);
		//$this->concatena_sin_cero($lista_ob, 'lista_codigos_obras');

            if($var1!=null && $var1!='_'){
            	$var1 = strtoupper_sisap($var1);
				$this->Session->write('pista', $var1);
            }else{
				$var1  = $this->Session->read('pista');
            }//fin else

            if($var2!=null){
               $pagina = $var2;
            }else{
            	$pagina = 1;
            }
            $this->set('pista', $var1);


                            $condicion = $condicion.'';
                            $ordena = "cod_obra";
                            $Tfilas=$this->$tabla->findCount($condicion." and ".$this->busca_separado(array("cod_obra","denominacion"), $var1));
					        if($Tfilas!=0){

					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->$tabla->findAll($condicion." and ".$this->busca_separado(array("cod_obra","denominacion"), $var1),null,$ordena." ASC",100,$pagina,null);
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





























function reporte_relacion_ordenes_compra($ir=null, $cod=null){
	$this->layout="ajax";

	 set_time_limit(0);

	if($ir!=null){
		if($ir=='si'){//si va al formulario
		  $this->set('ano',$ano=$this->ano_ejecucion());
		  $_SESSION['ano_compra_consulta'] = $this->ano_ejecucion();
		  $this->set('ir','si');
		}elseif($ir=='no'){//no va al formulario, pero si va al reporte
		$this->layout="pdf";
		$this->set('ir','no');

		$cp  = $this->Session->read('SScodpresi');
		$ce  = $this->Session->read('SScodentidad');
		$cti = $this->Session->read('SScodtipoinst');
		$ci  = $this->Session->read('SScodinst');
		$cd  = $this->Session->read('SScoddep');

		if(isset($_SESSION['ano_compra_consulta'])){$ano = $_SESSION['ano_compra_consulta'];}else{$ano = $this->ano_ejecucion();}

			$sql_op = "";  $sql_op2 = "";
			$cond = $this->SQLCA()." and ano_orden_compra='$ano' and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL)";

							   if(isset($cod)){
								    				$sql_orden_encabezado="select
																			    a.cod_dep,
																				a.cod_entidad,
																				a.cod_tipo_inst,
																				a.cod_inst,
																				a.cod_dep,
																				a.ano_orden_compra,
																				a.numero_orden_compra,
																				a.condicion_actividad,
																				a.fecha_orden_compra,
																				a.cod_obra,
																				a.rif,
																				(select b.rif from cpcd02 b where b.rif=a.rif)::varchar(20) as rif,
																				(select b.denominacion from cpcd02 b where b.rif=a.rif)::varchar(100) as beneficiario,
																				a.monto_orden
																				from cscd04_ordencompra_encabezado a
																				where
																				a.cod_presi        = '$cp'  and
																				a.cod_entidad      = '$ce'  and
																				a.cod_tipo_inst    = '$cti' and
																				a.cod_inst         = '$ci'  and
																				a.cod_dep          = '$cd'  and
																				a.ano_orden_compra = '$ano' and (a.cod_obra!='' and a.cod_obra!='0' and a.cod_obra IS NOT NULL) and  upper(a.cod_obra)=upper('$cod')
																				order by a.cod_obra, a.numero_orden_compra";
														$filas_encabezado=$this->cscd04_ordencompra_encabezado->findCount($cond." and upper(cod_obra)=upper('$cod')");
								}else{

												     $sql_orden_encabezado="select
																			    a.cod_dep,
																				a.cod_entidad,
																				a.cod_tipo_inst,
																				a.cod_inst,
																				a.cod_dep,
																				a.ano_orden_compra,
																				a.numero_orden_compra,
																				a.condicion_actividad,
																				a.fecha_orden_compra,
																				a.cod_obra,
																				a.rif,
																				(select b.rif from cpcd02 b where b.rif=a.rif)::varchar(20) as rif,
																				(select b.denominacion from cpcd02 b where b.rif=a.rif)::varchar(100) as beneficiario,
																				a.monto_orden
																				from cscd04_ordencompra_encabezado a
																				where
																				a.cod_presi        = '$cp'  and
																				a.cod_entidad      = '$ce'  and
																				a.cod_tipo_inst    = '$cti' and
																				a.cod_inst         = '$ci'  and
																				a.cod_dep          = '$cd'  and
																				a.ano_orden_compra = '$ano' and (a.cod_obra!='' and a.cod_obra!='0' and a.cod_obra IS NOT NULL)
																				order by a.cod_obra, a.numero_orden_compra";

														$filas_encabezado=$this->cscd04_ordencompra_encabezado->findCount($cond);
							}



							$datos_orden_encabezado=$this->cscd04_ordencompra_encabezado->execute($sql_orden_encabezado);
							$datos_autorizacion_pago=$this->cscd04_ordencompra_autorizacion_cuerpo->findAll($this->SQLCA().$sql_op2,array('ano_orden_compra','numero_orden_compra','ano_orden_pago','numero_orden_pago'));
							$datos_ordenpago_cuerpo=$this->cepd03_ordenpago_cuerpo->findAll($this->SQLCA(),array('ano_orden_pago','numero_orden_pago','condicion_actividad','fecha_orden_pago','beneficiario','monto_total','numero_cheque','fecha_cheque'), 'numero_orden_pago ASC');

							$this->set('filas_encabezado',$filas_encabezado-1);
							$this->set('datos_orden_encabezado',$datos_orden_encabezado);
							$this->set('datos_autorizacion_pago',$datos_autorizacion_pago);
							$this->set('datos_ordenpago_cuerpo',$datos_ordenpago_cuerpo);


		}
	}
}// fin function





function distinct_razonsocial_ordenescompras($var=null){
	$this->layout="ajax";
	$array =  array();
	if($var==1){
		$this->set('var',$var);
	}elseif($var==3){
		$this->set('var',$var);
	}elseif($var==2){
		/*if(isset($_SESSION['ano_compra_consulta'])){$ano = $_SESSION['ano_compra_consulta'];}else{$ano = $this->ano_ejecucion();}
		$cpcd02=$this->cpcd02->findAll(null,array('rif','denominacion'));
		$rif=$this->cscd04_ordencompra_encabezado->execute("select distinct rif from cscd04_ordencompra_encabezado where ".$this->SQLCA()." and  ano_orden_compra='".$ano."' ");
		$i=-1;
		$array = array();
		foreach($rif as $x){$i=$i+1;}
		for($j=0; $j<=$i; $j++){
			foreach($cpcd02 as $razonsocial){
	     	  if($rif[$j][0]['rif'] == $razonsocial['cpcd02']['rif']){
	 		  $array[$rif[$j][0]['rif']] = $razonsocial['cpcd02']['denominacion'];
			  }
			}
		}*/
		$this->set('razonsocial',$array);
		$this->set('var',$var);
	}


echo "<script>";
  echo "if(document.getElementById('datos-consulta')){document.getElementById('datos-consulta').innerHTML='';}";
echo "</script>";




}//razonsocial_ordenescompras






/** OPCIONFECHA
 * Orden de pago:       1
 * Registro compromiso: 2
 * Orden compra:        3
 */
function opcionfecha($documento=null, $var1=null){
	$this->layout="ajax";
	//$documento: Indica si es una orden de pago:1 -- registro de compromiso:2 -- orden de compra:3
	$this->set('documento',$documento);



echo "<script>";
  echo "document.getElementById('td-fecha').innerHTML='';";
echo "</script>";



}



function seleccionarfecha($documento=null,$opcion=null){
	$this->layout="ajax";
	//$documento: Indica si es una orden de pago:1 -- registro de compromiso:2 -- orden de compra:3
	//$opcion: Indica si marco la opcion de generar todos o generar por fecha.
	if($opcion!=null){
	$this->set('documento',$documento);
	$this->set('opcion',$opcion);
	}
}




function vacio(){
	$this->layout="ajax";
	$this->Session->delete('ano_consulta_actaanul');
}



/////////////////////////////////////////////////////////////////////////////////////////////////////////////7





function estado_cuenta_proveedores_form () {
	$this->layout="ajax";
	$Ano=$this->ano_ejecucion();
	$_SESSION['buscar_year_proveed'] = $Ano;
	$this->set('ANO',$Ano);

}//estado_cuentas_proveedores_form

function estado_cuenta_proveedores () {
	$this->layout="pdf";

     $this->set('entidad_federal', $this->Session->read('entidad_federal'));
	     if(isset($this->data["reporte"]["ano"]) && !empty($this->data["reporte"]["ano"])){
              $Ano=$this->data["reporte"]["ano"];
	     }else{
	     	$Ano=$this->ano_ejecucion();
	     }
    	$this->set('ANO',$Ano);



    	if(isset($this->data['cfpp05']['consolidacion'])){
    	    $con    = $this->SQLCA_consolidado($this->data['cfpp05']['consolidacion']);
    	     $con_a = $this->SQLCA_consolidado_opcion($this->data['cfpp05']['consolidacion'], "a");
    	}else{
    		$con=$this->SQLCA_consolidado();
    		$con_a=$this->SQLCA_consolidado_opcion(null, "a");
    	}


        $titulo_a = $this->Session->read('dependencia');
  	    $this->set('titulo_a',$titulo_a);

  	    $con_rif = "";
  	    if($this->data['reporte2']['proveed'] == 4){
  	    	if(isset($this->data['reporte2']['rif_prov'])){
				$rif = $this->data['reporte2']['rif_prov'];
				$con_rif = " and a.rif='$rif' ";
  	    	}
  	    }

     $estado_cuenta_proveedores=$this->cscd04_ordencompra_encabezado->execute("SELECT
a.cod_presi,
a.cod_entidad,
a.cod_tipo_inst,
a.cod_inst,
a.cod_dep,
a.ano_orden_compra,
a.numero_orden_compra,
a.tipo_orden,
a.fecha_orden_compra,
a.rif,
upper(a.razon_social) as razon_social,
a.monto_orden,a.modificacion_aumento,a.modificacion_disminucion,
a.monto_anticipo,
a.monto_amortizacion,
a.monto_cancelado,
b.ano_orden_pago,
b.numero_orden_pago,
b.monto_orden_pago,
b.monto_neto_cobrar,
null_cero((SELECT c.monto FROM cstd07_retenciones_cuerpo_islr c WHERE
b.cod_presi=c.cod_presi and
b.cod_entidad=c.cod_entidad and
b.cod_tipo_inst=c.cod_tipo_inst and
b.cod_inst=c.cod_inst and
b.cod_dep=c.cod_dep and
b.ano_orden_pago=c.ano_orden_pago and
b.numero_orden_pago = c.numero_orden_pago and c.status=2)) as monto_isrl,
(SELECT c.numero_cheque FROM cstd07_retenciones_cuerpo_islr c WHERE
b.cod_presi=c.cod_presi and
b.cod_entidad=c.cod_entidad and
b.cod_tipo_inst=c.cod_tipo_inst and
b.cod_inst=c.cod_inst and
b.cod_dep=c.cod_dep and
b.ano_orden_pago=c.ano_orden_pago and
b.numero_orden_pago = c.numero_orden_pago and c.status=2) as cheque_isrl,
(SELECT c.fecha_proceso_registro FROM cstd07_retenciones_cuerpo_islr c WHERE
b.cod_presi=c.cod_presi and
b.cod_entidad=c.cod_entidad and
b.cod_tipo_inst=c.cod_tipo_inst and
b.cod_inst=c.cod_inst and
b.cod_dep=c.cod_dep and
b.ano_orden_pago=c.ano_orden_pago and
b.numero_orden_pago = c.numero_orden_pago and c.status=2) as fecha_isrl,
null_cero((SELECT d.monto FROM cstd07_retenciones_cuerpo_iva d WHERE
b.cod_presi=d.cod_presi and
b.cod_entidad=d.cod_entidad and
b.cod_tipo_inst=d.cod_tipo_inst and
b.cod_inst=d.cod_inst and
b.cod_dep=d.cod_dep and
b.ano_orden_pago=d.ano_orden_pago and
b.numero_orden_pago = d.numero_orden_pago and d.status=2)) as monto_iva,
(SELECT d.numero_cheque FROM cstd07_retenciones_cuerpo_iva d WHERE
b.cod_presi=d.cod_presi and
b.cod_entidad=d.cod_entidad and
b.cod_tipo_inst=d.cod_tipo_inst and
b.cod_inst=d.cod_inst and
b.cod_dep=d.cod_dep and
b.ano_orden_pago=d.ano_orden_pago and
b.numero_orden_pago = d.numero_orden_pago and d.status=2) as cheque_iva,
(SELECT d.fecha_proceso_registro FROM cstd07_retenciones_cuerpo_iva d WHERE
b.cod_presi=d.cod_presi and
b.cod_entidad=d.cod_entidad and
b.cod_tipo_inst=d.cod_tipo_inst and
b.cod_inst=d.cod_inst and
b.cod_dep=d.cod_dep and
b.ano_orden_pago=d.ano_orden_pago and
b.numero_orden_pago = d.numero_orden_pago and d.status=2) as fecha_iva,
null_cero((SELECT e.monto FROM cstd07_retenciones_cuerpo_municipal e WHERE
b.cod_presi=e.cod_presi and
b.cod_entidad=e.cod_entidad and
b.cod_tipo_inst=e.cod_tipo_inst and
b.cod_inst=e.cod_inst and
b.cod_dep=e.cod_dep and
b.ano_orden_pago=e.ano_orden_pago and
b.numero_orden_pago = e.numero_orden_pago and e.status=2)) as monto_municipal,
(SELECT e.numero_cheque FROM cstd07_retenciones_cuerpo_municipal e WHERE
b.cod_presi=e.cod_presi and
b.cod_entidad=e.cod_entidad and
b.cod_tipo_inst=e.cod_tipo_inst and
b.cod_inst=e.cod_inst and
b.cod_dep=e.cod_dep and
b.ano_orden_pago=e.ano_orden_pago and
b.numero_orden_pago = e.numero_orden_pago and e.status=2) as cheque_municipal,
(SELECT e.fecha_proceso_registro FROM cstd07_retenciones_cuerpo_municipal e WHERE
b.cod_presi=e.cod_presi and
b.cod_entidad=e.cod_entidad and
b.cod_tipo_inst=e.cod_tipo_inst and
b.cod_inst=e.cod_inst and
b.cod_dep=e.cod_dep and
b.ano_orden_pago=e.ano_orden_pago and
b.numero_orden_pago = e.numero_orden_pago and e.status=2) as fecha_municipal,
null_cero((SELECT f.monto FROM cstd07_retenciones_cuerpo_timbre f WHERE
b.cod_presi=f.cod_presi and
b.cod_entidad=f.cod_entidad and
b.cod_tipo_inst=f.cod_tipo_inst and
b.cod_inst=f.cod_inst and
b.cod_dep=f.cod_dep and
b.ano_orden_pago=f.ano_orden_pago and
b.numero_orden_pago = f.numero_orden_pago and f.status=2)) as monto_timbre,
(SELECT f.numero_cheque FROM cstd07_retenciones_cuerpo_timbre f WHERE
b.cod_presi=f.cod_presi and
b.cod_entidad=f.cod_entidad and
b.cod_tipo_inst=f.cod_tipo_inst and
b.cod_inst=f.cod_inst and
b.cod_dep=f.cod_dep and
b.ano_orden_pago=f.ano_orden_pago and
b.numero_orden_pago = f.numero_orden_pago and f.status=2) as cheque_timbre,
(SELECT f.fecha_proceso_registro FROM cstd07_retenciones_cuerpo_timbre f WHERE
b.cod_presi=f.cod_presi and
b.cod_entidad=f.cod_entidad and
b.cod_tipo_inst=f.cod_tipo_inst and
b.cod_inst=f.cod_inst and
b.cod_dep=f.cod_dep and
b.ano_orden_pago=f.ano_orden_pago and
b.numero_orden_pago = f.numero_orden_pago and f.status=2) as fecha_timbre,






null_cero((SELECT f.monto FROM cstd07_retenciones_cuerpo_multa f WHERE
b.cod_presi=f.cod_presi and
b.cod_entidad=f.cod_entidad and
b.cod_tipo_inst=f.cod_tipo_inst and
b.cod_inst=f.cod_inst and
b.cod_dep=f.cod_dep and
b.ano_orden_pago=f.ano_orden_pago and
b.numero_orden_pago = f.numero_orden_pago and f.status=2)) as monto_multa,
(SELECT f.numero_cheque FROM cstd07_retenciones_cuerpo_multa f WHERE
b.cod_presi=f.cod_presi and
b.cod_entidad=f.cod_entidad and
b.cod_tipo_inst=f.cod_tipo_inst and
b.cod_inst=f.cod_inst and
b.cod_dep=f.cod_dep and
b.ano_orden_pago=f.ano_orden_pago and
b.numero_orden_pago = f.numero_orden_pago and f.status=2) as cheque_multa,
(SELECT f.fecha_proceso_registro FROM cstd07_retenciones_cuerpo_multa f WHERE
b.cod_presi=f.cod_presi and
b.cod_entidad=f.cod_entidad and
b.cod_tipo_inst=f.cod_tipo_inst and
b.cod_inst=f.cod_inst and
b.cod_dep=f.cod_dep and
b.ano_orden_pago=f.ano_orden_pago and
b.numero_orden_pago = f.numero_orden_pago and f.status=2) as fecha_multa,






null_cero((SELECT f.monto FROM cstd07_retenciones_cuerpo_responsabilidad f WHERE
b.cod_presi=f.cod_presi and
b.cod_entidad=f.cod_entidad and
b.cod_tipo_inst=f.cod_tipo_inst and
b.cod_inst=f.cod_inst and
b.cod_dep=f.cod_dep and
b.ano_orden_pago=f.ano_orden_pago and
b.numero_orden_pago = f.numero_orden_pago and f.status=2)) as monto_responsabilidad,
(SELECT f.numero_cheque FROM cstd07_retenciones_cuerpo_responsabilidad f WHERE
b.cod_presi=f.cod_presi and
b.cod_entidad=f.cod_entidad and
b.cod_tipo_inst=f.cod_tipo_inst and
b.cod_inst=f.cod_inst and
b.cod_dep=f.cod_dep and
b.ano_orden_pago=f.ano_orden_pago and
b.numero_orden_pago = f.numero_orden_pago and f.status=2) as cheque_responsabilidad,
(SELECT f.fecha_proceso_registro FROM cstd07_retenciones_cuerpo_responsabilidad f WHERE
b.cod_presi=f.cod_presi and
b.cod_entidad=f.cod_entidad and
b.cod_tipo_inst=f.cod_tipo_inst and
b.cod_inst=f.cod_inst and
b.cod_dep=f.cod_dep and
b.ano_orden_pago=f.ano_orden_pago and
b.numero_orden_pago = f.numero_orden_pago and f.status=2) as fecha_responsabilidad,








b.fecha_orden_pago,
b.fecha_cheque,
b.numero_cheque,
(SELECT denominacion FROM cstd01_entidades_bancarias x WHERE x.cod_entidad_bancaria=b.cod_entidad_bancaria) as entidad_bancaria
   FROM v_cscd04_ordencompra a, cepd03_ordenpago_cuerpo b
WHERE
a.cod_presi=b.cod_presi and
a.cod_entidad=b.cod_entidad and
a.cod_tipo_inst=b.cod_tipo_inst and
a.cod_inst=b.cod_inst and
a.cod_dep=b.cod_dep and
a.ano_orden_compra=b.ano_documento_origen and
a.numero_orden_compra::varchar(30)=b.numero_documento_origen::varchar(30) and
(b.cod_tipo_documento=2 OR b.cod_tipo_documento=3) and
b.condicion_actividad=1 and b.numero_cheque!=0 and ".$con_a." and a.ano_orden_compra=".$Ano.$con_rif." ORDER BY a.rif, a.numero_orden_compra ASC");

$this->set("estado_cuenta_proveedores",$estado_cuenta_proveedores);

}//estado_cuenta_proveedore



function buscar_elige_prov($consolida=null, $opc=null, $pista=null){
	$this->layout = "ajax";
	$this->set('opc', $opc);
	//echo $consolida;


	   if(isset($_SESSION['buscar_year_proveed'])){$ano = $_SESSION['buscar_year_proveed'];}else{$ano = $this->ano_ejecucion();}

	if($consolida == 1){
		$cond = $this->SQLCA_consolidado($consolida)." and ano_orden_compra='".$ano."' and upper(razon_social) LIKE upper('%".$pista."%') ";
	}else{
		$cond = $this->SQLCA_consolidado($consolida)." and ano_orden_compra='".$ano."' and upper(razon_social) LIKE upper('%".$pista."%')  ";
	}
	$rif = $this->v_cscd04_ordencompra->generateList($conditions = $cond." and condicion_actividad=1", $order = 'razon_social ASC', $limit = null, '{n}.v_cscd04_ordencompra.rif', '{n}.v_cscd04_ordencompra.razon_social');
	$this->concatena_sin_cero($rif, 'rif');

}//fin function




function elige_prov($consolida=null, $opc=null){
	$this->layout = "ajax";
	$this->set('opc', $opc);
	//echo $consolida;


	   if(isset($_SESSION['buscar_year_proveed'])){$ano = $_SESSION['buscar_year_proveed'];}else{$ano = $this->ano_ejecucion();}

	if($consolida == 1){
		$cond = $this->SQLCA_consolidado($consolida)." and ano_orden_compra='".$ano."' ";
	}else{
		$cond = $this->SQLCA_consolidado($consolida)." and ano_orden_compra='".$ano."' ";
	}
	//$rif = $this->v_cscd04_ordencompra->generateList($conditions = $cond." and condicion_actividad=1", $order = 'razon_social ASC', $limit = null, '{n}.v_cscd04_ordencompra.rif', '{n}.v_cscd04_ordencompra.razon_social');
	$this->concatena_sin_cero(array(), 'rif');
	$this->set('consolida', $consolida);

}


function elige_consolidar($consolida=null){
	$this->layout = "ajax";
	$this->set('consolida', $consolida);
}




/////////////////////////////////////////////////////////////////////////////////////////////////////////////////






function reporte_registro_compromiso($year=null, $opcion=null, $cont=null){

   $this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;

$this->Session->delete('FIN');

    $partidacorriente=407120;
	$partidacapital=407220;

if($year == "vista"){

  $ano = $this->ano_ejecucion();


$this->set('year', $ano);
$this->set('vista', $opcion);


$url                  =  "/select_ventana_generar_reporte/v_reporte_emision_rc_generico_1";
$width_aux            =  "750px";
$height_aux           =  "400px";
$title_aux            =  "Buscar";
$resizable_aux        =  false;
$maximizable_aux      =  false;
$minimizable_aux      =  false;
$closable_aux         =  false;

	  if($opcion==3){
	         echo"<script>";
	           echo  "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
	         echo"</script>";
    	}else{
              echo"<script>";
               echo  " Windows.close(document.getElementById('capa_ventana').value)";
              echo"</script>";

	}//fin else



}else{



if(!$year){


    $this->limpia_menu();
    $this->set('ir', 'no');

    $ano = $this->ano_ejecucion();

	if(!empty($dato)){
		$this->set('year', $dato);
	}else{
		$this->set('year', '');
	}


$sql = "delete from cugd06_oficios_poremitir_comun  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=3  ";
$this->cugd06_oficios_poremitir_comun->execute($sql);

$datos_cepd01_compromiso_poremitir = $this->cepd01_compromiso_poremitir->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' ");
$this->set('datos_cepd01_compromiso_poremitir', $datos_cepd01_compromiso_poremitir);
$this->set('usuario', $this->Session->read('nom_usuario'));


}else{


  	$this->layout = "pdf";
  	$radio = 1;
  	$numero_compromiso_a  = "";
  	$numero_compromiso_b  = "";


         if(isset($this->data['cepp01_compromiso']['radio'])){$radio = $this->data['cepp01_compromiso']['radio'];}
  		 if(isset($this->data['cepp01_compromiso']['ano'.$cont.''])){     $ano_compromiso_a = $this->data['cepp01_compromiso']['ano'.$cont.''];}
  		 if(isset($this->data['cepp01_compromiso']['numero_a'.$cont.''])){$numero_compromiso_a = $this->data['cepp01_compromiso']['numero_a'.$cont.''];}
  		 if(isset($this->data['cepp01_compromiso']['numero_b'])){$numero_compromiso_b = $this->data['cepp01_compromiso']['numero_b'];}




if($radio=="3" && $numero_compromiso_a!=""){////////////////////////////////////////////////OPCION 1

	$datos_cepd01_compromiso_cuerpo=$this->cepd01_compromiso_cuerpo->findAll($condicion." and condicion_actividad=1 and ano_documento=".$ano_compromiso_a."  and numero_documento='$numero_compromiso_a' ", null, ' numero_documento ASC');
	$datos_cepd01_compromiso_partidas=$this->cepd01_compromiso_partidas->findAll($condicion." and ano_documento=".$ano_compromiso_a."  and numero_documento='$numero_compromiso_a' ",  null, ' cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC');

$sql_para_por_emitir = " and ano=".$ano_compromiso_a."  and numero='$numero_compromiso_a' ";


$sql_rif_a ="";

$aux= 0;
foreach($datos_cepd01_compromiso_cuerpo as $aux_cepd01_compromiso_cuerpo){$aux++;
   $tipodocu[$aux]=$this->cepd01_tipo_compromiso->findAll('cod_tipo_compromiso='.$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_compromiso']);
   $corriente[$aux]     = 0;
   $capital[$aux]       = 0;
   $ordinario[$aux]     = 0;
   $coordinado[$aux]    = 0;
   $fci[$aux]           = 0;
   $mpps[$aux]          = 0;
   $ingresosextra[$aux] = 0;
   $ingresospropios[$aux] = 0;
   $laee[$aux]          = 0;
   $fides[$aux]         = 0;


foreach($datos_cepd01_compromiso_partidas as $partidas){
			$sql=$condicion." and ano=".$partidas['cepd01_compromiso_partidas']['ano_documento']." and cod_sector=".$partidas['cepd01_compromiso_partidas']['cod_sector']." and cod_programa=".$partidas['cepd01_compromiso_partidas']['cod_programa']." and cod_sub_prog=".$partidas['cepd01_compromiso_partidas']['cod_sub_prog']." and cod_proyecto=".$partidas['cepd01_compromiso_partidas']['cod_proyecto']." and cod_activ_obra=".$partidas['cepd01_compromiso_partidas']['cod_activ_obra']." and cod_partida=".$partidas['cepd01_compromiso_partidas']['cod_partida']." and cod_generica=".$partidas['cepd01_compromiso_partidas']['cod_generica']." and cod_especifica=".$partidas['cepd01_compromiso_partidas']['cod_especifica']." and cod_sub_espec=".$partidas['cepd01_compromiso_partidas']['cod_sub_espec']." and cod_auxiliar=".$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
			$busqueda=$this->cfpd05->findAll($sql,array('tipo_presupuesto', 'cod_tipo_gasto'));
         if($busqueda!=null){
				     if($busqueda[0]['cfpd05']['tipo_presupuesto']==1){$ordinario[$aux]=1;
				}elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==2){$coordinado[$aux]=1;
				}elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==3){$fci[$aux]=1;
				}elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==4){$mpps[$aux]=1;
				}elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==5){$ingresosextra[$aux]=1;
				}elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==6){$ingresospropios[$aux]=1;
				}elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==7){$laee[$aux]=1;
				}elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==8){$fides[$aux]=1;}

				if($busqueda[0]['cfpd05']['cod_tipo_gasto']==2){
					  $capital[$aux]   = 1;
				}else{$corriente[$aux] = 1;}
			}//fin if
			$evalpartcorriente = $partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
			$evalpartcapital   = $partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
			//if($evalpartcorriente==$partidacorriente){$corriente[$aux]=1;}
			//if($evalpartcapital==$partidacapital){$capital[$aux]=1;}
}//fin for

$resultado  =  $this->cepd01_compromiso_numero->execute("UPDATE cepd01_compromiso_numero  SET  situacion='3'  WHERE  ".$condicion." and ano_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento']."' and  numero_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento']."' and situacion='2'  ");
$rif        =  $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif'];

        $cugd02_direccionsup  =  $this->cugd02_direccionsuperior->findAll("cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior'],array('denominacion'));
		$cugd02_coordinacion  =  $this->cugd02_coordinacion->findAll("     cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']."  and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion'],array('denominacion'));
		$cugd02_secretaria    =  $this->cugd02_secretaria->findAll("       cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria'],array('denominacion'));
		$cugd02_direccion     =  $this->cugd02_direccion->findAll("        cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria']." and cod_direccion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_direccion'],array('denominacion'));
		$direccionsup[$aux]   =  $cugd02_direccionsup[0]['cugd02_direccionsuperior']['denominacion'];
		$coordinacion[$aux]   =  $cugd02_coordinacion[0]['cugd02_coordinacion']['denominacion'];
		$secretaria[$aux]     =  $cugd02_secretaria[0]['cugd02_secretaria']['denominacion'];
		$direccion[$aux]      =  $cugd02_direccion[0]['cugd02_direccion']['denominacion'];
		  $ano_documento      =  $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento'];
		  $numero_documento   =  $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento'];
		  $monto              =  $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['monto'];

if($this->cugd06_oficios_poremitir_comun->findCount($sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=3  and ano='".$ano_documento."'  and numero='".$numero_documento."' ")==0){
         $sql_por_emiter  = "INSERT INTO cugd06_oficios_poremitir_comun (cod_presi, cod_entidad , cod_tipo_inst, cod_inst, cod_dep, username, documento, ano, numero, beneficiario, monto)";
         $sql_por_emiter .= " VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$this->Session->read('nom_usuario')."', '3', '".$ano_documento."', '".$numero_documento."', '".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['beneficiario']."', '".$monto."')";
         $R3=$this->cugd06_oficios_poremitir_comun->execute($sql_por_emiter);
}//fin if
$sql_rif_a = "asdsad";

}//fin foreach de cuerpo


if($sql_rif_a==""){
	echo'<script>history.back(1);</script>';
}else{

$datos_cpcd02      =   $this->cpcd02->findAll(" rif='".$rif."' ");

}//fin fopr






}else if($radio=="2" && $numero_compromiso_a!="" && $numero_compromiso_b!=""){///////////////////////////////////////OPCION 2

	$datos_cepd01_compromiso_cuerpo=$this->cepd01_compromiso_cuerpo->findAll($condicion." and condicion_actividad=1 and ano_documento=".$ano_compromiso_a."  and (numero_documento>='$numero_compromiso_a'  and numero_documento<='$numero_compromiso_b') ", null, ' numero_documento ASC');
	$datos_cepd01_compromiso_partidas=$this->cepd01_compromiso_partidas->findAll($condicion." and ano_documento=".$ano_compromiso_a."  and (numero_documento>='$numero_compromiso_a'  and numero_documento<='$numero_compromiso_b') ",  null, ' cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC');

$sql_para_por_emitir = " and ano=".$ano_compromiso_a."  and (numero>='$numero_compromiso_a'  and numero<='$numero_compromiso_a')  ";

$sql_rif_a = "";

$aux= 0;
$sql_rif = "";
foreach($datos_cepd01_compromiso_cuerpo as $aux_cepd01_compromiso_cuerpo){$aux++;
   $tipodocu[$aux]=$this->cepd01_tipo_compromiso->findAll('cod_tipo_compromiso='.$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_compromiso']);
   $corriente[$aux]=0;
   $capital[$aux]=0;
   $ordinario[$aux]     = 0;
   $coordinado[$aux]    = 0;
   $fci[$aux]           = 0;
   $mpps[$aux]          = 0;
   $ingresosextra[$aux] = 0;
   $ingresospropios[$aux] = 0;
   $laee[$aux]          = 0;
   $fides[$aux]         = 0;

foreach($datos_cepd01_compromiso_partidas as $partidas){
	if($partidas['cepd01_compromiso_partidas']['ano_documento'] == $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento']  &&  $partidas['cepd01_compromiso_partidas']['numero_documento'] == $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento']){
			$sql=$condicion." and ano=".$partidas['cepd01_compromiso_partidas']['ano_documento']."  and cod_sector=".$partidas['cepd01_compromiso_partidas']['cod_sector']." and cod_programa=".$partidas['cepd01_compromiso_partidas']['cod_programa']." and cod_sub_prog=".$partidas['cepd01_compromiso_partidas']['cod_sub_prog']." and cod_proyecto=".$partidas['cepd01_compromiso_partidas']['cod_proyecto']." and cod_activ_obra=".$partidas['cepd01_compromiso_partidas']['cod_activ_obra']." and cod_partida=".$partidas['cepd01_compromiso_partidas']['cod_partida']." and cod_generica=".$partidas['cepd01_compromiso_partidas']['cod_generica']." and cod_especifica=".$partidas['cepd01_compromiso_partidas']['cod_especifica']." and cod_sub_espec=".$partidas['cepd01_compromiso_partidas']['cod_sub_espec']." and cod_auxiliar=".$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
			$busqueda=$this->cfpd05->findAll($sql,array('tipo_presupuesto', 'cod_tipo_gasto'));
         if($busqueda!=null){
				     if($busqueda[0]['cfpd05']['tipo_presupuesto']==1){$ordinario[$aux]=1;
				}elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==2){$coordinado[$aux]=1;
				}elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==3){$fci[$aux]=1;
				}elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==4){$mpps[$aux]=1;
				}elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==5){$ingresosextra[$aux]=1;
				}elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==6){$ingresospropios[$aux]=1;
				}elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==7){$laee[$aux]=1;
				}elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==8){$fides[$aux]=1;}
				if($busqueda[0]['cfpd05']['cod_tipo_gasto']==2){
					  $capital[$aux]   = 1;
				}else{$corriente[$aux] = 1;}
			}//fin if
			$evalpartcorriente=$partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
			$evalpartcapital=$partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
			//if($evalpartcorriente==$partidacorriente){$corriente[$aux]=1;}
			//if($evalpartcapital==$partidacapital){$capital[$aux]=1;}
	      }//fin if
		}//fin for

$resultado=$this->cepd01_compromiso_numero->execute("UPDATE cepd01_compromiso_numero  SET  situacion='3'  WHERE  ".$condicion." and ano_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento']."' and  numero_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento']."' and situacion='2'  ");
$rif =  $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif'];



        $cugd02_direccionsup  =  $this->cugd02_direccionsuperior->findAll("cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior'],array('denominacion'));
		$cugd02_coordinacion  =  $this->cugd02_coordinacion->findAll("     cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']."  and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion'],array('denominacion'));
		$cugd02_secretaria    =  $this->cugd02_secretaria->findAll("       cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria'],array('denominacion'));
		$cugd02_direccion     =  $this->cugd02_direccion->findAll("        cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria']." and cod_direccion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_direccion'],array('denominacion'));

		$direccionsup[$aux] = $cugd02_direccionsup[0]['cugd02_direccionsuperior']['denominacion'];
		$coordinacion[$aux] = $cugd02_coordinacion[0]['cugd02_coordinacion']['denominacion'];
		$secretaria[$aux]   = $cugd02_secretaria[0]['cugd02_secretaria']['denominacion'];
		$direccion[$aux]    = $cugd02_direccion[0]['cugd02_direccion']['denominacion'];

if($sql_rif==""){$sql_rif .= "rif='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif']."'  ";}else{$sql_rif .= " or rif='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif']."'  ";}


  $ano_documento     =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento'];
  $numero_documento  =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento'];
  $monto             =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['monto'];

if($this->cugd06_oficios_poremitir_comun->findCount($sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=3  and ano='".$ano_documento."'  and numero='".$numero_documento."' ")==0){
         $sql_por_emiter  = "INSERT INTO cugd06_oficios_poremitir_comun (cod_presi, cod_entidad , cod_tipo_inst, cod_inst, cod_dep, username, documento, ano, numero, beneficiario, monto)";
         $sql_por_emiter .= " VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$this->Session->read('nom_usuario')."', '3', '".$ano_documento."', '".$numero_documento."', '".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['beneficiario']."', '".$monto."')";
         $R3=$this->cugd06_oficios_poremitir_comun->execute($sql_por_emiter);
}//fin if

$sql_rif_a = "asdasd";

}//fin for each

if($sql_rif_a==""){
	echo'<script>history.back(1);</script>';
}else{

$datos_cpcd02                           =   $this->cpcd02->findAll($sql_rif);

}//fin for





}else if($radio=="1"){//////////////////////////////////////////////////////////////////////////////////OPCIOPN 3





$datos_cepd01_compromiso_poremitir      =   $this->cepd01_compromiso_poremitir->findAll($condicion." and username='".$this->Session->read('nom_usuario')."' " );

$ii=0;
$sql_1 = "";
$sql_para_por_emitir = "";
foreach($datos_cepd01_compromiso_poremitir as $aux_cepd01_compromiso_poremitir){ $ii++;

  $ano_documento_1[$ii]     =   $aux_cepd01_compromiso_poremitir['cepd01_compromiso_poremitir']['ano_documento'];
  $numero_documento_1[$ii]  =   $aux_cepd01_compromiso_poremitir['cepd01_compromiso_poremitir']['numero_documento'];


if($sql_1==""){$sql_1   .= "  ano_documento='".$ano_documento_1[$ii]."' and  numero_documento='".$numero_documento_1[$ii]."'  ";       $sql_para_por_emitir .= "    and   ano='".$ano_documento_1[$ii]."' and  numero='".$numero_documento_1[$ii]."'  ";
         }else{$sql_1   .= " or  (ano_documento='".$ano_documento_1[$ii]."' and  numero_documento='".$numero_documento_1[$ii]."')  ";  $sql_para_por_emitir .= " or  (ano='".$ano_documento_1[$ii]."' and  numero='".$numero_documento_1[$ii]."')  ";}

}//fin for


if($sql_1==""){

	echo'<script>history.back(1);</script>';

}else{


	$datos_cepd01_compromiso_cuerpo    =  $this->cepd01_compromiso_cuerpo->findAll(  $condicion." and condicion_actividad=1 and (".$sql_1.") and username_registro='".$this->Session->read('nom_usuario')."'   ", null, 'numero_documento ASC');
    $datos_cepd01_compromiso_partidas  =  $this->cepd01_compromiso_partidas->findAll($condicion." and (".$sql_1.")",  null, ' cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC');


$aux= 0;
$sql_rif = "";
foreach($datos_cepd01_compromiso_cuerpo as $aux_cepd01_compromiso_cuerpo){$aux++;
   $tipodocu[$aux]=$this->cepd01_tipo_compromiso->findAll('cod_tipo_compromiso='.$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_compromiso']);
   $corriente[$aux]     = 0;
   $capital[$aux]       = 0;
   $ordinario[$aux]     = 0;
   $coordinado[$aux]    = 0;
   $fci[$aux]           = 0;
   $mpps[$aux]          = 0;
   $ingresosextra[$aux] = 0;
   $ingresospropios[$aux] = 0;
   $laee[$aux]          = 0;
   $fides[$aux]         = 0;

foreach($datos_cepd01_compromiso_partidas as $partidas){
		if($partidas['cepd01_compromiso_partidas']['ano_documento'] == $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento']  &&  $partidas['cepd01_compromiso_partidas']['numero_documento'] == $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento']){
			$sql=$condicion." and ano=".$partidas['cepd01_compromiso_partidas']['ano_documento']." and cod_sector=".$partidas['cepd01_compromiso_partidas']['cod_sector']." and cod_programa=".$partidas['cepd01_compromiso_partidas']['cod_programa']." and cod_sub_prog=".$partidas['cepd01_compromiso_partidas']['cod_sub_prog']." and cod_proyecto=".$partidas['cepd01_compromiso_partidas']['cod_proyecto']." and cod_activ_obra=".$partidas['cepd01_compromiso_partidas']['cod_activ_obra']." and cod_partida=".$partidas['cepd01_compromiso_partidas']['cod_partida']." and cod_generica=".$partidas['cepd01_compromiso_partidas']['cod_generica']." and cod_especifica=".$partidas['cepd01_compromiso_partidas']['cod_especifica']." and cod_sub_espec=".$partidas['cepd01_compromiso_partidas']['cod_sub_espec']." and cod_auxiliar=".$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
			$busqueda=$this->cfpd05->findAll($sql,array('tipo_presupuesto', 'cod_tipo_gasto'));
         if($busqueda!=null){
				     if($busqueda[0]['cfpd05']['tipo_presupuesto']==1){$ordinario[$aux]     = 1;
				}elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==2){$coordinado[$aux]    = 1;
				}elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==3){$fci[$aux]          = 1;
				}elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==4){$mpps[$aux]         = 1;
				}elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==5){$ingresosextra[$aux] = 1;
				}elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==6){$ingresospropios[$aux]=1;
				}elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==7){$laee[$aux]=1;
				}elseif($busqueda[0]['cfpd05']['tipo_presupuesto']==8){$fides[$aux]=1;}
				     if($busqueda[0]['cfpd05']['cod_tipo_gasto']==2){
					    $capital[$aux] = 1;
				}else{$corriente[$aux] = 1; }
			}//fin if
			$evalpartcorriente = $partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
			$evalpartcapital   = $partidas['cepd01_compromiso_partidas']['cod_partida'].$partidas['cepd01_compromiso_partidas']['cod_generica'].$partidas['cepd01_compromiso_partidas']['cod_especifica'].$partidas['cepd01_compromiso_partidas']['cod_sub_espec'].$partidas['cepd01_compromiso_partidas']['cod_auxiliar'];
			//if($evalpartcorriente==$partidacorriente){$corriente[$aux]=1;}
			//if($evalpartcapital==$partidacapital){$capital[$aux]=1;}
		  }//fin if
		}//fin for
                   $resultado =  $this->cepd01_compromiso_numero->execute("UPDATE cepd01_compromiso_numero  SET  situacion='3'  WHERE  ".$condicion." and ano_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento']."' and  numero_compromiso='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento']."' and situacion='2'  ");
                        $rif  =  $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif'];
        $cugd02_direccionsup  =  $this->cugd02_direccionsuperior->findAll("cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior'],array('denominacion'));
		$cugd02_coordinacion  =  $this->cugd02_coordinacion->findAll("     cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']."  and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion'],array('denominacion'));
		$cugd02_secretaria    =  $this->cugd02_secretaria->findAll("       cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria'],array('denominacion'));
		$cugd02_direccion     =  $this->cugd02_direccion->findAll("        cod_tipo_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_tipo_inst']." and cod_institucion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_inst']." and cod_dependencia=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dep']." and cod_dir_superior=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_dir_superior']." and cod_coordinacion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_coordinacion']." and cod_secretaria=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_secretaria']." and cod_direccion=".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['cod_direccion'],array('denominacion'));
		$direccionsup[$aux]   =  $cugd02_direccionsup[0]['cugd02_direccionsuperior']['denominacion'];
		$coordinacion[$aux]   =  $cugd02_coordinacion[0]['cugd02_coordinacion']['denominacion'];
		$secretaria[$aux]     =  $cugd02_secretaria[0]['cugd02_secretaria']['denominacion'];
		$direccion[$aux]      =  $cugd02_direccion[0]['cugd02_direccion']['denominacion'];
if($sql_rif==""){$sql_rif .= "rif='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif']."'  ";}else{$sql_rif .= " or rif='".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['rif']."'  ";}
  $ano_documento     =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['ano_documento'];
  $numero_documento  =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['numero_documento'];
  $monto             =   $aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['monto'];
if($this->cugd06_oficios_poremitir_comun->findCount($sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=3  and ano='".$ano_documento."'  and numero='".$numero_documento."' ")==0){
         $sql_por_emiter  = "INSERT INTO cugd06_oficios_poremitir_comun (cod_presi, cod_entidad , cod_tipo_inst, cod_inst, cod_dep, username, documento, ano, numero, beneficiario, monto)";
         $sql_por_emiter .= " VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$this->Session->read('nom_usuario')."', '3', '".$ano_documento."', '".$numero_documento."', '".$aux_cepd01_compromiso_cuerpo['cepd01_compromiso_cuerpo']['beneficiario']."', '".$monto."')";
         $R3=$this->cugd06_oficios_poremitir_comun->execute($sql_por_emiter);
}//fin if


}//fin for each


$datos_cpcd02    =   $this->cpcd02->findAll($sql_rif);


$sql = "delete from cepd01_compromiso_poremitir  where cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' ";
$this->cepd01_compromiso_poremitir->execute($sql);


}//fin else


                  }//fin else

		$this->set('direccionsup',$direccionsup);
		$this->set('coordinacion',$coordinacion);
		$this->set('secretaria',$secretaria);
		$this->set('direccion',$direccion);

     $this->set('corriente',$corriente);
	 $this->set('capital',$capital);
	 $this->set('ordinario',$ordinario);
	 $this->set('coordinado',$coordinado);
	 $this->set('fci',$fci);
	 $this->set('mpps',$mpps);
	 $this->set('ingresosextra',$ingresosextra);
	 $this->set('ingresospropios',$ingresospropios);
	 $this->set('laee',$laee);
	 $this->set('fides',$fides);
	 $this->set('tipodocu',$tipodocu);
	 $this->set('datos_cepd01_compromiso_cuerpo',$datos_cepd01_compromiso_cuerpo);
	 $this->set('datos_cepd01_compromiso_partidas',$datos_cepd01_compromiso_partidas);
     $sql = "cod_presi=".$this->Session->read('SScodpresi')." and cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and username='".$this->Session->read('nom_usuario')."' and documento=3  ".$sql_para_por_emitir;
     $this->set('cugd06_oficios_poremitir_comun', $this->cugd06_oficios_poremitir_comun->findAll($sql, null, 'numero ASC'));

}//fin else





$this->set('titulo_a', $this->Session->read('dependencia'));
$this->set('entidad_federal', $this->Session->read('entidad_federal'));






}//fin else


}//fin funtion





//////////////////////////////////////////////////////////////////////////////////////////////////////////





function reporte_registro_compromiso_pre_impreso($ir=null,$opcion=null, $cont=null){
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;

//	cepd01_compromiso_cuerpo
//cepd01_compromiso_partidas
	if($ir=='no'){
		$this->layout="ajax";

		$this->set('ir','no');
		$this->set('usuario', $this->Session->read('nom_usuario'));
	}else if($ir=='vista'){
		$this->layout="ajax";

		$ano = $this->ano_ejecucion();
		$this->set('year', $ano);
		$this->set('vista','');
		$this->set('opcion',$opcion);




			$url                  =  "/select_ventana_generar_reporte/v_reporte_emision_rc_pre_imp_1";
			$width_aux            =  "750px";
			$height_aux           =  "400px";
			$title_aux            =  "Buscar";
			$resizable_aux        =  false;
			$maximizable_aux      =  false;
			$minimizable_aux      =  false;
			$closable_aux         =  false;

				  if($opcion==2){
				         echo"<script>";
				           echo  "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
				         echo"</script>";
			    	}else{
			              echo"<script>";
			               echo  " Windows.close(document.getElementById('capa_ventana').value)";
			              echo"</script>";

				}//fin else







	}else if($ir=='si'){
		$this->layout="pdf";
		$this->set('ir','si');
		$opcion=$this->data['cepp01_compromiso']['radio'];
			if($opcion==1){
				$this->set('modo',1);
				$ano=$this->data['cepp01_compromiso']['ano'.$cont.''];
				$num_a=$this->data['cepp01_compromiso']['numero_a'.$cont.''];
				$num_b=$this->data['cepp01_compromiso']['numero_b'];
				$k=0;
				for($i=$num_a;$i<=$num_b;$i++){
					$verifica=$this->cepd01_compromiso_cuerpo->FindAll($this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$i);
					if($verifica!=null){
			$query = " SELECT
						a.cod_presi,
						a.cod_entidad,
						a.cod_tipo_inst,
						a.cod_inst,
						a.cod_dep,
						a.ano_documento,
						a.numero_documento,
						a.cod_tipo_compromiso,
						a.fecha_documento,
						a.tipo_recurso,
						a.rif,
						a.cedula_identidad,
						a.concepto,
						a.beneficiario,
						a.condicion_juridica,
						a.monto,
						b.cod_sector,
						b.cod_programa,
						b.cod_sub_prog,
						b.cod_proyecto,
						b.cod_activ_obra,
						b.cod_partida,
						b.cod_generica,
						b.cod_especifica,
						b.cod_sub_espec,
						b.cod_auxiliar,
						b.monto as monto_partidas
						FROM cepd01_compromiso_partidas b,cepd01_compromiso_cuerpo a
						WHERE
						a.cod_presi=a.cod_presi and
						a.cod_entidad=b.cod_entidad and
						a.cod_tipo_inst=b.cod_tipo_inst and
						a.cod_inst=b.cod_inst and
						a.cod_dep=b.cod_dep and
						a.ano_documento=b.ano_documento and
						a.numero_documento=b.numero_documento and
						a.cod_presi=".$cod_presi." and
						a.cod_entidad=".$cod_entidad." and
						a.cod_tipo_inst=".$cod_tipo_inst." and
						a.cod_inst=".$cod_inst." and
						a.cod_dep=".$cod_dep." and
						a.ano_documento=".$ano." and
						a.numero_documento=".$i."
						ORDER BY
						a.cod_presi,
						a.cod_entidad,
						a.cod_tipo_inst,
						a.cod_inst,
						a.cod_dep,
						a.ano_documento,
						a.numero_documento,
						b.cod_sector,
						b.cod_programa,
						b.cod_sub_prog,
						b.cod_proyecto,
						b.cod_activ_obra,
						b.cod_partida,
						b.cod_generica,
						b.cod_especifica,
						b.cod_sub_espec,
						b.cod_auxiliar";
				$vector_data[$k]=$this->cepd01_compromiso_cuerpo->execute($query);
				$k++;
					}//fin verifica
				}//fio for
                        $cuerpo = $vector_data;
                 for($i=0;$i<count($cuerpo);$i++){
                 	$tipo_gasto_1[$i] = 0;
					$tipo_gasto_2[$i] = 0;
					for($j=0;$j<count($cuerpo[$i]);$j++){
										$cod_sector=$cuerpo[$i][$j][0]['cod_sector'];
									    $cod_programa=$cuerpo[$i][$j][0]['cod_programa'];
									    $cod_sub_prog=$cuerpo[$i][$j][0]['cod_sub_prog'];
										$cod_proyecto=$cuerpo[$i][$j][0]['cod_proyecto'];
										$cod_activ_obra=$cuerpo[$i][$j][0]['cod_activ_obra'];
										$cod_partida=$cuerpo[$i][$j][0]['cod_partida'];
										$cod_generica=$cuerpo[$i][$j][0]['cod_generica'];
										$cod_especifica=$cuerpo[$i][$j][0]['cod_especifica'];
										$cod_sub_espec=$cuerpo[$i][$j][0]['cod_sub_espec'];
										$cod_auxiliar=$cuerpo[$i][$j][0]['cod_auxiliar'];
										$cadena=$cod_partida.$cod_generica.$cod_especifica.$cod_sub_espec;

								$sql=$condicion." and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar;
								$busqueda=$this->cfpd05->findAll($sql,array('tipo_presupuesto', 'cod_tipo_gasto'));
					         if($busqueda!=null){
									     if($busqueda[0]['cfpd05']['cod_tipo_gasto']==2){
										    $tipo_gasto_1[$i] = 1;
									}else  {$tipo_gasto_2[$i] = 1; }
								}//fin if

					   }//fin for
					}//fin for



					$this->set('tipo_1',$tipo_gasto_1);
					$this->set('tipo_2',$tipo_gasto_2);


//			pr($vector_data);
//echo count($vector_data[0]);
				$this->set('cuerpo',$vector_data);
			}else{
				$this->set('modo',2);
				$ano   = $this->data['cepp01_compromiso']['ano'.$cont.''];
				$num_a = $this->data['cepp01_compromiso']['numero_a'.$cont.''];
//				$datos = $this->cepd01_compromiso_cuerpo->FindAll($this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$num_a);
			$query  =  "SELECT
						a.cod_presi,
						a.cod_entidad,
						a.cod_tipo_inst,
						a.cod_inst,
						a.cod_dep,
						a.ano_documento,
						a.numero_documento,
						a.cod_tipo_compromiso,
						a.fecha_documento,
						a.tipo_recurso,
						a.rif,
						a.cedula_identidad,
						a.concepto,
						a.beneficiario,
						a.condicion_juridica,
						a.monto,
						b.cod_sector,
						b.cod_programa,
						b.cod_sub_prog,
						b.cod_proyecto,
						b.cod_activ_obra,
						b.cod_partida,
						b.cod_generica,
						b.cod_especifica,
						b.cod_sub_espec,
						b.cod_auxiliar,
						b.monto as monto_partidas
						FROM cepd01_compromiso_partidas b,cepd01_compromiso_cuerpo a
						WHERE
						a.cod_presi=a.cod_presi and
						a.cod_entidad=b.cod_entidad and
						a.cod_tipo_inst=b.cod_tipo_inst and
						a.cod_inst=b.cod_inst and
						a.cod_dep=b.cod_dep and
						a.ano_documento=b.ano_documento and
						a.numero_documento=b.numero_documento and
						a.cod_presi=".$cod_presi." and
						a.cod_entidad=".$cod_entidad." and
						a.cod_tipo_inst=".$cod_tipo_inst." and
						a.cod_inst=".$cod_inst." and
						a.cod_dep=".$cod_dep." and
						a.ano_documento=".$ano." and
						a.numero_documento=".$num_a."
						ORDER BY
						a.cod_presi,
						a.cod_entidad,
						a.cod_tipo_inst,
						a.cod_inst,
						a.cod_dep,
						a.ano_documento,
						a.numero_documento,
						b.cod_sector,
						b.cod_programa,
						b.cod_sub_prog,
						b.cod_proyecto,
						b.cod_activ_obra,
						b.cod_partida,
						b.cod_generica,
						b.cod_especifica,
						b.cod_sub_espec,
						b.cod_auxiliar";
				$datos = $this->cepd01_compromiso_cuerpo->execute($query);
				$this->set('cuerpo',$datos);
					$partidacorriente = 407120;
					$partidacapital   = 407220;
					$tipo_gasto_1     = 0;
					$tipo_gasto_2     = 0;

					for($i=0;$i<count($datos);$i++){
						        //$year_sql       = $datos[$i][0]['ano'];
								$cod_sector     = $datos[$i][0]['cod_sector'];
							    $cod_programa   = $datos[$i][0]['cod_programa'];
							    $cod_sub_prog   = $datos[$i][0]['cod_sub_prog'];
								$cod_proyecto   = $datos[$i][0]['cod_proyecto'];
								$cod_activ_obra = $datos[$i][0]['cod_activ_obra'];
								$cod_partida    = $datos[$i][0]['cod_partida'];
								$cod_generica   = $datos[$i][0]['cod_generica'];
								$cod_especifica = $datos[$i][0]['cod_especifica'];
								$cod_sub_espec  = $datos[$i][0]['cod_sub_espec'];
								$cod_auxiliar   = $datos[$i][0]['cod_auxiliar'];
								$cadena         = $cod_partida.$cod_generica.$cod_especifica.$cod_sub_espec;
								if($partidacorriente == $cadena){ $tipo_gasto_1 = 1;}
								if($partidacapital   == $cadena){ $tipo_gasto_2 = 1;}
					}//fin for

			$sql=$condicion." and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar;
			$busqueda=$this->cfpd05->findAll($sql,array('tipo_presupuesto', 'cod_tipo_gasto'));
         if($busqueda!=null){
				     if($busqueda[0]['cfpd05']['cod_tipo_gasto']==2){
					    $tipo_gasto_1 = 1;
				}else  {$tipo_gasto_2 = 1; }
			}//fin if


					$this->set('tipo_1',$tipo_gasto_1);
					$this->set('tipo_2',$tipo_gasto_2);
				//pr($datos);
			}// fin opcion 2
	  }// fin ir si
}//fin reporte_registro_compromiso_new




////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////





function reporte_relacion_otros_compromisos($ir=null,$ano=null,$cod=null){
	$this->layout="ajax";

	 set_time_limit(0);

	if($ir!=null){
		if($ir=='si'){//si va al formulario
		   $this->set('ano',$this->ano_ejecucion());
		   $this->Session->write('ano_compromiso_cao000',$this->ano_ejecucion());
		   $_SESSION['ano_otroscompromisos'] = $this->ano_ejecucion();
		   $this->set('ir','si');
		}elseif($ir=='no'){//no va al formulario, pero si va al reporte
			$this->layout="pdf";
			$this->set('ir','no');

			$cp  = $this->Session->read('SScodpresi');
			$ce  = $this->Session->read('SScodentidad');
			$cti = $this->Session->read('SScodtipoinst');
			$ci  = $this->Session->read('SScodinst');
			$cd  = $this->Session->read('SScoddep');



			$peticion = $this->data['relacion_otroscompromisos']['tipo_peticion'];
			if(isset($cod)){
				$ano = $ano;
				$cond=" and upper(cod_obra)=upper('$cod')";
			}else{
				$ano = $this->data['relacion_otroscompromisos']['ano'];

				if(isset($this->data['relacion_otroscompromisos']['tipo_peticion']) && $this->data['relacion_otroscompromisos']['tipo_peticion']==1){
					$cond = "";
				}else{
					$cond = "";
				}
			}




			$sql_otros_compromisos="select
						a.cod_dep,
						a.cod_entidad,
						a.cod_tipo_inst,
						a.cod_inst,
						a.cod_dep,
						a.ano_documento,
						a.numero_documento,
						a.condicion_actividad,
						a.cod_tipo_compromiso,
						(select d.denominacion from cepd01_tipo_compromiso d where d.cod_tipo_compromiso=a.cod_tipo_compromiso)::varchar(50) as deno_compromiso,
						a.fecha_documento,
						a.rif,
						a.cedula_identidad,
						a.monto,
						a.beneficiario,
						a.cod_obra,
						(select c.numero_orden_pago from cepd03_ordenpago_cuerpo c where c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.ano_orden_pago=a.ano_orden_pago and c.numero_orden_pago=a.numero_orden_pago)::int4 as numero_orden_pago,
						(select c.fecha_orden_pago from cepd03_ordenpago_cuerpo c where c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.ano_orden_pago=a.ano_orden_pago and c.numero_orden_pago=a.numero_orden_pago)::date as fecha_orden_pago,
						(select c.numero_cheque from cepd03_ordenpago_cuerpo c where c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.ano_orden_pago=a.ano_orden_pago and c.numero_orden_pago=a.numero_orden_pago)::int4 as numero_cheque,
						(select c.fecha_cheque from cepd03_ordenpago_cuerpo c where c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.ano_orden_pago=a.ano_orden_pago and c.numero_orden_pago=a.numero_orden_pago)::date as fecha_cheque
						from cepd01_compromiso_cuerpo a
						where
						a.cod_presi      = '$cp'  and
						a.cod_entidad    = '$ce'  and
						a.cod_tipo_inst  = '$cti' and
						a.cod_inst       = '$ci'  and
						a.cod_dep        = '$cd'  and
						a.ano_documento  = '$ano' ".$cond." and (a.cod_obra!='' and a.cod_obra!='0' and a.cod_obra IS NOT NULL)
						order by a.cod_obra, a.numero_documento";

						$filas_compromiso_cuerpo=$this->cepd01_compromiso_cuerpo->findCount("cod_presi='$cp' and cod_entidad='$ce' and cod_tipo_inst='$cti' and cod_inst='$ci' and cod_dep='$cd' and ano_documento='$ano' ".$cond." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL) ");
						$datos_compromiso_cuerpo=$this->cepd01_compromiso_cuerpo->execute($sql_otros_compromisos);
						$this->set('filas_compromiso_cuerpo',$filas_compromiso_cuerpo-1);
						$this->set('datos_compromiso_cuerpo',$datos_compromiso_cuerpo);
						$this->set('subtituloreporte','');



		}//fin else
	}//fin null


}//relacion_otros_compromisos



function buscar_year_otroscompromisos($ano){
	$this->layout="ajax";
	$this->Session->delete('ano_compromiso_cao000');
	$this->Session->write('ano_compromiso_cao000',$ano);
}

function ventana($var=null){
	$this->layout="ajax";

	if($var==1){

	}else{
		$url                  =  "/reportes_cao000/buscar";
		$width_aux            =  "750px";
		$height_aux           =  "400px";
		$title_aux            =  "Buscar";
		$resizable_aux        =  false;
		$maximizable_aux      =  false;
		$minimizable_aux      =  false;
		$closable_aux         =  false;

		 echo"<script>";
//		 	echo "document.getElementById('peticion_1').checked=false;";
//		 	echo "document.getElementById('peticion_2').checked=false;";
           echo  "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
         echo"</script>";
	}


}



function buscar(){
$this->layout="ajax";



}


function buscar_pista_obras($var1=null, $var2=null, $var3=null){
	$this->layout="ajax";

	$year_buscar         = $this->Session->read('ano_compromiso_cao000');
	$tabla = "cfpd07_obras_cuerpo";
	$campo = "cod_obra";

        $SScoddeporig             =       $this->Session->read('SScoddeporig');
	    $SScoddep                 =       $this->Session->read('SScoddep');
	    $Modulo                   =       $this->Session->read('Modulo');
		$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$SScoddep.' and ano_estimacion='.$year_buscar;
	    //$lista_ob = $this->cfpd07_obras_cuerpo->generateList($condicion.' and ano_estimacion='.$year_buscar.'   and (estimado_presu - ((monto_contratado+ aumento_obras) - disminucion_obras)) != 0     ', ' cod_obra ASC', null, '{n}.cfpd07_obras_cuerpo.cod_obra', '{n}.cfpd07_obras_cuerpo.denominacion');
		//$this->set('lista_codigos_obras', $lista_ob);
		//$this->concatena_sin_cero($lista_ob, 'lista_codigos_obras');

            if($var1!=null && $var1!='_'){
            	$var1 = strtoupper_sisap($var1);
				$this->Session->write('pista', $var1);
            }else{
				$var1  = $this->Session->read('pista');
            }//fin else

            if($var2!=null){
               $pagina = $var2;
            }else{
            	$pagina = 1;
            }
            $this->set('pista', $var1);


                            $condicion = $condicion.'';
                            $ordena = "cod_obra";
                            $Tfilas=$this->$tabla->findCount($condicion." and ".$this->busca_separado(array("cod_obra","denominacion"), $var1));
					        if($Tfilas!=0){

					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->$tabla->findAll($condicion." and ".$this->busca_separado(array("cod_obra","denominacion"), $var1),null,$ordena." ASC",100,$pagina,null);
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




function distinct_beneficiarios_otros_compromisos($var=null){
	$this->layout="ajax";
	$array = array();
	if($var==1){
		$this->set('var',$var);
	}if($var==4){
		$this->set('var',$var);
	}else if($var==2){
		/*$beneficiarios=$this->cepd01_compromiso_cuerpo->execute("select distinct beneficiario from cepd01_compromiso_cuerpo where ".$this->SQLCA()." and ano_documento='".$_SESSION['ano_otroscompromisos']."' ");
		$i=-1;
		foreach($beneficiarios as $x){$i=$i+1;}
		for($j=0; $j<=$i; $j++){
		$array[$beneficiarios[$j][0]['beneficiario']] = $beneficiarios[$j][0]['beneficiario'];
		}*/
		$this->set('beneficiarios',$array);
		$this->set('var',$var);
	}else if($var==3){
		$compromisos=$this->cepd01_compromiso_cuerpo->execute("select distinct cod_tipo_compromiso from cepd01_compromiso_cuerpo where ".$this->SQLCA()." and ano_documento='".$_SESSION['ano_otroscompromisos']."' ");
		$tipocompromiso=$this->cepd01_tipo_compromiso->findAll();
		$i=-1;
		foreach($compromisos as $x){$i=$i+1;}
		for($j=0; $j<=$i; $j++){
			foreach($tipocompromiso as $a){
				if($a['cepd01_tipo_compromiso']['cod_tipo_compromiso']==$compromisos[$j][0]['cod_tipo_compromiso']){
					$array[$compromisos[$j][0]['cod_tipo_compromiso']] = $a['cepd01_tipo_compromiso']['denominacion'];
				}
			}
		}
		$this->set('tipocompromiso',$array);
		$this->set('var',$var);
	}




echo "<script>";
  echo "if(document.getElementById('datos-consulta')){document.getElementById('datos-consulta').innerHTML='';}";
echo "</script>";



}//distinct_beneficiarios_ordenes_pago


function relacion_obras_administradas_directamente ($ir=null,$ano=null,$cod=null) {
   $this->layout="ajax";
   if($ir!=null){
		if($ir=='si'){//si va al formulario
		   $this->set('ano',$this->ano_ejecucion());
		   $this->Session->write('ano_compromiso_cao000',$this->ano_ejecucion());
		   $_SESSION['ano_otroscompromisos'] = $this->ano_ejecucion();
		   $this->set('ir','si');
		}elseif($ir=='no'){//no va al formulario, pero si va al reporte
			$this->layout="pdf";
			$this->set('ir','no');
			$cp  = $this->Session->read('SScodpresi');
			$ce  = $this->Session->read('SScodentidad');
			$cti = $this->Session->read('SScodtipoinst');
			$ci  = $this->Session->read('SScodinst');
			$cd  = $this->Session->read('SScoddep');
			$peticion = $this->data['relacion_otroscompromisos']['tipo_peticion'];
			$_SESSION['desde_cao'] = '';
            $_SESSION['hasta_cao'] = '';
			if(isset($cod)){
				$ano = $ano;
				$cond=" and upper(cod_obra)=upper('$cod')";
			}else{
				$ano = $this->data['relacion_otroscompromisos']['ano'];
				if(isset($this->data['relacion_otroscompromisos']['tipo_peticion']) && $this->data['relacion_otroscompromisos']['tipo_peticion']==1){
					$cond = "";
				}else{
					$cond = "";
				}
			}
            $modelo ="v_cao_reporte_1";
			if(isset($this->data['relacion_otroscompromisos']['tipo_peticion']) && $this->data['relacion_otroscompromisos']['tipo_peticion']==3){

					  if(isset($this->data['relacion_otroscompromisos']['desde'])){
					  	  $desde = cambiar_formato_fecha($this->data['relacion_otroscompromisos']['desde']);
					  	  $hasta = cambiar_formato_fecha($this->data['relacion_otroscompromisos']['hasta']);
                          $cond_fecha = "  and fecha_documento between '$desde' and '$hasta'";
                          $_SESSION['desde_cao'] = $this->data['relacion_otroscompromisos']['desde'];
                          $_SESSION['hasta_cao'] = $this->data['relacion_otroscompromisos']['hasta'];
                      }else{
                      	$cond_fecha = "";
                      }
                      $modelo ="v_cao_reporte_1_fecha";
				}else{
					$cond_fecha = "";
					$modelo ="v_cao_reporte_1";
				}
            $this->set('tipo_peticion_reporte',$peticion);

            $data_reporte = $this->$modelo->findAll($this->SQLCA()." ".$cond." and ano_estimacion=$ano and monto_contratado_cuerpo!=0 " .$cond_fecha);
            $this->set('modelo_reporte',$modelo);
            $this->set('data_reporte',$data_reporte);




		}//fin else
	}//fin null

   //v_cao_reporte_1

}//fin funcion relacion_obras_administradas_directamente



function relacion_obras_administradas_directamente2 ($ir=null,$ano=null,$cod=null) {
   $this->layout="ajax";
   if($ir!=null){
		if($ir=='si'){//si va al formulario
		   $this->set('ano',$this->ano_ejecucion());
		   $this->Session->write('ano_compromiso_cao000',$this->ano_ejecucion());
		   $_SESSION['ano_otroscompromisos'] = $this->ano_ejecucion();
		   $this->set('ir','si');
		}elseif($ir=='no'){//no va al formulario, pero si va al reporte
			$this->layout="pdf";
			$this->set('ir','no');
			$cp  = $this->Session->read('SScodpresi');
			$ce  = $this->Session->read('SScodentidad');
			$cti = $this->Session->read('SScodtipoinst');
			$ci  = $this->Session->read('SScodinst');
			$cd  = $this->Session->read('SScoddep');
			$peticion = $this->data['relacion_otroscompromisos']['tipo_peticion'];
			$_SESSION['desde_cao'] = '';
            $_SESSION['hasta_cao'] = '';
			if(isset($cod)){
				$ano = $ano;
				$cond=" and upper(cod_obra)=upper('$cod')";
			}else{
				$ano = $this->data['relacion_otroscompromisos']['ano'];
				if(isset($this->data['relacion_otroscompromisos']['tipo_peticion']) && $this->data['relacion_otroscompromisos']['tipo_peticion']==1){
					$cond = "";
				}else{
					$cond = "";
				}
			}

             if(isset($this->data['relacion_otroscompromisos']['tipo_peticion']) && $this->data['relacion_otroscompromisos']['tipo_peticion']==3){

					  if(isset($this->data['relacion_otroscompromisos']['desde'])){
					  	  $desde = cambiar_formato_fecha($this->data['relacion_otroscompromisos']['desde']);
					  	  $hasta = cambiar_formato_fecha($this->data['relacion_otroscompromisos']['hasta']);
                          $cond_fecha = "  and fecha_documento between '$desde' and '$hasta'";
                          $_SESSION['desde_cao'] = $this->data['relacion_otroscompromisos']['desde'];
                          $_SESSION['hasta_cao'] = $this->data['relacion_otroscompromisos']['hasta'];
                      }else{
                      	$cond_fecha = "";
                      }
				}else{
					$cond_fecha = "";
				}
            $this->set('tipo_peticion_reporte',$peticion);



            $data_reporte = $this->v_cao_reporte_2->findAll($this->SQLCA()." ".$cond." and ano_estimacion=$ano ".$cond_fecha);

            $this->set('data_reporte',$data_reporte);




		}//fin else
	}//fin null

   //v_cao_reporte_1

}//fin funcion relacion_obras_administradas_directamente2


function relacion_obras_administradas_directamente3 ($ir=null,$ano=null,$cod=null) {
   $this->layout="ajax";
   if($ir!=null){
		if($ir=='si'){//si va al formulario
		   $this->set('ano',$this->ano_ejecucion());
		   $this->Session->write('ano_compromiso_cao000',$this->ano_ejecucion());
		   $_SESSION['ano_otroscompromisos'] = $this->ano_ejecucion();
		   $this->set('ir','si');
		}elseif($ir=='no'){//no va al formulario, pero si va al reporte
			$this->layout="pdf";
			$this->set('ir','no');
			$cp  = $this->Session->read('SScodpresi');
			$ce  = $this->Session->read('SScodentidad');
			$cti = $this->Session->read('SScodtipoinst');
			$ci  = $this->Session->read('SScodinst');
			$cd  = $this->Session->read('SScoddep');
			$peticion = $this->data['relacion_otroscompromisos']['tipo_peticion'];
			$_SESSION['desde_cao'] = '';
            $_SESSION['hasta_cao'] = '';
			if(isset($cod)){
				$ano = $ano;
				$cond=" and upper(cod_obra)=upper('$cod')";
			}else{
				$ano = $this->data['relacion_otroscompromisos']['ano'];
				if(isset($this->data['relacion_otroscompromisos']['tipo_peticion']) && $this->data['relacion_otroscompromisos']['tipo_peticion']==1){
					$cond = "";
				}else{
					$cond = "";
				}
			}

            $modelo ="v_cao_reporte_detallado";
			if(isset($this->data['relacion_otroscompromisos']['tipo_peticion']) && $this->data['relacion_otroscompromisos']['tipo_peticion']==3){

					  if(isset($this->data['relacion_otroscompromisos']['desde'])){
					  	  $desde = cambiar_formato_fecha($this->data['relacion_otroscompromisos']['desde']);
					  	  $hasta = cambiar_formato_fecha($this->data['relacion_otroscompromisos']['hasta']);
                          $cond_fecha = "  and fecha_documento between '$desde' and '$hasta'";
                          $_SESSION['desde_cao'] = $this->data['relacion_otroscompromisos']['desde'];
                          $_SESSION['hasta_cao'] = $this->data['relacion_otroscompromisos']['hasta'];
                      }else{
                      	$cond_fecha = "";
                      }
                      $modelo ="v_cao_reporte_detallado_fecha";
				}else{
					$cond_fecha = "";
					$modelo ="v_cao_reporte_detallado";
				}
            $this->set('tipo_peticion_reporte',$peticion);

            $data_reporte = $this->$modelo->findAll($this->SQLCA()." ".$cond." and ano_estimacion=$ano " .$cond_fecha);
            $this->set('modelo_reporte',$modelo);
            $this->set('data_reporte',$data_reporte);




		}//fin else
	}//fin null

   //v_cao_reporte_1

}//fin funcion relacion_obras_administradas_directamente3


function ventana2($var=null){
	$this->layout="ajax";

	if($var==1){
		    echo"<script>";
		 	echo "	document.getElementById('periodo_fechas_seleccion').style.display='none';";
            echo"</script>";
	}else if($var==3 || $var=='3'){
            echo"<script>";
		 	echo "	document.getElementById('periodo_fechas_seleccion').style.display='block';";
            echo"</script>";

	}else{
		$url                  =  "/reportes_cao000/buscar_2";
		$width_aux            =  "750px";
		$height_aux           =  "400px";
		$title_aux            =  "Buscar";
		$resizable_aux        =  false;
		$maximizable_aux      =  false;
		$minimizable_aux      =  false;
		$closable_aux         =  false;

		 echo"<script>";
//		 	echo "document.getElementById('peticion_1').checked=false;";
//		 	echo "document.getElementById('peticion_2').checked=false;";
           echo  "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
         echo"</script>";
         echo"<script>";
		 	echo "	document.getElementById('periodo_fechas_seleccion').style.display='none';";
            echo"</script>";
	}


}


function buscar_2(){
	$this->layout="ajax";
}

function buscar_pista_obras2($var1=null, $var2=null, $var3=null){
	$this->layout="ajax";
	$year_buscar         = $this->Session->read('ano_compromiso_cao000');
	$tabla = "cfpd07_obras_cuerpo";
	$campo = "cod_obra";

        $SScoddeporig             =       $this->Session->read('SScoddeporig');
	    $SScoddep                 =       $this->Session->read('SScoddep');
	    $Modulo                   =       $this->Session->read('Modulo');
		$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$SScoddep.' and ano_estimacion='.$year_buscar;
        if($var1!=null){
        	$var1 = strtoupper_sisap($var1);
			$this->Session->write('pista', $var1);
        }else{
					$var1  = $this->Session->read('pista');
        }//fin else
                            $condicion = $condicion.'';
                            $ordena = "cod_obra";
                            $Tfilas=$this->$tabla->findCount($condicion." and ".$this->busca_separado(array("cod_obra","denominacion"), $var1));
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->$tabla->findAll($condicion." and ".$this->busca_separado(array("cod_obra","denominacion"), $var1),null,$ordena." ASC",100,1,null);
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


function ventana3($var=null){
	$this->layout="ajax";

	if($var==1){
		    echo"<script>";
		 	echo "	document.getElementById('periodo_fechas_seleccion').style.display='none';";
            echo"</script>";
	}else if($var==3 || $var=='3'){
            echo"<script>";
		 	echo "	document.getElementById('periodo_fechas_seleccion').style.display='block';";
            echo"</script>";

	}else{
		$url                  =  "/reportes_cao000/buscar_3";
		$width_aux            =  "750px";
		$height_aux           =  "400px";
		$title_aux            =  "Buscar";
		$resizable_aux        =  false;
		$maximizable_aux      =  false;
		$minimizable_aux      =  false;
		$closable_aux         =  false;

		 echo"<script>";
//		 	echo "document.getElementById('peticion_1').checked=false;";
//		 	echo "document.getElementById('peticion_2').checked=false;";
           echo  "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
         echo"</script>";
         echo"<script>";
		 	echo "	document.getElementById('periodo_fechas_seleccion').style.display='none';";
            echo"</script>";
	}


}


function buscar_3(){
	$this->layout="ajax";
}

function buscar_pista_obras3($var1=null, $var2=null, $var3=null){
	$this->layout="ajax";
	$year_buscar         = $this->Session->read('ano_compromiso_cao000');
	$tabla = "cfpd07_obras_cuerpo";
	$campo = "cod_obra";

        $SScoddeporig             =       $this->Session->read('SScoddeporig');
	    $SScoddep                 =       $this->Session->read('SScoddep');
	    $Modulo                   =       $this->Session->read('Modulo');
		$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$SScoddep.' and ano_estimacion='.$year_buscar;
        if($var1!=null){
        	$var1 = strtoupper_sisap($var1);
			$this->Session->write('pista', $var1);
        }else{
					$var1  = $this->Session->read('pista');
        }//fin else
                            $condicion = $condicion.'';
                            $ordena = "cod_obra";
                            $Tfilas=$this->$tabla->findCount($condicion." and ".$this->busca_separado(array("cod_obra","denominacion"), $var1));
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->$tabla->findAll($condicion." and ".$this->busca_separado(array("cod_obra","denominacion"), $var1),null,$ordena." ASC",100,1,null);
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




function ventana4($var=null){
	$this->layout="ajax";

	if($var==1){
		    echo"<script>";
		 	echo "	document.getElementById('periodo_fechas_seleccion').style.display='none';";
            echo"</script>";
	}else if($var==3 || $var=='3'){
            echo"<script>";
		 	echo "	document.getElementById('periodo_fechas_seleccion').style.display='block';";
            echo"</script>";

	}else{
		$url                  =  "/reportes_cao000/buscar_4";
		$width_aux            =  "750px";
		$height_aux           =  "400px";
		$title_aux            =  "Buscar";
		$resizable_aux        =  false;
		$maximizable_aux      =  false;
		$minimizable_aux      =  false;
		$closable_aux         =  false;

		 echo"<script>";
//		 	echo "document.getElementById('peticion_1').checked=false;";
//		 	echo "document.getElementById('peticion_2').checked=false;";
           echo  "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
         echo"</script>";
         echo"<script>";
		 	echo "	document.getElementById('periodo_fechas_seleccion').style.display='none';";
            echo"</script>";
	}


}//fin ventana4


function buscar_4(){
	$this->layout="ajax";
}//fin buscar_4

function buscar_pista_obras4($var1=null, $var2=null, $var3=null){
	$this->layout="ajax";
	$year_buscar         = $this->Session->read('ano_compromiso_cao000');
	$tabla = "cfpd07_obras_cuerpo";
	$campo = "cod_obra";

        $SScoddeporig             =       $this->Session->read('SScoddeporig');
	    $SScoddep                 =       $this->Session->read('SScoddep');
	    $Modulo                   =       $this->Session->read('Modulo');
		$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$SScoddep.' and ano_estimacion='.$year_buscar;
        if($var1!=null){
        	$var1 = strtoupper_sisap($var1);
			$this->Session->write('pista', $var1);
        }else{
					$var1  = $this->Session->read('pista');
        }//fin else
                            $condicion = $condicion.'';
                            $ordena = "cod_obra";
                            $Tfilas=$this->$tabla->findCount($condicion." and ".$this->busca_separado(array("cod_obra","denominacion"), $var1));
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->$tabla->findAll($condicion." and ".$this->busca_separado(array("cod_obra","denominacion"), $var1),null,$ordena." ASC",100,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }


					$this->set("tabla", $tabla);
					$this->set("campo", $campo);


}//fin buscar_pista_obras4




}//fin class
?>