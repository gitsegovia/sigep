<?php

class Cfpp97Crea401AutomaticaController extends AppController {

    var $uses = array('ccfd04_cierre_mes', 'cfpd97', 'cnmd05', 'cfpd01_formulacion');
    var $helpers = array('Html', 'Ajax', 'Javascript', 'Sisap', 'Fpdf', 'Form');

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

    function SQLCA_admin($ano = null) {//sql para busqueda de codigos de arranque con y sin año
        $sql_re = "cod_presi=" . $this->verifica_SS(1) . "  and    ";
        $sql_re .= "cod_entidad=" . $this->verifica_SS(2) . "  and  ";
        $sql_re .= "cod_tipo_inst=" . $this->verifica_SS(3) . "  and ";
        $sql_re .= "cod_inst=" . $this->verifica_SS(4) . "  and  ";
        if ($ano != null) {
            if ($this->verifica_SS(5) != 1) {
                $sql_re .= "cod_dep=" . $this->verifica_SS(5) . "  and  ";
            }
            $sql_re .= "ano=" . $ano . "  ";
        } else {
            if ($this->verifica_SS(5) != 1) {
                $sql_re .= "cod_dep=" . $this->verifica_SS(5) . "  ";
            }
        }
        return $sql_re;
    }

//fin funcion SQLCA

