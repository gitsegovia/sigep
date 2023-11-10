<?php

class Cnmp15DepoFideicomisoController extends AppController {

    var $name = 'cnmp15_depo_fideicomiso';
    var $uses = array('cnmd15_depo_fideicomiso', 'ccfd04_cierre_mes', 'Cnmd01');
    var $helpers = array('Html', 'Ajax', 'Javascript', 'Sisap');

    function checkSession() {
        // If the session info hasn't been set...
        if (!$this->Session->check('Usuario')) {
            // Force the user to login
            $this->redirect('/salir/');
            exit();
        }
    }

//checkSession

    function beforeFilter() {

        $this->checkSession();
    }

//beforeFilter

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

            $this->set($nomVar, $cod);
        }
    }

//fin concatena

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

    function index() {
        $this->layout = "ajax";

        $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
        $this->concatenaN($lista, 'nomina');
        //$datos=$this->cnmd15_rango->FindAll($this->SQLCA(),null,' ORDER BY cod_tipo_nomina ASC');
        $lista1 = $this->Cnmd01->FindAll($this->SQLCA());
        //$this->set('datos',$datos);
        $this->set('lista1', $lista1);
    }

//FIN INDEX

    function cod_nomina($cod_nomina = null) {
        $this->layout = "ajax";
        $this->Session->delete('nomina');
        $this->Session->write('nomina', $cod_nomina);
        if ($cod_nomina != null) {
            $this->set('cod_nomina', $cod_nomina);
        }
        echo "<script>";
        echo "document.getElementById('transferencia').innerHTML='';";
        echo "</script>";
    }

    function deno_nomina($cod_nomina = null) {
        $this->layout = "ajax";
        if ($cod_nomina != null) {
            $deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->condicion() . " and Cnmd01.cod_tipo_nomina='$cod_nomina'", $order = "cod_tipo_nomina ASC");
            //echo "el tipo de nomina es: ".$deno_nomina;
            $this->set('deno_nomina', $deno_nomina);
        }
    }

    function escala_inputs($var = null) {
        $this->layout = "ajax";
        $v_ano = $this->cnmd15_depo_fideicomiso->execute("SELECT ano FROM cnmd15_depo_fideicomiso WHERE " . $this->SQLCA() . " and cod_tipo_nomina=" . $var . " ORDER BY ano DESC;");
        if ($v_ano != null) {
            $el_ano = ($v_ano[0][0]["ano"] + 1);
        } else {
            $el_ano = $this->ano_ejecucion();
        }

        if ($el_ano != "") {
            $this->set('ano_ejec', $el_ano);
        } else {
            $this->set('ano_ejec', "");
        }
    }

//fin function

    function grilla($var = null) {
        $this->layout = "ajax";
        $datos = $this->cnmd15_depo_fideicomiso->FindAll($this->SQLCA() . " and cod_tipo_nomina=" . $var, null, ' ORDER BY ano ASC');

        if ($datos) {
            $this->set('datos', $datos);
        } else {
            $this->set('datos', "");
        }
    }

