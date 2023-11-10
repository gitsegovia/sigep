<?php

/*
 * Proyecto: SIGEP
 * Archivo: cnmp05_datos_personales_controller.php
 * Fecha de creacion: 21/05/2008
 *
 * Creado por: Ing. Luis Alfredo Diaz Jaramillo
 * e-mail: ldiazjaramillo@gmail.com
 *
 * javascrip: js/cfpp02/cfpp02.js
 *
 */

class Cnmp15DatosPersonalesController extends AppController {

    var $uses = array('ccfd04_cierre_mes', 'Cnmd01', 'cnmd06_fichas', 'cnmd06_datos_personales', 'v_cnmd05', 'cugd02_institucion',
        'cugd02_dependencia', 'cnmd15_datos_personales', "datos_personales_super_busqueda");
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

    function beforeFilter() {
        $this->checkSession();
    }

    function SQLCA() {//sql para busqueda de codigos de arranque con y sin año
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

    
    
    function index() {
        $this->layout = "ajax";
        //pr($this->v_cnmd05->findAll($conditions = $this->condicion(), $fields = null, $order = null, $limit = null, $page = null, $recursive = null));
        //$lista_nomina = $this->v_cnmd05->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina ASC', $limit = null, '{n}.v_cnmd05.cod_tipo_nomina', '{n}.v_cnmd05.tipo_nomina');
        //$this->concatena($lista_nomina, 'lista_nomina');
        // $motivo_retiro = array('1'=>'Despido justificado', '2'=>'Despido injusticado', '3'=>'Retiro justificado', '4'=>'Renuncia', '5'=>'Jubilacion', '6'=>'Pensionado', '7'=>'Culminacion de contrato', '8'=>'Baja por propia solicitud', '9'=>'Fallecimiento', '10'=>'Baja por Expulsion', '11'=>'Remocion del cargo', '12'=>'Reduccion del Personal');
        $motivo_retiro = $this->ctipo_motivo_retiro();

        $this->set('motivo_retiro', $motivo_retiro);
        //pr($lista_nomina);

        $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
        $this->concatenaN($lista, 'lista_nomina');

        $this->Session->delete('fecha_session_desde_prestaciones');
        $this->Session->delete('fecha_session_hasta_prestaciones');

        if ($this->Session->read('cedula_pestana_prestaciones') == "") {
            $id = 0;
            $tipo_nomina = 0;
        } else {
            $id = $this->Session->read('cedula_pestana_prestaciones');
            $tipo_nomina = $this->Session->read('cod_tipo_nomina_prestaciones');
        }//fin else

        $this->set('cedula', "");
        $condicion = $this->condicion() . " and cod_tipo_nomina='" . $tipo_nomina . "'  ";
        $Tfilas = $this->cnmd15_datos_personales->findCount($condicion . " and cedula_identidad='" . $id . "' ");
        if ($Tfilas != 0) {
            $this->consulta_cedula($this->Session->read('cedula_pestana_prestaciones'));
            $this->render("consulta_cedula");
        } else {
            $this->set('ci', "");
            $this->set('pa', "");
            $this->set('sa', "");
            $this->set('pn', "");
            $this->set('sn', "");
        }//fin else

        $this->Session->delete('tipo_nomina');
    }

//fin function

    function regresar() {

        $this->layout = "ajax";
        $this->Session->write('cedula_pestana_prestaciones', "");

        echo"<script>
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_10').style.display='none';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='none';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_12').style.display='none';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_13').style.display='none';
						document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_14').style.display='none';
			          </script>";

        $this->index();
        $this->render("index");
    }

//fin function

    function show_cod_nomina($cod_tipo_nomina = null) {
        $this->layout = "ajax";
        if ($cod_tipo_nomina != null) {
            $this->set('cod_tipo_nomina', $cod_tipo_nomina);
            $this->Session->write('tipo_nomina', $cod_tipo_nomina);
        }
    }

    function limpiar_datos($cod_tipo_nomina = null) {
        $this->layout = "ajax";
        $motivo_retiro = $this->ctipo_motivo_retiro();
        $this->set('motivo_retiro', $motivo_retiro);
        $this->set('cod_tipo_nomina', $cod_tipo_nomina);
    }

//fin function

    function show_deno_nomina($cod_tipo_nomina = null) {
        $this->layout = "ajax";
        if ($cod_tipo_nomina != null) {
            $denominacion = $this->Cnmd01->field('denominacion', $conditions = $this->condicion() . " and cod_tipo_nomina='$cod_tipo_nomina'", $order = "cod_tipo_nomina ASC");
            $this->set('denominacion', $denominacion);
        }
    }

    function sel_cod_cargo($cod_tipo_nomina = null) {
        $this->layout = "ajax";
        if ($cod_tipo_nomina != null) {
            $lista_cargo = $this->cnmd06_fichas->generateList($conditions = $this->condicion() . " and cod_tipo_nomina='$cod_tipo_nomina'", $order = null, $limit = null, '{n}.cnmd06_fichas.cod_cargo', '{n}.cnmd06_fichas.cod_cargo');
            $this->set('lista_cargo', $lista_cargo);
            $this->set('cod_tipo_nomina', $cod_tipo_nomina);
            //pr($lista_cargo);
        }
    }

    function sel_cod_ficha($cod_tipo_nomina = null, $cod_cargo = null) {
        $this->layout = "ajax";
        if ($cod_tipo_nomina != null && $cod_cargo != null) {
            $lista_ficha = $this->cnmd06_fichas->generateList($conditions = $this->condicion() . " and cod_tipo_nomina='$cod_tipo_nomina' and cod_cargo='$cod_cargo'", $order = null, $limit = null, '{n}.cnmd06_fichas.cod_ficha', '{n}.cnmd06_fichas.cod_ficha');
            //pr($lista_ficha);
            $this->set('lista_ficha', $lista_ficha);
            $this->set('cod_tipo_nomina', $cod_tipo_nomina);
            $this->set('cod_cargo', $cod_cargo);
        }
    }

// Fecha en formato dd/mm/yyyy o dd-mm-yyyy retorna la diferencia en dias

    function restaFechas($dFecIni, $dFecFin) {
        $dFecIni = str_replace("-", "", $dFecIni);
        $dFecIni = str_replace("/", "", $dFecIni);
        $dFecFin = str_replace("-", "", $dFecFin);
        $dFecFin = str_replace("/", "", $dFecFin);

        ereg("([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})", $dFecIni, $aFecIni);
        ereg("([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})", $dFecFin, $aFecFin);

        $date1 = mktime(0, 0, 0, $aFecIni[2], $aFecIni[1], $aFecIni[3]);
        $date2 = mktime(0, 0, 0, $aFecFin[2], $aFecFin[1], $aFecFin[3]);

        return round(($date2 - $date1) / (60 * 60 * 24));
    }

    function get_edad($fecha_inicio, $fecha_fin, $modelo = 'cnmd06_fichas') {
        $age = $this->$modelo->execute("SELECT age('$fecha_inicio', '$fecha_fin')");
        $edad = $age[0][0]['age'];
        return $edad;
    }

