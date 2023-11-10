<?php
class CambiarConceptoController extends AppController {
   var $name = 'cambiar_concepto';
   var $uses = array('select_orden_compra','cepd03_tipo_documento', 'ccfd04_cierre_mes', 'cstd01_entidades_bancarias', 'ccfd10_descripcion', 'ccfd10_detalles', 'ccfd02', 'ccfd05_numero_asiento',
                            'ccfd04_cuentas_enlace', 'cpcd02', 'cepd01_compromiso_cuerpo', 'cscd04_ordencompra_anticipo_cuerpo', 'select_anticipo_compra',
                            'cscd04_ordencompra_encabezado', 'cscd04_ordencompra_autorizacion_cuerpo', 'cscd03_cotizacion_encabezado', 'cscd02_solicitud_encabezado',
						    'cscd04_ordencompra_encabezado', 'cobd01_contratoobras_anticipo_cuerpo', 'cobd01_contratoobras_valuacion_cuerpo', 'cstd03_cheque_partidas',
						    'cobd01_contratoobras_retencion_cuerpo', 'cepd02_contratoservicio_anticipo_cuerpo', 'cepd02_contratoservicio_valuacion_cuerpo', 'cstd09_notadebito_partidas_pago',
						    'cepd02_contratoservicio_retencion_cuerpo','cobd01_contratoobras_cuerpo', 'cepd02_contratoservicio_cuerpo','cugd05_restriccion_clave',
						    'select_autorizacion_pago', 'v_cepd02_contratoservicio_cuerpo', 'cscd04_ordencompra_autorizacion_cuerpo', 'cscd04_ordencompra_anticipo_cuerpo',
						    'cstd01_sucursales_bancarias', 'cfpd21', 'cfpd22', 'cfpd23', 'cstd03_cheque_cuerpo', 'cstd02_cuentas_bancarias', 'cstd09_notadebito_cuerpo_pago', 'cepd01_compromiso_partidas',
						    'cobd01_contratoobras_partidas', 'cscd04_ordencompra_partidas', 'cepd02_contratoservicio_partidas', 'cepd03_ordenpago_partidas', 'cepd03_ordenpago_cuerpo', 'v_cepd03_ordenpago_compromiso');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap');

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


 function index(){

$this->verifica_entrada('101');

 	$this->layout ="ajax";

 	$this->data =null;

	 	    $tipo_documento = array("1" => "registro de compromisos",
	 	                            "2" => "orden de compra",
	                                "3" => "orden de compra - anticipo",
	                                "4" => "orden de compra - autorizacion pago",
	                                "5" => "contrato obras",
	                                "6" => "contrato obras - anticipo",
	                                "7" => "contrato obras - valuaciones",
	                                "8" => "contrato obras - retenciones",
	                                "9" => "contrato servicio",
	                                "10" => "contrato servicio - anticipo",
	                                "11" => "contrato servicio - valuaciones",
	                                "12" => "contrato servicio - retenciones",
	                                "15"  => "ORDEN DE PAGO",
	                                "13"  => "cheque - orden de pago",
	                                "14"  => "cheque - nota de debito",
                                );
        $this->set('tipo',$tipo_documento);

		$this->set("ano_ejecucion",$this->ano_ejecucion());


 }// fin index