//fin grilla

    function guardar() {
        $this->layout = "ajax";

        $ano_depo = $this->data["cnmd15_depo_fide"]["depof_ano"];
        $ene_depo = $this->data["cnmd15_depo_fide"]["depof_ene"] == 'X' ? 1 : 2;
        $feb_depo = $this->data["cnmd15_depo_fide"]["depof_feb"] == 'X' ? 1 : 2;
        $mar_depo = $this->data["cnmd15_depo_fide"]["depof_mar"] == 'X' ? 1 : 2;
        $abr_depo = $this->data["cnmd15_depo_fide"]["depof_abr"] == 'X' ? 1 : 2;
        $may_depo = $this->data["cnmd15_depo_fide"]["depof_may"] == 'X' ? 1 : 2;
        $jun_depo = $this->data["cnmd15_depo_fide"]["depof_jun"] == 'X' ? 1 : 2;
        $jul_depo = $this->data["cnmd15_depo_fide"]["depof_jul"] == 'X' ? 1 : 2;
        $ago_depo = $this->data["cnmd15_depo_fide"]["depof_ago"] == 'X' ? 1 : 2;
        $sep_depo = $this->data["cnmd15_depo_fide"]["depof_sep"] == 'X' ? 1 : 2;
        $oct_depo = $this->data["cnmd15_depo_fide"]["depof_oct"] == 'X' ? 1 : 2;
        $nov_depo = $this->data["cnmd15_depo_fide"]["depof_nov"] == 'X' ? 1 : 2;
        $dic_depo = $this->data["cnmd15_depo_fide"]["depof_dic"] == 'X' ? 1 : 2;

        if ($ano_depo != "" && ($ene_depo != 2 || $feb_depo != 2 || $mar_depo != 2 || $abr_depo != 2 || $may_depo != 2 || $jun_depo != 2 || $jul_depo != 2 || $ago_depo != 2 || $sep_depo != 2 || $oct_depo != 2 || $nov_depo != 2 || $dic_depo != 2)) {
            $enc_nom_ano = $this->cnmd15_depo_fideicomiso->findCount("cod_tipo_nomina =" . $this->Session->read('nomina') . " and ano =" . $ano_depo. " and cod_dep =" . $this->verifica_SS(5));
            if ($enc_nom_ano == 0) {
                $sql_insert = "INSERT INTO cnmd15_depo_fideicomiso VALUES(" . $this->verifica_SS(1) . ", " . $this->verifica_SS(2) . ", " . $this->verifica_SS(3) . "," . $this->verifica_SS(4) . "," . $this->verifica_SS(5) . "," . $this->Session->read('nomina') . "," . $ano_depo . "," . $ene_depo . "," . $feb_depo . "," . $mar_depo . "," . $abr_depo . "," . $may_depo . "," . $jun_depo . "," . $jul_depo . "," . $ago_depo . "," . $sep_depo . "," . $oct_depo . "," . $nov_depo . "," . $dic_depo . ");";
                $sw1 = $this->cnmd15_depo_fideicomiso->execute($sql_insert);
                if ($sw1 > 1) {
                    $this->set('Message_existe', 'registro exitoso');
                    echo "<script>
					document.getElementById('id_depof_ano').value='" . ($ano_depo + 1) . "';
					document.getElementById('id_depof_ene').value='';
					document.getElementById('id_depof_feb').value='';
					document.getElementById('id_depof_mar').value='';
					document.getElementById('id_depof_abr').value='';
					document.getElementById('id_depof_may').value='';
					document.getElementById('id_depof_jun').value='';
					document.getElementById('id_depof_jul').value='';
					document.getElementById('id_depof_ago').value='';
					document.getElementById('id_depof_sep').value='';
					document.getElementById('id_depof_oct').value='';
					document.getElementById('id_depof_nov').value='';
					document.getElementById('id_depof_dic').value='';
				</script>";
                } else {
                    $this->set('errorMessage', 'No se pudo registrar');
                }
            } else {
                $this->set('errorMessage', 'Este a&ntilde;o ya se encuentra registrado para este tipo de n&oacute;mina');
            }
        } else {
            $this->set('errorMessage', 'Debe ingresar el a&ntilde;o y marcar alg&uacute;n mes');
        }

        $datos = $this->cnmd15_depo_fideicomiso->FindAll($this->SQLCA() . " and cod_tipo_nomina=" . $this->Session->read('nomina'), null, ' ORDER BY ano ASC');

        if ($datos) {
            $this->set('datos', $datos);
        } else {
            $this->set('datos', "");
        }
    }

//fin guardar

    function eliminar() {
        $this->layout = "ajax";
        $nomina = $this->Session->read('nomina');
        if ($nomina != "") {
            if ($this->cnmd15_depo_fideicomiso->FindAll($this->SQLCA() . " and cod_tipo_nomina=" . $nomina)) {
                $sw1 = $this->cnmd15_depo_fideicomiso->execute("delete from cnmd15_depo_fideicomiso where " . $this->SQLCA() . " and cod_tipo_nomina=" . $nomina);
                if ($sw1 > 1) {
                    $this->set('Message_existe', 'Los datos fueron eliminados exitosamente');
                } else {
                    $this->set('errorMessage', 'no se pudo eliminar');
                }
            } else {
                $this->set('errorMessage', 'El codigo que intenta eliminar no existe registrado!');
            }
        } else {
            $this->set('errorMessage', 'debe seleccionar el codigo a eliminar');
        }
        echo "<script>";
        echo "document.getElementById('cod_nomina').value='';";
        echo "document.getElementById('deno_nomina').value='';";
        echo "document.getElementById('muestra_grilla').innerHTML='';";
        echo "document.getElementById('cargar_grilla').innerHTML='';";
        echo "</script>";
    }

//fin eliminar

    function modificar($nomina = null, $ano_de = null, $ene_de = null, $feb_de = null, $mar_de = null, $abr_de = null, $may_de = null, $jun_de = null, $jul_de = null, $ago_de = null, $sep_de = null, $oct_de = null, $nov_de = null, $dic_de = null, $i = null) {
        $this->layout = "ajax";

        $this->set('nomina', $nomina);
        $this->set('ano_de', $ano_de);
        $this->set('enero', $ene_de);
        $this->set('febrero', $feb_de);
        $this->set('marzo', $mar_de);
        $this->set('abril', $abr_de);
        $this->set('mayo', $may_de);
        $this->set('junio', $jun_de);
        $this->set('julio', $jul_de);
        $this->set('agosto', $ago_de);
        $this->set('septiembre', $sep_de);
        $this->set('octubre', $oct_de);
        $this->set('noviembre', $nov_de);
        $this->set('diciembre', $dic_de);
        $this->set('k', $i);
    }