    function f_cedula($cod_tipo_nomina = null) {
        $this->layout = "ajax";
        if ($cod_tipo_nomina != null) {
            $this->set('cod_tipo_nomina', $cod_tipo_nomina);
        }
    }

    function cedula($cod_tipo_nomina = null, $cod_cargo = null, $cod_ficha = null) {
        $this->layout = "ajax";
        //$this->get_edad(date('Y-m-d'), '1983-12-23');
        if ($cod_tipo_nomina != null && $cod_cargo != null && $cod_ficha != null) {
            $condicion = $this->condicion() . " and cod_tipo_nomina='$cod_tipo_nomina' and cod_cargo='$cod_cargo' and cod_ficha='$cod_ficha'";
            $cedula = $this->cnmd06_fichas->field('cedula_identidad', $condicion, $order = null);
            $this->set('cedula', $cedula);
            //echo $cedula;
        }
    }

    function datos_personales($cod_tipo_nomina = null, $cedula = null) {
        $this->layout = "ajax";
        $motivo_retiro = $this->ctipo_motivo_retiro();
        $this->set('motivo_retiro2', $motivo_retiro);
        if ($cod_tipo_nomina != null && $cedula != null) {
            $condicion = $this->condicion() . " and cod_tipo_nomina='$cod_tipo_nomina'";
            $cod_presi = $this->Session->read('SScodpresi');
            $cod_entidad = $this->Session->read('SScodentidad');
            $cod_tipo_inst = $this->Session->read('SScodtipoinst');
            $cod_inst = $this->Session->read('SScodinst');
            $cod_dep = $this->Session->read('SScoddep');
            $cont_cedula = 0;

            //$cedula = $this->cnmd06_fichas->field('cedula_identidad', $condicion, $order=null);
            $this->set('cedula', $cedula);
            $cont_cedula = $this->cnmd06_datos_personales->findCount("cedula_identidad='$cedula'");
            if ($cont_cedula != 0) {
                $datos_personales = $this->cnmd06_datos_personales->findAll($conditions = "cedula_identidad='$cedula'", $fields = 'primer_apellido, segundo_apellido, primer_nombre, segundo_nombre', $order = null, $limit = 1, $page = null, $recursive = null);
                $this->set('datos_personales', $datos_personales);
                $datos_ficha = $this->cnmd06_fichas->findAll($conditions = $condicion . " and cedula_identidad='$cedula'", $fields = 'fecha_ingreso, fecha_retiro, motivo_retiro, fecha_terminacion_contrato, cod_cargo, cod_ficha', $order = null, $limit = 1, $page = null, $recursive = null);
                $fecha_egreso = "1900-01-01";

                $cont_cnmd15_datos_personales = $this->cnmd15_datos_personales->findCount($condicion . " and cedula_identidad='$cedula'");

                $codtiponimina = $this->cnmd15_datos_personales->findAll($conditions = $this->condicion() . " and cedula_identidad='$cedula'", $fields = 'cod_tipo_nomina', $order = null, $limit = null, $page = null, $recursive = null);
                //$codtiponimina = $this->cnmd15_datos_personales->field('cod_tipo_nomina', $conditions = $condicion." and cedula_identidad='$cedula'", $order =null);
                if (!empty($codtiponimina)) {
                    $codinomia = "";
                    foreach ($codtiponimina as $cod_nominas) {
                        $codinomia .= $this->mascara_tres($cod_nominas['cnmd15_datos_personales']['cod_tipo_nomina']) . ". ";
                    }
                }

                if ($cont_cnmd15_datos_personales != 0) {
                    $this->Session->write('cedula_pestana_prestaciones', $cedula);
                    $this->Session->write('cod_tipo_nomina_prestaciones', $cod_tipo_nomina);
                    $this->consulta_cedula2($this->Session->read('cedula_pestana_prestaciones'));
                    $this->render("consulta_cedula2");
                    echo "<script>document.getElementById('save').disabled=true;</script>";
                } else {
                    if (isset($codinomia) && $codinomia != null && $codinomia != "") {
                        // echo "<script>fun_msj('El Trabajador se Encuentra Registrado en la N&oacute;mina: ".$codinomi."');</script>";

                        echo "<script type='text/javascript'> var_confirmacion = confirm('\\n\\t" . $datos_personales[0]['cnmd06_datos_personales']['primer_nombre'] . " " . " " . $datos_personales[0]['cnmd06_datos_personales']['segundo_nombre'] . " " . $datos_personales[0]['cnmd06_datos_personales']['primer_apellido'] . " " . $datos_personales[0]['cnmd06_datos_personales']['segundo_apellido'] . " \\n\\nSE ENCUENTRA REGISTRADO EN LA(S) NOMINA(S): " . $codinomia . " ¿DESEA CONTINUAR?');
                                		if(var_confirmacion==true){ document.getElementById('save').disabled=false; }
                                		else{ document.getElementById('save').disabled=true; } </script>";

                        if (!empty($datos_ficha[0]['cnmd06_fichas']['cod_ficha'])) {
                            $cod_ficha = $datos_ficha[0]['cnmd06_fichas']['cod_ficha'];
                            $cod_cargo = $datos_ficha[0]['cnmd06_fichas']['cod_cargo'];
                            $datos_institucion = $this->v_cnmd05->findAll($conditions = $this->condicion() . " and cod_tipo_nomina='$cod_tipo_nomina' and cod_cargo='$cod_cargo'", $fields = 'denominacion_clase', $order = null, $limit = 1, $page = null, $recursive = null);
                            $this->set('cargo', $datos_institucion[0]['v_cnmd05']['denominacion_clase']);
                            $this->set('datos_ficha', $datos_ficha);
                            $institucion = $this->cugd02_institucion->field('denominacion', $conditions_geo = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst'", $order = null);
                            $dependencia = $this->cugd02_dependencia->field('denominacion', $conditions = $conditions_geo . " and cod_dependencia='$cod_dep'", $order = null);
                            $this->set('institucion', $institucion);
                            $this->set('dependencia', $dependencia);
                            //pr($datos_ficha);
                            $fecha_ingreso = $datos_ficha[0]['cnmd06_fichas']['fecha_ingreso'];
                            $fecha_egreso = $datos_ficha[0]['cnmd06_fichas']['fecha_retiro'];


                            $this->set('cont_cedula', $cont_cedula);
                        } else {
                            $this->set('cont_cedula', 0);
                            echo"<script>
								            document.getElementById('primer_apellido').value='" . $datos_personales[0]['cnmd06_datos_personales']['primer_apellido'] . "';
								            document.getElementById('segundo_apellido').value='" . $datos_personales[0]['cnmd06_datos_personales']['segundo_apellido'] . "';
								            document.getElementById('primer_nombre').value='" . $datos_personales[0]['cnmd06_datos_personales']['primer_nombre'] . "';
								            document.getElementById('segundo_nombre').value='" . $datos_personales[0]['cnmd06_datos_personales']['segundo_nombre'] . "';
								          </script>";
                        }//fin else
                        //echo $fecha_ingreso;
                        if (isset($fecha_ingreso)) {
                            $this->Session->write('fecha_session_desde_prestaciones', $fecha_ingreso);
                        }


                        if ($fecha_egreso != "1900-01-01") {
                            $tiempo_servicio = $this->get_edad($fecha_egreso, $fecha_ingreso);
                            $this->Session->write('fecha_session_hasta_prestaciones', $fecha_egreso);
                            $this->Session->write('fecha_session_desde_prestaciones', $fecha_ingreso);
                            $tiempo_servicio = explode(' ', $tiempo_servicio);
                            $this->set('tiempo_servicio', $tiempo_servicio);
                        } else {
                            $this->set('tiempo_servicio', 1);
                        }//fin else
                    } else {

                        echo "<script>document.getElementById('save').disabled=false;</script>";

                        if (!empty($datos_ficha[0]['cnmd06_fichas']['cod_ficha'])) {
                            $cod_ficha = $datos_ficha[0]['cnmd06_fichas']['cod_ficha'];
                            $cod_cargo = $datos_ficha[0]['cnmd06_fichas']['cod_cargo'];
                            $datos_institucion = $this->v_cnmd05->findAll($conditions = $this->condicion() . " and cod_tipo_nomina='$cod_tipo_nomina' and cod_cargo='$cod_cargo'", $fields = 'denominacion_clase', $order = null, $limit = 1, $page = null, $recursive = null);
                            $this->set('cargo', $datos_institucion[0]['v_cnmd05']['denominacion_clase']);
                            $this->set('datos_ficha', $datos_ficha);
                            $institucion = $this->cugd02_institucion->field('denominacion', $conditions_geo = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst'", $order = null);
                            $dependencia = $this->cugd02_dependencia->field('denominacion', $conditions = $conditions_geo . " and cod_dependencia='$cod_dep'", $order = null);
                            $this->set('institucion', $institucion);
                            $this->set('dependencia', $dependencia);
                            //pr($datos_ficha);
                            $fecha_ingreso = $datos_ficha[0]['cnmd06_fichas']['fecha_ingreso'];
                            $fecha_egreso = $datos_ficha[0]['cnmd06_fichas']['fecha_retiro'];


                            $this->set('cont_cedula', $cont_cedula);
                        } else {
                            $this->set('cont_cedula', 0);
                            echo"<script>
								            document.getElementById('primer_apellido').value='" . $datos_personales[0]['cnmd06_datos_personales']['primer_apellido'] . "';
								            document.getElementById('segundo_apellido').value='" . $datos_personales[0]['cnmd06_datos_personales']['segundo_apellido'] . "';
								            document.getElementById('primer_nombre').value='" . $datos_personales[0]['cnmd06_datos_personales']['primer_nombre'] . "';
								            document.getElementById('segundo_nombre').value='" . $datos_personales[0]['cnmd06_datos_personales']['segundo_nombre'] . "';
								          </script>";
                        }//fin else
                        //echo $fecha_ingreso;
                        if (isset($fecha_ingreso)) {
                            $this->Session->write('fecha_session_desde_prestaciones', $fecha_ingreso);
                        }


                        if ($fecha_egreso != "1900-01-01") {
                            $tiempo_servicio = $this->get_edad($fecha_egreso, $fecha_ingreso);
                            $this->Session->write('fecha_session_hasta_prestaciones', $fecha_egreso);
                            $this->Session->write('fecha_session_desde_prestaciones', $fecha_ingreso);
                            $tiempo_servicio = explode(' ', $tiempo_servicio);
                            $this->set('tiempo_servicio', $tiempo_servicio);
                        } else {
                            $this->set('tiempo_servicio', 1);
                        }//fin else
                    }
                }//fin else




                /* echo"<script>
                  document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_expediente"]."_10').style.display='block';
                  document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_expediente"]."_11').style.display='block';
                  document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_expediente"]."_12').style.display='block';
                  document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_expediente"]."_13').style.display='block';
                  document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal".$_SESSION["rand_expediente"]."_14').style.display='block';
                  </script>";

                  $this->Session->write('cedula_pestana_prestaciones',  $cedula);
                  $this->Session->write('cod_dep_prestaciones',         $cod_dep);
                  $this->Session->write('cod_tipo_nomina_prestaciones', $cod_tipo_nomina);
                  $this->Session->write('cod_cargo_prestaciones',       $cod_cargo);
                  $this->Session->write('cod_ficha_prestaciones',       $cod_ficha); */
            } else {

                $this->set('cont_cedula', $cont_cedula);

                echo"<script>
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_10').style.display='none';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='none';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_12').style.display='none';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_13').style.display='none';
						document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_14').style.display='none';
			          </script>";

                $this->set('msg_error', 'LA CEDULA NO FUE ENCONTRADA EN LA FICHA DE PERSONAL');
            }
            //pr($datos_personales);
        }


        $this->set('cod_tipo_nomina', $cod_tipo_nomina);
    }

//fin function

    function actualiza_deno_cargo_inicio($denom_cargo_inic = null) {
        $this->layout = "ajax";
        if ($denom_cargo_inic != null) {
            echo "<script>
                    document.getElementById('id_deno_cargo_inicio').value= '" . $denom_cargo_inic . "';
         	</script>";
        } else {
            echo "<script>
                    document.getElementById('id_deno_cargo_inicio').value= '';
         	</script>";
        }
    }

    function guardar() {
        $this->layout = "ajax";
        if (!empty($this->data)) {
            $cod_presi = $this->Session->read('SScodpresi');
            $cod_entidad = $this->Session->read('SScodentidad');
            $cod_tipo_inst = $this->Session->read('SScodtipoinst');
            $cod_inst = $this->Session->read('SScodinst');
            $cod_dep = $this->Session->read('SScoddep');
            $cod_tipo_nomina = $this->data['cnmp15_datos_personales']['cod_nomina'];
            $cod_cargo = $this->data['cnmp15_datos_personales']['cod_cargo'];
            $cod_ficha = $this->data['cnmp15_datos_personales']['cod_ficha'];
            $cedula = $this->data['cnmp15_datos_personales']['cedula'];
            $primer_apellido = $this->data['cnmp15_datos_personales']['primer_apellido'];
            $segundo_apellido = $this->data['cnmp15_datos_personales']['segundo_apellido'];
            $primer_nombre = $this->data['cnmp15_datos_personales']['primer_nombre'];
            $segundo_nombre = $this->data['cnmp15_datos_personales']['segundo_nombre'];
            $institucion = $this->data['cnmp15_datos_personales']['institucion'];
            $dependencia = $this->data['cnmp15_datos_personales']['dependencia'];
            $fecha_ingreso = $this->data['cnmp15_datos_personales']['fecha_ingreso'];
            $fecha_egreso = $this->data['cnmp15_datos_personales']['fecha_egreso'];
            $motivo_retiro = $this->data['cnmp15_datos_personales']['motivo_retiro'];
            $preaviso = $this->data['cnmp15_datos_personales']['preaviso'];
            $cobro_prestaciones = $this->data['cnmp15_datos_personales']['cobro_prestaciones'];
            $cargo = $this->data['cnmp15_datos_personales']['cargo'];
            $cargo_inicio = $this->data['cnmp15_datos_personales']['cargo_inicio'] != "" ? $this->data['cnmp15_datos_personales']['cargo_inicio'] : $cargo;
            $informacion_desemp = $this->data['cnmp15_datos_personales']['informacion'] != "" ? $this->data['cnmp15_datos_personales']['informacion'] : "Ejercio el cargo satisfactoriamente.";
            $observaciones = $this->data['cnmp15_datos_personales']['observaciones'] != "" ? $this->data['cnmp15_datos_personales']['observaciones'] : "Se elabora FP-023 para pago de prestaciones sociales";
            $informacion_desemp = str_replace("\n", " ", $informacion_desemp);
            $observaciones = str_replace("\n", " ", $observaciones);

            //echo $cod_tipo_nomina.' - '.$cod_cargo.' - '.$cod_ficha.' - '.$cedula.' - '.$primer_apellido.' - '.$segundo_apellido.' - '.$primer_nombre.' - '.$segundo_nombre.' - '.$institucion.' - '.$dependencia.' - '.$fecha_ingreso.' - '.$fecha_egreso.' - '.$motivo_retiro.' - '.$preaviso.' - '.$cobro_prestaciones;
            //pr($this->data);
            $sql_insert_datos_personales = "INSERT INTO cnmd15_datos_personales VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_tipo_nomina', '$cod_cargo', '$cod_ficha', '$cedula', '$primer_apellido', '$segundo_apellido', '$primer_nombre', '$segundo_nombre', '$cargo', '$institucion', '$dependencia', '$fecha_ingreso', '$fecha_egreso', '$motivo_retiro', '$preaviso', '$cobro_prestaciones', '$cargo_inicio', '$informacion_desemp', '$observaciones')";
            $sw1 = $this->cnmd15_datos_personales->execute("BEGIN; " . $sql_insert_datos_personales);
            if ($sw1 > 1) {
                $this->cnmd15_datos_personales->execute("COMMIT;");
                $this->set('msg', 'LOS DATOS PERSONALES FUERON REGISTRADOS CON EXITO');

                echo"<script>
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_10').style.display='block';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='block';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_12').style.display='block';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_13').style.display='block';
						document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_14').style.display='block';
			          </script>";

                $this->Session->write('cedula_pestana_prestaciones', $cedula);
                $this->Session->write('cod_dep_prestaciones', $cod_dep);
                $this->Session->write('cod_tipo_nomina_prestaciones', $cod_tipo_nomina);
                $this->Session->write('cod_cargo_prestaciones', $cod_cargo);
                $this->Session->write('cod_ficha_prestaciones', $cod_ficha);
            } else {

                echo"<script>
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_10').style.display='none';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='none';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_12').style.display='none';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_13').style.display='none';
						document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_14').style.display='none';
			          </script>";
                $this->cnmd15_datos_personales->execute("ROLLBACK;");
                $this->set('msg_error', 'NO SE LOGRO REGISTAR LOS DATOS - POR FAVOR INTENTE DE NUEVO');
            }
        } else {
            echo "faltan datos";
        }

