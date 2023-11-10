<?php

class Cnmp03partidasController extends AppController {

    var $name = 'cnmp03partidas';
    var $uses = array('cnmd03_partidas', 'cnmd03_transaccion', 'cfpd01_partida', 'cfpd01_generica', 'cfpd01_especifica', 'cfpd01_sub_espec', 'cfpd01_auxiliar');
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

    function AddCeroR($n, $extra = null) {
        if ($n != null) {
            if ($extra == null) {
                if ($n < 10) {
                    $Var = "0" . $n;
                } else {
                    $Var = $n;
                }
            } else {
                if ($n < 10) {
                    $Var = $extra . ".0" . $n;
                } else {
                    $Var = $extra . "." . $n;
                }
            }

            $Var = substr($Var, - 2);

            return $Var;
        } else {
            //return $Var;
        }
    }

//fin AddCero

    function AddCero($nomVar, $vector = object, $extra = null) {
        if ($vector != null) {
            if ($extra == null) {
                foreach ($vector as $x) {
                    if ($x < 10) {
                        $Var[$x] = "0" . $x;
                    } else {
                        $Var[$x] = $x;
                    }
                }//fin each
            } else {
                foreach ($vector as $x) {
                    if ($x < 10) {
                        $Var[$x] = $extra . ".0" . $x;
                    } else {
                        $Var[$x] = $extra . "." . $x;
                    }
                }//fin each
            }
            $this->set($nomVar, $Var);
        } else {
            $this->set($nomVar, '');
        }
    }

//fin AddCero

    function add_c_c($var) {
        if ($var <= 9 && strlen($var) == 1) {
            $codigo = '0' . $var;
        } else {
            $codigo = '.' . $var;
        }
        return $codigo;
    }

//fin AddCero

    function selec_partida($id = null, $var = '4', $aux = null) {
        $this->layout = "ajax";

        if ($this->data['cfpp00']['codigo'] && $var != null) {
            $this->set('selecion', $this->data['cfpp00']['codigo']);
        }
        if ($var == null) {
            $var = $this->data['cfpp00']['codigo'];
        }
        if ($aux != null) {
            $this->set('selecion', $aux);
        }

        $this->set('id', $id);

        $this->set('opcion1', $var);

        if ($var != null && $var != 'otros') {

            $this->concatena($this->cfpd01_partida->generateList('where cod_grupo =  ' . CE . ' and (cod_partida=1 or cod_partida=3 or cod_partida=7 or cod_partida=11) ', ' cod_partida ASC', null, '{n}.cfpd01_partida.cod_partida', '{n}.cfpd01_partida.descripcion'), 'partida');
        } else {
            $this->AddCero('partida', '');
        }
    }

    function selec_generica($id = null, $var1 = '4', $var2 = null, $aux = null) {
        $this->layout = "ajax";

        if ($this->data['cfpp00']['codigo'] && $var2 != null) {
            $this->set('selecion', $this->data['cfpp00']['codigo']);
        }
        if ($var2 == null) {
            $var2 = $this->data['cfpp00']['codigo'];
        }
        if ($aux != null) {
            $this->set('selecion', $aux);
        }

        $this->set('id', $id);

        $this->set('opcion1', $var1);
        $this->set('opcion2', $var2);

        if ($var2 != null && $var2 != 'otros') {

            $this->concatena($this->cfpd01_generica->generateList('where cod_grupo =  ' . $var1 . '  and cod_partida = ' . $var2 . '', ' cod_generica ASC', null, '{n}.cfpd01_generica.cod_generica', '{n}.cfpd01_generica.descripcion'), 'generica');
        } else {
            $this->AddCero('generica', '');
        }
    }

    function selec_especifica($id = null, $var1 = '4', $var2 = null, $var3 = null, $aux = null) {
        $this->layout = "ajax";

        if ($this->data['cfpp00']['codigo'] && $var3 != null) {
            $this->set('selecion', $this->data['cfpp00']['codigo']);
        }
        if ($var3 == null) {
            $var3 = $this->data['cfpp00']['codigo'];
        }
        if ($aux != null) {
            $this->set('selecion', $aux);
        }

        $this->set('id', $id);

        $this->set('opcion1', $var1);
        $this->set('opcion2', $var2);
        $this->set('opcion3', $var3);

        if ($var3 != null && $var3 != 'otros') {

            $this->concatena($this->cfpd01_especifica->generateList('where cod_grupo =  ' . $var1 . '  and cod_partida = ' . $var2 . ' and cod_generica = ' . $var3 . '', ' cod_especifica ASC', null, '{n}.cfpd01_especifica.cod_especifica', '{n}.cfpd01_especifica.descripcion'), 'especifica');
        } else {
            $this->AddCero('especifica', '');
        }
    }

    function selec_sub_especifica($id = null, $var1 = '4', $var2 = null, $var3 = null, $var4 = null, $aux = null) {
        $this->layout = "ajax";

        if ($this->data['cfpp00']['codigo'] && $var4 != null) {
            $this->set('selecion', $this->data['cfpp00']['codigo']);
        }
        if ($var4 == null) {
            $var4 = $this->data['cfpp00']['codigo'];
        }
        if ($aux != null) {
            $this->set('selecion', $aux);
        }

        $this->set('id', $id);

        $this->set('opcion1', $var1);
        $this->set('opcion2', $var2);
        $this->set('opcion3', $var3);
        $this->set('opcion4', $var4);

        if ($var4 != null && $var4 != 'otros') {

            $this->concatena($this->cfpd01_sub_espec->generateList('where cod_grupo =  ' . $var1 . '  and cod_partida = ' . $var2 . ' and cod_generica = ' . $var3 . ' and cod_especifica = ' . $var4 . '', ' cod_sub_espec ASC', null, '{n}.cfpd01_sub_espec.cod_sub_espec', '{n}.cfpd01_sub_espec.descripcion'), 'subespecifica');
        } else {
            $this->AddCero('subespecifica', '');
        }
    }

    function selec_auxiliar($id = null, $var1 = '4', $var2 = null, $var3 = null, $var4 = null, $var5 = null, $aux = null) {
        $this->layout = "ajax";

        if ($this->data['cfpp00']['codigo'] && $var5 != null) {
            $this->set('selecion', $this->data['cfpp00']['codigo']);
        }
        if ($var5 == null) {
            $var5 = $this->data['cfpp00']['codigo'];
        }
        if ($aux != null) {
            $this->set('selecion', $aux);
        }

        $this->set('id', $id);
        $this->set('id', $id);

        $this->set('opcion1', $var1);
        $this->set('opcion2', $var2);
        $this->set('opcion3', $var3);
        $this->set('opcion4', $var4);
        $this->set('opcion5', $var5);

        if ($var5 != null && $var5 != 'otros') {

            $this->concatena_auxiliar($this->cfpd01_auxiliar->generateList('where cod_grupo =  ' . $var1 . '  and cod_partida = ' . $var2 . ' and cod_generica = ' . $var3 . ' and cod_especifica = ' . $var4 . ' and cod_sub_espec = ' . $var5 . '', ' cod_auxiliar ASC', null, '{n}.cfpd01_auxiliar.cod_auxiliar', '{n}.cfpd01_auxiliar.descripcion'), 'auxiliar');
        } else {
            $this->AddCero('auxiliar', '');
        }
    }