//fin modificar

    function guardar_modificar($nomina = null, $ano_depo_fidec = null) {
        $this->layout = "ajax";

        $ano_depo = $this->data["cnmd15_depo_fide"]["depof_anom"];
        $ene_depo = $this->data["cnmd15_depo_fide"]["depof_enem"] == 'X' ? 1 : 2;
        $feb_depo = $this->data["cnmd15_depo_fide"]["depof_febm"] == 'X' ? 1 : 2;
        $mar_depo = $this->data["cnmd15_depo_fide"]["depof_marm"] == 'X' ? 1 : 2;
        $abr_depo = $this->data["cnmd15_depo_fide"]["depof_abrm"] == 'X' ? 1 : 2;
        $may_depo = $this->data["cnmd15_depo_fide"]["depof_maym"] == 'X' ? 1 : 2;
        $jun_depo = $this->data["cnmd15_depo_fide"]["depof_junm"] == 'X' ? 1 : 2;
        $jul_depo = $this->data["cnmd15_depo_fide"]["depof_julm"] == 'X' ? 1 : 2;
        $ago_depo = $this->data["cnmd15_depo_fide"]["depof_agom"] == 'X' ? 1 : 2;
        $sep_depo = $this->data["cnmd15_depo_fide"]["depof_sepm"] == 'X' ? 1 : 2;
        $oct_depo = $this->data["cnmd15_depo_fide"]["depof_octm"] == 'X' ? 1 : 2;
        $nov_depo = $this->data["cnmd15_depo_fide"]["depof_novm"] == 'X' ? 1 : 2;
        $dic_depo = $this->data["cnmd15_depo_fide"]["depof_dicm"] == 'X' ? 1 : 2;

        if ($nomina != null && $ano_depo_fidec != null) {
            if ($ano_depo != "" && ($ene_depo != 2 || $feb_depo != 2 || $mar_depo != 2 || $abr_depo != 2 || $may_depo != 2 || $jun_depo != 2 || $jul_depo != 2 || $ago_depo != 2 || $sep_depo != 2 || $oct_depo != 2 || $nov_depo != 2 || $dic_depo != 2)) {
                $sw = $this->cnmd15_depo_fideicomiso->execute("update cnmd15_depo_fideicomiso set ene=" . $ene_depo . " , feb=" . $feb_depo . " , mar=" . $mar_depo . " , abr=" . $abr_depo . " , may=" . $may_depo . " , jun=" . $jun_depo . " , jul=" . $jul_depo . " , ago=" . $ago_depo . " , sep=" . $sep_depo . " , oct=" . $oct_depo . " , nov=" . $nov_depo . " , dic=" . $dic_depo . " where " . $this->SQLCA() . " and cod_tipo_nomina=" . $nomina . " and ano=" . $ano_depo_fidec);
                if ($sw > 1) {
                    $this->set('Message_existe', 'Se ha modificado exitosamente');
                } else {
                    $this->set('errorMessage', 'No se pudo modificar, intente nuevamente');
                }

                $datos = $this->cnmd15_depo_fideicomiso->FindAll($this->SQLCA() . " and cod_tipo_nomina=" . $nomina, null, ' ORDER BY ano ASC');
                $this->set('datos', $datos);
            } else {
                $this->set('errorMessage', 'Debe ingresar el a&ntilde;o y marcar alg&uacute;n mes');
                $datos = $this->cnmd15_depo_fideicomiso->FindAll($this->SQLCA() . " and cod_tipo_nomina=" . $nomina, null, ' ORDER BY ano ASC');
                $this->set('datos', $datos);
            }
        } else {
            $this->set('errorMessage', 'No llegaron datos completos para procesar');
            $datos = $this->cnmd15_depo_fideicomiso->FindAll($this->SQLCA() . " and cod_tipo_nomina=" . $nomina, null, ' ORDER BY ano ASC');
            $this->set('datos', $datos);
        }
    }

//fin guardar_modificar

    function cancelar($nomina = null) {
        $this->layout = "ajax";
        $datos = $this->cnmd15_depo_fideicomiso->FindAll($this->SQLCA() . " and cod_tipo_nomina=" . $nomina, null, ' ORDER BY ano ASC');
        $this->set('datos', $datos);
    }

// fin cancelar

    function eliminar_item($nomina = null, $ano_depo_fide = null) {
        $this->layout = "ajax";
        if ($nomina != null && $ano_depo_fide != null) {
            $encit_nom_ano = $this->cnmd15_depo_fideicomiso->findCount("cod_tipo_nomina =" . $nomina . " and ano =" . $ano_depo_fide);
            if ($encit_nom_ano != 0) {
                $swe = $this->cnmd15_depo_fideicomiso->execute("delete from cnmd15_depo_fideicomiso where " . $this->SQLCA() . " and cod_tipo_nomina=" . $nomina . " and ano =" . $ano_depo_fide);
                if ($swe > 1) {
                    $this->set('Message_existe', 'se elimino exitosamente');
                } else {
                    $this->set('errorMessage', 'no se pudo eliminar');
                }
            } else {
                $this->set('errorMessage', 'El codigo del item que intenta eliminar no existe registrado!');
            }
        } else {
            $this->set('errorMessage', 'debe seleccionar el item a eliminar');
        }

        $datos = $this->cnmd15_depo_fideicomiso->FindAll($this->SQLCA() . " and cod_tipo_nomina=" . $nomina, null, ' ORDER BY ano ASC');
        $this->set('datos', $datos);
    }

//fin eliminar item
}

//fin clase controller
?>