        $this->data = array();

        //$this->set('userTable', $this->requestAction('/cnmp15_devengado/', array('return')));

        $this->index();
        //$this->render('index');
    }

//fin function

    function buscar_vista_2($var1 = null) {

        $this->layout = "ajax";
        $this->set("cod_tipo_nomina", $var1);
        $this->Session->delete('pista');
        $this->set("opcion", $var1);
        $this->Session->write('pista_opcion', 2);
    }

//fin function

    function buscar_por_pista_2($var1 = null, $var2 = null, $var3 = null) {
        $this->layout = "ajax";
        $sql_like = "";
        $tipo_nomina = $this->Session->read('tipo_nomina');
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');

        if ($var3 == null) {

            $var2 = strtoupper($var2);

            $this->Session->write('pista', $var2);

            $var_like = $var2;

            $sql_like = $this->busca_separado(array('cedula_identidad', 'primer_apellido', 'segundo_apellido', 'primer_nombre', 'segundo_nombre'), $var_like);
          
            
            $Tfilas = $this->cnmd15_datos_personales->findCount($this->SQLCA()."and ".$sql_like . " and cod_tipo_nomina='$tipo_nomina' "."and ".$this->SQLCA());
            if ($Tfilas != 0) {
                $pagina = 1;
                $Tfilas = (int) ceil($Tfilas / 100);
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $this->set('total_paginas', $Tfilas);
                $this->set('pagina_actual', $pagina);
                $this->set('ultimo', $Tfilas);
                $datos_filas = $this->cnmd15_datos_personales->findAll($this->SQLCA()."and ".$sql_like . " and cod_tipo_nomina='$tipo_nomina'", null, "primer_nombre,primer_apellido ASC", 100, 1, null);
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
            $var_like = $var22;
            $sql_like = $this->busca_separado(array('cedula_identidad', 'primer_apellido', 'segundo_apellido', 'primer_nombre', 'segundo_nombre'), $var_like);

            $Tfilas = $this->cnmd15_datos_personales->findCount($this->SQLCA().$sql_like . " and cod_tipo_nomina='$tipo_nomina'");

            if ($Tfilas != 0) {
                $pagina = 1;
                $Tfilas = (int) ceil($Tfilas / 100);
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $this->set('total_paginas', $Tfilas);
                $this->set('pagina_actual', $pagina);
                $this->set('ultimo', $Tfilas);
                $datos_filas = $this->cnmd15_datos_personales->findAll($this->SQLCA().$sql_like . " and cod_tipo_nomina='$tipo_nomina'", null, "primer_nombre,primer_apellido ASC", 100, 1, null);
                $this->set("datosFILAS", $datos_filas);
                $this->set('siguiente', $pagina + 1);
                $this->set('anterior', $pagina - 1);
                $this->bt_nav($Tfilas, $pagina);
            } else {
                $this->set("datosFILAS", '');
            }
        }//fin else


        $this->set("cod_tipo_nomina", $tipo_nomina);
        $this->set("opcion", $var1);
    }

//fin function

    function buscar_vista_1($var1 = null) {

        $this->layout = "ajax";
        $this->set("cod_tipo_nomina", $var1);
        $this->Session->delete('pista');
        $this->set("opcion", $var1);
        $this->Session->write('pista_opcion', 2);
    }

//fin function

    function buscar_por_pista($var1 = null, $var2 = null, $var3 = null) {
        $this->layout = "ajax";
        $sql_like = "";
        $tipo_nomina = $this->Session->read('tipo_nomina');
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        if ($var3 == null) {
            $var2 = strtoupper($var2);
            $this->Session->write('pista', $var2);
            $var_like = $var2;
            $sql_like = $this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion'));
            $Tfilas = $this->datos_personales_super_busqueda->findCount($sql_like);
            if ($Tfilas != 0) {
                $pagina = 1;
                $Tfilas = (int) ceil($Tfilas / 100);
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $this->set('total_paginas', $Tfilas);
                $this->set('pagina_actual', $pagina);
                $this->set('ultimo', $Tfilas);
                $datos_filas = $this->datos_personales_super_busqueda->findAll($sql_like, null, "primer_nombre,primer_apellido ASC", 100, 1, null);
                $sql = "";
                foreach ($datos_filas as $ve) {
                    if ($sql == "") {
                        $sql .= "    a.cedula_identidad = '" . $ve["datos_personales_super_busqueda"]["cedula_identidad"] . "' ";
                    } else {
                        $sql .= " or a.cedula_identidad = '" . $ve["datos_personales_super_busqueda"]["cedula_identidad"] . "'  ";
                    }
                }//fin foreach
                $dato_a = $this->datos_personales_super_busqueda->execute("
                                    SELECT
                                          a.cod_presi,
										  a.cod_entidad,
										  a.cod_tipo_inst,
										  a.cod_inst,
										  a.cod_dep,
										  a.cod_tipo_nomina,
										  a.cod_cargo,
										  a.cod_ficha,
										  a.cedula_identidad,
										  a.condicion_actividad
								     FROM
								           cnmd06_fichas a,
								           cnmd05        b
								     WHERE
								     	  a.cod_presi         =  '" . $cod_presi . "'      and
										  a.cod_entidad       =  '" . $cod_entidad . "'    and
										  a.cod_tipo_inst     =  '" . $cod_tipo_inst . "'  and
										  a.cod_inst          =  '" . $cod_inst . "'       and
										  a.cod_dep           =  '" . $cod_dep . "'        and
										  a.cod_tipo_nomina   =  '" . $tipo_nomina . "'    and
								          b.cod_presi         =  a.cod_presi           and
										  b.cod_entidad       =  a.cod_entidad         and
										  b.cod_tipo_inst     =  a.cod_tipo_inst       and
										  b.cod_inst          =  a.cod_inst            and
										  b.cod_dep           =  a.cod_dep             and
										  b.cod_tipo_nomina   =  a.cod_tipo_nomina     and
										  b.cod_cargo         =  a.cod_cargo           and ( " . $sql . " ) ");



                $this->set("datosFILAS", $datos_filas);
                $this->set('siguiente', $pagina + 1);
                $this->set('anterior', $pagina - 1);
                $this->bt_nav($Tfilas, $pagina);
            } else {
                $this->set("datosFILAS", '');
            }
            $this->set("dato_a", $dato_a);
        } else {
            $var22 = $this->Session->read('pista');
            $var22 = strtoupper($var22);
            $var_like = $var22;
            $sql_like = $this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion'));
            $Tfilas = $this->datos_personales_super_busqueda->findCount($sql_like);
            if ($Tfilas != 0) {
                $pagina = $var3;
                $Tfilas = (int) ceil($Tfilas / 100);
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $this->set('total_paginas', $Tfilas);
                $this->set('pagina_actual', $pagina);
                $this->set('ultimo', $Tfilas);
                $datos_filas = $this->datos_personales_super_busqueda->findAll($sql_like, null, "primer_nombre,primer_apellido ASC", 100, $pagina, null);
                $sql = "";
                foreach ($datos_filas as $ve) {
                    if ($sql == "") {
                        $sql .= "    a.cedula_identidad = '" . $ve["datos_personales_super_busqueda"]["cedula_identidad"] . "' ";
                    } else {
                        $sql .= " or a.cedula_identidad = '" . $ve["datos_personales_super_busqueda"]["cedula_identidad"] . "'  ";
                    }
                }//fin foreach
                $dato_a = $this->datos_personales_super_busqueda->execute("
					                                    SELECT
					                                          a.cod_presi,
															  a.cod_entidad,
															  a.cod_tipo_inst,
															  a.cod_inst,
															  a.cod_dep,
															  a.cod_tipo_nomina,
															  a.cod_cargo,
															  a.cod_ficha,
															  a.cedula_identidad,
															  a.condicion_actividad
													     FROM
													           cnmd06_fichas a,
													           cnmd05        b
													     WHERE
													     	  a.cod_presi         =  '" . $cod_presi . "'      and
															  a.cod_entidad       =  '" . $cod_entidad . "'    and
															  a.cod_tipo_inst     =  '" . $cod_tipo_inst . "'  and
															  a.cod_inst          =  '" . $cod_inst . "'       and
															  a.cod_dep           =  '" . $cod_dep . "'        and
															  a.cod_tipo_nomina   =  '" . $tipo_nomina . "'    and
													          b.cod_presi         =  a.cod_presi           and
															  b.cod_entidad       =  a.cod_entidad         and
															  b.cod_tipo_inst     =  a.cod_tipo_inst       and
															  b.cod_inst          =  a.cod_inst            and
															  b.cod_dep           =  a.cod_dep             and
															  b.cod_tipo_nomina   =  a.cod_tipo_nomina     and
															  b.cod_cargo         =  a.cod_cargo           and ( " . $sql . " ) ");
                $this->set("datosFILAS", $datos_filas);
                $this->set('siguiente', $pagina + 1);
                $this->set('anterior', $pagina - 1);
                $this->bt_nav($Tfilas, $pagina);
                $this->set("dato_a", $dato_a);
            } else {
                $this->set("datosFILAS", '');
            }
        }//fin else


        $this->set("cod_tipo_nomina", $tipo_nomina);
        $this->set("opcion", $var1);
    }

//fin function

    function funcion($var1 = null, $var2 = null, $var3 = null) {

        $this->layout = "ajax";
    }

//fin function

    function consulta_buscar_persona($pagina = null, $persona1 = null, $persona2 = null, $desactiva_nav = null) {
        $this->layout = "ajax";
        $motivo_retiro = $this->ctipo_motivo_retiro();
        $this->set('motivo_retiro', $motivo_retiro);

        if (isset($pagina)) {
            $pagina = $pagina;
        } else {
            $pagina = 1;
        }//fin else

        $Tfilas = $this->cnmd15_datos_personales->findCount($this->condicion());
        if ($Tfilas != 0) {
            $this->set('pag_cant', $pagina . '/' . $Tfilas);
            $this->set('ultimo', $Tfilas);
            $data = $this->cnmd15_datos_personales->findAll($this->condicion() . " and cod_tipo_nomina='" . $persona1 . "'  and cedula_identidad='" . $persona2 . "' ", null, 'cedula_identidad ASC', 1, $pagina, null);
            $this->set('data', $data);
            $cedula_identidad = $data[0]['cnmd15_datos_personales']['cedula_identidad'];
            $cod_dep = $data[0]['cnmd15_datos_personales']['cod_dep'];
            $cod_tipo_nomina = $data[0]['cnmd15_datos_personales']['cod_tipo_nomina'];
            $cod_cargo = $data[0]['cnmd15_datos_personales']['cod_cargo'];
            $cod_ficha = $data[0]['cnmd15_datos_personales']['cod_ficha'];
            $fecha_ingreso = $data[0]['cnmd15_datos_personales']['fecha_ingreso'];
            $fecha_egreso = $data[0]['cnmd15_datos_personales']['fecha_egreso'];

            $tiempo_servicio = $this->get_edad($fecha_egreso, $fecha_ingreso);
            $tiempo_servicio = explode(' ', $tiempo_servicio);
            $this->set('tiempo_servicio', $tiempo_servicio);

            $this->Session->write('fecha_session_hasta_prestaciones', $fecha_egreso);
            $this->Session->write('fecha_session_desde_prestaciones', $fecha_ingreso);

            $denominacion = $this->Cnmd01->field('denominacion', $conditions = $this->condicion() . " and cod_tipo_nomina='$cod_tipo_nomina'", $order = "cod_tipo_nomina ASC");
            $this->set('denominacion_nomina', $denominacion);

            $this->set('siguiente', $pagina + 1);
            $this->set('anterior', $pagina - 1);
            $this->bt_nav($Tfilas, $pagina);

            $this->Session->write('cedula_pestana_prestaciones', $cedula_identidad);
            $this->Session->write('cod_dep_prestaciones', $cod_dep);
            $this->Session->write('cod_tipo_nomina_prestaciones', $cod_tipo_nomina);
            $this->Session->write('cod_cargo_prestaciones', $cod_cargo);
            $this->Session->write('cod_ficha_prestaciones', $cod_ficha);

            echo"<script>
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_10').style.display='block';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='block';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_12').style.display='block';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_13').style.display='block';
						document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_14').style.display='block';
			          </script>";
        } else {
            echo"<script>
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_10').style.display='none';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='none';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_12').style.display='none';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_13').style.display='none';
						document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_14').style.display='none';
			          </script>";

            $this->set('COMPROMISO', '');
            $this->set('errorMessage', 'No se encontrar&oacute;n datos');
        }

        if ($desactiva_nav != null && $desactiva_nav == "si") {

            $this->set('desactiva_nav', $desactiva_nav);

            /*
              echo "<script>
              document.getElementById('nav').style.display='none';
              </script>"; */
        }

        $this->render('consulta');
    }

    function consulta_cedula() {


        if ($this->Session->read('cedula_pestana_prestaciones') == "") {
            $id = 0;
        } else {
            $id = $this->Session->read('cedula_pestana_prestaciones');
        }//fin else

        $this->layout = "ajax";
        $motivo_retiro = $this->ctipo_motivo_retiro();
        $this->set('motivo_retiro', $motivo_retiro);

        if (isset($pagina)) {
            $pagina = $pagina;
        } else {
            $pagina = 1;
        }//fin else

        $condicion = $this->condicion() . " and cod_tipo_nomina='" . $this->Session->read('cod_tipo_nomina_prestaciones') . "'  ";
        $Tfilas = $this->cnmd15_datos_personales->findCount($condicion . " and cedula_identidad='" . $id . "'");
        if ($Tfilas != 0) {
            $this->set('pag_cant', $pagina . '/' . $Tfilas);
            $this->set('ultimo', $Tfilas);
            $data = $this->cnmd15_datos_personales->findAll($condicion . " and cedula_identidad='" . $id . "'", null, 'cedula_identidad ASC', 1, $pagina, null);
            $this->set('data', $data);
            $cedula_identidad = $data[0]['cnmd15_datos_personales']['cedula_identidad'];
            $cod_dep = $data[0]['cnmd15_datos_personales']['cod_dep'];
            $cod_tipo_nomina = $data[0]['cnmd15_datos_personales']['cod_tipo_nomina'];
            $cod_cargo = $data[0]['cnmd15_datos_personales']['cod_cargo'];
            $cod_ficha = $data[0]['cnmd15_datos_personales']['cod_ficha'];
            $fecha_ingreso = $data[0]['cnmd15_datos_personales']['fecha_ingreso'];
            $fecha_egreso = $data[0]['cnmd15_datos_personales']['fecha_egreso'];

            $tiempo_servicio = $this->get_edad($fecha_egreso, $fecha_ingreso);
            $this->Session->write('fecha_session_hasta_prestaciones', $fecha_egreso);
            $this->Session->write('fecha_session_desde_prestaciones', $fecha_ingreso);
            $tiempo_servicio = explode(' ', $tiempo_servicio);
            $this->set('tiempo_servicio', $tiempo_servicio);

            $denominacion = $this->Cnmd01->field('denominacion', $conditions = $this->condicion() . " and cod_tipo_nomina='$cod_tipo_nomina'", $order = "cod_tipo_nomina ASC");
            $this->set('denominacion_nomina', $denominacion);

            $this->set('siguiente', $pagina + 1);
            $this->set('anterior', $pagina - 1);
            $this->bt_nav($Tfilas, $pagina);

            $this->Session->write('cedula_pestana_prestaciones', $cedula_identidad);
            $this->Session->write('cod_dep_prestaciones', $cod_dep);
            $this->Session->write('cod_tipo_nomina_prestaciones', $cod_tipo_nomina);
            $this->Session->write('cod_cargo_prestaciones', $cod_cargo);
            $this->Session->write('cod_ficha_prestaciones', $cod_ficha);

            echo"<script>
						            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_10').style.display='block';
						            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='block';
						            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_12').style.display='block';
						            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_13').style.display='block';
									document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_14').style.display='block';
						          </script>";
        } else {
            echo"<script>
						            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_10').style.display='none';
						            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='none';
						            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_12').style.display='none';
						            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_13').style.display='none';
									document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_14').style.display='none';
						          </script>";

            $this->set('COMPROMISO', '');
            $this->set('errorMessage', 'No se encontrar&oacute;n datos');
        }
    }

//fin function

    function fecha_session_hasta($var = null, $var2 = null, $var3 = null, $var4 = null) {

        $this->layout = "ajax";
        $this->Session->write('fecha_session_hasta_prestaciones', $var . "/" . $var2 . "/" . $var3);
        //echo $this->Session->read('fecha_session_desde_prestaciones');
        $tiempo_servicio = $this->get_edad($this->Session->read('fecha_session_hasta_prestaciones'), $this->Session->read('fecha_session_desde_prestaciones'));
        $tiempo_servicio = explode(' ', $tiempo_servicio);
        $c_array = count($tiempo_servicio);


        if ($c_array != 0) {
            $dias = 0;
            $meses = 0;
            $anios = 0;
            for ($t = 0; $t < $c_array; $t++) {
                if ($tiempo_servicio[$t] == "mons" || $tiempo_servicio[$t] == "mon") {
                    $meses = $tiempo_servicio[$t - 1];
                }
                if ($tiempo_servicio[$t] == "day" || $tiempo_servicio[$t] == "days") {
                    $dias = $tiempo_servicio[$t - 1];
                }
                if ($tiempo_servicio[$t] == "year" || $tiempo_servicio[$t] == "years") {
                    $anios = $tiempo_servicio[$t - 1];
                }
            }//fin
        } else {
            $dias = 0;
            $meses = 0;
            $anios = 0;
        }//fin elseak;
//get_edad();

        echo"<script>
                    document.getElementById('dias').value= '" . $dias . "';
				    document.getElementById('meses').value= '" . $meses . "';
					document.getElementById('anios').value= '" . $anios . "';
          </script>";



        $this->render('funcion');
    }

//fin function

    function fecha_session_desde($var = null, $var2 = null, $var3 = null, $var4 = null) {

        $this->layout = "ajax";
        $this->Session->write('fecha_session_desde_prestaciones', $var . "/" . $var2 . "/" . $var3);

        if (isset($_SESSION['fecha_session_hasta_prestaciones'])) {
            $tiempo_servicio = $this->get_edad($this->Session->read('fecha_session_hasta_prestaciones'), $this->Session->read('fecha_session_desde_prestaciones'));
            $tiempo_servicio = explode(' ', $tiempo_servicio);
            $c_array = count($tiempo_servicio);

            if ($c_array != 0) {
                $dias = 0;
                $meses = 0;
                $anios = 0;
                for ($t = 0; $t < $c_array; $t++) {
                    if ($tiempo_servicio[$t] == "mons" || $tiempo_servicio[$t] == "mon") {
                        $meses = $tiempo_servicio[$t - 1];
                    }
                    if ($tiempo_servicio[$t] == "day" || $tiempo_servicio[$t] == "days") {
                        $dias = $tiempo_servicio[$t - 1];
                    }
                    if ($tiempo_servicio[$t] == "year" || $tiempo_servicio[$t] == "years") {
                        $anios = $tiempo_servicio[$t - 1];
                    }
                }//fin
            } else {
                $dias = 0;
                $meses = 0;
                $anios = 0;
            }//fin else;
        } else {
            $dias = 0;
            $meses = 0;
            $anios = 0;
        }


//get_edad();

        echo"<script>
                    document.getElementById('dias').value= '" . $dias . "';
				    document.getElementById('meses').value= '" . $meses . "';
					document.getElementById('anios').value= '" . $anios . "';
           </script>";





        $this->render('funcion');
    }

//fin function

    function consulta_cedula2() {


        if ($this->Session->read('cedula_pestana_prestaciones') == "") {
            $id = 0;
        } else {
            $id = $this->Session->read('cedula_pestana_prestaciones');
        }//fin else

        $this->layout = "ajax";
        $motivo_retiro = $this->ctipo_motivo_retiro();
        $this->set('motivo_retiro', $motivo_retiro);

        if (isset($pagina)) {
            $pagina = $pagina;
        } else {
            $pagina = 1;
        }//fin else

        $condicion = $this->condicion() . " and cod_tipo_nomina='" . $this->Session->read('cod_tipo_nomina_prestaciones') . "'  ";
        $Tfilas = $this->cnmd15_datos_personales->findCount($condicion . " and cedula_identidad='" . $id . "'");
        if ($Tfilas != 0) {
            $this->set('pag_cant', $pagina . '/' . $Tfilas);
            $this->set('ultimo', $Tfilas);
            $data = $this->cnmd15_datos_personales->findAll($condicion . " and cedula_identidad='" . $id . "'", null, 'cedula_identidad ASC', 1, $pagina, null);
            $this->set('data', $data);
            $cedula_identidad = $data[0]['cnmd15_datos_personales']['cedula_identidad'];
            $cod_dep = $data[0]['cnmd15_datos_personales']['cod_dep'];
            $cod_tipo_nomina = $data[0]['cnmd15_datos_personales']['cod_tipo_nomina'];
            $cod_cargo = $data[0]['cnmd15_datos_personales']['cod_cargo'];
            $cod_ficha = $data[0]['cnmd15_datos_personales']['cod_ficha'];
            $fecha_ingreso = $data[0]['cnmd15_datos_personales']['fecha_ingreso'];
            $fecha_egreso = $data[0]['cnmd15_datos_personales']['fecha_egreso'];

            $tiempo_servicio = $this->get_edad($fecha_egreso, $fecha_ingreso);
            $tiempo_servicio = explode(' ', $tiempo_servicio);
            $this->set('tiempo_servicio', $tiempo_servicio);

            $this->Session->write('fecha_session_hasta_prestaciones', $fecha_egreso);
            $this->Session->write('fecha_session_desde_prestaciones', $fecha_ingreso);


            $denominacion = $this->Cnmd01->field('denominacion', $conditions = $this->condicion() . " and cod_tipo_nomina='$cod_tipo_nomina'", $order = "cod_tipo_nomina ASC");
            $this->set('denominacion_nomina', $denominacion);

            $this->set('siguiente', $pagina + 1);
            $this->set('anterior', $pagina - 1);
            $this->bt_nav($Tfilas, $pagina);

            $this->Session->write('cedula_pestana_prestaciones', $cedula_identidad);
            $this->Session->write('cod_dep_prestaciones', $cod_dep);
            $this->Session->write('cod_tipo_nomina_prestaciones', $cod_tipo_nomina);
            $this->Session->write('cod_cargo_prestaciones', $cod_cargo);
            $this->Session->write('cod_ficha_prestaciones', $cod_ficha);

            echo"<script>
						            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_10').style.display='block';
						            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='block';
						            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_12').style.display='block';
						            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_13').style.display='block';
									document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_14').style.display='block';
						          </script>";
        } else {
            echo"<script>
						            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_10').style.display='none';
						            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='none';
						            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_12').style.display='none';
						            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_13').style.display='none';
									document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_14').style.display='none';
						          </script>";

            $this->set('COMPROMISO', '');
            $this->set('errorMessage', 'No se encontrar&oacute;n datos');
        }
    }

//fin function

    function consulta($pagina = null, $cedula_iden = null) {
        $this->layout = "ajax";

        $motivo_retiro = $this->ctipo_motivo_retiro();
        $this->set('motivo_retiro', $motivo_retiro);

        if (isset($pagina)) {
            $pagina = $pagina;
        } else {
            $pagina = 1;
        }//fin else

        if (isset($cedula_iden)) {
            $cedula_iden = " and cedula_identidad='" . $cedula_iden . "'";
        } else {
            $cedula_iden = "";
        }//fin else

        if ($this->Session->read('cod_tipo_nomina_prestaciones') == "") {
            $cod_tipo_nomin = "";
        } else {
            $cod_tipo_nomin = " and cod_tipo_nomina='" . $this->Session->read('cod_tipo_nomina_prestaciones') . "'  ";
        }//fin else

        $Tfilas = $this->cnmd15_datos_personales->findCount($this->condicion() . $cod_tipo_nomin . $cedula_iden);
        if ($Tfilas != 0) {
            $this->set('pag_cant', $pagina . '/' . $Tfilas);
            $this->set('ultimo', $Tfilas);
            $data = $this->cnmd15_datos_personales->findAll($this->condicion() . $cod_tipo_nomin . $cedula_iden, null, 'cedula_identidad ASC', 1, $pagina, null);
            $this->set('data', $data);
            $cedula_identidad = $data[0]['cnmd15_datos_personales']['cedula_identidad'];
            $cod_dep = $data[0]['cnmd15_datos_personales']['cod_dep'];
            $cod_tipo_nomina = $data[0]['cnmd15_datos_personales']['cod_tipo_nomina'];
            $cod_cargo = $data[0]['cnmd15_datos_personales']['cod_cargo'];
            $cod_ficha = $data[0]['cnmd15_datos_personales']['cod_ficha'];
            $fecha_ingreso = $data[0]['cnmd15_datos_personales']['fecha_ingreso'];
            $fecha_egreso = $data[0]['cnmd15_datos_personales']['fecha_egreso'];

            $tiempo_servicio = $this->get_edad($fecha_egreso, $fecha_ingreso);
            $tiempo_servicio = explode(' ', $tiempo_servicio);
            $this->set('tiempo_servicio', $tiempo_servicio);

            $this->Session->write('fecha_session_hasta_prestaciones', $fecha_egreso);
            $this->Session->write('fecha_session_desde_prestaciones', $fecha_ingreso);

            $denominacion = $this->Cnmd01->field('denominacion', $conditions = $this->condicion() . " and cod_tipo_nomina='$cod_tipo_nomina'", $order = "cod_tipo_nomina ASC");
            $this->set('denominacion_nomina', $denominacion);

            $this->set('siguiente', $pagina + 1);
            $this->set('anterior', $pagina - 1);
            $this->bt_nav($Tfilas, $pagina);

            $this->Session->write('cedula_pestana_prestaciones', $cedula_identidad);
            $this->Session->write('cod_dep_prestaciones', $cod_dep);
            $this->Session->write('cod_tipo_nomina_prestaciones', $cod_tipo_nomina);
            $this->Session->write('cod_cargo_prestaciones', $cod_cargo);
            $this->Session->write('cod_ficha_prestaciones', $cod_ficha);

            echo"<script>
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_10').style.display='block';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='block';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_12').style.display='block';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_13').style.display='block';
						document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_14').style.display='block';
			          </script>";
        } else {
            echo"<script>
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_10').style.display='none';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_11').style.display='none';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_12').style.display='none';
			            document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_13').style.display='none';
						document.getElementById('tabTabdhtmlgoodies_tabViewPrincipal" . $_SESSION["rand_expediente"] . "_14').style.display='none';
			          </script>";

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

    function eliminar($cedula_identidad, $page) {
        $this->layout = "ajax";

        $sql_delete = "DELETE FROM cnmd15_datos_personales WHERE cedula_identidad='$cedula_identidad'";

        $sw = $this->cnmd15_datos_personales->execute($sql_delete);
        if ($sql_delete) {
            $this->set('msg', 'la eliminacion fue realizada con exito');
            $this->consulta($page);
            $this->render('consulta');
        } else {
            $this->set('msg_error', 'no se logro eliminar el dato - por favor intente de nuevo');
            $this->consulta($page + 1);
            $this->render('consulta');
        }
    }

    function editar($cedula_identidad, $page) {
        $this->layout = "ajax";
        $motivo_retiro = $this->ctipo_motivo_retiro();
        $this->set('motivo_retiro', $motivo_retiro);
        $data = $this->cnmd15_datos_personales->findAll($this->condicion() . " and cedula_identidad='$cedula_identidad'", null, 'cedula_identidad ASC', 1, null, null);
        $this->set('data', $data);
        $cod_tipo_nomina = $data[0]['cnmd15_datos_personales']['cod_tipo_nomina'];
        $fecha_ingreso = $data[0]['cnmd15_datos_personales']['fecha_ingreso'];
        $fecha_egreso = $data[0]['cnmd15_datos_personales']['fecha_egreso'];

        $tiempo_servicio = $this->get_edad($fecha_egreso, $fecha_ingreso);
        $tiempo_servicio = explode(' ', $tiempo_servicio);
        $this->set('tiempo_servicio', $tiempo_servicio);

        $this->Session->write('fecha_session_hasta_prestaciones', $fecha_egreso);
        $this->Session->write('fecha_session_desde_prestaciones', $fecha_ingreso);

        $denominacion = $this->Cnmd01->field('denominacion', $conditions = $this->condicion() . " and cod_tipo_nomina='$cod_tipo_nomina'", $order = "cod_tipo_nomina ASC");
        $this->set('denominacion_nomina', $denominacion);

        $this->set('page', $page + 1);







        echo"<script>
			            if(document.getElementById('cnmp15_datos_personales')) {document.getElementById('cnmp15_datos_personales').innerHTML='';}
			          </script>";
    }

//fin if

    function guardar_editar($cedula_identidad, $page) {
        $this->layout = "ajax";
        $denominacion_cargo = $this->data['cnmp15_datos_personales']['cargo'];
        $institucion = $this->data['cnmp15_datos_personales']['institucion'];
        $dependencia = $this->data['cnmp15_datos_personales']['dependencia'];
        $fecha_ingreso = $this->data['cnmp15_datos_personales']['fecha_ingreso'];
        $fecha_egreso = $this->data['cnmp15_datos_personales']['fecha_egreso'];
        $motivo_retiro = $this->data['cnmp15_datos_personales']['motivo_retiro'];
        $preaviso = $this->data['cnmp15_datos_personales']['preaviso'];
        $cobro_prestaciones = $this->data['cnmp15_datos_personales']['cobro_prestaciones'];
        $cargo_inicio = $this->data['cnmp15_datos_personales']['cargo_inicio'];
        $informacion_desemp = $this->data['cnmp15_datos_personales']['informacion'] != "" ? $this->data['cnmp15_datos_personales']['informacion'] : "Ejercio el cargo satisfactoriamente.";
        $observaciones = $this->data['cnmp15_datos_personales']['observaciones'] != "" ? $this->data['cnmp15_datos_personales']['observaciones'] : "Se elabora FP-023 para pago de prestaciones sociales";
        $informacion_desemp = str_replace("\n", " ", $informacion_desemp);
        $observaciones = str_replace("\n", " ", $observaciones);

        $sql_update = "UPDATE cnmd15_datos_personales SET denominacion_cargo='$denominacion_cargo', institucion='$institucion', dependencia='$dependencia', fecha_ingreso='$fecha_ingreso', fecha_egreso='$fecha_egreso', motivo_retiro='$motivo_retiro', cumplio_preaviso='$preaviso', prestacion_cancelada='$cobro_prestaciones', denominacion_cargo_inicio='$cargo_inicio', informacion_desempeno='$informacion_desemp', observaciones='$observaciones' WHERE " . $this->condicion() . " AND cedula_identidad='$cedula_identidad'";

        $sw1 = $this->cnmd15_datos_personales->execute($sql_update);

        if ($sw1 > 1) {
            $this->set('msg', 'los datos fueron actualizados con exito');
        } else {
            $this->set('msg_error', 'los datos no fueron actualizados - por favor intente de nuevo');
        }

        $this->consulta($page, $cedula_identidad);
        $this->render('consulta');
    }

}

?>
