<?php

 class Cnmp99OrdenPagosController extends AppController{


 	var $uses = array('ccfd04_cierre_mes','Cnmd01','cnmd09_numero_nominas_canceladas','cnmd07_calculo_aguinaldos','cnmd07_calculo_bonovaca',
                      'cnmd09_lunes_ejercicio','cnmd09_incidencia_sueldo_sugerido','v_cnmd07_transacciones_actuales_frecuencias2',
                      'trasacciones_no_conectadas','cargos_anos_diferentes','cstd02_cuentas_bancarias',
                      'nomina_beneficiario_partidas_p2','nomina_partidas_denot_p1','nomina_beneficiario_partidas_p2_2','nomina_partidas_denot_p1_2',
                      'v_cfpd05_denominaciones','cfpd21','cfpd21_numero_asiento_compromiso','v_cnmp99_historica_orden_pago_perma',
                      'cepd01_compromiso_poremitir','cepd01_compromiso_beneficiario_cedula','cepd01_compromiso_beneficiario_rif',
                      'cugd03_acta_anulacion_numero','cugd03_acta_anulacion_cuerpo','v_cfpd05_disponibilidad','cugd04',
                      'cpcd02','cscd01_catalogo','cepd01_tipo_compromiso','cepd01_compromiso_cuerpo','cepd01_compromiso_numero',
                      'cepd01_compromiso_partidas','cfpd05','cfpd05_requerimiento','cfpd05_2032_tmp','cfpd05_auxiliar',
                      'cfpp05auxiliar','cfpd02_sector', 'cfpd02_programa', 'cfpd02_sub_prog', 'cfpd02_proyecto', 'cfpd02_activ_obra',
                      'arrd05','cfpd01_ano_grupo', 'cfpd01_ano_partida', 'cfpd01_ano_generica', 'cfpd01_ano_especifica', 'cfpd01_ano_sub_espec',
                      'cfpd01_ano_auxiliar', 'ccfd03_instalacion','cugd02_direccionsuperior', 'cugd02_coordinacion', 'cugd02_secretaria',
                      'cepd03_ordenpago_cuerpo', 'cugd02_direccion', 'v_cscd01_catalogo_deno_und','cstd01_entidades_bancarias',
                      'ccfd10_descripcion', 'ccfd10_detalles', 'ccfd02', 'ccfd05_numero_asiento',
                      'cnmd09_ubicacion_direccion_personal','cuenta_banco_ficha','cuenta_banco_transacciones',
                      'cfpd22_numero_asiento_causado','cfpd22','cepd03_ordenpago_poremitir','v_cepd03_ordenpago_compromiso','cpcd02',
                      'cepd01_tipo_compromiso','cepd03_tipo_documento','ccfd03_instalacion','cepd03_ordenpago_numero','cepd03_ordenpago_cuerpo',
                      'cepd03_ordenpago_partidas','cepd03_ordenpago_tipopago','cepd03_ordenpago_facturas','cstd01_entidades_bancarias',
                      'cstd01_sucursales_bancarias','cnmd09_bancos_cancelan_nominas','ccfd04_cuentas_enlace', 'cstd03_cheque_cuerpo',
                      'cstd03_movimientos_manuales','cstd09_notadebito_cuerpo_pago','cscd04_ordencompra_encabezado','cobd01_contratoobras_cuerpo',
                      'cepd02_contratoservicio_cuerpo','cscd04_ordcom_modificacion_cuerpo','cobd01_co_modificacion_cuerpo',
                      'cfpd30_reintegro_cuerpo','ccfd02','cnmd03_transacciones','costo_presupuestario_p2');

 	var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf','Form');


function checkSession(){
	if (!$this->Session->check('Usuario')){
			$this->redirect('/salir/');
			exit();
	}else{
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
function index () {
    $this->layout="ajax";
    if($this->verifica_SS(1)==1 && $this->verifica_SS(2)==11 && $this->verifica_SS(3)==30 && $this->verifica_SS(4)==11){
        $status_nomina="99";
     }else{
    	$status_nomina="2";
     }
    $lista = $this->Cnmd01->generateList($this->SQLCA()." and status_nomina in ($status_nomina)", 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($this->Cnmd01->findCount($this->SQLCA()." and status_nomina in ($status_nomina)")!=0){
		$this->concatenaN($lista, 'tipo_nomina');
	}else{
		$this->set('tipo_nomina', array());
	}
}//fin index

function deno_nomina ($cod_tipo_nomina=null) {
     $this->layout="ajax";
    if (isset($cod_tipo_nomina)) {
        $lista = $this->Cnmd01->findAll($this->SQLCA()." and status_nomina in (2) and cod_tipo_nomina=".$cod_tipo_nomina,'cod_tipo_nomina, denominacion,numero_nomina,periodo_desde,periodo_hasta,correspondiente,cantidad_pagos,frecuencia_pago,modalidad_pago');

    		if($this->cnmd09_ubicacion_direccion_personal->findCount($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina)!=0){
    				if($this->Cnmd01->findCount($this->SQLCA()." and status_nomina in (2) and cod_tipo_nomina=".$cod_tipo_nomina)!=0){
    					$this->set( 'nomina',$lista);
    				}else{
    					$this->set('nomina', array());
    				}
    		}else{
    				$this->set('errorMessage','Por favor, registre la UBICACIÓN ADMINISTRATIVA DE LA DIRECCIÓN DE PERSONAL');
    		}
		}else{
            $this->set('errorMessage','Por favor, registre la UBICACIÓN ADMINISTRATIVA DE LA DIRECCIÓN DE PERSONAL');
		}

    $cuenta_proc=$this->Cnmd01->findCount($this->SQLCA()." and ejecucion_orden_pago=1 and status_nomina=2 and cod_tipo_nomina!=$cod_tipo_nomina ");

    if ($cuenta_proc!=0){
    	$this->set('errorMessage','NO PUEDE CORRER ESTE PROCESO....HASTA TERMINAR EL PROCESO EN CURSO');
    		echo "<script>
    				document.getElementById('bt_procesar').disabled=true;
    			</script>";
            // document.getElementById('bt_reporte').disabled=true;
    }


	}// fin funcion deno_nomina


function procesar () {
    set_time_limit(0);
  $this->layout="ajax";
  $cod_presi     = $this->Session->read('SScodpresi');
  $cod_entidad   = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst      = $this->Session->read('SScodinst');
  $cod_dep       = $this->Session->read('SScoddep');
  $x=true;

  if(!empty($this->data["cnmp99_prenomina"]["correspondientes"])){
    $datos=$this->data["cnmp99_prenomina"];
    $cod_tipo_nomina  = $datos["cod_tipo_nomina"];
    $desde_periodo    = $datos["desde_periodo"];
    $correspondientes = $datos["correspondientes"];
    $numero_nomina    = $datos["numero_nomina"];
    $modalidad_pago   = $datos["modalidad"];  
    $this->Session->write('cod_tipo_nomina',$cod_tipo_nomina);
    $this->Session->write('numero_nomina',$numero_nomina);

    $sql_update_cnmd01="UPDATE cnmd01 SET ejecucion_orden_pago=1 WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina;
    $this->Cnmd01->execute($sql_update_cnmd01);

    //VERIFICACION QUE LAS PARTIDAS NO ESTEN EN NEGATIVO
	$presupuesto_negativo=$this->costo_presupuestario_p2->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and ano=".divide_fecha($desde_periodo,'ANO'));
	$negativo = 0;
	/*foreach ($presupuesto_negativo as $pnegativo) {
		if($pnegativo["costo_presupuestario_p2"]["diferencia"]<0)
		{
			$negativo++;
		}
	}*/

	$nomina = $this->Cnmd01->findCount($this->SQLCA()." and status_nomina=2 and cod_tipo_nomina=$cod_tipo_nomina ");

    if($nomina!=0 && $negativo==0){
      $uno=$this->cnmd09_bancos_cancelan_nominas->findCount($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina."");
      $dos=$this->cuenta_banco_transacciones->findCount($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cantidad = 0");

               // ELIMINA LAS ORDENES DE PAGO GUARDADAS EN LA NÓMINA ANTERIOR
      $elimina_ante_tempo=$this->Cnmd01->execute("DELETE FROM cnmd99_orden_pago_prenomina WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
      $elimina_ante_perma=$this->Cnmd01->execute("DELETE FROM cnmd99_orden_pago_prenomina_perma WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina."and numero_nomina=".$numero_nomina);

      if($dos!=0){
        $this->set('banco',array());
        $this->set('transacciones',$this->cuenta_banco_transacciones->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cantidad = 0"));
        $this->set('errorMessage','Transacciones no creadas en Bancos que cancelan fondos de terceros');
      }else{
        if($uno==0){
          $this->set('banco',$this->cuenta_banco_ficha->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cantidad = 0"));
          $this->set('transacciones',array());
          $this->set('errorMessage','Bancos que cancelan nóminas sin llenar');
        }else{
          $aleatorio=rand();
          $cantidad_conex1 = $this->Cnmd01->execute("SELECT count(*) as c FROM cantidad_cnmd05_cfpd05 where ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cantidad_cfpd05=0 and (cod_ficha!=0 or cod_ficha is not null)  and $aleatorio=$aleatorio");

          if($cantidad_conex1[0][0]['c']==0 || $cantidad_conex1[0][0]['c']=='0'){

            $cantidad_conex2 = $this->Cnmd01->execute("SELECT count(*) as c FROM cantidad_conexion_cfpd05 where ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cantidad_cfpd05=0 and (cod_cargo!=0 or cod_cargo is not null)  and $aleatorio=$aleatorio");

            if($cantidad_conex2[0][0]['c']==0 || $cantidad_conex2[0][0]['c']=='0'){
                                
              $ubicacion=$this->cnmd09_ubicacion_direccion_personal->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);

              $grupo_beneficiarios   = $this->nomina_beneficiario_partidas_p2->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." GROUP BY cod_tipo_transaccion, cod_transaccion, enlace_contable, rif_cedula, beneficiario, cedula_identidad_autorizado, autorizado_a_cobrar, banco, cuenta",'cod_tipo_transaccion, cod_transaccion, enlace_contable, rif_cedula, beneficiario, cedula_identidad_autorizado, autorizado_a_cobrar, banco, cuenta','cod_tipo_transaccion, cod_transaccion, enlace_contable, rif_cedula, beneficiario, cedula_identidad_autorizado, autorizado_a_cobrar, banco, cuenta ASC');

              $data_partidas         = $this->nomina_beneficiario_partidas_p2->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina,'','cod_tipo_transaccion, cod_transaccion, enlace_contable, rif_cedula, beneficiario, cedula_identidad_autorizado, autorizado_a_cobrar, banco, cuenta, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra,cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC');

              $grupo_beneficiarios_2 = $this->nomina_beneficiario_partidas_p2_2->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." GROUP BY beneficiario, rif_cedula",'beneficiario, rif_cedula','beneficiario, rif_cedula ASC');

              $data_partidas_2       = $this->nomina_beneficiario_partidas_p2_2->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina,'','cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra,cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC');

              $data_concepto=$this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina,'cod_tipo_nomina,correspondiente,denominacion,denominacion_devengado,numero_recibo,ano_desde,ano_hasta,codigo_transaccion,periodo_desde,periodo_hasta,numero_nomina');

              $tipo_compromiso = 5;
              $tipo_pago = 4;
              $tipo_recurso = 1;//1.- Ordinario 2.- Coordinado 3.- Fides 4.- Laee 5.- Ingresos extraordinarios
              $resultado = array();
              $x_saber = 0;
              $verifica_guardado = 0;
              $secuencia=0;
              $ano = divide_fecha($data_concepto[0]['Cnmd01']['periodo_desde'],'ANO');
              $ano_compromiso = divide_fecha($data_concepto[0]['Cnmd01']['periodo_desde'],'ANO');

              // 
              // aqui deberia seleccionar el numero de compromiso y el numero de orden de pago
              //
              // subir antes del foreach comentado arriba
              // Obteniendo el numero de compromiso
              $aleatorio=rand();
              
              $max=$this->cepd01_compromiso_numero->execute("SELECT numero_compromiso FROM cepd01_compromiso_numero WHERE ".$this->SQLCA()." and ano_compromiso=".$ano_compromiso." and situacion=1 and $aleatorio=$aleatorio ORDER BY numero_compromiso ASC LIMIT 1");

              if($max!=null){
                  $numero_compromiso=$max[0][0]["numero_compromiso"];
                  $this->cepd01_compromiso_numero->execute("UPDATE  cepd01_compromiso_numero SET situacion=3 WHERE ".$this->SQLCA()." and numero_compromiso=".$numero_compromiso." and ano_compromiso=".$ano);
              }
              //hasta aqui
              //Obteniendo la orden de pago
              $max=$this->cepd03_ordenpago_numero->execute("SELECT numero_orden_pago FROM cepd03_ordenpago_numero WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_compromiso." and situacion=1 and $aleatorio=$aleatorio ORDER BY numero_orden_pago ASC LIMIT 1");

              if($max!=null){
                  $numero_orden_pago=$max[0][0]["numero_orden_pago"];
                  $this->cepd03_ordenpago_numero->execute("UPDATE  cepd03_ordenpago_numero SET situacion=3 WHERE ".$this->SQLCA()." and numero_orden_pago=".$numero_orden_pago." and ano_orden_pago=".$ano);
              }

              //Fin de obtener orden de pago
              foreach($grupo_beneficiarios as $gb1){
                $banco=$gb1['nomina_beneficiario_partidas_p2']['banco'];
                $cuenta=$gb1['nomina_beneficiario_partidas_p2']['cuenta'];
                $cod_tipo_transa=$gb1['nomina_beneficiario_partidas_p2']['cod_tipo_transaccion'];
                $cod_transaccion=$gb1['nomina_beneficiario_partidas_p2']['cod_transaccion'];
                $enlace_contable=$gb1['nomina_beneficiario_partidas_p2']['enlace_contable'];
                $cedula_identidad_autorizado=$gb1['nomina_beneficiario_partidas_p2']['cedula_identidad_autorizado'];
                $autorizado_a_cobrar=$gb1['nomina_beneficiario_partidas_p2']['autorizado_a_cobrar'];

                $denominacion[1]="CANCELACIÓN NETA DE: ".strtoupper($data_concepto[0]['Cnmd01']['denominacion']).", ".strtoupper($data_concepto[0]['Cnmd01']['denominacion_devengado'])." Y DEMÁS REMUNERACIONES, CORRESPONDIENTE A: ".strtoupper($data_concepto[0]['Cnmd01']['correspondiente'])." EN EL LAPSO COMPRENDIDO DESDE ".cambiar_formato_fecha($data_concepto[0]['Cnmd01']['periodo_desde'])." HASTA ".cambiar_formato_fecha($data_concepto[0]['Cnmd01']['periodo_hasta']).". PARA SER PAGADO POR ";

                if($modalidad_pago==1){
                  $denominacion[1]=$denominacion[1]."SISTEMA NACIONAL PATRIA.";
                }else{
                  $denominacion[1]=$denominacion[1].$cuenta." - ".$banco.".";
                }

                if($cod_tipo_transa==2 && $cod_transaccion>=400 && $cod_transaccion<=499){
                  $tipo_aporte_deduccion=" DEL APORTE PATRONAL";
                }else{
                  $tipo_aporte_deduccion=" DE LA DEDUCCIÓN";
                }


                $denominacion[2]="FONDO DE TERCEROS DE ".strtoupper($data_concepto[0]['Cnmd01']['denominacion']).", CORRESPONDIENTE A: ".$data_concepto[0]['Cnmd01']['correspondiente']." EN EL LAPSO COMPRENDIDO DESDE ".cambiar_formato_fecha($data_concepto[0]['Cnmd01']['periodo_desde'])." HASTA ".cambiar_formato_fecha($data_concepto[0]['Cnmd01']['periodo_hasta']).".".$tipo_aporte_deduccion." SIGUENTE: ";

                //$ano_compromiso = divide_fecha($data_concepto[0]['Cnmd01']['periodo_desde'],'ANO');
                  
                // AQUI VA LA SECUENCIA DEL NUEVO CAMPO
                // EJEMPLO 
                
                if($secuencia==0){
                  $numero_compromiso_secuencia = $numero_compromiso;
                  $numero_orden_pago_secuencia = $numero_orden_pago;
                  $secuencia++;
                }else{
                  $numero_compromiso_secuencia = $numero_compromiso . '-' . $secuencia;
                  $numero_orden_pago_secuencia = $numero_orden_pago . "-" . $secuencia;
                  $secuencia++;
                }

                // subir antes del foreach comentado arriba
                  /*$max=$this->cepd01_compromiso_numero->execute("SELECT numero_compromiso FROM cepd01_compromiso_numero WHERE ".$this->SQLCA()." and ano_compromiso=".$ano_compromiso." and situacion=1 and $aleatorio=$aleatorio ORDER BY numero_compromiso ASC LIMIT 1");

                  if($max!=null){
                    $numero_compromiso=$max[0][0]["numero_compromiso"];
                    $this->cepd01_compromiso_numero->execute("UPDATE  cepd01_compromiso_numero SET situacion=3 WHERE ".$this->SQLCA()." and numero_compromiso=".$numero_compromiso." and ano_compromiso=".$ano_compromiso);
                  }*/
                //hasta aqui

                $partidas=array();
                $tipo_concepto_enviar=0;
                $tmonto_rc=0;
                $concepto_p2= array();

                foreach($data_partidas as $da_pa){
                  
                  $d_fila = $da_pa["nomina_beneficiario_partidas_p2"];
                  if($d_fila['cod_tipo_transaccion']==$gb1['nomina_beneficiario_partidas_p2']['cod_tipo_transaccion'] && $d_fila['cod_transaccion']==$gb1['nomina_beneficiario_partidas_p2']['cod_transaccion'] && $d_fila['enlace_contable']==$gb1['nomina_beneficiario_partidas_p2']['enlace_contable'] && $d_fila['rif_cedula']==$gb1['nomina_beneficiario_partidas_p2']['rif_cedula'] && $d_fila['beneficiario']==$gb1['nomina_beneficiario_partidas_p2']['beneficiario'] && $d_fila['banco']==$gb1['nomina_beneficiario_partidas_p2']['banco'] && $d_fila['cuenta']==$gb1['nomina_beneficiario_partidas_p2']['cuenta']){
                    $partidas[]=array ($ano_compromiso,$d_fila['cod_sector'],$d_fila['cod_programa'],$d_fila['cod_sub_prog'],$d_fila['cod_proyecto'],$d_fila['cod_activ_obra'],$d_fila['cod_partida'],$d_fila['cod_generica'],$d_fila['cod_especifica'],$d_fila['cod_sub_espec'],$d_fila['cod_auxiliar'],$d_fila['monto']);
                    $tipo_concepto_enviar=$d_fila['tipo_denominacion'];
                    $tmonto_rc+=$d_fila['monto'];
                    $rif_cedula = $d_fila['rif_cedula'];
                    $personalidad = $d_fila['personalidad'];
                    $beneficiario = $d_fila['beneficiario'];
                  }
                }//partidas

                $denominacion_transaccion_concepto="";

                if($tipo_concepto_enviar==2){
                  $detrasac=$this->cnmd03_transacciones->findAll("cod_tipo_transaccion=".$cod_tipo_transa." and cod_transaccion=".$cod_transaccion);
                  foreach($detrasac as $dtc){
                    $denominacion_transaccion_concepto=mascara($dtc['cnmd03_transacciones']['cod_transaccion'],3)." - ".$dtc['cnmd03_transacciones']['denominacion'];
                    $denominacion[2]=$denominacion[2].$denominacion_transaccion_concepto;
                  }
                }
                if($modalidad_pago==1){
                  $denominacion[2]=$denominacion[2].". PARA SER PAGADO POR SISTEMA NACIONAL PATRIA.";
                }

                $concepto=$denominacion[$tipo_concepto_enviar];
                $cod_dir_superior = $ubicacion[0]['cnmd09_ubicacion_direccion_personal']['cod_dir_superior'];
                $cod_coordinacion = $ubicacion[0]['cnmd09_ubicacion_direccion_personal']['cod_coordinacion'];
                $cod_secretaria   = $ubicacion[0]['cnmd09_ubicacion_direccion_personal']['cod_secretaria'];
                $cod_direccion    = $ubicacion[0]['cnmd09_ubicacion_direccion_personal']['cod_direccion'];


                // en esta funcion agregar un campo adicional que es numero_compromiso_secuencia
                $result = $this->_guardar_rc($ano_compromiso,$numero_compromiso,$tipo_compromiso,$tipo_recurso,$rif_cedula,$personalidad,$concepto,$beneficiario,$cod_dir_superior,$cod_coordinacion,$cod_secretaria,$cod_direccion,$tmonto_rc,$partidas,$cedula_identidad_autorizado,$autorizado_a_cobrar,$numero_compromiso_secuencia);
                //REVISAR LUEGO
                if($result==true){
                  $result_op = $this->_guardar_op ($ano_compromiso,$numero_compromiso,$enlace_contable,$cedula_identidad_autorizado,$autorizado_a_cobrar, $numero_compromiso_secuencia,$numero_orden_pago,$numero_orden_pago_secuencia);
                }else{
                  $result_op = array('numero_orden_pago'=>0,'status'=>false,'error'=>'0');
                }

                $resultado[]=array('numero_compromiso'=>$numero_compromiso,'beneficiario'=>$beneficiario,'status'=>$result==true?'Guardado':'No guardado','numero_orden_pago'=>$result_op['numero_orden_pago'],'status_op'=>$result_op['status']==true?'Guardado':'No guardado-0-'.$result_op['error']);

              }//beneficiarios distintos

              foreach($grupo_beneficiarios_2 as $gb1){
                $ano_compromiso = divide_fecha($data_concepto[0]['Cnmd01']['periodo_desde'],'ANO');
                //$aleatorio=rand();

                // AQUI VA LA SECUENCIA DEL NUEVO CAMPO
                // EJEMPLO 
                  
                if($secuencia==0){
                  $numero_compromiso_secuencia = $numero_compromiso;
                  $numero_orden_pago_secuencia = $numero_orden_pago;
                  $secuencia++;
                }else{
                  $numero_compromiso_secuencia = $numero_compromiso . '-' . $secuencia;
                  $numero_orden_pago_secuencia = $numero_orden_pago . "-" . $secuencia;
                  $secuencia++;
                }

                /*$max=$this->cepd01_compromiso_numero->execute("SELECT numero_compromiso FROM cepd01_compromiso_numero WHERE ".$this->SQLCA()." and ano_compromiso=".$ano_compromiso." and situacion=1 and $aleatorio=$aleatorio ORDER BY numero_compromiso ASC LIMIT 1");
                
                if($max!=null){
                  $numero_compromiso=$max[0][0]["numero_compromiso"];
                  $this->cepd01_compromiso_numero->execute("UPDATE  cepd01_compromiso_numero SET situacion=3 WHERE ".$this->SQLCA()." and numero_compromiso=".$numero_compromiso." and ano_compromiso=".$ano_compromiso);
                }*/

                $partidas=array();
                $tipo_concepto_enviar=0;
                $tmonto_rc=0;
                $concepto_p2= array();

                foreach($data_partidas_2 as $da_pa){
                  $d_fila = $da_pa["nomina_beneficiario_partidas_p2_2"];
                  
                  if($d_fila['beneficiario']==$gb1['nomina_beneficiario_partidas_p2_2']['beneficiario']){
                  
                    $partidas[]=array ($ano_compromiso,$d_fila['cod_sector'],$d_fila['cod_programa'],$d_fila['cod_sub_prog'],$d_fila['cod_proyecto'],$d_fila['cod_activ_obra'],$d_fila['cod_partida'],$d_fila['cod_generica'],$d_fila['cod_especifica'],$d_fila['cod_sub_espec'],$d_fila['cod_auxiliar'],$d_fila['monto']);
                    $tipo_concepto_enviar=$d_fila['tipo_denominacion'];
                    $tmonto_rc+=$d_fila['monto'];
                    $rif_cedula = $d_fila['rif_cedula'];
                    $personalidad = $d_fila['personalidad'];
                    $beneficiario = $d_fila['beneficiario'];
                    $denominacion_transaccion_concepto="";
                  
                    if($tipo_concepto_enviar==2){
                  
                      $detrasac_2=$this->nomina_partidas_denot_p1_2->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina."  and rif_cedula='".$rif_cedula."' and cod_sector=".$d_fila['cod_sector']." and cod_programa=".$d_fila['cod_programa']."  and cod_sub_prog=".$d_fila['cod_sub_prog']."  and cod_proyecto=".$d_fila['cod_proyecto']."  and cod_activ_obra=".$d_fila['cod_activ_obra']."  and cod_partida=".$d_fila['cod_partida']."  and cod_generica=".$d_fila['cod_generica']."  and cod_especifica=".$d_fila['cod_especifica']."  and cod_sub_espec=".$d_fila['cod_sub_espec']."  and cod_auxiliar=".$d_fila['cod_auxiliar']." ",'denominacion','denominacion ASC');
                  
                      foreach($detrasac_2 as $dtc){
                        $denominacion_transaccion_concepto=$dtc['nomina_partidas_denot_p1_2']['denominacion'];
                        /**/
                        for($i_vector=0;$i_vector<count($concepto_p2);$i_vector++){
                          if(isset($concepto_p2[$i_vector])){
                            if($concepto_p2[$i_vector]==$denominacion_transaccion_concepto){
                              $denominacion_transaccion_concepto = "";
                              break;
                            }
                          }
                        }
                        if($denominacion_transaccion_concepto!=""){
                          $concepto_p2[]="".$denominacion_transaccion_concepto;
                        }

                        /**/
                                  //$concepto_p2[]="".$denominacion_transaccion_concepto;
                      }
                    }
                  }
                     //pr($partidas);
                }//partidas

                $concepto=$denominacion[$tipo_concepto_enviar].implode(', ',$concepto_p2);
                $cod_dir_superior = $ubicacion[0]['cnmd09_ubicacion_direccion_personal']['cod_dir_superior'];
                $cod_coordinacion = $ubicacion[0]['cnmd09_ubicacion_direccion_personal']['cod_coordinacion'];
                $cod_secretaria   = $ubicacion[0]['cnmd09_ubicacion_direccion_personal']['cod_secretaria'];
                $cod_direccion    = $ubicacion[0]['cnmd09_ubicacion_direccion_personal']['cod_direccion'];
                $result = $this->_guardar_rc($ano_compromiso,$numero_compromiso,$tipo_compromiso,$tipo_recurso,$rif_cedula,$personalidad,$concepto,$beneficiario,$cod_dir_superior,$cod_coordinacion,$cod_secretaria,$cod_direccion,$tmonto_rc,$partidas,$cedula_identidad_autorizado,$autorizado_a_cobrar,$numero_compromiso_secuencia);
                  //echo $concepto."<br><br>";
                  //--$result = false;

                if($result==true){
                  $result_op = $this->_guardar_op($ano_compromiso,$numero_compromiso,$enlace_contable,$cedula_identidad_autorizado,$autorizado_a_cobrar, $numero_compromiso_secuencia,$numero_orden_pago,$numero_orden_pago_secuencia);
                      //$result_op = array('numero_orden_pago'=>0,'status'=>false,'error'=>'0');
                }else{
                  $result_op = array('numero_orden_pago'=>0,'status'=>false,'error'=>'0');
                }
                
                $resultado[]=array('numero_compromiso'=>$numero_compromiso,'beneficiario'=>$beneficiario,'status'=>$result==true?'Guardado':'No guardado','numero_orden_pago'=>$result_op['numero_orden_pago'],'status_op'=>$result_op['status']==true?'Guardado':'No guardado-1-'.$result_op['error']);

              }//beneficiarios distintos2

								// VERIFIRCAR HASTA AQUI

                                // REGISTRO ASIENTOS CONTABLES - COMPROMISOS
                                  $otros_compro_tmp=$this->cugd03_acta_anulacion_numero->execute("SELECT ano_documento, numero_documento FROM cnmd99_orden_pago_prenomina WHERE ".$this->condicion()." and cod_tipo_nomina=".$cod_tipo_nomina." ORDER BY ano_documento, numero_documento ASC");
                                  if($otros_compro_tmp!=null){
                                    foreach($otros_compro_tmp as $compro_tmp){
                                                              $ano_docu=$compro_tmp[0]['ano_documento'];
                                                              $nume_docu=$compro_tmp[0]['numero_documento'];
                                                              $otros_compromisos=$this->cugd03_acta_anulacion_numero->execute("SELECT * FROM cepd01_compromiso_cuerpo WHERE ".$this->condicion()." and ano_documento=".$ano_docu." and numero_documento=".$nume_docu." ORDER BY ano_documento, numero_documento ASC");
                                      if($otros_compromisos!=null){
                                        foreach($otros_compromisos as $otros_compro){
                                                                  $ano = $otros_compro[0]['ano_documento'];
                                                                  $numero_documento = $otros_compro[0]['numero_documento'];
                                                                  $fecha_documento = $otros_compro[0]['fecha_documento'];
                                                                  $rif = strtoupper($otros_compro[0]['rif']);
                                                                  $cedula = $otros_compro[0]['cedula_identidad'];
                                                                  $concepto = $otros_compro[0]['concepto'];
                                                                  $monto = $otros_compro[0]['monto'];
                                                                  $beneficiario = $otros_compro[0]['beneficiario'];
                                                                  $num_asiento=0;
                                                                  $ano_orden_pago_comp = $otros_compro[0]['ano_orden_pago'];
                                                                  $numero_orden_pago_comp = $otros_compro[0]['numero_orden_pago'];
                                                                  if ($rif==null || $rif=='0'){$rif=$cedula;}

                                                                  $busca_orden_pago=$this->cugd03_acta_anulacion_numero->execute("SELECT enlace_contable FROM cepd03_ordenpago_cuerpo WHERE ".$this->condicion()." and ano_orden_pago=".$ano_orden_pago_comp." and numero_orden_pago=".$numero_orden_pago_comp."");
                                                                  if($busca_orden_pago!=null){
                                                                    $enlace_contable_comp=$busca_orden_pago[0][0]["enlace_contable"];
                                                                  }


                                                                  $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
                                                                    $to=1,
                                                                    $td=6,
                                                                    $rif_doc = $rif,
                                                                    $ano_dc  = $ano,
                                                                    $n_dc    = $numero_documento,
                                                                    $f_dc    = cambiar_formato_fecha($fecha_documento),
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
                                                                    $tipo_modificacion  = null,
                                                                    $f_dc_adj_array_pago= array(),
                                                                    $parametro_extras_1   = array(),
                                                                    $parametro_extras_2   = array()
                                                                  );
                                        }// FOREACH cepd01_compromiso_cuerpo
                                      }
                                    }// FOREACH cnmd99_orden_pago_prenomina
                                  }
                                // FIN ASIENTOS CONTABLES - COMPROMISOS




                                // REGISTRO ASIENTOS CONTABLES - CAUSADOS
                                  $orden_pagado_tmp=$this->cugd03_acta_anulacion_numero->execute("SELECT ano_orden_pago, numero_orden_pago FROM cnmd99_orden_pago_prenomina WHERE ".$this->condicion()." and cod_tipo_nomina=".$cod_tipo_nomina." ORDER BY ano_documento, numero_documento ASC");
                                  if($orden_pagado_tmp!=null){
                                      foreach($orden_pagado_tmp as $orden_tmp){
                                                  $ano_docu=$orden_tmp[0]['ano_orden_pago'];
                                                  $nume_docu=$orden_tmp[0]['numero_orden_pago'];
                                                  $orden_pagado=$this->cugd03_acta_anulacion_numero->execute("SELECT * FROM cepd03_ordenpago_cuerpo WHERE ".$this->condicion()." and ano_orden_pago=".$ano_docu." and numero_orden_pago=".$nume_docu." ORDER BY ano_orden_pago, numero_orden_pago ASC");
                                                  if($orden_pagado!=null){
                                                    foreach($orden_pagado as $orden_paga){
                                                          $ano_orden_pago = $orden_paga[0]['ano_orden_pago'];
                                                          $numero_orden_pago = $orden_paga[0]['numero_orden_pago'];
                                                          $fecha_orden = $orden_paga[0]['fecha_orden_pago'];
                                                          $rif = strtoupper($orden_paga[0]['rif']);
                                                          $ano_documento = $orden_paga[0]['ano_documento_origen'];
                                                          $numero_documento = $orden_paga[0]['numero_documento_origen'];
                                                          $fecha_documento = $orden_paga[0]['fecha_documento'];
                                                          $cedula = $orden_paga[0]['cedula_identidad'];
                                                          $concepto = $orden_paga[0]['concepto'];
                                                          $beneficiario = $orden_paga[0]['beneficiario'];
                                                          $monto_total_orden_contabilidad = $orden_paga[0]['monto_total'];
                                                          $monto_orden_pago_contabilidad = $orden_paga[0]['monto_orden_pago'];
                                                          $monto_amortizacion_contabilidad = $orden_paga[0]['amortizacion_anticipo'];
                                                          $numero_doc_adjunto = $orden_paga[0]['numero_documento_adjunto'];
                                                          $fecha_doc_adjunto = $orden_paga[0]['numero_documento_adjunto'];
                                                          $tipo_orden_pago = $orden_paga[0]['tipo_orden'];
                                                          $enlace_contable_orden = $orden_paga[0]['enlace_contable'];
                                                          $num_asiento=0;
                                                          if ($rif==null || $rif=='0'){$rif=$cedula;}


                                                          $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
                                                            $to       = 1,
                                                            $td       = 9,
                                                            $ano_dc   = $ano_documento,
                                                            $rif_doc  = $rif,
                                                            $f_dc     = cambiar_formato_fecha($fecha_documento),
                                                            $n_dc     = $numero_documento,
                                                            $cpt_dc   = $concepto,
                                                            $ben_dc   = $beneficiario,
                                                            $mon_dc   = array(
                                                              "monto_total_orden"  => $monto_total_orden_contabilidad,
                                                              "monto_orden_pago"   => $monto_orden_pago_contabilidad,
                                                              "monto_amortizacion" => $monto_amortizacion_contabilidad
                                                              ),
                                                            $ano_op   = $ano_orden_pago,
                                                            $n_op     = $numero_orden_pago,
                                                            $f_op     = cambiar_formato_fecha($fecha_orden),
                                                            $a_adj_op = null,
                                                            $n_adj_op        = null,
                                                            $f_adj_op        = null,
                                                            $tp_op           = 1,

                                                            $deno_ban_pago   = null,
                                                            $ano_movimiento  = null,
                                                            $cod_ent_pago    = null,
                                                            $cod_suc_pago    = null,
                                                            $cod_cta_pago    = null,

                                                            $num_che_o_debi  = null,
                                                            $fec_che_o_debi  = null,
                                                            $clas_che_o_debi = null,
                                                            $tipo_che_o_debi = null,
                                                            $ano_dc_array_pago    = array(),
                                                            $n_dc_array_pago      = array(),
                                                            $n_dc_adj_array_pago  = array(),
                                                            $f_dc_array_pago      = array(),

                                                            $ano_op_array_pago  = array(),
                                                            $n_op_array_pago    = array(),
                                                            $f_op_array_pago    = array(),
                                                            $tipo_op_array_pago = array(),
                                                            $tipo_modificacion  = null,
                                                            $f_dc_adj_array_pago= array(),
                                                            $parametro_extras_1   = array(),
                                                            $parametro_extras_2   = array()
                                                          );
                                                    }// foreach cepd03_ordenpago_cuerpo
                                                  }
                                      }// foreach cnmd99_orden_pago_prenomina
                                  }
                                // FIN ASIENTOS CONTABLES - CAUSADOS

                                $this->set('data_ordenes',$resultado);
                                if ($verifica_guardado==0){
                                  $sql_update_cnmd01="UPDATE cnmd01 SET status_nomina=4, ejecucion_orden_pago=0 WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina;
                                   // echo "<script>
                                   //    document.getElementById('bt_reporte').disabled=false;
                                   //  </script>";
                                  $this->set("Message_existe","Proceso Exitoso!!");
                                }else{
                                  $sql_update_cnmd01="UPDATE cnmd01 SET status_nomina=2, ejecucion_orden_pago=0 WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina;
                                  $this->set("errorMessage","POR FAVOR REVISE ORDENES DE PAGO NO GUARDADAS!!");
                                }

                                $this->Cnmd01->execute($sql_update_cnmd01);
                                echo '<script language="JavaScript" type="text/javascript">
                                Control.Modal.close(true);
                                </script>';
              }else{
                       $data_conex2 = $this->Cnmd01->execute("SELECT * FROM cantidad_conexion_cfpd05 where ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cantidad_cfpd05=0");
                       $this->set('DATA_CONEX1',$data_conex2);
                       $this->set('errorMessage','Conexión de la Clasificación presupuestaria no formulada para el ejercicio');
              }
            }else{
             $data_conex1 = $this->Cnmd01->execute("SELECT * FROM cantidad_cnmd05_cfpd05 where ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cantidad_cfpd05=0 and condicion_actividad=2");
             $this->set('DATA_CONEX1',$data_conex1);
             $this->set('errorMessage','Cargos de la institución - Clasificación presupuestaria no formulada para el ejercicio');
            }
          }//fin bancos 1
        }//fin bancos 2
    }else{
    	//no existe nomina para correr orden de pagos
        if($negativo!=0)
 		{
    		$this->set('errorMessage','Existen '. $negativo . ' partidas en negativo. Revise el costo presupuesto');
 		}else{
    		$this->set('errorMessage','Nomina seleccionada ya fue procesada');
 		}
    }
  }else{
    $this->set('errorMessage','Hay campos sin llenar, Verifique el formulario');
  }
}//fin procesar

function salir_prenomina ($numero) {
       $this->layout="ajax";
}//fin salir_prenomina


function _guardar_rc($ano_compromiso,$numero_compromiso,$tipo_compromiso,$tipo_recurso,$rif_cedula,$personalidad,$concepto,$beneficiario,$cod_dir_superior,$cod_coordinacion,$cod_secretaria,$cod_direccion,$monto,$array_partidas,$cedula_identidad_autorizado,$autorizado_a_cobrar,$numero_compromiso_secuencia){

  $this->layout="ajax";
set_time_limit(0);
  $cod_presi=$this->Session->read('SScodpresi');
  $cod_entidad=$this->Session->read('SScodentidad');
  $cod_tipo_inst=$this->Session->read('SScodtipoinst');  
  $cod_dep=$this->verifica_SS(5);
  $cod_inst=$this->Session->read('SScodinst');

  $ano=$ano_compromiso;
  $numero_documento=$numero_compromiso;
  $numero_documento_secuencia = $numero_compromiso_secuencia;
  $tipo_documento=$tipo_compromiso;
  $fecha_documento=date('d/m/Y');
  $condicion_juridica=$personalidad;
  $condicion_documento=1;//cuando se guarda es Activo=1
  $num_asiento=0;
  $fecha_proceso_registro=date("Y-m-d");
  $fecha_proceso_anulacion="1900-01-01";
  $username='AUTO_NOMINA';

  if($personalidad=="2" || $personalidad==2){
    $rif=strtoupper($rif_cedula);
    $rif_conta=$rif;
    $cedula="0";
    $cant_br=$this->cepd01_compromiso_beneficiario_rif->findCount("upper(rif)='".$rif."'");
    if($cant_br==0 || $cant_br=="0"){
      $Y=$this->cepd01_compromiso_beneficiario_rif->execute("INSERT INTO cepd01_compromiso_beneficiario_rif VALUES ('".$rif."','".$beneficiario."')");
    }
  }else{
    $rif="0";
    if($personalidad=="1" || $personalidad==1){
      $cedula=$rif_cedula;
      $rif_conta=$cedula;

      $cant_bc=$this->cepd01_compromiso_beneficiario_cedula->findCount("cedula='".$cedula."'");

      if($cant_bc==0 || $cant_bc=="0"){
        $z=$this->cepd01_compromiso_beneficiario_cedula->execute("INSERT INTO cepd01_compromiso_beneficiario_cedula VALUES ('".$cedula."','".$beneficiario."')");
      }
    }else{
      $cedula="0";
    }
  }

  if($cedula_identidad_autorizado!=0){
    $cant_bc_a=$this->cepd01_compromiso_beneficiario_cedula->findCount("cedula='".$cedula_identidad_autorizado."'");

    if($cant_bc_a==0 || $cant_bc_a=="0"){
      $ab=$this->cepd01_compromiso_beneficiario_cedula->execute("INSERT INTO cepd01_compromiso_beneficiario_cedula VALUES ('".$cedula_identidad_autorizado."','".$autorizado_a_cobrar."')");
    }
  }
  //agregar campo de secuencia
  $camposT2="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_documento,numero_documento,cod_tipo_compromiso,fecha_documento,tipo_recurso,rif,cedula_identidad,cod_dir_superior,cod_coordinacion,cod_secretaria,cod_direccion,concepto,monto,condicion_actividad,dia_asiento_registro,mes_asiento_registro,ano_asiento_registro, numero_asiento_registro,username_registro,ano_anulacion,numero_anulacion,dia_asiento_anulacion,mes_asiento_anulacion,ano_asiento_anulacion,numero_asiento_anulacion,username_anulacion,ano_orden_pago,numero_orden_pago,beneficiario,condicion_juridica,fecha_proceso_registro,fecha_proceso_anulacion,numero_documento_secuencia";

  $camposT3="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_documento,numero_documento,ano,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,monto,numero_control_compromiso,numero_documento_secuencia";

  $values="";
  $monto=0;
  $i=0;
  $ann=$ano;
  // agregar que busque con la secuencia
  $C_RC=$this->cepd01_compromiso_cuerpo->findCount($this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$numero_documento." and numero_documento_secuencia='".$numero_documento_secuencia."'");

  $this->cepd01_compromiso_cuerpo->execute("BEGIN;");//INICIO DE LA TRANSACCION

  /**
   *
   *  MODIFICACION PARA LAS ORDENES DE PAGOS NOMINAS
   *
   */

  // agregar el campo de secuencia
  $R2=$this->cepd01_compromiso_cuerpo->execute("INSERT INTO cepd01_compromiso_cuerpo (".$camposT2.") VALUES (".$this->SQLCAIN($ano).",".$numero_documento.",".$tipo_documento.",'".$fecha_documento."',".$tipo_recurso.",'".$rif."','".$cedula."',".$cod_dir_superior.",".$cod_coordinacion.",".$cod_secretaria.",".$cod_direccion.",'".$concepto."',".$monto.",1,0,0,0,0,'".$username."',0,0,'0',0,0,0,0,0,0,'".$beneficiario."',".$condicion_juridica.",'".$fecha_proceso_registro."','".$fecha_proceso_anulacion."','".$numero_documento_secuencia."')");

  if($R2>1 && $C_RC==0){

    $j=1;
    $x=0;

    $numero_compromiso= $this->cfpd21_numero_asiento_compromiso->field('cfpd21_numero_asiento_compromiso.numero_compromiso', $conditions = $this->condicionNDEP()." and ano_compromiso='$ann'", $order =null);
    if(!empty($numero_compromiso)){
      $numero_compromiso ++;
      $sql_numero_compromiso = "UPDATE cfpd21_numero_asiento_compromiso SET numero_compromiso='$numero_compromiso' WHERE ano_compromiso='$ann' and ".$this->condicionNDEP().";COMMIT;";
      $this->cepd01_compromiso_cuerpo->execute("BEGIN;");
    }else{
      $numero_compromiso = 1;
      $sql_numero_compromiso = "INSERT INTO cfpd21_numero_asiento_compromiso VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$ann', '$numero_compromiso')";
    }
    $sw_numero_compromiso = $this->cfpd21_numero_asiento_compromiso->query($sql_numero_compromiso);

          //pr($array_partidas);
        foreach($array_partidas as $vec){
               // pr($vec);
            $cp      = $this->crear_partida($vec[0], $vec[1], $vec[2], $vec[3], $vec[4], $vec[5], $vec[6],$vec[7], $vec[8], $vec[9],$vec[10]);
            $to      = 1;
            $td      = 3;
            $ta      = 1;
            $rnco    = $numero_compromiso;
            $mt      = $vec[11];
            $vec[12] = $rnco;
            $ccp     = $concepto;


 
          $sqlCheck = 'SELECT * FROM cfpd40_dependencias_presupuesto WHERE cod_dep='.$cod_dep;
          $checkDep = $this->arrd05->execute($sqlCheck);
          // if($cod_dep==9999 || $cod_dep==1028){
          if(count($checkDep)>0){
            $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fecha_documento, $mt, $ccp, $ann, $numero_documento, null, null, null, null, null, null, null, $rnco, null, null, null, $x);

            if($dnco != false){
              $dnco_rsultado = '<br>(correcto '.$dnco.') ';
            }else{
              $vec[12]=0;
              $dnco_rsultado = '<br>(no correcto) ';
              break;
            }
          }else{
            $dnco_rsultado = '<br>(correcto '.true.') ';
          }


            $values_a[]="(".$this->SQLCAIN($ano).",".$numero_documento.",".$ano.",".$vec[1].",".$vec[2].",".$vec[3].",".$vec[4].",".$vec[5].",".$vec[6].",".$vec[7].",".$vec[8].",".$vec[9].",".$vec[10].",".$vec[11].",".$vec[12].",'".$numero_documento_secuencia."')";

            $monto=$monto+$vec[11];
            $j++;
            $x++;
        }//fin for
        $values=implode(',',$values_a);

        //agregar numero de secuencia
        $this->cepd01_compromiso_cuerpo->execute("UPDATE cepd01_compromiso_cuerpo SET monto=".$monto."  where ".$this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$numero_documento." and numero_documento_secuencia='".$numero_documento_secuencia."'");

        $R3=$this->cepd01_compromiso_partidas->execute("INSERT INTO cepd01_compromiso_partidas (".$camposT3.") VALUES ".$values."");
          //echo "<br>"."INSERT INTO cepd01_compromiso_partidas (".$camposT3.") VALUES ".$values."";

        if($R3>1){
          $this->cepd01_compromiso_numero->execute("UPDATE cepd01_compromiso_numero SET situacion=3 WHERE ".$this->SQLCA()." and ano_compromiso=".$ano." and numero_compromiso=".$numero_documento);

          //este debe ser solo para el registro
          if ($numero_documento == $numero_documento_secuencia){
            $RESUL_EMITIR = $this->cepd01_compromiso_poremitir->execute("INSERT INTO cepd01_compromiso_poremitir (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,username,ano_documento,numero_documento,numero_documento_secuencia) VALUES (".$this->SQLCAIN().",'".$this->Session->read('nom_usuario')."',".$ano.",".$numero_documento.",'".$numero_documento_secuencia."')");
          }else{
            $RESUL_EMITIR=2;
          }

          if($RESUL_EMITIR>1){
            $this->cepd01_compromiso_cuerpo->execute("COMMIT;");
            $Guardados_exito = true;
          }else{
            $this->cepd01_compromiso_cuerpo->execute("ROLLBACK;");
                //agregar secuencia
            
            $this->cepd01_compromiso_cuerpo->execute("DELETE FROM cepd01_compromiso_cuerpo WHERE ".$this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$numero_documento." and numero_documento_secuencia='".$numero_documento_secuencia."'");

                // queda igual
            $this->cepd01_compromiso_numero->execute("UPDATE cepd01_compromiso_numero SET situacion=1 WHERE ".$this->SQLCA()." and ano_compromiso=".$ano." and numero_compromiso=".$numero_documento);
            $Guardados_exito = false;
            $verifica_guardado = 1;
          }
        }else{    
            //$this->cepd01_compromiso_cuerpo->execute("DELETE FROM cepd01_compromiso_cuerpo WHERE ".$this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$numero_documento);
          $this->cepd01_compromiso_cuerpo->execute("ROLLBACK;");
                // agregar secuencia
          $this->cepd01_compromiso_cuerpo->execute("DELETE FROM cepd01_compromiso_cuerpo WHERE ".$this->SQLCA()." and ano_documento=".$ano." and numero_documento=".$numero_documento);
                //queda igual
          $this->cepd01_compromiso_numero->execute("UPDATE cepd01_compromiso_numero SET situacion=1 WHERE ".$this->SQLCA()." and ano_compromiso=".$ano." and numero_compromiso=".$numero_documento);
          $Guardados_exito=false;
          $verifica_guardado = 1;
        }
  }else{
     $this->cepd01_compromiso_cuerpo->execute("ROLLBACK;");
     $Guardados_exito=false;
     $verifica_guardado = 1;
  }
  return  $Guardados_exito;
}//fin funcion guardar_rc