    function principal($var1 = '4', $var2 = null, $var3 = null, $var4 = null, $var5 = null, $var6 = null) {

        $this->layout = "ajax";
        $this->set('opcion1', $var1);
        $this->set('opcion2', $var2);
        $this->set('opcion3', $var3);
        $this->set('opcion4', $var4);
        $this->set('opcion5', $var5);
        $this->set('opcion6', $var6);

        $action = '';
        $tabla = '';
        $sql_3 = '';


        if ($var1 == 'otros') {
            $action = $var1;
        }
        if ($var2 == 'otros') {
            $action = $var2;
        }
        if ($var3 == 'otros') {
            $action = $var3;
        }
        if ($var4 == 'otros') {
            $action = $var4;
        }
        if ($var5 == 'otros') {
            $action = $var5;
        }
        if ($var6 == 'otros') {
            $action = $var6;
        }


        if ($var1 != null) {
            $sql_2 = 'cod_grupo =  ' . $var1 . '  ';
            $tabla = 'cfpd01_partida';
        }
        if ($var2 != null) {
            $sql_2 .= 'and cod_partida = ' . $var2 . '  ';
            $tabla = 'cfpd01_partida';
            $sql_3 = ' cod_grupo =  ' . $var1 . '  ';
        }
        if ($var3 != null) {
            $sql_2 .= 'and cod_generica = ' . $var3 . '  ';
            $tabla = 'cfpd01_generica';
            $sql_3 .= 'and cod_partida = ' . $var2 . '  ';
        }
        if ($var4 != null) {
            $sql_2 .= 'and cod_especifica = ' . $var4 . '  ';
            $tabla = 'cfpd01_especifica';
            $sql_3 .= 'and cod_generica = ' . $var3 . '  ';
        }


        if ($var5 != null) {
            $conta = $this->cfpd01_sub_espec->findCount($sql_2 . ' and cod_sub_espec = ' . $var5 . '  ');
            if ($conta != 0) {
                $sql_2.= 'and cod_sub_espec = ' . $var5 . '  ';
                $tabla = 'cfpd01_sub_espec';
                $sql_3 .= 'and cod_especifica = ' . $var4 . '  ';
            }//fin if
        }//fin if



        if ($var6 != null) {
            $conta = $this->cfpd01_auxiliar->findCount($sql_2 . ' and cod_auxiliar = ' . $var6 . '  ');
            if ($conta != 0) {
                $sql_2 .='and cod_auxiliar = ' . $var6 . '  ';
                $tabla = 'cfpd01_auxiliar';
                $sql_3.= 'and cod_sub_espec = ' . $var5 . ' ';
            }//fin if
        }//fin if

        $this->set('tabla', $tabla);

        if ($var1 != null && $action != 'otros') {

            $sql_re = $sql_2;
            $data = $this->$tabla->findAll($sql_re, null, null, null);

            $this->set('datos_cod_cfpp00', $data);
        } else if ($var1 != null) {

            $sql_re = $sql_3;
            $data = $this->$tabla->findAll($sql_re, null, null, null);

            $this->set('datos_cod_cfpp00', $data);
        }//fin else
    }

//FIN FUNCTION



