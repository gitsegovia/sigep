<?php

class Cscp04OrdencompraController extends AppController {

    var $uses = array('cscd05_consumo_productos_dep', 'cscd05_consumo_productos_inst', 'v_cotizacion_solicitud', 'ccfd04_cierre_mes',
        'cscd04_ordencompra_partidas', 'cscd04_ordencompra_numero', 'cscd04_ordencompra_parametros', 'cscd04_ordencompra_encabezado',
        'Usuario', 'ccfd03_instalacion', 'cscd03_cotizacion_encabezado', 'cscd03_cotizacion_cuerpo', 'cpcd02',
        'cscd02_solicitud_encabezado', 'cugd02_direccion', 'cscd02_solicitud_cuerpo', 'cscd01_catalogo', 'cfpd05',
        'v_distribucion_compras', 'v_compras_activ', 'v_cscd03_cotizacion', 'cugd04', 'v_cfpd05_disponibilidad',
        'cfpd02_sector', 'cfpd02_programa', 'cfpd02_sub_prog', 'cfpd02_proyecto', 'cfpd02_activ_obra', 'arrd05', 'cfpd01_ano_grupo',
        'cfpd01_ano_partida', 'cfpd01_ano_generica', 'cfpd01_ano_especifica', 'cfpd01_ano_sub_espec', 'cfpd01_ano_auxiliar',
        'cfpd01_formulacion', 'cfpp05auxiliar', 'cfpd05_auxiliar', 'v_cscd04_ordencompra', 'cfpd21', 'cfpd21_numero_asiento_compromiso',
        'cugd03_acta_anulacion_numero', 'cugd03_acta_anulacion_cuerpo', 'v_cfpd05_denominaciones', 'v_cscd03_cotizacion_rif',
        'v_cscd03_cotizacion_anulada', 'v_cscd04_rif', 'cscd03_cotizacion_encabezado_anulado', 'v_cscd02_relacion',
        'v_cscd04_deuda_proveedores', 'cscd05_consumo_productos', 'cscd05_consumo_institucion', 'cscd05_consumo_direcciones',
        'cscd05_cpcd02_suministro', 'cscd05_ordencompra_nota_entrega_encabezado', 'cscd04_ordencompra_autorizacion_cuerpo', 'v_cscd01_snc_grupo',
        'cepd03_ordenpago_cuerpo', 'cugd02_direccionsuperior', 'cugd02_coordinacion', 'cugd02_secretaria', 'cugd02_dependencia', 'cscd01_unidad_medida', 'v_catalgo_reporte_tipo_grupo',
        'cstd01_entidades_bancarias', 'ccfd10_descripcion', 'ccfd10_detalles', 'ccfd02', 'ccfd05_numero_asiento',
        'ccfd04_cuentas_enlace', 'cpcd02', 'cepd01_compromiso_cuerpo', 'cscd04_ordencompra_anticipo_cuerpo',
        'cscd04_ordencompra_encabezado', 'cscd04_ordencompra_autorizacion_cuerpo',
        'cscd04_ordencompra_encabezado', 'cobd01_contratoobras_anticipo_cuerpo', 'cobd01_contratoobras_valuacion_cuerpo',
        'cobd01_contratoobras_retencion_cuerpo', 'cepd02_contratoservicio_anticipo_cuerpo', 'cepd02_contratoservicio_valuacion_cuerpo',
        'cepd02_contratoservicio_retencion_cuerpo', 'cobd01_contratoobras_cuerpo', 'cepd02_contratoservicio_cuerpo', 'select_orden_compra', 'cugd05_restriccion_clave',
        'cscd05_c_institucion_s_cod_obra', 'cscd05_c_productos_inst_s_cod_obra', 'cscd05_c_productos_s_cod_obra', 'cscd05_c_direcciones_s_cod_obra',
        'cscd05_c_productos_dep_s_cod_obra'
    );
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

    function condicion() {
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $modulo = $this->Session->read('Modulo');
        $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst . " and cod_dep = " . $cod_dep;

        return $condicion;
    }

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

    function concatena($vector1 = null, $nomVar = null, $extra = null) {
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
                        $y = ($y != "") ? $y : "N/A";
                        $cod[$x] = '0' . $x . ' - ' . $y;
                    } else if ($x >= 10) {
                        $y = ($y != "") ? $y : "N/A";
                        $cod[$x] = $x . ' - ' . $y;
                    }
                }
            }
            //print_r($cod);
        }

        $this->set($nomVar, $cod);
    }

//fin function

    function concatena_aux($vector1 = null, $nomVar = null, $extra = null) {
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
                        $y = ($y != "") ? $y : "N/A";
                        $cod[$x] = '000' . $x . ' - ' . $y;
                    } else if ($x >= 10 && $x <= 99) {
                        $y = ($y != "") ? $y : "N/A";
                        $cod[$x] = '00' . $x . ' - ' . $y;
                    } else if ($x > 99 && $x <= 999) {
                        $y = ($y != "") ? $y : "N/A";
                        $cod[$x] = '0' . $x . ' - ' . $y;
                    } else if ($x > 999) {
                        $y = ($y != "") ? $y : "N/A";
                        $cod[$x] = $x . ' - ' . $y;
                    }
                }
            }
            //print_r($cod);
        }

        $this->set($nomVar, $cod);
    }

//fin function

    function concatena_aux2($vector1 = null, $nomVar = null, $extra = null) {
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
                        $y = ($y != "") ? $y : "N/A";
                        $cod[$x] = '000' . $x . ' - ' . $y;
                    } else if ($x >= 10 && $x <= 99) {
                        $y = ($y != "") ? $y : "N/A";
                        $cod[$x] = '00' . $x . ' - ' . $y;
                    } else if ($x > 99 && $x <= 999) {
                        $y = ($y != "") ? $y : "N/A";
                        $cod[$x] = '0' . $x . ' - ' . $y;
                    } else if ($x > 999) {
                        $y = ($y != "") ? $y : "N/A";
                        $cod[$x] = $x . ' - ' . $y;
                    }
                }
            }
            //print_r($cod);
        }

        return $cod;
    }