 function nro_documento($year = null, $var=null){

$this->layout ="ajax";


$ano = $this->ano_ejecucion();

$this->Session->write('tipo_documento_aux',$var);

 	switch($var){
				case 1://compromiso
						$this->Session->write('ano_documento',$ano);
						//$numero_documentos = $this->v_cepd03_ordenpago_compromiso->generateList($this->SQLCA()." and ano_documento=".$ano." and condicion_actividad=1   ",'numero_documento ASC', null, '{n}.v_cepd03_ordenpago_compromiso.numero_documento', '{n}.v_cepd03_ordenpago_compromiso.numero_documento');
						//print_r($numero_documentos);
					/*	$numero_documentos=$this->comboBox("v_cepd03_ordenpago_compromiso","numero_documento","deno_select",$this->SQLCA()." and ano_documento=".$ano." and condicion_actividad=1   ");
			//			$numero_documentos = $numero_documentos != null ? $numero_documentos : array();
					*/
					 $rs =  $this->v_cepd03_ordenpago_compromiso->execute("SELECT DISTINCT a.numero_documento, ( SELECT x.condicion_actividad
					           FROM cepd03_ordenpago_cuerpo x
			    		      WHERE x.cod_presi = a.cod_presi AND x.cod_entidad = a.cod_entidad AND x.cod_tipo_inst = a.cod_tipo_inst AND x.cod_inst = a.cod_inst AND x.cod_dep = a.cod_dep AND x.numero_orden_pago = a.numero_orden_pago AND x.ano_orden_pago = a.ano_orden_pago AND x.numero_documento_secuencia::text = a.numero_documento_secuencia::text) AS condicion_op, (mascara_6(a.numero_documento) || ' - '::text) || a.beneficiario::text AS deno_select
			   					FROM cepd01_compromiso_cuerpo a WHERE ".$this->SQLCA()." and ano_documento=".$ano." and condicion_actividad=1 ORDER BY numero_documento ASC");

					    foreach($rs as $l){
							$v[]=$l[0]['numero_documento'];
							$d[]=$l[0]['deno_select'];
						}

						if(isset($v) && count($v)!=0){
							$lista = array_combine($v, $d);
						}else{
							$v[]="";
							$lista = array_combine($v, $v);
						}

	                     $lista = $lista != null ? $lista : array();
						$this->set('numero_documentos',$lista);
						$this->set('tipo_documento',1);
				break;
				case 2://ordenes de compra
							$lista = $this->select_orden_compra->generateList($this->SQLCA()." and ano_orden_compra = ".$ano." AND beneficiario IS NOT NULL"." and (cod_obra='' or cod_obra='0' or cod_obra IS NULL)  and condicion_actividad=1 ", ' numero_orden_compra ASC', null, '{n}.select_orden_compra.numero_orden_compra', '{n}.select_orden_compra.beneficiario');
							$lista = $lista != null ? $lista : array();
							$this->set('numero_documentos',$lista);
							$this->set('tipo_documento',2);
				break;
				case 3://ordenes de compra  anticipo
							$lista = $this->select_orden_compra->generateList($this->SQLCA()." and ano_orden_compra = ".$ano." AND beneficiario IS NOT NULL"." and (cod_obra='' or cod_obra='0' or cod_obra IS NULL)  and condicion_actividad=1 ", ' numero_orden_compra ASC', null, '{n}.select_orden_compra.numero_orden_compra', '{n}.select_orden_compra.beneficiario');
							$lista = $lista != null ? $lista : array();
							$this->set('numero_documentos',$lista);
							$this->set('tipo_documento',3);
				break;
				case 4://ordenes de compra autorizacion pago
                     $lista = $this->select_orden_compra->generateList($this->SQLCA()." and ano_orden_compra = ".$ano." AND beneficiario IS NOT NULL"." and (cod_obra='' or cod_obra='0' or cod_obra IS NULL)  and condicion_actividad=1 ", ' numero_orden_compra ASC', null, '{n}.select_orden_compra.numero_orden_compra', '{n}.select_orden_compra.beneficiario');
					 $lista = $lista != null ? $lista : array();
					 $this->set('numero_documentos',$lista);
					 $this->set('tipo_documento',4);
				break;
				case 5://contrato obras
                     $lista = $this->cobd01_contratoobras_cuerpo->generateList($this->SQLCA()." and ano_contrato_obra='".$ano."'  ", ' numero_contrato_obra ASC', null, '{n}.cobd01_contratoobras_cuerpo.numero_contrato_obra', '{n}.cobd01_contratoobras_cuerpo.numero_contrato_obra');
					 $lista = $lista != null ? $lista : array();
					 $this->set('numero_documentos',$lista);
					 $this->set('tipo_documento',5);
                     //$this->set('tipo_documento',"sin_terminar");
				break;
				case 6://contrato obras anticipo
                     $lista = $this->cobd01_contratoobras_cuerpo->generateList($this->SQLCA()." and ano_contrato_obra='".$ano."'  ", ' numero_contrato_obra ASC', null, '{n}.cobd01_contratoobras_cuerpo.numero_contrato_obra', '{n}.cobd01_contratoobras_cuerpo.numero_contrato_obra');
					 $lista = $lista != null ? $lista : array();
					 $this->set('numero_documentos',$lista);
					 $this->set('tipo_documento',6);
                     //$this->set('tipo_documento',"sin_terminar");
				break;
				case 7://contrato obras valuaciones
                     $lista = $this->cobd01_contratoobras_cuerpo->generateList($this->SQLCA()." and ano_contrato_obra='".$ano."'  ", ' numero_contrato_obra ASC', null, '{n}.cobd01_contratoobras_cuerpo.numero_contrato_obra', '{n}.cobd01_contratoobras_cuerpo.numero_contrato_obra');
					 $lista = $lista != null ? $lista : array();
					 $this->set('numero_documentos',$lista);
					 $this->set('tipo_documento',7);
				break;
				case 8://contrato obras retenciones
                     $lista = $this->cobd01_contratoobras_cuerpo->generateList($this->SQLCA()." and ano_contrato_obra='".$ano."'  ", ' numero_contrato_obra ASC', null, '{n}.cobd01_contratoobras_cuerpo.numero_contrato_obra', '{n}.cobd01_contratoobras_cuerpo.numero_contrato_obra');
					 $lista = $lista != null ? $lista : array();
					 $this->set('numero_documentos',$lista);
					 $this->set('tipo_documento',8);
				break;
				case 9://contrato servicio
                     $lista =  $this->v_cepd02_contratoservicio_cuerpo->generateList($this->SQLCA().' and ano_contrato_servicio='.$ano, 'ano_contrato_servicio, numero_contrato_servicio ASC', null, '{n}.v_cepd02_contratoservicio_cuerpo.numero_contrato_servicio', '{n}.v_cepd02_contratoservicio_cuerpo.deno_numero_contrato_con_rif');
                     $lista = $lista != null ? $lista : array();
					 $this->set('numero_documentos',$lista);
					 $this->set('tipo_documento',9);
                     //$this->set('tipo_documento',"sin_terminar");
				break;
				case 10://contrato servicio anticipo
                     $lista =  $this->v_cepd02_contratoservicio_cuerpo->generateList($this->SQLCA().' and ano_contrato_servicio='.$ano, 'ano_contrato_servicio, numero_contrato_servicio ASC', null, '{n}.v_cepd02_contratoservicio_cuerpo.numero_contrato_servicio', '{n}.v_cepd02_contratoservicio_cuerpo.deno_numero_contrato_con_rif');
                     $lista = $lista != null ? $lista : array();
					 $this->set('numero_documentos',$lista);
					 $this->set('tipo_documento',10);
                     //$this->set('tipo_documento',"sin_terminar");
				break;
				case 11://contrato servicio valuaciones
                     $lista =  $this->v_cepd02_contratoservicio_cuerpo->generateList($this->SQLCA().' and ano_contrato_servicio='.$ano, 'ano_contrato_servicio, numero_contrato_servicio ASC', null, '{n}.v_cepd02_contratoservicio_cuerpo.numero_contrato_servicio', '{n}.v_cepd02_contratoservicio_cuerpo.deno_numero_contrato_con_rif');
                     $lista = $lista != null ? $lista : array();
					 $this->set('numero_documentos',$lista);
					 $this->set('tipo_documento',11);
				break;
				case 12://contrato servicio retenciones
                     $lista =  $this->v_cepd02_contratoservicio_cuerpo->generateList($this->SQLCA().' and ano_contrato_servicio='.$ano, 'ano_contrato_servicio, numero_contrato_servicio ASC', null, '{n}.v_cepd02_contratoservicio_cuerpo.numero_contrato_servicio', '{n}.v_cepd02_contratoservicio_cuerpo.deno_numero_contrato_con_rif');
                     $lista = $lista != null ? $lista : array();
					 $this->set('numero_documentos',$lista);
					 $this->set('tipo_documento',12);
				break;
				case 13://CHEQUES - ORDEN DE PAGO
				      echo "<script type='text/javascript'>ver_documento('/cambiar_concepto/consulta_cheque/1/','datos_parte_1');</script>";
                      $this->set('tipo_documento',13);
				break;
				case 14://CHEQUES - NOTA DE DEBITO
				      echo "<script type='text/javascript'>ver_documento('/cambiar_concepto/consulta_cheque/2/','datos_parte_1');</script>";
                      $this->set('tipo_documento',14);
				break;
				case 15://ORDEN DE PAGO
                     $lista = $this->cepd03_ordenpago_cuerpo->generateList($this->SQLCA()." and ano_orden_pago='".$ano."'  ", ' numero_orden_pago ASC', null, '{n}.cepd03_ordenpago_cuerpo.numero_orden_pago', '{n}.cepd03_ordenpago_cuerpo.beneficiario');
					 $lista = $lista != null ? $lista : array();
					 $this->concatena($lista,'numero_documentos');
					 $this->set('tipo_documento',15);
                     //$this->set('tipo_documento',"sin_terminar");
				break;
			}//fin switch
 }













function cargar($tipo=null, $var1=null, $var2=null, $var3=null){

$this->layout ="ajax";
$ano = $this->ano_ejecucion();



			  if($tipo==1){
							$datos = $this->cepd01_compromiso_cuerpo->findAll($this->SQLCA()." and ano_documento=".$ano." and condicion_actividad=1 and numero_documento=".$var1,array('rif','concepto','cedula_identidad','beneficiario','fecha_documento'));
							$datos = $datos != null ? $datos : array();
							$this->set('concepto', $datos[0]["cepd01_compromiso_cuerpo"]["concepto"]);
		}else if($tipo==2){


			                   $lista                 =  $this->cscd04_ordencompra_encabezado->findAll($this->SQLCA()."  and ano_orden_compra = ".$ano." and numero_orden_compra='".$var1."' "." ");
						  	   foreach($lista as $aux){
						  	   	    $contador_exitentes  = 0;
									$rif                 = $aux['cscd04_ordencompra_encabezado']['rif'];
									$ano_orden_compra    = $aux['cscd04_ordencompra_encabezado']['ano_orden_compra'];
									$numero_orden_compra = $aux['cscd04_ordencompra_encabezado']['numero_orden_compra'];
									$ano_cotizacion      = $aux['cscd04_ordencompra_encabezado']['ano_cotizacion'];
									$numero_cotizacion   = $aux['cscd04_ordencompra_encabezado']['numero_cotizacion'];
									$lista3              = $this->cscd03_cotizacion_encabezado->findAll($this->SQLCA()." and ano_cotizacion = ".$ano_cotizacion." and numero_cotizacion='".$numero_cotizacion."' and rif='".$rif."' ");
					                $ano_solicitud       = $lista3[0]["cscd03_cotizacion_encabezado"]["ano_solicitud"];
					                $numero_solicitud    = $lista3[0]["cscd03_cotizacion_encabezado"]["numero_solicitud"];
						  	   }

			                $datos = $this->cscd02_solicitud_encabezado->findAll($this->SQLCA()." and ano_solicitud=".$ano_solicitud." and numero_solicitud='".$numero_solicitud."' ");
							$datos = $datos != null ? $datos : array();
							$this->set('concepto', $datos[0]["cscd02_solicitud_encabezado"]["uso_destino"]);

	    }else if($tipo==3){

	    	                $datos = $this->cscd04_ordencompra_anticipo_cuerpo->findAll($this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".$var1." and numero_anticipo='".$var2."' ");
							$datos = $datos != null ? $datos : array();
							$this->set('concepto', $datos[0]["cscd04_ordencompra_anticipo_cuerpo"]["observaciones"]);

	    }else if($tipo==4){

	    	                $datos = $this->cscd04_ordencompra_autorizacion_cuerpo->findAll($this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".$var1." and numero_pago='".$var2."' ");
							$datos = $datos != null ? $datos : array();
							$this->set('concepto', $datos[0]["cscd04_ordencompra_autorizacion_cuerpo"]["concepto"]);


	    }else if($tipo==5){

	    	                $datos = $this->cobd01_contratoobras_cuerpo->findAll($this->SQLCA()." and ano_contrato_obra=".$ano." and numero_contrato_obra='".$var1."' ");
							$datos = $datos != null ? $datos : array();
							$this->set('concepto', $datos[0]["cobd01_contratoobras_cuerpo"]["denominacion_obra"]);

	    }else if($tipo==6){

	    	                $datos = $this->cobd01_contratoobras_anticipo_cuerpo->findAll($this->SQLCA()." and ano_contrato_obra=".$ano." and numero_contrato_obra='".$var1."' and numero_anticipo='".$var2."' ");
							$datos = $datos != null ? $datos : array();
							$this->set('concepto', $datos[0]["cobd01_contratoobras_anticipo_cuerpo"]["observaciones"]);

	    }else if($tipo==7){

	    	                $datos = $this->cobd01_contratoobras_valuacion_cuerpo->findAll($this->SQLCA()." and ano_contrato_obra=".$ano." and numero_contrato_obra='".$var1."' and numero_valuacion='".$var2."' ");
							$datos = $datos != null ? $datos : array();
							$this->set('concepto', $datos[0]["cobd01_contratoobras_valuacion_cuerpo"]["concepto"]);

	    }else if($tipo==8){

	    	                $datos = $this->cobd01_contratoobras_retencion_cuerpo->findAll($this->SQLCA()." and ano_contrato_obra=".$ano." and numero_contrato_obra='".$var1."' and numero_retencion='".$var2."' ");
							$datos = $datos != null ? $datos : array();
							$this->set('concepto', $datos[0]["cobd01_contratoobras_retencion_cuerpo"]["concepto"]);

	    }else if($tipo==9){

	    	                $datos = $this->cepd02_contratoservicio_cuerpo->findAll($this->SQLCA()." and ano_contrato_servicio=".$ano." and numero_contrato_servicio='".$var1."' ");
							$datos = $datos != null ? $datos : array();
							$this->set('concepto', $datos[0]["cepd02_contratoservicio_cuerpo"]["concepto"]);

	    }else if($tipo==10){

	    	                $datos = $this->cepd02_contratoservicio_anticipo_cuerpo->findAll($this->SQLCA()." and ano_contrato_servicio=".$ano." and numero_contrato_servicio='".$var1."' and numero_anticipo='".$var2."' ");
							$datos = $datos != null ? $datos : array();
							$this->set('concepto', $datos[0]["cepd02_contratoservicio_anticipo_cuerpo"]["observaciones"]);

	    }else if($tipo==11){

	    	                $datos = $this->cepd02_contratoservicio_valuacion_cuerpo->findAll($this->SQLCA()." and ano_contrato_servicio=".$ano." and numero_contrato_servicio='".$var1."' and numero_valuacion='".$var2."' ");
							$datos = $datos != null ? $datos : array();
							$this->set('concepto', $datos[0]["cepd02_contratoservicio_valuacion_cuerpo"]["concepto"]);

	    }else if($tipo==12){

                            $datos = $this->cepd02_contratoservicio_retencion_cuerpo->findAll($this->SQLCA()." and ano_contrato_servicio=".$ano." and numero_contrato_servicio='".$var1."' and numero_retencion='".$var2."' ");
							$datos = $datos != null ? $datos : array();
							$this->set('concepto', $datos[0]["cepd02_contratoservicio_retencion_cuerpo"]["concepto"]);


		}else if($tipo==13){
									    $radio_1 =  $this->Session->read('radio');
										$cod_1   =  $this->Session->read('cod1');
										$cod_2   =  $this->Session->read('cod2');
										$cod_3   =  $this->Session->read('cod3');
										$ano     =  $this->Session->read('cod4');
										$this->Session->write('cod5',$var1);
										$cond    =  $this->SQLCA();
										$cond   .= " and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria=".$cod_3."  ";
										$cond2   = $this->SQLCA()." and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria='".$cod_3."' and numero_cheque=".$var1;
							if(!isset($_SESSION['cod3'])){$cond2   = $this->SQLCA()." and numero_cheque=".$var1;}
									   $array = $this->cstd03_cheque_cuerpo->findAll($cond2." and ano_movimiento='".$ano."'  ");
									   foreach($array as $aux){
									 	$concepto                                  =   $aux['cstd03_cheque_cuerpo']['concepto'];
									}
							$this->set('concepto',$concepto);
		}else if($tipo==14){
									    $radio_1 =  $this->Session->read('radio');
										$cod_1   =  $this->Session->read('cod1');
										$cod_2   =  $this->Session->read('cod2');
										$cod_3   =  $this->Session->read('cod3');
										$ano     =  $this->Session->read('cod4');
										$this->Session->write('cod5',$var1);
										$cond    =  $this->SQLCA();
										$cond   .= " and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria=".$cod_3."  ";
										$cond2   = $this->SQLCA()." and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria='".$cod_3."' and numero_debito=".$var1;
							if(!isset($_SESSION['cod3'])){$cond2   = $this->SQLCA()." and numero_debito=".$var1;}
									   $array = $this->cstd09_notadebito_cuerpo_pago->findAll($cond2." and ano_movimiento='".$ano."'  ");
									   foreach($array as $aux){
									 	$concepto                                  =   $aux['cstd09_notadebito_cuerpo_pago']['concepto'];
									}
							$this->set('concepto',$concepto);

        }else if($tipo==15){


                            $datos = $this->cepd03_ordenpago_cuerpo->findAll($this->SQLCA()." and ano_orden_pago=".$ano." and numero_orden_pago='".$var1."' ");
							$datos = $datos != null ? $datos : array();
							$this->set('concepto', $datos[0]["cepd03_ordenpago_cuerpo"]["concepto"]);


		}//fin if


}//fin function








function cargar_adjunto($tipo=null, $var1=null, $var2=null){

$this->layout ="ajax";

$ano = $this->ano_ejecucion();


              if($tipo==1){

		}else if($tipo==2){

	    }else if($tipo==3){

	    	                $lista = $this->cscd04_ordencompra_anticipo_cuerpo->generateList($this->SQLCA()."  and ano_orden_compra = ".$ano." and numero_orden_compra='".$var1."' ", ' numero_anticipo ASC', null, '{n}.cscd04_ordencompra_anticipo_cuerpo.numero_anticipo', '{n}.cscd04_ordencompra_anticipo_cuerpo.numero_anticipo');
							$lista = $lista != null ? $lista : array();
							$this->set('numero_documentos',$lista);
							$this->set('select_documentos',$var1);
							$this->set('tipo_documento',3);


	    }else if($tipo==4){

	    	                $lista = $this->cscd04_ordencompra_autorizacion_cuerpo->generateList($this->SQLCA()."  and ano_orden_compra = ".$ano." and numero_orden_compra='".$var1."' ", ' numero_pago ASC', null, '{n}.cscd04_ordencompra_autorizacion_cuerpo.numero_pago', '{n}.cscd04_ordencompra_autorizacion_cuerpo.numero_pago');
							$lista = $lista != null ? $lista : array();
							$this->set('numero_documentos',$lista);
							$this->set('select_documentos',$var1);
							$this->set('tipo_documento',4);

	    }else if($tipo==5){

	    }else if($tipo==6){
                            $lista = $this->cobd01_contratoobras_anticipo_cuerpo->generateList($this->SQLCA()."  and ano_contrato_obra = ".$ano." and numero_contrato_obra='".$var1."' ", ' numero_anticipo ASC', null, '{n}.cobd01_contratoobras_anticipo_cuerpo.numero_anticipo', '{n}.cobd01_contratoobras_anticipo_cuerpo.numero_anticipo');
							$lista = $lista != null ? $lista : array();
							$this->set('numero_documentos',$lista);
							$this->set('select_documentos',$var1);
							$this->set('tipo_documento',6);
	    }else if($tipo==7){
                            $lista = $this->cobd01_contratoobras_valuacion_cuerpo->generateList($this->SQLCA()."  and ano_contrato_obra = ".$ano." and numero_contrato_obra='".$var1."' ", ' numero_valuacion ASC', null, '{n}.cobd01_contratoobras_valuacion_cuerpo.numero_valuacion', '{n}.cobd01_contratoobras_valuacion_cuerpo.numero_valuacion');
							$lista = $lista != null ? $lista : array();
							$this->set('numero_documentos',$lista);
							$this->set('select_documentos',$var1);
							$this->set('tipo_documento',7);
	    }else if($tipo==8){
                            $lista = $this->cobd01_contratoobras_retencion_cuerpo->generateList($this->SQLCA()."  and ano_contrato_obra = ".$ano." and numero_contrato_obra='".$var1."' ", ' numero_retencion ASC', null, '{n}.cobd01_contratoobras_retencion_cuerpo.numero_retencion', '{n}.cobd01_contratoobras_retencion_cuerpo.numero_retencion');
							$lista = $lista != null ? $lista : array();
							$this->set('numero_documentos',$lista);
							$this->set('select_documentos',$var1);
							$this->set('tipo_documento',8);
	    }else if($tipo==9){

	    }else if($tipo==10){
                            $lista = $this->cepd02_contratoservicio_anticipo_cuerpo->generateList($this->SQLCA()."  and ano_contrato_servicio = ".$ano." and numero_contrato_servicio='".$var1."' ", ' numero_anticipo ASC', null, '{n}.cepd02_contratoservicio_anticipo_cuerpo.numero_anticipo', '{n}.cepd02_contratoservicio_anticipo_cuerpo.numero_anticipo');
							$lista = $lista != null ? $lista : array();
							$this->set('numero_documentos',$lista);
							$this->set('select_documentos',$var1);
							$this->set('tipo_documento',10);
	    }else if($tipo==11){
                            $lista = $this->cepd02_contratoservicio_valuacion_cuerpo->generateList($this->SQLCA()."  and ano_contrato_servicio = ".$ano." and numero_contrato_servicio='".$var1."' ", ' numero_valuacion ASC', null, '{n}.cepd02_contratoservicio_valuacion_cuerpo.numero_valuacion', '{n}.cepd02_contratoservicio_valuacion_cuerpo.numero_valuacion');
							$lista = $lista != null ? $lista : array();
							$this->set('numero_documentos',$lista);
							$this->set('select_documentos',$var1);
							$this->set('tipo_documento',11);
	    }else if($tipo==12){
                            $lista = $this->cepd02_contratoservicio_retencion_cuerpo->generateList($this->SQLCA()."  and ano_contrato_servicio = ".$ano." and numero_contrato_servicio='".$var1."' ", ' numero_retencion ASC', null, '{n}.cepd02_contratoservicio_retencion_cuerpo.numero_retencion', '{n}.cepd02_contratoservicio_retencion_cuerpo.numero_retencion');
							$lista = $lista != null ? $lista : array();
							$this->set('numero_documentos',$lista);
							$this->set('select_documentos',$var1);
							$this->set('tipo_documento',12);
		}//fin if


}//fin function












function consulta_cheque($var1=null){
	$this->layout = "ajax";
	$this->set('tipo', $this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'));
	$this->Session->write('tipo_cheque_aux',$var1);
}//fin index





function select_consulta($select=null,$var=null, $var2=null) { //select codigos presupuestarios
	$this->layout = "ajax";
if($select!=null && $var!=null){
		//$cond =$this->SQLCA();
	switch($select){
		case 'sucursal':
			$this->set('SELECT','sucursal');
			$this->set('codigo','sucursal');
			$this->set('seleccion','');
			$this->set('n',2);
			//$this->set('no','no');
			if($var!=null && $var=='consulta'){$this->set('consulta','consulta');}
			$this->Session->write('cod1',$var2);
			$cond =" cod_entidad_bancaria=".$var2;
			$lista = "";
			if($var2!=""){
			  $lista=  $this->cstd01_sucursales_bancarias->generateList($cond, 'cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion');
			}//fin if
			if($lista==""){$lista = array(); $this->set('vector', $lista);}else{$this->set('vector', $lista);}
		break;
		case 'year':
			$this->set('SELECT','year');
			$this->set('codigo','year');
			$this->set('seleccion',$this->ano_ejecucion());
			$this->set('n',3);
			$this->set('no','no');
			$this->set('otro','otro');
			if($var!=null && $var=='consulta'){$this->set('consulta','consulta');}
			if($var!=null && $var=='consulta'){$this->set('year','year');}
			$this->Session->write('cod2',$var2);
			$cod_1 =  $this->Session->read('cod1');
			$cond  =  $this->SQLCA()." and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$var2;
			$lista = "";
			if($var2!=""){
						$rs=$this->cstd03_cheque_cuerpo->execute("SELECT DISTINCT a.ano_movimiento FROM cstd03_cheque_cuerpo a WHERE ". $cond);
					    foreach($rs as $l){
							$lista[$l[0]["ano_movimiento"]]=$l[0]["ano_movimiento"];
						}
			}//fin if
			$year2 = $this->ano_ejecucion();
            if($lista==""){$lista = array(); $this->set('vector',$lista);}else{$this->set('vector',$lista);}
            $this->set('year_ejecucion',$year2);
            echo "<script type='text/javascript'>ver_documento('/cambiar_concepto/select_consulta/cuenta/consulta/".$year2."','st_cuenta');</script>";
		break;
		case 'cuenta':
			$this->set('SELECT','cuenta');
			$this->set('codigo','cuenta');
			$this->set('seleccion','');
			$this->set('n',4);
			$this->set('no','no');
			$this->set('otro','otro');
			if($var!=null && $var=='consulta'){$this->set('consulta','consulta');}
			if($var!=null && $var=='consulta'){$this->set('cuenta','cuenta');}
			$cod_2 =  $this->Session->read('cod2');
			$cod_1 =  $this->Session->read('cod1');
			$this->Session->write('cod4',$var2);
			$cond  =  $this->SQLCA()." and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2;
			$lista = "";
			if($var2!=""){
			    $lista =  $this->cstd02_cuentas_bancarias->generateList($cond, 'cuenta_bancaria ASC', null, '{n}.cstd02_cuentas_bancarias.cuenta_bancaria', '{n}.cstd02_cuentas_bancarias.concepto_manejo');
			}//fin if
            if($lista==""){$lista = array(); $this->set('vector', $lista);}else{$this->set('vector', $lista);}
		break;
	}//fin wsitch
	}else{
			echo "";
	}
}//fin select codigos bancarios





function mostrar($opcion,$var,$codigo=null) {
	$this->layout="ajax";
	if(isset($codigo) && $codigo!=''){
	switch($opcion){
		case 'entidades':
			if(isset($var) && $var=="codigo"){
				//$c=$this->cepd03_ordenpago_tipopago->findByCod_entidad_bancaria($codigo);
				//$this->set("codigo",$c["cepd03_ordenpago_tipopago"]["cod_tipo_pago"]);
				$this->set("codigo",$codigo);
			}else if(isset($var) && $var=="deno"){
				$c=$this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($codigo);
				$this->set("deno",$c["cstd01_entidades_bancarias"]["denominacion"]);
			}

			echo'<script>';
			  echo"document.getElementById('codigo_select_3').innerHTML = '<br>'; ";
			  echo"document.getElementById('deno_select_3').innerHTML = '<br>'; ";
			echo'</script>';
			echo'<script>';
			  echo"document.getElementById('dispo').value = ''; ";
			  echo"document.getElementById('numero_cheque').value = ''; ";
			 // echo"document.getElementById('deno_select_3').value = ''; ";
			echo'</script>';


	break;
		case 'sucursales':
			if(isset($var) && $var=="codigo"){
		//$c=$this->cepd03_ordenpago_tipopago->findByCod_entidad_bancaria($codigo);
		//$this->set("codigo",$c["cepd03_ordenpago_tipopago"]["cod_tipo_pago"]);
		$this->set("codigo",$codigo);
	}else if(isset($var) && $var=="deno"){
		//$c=$this->cstd01_sucursales_bancarias->findByCod_sucursal($codigo);
		$codigo_entBan = $this->Session->read('cod1');// Se lee el codigo de la entidad.
		$c=$this->cstd01_sucursales_bancarias->findAll("cod_entidad_bancaria='$codigo_entBan' and cod_sucursal='$codigo'");
		$this->set("deno",$c[0]["cstd01_sucursales_bancarias"]["denominacion"]);
	}
	break;
	}
	}else{
		echo'<script>';
			  echo"document.getElementById('codigo_select_3').innerHTML = '<br>'; ";
			  echo"document.getElementById('deno_select_3').innerHTML = '<br>'; ";
			echo'</script>';
			echo'<script>';
			  echo"document.getElementById('dispo').value = ''; ";
			  echo"document.getElementById('numero_cheque').value = ''; ";
			 // echo"document.getElementById('deno_select_3').value = ''; ";
			echo'</script>';
		echo "";
	}

}//fin mostrar




function generate_select_numero($var=null){



		$this->layout="ajax";
    	$i = 0;

  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;


  $year2 = $this->ano_ejecucion();
  $this->set('tipo_documento_aux', $this->Session->read('tipo_documento_aux'));


      if($this->Session->read('tipo_documento_aux')==13){
						if(isset($var) && $var!=""){
							    $radio_1 =  $this->Session->read('radio');
								$cod_1   =  $this->Session->read('cod1');
								$cod_2   =  $this->Session->read('cod2');
								$this->Session->write('cod3',$var);
								$ano     =  $this->Session->read('cod4');
								$cond    =  $this->SQLCA();
								$cond   .= " and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria='".$var."' ";
								$cond2   = $this->SQLCA()." and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria='".$var."' and clase_beneficiario=1 ";

					           $lista=  $this->cstd03_cheque_cuerpo->generateList($cond2." and ano_movimiento='".$ano."'    ", 'numero_cheque ASC', null, '{n}.cstd03_cheque_cuerpo.numero_cheque', '{n}.cstd03_cheque_cuerpo.beneficiario');
						}else{$lista="";}//fin else
					$this->concatena($lista,'lista');
  }else if($this->Session->read('tipo_documento_aux')==14){
						if(isset($var) && $var!=""){
							    $radio_1 =  $this->Session->read('radio');
								$cod_1   =  $this->Session->read('cod1');
								$cod_2   =  $this->Session->read('cod2');
								$this->Session->write('cod3',$var);
								$ano     =  $this->Session->read('cod4');
								$cond    =  $this->SQLCA();
								$cond   .= " and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria='".$var."' ";
								$cond2   = $this->SQLCA()." and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria='".$var."' and clase_beneficiario=1  ";
					           $lista=  $this->cstd09_notadebito_cuerpo_pago->generateList($cond2." and ano_movimiento='".$year2."'    ", 'numero_debito ASC', null, '{n}.cstd09_notadebito_cuerpo_pago.numero_debito', '{n}.cstd09_notadebito_cuerpo_pago.beneficiario');
						}else{$lista="";}//fin else
					$this->concatena( $lista, 'lista');
  }//fin else

}//fin function
























function guardar($var1=null, $var2=null, $var3=null){


$this->layout="ajax";

$ano_ejecucion   =  $this->data['cambiar_concepto']['ano_ejecucion'];
$tipo_documento  =  $this->data['cambiar_concepto']['tipo_documento'];
$concepto        =  $this->data['cambiar_concepto']['concepto'];

$sw_begin = $this->cepd01_compromiso_cuerpo->execute("BEGIN;");

              if($tipo_documento==1){
						              	$numero_documento =  $this->data['cambiar_concepto']['numero_documento'];
						              	$dataCompromiso_a = $this->cepd01_compromiso_cuerpo->findAll($this->SQLCA()." and ano_documento=".$ano_ejecucion." and numero_documento='".$numero_documento."'   ");
						              	$dataCompromiso_b = $this->cepd01_compromiso_partidas->findAll($this->SQLCA()." and ano_documento=".$ano_ejecucion." and numero_documento='".$numero_documento."'   ");
						              	$concepto_a       = $dataCompromiso_a[0]["cepd01_compromiso_cuerpo"]["concepto"];
						              	$dia_asiento_registro     = $dataCompromiso_a[0]["cepd01_compromiso_cuerpo"]["dia_asiento_registro"];
						              	$mes_asiento_registro     = $dataCompromiso_a[0]["cepd01_compromiso_cuerpo"]["mes_asiento_registro"];
						              	$ano_asiento_registro     = $dataCompromiso_a[0]["cepd01_compromiso_cuerpo"]["ano_asiento_registro"];
						              	$numero_asiento_registro  = $dataCompromiso_a[0]["cepd01_compromiso_cuerpo"]["numero_asiento_registro"];
						              	$sw               = $this->cepd01_compromiso_cuerpo->execute("UPDATE cepd01_compromiso_cuerpo  set concepto='".$concepto."' where ".$this->condicion()." and ano_documento='".$ano_ejecucion."' and  numero_documento='".$numero_documento."'    ");
						              	if($sw>1){
									              	foreach($dataCompromiso_b as $aux_partida){
									                      $cod_sector                     =   $aux_partida['cepd01_compromiso_partidas']['cod_sector'];
														  $cod_programa                   =   $aux_partida['cepd01_compromiso_partidas']['cod_programa'];
														  $cod_sub_prog                   =   $aux_partida['cepd01_compromiso_partidas']['cod_sub_prog'];
														  $cod_proyecto                   =   $aux_partida['cepd01_compromiso_partidas']['cod_proyecto'];
														  $cod_activ_obra                 =   $aux_partida['cepd01_compromiso_partidas']['cod_activ_obra'];
														  $cod_partida                    =   $aux_partida['cepd01_compromiso_partidas']['cod_partida'];
														  $cod_generica                   =   $aux_partida['cepd01_compromiso_partidas']['cod_generica'];
														  $cod_especifica                 =   $aux_partida['cepd01_compromiso_partidas']['cod_especifica'];
														  $cod_sub_espec                  =   $aux_partida['cepd01_compromiso_partidas']['cod_sub_espec'];
														  $cod_auxiliar                   =   $aux_partida['cepd01_compromiso_partidas']['cod_auxiliar'];
														  $numero_control_compromiso      =   $aux_partida['cepd01_compromiso_partidas']['numero_control_compromiso'];
														  $cond        =   "numero_asiento_compromiso='".$numero_control_compromiso."' and  ".$this->condicion()." and ano='".$ano_ejecucion."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
														  $data_cfpd21 = $this->cfpd21->findAll($cond);
														  $concepto_b  = $data_cfpd21[0]["cfpd21"]["concepto"];
														  $concepto_b  = str_replace($concepto_a, $concepto , $concepto_b);
														  $sw2         = $this->cfpd21->execute("UPDATE cfpd21  set concepto='".$concepto_b."' where ".$cond);
														   if($sw2 > 1){}else{break;}//fin elsee
									              	}//fin foreach
											              	if($sw2 > 1){
															              	$data_ccfd10_descripcion = $this->ccfd10_descripcion->findAll($this->condicion()." and dia_asiento='".$dia_asiento_registro."' and mes_asiento='".$mes_asiento_registro."' and ano_asiento='".$ano_asiento_registro."' and numero_asiento='".$numero_asiento_registro."'  ");
									                                        $concepto_c              = $data_ccfd10_descripcion[0]["ccfd10_descripcion"]["concepto"];
									                                        $concepto_c              = str_replace($concepto_a, $concepto , $concepto_c);
									                                        $sw3                     = $this->ccfd10_descripcion->execute("UPDATE ccfd10_descripcion  set concepto='".$concepto_c."' where ".$this->condicion()." and dia_asiento='".$dia_asiento_registro."' and mes_asiento='".$mes_asiento_registro."' and ano_asiento='".$ano_asiento_registro."' and numero_asiento='".$numero_asiento_registro."'  ");
													              	            if($sw3 > 1){
						                                                               $this->cepd01_compromiso_cuerpo->execute("COMMIT;");
						                                                               $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
													              	            }else{
													              	            	   $this->cepd01_compromiso_cuerpo->execute("ROLLBACK;");
						                                                               $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
													              	            }//fin else
											              	}else{
				                                                    $this->cepd01_compromiso_cuerpo->execute("ROLLBACK;");
				                                                    $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
											              	}//fin else
						              	}else{
                                                    $this->cepd01_compromiso_cuerpo->execute("ROLLBACK;");
                                                    $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
						              	}//fin else
		}else if($tipo_documento==2){
                                        $numero_documento = $this->data['cambiar_concepto']['numero_documento'];
						              	$dataCompromiso_a = $this->cscd04_ordencompra_encabezado->findAll($this->SQLCA()." and ano_orden_compra=".$ano_ejecucion." and numero_orden_compra='".$numero_documento."'   ");
						              	$dataCompromiso_b = $this->cscd04_ordencompra_partidas->findAll($this->SQLCA()." and ano_orden_compra=".$ano_ejecucion." and numero_orden_compra='".$numero_documento."'   ");
			                            foreach($dataCompromiso_a as $aux){
									  	   	    $contador_exitentes  = 0;
												$rif                 = $aux['cscd04_ordencompra_encabezado']['rif'];
												$ano_orden_compra    = $aux['cscd04_ordencompra_encabezado']['ano_orden_compra'];
												$numero_orden_compra = $aux['cscd04_ordencompra_encabezado']['numero_orden_compra'];
												$ano_cotizacion      = $aux['cscd04_ordencompra_encabezado']['ano_cotizacion'];
												$numero_cotizacion   = $aux['cscd04_ordencompra_encabezado']['numero_cotizacion'];
												$lista3              = $this->cscd03_cotizacion_encabezado->findAll($this->SQLCA()." and ano_cotizacion = ".$ano_cotizacion." and numero_cotizacion='".$numero_cotizacion."' and rif='".$rif."' ");
								                $ano_solicitud       = $lista3[0]["cscd03_cotizacion_encabezado"]["ano_solicitud"];
								                $numero_solicitud    = $lista3[0]["cscd03_cotizacion_encabezado"]["numero_solicitud"];
									  	   }
						                $datos = $this->cscd02_solicitud_encabezado->findAll($this->SQLCA()." and ano_solicitud=".$ano_solicitud." and numero_solicitud='".$numero_solicitud."' ");
										$datos = $datos != null ? $datos : array();
										$concepto_a = $datos[0]["cscd02_solicitud_encabezado"]["uso_destino"];
						              	$dia_asiento_registro     = $dataCompromiso_a[0]["cscd04_ordencompra_encabezado"]["dia_asiento_registro"];
						              	$mes_asiento_registro     = $dataCompromiso_a[0]["cscd04_ordencompra_encabezado"]["mes_asiento_registro"];
						              	$ano_asiento_registro     = $dataCompromiso_a[0]["cscd04_ordencompra_encabezado"]["ano_asiento_registro"];
						              	$numero_asiento_registro  = $dataCompromiso_a[0]["cscd04_ordencompra_encabezado"]["numero_asiento_registro"];
						              	$sw               = $this->cscd02_solicitud_encabezado->execute("UPDATE cscd02_solicitud_encabezado  set uso_destino='".$concepto."' where ".$this->SQLCA()." and ano_solicitud=".$ano_solicitud." and numero_solicitud='".$numero_solicitud."' ");
						              	if($sw>1){
									              	foreach($dataCompromiso_b as $aux_partida){
									                      $cod_sector                     =   $aux_partida['cscd04_ordencompra_partidas']['cod_sector'];
														  $cod_programa                   =   $aux_partida['cscd04_ordencompra_partidas']['cod_programa'];
														  $cod_sub_prog                   =   $aux_partida['cscd04_ordencompra_partidas']['cod_sub_prog'];
														  $cod_proyecto                   =   $aux_partida['cscd04_ordencompra_partidas']['cod_proyecto'];
														  $cod_activ_obra                 =   $aux_partida['cscd04_ordencompra_partidas']['cod_activ_obra'];
														  $cod_partida                    =   $aux_partida['cscd04_ordencompra_partidas']['cod_partida'];
														  $cod_generica                   =   $aux_partida['cscd04_ordencompra_partidas']['cod_generica'];
														  $cod_especifica                 =   $aux_partida['cscd04_ordencompra_partidas']['cod_especifica'];
														  $cod_sub_espec                  =   $aux_partida['cscd04_ordencompra_partidas']['cod_sub_espec'];
														  $cod_auxiliar                   =   $aux_partida['cscd04_ordencompra_partidas']['cod_auxiliar'];
														  $numero_asiento_compromiso      =   $aux_partida['cscd04_ordencompra_partidas']['numero_asiento_compromiso'];
														  $cond        =   "numero_asiento_compromiso='".$numero_asiento_compromiso."' and  ".$this->condicion()." and ano='".$ano_ejecucion."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
														  $data_cfpd21 = $this->cfpd21->findAll($cond);
														  $concepto_b  = $data_cfpd21[0]["cfpd21"]["concepto"];
														  $concepto_b  = str_replace($concepto_a, $concepto , $concepto_b);
														  $sw2         = $this->cfpd21->execute("UPDATE cfpd21  set concepto='".$concepto_b."' where ".$cond);
														   if($sw2 > 1){}else{break;}//fin elsee
									              	}//fin foreach
											              	if($sw2 > 1){
															              	$data_ccfd10_descripcion = $this->ccfd10_descripcion->findAll($this->condicion()." and dia_asiento='".$dia_asiento_registro."' and mes_asiento='".$mes_asiento_registro."' and ano_asiento='".$ano_asiento_registro."' and numero_asiento='".$numero_asiento_registro."'  ");
									                                        $concepto_c              = $data_ccfd10_descripcion[0]["ccfd10_descripcion"]["concepto"];
									                                        $concepto_c              = str_replace($concepto_a, $concepto , $concepto_c);
									                                        $sw3                     = $this->ccfd10_descripcion->execute("UPDATE ccfd10_descripcion  set concepto='".$concepto_c."' where ".$this->condicion()." and dia_asiento='".$dia_asiento_registro."' and mes_asiento='".$mes_asiento_registro."' and ano_asiento='".$ano_asiento_registro."' and numero_asiento='".$numero_asiento_registro."'  ");
													              	            if($sw3 > 1){
						                                                               $this->cscd04_ordencompra_encabezado->execute("COMMIT;");
						                                                               $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
													              	            }else{
													              	            	   $this->cscd04_ordencompra_encabezado->execute("ROLLBACK;");
						                                                               $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
													              	            }//fin else
											              	}else{
				                                                    $this->cscd04_ordencompra_encabezado->execute("ROLLBACK;");
				                                                    $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
											              	}//fin else
						              	}else{
                                                    $this->cscd04_ordencompra_encabezado->execute("ROLLBACK;");
                                                    $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
						              	}//fin else
	    }else if($tipo_documento==3){

	    	                            $numero_documento         =  $this->data['cambiar_concepto']['numero_documento'];
	    	                            $numero_documento_adjunto =  $this->data['cambiar_concepto']['numero_documento_adjunto'];
						              	$sw               = $this->cscd04_ordencompra_anticipo_cuerpo->execute("UPDATE cscd04_ordencompra_anticipo_cuerpo  set observaciones='".$concepto."' where ".$this->condicion()." and ano_orden_compra='".$ano_ejecucion."' and  numero_orden_compra='".$numero_documento."' and numero_anticipo='".$numero_documento_adjunto."'    ");
						              	if($sw>1){
                                                   $this->cscd04_ordencompra_anticipo_cuerpo->execute("COMMIT;");
                                                   $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
						              	}else{
                                                    $this->cscd04_ordencompra_anticipo_cuerpo->execute("ROLLBACK;");
                                                    $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
						              	}//fin else

	    }else if($tipo_documento==4){

	    	                            $numero_documento         =  $this->data['cambiar_concepto']['numero_documento'];
	    	                            $numero_documento_adjunto =  $this->data['cambiar_concepto']['numero_documento_adjunto'];
						              	$sw               = $this->cscd04_ordencompra_autorizacion_cuerpo->execute("UPDATE cscd04_ordencompra_autorizacion_pago_cuerpo  set concepto='".$concepto."' where ".$this->condicion()." and ano_orden_compra='".$ano_ejecucion."' and  numero_orden_compra='".$numero_documento."' and numero_pago='".$numero_documento_adjunto."'    ");
						              	if($sw>1){
                                                   $this->cscd04_ordencompra_autorizacion_cuerpo->execute("COMMIT;");
                                                   $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
						              	}else{
                                                    $this->cscd04_ordencompra_autorizacion_cuerpo->execute("ROLLBACK;");
                                                    $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
						              	}//fin else

	    }else if($tipo_documento==5){

	    	                            $numero_documento =  $this->data['cambiar_concepto']['numero_documento'];
						              	$dataCompromiso_a = $this->cobd01_contratoobras_cuerpo->findAll($this->SQLCA()." and ano_contrato_obra=".$ano_ejecucion." and numero_contrato_obra='".$numero_documento."'   ");
						              	$dataCompromiso_b = $this->cobd01_contratoobras_partidas->findAll($this->SQLCA()." and ano_contrato_obra=".$ano_ejecucion." and numero_contrato_obra='".$numero_documento."'   ");
						              	$concepto_a       = $dataCompromiso_a[0]["cobd01_contratoobras_cuerpo"]["denominacion_obra"];
						              	$dia_asiento_registro     = $dataCompromiso_a[0]["cobd01_contratoobras_cuerpo"]["dia_asiento_registro"];
						              	$mes_asiento_registro     = $dataCompromiso_a[0]["cobd01_contratoobras_cuerpo"]["mes_asiento_registro"];
						              	$ano_asiento_registro     = $dataCompromiso_a[0]["cobd01_contratoobras_cuerpo"]["ano_asiento_registro"];
						              	$numero_asiento_registro  = $dataCompromiso_a[0]["cobd01_contratoobras_cuerpo"]["numero_asiento_registro"];
						              	$sw               = $this->cobd01_contratoobras_cuerpo->execute("UPDATE cobd01_contratoobras_cuerpo  set denominacion_obra='".$concepto."' where ".$this->condicion()." and ano_contrato_obra='".$ano_ejecucion."' and  numero_contrato_obra='".$numero_documento."'    ");
						              	if($sw>1){
									              	foreach($dataCompromiso_b as $aux_partida){
									                      $cod_sector                     =   $aux_partida['cobd01_contratoobras_partidas']['cod_sector'];
														  $cod_programa                   =   $aux_partida['cobd01_contratoobras_partidas']['cod_programa'];
														  $cod_sub_prog                   =   $aux_partida['cobd01_contratoobras_partidas']['cod_sub_prog'];
														  $cod_proyecto                   =   $aux_partida['cobd01_contratoobras_partidas']['cod_proyecto'];
														  $cod_activ_obra                 =   $aux_partida['cobd01_contratoobras_partidas']['cod_activ_obra'];
														  $cod_partida                    =   $aux_partida['cobd01_contratoobras_partidas']['cod_partida'];
														  $cod_generica                   =   $aux_partida['cobd01_contratoobras_partidas']['cod_generica'];
														  $cod_especifica                 =   $aux_partida['cobd01_contratoobras_partidas']['cod_especifica'];
														  $cod_sub_espec                  =   $aux_partida['cobd01_contratoobras_partidas']['cod_sub_espec'];
														  $cod_auxiliar                   =   $aux_partida['cobd01_contratoobras_partidas']['cod_auxiliar'];
														  $numero_control_compromiso      =   $aux_partida['cobd01_contratoobras_partidas']['numero_control_compromiso'];
														  $cond        =   "numero_asiento_compromiso='".$numero_control_compromiso."' and  ".$this->condicion()." and ano='".$ano_ejecucion."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
														  $data_cfpd21 = $this->cfpd21->findAll($cond);
														  $concepto_b  = $data_cfpd21[0]["cfpd21"]["concepto"];
														  $concepto_b  = str_replace($concepto_a, $concepto , $concepto_b);
														  $sw2         = $this->cfpd21->execute("UPDATE cfpd21  set concepto='".$concepto_b."' where ".$cond);
														   if($sw2 > 1){}else{break;}//fin elsee
									              	}//fin foreach
											              	if($sw2 > 1){
															              	$data_ccfd10_descripcion = $this->ccfd10_descripcion->findAll($this->condicion()." and dia_asiento='".$dia_asiento_registro."' and mes_asiento='".$mes_asiento_registro."' and ano_asiento='".$ano_asiento_registro."' and numero_asiento='".$numero_asiento_registro."'  ");
									                                        $concepto_c              = $data_ccfd10_descripcion[0]["ccfd10_descripcion"]["concepto"];
									                                        $concepto_c              = str_replace($concepto_a, $concepto , $concepto_c);
									                                        $sw3                     = $this->ccfd10_descripcion->execute("UPDATE ccfd10_descripcion  set concepto='".$concepto_c."' where ".$this->condicion()." and dia_asiento='".$dia_asiento_registro."' and mes_asiento='".$mes_asiento_registro."' and ano_asiento='".$ano_asiento_registro."' and numero_asiento='".$numero_asiento_registro."'  ");
													              	            if($sw3 > 1){
						                                                               $this->cobd01_contratoobras_cuerpo->execute("COMMIT;");
						                                                               $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
													              	            }else{
													              	            	   $this->cobd01_contratoobras_cuerpo->execute("ROLLBACK;");
						                                                               $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
													              	            }//fin else
											              	}else{
				                                                    $this->cobd01_contratoobras_cuerpo->execute("ROLLBACK;");
				                                                    $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
											              	}//fin else
						              	}else{
                                                    $this->cobd01_contratoobras_cuerpo->execute("ROLLBACK;");
                                                    $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
						              	}//fin else

	    }else if($tipo_documento==6){

	    	                            $numero_documento         =  $this->data['cambiar_concepto']['numero_documento'];
	    	                            $numero_documento_adjunto =  $this->data['cambiar_concepto']['numero_documento_adjunto'];
						              	$sw               = $this->cobd01_contratoobras_cuerpo->execute("UPDATE cobd01_contratoobras_anticipo_cuerpo  set observaciones='".$concepto."' where ".$this->condicion()." and ano_contrato_obra='".$ano_ejecucion."' and  numero_contrato_obra='".$numero_documento."' and numero_anticipo='".$numero_documento_adjunto."'    ");
						              	if($sw>1){
                                                   $this->cobd01_contratoobras_anticipo_cuerpo->execute("COMMIT;");
                                                   $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
						              	}else{
                                                    $this->cobd01_contratoobras_anticipo_cuerpo->execute("ROLLBACK;");
                                                    $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
						              	}//fin else

	    }else if($tipo_documento==7){

	    	                            $numero_documento         =  $this->data['cambiar_concepto']['numero_documento'];
	    	                            $numero_documento_adjunto =  $this->data['cambiar_concepto']['numero_documento_adjunto'];
						              	$sw               = $this->cobd01_contratoobras_valuacion_cuerpo->execute("UPDATE cobd01_contratoobras_valuacion_cuerpo  set concepto='".$concepto."' where ".$this->condicion()." and ano_contrato_obra='".$ano_ejecucion."' and  numero_contrato_obra='".$numero_documento."' and numero_valuacion='".$numero_documento_adjunto."'    ");
						              	if($sw>1){
                                                   $this->cobd01_contratoobras_valuacion_cuerpo->execute("COMMIT;");
                                                   $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
						              	}else{
                                                    $this->cobd01_contratoobras_valuacion_cuerpo->execute("ROLLBACK;");
                                                    $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
						              	}//fin else

	    }else if($tipo_documento==8){

	    	                            $numero_documento         =  $this->data['cambiar_concepto']['numero_documento'];
	    	                            $numero_documento_adjunto =  $this->data['cambiar_concepto']['numero_documento_adjunto'];
						              	$sw               = $this->cobd01_contratoobras_retencion_cuerpo->execute("UPDATE cobd01_contratoobras_retencion_cuerpo  set concepto='".$concepto."' where ".$this->condicion()." and ano_contrato_obra='".$ano_ejecucion."' and  numero_contrato_obra='".$numero_documento."' and numero_retencion='".$numero_documento_adjunto."'    ");
						              	if($sw>1){
                                                   $this->cobd01_contratoobras_retencion_cuerpo->execute("COMMIT;");
                                                   $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
						              	}else{
                                                    $this->cobd01_contratoobras_retencion_cuerpo->execute("ROLLBACK;");
                                                    $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
						              	}//fin else

	    }else if($tipo_documento==9){

	    	                            $numero_documento =  $this->data['cambiar_concepto']['numero_documento'];
						              	$dataCompromiso_a = $this->cepd02_contratoservicio_cuerpo->findAll($this->SQLCA()." and ano_contrato_servicio=".$ano_ejecucion." and numero_contrato_servicio='".$numero_documento."'   ");
						              	$dataCompromiso_b = $this->cepd02_contratoservicio_partidas->findAll($this->SQLCA()." and ano_contrato_servicio=".$ano_ejecucion." and numero_contrato_servicio='".$numero_documento."'   ");
						              	$concepto_a       = $dataCompromiso_a[0]["cepd02_contratoservicio_cuerpo"]["concepto"];
						              	$dia_asiento_registro     = $dataCompromiso_a[0]["cepd02_contratoservicio_cuerpo"]["dia_asiento_registro"];
						              	$mes_asiento_registro     = $dataCompromiso_a[0]["cepd02_contratoservicio_cuerpo"]["mes_asiento_registro"];
						              	$ano_asiento_registro     = $dataCompromiso_a[0]["cepd02_contratoservicio_cuerpo"]["ano_asiento_registro"];
						              	$numero_asiento_registro  = $dataCompromiso_a[0]["cepd02_contratoservicio_cuerpo"]["numero_asiento_registro"];
						              	$sw               = $this->cepd02_contratoservicio_cuerpo->execute("UPDATE cepd02_contratoservicio_cuerpo  set concepto='".$concepto."' where ".$this->condicion()." and ano_contrato_servicio='".$ano_ejecucion."' and  numero_contrato_servicio='".$numero_documento."'    ");
						              	if($sw>1){
									              	foreach($dataCompromiso_b as $aux_partida){
									                      $cod_sector                     =   $aux_partida['cepd02_contratoservicio_partidas']['cod_sector'];
														  $cod_programa                   =   $aux_partida['cepd02_contratoservicio_partidas']['cod_programa'];
														  $cod_sub_prog                   =   $aux_partida['cepd02_contratoservicio_partidas']['cod_sub_prog'];
														  $cod_proyecto                   =   $aux_partida['cepd02_contratoservicio_partidas']['cod_proyecto'];
														  $cod_activ_obra                 =   $aux_partida['cepd02_contratoservicio_partidas']['cod_activ_obra'];
														  $cod_partida                    =   $aux_partida['cepd02_contratoservicio_partidas']['cod_partida'];
														  $cod_generica                   =   $aux_partida['cepd02_contratoservicio_partidas']['cod_generica'];
														  $cod_especifica                 =   $aux_partida['cepd02_contratoservicio_partidas']['cod_especifica'];
														  $cod_sub_espec                  =   $aux_partida['cepd02_contratoservicio_partidas']['cod_sub_espec'];
														  $cod_auxiliar                   =   $aux_partida['cepd02_contratoservicio_partidas']['cod_auxiliar'];
														  $numero_control_compromiso      =   $aux_partida['cepd02_contratoservicio_partidas']['numero_control_compromiso'];
														  $cond        =   "numero_asiento_compromiso='".$numero_control_compromiso."' and  ".$this->condicion()." and ano='".$ano_ejecucion."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
														  $data_cfpd21 = $this->cfpd21->findAll($cond);
														  $concepto_b  = $data_cfpd21[0]["cfpd21"]["concepto"];
														  $concepto_b  = str_replace($concepto_a, $concepto , $concepto_b);
														  $sw2         = $this->cfpd21->execute("UPDATE cfpd21  set concepto='".$concepto_b."' where ".$cond);
														   if($sw2 > 1){}else{break;}//fin elsee
									              	}//fin foreach
											              	if($sw2 > 1){
															              	$data_ccfd10_descripcion = $this->ccfd10_descripcion->findAll($this->condicion()." and dia_asiento='".$dia_asiento_registro."' and mes_asiento='".$mes_asiento_registro."' and ano_asiento='".$ano_asiento_registro."' and numero_asiento='".$numero_asiento_registro."'  ");
									                                        $concepto_c              = $data_ccfd10_descripcion[0]["ccfd10_descripcion"]["concepto"];
									                                        $concepto_c              = str_replace($concepto_a, $concepto , $concepto_c);
									                                        $sw3                     = $this->ccfd10_descripcion->execute("UPDATE ccfd10_descripcion  set concepto='".$concepto_c."' where ".$this->condicion()." and dia_asiento='".$dia_asiento_registro."' and mes_asiento='".$mes_asiento_registro."' and ano_asiento='".$ano_asiento_registro."' and numero_asiento='".$numero_asiento_registro."'  ");
													              	            if($sw3 > 1){
						                                                               $this->cepd02_contratoservicio_cuerpo->execute("COMMIT;");
						                                                               $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
													              	            }else{
													              	            	   $this->cepd02_contratoservicio_cuerpo->execute("ROLLBACK;");
						                                                               $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
													              	            }//fin else
											              	}else{
				                                                    $this->cepd02_contratoservicio_cuerpo->execute("ROLLBACK;");
				                                                    $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
											              	}//fin else
						              	}else{
                                                    $this->cepd02_contratoservicio_cuerpo->execute("ROLLBACK;");
                                                    $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
						              	}//fin else

	    }else if($tipo_documento==10){

	    	                            $numero_documento         =  $this->data['cambiar_concepto']['numero_documento'];
	    	                            $numero_documento_adjunto =  $this->data['cambiar_concepto']['numero_documento_adjunto'];
						              	$sw               = $this->cepd02_contratoservicio_anticipo_cuerpo->execute("UPDATE cepd02_contratoservicio_anticipo_cuerpo  set observaciones='".$concepto."' where ".$this->condicion()." and ano_contrato_servicio='".$ano_ejecucion."' and  numero_contrato_servicio='".$numero_documento."' and numero_anticipo='".$numero_documento_adjunto."'    ");
						              	if($sw>1){
                                                   $this->cepd02_contratoservicio_anticipo_cuerpo->execute("COMMIT;");
                                                   $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
						              	}else{
                                                    $this->cepd02_contratoservicio_anticipo_cuerpo->execute("ROLLBACK;");
                                                    $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
						              	}//fin else

	    }else if($tipo_documento==11){

	    	                            $numero_documento         =  $this->data['cambiar_concepto']['numero_documento'];
	    	                            $numero_documento_adjunto =  $this->data['cambiar_concepto']['numero_documento_adjunto'];
						              	$sw               = $this->cepd02_contratoservicio_valuacion_cuerpo->execute("UPDATE cepd02_contratoservicio_valuacion_cuerpo  set concepto='".$concepto."' where ".$this->condicion()." and ano_contrato_servicio='".$ano_ejecucion."' and  numero_contrato_servicio='".$numero_documento."' and numero_valuacion='".$numero_documento_adjunto."'    ");
						              	if($sw>1){
                                                   $this->cepd02_contratoservicio_valuacion_cuerpo->execute("COMMIT;");
                                                   $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
						              	}else{
                                                    $this->cepd02_contratoservicio_valuacion_cuerpo->execute("ROLLBACK;");
                                                    $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
						              	}//fin else

	    }else if($tipo_documento==12){

	    	                            $numero_documento         =  $this->data['cambiar_concepto']['numero_documento'];
	    	                            $numero_documento_adjunto =  $this->data['cambiar_concepto']['numero_documento_adjunto'];
						              	$sw               = $this->cepd02_contratoservicio_retencion_cuerpo->execute("UPDATE cepd02_contratoservicio_retencion_cuerpo  set concepto='".$concepto."' where ".$this->condicion()." and ano_contrato_servicio='".$ano_ejecucion."' and  numero_contrato_servicio='".$numero_documento."' and numero_retencion='".$numero_documento_adjunto."'    ");
						              	if($sw>1){
                                                   $this->cepd02_contratoservicio_retencion_cuerpo->execute("COMMIT;");
                                                   $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
						              	}else{
                                                    $this->cepd02_contratoservicio_retencion_cuerpo->execute("ROLLBACK;");
                                                    $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
						              	}//fin else

	    }else if($tipo_documento==13){

										$cod_1   =  $this->Session->read('cod1');
										$cod_2   =  $this->Session->read('cod2');
										$cod_3   =  $this->Session->read('cod3');
										$ano     =  $this->Session->read('cod4');
										$num     =  $this->Session->read('cod5');
										$cond_a  = $this->SQLCA()." and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria='".$cod_3."' and numero_cheque=".$num;

										$dataCompromiso_a = $this->cstd03_cheque_cuerpo->findAll($cond_a);
						              	$dataCompromiso_b = $this->cstd03_cheque_partidas->findAll($cond_a);

						              	$dia_asiento_registro     = $dataCompromiso_a[0]["cstd03_cheque_cuerpo"]["dia_asiento_registro"];
						              	$mes_asiento_registro     = $dataCompromiso_a[0]["cstd03_cheque_cuerpo"]["mes_asiento_registro"];
						              	$ano_asiento_registro     = $dataCompromiso_a[0]["cstd03_cheque_cuerpo"]["ano_asiento_registro"];
						              	$numero_asiento_registro  = $dataCompromiso_a[0]["cstd03_cheque_cuerpo"]["numero_asiento_registro"];
						              	$concepto_a               = $dataCompromiso_a[0]["cstd03_cheque_cuerpo"]["concepto"];
						              	$sw               = $this->cscd02_solicitud_encabezado->execute("UPDATE cstd03_cheque_cuerpo  set concepto='".$concepto."' where ".$cond_a);
						              	if($sw>1){
									              	foreach($dataCompromiso_b as $aux_partida){
									                      $cod_sector                     =   $aux_partida['cstd03_cheque_partidas']['cod_sector'];
														  $cod_programa                   =   $aux_partida['cstd03_cheque_partidas']['cod_programa'];
														  $cod_sub_prog                   =   $aux_partida['cstd03_cheque_partidas']['cod_sub_prog'];
														  $cod_proyecto                   =   $aux_partida['cstd03_cheque_partidas']['cod_proyecto'];
														  $cod_activ_obra                 =   $aux_partida['cstd03_cheque_partidas']['cod_activ_obra'];
														  $cod_partida                    =   $aux_partida['cstd03_cheque_partidas']['cod_partida'];
														  $cod_generica                   =   $aux_partida['cstd03_cheque_partidas']['cod_generica'];
														  $cod_especifica                 =   $aux_partida['cstd03_cheque_partidas']['cod_especifica'];
														  $cod_sub_espec                  =   $aux_partida['cstd03_cheque_partidas']['cod_sub_espec'];
														  $cod_auxiliar                   =   $aux_partida['cstd03_cheque_partidas']['cod_auxiliar'];
														  $numero_asiento_compromiso      =   $aux_partida['cstd03_cheque_partidas']['numero_control_compromiso'];
														  $numero_asiento_causado         =   $aux_partida['cstd03_cheque_partidas']['numero_control_causado'];
														  $numero_asiento_pagado          =   $aux_partida['cstd03_cheque_partidas']['numero_control_pagado'];
														  $cond        =   "numero_asiento_pagado='".$numero_asiento_pagado."' and numero_asiento_causado='".$numero_asiento_causado."' and numero_asiento_compromiso='".$numero_asiento_compromiso."' and  ".$this->condicion()." and ano='".$ano_ejecucion."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
														  $data_cfpd23 = $this->cfpd23->findAll($cond);
														  $concepto_b  = $data_cfpd23[0]["cfpd23"]["concepto"];
														  $concepto_b  = str_replace($concepto_a, $concepto , $concepto_b);
														  $sw2         = $this->cfpd23->execute("UPDATE cfpd23  set concepto='".$concepto_b."' where ".$cond);
														   if($sw2 > 1){}else{break;}//fin elsee
									              	}//fin foreach
											              	if($sw2 > 1){
															              	$data_ccfd10_descripcion = $this->ccfd10_descripcion->findAll($this->condicion()." and dia_asiento='".$dia_asiento_registro."' and mes_asiento='".$mes_asiento_registro."' and ano_asiento='".$ano_asiento_registro."' and numero_asiento='".$numero_asiento_registro."'  ");
									                                        $concepto_c              = $data_ccfd10_descripcion[0]["ccfd10_descripcion"]["concepto"];
									                                        $concepto_c              = str_replace($concepto_a, $concepto , $concepto_c);
									                                        $sw3                     = $this->ccfd10_descripcion->execute("UPDATE ccfd10_descripcion  set concepto='".$concepto_c."' where ".$this->condicion()." and dia_asiento='".$dia_asiento_registro."' and mes_asiento='".$mes_asiento_registro."' and ano_asiento='".$ano_asiento_registro."' and numero_asiento='".$numero_asiento_registro."'  ");
													              	            if($sw3 > 1){
						                                                               $this->cstd03_cheque_cuerpo->execute("COMMIT;");
						                                                               $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
													              	            }else{
													              	            	   $this->cstd03_cheque_cuerpo->execute("ROLLBACK;");
						                                                               $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
													              	            }//fin else
											              	}else{
				                                                    $this->cstd03_cheque_cuerpo->execute("ROLLBACK;");
				                                                    $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
											              	}//fin else
						              	}else{
                                                    $this->cstd03_cheque_cuerpo->execute("ROLLBACK;");
                                                    $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
						              	}//fin else

	    }else if($tipo_documento==14){


                                        $cod_1   =  $this->Session->read('cod1');
										$cod_2   =  $this->Session->read('cod2');
										$cod_3   =  $this->Session->read('cod3');
										$ano     =  $this->Session->read('cod4');
										$num     =  $this->Session->read('cod5');
										$cond_a  = $this->SQLCA()." and cod_entidad_bancaria=".$cod_1." and cod_sucursal=".$cod_2." and cuenta_bancaria='".$cod_3."' and numero_debito=".$num;

										$dataCompromiso_a = $this->cstd09_notadebito_cuerpo_pago->findAll($cond_a);
						              	$dataCompromiso_b = $this->cstd09_notadebito_partidas_pago->findAll($cond_a);

						              	$dia_asiento_registro     = $dataCompromiso_a[0]["cstd09_notadebito_cuerpo_pago"]["dia_asiento_registro"];
						              	$mes_asiento_registro     = $dataCompromiso_a[0]["cstd09_notadebito_cuerpo_pago"]["mes_asiento_registro"];
						              	$ano_asiento_registro     = $dataCompromiso_a[0]["cstd09_notadebito_cuerpo_pago"]["ano_asiento_registro"];
						              	$numero_asiento_registro  = $dataCompromiso_a[0]["cstd09_notadebito_cuerpo_pago"]["numero_asiento_registro"];
						              	$concepto_a               = $dataCompromiso_a[0]["cstd09_notadebito_cuerpo_pago"]["concepto"];
						              	$sw               = $this->cscd02_solicitud_encabezado->execute("UPDATE cstd09_notadebito_cuerpo  set concepto='".$concepto."' where ".$cond_a);
						              	if($sw>1){
									              	foreach($dataCompromiso_b as $aux_partida){
									                      $cod_sector                     =   $aux_partida['cstd09_notadebito_partidas_pago']['cod_sector'];
														  $cod_programa                   =   $aux_partida['cstd09_notadebito_partidas_pago']['cod_programa'];
														  $cod_sub_prog                   =   $aux_partida['cstd09_notadebito_partidas_pago']['cod_sub_prog'];
														  $cod_proyecto                   =   $aux_partida['cstd09_notadebito_partidas_pago']['cod_proyecto'];
														  $cod_activ_obra                 =   $aux_partida['cstd09_notadebito_partidas_pago']['cod_activ_obra'];
														  $cod_partida                    =   $aux_partida['cstd09_notadebito_partidas_pago']['cod_partida'];
														  $cod_generica                   =   $aux_partida['cstd09_notadebito_partidas_pago']['cod_generica'];
														  $cod_especifica                 =   $aux_partida['cstd09_notadebito_partidas_pago']['cod_especifica'];
														  $cod_sub_espec                  =   $aux_partida['cstd09_notadebito_partidas_pago']['cod_sub_espec'];
														  $cod_auxiliar                   =   $aux_partida['cstd09_notadebito_partidas_pago']['cod_auxiliar'];
														  $numero_asiento_compromiso      =   $aux_partida['cstd09_notadebito_partidas_pago']['numero_control_compromiso'];
														  $numero_asiento_causado         =   $aux_partida['cstd09_notadebito_partidas_pago']['numero_control_causado'];
														  $numero_asiento_pagado          =   $aux_partida['cstd09_notadebito_partidas_pago']['numero_control_pagado'];
														  $cond        =   "numero_asiento_pagado='".$numero_asiento_pagado."' and numero_asiento_causado='".$numero_asiento_causado."' and numero_asiento_compromiso='".$numero_asiento_compromiso."' and  ".$this->condicion()." and ano='".$ano_ejecucion."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
														  $data_cfpd23 = $this->cfpd23->findAll($cond);
														  $concepto_b  = $data_cfpd23[0]["cfpd23"]["concepto"];
														  $concepto_b  = str_replace($concepto_a, $concepto , $concepto_b);
														  $sw2         = $this->cfpd23->execute("UPDATE cfpd23  set concepto='".$concepto_b."' where ".$cond);
														   if($sw2 > 1){}else{break;}//fin elsee
									              	}//fin foreach
											              	if($sw2 > 1){
															              	$data_ccfd10_descripcion = $this->ccfd10_descripcion->findAll($this->condicion()." and dia_asiento='".$dia_asiento_registro."' and mes_asiento='".$mes_asiento_registro."' and ano_asiento='".$ano_asiento_registro."' and numero_asiento='".$numero_asiento_registro."'  ");
									                                        $concepto_c              = $data_ccfd10_descripcion[0]["ccfd10_descripcion"]["concepto"];
									                                        $concepto_c              = str_replace($concepto_a, $concepto , $concepto_c);
									                                        $sw3                     = $this->ccfd10_descripcion->execute("UPDATE ccfd10_descripcion  set concepto='".$concepto_c."' where ".$this->condicion()." and dia_asiento='".$dia_asiento_registro."' and mes_asiento='".$mes_asiento_registro."' and ano_asiento='".$ano_asiento_registro."' and numero_asiento='".$numero_asiento_registro."'  ");
													              	            if($sw3 > 1){
						                                                               $this->cstd09_notadebito_cuerpo_pago->execute("COMMIT;");
						                                                               $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
													              	            }else{
													              	            	   $this->cstd09_notadebito_cuerpo_pago->execute("ROLLBACK;");
						                                                               $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
													              	            }//fin else
											              	}else{
				                                                    $this->cstd09_notadebito_cuerpo_pago->execute("ROLLBACK;");
				                                                    $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
											              	}//fin else
						              	}else{
                                                    $this->cstd09_notadebito_cuerpo_pago->execute("ROLLBACK;");
                                                    $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
						              	}//fin else


	    }else if($tipo_documento==15){

	    	                            $numero_documento =  $this->data['cambiar_concepto']['numero_documento'];
						              	$dataCompromiso_a = $this->cepd03_ordenpago_cuerpo->findAll($this->SQLCA()." and ano_orden_pago=".$ano_ejecucion." and numero_orden_pago='".$numero_documento."'   ");
						              	$dataCompromiso_b = $this->cepd03_ordenpago_partidas->findAll($this->SQLCA()." and ano_orden_pago=".$ano_ejecucion." and numero_orden_pago='".$numero_documento."'   ");
						              	$concepto_a       = $dataCompromiso_a[0]["cepd03_ordenpago_cuerpo"]["concepto"];
						              	$dia_asiento_registro     = $dataCompromiso_a[0]["cepd03_ordenpago_cuerpo"]["dia_asiento_registro"];
						              	$mes_asiento_registro     = $dataCompromiso_a[0]["cepd03_ordenpago_cuerpo"]["mes_asiento_registro"];
						              	$ano_asiento_registro     = $dataCompromiso_a[0]["cepd03_ordenpago_cuerpo"]["ano_asiento_registro"];
						              	$numero_asiento_registro  = $dataCompromiso_a[0]["cepd03_ordenpago_cuerpo"]["numero_asiento_registro"];
						              	$sw               = $this->cepd03_ordenpago_cuerpo->execute("UPDATE cepd03_ordenpago_cuerpo  set concepto='".$concepto."' where ".$this->condicion()." and ano_orden_pago='".$ano_ejecucion."' and  numero_orden_pago='".$numero_documento."'    ");
						              	if($sw>1){
									              	foreach($dataCompromiso_b as $aux_partida){
									                      $cod_sector                     =   $aux_partida['cepd03_ordenpago_partidas']['cod_sector'];
														  $cod_programa                   =   $aux_partida['cepd03_ordenpago_partidas']['cod_programa'];
														  $cod_sub_prog                   =   $aux_partida['cepd03_ordenpago_partidas']['cod_sub_prog'];
														  $cod_proyecto                   =   $aux_partida['cepd03_ordenpago_partidas']['cod_proyecto'];
														  $cod_activ_obra                 =   $aux_partida['cepd03_ordenpago_partidas']['cod_activ_obra'];
														  $cod_partida                    =   $aux_partida['cepd03_ordenpago_partidas']['cod_partida'];
														  $cod_generica                   =   $aux_partida['cepd03_ordenpago_partidas']['cod_generica'];
														  $cod_especifica                 =   $aux_partida['cepd03_ordenpago_partidas']['cod_especifica'];
														  $cod_sub_espec                  =   $aux_partida['cepd03_ordenpago_partidas']['cod_sub_espec'];
														  $cod_auxiliar                   =   $aux_partida['cepd03_ordenpago_partidas']['cod_auxiliar'];
														  $numero_control_compromiso      =   $aux_partida['cepd03_ordenpago_partidas']['numero_control_compromiso'];
														  $numero_control_causado         =   $aux_partida['cepd03_ordenpago_partidas']['numero_control_causado'];
														  $cond        =   "numero_asiento_causado='".$numero_control_causado."' and numero_asiento_compromiso='".$numero_control_compromiso."' and  ".$this->condicion()." and ano='".$ano_ejecucion."' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
														  $data_cfpd22 = $this->cfpd22->findAll($cond);
														  $concepto_b  = $data_cfpd22[0]["cfpd22"]["concepto"];
														  $concepto_b  = str_replace($concepto_a, $concepto , $concepto_b);
														  $sw2         = $this->cfpd22->execute("UPDATE cfpd22  set concepto='".$concepto_b."' where ".$cond);
														   if($sw2 > 1){}else{break;}//fin elsee
									              	}//fin foreach
											              	if($sw2 > 1){
															              	$data_ccfd10_descripcion = $this->ccfd10_descripcion->findAll($this->condicion()." and dia_asiento='".$dia_asiento_registro."' and mes_asiento='".$mes_asiento_registro."' and ano_asiento='".$ano_asiento_registro."' and numero_asiento='".$numero_asiento_registro."'  ");
									                                        $concepto_c              = $data_ccfd10_descripcion[0]["ccfd10_descripcion"]["concepto"];
									                                        $concepto_c              = str_replace($concepto_a, $concepto , $concepto_c);
									                                        $sw3                     = $this->ccfd10_descripcion->execute("UPDATE ccfd10_descripcion  set concepto='".$concepto_c."' where ".$this->condicion()." and dia_asiento='".$dia_asiento_registro."' and mes_asiento='".$mes_asiento_registro."' and ano_asiento='".$ano_asiento_registro."' and numero_asiento='".$numero_asiento_registro."'  ");
													              	            if($sw3 > 1){
						                                                               $this->cepd03_ordenpago_cuerpo->execute("COMMIT;");
						                                                               $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
													              	            }else{
													              	            	   $this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
						                                                               $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
													              	            }//fin else
											              	}else{
				                                                    $this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
				                                                    $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
											              	}//fin else
						              	}else{
                                                    $this->cepd03_ordenpago_cuerpo->execute("ROLLBACK;");
                                                    $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
						              	}//fin else

		}//fin if



$this->index();
$this->render("index");

}//fin function











}//Fin de la clase controller
?>