<?php

class ReporteEController extends AppController {

    var $name = "reporte_e";
    var $uses = array('ccfd04_cierre_mes', 'cfpd30_rendiciones_cuerpo', 'cfpd30_rendiciones_partidas', 'cugd07_firmas_oficio_anulacion',
        'v_cfpd30_rendiciones_generales', 'v_cfpd30_reintegros_generales', 'cfpd30_reintegro_cuerpo',
        'cfpd30_reintegro_partidas', 'cepd01_tipo_compromiso', 'v_cimd01_escalera_codigos_bienes',
        'cimd02_tipo_movimiento', 'cimd03_inventario_muebles', 'cugd01_estados', 'cugd01_municipios',
        'cugd01_centropoblados', 'cugd01_parroquias', 'arrd05', 'v_solicitud_cfpd05_p2', 'cfpd02_activ_obra',
        'v_csrd01_analitico_solicitud_recurso', 'v_partidas_nodisponibilidad_fecha', 'casd01_datos_personales', 'casd01_datos_familiares',
        'casd01_solicitud_ayuda', 'v_casp01_relacion_solicitudes', 'v_casd01_relacion_solicitantes', 'casd01_tipo_ayuda',
        'v_casd01_ubicacion_geografica', 'v_casd01_ubicacion_geografica_tipo_2', 'v_casd01_comunicacion', 'v_casd01_sintesis_social', 'v_historia_solicitud_ayudas',
        'csrd01_solicitud_recurso_cuerpo', 'v_cfpd05_denominaciones', 'cstd04_cheque_poremitir', 'cstd01_entidades_bancarias', 'cstd01_sucursales_bancarias',
        'cstd02_cuentas_bancarias', 'cstd06_comprobante_poremitir_egreso', 'cstd06_comprobante_cuerpo_egreso', 'cugd02_dependencia', 'cugd02_institucion');
// agregar estos modelos para los reportes de hacienda	'shd100_patente','shd001_registro_contribuyentes','shd100_solicitud','v_shd100_declaracion_ingreso'

    var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap');

//,'v_balance_ejecucion_partidas_inst','v_balance_ejecucion_partidas_dep'




    function checkSession() {
        if (!$this->Session->check('Usuario')) {
            $this->redirect('/salir/');
            exit();
        } else {
            $this->requestAction('/usuarios/actualizar_user');
        }
    }

//fin checksession

    function beforeFilter() {
        $this->checkSession();
    }

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

    function reporte_cfpd30_rendiciones($ir = null, $var = null) {
//	echo $ir."    ".$var;
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        if ($ir == 'si') {
            $this->layout = "ajax";
            $ano = $this->ano_ejecucion();
            $this->Session->write('escribir_ano_rendiciones', $ano);
            $this->set('ir', 'si');
            $this->set('year', $ano);
        }//fin ir si
        else if ($ir == 'vista') {
            $this->layout = "ajax";
            $this->set('ir', 'vista');
            if ($var == 1) {
                $this->set('vista', 'opcion');
            } else if ($var == 2) {
                $this->set('vista', 'select');
                $ano1 = $this->Session->read('escribir_ano_rendiciones');
                $dep = $this->cfpd30_rendiciones_cuerpo->generateList($conditions = $this->SQLCA() . " and ano_rendicion=" . $ano1, "numero_rendicion asc", $limit = null, '{n}.cfpd30_rendiciones_cuerpo.numero_rendicion', '{n}.cfpd30_rendiciones_cuerpo.numero_rendicion');
                $this->set('numero', $dep);
//			$this->concatena($dep, 'numero');
            }
        }//fin ir vista
        else if ($ir == 'boton') {
            $this->layout = "ajax";
            $this->set('ir', 'boton');
        }//fin ir boton
        else if ($ir == 'no') {


            $this->layout = "pdf";
            $this->set('ir', 'no');
            $ano = $this->data['cepp01_compromiso']['ano'];
            $ver = $this->data['cepp01_compromiso']['radio_ver'];
            if ($ver == 1) {

                if (isset($this->data['cepp01_compromiso']['opciones'])) {
                    $opcion = $this->data['cepp01_compromiso']['opciones'];
                    if ($opcion == 1) {
                        $datos = $this->v_cfpd30_rendiciones_generales->FindAll($this->SQLCA() . " and ano_rendicion=" . $ano);
                    } else if ($opcion == 2) {
                        $datos = $this->v_cfpd30_rendiciones_generales->FindAll($this->SQLCA() . " and condicion_actividad=1 and ano_rendicion=" . $ano);
                    } else if ($opcion == 3) {
                        $datos = $this->v_cfpd30_rendiciones_generales->FindAll($this->SQLCA() . " and condicion_actividad=2 and ano_rendicion=" . $ano);
                    }
                    $this->set('datos', $datos);
                } else {
                    $this->set('vacio', '');
                }
            } else {
                $numero = $this->data['cepp01_compromiso']['solicitud'];
                if ($numero != "") {
                    $datos = $this->v_cfpd30_rendiciones_generales->FindAll($this->SQLCA() . " and ano_rendicion=" . $ano . " and numero_rendicion=" . $numero);
                    $this->set('datos', $datos);
                } else {
                    $this->set('vacio', '');
                }
                //		pr($datos);
            }
        }//fin ir no
    }

//fin reporte_cfpd30_rendiciones

    function reporte_cheque_mov_manu_gene($year = null) {


        $this->layout = "ajax";

        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst . " and cod_dep=" . $cod_dep;
        $this->Session->delete('FIN');
        $borrar = "no";
        $contador = 0;


        if (isset($this->data['radio']['opcion'])) {
            $opcion = $this->data['radio']['opcion'];
        } else {
            $opcion = 1;
        }
        $this->set('opcion', $opcion);

        if (!$year) {

            $this->set('ir', 'no');
            $ano = $this->ano_ejecucion();
            if (!empty($ano)) {
                $this->set('year', $ano);
            } else {
                $this->set('year', '');
            }




            $datos_cstd04_cheque_poremitir = $this->cstd04_cheque_poremitir->findAll($condicion . " and username='" . $this->Session->read('nom_usuario') . "' ");
            $this->set('datos_cstd04_cheque_poremitir', $datos_cstd04_cheque_poremitir);

            $this->set('usuario', $this->Session->read('nom_usuario'));
        } else if ($opcion == 1) {


            $this->layout = "pdf";







            $datos_cstd04_cheque_poremitir = $this->cstd04_cheque_poremitir->findAll($condicion . " and username='" . $this->Session->read('nom_usuario') . "' ");

            $ii = 0;
            $sql_1 = "";
            $sql_2 = "";

            foreach ($datos_cstd04_cheque_poremitir as $aux_cstd04_cheque_poremitir) {
                $ii++;

                $ano_movimiento[$ii] = $aux_cstd04_cheque_poremitir['cstd04_cheque_poremitir']['ano_movimiento'];
                $cod_entidad_bancaria[$ii] = $aux_cstd04_cheque_poremitir['cstd04_cheque_poremitir']['cod_entidad_bancaria'];
                $cod_sucursal[$ii] = $aux_cstd04_cheque_poremitir['cstd04_cheque_poremitir']['cod_sucursal'];
                $cuenta_bancaria[$ii] = $aux_cstd04_cheque_poremitir['cstd04_cheque_poremitir']['cuenta_bancaria'];
                $numero_cheque[$ii] = $aux_cstd04_cheque_poremitir['cstd04_cheque_poremitir']['numero_cheque'];


                if ($sql_2 == "") {
                    $sql_2 .= "       ano_movimiento='" . $ano_movimiento[$ii] . "'  and  cod_entidad_bancaria='" . $cod_entidad_bancaria[$ii] . "'  and  cod_sucursal='" . $cod_sucursal[$ii] . "' and  cuenta_bancaria='" . $cuenta_bancaria[$ii] . "' and  numero_cheque='" . $numero_cheque[$ii] . "'  ";
                } else {
                    $sql_2 .= " or  ( ano_movimiento='" . $ano_movimiento[$ii] . "'  and  cod_entidad_bancaria='" . $cod_entidad_bancaria[$ii] . "'  and  cod_sucursal='" . $cod_sucursal[$ii] . "' and  cuenta_bancaria='" . $cuenta_bancaria[$ii] . "' and  numero_cheque='" . $numero_cheque[$ii] . "'      )  ";
                }
            }//fin for


            $datos_cstd06_comprobante_cuerpo_egreso = $this->cstd06_comprobante_cuerpo_egreso->findAll($condicion . " and (" . $sql_2 . ")");
            $aux2 = 0;
            $sql_3 = "";
            foreach ($datos_cstd06_comprobante_cuerpo_egreso as $aux_cstd06_comprobante_cuerpo_egreso) {
                $aux2++;
                $ano_comprobante_egreso[$aux2] = $aux_cstd06_comprobante_cuerpo_egreso['cstd06_comprobante_cuerpo_egreso']['ano_comprobante_egreso'];
                $numero_comprobante_egreso[$aux2] = $aux_cstd06_comprobante_cuerpo_egreso['cstd06_comprobante_cuerpo_egreso']['numero_comprobante_egreso'];
                $ano_movimiento[$aux2] = $aux_cstd06_comprobante_cuerpo_egreso['cstd06_comprobante_cuerpo_egreso']['ano_movimiento'];
                $cod_entidad_bancaria[$aux2] = $aux_cstd06_comprobante_cuerpo_egreso['cstd06_comprobante_cuerpo_egreso']['cod_entidad_bancaria'];
                $cod_sucursal[$aux2] = $aux_cstd06_comprobante_cuerpo_egreso['cstd06_comprobante_cuerpo_egreso']['cod_sucursal'];
                $cuenta_bancaria[$aux2] = $aux_cstd06_comprobante_cuerpo_egreso['cstd06_comprobante_cuerpo_egreso']['cuenta_bancaria'];
                $numero_cheque[$aux2] = $aux_cstd06_comprobante_cuerpo_egreso['cstd06_comprobante_cuerpo_egreso']['numero_cheque'];
                if ($sql_3 == "") {
                    $sql_3 .= "   numero_comprobante_egreso='" . $numero_comprobante_egreso[$aux2] . "'   and  ano_movimiento='" . $ano_movimiento[$aux2] . "'  and  cod_entidad_bancaria='" . $cod_entidad_bancaria[$aux2] . "' and  cod_sucursal='" . $cod_sucursal[$aux2] . "'    and  cuenta_bancaria='" . $cuenta_bancaria[$aux2] . "'  and  numero_cheque='" . $numero_cheque[$aux2] . "'    ";
                } else {
                    $sql_3 .= " or  (numero_comprobante_egreso='" . $numero_comprobante_egreso[$aux2] . "'    and  ano_movimiento='" . $ano_movimiento[$aux2] . "'  and  cod_entidad_bancaria='" . $cod_entidad_bancaria[$aux2] . "'  and  cod_sucursal='" . $cod_sucursal[$aux2] . "'  and  cuenta_bancaria='" . $cuenta_bancaria[$aux2] . "'  and  numero_cheque='" . $numero_cheque[$aux2] . "')  ";
                }
            }//FIN FOR
//echo $condicion.$sql_1." and username_registro='".$this->Session->read('nom_usuario')."'   ";

            $codpresi = $this->Session->read('SScodpresi');
            $codentidad = $this->Session->read('SScodentidad');
            $codtipoinst = $this->Session->read('SScodtipoinst');
            $codinst = $this->Session->read('SScodinst');
            $cod_dep = $this->Session->read('SScoddep');
            $usuario = strtoupper($this->Session->read('nom_usuario'));
            $ano_movimiento = $this->ano_ejecucion();

            $consulta_cheques = "select
	a.cod_presi              as a_cod_presi,
	a.cod_entidad            as a_cod_entidad,
	a.cod_tipo_inst          as a_cod_tipo_inst,
	a.cod_inst               as a_cod_inst,
	a.cod_dep                as a_cod_dep,
	a.username               as a_username,
	a.ano_movimiento         as a_ano_movimiento,
	a.cod_entidad_bancaria   as a_cod_entidad_bancaria,
	a.cod_sucursal           as a_cod_sucursal,
	a.cuenta_bancaria        as a_cuenta_bancaria,
	a.numero_cheque 	     as a_numero_cheque,
	(select b.beneficiario  from cstd03_movimientos_manuales b where
								  a.cod_presi                  = b.cod_presi            and
								  a.cod_entidad                = b.cod_entidad          and
								  a.cod_tipo_inst              = b.cod_tipo_inst        and
								  a.cod_inst                   = b.cod_inst             and
								  a.cod_dep                    = b.cod_dep              and
								  a.cod_entidad_bancaria       = b.cod_entidad_bancaria and
								  a.cod_sucursal               = b.cod_sucursal         and
								  a.cuenta_bancaria            = b.cuenta_bancaria      and
								  a.numero_cheque              = b.numero_documento     and
								  b.tipo_documento             = 4)::text as beneficiario,
	(select b.fecha_documento  from cstd03_movimientos_manuales b where
								  a.cod_presi                  = b.cod_presi            and
								  a.cod_entidad                = b.cod_entidad          and
								  a.cod_tipo_inst              = b.cod_tipo_inst        and
								  a.cod_inst                   = b.cod_inst             and
								  a.cod_dep                    = b.cod_dep              and
								  a.cod_entidad_bancaria       = b.cod_entidad_bancaria and
								  a.cod_sucursal               = b.cod_sucursal         and
								  a.cuenta_bancaria            = b.cuenta_bancaria      and
								  a.numero_cheque              = b.numero_documento     and
								  b.tipo_documento             = 4)::date as fecha_documento,
	(select b.concepto  from cstd03_movimientos_manuales b where
								  a.cod_presi                  = b.cod_presi            and
								  a.cod_entidad                = b.cod_entidad          and
								  a.cod_tipo_inst              = b.cod_tipo_inst        and
								  a.cod_inst                   = b.cod_inst             and
								  a.cod_dep                    = b.cod_dep              and
								  a.cod_entidad_bancaria       = b.cod_entidad_bancaria and
								  a.cod_sucursal               = b.cod_sucursal         and
								  a.cuenta_bancaria            = b.cuenta_bancaria      and
								  a.numero_cheque              = b.numero_documento     and
								  b.tipo_documento             = 4)::text as concepto,
	(select b.monto  from cstd03_movimientos_manuales b where
								  a.cod_presi                  = b.cod_presi            and
								  a.cod_entidad                = b.cod_entidad          and
								  a.cod_tipo_inst              = b.cod_tipo_inst        and
								  a.cod_inst                   = b.cod_inst             and
								  a.cod_dep                    = b.cod_dep              and
								  a.cod_entidad_bancaria       = b.cod_entidad_bancaria and
								  a.cod_sucursal               = b.cod_sucursal         and
								  a.cuenta_bancaria            = b.cuenta_bancaria      and
								  a.numero_cheque              = b.numero_documento     and
								  b.tipo_documento             = 4)::numeric(26,2)   as monto,
	(select c.numero_comprobante_egreso  from cstd06_comprobante_cuerpo_egreso c where
								  a.cod_presi                  = c.cod_presi            and
								  a.cod_entidad                = c.cod_entidad          and
								  a.cod_tipo_inst              = c.cod_tipo_inst        and
								  a.cod_inst                   = c.cod_inst             and
								  a.cod_dep                    = c.cod_dep              and
								  a.cod_entidad_bancaria       = c.cod_entidad_bancaria and
								  a.cod_sucursal               = c.cod_sucursal         and
								  a.cuenta_bancaria            = c.cuenta_bancaria      and
								  a.numero_cheque              = c.numero_cheque)::int4  as numero_comprobante_egreso
	from cstd04_cheque_poremitir a
	where a.cod_presi='$codpresi' and a.cod_entidad='$codentidad' and a.cod_tipo_inst='$codtipoinst' and a.cod_inst='$codinst' and a.cod_dep='$cod_dep' and upper(a.username)='$usuario'
	order by a.cod_entidad_bancaria, a.cod_sucursal, a.cuenta_bancaria, a.numero_cheque";

            $datos22 = $this->cstd04_cheque_poremitir->execute($consulta_cheques);
            $this->set('datos22', $datos22);

            $this->set('cod_entidad_bancaria22', $this->cstd01_entidades_bancarias->findAll());
            $this->set('cod_sucursal22', $this->cstd01_sucursales_bancarias->findAll());
            $this->set('cuenta_bancaria22', $this->cstd02_cuentas_bancarias->findAll($this->condicion()));
        } else if ($opcion == 2) {







            $this->layout = "pdf";






            $datos_cstd04_cheque_poremitir = $this->cstd04_cheque_poremitir->findAll($condicion . " and username='" . $this->Session->read('nom_usuario') . "' ");

            $ii = 0;
            $sql_1 = "";
            $sql_2 = "";

            foreach ($datos_cstd04_cheque_poremitir as $aux_cstd04_cheque_poremitir) {
                $ii++;

                $ano_movimiento[$ii] = $aux_cstd04_cheque_poremitir['cstd04_cheque_poremitir']['ano_movimiento'];
                $cod_entidad_bancaria[$ii] = $aux_cstd04_cheque_poremitir['cstd04_cheque_poremitir']['cod_entidad_bancaria'];
                $cod_sucursal[$ii] = $aux_cstd04_cheque_poremitir['cstd04_cheque_poremitir']['cod_sucursal'];
                $cuenta_bancaria[$ii] = $aux_cstd04_cheque_poremitir['cstd04_cheque_poremitir']['cuenta_bancaria'];
                $numero_cheque[$ii] = $aux_cstd04_cheque_poremitir['cstd04_cheque_poremitir']['numero_cheque'];


                if ($sql_2 == "") {
                    $sql_2 .= "       ano_movimiento='" . $ano_movimiento[$ii] . "'  and  cod_entidad_bancaria='" . $cod_entidad_bancaria[$ii] . "'  and  cod_sucursal='" . $cod_sucursal[$ii] . "' and  cuenta_bancaria='" . $cuenta_bancaria[$ii] . "' and  numero_cheque='" . $numero_cheque[$ii] . "'  ";
                } else {
                    $sql_2 .= " or  ( ano_movimiento='" . $ano_movimiento[$ii] . "'  and  cod_entidad_bancaria='" . $cod_entidad_bancaria[$ii] . "'  and  cod_sucursal='" . $cod_sucursal[$ii] . "' and  cuenta_bancaria='" . $cuenta_bancaria[$ii] . "' and  numero_cheque='" . $numero_cheque[$ii] . "'      )  ";
                }
            }//fin for




            $codpresi = $this->Session->read('SScodpresi');
            $codentidad = $this->Session->read('SScodentidad');
            $codtipoinst = $this->Session->read('SScodtipoinst');
            $codinst = $this->Session->read('SScodinst');
            $cod_dep = $this->Session->read('SScoddep');
            $usuario = strtoupper($this->Session->read('nom_usuario'));
            $ano_movimiento = $this->ano_ejecucion();

            $consulta_cheques = "select
	a.cod_presi              as a_cod_presi,
	a.cod_entidad            as a_cod_entidad,
	a.cod_tipo_inst          as a_cod_tipo_inst,
	a.cod_inst               as a_cod_inst,
	a.cod_dep                as a_cod_dep,
	a.username               as a_username,
	a.ano_movimiento         as a_ano_movimiento,
	a.cod_entidad_bancaria   as a_cod_entidad_bancaria,
	a.cod_sucursal           as a_cod_sucursal,
	a.cuenta_bancaria        as a_cuenta_bancaria,
	a.numero_cheque 	     as a_numero_cheque,
	(select b.beneficiario  from cstd03_movimientos_manuales b where
								  a.cod_presi                  = b.cod_presi            and
								  a.cod_entidad                = b.cod_entidad          and
								  a.cod_tipo_inst              = b.cod_tipo_inst        and
								  a.cod_inst                   = b.cod_inst             and
								  a.cod_dep                    = b.cod_dep              and
								  a.cod_entidad_bancaria       = b.cod_entidad_bancaria and
								  a.cod_sucursal               = b.cod_sucursal         and
								  a.cuenta_bancaria            = b.cuenta_bancaria      and
								  a.numero_cheque              = b.numero_documento     and
								  b.tipo_documento             = 4)::text as beneficiario,
	(select b.fecha_documento  from cstd03_movimientos_manuales b where
								  a.cod_presi                  = b.cod_presi            and
								  a.cod_entidad                = b.cod_entidad          and
								  a.cod_tipo_inst              = b.cod_tipo_inst        and
								  a.cod_inst                   = b.cod_inst             and
								  a.cod_dep                    = b.cod_dep              and
								  a.cod_entidad_bancaria       = b.cod_entidad_bancaria and
								  a.cod_sucursal               = b.cod_sucursal         and
								  a.cuenta_bancaria            = b.cuenta_bancaria      and
								  a.numero_cheque              = b.numero_documento     and
								  b.tipo_documento             = 4)::date as fecha_documento,
	(select b.concepto  from cstd03_movimientos_manuales b where
								  a.cod_presi                  = b.cod_presi            and
								  a.cod_entidad                = b.cod_entidad          and
								  a.cod_tipo_inst              = b.cod_tipo_inst        and
								  a.cod_inst                   = b.cod_inst             and
								  a.cod_dep                    = b.cod_dep              and
								  a.cod_entidad_bancaria       = b.cod_entidad_bancaria and
								  a.cod_sucursal               = b.cod_sucursal         and
								  a.cuenta_bancaria            = b.cuenta_bancaria      and
								  a.numero_cheque              = b.numero_documento     and
								  b.tipo_documento             = 4)::text as concepto,
	(select b.monto  from cstd03_movimientos_manuales b where
								  a.cod_presi                  = b.cod_presi            and
								  a.cod_entidad                = b.cod_entidad          and
								  a.cod_tipo_inst              = b.cod_tipo_inst        and
								  a.cod_inst                   = b.cod_inst             and
								  a.cod_dep                    = b.cod_dep              and
								  a.cod_entidad_bancaria       = b.cod_entidad_bancaria and
								  a.cod_sucursal               = b.cod_sucursal         and
								  a.cuenta_bancaria            = b.cuenta_bancaria      and
								  a.numero_cheque              = b.numero_documento     and
								  b.tipo_documento             = 4)::numeric(26,2)   as monto,
	(select c.numero_comprobante_egreso  from cstd06_comprobante_cuerpo_egreso c where
								  a.cod_presi                  = c.cod_presi            and
								  a.cod_entidad                = c.cod_entidad          and
								  a.cod_tipo_inst              = c.cod_tipo_inst        and
								  a.cod_inst                   = c.cod_inst             and
								  a.cod_dep                    = c.cod_dep              and
								  a.cod_entidad_bancaria       = c.cod_entidad_bancaria and
								  a.cod_sucursal               = c.cod_sucursal         and
								  a.cuenta_bancaria            = c.cuenta_bancaria      and
								  a.numero_cheque              = c.numero_cheque)::int4  as numero_comprobante_egreso
	from cstd04_cheque_poremitir a
	where a.cod_presi='$codpresi' and a.cod_entidad='$codentidad' and a.cod_tipo_inst='$codtipoinst' and a.cod_inst='$codinst' and a.cod_dep='$cod_dep' and upper(a.username)='$usuario'
	order by a.cod_entidad_bancaria, a.cod_sucursal, a.cuenta_bancaria, a.numero_cheque";

            $datos22 = $this->cstd04_cheque_poremitir->execute($consulta_cheques);
            $entidades = $this->cstd01_entidades_bancarias->findAll();
            $filas = $this->cstd04_cheque_poremitir->findCount($this->SQLCA() . " and upper(username)='$usuario'");
            //$datos22=$this->cstd04_cheque_poremitir->execute($sql);

            $this->set('entidad', $entidades);
            $this->set('filas', $filas);
            $this->set('datos22', $datos22);

            $this->set('titulo_inst', $this->Session->read('entidad_federal'));
            $this->set('titulo_a', $this->Session->read('dependencia'));




            $datos_cstd06_comprobante_cuerpo_egreso = $this->cstd06_comprobante_cuerpo_egreso->findAll($condicion . " and (" . $sql_2 . ")");


            $aux2 = 0;
            $aux = 0;


            $datos_cugd02_dependencias = $this->cugd02_dependencia->findAll("cod_tipo_institucion='" . $this->Session->read('SScodtipoinst') . "' and cod_institucion='" . $this->Session->read('SScodinst') . "'  ");





            $resul = $this->cugd02_institucion->findAll("cod_tipo_institucion = " . $cod_tipo_inst . " and cod_institucion = " . $cod_inst . " ");



            $this->set('agente_retencion_institucion', $resul[0]['cugd02_institucion']['agente_retencion']);
            $this->set('rif_institucion', $resul[0]['cugd02_institucion']['rif']);
            $this->set('denominacion_institucion', $resul[0]['cugd02_institucion']['denominacion']);



            $this->set('datos_cstd06_comprobante_cuerpo_egreso', $datos_cstd06_comprobante_cuerpo_egreso);
            $this->set('datos_cstd04_cheque_poremitir', $datos_cstd04_cheque_poremitir);




            $this->set('cod_entidad_bancaria22', $this->cstd01_entidades_bancarias->findAll());
            $this->set('cod_sucursal22', $this->cstd01_sucursales_bancarias->findAll());
            $this->set('cuenta_bancaria22', $this->cstd02_cuentas_bancarias->findAll($condicion));
            $this->set('datos_cugd02_dependencias', $datos_cugd02_dependencias);




///borra todo

            $sql = "delete from cstd04_cheque_poremitir  where cod_presi=" . $this->Session->read('SScodpresi') . " and cod_entidad=" . $this->Session->read('SScodentidad') . " and cod_tipo_inst=" . $this->Session->read('SScodtipoinst') . " and cod_inst=" . $this->Session->read('SScodinst') . " and cod_dep=" . $this->Session->read('SScoddep') . " and username='" . $this->Session->read('nom_usuario') . "' ";
            $this->cstd04_cheque_poremitir->execute($sql);
        }//fin else





        $this->set('cod_entidad_bancaria', $this->cstd01_entidades_bancarias->findAll());
        $this->set('cod_sucursal', $this->cstd01_sucursales_bancarias->findAll());
        $this->set('cuenta_bancaria', $this->cstd02_cuentas_bancarias->findAll($this->condicion()));
        $this->set('titulo_a', $this->Session->read('dependencia'));
        $this->set('entidad_federal', $this->Session->read('entidad_federal'));
    }

