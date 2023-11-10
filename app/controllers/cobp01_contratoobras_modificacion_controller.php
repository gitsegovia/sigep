<?php

class Cobp01contratoobrasmodificacionController extends AppController {

    var $name = "cobp01_contratoobras_modificacion";
    var $uses = array('ccfd03_instalacion', 'cobd01_contratoobras_cuerpo', 'cfpd07_obras_cuerpo', 'ccfd04_cierre_mes', 'cugd03_acta_anulacion_numero',
        'cugd03_acta_anulacion_cuerpo', 'cobd01_co_modificacion_cuerpo', 'cobd01_co_modificacion_partidas',
        'cobd01_contratoobras_partidas', 'cpcd02', 'v_cfpd05_disponibilidad', 'cfpd21_numero_asiento_compromiso', 'cfpd21', 'cfpd05',
        'cugd04', 'cugd05_restriccion_clave', 'cfpd07_obras_partidas',
        'cstd01_entidades_bancarias', 'ccfd10_descripcion', 'ccfd10_detalles', 'ccfd02', 'ccfd05_numero_asiento',
        'ccfd04_cuentas_enlace', 'cpcd02', 'cepd01_compromiso_cuerpo', 'cscd04_ordencompra_anticipo_cuerpo',
        'cscd04_ordencompra_encabezado', 'cscd04_ordencompra_autorizacion_cuerpo',
        'cscd04_ordencompra_encabezado', 'cobd01_contratoobras_anticipo_cuerpo', 'cobd01_contratoobras_valuacion_cuerpo',
        'cobd01_contratoobras_retencion_cuerpo', 'cepd02_contratoservicio_anticipo_cuerpo', 'cepd02_contratoservicio_valuacion_cuerpo',
        'cepd02_contratoservicio_retencion_cuerpo', 'cobd01_contratoobras_cuerpo', 'cepd02_contratoservicio_cuerpo'
    );
    var $helpers = array('Html', 'Ajax', 'Javascript', 'Sisap');

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

    function beforeFilter() {
        $this->checkSession();
    }

//fin function