function _guardar_op($ano_compromiso,$numero_compromiso,$enlace_contable,$cedula_identidad_autorizado,$autorizado_a_cobrar,$numero_compromiso_secuencia,$numero_orden_pago,$numero_orden_pago_secuencia){

  $this->layout="ajax";
  set_time_limit(0);
  $cod_presi=$this->verifica_SS(1);
  $cod_entidad=$this->verifica_SS(2);
  $cod_tipo_inst=$this->verifica_SS(3);
  $cod_inst=$this->verifica_SS(4);
  $cod_dep=$this->verifica_SS(5);
  $cod_tipo_nomina = $this->Session->read('cod_tipo_nomina');
  $numero_nomina = $this->Session->read('numero_nomina');
  $aleatorio=rand();
  /*$max=$this->cepd03_ordenpago_numero->execute("SELECT numero_orden_pago FROM cepd03_ordenpago_numero WHERE ".$this->SQLCA()." and ano_orden_pago=".$ano_compromiso." and situacion=1 and $aleatorio=$aleatorio ORDER BY numero_orden_pago ASC LIMIT 1");
  if($max!=null){
    $numero_orden_pago=$max[0][0]["numero_orden_pago"];
    $this->cepd03_ordenpago_numero->execute("UPDATE  cepd03_ordenpago_numero SET situacion=3 WHERE ".$this->SQLCA()." and numero_orden_pago=".$numero_orden_pago." and ano_orden_pago=".$ano_compromiso);
  }
  */

  $var[0] =$ano_compromiso;
  $ann = $ano_compromiso;
  $var[1] = 2;
  $fd = date("d/m/Y");// 01/34/6789
  $var[2] = date("Y-m-d");
  $opfecha=$fd;
  $var[3] = $ano_compromiso;
  $var[4] = 1;
  $var[5] = $numero_compromiso;
  $numero_doc_adjunto = 0;
  $fdoo = date("d/m/Y");// 01/34/6789
  $fdo=date("Y-m-d");
  $m_fdo=date("d/m/Y");
  $m_opfecha=date("d/m/Y");

  $data_rc = $this->cepd01_compromiso_cuerpo->findAll($this->SQLCA()." and ano_documento=".$ano_compromiso." and numero_documento=".$numero_compromiso. " and numero_documento_secuencia='".$numero_compromiso_secuencia."'");

  $drc=$data_rc[0]['cepd01_compromiso_cuerpo'];
  if($drc['condicion_juridica']==2 || $drc['condicion_juridica']=='2'){
   $var[6] = $drc['rif'];
  }else{
   $var[6] = $drc['cedula_identidad'];
  }

  if ($cedula_identidad_autorizado!=0){
    $autorizacion=$autorizado_a_cobrar;
  }else{
    $autorizacion=$drc['beneficiario'];
  }

  $var[7] = $drc['beneficiario'];
  $var[9] = $autorizacion;
  $var[9] = str_replace('"','\"',$var[9]);
  $var[10] = $cedula_identidad_autorizado;
  $var[11] = $drc['monto'];
  $var[12] = 4;
  $var[13] = $drc['monto'];
  $var[14] = 0;
  $var[15] = 0;
  $var[16] = 0;
  $var[17] = 0;
  $var[18] = 0;
  $var[19] = 0;
  $var[20] = 0;
  $var[21] = 0;
  $var[22] = $drc['monto'];
  $var[23] = 0;
  $var[24] = 0;
  $var[25] = 0;
  $var[26] = 0;
  $var[27] = 0;
  $var[28] = 0;
  $var[29] = 0;
  $var[30] = 0;
  $var[31] = $drc['monto'];
  $sustraendo=0;
  $porcentaje_iva=0;
  $var[32] = $drc['monto'];
  $var[33] = 1;
  $var[34] = $drc['monto'];
  $fd1     = date("Y-m-d");
  $fd2     = date("Y-m-d");
  $var[35] =  date("Y-m-d");
  $var[36] =  date("Y-m-d");
  $var[37] = 1;
  $var[38] = "0";
  $var[39] = "0";
  $var[40] = "0";
  $var[41] = "0";
  $var[42] = "0";
  $var[43] = "0";
  $var[44] = "0";
  $var[45] = $drc['concepto'];
  $var[45] = str_replace('"','\"',$var[45]);
  $var[46] = 0;
  $var[47] = 0;
  $var[48] = 0;
  $var[49] = 0;
  $var[50] = 0;
  $var[51] = 0;
  $var[52] = 0;
  $var[53] = 0;
  $var[54] = 0;
  $var[55] = 0;
  $var[56] = 0;
  $var[57] = 0;
  $var[58] = 0;
  $var[59] = $numero_orden_pago_secuencia;
  $var[60] = $numero_orden_pago;

  $CAMPOS_CUERPO="cod_presi,
  cod_entidad,
  cod_tipo_inst,
  cod_inst,
  cod_dep,
  ano_orden_pago,
  numero_orden_pago,
  tipo_orden,
  fecha_orden_pago,
  ano_documento_origen,
  numero_documento_origen,
  numero_documento_adjunto,
  fecha_documento,
  cod_tipo_documento,
  rif,
  beneficiario,
  autorizado,
  cedula_identidad,
  concepto,
  monto_total,
  numero_pago,
  monto_parcial,
  cod_frecuencia_pago,
  fecha_desde,
  fecha_hasta,
  cod_tipo_pago,
  monto_coniva,
  monto_iva,
  porcentaje_iva,
  monto_siniva,
  monto_retencion_laboral,
  porcentaje_laboral,
  monto_retencion_fielcumplimiento,
  porcentaje_fielcumplimiento,
  monto_descontar_impuesto,
  amortizacion_anticipo,
  porcentaje_amortizacion,
  monto_orden_pago,
  monto_retencion_iva,
  porcentaje_retencion_iva,
  monto_islr,
  porcentaje_islr,
  monto_sustraendo,
  monto_timbre_fiscal,
  porcentaje_timbre_fiscal,
  monto_impuesto_municipal,
  porcentaje_impuesto_municipal,
  monto_neto_cobrar ,
  dia_asiento_registro,
  mes_asiento_registro,
  ano_asiento_registro,
  numero_asiento_registro,
  username_registro,
  condicion_actividad,
  ano_anulacion,
  numero_anulacion,
  dia_asiento_anulacion,
  mes_asiento_anulacion,
  ano_asiento_anulacion,
  numero_asiento_anulacion,
  username_anulacion,
  ano_movimiento,
  cod_entidad_bancaria,
  cod_sucursal,
  cuenta_bancaria,
  numero_cheque,
  fecha_cheque,
  fecha_proceso_registro,
  fecha_proceso_anulacion,
  numero_comprobante_islr,
  numero_comprobante_timbre,
  numero_comprobante_municipal,
  numero_comprobante_iva,
  numero_comprobante_librocompras,
  numero_comprobante_egreso,
  enlace_contable,
  numero_orden_pago_secuencia,
  numero_documento_secuencia
  ";


  $CAMPOS_PARTIDAS="cod_presi,
  cod_entidad,
  cod_tipo_inst,
  cod_inst,
  cod_dep,
  ano_orden_pago,
  numero_orden_pago,
  ano,
  cod_sector,
  cod_programa,
  cod_sub_prog,
  cod_proyecto,
  cod_activ_obra,
  cod_partida,
  cod_generica,
  cod_especifica,
  cod_sub_espec,
  cod_auxiliar,
  monto,
  numero_control_compromiso,
  numero_control_causado,
  numero_orden_pago_secuencia,
  numero_documento_secuencia";


  switch($var[4]){
    case 1://compromiso
      $compromiso_partidas = $this->cepd01_compromiso_partidas->findAll($this->SQLCA()." and ano_documento=".$var[3]." and numero_documento=".$var[5]." and numero_documento_secuencia='".$numero_compromiso_secuencia."'");

      $total_partidas = $this->cepd01_compromiso_partidas->findCount($this->SQLCA()." and ano_documento=".$var[3]." and numero_documento=".$var[5]." and numero_documento_secuencia='".$numero_compromiso_secuencia."'");

      $total_partidas_iva = $this->cepd01_compromiso_partidas->findCount($this->SQLCA()." and ano_documento=".$var[3]." and numero_documento=".$var[5]." and numero_documento_secuencia='".$numero_compromiso_secuencia."' and ((cod_partida=403 and cod_generica=18 and cod_especifica=1) OR cod_partida=411)");

      $compromiso_partidas = $compromiso_partidas != null ? $compromiso_partidas : array();
      $partidas=$compromiso_partidas;

      $modelo="cepd01_compromiso_partidas";
      $modelo_tabla="cepd01_compromiso_partidas";
      $modelo_cuerpo1="cepd01_compromiso_cuerpo";
      $modelo_cuerpo2="cepd01_compromiso_cuerpo";
      $ano_up="ano_documento";
      $numero_up="numero_documento";
      $numero_up_adj="";
      $campo_ncc="numero_control_compromiso";
      $n_c_c="numero_control_causado";
      $ndo = $var[5];
      $nda = 0;
    break;
  }//fin switch

  $VALUES_CUERPO=$this->SQLCAIN().",".$var[0].",".$numero_orden_pago.",".$var[1].",'".$var[2]."',".$var[3].",'".$var[5]."',".$numero_doc_adjunto.",'".$fdo."',".$var[4].",'".$var[6]."','".$var[7]."','".$var[9]."',".$var[10].",'".$var[45]."',".$var[11].",".$var[33].",".$var[34].",".$var[37].",'".$var[35]."','".$var[36]."',".$var[12].",".$var[13].",".$var[18].",".$porcentaje_iva.",".($var[13]-$var[18]).",".$var[15].",".$var[14].",".$var[17].",".$var[16].",".$var[19].",".$var[21].",".$var[20].",".$var[22].",".$var[24].",".$var[23].",".$var[26].",".$var[25].",".$sustraendo.",".$var[28].",".$var[27].",".$var[30].",".$var[29].",".$var[31].",0,0,0,0,'AUTO_NOMINA',1,0,0,0,0,0,0,'0',0,0,0,0,0,'1900-01-01','".date("Y-m-d")."','1900-01-01',0,0,0,0,0,0,'".$enlace_contable."','".$numero_orden_pago_secuencia."','".$numero_compromiso_secuencia."'";

  
  $this->cepd03_ordenpago_cuerpo->execute("BEGIN;");
  $resultado1 = $this->cepd03_ordenpago_cuerpo->execute("INSERT INTO cepd03_ordenpago_cuerpo  ($CAMPOS_CUERPO) VALUES ($VALUES_CUERPO)");

  if($resultado1>1){
          //INSERTA ORDENES DE PAGO EFECTUADAS EN ESTA NOMINA
          $inserta_temporal=$this->cepd03_ordenpago_cuerpo->execute("INSERT INTO cnmd99_orden_pago_prenomina (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano_documento, numero_documento, ano_orden_pago, numero_orden_pago, beneficiario, monto, fecha, numero_documento_secuencia, numero_orden_pago_secuencia) VALUES (".$cod_presi.",".$cod_entidad.",".$cod_tipo_inst.",".$cod_inst.",".$cod_dep.",".$cod_tipo_nomina.",".$var[0].",".$var[5].",".$var[0].",".$numero_orden_pago.",'".$var[7]."',".$var[11].",'".$var[2]."','".$numero_compromiso_secuencia."','".$numero_orden_pago_secuencia."')");
            
          $inserta_permanente=$this->cepd03_ordenpago_cuerpo->execute("INSERT INTO cnmd99_orden_pago_prenomina_perma (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, numero_nomina, ano_documento, numero_documento, ano_orden_pago, numero_orden_pago, beneficiario, monto, fecha, numero_documento_secuencia, numero_orden_pago_secuencia) VALUES (".$cod_presi.",".$cod_entidad.",".$cod_tipo_inst.",".$cod_inst.",".$cod_dep.",".$cod_tipo_nomina.",".$numero_nomina.",".$var[0].",".$var[5].",".$var[0].",".$numero_orden_pago.",'".$var[7]."',".$var[11].",'".$var[2]."','".$numero_compromiso_secuencia."','".$numero_orden_pago_secuencia."')");

          
          $resultado3=2;
          $i=1;
          $j=0;

          $numero_causado= $this->cfpd22_numero_asiento_causado->field('cfpd22_numero_asiento_causado.numero_causado', $conditions = $this->condicionNDEP()." and ano_causado='$ann'", $order =null);

          if(!empty($numero_causado)){
            $numero_causado ++;
            $sql_numero_causado = "UPDATE cfpd22_numero_asiento_causado SET numero_causado='$numero_causado' WHERE ano_causado='$ann' and ".$this->condicionNDEP().";";
          }else{
            $numero_causado = 1;
            $sql_numero_causado = "INSERT INTO cfpd22_numero_asiento_causado VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$ann', '$numero_causado')";
          }

          $sw_numero_causado = $this->cfpd22_numero_asiento_causado->query($sql_numero_causado);


          foreach($partidas as $partida){

             $cp   = $this->crear_partida($partida[$modelo]["ano"],$partida[$modelo]["cod_sector"],$partida[$modelo]["cod_programa"],$partida[$modelo]["cod_sub_prog"],$partida[$modelo]["cod_proyecto"],$partida[$modelo]["cod_activ_obra"],$partida[$modelo]["cod_partida"],$partida[$modelo]["cod_generica"],$partida[$modelo]["cod_especifica"],$partida[$modelo]["cod_sub_espec"],$partida[$modelo]["cod_auxiliar"]);
              $to   = 1;
              $td   = 4;
              $ta   = 1;
              $mt   = $partida[$modelo]["monto"];
              $ccp  = $var[45];
              $rnco = $partida[$modelo][$campo_ncc];
              $rnca = $numero_causado;


              $sqlCheck = 'SELECT * FROM cfpd40_dependencias_presupuesto WHERE cod_dep='.$cod_dep;
              $checkDep = $this->arrd05->execute($sqlCheck);
              // if($cod_dep==9999 || $cod_dep==1028){
              if(count($checkDep)>0){
                $dnca = $this->motor_presupuestario($cp, $to, $td, $ta, $m_fdo, $mt, $ccp, $ann, $ndo, $nda, $numero_orden_pago, $m_opfecha, null, null, null, null, $rnco, $rnca, null, null, $i);
 
                $ncc=$dnca;
              }else{
                $ncc=0;
              }
              
 
             $VPARTIDAS[]="(".$this->SQLCAIN().",".$var[0].",".$numero_orden_pago.",".$partida[$modelo]["ano"].",".$partida[$modelo]["cod_sector"].",".$partida[$modelo]["cod_programa"].",".$partida[$modelo]["cod_sub_prog"].",".$partida[$modelo]["cod_proyecto"].",".$partida[$modelo]["cod_activ_obra"].",".$partida[$modelo]["cod_partida"].",".$partida[$modelo]["cod_generica"].",".$partida[$modelo]["cod_especifica"].",".$partida[$modelo]["cod_sub_espec"].",".$partida[$modelo]["cod_auxiliar"].",".$partida[$modelo]["monto"].",".$rnco.",".$rnca.",'".$numero_orden_pago_secuencia."','".$numero_compromiso_secuencia."')";

              if($var[4]!=1 && $ncc!=0){
                $RS_update_causado_partidas=$this->$modelo->execute("UPDATE ".$modelo_tabla." SET numero_control_causado=".$ncc." WHERE ".$this->SQLCA()." and ".$ano_up."=".$var[3]." and ".$numero_up."='".$var[5]."' ".$numero_up_adj." and cod_sector=".$partida[$modelo]["cod_sector"]." and cod_programa=".$partida[$modelo]["cod_programa"]." and cod_sub_prog=".$partida[$modelo]["cod_sub_prog"]." and cod_proyecto=".$partida[$modelo]["cod_proyecto"]." and cod_activ_obra=".$partida[$modelo]["cod_activ_obra"]." and cod_partida=".$partida[$modelo]["cod_partida"]." and cod_generica=".$partida[$modelo]["cod_generica"]." and cod_especifica=".$partida[$modelo]["cod_especifica"]." and cod_sub_espec=".$partida[$modelo]["cod_sub_espec"]." and cod_auxiliar=".$partida[$modelo]["cod_auxiliar"]." and numero_documento_secuencia='".$partida[$modelo]["numero_documento_secuencia"]."'");
              }
              $i++;
              $j++;
          }//fin foreach partidas

          $VALUES_PARTIDAS=implode(',', $VPARTIDAS);

          
          $resultado2=$this->cepd03_ordenpago_partidas->execute("INSERT INTO cepd03_ordenpago_partidas  ($CAMPOS_PARTIDAS) VALUES $VALUES_PARTIDAS");
          
          if($resultado2>1){
            if($resultado3>1){
              $this->cepd03_ordenpago_numero->execute("UPDATE cepd03_ordenpago_numero SET situacion=3 WHERE ".$this->SQLCA()." and ano_orden_pago=".$var[0]." and numero_orden_pago=".$numero_orden_pago);
              $upop=$this->$modelo_cuerpo1->execute("UPDATE ".$modelo_cuerpo2." SET ano_orden_pago=".$var[0].", numero_orden_pago=".$numero_orden_pago." WHERE ".$this->SQLCA()." and ".$ano_up."=".$var[3]." and ".$numero_up."='".$var[5]."' ".$numero_up_adj);
              
              if ($numero_orden_pago==$numero_orden_pago_secuencia){
                $re=$this->cepd03_ordenpago_poremitir->execute("INSERT INTO cepd03_ordenpago_poremitir VALUES (".$this->SQLCAIN().",'".$this->Session->read('nom_usuario')."',".$var[0].",".$numero_orden_pago.")");
              }else{
                $re=2;
              }
              //fin else

              if($re>1 && $upop>1){
                $this->cepd03_ordenpago_cuerpo->execute("COMMIT;");
                return array('numero_orden_pago'=>$numero_orden_pago,'status'=>true,'error'=>'1');
              }else{
                $this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
                return array('numero_orden_pago'=>$numero_orden_pago,'status'=>false,'error'=>'2');
              }
            }else{
              $this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
              $this->cepd03_ordenpago_numero->execute("UPDATE cepd03_ordenpago_numero SET situacion=1 WHERE ".$this->SQLCA()." and ano_orden_pago=".$var[0]." and numero_orden_pago=".$numero_orden_pago);
              return array('numero_orden_pago'=>$numero_orden_pago,'status'=>false,'error'=>'3');
            }
          }else{
            $this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
            //$this->cepd03_ordenpago_cuerpo->execute("DELETE FROM cepd03_ordenpago_cuerpo WHERE ".$this->SQLCA()." and ano_orden_pago=".$var[0]." and numero_orden_pago=".$numero_orden_pago." and numero_orden_pago_secuencia='".$numero_orden_pago_secuencia."'");
            $this->cepd03_ordenpago_numero->execute("UPDATE cepd03_ordenpago_numero SET situacion=1 WHERE ".$this->SQLCA()." and ano_orden_pago=".$var[0]." and numero_orden_pago=".$numero_orden_pago);
            return array('numero_orden_pago'=>$numero_orden_pago,'status'=>false,'error'=>'4');
          }
  }else{// si no se ejecuto el cuerpo de la orden se realiza el rollback
    $this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
    $this->cepd03_ordenpago_numero->execute("UPDATE cepd03_ordenpago_numero SET situacion=1 WHERE ".$this->SQLCA()." and ano_orden_pago=".$var[0]." and numero_orden_pago=".$numero_orden_pago);
    return array('numero_orden_pago'=>$numero_orden_pago,'status'=>false,'error'=>'5');
  }
}//fin Guardar orden pago nomina


