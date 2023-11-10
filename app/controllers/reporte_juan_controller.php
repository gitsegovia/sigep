<?php

class ReporteJuanController extends AppController {

    var $name = "reporte_juan";
    var $uses = array('cfpd01_formulacion', 'arrd05', 'ccfd04_cierre_mes', 'v_balance_ejecucion', 'v_balance_ejecucion2', 'v_balance_ejecucion_inst', 'v_balance_ejecucion2_inst', 'v_cfpd05_denominaciones', 'v_cfpd05_tipo_gasto',
        'cfpd10_reformulacion_texto', 'cfpd05', 'v_analisis_presupuesto', 'cfpd10_reformulacion_partidas', 'cfpd10_reformulacion_texto', 'cscd04_ordencompra_encabezado', 'cscd04_ordencompra_partidas',
        'cobd01_contratoobras_partidas', 'cobd01_contratoobras_cuerpo', 'cepd02_contratoservicio_partidas', 'cepd02_contratoservicio_cuerpo', 'cepd01_compromiso_partidas', 'cepd01_compromiso_cuerpo',
        'cepd03_ordenpago_partidas', 'cepd03_ordenpago_cuerpo', 'cstd03_cheque_partidas', 'cstd03_cheque_cuerpo', "v_cfpd05_asignacion_corriente_capital", 'cstd07_retenciones_cuerpo_timbre', 'cepd03_ordenpago_facturas',
        'cepd03_ordenpago_cuerpo', 'cscd04_ordencompra_encabezado', 'cscd04_ordencompra_autorizacion_cuerpo', 'v_cscd04_ordencompra_completa', 'cscd04_ordencompra_anticipo_cuerpo', 'cpcd02', 'cugd03_acta_anulacion_cuerpo', 'cstd03_cheque_cuerpo', 'cstd03_movimientos_manuales', 'cepd01_compromiso_cuerpo', 'cepd03_ordenpago_tipopago', 'cepd01_tipo_compromiso', 'cstd04_movimientos_generales', 'v_cstd_mov_gral', 'v_proyeccion_gasto_inst', 'v_proyeccion_gasto_dep', 'v_credito_agrupado', 'v_credito_agrupado_inst', 'v_credito_agrupado_dep', 'cobd01_contratoobras_anticipo_cuerpo', 'cobd01_contratoobras_valuacion_cuerpo', 'cepd02_contratoservicio_anticipo_cuerpo', 'cepd02_contratoservicio_valuacion_cuerpo', 'cstd01_entidades_bancarias', 'v_credito_presupuestario_dependencia',
        'cugd07_firmas_oficio_anulacion', 'cobd01_contratoobras_cuerpo', 'cobd01_contratoobras_valuacion_cuerpo', 'cobd01_contratoobras_retencion_cuerpo', 'cepd02_contratoservicio_valuacion_partidas', 'cepd02_contratoservicio_retencion_cuerpo', 'cfpd07_obras_cuerpo',
        'ccfd01_tipo', 'ccfd01_cuenta', 'ccfd01_subcuenta', 'ccfd01_division', 'cugd01_estados', 'cugd02_direccionsuperior', 'ccfd01_subdivision', 'Cnmd01', 'v_cscd04_ordencompra', 'v_cfpd05_tipo_gasto2', 'cugd02_dependencia', 'v_cfpd05_denominaciones', 'v_cfpd97', 'cstd01_sucursales_bancarias', 'cstd02_cuentas_bancarias');
    var $helpers = array('Html', 'Ajax', 'Javascript', 'Sisap', 'Fpdf');

    function checkSession() {
        if (!$this->Session->check('Usuario')) {
            $this->redirect('/salir/');
            exit();
        } else {
            //$this->set('userTable', $this->requestAction('/cnmp03partidas/', array('return')));
            //echo "H".$this->requestAction('/usuarios/actualizar_user',array('return'));
            $this->requestAction('/usuarios/actualizar_user');
        }
    }

//fin checksession

    function SQLCA($ano = null) {//sql para busqueda de codigos de arranque con y sin año
        $sql_re = "cod_presi=" . $this->verifica_SS(1) . "  and    ";
        $sql_re .= "cod_entidad=" . $this->verifica_SS(2) . "  and  ";
        $sql_re .= "cod_tipo_inst=" . $this->verifica_SS(3) . "  and ";
        $sql_re .= "cod_inst=" . $this->verifica_SS(4) . "  and  ";
        if ($ano != null) {
            $sql_re .= "cod_dep=" . $this->verifica_SS(5) . "  and  ";
            $sql_re .= "ano=" . $ano . "  ";
        } else {
            $sql_re .= "cod_dep=" . $this->verifica_SS(5) . " ";
        }
        return $sql_re;
    }

//fin funcion SQLCA

    function SQLCA_report($pre = null) {
        $sql_re = "cod_presi=" . $this->verifica_SS(1) . "  and    ";
        $sql_re .= "cod_entidad=" . $this->verifica_SS(2) . "  and  ";
        $sql_re .= "cod_tipo_inst=" . $this->verifica_SS(3) . "  and ";
        if ($pre != null && $pre == 1) {
            $sql_re .= "cod_inst=" . $this->verifica_SS(4) . " ";
            //$sql_re .= "cod_dep=0";
        } else {
            $sql_re .= "cod_inst=" . $this->verifica_SS(4) . "  and  ";
            $sql_re .= "cod_dep=" . $this->verifica_SS(5) . " ";
        }

        return $sql_re;
    }

//fin funcion SQLCA

    function SQLCA_report_a($pre = null) {
        $sql_re = "a.cod_presi=" . $this->verifica_SS(1) . "  and    ";
        $sql_re .= "a.cod_entidad=" . $this->verifica_SS(2) . "  and  ";
        $sql_re .= "a.cod_tipo_inst=" . $this->verifica_SS(3) . "  and ";
        if ($pre != null && $pre == 1) {
            $sql_re .= "a.cod_inst=" . $this->verifica_SS(4) . " ";
            //$sql_re .= "cod_dep=0";
        } else {
            $sql_re .= "a.cod_inst=" . $this->verifica_SS(4) . "  and  ";
            $sql_re .= "a.cod_dep=" . $this->verifica_SS(5) . " ";
        }

        return $sql_re;
    }

//fin funcion SQLCA

    function SQLCA_report_in($pre = null) {
        $sql_re = $this->verifica_SS(1) . ",";
        $sql_re .= $this->verifica_SS(2) . ",";
        $sql_re .= $this->verifica_SS(3) . ",";
        if ($pre != null && $pre == 1) {
            $sql_re .= $this->verifica_SS(4) . ",";
            $sql_re .= 0;
        } else {
            $sql_re .= $this->verifica_SS(4) . ",";
            $sql_re .= $this->verifica_SS(5) . " ";
        }

        return $sql_re;
    }

//fin funcion SQLCA

    function verifica_SS($i) {
        switch ($i) {
            case 1:return $this->Session->read('SScodpresi');
                break;
            case 2:return $this->Session->read('SScodentidad');
                break;
            case 3:return $this->Session->read('SScodtipoinst');
                break;
            case 4:return $this->Session->read('SScodinst');
                break;
            case 5:return $this->Session->read('SScoddep');
                break;
            case 6:return $this->Session->read('entidad_federal');
                break;
            default:
                return "NULO";
        }//fin switch
    }

//fin verifica_SS

    function beforeFilter() {
        if (defined('SERVIDOR_REPORTE')) {
            if (SERVIDOR_REPORTE == "localhost") {
                $this->checkSession();
            } else {
                if (defined('SERVIDOR_HOST')) {
                    $longitud = strlen(SERVIDOR_HOST);
                    $direccion_referencial = substr($_SERVER["HTTP_REFERER"], 0, $longitud);
                    if (SERVIDOR_HOST == $direccion_referencial && isset($_SERVER["HTTP_REFERER"])) {
                        $_SESSION = recibe_input_array($this->params["form"]["REPORTE"]);
                        if ($_SESSION == null) {
                            $this->redirect($_SERVER["HTTP_REFERER"]);
                        } else {
                            $cod_presi = $_SESSION["SScodpresi"];
                            $cod_entidad = $_SESSION["SScodentidad"];
                            $cod_tipo_inst = $_SESSION["SScodtipoinst"];
                            $cod_inst = $_SESSION["SScodinst"];
                            $cod_dep = $_SESSION["SScoddep"];
                            $username = $_SESSION["nom_usuario"];
                            $rsp = $this->v_cfpd05_denominaciones->execute("select * from cugd04_entrada_modulo where cod_presi='" . $cod_presi . "' and  cod_entidad='" . $cod_entidad . "' and  cod_tipo_inst='" . $cod_tipo_inst . "' and  cod_inst='" . $cod_inst . "' and  cod_dep='" . $cod_dep . "' and  username='" . $username . "' ");
                            if (!isset($rsp[0][0]["username"])) {
                                $this->redirect($_SERVER["HTTP_REFERER"]);
                            }
                        }//else
                    } else {
                        $this->redirect($_SERVER["HTTP_REFERER"]);
                    }
                } else {
                    $this->checkSession();
                }//fin else
            }//fin else
        } else {
            $this->checkSession();
        }//fin else
    }

//fin function

    function activa_enviar() {

        $this->layout = "ajax";
    }

//fin function

    function reporte_agentes_retencion($var1 = null, $var2 = null, $var3 = null, $var4 = null) {

        set_time_limit(0);

        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');


        if ($var1 == null) {

            $this->layout = "ajax";
            $opcion = 1;
            $this->set('lista_numero', "");
        } else if ($var1 == 2) {

            $this->layout = "ajax";
            $opcion = 2;


            if ($var4 == 2) {
                if ($var2 == 1) {
                    $dep_A = "";
                    $dep_B = "";
                } else if ($var2 == 2) {
                    $dep_A = " a.cod_dep   = " . $cod_dep . "  and ";
                    $dep_B = " a.cod_dep, ";
                }//fin function


                $sql = "
								         SELECT

												  b.rif,
												  b.beneficiario

								          FROM
								                  cstd07_retenciones_cuerpo_islr a, cepd03_ordenpago_cuerpo b

								         WHERE

									               a.cod_presi            =  " . $cod_presi . "             and
												   a.cod_entidad          =  " . $cod_entidad . "           and
												   a.cod_tipo_inst        =  " . $cod_tipo_inst . "         and
												   a.cod_inst             =  " . $cod_inst . "              and
												   " . $dep_A . "
												   a.status               =  2                          and
												   a.ano_orden_pago       =  " . $var3 . "                  and
												   b.cod_presi            =  a.cod_presi                and
												   b.cod_entidad          =  a.cod_entidad              and
												   b.cod_tipo_inst        =  a.cod_tipo_inst            and
												   b.cod_inst             =  a.cod_inst                 and
												   b.cod_dep              =  a.cod_dep                  and
												   b.ano_orden_pago       =  a.ano_orden_pago           and
												   b.numero_orden_pago    =  a.numero_orden_pago

												    GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, " . $dep_B . " b.rif, b.beneficiario

												    ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, " . $dep_B . " b.beneficiario ASC


												   ; ";
                $data = $this->v_cfpd05_denominaciones->execute($sql);
                foreach ($data as $ve) {
                    $lista_numero[$ve[0]["rif"]] = $ve[0]["rif"] . " - " . $ve[0]["beneficiario"];
                }//fin foreach
            } else {
                $lista_numero = "no";
            }//fin else




            $this->set('lista_numero', $lista_numero);
        } else if ($var1 == 3) {

            $this->layout = "ajax";
            $opcion = 3;


            $this->set('consolidado', $var2);
        } else if ($var1 == 4) {

            $this->layout = "ajax";
            $opcion = 4;

            $this->set('consolidado', $var2);
            $this->set('year', $var3);
        } else if ($var1 == 5) {

            if (!empty($this->data['reporte3']['consolidacion'])) {
                $consolidacion = $this->data['reporte3']['consolidacion'];
            } else {
                $consolidacion = 2;
            }
            if (!empty($this->data['reporte3']['year'])) {
                $year = $this->data['reporte3']['year'];
            } else {
                $year = $this->ano_ejecucion();
            }
            if (!empty($this->data['reporte3']['rif_constribuyente'])) {
                $rif = $this->data['reporte3']['rif_constribuyente'];
            } else {
                $rif = "";
            }

            if ($consolidacion == 1) {
                $dep_A = "";
                $dep_B = "";
            } else if ($consolidacion == 2) {
                $dep_A = " a.cod_dep   =  " . $cod_dep . "  and";
                $dep_B = " a.cod_dep, ";
            }//fin function

            if ($rif == "") {
                $rif = "";
            } else if ($rif != "") {
                $rif = " and  b.rif  =  '" . $rif . "'";
            }//fin function

            $this->layout = "pdf";
            $opcion = 5;
            $sql = "
         SELECT
	              a.cod_presi,
				  a.cod_entidad,
				  a.cod_tipo_inst,
				  a.cod_inst,
				  a.cod_dep,
				  a.ano_orden_pago,
				  a.clase_orden,
				  a.numero_orden_pago,
				  a.monto,
				  a.fecha_proceso_registro,
				  a.status,
				  a.ano_movimiento,
				  (SELECT x.denominacion FROM cstd01_entidades_bancarias x WHERE a.cod_entidad_bancaria = x.cod_entidad_bancaria ) as banco,
				  a.cod_entidad_bancaria,
				  a.cod_sucursal,
				  a.cuenta_bancaria,
				  a.numero_cheque,
				  a.fecha_proceso_anulacion,
				  b.cod_presi            as  cod_presi_pago,
				  b.cod_entidad          as  cod_entidad_pago,
				  b.cod_tipo_inst        as  cod_tipo_inst_pago,
				  b.cod_inst             as  cod_inst_pago,
				  b.cod_dep              as  cod_dep_pago,
				  b.ano_orden_pago       as  ano_orden_pago_pago,
				  b.numero_orden_pago    as  numero_orden_pago_pago,
				  b.rif,
				  b.beneficiario,
				  b.autorizado,
				  b.cedula_identidad,
				  b.monto_descontar_impuesto,
	  			  b.porcentaje_islr,
	  			  (SELECT xa.objeto               FROM cpcd02 xa WHERE xa.rif = b.rif ) as objeto_rif,
	  			  (SELECT xb.denominacion         FROM cpcd02 xb WHERE xb.rif = b.rif ) as denominacion_rif,
	  			  (SELECT xc.direccion_comercial  FROM cpcd02 xc WHERE xc.rif = b.rif ) as direccion_comercial_rif,
	  			  (SELECT xd.codigo_area_empresa  FROM cpcd02 xd WHERE xd.rif = b.rif ) as codigo_area_empresa_rif,
	  			  (SELECT xe.telefonos            FROM cpcd02 xe WHERE xe.rif = b.rif ) as telefonos_rif,
	  			  (una_fecha(((SELECT
	  			  		xf.fecha_debito
	  			  FROM
	  			  		cstd30_debito_cuerpo xf
	  			  WHERE
	  			  		xf.cod_presi             =  a.cod_presi                and
					    xf.cod_entidad           =  a.cod_entidad              and
					    xf.cod_tipo_inst         =  a.cod_tipo_inst            and
					    xf.cod_inst              =  a.cod_inst                 and
					    xf.cod_dep               =  a.cod_dep                  and
					    xf.cod_entidad_bancaria  =  a.cod_entidad_bancaria     and
					    xf.cod_sucursal          =  a.cod_sucursal             and
					    xf.cuenta_bancaria       =  a.cuenta_bancaria          and
					    xf.numero_debito         =  a.numero_cheque
	  			  ))::text,((SELECT
	  			  		xf.fecha_cheque
	  			  FROM
	  			  		cstd03_cheque_cuerpo xf
	  			  WHERE
	  			  		xf.cod_presi             =  a.cod_presi                and
					    xf.cod_entidad           =  a.cod_entidad              and
					    xf.cod_tipo_inst         =  a.cod_tipo_inst            and
					    xf.cod_inst              =  a.cod_inst                 and
					    xf.cod_dep               =  a.cod_dep                  and
					    xf.cod_entidad_bancaria  =  a.cod_entidad_bancaria     and
					    xf.cod_sucursal          =  a.cod_sucursal             and
					    xf.cuenta_bancaria       =  a.cuenta_bancaria          and
					    xf.numero_cheque         =  a.numero_cheque
	  			  ))::text)       ) as fecha_pago_cheque,
	  			  (SELECT
	  			  		xg.tipo_dependencia
	  			  FROM
	  			  		cugd02_dependencias xg
	  			  WHERE
	  			  		xg.cod_tipo_institucion      =  a.cod_tipo_inst         and
					    xg.cod_institucion           =  a.cod_inst              and
					    xg.cod_dependencia           =  a.cod_dep
	  			  ) as tipo_dependencia

          FROM
                  cstd07_retenciones_cuerpo_islr a, cepd03_ordenpago_cuerpo b

         WHERE

	               a.cod_presi            =  " . $cod_presi . "             and
				   a.cod_entidad          =  " . $cod_entidad . "           and
				   a.cod_tipo_inst        =  " . $cod_tipo_inst . "         and
				   a.cod_inst             =  " . $cod_inst . "              and
				   " . $dep_A . "
				   a.status               =  2                          and
				   a.ano_orden_pago       =  " . $year . "                  and
				   b.cod_presi            =  a.cod_presi                and
				   b.cod_entidad          =  a.cod_entidad              and
				   b.cod_tipo_inst        =  a.cod_tipo_inst            and
				   b.cod_inst             =  a.cod_inst                 and
				   b.cod_dep              =  a.cod_dep                  and
				   b.ano_orden_pago       =  a.ano_orden_pago           and
				   b.numero_orden_pago    =  a.numero_orden_pago       " . $rif . "

				   ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, tipo_dependencia, denominacion_rif,fecha_pago_cheque ASC; ";

            $data = $this->v_cfpd05_denominaciones->execute($sql);
            $this->set('data', $data);
            $this->set('year', $year);
            $this->set('datos_cugd02_dependencias', $this->cugd02_dependencia->findAll());
        }//fin function





        $this->set('titulo_a', $this->Session->read('dependencia'));
        $this->set('opcion', $opcion);
        $this->set('year', $this->ano_ejecucion());
    }

//fin function

    function reporte_agentes_retencion_2($var1 = null, $var2 = null, $var3 = null, $var4 = null) {

        set_time_limit(0);

        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');


        if ($var1 == null) {

            $this->layout = "ajax";
            $opcion = 1;
            $this->set('lista_numero', "");
        } else if ($var1 == 2) {

            $this->layout = "ajax";
            $opcion = 2;


            if ($var4 == 2) {
                if ($var2 == 1) {
                    $dep_A = "";
                    $dep_B = "";
                } else if ($var2 == 2) {
                    $dep_A = " a.cod_dep   = " . $cod_dep . "  and ";
                    $dep_B = " a.cod_dep, ";
                }//fin function


                $sql = "
								         SELECT

												  b.rif,
												  b.beneficiario

								          FROM
								                  cstd07_retenciones_cuerpo_islr a, cepd03_ordenpago_cuerpo b

								         WHERE

									               a.cod_presi            =  " . $cod_presi . "             and
												   a.cod_entidad          =  " . $cod_entidad . "           and
												   a.cod_tipo_inst        =  " . $cod_tipo_inst . "         and
												   a.cod_inst             =  " . $cod_inst . "              and
												   " . $dep_A . "
												   a.status               =  2                          and
												   a.ano_orden_pago       =  " . $var3 . "                  and
												   b.cod_presi            =  a.cod_presi                and
												   b.cod_entidad          =  a.cod_entidad              and
												   b.cod_tipo_inst        =  a.cod_tipo_inst            and
												   b.cod_inst             =  a.cod_inst                 and
												   b.cod_dep              =  a.cod_dep                  and
												   b.ano_orden_pago       =  a.ano_orden_pago           and
												   b.numero_orden_pago    =  a.numero_orden_pago

												    GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, " . $dep_B . " b.rif, b.beneficiario

												    ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, " . $dep_B . " b.beneficiario ASC


												   ; ";

                $data = $this->v_cfpd05_denominaciones->execute($sql);
                foreach ($data as $ve) {
                    $lista_numero[$ve[0]["rif"]] = $ve[0]["rif"] . " - " . $ve[0]["beneficiario"];
                }//fin foreach

                if (!isset($lista_numero)) {
                    $lista_numero = "vacio";
                }
            } else {
                $lista_numero = "no";
            }//fin else



            $this->set('consolidado', $var2);
            $this->set('year', $var3);
            $this->set('lista_numero', $lista_numero);
        } else if ($var1 == 3) {

            $this->layout = "ajax";
            $opcion = 3;


            $this->set('consolidado', $var2);
        } else if ($var1 == 4) {

            $this->layout = "ajax";
            $opcion = 4;

            $this->set('consolidado', $var2);
            $this->set('year', $var3);
        } else if ($var1 == 6) {
            $opcion = 6;


            $this->layout = "ajax";


            if ($var4 != "") {
                if ($var2 == 1) {
                    $dep_A = "";
                    $dep_B = "";
                } else if ($var2 == 2) {
                    $dep_A = " a.cod_dep   = " . $cod_dep . "  and ";
                    $dep_B = " a.cod_dep, ";
                }//fin function


                $sql = "
											         SELECT

															  b.rif,
															  b.beneficiario

											          FROM
											                  cstd07_retenciones_cuerpo_islr a, cepd03_ordenpago_cuerpo b

											         WHERE

												               a.cod_presi            =  " . $cod_presi . "             and
															   a.cod_entidad          =  " . $cod_entidad . "           and
															   a.cod_tipo_inst        =  " . $cod_tipo_inst . "         and
															   a.cod_inst             =  " . $cod_inst . "              and
															   " . $dep_A . "
															   a.status               =  2                          and
															   a.ano_orden_pago       =  " . $var3 . "                  and
															   b.cod_presi            =  a.cod_presi                and
															   b.cod_entidad          =  a.cod_entidad              and
															   b.cod_tipo_inst        =  a.cod_tipo_inst            and
															   b.cod_inst             =  a.cod_inst                 and
															   b.cod_dep              =  a.cod_dep                  and
															   b.ano_orden_pago       =  a.ano_orden_pago           and
															   b.numero_orden_pago    =  a.numero_orden_pago        and
															   b.beneficiario         like   '%" . strtoupper($var4) . "%'

															    GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, " . $dep_B . " b.rif, b.beneficiario

															    ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, " . $dep_B . " b.beneficiario ASC


															   ; ";

                $data = $this->v_cfpd05_denominaciones->execute($sql);
                foreach ($data as $ve) {
                    $lista_numero[$ve[0]["rif"]] = $ve[0]["rif"] . " - " . $ve[0]["beneficiario"];
                }//fin foreach

                if (!isset($lista_numero)) {
                    $lista_numero = "vacio";
                }
            } else {
                $lista_numero = "no";
            }//fin else


            $this->set('consolidado', $var2);
            $this->set('year', $var3);
            $this->set('lista_numero', $lista_numero);
        }//fin function





        $this->set('titulo_a', $this->Session->read('dependencia'));
        $this->set('opcion', $opcion);

        if ($var3 == null) {
            $this->set('year', $this->ano_ejecucion());
        }
    }

//fin function







    /* Para el reporte de pagos por subpartidas */

    function select3($select = null, $var = null, $var2 = null) { //select codigos
        $this->layout = "ajax";

        if ($var != null) {
            $cond = $this->SQLCA(); //vario
            switch ($select) {
                case 'entidad_bancaria':
                    $this->set('SELECT', 'coordinacion');
                    $this->set('codigo', 'secretaria');
                    $this->set('seleccion', '');
                    $this->set('n', 1);
                    break;
                case 'sucursal':
                    $this->set('SELECT', 'secretaria'); //El parametro que se le pasa para que busque el proximo select (cuando entre en select3)
                    $this->set('codigo', 'sucursal'); //El nombre que se le asigna al select actual cuando se crea
                    $this->set('cod_sucursal', $var); //cod_sucursal es para mantener el valor de la variable que llega y pasarselo al paso que viene en select3
                    $this->set('seleccion', '');
                    $this->set('n', 2);
                    $this->set('no', 'no');
                    $this->set('codigo_entidad', $var);
                    $this->set('codigo_sucursal', $var2);
                    $cond = "cod_entidad_bancaria=" . $var;
                    $sucursales = $this->cstd01_sucursales_bancarias->findAll($cond, null, 'cod_sucursal ASC');
                    $lista = array();
                    $codSucursal = array();
                    $denoSucursal = array();
                    $total_sucursales = count($sucursales);
                    if ($total_sucursales == 0) {
                        $lista = array();
                    } else {
                        for ($i = 0; $i < $total_sucursales; $i++) {
                            $codSucursal[] = mascara($sucursales[$i]['cstd01_sucursales_bancarias']['cod_sucursal'], 4);
                            $denoSucursal[] = mascara($sucursales[$i]['cstd01_sucursales_bancarias']['cod_sucursal'], 4) . " - " . $sucursales[$i]['cstd01_sucursales_bancarias']['denominacion'];
                        }
                        $lista = array_combine($codSucursal, $denoSucursal);
                    }
                    $this->set('vector', $lista);
                    //$this->concatena($lista, 'vector');
                    break;
                case 'secretaria':
                    // $this->set('SELECT','direccion');
                    // $this->set('codigo','secretaria');
                    // $this->set('seleccion','');
                    // this->set('n',3);
                    // echo $select."- - - 1.<br>";
                    // echo $var."- - - 1.<br>";
                    // $lista=  $this->cugd02_secretaria->generateList($cond, 'cod_secretaria ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
                    // $this->concatena($lista, 'vector');
                    break;
            }//fin switch
        } else {
            $this->set('SELECT', '');
            $this->set('codigo', '');
            $this->set('seleccion', '');
            $this->set('n', 10);
            $this->set('no', 'no');
            $this->set('vector', '');
        }
    }

//fin select codigos

    function mostrar4($select = null, $var = null, $var2 = null) {
        $this->layout = "ajax";

        if ($var != null) {
            switch ($select) {
                case 'entidad_bancaria':
                    $cond = "cod_entidad_bancaria=" . $var;
                    $a = $this->cstd01_entidades_bancarias->findAll($cond);
                    echo "<input type='text' name='data[reporte3][cod_entidad_bancaria]' value='" . mascara($a[0]['cstd01_entidades_bancarias']['cod_entidad_bancaria'], 4) . "' size='5'  maxlength='4' id='cod_entidad_bancaria' readonly='readonly' class='inputtext' style='text-align:center' />";
                    break;
                case 'sucursal':
                    if (!isset($var) || !isset($var2)) {
                        echo "<input type='text' name='data[reporte3][cod_sucursal_bancaria]' value='' size='5' maxlength='4' id='cod_sucursal_bancaria' readonly='readonly' class='inputtext' style='text-align:center' />";
                    } else {
                        $cond = "cod_entidad_bancaria=" . $var . " and cod_sucursal=" . $var2;
                        $a = $this->cstd01_sucursales_bancarias->findAll($cond);
                        echo "<input type='text' name='data[reporte3][cod_sucursal_bancaria]' value='" . mascara($a[0]['cstd01_sucursales_bancarias']['cod_sucursal'], 4) . "' id='cod_sucursal_bancaria' size='5' maxlength='4'  readonly='readonly' class='inputtext' style='text-align:center' />";
                    }
                    break;
                case 'secretaria':

                    break;
            }//fin switch
        } else {
            echo "<input type='text' name='data[reporte3]' size='5' maxlength='4' id='cod_entidad_bancaria' class='inputtext' style='text-align:center' />";
        }
    }

//fin mostrar4 codigos

    function mostrar3($select = null, $var = null, $var2 = null) {
        $this->layout = "ajax";
        if ($var != null && !empty($var)) {
            switch ($select) {
                case 'entidad_bancaria':
                    $cond = "cod_entidad_bancaria=" . $var;
                    $a = $this->cstd01_entidades_bancarias->findAll($cond);
                    echo "<input type='text' name='data[reporte3][deno_entidad_bancaria]' value='" . $a[0]['cstd01_entidades_bancarias']['denominacion'] . "' maxlength='100' id='deno_entidad_bancaria' class='inputtext' />";
                    break;
                case 'sucursal':
                    if (!isset($var2)) {
                        echo "<input type='text' name='data[reporte3][deno_sucursal_bancaria]' value='' maxlength='100' id='deno_sucursal_bancaria' class='inputtext' />";
                    } else {
                        $cond = "cod_entidad_bancaria=" . $var . " and cod_sucursal=" . $var2;
                        $a = $this->cstd01_sucursales_bancarias->findAll($cond);
                        echo "<input type='text' name='data[reporte3][deno_sucursal_bancaria]' value='" . $a[0]['cstd01_sucursales_bancarias']['denominacion'] . "' maxlength='100' id='deno_sucursal_bancaria' class='inputtext' />";
                    }
                    break;
                case 'secretaria':

                    break;
            }//fin switch
        } else {
            echo "<input type='text' name='data[reporte3] value='' size='37'  maxlength='100' />";
        }
    }

//fin mostrar3 denominaciones

    function mostrar5($select = null, $var = null, $var2 = null, $cod_ent = null, $cod_sucursa = null) {
        $this->layout = "ajax";
        if (!isset($var2)) {
            $this->set('vector_cuenta', '');
            $this->set('lista', '');
        } else {


            if ($_SESSION["consolidado_reporte_opcion"] == 1) {
                $cond = $this->condicionNDEP() . " and cod_entidad_bancaria =" . $var . " and cod_sucursal=" . $var2;
            } else {
                $cond = $this->condicionNDEP() . " and cod_dep=" . $_SESSION['cod_dep_reporte_consolidado'] . " and cod_entidad_bancaria =" . $var . " and cod_sucursal=" . $var2;
            }




            $lista = $this->cstd02_cuentas_bancarias->generateList($cond, 'cod_sucursal ASC', null, '{n}.cstd02_cuentas_bancarias.cuenta_bancaria', '{n}.cstd02_cuentas_bancarias.concepto_manejo');

            if ($lista == 0) {
                $this->set('vector_cuenta', array('no' => 'no hay registros'));
            } else {
                //$this->set('vector_cuenta', $lista);
                $this->concatena($lista, 'vector_cuenta');
                $this->set('codigo', $var);
                $this->set('cod_sucursal', $var2);
            }
        }
    }

    /* fin funciones para el reporte de pago por subpartidas */

    function pagos_realizados_por_subpartida_opcion($var1 = null) {

        $this->layout = "ajax";

        $_SESSION["consolidado_reporte_opcion"] = $var1;
    }

    function pagos_realizados_por_subpartida($var1 = null, $var2 = null, $var3 = null, $var4 = null) {
        set_time_limit(0);


        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');

        if ($var1 == 1) {

            $this->layout = "ajax";
            $opcion = 1;
            $this->set('year', $this->ano_ejecucion());
            $meses = array('1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio', '8' => 'Agosto', '9' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre');
            $this->concatena($meses, 'mes');
        } else if ($var1 == 2) {

//pr($this->data);

            $sql_partida = "";
            $acepta = 1;
            $meses = array('1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio', '8' => 'Agosto', '9' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre');
            $this->set('meses', $meses);
            if (!empty($this->data['reporte3']['year'])) {
                $year = $this->data['reporte3']['year'];
            } else {
                $year = "";
                $acepta = 2;
            }
            //if(!empty($this->data['reporte3']['mes'])){ $mes = $this->data['reporte3']['mes'];  }else{ $mes = ""; }
            if (!empty($this->data['reporte3']['tipo_reporte'])) {
                $tipo_reporte = $this->data['reporte3']['tipo_reporte'];
                if ($tipo_reporte == 2) {
                    $acepta = 2;
                    if (isset($this->data['reporte']['cod_partida']) && $this->data['reporte']['cod_partida'] != "") {
                        $sql_partida = "     a.cod_partida    = '" . $this->data['reporte']['cod_partida'] . "'       and ";
                        $acepta = 1;
                        if (isset($this->data['reporte']['cod_generica']) && $this->data['reporte']['cod_generica'] != "") {
                            $sql_partida .= "     a.cod_generica   = '" . $this->data['reporte']['cod_generica'] . "'      and ";
                            $acepta = 1;
                            if (isset($this->data['reporte']['cod_especifica']) && $this->data['reporte']['cod_especifica'] != "") {
                                $sql_partida .= "     a.cod_especifica = '" . $this->data['reporte']['cod_especifica'] . "'    and ";
                                $acepta = 1;
                                if (isset($this->data['reporte']['cod_subespecifica']) && $this->data['reporte']['cod_subespecifica'] != "") {
                                    $sql_partida .= "     a.cod_sub_espec  = '" . $this->data['reporte']['cod_subespecifica'] . "' and ";
                                    $acepta = 1;
                                }//fin if
                            }//fin if
                        }//fin if
                    }//fin if
                } else {
                    $tipo_reporte = "";
                }
            } else {
                $acepta = 2;
            }



            $consolidacion = 2;

            if (isset($this->data['cfpp05']['consolidacion'])) {

                $consolidacion = $this->data['cfpp05']['consolidacion'];

                if ($consolidacion == 1) {
                    $sql = "  a.cod_presi            =  " . $cod_presi . "             and
															   a.cod_entidad          =  " . $cod_entidad . "           and
															   a.cod_tipo_inst        =  " . $cod_tipo_inst . "         and
															   a.cod_inst             =  " . $cod_inst . "              and
						                                    ";
                } else if ($consolidacion == 2) {
                    $sql = "  a.cod_presi            =  " . $cod_presi . "             and
															   a.cod_entidad          =  " . $cod_entidad . "           and
															   a.cod_tipo_inst        =  " . $cod_tipo_inst . "         and
															   a.cod_inst             =  " . $cod_inst . "              and
															   a.cod_dep              =  " . $_SESSION['cod_dep_reporte_consolidado'] . "               and
						                                    ";
                } else {
                    $acepta = 2;
                }
            } else {


                if ($consolidacion == 1) {
                    $sql = "  a.cod_presi            =  " . $cod_presi . "             and
														   a.cod_entidad          =  " . $cod_entidad . "           and
														   a.cod_tipo_inst        =  " . $cod_tipo_inst . "         and
														   a.cod_inst             =  " . $cod_inst . "              and
					                                    ";
                } else if ($consolidacion == 2) {
                    $sql = "  a.cod_presi            =  " . $cod_presi . "             and
														   a.cod_entidad          =  " . $cod_entidad . "           and
														   a.cod_tipo_inst        =  " . $cod_tipo_inst . "         and
														   a.cod_inst             =  " . $cod_inst . "              and
														   a.cod_dep              =  " . $cod_dep . "               and
					                                    ";
                } else {
                    $acepta = 2;
                }
            }//fin else


            $mes = "";
            //if($mes!=""){ $sql .="  substr(CAST (a.fecha_documento AS text),6,2)::int  =  '".$mes."' and ";}else{$acepta=2;}


            $opcion_reporte = $this->data['reporte3']['opcion_reporte'];
            $this->set('opcion_reporte', $opcion_reporte);

            if ($opcion_reporte == 1) {// 1-Por mes
                if (!empty($this->data['reporte3']['mes'])) {
                    $mes = $this->data['reporte3']['mes'];
                } else {
                    $mes = "";
                }
                if ($mes != "") {
                    $sql .="  substr(CAST (a.fecha_documento AS text),6,2)::int  =  '" . $mes . "' and ";
                } else {
                    $acepta = 2;
                }
            } elseif ($opcion_reporte == 2) {// 2-Por fecha
                $fecha_inicial = $this->data['reporte3']['fecha_inicial'];
                $fecha_final = $this->data['reporte3']['fecha_final'];
                if ($fecha_inicial != "" && $fecha_final != "") {
                    $sql .= " (a.fecha_documento BETWEEN '$fecha_inicial' AND '$fecha_final') and ";
                } else {
                    $acepta = 2;
                }
                $this->set('fecha_inicial', $fecha_inicial);
                $this->set('fecha_final', $fecha_final);
            } elseif ($opcion_reporte == 3) {// 3-Por cuenta bancaria
                $opcion_reporte_cuenta = $this->data['reporte3']['por_ano'];
                if (!empty($this->data['reporte3']['codigo_entidad_bancaria'])) {
                    $codigo_entidad_bancaria = $this->data['reporte3']['codigo_entidad_bancaria'];
                } else {
                    $codigo_entidad_bancaria = "";
                }
                if (!empty($this->data['reporte3']['codigo_sucursal'])) {
                    $codigo_sucursal = $this->data['reporte3']['codigo_sucursal'];
                } else {
                    $codigo_sucursal = "";
                }
                if (!empty($this->data['reporte3']['cuenta_bancaria'])) {
                    $cuenta_bancaria = $this->data['reporte3']['cuenta_bancaria'];
                } else {
                    $cuenta_bancaria = "";
                }
                $this->set('opcion_reporte_cuenta', $opcion_reporte_cuenta);
                $this->set('cuenta_bancaria', $cuenta_bancaria);


                if ($codigo_entidad_bancaria != "" && $codigo_sucursal != "" and $cuenta_bancaria != "") {
                    $sql .= " a.cod_entidad_bancaria='$codigo_entidad_bancaria' and a.cod_sucursal='$codigo_sucursal' and a.cuenta_bancaria='$cuenta_bancaria' and";
                    if ($opcion_reporte_cuenta == 1) {
                        if (!empty($this->data['reporte3']['mes'])) {
                            $mes = $this->data['reporte3']['mes'];
                        } else {
                            $mes = "";
                        }
                        if ($mes != "") {
                            $sql .="  substr(CAST (a.fecha_documento AS text),6,2)::int  =  '" . $mes . "' and ";
                        } else {
                            $acepta = 2;
                        }
                    } elseif ($opcion_reporte_cuenta == 2) {
                        $fecha_inicial = $this->data['reporte3']['fecha_inicial'];
                        $fecha_final = $this->data['reporte3']['fecha_final'];
                        if ($fecha_inicial != "" && $fecha_final != "") {
                            $sql .= " (a.fecha_documento BETWEEN '$fecha_inicial' AND '$fecha_final') and ";
                        } else {
                            $acepta = 2;
                        }
                        $this->set('fecha_inicial', $fecha_inicial);
                        $this->set('fecha_final', $fecha_final);
                    } else {
                        $acepta = 2;
                    }
                } else {
                    $acepta = 2;
                }
            }


            if ($this->data['reporte3']['tipo_pago_consolidado'] == 2) {
                $sql_condicion = "  a.condicion_actividad  =  1  and  ";
            } else if ($this->data['reporte3']['tipo_pago_consolidado'] == 3) {
                $sql_condicion = "  a.condicion_actividad  =  2  and  ";
            } else {
                $sql_condicion = "";
            }


            $acepta = 1;


            if ($acepta == 2) {
                $this->layout = "ajax";
                $opcion = 19;

                $this->set('errorMessage', "FALTAN DATOS PARA GENERAR EL REPORTE");
            } else {
                $this->layout = "pdf";
                $opcion = 2;

                set_time_limit(0);

                $this->set('mes', $mes);
                $this->set('year', $year);
                $datos = $this->v_cfpd05_denominaciones->execute("
								                                SELECT
									                                      a.cod_presi,
																		  a.cod_entidad,
																		  a.cod_tipo_inst,
																		  a.cod_inst,
																		  a.cod_dep,
																		  a.ano_movimiento,
																		  a.cod_entidad_bancaria,
																		  a.cod_sucursal,
																		  a.cuenta_bancaria,
																		  a.numero_documento,
																		  a.fecha_documento,
																		  a.beneficiario,
																		  a.monto_cuerpo,
																		  a.concepto,
																		  a.rif_cedula,
																		  a.cod_tipo_pago,
																		  a.status_cheque,
																		  a.clase_beneficiario,
																		  a.clase_orden,
																		  a.ano_orden_pago,
																		  a.numero_orden_pago,
																		  a.ano,
																		  a.cod_sector,
																		  a.cod_programa,
																		  a.cod_sub_prog,
																		  a.cod_proyecto,
																		  a.cod_activ_obra,
																		  a.cod_partida,
																		  a.cod_generica,
																		  a.cod_especifica,
																		  a.cod_sub_espec,
																		  a.cod_auxiliar,
																		  a.monto_partida,
																		  a.numero_control_compromiso,
																		  a.numero_control_causado,
																		  a.numero_control_pagado,
																		  a.tipo,
																		  b.tipo_orden,
																		  b.fecha_orden_pago,
																		  b.ano_documento_origen,
																		  b.numero_documento_origen,
																		  b.numero_documento_adjunto,
																		  b.fecha_documento as fecha_compromiso,
																		  b.cod_tipo_documento,
																		  substr(CAST (a.fecha_documento AS text),6,2)::int as mes,
																		  (select denominacion from cfpd01_ano_5_sub_espec where cfpd01_ano_5_sub_espec.ejercicio=a.ano and cfpd01_ano_5_sub_espec.cod_grupo=4 and cfpd01_ano_5_sub_espec.cod_partida=substr(CAST (a.cod_partida AS text),2,2)::int and cfpd01_ano_5_sub_espec.cod_generica=a.cod_generica and cfpd01_ano_5_sub_espec.cod_especifica=a.cod_especifica and cfpd01_ano_5_sub_espec.cod_sub_espec=a.cod_sub_espec limit 1) as denominacion_partida,
																		  (SELECT x.denominacion  FROM  cstd01_entidades_bancarias x  WHERE cod_entidad_bancaria=a.cod_entidad_bancaria ) as denominacion_banco,
																		  (SELECT xx.denominacion  FROM  cepd03_tipo_documento xx  WHERE cod_tipo_documento=b.cod_tipo_documento ) as cod_tipo_documento_denominacion

								                                FROM
								                                          v_union_cheque_debito   a,
								                                          cepd03_ordenpago_cuerpo b


								                                WHERE

								                                      " . $sql . "
								                                      " . $sql_partida . "
								                              		   b.cod_presi            =  a.cod_presi                and
																	   b.cod_entidad          =  a.cod_entidad              and
																	   b.cod_tipo_inst        =  a.cod_tipo_inst            and
																	   b.cod_inst             =  a.cod_inst                 and
																	   b.cod_dep              =  a.cod_dep                  and
																	   b.ano_orden_pago       =  a.ano_orden_pago           and
																	   b.numero_orden_pago    =  a.numero_orden_pago        and
																	   " . $sql_condicion . "
																	   a.ano_movimiento       =  '" . $year . "'

								                                ORDER BY

								                                        a.cod_presi,
								                                        a.cod_entidad,
								                                        a.cod_tipo_inst,
								                                        a.cod_inst,
								                                        a.cod_partida,
																		a.cod_generica,
																		a.cod_especifica,
																		a.cod_sub_espec,
																	    a.ano_movimiento,
																		a.cod_entidad_bancaria,
																		a.cod_sucursal,
																		a.cuenta_bancaria,
																		a.numero_documento

								                                        ASC;

								                           ");

                $this->set('datos', $datos);
                //pr($datos);
            }//fin
        } else if ($var1 == 3) {

            $this->layout = "ajax";
            $opcion = 3;

            if ($var2 == 1) {
                $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst;
            } else {
                $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst . " and cod_dep=" . $cod_dep;
            }
            $this->set('ano', $var3);
            $this->Session->write('ano_reporte', $var3);
            $this->Session->write('ano', $var3);
            $rsp = $this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_partida,deno_partida FROM v_cfpd05_denominaciones  WHERE " . $condicion . " and ano=" . $var3 . " ORDER BY cod_partida ASC");

            foreach ($rsp as $lp) {
                $vp[] = $lp[0]["cod_partida"];
                $dp[] = $lp[0]["deno_partida"];
            }
            if (isset($vp)) {
                $partida = array_combine($vp, $dp);
            } else {
                $partida = array();
            }
            $this->concatena($partida, 'partida');
            $this->set('opcion_var', $var4);
        } else if ($var1 == 4) {

            $this->layout = "ajax";
            $opcion = 4;

            $this->set('consolidado', $var2);
            $this->set('year', $this->ano_ejecucion());
        } else if ($var1 == 5) {

            $this->layout = "ajax";
            $opcion = 5;

            $this->set('consolidado', $var2);

            if ($var3 != null) {
                $this->set('year', $var3);
            } else {
                $this->set('year', $this->ano_ejecucion());
            }
        } else {
            $this->layout = "ajax";
            $opcion = 10;
        }//fin else

        $this->set('opcion', $opcion);
    }

//fin funtion

    function radio_reporte_pagos_por_subpartida($var = null) {
        $this->layout = "ajax";

        if (isset($var) && $var == 1) {
            $meses = array('1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio', '8' => 'Agosto', '9' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre');
            $this->concatena($meses, 'mes');
        } elseif (isset($var) && $var == 2) {
            
        } elseif (isset($var) && $var == 3) {
            $entidades_banc = $this->cstd01_entidades_bancarias->findAll(null, null, 'cod_entidad_bancaria ASC');
            $entidades = array();
            $codEntidad = array();
            $denoEntidad = array();
            $total_entidades = count($entidades_banc);
            if ($total_entidades != 0) {
                for ($i = 0; $i < $total_entidades; $i++) {
                    $codEntidad[] = mascara($entidades_banc[$i]['cstd01_entidades_bancarias']['cod_entidad_bancaria'], 4);
                    $denoEntidad[] = mascara($entidades_banc[$i]['cstd01_entidades_bancarias']['cod_entidad_bancaria'], 4) . " - " . $entidades_banc[$i]['cstd01_entidades_bancarias']['denominacion'];
                }
                $entidades = array_combine($codEntidad, $denoEntidad);
            }
            $this->set('entidades', $entidades);
            $this->set('vector_cuenta', '');

            $meses = array('1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio', '8' => 'Agosto', '9' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre');
            $this->concatena($meses, 'mes');
        } elseif (isset($var) && $var == 4) {
            
        }
        $this->set('var', $var);
    }

    function libro_de_compras($var1 = null, $var2 = null, $var3 = null, $var4 = null) {
        set_time_limit(0);

        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');

        if ($var1 == 1) {

            $this->layout = "ajax";
            $opcion = 1;
            $this->set('year', $this->ano_ejecucion());
        } else if ($var1 == 2) {

            $this->layout = "pdf";
            $opcion = 2;


            $meses = array('1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio', '8' => 'Agosto', '9' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre');
            $this->set('meses', $meses);


            if (!empty($this->data['reporte3']['consolidacion'])) {
                $consolidacion = $this->data['reporte3']['consolidacion'];
            } else {
                $consolidacion = 2;
            }
            if (!empty($this->data['reporte3']['year'])) {
                $year = $this->data['reporte3']['year'];
            } else {
                $year = "";
            }
            if (!empty($this->data['reporte3']['mes'])) {
                $mes = $this->data['reporte3']['mes'];
            } else {
                $mes = "";
            }
            if (!empty($this->data['reporte3']['tipo_year'])) {
                $tipo_year = $this->data['reporte3']['tipo_year'];
            } else {
                $tipo_year = "";
            }



            if ($consolidacion == 1) {
                $sql = "  a.cod_presi            =  " . $cod_presi . "             and
									   a.cod_entidad          =  " . $cod_entidad . "           and
									   a.cod_tipo_inst        =  " . $cod_tipo_inst . "         and
									   a.cod_inst             =  " . $cod_inst . "              and
                                    ";
            } else if ($consolidacion == 2) {
                $sql = "  a.cod_presi            =  " . $cod_presi . "             and
									   a.cod_entidad          =  " . $cod_entidad . "           and
									   a.cod_tipo_inst        =  " . $cod_tipo_inst . "         and
									   a.cod_inst             =  " . $cod_inst . "              and
									   a.cod_dep              =  " . $cod_dep . "               and
                                    ";
            }//fin if


            if ($mes != "" && $tipo_year == 2) {
                $sql .="  substr(CAST (b.fecha_factura AS text),6,2)::int  =  '" . $mes . "' and ";
            }

            if ($tipo_year == 3) {

                $fecha_inicial = $this->data['reporte3']['fecha_inicial'];
                $fecha_final = $this->data['reporte3']['fecha_final'];

                $sql .= "fecha_factura between '". $fecha_inicial ."' and '".$fecha_final."' and ";
                
                $this->set('tipo_year', $tipo_year);
                
            }

            $rango_fechas="Desde:  ". $fecha_inicial ."   Hasta:  ". $fecha_final;
            
            $this->set('mes', $mes);
            $this->set('year', $year);
            $this->set('rango_fechas', $rango_fechas);
            

            $sql_ejecutar = "SELECT
												  a.cod_presi,
												  a.cod_entidad,
												  a.cod_tipo_inst,
												  a.cod_inst,
												  a.cod_dep,
												  a.ano_orden_pago,
												  a.numero_orden_pago,
												  a.tipo_orden,
												  a.fecha_orden_pago,
												  a.ano_documento_origen,
												  a.numero_documento_origen,
												  a.numero_documento_adjunto,
												  a.fecha_documento,
												  a.cod_tipo_documento,
												  a.rif,
												  a.beneficiario,
												  a.autorizado,
												  a.cedula_identidad,
												  a.concepto,
												  a.monto_total,
												  a.numero_pago,
												  a.monto_parcial,
												  a.cod_frecuencia_pago,
												  a.fecha_desde,
												  a.fecha_hasta,
												  a.cod_tipo_pago,
												  a.monto_coniva,
												  a.monto_iva,
												  a.porcentaje_iva,
												  a.monto_siniva,
												  a.monto_retencion_laboral,
												  a.porcentaje_laboral,
												  a.monto_retencion_fielcumplimiento,
												  a.porcentaje_fielcumplimiento,
												  a.monto_descontar_impuesto,
												  a.amortizacion_anticipo,
												  a.porcentaje_amortizacion,
												  a.monto_orden_pago,
												  a.monto_retencion_iva,
												  a.porcentaje_retencion_iva,
												  a.monto_islr,
												  a.porcentaje_islr,
												  a.monto_sustraendo,
												  a.monto_timbre_fiscal,
												  a.porcentaje_timbre_fiscal,
												  a.monto_impuesto_municipal,
												  a.porcentaje_impuesto_municipal,
												  a.monto_neto_cobrar,
												  a.dia_asiento_registro,
												  a.mes_asiento_registro,
												  a.ano_asiento_registro,
												  a.numero_asiento_registro,
												  a.username_registro,
												  a.condicion_actividad,
												  a.ano_anulacion,
												  a.numero_anulacion,
												  a.dia_asiento_anulacion,
												  a.mes_asiento_anulacion,
												  a.ano_asiento_anulacion,
												  a.numero_asiento_anulacion,
												  a.username_anulacion,
												  a.ano_movimiento,
												  a.cod_entidad_bancaria,
												  a.cod_sucursal,
												  a.cuenta_bancaria,
												  a.numero_cheque,
												  a.fecha_cheque,
												  a.fecha_proceso_registro,
												  a.fecha_proceso_anulacion,
												  a.numero_comprobante_islr,
												  a.numero_comprobante_timbre,
												  a.numero_comprobante_municipal,
												  a.numero_comprobante_iva,
												  a.numero_comprobante_librocompras,
												  a.numero_comprobante_egreso,
												  a.documento_pago,
												  b.numero_factura,
												  b.numero_control,
												  b.fecha_factura,
												  b.monto_total_factura,
												  b.monto_sub_total,
												  b.porcentaje_iva,
												  b.monto_exento,
												  b.monto_iva,
												  b.monto_retencion_iva,
												  substr(CAST (b.fecha_factura AS text),6,2)::int as mes

							      FROM

							                      cepd03_ordenpago_cuerpo a,
							                      cepd03_ordenpago_facturas b

							      WHERE


										          " . $sql . "
			                                       b.cod_presi            =  a.cod_presi                and
												   b.cod_entidad          =  a.cod_entidad              and
												   b.cod_tipo_inst        =  a.cod_tipo_inst            and
												   b.cod_inst             =  a.cod_inst                 and
												   b.cod_dep              =  a.cod_dep                  and
												   b.ano_orden_pago       =  a.ano_orden_pago           and
												   b.numero_orden_pago    =  a.numero_orden_pago        and
												   a.condicion_actividad  =  1                          and
												   a.ano_orden_pago       =  '" . $year . "'

			                                ORDER BY

			                                        a.cod_presi,
			                                        a.cod_entidad,
			                                        a.cod_tipo_inst,
			                                        a.cod_inst,
			                                        a.cod_dep,
			                                        mes,
													b.fecha_factura


			                                        ASC;
                           ";
            
            $datos = $this->v_cfpd05_denominaciones->execute($sql_ejecutar);

            $this->set('datos', $datos);
            
        } else if ($var1 == 3) {
            $this->layout = "ajax";
            $opcion = 3;
            $meses = array('1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio', '8' => 'Agosto', '9' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre');
            $this->concatena($meses, 'mes');
            $this->set('opcion_var', $var2);
       
           
          
        } else {
            $this->layout = "ajax";
            $opcion = 10;
        }//fin else


        $this->set('opcion', $opcion);
    }

//fin function

    function select3_partidas($select = null, $var = null) { //select codigos presupuestarios
        $this->layout = "ajax";

        if ($select != null && $var != null) {
            if ($this->verifica_SS(5) == 1) {
                $cond = $this->SQLCA_report(1);
            } else {
                $cond = $this->SQLCA_report();
            }
            switch ($select) {

                case 'partida':
                    $this->set('SELECT', 'generica');
                    $this->set('codigo', 'partida');
                    $this->set('seleccion', '');
                    $this->set('n', 6);
                    $ano = $this->Session->read('ano');
                    $sec = $this->Session->read('sec');
                    $prog = $this->Session->read('prog');
                    $subp = $this->Session->read('subp');
                    $proy = $this->Session->read('proy');
                    $this->Session->write('actividad', $var);
                    $cond2 = $cond . " and ano=" . $ano;
                    //$lista=  $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_partida ASC', null, '{n}.v_cfpd05_denominaciones.cod_partida', '{n}.v_cfpd05_denominaciones.deno_partida');
                    //$this->concatena($lista, 'vector');
                    $rs = $this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_partida,deno_partida FROM v_cfpd05_denominaciones WHERE " . $cond2 . " ORDER BY cod_partida ASC");
                    foreach ($rs as $l) {
                        $v[] = $l[0]["cod_partida"];
                        $d[] = $l[0]["deno_partida"];
                    }
                    $lista = array_combine($v, $d);
                    $this->concatena($lista, 'vector');
                    break;
                case 'generica':
                    $this->set('SELECT', 'especifica');
                    $this->set('codigo', 'generica');
                    $this->set('seleccion', '');
                    $this->set('n', 7);
                    $ano = $this->Session->read('ano');
                    $sec = $this->Session->read('sec');
                    $prog = $this->Session->read('prog');
                    $subp = $this->Session->read('subp');
                    $proy = $this->Session->read('proy');
                    $activ = $this->Session->read('actividad');
                    $this->Session->write('cpar', $var);
                    $cond2 = $cond . " and ano=" . $ano . "  and cod_partida=" . $var;
                    //$lista=  $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_generica ASC', null, '{n}.v_cfpd05_denominaciones.cod_generica', '{n}.v_cfpd05_denominaciones.deno_generica');
                    //$this->concatena($lista, 'vector');
                    $rs = $this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_generica,deno_generica FROM v_cfpd05_denominaciones WHERE " . $cond2 . " ORDER BY cod_generica ASC");
                    foreach ($rs as $l) {
                        $v[] = $l[0]["cod_generica"];
                        $d[] = $l[0]["deno_generica"];
                    }
                    $lista = array_combine($v, $d);
                    $this->concatena($lista, 'vector');
                    break;
                case 'especifica':
                    $this->set('SELECT', 'subespecifica');
                    $this->set('codigo', 'especifica');
                    $this->set('seleccion', '');
                    $this->set('n', 8);
                    $ano = $this->Session->read('ano');
                    $sec = $this->Session->read('sec');
                    $prog = $this->Session->read('prog');
                    $subp = $this->Session->read('subp');
                    $proy = $this->Session->read('proy');
                    $activ = $this->Session->read('actividad');
                    $cpar = $this->Session->read('cpar');
                    $this->Session->write('cgen', $var);
                    $cond2 = $cond . " and ano=" . $ano . "  and cod_partida=" . $cpar . " and cod_generica=" . $var;
                    //$lista = $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_especifica ASC', null, '{n}.v_cfpd05_denominaciones.cod_especifica', '{n}.v_cfpd05_denominaciones.deno_especifica');
                    //$this->concatena($lista, 'vector');
                    $rs = $this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_especifica,deno_especifica FROM v_cfpd05_denominaciones WHERE " . $cond2 . " ORDER BY cod_especifica ASC");
                    foreach ($rs as $l) {
                        $v[] = $l[0]["cod_especifica"];
                        $d[] = $l[0]["deno_especifica"];
                    }
                    $lista = array_combine($v, $d);
                    $this->concatena($lista, 'vector');
                    break;
                case 'subespecifica':
                    $this->set('SELECT', 'auxiliar');
                    $this->set('codigo', 'subespecifica');
                    $this->set('seleccion', '');
                    $this->set('n', 9);
                    $ano = $this->Session->read('ano');
                    $sec = $this->Session->read('sec');
                    $prog = $this->Session->read('prog');
                    $subp = $this->Session->read('subp');
                    $proy = $this->Session->read('proy');
                    $activ = $this->Session->read('actividad');
                    $cpar = $this->Session->read('cpar');
                    $cgen = $this->Session->read('cgen');
                    $this->Session->write('cesp', $var);
                    $cond2 = $cond . " and ano=" . $ano . "  and cod_partida=" . $cpar . " and cod_generica=" . $cgen . " and cod_especifica=" . $var;
                    //$lista=  $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_sub_espec ASC', null, '{n}.v_cfpd05_denominaciones.cod_sub_espec', '{n}.v_cfpd05_denominaciones.deno_sub_espec');
                    //$this->concatena($lista, 'vector');
                    $rs = $this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_sub_espec,deno_sub_espec FROM v_cfpd05_denominaciones WHERE " . $cond2 . " ORDER BY cod_sub_espec ASC");
                    foreach ($rs as $l) {
                        $v[] = $l[0]["cod_sub_espec"];
                        $d[] = $l[0]["deno_sub_espec"];
                    }
                    $lista = array_combine($v, $d);
                    $this->concatena($lista, 'vector');
                    break;
                case 'auxiliar':

                    $this->set('SELECT', 'escribir_aux');
                    $this->set('codigo', 'auxiliar');
                    $this->set('seleccion', '');
                    $this->set('n', 10);
                    //$this->set('no','no');
                    $ano = $this->Session->read('ano');
                    $sec = $this->Session->read('sec');
                    $prog = $this->Session->read('prog');
                    $subp = $this->Session->read('subp');
                    $proy = $this->Session->read('proy');
                    $activ = $this->Session->read('actividad');
                    $cpar = $this->Session->read('cpar');
                    $cgen = $this->Session->read('cgen');
                    $cesp = $this->Session->read('cesp');
                    $this->Session->write('csesp', $var);
                    //$cpar=$cpar<9 ? "40".$cpar  : "4".$cpar;
                    //$cond2 ="ano=".$ano." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$var;
                    //echo "AUX1".$cond2;
                    $cond2 = $cond . " and ano=" . $ano . "  and cod_partida=" . $cpar . " and cod_generica=" . $cgen . " and cod_especifica=" . $cesp . " and cod_sub_espec=" . $var;
                    //echo $cond2;
                    //$lista=  $this->v_cfpd05_denominaciones->generateList($cond." and ".$cond2, 'cod_auxiliar ASC', null, '{n}.v_cfpd05_denominaciones.cod_auxiliar', '{n}.v_cfpd05_denominaciones.deno_auxiliar');
                    $rs = $this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_auxiliar,deno_auxiliar FROM v_cfpd05_denominaciones WHERE " . $cond2 . " ORDER BY cod_auxiliar ASC");
                    foreach ($rs as $l) {
                        $v[] = $l[0]["cod_auxiliar"];
                        $d[] = $l[0]["deno_auxiliar"];
                    }
                    $lista = array_combine($v, $d);
                    //$this->concatena($lista, 'vector');
                    if ($lista != null) {
                        $this->concatena($lista, 'vector');
                    } else {
                        $this->set('vector', array('0' => '00'));
                    }
                    //echo "muestra";
                    break;
                case 'auxiliar2':
                    //echo "hola auxiliar 2";
                    $this->set('SELECT', 'auxiliar');
                    $this->set('codigo', 'auxiliar');
                    $this->set('seleccion', '');
                    $this->set('n', 10);
                    //$this->set('no','no');
                    $ano = $this->Session->read('ano');
                    $sec = $this->Session->read('sec');
                    $prog = $this->Session->read('prog');
                    $subp = $this->Session->read('subp');
                    $proy = $this->Session->read('proy');
                    //$activ=$this->Session->read('actividad');
                    $cpar = $this->Session->read('cpar');
                    $cgen = $this->Session->read('cgen');
                    $cesp = $this->Session->read('cesp');
                    $this->Session->write('actividad', $var);
                    $f = $this->Session->read('CodigosDireccion');
                    $p = $this->Session->read('partidas');
                    //print_r($p);
                    /* $part= $p[0]['cscd01_catalogo']['cod_partida']<9 ? "40".$p[0]['cscd01_catalogo']['cod_partida']:$p[0]['cscd01_catalogo']['cod_partida'];
                      $part= $part <400 ? "4".$part : $part;
                      if($this->Session->read("year_pago")>date("Y")){
                      $ano= 1+date("Y");
                      }else{
                      $ano=date("Y");
                      }
                      $cond2 =" cod_sector=".$f[0]["cugd02_direccion"]["cod_sector"]." and cod_programa=".$f[0]["cugd02_direccion"]["cod_programa"]." and cod_sub_prog=".$f[0]["cugd02_direccion"]["cod_sub_prog"]." and cod_proyecto=".$f[0]["cugd02_direccion"]["cod_proyecto"]." and cod_activ_obra=".$var." and ano=".$ano." and cod_partida=".$part." and cod_generica=".$p[0]["cscd01_catalogo"]["cod_generica"]." and cod_especifica=".$p[0]["cscd01_catalogo"]["cod_especifica"]." and cod_sub_espec=".$p[0]["cscd01_catalogo"]["cod_sub_espec"];
                      //echo "AUX2".$cond2; */
                    $cond2 = $cond . " and ano=" . $ano . "  and cod_partida=" . $cpar . " and cod_generica=" . $cgen . " and cod_especifica=" . $cesp . " and cod_sub_espec=" . $p[0]["cscd01_catalogo"]["cod_sub_espec"];
                    //echo $cond2;
                    $lista = $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_auxiliar ASC', null, '{n}.v_cfpd05_denominaciones.cod_auxiliar', '{n}.v_cfpd05_denominaciones.deno_auxiliar');
                    /** 			if($lista!=null){
                      $this->AddCero('vector',$lista);
                      }else{
                      $this->set('vector',array('0'=>'00'));
                      } */
                    if ($lista != null) {
                        $this->concatena($lista, 'vector');
                        //echo count($lista);
                    } else {
                        $this->set('vector', array('0' => '00'));
                        //echo "cero";
                        $disponibilidad = $this->disponibilidad($_SESSION["ano"], $_SESSION["sec"], $_SESSION["prog"], $_SESSION["subp"], $_SESSION["proy"], $_SESSION["actividad"], $_SESSION["cpar"], $_SESSION["cgen"], $_SESSION["cesp"], $_SESSION["csesp"], 0);

                        echo "<script>" .
                        "document.getElementById('td_disponibilidad').innerHTML='" . $this->Formato2($disponibilidad) . "'; " .
                        "</script>";
                    }
                    break;
                case 'escribir_aux':
                    /// echo "saaaaaaaaaaa";
                    $this->Session->write('auxiliar', $var);
                    $disponibilidad = $this->disponibilidad($_SESSION["ano"], $_SESSION["sec"], $_SESSION["prog"], $_SESSION["subp"], $_SESSION["proy"], $_SESSION["actividad"], $_SESSION["cpar"], $_SESSION["cgen"], $_SESSION["cesp"], $_SESSION["csesp"], $_SESSION["auxiliar"]);

                    echo "<script>" .
                    "document.getElementById('td_disponibilidad').innerHTML='" . $this->Formato2($disponibilidad) . "';" .
                    "</script>";
                    $this->set("ocultar", true);
                    break;
            }//fin wsitch
        } else {
            $this->set('SELECT', '');
            $this->set('codigo', '');
            $this->set('seleccion', '');
            $this->set('n', 12);
            $this->set('no', 'no');
            $this->set('vector', array('0' => '00'));
        }
    }

//fin select codigos presupuestarios

    function funcion() {
        $this->layout = "ajax";
    }

    function distribucion_recursos_humanos_cfpd97($var1 = null, $var2 = null) {


        $tabla = "";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');

        set_time_limit(0);

        $tabla = "cfpd97";
        $vista = "v_cfpd97";


        if ($var2 == null) {
            $this->layout = "ajax";
            $opcion = 1;

            $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst;
            $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
            $dato = null;
            foreach ($year as $year) {
                $dato_year = $year['cfpd01_formulacion']['ano_formular'];
            }
            $year_vista = $dato_year;
            $this->set('year', $year_vista);
        } else {
            $this->layout = "pdf";
            $opcion = 2;

            if (!empty($this->data['reporte3']['consolidacion'])) {
                $consolidacion = $this->data['reporte3']['consolidacion'];
            } else {
                $consolidacion = 2;
            }
            if (!empty($this->data['reporte3']['year'])) {
                $year = $this->data['reporte3']['year'];
            } else {
                $year = $this->ano_ejecucion();
            }
            if (!empty($this->data['reporte3']['tipo'])) {
                $tipo = $this->data['reporte3']['tipo'];
            } else {
                $tipo = 1;
            }

            if ($tipo != 15) {
                $clasificacion = "b.clasificacion_personal  = " . $tipo . "  and ";
            } else {
                $clasificacion = "  ";
            }


            if (isset($this->data['cfpp05']['consolidacion'])) {
                if ($this->data['cfpp05']['consolidacion'] == 2) {
                    $dep_A = " a.cod_dep   =  " . $_SESSION['cod_dep_reporte_consolidado'] . "  and";
                    $dep_B = " a.cod_dep, ";
                    $titulo_a = $_SESSION["dependencia_reporte_consolidado"];
                } else if ($this->data['cfpp05']['consolidacion'] == 1) {
                    $dep_A = "";
                    $dep_B = "";
                    $titulo_a = $this->Session->read('dependencia');
                }
            } else {

                $dep_A = "";
                $dep_B = "";
                $titulo_a = $this->Session->read('dependencia');
            }

            $this->set('titulo_a', $titulo_a);

            $rs = $this->v_cfpd05_denominaciones->execute("

		         SELECT
			              a.cod_presi,
						  a.cod_entidad,
						  a.cod_tipo_inst,
						  a.cod_inst,
						  a.cod_dep,
						  a.cod_tipo_nomina,
						  a.cod_cargo,
						  a.cod_puesto,
						  a.sueldo_basico,
						  a.compensaciones,
						  a.primas,
						  a.bonos,
						  a.cod_dir_superior,
						  a.cod_coordinacion,
						  a.cod_secretaria,
						  a.cod_direccion,
						  a.cod_division,
						  a.cod_departamento,
						  a.cod_oficina,
						  a.cod_estado,
						  a.cod_municipio,
						  a.cod_parroquia,
						  a.cod_centro,
						  a.condicion_actividad,
						  a.ano,
						  a.cod_sector,
						(select
							z.deno_sector
							from v_cfpd05_denominaciones z
							where
							z.cod_presi=a.cod_presi and
							z.cod_entidad=a.cod_entidad and
							z.cod_tipo_inst=a.cod_tipo_inst and
							z.cod_inst=a.cod_inst and
							z.cod_dep=a.cod_dep and
							z.ano=a.ano and
							z.cod_sector=a.cod_sector limit 1)
						as deno_sector,
						a.cod_programa,
						(select
							y.deno_programa
							from v_cfpd05_denominaciones y
							where
							y.cod_presi=a.cod_presi and
							y.cod_entidad=a.cod_entidad and
							y.cod_tipo_inst=a.cod_tipo_inst and
							y.cod_inst=a.cod_inst and
							y.cod_dep=a.cod_dep and
							y.ano=a.ano and
							y.cod_sector=a.cod_sector and
							y.cod_programa=a.cod_programa limit 1)
						as deno_programa,
						a.cod_sub_prog,
						(select
							x.deno_sub_prog
							from v_cfpd05_denominaciones x
							where
							x.cod_presi=a.cod_presi and
							x.cod_entidad=a.cod_entidad and
							x.cod_tipo_inst=a.cod_tipo_inst and
							x.cod_inst=a.cod_inst and
							x.cod_dep=a.cod_dep and
							x.ano=a.ano and
							x.cod_sector=a.cod_sector and
							x.cod_programa=a.cod_programa and
							x.cod_sub_prog=a.cod_sub_prog limit 1)
						as deno_sub_prog,
						a.cod_proyecto,
						(select
							w.deno_proyecto
							from v_cfpd05_denominaciones w
							where
							w.cod_presi=a.cod_presi and
							w.cod_entidad=a.cod_entidad and
							w.cod_tipo_inst=a.cod_tipo_inst and
							w.cod_inst=a.cod_inst and
							w.cod_dep=a.cod_dep and
							w.ano=a.ano and
							w.cod_sector=a.cod_sector and
							w.cod_programa=a.cod_programa and
							w.cod_sub_prog=a.cod_sub_prog and
							w.cod_proyecto=a.cod_proyecto limit 1)
						as deno_proyecto,
						a.cod_activ_obra,
						(select
							u.deno_activ_obra
							from v_cfpd05_denominaciones u
							where
							u.cod_presi=a.cod_presi and
							u.cod_entidad=a.cod_entidad and
							u.cod_tipo_inst=a.cod_tipo_inst and
							u.cod_inst=a.cod_inst and
							u.cod_dep=a.cod_dep and
							u.ano=a.ano and
							u.cod_sector=a.cod_sector and
							u.cod_programa=a.cod_programa and
							u.cod_sub_prog=a.cod_sub_prog and
							u.cod_proyecto=a.cod_proyecto and u.cod_activ_obra=a.cod_activ_obra limit 1)
						as deno_activ_obra,
						a.cod_partida,
						(select v.deno_partida
							from v_cfpd05_denominaciones v
							where
							v.cod_presi=a.cod_presi and
							v.cod_entidad=a.cod_entidad and
							v.cod_tipo_inst=a.cod_tipo_inst and
							v.cod_inst=a.cod_inst and
							v.cod_dep=a.cod_dep and
							v.ano=a.ano and
							v.cod_sector=a.cod_sector and
							v.cod_programa=a.cod_programa and
							v.cod_sub_prog=a.cod_sub_prog and
							v.cod_proyecto=a.cod_proyecto and
							v.cod_activ_obra=a.cod_activ_obra and
							v.cod_partida=a.cod_partida limit 1)
						as deno_partida,
						a.cod_generica,
						(select t.deno_generica
							from v_cfpd05_denominaciones t
							where
							t.cod_presi=a.cod_presi and
							t.cod_entidad=a.cod_entidad and
							t.cod_tipo_inst=a.cod_tipo_inst and
							t.cod_inst=a.cod_inst and
							t.cod_dep=a.cod_dep and
							t.ano=a.ano and
							t.cod_sector=a.cod_sector and
							t.cod_programa=a.cod_programa and
							t.cod_sub_prog=a.cod_sub_prog and
							t.cod_proyecto=a.cod_proyecto and
							t.cod_activ_obra=a.cod_activ_obra and
							t.cod_partida=a.cod_partida and
							t.cod_partida=a.cod_partida and
							t.cod_generica=a.cod_generica limit 1)
						as deno_generica,
						a.cod_especifica,
						(select s.deno_especifica
							from v_cfpd05_denominaciones s
							where
							s.cod_presi=a.cod_presi and
							s.cod_entidad=a.cod_entidad and
							s.cod_tipo_inst=a.cod_tipo_inst and
							s.cod_inst=a.cod_inst and
							s.cod_dep=a.cod_dep and
							s.ano=a.ano and
							s.cod_sector=a.cod_sector and
							s.cod_programa=a.cod_programa and
							s.cod_sub_prog=a.cod_sub_prog and
							s.cod_proyecto=a.cod_proyecto and
							s.cod_activ_obra=a.cod_activ_obra and
							s.cod_partida=a.cod_partida and
							s.cod_partida=a.cod_partida and
							s.cod_generica=a.cod_generica and
							s.cod_especifica=a.cod_especifica limit 1)
						as deno_especifica,
						a.cod_sub_espec,
						(select r.deno_sub_espec
							from v_cfpd05_denominaciones r
							where
							r.cod_presi=a.cod_presi and
							r.cod_entidad=a.cod_entidad and
							r.cod_tipo_inst=a.cod_tipo_inst and
							r.cod_inst=a.cod_inst and
							r.cod_dep=a.cod_dep and
							r.ano=a.ano and
							r.cod_sector=a.cod_sector and
							r.cod_programa=a.cod_programa and
							r.cod_sub_prog=a.cod_sub_prog and
							r.cod_proyecto=a.cod_proyecto and
							r.cod_activ_obra=a.cod_activ_obra and
							r.cod_partida=a.cod_partida and
							r.cod_partida=a.cod_partida and
							r.cod_generica=a.cod_generica and
							r.cod_especifica=a.cod_especifica and
							r.cod_sub_espec=a.cod_sub_espec limit 1)
						as deno_sub_espe,
						a.cod_auxiliar,
						(select o.deno_auxiliar
							from v_cfpd05_denominaciones o
							where
							o.cod_presi=a.cod_presi and
							o.cod_entidad=a.cod_entidad and
							o.cod_tipo_inst=a.cod_tipo_inst and
							o.cod_inst=a.cod_inst and
							o.cod_dep=a.cod_dep and
							o.ano=a.ano and
							o.cod_sector=a.cod_sector and
							o.cod_programa=a.cod_programa and
							o.cod_sub_prog=a.cod_sub_prog and
							o.cod_proyecto=a.cod_proyecto and
							o.cod_activ_obra=a.cod_activ_obra and
							o.cod_partida=a.cod_partida and
							o.cod_partida=a.cod_partida and
							o.cod_generica=a.cod_generica and
							o.cod_especifica=a.cod_especifica and
							o.cod_sub_espec=a.cod_sub_espec and
							o.cod_auxiliar=a.cod_auxiliar limit 1)
						as deno_auxiliar,
						  a.cod_nivel_i,
						  a.cod_nivel_ii,
						  a.cod_ficha,
						  b.denominacion,
						  b.denominacion_devengado,
						  b.clasificacion_personal,
						  b.frecuencia_cobro,
						  b.dias_laborables,
						  b.horas_laborables,
						  b.descuentos_ley,
						  b.mensajes_colectivos,
						  b.status_nomina,
						  b.cantidad_pagos,
						  b.correspondiente,
						  b.frecuencia_pago,
						  b.numero_recibo,
						  b.control_autorizacion,
						  b.autorizacion_diskettes,
						  b.sueldo_sugerido,
						  b.ultimo_cargo,
						  b.ultima_ficha,
						  b.ano_desde,
						  b.ano_hasta,
						  b.codigo_transaccion,
						  b.dias_cobro,
						  c.denominacion_clase as denominacion_cargo


                 FROM

                          " . $tabla . " a, cnmd01 b, " . $vista . " c


                 WHERE

                           a.cod_presi            =  " . $cod_presi . "             and
						   a.cod_entidad          =  " . $cod_entidad . "           and
						   a.cod_tipo_inst        =  " . $cod_tipo_inst . "         and
						   a.cod_inst             =  " . $cod_inst . "              and
						   " . $dep_A . "
						   a.ano                     =  " . $year . "               and
						   " . $clasificacion . "
						   b.cod_presi            =  a.cod_presi                and
						   b.cod_entidad          =  a.cod_entidad              and
						   b.cod_tipo_inst        =  a.cod_tipo_inst            and
						   b.cod_inst             =  a.cod_inst                 and
						   b.cod_dep              =  a.cod_dep                  and
						   b.cod_tipo_nomina      =  a.cod_tipo_nomina          and
						   c.cod_presi            =  a.cod_presi                and
						   c.cod_entidad          =  a.cod_entidad              and
						   c.cod_tipo_inst        =  a.cod_tipo_inst            and
						   c.cod_inst             =  a.cod_inst                 and
						   c.cod_dep              =  a.cod_dep                  and
						   c.cod_tipo_nomina      =  a.cod_tipo_nomina          and
						   c.cod_cargo            =  a.cod_cargo                and
                           c.cod_puesto           =  a.cod_puesto


                  ORDER BY

                            a.cod_presi,
                            a.cod_entidad,
                            a.cod_tipo_inst,
                            a.cod_inst,
                            a.cod_sector,
							a.cod_programa,
							a.cod_sub_prog,
							a.cod_proyecto,
						    a.cod_activ_obra,
                            a.cod_puesto,
                            a.sueldo_basico,
                            a.cod_tipo_nomina,
                            a.cod_cargo


                            ASC

             ; ");

            $this->set('tipo', $tipo);
            $this->set('datos', $rs);
        }//fin$tipo


        $this->set('opcion', $opcion);
        $this->set('var1', $var1);
    }

//fin function

    function distribucion_recursos_humanos_cnmd05($var1 = null, $var2 = null) {


        $tabla = "";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');

        set_time_limit(0);

        $tabla = "cnmd05";
        $vista = "v_cnmd05";

        if ($var2 == null) {
            $this->layout = "ajax";
            $opcion = 1;

            $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst;
            $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
            $dato = null;
            foreach ($year as $year) {
                $dato_year = $year['cfpd01_formulacion']['ano_formular'];
            }
            $year_vista = $dato_year;
            $this->set('year', $year_vista);
        } else {
            ini_set("memory_limit", "2048M");
            $this->layout = "pdf";
            $opcion = 2;

            if (!empty($this->data['reporte3']['consolidacion'])) {
                $consolidacion = $this->data['reporte3']['consolidacion'];
            } else {
                $consolidacion = 2;
            }
            if (!empty($this->data['reporte3']['year'])) {
                $year = $this->data['reporte3']['year'];
            } else {
                $year = $this->ano_ejecucion();
            }
            if (!empty($this->data['reporte3']['tipo'])) {
                $tipo = $this->data['reporte3']['tipo'];
            } else {
                $tipo = 1;
            }

            if ($tipo != 15) {
                $clasificacion = "b.clasificacion_personal  = " . $tipo . "  and ";
            } else {
                $clasificacion = "  ";
            }


            if ($consolidacion == 1) {
                $dep_A = "";
                $dep_B = "";
            } else if ($consolidacion == 2) {
                $dep_A = " a.cod_dep   =  " . $cod_dep . "  and";
                $dep_B = " a.cod_dep, ";
            }//fin function

            $rs = $this->v_cfpd05_denominaciones->execute("

		         SELECT
			              a.cod_presi,
						  a.cod_entidad,
						  a.cod_tipo_inst,
						  a.cod_inst,
						  a.cod_dep,
						  a.cod_tipo_nomina,
						  a.cod_cargo,
						  a.cod_puesto,
						  a.sueldo_basico,
						  a.compensaciones,
						  a.primas,
						  a.bonos,
						  a.cod_dir_superior,
						  a.cod_coordinacion,
						  a.cod_secretaria,
						  a.cod_direccion,
						  a.cod_division,
						  a.cod_departamento,
						  a.cod_oficina,
						  a.cod_estado,
						  a.cod_municipio,
						  a.cod_parroquia,
						  a.cod_centro,
						  a.condicion_actividad,
						  a.ano,
						  a.cod_sector,
						(select
							z.deno_sector
							from v_cfpd05_denominaciones z
							where
							z.cod_presi=a.cod_presi and
							z.cod_entidad=a.cod_entidad and
							z.cod_tipo_inst=a.cod_tipo_inst and
							z.cod_inst=a.cod_inst and
							z.cod_dep=a.cod_dep and
							z.ano=a.ano and
							z.cod_sector=a.cod_sector limit 1)
						as deno_sector,
						a.cod_programa,
						(select
							y.deno_programa
							from v_cfpd05_denominaciones y
							where
							y.cod_presi=a.cod_presi and
							y.cod_entidad=a.cod_entidad and
							y.cod_tipo_inst=a.cod_tipo_inst and
							y.cod_inst=a.cod_inst and
							y.cod_dep=a.cod_dep and
							y.ano=a.ano and
							y.cod_sector=a.cod_sector and
							y.cod_programa=a.cod_programa limit 1)
						as deno_programa,
						a.cod_sub_prog,
						(select
							x.deno_sub_prog
							from v_cfpd05_denominaciones x
							where
							x.cod_presi=a.cod_presi and
							x.cod_entidad=a.cod_entidad and
							x.cod_tipo_inst=a.cod_tipo_inst and
							x.cod_inst=a.cod_inst and
							x.cod_dep=a.cod_dep and
							x.ano=a.ano and
							x.cod_sector=a.cod_sector and
							x.cod_programa=a.cod_programa and
							x.cod_sub_prog=a.cod_sub_prog limit 1)
						as deno_sub_prog,
						a.cod_proyecto,
						(select
							w.deno_proyecto
							from v_cfpd05_denominaciones w
							where
							w.cod_presi=a.cod_presi and
							w.cod_entidad=a.cod_entidad and
							w.cod_tipo_inst=a.cod_tipo_inst and
							w.cod_inst=a.cod_inst and
							w.cod_dep=a.cod_dep and
							w.ano=a.ano and
							w.cod_sector=a.cod_sector and
							w.cod_programa=a.cod_programa and
							w.cod_sub_prog=a.cod_sub_prog and
							w.cod_proyecto=a.cod_proyecto limit 1)
						as deno_proyecto,
						a.cod_activ_obra,
						(select
							u.deno_activ_obra
							from v_cfpd05_denominaciones u
							where
							u.cod_presi=a.cod_presi and
							u.cod_entidad=a.cod_entidad and
							u.cod_tipo_inst=a.cod_tipo_inst and
							u.cod_inst=a.cod_inst and
							u.cod_dep=a.cod_dep and
							u.ano=a.ano and
							u.cod_sector=a.cod_sector and
							u.cod_programa=a.cod_programa and
							u.cod_sub_prog=a.cod_sub_prog and
							u.cod_proyecto=a.cod_proyecto and u.cod_activ_obra=a.cod_activ_obra limit 1)
						as deno_activ_obra,
						a.cod_partida,
						(select v.deno_partida
							from v_cfpd05_denominaciones v
							where
							v.cod_presi=a.cod_presi and
							v.cod_entidad=a.cod_entidad and
							v.cod_tipo_inst=a.cod_tipo_inst and
							v.cod_inst=a.cod_inst and
							v.cod_dep=a.cod_dep and
							v.ano=a.ano and
							v.cod_sector=a.cod_sector and
							v.cod_programa=a.cod_programa and
							v.cod_sub_prog=a.cod_sub_prog and
							v.cod_proyecto=a.cod_proyecto and
							v.cod_activ_obra=a.cod_activ_obra and
							v.cod_partida=a.cod_partida limit 1)
						as deno_partida,
						a.cod_generica,
						(select t.deno_generica
							from v_cfpd05_denominaciones t
							where
							t.cod_presi=a.cod_presi and
							t.cod_entidad=a.cod_entidad and
							t.cod_tipo_inst=a.cod_tipo_inst and
							t.cod_inst=a.cod_inst and
							t.cod_dep=a.cod_dep and
							t.ano=a.ano and
							t.cod_sector=a.cod_sector and
							t.cod_programa=a.cod_programa and
							t.cod_sub_prog=a.cod_sub_prog and
							t.cod_proyecto=a.cod_proyecto and
							t.cod_activ_obra=a.cod_activ_obra and
							t.cod_partida=a.cod_partida and
							t.cod_partida=a.cod_partida and
							t.cod_generica=a.cod_generica limit 1)
						as deno_generica,
						a.cod_especifica,
						(select s.deno_especifica
							from v_cfpd05_denominaciones s
							where
							s.cod_presi=a.cod_presi and
							s.cod_entidad=a.cod_entidad and
							s.cod_tipo_inst=a.cod_tipo_inst and
							s.cod_inst=a.cod_inst and
							s.cod_dep=a.cod_dep and
							s.ano=a.ano and
							s.cod_sector=a.cod_sector and
							s.cod_programa=a.cod_programa and
							s.cod_sub_prog=a.cod_sub_prog and
							s.cod_proyecto=a.cod_proyecto and
							s.cod_activ_obra=a.cod_activ_obra and
							s.cod_partida=a.cod_partida and
							s.cod_partida=a.cod_partida and
							s.cod_generica=a.cod_generica and
							s.cod_especifica=a.cod_especifica limit 1)
						as deno_especifica,
						a.cod_sub_espec,
						(select r.deno_sub_espec
							from v_cfpd05_denominaciones r
							where
							r.cod_presi=a.cod_presi and
							r.cod_entidad=a.cod_entidad and
							r.cod_tipo_inst=a.cod_tipo_inst and
							r.cod_inst=a.cod_inst and
							r.cod_dep=a.cod_dep and
							r.ano=a.ano and
							r.cod_sector=a.cod_sector and
							r.cod_programa=a.cod_programa and
							r.cod_sub_prog=a.cod_sub_prog and
							r.cod_proyecto=a.cod_proyecto and
							r.cod_activ_obra=a.cod_activ_obra and
							r.cod_partida=a.cod_partida and
							r.cod_partida=a.cod_partida and
							r.cod_generica=a.cod_generica and
							r.cod_especifica=a.cod_especifica and
							r.cod_sub_espec=a.cod_sub_espec limit 1)
						as deno_sub_espe,
						a.cod_auxiliar,
						(select o.deno_auxiliar
							from v_cfpd05_denominaciones o
							where
							o.cod_presi=a.cod_presi and
							o.cod_entidad=a.cod_entidad and
							o.cod_tipo_inst=a.cod_tipo_inst and
							o.cod_inst=a.cod_inst and
							o.cod_dep=a.cod_dep and
							o.ano=a.ano and
							o.cod_sector=a.cod_sector and
							o.cod_programa=a.cod_programa and
							o.cod_sub_prog=a.cod_sub_prog and
							o.cod_proyecto=a.cod_proyecto and
							o.cod_activ_obra=a.cod_activ_obra and
							o.cod_partida=a.cod_partida and
							o.cod_partida=a.cod_partida and
							o.cod_generica=a.cod_generica and
							o.cod_especifica=a.cod_especifica and
							o.cod_sub_espec=a.cod_sub_espec and
							o.cod_auxiliar=a.cod_auxiliar limit 1)
						as deno_auxiliar,
						  a.cod_nivel_i,
						  a.cod_nivel_ii,
						  a.cod_ficha,
						  b.denominacion,
						  b.denominacion_devengado,
						  b.clasificacion_personal,
						  b.frecuencia_cobro,
						  b.dias_laborables,
						  b.horas_laborables,
						  b.descuentos_ley,
						  b.mensajes_colectivos,
						  b.status_nomina,
						  b.cantidad_pagos,
						  b.correspondiente,
						  b.frecuencia_pago,
						  b.numero_recibo,
						  b.control_autorizacion,
						  b.autorizacion_diskettes,
						  b.sueldo_sugerido,
						  b.ultimo_cargo,
						  b.ultima_ficha,
						  b.ano_desde,
						  b.ano_hasta,
						  b.codigo_transaccion,
						  b.dias_cobro,
						  c.denominacion_clase as denominacion_cargo


                 FROM

                          " . $tabla . " a, cnmd01 b, " . $vista . " c


                 WHERE

                           a.cod_presi            =  " . $cod_presi . "             and
						   a.cod_entidad          =  " . $cod_entidad . "           and
						   a.cod_tipo_inst        =  " . $cod_tipo_inst . "         and
						   a.cod_inst             =  " . $cod_inst . "              and
						   " . $dep_A . "
						   " . $clasificacion . "
						   b.cod_presi            =  a.cod_presi                and
						   b.cod_entidad          =  a.cod_entidad              and
						   b.cod_tipo_inst        =  a.cod_tipo_inst            and
						   b.cod_inst             =  a.cod_inst                 and
						   b.cod_dep              =  a.cod_dep                  and
						   b.cod_tipo_nomina      =  a.cod_tipo_nomina          and
						   c.cod_presi            =  a.cod_presi                and
						   c.cod_entidad          =  a.cod_entidad              and
						   c.cod_tipo_inst        =  a.cod_tipo_inst            and
						   c.cod_inst             =  a.cod_inst                 and
						   c.cod_dep              =  a.cod_dep                  and
						   c.cod_tipo_nomina      =  a.cod_tipo_nomina          and
						   c.cod_cargo            =  a.cod_cargo                and
                           c.cod_puesto           =  a.cod_puesto


                  ORDER BY

                            a.cod_presi,
                            a.cod_entidad,
                            a.cod_tipo_inst,
                            a.cod_inst,
                            a.cod_sector,
							a.cod_programa,
							a.cod_sub_prog,
							a.cod_proyecto,
						    a.cod_activ_obra,
                            a.cod_puesto,
                            a.sueldo_basico,
                            a.cod_tipo_nomina,
                            a.cod_cargo


                            ASC

             ; ");

            $this->set('tipo', $tipo);
            $this->set('datos', $rs);
        }//fin$tipo


        $this->set('opcion', $opcion);
        $this->set('var1', $var1);

    }

//fin function

    function distribucion_recursos_humanos_sueldos_cfpd97($var1 = null, $var2 = null) {


        $tabla = "";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');

        set_time_limit(0);

        $tabla = "cfpd97";
        $vista = "v_cfpd97";

        /*
          Nota: Se debe agrupar por cod_sector  cod_programa  cod_sub_prog  cod_proyecto  cod_activ_obra  cod_puesto de la tabla cfpd07 o cnmd05
          para los empleados se debe separar los tipos de nominas donde el campo clasificacion_personal de la tabla cnmd01 sea diferente a 1
         */


        if ($var2 == null) {
            $this->layout = "ajax";
            $opcion = 1;
            $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst;
            $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
            $dato = null;
            foreach ($year as $year) {
                $dato_year = $year['cfpd01_formulacion']['ano_formular'];
            }
            $year_vista = $dato_year;
            $this->set('year', $year_vista);
        } else {
            $this->layout = "pdf";
            $opcion = 2;

            if (!empty($this->data['reporte3']['consolidacion'])) {
                $consolidacion = $this->data['reporte3']['consolidacion'];
            } else {
                $consolidacion = 2;
            }
            if (!empty($this->data['reporte3']['year'])) {
                $year = $this->data['reporte3']['year'];
            } else {
                $year = $this->ano_ejecucion();
            }
            if (!empty($this->data['reporte3']['tipo'])) {
                $tipo = $this->data['reporte3']['tipo'];
            } else {
                $tipo = 1;
            }

            if ($tipo != 15) {
                $clasificacion = "b.clasificacion_personal  = " . $tipo . "  and ";
            } else {
                $clasificacion = "  ";
            }


            if (isset($this->data['cfpp05']['consolidacion'])) {
                if ($this->data['cfpp05']['consolidacion'] == 2) {
                    $dep_A = " a.cod_dep   =  " . $_SESSION['cod_dep_reporte_consolidado'] . "  and";
                    $dep_B = " a.cod_dep, ";
                    $titulo_a = $_SESSION["dependencia_reporte_consolidado"];
                } else if ($this->data['cfpp05']['consolidacion'] == 1) {
                    $dep_A = "";
                    $dep_B = "";
                    $titulo_a = $this->Session->read('dependencia');
                }
            } else {

                $dep_A = "";
                $dep_B = "";
                $titulo_a = $this->Session->read('dependencia');
            }

            $this->set('titulo_a', $titulo_a);

            $rs = $this->v_cfpd05_denominaciones->execute("

		         SELECT
			              a.cod_presi,
						  a.cod_entidad,
						  a.cod_tipo_inst,
						  a.cod_inst,
						  a.cod_dep,
						  a.cod_tipo_nomina,
						  a.cod_cargo,
						  a.cod_puesto,
						  a.sueldo_basico,
						  a.compensaciones,
						  a.primas,
						  a.bonos,
						  a.cod_dir_superior,
						  a.cod_coordinacion,
						  a.cod_secretaria,
						  a.cod_direccion,
						  a.cod_division,
						  a.cod_departamento,
						  a.cod_oficina,
						  a.cod_estado,
						  a.cod_municipio,
						  a.cod_parroquia,
						  a.cod_centro,
						  a.condicion_actividad,
						  a.ano,
						  a.cod_sector,
						(select
							z.deno_sector
							from v_cfpd05_denominaciones z
							where
							z.cod_presi=a.cod_presi and
							z.cod_entidad=a.cod_entidad and
							z.cod_tipo_inst=a.cod_tipo_inst and
							z.cod_inst=a.cod_inst and
							z.cod_dep=a.cod_dep and
							z.ano=a.ano and
							z.cod_sector=a.cod_sector limit 1)
						as deno_sector,
						a.cod_programa,
						(select
							y.deno_programa
							from v_cfpd05_denominaciones y
							where
							y.cod_presi=a.cod_presi and
							y.cod_entidad=a.cod_entidad and
							y.cod_tipo_inst=a.cod_tipo_inst and
							y.cod_inst=a.cod_inst and
							y.cod_dep=a.cod_dep and
							y.ano=a.ano and
							y.cod_sector=a.cod_sector and
							y.cod_programa=a.cod_programa limit 1)
						as deno_programa,
						a.cod_sub_prog,
						(select
							x.deno_sub_prog
							from v_cfpd05_denominaciones x
							where
							x.cod_presi=a.cod_presi and
							x.cod_entidad=a.cod_entidad and
							x.cod_tipo_inst=a.cod_tipo_inst and
							x.cod_inst=a.cod_inst and
							x.cod_dep=a.cod_dep and
							x.ano=a.ano and
							x.cod_sector=a.cod_sector and
							x.cod_programa=a.cod_programa and
							x.cod_sub_prog=a.cod_sub_prog limit 1)
						as deno_sub_prog,
						a.cod_proyecto,
						(select
							w.deno_proyecto
							from v_cfpd05_denominaciones w
							where
							w.cod_presi=a.cod_presi and
							w.cod_entidad=a.cod_entidad and
							w.cod_tipo_inst=a.cod_tipo_inst and
							w.cod_inst=a.cod_inst and
							w.cod_dep=a.cod_dep and
							w.ano=a.ano and
							w.cod_sector=a.cod_sector and
							w.cod_programa=a.cod_programa and
							w.cod_sub_prog=a.cod_sub_prog and
							w.cod_proyecto=a.cod_proyecto limit 1)
						as deno_proyecto,
						a.cod_activ_obra,
						(select
							u.deno_activ_obra
							from v_cfpd05_denominaciones u
							where
							u.cod_presi=a.cod_presi and
							u.cod_entidad=a.cod_entidad and
							u.cod_tipo_inst=a.cod_tipo_inst and
							u.cod_inst=a.cod_inst and
							u.cod_dep=a.cod_dep and
							u.ano=a.ano and
							u.cod_sector=a.cod_sector and
							u.cod_programa=a.cod_programa and
							u.cod_sub_prog=a.cod_sub_prog and
							u.cod_proyecto=a.cod_proyecto and u.cod_activ_obra=a.cod_activ_obra limit 1)
						as deno_activ_obra,
						a.cod_partida,
						(select v.deno_partida
							from v_cfpd05_denominaciones v
							where
							v.cod_presi=a.cod_presi and
							v.cod_entidad=a.cod_entidad and
							v.cod_tipo_inst=a.cod_tipo_inst and
							v.cod_inst=a.cod_inst and
							v.cod_dep=a.cod_dep and
							v.ano=a.ano and
							v.cod_sector=a.cod_sector and
							v.cod_programa=a.cod_programa and
							v.cod_sub_prog=a.cod_sub_prog and
							v.cod_proyecto=a.cod_proyecto and
							v.cod_activ_obra=a.cod_activ_obra and
							v.cod_partida=a.cod_partida limit 1)
						as deno_partida,
						a.cod_generica,
						(select t.deno_generica
							from v_cfpd05_denominaciones t
							where
							t.cod_presi=a.cod_presi and
							t.cod_entidad=a.cod_entidad and
							t.cod_tipo_inst=a.cod_tipo_inst and
							t.cod_inst=a.cod_inst and
							t.cod_dep=a.cod_dep and
							t.ano=a.ano and
							t.cod_sector=a.cod_sector and
							t.cod_programa=a.cod_programa and
							t.cod_sub_prog=a.cod_sub_prog and
							t.cod_proyecto=a.cod_proyecto and
							t.cod_activ_obra=a.cod_activ_obra and
							t.cod_partida=a.cod_partida and
							t.cod_partida=a.cod_partida and
							t.cod_generica=a.cod_generica limit 1)
						as deno_generica,
						a.cod_especifica,
						(select s.deno_especifica
							from v_cfpd05_denominaciones s
							where
							s.cod_presi=a.cod_presi and
							s.cod_entidad=a.cod_entidad and
							s.cod_tipo_inst=a.cod_tipo_inst and
							s.cod_inst=a.cod_inst and
							s.cod_dep=a.cod_dep and
							s.ano=a.ano and
							s.cod_sector=a.cod_sector and
							s.cod_programa=a.cod_programa and
							s.cod_sub_prog=a.cod_sub_prog and
							s.cod_proyecto=a.cod_proyecto and
							s.cod_activ_obra=a.cod_activ_obra and
							s.cod_partida=a.cod_partida and
							s.cod_partida=a.cod_partida and
							s.cod_generica=a.cod_generica and
							s.cod_especifica=a.cod_especifica limit 1)
						as deno_especifica,
						a.cod_sub_espec,
						(select r.deno_sub_espec
							from v_cfpd05_denominaciones r
							where
							r.cod_presi=a.cod_presi and
							r.cod_entidad=a.cod_entidad and
							r.cod_tipo_inst=a.cod_tipo_inst and
							r.cod_inst=a.cod_inst and
							r.cod_dep=a.cod_dep and
							r.ano=a.ano and
							r.cod_sector=a.cod_sector and
							r.cod_programa=a.cod_programa and
							r.cod_sub_prog=a.cod_sub_prog and
							r.cod_proyecto=a.cod_proyecto and
							r.cod_activ_obra=a.cod_activ_obra and
							r.cod_partida=a.cod_partida and
							r.cod_partida=a.cod_partida and
							r.cod_generica=a.cod_generica and
							r.cod_especifica=a.cod_especifica and
							r.cod_sub_espec=a.cod_sub_espec limit 1)
						as deno_sub_espe,
						a.cod_auxiliar,
						(select o.deno_auxiliar
							from v_cfpd05_denominaciones o
							where
							o.cod_presi=a.cod_presi and
							o.cod_entidad=a.cod_entidad and
							o.cod_tipo_inst=a.cod_tipo_inst and
							o.cod_inst=a.cod_inst and
							o.cod_dep=a.cod_dep and
							o.ano=a.ano and
							o.cod_sector=a.cod_sector and
							o.cod_programa=a.cod_programa and
							o.cod_sub_prog=a.cod_sub_prog and
							o.cod_proyecto=a.cod_proyecto and
							o.cod_activ_obra=a.cod_activ_obra and
							o.cod_partida=a.cod_partida and
							o.cod_partida=a.cod_partida and
							o.cod_generica=a.cod_generica and
							o.cod_especifica=a.cod_especifica and
							o.cod_sub_espec=a.cod_sub_espec and
							o.cod_auxiliar=a.cod_auxiliar limit 1)
						as deno_auxiliar,
						  a.cod_nivel_i,
						  a.cod_nivel_ii,
						  a.cod_ficha,
						  b.denominacion,
						  b.denominacion_devengado,
						  b.clasificacion_personal,
						  b.frecuencia_cobro,
						  b.dias_laborables,
						  b.horas_laborables,
						  b.descuentos_ley,
						  b.mensajes_colectivos,
						  b.status_nomina,
						  b.cantidad_pagos,
						  b.correspondiente,
						  b.frecuencia_pago,
						  b.numero_recibo,
						  b.control_autorizacion,
						  b.autorizacion_diskettes,
						  b.sueldo_sugerido,
						  b.ultimo_cargo,
						  b.ultima_ficha,
						  b.ano_desde,
						  b.ano_hasta,
						  b.codigo_transaccion,
						  b.dias_cobro,
						  c.denominacion_clase as denominacion_cargo,
						  (SELECT denominacion FROM cugd02_dependencias b WHERE b.cod_tipo_institucion = a.cod_tipo_inst and b.cod_institucion  =  a.cod_inst and b.cod_dependencia   =  a.cod_dep) as denominacion_dep



                 FROM

                          " . $tabla . " a, cnmd01 b, " . $vista . " c


                 WHERE

                           a.cod_presi            =  " . $cod_presi . "             and
						   a.cod_entidad          =  " . $cod_entidad . "           and
						   a.cod_tipo_inst        =  " . $cod_tipo_inst . "         and
						   a.cod_inst             =  " . $cod_inst . "              and
						   " . $dep_A . "
						   a.ano                     =  " . $year . "               and
						   " . $clasificacion . "
						   b.cod_presi            =  a.cod_presi                and
						   b.cod_entidad          =  a.cod_entidad              and
						   b.cod_tipo_inst        =  a.cod_tipo_inst            and
						   b.cod_inst             =  a.cod_inst                 and
						   b.cod_dep              =  a.cod_dep                  and
						   b.cod_tipo_nomina      =  a.cod_tipo_nomina          and
						   c.cod_presi            =  a.cod_presi                and
						   c.cod_entidad          =  a.cod_entidad              and
						   c.cod_tipo_inst        =  a.cod_tipo_inst            and
						   c.cod_inst             =  a.cod_inst                 and
						   c.cod_dep              =  a.cod_dep                  and
						   c.cod_tipo_nomina      =  a.cod_tipo_nomina          and
						   c.cod_cargo            =  a.cod_cargo                and
                           c.cod_puesto           =  a.cod_puesto


                  ORDER BY

                            a.cod_presi,
                            a.cod_entidad,
                            a.cod_tipo_inst,
                            a.cod_inst,
                            a.cod_puesto,
                            a.cod_dep,
                            a.cod_tipo_nomina,
                            a.cod_cargo


                            ASC

             ; ");

            $this->set('tipo', $tipo);
            $this->set('datos', $rs);
        }//fin$tipo


        $this->set('opcion', $opcion);
        $this->set('var1', $var1);
    }

//fin function

    function distribucion_recursos_humanos_sueldos_cnmd05($var1 = null, $var2 = null) {


        $tabla = "";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');

        set_time_limit(0);


        $tabla = "cnmd05";
        $vista = "v_cnmd05";
        /*
          Nota: Se debe agrupar por cod_sector  cod_programa  cod_sub_prog  cod_proyecto  cod_activ_obra  cod_puesto de la tabla cfpd07 o cnmd05
          para los empleados se debe separar los tipos de nominas donde el campo clasificacion_personal de la tabla cnmd01 sea diferente a 1
         */


        if ($var2 == null) {
            $this->layout = "ajax";
            $opcion = 1;
            $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst;
            $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
            $dato = null;
            foreach ($year as $year) {
                $dato_year = $year['cfpd01_formulacion']['ano_formular'];
            }
            if ($var1 == 1) {
                $year_vista = $dato_year;
            } else {
                $year_vista = $this->ano_ejecucion();
            }
            $this->set('year', $year_vista);
        } else {
            ini_set("memory_limit", "2048M");
            $this->layout = "pdf";
            $opcion = 2;

            if (!empty($this->data['reporte3']['consolidacion'])) {
                $consolidacion = $this->data['reporte3']['consolidacion'];
            } else {
                $consolidacion = 2;
            }
            if (!empty($this->data['reporte3']['year'])) {
                $year = $this->data['reporte3']['year'];
            } else {
                $year = $this->ano_ejecucion();
            }
            if (!empty($this->data['reporte3']['tipo'])) {
                $tipo = $this->data['reporte3']['tipo'];
            } else {
                $tipo = 1;
            }

            if ($tipo != 15) {
                $clasificacion = "b.clasificacion_personal  = " . $tipo . "  and ";
            } else {
                $clasificacion = "  ";
            }


            if ($consolidacion == 1) {
                $dep_A = "";
                $dep_B = "";
            } else if ($consolidacion == 2) {
                $dep_A = " a.cod_dep   =  " . $cod_dep . "  and";
                $dep_B = " a.cod_dep, ";
            }//fin function

            $rs = $this->v_cfpd05_denominaciones->execute("

		         SELECT
			              a.cod_presi,
						  a.cod_entidad,
						  a.cod_tipo_inst,
						  a.cod_inst,
						  a.cod_dep,
						  a.cod_tipo_nomina,
						  a.cod_cargo,
						  a.cod_puesto,
						  a.sueldo_basico,
						  a.compensaciones,
						  a.primas,
						  a.bonos,
						  a.cod_dir_superior,
						  a.cod_coordinacion,
						  a.cod_secretaria,
						  a.cod_direccion,
						  a.cod_division,
						  a.cod_departamento,
						  a.cod_oficina,
						  a.cod_estado,
						  a.cod_municipio,
						  a.cod_parroquia,
						  a.cod_centro,
						  a.condicion_actividad,
						  a.ano,
						  a.cod_sector,
						(select
							z.deno_sector
							from v_cfpd05_denominaciones z
							where
							z.cod_presi=a.cod_presi and
							z.cod_entidad=a.cod_entidad and
							z.cod_tipo_inst=a.cod_tipo_inst and
							z.cod_inst=a.cod_inst and
							z.cod_dep=a.cod_dep and
							z.ano=a.ano and
							z.cod_sector=a.cod_sector limit 1)
						as deno_sector,
						a.cod_programa,
						(select
							y.deno_programa
							from v_cfpd05_denominaciones y
							where
							y.cod_presi=a.cod_presi and
							y.cod_entidad=a.cod_entidad and
							y.cod_tipo_inst=a.cod_tipo_inst and
							y.cod_inst=a.cod_inst and
							y.cod_dep=a.cod_dep and
							y.ano=a.ano and
							y.cod_sector=a.cod_sector and
							y.cod_programa=a.cod_programa limit 1)
						as deno_programa,
						a.cod_sub_prog,
						(select
							x.deno_sub_prog
							from v_cfpd05_denominaciones x
							where
							x.cod_presi=a.cod_presi and
							x.cod_entidad=a.cod_entidad and
							x.cod_tipo_inst=a.cod_tipo_inst and
							x.cod_inst=a.cod_inst and
							x.cod_dep=a.cod_dep and
							x.ano=a.ano and
							x.cod_sector=a.cod_sector and
							x.cod_programa=a.cod_programa and
							x.cod_sub_prog=a.cod_sub_prog limit 1)
						as deno_sub_prog,
						a.cod_proyecto,
						(select
							w.deno_proyecto
							from v_cfpd05_denominaciones w
							where
							w.cod_presi=a.cod_presi and
							w.cod_entidad=a.cod_entidad and
							w.cod_tipo_inst=a.cod_tipo_inst and
							w.cod_inst=a.cod_inst and
							w.cod_dep=a.cod_dep and
							w.ano=a.ano and
							w.cod_sector=a.cod_sector and
							w.cod_programa=a.cod_programa and
							w.cod_sub_prog=a.cod_sub_prog and
							w.cod_proyecto=a.cod_proyecto limit 1)
						as deno_proyecto,
						a.cod_activ_obra,
						(select
							u.deno_activ_obra
							from v_cfpd05_denominaciones u
							where
							u.cod_presi=a.cod_presi and
							u.cod_entidad=a.cod_entidad and
							u.cod_tipo_inst=a.cod_tipo_inst and
							u.cod_inst=a.cod_inst and
							u.cod_dep=a.cod_dep and
							u.ano=a.ano and
							u.cod_sector=a.cod_sector and
							u.cod_programa=a.cod_programa and
							u.cod_sub_prog=a.cod_sub_prog and
							u.cod_proyecto=a.cod_proyecto and u.cod_activ_obra=a.cod_activ_obra limit 1)
						as deno_activ_obra,
						a.cod_partida,
						(select v.deno_partida
							from v_cfpd05_denominaciones v
							where
							v.cod_presi=a.cod_presi and
							v.cod_entidad=a.cod_entidad and
							v.cod_tipo_inst=a.cod_tipo_inst and
							v.cod_inst=a.cod_inst and
							v.cod_dep=a.cod_dep and
							v.ano=a.ano and
							v.cod_sector=a.cod_sector and
							v.cod_programa=a.cod_programa and
							v.cod_sub_prog=a.cod_sub_prog and
							v.cod_proyecto=a.cod_proyecto and
							v.cod_activ_obra=a.cod_activ_obra and
							v.cod_partida=a.cod_partida limit 1)
						as deno_partida,
						a.cod_generica,
						(select t.deno_generica
							from v_cfpd05_denominaciones t
							where
							t.cod_presi=a.cod_presi and
							t.cod_entidad=a.cod_entidad and
							t.cod_tipo_inst=a.cod_tipo_inst and
							t.cod_inst=a.cod_inst and
							t.cod_dep=a.cod_dep and
							t.ano=a.ano and
							t.cod_sector=a.cod_sector and
							t.cod_programa=a.cod_programa and
							t.cod_sub_prog=a.cod_sub_prog and
							t.cod_proyecto=a.cod_proyecto and
							t.cod_activ_obra=a.cod_activ_obra and
							t.cod_partida=a.cod_partida and
							t.cod_partida=a.cod_partida and
							t.cod_generica=a.cod_generica limit 1)
						as deno_generica,
						a.cod_especifica,
						(select s.deno_especifica
							from v_cfpd05_denominaciones s
							where
							s.cod_presi=a.cod_presi and
							s.cod_entidad=a.cod_entidad and
							s.cod_tipo_inst=a.cod_tipo_inst and
							s.cod_inst=a.cod_inst and
							s.cod_dep=a.cod_dep and
							s.ano=a.ano and
							s.cod_sector=a.cod_sector and
							s.cod_programa=a.cod_programa and
							s.cod_sub_prog=a.cod_sub_prog and
							s.cod_proyecto=a.cod_proyecto and
							s.cod_activ_obra=a.cod_activ_obra and
							s.cod_partida=a.cod_partida and
							s.cod_partida=a.cod_partida and
							s.cod_generica=a.cod_generica and
							s.cod_especifica=a.cod_especifica limit 1)
						as deno_especifica,
						a.cod_sub_espec,
						(select r.deno_sub_espec
							from v_cfpd05_denominaciones r
							where
							r.cod_presi=a.cod_presi and
							r.cod_entidad=a.cod_entidad and
							r.cod_tipo_inst=a.cod_tipo_inst and
							r.cod_inst=a.cod_inst and
							r.cod_dep=a.cod_dep and
							r.ano=a.ano and
							r.cod_sector=a.cod_sector and
							r.cod_programa=a.cod_programa and
							r.cod_sub_prog=a.cod_sub_prog and
							r.cod_proyecto=a.cod_proyecto and
							r.cod_activ_obra=a.cod_activ_obra and
							r.cod_partida=a.cod_partida and
							r.cod_partida=a.cod_partida and
							r.cod_generica=a.cod_generica and
							r.cod_especifica=a.cod_especifica and
							r.cod_sub_espec=a.cod_sub_espec limit 1)
						as deno_sub_espe,
						a.cod_auxiliar,
						(select o.deno_auxiliar
							from v_cfpd05_denominaciones o
							where
							o.cod_presi=a.cod_presi and
							o.cod_entidad=a.cod_entidad and
							o.cod_tipo_inst=a.cod_tipo_inst and
							o.cod_inst=a.cod_inst and
							o.cod_dep=a.cod_dep and
							o.ano=a.ano and
							o.cod_sector=a.cod_sector and
							o.cod_programa=a.cod_programa and
							o.cod_sub_prog=a.cod_sub_prog and
							o.cod_proyecto=a.cod_proyecto and
							o.cod_activ_obra=a.cod_activ_obra and
							o.cod_partida=a.cod_partida and
							o.cod_partida=a.cod_partida and
							o.cod_generica=a.cod_generica and
							o.cod_especifica=a.cod_especifica and
							o.cod_sub_espec=a.cod_sub_espec and
							o.cod_auxiliar=a.cod_auxiliar limit 1)
						as deno_auxiliar,
						  a.cod_nivel_i,
						  a.cod_nivel_ii,
						  a.cod_ficha,
						  b.denominacion,
						  b.denominacion_devengado,
						  b.clasificacion_personal,
						  b.frecuencia_cobro,
						  b.dias_laborables,
						  b.horas_laborables,
						  b.descuentos_ley,
						  b.mensajes_colectivos,
						  b.status_nomina,
						  b.cantidad_pagos,
						  b.correspondiente,
						  b.frecuencia_pago,
						  b.numero_recibo,
						  b.control_autorizacion,
						  b.autorizacion_diskettes,
						  b.sueldo_sugerido,
						  b.ultimo_cargo,
						  b.ultima_ficha,
						  b.ano_desde,
						  b.ano_hasta,
						  b.codigo_transaccion,
						  b.dias_cobro,
						  c.denominacion_clase as denominacion_cargo,
						  (SELECT denominacion FROM cugd02_dependencias b WHERE b.cod_tipo_institucion = a.cod_tipo_inst and b.cod_institucion  =  a.cod_inst and b.cod_dependencia   =  a.cod_dep) as denominacion_dep



                 FROM

                          " . $tabla . " a, cnmd01 b, " . $vista . " c


                 WHERE

                           a.cod_presi            =  " . $cod_presi . "             and
						   a.cod_entidad          =  " . $cod_entidad . "           and
						   a.cod_tipo_inst        =  " . $cod_tipo_inst . "         and
						   a.cod_inst             =  " . $cod_inst . "              and
						   " . $dep_A . "
						   " . $clasificacion . "
						   b.cod_presi            =  a.cod_presi                and
						   b.cod_entidad          =  a.cod_entidad              and
						   b.cod_tipo_inst        =  a.cod_tipo_inst            and
						   b.cod_inst             =  a.cod_inst                 and
						   b.cod_dep              =  a.cod_dep                  and
						   b.cod_tipo_nomina      =  a.cod_tipo_nomina          and
						   c.cod_presi            =  a.cod_presi                and
						   c.cod_entidad          =  a.cod_entidad              and
						   c.cod_tipo_inst        =  a.cod_tipo_inst            and
						   c.cod_inst             =  a.cod_inst                 and
						   c.cod_dep              =  a.cod_dep                  and
						   c.cod_tipo_nomina      =  a.cod_tipo_nomina          and
						   c.cod_cargo            =  a.cod_cargo                and
                           c.cod_puesto           =  a.cod_puesto


                  ORDER BY

                            a.cod_presi,
                            a.cod_entidad,
                            a.cod_tipo_inst,
                            a.cod_inst,
                            a.cod_puesto,
                            a.cod_dep,
                            a.cod_tipo_nomina,
                            a.cod_cargo


                            ASC

             ; ");

            $this->set('tipo', $tipo);
            $this->set('datos', $rs);
        }//fin$tipo


        $this->set('opcion', $opcion);
        $this->set('var1', $var1);
    }

//fin function

    function cargos_disponibles_ordenados_por_cargos($var1 = null, $var2 = null) {




        $tabla = "";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');

        set_time_limit(0);

        $tabla = "cnmd05";
        $vista = "v_cnmd05";



        if ($var2 == null) {
            $this->layout = "ajax";
            $opcion = 1;
            $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst;
            $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
            $dato = null;
            foreach ($year as $year) {
                $dato_year = $year['cfpd01_formulacion']['ano_formular'];
            }
            if ($var1 == 1) {
                $year_vista = $dato_year;
            } else {
                $year_vista = $this->ano_ejecucion();
            }
            $this->set('year', $year_vista);

            //$Lista = $this->v_cnmd05->generateList($this->SQLCA(), 'cod_tipo_nomina ASC', null, '{n}.v_cnmd05.cod_tipo_nomina', '{n}.v_cnmd05.tipo_nomina');
            //$this->concatena($Lista, 'cod_tipo_nomina');

            $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
            $this->concatenaN($lista, 'nomina');
        } else {
            $this->layout = "pdf";
            $opcion = 2;

            if (!empty($this->data['reporte3']['consolidacion'])) {
                $consolidacion = $this->data['reporte3']['consolidacion'];
            } else {
                $consolidacion = 2;
            }
            if (!empty($this->data['reporte3']['year'])) {
                $year = $this->data['reporte3']['year'];
            } else {
                $year = $this->ano_ejecucion();
            }
            if (!empty($this->data['reporte3']['tipo'])) {
                $tipo = $this->data['reporte3']['tipo'];
            } else {
                $tipo = 1;
            }
            if (!empty($this->data['reporte3']['frecuencia'])) {
                $frecuencia = $this->data['reporte3']['frecuencia'];
            } else {
                $frecuencia = 1;
            }
            if (!empty($this->data['reporte3']['ordenamiento'])) {
                $ordenamiento = $this->data['reporte3']['ordenamiento'];
            } else {
                $ordenamiento = 1;
            }



            $tipo_nomina = "a.cod_tipo_nomina  = " . $tipo . "  and ";



            //$frecuencia = "b.frecuencia_cobro  = ".$frecuencia."  and ";


            if ($consolidacion == 1) {
                $dep_A = "";
                $dep_B = "";
            } else if ($consolidacion == 2) {
                $dep_A = " a.cod_dep   =  " . $cod_dep . "  and";
                $dep_B = " a.cod_dep, ";
            }//fin function


            $this->set('ordenamiento2', $ordenamiento);


            if ($ordenamiento == 1) {

                $ordenamiento = "	   	 ORDER BY
				                            a.cod_presi,
				                            a.cod_entidad,
				                            a.cod_tipo_inst,
				                            a.cod_inst,
				                            a.cod_dep,
				                            a.cod_tipo_nomina,
				                            a.cod_puesto,
				                            a.cod_cargo ";
            } else if ($ordenamiento == 2) {

                $ordenamiento = "	   	 ORDER BY
				                            a.cod_presi,
				                            a.cod_entidad,
				                            a.cod_tipo_inst,
				                            a.cod_inst,
				                            a.cod_dep,
				                            a.cod_tipo_nomina,
				                            a.cod_cargo,
				                            a.cod_puesto ";
            } else if ($ordenamiento == 3) {



                $ordenamiento = "	   	 ORDER BY
				                            a.cod_presi,
				                            a.cod_entidad,
				                            a.cod_tipo_inst,
				                            a.cod_inst,
				                            a.cod_dep,
				                            a.cod_tipo_nomina,
				                            a.cod_nivel_i,
				                            a.cod_nivel_ii,
				                            a.cod_cargo,
				                            a.cod_puesto ";
            }//fin else
            // TODOS - VACANTES - OCUPADOS
            $vacantes = $this->data['reporte3']['vacante'];
            if ($vacantes == 1) {
                $cond_vacante = " a.condicion_actividad=1 AND"; // vacante
            } else if ($vacantes == 2) {
                $cond_vacante = " a.condicion_actividad=2 AND "; // Ocupado
            } else if ($vacantes == 3) {
                $cond_vacante = " "; // todos
            }


            $rs = $this->v_cfpd05_denominaciones->execute("

		         SELECT
			              a.cod_presi,
						  a.cod_entidad,
						  a.cod_tipo_inst,
						  a.cod_inst,
						  a.cod_dep,
						  a.cod_tipo_nomina,
						  a.cod_cargo,
						  a.cod_puesto,
						  a.sueldo_basico,
						  a.compensaciones,
						  a.primas,
						  a.bonos,
						  a.cod_dir_superior,
						  a.cod_coordinacion,
						  a.cod_secretaria,
						  a.cod_direccion,
						  a.cod_division,
						  a.cod_departamento,
						  a.cod_oficina,
						  a.cod_estado,
						  a.cod_municipio,
						  a.cod_parroquia,
						  a.cod_centro,
						  a.condicion_actividad,
						  a.ano,
						  a.cod_sector,
						(select
							z.deno_sector
							from v_cfpd05_denominaciones z
							where
							z.cod_presi=a.cod_presi and
							z.cod_entidad=a.cod_entidad and
							z.cod_tipo_inst=a.cod_tipo_inst and
							z.cod_inst=a.cod_inst and
							z.cod_dep=a.cod_dep and
							z.ano=a.ano and
							z.cod_sector=a.cod_sector limit 1)
						as deno_sector,
						a.cod_programa,
						(select
							y.deno_programa
							from v_cfpd05_denominaciones y
							where
							y.cod_presi=a.cod_presi and
							y.cod_entidad=a.cod_entidad and
							y.cod_tipo_inst=a.cod_tipo_inst and
							y.cod_inst=a.cod_inst and
							y.cod_dep=a.cod_dep and
							y.ano=a.ano and
							y.cod_sector=a.cod_sector and
							y.cod_programa=a.cod_programa limit 1)
						as deno_programa,
						a.cod_sub_prog,
						(select
							x.deno_sub_prog
							from v_cfpd05_denominaciones x
							where
							x.cod_presi=a.cod_presi and
							x.cod_entidad=a.cod_entidad and
							x.cod_tipo_inst=a.cod_tipo_inst and
							x.cod_inst=a.cod_inst and
							x.cod_dep=a.cod_dep and
							x.ano=a.ano and
							x.cod_sector=a.cod_sector and
							x.cod_programa=a.cod_programa and
							x.cod_sub_prog=a.cod_sub_prog limit 1)
						as deno_sub_prog,
						a.cod_proyecto,
						(select
							w.deno_proyecto
							from v_cfpd05_denominaciones w
							where
							w.cod_presi=a.cod_presi and
							w.cod_entidad=a.cod_entidad and
							w.cod_tipo_inst=a.cod_tipo_inst and
							w.cod_inst=a.cod_inst and
							w.cod_dep=a.cod_dep and
							w.ano=a.ano and
							w.cod_sector=a.cod_sector and
							w.cod_programa=a.cod_programa and
							w.cod_sub_prog=a.cod_sub_prog and
							w.cod_proyecto=a.cod_proyecto limit 1)
						as deno_proyecto,
						a.cod_activ_obra,
						(select
							u.deno_activ_obra
							from v_cfpd05_denominaciones u
							where
							u.cod_presi=a.cod_presi and
							u.cod_entidad=a.cod_entidad and
							u.cod_tipo_inst=a.cod_tipo_inst and
							u.cod_inst=a.cod_inst and
							u.cod_dep=a.cod_dep and
							u.ano=a.ano and
							u.cod_sector=a.cod_sector and
							u.cod_programa=a.cod_programa and
							u.cod_sub_prog=a.cod_sub_prog and
							u.cod_proyecto=a.cod_proyecto and u.cod_activ_obra=a.cod_activ_obra limit 1)
						as deno_activ_obra,
						a.cod_partida,
						(select v.deno_partida
							from v_cfpd05_denominaciones v
							where
							v.cod_presi=a.cod_presi and
							v.cod_entidad=a.cod_entidad and
							v.cod_tipo_inst=a.cod_tipo_inst and
							v.cod_inst=a.cod_inst and
							v.cod_dep=a.cod_dep and
							v.ano=a.ano and
							v.cod_sector=a.cod_sector and
							v.cod_programa=a.cod_programa and
							v.cod_sub_prog=a.cod_sub_prog and
							v.cod_proyecto=a.cod_proyecto and
							v.cod_activ_obra=a.cod_activ_obra and
							v.cod_partida=a.cod_partida limit 1)
						as deno_partida,
						a.cod_generica,
						(select t.deno_generica
							from v_cfpd05_denominaciones t
							where
							t.cod_presi=a.cod_presi and
							t.cod_entidad=a.cod_entidad and
							t.cod_tipo_inst=a.cod_tipo_inst and
							t.cod_inst=a.cod_inst and
							t.cod_dep=a.cod_dep and
							t.ano=a.ano and
							t.cod_sector=a.cod_sector and
							t.cod_programa=a.cod_programa and
							t.cod_sub_prog=a.cod_sub_prog and
							t.cod_proyecto=a.cod_proyecto and
							t.cod_activ_obra=a.cod_activ_obra and
							t.cod_partida=a.cod_partida and
							t.cod_partida=a.cod_partida and
							t.cod_generica=a.cod_generica limit 1)
						as deno_generica,
						a.cod_especifica,
						(select s.deno_especifica
							from v_cfpd05_denominaciones s
							where
							s.cod_presi=a.cod_presi and
							s.cod_entidad=a.cod_entidad and
							s.cod_tipo_inst=a.cod_tipo_inst and
							s.cod_inst=a.cod_inst and
							s.cod_dep=a.cod_dep and
							s.ano=a.ano and
							s.cod_sector=a.cod_sector and
							s.cod_programa=a.cod_programa and
							s.cod_sub_prog=a.cod_sub_prog and
							s.cod_proyecto=a.cod_proyecto and
							s.cod_activ_obra=a.cod_activ_obra and
							s.cod_partida=a.cod_partida and
							s.cod_partida=a.cod_partida and
							s.cod_generica=a.cod_generica and
							s.cod_especifica=a.cod_especifica limit 1)
						as deno_especifica,
						a.cod_sub_espec,
						(select r.deno_sub_espec
							from v_cfpd05_denominaciones r
							where
							r.cod_presi=a.cod_presi and
							r.cod_entidad=a.cod_entidad and
							r.cod_tipo_inst=a.cod_tipo_inst and
							r.cod_inst=a.cod_inst and
							r.cod_dep=a.cod_dep and
							r.ano=a.ano and
							r.cod_sector=a.cod_sector and
							r.cod_programa=a.cod_programa and
							r.cod_sub_prog=a.cod_sub_prog and
							r.cod_proyecto=a.cod_proyecto and
							r.cod_activ_obra=a.cod_activ_obra and
							r.cod_partida=a.cod_partida and
							r.cod_partida=a.cod_partida and
							r.cod_generica=a.cod_generica and
							r.cod_especifica=a.cod_especifica and
							r.cod_sub_espec=a.cod_sub_espec limit 1)
						as deno_sub_espe,
						a.cod_auxiliar,
						(select o.deno_auxiliar
							from v_cfpd05_denominaciones o
							where
							o.cod_presi=a.cod_presi and
							o.cod_entidad=a.cod_entidad and
							o.cod_tipo_inst=a.cod_tipo_inst and
							o.cod_inst=a.cod_inst and
							o.cod_dep=a.cod_dep and
							o.ano=a.ano and
							o.cod_sector=a.cod_sector and
							o.cod_programa=a.cod_programa and
							o.cod_sub_prog=a.cod_sub_prog and
							o.cod_proyecto=a.cod_proyecto and
							o.cod_activ_obra=a.cod_activ_obra and
							o.cod_partida=a.cod_partida and
							o.cod_partida=a.cod_partida and
							o.cod_generica=a.cod_generica and
							o.cod_especifica=a.cod_especifica and
							o.cod_sub_espec=a.cod_sub_espec and
							o.cod_auxiliar=a.cod_auxiliar limit 1)
						as deno_auxiliar,
						  a.cod_nivel_i,
						  a.cod_nivel_ii,
						  a.cod_ficha,
						  b.denominacion as denominacion_nomina,
						  b.denominacion_devengado,
						  b.clasificacion_personal,
						  b.frecuencia_cobro,
						  b.dias_laborables,
						  b.horas_laborables,
						  b.descuentos_ley,
						  b.mensajes_colectivos,
						  b.status_nomina,
						  b.cantidad_pagos,
						  b.correspondiente,
						  b.frecuencia_pago,
						  b.numero_recibo,
						  b.control_autorizacion,
						  b.autorizacion_diskettes,
						  b.sueldo_sugerido,
						  b.ultimo_cargo,
						  b.ultima_ficha,
						  b.ano_desde,
						  b.ano_hasta,
						  b.codigo_transaccion,
						  b.dias_cobro,
						  c.denominacion_clase as denominacion_cargo,
						  (SELECT denominacion FROM cugd02_dependencias b WHERE b.cod_tipo_institucion = a.cod_tipo_inst and b.cod_institucion  =  a.cod_inst and b.cod_dependencia   =  a.cod_dep) as denominacion_dep,
						  (SELECT denominacion FROM cnmd04_ocupacion bb WHERE bb.cod_nivel_i = a.cod_nivel_i and bb.cod_nivel_ii  =  a.cod_nivel_ii ) as denominacion_clasificacion,
						  (SELECT d.cedula_identidad FROM cnmd06_fichas d where
						   d.cod_presi            =  a.cod_presi                and
						   d.cod_entidad          =  a.cod_entidad              and
						   d.cod_tipo_inst        =  a.cod_tipo_inst            and
						   d.cod_inst             =  a.cod_inst                 and
						   d.cod_dep              =  a.cod_dep                  and
						   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
						   d.cod_cargo            =  a.cod_cargo                and
						   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  as  cedula_identidad_ficha,


						   (SELECT e.primer_apellido  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
																	   d.cod_presi            =  a.cod_presi                and
																	   d.cod_entidad          =  a.cod_entidad              and
																	   d.cod_tipo_inst        =  a.cod_tipo_inst            and
																	   d.cod_inst             =  a.cod_inst                 and
																	   d.cod_dep              =  a.cod_dep                  and
																	   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
																	   d.cod_cargo            =  a.cod_cargo                and
																	   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.primer_apellido)
	                      as  primer_apellido,

	                      (SELECT e.segundo_apellido  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
																	   d.cod_presi            =  a.cod_presi                and
																	   d.cod_entidad          =  a.cod_entidad              and
																	   d.cod_tipo_inst        =  a.cod_tipo_inst            and
																	   d.cod_inst             =  a.cod_inst                 and
																	   d.cod_dep              =  a.cod_dep                  and
																	   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
																	   d.cod_cargo            =  a.cod_cargo                and
																	   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.segundo_apellido)
	                      as  segundo_apellido,

                          (SELECT e.primer_nombre  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
																	   d.cod_presi            =  a.cod_presi                and
																	   d.cod_entidad          =  a.cod_entidad              and
																	   d.cod_tipo_inst        =  a.cod_tipo_inst            and
																	   d.cod_inst             =  a.cod_inst                 and
																	   d.cod_dep              =  a.cod_dep                  and
																	   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
																	   d.cod_cargo            =  a.cod_cargo                and
																	   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.primer_nombre)
	                      as  primer_nombre,


	                      (SELECT e.segundo_nombre  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
																	   d.cod_presi            =  a.cod_presi                and
																	   d.cod_entidad          =  a.cod_entidad              and
																	   d.cod_tipo_inst        =  a.cod_tipo_inst            and
																	   d.cod_inst             =  a.cod_inst                 and
																	   d.cod_dep              =  a.cod_dep                  and
																	   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
																	   d.cod_cargo            =  a.cod_cargo                and
																	   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.segundo_nombre)
	                      as  segundo_nombre


                 FROM

                          " . $tabla . " a, cnmd01 b, " . $vista . " c


                 WHERE

                           a.cod_presi            =  " . $cod_presi . "             and
						   a.cod_entidad          =  " . $cod_entidad . "           and
						   a.cod_tipo_inst        =  " . $cod_tipo_inst . "         and
						   a.cod_inst             =  " . $cod_inst . "              and
						   " . $dep_A . "
						   " . $tipo_nomina . "
						   " . $cond_vacante . "
						   b.cod_presi            =  a.cod_presi                and
						   b.cod_entidad          =  a.cod_entidad              and
						   b.cod_tipo_inst        =  a.cod_tipo_inst            and
						   b.cod_inst             =  a.cod_inst                 and
						   b.cod_dep              =  a.cod_dep                  and
						   b.cod_tipo_nomina      =  a.cod_tipo_nomina          and
						   c.cod_presi            =  a.cod_presi                and
						   c.cod_entidad          =  a.cod_entidad              and
						   c.cod_tipo_inst        =  a.cod_tipo_inst            and
						   c.cod_inst             =  a.cod_inst                 and
						   c.cod_dep              =  a.cod_dep                  and
						   c.cod_tipo_nomina      =  a.cod_tipo_nomina          and
						   c.cod_cargo            =  a.cod_cargo                and
                           c.cod_puesto           =  a.cod_puesto


                 " . $ordenamiento . "


                            ASC

             ; ");

            $this->set('tipo', $tipo);
            $this->set('datos', $rs);
            $this->set('presentar_como', $frecuencia);
        }//fin$tipo


        $this->set('opcion', $opcion);
        $this->set('var1', $var1);
    }

//fin funtion

    function cargos_ordenados_por_ubicacion_administrativa($var1 = null, $var2 = null) {




        $tabla = "";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');

        set_time_limit(0);

        $tabla = "cnmd05";
        $vista = "v_cnmd05";



        if ($var2 == null) {
            $this->layout = "ajax";
            $opcion = 1;
            $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst;
            $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
            $dato = null;
            foreach ($year as $year) {
                $dato_year = $year['cfpd01_formulacion']['ano_formular'];
            }
            if ($var1 == 1) {
                $year_vista = $dato_year;
            } else {
                $year_vista = $this->ano_ejecucion();
            }
            $this->set('year', $year_vista);

            //$Lista = $this->v_cnmd05->generateList($this->SQLCA(), 'cod_tipo_nomina ASC', null, '{n}.v_cnmd05.cod_tipo_nomina', '{n}.v_cnmd05.tipo_nomina');
            //$this->concatena($Lista, 'cod_tipo_nomina');

            $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
            $this->concatenaN($lista, 'nomina');
        } else {
            $this->layout = "pdf";
            $opcion = 2;

            if (!empty($this->data['reporte3']['consolidacion'])) {
                $consolidacion = $this->data['reporte3']['consolidacion'];
            } else {
                $consolidacion = 2;
            }
            if (!empty($this->data['reporte3']['year'])) {
                $year = $this->data['reporte3']['year'];
            } else {
                $year = $this->ano_ejecucion();
            }
            if (!empty($this->data['reporte3']['tipo'])) {
                $tipo = $this->data['reporte3']['tipo'];
            } else {
                $tipo = 1;
            }
            if (!empty($this->data['reporte3']['frecuencia'])) {
                $frecuencia = $this->data['reporte3']['frecuencia'];
            } else {
                $frecuencia = 1;
            }

            $sql_a = "";

            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_dirsuperior'])) {
                $sql_a .= "a.cod_dir_superior  = " . $this->data['cscp02_solicitud_cotizacion']['cod_dirsuperior'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_coordinacion'])) {
                $sql_a .= "a.cod_coordinacion  = " . $this->data['cscp02_solicitud_cotizacion']['cod_coordinacion'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_secretaria'])) {
                $sql_a .= "a.cod_secretaria  = " . $this->data['cscp02_solicitud_cotizacion']['cod_secretaria'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_direccion'])) {
                $sql_a .= "a.cod_direccion  = " . $this->data['cscp02_solicitud_cotizacion']['cod_direccion'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_division'])) {
                $sql_a .= "a.cod_division  = " . $this->data['cscp02_solicitud_cotizacion']['cod_division'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_departamento'])) {
                $sql_a .= "a.cod_departamento  = " . $this->data['cscp02_solicitud_cotizacion']['cod_departamento'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_oficina'])) {
                $sql_a .= "a.cod_oficina  = " . $this->data['cscp02_solicitud_cotizacion']['cod_oficina'] . "  and ";
            }





            $tipo_nomina = "a.cod_tipo_nomina  = " . $tipo . "  and ";


            if ($consolidacion == 1) {
                $dep_A = "";
                $dep_B = "";
            } else if ($consolidacion == 2) {
                $dep_A = " a.cod_dep   =  " . $cod_dep . "  and";
                $dep_B = " a.cod_dep, ";
            }//fin function
            // TODOS - VACANTES - OCUPADOS
            $vacantes = $this->data['reporte3']['vacante'];
            if ($vacantes == 1) {
                $cond_vacante = " a.condicion_actividad=1 AND"; // vacante
            } else if ($vacantes == 2) {
                $cond_vacante = " a.condicion_actividad=2 AND "; // Ocupado
            } else if ($vacantes == 3) {
                $cond_vacante = " "; // todos
            }

            $rs = $this->v_cfpd05_denominaciones->execute("

		         SELECT
			              a.cod_presi,
						  a.cod_entidad,
						  a.cod_tipo_inst,
						  a.cod_inst,
						  a.cod_dep,
						  a.cod_tipo_nomina,
						  a.cod_cargo,
						  a.cod_puesto,
						  a.sueldo_basico,
						  a.compensaciones,
						  a.primas,
						  a.bonos,
						  a.cod_dir_superior,
						  a.cod_coordinacion,
						  a.cod_secretaria,
						  a.cod_direccion,
						  a.cod_division,
						  a.cod_departamento,
						  a.cod_oficina,
						 (SELECT xa.denominacion FROM cugd02_direccionsuperior xa where xa.cod_tipo_institucion=a.cod_tipo_inst  and xa.cod_institucion=a.cod_inst and xa.cod_dependencia=a.cod_dep and xa.cod_dir_superior=a.cod_dir_superior                                                                                                                                                                                                                                        GROUP BY xa.denominacion) as  deno_cod_dir_superior,
						 (SELECT xb.denominacion FROM cugd02_coordinacion xb      where xb.cod_tipo_institucion=a.cod_tipo_inst  and xb.cod_institucion=a.cod_inst and xb.cod_dependencia=a.cod_dep and xb.cod_dir_superior=a.cod_dir_superior and xb.cod_coordinacion=a.cod_coordinacion                                                                                                                                                                                             GROUP BY xb.denominacion) as  deno_cod_coordinacion,
						 (SELECT xc.denominacion FROM cugd02_secretaria xc        where xc.cod_tipo_institucion=a.cod_tipo_inst  and xc.cod_institucion=a.cod_inst and xc.cod_dependencia=a.cod_dep and xc.cod_dir_superior=a.cod_dir_superior and xc.cod_coordinacion=a.cod_coordinacion and xc.cod_secretaria=a.cod_secretaria                                                                                                                                                      GROUP BY xc.denominacion) as  deno_cod_secretaria,
						 (SELECT xd.denominacion FROM cugd02_direccion xd         where xd.cod_tipo_institucion=a.cod_tipo_inst  and xd.cod_institucion=a.cod_inst and xd.cod_dependencia=a.cod_dep and xd.cod_dir_superior=a.cod_dir_superior and xd.cod_coordinacion=a.cod_coordinacion and xd.cod_secretaria=a.cod_secretaria and xd.cod_direccion=a.cod_direccion                                                                                                                 GROUP BY xd.denominacion) as  deno_cod_direccion,
						 (SELECT xe.denominacion FROM cugd02_division xe          where xe.cod_tipo_institucion=a.cod_tipo_inst  and xe.cod_institucion=a.cod_inst and xe.cod_dependencia=a.cod_dep and xe.cod_dir_superior=a.cod_dir_superior and xe.cod_coordinacion=a.cod_coordinacion and xe.cod_secretaria=a.cod_secretaria and xe.cod_direccion=a.cod_direccion  and xe.cod_division=a.cod_division                                                                             GROUP BY xe.denominacion) as  deno_cod_division,
						 (SELECT xf.denominacion FROM cugd02_departamento xf      where xf.cod_tipo_institucion=a.cod_tipo_inst  and xf.cod_institucion=a.cod_inst and xf.cod_dependencia=a.cod_dep and xf.cod_dir_superior=a.cod_dir_superior and xf.cod_coordinacion=a.cod_coordinacion and xf.cod_secretaria=a.cod_secretaria and xf.cod_direccion=a.cod_direccion  and xf.cod_division=a.cod_division and xf.cod_departamento=a.cod_departamento                                  GROUP BY xf.denominacion) as  deno_cod_departamento,
						 (SELECT xg.denominacion FROM cugd02_oficina xg           where xg.cod_tipo_institucion=a.cod_tipo_inst  and xg.cod_institucion=a.cod_inst and xg.cod_dependencia=a.cod_dep and xg.cod_dir_superior=a.cod_dir_superior and xg.cod_coordinacion=a.cod_coordinacion and xg.cod_secretaria=a.cod_secretaria and xg.cod_direccion=a.cod_direccion  and xg.cod_division=a.cod_division and xg.cod_departamento=a.cod_departamento and xg.cod_oficina=a.cod_oficina GROUP BY xg.denominacion) as  deno_cod_oficina,
						  a.cod_estado,
						  a.cod_municipio,
						  a.cod_parroquia,
						  a.cod_centro,
						  a.condicion_actividad,
						  a.ano,
						  a.cod_sector,
						(select
							z.deno_sector
							from v_cfpd05_denominaciones z
							where
							z.cod_presi=a.cod_presi and
							z.cod_entidad=a.cod_entidad and
							z.cod_tipo_inst=a.cod_tipo_inst and
							z.cod_inst=a.cod_inst and
							z.cod_dep=a.cod_dep and
							z.ano=a.ano and
							z.cod_sector=a.cod_sector limit 1)
						as deno_sector,
						a.cod_programa,
						(select
							y.deno_programa
							from v_cfpd05_denominaciones y
							where
							y.cod_presi=a.cod_presi and
							y.cod_entidad=a.cod_entidad and
							y.cod_tipo_inst=a.cod_tipo_inst and
							y.cod_inst=a.cod_inst and
							y.cod_dep=a.cod_dep and
							y.ano=a.ano and
							y.cod_sector=a.cod_sector and
							y.cod_programa=a.cod_programa limit 1)
						as deno_programa,
						a.cod_sub_prog,
						(select
							x.deno_sub_prog
							from v_cfpd05_denominaciones x
							where
							x.cod_presi=a.cod_presi and
							x.cod_entidad=a.cod_entidad and
							x.cod_tipo_inst=a.cod_tipo_inst and
							x.cod_inst=a.cod_inst and
							x.cod_dep=a.cod_dep and
							x.ano=a.ano and
							x.cod_sector=a.cod_sector and
							x.cod_programa=a.cod_programa and
							x.cod_sub_prog=a.cod_sub_prog limit 1)
						as deno_sub_prog,
						a.cod_proyecto,
						(select
							w.deno_proyecto
							from v_cfpd05_denominaciones w
							where
							w.cod_presi=a.cod_presi and
							w.cod_entidad=a.cod_entidad and
							w.cod_tipo_inst=a.cod_tipo_inst and
							w.cod_inst=a.cod_inst and
							w.cod_dep=a.cod_dep and
							w.ano=a.ano and
							w.cod_sector=a.cod_sector and
							w.cod_programa=a.cod_programa and
							w.cod_sub_prog=a.cod_sub_prog and
							w.cod_proyecto=a.cod_proyecto limit 1)
						as deno_proyecto,
						a.cod_activ_obra,
						(select
							u.deno_activ_obra
							from v_cfpd05_denominaciones u
							where
							u.cod_presi=a.cod_presi and
							u.cod_entidad=a.cod_entidad and
							u.cod_tipo_inst=a.cod_tipo_inst and
							u.cod_inst=a.cod_inst and
							u.cod_dep=a.cod_dep and
							u.ano=a.ano and
							u.cod_sector=a.cod_sector and
							u.cod_programa=a.cod_programa and
							u.cod_sub_prog=a.cod_sub_prog and
							u.cod_proyecto=a.cod_proyecto and u.cod_activ_obra=a.cod_activ_obra limit 1)
						as deno_activ_obra,
						a.cod_partida,
						(select v.deno_partida
							from v_cfpd05_denominaciones v
							where
							v.cod_presi=a.cod_presi and
							v.cod_entidad=a.cod_entidad and
							v.cod_tipo_inst=a.cod_tipo_inst and
							v.cod_inst=a.cod_inst and
							v.cod_dep=a.cod_dep and
							v.ano=a.ano and
							v.cod_sector=a.cod_sector and
							v.cod_programa=a.cod_programa and
							v.cod_sub_prog=a.cod_sub_prog and
							v.cod_proyecto=a.cod_proyecto and
							v.cod_activ_obra=a.cod_activ_obra and
							v.cod_partida=a.cod_partida limit 1)
						as deno_partida,
						a.cod_generica,
						(select t.deno_generica
							from v_cfpd05_denominaciones t
							where
							t.cod_presi=a.cod_presi and
							t.cod_entidad=a.cod_entidad and
							t.cod_tipo_inst=a.cod_tipo_inst and
							t.cod_inst=a.cod_inst and
							t.cod_dep=a.cod_dep and
							t.ano=a.ano and
							t.cod_sector=a.cod_sector and
							t.cod_programa=a.cod_programa and
							t.cod_sub_prog=a.cod_sub_prog and
							t.cod_proyecto=a.cod_proyecto and
							t.cod_activ_obra=a.cod_activ_obra and
							t.cod_partida=a.cod_partida and
							t.cod_partida=a.cod_partida and
							t.cod_generica=a.cod_generica limit 1)
						as deno_generica,
						a.cod_especifica,
						(select s.deno_especifica
							from v_cfpd05_denominaciones s
							where
							s.cod_presi=a.cod_presi and
							s.cod_entidad=a.cod_entidad and
							s.cod_tipo_inst=a.cod_tipo_inst and
							s.cod_inst=a.cod_inst and
							s.cod_dep=a.cod_dep and
							s.ano=a.ano and
							s.cod_sector=a.cod_sector and
							s.cod_programa=a.cod_programa and
							s.cod_sub_prog=a.cod_sub_prog and
							s.cod_proyecto=a.cod_proyecto and
							s.cod_activ_obra=a.cod_activ_obra and
							s.cod_partida=a.cod_partida and
							s.cod_partida=a.cod_partida and
							s.cod_generica=a.cod_generica and
							s.cod_especifica=a.cod_especifica limit 1)
						as deno_especifica,
						a.cod_sub_espec,
						(select r.deno_sub_espec
							from v_cfpd05_denominaciones r
							where
							r.cod_presi=a.cod_presi and
							r.cod_entidad=a.cod_entidad and
							r.cod_tipo_inst=a.cod_tipo_inst and
							r.cod_inst=a.cod_inst and
							r.cod_dep=a.cod_dep and
							r.ano=a.ano and
							r.cod_sector=a.cod_sector and
							r.cod_programa=a.cod_programa and
							r.cod_sub_prog=a.cod_sub_prog and
							r.cod_proyecto=a.cod_proyecto and
							r.cod_activ_obra=a.cod_activ_obra and
							r.cod_partida=a.cod_partida and
							r.cod_partida=a.cod_partida and
							r.cod_generica=a.cod_generica and
							r.cod_especifica=a.cod_especifica and
							r.cod_sub_espec=a.cod_sub_espec limit 1)
						as deno_sub_espe,
						a.cod_auxiliar,
						(select o.deno_auxiliar
							from v_cfpd05_denominaciones o
							where
							o.cod_presi=a.cod_presi and
							o.cod_entidad=a.cod_entidad and
							o.cod_tipo_inst=a.cod_tipo_inst and
							o.cod_inst=a.cod_inst and
							o.cod_dep=a.cod_dep and
							o.ano=a.ano and
							o.cod_sector=a.cod_sector and
							o.cod_programa=a.cod_programa and
							o.cod_sub_prog=a.cod_sub_prog and
							o.cod_proyecto=a.cod_proyecto and
							o.cod_activ_obra=a.cod_activ_obra and
							o.cod_partida=a.cod_partida and
							o.cod_partida=a.cod_partida and
							o.cod_generica=a.cod_generica and
							o.cod_especifica=a.cod_especifica and
							o.cod_sub_espec=a.cod_sub_espec and
							o.cod_auxiliar=a.cod_auxiliar limit 1)
						as deno_auxiliar,
						  a.cod_nivel_i,
						  a.cod_nivel_ii,
						  a.cod_ficha,
						  b.denominacion as denominacion_nomina,
						  b.denominacion_devengado,
						  b.clasificacion_personal,
						  b.frecuencia_cobro,
						  b.dias_laborables,
						  b.horas_laborables,
						  b.descuentos_ley,
						  b.mensajes_colectivos,
						  b.status_nomina,
						  b.cantidad_pagos,
						  b.correspondiente,
						  b.frecuencia_pago,
						  b.numero_recibo,
						  b.control_autorizacion,
						  b.autorizacion_diskettes,
						  b.sueldo_sugerido,
						  b.ultimo_cargo,
						  b.ultima_ficha,
						  b.ano_desde,
						  b.ano_hasta,
						  b.codigo_transaccion,
						  b.dias_cobro,
						  c.denominacion_clase as denominacion_cargo,
						  (SELECT denominacion FROM cugd02_dependencias b WHERE b.cod_tipo_institucion = a.cod_tipo_inst and b.cod_institucion  =  a.cod_inst and b.cod_dependencia   =  a.cod_dep) as denominacion_dep,
						  (SELECT denominacion FROM cnmd04_ocupacion bb WHERE bb.cod_nivel_i = a.cod_nivel_i and bb.cod_nivel_ii  =  a.cod_nivel_ii ) as denominacion_clasificacion,
						  (SELECT d.cedula_identidad FROM cnmd06_fichas d where
						   d.cod_presi            =  a.cod_presi                and
						   d.cod_entidad          =  a.cod_entidad              and
						   d.cod_tipo_inst        =  a.cod_tipo_inst            and
						   d.cod_inst             =  a.cod_inst                 and
						   d.cod_dep              =  a.cod_dep                  and
						   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
						   d.cod_cargo            =  a.cod_cargo                and
						   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  as  cedula_identidad_ficha,


						   (SELECT e.primer_apellido  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
																	   d.cod_presi            =  a.cod_presi                and
																	   d.cod_entidad          =  a.cod_entidad              and
																	   d.cod_tipo_inst        =  a.cod_tipo_inst            and
																	   d.cod_inst             =  a.cod_inst                 and
																	   d.cod_dep              =  a.cod_dep                  and
																	   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
																	   d.cod_cargo            =  a.cod_cargo                and
																	   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.primer_apellido)
	                      as  primer_apellido,

	                      (SELECT e.segundo_apellido  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
																	   d.cod_presi            =  a.cod_presi                and
																	   d.cod_entidad          =  a.cod_entidad              and
																	   d.cod_tipo_inst        =  a.cod_tipo_inst            and
																	   d.cod_inst             =  a.cod_inst                 and
																	   d.cod_dep              =  a.cod_dep                  and
																	   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
																	   d.cod_cargo            =  a.cod_cargo                and
																	   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.segundo_apellido)
	                      as  segundo_apellido,

                          (SELECT e.primer_nombre  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
																	   d.cod_presi            =  a.cod_presi                and
																	   d.cod_entidad          =  a.cod_entidad              and
																	   d.cod_tipo_inst        =  a.cod_tipo_inst            and
																	   d.cod_inst             =  a.cod_inst                 and
																	   d.cod_dep              =  a.cod_dep                  and
																	   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
																	   d.cod_cargo            =  a.cod_cargo                and
																	   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.primer_nombre)
	                      as  primer_nombre,


	                      (SELECT e.segundo_nombre  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
																	   d.cod_presi            =  a.cod_presi                and
																	   d.cod_entidad          =  a.cod_entidad              and
																	   d.cod_tipo_inst        =  a.cod_tipo_inst            and
																	   d.cod_inst             =  a.cod_inst                 and
																	   d.cod_dep              =  a.cod_dep                  and
																	   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
																	   d.cod_cargo            =  a.cod_cargo                and
																	   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.segundo_nombre)
	                      as  segundo_nombre


                 FROM

                          " . $tabla . " a, cnmd01 b, " . $vista . " c


                 WHERE

                           a.cod_presi            =  " . $cod_presi . "             and
						   a.cod_entidad          =  " . $cod_entidad . "           and
						   a.cod_tipo_inst        =  " . $cod_tipo_inst . "         and
						   a.cod_inst             =  " . $cod_inst . "              and
						   " . $dep_A . "
						   " . $tipo_nomina . "
						   " . $sql_a . "
						   " . $cond_vacante . "
						   b.cod_presi            =  a.cod_presi                and
						   b.cod_entidad          =  a.cod_entidad              and
						   b.cod_tipo_inst        =  a.cod_tipo_inst            and
						   b.cod_inst             =  a.cod_inst                 and
						   b.cod_dep              =  a.cod_dep                  and
						   b.cod_tipo_nomina      =  a.cod_tipo_nomina          and
						   c.cod_presi            =  a.cod_presi                and
						   c.cod_entidad          =  a.cod_entidad              and
						   c.cod_tipo_inst        =  a.cod_tipo_inst            and
						   c.cod_inst             =  a.cod_inst                 and
						   c.cod_dep              =  a.cod_dep                  and
						   c.cod_tipo_nomina      =  a.cod_tipo_nomina          and
						   c.cod_cargo            =  a.cod_cargo                and
                           c.cod_puesto           =  a.cod_puesto


                 ORDER BY
                            a.cod_presi,
                            a.cod_entidad,
                            a.cod_tipo_inst,
                            a.cod_inst,
                            a.cod_dep,
                            a.cod_tipo_nomina,
                            a.cod_dir_superior,
						    a.cod_coordinacion,
						    a.cod_secretaria,
						    a.cod_direccion,
						    a.cod_division,
						    a.cod_departamento,
						    a.cod_oficina,
                            a.cod_cargo


                            ASC

             ; ");

            $this->set('tipo', $tipo);
            $this->set('datos', $rs);
            $this->set('presentar_como', $frecuencia);
        }//fin$tipo


        $this->set('opcion', $opcion);
        $this->set('var1', $var1);
    }

//fin funtion

    function cargos_ordenados_por_ubicacion_geografica($var1 = null, $var2 = null) {




        $tabla = "";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');
        $this->Session->write('rep', $cod_presi);

        set_time_limit(0);

        $tabla = "cnmd05";
        $vista = "v_cnmd05";


        if ($var2 == null) {
            $this->layout = "ajax";
            $opcion = 1;
            $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst;
            $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
            $dato = null;
            foreach ($year as $year) {
                $dato_year = $year['cfpd01_formulacion']['ano_formular'];
            }
            if ($var1 == 1) {
                $year_vista = $dato_year;
            } else {
                $year_vista = $this->ano_ejecucion();
            }
            $this->set('year', $year_vista);



            $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
            $this->concatenaN($lista, 'nomina');
        } else {
            $this->layout = "pdf";
            $opcion = 2;

            if (!empty($this->data['reporte3']['consolidacion'])) {
                $consolidacion = $this->data['reporte3']['consolidacion'];
            } else {
                $consolidacion = 2;
            }
            if (!empty($this->data['reporte3']['year'])) {
                $year = $this->data['reporte3']['year'];
            } else {
                $year = $this->ano_ejecucion();
            }
            if (!empty($this->data['reporte3']['tipo'])) {
                $tipo = $this->data['reporte3']['tipo'];
            } else {
                $tipo = 1;
            }
            if (!empty($this->data['reporte3']['frecuencia'])) {
                $frecuencia = $this->data['reporte3']['frecuencia'];
            } else {
                $frecuencia = 1;
            }

            $sql_a = "";
            $sql_b = "";



            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_dirsuperior'])) {
                $sql_a .= "a.cod_dir_superior  = " . $this->data['cscp02_solicitud_cotizacion']['cod_dirsuperior'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_coordinacion'])) {
                $sql_a .= "a.cod_coordinacion  = " . $this->data['cscp02_solicitud_cotizacion']['cod_coordinacion'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_secretaria'])) {
                $sql_a .= "a.cod_secretaria  = " . $this->data['cscp02_solicitud_cotizacion']['cod_secretaria'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_direccion'])) {
                $sql_a .= "a.cod_direccion  = " . $this->data['cscp02_solicitud_cotizacion']['cod_direccion'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_division'])) {
                $sql_a .= "a.cod_division  = " . $this->data['cscp02_solicitud_cotizacion']['cod_division'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_departamento'])) {
                $sql_a .= "a.cod_departamento  = " . $this->data['cscp02_solicitud_cotizacion']['cod_departamento'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_oficina'])) {
                $sql_a .= "a.cod_oficina  = " . $this->data['cscp02_solicitud_cotizacion']['cod_oficina'] . "  and ";
            }




            if (!empty($this->data['cobp01_contratoobras']['cod_estado'])) {
                $sql_b .= "a.cod_estado  = " . $this->data['cobp01_contratoobras']['cod_estado'] . "  and ";
            }
            if (!empty($this->data['cobp01_contratoobras']['cod_municipio'])) {
                $sql_b .= "a.cod_municipio  = " . $this->data['cobp01_contratoobras']['cod_municipio'] . "  and ";
            }
            if (!empty($this->data['cobp01_contratoobras']['cod_parroquia'])) {
                $sql_b .= "a.cod_parroquia  = " . $this->data['cobp01_contratoobras']['cod_parroquia'] . "  and ";
            }
            if (!empty($this->data['cobp01_contratoobras']['cod_centro'])) {
                $sql_b .= "a.cod_centro  = " . $this->data['cobp01_contratoobras']['cod_centro'] . "  and ";
            }





            $tipo_nomina = "a.cod_tipo_nomina  = " . $tipo . "  and ";


            if ($consolidacion == 1) {
                $dep_A = "";
                $dep_B = "";
            } else if ($consolidacion == 2) {
                $dep_A = " a.cod_dep   =  " . $cod_dep . "  and";
                $dep_B = " a.cod_dep, ";
            }//fin function
            // TODOS - VACANTES - OCUPADOS
            $vacantes = $this->data['reporte3']['vacante'];
            if ($vacantes == 1) {
                $cond_vacante = " a.condicion_actividad=1 AND"; // vacante
            } else if ($vacantes == 2) {
                $cond_vacante = " a.condicion_actividad=2 AND "; // Ocupado
            } else if ($vacantes == 3) {
                $cond_vacante = " "; // todos
            }


            $rs = $this->v_cfpd05_denominaciones->execute("

		         SELECT
			              a.cod_presi,
						  a.cod_entidad,
						  a.cod_tipo_inst,
						  a.cod_inst,
						  a.cod_dep,
						  a.cod_tipo_nomina,
						  a.cod_cargo,
						  a.cod_puesto,
						  a.sueldo_basico,
						  a.compensaciones,
						  a.primas,
						  a.bonos,
						  a.cod_dir_superior,
						  a.cod_coordinacion,
						  a.cod_secretaria,
						  a.cod_direccion,
						  a.cod_division,
						  a.cod_departamento,
						  a.cod_oficina,
						 (SELECT xa.denominacion FROM cugd02_direccionsuperior xa where xa.cod_tipo_institucion=a.cod_tipo_inst  and xa.cod_institucion=a.cod_inst and xa.cod_dependencia=a.cod_dep and xa.cod_dir_superior=a.cod_dir_superior                                                                                                                                                                                                                                        GROUP BY xa.denominacion) as  deno_cod_dir_superior,
						 (SELECT xb.denominacion FROM cugd02_coordinacion xb      where xb.cod_tipo_institucion=a.cod_tipo_inst  and xb.cod_institucion=a.cod_inst and xb.cod_dependencia=a.cod_dep and xb.cod_dir_superior=a.cod_dir_superior and xb.cod_coordinacion=a.cod_coordinacion                                                                                                                                                                                             GROUP BY xb.denominacion) as  deno_cod_coordinacion,
						 (SELECT xc.denominacion FROM cugd02_secretaria xc        where xc.cod_tipo_institucion=a.cod_tipo_inst  and xc.cod_institucion=a.cod_inst and xc.cod_dependencia=a.cod_dep and xc.cod_dir_superior=a.cod_dir_superior and xc.cod_coordinacion=a.cod_coordinacion and xc.cod_secretaria=a.cod_secretaria                                                                                                                                                      GROUP BY xc.denominacion) as  deno_cod_secretaria,
						 (SELECT xd.denominacion FROM cugd02_direccion xd         where xd.cod_tipo_institucion=a.cod_tipo_inst  and xd.cod_institucion=a.cod_inst and xd.cod_dependencia=a.cod_dep and xd.cod_dir_superior=a.cod_dir_superior and xd.cod_coordinacion=a.cod_coordinacion and xd.cod_secretaria=a.cod_secretaria and xd.cod_direccion=a.cod_direccion                                                                                                                 GROUP BY xd.denominacion) as  deno_cod_direccion,
						 (SELECT xe.denominacion FROM cugd02_division xe          where xe.cod_tipo_institucion=a.cod_tipo_inst  and xe.cod_institucion=a.cod_inst and xe.cod_dependencia=a.cod_dep and xe.cod_dir_superior=a.cod_dir_superior and xe.cod_coordinacion=a.cod_coordinacion and xe.cod_secretaria=a.cod_secretaria and xe.cod_direccion=a.cod_direccion  and xe.cod_division=a.cod_division                                                                             GROUP BY xe.denominacion) as  deno_cod_division,
						 (SELECT xf.denominacion FROM cugd02_departamento xf      where xf.cod_tipo_institucion=a.cod_tipo_inst  and xf.cod_institucion=a.cod_inst and xf.cod_dependencia=a.cod_dep and xf.cod_dir_superior=a.cod_dir_superior and xf.cod_coordinacion=a.cod_coordinacion and xf.cod_secretaria=a.cod_secretaria and xf.cod_direccion=a.cod_direccion  and xf.cod_division=a.cod_division and xf.cod_departamento=a.cod_departamento                                  GROUP BY xf.denominacion) as  deno_cod_departamento,
						 (SELECT xg.denominacion FROM cugd02_oficina xg           where xg.cod_tipo_institucion=a.cod_tipo_inst  and xg.cod_institucion=a.cod_inst and xg.cod_dependencia=a.cod_dep and xg.cod_dir_superior=a.cod_dir_superior and xg.cod_coordinacion=a.cod_coordinacion and xg.cod_secretaria=a.cod_secretaria and xg.cod_direccion=a.cod_direccion  and xg.cod_division=a.cod_division and xg.cod_departamento=a.cod_departamento and xg.cod_oficina=a.cod_oficina GROUP BY xg.denominacion) as  deno_cod_oficina,
                          a.cod_estado,
						  a.cod_municipio,
						  a.cod_parroquia,
						  a.cod_centro,
						 (SELECT xya.denominacion FROM cugd01_estados          xya where xya.cod_republica=a.cod_presi and xya.cod_estado=a.cod_estado                                                                                                                  GROUP BY xya.denominacion) as  deno_cod_estado,
						 (SELECT xyb.denominacion FROM cugd01_municipios       xyb where xyb.cod_republica=a.cod_presi and xyb.cod_estado=a.cod_estado  and xyb.cod_municipio=a.cod_municipio                                                                           GROUP BY xyb.denominacion) as  deno_cod_municipio,
						 (SELECT xyc.denominacion FROM cugd01_parroquias       xyc where xyc.cod_republica=a.cod_presi and xyc.cod_estado=a.cod_estado  and xyc.cod_municipio=a.cod_municipio and xyc.cod_parroquia = a.cod_parroquia                                   GROUP BY xyc.denominacion) as  deno_cod_parroquia,
						 (SELECT xyd.denominacion FROM cugd01_centros_poblados xyd where xyd.cod_republica=a.cod_presi and xyd.cod_estado=a.cod_estado  and xyd.cod_municipio=a.cod_municipio and xyd.cod_parroquia = a.cod_parroquia and xyd.cod_centro = a.cod_centro GROUP BY xyd.denominacion) as  deno_cod_centro,
						  a.condicion_actividad,
						  a.ano,
						  a.cod_sector,
						(select
							z.deno_sector
							from v_cfpd05_denominaciones z
							where
							z.cod_presi=a.cod_presi and
							z.cod_entidad=a.cod_entidad and
							z.cod_tipo_inst=a.cod_tipo_inst and
							z.cod_inst=a.cod_inst and
							z.cod_dep=a.cod_dep and
							z.ano=a.ano and
							z.cod_sector=a.cod_sector limit 1)
						as deno_sector,
						a.cod_programa,
						(select
							y.deno_programa
							from v_cfpd05_denominaciones y
							where
							y.cod_presi=a.cod_presi and
							y.cod_entidad=a.cod_entidad and
							y.cod_tipo_inst=a.cod_tipo_inst and
							y.cod_inst=a.cod_inst and
							y.cod_dep=a.cod_dep and
							y.ano=a.ano and
							y.cod_sector=a.cod_sector and
							y.cod_programa=a.cod_programa limit 1)
						as deno_programa,
						a.cod_sub_prog,
						(select
							x.deno_sub_prog
							from v_cfpd05_denominaciones x
							where
							x.cod_presi=a.cod_presi and
							x.cod_entidad=a.cod_entidad and
							x.cod_tipo_inst=a.cod_tipo_inst and
							x.cod_inst=a.cod_inst and
							x.cod_dep=a.cod_dep and
							x.ano=a.ano and
							x.cod_sector=a.cod_sector and
							x.cod_programa=a.cod_programa and
							x.cod_sub_prog=a.cod_sub_prog limit 1)
						as deno_sub_prog,
						a.cod_proyecto,
						(select
							w.deno_proyecto
							from v_cfpd05_denominaciones w
							where
							w.cod_presi=a.cod_presi and
							w.cod_entidad=a.cod_entidad and
							w.cod_tipo_inst=a.cod_tipo_inst and
							w.cod_inst=a.cod_inst and
							w.cod_dep=a.cod_dep and
							w.ano=a.ano and
							w.cod_sector=a.cod_sector and
							w.cod_programa=a.cod_programa and
							w.cod_sub_prog=a.cod_sub_prog and
							w.cod_proyecto=a.cod_proyecto limit 1)
						as deno_proyecto,
						a.cod_activ_obra,
						(select
							u.deno_activ_obra
							from v_cfpd05_denominaciones u
							where
							u.cod_presi=a.cod_presi and
							u.cod_entidad=a.cod_entidad and
							u.cod_tipo_inst=a.cod_tipo_inst and
							u.cod_inst=a.cod_inst and
							u.cod_dep=a.cod_dep and
							u.ano=a.ano and
							u.cod_sector=a.cod_sector and
							u.cod_programa=a.cod_programa and
							u.cod_sub_prog=a.cod_sub_prog and
							u.cod_proyecto=a.cod_proyecto and u.cod_activ_obra=a.cod_activ_obra limit 1)
						as deno_activ_obra,
						a.cod_partida,
						(select v.deno_partida
							from v_cfpd05_denominaciones v
							where
							v.cod_presi=a.cod_presi and
							v.cod_entidad=a.cod_entidad and
							v.cod_tipo_inst=a.cod_tipo_inst and
							v.cod_inst=a.cod_inst and
							v.cod_dep=a.cod_dep and
							v.ano=a.ano and
							v.cod_sector=a.cod_sector and
							v.cod_programa=a.cod_programa and
							v.cod_sub_prog=a.cod_sub_prog and
							v.cod_proyecto=a.cod_proyecto and
							v.cod_activ_obra=a.cod_activ_obra and
							v.cod_partida=a.cod_partida limit 1)
						as deno_partida,
						a.cod_generica,
						(select t.deno_generica
							from v_cfpd05_denominaciones t
							where
							t.cod_presi=a.cod_presi and
							t.cod_entidad=a.cod_entidad and
							t.cod_tipo_inst=a.cod_tipo_inst and
							t.cod_inst=a.cod_inst and
							t.cod_dep=a.cod_dep and
							t.ano=a.ano and
							t.cod_sector=a.cod_sector and
							t.cod_programa=a.cod_programa and
							t.cod_sub_prog=a.cod_sub_prog and
							t.cod_proyecto=a.cod_proyecto and
							t.cod_activ_obra=a.cod_activ_obra and
							t.cod_partida=a.cod_partida and
							t.cod_partida=a.cod_partida and
							t.cod_generica=a.cod_generica limit 1)
						as deno_generica,
						a.cod_especifica,
						(select s.deno_especifica
							from v_cfpd05_denominaciones s
							where
							s.cod_presi=a.cod_presi and
							s.cod_entidad=a.cod_entidad and
							s.cod_tipo_inst=a.cod_tipo_inst and
							s.cod_inst=a.cod_inst and
							s.cod_dep=a.cod_dep and
							s.ano=a.ano and
							s.cod_sector=a.cod_sector and
							s.cod_programa=a.cod_programa and
							s.cod_sub_prog=a.cod_sub_prog and
							s.cod_proyecto=a.cod_proyecto and
							s.cod_activ_obra=a.cod_activ_obra and
							s.cod_partida=a.cod_partida and
							s.cod_partida=a.cod_partida and
							s.cod_generica=a.cod_generica and
							s.cod_especifica=a.cod_especifica limit 1)
						as deno_especifica,
						a.cod_sub_espec,
						(select r.deno_sub_espec
							from v_cfpd05_denominaciones r
							where
							r.cod_presi=a.cod_presi and
							r.cod_entidad=a.cod_entidad and
							r.cod_tipo_inst=a.cod_tipo_inst and
							r.cod_inst=a.cod_inst and
							r.cod_dep=a.cod_dep and
							r.ano=a.ano and
							r.cod_sector=a.cod_sector and
							r.cod_programa=a.cod_programa and
							r.cod_sub_prog=a.cod_sub_prog and
							r.cod_proyecto=a.cod_proyecto and
							r.cod_activ_obra=a.cod_activ_obra and
							r.cod_partida=a.cod_partida and
							r.cod_partida=a.cod_partida and
							r.cod_generica=a.cod_generica and
							r.cod_especifica=a.cod_especifica and
							r.cod_sub_espec=a.cod_sub_espec limit 1)
						as deno_sub_espe,
						a.cod_auxiliar,
						(select o.deno_auxiliar
							from v_cfpd05_denominaciones o
							where
							o.cod_presi=a.cod_presi and
							o.cod_entidad=a.cod_entidad and
							o.cod_tipo_inst=a.cod_tipo_inst and
							o.cod_inst=a.cod_inst and
							o.cod_dep=a.cod_dep and
							o.ano=a.ano and
							o.cod_sector=a.cod_sector and
							o.cod_programa=a.cod_programa and
							o.cod_sub_prog=a.cod_sub_prog and
							o.cod_proyecto=a.cod_proyecto and
							o.cod_activ_obra=a.cod_activ_obra and
							o.cod_partida=a.cod_partida and
							o.cod_partida=a.cod_partida and
							o.cod_generica=a.cod_generica and
							o.cod_especifica=a.cod_especifica and
							o.cod_sub_espec=a.cod_sub_espec and
							o.cod_auxiliar=a.cod_auxiliar limit 1)
						as deno_auxiliar,
						  a.cod_nivel_i,
						  a.cod_nivel_ii,
						  a.cod_ficha,
						  b.denominacion as denominacion_nomina,
						  b.denominacion_devengado,
						  b.clasificacion_personal,
						  b.frecuencia_cobro,
						  b.dias_laborables,
						  b.horas_laborables,
						  b.descuentos_ley,
						  b.mensajes_colectivos,
						  b.status_nomina,
						  b.cantidad_pagos,
						  b.correspondiente,
						  b.frecuencia_pago,
						  b.numero_recibo,
						  b.control_autorizacion,
						  b.autorizacion_diskettes,
						  b.sueldo_sugerido,
						  b.ultimo_cargo,
						  b.ultima_ficha,
						  b.ano_desde,
						  b.ano_hasta,
						  b.codigo_transaccion,
						  b.dias_cobro,
						  c.denominacion_clase as denominacion_cargo,
						  (SELECT denominacion FROM cugd02_dependencias b WHERE b.cod_tipo_institucion = a.cod_tipo_inst and b.cod_institucion  =  a.cod_inst and b.cod_dependencia   =  a.cod_dep) as denominacion_dep,
						  (SELECT denominacion FROM cnmd04_ocupacion bb WHERE bb.cod_nivel_i = a.cod_nivel_i and bb.cod_nivel_ii  =  a.cod_nivel_ii ) as denominacion_clasificacion,
						  (SELECT d.cedula_identidad FROM cnmd06_fichas d where
						   d.cod_presi            =  a.cod_presi                and
						   d.cod_entidad          =  a.cod_entidad              and
						   d.cod_tipo_inst        =  a.cod_tipo_inst            and
						   d.cod_inst             =  a.cod_inst                 and
						   d.cod_dep              =  a.cod_dep                  and
						   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
						   d.cod_cargo            =  a.cod_cargo                and
						   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  as  cedula_identidad_ficha,


						   (SELECT e.primer_apellido  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
																	   d.cod_presi            =  a.cod_presi                and
																	   d.cod_entidad          =  a.cod_entidad              and
																	   d.cod_tipo_inst        =  a.cod_tipo_inst            and
																	   d.cod_inst             =  a.cod_inst                 and
																	   d.cod_dep              =  a.cod_dep                  and
																	   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
																	   d.cod_cargo            =  a.cod_cargo                and
																	   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.primer_apellido)
	                      as  primer_apellido,

	                      (SELECT e.segundo_apellido  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
																	   d.cod_presi            =  a.cod_presi                and
																	   d.cod_entidad          =  a.cod_entidad              and
																	   d.cod_tipo_inst        =  a.cod_tipo_inst            and
																	   d.cod_inst             =  a.cod_inst                 and
																	   d.cod_dep              =  a.cod_dep                  and
																	   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
																	   d.cod_cargo            =  a.cod_cargo                and
																	   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.segundo_apellido)
	                      as  segundo_apellido,

                          (SELECT e.primer_nombre  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
																	   d.cod_presi            =  a.cod_presi                and
																	   d.cod_entidad          =  a.cod_entidad              and
																	   d.cod_tipo_inst        =  a.cod_tipo_inst            and
																	   d.cod_inst             =  a.cod_inst                 and
																	   d.cod_dep              =  a.cod_dep                  and
																	   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
																	   d.cod_cargo            =  a.cod_cargo                and
																	   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.primer_nombre)
	                      as  primer_nombre,


	                      (SELECT e.segundo_nombre  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
																	   d.cod_presi            =  a.cod_presi                and
																	   d.cod_entidad          =  a.cod_entidad              and
																	   d.cod_tipo_inst        =  a.cod_tipo_inst            and
																	   d.cod_inst             =  a.cod_inst                 and
																	   d.cod_dep              =  a.cod_dep                  and
																	   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
																	   d.cod_cargo            =  a.cod_cargo                and
																	   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.segundo_nombre)
	                      as  segundo_nombre


                 FROM

                          " . $tabla . " a, cnmd01 b, " . $vista . " c


                 WHERE

                           a.cod_presi            =  " . $cod_presi . "             and
						   a.cod_entidad          =  " . $cod_entidad . "           and
						   a.cod_tipo_inst        =  " . $cod_tipo_inst . "         and
						   a.cod_inst             =  " . $cod_inst . "              and
						   " . $dep_A . "
						   " . $tipo_nomina . "
						   " . $sql_a . "
						   " . $sql_b . "
						   " . $cond_vacante . "
						   b.cod_presi            =  a.cod_presi                and
						   b.cod_entidad          =  a.cod_entidad              and
						   b.cod_tipo_inst        =  a.cod_tipo_inst            and
						   b.cod_inst             =  a.cod_inst                 and
						   b.cod_dep              =  a.cod_dep                  and
						   b.cod_tipo_nomina      =  a.cod_tipo_nomina          and
						   c.cod_presi            =  a.cod_presi                and
						   c.cod_entidad          =  a.cod_entidad              and
						   c.cod_tipo_inst        =  a.cod_tipo_inst            and
						   c.cod_inst             =  a.cod_inst                 and
						   c.cod_dep              =  a.cod_dep                  and
						   c.cod_tipo_nomina      =  a.cod_tipo_nomina          and
						   c.cod_cargo            =  a.cod_cargo                and
                           c.cod_puesto           =  a.cod_puesto


                 ORDER BY
                            a.cod_presi,
                            a.cod_entidad,
                            a.cod_tipo_inst,
                            a.cod_inst,
                            a.cod_dep,
                            a.cod_tipo_nomina,
                            a.cod_estado,
						    a.cod_municipio,
						    a.cod_parroquia,
						    a.cod_centro,
                            a.cod_cargo


                            ASC

             ; ");

            $this->set('tipo', $tipo);
            $this->set('datos', $rs);
            $this->set('presentar_como', $frecuencia);
        }//fin$tipo


        $this->set('opcion', $opcion);
        $this->set('var1', $var1);
    }

//fin funtion

    function cargos_ordenado_administrativo_geografico($var1 = null, $var2 = null) {




        $tabla = "";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');

        set_time_limit(0);

        $tabla = "cnmd05";
        $vista = "v_cnmd05";



        if ($var2 == null) {
            $this->layout = "ajax";
            $opcion = 1;
            $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst;
            $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
            $dato = null;
            foreach ($year as $year) {
                $dato_year = $year['cfpd01_formulacion']['ano_formular'];
            }
            if ($var1 == 1) {
                $year_vista = $dato_year;
            } else {
                $year_vista = $this->ano_ejecucion();
            }
            $this->set('year', $year_vista);



            $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
            $this->concatenaN($lista, 'nomina');
        } else {
            $this->layout = "pdf";
            $opcion = 2;

            if (!empty($this->data['reporte3']['consolidacion'])) {
                $consolidacion = $this->data['reporte3']['consolidacion'];
            } else {
                $consolidacion = 2;
            }
            if (!empty($this->data['reporte3']['year'])) {
                $year = $this->data['reporte3']['year'];
            } else {
                $year = $this->ano_ejecucion();
            }
            if (!empty($this->data['reporte3']['tipo'])) {
                $tipo = $this->data['reporte3']['tipo'];
            } else {
                $tipo = 1;
            }
            if (!empty($this->data['reporte3']['frecuencia'])) {
                $frecuencia = $this->data['reporte3']['frecuencia'];
            } else {
                $frecuencia = 1;
            }

            $sql_a = "";
            $sql_b = "";



            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_dirsuperior'])) {
                $sql_a .= "a.cod_dir_superior  = " . $this->data['cscp02_solicitud_cotizacion']['cod_dirsuperior'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_coordinacion'])) {
                $sql_a .= "a.cod_coordinacion  = " . $this->data['cscp02_solicitud_cotizacion']['cod_coordinacion'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_secretaria'])) {
                $sql_a .= "a.cod_secretaria  = " . $this->data['cscp02_solicitud_cotizacion']['cod_secretaria'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_direccion'])) {
                $sql_a .= "a.cod_direccion  = " . $this->data['cscp02_solicitud_cotizacion']['cod_direccion'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_division'])) {
                $sql_a .= "a.cod_division  = " . $this->data['cscp02_solicitud_cotizacion']['cod_division'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_departamento'])) {
                $sql_a .= "a.cod_departamento  = " . $this->data['cscp02_solicitud_cotizacion']['cod_departamento'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_oficina'])) {
                $sql_a .= "a.cod_oficina  = " . $this->data['cscp02_solicitud_cotizacion']['cod_oficina'] . "  and ";
            }




            if (!empty($this->data['cobp01_contratoobras']['cod_estado'])) {
                $sql_b .= "a.cod_estado  = " . $this->data['cobp01_contratoobras']['cod_estado'] . "  and ";
            }
            if (!empty($this->data['cobp01_contratoobras']['cod_municipio'])) {
                $sql_b .= "a.cod_municipio  = " . $this->data['cobp01_contratoobras']['cod_municipio'] . "  and ";
            }
            if (!empty($this->data['cobp01_contratoobras']['cod_parroquia'])) {
                $sql_b .= "a.cod_parroquia  = " . $this->data['cobp01_contratoobras']['cod_parroquia'] . "  and ";
            }
            if (!empty($this->data['cobp01_contratoobras']['cod_centro'])) {
                $sql_b .= "a.cod_centro  = " . $this->data['cobp01_contratoobras']['cod_centro'] . "  and ";
            }





            $tipo_nomina = "a.cod_tipo_nomina  = " . $tipo . "  and ";


            if ($consolidacion == 1) {
                $dep_A = "";
                $dep_B = "";
            } else if ($consolidacion == 2) {
                $dep_A = " a.cod_dep   =  " . $cod_dep . "  and";
                $dep_B = " a.cod_dep, ";
            }//fin function
            // TODOS - VACANTES - OCUPADOS
            $vacantes = $this->data['reporte3']['vacante'];
            if ($vacantes == 1) {
                $cond_vacante = " a.condicion_actividad=1 AND"; // vacante
            } else if ($vacantes == 2) {
                $cond_vacante = " a.condicion_actividad=2 AND "; // Ocupado
            } else if ($vacantes == 3) {
                $cond_vacante = " "; // todos
            }

            $rs = $this->v_cfpd05_denominaciones->execute("

		         SELECT
			              a.cod_presi,
						  a.cod_entidad,
						  a.cod_tipo_inst,
						  a.cod_inst,
						  a.cod_dep,
						  a.cod_tipo_nomina,
						  a.cod_cargo,
						  a.cod_puesto,
						  a.sueldo_basico,
						  a.compensaciones,
						  a.primas,
						  a.bonos,
						  a.cod_dir_superior,
						  a.cod_coordinacion,
						  a.cod_secretaria,
						  a.cod_direccion,
						  a.cod_division,
						  a.cod_departamento,
						  a.cod_oficina,
						 (SELECT xa.denominacion FROM cugd02_direccionsuperior xa where xa.cod_tipo_institucion=a.cod_tipo_inst  and xa.cod_institucion=a.cod_inst  and xa.cod_dependencia=a.cod_dep and xa.cod_dir_superior=a.cod_dir_superior                                                                                                                                                                                                                                        GROUP BY xa.denominacion) as  deno_cod_dir_superior,
						 (SELECT xb.denominacion FROM cugd02_coordinacion xb      where xb.cod_tipo_institucion=a.cod_tipo_inst  and xb.cod_institucion=a.cod_inst  and xb.cod_dependencia=a.cod_dep and xb.cod_dir_superior=a.cod_dir_superior and xb.cod_coordinacion=a.cod_coordinacion                                                                                                                                                                                             GROUP BY xb.denominacion) as  deno_cod_coordinacion,
						 (SELECT xc.denominacion FROM cugd02_secretaria xc        where xc.cod_tipo_institucion=a.cod_tipo_inst  and xc.cod_institucion=a.cod_inst  and xc.cod_dependencia=a.cod_dep and xc.cod_dir_superior=a.cod_dir_superior and xc.cod_coordinacion=a.cod_coordinacion and xc.cod_secretaria=a.cod_secretaria                                                                                                                                                      GROUP BY xc.denominacion) as  deno_cod_secretaria,
						 (SELECT xd.denominacion FROM cugd02_direccion xd         where xd.cod_tipo_institucion=a.cod_tipo_inst  and xd.cod_institucion=a.cod_inst  and xd.cod_dependencia=a.cod_dep and xd.cod_dir_superior=a.cod_dir_superior and xd.cod_coordinacion=a.cod_coordinacion and xd.cod_secretaria=a.cod_secretaria and xd.cod_direccion=a.cod_direccion                                                                                                                 GROUP BY xd.denominacion) as  deno_cod_direccion,
						 (SELECT xe.denominacion FROM cugd02_division xe          where xe.cod_tipo_institucion=a.cod_tipo_inst  and xe.cod_institucion=a.cod_inst  and xe.cod_dependencia=a.cod_dep and xe.cod_dir_superior=a.cod_dir_superior and xe.cod_coordinacion=a.cod_coordinacion and xe.cod_secretaria=a.cod_secretaria and xe.cod_direccion=a.cod_direccion  and xe.cod_division=a.cod_division                                                                             GROUP BY xe.denominacion) as  deno_cod_division,
						 (SELECT xf.denominacion FROM cugd02_departamento xf      where xf.cod_tipo_institucion=a.cod_tipo_inst  and xf.cod_institucion=a.cod_inst  and xf.cod_dependencia=a.cod_dep and xf.cod_dir_superior=a.cod_dir_superior and xf.cod_coordinacion=a.cod_coordinacion and xf.cod_secretaria=a.cod_secretaria and xf.cod_direccion=a.cod_direccion  and xf.cod_division=a.cod_division::int and xf.cod_departamento=a.cod_departamento::int                                  GROUP BY xf.denominacion) as  deno_cod_departamento,
						 (SELECT xg.denominacion FROM cugd02_oficina xg           where xg.cod_tipo_institucion=a.cod_tipo_inst  and xg.cod_institucion=a.cod_inst  and xg.cod_dependencia=a.cod_dep and xg.cod_dir_superior=a.cod_dir_superior and xg.cod_coordinacion=a.cod_coordinacion and xg.cod_secretaria=a.cod_secretaria and xg.cod_direccion=a.cod_direccion  and xg.cod_division=a.cod_division::int and xg.cod_departamento=a.cod_departamento::int and xg.cod_oficina=a.cod_oficina::int GROUP BY xg.denominacion) as  deno_cod_oficina,
                          a.cod_estado,
						  a.cod_municipio,
						  a.cod_parroquia,
						  a.cod_centro,
						 (SELECT xya.denominacion FROM cugd01_estados          xya where xya.cod_republica=a.cod_presi and xya.cod_estado=a.cod_estado                                                                                                                  GROUP BY xya.denominacion) as  deno_cod_estado,
						 (SELECT xyb.denominacion FROM cugd01_municipios       xyb where xyb.cod_republica=a.cod_presi and xyb.cod_estado=a.cod_estado  and xyb.cod_municipio=a.cod_municipio                                                                           GROUP BY xyb.denominacion) as  deno_cod_municipio,
						 (SELECT xyc.denominacion FROM cugd01_parroquias       xyc where xyc.cod_republica=a.cod_presi and xyc.cod_estado=a.cod_estado  and xyc.cod_municipio=a.cod_municipio and xyc.cod_parroquia = a.cod_parroquia                                   GROUP BY xyc.denominacion) as  deno_cod_parroquia,
						 (SELECT xyd.denominacion FROM cugd01_centros_poblados xyd where xyd.cod_republica=a.cod_presi and xyd.cod_estado=a.cod_estado  and xyd.cod_municipio=a.cod_municipio and xyd.cod_parroquia = a.cod_parroquia and xyd.cod_centro = a.cod_centro GROUP BY xyd.denominacion) as  deno_cod_centro,
						  a.condicion_actividad,
						  a.ano,
						  a.cod_sector,
						(select
							z.deno_sector
							from v_cfpd05_denominaciones z
							where
							z.cod_presi=a.cod_presi and
							z.cod_entidad=a.cod_entidad and
							z.cod_tipo_inst=a.cod_tipo_inst and
							z.cod_inst=a.cod_inst and
							z.cod_dep=a.cod_dep and
							z.ano=a.ano and
							z.cod_sector=a.cod_sector limit 1)
						as deno_sector,
						a.cod_programa,
						(select
							y.deno_programa
							from v_cfpd05_denominaciones y
							where
							y.cod_presi=a.cod_presi and
							y.cod_entidad=a.cod_entidad and
							y.cod_tipo_inst=a.cod_tipo_inst and
							y.cod_inst=a.cod_inst and
							y.cod_dep=a.cod_dep and
							y.ano=a.ano and
							y.cod_sector=a.cod_sector and
							y.cod_programa=a.cod_programa limit 1)
						as deno_programa,
						a.cod_sub_prog,
						(select
							x.deno_sub_prog
							from v_cfpd05_denominaciones x
							where
							x.cod_presi=a.cod_presi and
							x.cod_entidad=a.cod_entidad and
							x.cod_tipo_inst=a.cod_tipo_inst and
							x.cod_inst=a.cod_inst and
							x.cod_dep=a.cod_dep and
							x.ano=a.ano and
							x.cod_sector=a.cod_sector and
							x.cod_programa=a.cod_programa and
							x.cod_sub_prog=a.cod_sub_prog limit 1)
						as deno_sub_prog,
						a.cod_proyecto,
						(select
							w.deno_proyecto
							from v_cfpd05_denominaciones w
							where
							w.cod_presi=a.cod_presi and
							w.cod_entidad=a.cod_entidad and
							w.cod_tipo_inst=a.cod_tipo_inst and
							w.cod_inst=a.cod_inst and
							w.cod_dep=a.cod_dep and
							w.ano=a.ano and
							w.cod_sector=a.cod_sector and
							w.cod_programa=a.cod_programa and
							w.cod_sub_prog=a.cod_sub_prog and
							w.cod_proyecto=a.cod_proyecto limit 1)
						as deno_proyecto,
						a.cod_activ_obra,
						(select
							u.deno_activ_obra
							from v_cfpd05_denominaciones u
							where
							u.cod_presi=a.cod_presi and
							u.cod_entidad=a.cod_entidad and
							u.cod_tipo_inst=a.cod_tipo_inst and
							u.cod_inst=a.cod_inst and
							u.cod_dep=a.cod_dep and
							u.ano=a.ano and
							u.cod_sector=a.cod_sector and
							u.cod_programa=a.cod_programa and
							u.cod_sub_prog=a.cod_sub_prog and
							u.cod_proyecto=a.cod_proyecto and u.cod_activ_obra=a.cod_activ_obra limit 1)
						as deno_activ_obra,
						a.cod_partida,
						(select v.deno_partida
							from v_cfpd05_denominaciones v
							where
							v.cod_presi=a.cod_presi and
							v.cod_entidad=a.cod_entidad and
							v.cod_tipo_inst=a.cod_tipo_inst and
							v.cod_inst=a.cod_inst and
							v.cod_dep=a.cod_dep and
							v.ano=a.ano and
							v.cod_sector=a.cod_sector and
							v.cod_programa=a.cod_programa and
							v.cod_sub_prog=a.cod_sub_prog and
							v.cod_proyecto=a.cod_proyecto and
							v.cod_activ_obra=a.cod_activ_obra and
							v.cod_partida=a.cod_partida limit 1)
						as deno_partida,
						a.cod_generica,
						(select t.deno_generica
							from v_cfpd05_denominaciones t
							where
							t.cod_presi=a.cod_presi and
							t.cod_entidad=a.cod_entidad and
							t.cod_tipo_inst=a.cod_tipo_inst and
							t.cod_inst=a.cod_inst and
							t.cod_dep=a.cod_dep and
							t.ano=a.ano and
							t.cod_sector=a.cod_sector and
							t.cod_programa=a.cod_programa and
							t.cod_sub_prog=a.cod_sub_prog and
							t.cod_proyecto=a.cod_proyecto and
							t.cod_activ_obra=a.cod_activ_obra and
							t.cod_partida=a.cod_partida and
							t.cod_partida=a.cod_partida and
							t.cod_generica=a.cod_generica limit 1)
						as deno_generica,
						a.cod_especifica,
						(select s.deno_especifica
							from v_cfpd05_denominaciones s
							where
							s.cod_presi=a.cod_presi and
							s.cod_entidad=a.cod_entidad and
							s.cod_tipo_inst=a.cod_tipo_inst and
							s.cod_inst=a.cod_inst and
							s.cod_dep=a.cod_dep and
							s.ano=a.ano and
							s.cod_sector=a.cod_sector and
							s.cod_programa=a.cod_programa and
							s.cod_sub_prog=a.cod_sub_prog and
							s.cod_proyecto=a.cod_proyecto and
							s.cod_activ_obra=a.cod_activ_obra and
							s.cod_partida=a.cod_partida and
							s.cod_partida=a.cod_partida and
							s.cod_generica=a.cod_generica and
							s.cod_especifica=a.cod_especifica limit 1)
						as deno_especifica,
						a.cod_sub_espec,
						(select r.deno_sub_espec
							from v_cfpd05_denominaciones r
							where
							r.cod_presi=a.cod_presi and
							r.cod_entidad=a.cod_entidad and
							r.cod_tipo_inst=a.cod_tipo_inst and
							r.cod_inst=a.cod_inst and
							r.cod_dep=a.cod_dep and
							r.ano=a.ano and
							r.cod_sector=a.cod_sector and
							r.cod_programa=a.cod_programa and
							r.cod_sub_prog=a.cod_sub_prog and
							r.cod_proyecto=a.cod_proyecto and
							r.cod_activ_obra=a.cod_activ_obra and
							r.cod_partida=a.cod_partida and
							r.cod_partida=a.cod_partida and
							r.cod_generica=a.cod_generica and
							r.cod_especifica=a.cod_especifica and
							r.cod_sub_espec=a.cod_sub_espec limit 1)
						as deno_sub_espe,
						a.cod_auxiliar,
						(select o.deno_auxiliar
							from v_cfpd05_denominaciones o
							where
							o.cod_presi=a.cod_presi and
							o.cod_entidad=a.cod_entidad and
							o.cod_tipo_inst=a.cod_tipo_inst and
							o.cod_inst=a.cod_inst and
							o.cod_dep=a.cod_dep and
							o.ano=a.ano and
							o.cod_sector=a.cod_sector and
							o.cod_programa=a.cod_programa and
							o.cod_sub_prog=a.cod_sub_prog and
							o.cod_proyecto=a.cod_proyecto and
							o.cod_activ_obra=a.cod_activ_obra and
							o.cod_partida=a.cod_partida and
							o.cod_partida=a.cod_partida and
							o.cod_generica=a.cod_generica and
							o.cod_especifica=a.cod_especifica and
							o.cod_sub_espec=a.cod_sub_espec and
							o.cod_auxiliar=a.cod_auxiliar limit 1)
						as deno_auxiliar,
						  a.cod_nivel_i,
						  a.cod_nivel_ii,
						  a.cod_ficha,
						  b.denominacion as denominacion_nomina,
						  b.denominacion_devengado,
						  b.clasificacion_personal,
						  b.frecuencia_cobro,
						  b.dias_laborables,
						  b.horas_laborables,
						  b.descuentos_ley,
						  b.mensajes_colectivos,
						  b.status_nomina,
						  b.cantidad_pagos,
						  b.correspondiente,
						  b.frecuencia_pago,
						  b.numero_recibo,
						  b.control_autorizacion,
						  b.autorizacion_diskettes,
						  b.sueldo_sugerido,
						  b.ultimo_cargo,
						  b.ultima_ficha,
						  b.ano_desde,
						  b.ano_hasta,
						  b.codigo_transaccion,
						  b.dias_cobro,
						  c.denominacion_clase as denominacion_cargo,
						  (SELECT denominacion FROM cugd02_dependencias b WHERE b.cod_tipo_institucion = a.cod_tipo_inst and b.cod_institucion  =  a.cod_inst and b.cod_dependencia   =  a.cod_dep) as denominacion_dep,
						  (SELECT denominacion FROM cnmd04_ocupacion bb WHERE bb.cod_nivel_i = a.cod_nivel_i and bb.cod_nivel_ii  =  a.cod_nivel_ii ) as denominacion_clasificacion,
						  (SELECT d.cedula_identidad FROM cnmd06_fichas d where
						   d.cod_presi            =  a.cod_presi                and
						   d.cod_entidad          =  a.cod_entidad              and
						   d.cod_tipo_inst        =  a.cod_tipo_inst            and
						   d.cod_inst             =  a.cod_inst                 and
						   d.cod_dep              =  a.cod_dep                  and
						   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
						   d.cod_cargo            =  a.cod_cargo                and
						   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  as  cedula_identidad_ficha,


						   (SELECT e.primer_apellido  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
																	   d.cod_presi            =  a.cod_presi                and
																	   d.cod_entidad          =  a.cod_entidad              and
																	   d.cod_tipo_inst        =  a.cod_tipo_inst            and
																	   d.cod_inst             =  a.cod_inst                 and
																	   d.cod_dep              =  a.cod_dep                  and
																	   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
																	   d.cod_cargo            =  a.cod_cargo                and
																	   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.primer_apellido)
	                      as  primer_apellido,

	                      (SELECT e.segundo_apellido  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
																	   d.cod_presi            =  a.cod_presi                and
																	   d.cod_entidad          =  a.cod_entidad              and
																	   d.cod_tipo_inst        =  a.cod_tipo_inst            and
																	   d.cod_inst             =  a.cod_inst                 and
																	   d.cod_dep              =  a.cod_dep                  and
																	   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
																	   d.cod_cargo            =  a.cod_cargo                and
																	   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.segundo_apellido)
	                      as  segundo_apellido,

                          (SELECT e.primer_nombre  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
																	   d.cod_presi            =  a.cod_presi                and
																	   d.cod_entidad          =  a.cod_entidad              and
																	   d.cod_tipo_inst        =  a.cod_tipo_inst            and
																	   d.cod_inst             =  a.cod_inst                 and
																	   d.cod_dep              =  a.cod_dep                  and
																	   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
																	   d.cod_cargo            =  a.cod_cargo                and
																	   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.primer_nombre)
	                      as  primer_nombre,


	                      (SELECT e.segundo_nombre  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
																	   d.cod_presi            =  a.cod_presi                and
																	   d.cod_entidad          =  a.cod_entidad              and
																	   d.cod_tipo_inst        =  a.cod_tipo_inst            and
																	   d.cod_inst             =  a.cod_inst                 and
																	   d.cod_dep              =  a.cod_dep                  and
																	   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
																	   d.cod_cargo            =  a.cod_cargo                and
																	   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.segundo_nombre)
	                      as  segundo_nombre


                 FROM

                          " . $tabla . " a, cnmd01 b, " . $vista . " c


                 WHERE

                           a.cod_presi            =  " . $cod_presi . "             and
						   a.cod_entidad          =  " . $cod_entidad . "           and
						   a.cod_tipo_inst        =  " . $cod_tipo_inst . "         and
						   a.cod_inst             =  " . $cod_inst . "              and
						   " . $dep_A . "
						   " . $tipo_nomina . "
						   " . $sql_a . "
						   " . $sql_b . "
						   " . $cond_vacante . "
						   b.cod_presi            =  a.cod_presi                and
						   b.cod_entidad          =  a.cod_entidad              and
						   b.cod_tipo_inst        =  a.cod_tipo_inst            and
						   b.cod_inst             =  a.cod_inst                 and
						   b.cod_dep              =  a.cod_dep                  and
						   b.cod_tipo_nomina      =  a.cod_tipo_nomina          and
						   c.cod_presi            =  a.cod_presi                and
						   c.cod_entidad          =  a.cod_entidad              and
						   c.cod_tipo_inst        =  a.cod_tipo_inst            and
						   c.cod_inst             =  a.cod_inst                 and
						   c.cod_dep              =  a.cod_dep                  and
						   c.cod_tipo_nomina      =  a.cod_tipo_nomina          and
						   c.cod_cargo            =  a.cod_cargo                and
                           c.cod_puesto           =  a.cod_puesto


                 ORDER BY
                            a.cod_presi,
                            a.cod_entidad,
                            a.cod_tipo_inst,
                            a.cod_inst,
                            a.cod_dep,
                            a.cod_tipo_nomina,
                            a.cod_estado,
						    a.cod_municipio,
						    a.cod_parroquia,
						    a.cod_centro,
						    a.cod_dir_superior,
						    a.cod_coordinacion,
						    a.cod_secretaria,
						    a.cod_direccion,
						    a.cod_division,
						    a.cod_departamento,
						    a.cod_oficina,
                            a.cod_cargo


                            ASC

             ; ");

            $this->set('tipo', $tipo);
            $this->set('datos', $rs);
            $this->set('presentar_como', $frecuencia);
        }//fin$tipo


        $this->set('opcion', $opcion);
        $this->set('var1', $var1);
    }

//fin funtion

    function cargos_ordenados_por_ubicacion_administrativa_cfpd97($var1 = null, $var2 = null) {




        $tabla = "";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');

        set_time_limit(0);

        $tabla = "cfpd97";
        $vista = "v_cfpd97";



        if ($var2 == null) {
            $this->layout = "ajax";
            $opcion = 1;
            $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst;
            $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
            $dato = null;
            foreach ($year as $year) {
                $dato_year = $year['cfpd01_formulacion']['ano_formular'];
            }
            $year_vista = $dato_year;
            $this->set('year', $year_vista);

            //$Lista = $this->v_cnmd05->generateList($this->SQLCA(), 'cod_tipo_nomina ASC', null, '{n}.v_cnmd05.cod_tipo_nomina', '{n}.v_cnmd05.tipo_nomina');
            //$this->concatena($Lista, 'cod_tipo_nomina');

            $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
            $this->concatenaN($lista, 'nomina');
        } else {
            $this->layout = "pdf";
            $opcion = 2;

            if (!empty($this->data['reporte3']['consolidacion'])) {
                $consolidacion = $this->data['reporte3']['consolidacion'];
            } else {
                $consolidacion = 2;
            }
            if (!empty($this->data['reporte3']['year'])) {
                $year = $this->data['reporte3']['year'];
            } else {
                $year = $this->ano_ejecucion();
            }
            if (!empty($this->data['reporte3']['tipo'])) {
                $tipo = $this->data['reporte3']['tipo'];
            } else {
                $tipo = 1;
            }
            if (!empty($this->data['reporte3']['frecuencia'])) {
                $frecuencia = $this->data['reporte3']['frecuencia'];
            } else {
                $frecuencia = 1;
            }

            $sql_a = "";

            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_dirsuperior'])) {
                $sql_a .= "a.cod_dir_superior  = " . $this->data['cscp02_solicitud_cotizacion']['cod_dirsuperior'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_coordinacion'])) {
                $sql_a .= "a.cod_coordinacion  = " . $this->data['cscp02_solicitud_cotizacion']['cod_coordinacion'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_secretaria'])) {
                $sql_a .= "a.cod_secretaria  = " . $this->data['cscp02_solicitud_cotizacion']['cod_secretaria'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_direccion'])) {
                $sql_a .= "a.cod_direccion  = " . $this->data['cscp02_solicitud_cotizacion']['cod_direccion'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_division'])) {
                $sql_a .= "a.cod_division  = " . $this->data['cscp02_solicitud_cotizacion']['cod_division'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_departamento'])) {
                $sql_a .= "a.cod_departamento  = " . $this->data['cscp02_solicitud_cotizacion']['cod_departamento'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_oficina'])) {
                $sql_a .= "a.cod_oficina  = " . $this->data['cscp02_solicitud_cotizacion']['cod_oficina'] . "  and ";
            }





            $tipo_nomina = "a.cod_tipo_nomina  = " . $tipo . "  and ";

            if ($cod_dep == 1) {

                if (isset($this->data['cfpp05']['consolidacion'])) {
                    if ($this->data['cfpp05']['consolidacion'] == 2) {
                        $dep_A = " a.cod_dep   =  " . $_SESSION['cod_dep_reporte_consolidado'] . "  and ";
                        $dep_B = " a.cod_dep, ";
                    } else if ($this->data['cfpp05']['consolidacion'] == 1) {
                        $dep_A = "";
                        $dep_B = "";
                    }
                } else {

                    $dep_A = "";
                    $dep_B = "";
                }
            } else {
                $dep_A = " a.cod_dep   =  " . $_SESSION['cod_dep_reporte_consolidado'] . "  and ";
                $dep_B = " a.cod_dep, ";
            }



            $rs = $this->v_cfpd05_denominaciones->execute("

						         SELECT
							              a.cod_presi,
										  a.cod_entidad,
										  a.cod_tipo_inst,
										  a.cod_inst,
										  a.cod_dep,
										  a.cod_tipo_nomina,
										  a.cod_cargo,
										  a.cod_puesto,
										  a.sueldo_basico,
										  a.compensaciones,
										  a.primas,
										  a.bonos,
										  a.cod_dir_superior,
										  a.cod_coordinacion,
										  a.cod_secretaria,
										  a.cod_direccion,
										  a.cod_division,
										  a.cod_departamento,
										  a.cod_oficina,
										 (SELECT xa.denominacion FROM cugd02_direccionsuperior xa where xa.cod_tipo_institucion=a.cod_tipo_inst  and xa.cod_institucion=a.cod_inst and xa.cod_dependencia=a.cod_dep and xa.cod_dir_superior=a.cod_dir_superior                                                                                                                                                                                                                                        GROUP BY xa.denominacion) as  deno_cod_dir_superior,
										 (SELECT xb.denominacion FROM cugd02_coordinacion xb      where xb.cod_tipo_institucion=a.cod_tipo_inst  and xb.cod_institucion=a.cod_inst and xb.cod_dependencia=a.cod_dep and xb.cod_dir_superior=a.cod_dir_superior and xb.cod_coordinacion=a.cod_coordinacion                                                                                                                                                                                             GROUP BY xb.denominacion) as  deno_cod_coordinacion,
										 (SELECT xc.denominacion FROM cugd02_secretaria xc        where xc.cod_tipo_institucion=a.cod_tipo_inst  and xc.cod_institucion=a.cod_inst and xc.cod_dependencia=a.cod_dep and xc.cod_dir_superior=a.cod_dir_superior and xc.cod_coordinacion=a.cod_coordinacion and xc.cod_secretaria=a.cod_secretaria                                                                                                                                                      GROUP BY xc.denominacion) as  deno_cod_secretaria,
										 (SELECT xd.denominacion FROM cugd02_direccion xd         where xd.cod_tipo_institucion=a.cod_tipo_inst  and xd.cod_institucion=a.cod_inst and xd.cod_dependencia=a.cod_dep and xd.cod_dir_superior=a.cod_dir_superior and xd.cod_coordinacion=a.cod_coordinacion and xd.cod_secretaria=a.cod_secretaria and xd.cod_direccion=a.cod_direccion                                                                                                                 GROUP BY xd.denominacion) as  deno_cod_direccion,
										 (SELECT xe.denominacion FROM cugd02_division xe          where xe.cod_tipo_institucion=a.cod_tipo_inst  and xe.cod_institucion=a.cod_inst and xe.cod_dependencia=a.cod_dep and xe.cod_dir_superior=a.cod_dir_superior and xe.cod_coordinacion=a.cod_coordinacion and xe.cod_secretaria=a.cod_secretaria and xe.cod_direccion=a.cod_direccion  and xe.cod_division=a.cod_division                                                                             GROUP BY xe.denominacion) as  deno_cod_division,
										 (SELECT xf.denominacion FROM cugd02_departamento xf      where xf.cod_tipo_institucion=a.cod_tipo_inst  and xf.cod_institucion=a.cod_inst and xf.cod_dependencia=a.cod_dep and xf.cod_dir_superior=a.cod_dir_superior and xf.cod_coordinacion=a.cod_coordinacion and xf.cod_secretaria=a.cod_secretaria and xf.cod_direccion=a.cod_direccion  and xf.cod_division=a.cod_division and xf.cod_departamento=a.cod_departamento                                  GROUP BY xf.denominacion) as  deno_cod_departamento,
										 (SELECT xg.denominacion FROM cugd02_oficina xg           where xg.cod_tipo_institucion=a.cod_tipo_inst  and xg.cod_institucion=a.cod_inst and xg.cod_dependencia=a.cod_dep and xg.cod_dir_superior=a.cod_dir_superior and xg.cod_coordinacion=a.cod_coordinacion and xg.cod_secretaria=a.cod_secretaria and xg.cod_direccion=a.cod_direccion  and xg.cod_division=a.cod_division and xg.cod_departamento=a.cod_departamento and xg.cod_oficina=a.cod_oficina GROUP BY xg.denominacion) as  deno_cod_oficina,
										  a.cod_estado,
										  a.cod_municipio,
										  a.cod_parroquia,
										  a.cod_centro,
										  a.condicion_actividad,
										  a.ano,
										  a.cod_sector,
										(select
											z.deno_sector
											from v_cfpd05_denominaciones z
											where
											z.cod_presi=a.cod_presi and
											z.cod_entidad=a.cod_entidad and
											z.cod_tipo_inst=a.cod_tipo_inst and
											z.cod_inst=a.cod_inst and
											z.cod_dep=a.cod_dep and
											z.ano=a.ano and
											z.cod_sector=a.cod_sector limit 1)
										as deno_sector,
										a.cod_programa,
										(select
											y.deno_programa
											from v_cfpd05_denominaciones y
											where
											y.cod_presi=a.cod_presi and
											y.cod_entidad=a.cod_entidad and
											y.cod_tipo_inst=a.cod_tipo_inst and
											y.cod_inst=a.cod_inst and
											y.cod_dep=a.cod_dep and
											y.ano=a.ano and
											y.cod_sector=a.cod_sector and
											y.cod_programa=a.cod_programa limit 1)
										as deno_programa,
										a.cod_sub_prog,
										(select
											x.deno_sub_prog
											from v_cfpd05_denominaciones x
											where
											x.cod_presi=a.cod_presi and
											x.cod_entidad=a.cod_entidad and
											x.cod_tipo_inst=a.cod_tipo_inst and
											x.cod_inst=a.cod_inst and
											x.cod_dep=a.cod_dep and
											x.ano=a.ano and
											x.cod_sector=a.cod_sector and
											x.cod_programa=a.cod_programa and
											x.cod_sub_prog=a.cod_sub_prog limit 1)
										as deno_sub_prog,
										a.cod_proyecto,
										(select
											w.deno_proyecto
											from v_cfpd05_denominaciones w
											where
											w.cod_presi=a.cod_presi and
											w.cod_entidad=a.cod_entidad and
											w.cod_tipo_inst=a.cod_tipo_inst and
											w.cod_inst=a.cod_inst and
											w.cod_dep=a.cod_dep and
											w.ano=a.ano and
											w.cod_sector=a.cod_sector and
											w.cod_programa=a.cod_programa and
											w.cod_sub_prog=a.cod_sub_prog and
											w.cod_proyecto=a.cod_proyecto limit 1)
										as deno_proyecto,
										a.cod_activ_obra,
										(select
											u.deno_activ_obra
											from v_cfpd05_denominaciones u
											where
											u.cod_presi=a.cod_presi and
											u.cod_entidad=a.cod_entidad and
											u.cod_tipo_inst=a.cod_tipo_inst and
											u.cod_inst=a.cod_inst and
											u.cod_dep=a.cod_dep and
											u.ano=a.ano and
											u.cod_sector=a.cod_sector and
											u.cod_programa=a.cod_programa and
											u.cod_sub_prog=a.cod_sub_prog and
											u.cod_proyecto=a.cod_proyecto and u.cod_activ_obra=a.cod_activ_obra limit 1)
										as deno_activ_obra,
										a.cod_partida,
										(select v.deno_partida
											from v_cfpd05_denominaciones v
											where
											v.cod_presi=a.cod_presi and
											v.cod_entidad=a.cod_entidad and
											v.cod_tipo_inst=a.cod_tipo_inst and
											v.cod_inst=a.cod_inst and
											v.cod_dep=a.cod_dep and
											v.ano=a.ano and
											v.cod_sector=a.cod_sector and
											v.cod_programa=a.cod_programa and
											v.cod_sub_prog=a.cod_sub_prog and
											v.cod_proyecto=a.cod_proyecto and
											v.cod_activ_obra=a.cod_activ_obra and
											v.cod_partida=a.cod_partida limit 1)
										as deno_partida,
										a.cod_generica,
										(select t.deno_generica
											from v_cfpd05_denominaciones t
											where
											t.cod_presi=a.cod_presi and
											t.cod_entidad=a.cod_entidad and
											t.cod_tipo_inst=a.cod_tipo_inst and
											t.cod_inst=a.cod_inst and
											t.cod_dep=a.cod_dep and
											t.ano=a.ano and
											t.cod_sector=a.cod_sector and
											t.cod_programa=a.cod_programa and
											t.cod_sub_prog=a.cod_sub_prog and
											t.cod_proyecto=a.cod_proyecto and
											t.cod_activ_obra=a.cod_activ_obra and
											t.cod_partida=a.cod_partida and
											t.cod_partida=a.cod_partida and
											t.cod_generica=a.cod_generica limit 1)
										as deno_generica,
										a.cod_especifica,
										(select s.deno_especifica
											from v_cfpd05_denominaciones s
											where
											s.cod_presi=a.cod_presi and
											s.cod_entidad=a.cod_entidad and
											s.cod_tipo_inst=a.cod_tipo_inst and
											s.cod_inst=a.cod_inst and
											s.cod_dep=a.cod_dep and
											s.ano=a.ano and
											s.cod_sector=a.cod_sector and
											s.cod_programa=a.cod_programa and
											s.cod_sub_prog=a.cod_sub_prog and
											s.cod_proyecto=a.cod_proyecto and
											s.cod_activ_obra=a.cod_activ_obra and
											s.cod_partida=a.cod_partida and
											s.cod_partida=a.cod_partida and
											s.cod_generica=a.cod_generica and
											s.cod_especifica=a.cod_especifica limit 1)
										as deno_especifica,
										a.cod_sub_espec,
										(select r.deno_sub_espec
											from v_cfpd05_denominaciones r
											where
											r.cod_presi=a.cod_presi and
											r.cod_entidad=a.cod_entidad and
											r.cod_tipo_inst=a.cod_tipo_inst and
											r.cod_inst=a.cod_inst and
											r.cod_dep=a.cod_dep and
											r.ano=a.ano and
											r.cod_sector=a.cod_sector and
											r.cod_programa=a.cod_programa and
											r.cod_sub_prog=a.cod_sub_prog and
											r.cod_proyecto=a.cod_proyecto and
											r.cod_activ_obra=a.cod_activ_obra and
											r.cod_partida=a.cod_partida and
											r.cod_partida=a.cod_partida and
											r.cod_generica=a.cod_generica and
											r.cod_especifica=a.cod_especifica and
											r.cod_sub_espec=a.cod_sub_espec limit 1)
										as deno_sub_espe,
										a.cod_auxiliar,
										(select o.deno_auxiliar
											from v_cfpd05_denominaciones o
											where
											o.cod_presi=a.cod_presi and
											o.cod_entidad=a.cod_entidad and
											o.cod_tipo_inst=a.cod_tipo_inst and
											o.cod_inst=a.cod_inst and
											o.cod_dep=a.cod_dep and
											o.ano=a.ano and
											o.cod_sector=a.cod_sector and
											o.cod_programa=a.cod_programa and
											o.cod_sub_prog=a.cod_sub_prog and
											o.cod_proyecto=a.cod_proyecto and
											o.cod_activ_obra=a.cod_activ_obra and
											o.cod_partida=a.cod_partida and
											o.cod_partida=a.cod_partida and
											o.cod_generica=a.cod_generica and
											o.cod_especifica=a.cod_especifica and
											o.cod_sub_espec=a.cod_sub_espec and
											o.cod_auxiliar=a.cod_auxiliar limit 1)
										as deno_auxiliar,
										  a.cod_nivel_i,
										  a.cod_nivel_ii,
										  a.cod_ficha,
										  b.denominacion as denominacion_nomina,
										  b.denominacion_devengado,
										  b.clasificacion_personal,
										  b.frecuencia_cobro,
										  b.dias_laborables,
										  b.horas_laborables,
										  b.descuentos_ley,
										  b.mensajes_colectivos,
										  b.status_nomina,
										  b.cantidad_pagos,
										  b.correspondiente,
										  b.frecuencia_pago,
										  b.numero_recibo,
										  b.control_autorizacion,
										  b.autorizacion_diskettes,
										  b.sueldo_sugerido,
										  b.ultimo_cargo,
										  b.ultima_ficha,
										  b.ano_desde,
										  b.ano_hasta,
										  b.codigo_transaccion,
										  b.dias_cobro,
										  c.denominacion_clase as denominacion_cargo,
										  (SELECT denominacion FROM cugd02_dependencias b WHERE b.cod_tipo_institucion = a.cod_tipo_inst and b.cod_institucion  =  a.cod_inst and b.cod_dependencia   =  a.cod_dep) as denominacion_dep,
										  (SELECT denominacion FROM cnmd04_ocupacion bb WHERE bb.cod_nivel_i = a.cod_nivel_i and bb.cod_nivel_ii  =  a.cod_nivel_ii ) as denominacion_clasificacion,
										  (SELECT d.cedula_identidad FROM cnmd06_fichas d where
										   d.cod_presi            =  a.cod_presi                and
										   d.cod_entidad          =  a.cod_entidad              and
										   d.cod_tipo_inst        =  a.cod_tipo_inst            and
										   d.cod_inst             =  a.cod_inst                 and
										   d.cod_dep              =  a.cod_dep                  and
										   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
										   d.cod_cargo            =  a.cod_cargo                and
										   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  as  cedula_identidad_ficha,


										   (SELECT e.primer_apellido  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
																					   d.cod_presi            =  a.cod_presi                and
																					   d.cod_entidad          =  a.cod_entidad              and
																					   d.cod_tipo_inst        =  a.cod_tipo_inst            and
																					   d.cod_inst             =  a.cod_inst                 and
																					   d.cod_dep              =  a.cod_dep                  and
																					   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
																					   d.cod_cargo            =  a.cod_cargo                and
																					   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.primer_apellido)
					                      as  primer_apellido,

					                      (SELECT e.segundo_apellido  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
																					   d.cod_presi            =  a.cod_presi                and
																					   d.cod_entidad          =  a.cod_entidad              and
																					   d.cod_tipo_inst        =  a.cod_tipo_inst            and
																					   d.cod_inst             =  a.cod_inst                 and
																					   d.cod_dep              =  a.cod_dep                  and
																					   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
																					   d.cod_cargo            =  a.cod_cargo                and
																					   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.segundo_apellido)
					                      as  segundo_apellido,

				                          (SELECT e.primer_nombre  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
																					   d.cod_presi            =  a.cod_presi                and
																					   d.cod_entidad          =  a.cod_entidad              and
																					   d.cod_tipo_inst        =  a.cod_tipo_inst            and
																					   d.cod_inst             =  a.cod_inst                 and
																					   d.cod_dep              =  a.cod_dep                  and
																					   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
																					   d.cod_cargo            =  a.cod_cargo                and
																					   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.primer_nombre)
					                      as  primer_nombre,


					                      (SELECT e.segundo_nombre  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
																					   d.cod_presi            =  a.cod_presi                and
																					   d.cod_entidad          =  a.cod_entidad              and
																					   d.cod_tipo_inst        =  a.cod_tipo_inst            and
																					   d.cod_inst             =  a.cod_inst                 and
																					   d.cod_dep              =  a.cod_dep                  and
																					   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
																					   d.cod_cargo            =  a.cod_cargo                and
																					   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.segundo_nombre)
					                      as  segundo_nombre


				                 FROM

				                          " . $tabla . " a, cnmd01 b, " . $vista . " c


				                 WHERE

				                           a.cod_presi            =  " . $cod_presi . "             and
										   a.cod_entidad          =  " . $cod_entidad . "           and
										   a.cod_tipo_inst        =  " . $cod_tipo_inst . "         and
										   a.cod_inst             =  " . $cod_inst . "              and
										   " . $dep_A . "
										   " . $tipo_nomina . "
										   " . $sql_a . "
										   b.cod_presi            =  a.cod_presi                and
										   b.cod_entidad          =  a.cod_entidad              and
										   b.cod_tipo_inst        =  a.cod_tipo_inst            and
										   b.cod_inst             =  a.cod_inst                 and
										   b.cod_dep              =  a.cod_dep                  and
										   b.cod_tipo_nomina      =  a.cod_tipo_nomina          and
										   c.cod_presi            =  a.cod_presi                and
										   c.cod_entidad          =  a.cod_entidad              and
										   c.cod_tipo_inst        =  a.cod_tipo_inst            and
										   c.cod_inst             =  a.cod_inst                 and
										   c.cod_dep              =  a.cod_dep                  and
										   c.cod_tipo_nomina      =  a.cod_tipo_nomina          and
										   c.cod_cargo            =  a.cod_cargo                and
				                           c.cod_puesto           =  a.cod_puesto


				                 ORDER BY
				                            a.cod_presi,
				                            a.cod_entidad,
				                            a.cod_tipo_inst,
				                            a.cod_inst,
				                            a.cod_dep,
				                            a.cod_tipo_nomina,
				                            a.cod_dir_superior,
										    a.cod_coordinacion,
										    a.cod_secretaria,
										    a.cod_direccion,
										    a.cod_division,
										    a.cod_departamento,
										    a.cod_oficina,
				                            a.cod_cargo


				                            ASC

				             ; ");

            $this->set('tipo', $tipo);
            $this->set('datos', $rs);
            $this->set('presentar_como', $frecuencia);
        }//fin$tipo


        $this->set('opcion', $opcion);
        $this->set('var1', $var1);
    }

//fin funtion

    function select_ubicacion($var1 = null, $var2 = null) {

        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');

        $this->layout = "ajax";

        $listadireccion_superior = $this->cugd02_direccionsuperior->generateList('cod_tipo_institucion=' . $cod_tipo_inst . ' and cod_institucion=' . $cod_inst . ' and  cod_dependencia=' . $cod_dep, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
        $this->concatena($listadireccion_superior, 'direccion_superior');

        $this->set("opcion", $var1);
    }

//fin opcion

    function select_geografica($var1 = null) {


        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');
        $this->Session->write('rep', $cod_presi);

        $this->layout = "ajax";

        $lista_estado = $this->cugd01_estados->generateList("cod_republica=" . $this->Session->read('SScodpresi'), 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
        $this->concatena($lista_estado, 'cod_estado');

        $this->set("opcion", $var1);
    }

//fin function

    function cargos_atender_poblacion($var1 = null, $var2 = null) {




        $tabla = "";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');

        set_time_limit(0);

        $tabla = "cnmd05";
        $vista = "v_cnmd05";
        $var1 = 2;


        if ($var2 == null) {
            $this->layout = "ajax";
            $opcion = 1;
            $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst;
            $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
            $dato = null;
            foreach ($year as $year) {
                $dato_year = $year['cfpd01_formulacion']['ano_formular'];
            }
            if ($var1 == 1) {
                $year_vista = $dato_year;
            } else {
                $year_vista = $this->ano_ejecucion();
            }
            $this->set('year', $year_vista);



            $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
            $this->concatenaN($lista, 'nomina');
        } else {
            $this->layout = "pdf";
            $opcion = 2;

            if (!empty($this->data['reporte3']['consolidacion'])) {
                $consolidacion = $this->data['reporte3']['consolidacion'];
            } else {
                $consolidacion = 2;
            }
            if (!empty($this->data['reporte3']['year'])) {
                $year = $this->data['reporte3']['year'];
            } else {
                $year = $this->ano_ejecucion();
            }
            if (!empty($this->data['reporte3']['tipo'])) {
                $tipo = $this->data['reporte3']['tipo'];
            } else {
                $tipo = 1;
            }
            if (!empty($this->data['reporte3']['frecuencia'])) {
                $frecuencia = $this->data['reporte3']['frecuencia'];
            } else {
                $frecuencia = 1;
            }

            $sql_a = "";
            $sql_b = "";






            if (!empty($this->data['cobp01_contratoobras']['cod_estado'])) {
                $sql_b .= "a.cod_estado  = " . $this->data['cobp01_contratoobras']['cod_estado'] . "  and ";
            }
            if (!empty($this->data['cobp01_contratoobras']['cod_municipio'])) {
                $sql_b .= "a.cod_municipio  = " . $this->data['cobp01_contratoobras']['cod_municipio'] . "  and ";
            }
            if (!empty($this->data['cobp01_contratoobras']['cod_parroquia'])) {
                $sql_b .= "a.cod_parroquia  = " . $this->data['cobp01_contratoobras']['cod_parroquia'] . "  and ";
            }
            if (!empty($this->data['cobp01_contratoobras']['cod_centro'])) {
                $sql_b .= "a.cod_centro  = " . $this->data['cobp01_contratoobras']['cod_centro'] . "  and ";
            }





            $tipo_nomina = "a.cod_tipo_nomina  = " . $tipo . "  and ";


            if ($consolidacion == 1) {
                $dep_A = "";
                $dep_B = "";
            } else if ($consolidacion == 2) {
                $dep_A = " a.cod_dep   =  " . $cod_dep . "  and";
                $dep_B = " a.cod_dep, ";
            }//fin function



            $rs = $this->v_cfpd05_denominaciones->execute("

		         SELECT
			              a.cod_presi,
						  a.cod_entidad,
						  a.cod_tipo_inst,
						  a.cod_inst,
						  a.cod_dep,
						  count(a.cod_cargo) as cod_cargo,
						  a.cod_puesto,
						  SUM(a.sueldo_basico) as sueldo_basico,
						  SUM(a.compensaciones) as compensaciones,
						  SUM(a.primas) as primas,
						  SUM(a.bonos) as bonos,
						  a.cod_estado,
						  a.cod_municipio,
						  a.cod_parroquia,
						  a.cod_centro,
						 (SELECT xya.denominacion FROM cugd01_estados          xya where xya.cod_republica=a.cod_presi and xya.cod_estado=a.cod_estado                                                                                                                  GROUP BY xya.denominacion) as  deno_cod_estado,
						 (SELECT xyb.denominacion FROM cugd01_municipios       xyb where xyb.cod_republica=a.cod_presi and xyb.cod_estado=a.cod_estado  and xyb.cod_municipio=a.cod_municipio                                                                           GROUP BY xyb.denominacion) as  deno_cod_municipio,
						 (SELECT xyc.denominacion FROM cugd01_parroquias       xyc where xyc.cod_republica=a.cod_presi and xyc.cod_estado=a.cod_estado  and xyc.cod_municipio=a.cod_municipio and xyc.cod_parroquia = a.cod_parroquia                                   GROUP BY xyc.denominacion) as  deno_cod_parroquia,
						 (SELECT xyd.denominacion FROM cugd01_centros_poblados xyd where xyd.cod_republica=a.cod_presi and xyd.cod_estado=a.cod_estado  and xyd.cod_municipio=a.cod_municipio and xyd.cod_parroquia = a.cod_parroquia and xyd.cod_centro = a.cod_centro GROUP BY xyd.denominacion) as  deno_cod_centro,
						 (SELECT xye.poblacion    FROM cugd01_centros_poblados xye where xye.cod_republica=a.cod_presi and xye.cod_estado=a.cod_estado  and xye.cod_municipio=a.cod_municipio and xye.cod_parroquia = a.cod_parroquia and xye.cod_centro = a.cod_centro GROUP BY xye.poblacion   ) as  poblacion_centro_poblado,
						  b.frecuencia_cobro,
						  c.denominacion_clase as denominacion_cargo,
						  (SELECT denominacion FROM cnmd04_ocupacion bb WHERE bb.cod_nivel_i = a.cod_nivel_i and bb.cod_nivel_ii  =  a.cod_nivel_ii ) as denominacion_clasificacion


                 FROM

                          " . $tabla . " a, cnmd01 b, " . $vista . " c


                 WHERE

                           a.cod_presi            =  " . $cod_presi . "             and
						   a.cod_entidad          =  " . $cod_entidad . "           and
						   a.cod_tipo_inst        =  " . $cod_tipo_inst . "         and
						   a.cod_inst             =  " . $cod_inst . "              and
						   " . $dep_A . "
						   " . $sql_a . "
						   " . $sql_b . "
						   b.cod_presi            =  a.cod_presi                and
						   b.cod_entidad          =  a.cod_entidad              and
						   b.cod_tipo_inst        =  a.cod_tipo_inst            and
						   b.cod_inst             =  a.cod_inst                 and
						   b.cod_dep              =  a.cod_dep                  and
						   b.cod_tipo_nomina      =  a.cod_tipo_nomina          and
						   c.cod_presi            =  a.cod_presi                and
						   c.cod_entidad          =  a.cod_entidad              and
						   c.cod_tipo_inst        =  a.cod_tipo_inst            and
						   c.cod_inst             =  a.cod_inst                 and
						   c.cod_dep              =  a.cod_dep                  and
						   c.cod_cargo            =  a.cod_cargo                and
                           c.cod_puesto           =  a.cod_puesto

                  group by

                          a.cod_presi,
						  a.cod_entidad,
						  a.cod_tipo_inst,
						  a.cod_inst,
						  " . $dep_B . "
						  a.cod_puesto,
						  a.cod_estado,
						  a.cod_municipio,
						  a.cod_parroquia,
						  a.cod_centro,
						  poblacion_centro_poblado,
						  deno_cod_estado,
						  deno_cod_municipio,
						  deno_cod_parroquia,
						  deno_cod_centro,
						  b.frecuencia_cobro,
						  denominacion_cargo,
						  denominacion_clasificacion


                   ORDER BY
                            a.cod_presi,
                            a.cod_entidad,
                            a.cod_tipo_inst,
                            a.cod_inst,
                            a.cod_dep,
                            a.cod_estado,
						    a.cod_municipio,
						    a.cod_parroquia,
						    a.cod_centro


                            ASC

             ; ");

            $this->set('tipo', $tipo);
            $this->set('datos', $rs);
            $this->set('presentar_como', $frecuencia);
        }//fin$tipo


        $this->set('opcion', $opcion);
        $this->set('var1', $var1);
    }

//fin funtion

    function grafico_formulacion_tipo_gasto($var = null) {
        $this->layout = "ajax";
        $cod_dep = $this->Session->read('SScoddep');
        $username = $this->Session->read('nom_usuario');
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');



        $ver = "si";

        $condicion_formulacion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' ';
        $year2 = $this->cfpd01_formulacion->findAll($condicion_formulacion, null, 'ano_formular ASC', null);
        $ano_formulacion = null;
        foreach ($year2 as $year2) {
            $ano = $year2['cfpd01_formulacion']['ano_formular'];
        }
        $ano = $year2['cfpd01_formulacion']['ano_formular'];

        $dato_ano = $ano;
        if (isset($this->data["tipo_presupuestoPDF"]["year"])) {
            $year = $this->data["tipo_presupuestoPDF"]["year"];
        } else {
            $year = $dato_ano;
        }
        if (isset($this->data["tipo_presupuestoPDF"]["opcion"])) {
            $var = $this->data["tipo_presupuestoPDF"]["opcion"];
            $ver = "no";
        } else {
            $var = 1;
        }
        $this->set("year", $year);
        $this->set("ver", $ver);

        $condicion = $this->condicion();
        $fields = null;
        $this->set('cod_dep', $cod_dep);
        if ($var == 1 && $cod_dep == 1) {
            $this->set('var', $var);
            $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst;
            $condicion .= " and ano=" . $year . " GROUP BY cod_presi, cod_entidad, cod_tipo_inst, cod_inst, ano";
            $fields = "SUM(gasto_inversion) as gasto_inversion, SUM(gasto_corriente) as gasto_corriente, SUM(total) as total";
        } else {

            $this->set('var', $var);
            $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst . " and cod_dep=" . $cod_dep . " and ano=" . $year . "";
            $fields = "SUM(gasto_inversion) as gasto_inversion, SUM(gasto_corriente) as gasto_corriente, SUM(total) as total";
        }
        $datos = $this->v_cfpd05_asignacion_corriente_capital->findAll($condicion, $fields, $order = null, $limit = null, $page = null, $recursive = null);












        $this->set('datos', $datos);
    }

//fin foreach

    function grafico_formulacion_tipo_gasto_pdf() {
        $this->layout = "pdf";
        $username = $this->Session->read('nom_usuario');
        $this->set('user', $username);
        $gasto_inversion = $this->data['tipo_gastoPDF']['gasto_inversion'];
        $por_gasto_inversion = $this->data['tipo_gastoPDF']['por_gasto_inversion'];
        $gasto_corriente = $this->data['tipo_gastoPDF']['gasto_corriente'];
        $por_gasto_corriente = $this->data['tipo_gastoPDF']['por_gasto_corriente'];
        $total = $this->data['tipo_gastoPDF']['total'];
        $rdm = $this->data['tipo_gastoPDF']['rdm'];
        $year = $this->data["tipo_gastoPDF"]["year"];
        $this->set('gasto_inversion', $gasto_inversion);
        $this->set('gasto_corriente', $gasto_corriente);
        $this->set('por_gasto_inversion', $por_gasto_inversion);
        $this->set('por_gasto_corriente', $por_gasto_corriente);
        $this->set('total', $total);
        $this->set('rdm', $rdm);
        $this->set('year', $year);
    }

    function grafico_formulacion_tipo_presupuesto($var = null, $var2 = null) {
        $this->layout = "ajax";
        $this->set("cod_dep", $this->verifica_SS(5));
        $ver = "si";
        $condicion_formulacion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' ';
        $year2 = $this->cfpd01_formulacion->findAll($condicion_formulacion, null, 'ano_formular ASC', null);
        $ano_formulacion = null;
        foreach ($year2 as $year2) {
            $ano = $year2['cfpd01_formulacion']['ano_formular'];
        }
        $ano = $year2['cfpd01_formulacion']['ano_formular'];

        $dato_ano = $ano;
        if (isset($this->data["tipo_presupuestoPDF"]["year"])) {
            $year = $this->data["tipo_presupuestoPDF"]["year"];
        } else {
            $year = $dato_ano;
        }
        if (isset($this->data["tipo_presupuestoPDF"]["opcion"])) {
            $var = $this->data["tipo_presupuestoPDF"]["opcion"];
            $ver = "no";
        } else {
            $var = 1;
        }
        $this->set("year", $year);
        $this->set("ver", $ver);

        if (isset($var) && $this->verifica_SS(5) == 1) {
            if ($var == 1) {//institucion
                $cod_dep = "";
                $cod_dep2 = "";
                $con = $this->SQLCA_report_a($var);
                $gror = "";
                $this->set("opcion", 1);
            } else {
                $cod_dep = "a.cod_dep,";
                $cod_dep2 = "x.cod_dep=a.cod_dep and";
                $con = $this->SQLCA_report_a();
                $gror = ",a.cod_dep";
                $this->set("opcion", 2);
            }
        } else {
            $cod_dep = "a.cod_dep,";
            $cod_dep2 = "x.cod_dep=a.cod_dep and";
            $con = $this->SQLCA_report_a();
            $gror = ",a.cod_dep";
            $this->set("opcion", 2);
        }

        $tipo_presupuesto = $this->cfpd05->execute("SELECT
a.cod_presi,
a.cod_entidad,
a.cod_tipo_inst,
a.cod_inst,
" . $cod_dep . "
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.tipo_presupuesto=1 and x.ano=" . $year . ") as ordinario,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.tipo_presupuesto=2 and x.ano=" . $year . ") as coordinado,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.tipo_presupuesto=3 and x.ano=" . $year . ") as laee,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.tipo_presupuesto=4 and x.ano=" . $year . ") as fides,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.tipo_presupuesto=5 and x.ano=" . $year . ") as ingresos_extra,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.tipo_presupuesto=5 and x.ano=" . $year . ") as ingresos_propios,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.ano=" . $year . ") as asignacion_total
   FROM
cfpd05 a
WHERE
   	" . $con . " and a.ano=" . $year . "
group by
a.cod_presi,
a.cod_entidad,
a.cod_tipo_inst,
a.cod_inst
" . $gror . "
order by
a.cod_presi,
a.cod_entidad,
a.cod_tipo_inst,
a.cod_inst
" . $gror . ";");

        $this->set('tipo_presupuesto', $tipo_presupuesto);
//pr($tipo_presupuesto);
    }

//fin tipo presupuesto

    function grafico_formulacion_tipo_sector($var = null) {
        $this->layout = "ajax";
        $this->set("cod_dep", $this->verifica_SS(5));

        $ver = "si";
        $condicion_formulacion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' ';
        $year2 = $this->cfpd01_formulacion->findAll($condicion_formulacion, null, 'ano_formular ASC', null);
        $ano_formulacion = null;
        foreach ($year2 as $year2) {
            $ano = $year2['cfpd01_formulacion']['ano_formular'];
        }
        $ano = $year2['cfpd01_formulacion']['ano_formular'];

        $dato_ano = $ano;
        if (isset($this->data["tipo_presupuestoPDF"]["year"])) {
            $year = $this->data["tipo_presupuestoPDF"]["year"];
        } else {
            $year = $dato_ano;
        }
        if (isset($this->data["tipo_presupuestoPDF"]["opcion"])) {
            $var = $this->data["tipo_presupuestoPDF"]["opcion"];
            $ver = "no";
        } else {
            $var = 1;
        }
        $this->set("year", $year);
        $this->set("ver", $ver);

        if (isset($var) && $this->verifica_SS(5) == 1) {
            if ($var == 1) {//institucion
                $cod_dep = "";
                $cod_dep2 = "";
                $con = $this->SQLCA_report_a($var);
                $gror = "";
                $this->set("opcion", 1);
            } else {
                $cod_dep = "a.cod_dep,";
                $cod_dep2 = "x.cod_dep=a.cod_dep and";
                $con = $this->SQLCA_report_a();
                $gror = ",a.cod_dep";
                $this->set("opcion", 2);
            }
        } else {
            $cod_dep = "a.cod_dep,";
            $cod_dep2 = "x.cod_dep=a.cod_dep and";
            $con = $this->SQLCA_report_a();
            $gror = ",a.cod_dep";
            $this->set("opcion", 2);
        }
        $_SESSION["CONDICIONPDF"] = $con;
        $tipo_sector = $this->cfpd05->execute("SELECT
a.cod_presi,
a.cod_entidad,
a.cod_tipo_inst,
a.cod_inst,
" . $cod_dep . "
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.cod_sector=1 and x.ano=" . $year . ") as sector_1,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.cod_sector=2 and x.ano=" . $year . ") as sector_2,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.cod_sector=3 and x.ano=" . $year . ") as sector_3,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.cod_sector=4 and x.ano=" . $year . ") as sector_4,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.cod_sector=5 and x.ano=" . $year . ") as sector_5,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.cod_sector=6 and x.ano=" . $year . ") as sector_6,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.cod_sector=7 and x.ano=" . $year . ") as sector_7,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.cod_sector=8 and x.ano=" . $year . ") as sector_8,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.cod_sector=9 and x.ano=" . $year . ") as sector_9,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.cod_sector=10 and x.ano=" . $year . ") as sector_10,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.cod_sector=11 and x.ano=" . $year . ") as sector_11,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.cod_sector=12 and x.ano=" . $year . ") as sector_12,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.cod_sector=13 and x.ano=" . $year . ") as sector_13,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.cod_sector=14 and x.ano=" . $year . ") as sector_14,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.cod_sector=15 and x.ano=" . $year . ") as sector_15,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.ano=" . $year . ") as asignacion_total
 	 FROM
cfpd05 a
WHERE
   	" . $con . " and a.ano=" . $year . "
group by
a.cod_presi,
a.cod_entidad,
a.cod_tipo_inst,
a.cod_inst
" . $gror . "
order by
a.cod_presi,
a.cod_entidad,
a.cod_tipo_inst,
a.cod_inst
" . $gror . ";");

        $this->set('tipo_sector', $tipo_sector);
        $rs = $this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_sector,a.deno_sector FROM v_balance_ejecucion a WHERE " . $con . " and a.ano=" . $year . "");
        foreach ($rs as $l) {
            $v[] = $l[0]["cod_sector"];
            $d[] = $l[0]["deno_sector"];
        }
        if (isset($v)) {
            $sector = array_combine($v, $d);
        } else {
            $sector = array();
        }
        $this->set("SECTOR", $sector);

//pr($sector);
    }

//tipo sector

    function grafico_formulacion_tipo_partida($var = null) {
        $this->layout = "ajax";
        $this->set("cod_dep", $this->verifica_SS(5));

        $ver = "si";
        $condicion_formulacion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' ';
        $year2 = $this->cfpd01_formulacion->findAll($condicion_formulacion, null, 'ano_formular ASC', null);
        $ano_formulacion = null;
        foreach ($year2 as $year2) {
            $ano = $year2['cfpd01_formulacion']['ano_formular'];
        }
        $ano = $year2['cfpd01_formulacion']['ano_formular'];

        $dato_ano = $ano;
        if (isset($this->data["tipo_presupuestoPDF"]["year"])) {
            $year = $this->data["tipo_presupuestoPDF"]["year"];
        } else {
            $year = $dato_ano;
        }
        if (isset($this->data["tipo_presupuestoPDF"]["opcion"])) {
            $var = $this->data["tipo_presupuestoPDF"]["opcion"];
            $ver = "no";
        } else {
            $var = 1;
        }
        $this->set("year", $year);
        $this->set("ver", $ver);


        if (isset($var) && $this->verifica_SS(5) == 1) {
            if ($var == 1) {//institucion
                $cod_dep = "";
                $cod_dep2 = "";
                $con = $this->SQLCA_report_a($var);
                $gror = "";
                $this->set("opcion", 1);
            } else {
                $cod_dep = "a.cod_dep,";
                $cod_dep2 = "x.cod_dep=a.cod_dep and";
                $con = $this->SQLCA_report_a();
                $gror = ",a.cod_dep";
                $this->set("opcion", 2);
            }
        } else {
            $cod_dep = "a.cod_dep,";
            $cod_dep2 = "x.cod_dep=a.cod_dep and";
            $con = $this->SQLCA_report_a();
            $gror = ",a.cod_dep";
            $this->set("opcion", 2);
        }
        $_SESSION["CONDICIONPDF"] = $con;
        $tipo_partida = $this->cfpd05->execute("SELECT
a.cod_presi,
a.cod_entidad,
a.cod_tipo_inst,
a.cod_inst,
" . $cod_dep . "
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.cod_partida=401 and x.ano=" . $year . ") as partida_401,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.cod_partida=402 and x.ano=" . $year . ") as partida_402,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.cod_partida=403 and x.ano=" . $year . ") as partida_403,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.cod_partida=404 and x.ano=" . $year . ") as partida_404,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.cod_partida=405 and x.ano=" . $year . ") as partida_405,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.cod_partida=406 and x.ano=" . $year . ") as partida_406,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.cod_partida=407 and x.ano=" . $year . ") as partida_407,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.cod_partida=408 and x.ano=" . $year . ") as partida_408,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.cod_partida=409 and x.ano=" . $year . ") as partida_409,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.cod_partida=410 and x.ano=" . $year . ") as partida_410,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.cod_partida=411 and x.ano=" . $year . ") as partida_411,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.cod_partida=412 and x.ano=" . $year . ") as partida_412,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.cod_partida=498 and x.ano=" . $year . ") as partida_498,
(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and " . $cod_dep2 . " x.ano=" . $year . ") as asignacion_total
  FROM
cfpd05 a
WHERE
   	" . $con . " and a.ano=" . $year . "
group by
a.cod_presi,
a.cod_entidad,
a.cod_tipo_inst,
a.cod_inst
" . $gror . "
order by
a.cod_presi,
a.cod_entidad,
a.cod_tipo_inst,
a.cod_inst
" . $gror . ";");

        $this->set('tipo_partida', $tipo_partida);
        $rs = $this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_partida,a.deno_partida FROM v_cfpd05_denominaciones a WHERE " . $con . " and a.ano=" . $year . "");
        foreach ($rs as $l) {
            $v[] = $l[0]["cod_partida"];
            $d[] = $l[0]["deno_partida"];
        }
        if (isset($v)) {
            $PARTIDA = array_combine($v, $d);
        } else {
            $PARTIDA = array();
        }
        $this->set("PARTIDA", $PARTIDA);
    }

//tipo partidas

    function grafico_formulacion_tipo_presupuesto_pdf() {
        $this->layout = "pdf";
        $username = $this->Session->read('nom_usuario');
        $this->set('user', $username);
        $ordinario = $this->data["tipo_presupuetoPDF"]["ordinario"];
        $coordinado = $this->data["tipo_presupuetoPDF"]["coordinado"];
        $laee = $this->data["tipo_presupuetoPDF"]["laee"];
        $fides = $this->data["tipo_presupuetoPDF"]["fides"];
        $ingresos_extra = $this->data["tipo_presupuetoPDF"]["ingresos_extra"];
        $ingresos_propios = $this->data["tipo_presupuetoPDF"]["ingresos_propios"];
        $total_presupuesto = $this->data["tipo_presupuetoPDF"]["total_presupuesto"];
        $por_ordinario = $this->data["tipo_presupuetoPDF"]["por_ordinario"];
        $por_coordinado = $this->data["tipo_presupuetoPDF"]["por_coordinado"];
        $por_laee = $this->data["tipo_presupuetoPDF"]["por_laee"];
        $por_fides = $this->data["tipo_presupuetoPDF"]["por_fides"];
        $por_ingresos_extra = $this->data["tipo_presupuetoPDF"]["por_ingresos_extra"];
        $por_ingresos_propios = $this->data["tipo_presupuetoPDF"]["por_ingresos_propios"];
        $rdm = $this->data["tipo_presupuetoPDF"]["rdm"];
        $year = $this->data["tipo_presupuetoPDF"]["year"];
        $this->set('ordinario', $ordinario);
        $this->set('coordinado', $coordinado);
        $this->set('laee', $laee);
        $this->set('fides', $fides);
        $this->set('ingresos_extra', $ingresos_extra);
        $this->set('ingresos_propios', $ingresos_propios);
        $this->set('por_ordinario', $por_ordinario);
        $this->set('por_coordinado', $por_coordinado);
        $this->set('por_laee', $por_laee);
        $this->set('por_fides', $por_fides);
        $this->set('por_ingresos_extra', $por_ingresos_extra);
        $this->set('por_ingresos_propios', $por_ingresos_propios);
        $this->set('total_presupuesto', $total_presupuesto);
        $this->set('rdm', $rdm);
        $this->set('year', $year);
    }

    function grafico_formulacion_tipo_sector_pdf() {
        $this->layout = "pdf";
        $username = $this->Session->read('nom_usuario');
        $this->set('user', $username);
        $kk = $_SESSION["vector_sectores"];
        $this->set("KK", $kk);
        $year = $this->data["tipo_presupuetoPDF"]["year"];
        for ($i = 0; $i < count($kk); $i++) {
            $sector[$kk[$i]] = $this->data["tipo_presupuetoPDF"]["sector_" . $kk[$i]];
            $por_sector[$kk[$i]] = $this->data["tipo_presupuetoPDF"]["por_sector_" . $kk[$i]];
        }
        $total_presupuesto = $this->data["tipo_presupuetoPDF"]["total_presupuesto_sector"];
        $rdm = $this->data["tipo_presupuetoPDF"]["rdm"];
        $con = $_SESSION["CONDICIONPDF"];
        $rs = $this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_sector,a.deno_sector FROM v_balance_ejecucion a WHERE " . $con);
        foreach ($rs as $l) {
            $v[] = $l[0]["cod_sector"];
            $d[] = $l[0]["deno_sector"];
        }
        if (isset($v)) {
            $SECTOR = array_combine($v, $d);
        } else {
            $SECTOR = array();
        }
        if (!isset($sector)) {
            $sector = array();
        }
        if (!isset($por_sector)) {
            $por_sector = array();
        }
        $this->set("SECTOR", $SECTOR);
        $this->set('sector', $sector);
        $this->set('por_sector', $por_sector);
        $this->set('total_presupuesto', $total_presupuesto);
        $this->set('rdm', $rdm);
        $this->set('year', $year);
    }

    function grafico_formulacion_tipo_partida_pdf() {
        $this->layout = "pdf";
        $username = $this->Session->read('nom_usuario');
        $this->set('user', $username);
        $kk = $_SESSION["vector_partidas"];
        $year = $this->data["tipo_presupuetoPDF"]["year"];
        $this->set("KK", $kk);
        for ($i = 0; $i < count($kk); $i++) {
            $partida[$kk[$i]] = $this->data["tipo_presupuetoPDF"]["partida_" . $kk[$i]];
            $por_partida[$kk[$i]] = $this->data["tipo_presupuetoPDF"]["por_partida_" . $kk[$i]];
        }
        $total_presupuesto = $this->data["tipo_presupuetoPDF"]["total_presupuesto_partida"];
        $rdm = $this->data["tipo_presupuetoPDF"]["rdm"];
        $con = $_SESSION["CONDICIONPDF"];
        $rs = $this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_partida,a.deno_partida FROM v_cfpd05_denominaciones a WHERE " . $con);
        foreach ($rs as $l) {
            $v[] = $l[0]["cod_partida"];
            $d[] = $l[0]["deno_partida"];
        }
        if (isset($v)) {
            $PARTIDA = array_combine($v, $d);
        } else {
            $PARTIDA = array();
        }
        if (!isset($partida)) {
            $partida = array();
        }
        if (!isset($por_partida)) {
            $por_partida = array();
        }
        $this->set("PARTIDA", $PARTIDA);
        $this->set('partida', $partida);
        $this->set('por_partida', $por_partida);
        $this->set('total_presupuesto', $total_presupuesto);
        $this->set('rdm', $rdm);
        $this->set('year', $year);
    }

    function cargos_de_la_institucion_ordenando_recursos_humanos_cfpd97($var1 = null, $var2 = null) {




        $tabla = "";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');

        set_time_limit(0);

        $tabla = "cfpd97";
        $vista = "v_cfpd97";



        if ($var2 == null) {
            $this->layout = "ajax";
            $opcion = 1;
            $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst;
            $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
            $dato = null;
            foreach ($year as $year) {
                $dato_year = $year['cfpd01_formulacion']['ano_formular'];
            }
            $year_vista = $dato_year;
            $this->set('year', $year_vista);

            //$Lista = $this->v_cnmd05->generateList($this->SQLCA(), 'cod_tipo_nomina ASC', null, '{n}.v_cnmd05.cod_tipo_nomina', '{n}.v_cnmd05.tipo_nomina');
            //$this->concatena($Lista, 'cod_tipo_nomina');

            $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
            $this->concatenaN($lista, 'nomina');
        } else {
            $this->layout = "pdf";
            $opcion = 2;

            if (!empty($this->data['reporte3']['consolidacion'])) {
                $consolidacion = $this->data['reporte3']['consolidacion'];
            } else {
                $consolidacion = 2;
            }
            if (!empty($this->data['reporte3']['year'])) {
                $year = $this->data['reporte3']['year'];
            } else {
                $year = $this->ano_ejecucion();
            }
            if (!empty($this->data['reporte3']['tipo'])) {
                $tipo = $this->data['reporte3']['tipo'];
            } else {
                $tipo = 1;
            }
            if (!empty($this->data['reporte3']['frecuencia'])) {
                $frecuencia = $this->data['reporte3']['frecuencia'];
            } else {
                $frecuencia = 1;
            }

            $sql_a = "";

            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_dirsuperior'])) {
                $sql_a .= "a.cod_dir_superior  = " . $this->data['cscp02_solicitud_cotizacion']['cod_dirsuperior'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_coordinacion'])) {
                $sql_a .= "a.cod_coordinacion  = " . $this->data['cscp02_solicitud_cotizacion']['cod_coordinacion'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_secretaria'])) {
                $sql_a .= "a.cod_secretaria  = " . $this->data['cscp02_solicitud_cotizacion']['cod_secretaria'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_direccion'])) {
                $sql_a .= "a.cod_direccion  = " . $this->data['cscp02_solicitud_cotizacion']['cod_direccion'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_division'])) {
                $sql_a .= "a.cod_division  = " . $this->data['cscp02_solicitud_cotizacion']['cod_division'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_departamento'])) {
                $sql_a .= "a.cod_departamento  = " . $this->data['cscp02_solicitud_cotizacion']['cod_departamento'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_oficina'])) {
                $sql_a .= "a.cod_oficina  = " . $this->data['cscp02_solicitud_cotizacion']['cod_oficina'] . "  and ";
            }





            $tipo_nomina = "a.cod_tipo_nomina  = " . $tipo . "  and ";


            if (isset($this->data['cfpp05']['consolidacion'])) {
                if ($this->data['cfpp05']['consolidacion'] == 2) {
                    $dep_A = " a.cod_dep   =  " . $_SESSION['cod_dep_reporte_consolidado'] . "  and";
                    $dep_B = " a.cod_dep, ";
                    $titulo_a = $_SESSION["dependencia_reporte_consolidado"];
                } else if ($this->data['cfpp05']['consolidacion'] == 1) {
                    $dep_A = "";
                    $dep_B = "";
                    $titulo_a = $this->Session->read('dependencia');
                }
            } else {

                $dep_A = "";
                $dep_B = "";
                $titulo_a = $this->Session->read('dependencia');
            }

            $this->set('titulo_a', $titulo_a);

            $rs = $this->v_cfpd05_denominaciones->execute("

		         SELECT
			              a.cod_presi,
						  a.cod_entidad,
						  a.cod_tipo_inst,
						  a.cod_inst,
						  a.cod_dep,
						  a.cod_tipo_nomina,
						  a.cod_cargo,
						  a.cod_puesto,
						  a.sueldo_basico,
						  a.compensaciones,
						  a.primas,
						  a.bonos,
						  a.cod_dir_superior,
						  a.cod_coordinacion,
						  a.cod_secretaria,
						  a.cod_direccion,
						  a.cod_division,
						  a.cod_departamento,
						  a.cod_oficina,
						 (SELECT xa.denominacion FROM cugd02_direccionsuperior xa where xa.cod_dependencia=a.cod_dep and xa.cod_dir_superior=a.cod_dir_superior                                                                                                                                                                                                                                        GROUP BY xa.denominacion) as  deno_cod_dir_superior,
						 (SELECT xb.denominacion FROM cugd02_coordinacion xb      where xb.cod_dependencia=a.cod_dep and xb.cod_dir_superior=a.cod_dir_superior and xb.cod_coordinacion=a.cod_coordinacion                                                                                                                                                                                             GROUP BY xb.denominacion) as  deno_cod_coordinacion,
						 (SELECT xc.denominacion FROM cugd02_secretaria xc        where xc.cod_dependencia=a.cod_dep and xc.cod_dir_superior=a.cod_dir_superior and xc.cod_coordinacion=a.cod_coordinacion and xc.cod_secretaria=a.cod_secretaria                                                                                                                                                      GROUP BY xc.denominacion) as  deno_cod_secretaria,
						 (SELECT xd.denominacion FROM cugd02_direccion xd         where xd.cod_dependencia=a.cod_dep and xd.cod_dir_superior=a.cod_dir_superior and xd.cod_coordinacion=a.cod_coordinacion and xd.cod_secretaria=a.cod_secretaria and xd.cod_direccion=a.cod_direccion                                                                                                                 GROUP BY xd.denominacion) as  deno_cod_direccion,
						 (SELECT xe.denominacion FROM cugd02_division xe          where xe.cod_dependencia=a.cod_dep and xe.cod_dir_superior=a.cod_dir_superior and xe.cod_coordinacion=a.cod_coordinacion and xe.cod_secretaria=a.cod_secretaria and xe.cod_direccion=a.cod_direccion  and xe.cod_division=a.cod_division                                                                             GROUP BY xe.denominacion) as  deno_cod_division,
						 (SELECT xf.denominacion FROM cugd02_departamento xf      where xf.cod_dependencia=a.cod_dep and xf.cod_dir_superior=a.cod_dir_superior and xf.cod_coordinacion=a.cod_coordinacion and xf.cod_secretaria=a.cod_secretaria and xf.cod_direccion=a.cod_direccion  and xf.cod_division=a.cod_division and xf.cod_departamento=a.cod_departamento                                  GROUP BY xf.denominacion) as  deno_cod_departamento,
						 (SELECT xg.denominacion FROM cugd02_oficina xg           where xg.cod_dependencia=a.cod_dep and xg.cod_dir_superior=a.cod_dir_superior and xg.cod_coordinacion=a.cod_coordinacion and xg.cod_secretaria=a.cod_secretaria and xg.cod_direccion=a.cod_direccion  and xg.cod_division=a.cod_division and xg.cod_departamento=a.cod_departamento and xg.cod_oficina=a.cod_oficina GROUP BY xg.denominacion) as  deno_cod_oficina,
                          a.cod_estado,
						  a.cod_municipio,
						  a.cod_parroquia,
						  a.cod_centro,
						  a.condicion_actividad,
						  a.ano,
						  a.cod_sector,
						(select
							z.deno_sector
							from v_cfpd05_denominaciones z
							where
							z.cod_presi=a.cod_presi and
							z.cod_entidad=a.cod_entidad and
							z.cod_tipo_inst=a.cod_tipo_inst and
							z.cod_inst=a.cod_inst and
							z.cod_dep=a.cod_dep and
							z.ano=a.ano and
							z.cod_sector=a.cod_sector limit 1)
						as deno_sector,
						a.cod_programa,
						(select
							y.deno_programa
							from v_cfpd05_denominaciones y
							where
							y.cod_presi=a.cod_presi and
							y.cod_entidad=a.cod_entidad and
							y.cod_tipo_inst=a.cod_tipo_inst and
							y.cod_inst=a.cod_inst and
							y.cod_dep=a.cod_dep and
							y.ano=a.ano and
							y.cod_sector=a.cod_sector and
							y.cod_programa=a.cod_programa limit 1)
						as deno_programa,
						a.cod_sub_prog,
						(select
							x.deno_sub_prog
							from v_cfpd05_denominaciones x
							where
							x.cod_presi=a.cod_presi and
							x.cod_entidad=a.cod_entidad and
							x.cod_tipo_inst=a.cod_tipo_inst and
							x.cod_inst=a.cod_inst and
							x.cod_dep=a.cod_dep and
							x.ano=a.ano and
							x.cod_sector=a.cod_sector and
							x.cod_programa=a.cod_programa and
							x.cod_sub_prog=a.cod_sub_prog limit 1)
						as deno_sub_prog,
						a.cod_proyecto,
						(select
							w.deno_proyecto
							from v_cfpd05_denominaciones w
							where
							w.cod_presi=a.cod_presi and
							w.cod_entidad=a.cod_entidad and
							w.cod_tipo_inst=a.cod_tipo_inst and
							w.cod_inst=a.cod_inst and
							w.cod_dep=a.cod_dep and
							w.ano=a.ano and
							w.cod_sector=a.cod_sector and
							w.cod_programa=a.cod_programa and
							w.cod_sub_prog=a.cod_sub_prog and
							w.cod_proyecto=a.cod_proyecto limit 1)
						as deno_proyecto,
						a.cod_activ_obra,
						(select
							u.deno_activ_obra
							from v_cfpd05_denominaciones u
							where
							u.cod_presi=a.cod_presi and
							u.cod_entidad=a.cod_entidad and
							u.cod_tipo_inst=a.cod_tipo_inst and
							u.cod_inst=a.cod_inst and
							u.cod_dep=a.cod_dep and
							u.ano=a.ano and
							u.cod_sector=a.cod_sector and
							u.cod_programa=a.cod_programa and
							u.cod_sub_prog=a.cod_sub_prog and
							u.cod_proyecto=a.cod_proyecto and u.cod_activ_obra=a.cod_activ_obra limit 1)
						as deno_activ_obra,
						a.cod_partida,
						(select v.deno_partida
							from v_cfpd05_denominaciones v
							where
							v.cod_presi=a.cod_presi and
							v.cod_entidad=a.cod_entidad and
							v.cod_tipo_inst=a.cod_tipo_inst and
							v.cod_inst=a.cod_inst and
							v.cod_dep=a.cod_dep and
							v.ano=a.ano and
							v.cod_sector=a.cod_sector and
							v.cod_programa=a.cod_programa and
							v.cod_sub_prog=a.cod_sub_prog and
							v.cod_proyecto=a.cod_proyecto and
							v.cod_activ_obra=a.cod_activ_obra and
							v.cod_partida=a.cod_partida limit 1)
						as deno_partida,
						a.cod_generica,
						(select t.deno_generica
							from v_cfpd05_denominaciones t
							where
							t.cod_presi=a.cod_presi and
							t.cod_entidad=a.cod_entidad and
							t.cod_tipo_inst=a.cod_tipo_inst and
							t.cod_inst=a.cod_inst and
							t.cod_dep=a.cod_dep and
							t.ano=a.ano and
							t.cod_sector=a.cod_sector and
							t.cod_programa=a.cod_programa and
							t.cod_sub_prog=a.cod_sub_prog and
							t.cod_proyecto=a.cod_proyecto and
							t.cod_activ_obra=a.cod_activ_obra and
							t.cod_partida=a.cod_partida and
							t.cod_partida=a.cod_partida and
							t.cod_generica=a.cod_generica limit 1)
						as deno_generica,
						a.cod_especifica,
						(select s.deno_especifica
							from v_cfpd05_denominaciones s
							where
							s.cod_presi=a.cod_presi and
							s.cod_entidad=a.cod_entidad and
							s.cod_tipo_inst=a.cod_tipo_inst and
							s.cod_inst=a.cod_inst and
							s.cod_dep=a.cod_dep and
							s.ano=a.ano and
							s.cod_sector=a.cod_sector and
							s.cod_programa=a.cod_programa and
							s.cod_sub_prog=a.cod_sub_prog and
							s.cod_proyecto=a.cod_proyecto and
							s.cod_activ_obra=a.cod_activ_obra and
							s.cod_partida=a.cod_partida and
							s.cod_partida=a.cod_partida and
							s.cod_generica=a.cod_generica and
							s.cod_especifica=a.cod_especifica limit 1)
						as deno_especifica,
						a.cod_sub_espec,
						(select r.deno_sub_espec
							from v_cfpd05_denominaciones r
							where
							r.cod_presi=a.cod_presi and
							r.cod_entidad=a.cod_entidad and
							r.cod_tipo_inst=a.cod_tipo_inst and
							r.cod_inst=a.cod_inst and
							r.cod_dep=a.cod_dep and
							r.ano=a.ano and
							r.cod_sector=a.cod_sector and
							r.cod_programa=a.cod_programa and
							r.cod_sub_prog=a.cod_sub_prog and
							r.cod_proyecto=a.cod_proyecto and
							r.cod_activ_obra=a.cod_activ_obra and
							r.cod_partida=a.cod_partida and
							r.cod_partida=a.cod_partida and
							r.cod_generica=a.cod_generica and
							r.cod_especifica=a.cod_especifica and
							r.cod_sub_espec=a.cod_sub_espec limit 1)
						as deno_sub_espe,
						a.cod_auxiliar,
						(select o.deno_auxiliar
							from v_cfpd05_denominaciones o
							where
							o.cod_presi=a.cod_presi and
							o.cod_entidad=a.cod_entidad and
							o.cod_tipo_inst=a.cod_tipo_inst and
							o.cod_inst=a.cod_inst and
							o.cod_dep=a.cod_dep and
							o.ano=a.ano and
							o.cod_sector=a.cod_sector and
							o.cod_programa=a.cod_programa and
							o.cod_sub_prog=a.cod_sub_prog and
							o.cod_proyecto=a.cod_proyecto and
							o.cod_activ_obra=a.cod_activ_obra and
							o.cod_partida=a.cod_partida and
							o.cod_partida=a.cod_partida and
							o.cod_generica=a.cod_generica and
							o.cod_especifica=a.cod_especifica and
							o.cod_sub_espec=a.cod_sub_espec and
							o.cod_auxiliar=a.cod_auxiliar limit 1)
						as deno_auxiliar,
						  a.cod_nivel_i,
						  a.cod_nivel_ii,
						  (SELECT a_a.denominacion FROM cnmd04_tipo      a_a WHERE a_a.cod_nivel_i = a.cod_nivel_i                                       ) as deno_cod_nivel_i,
						  (SELECT a_b.denominacion FROM cnmd04_ocupacion a_b WHERE a_b.cod_nivel_i = a.cod_nivel_i and a_b.cod_nivel_ii = a.cod_nivel_ii ) as deno_cod_nivel_ii,
						  a.cod_ficha,
						  b.denominacion as denominacion_nomina,
						  b.denominacion_devengado,
						  b.clasificacion_personal,
						  b.frecuencia_cobro,
						  b.dias_laborables,
						  b.horas_laborables,
						  b.descuentos_ley,
						  b.mensajes_colectivos,
						  b.status_nomina,
						  b.cantidad_pagos,
						  b.correspondiente,
						  b.frecuencia_pago,
						  b.numero_recibo,
						  b.control_autorizacion,
						  b.autorizacion_diskettes,
						  b.sueldo_sugerido,
						  b.ultimo_cargo,
						  b.ultima_ficha,
						  b.ano_desde,
						  b.ano_hasta,
						  b.codigo_transaccion,
						  b.dias_cobro,
						  c.denominacion_clase as denominacion_cargo,
						  (SELECT denominacion FROM cugd02_dependencias b WHERE b.cod_tipo_institucion = a.cod_tipo_inst and b.cod_institucion  =  a.cod_inst and b.cod_dependencia   =  a.cod_dep) as denominacion_dep,
						  (SELECT denominacion FROM cnmd04_ocupacion bb WHERE bb.cod_nivel_i = a.cod_nivel_i and bb.cod_nivel_ii  =  a.cod_nivel_ii ) as denominacion_clasificacion,
						  (SELECT d.cedula_identidad FROM cnmd06_fichas d where
						   d.cod_presi            =  a.cod_presi                and
						   d.cod_entidad          =  a.cod_entidad              and
						   d.cod_tipo_inst        =  a.cod_tipo_inst            and
						   d.cod_inst             =  a.cod_inst                 and
						   d.cod_dep              =  a.cod_dep                  and
						   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
						   d.cod_cargo            =  a.cod_cargo                and
						   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  as  cedula_identidad_ficha,


						   (SELECT e.primer_apellido  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
																	   d.cod_presi            =  a.cod_presi                and
																	   d.cod_entidad          =  a.cod_entidad              and
																	   d.cod_tipo_inst        =  a.cod_tipo_inst            and
																	   d.cod_inst             =  a.cod_inst                 and
																	   d.cod_dep              =  a.cod_dep                  and
																	   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
																	   d.cod_cargo            =  a.cod_cargo                and
																	   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.primer_apellido)
	                      as  primer_apellido,

	                      (SELECT e.segundo_apellido  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
																	   d.cod_presi            =  a.cod_presi                and
																	   d.cod_entidad          =  a.cod_entidad              and
																	   d.cod_tipo_inst        =  a.cod_tipo_inst            and
																	   d.cod_inst             =  a.cod_inst                 and
																	   d.cod_dep              =  a.cod_dep                  and
																	   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
																	   d.cod_cargo            =  a.cod_cargo                and
																	   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.segundo_apellido)
	                      as  segundo_apellido,

                          (SELECT e.primer_nombre  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
																	   d.cod_presi            =  a.cod_presi                and
																	   d.cod_entidad          =  a.cod_entidad              and
																	   d.cod_tipo_inst        =  a.cod_tipo_inst            and
																	   d.cod_inst             =  a.cod_inst                 and
																	   d.cod_dep              =  a.cod_dep                  and
																	   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
																	   d.cod_cargo            =  a.cod_cargo                and
																	   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.primer_nombre)
	                      as  primer_nombre,


	                      (SELECT e.segundo_nombre  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
																	   d.cod_presi            =  a.cod_presi                and
																	   d.cod_entidad          =  a.cod_entidad              and
																	   d.cod_tipo_inst        =  a.cod_tipo_inst            and
																	   d.cod_inst             =  a.cod_inst                 and
																	   d.cod_dep              =  a.cod_dep                  and
																	   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
																	   d.cod_cargo            =  a.cod_cargo                and
																	   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.segundo_nombre)
	                      as  segundo_nombre


                 FROM

                          " . $tabla . " a, cnmd01 b, " . $vista . " c


                 WHERE

                           a.cod_presi            =  " . $cod_presi . "             and
						   a.cod_entidad          =  " . $cod_entidad . "           and
						   a.cod_tipo_inst        =  " . $cod_tipo_inst . "         and
						   a.cod_inst             =  " . $cod_inst . "              and
						   " . $dep_A . "
						   a.ano                     =  " . $year . "               and
						   " . $tipo_nomina . "
						   " . $sql_a . "
						   b.cod_presi            =  a.cod_presi                and
						   b.cod_entidad          =  a.cod_entidad              and
						   b.cod_tipo_inst        =  a.cod_tipo_inst            and
						   b.cod_inst             =  a.cod_inst                 and
						   b.cod_dep              =  a.cod_dep                  and
						   b.cod_tipo_nomina      =  a.cod_tipo_nomina          and
						   c.cod_presi            =  a.cod_presi                and
						   c.cod_entidad          =  a.cod_entidad              and
						   c.cod_tipo_inst        =  a.cod_tipo_inst            and
						   c.cod_inst             =  a.cod_inst                 and
						   c.cod_dep              =  a.cod_dep                  and
						   c.cod_tipo_nomina      =  a.cod_tipo_nomina          and
						   c.cod_cargo            =  a.cod_cargo                and
                           c.cod_puesto           =  a.cod_puesto


                 ORDER BY
                            a.cod_presi,
                            a.cod_entidad,
                            a.cod_tipo_inst,
                            a.cod_inst,
                            a.cod_dep,
                            a.cod_tipo_nomina,
                            a.cod_nivel_i,
						    a.cod_nivel_ii,
                            a.cod_cargo


                            ASC

             ; ");

            $this->set('tipo', $tipo);
            $this->set('datos', $rs);
            $this->set('presentar_como', $frecuencia);
        }//fin$tipo


        $this->set('opcion', $opcion);
        $this->set('var1', $var1);
    }

//fin funtion

    function listado_expediente($var1 = null, $var2 = null) {

        $tabla = "";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');

        set_time_limit(0);

        $tabla = "cnmd05";
        $vista = "v_cnmd05";

        if ($var2 == null) {
            $this->layout = "ajax";
            $opcion = 1;
            $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst;
            $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
            $dato = null;
            foreach ($year as $year) {
                $dato_year = $year['cfpd01_formulacion']['ano_formular'];
            }
            if ($var1 == 1) {
                $year_vista = $dato_year;
            } else {
                $year_vista = $this->ano_ejecucion();
            }
            $this->set('year', $year_vista);
            $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
            $this->concatenaN($lista, 'nomina');
        } else {
            $this->layout = "pdf";
            $opcion = 2;

            if (!empty($this->data['reporte3']['consolidacion'])) {
                $consolidacion = $this->data['reporte3']['consolidacion'];
            } else {
                $consolidacion = 2;
            }
            if (!empty($this->data['reporte3']['year'])) {
                $year = $this->data['reporte3']['year'];
            } else {
                $year = $this->ano_ejecucion();
            }
            if (!empty($this->data['reporte3']['tipo'])) {
                $tipo = $this->data['reporte3']['tipo'];
            } else {
                $tipo = 1;
            }
            if (!empty($this->data['reporte3']['frecuencia'])) {
                $frecuencia = $this->data['reporte3']['frecuencia'];
            } else {
                $frecuencia = 1;
            }
            //echo $this->data['reporte3']['radio_ordenamiento_codigo'];

            $sql_a = "";
            $sql_b = "";


            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_dirsuperior'])) {
                $sql_a .= "a.cod_dir_superior  = " . $this->data['cscp02_solicitud_cotizacion']['cod_dirsuperior'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_coordinacion'])) {
                $sql_a .= "a.cod_coordinacion  = " . $this->data['cscp02_solicitud_cotizacion']['cod_coordinacion'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_secretaria'])) {
                $sql_a .= "a.cod_secretaria  = " . $this->data['cscp02_solicitud_cotizacion']['cod_secretaria'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_direccion'])) {
                $sql_a .= "a.cod_direccion  = " . $this->data['cscp02_solicitud_cotizacion']['cod_direccion'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_division'])) {
                $sql_a .= "a.cod_division  = " . $this->data['cscp02_solicitud_cotizacion']['cod_division'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_departamento'])) {
                $sql_a .= "a.cod_departamento  = " . $this->data['cscp02_solicitud_cotizacion']['cod_departamento'] . "  and ";
            }
            if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_oficina'])) {
                $sql_a .= "a.cod_oficina  = " . $this->data['cscp02_solicitud_cotizacion']['cod_oficina'] . "  and ";
            }


            if (!empty($this->data['cobp01_contratoobras']['cod_estado'])) {
                $sql_b .= "a.cod_estado  = " . $this->data['cobp01_contratoobras']['cod_estado'] . "  and ";
            }
            if (!empty($this->data['cobp01_contratoobras']['cod_municipio'])) {
                $sql_b .= "a.cod_municipio  = " . $this->data['cobp01_contratoobras']['cod_municipio'] . "  and ";
            }
            if (!empty($this->data['cobp01_contratoobras']['cod_parroquia'])) {
                $sql_b .= "a.cod_parroquia  = " . $this->data['cobp01_contratoobras']['cod_parroquia'] . "  and ";
            }
            if (!empty($this->data['cobp01_contratoobras']['cod_centro'])) {
                $sql_b .= "a.cod_centro  = " . $this->data['cobp01_contratoobras']['cod_centro'] . "  and ";
            }

            $tipo_nomina = "a.cod_tipo_nomina  = " . $tipo . "  and ";

            if ($consolidacion == 1) {
                $dep_A = "";
                $dep_B = "";
            } else if ($consolidacion == 2) {
                $dep_A = " a.cod_dep   =  " . $cod_dep . "  and";
                $dep_B = " a.cod_dep, ";
            }//fin function



            $radio_geograf = $this->data['reporte3']['radio_ubicacion_geografica'];
            $radio_admin = $this->data['reporte3']['radio_ubicacion_administrativa'];
            $order_by = "";
            if ($radio_geograf == 1 && $radio_admin == 1) {
                $order_by .= " a.cod_cargo, a.cod_ficha";
            } elseif ($radio_geograf == 2 && $radio_admin == 2) {
                $order_by .= " a.cod_estado,
							a.cod_municipio,
						    a.cod_parroquia,
						    a.cod_centro,
							a.cod_dir_superior,
						  	a.cod_coordinacion,
						  	a.cod_secretaria,
						  	a.cod_direccion,
						  	a.cod_division,
						  	a.cod_departamento,
						  	a.cod_oficina,
						  	a.cod_cargo,
						  	a.cod_ficha
							";
            } elseif ($radio_geograf == 2) {
                $order_by .= " a.cod_estado,
							a.cod_municipio,
						    a.cod_parroquia,
						    a.cod_centro,
							a.cod_dir_superior,
						  	a.cod_coordinacion,
						  	a.cod_secretaria,
						  	a.cod_direccion,
						  	a.cod_division,
						  	a.cod_departamento,
						  	a.cod_oficina,
						  	a.cod_cargo,
						  	a.cod_ficha
							";
            } elseif ($radio_admin == 2) {
                $order_by .= " a.cod_dir_superior,
						  	a.cod_coordinacion,
						  	a.cod_secretaria,
						  	a.cod_direccion,
						  	a.cod_division,
						  	a.cod_departamento,
						  	a.cod_oficina,
						  	a.cod_cargo,
						  	a.cod_ficha
							";
            }

            $rs = $this->v_cfpd05_denominaciones->execute("

		         SELECT
			              a.cod_presi,
						  a.cod_entidad,
						  a.cod_tipo_inst,
						  a.cod_inst,
						  a.cod_dep,
						  a.cod_tipo_nomina,
						  a.cod_cargo,
						  a.cod_puesto,
						  a.sueldo_basico,
						  a.compensaciones,
						  a.primas,
						  a.bonos,
						  a.cod_dir_superior,
						  a.cod_coordinacion,
						  a.cod_secretaria,
						  a.cod_direccion,
						  a.cod_division,
						  a.cod_departamento,
						  a.cod_oficina,
						 (SELECT xa.denominacion FROM cugd02_direccionsuperior xa where xa.cod_tipo_institucion=a.cod_tipo_inst  and xa.cod_institucion=a.cod_inst and xa.cod_dependencia=a.cod_dep and xa.cod_dir_superior=a.cod_dir_superior                                                                                                                                                                                                                                        GROUP BY xa.denominacion) as  deno_cod_dir_superior,
						 (SELECT xb.denominacion FROM cugd02_coordinacion xb      where xb.cod_tipo_institucion=a.cod_tipo_inst  and xb.cod_institucion=a.cod_inst and xb.cod_dependencia=a.cod_dep and xb.cod_dir_superior=a.cod_dir_superior and xb.cod_coordinacion=a.cod_coordinacion                                                                                                                                                                                             GROUP BY xb.denominacion) as  deno_cod_coordinacion,
						 (SELECT xc.denominacion FROM cugd02_secretaria xc        where xc.cod_tipo_institucion=a.cod_tipo_inst  and xc.cod_institucion=a.cod_inst and xc.cod_dependencia=a.cod_dep and xc.cod_dir_superior=a.cod_dir_superior and xc.cod_coordinacion=a.cod_coordinacion and xc.cod_secretaria=a.cod_secretaria                                                                                                                                                      GROUP BY xc.denominacion) as  deno_cod_secretaria,
						 (SELECT xd.denominacion FROM cugd02_direccion xd         where xd.cod_tipo_institucion=a.cod_tipo_inst  and xd.cod_institucion=a.cod_inst and xd.cod_dependencia=a.cod_dep and xd.cod_dir_superior=a.cod_dir_superior and xd.cod_coordinacion=a.cod_coordinacion and xd.cod_secretaria=a.cod_secretaria and xd.cod_direccion=a.cod_direccion                                                                                                                 GROUP BY xd.denominacion) as  deno_cod_direccion,
						 (SELECT xe.denominacion FROM cugd02_division xe          where xe.cod_tipo_institucion=a.cod_tipo_inst  and xe.cod_institucion=a.cod_inst and xe.cod_dependencia=a.cod_dep and xe.cod_dir_superior=a.cod_dir_superior and xe.cod_coordinacion=a.cod_coordinacion and xe.cod_secretaria=a.cod_secretaria and xe.cod_direccion=a.cod_direccion  and xe.cod_division=a.cod_division                                                                             GROUP BY xe.denominacion) as  deno_cod_division,
						 (SELECT xf.denominacion FROM cugd02_departamento xf      where xf.cod_tipo_institucion=a.cod_tipo_inst  and xf.cod_institucion=a.cod_inst and xf.cod_dependencia=a.cod_dep and xf.cod_dir_superior=a.cod_dir_superior and xf.cod_coordinacion=a.cod_coordinacion and xf.cod_secretaria=a.cod_secretaria and xf.cod_direccion=a.cod_direccion  and xf.cod_division=a.cod_division and xf.cod_departamento=a.cod_departamento                                  GROUP BY xf.denominacion) as  deno_cod_departamento,
						 (SELECT xg.denominacion FROM cugd02_oficina xg           where xg.cod_tipo_institucion=a.cod_tipo_inst  and xg.cod_institucion=a.cod_inst and xg.cod_dependencia=a.cod_dep and xg.cod_dir_superior=a.cod_dir_superior and xg.cod_coordinacion=a.cod_coordinacion and xg.cod_secretaria=a.cod_secretaria and xg.cod_direccion=a.cod_direccion  and xg.cod_division=a.cod_division and xg.cod_departamento=a.cod_departamento and xg.cod_oficina=a.cod_oficina GROUP BY xg.denominacion) as  deno_cod_oficina,
                          a.cod_estado,
						  a.cod_municipio,
						  a.cod_parroquia,
						  a.cod_centro,
						 (SELECT xya.denominacion FROM cugd01_estados          xya where xya.cod_republica=a.cod_presi and xya.cod_estado=a.cod_estado                                                                                                                  GROUP BY xya.denominacion) as  deno_cod_estado,
						 (SELECT xyb.denominacion FROM cugd01_municipios       xyb where xyb.cod_republica=a.cod_presi and xyb.cod_estado=a.cod_estado  and xyb.cod_municipio=a.cod_municipio                                                                           GROUP BY xyb.denominacion) as  deno_cod_municipio,
						 (SELECT xyc.denominacion FROM cugd01_parroquias       xyc where xyc.cod_republica=a.cod_presi and xyc.cod_estado=a.cod_estado  and xyc.cod_municipio=a.cod_municipio and xyc.cod_parroquia = a.cod_parroquia                                   GROUP BY xyc.denominacion) as  deno_cod_parroquia,
						 (SELECT xyd.denominacion FROM cugd01_centros_poblados xyd where xyd.cod_republica=a.cod_presi and xyd.cod_estado=a.cod_estado  and xyd.cod_municipio=a.cod_municipio and xyd.cod_parroquia = a.cod_parroquia and xyd.cod_centro = a.cod_centro GROUP BY xyd.denominacion) as  deno_cod_centro,
						  a.condicion_actividad,
						  a.ano,
						  a.cod_sector,
						(select
							z.deno_sector
							from v_cfpd05_denominaciones z
							where
							z.cod_presi=a.cod_presi and
							z.cod_entidad=a.cod_entidad and
							z.cod_tipo_inst=a.cod_tipo_inst and
							z.cod_inst=a.cod_inst and
							z.cod_dep=a.cod_dep and
							z.ano=a.ano and
							z.cod_sector=a.cod_sector limit 1)
						as deno_sector,
						a.cod_programa,
						(select
							y.deno_programa
							from v_cfpd05_denominaciones y
							where
							y.cod_presi=a.cod_presi and
							y.cod_entidad=a.cod_entidad and
							y.cod_tipo_inst=a.cod_tipo_inst and
							y.cod_inst=a.cod_inst and
							y.cod_dep=a.cod_dep and
							y.ano=a.ano and
							y.cod_sector=a.cod_sector and
							y.cod_programa=a.cod_programa limit 1)
						as deno_programa,
						a.cod_sub_prog,
						(select
							x.deno_sub_prog
							from v_cfpd05_denominaciones x
							where
							x.cod_presi=a.cod_presi and
							x.cod_entidad=a.cod_entidad and
							x.cod_tipo_inst=a.cod_tipo_inst and
							x.cod_inst=a.cod_inst and
							x.cod_dep=a.cod_dep and
							x.ano=a.ano and
							x.cod_sector=a.cod_sector and
							x.cod_programa=a.cod_programa and
							x.cod_sub_prog=a.cod_sub_prog limit 1)
						as deno_sub_prog,
						a.cod_proyecto,
						(select
							w.deno_proyecto
							from v_cfpd05_denominaciones w
							where
							w.cod_presi=a.cod_presi and
							w.cod_entidad=a.cod_entidad and
							w.cod_tipo_inst=a.cod_tipo_inst and
							w.cod_inst=a.cod_inst and
							w.cod_dep=a.cod_dep and
							w.ano=a.ano and
							w.cod_sector=a.cod_sector and
							w.cod_programa=a.cod_programa and
							w.cod_sub_prog=a.cod_sub_prog and
							w.cod_proyecto=a.cod_proyecto limit 1)
						as deno_proyecto,
						a.cod_activ_obra,
						(select
							u.deno_activ_obra
							from v_cfpd05_denominaciones u
							where
							u.cod_presi=a.cod_presi and
							u.cod_entidad=a.cod_entidad and
							u.cod_tipo_inst=a.cod_tipo_inst and
							u.cod_inst=a.cod_inst and
							u.cod_dep=a.cod_dep and
							u.ano=a.ano and
							u.cod_sector=a.cod_sector and
							u.cod_programa=a.cod_programa and
							u.cod_sub_prog=a.cod_sub_prog and
							u.cod_proyecto=a.cod_proyecto and u.cod_activ_obra=a.cod_activ_obra limit 1)
						as deno_activ_obra,
						a.cod_partida,
						(select v.deno_partida
							from v_cfpd05_denominaciones v
							where
							v.cod_presi=a.cod_presi and
							v.cod_entidad=a.cod_entidad and
							v.cod_tipo_inst=a.cod_tipo_inst and
							v.cod_inst=a.cod_inst and
							v.cod_dep=a.cod_dep and
							v.ano=a.ano and
							v.cod_sector=a.cod_sector and
							v.cod_programa=a.cod_programa and
							v.cod_sub_prog=a.cod_sub_prog and
							v.cod_proyecto=a.cod_proyecto and
							v.cod_activ_obra=a.cod_activ_obra and
							v.cod_partida=a.cod_partida limit 1)
						as deno_partida,
						a.cod_generica,
						(select t.deno_generica
							from v_cfpd05_denominaciones t
							where
							t.cod_presi=a.cod_presi and
							t.cod_entidad=a.cod_entidad and
							t.cod_tipo_inst=a.cod_tipo_inst and
							t.cod_inst=a.cod_inst and
							t.cod_dep=a.cod_dep and
							t.ano=a.ano and
							t.cod_sector=a.cod_sector and
							t.cod_programa=a.cod_programa and
							t.cod_sub_prog=a.cod_sub_prog and
							t.cod_proyecto=a.cod_proyecto and
							t.cod_activ_obra=a.cod_activ_obra and
							t.cod_partida=a.cod_partida and
							t.cod_partida=a.cod_partida and
							t.cod_generica=a.cod_generica limit 1)
						as deno_generica,
						a.cod_especifica,
						(select s.deno_especifica
							from v_cfpd05_denominaciones s
							where
							s.cod_presi=a.cod_presi and
							s.cod_entidad=a.cod_entidad and
							s.cod_tipo_inst=a.cod_tipo_inst and
							s.cod_inst=a.cod_inst and
							s.cod_dep=a.cod_dep and
							s.ano=a.ano and
							s.cod_sector=a.cod_sector and
							s.cod_programa=a.cod_programa and
							s.cod_sub_prog=a.cod_sub_prog and
							s.cod_proyecto=a.cod_proyecto and
							s.cod_activ_obra=a.cod_activ_obra and
							s.cod_partida=a.cod_partida and
							s.cod_partida=a.cod_partida and
							s.cod_generica=a.cod_generica and
							s.cod_especifica=a.cod_especifica limit 1)
						as deno_especifica,
						a.cod_sub_espec,
						(select r.deno_sub_espec
							from v_cfpd05_denominaciones r
							where
							r.cod_presi=a.cod_presi and
							r.cod_entidad=a.cod_entidad and
							r.cod_tipo_inst=a.cod_tipo_inst and
							r.cod_inst=a.cod_inst and
							r.cod_dep=a.cod_dep and
							r.ano=a.ano and
							r.cod_sector=a.cod_sector and
							r.cod_programa=a.cod_programa and
							r.cod_sub_prog=a.cod_sub_prog and
							r.cod_proyecto=a.cod_proyecto and
							r.cod_activ_obra=a.cod_activ_obra and
							r.cod_partida=a.cod_partida and
							r.cod_partida=a.cod_partida and
							r.cod_generica=a.cod_generica and
							r.cod_especifica=a.cod_especifica and
							r.cod_sub_espec=a.cod_sub_espec limit 1)
						as deno_sub_espe,
						a.cod_auxiliar,
						(select o.deno_auxiliar
							from v_cfpd05_denominaciones o
							where
							o.cod_presi=a.cod_presi and
							o.cod_entidad=a.cod_entidad and
							o.cod_tipo_inst=a.cod_tipo_inst and
							o.cod_inst=a.cod_inst and
							o.cod_dep=a.cod_dep and
							o.ano=a.ano and
							o.cod_sector=a.cod_sector and
							o.cod_programa=a.cod_programa and
							o.cod_sub_prog=a.cod_sub_prog and
							o.cod_proyecto=a.cod_proyecto and
							o.cod_activ_obra=a.cod_activ_obra and
							o.cod_partida=a.cod_partida and
							o.cod_partida=a.cod_partida and
							o.cod_generica=a.cod_generica and
							o.cod_especifica=a.cod_especifica and
							o.cod_sub_espec=a.cod_sub_espec and
							o.cod_auxiliar=a.cod_auxiliar limit 1)
						as deno_auxiliar,
						  a.cod_nivel_i,
						  a.cod_nivel_ii,
						  a.cod_ficha,
						  b.denominacion as denominacion_nomina,
						  b.denominacion_devengado,
						  b.clasificacion_personal,
						  b.frecuencia_cobro,
						  b.dias_laborables,
						  b.horas_laborables,
						  b.descuentos_ley,
						  b.mensajes_colectivos,
						  b.status_nomina,
						  b.cantidad_pagos,
						  b.correspondiente,
						  b.frecuencia_pago,
						  b.numero_recibo,
						  b.control_autorizacion,
						  b.autorizacion_diskettes,
						  b.sueldo_sugerido,
						  b.ultimo_cargo,
						  b.ultima_ficha,
						  b.ano_desde,
						  b.ano_hasta,
						  b.codigo_transaccion,
						  b.dias_cobro,
						  c.denominacion_clase as denominacion_cargo,
						  (SELECT denominacion FROM cugd02_dependencias b WHERE b.cod_tipo_institucion = a.cod_tipo_inst and b.cod_institucion  =  a.cod_inst and b.cod_dependencia   =  a.cod_dep) as denominacion_dep,
						  (SELECT denominacion FROM cnmd04_ocupacion bb WHERE bb.cod_nivel_i = a.cod_nivel_i and bb.cod_nivel_ii  =  a.cod_nivel_ii ) as denominacion_clasificacion,
						  (SELECT d.cedula_identidad FROM cnmd06_fichas d where
						   d.cod_presi            =  a.cod_presi                and
						   d.cod_entidad          =  a.cod_entidad              and
						   d.cod_tipo_inst        =  a.cod_tipo_inst            and
						   d.cod_inst             =  a.cod_inst                 and
						   d.cod_dep              =  a.cod_dep                  and
						   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
						   d.cod_cargo            =  a.cod_cargo                and
						   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  as  cedula_identidad_ficha,

						   (SELECT e.primer_apellido  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
																	   d.cod_presi            =  a.cod_presi                and
																	   d.cod_entidad          =  a.cod_entidad              and
																	   d.cod_tipo_inst        =  a.cod_tipo_inst            and
																	   d.cod_inst             =  a.cod_inst                 and
																	   d.cod_dep              =  a.cod_dep                  and
																	   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
																	   d.cod_cargo            =  a.cod_cargo                and
																	   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.primer_apellido)
	                      as  primer_apellido,

	                      (SELECT e.segundo_apellido  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
																	   d.cod_presi            =  a.cod_presi                and
																	   d.cod_entidad          =  a.cod_entidad              and
																	   d.cod_tipo_inst        =  a.cod_tipo_inst            and
																	   d.cod_inst             =  a.cod_inst                 and
																	   d.cod_dep              =  a.cod_dep                  and
																	   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
																	   d.cod_cargo            =  a.cod_cargo                and
																	   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.segundo_apellido)
	                      as  segundo_apellido,

                          (SELECT e.primer_nombre  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
																	   d.cod_presi            =  a.cod_presi                and
																	   d.cod_entidad          =  a.cod_entidad              and
																	   d.cod_tipo_inst        =  a.cod_tipo_inst            and
																	   d.cod_inst             =  a.cod_inst                 and
																	   d.cod_dep              =  a.cod_dep                  and
																	   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
																	   d.cod_cargo            =  a.cod_cargo                and
																	   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.primer_nombre)
	                      as  primer_nombre,

	                      (SELECT e.segundo_nombre  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
																	   d.cod_presi            =  a.cod_presi                and
																	   d.cod_entidad          =  a.cod_entidad              and
																	   d.cod_tipo_inst        =  a.cod_tipo_inst            and
																	   d.cod_inst             =  a.cod_inst                 and
																	   d.cod_dep              =  a.cod_dep                  and
																	   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
																	   d.cod_cargo            =  a.cod_cargo                and
																	   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.segundo_nombre)
	                      as  segundo_nombre,


	                      (SELECT e.fecha_nacimiento  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
																	   d.cod_presi            =  a.cod_presi                and
																	   d.cod_entidad          =  a.cod_entidad              and
																	   d.cod_tipo_inst        =  a.cod_tipo_inst            and
																	   d.cod_inst             =  a.cod_inst                 and
																	   d.cod_dep              =  a.cod_dep                  and
																	   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
																	   d.cod_cargo            =  a.cod_cargo                and
																	   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.fecha_nacimiento)
	                      as  fecha_nacimiento,


	                      (SELECT d.fecha_ingreso FROM cnmd06_fichas d where
								   d.cod_presi            =  a.cod_presi                and
								   d.cod_entidad          =  a.cod_entidad              and
								   d.cod_tipo_inst        =  a.cod_tipo_inst            and
								   d.cod_inst             =  a.cod_inst                 and
								   d.cod_dep              =  a.cod_dep                  and
								   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
								   d.cod_cargo            =  a.cod_cargo                and
								   d.cod_ficha            =  a.cod_ficha  GROUP BY d.fecha_ingreso)
						   as  fecha_ingreso,


						   (SELECT d.cuenta_bancaria FROM cnmd06_fichas d where
								   d.cod_presi            =  a.cod_presi                and
								   d.cod_entidad          =  a.cod_entidad              and
								   d.cod_tipo_inst        =  a.cod_tipo_inst            and
								   d.cod_inst             =  a.cod_inst                 and
								   d.cod_dep              =  a.cod_dep                  and
								   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
								   d.cod_cargo            =  a.cod_cargo                and
								   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cuenta_bancaria)
						   as  cuenta_bancaria,


						   (SELECT ent.denominacion FROM cstd01_entidades_bancarias ent WHERE cod_entidad_bancaria=(SELECT d.cod_entidad_bancaria FROM cnmd06_fichas d where
								   d.cod_presi            =  a.cod_presi                and
								   d.cod_entidad          =  a.cod_entidad              and
								   d.cod_tipo_inst        =  a.cod_tipo_inst            and
								   d.cod_inst             =  a.cod_inst                 and
								   d.cod_dep              =  a.cod_dep                  and
								   d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
								   d.cod_cargo            =  a.cod_cargo                and
								   d.cod_ficha            =  a.cod_ficha  GROUP BY d.cod_entidad_bancaria))
						   as  banco,

						   (SELECT v.denominacion_profesion FROM v_cnmd06_fichas_datos_personales v where
						   v.cod_presi            =  a.cod_presi                and
						   v.cod_entidad          =  a.cod_entidad              and
						   v.cod_tipo_inst        =  a.cod_tipo_inst            and
						   v.cod_inst             =  a.cod_inst                 and
						   v.cod_dep              =  a.cod_dep                  and
						   v.cod_tipo_nomina      =  a.cod_tipo_nomina          and
						   v.cod_cargo            =  a.cod_cargo                and
						   v.cod_ficha            =  a.cod_ficha)  as  denominacion_profesion

                 FROM

                          " . $tabla . " a, cnmd01 b, " . $vista . " c

                 WHERE

                           a.cod_presi            =  " . $cod_presi . "             and
						   a.cod_entidad          =  " . $cod_entidad . "           and
						   a.cod_tipo_inst        =  " . $cod_tipo_inst . "         and
						   a.cod_inst             =  " . $cod_inst . "              and
						   " . $dep_A . "
						   " . $tipo_nomina . "
						   " . $sql_a . "
						   " . $sql_b . "
						   b.cod_presi            =  a.cod_presi                and
						   b.cod_entidad          =  a.cod_entidad              and
						   b.cod_tipo_inst        =  a.cod_tipo_inst            and
						   b.cod_inst             =  a.cod_inst                 and
						   b.cod_dep              =  a.cod_dep                  and
						   b.cod_tipo_nomina      =  a.cod_tipo_nomina          and
						   c.cod_presi            =  a.cod_presi                and
						   c.cod_entidad          =  a.cod_entidad              and
						   c.cod_tipo_inst        =  a.cod_tipo_inst            and
						   c.cod_inst             =  a.cod_inst                 and
						   c.cod_dep              =  a.cod_dep                  and
						   c.cod_tipo_nomina      =  a.cod_tipo_nomina          and
						   c.cod_cargo            =  a.cod_cargo                and
                           c.cod_puesto           =  a.cod_puesto

                 ORDER BY
                            a.cod_presi,
                            a.cod_entidad,
                            a.cod_tipo_inst,
                            a.cod_inst,
                            a.cod_dep,
                            a.cod_tipo_nomina,
                            " . $order_by . "
                            ASC

             ; ");

            //pr($rs);

            $this->set('tipo', $tipo);
            $this->set('datos', $rs);
            $this->set('presentar_como', $frecuencia);

            $this->set('geografico', $radio_geograf);
            $this->set('administrativo', $radio_admin);
        }//fin $tipo

        $this->set('opcion', $opcion);
        $this->set('var1', $var1);
    }

//fin funtion listado_expediente

    function listado_expediente_personal($var1 = null, $var2 = null) {
        $tabla = "";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');

        if ($var2 == null) {
            $this->layout = "ajax";
            $opcion = 1;
            $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst;
            $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
            $dato = null;
            foreach ($year as $year) {
                $dato_year = $year['cfpd01_formulacion']['ano_formular'];
            }
            if ($var1 == 1) {
                $year_vista = $dato_year;
            } else {
                $year_vista = $this->ano_ejecucion();
            }
            $this->set('year', $year_vista);
            $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
            $this->concatenaN($lista, 'nomina');
        } else {
            $this->layout = "pdf";
            $opcion = 2;

            set_time_limit(0);


            $sql_a = "";
            $sql_b = "";

            $radio_geograf = "";
            $radio_admin = "";


            if (!empty($this->data['reporte3']['consolidacion'])) {
                $consolidacion = $this->data['reporte3']['consolidacion'];
            } else {
                $consolidacion = 2;
            }
            if (!empty($this->data['reporte3']['year'])) {
                $year = $this->data['reporte3']['year'];
            } else {
                $year = $this->ano_ejecucion();
            }
            if (!empty($this->data['reporte3']['tipo'])) {
                $tipo = $this->data['reporte3']['tipo'];
            } else {
                $tipo = 1;
            }
            if (!empty($this->data['reporte3']['frecuencia'])) {
                $frecuencia = $this->data['reporte3']['frecuencia'];
            } else {
                $frecuencia = 1;
            }


            if (!empty($this->data['reporte3']['radio_condicion_actividad'])) {
                $radio_condicion_actividad = $this->data['reporte3']['radio_condicion_actividad'];
            } else {
                $radio_condicion_actividad = 20;
            }

            if (!empty($this->data['reporte3']['radio_ordenamiento_codigo'])) {
                $ordenamiento = $this->data['reporte3']['radio_ordenamiento_codigo'];
            } else {
                $ordenamiento = 1;
            }
            if (!empty($this->data['reporte3']['radio_ubicacion_geografica'])) {
                $radio_geograf = $this->data['reporte3']['radio_ubicacion_geografica'];
            } else {
                $radio_geograf = 0;
            }
            if (!empty($this->data['reporte3']['radio_ubicacion_administrativa'])) {
                $radio_admin = $this->data['reporte3']['radio_ubicacion_administrativa'];
            } else {
                $radio_admin = 0;
            }

            if ($radio_geograf == 2) {
                if (!empty($this->data['cobp01_contratoobras']['cod_estado'])) {
                    $sql_b .= "a.cod_estado  = " . $this->data['cobp01_contratoobras']['cod_estado'] . "  and ";
                }
                if (!empty($this->data['cobp01_contratoobras']['cod_municipio'])) {
                    $sql_b .= "a.cod_municipio  = " . $this->data['cobp01_contratoobras']['cod_municipio'] . "  and ";
                }
                if (!empty($this->data['cobp01_contratoobras']['cod_parroquia'])) {
                    $sql_b .= "a.cod_parroquia  = " . $this->data['cobp01_contratoobras']['cod_parroquia'] . "  and ";
                }
                if (!empty($this->data['cobp01_contratoobras']['cod_centro'])) {
                    $sql_b .= "a.cod_centro  = " . $this->data['cobp01_contratoobras']['cod_centro'] . "  and ";
                }

                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_dirsuperior'])) {
                    $sql_a .= "a.cod_dir_superior  = " . $this->data['cscp02_solicitud_cotizacion']['cod_dirsuperior'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_coordinacion'])) {
                    $sql_a .= "a.cod_coordinacion  = " . $this->data['cscp02_solicitud_cotizacion']['cod_coordinacion'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_secretaria'])) {
                    $sql_a .= "a.cod_secretaria  = " . $this->data['cscp02_solicitud_cotizacion']['cod_secretaria'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_direccion'])) {
                    $sql_a .= "a.cod_direccion  = " . $this->data['cscp02_solicitud_cotizacion']['cod_direccion'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_division'])) {
                    $sql_a .= "a.cod_division  = " . $this->data['cscp02_solicitud_cotizacion']['cod_division'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_departamento'])) {
                    $sql_a .= "a.cod_departamento  = " . $this->data['cscp02_solicitud_cotizacion']['cod_departamento'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_oficina'])) {
                    $sql_a .= "a.cod_oficina  = " . $this->data['cscp02_solicitud_cotizacion']['cod_oficina'] . "  and ";
                }
            }

            if ($radio_admin == 2) {
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_dirsuperior'])) {
                    $sql_a .= "a.cod_dir_superior  = " . $this->data['cscp02_solicitud_cotizacion']['cod_dirsuperior'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_coordinacion'])) {
                    $sql_a .= "a.cod_coordinacion  = " . $this->data['cscp02_solicitud_cotizacion']['cod_coordinacion'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_secretaria'])) {
                    $sql_a .= "a.cod_secretaria  = " . $this->data['cscp02_solicitud_cotizacion']['cod_secretaria'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_direccion'])) {
                    $sql_a .= "a.cod_direccion  = " . $this->data['cscp02_solicitud_cotizacion']['cod_direccion'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_division'])) {
                    $sql_a .= "a.cod_division  = " . $this->data['cscp02_solicitud_cotizacion']['cod_division'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_departamento'])) {
                    $sql_a .= "a.cod_departamento  = " . $this->data['cscp02_solicitud_cotizacion']['cod_departamento'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_oficina'])) {
                    $sql_a .= "a.cod_oficina  = " . $this->data['cscp02_solicitud_cotizacion']['cod_oficina'] . "  and ";
                }
            }

            if ($radio_condicion_actividad == 20) {
                $sql_condicion_actividad = "";
                $_SESSION["sql_condicion_actividad"] = "";
            } else {
                $sql_condicion_actividad = " and b.condicion_actividad_ficha='" . $radio_condicion_actividad . "'   ";
                $condicion_aaa = array(20 => "TODOS", 1 => "Activo", 2 => "Permiso no Remunerado", 3 => "Comisión de Servicio", 4 => "Vacaciones", 5 => "Suspendido", 6 => "Retirado", 7 => "Ascenso", 8 => "Reposo");
                $_SESSION["sql_condicion_actividad"] = "CONDICIÓN DE ACTIVIDAD: " . $condicion_aaa[$radio_condicion_actividad];
            }

            if ($ordenamiento == '10') {
                $mes_ingreso = $this->data['reporte3']['select_omes'];
                $sql_smes = " AND substr(ficha.fecha_ingreso::text,6,2)::integer = '" . $mes_ingreso . "'::integer";
            } else {
                $mes_ingreso = "";
                $sql_smes = " ";
            }

            $order_by = "";
            if ($radio_geograf == 0 && $radio_admin == 0) {
                switch ($ordenamiento) {
                    case '1': $order_by .= " a.cod_tipo_nomina, a.cod_cargo, a.cod_ficha"; /* echo "<br />option: 1"; */ break;
                    case '2': $order_by .= " a.cod_tipo_nomina, a.cod_ficha, a.cod_cargo"; /* echo "<br />option: 2"; */ break;
                    case '3': $order_by .= " a.cod_tipo_nomina, a.cod_puesto, a.cod_cargo, a.cod_ficha"; /* echo "<br />option: 3"; */ break;
                    case '4': $order_by .= " a.cod_tipo_nomina, cedula_identidad, a.cod_cargo, a.cod_ficha"; /* echo "<br />option: 4"; */ break;
                    case '5': $order_by .= " a.cod_tipo_nomina, primer_apellido, segundo_apellido, primer_nombre, segundo_nombre, a.cod_cargo, a.cod_ficha"; /* echo "<br />option: 5"; */ break;
                    case '6': $order_by .= " a.cod_tipo_nomina, fecha_nacimiento, a.cod_cargo, a.cod_ficha"; /* echo "<br />option: 6"; */ break;
                    case '7': $order_by .= " a.cod_tipo_nomina, denominacion_profesion, a.cod_cargo, a.cod_ficha"; /* echo "<br />option: 7"; */ break;
                    case '8': $order_by .= " a.cod_tipo_nomina, fecha_ingreso, a.cod_cargo, a.cod_ficha"; /* echo "<br />option: 8"; */ break;
                    case '9': $order_by .= " a.cod_tipo_nomina, fecha_condicion, a.cod_cargo, a.cod_ficha"; /* echo "<br />option: 8"; */ break;
                    case '10': $order_by .= " a.cod_tipo_nomina, fecha_ingreso, a.cod_cargo, a.cod_ficha";
                        break;
                    default: break;
                }
            } elseif ($radio_geograf == 1 || $radio_geograf == 2) {
                $order_by .= " a.cod_estado,
						a.cod_municipio,
					    a.cod_parroquia,
					    a.cod_centro,
						a.cod_dir_superior,
					  	a.cod_coordinacion,
					  	a.cod_secretaria,
					  	a.cod_direccion,
					  	a.cod_division,
					  	a.cod_departamento,
					  	a.cod_oficina,
					  	a.cod_cargo,
					  	a.cod_ficha";
            } elseif ($radio_admin == 1 || $radio_admin == 2) {
                $order_by .= " a.cod_dir_superior,
					  	a.cod_coordinacion,
					  	a.cod_secretaria,
					  	a.cod_direccion,
					  	a.cod_division,
					  	a.cod_departamento,
					  	a.cod_oficina,
					  	a.cod_cargo,
					  	a.cod_ficha";
            }


            if ($radio_condicion_actividad != 20 && $radio_condicion_actividad != 6) {

                $rs = $this->v_cfpd05_denominaciones->execute("
			SELECT
			  a.cod_tipo_nomina,
			  (SELECT cn01.denominacion FROM cnmd01 cn01 WHERE cn01.cod_presi = a.cod_presi AND cn01.cod_entidad = a.cod_entidad AND cn01.cod_tipo_inst = a.cod_tipo_inst AND cn01.cod_inst = a.cod_inst AND cn01.cod_dep = a.cod_dep AND cn01.cod_tipo_nomina = a.cod_tipo_nomina) as denominacion_nomina,
			  (SELECT cn01.frecuencia_cobro FROM cnmd01 cn01 WHERE cn01.cod_presi = a.cod_presi AND cn01.cod_entidad = a.cod_entidad AND cn01.cod_tipo_inst = a.cod_tipo_inst AND cn01.cod_inst = a.cod_inst AND cn01.cod_dep = a.cod_dep AND cn01.cod_tipo_nomina = a.cod_tipo_nomina) as frecuencia_cobro,
			  (SELECT ficha.cedula_identidad FROM cnmd06_fichas ficha WHERE ficha.cod_presi = a.cod_presi AND ficha.cod_entidad = a.cod_entidad AND ficha.cod_tipo_inst = a.cod_tipo_inst AND ficha.cod_inst = a.cod_inst AND ficha.cod_dep = a.cod_dep AND ficha.cod_tipo_nomina = a.cod_tipo_nomina AND ficha.cod_cargo = a.cod_cargo AND ficha.cod_ficha = a.cod_ficha) as cedula_identidad_ficha,
			  (SELECT d.cedula_identidad FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as cedula_identidad,
			  (SELECT d.primer_nombre FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as primer_nombre,
			  (SELECT d.segundo_nombre FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as segundo_nombre,
			  (SELECT d.primer_apellido FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as primer_apellido,
			  (SELECT d.segundo_apellido FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as segundo_apellido,
			  (SELECT d.fecha_nacimiento FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as fecha_nacimiento,
			  (SELECT d.cod_profesion FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as cod_profesion,
			  (SELECT c.denominacion FROM cnmd06_profesiones c WHERE c.cod_profesion=(SELECT d.cod_profesion FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha))) as denominacion_profesion,
			  (SELECT ficha.fecha_ingreso   FROM cnmd06_fichas ficha WHERE ficha.cod_presi = a.cod_presi AND ficha.cod_entidad = a.cod_entidad AND ficha.cod_tipo_inst = a.cod_tipo_inst AND ficha.cod_inst = a.cod_inst AND ficha.cod_dep = a.cod_dep AND ficha.cod_tipo_nomina = a.cod_tipo_nomina AND ficha.cod_cargo = a.cod_cargo AND ficha.cod_ficha = a.cod_ficha $sql_smes) as fecha_ingreso,
			  (SELECT ficha.fecha_condicion FROM cnmd06_fichas ficha WHERE ficha.cod_presi = a.cod_presi AND ficha.cod_entidad = a.cod_entidad AND ficha.cod_tipo_inst = a.cod_tipo_inst AND ficha.cod_inst = a.cod_inst AND ficha.cod_dep = a.cod_dep AND ficha.cod_tipo_nomina = a.cod_tipo_nomina AND ficha.cod_cargo = a.cod_cargo AND ficha.cod_ficha = a.cod_ficha) as fecha_condicion,
			  (SELECT ficha.cod_entidad_bancaria FROM cnmd06_fichas ficha WHERE ficha.cod_presi = a.cod_presi AND ficha.cod_entidad = a.cod_entidad AND ficha.cod_tipo_inst = a.cod_tipo_inst AND ficha.cod_inst = a.cod_inst AND ficha.cod_dep = a.cod_dep AND ficha.cod_tipo_nomina = a.cod_tipo_nomina AND ficha.cod_cargo = a.cod_cargo AND ficha.cod_ficha = a.cod_ficha) as cod_entidad_bancaria,
			  (SELECT ficha.cuenta_bancaria FROM cnmd06_fichas ficha WHERE ficha.cod_presi = a.cod_presi AND ficha.cod_entidad = a.cod_entidad AND ficha.cod_tipo_inst = a.cod_tipo_inst AND ficha.cod_inst = a.cod_inst AND ficha.cod_dep = a.cod_dep AND ficha.cod_tipo_nomina = a.cod_tipo_nomina AND ficha.cod_cargo = a.cod_cargo AND ficha.cod_ficha = a.cod_ficha) as cuenta_bancaria,
			  (SELECT ban.denominacion FROM cstd01_entidades_bancarias ban WHERE ban.cod_entidad_bancaria=(SELECT ficha.cod_entidad_bancaria FROM cnmd06_fichas ficha WHERE ficha.cod_presi = a.cod_presi AND ficha.cod_entidad = a.cod_entidad AND ficha.cod_tipo_inst = a.cod_tipo_inst AND ficha.cod_inst = a.cod_inst AND ficha.cod_dep = a.cod_dep AND ficha.cod_tipo_nomina = a.cod_tipo_nomina AND ficha.cod_cargo = a.cod_cargo AND ficha.cod_ficha = a.cod_ficha)) as banco,
			  a.cod_cargo,
			  a.cod_puesto,
			  b.denominacion_clase AS denominacion_cargo,
			  a.cod_ficha,
			  a.sueldo_basico,
			  a.compensaciones,
			  a.primas,
			  a.bonos,
			  a.cod_dir_superior,
			  a.cod_coordinacion,
			  a.cod_secretaria,
			  a.cod_direccion,
			  a.cod_division,
			  a.cod_departamento,
			  a.cod_oficina,
			  a.cod_estado,
			  a.cod_municipio,
			  a.cod_parroquia,
			  a.cod_centro,
			  b.dir_superior,
			  b.coordinacion,
			  b.secretaria,
			  b.direccion,
			  b.division,
			  b.departamento,
			  b.oficina,
			  (SELECT xya.denominacion FROM cugd01_estados          xya where xya.cod_republica=a.cod_presi and xya.cod_estado=a.cod_estado                                                                                                                  GROUP BY xya.denominacion) as  deno_cod_estado,
			  (SELECT xyb.denominacion FROM cugd01_municipios       xyb where xyb.cod_republica=a.cod_presi and xyb.cod_estado=a.cod_estado  and xyb.cod_municipio=a.cod_municipio                                                                           GROUP BY xyb.denominacion) as  deno_cod_municipio,
			  (SELECT xyc.denominacion FROM cugd01_parroquias       xyc where xyc.cod_republica=a.cod_presi and xyc.cod_estado=a.cod_estado  and xyc.cod_municipio=a.cod_municipio and xyc.cod_parroquia = a.cod_parroquia                                   GROUP BY xyc.denominacion) as  deno_cod_parroquia,
			  (SELECT xyd.denominacion FROM cugd01_centros_poblados xyd where xyd.cod_republica=a.cod_presi and xyd.cod_estado=a.cod_estado  and xyd.cod_municipio=a.cod_municipio and xyd.cod_parroquia = a.cod_parroquia and xyd.cod_centro = a.cod_centro GROUP BY xyd.denominacion) as  deno_cod_centro

			FROM
			 cnmd05 a, v_cnmd05_cargos_todo b

			WHERE
			  a.cod_presi     =  " . $cod_presi . "     and
			  a.cod_entidad   =  " . $cod_entidad . "   and
			  a.cod_tipo_inst =  " . $cod_tipo_inst . " and
			  a.cod_inst      =  " . $cod_inst . "      and
			  a.cod_dep       =  " . $cod_dep . "       and
			  " . $sql_a . "
			  " . $sql_b . "
			  a.cod_presi = b.cod_presi AND
			  a.cod_entidad = b.cod_entidad AND
			  a.cod_tipo_inst = b.cod_tipo_inst AND
			  a.cod_inst = b.cod_inst AND
			  a.cod_dep = b.cod_dep AND
			  a.cod_tipo_nomina = b.cod_tipo_nomina AND
			  a.cod_cargo = b.cod_cargo AND
			  a.cod_puesto = b.cod_puesto AND
			  a.cod_tipo_nomina = " . $tipo . "  " . $sql_condicion_actividad . "

			ORDER BY
			  a.cod_presi,
			  a.cod_entidad,
			  a.cod_tipo_inst,
			  a.cod_inst,
			  a.cod_dep,
			  a.cod_tipo_nomina,
			  " . $order_by . "");
            } else if ($radio_condicion_actividad == 6) {

                $rs = $this->v_cfpd05_denominaciones->execute("
			SELECT
			  a.cod_tipo_nomina,
			  (SELECT cn01.denominacion FROM cnmd01 cn01 WHERE cn01.cod_presi = b.cod_presi AND cn01.cod_entidad = b.cod_entidad AND cn01.cod_tipo_inst = b.cod_tipo_inst AND cn01.cod_inst = b.cod_inst AND cn01.cod_dep = b.cod_dep AND cn01.cod_tipo_nomina = b.cod_tipo_nomina) as denominacion_nomina,
			  (SELECT cn01.frecuencia_cobro FROM cnmd01 cn01 WHERE cn01.cod_presi = b.cod_presi AND cn01.cod_entidad = b.cod_entidad AND cn01.cod_tipo_inst = b.cod_tipo_inst AND cn01.cod_inst = b.cod_inst AND cn01.cod_dep = b.cod_dep AND cn01.cod_tipo_nomina = b.cod_tipo_nomina) as frecuencia_cobro,
			  (SELECT ficha.cedula_identidad FROM cnmd06_fichas ficha WHERE ficha.cod_presi = b.cod_presi AND ficha.cod_entidad = b.cod_entidad AND ficha.cod_tipo_inst = b.cod_tipo_inst AND ficha.cod_inst = b.cod_inst AND ficha.cod_dep = b.cod_dep AND ficha.cod_tipo_nomina = b.cod_tipo_nomina AND ficha.cod_cargo = b.cod_cargo AND ficha.cod_ficha = b.cod_ficha) as cedula_identidad_ficha,
			  (SELECT d.cedula_identidad FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = b.cod_presi AND f.cod_entidad = b.cod_entidad AND f.cod_tipo_inst = b.cod_tipo_inst AND f.cod_inst = b.cod_inst AND f.cod_dep = b.cod_dep AND f.cod_tipo_nomina = b.cod_tipo_nomina AND f.cod_cargo = b.cod_cargo AND f.cod_ficha = b.cod_ficha)) as cedula_identidad,
			  (SELECT d.primer_nombre FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = b.cod_presi AND f.cod_entidad = b.cod_entidad AND f.cod_tipo_inst = b.cod_tipo_inst AND f.cod_inst = b.cod_inst AND f.cod_dep = b.cod_dep AND f.cod_tipo_nomina = b.cod_tipo_nomina AND f.cod_cargo = b.cod_cargo AND f.cod_ficha = b.cod_ficha)) as primer_nombre,
			  (SELECT d.segundo_nombre FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = b.cod_presi AND f.cod_entidad = b.cod_entidad AND f.cod_tipo_inst = b.cod_tipo_inst AND f.cod_inst = b.cod_inst AND f.cod_dep = b.cod_dep AND f.cod_tipo_nomina = b.cod_tipo_nomina AND f.cod_cargo = b.cod_cargo AND f.cod_ficha = b.cod_ficha)) as segundo_nombre,
			  (SELECT d.primer_apellido FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = b.cod_presi AND f.cod_entidad = b.cod_entidad AND f.cod_tipo_inst = b.cod_tipo_inst AND f.cod_inst = b.cod_inst AND f.cod_dep = b.cod_dep AND f.cod_tipo_nomina = b.cod_tipo_nomina AND f.cod_cargo = b.cod_cargo AND f.cod_ficha = b.cod_ficha)) as primer_apellido,
			  (SELECT d.segundo_apellido FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = b.cod_presi AND f.cod_entidad = b.cod_entidad AND f.cod_tipo_inst = b.cod_tipo_inst AND f.cod_inst = b.cod_inst AND f.cod_dep = b.cod_dep AND f.cod_tipo_nomina = b.cod_tipo_nomina AND f.cod_cargo = b.cod_cargo AND f.cod_ficha = b.cod_ficha)) as segundo_apellido,
			  (SELECT d.fecha_nacimiento FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = b.cod_presi AND f.cod_entidad = b.cod_entidad AND f.cod_tipo_inst = b.cod_tipo_inst AND f.cod_inst = b.cod_inst AND f.cod_dep = b.cod_dep AND f.cod_tipo_nomina = b.cod_tipo_nomina AND f.cod_cargo = b.cod_cargo AND f.cod_ficha = b.cod_ficha)) as fecha_nacimiento,
			  (SELECT d.cod_profesion FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = b.cod_presi AND f.cod_entidad = b.cod_entidad AND f.cod_tipo_inst = b.cod_tipo_inst AND f.cod_inst = b.cod_inst AND f.cod_dep = b.cod_dep AND f.cod_tipo_nomina = b.cod_tipo_nomina AND f.cod_cargo = b.cod_cargo AND f.cod_ficha = b.cod_ficha)) as cod_profesion,
			  (SELECT c.denominacion FROM cnmd06_profesiones c WHERE c.cod_profesion=(SELECT d.cod_profesion FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = b.cod_presi AND f.cod_entidad = b.cod_entidad AND f.cod_tipo_inst = b.cod_tipo_inst AND f.cod_inst = b.cod_inst AND f.cod_dep = b.cod_dep AND f.cod_tipo_nomina = b.cod_tipo_nomina AND f.cod_cargo = b.cod_cargo AND f.cod_ficha = b.cod_ficha))) as denominacion_profesion,
			  (SELECT ficha.fecha_ingreso   FROM cnmd06_fichas ficha WHERE ficha.cod_presi = b.cod_presi AND ficha.cod_entidad = b.cod_entidad AND ficha.cod_tipo_inst = b.cod_tipo_inst AND ficha.cod_inst = b.cod_inst AND ficha.cod_dep = b.cod_dep AND ficha.cod_tipo_nomina = b.cod_tipo_nomina AND ficha.cod_cargo = b.cod_cargo AND ficha.cod_ficha = b.cod_ficha $sql_smes) as fecha_ingreso,
			  (SELECT ficha.fecha_condicion FROM cnmd06_fichas ficha WHERE ficha.cod_presi = b.cod_presi AND ficha.cod_entidad = b.cod_entidad AND ficha.cod_tipo_inst = b.cod_tipo_inst AND ficha.cod_inst = b.cod_inst AND ficha.cod_dep = b.cod_dep AND ficha.cod_tipo_nomina = b.cod_tipo_nomina AND ficha.cod_cargo = b.cod_cargo AND ficha.cod_ficha = b.cod_ficha) as fecha_condicion,
			  (SELECT ficha.cod_entidad_bancaria FROM cnmd06_fichas ficha WHERE ficha.cod_presi = b.cod_presi AND ficha.cod_entidad = b.cod_entidad AND ficha.cod_tipo_inst = b.cod_tipo_inst AND ficha.cod_inst = b.cod_inst AND ficha.cod_dep = b.cod_dep AND ficha.cod_tipo_nomina = b.cod_tipo_nomina AND ficha.cod_cargo = b.cod_cargo AND ficha.cod_ficha = b.cod_ficha) as cod_entidad_bancaria,
			  (SELECT ficha.cuenta_bancaria FROM cnmd06_fichas ficha WHERE ficha.cod_presi = b.cod_presi AND ficha.cod_entidad = b.cod_entidad AND ficha.cod_tipo_inst = b.cod_tipo_inst AND ficha.cod_inst = b.cod_inst AND ficha.cod_dep = b.cod_dep AND ficha.cod_tipo_nomina = b.cod_tipo_nomina AND ficha.cod_cargo = b.cod_cargo AND ficha.cod_ficha = b.cod_ficha) as cuenta_bancaria,
			  (SELECT ban.denominacion FROM cstd01_entidades_bancarias ban WHERE ban.cod_entidad_bancaria=(SELECT ficha.cod_entidad_bancaria FROM cnmd06_fichas ficha WHERE ficha.cod_presi = b.cod_presi AND ficha.cod_entidad = b.cod_entidad AND ficha.cod_tipo_inst = b.cod_tipo_inst AND ficha.cod_inst = b.cod_inst AND ficha.cod_dep = b.cod_dep AND ficha.cod_tipo_nomina = b.cod_tipo_nomina AND ficha.cod_cargo = b.cod_cargo AND ficha.cod_ficha = b.cod_ficha)) as banco,
			  a.cod_cargo,
			  a.cod_puesto,
			  b.denominacion_clase AS denominacion_cargo,
			  b.cod_ficha,
			  a.sueldo_basico,
			  a.compensaciones,
			  a.primas,
			  a.bonos,
			  a.cod_dir_superior,
			  a.cod_coordinacion,
			  a.cod_secretaria,
			  a.cod_direccion,
			  a.cod_division,
			  a.cod_departamento,
			  a.cod_oficina,
			  a.cod_estado,
			  a.cod_municipio,
			  a.cod_parroquia,
			  a.cod_centro,
			  b.dir_superior,
			  b.coordinacion,
			  b.secretaria,
			  b.direccion,
			  b.division,
			  b.departamento,
			  b.oficina,
			  (SELECT xya.denominacion FROM cugd01_estados          xya where xya.cod_republica=a.cod_presi and xya.cod_estado=a.cod_estado                                                                                                                  GROUP BY xya.denominacion) as  deno_cod_estado,
			  (SELECT xyb.denominacion FROM cugd01_municipios       xyb where xyb.cod_republica=a.cod_presi and xyb.cod_estado=a.cod_estado  and xyb.cod_municipio=a.cod_municipio                                                                           GROUP BY xyb.denominacion) as  deno_cod_municipio,
			  (SELECT xyc.denominacion FROM cugd01_parroquias       xyc where xyc.cod_republica=a.cod_presi and xyc.cod_estado=a.cod_estado  and xyc.cod_municipio=a.cod_municipio and xyc.cod_parroquia = a.cod_parroquia                                   GROUP BY xyc.denominacion) as  deno_cod_parroquia,
			  (SELECT xyd.denominacion FROM cugd01_centros_poblados xyd where xyd.cod_republica=a.cod_presi and xyd.cod_estado=a.cod_estado  and xyd.cod_municipio=a.cod_municipio and xyd.cod_parroquia = a.cod_parroquia and xyd.cod_centro = a.cod_centro GROUP BY xyd.denominacion) as  deno_cod_centro

			FROM
			 cnmd05 a, v_cnmd05_cargos_2 b

			WHERE
			  a.cod_presi     =  " . $cod_presi . "     and
			  a.cod_entidad   =  " . $cod_entidad . "   and
			  a.cod_tipo_inst =  " . $cod_tipo_inst . " and
			  a.cod_inst      =  " . $cod_inst . "      and
			  a.cod_dep       =  " . $cod_dep . "       and
			  " . $sql_a . "
			  " . $sql_b . "
			  a.cod_presi = b.cod_presi AND
			  a.cod_entidad = b.cod_entidad AND
			  a.cod_tipo_inst = b.cod_tipo_inst AND
			  a.cod_inst = b.cod_inst AND
			  a.cod_dep = b.cod_dep AND
			  a.cod_tipo_nomina = b.cod_tipo_nomina AND
			  a.cod_cargo = b.cod_cargo AND
			  a.cod_puesto = b.cod_puesto AND
			  a.cod_tipo_nomina = " . $tipo . "  " . $sql_condicion_actividad . "

			ORDER BY
			  a.cod_presi,
			  a.cod_entidad,
			  a.cod_tipo_inst,
			  a.cod_inst,
			  a.cod_dep,
			  a.cod_tipo_nomina,
			  " . $order_by . "");
            } else {

                /*

                  $rs=$this->v_cfpd05_denominaciones->execute("
                  SELECT * FROM v_cnmd05_cargos_todo_retirado a

                  WHERE
                  a.cod_presi     =  ".$cod_presi."     and
                  a.cod_entidad   =  ".$cod_entidad."   and
                  a.cod_tipo_inst =  ".$cod_tipo_inst." and
                  a.cod_inst      =  ".$cod_inst."      and
                  a.cod_dep       =  ".$cod_dep."       and
                  ".$sql_a."
                  ".$sql_b."
                  a.cod_tipo_nomina = ".$tipo."

                  ORDER BY
                  a.cod_presi,
                  a.cod_entidad,
                  a.cod_tipo_inst,
                  a.cod_inst,
                  a.cod_dep,
                  a.cod_tipo_nomina,
                  ".$order_by.""); */

                // (SELECT ficha.fecha_ingreso   FROM cnmd06_fichas ficha WHERE ficha.cod_presi = a.cod_presi AND ficha.cod_entidad = a.cod_entidad AND ficha.cod_tipo_inst = a.cod_tipo_inst AND ficha.cod_inst = a.cod_inst AND ficha.cod_dep = a.cod_dep AND ficha.cod_tipo_nomina = a.cod_tipo_nomina AND ficha.cod_cargo = a.cod_cargo AND ficha.cod_ficha = a.cod_ficha $sql_smes AND EXISTS (SELECT m.cod_ficha from cnmd05 m WHERE ficha.cod_presi = m.cod_presi AND ficha.cod_entidad = m.cod_entidad AND ficha.cod_tipo_inst = m.cod_tipo_inst AND ficha.cod_inst = m.cod_inst AND ficha.cod_dep = m.cod_dep AND ficha.cod_tipo_nomina = m.cod_tipo_nomina AND ficha.cod_cargo = m.cod_cargo AND ficha.cod_ficha = m.cod_ficha)) as fecha_ingreso,

                $rs = $this->v_cfpd05_denominaciones->execute("SELECT
			  a.cod_tipo_nomina,
			  (SELECT cn01.denominacion FROM cnmd01 cn01 WHERE cn01.cod_presi = a.cod_presi AND cn01.cod_entidad = a.cod_entidad AND cn01.cod_tipo_inst = a.cod_tipo_inst AND cn01.cod_inst = a.cod_inst AND cn01.cod_dep = a.cod_dep AND cn01.cod_tipo_nomina = a.cod_tipo_nomina) as denominacion_nomina,
			  (SELECT cn01.frecuencia_cobro FROM cnmd01 cn01 WHERE cn01.cod_presi = a.cod_presi AND cn01.cod_entidad = a.cod_entidad AND cn01.cod_tipo_inst = a.cod_tipo_inst AND cn01.cod_inst = a.cod_inst AND cn01.cod_dep = a.cod_dep AND cn01.cod_tipo_nomina = a.cod_tipo_nomina) as frecuencia_cobro,
			  (SELECT ficha.cedula_identidad FROM cnmd06_fichas ficha WHERE ficha.cod_presi = a.cod_presi AND ficha.cod_entidad = a.cod_entidad AND ficha.cod_tipo_inst = a.cod_tipo_inst AND ficha.cod_inst = a.cod_inst AND ficha.cod_dep = a.cod_dep AND ficha.cod_tipo_nomina = a.cod_tipo_nomina AND ficha.cod_cargo = a.cod_cargo AND ficha.cod_ficha = a.cod_ficha) as cedula_identidad_ficha,
			  (SELECT d.cedula_identidad FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as cedula_identidad,
			  (SELECT d.primer_nombre FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as primer_nombre,
			  (SELECT d.segundo_nombre FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as segundo_nombre,
			  (SELECT d.primer_apellido FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as primer_apellido,
			  (SELECT d.segundo_apellido FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as segundo_apellido,
			  (SELECT d.fecha_nacimiento FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as fecha_nacimiento,
			  (SELECT d.cod_profesion FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as cod_profesion,
			  (SELECT c.denominacion FROM cnmd06_profesiones c WHERE c.cod_profesion=(SELECT d.cod_profesion FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha))) as denominacion_profesion,
			  (SELECT ficha.fecha_ingreso   FROM cnmd06_fichas ficha WHERE ficha.cod_presi = a.cod_presi AND ficha.cod_entidad = a.cod_entidad AND ficha.cod_tipo_inst = a.cod_tipo_inst AND ficha.cod_inst = a.cod_inst AND ficha.cod_dep = a.cod_dep AND ficha.cod_tipo_nomina = a.cod_tipo_nomina AND ficha.cod_cargo = a.cod_cargo AND ficha.cod_ficha = a.cod_ficha $sql_smes) as fecha_ingreso,
			  (SELECT ficha.fecha_condicion FROM cnmd06_fichas ficha WHERE ficha.cod_presi = a.cod_presi AND ficha.cod_entidad = a.cod_entidad AND ficha.cod_tipo_inst = a.cod_tipo_inst AND ficha.cod_inst = a.cod_inst AND ficha.cod_dep = a.cod_dep AND ficha.cod_tipo_nomina = a.cod_tipo_nomina AND ficha.cod_cargo = a.cod_cargo AND ficha.cod_ficha = a.cod_ficha) as fecha_condicion,
			  (SELECT ficha.cod_entidad_bancaria FROM cnmd06_fichas ficha WHERE ficha.cod_presi = a.cod_presi AND ficha.cod_entidad = a.cod_entidad AND ficha.cod_tipo_inst = a.cod_tipo_inst AND ficha.cod_inst = a.cod_inst AND ficha.cod_dep = a.cod_dep AND ficha.cod_tipo_nomina = a.cod_tipo_nomina AND ficha.cod_cargo = a.cod_cargo AND ficha.cod_ficha = a.cod_ficha) as cod_entidad_bancaria,
			  (SELECT ficha.cuenta_bancaria FROM cnmd06_fichas ficha WHERE ficha.cod_presi = a.cod_presi AND ficha.cod_entidad = a.cod_entidad AND ficha.cod_tipo_inst = a.cod_tipo_inst AND ficha.cod_inst = a.cod_inst AND ficha.cod_dep = a.cod_dep AND ficha.cod_tipo_nomina = a.cod_tipo_nomina AND ficha.cod_cargo = a.cod_cargo AND ficha.cod_ficha = a.cod_ficha) as cuenta_bancaria,
			  (SELECT ban.denominacion FROM cstd01_entidades_bancarias ban WHERE ban.cod_entidad_bancaria=(SELECT ficha.cod_entidad_bancaria FROM cnmd06_fichas ficha WHERE ficha.cod_presi = a.cod_presi AND ficha.cod_entidad = a.cod_entidad AND ficha.cod_tipo_inst = a.cod_tipo_inst AND ficha.cod_inst = a.cod_inst AND ficha.cod_dep = a.cod_dep AND ficha.cod_tipo_nomina = a.cod_tipo_nomina AND ficha.cod_cargo = a.cod_cargo AND ficha.cod_ficha = a.cod_ficha)) as banco,
			  a.cod_cargo,
			  a.cod_puesto,
			  b.denominacion_clase AS denominacion_cargo,
			  a.cod_ficha,
			  a.sueldo_basico,
			  a.compensaciones,
			  a.primas,
			  a.bonos,
			  a.cod_dir_superior,
			  a.cod_coordinacion,
			  a.cod_secretaria,
			  a.cod_direccion,
			  a.cod_division,
			  a.cod_departamento,
			  a.cod_oficina,
			  a.cod_estado,
			  a.cod_municipio,
			  a.cod_parroquia,
			  a.cod_centro,
			  b.dir_superior,
			  b.coordinacion,
			  b.secretaria,
			  b.direccion,
			  b.division,
			  b.departamento,
			  b.oficina,
			  (SELECT xya.denominacion FROM cugd01_estados          xya where xya.cod_republica=a.cod_presi and xya.cod_estado=a.cod_estado                                                                                                                  GROUP BY xya.denominacion) as  deno_cod_estado,
			  (SELECT xyb.denominacion FROM cugd01_municipios       xyb where xyb.cod_republica=a.cod_presi and xyb.cod_estado=a.cod_estado  and xyb.cod_municipio=a.cod_municipio                                                                           GROUP BY xyb.denominacion) as  deno_cod_municipio,
			  (SELECT xyc.denominacion FROM cugd01_parroquias       xyc where xyc.cod_republica=a.cod_presi and xyc.cod_estado=a.cod_estado  and xyc.cod_municipio=a.cod_municipio and xyc.cod_parroquia = a.cod_parroquia                                   GROUP BY xyc.denominacion) as  deno_cod_parroquia,
			  (SELECT xyd.denominacion FROM cugd01_centros_poblados xyd where xyd.cod_republica=a.cod_presi and xyd.cod_estado=a.cod_estado  and xyd.cod_municipio=a.cod_municipio and xyd.cod_parroquia = a.cod_parroquia and xyd.cod_centro = a.cod_centro GROUP BY xyd.denominacion) as  deno_cod_centro

			FROM
			 cnmd05 a, v_cnmd05_cargos b

			WHERE
			  a.cod_presi     =  " . $cod_presi . "     and
			  a.cod_entidad   =  " . $cod_entidad . "   and
			  a.cod_tipo_inst =  " . $cod_tipo_inst . " and
			  a.cod_inst      =  " . $cod_inst . "      and
			  a.cod_dep       =  " . $cod_dep . "       and
			  " . $sql_a . "
			  " . $sql_b . "
			  a.cod_presi = b.cod_presi AND
			  a.cod_entidad = b.cod_entidad AND
			  a.cod_tipo_inst = b.cod_tipo_inst AND
			  a.cod_inst = b.cod_inst AND
			  a.cod_dep = b.cod_dep AND
			  a.cod_tipo_nomina = b.cod_tipo_nomina AND
			  a.cod_cargo = b.cod_cargo AND
			  a.cod_puesto = b.cod_puesto AND
			  a.cod_tipo_nomina = " . $tipo . "  " . $sql_condicion_actividad . "

			ORDER BY
			  a.cod_presi,
			  a.cod_entidad,
			  a.cod_tipo_inst,
			  a.cod_inst,
			  a.cod_dep,
			  a.cod_tipo_nomina,
			  " . $order_by . "");
            }

            $this->set('tipo', $tipo);
            $this->set('datos', $rs);
            $this->set('presentar_como', $frecuencia);

            $this->set('geografico', $radio_geograf);
            $this->set('administrativo', $radio_admin);

            if ($radio_geograf == 0 && $radio_admin == 0) {
                $this->set('ordenamiento', $ordenamiento);
                $this->set('mes_ingreso', $mes_ingreso);
                $this->render("listado_expediente_codigo");
            } elseif ($radio_geograf == 1 || $radio_geograf == 2) {
                $this->render("listado_expediente_geografico");
            } elseif ($radio_admin == 1 || $radio_admin == 2) {
                $this->render("listado_expediente_administrativo");
            }
        }



//--- Importante ----//
        $this->set('opcion', $opcion);
        $this->set('var1', $var1);



        /*
          $tabla = "cnmd05";
          $vista = "v_cnmd05";

          if($var2==null){
          $this->layout = "ajax";
          $opcion = 1;
          $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
          $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
          $dato = null;
          foreach($year as $year){$dato_year = $year['cfpd01_formulacion']['ano_formular'];}
          if($var1==1){ $year_vista = $dato_year; }else{ $year_vista = $this->ano_ejecucion();}
          $this->set('year',$year_vista);
          $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
          $this->concatena($lista, 'nomina');

          }else{
          $this->layout = "pdf";
          $opcion = 2;

          if(!empty($this->data['reporte3']['consolidacion'])){ $consolidacion = $this->data['reporte3']['consolidacion'];  }else{ $consolidacion = 2;}
          if(!empty($this->data['reporte3']['year'])){ $year = $this->data['reporte3']['year'];  }else{ $year = $this->ano_ejecucion();}
          if(!empty($this->data['reporte3']['tipo'])){ $tipo = $this->data['reporte3']['tipo'];  }else{ $tipo = 1;}
          if(!empty($this->data['reporte3']['frecuencia'])){ $frecuencia = $this->data['reporte3']['frecuencia'];  }else{ $frecuencia = 1;}
          //echo $this->data['reporte3']['radio_ordenamiento_codigo'];

          $sql_a = "";
          $sql_b = "";


          if(!empty($this->data['cscp02_solicitud_cotizacion']['cod_dirsuperior'])){  $sql_a .= "a.cod_dir_superior  = ".$this->data['cscp02_solicitud_cotizacion']['cod_dirsuperior']."  and "; }
          if(!empty($this->data['cscp02_solicitud_cotizacion']['cod_coordinacion'])){ $sql_a .= "a.cod_coordinacion  = ".$this->data['cscp02_solicitud_cotizacion']['cod_coordinacion']."  and "; }
          if(!empty($this->data['cscp02_solicitud_cotizacion']['cod_secretaria'])){   $sql_a .= "a.cod_secretaria  = ".$this->data['cscp02_solicitud_cotizacion']['cod_secretaria']."  and "; }
          if(!empty($this->data['cscp02_solicitud_cotizacion']['cod_direccion'])){    $sql_a .= "a.cod_direccion  = ".$this->data['cscp02_solicitud_cotizacion']['cod_direccion']."  and "; }
          if(!empty($this->data['cscp02_solicitud_cotizacion']['cod_division'])){     $sql_a .= "a.cod_division  = ".$this->data['cscp02_solicitud_cotizacion']['cod_division']."  and "; }
          if(!empty($this->data['cscp02_solicitud_cotizacion']['cod_departamento'])){ $sql_a .= "a.cod_departamento  = ".$this->data['cscp02_solicitud_cotizacion']['cod_departamento']."  and "; }
          if(!empty($this->data['cscp02_solicitud_cotizacion']['cod_oficina'])){      $sql_a .= "a.cod_oficina  = ".$this->data['cscp02_solicitud_cotizacion']['cod_oficina']."  and "; }


          if(!empty($this->data['cobp01_contratoobras']['cod_estado'])){      $sql_b .= "a.cod_estado  = ".$this->data['cobp01_contratoobras']['cod_estado']."  and "; }
          if(!empty($this->data['cobp01_contratoobras']['cod_municipio'])){   $sql_b .= "a.cod_municipio  = ".$this->data['cobp01_contratoobras']['cod_municipio']."  and "; }
          if(!empty($this->data['cobp01_contratoobras']['cod_parroquia'])){   $sql_b .= "a.cod_parroquia  = ".$this->data['cobp01_contratoobras']['cod_parroquia']."  and "; }
          if(!empty($this->data['cobp01_contratoobras']['cod_centro'])){      $sql_b .= "a.cod_centro  = ".$this->data['cobp01_contratoobras']['cod_centro']."  and "; }

          $tipo_nomina = "a.cod_tipo_nomina  = ".$tipo."  and ";

          if($consolidacion==1){
          $dep_A = "";
          $dep_B = "";
          }else if($consolidacion==2){
          $dep_A = " a.cod_dep   =  ".$cod_dep."  and";
          $dep_B = " a.cod_dep, ";
          }//fin function



          $radio_geograf = $this->data['reporte3']['radio_ubicacion_geografica'];
          $radio_admin = $this->data['reporte3']['radio_ubicacion_administrativa'];
          $order_by = "";
          if($radio_geograf==1 && $radio_admin==1){
          $order_by .= " a.cod_cargo, a.cod_ficha";
          }elseif($radio_geograf==2 && $radio_admin==2){
          $order_by .= " a.cod_estado,
          a.cod_municipio,
          a.cod_parroquia,
          a.cod_centro,
          a.cod_dir_superior,
          a.cod_coordinacion,
          a.cod_secretaria,
          a.cod_direccion,
          a.cod_division,
          a.cod_departamento,
          a.cod_oficina,
          a.cod_cargo,
          a.cod_ficha
          ";
          }elseif($radio_geograf==2){
          $order_by .= " a.cod_estado,
          a.cod_municipio,
          a.cod_parroquia,
          a.cod_centro,
          a.cod_dir_superior,
          a.cod_coordinacion,
          a.cod_secretaria,
          a.cod_direccion,
          a.cod_division,
          a.cod_departamento,
          a.cod_oficina,
          a.cod_cargo,
          a.cod_ficha
          ";
          }elseif($radio_admin==2){
          $order_by .= " a.cod_dir_superior,
          a.cod_coordinacion,
          a.cod_secretaria,
          a.cod_direccion,
          a.cod_division,
          a.cod_departamento,
          a.cod_oficina,
          a.cod_cargo,
          a.cod_ficha
          ";
          }

          $rs=$this->v_cfpd05_denominaciones->execute("

          SELECT
          a.cod_presi,
          a.cod_entidad,
          a.cod_tipo_inst,
          a.cod_inst,
          a.cod_dep,
          a.cod_tipo_nomina,
          a.cod_cargo,
          a.cod_puesto,
          a.sueldo_basico,
          a.compensaciones,
          a.primas,
          a.bonos,
          a.cod_dir_superior,
          a.cod_coordinacion,
          a.cod_secretaria,
          a.cod_direccion,
          a.cod_division,
          a.cod_departamento,
          a.cod_oficina,
          (SELECT xa.denominacion FROM cugd02_direccionsuperior xa where xa.cod_tipo_institucion=a.cod_tipo_inst  and xa.cod_institucion=a.cod_inst and xa.cod_dependencia=a.cod_dep and xa.cod_dir_superior=a.cod_dir_superior                                                                                                                                                                                                                                        GROUP BY xa.denominacion) as  deno_cod_dir_superior,
          (SELECT xb.denominacion FROM cugd02_coordinacion xb      where xb.cod_tipo_institucion=a.cod_tipo_inst  and xb.cod_institucion=a.cod_inst and xb.cod_dependencia=a.cod_dep and xb.cod_dir_superior=a.cod_dir_superior and xb.cod_coordinacion=a.cod_coordinacion                                                                                                                                                                                             GROUP BY xb.denominacion) as  deno_cod_coordinacion,
          (SELECT xc.denominacion FROM cugd02_secretaria xc        where xc.cod_tipo_institucion=a.cod_tipo_inst  and xc.cod_institucion=a.cod_inst and xc.cod_dependencia=a.cod_dep and xc.cod_dir_superior=a.cod_dir_superior and xc.cod_coordinacion=a.cod_coordinacion and xc.cod_secretaria=a.cod_secretaria                                                                                                                                                      GROUP BY xc.denominacion) as  deno_cod_secretaria,
          (SELECT xd.denominacion FROM cugd02_direccion xd         where xd.cod_tipo_institucion=a.cod_tipo_inst  and xd.cod_institucion=a.cod_inst and xd.cod_dependencia=a.cod_dep and xd.cod_dir_superior=a.cod_dir_superior and xd.cod_coordinacion=a.cod_coordinacion and xd.cod_secretaria=a.cod_secretaria and xd.cod_direccion=a.cod_direccion                                                                                                                 GROUP BY xd.denominacion) as  deno_cod_direccion,
          (SELECT xe.denominacion FROM cugd02_division xe          where xe.cod_tipo_institucion=a.cod_tipo_inst  and xe.cod_institucion=a.cod_inst and xe.cod_dependencia=a.cod_dep and xe.cod_dir_superior=a.cod_dir_superior and xe.cod_coordinacion=a.cod_coordinacion and xe.cod_secretaria=a.cod_secretaria and xe.cod_direccion=a.cod_direccion  and xe.cod_division=a.cod_division                                                                             GROUP BY xe.denominacion) as  deno_cod_division,
          (SELECT xf.denominacion FROM cugd02_departamento xf      where xf.cod_tipo_institucion=a.cod_tipo_inst  and xf.cod_institucion=a.cod_inst and xf.cod_dependencia=a.cod_dep and xf.cod_dir_superior=a.cod_dir_superior and xf.cod_coordinacion=a.cod_coordinacion and xf.cod_secretaria=a.cod_secretaria and xf.cod_direccion=a.cod_direccion  and xf.cod_division=a.cod_division and xf.cod_departamento=a.cod_departamento                                  GROUP BY xf.denominacion) as  deno_cod_departamento,
          (SELECT xg.denominacion FROM cugd02_oficina xg           where xg.cod_tipo_institucion=a.cod_tipo_inst  and xg.cod_institucion=a.cod_inst and xg.cod_dependencia=a.cod_dep and xg.cod_dir_superior=a.cod_dir_superior and xg.cod_coordinacion=a.cod_coordinacion and xg.cod_secretaria=a.cod_secretaria and xg.cod_direccion=a.cod_direccion  and xg.cod_division=a.cod_division and xg.cod_departamento=a.cod_departamento and xg.cod_oficina=a.cod_oficina GROUP BY xg.denominacion) as  deno_cod_oficina,
          a.cod_estado,
          a.cod_municipio,
          a.cod_parroquia,
          a.cod_centro,
          (SELECT xya.denominacion FROM cugd01_estados          xya where xya.cod_republica=a.cod_presi and xya.cod_estado=a.cod_estado                                                                                                                  GROUP BY xya.denominacion) as  deno_cod_estado,
          (SELECT xyb.denominacion FROM cugd01_municipios       xyb where xyb.cod_republica=a.cod_presi and xyb.cod_estado=a.cod_estado  and xyb.cod_municipio=a.cod_municipio                                                                           GROUP BY xyb.denominacion) as  deno_cod_municipio,
          (SELECT xyc.denominacion FROM cugd01_parroquias       xyc where xyc.cod_republica=a.cod_presi and xyc.cod_estado=a.cod_estado  and xyc.cod_municipio=a.cod_municipio and xyc.cod_parroquia = a.cod_parroquia                                   GROUP BY xyc.denominacion) as  deno_cod_parroquia,
          (SELECT xyd.denominacion FROM cugd01_centros_poblados xyd where xyd.cod_republica=a.cod_presi and xyd.cod_estado=a.cod_estado  and xyd.cod_municipio=a.cod_municipio and xyd.cod_parroquia = a.cod_parroquia and xyd.cod_centro = a.cod_centro GROUP BY xyd.denominacion) as  deno_cod_centro,
          a.condicion_actividad,
          a.ano,
          a.cod_sector,
          (select
          z.deno_sector
          from v_cfpd05_denominaciones z
          where
          z.cod_presi=a.cod_presi and
          z.cod_entidad=a.cod_entidad and
          z.cod_tipo_inst=a.cod_tipo_inst and
          z.cod_inst=a.cod_inst and
          z.cod_dep=a.cod_dep and
          z.ano=a.ano and
          z.cod_sector=a.cod_sector limit 1)
          as deno_sector,
          a.cod_programa,
          (select
          y.deno_programa
          from v_cfpd05_denominaciones y
          where
          y.cod_presi=a.cod_presi and
          y.cod_entidad=a.cod_entidad and
          y.cod_tipo_inst=a.cod_tipo_inst and
          y.cod_inst=a.cod_inst and
          y.cod_dep=a.cod_dep and
          y.ano=a.ano and
          y.cod_sector=a.cod_sector and
          y.cod_programa=a.cod_programa limit 1)
          as deno_programa,
          a.cod_sub_prog,
          (select
          x.deno_sub_prog
          from v_cfpd05_denominaciones x
          where
          x.cod_presi=a.cod_presi and
          x.cod_entidad=a.cod_entidad and
          x.cod_tipo_inst=a.cod_tipo_inst and
          x.cod_inst=a.cod_inst and
          x.cod_dep=a.cod_dep and
          x.ano=a.ano and
          x.cod_sector=a.cod_sector and
          x.cod_programa=a.cod_programa and
          x.cod_sub_prog=a.cod_sub_prog limit 1)
          as deno_sub_prog,
          a.cod_proyecto,
          (select
          w.deno_proyecto
          from v_cfpd05_denominaciones w
          where
          w.cod_presi=a.cod_presi and
          w.cod_entidad=a.cod_entidad and
          w.cod_tipo_inst=a.cod_tipo_inst and
          w.cod_inst=a.cod_inst and
          w.cod_dep=a.cod_dep and
          w.ano=a.ano and
          w.cod_sector=a.cod_sector and
          w.cod_programa=a.cod_programa and
          w.cod_sub_prog=a.cod_sub_prog and
          w.cod_proyecto=a.cod_proyecto limit 1)
          as deno_proyecto,
          a.cod_activ_obra,
          (select
          u.deno_activ_obra
          from v_cfpd05_denominaciones u
          where
          u.cod_presi=a.cod_presi and
          u.cod_entidad=a.cod_entidad and
          u.cod_tipo_inst=a.cod_tipo_inst and
          u.cod_inst=a.cod_inst and
          u.cod_dep=a.cod_dep and
          u.ano=a.ano and
          u.cod_sector=a.cod_sector and
          u.cod_programa=a.cod_programa and
          u.cod_sub_prog=a.cod_sub_prog and
          u.cod_proyecto=a.cod_proyecto and u.cod_activ_obra=a.cod_activ_obra limit 1)
          as deno_activ_obra,
          a.cod_partida,
          (select v.deno_partida
          from v_cfpd05_denominaciones v
          where
          v.cod_presi=a.cod_presi and
          v.cod_entidad=a.cod_entidad and
          v.cod_tipo_inst=a.cod_tipo_inst and
          v.cod_inst=a.cod_inst and
          v.cod_dep=a.cod_dep and
          v.ano=a.ano and
          v.cod_sector=a.cod_sector and
          v.cod_programa=a.cod_programa and
          v.cod_sub_prog=a.cod_sub_prog and
          v.cod_proyecto=a.cod_proyecto and
          v.cod_activ_obra=a.cod_activ_obra and
          v.cod_partida=a.cod_partida limit 1)
          as deno_partida,
          a.cod_generica,
          (select t.deno_generica
          from v_cfpd05_denominaciones t
          where
          t.cod_presi=a.cod_presi and
          t.cod_entidad=a.cod_entidad and
          t.cod_tipo_inst=a.cod_tipo_inst and
          t.cod_inst=a.cod_inst and
          t.cod_dep=a.cod_dep and
          t.ano=a.ano and
          t.cod_sector=a.cod_sector and
          t.cod_programa=a.cod_programa and
          t.cod_sub_prog=a.cod_sub_prog and
          t.cod_proyecto=a.cod_proyecto and
          t.cod_activ_obra=a.cod_activ_obra and
          t.cod_partida=a.cod_partida and
          t.cod_partida=a.cod_partida and
          t.cod_generica=a.cod_generica limit 1)
          as deno_generica,
          a.cod_especifica,
          (select s.deno_especifica
          from v_cfpd05_denominaciones s
          where
          s.cod_presi=a.cod_presi and
          s.cod_entidad=a.cod_entidad and
          s.cod_tipo_inst=a.cod_tipo_inst and
          s.cod_inst=a.cod_inst and
          s.cod_dep=a.cod_dep and
          s.ano=a.ano and
          s.cod_sector=a.cod_sector and
          s.cod_programa=a.cod_programa and
          s.cod_sub_prog=a.cod_sub_prog and
          s.cod_proyecto=a.cod_proyecto and
          s.cod_activ_obra=a.cod_activ_obra and
          s.cod_partida=a.cod_partida and
          s.cod_partida=a.cod_partida and
          s.cod_generica=a.cod_generica and
          s.cod_especifica=a.cod_especifica limit 1)
          as deno_especifica,
          a.cod_sub_espec,
          (select r.deno_sub_espec
          from v_cfpd05_denominaciones r
          where
          r.cod_presi=a.cod_presi and
          r.cod_entidad=a.cod_entidad and
          r.cod_tipo_inst=a.cod_tipo_inst and
          r.cod_inst=a.cod_inst and
          r.cod_dep=a.cod_dep and
          r.ano=a.ano and
          r.cod_sector=a.cod_sector and
          r.cod_programa=a.cod_programa and
          r.cod_sub_prog=a.cod_sub_prog and
          r.cod_proyecto=a.cod_proyecto and
          r.cod_activ_obra=a.cod_activ_obra and
          r.cod_partida=a.cod_partida and
          r.cod_partida=a.cod_partida and
          r.cod_generica=a.cod_generica and
          r.cod_especifica=a.cod_especifica and
          r.cod_sub_espec=a.cod_sub_espec limit 1)
          as deno_sub_espe,
          a.cod_auxiliar,
          (select o.deno_auxiliar
          from v_cfpd05_denominaciones o
          where
          o.cod_presi=a.cod_presi and
          o.cod_entidad=a.cod_entidad and
          o.cod_tipo_inst=a.cod_tipo_inst and
          o.cod_inst=a.cod_inst and
          o.cod_dep=a.cod_dep and
          o.ano=a.ano and
          o.cod_sector=a.cod_sector and
          o.cod_programa=a.cod_programa and
          o.cod_sub_prog=a.cod_sub_prog and
          o.cod_proyecto=a.cod_proyecto and
          o.cod_activ_obra=a.cod_activ_obra and
          o.cod_partida=a.cod_partida and
          o.cod_partida=a.cod_partida and
          o.cod_generica=a.cod_generica and
          o.cod_especifica=a.cod_especifica and
          o.cod_sub_espec=a.cod_sub_espec and
          o.cod_auxiliar=a.cod_auxiliar limit 1)
          as deno_auxiliar,
          a.cod_nivel_i,
          a.cod_nivel_ii,
          a.cod_ficha,
          b.denominacion as denominacion_nomina,
          b.denominacion_devengado,
          b.clasificacion_personal,
          b.frecuencia_cobro,
          b.dias_laborables,
          b.horas_laborables,
          b.descuentos_ley,
          b.mensajes_colectivos,
          b.status_nomina,
          b.cantidad_pagos,
          b.correspondiente,
          b.frecuencia_pago,
          b.numero_recibo,
          b.control_autorizacion,
          b.autorizacion_diskettes,
          b.sueldo_sugerido,
          b.ultimo_cargo,
          b.ultima_ficha,
          b.ano_desde,
          b.ano_hasta,
          b.codigo_transaccion,
          b.dias_cobro,
          c.denominacion_clase as denominacion_cargo,
          (SELECT denominacion FROM cugd02_dependencias b WHERE b.cod_tipo_institucion = a.cod_tipo_inst and b.cod_institucion  =  a.cod_inst and b.cod_dependencia   =  a.cod_dep) as denominacion_dep,
          (SELECT denominacion FROM cnmd04_ocupacion bb WHERE bb.cod_nivel_i = a.cod_nivel_i and bb.cod_nivel_ii  =  a.cod_nivel_ii ) as denominacion_clasificacion,
          (SELECT d.cedula_identidad FROM cnmd06_fichas d where
          d.cod_presi            =  a.cod_presi                and
          d.cod_entidad          =  a.cod_entidad              and
          d.cod_tipo_inst        =  a.cod_tipo_inst            and
          d.cod_inst             =  a.cod_inst                 and
          d.cod_dep              =  a.cod_dep                  and
          d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
          d.cod_cargo            =  a.cod_cargo                and
          d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  as  cedula_identidad_ficha,

          (SELECT e.primer_apellido  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
          d.cod_presi            =  a.cod_presi                and
          d.cod_entidad          =  a.cod_entidad              and
          d.cod_tipo_inst        =  a.cod_tipo_inst            and
          d.cod_inst             =  a.cod_inst                 and
          d.cod_dep              =  a.cod_dep                  and
          d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
          d.cod_cargo            =  a.cod_cargo                and
          d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.primer_apellido)
          as  primer_apellido,

          (SELECT e.segundo_apellido  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
          d.cod_presi            =  a.cod_presi                and
          d.cod_entidad          =  a.cod_entidad              and
          d.cod_tipo_inst        =  a.cod_tipo_inst            and
          d.cod_inst             =  a.cod_inst                 and
          d.cod_dep              =  a.cod_dep                  and
          d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
          d.cod_cargo            =  a.cod_cargo                and
          d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.segundo_apellido)
          as  segundo_apellido,

          (SELECT e.primer_nombre  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
          d.cod_presi            =  a.cod_presi                and
          d.cod_entidad          =  a.cod_entidad              and
          d.cod_tipo_inst        =  a.cod_tipo_inst            and
          d.cod_inst             =  a.cod_inst                 and
          d.cod_dep              =  a.cod_dep                  and
          d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
          d.cod_cargo            =  a.cod_cargo                and
          d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.primer_nombre)
          as  primer_nombre,

          (SELECT e.segundo_nombre  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
          d.cod_presi            =  a.cod_presi                and
          d.cod_entidad          =  a.cod_entidad              and
          d.cod_tipo_inst        =  a.cod_tipo_inst            and
          d.cod_inst             =  a.cod_inst                 and
          d.cod_dep              =  a.cod_dep                  and
          d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
          d.cod_cargo            =  a.cod_cargo                and
          d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.segundo_nombre)
          as  segundo_nombre,


          (SELECT e.fecha_nacimiento  FROM cnmd06_datos_personales e where e.cedula_identidad=(SELECT d.cedula_identidad FROM cnmd06_fichas d where
          d.cod_presi            =  a.cod_presi                and
          d.cod_entidad          =  a.cod_entidad              and
          d.cod_tipo_inst        =  a.cod_tipo_inst            and
          d.cod_inst             =  a.cod_inst                 and
          d.cod_dep              =  a.cod_dep                  and
          d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
          d.cod_cargo            =  a.cod_cargo                and
          d.cod_ficha            =  a.cod_ficha  GROUP BY d.cedula_identidad)  GROUP BY e.fecha_nacimiento)
          as  fecha_nacimiento,


          (SELECT d.fecha_ingreso FROM cnmd06_fichas d where
          d.cod_presi            =  a.cod_presi                and
          d.cod_entidad          =  a.cod_entidad              and
          d.cod_tipo_inst        =  a.cod_tipo_inst            and
          d.cod_inst             =  a.cod_inst                 and
          d.cod_dep              =  a.cod_dep                  and
          d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
          d.cod_cargo            =  a.cod_cargo                and
          d.cod_ficha            =  a.cod_ficha  GROUP BY d.fecha_ingreso)
          as  fecha_ingreso,


          (SELECT d.cuenta_bancaria FROM cnmd06_fichas d where
          d.cod_presi            =  a.cod_presi                and
          d.cod_entidad          =  a.cod_entidad              and
          d.cod_tipo_inst        =  a.cod_tipo_inst            and
          d.cod_inst             =  a.cod_inst                 and
          d.cod_dep              =  a.cod_dep                  and
          d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
          d.cod_cargo            =  a.cod_cargo                and
          d.cod_ficha            =  a.cod_ficha  GROUP BY d.cuenta_bancaria)
          as  cuenta_bancaria,


          (SELECT ent.denominacion FROM cstd01_entidades_bancarias ent WHERE cod_entidad_bancaria=(SELECT d.cod_entidad_bancaria FROM cnmd06_fichas d where
          d.cod_presi            =  a.cod_presi                and
          d.cod_entidad          =  a.cod_entidad              and
          d.cod_tipo_inst        =  a.cod_tipo_inst            and
          d.cod_inst             =  a.cod_inst                 and
          d.cod_dep              =  a.cod_dep                  and
          d.cod_tipo_nomina      =  a.cod_tipo_nomina          and
          d.cod_cargo            =  a.cod_cargo                and
          d.cod_ficha            =  a.cod_ficha  GROUP BY d.cod_entidad_bancaria))
          as  banco,

          (SELECT v.denominacion_profesion FROM v_cnmd06_fichas_datos_personales v where
          v.cod_presi            =  a.cod_presi                and
          v.cod_entidad          =  a.cod_entidad              and
          v.cod_tipo_inst        =  a.cod_tipo_inst            and
          v.cod_inst             =  a.cod_inst                 and
          v.cod_dep              =  a.cod_dep                  and
          v.cod_tipo_nomina      =  a.cod_tipo_nomina          and
          v.cod_cargo            =  a.cod_cargo                and
          v.cod_ficha            =  a.cod_ficha)  as  denominacion_profesion

          FROM

          ".$tabla." a, cnmd01 b, ".$vista." c

          WHERE

          a.cod_presi            =  ".$cod_presi."             and
          a.cod_entidad          =  ".$cod_entidad."           and
          a.cod_tipo_inst        =  ".$cod_tipo_inst."         and
          a.cod_inst             =  ".$cod_inst."              and
          ".$dep_A."
          ".$tipo_nomina."
          ".$sql_a."
          ".$sql_b."
          b.cod_presi            =  a.cod_presi                and
          b.cod_entidad          =  a.cod_entidad              and
          b.cod_tipo_inst        =  a.cod_tipo_inst            and
          b.cod_inst             =  a.cod_inst                 and
          b.cod_dep              =  a.cod_dep                  and
          b.cod_tipo_nomina      =  a.cod_tipo_nomina          and
          c.cod_presi            =  a.cod_presi                and
          c.cod_entidad          =  a.cod_entidad              and
          c.cod_tipo_inst        =  a.cod_tipo_inst            and
          c.cod_inst             =  a.cod_inst                 and
          c.cod_dep              =  a.cod_dep                  and
          c.cod_tipo_nomina      =  a.cod_tipo_nomina          and
          c.cod_cargo            =  a.cod_cargo                and
          c.cod_puesto           =  a.cod_puesto

          ORDER BY
          a.cod_presi,
          a.cod_entidad,
          a.cod_tipo_inst,
          a.cod_inst,
          a.cod_dep,
          a.cod_tipo_nomina,
          ".$order_by."
          ASC

          ; ");

          //pr($rs);

          $this->set('tipo', $tipo);
          $this->set('datos', $rs);
          $this->set('presentar_como', $frecuencia);

          $this->set('geografico', $radio_geograf);
          $this->set('administrativo', $radio_admin);

          }//fin $tipo

          $this->set('opcion', $opcion);
          $this->set('var1',$var1);
         */
    }

    function pordenamiento($var_opc = null) {
        $this->layout = "ajax";
        $this->set('var_opc', $var_opc);
    }

    function listado_expediente_personal_actual($var1 = null, $var2 = null) {
        $tabla = "";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');

        if ($var2 == null) {
            $this->layout = "ajax";
            $opcion = 1;
            $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst;
            $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
            $dato = null;
            foreach ($year as $year) {
                $dato_year = $year['cfpd01_formulacion']['ano_formular'];
            }
            if ($var1 == 1) {
                $year_vista = $dato_year;
            } else {
                $year_vista = $this->ano_ejecucion();
            }
            $this->set('year', $year_vista);
            $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
            $this->concatenaN($lista, 'nomina');
        } else {
            $this->layout = "pdf";
            $opcion = 2;

            set_time_limit(0);


            $sql_a = "";
            $sql_b = "";

            $radio_geograf = "";
            $radio_admin = "";


            if (!empty($this->data['reporte3']['consolidacion'])) {
                $consolidacion = $this->data['reporte3']['consolidacion'];
            } else {
                $consolidacion = 2;
            }
            if (!empty($this->data['reporte3']['year'])) {
                $year = $this->data['reporte3']['year'];
            } else {
                $year = $this->ano_ejecucion();
            }
            if (!empty($this->data['reporte3']['tipo'])) {
                $tipo = $this->data['reporte3']['tipo'];
            } else {
                $tipo = 1;
            }
            if (!empty($this->data['reporte3']['frecuencia'])) {
                $frecuencia = $this->data['reporte3']['frecuencia'];
            } else {
                $frecuencia = 1;
            }


            if (!empty($this->data['reporte3']['radio_condicion_actividad'])) {
                $radio_condicion_actividad = $this->data['reporte3']['radio_condicion_actividad'];
            } else {
                $radio_condicion_actividad = 20;
            }

            if (!empty($this->data['reporte3']['radio_ordenamiento_codigo'])) {
                $ordenamiento = $this->data['reporte3']['radio_ordenamiento_codigo'];
            } else {
                $ordenamiento = 1;
            }
            if (!empty($this->data['reporte3']['radio_ubicacion_geografica'])) {
                $radio_geograf = $this->data['reporte3']['radio_ubicacion_geografica'];
            } else {
                $radio_geograf = 0;
            }
            if (!empty($this->data['reporte3']['radio_ubicacion_administrativa'])) {
                $radio_admin = $this->data['reporte3']['radio_ubicacion_administrativa'];
            } else {
                $radio_admin = 0;
            }

            if ($radio_geograf == 2) {
                if (!empty($this->data['cobp01_contratoobras']['cod_estado'])) {
                    $sql_b .= "a.cod_estado  = " . $this->data['cobp01_contratoobras']['cod_estado'] . "  and ";
                }
                if (!empty($this->data['cobp01_contratoobras']['cod_municipio'])) {
                    $sql_b .= "a.cod_municipio  = " . $this->data['cobp01_contratoobras']['cod_municipio'] . "  and ";
                }
                if (!empty($this->data['cobp01_contratoobras']['cod_parroquia'])) {
                    $sql_b .= "a.cod_parroquia  = " . $this->data['cobp01_contratoobras']['cod_parroquia'] . "  and ";
                }
                if (!empty($this->data['cobp01_contratoobras']['cod_centro'])) {
                    $sql_b .= "a.cod_centro  = " . $this->data['cobp01_contratoobras']['cod_centro'] . "  and ";
                }

                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_dirsuperior'])) {
                    $sql_a .= "a.cod_dir_superior  = " . $this->data['cscp02_solicitud_cotizacion']['cod_dirsuperior'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_coordinacion'])) {
                    $sql_a .= "a.cod_coordinacion  = " . $this->data['cscp02_solicitud_cotizacion']['cod_coordinacion'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_secretaria'])) {
                    $sql_a .= "a.cod_secretaria  = " . $this->data['cscp02_solicitud_cotizacion']['cod_secretaria'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_direccion'])) {
                    $sql_a .= "a.cod_direccion  = " . $this->data['cscp02_solicitud_cotizacion']['cod_direccion'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_division'])) {
                    $sql_a .= "a.cod_division  = " . $this->data['cscp02_solicitud_cotizacion']['cod_division'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_departamento'])) {
                    $sql_a .= "a.cod_departamento  = " . $this->data['cscp02_solicitud_cotizacion']['cod_departamento'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_oficina'])) {
                    $sql_a .= "a.cod_oficina  = " . $this->data['cscp02_solicitud_cotizacion']['cod_oficina'] . "  and ";
                }
            }

            if ($radio_admin == 2) {
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_dirsuperior'])) {
                    $sql_a .= "a.cod_dir_superior  = " . $this->data['cscp02_solicitud_cotizacion']['cod_dirsuperior'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_coordinacion'])) {
                    $sql_a .= "a.cod_coordinacion  = " . $this->data['cscp02_solicitud_cotizacion']['cod_coordinacion'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_secretaria'])) {
                    $sql_a .= "a.cod_secretaria  = " . $this->data['cscp02_solicitud_cotizacion']['cod_secretaria'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_direccion'])) {
                    $sql_a .= "a.cod_direccion  = " . $this->data['cscp02_solicitud_cotizacion']['cod_direccion'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_division'])) {
                    $sql_a .= "a.cod_division  = " . $this->data['cscp02_solicitud_cotizacion']['cod_division'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_departamento'])) {
                    $sql_a .= "a.cod_departamento  = " . $this->data['cscp02_solicitud_cotizacion']['cod_departamento'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_oficina'])) {
                    $sql_a .= "a.cod_oficina  = " . $this->data['cscp02_solicitud_cotizacion']['cod_oficina'] . "  and ";
                }
            }

//echo "<br/>".$radio_geograf;
//echo "<br/>".$radio_admin;
//echo "<br />".$ordenamiento;



            if ($radio_condicion_actividad == 20) {
                $sql_condicion_actividad = "";
                $_SESSION["sql_condicion_actividad"] = "";
            } else {
                $sql_condicion_actividad = " and b.condicion_actividad_ficha='" . $radio_condicion_actividad . "'   ";
                $condicion_aaa = array(20 => "TODOS", 1 => "Activo", 2 => "Permiso no Remunerado", 3 => "Comisión de Servicio", 4 => "Vacaciones", 5 => "Suspendido", 6 => "Retirado", 7 => "Ascenso", 8 => "Reposo");
                $_SESSION["sql_condicion_actividad"] = "CONDICIÓN DE ACTIVIDAD: " . $condicion_aaa[$radio_condicion_actividad];
            }

            $order_by = "";
            if ($radio_geograf == 0 && $radio_admin == 0) {
                //echo "entro en 1";
                switch ($ordenamiento) {
                    case '1': $order_by .= " a.cod_tipo_nomina, a.cod_cargo, a.cod_ficha"; /* echo "<br />option: 1"; */ break;
                    case '2': $order_by .= " a.cod_tipo_nomina, a.cod_ficha, a.cod_cargo"; /* echo "<br />option: 2"; */ break;
                    case '3': $order_by .= " a.cod_tipo_nomina, a.cod_puesto, a.cod_cargo, a.cod_ficha"; /* echo "<br />option: 3"; */ break;
                    case '4': $order_by .= " a.cod_tipo_nomina, cedula_identidad, a.cod_cargo, a.cod_ficha"; /* echo "<br />option: 4"; */ break;
                    case '5': $order_by .= " a.cod_tipo_nomina, primer_apellido, segundo_apellido, primer_nombre, segundo_nombre, a.cod_cargo, a.cod_ficha"; /* echo "<br />option: 5"; */ break;
                    case '6': $order_by .= " a.cod_tipo_nomina, fecha_nacimiento, a.cod_cargo, a.cod_ficha"; /* echo "<br />option: 6"; */ break;
                    case '7': $order_by .= " a.cod_tipo_nomina, denominacion_profesion, a.cod_cargo, a.cod_ficha"; /* echo "<br />option: 7"; */ break;
                    case '8': $order_by .= " a.cod_tipo_nomina, fecha_ingreso, a.cod_cargo, a.cod_ficha"; /* echo "<br />option: 8"; */ break;
                    case '9': $order_by .= " a.cod_tipo_nomina, fecha_condicion, a.cod_cargo, a.cod_ficha"; /* echo "<br />option: 8"; */ break;
                    default: break;
                }
            } elseif ($radio_geograf == 1 || $radio_geograf == 2) {
                //echo "entro en 2";
                $order_by .= " a.cod_estado,
						a.cod_municipio,
					    a.cod_parroquia,
					    a.cod_centro,
						a.cod_dir_superior,
					  	a.cod_coordinacion,
					  	a.cod_secretaria,
					  	a.cod_direccion,
					  	a.cod_division,
					  	a.cod_departamento,
					  	a.cod_oficina,
					  	a.cod_cargo,
					  	a.cod_ficha";
            } elseif ($radio_admin == 1 || $radio_admin == 2) {
                //echo "entro en 3";
                $order_by .= " a.cod_dir_superior,
					  	a.cod_coordinacion,
					  	a.cod_secretaria,
					  	a.cod_direccion,
					  	a.cod_division,
					  	a.cod_departamento,
					  	a.cod_oficina,
					  	a.cod_cargo,
					  	a.cod_ficha";
            }




            if ($radio_condicion_actividad != 20 && $radio_condicion_actividad != 6) {


                $rs = $this->v_cfpd05_denominaciones->execute("
			SELECT
			  a.cod_tipo_nomina,
			  (SELECT cn01.denominacion FROM cnmd01 cn01 WHERE cn01.cod_presi = a.cod_presi AND cn01.cod_entidad = a.cod_entidad AND cn01.cod_tipo_inst = a.cod_tipo_inst AND cn01.cod_inst = a.cod_inst AND cn01.cod_dep = a.cod_dep AND cn01.cod_tipo_nomina = a.cod_tipo_nomina) as denominacion_nomina,
			  (SELECT cn01.frecuencia_cobro FROM cnmd01 cn01 WHERE cn01.cod_presi = a.cod_presi AND cn01.cod_entidad = a.cod_entidad AND cn01.cod_tipo_inst = a.cod_tipo_inst AND cn01.cod_inst = a.cod_inst AND cn01.cod_dep = a.cod_dep AND cn01.cod_tipo_nomina = a.cod_tipo_nomina) as frecuencia_cobro,
			  (SELECT ficha.cedula_identidad FROM cnmd06_fichas ficha WHERE ficha.cod_presi = a.cod_presi AND ficha.cod_entidad = a.cod_entidad AND ficha.cod_tipo_inst = a.cod_tipo_inst AND ficha.cod_inst = a.cod_inst AND ficha.cod_dep = a.cod_dep AND ficha.cod_tipo_nomina = a.cod_tipo_nomina AND ficha.cod_cargo = a.cod_cargo AND ficha.cod_ficha = a.cod_ficha) as cedula_identidad_ficha,
			  (SELECT d.cedula_identidad FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as cedula_identidad,
			  (SELECT d.primer_nombre FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as primer_nombre,
			  (SELECT d.segundo_nombre FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as segundo_nombre,
			  (SELECT d.primer_apellido FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as primer_apellido,
			  (SELECT d.segundo_apellido FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as segundo_apellido,
			  (SELECT d.fecha_nacimiento FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as fecha_nacimiento,
			  (SELECT d.cod_profesion FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as cod_profesion,
			  (SELECT c.denominacion FROM cnmd06_profesiones c WHERE c.cod_profesion=(SELECT d.cod_profesion FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha))) as denominacion_profesion,
			  (SELECT ficha.fecha_ingreso   FROM cnmd06_fichas ficha WHERE ficha.cod_presi = a.cod_presi AND ficha.cod_entidad = a.cod_entidad AND ficha.cod_tipo_inst = a.cod_tipo_inst AND ficha.cod_inst = a.cod_inst AND ficha.cod_dep = a.cod_dep AND ficha.cod_tipo_nomina = a.cod_tipo_nomina AND ficha.cod_cargo = a.cod_cargo AND ficha.cod_ficha = a.cod_ficha) as fecha_ingreso,
			  (SELECT ficha.fecha_condicion FROM cnmd06_fichas ficha WHERE ficha.cod_presi = a.cod_presi AND ficha.cod_entidad = a.cod_entidad AND ficha.cod_tipo_inst = a.cod_tipo_inst AND ficha.cod_inst = a.cod_inst AND ficha.cod_dep = a.cod_dep AND ficha.cod_tipo_nomina = a.cod_tipo_nomina AND ficha.cod_cargo = a.cod_cargo AND ficha.cod_ficha = a.cod_ficha) as fecha_condicion,
			  (SELECT ficha.cod_entidad_bancaria FROM cnmd06_fichas ficha WHERE ficha.cod_presi = a.cod_presi AND ficha.cod_entidad = a.cod_entidad AND ficha.cod_tipo_inst = a.cod_tipo_inst AND ficha.cod_inst = a.cod_inst AND ficha.cod_dep = a.cod_dep AND ficha.cod_tipo_nomina = a.cod_tipo_nomina AND ficha.cod_cargo = a.cod_cargo AND ficha.cod_ficha = a.cod_ficha) as cod_entidad_bancaria,
			  (SELECT ficha.cuenta_bancaria FROM cnmd06_fichas ficha WHERE ficha.cod_presi = a.cod_presi AND ficha.cod_entidad = a.cod_entidad AND ficha.cod_tipo_inst = a.cod_tipo_inst AND ficha.cod_inst = a.cod_inst AND ficha.cod_dep = a.cod_dep AND ficha.cod_tipo_nomina = a.cod_tipo_nomina AND ficha.cod_cargo = a.cod_cargo AND ficha.cod_ficha = a.cod_ficha) as cuenta_bancaria,
			  (SELECT ban.denominacion FROM cstd01_entidades_bancarias ban WHERE ban.cod_entidad_bancaria=(SELECT ficha.cod_entidad_bancaria FROM cnmd06_fichas ficha WHERE ficha.cod_presi = a.cod_presi AND ficha.cod_entidad = a.cod_entidad AND ficha.cod_tipo_inst = a.cod_tipo_inst AND ficha.cod_inst = a.cod_inst AND ficha.cod_dep = a.cod_dep AND ficha.cod_tipo_nomina = a.cod_tipo_nomina AND ficha.cod_cargo = a.cod_cargo AND ficha.cod_ficha = a.cod_ficha)) as banco,
			  a.cod_cargo,
			  a.cod_puesto,
			  b.denominacion_clase AS denominacion_cargo,
			  a.cod_ficha,
			  a.sueldo_basico,
			  a.compensaciones,
			  a.primas,
			  a.bonos,
			  a.cod_dir_superior,
			  a.cod_coordinacion,
			  a.cod_secretaria,
			  a.cod_direccion,
			  a.cod_division,
			  a.cod_departamento,
			  a.cod_oficina,
			  a.cod_estado,
			  a.cod_municipio,
			  a.cod_parroquia,
			  a.cod_centro,
			  b.dir_superior,
			  b.coordinacion,
			  b.secretaria,
			  b.direccion,
			  b.division,
			  b.departamento,
			  b.oficina,
			  (
              select SUM(aaa.monto_cuota) from cnmd07_transacciones_actuales aaa, cnmd09_transa_queconforma_sueldointegral bbb

                                                                             where aaa.cod_presi     =  " . $cod_presi . "     and
																				   aaa.cod_entidad   =  " . $cod_entidad . "   and
																				   aaa.cod_tipo_inst =  " . $cod_tipo_inst . " and
																				   aaa.cod_inst      =  " . $cod_inst . "      and
																				   aaa.cod_dep       =  " . $cod_dep . "       and
																				   aaa.cod_tipo_nomina = a.cod_tipo_nomina and
																				   aaa.cod_cargo       = a.cod_cargo       and
																				   aaa.cod_ficha       = a.cod_ficha       and

																				   bbb.cod_presi     =  " . $cod_presi . "     and
																				   bbb.cod_entidad   =  " . $cod_entidad . "   and
																				   bbb.cod_tipo_inst =  " . $cod_tipo_inst . " and
																				   bbb.cod_inst      =  " . $cod_inst . "      and
																				   bbb.cod_dep       =  " . $cod_dep . "       and
																				   bbb.cod_tipo_nomina = a.cod_tipo_nomina and

																				   aaa.cod_tipo_transaccion = bbb.cod_tipo_transaccion and
																				   aaa.cod_transaccion      = bbb.cod_transaccion
			  ) as asignaciones_sueldo_integral,
			  (SELECT xya.denominacion FROM cugd01_estados          xya where xya.cod_republica=a.cod_presi and xya.cod_estado=a.cod_estado                                                                                                                  GROUP BY xya.denominacion) as  deno_cod_estado,
			  (SELECT xyb.denominacion FROM cugd01_municipios       xyb where xyb.cod_republica=a.cod_presi and xyb.cod_estado=a.cod_estado  and xyb.cod_municipio=a.cod_municipio                                                                           GROUP BY xyb.denominacion) as  deno_cod_municipio,
			  (SELECT xyc.denominacion FROM cugd01_parroquias       xyc where xyc.cod_republica=a.cod_presi and xyc.cod_estado=a.cod_estado  and xyc.cod_municipio=a.cod_municipio and xyc.cod_parroquia = a.cod_parroquia                                   GROUP BY xyc.denominacion) as  deno_cod_parroquia,
			  (SELECT xyd.denominacion FROM cugd01_centros_poblados xyd where xyd.cod_republica=a.cod_presi and xyd.cod_estado=a.cod_estado  and xyd.cod_municipio=a.cod_municipio and xyd.cod_parroquia = a.cod_parroquia and xyd.cod_centro = a.cod_centro GROUP BY xyd.denominacion) as  deno_cod_centro

			FROM
			 cnmd05 a, v_cnmd05_cargos_todo b

			WHERE
			  a.cod_presi     =  " . $cod_presi . "     and
			  a.cod_entidad   =  " . $cod_entidad . "   and
			  a.cod_tipo_inst =  " . $cod_tipo_inst . " and
			  a.cod_inst      =  " . $cod_inst . "      and
			  a.cod_dep       =  " . $cod_dep . "       and
			  " . $sql_a . "
			  " . $sql_b . "
			  a.cod_presi = b.cod_presi AND
			  a.cod_entidad = b.cod_entidad AND
			  a.cod_tipo_inst = b.cod_tipo_inst AND
			  a.cod_inst = b.cod_inst AND
			  a.cod_dep = b.cod_dep AND
			  a.cod_tipo_nomina = b.cod_tipo_nomina AND
			  a.cod_cargo = b.cod_cargo AND
			  a.cod_puesto = b.cod_puesto AND
			  a.cod_tipo_nomina = " . $tipo . "  " . $sql_condicion_actividad . "

			ORDER BY
			  a.cod_presi,
			  a.cod_entidad,
			  a.cod_tipo_inst,
			  a.cod_inst,
			  a.cod_dep,
			  a.cod_tipo_nomina,
			  " . $order_by . "");
            } else if ($radio_condicion_actividad == 6) {


                $rs = $this->v_cfpd05_denominaciones->execute("
			SELECT
			  a.cod_tipo_nomina,
			  (SELECT cn01.denominacion FROM cnmd01 cn01 WHERE cn01.cod_presi = b.cod_presi AND cn01.cod_entidad = b.cod_entidad AND cn01.cod_tipo_inst = b.cod_tipo_inst AND cn01.cod_inst = b.cod_inst AND cn01.cod_dep = b.cod_dep AND cn01.cod_tipo_nomina = b.cod_tipo_nomina) as denominacion_nomina,
			  (SELECT cn01.frecuencia_cobro FROM cnmd01 cn01 WHERE cn01.cod_presi = b.cod_presi AND cn01.cod_entidad = b.cod_entidad AND cn01.cod_tipo_inst = b.cod_tipo_inst AND cn01.cod_inst = b.cod_inst AND cn01.cod_dep = b.cod_dep AND cn01.cod_tipo_nomina = b.cod_tipo_nomina) as frecuencia_cobro,
			  (SELECT ficha.cedula_identidad FROM cnmd06_fichas ficha WHERE ficha.cod_presi = b.cod_presi AND ficha.cod_entidad = b.cod_entidad AND ficha.cod_tipo_inst = b.cod_tipo_inst AND ficha.cod_inst = b.cod_inst AND ficha.cod_dep = b.cod_dep AND ficha.cod_tipo_nomina = b.cod_tipo_nomina AND ficha.cod_cargo = b.cod_cargo AND ficha.cod_ficha = b.cod_ficha) as cedula_identidad_ficha,
			  (SELECT d.cedula_identidad FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = b.cod_presi AND f.cod_entidad = b.cod_entidad AND f.cod_tipo_inst = b.cod_tipo_inst AND f.cod_inst = b.cod_inst AND f.cod_dep = b.cod_dep AND f.cod_tipo_nomina = b.cod_tipo_nomina AND f.cod_cargo = b.cod_cargo AND f.cod_ficha = b.cod_ficha)) as cedula_identidad,
			  (SELECT d.primer_nombre FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = b.cod_presi AND f.cod_entidad = b.cod_entidad AND f.cod_tipo_inst = b.cod_tipo_inst AND f.cod_inst = b.cod_inst AND f.cod_dep = b.cod_dep AND f.cod_tipo_nomina = b.cod_tipo_nomina AND f.cod_cargo = b.cod_cargo AND f.cod_ficha = b.cod_ficha)) as primer_nombre,
			  (SELECT d.segundo_nombre FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = b.cod_presi AND f.cod_entidad = b.cod_entidad AND f.cod_tipo_inst = b.cod_tipo_inst AND f.cod_inst = b.cod_inst AND f.cod_dep = b.cod_dep AND f.cod_tipo_nomina = b.cod_tipo_nomina AND f.cod_cargo = b.cod_cargo AND f.cod_ficha = b.cod_ficha)) as segundo_nombre,
			  (SELECT d.primer_apellido FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = b.cod_presi AND f.cod_entidad = b.cod_entidad AND f.cod_tipo_inst = b.cod_tipo_inst AND f.cod_inst = b.cod_inst AND f.cod_dep = b.cod_dep AND f.cod_tipo_nomina = b.cod_tipo_nomina AND f.cod_cargo = b.cod_cargo AND f.cod_ficha = b.cod_ficha)) as primer_apellido,
			  (SELECT d.segundo_apellido FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = b.cod_presi AND f.cod_entidad = b.cod_entidad AND f.cod_tipo_inst = b.cod_tipo_inst AND f.cod_inst = b.cod_inst AND f.cod_dep = b.cod_dep AND f.cod_tipo_nomina = b.cod_tipo_nomina AND f.cod_cargo = b.cod_cargo AND f.cod_ficha = b.cod_ficha)) as segundo_apellido,
			  (SELECT d.fecha_nacimiento FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = b.cod_presi AND f.cod_entidad = b.cod_entidad AND f.cod_tipo_inst = b.cod_tipo_inst AND f.cod_inst = b.cod_inst AND f.cod_dep = b.cod_dep AND f.cod_tipo_nomina = b.cod_tipo_nomina AND f.cod_cargo = b.cod_cargo AND f.cod_ficha = b.cod_ficha)) as fecha_nacimiento,
			  (SELECT d.cod_profesion FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = b.cod_presi AND f.cod_entidad = b.cod_entidad AND f.cod_tipo_inst = b.cod_tipo_inst AND f.cod_inst = b.cod_inst AND f.cod_dep = b.cod_dep AND f.cod_tipo_nomina = b.cod_tipo_nomina AND f.cod_cargo = b.cod_cargo AND f.cod_ficha = b.cod_ficha)) as cod_profesion,
			  (SELECT c.denominacion FROM cnmd06_profesiones c WHERE c.cod_profesion=(SELECT d.cod_profesion FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = b.cod_presi AND f.cod_entidad = b.cod_entidad AND f.cod_tipo_inst = b.cod_tipo_inst AND f.cod_inst = b.cod_inst AND f.cod_dep = b.cod_dep AND f.cod_tipo_nomina = b.cod_tipo_nomina AND f.cod_cargo = b.cod_cargo AND f.cod_ficha = b.cod_ficha))) as denominacion_profesion,
			  (SELECT ficha.fecha_ingreso   FROM cnmd06_fichas ficha WHERE ficha.cod_presi = b.cod_presi AND ficha.cod_entidad = b.cod_entidad AND ficha.cod_tipo_inst = b.cod_tipo_inst AND ficha.cod_inst = b.cod_inst AND ficha.cod_dep = b.cod_dep AND ficha.cod_tipo_nomina = b.cod_tipo_nomina AND ficha.cod_cargo = b.cod_cargo AND ficha.cod_ficha = b.cod_ficha) as fecha_ingreso,
			  (SELECT ficha.fecha_condicion FROM cnmd06_fichas ficha WHERE ficha.cod_presi = b.cod_presi AND ficha.cod_entidad = b.cod_entidad AND ficha.cod_tipo_inst = b.cod_tipo_inst AND ficha.cod_inst = b.cod_inst AND ficha.cod_dep = b.cod_dep AND ficha.cod_tipo_nomina = b.cod_tipo_nomina AND ficha.cod_cargo = b.cod_cargo AND ficha.cod_ficha = b.cod_ficha) as fecha_condicion,
			  (SELECT ficha.cod_entidad_bancaria FROM cnmd06_fichas ficha WHERE ficha.cod_presi = b.cod_presi AND ficha.cod_entidad = b.cod_entidad AND ficha.cod_tipo_inst = b.cod_tipo_inst AND ficha.cod_inst = b.cod_inst AND ficha.cod_dep = b.cod_dep AND ficha.cod_tipo_nomina = b.cod_tipo_nomina AND ficha.cod_cargo = b.cod_cargo AND ficha.cod_ficha = b.cod_ficha) as cod_entidad_bancaria,
			  (SELECT ficha.cuenta_bancaria FROM cnmd06_fichas ficha WHERE ficha.cod_presi = b.cod_presi AND ficha.cod_entidad = b.cod_entidad AND ficha.cod_tipo_inst = b.cod_tipo_inst AND ficha.cod_inst = b.cod_inst AND ficha.cod_dep = b.cod_dep AND ficha.cod_tipo_nomina = b.cod_tipo_nomina AND ficha.cod_cargo = b.cod_cargo AND ficha.cod_ficha = b.cod_ficha) as cuenta_bancaria,
			  (SELECT ban.denominacion FROM cstd01_entidades_bancarias ban WHERE ban.cod_entidad_bancaria=(SELECT ficha.cod_entidad_bancaria FROM cnmd06_fichas ficha WHERE ficha.cod_presi = b.cod_presi AND ficha.cod_entidad = b.cod_entidad AND ficha.cod_tipo_inst = b.cod_tipo_inst AND ficha.cod_inst = b.cod_inst AND ficha.cod_dep = b.cod_dep AND ficha.cod_tipo_nomina = b.cod_tipo_nomina AND ficha.cod_cargo = b.cod_cargo AND ficha.cod_ficha = b.cod_ficha)) as banco,
			  a.cod_cargo,
			  a.cod_puesto,
			  b.denominacion_clase AS denominacion_cargo,
			  b.cod_ficha,
			  a.sueldo_basico,
			  a.compensaciones,
			  a.primas,
			  a.bonos,
			  a.cod_dir_superior,
			  a.cod_coordinacion,
			  a.cod_secretaria,
			  a.cod_direccion,
			  a.cod_division,
			  a.cod_departamento,
			  a.cod_oficina,
			  a.cod_estado,
			  a.cod_municipio,
			  a.cod_parroquia,
			  a.cod_centro,
			  b.dir_superior,
			  b.coordinacion,
			  b.secretaria,
			  b.direccion,
			  b.division,
			  b.departamento,
			  b.oficina,

			  (
              select SUM(aaa.monto_cuota) from cnmd07_transacciones_actuales aaa, cnmd09_transa_queconforma_sueldointegral bbb

                                                                             where aaa.cod_presi     =  " . $cod_presi . "     and
																				   aaa.cod_entidad   =  " . $cod_entidad . "   and
																				   aaa.cod_tipo_inst =  " . $cod_tipo_inst . " and
																				   aaa.cod_inst      =  " . $cod_inst . "      and
																				   aaa.cod_dep       =  " . $cod_dep . "       and
																				   aaa.cod_tipo_nomina = a.cod_tipo_nomina and
																				   aaa.cod_cargo       = a.cod_cargo       and
																				   aaa.cod_ficha       = a.cod_ficha       and

																				   bbb.cod_presi     =  " . $cod_presi . "     and
																				   bbb.cod_entidad   =  " . $cod_entidad . "   and
																				   bbb.cod_tipo_inst =  " . $cod_tipo_inst . " and
																				   bbb.cod_inst      =  " . $cod_inst . "      and
																				   bbb.cod_dep       =  " . $cod_dep . "       and
																				   bbb.cod_tipo_nomina = a.cod_tipo_nomina and

																				   aaa.cod_tipo_transaccion = bbb.cod_tipo_transaccion and
																				   aaa.cod_transaccion      = bbb.cod_transaccion
			  ) as asignaciones_sueldo_integral,

			  (SELECT xya.denominacion FROM cugd01_estados          xya where xya.cod_republica=a.cod_presi and xya.cod_estado=a.cod_estado                                                                                                                  GROUP BY xya.denominacion) as  deno_cod_estado,
			  (SELECT xyb.denominacion FROM cugd01_municipios       xyb where xyb.cod_republica=a.cod_presi and xyb.cod_estado=a.cod_estado  and xyb.cod_municipio=a.cod_municipio                                                                           GROUP BY xyb.denominacion) as  deno_cod_municipio,
			  (SELECT xyc.denominacion FROM cugd01_parroquias       xyc where xyc.cod_republica=a.cod_presi and xyc.cod_estado=a.cod_estado  and xyc.cod_municipio=a.cod_municipio and xyc.cod_parroquia = a.cod_parroquia                                   GROUP BY xyc.denominacion) as  deno_cod_parroquia,
			  (SELECT xyd.denominacion FROM cugd01_centros_poblados xyd where xyd.cod_republica=a.cod_presi and xyd.cod_estado=a.cod_estado  and xyd.cod_municipio=a.cod_municipio and xyd.cod_parroquia = a.cod_parroquia and xyd.cod_centro = a.cod_centro GROUP BY xyd.denominacion) as  deno_cod_centro

			FROM
			 cnmd05 a, v_cnmd05_cargos_2 b

			WHERE
			  a.cod_presi     =  " . $cod_presi . "     and
			  a.cod_entidad   =  " . $cod_entidad . "   and
			  a.cod_tipo_inst =  " . $cod_tipo_inst . " and
			  a.cod_inst      =  " . $cod_inst . "      and
			  a.cod_dep       =  " . $cod_dep . "       and
			  " . $sql_a . "
			  " . $sql_b . "
			  a.cod_presi = b.cod_presi AND
			  a.cod_entidad = b.cod_entidad AND
			  a.cod_tipo_inst = b.cod_tipo_inst AND
			  a.cod_inst = b.cod_inst AND
			  a.cod_dep = b.cod_dep AND
			  a.cod_tipo_nomina = b.cod_tipo_nomina AND
			  a.cod_cargo = b.cod_cargo AND
			  a.cod_puesto = b.cod_puesto AND
			  a.cod_tipo_nomina = " . $tipo . "  " . $sql_condicion_actividad . "

			ORDER BY
			  a.cod_presi,
			  a.cod_entidad,
			  a.cod_tipo_inst,
			  a.cod_inst,
			  a.cod_dep,
			  a.cod_tipo_nomina,
			  " . $order_by . "");
            } else {




                $rs = $this->v_cfpd05_denominaciones->execute("
			SELECT
			  a.cod_tipo_nomina,
			  (SELECT cn01.denominacion FROM cnmd01 cn01 WHERE cn01.cod_presi = a.cod_presi AND cn01.cod_entidad = a.cod_entidad AND cn01.cod_tipo_inst = a.cod_tipo_inst AND cn01.cod_inst = a.cod_inst AND cn01.cod_dep = a.cod_dep AND cn01.cod_tipo_nomina = a.cod_tipo_nomina) as denominacion_nomina,
			  (SELECT cn01.frecuencia_cobro FROM cnmd01 cn01 WHERE cn01.cod_presi = a.cod_presi AND cn01.cod_entidad = a.cod_entidad AND cn01.cod_tipo_inst = a.cod_tipo_inst AND cn01.cod_inst = a.cod_inst AND cn01.cod_dep = a.cod_dep AND cn01.cod_tipo_nomina = a.cod_tipo_nomina) as frecuencia_cobro,
			  (SELECT ficha.cedula_identidad FROM cnmd06_fichas ficha WHERE ficha.cod_presi = a.cod_presi AND ficha.cod_entidad = a.cod_entidad AND ficha.cod_tipo_inst = a.cod_tipo_inst AND ficha.cod_inst = a.cod_inst AND ficha.cod_dep = a.cod_dep AND ficha.cod_tipo_nomina = a.cod_tipo_nomina AND ficha.cod_cargo = a.cod_cargo AND ficha.cod_ficha = a.cod_ficha) as cedula_identidad_ficha,
			  (SELECT d.cedula_identidad FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as cedula_identidad,
			  (SELECT d.primer_nombre FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as primer_nombre,
			  (SELECT d.segundo_nombre FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as segundo_nombre,
			  (SELECT d.primer_apellido FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as primer_apellido,
			  (SELECT d.segundo_apellido FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as segundo_apellido,
			  (SELECT d.fecha_nacimiento FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as fecha_nacimiento,
			  (SELECT d.cod_profesion FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as cod_profesion,
			  (SELECT c.denominacion FROM cnmd06_profesiones c WHERE c.cod_profesion=(SELECT d.cod_profesion FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha))) as denominacion_profesion,
			  (SELECT ficha.fecha_ingreso   FROM cnmd06_fichas ficha WHERE ficha.cod_presi = a.cod_presi AND ficha.cod_entidad = a.cod_entidad AND ficha.cod_tipo_inst = a.cod_tipo_inst AND ficha.cod_inst = a.cod_inst AND ficha.cod_dep = a.cod_dep AND ficha.cod_tipo_nomina = a.cod_tipo_nomina AND ficha.cod_cargo = a.cod_cargo AND ficha.cod_ficha = a.cod_ficha) as fecha_ingreso,
			  (SELECT ficha.fecha_condicion FROM cnmd06_fichas ficha WHERE ficha.cod_presi = a.cod_presi AND ficha.cod_entidad = a.cod_entidad AND ficha.cod_tipo_inst = a.cod_tipo_inst AND ficha.cod_inst = a.cod_inst AND ficha.cod_dep = a.cod_dep AND ficha.cod_tipo_nomina = a.cod_tipo_nomina AND ficha.cod_cargo = a.cod_cargo AND ficha.cod_ficha = a.cod_ficha) as fecha_condicion,
			  (SELECT ficha.cod_entidad_bancaria FROM cnmd06_fichas ficha WHERE ficha.cod_presi = a.cod_presi AND ficha.cod_entidad = a.cod_entidad AND ficha.cod_tipo_inst = a.cod_tipo_inst AND ficha.cod_inst = a.cod_inst AND ficha.cod_dep = a.cod_dep AND ficha.cod_tipo_nomina = a.cod_tipo_nomina AND ficha.cod_cargo = a.cod_cargo AND ficha.cod_ficha = a.cod_ficha) as cod_entidad_bancaria,
			  (SELECT ficha.cuenta_bancaria FROM cnmd06_fichas ficha WHERE ficha.cod_presi = a.cod_presi AND ficha.cod_entidad = a.cod_entidad AND ficha.cod_tipo_inst = a.cod_tipo_inst AND ficha.cod_inst = a.cod_inst AND ficha.cod_dep = a.cod_dep AND ficha.cod_tipo_nomina = a.cod_tipo_nomina AND ficha.cod_cargo = a.cod_cargo AND ficha.cod_ficha = a.cod_ficha) as cuenta_bancaria,
			  (SELECT ban.denominacion FROM cstd01_entidades_bancarias ban WHERE ban.cod_entidad_bancaria=(SELECT ficha.cod_entidad_bancaria FROM cnmd06_fichas ficha WHERE ficha.cod_presi = a.cod_presi AND ficha.cod_entidad = a.cod_entidad AND ficha.cod_tipo_inst = a.cod_tipo_inst AND ficha.cod_inst = a.cod_inst AND ficha.cod_dep = a.cod_dep AND ficha.cod_tipo_nomina = a.cod_tipo_nomina AND ficha.cod_cargo = a.cod_cargo AND ficha.cod_ficha = a.cod_ficha)) as banco,
			  a.cod_cargo,
			  a.cod_puesto,
			  b.denominacion_clase AS denominacion_cargo,
			  a.cod_ficha,
			  a.sueldo_basico,
			  a.compensaciones,
			  a.primas,
			  a.bonos,
			  a.cod_dir_superior,
			  a.cod_coordinacion,
			  a.cod_secretaria,
			  a.cod_direccion,
			  a.cod_division,
			  a.cod_departamento,
			  a.cod_oficina,
			  a.cod_estado,
			  a.cod_municipio,
			  a.cod_parroquia,
			  a.cod_centro,
			  b.dir_superior,
			  b.coordinacion,
			  b.secretaria,
			  b.direccion,
			  b.division,
			  b.departamento,
			  b.oficina,
			  (
              select SUM(aaa.monto_cuota) from cnmd07_transacciones_actuales aaa, cnmd09_transa_queconforma_sueldointegral bbb

                                                                             where aaa.cod_presi     =  " . $cod_presi . "     and
																				   aaa.cod_entidad   =  " . $cod_entidad . "   and
																				   aaa.cod_tipo_inst =  " . $cod_tipo_inst . " and
																				   aaa.cod_inst      =  " . $cod_inst . "      and
																				   aaa.cod_dep       =  " . $cod_dep . "       and
																				   aaa.cod_tipo_nomina = a.cod_tipo_nomina and
																				   aaa.cod_cargo       = a.cod_cargo       and
																				   aaa.cod_ficha       = a.cod_ficha       and

																				   bbb.cod_presi     =  " . $cod_presi . "     and
																				   bbb.cod_entidad   =  " . $cod_entidad . "   and
																				   bbb.cod_tipo_inst =  " . $cod_tipo_inst . " and
																				   bbb.cod_inst      =  " . $cod_inst . "      and
																				   bbb.cod_dep       =  " . $cod_dep . "       and
																				   bbb.cod_tipo_nomina = a.cod_tipo_nomina and

																				   aaa.cod_tipo_transaccion = bbb.cod_tipo_transaccion and
																				   aaa.cod_transaccion      = bbb.cod_transaccion
			  ) as asignaciones_sueldo_integral,

			  (SELECT xya.denominacion FROM cugd01_estados          xya where xya.cod_republica=a.cod_presi and xya.cod_estado=a.cod_estado                                                                                                                  GROUP BY xya.denominacion) as  deno_cod_estado,
			  (SELECT xyb.denominacion FROM cugd01_municipios       xyb where xyb.cod_republica=a.cod_presi and xyb.cod_estado=a.cod_estado  and xyb.cod_municipio=a.cod_municipio                                                                           GROUP BY xyb.denominacion) as  deno_cod_municipio,
			  (SELECT xyc.denominacion FROM cugd01_parroquias       xyc where xyc.cod_republica=a.cod_presi and xyc.cod_estado=a.cod_estado  and xyc.cod_municipio=a.cod_municipio and xyc.cod_parroquia = a.cod_parroquia                                   GROUP BY xyc.denominacion) as  deno_cod_parroquia,
			  (SELECT xyd.denominacion FROM cugd01_centros_poblados xyd where xyd.cod_republica=a.cod_presi and xyd.cod_estado=a.cod_estado  and xyd.cod_municipio=a.cod_municipio and xyd.cod_parroquia = a.cod_parroquia and xyd.cod_centro = a.cod_centro GROUP BY xyd.denominacion) as  deno_cod_centro

			FROM
			 cnmd05 a, v_cnmd05_cargos b

			WHERE
			  a.cod_presi     =  " . $cod_presi . "     and
			  a.cod_entidad   =  " . $cod_entidad . "   and
			  a.cod_tipo_inst =  " . $cod_tipo_inst . " and
			  a.cod_inst      =  " . $cod_inst . "      and
			  a.cod_dep       =  " . $cod_dep . "       and
			  " . $sql_a . "
			  " . $sql_b . "
			  a.cod_presi = b.cod_presi AND
			  a.cod_entidad = b.cod_entidad AND
			  a.cod_tipo_inst = b.cod_tipo_inst AND
			  a.cod_inst = b.cod_inst AND
			  a.cod_dep = b.cod_dep AND
			  a.cod_tipo_nomina = b.cod_tipo_nomina AND
			  a.cod_cargo = b.cod_cargo AND
			  a.cod_puesto = b.cod_puesto AND
			  a.cod_tipo_nomina = " . $tipo . "  " . $sql_condicion_actividad . "

			ORDER BY
			  a.cod_presi,
			  a.cod_entidad,
			  a.cod_tipo_inst,
			  a.cod_inst,
			  a.cod_dep,
			  a.cod_tipo_nomina,
			  " . $order_by . "");
            }




            $this->set('tipo', $tipo);
            $this->set('datos', $rs);
            $this->set('presentar_como', $frecuencia);

            $this->set('geografico', $radio_geograf);
            $this->set('administrativo', $radio_admin);

            if ($radio_geograf == 0 && $radio_admin == 0) {
                $this->set('ordenamiento', $ordenamiento);
                $this->render("listado_expediente_codigo_actual");
            } elseif ($radio_geograf == 1 || $radio_geograf == 2) {
                $this->render("listado_expediente_geografico_actual");
            } elseif ($radio_admin == 1 || $radio_admin == 2) {
                $this->render("listado_expediente_administrativo_actual");
            }
        }



//--- Importante ----//
        $this->set('opcion', $opcion);
        $this->set('var1', $var1);
    }

    function listado_expediente_personal_actual2($var1 = null, $var2 = null) {
        $tabla = "";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');

        if ($var2 == null) {
            $this->layout = "ajax";
            $opcion = 1;
            $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst;
            $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
            $dato = null;
            foreach ($year as $year) {
                $dato_year = $year['cfpd01_formulacion']['ano_formular'];
            }
            if ($var1 == 1) {
                $year_vista = $dato_year;
            } else {
                $year_vista = $this->ano_ejecucion();
            }
            $this->set('year', $year_vista);
            $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
            $this->concatenaN($lista, 'nomina');
        } else {
            $this->layout = "pdf";
            $opcion = 2;

            set_time_limit(0);


            $sql_a = "";
            $sql_b = "";

            $radio_geograf = "";
            $radio_admin = "";


            if (!empty($this->data['reporte3']['consolidacion'])) {
                $consolidacion = $this->data['reporte3']['consolidacion'];
            } else {
                $consolidacion = 2;
            }
            if (!empty($this->data['reporte3']['year'])) {
                $year = $this->data['reporte3']['year'];
            } else {
                $year = $this->ano_ejecucion();
            }
            if (!empty($this->data['reporte3']['tipo'])) {
                $tipo = $this->data['reporte3']['tipo'];
            } else {
                $tipo = 1;
            }
            if (!empty($this->data['reporte3']['frecuencia'])) {
                $frecuencia = $this->data['reporte3']['frecuencia'];
            } else {
                $frecuencia = 1;
            }

            if (!empty($this->data['reporte3']['radio_ordenamiento_codigo'])) {
                $ordenamiento = $this->data['reporte3']['radio_ordenamiento_codigo'];
            } else {
                $ordenamiento = 1;
            }
            if (!empty($this->data['reporte3']['radio_ubicacion_geografica'])) {
                $radio_geograf = $this->data['reporte3']['radio_ubicacion_geografica'];
            } else {
                $radio_geograf = 0;
            }
            if (!empty($this->data['reporte3']['radio_ubicacion_administrativa'])) {
                $radio_admin = $this->data['reporte3']['radio_ubicacion_administrativa'];
            } else {
                $radio_admin = 0;
            }

            if ($radio_geograf == 2) {
                if (!empty($this->data['cobp01_contratoobras']['cod_estado'])) {
                    $sql_b .= "a.cod_estado  = " . $this->data['cobp01_contratoobras']['cod_estado'] . "  and ";
                }
                if (!empty($this->data['cobp01_contratoobras']['cod_municipio'])) {
                    $sql_b .= "a.cod_municipio  = " . $this->data['cobp01_contratoobras']['cod_municipio'] . "  and ";
                }
                if (!empty($this->data['cobp01_contratoobras']['cod_parroquia'])) {
                    $sql_b .= "a.cod_parroquia  = " . $this->data['cobp01_contratoobras']['cod_parroquia'] . "  and ";
                }
                if (!empty($this->data['cobp01_contratoobras']['cod_centro'])) {
                    $sql_b .= "a.cod_centro  = " . $this->data['cobp01_contratoobras']['cod_centro'] . "  and ";
                }

                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_dirsuperior'])) {
                    $sql_a .= "a.cod_dir_superior  = " . $this->data['cscp02_solicitud_cotizacion']['cod_dirsuperior'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_coordinacion'])) {
                    $sql_a .= "a.cod_coordinacion  = " . $this->data['cscp02_solicitud_cotizacion']['cod_coordinacion'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_secretaria'])) {
                    $sql_a .= "a.cod_secretaria  = " . $this->data['cscp02_solicitud_cotizacion']['cod_secretaria'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_direccion'])) {
                    $sql_a .= "a.cod_direccion  = " . $this->data['cscp02_solicitud_cotizacion']['cod_direccion'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_division'])) {
                    $sql_a .= "a.cod_division  = " . $this->data['cscp02_solicitud_cotizacion']['cod_division'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_departamento'])) {
                    $sql_a .= "a.cod_departamento  = " . $this->data['cscp02_solicitud_cotizacion']['cod_departamento'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_oficina'])) {
                    $sql_a .= "a.cod_oficina  = " . $this->data['cscp02_solicitud_cotizacion']['cod_oficina'] . "  and ";
                }
            }

            if ($radio_admin == 2) {
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_dirsuperior'])) {
                    $sql_a .= "a.cod_dir_superior  = " . $this->data['cscp02_solicitud_cotizacion']['cod_dirsuperior'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_coordinacion'])) {
                    $sql_a .= "a.cod_coordinacion  = " . $this->data['cscp02_solicitud_cotizacion']['cod_coordinacion'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_secretaria'])) {
                    $sql_a .= "a.cod_secretaria  = " . $this->data['cscp02_solicitud_cotizacion']['cod_secretaria'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_direccion'])) {
                    $sql_a .= "a.cod_direccion  = " . $this->data['cscp02_solicitud_cotizacion']['cod_direccion'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_division'])) {
                    $sql_a .= "a.cod_division  = " . $this->data['cscp02_solicitud_cotizacion']['cod_division'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_departamento'])) {
                    $sql_a .= "a.cod_departamento  = " . $this->data['cscp02_solicitud_cotizacion']['cod_departamento'] . "  and ";
                }
                if (!empty($this->data['cscp02_solicitud_cotizacion']['cod_oficina'])) {
                    $sql_a .= "a.cod_oficina  = " . $this->data['cscp02_solicitud_cotizacion']['cod_oficina'] . "  and ";
                }
            }

//echo "<br/>".$radio_geograf;
//echo "<br/>".$radio_admin;
//echo "<br />".$ordenamiento;

            $order_by = "";
            if ($radio_geograf == 0 && $radio_admin == 0) {
                //echo "entro en 1";
                switch ($ordenamiento) {
                    case '1': $order_by .= " a.cod_tipo_nomina, a.cod_cargo, a.cod_ficha"; /* echo "<br />option: 1"; */ break;
                    case '2': $order_by .= " a.cod_tipo_nomina, a.cod_ficha, a.cod_cargo"; /* echo "<br />option: 2"; */ break;
                    case '3': $order_by .= " a.cod_tipo_nomina, a.cod_puesto, a.cod_cargo, a.cod_ficha"; /* echo "<br />option: 3"; */ break;
                    case '4': $order_by .= " a.cod_tipo_nomina, cedula_identidad, a.cod_cargo, a.cod_ficha"; /* echo "<br />option: 4"; */ break;
                    case '5': $order_by .= " a.cod_tipo_nomina, primer_apellido, segundo_apellido, primer_nombre, segundo_nombre, a.cod_cargo, a.cod_ficha"; /* echo "<br />option: 5"; */ break;
                    case '6': $order_by .= " a.cod_tipo_nomina, fecha_nacimiento, a.cod_cargo, a.cod_ficha"; /* echo "<br />option: 6"; */ break;
                    case '7': $order_by .= " a.cod_tipo_nomina, denominacion_profesion, a.cod_cargo, a.cod_ficha"; /* echo "<br />option: 7"; */ break;
                    case '8': $order_by .= " a.cod_tipo_nomina, fecha_ingreso, a.cod_cargo, a.cod_ficha"; /* echo "<br />option: 8"; */ break;
                    default: break;
                }
            } elseif ($radio_geograf == 1 || $radio_geograf == 2) {
                //echo "entro en 2";
                $order_by .= " a.cod_estado,
						a.cod_municipio,
					    a.cod_parroquia,
					    a.cod_centro,
						a.cod_dir_superior,
					  	a.cod_coordinacion,
					  	a.cod_secretaria,
					  	a.cod_direccion,
					  	a.cod_division,
					  	a.cod_departamento,
					  	a.cod_oficina,
					  	a.cod_cargo,
					  	a.cod_ficha";
            } elseif ($radio_admin == 1 || $radio_admin == 2) {
                //echo "entro en 3";
                $order_by .= " a.cod_dir_superior,
					  	a.cod_coordinacion,
					  	a.cod_secretaria,
					  	a.cod_direccion,
					  	a.cod_division,
					  	a.cod_departamento,
					  	a.cod_oficina,
					  	a.cod_cargo,
					  	a.cod_ficha";
            }

            $rs = $this->v_cfpd05_denominaciones->execute("
			SELECT
			  a.cod_tipo_nomina,
			  (SELECT cn01.denominacion FROM cnmd01 cn01 WHERE cn01.cod_presi = a.cod_presi AND cn01.cod_entidad = a.cod_entidad AND cn01.cod_tipo_inst = a.cod_tipo_inst AND cn01.cod_inst = a.cod_inst AND cn01.cod_dep = a.cod_dep AND cn01.cod_tipo_nomina = a.cod_tipo_nomina) as denominacion_nomina,
			  (SELECT cn01.frecuencia_cobro FROM cnmd01 cn01 WHERE cn01.cod_presi = a.cod_presi AND cn01.cod_entidad = a.cod_entidad AND cn01.cod_tipo_inst = a.cod_tipo_inst AND cn01.cod_inst = a.cod_inst AND cn01.cod_dep = a.cod_dep AND cn01.cod_tipo_nomina = a.cod_tipo_nomina) as frecuencia_cobro,
			  (SELECT ficha.cedula_identidad FROM cnmd06_fichas ficha WHERE ficha.cod_presi = a.cod_presi AND ficha.cod_entidad = a.cod_entidad AND ficha.cod_tipo_inst = a.cod_tipo_inst AND ficha.cod_inst = a.cod_inst AND ficha.cod_dep = a.cod_dep AND ficha.cod_tipo_nomina = a.cod_tipo_nomina AND ficha.cod_cargo = a.cod_cargo AND ficha.cod_ficha = a.cod_ficha) as cedula_identidad_ficha,
			  (SELECT d.cedula_identidad FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as cedula_identidad,
			  (SELECT d.primer_nombre FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as primer_nombre,
			  (SELECT d.segundo_nombre FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as segundo_nombre,
			  (SELECT d.primer_apellido FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as primer_apellido,
			  (SELECT d.segundo_apellido FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as segundo_apellido,
			  (SELECT d.fecha_nacimiento FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as fecha_nacimiento,
			  (SELECT d.cod_profesion FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha)) as cod_profesion,
			  (SELECT c.denominacion FROM cnmd06_profesiones c WHERE c.cod_profesion=(SELECT d.cod_profesion FROM cnmd06_datos_personales d WHERE d.cedula_identidad=(SELECT f.cedula_identidad FROM cnmd06_fichas f WHERE f.cod_presi = a.cod_presi AND f.cod_entidad = a.cod_entidad AND f.cod_tipo_inst = a.cod_tipo_inst AND f.cod_inst = a.cod_inst AND f.cod_dep = a.cod_dep AND f.cod_tipo_nomina = a.cod_tipo_nomina AND f.cod_cargo = a.cod_cargo AND f.cod_ficha = a.cod_ficha))) as denominacion_profesion,
			  (SELECT ficha.fecha_ingreso FROM cnmd06_fichas ficha WHERE ficha.cod_presi = a.cod_presi AND ficha.cod_entidad = a.cod_entidad AND ficha.cod_tipo_inst = a.cod_tipo_inst AND ficha.cod_inst = a.cod_inst AND ficha.cod_dep = a.cod_dep AND ficha.cod_tipo_nomina = a.cod_tipo_nomina AND ficha.cod_cargo = a.cod_cargo AND ficha.cod_ficha = a.cod_ficha) as fecha_ingreso,
			  (SELECT ficha.cod_entidad_bancaria FROM cnmd06_fichas ficha WHERE ficha.cod_presi = a.cod_presi AND ficha.cod_entidad = a.cod_entidad AND ficha.cod_tipo_inst = a.cod_tipo_inst AND ficha.cod_inst = a.cod_inst AND ficha.cod_dep = a.cod_dep AND ficha.cod_tipo_nomina = a.cod_tipo_nomina AND ficha.cod_cargo = a.cod_cargo AND ficha.cod_ficha = a.cod_ficha) as cod_entidad_bancaria,
			  (SELECT ficha.cuenta_bancaria FROM cnmd06_fichas ficha WHERE ficha.cod_presi = a.cod_presi AND ficha.cod_entidad = a.cod_entidad AND ficha.cod_tipo_inst = a.cod_tipo_inst AND ficha.cod_inst = a.cod_inst AND ficha.cod_dep = a.cod_dep AND ficha.cod_tipo_nomina = a.cod_tipo_nomina AND ficha.cod_cargo = a.cod_cargo AND ficha.cod_ficha = a.cod_ficha) as cuenta_bancaria,
			  (SELECT ban.denominacion FROM cstd01_entidades_bancarias ban WHERE ban.cod_entidad_bancaria=(SELECT ficha.cod_entidad_bancaria FROM cnmd06_fichas ficha WHERE ficha.cod_presi = a.cod_presi AND ficha.cod_entidad = a.cod_entidad AND ficha.cod_tipo_inst = a.cod_tipo_inst AND ficha.cod_inst = a.cod_inst AND ficha.cod_dep = a.cod_dep AND ficha.cod_tipo_nomina = a.cod_tipo_nomina AND ficha.cod_cargo = a.cod_cargo AND ficha.cod_ficha = a.cod_ficha)) as banco,
			  a.cod_cargo,
			  a.cod_puesto,
			  b.denominacion_clase AS denominacion_cargo,
			  a.cod_ficha,
			  a.sueldo_basico,
			  a.compensaciones,
			  a.primas,
			  a.bonos,
			  a.cod_dir_superior,
			  a.cod_coordinacion,
			  a.cod_secretaria,
			  a.cod_direccion,
			  a.cod_division,
			  a.cod_departamento,
			  a.cod_oficina,
			  a.cod_estado,
			  a.cod_municipio,
			  a.cod_parroquia,
			  a.cod_centro,
			  b.dir_superior,
			  b.coordinacion,
			  b.secretaria,
			  b.direccion,
			  b.division,
			  b.departamento,
			  b.oficina,

              (
              select SUM(aaa.monto_cuota) from cnmd07_transacciones_actuales aaa, cnmd09_transa_queconforma_sueldointegral bbb

                                                                             where aaa.cod_presi     =  " . $cod_presi . "     and
																				   aaa.cod_entidad   =  " . $cod_entidad . "   and
																				   aaa.cod_tipo_inst =  " . $cod_tipo_inst . " and
																				   aaa.cod_inst      =  " . $cod_inst . "      and
																				   aaa.cod_dep       =  " . $cod_dep . "       and
																				   aaa.cod_tipo_nomina = a.cod_tipo_nomina and
																				   aaa.cod_cargo       = a.cod_cargo       and
																				   aaa.cod_ficha       = a.cod_ficha       and

																				   bbb.cod_presi     =  " . $cod_presi . "     and
																				   bbb.cod_entidad   =  " . $cod_entidad . "   and
																				   bbb.cod_tipo_inst =  " . $cod_tipo_inst . " and
																				   bbb.cod_inst      =  " . $cod_inst . "      and
																				   bbb.cod_dep       =  " . $cod_dep . "       and
																				   bbb.cod_tipo_nomina = a.cod_tipo_nomina and

																				   aaa.cod_tipo_transaccion = bbb.cod_tipo_transaccion and
																				   aaa.cod_transaccion      = bbb.cod_transaccion
			  ) as asignaciones_sueldo_integral,


			  (SELECT xya.denominacion FROM cugd01_estados          xya where xya.cod_republica=a.cod_presi and xya.cod_estado=a.cod_estado                                                                                                                  GROUP BY xya.denominacion) as  deno_cod_estado,
			  (SELECT xyb.denominacion FROM cugd01_municipios       xyb where xyb.cod_republica=a.cod_presi and xyb.cod_estado=a.cod_estado  and xyb.cod_municipio=a.cod_municipio                                                                           GROUP BY xyb.denominacion) as  deno_cod_municipio,
			  (SELECT xyc.denominacion FROM cugd01_parroquias       xyc where xyc.cod_republica=a.cod_presi and xyc.cod_estado=a.cod_estado  and xyc.cod_municipio=a.cod_municipio and xyc.cod_parroquia = a.cod_parroquia                                   GROUP BY xyc.denominacion) as  deno_cod_parroquia,
			  (SELECT xyd.denominacion FROM cugd01_centros_poblados xyd where xyd.cod_republica=a.cod_presi and xyd.cod_estado=a.cod_estado  and xyd.cod_municipio=a.cod_municipio and xyd.cod_parroquia = a.cod_parroquia and xyd.cod_centro = a.cod_centro GROUP BY xyd.denominacion) as  deno_cod_centro

			FROM
			 cnmd05 a, v_cnmd05 b

			WHERE
			  a.cod_presi     =  " . $cod_presi . "     and
			  a.cod_entidad   =  " . $cod_entidad . "   and
			  a.cod_tipo_inst =  " . $cod_tipo_inst . " and
			  a.cod_inst      =  " . $cod_inst . "      and
			  a.cod_dep       =  " . $cod_dep . "       and
			  " . $sql_a . "
			  " . $sql_b . "
			  a.cod_presi = b.cod_presi AND
			  a.cod_entidad = b.cod_entidad AND
			  a.cod_tipo_inst = b.cod_tipo_inst AND
			  a.cod_inst = b.cod_inst AND
			  a.cod_dep = b.cod_dep AND
			  a.cod_tipo_nomina = b.cod_tipo_nomina AND
			  a.cod_cargo = b.cod_cargo AND
			  a.cod_puesto = b.cod_puesto AND
			  a.cod_tipo_nomina = " . $tipo . "

			ORDER BY
			  a.cod_presi,
			  a.cod_entidad,
			  a.cod_tipo_inst,
			  a.cod_inst,
			  a.cod_dep,
			  a.cod_tipo_nomina,
			  " . $order_by . "");



            $this->set('tipo', $tipo);
            $this->set('datos', $rs);
            $this->set('presentar_como', $frecuencia);

            $this->set('geografico', $radio_geograf);
            $this->set('administrativo', $radio_admin);

            if ($radio_geograf == 0 && $radio_admin == 0) {
                $this->set('ordenamiento', $ordenamiento);
                $this->render("listado_expediente_codigo_actual");
            } elseif ($radio_geograf == 1 || $radio_geograf == 2) {
                $this->render("listado_expediente_geografico_actual");
            } elseif ($radio_admin == 1 || $radio_admin == 2) {
                $this->render("listado_expediente_administrativo_actual");
            }
        }



//--- Importante ----//
        $this->set('opcion', $opcion);
        $this->set('var1', $var1);
    }

    function frm_reportes_timbre_fiscal_detallado($op = null) {

        if ($op == 1) {
            $this->layout = "ajax";
        } else {
            $this->layout = "pdf";
            $this->layout = "ajax";
            $cond = $this->SQLCA();
            $condstatus = "";
            $this->data['reporte_juan']['status'] = 2;
            $fecha_desde = cambiar_formato_fecha($this->data['reporte_juan']['fecha_desde']);
            $fecha_hasta = cambiar_formato_fecha($this->data['reporte_juan']['fecha_hasta']);
            $condstatus = $cond . " and status=" . $this->data['reporte_juan']['status'] . " and (fecha_proceso_registro BETWEEN '$fecha_desde' and '$fecha_hasta')and ano_orden_pago=" . $this->data['reporte_juan']['year']; // status=1 :Por Emitir / status=3 :Por Restaurar
            if ($datos_cuerpo_timbre = $this->cstd07_retenciones_cuerpo_timbre->findAll($condstatus, array('clase_orden', 'ano_orden_pago', 'numero_orden_pago', 'monto', 'fecha_proceso_registro'), 'numero_orden_pago DESC')) {
                $sql = "";
                if (is_array($datos_cuerpo_timbre)) {
                    foreach ($datos_cuerpo_timbre as $ve_aux) {
                        $ano_orden_pago = $ve_aux['cstd07_retenciones_cuerpo_timbre']['ano_orden_pago'];
                        $clase_orden = $ve_aux['cstd07_retenciones_cuerpo_timbre']['clase_orden'];
                        $numero_orden_pago = $ve_aux['cstd07_retenciones_cuerpo_timbre']['numero_orden_pago'];
                        if ($sql == "") {
                            $sql = " and (ano_orden_pago='" . $ano_orden_pago . "'    and  numero_orden_pago='" . $numero_orden_pago . "' ";
                        } else {
                            $sql .= " or  ano_orden_pago='" . $ano_orden_pago . "'    and  numero_orden_pago='" . $numero_orden_pago . "'    ";
                        }
                    }//fin fore
                    if ($sql != "") {
                        $sql .=" )";
                    }
                }//fin
                $datos_ordenpago = $this->cepd03_ordenpago_cuerpo->findAll($cond . " and ano_orden_pago=" . $this->data['reporte_juan']['year'] . " " . $sql, null, 'fecha_proceso_registro, numero_orden_pago, beneficiario DESC');



                $campos = 'cod_presi ,
			cod_entidad,
			cod_tipo_inst,
			cod_inst, cod_dep,
			ano_orden_pago,
			numero_orden_pago,
			SUM(monto_sub_total) as "monto_sub_total" ';


                $agrupar = 'GROUP BY cod_presi,
					 cod_entidad,
					 cod_tipo_inst,
					 cod_inst, cod_dep,
				 	 ano_orden_pago ,
					 numero_orden_pago';

                $datos_ordenpago_facturas = $this->cepd03_ordenpago_facturas->findAll($cond . " and ano_orden_pago=" . $this->data['reporte_juan']['year'] . " " . $sql . $agrupar, $campos, 'numero_orden_pago DESC');
                $this->set('datos_ordenpago_facturas', $datos_ordenpago_facturas);


                $this->set('datos_ordenpago', $datos_ordenpago);
                $this->set('datos_cuerpo_timbre', $datos_cuerpo_timbre);
                $this->set('vacio', 'no');
            } else {
                $this->set('vacio', 'si');
                if ($this->data['reporte_juan']['status'] == 1) {
                    $this->set('mensaje', 'NO SE ENCONTRARÓN DATOS EN LA RETENCIÓN DEL TIMBRE FISCAL PENDIENTE');
                } elseif ($this->data['reporte_juan']['status'] == 3) {
                    $this->set('mensaje', 'NO SE ENCONTRARÓN DATOS POR RESTAURAR EN LA RETENCIÓN DEL TIMBRE FISCAL');
                }
            }
            $this->set('titulo_a', $this->Session->read('dependencia'));
            $this->set('titulo_inst', 'GOBERNACIÓN DEL ESTADO FALCÓN');
        }

        $this->set('year', $this->ano_ejecucion());
        $this->set('opcion', $op);
    }

//fin function

    function frm_reportes_timbre_fiscal_detallado_ods($op = null) {

        if ($op == 1) {
            $this->layout = "ajax";
        } else {
            $this->layout = "pdf";
            $this->layout = "ajax";
            $cond = $this->SQLCA();
            $condstatus = "";
            $this->data['reporte_juan']['status'] = 2;
            $fecha_desde = cambiar_formato_fecha($this->data['reporte_juan']['fecha_desde']);
            $fecha_hasta = cambiar_formato_fecha($this->data['reporte_juan']['fecha_hasta']);
            $condstatus = $cond . " and status=" . $this->data['reporte_juan']['status'] . " and (fecha_proceso_registro BETWEEN '$fecha_desde' and '$fecha_hasta')and ano_orden_pago=" . $this->data['reporte_juan']['year']; // status=1 :Por Emitir / status=3 :Por Restaurar
            if ($datos_cuerpo_timbre = $this->cstd07_retenciones_cuerpo_timbre->findAll($condstatus, array('clase_orden', 'ano_orden_pago', 'numero_orden_pago', 'monto', 'fecha_proceso_registro'), 'numero_orden_pago DESC')) {
                $sql = "";
                if (is_array($datos_cuerpo_timbre)) {
                    foreach ($datos_cuerpo_timbre as $ve_aux) {
                        $ano_orden_pago = $ve_aux['cstd07_retenciones_cuerpo_timbre']['ano_orden_pago'];
                        $clase_orden = $ve_aux['cstd07_retenciones_cuerpo_timbre']['clase_orden'];
                        $numero_orden_pago = $ve_aux['cstd07_retenciones_cuerpo_timbre']['numero_orden_pago'];
                        if ($sql == "") {
                            $sql = " and (ano_orden_pago='" . $ano_orden_pago . "'    and  numero_orden_pago='" . $numero_orden_pago . "' ";
                        } else {
                            $sql .= " or  ano_orden_pago='" . $ano_orden_pago . "'    and  numero_orden_pago='" . $numero_orden_pago . "'    ";
                        }
                    }//fin fore
                    if ($sql != "") {
                        $sql .=" )";
                    }
                }//fin
                $datos_ordenpago = $this->cepd03_ordenpago_cuerpo->findAll($cond . " and ano_orden_pago=" . $this->data['reporte_juan']['year'] . " " . $sql, null, 'cuenta_bancaria, numero_orden_pago, beneficiario DESC');



                $campos = 'cod_presi ,
			cod_entidad,
			cod_tipo_inst,
			cod_inst, cod_dep,
			ano_orden_pago,
			numero_orden_pago,
			SUM(monto_sub_total) as "monto_sub_total" ';


                $agrupar = 'GROUP BY cod_presi,
					 cod_entidad,
					 cod_tipo_inst,
					 cod_inst, cod_dep,
				 	 ano_orden_pago ,
					 numero_orden_pago';

                $datos_ordenpago_facturas = $this->cepd03_ordenpago_facturas->findAll($cond . " and ano_orden_pago=" . $this->data['reporte_juan']['year'] . " " . $sql . $agrupar, $campos, 'numero_orden_pago DESC');
                $this->set('datos_ordenpago_facturas', $datos_ordenpago_facturas);


                $this->set('datos_ordenpago', $datos_ordenpago);
                $this->set('datos_cuerpo_timbre', $datos_cuerpo_timbre);
                $this->set('vacio', 'no');
            } else {
                $this->set('vacio', 'si');
                if ($this->data['reporte_juan']['status'] == 1) {
                    $this->set('mensaje', 'NO SE ENCONTRARÓN DATOS EN LA RETENCIÓN DEL TIMBRE FISCAL PENDIENTE');
                } elseif ($this->data['reporte_juan']['status'] == 3) {
                    $this->set('mensaje', 'NO SE ENCONTRARÓN DATOS POR RESTAURAR EN LA RETENCIÓN DEL TIMBRE FISCAL');
                }
            }
            $this->set('titulo_a', $this->Session->read('dependencia'));
            $this->set('titulo_inst', 'GOBERNACIÓN DEL ESTADO FALCÓN');
        }

        $this->set('year', $this->ano_ejecucion());
        $this->set('opcion', $op);
        $this->set('titulo_a', $this->Session->read('dependencia'));
        $this->set('entidad_federal', 'GOBERNACIÓN DEL ESTADO FALCÓN');
    }

//fin function

    function capa_vacia() {
        $this->layout = "ajax";
    }

}

//fin class
?>