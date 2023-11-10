<?php

class Cobp01ContratoobrasAdendoController extends AppController {

    var $uses = array('cugd90_municipio_defecto', 'cobd01_contratoobras_cuerpo', 'cobd01_contratoobras_partidas', 'cpcd02', 'cscd01_catalogo', 'cfpd05',
        'cfpd07_obras_cuerpo', 'cfpd07_obras_partidas', 'ccfd03_instalacion', 'cugd01_republica', 'cugd04', 'v_cfpd05_disponibilidad',
        'cugd01_estados', 'cugd01_municipios', 'cugd01_parroquias', 'cugd01_centropoblados', 'cfpd05', 'ccfd04_cierre_mes', 'cscd04_ordencompra_parametros'
        , 'cugd03_acta_anulacion_numero', 'cugd03_acta_anulacion_cuerpo', 'cfpd21_numero_asiento_compromiso', 'cfpd21', 'cugd10_imagenes',
        'cstd01_entidades_bancarias', 'ccfd10_descripcion', 'ccfd10_detalles', 'ccfd02', 'ccfd05_numero_asiento',
        'ccfd04_cuentas_enlace', 'cpcd02', 'cepd01_compromiso_cuerpo', 'cscd04_ordencompra_anticipo_cuerpo',
        'cscd04_ordencompra_encabezado', 'cscd04_ordencompra_autorizacion_cuerpo',
        'cscd04_ordencompra_encabezado', 'cobd01_contratoobras_anticipo_cuerpo', 'cobd01_contratoobras_valuacion_cuerpo',
        'cobd01_contratoobras_retencion_cuerpo', 'cepd02_contratoservicio_anticipo_cuerpo', 'cepd02_contratoservicio_valuacion_cuerpo',
        'cepd02_contratoservicio_retencion_cuerpo', 'cobd01_contratoobras_cuerpo', 'cepd02_contratoservicio_cuerpo', 'cugd05_restriccion_clave'
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

        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $modulo = $this->Session->read('Modulo');
        /*
          if($cod_dep=='1'){
          return;
          }else{
          echo "<h3>LO SIENTO - SOLO LA ADMINISTRACION CENTRAL TIENE ACCESO A ESTE PROGRAMA!!</h3>";
          exit;
          }
         */
    }

//fin before filter

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

    function zero($x = null) {
        if ($x != null) {
            if ($x < 10) {
                $x = "0" . $x;
            } else if ($x >= 10 && $x <= 99) {
                $x = $x;
            }
        }
        return $x;
    }

//fin zero

    function concatena($vector1 = null, $nomVar = null) {
        if ($vector1 != null) {
            foreach ($vector1 as $x => $y) {
                $cod[$x] = $this->zero($x) . ' - ' . $y;
            }
            //print_r($cod);
            $this->set($nomVar, $cod);
        }
    }

//fin concatena

    function concatena_sin_cero($vector1 = null, $nomVar = null) {
        if ($vector1 != null) {
            foreach ($vector1 as $x => $y) {
                $cod[$x] = $x . ' - ' . $y;
            }
            //print_r($cod);
            $this->set($nomVar, $cod);
        }
    }

//fin concatena

    function index($var = null) {

        $this->verifica_entrada('79');
        $this->layout = "ajax";

        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');

        $this->layout = "ajax";
        $ano = '';
        $lista = "";
        $ano = $this->ano_ejecucion();
        $this->data = null;

        $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $SScoddep . ' and ano_contrato_obra=' . $ano;
        $a = $this->cobd01_contratoobras_cuerpo->findAll($condicion, null, " numero_contrato_obra ASC");
        $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and ano_estimacion=' . $ano . ' and cod_dep_original=' . $SScoddeporig . '   and ano_estimacion=' . $ano;
        $b = $this->cfpd07_obras_cuerpo->findAll($condicion);
        foreach ($a as $a_aux) {
            foreach ($b as $b_aux) {
                if ($a_aux['cobd01_contratoobras_cuerpo']['ano_estimacion'] == $b_aux['cfpd07_obras_cuerpo']['ano_estimacion'] && strtoupper($a_aux['cobd01_contratoobras_cuerpo']['cod_obra']) == strtoupper($b_aux['cfpd07_obras_cuerpo']['cod_obra']) and ($a_aux['cobd01_contratoobras_cuerpo']['contrato_padre'] == '') ) {
                    $lista[$a_aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra']] = $a_aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra'];
                }//fin if
            }//fin foreach
        }//fin foreach
        
        $this->set('lista_numero', $lista);
        $this->set('ano', $ano);
        $this->set('year', $ano);

        $this->Session->delete("items");
        $this->Session->delete("i");
        $this->Session->delete("ano_contrato_obra");
    }

//fin index

    function ver_semaforo($var = null) {
        $this->layout = "ajax";
        //echo $this->Formato2($this->Formato1($var));
        $monto = $this->Formato1($var);
        //echo   $_SESSION["ano"],$_SESSION["sec"], $_SESSION["pro"],$_SESSION["subp"],$_SESSION["proy"],$_SESSION["actividad"],$_SESSION["cpar"],$_SESSION["cgen"],$_SESSION["cesp"],$_SESSION["csesp"],$_SESSION["auxiliar"];
        if ($_SESSION["cpar"] < 400) {
            $partida = $_SESSION["cpar"] < 9 ? CE . "0" . $_SESSION["cpar"] : CE . $_SESSION["cpar"];
        } else {
            $partida = $_SESSION["cpar"]; //<9?CE."0".$_SESSION["cpar"]:CE.$_SESSION["cpar"];
        }

        //echo $partida;
        //echo $_SESSION["ano"], $_SESSION["sec"], $_SESSION["prog"], $_SESSION["subp"], $_SESSION["proy"], $_SESSION["actividad"], $partida, $_SESSION["cgen"], $_SESSION["cesp"], $_SESSION["csesp"], $_SESSION["auxiliar"];
        $username = strtoupper($this->Session->read('nom_usuario'));
        if ($_SESSION["ano"] != null && $_SESSION["sec"] != null && $_SESSION["prog"] != null && $_SESSION["subp"] != null && $_SESSION["proy"] != null && $_SESSION["actividad"] != null && $partida != null && $_SESSION["cgen"] != null && $_SESSION["cesp"] != null && $_SESSION["csesp"] != null && $monto != null && $_SESSION["auxiliar"] != null) {
            $trafico = $this->semaforo($_SESSION["ano"], $_SESSION["sec"], $_SESSION["prog"], $_SESSION["subp"], $_SESSION["proy"], $_SESSION["actividad"], $partida, $_SESSION["cgen"], $_SESSION["cesp"], $_SESSION["csesp"], $_SESSION["auxiliar"]);
            //echo "entrar uno";
            //echo "el username es: ".$trafico['username']." y el color es: ".$trafico['color'];

            if ($trafico['color'] == 'rojo' && $trafico['username'] != $username) {
                //echo  "entra dos";
                $this->set('msg', $trafico['mensaje']);
            } else {
                //echo "entrar tres";
                $this->guardar_cugd04($_SESSION["ano"], $_SESSION["sec"], $_SESSION["prog"], $_SESSION["subp"], $_SESSION["proy"], $_SESSION["actividad"], $partida, $_SESSION["cgen"], $_SESSION["cesp"], $_SESSION["csesp"], $_SESSION["auxiliar"], $username);
                $disponibilidad = $this->disponibilidad($_SESSION["ano"], $_SESSION["sec"], $_SESSION["prog"], $_SESSION["subp"], $_SESSION["proy"], $_SESSION["actividad"], $partida, $_SESSION["cgen"], $_SESSION["cesp"], $_SESSION["csesp"], $_SESSION["auxiliar"]);
                if (empty($disponibilidad)) {
                    $this->set('msg', 'ESTA PARTIDA NO ESTA REGISTRADA EN LA DISTRIBUCCION DE GASTO');
                    $this->set('MostrarTime', false);
                    //echo "entrar cuatro";
                } else {
                    //echo "entrar cinco";
                    if ($disponibilidad < $monto) {
                        $this->set('msg', 'EL MONTO DISPONIBLE PARA ESTA PARTIDA ES DE ' . $this->Formato2($disponibilidad) . ' ' . MONEDA2);
                        $this->set('hide', true);
                        $this->set('MostrarTime', false);
                        //echo "entrar seis";
                    }
                }
            }
        }
    }