function seleccion_nomina () {
    $this->layout="ajax";
    $lista = $this->Cnmd01->generateList($this->SQLCA()." and status_nomina in (2)", 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($this->Cnmd01->findCount($this->SQLCA()." and status_nomina in (2)")!=0){
		$this->concatena($lista, 'tipo_nomina');
	}else{
		$this->set('tipo_nomina', array());
	}
}//fin index



function actualizar_rif_op () {
   $this->layout="ajax";


   $datarif=$this->Cnmd01->execute("SELECT a.cod_inst,
a.cod_dep,
a.numero_documento,
a.cod_tipo_compromiso,
a.fecha_documento,
a.tipo_recurso,
a.rif,
a.cedula_identidad,
a.condicion_actividad,
a.ano_orden_pago,
a.numero_orden_pago

  FROM cepd01_compromiso_cuerpo a ,cepd03_ordenpago_cuerpo b

  where
  a.cod_inst=b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.condicion_actividad=1 and
  b.condicion_actividad=1 and
  a.ano_orden_pago = b.ano_orden_pago and
  a.numero_orden_pago = b.numero_orden_pago and
  b.rif='0'
  order by a.cod_dep asc");


  foreach($datarif as $dr){
  	$rif=$dr[0]['rif'];
  	$cod_inst = $dr[0]['cod_inst'];
  	$cod_dep = $dr[0]['cod_dep'];
  	$ano_op = $dr[0]['ano_orden_pago'];
  	$numero_op = $dr[0]['numero_orden_pago'];
  	$this->Cnmd01->execute("UPDATE cepd03_ordenpago_cuerpo SET rif = '$rif' WHERE cod_inst=$cod_inst and cod_dep=$cod_dep and ano_orden_pago=$ano_op and numero_orden_pago=$numero_op");

  }



}//fin funcion actualizar_rif_op



function cod_deno_nomina ($cod_tipo_nomina=null) {
     $this->layout="ajax";
     $this->set('cod_nomina', $cod_tipo_nomina);
     if (isset($cod_tipo_nomina)) {

		$anos_docs = $this->v_cnmp99_historica_orden_pago_perma->generateList($this->SQLCA()." and cod_tipo_nomina=$cod_tipo_nomina", 'ano_documento ASC', null, '{n}.v_cnmp99_historica_orden_pago_perma.ano_documento', '{n}.v_cnmp99_historica_orden_pago_perma.ano_documento');
		if(!empty($anos_docs)){
			$this->set('anos_docs', $anos_docs);
		}else{
			$this->set('anos_docs', array());
		}

        // $a = $this->v_cnmp99_historica_orden_pago_perma->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina, 'cod_tipo_nomina, denominacion');
        $a = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina, 'cod_tipo_nomina, denominacion');
		if(!empty($a)){

		echo "<script>
				document.getElementById('in_cod_tipo_nomina').value='".mascara($a[0]['Cnmd01']['cod_tipo_nomina'], 3)."';
				document.getElementById('in_denominacion_tipo_nomina').value='".$a[0]['Cnmd01']['denominacion']."';
				document.getElementById('b_generar').disabled=true;
			</script>";
		}else{
		echo "<script>
				document.getElementById('in_cod_tipo_nomina').value='';
				document.getElementById('in_denominacion_tipo_nomina').value='';
				document.getElementById('b_generar').disabled=true;
			</script>";
		}
	}
}


function numeros_nomina ($cod_tipo_nomina = null, $ano_doc = null) {

     $this->layout="ajax";
     $this->set('cod_nomina', $cod_tipo_nomina);
     $this->set('ano_documento', $ano_doc);
     if ($cod_tipo_nomina != null && $ano_doc != null) {
        // $numeros_nom = $this->v_cnmp99_historica_orden_pago_perma->generateList($this->SQLCA()." and cod_tipo_nomina=$cod_tipo_nomina and ano_documento=$ano_doc", 'numero_nomina ASC', null, '{n}.v_cnmp99_historica_orden_pago_perma.numero_nomina', '{n}.v_cnmp99_historica_orden_pago_perma.numero_nomina');
		$numeros_nom = $this->v_cnmp99_historica_orden_pago_perma->findAll($this->SQLCA()." and cod_tipo_nomina=$cod_tipo_nomina and ano_documento=$ano_doc", 'numero_nomina, concepto', 'numero_nomina ASC');
		if(!empty($numeros_nom)){
			foreach($numeros_nom as $l){
				$v[]=$l["v_cnmp99_historica_orden_pago_perma"]["numero_nomina"];
				$d[]=$l["v_cnmp99_historica_orden_pago_perma"]["numero_nomina"]." -- ".$l["v_cnmp99_historica_orden_pago_perma"]["concepto"];
			}
			$lista_num = array_combine($v, $d);
			$this->set('numeros_nom', $lista_num);
		}else{
			$this->set('numeros_nom', array());
		}
		echo "<script>
				document.getElementById('b_generar').disabled=true;
			</script>";
	}
}


function op_emitidas($cod_tipo_nomina = null, $ano_doc = null, $numero_nom = null) {

    $this->layout="ajax";
    if($cod_tipo_nomina != null && $ano_doc != null){
    $datos_op = $this->v_cnmp99_historica_orden_pago_perma->findAll($this->SQLCA()." and cod_tipo_nomina=$cod_tipo_nomina and ano_documento=$ano_doc and numero_nomina=$numero_nom", 'ano_orden_pago, numero_orden_pago, beneficiario, monto', 'numero_orden_pago ASC');
	if(!empty($datos_op)){
		$this->set('datos_op', $datos_op);
		echo "<script>
				document.getElementById('b_generar').disabled=false;
			</script>";
	}else{
		$this->set('datos_op', array());
		echo "<script>
				document.getElementById('b_generar').disabled=true;
			</script>";
	}
    }else{
    	$this->set('datos_op', array());
		echo "<script>
				document.getElementById('b_generar').disabled=true;
			</script>";
    }
}


function historia_op_emitidas() {

    $this->layout="ajax";
    // $lista = $this->v_cnmp99_historica_orden_pago_perma->generateList($this->SQLCA(), 'cod_tipo_nomina ASC', null, '{n}.v_cnmp99_historica_orden_pago_perma.cod_tipo_nomina', '{n}.v_cnmp99_historica_orden_pago_perma.denominacion');
    $lista = $this->Cnmd01->generateList($this->SQLCA(), 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if(!empty($lista)){
		$this->concatena($lista, 'tipo_nomina');
	}else{
		$this->set('tipo_nomina', array());
	}
}



function reporte_op() {
    $this->layout="pdf";
	$ctipo_nomina = $this->data["cnmp99_orden_pagos"]["cod_tipo_nomina"];
	$ano_documento = $this->data["cnmp99_orden_pagos"]["ano_documento"];
    $datos_op = $this->v_cnmp99_historica_orden_pago_perma->findAll($this->SQLCA()." and cod_tipo_nomina=$ctipo_nomina and ano_documento=$ano_documento", 'cod_tipo_nomina, denominacion, ano_documento, numero_nomina, ano_orden_pago, numero_orden_pago, beneficiario, monto', 'numero_orden_pago ASC');
	if(!empty($datos_op)){
		$this->set('datos_op', $datos_op);
	}else{
		$this->set('datos_op', array());
	}
}


}//fin class
?>
