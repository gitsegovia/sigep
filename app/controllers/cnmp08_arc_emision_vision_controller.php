<?php

class Cnmp08ArcEmisionVisionController extends AppController {

    var $name = "cnmp08_arc_emision_vision";
    var $uses = array("cugd07_firmas_oficio_anulacion", "Cnmd01", "cnmd03_transaccion", "v_cnmd07_transacciones_actuales", "v_cnmd07_transacciones_actuales_con",
        "v_cnmd06_fichas","v_cnmd08_historia_trans_con","v_cnmd08_historia_trabajador", "v_cnmd08_historia_trabajador_vision", "cnmd01", "v_cnmd08_historia_transacciones");
    var $helpers = array('Html', 'Ajax', 'Javascript', 'Sisap', 'Fpdf', 'Infogob');


    function beforeFilter() {
        $this->checkSession();
    }

//fin function


    function checkSession() {
        if (!$this->Session->check('Usuario')) {
            $this->redirect('/salir/');
            exit();
        } else {
            $this->requestAction('/usuarios/actualizar_user');
        }
    }

//fin checksession

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



    function show_cod_nomina_2($cod_tipo_nomina = null) {
        $this->layout = "ajax";
        if ($cod_tipo_nomina != null) {
            $this->Session->write('tipo_nomina', $cod_tipo_nomina);
            $denominacion = $this->Cnmd01->field('denominacion', $conditions = $this->condicion() . " and cod_tipo_nomina='$cod_tipo_nomina'", $order = "cod_tipo_nomina ASC");
            $this->set('denominacion', $denominacion);
        }
        echo"<script>";
        echo"document.getElementById('cod_nomina').value='" . mascara_tres($cod_tipo_nomina) . "';";
        echo"document.getElementById('generar').disabled=true;";
        echo"</script>";
    }

//fin function


    function reporte_arc() {
        $this->envia_form_firmas(10006);

        $this->layout = "ajax";
        $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
        $this->concatena($lista, 'lista_nomina');
        $this->Session->delete('tipo_nomina');
        echo"<script>";
        echo"console.log('submit_pdf');";

        echo"document.getElementById('submit_pdf').disabled=true;";
        echo"</script>";

   }


function reporte_arc_info() {
	$this->layout="ajax";
	$cedula = $_SESSION['infogobierno']['cedula_identidad'];

	$nominas = $this->v_cnmd06_fichas->findAll("cedula_identidad=$cedula",'cod_dep, denominacion_dependencia, cod_tipo_nomina, cod_cargo, cod_ficha, denominacion_nomina, primer_apellido, primer_nombre');

	if(count($nominas)!=0){
		foreach($nominas as $n){
			$cod[] = $n['v_cnmd06_fichas']['cod_dep'].'-'.$n['v_cnmd06_fichas']['cod_tipo_nomina'];
			$deno[] = mascara_tres($n['v_cnmd06_fichas']['cod_tipo_nomina']).' - '.strtoupper($n['v_cnmd06_fichas']['denominacion_dependencia']).' - '.strtoupper($n['v_cnmd06_fichas']['denominacion_nomina']);
		}
		$lista=array_combine($cod, $deno);
	}else{
		$lista=array('0'=>'No se encuentra presente en ninguna nomina');
	}
	$this->set('lista',$lista);
	$this->set('cedula',$cedula);
	$this->set('nominas_trabajador',$nominas);
}


function mostrar_ano($cedula = null, $cod_tipo_nomina = null){
	$this->layout="ajax";

		$cod_dep_nomina = $cod_tipo_nomina;

                $codigos = explode('-',$cod_dep_nomina);
                if(!empty($codigos)){
                    $cod_dep = $codigos[0];
                    $cod_tipo_nomina = $codigos[1];
                }else{
                    $cod_dep = null;
                    $cod_tipo_nomina = null;
                }

        $rsp = $this->Cnmd01->execute("SELECT DISTINCT ano FROM cnmd08_historia_trabajador_fideicomiso WHERE " . $this->SQLCA_report(1) . " and cod_dep=$cod_dep and cod_tipo_nomina='" . ((int)$cod_tipo_nomina) . "' and cedula_identidad='$cedula' ORDER BY ano ASC");
        foreach ($rsp as $lp) {
            $vp[] = $lp[0]["ano"];
            $dp[] = $lp[0]["ano"];
        }
        if (isset($vp)) {
            $ano = array_combine($vp, $dp);
        } else {
            $ano = array();
        }
        $this->set('lista', $ano);
        echo "<script>document.getElementById('boton_garc').disabled=true;</script>";
}