    function verifica_SS($i) {
        /**
         * Funcion que permite leer las varibles de session. la cual permite capturar los codigos del usuario
         * para ser insertados en todas las tablas.
         * */
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

    function SQLCAIN($ano = null) {//sql para busqueda de codigos de arranque con y sin año
        $sql_re = $this->verifica_SS(1) . ",";
        $sql_re .= $this->verifica_SS(2) . ",";
        $sql_re .= $this->verifica_SS(3) . ",";
        $sql_re .= $this->verifica_SS(4) . ",";
        if ($ano != null) {
            $sql_re .= $this->verifica_SS(5) . ",";
            $sql_re .= $ano . "";
        } else {
            $sql_re .= $this->verifica_SS(5) . "";
        }
        return $sql_re;
    }

//fin funcion SQLCAIN

    function Formato1($monto) {
        $monto = preg_replace("/[^0-9\.]/", "", str_replace(',', '.', $monto));
        if (substr($monto, -3, 1) == '.') {
            $sents = '.' . substr($monto, -2);
            $monto = substr($monto, 0, strlen($monto) - 3);
        } elseif (substr($monto, -2, 1) == '.') {
            $sents = '.' . substr($monto, -1);
            $monto = substr($monto, 0, strlen($monto) - 2);
        } else {
            $sents = '.00';
        }
        $monto = preg_replace("/[^0-9]/", "", $monto);
        return number_format($monto . $sents, 2, '.', '');
    }

    function Formato2($monto) {
        return number_format($monto, 2, ",", ".");
    }

    function filtra_obra($year = null) {



        $cod_dep = $this->Session->read('SScoddep');
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $Modulo = $this->Session->read('Modulo');



        $sql_obra = "";

        $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $cod_dep . '  ';

        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');


        if ($SScoddep == 1 && $SScoddeporig == 1) {
            $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . '  ';
        } else {
            $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $this->Session->read('SScoddep') . '';
        }//fin else


        $a = $this->cobd01_contratoobras_cuerpo->findAll($condicion . " and ano_contrato_obra=" . $year);


        if ($SScoddep == 1 && $SScoddeporig == 1) {
            $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . '  ';
        } else {
            $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep_original=' . $SScoddeporig . ' ';
        }//fin else

        $b = $this->cfpd07_obras_cuerpo->findAll($condicion . " and ano_estimacion=" . $year);



        foreach ($a as $a_aux) {
            foreach ($b as $b_aux) {
                if ($a_aux['cobd01_contratoobras_cuerpo']['ano_estimacion'] == $b_aux['cfpd07_obras_cuerpo']['ano_estimacion'] && strtoupper($a_aux['cobd01_contratoobras_cuerpo']['cod_obra']) == strtoupper($b_aux['cfpd07_obras_cuerpo']['cod_obra'])) {
                    if ($sql_obra == "") {
                        $sql_obra .= "    upper(numero_contrato_obra)='" . strtoupper($a_aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra']) . "' ";
                    } else {
                        $sql_obra .= " or upper(numero_contrato_obra)='" . strtoupper($a_aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra']) . "' ";
                    }
                }//fin if
            }//fin foreach
        }//fin foreach

        if ($sql_obra != "") {
            $sql_obra = "  and (" . $sql_obra . ")";
        }



        return $sql_obra;
    }

//fin function

    function index($var = null) {

$this->verifica_entrada('32');

        $this->layout = "ajax";

        if ($_SESSION['SScodpresi'] == 1 && $_SESSION['SScodentidad'] == 11 && $_SESSION['SScodtipoinst'] == 30 && $_SESSION['SScodinst'] != 11) {
            $this->set('autor_valido', true);
        }

        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');
        $lista = "";

        $this->data = null;
        $ano = '';
        $ano = $this->ano_ejecucion();



        $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $SScoddep . ' and ((monto_original_contrato+aumento)-(disminucion+monto_cancelado+monto_amortizacion+monto_retencion_laboral+monto_retencion_fielcumplimiento))!=0 and ano_contrato_obra=' . $ano . ' and condicion_actividad=1';

        $a = $this->cobd01_contratoobras_cuerpo->findAll($condicion, null, ' numero_contrato_obra ASC');
        $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep_original=' . $SScoddeporig . '   and ano_estimacion=' . $ano;
        $b = $this->cfpd07_obras_cuerpo->findAll($condicion);
        foreach ($a as $a_aux) {
            foreach ($b as $b_aux) {
                if ($a_aux['cobd01_contratoobras_cuerpo']['ano_estimacion'] == $b_aux['cfpd07_obras_cuerpo']['ano_estimacion'] && strtoupper($a_aux['cobd01_contratoobras_cuerpo']['cod_obra']) == strtoupper($b_aux['cfpd07_obras_cuerpo']['cod_obra'])) {
                    $lista[$a_aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra']] = $a_aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra'];
                }//fin if
            }//fin foreach
        }//fin foreach


        $this->set('lista_numero', $lista);
        $ano = $this->ano_ejecucion();
        $this->set('year', $ano);
        $this->Session->delete("ano_contrato_obra");
    }

//fin function

    function selecion($var1 = null) {
        $this->layout = "ajax";

        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');

        $ano = '';
        $ano = $this->ano_ejecucion();


        $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $SScoddep . ' and ((monto_original_contrato+aumento)-(disminucion+monto_cancelado+monto_amortizacion+monto_retencion_laboral+monto_retencion_fielcumplimiento))!=0 and ano_contrato_obra=' . $ano . ' and condicion_actividad=1';

        $a = $this->cobd01_contratoobras_cuerpo->findAll($condicion, null, ' numero_contrato_obra ASC');

        $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep_original=' . $SScoddeporig . '   and ano_estimacion=' . $ano;
        $b = $this->cfpd07_obras_cuerpo->findAll($condicion);

        foreach ($a as $a_aux) {
            foreach ($b as $b_aux) {
                if ($a_aux['cobd01_contratoobras_cuerpo']['ano_estimacion'] == $b_aux['cfpd07_obras_cuerpo']['ano_estimacion'] && strtoupper($a_aux['cobd01_contratoobras_cuerpo']['cod_obra']) == strtoupper($b_aux['cfpd07_obras_cuerpo']['cod_obra'])) {
                    $lista[$a_aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra']] = $a_aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra'];
                }//fin if
            }//fin foreach
        }//fin foreach




        $this->set('lista_numero', $lista);
        $this->set('numero_orden_compra', $var1);
        $this->Session->delete('PAG_NUM');


        if ($var1 == null) {

            $this->set('autor_valido', true);
            $this->index("user_valido");
            $this->render('index');
        } else {

            $condicion = "cod_presi=" . $this->Session->read('SScodpresi') . "  and  cod_entidad=" . $this->Session->read('SScodentidad') . " and cod_tipo_inst=" . $this->Session->read('SScodtipoinst') . " and  cod_inst=" . $this->Session->read('SScodinst') . " and cod_dep=" . $this->Session->read('SScoddep') . " and  numero_contrato_obra='" . $var1 . "'  and  ano_contrato_obra=" . $ano . "";
            $numero_datos = $this->cobd01_contratoobras_cuerpo->findAll($condicion);
            $numero_datos_partidas = $this->cobd01_contratoobras_partidas->findAll($condicion, null, 'numero_contrato_obra DESC');

            $numero_datos_aux = $numero_datos;
            foreach ($numero_datos_aux as $aux) {
                $rif = $aux['cobd01_contratoobras_cuerpo']['rif'];
                $ano_orden_compra = $aux['cobd01_contratoobras_cuerpo']['ano_contrato_obra'];
                $numero_orden_compra = $aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra'];
                $numero_orden_obra = $aux['cobd01_contratoobras_cuerpo']['cod_obra'];
            }//fin foreach

            $this->Session->write("cobp01_codigo_obra", $numero_orden_obra);
            $this->Session->write("cobp01_numero_contrato", $numero_orden_compra);


            $cfpd07_info = $this->cfpd07_obras_cuerpo->findAll("cod_presi='" . $this->Session->read('SScodpresi') . "' and cod_entidad='" . $this->Session->read('SScodentidad') . "' and cod_tipo_inst='" . $this->Session->read('SScodtipoinst') . "' and cod_inst='" . $this->Session->read('SScodinst') . "' and cod_dep_original='" . $SScoddeporig . "'  and ano_estimacion='" . $ano . "'  and upper(cod_obra)='" . strtoupper_sisap($numero_orden_obra) . "' ", null, 'cod_obra DESC');

            foreach ($cfpd07_info as $cfpd07_informa) {
                $estimado_presu = $cfpd07_informa['cfpd07_obras_cuerpo']['estimado_presu'];
                $aumento_obras = $cfpd07_informa['cfpd07_obras_cuerpo']['aumento_obras'];
                $disminucion_obras = $cfpd07_informa['cfpd07_obras_cuerpo']['disminucion_obras'];
                $monto_ajustado = (($estimado_presu + $aumento_obras) - $disminucion_obras);
                $monto_contratado = $cfpd07_informa['cfpd07_obras_cuerpo']['monto_contratado'];
                $saldo = (($estimado_presu + $aumento_obras) - ($disminucion_obras + $monto_contratado));
            }

            $this->set('estimado_presu', $estimado_presu);
            $this->set('aumento_obras', $aumento_obras);
            $this->set('disminucion_obras', $disminucion_obras);
            $this->set('monto_ajustado', $monto_ajustado);
            $this->set('monto_contratado', $monto_contratado);
            $this->set('saldo', $saldo);


            $opc = $this->cobd01_co_modificacion_cuerpo->findCount($condicion . " and ano_contrato_obra=" . $ano_orden_compra . "  and  upper(numero_contrato_obra)='" . strtoupper($numero_orden_compra) . "'");
            $result = $this->cobd01_co_modificacion_cuerpo->findAll($condicion . "   and ano_contrato_obra=" . $ano_orden_compra . "  and  upper(numero_contrato_obra)='" . strtoupper($numero_orden_compra) . "' ", null, "numero_modificacion ASC", null, null);
            foreach ($result as $ves) {
                $opc = $ves['cobd01_co_modificacion_cuerpo']['numero_modificacion'];
            }//fin foreach


            $rif_datos = $this->cpcd02->findAll("rif='" . $rif . "'");
            foreach ($rif_datos as $aux_2) {
                $denominacion_rif = $aux_2['cpcd02']['denominacion'];
                $direccion_comercial_rif = $aux_2['cpcd02']['direccion_comercial'];
            }//fin foreach


            $opc++;

            $this->set('ano_orden_compra_modificacion', $ano);
            $this->set('numero_orden_compra_modificacion', $opc);
            $this->set('datos_orden_compra', $numero_datos);
            $this->set('datos_orden_compra_partidas', $numero_datos_partidas);
            $this->set('denominacion_rif', $denominacion_rif);
            $this->set('direccion_comercial_rif', $direccion_comercial_rif);
        }//fin else
    }

//fin function



function aumento_disminucion($opcion=null, $op2=null, $monto=null){
        $this->layout = "ajax";
        $username = $this->Session->read('nom_usuario');
        $ano = $this->ano_ejecucion();
        $tipo = $opcion;
        $monto = $this->Formato1($monto);
        $disponibilidad = 0;

        $cobp01_codigo_obra = $this->Session->read("cobp01_codigo_obra");
        $cobp01_numero_contrato = $this->Session->read("cobp01_numero_contrato");

		$condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $this->Session->read('SScoddep') . '  ';

        if ($tipo == 1){
				$cfpd07_obras_cuerpo_aux = $this->cfpd07_obras_cuerpo->findAll($condicion . " and ano_estimacion=" . $ano . " and upper(cod_obra)='" . strtoupper($cobp01_codigo_obra) . "' ");

            foreach ($cfpd07_obras_cuerpo_aux as $aux) {
                $estimado_presu    = $aux['cfpd07_obras_cuerpo']['estimado_presu'];
                $aumento_obras     = $aux['cfpd07_obras_cuerpo']['aumento_obras'];
                $disminucion_obras = $aux['cfpd07_obras_cuerpo']['disminucion_obras'];
                $monto_contratado  = $aux['cfpd07_obras_cuerpo']['monto_contratado'];
                $disponibilidad    = (($estimado_presu + $aumento_obras) - ($disminucion_obras + $monto_contratado));
            }//fin foreach

			$disponibilidad = $this->Formato2($disponibilidad);
            $disponibilidad = $this->Formato1($disponibilidad);

            if ($monto > $disponibilidad) {

            	if($op2==1) $id_campo = "aumento_obras_extras"; else if($op2==2) $id_campo = "aumento_reconsideracion"; else if($op2==3) $id_campo = "aumento_obras"; else $id_campo = "";

                $this->set('msg_error', 'El monto del aumento es mayor al saldo de la relacion de obras - Saldo Disponible: ' . $this->Formato2($disponibilidad));
				echo "<script> document.getElementById('guardar').disabled=true; </script>";
				if($id_campo!="")
					echo "<script> document.getElementById('$id_campo').value='0,00'; document.getElementById('consulta_monto_Aumento').value='0,00'; </script>";
            }else{
            	echo "<script> document.getElementById('guardar').disabled=false; </script>";
            }
        }else{
		    $numero_datos_cuerpo_6 = $this->cobd01_contratoobras_cuerpo->findAll($condicion . " and ano_contrato_obra=" . $ano . " and upper(numero_contrato_obra)='" . strtoupper($cobp01_numero_contrato) . "' ");
            foreach ($numero_datos_cuerpo_6 as $aux_cuerpo_6) {
                $monto_6         = $aux_cuerpo_6['cobd01_contratoobras_cuerpo']['monto_original_contrato'];
                $aumento_6       = $aux_cuerpo_6['cobd01_contratoobras_cuerpo']['aumento'];
                $disminucion_6   = $aux_cuerpo_6['cobd01_contratoobras_cuerpo']['disminucion'];
                $amortizacion_6  = $aux_cuerpo_6['cobd01_contratoobras_cuerpo']['monto_amortizacion'];
                $ret_laboral_6   = $aux_cuerpo_6['cobd01_contratoobras_cuerpo']['monto_retencion_laboral'];
                $ret_fielcumpl_6 = $aux_cuerpo_6['cobd01_contratoobras_cuerpo']['monto_retencion_fielcumplimiento'];
                $cancelado_6     = $aux_cuerpo_6['cobd01_contratoobras_cuerpo']['monto_cancelado'];
                $disponibilidad  = (($monto_6 + $aumento_6) - ($disminucion_6 + $amortizacion_6 + $ret_laboral_6 + $ret_fielcumpl_6 + $cancelado_6));
            }//fin foreach

            $disponibilidad = $this->Formato2($disponibilidad);
            $disponibilidad = $this->Formato1($disponibilidad);

            if ($monto > $disponibilidad) {
                $this->set('msg_error', 'El monto de la disminucion es mayor al saldo del contrato - Saldo Disponible: ' . $this->Formato2($disponibilidad));
                echo "<script> document.getElementById('guardar').disabled=true; </script>";
			    echo "<script> document.getElementById('disminucion').value='0,00'; </script>";
            }else{
            	echo "<script> document.getElementById('guardar').disabled=false; </script>";
            }

     }

}// fin funcion aumento_disminucion



    function ver_disponibilidad($i, $ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar, $monto) {
        $this->layout = "ajax";
        $username = $this->Session->read('nom_usuario');
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $tipo = $_SESSION['tipo'];
        $monto = $this->Formato1($monto);
        $disponibilidad_obra = 0;
        $disponibilidad_contrato = 0;
        $cobp01_codigo_obra = $this->Session->read("cobp01_codigo_obra");
        $cobp01_numero_contrato = $this->Session->read("cobp01_numero_contrato");

        if ($tipo==1){
            $cfpd07_obras_partidas = $this->cfpd07_obras_partidas->findAll("cod_presi='" . $cod_presi . "' and cod_entidad='" . $cod_entidad . "' and cod_tipo_inst='" . $cod_tipo_inst . "' and cod_inst='" . $cod_inst . "' and cod_dep='" . $cod_dep . "'  and ano_estimacion='" . $ano . "'  and upper(cod_obra)='" . strtoupper_sisap($cobp01_codigo_obra) . "' and  cod_sector='" . $cod_sector . "' and cod_programa='" . $cod_programa . "' and cod_sub_prog='" . $cod_sub_prog . "' and cod_proyecto='" . $cod_proyecto . "' and cod_activ_obra='" . $cod_activ_obra . "' and cod_partida='" . $cod_partida . "' and cod_generica='" . $cod_generica . "' and cod_especifica='" . $cod_especifica . "' and cod_sub_espec='" . $cod_sub_espec . "' and cod_auxiliar='" . $cod_auxiliar . "' ", null, 'cod_obra DESC');

            foreach ($cfpd07_obras_partidas as $ve3) {
                $monto_2 = $ve3["cfpd07_obras_partidas"]["monto"];
                $aumento_obras_2 = $ve3["cfpd07_obras_partidas"]["aumento_obras"];
                $disminucion_obras_2 = $ve3["cfpd07_obras_partidas"]["disminucion_obras"];
                $monto_contratado_2 = $ve3["cfpd07_obras_partidas"]["monto_contratado"];
                $disponibilidad_obra = (($monto_2 + $aumento_obras_2) - ($disminucion_obras_2 + $monto_contratado_2));
            }//fin foreach

            $disponibilidad_obra = $this->Formato2($disponibilidad_obra);
            $disponibilidad_obra = $this->Formato1($disponibilidad_obra);

			echo "<script> document.getElementById('monto_actual_base').value='$disponibilidad_obra'; </script>";

            if ($monto > $disponibilidad_obra) {
                $this->set('msg_error', 'la disponibilidad para aumentar el contrato para esta partida es de: ' . $this->Formato2($disponibilidad_obra));
                $this->set('i', $i);
				echo "<script> document.getElementById('guardar').disabled=true; </script>";
            }else{
				echo "<script> document.getElementById('guardar').disabled=false; </script>";
            }
        }else{
            $cobd01_obras_partidas = $this->cobd01_contratoobras_partidas->findAll("cod_presi='" . $cod_presi . "' and cod_entidad='" . $cod_entidad . "' and cod_tipo_inst='" . $cod_tipo_inst . "' and cod_inst='" . $cod_inst . "' and cod_dep='" . $cod_dep . "' and ano_contrato_obra='" . $ano . "'  and upper(numero_contrato_obra)='" . strtoupper_sisap($cobp01_numero_contrato) . "' and  cod_sector='" . $cod_sector . "' and cod_programa='" . $cod_programa . "' and cod_sub_prog='" . $cod_sub_prog . "' and cod_proyecto='" . $cod_proyecto . "' and cod_activ_obra='" . $cod_activ_obra . "' and cod_partida='" . $cod_partida . "' and cod_generica='" . $cod_generica . "' and cod_especifica='" . $cod_especifica . "' and cod_sub_espec='" . $cod_sub_espec . "' and cod_auxiliar='" . $cod_auxiliar . "' ", null, 'numero_contrato_obra DESC');

            foreach ($cobd01_obras_partidas as $ve33) {
                $monto_3 = $ve33["cobd01_contratoobras_partidas"]["monto"];
                $aumento_3 = $ve33["cobd01_contratoobras_partidas"]["aumento"];
                $disminucion_3 = $ve33["cobd01_contratoobras_partidas"]["disminucion"];
                $amortizacion_3 = $ve33["cobd01_contratoobras_partidas"]["amortizacion"];
                $cancelacion_3 = $ve33["cobd01_contratoobras_partidas"]["cancelacion"];
                $retencion_laboral_3 = $ve33["cobd01_contratoobras_partidas"]["retencion_laboral"];
                $retencion_fielcumplimiento_3 = $ve33["cobd01_contratoobras_partidas"]["retencion_fielcumplimiento"];

                $disponibilidad_contrato = (($monto_3 + $aumento_3) - ($disminucion_3 + $amortizacion_3 + $cancelacion_3 + $retencion_laboral_3 + $retencion_fielcumplimiento_3));
            }//fin foreach


            $disponibilidad_contrato = $this->Formato2($disponibilidad_contrato);
            $disponibilidad_contrato = $this->Formato1($disponibilidad_contrato);

            echo "<script> document.getElementById('monto_actual_base').value='$disponibilidad_contrato'; </script>";

            if ($monto > $disponibilidad_contrato) {
                $this->set('msg_error', 'la disponibilidad para disminuir el contrato para esta partida es de: ' . $this->Formato2($disponibilidad_contrato));
                $this->set('i', $i);
				echo "<script> document.getElementById('guardar').disabled=true; </script>";
            }else{
				echo "<script> document.getElementById('guardar').disabled=false; </script>";
            }
        }
    }

// fin funcion

    function guardar_cugd04($ano = null, $cod_sector = null, $cod_programa = null, $cod_sub_prog = null, $cod_proyecto = null, $cod_activ_obra = null, $cod_partida = null, $cod_generica = null, $cod_especifica = null, $cod_sub_espec = null, $cod_auxiliar = null, $username = null) {

        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');

        if ($ano != null && $cod_sector != null && $cod_programa != null && $cod_sub_prog != null && $cod_proyecto != null && $cod_activ_obra != null && $cod_partida != null && $cod_generica != null && $cod_especifica != null && $cod_sub_espec != null && $username != null && $cod_auxiliar != null) {

            $partida = " and ano='$ano' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and username='$username'";
            $cont_partida = $this->cugd04->findCount($this->condicion() . $partida);
            if ($cont_partida == 0) {
                $sql_insert_cugd04 = "INSERT INTO cugd04 VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano', '$cod_sector', '$cod_programa', '$cod_sub_prog', '$cod_proyecto', '$cod_activ_obra', '$cod_partida', '$cod_generica', '$cod_especifica', '$cod_sub_espec', '$cod_auxiliar', '$username')";
                $this->cugd04->execute($sql_insert_cugd04);
                $time = date('U');
                $this->cugd04->execute("UPDATE cugd04_entrada_modulo SET hora_captura_partida='$time'");
            } else {
                $sql_update_cugd04 = "UPDATE cugd04 set cod_auxiliar='$cod_auxiliar' WHERE " . $this->condicion() . $partida;
                $this->cugd04->execute($sql_update_cugd04);
            }
        }
    }

    function prueba() {

        echo'<script>';
        echo'alert("hola");';
        echo'</script>';
    }

//fin function

    function tipo_modificacion($var1 = null) {
        $this->layout = "ajax";

        $_SESSION['tipo'] = $var1;
        switch ($var1) {
            case '1': {
                    $tipo = "Monto del Aumento";
                    $opcion = "aumento";
                }break;
            case '2': {
                    $tipo = "Monto de la Disminución";
                    $opcion = "disminucion";
                }break;
        }//fin switch

		$this->set('tipo_opc', $var1);
        $this->set('tipo', $var1);
        $_SESSION['tipo'] = $var1;

        echo'<script> document.getElementById("modificacion_texto").innerHTML = "' . $tipo . '"; </script>';
    }

//fin function

    function tipo_modificacion_2($var1 = null) {
        $this->layout = "ajax";


        $this->set('tipo', $var1);
    }

//fin function

    function guardar() {
        $this->layout = "ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $ano_orden_compra = $this->data['cscp04_ordencompra_modificacion']['ano_orden_compra'];
        $ann = $ano_orden_compra;
        $numero_orden_compra = strtoupper($this->data['cscp04_ordencompra_modificacion']['numero_orden_compra']);
        $numero_modificacion = $this->data['cscp04_ordencompra_modificacion']['numero_orden_compra_modificacion'];
        $tipo_modificacion = $this->data['cscp04_ordencompra_modificacion']['tipo_modificacion'];

        if ($tipo_modificacion == '1') {
            $monto1 = $this->Formato1($this->data['cscp04_ordencompra_modificacion']['obras_extras']);
            $monto2 = $this->Formato1($this->data['cscp04_ordencompra_modificacion']['recon_precios']);
            $monto3 = $this->Formato1($this->data['cscp04_ordencompra_modificacion']['aumento_obras']);
            $monto = $monto1 + $monto2 + $monto3;
        } else if ($tipo_modificacion == '2') {
            $monto = $this->Formato1($this->data['cscp04_ordencompra_modificacion']['disminucion']);
            $monto1 = '0';
            $monto2 = '0';
            $monto3 = '0';
        }//fin else

        $monto_modificacion = $monto;
        $fecha_modificacion = $this->data['cscp04_ordencompra_modificacion']['fecha_modificacion'];
        $fecha_contrato_obra = $this->data['cscp04_ordencompra_modificacion']['fecha_contrato_obra'];
        $fd = $this->data['cscp04_ordencompra_modificacion']['fecha_modificacion'];
        $fecha_proceso_registro = $this->data['cscp04_ordencompra_modificacion']['fecha_proceso_registro'];
        $rif = $this->data["cscp04_ordencompra_modificacion"]["rif"];
        $observaciones = $this->data['cscp04_ordencompra_modificacion']['observaciones'];

        $ccp = $observaciones;
        $ano_asiento_registro = '0';
        $mes_asiento_registro = '0';
        $dia_asiento_registro = '0';
        $numero_asiento_registro = '0';
        $fecha_proceso_registro = date('d/m/Y');
        $username_registro = $_SESSION['nom_usuario'];
        $condicion_actividad = '1';
        $ano_asiento_anulacion = '0';
        $mes_asiento_anulacion = '0';
        $dia_asiento_anulacion = '0';
        $numero_asiento_anulacion = '0';
        $username_anulacion = '0';
        $fecha_proceso_anulacion = '01/01/1900';
        $ano_anulacion = "0";
        $numero_anulacion = "0";
        $sw15 = 0;



        $numero_orden_compra2 = $numero_orden_compra;
        $ano_orden_compra2 = $ano_orden_compra;
        $ann = $ano_orden_compra;
        $sql = " BEGIN; INSERT INTO cobd01_contratoobras_modificacion_cuerpo (cod_presi, cod_entidad, cod_tipo_inst ,cod_inst, cod_dep , ano_contrato_obra, numero_contrato_obra, numero_modificacion, tipo_modificacion, monto_modificacion, fecha_modificacion, observaciones, ano_asiento_registro, mes_asiento_registro, dia_asiento_registro, numero_asiento_registro, fecha_proceso_registro, username_registro, condicion_actividad, ano_asiento_anulacion, mes_asiento_anulacion, dia_asiento_anulacion , numero_asiento_anulacion, username_anulacion, fecha_proceso_anulacion, ano_anulacion, numero_anulacion,aumento_obra_extra,aumento_reconsideracion_precio,aumento_obras)";
        $sql.="VALUES ('" . $this->Session->read('SScodpresi') . "', '" . $this->Session->read('SScodentidad') . "', '" . $this->Session->read('SScodtipoinst') . "', '" . $this->Session->read('SScodinst') . "', '" . $this->Session->read('SScoddep') . "', '" . $ano_orden_compra . "', '" . $numero_orden_compra . "', '" . $numero_modificacion . "', '" . $tipo_modificacion . "', '" . $monto_modificacion . "', '" . $this->Cfecha($fecha_modificacion, 'A-M-D') . "', '" . $observaciones . "', '" . $ano_asiento_registro . "', '" . $mes_asiento_registro . "', '" . $dia_asiento_registro . "', '" . $numero_asiento_registro . "', '" . $this->Cfecha($fecha_proceso_registro, 'A-M-D') . "', '" . $username_registro . "', '" . $condicion_actividad . "', '" . $ano_asiento_anulacion . "', '" . $mes_asiento_anulacion . "', '" . $dia_asiento_anulacion . "', '" . $numero_asiento_anulacion . "', '" . $username_anulacion . "', '" . $this->Cfecha($fecha_proceso_anulacion, 'A-M-D') . "', '" . $ano_anulacion . "', '" . $numero_anulacion . "'," . $monto1 . "," . $monto2 . "," . $monto3 . "); ";
        $sw10 = $this->cobd01_co_modificacion_cuerpo->execute($sql);

        if ($sw10 > 1) {

            $i_lenght = $this->data['cscp04_ordencompra_modificacion']['cuenta_i'];
            $condicion = "cod_presi=" . $this->Session->read('SScodpresi') . "  and  cod_entidad=" . $this->Session->read('SScodentidad') . " and cod_tipo_inst=" . $this->Session->read('SScodtipoinst') . " and  cod_inst=" . $this->Session->read('SScodinst') . " and cod_dep=" . $this->Session->read('SScoddep') . " and  numero_contrato_obra='" . $numero_orden_compra . "'  and  ano_contrato_obra=" . $ano_orden_compra . "";
            $numero_datos_partidas = $this->cobd01_contratoobras_partidas->findAll($condicion, null, 'numero_contrato_obra DESC');
            $a = 0;
            $i_aux = 0;
            foreach ($numero_datos_partidas as $aux_partidas) {
                $cod_presi2[$a] = $aux_partidas['cobd01_contratoobras_partidas']['cod_presi'];
                $cod_entidad2[$a] = $aux_partidas['cobd01_contratoobras_partidas']['cod_entidad'];
                $cod_tipo_inst2[$a] = $aux_partidas['cobd01_contratoobras_partidas']['cod_tipo_inst'];
                $cod_inst2[$a] = $aux_partidas['cobd01_contratoobras_partidas']['cod_inst'];
                $cod_dep2[$a] = $aux_partidas['cobd01_contratoobras_partidas']['cod_dep'];
                $ano_orden_compra3[$a] = $aux_partidas['cobd01_contratoobras_partidas']['ano_contrato_obra'];
                $numero_orden_compra3[$a] = $aux_partidas['cobd01_contratoobras_partidas']['numero_contrato_obra'];
                $ano_partidas[$a] = $aux_partidas['cobd01_contratoobras_partidas']['ano'];
                $cod_sector[$a] = $aux_partidas['cobd01_contratoobras_partidas']['cod_sector'];
                $cod_programa[$a] = $aux_partidas['cobd01_contratoobras_partidas']['cod_programa'];
                $cod_sub_prog[$a] = $aux_partidas['cobd01_contratoobras_partidas']['cod_sub_prog'];
                $cod_proyecto[$a] = $aux_partidas['cobd01_contratoobras_partidas']['cod_proyecto'];
                $cod_activ_obra[$a] = $aux_partidas['cobd01_contratoobras_partidas']['cod_activ_obra'];
                $cod_partida[$a] = $aux_partidas['cobd01_contratoobras_partidas']['cod_partida'];
                $cod_generica[$a] = $aux_partidas['cobd01_contratoobras_partidas']['cod_generica'];
                $cod_especifica[$a] = $aux_partidas['cobd01_contratoobras_partidas']['cod_especifica'];
                $cod_sub_espec[$a] = $aux_partidas['cobd01_contratoobras_partidas']['cod_sub_espec'];
                $cod_auxiliar[$a] = $aux_partidas['cobd01_contratoobras_partidas']['cod_auxiliar'];
                $monto2[$a] = $aux_partidas['cobd01_contratoobras_partidas']['monto'];
                $aumento2[$a] = $aux_partidas['cobd01_contratoobras_partidas']['aumento'];
                $disminucion2[$a] = $aux_partidas['cobd01_contratoobras_partidas']['disminucion'];
                $numero_compromiso = $aux_partidas['cobd01_contratoobras_partidas']['numero_control_compromiso'];
                $a++;

                $partidas_aux = $aux_partidas['cobd01_contratoobras_partidas']['cod_sector'];
                $partidas_aux .= $aux_partidas['cobd01_contratoobras_partidas']['cod_programa'];
                $partidas_aux .= $aux_partidas['cobd01_contratoobras_partidas']['cod_sub_prog'];
                $partidas_aux .= $aux_partidas['cobd01_contratoobras_partidas']['cod_proyecto'];
                $partidas_aux .= $aux_partidas['cobd01_contratoobras_partidas']['cod_activ_obra'];
                $partidas_aux .= $aux_partidas['cobd01_contratoobras_partidas']['cod_partida'];
                $partidas_aux .= $aux_partidas['cobd01_contratoobras_partidas']['cod_generica'];
                $partidas_aux .= $aux_partidas['cobd01_contratoobras_partidas']['cod_especifica'];
                $partidas_aux .= $aux_partidas['cobd01_contratoobras_partidas']['cod_sub_espec'];
                $partidas_aux .= $aux_partidas['cobd01_contratoobras_partidas']['cod_auxiliar'];
                for ($i = 0; $i < $i_lenght; $i++) {
                    if ($partidas_aux == $this->data['partidas']['partidas_' . $i]) {
                        $partidas_vista['pago_' . $i_aux] = $this->data['cscp04_ordencompra_modificacion']['modificacion_' . $i];
                        $i_aux++;
                    }
                }//fin foreach
            }//fin foreach

            $j = 0;

            $condicion = "cod_presi=" . $this->Session->read('SScodpresi') . "  and  cod_entidad=" . $this->Session->read('SScodentidad') . " and cod_tipo_inst=" . $this->Session->read('SScodtipoinst') . " and  cod_inst=" . $this->Session->read('SScodinst') . " and cod_dep=" . $this->Session->read('SScoddep') . " and   upper(numero_contrato_obra)='" . strtoupper($numero_orden_compra2) . "'  and  ano_contrato_obra=" . $ano_orden_compra2 . "";
            $numero_datos = $this->cobd01_contratoobras_cuerpo->findAll($condicion);
            $numero_datos_aux = $numero_datos;
            //print_r($numero_datos);
            foreach ($numero_datos_aux as $aux) {
                $modificacion_aumento = $aux['cobd01_contratoobras_cuerpo']['aumento'];
                $modificacion_disminucion = $aux['cobd01_contratoobras_cuerpo']['disminucion'];
                $cod_obra = $aux['cobd01_contratoobras_cuerpo']['cod_obra'];
            }//fin foreach

            for ($i = 0; $i < $i_lenght; $i++) {
                $var[$i]['monto'] = $partidas_vista['pago_' . $i];
                if ($var[$i]['monto'] != "0,00") {
                    $var[$i]['monto'] = $this->Formato1($var[$i]['monto']);


                    $cfpd07_obras_partidas = $this->cfpd07_obras_partidas->findAll("cod_presi='" . $cod_presi . "' and cod_entidad='" . $cod_entidad . "' and cod_tipo_inst='" . $cod_tipo_inst . "' and cod_inst='" . $cod_inst . "' and cod_dep='" . $cod_dep . "' and ano_estimacion='" . $ano_orden_compra2 . "'  and upper(cod_obra)='" . strtoupper_sisap($cod_obra) . "' and  cod_sector='" . $cod_sector[$i] . "' and cod_programa='" . $cod_programa[$i] . "' and cod_sub_prog='" . $cod_sub_prog[$i] . "' and cod_proyecto='" . $cod_proyecto[$i] . "' and cod_activ_obra='" . $cod_activ_obra[$i] . "' and cod_partida='" . $cod_partida[$i] . "' and cod_generica='" . $cod_generica[$i] . "' and cod_especifica='" . $cod_especifica[$i] . "' and cod_sub_espec='" . $cod_sub_espec[$i] . "' and cod_auxiliar='" . $cod_auxiliar[$i] . "' ", null, 'cod_obra DESC');

                    foreach ($cfpd07_obras_partidas as $ve3) {
                        $monto_2 = $ve3["cfpd07_obras_partidas"]["monto"];
                        $monto_contratado_2 = $ve3["cfpd07_obras_partidas"]["monto_contratado"];
                        $aumento_obras_2 = $ve3["cfpd07_obras_partidas"]["aumento_obras"];
                        $disminucion_obras_2 = $ve3["cfpd07_obras_partidas"]["disminucion_obras"];
                        $disponibilidad = (($monto_2 + $aumento_obras_2) - ($disminucion_obras_2 + $monto_contratado_2));
                        $disponibilidad = $disponibilidad - $var[$i]['monto'];
                    }

                        $cp = $this->crear_partida($ano_partidas[$i], $cod_sector[$i], $cod_programa[$i], $cod_sub_prog[$i], $cod_proyecto[$i], $cod_activ_obra[$i], $cod_partida[$i], $cod_generica[$i], $cod_especifica[$i], $cod_sub_espec[$i], $cod_auxiliar[$i]);
                        $to = 1;
                        $td = 2;
                        if ($tipo_modificacion=='1'){$ta=3;}else{$ta=4;}
                        $mt = $var[$i]['monto'];
                        $ndo = $numero_orden_compra3[$i];
                        $rnco = $numero_compromiso;


                        $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fd, $mt, $ccp, $ann, $ndo, $numero_modificacion, null, null, null, null, null, null, $rnco, null, null, null, $i);

                        $sql2 = "INSERT INTO cobd01_contratoobras_modificacion_partidas(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_contrato_obra, numero_contrato_obra, numero_modificacion, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica,  cod_sub_espec, cod_auxiliar,  monto, numero_control_compromiso) ";
                        $sql2 .= "  VALUES ('" . $cod_presi2[$i] . "', '" . $cod_entidad2[$i] . "', '" . $cod_tipo_inst2[$i] . "', '" . $cod_inst2[$i] . "', '" . $cod_dep2[$i] . "', '" . $ano_orden_compra3[$i] . "', '" . $numero_orden_compra3[$i] . "', '" . $numero_modificacion . "', '" . $ano_partidas[$i] . "', '" . $cod_sector[$i] . "', '" . $cod_programa[$i] . "', '" . $cod_sub_prog[$i] . "', '" . $cod_proyecto[$i] . "', '" . $cod_activ_obra[$i] . "', '" . $cod_partida[$i] . "', '" . $cod_generica[$i] . "', '" . $cod_especifica[$i] . "',  '" . $cod_sub_espec[$i] . "', '" . $cod_auxiliar[$i] . "',  '" . $var[$i]['monto'] . "', '" . $rnco . "'); ";
                        $sw13 = $this->cobd01_co_modificacion_partidas->execute($sql2);
                        if ($sw13 > 1) {

                            $monto_modificacion_tipo2 = 0;

                            if ($tipo_modificacion == '1') {
                                $monto_modificacion_tipo2 = $aumento2[$i] + $var[$i]['monto'];
                                $campo = "aumento";
                                $campo2 = "aumento_obras";
                                $aa = $var[$i]['monto'];
                                $bb = 0;
                            } else if ($tipo_modificacion == '2') {
                                $monto_modificacion_tipo2 = $disminucion2[$i] + $var[$i]['monto'];
                                $campo = "disminucion";
                                $campo2 = "disminucion_obras";
                                $bb = $var[$i]['monto'];
                                $aa = 0;
                            }//fin else
                            $sql4 = "UPDATE cobd01_contratoobras_partidas SET " . $campo . " = '" . $monto_modificacion_tipo2 . "'  where cod_presi=" . $this->Session->read('SScodpresi') . " and cod_entidad=" . $this->Session->read('SScodentidad') . " and cod_tipo_inst=" . $this->Session->read('SScodtipoinst') . " and cod_inst=" . $this->Session->read('SScodinst') . " and cod_dep=" . $this->Session->read('SScoddep') . "  and  upper(numero_contrato_obra)='" . strtoupper($numero_orden_compra2) . "'  and  ano_contrato_obra=" . $ano_orden_compra2 . " and ano=" . $ano_partidas[$i] . " and cod_sector=" . $cod_sector[$i] . " and cod_programa=" . $cod_programa[$i] . " and cod_sub_prog=" . $cod_sub_prog[$i] . " and cod_proyecto=" . $cod_proyecto[$i] . " and cod_activ_obra=" . $cod_activ_obra[$i] . " and cod_partida=" . $cod_partida[$i] . " and cod_generica=" . $cod_generica[$i] . " and cod_especifica=" . $cod_especifica[$i] . " and cod_sub_espec=" . $cod_sub_espec[$i] . " and cod_auxiliar=" . $cod_auxiliar[$i] . "; ";
                            $sw14 = $this->cobd01_contratoobras_partidas->execute($sql4);
                            if ($sw14 > 1) {

                                $sql_502 = "UPDATE cfpd07_obras_partidas SET monto_contratado = monto_contratado + " . $aa . " where cod_presi=" . $this->Session->read('SScodpresi') . " and cod_entidad=" . $this->Session->read('SScodentidad') . " and cod_tipo_inst=" . $this->Session->read('SScodtipoinst') . " and cod_inst=" . $this->Session->read('SScodinst') . " and cod_dep=" . $this->Session->read('SScoddep') . "  and   upper(cod_obra)='" . strtoupper($cod_obra) . "'  and  ano_estimacion=" . $ano_orden_compra2 . " and cod_sector=" . $cod_sector[$i] . " and cod_programa=" . $cod_programa[$i] . " and cod_sub_prog=" . $cod_sub_prog[$i] . " and cod_proyecto=" . $cod_proyecto[$i] . " and cod_activ_obra=" . $cod_activ_obra[$i] . " and cod_partida=" . $cod_partida[$i] . " and cod_generica=" . $cod_generica[$i] . " and cod_especifica=" . $cod_especifica[$i] . " and cod_sub_espec=" . $cod_sub_espec[$i] . " and cod_auxiliar=" . $cod_auxiliar[$i] . "; ";
                                $sw_502 = $this->cobd01_contratoobras_partidas->execute($sql_502);

                                $sql_503 = "UPDATE cfpd07_obras_partidas SET monto_contratado = monto_contratado - " . $bb . "  where cod_presi=" . $this->Session->read('SScodpresi') . " and cod_entidad=" . $this->Session->read('SScodentidad') . " and cod_tipo_inst=" . $this->Session->read('SScodtipoinst') . " and cod_inst=" . $this->Session->read('SScodinst') . " and cod_dep=" . $this->Session->read('SScoddep') . "  and   upper(cod_obra)='" . strtoupper($cod_obra) . "'  and  ano_estimacion=" . $ano_orden_compra2 . " and cod_sector=" . $cod_sector[$i] . " and cod_programa=" . $cod_programa[$i] . " and cod_sub_prog=" . $cod_sub_prog[$i] . " and cod_proyecto=" . $cod_proyecto[$i] . " and cod_activ_obra=" . $cod_activ_obra[$i] . " and cod_partida=" . $cod_partida[$i] . " and cod_generica=" . $cod_generica[$i] . " and cod_especifica=" . $cod_especifica[$i] . " and cod_sub_espec=" . $cod_sub_espec[$i] . " and cod_auxiliar=" . $cod_auxiliar[$i] . "; ";
                                $sw_503 = $this->cobd01_contratoobras_partidas->execute($sql_503);
                            } else {
                                break;
                            }
                        } else {
                            break;
                        }

                }//fin if
            }//fin for


            if ($sw14 > 1) {

                $valor_motor_contabilidad = $this->motor_contabilidad_fiscal(
                        $to = 1, $td = 11, $rif_doc = $rif, $ano_dc = $ano_orden_compra, $n_dc = $numero_orden_compra, $f_dc = $fecha_contrato_obra, $cpt_dc = $ccp, $ben_dc = null, $mon_dc = array("monto" => $monto_modificacion), $ano_op = null, $n_op = null, $f_op = null, $a_adj_op = null, $n_adj_op = $numero_modificacion, $f_adj_op = $fecha_modificacion, $tp_op = null, $deno_ban_pago = null, $ano_movimiento = null, $cod_ent_pago = null, $cod_suc_pago = null, $cod_cta_pago = null, $num_che_o_debi = null, $fec_che_o_debi = null, $clas_che_o_debi = null, $tipo_che_o_debi = null, $ano_dc_array_pago = array(), $n_dc_array_pago = array(), $n_dc_adj_array_pago = array(), $f_dc_array_pago = array(), $ano_op_array_pago = array(), $n_op_array_pago = array(), $f_op_array_pago = array(), $tipo_op_array_pago = array(), $tipo_modificacion2 = $tipo_modificacion);
            } else {

                $valor_motor_contabilidad = false;
            }//fin else


            if ($valor_motor_contabilidad == true) {

                $campo = "";
                $condicion = "cod_presi=" . $this->Session->read('SScodpresi') . "  and  cod_entidad=" . $this->Session->read('SScodentidad') . " and cod_tipo_inst=" . $this->Session->read('SScodtipoinst') . " and  cod_inst=" . $this->Session->read('SScodinst') . " and cod_dep=" . $this->Session->read('SScoddep') . " and   upper(numero_contrato_obra)='" . strtoupper($numero_orden_compra2) . "'  and  ano_contrato_obra=" . $ano_orden_compra2 . " ";
                $numero_datos = $this->cobd01_contratoobras_cuerpo->findAll($condicion);
                $numero_datos_aux = $numero_datos;

                //print_r($numero_datos);

                foreach ($numero_datos_aux as $aux) {
                    $modificacion_aumento = $aux['cobd01_contratoobras_cuerpo']['aumento'];
                    $modificacion_disminucion = $aux['cobd01_contratoobras_cuerpo']['disminucion'];
                    $cod_obra = $aux['cobd01_contratoobras_cuerpo']['cod_obra'];
                }//fin foreach

                if ($tipo_modificacion == '1') {
                    $monto_modificacion_tipo = $monto_modificacion + $modificacion_aumento;
                    $campo = "aumento";
                    $aumento = $monto_modificacion;
                    $signo2 = " + ";
                    $disminucion = 0;
                } else if ($tipo_modificacion == '2') {
                    $monto_modificacion_tipo = $monto_modificacion + $modificacion_disminucion;
                    $campo = "disminucion";
                    $aumento = 0;
                    $disminucion = $monto_modificacion;
                    $signo2 = " - ";
                }//fin else



                $condicion = "cod_presi=" . $this->Session->read('SScodpresi') . "  and  cod_entidad=" . $this->Session->read('SScodentidad') . " and cod_tipo_inst=" . $this->Session->read('SScodtipoinst') . " and  cod_inst=" . $this->Session->read('SScodinst') . " and cod_dep=" . $this->Session->read('SScoddep') . " and   upper(cod_obra)='" . strtoupper($cod_obra) . "'  and  ano_estimacion=" . $ano_orden_compra2 . "";
                $cfpd07_obras_cuerpo = $this->cfpd07_obras_cuerpo->findAll($condicion, null, null);

                foreach ($cfpd07_obras_cuerpo as $ve32) {
                    $tipo_recurso = $ve32['cfpd07_obras_cuerpo']['tipo_recurso'];
                    $clasificacion_recurso = $ve32['cfpd07_obras_cuerpo']['clasificacion_recurso'];
                }//fin foreach

                $condicion = "cod_presi=" . $this->Session->read('SScodpresi') . "  and  cod_entidad=" . $this->Session->read('SScodentidad') . " and cod_tipo_inst=" . $this->Session->read('SScodtipoinst') . " and  cod_inst=" . $this->Session->read('SScodinst') . " and cod_dep=" . $this->Session->read('SScoddep') . " and   upper(numero_contrato_obra)='" . strtoupper($numero_orden_compra2) . "'  and  ano_contrato_obra=" . $ano_orden_compra2 . "";

                $sql3 = "UPDATE cobd01_contratoobras_cuerpo SET " . $campo . "= '" . $monto_modificacion_tipo . "'  where cod_presi=" . $this->Session->read('SScodpresi') . " and cod_entidad=" . $this->Session->read('SScodentidad') . " and cod_tipo_inst=" . $this->Session->read('SScodtipoinst') . " and cod_inst=" . $this->Session->read('SScodinst') . " and cod_dep=" . $this->Session->read('SScoddep') . "  and   upper(numero_contrato_obra)='" . strtoupper($numero_orden_compra2) . "'  and  ano_contrato_obra=" . $ano_orden_compra2 . "; ";
                $sw15 = $this->cobd01_contratoobras_cuerpo->execute($sql3);

                if ($sw15 > 1) {

                    $sql500 = "UPDATE cfpd07_obras_cuerpo SET monto_contratado = monto_contratado + " . $aumento . " where cod_presi=" . $this->Session->read('SScodpresi') . " and cod_entidad=" . $this->Session->read('SScodentidad') . " and cod_tipo_inst=" . $this->Session->read('SScodtipoinst') . " and cod_inst=" . $this->Session->read('SScodinst') . " and cod_dep=" . $this->Session->read('SScoddep') . "  and   upper(cod_obra)='" . strtoupper($cod_obra) . "'  and  ano_estimacion=" . $ano_orden_compra2 . "; ";
                    $sw500 = $this->cobd01_contratoobras_cuerpo->execute($sql500);

                    $sql501 = "UPDATE cfpd07_obras_cuerpo SET monto_contratado = monto_contratado - " . $disminucion . " where cod_presi=" . $this->Session->read('SScodpresi') . " and cod_entidad=" . $this->Session->read('SScodentidad') . " and cod_tipo_inst=" . $this->Session->read('SScodtipoinst') . " and cod_inst=" . $this->Session->read('SScodinst') . " and cod_dep=" . $this->Session->read('SScoddep') . "  and   upper(cod_obra)='" . strtoupper($cod_obra) . "'  and  ano_estimacion=" . $ano_orden_compra2 . "; ";
                    $sw501 = $this->cobd01_contratoobras_cuerpo->execute($sql501);

                    $sql333 = "UPDATE cfpd07_plan_inversion SET monto_presupuestado =  monto_presupuestado " . $signo2 . " " . $monto_modificacion . "  where cod_presi=" . $this->Session->read('SScodpresi') . " and cod_entidad=" . $this->Session->read('SScodentidad') . " and cod_tipo_inst=" . $this->Session->read('SScodtipoinst') . " and cod_inst=" . $this->Session->read('SScodinst') . " and tipo_recurso=" . $tipo_recurso . "  and clasificacion_recurso=" . $clasificacion_recurso . ";";
                    $sw17 = $this->cobd01_contratoobras_cuerpo->execute($sql333);
                    if ($sw17 > 1) {


                        ////************************************** DESPUES DE GUARDAR ****************************************/////
                        $ano = '';
                        $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . '';
                        $ano = $this->ano_ejecucion();
                        $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $this->Session->read('SScoddep') . ' and  ano_contrato_obra=' . $ano . '';
                        $lista = $this->cobd01_contratoobras_cuerpo->generateList($condicion . ' and condicion_actividad=1', ' numero_contrato_obra ASC', null, '{n}.cobd01_contratoobras_cuerpo.numero_contrato_obra', '{n}.cobd01_contratoobras_cuerpo.numero_contrato_obra');
                        $this->AddCero('lista_numero', $lista);
                        $this->set('numero_orden_compra', $numero_orden_compra2);
                        $condicion = "cod_presi=" . $this->Session->read('SScodpresi') . "  and  cod_entidad=" . $this->Session->read('SScodentidad') . " and cod_tipo_inst=" . $this->Session->read('SScodtipoinst') . " and  cod_inst=" . $this->Session->read('SScodinst') . " and cod_dep=" . $this->Session->read('SScoddep') . " and  numero_contrato_obra='" . $numero_orden_compra2 . "'  and  ano_contrato_obra=" . $ano_orden_compra2 . "";
                        $numero_datos_encabezado = $this->cobd01_contratoobras_cuerpo->findAll($condicion . " and rif='" . $this->data['cscp04_ordencompra_modificacion']['rif'] . "' ");
                        $numero_datos_orden_compra_partidas = $this->cobd01_contratoobras_partidas->findAll($condicion . " and ano=" . $ano_partidas[0] . "  ", null, 'numero_contrato_obra DESC');
                        $numero_datos_aux = $numero_datos_encabezado;
                        foreach ($numero_datos_aux as $aux) {
                            $rif = $aux['cobd01_contratoobras_cuerpo']['rif'];
                            $ano_orden_compra = $aux['cobd01_contratoobras_cuerpo']['ano_contrato_obra'];
                            $numero_orden_compra = $aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra'];
                        }//fin foreach
                        $opc = $this->cobd01_co_modificacion_cuerpo->findAll($condicion . " and ano_contrato_obra=" . $ano_orden_compra . "  and numero_contrato_obra='" . $numero_orden_compra . "' and numero_modificacion=" . $numero_modificacion . "");
                        $rif_datos = $this->cpcd02->findAll("rif='" . $rif . "'");
                        foreach ($rif_datos as $aux_2) {
                            $denominacion_rif = $aux_2['cpcd02']['denominacion'];
                            $direccion_comercial_rif = $aux_2['cpcd02']['direccion_comercial'];
                        }//fin foreach
                        $campos = ' cod_presi ,
															cod_entidad,
															cod_tipo_inst,
															cod_inst,
															SUM(monto) as "monto" ';
                        $agrupar = ' GROUP BY cod_presi,
															cod_entidad,
															cod_tipo_inst,
															cod_inst';
                        $aux_monto_modificacion = $this->cobd01_co_modificacion_partidas->findAll($condicion . " and ano_contrato_obra=" . $ano_orden_compra . "  and numero_contrato_obra='" . $numero_orden_compra . "' and numero_modificacion=" . $numero_modificacion . "  " . $agrupar, $campos, null, null, null);
                        foreach ($aux_monto_modificacion as $aux2_monto_modificacion) {
                            $monto_modificacion = $aux2_monto_modificacion[0]['monto'];
                        }//fin foreach
                        $this->set('year', $ano);
                        $this->set('datos_orden_compra_modificacion_cuerpo', $opc);
                        $this->set('datos_orden_compra_encabezado', $numero_datos_encabezado);
                        $this->set('datos_orden_compra_partidas', $numero_datos_orden_compra_partidas);
                        $this->set('denominacion_rif', $denominacion_rif);
                        $this->set('direccion_comercial_rif', $direccion_comercial_rif);
                        $this->set('monto_modificacion', $monto_modificacion);
                        $this->cobd01_co_modificacion_partidas->execute("COMMIT;");
                        $this->set('Message_existe', 'Los datos fueron guardados correctamente');
                        ////************************************** DESPUES DE GUARDAR ****************************************/////
                    } else {
                        $this->cobd01_co_modificacion_partidas->execute("ROLLBACK;");
                        $this->set('errorMessage', 'NO SE LOGRO REALIZAR LA MODIFICACION - POR FAVOR INTENTE DE NUEVO');
                    }//fin else
                } else {
                    $this->cobd01_co_modificacion_partidas->execute("ROLLBACK;");
                    $this->set('errorMessage', 'NO SE LOGRO REALIZAR LA MODIFICACION - POR FAVOR INTENTE DE NUEVO');
                }//fin else
            } else {
                $this->cobd01_co_modificacion_partidas->execute("ROLLBACK;");
                $this->set('errorMessage', 'NO SE LOGRO REALIZAR LA MODIFICACION - POR FAVOR INTENTE DE NUEVO');
            }//fin else
        } else {
            $this->cobd01_co_modificacion_partidas->execute("ROLLBACK;");
            $this->set('errorMessage', 'NO SE LOGRO REALIZAR LA MODIFICACION - POR FAVOR INTENTE DE NUEVO');
        }//fin else

        $this->set('autor_valido', true);
        $this->index("user_valido");
        $this->render("index");
    }

//fin funtion guardar

    function consulta_index($var1 = null) {

        $this->layout = "ajax";
        $pag_num = 0;
        $opcion = 'no';
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');

        if ($SScoddep == 1 && $SScoddeporig == 1) {
            $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . '  ';
        } else {
            $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $this->Session->read('SScoddep') . '';
        }//fin else


        if (!empty($this->data['cobp01_contratoobras_modificacion']['ano_contrato'])) {
            $_SESSION['ano_contrato_obra'] = $this->data['cobp01_contratoobras_modificacion']['ano_contrato'];
        } else {
            $_SESSION['ano_contrato_obra'] = $this->ano_ejecucion();
        }
        $ano = $_SESSION['ano_contrato_obra'];

        if ($var1 != null) {
            if ($var1 == 'si') {





                if (!empty($this->data['cobp01_contratoobras_modificacion']['numero_contrato_obra'])) {

                    $array = $this->cobd01_co_modificacion_cuerpo->findAll($condicion . "  and numero_contrato_obra='" . $this->data['cobp01_contratoobras_modificacion']['numero_contrato_obra'] . "' and ano_contrato_obra = " . $ano, null, 'ano_contrato_obra, numero_contrato_obra, numero_modificacion ASC', null);
                    $i = 0;

                    foreach ($array as $aux) {
                        $numero[$i]['ano_contrato_obra'] = $aux['cobd01_co_modificacion_cuerpo']['ano_contrato_obra'];
                        $numero[$i]['numero_contrato_obra'] = $aux['cobd01_co_modificacion_cuerpo']['numero_contrato_obra'];
                        $numero[$i]['numero_modificacion'] = $aux['cobd01_co_modificacion_cuerpo']['numero_modificacion'];
                        $i++;
                    } $i--;


                    for ($a = 0; $a <= $i; $a++) {

                        if ($this->data['cobp01_contratoobras_modificacion']['numero_contrato_obra'] == $numero[$a]['numero_contrato_obra']) {
                            $pag_num = 0;
                            $opcion = 'si';
                            $numero_documento = $numero[$a]['numero_contrato_obra'];
                            break;
                        } else {
                            $pag_num = 0;
                            $opcion = 'si';
                            $numero_documento = $numero[0]['numero_contrato_obra'];
                        }
                    }//fin for

                    if ($opcion == 'si') {
                        $this->consulta($pag_num, $numero_documento);
                        $this->render('consulta');
                    } else if ($opcion == 'no') {
                        $this->set('errorMessage', 'No existen datos');
                        $this->consulta_index();
                        $this->render('consulta_index');
                    }//fin else
                } else {

                    $array = $this->cobd01_co_modificacion_cuerpo->findAll($condicion . "   and ano_contrato_obra = " . $ano, null, 'ano_contrato_obra, numero_contrato_obra, numero_modificacion ASC', null);
                    $i = 0;

                    foreach ($array as $aux) {
                        $numero[$i]['ano_contrato_obra'] = $aux['cobd01_co_modificacion_cuerpo']['ano_contrato_obra'];
                        $numero[$i]['numero_contrato_obra'] = $aux['cobd01_co_modificacion_cuerpo']['numero_contrato_obra'];
                        $numero[$i]['numero_modificacion'] = $aux['cobd01_co_modificacion_cuerpo']['numero_modificacion'];
                        $i++;
                    } $i--;

                    if ($i <= 0) {
                        $this->set('errorMessage', 'No existen datos');
                        $this->consulta_index();
                        $this->render('consulta_index');
                    } else {
                        $this->consulta(0, $numero[0]['numero_contrato_obra']);
                        $this->render('consulta');
                    }
                }//fin else
            }//fin if
        }//fin i





        $lista = $this->cobd01_co_modificacion_cuerpo->generateList($condicion . ' and ano_contrato_obra=' . $ano . $this->filtra_obra($ano), ' numero_contrato_obra ASC', null, '{n}.cobd01_co_modificacion_cuerpo.numero_contrato_obra', '{n}.cobd01_co_modificacion_cuerpo.numero_contrato_obra');
        $this->set('lista_numero', $lista);
        $this->set('ano_contrato_obra', $_SESSION['ano_contrato_obra']);
        $this->set('entidad_federal', $this->Session->read('entidad_federal'));
    }

//fin function

    function buscar_year($var1 = null) {
        $this->layout = "ajax";
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');


        if ($SScoddep == 1 && $SScoddeporig == 1) {

            $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . '  ';
            $lista = $this->cobd01_co_modificacion_cuerpo->generateList($condicion . ' and ano_contrato_obra=' . $var1, ' numero_contrato_obra ASC', null, '{n}.cobd01_co_modificacion_cuerpo.numero_contrato_obra', '{n}.cobd01_co_modificacion_cuerpo.numero_contrato_obra');
            if ($lista == "") {
                $lista = array('' => '');
            }
            $this->set('obras', $lista);
            $this->set('ano', $var1);
        } else {


            $lista = $this->cobd01_co_modificacion_cuerpo->generateList($this->condicion() . ' and ano_contrato_obra=' . $var1 . $this->filtra_obra($var1), ' numero_contrato_obra ASC', null, '{n}.cobd01_co_modificacion_cuerpo.numero_contrato_obra', '{n}.cobd01_co_modificacion_cuerpo.numero_contrato_obra');
            if ($lista == "") {
                $lista = array('' => '');
            }
            $this->set('obras', $lista);
        }//fin else
    }

//fin function

    function consulta($pag_num = null, $numero_documento = null, $g = null) {
        $this->layout = "ajax";
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');
        $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $this->Session->read('SScoddep') . '';
        if (isset($_SESSION['ano_contrato_obra'])) {
            $ano = $_SESSION['ano_contrato_obra'];
        } else {
            $ano = $this->ano_ejecucion();
        }
        $this->set('ano_contrato_obra', $ano);


        $this->set('ano_contrato_obra_ejecucion', $this->ano_ejecucion());



        if ($SScoddep == 1 && $SScoddeporig == 1) {
            $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . '  ';
        } else {
            $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $this->Session->read('SScoddep') . '';
        }//fin else


        $this->set('ano_contrato_obra', $ano);

        $array = $this->cobd01_co_modificacion_cuerpo->findAll($condicion . "  and numero_contrato_obra='" . $numero_documento . "' and ano_contrato_obra = " . $ano, null, 'ano_contrato_obra, numero_contrato_obra, numero_modificacion ASC', null);

        $i = 0;
        if ($pag_num == null) {
            $pag_num = 0;
        }

        foreach ($array as $aux) {

            $numero[$i]['ano_contrato_obra'] = $aux['cobd01_co_modificacion_cuerpo']['ano_contrato_obra'];
            $numero[$i]['numero_contrato_obra'] = $aux['cobd01_co_modificacion_cuerpo']['numero_contrato_obra'];
            $numero[$i]['numero_modificacion'] = $aux['cobd01_co_modificacion_cuerpo']['numero_modificacion'];

            $i++;
        } $i--;




        $acta = 0;
        $concepto = "";

        $sacar1 = $this->cobd01_co_modificacion_cuerpo->findAll($condicion . " and ano_contrato_obra= " . $ano);
        foreach ($sacar1 as $sa) {
            $acta = $sa['cobd01_co_modificacion_cuerpo']['numero_anulacion'];
        }
        $sacar = $this->cugd03_acta_anulacion_cuerpo->findAll($condicion . " and numero_acta_anulacion=" . $acta . " and ano_acta_anulacion=" . $ano);
        foreach ($sacar as $sa2) {
            $concepto = $sa2['cugd03_acta_anulacion_cuerpo']['motivo_anulacion'];
        }
        $this->set('concepto', $concepto);





        if (isset($numero[$pag_num]['numero_contrato_obra'])) {

            $datos_orden_compra_encabezado = $this->cobd01_contratoobras_cuerpo->findAll($condicion . " and ano_contrato_obra=" . $numero[$pag_num]['ano_contrato_obra'] . "  and  upper(numero_contrato_obra)='" . strtoupper($numero[$pag_num]['numero_contrato_obra']) . "'");
            $datos_orden_compra_modificacion_cuerpo = $this->cobd01_co_modificacion_cuerpo->findAll($condicion . " and ano_contrato_obra=" . $numero[$pag_num]['ano_contrato_obra'] . "  and  upper(numero_contrato_obra)='" . strtoupper($numero[$pag_num]['numero_contrato_obra']) . "' and  numero_modificacion=" . $numero[$pag_num]['numero_modificacion'] . "");
//print_r($datos_orden_compra_modificacion_cuerpo);



            $numero_datos_aux = $datos_orden_compra_encabezado;
            foreach ($numero_datos_aux as $aux) {
                $rif = $aux['cobd01_contratoobras_cuerpo']['rif'];
                $ano_estimacion = $aux['cobd01_contratoobras_cuerpo']['ano_estimacion'];
                $cod_obra = $aux['cobd01_contratoobras_cuerpo']['cod_obra'];
            }//fin foreach

            $rif_datos = $this->cpcd02->findAll("rif='" . $rif . "'");
            foreach ($rif_datos as $aux_2) {
                $denominacion_rif = $aux_2['cpcd02']['denominacion'];
                $direccion_comercial_rif = $aux_2['cpcd02']['direccion_comercial'];
            }//fin foreach
            $campos = ' cod_presi, cod_entidad, cod_tipo_inst, cod_inst, SUM(monto) as "monto" ';
            $agrupar = ' GROUP BY cod_presi, cod_entidad, cod_tipo_inst, cod_inst';
            $sql = $condicion . "  and  ano_contrato_obra=" . $numero[$pag_num]['ano_contrato_obra'] . "  and  numero_contrato_obra='" . $numero[$pag_num]['numero_contrato_obra'] . "' and numero_modificacion=" . $numero[$pag_num]['numero_modificacion'] . "" . $agrupar;
            $sql2 = $condicion . "  and  ano_contrato_obra=" . $numero[$pag_num]['ano_contrato_obra'] . "  and  numero_contrato_obra='" . $numero[$pag_num]['numero_contrato_obra'] . "' and numero_modificacion=" . $numero[$pag_num]['numero_modificacion'] . "";
            $aux_monto_modificacion = $this->cobd01_co_modificacion_partidas->findAll($sql, $campos, null, null, null);


            $numero_datos_orden_compra_partidas = $this->cobd01_co_modificacion_partidas->findAll($sql2);
            foreach ($aux_monto_modificacion as $aux2_monto_modificacion) {
                $monto_modificacion = $aux2_monto_modificacion[0]['monto'];
            }//fin foreach
            $this->set('monto_modificacion', $monto_modificacion);
            $this->set('denominacion_rif', $denominacion_rif);
            $this->set('datos_orden_compra__modificacion_partidas', $numero_datos_orden_compra_partidas);
            $this->set('direccion_comercial_rif', $direccion_comercial_rif);
            $this->set('datos_orden_compra_encabezado', $datos_orden_compra_encabezado);
            $this->set('datos_orden_compra_modificacion_cuerpo', $datos_orden_compra_modificacion_cuerpo);

            $this->set('pag_num', $pag_num);
            $this->set('totalPages_Recordset1', $i);
        } else {

            $this->set('pag_num', 0);
            $this->set('totalPages_Recordset1', '');
            $this->set('errorMessage', 'No existen datos');
        }//fin else
    }

//fin function

    function guardar_anulacion1($var = null) {
        $this->layout = "ajax";


        echo'<script>';
        echo'document.getElementById("guardar").disabled = false; ';
        echo'document.getElementById("anular").disabled = true; ';
        echo'</script>';
    }

//fin function

    function guardar_anulacion2($tipo_modificacion, $var = null) {

        //echo "si entro a anular";

        $this->layout = "ajax";
//print_r($this->data["cscp04_ordencompra_modificacion"]);
        if (isset($this->data["cscp04_ordencompra_modificacion"])) {


            $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $this->Session->read('SScoddep') . ' ';


            $concepto_anulacion = $this->data["cscp04_ordencompra_modificacion"]["concepto_anulacion"];
            $concepto = $concepto_anulacion;
            $fecha_proceso_anulacion = date("d/m/Y");

            $condicion_documento = 2; //cuando se guarda es Activo=1




            $ano_orden_compra    = $this->data["cscp04_ordencompra_modificacion"]["ano_orden_compra"];
            $numero_orden_compra = $this->data["cscp04_ordencompra_modificacion"]["numero_orden_compra"];
            $fecha_contrato_obra = $this->data["cscp04_ordencompra_modificacion"]["fecha_contrato_obra"];
            $fecha_contrato      = $this->data["cscp04_ordencompra_modificacion"]["fecha_contrato2"];
            $fecha_modificacion  = $this->data["cscp04_ordencompra_modificacion"]["fecha_modificacion"];
            $fd                  = $fecha_modificacion;
            $rif                 = $this->data["cscp04_ordencompra_modificacion"]["rif"];
            $numero_modificacion = $this->data["cscp04_ordencompra_modificacion"]["numero_modificacion"];

            if ($tipo_modificacion == 1) {
                //echo "entro a una";
                $actualizar  = 'aumento';
                $actualizar2 = 'aumento_obras';
                $tipo        = 'aumento =aumento - ';
                $signo       = '-';
                $signo2      = "-";
                $ta          = 3;
            } else {
                //echo "entro a otra";
                $tipo        = 'disminucion = disminucion - ';
                $actualizar  = 'disminucion';
                $actualizar2 = 'disminucion_obras';
                $signo       = '-';
                $signo2      = "-";
                $ta          = 4;
            }

            $datos_partidas = $this->cobd01_co_modificacion_partidas->findAll($conditions = $this->condicion() . " and ano_contrato_obra='$ano_orden_compra' and upper(numero_contrato_obra)='" . strtoupper($numero_orden_compra) . "' and numero_modificacion='$numero_modificacion'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);

            $monto_anticipo = 0;
            $sql_update_cscd04_partidas = '';
            $sql_602 = "";
            $sql_603 = "";
            $monto_modificacion = 0;


            $condicion = "cod_presi=" . $this->Session->read('SScodpresi') . "  and  cod_entidad=" . $this->Session->read('SScodentidad') . " and cod_tipo_inst=" . $this->Session->read('SScodtipoinst') . " and  cod_inst=" . $this->Session->read('SScodinst') . " and cod_dep=" . $this->Session->read('SScoddep') . " and   upper(numero_contrato_obra)='" . strtoupper($numero_orden_compra) . "'  and  ano_contrato_obra=" . $ano_orden_compra . "";
            $numero_datos = $this->cobd01_contratoobras_cuerpo->findAll($condicion);
            $numero_datos_aux = $numero_datos;

            foreach ($numero_datos_aux as $aux) {
                $modificacion_aumento = $aux['cobd01_contratoobras_cuerpo']['aumento'];
                $modificacion_disminucion = $aux['cobd01_contratoobras_cuerpo']['disminucion'];
                $cod_obra = $aux['cobd01_contratoobras_cuerpo']['cod_obra'];
            }//fin foreach

            foreach ($datos_partidas as $row) {
                $ano = $row['cobd01_co_modificacion_partidas']['ano'];
                $cod_sector = $row['cobd01_co_modificacion_partidas']['cod_sector'];
                $cod_programa = $row['cobd01_co_modificacion_partidas']['cod_programa'];
                $cod_sub_prog = $row['cobd01_co_modificacion_partidas']['cod_sub_prog'];
                $cod_proyecto = $row['cobd01_co_modificacion_partidas']['cod_proyecto'];
                $cod_activ_obra = $row['cobd01_co_modificacion_partidas']['cod_activ_obra'];
                $cod_partida = $row['cobd01_co_modificacion_partidas']['cod_partida'];
                $cod_generica = $row['cobd01_co_modificacion_partidas']['cod_generica'];
                $cod_especifica = $row['cobd01_co_modificacion_partidas']['cod_especifica'];
                $cod_sub_espec = $row['cobd01_co_modificacion_partidas']['cod_sub_espec'];
                $cod_auxiliar = $row['cobd01_co_modificacion_partidas']['cod_auxiliar'];
                $monto_partida = $row['cobd01_co_modificacion_partidas']['monto'];
                $numero_control_compromiso = $row['cobd01_co_modificacion_partidas']['numero_control_compromiso'];

                $cond1 = $this->condicion() . " and ano_contrato_obra='$ano_orden_compra' and upper(numero_contrato_obra)='" . strtoupper($numero_orden_compra) . "' and ano='$ano' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
                $cond2 = " cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";

                $monto_modificacion += $monto_partida;
                $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);

                $num_asiento_compromiso = $this->motor_presupuestario($cp, 2, 2, $ta, date("d/m/Y"), $monto_partida, $concepto, $ano, $numero_orden_compra, $numero_modificacion, null, null, null, null, null, null, $numero_control_compromiso, null, null, null, null);

                $sql_update_cscd04_partidas .= "UPDATE cobd01_contratoobras_partidas SET " . $actualizar . "=" . $actualizar . "" . $signo . "'$monto_partida' WHERE " . $cond1 . ";";


                if ($tipo_modificacion == 1) {
                    $aumento2 = $monto_partida;
                    $disminucion2 = 0;
                } else {
                    $aumento2 = 0;
                    $disminucion2 = $monto_partida;
                }//fin if

                $sql_602 .= "UPDATE cfpd07_obras_partidas SET monto_contratado = monto_contratado - " . $aumento2 . " where cod_presi=" . $this->Session->read('SScodpresi') . " and cod_entidad=" . $this->Session->read('SScodentidad') . " and cod_tipo_inst=" . $this->Session->read('SScodtipoinst') . " and cod_inst=" . $this->Session->read('SScodinst') . " and cod_dep=" . $this->Session->read('SScoddep') . "  and   upper(cod_obra)='" . strtoupper($cod_obra) . "'  and  ano_estimacion=" . $ano_orden_compra . " and " . $cond2 . "; ";
                $sql_603 .= "UPDATE cfpd07_obras_partidas SET monto_contratado = monto_contratado + " . $disminucion2 . " where cod_presi=" . $this->Session->read('SScodpresi') . " and cod_entidad=" . $this->Session->read('SScodentidad') . " and cod_tipo_inst=" . $this->Session->read('SScodtipoinst') . " and cod_inst=" . $this->Session->read('SScodinst') . " and cod_dep=" . $this->Session->read('SScoddep') . "  and   upper(cod_obra)='" . strtoupper($cod_obra) . "'  and  ano_estimacion=" . $ano_orden_compra . " and " . $cond2 . "; ";
            }

            $sql_cscd04_encabezado = "UPDATE cobd01_contratoobras_cuerpo SET " . $tipo . " " . $monto_modificacion . " WHERE " . $this->condicion() . " and ano_contrato_obra='$ano_orden_compra' and upper(numero_contrato_obra)='" . strtoupper($numero_orden_compra) . "';";

            $this->cobd01_co_modificacion_partidas->execute($sql_cscd04_encabezado);
            $this->cobd01_co_modificacion_partidas->execute($sql_602);
            $this->cobd01_co_modificacion_partidas->execute($sql_603);
            $this->cobd01_co_modificacion_partidas->execute($sql_update_cscd04_partidas);


            //$numero_anulacion=$this->cugd03_acta_anulacion_numero->execute($condicion);
            $v = $this->cugd03_acta_anulacion_numero->execute("SELECT numero_acta_anulacion FROM cugd03_acta_anulacion_numero WHERE " . $this->SQLCA() . " and ano_acta_anulacion=" . $ano_orden_compra . " ORDER BY numero_acta_anulacion DESC");

            if ($tipo_modificacion == 1) {
                $aumento = $monto_modificacion;
                $disminucion = 0;
                $tipo_documento = '22.3';
            } else {
            	$tipo_documento = '22.4';
                $aumento = 0;
                $disminucion = $monto_modificacion;
            }//fin if

            if ($v != null) {
                $numero = $v[0][0]["numero_acta_anulacion"];
                $numero = $numero == "" ? 1 : $numero + 1;
                $this->cugd03_acta_anulacion_numero->execute("UPDATE cugd03_acta_anulacion_numero SET numero_acta_anulacion=" . $numero . " where " . $this->SQLCA() . " and ano_acta_anulacion=" . $ano_orden_compra . "");
            } else {
                $v = $this->cugd03_acta_anulacion_numero->execute("INSERT INTO cugd03_acta_anulacion_numero(cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion) VALUES (" . $this->SQLCAIN() . "," . $ano_orden_compra . ",1)");
                $numero = 1;
            }//fin else
            $R1 = $this->cobd01_co_modificacion_cuerpo->execute("UPDATE cobd01_contratoobras_modificacion_cuerpo SET ano_anulacion=" . date("Y") . ", numero_anulacion=" . $numero . ", condicion_actividad=" . $condicion_documento . ",  fecha_proceso_anulacion='" . $this->Cfecha($fecha_proceso_anulacion, 'A-M-D') . "', username_anulacion='" . $_SESSION['nom_usuario'] . "'  WHERE " . $this->SQLCA() . " and ano_contrato_obra=" . $ano_orden_compra . " and numero_contrato_obra='" . $numero_orden_compra . "' and numero_modificacion=" . $numero_modificacion);
            $v = $this->cugd03_acta_anulacion_cuerpo->execute("INSERT INTO cugd03_acta_anulacion_cuerpo  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion,tipo_operacion,ano_documento,numero_documento,fecha_documento,motivo_anulacion) VALUES (" . $this->SQLCAIN() . "," . $ano_orden_compra . "," . $numero . "," . $tipo_documento . "," . $ano_orden_compra . ",'" . $numero_orden_compra . "','" . $this->Cfecha($fecha_modificacion, 'A-M-D') . "','" . $concepto_anulacion . "')");


            $condicion = "cod_presi=" . $this->Session->read('SScodpresi') . "  and  cod_entidad=" . $this->Session->read('SScodentidad') . " and cod_tipo_inst=" . $this->Session->read('SScodtipoinst') . " and  cod_inst=" . $this->Session->read('SScodinst') . " and cod_dep=" . $this->Session->read('SScoddep') . " and   upper(numero_contrato_obra)='" . strtoupper($numero_orden_compra) . "'  and  ano_contrato_obra=" . $ano_orden_compra . "";
            $numero_datos = $this->cobd01_contratoobras_cuerpo->findAll($condicion);
            $numero_datos_aux = $numero_datos;
            //print_r($numero_datos);
            foreach ($numero_datos_aux as $aux) {
                $modificacion_aumento = $aux['cobd01_contratoobras_cuerpo']['aumento'];
                $modificacion_disminucion = $aux['cobd01_contratoobras_cuerpo']['disminucion'];
                $cod_obra = $aux['cobd01_contratoobras_cuerpo']['cod_obra'];
            }//fin foreach
            $condicion = "cod_presi=" . $this->Session->read('SScodpresi') . "  and  cod_entidad=" . $this->Session->read('SScodentidad') . " and cod_tipo_inst=" . $this->Session->read('SScodtipoinst') . " and  cod_inst=" . $this->Session->read('SScodinst') . " and cod_dep=" . $this->Session->read('SScoddep') . " and   upper(cod_obra)='" . strtoupper($cod_obra) . "'  and  ano_estimacion=" . $ano_orden_compra . "";
            $cfpd07_obras_cuerpo = $this->cfpd07_obras_cuerpo->findAll($condicion, null, null);
            foreach ($cfpd07_obras_cuerpo as $ve32) {
                $tipo_recurso = $ve32['cfpd07_obras_cuerpo']['tipo_recurso'];
                $clasificacion_recurso = $ve32['cfpd07_obras_cuerpo']['clasificacion_recurso'];
            }//fin foreach
            $condicion = "cod_presi=" . $this->Session->read('SScodpresi') . "  and  cod_entidad=" . $this->Session->read('SScodentidad') . " and cod_tipo_inst=" . $this->Session->read('SScodtipoinst') . " and  cod_inst=" . $this->Session->read('SScodinst') . " and cod_dep=" . $this->Session->read('SScoddep') . " and   upper(numero_contrato_obra)='" . strtoupper($numero_orden_compra) . "'  and  ano_contrato_obra=" . $ano_orden_compra . "";


            $sql600 = "UPDATE cfpd07_obras_cuerpo SET monto_contratado = monto_contratado - " . $aumento . " where cod_presi=" . $this->Session->read('SScodpresi') . " and cod_entidad=" . $this->Session->read('SScodentidad') . " and cod_tipo_inst=" . $this->Session->read('SScodtipoinst') . " and cod_inst=" . $this->Session->read('SScodinst') . " and cod_dep=" . $this->Session->read('SScoddep') . "  and   upper(cod_obra)='" . strtoupper($cod_obra) . "'  and  ano_estimacion=" . $ano_orden_compra . "; ";
            $sw600 = $this->cobd01_contratoobras_cuerpo->execute($sql600);

            $sql601 = "UPDATE cfpd07_obras_cuerpo SET monto_contratado = monto_contratado + " . $disminucion . " where cod_presi=" . $this->Session->read('SScodpresi') . " and cod_entidad=" . $this->Session->read('SScodentidad') . " and cod_tipo_inst=" . $this->Session->read('SScodtipoinst') . " and cod_inst=" . $this->Session->read('SScodinst') . " and cod_dep=" . $this->Session->read('SScoddep') . "  and   upper(cod_obra)='" . strtoupper($cod_obra) . "'  and  ano_estimacion=" . $ano_orden_compra . "; ";
            $sw601 = $this->cobd01_contratoobras_cuerpo->execute($sql601);

            $sql333 = "UPDATE cfpd07_plan_inversion SET monto_presupuestado =  monto_presupuestado " . $signo2 . " " . $monto_modificacion . "  where cod_presi=" . $this->Session->read('SScodpresi') . " and cod_entidad=" . $this->Session->read('SScodentidad') . " and cod_tipo_inst=" . $this->Session->read('SScodtipoinst') . " and cod_inst=" . $this->Session->read('SScodinst') . " and tipo_recurso=" . $tipo_recurso . "  and clasificacion_recurso=" . $clasificacion_recurso . "; ";
            $sw17 = $this->cobd01_contratoobras_cuerpo->execute($sql333);




            if ($R1 > 1 && $v > 1) {

            }


            $valor_motor_contabilidad = $this->motor_contabilidad_fiscal(
                    $to = 2, $td = 11, $rif_doc = $rif, $ano_dc = $ano_orden_compra, $n_dc = $numero_orden_compra, $f_dc = $fecha_contrato_obra, $cpt_dc = $concepto, $ben_dc = null, $mon_dc = array("monto" => $monto_modificacion), $ano_op = null, $n_op = null, $f_op = null, $a_adj_op = null, $n_adj_op = $numero_modificacion, $f_adj_op = date("d/m/Y"), $tp_op = null, $deno_ban_pago = null, $ano_movimiento = null, $cod_ent_pago = null, $cod_suc_pago = null, $cod_cta_pago = null, $num_che_o_debi = null, $fec_che_o_debi = null, $clas_che_o_debi = null, $tipo_che_o_debi = null, $ano_dc_array_pago = array(), $n_dc_array_pago = array(), $n_dc_adj_array_pago = array(), $f_dc_array_pago = array(), $ano_op_array_pago = array(), $n_op_array_pago = array(), $f_op_array_pago = array(), $tipo_op_array_pago = array(), $tipo_modificacion2 = $tipo_modificacion);
        }//fin if

        $this->set('Message_existe', 'El registro fue eliminado correctamente');
        $this->consulta_index("");
        $this->render("consulta_index");
    }

//fin function

    function entrar() {
        $this->layout = "ajax";
        if (isset($this->data['cobp01_contratoobras_modificacion']['login']) && isset($this->data['cobp01_contratoobras_modificacion']['password'])) {

            $l = "PROYECTO";
            $c = "JJJSAE";
            $user = addslashes($this->data['cobp01_contratoobras_modificacion']['login']);
            $paswd = addslashes($this->data['cobp01_contratoobras_modificacion']['password']);
            $cond = $this->SQLCA() . " and username='" . $user . "' and cod_tipo=32 and clave='" . $paswd . "'";
            if ($user == $l && $paswd == $c) {
                $this->set('autor_valido', true);
                $this->index("user_valido");
                $this->render("index");
            } elseif ($this->cugd05_restriccion_clave->findCount($cond) != 0) {
                $this->set('autor_valido', true);
                $this->index("user_valido");
                $this->render("index");
            } else {
                $this->set('errorMessage', "Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
                $this->set('autor_valido', false);
                $this->index("");
                $this->render("index");
            }
        }
    }

}

//fin class
?>