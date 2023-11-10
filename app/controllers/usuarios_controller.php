<?php
uses ('sanitize');

$mrClean = new Sanitize();
class UsuariosController extends AppController {

    var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap');
    var $uses = array('cugd02_institucion', 'Usuario', 'arrd05', 'cugd04', 'cugd04_entrada_modulo', 'cugd02_dependencia',
        'ccfd03_instalacion', 'ccfd04_cierre_mes', 'casd00_autorizacion', 'ccnd00', 'ccnd01_concejo_comunal', 'cstd03_cheque_cuerpo',
        'cstd03_movimientos_manuales', "cnmd06_datos_registro_titulo", "cnmd06_especialidades", "cnmd06_profesiones", 'a_control_panel');
    var $layout = "index_usuario";

    function index($var = null) {
        $this->Session->delete('Usuario');
        $this->Session->delete('nom_usuario');
        $this->Session->delete('SScodpresi');
        $this->Session->delete('SScodentidad');
        $this->Session->delete('SScodtipoinst');
        $this->Session->delete('SScodinst');
        $this->Session->delete('SScoddep');
        $this->Session->delete('Modulo');
        $this->Session->delete('dependencia');
        $this->Session->delete('entidad_federal');
        $this->Session->delete('SScoddep_aux');

        $this->Session->delete('concejo_comunal');
        $this->Session->delete('nom_usuario');
        $this->Session->delete('SScodpresi');
        $this->Session->delete('CC_republica');
        $this->Session->delete('CC_estado');
        $this->Session->delete('CC_municipio');
        $this->Session->delete('CC_parroquia');
        $this->Session->delete('CC_centro');
        $this->Session->delete('CC_concejo');

        $this->Session->delete('ATS_autorizados');
        $this->Session->delete('not_autorizado');
        $this->set('error', false);
        $data_control_pane = $this->a_control_panel->findAll(CODIGOSCONDICION);
        //pr($data_control_pane);
        if ($data_control_pane[0]['a_control_panel']['sistema_cerrado'] == 0) {
            define('CERRAR_SISTEMA', false);
        } else {
            define('CERRAR_SISTEMA', true);
        }

        if ($var != null) {
            if ($var == '1') {
                $this->set('msg', '');
            }
            if ($var == 'salir') {
                $this->set('SesionCerradaBT', true);
            }
        }
        if (defined('CERRAR_SISTEMA') == true && CERRAR_SISTEMA == true) {
            $this->render('sistema_cerrado');
        } else {

            if (!empty($this->data)) {
                $xml1 = (int) date("m");
                $xml2 = date("Y");

                $Usuario_cont = $this->Usuario->findCount("upper(username) = '" . strtoupper($this->data['Usuario']['username']) . "' ");
                $ccnd00_cont = $this->ccnd00->findCount("upper(username) = '" . strtoupper($this->data['Usuario']['username']) . "' ");


                if ($Usuario_cont != 0) {

                    $someone = $this->Usuario->findByUsername(strtoupper($this->data['Usuario']['username']));
                    // if (!empty($someone['Usuario']['password']) && strtoupper($someone['Usuario']['password']) == strtoupper($this->data['Usuario']['password'])) {


                    if (!empty($someone['Usuario']['password']) && strtoupper($someone['Usuario']['password']) == strtoupper(md5($this->data['Usuario']['password']))) {


                        $condicion = "cod_presi = " . $someone['Usuario']['cod_presi'] . " and cod_entidad = " . $someone['Usuario']['cod_entidad'] . " and cod_tipo_inst = " . $someone['Usuario']['cod_tipo_inst'] . " and cod_inst = " . $someone['Usuario']['cod_inst'] . " and cod_dep='" . $someone['Usuario']['cod_dep'] . "'  ";
                        $someone2 = $this->arrd05->findAll($condicion);


               if($someone['Usuario']['condicion_actividad'] == 3) {

                    $this->set('mantenimiento', true);

               } else if ($someone['Usuario']['condicion_actividad'] == 2 ||$someone2[0]['arrd05']['condicion_actividad'] == 2 ) {

                    //    if (false) {

                            $this->set('error', true);
                        } else {

                            $_SESSION["Usuario"] = $someone['Usuario'];
                            $this->Session->write('Usuario', $someone['Usuario']);
                            $this->Session->write('nom_usuario', $someone['Usuario']['username']);
                            $this->Session->write('passw_usuario', $this->data['Usuario']['password']);
                            $this->Session->write('SScodpresi', $someone['Usuario']['cod_presi']);
                            $this->Session->write('SScodentidad', $someone['Usuario']['cod_entidad']);
                            $this->Session->write('SScodtipoinst', $someone['Usuario']['cod_tipo_inst']);
                            $this->Session->write('SScodinst', $someone['Usuario']['cod_inst']);
                            $this->Session->write('SScoddep', $someone['Usuario']['cod_dep']);
                            $this->Session->write('SScoddeporig', $someone['Usuario']['cod_dep_original']);
                            $this->Session->write('Modulo', $someone['Usuario']['modulo']);

                            if ($_SESSION["nom_usuario"] == "ADMIN") {
                                $this->Session->write('concejo_comunal', $someone['Usuario']);
                                $this->Session->write('CC_republica', 1);
                                $this->Session->write('CC_estado', 1);
                                $this->Session->write('CC_municipio', 1);
                                $this->Session->write('CC_parroquia', 1);
                                $this->Session->write('CC_centro', 1);
                                $this->Session->write('CC_concejo', 1);
                            }



                            //////////////////////////AUTORIZACION USUARIOS ATENCION SOCIAL///////////////////////
                            $VR = $this->casd00_autorizacion->FindCount("username='" . $someone['Usuario']['username'] . "'");
                            if ($VR != 0) {
                                $VRT = $this->casd00_autorizacion->Findall("username='" . $someone['Usuario']['username'] . "'");
                                $vector['casp01_atencion_social'] = $VRT[0]['casd00_autorizacion']['datos_personales'];
                                $vector['casp01_solicitud_ayudas'] = $VRT[0]['casd00_autorizacion']['solicitudes'];
                                $vector['casp01_evaluacion_ayudas'] = $VRT[0]['casd00_autorizacion']['evaluaciones'];
                                $vector['casp01_ayudas'] = $VRT[0]['casd00_autorizacion']['ayudas'];
                                $vector['casp01_tipo_ayuda'] = $VRT[0]['casd00_autorizacion']['tipo_ayuda'];
                                $vector['graficos'] = $VRT[0]['casd00_autorizacion']['graficos'];
                                $vector['reportes'] = $VRT[0]['casd00_autorizacion']['reportes'];

                                $_SESSION["ATS_autorizados"] = $vector;
                            } else {
                                $this->Session->write('not_autorizado', 'no autorizado');
                            }



                            /////////////////////////////////////////////////////////////////////////////////////


                            $ano_ejecucion = $this->ano_ejecucion();
                            if ($ano_ejecucion == null) {
                                $ano_ejecucion = date("Y");
                            }
                            $mes_cierre = $this->ccfd03_instalacion->findAll("cod_presi=" . $someone['Usuario']['cod_presi'] . " and cod_entidad=" . $someone['Usuario']['cod_entidad'] . " and cod_tipo_inst=" . $someone['Usuario']['cod_tipo_inst'] . " and cod_inst=" . $someone['Usuario']['cod_inst'] . " and cod_dep=" . $someone['Usuario']['cod_dep'] . " and ano_cierre_mensual=" . $ano_ejecucion);
                            $cant_m = $this->ccfd03_instalacion->findCount("cod_presi=" . $someone['Usuario']['cod_presi'] . " and cod_entidad=" . $someone['Usuario']['cod_entidad'] . " and cod_tipo_inst=" . $someone['Usuario']['cod_tipo_inst'] . " and cod_inst=" . $someone['Usuario']['cod_inst'] . " and cod_dep=" . $someone['Usuario']['cod_dep'] . " and ano_cierre_mensual=" . $ano_ejecucion);
                            if ($cant_m != 0) {
                                $this->Session->write('ANO_CERRADO_EJECUCION', $ano_ejecucion);
                                $this->Session->write('MES_CERRADO_EJECUCION', $mes_cierre[0]["ccfd03_instalacion"]["mes_cierre_mensual"]);
                            } else {
                                $this->Session->write('ANO_CERRADO_EJECUCION', $ano_ejecucion);
                                $this->Session->write('MES_CERRADO_EJECUCION', 0);
                            }

                            $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $this->Session->read('SScoddep') . '   ';
                            $dep = $this->arrd05->findAll($condicion);
                            $this->Session->write('SStipoddep', $dep[0]['arrd05']['tipo_dependencia']);



                            if ($this->entrada()) {
                                $a = " and cod_tipo_inst=" . $someone['Usuario']['cod_tipo_inst'];
                                $a.=" and cod_inst=" . $someone['Usuario']['cod_inst'];
                                if ($this->cugd02_institucion->findCount("cod_tipo_institucion=" . $someone['Usuario']['cod_tipo_inst'] . " and cod_institucion=" . $someone['Usuario']['cod_inst']) != 0) {
                                    $v = $this->cugd02_institucion->findAll("cod_tipo_institucion=" . $someone['Usuario']['cod_tipo_inst'] . " and cod_institucion=" . $someone['Usuario']['cod_inst'], 'denominacion', 'cod_tipo_institucion ASC', 1, 1, null);
                                    $v[0]['cugd02_institucion']['denominacion'] != null && $v[0]['cugd02_institucion']['denominacion'] != '' ? $this->Session->write('entidad_federal', $v[0]['cugd02_institucion']['denominacion']) : $this->Session->write('entidad_federal', '');
                                } else {
                                    $this->Session->write('entidad_federal', '');
                                }
                                $this->arrd05->recursive = 0;
                                if ($this->arrd05->findCount("arrd05.cod_presi=" . $someone['Usuario']['cod_presi'] . " and arrd05.cod_entidad=" . $someone['Usuario']['cod_entidad'] . " and arrd05.cod_tipo_inst=" . $someone['Usuario']['cod_tipo_inst'] . " and arrd05.cod_inst=" . $someone['Usuario']['cod_inst'] . " and arrd05.cod_dep=" . $someone['Usuario']['cod_dep'] . "") != 0) {
                                    $v = $this->arrd05->findAll("arrd05.cod_presi=" . $someone['Usuario']['cod_presi'] . " and arrd05.cod_entidad=" . $someone['Usuario']['cod_entidad'] . " and arrd05.cod_tipo_inst=" . $someone['Usuario']['cod_tipo_inst'] . " and arrd05.cod_inst=" . $someone['Usuario']['cod_inst'] . " and arrd05.cod_dep=" . $someone['Usuario']['cod_dep'] . "", 'denominacion', 'cod_dep ASC', 1, 1, null);
                                    $v[0]['arrd05']['denominacion'] != null && $v[0]['arrd05']['denominacion'] != '' ? $this->Session->write('dependencia', $v[0]['arrd05']['denominacion']) : $this->Session->write('dependencia', '');
                                }


                                $_SESSION['cod_dep_reporte_consolidado'] = $_SESSION["SScoddeporig"];
                                $_SESSION["entidad_federal_reporte_consolidado"] = $_SESSION["entidad_federal"];
                                $_SESSION["dependencia_reporte_consolidado"] = $_SESSION["dependencia"];

                                $this->log("Entró al sistema el usuario [" . $this->data['Usuario']['username'] . "[" . $someone['Usuario']['cod_presi'] . "][" . $someone['Usuario']['cod_entidad'] . "][" . $someone['Usuario']['cod_tipo_inst'] . "][" . $someone['Usuario']['cod_inst'] . "][" . $someone['Usuario']['cod_dep'] . "]", LOG_ERROR);

                                //if($xml1>=4 && $xml2==2010){
                                //	$this->set('error', true);
                                //	$this->redirect('/salir/');
                                //}else{
                                $this->redirect('/modulos/index/entrada_exitosa');
                                //}
                            } else {
                                $this->set('msgError', 'LO SIENTO YA EXISTE UN USUARIO CONECTADO CON SU LOGIN');
                                $this->redirect('usuarios/index/1');
                            }
                        }//fin else
                    } else {

                        $this->set('error', true);
                        $this->log("PASSWORD/LOGIN INVALIDO [" . $_SERVER['REMOTE_ADDR'] . "/" . $this->data['Usuario']['username'] . "/" . $this->data['Usuario']['password'] . "]", LOG_ERROR);
                    }//fin else
                } else if ($ccnd00_cont != 0) {



                    $someone = $this->ccnd00->findByUsername(strtoupper($this->data['Usuario']['username']));
                    // if (!empty($someone['ccnd00']['password']) && strtoupper($someone['ccnd00']['password']) == strtoupper($this->data['Usuario']['password'])) {

					if (!empty($someone['ccnd00']['password']) && strtoupper($someone['ccnd00']['password']) == strtoupper(md5($this->data['Usuario']['password']))) {

                        $_SESSION["Usuario"] = $someone['ccnd00'];

                        $this->Session->write('concejo_comunal', $someone['ccnd00']);
                        $this->Session->write('nom_usuario', $someone['ccnd00']['username']);
                        $this->Session->write('passw_usuario', $this->data['Usuario']['password']);
                        $this->Session->write('CC_republica', $someone['ccnd00']['cod_republica']);
                        $this->Session->write('CC_estado', $someone['ccnd00']['cod_estado']);
                        $this->Session->write('CC_municipio', $someone['ccnd00']['cod_municipio']);
                        $this->Session->write('CC_parroquia', $someone['ccnd00']['cod_parroquia']);
                        $this->Session->write('CC_centro', $someone['ccnd00']['cod_centro']);
                        $this->Session->write('CC_concejo', $someone['ccnd00']['cod_concejo']);

                        $this->Session->write('SScodpresi', $someone['ccnd00']['cod_republica']);
                        $this->Session->write('SScodentidad', $someone['ccnd00']['cod_estado']);
                        $this->Session->write('SScodtipoinst', $someone['ccnd00']['cod_municipio']);
                        $this->Session->write('SScodinst', $someone['ccnd00']['cod_parroquia']);
                        $this->Session->write('SScoddep', $someone['ccnd00']['cod_centro']);

                        $ano_ejecucion = date("Y");
                        $this->Session->write('ANO_CERRADO_EJECUCION', $ano_ejecucion);
                        $this->Session->write('MES_CERRADO_EJECUCION', 0);


                        if ($_SESSION["nom_usuario"] == "ADMIN_CONCEJO" || $_SESSION["nom_usuario"] == "ADMIN_CONSEJO") {
                            $this->Session->write('dependencia', '');
                            $this->Session->write('entidad_federal', '');

                            $this->Session->write('dependencia_reporte_consolidado', '');
                            $this->Session->write('entidad_federal_reporte_consolidado', '');

                            $_SESSION['cod_dep_reporte_consolidado'] = $_SESSION["SScoddep"];
                        } else {

                            $sql = " cod_republica='" . $someone["ccnd00"]["cod_republica"] . "' and  cod_estado='" . $someone["ccnd00"]["cod_estado"] . "' and   cod_municipio='" . $someone["ccnd00"]["cod_municipio"] . "' and   cod_parroquia='" . $someone["ccnd00"]["cod_parroquia"] . "' and   cod_centro='" . $someone["ccnd00"]["cod_centro"] . "' and  cod_concejo='" . $someone["ccnd00"]["cod_concejo"] . "' ";
                            if ($this->ccnd01_concejo_comunal->findCount($sql) != 0) {
                                $v = $this->ccnd01_concejo_comunal->findAll($sql, 'denominacion', 'cod_concejo ASC', 1, 1, null);
                                $v[0]['ccnd01_concejo_comunal']['denominacion'] != null && $v[0]['ccnd01_concejo_comunal']['denominacion'] != '' ? $this->Session->write('dependencia', $v[0]['ccnd01_concejo_comunal']['denominacion']) : $this->Session->write('dependencia', '');
                                $v[0]['ccnd01_concejo_comunal']['denominacion'] != null && $v[0]['ccnd01_concejo_comunal']['denominacion'] != '' ? $this->Session->write('entidad_federal', $v[0]['ccnd01_concejo_comunal']['denominacion']) : $this->Session->write('entidad_federal', '');

                                $_SESSION['cod_dep_reporte_consolidado'] = $_SESSION["SScoddep"];
                                $_SESSION["entidad_federal_reporte_consolidado"] = $_SESSION["entidad_federal"];
                                $_SESSION["dependencia_reporte_consolidado"] = $_SESSION["dependencia"];
                            }
                        }//fin else
                        //if($xml1>=4 && $xml2==2010){
                        // $this->set('error', true);
                        //$this->redirect('/salir/');
                        //}else{
                        $this->redirect('/modulos/index/entrada_exitosa');
                        //}
                    } else {

                        $this->set('error', true);
                    }//fin else
                } else {

                    $this->set('error', true);
                    $this->log("INVALIDO [" . $_SERVER['REMOTE_ADDR'] . "/" . $this->data['Usuario']['username'] . "/" . $this->data['Usuario']['password'] . "]", LOG_ERROR);
                }//fin else
            }//fin  !empty($this->data)



            $this->render('index');
        }//fin CERRAR_sistema


        $this->data = null;
    }

///fin function