    function show_ano_nomina_2_recibos($cod_tipo_nomina = null) {
        $this->layout = "ajax";
        $this->Session->delete('codig_tipo_nomina');
        $this->Session->write('codig_tipo_nomina', $cod_tipo_nomina);
        $rsp = $this->Cnmd01->execute("SELECT DISTINCT ano  FROM cnmd08_historia_trabajador_fideicomiso  WHERE " . $this->condicion() . " and cod_tipo_nomina='" . $cod_tipo_nomina . "'  ORDER BY ano ASC");
        foreach ($rsp as $lp) {
            $vp[] = $lp[0]["ano"];
            $dp[] = $lp[0]["ano"];
        }
        if (isset($vp)) {
            $ano = array_combine($vp, $dp);
        } else {
            $ano = array();
        }
        $this->set('lista', $ano);
        $this->set('opcion1', $cod_tipo_nomina);
        echo"<script>";
        echo"document.getElementById('submit_pdf').disabled=true;";
        echo"</script>";
    }



    function seleccion($var=null){
       $this->layout = "ajax";
       if ($var==1){
           echo '<script>document.getElementById("datos_personales").style.visibility="hidden";</script>';
       }else {
           echo '<script>document.getElementById("datos_personales").style.visibility="visible"</script>';
       }

    }

    function show_numero_nomina_2_recibos($tipo_nomina = null, $ano = null) {
        $this->layout = "ajax";
       $this->Session->write('ano_nomina', $ano);
       $this->Session->write('tipo_nomina', $tipo_nomina);


        $oculta="false";

        if ($ano == '') {
            echo "
            <script>
            document.getElementById('submit_pdf').disabled=true;
            </script>";

        }else {
        echo "<script>
            document.getElementById('submit_pdf').disabled=false;
            </script>";
        }

    }
//fin function

    function buscar_persona_historico_recibos($var1 = null) {
        $this->layout = "ajax";
        $this->set("opcion", $var1);
        $this->Session->delete('pista');
        $this->Session->write('pista_opcion', 2);
    }

//fin function