    function reporte_disponibilidad_por_subp($opcion = null) {


        if ($opcion == 1) {

            $this->layout = "ajax";
            $this->set('entidad_federal', $this->Session->read('entidad_federal'));
            $ano = $this->ano_ejecucion();
            $this->set('ano', $ano);
            $this->Session->write('ano_reporte', $ano);
            if ($this->verifica_SS(5) == 1) {
                $cond = $this->SQLCA_report(1);
            } else {
                $cond = $this->SQLCA_report();
            }
            $rs = $this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_sector,deno_sector FROM v_cfpd05_denominaciones WHERE " . $cond . " and ano=" . $ano . " ORDER BY cod_sector ASC");
            foreach ($rs as $l) {
                $v[] = $l[0]["cod_sector"];
                $d[] = $l[0]["deno_sector"];
            }
            $lista = array_combine($v, $d);
            $rsp = $this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_partida,deno_partida FROM v_cfpd05_denominaciones  WHERE " . $cond . " and ano=" . $ano . " ORDER BY cod_partida ASC");
            foreach ($rsp as $lp) {
                $vp[] = $lp[0]["cod_partida"];
                $dp[] = $lp[0]["deno_partida"];
            }
            $partida = array_combine($vp, $dp);
            $this->concatena($lista, 'sector');
            $this->concatena($partida, 'partida');
            //$this->set('partida',$partida);
            if (isset($var)) {
                $this->set('tipo_reporte', $var);
            } else {
                $this->set('tipo_reporte', "reporte_balance_ejecucion");
            }
        } else {

            $this->layout = "pdf";
            $this->set('entidad_federal', $this->Session->read('entidad_federal'));
            if (isset($this->data["reporte"]["ano"]) && !empty($this->data["reporte"]["ano"])) {
                $Ano = $this->data["reporte"]["ano"];
            } else {
                $Ano = $this->ano_ejecucion();
            }

            $this->set('ANO', $Ano);
            if (isset($this->data['cfpp05']['consolidacion'])) {
                $con = $this->SQLCA_consolidado($this->data['cfpp05']['consolidacion']);
                $modelo = $this->data['cfpp05']['consolidacion'] == 1 ? "cfpd05_disponiblidad_rep" : "cfpd05_disponiblidad_rep";
                $this->set("modelo", $modelo);
            } else {
                $con = $this->SQLCA_consolidado();
                $modelo = "cfpd05_disponiblidad_rep";
                $this->set("modelo", $modelo);
            }

            $titulo_a = $this->Session->read('dependencia');
            $this->set('titulo_a', $titulo_a);

            if (isset($this->data["reporte"]["cod_sector"]) && $this->data["reporte"]["cod_sector"] != "")
                $cod_sector = " cod_sector=" . $this->data["reporte"]["cod_sector"] . " and ";
            else
                $cod_sector = " 1=1 and ";
            if (isset($this->data["reporte"]["cod_programa"]) && $this->data["reporte"]["cod_programa"] != "")
                $cod_programa = " cod_programa=" . $this->data["reporte"]["cod_programa"] . " and ";
            else
                $cod_programa = " 1=1 and ";
            if (isset($this->data["reporte"]["cod_subprograma"]) && $this->data["reporte"]["cod_subprograma"] != "")
                $cod_sub_prog = " cod_sub_prog=" . $this->data["reporte"]["cod_subprograma"] . " and ";
            else
                $cod_sub_prog = " 1=1 and ";
            if (isset($this->data["reporte"]["cod_proyecto"]) && $this->data["reporte"]["cod_proyecto"] != "")
                $cod_proyecto = " cod_proyecto=" . $this->data["reporte"]["cod_proyecto"] . " and ";
            else
                $cod_proyecto = " 1=1 and ";
            if (isset($this->data["reporte"]["cod_actividad"]) && $this->data["reporte"]["cod_actividad"] != "")
                $cod_activ_obra = " cod_activ_obra=" . $this->data["reporte"]["cod_actividad"] . " ";
            else
                $cod_activ_obra = " 1=1 ";
            if (isset($this->data["reporte"]["cod_partida"]) && $this->data["reporte"]["cod_partida"] != "")
                $cod_partida = " cod_partida=" . $this->data["reporte"]["cod_partida"] . " ";
            else
                $cod_partida = " 1=1 ";
            if (isset($this->data["reporte"]["cod_generica"]) && $this->data["reporte"]["cod_generica"] != "")
                $cod_generica = " cod_generica=" . $this->data["reporte"]["cod_generica"] . " and ";
            else
                $cod_generica = " 1=1 and ";
            if (isset($this->data["reporte"]["cod_especifica"]) && $this->data["reporte"]["cod_especifica"] != "")
                $cod_especifica = " cod_especifica=" . $this->data["reporte"]["cod_especifica"] . " and ";
            else
                $cod_especifica = " 1=1 and ";
            if (isset($this->data["reporte"]["cod_subespecifica"]) && $this->data["reporte"]["cod_subespecifica"] != "")
                $cod_sub_espec = " cod_sub_espec=" . $this->data["reporte"]["cod_subespecifica"] . " and ";
            else
                $cod_sub_espec = " 1=1 and ";
            if (isset($this->data["reporte"]["cod_auxiliar"]) && $this->data["reporte"]["cod_auxiliar"] != "")
                $cod_auxiliar = " cod_auxiliar=" . $this->data["reporte"]["cod_auxiliar"] . " ";
            else
                $cod_auxiliar = " 1=1 ";

            $modo = (int) $this->data["reporte"]["modo"];
            // echo "MODO: ".$modo;
            switch ($modo) {
                case 1:
                    //completo todo
                    $condicion = " 1=1";
                    break;
                case 2:
                    //por categoria
                    $condicion = " " . $cod_sector . $cod_programa . $cod_sub_prog . $cod_proyecto . $cod_activ_obra;
                    break;
                case 3:
                    //por categoria y partida
                    $condicion = " " . $cod_sector . $cod_programa . $cod_sub_prog . $cod_proyecto . $cod_activ_obra . " and " . $cod_partida . " and " . $cod_generica . $cod_especifica . $cod_sub_espec . $cod_auxiliar;
                    break;
                case 4:
                    $condicion = " " . $cod_partida;
                    break;
                case 5:
                    $condicion = " " . $cod_partida . " and " . $cod_generica . $cod_especifica . $cod_sub_espec . $cod_auxiliar;
                    break;
                default: $condicion = " 1=1";
            }//fin switch
            //echo $condicion;


            $total_spsppa = $this->cfpd30_reintegro_cuerpo->execute("SELECT * FROM " . $modelo . " WHERE " . $con . " and ano=" . $Ano . " and " . $condicion . "   ORDER BY cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra, cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC");
            $this->set('cfpd05', $total_spsppa);
        }

        $this->set('opcion', $opcion);
    }