    function sistema_cerrado() {

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

    function entrada() {
        $this->layout = "ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $username = $this->Session->read('nom_usuario');
        $this->borrar_cugd04();
        $cont_user = $this->cugd04_entrada_modulo->findCount($this->condicion() . " and username='$username'");
        $this->actualizar_usuarios();
        if ($cont_user == 0) {
            $hora_entrada = date("U");
            $hora_comercial = date("h:ia");
            //		$hora_comercial=0;
            //echo "la hora de entrada es: ".$hora_entrada;
            $sql_insert = "INSERT INTO cugd04_entrada_modulo VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$username', '$hora_entrada', '0','0', '$hora_comercial')";
            $sw = $this->cugd04_entrada_modulo->execute($sql_insert);
            if ($sw > 1) {
                return true;
            }
        } else {
            $hora_entrada = $this->cugd04_entrada_modulo->field('cugd04_entrada_modulo.hora_entrada_modulo', $conditions = "cugd04_entrada_modulo.username='$username'", $order = null);
            $hora_actual = date("U");
            $tiempo_afuera = $hora_actual - $hora_entrada;
            if ($tiempo_afuera > 600) {
                $sql_update = "UPDATE cugd04_entrada_modulo SET hora_entrada_modulo='$hora_actual' , hora_actualizada=0, hora_captura_partida=0 where username='$username' and " . $this->condicion();
                $sw_update = $this->cugd04_entrada_modulo->execute($sql_update);
                if ($sw_update) {
                    $this->borrar_cugd04();
                    return true;
                }
            }
            $count_username = substr_count(strtoupper($username), 'ADMIN_');
            $modulo_username = $this->Session->read('Modulo');
            if (strtoupper($username) == 'ADMIN' || strtoupper($username) == 'DEMO' || ($count_username != 0 && $modulo_username == '0' && $cod_dep == 1))
                return true;
            else
                //return false;
                return true;
        }
        //return false;
        return true;
    }

//FIN ENTRADA

    function actualizar_usuarios() {
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $username = $this->Session->read('nom_usuario');
        $cont = $this->cugd04_entrada_modulo->findCount();
        $sql_delete = "DELETE FROM cugd04_entrada_modulo WHERE ";
        if ($cont > 0) {
            $conectados = $this->cugd04_entrada_modulo->findAll($conditions = null, $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
            $hora_actual = date('U');
            $i = 0;
            $nro = count($conectados);
            foreach ($conectados as $row) {
                $username = $row['cugd04_entrada_modulo']['username'];
                $hora_actualizada = $row['cugd04_entrada_modulo']['hora_actualizada'];
                $tiempo_fuera = $hora_actual - $hora_actualizada;
                if ($tiempo_fuera > 600) {
                    if (!isset($primero)) {
                        $primero = '1';
                        $sql_delete .= "username='$username'";
                    } else {
                        $sql_delete .= " or username='$username'";
                    }
                }
                $i++;
            }
            if (!isset($primero)) {
                $sql_delete .= " ;";
            } else {
                $sql_delete .= " ;";
                $sw = $this->cugd04_entrada_modulo->execute($sql_delete);
            }
        }
        //$count_msj= $this->cugd04_entrada_modulo->execute("SELECT count(*) as c FROM mensajes WHERE cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and upper(username)='".strtoupper($username)."'");
        //$msj= $this->cugd04_entrada_modulo->execute("SELECT contenido_msj FROM mensajes WHERE cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and upper(username)='".strtoupper($username)."'");
        //$this->cugd04_entrada_modulo->execute("DELETE FROM mensajes WHERE cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and upper(username)='".strtoupper($username)."'");
        /* if($count_msj[0][0]["c"]>0){
          echo "<script>
          Control.Modal.open('<u>$username</u>,<br>".$msj[0][0]["contenido_msj"]."');
          </script>";
          } */
    }

//FIN ACTUALIZAR USUARIO

    function salir() {
        if (isset($_SESSION['nom_usuario'])) {
            $this->log("El [" . $this->Session->read('nom_usuario') . "][" . $this->Session->read('SScodpresi') . "][" . $this->Session->read('SScodentidad') . "][" . $this->Session->read('SScodtipoinst') . "][" . $this->Session->read('SScodinst') . "][" . $this->Session->read('SScoddep') . "] a cerrado la session", LOG_ERROR);
            $this->borrar_cugd04();
            $this->cugd04_entrada_modulo->execute("DELETE FROM cugd04_entrada_modulo WHERE username='" . $this->Session->read("nom_usuario") . "'");
            $this->actualizar_usuarios();
            $this->Session->delete('Usuario');
            $this->Session->delete('nom_usuario');
            $this->Session->delete('SScodpresi');
            $this->Session->delete('SScodentidad');
            $this->Session->delete('SScodtipoinst');
            $this->Session->delete('SScodinst');
            $this->Session->delete('SScoddep');
            $this->Session->delete('Modulo');

            $this->Session->delete('concejo_comunal');
            $this->Session->delete('nom_usuario');
            $this->Session->delete('SScodpresi');
            $this->Session->delete('CC_republica');
            $this->Session->delete('CC_estado');
            $this->Session->delete('CC_municipio');
            $this->Session->delete('CC_parroquia');
            $this->Session->delete('CC_centro');


            $this->set('error', false);
            $this->redirect('/usuarios/index/salir');
            $_SESSION = array();
            //$this->index('salir');
            //$this->render('index');
        }//fin if
    }

//fin function

    function salir_concejo() {
        if (isset($_SESSION['concejo_comunal'])) {

            $this->Session->delete('concejo_comunal');
            $this->Session->delete('nom_usuario');
            $this->Session->delete('SScodpresi');
            $this->Session->delete('CC_republica');
            $this->Session->delete('CC_estado');
            $this->Session->delete('CC_municipio');
            $this->Session->delete('CC_parroquia');
            $this->Session->delete('CC_centro');
            $_SESSION = array();
            $this->set('error', false);
            $this->redirect('/usuarios/index/salir');
        }
    }

//fin function

    function actualizar_user() {
        $data_control_pane = $this->a_control_panel->findAll(CODIGOSCONDICION);
        //pr($data_control_pane);
        if ($data_control_pane[0]['a_control_panel']['sistema_cerrado'] == 0) {
            if (!defined('CERRAR_SISTEMA')) {
                define('CERRAR_SISTEMA', false);
            }

            if (isset($_SESSION['nom_usuario'])) {
                $username = $this->Session->read("nom_usuario");
                $c = $this->cugd04_entrada_modulo->findCount("username='" . $username . "'");
                if ($c != 0) {
                    //$q=$this->cugd04_entrada_modulo->field("hora_actualizada","username='".$this->Session->read("nom_usuario")."'",null);
                    $this->cugd04_entrada_modulo->execute("UPDATE cugd04_entrada_modulo SET hora_entrada_modulo=hora_actualizada , hora_actualizada='" . date("U") . "' WHERE username='" . $this->Session->read("nom_usuario") . "'");
                    if (!defined('MSJ_USUARIO')) {

                    } else {
                        $count_msj = $this->cugd04_entrada_modulo->execute("SELECT count(*) as c FROM mensajes WHERE upper(username)='" . strtoupper($this->Session->read("nom_usuario")) . "' and estado=1");
                        if ($count_msj[0][0]["c"] > 0) {
                            $msj = $this->cugd04_entrada_modulo->execute("SELECT contenido_msj,id_mensaje,username_origen FROM mensajes WHERE upper(username)='" . strtoupper($this->Session->read("nom_usuario")) . "'  and estado=1 order by id_mensaje DESC limit 1");
                            $this->cugd04_entrada_modulo->execute("UPDATE mensajes SET estado=2 where id_mensaje=" . $msj[0][0]["id_mensaje"] . "");
                            echo "<script>\n
						                 Control.Modal.open('<u>$username</u>,<br>" . $msj[0][0]["contenido_msj"] . "<br><br>Atte:" . $msj[0][0]["username_origen"] . "');\n
							         </script>";
                        }
                    }
                }
                return;
            }//fin if
        } else {
            if (!defined('CERRAR_SISTEMA')) {
                define('CERRAR_SISTEMA', true);
            }
            if (isset($_SESSION['nom_usuario'])) {
                $this->log("El [" . $this->Session->read('nom_usuario') . "][" . $this->Session->read('SScodpresi') . "][" . $this->Session->read('SScodentidad') . "][" . $this->Session->read('SScodtipoinst') . "][" . $this->Session->read('SScodinst') . "][" . $this->Session->read('SScoddep') . "] a cerrado la session", LOG_ERROR);
                $this->borrar_cugd04();
                $this->cugd04_entrada_modulo->execute("DELETE FROM cugd04_entrada_modulo WHERE username='" . $this->Session->read("nom_usuario") . "'");
                $this->actualizar_usuarios();
                $this->Session->delete('Usuario');
                $this->Session->delete('nom_usuario');
                $this->Session->delete('SScodpresi');
                $this->Session->delete('SScodentidad');
                $this->Session->delete('SScodtipoinst');
                $this->Session->delete('SScodinst');
                $this->Session->delete('SScoddep');
                $this->Session->delete('Modulo');
            }//fin if
            $this->redirect('/salir/');
            exit();
        }
    }

//fin function

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
        $sql_re = "cod_presi=1  and    ";
        $sql_re .= "cod_entidad=11  and  ";
        $sql_re .= "cod_tipo_inst=30  and ";
        $sql_re .= "cod_inst=11  and  ";
        if ($ano != null) {
            $sql_re .= "cod_dep=2  and  ";
            $sql_re .= "ano=" . $ano . "  ";
        } else {
            $sql_re .= "cod_dep=2 ";
        }
        return $sql_re;
    }

//fin funcion SQLCA

    function verifica_msj($id = null) {
        $this->layout = "ajax";

        $condicion = $this->condicion();
        $username = $this->Session->read('nom_usuario');
        $username = strtoupper($username);
        if (!isset($id)) {
            $rs = $this->Usuario->execute("SELECT a.username_origen,a.id_mensaje FROM mensajes a where a.username='$username' and a.estado=1 ORDER BY a.id_mensaje ASC");
            $c = $this->Usuario->execute("SELECT count(*) as t FROM mensajes where " . $condicion . " and username='$username' and estado=1");
            $this->set("data_usuarios", $rs);
            $this->set("CANTIDAD_MSJ", $c[0][0]["t"]);
        } else if (!empty($id)) {
            $id_msj = $id;
            $rs = $this->Usuario->execute("SELECT a.*,(SELECT b.denominacion FROM arrd05 b WHERE  b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_dep=a.cod_dep_origen) as dependencia FROM mensajes a where a.username='$username' and a.estado=1 and id_mensaje=" . $id_msj . " ORDER BY a.id_mensaje ASC limit 1");
            $c = $this->Usuario->execute("SELECT count(*) as t FROM mensajes where " . $condicion . " and username='$username' and estado=1");
            if ($c[0][0]["t"] > 0) {
                //$rs_up=$this->Usuario->execute("UPDATE mensajes SET estado = 2, fecha_leido=CURRENT_TIMESTAMP where id_mensaje=".$rs[0][0]["id_mensaje"]);
                $contenido_msj = $rs[0][0]["contenido_msj"];
                $this->set("DATA", $rs);
            }
        }
    }

//fin verifica_msj

    function cerrar_msj($id = null) {
        $this->layout = "ajax";

        $condicion = $this->condicion();
        $username = $this->Session->read('nom_usuario');
        $username = strtoupper($username);
        if (isset($id) && !empty($id)) {
            $this->cugd04_entrada_modulo->finded($this->params['form']['data']['mensajes']['msj']);
            $id_msj = $id;
            $rs = $this->Usuario->execute("UPDATE mensajes SET estado=2 where username='$username' and id_mensaje=" . $id_msj . "");
            $rs = $this->Usuario->execute("SELECT a.username_origen,a.id_mensaje FROM mensajes a where a.username='$username' and a.estado=1 ORDER BY a.id_mensaje ASC");
            $c = $this->Usuario->execute("SELECT count(*) as t FROM mensajes where " . $condicion . " and username='$username' and estado=1");
            $this->set("data_usuarios", $rs);
            $this->set("CANTIDAD_MSJ", $c[0][0]["t"]);
        }
    }

//fin cerrar_msj
}

//fin classs
?>