    function SQLCA_reque($ano = null) {//sql para busqueda de codigos de arranque con y sin año
        $sql_re = "cod_presi=" . $this->verifica_SS(1) . "  and    ";
        $sql_re .= "cod_entidad=" . $this->verifica_SS(2) . "  and  ";
        $sql_re .= "cod_tipo_inst=" . $this->verifica_SS(3) . "  and ";
        $sql_re .= "cod_inst=" . $this->verifica_SS(4) . "  and  ";
        if ($ano != null) {
            $sql_re .= "ano=" . $ano . "  ";
        } else {

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

    function index() {
        $this->layout = "ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $this->set("cod_dep", $cod_dep);
        $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst;

        $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
        $dato = null;
        foreach ($year as $year) {
            $dato = $year['cfpd01_formulacion']['ano_formular'];
        }
        if (!empty($dato)) {
            $this->Session->write('ANO_EJECUCION', $this->ano_ejecucion());
            $this->Session->write('ANO_FORMULAR', $dato);
            $this->set('year', $dato);
        } else {
            $this->set('year', '');
        }
    }

//fin index

    function ejecutar_proceso() {
        $this->layout = "ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $tn = 0;

        $sql_ano_formulacion = $this->cfpd97->execute("SELECT ano_formular FROM cfpd01_formulacion WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst");
        if (!empty($sql_ano_formulacion)) {
            $ano_formular = $sql_ano_formulacion[0][0]['ano_formular'];
        }

        $sql_ano_ejecucion = $this->cfpd97->execute("SELECT ano_arranque FROM ccfd03_instalacion WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep");
        if (!empty($sql_ano_ejecucion)) {
            $ano_ejecucion = $sql_ano_ejecucion[0][0]['ano_arranque'];
        }

        if ($ano_formular != $ano_ejecucion) {

            $sql_delete_cfpd97 = "DELETE from cfpd97_transacciones WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_=$cod_inst and cod_dep=$cod_dep";
            $this->cfpd97->execute($sql_delete_cfpd97);

            $j = 0;
            $cfpd97_cargos = $this->cfpd97->execute("SELECT * FROM cfpd97 WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep ORDER BY cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo;");
            if (!empty($cfpd97_cargos)) {
                foreach ($cfpd97_cargos as $cargo) {
                    $tipo_nomina = $cargo[0]['cod_tipo_nomina'];
                    $cod_cargo = $cargo[0]['cod_cargo'];
                    $cod_puesto = $cargo[0]['cod_puesto'];
                    $cod_ficha = $cargo[0]['cod_ficha'];
                    $ano = $ano_formular;
                    $sector = $cargo[0]['cod_sector'];
                    $programa = $cargo[0]['cod_programa'];
                    $sub_programa = $cargo[0]['cod_sub_prog'];
                    $proyecto = $cargo[0]['cod_proyecto'];
                    $actividad = $cargo[0]['cod_activ_obra'];
                    $partida = $cargo[0]['cod_partida'];
                    $generica = $cargo[0]['cod_generica'];
                    $especifica = $cargo[0]['cod_especifica'];
                    $sub_especifica = $cargo[0]['cod_sub_espec'];
                    $auxiliar = $cargo[0]['cod_auxiliar'];
                    $sueldo_basico = $cargo[0]['sueldo_basico'];



                    if ($tipo_nomina != $tn) {
                        $clasificacion_personal = $this->cfpd97->execute("SELECT clasificacion_personal, dias_cobro, denominacion FROM cnmd01 WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina;");
                        if (!empty($clasificacion_personal)) {
                            $cla_per = $clasificacion_personal[0][0]['clasificacion_personal'];
                            $dias_cobro = $clasificacion_personal[0][0]['dias_cobro'];
                            $deno_nomi = $clasificacion_personal[0][0]['denominacion'];
                            $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina;
                            $sql_dias_ano = $this->cfpd97->execute("SELECT cfpd97_formula_anual($parametros) as dias_ano;");
                            $dias_ano = $sql_dias_ano[0][0]['dias_ano'];
                            $ct = $es = $ct_ge = $es_ge = $ct_es = $es_es = $ct_pu = $es_pu = $ct_se = $es_se = $ct_in_bo = $es_in_bo = $ct_po = $es_po = $ct_es_po = $es_es_po = $ct_es_pu = $es_es_pu = $ct_po_ge = $es_po_ge = $ct_po_es = $es_po_es = $ct_dia = $es_dia = $ct_dia_es = $es_dia_es = $ct_dia_fe = $es_dia_fe = $ct_dia_ag = $es_dia_ag = $ct_apor = $es_apor = 0;
                        }

                        // BUSCA EL SUELDO MINIMO SUGERIDO
                        $incidencia_sugerido = $this->cfpd97->execute("SELECT sueldo_sugerido FROM cnmd09_incidencia_sueldo_sugerido WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina;");
                        if (!empty($incidencia_sugerido)) {
                            $sueldo_sugerido = $incidencia_sugerido[0][0]['sueldo_sugerido'];
                        } else {
                            $sueldo_sugerido = '0.00';
                        }
                    }
                    $tn = $tipo_nomina;


                    // ACTUALIZA SUELDOS QUE ESTAN POR DEBAJO DEL SUELDO MINIMO SUGERIDO
                    if ($sueldo_basico != 0) {
                        if ($sueldo_sugerido != 0) {
                            if ($sueldo_sugerido > $sueldo_basico) {
                                $sueldo_basico = $sueldo_sugerido;
                                $sql_update_cfpd97 = "UPDATE cfpd97 SET sueldo_basico=$sueldo_sugerido WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina and cod_cargo=$cod_cargo;";
                                $this->cfpd97->execute($sql_update_cfpd97);
                            }
                        }

                        // ACTUALIZA SUELDO O SALARIO
                        $monto_calculado = $sueldo_basico;
                        $dias_escenario = $dias_cobro;
                        $anos_escenario = 0;
                        $cod_tipo_transa = 1;
                        $cod_transaccion = 1;
                        $monto_anual = $this->redondeo($sueldo_basico * $dias_ano);
                        $monto_anual = $this->Formato1($monto_anual);
                        $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_cargo . "," . $cod_tipo_transa . "," . $cod_transaccion . "," . $ano . "," . $sector . "," . $programa . "," . $sub_programa . "," . $proyecto . "," . $actividad . "," . $partida . "," . $generica . "," . $especifica . "," . $sub_especifica . "," . $auxiliar . "," . $monto_calculado . "," . $monto_anual . "," . $dias_cobro . "," . $cla_per . "," . $dias_ano . "," . $dias_escenario . "," . $anos_escenario;
                        $update_cargo = $this->cfpd97->execute("SELECT update_cfpd97_transacciones($parametros);");
                    }






                    // ACTUALIZA ASIGNACIONES COMUNES EN BOLIVARES
                    $veri = $ct;
                    $escenario_asignacion_bolivares = $this->cfpd97->execute("SELECT * FROM cnmd10_comunes_bolivares_asignacion WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina;");
                    if (!empty($escenario_asignacion_bolivares)) {
                        foreach ($escenario_asignacion_bolivares as $escenario_bolivares) {
                            $cod_tipo_transa = $escenario_bolivares[0]['cod_tipo_transaccion'];
                            $cod_transaccion = $escenario_bolivares[0]['cod_transaccion'];
                            $monto_calculado = $escenario_bolivares[0]['monto'];
                            $anos_escenario = 0;
                            $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_tipo_transa . "," . $cod_transaccion;
                            $sql_dias_ano = $this->cfpd97->execute("SELECT cfpd97_formula_anual_transa($parametros) as dias_ano;");
                            $dias_ano = $sql_dias_ano[0][0]['dias_ano'];
                            $this->ejecutar_proceso_partidas($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $tipo_nomina, $cod_cargo, $cod_tipo_transa, $cod_transaccion, $ano, $sector, $programa, $sub_programa, $proyecto, $actividad, $partida, $generica, $especifica, $sub_especifica, $auxiliar, $monto_calculado, $monto_anual, $dias_cobro, $cla_per, $dias_ano, $dias_escenario, $anos_escenario, $tn, $veri, $deno_nomi);
                        }// fin foreach
                    } else {
                        if ($tipo_nomina != $es) {
                            $j++;
                            $ano_esc[$j][0] = $deno_nomi;
                            $ano_esc[$j][1] = " ASIGNACIONES COMUNES EN BOLIVARES";

                            $es = $tipo_nomina;
                        }
                    } // FIN ACTUALIZA ASIGNACIONES COMUNES EN BOLIVARES


                    // ACTUALIZA ASIGNACIONES COMUNES UTILIZANDO UNA ESCALA DE SUELDO
                    $veri = $ct_es;
                    $escenario_asignacion_escala = $this->cfpd97->execute("SELECT * FROM cnmd10_comunes_escala_sueldo_bolivares_asig WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina;");
                    if (!empty($escenario_asignacion_escala)) {
                        foreach ($escenario_asignacion_escala as $escenario_escala) {
                            $cod_tipo_transa = $escenario_escala[0]['cod_tipo_transaccion'];
                            $cod_transaccion = $escenario_escala[0]['cod_transaccion'];
                            $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_tipo_transa . "," . $cod_transaccion;
                            $sql_dias_ano = $this->cfpd97->execute("SELECT cfpd97_formula_anual_transa($parametros) as dias_ano;");
                            $dias_ano = $sql_dias_ano[0][0]['dias_ano'];
                            $escenario_asignacion_escala_monto = $this->cfpd97->execute("SELECT monto FROM cnmd10_comunes_escala_sueldo_bolivares_asig_2 WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina and cod_tipo_transaccion=$cod_tipo_transa and cod_transaccion=$cod_transaccion and $sueldo_basico BETWEEN desde_sueldo AND hasta_sueldo;");
                            if (!empty($escenario_asignacion_escala_monto)) {
                                $monto_calculado = $escenario_asignacion_escala_monto[0][0]['monto'];
                                $anos_escenario = 0;
                                $this->ejecutar_proceso_partidas($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $tipo_nomina, $cod_cargo, $cod_tipo_transa, $cod_transaccion, $ano, $sector, $programa, $sub_programa, $proyecto, $actividad, $partida, $generica, $especifica, $sub_especifica, $auxiliar, $monto_calculado, $monto_anual, $dias_cobro, $cla_per, $dias_ano, $dias_escenario, $anos_escenario, $tn, $veri, $deno_nomi);
                            }
                        }
                    } else {
                        if ($tipo_nomina != $es_es) {
                            $j++;
                            $ano_esc[$j][0] = $deno_nomi;
                            $ano_esc[$j][1] = "ASIGNACIONES COMUNES UTILIZANDO UNA ESCALA DE SUELDO";

                            $es_es = $tipo_nomina;
                        }
                    } // FIN ACTUALIZA ASIGNACIONES COMUNES UTILIZANDO UNA ESCALA DE SUELDO


                    // ACTUALIZA ASIGNACIONES COMUNES SEGÚN EL PUESTO QUE OCUPA
                    $veri = $ct_pu;
                    $escenario_asignacion_puesto = $this->cfpd97->execute("SELECT * FROM cnmd10_comunes_puestos_bolivares_asig WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina;");
                    if (!empty($escenario_asignacion_puesto)) {
                        foreach ($escenario_asignacion_puesto as $asignacion_escala) {
                            $cod_tipo_transa = $asignacion_escala[0]['cod_tipo_transaccion'];
                            $cod_transaccion = $asignacion_escala[0]['cod_transaccion'];
                            $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_tipo_transa . "," . $cod_transaccion;
                            $sql_dias_ano = $this->cfpd97->execute("SELECT cfpd97_formula_anual_transa($parametros) as dias_ano;");
                            $dias_ano = $sql_dias_ano[0][0]['dias_ano'];
                            $escenario_asignacion_puesto_monto = $this->cfpd97->execute("SELECT monto FROM cnmd10_comunes_puestos_bolivares_asig_2 WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina and cod_tipo_transaccion=$cod_tipo_transa and cod_transaccion=$cod_transaccion and cod_puesto=$cod_puesto;");
                            if (!empty($escenario_asignacion_puesto_monto)) {
                                $monto_calculado = $escenario_asignacion_puesto_monto[0][0]['monto'];
                                $anos_escenario = 0;
                                $this->ejecutar_proceso_partidas($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $tipo_nomina, $cod_cargo, $cod_tipo_transa, $cod_transaccion, $ano, $sector, $programa, $sub_programa, $proyecto, $actividad, $partida, $generica, $especifica, $sub_especifica, $auxiliar, $monto_calculado, $monto_anual, $dias_cobro, $cla_per, $dias_ano, $dias_escenario, $anos_escenario, $tn, $veri, $deno_nomi);
                            }
                        }// foreach
                    } else {
                        if ($tipo_nomina != $es_pu) {
                            $j++;
                            $ano_esc[$j][0] = $deno_nomi;
                            $ano_esc[$j][1] = " ASIGNACIONES COMUNES SEGÚN EL PUESTO QUE OCUPA";

                            $es_pu = $tipo_nomina;
                        }
                    }// FIN ACTUALIZA ASIGNACIONES COMUNES SEGÚN EL PUESTO QUE OCUPA



                    // ACTUALIZA ASIGNACIONES COMUNES EN BOLIVARES SEGÚN EL GÉNERO
                    // REVISADO Y CERTIFICADO
                    $veri = $ct_ge;
                    $escenario_asignacion_genero = $this->cfpd97->execute("SELECT * FROM cnmd10_comunes_asignacion_bolivares_sexo WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina;");
                    if (!empty($escenario_asignacion_genero)) {
                        foreach ($escenario_asignacion_genero as $escenario_genero) {
                            $cod_tipo_transa = $escenario_genero[0]['cod_tipo_transaccion'];
                            $cod_transaccion = $escenario_genero[0]['cod_transaccion'];
                            $monto_femenino = $escenario_genero[0]['monto_femenino'];
                            $monto_masculino = $escenario_genero[0]['monto_masculino'];
                            $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_tipo_transa . "," . $cod_transaccion;
                            $sql_dias_ano = $this->cfpd97->execute("SELECT cfpd97_formula_anual_transa($parametros) as dias_ano;");
                            $dias_ano = $sql_dias_ano[0][0]['dias_ano'];
                            $busca_ficha = $this->cfpd97->execute("SELECT sexo FROM v_cnmd06_fichas WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha;");
                            if (!empty($busca_ficha)) {
                                $genero = $busca_ficha[0][0]['sexo'];
                                if ($genero == 'f' || $genero == 'F') {
                                    $monto_calculado = $monto_femenino;
                                } else {
                                    $monto_calculado = $monto_masculino;
                                }
                            } else {
                                $monto_calculado = $this->redondeo((($monto_femenino + $monto_masculino) / 2));
                                $monto_calculado = $this->Formato1($monto_calculado);
                            }
                            $anos_escenario = 0;
                            $this->ejecutar_proceso_partidas($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $tipo_nomina, $cod_cargo, $cod_tipo_transa, $cod_transaccion, $ano, $sector, $programa, $sub_programa, $proyecto, $actividad, $partida, $generica, $especifica, $sub_especifica, $auxiliar, $monto_calculado, $monto_anual, $dias_cobro, $cla_per, $dias_ano, $dias_escenario, $anos_escenario, $tn, $veri, $deno_nomi);
                        }// foreach
                    } else {
                        if ($tipo_nomina != $es_ge) {
                            $j++;
                            $ano_esc[$j][0] = $deno_nomi;
                            $ano_esc[$j][1] = " ASIGNACIONES COMUNES EN BOLIVARES SEGÚN EL GÉNERO";
                            $es_ge = $tipo_nomina;
                        }
                    } // FIN ACTUALIZA ASIGNACIONES COMUNES EN BOLIVARES SEGÚN EL GÉNERO


                    // ACTUALIZA ASIGNACIONES COMUNES CALCULADO SEGÚN ESCALA DE AÑOS DE SERVICIO
                    // REVISADO Y CERTIFICADO
                    $veri = $ct_se;
                    $escenario_asignacion_escala = $this->cfpd97->execute("SELECT * FROM cnmd10_comunes_escala_antiguedad_bolivares_asig WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina;");
                    if (!empty($escenario_asignacion_escala)) {
                        foreach ($escenario_asignacion_escala as $esce_aig_escala) {
                            $cod_tipo_transa = $esce_aig_escala[0]['cod_tipo_transaccion'];
                            $cod_transaccion = $esce_aig_escala[0]['cod_transaccion'];
                            $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_tipo_transa . "," . $cod_transaccion;
                            $sql_dias_ano = $this->cfpd97->execute("SELECT cfpd97_formula_anual_transa($parametros) as dias_ano;");
                            $dias_ano = $sql_dias_ano[0][0]['dias_ano'];
                            $anos_escenario = 0;
                            $fecha_calculo = "'$ano-12-31'";
                            $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_cargo . "," . $cod_ficha . "," . $cod_tipo_transa . "," . $cod_transaccion . "," . $fecha_calculo;
                            $sql_monto = $this->cfpd97->execute("SELECT cfpd97_devolver_monto_antiguedad($parametros) as monto;");
                            if (!empty($sql_monto)) {
                                $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_cargo . "," . $cod_ficha . "," . $fecha_calculo;
                                $sql_anos = $this->cfpd97->execute("SELECT cfpd97_devolver_antiguedad($parametros) as anos;");
                                $anos_escenario = $sql_anos[0][0]['anos'];
                                $monto_calculado = $sql_monto[0][0]['monto'];
                            } else {
                                $monto_calculado = 0;
                            }
                            if ($monto_calculado != 0) {
                                $this->ejecutar_proceso_partidas($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $tipo_nomina, $cod_cargo, $cod_tipo_transa, $cod_transaccion, $ano, $sector, $programa, $sub_programa, $proyecto, $actividad, $partida, $generica, $especifica, $sub_especifica, $auxiliar, $monto_calculado, $monto_anual, $dias_cobro, $cla_per, $dias_ano, $dias_escenario, $anos_escenario, $tn, $veri, $deno_nomi);
                            } else {
                                //$escenario_asignacion_escala_monto=$this->cfpd97->execute("SELECT max(monto) as monto FROM cnmd10_comunes_escala_antiguedad_bolivares_asig_2 WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina and cod_tipo_transaccion=$cod_tipo_transa and cod_transaccion=$cod_transaccion;");
                                $escenario_asignacion_escala_monto = $this->cfpd97->execute("SELECT avg(monto) as monto FROM cnmd10_comunes_escala_antiguedad_bolivares_asig_2 WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina and cod_tipo_transaccion=$cod_tipo_transa and cod_transaccion=$cod_transaccion;");
                                if (!empty($escenario_asignacion_escala_monto)) {
                                    $monto_calculado = $escenario_asignacion_escala_monto[0][0]['monto'];
                                    $this->ejecutar_proceso_partidas($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $tipo_nomina, $cod_cargo, $cod_tipo_transa, $cod_transaccion, $ano, $sector, $programa, $sub_programa, $proyecto, $actividad, $partida, $generica, $especifica, $sub_especifica, $auxiliar, $monto_calculado, $monto_anual, $dias_cobro, $cla_per, $dias_ano, $dias_escenario, $anos_escenario, $tn, $veri, $deno_nomi);
                                }
                            }
                        }// foreach
                    } else {

                        if ($tipo_nomina != $es_se) {
                            $j++;
                            $ano_esc[$j][0] = $deno_nomi;
                            $ano_esc[$j][1] = "ASIGNACIONES COMUNES CALCULADO SEGÚN ESCALA DE AÑOS DE SERVICIO";

                            $es_se = $tipo_nomina;
                        }
                    } // FIN ACTUALIZA ASIGNACIONES COMUNES CALCULADO SEGÚN ESCALA DE AÑOS DE SERVICIO



                    // ACTUALIZA ASIGNACIONES INDIVIDUALES EN BOLIVARES SEGÚN LA CANTIDAD QUE LE CORRESPONDE
                    $veri = $ct_in_bo;
                    $escenario_asig_indiv_monto = $this->cfpd97->execute("SELECT * FROM cnmd10_individual_bolivares WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina;");
                    if (!empty($escenario_asig_indiv_monto)) {
                        foreach ($escenario_asig_indiv_monto as $esce_indiv_monto) {
                            $cod_tipo_transa = $esce_indiv_monto[0]['cod_tipo_transaccion'];
                            $cod_transaccion = $esce_indiv_monto[0]['cod_transaccion'];
                            $monto_individ = $esce_indiv_monto[0]['monto'];
                            $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_tipo_transa . "," . $cod_transaccion;
                            $sql_dias_ano = $this->cfpd97->execute("SELECT cfpd97_formula_anual_transa($parametros) as dias_ano;");
                            $dias_ano = $sql_dias_ano[0][0]['dias_ano'];
                            $escenario_asig_indiv_cantidad = $this->cfpd97->execute("SELECT cantidad FROM cnmd10_individual_bolivares_cantidad WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina and cod_cargo=$cod_cargo and cod_tipo_transaccion=$cod_tipo_transa and cod_transaccion=$cod_transaccion;");
                            if (!empty($escenario_asig_indiv_cantidad)) {
                                $cantidad = $escenario_asig_indiv_cantidad[0][0]['cantidad'];
                                $monto_calculado = ($monto_individ * $cantidad);
                                $anos_escenario = 0;
                                $this->ejecutar_proceso_partidas($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $tipo_nomina, $cod_cargo, $cod_tipo_transa, $cod_transaccion, $ano, $sector, $programa, $sub_programa, $proyecto, $actividad, $partida, $generica, $especifica, $sub_especifica, $auxiliar, $monto_calculado, $monto_anual, $dias_cobro, $cla_per, $dias_ano, $dias_escenario, $anos_escenario, $tn, $veri, $deno_nomi);
                            }
                        }// foreach
                    } else {

                        if ($tipo_nomina != $es_in_bo) {
                            $j++;
                            $ano_esc[$j][0] = $deno_nomi;
                            $ano_esc[$j][1] = "ASIGNACIONES INDIVIDUALES EN BOLIVARES SEGÚN LA CANTIDAD QUE LE CORRESPONDE";
                            $es_in_bo = $tipo_nomina;
                        }
                    } // FIN ACTUALIZA ASIGNACIONES INDIVIDUALES EN BOLIVARES SEGÚN LA CANTIDAD QUE LE CORRESPONDE


                    // ACTUALIZA ASIGNACIONES COMUNES EN PORCENTAJE CALCULADO SEGÚN EL SALARIO
                    $veri = $ct_po;
                    $escenario_asignacion_bolivares_porc = $this->cfpd97->execute("SELECT * FROM cnmd10_comunes_porcentaje_asignacion WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina;");
                    if (!empty($escenario_asignacion_bolivares_porc)) {
                        foreach ($escenario_asignacion_bolivares_porc as $esce_boli_porc) {
                            $cod_tipo_transa = $esce_boli_porc[0]['cod_tipo_transaccion'];
                            $cod_transaccion = $esce_boli_porc[0]['cod_transaccion'];
                            $porcentaje = $esce_boli_porc[0]['porcentaje'];
                            $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_tipo_transa . "," . $cod_transaccion;
                            $sql_dias_ano = $this->cfpd97->execute("SELECT cfpd97_formula_anual_transa($parametros) as dias_ano;");
                            $dias_ano = $sql_dias_ano[0][0]['dias_ano'];
                            $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_cargo . "," . $cod_tipo_transa . "," . $cod_transaccion;
                            $sql_salario = $this->cfpd97->execute("SELECT cfpd97_salario_integral_asig_asig($parametros) as salario;");
                            $salario = $sql_salario[0][0]['salario'];
                            $monto_calculado = ((($salario + $sueldo_basico) * $porcentaje) / 100);
                            $anos_escenario = 0;
                            $this->ejecutar_proceso_partidas($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $tipo_nomina, $cod_cargo, $cod_tipo_transa, $cod_transaccion, $ano, $sector, $programa, $sub_programa, $proyecto, $actividad, $partida, $generica, $especifica, $sub_especifica, $auxiliar, $monto_calculado, $monto_anual, $dias_cobro, $cla_per, $dias_ano, $dias_escenario, $anos_escenario, $tn, $veri, $deno_nomi);
                        }// foreach
                    } else {

                        if ($tipo_nomina != $es_po) {
                            $j++;
                            $ano_esc[$j][0] = $deno_nomi;
                            $ano_esc[$j][1] = "ASIGNACIONES COMUNES EN PORCENTAJE CALCULADO SEGÚN EL SALARIO";
                            $es_po = $tipo_nomina;
                        }
                    } // FIN ACTUALIZA ASIGNACIONES COMUNES EN PORCENTAJE CALCULADO SEGÚN EL SALARIO


                    // ACTUALIZA ASIGNACIONES COMUNES UTILIZANDO UNA ESCALA DE SUELDO
                    $veri = $ct_es_po;
                    $escenario_asignacion_escala_porc = $this->cfpd97->execute("SELECT * FROM cnmd10_comunes_escala_sueldo_porcentaje_asig WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina;");
                    if (!empty($escenario_asignacion_escala_porc)) {
                        foreach ($escenario_asignacion_escala_porc as $esce_escala_porc) {
                            $cod_tipo_transa = $esce_escala_porc[0]['cod_tipo_transaccion'];
                            $cod_transaccion = $esce_escala_porc[0]['cod_transaccion'];
                            $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_tipo_transa . "," . $cod_transaccion;
                            $sql_dias_ano = $this->cfpd97->execute("SELECT cfpd97_formula_anual_transa($parametros) as dias_ano;");
                            $dias_ano = $sql_dias_ano[0][0]['dias_ano'];
                            $escenario_asignacion_escala_porcentaje = $this->cfpd97->execute("SELECT porcentaje FROM cnmd10_comunes_escala_sueldo_porcentaje_asig_2 WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina and cod_tipo_transaccion=$cod_tipo_transa and cod_transaccion=$cod_transaccion and $sueldo_basico BETWEEN desde_sueldo AND hasta_sueldo;");
                            if (!empty($escenario_asignacion_escala_porcentaje)) {
                                $porcentaje = $escenario_asignacion_escala_porcentaje[0][0]['porcentaje'];
                                $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_cargo . "," . $cod_tipo_transa . "," . $cod_transaccion;
                                $sql_salario = $this->cfpd97->execute("SELECT cfpd97_salario_integral_asig_asig($parametros) as salario;");
                                $salario = $sql_salario[0][0]['salario'];
                                $monto_calculado = ((($salario + $sueldo_basico) * $porcentaje) / 100);
                                $anos_escenario = 0;
                                $this->ejecutar_proceso_partidas($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $tipo_nomina, $cod_cargo, $cod_tipo_transa, $cod_transaccion, $ano, $sector, $programa, $sub_programa, $proyecto, $actividad, $partida, $generica, $especifica, $sub_especifica, $auxiliar, $monto_calculado, $monto_anual, $dias_cobro, $cla_per, $dias_ano, $dias_escenario, $anos_escenario, $tn, $veri, $deno_nomi);
                            }
                        }// foreach
                    } else {
                        if ($tipo_nomina != $es_es_po) {
                            $j++;
                            $ano_esc[$j][0] = $deno_nomi;
                            $ano_esc[$j][1] = "ASIGNACIONES COMUNES UTILIZANDO UNA ESCALA DE SUELDO";
                            $es_es_po = $tipo_nomina;
                        }
                    } // FIN ACTUALIZA ASIGNACIONES COMUNES UTILIZANDO UNA ESCALA DE SUELDO



                    // ACTUALIZA ASIGNACIONES COMUNES EN PORCENTAJE SEGÚN EL PUESTO QUE OCUPA
                    $veri = $ct_es_pu;
                    $escenario_asignacion_puesto_porce = $this->cfpd97->execute("SELECT * FROM cnmd10_comunes_puestos_porcentaje_asig WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina;");
                    if (!empty($escenario_asignacion_puesto_porce)) {
                        foreach ($escenario_asignacion_puesto_porce as $esce_puesto_porc) {
                            $cod_tipo_transa = $esce_puesto_porc[0]['cod_tipo_transaccion'];
                            $cod_transaccion = $esce_puesto_porc[0]['cod_transaccion'];
                            $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_tipo_transa . "," . $cod_transaccion;
                            $sql_dias_ano = $this->cfpd97->execute("SELECT cfpd97_formula_anual_transa($parametros) as dias_ano;");
                            $dias_ano = $sql_dias_ano[0][0]['dias_ano'];
                            $escenario_asignacion_puesto_porcentaje = $this->cfpd97->execute("SELECT porcentaje FROM cnmd10_comunes_puestos_porcentaje_asig_2 WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina and cod_tipo_transaccion=$cod_tipo_transa and cod_transaccion=$cod_transaccion and cod_puesto=$cod_puesto;");
                            if (!empty($escenario_asignacion_puesto_porcentaje)) {
                                $porcentaje = $escenario_asignacion_puesto_porcentaje[0][0]['porcentaje'];
                                $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_cargo . "," . $cod_tipo_transa . "," . $cod_transaccion;
                                $sql_salario = $this->cfpd97->execute("SELECT cfpd97_salario_integral_asig_asig($parametros) as salario;");
                                $salario = $sql_salario[0][0]['salario'];
                                $monto_calculado = ((($salario + $sueldo_basico) * $porcentaje) / 100);
                                $anos_escenario = 0;
                                $this->ejecutar_proceso_partidas($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $tipo_nomina, $cod_cargo, $cod_tipo_transa, $cod_transaccion, $ano, $sector, $programa, $sub_programa, $proyecto, $actividad, $partida, $generica, $especifica, $sub_especifica, $auxiliar, $monto_calculado, $monto_anual, $dias_cobro, $cla_per, $dias_ano, $dias_escenario, $anos_escenario, $tn, $veri, $deno_nomi);
                            }
                        }// foreach
                    } else {

                        if ($tipo_nomina != $es_es_pu) {
                            $j++;
                            $ano_esc[$j][0] = $deno_nomi;
                            $ano_esc[$j][1] = "ASIGNACIONES COMUNES EN PORCENTAJE SEGÚN EL PUESTO QUE OCUPA";
                            $es_es_pu = $tipo_nomina;
                        }
                    } // FIN ACTUALIZA ASIGNACIONES COMUNES EN PORCENTAJE SEGÚN EL PUESTO QUE OCUPA


                    // ACTUALIZA ASIGNACIONES COMUNES EN PORCENTAJE SEGÚN EL GÉNERO
                    // REVISADO Y CERTIFICADO
                    $veri = $ct_po_ge;
                    $escenario_asignacion_genero_porc = $this->cfpd97->execute("SELECT * FROM cnmd10_comunes_asignacion_porcentaje_sexo WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina;");
                    if (!empty($escenario_asignacion_genero_porc)) {
                        foreach ($escenario_asignacion_genero_porc as $esce_genero_porc) {
                            $cod_tipo_transa = $esce_genero_porc[0]['cod_tipo_transaccion'];
                            $cod_transaccion = $esce_genero_porc[0]['cod_transaccion'];
                            $porce_femenino = $esce_genero_porc[0]['porcentaje_femenino'];
                            $porce_masculino = $esce_genero_porc[0]['porcentaje_masculino'];
                            $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_tipo_transa . "," . $cod_transaccion;
                            $sql_dias_ano = $this->cfpd97->execute("SELECT cfpd97_formula_anual_transa($parametros) as dias_ano;");
                            $dias_ano = $sql_dias_ano[0][0]['dias_ano'];
                            $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_cargo . "," . $cod_tipo_transa . "," . $cod_transaccion;
                            $sql_salario = $this->cfpd97->execute("SELECT cfpd97_salario_integral_asig_asig($parametros) as salario;");
                            $salario = $sql_salario[0][0]['salario'];
                            $busca_ficha_genero = $this->cfpd97->execute("SELECT sexo FROM v_cnmd06_fichas WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha;");
                            if (!empty($busca_ficha_genero)) {
                                $genero = $busca_ficha_genero[0][0]['sexo'];
                                if ($genero == 'f' || $genero == 'F') {
                                    $monto_calculado = ((($salario + $sueldo_basico) * $porce_femenino) / 100);
                                } else {
                                    $monto_calculado = ((($salario + $sueldo_basico) * $porce_masculino) / 100);
                                }
                            } else {
                                $monto_femenino = ((($salario + $sueldo_basico) * $porce_femenino) / 100);
                                $monto_masculin = ((($salario + $sueldo_basico) * $porce_masculino) / 100);
                                $monto_calculado = (($monto_femenino + $monto_masculin) / 2);
                            }
                            $monto_calculado = $this->redondeo($monto_calculado);
                            $monto_calculado = $this->Formato1($monto_calculado);
                            $anos_escenario = 0;
                            $this->ejecutar_proceso_partidas($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $tipo_nomina, $cod_cargo, $cod_tipo_transa, $cod_transaccion, $ano, $sector, $programa, $sub_programa, $proyecto, $actividad, $partida, $generica, $especifica, $sub_especifica, $auxiliar, $monto_calculado, $monto_anual, $dias_cobro, $cla_per, $dias_ano, $dias_escenario, $anos_escenario, $tn, $veri, $deno_nomi);
                        } // foreach
                    } else {

                        if ($tipo_nomina != $es_po_ge) {
                            $j++;
                            $ano_esc[$j][0] = $deno_nomi;
                            $ano_esc[$j][1] = "ASIGNACIONES COMUNES EN PORCENTAJE SEGÚN EL GÉNERO";
                            $es_po_ge = $tipo_nomina;
                        }
                    } // FIN ACTUALIZA ASIGNACIONES COMUNES EN PORCENTAJE SEGÚN EL GÉNERO



                    // ACTUALIZA ASIGNACIONES COMUNES CALCULADO EN PORCENTAJE SEGÚN ESCALA DE AÑOS DE SERVICIO
                    // REVISADO Y CERTIFICADO
                    $veri = $ct_po_es;
                    $escenario_asignacion_escala_porc = $this->cfpd97->execute("SELECT * FROM cnmd10_comunes_escala_antiguedad_porcentaje_asig WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina;");
                    if (!empty($escenario_asignacion_escala_porc)) {
                        foreach ($escenario_asignacion_escala_porc as $esce_asig_escala) {
                            $cod_tipo_transa = $esce_asig_escala[0]['cod_tipo_transaccion'];
                            $cod_transaccion = $esce_asig_escala[0]['cod_transaccion'];
                            $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_tipo_transa . "," . $cod_transaccion;
                            $sql_dias_ano = $this->cfpd97->execute("SELECT cfpd97_formula_anual_transa($parametros) as dias_ano;");
                            $dias_ano = $sql_dias_ano[0][0]['dias_ano'];
                            $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_cargo . "," . $cod_tipo_transa . "," . $cod_transaccion;
                            $sql_salario = $this->cfpd97->execute("SELECT cfpd97_salario_integral_asig_asig($parametros) as salario;");
                            $salario = $sql_salario[0][0]['salario'];
                            $fecha_calculo = "'$ano-12-31'";

                            $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_cargo . "," . $cod_ficha . "," . $cod_tipo_transa . "," . $cod_transaccion . "," . $fecha_calculo;
                            $sql_porcentaje = $this->cfpd97->execute("SELECT cfpd97_devolver_porcentaje_antiguedad($parametros) as porcentaje;");
                            if (!empty($sql_porcentaje)) {
                                $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_cargo . "," . $cod_ficha . "," . $fecha_calculo;
                                $sql_anos = $this->cfpd97->execute("SELECT cfpd97_devolver_antiguedad($parametros) as anos;");
                                $anos_escenario = $sql_anos[0][0]['anos'];
                                $porcentaje = $sql_porcentaje[0][0]['porcentaje'];
                            } else {
                                $porcentaje = 0;
                            }
                            if ($porcentaje != 0) {
                                $monto_calculado = ((($salario + $sueldo_basico) * $porcentaje) / 100);
                                $this->ejecutar_proceso_partidas($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $tipo_nomina, $cod_cargo, $cod_tipo_transa, $cod_transaccion, $ano, $sector, $programa, $sub_programa, $proyecto, $actividad, $partida, $generica, $especifica, $sub_especifica, $auxiliar, $monto_calculado, $monto_anual, $dias_cobro, $cla_per, $dias_ano, $dias_escenario, $anos_escenario, $tn, $veri, $deno_nomi);
                            } else {
                                //$escenario_asignacion_escala_porce=$this->cfpd97->execute("SELECT max(porcentaje) as porcentaje FROM cnmd10_comunes_escala_antiguedad_porcentaje_asig_2 WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina and cod_tipo_transaccion=$cod_tipo_transa and cod_transaccion=$cod_transaccion;");
                                $escenario_asignacion_escala_porce = $this->cfpd97->execute("SELECT avg(porcentaje) as porcentaje FROM cnmd10_comunes_escala_antiguedad_porcentaje_asig_2 WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina and cod_tipo_transaccion=$cod_tipo_transa and cod_transaccion=$cod_transaccion;");
                                if (!empty($escenario_asignacion_escala_porce)) {
                                    $anos_escenario = 0;
                                    $porcentaje = $escenario_asignacion_escala_porce[0][0]['porcentaje'];
                                    $monto_calculado = ((($salario + $sueldo_basico) * $porcentaje) / 100);
                                    $this->ejecutar_proceso_partidas($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $tipo_nomina, $cod_cargo, $cod_tipo_transa, $cod_transaccion, $ano, $sector, $programa, $sub_programa, $proyecto, $actividad, $partida, $generica, $especifica, $sub_especifica, $auxiliar, $monto_calculado, $monto_anual, $dias_cobro, $cla_per, $dias_ano, $dias_escenario, $anos_escenario, $tn, $veri, $deno_nomi);
                                }
                            }
                        }// foreach
                    } else {

                        if ($tipo_nomina != $es_po_es) {
                            $j++;
                            $ano_esc[$j][0] = $deno_nomi;
                            $ano_esc[$j][1] = "ASIGNACIONES COMUNES CALCULADO EN PORCENTAJE SEGÚN ESCALA DE AÑOS DE SERVICIO";
                            $es_po_es = $tipo_nomina;
                        }
                    } // FIN ACTUALIZA ASIGNACIONES COMUNES CALCULADO EN PORCENTAJE SEGÚN ESCALA DE AÑOS DE SERVICIO



                    // ACTUALIZA ASIGNACIONES COMUNES EN DIAS CALCULADO SEGÚN EL SALARIO (EJEMPLO: DIAS FERIADOS)
                    $veri = $ct_dia;
                    $escenario_asignacion_dia = $this->cfpd97->execute("SELECT * FROM cnmd10_comunes_dia_asignacion WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina;");
                    if (!empty($escenario_asignacion_dia)) {
                        foreach ($escenario_asignacion_dia as $esce_asig_dia) {
                            $cod_tipo_transa = $esce_asig_dia[0]['cod_tipo_transaccion'];
                            $cod_transaccion = $esce_asig_dia[0]['cod_transaccion'];
                            $dias_escenario = $esce_asig_dia[0]['dias'];
                            $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_tipo_transa . "," . $cod_transaccion;
                            $sql_dias_ano = $this->cfpd97->execute("SELECT cfpd97_formula_anual_transa($parametros) as dias_ano;");
                            $dias_ano = $sql_dias_ano[0][0]['dias_ano'];
                            $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_cargo . "," . $cod_tipo_transa . "," . $cod_transaccion;
                            $sql_salario = $this->cfpd97->execute("SELECT cfpd97_salario_integral_asig_asig($parametros) as salario;");
                            $salario = $sql_salario[0][0]['salario'];
                            $monto_calculado = ((($salario + $sueldo_basico) / $dias_cobro) * $dias_escenario);
                            $anos_escenario = 0;
                            $this->ejecutar_proceso_partidas($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $tipo_nomina, $cod_cargo, $cod_tipo_transa, $cod_transaccion, $ano, $sector, $programa, $sub_programa, $proyecto, $actividad, $partida, $generica, $especifica, $sub_especifica, $auxiliar, $monto_calculado, $monto_anual, $dias_cobro, $cla_per, $dias_ano, $dias_escenario, $anos_escenario, $tn, $veri, $deno_nomi);
                        }// foreach
                    } else {

                        if ($tipo_nomina != $es_dia) {
                            $j++;
                            $ano_esc[$j][0] = $deno_nomi;
                            $ano_esc[$j][1] = "ASIGNACIONES COMUNES EN DIAS CALCULADO SEGÚN EL SALARIO (EJEMPLO: DIAS FERIADOS)";
                            $es_dia = $tipo_nomina;
                        }
                    } // FIN ACTUALIZA ASIGNACIONES COMUNES EN DIAS CALCULADO SEGÚN EL SALARIO (EJEMPLO: DIAS FERIADOS)



                    // ACTUALIZA ASIGNACIONES COMUNES EN DIAS CALCULADO SEGÚN ESCALA DE AÑOS DE SERVICIO (EJEMPLO: BONO VACACIONAL-COLECTIVO)
                    // REVISADO Y CERTIFICADO
                    $veri = $ct_dia_es;
                    $escenario_bono_colectivo = $this->cfpd97->execute("SELECT * FROM cnmd10_comunes_escala_antiguedad_dias_asig WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina;");
                    if (!empty($escenario_bono_colectivo)) {
                        foreach ($escenario_bono_colectivo as $esce_bono_cole) {
                            $cod_tipo_transa = $esce_bono_cole[0]['cod_tipo_transaccion'];
                            $cod_transaccion = $esce_bono_cole[0]['cod_transaccion'];
                            $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_tipo_transa . "," . $cod_transaccion;
                            $sql_dias_ano = $this->cfpd97->execute("SELECT cfpd97_formula_anual_transa($parametros) as dias_ano;");
                            $dias_ano = $sql_dias_ano[0][0]['dias_ano'];
                            $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_cargo . "," . $cod_tipo_transa . "," . $cod_transaccion;
                            $sql_salario = $this->cfpd97->execute("SELECT cfpd97_salario_integral_asig_asig($parametros) as salario;");
                            $salario = $sql_salario[0][0]['salario'];
                            $fecha_calculo = "'$ano-12-31'";
                            $sql_dias_ant = "SELECT cfpd97_devolver_dia_antiguedad($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $tipo_nomina, $cod_cargo, $cod_ficha, $cod_tipo_transa, $cod_transaccion, $fecha_calculo) as dias";
                            $sql_dias = $this->cfpd97->execute($sql_dias_ant);
                            if (!empty($sql_dias)) {
                                $sql_anos_esce = "SELECT cfpd97_devolver_antiguedad($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $tipo_nomina, $cod_cargo, $cod_ficha, $fecha_calculo) as anos";
                                $sql_anos = $this->cfpd97->execute($sql_anos_esce);
                                $anos_escenario = $sql_anos[0][0]['anos'];
                                $dias_escenario = $sql_dias[0][0]['dias'];
                            } else {
                                $dias_escenario = 0;
                            }
                            if ($dias_escenario != 0) {
                                $monto_calculado = ((($salario + $sueldo_basico) / $dias_cobro) * $dias_escenario);
                                $this->ejecutar_proceso_partidas($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $tipo_nomina, $cod_cargo, $cod_tipo_transa, $cod_transaccion, $ano, $sector, $programa, $sub_programa, $proyecto, $actividad, $partida, $generica, $especifica, $sub_especifica, $auxiliar, $monto_calculado, $monto_anual, $dias_cobro, $cla_per, $dias_ano, $dias_escenario, $anos_escenario, $tn, $veri, $deno_nomi);
                            } else {
                                //$escenario_bono_colectivo_fecha=$this->cfpd97->execute("SELECT max(dias) as dias FROM cnmd10_comunes_escala_antiguedad_dias_asig_2 WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina and cod_tipo_transaccion=$cod_tipo_transa and cod_transaccion=$cod_transaccion;");
                                $escenario_bono_colectivo_fecha = $this->cfpd97->execute("SELECT avg(dias) as dias FROM cnmd10_comunes_escala_antiguedad_dias_asig_2 WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina and cod_tipo_transaccion=$cod_tipo_transa and cod_transaccion=$cod_transaccion;");
                                if (!empty($escenario_bono_colectivo_fecha)) {
                                    $anos_escenario = 0;
                                    $dias_escenario = $escenario_bono_colectivo_fecha[0][0]['dias'];
                                    $monto_calculado = ((($salario + $sueldo_basico) / $dias_cobro) * $dias_escenario);
                                    $this->ejecutar_proceso_partidas($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $tipo_nomina, $cod_cargo, $cod_tipo_transa, $cod_transaccion, $ano, $sector, $programa, $sub_programa, $proyecto, $actividad, $partida, $generica, $especifica, $sub_especifica, $auxiliar, $monto_calculado, $monto_anual, $dias_cobro, $cla_per, $dias_ano, $dias_escenario, $anos_escenario, $tn, $veri, $deno_nomi);
                                }
                            }
                        }// foreach
                    } else {

                        if ($tipo_nomina != $es_dia_es) {
                            $j++;
                            $ano_esc[$j][0] = $deno_nomi;
                            $ano_esc[$j][1] = "ASIGNACIONES COMUNES EN DIAS CALCULADO SEGÚN ESCALA DE AÑOS DE SERVICIO (EJEMPLO: BONO VACACIONAL-COLECTIVO)";
                            $es_dia_es = $tipo_nomina;
                        }
                    } // FIN ACTUALIZA ASIGNACIONES COMUNES EN DIAS CALCULADO SEGÚN ESCALA DE AÑOS DE SERVICIO (EJEMPLO: BONO VACACIONAL-COLECTIVO)



                    // ACTUALIZA ASIGNACIONES COMUNES EN DIAS CALCULADO SEGÚN ESCALA DE AÑOS DE SERVICIO (EJEMPLO: BONO VACACIONAL-SELECTIVO)
                    // REVISADO Y CERTIFICADO
                    $veri = $ct_dia_fe;
                    $escenario_bono_selectivo = $this->cfpd97->execute("SELECT * FROM cnmd10_comunes_escala_antiguedad_dias_asig_fecha WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina;");
                    if (!empty($escenario_bono_selectivo)) {
                        foreach ($escenario_bono_selectivo as $esce_bono_selec) {

                            $cod_tipo_transa = $esce_bono_selec[0]['cod_tipo_transaccion'];
                            $cod_transaccion = $esce_bono_selec[0]['cod_transaccion'];
                            $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_tipo_transa . "," . $cod_transaccion;
                            $sql_dias_ano = $this->cfpd97->execute("SELECT cfpd97_formula_anual_transa($parametros) as dias_ano;");
                            $dias_ano = $sql_dias_ano[0][0]['dias_ano'];
                            $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_cargo . "," . $cod_tipo_transa . "," . $cod_transaccion;
                            $sql_salario_sel = $this->cfpd97->execute("SELECT cfpd97_salario_integral_asig_asig($parametros) as salario;");
                            $salario = $sql_salario_sel[0][0]['salario'];
                            $fecha_calculo = "'$ano-12-31'";
                            $sql_dias_esc_bn = "SELECT cfpd97_devolver_dia_antiguedad_fecha($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $tipo_nomina, $cod_cargo, $cod_ficha, $cod_tipo_transa, $cod_transaccion, $fecha_calculo) as dias";
                            $sql_dias_esca = $this->cfpd97->execute($sql_dias_esc_bn);
                            if (!empty($sql_dias_esca)) {
                                $sql_anos_esce = "SELECT cfpd97_devolver_antiguedad($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $tipo_nomina, $cod_cargo, $cod_ficha, $fecha_calculo) as anos";
                                $sql_anos = $this->cfpd97->execute($sql_anos_esce);
                                $anos_escenario = $sql_anos[0][0]['anos'];
                                $dias_escenario = $sql_dias_esca[0][0]['dias'];
                            } else {
                                $dias_escenario = 0;
                            }
                            if ($dias_escenario != 0) {
                                $monto_calculado = ((($salario + $sueldo_basico) / $dias_cobro) * $dias_escenario);
                                $this->ejecutar_proceso_partidas($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $tipo_nomina, $cod_cargo, $cod_tipo_transa, $cod_transaccion, $ano, $sector, $programa, $sub_programa, $proyecto, $actividad, $partida, $generica, $especifica, $sub_especifica, $auxiliar, $monto_calculado, $monto_anual, $dias_cobro, $cla_per, $dias_ano, $dias_escenario, $anos_escenario, $tn, $veri, $deno_nomi);
                            } else {
                                //$escenario_bono_selectivo_fecha=$this->cfpd97->execute("SELECT max(dias) as dias FROM cnmd10_comunes_escala_antiguedad_dias_asig_fecha_2 WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina and cod_tipo_transaccion=$cod_tipo_transa and cod_transaccion=$cod_transaccion;");
                                $escenario_bono_selectivo_fecha = $this->cfpd97->execute("SELECT avg(dias) as dias FROM cnmd10_comunes_escala_antiguedad_dias_asig_fecha_2 WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina and cod_tipo_transaccion=$cod_tipo_transa and cod_transaccion=$cod_transaccion;");
                                if (!empty($escenario_bono_selectivo_fecha)) {
                                    $anos_escenario = 0;
                                    $dias_escenario = $escenario_bono_selectivo_fecha[0][0]['dias'];
                                    $monto_calculado = ((($salario + $sueldo_basico) / $dias_cobro) * $dias_escenario);
                                    $this->ejecutar_proceso_partidas($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $tipo_nomina, $cod_cargo, $cod_tipo_transa, $cod_transaccion, $ano, $sector, $programa, $sub_programa, $proyecto, $actividad, $partida, $generica, $especifica, $sub_especifica, $auxiliar, $monto_calculado, $monto_anual, $dias_cobro, $cla_per, $dias_ano, $dias_escenario, $anos_escenario, $tn, $veri, $deno_nomi);
                                }
                            }
                        }//foreach
                    } else {

                        if ($tipo_nomina != $es_dia_fe) {
                            $j++;
                            $ano_esc[$j][0] = $deno_nomi;
                            $ano_esc[$j][1] = "ASIGNACIONES COMUNES EN DIAS CALCULADO SEGÚN ESCALA DE AÑOS DE SERVICIO (EJEMPLO: BONO VACACIONAL-SELECTIVO)";
                            $es_dia_fe = $tipo_nomina;
                        }
                    } // FIN ACTUALIZA ASIGNACIONES COMUNES EN DIAS CALCULADO SEGÚN ESCALA DE AÑOS DE SERVICIO (EJEMPLO: BONO VACACIONAL-SELECTIVO)



                    // ACTUALIZA ASIGNACIONES COMUNES EN DIAS CALCULADO UTILIZANDO UNA ESCALA POR MES Y DIA (EJEMPLO: AGUINALDOS)
                    $veri = $ct_dia_ag;
                    $escenario_aguinaldo = $this->cfpd97->execute("SELECT * FROM cnmd10_comunes_escala_mes_dia_asig WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina;");
                    if (!empty($escenario_aguinaldo)) {
                        foreach ($escenario_aguinaldo as $esce_agui) {

                            $cod_tipo_transa = $esce_agui[0]['cod_tipo_transaccion'];
                            $cod_transaccion = $esce_agui[0]['cod_transaccion'];
                            $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_tipo_transa . "," . $cod_transaccion;
                            $sql_dias_ano = $this->cfpd97->execute("SELECT cfpd97_formula_anual_transa($parametros) as dias_ano;");
                            $dias_ano = $sql_dias_ano[0][0]['dias_ano'];
                            //$escenario_aguinaldo_dias=$this->cfpd97->execute("SELECT avg(dias) as dias FROM cnmd10_comunes_escala_mes_dia_asig_2 WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina and cod_tipo_transaccion=$cod_tipo_transa and cod_transaccion=$cod_transaccion;");
                            $escenario_aguinaldo_dias = $this->cfpd97->execute("SELECT max(dias) as dias FROM cnmd10_comunes_escala_mes_dia_asig_2 WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina and cod_tipo_transaccion=$cod_tipo_transa and cod_transaccion=$cod_transaccion;");
                            if (!empty($escenario_aguinaldo_dias)) {
                                $dias_escenario = $escenario_aguinaldo_dias[0][0]['dias'];
                                $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_cargo . "," . $cod_tipo_transa . "," . $cod_transaccion;
                                $sql_salario = $this->cfpd97->execute("SELECT cfpd97_salario_integral_asig_asig($parametros) as salario;");
                                $salario = $sql_salario[0][0]['salario'];
                                $monto_calculado = ((($salario + $sueldo_basico) / $dias_cobro) * $dias_escenario);
                                $anos_escenario = 0;
                                $this->ejecutar_proceso_partidas($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $tipo_nomina, $cod_cargo, $cod_tipo_transa, $cod_transaccion, $ano, $sector, $programa, $sub_programa, $proyecto, $actividad, $partida, $generica, $especifica, $sub_especifica, $auxiliar, $monto_calculado, $monto_anual, $dias_cobro, $cla_per, $dias_ano, $dias_escenario, $anos_escenario, $tn, $veri, $deno_nomi);
                            }
                        }// foreach
                    } else {

                        if ($tipo_nomina != $es_dia_ag) {
                            $j++;
                            $ano_esc[$j][0] = $deno_nomi;
                            $ano_esc[$j][1] = "ASIGNACIONES COMUNES EN DIAS CALCULADO UTILIZANDO UNA ESCALA POR MES Y DIA (EJEMPLO: AGUINALDOS)";
                            $es_dia_ag = $tipo_nomina;
                        }
                    } // FIN ACTUALIZA ASIGNACIONES COMUNES EN DIAS CALCULADO UTILIZANDO UNA ESCALA POR MES Y DIA (EJEMPLO: AGUINALDOS)


                    // ACTUALIZA DEDUCCIONES APORTES PATRONALES EL PORCENTAJE CALCULO SEGÚN EL SALARIO
                    $escenario_aporte_patronales = $this->cfpd97->execute("SELECT * FROM cnmd10_aportes_patronales WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina;");
                    if (!empty($escenario_aporte_patronales)) {
                        foreach ($escenario_aporte_patronales as $aporte_patronal) {
                            $cod_tipo_transa = $aporte_patronal[0]['cod_tipo_transaccion'];
                            $cod_transaccion = $aporte_patronal[0]['cod_transaccion'];
                            $cod_tipo_patron = $aporte_patronal[0]['cod_tipo_transa_patrono'];
                            $cod_tran_patron = $aporte_patronal[0]['cod_transa_patrono'];
                            $porcentaje = $aporte_patronal[0]['porcentaje_patrono'];
                            $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_tipo_transa . "," . $cod_transaccion;
                            $sql_dias_ano = $this->cfpd97->execute("SELECT cfpd97_formula_anual_transa($parametros) as dias_ano;");
                            $dias_ano = $sql_dias_ano[0][0]['dias_ano'];
                            $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_cargo . "," . $cod_tipo_transa . "," . $cod_transaccion;
                            $sql_salario = $this->cfpd97->execute("SELECT cfpd97_salario_integral_asig_ded($parametros) as salario;");
                            $salario = $sql_salario[0][0]['salario'];
                            if ($cod_tran_patron == 400 || $cod_tran_patron == 401) {
                                $salario_aporte = ((($sueldo_basico + $salario) * $dias_ano) / 52);
                                $monto_calculado = (($salario_aporte * $porcentaje) / 100);
                                if ($cla_per != 2 && $dias_ano == 15) {
                                    $sem4 = ($monto_calculado * 2);
                                    $sem5 = ($monto_calculado * 2.5);
                                    $monto_calculado = (($sem4 + $sem5) / 2);
                                }
                                if ($cla_per != 2 && $dias_ano == 30) {
                                    $sem4 = ($monto_calculado * 4);
                                    $sem5 = ($monto_calculado * 5);
                                    $monto_calculado = (($sem4 + $sem5) / 2);
                                }
                            } else {
                                $monto_calculado = ((($salario + $sueldo_basico) * $porcentaje) / 100);
                            }

                            $anos_escenario = 0;
                            $denominacion_transa = $this->cfpd97->execute("SELECT denominacion FROM cnmd03_transacciones WHERE cod_tipo_transaccion=$cod_tipo_patron and cod_transaccion=$cod_tran_patron;");
                            $denominacion = $denominacion_transa[0][0]['denominacion'];
                            $partida_asignacion = $this->cfpd97->execute("SELECT cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar FROM cnmd03_partidas WHERE cod_tipo_transaccion=$cod_tipo_patron and cod_transaccion=$cod_tran_patron and clasificacion_personal=$cla_per;");
                            if (!empty($partida_asignacion)) {
                                $partida = $partida_asignacion[0][0]['cod_partida'];
                                $generica = $partida_asignacion[0][0]['cod_generica'];
                                $especifica = $partida_asignacion[0][0]['cod_especifica'];
                                $sub_especifica = $partida_asignacion[0][0]['cod_sub_espec'];
                                $auxiliar = $partida_asignacion[0][0]['cod_auxiliar'];
                            } else {
                                $partida = 401;
                                $generica = 06;
                                $especifica = 96;
                                $sub_especifica = 00;
                                $auxiliar = 0000;
                                if ($cod_transaccion != $ct_apor && $tipo_nomina === $tn) {
                                    echo "<script>alert('NO HA INDICADO LA PARTIDA QUE CANCELARÁ LA TRANSACCIÓN: " . $denominacion . " DE LA NÓMINA : " . $deno_nomi . " ,MIENTRAS TANTO UTILIZARÁ LA PARTIDA: 401-06-96-00-00 OTROS APORTES POR EMPLEADOS')</script>";
                                    $ct_apor = $cod_transaccion;
                                }
                            }

                            $monto_anual = $this->redondeo($monto_calculado * $dias_ano);
                            $monto_anual = $this->Formato1($monto_anual);
                            $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_cargo . "," . $cod_tipo_patron . "," . $cod_tran_patron . "," . $ano . "," . $sector . "," . $programa . "," . $sub_programa . "," . $proyecto . "," . $actividad . "," . $partida . "," . $generica . "," . $especifica . "," . $sub_especifica . "," . $auxiliar . "," . $monto_calculado . "," . $monto_anual . "," . $dias_cobro . "," . $cla_per . "," . $dias_ano . "," . $dias_escenario . "," . $anos_escenario;
                            $update_cargo = $this->cfpd97->execute("SELECT update_cfpd97_transacciones($parametros);");
                        }//foreach
                    } else {

                        if ($tipo_nomina != $es_apor) {
                            $j++;
                            $ano_esc[$j][0] = $deno_nomi;
                            $ano_esc[$j][1] = " DEDUCCIONES APORTES PATRONALES EL PORCENTAJE CALCULO SEGÚN EL SALARIO";

                            $es_apor = $tipo_nomina;
                        }
                    } // FIN ACTUALIZA DEDUCCIONES APORTES PATRONALES EL PORCENTAJE CALCULO SEGÚN EL SALARIO
                }//FIN CARGOS

                $this->set("ano_escenarios", $ano_esc);



   		echo "<script>
        	$('procesar').value='Proceso Finalizado';
        	$('procesar').disabled='true';
        	$('reportes').style.visibility='visible';
        </script>";
            }// FIN EMPTY
        } else {
            $this->set('errorMessage', 'No se puede correr este proceso mientras se esta ejecutando el ejercicio que se esta formulando.');
        }
    }

//fin ejecutar_proceso



    function ejecutar_proceso_partidas($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $tipo_nomina, $cod_cargo, $cod_tipo_transa, $cod_transaccion, $ano, $sector, $programa, $sub_programa, $proyecto, $actividad, $partida, $generica, $especifica, $sub_especifica, $auxiliar, $monto_calculado, $monto_anual, $dias_cobro, $cla_per, $dias_ano, $dias_escenario, $anos_escenario, $tn, $veri, $deno_nomi) {

        $denominacion_transa = $this->cfpd97->execute("SELECT denominacion FROM cnmd03_transacciones WHERE cod_tipo_transaccion=$cod_tipo_transa and cod_transaccion=$cod_transaccion;");
        $denominacion = $denominacion_transa[0][0]['denominacion'];

        $partida_asignacion = $this->cfpd97->execute("SELECT cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar FROM cnmd03_partidas WHERE cod_tipo_transaccion=$cod_tipo_transa and cod_transaccion=$cod_transaccion and clasificacion_personal=$cla_per;");
        if (!empty($partida_asignacion)) {

            $partida = $partida_asignacion[0][0]['cod_partida'];
            $generica = $partida_asignacion[0][0]['cod_generica'];
            $especifica = $partida_asignacion[0][0]['cod_especifica'];
            $sub_especifica = $partida_asignacion[0][0]['cod_sub_espec'];
            $auxiliar = $partida_asignacion[0][0]['cod_auxiliar'];
        } else {

            $partida = 401;
            $generica = 01;
            $especifica = 01;
            $sub_especifica = 99;
            $auxiliar = 0000;

            if ($cod_transaccion != $veri && $tipo_nomina === $tn) {
                echo "<script>alert('NO HA INDICADO LA PARTIDA QUE CANCELARÁ LA TRANSACCIÓN:    " . $cod_transaccion . " - " . $denominacion . "      DE LA NÓMINA : " . $deno_nomi . " ,MIENTRAS TANTO UTILIZARÁ LA PARTIDA: 401-01-01-99-00 OTRAS RETRIBUCIONES')</script>";
                $veri = $cod_transaccion;
            }
        }

        $monto_anual = $this->redondeo($monto_calculado * $dias_ano);
        $monto_anual = $this->Formato1($monto_anual);
        $parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_cargo . "," . $cod_tipo_transa . "," . $cod_transaccion . "," . $ano . "," . $sector . "," . $programa . "," . $sub_programa . "," . $proyecto . "," . $actividad . "," . $partida . "," . $generica . "," . $especifica . "," . $sub_especifica . "," . $auxiliar . "," . $monto_calculado . "," . $monto_anual . "," . $dias_cobro . "," . $cla_per . "," . $dias_ano . "," . $dias_escenario . "," . $anos_escenario;
        $update_cargo = $this->cfpd97->execute("SELECT update_cfpd97_transacciones($parametros);");
    }

// ejecutar_proceso_partidas



    function formulacion_automatica() {
        $this->layout = "ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');

        $sql_formulacion_automatico = "SELECT * FROM v_cfpd97_transa_resumen_formulacion WHERE cod_presi=$cod_presi AND cod_entidad=$cod_entidad AND cod_tipo_inst=$cod_tipo_inst AND cod_inst=$cod_inst AND cod_dep=$cod_dep";
        $formulacion_automatico = $this->cfpd97->execute($sql_formulacion_automatico);
        if (!empty($formulacion_automatico)) {
            foreach ($formulacion_automatico as $formulacion) {
                $ano = $formulacion[0]['ano'];
                $sector = $formulacion[0]['cod_sector'];
                $programa = $formulacion[0]['cod_programa'];
                $sub_programa = $formulacion[0]['cod_sub_prog'];
                $proyecto = $formulacion[0]['cod_proyecto'];
                $actividad = $formulacion[0]['cod_activ_obra'];
                $partida = $formulacion[0]['cod_partida'];
                $generica = $formulacion[0]['cod_generica'];
                $especifica = $formulacion[0]['cod_especifica'];
                $sub_especifica = $formulacion[0]['cod_sub_espec'];
                $auxiliar = $formulacion[0]['cod_auxiliar'];
                $monto_anual = $formulacion[0]['monto_anual'];


                $Cpresi = $cod_presi;
                $Centidad = $cod_entidad;
                $Ctipo_inst = $cod_tipo_inst;
                $Cinst = $cod_inst;
                $Cdep = $cod_dep;
                $Csector = $sector;
                $Cprograma = $programa;
                $Csubprograma = $sub_programa;
                $Cproyecto = $proyecto;
                $Cactividad = $actividad;
                $Cpartida = $partida;
                $Cgenerica = $generica;
                $Cespecifica = $especifica;
                $Csub_espec = $sub_especifica;
                $Cauxiliar = $auxiliar;
                $Ctipo_gasto = 1;
                $tipo_presupuesto = 1;
                $asignacion_anual = $monto_anual;
                $clasificacion_recurso_extra = 0;

                $sql_delete_cfpd05 = "DELETE from cfpd05 WHERE cod_presi=$Cpresi AND cod_entidad=$Centidad AND cod_tipo_inst=$Ctipo_inst AND cod_inst=$Cinst AND cod_dep=$Cdep AND ano=$ano AND cod_sector=$Csector AND cod_programa=$Cprograma AND cod_sub_prog=$Csubprograma AND cod_proyecto=$Cproyecto AND cod_activ_obra=$Cactividad AND cod_partida=$Cpartida AND cod_generica=$Cgenerica AND cod_especifica=$Cespecifica AND cod_sub_espec=$Csub_espec AND cod_auxiliar=$Cauxiliar";
                $this->cfpd97->execute($sql_delete_cfpd05);

                $insert_cfpd05_formulacion = "INSERT INTO cfpd05 (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,cod_tipo_gasto,tipo_presupuesto,asignacion_anual,aumento_traslado_anual,disminucion_traslado_anual,credito_adicional_anual,rebaja_anual,compromiso_anual,causado_anual,pagado_anual,asignacion_ene,aumento_traslado_ene,disminucion_traslado_ene,credito_adicional_ene,rebaja_ene,compromiso_ene,causado_ene,pagado_ene,asignacion_feb,aumento_traslado_feb,disminucion_traslado_feb,credito_adicional_feb,rebaja_feb,compromiso_feb,causado_feb,pagado_feb,asignacion_mar,aumento_traslado_mar,disminucion_traslado_mar,credito_adicional_mar,rebaja_mar,compromiso_mar,causado_mar,pagado_mar,asignacion_abr,aumento_traslado_abr,disminucion_traslado_abr,credito_adicional_abr,rebaja_abr,compromiso_abr,causado_abr,pagado_abr,asignacion_may,aumento_traslado_may,disminucion_traslado_may,credito_adicional_may,rebaja_may,compromiso_may,causado_may,pagado_may,asignacion_jun,aumento_traslado_jun,disminucion_traslado_jun,credito_adicional_jun,rebaja_jun,compromiso_jun,causado_jun,pagado_jun,asignacion_jul,disminucion_traslado_jul,credito_adicional_jul,rebaja_jul,compromiso_jul,causado_jul,pagado_jul,asignacion_ago,aumento_traslado_ago,disminucion_traslado_ago,credito_adicional_ago,rebaja_ago,compromiso_ago,causado_ago,pagado_ago,asignacion_sep,aumento_traslado_sep,disminucion_traslado_sep,credito_adicional_sep,rebaja_sep,compromiso_sep,causado_sep,pagado_sep,asignacion_oct,aumento_traslado_oct,disminucion_traslado_oct,credito_adicional_oct,rebaja_oct,compromiso_oct,causado_oct,pagado_oct,asignacion_nov,aumento_traslado_nov,disminucion_traslado_nov,credito_adicional_nov,rebaja_nov,compromiso_nov,causado_nov,pagado_nov,asignacion_dic,aumento_traslado_dic,disminucion_traslado_dic,credito_adicional_dic,rebaja_dic,compromiso_dic,causado_dic,pagado_dic,precompromiso_congelado,precompromiso_requisicion,precompromiso_obras,precompromiso_fondo_avance,clasificacion_recurso_extra)
		                    VALUES ($Cpresi,$Centidad,$Ctipo_inst,$Cinst,$Cdep,$ano,$Csector,$Cprograma,$Csubprograma,$Cproyecto,$Cactividad,$Cpartida,$Cgenerica,$Cespecifica,$Csub_espec,$Cauxiliar,$Ctipo_gasto,$tipo_presupuesto,$asignacion_anual,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,$clasificacion_recurso_extra)";
                $insert_cfpd05 = $this->cfpd97->execute($insert_cfpd05_formulacion);
            }// foreach
        }// if
    }

//formulacion_automatico



    function reporte_transa_detalles(){
        $this->layout = "pdf";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');

        $sql_cfpd97_detalles = "SELECT * FROM v_cfpd97_transa_detalles WHERE cod_presi=$cod_presi AND cod_entidad=$cod_entidad AND cod_tipo_inst=$cod_tipo_inst AND cod_inst=$cod_inst AND cod_dep=$cod_dep";
        $detalles = $this->cfpd97->execute($sql_cfpd97_detalles);

        $this->set('vista_detalles', $detalles);
        $this->set('titulo_reporte', 'DETALLES CALCULOS AUTOMATICOS');


        echo "<script>
         $('emision_detalle').disabled = false;
         $('generar_formulacion').disabled = false;
          </script>";
    }

//transa_detalles




    function reporte_transa_resumen_formulacion(){
        $this->layout = "pdf";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');


        $sql_cfpd97_formulacion = "SELECT * FROM v_cfpd97_transa_resumen_formulacion WHERE cod_presi=$cod_presi AND cod_entidad=$cod_entidad AND cod_tipo_inst=$cod_tipo_inst AND cod_inst=$cod_inst AND cod_dep=$cod_dep";
        $formulacion = $this->cfpd97->execute($sql_cfpd97_formulacion);
        $this->set('vista_formulacion', $formulacion);
        $this->set('titulo_reporte', 'CALCULOS_PARA_FORMULACION');

        echo "<script>
         $('emision_formulacion').disabled = false;
         $('generar_formulacion').disabled = false;
          </script>";
    }

//transa_resumen_formulacion



    function reporte_transa_resumen_general(){
        $this->layout = "pdf";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');


        $sql_cfpd97_general = "SELECT * FROM v_cfpd97_transa_resumen_general WHERE cod_presi=$cod_presi AND cod_entidad=$cod_entidad AND cod_tipo_inst=$cod_tipo_inst AND cod_inst=$cod_inst AND cod_dep=$cod_dep";
        $general = $this->cfpd97->execute($sql_cfpd97_general);
        $this->set('vista_general', $general);
        $this->set('titulo_reporte', 'RESUMEN_GENERAL');


        echo "<script>
         $('resumen_general').disabled = false;
         $('generar_formulacion').disabled = false;
          </script>";
    }

//transa_resumen_general



    function reporte_transa_resumen_nomina(){
        $this->layout = "pdf";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');


        $sql_cfpd97_nomina = "SELECT * FROM v_cfpd97_transa_resumen_nomina WHERE cod_presi=$cod_presi AND cod_entidad=$cod_entidad AND cod_tipo_inst=$cod_tipo_inst AND cod_inst=$cod_inst AND cod_dep=$cod_dep";
        $nomina = $this->cfpd97->execute($sql_cfpd97_nomina);
        $this->set('vista_nomina', $nomina);
        $this->set('titulo_reporte', 'RESUMEN_NOMINA');

        echo "<script>
         $('resumen_nomina').disabled = false;
         $('generar_formulacion').disabled = false;
          </script>";
    }

//transa_resumen_nomina
}

//fin class
?>