    function reporte_disponibilidad_simple($opcion = null) {


        if ($opcion == 1) {

            $this->layout = "ajax";
            $this->set('entidad_federal', $this->Session->read('entidad_federal'));
            $ano = $this->ano_ejecucion();
            $this->set('ano', $ano);
            $this->Session->write('ano_reporte', $ano);
            if ($this->verifica_SS(5) == 1) {
                $cond = $this->SQLCA_report(1);
            } else {
                $cond = $this->SQLCA_report();
            }
            $rs = $this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_sector,deno_sector FROM v_cfpd05_denominaciones WHERE " . $cond . " and ano=" . $ano . " ORDER BY cod_sector ASC");
            foreach ($rs as $l) {
                $v[] = $l[0]["cod_sector"];
                $d[] = $l[0]["deno_sector"];
            }
            $lista = array_combine($v, $d);
            $rsp = $this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_partida,deno_partida FROM v_cfpd05_denominaciones  WHERE " . $cond . " and ano=" . $ano . " ORDER BY cod_partida ASC");
            foreach ($rsp as $lp) {
                $vp[] = $lp[0]["cod_partida"];
                $dp[] = $lp[0]["deno_partida"];
            }
            $partida = array_combine($vp, $dp);
            $this->concatena($lista, 'sector');
            $this->concatena($partida, 'partida');
            //$this->set('partida',$partida);
            if (isset($var)) {
                $this->set('tipo_reporte', $var);
            } else {
                $this->set('tipo_reporte', "reporte_balance_ejecucion");
            }
        } else {

            $this->layout = "pdf";
            $this->set('entidad_federal', $this->Session->read('entidad_federal'));
            if (isset($this->data["reporte"]["ano"]) && !empty($this->data["reporte"]["ano"])) {
                $Ano = $this->data["reporte"]["ano"];
            } else {
                $Ano = $this->ano_ejecucion();
            }

            $this->set('ANO', $Ano);
            if (isset($this->data['cfpp05']['consolidacion'])) {
                $con = $this->SQLCA_consolidado($this->data['cfpp05']['consolidacion']);
                $modelo = $this->data['cfpp05']['consolidacion'] == 1 ? "cfpd05_disponiblidad_rep" : "cfpd05_disponiblidad_rep";
                $this->set("modelo", $modelo);
            } else {
                $con = $this->SQLCA_consolidado();
                $modelo = "cfpd05_disponiblidad_rep";
                $this->set("modelo", $modelo);
            }

            $titulo_a = $this->Session->read('dependencia');
            $this->set('titulo_a', $titulo_a);

            if (isset($this->data["reporte"]["cod_sector"]) && $this->data["reporte"]["cod_sector"] != "")
                $cod_sector = " cod_sector=" . $this->data["reporte"]["cod_sector"] . " and ";
            else
                $cod_sector = " 1=1 and ";
            if (isset($this->data["reporte"]["cod_programa"]) && $this->data["reporte"]["cod_programa"] != "")
                $cod_programa = " cod_programa=" . $this->data["reporte"]["cod_programa"] . " and ";
            else
                $cod_programa = " 1=1 and ";
            if (isset($this->data["reporte"]["cod_subprograma"]) && $this->data["reporte"]["cod_subprograma"] != "")
                $cod_sub_prog = " cod_sub_prog=" . $this->data["reporte"]["cod_subprograma"] . " and ";
            else
                $cod_sub_prog = " 1=1 and ";
            if (isset($this->data["reporte"]["cod_proyecto"]) && $this->data["reporte"]["cod_proyecto"] != "")
                $cod_proyecto = " cod_proyecto=" . $this->data["reporte"]["cod_proyecto"] . " and ";
            else
                $cod_proyecto = " 1=1 and ";
            if (isset($this->data["reporte"]["cod_actividad"]) && $this->data["reporte"]["cod_actividad"] != "")
                $cod_activ_obra = " cod_activ_obra=" . $this->data["reporte"]["cod_actividad"] . " ";
            else
                $cod_activ_obra = " 1=1 ";
            if (isset($this->data["reporte"]["cod_partida"]) && $this->data["reporte"]["cod_partida"] != "")
                $cod_partida = " cod_partida=" . $this->data["reporte"]["cod_partida"] . " ";
            else
                $cod_partida = " 1=1 ";
            if (isset($this->data["reporte"]["cod_generica"]) && $this->data["reporte"]["cod_generica"] != "")
                $cod_generica = " cod_generica=" . $this->data["reporte"]["cod_generica"] . " and ";
            else
                $cod_generica = " 1=1 and ";
            if (isset($this->data["reporte"]["cod_especifica"]) && $this->data["reporte"]["cod_especifica"] != "")
                $cod_especifica = " cod_especifica=" . $this->data["reporte"]["cod_especifica"] . " and ";
            else
                $cod_especifica = " 1=1 and ";
            if (isset($this->data["reporte"]["cod_subespecifica"]) && $this->data["reporte"]["cod_subespecifica"] != "")
                $cod_sub_espec = " cod_sub_espec=" . $this->data["reporte"]["cod_subespecifica"] . " and ";
            else
                $cod_sub_espec = " 1=1 and ";
            if (isset($this->data["reporte"]["cod_auxiliar"]) && $this->data["reporte"]["cod_auxiliar"] != "")
                $cod_auxiliar = " cod_auxiliar=" . $this->data["reporte"]["cod_auxiliar"] . " ";
            else
                $cod_auxiliar = " 1=1 ";

            $modo = (int) $this->data["reporte"]["modo"];
            // echo "MODO: ".$modo;
            switch ($modo) {
                case 1:
                    //completo todo
                    $condicion = " 1=1";
                    break;
                case 2:
                    //por categoria
                    $condicion = " " . $cod_sector . $cod_programa . $cod_sub_prog . $cod_proyecto . $cod_activ_obra;
                    break;
                case 3:
                    //por categoria y partida
                    $condicion = " " . $cod_sector . $cod_programa . $cod_sub_prog . $cod_proyecto . $cod_activ_obra . " and " . $cod_partida . " and " . $cod_generica . $cod_especifica . $cod_sub_espec . $cod_auxiliar;
                    break;
                case 4:
                    $condicion = " " . $cod_partida;
                    break;
                case 5:
                    $condicion = " " . $cod_partida . " and " . $cod_generica . $cod_especifica . $cod_sub_espec . $cod_auxiliar;
                    break;
                default: $condicion = " 1=1";
            }//fin switch
            //echo $condicion;


            $total_spsppa = $this->cfpd30_reintegro_cuerpo->execute("SELECT * FROM " . $modelo . " WHERE " . $con . " and ano=" . $Ano . " and " . $condicion . "   ORDER BY cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra, cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC");
            $this->set('cfpd05', $total_spsppa);
        }

        $this->set('opcion', $opcion);
    }

    function escribir_ano_reintegro($ano = null) {

        $this->layout = "ajax";

        if ($ano != null) {
            $this->Session->write('escribir_ano_reintegro', $ano);
        } else {
            $ano = $this->ano_ejecucion();
            $this->Session->write('escribir_ano_reintegro', $ano);
        }
        echo "<script>";
        echo "document.getElementById('reporte_registro_compromiso_opcion_1').checked=false;";
        echo "document.getElementById('reporte_registro_compromiso_opcion_2').checked=false;";
        echo "</script>";
    }

//fin function

    function escribir_ano_rendiciones($ano = null) {

        $this->layout = "ajax";

        if ($ano != null) {
            $this->Session->write('escribir_ano_rendiciones', $ano);
        } else {
            $ano = $this->ano_ejecucion();
            $this->Session->write('escribir_ano_rendiciones', $ano);
        }
        echo "<script>";
        echo "document.getElementById('reporte_registro_compromiso_opcion_1').checked=false;";
        echo "document.getElementById('reporte_registro_compromiso_opcion_2').checked=false;";
        echo "</script>";
    }

//fin function

    function reporte_cfpd30_reintegros($ir = null, $var = null) {
//	echo $ir."    ".$var;
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        if ($ir == 'si') {
            $this->layout = "ajax";
            $ano = $this->ano_ejecucion();
            $this->Session->write('escribir_ano_reintegro', $ano);
            $this->set('ir', 'si');
            $this->set('year', $ano);
        }//fin ir si
        else if ($ir == 'vista') {
            $this->layout = "ajax";
            $this->set('ir', 'vista');
            if ($var == 1) {
                $this->set('vista', 'opcion');
            } else if ($var == 2) {
                $this->set('vista', 'select');
                $ano1 = $this->Session->read('escribir_ano_reintegro');
                $dep = $this->cfpd30_reintegro_cuerpo->generateList($conditions = $this->SQLCA() . " and ano_reintegro=" . $ano1, "numero_reintegro asc", $limit = null, '{n}.cfpd30_reintegro_cuerpo.numero_reintegro', '{n}.cfpd30_reintegro_cuerpo.numero_reintegro');
                $this->set('numero', $dep);
//			$this->concatena($dep, 'numero');
            }
        }//fin ir vista
        else if ($ir == 'boton') {
            $this->layout = "ajax";
            $this->set('ir', 'boton');
        }//fin ir boton
        else if ($ir == 'no') {
            $this->layout = "pdf";
            $this->set('ir', 'no');
            $ano = $this->data['cepp01_compromiso']['ano'];
            $ver = $this->data['cepp01_compromiso']['radio_ver'];
            if ($ver == 1) {
                if (isset($this->data['cepp01_compromiso']['opciones'])) {
                    $opcion = $this->data['cepp01_compromiso']['opciones'];
                    if ($opcion == 1) {
                        $datos = $this->v_cfpd30_reintegros_generales->FindAll($this->SQLCA() . " and ano_reintegro=" . $ano);
                    } else if ($opcion == 2) {
                        $datos = $this->v_cfpd30_reintegros_generales->FindAll($this->SQLCA() . " and condicion_actividad=1 and ano_reintegro=" . $ano);
                    } else if ($opcion == 3) {
                        $datos = $this->v_cfpd30_reintegros_generales->FindAll($this->SQLCA() . " and condicion_actividad=2 and ano_reintegro=" . $ano);
                    }
                    $this->set('datos', $datos);
//					pr($datos);
                } else {
                    $this->set('vacio', '');
                }
            } else {
                $numero = $this->data['cepp01_compromiso']['solicitud'];
                if ($numero != "") {
                    $datos = $this->v_cfpd30_reintegros_generales->FindAll($this->SQLCA() . " and ano_reintegro=" . $ano . " and numero_reintegro=" . $numero);
                    $this->set('datos', $datos);
                } else {
                    $this->set('vacio', '');
                }
                //		pr($datos);
            }
        }//fin ir no
    }

//fin reporte_cfpd30_reintegros

    function reporte_compromiso_tipocompromiso() {
        $this->layout = "pdf";
        $datos = $this->cepd01_tipo_compromiso->findAll(null, null, 'cod_tipo_compromiso ASC');
        $this->set('datos', $datos);
        $this->set('titulo_a', $this->Session->read('dependencia'));
        $this->set('titulo_inst', 'GOBERNACIÓN DEL ESTADO FALCÓN');
    }