    function buscar_persona_historico_2_recibos($var1 = null, $var2 = null, $var3 = null) {
        $this->layout = "ajax";
        $sql_like = "";
        $tipo_nomina = $this->Session->read('tipo_nomina');
        $ano_nomina = $this->Session->read('ano_nomina');
        $opcion_buscar_historico = $this->Session->read('opcion_buscar_historico');

        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');

        if ($var3 == null) {
            $var2 = strtoupper($var2);
            $this->Session->write('pista', $var2);
            $var_like = $var2;
            if ($opcion_buscar_historico == 1) {
                $sql_like = $this->SQLCA() . " and (" . $this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion')) . ") and cod_tipo_nomina   =  '" . $tipo_nomina . "'  and ano='" . $ano_nomina . "'  ";
            } else {
                $sql_like = $this->SQLCA() . " and (" . $this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion')) . ") and cod_tipo_nomina   =  '" . $tipo_nomina . "'  and ano='" . $ano_nomina . "' ";
            }
            $Tfilas = $this->v_cnmd08_historia_trabajador_vision->findCount($sql_like); // v_cnmd08_historia_trabajador
            if ($Tfilas != 0) {
                $pagina = 1;
                $Tfilas = (int) ceil($Tfilas / 100);
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $this->set('total_paginas', $Tfilas);
                $this->set('pagina_actual', $pagina);
                $this->set('ultimo', $Tfilas);
                $datos_filas = $this->v_cnmd08_historia_trabajador_vision->findAll($sql_like, "  DISTINCT   cedula_identidad, cod_cargo, cod_ficha, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido ", "primer_nombre,primer_apellido ASC", 100, 1, null);
                $sql = "";

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
            if ($opcion_buscar_historico == 1) {
                $sql_like = $this->SQLCA() . " and (" . $this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion')) . ") and cod_tipo_nomina   =  '" . $tipo_nomina . "'  and ano='" . $ano_nomina . "'  ";
            } else {
                $sql_like = $this->SQLCA() . " and (" . $this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion')) . ") and cod_tipo_nomina   =  '" . $tipo_nomina . "'  and ano='" . $ano_nomina . "' ";
            }
            $Tfilas = $this->v_cnmd08_historia_trabajador_vision->findCount($sql_like);
            if ($Tfilas != 0) {
                $pagina = $var3;
                $Tfilas = (int) ceil($Tfilas / 100);
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $this->set('total_paginas', $Tfilas);
                $this->set('pagina_actual', $pagina);
                $this->set('ultimo', $Tfilas);
                $datos_filas = $this->v_cnmd08_historia_trabajador_vision->findAll($sql_like, "  DISTINCT   cedula_identidad, cod_cargo, cod_ficha, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido ", "primer_nombre,primer_apellido ASC", 100, $pagina, null);
                $sql = "";
                $this->set("datosFILAS", $datos_filas);
                $this->set('siguiente', $pagina + 1);
                $this->set('anterior', $pagina - 1);
                $this->bt_nav($Tfilas, $pagina);
                //$this->set("dato_a",$dato_a);
            } else {
                $this->set("datosFILAS", '');
            }
        }//fin else
        $this->set("opcion", $var1);
    }

//fin buscar_persona_historico_2_recibos

    function buscar_persona_historico_3_recibos($var1 = null, $var2 = null, $pag_num = null) {
        $this->layout = "ajax";
        $tipo_nomina = $this->Session->read('tipo_nomina');
        $ano_nomina = $this->Session->read('ano_nomina');

        $cedula_identidad = $var1;
        $cod_cargo = $var2;

        $opcion_buscar_historico = $this->Session->read('opcion_buscar_historico');

        if ($opcion_buscar_historico == 1) {
            $sql_like = $this->SQLCA() . " and cod_tipo_nomina   =  '" . $tipo_nomina . "'  and ano='" . $ano_nomina . "' and cedula_identidad='" . $cedula_identidad . "' and cod_cargo='" . $cod_cargo . "'  ";
        } else {
            $sql_like = $this->SQLCA() . " and cod_tipo_nomina   =  '" . $tipo_nomina . "'  and ano='" . $ano_nomina . "' and cedula_identidad='" . $cedula_identidad . "' and cod_cargo='" . $cod_cargo . "'  ";
        }

        $datos_cnmd07_transacciones_actuales = $this->v_cnmd08_historia_trans_con->findAll($sql_like, null, ' cod_tipo_transaccion,  cod_transaccion  ASC');
        $this->set("datos_cnmd07_transacciones_actuales", $datos_cnmd07_transacciones_actuales);

        echo"<script>";
            echo"document.getElementById('submit_pdf').disabled=false;";
        echo"</script>";
    }

//fin buscar_persona_historico_3_recibos


function envia_form_firmas($num_tipo_doc = null) {
        $this->layout = "ajax";

        if ($num_tipo_doc != null) {

            $firmantes = $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA() . " and tipo_documento=" . $num_tipo_doc);

            if ($firmantes != null) {
                $this->set('firma_existe', 'si');
                $this->set('b_readonly', 'readonly');
                $this->set('tipo_documento', $firmantes[0]['cugd07_firmas_oficio_anulacion']['tipo_documento']);
                $this->set('nombre_primera_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
                $this->set('cargo_primera_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
                $this->set('nombre_segunda_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
                $this->set('cargo_segunda_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
                $this->set('nombre_tercera_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
                $this->set('cargo_tercera_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
                $this->set('nombre_cuarta_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
                $this->set('cargo_cuarta_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
                $this->set('cedula_primera_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['primera_copia']);
                $this->set('cedula_segunda_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['segunda_copia']);
                $this->set('cedula_tercera_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['tercera_copia']);
                $this->set('cedula_cuarta_firma', $firmantes[0]['cugd07_firmas_oficio_anulacion']['cuarta_copia']);

            } else {
                $this->set('Message_existe', 'POR FAVOR, INGRESE LOS NOMBRES Y CARGO DE LOS FIRMANTES');
                $this->set('firma_existe', 'no');
                $this->set('b_readonly', '');
                $this->set('tipo_documento', $num_tipo_doc);
                $this->set('nombre_primera_firma', '');
                $this->set('cargo_primera_firma', '');
                $this->set('nombre_segunda_firma', '');
                $this->set('cargo_segunda_firma', '');
                $this->set('nombre_tercera_firma', '');
                $this->set('cargo_tercera_firma', '');
                $this->set('nombre_cuarta_firma', '');
                $this->set('cargo_cuarta_firma', '');
                $this->set('cedula_primera_firma', '');
                $this->set('cedula_segunda_firma', '');
                $this->set('cedula_tercera_firma', '');
                $this->set('cedula_cuarta_firma', '');

            }
        } else {
            $this->set('errorMessage', 'Disculpe, No llego el N&uacute;mero del Tipo de Documento para realizar el proceso de firmas');
        } // fin else num_tipo_doc dif. null
    }

// fin funcion envia_form_firmas

    function guardar_editar_firmas() {
        $this->layout = "ajax";

        $cp = $this->Session->read('SScodpresi');
        $ce = $this->Session->read('SScodentidad');
        $cti = $this->Session->read('SScodtipoinst');
        $ci = $this->Session->read('SScodinst');
        $cd = $this->Session->read('SScoddep');

        $tipo_doc = $this->data['cugd07_firmas_oficio_anulacion']['tipo_documento'];
        $nombre_primera_firma = $this->data['cugd07_firmas_oficio_anulacion']['nombre_primera_firma'];
        $cargo_primera_firma = $this->data['cugd07_firmas_oficio_anulacion']['cargo_primera_firma'];
        $nombre_segunda_firma = $this->data['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma'];
        $cargo_segunda_firma = $this->data['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma'];
        $nombre_tercera_firma = $this->data['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma'];
        $cargo_tercera_firma = $this->data['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma'];
        $nombre_cuarta_firma = $this->data['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma'];
        $cargo_cuarta_firma = $this->data['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma'];

        $cedula_primera_firma = isset($this->data['cugd07_firmas_oficio_anulacion']['cedula_primera_firma']) ? $this->data['cugd07_firmas_oficio_anulacion']['cedula_primera_firma'] : '';
        $cedula_segunda_firma= isset($this->data['cugd07_firmas_oficio_anulacion']['cedula_primera_firma']) ? $this->data['cugd07_firmas_oficio_anulacion']['cedula_segunda_firma'] : '';
        $cedula_tercera_firma = isset($this->data['cugd07_firmas_oficio_anulacion']['cedula_primera_firma']) ? $this->data['cugd07_firmas_oficio_anulacion']['cedula_tercera_firma'] : '';
        $cedula_cuarta_firma = isset($this->data['cugd07_firmas_oficio_anulacion']['cedula_primera_firma']) ? $this->data['cugd07_firmas_oficio_anulacion']['cedula_cuarta_firma'] : '';



        $enc_td_firma = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA() . " and tipo_documento=$tipo_doc");

        if ($enc_td_firma == 0) {
            $muestr_accion = 'Registradas';
            $sql_ejecutar = "INSERT INTO cugd07_firmas_oficio_anulacion VALUES ($cp, $ce, $cti, $ci, $cd, $tipo_doc,'$nombre_primera_firma', '$cargo_primera_firma', '$nombre_segunda_firma', '$cargo_segunda_firma', '$nombre_tercera_firma', '$cargo_tercera_firma','$cedula_primera_firma','$cedula_segunda_firma','$cedula_tercera_firma','$cedula_cuarta_firma','','','','','$nombre_cuarta_firma', '$cargo_cuarta_firma');";
        } else {
            $muestr_accion = 'Modificadas';
            $sql_ejecutar = "UPDATE cugd07_firmas_oficio_anulacion SET nombre_primera_firma='$nombre_primera_firma', cargo_primera_firma='$cargo_primera_firma', nombre_segunda_firma='$nombre_segunda_firma', cargo_segunda_firma='$cargo_segunda_firma', nombre_tercera_firma='$nombre_tercera_firma', cargo_tercera_firma='$cargo_tercera_firma', nombre_cuarta_firma='$nombre_cuarta_firma', cargo_cuarta_firma='$cargo_cuarta_firma', primera_copia='$cedula_primera_firma', segunda_copia='$cedula_segunda_firma', tercera_copia='$cedula_tercera_firma', cuarta_copia='$cedula_cuarta_firma' WHERE " . $this->SQLCA() . " and tipo_documento=" . $tipo_doc;
        }

        $swi = $this->cugd07_firmas_oficio_anulacion->execute($sql_ejecutar);


        if ($swi > 1) {
            $this->set('Message_existe', 'Las firmas fuer&oacute;n ' . $muestr_accion . ' correctamente');
        } else {
            $this->set('errorMessage', 'Las firmas no fuer&oacute;n ' . $muestr_accion . '');
        }


        $this->reporte_arc();
        $this->render('reporte_arc');

    }



     function modificar_firmas_form($reportem_tipo = null) {
        $this->layout = "ajax";
        $this->set('reportem_tipo', $reportem_tipo);
        $this->set('Message_existe', 'Puede modificar los nombres y cargos de los firmantes');
    }


function generar_reporte_arc($proviene = null){
	/**
	 * ESTA FUNCION IMPRIME TANTO PARA EL MODULO DE NOMINA COMO PARA EL MODULO INFOGOBIERNO...
	 * */
	$this->layout = "pdf";
	$cedula_identidad=$this->data['reporte_arc']['cedula_identidad2'];
	$ano_nomina=$this->data['reporte_arc']['ano_nomina'];

	if($proviene == 'i'){ // InfoGobierno...
		$cod_dep_nomina=$this->data['reporte_arc']['cod_nomina'];

                $codigos = explode('-',$cod_dep_nomina);
                if(!empty($codigos)){
                    $cod_dep = $codigos[0];
                    $cod_tipo_nomina = $codigos[1];
                }else{
                    $cod_dep = null;
                    $cod_tipo_nomina = null;
                }

		$condicion = $this->SQLCA_report(1)." and cod_dep=$cod_dep and ano='$ano_nomina' and cod_tipo_nomina=".$cod_tipo_nomina;
	}else{
		$cod_tipo_nomina=$this->data['reporte_arc']['cod_nomina'];
		$condicion = $this->SQLCA_report()." and ano='$ano_nomina' and cod_tipo_nomina=".$cod_tipo_nomina;
	}

       if (isset($cedula_identidad) && ($cedula_identidad !='')){$condicion=$condicion." and cedula_identidad=".$cedula_identidad;}
				$sql_vista = "SELECT * FROM v_cnmd08_arc_historia_cuatro WHERE ".$condicion;
				$vista = $this->Cnmd01->execute($sql_vista);
				$this->set('vista',$vista);
                $this->set('titulo_reporte','REPORTE ARC');

				$this->envia_form_firmas(10006);

}// fin generar_reporte_arc

}//fin Class

?>