    function index2($c_t_t = null, $c_t = null, $boton = null) {

        $this->layout = "ajax";
        $opcion = 'no';
        $data = '';
        $denominacion = '';

        $nombre_opcion = array('1' => 'empleados', '2' => 'obreros', '3' => 'militares_profesionales', '4' => 'militares_no_profesionales', '5' => 'contratados_empleados', '6' => 'suplencias_empleados', '7' => 'jubilados_empleados', '8' => 'jubilados_obreros', '9' => 'pensionados_empleados', '10' => 'pensionados_obreros', '11' => 'dietas', '12' => 'comision_de_servicio', '13' => 'becas', '14' => 'ayudas', '15' => 'suplencias_obreros', '16' => 'contratados_obreros', '17' => 'altos_funcionarios', '18' => 'eleccion_popular');

        $this->concatena($this->cfpd01_partida->generateList('where cod_grupo =  ' . CE . ' and (cod_partida=1 or cod_partida=3 or cod_partida=7 or cod_partida=11)', 'cod_partida ASC', null, '{n}.cfpd01_partida.cod_partida', '{n}.cfpd01_partida.descripcion'), 'partida_select', '4');

        for ($i = 1; $i <= 18; $i++) {
            $opcion = $nombre_opcion[$i];

            $this->concatena($this->cfpd01_partida->generateList('where cod_grupo =  ' . CE . ' and (cod_partida=1 or cod_partida=3 or cod_partida=7 or cod_partida=11)', 'cod_partida ASC', null, '{n}.cfpd01_partida.cod_partida', '{n}.cfpd01_partida.descripcion'), 'partida_' . $opcion . '', '4');
            $this->set('generica_' . $opcion . '', '');
            $this->set('especifica_' . $opcion . '', '');
            $this->set('subespecifica_' . $opcion . '', '');
            $this->set('auxiliar_' . $opcion . '', '');
            $this->set('denominacion_' . $opcion . '', '');
        }//fin for


        if ($c_t_t != null) {
            $this->set('selecion_c_t_t', $c_t_t);
            $opcion = 'si';
        } else {

            $this->set('selecion_c_t_t', '');
            $opcion = 'si';
        }//fin else


        if ($c_t != null) {
            $sql_c_t = "cod_tipo_transaccion=" . $c_t_t;
            if ($c_t_t == 1) {

            } else if ($c_t_t == 2) {
                $sql_c_t .= " and uso_transaccion=6";
            }
            $consulta_c_t = $this->cnmd03_transaccion->generateList($sql_c_t, 'cod_transaccion ASC', null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.denominacion');
            //$this->AddCero('c_t', $consulta_c_t);
            $this->concatenaN($consulta_c_t, "c_t");
            $this->set('selecion_c_t', $c_t);
            $denominacion = $this->cnmd03_transaccion->findAll('cod_tipo_transaccion=' . $c_t_t . ' and cod_transaccion=' . $c_t . '', null, null, null);
            $data = $this->cnmd03_partidas->findAll('cod_tipo_transaccion=' . $c_t_t . ' and cod_transaccion=' . $c_t . '', null, null, null);
            foreach ($denominacion as $denominacion_pre) {

            }

            $denominacion = $denominacion_pre['cnmd03_transaccion']['denominacion'];
            $opcion = 'no';

            $data_aux = $data;

            $cont = 0;

            foreach ($data_aux as $datos) {
                $cont++;

                $vec_datos[$cont]['clasificacion_personal'] = $datos['cnmd03_partidas']['clasificacion_personal'];
                $vec_datos[$cont]['cod_partida'] = $datos['cnmd03_partidas']['cod_partida'];
                $vec_datos[$cont]['cod_generica'] = $datos['cnmd03_partidas']['cod_generica'];
                $vec_datos[$cont]['cod_especifica'] = $datos['cnmd03_partidas']['cod_especifica'];
                $vec_datos[$cont]['cod_sub_espec'] = $datos['cnmd03_partidas']['cod_sub_espec'];
                $vec_datos[$cont]['cod_auxiliar'] = $datos['cnmd03_partidas']['cod_auxiliar'];
            }


//HAY QUE CAMBIAR EN EL GENERATE LISTE DE TODO LE QUE ESTA HACIA ABAJO VE QUE ESTAN INVERTIDAS O INTERCAMBIADAS POR EJEMPLO where cod_grupo =  4  and cod_partida = '.$vec_datos[$i]['cod_generica'].'

            if ($cont != 0) {

                for ($i = 1; $i <= 18; $i++) {
                    $opcion = $nombre_opcion[$i];

                    for ($j = 1; $j <= $cont; $j++) {
                        $sql_2 = "";
                        $tabla = "";
                        if ($vec_datos[$j]['clasificacion_personal'] == $i) {

                            /* $this->AddCero('partida_'.$opcion.'', $this->cfpd01_partida->generateList('where cod_grupo =  '.CE.' and (cod_partida=1 or cod_partida=3 or cod_partida=7)', ' cod_partida ASC', null, '{n}.cfpd01_partida.cod_partida', '{n}.cfpd01_partida.cod_partida'),'4');
                              $this->AddCero('generica_'.$opcion.'', $this->cfpd01_generica->generateList('where cod_grupo =  '.CE.'  and cod_partida = '.substr($vec_datos[$j]['cod_partida'],-2).'', ' cod_partida ASC', null, '{n}.cfpd01_generica.cod_generica', '{n}.cfpd01_generica.cod_generica'));
                              $this->AddCero('especifica_'.$opcion.'', $this->cfpd01_especifica->generateList('where cod_grupo =  '.CE.'  and cod_partida = '.substr($vec_datos[$j]['cod_partida'],-2).' and cod_generica = '.$vec_datos[$j]['cod_generica'].'', ' cod_generica ASC', null, '{n}.cfpd01_especifica.cod_especifica', '{n}.cfpd01_especifica.cod_especifica'));
                              $this->AddCero('subespecifica_'.$opcion.'', $this->cfpd01_sub_espec->generateList('where cod_grupo =  '.CE.'  and cod_partida = '.substr($vec_datos[$j]['cod_partida'],-2).' and cod_generica = '.$vec_datos[$j]['cod_generica'].' and cod_especifica = '.$vec_datos[$j]['cod_especifica'].'', ' cod_especifica ASC', null, '{n}.cfpd01_sub_espec.cod_sub_espec', '{n}.cfpd01_sub_espec.cod_sub_espec'));
                              $this->AddCero('auxiliar_'.$opcion.'', $this->cfpd01_auxiliar->generateList('where cod_grupo =  '.CE.'  and cod_partida = '.substr($vec_datos[$j]['cod_partida'],-2).' and cod_generica = '.$vec_datos[$j]['cod_generica'].' and cod_especifica = '.$vec_datos[$j]['cod_especifica'].' and cod_sub_espec = '.$vec_datos[$j]['cod_sub_espec'].'', ' cod_sub_espec ASC', null, '{n}.cfpd01_auxiliar.cod_auxiliar', '{n}.cfpd01_auxiliar.cod_auxiliar'));
                             */



                            $this->concatena($this->cfpd01_partida->generateList('where cod_grupo = ' . CE . ' and (cod_partida=1 or cod_partida=3 or cod_partida=7 or cod_partida=11) ', ' cod_partida ASC', null, '{n}.cfpd01_partida.cod_partida', '{n}.cfpd01_partida.descripcion'), 'partida_' . $opcion . '', '4');
                            $this->concatena($this->cfpd01_generica->generateList('where cod_grupo =  ' . CE . '  and cod_partida = ' . substr($vec_datos[$j]['cod_partida'], -2) . '', ' cod_partida ASC', null, '{n}.cfpd01_generica.cod_generica', '{n}.cfpd01_generica.descripcion'), 'generica_' . $opcion . '');
                            $this->concatena($this->cfpd01_especifica->generateList('where cod_grupo =  ' . CE . '  and cod_partida = ' . substr($vec_datos[$j]['cod_partida'], -2) . ' and cod_generica = ' . $vec_datos[$j]['cod_generica'] . '', ' cod_generica ASC', null, '{n}.cfpd01_especifica.cod_especifica', '{n}.cfpd01_especifica.descripcion'), 'especifica_' . $opcion . '');
                            $this->concatena($this->cfpd01_sub_espec->generateList('where cod_grupo =  ' . CE . '  and cod_partida = ' . substr($vec_datos[$j]['cod_partida'], -2) . ' and cod_generica = ' . $vec_datos[$j]['cod_generica'] . ' and cod_especifica = ' . $vec_datos[$j]['cod_especifica'] . '', ' cod_especifica ASC', null, '{n}.cfpd01_sub_espec.cod_sub_espec', '{n}.cfpd01_sub_espec.descripcion'), 'subespecifica_' . $opcion . '');
                            $this->concatena_auxiliar($this->cfpd01_auxiliar->generateList('where cod_grupo =  ' . CE . '  and cod_partida = ' . substr($vec_datos[$j]['cod_partida'], -2) . ' and cod_generica = ' . $vec_datos[$j]['cod_generica'] . ' and cod_especifica = ' . $vec_datos[$j]['cod_especifica'] . ' and cod_sub_espec = ' . $vec_datos[$j]['cod_sub_espec'] . '', ' cod_sub_espec ASC', null, '{n}.cfpd01_auxiliar.cod_auxiliar', '{n}.cfpd01_auxiliar.descripcion'), 'auxiliar_' . $opcion . '');


                            if ($vec_datos[$j]['cod_partida'] != '') {
                                $sql_2 .='cod_grupo = 4 and cod_partida = ' . substr($vec_datos[$j]['cod_partida'], -2) . '  ';
                                $tabla = 'cfpd01_partida';
                            }
                            if ($vec_datos[$j]['cod_generica'] != '') {
                                $sql_2 .='and cod_generica = ' . $vec_datos[$j]['cod_generica'] . '  ';
                                $tabla = 'cfpd01_generica';
                            }
                            if ($vec_datos[$j]['cod_especifica'] != '') {
                                $sql_2 .='and cod_especifica = ' . $vec_datos[$j]['cod_especifica'] . '  ';
                                $tabla = 'cfpd01_especifica';
                            }
                            if ($vec_datos[$j]['cod_sub_espec'] != '') {
                                if ($vec_datos[$j]['cod_sub_espec'] == 0 && $vec_datos[$j]['cod_auxiliar'] == 0) {

                                } else {
                                    $sql_2.= 'and cod_sub_espec = ' . $vec_datos[$j]['cod_sub_espec'] . '  ';
                                    $tabla = 'cfpd01_sub_espec';
                                }//fin else
                            }



                            if ($vec_datos[$j]['cod_auxiliar'] != '' && $vec_datos[$j]['cod_auxiliar'] != 0) {
                                $sql_2 .='and cod_auxiliar = ' . $vec_datos[$j]['cod_auxiliar'] . '  ';
                                $tabla = 'cfpd01_auxiliar';
                            }
                            $data_aux_1 = $this->$tabla->findAll($sql_2, null, null, null);
                            foreach ($data_aux_1 as $datos_aux_1) {

                            }

                            $this->set('denominacion_' . $opcion . '', $datos_aux_1[$tabla]['descripcion']);
                        }//fin if
                    }//fin forj
                }//for
            }//fin else
        } else {

            if ($c_t_t == null) {
                $this->set('selecion_c_t', '');
                $this->set('c_t', '');
                $opcion = 'si';
            } else {
                $sql_c_t = "cod_tipo_transaccion=" . $c_t_t;
                if ($c_t_t == 1) {

                } else if ($c_t_t == 2) {
                    $sql_c_t .= " and uso_transaccion=6";
                }
                $this->set('selecion_c_t', '');
                if ($this->cnmd03_transaccion->findCount($sql_c_t) != 0) {
                    $consulta_c_t = $this->cnmd03_transaccion->generateList($sql_c_t, 'cod_transaccion ASC', null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.denominacion');
                    //$this->AddCero('c_t', $consulta_c_t);
                    $this->concatenaN($consulta_c_t, "c_t");
                } else {
                    $this->set('c_t', 'vacio');
                }
                //$opcion = 'si';
            }
        }//fin else

        if ($boton == 'agregar') {
            $opcion = "agregar";
        }


        $this->set('denominacion', $denominacion);
        $this->set('entidad_federal', $this->Session->read('entidad_federal'));
        $this->set('agregar', $opcion);
        $this->set('boton', $boton);
        $this->set('data', $data);
    }

//fin funcion

    function index($var1 = null) {

        $this->layout = "ajax";

        if ($var1 == "a") {
            $_SESSION["opcion_de_venir"] = "si";
        }


        if ($var1 == null || $var1 == "a") {
            $var1 = 1;
        }

        $sql_c_t = "cod_tipo_transaccion=" . $var1;
        if ($var1 == 1) {

        } else if ($var1 == 2) {
            $sql_c_t .= " and uso_transaccion=6";
        }



        $this->set('selecion_c_t', '');
        if ($this->cnmd03_transaccion->findCount($sql_c_t) != 0) {

            $consulta_c_t = $this->cnmd03_transaccion->generateList($sql_c_t, 'cod_transaccion ASC', null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.denominacion');
            //$his->AddCero('c_t', $consulta_c_t);
            $this->concatenaN($consulta_c_t, "c_t");
        } else {
            $this->set('c_t', 'vacio');
        }


        $this->set('entidad_federal', $this->Session->read('entidad_federal'));



        $this->set("opcion", $var1);
    }

//fin function

    function cod_transaccion($cod = null) {

        $this->layout = "ajax";

        $sql_c_t = "cod_tipo_transaccion=" . $cod;
        if ($cod == 1) {

        } else if ($cod == 2) {
            $sql_c_t .= " and uso_transaccion=6";
        }

        $this->AddCero('cod_transaccion', $this->cnmd03_transaccion->generateList($sql_c_t, null, null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.cod_transaccion'));
    }

    function salir() {

        $this->layout = "ajax";

        if (!isset($_SESSION["opcion_de_venir"])) {
            $this->set('userTable', $this->requestAction('/cnmp03transacciones/', array('return')));
        } else {
            $this->set('userTable', "");
            $this->Session->delete('opcion_de_venir');
        }
    }

//fin funtcion

    function grabar($c_t_t = null, $c_t = null, $boton = null) {

        $this->layout = "ajax";
        $opcion = 'no';
        $data = '';
        $denominacion = '';



        $nombre_opcion = array('1' => 'empleados', '2' => 'obreros', '3' => 'militares_profesionales', '4' => 'militares_no_profesionales', '5' => 'contratados_empleados', '6' => 'suplencias_empleados', '7' => 'jubilados_empleados', '8' => 'jubilados_obreros', '9' => 'pensionados_empleados', '10' => 'pensionados_obreros', '11' => 'dietas', '12' => 'comision_de_servicio', '13' => 'becas', '14' => 'ayudas', '15' => 'suplencias_obreros', '16' => 'contratados_obreros', '17' => 'altos_funcionarios', '18' => 'eleccion_popular');

        for ($i = 1; $i <= 18; $i++) {

            $opcion = $nombre_opcion[$i];

            if (isset($this->data['cnmp03partidas'][$opcion])) {
                if (isset($this->data[$opcion]['cod_auxiliar'])) {
                    if ($this->data[$opcion]['cod_auxiliar'] == "") {
                        $this->data[$opcion]['cod_auxiliar'] = 0;
                    }
                } else {
                    $this->data[$opcion]['cod_auxiliar'] = 0;
                }//fin else



                if (isset($this->data[$opcion]['cod_sub_espec'])) {
                    if ($this->data[$opcion]['cod_sub_espec'] == "") {
                        $this->data[$opcion]['cod_sub_espec'] = 0;
                    }
                } else {
                    $this->data[$opcion]['cod_sub_espec'] = 0;
                }//fin else


                $sql_re = "cod_tipo_transaccion=" . $c_t_t . " and cod_transaccion=" . $c_t . " and clasificacion_personal=" . $i . " ";
                if ($this->cnmd03_partidas->findCount($sql_re) == 0) {
                    $sql = "INSERT INTO cnmd03_partidas (cod_tipo_transaccion, cod_transaccion, clasificacion_personal, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar)   VALUES  ( '" . $c_t_t . "',  '" . $c_t . "',  '" . $i . "',  '" . CE . $this->AddCeroR($this->data[$opcion]['cod_partida']) . "',  '" . $this->data[$opcion]['cod_generica'] . "',  '" . $this->data[$opcion]['cod_especifica'] . "',  '" . $this->data[$opcion]['cod_sub_espec'] . "',  '" . $this->data[$opcion]['cod_auxiliar'] . "')";
                    $this->cnmd03_partidas->execute($sql);
                } else {
                    $sql = "UPDATE cnmd03_partidas SET cod_partida='" . CE . $this->AddCeroR($this->data[$opcion]['cod_partida']) . "', cod_generica='" . $this->data[$opcion]['cod_generica'] . "', cod_especifica='" . $this->data[$opcion]['cod_especifica'] . "', cod_sub_espec='" . $this->data[$opcion]['cod_sub_espec'] . "', cod_auxiliar='" . $this->data[$opcion]['cod_auxiliar'] . "'    where cod_tipo_transaccion=" . $c_t_t . " and cod_transaccion=" . $c_t . " and clasificacion_personal=" . $i . "";
                    $this->cnmd03_partidas->execute($sql);
                }//fin else
            }//fin if
        }//fin for


        $nombre_opcion = array('1' => 'empleados', '2' => 'obreros', '3' => 'militares_profesionales', '4' => 'militares_no_profesionales', '5' => 'contratados_empleados', '6' => 'suplencias_empleados', '7' => 'jubilados_empleados', '8' => 'jubilados_obreros', '9' => 'pensionados_empleados', '10' => 'pensionados_obreros', '11' => 'dietas', '12' => 'comision_de_servicio', '13' => 'becas', '14' => 'ayudas', '15' => 'suplencias_obreros', '16' => 'contratados_obreros', '17' => 'altos_funcionarios', '18' => 'eleccion_popular');

        $this->AddCero('partida_select', $this->cfpd01_partida->generateList('where cod_grupo =  ' . CE . ' and (cod_partida=1 or cod_partida=3 or cod_partida=7 or cod_partida=11)', 'cod_partida ASC', null, '{n}.cfpd01_partida.cod_partida', '{n}.cfpd01_partida.cod_partida'), '4');

        for ($i = 1; $i <= 18; $i++) {
            $opcion = $nombre_opcion[$i];
            $this->AddCero('partida_' . $opcion . '', $this->cfpd01_partida->generateList('where cod_grupo =  ' . CE . ' and (cod_partida=1 or cod_partida=3 or cod_partida=7 or cod_partida=11)', 'cod_partida ASC', null, '{n}.cfpd01_partida.cod_partida', '{n}.cfpd01_partida.cod_partida'), '4');
            $this->set('generica_' . $opcion . '', '');
            $this->set('especifica_' . $opcion . '', '');
            $this->set('subespecifica_' . $opcion . '', '');
            $this->set('auxiliar_' . $opcion . '', '');
            $this->set('denominacion_' . $opcion . '', '');
        }//fin for



        if ($c_t_t != null) {
            $this->set('selecion_c_t_t', $c_t_t);
            $opcion = 'si';
        } else {

            $this->set('selecion_c_t_t', '');
            $opcion = 'si';
        }//fin else


        if ($c_t != null) {
            $sql_c_t = "cod_tipo_transaccion=" . $c_t_t;
            if ($c_t_t == 1) {

            } else if ($c_t_t == 2) {
                $sql_c_t .= " and uso_transaccion=6";
            }
            $consulta_c_t = $this->cnmd03_transaccion->generateList($sql_c_t, 'cod_transaccion ASC', null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.cod_transaccion');
            $this->AddCero('c_t', $consulta_c_t);
            $this->set('selecion_c_t', $c_t);
            $denominacion = $this->cnmd03_transaccion->findAll('cod_tipo_transaccion=' . $c_t_t . ' and cod_transaccion=' . $c_t . '', null, null, null);
            $data = $this->cnmd03_partidas->findAll('cod_tipo_transaccion=' . $c_t_t . ' and cod_transaccion=' . $c_t . '', null, null, null);
            foreach ($denominacion as $denominacion_pre) {

            }
            $denominacion = $denominacion_pre['cnmd03_transaccion']['denominacion'];
            $opcion = 'no';

            $data_aux = $data;

            $cont = 0;

            foreach ($data_aux as $datos) {
                $cont++;

                $vec_datos[$cont]['clasificacion_personal'] = $datos['cnmd03_partidas']['clasificacion_personal'];
                $vec_datos[$cont]['cod_partida'] = $datos['cnmd03_partidas']['cod_partida'];
                $vec_datos[$cont]['cod_generica'] = $datos['cnmd03_partidas']['cod_generica'];
                $vec_datos[$cont]['cod_especifica'] = $datos['cnmd03_partidas']['cod_especifica'];
                $vec_datos[$cont]['cod_sub_espec'] = $datos['cnmd03_partidas']['cod_sub_espec'];
                $vec_datos[$cont]['cod_auxiliar'] = $datos['cnmd03_partidas']['cod_auxiliar'];
            }


//HAY QUE CAMBIAR EN EL GENERATE LISTE DE TODO LE QUE ESTA HACIA BABAJO VE QUE ESTAN INVERTIDAS O INTERCAMBIADAS POR EJEMPLO where cod_grupo =  4  and cod_partida = '.$vec_datos[$i]['cod_generica'].'

            if ($cont != 0) {

                for ($i = 1; $i <= 18; $i++) {
                    $opcion = $nombre_opcion[$i];



                    for ($j = 1; $j <= $cont; $j++) {

                        $sql_2 = "";
                        $tabla = "";

                        if ($vec_datos[$j]['clasificacion_personal'] == $i) {

                            /* $this->AddCero('partida_'.$opcion.'', $this->cfpd01_partida->generateList('where cod_grupo =  '.CE.' and (cod_partida=1 or cod_partida=3 or cod_partida=7) ', ' cod_partida ASC', null, '{n}.cfpd01_partida.cod_partida', '{n}.cfpd01_partida.cod_partida'),'4');
                              $this->AddCero('generica_'.$opcion.'', $this->cfpd01_generica->generateList('where cod_grupo =  '.CE.'  and cod_partida = '.substr($vec_datos[$j]['cod_partida'],-2).'', ' cod_partida ASC', null, '{n}.cfpd01_generica.cod_generica', '{n}.cfpd01_generica.cod_generica'));
                              $this->AddCero('especifica_'.$opcion.'', $this->cfpd01_especifica->generateList('where cod_grupo =  '.CE.'  and cod_partida = '.substr($vec_datos[$j]['cod_partida'],-2).' and cod_generica = '.$vec_datos[$j]['cod_generica'].'', ' cod_generica ASC', null, '{n}.cfpd01_especifica.cod_especifica', '{n}.cfpd01_especifica.cod_especifica'));
                              $this->AddCero('subespecifica_'.$opcion.'', $this->cfpd01_sub_espec->generateList('where cod_grupo =  '.CE.'  and cod_partida = '.substr($vec_datos[$j]['cod_partida'],-2).' and cod_generica = '.$vec_datos[$j]['cod_generica'].' and cod_especifica = '.$vec_datos[$j]['cod_especifica'].'', ' cod_especifica ASC', null, '{n}.cfpd01_sub_espec.cod_sub_espec', '{n}.cfpd01_sub_espec.cod_sub_espec'));
                              $this->AddCero('auxiliar_'.$opcion.'', $this->cfpd01_auxiliar->generateList('where cod_grupo =  '.CE.'  and cod_partida = '.substr($vec_datos[$j]['cod_partida'],-2).' and cod_generica = '.$vec_datos[$j]['cod_generica'].' and cod_especifica = '.$vec_datos[$j]['cod_especifica'].' and cod_sub_espec = '.$vec_datos[$j]['cod_sub_espec'].'', ' cod_sub_espec ASC', null, '{n}.cfpd01_auxiliar.cod_auxiliar', '{n}.cfpd01_auxiliar.cod_auxiliar'));
                             */
                            $this->concatena($this->cfpd01_partida->generateList('where cod_grupo = ' . CE . ' and (cod_partida=1 or cod_partida=3 or cod_partida=7 or cod_partida=11) ', ' cod_partida ASC', null, '{n}.cfpd01_partida.cod_partida', '{n}.cfpd01_partida.descripcion'), 'partida_' . $opcion . '', '4');
                            $this->concatena($this->cfpd01_generica->generateList('where cod_grupo =  ' . CE . '  and cod_partida = ' . substr($vec_datos[$j]['cod_partida'], -2) . '', ' cod_partida ASC', null, '{n}.cfpd01_generica.cod_generica', '{n}.cfpd01_generica.descripcion'), 'generica_' . $opcion . '');
                            $this->concatena($this->cfpd01_especifica->generateList('where cod_grupo =  ' . CE . '  and cod_partida = ' . substr($vec_datos[$j]['cod_partida'], -2) . ' and cod_generica = ' . $vec_datos[$j]['cod_generica'] . '', ' cod_generica ASC', null, '{n}.cfpd01_especifica.cod_especifica', '{n}.cfpd01_especifica.descripcion'), 'especifica_' . $opcion . '');
                            $this->concatena($this->cfpd01_sub_espec->generateList('where cod_grupo =  ' . CE . '  and cod_partida = ' . substr($vec_datos[$j]['cod_partida'], -2) . ' and cod_generica = ' . $vec_datos[$j]['cod_generica'] . ' and cod_especifica = ' . $vec_datos[$j]['cod_especifica'] . '', ' cod_especifica ASC', null, '{n}.cfpd01_sub_espec.cod_sub_espec', '{n}.cfpd01_sub_espec.descripcion'), 'subespecifica_' . $opcion . '');
                            $this->concatena_auxiliar($this->cfpd01_auxiliar->generateList('where cod_grupo =  ' . CE . '  and cod_partida = ' . substr($vec_datos[$j]['cod_partida'], -2) . ' and cod_generica = ' . $vec_datos[$j]['cod_generica'] . ' and cod_especifica = ' . $vec_datos[$j]['cod_especifica'] . ' and cod_sub_espec = ' . $vec_datos[$j]['cod_sub_espec'] . '', ' cod_sub_espec ASC', null, '{n}.cfpd01_auxiliar.cod_auxiliar', '{n}.cfpd01_auxiliar.descripcion'), 'auxiliar_' . $opcion . '');

                            if ($vec_datos[$j]['cod_partida'] != '') {
                                $sql_2 .='cod_grupo = ' . CE . ' and cod_partida = ' . substr($vec_datos[$j]['cod_partida'], -2) . '  ';
                                $tabla = 'cfpd01_partida';
                            }
                            if ($vec_datos[$j]['cod_generica'] != '') {
                                $sql_2 .='and cod_generica = ' . $vec_datos[$j]['cod_generica'] . '  ';
                                $tabla = 'cfpd01_generica';
                            }
                            if ($vec_datos[$j]['cod_especifica'] != '') {
                                $sql_2 .='and cod_especifica = ' . $vec_datos[$j]['cod_especifica'] . '  ';
                                $tabla = 'cfpd01_especifica';
                            }
                            if ($vec_datos[$j]['cod_sub_espec'] != '') {
                                if ($vec_datos[$j]['cod_sub_espec'] == 0 && $vec_datos[$j]['cod_auxiliar'] == 0) {

                                } else {
                                    $sql_2.= 'and cod_sub_espec = ' . $vec_datos[$j]['cod_sub_espec'] . '  ';
                                    $tabla = 'cfpd01_sub_espec';
                                }//fin else
                            }



                            if ($vec_datos[$j]['cod_auxiliar'] != '' && $vec_datos[$j]['cod_auxiliar'] != 0) {
                                $sql_2 .='and cod_auxiliar = ' . $vec_datos[$j]['cod_auxiliar'] . '  ';
                                $tabla = 'cfpd01_auxiliar';
                            }
                            $data_aux_1 = $this->$tabla->findAll($sql_2, null, null, null);
                            foreach ($data_aux_1 as $datos_aux_1) {

                            }

                            $this->set('denominacion_' . $opcion . '', $datos_aux_1[$tabla]['descripcion']);
                        }//fin if
                    }//fin forj
                }//for
            }//fin else
        } else {

            if ($c_t_t == null) {
                $this->set('selecion_c_t', '');
                $this->set('c_t', '');
                $opcion = 'si';
            } else {
                $sql_c_t = "cod_tipo_transaccion=" . $c_t_t;
                if ($c_t_t == 1) {

                } else if ($c_t_t == 2) {
                    $sql_c_t .= " and uso_transaccion=6";
                }
                $this->set('selecion_c_t', '');
                if ($this->cnmd03_transaccion->findCount($sql_c_t) != 0) {
                    $consulta_c_t = $this->cnmd03_transaccion->generateList($sql_c_t, 'cod_transaccion ASC', null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.cod_transaccion');
                    $this->AddCero('c_t', $consulta_c_t);
                } else {
                    $this->set('c_t', 'vacio');
                }
                //$opcion = 'si';
            }
        }//fin else

        if ($boton == 'agregar') {
            $opcion = "agregar";
        }


        $this->set('denominacion', $denominacion);
        $this->set('entidad_federal', $this->Session->read('entidad_federal'));
        $this->set('agregar', $opcion);
        $this->set('boton', $boton);
        $this->set('data', $data);


        if (!isset($_SESSION["opcion_de_venir"])) {
            $this->set('userTable', $this->requestAction('/cnmp03transacciones/', array('return')));
        } else {

            $this->index2($c_t_t, $c_t);
            $this->render("index2");
        }
    }

//fin grabar

    function eliminar_view($c_t_t = null, $c_t = null, $boton = null) {


        $this->layout = "ajax";
        $opcion = 'no';
        $data = '';
        $denominacion = "";

        $nombre_opcion = array('1' => 'empleados', '2' => 'obreros', '3' => 'militares_profesionales', '4' => 'militares_no_profesionales', '5' => 'contratados_empleados', '6' => 'suplencias_empleados', '7' => 'jubilados_empleados', '8' => 'jubilados_obreros', '9' => 'pensionados_empleados', '10' => 'pensionados_obreros', '11' => 'dietas', '12' => 'comision_de_servicio', '13' => 'becas', '14' => 'ayudas', '15' => 'suplencias_obreros', '16' => 'contratados_obreros', '17' => 'altos_funcionarios', '18' => 'eleccion_popular');

        $this->AddCero('partida_select', $this->cfpd01_partida->generateList('where cod_grupo =  ' . CE . ' and (cod_partida=1 or cod_partida=3 or cod_partida=7 or cod_partida=11)', 'cod_partida ASC', null, '{n}.cfpd01_partida.cod_partida', '{n}.cfpd01_partida.cod_partida'), '4');

        for ($i = 1; $i <= 18; $i++) {
            $opcion = $nombre_opcion[$i];
            $this->AddCero('partida_' . $opcion . '', $this->cfpd01_partida->generateList('where cod_grupo =  ' . CE . ' and (cod_partida=1 or cod_partida=3 or cod_partida=7 or cod_partida=11)', 'cod_partida ASC', null, '{n}.cfpd01_partida.cod_partida', '{n}.cfpd01_partida.cod_partida'), '4');
            $this->set('generica_' . $opcion . '', '');
            $this->set('especifica_' . $opcion . '', '');
            $this->set('subespecifica_' . $opcion . '', '');
            $this->set('auxiliar_' . $opcion . '', '');
            $this->set('denominacion_' . $opcion . '', '');
        }//fin for



        if ($c_t_t != null) {
            $this->set('selecion_c_t_t', $c_t_t);
            $opcion = 'si';
        } else {

            $this->set('selecion_c_t_t', '');
            $opcion = 'si';
        }//fin else


        if ($c_t != null) {
            $sql_c_t = "cod_tipo_transaccion=" . $c_t_t;
            if ($c_t_t == 1) {

            } else if ($c_t_t == 2) {
                $sql_c_t .= " and uso_transaccion=6";
            }
            $consulta_c_t = $this->cnmd03_transaccion->generateList($sql_c_t, 'cod_transaccion ASC', null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.cod_transaccion');
            $this->AddCero('c_t', $consulta_c_t);
            $this->set('selecion_c_t', $c_t);
            $denominacion = $this->cnmd03_transaccion->findAll('cod_tipo_transaccion=' . $c_t_t . ' and cod_transaccion=' . $c_t . '', null, null, null);
            $data = $this->cnmd03_partidas->findAll('cod_tipo_transaccion=' . $c_t_t . ' and cod_transaccion=' . $c_t . '', null, null, null);
            foreach ($denominacion as $denominacion_pre) {

            }
            $denominacion = $denominacion_pre['cnmd03_transaccion']['denominacion'];
            $opcion = 'no';

            $data_aux = $data;

            $cont = 0;

            foreach ($data_aux as $datos) {
                $cont++;

                $vec_datos[$cont]['clasificacion_personal'] = $datos['cnmd03_partidas']['clasificacion_personal'];
                $vec_datos[$cont]['cod_partida'] = $datos['cnmd03_partidas']['cod_partida'];
                $vec_datos[$cont]['cod_generica'] = $datos['cnmd03_partidas']['cod_generica'];
                $vec_datos[$cont]['cod_especifica'] = $datos['cnmd03_partidas']['cod_especifica'];
                $vec_datos[$cont]['cod_sub_espec'] = $datos['cnmd03_partidas']['cod_sub_espec'];
                $vec_datos[$cont]['cod_auxiliar'] = $datos['cnmd03_partidas']['cod_auxiliar'];
            }


//HAY QUE CAMBIAR EN EL GENERATE LISTE DE TODO LE QUE ESTA HACIA BABAJO VE QUE ESTAN INVERTIDAS O INTERCAMBIADAS POR EJEMPLO where cod_grupo =  4  and cod_partida = '.$vec_datos[$i]['cod_generica'].'

            if ($cont != 0) {

                for ($i = 1; $i <= 18; $i++) {
                    $opcion = $nombre_opcion[$i];



                    for ($j = 1; $j <= $cont; $j++) {

                        $sql_2 = "";
                        $tabla = "";

                        if ($vec_datos[$j]['clasificacion_personal'] == $i) {

                            /* $this->AddCero('partida_'.$opcion.'', $this->cfpd01_partida->generateList('where cod_grupo =  '.CE.' and (cod_partida=1 or cod_partida=3 or cod_partida=7) ', ' cod_partida ASC', null, '{n}.cfpd01_partida.cod_partida', '{n}.cfpd01_partida.cod_partida'),'4');
                              $this->AddCero('generica_'.$opcion.'', $this->cfpd01_generica->generateList('where cod_grupo =  '.CE.'  and cod_partida = '.substr($vec_datos[$j]['cod_partida'],-2).'', ' cod_partida ASC', null, '{n}.cfpd01_generica.cod_generica', '{n}.cfpd01_generica.cod_generica'));
                              $this->AddCero('especifica_'.$opcion.'', $this->cfpd01_especifica->generateList('where cod_grupo =  '.CE.'  and cod_partida = '.substr($vec_datos[$j]['cod_partida'],-2).' and cod_generica = '.$vec_datos[$j]['cod_generica'].'', ' cod_generica ASC', null, '{n}.cfpd01_especifica.cod_especifica', '{n}.cfpd01_especifica.cod_especifica'));
                              $this->AddCero('subespecifica_'.$opcion.'', $this->cfpd01_sub_espec->generateList('where cod_grupo =  '.CE.'  and cod_partida = '.substr($vec_datos[$j]['cod_partida'],-2).' and cod_generica = '.$vec_datos[$j]['cod_generica'].' and cod_especifica = '.$vec_datos[$j]['cod_especifica'].'', ' cod_especifica ASC', null, '{n}.cfpd01_sub_espec.cod_sub_espec', '{n}.cfpd01_sub_espec.cod_sub_espec'));
                              $this->AddCero('auxiliar_'.$opcion.'', $this->cfpd01_auxiliar->generateList('where cod_grupo =  '.CE.'  and cod_partida = '.substr($vec_datos[$j]['cod_partida'],-2).' and cod_generica = '.$vec_datos[$j]['cod_generica'].' and cod_especifica = '.$vec_datos[$j]['cod_especifica'].' and cod_sub_espec = '.$vec_datos[$j]['cod_sub_espec'].'', ' cod_sub_espec ASC', null, '{n}.cfpd01_auxiliar.cod_auxiliar', '{n}.cfpd01_auxiliar.cod_auxiliar'));
                             */

                            $this->concatena($this->cfpd01_partida->generateList('where cod_grupo = ' . CE . ' and (cod_partida=1 or cod_partida=3 or cod_partida=7 or cod_partida=11) ', ' cod_partida ASC', null, '{n}.cfpd01_partida.cod_partida', '{n}.cfpd01_partida.descripcion'), 'partida_' . $opcion . '', '4');
                            $this->concatena($this->cfpd01_generica->generateList('where cod_grupo =  ' . CE . '  and cod_partida = ' . substr($vec_datos[$j]['cod_partida'], -2) . '', ' cod_partida ASC', null, '{n}.cfpd01_generica.cod_generica', '{n}.cfpd01_generica.descripcion'), 'generica_' . $opcion . '');
                            $this->concatena($this->cfpd01_especifica->generateList('where cod_grupo =  ' . CE . '  and cod_partida = ' . substr($vec_datos[$j]['cod_partida'], -2) . ' and cod_generica = ' . $vec_datos[$j]['cod_generica'] . '', ' cod_generica ASC', null, '{n}.cfpd01_especifica.cod_especifica', '{n}.cfpd01_especifica.descripcion'), 'especifica_' . $opcion . '');
                            $this->concatena($this->cfpd01_sub_espec->generateList('where cod_grupo =  ' . CE . '  and cod_partida = ' . substr($vec_datos[$j]['cod_partida'], -2) . ' and cod_generica = ' . $vec_datos[$j]['cod_generica'] . ' and cod_especifica = ' . $vec_datos[$j]['cod_especifica'] . '', ' cod_especifica ASC', null, '{n}.cfpd01_sub_espec.cod_sub_espec', '{n}.cfpd01_sub_espec.descripcion'), 'subespecifica_' . $opcion . '');
                            $this->concatena_auxiliar($this->cfpd01_auxiliar->generateList('where cod_grupo =  ' . CE . '  and cod_partida = ' . substr($vec_datos[$j]['cod_partida'], -2) . ' and cod_generica = ' . $vec_datos[$j]['cod_generica'] . ' and cod_especifica = ' . $vec_datos[$j]['cod_especifica'] . ' and cod_sub_espec = ' . $vec_datos[$j]['cod_sub_espec'] . '', ' cod_sub_espec ASC', null, '{n}.cfpd01_auxiliar.cod_auxiliar', '{n}.cfpd01_auxiliar.descripcion'), 'auxiliar_' . $opcion . '');



                            if ($vec_datos[$j]['cod_partida'] != '') {
                                $sql_2 .='cod_grupo = ' . CE . ' and cod_partida = ' . substr($vec_datos[$j]['cod_partida'], -2) . '  ';
                                $tabla = 'cfpd01_partida';
                            }
                            if ($vec_datos[$j]['cod_generica'] != '') {
                                $sql_2 .='and cod_generica = ' . $vec_datos[$j]['cod_generica'] . '  ';
                                $tabla = 'cfpd01_generica';
                            }
                            if ($vec_datos[$j]['cod_especifica'] != '') {
                                $sql_2 .='and cod_especifica = ' . $vec_datos[$j]['cod_especifica'] . '  ';
                                $tabla = 'cfpd01_especifica';
                            }
                            if ($vec_datos[$j]['cod_sub_espec'] != '') {
                                if ($vec_datos[$j]['cod_sub_espec'] == 0 && $vec_datos[$j]['cod_auxiliar'] == 0) {

                                } else {
                                    $sql_2.= 'and cod_sub_espec = ' . $vec_datos[$j]['cod_sub_espec'] . '  ';
                                    $tabla = 'cfpd01_sub_espec';
                                }//fin else
                            }


                            if ($vec_datos[$j]['cod_auxiliar'] != '' && $vec_datos[$j]['cod_auxiliar'] != 0) {
                                $sql_2 .='and cod_auxiliar = ' . $vec_datos[$j]['cod_auxiliar'] . '  ';
                                $tabla = 'cfpd01_auxiliar';
                            }
                            $data_aux_1 = $this->$tabla->findAll($sql_2, null, null, null);
                            foreach ($data_aux_1 as $datos_aux_1) {

                            }

                            $this->set('denominacion_' . $opcion . '', $datos_aux_1[$tabla]['descripcion']);
                        }//fin if
                    }//fin forj
                }//for
            }//fin else
        } else {

            if ($c_t_t == null) {
                $this->set('selecion_c_t', '');
                $this->set('c_t', '');
                $opcion = 'si';
            } else {
                $sql_c_t = "cod_tipo_transaccion=" . $c_t_t;
                if ($c_t_t == 1) {

                } else if ($c_t_t == 2) {
                    $sql_c_t .= " and uso_transaccion=6";
                }
                $this->set('selecion_c_t', '');
                if ($this->cnmd03_transaccion->findCount($sql_c_t) != 0) {
                    $consulta_c_t = $this->cnmd03_transaccion->generateList($sql_c_t, 'cod_transaccion ASC', null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.cod_transaccion');
                    $this->AddCero('c_t', $consulta_c_t);
                } else {
                    $this->set('c_t', 'vacio');
                }
                //$opcion = 'si';
            }
        }//fin else

        if ($boton == 'agregar') {
            $opcion = "agregar";
        }
        $this->set('entidad_federal', $this->Session->read('entidad_federal'));
        $this->set('agregar', $opcion);
        $this->set('boton', $boton);
        $this->set('data', $data);
        $this->set('denominacion', $denominacion);
        $this->set('selecion_c_t_t', $c_t_t);
        $this->set('selecion_c_t', $c_t);
    }

//fin function

    function eliminar($c_t_t = null, $c_t = null, $c_p = null, $boton = null) {
        $this->layout = "ajax";
        $sql = "DELETE  FROM  cnmd03_partidas  where cod_tipo_transaccion=" . $c_t_t . " and cod_transaccion=" . $c_t . " ";
        $this->cnmd03_partidas->execute($sql);
        $this->set("Message_existe", "El registro fu&eacute; eliminado con éxito");
        $this->index2($c_t_t, $c_t);
        $this->render("index2");
    }

//fin function

    function eliminar_uno($cod_tipo_transaccion = null, $cod_transaccion = null, $clasificacion_personal = null) {
        $this->layout = "ajax";
        $sql = "DELETE  FROM  cnmd03_partidas  where cod_tipo_transaccion=" . $cod_tipo_transaccion . " and cod_transaccion=" . $cod_transaccion . " and clasificacion_personal=" . $clasificacion_personal . " ";
        $this->cnmd03_partidas->execute($sql);
        $this->set("Message_existe", "El registro fu&eacute; eliminado con éxito");
        //$this->index2($cod_tipo_transaccion, $cod_transaccion);
        //$this->render("index2");
    }

//fin function

    function consulta($pag_num = null) {
        $this->layout = "ajax";

        $denominacion = '';
        $c_t_t = '';
        $c_t = '';
        $imprimir = 'si';
        $consulta = "";
        $pag_num_aux = "";
        $data = '';
        $cont_aux = "";
        $vec_datos[0]['cod_tipo_transaccion'] = '';

        if ($pag_num == null) {
            $pag_num = 1;
        }



        $nombre_opcion = array('1' => 'empleados', '2' => 'obreros', '3' => 'militares_profesionales', '4' => 'militares_no_profesionales', '5' => 'contratados_empleados', '6' => 'suplencias_empleados', '7' => 'jubilados_empleados', '8' => 'jubilados_obreros', '9' => 'pensionados_empleados', '10' => 'pensionados_obreros', '11' => 'dietas', '12' => 'comision_de_servicio', '13' => 'becas', '14' => 'ayudas', '15' => 'suplencias_obreros', '16' => 'contratados_obreros', '17' => 'altos_funcionarios', '18' => 'eleccion_popular');
        $data = $this->cnmd03_partidas->findAll(null, null, 'cod_tipo_transaccion, cod_transaccion, clasificacion_personal ASC');
        $data_aux = $data;

        for ($i = 1; $i <= 18; $i++) {
            $opcion = $nombre_opcion[$i];
            $this->AddCero('partida_' . $opcion . '', $this->cfpd01_partida->generateList('where cod_grupo =  ' . CE . ' and (cod_partida=1 or cod_partida=3 or cod_partida=7 or cod_partida=11)', 'cod_partida ASC', null, '{n}.cfpd01_partida.cod_partida', '{n}.cfpd01_partida.cod_partida'), '4');
            $this->set('generica_' . $opcion . '', '');
            $this->set('especifica_' . $opcion . '', '');
            $this->set('subespecifica_' . $opcion . '', '');
            $this->set('auxiliar_' . $opcion . '', '');
            $this->set('denominacion_' . $opcion . '', '');
        }//fin for

        $cont = 0;
        $cont_aux = 0;
        $aux_1 = 0;
        $aux_2 = 0;

        foreach ($data_aux as $datos) {
            $cont++;

            $vec_datos[$cont]['cod_tipo_transaccion'] = $datos['cnmd03_partidas']['cod_tipo_transaccion'];
            $vec_datos[$cont]['cod_transaccion'] = $datos['cnmd03_partidas']['cod_transaccion'];
            $vec_datos[$cont]['clasificacion_personal'] = $datos['cnmd03_partidas']['clasificacion_personal'];
            $vec_datos[$cont]['cod_partida'] = $datos['cnmd03_partidas']['cod_partida'];
            $vec_datos[$cont]['cod_generica'] = $datos['cnmd03_partidas']['cod_generica'];
            $vec_datos[$cont]['cod_especifica'] = $datos['cnmd03_partidas']['cod_especifica'];
            $vec_datos[$cont]['cod_sub_espec'] = $datos['cnmd03_partidas']['cod_sub_espec'];
            $vec_datos[$cont]['cod_auxiliar'] = $datos['cnmd03_partidas']['cod_auxiliar'];



            if ($aux_2 != $vec_datos[$cont]['cod_transaccion']) {
                $aux_2 = $vec_datos[$cont]['cod_transaccion'];
                $cont_aux++;
                if ($pag_num == $cont_aux) {
                    $pag_num_aux = $cont;
                }
            }
        }

        if ($cont != 0) {

            $totalPages_Recordset1 = $cont - 1;
            $totalPages_Recordset1 = abs($totalPages_Recordset1);
            $totalPages_Recordset1 = floor($totalPages_Recordset1);


//HAY QUE CAMBIAR EN EL GENERATE LISTE DE TODO LE QUE ESTA HACIA BABAJO VE QUE ESTAN INVERTIDAS O INTERCAMBIADAS POR EJEMPLO where cod_grupo =  4  and cod_partida = '.$vec_datos[$i]['cod_generica'].'



            if ($cont != 0) {


                for ($j = 1; $j <= $cont; $j++) {
                    $sql_2 = "";
                    $tabla = "";

                    for ($i = 1; $i <= 18; $i++) {
                        $opcion = $nombre_opcion[$i];

                        if ($vec_datos[$j]['clasificacion_personal'] == $i) {


                            $this->concatena($this->cfpd01_partida->generateList('where cod_grupo = ' . CE . ' and (cod_partida=1 or cod_partida=3 or cod_partida=7 or cod_partida=11) ', ' cod_partida ASC', null, '{n}.cfpd01_partida.cod_partida', '{n}.cfpd01_partida.descripcion'), 'partida_' . $opcion . '', '4');
                            $this->concatena($this->cfpd01_generica->generateList('where cod_grupo =  ' . CE . '  and cod_partida = ' . substr($vec_datos[$j]['cod_partida'], -2) . '', ' cod_partida ASC', null, '{n}.cfpd01_generica.cod_generica', '{n}.cfpd01_generica.descripcion'), 'generica_' . $opcion . '');
                            $this->concatena($this->cfpd01_especifica->generateList('where cod_grupo =  ' . CE . '  and cod_partida = ' . substr($vec_datos[$j]['cod_partida'], -2) . ' and cod_generica = ' . $vec_datos[$j]['cod_generica'] . '', ' cod_generica ASC', null, '{n}.cfpd01_especifica.cod_especifica', '{n}.cfpd01_especifica.descripcion'), 'especifica_' . $opcion . '');
                            $this->concatena($this->cfpd01_sub_espec->generateList('where cod_grupo =  ' . CE . '  and cod_partida = ' . substr($vec_datos[$j]['cod_partida'], -2) . ' and cod_generica = ' . $vec_datos[$j]['cod_generica'] . ' and cod_especifica = ' . $vec_datos[$j]['cod_especifica'] . '', ' cod_especifica ASC', null, '{n}.cfpd01_sub_espec.cod_sub_espec', '{n}.cfpd01_sub_espec.descripcion'), 'subespecifica_' . $opcion . '');
                            $this->concatena_auxiliar($this->cfpd01_auxiliar->generateList('where cod_grupo =  ' . CE . '  and cod_partida = ' . substr($vec_datos[$j]['cod_partida'], -2) . ' and cod_generica = ' . $vec_datos[$j]['cod_generica'] . ' and cod_especifica = ' . $vec_datos[$j]['cod_especifica'] . ' and cod_sub_espec = ' . $vec_datos[$j]['cod_sub_espec'] . '', ' cod_sub_espec ASC', null, '{n}.cfpd01_auxiliar.cod_auxiliar', '{n}.cfpd01_auxiliar.descripcion'), 'auxiliar_' . $opcion . '');



                            if ($vec_datos[$j]['cod_partida'] != '') {
                                $sql_2 .='cod_grupo = ' . CE . ' and cod_partida = ' . substr($vec_datos[$j]['cod_partida'], -2) . '  ';
                                $tabla = 'cfpd01_partida';
                            }
                            if ($vec_datos[$j]['cod_generica'] != '') {
                                $sql_2 .='and cod_generica = ' . $vec_datos[$j]['cod_generica'] . '  ';
                                $tabla = 'cfpd01_generica';
                            }
                            if ($vec_datos[$j]['cod_especifica'] != '') {
                                $sql_2 .='and cod_especifica = ' . $vec_datos[$j]['cod_especifica'] . '  ';
                                $tabla = 'cfpd01_especifica';
                            }
                            if ($vec_datos[$j]['cod_sub_espec'] != '') {
                                if ($vec_datos[$j]['cod_sub_espec'] == 0 && $vec_datos[$j]['cod_auxiliar'] == 0) {

                                } else {
                                    $sql_2.= 'and cod_sub_espec = ' . $vec_datos[$j]['cod_sub_espec'] . '  ';
                                    $tabla = 'cfpd01_sub_espec';
                                }//fin else
                            }



                            if ($vec_datos[$j]['cod_auxiliar'] != '' && $vec_datos[$j]['cod_auxiliar'] != 0) {
                                $sql_2 .='and cod_auxiliar = ' . $vec_datos[$j]['cod_auxiliar'] . '  ';
                                $tabla = 'cfpd01_auxiliar';
                            }
                            $data_aux_1 = $this->$tabla->findAll($sql_2, null, null, null);
                            foreach ($data_aux_1 as $datos_aux_1) {

                            }

                            if ($vec_datos[$pag_num_aux]['cod_tipo_transaccion'] == $vec_datos[$j]['cod_tipo_transaccion'] && $vec_datos[$pag_num_aux]['cod_transaccion'] == $vec_datos[$j]['cod_transaccion']) {
                                $this->set('denominacion_' . $opcion . '', $datos_aux_1[$tabla]['descripcion']);
                            }
                        }//fin if
                    }//fin forj
                }//for
            }//fin else


            $denominacion = $this->cnmd03_transaccion->findAll('cod_tipo_transaccion=' . $vec_datos[$pag_num_aux]['cod_tipo_transaccion'] . ' and cod_transaccion=' . $vec_datos[$pag_num_aux]['cod_transaccion'] . '', null, null, null);
            foreach ($denominacion as $denominacion_pre) {

            }
            $denominacion = $denominacion_pre['cnmd03_transaccion']['denominacion'];



            if ($pag_num != null) {
                $this->set('pagina_actual', $pag_num);
            }
        } else {
            $vec_datos[$pag_num_aux]['cod_tipo_transaccion'] = '';
            $vec_datos[$pag_num_aux]['cod_transaccion'] = '';
            $imprimir = 'no';
        }


        $this->set('denominacion', $denominacion);
        $this->set('entidad_federal', $this->Session->read('entidad_federal'));
        $this->set('data', $data);
        $this->set('agregar', 'no');
        $this->set('totalPages_Recordset1', $cont_aux);
        $this->set('imprimir', $imprimir);
        $this->set('consulta', $consulta);
        $this->set('selecion_c_t_t', $vec_datos[$pag_num_aux]['cod_tipo_transaccion']);
        $this->set('selecion_c_t', $vec_datos[$pag_num_aux]['cod_transaccion']);
    }

//fin function
}

//fin class
?>