    function analitico_solicitud_recurso($ir = null, $var = null) {
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        if ($ir == 'si') {
            $this->layout = "ajax";
            if ($cod_dep == 1) {
                $dep = $this->arrd05->generateList($conditions = $this->condicionNDEP(), $order = 'cod_dep', $limit = null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
                $this->concatena($dep, 'dependencias');
            } else {
                $dep = $this->arrd05->generateList($conditions = $this->condicion(), $order = 'cod_dep', $limit = null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
                $this->concatena($dep, 'dependencias');
            }
            $ano = $this->ano_ejecucion();
            $this->set('year', $ano);
            $this->set('ir', 'si');
        } else if ($ir == 'select') {
            $this->layout = "ajax";
            if ($var != "") {
                $ano11 = $this->ano_ejecucion();
                $lista = $this->v_solicitud_cfpd05_p2->execute("select distinct cod_activ_obra from v_solicitud_cfpd05_p2 where " . $this->condicionNDEP() . " and cod_dep=" . $var . " and ano=" . $ano11);
                if ($lista != null) {
                    foreach ($lista as $l) {
                        $v[] = $l[0]["cod_activ_obra"];
                    }
                    $lista = array_combine($v, $v);
                    $this->set('activ', $lista);
                } else {
                    $this->set('activ', array());
                }
            } else {
                $this->set('activ', array());
            }
        } else if ($ir == 'no') {
            $this->layout = "pdf";
//	pr($this->data);
            $ano = $this->data['organismo']['ano'];
            $tiempo = $this->data['organismo']['tiempo'];
            if (isset($this->data['organismo']['tipo_peticion'])) {
                $tipo_peticion = $this->data['organismo']['tipo_peticion'];
                if ($ano == '' || $tipo_peticion == '' || $tiempo == '') {
                    echo'<script>history.back(1);</script>';
                } else {
                    if ($tipo_peticion == 1) {
                        if ($tiempo == 1) {
                            $datos = $this->v_csrd01_analitico_solicitud_recurso->findAll($this->condicionNDEP() . " and ano_solicitud=" . $ano, null, 'order by cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,fecha_solicitud,numero_solicitud');
                        } else {
                            $mes_solicitud = $this->data['organismo']['mes_solicitud'];
                            if ($mes_solicitud == '') {
                                echo'<script>history.back(1);</script>';
                            } else {
//									$datos=$this->v_csrd01_analitico_solicitud_recurso->findAll($this->condicionNDEP()." and ano_solicitud=".$ano." and mes_solicitud=".$mes_solicitud,null,'order by cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,fecha_solicitud,numero_solicitud');
                                $datos = $this->v_csrd01_analitico_solicitud_recurso->findAll($this->condicionNDEP() . " and ano_solicitud=" . $ano . " and substr(fecha_solicitud::text,6,2)::integer=" . $mes_solicitud, null, 'order by cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,fecha_solicitud,numero_solicitud');
                            }
                        }
                    } else {
                        $depe = $this->data['organismo']['cod_dep'];
                        $activi = $this->data['organismo']['actividad'];
                        if ($tiempo == 1) {
                            if ($activi == '' || $depe == '') {
                                echo'<script>history.back(1);</script>';
                            } else {
                                $datos = $this->v_csrd01_analitico_solicitud_recurso->findAll($this->condicionNDEP() . " and ano_solicitud=" . $ano . " and cod_dep=" . $depe . " and cod_activ_obra=" . $activi, null, 'order by cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,fecha_solicitud,numero_solicitud');
                            }
                        } else {
                            $mes_solicitud = $this->data['organismo']['mes_solicitud'];
                            if ($activi == '' || $depe == '' || $mes_solicitud == '') {
                                echo'<script>history.back(1);</script>';
                            } else {
                                $datos = $this->v_csrd01_analitico_solicitud_recurso->findAll($this->condicionNDEP() . " and ano_solicitud=" . $ano . " and cod_dep=" . $depe . " and cod_activ_obra=" . $activi . " and substr(fecha_solicitud::text,6,2)::integer=" . $mes_solicitud, null, 'order by cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,fecha_solicitud,numero_solicitud');
                            }
                        }
                    }
                }
            } else {
                if ($tiempo == 1) {
                    $datos = $this->v_csrd01_analitico_solicitud_recurso->findAll($this->condicion() . " and ano_solicitud=" . $ano, null, 'order by cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,fecha_solicitud,numero_solicitud');
                } else {
                    $mes_solicitud = $this->data['organismo']['mes_solicitud'];
                    if ($mes_solicitud == '') {
                        echo'<script>history.back(1);</script>';
                    } else {
                        $datos = $this->v_csrd01_analitico_solicitud_recurso->findAll($this->condicion() . " and ano_solicitud=" . $ano . " and substr(fecha_solicitud::text,6,2)::integer=" . $mes_solicitud, null, 'order by cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,fecha_solicitud,numero_solicitud');
                    }
                }
            }
//		pr($datos);
            $this->set('datos', $datos);
            $this->set('ir', 'no');
        } else if ($ir == 'dep') {
            $this->layout = "ajax";
            if ($var != "") {
                $deno_nomina = $this->arrd05->field('denominacion', $conditions = $this->condicionNDEP() . " and cod_dep='$var'", $order = "cod_dep ASC");
                $this->set('dep', $deno_nomina);
            } else {
                $this->set('dep', '');
            }
            $this->set('cod_dep', $var);
            $this->Session->delete('depen');
            $this->Session->write('depen', $var);
        } else if ($ir == 'actividad') {
            $this->layout = "ajax";
//		echo $this->Session->read('depen')."  ".$var;
            $deno_nomina = $this->cfpd02_activ_obra->field('denominacion', "cod_dep=" . $this->Session->read('depen') . " and cod_activ_obra=" . $var, $order = "cod_activ_obra ASC");
            $this->set('actividad', $deno_nomina);
        } else if ($ir == 'boton') {
            $this->layout = "ajax";
            $this->set('boton', '');
        } else if ($ir == 'peticion') {
            $this->layout = "ajax";
            $this->set('peticion', 'si');
            if ($var == 1) {
                $this->set('peticion1', '');
            } else {
                if ($cod_dep == 1) {
                    $dep = $this->arrd05->generateList($conditions = $this->condicionNDEP(), $order = 'cod_dep', $limit = null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
                    $this->concatena($dep, 'dependencias');
                } else {
                    $dep = $this->arrd05->generateList($conditions = $this->condicion(), $order = 'cod_dep', $limit = null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
                    $this->concatena($dep, 'dependencias');
                }
                $this->set('peticion2', '');
            }
        } else if ($ir == 'tiempo') {
            $this->layout = "ajax";
            $this->set('tiempo', '');
            if ($var == 1) {
                $this->set('tiempo1', '');
            } else {
                $meses = array('1' => 'Enero', '2' => 'Febrero', '3' => 'Marzo', '4' => 'Abril', '5' => 'Mayo', '6' => 'Junio', '7' => 'Julio', '8' => 'Agosto', '9' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre');
                $this->concatena($meses, 'mes');
                $this->set('tiempo2', '');
            }
        }
    }

// fin analitico_solicitud_recurso

    function partidas_en_cero() {
        $this->layout = "pdf";
        $mes = date("m");
        $ano = $this->ano_ejecucion();
        switch ($mes) {
            case 1:
                $datos = $this->v_partidas_nodisponibilidad_fecha->execute("select * from v_partidas_nodisponibilidad_fecha where " . $this->condicionNDEP() . " and asignacion_anual_actualizada_ene<=0 and ano=" . $ano);
                $this->set('mes', '_ene');
                break;
            case 2:
                $datos = $this->v_partidas_nodisponibilidad_fecha->execute("select * from v_partidas_nodisponibilidad_fecha where " . $this->condicionNDEP() . " and asignacion_anual_actualizada_feb<=0 and ano=" . $ano);
                $this->set('mes', '_feb');
                break;
            case 3:
                $datos = $this->v_partidas_nodisponibilidad_fecha->execute("select * from v_partidas_nodisponibilidad_fecha where " . $this->condicionNDEP() . " and asignacion_anual_actualizada_mar<=0 and ano=" . $ano);
                $this->set('mes', '_mar');
                break;
            case 4:
                $datos = $this->v_partidas_nodisponibilidad_fecha->execute("select * from v_partidas_nodisponibilidad_fecha where " . $this->condicionNDEP() . " and asignacion_anual_actualizada_abr<=0 and ano=" . $ano);
                $this->set('mes', '_abr');
                break;
            case 5:
                $datos = $this->v_partidas_nodisponibilidad_fecha->execute("select * from v_partidas_nodisponibilidad_fecha where " . $this->condicionNDEP() . " and asignacion_anual_actualizada_may<=0 and ano=" . $ano);
                $this->set('mes', '_may');
                break;
            case 6:
                $datos = $this->v_partidas_nodisponibilidad_fecha->execute("select * from v_partidas_nodisponibilidad_fecha where " . $this->condicionNDEP() . " and asignacion_anual_actualizada_jun<=0 and ano=" . $ano);
                $this->set('mes', '_jun');
                break;
            case 7:
                $datos = $this->v_partidas_nodisponibilidad_fecha->execute("select * from v_partidas_nodisponibilidad_fecha where " . $this->condicionNDEP() . " and asignacion_anual_actualizada_jul<=0 and ano=" . $ano);
                $this->set('mes', '_jul');
                break;
            case 8:
                $datos = $this->v_partidas_nodisponibilidad_fecha->execute("select * from v_partidas_nodisponibilidad_fecha where " . $this->condicionNDEP() . " and asignacion_anual_actualizada_ago<=0 and ano=" . $ano);
                $this->set('mes', '_ago');
                break;
            case 9:
                $datos = $this->v_partidas_nodisponibilidad_fecha->execute("select * from v_partidas_nodisponibilidad_fecha where " . $this->condicionNDEP() . " and asignacion_anual_actualizada_sep<=0 and ano=" . $ano);
                $this->set('mes', '_sep');
                break;
            case 10:
                $datos = $this->v_partidas_nodisponibilidad_fecha->execute("select * from v_partidas_nodisponibilidad_fecha where " . $this->condicionNDEP() . " and asignacion_anual_actualizada_oct<=0 and ano=" . $ano);
                $this->set('mes', '_oct');
                break;
            case 11:
                $datos = $this->v_partidas_nodisponibilidad_fecha->execute("select * from v_partidas_nodisponibilidad_fecha where " . $this->condicionNDEP() . " and asignacion_anual_actualizada_nov<=0 and ano=" . $ano);
                $this->set('mes', '_nov');
                break;
            case 12:
                $datos = $this->v_partidas_nodisponibilidad_fecha->execute("select * from v_partidas_nodisponibilidad_fecha where " . $this->condicionNDEP() . " and asignacion_anual_actualizada_dic<=0 and ano=" . $ano);
                $this->set('mes', '_dic');
                break;
        }
        $this->set('datos', $datos);
    }

    function casd01_reporte_solicitudes($ir = null, $var1 = null, $var2 = null, $var3 = null) {
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        if ($ir == 'no') {
            $this->layout = "ajax";
            $this->set('ir', 'no');
            $this->Session->write('filtrar', 2);
        } else if ($ir == 'solicitud') {
            $this->layout = "ajax";
            if ($var1 == 2) {
                $this->set('solicitud', '');
                echo "<script>";
                echo "document.getElementById('persona').innerHTML='';";
                echo "</script>";
            } else if ($var1 == 3) {
                $this->set('solicitud', 'fecha');
            }
        } else if ($ir == 'buscar_datos') {
            $this->layout = "ajax";
            $this->set("opcion", $var1);
            $this->set('buscar_datos', '');
            $this->Session->delete('pista');
        } else if ($ir == 'buscar_por_pista') {
            $this->layout = "ajax";
            if ($var3 == null) {
                $var2 = strtoupper($var2);
                $this->Session->write('pista', $var2);
//					if(is_int($var2)){$sql   = " (cod_tipo_nomina='$nomina' and denominacion_busqueda LIKE '%$var2%')  or   ";}else{ $sql = "";}
                $Tfilas = $this->casd01_datos_personales->findCount("cedula_identidad::text LIKE '%$var2%' or upper(apellidos_nombres::text) LIKE upper('%" . $var2 . "%') ");
//					        echo "cod_tipo_nomina='$nomina' and denominacion_busqueda LIKE '%$var2%'";
                if ($Tfilas != 0) {
                    $pagina = 1;
                    $Tfilas = (int) ceil($Tfilas / 100);
                    $this->set('pag_cant', $pagina . '/' . $Tfilas);
                    $this->set('total_paginas', $Tfilas);
                    $this->set('pagina_actual', $pagina);
                    $this->set('ultimo', $Tfilas);
                    $datos_filas = $this->casd01_datos_personales->findAll("cedula_identidad::text LIKE '%$var2%' or upper(apellidos_nombres::text) LIKE upper('%" . $var2 . "%')", null, "cedula_identidad ASC", 100, 1, null);
                    $this->set("datosFILAS", $datos_filas);
                    $this->set('siguiente', $pagina + 1);
                    $this->set('anterior', $pagina - 1);
                    $this->bt_nav($Tfilas, $pagina);
                } else {
                    $this->set("datosFILAS", '');
                }
            } else {
                $var22 = $this->Session->read('pista');
                $var22 = strtoupper($var22);
                $Tfilas = $this->casd01_datos_personales->findCount("cedula_identidad::text LIKE '%$var22%' or upper(apellidos_nombres::text) LIKE upper('%" . $var22 . "%')");
                if ($Tfilas != 0) {
                    $pagina = $var3;
                    $Tfilas = (int) ceil($Tfilas / 100);
                    $this->set('pag_cant', $pagina . '/' . $Tfilas);
                    $this->set('total_paginas', $Tfilas);
                    $this->set('pagina_actual', $pagina);
                    $this->set('ultimo', $Tfilas);
//						     	    $datos_filas=$this->cscd01_catalogo->findAll($sql." (denominacion LIKE '%$var22%')  OR  (cod_snc LIKE '%$var22%')   ",null,"codigo_prod_serv ASC",100,$pagina,null);
                    $datos_filas = $this->casd01_datos_personales->findAll("cedula_identidad::text LIKE '%$var2%' or upper(apellidos_nombres::text) LIKE upper('%" . $var22 . "%')", null, "cedula_identidad ASC", 100, $pagina, null);
                    $this->set("datosFILAS", $datos_filas);
                    $this->set('siguiente', $pagina + 1);
                    $this->set('anterior', $pagina - 1);
                    $this->bt_nav($Tfilas, $pagina);
                } else {
                    $this->set("datosFILAS", '');
                }
            }//fin else
            $this->set("opcion", $var1);
            $this->set("buscar_pista", '');
        } else if ($ir == 'seleccion_busqueda') {
            $this->layout = "ajax";
            $this->set("seleccion_busqueda", '');
            $sql = "select cedula_identidad,apellidos_nombres from casd01_datos_personales where cedula_identidad='$var2'";
            $dato = $this->casd01_datos_personales->execute($sql);
            $this->set('dato', $dato);
        } else if ($ir == 'si') {
            $this->layout = "pdf";
            $tipo_peticion = $this->data['casp01']['tipo_peticion'];
            $tipo_solicitud = $this->data['casp01']['solicitud'];
            $promotor = $this->data['casp01']['promotor'];

            if ($tipo_peticion == 2) {
                $cond = $this->condicionNDEP();
                $cond1 = $this->condicionNDEP() . " and";
            } else if ($tipo_peticion == 3) {
                $cond = $this->SQLCA();
                $cond1 = $this->SQLCA() . " and";
            }

            if ($promotor == 1) {
                if ($tipo_solicitud == 1) {
                    if ($tipo_peticion == 1) {
                        $datos = $this->v_casp01_relacion_solicitudes->execute("select * from v_casp01_relacion_solicitudes order by cedula_identidad,fecha_solicitud asc");
                    } else {
                        $datos = $this->v_casp01_relacion_solicitudes->execute("select * from v_casp01_relacion_solicitudes where " . $cond . " order by cedula_identidad,fecha_solicitud asc");
                    }
                } else if ($tipo_solicitud == 2) {
                    if (empty($this->data['casp01']['cedula'])) {
                        echo'<script>history.back(1);</script>';
                    } else {
                        $cedula = $this->data['casp01']['cedula'];
                    }
                    if ($tipo_peticion == 1) {
                        $datos = $this->v_casp01_relacion_solicitudes->execute("select * from v_casp01_relacion_solicitudes where cedula_identidad=" . $cedula . " order by cedula_identidad,fecha_solicitud asc");
                    } else {
                        $datos = $this->v_casp01_relacion_solicitudes->execute("select * from v_casp01_relacion_solicitudes where " . $cond1 . " cedula_identidad=" . $cedula . " order by cedula_identidad,fecha_solicitud asc");
                    }
                } else if ($tipo_solicitud == 3) {
                    /////aqui lo del rango por fecha
                    $fecha_inicial = $this->data['casp01']['fecha_inicial'];
                    $fecha_final = $this->data['casp01']['fecha_final'];
                    if ($tipo_peticion == 1) {
                        $datos = $this->v_casp01_relacion_solicitudes->execute("select * from v_casp01_relacion_solicitudes where fecha_solicitud BETWEEN '$fecha_inicial' and '$fecha_final' order by fecha_solicitud,cedula_identidad asc");
                    } else {
                        $datos = $this->v_casp01_relacion_solicitudes->execute("select * from v_casp01_relacion_solicitudes where " . $cond . "  and fecha_solicitud BETWEEN '$fecha_inicial' and '$fecha_final' order by fecha_solicitud,cedula_identidad asc");
                    }
                }
            } else {
                if (empty($this->data['casp01']['ver_promotor'])) {
                    echo'<script>history.back(1);</script>';
                }
                $promotor1 = $this->data['casp01']['ver_promotor'];
                if ($tipo_solicitud == 1) {
                    if ($tipo_peticion == 1) {
                        $datos = $this->v_casp01_relacion_solicitudes->execute("select * from v_casp01_relacion_solicitudes where nombre_promotor_solicitud='$promotor1' order by cedula_identidad,fecha_solicitud asc");
                    } else {
                        $datos = $this->v_casp01_relacion_solicitudes->execute("select * from v_casp01_relacion_solicitudes where  " . $cond . " and nombre_promotor_solicitud='$promotor1' order by cedula_identidad,fecha_solicitud asc");
                    }
                } else if ($tipo_solicitud == 2) {
                    if (empty($this->data['casp01']['cedula'])) {
                        echo'<script>history.back(1);</script>';
                    } else {
                        $cedula = $this->data['casp01']['cedula'];
                    }
                    if ($tipo_peticion == 1) {
                        $datos = $this->v_casp01_relacion_solicitudes->execute("select * from v_casp01_relacion_solicitudes where cedula_identidad=" . $cedula . " and nombre_promotor_solicitud='$promotor1' order by cedula_identidad,fecha_solicitud asc");
                    } else {
                        $datos = $this->v_casp01_relacion_solicitudes->execute("select * from v_casp01_relacion_solicitudes where " . $cond1 . " cedula_identidad=" . $cedula . " and nombre_promotor_solicitud='$promotor1' order by cedula_identidad,fecha_solicitud asc");
                    }
                } else if ($tipo_solicitud == 3) {
                    /////aqui lo del rango por fecha
                    $fecha_inicial = $this->data['casp01']['fecha_inicial'];
                    $fecha_final = $this->data['casp01']['fecha_final'];
                    if ($tipo_peticion == 1) {
                        $datos = $this->v_casp01_relacion_solicitudes->execute("select * from v_casp01_relacion_solicitudes where nombre_promotor_solicitud='$promotor1' and fecha_solicitud BETWEEN '$fecha_inicial' and '$fecha_final' order by fecha_solicitud,cedula_identidad asc");
                    } else {
                        $datos = $this->v_casp01_relacion_solicitudes->execute("select * from v_casp01_relacion_solicitudes where " . $cond . "  and nombre_promotor_solicitud='$promotor1' and fecha_solicitud BETWEEN '$fecha_inicial' and '$fecha_final' order by fecha_solicitud,cedula_identidad asc");
                    }
                }
            }

            $this->set('ir', 'si');
//	pr($datos);
            $this->set('datos', $datos);
        } else if ($ir == 'promotor') {
            $this->layout = "ajax";
            $this->set('promotor', $var1);
            $ver = $this->Session->read('filtrar');
            if ($ver == 1) {
                $sql = "select username_promotor_solicitud,nombre_promotor_solicitud from v_casp01_relacion_solicitudes group by username_promotor_solicitud,nombre_promotor_solicitud order by nombre_promotor_solicitud asc";
            } else if ($ver == 2) {
                $sql = "select cod_presi,cod_entidad,cod_tipo_inst,cod_inst,username_promotor_solicitud,nombre_promotor_solicitud from v_casp01_relacion_solicitudes where " . $this->condicionNDEP() . "group by cod_presi,cod_entidad,cod_tipo_inst,cod_inst,username_promotor_solicitud,nombre_promotor_solicitud order by nombre_promotor_solicitud asc";
            } else {
                $sql = "select cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,username_promotor_solicitud,nombre_promotor_solicitud from v_casp01_relacion_solicitudes where " . $this->SQLCA() . "group by cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,username_promotor_solicitud,nombre_promotor_solicitud order by nombre_promotor_solicitud asc";
            }
            $rs = $this->v_casp01_relacion_solicitudes->execute($sql);
            if (count($rs) != 0) {
                foreach ($rs as $l) {
                    $v[] = $l[0]["nombre_promotor_solicitud"];
                    if ($l[0]["nombre_promotor_solicitud"] != '')
                        $d[] = $l[0]["nombre_promotor_solicitud"];
                    else
                        $d[] = $l[0]["username_promotor_solicitud"];
                }
                $lista = array_combine($v, $d);
                $this->set('select', $lista);
            }else {
                $this->set('select', array('0' => 'No hay registros'));
            }
        } else if ($ir == 'nada') {
            $this->layout = "ajax";
            $this->Session->write('filtrar', $var1);
            echo"<script>
					 document.getElementById('promotores').innerHTML='';
			 		 document.getElementById('promotor_1').checked=true;
			 </script>";
        }
    }

// fin casd01_reporte_solicitudes

    function mascara_9_select($var) {
        $num = strlen($var);
        switch ($num) {
            case 1:
                return '00000000' . $var;
                break;
            case 2:
                return '0000000' . $var;
                break;
            case 6:
                return '000' . $var;
                break;
            case 7:
                return '00' . $var;
                break;
            case 8:
                return '0' . $var;
                break;
            case 1:
                return $var;
                break;
            default :
                return $var;
        }
    }

    function bt_nav($Tfilas, $pagina) {
        if ($Tfilas == 1) {
            $this->set('mostrarS', false);
            $this->set('mostrarA', false);
        } else if ($Tfilas == 2) {
            if ($pagina == 2) {
                $this->set('mostrarS', false);
                $this->set('mostrarA', true);
            } else {
                $this->set('mostrarS', true);
                $this->set('mostrarA', false);
            }
        } else if ($Tfilas >= 3) {
            if ($pagina == $Tfilas) {
                $this->set('mostrarS', false);
                $this->set('mostrarA', true);
            } else if ($pagina == 1) {
                $this->set('mostrarS', true);
                $this->set('mostrarA', false);
            } else {
                $this->set('mostrarS', true);
                $this->set('mostrarA', true);
            }
        }
    }

//fin navegacion

    function casd01_relacion_solicitantes($ir = null, $var = null) {
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        if ($ir == 'no') {
            $this->layout = "ajax";
            $this->set('ir', 'no');
        } else if ($ir == 'si') {
            $this->layout = "pdf";

            $tipo_peticion = $this->data['casp01']['tipo_peticion'];
            $solicitud = $this->data['casp01']['solicitud'];
            $periodo = $this->data['casp01']['periodo'];

            switch ($solicitud) {
                case 1:
                    $order = "order by cedula_identidad";
                    break;
                case 2:
                    $order = "order by apellidos_nombres";
                    break;
                case 3:
                    $order = "order by cod_ambito";
                    break;
                case 4:
                    $order = "order by cod_zona";
                    break;
                case 5:
                    $order = "order by cod_vivienda";
                    break;
                case 6:
                    $order = "order by cod_estado,cod_municipio,cod_parroquia,cod_centro_poblado";
                    break;
            }

            if ($periodo == 1) {
                $sql = '';
            } else if ($periodo == 2) {
                $ano = $this->data['casp01']['ano'];
                $sql = " and substr(fecha_inscripcion::text,0,5)::integer=" . $ano;
                $sql1 = " substr(fecha_inscripcion::text,0,5)::integer=" . $ano;
            } else if ($periodo == 3) {
                $fecha_inicial = $this->data['casp01']['fecha_inicial'];
                $fecha_final = $this->data['casp01']['fecha_final'];
                $sql = " and fecha_inscripcion BETWEEN '$fecha_inicial' and '$fecha_final'";
                $sql1 = " fecha_inscripcion BETWEEN '$fecha_inicial' and '$fecha_final'";
            }

            if ($tipo_peticion == 1) {
                if ($periodo != 1) {
                    $sql = "select * from v_casd01_relacion_solicitantes where " . $sql1 . " " . $order;
                } else {
                    $sql = "select * from v_casd01_relacion_solicitantes " . $order;
                }
            } else if ($tipo_peticion == 2) {
                $sql = "select * from v_casd01_relacion_solicitantes where " . $this->condicionNDEP() . " " . $sql . " " . $order;
            } else if ($tipo_peticion == 3) {
                $sql = "select * from v_casd01_relacion_solicitantes where " . $this->SQLCA() . " " . $sql . " " . $order;
            }
            $datos = $this->v_casd01_relacion_solicitantes->execute($sql);
            $this->set('datos', $datos);
            $this->set('ir', 'si');
        } else if ($ir == 'periodo') {
            $this->layout = "pdf";
            $this->set('muestra', $var);
        }
    }

// fin casd01_relacion_solicitantes

    function casp01_reporte_solicitud_tipo($ir = null, $var = null) {
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        if ($ir == 'no') {
            $this->layout = "ajax";
            $this->set('ir', 'no');
        } else if ($ir == 'si') {
            $this->layout = "pdf";
            $this->set('ir', 'si');

            $tipo_peticion = $this->data['casp01']['tipo_peticion'];
            $periodo = $this->data['casp01']['periodo'];
            if ($periodo == 1) {
                $select1 = '';
                $select2 = '';
                $select3 = '';
            } else if ($periodo == 2) {
                $ano = $this->data['casp01']['ano'];
                $select1 = " and substr(b.fecha_solicitud::text,0,5)::integer=" . $ano;
                $select2 = " and substr(c.fecha_solicitud::text,0,5)::integer=" . $ano;
                $select3 = " and substr(d.fecha_solicitud::text,0,5)::integer=" . $ano;
            } else if ($periodo == 3) {
                $fecha_inicial = $this->data['casp01']['fecha_inicial'];
                $fecha_final = $this->data['casp01']['fecha_final'];
                $select1 = " and b.fecha_solicitud BETWEEN '$fecha_inicial' and '$fecha_final'";
                $select2 = " and c.fecha_solicitud BETWEEN '$fecha_inicial' and '$fecha_final'";
                $select3 = " and d.fecha_solicitud BETWEEN '$fecha_inicial' and '$fecha_final'";
            }


            switch ($tipo_peticion) {
                case 1:
                    $sql = "select
					a.cod_tipo_ayuda,
					a.denominacion,
					(select count(b.cod_tipo_ayuda) from v_casp01_relacion_solicitudes b where b.cod_tipo_ayuda=a.cod_tipo_ayuda " . $select1 . " ) as numero_solicitudes,
					(select count(c.numero_documento_ayuda) from v_casp01_relacion_solicitudes c where c.cod_tipo_ayuda=a.cod_tipo_ayuda " . $select2 . " ) as numero_ayudas,
					(select sum(d.monto_total) from v_casp01_relacion_solicitudes d where d.cod_tipo_ayuda=a.cod_tipo_ayuda " . $select3 . " ) as monto_total
					from casd01_tipo_ayuda a ORDER BY numero_solicitudes DESC";
                    break;
                case 2:
                    $sql = "select
					a.cod_tipo_ayuda,
					a.denominacion,
					(select count(b.cod_tipo_ayuda) from v_casp01_relacion_solicitudes b where cod_presi='$cod_presi' and cod_entidad='$cod_entidad' and cod_tipo_inst='$cod_tipo_inst' and cod_inst='$cod_inst' and b.cod_tipo_ayuda=a.cod_tipo_ayuda " . $select1 . " ) as numero_solicitudes,
					(select count(c.numero_documento_ayuda) from v_casp01_relacion_solicitudes c where cod_presi='$cod_presi' and cod_entidad='$cod_entidad' and cod_tipo_inst='$cod_tipo_inst' and cod_inst='$cod_inst' and c.cod_tipo_ayuda=a.cod_tipo_ayuda " . $select2 . " ) as numero_ayudas,
					(select sum(d.monto_total) from v_casp01_relacion_solicitudes d where cod_presi='$cod_presi' and cod_entidad='$cod_entidad' and cod_tipo_inst='$cod_tipo_inst' and cod_inst='$cod_inst' and d.cod_tipo_ayuda=a.cod_tipo_ayuda " . $select3 . " ) as monto_total
					from casd01_tipo_ayuda a ORDER BY numero_solicitudes DESC";
                    break;
                case 3:
                    $sql = "select
					a.cod_tipo_ayuda,
					a.denominacion,
					(select count(b.cod_tipo_ayuda) from v_casp01_relacion_solicitudes b where cod_presi='$cod_presi' and cod_entidad='$cod_entidad' and cod_tipo_inst='$cod_tipo_inst' and cod_inst='$cod_inst' and cod_dep='$cod_dep' and b.cod_tipo_ayuda=a.cod_tipo_ayuda " . $select1 . " ) as numero_solicitudes,
					(select count(c.numero_documento_ayuda) from v_casp01_relacion_solicitudes c where cod_presi='$cod_presi' and cod_entidad='$cod_entidad' and cod_tipo_inst='$cod_tipo_inst' and cod_inst='$cod_inst' and cod_dep='$cod_dep' and c.cod_tipo_ayuda=a.cod_tipo_ayuda " . $select2 . " ) as numero_ayudas,
					(select sum(d.monto_total) from v_casp01_relacion_solicitudes d where cod_presi='$cod_presi' and cod_entidad='$cod_entidad' and cod_tipo_inst='$cod_tipo_inst' and cod_inst='$cod_inst' and cod_dep='$cod_dep' and d.cod_tipo_ayuda=a.cod_tipo_ayuda " . $select3 . " ) as monto_total
					from casd01_tipo_ayuda a ORDER BY numero_solicitudes DESC";
                    break;
            }

            $datos = $this->casd01_tipo_ayuda->execute($sql);
            $this->set('datos', $datos);
        } else if ($ir == 'periodo') {
            $this->layout = "pdf";
            $this->set('muestra', $var);
        }
    }

// fin casp01_reporte_solicitud_tipo

    function casd01_ubicacion_geografica($ir = null, $var = null) {
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');

        if ($ir == 'no') {
            $this->layout = "ajax";
            $this->set('ir', 'no');
        } else if ($ir == 'si') {
            $this->layout = "pdf";
            $this->set('ir', 'si');

            $tipo_peticion = $this->data['casp01']['tipo_peticion'];
            $periodo = $this->data['casp01']['periodo'];
            if ($periodo == 1) {
                $select = '';
            } else if ($periodo == 2) {
                $ano = $this->data['casp01']['ano'];
                $select = " and substr(fecha_solicitud::text,0,5)::integer=" . $ano;
                $select1 = " substr(fecha_solicitud::text,0,5)::integer=" . $ano;
            } else if ($periodo == 3) {
                $fecha_inicial = $this->data['casp01']['fecha_inicial'];
                $fecha_final = $this->data['casp01']['fecha_final'];
                $select = " and fecha_solicitud BETWEEN '$fecha_inicial' and '$fecha_final'";
                $select1 = " fecha_solicitud BETWEEN '$fecha_inicial' and '$fecha_final'";
            }

            switch ($tipo_peticion) {
                case 1:
                    if ($periodo != 1) {
                        $sql = "select cod_estado,cod_municipio,cod_parroquia,cod_centro_poblado,denominacion,sum(numero_solicitudes) as numero_solicitudes,sum(numero_ayudas) as numero_ayudas,sum(monto_ayudas) as monto_ayudas
						from v_casd01_ubicacion_geografica_rango
						where " . $select1 . "
						group by cod_estado,cod_municipio,cod_parroquia,cod_centro_poblado,denominacion
						order by cod_estado,cod_municipio,cod_parroquia,cod_centro_poblado,denominacion asc";
                    } else {
                        $sql = "select cod_estado,cod_municipio,cod_parroquia,cod_centro_poblado,denominacion,sum(numero_solicitudes) as numero_solicitudes,sum(numero_ayudas) as numero_ayudas,sum(monto_ayudas) as monto_ayudas
						from v_casd01_ubicacion_geografica_rango
						group by cod_estado,cod_municipio,cod_parroquia,cod_centro_poblado,denominacion
						order by cod_estado,cod_municipio,cod_parroquia,cod_centro_poblado,denominacion asc";
                    }
                    break;
                case 2:
                    $sql = "select cod_estado,cod_municipio,cod_parroquia,cod_centro_poblado,denominacion,sum(numero_solicitudes) as numero_solicitudes,sum(numero_ayudas) as numero_ayudas,sum(monto_ayudas) as monto_ayudas
						from v_casd01_ubicacion_geografica_rango
						where cod_presi='$cod_presi' and cod_entidad='$cod_entidad' and cod_tipo_inst='$cod_tipo_inst' and cod_inst='$cod_inst' " . $select . "
						group by cod_estado,cod_municipio,cod_parroquia,cod_centro_poblado,denominacion
						order by cod_estado,cod_municipio,cod_parroquia,cod_centro_poblado,denominacion asc";
                    break;
                case 3:
                    $sql = "select cod_estado,cod_municipio,cod_parroquia,cod_centro_poblado,denominacion,sum(numero_solicitudes) as numero_solicitudes,sum(numero_ayudas) as numero_ayudas,sum(monto_ayudas) as monto_ayudas
						from v_casd01_ubicacion_geografica_rango
						where cod_presi='$cod_presi' and cod_entidad='$cod_entidad' and cod_tipo_inst='$cod_tipo_inst' and cod_inst='$cod_inst' and cod_dep='$cod_dep' " . $select . "
						group by cod_estado,cod_municipio,cod_parroquia,cod_centro_poblado,denominacion
						order by cod_estado,cod_municipio,cod_parroquia,cod_centro_poblado,denominacion asc";

                    break;
            }
            $datos = $this->v_casd01_ubicacion_geografica->execute($sql);
            $this->set('datos', $datos);
        } else if ($ir == 'periodo') {
            $this->layout = "pdf";
            $this->set('muestra', $var);
        }
    }

// fin casd01_ubicacion_geografica

    function casd01_ubicacion_geografica_tipo($ir = null, $var = null) {
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');

        if ($ir == 'no') {
            $this->layout = "ajax";
            $this->set('ir', 'no');
        } else if ($ir == 'si') {
            $this->layout = "pdf";
            $this->set('ir', 'si');

            $tipo_peticion = $this->data['casp01']['tipo_peticion'];
            $periodo = $this->data['casp01']['periodo'];
            if ($periodo == 1) {
                $select = '';
            } else if ($periodo == 2) {
                $ano = $this->data['casp01']['ano'];
                $select = " and substr(a.fecha_solicitud::text,0,5)::integer=" . $ano;
                $select1 = " substr(a.fecha_solicitud::text,0,5)::integer=" . $ano;
            } else if ($periodo == 3) {
                $fecha_inicial = $this->data['casp01']['fecha_inicial'];
                $fecha_final = $this->data['casp01']['fecha_final'];
                $select = " and a.fecha_solicitud BETWEEN '$fecha_inicial' and '$fecha_final'";
                $select1 = " a.fecha_solicitud BETWEEN '$fecha_inicial' and '$fecha_final'";
            }

            switch ($tipo_peticion) {
                case 1:
                    if ($periodo != 1) {
                        $sql = "SELECT a.cod_estado,a.cod_municipio,a.cod_parroquia,a.cod_centro_poblado,a.denominacion_estado,a.denominacion_municipio,a.denominacion_parroquia,a.denominacion_centro,a.tipo_ayuda as denominacion_ayuda,count(a.numero_ocacion) AS numero_solicitudes,count(a.numero_documento_ayuda) AS numero_ayudas,sum(a.monto_total) AS monto_ayudas
					FROM v_casp01_relacion_solicitudes a
					where " . $select1 . "
					GROUP BY a.cod_estado,a.cod_municipio,a.cod_parroquia,a.cod_centro_poblado,a.denominacion_estado,a.denominacion_municipio,a.denominacion_parroquia,a.denominacion_centro,a.tipo_ayuda
					ORDER BY a.cod_estado,a.cod_municipio,a.cod_parroquia,a.cod_centro_poblado,a.denominacion_estado,a.denominacion_municipio,a.denominacion_parroquia,a.denominacion_centro,a.tipo_ayuda";
                    } else {
                        $sql = "SELECT a.cod_estado,a.cod_municipio,a.cod_parroquia,a.cod_centro_poblado,a.denominacion_estado,a.denominacion_municipio,a.denominacion_parroquia,a.denominacion_centro,a.tipo_ayuda as denominacion_ayuda,count(a.numero_ocacion) AS numero_solicitudes,count(a.numero_documento_ayuda) AS numero_ayudas,sum(a.monto_total) AS monto_ayudas
					FROM v_casp01_relacion_solicitudes a
					GROUP BY a.cod_estado,a.cod_municipio,a.cod_parroquia,a.cod_centro_poblado,a.denominacion_estado,a.denominacion_municipio,a.denominacion_parroquia,a.denominacion_centro,a.tipo_ayuda
					ORDER BY a.cod_estado,a.cod_municipio,a.cod_parroquia,a.cod_centro_poblado,a.denominacion_estado,a.denominacion_municipio,a.denominacion_parroquia,a.denominacion_centro,a.tipo_ayuda";
                    }
                    break;
                case 2:
                    $sql = "SELECT a.cod_estado,a.cod_municipio,a.cod_parroquia,a.cod_centro_poblado,a.denominacion_estado,a.denominacion_municipio,a.denominacion_parroquia,a.denominacion_centro,a.tipo_ayuda as denominacion_ayuda,count(a.numero_ocacion) AS numero_solicitudes,count(a.numero_documento_ayuda) AS numero_ayudas,sum(a.monto_total) AS monto_ayudas
					FROM v_casp01_relacion_solicitudes a
					where cod_presi='$cod_presi' and cod_entidad='$cod_entidad' and cod_tipo_inst='$cod_tipo_inst' and cod_inst='$cod_inst' " . $select . "
					GROUP BY a.cod_estado,a.cod_municipio,a.cod_parroquia,a.cod_centro_poblado,a.denominacion_estado,a.denominacion_municipio,a.denominacion_parroquia,a.denominacion_centro,a.tipo_ayuda
					ORDER BY a.cod_estado,a.cod_municipio,a.cod_parroquia,a.cod_centro_poblado,a.denominacion_estado,a.denominacion_municipio,a.denominacion_parroquia,a.denominacion_centro,a.tipo_ayuda";
                    break;
                case 3:
                    $sql = "SELECT a.cod_estado,a.cod_municipio,a.cod_parroquia,a.cod_centro_poblado,a.denominacion_estado,a.denominacion_municipio,a.denominacion_parroquia,a.denominacion_centro,a.tipo_ayuda as denominacion_ayuda,count(a.numero_ocacion) AS numero_solicitudes,count(a.numero_documento_ayuda) AS numero_ayudas,sum(a.monto_total) AS monto_ayudas
					FROM v_casp01_relacion_solicitudes a
					where cod_presi='$cod_presi' and cod_entidad='$cod_entidad' and cod_tipo_inst='$cod_tipo_inst' and cod_inst='$cod_inst' and cod_dep='$cod_dep' " . $select . "
					GROUP BY a.cod_estado,a.cod_municipio,a.cod_parroquia,a.cod_centro_poblado,a.denominacion_estado,a.denominacion_municipio,a.denominacion_parroquia,a.denominacion_centro,a.tipo_ayuda
					ORDER BY a.cod_estado,a.cod_municipio,a.cod_parroquia,a.cod_centro_poblado,a.denominacion_estado,a.denominacion_municipio,a.denominacion_parroquia,a.denominacion_centro,a.tipo_ayuda";

                    break;
            }
            $datos = $this->v_casd01_ubicacion_geografica_tipo_2->execute($sql);
//		pr($datos);
            $this->set('datos', $datos);
        } else if ($ir == 'periodo') {
            $this->layout = "pdf";
            $this->set('muestra', $var);
        }
    }

// fin casd01_ubicacion_geografica_tipo

    function firmas_balance_ejecucion_mes() {
        $this->layout = "ajax";

        $cp = $this->Session->read('SScodpresi');
        $ce = $this->Session->read('SScodentidad');
        $cti = $this->Session->read('SScodtipoinst');
        $ci = $this->Session->read('SScodinst');
        $cd = $this->Session->read('SScoddep');

        $tipo_doc_anul = $this->data['cugp03_acta_anulacion']['tipo_doc_anul'];
        $nombre_primera_firma = $this->data['cugp03_acta_anulacion']['nombre_primera_firma'];
        $cargo_primera_firma = $this->data['cugp03_acta_anulacion']['cargo_primera_firma'];
        $nombre_segunda_firma = $this->data['cugp03_acta_anulacion']['nombre_segunda_firma'];
        $cargo_segunda_firma = $this->data['cugp03_acta_anulacion']['cargo_segunda_firma'];
        $nombre_tercera_firma = $this->data['cugp03_acta_anulacion']['nombre_tercera_firma'];
        $cargo_tercera_firma = $this->data['cugp03_acta_anulacion']['cargo_tercera_firma'];
        $nombre_cuarta_firma = $this->data['cugp03_acta_anulacion']['nombre_cuarta_firma'];
        $cargo_cuarta_firma = $this->data['cugp03_acta_anulacion']['cargo_cuarta_firma'];

        $primera_cc = isset($this->data['cugp03_acta_anulacion']['primera_copia']) ? $this->data['cugp03_acta_anulacion']['primera_copia'] : 'N/A';
        $segunda_cc = isset($this->data['cugp03_acta_anulacion']['segunda_copia']) ? $this->data['cugp03_acta_anulacion']['segunda_copia'] : 'N/A';
        $tercera_cc = isset($this->data['cugp03_acta_anulacion']['tercera_copia']) ? $this->data['cugp03_acta_anulacion']['tercera_copia'] : 'N/A';
        $cuarta_cc = isset($this->data['cugp03_acta_anulacion']['cuarta_copia']) ? $this->data['cugp03_acta_anulacion']['cuarta_copia'] : 'N/A';
        $quinta_cc = isset($this->data['cugp03_acta_anulacion']['quinta_copia']) ? $this->data['cugp03_acta_anulacion']['quinta_copia'] : 'N/A';
        $sexta_cc = isset($this->data['cugp03_acta_anulacion']['sexta_copia']) ? $this->data['cugp03_acta_anulacion']['sexta_copia'] : 'N/A';
        $septima_cc = isset($this->data['cugp03_acta_anulacion']['septima_copia']) ? $this->data['cugp03_acta_anulacion']['septima_copia'] : 'N/A';
        $octava_cc = isset($this->data['cugp03_acta_anulacion']['octava_copia']) ? $this->data['cugp03_acta_anulacion']['octava_copia'] : 'N/A';

        $insert = "INSERT INTO cugd07_firmas_oficio_anulacion VALUES ($cp, $ce, $cti, $ci, $cd, $tipo_doc_anul,'$nombre_primera_firma', '$cargo_primera_firma', '$nombre_segunda_firma', '$cargo_segunda_firma', '$nombre_tercera_firma', '$cargo_tercera_firma', '$primera_cc', '$segunda_cc', '$tercera_cc', '$cuarta_cc', '$quinta_cc', '$sexta_cc', '$septima_cc', '$octava_cc', '$nombre_cuarta_firma', '$cargo_cuarta_firma')";
        $this->cugd07_firmas_oficio_anulacion->execute($insert);
        echo'<script>';
        echo" document.getElementById('b_generar').disabled = ''; ";
        echo'</script>';

        $this->set('mensaje', 'Las firmas fuer&oacute;n registradas correctamente');
        $this->casp01_cumpleano('no');
        $this->render('casp01_cumpleano');
    }

    function modificar_firmas_balance_mes() {
        $this->layout = "ajax";

        $tipo_doc_anul = $this->data['cugp03_acta_anulacion']['tipo_doc_anul'];
        $nombre_primera_firma = $this->data['cugp03_acta_anulacion']['nombre_primera_firma'];
        $cargo_primera_firma = $this->data['cugp03_acta_anulacion']['cargo_primera_firma'];
        $nombre_segunda_firma = $this->data['cugp03_acta_anulacion']['nombre_segunda_firma'];
        $cargo_segunda_firma = $this->data['cugp03_acta_anulacion']['cargo_segunda_firma'];
        $nombre_tercera_firma = $this->data['cugp03_acta_anulacion']['nombre_tercera_firma'];
        $cargo_tercera_firma = $this->data['cugp03_acta_anulacion']['cargo_tercera_firma'];
        $nombre_cuarta_firma = $this->data['cugp03_acta_anulacion']['nombre_cuarta_firma'];
        $cargo_cuarta_firma = $this->data['cugp03_acta_anulacion']['cargo_cuarta_firma'];

        $primera_cc = isset($this->data['cugp03_acta_anulacion']['primera_copia']) ? $this->data['cugp03_acta_anulacion']['primera_copia'] : 'A';
        $segunda_cc = isset($this->data['cugp03_acta_anulacion']['segunda_copia']) ? $this->data['cugp03_acta_anulacion']['segunda_copia'] : 'A';
        $tercera_cc = isset($this->data['cugp03_acta_anulacion']['tercera_copia']) ? $this->data['cugp03_acta_anulacion']['tercera_copia'] : 'A';
        $cuarta_cc = isset($this->data['cugp03_acta_anulacion']['cuarta_copia']) ? $this->data['cugp03_acta_anulacion']['cuarta_copia'] : 'A';
        $quinta_cc = isset($this->data['cugp03_acta_anulacion']['quinta_copia']) ? $this->data['cugp03_acta_anulacion']['quinta_copia'] : 'A';
        $sexta_cc = isset($this->data['cugp03_acta_anulacion']['sexta_copia']) ? $this->data['cugp03_acta_anulacion']['sexta_copia'] : 'A';
        $septima_cc = isset($this->data['cugp03_acta_anulacion']['septima_copia']) ? $this->data['cugp03_acta_anulacion']['septima_copia'] : 'A';
        $octava_cc = isset($this->data['cugp03_acta_anulacion']['octava_copia']) ? $this->data['cugp03_acta_anulacion']['octava_copia'] : 'A';

        $update = "UPDATE cugd07_firmas_oficio_anulacion SET nombre_primera_firma='$nombre_primera_firma', cargo_primera_firma='$cargo_primera_firma', nombre_segunda_firma='$nombre_segunda_firma', cargo_segunda_firma='$cargo_segunda_firma', nombre_tercera_firma='$nombre_tercera_firma', cargo_tercera_firma='$cargo_tercera_firma', primera_copia='$primera_cc', segunda_copia='$segunda_cc', tercera_copia='$tercera_cc', cuarta_copia='$cuarta_cc', quinta_copia='$quinta_cc', sexta_copia='$sexta_cc', septima_copia='$septima_cc', octava_copia='$octava_cc', nombre_cuarta_firma='$nombre_cuarta_firma', cargo_cuarta_firma='$cargo_cuarta_firma' WHERE " . $this->SQLCA() . " and tipo_documento=" . $tipo_doc_anul;
        $this->cugd07_firmas_oficio_anulacion->execute($update);

        $this->set('mensaje', 'Las firmas fuer&oacute;n modificadas correctamente');
        $this->casp01_cumpleano('no');
        $this->render('casp01_cumpleano');
    }

    function casp01_cumpleano($ir = null) {
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');

        if ($ir == 'no') {
            $this->layout = "ajax";
            $this->set('ir', 'no');
            $cont = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA() . " and tipo_documento='9997'");
            if ($cont == 0) {
                echo '<script>';
                echo " fun_msj2('Por favor, ingrese la denominaci&oacute;n de la oficina, el nombre y cargo del firmante');";
                echo '</script>';
                $this->set('nombre_primera_firma', '');
                $this->set('cargo_primera_firma', '');
                $this->set('nombre_segunda_firma', '');
                $this->set('cargo_segunda_firma', '');
                $this->set('nombre_tercera_firma', 'N/A');
                $this->set('cargo_tercera_firma', 'N/A');
                $this->set('nombre_cuarta_firma', 'N/A');
                $this->set('cargo_cuarta_firma', 'N/A');
                $this->set('tipo_doc_anul', 9997);
                $firma_existe = 'no';
            } else {
                $firmantes = $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA() . " and tipo_documento=9997");
                $this->set('nombre_primera_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
                $this->set('cargo_primera_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
                $this->set('nombre_segunda_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
                $this->set('cargo_segunda_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
                $this->set('nombre_tercera_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
                $this->set('cargo_tercera_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
                $this->set('nombre_cuarta_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
                $this->set('cargo_cuarta_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
                $this->set('tipo_doc_anul', 9997);
                $firma_existe = 'si';
            }
            $this->set('firma_existe', $firma_existe);
        } else if ($ir == 'si') {
            $this->layout = "pdf";
            $this->set('ir', 'si');
            if (!empty($this->data['casp01']['fecha_nacimiento'])) {

                $firmantes = $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA() . " and tipo_documento=9997");
                if (!empty($firmantes)) {
                    $this->set('nombre_primera_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
                    $this->set('cargo_primera_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
                    $this->set('nombre_segunda_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
                } else {
                    $this->set('nombre_primera_firma', '');
                    $this->set('cargo_primera_firma', '');
                    $this->set('nombre_segunda_firma', '');
                }

                $fecha = $this->data['casp01']['fecha_nacimiento'];
                $paso = explode('/', $fecha);
                //	    echo $dia=$paso[0];
                //	    echo "<br>".$mes=$paso[1];
                $dia = $paso[0];
                $mes = $paso[1];
                $ano = $paso[2];
                $this->set('dia', $dia);
                $this->set('ano', $ano);


                $sql = "(select cedula_identidad,0::character varying as cedula, apellidos_nombres,fecha_nacimiento,sexo,denominacion_estado,denominacion_municipio,denominacion_parroquia,denominacion_centro,conocido,direccion_habitacion from v_casd01_relacion_solicitantes where substr(fecha_nacimiento::text,9,2)::integer=" . $dia . " and substr(fecha_nacimiento::text,6,2)::integer=" . $mes . ")
					UNION
					(select a.cedula_identidad,a.cedula,a.apellidos_nombres,a.fecha_nacimiento,a.sexo,b.denominacion_estado,b.denominacion_municipio,b.denominacion_parroquia,b.denominacion_centro,b.conocido,b.direccion_habitacion from casd01_datos_familiares a,v_casd01_relacion_solicitantes b where substr(a.fecha_nacimiento::text,9,2)::integer=" . $dia . " and substr(a.fecha_nacimiento::text,6,2)::integer=" . $mes . " and a.cedula_identidad=b.cedula_identidad and a.cedula not in (select f.cedula_identidad::character varying from v_casd01_relacion_solicitantes f where f.cedula_identidad::character varying=a.cedula))
					";
                $datos = $this->v_casd01_relacion_solicitantes->execute($sql);
                $this->set('datos', $datos);
                //pr($datos);
                switch ($mes) {
                    case 1:
                        $mes = 'enero';
                        break;
                    case 2:
                        $mes = 'febrero';
                        break;
                    case 3:
                        $mes = 'marzo';
                        break;
                    case 4:
                        $mes = 'abril';
                        break;
                    case 5:
                        $mes = 'mayo';
                        break;
                    case 6:
                        $mes = 'junio';
                        break;
                    case 7:
                        $mes = 'julio';
                        break;
                    case 8:
                        $mes = 'agosto';
                        break;
                    case 9:
                        $mes = 'septiembre';
                        break;
                    case 10:
                        $mes = 'octubre';
                        break;
                    case 11:
                        $mes = 'noviembre';
                        break;
                    case 12:
                        $mes = 'diciembre';
                        break;
                }
                $this->set('mes', $mes);
            } else {
                echo'<script>history.back(1);</script>';
            }
        }
    }

// fin cumpleaños

    function casp01_comunicacion($ir = null, $var1 = null, $var2 = null, $var3 = null) {
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');

        if ($ir == 'no') {
            $this->layout = "ajax";
            $this->set('ir', 'no');
        } else if ($ir == 'buscar_datos') {
            $this->layout = "ajax";
            $this->set("opcion", $var1);
            $this->set('buscar_datos', '');
            $this->Session->delete('pista');
        } else if ($ir == 'buscar_por_pista') {
            $this->layout = "ajax";
            $group = "cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cedula_identidad";
            if ($var3 == null) {
                $var2 = strtoupper($var2);
                $this->Session->write('pista', $var2);
//					if(is_int($var2)){$sql   = " (cod_tipo_nomina='$nomina' and denominacion_busqueda LIKE '%$var2%')  or   ";}else{ $sql = "";}
                $Tfilas = $this->v_casd01_comunicacion->findCount($this->SQLCA() . " and (cedula_identidad::text LIKE '%$var2%' or upper(apellidos_nombres::text) LIKE upper('%" . $var2 . "%')) ");
//					        echo "cod_tipo_nomina='$nomina' and denominacion_busqueda LIKE '%$var2%'";
                if ($Tfilas != 0) {
                    $pagina = 1;
                    $Tfilas = (int) ceil($Tfilas / 100);
                    $this->set('pag_cant', $pagina . '/' . $Tfilas);
                    $this->set('total_paginas', $Tfilas);
                    $this->set('pagina_actual', $pagina);
                    $this->set('ultimo', $Tfilas);
                    echo $this->SQLCA();
                    $datos_filas = $this->v_casd01_comunicacion->findAll($this->SQLCA() . " and (cedula_identidad::text LIKE '%$var2%' or upper(apellidos_nombres::text) LIKE upper('%" . $var2 . "%'))", null, "cedula_identidad ASC", 100, 1, null);
                    $this->set("datosFILAS", $datos_filas);
                    $this->set('siguiente', $pagina + 1);
                    $this->set('anterior', $pagina - 1);
                    $this->bt_nav($Tfilas, $pagina);
                } else {
                    $this->set("datosFILAS", '');
                }
            } else {
                $var22 = $this->Session->read('pista');
                $var22 = strtoupper($var22);
                $Tfilas = $this->v_casd01_comunicacion->findCount($this->SQLCA() . " and (cedula_identidad::text LIKE '%$var22%' or upper(apellidos_nombres::text) LIKE upper('%" . $var22 . "%')) ");
                if ($Tfilas != 0) {
                    $pagina = $var3;
                    $Tfilas = (int) ceil($Tfilas / 100);
                    $this->set('pag_cant', $pagina . '/' . $Tfilas);
                    $this->set('total_paginas', $Tfilas);
                    $this->set('pagina_actual', $pagina);
                    $this->set('ultimo', $Tfilas);
//						     	    $datos_filas=$this->cscd01_catalogo->findAll($sql." (denominacion LIKE '%$var22%')  OR  (cod_snc LIKE '%$var22%')   ",null,"codigo_prod_serv ASC",100,$pagina,null);
                    $datos_filas = $this->v_casd01_comunicacion->findAll($this->SQLCA() . " and (cedula_identidad::text LIKE '%$var2%' or upper(apellidos_nombres::text) LIKE upper('%" . $var22 . "%'))", null, "cedula_identidad ASC", 100, 1, null);
                    $this->set("datosFILAS", $datos_filas);
                    $this->set('siguiente', $pagina + 1);
                    $this->set('anterior', $pagina - 1);
                    $this->bt_nav($Tfilas, $pagina);
                } else {
                    $this->set("datosFILAS", '');
                }
            }//fin else
            $this->set("opcion", $var1);
            $this->set("buscar_pista", '');
        } else if ($ir == 'si') {
            $this->layout = "pdf";
            $this->set('ir', 'si');

            $fecha = date("d/m/Y");
            $paso = explode('/', $fecha);
            $dia = $paso[0];
            $mes = $paso[1];
            $ano = $paso[2];
            $this->set('dia', $dia);
            $this->set('ano', $ano);
//echo $var2;
            $sql = "select a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,
				a.cedula_identidad,a.cod_tipo_ayuda,a.numero_ocacion,
				a.numero_documento_evaluacion,a.aprobado,a.evaluacion,
				(select b.ayuda_solicitada from casd01_solicitud_ayuda b where a.cod_presi=b.cod_presi and a.cod_entidad=b.cod_entidad and a.cod_tipo_inst=b.cod_tipo_inst and a.cod_inst=b.cod_inst and a.cod_dep=b.cod_dep and a.cedula_identidad=b.cedula_identidad and a.cod_tipo_ayuda=b.cod_tipo_ayuda and a.numero_ocacion=b.numero_ocacion
				and a.numero_documento_evaluacion=b.numero_documento_evaluacion) as ayuda_solicitada,
				 c.apellidos_nombres,c.sexo,c.denominacion_estado,c.denominacion_municipio,c.denominacion_parroquia,c.denominacion_centro,c.conocido,c.direccion_habitacion
				from casd01_evaluacion_ayuda a,v_casd01_relacion_solicitantes c where a.cod_presi='$cod_presi' and a.cod_entidad='$cod_entidad' and a.cod_tipo_inst='$cod_tipo_inst' and a.cod_inst='$cod_inst' and a.cod_dep='$cod_dep' and a.cedula_identidad=c.cedula_identidad and a.cedula_identidad='$var2' and a.aprobado!=1 ";



//		$datos=$this->v_casd01_relacion_solicitantes->execute("select * from v_casd01_comunicacion where ".$this->SQLCA()." and cedula_identidad=".$var2);
            $datos = $this->v_casd01_relacion_solicitantes->execute($sql);
            $this->set('datos', $datos);
//pr($datos);
            switch ($mes) {
                case 1:
                    $mes = 'enero';
                    break;
                case 2:
                    $mes = 'febrero';
                    break;
                case 3:
                    $mes = 'marzo';
                    break;
                case 4:
                    $mes = 'abril';
                    break;
                case 5:
                    $mes = 'mayo';
                    break;
                case 6:
                    $mes = 'junio';
                    break;
                case 7:
                    $mes = 'julio';
                    break;
                case 8:
                    $mes = 'agosto';
                    break;
                case 9:
                    $mes = 'septiembre';
                    break;
                case 10:
                    $mes = 'octubre';
                    break;
                case 11:
                    $mes = 'noviembre';
                    break;
                case 12:
                    $mes = 'diciembre';
                    break;
            }
            $this->set('mes', $mes);
        }
    }

    function casp01_planilla($ir = null, $var1 = null, $var2 = null, $var3 = null) {
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');

        if ($ir == 'no') {
            $this->layout = "ajax";
            $this->set('ir', 'no');
        } else if ($ir == 'buscar_datos') {
            $this->layout = "ajax";
            $this->set("opcion", $var1);
            $this->set('buscar_datos', '');
            $this->Session->delete('pista');
        } else if ($ir == 'buscar_por_pista') {
            $this->layout = "ajax";
            $group = "cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cedula_identidad";
            if ($var3 == null) {
                $var2 = strtoupper($var2);
                $this->Session->write('pista', $var2);
//					if(is_int($var2)){$sql   = " (cod_tipo_nomina='$nomina' and denominacion_busqueda LIKE '%$var2%')  or   ";}else{ $sql = "";}
                $Tfilas = $this->casd01_datos_personales->findCount("cedula_identidad::text LIKE '%$var2%' or upper(apellidos_nombres::text) LIKE upper('%" . $var2 . "%') ");
//					        echo "cod_tipo_nomina='$nomina' and denominacion_busqueda LIKE '%$var2%'";
                if ($Tfilas != 0) {
                    $pagina = 1;
                    $Tfilas = (int) ceil($Tfilas / 100);
                    $this->set('pag_cant', $pagina . '/' . $Tfilas);
                    $this->set('total_paginas', $Tfilas);
                    $this->set('pagina_actual', $pagina);
                    $this->set('ultimo', $Tfilas);
                    $datos_filas = $this->casd01_datos_personales->findAll("cedula_identidad::text LIKE '%$var2%' or upper(apellidos_nombres::text) LIKE upper('%" . $var2 . "%')", null, "cedula_identidad ASC", 100, 1, null);
                    $this->set("datosFILAS", $datos_filas);
                    $this->set('siguiente', $pagina + 1);
                    $this->set('anterior', $pagina - 1);
                    $this->bt_nav($Tfilas, $pagina);
                } else {
                    $this->set("datosFILAS", '');
                }
            } else {
                $var22 = $this->Session->read('pista');
                $var22 = strtoupper($var22);
                $Tfilas = $this->casd01_datos_personales->findCount("cedula_identidad::text LIKE '%$var22%' or upper(apellidos_nombres::text) LIKE upper('%" . $var22 . "%') ");
                if ($Tfilas != 0) {
                    $pagina = $var3;
                    $Tfilas = (int) ceil($Tfilas / 100);
                    $this->set('pag_cant', $pagina . '/' . $Tfilas);
                    $this->set('total_paginas', $Tfilas);
                    $this->set('pagina_actual', $pagina);
                    $this->set('ultimo', $Tfilas);
//						     	    $datos_filas=$this->cscd01_catalogo->findAll($sql." (denominacion LIKE '%$var22%')  OR  (cod_snc LIKE '%$var22%')   ",null,"codigo_prod_serv ASC",100,$pagina,null);
                    $datos_filas = $this->casd01_datos_personales->findAll("cedula_identidad::text LIKE '%$var2%' or upper(apellidos_nombres::text) LIKE upper('%" . $var22 . "%')", null, "cedula_identidad ASC", 100, 1, null);
                    $this->set("datosFILAS", $datos_filas);
                    $this->set('siguiente', $pagina + 1);
                    $this->set('anterior', $pagina - 1);
                    $this->bt_nav($Tfilas, $pagina);
                } else {
                    $this->set("datosFILAS", '');
                }
            }//fin else
            $this->set("opcion", $var1);
            $this->set("buscar_pista", '');
        } else {
            $this->layout = "pdf";
            $this->set('ir', 'si');
            if (!isset($var2)) {
                $desde = $this->data['casp01']['fecha_inicial'];
                $hasta = $this->data['casp01']['fecha_final'];
                $datos = $this->v_casd01_sintesis_social->execute("select * from v_casd01_sintesis_social where fecha_inscripcion between '$desde' and '$hasta'");

                $datos = $this->v_casd01_sintesis_social->execute("select cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cedula_identidad,apellidos_nombres,fecha_nacimiento,sexo,peso,estado_civil,estatura,grupo_sanguineo,denominacion_profesion,denominacion_oficio,cod_ambito,cod_zona,cod_vivienda,direccion_habitacion,telefonos_fijos,telefonos_movil,fecha_inscripcion,denominacion_estado,denominacion_municipio,denominacion_parroquia,denominacion_centro,cod_tenencia_vivienda,anos_residencia,monto_alquiler_hipoteca,cod_mision from v_casd01_sintesis_social where fecha_inscripcion between '$desde' and '$hasta' group by cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cedula_identidad,apellidos_nombres,fecha_nacimiento,sexo,peso,estado_civil,estatura,grupo_sanguineo,denominacion_profesion,denominacion_oficio,cod_ambito,cod_zona,cod_vivienda,direccion_habitacion,telefonos_fijos,telefonos_movil,fecha_inscripcion,denominacion_estado,denominacion_municipio,denominacion_parroquia,denominacion_centro,cod_tenencia_vivienda,anos_residencia,monto_alquiler_hipoteca,cod_mision");

                $datos1 = $this->v_casd01_sintesis_social->execute("select cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cedula_identidad,apellidos_nombres,fecha_nacimiento,sexo,peso,estado_civil,estatura,grupo_sanguineo,denominacion_profesion,denominacion_oficio,cod_ambito,cod_zona,cod_vivienda,direccion_habitacion,telefonos_fijos,telefonos_movil,fecha_inscripcion,denominacion_estado,denominacion_municipio,denominacion_parroquia,denominacion_centro,cod_tenencia_vivienda,anos_residencia,monto_alquiler_hipoteca,cod_mision,cedula_familiar,denominacion_parentesco,apellidos_nombres_familiares,fecha_nacimiento_familiar,sexo_familiar,trabaja_familiar,estudia_familiar from v_casd01_sintesis_social where fecha_inscripcion between '$desde' and '$hasta' group by cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cedula_identidad,apellidos_nombres,fecha_nacimiento,sexo,peso,estado_civil,estatura,grupo_sanguineo,denominacion_profesion,denominacion_oficio,cod_ambito,cod_zona,cod_vivienda,direccion_habitacion,telefonos_fijos,telefonos_movil,fecha_inscripcion,denominacion_estado,denominacion_municipio,denominacion_parroquia,denominacion_centro,cod_tenencia_vivienda,anos_residencia,monto_alquiler_hipoteca,cod_mision,cedula_familiar,denominacion_parentesco,apellidos_nombres_familiares,fecha_nacimiento_familiar,sexo_familiar,trabaja_familiar,estudia_familiar");
                $this->set('datos1', $datos1);
                $datos2 = $this->v_historia_solicitud_ayudas->execute("select * from v_historia_solicitud_ayudas order by cedula_identidad,fecha_ayuda desc");
                $this->set('datos2', $datos2);
            } else {
                $datos = $this->v_casd01_sintesis_social->execute("select cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cedula_identidad,apellidos_nombres,fecha_nacimiento,sexo,peso,estado_civil,estatura,grupo_sanguineo,denominacion_profesion,denominacion_oficio,cod_ambito,cod_zona,cod_vivienda,direccion_habitacion,telefonos_fijos,telefonos_movil,fecha_inscripcion,denominacion_estado,denominacion_municipio,denominacion_parroquia,denominacion_centro,cod_tenencia_vivienda,anos_residencia,monto_alquiler_hipoteca,cod_mision from v_casd01_sintesis_social where cedula_identidad='$var2' group by cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cedula_identidad,apellidos_nombres,fecha_nacimiento,sexo,peso,estado_civil,estatura,grupo_sanguineo,denominacion_profesion,denominacion_oficio,cod_ambito,cod_zona,cod_vivienda,direccion_habitacion,telefonos_fijos,telefonos_movil,fecha_inscripcion,denominacion_estado,denominacion_municipio,denominacion_parroquia,denominacion_centro,cod_tenencia_vivienda,anos_residencia,monto_alquiler_hipoteca,cod_mision");

                $datos1 = $this->v_casd01_sintesis_social->execute("select cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cedula_identidad,apellidos_nombres,fecha_nacimiento,sexo,peso,estado_civil,estatura,grupo_sanguineo,denominacion_profesion,denominacion_oficio,cod_ambito,cod_zona,cod_vivienda,direccion_habitacion,telefonos_fijos,telefonos_movil,fecha_inscripcion,denominacion_estado,denominacion_municipio,denominacion_parroquia,denominacion_centro,cod_tenencia_vivienda,anos_residencia,monto_alquiler_hipoteca,cod_mision,cedula_familiar,denominacion_parentesco,apellidos_nombres_familiares,fecha_nacimiento_familiar,sexo_familiar,trabaja_familiar,estudia_familiar from v_casd01_sintesis_social where cedula_identidad='$var2' group by cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cedula_identidad,apellidos_nombres,fecha_nacimiento,sexo,peso,estado_civil,estatura,grupo_sanguineo,denominacion_profesion,denominacion_oficio,cod_ambito,cod_zona,cod_vivienda,direccion_habitacion,telefonos_fijos,telefonos_movil,fecha_inscripcion,denominacion_estado,denominacion_municipio,denominacion_parroquia,denominacion_centro,cod_tenencia_vivienda,anos_residencia,monto_alquiler_hipoteca,cod_mision,cedula_familiar,denominacion_parentesco,apellidos_nombres_familiares,fecha_nacimiento_familiar,sexo_familiar,trabaja_familiar,estudia_familiar");
                $this->set('datos1', $datos1);
                $datos2 = $this->v_historia_solicitud_ayudas->execute("select * from v_historia_solicitud_ayudas where cedula_identidad='$var2' order by cedula_identidad,fecha_ayuda desc");
                $this->set('datos2', $datos2);
            }
            //		pr($datos);
            $this->set('datos', $datos);
        }
    }

//fin planilla

    function solicitud_recurso_organismos($ir = null, $var = null) {
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        if ($ir == 'si') {
            $this->layout = "ajax";
            if ($cod_dep == 1) {
                $dep = $this->arrd05->generateList($conditions = $this->condicionNDEP(), $order = 'cod_dep', $limit = null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
                $this->concatena($dep, 'dependencias');
            } else {
                $dep = $this->arrd05->generateList($conditions = $this->condicion(), $order = 'cod_dep', $limit = null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
                $this->concatena($dep, 'dependencias');
            }
            $ano = $this->ano_ejecucion();
            $this->set('year', $ano);
            $this->set('ir', 'si');
        } else if ($ir == 'no') {
            $this->layout = "pdf";

            if (isset($this->data['organismo']['tipo_peticion'])) {
                $tipo_peticion = $this->data['organismo']['tipo_peticion'];
                if ($tipo_peticion == 1) {
                    $filtro = $this->condicionNDEP();
                } else {
                    if (!empty($this->data['organismo']['cod_dep'])) {
                        $filtro = $this->condicionNDEP() . " and cod_dep=" . $this->data['organismo']['cod_dep'];
                    } else {
                        $filtro = $this->SQLCA();
                    }
                }
            } else {
                $filtro = $this->SQLCA();
            }

            if (!empty($this->data['organismo']['ano'])) {
                $ano = $this->data['organismo']['ano'];
                $filtro.=" and ano_solicitud=" . $ano;
            }

            if (isset($this->data['organismo']['fecha_desde']) && !empty($this->data['organismo']['fecha_desde']) && !empty($this->data['organismo']['fecha_hasta']) && $this->data['organismo']['fecha_desde'] <= $this->data['organismo']['fecha_hasta']) {

                $tipo = $this->data['organismo']['tipo'];

                if ($tipo == "1") {
                    $filtro.=" and fecha_solicitud BETWEEN '" . $this->data['organismo']['fecha_desde'] . "' AND '" . $this->data['organismo']['fecha_hasta'] . "'";
                } elseif ($tipo == "2") {
                    $filtro.=" and fecha_cheque BETWEEN '" . $this->data['organismo']['fecha_desde'] . "' AND '" . $this->data['organismo']['fecha_hasta'] . "'";
                }
            }


            //para los totales dentro de los Rangos de Fechas

            $op_fecha = $this->data['organismo']['op_fecha'];

            if ($op_fecha == "1") {
                $filtro_solicitado = "(select sum(b.monto_solicitado) as solicitado from csrd01_solicitud_recurso_cuerpo b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano_solicitud=a.ano_solicitud) as solicitado,";
                $filtro_entregado = "(select sum(b.monto_entregado) as entregado from csrd01_solicitud_recurso_cuerpo b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano_solicitud=a.ano_solicitud) as monto_entregado,";



            } else { //por Rango de Fechas
                //Solicitud
                 if ($tipo == "1") {
                $filtro_solicitado = "(select sum(b.monto_solicitado) as solicitado from csrd01_solicitud_recurso_cuerpo b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano_solicitud=a.ano_solicitud and b.fecha_solicitud BETWEEN '" . $this->data['organismo']['fecha_desde'] . "' AND '" . $this->data['organismo']['fecha_hasta'] . "') as solicitado,";
                $filtro_entregado = "(select sum(b.monto_entregado) as entregado from csrd01_solicitud_recurso_cuerpo b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano_solicitud=a.ano_solicitud and b.fecha_cheque BETWEEN '" . $this->data['organismo']['fecha_desde'] . "' AND '" . $this->data['organismo']['fecha_hasta'] . "') as monto_entregado,";

                //Cheque
                 }else if ($tipo == "2") {
                $filtro_solicitado = "(select sum(b.monto_solicitado) as solicitado from csrd01_solicitud_recurso_cuerpo b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano_solicitud=a.ano_solicitud and b.fecha_cheque BETWEEN '" . $this->data['organismo']['fecha_desde'] . "' AND '" . $this->data['organismo']['fecha_hasta'] . "') as solicitado,";
                $filtro_entregado = "(select sum(b.monto_entregado) as entregado from csrd01_solicitud_recurso_cuerpo b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano_solicitud=a.ano_solicitud and b.fecha_cheque BETWEEN '" . $this->data['organismo']['fecha_desde'] . "' AND '" . $this->data['organismo']['fecha_hasta'] . "') as monto_entregado,";
                 }
            }


            $sql = "SELECT
				a.cod_presi,
				a.cod_entidad,
				a.cod_tipo_inst,
				a.cod_inst,
				a.cod_dep,
				a.ano_solicitud,
				a.numero_solicitud,
				a.fecha_solicitud,
				a.monto_solicitado,
				a.monto_entregado as monto_entregado_solicitud,
				a.cod_entidad_bancaria,
				a.cod_sucursal,
				(select b.denominacion from cstd01_entidades_bancarias b where b.cod_entidad_bancaria=a.cod_entidad_bancaria) as denominacion_entidad_bancaria,
				(select b.denominacion from cstd01_sucursales_bancarias b where b.cod_entidad_bancaria=a.cod_entidad_bancaria and b.cod_sucursal=a.cod_sucursal) as denominacion_sucursal_bancaria,
				a.cuenta_bancaria,
				a.numero_cheque,
				a.fecha_cheque,
				a.concepto,
				a.frecuencia_solicitud,
				a.tipo_solicitud_recurso,
				a.forma_solicitud,
				a.mes_solicitado,
				a.numero_quincena,
				a.asignacion_inicial,
				a.aumentos,
				a.disminuciones,
				a.asignacion_ajustada,
				a.monto_solicitado_acumulado,
				a.monto_entregado_acumulado,
				a.disponibilidad_anual,
				a.disponibilidad_fecha,
				a.monto_reintegro,
				a.monto_reintegro_acumulado,
				(select b.denominacion from arrd05 b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep) as denominacion_dependencia,
				(select sum(b.asignacion_anual) as asignacion_anual from cfpd05 b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano=a.ano_solicitud) as asignacion_inicial_1,
				(select sum(b.aumento_traslado_anual + b.credito_adicional_anual) as aumentos from cfpd05 b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano=a.ano_solicitud) as aumento_1,
				(select sum(b.disminucion_traslado_anual + b.rebaja_anual) as disminuciones from cfpd05 b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano=a.ano_solicitud) as disminuciones,
				(select sum(b.precompromiso_congelado+precompromiso_requisicion+precompromiso_fondo_avance) as precompromiso from cfpd05 b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano=a.ano_solicitud) as precompromiso,
				" . $filtro_entregado . "
				" . $filtro_solicitado . "
				(select sum(b.monto_reintegro) as solicitado from csrd01_solicitud_recurso_cuerpo b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano_solicitud=a.ano_solicitud) as reintegro,
				(select sum(b.monto_reintegro_acumulado) as solicitado from csrd01_solicitud_recurso_cuerpo b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano_solicitud=a.ano_solicitud) as reintegro_acumulado
				  FROM csrd01_solicitud_recurso_cuerpo a WHERE " . $filtro . " order by cod_dep,numero_solicitud asc";

            $datos = $this->csrd01_solicitud_recurso_cuerpo->execute($sql);
//		pr($datos);
            if ($datos != null) {
                $this->set('datos', $datos);
            } else {
                $this->set('datos', null);
            }
            $this->set('ir', 'no');
        } else if ($ir == 'peticion') {
            $this->layout = "ajax";
            $this->set('peticion', $var);
            $dep = $this->arrd05->generateList($conditions = $this->condicionNDEP(), $order = 'cod_dep', $limit = null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
            $this->concatena($dep, 'dependencias');
        }
    }

    function solicitud_recurso_resumido($ir = null, $var = null) {
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        if ($ir == 'si') {
            $this->layout = "ajax";
            if ($cod_dep == 1) {
                $dep = $this->arrd05->generateList($conditions = $this->condicionNDEP(), $order = 'cod_dep', $limit = null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
                $this->concatena($dep, 'dependencias');
            } else {
                $dep = $this->arrd05->generateList($conditions = $this->condicion(), $order = 'cod_dep', $limit = null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
                $this->concatena($dep, 'dependencias');
            }
            $ano = $this->ano_ejecucion();
            $this->set('year', $ano);
            $this->set('ir', 'si');
        } else if ($ir == 'no') {
            $this->layout = "pdf";

            if (isset($this->data['organismo']['tipo_peticion'])) {
                $tipo_peticion = $this->data['organismo']['tipo_peticion'];
                if ($tipo_peticion == 1) {
                    $filtro = $this->condicionNDEP();
                } else {
                    if (!empty($this->data['organismo']['cod_dep'])) {
                        $filtro = $this->condicionNDEP() . " and cod_dep=" . $this->data['organismo']['cod_dep'];
                    } else {
                        $filtro = $this->SQLCA();
                    }
                }
            } else {
                $filtro = $this->SQLCA();
            }

           $ano = $this->data['organismo']['ano'];



           $sql="SELECT * from v_csrd01_solicitud_recurso_aprob_mes where ano_solicitud=".$ano." and ".$filtro;;

            $datos = $this->csrd01_solicitud_recurso_cuerpo->execute($sql);
	//	pr($datos);
            if ($datos != null) {
                $this->set('datos', $datos);
            } else {
                $this->set('datos', null);
            }
            $this->set('ir', 'no');
        } else if ($ir == 'peticion') {
            $this->layout = "ajax";
            $this->set('peticion', $var);
            $dep = $this->arrd05->generateList($conditions = $this->condicionNDEP(), $order = 'cod_dep', $limit = null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
            $this->concatena($dep, 'dependencias');
        }
    }


    function rango($var = null) {
        $this->layout = "ajax";
        $this->set('var', $var);
    }

    function casp01_archivos_planos($ir = null, $var = null) {
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        if ($ir == 'no') {
            $this->layout = "ajax";
            $this->set('ir', 'no');
        } else {
            $this->layout = "txt";
            $this->set('ir', 'si');

            $tipo_peticion = $this->data['casp01']['tipo_peticion'];
            if ($tipo_peticion == 1) {
                $nombre_archivo = "solicitudes_todas_" . date('d_m_Y');
                $sql = "SELECT
					b.cod_presi,
					b.cod_entidad,
					b.cod_tipo_inst,
					b.cod_inst,
					b.cod_dep,
					b.cedula_identidad,
					(select a.apellidos_nombres from casd01_datos_personales a where b.cedula_identidad=a.cedula_identidad limit 1) as apellidos_nombres,
					(select a.sexo from casd01_datos_personales a where b.cedula_identidad=a.cedula_identidad limit 1) as sexo,
					(select a.telefonos_fijos from casd01_datos_personales a where b.cedula_identidad=a.cedula_identidad limit 1) as telefonos_fijos,
					(select a.telefonos_movil from casd01_datos_personales a where b.cedula_identidad=a.cedula_identidad limit 1) as telefonos_movil,
					(select a.denominacion_estado from v_casd01_relacion_solicitantes a where b.cedula_identidad=a.cedula_identidad limit 1) as denominacion_estado,
					(select a.denominacion_municipio from v_casd01_relacion_solicitantes a where b.cedula_identidad=a.cedula_identidad limit 1) as denominacion_municipio,
					(select a.denominacion_parroquia from v_casd01_relacion_solicitantes a where b.cedula_identidad=a.cedula_identidad limit 1) as denominacion_parroquia,
					(select a.denominacion_centro from v_casd01_relacion_solicitantes a where b.cedula_identidad=a.cedula_identidad limit 1) as denominacion_centro,
					(select a.direccion_habitacion from casd01_datos_personales a where b.cedula_identidad=a.cedula_identidad limit 1) as direccion_habitacion
					  FROM casd01_solicitud_ayuda b where b.cod_presi='$cod_presi' and b.cod_entidad='$cod_entidad' and b.cod_tipo_inst='$cod_tipo_inst' and b.cod_inst='$cod_inst'
					  group by
					b.cod_presi,
					b.cod_entidad,
					b.cod_tipo_inst,
					b.cod_inst,
					b.cod_dep,
					b.cedula_identidad order by b.cedula_identidad asc;
			";
            } else if ($tipo_peticion == 2) {
                $nombre_archivo = "solicitudes_aprobadas_" . date('d_m_Y');
                $sql = "SELECT
					b.cod_presi,
					b.cod_entidad,
					b.cod_tipo_inst,
					b.cod_inst,
					b.cod_dep,
					b.cedula_identidad,
					(select a.apellidos_nombres from casd01_datos_personales a where b.cedula_identidad=a.cedula_identidad limit 1) as apellidos_nombres,
					(select a.sexo from casd01_datos_personales a where b.cedula_identidad=a.cedula_identidad limit 1) as sexo,
					(select a.telefonos_fijos from casd01_datos_personales a where b.cedula_identidad=a.cedula_identidad limit 1) as telefonos_fijos,
					(select a.telefonos_movil from casd01_datos_personales a where b.cedula_identidad=a.cedula_identidad limit 1) as telefonos_movil,
					(select a.denominacion_estado from v_casd01_relacion_solicitantes a where b.cedula_identidad=a.cedula_identidad limit 1) as denominacion_estado,
					(select a.denominacion_municipio from v_casd01_relacion_solicitantes a where b.cedula_identidad=a.cedula_identidad limit 1) as denominacion_municipio,
					(select a.denominacion_parroquia from v_casd01_relacion_solicitantes a where b.cedula_identidad=a.cedula_identidad limit 1) as denominacion_parroquia,
					(select a.denominacion_centro from v_casd01_relacion_solicitantes a where b.cedula_identidad=a.cedula_identidad limit 1) as denominacion_centro,
					(select a.direccion_habitacion from casd01_datos_personales a where b.cedula_identidad=a.cedula_identidad limit 1) as direccion_habitacion
					  FROM casd01_solicitud_ayuda b where b.cod_presi='$cod_presi' and b.cod_entidad='$cod_entidad' and b.cod_tipo_inst='$cod_tipo_inst' and b.cod_inst='$cod_inst' and b.numero_documento_ayuda is not null
					  group by
					b.cod_presi,
					b.cod_entidad,
					b.cod_tipo_inst,
					b.cod_inst,
					b.cod_dep,
					b.cedula_identidad order by b.cedula_identidad asc ;
			";
            } else {
                $nombre_archivo = "solicitudes_no_aprobadas_" . date('d_m_Y');
                $sql = "SELECT
					b.cod_presi,
					b.cod_entidad,
					b.cod_tipo_inst,
					b.cod_inst,
					b.cod_dep,
					b.cedula_identidad,
					(select a.apellidos_nombres from casd01_datos_personales a where b.cedula_identidad=a.cedula_identidad limit 1) as apellidos_nombres,
					(select a.sexo from casd01_datos_personales a where b.cedula_identidad=a.cedula_identidad limit 1) as sexo,
					(select a.telefonos_fijos from casd01_datos_personales a where b.cedula_identidad=a.cedula_identidad limit 1) as telefonos_fijos,
					(select a.telefonos_movil from casd01_datos_personales a where b.cedula_identidad=a.cedula_identidad limit 1) as telefonos_movil,
					(select a.denominacion_estado from v_casd01_relacion_solicitantes a where b.cedula_identidad=a.cedula_identidad limit 1) as denominacion_estado,
					(select a.denominacion_municipio from v_casd01_relacion_solicitantes a where b.cedula_identidad=a.cedula_identidad limit 1) as denominacion_municipio,
					(select a.denominacion_parroquia from v_casd01_relacion_solicitantes a where b.cedula_identidad=a.cedula_identidad limit 1) as denominacion_parroquia,
					(select a.denominacion_centro from v_casd01_relacion_solicitantes a where b.cedula_identidad=a.cedula_identidad limit 1) as denominacion_centro,
					(select a.direccion_habitacion from casd01_datos_personales a where b.cedula_identidad=a.cedula_identidad limit 1) as direccion_habitacion
					  FROM casd01_solicitud_ayuda b where b.cod_presi='$cod_presi' and b.cod_entidad='$cod_entidad' and b.cod_tipo_inst='$cod_tipo_inst' and b.cod_inst='$cod_inst' and b.numero_documento_ayuda is null
					  group by
					b.cod_presi,
					b.cod_entidad,
					b.cod_tipo_inst,
					b.cod_inst,
					b.cod_dep,
					b.cedula_identidad order by b.cedula_identidad asc ;
			";
            }

            $_SESSION["nombre_txt"] = $nombre_archivo . ".txt";
            $datos = $data3 = $this->v_casd01_relacion_solicitantes->execute($sql);
            $filas_archivo = "";
            foreach ($datos as $rsdata) {
                extract($rsdata[0]);

                $cedula_pricipal = mascara($cedula_identidad, 10);
                $nombre_completo = str_replace('  ', ' ', trim($apellidos_nombres));
                $nombre_completo = str_replace("\t", ' ', $nombre_completo);
                $nombre_completo = str_replace("Ñ", '@', $nombre_completo);
                $nombre_completo = str_replace("Ñ", '@', $nombre_completo);
                $nombre = cortar_cadena_diskette(elimina_acentos($nombre_completo), 30);

                if ($sexo == 1) {
                    $sexo = 'MASCULINO';
                } else {
                    $sexo = 'FEMENINO';
                }
                /*
                  if($sexo_fami==1){
                  $sexo_fami='MASCULINO';
                  }else{
                  $sexo_fami='FEMENINO';
                  }

                  if($estudia==1){
                  $estudia='SI';
                  }else{
                  $estudia='NO';
                  }

                  if($trabaja==1){
                  $trabaja='SI';
                  }else{
                  $trabaja='NO';
                  }

                 */

                $sexo = cortar_cadena_diskette(elimina_acentos($sexo), 9);
                $telefonos_fijos = cortar_cadena_diskette(elimina_acentos($telefonos_fijos), 17);
                $telefonos_movil = cortar_cadena_diskette(elimina_acentos($telefonos_movil), 17);
                $denominacion_estado = cortar_cadena_diskette(elimina_acentos($denominacion_estado), 15);
                $denominacion_municipio = cortar_cadena_diskette(elimina_acentos($denominacion_municipio), 20);
                $denominacion_parroquia = cortar_cadena_diskette(elimina_acentos($denominacion_parroquia), 25);
                $denominacion_centro = cortar_cadena_diskette(elimina_acentos($denominacion_centro), 25);
//			$direccion_habitacion = cortar_cadena_diskette(elimina_acentos($direccion_habitacion), 9);

                $telefonos = $telefonos_fijos . " - " . $telefonos_movil;
//			$filas_archivo .=$cedula_pricipal."\t".$nombre."\t".$sexo."\t".$telefonos."\t".$deno_parentesco."\t".$cedula."\t".$fami."\t".$sexo_fami."\t".$trabaja."\t".$estudia."\n";
                $filas_archivo .=$cedula_pricipal . "\t" . $nombre . "\t" . $sexo . "\t" . $telefonos . "\t" . $denominacion_estado . "\t" . $denominacion_municipio . "\t" . $denominacion_parroquia . "\t" . $denominacion_centro . "\t" . $direccion_habitacion . "\n";
            }
            $this->wFile($nombre_archivo, $filas_archivo);
            $this->set('filas_archivo', $filas_archivo);
        }
    }

}

//fin class
?>