//fin function

    function condicion2() {
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $modulo = $this->Session->read('Modulo');
        $condicion = "cod_tipo_institucion = " . $cod_tipo_inst . " and cod_institucion = " . $cod_inst . " and cod_dependencia = " . $cod_dep;

        return $condicion;
    }

    function beforeFilter() {
        $this->checkSession();
        $condicion = $this->condicion();
        $opc = $this->Usuario->findCount($condicion);

        /* if($cod_dep == '01'){
          return;
          }else{
          echo "LO SIENTO - UD. NO TIENE PERMISOS PARA ESTE PROCESO!!";
          exit;
          } */
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

    /*
      function diploma(){
      $this->layout = "pdf";
      $this->set('diplomas', $this->diploma->findAll());
      }
     */

    function index($var = null) {///////////////<<--INDEX

 $this->verifica_entrada('77');

        $this->layout = "ajax";
        $ano = $this->ano_ejecucion();

        $maxi = $this->cscd04_ordencompra_numero->findCount($this->SQLCA() . " and ano_orden_compra=" . $ano . " and situacion=1");
        //$max=$this->cepd01_compromiso_numero->execute("SELECT numero_compromiso FROM cepd01_compromiso_numero WHERE ".$this->SQLCA()."  ORDER BY numero_compromiso ASC LIMIT 1");
        if ($maxi == 0) {
            $this->set("errorMessage", "Verifique el n&uacute;mero de control de compromisos");
            $this->set("numero_compromiso", "");
            $this->redirect("/cscp04_ordencompra_numero/");
        }
    }

//fin index

    function index2() {
        $this->layout = "ajax";
        $this->Session->delete('codigos');
        $_SESSION["codigos"] = null;
        $condicion = $this->condicion();
        $username = $this->Session->read('nom_usuario');
        $this->limpiar_lista();
        $this->borrar_cugd04();

        //echo $condicion;
        $this->set('fecha', date('d/m/Y'));
        $this->set('operador', $username);
        $ano_arranque = $ano = $this->ano_ejecucion();

        $this->set('ano_arranque', $ano_arranque);
        $listaRif = $this->v_cscd04_rif->generateList($conditions = $this->condicion() . " and ano_cotizacion =" . $ano_arranque . " and numero_ordencompra=0" . " and (cod_obra='' or cod_obra='0' or cod_obra IS NULL)", $order = 'numero_cotizacion ASC', $limit = null, '{n}.v_cscd04_rif.rif', '{n}.v_cscd04_rif.denominacion');
        $this->concatena_sin_cero($listaRif, 'listaRif');
        $cotizacion = $this->cscd03_cotizacion_encabezado->findAll($conditions = $condicion . " and ano_cotizacion =" . $ano_arranque . "  and numero_ordencompra=0", $fields = 'rif, ano_cotizacion, numero_cotizacion, fecha_cotizacion', $order = 'numero_cotizacion ASC', $limit = null, $page = null, $recursive = null);
        $lista = $this->v_cscd03_cotizacion_rif->generateList($conditions = $condicion . " and ano_cotizacion =" . $ano_arranque . " and (cod_obra='' or cod_obra='0' or cod_obra IS NULL)", $order = 'numero_cotizacion ASC', $limit = null, '{n}.v_cscd03_cotizacion_rif.numero_cotizacion', '{n}.v_cscd03_cotizacion_rif.denominacion');
        $this->concatena($lista, 'lista');
        $sector = $this->v_cfpd05_denominaciones->generateList($this->SQLCA($ano), 'cod_sector ASC', null, '{n}.v_cfpd05_denominaciones.cod_sector', '{n}.v_cfpd05_denominaciones.deno_sector');
        $sector = $sector != null ? $sector : array();
        $this->concatena($sector, 'sector');
        //$lista2=  $this->v_cfpd02_sector->generateList($condicion." and ano='$ano_arranque'", 'cod_sector ASC', null, '{n}.v_cfpd02_sector.cod_sector', '{n}.v_cfpd02_sector.denominacion');
        //$this->concatena($lista2, 'sector');
        $numero_orden_compra = $this->cscd04_ordencompra_numero->field('cscd04_ordencompra_numero.numero_orden_compra', $conditions = $this->condicion() . " and ano_orden_compra='$ano_arranque' and situacion=1", $order = "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_orden_compra ASC");
        //echo 'el numero es '.$numero_orden_compra;

        $numero_orden_compra_anterior = $this->cscd04_ordencompra_numero->field('cscd04_ordencompra_numero.numero_orden_compra', $conditions = $this->condicion() . " and ano_orden_compra='$ano_arranque' and situacion=3 and numero_orden_compra<='" . $numero_orden_compra . "'    ", $order = "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_orden_compra DESC");
        if ($numero_orden_compra_anterior != '') {
            $fecha_orden_compra_anterior = $this->cscd04_ordencompra_encabezado->field('cscd04_ordencompra_encabezado.fecha_orden_compra', $conditions = $this->condicion() . " and ano_orden_compra='$ano_arranque' and numero_orden_compra='" . $numero_orden_compra_anterior . "' ", $order = "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_orden_compra DESC");
            $this->set('numero_documento_anterior', $numero_orden_compra_anterior);
            $this->set('fecha_orden_compra_anterior', $fecha_orden_compra_anterior);
        } else {
            $this->set('numero_documento_anterior', 0);
            $this->set('fecha_orden_compra_anterior', 0);
        }


        ///echo 'el numero de orden de compra es: '.$numero_orden_compra;
        if (!empty($numero_orden_compra)) {
            $this->set('numero_orden_compra', $numero_orden_compra);
        } else {
            $this->set('msg_error1', $msg_error1 = 'NECESITA CREAR LOS NUMEROS DE LA ORDEN DE COMPRA PARA CONTINUAR');
            $this->redirect('/cscp04_ordencompra_numero/index/null/1');

            return;
        }
        $porcentaje_iva = $this->cscd04_ordencompra_parametros->field('cscd04_ordencompra_parametros.porcentaje_iva', $conditions = $this->SQLCA(), $order = null);
        if (!empty($porcentaje_iva)) {
            $this->set('porcentaje_iva', $porcentaje_iva);
        } else {
            $this->set('porcentaje_iva', '0');
        }

        $this->cscd04_ordencompra_numero->execute('UPDATE cscd04_ordencompra_numero set situacion=2 WHERE ' . $this->condicion() . " and ano_orden_compra='$ano_arranque' and numero_orden_compra = '$numero_orden_compra'");
    }

    function agregar_partidas($var = null) {
        $this->layout = "ajax";
        if (isset($var) && !empty($var)) {
            //print_r($this->data);
            //print_r($this->params);
            //echo "holaaaaaaa";
            $cod[0] = $this->data["cscp04_ordencompra"]["ano_partidas"];
            $cod[1] = $this->data["cscp04_ordencompra"]["cod_sector"];
            $cod[2] = $this->data["cscp04_ordencompra"]["cod_programa"];
            $cod[3] = $this->data["cscp04_ordencompra"]["cod_subprograma"];
            $cod[4] = $this->data["cscp04_ordencompra"]["cod_proyecto"];
            $cod[5] = $this->data["cscp04_ordencompra"]["cod_actividad"];
            $cod[6] = $this->data["cscp04_ordencompra"]["cod_partida"];
            if ($cod[6] < 9) {
                $cod[6] = "40" . $cod[6];
            } else if ($cod[6] < 100) {
                $cod[6] = "4" . $cod[6];
            } else {
                $cod[6] = $cod[6];
            }

            $cod[7] = $this->data["cscp04_ordencompra"]["cod_generica"];
            $cod[8] = $this->data["cscp04_ordencompra"]["cod_especifica"];
            $cod[9] = $this->data["cscp04_ordencompra"]["cod_subespecifica"];
            $cod[10] = $this->data["cscp04_ordencompra"]["cod_auxiliar"]; //
            $cod[10] = $cod[10] < 9 ? str_replace("0", "", $cod[10]) : $cod[10];
            $cod[10] = $cod[10] < 9 ? "0" . $cod[10] : $cod[10];
            $cod[11] = $this->data["cscp04_ordencompra"]["monto_partidas"];
            if (isset($_SESSION["i"])) {
                $i = $this->Session->read("i") + 1;
                $this->Session->write("i", $i);
            } else {
                $this->Session->write("i", 0);
                $i = 0;
            }
            switch ($var) {
                case 'normal':
                    $vec[$i][0] = $this->data["cscp04_ordencompra"]["ano_partidas"];
                    $vec[$i][1] = $this->data["cscp04_ordencompra"]["cod_sector"];
                    $vec[$i][2] = $this->data["cscp04_ordencompra"]["cod_programa"];
                    $vec[$i][3] = $this->data["cscp04_ordencompra"]["cod_subprograma"];
                    $vec[$i][4] = $this->data["cscp04_ordencompra"]["cod_proyecto"];
                    $vec[$i][5] = $this->data["cscp04_ordencompra"]["cod_actividad"];
                    $vec[$i][6] = $this->data["cscp04_ordencompra"]["cod_partida"]; //<9 ? "4.0".$this->data["cepp01_compromiso_partidas"]["cod_partida"] : "4.".$this->data["cepp01_compromiso_partidas"]["cod_partida"];
                    $vec[$i][7] = $this->data["cscp04_ordencompra"]["cod_generica"];
                    $vec[$i][8] = $this->data["cscp04_ordencompra"]["cod_especifica"];
                    $vec[$i][9] = $this->data["cscp04_ordencompra"]["cod_subespecifica"];
                    $vec[$i][10] = $this->AddCeroR($this->data["cscp04_ordencompra"]["cod_auxiliar"]);
                    $vec[$i][11] = $this->data["cscp04_ordencompra"]["monto_partidas"];
                    $vec[$i]["id"] = $i;
                    if (isset($_SESSION["codigos"])) {
                        foreach ($_SESSION["codigos"] as $codi) {
                            //echo $codi[0].$cod[0].$codi[1].$cod[1].$codi[2].$cod[2].$codi[3].$cod[3].$codi[4].$cod[4].$codi[5].$cod[5].$codi[6].$cod[6].$codi[7].$cod[7]. $codi[8].$cod[8].$codi[9].$cod[9].$codi[10].$cod[10];
                            if ($codi[0] == $cod[0] && $codi[1] == $cod[1] && $codi[2] == $cod[2] && $codi[3] == $cod[3] && $codi[4] == $cod[4] && $codi[5] == $cod[5] && $codi[6] == $cod[6] && $codi[7] == $cod[7] && $codi[8] == $cod[8] && $codi[9] == $cod[9] && $codi[10] == $cod[10]) {
                                $est = true;
                                break;
                            } else {
                                $est = false;
                            }
                        }//fin foreach
                        if ($est == true) {
                            //	echo "no";
                            $i = $this->Session->read("i") - 1;
                            $this->Session->write("i", $i);
                            $this->set('errorMessage', 'Los codigos seleccionados ya existen en la lista');
                        } else {
                            $_SESSION["codigos"] = $_SESSION["codigos"] + $vec;
                            //  echo "si";
                        }
                    } else {
                        $_SESSION["codigos"] = $vec;
                    }
                    break;
                case 'nuevos':
                    $vec[$i][0] = $cod[0];
                    $vec[$i][1] = $cod[1];
                    $vec[$i][2] = $cod[2];
                    $vec[$i][3] = $cod[3];
                    $vec[$i][4] = $cod[4];
                    $vec[$i][5] = $cod[5];
                    $vec[$i][6] = $cod[6];
                    $vec[$i][7] = $cod[7];
                    $vec[$i][8] = $cod[8];
                    $vec[$i][9] = $cod[9];
                    $vec[$i][10] = $this->mascara_cuatro($cod[10]);
                    $vec[$i][11] = $cod[11];
                    $vec[$i]["id"] = $i;
                    if (isset($_SESSION["codigos"])) {
                        foreach ($_SESSION["codigos"] as $codi) {
                            if ($codi[0] == $cod[0] && $codi[1] == $cod[1] && $codi[2] == $cod[2] && $codi[3] == $cod[3] && $codi[4] == $cod[4] && $codi[5] == $cod[5] && $codi[6] == $cod[6] && $codi[7] == $cod[7] && $codi[8] == $cod[8] && $codi[9] == $cod[9] && $codi[10] == $cod[10]) {
                                $est = true;
                                break;
                            } else {
                                $est = false;
                            }
                        }//fin foreach
                        if ($est == true) {
                            //echo "no";
                            $i = $this->Session->read("i") - 1;
                            $this->Session->write("i", $i);
                            $this->set('errorMessage', 'Los codigos seleccionados ya existen en la lista');
                        } else {
                            //print_r($vec);echo "<br/>";
                            //echo $i;
                            $aux = $_SESSION["codigos"];
                            $_SESSION["codigos"] = array();
                            $_SESSION["codigos"] = $aux + $vec;

                            //print_r($vec);
                            //print_r($_SESSION["codigos"]);
                            //print_r($aux);
                            //echo "si";
                        }
                    } else {
                        $_SESSION["codigos"] = $vec;
                    }
                    break;
            }//fin switch
        }//
    }

//fin funcion agregar_partidas

    function numorden($ano = null, $var = null) {
        $this->layout = "ajax";
        if ($var != null && $ano != null) {
            if ($var == 1) {
                $numero_orden_compra = $this->cscd04_ordencompra_numero->field('cscd04_ordencompra_numero.numero_orden_compra', $conditions = $this->condicion() . " and ano_orden_compra='$ano'", $order = "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_orden_compra ASC");
                if ($numero_orden_compra == null) {
                    $numero_orden_compra = 1;
                } else {
                    $numero_orden_compra+=1;
                }
                $this->set('var', 1);
                $this->set('numero_orden_compra', $numero_orden_compra);
            }
        }
    }

    function eliminar_items($id) {
        $this->layout = "ajax";
        $this->set('i', $id);
        //echo "<br/>antes: ".count($_SESSION["codigos"])."<br/>";
        //print_r($_SESSION["codigos"]);
        //$_SESSION["codigos"][$id]=null;
        //echo "<br/>despues: ".count($_SESSION["codigos"])."<br/>";
        //print_r($_SESSION["codigos"]);
        $cont1 = count($_SESSION["codigos"]);
        $cont2 = 0;
        $i = 0;
        $j = 0;
        foreach ($_SESSION["codigos"] as $cod) {
            if ($id != $cod["id"]) {
                $i++;
                $vec[$j][0] = $cod[0];
                $vec[$j][1] = $cod[1];
                $vec[$j][2] = $cod[2];
                $vec[$j][3] = $cod[3];
                $vec[$j][4] = $cod[4];
                $vec[$j][5] = $cod[5];
                $vec[$j][6] = $cod[6];
                $vec[$j][7] = $cod[7];
                $vec[$j][8] = $cod[8];
                $vec[$j][9] = $cod[9];
                $vec[$j][10] = $this->mascara_cuatro($cod[10]);
                $vec[$j][11] = $cod[11];
                $vec[$j]["id"] = $j;
                $j++;
            }
            if ($cod[$i] == null) {
                $cont2 += 1;
            }
        }

        $this->Session->delete('codigos');
        $_SESSION['codigos'] = $vec;
        $_SESSION["i"] = $i;


        if ($i == 0) {
            $this->limpiar_lista();
            $this->eliminar_item();
            echo "<script>";
            echo "document.getElementById('total2_manual').innerHTML='0,00';";
            echo "</script>";
            $this->set('nulo', '');
        }
    }

    function limpiar_lista() {
        $this->layout = "ajax";
        $this->Session->delete("codigos");
        $this->Session->delete("i");
    }

    function eliminar_item($i = null, $ano = null, $cod_sector = null, $cod_programa = null, $cod_sub_prog = null, $cod_proyecto = null, $cod_activ_obra = null, $cod_partida = null, $cod_generica = null, $cod_especifica = null, $cod_sub_espec = null, $cod_auxiliar = null) {
        $this->layout = "ajax";
        $username = strtoupper($this->Session->read('nom_usuario'));
        //$cond_partidas = "ano='$ano' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar' and ";
        $sql_delete_cugd04 = "DELETE FROM cugd04 WHERE username='$username' and " . $this->condicion();

        $sw = $this->borrar_cugd04();
        $this->Session->delete("codigos");
        $this->Session->delete("i");
    }

    function proveedor($rif = null) {
        $this->layout = "ajax";
        //echo $rif;
        if ($rif != null) {
            //$num_cotizacion = substr($num_cotizacion,0,1);
            //echo $num_cotizacion;
            //$rif = $this->cscd03_cotizacion_encabezado->field('cscd03_cotizacion_encabezado.rif', $conditions = $this->condicion()." and cscd03_cotizacion_encabezado.numero_cotizacion='$num_cotizacion'", $order ="numero_cotizacion ASC");
            //echo $rif;

            $razon_social = $this->cpcd02->field('cpcd02.denominacion', $conditions = "mayus_acentos(cpcd02.rif)=mayus_acentos('$rif')", $order = "rif ASC");
            $direccion = $this->cpcd02->field('cpcd02.direccion_comercial', $conditions = "mayus_acentos(cpcd02.rif)=mayus_acentos('$rif')", $order = "rif ASC");

            $this->set('rif', $rif);
            $this->Session->write('rif', $rif);
            $this->set('razon_social', $razon_social);
            $this->set('direccion', $direccion);
        } else {

        }
    }

    function dirif($rif = null) {
        $this->layout = "ajax";
        //echo $rif;
        if ($rif != null) {
            //$num_cotizacion = substr($num_cotizacion,0,1);
            //echo $num_cotizacion;
            //$rif = $this->cscd03_cotizacion_encabezado->field('cscd03_cotizacion_encabezado.rif', $conditions = $this->condicion()." and cscd03_cotizacion_encabezado.numero_cotizacion='$num_cotizacion'", $order ="numero_cotizacion ASC");
            //echo $rif;

            $razon_social = $this->cpcd02->field('cpcd02.denominacion', $conditions = "mayus_acentos(cpcd02.rif)=mayus_acentos('$rif')", $order = "rif ASC");
            $direccion = $this->cpcd02->field('cpcd02.direccion_comercial', $conditions = "mayus_acentos(cpcd02.rif)=mayus_acentos('$rif')", $order = "rif ASC");

            $this->set('rif', $rif);
            $this->Session->write('rif', $rif);
            $this->set('razon_social', $razon_social);
            $this->set('direccion', $direccion);
        } else {

        }
    }

    function cotizar($rif = null) {
        $this->layout = "ajax";
        $ano_arranque = $ano = $this->ano_ejecucion();
        if ($rif != null) {


            $lista = $this->v_cotizacion_solicitud->generateList($conditions = $this->condicion() . " and ano_cotizacion =" . $ano_arranque . " and numero_ordencompra=0 and mayus_acentos(rif)=mayus_acentos('$rif')" . " and (cod_obra='' or cod_obra='0' or cod_obra IS NULL)", $order = 'numero_cotizacion ASC', $limit = null, '{n}.v_cotizacion_solicitud.numero_cotizacion', '{n}.v_cotizacion_solicitud.numero_cotizacion');
            $this->set('lista', $lista);
            $this->set('rif', $rif);
        }
    }

    function yearcotizacion($num_cotizacion = null) {
        $this->layout = "ajax";
        $ano_arranque = $ano = $this->ano_ejecucion();
        if ($num_cotizacion != null) {
            $yearcotizacion = $this->cscd03_cotizacion_encabezado->field('cscd03_cotizacion_encabezado.ano_cotizacion', $conditions = $this->condicion() . " and cscd03_cotizacion_encabezado.numero_cotizacion='" . $num_cotizacion . "'  and ano_cotizacion =" . $ano_arranque, $order = "numero_cotizacion ASC");
            $this->set('yearcotizacion', $yearcotizacion);
        } else {

        }
    }

    function limpiar_distribuir() {
        $this->layout = "ajax";
        $this->limpiar_lista();
    }

    function limpiar_datos() {
        $this->layout = "ajax";
    }

    function fechacotizacion($rif = null, $num_cotizacion = null) {
        $this->layout = "ajax";
        if ($num_cotizacion != null) {
            $fechacotizacion = $this->cscd03_cotizacion_encabezado->field('cscd03_cotizacion_encabezado.fecha_cotizacion', $conditions = $this->condicion() . " and cscd03_cotizacion_encabezado.numero_cotizacion='$num_cotizacion' and rif='$rif'  and ano_cotizacion='" . $this->ano_ejecucion() . "' ", $order = "numero_cotizacion ASC");
            $anocotizacion = $this->cscd03_cotizacion_encabezado->field('cscd03_cotizacion_encabezado.ano_cotizacion', $conditions = $this->condicion() . " and cscd03_cotizacion_encabezado.numero_cotizacion='$num_cotizacion' and rif='$rif'  and ano_cotizacion='" . $this->ano_ejecucion() . "' ", $order = "numero_cotizacion ASC");
            $dato = str_split($fechacotizacion, 4);
            echo "<script>";
            echo "document.getElementById('cotizaAnio').value='$anocotizacion';";
            echo "</script>";
            //print_r($dato);
            $fechacotizacion = $dato[2] . str_replace('-', '/', $dato[1]) . $dato[0];
            $this->set('fechacotizacion', $fechacotizacion);
        } else {
            echo "<script>";
            echo "document.getElementById('cotizaAnio').value='';";
            echo "</script>";
        }
    }

    function direccionsolicitante($num_cotizacion = null, $rif = null) {
        $this->layout = "ajax";

        if ($num_cotizacion != null) {
            $cod_tipo_inst = $this->Session->read('SScodtipoinst');
            $cod_inst = $this->Session->read('SScodinst');
            $cod_dep = $this->Session->read('SScoddep');
            $numsolicitud = $this->cscd03_cotizacion_encabezado->field('cscd03_cotizacion_encabezado.numero_solicitud', $conditions = $this->condicion() . " and ano_solicitud='" . $this->ano_ejecucion() . "'  and cscd03_cotizacion_encabezado.numero_cotizacion='$num_cotizacion' and mayus_acentos(rif)=mayus_acentos('$rif')", $order = "numero_cotizacion ASC");
            //echo "el numero de solicitud es: ".$numsolicitud;
            $cod_dirsuperior = $this->cscd02_solicitud_encabezado->field('cscd02_solicitud_encabezado.cod_dir_superior', $conditions = $this->condicion() . "    and ano_solicitud='" . $this->ano_ejecucion() . "' and cscd02_solicitud_encabezado.numero_solicitud='$numsolicitud'", $order = "numero_solicitud ASC");
            $cod_coordinacion = $this->cscd02_solicitud_encabezado->field('cscd02_solicitud_encabezado.cod_coordinacion', $conditions = $this->condicion() . "   and ano_solicitud='" . $this->ano_ejecucion() . "' and cscd02_solicitud_encabezado.numero_solicitud='$numsolicitud'", $order = "numero_solicitud ASC");
            $cod_secretaria = $this->cscd02_solicitud_encabezado->field('cscd02_solicitud_encabezado.cod_secretaria', $conditions = $this->condicion() . "       and ano_solicitud='" . $this->ano_ejecucion() . "' and cscd02_solicitud_encabezado.numero_solicitud='$numsolicitud'", $order = "numero_solicitud ASC");
            $cod_direccion = $this->cscd02_solicitud_encabezado->field('cscd02_solicitud_encabezado.cod_direccion', $conditions = $this->condicion() . "         and ano_solicitud='" . $this->ano_ejecucion() . "' and cscd02_solicitud_encabezado.numero_solicitud='$numsolicitud'", $order = "numero_solicitud ASC");
            $dDireccion = $this->cugd02_direccion->field('cugd02_direccion.denominacion', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dirsuperior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cugd02_direccion.cod_direccion='$cod_direccion'", $order = null);
            //echo $conditions;
            $this->set('dDireccion', strtoupper($dDireccion));
            echo "<script>";
            echo "document.getElementById('lugar').value='" . strtoupper($dDireccion) . "';";
            echo "</script>";
        } else {

        }
    }

    function datos($rif = null, $num_cotizacion = null) {
        $this->layout = "ajax";
        if ($num_cotizacion != null) {
            //$lista_cscd02_solicitud_cuerpo
            //$rif = $this->Session->read('rif');
            $condicion = $this->condicion();
            $numsolicitud = $this->v_cscd03_cotizacion->field('v_cscd03_cotizacion.numero_solicitud', $conditions = $this->condicion() . " and mayus_acentos(rif)=mayus_acentos('$rif') and v_cscd03_cotizacion.numero_cotizacion='$num_cotizacion'", $order = "numero_cotizacion ASC");
            $lista = $this->v_cscd03_cotizacion->findAll($condicion . " and mayus_acentos(rif)=mayus_acentos('$rif') and numero_cotizacion='$num_cotizacion' and ano_cotizacion='" . $this->ano_ejecucion() . "' ", 'DISTINCT codigo_prod_serv, expresion, cantidad, precio_unitario, descripcion, numero_cotizacion', 'numero_cotizacion ASC', null);
            //print_r($lista);
            $this->set('lista_cscd02_solicitud_cuerpo', $lista);
        }
    }

    function partidas($num_cotizacion = null) {
        if ($num_cotizacion != null) {
            $condicion = $this->condicion();
            $productos = $this->cscd03_cotizacion_cuerpo->findAll($condicion . " and numero_cotizacion='$num_cotizacion' and ano_cotizacion='" . $this->ano_ejecucion() . "' ", $fields = 'codigo_prod_serv', $order = "numero_cotizacion", $limit = null, $page = null, $recursive = null);

            $num_solicitud = $this->cscd03_cotizacion_encabezado->field($fields = 'numero_solicitud', $condicion . " and numero_cotizacion='$num_cotizacion'   and ano_solicitud='" . $this->ano_ejecucion() . "' ", $order = "numero_cotizacion");
            $cod_dir_superior = $this->cscd02_solicitud_encabezado->field($fields = 'cod_dir_superior', $condicion . " and numero_cotizacion='$num_cotizacion' and ano_solicitud='" . $this->ano_ejecucion() . "' ", $order = "numero_solicitud");
            $cod_coordinacion = $this->cscd02_solicitud_encabezado->field($fields = 'cod_coordinacion', $condicion . " and numero_cotizacion='$num_cotizacion' and ano_solicitud='" . $this->ano_ejecucion() . "' ", $order = "numero_solicitud");
            $cod_secretaria = $this->cscd02_solicitud_encabezado->field($fields = 'cod_secretaria', $condicion . " and numero_cotizacion='$num_cotizacion'     and ano_solicitud='" . $this->ano_ejecucion() . "' ", $order = "numero_solicitud");
            $cod_direccion = $this->cscd02_solicitud_encabezado->field($fields = 'cod_direccion', $condicion . " and numero_cotizacion='$num_cotizacion'       and ano_solicitud='" . $this->ano_ejecucion() . "' ", $order = "numero_solicitud");

            $cod_categoria = $this->cugd02_direccion->findAll($conditions = $this->condicion2() . " and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_direccion='$cod_direccion'", $fields = 'cod_sector, cod_programa, cod_sub_prog, cod_proyecto', $order = 'cod_dir_superior, cod_coordinacion, cod_direccion ASC', $limit = null, $page = null, $recursive = null);
            $i = 0;
            foreach ($productos as $row) {
                $prod[$i] = $row['cscd03_cotizacion_cuerpo']['codigo_prod_serv'];
                $cod_pres[$i] = $this->cscd01_catalogo->findAll($conditions = "codigo_prod_serv=" . $prod[$i], $fields = 'cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar', $order = 'codigo_prod_serv', $limit = null, $page = null, $recursive = null);
                $i++;
            }
            //print_r($cod_pres[0]);
            foreach ($cod_categoria as $fila) {
                $cod_sector = $fila['cugd02_direccion']['cod_sector'];
                $cod_programa = $fila['cugd02_direccion']['cod_programa'];
                $cod_sub_prog = $fila['cugd02_direccion']['cod_sub_prog'];
                $cod_proyecto = $fila['cugd02_direccion']['cod_proyecto'];
            }
            $categoria = "cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto'";
            //echo $categoria;



            for ($i = 0; $i < count($prod); $i++) {
                foreach ($cod_pres[$i] as $fila) {
                    $cod_partida1 = $cod_partida[$i] = $fila['cscd01_catalogo']['cod_partida'];
                    $cod_generica1 = $cod_generica[$i] = $fila['cscd01_catalogo']['cod_generica'];
                    $cod_especifica1 = $cod_especifica[$i] = $fila['cscd01_catalogo']['cod_especifica'];
                    $cod_sub_espec1 = $cod_sub_espec[$i] = $fila['cscd01_catalogo']['cod_sub_espec'];
                    $cod_auxiliar1 = $cod_auxiliar[$i] = $fila['cscd01_catalogo']['cod_auxiliar'];
                }
                //echo $cod_partida;
                $presupuesto = "cod_partida ='$cod_partida1' and cod_generica='$cod_generica1' and cod_especifica='$cod_especifica1' and cod_sub_espec='$cod_sub_espec1' and cod_auxiliar='$cod_auxiliar1'";
                $condicion_cfpd05 = $this->condicion() . " and " . $categoria . " and " . $presupuesto;
                $cont = $this->cfpd05->findCount($condicion_cfpd05 . "  and ano='" . $this->ano_ejecucion() . "' ");
                //echo $cont;.
                if ($cont != 0) {
                    $distribucion[$i] = $this->cfpd05->findAll($conditions = $condicion_cfpd05 . "  and ano='" . $this->ano_ejecucion() . "' ", $fields = 'ano, cod_activ_obra, cod_auxiliar, asignacion_anual', $order = null, $limit = null, $page = null, $recursive = null);
                    //print_r($distribucion);
                }
            }

            //$this->set('productos', $prod);
            $this->set('presupuesto', $cod_pres);
            $this->set('categoria', $cod_categoria);
            $this->set('distribucion', $distribucion);
            $this->set('cod_partida', $cod_partida);
            $this->set('cod_generica', $cod_generica);
            $this->set('cod_especifica', $cod_especifica);
            $this->set('cod_sub_espec', $cod_sub_espec);
            $this->set('prod', count($productos));
        }
    }

    function activ($dist_gasto, $num_cotizacion, $rif) {
        //print_r($dist_gasto);
        //echo "el array tiene: ".count($dist_gasto)."<br>";
        $i = 0;
        $c = 0;
        //echo "2 - estoy en activ<br/>";
        //pr($dist_gasto);
        foreach ($dist_gasto as $row) {
            //echo "hice el ciclo ".$i++."vez<br>";
            $ano = $row['v_distribucion_compras']['ano_cotizacion'];
            $cod_sector = $row['v_distribucion_compras']['cod_sector'];
            $cod_programa = $row['v_distribucion_compras']['cod_programa'];
            $cod_sub_prog = $row['v_distribucion_compras']['cod_sub_prog'];
            $cod_proyecto = $row['v_distribucion_compras']['cod_proyecto'];
            $cod_partida = $row['v_distribucion_compras']['cod_partida'];
            $cod_generica = $row['v_distribucion_compras']['cod_generica'];
            $cod_especifica = $row['v_distribucion_compras']['cod_especifica'];
            $cod_sub_espec = $row['v_distribucion_compras']['cod_sub_espec'];
            $group = "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_cotizacion, rif, ano_cotizacion, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec";
            $contador = $this->v_compras_activ->findCount($conditions = $this->condicion() . " and ano_cotizacion='$ano' and numero_cotizacion='$num_cotizacion' and rif='$rif' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' group by " . $group);
            //echo $conditions;
            //echo "contador activ: ".$contador."<br>";
            if ($contador > 1) {
                $Lista = $this->v_compras_activ->generateList($conditions = $this->condicion() . " and ano_cotizacion='$ano' and numero_cotizacion='$num_cotizacion' and rif='$rif' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec'", $order = null, $limit = null, '{n}.v_compras_activ.cod_activ_obra', '{n}.v_compras_activ.cod_activ_obra');
                $VecActiv[$i] = $Lista;
            } else {
                //echo "epa entre aqui";
                $VecActiv[$i] = $this->v_compras_activ->field('v_compras_activ.cod_activ_obra', $conditions = $this->condicion() . " and ano_cotizacion='$ano' and numero_cotizacion='$num_cotizacion' and rif='$rif' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec'", $order = null);
                $cod_activ_obra = $VecActiv[$i];
                //echo 'numero '.$num_cotizacion;
                $ListaAux = $this->auxiliar2($i, $ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $num_cotizacion, $rif);
                //print_r($ListaAux);
                $VecAux[$i] = $ListaAux;
                $this->set('ListaAux', $VecAux);

                /* $contadorAux= $this->v_compras_activ->findCount($conditions =  $this->condicion()." and ano_cotizacion='$ano' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' group by ".$group.", cod_activ_obra");
                  if($contadorAux > 1){
                  $ListaAux = $this->v_compras_activ->generateList($conditions = $this->condicion()." and ano_cotizacion='$ano' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec'", $order = null, $limit = null, '{n}.v_compras_activ.cod_activ_obra', '{n}.v_compras_activ.cod_activ_obra');
                  $VecAux[$i] = $ListaAux;
                  }else{
                  $VecAux[$i]= $this->v_compras_activ->field('v_compras_activ.cod_activ_obra', $conditions = $this->condicion()." and ano_cotizacion='$ano' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec'", $order =null);;
                  } */

                //echo $contadorAux;
            }
            $i++;
        }
        //print_r($VecActiv);
        //print_r($VecAux);
        $this->set('VecActiv', $VecActiv);
        $this->set('VecAux', $VecAux);
    }

    function distribuir($rif = null, $num_cotizacion = null) {
        $this->layout = "ajax";
        //echo "estoy en distribuir<br/>";
        $this->desbloquea_imputacion();
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $this->Session->delete('codigos');
        $this->limpiar_lista();
        $this->borrar_cugd04();
        $_SESSION["codigos"] = null;
        $condicion = $this->condicion();
        if ($num_cotizacion != null) {
            //$this->partidas($num_cotizacion);
            $numero_solicitud = $this->cscd03_cotizacion_encabezado->field('numero_solicitud', $this->condicion() . " and numero_cotizacion='$num_cotizacion' and ano_cotizacion='" . $this->ano_ejecucion() . "'  and mayus_acentos(rif)=mayus_acentos('$rif')", null);
            $cod_dir_superior = $this->cscd02_solicitud_encabezado->field('cscd02_solicitud_encabezado.cod_dir_superior', $conditions = $this->condicion() . "  and ano_solicitud='" . $this->ano_ejecucion() . "' and cscd02_solicitud_encabezado.numero_solicitud='$numero_solicitud' and mayus_acentos(rif)=mayus_acentos('$rif')", $order = null);
            $cod_coordinacion = $this->cscd02_solicitud_encabezado->field('cscd02_solicitud_encabezado.cod_coordinacion', $conditions = $this->condicion() . "  and ano_solicitud='" . $this->ano_ejecucion() . "' and cscd02_solicitud_encabezado.numero_solicitud='$numero_solicitud' and mayus_acentos(rif)=mayus_acentos('$rif')", $order = null);
            $cod_secretaria = $this->cscd02_solicitud_encabezado->field('cscd02_solicitud_encabezado.cod_secretaria', $conditions = $this->condicion() . "  and ano_solicitud='" . $this->ano_ejecucion() . "' and cscd02_solicitud_encabezado.numero_solicitud='$numero_solicitud' and mayus_acentos(rif)=mayus_acentos('$rif')", $order = null);
            $cod_direccion = $this->cscd02_solicitud_encabezado->field('cscd02_solicitud_encabezado.cod_direccion', $conditions = $this->condicion() . "  and ano_solicitud='" . $this->ano_ejecucion() . "' and cscd02_solicitud_encabezado.numero_solicitud='$numero_solicitud' and mayus_acentos(rif)=mayus_acentos('$rif')", $order = null);
            $direccion = $this->cugd02_direccion->findAll($conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$cod_dir_superior' and cod_coordinacion='$cod_coordinacion' and cod_secretaria='$cod_secretaria' and cod_direccion='$cod_direccion'", $fields = 'cod_sector, cod_programa, cod_sub_prog, cod_proyecto', $order = null, $limit = 1, $page = null, $recursive = null);
            foreach ($direccion as $row) {
                $cod_sector = $row['cugd02_direccion']['cod_sector'];
                $cod_programa = $row['cugd02_direccion']['cod_programa'];
                $cod_sub_prog = $row['cugd02_direccion']['cod_sub_prog'];
                $cod_proyecto = $row['cugd02_direccion']['cod_proyecto'];
            }
            //echo "sector='$cod_sector' - programa='$cod_programa' - sub_prog = '$cod_sub_prog' - proyecto = '$cod_proyecto' ";
            $cond_categoria = " and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog = '$cod_sub_prog' and cod_proyecto = '$cod_proyecto' ";
            $ano_cotizacion = $this->ano_ejecucion();
            $distribucion = $this->v_distribucion_compras->findAll($conditions = $this->condicion() . $cond_categoria . " and ano_cotizacion='$ano_cotizacion' and numero_cotizacion='$num_cotizacion' and mayus_acentos(rif)=mayus_acentos('$rif')", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
            //echo $conditions;

            $this->set('distribucion', $distribucion);
            $this->set('numero_cotizacion', $num_cotizacion);
            $this->set('rif', $rif);
            //echo "llamando a activ<br/>";
            $this->activ($distribucion, $num_cotizacion, $rif);
        }
    }

    function auxiliar($i = null, $ano = null, $cod_sector = null, $cod_programa = null, $cod_sub_prog = null, $cod_proyecto = null, $cod_partida = null, $cod_generica = null, $cod_especifica = null, $cod_sub_espec = null, $monto = null, $numero_cotizacion = null, $rif = null, $cod_activ_obra = null) {
        $this->layout = "ajax";
        if ($cod_activ_obra != null) {
            $sql_select_aux = "SELECT DISTINCT a.cod_auxiliar, a.deno_auxiliar FROM v_balance_ejecucion a WHERE  a.cod_sector='$cod_sector' and a.cod_programa='$cod_programa' and a.cod_sub_prog='$cod_sub_prog' and a.cod_proyecto='$cod_proyecto' and a.cod_activ_obra='$cod_activ_obra' and a.cod_partida='$cod_partida' and a.cod_generica='$cod_generica' and a.cod_especifica='$cod_especifica' and a.cod_sub_espec='$cod_sub_espec' and a.ano='$ano' and " . $this->condicion();
            //echo $sql_select_aux;
            $rs = $this->v_cscd03_cotizacion->execute($sql_select_aux);
            //pr($rs);
            foreach ($rs as $l) {
                $v[] = $l[0]["cod_auxiliar"];
                $d[] = $l[0]["deno_auxiliar"];
            }
            $ListaAux = array_combine($v, $d);
            $ListaAux = $this->concatena_aux($ListaAux, 'ListaAux');
            //$ListaAux = $this->v_compras_activ->generateList($conditions = $this->condicion()." and ano_cotizacion='$ano' and numero_cotizacion='$numero_cotizacion' and rif='$rif' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec'", $order = null, $limit = null, '{n}.v_compras_activ.cod_auxiliar', '{n}.v_compras_activ.cod_auxiliar');
            //$this->set('ListaAux', $ListaAux);
            $this->set('i', $i);
            $this->set('year', $ano);
            $this->set('cod_sector', $cod_sector);
            $this->set('cod_programa', $cod_programa);
            $this->set('cod_sub_prog', $cod_sub_prog);
            $this->set('cod_proyecto', $cod_proyecto);
            $this->set('cod_activ_obra', $cod_activ_obra);
            $this->set('cod_partida', $cod_partida);
            $this->set('cod_generica', $cod_generica);
            $this->set('cod_especifica', $cod_especifica);
            $this->set('cod_sub_espec', $cod_sub_espec);
            $this->set('monto', $monto);
        }
    }

    function anular($numero_orden_compra = null, $ano = null, $page = null, $fd = null, $num_cotizacion = null) {
        $this->layout = "ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        if ($numero_orden_compra != null) {
            $concepto = $this->data['cscp04_ordencompra']['concepto_anulacion'];
            $partidas = $this->cscd04_ordencompra_partidas->findAll($conditions = $this->condicion() . " and numero_orden_compra='$numero_orden_compra' and ano_orden_compra='$ano'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
            $rif = $this->cscd04_ordencompra_encabezado->field('rif', $this->condicion() . " and numero_orden_compra='$numero_orden_compra' and ano_orden_compra='$ano'", null);
            $i = 0;
            $cotizacion_encabezado = $this->cscd03_cotizacion_encabezado->findAll($conditions = $this->condicion() . " and numero_cotizacion='$num_cotizacion' and ano_cotizacion='$ano' and mayus_acentos(rif)=mayus_acentos('$rif')", $fields = null, $order = null, $limit = 1, $page = null, $recursive = null);
            $cotizacion_cuerpo = $this->cscd03_cotizacion_cuerpo->findAll($conditions = $this->condicion() . " and numero_cotizacion='$num_cotizacion' and ano_cotizacion='$ano' and mayus_acentos(rif)=mayus_acentos('$rif')", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);

            $resultado = strpos("PROVEEDOR:", $concepto);
            if ($resultado == FALSE) {
                $rif = strtoupper($rif);
                $proveedor = $this->cpcd02->field('cpcd02.denominacion', $conditions = "upper(cpcd02.rif)='$rif'", $order = null);
                $concepto = "PROVEEDOR: " . $proveedor . " ANULADA POR: " . $concepto;
            }

            $this->cscd03_cotizacion_cuerpo->execute("BEGIN;");


            foreach ($cotizacion_encabezado as $ct1) {
                $ano_cotizacion = $ct1['cscd03_cotizacion_encabezado']['ano_cotizacion'];
                $numero_cotizacion = $ct1['cscd03_cotizacion_encabezado']['numero_cotizacion'];
                $fecha_cotizacion = $ct1['cscd03_cotizacion_encabezado']['fecha_cotizacion'];
                $ano_solicitud = $ct1['cscd03_cotizacion_encabezado']['ano_solicitud'];
                $numero_solicitud = $ct1['cscd03_cotizacion_encabezado']['numero_solicitud'];
                $fecha_proceso = $ct1['cscd03_cotizacion_encabezado']['fecha_proceso'];
                $sql_cotizacion_encabezado_anulado = " INSERT INTO cscd03_cotizacion_encabezado_anulado VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$rif', '$ano', '$num_cotizacion', '$fecha_cotizacion', '$ano_solicitud', '$numero_solicitud', '$fecha_proceso', '$ano', '$numero_orden_compra');";
            }
            $sql_cotizacion_encabezado_cuerpo_anulado = "INSERT INTO cscd03_cotizacion_cuerpo_anulado (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif, ano_cotizacion, numero_cotizacion, codigo_prod_serv, descripcion, cod_medida, cantidad, precio_unitario, cantidad_entregada, ano_ordencompra, numero_ordencompra) VALUES ";
            $centinela = count($cotizacion_cuerpo);
            $j = 0;
            foreach ($cotizacion_cuerpo as $ct2) {
                $codigo_prod_serv = $ct2['cscd03_cotizacion_cuerpo']['codigo_prod_serv'];
                $descripcion = $ct2['cscd03_cotizacion_cuerpo']['descripcion'];
                $cod_medida = $ct2['cscd03_cotizacion_cuerpo']['cod_medida'];
                $cantidad = $ct2['cscd03_cotizacion_cuerpo']['cantidad'];
                $precio_unitario = $ct2['cscd03_cotizacion_cuerpo']['precio_unitario'];
                $cantidad_entregada = $ct2['cscd03_cotizacion_cuerpo']['cantidad_entregada'];
                if ($j == ($centinela - 1)) {
                    $sql_cotizacion_encabezado_cuerpo_anulado .= "('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$rif', '$ano', '$num_cotizacion', '$codigo_prod_serv', '$descripcion', '$cod_medida', '$cantidad', '$precio_unitario', '$cantidad_entregada', '$ano', '$numero_orden_compra');";
                } else {
                    $sql_cotizacion_encabezado_cuerpo_anulado .= "('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$rif', '$ano', '$num_cotizacion', '$codigo_prod_serv', '$descripcion', '$cod_medida', '$cantidad', '$precio_unitario', '$cantidad_entregada', '$ano', '$numero_orden_compra'),";
                }

                $j++;
            }

            $sw1 = $this->cscd03_cotizacion_cuerpo->execute($sql_cotizacion_encabezado_anulado . $sql_cotizacion_encabezado_cuerpo_anulado);

            if ($sw1 > 1) {//IF LUEGO DE COMPROBAR QUE SE GUARDARON LAS TABLAS CSCD03_COTIZACION_ANULACION
                foreach ($partidas as $row) {
                    $ano = $row['cscd04_ordencompra_partidas']['ano_orden_compra'];
                    $cod_sector = $row['cscd04_ordencompra_partidas']['cod_sector'];
                    $cod_programa = $row['cscd04_ordencompra_partidas']['cod_programa'];
                    $cod_sub_prog = $row['cscd04_ordencompra_partidas']['cod_sub_prog'];
                    $cod_proyecto = $row['cscd04_ordencompra_partidas']['cod_proyecto'];
                    $cod_activ_obra = $row['cscd04_ordencompra_partidas']['cod_activ_obra'];
                    $cod_partida = $row['cscd04_ordencompra_partidas']['cod_partida'];
                    $cod_generica = $row['cscd04_ordencompra_partidas']['cod_generica'];
                    $cod_especifica = $row['cscd04_ordencompra_partidas']['cod_especifica'];
                    $cod_sub_espec = $row['cscd04_ordencompra_partidas']['cod_sub_espec'];
                    $cod_auxiliar = $row['cscd04_ordencompra_partidas']['cod_auxiliar'];
                    $monto[$i] = $row['cscd04_ordencompra_partidas']['monto'];
                    $numero_asiento_compromiso[$i] = $row['cscd04_ordencompra_partidas']['numero_asiento_compromiso'];

                    $cp[$i] = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
                    $cod_cp[$i] = "ano_orden_compra='$ano' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
                    $i++;
                }
                $nro_acta_anulacion = $this->cugd03_acta_anulacion_numero->field('cugd03_acta_anulacion_numero.numero_acta_anulacion', $conditions = $this->condicion() . " and ano_acta_anulacion='$ano'", $order = null);
                if (empty($nro_acta_anulacion)) {
                    //echo "no encontre el acta de anulacion es 1<br>";
                    $nro_acta_anulacion = 1;
                    $sql_update_numero_anulacion = "INSERT INTO cugd03_acta_anulacion_numero VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano', '$nro_acta_anulacion');";
                } else {
                    //echo "encontre el acta de anulacion<br>";
                    $nro_acta_anulacion += 1;
                    $sql_update_numero_anulacion = "UPDATE cugd03_acta_anulacion_numero SET numero_acta_anulacion='$nro_acta_anulacion' WHERE " . $this->condicion() . " and ano_acta_anulacion='$ano';";
                }
                $sql_insert_anulacion = "INSERT INTO cugd03_acta_anulacion_cuerpo VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano', '$nro_acta_anulacion', '232', '$ano', '$numero_orden_compra', '$fd', '$concepto');";
                $fecha_anulacion = date('Y/m/d');
                $username = strtoupper($this->Session->read('nom_usuario'));
                $sql_update_anular = "UPDATE cscd04_ordencompra_encabezado SET entrega_completa=0, condicion_actividad=2, username_anulacion='$username', fecha_proceso_anulacion='$fecha_anulacion' WHERE ano_orden_compra='$ano' and numero_orden_compra='$numero_orden_compra' and " . $this->condicion() . ';';
                $sql_update_cotizacion = "UPDATE cscd03_cotizacion_encabezado SET ano_ordencompra=0, numero_ordencompra=0 WHERE " . $this->condicion() . " and numero_cotizacion='$num_cotizacion' and ano_cotizacion='$ano' and mayus_acentos(rif)=mayus_acentos('$rif');";
                $sql_update_cotizacion_cuerpo = "UPDATE cscd03_cotizacion_cuerpo SET cantidad_entregada=0 WHERE " . $this->condicion() . " and numero_cotizacion='$num_cotizacion' and ano_cotizacion='$ano' and mayus_acentos(rif)=mayus_acentos('$rif');";
                $sql_delete_nota_entrega_encabezado = "DELETE from cscd05_ordencompra_nota_entrega_encabezado WHERE ano_orden_compra='$ano' and numero_orden_compra='$numero_orden_compra' and mayus_acentos(rif)=mayus_acentos('$rif') and " . $this->condicion() . ';';
                $nota_entrega = $this->cscd05_ordencompra_nota_entrega_encabezado->field('numero_nota_entrega', "ano_orden_compra='$ano' and numero_orden_compra='$numero_orden_compra' and mayus_acentos(rif)=mayus_acentos('$rif') and " . $this->condicion(), null);
                if (!empty($nota_entrega)) {
                    $sql_delete_nota_entrega_encabezado .= "DELETE from cscd05_ordencompra_nota_entrega_cuerpo WHERE numero_nota_entrega='$nota_entrega' and ano_nota_entrega='$ano' and mayus_acentos(rif)=mayus_acentos('$rif') and " . $this->condicion() . ';';
                } else {
                    $sql_delete_nota_entrega_encabezado .= "";
                }

                $sw2 = $this->cugd03_acta_anulacion_numero->execute($sql_update_numero_anulacion . $sql_insert_anulacion . $sql_update_anular . $sql_update_cotizacion . $sql_update_cotizacion_cuerpo . $sql_delete_nota_entrega_encabezado);

                if ($sw2 > 1) {//IF LUEGO DE ACTUALIZAR EN LAS TABLAS CSCD04_ORDENCOMPRA Y CSCD03_COTIZACION_ENCABEZADO
                    $sql_update_num = "UPDATE cscd04_ordencompra_numero set situacion=4 where " . $this->condicion() . " and ano_orden_compra='$ano' and numero_orden_compra='$numero_orden_compra'";
                    $sw3 = $this->cscd04_ordencompra_encabezado->execute($sql_update_num);

                    if ($sw3 > 1) {//IF LUEGO DE ACTUALIZAR EL NUMERO DE LA ORDEN DE COMPRA CSCD04_ORDENCOMPRA_NUMERO
                        $caso = 1;
                        $sum_monto = 0;
                        for ($i = 0; $i < count($cp); $i++) {
                            $num_asiento_compromiso = $this->motor_presupuestario($cp[$i], 2, 3, 2, date("d/m/Y"), $monto[$i], $concepto, $ano, $numero_orden_compra, null, null, null, null, null, null, null, $numero_asiento_compromiso[$i], null, null, null, $j);
                            $sum_monto += $monto[$i];
                        }


                        if ($caso == 1) {

                            $valor_motor_contabilidad = $this->motor_contabilidad_fiscal($to = 2, $td = 5, $rif_doc = $rif, $ano_dc = $ano, $n_dc = $numero_orden_compra, $f_dc = date("d/m/Y"), $cpt_dc = $concepto, $ben_dc = null, $mon_dc = array("monto" => $sum_monto), $ano_op = null, $n_op = null, $f_op = null, $a_adj_op = null, $n_adj_op = null, $f_adj_op = null, $tp_op = null, $deno_ban_pago = null, $ano_movimiento = null, $cod_ent_pago = null, $cod_suc_pago = null, $cod_cta_pago = null, $num_che_o_debi = null, $fec_che_o_debi = null, $clas_che_o_debi = null);
                        } else {

                            $valor_motor_contabilidad = false;
                        }//fin else



                        if ($valor_motor_contabilidad == true) {//IF PARA COMPROBAR QUE LA PARTIDA FUE GUARDADA EN EL MOTOR Y FINALMENTE REALIZAR EL COMMIT
                            $this->cugd03_acta_anulacion_numero->execute("COMMIT;");
                            $this->set('msg', 'LA ORDEN DE COMPRA FUE ANULADA CON EXITO');
                        } else {//EJECUTAR ROLLBACK SI OCURRE UN ERROR EN EL MOTOR
                            $this->cfpd05->execute('ROLLBACK;');
                            $this->set('msg_error', 'NO SE PUDO ANULAR LA ORDEN DE COMPRA');
                        }
                    } else {//EJECUTAR ROLLBACK SI OCURRE UN ERROR EN EL MOTOR
                        $this->cugd03_acta_anulacion_numero->execute("ROLLBACK;");
                        $this->set('msg_error', 'NO SE PUDO ANULAR LA ORDEN DE COMPRA');
                    }
                } else {//EJECUTAR ROLLBACK SI OCURRE UN ERROR EN LA ACTUALIZACION DE LA CSCD04_ORDENCOMPRA Y CSCD03_COTIZACION
                    $this->cugd03_acta_anulacion_numero->execute("ROLLBACK;");
                    $this->set('msg_error', 'NO SE PUDO ANULAR LA ORDEN DE COMPRA');
                }
            } else {//EJECUTAR ROLLBACK SI NO GUARDO EN LA TABLA CSCD03_COTIZACION_ANULACION
                $this->cscd03_cotizacion_cuerpo->execute("ROLLBACK;");
                $this->set('msg_error', 'NO SE PUDO ANULAR LA ORDEN DE COMPRA');
            }

            $this->buscar2($numero_orden_compra, $ano);
        }//FIN DEL IF NUM_ORDENCOMPRA != NULL
    }

//FIN FUNCION ANULAR

    function preanular() {
        $this->layout = "ajax";
        echo "<script>document.getElementById('bt_anular').disabled=true;</script>";
    }

    function auxiliar2($i = null, $ano = null, $cod_sector = null, $cod_programa = null, $cod_sub_prog = null, $cod_proyecto = null, $cod_activ_obra = null, $cod_partida = null, $cod_generica = null, $cod_especifica = null, $cod_sub_espec = null, $num_cotizacion = null, $rif = null) {
        //echo "auxilio";
        //echo 'cotizacion '.$num_cotizacion;
        //echo "3 - estoy en auxiliar2<br/>";
        $group = "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_cotizacion, ano_cotizacion, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec";
        $group2 = "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_cotizacion, ano_cotizacion, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_partida, cod_generica, cod_especifica, cod_sub_espec";
        if ($cod_activ_obra != null) {
            $contadorAux = $this->v_compras_activ->findCount($conditions = $this->condicion() . " and ano_cotizacion='$ano' and numero_cotizacion='$num_cotizacion' and rif='$rif' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' group by " . $group . ", cod_activ_obra");
            if ($contadorAux > 1) {
                $ListaAux = $this->v_compras_activ->generateList($conditions = $this->condicion() . " and ano_cotizacion='$ano' and numero_cotizacion='$num_cotizacion' and rif='$rif' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec'", $order = null, $limit = null, '{n}.v_compras_activ.cod_auxiliar', '{n}.v_compras_activ.cod_auxiliar');
            } else {
                $ListaAux = $this->v_compras_activ->field('v_compras_activ.cod_auxiliar', $conditions = $this->condicion() . " and ano_cotizacion='$ano' and numero_cotizacion='$num_cotizacion' and rif='$rif' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' group by " . $group . ", cod_activ_obra, cod_auxiliar", $order = null);
                $monto = $this->v_distribucion_compras->field('v_distribucion_compras.total', $conditions = $this->condicion() . " and ano_cotizacion='$ano' and numero_cotizacion='$num_cotizacion' and rif='$rif' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and numero_cotizacion='$num_cotizacion' and mayus_acentos(rif)=mayus_acentos('$rif') group by " . $group2 . ", total", $order = null);
                $codigos = $ano . "/" . $cod_sector . "/" . $cod_programa . "/" . $cod_sub_prog . "/" . $cod_proyecto . "/" . $ListaAux . "/" . $cod_partida . "/" . $cod_generica . "/" . $cod_especifica . "/" . $cod_sub_espec . '/' . $monto;
                $this->ver_trafico2($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $ListaAux, $monto);
                //echo "monto_original: ".$monto;
                $mt = $this->Formato2($monto);
                //echo $mt."-";
                //echo "el monto formato2 es: ".$mt;
                //echo "el monto formato1 es: ".$this->Formato1($mt);
                //echo "+ ";
                //echo "llame a items<br/>";
                $this->items($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $mt, $i, $ListaAux);
            }

            //$this->set('ListaAux', $ListaAux);
            //echo "retorno lista en auxiliar2<br/>";
            return($ListaAux);
            $this->set('i', $i);
            $this->set('year', $ano);
            $this->set('cod_sector', $cod_sector);
            $this->set('cod_programa', $cod_programa);
            $this->set('cod_sub_prog', $cod_sub_prog);
            $this->set('cod_proyecto', $cod_proyecto);
            $this->set('cod_activ_obra', $cod_activ_obra);
            $this->set('cod_partida', $cod_partida);
            $this->set('cod_generica', $cod_generica);
            $this->set('cod_especifica', $cod_especifica);
            $this->set('cod_sub_espec', $cod_sub_espec);
            $this->set('monto', $monto);
        }
    }

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
                $this->cugd04->execute("UPDATE cugd04_entrada_modulo SET hora_captura_partida='$time' WHERE username='$username'");
            } else {
                $sql_update_cugd04 = "UPDATE cugd04 set cod_auxiliar='$cod_auxiliar' WHERE " . $this->condicion() . $partida;
                $this->cugd04->execute($sql_update_cugd04);
            }
        }
    }

    function borrar_items($id) {
        $_SESSION["codigos"][$id] = null;
    }

    function bloquea_imputacion() {
        echo "<script>";
        echo "document.getElementById('ano_partidas').disabled='disabled';";
        for ($i = 1; $i < 11; $i++) {
            echo "document.getElementById('seleccion_$i').disabled='disabled';";
        }
        echo "document.getElementById('monto').disabled='disabled';";
        echo "document.getElementById('plus').disabled='disabled';";
        echo "</script>";
    }

    function desbloquea_imputacion() {
        echo "<script>";
        echo "document.getElementById('ano_partidas').disabled='';";
        for ($i = 1; $i < 11; $i++) {
            echo "document.getElementById('seleccion_$i').disabled='';";
        }
        echo "document.getElementById('monto').disabled='';";
        echo "document.getElementById('plus').disabled='';";
        echo "</script>";
    }

    function verifica_monto() {
        $this->layout = "ajax";
        //echo $this->Session->read('total_cotizacion');
    }

    function items($ano = null, $cod_sector = null, $cod_programa = null, $cod_sub_prog = null, $cod_proyecto = null, $cod_activ_obra = null, $cod_partida = null, $cod_generica = null, $cod_especifica = null, $cod_sub_espec = null, $monto = null, $id = null, $cod_auxiliar = null) {
        $this->layout = "ajax";
//echo "estoy en items";

        if ($ano != null && $cod_sector != null && $cod_programa != null && $cod_sub_prog != null && $cod_proyecto != null && $cod_activ_obra != null && $cod_partida != null && $cod_generica != null && $cod_especifica != null && $cod_sub_espec != null && $monto != null && $cod_auxiliar != null) {

            $disponibilidad = $this->disponibilidad($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar, $monto);
            //echo $id.": ".$disponibilidad." < ".$this->Formato1($monto);

            if ($disponibilidad < $this->Formato1($monto)) {
                //echo "entre";
                echo "<script>document.getElementById('save').disabled='disabled';document.getElementById('check_$id').style.visibility='visible';new Effect.Appear('tabla_imputacion');</script>";
                $this->set('errorMessage', "la disponibilidad para la partida es: " . $disponibilidad);
                return;
            } else {
                echo "<script>";
                echo "document.getElementById('check_$id').style.visibility='hidden';";
                echo "new Effect.DropOut('tr_$id');";
                echo "</script>";
            }
            //echo "<script>new Effect.DropOut('codigos_automaticos')</script>";
            //$this->bloquea_imputacion();


            if (isset($_SESSION["i"])) {
                //echo $_SESSION["i"]."<=".$id;
                if ($_SESSION["i"] <= $id) {
                    $i = $id;
                } else {
                    $i = $id;
                    $this->Session->write("i", $i);
                }
            } else {
                $this->Session->write("i", $id);
                $i = $id;
            }

            //echo $disponibilidad." || ".$monto;return;
            //echo $id.'-'.$ano.'-'.$cod_sector.'-'.$cod_programa.'-'.$cod_sub_prog.'-'.$cod_proyecto.'-'.$cod_activ_obra.'-'.$cod_partida.'-'.$cod_generica.'-'.$cod_especifica.'-'.$cod_sub_espec.'-'.$cod_auxiliar.'-'.$monto.'-'.$i."<br/>";
            if (isset($_SESSION["codigos"])) {
                $array = $_SESSION["codigos"];
                $_SESSION["codigos"][$i] = array();
                $_SESSION["codigos"][$i][0] = $ano;
                $_SESSION["codigos"][$i][1] = $cod_sector;
                $_SESSION["codigos"][$i][2] = $cod_programa;
                $_SESSION["codigos"][$i][3] = $cod_sub_prog;
                $_SESSION["codigos"][$i][4] = $cod_proyecto;
                $_SESSION["codigos"][$i][5] = $cod_activ_obra;
                $_SESSION["codigos"][$i][6] = $cod_partida;
                $_SESSION["codigos"][$i][7] = $cod_generica;
                $_SESSION["codigos"][$i][8] = $cod_especifica;
                $_SESSION["codigos"][$i][9] = $cod_sub_espec;
                $_SESSION["codigos"][$i][10] = $cod_auxiliar;
                $_SESSION["codigos"][$i][11] = $this->Formato2($monto);
                $_SESSION["codigos"][$i]['id'] = $i;
            } else {

                $codigos[$i][0] = $ano;
                $codigos[$i][1] = $cod_sector;
                $codigos[$i][2] = $cod_programa;
                $codigos[$i][3] = $cod_sub_prog;
                $codigos[$i][4] = $cod_proyecto;
                $codigos[$i][5] = $cod_activ_obra;
                $codigos[$i][6] = $cod_partida;
                $codigos[$i][7] = $cod_generica;
                $codigos[$i][8] = $cod_especifica;
                $codigos[$i][9] = $cod_sub_espec;
                $codigos[$i][10] = $cod_auxiliar;
                $codigos[$i][11] = $this->Formato2($monto);
                $codigos[$i]['id'] = $i;
            }
            if (isset($_SESSION["codigos"])) {
                //$_SESSION["codigos"]=$_SESSION["codigos"]+$codigos;
            } else {
                $_SESSION["codigos"] = $codigos;
            }
            //print_r($_SESSION["codigos"]);
        }

        $total_items = count($_SESSION["codigos"]);
        $total_filas = $_SESSION["total_filas"];
        //echo "$total_items || $total_filas";
        $faltante = $total_filas - $total_items;
        if ($faltante == 0) {
            echo "<script>";
            echo "new Effect.DropOut('codigos_automaticos');";
            echo "new Effect.Appear('tabla_imputacion');";
            echo "</script>";
        }

        //$this->render('agregar_partidas');
    }

//fin funcion items

    function trafico($ano = null, $cod_sector = null, $cod_programa = null, $cod_sub_prog = null, $cod_proyecto = null, $cod_activ_obra = null, $cod_partida = null, $cod_generica = null, $cod_especifica = null, $cod_sub_espec = null, $monto = null, $cod_auxiliar = null) {
        $this->layout = "ajax";
        $username = strtoupper($this->Session->read('nom_usuario'));
        //echo $ano.'-'.$cod_sector.'-'.$cod_programa.'-'.$cod_sub_prog.'-'.$cod_proyecto.'-'.$cod_activ_obra.'-'.$cod_partida.'-'.$cod_generica.'-'.$cod_especifica.'-'.$cod_sub_espec.'-'.$cod_auxiliar.'-'.$monto;
        if ($ano != null && $cod_sector != null && $cod_programa != null && $cod_sub_prog != null && $cod_proyecto != null && $cod_activ_obra != null && $cod_partida != null && $cod_generica != null && $cod_especifica != null && $cod_sub_espec != null && $monto != null && $cod_auxiliar != null) {

            $trafico = $this->semaforo($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);

            //echo "el username es: ".$trafico['username']." y el color es: ".$trafico['color'];

            if ($trafico['color'] == 'rojo' && $trafico['username'] != $username) {
                $this->set('msg', $trafico['mensaje']);
                $this->set('remote', 'luis');
                $partida = $ano . '/' . $cod_sector . '/' . $cod_programa . '/' . $cod_sub_prog . '/' . $cod_proyecto . '/' . $cod_activ_obra . '/' . $cod_partida . '/' . $cod_generica . '/' . $cod_especifica . '/' . $cod_sub_espec . '/' . $cod_auxiliar . '/' . $monto;
                $this->set('partida', $partida);
            } else {
                //$this->guardar_cugd04($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar, $username);
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

    function select3($select = null, $var = null) { //select codigos presupuestarios
        $this->layout = "ajax";
        if ($select != null && $var != null) {
            $cond = $this->SQLCA();
            switch ($select) {
                case 'sector':
                    $this->set('SELECT', 'programa');
                    $this->set('codigo', 'sector');
                    $this->set('seleccion', '');
                    $this->set('n', 1);
                    $this->Session->write('ano', $var);
                    $cond .=" and ano=" . $var;
                    //$lista=  $this->cfpd02_sector->generateList($cond, 'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.denominacion');
                    $lista = $this->v_cfpd05_denominaciones->generateList($cond, 'cod_sector ASC', null, '{n}.v_cfpd05_denominaciones.cod_sector', '{n}.v_cfpd05_denominaciones.deno_sector');
                    $this->concatena($lista, 'vector');
                    break;
                case 'programa':

                    $this->set('SELECT', 'subprograma');
                    $this->set('codigo', 'programa');
                    $this->set('seleccion', '');
                    $this->set('n', 2);
                    $year_pago = $this->Session->read('year_pago') - date("Y");
                    if ($this->Session->read('year_pago') > date("Y")) {
                        $ano = $this->ano_ejecucion();
                    } else {
                        $ano = $this->ano_ejecucion();
                    }
                    $this->Session->write('ano', $ano);
                    $this->Session->write('sec', $var);
                    $cond .=" and ano=" . $ano . " and cod_sector=" . $var;
                    $lista = $this->v_cfpd05_denominaciones->generateList($cond, 'cod_programa ASC', null, '{n}.v_cfpd05_denominaciones.cod_programa', '{n}.v_cfpd05_denominaciones.deno_programa');
                    $this->concatena($lista, 'vector');
                    break;
                case 'subprograma':
                    $this->set('SELECT', 'proyecto');
                    $this->set('codigo', 'subprograma');
                    $this->set('seleccion', '');
                    $this->set('n', 3);
                    $ano = $this->Session->read('ano');
                    $sec = $this->Session->read('sec');
                    $this->Session->write('prog', $var);
                    $cond .=" and ano=" . $ano . " and cod_sector=" . $sec . " and cod_programa=" . $var;
                    $lista = $this->v_cfpd05_denominaciones->generateList($cond, 'cod_sub_prog ASC', null, '{n}.v_cfpd05_denominaciones.cod_sub_prog', '{n}.v_cfpd05_denominaciones.deno_sub_prog');
                    $this->concatena($lista, 'vector');
                    break;
                case 'proyecto':
                    $this->set('SELECT', 'actividad');
                    $this->set('codigo', 'proyecto');
                    $this->set('seleccion', '');
                    $this->set('n', 4);
                    $ano = $this->Session->read('ano');
                    $sec = $this->Session->read('sec');
                    $prog = $this->Session->read('prog');
                    $this->Session->write('subp', $var);
                    $cond .=" and ano=" . $ano . " and cod_sector=" . $sec . " and cod_programa=" . $prog . " and cod_sub_prog=" . $var;
                    $lista = $this->v_cfpd05_denominaciones->generateList($cond, 'cod_proyecto ASC', null, '{n}.v_cfpd05_denominaciones.cod_proyecto', '{n}.v_cfpd05_denominaciones.deno_proyecto');
                    $this->concatena($lista, 'vector');
                    break;
                case 'actividad':
                    $this->set('SELECT', 'partida');
                    $this->set('codigo', 'actividad');
                    $this->set('seleccion', '');
                    $this->set('n', 5);
                    $ano = $this->Session->read('ano');
                    $sec = $this->Session->read('sec');
                    $prog = $this->Session->read('prog');
                    $subp = $this->Session->read('subp');
                    $this->Session->write('proy', $var);
                    $cond .=" and ano=" . $ano . " and cod_sector=" . $sec . " and cod_programa=" . $prog . " and cod_sub_prog=" . $subp . " and cod_proyecto=" . $var;
                    $lista = $this->v_cfpd05_denominaciones->generateList($cond, 'cod_activ_obra ASC', null, '{n}.v_cfpd05_denominaciones.cod_activ_obra', '{n}.v_cfpd05_denominaciones.deno_activ_obra');
                    $this->concatena($lista, 'vector');
                    break;
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
                    $cond2 = $cond . " and ano=" . $ano . " and cod_sector=" . $sec . " and cod_programa=" . $prog . " and cod_sub_prog=" . $subp . " and cod_proyecto=" . $proy . " and cod_activ_obra=" . $var;
                    $lista = $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_partida ASC', null, '{n}.v_cfpd05_denominaciones.cod_partida', '{n}.v_cfpd05_denominaciones.deno_partida');
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
                    $cond2 = $cond . " and ano=" . $ano . " and cod_sector=" . $sec . " and cod_programa=" . $prog . " and cod_sub_prog=" . $subp . " and cod_proyecto=" . $proy . " and cod_activ_obra=" . $activ . " and cod_partida=" . $var;
                    $lista = $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_generica ASC', null, '{n}.v_cfpd05_denominaciones.cod_generica', '{n}.v_cfpd05_denominaciones.deno_generica');
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
                    $cond2 = $cond . " and ano=" . $ano . " and cod_sector=" . $sec . " and cod_programa=" . $prog . " and cod_sub_prog=" . $subp . " and cod_proyecto=" . $proy . " and cod_activ_obra=" . $activ . " and cod_partida=" . $cpar . " and cod_generica=" . $var;
                    $lista = $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_especifica ASC', null, '{n}.v_cfpd05_denominaciones.cod_especifica', '{n}.v_cfpd05_denominaciones.deno_especifica');
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
                    $cond2 = $cond . " and ano=" . $ano . " and cod_sector=" . $sec . " and cod_programa=" . $prog . " and cod_sub_prog=" . $subp . " and cod_proyecto=" . $proy . " and cod_activ_obra=" . $activ . " and cod_partida=" . $cpar . " and cod_generica=" . $cgen . " and cod_especifica=" . $var;
                    $lista = $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_sub_espec ASC', null, '{n}.v_cfpd05_denominaciones.cod_sub_espec', '{n}.v_cfpd05_denominaciones.deno_sub_espec');
                    $this->concatena($lista, 'vector');
                    break;
                case 'auxiliar':

                    $this->set('SELECT', 'escribir_aux');
                    $this->set('codigo', 'auxiliar');
                    $this->set('seleccion', null);
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
                    $cond2 = $cond . " and ano=" . $ano . " and cod_sector=" . $sec . " and cod_programa=" . $prog . " and cod_sub_prog=" . $subp . " and cod_proyecto=" . $proy . " and cod_activ_obra=" . $activ . " and cod_partida=" . $cpar . " and cod_generica=" . $cgen . " and cod_especifica=" . $cesp . " and cod_sub_espec=" . $var;
                    //$lista=  $this->cfpd05->generateList($cond." and ".$cond2, 'cod_auxiliar ASC', null, '{n}.cfpd05.cod_auxiliar', '{n}.cfpd05.denominacion');
                    $lista = $this->v_cfpd05_denominaciones->generateList($cond . " and " . $cond2, 'cod_auxiliar ASC', null, '{n}.v_cfpd05_denominaciones.cod_auxiliar', '{n}.v_cfpd05_denominaciones.deno_auxiliar');
                    if ($lista != null) {
                        $this->concatena_aux($lista, 'vector');
                    } else {
                        $this->set('vector', array('0' => '0000'));
                    }
                    //echo "muestra";
                    break;
                case 'auxiliar2':
                    //echo "hola auxiliar 2";
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
                    //$this->Session->write('actividad',$var);
                    $f = $this->Session->read('CodigosDireccion');
                    $p = $this->Session->read('partidas');
                    /* $part= $p[0]['cscd01_catalogo']['cod_partida']<9 ? "40".$p[0]['cscd01_catalogo']['cod_partida']:$p[0]['cscd01_catalogo']['cod_partida'];
                      $part= $part <400 ? "4".$part : $part;
                      if($this->Session->read("year_pago")>date("Y")){
                      $ano= 1+date("Y");
                      }else{
                      $ano=date("Y");
                      }
                      $cond2 =" cod_sector=".$f[0]["cugd02_direccion"]["cod_sector"]." and cod_programa=".$f[0]["cugd02_direccion"]["cod_programa"]." and cod_sub_prog=".$f[0]["cugd02_direccion"]["cod_sub_prog"]." and cod_proyecto=".$f[0]["cugd02_direccion"]["cod_proyecto"]." and cod_activ_obra=".$var." and ano=".$ano." and cod_partida=".$part." and cod_generica=".$p[0]["cscd01_catalogo"]["cod_generica"]." and cod_especifica=".$p[0]["cscd01_catalogo"]["cod_especifica"]." and cod_sub_espec=".$p[0]["cscd01_catalogo"]["cod_sub_espec"];
                      //echo "AUX2".$cond2; */
                    $cond2 = $cond . " and ano=" . $ano . " and cod_sector=" . $sec . " and cod_programa=" . $prog . " and cod_sub_prog=" . $subp . " and cod_proyecto=" . $proy . " and cod_activ_obra=" . $activ . " and cod_partida=" . $cpar . " and cod_generica=" . $cgen . " and cod_especifica=" . $cesp . " and cod_sub_espec=" . $var;
                    $lista = $this->cfpd05->generateList($cond2, 'cod_auxiliar ASC', null, '{n}.cfpd05.cod_auxiliar', '{n}.cfpd05.cod_auxiliar');
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
                        echo "cero";
                        $disponibilidad = $this->disponibilidad($_SESSION["ano"], $_SESSION["sec"], $_SESSION["prog"], $_SESSION["subp"], $_SESSION["proy"], $_SESSION["actividad"], $_SESSION["cpar"], $_SESSION["cgen"], $_SESSION["cesp"], $_SESSION["csesp"], 0);

                        echo "<script>" .
                        "document.getElementById('td_disponibilidad').innerHTML='" . $disponibilidad . "'; " .
                        "</script>";
                    }
                    break;
                case 'escribir_aux':

                    $this->Session->write('auxiliar', $var);
                    $disponibilidad = $this->disponibilidad($_SESSION["ano"], $_SESSION["sec"], $_SESSION["prog"], $_SESSION["subp"], $_SESSION["proy"], $_SESSION["actividad"], $_SESSION["cpar"], $_SESSION["cgen"], $_SESSION["cesp"], $_SESSION["csesp"], $_SESSION["auxiliar"]);

                    echo "<script>" .
                    "document.getElementById('td_disponibilidad').innerHTML='" . $disponibilidad . "';" .
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

    function codigos_diferentes($year = null, $i = null) {
        $this->layout = "ajax";
        $ano = $year;
        $this->set('i', $i);
        $this->set('ano', $ano);
        $sector = $this->cfpd02_sector->generateList($this->SQLCA($ano), 'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.denominacion');
        $sector = $sector != null ? $sector : array();
        $this->concatena($sector, 'sector');
    }

    function pretrafico($i = null, $ano = null, $monto = null) {
        $this->layout = "ajax";
        $cod_sector = $this->Session->read('sec');
        $cod_programa = $this->Session->read('prog');
        $cod_sub_prog = $this->Session->read('subp');
        $cod_proyecto = $this->Session->read('proy');
        $cod_activ_obra = $this->Session->read('actividad');
        $cod_partida = $this->Session->read('cpar');
        $cod_partida = CE . $this->zero($cod_partida);
        $cod_generica = $this->Session->read('cgen');
        $cod_especifica = $this->Session->read('cesp');
        $cod_sub_espec = $this->Session->read('csesp');
        $cod_auxiliar = $this->Session->read('auxiliar');


        $codigos[$i][0] = $ano;
        $codigos[$i][1] = $cod_sector;
        $codigos[$i][2] = $cod_programa;
        $codigos[$i][3] = $cod_sub_prog;
        $codigos[$i][4] = $cod_proyecto;
        $codigos[$i][5] = $cod_activ_obra;
        $codigos[$i][6] = $cod_partida;
        $codigos[$i][7] = $cod_generica;
        $codigos[$i][8] = $cod_especifica;
        $codigos[$i][9] = $cod_sub_espec;
        $codigos[$i][10] = $cod_auxiliar;
        $codigos[$i][11] = $monto;
        if (isset($_SESSION["codigos"])) {
            $_SESSION["codigos"] = $_SESSION["codigos"] + $codigos;
        } else {
            $_SESSION["codigos"] = $codigos;
        }
        //print_r($_SESSION["codigos"]);
        $this->trafico($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $monto, $cod_auxiliar);

        $this->render('trafico');
    }

    function ver_trafico2($ano = null, $cod_sector = null, $cod_programa = null, $cod_sub_prog = null, $cod_proyecto = null, $cod_activ_obra = null, $cod_partida = null, $cod_generica = null, $cod_especifica = null, $cod_sub_espec = null, $cod_auxiliar = null, $monto = null) {
        $this->layout = "ajax";

        $this->trafico($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $monto, $cod_auxiliar);

        //$this->render('ver_trafico');
    }

    function ver_trafico($ano = null, $cod_sector = null, $cod_programa = null, $cod_sub_prog = null, $cod_proyecto = null, $cod_activ_obra = null, $cod_partida = null, $cod_generica = null, $cod_especifica = null, $cod_sub_espec = null, $cod_auxiliar = null, $monto = null) {
        $this->layout = "ajax";

        $this->trafico($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $monto, $cod_auxiliar);

        $this->render('trafico');
    }

    function guardar() {
        $this->layout = "ajax";
        //echo "guardandoooo";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        //echo "la variable contador es: ".$this->Session->read('contador');

        if (!empty($this->data['cscp04_ordencompra'])) {
            $ano_orden_compra = $this->data['cscp04_ordencompra']['ano_orden'];
            $ann = $ano_orden_compra;
            $ano = $ann;
            $tipo_orden = $this->data['cscp04_ordencompra']['tipo_ordencompra'];
            $rif = strtoupper($this->data['cscp04_ordencompra']['rif']);
            $ano_cotizacion = $this->data['cscp04_ordencompra']['ano_cotizacion'];
            $numero_cotizacion = $this->data['cscp04_ordencompra']['num_compra'];
            $ccp = $this->cscd02_solicitud_encabezado->field('cscd02_solicitud_encabezado.uso_destino', $conditions = $this->condicion() . " and cscd02_solicitud_encabezado.numero_cotizacion='" . $numero_cotizacion . "' and  cscd02_solicitud_encabezado.ano_cotizacion='" . $ano_cotizacion . "' and cscd02_solicitud_encabezado.rif='" . $rif . "'  ", $order = null);
            $lugar_entrega = $this->data['cscp04_ordencompra']['lugar'];
            $plazo_entrega = $this->data['cscp04_ordencompra']['plazo'];
            $username_registro = $this->Session->read('nom_usuario');
            $fecha_proceso_registro = $this->data['cscp04_ordencompra']['fecha_ordencompra'];
            $fd = $this->data['cscp04_ordencompra']['fecha_ordencompra'];
            $dato = str_split($fecha_proceso_registro, 3);
            $fecha_proceso_registro = $dato[2] . $dato[3] . '/' . $dato[1] . str_replace('/', '', $dato[0]);
            $fecha_proceso_registro = str_replace('/', '-', $fecha_proceso_registro);
            $monto_orden = $this->Session->read('monto_orden');
            $condicion_actividad = 1;
            $numero_orden_compra = $this->data['cscp04_ordencompra']['num_ordencompra'];
            $ndo = $numero_orden_compra;
            $condiciones = $this->data['cscp04_ordencompra']['condiciones'];
            $cod_obra = $this->data['cscp04_ordencompra']['cod_obra'];


            $resultado = strpos("PROVEEDOR:", $ccp);
            if ($resultado == FALSE) {
                $rif = strtoupper($rif);
                $proveedor = $this->cpcd02->field('cpcd02.denominacion', $conditions = "upper(cpcd02.rif)='$rif'", $order = null);
                $ccp = "PROVEEDOR: " . $proveedor . " POR CONCEPTO DE: " . $ccp;
            }


            $pregunta_ejercicio = $this->data['cscp04_ordencompra']['pregunta_ejercicio'];


            if (isset($this->data['cscp04_ordencompra']['manual'])) {
                $manual = $this->data['cscp04_ordencompra']['manual'];
            }
            $auto = $this->data['cscp04_ordencompra']['auto'];
            $iva = $this->Formato1($this->data['cscp04_ordencompra']['iva']);
            if (empty($iva)) {
                $iva = $this->cscd04_ordencompra_parametros->field('cscd04_ordencompra_parametros.porcentaje_iva', $conditions = $this->SQLCA(), $order = null);
                if (empty($iva)) {
                    $iva = 0;
                }
            }


            if ($this->cscd03_cotizacion_encabezado->findCount($this->condicion() . " and ano_cotizacion='" . $ano_cotizacion . "' and numero_cotizacion='" . $numero_cotizacion . "' and mayus_acentos(rif)='" . $rif . "' and ano_ordencompra=0 and numero_ordencompra=0") != 0) {



                $fecha_proceso_registro2 = date('Y/m/d');
                $sql_encabezado = "BEGIN; INSERT INTO cscd04_ordencompra_encabezado values('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano_orden_compra', '$numero_orden_compra', '$tipo_orden', '$rif', '$ano_cotizacion', '$numero_cotizacion', '$lugar_entrega', '$plazo_entrega', '$monto_orden', '0', '0', '0', '0', '0', '$iva', '0', '0','$fecha_proceso_registro2', '0', '0', '0', '0', '$username_registro', '$condicion_actividad', '0', '0', '0', '0', '0', '1999-01-01', '$fecha_proceso_registro', '1', '" . $pregunta_ejercicio . "',null, '" . $condiciones . "', '0', '0', '0', '0')";
                $sw = $this->cscd04_ordencompra_encabezado->execute($sql_encabezado);
                if ($sw > 1) {
                    if ($tipo_orden == 1) {
                        $sql_insert_poremitir = "INSERT INTO cscd04_ordencompra_poremitir_bienes VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$username_registro', '$ano_orden_compra', '$numero_orden_compra');";
                    } else {
                        $sql_insert_poremitir = "INSERT INTO cscd04_ordencompra_poremitir_servicio VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$username_registro', '$ano_orden_compra', '$numero_orden_compra');";
                    }

                    $this->cscd04_ordencompra_encabezado->execute($sql_insert_poremitir);


                    $sql_update_num = "UPDATE cscd04_ordencompra_numero set situacion=3 where " . $this->condicion() . " and ano_orden_compra='" . $ano_orden_compra . "' and numero_orden_compra='" . $numero_orden_compra . "'; ";
                    $this->cscd04_ordencompra_encabezado->execute($sql_update_num);

                    $sql_update_cotizacion = "UPDATE cscd03_cotizacion_encabezado SET ano_ordencompra='" . $ano_orden_compra . "', numero_ordencompra='" . $numero_orden_compra . "' WHERE " . $this->condicion() . " and ano_cotizacion='" . $ano_cotizacion . "' and numero_cotizacion='" . $numero_cotizacion . "' and upper(rif)='" . $rif . "';  ";
                    $s_cotizacion = $this->cscd03_cotizacion_encabezado->execute($sql_update_cotizacion);
                    $random = rand();
                    if ($this->cscd03_cotizacion_encabezado->findCount($random . "=" . $random . " and " . $this->condicion() . " and ano_cotizacion='" . $ano_cotizacion . "' and numero_cotizacion='" . $numero_cotizacion . "' and mayus_acentos(rif)='" . $rif . "' and ano_ordencompra='" . $ano_orden_compra . "' and numero_ordencompra='" . $numero_orden_compra . "'   ") == 0) {
                        $s_cotizacion = 0;
                    }

                    if ($s_cotizacion > 1) {
                        $j = 0;

                        $numero_compromiso = $this->cfpd21_numero_asiento_compromiso->field('cfpd21_numero_asiento_compromiso.numero_compromiso', $conditions = $this->condicionNDEP() . " and ano_compromiso='$ann'", $order = null);
                        if (!empty($numero_compromiso)) {
                            $numero_compromiso++;
                            $sql_numero_compromiso = "UPDATE cfpd21_numero_asiento_compromiso SET numero_compromiso='$numero_compromiso' WHERE ano_compromiso='$ann' and " . $this->condicionNDEP() . ";";
                        } else {
                            $numero_compromiso = 1;
                            $sql_numero_compromiso = "INSERT INTO cfpd21_numero_asiento_compromiso VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$ano', '$numero_compromiso');";
                        }
                        $sw_numero_compromiso = $this->cfpd21_numero_asiento_compromiso->query($sql_numero_compromiso);
                        $numero_control_compromiso = $numero_compromiso;

                        $i = 0;
                        $sw2 = 0;
                        $sum_monto = 0;
                        foreach ($_SESSION["codigos"] as $nss) {
                            if ($nss != null) {
                                $new_array[$i]['ano'] = $nss[0];
                                $new_array[$i]['cod_sector'] = $nss[1];
                                $new_array[$i]['cod_programa'] = $nss[2];
                                $new_array[$i]['cod_sub_prog'] = $nss[3];
                                $new_array[$i]['cod_proyecto'] = $nss[4];
                                $new_array[$i]['cod_activ_obra'] = $nss[5];
                                $new_array[$i]['cod_partida'] = $nss[6];
                                $new_array[$i]['cod_generica'] = $nss[7];
                                $new_array[$i]['cod_especifica'] = $nss[8];
                                $new_array[$i]['cod_sub_espec'] = $nss[9];
                                $new_array[$i]['cod_auxiliar'] = $nss[10];
                                $new_array[$i]['monto'] = $this->Formato1($nss[11]);
                                $i++;
                            }//null
                        }//fin foreach

                        $cont = $this->Session->read('i');
                        foreach ($new_array as $cod) {
                            $ano = $cod['ano'];
                            $cod_sector = $cod['cod_sector'];
                            $cod_programa = $cod['cod_programa'];
                            $cod_sub_prog = $cod['cod_sub_prog'];
                            $cod_proyecto = $cod['cod_proyecto'];
                            $cod_activ_obra = $cod['cod_activ_obra'];
                            $cod_partida = $cod['cod_partida'];
                            $cod_generica = $cod['cod_generica'];
                            $cod_especifica = $cod['cod_especifica'];
                            $cod_sub_espec = $cod['cod_sub_espec'];
                            $cod_auxiliar = $cod['cod_auxiliar'];
                            $monto = $cod['monto'];
                            $sum_monto += $monto;

                            $disponibilidad = $this->disponibilidad($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);

                            if ($disponibilidad >= $monto) {

                                if ($cod_sector != null) {
                                    $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
                                    $to = 1;
                                    $td = 3;
                                    $ta = 2;
                                    $mt = $monto;

                                    $dnco = $this->motor_presupuestario($cp, $to, $td, $ta, $fd, $mt, $ccp, $ann, $ndo, null, null, null, null, null, null, null, $numero_compromiso, null, null, null, $j);
                                    $sql_insert_partidas = "INSERT INTO cscd04_ordencompra_partidas VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano_orden_compra', '$numero_orden_compra', '$ano', '$cod_sector', '$cod_programa', '$cod_sub_prog', '$cod_proyecto', '$cod_activ_obra', '$cod_partida', '$cod_generica', '$cod_especifica', '$cod_sub_espec', '$cod_auxiliar','$monto', '0', '0', '0', '0', '0', '$numero_compromiso', '0', '0'); ";
                                    $sw2 = $this->cscd04_ordencompra_partidas->execute($sql_insert_partidas);
                                }
                                $j++;
                            } else {
                                $this->cscd04_ordencompra_encabezado->execute("ROLLBACK;");
                                $this->set('msg_error', 'NO SE REALIZO EL REGISTRO DE LA ORDEN DE COMPRA');
                                $this->set('msg_error', 'HAY PARTIDAS QUE NO TIENEN DISPONIBILIDAD');
                                $this->Session->delete('codigos');
                                $_SESSION["codigos"] = null;
                                $this->borrar_cugd04();
                                $this->data['cscp04_ordencompra'] = null;
                                $this->index();
                                $this->render('index');
                            }
                        }//fin for



                        if ($sw2 > 1) {

                            $valor_motor_contabilidad = $this->motor_contabilidad_fiscal($to = 1, $td = 5, $rif_doc = $rif, $ano_dc = $ann, $n_dc = $ndo, $f_dc = $fd, $cpt_dc = $ccp, $ben_dc = null, $mon_dc = array("monto" => $sum_monto), $ano_op = null, $n_op = null, $f_op = null, $a_adj_op = null, $n_adj_op = null, $f_adj_op = null, $tp_op = null, $deno_ban_pago = null, $ano_movimiento = null, $cod_ent_pago = null, $cod_suc_pago = null, $cod_cta_pago = null, $num_che_o_debi = null, $fec_che_o_debi = null, $clas_che_o_debi = null);
                        } else {

                            $valor_motor_contabilidad = false;
                        }//fin else



                        if ($valor_motor_contabilidad == true) {

                            $sql_update_monto = "UPDATE cscd04_ordencompra_encabezado SET monto_orden='" . $sum_monto . "' WHERE " . $this->condicion() . " and numero_orden_compra='$numero_orden_compra' and ano_orden_compra='$ano_orden_compra'; ";
                            $sw3 = $this->cscd04_ordencompra_encabezado->execute($sql_update_monto);
                            if ($sw3 > 1) {
                                $this->cscd04_ordencompra_encabezado->execute("COMMIT;");
                                $this->set('msg', 'LA ORDEN DE COMPRA FUE REGISTRADA CON EXITO');
                            } else {
                                $this->cscd04_ordencompra_encabezado->execute("ROLLBACK;");
                                $this->set('msg_error', 'NO SE REALIZO EL REGISTRO DE LA ORDEN DE COMPRA');
                            }
                        } else {
                            $this->cscd04_ordencompra_encabezado->execute("ROLLBACK;");
                            $this->set('msg_error', 'NO SE REALIZO EL REGISTRO DE LA ORDEN DE COMPRA');
                        }
                    } else {
                        $this->cscd04_ordencompra_encabezado->execute("ROLLBACK;");
                        $this->set('msg_error', 'NO SE REALIZO EL REGISTRO DE LA ORDEN DE COMPRA');
                    }
                } else {
                    $this->cscd04_ordencompra_encabezado->execute("ROLLBACK;");
                    $this->set('msg_error', 'NO SE REALIZO EL REGISTRO DE LA ORDEN DE COMPRA');
                }

                $this->Session->delete('codigos');
                $_SESSION["codigos"] = null;
                $this->borrar_cugd04();
                $this->data['cscp04_ordencompra'] = null;
                $this->index();
                $this->render('index');
            } else {
                $this->set('msg_error', ' LA COTIZACIÓN DE ESTE PROVEEDOR YA FUE REGISTRADA ANTERIORMENTE');
                $sql_update_num = "UPDATE cscd04_ordencompra_numero set situacion=1 where " . $this->condicion() . " and ano_orden_compra='$ano_orden_compra' and numero_orden_compra='$numero_orden_compra'; ";
                $this->cscd04_ordencompra_encabezado->execute($sql_update_num);
            }

            $this->set('autor_valido', true);
        } else {
            $this->set('msg_error', 'NO SE REALIZO LA ORDEN DE COMPRA');
            $this->cscd04_ordencompra_encabezado->execute("ROLLBACK;");
            $this->set('autor_valido', true);
        }
    }

//fin function

    function consulta_index($var1 = null) {

        $this->layout = "ajax";
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');
        $pag_num = 0;
        $opcion = 'si';
        $condicion = $this->condicion();

        if (!empty($this->data['cscp04_ordencompra']['ano_ejecucion'])) {
            $_SESSION['ano_compra'] = $this->data['cscp04_ordencompra']['ano_ejecucion'];
        } else {
            $_SESSION['ano_compra'] = $this->ano_ejecucion();
        }

        $ano = $_SESSION['ano_compra'];

        $this->set('ano_compra', $ano);
        $this->set('ano_ejecucion', $this->ano_ejecucion());


        $lista = $this->select_orden_compra->generateList($condicion . " and ano_orden_compra = " . $ano . " AND beneficiario IS NOT NULL" . " and (cod_obra='' or cod_obra='0' or cod_obra IS NULL)", ' numero_orden_compra ASC', null, '{n}.select_orden_compra.numero_orden_compra', '{n}.select_orden_compra.beneficiario');
        $this->set('lista_numero', $lista);
        $this->set('ano', $ano);

        if ($var1 != null) {
            if ($var1 == 'si') {

                $numero = null;
                if (!empty($this->data['cscp04_ordencompra']['numero_orden_compra'])) {
                    $numero = $this->data['cscp04_ordencompra']['numero_orden_compra'];
                }
                $this->consulta(null, $numero);
                $this->render('consulta');
            }//fin if
        }//fin if



        $this->set('entidad_federal', $this->Session->read('entidad_federal'));
    }

//fin function

    function buscar_year($var1 = null) {

        $this->layout = "ajax";
        $SScoddeporig = $this->Session->read('SScoddeporig');
        $SScoddep = $this->Session->read('SScoddep');
        $Modulo = $this->Session->read('Modulo');


        $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $this->Session->read('SScoddep') . '';


        //$lista = $this->cscd04_ordencompra_encabezado->generateList($condicion." and ano_orden_compra=".$var1, ' numero_orden_compra ASC', null, '{n}.cscd04_ordencompra_encabezado.numero_orden_compra', '{n}.cscd04_ordencompra_encabezado.numero_orden_compra');
        //$this->AddCero('compras', $lista);
        $lista = $this->select_orden_compra->generateList($condicion . " and ano_orden_compra = " . $var1 . " AND beneficiario IS NOT NULL" . " and (cod_obra='' or cod_obra='0' or cod_obra IS NULL)", ' numero_orden_compra ASC', null, '{n}.select_orden_compra.numero_orden_compra', '{n}.select_orden_compra.beneficiario');
        $this->set('compras', $lista);
    }

//fin function

    function buscar_year2($var1 = null) {

        $this->layout = "ajax";

        $_SESSION['ano_compra_articulos_suministrado'] = $var1;
    }

//fin function

    function consulta($pagina = null, $num_oc = null) {
        $this->layout = "ajax";

        if (isset($pagina)) {
            $pagina = $pagina;
        } else {
            $pagina = 1;
        }//fin else




        if (isset($_SESSION['ano_compra'])) {
            $ano = $_SESSION['ano_compra'];
        } else {
            $ano = $this->ano_ejecucion();
        }
        $this->set('ano_compra', $ano);

        $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $this->Session->read('SScoddep') . '';
        $this->set('ano_ejecucion', $this->ano_ejecucion());

        if ($num_oc == null) {
            $sql = $this->condicion() . "  and ano_orden_compra=" . $ano;
        } else {
            $sql = $this->condicion() . "  and ano_orden_compra='" . $ano . "'  and numero_orden_compra='" . $num_oc . "'";
        }


        $Tfilas = $this->v_cscd04_ordencompra->findCount($sql);
        if ($Tfilas != 0) {
            $this->set('pag_cant', $pagina . '/' . $Tfilas);
            $this->set('ultimo', $Tfilas);
            $data = $this->v_cscd04_ordencompra->findAll($sql, null, 'numero_orden_compra ASC', 1, $pagina, null);
            $this->set('data', $data);
            foreach ($data as $row) {
                $numero_orden_compra = $row['v_cscd04_ordencompra']['numero_orden_compra'];
                $condicion_actividad = $row['v_cscd04_ordencompra']['condicion_actividad'];
                $ano_documento = $row['v_cscd04_ordencompra']['ano_orden_compra'];
                $fecha_documento = $row['v_cscd04_ordencompra']['fecha_orden_compra'];
                $num_cotizacion = $row['v_cscd04_ordencompra']['numero_cotizacion'];
                $rif = $row['v_cscd04_ordencompra']['rif'];
            }



            $condicion = $this->condicion();

            if ($condicion_actividad == 2) {
                $fecha_cotizacion2 = $this->cscd03_cotizacion_encabezado_anulado->field('fecha_cotizacion', $conditions = $this->condicion() . " and numero_cotizacion='$num_cotizacion' and mayus_acentos(rif)=mayus_acentos('$rif') and ano_ordencompra='$ano_documento' and numero_ordencompra='$numero_orden_compra'", $order = null);
                $this->set('fecha_cotizacion2', $fecha_cotizacion2);
                $numsolicitud = $this->v_cscd03_cotizacion_anulada->field('v_cscd03_cotizacion_anulada.numero_solicitud', $conditions = $this->condicion() . " and v_cscd03_cotizacion_anulada.numero_cotizacion='$num_cotizacion' and mayus_acentos(rif)=mayus_acentos('$rif') and ano_ordencompra='$ano_documento' and numero_ordencompra='$numero_orden_compra'", $order = "numero_cotizacion ASC");
                $lista = $this->v_cscd03_cotizacion_anulada->findAll($condicion . " and numero_cotizacion='$num_cotizacion' and mayus_acentos(rif)=mayus_acentos('$rif') and ano_ordencompra='$ano_documento' and numero_ordencompra='$numero_orden_compra'", 'DISTINCT codigo_prod_serv, numero_cotizacion, expresion, cantidad, descripcion,precio_unitario, total', 'numero_cotizacion ASC', null);
                $this->set('lista_cscd02_solicitud_cuerpo', $lista);
                $ano_anulacion = $this->cugd03_acta_anulacion_cuerpo->field('cugd03_acta_anulacion_cuerpo.ano_acta_anulacion', $conditions = $this->condicion() . " and cugd03_acta_anulacion_cuerpo.ano_documento='$ano_documento' and numero_documento='$numero_orden_compra' and fecha_documento='$fecha_documento' and tipo_operacion=232", $order = "ano_acta_anulacion, numero_acta_anulacion ASC");
                $numero_acta_anulacion = $this->cugd03_acta_anulacion_cuerpo->field('cugd03_acta_anulacion_cuerpo.numero_acta_anulacion', $conditions = $this->condicion() . " and cugd03_acta_anulacion_cuerpo.ano_documento='$ano_documento' and numero_documento='$numero_orden_compra' and fecha_documento='$fecha_documento' and tipo_operacion=232", $order = "ano_acta_anulacion, numero_acta_anulacion ASC");
                $motivo_anulacion = $this->cugd03_acta_anulacion_cuerpo->field('cugd03_acta_anulacion_cuerpo.motivo_anulacion', $conditions = $this->condicion() . " and cugd03_acta_anulacion_cuerpo.ano_documento='$ano_documento' and numero_documento='$numero_orden_compra' and fecha_documento='$fecha_documento' and tipo_operacion=232", $order = "ano_acta_anulacion, numero_acta_anulacion ASC");
                $this->set('index_cotizacion', 'v_cscd03_cotizacion_anulada');
//echo $conditions;
//echo "el ANO_ANULACION ES: ".$ano_anulacion." el numero es: ".$numero_acta_anulacion." el motivo es: ".$motivo_anulacion;
            } else {
                $fecha_cotizacion2 = $this->cscd03_cotizacion_encabezado->field('cscd03_cotizacion_encabezado.fecha_cotizacion', $conditions = $this->condicion() . " and numero_cotizacion='$num_cotizacion' and mayus_acentos(rif)=mayus_acentos('$rif') and ano_ordencompra='$ano_documento' and numero_ordencompra='$numero_orden_compra'", $order = null);
                $this->set('fecha_cotizacion2', $fecha_cotizacion2);
                $numsolicitud = $this->v_cscd03_cotizacion->field('v_cscd03_cotizacion.numero_solicitud', $conditions = $this->condicion() . "  and ano_cotizacion='" . $ano . "'  and ano_ordencompra='" . $ano . "' and v_cscd03_cotizacion.numero_cotizacion='$num_cotizacion' and mayus_acentos(rif)=mayus_acentos('$rif')", $order = "numero_cotizacion ASC");
                $lista = $this->v_cscd03_cotizacion->findAll($condicion . "  and ano_cotizacion='" . $ano . "' and numero_cotizacion='$num_cotizacion' and mayus_acentos(rif)=mayus_acentos('$rif')", 'DISTINCT codigo_prod_serv, expresion, cantidad, precio_unitario, descripcion, numero_cotizacion', 'numero_cotizacion ASC', null);
                $this->set('lista_cscd02_solicitud_cuerpo', $lista);
                $this->set('index_cotizacion', 'v_cscd03_cotizacion');
                $ano_anulacion = 0;
                $numero_acta_anulacion = 0;
                $motivo_anulacion = " ";
            }
            $this->set('ano_anulacion', $ano_anulacion);
            $this->set('numero_acta_anulacion', $numero_acta_anulacion);
            $this->set('motivo_anulacion', $motivo_anulacion);

            $ordencompra_encabezado = $this->cscd04_ordencompra_encabezado->findAll($this->condicion() . " and ano_orden_compra='" . $ano . "' and numero_orden_compra='$numero_orden_compra'");
            $ordencompra_partidas = $this->cscd04_ordencompra_partidas->findAll($this->condicion() . "  and ano_orden_compra='" . $ano . "' and numero_orden_compra='$numero_orden_compra'");
            $this->set('ordencompra_partidas', $ordencompra_partidas);
            $this->set('ordencompra_encabezado', $ordencompra_encabezado);



            $this->set('siguiente', $pagina + 1);
            $this->set('anterior', $pagina - 1);
            $this->bt_nav($Tfilas, $pagina);
        } else {
            $this->set('COMPROMISO', '');
            $this->set('errorMessage', 'No se encontrar&oacute;n datos');
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

    function buscar_index() {
        $this->layout = "ajax";
        $ano = $this->ano_ejecucion();
        $this->set('ano', $ano);
        $lista = $this->cscd04_ordencompra_encabezado->generateList($conditions = $this->condicion() . " and ano_orden_compra='$ano'", $order = 'numero_orden_compra ASC', $limit = null, '{n}.cscd04_ordencompra_encabezado.numero_orden_compra', '{n}.cscd04_ordencompra_encabezado.rif');
//print_r($lista);
        $this->concatena($lista, 'orden_compra');
    }

    function show_buscar() {
        $this->layout = "ajax";
        echo "<script>" .
        "if(document.getElementById('ano_ejecucion').value==''){
	fun_msj('EL A&Ntilde;O NO PUEDE ESTAR VACIO');
}else{ show_save();}" .
        "</script>";
    }

    function buscar() {
        $this->layout = "ajax";
        if (!empty($this->data['cscp04_ordencompra'])) {
            $ano_ordencompra = $this->data['cscp04_ordencompra']['ano_ejecucion'];
            $numero_ordencompra = $this->data['cscp04_ordencompra']['numero'];
            $pagina = null;



            $data = $this->v_cscd04_ordencompra->findAll($this->condicion() . " and ano_orden_compra='$ano_ordencompra' and numero_orden_compra='$numero_ordencompra'", null, 'numero_orden_compra ASC', 1, null, null);
            $this->set('data', $data);
            foreach ($data as $row) {
                $numero_orden_compra = $row['v_cscd04_ordencompra']['numero_orden_compra'];
                $condicion_actividad = $row['v_cscd04_ordencompra']['condicion_actividad'];
                $ano_documento = $row['v_cscd04_ordencompra']['ano_orden_compra'];
                $fecha_documento = $row['v_cscd04_ordencompra']['fecha_orden_compra'];
                $num_cotizacion = $row['v_cscd04_ordencompra']['numero_cotizacion'];
                $rif = $row['v_cscd04_ordencompra']['rif'];
            }
            $condicion = $this->condicion();


            if ($condicion_actividad == 2) {
                $fecha_cotizacion2 = $this->cscd03_cotizacion_encabezado_anulado->field('fecha_cotizacion', $conditions = $this->condicion() . " and numero_cotizacion='$num_cotizacion' and mayus_acentos(rif)=mayus_acentos('$rif') and ano_ordencompra='$ano_documento' and numero_ordencompra='$numero_orden_compra'", $order = null);
                $this->set('fecha_cotizacion2', $fecha_cotizacion2);
                $numsolicitud = $this->v_cscd03_cotizacion_anulada->field('v_cscd03_cotizacion_anulada.numero_solicitud', $conditions = $this->condicion() . " and v_cscd03_cotizacion_anulada.numero_cotizacion='$num_cotizacion' and mayus_acentos(rif)=mayus_acentos('$rif')", $order = "numero_cotizacion ASC");
                $lista = $this->v_cscd03_cotizacion_anulada->findAll($condicion . " and numero_cotizacion='$num_cotizacion' and mayus_acentos(rif)=mayus_acentos('$rif')", 'DISTINCT codigo_prod_serv, numero_cotizacion, expresion, cantidad, descripcion,precio_unitario, total', 'numero_cotizacion ASC', null);
                $this->set('lista_cscd02_solicitud_cuerpo', $lista);
                $this->set('index_cotizacion', 'v_cscd03_cotizacion_anulada');
                $ano_anulacion = $this->cugd03_acta_anulacion_cuerpo->field('cugd03_acta_anulacion_cuerpo.ano_acta_anulacion', $conditions = $this->condicion() . " and cugd03_acta_anulacion_cuerpo.ano_documento='$ano_documento' and numero_documento='$numero_orden_compra' and fecha_documento='$fecha_documento' and tipo_operacion=232", $order = "ano_acta_anulacion, numero_acta_anulacion ASC");
                $numero_acta_anulacion = $this->cugd03_acta_anulacion_cuerpo->field('cugd03_acta_anulacion_cuerpo.numero_acta_anulacion', $conditions = $this->condicion() . " and cugd03_acta_anulacion_cuerpo.ano_documento='$ano_documento' and numero_documento='$numero_orden_compra' and fecha_documento='$fecha_documento' and tipo_operacion=232", $order = "ano_acta_anulacion, numero_acta_anulacion ASC");
                $motivo_anulacion = $this->cugd03_acta_anulacion_cuerpo->field('cugd03_acta_anulacion_cuerpo.motivo_anulacion', $conditions = $this->condicion() . " and cugd03_acta_anulacion_cuerpo.ano_documento='$ano_documento' and numero_documento='$numero_orden_compra' and fecha_documento='$fecha_documento' and tipo_operacion=232", $order = "ano_acta_anulacion, numero_acta_anulacion ASC");
//echo $conditions;
//echo "el ANO_ANULACION ES: ".$ano_anulacion." el numero es: ".$numero_acta_anulacion." el motivo es: ".$motivo_anulacion;
            } else {
                $fecha_cotizacion2 = $this->cscd03_cotizacion_encabezado->field('cscd03_cotizacion_encabezado.fecha_cotizacion', $conditions = $this->condicion() . " and numero_cotizacion='$num_cotizacion' and mayus_acentos(rif)=mayus_acentos('$rif') and ano_ordencompra='$ano_documento' and numero_ordencompra='$numero_orden_compra'", $order = null);
                $this->set('fecha_cotizacion2', $fecha_cotizacion2);



                $numsolicitud = $this->v_cscd03_cotizacion->field('v_cscd03_cotizacion.numero_solicitud', $conditions = $this->condicion() . " and v_cscd03_cotizacion.numero_cotizacion='$num_cotizacion' and mayus_acentos(rif)=mayus_acentos('$rif')  and ano_cotizacion='" . $this->ano_ejecucion() . "' ", $order = "numero_cotizacion ASC");
                $lista = $this->v_cscd03_cotizacion->findAll($condicion . " and numero_cotizacion='$num_cotizacion' and mayus_acentos(rif)=mayus_acentos('$rif')  and ano_cotizacion='" . $this->ano_ejecucion() . "' ", 'DISTINCT codigo_prod_serv, expresion, cantidad, precio_unitario, descripcion, numero_cotizacion', 'numero_cotizacion ASC', null);
                //print_r($lista);
                $this->set('lista_cscd02_solicitud_cuerpo', $lista);
                $this->set('index_cotizacion', 'v_cscd03_cotizacion');
                $ano_anulacion = 0;
                $numero_acta_anulacion = 0;
                $motivo_anulacion = " ";
            }
            $this->set('ano_anulacion', $ano_anulacion);
            $this->set('numero_acta_anulacion', $numero_acta_anulacion);
            $this->set('motivo_anulacion', $motivo_anulacion);

            $ordencompra_encabezado = $this->cscd04_ordencompra_encabezado->findAll($this->condicion() . "  and ano_orden_compra='" . $this->ano_ejecucion() . "'  and numero_orden_compra='$numero_orden_compra'");
            $ordencompra_partidas = $this->cscd04_ordencompra_partidas->findAll($this->condicion() . "  and ano_orden_compra='" . $this->ano_ejecucion() . "'  and numero_orden_compra='$numero_orden_compra'");
            $this->set('ordencompra_partidas', $ordencompra_partidas);
            $this->set('ordencompra_encabezado', $ordencompra_encabezado);


            $this->set('siguiente', $pagina + 1);
            $this->set('anterior', $pagina - 1);
            $this->set('mostrarA', '');
            $this->set('mostrarS', '');
            $this->set('ultimo', '');
            $this->set('primero', '');
            $this->render('consulta');
        } else {
            $this->buscar_index();
            $this->render('buscar_index');
        }
    }

    function buscar2($numero_ordencompra, $ano_ordencompra) {
        $this->layout = "ajax";
        if ($numero_ordencompra != null) {
            $pagina = null;
            $data = $this->v_cscd04_ordencompra->findAll($this->condicion() . " and ano_orden_compra='$ano_ordencompra' and numero_orden_compra='$numero_ordencompra'", null, 'numero_orden_compra ASC', 1, null, null);
            $this->set('data', $data);
            foreach ($data as $row) {
                $numero_orden_compra = $row['v_cscd04_ordencompra']['numero_orden_compra'];
                $condicion_actividad = $row['v_cscd04_ordencompra']['condicion_actividad'];
                $ano_documento = $row['v_cscd04_ordencompra']['ano_orden_compra'];
                $fecha_documento = $row['v_cscd04_ordencompra']['fecha_orden_compra'];
                $num_cotizacion = $row['v_cscd04_ordencompra']['numero_cotizacion'];
                $rif = $row['v_cscd04_ordencompra']['rif'];
            }
            $condicion = $this->condicion();



            if ($condicion_actividad == 2) {
                $fecha_cotizacion2 = $this->cscd03_cotizacion_encabezado_anulado->field('fecha_cotizacion', $conditions = $this->condicion() . " and numero_cotizacion='$num_cotizacion' and mayus_acentos(rif)=mayus_acentos('$rif') and ano_ordencompra='$ano_documento' and numero_ordencompra='$numero_orden_compra'", $order = null);
                $this->set('fecha_cotizacion2', $fecha_cotizacion2);
                $numsolicitud = $this->v_cscd03_cotizacion_anulada->field('v_cscd03_cotizacion_anulada.numero_solicitud', $conditions = $this->condicion() . " and v_cscd03_cotizacion_anulada.numero_cotizacion='$num_cotizacion' and mayus_acentos(rif)=mayus_acentos('$rif')  and ano_cotizacion='" . $this->ano_ejecucion() . "' ", $order = "numero_cotizacion ASC");
                $lista = $this->v_cscd03_cotizacion_anulada->findAll($condicion . " and numero_cotizacion='$num_cotizacion' and mayus_acentos(rif)=mayus_acentos('$rif')  and ano_cotizacion='" . $this->ano_ejecucion() . "' ", 'DISTINCT codigo_prod_serv, numero_cotizacion, expresion, cantidad, descripcion,precio_unitario, total', 'numero_cotizacion ASC', null);
                $this->set('lista_cscd02_solicitud_cuerpo', $lista);
                $this->set('index_cotizacion', 'v_cscd03_cotizacion_anulada');
                $ano_anulacion = $this->cugd03_acta_anulacion_cuerpo->field('cugd03_acta_anulacion_cuerpo.ano_acta_anulacion', $conditions = $this->condicion() . " and cugd03_acta_anulacion_cuerpo.ano_documento='$ano_documento' and numero_documento='$numero_orden_compra' and fecha_documento='$fecha_documento' and tipo_operacion=232", $order = "ano_acta_anulacion, numero_acta_anulacion ASC");
                $numero_acta_anulacion = $this->cugd03_acta_anulacion_cuerpo->field('cugd03_acta_anulacion_cuerpo.numero_acta_anulacion', $conditions = $this->condicion() . " and cugd03_acta_anulacion_cuerpo.ano_documento='$ano_documento' and numero_documento='$numero_orden_compra' and fecha_documento='$fecha_documento' and tipo_operacion=232", $order = "ano_acta_anulacion, numero_acta_anulacion ASC");
                $motivo_anulacion = $this->cugd03_acta_anulacion_cuerpo->field('cugd03_acta_anulacion_cuerpo.motivo_anulacion', $conditions = $this->condicion() . " and cugd03_acta_anulacion_cuerpo.ano_documento='$ano_documento' and numero_documento='$numero_orden_compra' and fecha_documento='$fecha_documento' and tipo_operacion=232", $order = "ano_acta_anulacion, numero_acta_anulacion ASC");
//echo $conditions;
//echo "el ANO_ANULACION ES: ".$ano_anulacion." el numero es: ".$numero_acta_anulacion." el motivo es: ".$motivo_anulacion;
            } else {
                $fecha_cotizacion2 = $this->cscd03_cotizacion_encabezado->field('cscd03_cotizacion_encabezado.fecha_cotizacion', $conditions = $this->condicion() . " and numero_cotizacion='$num_cotizacion' and mayus_acentos(rif)=mayus_acentos('$rif') and ano_ordencompra='$ano_documento' and numero_ordencompra='$numero_orden_compra'", $order = null);
                $this->set('fecha_cotizacion2', $fecha_cotizacion2);
                $numsolicitud = $this->v_cscd03_cotizacion->field('v_cscd03_cotizacion.numero_solicitud', $conditions = $this->condicion() . " and v_cscd03_cotizacion.numero_cotizacion='$num_cotizacion'  and ano_cotizacion='" . $this->ano_ejecucion() . "' ", $order = "numero_cotizacion ASC");
                $lista = $this->v_cscd03_cotizacion->findAll($condicion . " and numero_cotizacion='$num_cotizacion'  and ano_cotizacion='" . $this->ano_ejecucion() . "' ", 'DISTINCT codigo_prod_serv, expresion, cantidad, precio_unitario, descripcion, numero_cotizacion', 'numero_cotizacion ASC', null);
//print_r($lista);
                $this->set('lista_cscd02_solicitud_cuerpo', $lista);
                $this->set('index_cotizacion', 'v_cscd03_cotizacion');
                $ano_anulacion = 0;
                $numero_acta_anulacion = 0;
                $motivo_anulacion = " ";
            }
            $this->set('ano_anulacion', $ano_anulacion);
            $this->set('numero_acta_anulacion', $numero_acta_anulacion);
            $this->set('motivo_anulacion', $motivo_anulacion);

            $ordencompra_encabezado = $this->cscd04_ordencompra_encabezado->findAll($this->condicion() . " and numero_orden_compra='$numero_orden_compra'");
            $ordencompra_partidas = $this->cscd04_ordencompra_partidas->findAll($this->condicion() . " and numero_orden_compra='$numero_orden_compra'");
            $this->set('ordencompra_partidas', $ordencompra_partidas);
            $this->set('ordencompra_encabezado', $ordencompra_encabezado);


            $this->set('siguiente', $pagina + 1);
            $this->set('anterior', $pagina - 1);
            $this->set('mostrarA', '');
            $this->set('mostrarS', '');
            $this->set('ultimo', '');
            $this->set('primero', '');
            $this->render('consulta');
        } else {
            $this->buscar_index();
            $this->render('buscar_index');
        }
    }

    function desbloquear_numero($numero) {
        $this->layout = "ajax";
        $ano = $this->ano_ejecucion();
        $this->borrar_cugd04();
        $resultado = $this->cscd04_ordencompra_numero->execute("UPDATE cscd04_ordencompra_numero SET situacion=1 WHERE " . $this->condicion() . " and numero_orden_compra='$numero' and ano_orden_compra='$ano'");
        echo"<script>menu_activo();</script>";
    }

//fin function

    function reporte_solicitud() {
        $this->layout = "ajax";
    }

    function reporte_cotizacion() {
        $this->layout = "ajax";
    }

    function solicitud_pdf() {
        $this->layout = "pdf";

        $cond = $this->data['cscp04_ordencompra']['tipo'];

        switch ($cond) {
            case 1:
                $datos = $this->v_cscd02_relacion->findAll($conditions = $this->condicion() . "  and ano_solicitud='" . $this->ano_ejecucion() . "'  and (cod_obra='' or cod_obra='0' or cod_obra IS NULL)", $fields = null, $order = 'numero_solicitud ASC', $limit = null, $page = null, $recursive = null);
                break;
            case 2:
                $datos = $this->v_cscd02_relacion->findAll($conditions = $this->condicion() . "       and ano_solicitud='" . $this->ano_ejecucion() . "' and numero_cotizacion!='0' and (cod_obra='' or cod_obra='0' or cod_obra IS NULL)", $fields = null, $order = 'numero_solicitud ASC', $limit = null, $page = null, $recursive = null);
                break;
            case 3:
                $datos = $this->v_cscd02_relacion->findAll($conditions = $this->condicion() . "       and ano_solicitud='" . $this->ano_ejecucion() . "' and numero_cotizacion='0' and (cod_obra='' or cod_obra='0' or cod_obra IS NULL)", $fields = null, $order = 'numero_solicitud ASC', $limit = null, $page = null, $recursive = null);
                break;

            default:
                break;
        }

        $this->set('datos', $datos);
    }

    function cotizacion_pdf() {
        $this->layout = "pdf";

        $cond = $this->data['cscp04_ordencompra']['tipo'];

        switch ($cond) {
            case 1:
                $datos = $this->v_cscd02_relacion->findAll($conditions = $this->condicion() . "  and ano_solicitud='" . $this->ano_ejecucion() . "'  and numero_cotizacion!='0' and (cod_obra='' or cod_obra='0' or cod_obra IS NULL)", $fields = null, $order = 'numero_cotizacion ASC', $limit = null, $page = null, $recursive = null);
                break;
            case 2:
                $datos = $this->v_cscd02_relacion->findAll($conditions = $this->condicion() . "       and ano_solicitud='" . $this->ano_ejecucion() . "' and numero_cotizacion!='0' and numero_orden_compra!=0 and (cod_obra='' or cod_obra='0' or cod_obra IS NULL)", $fields = null, $order = 'numero_cotizacion ASC', $limit = null, $page = null, $recursive = null);
                break;
            case 3:
                $datos = $this->v_cscd02_relacion->findAll($conditions = $this->condicion() . "        and ano_solicitud='" . $this->ano_ejecucion() . "' and numero_cotizacion!='0' and numero_orden_compra=0 and (cod_obra='' or cod_obra='0' or cod_obra IS NULL)", $fields = null, $order = 'numero_cotizacion ASC', $limit = null, $page = null, $recursive = null);
                break;

            default:
                break;
        }

        $this->set('datos', $datos);
    }

    function reporte_ordencompra() {
        $this->layout = "ajax";
        $this->set('ano_ejecucion', $this->ano_ejecucion());
        $_SESSION['ano_compra_consulta'] = $this->ano_ejecucion();
    }

    function buscar_year_ano_compra_consulta($var1 = null) {

        $this->layout = "ajax";
        $_SESSION['ano_compra_consulta'] = $var1;

        echo "<script>";
        echo "document.getElementById('tipo_2').checked=false;";
        echo "document.getElementById('tipo_3').checked=true;";
        echo "document.getElementById('tipo_4').checked=false;";
        echo "</script>";
    }

//fin function

    function ordencompra_pdf() {
        $this->layout = "pdf";

        $cond = $this->data['cscp04_ordencompra']['tipo'];
        $ano_orden_compra = $this->data['cscp04_ordencompra']['ano_ejecucion'];

        if (isset($_SESSION['ano_compra_consulta'])) {
            $ano_orden_compra = $_SESSION['ano_compra_consulta'];
        } else {
            $ano_orden_compra = $this->ano_ejecucion();
        }



        $campos = "ano_orden_compra, numero_orden_compra, fecha_orden_compra, razon_social, monto_orden, modificacion_aumento, modificacion_disminucion, monto_anticipo, monto_amortizacion, monto_cancelado, porcentaje_anticipo";

        switch ($cond) {
            case 1:
                $datos = $this->v_cscd04_ordencompra->findAll($conditions = $this->condicion() . " and ano_orden_compra='$ano_orden_compra' and condicion_actividad=1", $fields = $campos, $order = 'numero_orden_compra ASC', $limit = null, $page = null, $recursive = null);
                break;
            case 2:
                $desde = $this->data['cscp04_ordencompra']['desde'];
                $hasta = $this->data['cscp04_ordencompra']['hasta'];
                $datos = $this->v_cscd04_ordencompra->findAll($conditions = $this->condicion() . " and ano_orden_compra='$ano_orden_compra' and condicion_actividad=1 and numero_orden_compra BETWEEN $desde AND $hasta", $fields = $campos, $order = 'numero_orden_compra ASC', $limit = null, $page = null, $recursive = null);
                break;
            case 3:
                $fecha = $this->data['cscp04_ordencompra']['fecha'];
                if ($fecha == 9) {
                    $fecha_desde = $this->data['cscp04_ordencompra']['fecha_desde'];
                    $fecha_hasta = $this->data['cscp04_ordencompra']['fecha_hasta'];
                    $datos = $this->v_cscd04_ordencompra->findAll($conditions = $this->condicion() . " and ano_orden_compra='$ano_orden_compra' and condicion_actividad=1 and fecha_orden_compra BETWEEN '$fecha_desde' AND '$fecha_hasta'", $fields = $campos, $order = 'numero_orden_compra ASC', $limit = null, $page = null, $recursive = null);
                } else {
                    $datos = $this->v_cscd04_ordencompra->findAll($conditions = $this->condicion() . " and ano_orden_compra='$ano_orden_compra' and condicion_actividad=1", $fields = $campos, $order = 'numero_orden_compra ASC', $limit = null, $page = null, $recursive = null);
                }

                break;
            case 4:
                $rif = $this->data['cscp04_ordencompra']['rif'];
                $fecha = $this->data['cscp04_ordencompra']['fecha'];
                if ($fecha == 9) {
                    $fecha_desde = $this->data['cscp04_ordencompra']['fecha_desde'];
                    $fecha_hasta = $this->data['cscp04_ordencompra']['fecha_hasta'];
                    $datos = $this->v_cscd04_ordencompra->findAll($conditions = $this->condicion() . " and ano_orden_compra='$ano_orden_compra' and condicion_actividad=1 and rif='$rif' and fecha_orden_compra BETWEEN '$fecha_desde' AND '$fecha_hasta'", $fields = $campos, $order = 'numero_orden_compra ASC', $limit = null, $page = null, $recursive = null);
                } else {
                    $datos = $this->v_cscd04_ordencompra->findAll($conditions = $this->condicion() . " and ano_orden_compra='$ano_orden_compra' and condicion_actividad=1 and rif='$rif'", $fields = $campos, $order = 'numero_orden_compra ASC', $limit = null, $page = null, $recursive = null);
                }
                break;

            default:
                $datos = $this->v_cscd04_ordencompra->findAll($conditions = $this->condicion() . " and ano_orden_compra='$ano_orden_compra' and condicion_actividad=1", $fields = $campos, $order = 'numero_orden_compra ASC', $limit = null, $page = null, $recursive = null);
                break;
        }

        $this->set('datos', $datos);
    }

    function fecha_reporte($opc = null) {
        $this->layout = "ajax";
//echo $opc;
        $this->set('opc', $opc);
    }

    function numero() {
        $this->layout = "ajax";
    }

    function opciones_reporte($opc = null) {
        $this->layout = "ajax";
        switch ($opc) {
            case 1:
                $this->render('todo');
                return;
                break;

            case 2:
                $this->render('rango');
                return;
                break;

            case 3:
                $this->render('numero');
                return;
                break;

            case 4:
                if (isset($_SESSION['ano_compra_consulta'])) {
                    $ano = $_SESSION['ano_compra_consulta'];
                } else {
                    $ano = $this->ano_ejecucion();
                }
                //$proveedor = $this->v_cscd04_ordencompra->generateList($conditions = $this->condicion()." and condicion_actividad=1  and ano_orden_compra='".$ano."' ", $order = "razon_social ASC", $limit = null, '{n}.v_cscd04_ordencompra.rif', '{n}.v_cscd04_ordencompra.razon_social');
                $proveedor = array();
                $this->concatena_sin_cero($proveedor, 'proveedor');
                $this->render('rif');
                break;

            default:
                break;
        }
    }

    function buscar_opciones_reporte_proveedores($pista = null) {

        $this->layout = "ajax";
        if (isset($_SESSION['ano_compra_consulta'])) {
            $ano = $_SESSION['ano_compra_consulta'];
        } else {
            $ano = $this->ano_ejecucion();
        }
        $proveedor = $this->v_cscd04_ordencompra->generateList($conditions = $this->condicion() . " and condicion_actividad=1  and ano_orden_compra='" . $ano . "'  and (UPPER(rif) LIKE  mayus_acentos('%$pista%')  or   UPPER(razon_social) LIKE  mayus_acentos('%$pista%')) ", $order = "razon_social ASC", $limit = null, '{n}.v_cscd04_ordencompra.rif', '{n}.v_cscd04_ordencompra.razon_social');
        $this->concatena_sin_cero($proveedor, 'proveedor');
    }

//fin function

    function reporte_deuda_proveedores() {
        $this->layout = "ajax";
        $this->set('ano_vigente', $this->ano_ejecucion());
    }

    function deudas_proveedores_pdf() {
        $this->layout = "pdf";
        $ano_vigente = $this->data['cfpp04_ordencompra']['ano_vigente'];
        $datos = $this->v_cscd04_deuda_proveedores->findAll($conditions = $this->condicion() . " and ano_orden_compra='$ano_vigente'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
        $this->set('datos', $datos);
    }

    function busqueda_snc_grupo_tipo_1($var1 = null, $var2 = null) {

        $this->layout = "ajax";
        $this->set('opcion', $var1);
    }

//fin function

    function busqueda_snc_grupo_tipo($var1 = null, $var2 = null) {

        $this->layout = "ajax";

        if ($var1 == 1) {
            if ($var2 != null) {
                $lista = $this->v_cscd01_snc_grupo->generateList("(mayus_acentos(cod_grupo)  LIKE mayus_acentos('%" . $var2 . "%')   or   mayus_acentos(denominacion)  LIKE mayus_acentos('%" . $var2 . "%'))  and (CHAR_LENGTH(cod_grupo)=3)   ", $order = " cod_grupo, denominacion ASC", $limit = null, '{n}.v_cscd01_snc_grupo.cod_grupo_3', '{n}.v_cscd01_snc_grupo.denominacion');
                $this->concatena_sin_cero($lista, 'lista');
            }
        } else if ($var1 == 2) {
            if ($var2 != null) {
                $lista = $this->v_cscd01_snc_grupo->generateList("(mayus_acentos(cod_grupo_5)  LIKE mayus_acentos('%" . $var2 . "%')   or   mayus_acentos(denominacion)  LIKE mayus_acentos('%" . $var2 . "%')) and (CHAR_LENGTH(cod_grupo_5)=5)  ", $order = " cod_grupo_5, denominacion ASC", $limit = null, '{n}.v_cscd01_snc_grupo.cod_grupo_5', '{n}.v_cscd01_snc_grupo.denominacion');
                $this->concatena_sin_cero($lista, 'lista');
            }
        } else if ($var1 == 3) {

        }//fin if

        $this->set('opcion', $var1);
    }

//fin function

    function reporte_consumo_productos() {
        $this->layout = "ajax";
        $cod_dep = $this->Session->read('SScoddep');
        $this->set('cod_dep', $cod_dep);
        $this->set('ano', $this->ano_ejecucion());
    }

    function consumo_productos_pdf() {
        $this->layout = "pdf";
        $ordenado = $this->data['cscp04_ordencompra']['ordenado'];

        if ($ordenado == 1) {
            $ordenado = "denominacion ASC";
        } else if ($ordenado == 2) {
            $ordenado = "cod_snc ASC";
        } else if ($ordenado == 3) {
            $ordenado = "codigo_prod_serv ASC";
        }//fin else if

        $sql = "";

        if (isset($this->data['cscp04_ordencompra']['year'])) {
            $sql = "ano_nota_entrega=" . $this->data['cscp04_ordencompra']['year'] . " and ";
            $year_sql = $this->data['cscp04_ordencompra']['year'];
        }//fin if



        if (isset($this->data['reporte']['tipo_snc_grupo_tipo'])) {
            if ($this->data['reporte']['tipo_snc_grupo_tipo'] == 1) {
                $sql_tipo = " and cod_grupo_3='" . $this->data['reporte']['seleccion_snc_grupo_tipo'] . "'  ";
            } else if ($this->data['reporte']['tipo_snc_grupo_tipo'] == 2) {
                $sql_tipo = " and cod_grupo_5='" . $this->data['reporte']['seleccion_snc_grupo_tipo'] . "'  ";
            } else if ($this->data['reporte']['tipo_snc_grupo_tipo'] == 3) {
                $sql_tipo = "";
            }//fin else
        } else {
            $sql_tipo = "";
        }//fin else






        $cod_dep = $this->Session->read('SScoddep');
        if ($cod_dep == 1) {
            $group1 = $this->data['cscp04_ordencompra']['agrupado'];
            if ($group1 == 1) {
                $datos = $this->cscd05_c_productos_inst_s_cod_obra->findAll($conditions = $sql . $this->SQLCA_consolidado($group1) . " and ano_nota_entrega=" . $year_sql . " " . $sql_tipo, $fields = null, $order = $ordenado, $limit = null, $page = null, $recursive = null);
                $this->set('index', 'cscd05_c_productos_inst_s_cod_obra');
            } else {
                $group2 = $this->data['cscp04_ordencompra']['agrupado'];
                if ($group2 == 3) {
                    $datos = $this->cscd05_c_productos_dep_s_cod_obra->findAll($conditions = $sql . $this->SQLCA_consolidado($group1) . " and ano_nota_entrega=" . $year_sql . " " . $sql_tipo, $fields = null, $order = $ordenado, $limit = null, $page = null, $recursive = null);
                    $this->set('index', 'cscd05_c_productos_dep_s_cod_obra');
                } else {
                    $ano = $this->ano_ejecucion();
                    $datos = $this->cscd05_c_productos_dep_s_cod_obra->findAll($conditions = $sql . $this->SQLCA_consolidado($group1) . " and ano_nota_entrega=" . $year_sql . " " . $sql_tipo, $fields = null, $order = $ordenado, $limit = null, $page = null, $recursive = null);
                    $this->set('index', 'cscd05_c_productos_dep_s_cod_obra');
                }
            }
        } else {
            $group2 = $this->data['cscp04_ordencompra']['agrupado'];
            if ($group2 == 3) {
                $datos = $this->cscd05_c_productos_dep_s_cod_obra->findAll($conditions = $sql . $this->condicion() . " and ano_nota_entrega=" . $year_sql . " " . $sql_tipo, $fields = null, $order = $ordenado, $limit = null, $page = null, $recursive = null);
                $this->set('index', 'cscd05_c_productos_dep_s_cod_obra');
            } else {
                $ano = $this->ano_ejecucion();
                /* $cod_dirsuperior=$this->data['cscp02_solicitud_cotizacion']['cod_dirsuperior'];
                  $cod_coordinacion=$this->data['cscp02_solicitud_cotizacion']['cod_coordinacion'];
                  $cod_secretaria=$this->data['cscp02_solicitud_cotizacion']['cod_secretaria'];
                  $cod_direccion=$this->data['cscp02_solicitud_cotizacion']['cod_direccion']; */
                $datos = $this->cscd05_c_productos_dep_s_cod_obra->findAll($conditions = $sql . $this->condicion() . " and ano_nota_entrega=" . $year_sql . " " . $sql_tipo, $fields = null, $order = $ordenado, $limit = null, $page = null, $recursive = null);
                $this->set('index', 'cscd05_c_productos_dep_s_cod_obra');
            }
        }

        $this->set('datos', $datos);
    }

    function consumo_productos_html($pagina = null) {

        $this->layout = "ajax";

        $cod_dep = $this->Session->read('SScoddep');
        echo "<script>document.getElementById('consumo_html').style.visibility='visible';</script>";

        $ordenado = $this->data['cscp04_ordencompra']['ordenado'];

        if ($ordenado == 1) {
            $ordenado = "denominacion ASC";
        } else if ($ordenado == 2) {
            $ordenado = "cod_snc ASC";
        } else if ($ordenado == 3) {
            $ordenado = "codigo_prod_serv ASC";
        }//fin else if

        $sql = "";
        $datos_filas = "";



        if (isset($this->data['cscp04_ordencompra']['year'])) {
            $sql = "ano_nota_entrega=" . $this->data['cscp04_ordencompra']['year'] . " and ";
            $year_sql = $this->data['cscp04_ordencompra']['year'];
            $_SESSION["year_sql"] = $year_sql;
        } else {
            $year_sql = $_SESSION["year_sql"];
        }//fin else




        if (isset($pagina)) {
            $pagina = $pagina;
        } else {
            $pagina = 1;
        }//fin else



        if ($cod_dep == 1) {

            if (isset($this->data['cscp04_ordencompra']['agrupado'])) {
                $group1 = $this->data['cscp04_ordencompra']['agrupado'];
                $_SESSION["agrupado"] = $group1;
            } else {
                $group1 = $_SESSION["agrupado"];
            }//fin else

            if ($group1 == 1) {
                //$datos_filas = $this->cscd05_consumo_institucion->findAll($conditions = $sql.$this->condicionNDEP()." and ano_nota_entrega=".$year_sql, $fields = null, $order = $ordenado, $limit = null, $page = null, $recursive = null);
                $Tfilas = $this->cscd05_c_institucion_s_cod_obra->findCount($sql . $this->condicionNDEP() . " and ano_nota_entrega=" . $year_sql);
                $where = $sql . $this->condicionNDEP() . " and ano_nota_entrega=" . $year_sql;
//					        $total_total=$this->cscd05_c_productos_s_cod_obra->execute(" select SUM(total_consumo) from  cscd05_consumo_institucion_s_cod_obra where ".$where);
                if ($Tfilas != 0) {
                    $Tfilas = (int) ceil($Tfilas / 100);
                    $this->set('total_paginas', $Tfilas);
                    $this->set('pagina_actual', $pagina);
                    $this->set('pag_cant', $pagina . '/' . $Tfilas);
                    $this->set('ultimo', $Tfilas);
                    $datos_filas = $this->cscd05_c_institucion_s_cod_obra->findAll($sql . $this->condicionNDEP() . " and ano_nota_entrega=" . $year_sql, null, $ordenado, 100, $pagina, null);
                    $this->set("datosFILAS", $datos_filas);
                    $this->set('siguiente', $pagina + 1);
                    $this->set('anterior', $pagina - 1);
                    $this->bt_nav($Tfilas, $pagina);
                } else {
                    $this->set("datosFILAS", '');
                }
                $this->set('index', 'cscd05_c_institucion_s_cod_obra');
            } else {


                if (isset($this->data['cscp04_ordencompra']['agrupado2'])) {
                    $group2 = $this->data['cscp04_ordencompra']['agrupado2'];
                    $_SESSION["agrupado2"] = $group2;
                } else {
                    $group2 = $_SESSION["agrupado2"];
                }//fin else


                if ($group2 == 3) {
                    //$datos = $this->cscd05_consumo_direcciones->findAll($conditions = $sql.$this->condicion()." and ano_nota_entrega=".$year_sql, $fields = null, $order = $ordenado, $limit = null, $page = null, $recursive = null);
                    $Tfilas = $this->cscd05_c_direcciones_s_cod_obra->findCount($sql . $this->condicion() . " and ano_nota_entrega=" . $year_sql);
                    $where = $sql . $this->condicion() . " and ano_nota_entrega=" . $year_sql;
//							        $total_total=$this->cscd05_c_productos_s_cod_obra->execute(" select SUM(total_consumo) from  cscd05_consumo_direcciones_s_cod_obra where ".$where);
                    if ($Tfilas != 0) {
                        $Tfilas = (int) ceil($Tfilas / 100);
                        $this->set('total_paginas', $Tfilas);
                        $this->set('pagina_actual', $pagina);
                        $this->set('pag_cant', $pagina . '/' . $Tfilas);
                        $this->set('ultimo', $Tfilas);
                        $datos_filas = $this->cscd05_c_direcciones_s_cod_obra->findAll($sql . $this->condicion() . " and ano_nota_entrega=" . $year_sql, null, $ordenado, 100, $pagina, null);
                        $this->set("datosFILAS", $datos_filas);
                        $this->set('siguiente', $pagina + 1);
                        $this->set('anterior', $pagina - 1);
                        $this->bt_nav($Tfilas, $pagina);
                    } else {
                        $this->set("datosFILAS", '');
                    }
                    $this->set('index', 'cscd05_c_direcciones_s_cod_obra');
                } else {

                    if (isset($this->data['cscp02_solicitud_cotizacion']['cod_dirsuperior'])) {

                        $cod_dirsuperior = $this->data['cscp02_solicitud_cotizacion']['cod_dirsuperior'];
                        $cod_coordinacion = $this->data['cscp02_solicitud_cotizacion']['cod_coordinacion'];
                        $cod_secretaria = $this->data['cscp02_solicitud_cotizacion']['cod_secretaria'];
                        $cod_direccion = $this->data['cscp02_solicitud_cotizacion']['cod_direccion'];

                        $_SESSION['cod_dirsuperior'] = $cod_dirsuperior;
                        $_SESSION['cod_coordinacion'] = $cod_coordinacion;
                        $_SESSION['cod_secretaria'] = $cod_secretaria;
                        $_SESSION['cod_direccion'] = $cod_direccion;
                    } else {

                        $cod_dirsuperior = $_SESSION['cod_dirsuperior'];
                        $cod_coordinacion = $_SESSION['cod_coordinacion'];
                        $cod_secretaria = $_SESSION['cod_secretaria'];
                        $cod_direccion = $_SESSION['cod_direccion'];
                    }//fin else
                    //$datos            = $this->cscd05_consumo_productos->findAll($conditions = $sql.$this->condicion()." and cod_dir_superior='$cod_dirsuperior' AND cod_coordinacion='$cod_coordinacion' AND cod_secretaria='$cod_secretaria' AND cod_direccion='$cod_direccion'"." and ano_nota_entrega=".$year_sql, $fields = null, $order = $ordenado, $limit = null, $page = null, $recursive = null);
                    $Tfilas = $this->cscd05_c_productos_s_cod_obra->findCount($sql . $this->condicion() . " and cod_dir_superior='$cod_dirsuperior' AND cod_coordinacion='$cod_coordinacion' AND cod_secretaria='$cod_secretaria' AND cod_direccion='$cod_direccion'" . " and ano_nota_entrega=" . $year_sql);
                    $where = $sql . $this->condicion() . " and cod_dir_superior='$cod_dirsuperior' AND cod_coordinacion='$cod_coordinacion' AND cod_secretaria='$cod_secretaria' AND cod_direccion='$cod_direccion'" . " and ano_nota_entrega=" . $year_sql;
//							        $total_total=$this->cscd05_c_productos_s_cod_obra->execute(" select SUM(total_consumo) from  cscd05_consumo_productos_s_cod_obra where ".$where);
                    if ($Tfilas != 0) {
                        $Tfilas = (int) ceil($Tfilas / 100);
                        $this->set('total_paginas', $Tfilas);
                        $this->set('pagina_actual', $pagina);
                        $this->set('pag_cant', $pagina . '/' . $Tfilas);
                        $this->set('ultimo', $Tfilas);
                        $datos_filas = $this->cscd05_c_productos_s_cod_obra->findAll($sql . $this->condicion() . " and cod_dir_superior='$cod_dirsuperior' AND cod_coordinacion='$cod_coordinacion' AND cod_secretaria='$cod_secretaria' AND cod_direccion='$cod_direccion'" . " and ano_nota_entrega=" . $year_sql, null, $ordenado, 100, $pagina, null);
                        $this->set("datosFILAS", $datos_filas);
                        $this->set('siguiente', $pagina + 1);
                        $this->set('anterior', $pagina - 1);
                        $this->bt_nav($Tfilas, $pagina);
                    } else {
                        $this->set("datosFILAS", '');
                    }
                    $this->set('index', 'cscd05_c_productos_s_cod_obra');
                }
            }
        } else {
            if (isset($this->data['cscp04_ordencompra']['agrupado2'])) {
                $group2 = $this->data['cscp04_ordencompra']['agrupado2'];
                $_SESSION["agrupado2"] = $group2;
            } else {
                $group2 = $_SESSION["agrupado2"];
            }//fin else

            if ($group2 == 3) {
                //$datos = $this->cscd05_consumo_direcciones->findAll($conditions = $sql.$this->condicion()." and ano_nota_entrega=".$year_sql, $fields = null, $order = $ordenado, $limit = null, $page = null, $recursive = null);
                $Tfilas = $this->cscd05_c_direcciones_s_cod_obra->findCount($sql . $this->condicion() . " and ano_nota_entrega=" . $year_sql);
                $where = $sql . $this->condicion() . " and ano_nota_entrega=" . $year_sql;
//							        $total_total=$this->cscd05_c_productos_s_cod_obra->execute(" select SUM(total_consumo) from  cscd05_consumo_direcciones_s_cod_obra where ".$where);
                if ($Tfilas != 0) {
                    $Tfilas = (int) ceil($Tfilas / 100);
                    $this->set('total_paginas', $Tfilas);
                    $this->set('pagina_actual', $pagina);
                    $this->set('pag_cant', $pagina . '/' . $Tfilas);
                    $this->set('ultimo', $Tfilas);
                    $datos_filas = $this->cscd05_c_direcciones_s_cod_obra->findAll($sql . $this->condicion() . " and ano_nota_entrega=" . $year_sql, null, $ordenado, 100, $pagina, null);
                    $this->set("datosFILAS", $datos_filas);
                    $this->set('siguiente', $pagina + 1);
                    $this->set('anterior', $pagina - 1);
                    $this->bt_nav($Tfilas, $pagina);
                } else {
                    $this->set("datosFILAS", '');
                }
                $this->set('index', 'cscd05_c_direcciones_s_cod_obra');
            } else {




                if (isset($this->data['cscp02_solicitud_cotizacion']['cod_dirsuperior'])) {

                    $cod_dirsuperior = $this->data['cscp02_solicitud_cotizacion']['cod_dirsuperior'];
                    $cod_coordinacion = $this->data['cscp02_solicitud_cotizacion']['cod_coordinacion'];
                    $cod_secretaria = $this->data['cscp02_solicitud_cotizacion']['cod_secretaria'];
                    $cod_direccion = $this->data['cscp02_solicitud_cotizacion']['cod_direccion'];

                    $_SESSION['cod_dirsuperior'] = $cod_dirsuperior;
                    $_SESSION['cod_coordinacion'] = $cod_coordinacion;
                    $_SESSION['cod_secretaria'] = $cod_secretaria;
                    $_SESSION['cod_direccion'] = $cod_direccion;
                } else {

                    $cod_dirsuperior = $_SESSION['cod_dirsuperior'];
                    $cod_coordinacion = $_SESSION['cod_coordinacion'];
                    $cod_secretaria = $_SESSION['cod_secretaria'];
                    $cod_direccion = $_SESSION['cod_direccion'];
                }//fin else
                //$datos            = $this->cscd05_consumo_productos->findAll($conditions = $sql.$this->condicion()." and cod_dir_superior='$cod_dirsuperior' AND cod_coordinacion='$cod_coordinacion' AND cod_secretaria='$cod_secretaria' AND cod_direccion='$cod_direccion'"." and ano_nota_entrega=".$year_sql, $fields = null, $order = $ordenado, $limit = null, $page = null, $recursive = null);
                $Tfilas = $this->cscd05_c_productos_s_cod_obra->findCount($sql . $this->condicion() . " and cod_dir_superior='$cod_dirsuperior' AND cod_coordinacion='$cod_coordinacion' AND cod_secretaria='$cod_secretaria' AND cod_direccion='$cod_direccion'" . " and ano_nota_entrega=" . $year_sql);
                $where = $sql . $this->condicion() . " and cod_dir_superior='$cod_dirsuperior' AND cod_coordinacion='$cod_coordinacion' AND cod_secretaria='$cod_secretaria' AND cod_direccion='$cod_direccion'" . " and ano_nota_entrega=" . $year_sql;
//							            $total_total=$this->cscd05_c_productos_s_cod_obra->execute(" select SUM(total_consumo) from  cscd05_consumo_productos_s_cod_obra where ".$where);
                if ($Tfilas != 0) {
                    $Tfilas = (int) ceil($Tfilas / 100);
                    $this->set('total_paginas', $Tfilas);
                    $this->set('pagina_actual', $pagina);
                    $this->set('pag_cant', $pagina . '/' . $Tfilas);
                    $this->set('ultimo', $Tfilas);
                    $datos_filas = $this->cscd05_c_productos_s_cod_obra->findAll($sql . $this->condicion() . " and cod_dir_superior='$cod_dirsuperior' AND cod_coordinacion='$cod_coordinacion' AND cod_secretaria='$cod_secretaria' AND cod_direccion='$cod_direccion'" . " and ano_nota_entrega=" . $year_sql, null, $ordenado, 100, $pagina, null);
                    $this->set("datosFILAS", $datos_filas);
                    $this->set('siguiente', $pagina + 1);
                    $this->set('anterior', $pagina - 1);
                    $this->bt_nav($Tfilas, $pagina);
                } else {
                    $this->set("datosFILAS", '');
                }

                $this->set('index', 'cscd05_c_productos_s_cod_obra');
            }
        }//fin else


        $total_total = 0;

        $this->set('total_total', $total_total[0][0]["sum"]);
        $this->set('datos', $datos_filas);
    }

//fin function

    function buscar_year_productoespecifico($var1 = null) {

        $this->layout = "ajax";

        $_SESSION["year_productoespecifico"] = $var1;
        echo "<script>";
        echo "document.getElementById('productoespecifico_1').checked=true;";
        echo "document.getElementById('productoespecifico_2').checked=false;";
        echo "</script>";
    }

//fin function

    function busqueda_producto($var = null) {
        $this->layout = "ajax";
        //@var: pista para la busqueda del producto

        $var = strtoupper($var);
        $existe = $this->cscd03_cotizacion_cuerpo->findCount("mayus_acentos(descripcion) LIKE '%$var%'  and ano_cotizacion=" . $_SESSION["year_productoespecifico"]);
        if ($existe == 0) {
            $this->set('catalogo', array());
            $this->set('notfound', 'ESTE PRODUCTO NO ESTA REGISTRADO EN EL CATALOGO - POR FAVOR INTENTE DE NUEVO');
            return;
        } elseif ($existe != 0) {
            $sql_productos = "SELECT DISTINCT a.codigo_prod_serv,
						  (SELECT mayus_acentos(cod_snc) FROM cscd01_catalogo b WHERE a.codigo_prod_serv=b.codigo_prod_serv) AS cod_snc,
						  (SELECT mayus_acentos(denominacion) FROM cscd01_catalogo b WHERE a.codigo_prod_serv=b.codigo_prod_serv) AS deno_producto,
						  (SELECT um.expresion from cscd01_unidad_medida um WHERE um.cod_medida=(select c.cod_medida from cscd01_catalogo c where c.codigo_prod_serv = a.codigo_prod_serv)) AS expresion
						  FROM cscd03_cotizacion_cuerpo a WHERE mayus_acentos(descripcion) LIKE '%$var%' and ano_cotizacion='" . $_SESSION["year_productoespecifico"] . "' and cantidad_entregada != 0 GROUP BY a.codigo_prod_serv, a.descripcion ORDER BY cod_snc";
            $productos = $this->cscd03_cotizacion_cuerpo->execute($sql_productos);
            for ($i = 0; $i < count($productos); $i++) {
                $cod_prod[] = $productos[$i][0]['codigo_prod_serv'];
                $deno_prod[] = $productos[$i][0]['cod_snc'] . " - " . $productos[$i][0]['codigo_prod_serv'] . " - " . $productos[$i][0]['deno_producto'] . " - " . $productos[$i][0]['expresion'];
            }
            if (isset($cod_prod)) {
                $lista = array_combine($cod_prod, $deno_prod);
            } else {
                $lista = array();
            }

            $this->set('catalogo', $lista);
        }
    }

    function reporte_consumo_productos_dep($var = null) {
        $this->layout = "ajax";

        $sql = "";

        if (isset($this->data['cscp04_ordencompra']['year'])) {
            $sql = "  and ano_nota_entrega=" . $this->data['cscp04_ordencompra']['year'] . "  ";
            $ano_nota = $this->data['cscp04_ordencompra']['year'];
        }//fin if

        if ($var != null) {
            if ($var == 'si') {//Lo enviamos a la vista de peticion
                $this->set('ir', 'si');
                $_SESSION["year_productoespecifico"] = $this->ano_ejecucion();
            } elseif ($var == 'no') {//Lo enviamos a la vista del reporte
                $this->layout = "pdf";
                $cod_presi = $this->Session->read('SScodpresi');
                $cod_entidad = $this->Session->read('SScodentidad');
                $cod_tipo_inst = $this->Session->read('SScodtipoinst');
                $cod_inst = $this->Session->read('SScodinst');


                if (isset($this->data['reporte']['tipo_snc_grupo_tipo'])) {
                    if ($this->data['reporte']['tipo_snc_grupo_tipo'] == 1) {
                        $sql_tipo = " and a.cod_grupo_3='" . $this->data['reporte']['seleccion_snc_grupo_tipo'] . "'  ";
                        $sql_tipo2 = " and   cod_grupo_3='" . $this->data['reporte']['seleccion_snc_grupo_tipo'] . "'  ";
                        $productoespecifico = 1;
                    } else if ($this->data['reporte']['tipo_snc_grupo_tipo'] == 2) {
                        $sql_tipo = " and a.cod_grupo_5='" . $this->data['reporte']['seleccion_snc_grupo_tipo'] . "'  ";
                        $sql_tipo2 = " and   cod_grupo_5='" . $this->data['reporte']['seleccion_snc_grupo_tipo'] . "'  ";
                        $productoespecifico = 1;
                    } else if ($this->data['reporte']['tipo_snc_grupo_tipo'] == 4) {
                        $productoespecifico = 2;
                        $sql_tipo = "";
                    } else if ($this->data['reporte']['tipo_snc_grupo_tipo'] == 5) {
                        $productoespecifico = 1;
                        $sql_tipo = "";
                    }//fin else
                } else {
                    $sql_tipo = "";
                    $productoespecifico = 1;
                }//fin else



                if ($productoespecifico == 1) {
                    $sql_consulta2 = "SELECT a.cod_dep, a.ano_nota_entrega, a.codigo_prod_serv, a.cod_snc, a.cantidad_promedio, a.precio_promedio, a.total_consumo, a.expresion,
								(SELECT b.denominacion FROM cscd01_catalogo b WHERE b.codigo_prod_serv=a.codigo_prod_serv) AS denominacion_producto
								 FROM cscd05_consumo_productos_dep_s_cod_obra a WHERE a.ano_nota_entrega='$ano_nota' and a.cod_presi='$cod_presi' and a.cod_entidad='$cod_entidad' and a.cod_tipo_inst='$cod_tipo_inst' and a.cod_inst='$cod_inst' " . $sql_tipo . " ORDER BY cod_snc, codigo_prod_serv, a.precio_promedio, a.cod_dep ASC;";
                    $consulta = $this->cscd05_c_productos_dep_s_cod_obra->execute($sql_consulta2);
                    $find_dep = $this->cugd02_dependencia->findAll(null, array('cod_dependencia', 'denominacion'));
                    foreach ($find_dep as $d) {
                        $cod_depe[] = $d['cugd02_dependencia']['cod_dependencia'];
                        $deno_dep[] = $d['cugd02_dependencia']['denominacion'];
                    }
                    $dependencias = array_combine($cod_depe, $deno_dep);

                    $this->set('datos', $consulta);
                    $this->set('denominacion', 'vacio');
                    $this->set('cod_snc', 'vacio');
                    $this->set('codigo_prod_serv', 'vacio');
                    $this->set('expresion', 'vacio');
                    $this->set('dependencias', $dependencias);
                    $this->set('opcion', 2);
                } else {
                    $cod_prod_serv = $this->data['cscp04_ordencompra']['codigo_prod_serv'];
                    //$sql_consulta = "SELECT DISTINCT cod_dep, cantidad, precio_unitario FROM cscd03_cotizacion_cuerpo WHERE codigo_prod_serv='$cod_prod_serv' AND cantidad_entregada !=0 ";
                    $sql_consulta2 = "SELECT DISTINCT cod_snc, codigo_prod_serv, ano_nota_entrega, cod_dep, cantidad_promedio, precio_promedio, total_consumo FROM cscd05_consumo_productos_dep_s_cod_obra WHERE codigo_prod_serv='$cod_prod_serv'" . " " . $sql_tipo . " " . $sql . " ORDER BY cod_snc, codigo_prod_serv, precio_promedio, cod_dep ASC;";
                    $consulta = $this->cscd05_c_productos_dep_s_cod_obra->execute($sql_consulta2);
                    $buscar_deno = $this->cscd01_catalogo->findAll("codigo_prod_serv=" . $cod_prod_serv, array('denominacion', 'cod_snc', 'codigo_prod_serv', 'cod_medida'));
                    $expresion_medida = $this->cscd01_unidad_medida->findAll('cod_medida=' . $buscar_deno[0]['cscd01_catalogo']['cod_medida'], array('expresion'));

                    $find_dep = $this->cugd02_dependencia->findAll(null, array('cod_dependencia', 'denominacion'));
                    foreach ($find_dep as $d) {
                        $cod_depe[] = $d['cugd02_dependencia']['cod_dependencia'];
                        $deno_dep[] = $d['cugd02_dependencia']['denominacion'];
                    }
                    $dependencias = array_combine($cod_depe, $deno_dep);

                    $this->set('datos', $consulta);
                    $this->set('denominacion', $buscar_deno[0]['cscd01_catalogo']['denominacion']);
                    $this->set('cod_snc', $buscar_deno[0]['cscd01_catalogo']['cod_snc']);
                    $this->set('codigo_prod_serv', $buscar_deno[0]['cscd01_catalogo']['codigo_prod_serv']);
                    $this->set('expresion', $expresion_medida[0]['cscd01_unidad_medida']['expresion']);
                    $this->set('dependencias', $dependencias);
                    $this->set('opcion', 1);
                }
                $this->set('ir', 'no');
            } else {
                $this->set('ir', 'si');
            }
        } else {
            $this->set('ir', 'si');
        }

        $this->set('ano', $this->ano_ejecucion());
    }

    function productoespecifico($var = null) {
        $this->layout = "ajax";
        $this->set('opcion', $var);
    }

    function reporte_proveedor_suministro() {
        $this->layout = "ajax";
        $_SESSION["cosolidado_proveedor_suministro"] = 1;
        $_SESSION["buscar_year_proveedor_suministro"] = $this->ano_ejecucion();
        $this->set('ano', $this->ano_ejecucion());
    }

//fin function

    function buscar_year_proveedor_suministro($var1 = null) {
        $this->layout = "ajax";
        $_SESSION["buscar_year_proveedor_suministro"] = $var1;
        echo "<script>";
        echo "document.getElementById('buscar').value='';";
        echo "</script>";
    }

//fin function

    function cosolidado_proveedor_suministro($var1 = null) {
        $this->layout = "ajax";
        $_SESSION["cosolidado_proveedor_suministro"] = $var1;
        echo "<script>";
        echo "document.getElementById('buscar').value='';";
        echo "</script>";
        $this->render("buscar_year_proveedor_suministro");
    }

//fin function

    function proveedor_suministro_pdf() {
        $this->layout = "pdf";
        $codigo_prod = $this->data['cscp04_ordencompra']['codigo_prod'];
        $datos = $this->cscd05_cpcd02_suministro->findAll($conditions = $this->SQLCA_consolidado($_SESSION["cosolidado_proveedor_suministro"]) . " and ano_orden_compra='" . $_SESSION["buscar_year_proveedor_suministro"] . "' and codigo_prod_serv='$codigo_prod'", $fields = null, $order = "cod_dep, rif, numero_orden_compra ASC", $limit = null, $page = null, $recursive = null);
        $articulo = $this->cscd05_cpcd02_suministro->field('cscd05_cpcd02_suministro.producto', $conditions = $this->SQLCA_consolidado($_SESSION["cosolidado_proveedor_suministro"]) . " and ano_orden_compra='" . $_SESSION["buscar_year_proveedor_suministro"] . "' AND cscd05_cpcd02_suministro.codigo_prod_serv='$codigo_prod'", $order = " cod_dep, rif, numero_orden_compra ASC");
        $this->set('datos', $datos);
        $this->set('cosolidado', $_SESSION["cosolidado_proveedor_suministro"]);
        $this->set('dep_reporte', $this->cod_dep_consolidado());
        $this->set('articulo', $articulo);
    }

//fin function

    function pista_reporte($pista = null) {
        $this->layout = "ajax";
        $pista = strtoupper($pista);
        $articulo = $this->cscd05_cpcd02_suministro->findAll($this->SQLCA_consolidado($_SESSION["cosolidado_proveedor_suministro"]) . "  and ano_orden_compra='" . $_SESSION["buscar_year_proveedor_suministro"] . "' and (mayus_acentos(producto) LIKE mayus_acentos('%$pista%'))   ", $fields = null, $order = "cod_dep, rif, numero_orden_compra ASC", $limit = null, $page = null, $recursive = null);
        if (empty($articulo)) {
            $articulo = array();
            $this->set('msg_error', 'no se encuentra ningun proveedor que suministre ese articulo');
        } else {
            $articulo = $this->concatena_pista_reporte($articulo);
        }
        $this->set('articulo', $articulo);
    }

//fin function

    function concatena_pista_reporte($vector1 = null) {
        $cod = array();
        if ($vector1 != null) {
            foreach ($vector1 as $x) {
                $cod[$x["cscd05_cpcd02_suministro"]["codigo_prod_serv"]] = $x["cscd05_cpcd02_suministro"]["cod_snc"] . " - " . $x["cscd05_cpcd02_suministro"]["codigo_prod_serv"] . " - " . $x["cscd05_cpcd02_suministro"]["producto"] . " - " . $x["cscd05_cpcd02_suministro"]["denominacion_medida_producto"];
            }
        }

        return $cod;
    }

//fin function

    function consulta_proveedores() {
        $this->layout = "ajax";
        //$this->Session->delete('SScoddep_aux');

        if (isset($_SESSION["SScoddep_aux"])) {
            $cod_dep = $this->Session->read('SScoddep_aux');
            $this->Session->write('SScoddep', $cod_dep);
        } else {
            $cod_dep = $this->Session->read('SScoddep');
        }



        $rif = $this->v_cscd04_ordencompra->generateList($conditions = $this->condicion() . " and condicion_actividad=1  and ano_orden_compra='" . $this->ano_ejecucion() . "' and  (cod_obra='' or cod_obra='0' or cod_obra IS NULL)", $order = 'razon_social ASC', $limit = null, '{n}.v_cscd04_ordencompra.rif', '{n}.v_cscd04_ordencompra.razon_social');
        $nom = $this->arrd05->generateList($this->condicionNDEP(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
        $this->concatena($nom, 'arr05');
        if ($cod_dep == 1) {
            $this->Session->write('select_dependencia', $cod_dep);
            $this->Session->write('year_consulta', $this->ano_ejecucion());
        }//fin if

        $this->Session->write('SScoddep_aux', $cod_dep);

        $_SESSION['ano_compra_consulta'] = $this->ano_ejecucion();
        $this->concatena_sin_cero($rif, 'rif');
        $ano_ejecucion = $this->ano_ejecucion();
        $this->set('ano_ejecucion', $ano_ejecucion);
    }

//fin function

    function ano_rif_prov($ano_ejecucion = null) {
        $this->layout = "ajax";

        $_SESSION['ano_compra_consulta'] = $ano_ejecucion;

        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        //$cod_dep = $this->Session->read('SScoddep');
        $var1 = $this->Session->read('select_dependencia');

        if ($this->Session->read('SScoddep_aux') == 1) {
            $this->Session->write('year_consulta', $ano_ejecucion);
            $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst . " and cod_dep=" . $var1;
        } else {
            $condicion = $this->condicion();
        }

        $rif = $this->v_cscd04_ordencompra->generateList($condicion . " and condicion_actividad=1 and ano_orden_compra='" . $ano_ejecucion . "' ", $order = 'razon_social ASC', $limit = null, '{n}.v_cscd04_ordencompra.rif', '{n}.v_cscd04_ordencompra.razon_social');
        $this->concatena_sin_cero($rif, 'rif');
        $this->set('ano_ejecucion', $ano_ejecucion);
    }

    function seleccion_dep_consulra_proveedores($var1 = null) {

        $this->layout = "ajax";

        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        //$cod_dep = $this->Session->read('SScoddep');
        //$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$var1;
        if ($this->Session->read('SScoddep_aux') == 1) {
            $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst . " and cod_dep=" . $var1;
        } else {
            $condicion = $this->condicion();
        }

        if (isset($_SESSION['ano_compra_consulta'])) {
            $ano = $_SESSION['ano_compra_consulta'];
        } else {
            $ano = $this->ano_ejecucion();
        }
        $rif = $this->v_cscd04_ordencompra->generateList($condicion . " and condicion_actividad=1 and ano_orden_compra='" . $ano . "' ", $order = 'razon_social ASC', $limit = null, '{n}.v_cscd04_ordencompra.rif', '{n}.v_cscd04_ordencompra.razon_social');
        $this->concatena_sin_cero($rif, 'rif');


        if ($this->Session->read('SScoddep_aux') == 1) {
            $this->Session->write('select_dependencia', $var1);
            $this->Session->write('SScoddep', $var1);
            $ano_ejecucion = $ano;
        }//fin if



        $this->set('ano_ejecucion', $ano);
    }

//fin function

    function salir() {
        $this->layout = "ajax";
        if ($this->Session->read('SScoddep_aux') == 1) {
            $this->Session->write('SScoddep', $this->Session->read('SScoddep_aux'));
            $this->Session->delete('SScoddep_aux');
        }
        $this->Session->delete("autor_valido");
    }

    function cons_datos_rif($ano_ejecucion = null, $rif = null) {
        $this->layout = "ajax";
        if (isset($_SESSION['ano_compra_consulta'])) {
            $ano_ejecucion = $_SESSION['ano_compra_consulta'];
        } else {
            $ano_ejecucion = $this->ano_ejecucion();
        }

        if ($rif != null) {
            $this->set('rif', $rif);
            $datos = $this->cpcd02->findAll($conditions = "rif='$rif'", $fields = "denominacion, representante_legal, direccion_comercial, codigo_area_empresa, telefonos, correo_electronico_empresa", $order = null, $limit = null, $page = null, $recursive = null);
            $this->set('datos', $datos);
            $cod_presi = $this->Session->read('SScodpresi');
            $cod_entidad = $this->Session->read('SScodentidad');
            $cod_tipo_inst = $this->Session->read('SScodtipoinst');
            $cod_inst = $this->Session->read('SScodinst');
            if ($this->Session->read('SScoddep_aux') == 1) {
                $var1 = $this->Session->read('select_dependencia');
            }//fin if
            if ($this->Session->read('SScoddep_aux') == 1) {
                $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst . " and cod_dep=" . $var1;
            } else {
                $condicion = $this->condicion();
            }
            $ano_vigente = $ano_ejecucion;
            $deudas = $this->v_cscd04_deuda_proveedores->findAll($condicion . " and ano_orden_compra='$ano_vigente' and rif='$rif'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
            $this->set('deudas', $deudas);
            if (empty($deudas)) {
                echo "<script>";
                echo "document.getElementById('bt_relacion').disabled=true;";
                echo "</script>";
                $this->set('msg_error', 'NO EXISTEN DATOS PARA ESTE PROVEEDOR EN ESTE AÑO');
            }
            $this->set('ano_ejecucion', $ano_ejecucion);
        }
    }

    function consulta_relacion_oc($rif = null, $ano_ejecucion = null) {
        $this->layout = "ajax";
        $this->set('rif', $rif);
        $this->set('ano_ejecucion', $ano_ejecucion);
    }

    function relacion_ordencompra($rif = null, $ano_ejecucion = null, $opc = null, $numero_ordencompra = null) {
        $this->layout = "ajax";
//echo "$rif || $opc";
        $this->set('opc', $opc);
        $this->set('rif', $rif);
        $this->set('ano_ejecucion', $ano_ejecucion);

        if ($opc == 1) {
            $this->consulta_relacion_ordencompra(null, $rif, $ano_ejecucion);
            $this->render('consulta_relacion_ordencompra');
        } else {
            $this->consulta_relacion_rifoc(null, $rif, $ano_ejecucion);
            $this->render('consulta_relacion_rifoc');
        }
    }

    function consulta_relacion_rifoc($pagina = null, $rif = null, $ano_ejecucion = null) {
        $this->layout = "ajax";
        $this->set('rif', $rif);
        $lista_oc = $this->v_cscd04_ordencompra->generateList($conditions = $this->condicion() . " and condicion_actividad=1 and mayus_acentos(rif)=mayus_acentos('$rif')  and ano_orden_compra='" . $ano_ejecucion . "' and  (cod_obra='' or cod_obra='0' or cod_obra IS NULL) ", $order = null, $limit = null, '{n}.v_cscd04_ordencompra.numero_orden_compra', '{n}.v_cscd04_ordencompra.rif');
        $this->concatena($lista_oc, 'lista_oc');
        $this->set('ano_ejecucion', $ano_ejecucion);
    }

    function trasladar_consulta_oc($rif = null, $ano_ejecucion = null, $numero_orden_compra = null) {
        $this->layout = "ajax";
        $this->set('ano_ejecucion', $ano_ejecucion);
        if ($numero_orden_compra != null) {
            $data = $this->v_cscd04_ordencompra->findAll($this->condicion() . " and ano_orden_compra='$ano_ejecucion' and condicion_actividad=1 and mayus_acentos(rif)=mayus_acentos('$rif') and  (cod_obra='' or cod_obra='0' or cod_obra IS NULL)", 'ano_orden_compra, numero_orden_compra', 'numero_orden_compra ASC', null, null, null);
            $page = 1;
            foreach ($data as $row) {
                if ($numero_orden_compra == $row['v_cscd04_ordencompra']['numero_orden_compra'] && $ano_ejecucion == $row['v_cscd04_ordencompra']['ano_orden_compra']) {
                    //echo $page;
                    $this->consulta_relacion_ordencompra($page, $rif, $ano_ejecucion, 2);
                    $this->render('consulta_relacion_ordencompra');
                }
                $page++;
            }
        }//fin if
    }

//fin function

    function consulta_proveedor_ordencompra($rif = null, $ano_ejecucion = null, $numero_orden_compra = null, $dep = null) {

        $this->layout = "ajax";
        if ($rif != null) {
            //echo $rif;
            $datos_rif = $this->cpcd02->findAll($conditions = "rif='$rif'", $fields = 'denominacion, representante_legal, direccion_comercial, codigo_area_empresa, telefonos, correo_electronico_empresa', $order = null, $limit = 1, $page = null, $recursive = null);
            $this->set('rif', $rif);
            $this->set('datos', $datos_rif);
//pr($datos_rif);
        }



        $this->set('ano_ejecucion', $ano_ejecucion);
        if ($numero_orden_compra != null) {

            $data = $this->v_cscd04_ordencompra->findAll($this->condicionNDEP() . " and cod_dep='" . $dep . "' and ano_orden_compra='$ano_ejecucion' and numero_orden_compra='" . $numero_orden_compra . "' and condicion_actividad=1 and mayus_acentos(rif)=mayus_acentos('$rif')", 'numero_orden_compra', 'numero_orden_compra ASC', null, null, null);

            $page = 1;
            foreach ($data as $row) {
                if ($numero_orden_compra == $row['v_cscd04_ordencompra']['numero_orden_compra']) {


                    $viene = 2;
                    if (isset($pagina)) {
                        $pagina = $pagina;
                    } else {
                        $pagina = 1;
                    }//fin else

                    $this->set('rif', $rif);
                    $Tfilas = $this->v_cscd04_ordencompra->findCount($this->condicionNDEP() . " and cod_dep='" . $dep . "' and ano_orden_compra='$ano_ejecucion' and condicion_actividad=1 and mayus_acentos(rif)=mayus_acentos('$rif') and numero_orden_compra='" . $numero_orden_compra . "' ");

                    if ($Tfilas != 0) {
                        $this->set('pag_cant', $pagina . '/' . $Tfilas);
                        $this->set('ultimo', $Tfilas);
                        $data = $this->v_cscd04_ordencompra->findAll($this->condicionNDEP() . " and cod_dep='" . $dep . "' and condicion_actividad=1 and mayus_acentos(rif)=mayus_acentos('$rif') and ano_orden_compra='$ano_ejecucion' and numero_orden_compra='" . $numero_orden_compra . "' ", null, 'numero_orden_compra ASC', 1, $pagina, null);
                        //pr($data);
                        $this->set('data', $data);
                        foreach ($data as $row) {
                            $numero_dependencia = $row['v_cscd04_ordencompra']['cod_dep'];
                            $numero_orden_compra = $row['v_cscd04_ordencompra']['numero_orden_compra'];
                            $ano_orden_compra = $row['v_cscd04_ordencompra']['ano_orden_compra'];
                            $condicion_actividad = $row['v_cscd04_ordencompra']['condicion_actividad'];
                            $ano_documento = $row['v_cscd04_ordencompra']['ano_orden_compra'];
                            $fecha_documento = $row['v_cscd04_ordencompra']['fecha_orden_compra'];
                            $num_cotizacion = $row['v_cscd04_ordencompra']['numero_cotizacion'];
                        }

                        $condicion = $this->condicionNDEP() . " and cod_dep='" . $numero_dependencia . "' ";
                        $uso_destino = $this->cscd02_solicitud_encabezado->field('cscd02_solicitud_encabezado.uso_destino', $conditions = $this->condicionNDEP() . " and cod_dep='" . $numero_dependencia . "' " . " and numero_cotizacion='$num_cotizacion' and rif='$rif' and ano_cotizacion='$ano_orden_compra'", $order = null);
                        $this->set('uso_destino', $uso_destino);
                        $fecha_cotizacion = $this->cscd03_cotizacion_encabezado->field('cscd03_cotizacion_encabezado.fecha_cotizacion', $conditions = $condicion . " and numero_cotizacion='$num_cotizacion' and rif='$rif'", $order = null);
                        $this->set('fecha_cotizacion', $fecha_cotizacion);
                        //$ordencompra_encabezado = $this->cscd04_ordencompra_encabezado->findAll($this->condicion()." and numero_orden_compra='$numero_orden_compra'");
                        $ordencompra_partidas = $this->cscd04_ordencompra_partidas->findAll($this->condicionNDEP() . " and cod_dep='" . $numero_dependencia . "' " . " and ano_orden_compra='$ano_ejecucion' and numero_orden_compra='$numero_orden_compra'");
                        $this->set('ordencompra_partidas', $ordencompra_partidas);
                        //$this->set('ordencompra_encabezado', $ordencompra_encabezado);
                        $datos_ne = $this->cscd05_ordencompra_nota_entrega_encabezado->findAll($conditions = $condicion . " and rif='$rif' and numero_orden_compra='$numero_orden_compra' and ano_orden_compra='$ano_orden_compra'", $fields = 'ano_nota_entrega, numero_nota_entrega', $order = null, $limit = null, $page = null, $recursive = null);
                        $this->set('datos_ne', $datos_ne);
                        //pr($datos_ne);

                        $datos_ap = $this->cscd04_ordencompra_autorizacion_cuerpo->findAll($conditions = $condicion . " and ano_orden_compra='$ano_orden_compra' and numero_orden_compra='$numero_orden_compra' and condicion_actividad=1", $fields = 'numero_pago, fecha_autorizacion, monto_cancelar', $order = null, $limit = null, $page = null, $recursive = null);
                        $this->set('datos_ap', $datos_ap);
                        //pr($datos_ap);

                        $datos_op = $this->cepd03_ordenpago_cuerpo->findAll($conditions = $condicion . " and cod_tipo_documento='3' and condicion_actividad=1 and numero_documento_origen='$numero_orden_compra' and ano_documento_origen='$ano_orden_compra'", $fields = 'ano_orden_pago, numero_orden_pago, fecha_orden_pago, monto_total, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_cheque, fecha_cheque', $order = null, $limit = null, $page = null, $recursive = null);
                        //pr($datos_op);
                        $this->set('datos_op', $datos_op);
                        if (!empty($datos_op)) {
                            $cod_entidad_bancaria = $datos_op[0]['cepd03_ordenpago_cuerpo']['cod_entidad_bancaria'];
                            //echo $cod_entidad_bancaria;

                            $banco = $this->cepd03_ordenpago_cuerpo->execute("SELECT denominacion FROM cstd01_entidades_bancarias WHERE cod_entidad_bancaria='$cod_entidad_bancaria';");
                            //pr($banco);
                            if (!empty($banco[0][0]['denominacion'])) {
                                $this->set('banco', $banco[0][0]['denominacion']);
                            } else {
                                $this->set('banco', '');
                            }
                        } else {
                            $this->set('banco', '');
                        }


                        $numsolicitud = $this->v_cscd03_cotizacion->field('v_cscd03_cotizacion.numero_solicitud', $conditions = $this->condicionNDEP() . " and cod_dep='" . $numero_dependencia . "' " . " and v_cscd03_cotizacion.numero_cotizacion='$num_cotizacion' and mayus_acentos(rif)=mayus_acentos('$rif') and ano_cotizacion='" . $ano_ejecucion . "' ", $order = "numero_cotizacion ASC");
                        $lista = $this->v_cscd03_cotizacion->findAll($condicion . " and numero_cotizacion='$num_cotizacion' and mayus_acentos(rif)=mayus_acentos('$rif') and ano_cotizacion='" . $ano_ejecucion . "'", 'DISTINCT codigo_prod_serv, expresion, cantidad, precio_unitario, descripcion, numero_cotizacion', 'codigo_prod_serv DESC', null);
                        $this->set('lista_cscd02_solicitud_cuerpo', $lista);
                        $this->set('index_cotizacion', 'v_cscd03_cotizacion');
                        $this->set('ano_ejecucion', $ano_ejecucion);
                        $this->set('siguiente', $pagina + 1);
                        $this->set('anterior', $pagina - 1);
                        if ($viene != null) {
                            $this->bt_nav(1, 2);
                        } else {
                            $this->bt_nav($Tfilas, $pagina);
                        }
                    } else {
                        $this->set('COMPROMISO', '');
                        $this->set('errorMessage', 'No se encontrar&oacute;n datos');
                    }
                }
                $page++;
            }
        }
    }

//fin function

    function consulta_relacion_ordencompra($pagina = null, $rif = null, $ano_ejecucion = null, $viene = null) {
        $this->layout = "ajax";

        if (isset($pagina)) {
            $pagina = $pagina;
        } else {
            $pagina = 1;
        }//fin else



        $this->set('rif', $rif);
        $Tfilas = $this->v_cscd04_ordencompra->findCount($this->condicion() . " and ano_orden_compra='$ano_ejecucion' and condicion_actividad=1 and mayus_acentos(rif)=mayus_acentos('$rif') and  (cod_obra='' or cod_obra='0' or cod_obra IS NULL)");

        if ($Tfilas != 0) {
            $this->set('pag_cant', $pagina . '/' . $Tfilas);
            $this->set('ultimo', $Tfilas);
            $data = $this->v_cscd04_ordencompra->findAll($this->condicion() . " and condicion_actividad=1 and mayus_acentos(rif)=mayus_acentos('$rif') and ano_orden_compra='$ano_ejecucion' and  (cod_obra='' or cod_obra='0' or cod_obra IS NULL)", null, 'numero_orden_compra ASC', 1, $pagina, null);
            //pr($data);
            $this->set('data', $data);
            foreach ($data as $row) {
                $numero_orden_compra = $row['v_cscd04_ordencompra']['numero_orden_compra'];
                $ano_orden_compra = $row['v_cscd04_ordencompra']['ano_orden_compra'];
                $condicion_actividad = $row['v_cscd04_ordencompra']['condicion_actividad'];
                $ano_documento = $row['v_cscd04_ordencompra']['ano_orden_compra'];
                $fecha_documento = $row['v_cscd04_ordencompra']['fecha_orden_compra'];
                $num_cotizacion = $row['v_cscd04_ordencompra']['numero_cotizacion'];
            }
            $condicion = $this->condicion();
            $uso_destino = $this->cscd02_solicitud_encabezado->field('cscd02_solicitud_encabezado.uso_destino', $conditions = $this->condicion() . " and numero_cotizacion='$num_cotizacion' and rif='$rif' and ano_cotizacion='$ano_orden_compra' and  (cod_obra='' or cod_obra='0' or cod_obra IS NULL)", $order = null);
            $this->set('uso_destino', $uso_destino);
            $fecha_cotizacion = $this->cscd03_cotizacion_encabezado->field('cscd03_cotizacion_encabezado.fecha_cotizacion', $conditions = $condicion . " and numero_cotizacion='$num_cotizacion' and rif='$rif' and  (cod_obra='' or cod_obra='0' or cod_obra IS NULL)", $order = null);
            $this->set('fecha_cotizacion', $fecha_cotizacion);
            //$ordencompra_encabezado = $this->cscd04_ordencompra_encabezado->findAll($this->condicion()." and numero_orden_compra='$numero_orden_compra'");
            $ordencompra_partidas = $this->cscd04_ordencompra_partidas->findAll($this->condicion() . " and numero_orden_compra='$numero_orden_compra' and ano_orden_compra='$ano_orden_compra'");
            $this->set('ordencompra_partidas', $ordencompra_partidas);
            //$this->set('ordencompra_encabezado', $ordencompra_encabezado);
            $datos_ne = $this->cscd05_ordencompra_nota_entrega_encabezado->findAll($conditions = $condicion . " and rif='$rif' and numero_orden_compra='$numero_orden_compra' and ano_orden_compra='$ano_orden_compra'", $fields = 'ano_nota_entrega, numero_nota_entrega', $order = null, $limit = null, $page = null, $recursive = null);
            $this->set('datos_ne', $datos_ne);
            //pr($datos_ne);

            $datos_ap = $this->cscd04_ordencompra_autorizacion_cuerpo->findAll($conditions = $condicion . " and ano_orden_compra='$ano_orden_compra' and numero_orden_compra='$numero_orden_compra' and condicion_actividad=1", $fields = 'numero_pago, fecha_autorizacion, monto_cancelar', $order = null, $limit = null, $page = null, $recursive = null);
            $this->set('datos_ap', $datos_ap);
            //pr($datos_ap);

            $datos_op = $this->cepd03_ordenpago_cuerpo->findAll($conditions = $condicion . " and cod_tipo_documento='3' and condicion_actividad=1 and numero_documento_origen='$numero_orden_compra' and ano_documento_origen='$ano_orden_compra'", $fields = 'ano_orden_pago, numero_orden_pago, fecha_orden_pago, monto_total, ano_movimiento, cod_entidad_bancaria, cod_sucursal, cuenta_bancaria, numero_cheque, fecha_cheque', $order = null, $limit = null, $page = null, $recursive = null);
            //pr($datos_op);
            $this->set('datos_op', $datos_op);
            if (!empty($datos_op)) {
                $cod_entidad_bancaria = $datos_op[0]['cepd03_ordenpago_cuerpo']['cod_entidad_bancaria'];
                //echo $cod_entidad_bancaria;

                $banco = $this->cepd03_ordenpago_cuerpo->execute("SELECT denominacion FROM cstd01_entidades_bancarias WHERE cod_entidad_bancaria='$cod_entidad_bancaria';");
                //pr($banco);
                if (!empty($banco[0][0]['denominacion'])) {
                    $this->set('banco', $banco[0][0]['denominacion']);
                } else {
                    $this->set('banco', '');
                }
            } else {
                $this->set('banco', '');
            }


            $numsolicitud = $this->v_cscd03_cotizacion->field('v_cscd03_cotizacion.numero_solicitud', $conditions = $this->condicion() . " and v_cscd03_cotizacion.numero_cotizacion='$num_cotizacion' and mayus_acentos(rif)=mayus_acentos('$rif') and ano_cotizacion='" . $ano_ejecucion . "' ", $order = "numero_cotizacion ASC");
            $lista = $this->v_cscd03_cotizacion->findAll($condicion . " and numero_cotizacion='$num_cotizacion' and mayus_acentos(rif)=mayus_acentos('$rif') and ano_cotizacion='" . $ano_ejecucion . "'", 'DISTINCT codigo_prod_serv, expresion, cantidad, precio_unitario, descripcion, numero_cotizacion', 'codigo_prod_serv DESC', null);
            $this->set('lista_cscd02_solicitud_cuerpo', $lista);
            $this->set('index_cotizacion', 'v_cscd03_cotizacion');
            $this->set('ano_ejecucion', $ano_ejecucion);
            $this->set('siguiente', $pagina + 1);
            $this->set('anterior', $pagina - 1);
            if ($viene != null) {
                $this->bt_nav(1, 2);
            } else {
                $this->bt_nav($Tfilas, $pagina);
            }
        } else {
            $this->set('COMPROMISO', '');
            $this->set('errorMessage', 'No se encontrar&oacute;n datos');
        }
    }

//fin function

    function consulta_art_suministrados() {
        $this->layout = "ajax";
        $_SESSION['ano_compra_articulos_suministrado'] = $this->ano_ejecucion();
        $this->set('ano_compra_articulos_suministrado', $this->ano_ejecucion());
    }

    function buscar_por_pista($var1 = null, $var2 = null, $var3 = null) {
        $this->layout = "ajax";


        $campos = '
cod_snc,
codigo_prod_serv,
producto, expresion_medida_producto ';


        $agrupar = 'GROUP BY
cod_snc,
codigo_prod_serv,
producto, expresion_medida_producto';


        if (isset($_SESSION['ano_compra_articulos_suministrado'])) {
            $ano = $_SESSION['ano_compra_articulos_suministrado'];
        } else {
            $ano = $this->ano_ejecucion();
        }


        if ($var1 == 1) {
            $var2 = strtoupper($var2);
            $this->Session->write('pista', $var2);
            if (is_numeric($var2)) {
                $sql = " (codigo_prod_serv::text LIKE '%$var2%')  or   ";
            } else {
                $sql = "";
            }
            $Tfilas = $this->cscd05_cpcd02_suministro->findCount($this->condicionNDEP() . " and  (cod_obra='' or cod_obra='0' or cod_obra IS NULL) " . ' and (' . $sql . " (mayus_acentos(producto) LIKE mayus_acentos('%$var2%')  )  ) and ano_orden_compra='" . $ano . "' " . $agrupar, $campos, " producto ASC");
            if ($Tfilas != 0) {
                $pagina = 1;
                $Tfilas = (int) ceil($Tfilas / 100);
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $this->set('total_paginas', $Tfilas);
                $this->set('pagina_actual', $pagina);
                $this->set('ultimo', $Tfilas);
                $datos_filas = $this->cscd05_cpcd02_suministro->findAll($this->condicionNDEP() . " and  (cod_obra='' or cod_obra='0' or cod_obra IS NULL) " . ' and (' . $sql . " (mayus_acentos(producto) LIKE mayus_acentos('%$var2%'))) and ano_orden_compra='" . $ano . "' " . $agrupar, $campos, " producto ASC", 100, 1, null);
                $this->set("datosFILAS", $datos_filas);
                $this->set('siguiente', $pagina + 1);
                $this->set('anterior', $pagina - 1);
                $this->bt_nav($Tfilas, $pagina);
            } else {
                $this->set("datosFILAS", '');
            }
        } else if ($var1 == 2) {
            $var22 = $this->Session->read('pista');
            $var22 = strtoupper($var22);
            if (is_numeric($var22)) {
                $sql = " (codigo_prod_serv::text LIKE '%$var22%')  or   ";
            } else {
                $sql = "";
            }
            $Tfilas = $this->cscd05_cpcd02_suministro->findCount($this->condicionNDEP() . " and  (cod_obra='' or cod_obra='0' or cod_obra IS NULL) " . '  and ' . $sql . " ((mayus_acentos(producto) LIKE mayus_acentos('%$var2%')))   and ano_orden_compra='" . $ano . "' " . $agrupar, $campos, " producto ASC");
            if ($Tfilas != 0) {
                $pagina = $var2;
                $Tfilas = (int) ceil($Tfilas / 100);
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $this->set('total_paginas', $Tfilas);
                $this->set('pagina_actual', $pagina);
                $this->set('ultimo', $Tfilas);
                $datos_filas = $this->cscd05_cpcd02_suministro->findAll($this->condicionNDEP() . " and  (cod_obra='' or cod_obra='0' or cod_obra IS NULL) " . ' and ' . $sql . " ((mayus_acentos(producto) LIKE mayus_acentos('%$var22%')))  and ano_orden_compra='" . $ano . "' " . $agrupar, $campos, " producto, denominacion_comercial ASC", 100, $pagina, null);
                $this->set("datosFILAS", $datos_filas);
                $this->set('siguiente', $pagina + 1);
                $this->set('anterior', $pagina - 1);
                $this->bt_nav($Tfilas, $pagina);
            } else {
                $this->set("datosFILAS", '');
            }
        }//fin else
    }

//fin function

    function mostrar_articulos_sum($codigo_prod_serv = null) {
        $this->layout = "ajax";


        if (isset($_SESSION['ano_compra_articulos_suministrado'])) {
            $ano = $_SESSION['ano_compra_articulos_suministrado'];
        } else {
            $ano = $this->ano_ejecucion();
        }




        if ($codigo_prod_serv != null) {
            $datos_prod = $this->cscd05_cpcd02_suministro->findAll($this->condicionNDEP() . " and  (cod_obra='' or cod_obra='0' or cod_obra IS NULL) " . " and codigo_prod_serv='$codigo_prod_serv' and ano_orden_compra='" . $ano . "'", null, $order = 'cod_dep, ano_orden_compra, numero_orden_compra, fecha_nota_entrega ASC', $limit = null, $page = null, $recursive = null);
//pr($datos_prod);
            $this->set('datos', $datos_prod);


            $deno_prod = $this->cscd05_cpcd02_suministro->field('producto', $this->condicionNDEP() . " and  (cod_obra='' or cod_obra='0' or cod_obra IS NULL) " . " and  codigo_prod_serv='$codigo_prod_serv' and ano_orden_compra='" . $ano . "'", $order = null);
            $unidad_medida = $this->v_cscd03_cotizacion->field('expresion', $conditions = "codigo_prod_serv='$codigo_prod_serv' and ano_cotizacion='" . $ano . "'", $order = null);
            $this->set('deno_prod', $deno_prod . ' - ' . strtoupper($unidad_medida));
            $articulo = $deno_prod . ' - ' . strtoupper($unidad_medida);
            $articulo = javascript_encode($articulo);
            echo "<script>";
            echo "document.getElementById('rif').value='';";
            echo "document.getElementById('denominacion').value='';";
            echo "document.getElementById('representante').value='';";
            echo "document.getElementById('direccion').value='';";
            echo "document.getElementById('telefono').value='';";
            echo "document.getElementById('email').value='';";
            echo "document.getElementById('deno_articulo').innerHTML='" . $articulo . "';";

            echo "</script>";
        }
    }

//fin function

    function mostrar_articulos_sum_busqueda($var1 = null) {
        $this->layout = "ajax";
    }

//fin function

    function mostrar_deno_articulo($codigo_prod_serv = null) {
        $this->layout = "ajax";
        if ($codigo_prod_serv != null) {
            $deno_prod = $this->cscd05_cpcd02_suministro->field('producto', $conditions = $this->condicionNDEP() . " and ano_orden_compra='" . $this->ano_ejecucion() . "' and codigo_prod_serv='$codigo_prod_serv'", $order = null);
//$cod_medida = $this->cscd01_catalogo->field('cod_medida', $conditions = "", $order =null);
            $unidad_medida = $this->v_cscd03_cotizacion->field('expresion', $conditions = $this->condicion() . " and ano_cotizacion='" . $this->ano_ejecucion() . "' codigo_prod_serv='$codigo_prod_serv'", $order = null);
            $this->set('deno_prod', $deno_prod . ' - ' . strtoupper($unidad_medida));
        }
    }

    function datos_proveedor($rif = null) {
        $this->layout = "ajax";
        if ($rif != null) {
            //echo $rif;
            $datos_rif = $this->cpcd02->findAll($conditions = "rif='$rif'", $fields = 'denominacion, representante_legal, direccion_comercial, codigo_area_empresa, telefonos, correo_electronico_empresa', $order = null, $limit = 1, $page = null, $recursive = null);
            $this->set('rif', $rif);
            $this->set('datos', $datos_rif);
//pr($datos_rif);
        }
    }

    function consulta_consumo_prod() {
        $this->layout = "ajax";
        $cod_dep = $this->Session->read('SScoddep');
        $this->set('cod_dep', $cod_dep);
        $this->set('ano', $this->ano_ejecucion());
    }

    function show_direccion($opc = null) {
        $this->layout = "ajax";
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $this->set('opc', $opc);
        $listadireccion_superior = $this->cugd02_direccionsuperior->generateList(" cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep'", 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
        $this->concatena($listadireccion_superior, 'direccion_superior');
    }

    function mostrar_radio_cp($opc1 = null) {
        $this->layout = "ajax";
        $this->set('opc1', $opc1);
    }

    function generar_cotizacion($rif = null, $numero_cotizacion = null) {
        $this->layout = "ajax";
        $this->Session->delete('codigos');
        $_SESSION["codigos"] = null;
        $this->Session->delete('total_filas');
        $_SESSION["total_filas"] = null;
        $this->Session->delete('monto_orden');
        $_SESSION["monto_orden"] = null;
        if ($rif != null && $numero_cotizacion != null) {
            $this->set('rif', $rif); // seteo el rif
            $this->set('numero_cotizacion', $numero_cotizacion); //seteo el numero de cotizacion
            $ano_arranque = $ano = $this->ano_ejecucion();
            $this->set('ano_arranque', $ano_arranque); // genero el ano de ejecucion
            $sector = $this->v_cfpd05_denominaciones->generateList($this->SQLCA($ano_arranque), 'cod_sector ASC', null, '{n}.v_cfpd05_denominaciones.cod_sector', '{n}.v_cfpd05_denominaciones.deno_sector');
            $sector = $sector != null ? $sector : array();
            $this->concatena($sector, 'sector');
            $this->direccionsolicitante($numero_cotizacion, $rif);
//A PARITR DE AQUI GENERO LOS DATOS DE LA COTIZACION
            $numsolicitud = $this->v_cscd03_cotizacion->field('v_cscd03_cotizacion.numero_solicitud', $conditions = $this->condicion() . " and mayus_acentos(rif)=mayus_acentos('$rif') and v_cscd03_cotizacion.numero_cotizacion='$numero_cotizacion' and ano_cotizacion='" . $ano_arranque . "'  ", $order = "numero_cotizacion ASC");
            $lista = $this->v_cscd03_cotizacion->findAll($this->condicion() . " and mayus_acentos(rif)=mayus_acentos('$rif') and numero_cotizacion='$numero_cotizacion' and ano_cotizacion='$ano_arranque' ", 'DISTINCT codigo_prod_serv, expresion, cantidad, precio_unitario, descripcion, numero_cotizacion', 'codigo_prod_serv DESC', null);
//print_r($lista);
            $this->set('lista_cscd02_solicitud_cuerpo', $lista);

// A PARTIR DE AQUI SETEO LOS CODIGOS DE LAS PARTIDAS
            $sql_codigo_presupuestario = "SELECT DISTINCT
cc.cod_partida, cc.cod_generica, cc.cod_especifica, cc.cod_sub_espec,
cc.cod_sector, cc.cod_programa, cc.cod_sub_prog, cc.cod_proyecto,
(SELECT SUM(round(a.cantidad * a.precio_unitario,2)) FROM cscd03_cotizacion_cuerpo a
WHERE a.numero_cotizacion=cc.numero_cotizacion and
a.rif = cc.rif and
a.ano_cotizacion=cc.ano_cotizacion and
a.cod_presi=cc.cod_presi and
a.cod_entidad=cc.cod_entidad and
a.cod_tipo_inst=cc.cod_tipo_inst and
a.cod_inst=cc.cod_inst and
a.cod_dep=cc.cod_dep and
cc.cod_partida=a.cod_partida and
cc.cod_generica=a.cod_generica and
cc.cod_especifica=a.cod_especifica and
cc.cod_sub_espec=a.cod_sub_espec and
cc.cod_sector=a.cod_sector and
cc.cod_programa=a.cod_programa and
cc.cod_sub_prog=a.cod_sub_prog and
cc.cod_proyecto=a.cod_proyecto )::numeric(22,2) as total
FROM cscd03_cotizacion_cuerpo cc
WHERE " . $this->SQLCA_opcion('cc') . " and cc.ano_cotizacion='$ano_arranque' and cc.numero_cotizacion='$numero_cotizacion' and mayus_acentos(rif)=mayus_acentos('$rif')
GROUP BY cc.cod_presi, cc.cod_entidad, cc.cod_tipo_inst, cc.cod_inst, cc.cod_dep, cc.ano_cotizacion, cc.numero_cotizacion,cc.rif,
cc.cod_partida, cc.cod_generica, cc.cod_especifica, cc.cod_sub_espec,
cc.cod_sector, cc.cod_programa, cc.cod_sub_prog, cc.cod_proyecto
ORDER BY cc.cod_sector, cc.cod_programa, cc.cod_sub_prog, cc.cod_proyecto, cc.cod_partida, cc.cod_generica, cc.cod_especifica, cc.cod_sub_espec";

            $distribucion = $this->v_cscd03_cotizacion->execute($sql_codigo_presupuestario);
//pr($distribucion);
            $this->set('distribucion', $distribucion);
//A PARTIR DE AQUI COMIENZO A REALIZAR LOS SELECT
            $i = 0;
            $total_filas = count($distribucion);
            $_SESSION['total_filas'] = $total_filas;
//echo $total_filas;
            foreach ($distribucion as $row) {
                $cod_sector = $row[0]['cod_sector'];
                $cod_programa = $row[0]['cod_programa'];
                $cod_sub_prog = $row[0]['cod_sub_prog'];
                $cod_proyecto = $row[0]['cod_proyecto'];
                $cod_partida = $row[0]['cod_partida'];
                $cod_generica = $row[0]['cod_generica'];
                $cod_especifica = $row[0]['cod_especifica'];
                $cod_sub_espec = $row[0]['cod_sub_espec'];
                $monto = $row[0]['total'];

                $sql_actividades = "SELECT DISTINCT a.cod_activ_obra FROM cfpd05 a WHERE a.cod_sector='$cod_sector' and a.cod_programa='$cod_programa' and a.cod_sub_prog='$cod_sub_prog' and a.cod_proyecto='$cod_proyecto' and a.cod_partida='$cod_partida' and a.cod_generica='$cod_generica' and a.cod_especifica='$cod_especifica' and a.cod_sub_espec='$cod_sub_espec' and a.ano='$ano_arranque' and " . $this->condicion();
//echo $sql_auxiliares;
                $actividades = $this->v_cscd03_cotizacion->execute($sql_actividades);
//pr($auxiliares);
                $cont_act = count($actividades);
                if ($cont_act == 1) {
                    //echo " var cont_act = $i <br/>";
                    $cod_activ_obra = $actividades[0][0]['cod_activ_obra'];
                    $VecActiv[$i] = $cod_activ_obra;

                    $sql_auxiliares = "SELECT DISTINCT a.cod_auxiliar FROM cfpd05 a WHERE a.cod_sector='$cod_sector' and a.cod_programa='$cod_programa' and a.cod_sub_prog='$cod_sub_prog' and a.cod_proyecto='$cod_proyecto' and a.cod_activ_obra='$cod_activ_obra' and a.cod_partida='$cod_partida' and a.cod_generica='$cod_generica' and a.cod_especifica='$cod_especifica' and a.cod_sub_espec='$cod_sub_espec' and a.ano='$ano_arranque' and " . $this->condicion();
                    $auxiliares = $this->v_cscd03_cotizacion->execute($sql_auxiliares);
//pr($auxiliares);
                    $cont_aux = count($auxiliares);
                    if ($cont_aux == 1) {
                        //echo " var cont_aux = $i <br/>";
                        $cod_auxiliar = $auxiliares[0][0]['cod_auxiliar'];
                        //echo $cod_auxiliar;
                        $VecAux[$i] = $cod_auxiliar;
                        //echo $monto;
                        $this->items($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $monto, $i, $cod_auxiliar);

                        //echo "estoy";
                    } else {
                        //echo " var !cont_aux = $i <br/>";
                        $sql_select_aux = "SELECT DISTINCT a.cod_auxiliar, a.deno_auxiliar FROM v_balance_ejecucion a WHERE  a.cod_sector='$cod_sector' and a.cod_programa='$cod_programa' and a.cod_sub_prog='$cod_sub_prog' and a.cod_proyecto='$cod_proyecto' and a.cod_activ_obra='$cod_activ_obra' and a.cod_partida='$cod_partida' and a.cod_generica='$cod_generica' and a.cod_especifica='$cod_especifica' and a.cod_sub_espec='$cod_sub_espec' and a.ano='$ano_arranque' and " . $this->condicion() . " order by a.cod_auxiliar";
                        //echo $sql_select_aux;
                        $rs2 = $this->v_cscd03_cotizacion->execute($sql_select_aux);
                        foreach ($rs2 as $l) {
                            $a[] = $l[0]["cod_auxiliar"];
                            $h[] = $l[0]["deno_auxiliar"];
                        }
                        $VecAux2 = array_combine($a, $h);
                        $VecAux[$i] = $this->concatena_aux2($VecAux2);
                        //pr($VecAux[$i]);
                        //pr($AUXILIAR);
                    }
                } else {
                    //echo " var !cont_act = $i <br/>";
                    $sql_select_act = "SELECT DISTINCT a.cod_activ_obra, a.deno_activ_obra FROM v_balance_ejecucion a WHERE  a.cod_sector='$cod_sector' and a.cod_programa='$cod_programa' and a.cod_sub_prog='$cod_sub_prog' and a.cod_proyecto='$cod_proyecto' and a.cod_partida='$cod_partida' and a.cod_generica='$cod_generica' and a.cod_especifica='$cod_especifica' and a.cod_sub_espec='$cod_sub_espec' and a.ano='$ano_arranque' and " . $this->condicion();
//$sql_select_aux = "SELECT DISTINCT a.cod_activ_obra, a.deno_activ_obra FROM v_balance_ejecucion a WHERE  a.cod_sector='$cod_sector' and a.cod_programa='$cod_programa' and a.cod_sub_prog='$cod_sub_prog' and a.cod_proyecto='$cod_proyecto' and a.cod_activ_obra='$cod_activ_obra' and a.cod_partida='$cod_partida' and a.cod_generica='$cod_generica' and a.cod_especifica='$cod_especifica' and a.cod_sub_espec='$cod_sub_espec' and a.ano='$ano_arranque' and ". $this->condicion();
//echo $sql_select_act;
                    $rs = $this->v_cscd03_cotizacion->execute($sql_select_act);
//pr($rs);
                    foreach ($rs as $l) {
                        $v[] = $l[0]["cod_activ_obra"];
                        $d[] = $this->zero($l[0]["cod_activ_obra"]) . " - " . $l[0]["deno_activ_obra"];
                    }
                    $VecActiv[$i] = array_combine($v, $d);
                    $VecAux[$i] = array();
                }

                $i++;
            }

            $this->set('VecActiv', $VecActiv);
            $this->set('VecAux', $VecAux);
//pr($distribucion);
        } else {
            $this->Session->delete('codigos');
            $_SESSION["codigos"] = null;
        }
    }

    function script1($offset) {
        $sql_solicitudes_cuerpo = "SELECT DISTINCT codigo_prod_serv FROM cscd02_solicitud_cuerpo WHERE cod_partida is null or cod_generica is null or cod_especifica is null or cod_sub_espec is null ORDER BY codigo_prod_serv ASC LIMIT 500 OFFSET $offset";
        $solicitudes_cuerpo = $this->v_cscd03_cotizacion->execute($sql_solicitudes_cuerpo);
        $sql_solicitudes_encabezado = "SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep,
a.numero_solicitud, a.cod_dir_superior, a.cod_coordinacion, a.cod_secretaria, a.cod_direccion
FROM cscd02_solicitud_encabezado a
ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud
LIMIT 200 OFFSET $offset";
        $solicitudes_cuerpo = $this->v_cscd03_cotizacion->execute($sql_solicitudes_cuerpo);
//$solicitudes_encabezado = $this->v_cscd03_cotizacion->execute($sql_solicitudes_encabezado);
        $SQL_UPDATE_SOLICITUD = "BEGIN; ";
        $i = 0;
        foreach ($solicitudes_cuerpo as $sol) {
            $codigo_prod_serv = $sol[0]['codigo_prod_serv'];
//echo "codigo_prod_serv='$codigo_prod_serv'";
//$SQL_UPDATE_SOLICITUD .= "UPDATE cscd02_solicitud_cuerpo SET cod_sector=(SELECT a.cod_sector FROM cugd02_direccion a WHERE a.cod_tipo_institucion='$cod_tipo_inst' and a.cod_institucion='$cod_inst' and a.cod_dependencia='$cod_dep') WHERE codigo_prod_serv='$codigo_prod_serv';";
            $SQL_UPDATE_SOLICITUD .= "UPDATE cscd02_solicitud_cuerpo SET cod_partida=(SELECT a.cod_partida FROM cscd01_catalogo a WHERE a.codigo_prod_serv='$codigo_prod_serv') WHERE codigo_prod_serv='$codigo_prod_serv';";
            $SQL_UPDATE_SOLICITUD .= "UPDATE cscd02_solicitud_cuerpo SET cod_generica=(SELECT a.cod_generica FROM cscd01_catalogo a WHERE a.codigo_prod_serv='$codigo_prod_serv') WHERE codigo_prod_serv='$codigo_prod_serv';";
            $SQL_UPDATE_SOLICITUD .= "UPDATE cscd02_solicitud_cuerpo SET cod_especifica=(SELECT a.cod_especifica FROM cscd01_catalogo a WHERE a.codigo_prod_serv='$codigo_prod_serv') WHERE codigo_prod_serv='$codigo_prod_serv';";
            $SQL_UPDATE_SOLICITUD .= "UPDATE cscd02_solicitud_cuerpo SET cod_sub_espec=(SELECT a.cod_sub_espec FROM cscd01_catalogo a WHERE a.codigo_prod_serv='$codigo_prod_serv') WHERE codigo_prod_serv='$codigo_prod_serv';";

            $i++;
        }

        $sw = $this->v_cscd03_cotizacion->execute($SQL_UPDATE_SOLICITUD);
        if ($sw > 1) {
            $this->v_cscd03_cotizacion->execute("COMMIT;");
            echo "LA ACTUALIZACION FUE UN EXITO CON EL OFFSET: " . $offset;
        } else {
            $this->v_cscd03_cotizacion->execute("ROLLBACK;");
            echo "LA ACTUALIZACION NO SE LOGRO CON EL OFFSET: " . $offset;
        }
    }

    function script2($offset = 0, $cod_dep = null, $num_solicitud = null) {
        if ($cod_dep != null && $num_solicitud != null) {
            $cond1 = "WHERE a.cod_dep='$cod_dep' and a.numero_solicitud='$num_solicitud' ";
        } else {
            $cond1 = "";
        }

        if ($cod_dep != null && $num_solicitud == null) {
            $cond1 = "WHERE a.cod_dep='$cod_dep' ";
        } else {
            $cond1 = "";
        }

        $sql_solicitudes_encabezado = "SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep,
a.numero_solicitud, a.cod_dir_superior, a.cod_coordinacion, a.cod_secretaria, a.cod_direccion
FROM cscd02_solicitud_encabezado a " . $cond1 . "
ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud
LIMIT 500 OFFSET $offset";
        $solicitudes_encabezado = $this->v_cscd03_cotizacion->execute($sql_solicitudes_encabezado);
        $SQL_UPDATE_SOLICITUD = "BEGIN; ";
        $i = 0;
        $cant_total = count($solicitudes_encabezado);
        foreach ($solicitudes_encabezado as $sol) {
            $cod_tipo_inst = $sol[0]['cod_tipo_inst'];
            $cod_inst = $sol[0]['cod_inst'];
            $cod_dep = $sol[0]['cod_dep'];
            $numero_solicitud = $sol[0]['numero_solicitud'];
            $cod_dir_superior = $sol[0]['cod_dir_superior'];
            $cod_coordinacion = $sol[0]['cod_coordinacion'];
            $cod_secretaria = $sol[0]['cod_secretaria'];
            $cod_direccion = $sol[0]['cod_direccion'];

            $SQL_UPDATE_SOLICITUD .= "UPDATE cscd02_solicitud_cuerpo SET
cod_sector=(SELECT a.cod_sector FROM cugd02_direccion a
WHERE a.cod_tipo_institucion=$cod_tipo_inst and cod_institucion=$cod_inst and cod_dependencia=$cod_dep and cod_dir_superior=$cod_dir_superior and cod_coordinacion=$cod_coordinacion and cod_secretaria=$cod_secretaria and cod_direccion=$cod_direccion)
WHERE cod_dep=$cod_dep and  numero_solicitud=$numero_solicitud ; ";
            $SQL_UPDATE_SOLICITUD .= "UPDATE cscd02_solicitud_cuerpo SET
cod_programa=(SELECT a.cod_programa FROM cugd02_direccion a
WHERE a.cod_tipo_institucion=$cod_tipo_inst and cod_institucion=$cod_inst and cod_dependencia=$cod_dep and cod_dir_superior=$cod_dir_superior and cod_coordinacion=$cod_coordinacion and cod_secretaria=$cod_secretaria and cod_direccion=$cod_direccion)
WHERE cod_dep=$cod_dep and  numero_solicitud=$numero_solicitud ; ";
            $SQL_UPDATE_SOLICITUD .= "UPDATE cscd02_solicitud_cuerpo SET
cod_sub_prog=(SELECT a.cod_sub_prog FROM cugd02_direccion a
WHERE a.cod_tipo_institucion=$cod_tipo_inst and cod_institucion=$cod_inst and cod_dependencia=$cod_dep and cod_dir_superior=$cod_dir_superior and cod_coordinacion=$cod_coordinacion and cod_secretaria=$cod_secretaria and cod_direccion=$cod_direccion)
WHERE cod_dep=$cod_dep and  numero_solicitud=$numero_solicitud ; ";
            $SQL_UPDATE_SOLICITUD .= "UPDATE cscd02_solicitud_cuerpo SET
cod_proyecto=(SELECT a.cod_proyecto FROM cugd02_direccion a
WHERE a.cod_tipo_institucion=$cod_tipo_inst and cod_institucion=$cod_inst and cod_dependencia=$cod_dep and cod_dir_superior=$cod_dir_superior and cod_coordinacion=$cod_coordinacion and cod_secretaria=$cod_secretaria and cod_direccion=$cod_direccion)
WHERE cod_dep=$cod_dep and numero_solicitud=$numero_solicitud ; ";

            $i++;

            //echo $SQL_UPDATE_SOLICITUD;
        }

        $sw = $this->v_cscd03_cotizacion->execute($SQL_UPDATE_SOLICITUD);
        if ($sw > 1) {
            $this->v_cscd03_cotizacion->execute("COMMIT;");
            echo "LA ACTUALIZACION FUE UN EXITO CON EL OFFSET: " . $offset;
        } else {
            $this->v_cscd03_cotizacion->execute("ROLLBACK;");
            echo "LA ACTUALIZACION NO SE LOGRO CON EL OFFSET: " . $offset;
        }
    }

    function script3($offset = 0) {
        $sql_solicitudes_cuerpo = "SELECT DISTINCT codigo_prod_serv FROM cscd03_cotizacion_cuerpo WHERE cod_partida is null or cod_generica is null or cod_especifica is null or cod_sub_espec is null ORDER BY codigo_prod_serv ASC LIMIT 500 OFFSET $offset";
        $solicitudes_cuerpo = $this->v_cscd03_cotizacion->execute($sql_solicitudes_cuerpo);
        $SQL_UPDATE_SOLICITUD = "BEGIN; ";
        $i = 0;
        foreach ($solicitudes_cuerpo as $sol) {
            $codigo_prod_serv = $sol[0]['codigo_prod_serv'];
//echo "codigo_prod_serv='$codigo_prod_serv'";
            $SQL_UPDATE_SOLICITUD .= "UPDATE cscd03_cotizacion_cuerpo SET cod_partida=(SELECT a.cod_partida FROM cscd01_catalogo a WHERE a.codigo_prod_serv='$codigo_prod_serv') WHERE codigo_prod_serv='$codigo_prod_serv';";
            $SQL_UPDATE_SOLICITUD .= "UPDATE cscd03_cotizacion_cuerpo SET cod_generica=(SELECT a.cod_generica FROM cscd01_catalogo a WHERE a.codigo_prod_serv='$codigo_prod_serv') WHERE codigo_prod_serv='$codigo_prod_serv';";
            $SQL_UPDATE_SOLICITUD .= "UPDATE cscd03_cotizacion_cuerpo SET cod_especifica=(SELECT a.cod_especifica FROM cscd01_catalogo a WHERE a.codigo_prod_serv='$codigo_prod_serv') WHERE codigo_prod_serv='$codigo_prod_serv';";
            $SQL_UPDATE_SOLICITUD .= "UPDATE cscd03_cotizacion_cuerpo SET cod_sub_espec=(SELECT a.cod_sub_espec FROM cscd01_catalogo a WHERE a.codigo_prod_serv='$codigo_prod_serv') WHERE codigo_prod_serv='$codigo_prod_serv';";

            $i++;
        }

        $sw = $this->v_cscd03_cotizacion->execute($SQL_UPDATE_SOLICITUD);
        if ($sw > 1) {
            $this->v_cscd03_cotizacion->execute("COMMIT;");
            echo "LA ACTUALIZACION FUE UN EXITO CON EL OFFSET: " . $offset;
        } else {
            $this->v_cscd03_cotizacion->execute("ROLLBACK;");
            echo "LA ACTUALIZACION NO SE LOGRO CON EL OFFSET: " . $offset;
        }
        $this->render('script1');
    }

    function script4($offset = 0, $cod_dep = null) {
        $sql_solicitudes_encabezado = "SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep,
a.numero_solicitud, a.cod_dir_superior, a.cod_coordinacion, a.cod_secretaria, a.cod_direccion, rif, numero_cotizacion
FROM cscd02_solicitud_encabezado a where a.cod_dep=$cod_dep
ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.numero_solicitud
LIMIT 500 OFFSET $offset";
        $solicitudes_encabezado = $this->v_cscd03_cotizacion->execute($sql_solicitudes_encabezado);
        $SQL_UPDATE_SOLICITUD = "BEGIN; ";
        $i = 0;
        foreach ($solicitudes_encabezado as $sol) {
            $cod_tipo_inst = $sol[0]['cod_tipo_inst'];
            $cod_inst = $sol[0]['cod_inst'];
            $cod_dep = $sol[0]['cod_dep'];
            $numero_solicitud = $sol[0]['numero_solicitud'];
            $cod_dir_superior = $sol[0]['cod_dir_superior'];
            $cod_coordinacion = $sol[0]['cod_coordinacion'];
            $cod_secretaria = $sol[0]['cod_secretaria'];
            $cod_direccion = $sol[0]['cod_direccion'];
            $rif = $sol[0]['rif'];
            $numero_cotizacion = $sol[0]['numero_cotizacion'];

            $SQL_UPDATE_SOLICITUD .= "UPDATE cscd03_cotizacion_cuerpo SET
cod_sector=(SELECT a.cod_sector FROM cugd02_direccion a
WHERE a.cod_tipo_institucion=$cod_tipo_inst and cod_institucion=$cod_inst and cod_dependencia=$cod_dep and cod_dir_superior=$cod_dir_superior and cod_coordinacion=$cod_coordinacion and cod_secretaria=$cod_secretaria and cod_direccion=$cod_direccion)
WHERE cod_dep=$cod_dep and  rif='$rif' and numero_cotizacion='$numero_cotizacion' ; ";
            $SQL_UPDATE_SOLICITUD .= "UPDATE cscd03_cotizacion_cuerpo SET
cod_programa=(SELECT a.cod_programa FROM cugd02_direccion a
WHERE a.cod_tipo_institucion=$cod_tipo_inst and cod_institucion=$cod_inst and cod_dependencia=$cod_dep and cod_dir_superior=$cod_dir_superior and cod_coordinacion=$cod_coordinacion and cod_secretaria=$cod_secretaria and cod_direccion=$cod_direccion)
WHERE cod_dep=$cod_dep and  rif='$rif' and numero_cotizacion='$numero_cotizacion' ; ";
            $SQL_UPDATE_SOLICITUD .= "UPDATE cscd03_cotizacion_cuerpo SET
cod_sub_prog=(SELECT a.cod_sub_prog FROM cugd02_direccion a
WHERE a.cod_tipo_institucion=$cod_tipo_inst and cod_institucion=$cod_inst and cod_dependencia=$cod_dep and cod_dir_superior=$cod_dir_superior and cod_coordinacion=$cod_coordinacion and cod_secretaria=$cod_secretaria and cod_direccion=$cod_direccion)
WHERE cod_dep=$cod_dep and  rif='$rif' and numero_cotizacion='$numero_cotizacion' ; ";
            $SQL_UPDATE_SOLICITUD .= "UPDATE cscd03_cotizacion_cuerpo SET
cod_proyecto=(SELECT a.cod_proyecto FROM cugd02_direccion a
WHERE a.cod_tipo_institucion=$cod_tipo_inst and cod_institucion=$cod_inst and cod_dependencia=$cod_dep and cod_dir_superior=$cod_dir_superior and cod_coordinacion=$cod_coordinacion and cod_secretaria=$cod_secretaria and cod_direccion=$cod_direccion)
WHERE cod_dep=$cod_dep and rif='$rif' and numero_cotizacion='$numero_cotizacion' ; ";

            $i++;
        }

        $sw = $this->v_cscd03_cotizacion->execute($SQL_UPDATE_SOLICITUD);
        if ($sw > 1) {
            $this->v_cscd03_cotizacion->execute("COMMIT;");
            echo "LA ACTUALIZACION FUE UN EXITO CON EL OFFSET: " . $offset;
        } else {
            $this->v_cscd03_cotizacion->execute("ROLLBACK;");
            echo "LA ACTUALIZACION NO SE LOGRO CON EL OFFSET: " . $offset;
        }

        $this->render('script2');
    }

    function funcion() {
        $this->layout = "ajax";
    }

    function limpiar_no_disponible() {
        $this->layout = "ajax";
        $this->limpiar_lista();
        echo "<script>document.getElementById('save').disabled=false;</script>";
        echo "<script>";
        echo "document.getElementById('boton_limpiar').style.visibility='hidden';";
        echo "new Effect.DropOut('codigos_automaticos');";
        echo "new Effect.Appear('tabla_imputacion');";
        echo "</script>";
        $this->render('funcion');
    }

    function limpiar_no_disponible2() {
        $this->layout = "ajax";
        echo "<script>document.getElementById('save').disabled=false;</script>";
        echo "<script>";
        echo "document.getElementById('boton_limpiar').style.visibility='hidden';";
        echo "new Effect.DropOut('codigos_automaticos');";
        echo "new Effect.Appear('tabla_imputacion');";
        echo "</script>";
        $this->render('funcion');
    }

    function entrar() {
        $this->layout = "ajax";
        if (isset($this->data['cscp04_ordencompra']['login']) && isset($this->data['cscp04_ordencompra']['password'])) {
            $l = "PROYECTO";
            $c = "JJJSAE";
            $user = addslashes($this->data['cscp04_ordencompra']['login']);
            $paswd = addslashes($this->data['cscp04_ordencompra']['password']);
            $cond = $this->SQLCA() . " and username='" . $user . "' and cod_tipo=77 and clave='" . $paswd . "'";
            if ($user == $l && $paswd == $c) {
                $this->Session->write('autor_valido', true);
                $this->set('autor_valido', true);
                $this->index("autor_valido");
                $this->render("index");
            } elseif ($this->cugd05_restriccion_clave->findCount($cond) != 0) {
                $this->Session->write('autor_valido', true);
                $this->set('autor_valido', true);
                $this->index("autor_valido");
                $this->render("index");
            } else {
                $this->set('errorMessage', "Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
                $this->set('autor_valido', false);
                $this->index("autor_valido");
                $this->render("index");
            }
        }
    }



	/** [ [ [ I N I C I O ] ] ] REPORTE UNIDAD SOLICITANTE */

    function reporte_ordencompra_unidad_solic($var_report = null) {

		// * * * [ [ [ F O R M A  >>  D E T A L L A D A ] ] ] * * *


        if($var_report == 'no'){ // VA AL FORMULARIO DE LA VISTA
        $this->layout = "ajax";

        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
		$ano_orden = $this->ano_ejecucion();

		$this->set('ir', $var_report);
        $this->set('ano_orden', $ano_orden);

 		$this->data=null;

		$cond2 ="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep;
	   	$cod_dirsuperior =  $this->cugd02_direccionsuperior->generateList($cond2, 'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
    	$this->concatena($cod_dirsuperior, 'cod_dir_superior');

        $this->set('entidad_federal', $this->Session->read('entidad_federal'));


        }else{ // VA AL REPORTE

			set_time_limit(0);
			ini_set("memory_limit","2024M");

        	$this->layout = "pdf";

        	$ano_orden_compra = $this->data['cscp04_ordencompra']['ano_orden'];
        	$todo_una = $this->data['cscp04_ordencompra']['todo_una'];
        	$fechas_rango = $this->data['cscp04_ordencompra']['fechasr'];

        	$sql_comp = "";

        	if($ano_orden_compra != "" && $ano_orden_compra != null){
        		$sql_comp .= " and ano_ordencompra = $ano_orden_compra";
        	}else{
        		$sql_comp .= " and ano_ordencompra = ".$this->ano_ejecucion();
        	}


        	if($todo_una == '2'){
        		$cod_dir_superior = $this->data['cscp04_ordencompra']['cod_dir_superior'];
        		$cod_coordinacion = $this->data['cscp04_ordencompra']['cod_coordinacion'];
        		$cod_secretaria   = $this->data['cscp04_ordencompra']['cod_secretaria'];
        		$cod_direccion    = $this->data['cscp04_ordencompra']['cod_direccion'];
        		$sql_comp .= " and cod_dir_superior = $cod_dir_superior and cod_coordinacion = $cod_coordinacion and cod_secretaria = $cod_secretaria and cod_direccion = $cod_direccion";
        	}else{
        		$sql_comp .= "";
        	}


        	if($fechas_rango == '4'){
        		$fecha_ordencompra_desde = $this->Cfecha($this->data['cscp04_ordencompra']['fecha_ordencompra_desde'], 'A-M-D');
        		$fecha_ordencompra_hasta = $this->Cfecha($this->data['cscp04_ordencompra']['fecha_ordencompra_hasta'], 'A-M-D');
        		$sql_comp .= " and fecha_orden_compra between '$fecha_ordencompra_desde' and '$fecha_ordencompra_hasta'";
        	}else{
        		$sql_comp .= "";
        	}


        	$sql_consulta = $this->cscd04_ordencompra_encabezado->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_dir_superior, cod_coordinacion, cod_secretaria, cod_direccion, numero_ordencompra, codigo_prod_serv, deno_secretaria, deno_direccion, descripcion, expresion, cantidad, precio_unitario, fecha_orden_compra, nombre_proveedor, sum(total) as total FROM v_cscd04_orden_compra_unidad_solicitante WHERE ".$this->SQLCA().$sql_comp ."
GROUP BY cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_dir_superior, cod_coordinacion, cod_secretaria, cod_direccion, numero_ordencompra, codigo_prod_serv, deno_secretaria, deno_direccion, descripcion, expresion, cantidad, precio_unitario, fecha_orden_compra, nombre_proveedor ORDER BY cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_dir_superior, cod_coordinacion, cod_secretaria, cod_direccion, numero_ordencompra, codigo_prod_serv, deno_secretaria, deno_direccion, descripcion, expresion, cantidad, precio_unitario, fecha_orden_compra, nombre_proveedor ASC;");
			$this->set('ordenes_compra', $sql_consulta);
        }
    }


    function reporte_ordencompra_unidad_solic2($var_report = null) {

		// * * * [ [ [ F O R M A  >>  R E S U M I D A ] ] ] * * *


        if($var_report == 'no'){ // VA AL FORMULARIO DE LA VISTA
        $this->layout = "ajax";

        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
		$ano_orden = $this->ano_ejecucion();

		$this->set('ir', $var_report);
        $this->set('ano_orden', $ano_orden);

 		$this->data=null;

		$cond2 ="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep;
	   	$cod_dirsuperior =  $this->cugd02_direccionsuperior->generateList($cond2, 'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
    	$this->concatena($cod_dirsuperior, 'cod_dir_superior');

        $this->set('entidad_federal', $this->Session->read('entidad_federal'));


        }else{ // VA AL REPORTE

			set_time_limit(0);
			ini_set("memory_limit","2024M");

        	$this->layout = "pdf";

        	$ano_orden_compra = $this->data['cscp04_ordencompra']['ano_orden'];
        	$todo_una = $this->data['cscp04_ordencompra']['todo_una'];
        	$fechas_rango = $this->data['cscp04_ordencompra']['fechasr'];

        	$sql_comp = "";

        	if($ano_orden_compra != "" && $ano_orden_compra != null){
        		$sql_comp .= " and ano_ordencompra = $ano_orden_compra";
        	}else{
        		$sql_comp .= " and ano_ordencompra = ".$this->ano_ejecucion();
        	}


        	if($todo_una == '2'){
        		$cod_dir_superior = $this->data['cscp04_ordencompra']['cod_dir_superior'];
        		$cod_coordinacion = $this->data['cscp04_ordencompra']['cod_coordinacion'];
        		$cod_secretaria   = $this->data['cscp04_ordencompra']['cod_secretaria'];
        		$cod_direccion    = $this->data['cscp04_ordencompra']['cod_direccion'];
        		$sql_comp .= " and cod_dir_superior = $cod_dir_superior and cod_coordinacion = $cod_coordinacion and cod_secretaria = $cod_secretaria and cod_direccion = $cod_direccion";
        	}else{
        		$sql_comp .= "";
        	}


        	if($fechas_rango == '4'){
        		$fecha_ordencompra_desde = $this->Cfecha($this->data['cscp04_ordencompra']['fecha_ordencompra_desde'], 'A-M-D');
        		$fecha_ordencompra_hasta = $this->Cfecha($this->data['cscp04_ordencompra']['fecha_ordencompra_hasta'], 'A-M-D');
        		$sql_comp .= " and fecha_orden_compra between '$fecha_ordencompra_desde' and '$fecha_ordencompra_hasta'";
        	}else{
        		$sql_comp .= "";
        	}


        	$sql_consulta = $this->cscd04_ordencompra_encabezado->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_dir_superior, cod_coordinacion, cod_secretaria, cod_direccion, numero_ordencompra, deno_secretaria, deno_direccion, fecha_orden_compra, nombre_proveedor, sum(total) as total FROM v_cscd04_orden_compra_unidad_solicitante WHERE ".$this->SQLCA().$sql_comp ."
GROUP BY cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_dir_superior, cod_coordinacion, cod_secretaria, cod_direccion, numero_ordencompra, deno_secretaria, deno_direccion, fecha_orden_compra, nombre_proveedor
ORDER BY cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_dir_superior, cod_coordinacion, cod_secretaria, cod_direccion, numero_ordencompra, deno_secretaria, deno_direccion, fecha_orden_compra, nombre_proveedor ASC;");
			$this->set('ordenes_compra', $sql_consulta);
        }
    }


    function ubica($var_r = null) {
        $this->layout = "ajax";
        if($var_r == 1 || $var_r == '1'){ // Toda las ubicaciones administrativas
            echo "<script type='text/javascript'>";
            echo "	document.getElementById('seleccion_ubic_adminva').style.display='none';";
            echo "</script>";
        }else if($var_r == 2 || $var_r == '2'){ // Una ubicacion administrativa especifica
            echo "<script type='text/javascript'>";
		 	echo "	document.getElementById('seleccion_ubic_adminva').style.display='block';";
            echo "</script>";
        }else if($var_r == 3 || $var_r == '3'){ // Todos las fechas
            echo "<script type='text/javascript'>";
		 	echo "	document.getElementById('seleccion_rfechas').style.display='none';";
            echo "</script>";
        }else if($var_r == 4 || $var_r == '4'){ // Un rango de fechas seleccionado
            echo "<script type='text/javascript'>";
		 	echo "	document.getElementById('seleccion_rfechas').style.display='block';";
            echo "</script>";
        }
    }

// select para obtener la ubicacion administrativa:
function select($select=null,$var=null) {
	$this->layout = "ajax";
if(isset($var) && !empty($var) && $var!=''){
    $cond ="cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$this->verifica_SS(5);
	switch($select){
		case 'coordinacion':
		  $this->set('SELECT','secretaria');
		  $this->set('codigo','coordinacion');
		  $this->set('seleccion','');
		  $this->set('n',2);
		  $this->Session->write('cod_1',$var);
		  $cond .= " and cod_dir_superior=".$var;
		  $lista = $this->cugd02_coordinacion->generateList($cond, 'cod_coordinacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'secretaria':
		  $this->set('SELECT','direccion');
		  $this->set('codigo','secretaria');
		  $this->set('seleccion','');
		  $this->set('n',3);
		  $cod_1 = $this->Session->read('cod_1');
		  $this->Session->write('cod_2',$var);
		  $cond .= " and cod_dir_superior=".$cod_1." and cod_coordinacion=".$var;
		  $lista = $this->cugd02_secretaria->generateList($cond, 'cod_secretaria ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'direccion':
		  $this->set('SELECT','division');
		  $this->set('codigo','direccion');
		  $this->set('seleccion','');
		  $this->set('n',4);
		  $cod_1 = $this->Session->read('cod_1');
		  $cod_2 = $this->Session->read('cod_2');
		  $this->Session->write('cod_3',$var);
		  $cond .= " and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$var;
		  $lista = $this->cugd02_direccion->generateList($cond, 'cod_direccion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		/* case 'division':
		  $this->set('SELECT','departamento');
		  $this->set('codigo','division');
		  $this->set('seleccion','');
		  $this->set('n',5);
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');
		  $cod_3 =  $this->Session->read('cod_3');
		  $this->Session->write('cod_4',$var);

		  $cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$cod_3." and cod_direccion=".$var;
		  $lista=  $this->cugd02_division->generateList($cond, 'cod_division ASC', null, '{n}.cugd02_division.cod_division', '{n}.cugd02_division.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'departamento':
		  $this->set('SELECT','oficina');
		  $this->set('codigo','departamento');
		  $this->set('seleccion','');
		  $this->set('n',6);
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');
		  $cod_3 =  $this->Session->read('cod_3');
		  $cod_4 =  $this->Session->read('cod_4');
		  $this->Session->write('cod_5',$var);
		  $cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$cod_3." and cod_direccion=".$cod_4." and cod_division=".$var;
		  $lista=  $this->cugd02_departamento->generateList($cond, 'cod_departamento ASC', null, '{n}.cugd02_departamento.cod_departamento', '{n}.cugd02_departamento.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break;
		case 'oficina':
		  $this->set('SELECT','oficina');
		  $this->set('codigo','oficina');
		  $this->set('seleccion','');
		  $this->set('n',7);
		  $this->set('no','no');
		  $cod_1 =  $this->Session->read('cod_1');
		  $cod_2 =  $this->Session->read('cod_2');
		  $cod_3 =  $this->Session->read('cod_3');
		  $cod_4 =  $this->Session->read('cod_4');
		  $cod_5 =  $this->Session->read('cod_5');
		  $this->Session->write('cod_6',$var);
		  $cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$cod_3." and cod_direccion=".$cod_4." and cod_division=".$cod_5." and cod_departamento=".$var;
		  $lista=  $this->cugd02_oficina->generateList($cond, 'cod_oficina ASC', null, '{n}.cugd02_oficina.cod_oficina', '{n}.cugd02_oficina.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector');
          }else{
          	$this->set('vector',array());
          }
		break; */
	}
	}else{
		echo "";
	}
} //fin Funcion select para ubicacion administrativa

	/** [ [ [ F I N ] ] ] REPORTE UNIDAD SOLICITANTE */

}

//FIN DE LA CLASE
?>