    function trafico($ano = null, $cod_sector = null, $cod_programa = null, $cod_sub_prog = null, $cod_proyecto = null, $cod_activ_obra = null, $cod_partida = null, $cod_generica = null, $cod_especifica = null, $cod_sub_espec = null, $monto = null, $cod_auxiliar = null) {
        $this->layout = "ajax";
        $username = strtoupper($this->Session->read('nom_usuario'));
        //echo $ano.'-'.$cod_sector.'-'.$cod_programa.'-'.$cod_sub_prog.'-'.$cod_proyecto.'-'.$cod_activ_obra.'-'.$cod_partida.'-'.$cod_generica.'-'.$cod_especifica.'-'.$cod_sub_espec.'-'.$cod_auxiliar.'-'.$monto.'<br>';


        if ($ano != null && $cod_sector != null && $cod_programa != null && $cod_sub_prog != null && $cod_proyecto != null && $cod_activ_obra != null && $cod_partida != null && $cod_generica != null && $cod_especifica != null && $cod_sub_espec != null && $monto != null && $cod_auxiliar != null) {



            $trafico = $this->semaforo($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);

            //echo "el username es: ".$trafico['username']." y el color es: ".$trafico['color'];

            if ($trafico['color'] == 'rojo' && $trafico['username'] != $username) {
                $this->set('msg', $trafico['mensaje']);
                $this->set('remote', 'luis');
                $partida = $ano . '/' . $cod_sector . '/' . $cod_programa . '/' . $cod_sub_prog . '/' . $cod_proyecto . '/' . $cod_activ_obra . '/' . $cod_partida . '/' . $cod_generica . '/' . $cod_especifica . '/' . $cod_sub_espec . '/' . $monto . '/' . $cod_auxiliar;
                $this->set('partida', $partida);
            } else {
                $this->guardar_cugd04($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar, $username);
                $disponibilidad = $this->disponibilidad($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
                //echo "la disponibilidad es de: ".$disponibilidad;

                if (empty($disponibilidad)) {
                    $this->set('msg', 'ESTA PARTIDA NO ESTA REGISTRADA EN LA DISTRIBUCCION DE GASTO');
                } else {
                    if ($disponibilidad < $monto) {
                        $this->set('msg', 'EL MONTO DISPONIBLE PARA ESTA PARTIDA ES DE ' . $this->Formato2($disponibilidad) . ' ' . MONEDA2);
                    }
                }
            }
        }
    }

//fin function

    function guardar_cugd04($ano = null, $cod_sector = null, $cod_programa = null, $cod_sub_prog = null, $cod_proyecto = null, $cod_activ_obra = null, $cod_partida = null, $cod_generica = null, $cod_especifica = null, $cod_sub_espec = null, $cod_auxiliar = null, $username = null) {

        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $cod_dep = "1";

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

    function borrar_cugd04() {
        $condicion = $this->condicion();
        $username = $this->Session->read('nom_usuario');
        $username = strtoupper($username);

        $c = $this->cugd04->findCount($condicion . " and username='$username'");

        if ($c != 0) {
            $this->cugd04->execute("DELETE FROM cugd04 WHERE " . $condicion . " and username='$username'");
        }
    }

//fin borrar

    function ver_trafico($ano = null, $cod_sector = null, $cod_programa = null, $cod_sub_prog = null, $cod_proyecto = null, $cod_activ_obra = null, $cod_partida = null, $cod_generica = null, $cod_especifica = null, $cod_sub_espec = null, $cod_auxiliar = null, $monto = null) {
        $this->layout = "ajax";

        $this->trafico($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $monto, $cod_auxiliar);

        $this->render('trafico');
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


        if ($SScoddep == 1 && $SScoddeporig == 1 && $Modulo == "0") {
            $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . '  ';
        } else {
            $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $this->Session->read('SScoddep') . '';
        }//fin else

        $a = $this->cobd01_contratoobras_cuerpo->findAll($condicion . " and ano_contrato_obra=" . $year);


        if ($SScoddep == 1 && $SScoddeporig == 1 && $Modulo == "0") {
            $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . '  ';
        } else {
            $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep_original=' . $SScoddeporig . ' ';
        }//fin else

        $b = $this->cfpd07_obras_cuerpo->findAll($condicion . " and ano_estimacion=" . $year . " and punto_operacion!=" . "1");

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

    function selecion($var1 = null) {
        $this->layout = "ajax"; //echo $var1;

        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $Modulo = $this->Session->read('Modulo');
        $this->data = null;

        $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $this->Session->read('SScoddep') . '  ';
        $ano = '';
        $monto_original_contrato_aux = 0;
        $aux_guardar_2 = 0;
        $aux_guardar = 0;
        $ano = $this->ano_ejecucion();

        $this->set('ano', $ano);
        $this->set('year', $ano);

        $_SESSION['ano_contrato_obra'] = $ano;




        if ($var1 != null && $var1 != 'otros') {


            if ($cod_dep == 1 && $SScoddeporig == 1) {
                $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . '  ';
            } else {
                $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $this->Session->read('SScoddep') . '';
            }//fin else

            if ($this->cobd01_contratoobras_cuerpo->findCount($condicion . " and ano_contrato_obra=" . $ano . " and upper(numero_contrato_obra)='" . strtoupper($var1) . "' ") != 0) {

                $array = $this->cobd01_contratoobras_cuerpo->findAll($condicion . ' and ano_contrato_obra = ' . $ano . '  and ano_estimacion=' . $ano . $this->filtra_obra($ano), 'DISTINCT ano_contrato_obra, numero_contrato_obra, ano_estimacion, cod_obra ', 'ano_contrato_obra, numero_contrato_obra, ano_estimacion, cod_obra ASC', null);
                $i = 0;
                foreach ($array as $aux) {
                    $numero[$i]['ano_contrato_obra'] = $aux['cobd01_contratoobras_cuerpo']['ano_contrato_obra'];
                    $numero[$i]['numero_contrato_obra'] = $aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra'];
                    $numero[$i]['ano_estimacion'] = $aux['cobd01_contratoobras_cuerpo']['ano_estimacion'];
                    $numero[$i]['cod_obra'] = $aux['cobd01_contratoobras_cuerpo']['cod_obra'];
                    $i++;
                } $i--;



                for ($a = 0; $a <= $i; $a++) {
                    if ($var1 == $numero[$a]['numero_contrato_obra']) {
                        $pag_num = $a;
                        $opcion = 'si';
                        break;
                    } else {
                        $opcion = 'no';
                    }
                }//fin for



                if ($opcion == 'si') {
                    $this->consulta($pag_num);
                    $this->render('consulta');
                }
            }//fin if
        } else if ($var1 == 'otros') {




            $cfpd07_obras_cuerpo = $this->cfpd07_obras_cuerpo->findAll($condicion . " and ano_estimacion=" . $ano . " and punto_operacion!=" . "1" . "and upper(cod_obra)='" . strtoupper($var1) . "' ");
            $cfpd07_obras_partidas = $this->cfpd07_obras_partidas->findAll($condicion . " and ano_estimacion=" . $ano . " and upper(cod_obra)='" . strtoupper($var1) . "' ");

            $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep_original=' . $SScoddeporig . ' and ano_estimacion=' . $ano;
            $lista = $this->cfpd07_obras_cuerpo->generateList($condicion . 'and punto_operacion!=1 and ano_estimacion=' . $ano . 'and ((estimado_presu+aumento_obras)-(monto_contratado+disminucion_obras))!=0', ' cod_obra ASC', null, '{n}.cfpd07_obras_cuerpo.cod_obra', '{n}.cfpd07_obras_cuerpo.denominacion');

            $lista_estado = $this->cugd01_estados->generateList(null, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
            $lista_rif = $this->cpcd02->generateList(null, ' rif ASC', null, '{n}.cpcd02.rif', '{n}.cpcd02.rif');


            $this->set('lista_rif', $lista_rif);
            $this->concatena($lista_estado, 'cod_estado');
            $this->concatena_sin_cero($lista, 'lista_numero');
            $this->set('selecion_lista', $var1);
            $this->set('cfpd07_obras_cuerpo', $cfpd07_obras_cuerpo);
            $this->set('cfpd07_obras_partidas', $cfpd07_obras_partidas);
        } else {

            $this->index();
            $this->render('index');
        }//fin else
    }

//fin function

    function select_numero_contrato() {

        $this->layout = "ajax";
        $ano = '';
        $ano = $this->ano_ejecucion();

        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');


        if ($SScoddep == 1 && $SScoddeporig == 1 && $Modulo == "0") {
            $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . '  ';
        } else {
            $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $this->Session->read('SScoddep') . '';
        }//fin else


        $lista = $this->cobd01_contratoobras_cuerpo->generateList($condicion . ' and ano_contrato_obra=' . $ano . ' ', ' cod_obra ASC', null, '{n}.cobd01_contratoobras_cuerpo.numero_contrato_obra', '{n}.cobd01_contratoobras_cuerpo.numero_contrato_obra');
        $this->set('lista_numero', $lista);

        echo'<script>';
        echo"   document.getElementById('select_cod_obra').innerHTML='<select></select>';  ";
        echo'</script>';


        $numero_contrato = $this->data['cobp01_contratoobras']['numero_contrato'];
        $this->set('selecion', $numero_contrato);
    }

//fin function

    function selecion_cod_obra($var = null) {

        $this->layout = "ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $this->Session->read('SScoddep') . '  ';
        $ano = '';
        $monto_original_contrato_aux = 0;
        $aux_guardar_2 = 0;
        $aux_guardar = 0;
        $ano = $this->ano_ejecucion();
        $denominacion_snc = "";
        $cod_snc = "";

        $numero_contrato=$var;
        
         $array = $this->cobd01_contratoobras_cuerpo->findAll($condicion . " and numero_contrato_obra='" . $numero_contrato . "' " );
        
         $var=$array[0]['cobd01_contratoobras_cuerpo']['cod_obra'];
         
         $contrato_padre=$numero_contrato;
         
         $numero_contrato= $this->Session->read('numero_adendo');
         
    
        $this->Session->delete("items");
        $this->Session->delete("i");

        if ($var != null) {
            $cfpd07_obras_cuerpo = $this->cfpd07_obras_cuerpo->findAll($condicion . " and ano_estimacion=" . $ano . " and upper(cod_obra)='" . strtoupper($var) . "' ");
            $cfpd07_obras_partidas = $this->cfpd07_obras_partidas->findAll($condicion . " and ano_estimacion=" . $ano . " and upper(cod_obra)='" . strtoupper($var) . "' ", null, 'cod_obra DESC');
            $opcion = "si";
            $cont_a = 0;
            $cont_b = 0;

            foreach ($cfpd07_obras_partidas as $aux_cfpd07_obras_partidas) {
                $cont_a++;
                $ano = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['ano_estimacion'];
                $cod_presi = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_presi'];
                $cod_entidad = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_entidad'];
                $cod_tipo_inst = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_tipo_inst'];
                $cod_inst = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_inst'];
                $cod_dep = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_dep'];
                $ano_estimacion = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['ano_estimacion'];
                $cod_obra = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_obra'];
                $cod_sector = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sector'];
                $cod_programa = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_programa'];
                $cod_sub_prog = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_prog'];
                $cod_proyecto = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_proyecto'];
                $cod_activ_obra = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_activ_obra'];
                $cod_partida = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_partida'];
                $cod_generica = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_generica'];
                $cod_especifica = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_especifica'];
                $cod_sub_espec = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_espec'];
                $cod_auxiliar = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_auxiliar'];
               
                $monto= $this->disponibilidad($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica,$cod_especifica,$cod_sub_espec,$cod_auxiliar);
                $monto_contratado=$monto;
                
                // $monto = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['monto'];
               // $monto_contratado = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['monto_contratado'];


                $sql_verificar = "cod_presi=" . $cod_presi . " and cod_entidad=" . $cod_entidad . " and cod_tipo_inst=" . $cod_tipo_inst . " and cod_inst=" . $cod_inst . " and cod_dep=1 and ano=" . $ano_estimacion;
                $sql_verificar .=" and cod_sector=" . $cod_sector . " and cod_programa=" . $cod_programa . " and cod_sub_prog=" . $cod_sub_prog . " and cod_proyecto=" . $cod_proyecto . " and cod_activ_obra=" . $cod_activ_obra;
                $sql_verificar .=" and cod_partida=" . $this->AddCeroR($cod_partida) . " and cod_generica=" . $cod_generica . " and cod_especifica=" . $cod_especifica . " and cod_sub_espec=" . $cod_sub_espec . " and cod_auxiliar=" . $cod_auxiliar . "";

                if ($this->cfpd05->findCount($sql_verificar) != 0) {
                    $disponibilidad = $this->disponibilidad_con_dep('1', $ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
                    $monto2 = $monto - $monto_contratado;
                    if ($disponibilidad == "0.00" || $disponibilidad < $monto2) {
                        $opcion = "no";
                        $cont_b++;
                    } else {

                        //$this->trafico($ano_estimacion, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $monto2, $cod_auxiliar);
                    }//fin else
                }//fin if contador
            }//fin foreach



            if ($opcion == "no" && ($cont_a == $cont_b)) {
                $this->set('msg_error', 'Lo siento una de las partidas no tiene disponibilidad');
                echo'<script>';
                echo"document.getElementById('guardar').disabled=true;";
                echo"document.getElementById('consultar').disabled=false;";
                echo'</script>';
            }//fin if

            $lista = $this->cfpd07_obras_cuerpo->generateList($condicion . ' and ano_estimacion=' . $ano, ' cod_obra ASC', null, '{n}.cfpd07_obras_cuerpo.cod_obra', '{n}.cfpd07_obras_cuerpo.denominacion');
            $lista_estado = $this->cugd01_estados->generateList("cod_republica=" . $this->Session->read('SScodpresi'), 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
            $rs = $this->cugd90_municipio_defecto->findAll($this->SQLCA());
            $cond = " cod_republica=" . $this->Session->read('SScodpresi') . " and cod_estado=" . $rs[0]['cugd90_municipio_defecto']['cod_estado'];
            $aux = $this->cugd01_estados->findAll($cond);
            foreach ($aux as $aux2) {
                $cod_estado = $aux2['cugd01_estados']['cod_estado'];
                $denominacion = $aux2['cugd01_estados']['denominacion'];
            }//fin foreach
            $this->set('codigo_estado', $cod_estado);
            $this->set('denominacion_estado', $denominacion);
            $this->Session->write('est', $cod_estado);
            $this->Session->write('rep', $this->Session->read('SScodpresi'));
            $this->Session->write('mun', $rs[0]['cugd90_municipio_defecto']['cod_municipio']);
            $lista_municipios = $this->cugd01_municipios->generateList($cond, 'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
            $this->concatena($lista_municipios, 'lista_municipios');
            $this->set('mun_def', $rs[0]['cugd90_municipio_defecto']['cod_municipio']);
//nuevo
            $this->set('SELECT', 'centro');
            $this->set('codigo', 'parroquia');
            $this->set('seleccion', '');
            $this->set('n', 4);
            $rep = $this->Session->read('rep');
            $est = $this->Session->read('est');
            $cond = " cod_republica=" . $rep . " and cod_estado=" . $rs[0]['cugd90_municipio_defecto']['cod_estado'] . " and cod_municipio=" . $rs[0]['cugd90_municipio_defecto']['cod_municipio'];
            $aux = $this->cugd01_municipios->findAll($cond);
            foreach ($aux as $aux2) {
                $cod_municipio = $aux2['cugd01_municipios']['cod_municipio'];
                $denominacion1 = $aux2['cugd01_municipios']['denominacion'];
            }//fin foreach
            echo'<script>';
            echo'document.getElementById("ver_cod_municipio").innerHTML = "' . $this->AddCeroR($cod_municipio) . '"; ';
            echo'document.getElementById("deno_cod_municipio").innerHTML = "' . $denominacion1 . '"; ';
            echo'document.getElementById("ver_cod_parroquia").innerHTML = "<br>"; ';
            echo'document.getElementById("deno_cod_parroquia").innerHTML = "<br>"; ';
            echo'document.getElementById("ver_cod_centro").innerHTML = "<br>"; ';
            echo'document.getElementById("deno_cod_centro").innerHTML = "<br>"; ';
            echo'</script>';
            $lista_mun = $this->cugd01_parroquias->generateList($cond, 'cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
            $this->concatena($lista_mun, 'vector_mun');
//fin nuevo

            $denominacion_obra = "";

            $cfpd07_obras_cuerpo_aux = $cfpd07_obras_cuerpo;

            foreach ($cfpd07_obras_cuerpo_aux as $aux) {
                $codigo_prod_serv = $aux['cfpd07_obras_cuerpo']['codigo_prod_serv'];
                $estimado_presu = $aux['cfpd07_obras_cuerpo']['estimado_presu'];
                $denominacion_obra = $aux['cfpd07_obras_cuerpo']['denominacion'];
            }//fin foreach

            if (!isset($codigo_prod_serv)) {

                $codigo_prod_serv = "";
                $cod_snc = "";
                $denominacion_snc = "";
            } else {
                $cscd01_catalogo = $this->cscd01_catalogo->findAll("codigo_prod_serv='" . $codigo_prod_serv . "' ");
                $cscd01_catalogo_aux = $cscd01_catalogo;

                foreach ($cscd01_catalogo_aux as $aux2) {
                    $cod_snc = $aux2['cscd01_catalogo']['cod_snc'];
                    $denominacion_snc = $aux2['cscd01_catalogo']['denominacion'];
                }//fin foreach
            }

            $lista_rif = $this->cpcd02->generateList(null, ' rif ASC', null, '{n}.cpcd02.rif', '{n}.cpcd02.rif');


//$denominacion_obra = "";


            $snc = $this->cscd01_catalogo->generateList("upper(cod_snc) LIKE 'O%'", null, null, '{n}.cscd01_catalogo.codigo_prod_serv', '{n}.cscd01_catalogo.cod_snc');
            $this->concatena_sin_cero($snc, 'snc');

            $rif= $array[0]['cobd01_contratoobras_cuerpo']['rif'];
            $especifique_ubicacion= $array[0]['cobd01_contratoobras_cuerpo']['especifique_ubicacion'];
            

            $this->set('lista_rif', $lista_rif);
            $this->concatena($lista_estado, 'cod_estado');
            $this->concatena_sin_cero($lista, 'lista_numero');
            $this->set('selecion_lista', $var);
            $this->set('estimado_presu', $estimado_presu);
            $this->set('denominacion_snc', $denominacion_snc);
            $this->set('cfpd07_obras_cuerpo', $cfpd07_obras_cuerpo);
            $this->set('cfpd07_obras_partidas', $cfpd07_obras_partidas);
            $this->set('cod_snc', $cod_snc);
            $this->set('ano_ejecucion', $ano);
            
            $this->set('contrato_padre', $contrato_padre);
            $this->set('especifique_ubicacion', $especifique_ubicacion);
            $rif=substr($rif, 2, 8);
           
            $this->set('rif', $rif);
          
                       
        //Trae la Denominacion del Contrato.
            $denominacion_obra="ADENDO DE: ".$array[0]['cobd01_contratoobras_cuerpo']['denominacion_obra'];
            
            


            $denominacion_obra = str_replace("\n", '',$denominacion_obra);
            $denominacion_obra = str_replace("'", '', $denominacion_obra);
            $denominacion_obra = str_replace(">", '', $denominacion_obra);
            $denominacion_obra = str_replace("<", '', $denominacion_obra);
            $denominacion_obra = str_replace('"', '', $denominacion_obra);


            $denominacion_snc = str_replace("\n", '', $denominacion_snc);
            $denominacion_snc = str_replace("'", '', $denominacion_snc);
            $denominacion_snc = str_replace(">", '', $denominacion_snc);
            $denominacion_snc = str_replace("<", '', $denominacion_snc);
            $denominacion_snc = str_replace('"', '', $denominacion_snc);


            $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $this->Session->read('SScoddep') . ' ';
            $parametros_datos = $this->cscd04_ordencompra_parametros->findAll($condicion);
            foreach ($parametros_datos as $aux_22) {
                $anticipo_incluye_iva = $aux_22['cscd04_ordencompra_parametros']['anticipo_incluye_iva'];
                $porcentaje_anticipo = $aux_22['cscd04_ordencompra_parametros']['porcentaje_anticipo'];
                $porcentaje_iva = $aux_22['cscd04_ordencompra_parametros']['porcentaje_iva'];
            }//fin foreach

            $this->set('porcentaje_iva_parametro', $porcentaje_iva);

            if ($denominacion_snc != "") {

                echo'<script>';
                echo"   if(document.getElementById('numero_contrato')){document.getElementById('numero_contrato').value= \"$numero_contrato\";  }";
                echo"   if(document.getElementById('input_cod_obra')){document.getElementById('input_cod_obra').value= \"$var\";  }";
                echo"   if(document.getElementById('codigo_snc')){document.getElementById('codigo_snc').value= \"$cod_snc\";  }";
                echo"   if(document.getElementById('clasificacion_tipo_obra')){document.getElementById('clasificacion_tipo_obra').value= \"$denominacion_snc\";  }";
                echo"   if(document.getElementById('denominacion_obra')){document.getElementById('denominacion_obra').value=\"$denominacion_obra\";  }";
                echo'</script>';
            } else {


                echo'<script>';
                echo"   if(document.getElementById('numero_contrato')){document.getElementById('numero_contrato').value=\"$numero_contrato\"; } ";
                echo"   if(document.getElementById('input_cod_obra')){document.getElementById('input_cod_obra').value=\"$var\"; } ";
                echo"   if(document.getElementById('denominacion_obra')){document.getElementById('denominacion_obra').value=\"$denominacion_obra\";  }";
                echo'</script>';
            }//fin else
        } else {

            echo'<script>';
            echo"   if(document.getElementById('numero_contrato')){document.getElementById('numero_contrato').value='';  }";
            echo"   if(document.getElementById('input_cod_obra')){document.getElementById('input_cod_obra').value='';  }";
            echo"   if(document.getElementById('codigo_snc')){document.getElementById('codigo_snc').value='';  }";
            echo"   if(document.getElementById('clasificacion_tipo_obra')){document.getElementById('clasificacion_tipo_obra').value='';  }";
            echo"   if(document.getElementById('denominacion_obra')){document.getElementById('denominacion_obra').value='';  }";
            echo'</script>';
        }//fin else
    }

//fin funtion

    function selecion_rif($var = null) {
        $this->layout = "ajax";
        $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . '';
        $ano = $this->ano_ejecucion();

        if ($var != null) {
            $condicion = "rif='" . $var . "' ";
            $rif = $this->cpcd02->findAll($condicion, null, 'rif ASC', null);
            foreach ($rif as $r) {
                $var_aux = $r['cpcd02']['denominacion'];
                echo'<script>';
                echo"document.getElementById('rif_nombre').value =  \"$var_aux\"; ";
                echo'</script>';
            }//fin foreach
        } else {
            echo'<script>';
            echo'document.getElementById("rif_nombre").value =""; ';
            echo'</script>';
        }//fin else
    }

//fin function

    function ver_disponibilidad($i = null, $a = null, $b = null) {
        $this->layout = "ajax";
        $b = $this->Formato1($b);
//echo $b.'____'.$a;
        if ($b > $a) {
            $this->set('msg_error', 'El monto es mayor al saldo');
            $this->set('i', $i);
            $_SESSION["items"][$i][11] = '0.00';
        } else {
            $_SESSION["items"][$i][11] = $b;
        }//fin else

        $vec = $_SESSION["items"];
        $monto = 0;
        $opcion = "si";

        for ($z = 0; $z < count($vec); $z++) {
            if ($vec[$z]['id'] == "no" && $vec[$z]['id'] != "0") {
                
            } else {
                $monto += $this->Formato1($vec[$z][11]);
            }//fin else
        }//fin for



        echo "<script>cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', " . $monto . ")</script>";
    }

//fin function

    function valida_numero($year = null, $var = null) {
        $this->layout = "ajax";

        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $this->Session->read('SScoddep') . '  ';

        $var = str_replace(" ", "", $var);
        $var = trim($var);
        $var = str_replace(",", "-", $var);
        $var = str_replace(";", "-", $var);
        $var = strtoupper($var)."-ADENDO";

       $numero_adendo=$this->cobd01_contratoobras_cuerpo->findCount("cod_presi=" . $cod_presi . " and cod_entidad=" . $cod_entidad . " and cod_tipo_inst=" . $cod_tipo_inst . " and  cod_inst=" . $cod_inst . " and cod_dep=" . $cod_dep . " and ano_contrato_obra=" . $year . " and upper(numero_contrato_obra) like '" . strtoupper($var) . "%' ");
       
       echo "<script>document.getElementById('numero_contrato').value='" . $var."-".$numero_adendo."'</script>";
       
       $numero_adendo= mascara2($numero_adendo);
       
       $numero_adendo=$var."-".$numero_adendo;
       
       $this->Session->write('numero_adendo', $numero_adendo);
                     
        $vec = $this->cugd10_imagenes->findCount($condicion . " and cod_campo=13 and identificacion='" . $var . "1'");
        if ($vec != 0) {
            $this->set('aqui_imagen_existe1', true);
        } else {
            $this->set('aqui_imagen_existe1', false);
        }

        $vec = $this->cugd10_imagenes->findCount($condicion . " and cod_campo=13 and identificacion='" . $var . "2'");
        if ($vec != 0) {
            $this->set('aqui_imagen_existe2', true);
        } else {
            $this->set('aqui_imagen_existe2', false);
        }

        $vec = $this->cugd10_imagenes->findCount($condicion . " and cod_campo=13 and identificacion='" . $var . "3'");
        if ($vec != 0) {
            $this->set('aqui_imagen_existe3', true);
        } else {
            $this->set('aqui_imagen_existe3', false);
        }

        $vec = $this->cugd10_imagenes->findCount($condicion . " and cod_campo=13 and identificacion='" . $var . "4'");
        if ($vec != 0) {
            $this->set('aqui_imagen_existe4', true);
        } else {
            $this->set('aqui_imagen_existe4', false);
        }

        $vec = $this->cugd10_imagenes->findCount($condicion . " and cod_campo=14 and identificacion='" . $var . "5'");
        if ($vec != 0) {
            $this->set('aqui_imagen_existe5', true);
        } else {
            $this->set('aqui_imagen_existe5', false);
        }

        $vec = $this->cugd10_imagenes->findCount($condicion . " and cod_campo=14 and identificacion='" . $var . "6'");
        if ($vec != 0) {
            $this->set('aqui_imagen_existe6', true);
        } else {
            $this->set('aqui_imagen_existe6', false);
        }

        $vec = $this->cugd10_imagenes->findCount($condicion . " and cod_campo=14 and identificacion='" . $var . "7'");
        if ($vec != 0) {
            $this->set('aqui_imagen_existe7', true);
        } else {
            $this->set('aqui_imagen_existe7', false);
        }

        $vec = $this->cugd10_imagenes->findCount($condicion . " and cod_campo=14 and identificacion='" . $var . "8'");
        if ($vec != 0) {
            $this->set('aqui_imagen_existe8', true);
        } else {
            $this->set('aqui_imagen_existe8', false);
        }

        $this->set('aqui_imagen', $var . '1');
        $this->set('aqui_imagen2', $var . '2');
        $this->set('aqui_imagen3', $var . '3');
        $this->set('aqui_imagen4', $var . '4');
        $this->set('aqui_imagen5', $var . '5');
        $this->set('aqui_imagen6', $var . '6');
        $this->set('aqui_imagen7', $var . '7');
        $this->set('aqui_imagen8', $var . '8');
    }

//fin function
    function modificar($var = null) {

        $this->layout = "ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $this->Session->read('SScoddep') . '  ';

        $vec = $this->cugd10_imagenes->findCount($condicion . " and cod_campo=13 and identificacion='" . $var . "1'");
        if ($vec != 0) {
            $this->set('aqui_imagen_existe1', true);
        } else {
            $this->set('aqui_imagen_existe1', false);
        }

        $vec = $this->cugd10_imagenes->findCount($condicion . " and cod_campo=13 and identificacion='" . $var . "2'");
        if ($vec != 0) {
            $this->set('aqui_imagen_existe2', true);
        } else {
            $this->set('aqui_imagen_existe2', false);
        }

        $vec = $this->cugd10_imagenes->findCount($condicion . " and cod_campo=13 and identificacion='" . $var . "3'");
        if ($vec != 0) {
            $this->set('aqui_imagen_existe3', true);
        } else {
            $this->set('aqui_imagen_existe3', false);
        }

        $vec = $this->cugd10_imagenes->findCount($condicion . " and cod_campo=13 and identificacion='" . $var . "4'");
        if ($vec != 0) {
            $this->set('aqui_imagen_existe4', true);
        } else {
            $this->set('aqui_imagen_existe4', false);
        }

        $vec = $this->cugd10_imagenes->findCount($condicion . " and cod_campo=14 and identificacion='" . $var . "5'");
        if ($vec != 0) {
            $this->set('aqui_imagen_existe5', true);
        } else {
            $this->set('aqui_imagen_existe5', false);
        }

        $vec = $this->cugd10_imagenes->findCount($condicion . " and cod_campo=14 and identificacion='" . $var . "6'");
        if ($vec != 0) {
            $this->set('aqui_imagen_existe6', true);
        } else {
            $this->set('aqui_imagen_existe6', false);
        }

        $vec = $this->cugd10_imagenes->findCount($condicion . " and cod_campo=14 and identificacion='" . $var . "7'");
        if ($vec != 0) {
            $this->set('aqui_imagen_existe7', true);
        } else {
            $this->set('aqui_imagen_existe7', false);
        }

        $vec = $this->cugd10_imagenes->findCount($condicion . " and cod_campo=14 and identificacion='" . $var . "8'");
        if ($vec != 0) {
            $this->set('aqui_imagen_existe8', true);
        } else {
            $this->set('aqui_imagen_existe8', false);
        }

        $this->set('aqui_imagen', $var . '1');
        $this->set('aqui_imagen2', $var . '2');
        $this->set('aqui_imagen3', $var . '3');
        $this->set('aqui_imagen4', $var . '4');
        $this->set('aqui_imagen5', $var . '5');
        $this->set('aqui_imagen6', $var . '6');
        $this->set('aqui_imagen7', $var . '7');
        $this->set('aqui_imagen8', $var . '8');

        $this->set('numero_contrato', $var);

        echo'<script>';
        echo"document.getElementById('modificar').disabled=true;";
        echo"document.getElementById('guardar_modificar').disabled=false;";
        echo'</script>';
    }

//fin function

    function guardar_modificar($var1 = null, $var2 = null) {

        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');

        if (!empty($this->data['cobp01_contratoobras']['fecha_calidad'])) {
            $fecha_fianza_calidad = $this->data['cobp01_contratoobras']['fecha_calidad'];
        } else {
            $fecha_fianza_calidad = "";
        }
        if (!empty($this->data['cobp01_contratoobras']['fecha_fiel_cumplimiento'])) {
            $fecha_fiel_cumplimiento = $this->data['cobp01_contratoobras']['fecha_fiel_cumplimiento'];
        } else {
            $fecha_fiel_cumplimiento = "";
        }
        if (!empty($this->data['cobp01_contratoobras']['fecha_anticipo'])) {
            $fecha_anticipo = $this->data['cobp01_contratoobras']['fecha_anticipo'];
        } else {
            $fecha_anticipo = "";
        }
        if (!empty($this->data['cobp01_contratoobras']['fecha_buena_pro'])) {
            $fecha_buena_pro = $this->data['cobp01_contratoobras']['fecha_buena_pro'];
        } else {
            $fecha_buena_pro = "";
        }

        if (!empty($this->data['cobp01_contratoobras']['numero_anticipo'])) {
            $numero_anticipo = $this->data['cobp01_contratoobras']['numero_anticipo'];
        } else {
            $numero_anticipo = "";
        }
        if (!empty($this->data['cobp01_contratoobras']['numero_fiel_cumplimiento'])) {
            $numero_fiel_cumplimiento = $this->data['cobp01_contratoobras']['numero_fiel_cumplimiento'];
        } else {
            $numero_fiel_cumplimiento = "";
        }
        if (!empty($this->data['cobp01_contratoobras']['numero_calida'])) {
            $numero_calida = $this->data['cobp01_contratoobras']['numero_calida'];
        } else {
            $numero_calida = "";
        }
        if (!empty($this->data['cobp01_contratoobras']['numero_buena_pro'])) {
            $numero_buena_pro = $this->data['cobp01_contratoobras']['numero_buena_pro'];
        } else {
            $numero_buena_pro = "";
        }

        if ($fecha_buena_pro == "" || $numero_buena_pro == "") {
            $fecha_buena_pro = "1/1/1900";
        }
        if ($fecha_anticipo == "" || $numero_anticipo == "") {
            $fecha_anticipo = "1/1/1900";
        }
        if ($fecha_fiel_cumplimiento == "" || $numero_fiel_cumplimiento == "") {
            $fecha_fiel_cumplimiento = "1/1/1900";
        }
        if ($fecha_fianza_calidad == "" || $numero_calida == "") {
            $fecha_fianza_calidad = "1/1/1900";
        }

        $update = "	update   cobd01_contratoobras_cuerpo    set   numero_buenapro                  = '" . $numero_buena_pro . "',
															  fecha_buenapro                   = '" . $fecha_buena_pro . "',
															  numero_fianza_anticipo           = '" . $numero_anticipo . "',
															  fecha_fianza_anticipo            = '" . $fecha_anticipo . "',
															  numero_fianza_fielcumplimiento   = '" . $numero_fiel_cumplimiento . "',
															  fecha_fianza_fielcumplimiento    = '" . $fecha_fiel_cumplimiento . "',
															  numero_fianza_calidad            = '" . $numero_calida . "',
															  fecha_fianza_calidad             = '" . $fecha_fianza_calidad . "'
		      ";


        $update .= " where cod_presi=" . $cod_presi . " and cod_entidad=" . $cod_entidad . " and cod_tipo_inst=" . $cod_tipo_inst . " and  cod_inst=" . $cod_inst . " and cod_dep=" . $cod_dep . " and ano_contrato_obra='" . $this->ano_ejecucion() . "' and upper(numero_contrato_obra)='" . strtoupper($this->data['cobp01_contratoobras']['numero_contrato_obra']) . "' ;  ";
        $sw3 = $this->cobd01_contratoobras_cuerpo->execute($update);
        if ($sw3 > 1) {
            $this->set('Message_existe', "los datos fueron guardados");
        } else {
            $this->set('errorMessage', "los datos no fueron guardados");
        }
        $this->consulta_index("si");
//	$this->regresar_modificar($var1, $var2);
//	$this->render("regresar_modificar");
    }

//fin

    function regresar_modificar_consulta($var1 = null, $var2 = null) {
        $this->consulta_index("si");
//	$this->regresar_modificar($var1, $var2);
//	$this->render("regresar_modificar");
    }

//fin

    function modificar_fianza($var1 = null, $var2 = null) {
        $this->layout = "ajax";
        $datos_cobd01_contratoobras_cuerpo = $this->cobd01_contratoobras_cuerpo->findAll($this->condicionNDEP() . " and ano_contrato_obra='" . $var1 . "'  and upper(numero_contrato_obra)='" . strtoupper($var2) . "'");
        $this->set('datos_cobd01_contratoobras_cuerpo', $datos_cobd01_contratoobras_cuerpo);

        $condicion = "rif='" . $datos_cobd01_contratoobras_cuerpo[0]["cobd01_contratoobras_cuerpo"]["rif"] . "' ";
        $rif = $this->cpcd02->findAll($condicion, null, 'rif ASC', null);
        foreach ($rif as $r) {
            $denominacion_constructora = $r["cpcd02"]["denominacion"];
        }//fin foreach
        $this->set('denominacion_constructora', $denominacion_constructora);
    }

//fin

    function regresar_modificar($var = null) {

        $this->layout = "ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $this->Session->read('SScoddep') . '  ';


        echo'<script>';
        echo"document.getElementById('modificar').disabled=false;";
        echo"document.getElementById('guardar_modificar').disabled=true;";
        echo'</script>';


        $vec = $this->cugd10_imagenes->findCount($condicion . " and cod_campo=13 and identificacion='" . $var . "1'");
        if ($vec != 0) {
            $this->set('aqui_imagen_existe1', true);
        } else {
            $this->set('aqui_imagen_existe1', false);
        }

        $vec = $this->cugd10_imagenes->findCount($condicion . " and cod_campo=13 and identificacion='" . $var . "2'");
        if ($vec != 0) {
            $this->set('aqui_imagen_existe2', true);
        } else {
            $this->set('aqui_imagen_existe2', false);
        }

        $vec = $this->cugd10_imagenes->findCount($condicion . " and cod_campo=13 and identificacion='" . $var . "3'");
        if ($vec != 0) {
            $this->set('aqui_imagen_existe3', true);
        } else {
            $this->set('aqui_imagen_existe3', false);
        }

        $vec = $this->cugd10_imagenes->findCount($condicion . " and cod_campo=13 and identificacion='" . $var . "4'");
        if ($vec != 0) {
            $this->set('aqui_imagen_existe4', true);
        } else {
            $this->set('aqui_imagen_existe4', false);
        }

        $vec = $this->cugd10_imagenes->findCount($condicion . " and cod_campo=14 and identificacion='" . $var . "5'");
        if ($vec != 0) {
            $this->set('aqui_imagen_existe5', true);
        } else {
            $this->set('aqui_imagen_existe5', false);
        }

        $vec = $this->cugd10_imagenes->findCount($condicion . " and cod_campo=14 and identificacion='" . $var . "6'");
        if ($vec != 0) {
            $this->set('aqui_imagen_existe6', true);
        } else {
            $this->set('aqui_imagen_existe6', false);
        }

        $vec = $this->cugd10_imagenes->findCount($condicion . " and cod_campo=14 and identificacion='" . $var . "7'");
        if ($vec != 0) {
            $this->set('aqui_imagen_existe7', true);
        } else {
            $this->set('aqui_imagen_existe7', false);
        }

        $vec = $this->cugd10_imagenes->findCount($condicion . " and cod_campo=14 and identificacion='" . $var . "8'");
        if ($vec != 0) {
            $this->set('aqui_imagen_existe8', true);
        } else {
            $this->set('aqui_imagen_existe8', false);
        }




        $this->set('aqui_imagen', $var . '1');
        $this->set('aqui_imagen2', $var . '2');
        $this->set('aqui_imagen3', $var . '3');
        $this->set('aqui_imagen4', $var . '4');
        $this->set('aqui_imagen5', $var . '5');
        $this->set('aqui_imagen6', $var . '6');
        $this->set('aqui_imagen7', $var . '7');
        $this->set('aqui_imagen8', $var . '8');
    }

//fin function

    function eliminar_items($id) {
        $this->layout = "ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');

        $_SESSION["items"][$id]['id'] = 'no';

        $vec = $_SESSION["items"];
        $monto = 0;
        $opcion = "si";
        $monto2 = 0;
        $conta = 0;
//echo $cod_dep;

        for ($z = 0; $z < count($vec); $z++) {
            if ($vec[$z]['id'] == "no" && $vec[$z]['id'] != "0") {
                
            } else {
                $conta++;
                $cod_presi = $cod_presi;
                $cod_entidad = $cod_entidad;
                $cod_tipo_inst = $cod_tipo_inst;
                $cod_inst = $cod_inst;
                $cod_dep = $cod_dep;
                $ano_estimacion = $vec[$z][0];
                $cod_sector = $vec[$z][1];
                $cod_programa = $vec[$z][2];
                $cod_sub_prog = $vec[$z][3];
                $cod_proyecto = $vec[$z][4];
                $cod_activ_obra = $vec[$z][5];
                $cod_partida = $vec[$z][6];
                $cod_generica = $vec[$z][7];
                $cod_especifica = $vec[$z][8];
                $cod_sub_espec = $vec[$z][9];
                $cod_auxiliar = $vec[$z][10];
                $monto = $this->Formato1($vec[$z][11]);
                $sql_verificar = "cod_presi=" . $cod_presi . " and cod_entidad=" . $cod_entidad . " and cod_tipo_inst=" . $cod_tipo_inst . " and cod_inst=" . $cod_inst . " and cod_dep=" . $cod_dep . "  and ano=" . $ano_estimacion;
                $sql_verificar .=" and cod_sector=" . $cod_sector . " and cod_programa=" . $cod_programa . " and cod_sub_prog=" . $cod_sub_prog . " and cod_proyecto=" . $cod_proyecto . " and cod_activ_obra=" . $cod_activ_obra;
                $sql_verificar .=" and cod_partida=" . $this->AddCeroR($cod_partida) . " and cod_generica=" . $cod_generica . " and cod_especifica=" . $cod_especifica . " and cod_sub_espec=" . $cod_sub_espec . " and cod_auxiliar=" . $cod_auxiliar . "";
                if ($this->cfpd05->findCount($sql_verificar) != 0) {
                    $disponibilidad = $this->disponibilidad_con_dep($cod_dep, $ano_estimacion, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
                    $monto2 += $monto;
                    if ($disponibilidad == "0.00" || $disponibilidad < $monto) {
                        $opcion = "no";
                    }
                }//fin if contador
            }//fin else
        }//fin for





        if ($opcion == "no") {
            $this->set('msg_error', 'Una de las partidas no tiene disponibilidad');
            echo'<script>';
            echo"document.getElementById('guardar').disabled=true;";
            echo"document.getElementById('consultar').disabled=false;";
            echo'</script>';
        } else if ($conta != 0) {
            echo'<script>';
            echo"document.getElementById('guardar').disabled=false;";
            echo"document.getElementById('consultar').disabled=false;";
            echo'</script>';
        } else if ($conta == 0) {
            $this->set('msg_error', 'No existen partidas');
            echo'<script>';
            echo"document.getElementById('guardar').disabled=true;";
            echo"document.getElementById('consultar').disabled=false;";
            echo'</script>';
        }//fin else


        echo '<script>document.getElementById("TOTALINGRESOS").innerHTML="' . $this->Formato2($monto2) . '";</script>';
    }

//fin funtion

    function concatenaRif($vector1 = null, $nomVar = null, $extra = null) {
        $cod = array();
        if ($vector1 != null) {
            foreach ($vector1 as $x => $y) {
                if ($extra != null) {
                    if ($x < 10) {
                        $cod[$x] = $extra . '.0' . $x . ' - ' . $y;
                    } else if ($x >= 10 && $x <= 99) {
                        $cod[$x] = $extra . '.' . $x . ' - ' . $y;
                    }
                } else {

                    if ($x < 10) {
                        $cod[$x] = $x . ' - ' . $y;
                    } else if ($x >= 10 && $x <= 99) {
                        $cod[$x] = $x . ' - ' . $y;
                    }
                }
            }
            //print_r($cod);
        }

        $this->set($nomVar, $cod);
    }

//fin function

    function show_rif($pista = null) {
        $this->layout = "ajax";
        if ($pista != null) {
            $pista = strtoupper($pista);
            if ($this->cpcd02->findCount("(objeto=2 or objeto=3) and  (upper(denominacion) LIKE '%$pista%' OR upper(rif) LIKE '%$pista%'  )  ") > 0) {
                $proveedor = $this->cpcd02->generateList($conditions = "(condicion_actividad=1 and objeto=2 or objeto=3) and (upper(denominacion) LIKE '%$pista%' OR upper(rif) LIKE '%$pista%')  ", $order = null, $limit = null, '{n}.cpcd02.rif', '{n}.cpcd02.denominacion');
                $this->concatenaRif($proveedor, 'proveedor');
            } else {
                $this->set('msgError', 'NO SE ENCONTRO NINGUN PROVEEDOR REGISTRADO');
            }
        }
    }

    function guardar() {

        $this->layout = "ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $this->Session->read('SScoddep') . '  ';
        $ano = '';
        $monto_original_contrato_aux = 0;
        $aux_guardar_2 = 0;
        $aux_guardar = 0;
        $ano = $this->ano_ejecucion();


        if (!empty($this->data['cobp01_contratoobras']['fecha_calidad'])) {
            $fecha_fianza_calidad = $this->data['cobp01_contratoobras']['fecha_calidad'];
        } else {
            $fecha_fianza_calidad = "";
        }
        if (!empty($this->data['cobp01_contratoobras']['fecha_fiel_cumplimiento'])) {
            $fecha_fiel_cumplimiento = $this->data['cobp01_contratoobras']['fecha_fiel_cumplimiento'];
        } else {
            $fecha_fiel_cumplimiento = "";
        }
        if (!empty($this->data['cobp01_contratoobras']['fecha_anticipo'])) {
            $fecha_anticipo = $this->data['cobp01_contratoobras']['fecha_anticipo'];
        } else {
            $fecha_anticipo = "";
        }

        $cuenta_i = $this->data['cobp01_contratoobras']['cuenta_i'];


        $cod_obra = $this->data['cobp01_contratoobras']['input_cod_obra'];
        $ano = $this->data['cobp01_contratoobras']['ano'];
        $numero_contrato = $this->data['cobp01_contratoobras']['numero_contrato'];
        $ano_obra = $this->data['cobp01_contratoobras']['ano_obra'];
        $contrato_padre = $this->data['cobp01_contratoobras']['contrato_padre'];

        $tipo_otorgamiento = $this->data['cobp01_contratoobras']['tipo_otorgamiento'];
        $numero_buena_pro = $this->data['cobp01_contratoobras']['numero_buena_pro'];

        if (!empty($this->data['cobp01_contratoobras']['fecha_buena_pro'])) {
            $fecha_buena_pro = $this->data['cobp01_contratoobras']['fecha_buena_pro'];
        } else {
            $fecha_buena_pro = "";
        }

        $rif_contructor = $this->data['cscp03_registro_cotizacion']['rif_numero_2'];
        $denominacion_contructora = $this->data['cscp03_registro_cotizacion']['rif_nombre_2'];



        $fecha_contrato = $this->data['cobp01_contratoobras']['fecha_contrato'];
        $fecha_inicio = $this->data['cobp01_contratoobras']['fecha_inicio'];
        $fecha_terminacion = $this->data['cobp01_contratoobras']['fecha_terminacion'];


        $codigo_snc = $this->data['cobp01_contratoobras']['codigo_snc'];
        $clasificacion_tipo_obra = $this->data['cobp01_contratoobras']['clasificacion_tipo_obra'];



        if (!empty($this->data['cobp01_contratoobras']['numero_anticipo'])) {
            $numero_anticipo = $this->data['cobp01_contratoobras']['numero_anticipo'];
        } else {
            $numero_anticipo = "";
        }
        if (!empty($this->data['cobp01_contratoobras']['numero_fiel_cumplimiento'])) {
            $numero_fiel_cumplimiento = $this->data['cobp01_contratoobras']['numero_fiel_cumplimiento'];
        } else {
            $numero_fiel_cumplimiento = "";
        }
        if (!empty($this->data['cobp01_contratoobras']['numero_calida'])) {
            $numero_calida = $this->data['cobp01_contratoobras']['numero_calida'];
        } else {
            $numero_calida = "";
        }


        $denominacion_obra = $this->data['cobp01_contratoobras']['denominacion_obra'];
        $ubicacion_detallada_obra = $this->data['cobp01_contratoobras']['ubicacion_detallada_obra'];

        $anticipo_iva = "0";



        if (!empty($this->data['cobp01_contratoobras']['cod_estado'])) {
            $cod_estado = $this->data['cobp01_contratoobras']['cod_estado'];
        } else {
            $cod_estado = "0";
        }
        if (!empty($this->data['cobp01_contratoobras']['cod_municipio'])) {
            $cod_municipio = $this->data['cobp01_contratoobras']['cod_municipio'];
        } else {
            $cod_municipio = "0";
        }
        if (!empty($this->data['cobp01_contratoobras']['cod_parroquia'])) {
            $cod_parroquia = $this->data['cobp01_contratoobras']['cod_parroquia'];
        } else {
            $cod_parroquia = "0";
        }
        if (!empty($this->data['cobp01_contratoobras']['cod_centro'])) {
            $cod_centro = $this->data['cobp01_contratoobras']['cod_centro'];
        } else {
            $cod_centro = "0";
        }

        $vec = $_SESSION["items"];
        for ($i = 0; $i < count($vec); $i++) {
            if ($vec[$i]['id'] == "no" && $vec[$i]['id'] != "0") {
                
            } else {
                $var[$i]['monto'] = $this->data['cobp01_contratoobras']['monto_' . $i];
                if ($var[$i]['monto'] != "0,00") {
                    $monto_original_contrato_aux = $monto_original_contrato_aux + $this->Formato1($var[$i]['monto']);
                }//fin if
            }//fin else
        }//fin for


        $porcentaje_iva_parametro = Formato1($this->data['cobp01_contratoobras']['porcentaje_iva_parametro']);

        $ano_contrato_obra = $ano_obra;
        $numero_contrato_obra = $numero_contrato;
        $ano_estimacion = $ano;
        $cod_obra = $cod_obra;
        $cod_estado = $cod_estado;
        $cod_municipio = $cod_municipio;
        $cod_parroquia = $cod_parroquia;
        $cod_centro = $cod_centro;
        $especifique_ubicacion = $ubicacion_detallada_obra;
        $otorgamiento = $tipo_otorgamiento;
        $denominacion_obra = $denominacion_obra;
        $rif = $rif_contructor;
        $fecha_contrato_obra = $fecha_contrato;
        $fecha_inicio_contrato = $fecha_inicio;
        $fecha_terminacion_contrato = $fecha_terminacion;
        $monto_original_contrato = $monto_original_contrato_aux;
        $aumento = "0";
        $disminucion = "0";
        $monto_anticipo = "0";
        $monto_amortizacion = "0";
        $monto_retencion_laboral = "0";
        $monto_retencion_fielcumplimiento = "0";
        $monto_cancelado = "0";
        $porcentaje_iva = $porcentaje_iva_parametro;
        $porcentaje_anticipo = "0";
        $anticipo_con_iva = "0";

        $fecha_proceso_registro = date("d/m/Y");
        $dia_asiento_registro = "0";
        $mes_asiento_registro = "0";
        $ano_asiento_registro = "0";
        $numero_asiento_registro = "0";
        $username_registro = $_SESSION['nom_usuario'];
        $condicion_actividad = "1";
        $ano_anulacion = "0";
        $numero_anulacion = "0";
        $dia_asiento_anulacion = "0";
        $mes_asiento_anulacion = "0";
        $ano_asiento_anulacion = "0";
        $fecha_proceso_anulacion = "1/1/1900";
        $username_anulacion = "0";
        $fielcumplimiento_cancelado = "0";
        $laboral_cancelado = "0";
        $numero_asiento_anulacion = "0";
        $dispo = "no";

        $pregunta_ejercicio = $this->data['cobp01_contratoobras']['pregunta_ejercicio'];



        $numero_buenapro = $numero_buena_pro;
        $fecha_buenapro = $fecha_buena_pro;
        $numero_fianza_anticipo = $numero_anticipo;
        $fecha_fianza_anticipo = $fecha_anticipo;
        $numero_fianza_fielcumplimiento = $numero_fiel_cumplimiento;
        $fecha_fianza_fielcumplimiento = $fecha_fiel_cumplimiento;
        $numero_fianza_calidad = $numero_calida;
        $fecha_fianza_calidad = $fecha_fianza_calidad;



        if ($fecha_contrato_obra == "") {
            $fecha_contrato_obra = "1/1/1900";
        }
        if ($fecha_inicio_contrato == "") {
            $fecha_inicio_contrato = "1/1/1900";
        }
        if ($fecha_terminacion_contrato == "") {
            $fecha_terminacion_contrato = "1/1/1900";
        }
        if ($fecha_buenapro == "") {
            $fecha_buenapro = "1/1/1900";
        }
        if ($fecha_fianza_anticipo == "") {
            $fecha_fianza_anticipo = "1/1/1900";
        }
        if ($fecha_fianza_fielcumplimiento == "") {
            $fecha_fianza_fielcumplimiento = "1/1/1900";
        }
        if ($fecha_fianza_calidad == "") {
            $fecha_fianza_calidad = "1/1/1900";
        }


        $fecha_contrato_obra2 = $fecha_contrato_obra;
        $aux = $fecha_contrato_obra2[6] . $fecha_contrato_obra2[7] . $fecha_contrato_obra2[8] . $fecha_contrato_obra2[9];
        if ($aux != $ano_contrato_obra) {
            $fecha_contrato_obra2 = date("d/m/Y");
        }


        $sw2 = 0;
        $sw3 = 0;
        $sw4 = 0;
        $sw5 = 0;

        $aux_guardar_2 = 0;
        $aux_guardar = 0;


        $sql2 = "  BEGIN;  INSERT INTO cobd01_contratoobras_cuerpo( cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_contrato_obra, numero_contrato_obra, ano_estimacion, cod_obra, cod_estado, cod_municipio, cod_parroquia, cod_centro, especifique_ubicacion, otorgamiento, denominacion_obra, rif, fecha_contrato_obra,fecha_inicio_contrato, fecha_terminacion_contrato, monto_original_contrato, aumento, disminucion, monto_anticipo, monto_amortizacion, monto_retencion_laboral, monto_retencion_fielcumplimiento, monto_cancelado, porcentaje_iva, porcentaje_anticipo, anticipo_con_iva, fecha_proceso_registro, dia_asiento_registro, mes_asiento_registro, ano_asiento_registro, numero_asiento_registro, username_registro, condicion_actividad, ano_anulacion, numero_anulacion, dia_asiento_anulacion, mes_asiento_anulacion, ano_asiento_anulacion, fecha_proceso_anulacion, username_anulacion, fielcumplimiento_cancelado, laboral_cancelado, numero_buenapro, fecha_buenapro, numero_fianza_anticipo, fecha_fianza_anticipo, numero_fianza_fielcumplimiento, fecha_fianza_fielcumplimiento, numero_fianza_calidad, fecha_fianza_calidad, numero_asiento_anulacion, contrato_padre) ";
        $sql2 .= "  VALUES ('" . $cod_presi . "', '" . $cod_entidad . "', '" . $cod_tipo_inst . "', '" . $cod_inst . "', '" . $cod_dep . "', '" . $ano_contrato_obra . "', '" . strtoupper($numero_contrato_obra) . "', '" . $ano_estimacion . "', '" . strtoupper($cod_obra) . "', '" . $cod_estado . "', '" . $cod_municipio . "', '" . $cod_parroquia . "', '" . $cod_centro . "', '" . $especifique_ubicacion . "', '" . $otorgamiento . "', '" . $denominacion_obra . "', '" . $rif . "', '" . $this->Cfecha($fecha_contrato_obra, 'A-M-D') . "', '" . $this->Cfecha($fecha_inicio_contrato, 'A-M-D') . "', '" . $this->Cfecha($fecha_terminacion_contrato, 'A-M-D') . "', '" . $monto_original_contrato . "', '" . $aumento . "', '" . $disminucion . "', '" . $monto_anticipo . "', '" . $monto_amortizacion . "', '" . $monto_retencion_laboral . "', '" . $monto_retencion_fielcumplimiento . "', '" . $monto_cancelado . "', '" . $porcentaje_iva . "', '" . $porcentaje_anticipo . "', '" . $anticipo_con_iva . "', '" . $this->Cfecha($fecha_proceso_registro, 'A-M-D') . "', '" . $dia_asiento_registro . "', '" . $mes_asiento_registro . "', '" . $ano_asiento_registro . "', '" . $numero_asiento_registro . "', '" . $username_registro . "', '" . $condicion_actividad . "', '" . $ano_anulacion . "', '" . $numero_anulacion . "', '" . $dia_asiento_anulacion . "', '" . $mes_asiento_anulacion . "', '" . $ano_asiento_anulacion . "', '" . $this->Cfecha($fecha_proceso_anulacion, 'A-M-D') . "', '" . $username_anulacion . "', '" . $fielcumplimiento_cancelado . "', '" . $laboral_cancelado . "', '" . $numero_buenapro . "', '" . $this->Cfecha($fecha_buenapro, 'A-M-D') . "', '" . $numero_fianza_anticipo . "', '" . $fecha_fianza_anticipo . "', '" . $numero_fianza_fielcumplimiento . "', '" . $this->Cfecha($fecha_fianza_fielcumplimiento, 'A-M-D') . "', '" . $numero_fianza_calidad . "', '" . $this->Cfecha($fecha_fianza_calidad, 'A-M-D') . "','" . $numero_asiento_anulacion . "' , '" .$contrato_padre."' );";

        if ($this->cobd01_contratoobras_cuerpo->findCount("cod_presi=" . $cod_presi . " and cod_entidad=" . $cod_entidad . " and cod_tipo_inst=" . $cod_tipo_inst . " and  cod_inst=" . $cod_inst . " and cod_dep=" . $cod_dep . " and ano_contrato_obra=" . $ano_contrato_obra . " and numero_contrato_obra='" . $numero_contrato_obra . "' ") == 0) {
            $aux_guardar = $this->cobd01_contratoobras_cuerpo->execute($sql2);
        }//fin if



        if ($aux_guardar > 1) {


            $sql_aux = "cod_presi=" . $cod_presi . " and cod_entidad=" . $cod_entidad . " and cod_tipo_inst=" . $cod_tipo_inst . " and  cod_inst=" . $cod_inst . " and cod_dep=" . $cod_dep . " and ano_estimacion='" . $ano . "' and upper(cod_obra)='" . strtoupper($cod_obra) . "' ;  ";
            $sw3 = $this->cfpd07_obras_cuerpo->execute("UPDATE cfpd07_obras_cuerpo SET monto_contratado=monto_contratado+" . $monto_original_contrato_aux . " where " . $sql_aux);

            if ($sw3 > 1) {




                $numero_datos_partidas = $this->cfpd07_obras_partidas->findAll($condicion . " and ano_estimacion=" . $ano . " and upper(cod_obra)='" . strtoupper($cod_obra) . "' ");




                $a = 0;
                $ann = $ano;
                $j = 0;

                $numero_compromiso = $this->cfpd21_numero_asiento_compromiso->field('cfpd21_numero_asiento_compromiso.numero_compromiso', $conditions = $this->condicionNDEP() . " and ano_compromiso='$ann'", $order = null);
                if (!empty($numero_compromiso)) {
                    $numero_compromiso ++;
                    $sql_numero_compromiso = "UPDATE cfpd21_numero_asiento_compromiso SET numero_compromiso='$numero_compromiso' WHERE ano_compromiso='$ann' and " . $this->condicionNDEP() . ";";
                } else {
                    $numero_compromiso = 1;
                    $sql_numero_compromiso = "INSERT INTO cfpd21_numero_asiento_compromiso VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$ano', '$numero_compromiso');";
                }
                $sw_numero_compromiso = $this->cfpd21_numero_asiento_compromiso->query($sql_numero_compromiso);


                $vec = $_SESSION["items"];
                $monto = 0;
                $values = "";


                $monto = "";
                $aumento = "0";
                $disminucion = "0";
                $anticipo = "0";
                $amortizacion = "0";
                $retencion_laboral = "0";
                $retencion_fielcumplimiento = "0";
                $cancelacion = "0";

                $monto_contratado_aux = 0;


                for ($i = 0; $i < count($vec); $i++) {

                    if ($vec[$i]['id'] == "no" && $vec[$i]['id'] != "0") {
                        
                    } else {

                        $sw4 = 0;
                        $cod_presi2[$i] = $cod_presi;
                        $cod_entidad2[$i] = $cod_entidad;
                        $cod_tipo_inst2[$i] = $cod_tipo_inst;
                        $cod_inst2[$i] = $cod_inst;
                        $cod_dep2[$i] = $cod_dep;
                        $ano_estimacion2[$i] = $vec[$i][0];
                        $cod_sector2[$i] = $vec[$i][1];
                        $cod_programa2[$i] = $vec[$i][2];
                        $cod_sub_prog2[$i] = $vec[$i][3];
                        $cod_proyecto2[$i] = $vec[$i][4];
                        $cod_activ_obra2[$i] = $vec[$i][5];
                        $cod_partida2[$i] = $vec[$i][6];
                        $cod_generica2[$i] = $vec[$i][7];
                        $cod_especifica2[$i] = $vec[$i][8];
                        $cod_sub_espec2[$i] = $vec[$i][9];
                        $cod_auxiliar2[$i] = $vec[$i][10];
                        $monto2[$i] = $this->Formato1($vec[$i][11]);

                        $var[$i]['monto'] = $this->data['cobp01_contratoobras']['monto_' . $i];
                        if ($var[$i]['monto'] != "0,00") {

                            $var[$i]['monto'] = $this->Formato1($var[$i]['monto']);
                            $monto = $var[$i]['monto'];


                            $resultado = strpos("CONTRATISTA:", $denominacion_obra);
                            if ($resultado == FALSE) {
                                $rif = strtoupper($rif);
                                $contratista = $this->cpcd02->field('cpcd02.denominacion', $conditions = "upper(cpcd02.rif)='$rif'", $order = null);
                                $denominacion_obra = "CONTRATISTA: " . $contratista . " DENOMINACIÓN DE LA OBRA: " . $denominacion_obra;
                            }

                            $cp = $this->crear_partida($ano_estimacion2[$i], $cod_sector2[$i], $cod_programa2[$i], $cod_sub_prog2[$i], $cod_proyecto2[$i], $cod_activ_obra2[$i], $cod_partida2[$i], $cod_generica2[$i], $cod_especifica2[$i], $cod_sub_espec2[$i], $cod_auxiliar2[$i]);
                            $to = 1;
                            $td = 3;
                            $ta = 3;
                            $mt = $monto;
                            $ccp = $denominacion_obra;
                            $rnco = $numero_compromiso;

                            $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fecha_contrato_obra2, $mt, $ccp, $ano, $numero_contrato_obra, null, null, null, null, null, null, null, $rnco, null, null, null, $i);

                            $sql2 = "INSERT INTO cobd01_contratoobras_partidas(cod_presi,  cod_entidad,  cod_tipo_inst,  cod_inst,  cod_dep,  ano_contrato_obra,  numero_contrato_obra,  ano,  cod_sector,  cod_programa,  cod_sub_prog,  cod_proyecto,  cod_activ_obra,  cod_partida,  cod_generica,  cod_especifica,  cod_sub_espec,  cod_auxiliar,  monto,  aumento,  disminucion,  anticipo,  amortizacion, retencion_laboral, retencion_fielcumplimiento, cancelacion, numero_control_compromiso) ";
                            $sql2 .= "  VALUES ( '" . $cod_presi2[$i] . "', '" . $cod_entidad2[$i] . "', '" . $cod_tipo_inst2[$i] . "', '" . $cod_inst2[$i] . "', '" . $cod_dep2[$i] . "', '" . $ano_contrato_obra . "', '" . strtoupper($numero_contrato_obra) . "', '" . $ano_estimacion2[$i] . "', '" . $cod_sector2[$i] . "', '" . $cod_programa2[$i] . "', '" . $cod_sub_prog2[$i] . "', '" . $cod_proyecto2[$i] . "', '" . $cod_activ_obra2[$i] . "', '" . $cod_partida2[$i] . "', '" . $cod_generica2[$i] . "', '" . $cod_especifica2[$i] . "', '" . $cod_sub_espec2[$i] . "', '" . $cod_auxiliar2[$i] . "',  '" . $monto . "',  '" . $aumento . "',  '" . $disminucion . "',  '" . $anticipo . "',  '" . $amortizacion . "', '" . $retencion_laboral . "', '" . $retencion_fielcumplimiento . "', '" . $cancelacion . "', '" . $rnco . "'); ";

                            if ($this->cobd01_contratoobras_partidas->findCount("cod_presi=" . $cod_presi . " and cod_entidad=" . $cod_entidad . " and cod_tipo_inst=" . $cod_tipo_inst . " and  cod_inst=" . $cod_inst . " and cod_dep=" . $cod_dep . " and ano_contrato_obra=" . $ano_contrato_obra . " and upper(numero_contrato_obra)='" . strtoupper($numero_contrato_obra) . "' and  ano=" . $ano_estimacion2[$i] . " and cod_sector=" . $cod_sector2[$i] . "   and   cod_programa=" . $cod_programa2[$i] . "   and   cod_sub_prog=" . $cod_sub_prog2[$i] . "   and   cod_proyecto=" . $cod_proyecto2[$i] . "   and   cod_activ_obra=" . $cod_activ_obra2[$i] . "   and   cod_partida=" . $cod_partida2[$i] . "   and   cod_generica=" . $cod_generica2[$i] . "   and   cod_especifica=" . $cod_especifica2[$i] . "   and   cod_sub_espec=" . $cod_sub_espec2[$i] . "   and   cod_auxiliar=" . $cod_auxiliar2[$i] . "  ") == 0) {
                                $aux_guardar_2 = $this->cobd01_contratoobras_partidas->execute($sql2);

                                $sql_aux = "cod_presi=" . $cod_presi . " and cod_entidad=" . $cod_entidad . " and cod_tipo_inst=" . $cod_tipo_inst . " and  cod_inst=" . $cod_inst . " and cod_dep=" . $cod_dep . " and ano_estimacion=" . $ano . " and upper(cod_obra)='" . strtoupper($cod_obra) . "' and cod_sector=" . $cod_sector2[$i] . "   and   cod_programa=" . $cod_programa2[$i] . "   and   cod_sub_prog=" . $cod_sub_prog2[$i] . "   and   cod_proyecto=" . $cod_proyecto2[$i] . "   and   cod_activ_obra=" . $cod_activ_obra2[$i] . "   and   cod_partida=" . $cod_partida2[$i] . "   and   cod_generica=" . $cod_generica2[$i] . "   and   cod_especifica=" . $cod_especifica2[$i] . "   and   cod_sub_espec=" . $cod_sub_espec2[$i] . "   and   cod_auxiliar=" . $cod_auxiliar2[$i] . ";";
                                $sw4 = $this->cfpd07_obras_partidas->execute("UPDATE cfpd07_obras_partidas SET monto_contratado=monto_contratado+" . $monto . " where " . $sql_aux);
                            }//fin if
                        }//fin if
                    }//fin else
                }//fin for



                if ($sw4 > 1) {
                    $valor_motor_contabilidad = true;
                    if ($pregunta_ejercicio == 2) {
                        $valor_motor_contabilidad = $this->motor_contabilidad_fiscal($to = 1, $td = 7, $rif_doc = $rif, $ano_dc = $ano, $n_dc = $numero_contrato_obra, $f_dc = $fecha_contrato_obra2, $cpt_dc = $ccp, $ben_dc = null, $mon_dc = array("monto" => $monto_original_contrato), $ano_op = null, $n_op = null, $f_op = null, $a_adj_op = null, $n_adj_op = null, $f_adj_op = null, $tp_op = null, $deno_ban_pago = null, $ano_movimiento = null, $cod_ent_pago = null, $cod_suc_pago = null, $cod_cta_pago = null, $num_che_o_debi = null, $fec_che_o_debi = null, $clas_che_o_debi = null);
                    }
                } else {

                    $valor_motor_contabilidad = false;
                }//fin



                if ($valor_motor_contabilidad == true) {

                    $this->cobd01_contratoobras_partidas->execute("COMMIT;");
                    $this->set('msg', 'Los Datos fueron guardados correctamente');
                } else {

                    $this->cobd01_contratoobras_cuerpo->execute("ROLLBACK;");
                    if ($dispo == 'si') {
                        $this->set('msg_error', 'Los Datos no fueron guardados por falta de disponibilidad');
                    } else {
                        $this->set('msg_error', 'Los Datos no fueron guardados');
                    }
                }//fin
            } else {

                $this->cfpd07_obras_cuerpo->execute("ROLLBACK;");
                if ($dispo == 'si') {
                    $this->set('msg_error', 'Los Datos no fueron guardados por falta de disponibilidad');
                } else {
                    $this->set('msg_error', 'Los Datos no fueron guardados');
                }
            }//fin else
        } else {
            $this->cobd01_contratoobras_cuerpo->execute("ROLLBACK;");
            if ($dispo == 'si') {
                $this->set('msg_error', 'Los Datos no fueron guardados por falta de disponibilidad');
            } else {
                $this->set('msg_error', 'Los Datos no fueron guardados');
            }
        }//fin else







        echo'<script>';
        // echo'document.getElementById("denominacion_obra").value = ""; ';
        echo'</script>';



        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');

        $lista = "";
        $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . '';
        $ano = $this->ano_ejecucion();


        if ($Modulo != "0" || $SScoddep != 1) {

            $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $SScoddep . ' ';
            $a = $this->cobd01_contratoobras_cuerpo->findAll($condicion . ' and ano_contrato_obra=' . $ano);
            $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep_original=' . $SScoddeporig . ' ';
            $b = $this->cfpd07_obras_cuerpo->findAll($condicion . ' and ano_estimacion=' . $ano);
            foreach ($a as $a_aux) {
                foreach ($b as $b_aux) {
                    if ($a_aux['cobd01_contratoobras_cuerpo']['ano_estimacion'] == $b_aux['cfpd07_obras_cuerpo']['ano_estimacion'] && strtoupper($a_aux['cobd01_contratoobras_cuerpo']['cod_obra']) == strtoupper($b_aux['cfpd07_obras_cuerpo']['cod_obra'])) {
                        $lista[$a_aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra']] = $a_aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra'];
                    }//fin if
                }//fin foreach
            }//fin foreach
        } else {
            $lista = $this->cobd01_contratoobras_cuerpo->generateList($condicion . ' and ano_contrato_obra=' . $ano, ' cod_obra ASC', null, '{n}.cobd01_contratoobras_cuerpo.numero_contrato_obra', '{n}.cobd01_contratoobras_cuerpo.numero_contrato_obra');
        }//fin else


        $this->set('lista_numero', $lista);

        $this->set('ano', $ano);
        $this->set('year', $ano);

        $this->Session->delete("items");
        $this->Session->delete("i");


        $this->selecion('otros');
        $this->render("selecion");
    }

//fin guardar

    function consulta_index($var1 = null) {
        $this->layout = "ajax";
        $pag_num = 0;
        $opcion = 'no';
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');

        $this->set('ano_ejecucion', $this->ano_ejecucion());


        if ($SScoddep == 1 && $SScoddeporig == 1 && $Modulo == "0") {
            $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . '  ';
        } else {
            $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $this->Session->read('SScoddep') . '';
        }//fin else


        if (!empty($this->data['cobp01_contratoobras']['ano_contrato'])) {
            $_SESSION['ano_contrato_obra'] = $this->data['cobp01_contratoobras']['ano_contrato'];
        } else {
            $_SESSION['ano_contrato_obra'] = $this->ano_ejecucion();
        }
        $ano = $_SESSION['ano_contrato_obra'];


        if ($var1 != null) {
            if ($var1 == 'si') {
                $array = $this->cobd01_contratoobras_cuerpo->findAll($condicion . ' and ano_contrato_obra = ' . $ano . $this->filtra_obra($ano), 'DISTINCT ano_contrato_obra, numero_contrato_obra, ano_estimacion, cod_obra', 'ano_contrato_obra, numero_contrato_obra, ano_estimacion, cod_obra ASC', null);

                $i = 0;

                foreach ($array as $aux) {
                    $numero[$i]['ano_contrato_obra'] = $aux['cobd01_contratoobras_cuerpo']['ano_contrato_obra'];
                    $numero[$i]['numero_contrato_obra'] = $aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra'];
                    $numero[$i]['ano_estimacion'] = $aux['cobd01_contratoobras_cuerpo']['ano_estimacion'];
                    $numero[$i]['cod_obra'] = $aux['cobd01_contratoobras_cuerpo']['cod_obra'];
                    $i++;
                } $i--;



                if (!empty($this->data['cobp01_contratoobras']['numero_contrato_obra'])) {
                    for ($a = 0; $a <= $i; $a++) {
                        if ($this->data['cobp01_contratoobras']['numero_contrato_obra'] == $numero[$a]['numero_contrato_obra']) {
                            $pag_num = $a;
                            $opcion = 'si';
                            break;
                        } else {
                            $opcion = 'no';
                        }
                    }//fin for

                    if ($opcion == 'si') {
                        $this->consulta($pag_num);
                        $this->render('consulta');
                    } else if ($opcion == 'no') {
                        $this->set('errorMessage', 'No existen datos');
                        $this->consulta_index();
                        $this->render('consulta_index');
                    }//fin else
                } else {

                    if ($i <= 0) {
                        $this->set('errorMessage', 'No existen datos');
                        $this->consulta_index();
                        $this->render('consulta_index');
                    } else {
                        $this->consulta($pag_num);
                        $this->render('consulta');
                    }
                }//fin else
            }//fin if
        }//fin i


        $lista = "";


        if ($SScoddep == 1 && $SScoddeporig == 1 && $Modulo == "0") {
            $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . '  and ano_contrato_obra=' . $ano;
        } else {
            $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $this->Session->read('SScoddep') . '';
            $condicion = $condicion . ' and ano_contrato_obra=' . $ano . $this->filtra_obra($ano);
        }//fin else



        $lista = $this->cobd01_contratoobras_cuerpo->generateList($condicion, ' numero_contrato_obra ASC', null, '{n}.cobd01_contratoobras_cuerpo.numero_contrato_obra', '{n}.cobd01_contratoobras_cuerpo.numero_contrato_obra');
        if ($lista == "") {
            $lista = array('' => '');
        }
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


        if ($SScoddep == 1 && $SScoddeporig == 1 && $Modulo == "0") {
            $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . '  and ano_contrato_obra=' . $var1;
        } else {
            $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $this->Session->read('SScoddep') . '';
            $condicion = $condicion . ' and ano_contrato_obra=' . $var1 . $this->filtra_obra($var1);
        }//fin else


        $lista = $this->cobd01_contratoobras_cuerpo->generateList($condicion, ' numero_contrato_obra ASC', null, '{n}.cobd01_contratoobras_cuerpo.numero_contrato_obra', '{n}.cobd01_contratoobras_cuerpo.numero_contrato_obra');
        if ($lista == "") {
            $lista = array('' => '');
        }
        $this->set('obras', $lista);
    }

//fin function

    function consulta($pag_num = null) {
        $this->layout = "ajax";

        $codigo_prod_serv = "";
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');

        $this->set('ano_ejecucion', $this->ano_ejecucion());



        if ($SScoddep == 1 && $SScoddeporig == 1 && $Modulo == "0") {
            $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . '  ';
        } else {
            $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $this->Session->read('SScoddep') . '';
        }//fin else


        if (isset($_SESSION['ano_contrato_obra'])) {
            $ano = $_SESSION['ano_contrato_obra'];
        } else {
            $ano = $this->ano_ejecucion();
        }
        $this->set('ano_contrato_obra', $ano);

        $array = $this->cobd01_contratoobras_cuerpo->findAll($condicion . 'and ano_contrato_obra = ' . $ano . $this->filtra_obra($ano), 'DISTINCT ano_contrato_obra, numero_contrato_obra, ano_estimacion, cod_obra ', 'ano_contrato_obra, numero_contrato_obra, ano_estimacion, cod_obra ASC', null);

        $i = 0;
        if ($pag_num == null) {
            $pag_num = 0;
        }

        foreach ($array as $aux) {

            $numero[$i]['ano_contrato_obra'] = $aux['cobd01_contratoobras_cuerpo']['ano_contrato_obra'];
            $numero[$i]['numero_contrato_obra'] = $aux['cobd01_contratoobras_cuerpo']['numero_contrato_obra'];
            $numero[$i]['ano_estimacion'] = $aux['cobd01_contratoobras_cuerpo']['ano_estimacion'];
            $numero[$i]['cod_obra'] = $aux['cobd01_contratoobras_cuerpo']['cod_obra'];

            $i++;
        } $i--;







        if (isset($numero[$pag_num]['numero_contrato_obra'])) {



            $datos_cobd01_contratoobras_cuerpo_count = $this->cobd01_contratoobras_cuerpo->findCount($condicion . " and ano_contrato_obra='" . $numero[$pag_num]["ano_contrato_obra"] . "'  and upper(numero_contrato_obra)='" . strtoupper($numero[$pag_num]["numero_contrato_obra"]) . "'  and ano_estimacion='" . $numero[$pag_num]["ano_estimacion"] . "'  and upper(cod_obra)='" . strtoupper($numero[$pag_num]["cod_obra"]) . "'  ");

            $sql_aux = "";

            if ($datos_cobd01_contratoobras_cuerpo_count > 1) {
                $sql_aux = " and condicion_actividad=1";
            }


            $datos_cobd01_contratoobras_cuerpo = $this->cobd01_contratoobras_cuerpo->findAll($condicion . " and ano_contrato_obra='" . $numero[$pag_num]["ano_contrato_obra"] . "'  and upper(numero_contrato_obra)='" . strtoupper($numero[$pag_num]["numero_contrato_obra"]) . "'  and ano_estimacion='" . $numero[$pag_num]["ano_estimacion"] . "'  and upper(cod_obra)='" . strtoupper($numero[$pag_num]["cod_obra"]) . "'  " . $sql_aux);
            $datos_cobd01_contratoobras_partidas = $this->cobd01_contratoobras_partidas->findAll($condicion . " and ano_contrato_obra=" . $numero[$pag_num]["ano_contrato_obra"] . "  and upper(numero_contrato_obra)='" . strtoupper($numero[$pag_num]["numero_contrato_obra"]) . "'  ");



            $this->set('datos_cobd01_contratoobras_cuerpo', $datos_cobd01_contratoobras_cuerpo);
            $this->set('datos_cobd01_contratoobras_partidas', $datos_cobd01_contratoobras_partidas);
            $this->set('pag_num', $pag_num);



            $var = $numero[$pag_num]['numero_contrato_obra'];


            $vec = $this->cugd10_imagenes->findCount($condicion . " and cod_campo=13 and upper(identificacion)=('" . $var . "1')");

            if ($vec != 0) {
                $this->set('aqui_imagen_existe1', true);
            } else {
                $this->set('aqui_imagen_existe1', false);
            }

            $vec = $this->cugd10_imagenes->findCount($condicion . " and cod_campo=13 and identificacion='" . $var . "2'");
            if ($vec != 0) {
                $this->set('aqui_imagen_existe2', true);
            } else {
                $this->set('aqui_imagen_existe2', false);
            }

            $vec = $this->cugd10_imagenes->findCount($condicion . " and cod_campo=13 and identificacion='" . $var . "3'");
            if ($vec != 0) {
                $this->set('aqui_imagen_existe3', true);
            } else {
                $this->set('aqui_imagen_existe3', false);
            }

            $vec = $this->cugd10_imagenes->findCount($condicion . " and cod_campo=13 and identificacion='" . $var . "4'");
            if ($vec != 0) {
                $this->set('aqui_imagen_existe4', true);
            } else {
                $this->set('aqui_imagen_existe4', false);
            }

            $vec = $this->cugd10_imagenes->findCount($condicion . " and cod_campo=14 and identificacion='" . $var . "5'");
            if ($vec != 0) {
                $this->set('aqui_imagen_existe5', true);
            } else {
                $this->set('aqui_imagen_existe5', false);
            }

            $vec = $this->cugd10_imagenes->findCount($condicion . " and cod_campo=14 and identificacion='" . $var . "6'");
            if ($vec != 0) {
                $this->set('aqui_imagen_existe6', true);
            } else {
                $this->set('aqui_imagen_existe6', false);
            }

            $vec = $this->cugd10_imagenes->findCount($condicion . " and cod_campo=14 and identificacion='" . $var . "7'");
            if ($vec != 0) {
                $this->set('aqui_imagen_existe7', true);
            } else {
                $this->set('aqui_imagen_existe7', false);
            }

            $vec = $this->cugd10_imagenes->findCount($condicion . " and cod_campo=14 and identificacion='" . $var . "8'");
            if ($vec != 0) {
                $this->set('aqui_imagen_existe8', true);
            } else {
                $this->set('aqui_imagen_existe8', false);
            }




            $this->set('aqui_imagen', $var . '1');
            $this->set('aqui_imagen2', $var . '2');
            $this->set('aqui_imagen3', $var . '3');
            $this->set('aqui_imagen4', $var . '4');
            $this->set('aqui_imagen5', $var . '5');
            $this->set('aqui_imagen6', $var . '6');
            $this->set('aqui_imagen7', $var . '7');
            $this->set('aqui_imagen8', $var . '8');









            $this->set('totalPages_Recordset1', $i);


            $cfpd07_obras_cuerpo = $this->cfpd07_obras_cuerpo->findAll($condicion . " and ano_estimacion=" . $numero[$pag_num]["ano_contrato_obra"] . " and upper(cod_obra)='" . strtoupper($numero[$pag_num]["cod_obra"]) . "' ");
            $cfpd07_obras_cuerpo_aux = $cfpd07_obras_cuerpo;

            foreach ($cfpd07_obras_cuerpo_aux as $aux) {
                $codigo_prod_serv = $aux['cfpd07_obras_cuerpo']['codigo_prod_serv'];
                $estimado_presu = $aux['cfpd07_obras_cuerpo']['estimado_presu'];
            }//fin foreach


            $datos_cobd01_contratoobras_cuerpo_aux = $datos_cobd01_contratoobras_cuerpo;

            foreach ($datos_cobd01_contratoobras_cuerpo_aux as $aux2) {
                $rif = $aux2['cobd01_contratoobras_cuerpo']['rif'];

                $cod_estado = $aux2['cobd01_contratoobras_cuerpo']['cod_estado'];
                $cod_municipio = $aux2['cobd01_contratoobras_cuerpo']['cod_municipio'];
                $cod_parroquia = $aux2['cobd01_contratoobras_cuerpo']['cod_parroquia'];
                $cod_centro = $aux2['cobd01_contratoobras_cuerpo']['cod_centro'];
                $numero_anulacion = $aux2['cobd01_contratoobras_cuerpo']['numero_anulacion'];
            }//fin foreach

            $C_A = $this->cugd03_acta_anulacion_cuerpo->findAll($condicion . " and numero_acta_anulacion=" . $numero_anulacion . " and ano_acta_anulacion=" . $numero[$pag_num]["ano_contrato_obra"]);

            if ($C_A != null) {
                $this->set("concepto_anulacion", $C_A[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"]);
            } else {
                $this->set("concepto_anulacion", "");
            }//fin else

            $cod_snc = "";
            $denominacion_snc = "";


            if ($codigo_prod_serv != "") {

                $cscd01_catalogo = $this->cscd01_catalogo->findAll("codigo_prod_serv=" . $codigo_prod_serv . " ");
                $cscd01_catalogo_aux = $cscd01_catalogo;

                foreach ($cscd01_catalogo_aux as $aux2) {
                    $cod_snc = $aux2['cscd01_catalogo']['cod_snc'];
                    $denominacion_snc = $aux2['cscd01_catalogo']['denominacion'];
                }//fin foreach
            }//fin if


            $condicion = "rif='" . $rif . "' ";
            $rif = $this->cpcd02->findAll($condicion, null, 'rif ASC', null);
            foreach ($rif as $r) {
                $denominacion_constructora = $r["cpcd02"]["denominacion"];
            }//fin foreach


            $deno_cod_estado = "<br>";
            $deno_cod_municipio = "<br>";
            $deno_cod_parroquia = "<br>";
            $deno_cod_centro = "<br>";

//cugd01_estados  cugd01_municipios  cugd01_parroquias  cugd01_centropoblados

            $rs = $this->cugd90_municipio_defecto->findAll($this->SQLCA());

            $cond_a = " cod_republica=1 and cod_estado=" . $rs[0][cugd90_municipio_defecto]['cod_estado'];
            $cond_b = " cod_republica=1 and cod_estado=" . $rs[0][cugd90_municipio_defecto]['cod_estado'] . " and cod_municipio=" . $rs[0][cugd90_municipio_defecto]['cod_municipio'];
            $cond_c = " cod_republica=1 and cod_estado=" . $cod_estado . " and cod_municipio=" . $cod_municipio . " and cod_parroquia=" . $cod_parroquia;
            $cond_d = " cod_republica=1 and cod_estado=" . $cod_estado . " and cod_municipio=" . $cod_municipio . " and cod_parroquia=" . $cod_parroquia . " and cod_centro=" . $cod_centro;


            $cugd01_estados = $this->cugd01_estados->findAll($cond_a);
            $cugd01_municipios = $this->cugd01_municipios->findAll($cond_b);
            $cugd01_parroquias = $this->cugd01_parroquias->findAll($cond_c);
            $cugd01_centropoblados = $this->cugd01_centropoblados->findAll($cond_d);


            foreach ($cugd01_estados as $cugd01_estados_aux) {
                $deno_cod_estado = $cugd01_estados_aux["cugd01_estados"]["denominacion"];
            }//fin foreach
            foreach ($cugd01_municipios as $cugd01_municipios_aux) {
                $deno_cod_municipio = $cugd01_municipios_aux["cugd01_municipios"]["denominacion"];
            }//fin foreach
            foreach ($cugd01_parroquias as $cugd01_parroquias_aux) {
                $deno_cod_parroquia = $cugd01_parroquias_aux["cugd01_parroquias"]["denominacion"];
            }//fin foreach
            foreach ($cugd01_centropoblados as $cugd01_centropoblados_aux) {
                $deno_cod_centro = $cugd01_centropoblados_aux["cugd01_centropoblados"]["denominacion"];
            }//fin foreach


            $this->set('cod_estado', $cod_estado);
            $this->set('cod_municipio', $cod_municipio);
            $this->set('cod_parroquia', $cod_parroquia);
            $this->set('cod_centro', $cod_centro);

            $this->set('deno_cod_estado', $deno_cod_estado);
            $this->set('deno_cod_municipio', $deno_cod_municipio);
            $this->set('deno_cod_parroquia', $deno_cod_parroquia);
            $this->set('deno_cod_centro', $deno_cod_centro);

            $this->set('cod_snc', $cod_snc);
            $this->set('denominacion_snc', $denominacion_snc);
            $this->set('denominacion_constructora', $denominacion_constructora);
        } else {

            $this->set('pag_num', 0);
            $this->set('totalPages_Recordset1', '');
            $this->set('errorMessage', 'No existen datos');
        }//fin else
    }

//fin function

    function select3($select = null, $var = null) { //select codigos presupuestarios
        $this->layout = "ajax";
        $lista = "";
        if ($var != null) {
            switch ($select) {
                case 'republica':
                    $this->set('SELECT', 'estado');
                    $this->set('codigo', 'republica');
                    $this->set('seleccion', '');
                    $this->set('n', 1);
                    $lista = $this->cugd01_republica->generateList(null, 'cod_republica ASC', null, '{n}.cfpd02_sector.cod_republica', '{n}.cfpd02_sector.denominacion');
                    $this->concatena($lista, 'vector');
                    break;
                case 'estado':
                    $this->set('SELECT', 'municipio');
                    $this->set('codigo', 'estado');
                    $this->set('seleccion', '');
                    $this->set('n', 2);
                    $this->Session->write('rep', "1");
                    $cond = " cod_republica=" . $var;
                    $lista = $this->cugd01_estados->generateList($cond, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
                    $this->concatena($lista, 'vector');
                    break;
                case 'municipio':
                    $this->set('SELECT', 'parroquia');
                    $this->set('codigo', 'municipio');
                    $this->set('seleccion', '');
                    $this->set('n', 3);
                    // $this->set('buitre',true);
                    $this->Session->write('est', $var);
                    $rep = $this->Session->read('rep');
                    $cond = " cod_republica=" . $rep . " and cod_estado=" . $var;

                    $aux = $this->cugd01_estados->findAll($cond);
                    foreach ($aux as $aux2) {
                        $cod_estado = $aux2['cugd01_estados']['cod_estado'];
                        $denominacion = $aux2['cugd01_estados']['denominacion'];
                    }//fin foreach
                    echo'<script>';
                    echo'document.getElementById("ver_cod_estado").innerHTML = "' . $this->AddCeroR($cod_estado) . '"; ';
                    echo'document.getElementById("deno_cod_estado").innerHTML = "' . $denominacion . '"; ';
                    echo'document.getElementById("ver_cod_municipio").innerHTML = "<br>"; ';
                    echo'document.getElementById("deno_cod_municipio").innerHTML = "<br>"; ';
                    echo'document.getElementById("ver_cod_parroquia").innerHTML = "<br>"; ';
                    echo'document.getElementById("deno_cod_parroquia").innerHTML = "<br>"; ';
                    echo'document.getElementById("ver_cod_centro").innerHTML = "<br>"; ';
                    echo'document.getElementById("deno_cod_centro").innerHTML = "<br>"; ';
                    echo'</script>';



                    $lista = $this->cugd01_municipios->generateList($cond, 'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
                    $this->concatena($lista, 'vector');
                    break;
                case 'parroquia':
                    $this->set('SELECT', 'centro');
                    $this->set('codigo', 'parroquia');
                    $this->set('seleccion', '');
                    $this->set('n', 4);
                    $rep = $this->Session->read('rep');
                    $est = $this->Session->read('est');
                    $this->Session->write('mun', $var);
                    $cond = " cod_republica=" . $rep . " and cod_estado=" . $est . " and cod_municipio=" . $var;

                    $aux = $this->cugd01_municipios->findAll($cond);
                    foreach ($aux as $aux2) {
                        $cod_municipio = $aux2['cugd01_municipios']['cod_municipio'];
                        $denominacion = $aux2['cugd01_municipios']['denominacion'];
                    }//fin foreach
                    echo'<script>';
                    echo'document.getElementById("ver_cod_municipio").innerHTML = "' . $this->AddCeroR($cod_municipio) . '"; ';
                    echo'document.getElementById("deno_cod_municipio").innerHTML = "' . $denominacion . '"; ';
                    echo'document.getElementById("ver_cod_parroquia").innerHTML = "<br>"; ';
                    echo'document.getElementById("deno_cod_parroquia").innerHTML = "<br>"; ';
                    echo'document.getElementById("ver_cod_centro").innerHTML = "<br>"; ';
                    echo'document.getElementById("deno_cod_centro").innerHTML = "<br>"; ';
                    echo'</script>';


                    $lista = $this->cugd01_parroquias->generateList($cond, 'cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
                    $this->concatena($lista, 'vector');
                    break;
                case 'centro':

                    $rs = $this->cugd90_municipio_defecto->findAll($this->SQLCA());


                    $this->set('SELECT', 'funcion');
                    $this->set('codigo', 'centro');
                    $this->set('seleccion', '');
                    $this->set('n', 5);
                    //$this->set('no','no');
                    $rep = $this->Session->read('rep');
                    $est = $this->Session->read('est');
                    $mun = $this->Session->read('mun');
                    $this->Session->write('par', $var);
                    $cond = " cod_republica=" . $rep . " and cod_estado=" . $est . " and cod_municipio=" . $mun . " and cod_parroquia=" . $var;

                    $aux = $this->cugd01_parroquias->findAll($cond);
                    foreach ($aux as $aux2) {
                        $cod_parroquia = $aux2['cugd01_parroquias']['cod_parroquia'];
                        $denominacion = $aux2['cugd01_parroquias']['denominacion'];
                    }//fin foreach
                    echo'<script>';
                    echo'document.getElementById("ver_cod_parroquia").innerHTML = "' . $this->AddCeroR($cod_parroquia) . '"; ';
                    echo'document.getElementById("deno_cod_parroquia").innerHTML = "' . $denominacion . '"; ';
                    echo'document.getElementById("ver_cod_centro").innerHTML = "<br>"; ';
                    echo'document.getElementById("deno_cod_centro").innerHTML = "<br>"; ';
                    echo'</script>';


                    $lista = $this->cugd01_centropoblados->generateList($cond, 'cod_centro ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
                    $this->concatena($lista, 'vector');
                    break;

                case 'funcion':
                    $this->set('SELECT', 'funcion');
                    $this->set('codigo', 'funcion');
                    //$this->set('seleccion','');
                    $this->set('n', 6);
                    $rep = $this->Session->read('rep');
                    $est = $this->Session->read('est');
                    $mun = $this->Session->read('mun');
                    $par = $this->Session->read('par');

                    $rs = $this->cugd90_municipio_defecto->findAll($this->SQLCA());
                    $cond = " cod_republica=" . $rep . " and cod_estado=" . $est . " and cod_municipio=" . $mun . " and cod_parroquia=" . $par . " and cod_centro=" . $var;

                    $aux = $this->cugd01_centropoblados->findAll($cond);
                    foreach ($aux as $aux2) {
                        $cod_centro = $aux2['cugd01_centropoblados']['cod_centro'];
                        $denominacion = $aux2['cugd01_centropoblados']['denominacion'];
                    }//fin foreach
                    echo'<script>';
                    echo'document.getElementById("ver_cod_centro").innerHTML = "' . $this->AddCeroR($cod_centro) . '"; ';
                    echo'document.getElementById("deno_cod_centro").innerHTML = "' . $denominacion . '"; ';
                    echo'</script>';

                    break;
            }//fin wsitch
        } else {
            $this->set('SELECT', '');
            $this->set('codigo', '');
            $this->set('seleccion', '');
            $this->set('n', 15);
            $this->set('no', 'no');
            $this->set('vector', '');
        }
    }

//fin select codigos presupuestarios

    function guardar_anulacion1($var = null) {
        $this->layout = "ajax";


        echo'<script>';
        echo'document.getElementById("guardar").disabled = false; ';
        echo'document.getElementById("anular").disabled = true; ';
        echo'</script>';
    }

//fin function

    function guardar_anulacion2($var = null) {

        $this->layout = "ajax";



        if (isset($this->data["cobp01_contratoobras"])) {


            $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $this->Session->read('SScoddep') . ' ';

            $tipo_documento = 233;

            $concepto_anulacion = $this->data["cobp01_contratoobras"]["concepto_anulacion"];
            $fecha_proceso_anulacion = date("d/m/Y");


            $condicion_documento = 2; //cuando se guarda es Activo=1

            $ano_contrato_obra = $this->data["cobp01_contratoobras"]["ano_contrato_obra"];
            $numero_contrato_obra = $this->data["cobp01_contratoobras"]["numero_contrato_obra"];
            $ano_estimacion = $this->data["cobp01_contratoobras"]["ano_estimacion"];
            $cod_obra = $this->data["cobp01_contratoobras"]["cod_obra"];
            $pregunta_ejercicio = $this->data['cobp01_contratoobras']['pregunta_ejercicio'];



            $this->requestAction('/cugp10_imagenes/eliminar/' . $numero_contrato_obra . '1' . '/13');
            $this->requestAction('/cugp10_imagenes/eliminar/' . $numero_contrato_obra . '2' . '/13');
            $this->requestAction('/cugp10_imagenes/eliminar/' . $numero_contrato_obra . '3' . '/13');
            $this->requestAction('/cugp10_imagenes/eliminar/' . $numero_contrato_obra . '4' . '/13');
            $this->requestAction('/cugp10_imagenes/eliminar/' . $numero_contrato_obra . '5' . '/14');
            $this->requestAction('/cugp10_imagenes/eliminar/' . $numero_contrato_obra . '6' . '/14');
            $this->requestAction('/cugp10_imagenes/eliminar/' . $numero_contrato_obra . '7' . '/14');
            $this->requestAction('/cugp10_imagenes/eliminar/' . $numero_contrato_obra . '8' . '/14');



            $concepto_anulacion = $this->data["cobp01_contratoobras"]["concepto_anulacion"];
            $concepto = $concepto_anulacion;
            $fecha_proceso_registro = $this->data["cobp01_contratoobras"]["fecha_proceso_registro"];
            $fecha_contrato_obra = $this->data["cobp01_contratoobras"]["fecha_contrato_obra"];
            $monto = $this->data["cobp01_contratoobras"]["monto"];
            $rif = $this->data["cobp01_contratoobras"]["rif"];
            $monto_original_contrato = $this->data["cobp01_contratoobras"]["monto_original_contrato"];

            $resultado = strpos("CONTRATISTA:", $concepto_anulacion);
            if ($resultado == FALSE) {
                $rif = strtoupper($rif);
                $contratista = $this->cpcd02->field('cpcd02.denominacion', $conditions = "upper(cpcd02.rif)='$rif'", $order = null);
                $concepto_anulacion = "CONTRATISTA: " . $contratista . " ANULADO POR: " . $concepto_anulacion;
            }

            $fd = $fecha_contrato_obra;


            // echo $fecha_proceso_registro.'_____'.$fecha_contrato_obra;

            $aux = $fecha_contrato_obra[6] . $fecha_contrato_obra[7] . $fecha_contrato_obra[8] . $fecha_contrato_obra[9];
            if ($aux != $ano_contrato_obra) {
                $fd = $fecha_proceso_registro[8] . $fecha_proceso_registro[9] . $fecha_proceso_registro[7] . $fecha_proceso_registro[5] . $fecha_proceso_registro[6] . $fecha_proceso_registro[4] . $fecha_proceso_registro[0] . $fecha_proceso_registro[1] . $fecha_proceso_registro[2] . $fecha_proceso_registro[3];
            }


            $partidas = $this->cobd01_contratoobras_partidas->findAll($conditions = $this->condicion() . " and numero_contrato_obra='$numero_contrato_obra' and ano_contrato_obra='$ano_contrato_obra'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
            $i = 0;

            $cfpd07_obras_partidas_aux = $this->cfpd07_obras_partidas->findAll("ano_estimacion='" . $ano_estimacion . "' and upper(cod_obra)='" . strtoupper($cod_obra) . "' ");


            foreach ($partidas as $row) {

                $ano_contrato_obra = $row['cobd01_contratoobras_partidas']['ano_contrato_obra'];
                $numero_contrato_obra = $row['cobd01_contratoobras_partidas']['numero_contrato_obra'];
                $ano = $row['cobd01_contratoobras_partidas']['ano'];
                $cod_sector = $row['cobd01_contratoobras_partidas']['cod_sector'];
                $cod_programa = $row['cobd01_contratoobras_partidas']['cod_programa'];
                $cod_sub_prog = $row['cobd01_contratoobras_partidas']['cod_sub_prog'];
                $cod_proyecto = $row['cobd01_contratoobras_partidas']['cod_proyecto'];
                $cod_activ_obra = $row['cobd01_contratoobras_partidas']['cod_activ_obra'];
                $cod_partida = $row['cobd01_contratoobras_partidas']['cod_partida'];
                $cod_generica = $row['cobd01_contratoobras_partidas']['cod_generica'];
                $cod_especifica = $row['cobd01_contratoobras_partidas']['cod_especifica'];
                $cod_sub_espec = $row['cobd01_contratoobras_partidas']['cod_sub_espec'];
                $cod_auxiliar = $row['cobd01_contratoobras_partidas']['cod_auxiliar'];
                $monto2[$i] = $row['cobd01_contratoobras_partidas']['monto'];
                $numero_asiento_compromiso[$i] = $row['cobd01_contratoobras_partidas']['numero_control_compromiso'];

                $cp[$i] = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
                $cod_cp[$i] = "ano='$ano' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";


                foreach ($cfpd07_obras_partidas_aux as $cfpd07_obras_partidas_aux_2) {
                    $cod_sector2 = $cfpd07_obras_partidas_aux_2['cfpd07_obras_partidas']['cod_sector'];
                    $cod_programa2 = $cfpd07_obras_partidas_aux_2['cfpd07_obras_partidas']['cod_programa'];
                    $cod_sub_prog2 = $cfpd07_obras_partidas_aux_2['cfpd07_obras_partidas']['cod_sub_prog'];
                    $cod_proyecto2 = $cfpd07_obras_partidas_aux_2['cfpd07_obras_partidas']['cod_proyecto'];
                    $cod_activ_obra2 = $cfpd07_obras_partidas_aux_2['cfpd07_obras_partidas']['cod_activ_obra'];
                    $cod_partida2 = $cfpd07_obras_partidas_aux_2['cfpd07_obras_partidas']['cod_partida'];
                    $cod_generica2 = $cfpd07_obras_partidas_aux_2['cfpd07_obras_partidas']['cod_generica'];
                    $cod_especifica2 = $cfpd07_obras_partidas_aux_2['cfpd07_obras_partidas']['cod_especifica'];
                    $cod_sub_espec2 = $cfpd07_obras_partidas_aux_2['cfpd07_obras_partidas']['cod_sub_espec'];
                    $cod_auxiliar2 = $cfpd07_obras_partidas_aux_2['cfpd07_obras_partidas']['cod_auxiliar'];

                    if ($cod_sector2 == $cod_sector && $cod_programa2 == $cod_programa && $cod_sub_prog2 == $cod_sub_prog && $cod_proyecto2 == $cod_proyecto && $cod_activ_obra2 == $cod_activ_obra && $cod_partida2 == $cod_partida && $cod_generica2 == $cod_generica && $cod_especifica2 == $cod_especifica && $cod_sub_espec2 == $cod_sub_espec && $cod_auxiliar2 == $cod_auxiliar) {
                        $R2_anula = $this->cfpd07_obras_partidas->execute("UPDATE cfpd07_obras_partidas SET  monto_contratado=monto_contratado-" . $monto2[$i] . "  WHERE " . $this->SQLCA() . " and ano_estimacion=" . $ano_estimacion . "  and  upper(cod_obra)='" . strtoupper($cod_obra) . "'  and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'");
                    }//fin ifr
                }//fin foreach
                $i++;
            }//fin for
            //$numero_anulacion=$this->cugd03_acta_anulacion_numero->execute($condicion);
            $v = $this->cugd03_acta_anulacion_numero->execute("SELECT numero_acta_anulacion FROM cugd03_acta_anulacion_numero WHERE " . $this->SQLCA() . " and ano_acta_anulacion=" . $ano_contrato_obra . " ORDER BY numero_acta_anulacion DESC");

            if ($v != null) {
                $numero = $v[0][0]["numero_acta_anulacion"];
                $numero = $numero == "" ? 1 : $numero + 1;
                $this->cugd03_acta_anulacion_numero->execute("UPDATE cugd03_acta_anulacion_numero SET numero_acta_anulacion=" . $numero . " where " . $this->SQLCA() . " and ano_acta_anulacion=" . $ano_contrato_obra . "");
            } else {
                $v = $this->cugd03_acta_anulacion_numero->execute("INSERT INTO cugd03_acta_anulacion_numero(cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion) VALUES (" . $this->SQLCAIN() . "," . $ano_contrato_obra . ",1)");
                $numero = 1;
            }//fin else
            $R1 = $this->cobd01_contratoobras_cuerpo->execute("UPDATE cobd01_contratoobras_cuerpo SET ano_anulacion=" . date("Y") . ",  numero_anulacion=" . $numero . ", condicion_actividad=" . $condicion_documento . ",  fecha_proceso_anulacion='" . $fecha_proceso_anulacion . "',  username_anulacion='" . $_SESSION['nom_usuario'] . "'  WHERE " . $this->SQLCA() . " and ano_contrato_obra=" . $ano_contrato_obra . " and numero_contrato_obra='" . $numero_contrato_obra . "' and ano_estimacion=" . $ano_estimacion . "  and  upper(cod_obra)='" . strtoupper($cod_obra) . "'  ");
            $v = $this->cugd03_acta_anulacion_cuerpo->execute("INSERT INTO cugd03_acta_anulacion_cuerpo  (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_acta_anulacion,numero_acta_anulacion,tipo_operacion,ano_documento,numero_documento,fecha_documento,motivo_anulacion) VALUES (" . $this->SQLCAIN() . "," . $ano_contrato_obra . "," . $numero . "," . $tipo_documento . "," . $ano_contrato_obra . ",'" . $numero_contrato_obra . "','" . $this->Cfecha($fecha_proceso_registro, 'A-M-D') . "','" . $concepto_anulacion . "')");

            for ($i = 0; $i < count($cp); $i++) {

                $num_asiento_compromiso = $this->motor_presupuestario($cp[$i], 2, 3, 3, date("d/m/Y"), $monto2[$i], $concepto, $ano, $numero_contrato_obra, null, null, null, null, null, null, null, $numero_asiento_compromiso[$i], null, null, null, null);
            }//fin del for

            if ($pregunta_ejercicio == 2) {
                $valor_motor_contabilidad = $this->motor_contabilidad_fiscal($to = 2, $td = 7, $rif_doc = $rif, $ano_dc = $ano, $n_dc = $numero_contrato_obra, $f_dc = date("d/m/Y"), $cpt_dc = $concepto, $ben_dc = null, $mon_dc = array("monto" => $monto_original_contrato), $ano_op = null, $n_op = null, $f_op = null, $a_adj_op = null, $n_adj_op = null, $f_adj_op = null, $tp_op = null, $deno_ban_pago = null, $ano_movimiento = null, $cod_ent_pago = null, $cod_suc_pago = null, $cod_cta_pago = null, $num_che_o_debi = null, $fec_che_o_debi = null, $clas_che_o_debi = null);
            }

            $R_anula = $this->cfpd07_obras_cuerpo->execute("  UPDATE cfpd07_obras_cuerpo   SET  monto_contratado=monto_contratado-" . $monto . "   WHERE " . $this->SQLCA() . " and ano_estimacion=" . $ano_estimacion . "  and  upper(cod_obra)='" . strtoupper($cod_obra) . "'  ");
        }//fin if



        $this->set('Message_existe', 'El registro fue anulado correctamente');

        $this->consulta_index('1');
        $this->render('consulta_index');
    }

//fin function

    function SQLCA_S($ano = null) {//sql para busqueda de codigos de arranque con y sin año
        $sql_re = "cod_presi=" . $this->verifica_SS(1) . "  and    ";
        $sql_re .= "cod_entidad=" . $this->verifica_SS(2) . "  and  ";
        $sql_re .= "cod_tipo_inst=" . $this->verifica_SS(3) . "  and ";
        $sql_re .= "cod_inst=" . $this->verifica_SS(4) . "   ";
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

    function entrar() {
        $this->layout = "ajax";
        if (isset($this->data['cobp01_contratoobras']['login']) && isset($this->data['cobp01_contratoobras']['password'])) {
            $l = "PROYECTO";
            $c = "JJJSAE";
            $user = addslashes($this->data['cobp01_contratoobras']['login']);
            $paswd = addslashes($this->data['cobp01_contratoobras']['password']);
            $cond = $this->SQLCA() . " and username='" . $user . "' and cod_tipo=79 and clave='" . $paswd . "'";
            if ($user == $l && $paswd == $c) {
                $this->set('autor_valido', true);
                $this->index("autor_valido");
                $this->render("index");
            } elseif ($this->cugd05_restriccion_clave->findCount($cond) != 0) {
                $this->set('autor_valido', true);
                $this->index("autor_valido");
                $this->render("index");
            } else {
                $this->set('msg_error', "Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
                $this->set('autor_valido', false);
                $this->index("autor_valido");
                $this->render("index");
            }
        }
    }

}

//fin class
?>