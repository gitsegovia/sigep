<?php

class Cpcp02Controller extends AppController {

    var $uses = array('arrd05', 'cugd02_institucion', 'ccfd01_cuenta', 'ccfd01_division', 'ccfd01_subcuenta', 'ccfd01_tipo', 'v_cpcd02', 'cugd10_imagenes', 'ccfd01_subdivision', 'cobd01_contratoobras_cuerpo', 'cepd02_contratoservicio_cuerpo', 'cscd03_cotizacion_encabezado', 'cpcd01', 'cpcd02', 'cugd01_estados', 'cugd01_municipios', 'cpcd02_requisitos_proveedores', 'cpcd02_requisitos_contratistas', 'cpcd02_requisitos_cooperativas', 'cpcd02_requisitos_personas');
    var $helpers = array('Html', 'Ajax', 'Javascript', 'Sisap');

   var $categoria_suministro =array('1' => '1.-Bienes', '2' => '2.-Servicios', '3' => '3.-Bienes y Servicios', '4' => '4.-Obras');

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

    function SQLCAX($ano = null) {//sql para busqueda de codigos de arranque con y sin año
        $sql_re = "cod_republica=" . $this->verifica_SS(1) . "  and    ";
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
            $this->set($nomVar, $cod);
        }
    }

//fin concatena

    function index($var = null) {
        $this->layout = "ajax";

        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');

        $this->set('sol', 1);
        $this->Session->delete('rif');
        $this->data['cpcp02'] = null;
        $listaramo = $this->cpcd01->generateList(null, ' upper(trim(denominacion)) ASC', null, '{n}.cpcd01.codigo', '{n}.cpcd01.denominacion');
        $this->concatena_tres_digitos($listaramo, 'codigo');
        $cond = 'cod_republica=' . $this->Session->read('SScodpresi');
        $listaramo = $this->cugd01_estados->generateList($cond, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
        $this->concatena($listaramo, 'cod_estado');
        $ss = $this->cpcd02->findAll(null, array('numero_expediente'), 'numero_expediente DESC', 1, 1, null);

        $this->set('categoria_suministro', $this->categoria_suministro);

        $vivienda = array('1' => 'Quinta', '2' => 'Casa-Quinta', '3' => 'Casa popular', '4' => 'apartamento', '5' => 'Vivienda popular', '6' => 'Rancho', '7' => 'Otro', '8' => 'Ninguno');
        $this->set($vivienda, 'vivienda');

		$cugd02i = $this->cugd02_institucion->findAll("cod_tipo_institucion=$cod_tipo_inst and cod_institucion=$cod_inst", array('denominacion'), null, 1, 1, null);
		$cugd02d = $this->cugd02_institucion->execute("SELECT denominacion FROM cugd02_dependencias WHERE cod_tipo_institucion=$cod_tipo_inst and cod_institucion=$cod_inst and cod_dependencia=$cod_dep;");

		$this->set('reg_inst', $cugd02i[0]["cugd02_institucion"]["denominacion"]);
		$this->set('reg_dep', $cugd02d[0][0]["denominacion"]);

        if ($ss == null) {
            $new_numero = 1;
        } else {
            $new_numero = $ss[0]["cpcd02"]["numero_expediente"] + 1;
        }
        $this->set('numero', $new_numero);
    }

//fin index

    function select3($select = null, $var = null) { //select codigos presupuestarios
        $this->layout = "ajax";
        if ($var != null) {
            $cond = $this->SQLCAX();
            switch ($select) {
                case 'estados':
                    $this->set('SELECT', 'municipios');
                    $this->set('codigo', 'estados');
                    $this->set('seleccion', '');
                    $this->set('n', 1);
                    $lista = $this->cugd01_estados->generateList($cond, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.cod_estado');
                    $this->AddCero('vector', $lista);
                    break;
                case 'municipios':
                    $this->set('SELECT', 'municipios');
                    $this->set('codigo', 'municipios');
                    $this->set('seleccion', '');
                    $this->set('n', 2);
                    $this->set('no', 'no');
                    $this->Session->write('esta', $var);
                    $cond .=" cod_estado=" . $var;
                    $lista = $this->cugd01_municipios->generateList($cond, 'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
                    $this->concatena($lista, 'vector');
                    echo "<script>";
                    echo "document.getElementById('codi_codpresupuestarios_2').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('deno_2').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "</script>";
                    break;
            }//fin wsitch
        } else {
            $this->set('SELECT', '');
            $this->set('codigo', '');
            $this->set('seleccion', '');
            $this->set('n', 3);
            $this->set('no', 'no');
            $this->set('vector', '');
        }
    }

//fin select codigos presupuestarios

    function mostrar4($select = null, $var = null) { //mostrar3 codigos presupuestarios
        $this->layout = "ajax";
        if ($var != null) {
            $cond = $this->SQLCAX();
            switch ($select) {
                case 'estados':
                    $this->Session->write('esta', $var);
                    $cond .="  cod_estado=" . $var;
                    $a = $this->cugd01_estados->findAll($cond);
                    $cod = $a[0]['cugd01_estados']['cod_estado'] > 9 ? $a[0]['cugd01_estados']['cod_estado'] : "0" . $a[0]['cugd01_estados']['cod_estado'];
                    $this->set('codi', $cod);
                    break;
                case 'municipios':
                    $esta = $this->Session->read('esta');
                    $this->Session->write('muni', $var);
                    $cond .="  cod_estado=" . $esta . " and cod_municipio=" . $var;
                    $a = $this->cugd01_municipios->findAll($cond);
                    $cod = $a[0]['cugd01_municipios']['cod_municipio'] > 9 ? $a[0]['cugd01_municipios']['cod_municipio'] : "0" . $a[0]['cugd01_municipios']['cod_municipio'];
                    $this->set('codi', $cod);
                    break;
            }//fin wsitch
        } else {
            echo "";
        }
    }

//fin mostrar3 codigos presupuestarios

    function mostrar3($select = null, $var = null) { //mostrar3 codigos presupuestarios
        $this->layout = "ajax";
        if ($var != null && !empty($var)) {
            $cond = $this->SQLCAX();
            switch ($select) {
                case 'estados':
                    $this->Session->write('esta', $var);
                    $cond .="  cod_estado=" . $var;
                    $a = $this->cugd01_estados->findAll($cond);
                    $den = $a[0]['cugd01_estados']['denominacion'];
                    $this->set('deno', $den);
                    break;
                case 'municipios':
                    $esta = $this->Session->read('esta');
                    $this->Session->write('muni', $var);
                    $cond .=" cod_estado=" . $esta . " and cod_municipio=" . $var;
                    $a = $this->cugd01_municipios->findAll($cond);
                    $den = $a[0]['cugd01_municipios']['denominacion'];
                    $this->set('deno', $den);
                    break;
            }//fin wsitch
        } else {
            echo "";
        }
    }

//fin mostrar3 co

    function codigo_cpcp02($codigo) {
        $this->layout = "ajax";
        $a = $this->cpcd01->findAll("codigo=" . $codigo, array('codigo', 'denominacion'));
        $this->set("a", $a[0]['cpcd01']['codigo']);
    }

//fin cpcp02_codigo

    function denominacion_cpcp02($codigo) {
        $this->layout = "ajax";
        $b = $this->cpcd01->findAll("codigo=" . $codigo, array('codigo', 'denominacion'));
        $this->set("b", $b[0]['cpcd01']['denominacion']);
    }

//fin cpcp02_denominacion

    function guardar() {
        $this->layout = "ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');

        if (!empty($this->data)) {
            $rif1 = $this->data['cpcp02']['rif'];
            $rif = strtoupper($rif1);
            $denominacion = $this->data['cpcp02']['razon'];
            $numero_expediente = $this->data['cpcp02']['numero'];
            $numero_empleados = $this->data['cpcp02']['numero_empleados'];
            if ($numero_empleados == "")
                $numero_empleados = 0;
            $objeto = $this->data['cpcp02']['objeto'];
            $ramo_comercial = $this->data['cpcp02']['codigo_ramo'];
            $direccion_comercial = $this->data['cpcp02']['direccion_comercial'];
            $actividades_principales = $this->data['cpcp02']['descripcion_objeto'];
            $cod_estado = $this->data['cpcp02']['cod_estados'];
            $cod_municipio = $this->data['cpcp02']['codigo_municipios'];
            $inscripcion_sunacoop = $this->data['cpcp02']['numero_sunacoop'];
            if ($inscripcion_sunacoop == "")
                $inscripcion_sunacoop = 0;
            $codigo_area_empresa = $this->data['cpcp02']['codigo_area_empresa'];
            if ($codigo_area_empresa == "")
                $codigo_area_empresa = 0;
            $telefonos = $this->data['cpcp02']['telefonos_empresa'];
            if ($telefonos == "")
                $telefonos = 0;
            $codigo_postal = $this->data['cpcp02']['zona_postal_empresa'];
            if ($codigo_postal == "")
                $codigo_postal = 0;
            $correo_electronico_empresa = $this->data['cpcp02']['correo_empresa'];
            if ($correo_electronico_empresa == "")
                $correo_electronico_empresa = 0;
            $equipos_disponibles = $this->data['cpcp02']['equipos_disponibles'];
            if ($equipos_disponibles == "")
                $equipos_disponibles = 0;
            $capacidad_financiera = $this->Formato1($this->data['cpcp02']['capacidad_financiera']);
            if ($capacidad_financiera == "")
                $capacidad_financiera = 0;
            $registro_mercantil = $this->data['cpcp02']['registro_mercantil'];
            if ($registro_mercantil == "")
                $registro_mercantil = 0;
            $socios = $this->data['cpcp02']['socios'];
            if ($socios == "")
                $socios = 0;
            $representante_legal = $this->data['cpcp02']['nombre_representante'];
            if ($representante_legal == "")
                $representante_legal = 0;
            $direccion_representante = $this->data['cpcp02']['direccion_representante'];
            if ($direccion_representante == "")
                $direccion_representante = 0;
            $cedula_identidad = $this->data['cpcp02']['cedula_ide'];
            if ($cedula_identidad == "")
                $cedula_identidad = 0;
            $codigo_area_representante = $this->data['cpcp02']['codigo_area_representante'];
            if ($codigo_area_representante == "")
                $codigo_area_representante = 0;
            $telefonos_fijos_representante = $this->data['cpcp02']['telefonos_fijos'];
            if ($telefonos_fijos_representante == "")
                $telefonos_fijos_representante = 0;
            $telefonos_moviles_representante = $this->data['cpcp02']['telefonos_moviles'];
            if ($telefonos_moviles_representante == "")
                $telefonos_moviles_representante = 0;
            $correo_electronico_representante = $this->data['cpcp02']['correo_representante'];
            if ($correo_electronico_representante == "")
                $correo_electronico_representante = 0;
            $numero_ocei = $this->data['cpcp02']['numero_ocei'];
            if ($numero_ocei == "")
                $numero_ocei = 0;
            $f1 = $this->data['cpcp02']['fecha_ocei'];
            $f1 = $f1 == "" ? "01/01/1900" : $f1;
            $fecha_vencimiento_ocei = $f1[6] . $f1[7] . $f1[8] . $f1[9] . "-" . $f1[3] . $f1[4] . "-" . $f1[0] . $f1[1];
            $numero_solvencia_laboral = $this->data['cpcp02']['numero_laboral'];
            if ($numero_solvencia_laboral == "")
                $numero_solvencia_laboral = 0;
            $f2 = $this->data['cpcp02']['fecha_laboral'];
            $f2 = $f2 == "" ? "01/01/1900" : $f2;
            $fecha_vencimiento_laboral = $f2[6] . $f2[7] . $f2[8] . $f2[9] . "-" . $f2[3] . $f2[4] . "-" . $f2[0] . $f2[1];
            $numero_solvencia_seguro = $this->data['cpcp02']['numero_seguro'];
            if ($numero_solvencia_seguro == "")
                $numero_solvencia_seguro = 0;
            $f3 = $this->data['cpcp02']['fecha_seguro'];
            $f3 = $f3 == "" ? "01/01/1900" : $f3;
            $fecha_vencimiento_seguro = $f3[6] . $f3[7] . $f3[8] . $f3[9] . "-" . $f3[3] . $f3[4] . "-" . $f3[0] . $f3[1];
            $numero_solvencia_ince = $this->data['cpcp02']['numero_ince'];
            if ($numero_solvencia_ince == "")
                $numero_solvencia_ince = 0;
            $f4 = $this->data['cpcp02']['fecha_ince'];
            $f4 = $f4 == "" ? "01/01/1900" : $f4;
            $fecha_vencimiento_ince = $f4[6] . $f4[7] . $f4[8] . $f4[9] . "-" . $f4[3] . $f4[4] . "-" . $f4[0] . $f4[1];
            $numero_solvencia_municipal = $this->data['cpcp02']['numero_municipal'];
            if ($numero_solvencia_municipal == "")
                $numero_solvencia_municipal = 0;
            $f5 = $this->data['cpcp02']['fecha_municipal'];
            $f5 = $f5 == "" ? "01/01/1900" : $f5;
            $fecha_vencimiento_municipal = $f5[6] . $f5[7] . $f5[8] . $f5[9] . "-" . $f5[3] . $f5[4] . "-" . $f5[0] . $f5[1];
            $observacion = $this->data['cpcp02']['observacion'];
            if ($observacion == "")
                $observacion = 0;
            $f6 = $this->data['cpcp02']['fecha_inscripcion'];
            $f6 = $f6 == "" ? "01/01/1900" : $f6;
            $fecha_inscripcion = $f6[6] . $f6[7] . $f6[8] . $f6[9] . "-" . $f6[3] . $f6[4] . "-" . $f6[0] . $f6[1];
            $f7 = $this->data['cpcp02']['fecha_actualizacion'];
            $f7 = $f7 == "" ? "01/01/1900" : $f7;
            $fecha_actualizacion = $f7[6] . $f7[7] . $f7[8] . $f7[9] . "-" . $f7[3] . $f7[4] . "-" . $f7[0] . $f7[1];
            $exento = $this->data['cpcp02']['exento'];
            if ($exento == "")
                $exento = 2;
            $cero = 0;

             $fecha_inscrip_inicial_snc=date("Y-m-d",strtotime($this->data['cpcp02']['fecha_inscrip_inicial_snc']));

             $categoria_suministro = $this->data['cpcp02']['categoria_suministro'];
             $suministro_cliente_similar = $this->data['cpcp02']['suministro_cliente_similar'];

             $registrado_institucion = $this->data['cpcp02']['registrado_institucion'];
             $registrado_dependencia = $this->data['cpcp02']['registrado_dependencia'];
             $registrado_username = $this->data['cpcp02']['registrado_username'];
             $registrado_fecha = $this->Cfecha($this->data['cpcp02']['registrado_fecha'], 'A-M-D');
             $condicion_actividad = $this->data['cpcp02']['condicion_actividad'];
        }
/////cuenta 300
//	$instituciones = $this->arrd05->findAll('cod_dep=1');
//	foreach($instituciones as $it){


        $presu = $this->ccfd01_subdivision->findAll($this->condicionNDEP() . ' and cod_dep=1 and cod_tipo_cuenta=1 and cod_cuenta=300 and cod_subcuenta=001 and cod_division=' . $objeto, array('cod_subdivision'), 'cod_subdivision DESC', 1, 1, null);
        if ($presu == null) {
            $gasto_presu_subdivision = 1;
        } else {
            $gasto_presu_subdivision = $presu[0]["ccfd01_subdivision"]["cod_subdivision"] + 1;
        }
/////cuenta 103
        $pagar = $this->ccfd01_subdivision->findAll($this->condicionNDEP() . ' and cod_dep=1 and cod_tipo_cuenta=2 and cod_cuenta=103 and cod_subcuenta=001 and cod_division=' . $objeto, array('cod_subdivision'), 'cod_subdivision DESC', 1, 1, null);
        if ($pagar == null) {
            $gasto_pagar_subdivision = 1;
        } else {
            $gasto_pagar_subdivision = $pagar[0]["ccfd01_subdivision"]["cod_subdivision"] + 1;
        }
/////cuenta 101
        $pago = $this->ccfd01_subdivision->findAll($this->condicionNDEP() . ' and cod_dep=1 and cod_tipo_cuenta=2 and cod_cuenta=101 and cod_subcuenta=001 and cod_division=' . $objeto, array('cod_subdivision'), 'cod_subdivision DESC', 1, 1, null);
        if ($pago == null) {
            $orden_pago_subdivision = 1;
        } else {
            $orden_pago_subdivision = $pago[0]["ccfd01_subdivision"]["cod_subdivision"] + 1;
        }
/////cuenta 128
        $prov = $this->ccfd01_subdivision->findAll($this->condicionNDEP() . ' and cod_dep=1 and cod_tipo_cuenta=1 and cod_cuenta=128 and cod_subcuenta=001 and cod_division=' . $objeto, array('cod_subdivision'), 'cod_subdivision DESC', 1, 1, null);
        if ($prov == null) {
            $anticipo_prov_subdivision = 1;
        } else {
            $anticipo_prov_subdivision = $prov[0]["ccfd01_subdivision"]["cod_subdivision"] + 1;
        }
        /*
          $cod_presi 		= $it['arrd05']['cod_presi'];
          $cod_entidad 	= $it['arrd05']['cod_entidad'];
          $cod_tipo_inst	= $it['arrd05']['cod_tipo_inst'];
          $cod_inst		= $it['arrd05']['cod_inst'];
          $cod_dep		= 1;
         */
        $SQL_INSERT300 = "INSERT INTO ccfd01_subdivision (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta, cod_cuenta,
  		cod_subcuenta, cod_division, cod_subdivision, denominacion)";
        $SQL_INSERT300 .=" VALUES ($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,1,300,001,$objeto,$gasto_presu_subdivision,'" . $denominacion . "')";
        $SQL_INSERT103 = "INSERT INTO ccfd01_subdivision (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta, cod_cuenta,
  		cod_subcuenta, cod_division, cod_subdivision, denominacion)";
        $SQL_INSERT103 .=" VALUES ($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,2,103,001,$objeto,$gasto_pagar_subdivision,'" . $denominacion . "')";
        $SQL_INSERT101 = "INSERT INTO ccfd01_subdivision (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta, cod_cuenta,
  		cod_subcuenta, cod_division, cod_subdivision, denominacion)";
        $SQL_INSERT101 .=" VALUES ($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,2,101,001,$objeto,$orden_pago_subdivision,'" . $denominacion . "')";
        $SQL_INSERT128 = "INSERT INTO ccfd01_subdivision (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta, cod_cuenta,
  		cod_subcuenta, cod_division, cod_subdivision, denominacion)";
        $SQL_INSERT128 .=" VALUES ($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,1,128,001,$objeto,$anticipo_prov_subdivision,'" . $denominacion . "')";


        if ($objeto == 3) { //cooperativa
            $SQL_INSERT = "INSERT INTO cpcd02 (rif, objeto, denominacion, ramo_comercial, direccion_comercial, codigo_area_empresa, telefonos, codigo_postal, correo_electronico_empresa, equipos_disponibles, actividades_principales, capacidad_financiera, registro_mercantil, socios, representante_legal, direccion_representante, codigo_area_representante, telefonos_fijos_representante, telefonos_moviles_representante, correo_electronico_representante, numero_ocei, fecha_vencimiento_ocei, numero_solvencia_laboral, fecha_vencimiento_laboral, numero_solvencia_seguro, fecha_vencimiento_seguro, numero_solvencia_ince, fecha_vencimiento_ince, numero_solvencia_municipal, fecha_vencimiento_municipal, observacion, numero_empleados, inscripcion_sunacoop, cod_estado, cod_municipio, numero_expediente, fecha_inscripcion, fecha_actualizacion, cedula_identidad, imagen_constancia_ocei, imagen_solvencia_laboral, imagen_solvencia_ivss,  imagen_solvencia_ince, imagen_solvencia_municipal, imagen_cedula_identidad, imagen_solvencia_civ, imagen_titulo_universitario,exento_islr_cooperativa,gasto_presu_tipo, gasto_presu_cuenta, gasto_presu_subcuenta, gasto_presu_division, gasto_presu_subdivision, gasto_pagar_tipo,
  		gasto_pagar_cuenta, gasto_pagar_subcuenta, gasto_pagar_division, gasto_pagar_subdivision, orden_pago_tipo, orden_pago_cuenta,
  		orden_pago_subcuenta, orden_pago_division, orden_pago_subdivision, anticipo_prov_tipo, anticipo_prov_cuenta, anticipo_prov_subcuenta,
  		anticipo_prov_division, anticipo_prov_subdivision,fecha_inscrip_inicial_snc,categoria_suministro,suministro_cliente_similar,registrado_institucion,registrado_dependencia,registrado_username,registrado_fecha, condicion_actividad)";
            $SQL_INSERT .=" VALUES ('" . $rif . "', $objeto, '" . $denominacion . "', $ramo_comercial, '" . $direccion_comercial . "', $codigo_area_empresa, '" . $telefonos . "', '" . $codigo_postal . "', '" . $correo_electronico_empresa . "', '" . $equipos_disponibles . "', '" . $actividades_principales . "', $capacidad_financiera, '" . $registro_mercantil . "', '" . $socios . "', '" . $representante_legal . "', '" . $direccion_representante . "', '" . $codigo_area_representante . "', '" . $telefonos_fijos_representante . "', '" . $telefonos_moviles_representante . "', '" . $correo_electronico_representante . "', '" . $numero_ocei . "', '" . $fecha_vencimiento_ocei . "', '" . $numero_solvencia_laboral . "', '" . $fecha_vencimiento_laboral . "', '" . $numero_solvencia_seguro . "', '" . $fecha_vencimiento_seguro . "', '" . $numero_solvencia_ince . "', '" . $fecha_vencimiento_ince . "', '" . $numero_solvencia_municipal . "', '" . $fecha_vencimiento_municipal . "', '" . $observacion . "', $numero_empleados, '" . $inscripcion_sunacoop . "', $cod_estado, $cod_municipio, $numero_expediente, '" . $fecha_inscripcion . "', '" . $fecha_actualizacion . "', $cedula_identidad, '" . $cero . "', '" . $cero . "', '" . $cero . "',  '" . $cero . "', '" . $cero . "', '" . $cero . "', '" . $cero . "', '" . $cero . "',$exento,
	 	1,300,001,$objeto,$gasto_presu_subdivision,2,103,001,$objeto,$gasto_pagar_subdivision,2,101,001,$objeto,$orden_pago_subdivision,1,128,001,$objeto,$anticipo_prov_subdivision,'$fecha_inscrip_inicial_snc',$categoria_suministro,$suministro_cliente_similar,'$registrado_institucion','$registrado_dependencia','$registrado_username','$registrado_fecha', $condicion_actividad)";


        } else if ($objeto != 3) { //cualquier otro
            $SQL_INSERT = "INSERT INTO cpcd02 (rif, objeto, denominacion, ramo_comercial, direccion_comercial, codigo_area_empresa, telefonos, codigo_postal, correo_electronico_empresa, equipos_disponibles, actividades_principales, capacidad_financiera, registro_mercantil, socios, representante_legal, direccion_representante, codigo_area_representante, telefonos_fijos_representante, telefonos_moviles_representante, correo_electronico_representante, numero_ocei, fecha_vencimiento_ocei, numero_solvencia_laboral, fecha_vencimiento_laboral, numero_solvencia_seguro, fecha_vencimiento_seguro, numero_solvencia_ince, fecha_vencimiento_ince, numero_solvencia_municipal, fecha_vencimiento_municipal, observacion, numero_empleados, inscripcion_sunacoop, cod_estado, cod_municipio, numero_expediente, fecha_inscripcion, fecha_actualizacion, cedula_identidad, imagen_constancia_ocei, imagen_solvencia_laboral, imagen_solvencia_ivss,  imagen_solvencia_ince, imagen_solvencia_municipal, imagen_cedula_identidad, imagen_solvencia_civ, imagen_titulo_universitario,exento_islr_cooperativa,gasto_presu_tipo, gasto_presu_cuenta, gasto_presu_subcuenta, gasto_presu_division, gasto_presu_subdivision, gasto_pagar_tipo,
  		gasto_pagar_cuenta, gasto_pagar_subcuenta, gasto_pagar_division, gasto_pagar_subdivision, orden_pago_tipo, orden_pago_cuenta,
  		orden_pago_subcuenta, orden_pago_division, orden_pago_subdivision, anticipo_prov_tipo, anticipo_prov_cuenta, anticipo_prov_subcuenta,
  		anticipo_prov_division, anticipo_prov_subdivision,fecha_inscrip_inicial_snc,categoria_suministro,suministro_cliente_similar,registrado_institucion,registrado_dependencia,registrado_username,registrado_fecha, condicion_actividad)";
            $SQL_INSERT .=" VALUES ('" . $rif . "', $objeto, '" . $denominacion . "', $ramo_comercial, '" . $direccion_comercial . "', $codigo_area_empresa, '" . $telefonos . "', '" . $codigo_postal . "', '" . $correo_electronico_empresa . "', '" . $equipos_disponibles . "', '" . $actividades_principales . "', $capacidad_financiera, '" . $registro_mercantil . "', '" . $socios . "', '" . $representante_legal . "', '" . $direccion_representante . "', '" . $codigo_area_representante . "', '" . $telefonos_fijos_representante . "', '" . $telefonos_moviles_representante . "', '" . $correo_electronico_representante . "', '" . $numero_ocei . "', '" . $fecha_vencimiento_ocei . "', '" . $numero_solvencia_laboral . "', '" . $fecha_vencimiento_laboral . "', '" . $numero_solvencia_seguro . "', '" . $fecha_vencimiento_seguro . "', '" . $numero_solvencia_ince . "', '" . $fecha_vencimiento_ince . "', '" . $numero_solvencia_municipal . "', '" . $fecha_vencimiento_municipal . "', '" . $observacion . "', $numero_empleados, '" . $cero . "', $cod_estado, $cod_municipio, $numero_expediente, '" . $fecha_inscripcion . "', '" . $fecha_actualizacion . "', $cedula_identidad, '" . $cero . "', '" . $cero . "', '" . $cero . "',  '" . $cero . "', '" . $cero . "', '" . $cero . "', '" . $cero . "', '" . $cero . "',$exento,
	 	1,300,001,$objeto,$gasto_presu_subdivision,2,103,001,$objeto,$gasto_pagar_subdivision,2,101,001,$objeto,$orden_pago_subdivision,1,128,001,$objeto,$anticipo_prov_subdivision,'$fecha_inscrip_inicial_snc',$categoria_suministro,$suministro_cliente_similar,'$registrado_institucion','$registrado_dependencia','$registrado_username','$registrado_fecha', $condicion_actividad)";
        }
        $x300 = $this->ccfd01_subdivision->execute($SQL_INSERT300);
        $x103 = $this->ccfd01_subdivision->execute($SQL_INSERT103);
        $x101 = $this->ccfd01_subdivision->execute($SQL_INSERT101);
        $x128 = $this->ccfd01_subdivision->execute($SQL_INSERT128);
        //}
        $resp = $this->cpcd02->execute($SQL_INSERT);
        if ($resp > 1) {
            $this->set('Message_existe', 'Registro Agregado con exito.');
            $this->index();
            $this->render("index"); //echo "si entro";
        } else if ($resp <= 1) {
            $this->set('errorMessage', 'Disculpe, El Registro no fue creado.');
            $this->index();
            $this->render("index"); //echo "no entro";
        }
        $this->data = null;
        $this->set('Message_existe', 'Registro Agregado con exito.');
    }

//fin guardar

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

    function consultar($pagina = null) {
        $this->layout = "ajax";

           $this->set('categoria_suministro', $this->categoria_suministro);

        if ($pagina != null) {
            $pagina = $pagina;
            $Tfilas = $this->v_cpcd02->findCount();
            if ($Tfilas == 0) {
                $this->set('errorMessage', 'NO EXISTEN DATOS.');
                $this->index();
                $this->render("index");
            }
            if ($Tfilas != 0) {
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $datacpcp01 = $this->v_cpcd02->findAll(null, null, 'rif ASC', 1, $pagina, null);
                $this->set('datos', $datacpcp01);
                $this->set('siguiente', $pagina + 1);
                $this->set('anterior', $pagina - 1);
                $this->bt_nav($Tfilas, $pagina);
            }
        } else {
            $pagina = 1;
            $Tfilas = $this->v_cpcd02->findCount();
            if ($Tfilas == 0) {
                $this->set('errorMessage', 'NO EXISTEN DATOS.');
                $this->index();
                $this->render("index");
            }
            if ($Tfilas != 0) {
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $datacpcp01 = $this->v_cpcd02->findAll(null, null, 'rif ASC', 1, $pagina, null);
                $this->set('datos', $datacpcp01);
                $this->set('siguiente', $pagina + 1);
                $this->set('anterior', $pagina - 1);
                $this->bt_nav($Tfilas, $pagina);
            }
        }
    }

//fin function consultar2

    function eliminar($rif) {
        $this->layout = "ajax";
        $sql = "rif='" . $rif . "'";
        $x = $this->cpcd02->findCount($sql);
        $veri_compra = $this->cscd03_cotizacion_encabezado->findCount($sql);
        $veri_obras = $this->cobd01_contratoobras_cuerpo->findCount($sql);
        $veri_servicios = $this->cepd02_contratoservicio_cuerpo->findCount($sql);
        if ($veri_compra != 0 || $veri_obras != 0 || $veri_servicios != 0) {
            $this->set('errorMessage', 'No se Puede eliminar este RIF ya fue utilizado en otro modulo');
            $this->consultar();
            $this->render("consultar");
        }
        if ($x != 0 && $veri_compra == 0 && $veri_obras == 0 && $veri_servicios == 0) {
            $sql1 = "DELETE  FROM  cpcd02 where " . $sql;
            $this->cpcd02->execute($sql1);
            $this->set('Message_existe', 'Dato Eliminado con exito.');
            $y = $this->cpcd02->findCount();
            if ($y != 0) {
                $this->consultar();
                $this->render("consultar");
            } else if ($y == 0) {
                $this->index();
                $this->render("index");
            }//fin if
        }
    }

//fin eliminar


    function modificar($rif = null, $pagina = null) {
        $this->layout = "ajax";

        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');

        $pagina = $pagina;
        $cond = "rif='" . $rif . "'";
           $this->set('categoria_suministro', $this->categoria_suministro);

         $datacpcp01 = $this->v_cpcd02->findAll($cond);
        foreach ($datacpcp01 as $dato) {
            $est = $dato['v_cpcd02']['cod_estado'];
            //$esta=$var['cod_estado'];
        }
        $this->Session->write('esta', $est);
        $vec = $this->cugd10_imagenes->findCount($this->SQLCA() . " and cod_campo=5 and identificacion='" . $rif . "'");
        if ($vec != 0) {
            $this->set('existe_imagen', true);
        } else {
            $this->set('existe_imagen', false);
        }
        $listaramo = $this->cpcd01->generateList(null, ' upper(trim(denominacion)) ASC', null, '{n}.cpcd01.codigo', '{n}.cpcd01.denominacion');
        $this->concatena_tres_digitos($listaramo, 'codigo');
        $condxxx = 'cod_republica=' . $this->Session->read('SScodpresi');
        $listaramo2 = $this->cugd01_estados->generateList($condxxx, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
        $this->concatena($listaramo2, 'cod_estado');
        $listamunicio = $this->cugd01_municipios->generateList('cod_estado=' . $est, 'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
        $this->concatena($listamunicio, 'cod_municipios');
        $this->set('datos', $datacpcp01);
        $this->set('pagina', $pagina);

		$cugd02i = $this->cugd02_institucion->findAll("cod_tipo_institucion=$cod_tipo_inst and cod_institucion=$cod_inst", array('denominacion'), null, 1, 1, null);
		$cugd02d = $this->cugd02_institucion->execute("SELECT denominacion FROM cugd02_dependencias WHERE cod_tipo_institucion=$cod_tipo_inst and cod_institucion=$cod_inst and cod_dependencia=$cod_dep;");

		$this->set('reg_inst', $cugd02i[0]["cugd02_institucion"]["denominacion"]);
		$this->set('reg_dep', $cugd02d[0][0]["denominacion"]);
    }

    function guardar_modificar($pagina = null) {
        $this->layout = "ajax";
        if (!empty($this->data)) {

            $rif = $this->data['cpcp02']['rif'];
            $denominacion = $this->data['cpcp02']['razon'];
            $numero_expediente = $this->data['cpcp02']['numero'];
            $numero_empleados = $this->data['cpcp02']['numero_empleados'];
            $objeto = $this->data['cpcp02']['objeto'];
            $ramo_comercial = $this->data['cpcp02']['codigo_ramo'];
            $direccion_comercial = $this->data['cpcp02']['direccion_comercial'];
            $actividades_principales = $this->data['cpcp02']['descripcion_objeto'];
            $cod_estado = $this->data['cpcp02']['codigo_estado'];
            $cod_municipio = $this->data['cpcp02']['codigo_municipios'];
            $inscripcion_sunacoop = $this->data['cpcp02']['numero_sunacoop'];
            $codigo_area_empresa = $this->data['cpcp02']['codigo_area_empresa'];
            $telefonos = $this->data['cpcp02']['telefonos_empresa'];
            $codigo_postal = $this->data['cpcp02']['zona_postal_empresa'];
            $correo_electronico_empresa = $this->data['cpcp02']['correo_empresa'];
            $equipos_disponibles = $this->data['cpcp02']['equipos_disponibles'];
            $capacidad_financiera = $this->Formato1($this->data['cpcp02']['capacidad_financiera']);
            $registro_mercantil = $this->data['cpcp02']['registro_mercantil'];
            $socios = $this->data['cpcp02']['socios'];
            $representante_legal = $this->data['cpcp02']['nombre_representante'];
            $direccion_representante = $this->data['cpcp02']['direccion_representante'];
            $cedula_identidad = $this->data['cpcp02']['cedula_ide'];
            $codigo_area_representante = $this->data['cpcp02']['codigo_area_representante'];
            $telefonos_fijos_representante = $this->data['cpcp02']['telefonos_fijos'];
            $telefonos_moviles_representante = $this->data['cpcp02']['telefonos_moviles'];
            $correo_electronico_representante = $this->data['cpcp02']['correo_representante'];
            $numero_ocei = $this->data['cpcp02']['numero_ocei'];



            $f1 = $this->data['cpcp02']['fecha_ocei'];
            $f1 = $f1 == "" ? "01/01/1900" : $f1;
            $fecha_vencimiento_ocei = $f1[6] . $f1[7] . $f1[8] . $f1[9] . "-" . $f1[3] . $f1[4] . "-" . $f1[0] . $f1[1];

            $numero_solvencia_laboral = $this->data['cpcp02']['numero_laboral'];

            $f2 = $this->data['cpcp02']['fecha_laboral'];
            $f2 = $f2 == "" ? "01/01/1900" : $f2;
            $fecha_vencimiento_laboral = $f2[6] . $f2[7] . $f2[8] . $f2[9] . "-" . $f2[3] . $f2[4] . "-" . $f2[0] . $f2[1];

            $numero_solvencia_seguro = $this->data['cpcp02']['numero_seguro'];

            $f3 = $this->data['cpcp02']['fecha_seguro'];
            $f3 = $f3 == "" ? "01/01/1900" : $f3;
            $fecha_vencimiento_seguro = $f3[6] . $f3[7] . $f3[8] . $f3[9] . "-" . $f3[3] . $f3[4] . "-" . $f3[0] . $f3[1];

            $numero_solvencia_ince = $this->data['cpcp02']['numero_ince'];

            $f4 = $this->data['cpcp02']['fecha_ince'];
            $f4 = $f4 == "" ? "01/01/1900" : $f4;
            $fecha_vencimiento_ince = $f4[6] . $f4[7] . $f4[8] . $f4[9] . "-" . $f4[3] . $f4[4] . "-" . $f4[0] . $f4[1];

            $numero_solvencia_municipal = $this->data['cpcp02']['numero_municipal'];

            $f5 = $this->data['cpcp02']['fecha_municipal'];
            $f5 = $f5 == "" ? "01/01/1900" : $f5;
            $fecha_vencimiento_municipal = $f5[6] . $f5[7] . $f5[8] . $f5[9] . "-" . $f5[3] . $f5[4] . "-" . $f5[0] . $f5[1];

            $observacion = $this->data['cpcp02']['observacion'];
            $f6 = $this->data['cpcp02']['fecha_inscripcion'];
            $f6 = $f6 == "" ? "01/01/1900" : $f6;
            $fecha_inscripcion = $f6[6] . $f6[7] . $f6[8] . $f6[9] . "-" . $f6[3] . $f6[4] . "-" . $f6[0] . $f6[1];

            $f7 = $this->data['cpcp02']['fecha_actualizacion'];
            $f7 = $f7 == "" ? "01/01/1900" : $f7;
            $fecha_actualizacion = $f7[6] . $f7[7] . $f7[8] . $f7[9] . "-" . $f7[3] . $f7[4] . "-" . $f7[0] . $f7[1];


            $exento = $this->data['cpcp02']['exento'];
            if ($exento == "")
                $exento = 2;
             $fecha_inscrip_inicial_snc=date("Y-m-d",strtotime($this->data['cpcp02']['fecha_inscrip_inicial_snc']));

             $categoria_suministro = $this->data['cpcp02']['categoria_suministro'];
             $suministro_cliente_similar = $this->data['cpcp02']['suministro_cliente_similar'];

             $registrado_institucion = $this->data['cpcp02']['registrado_institucion'];
             $registrado_dependencia = $this->data['cpcp02']['registrado_dependencia'];
             $registrado_username = $this->data['cpcp02']['registrado_username'];
             $registrado_fecha = $this->Cfecha($this->data['cpcp02']['registrado_fecha'], 'A-M-D');
             $condicion_actividad = $this->data['cpcp02']['condicion_actividad'];

            $sql = "update cpcd02 set objeto=$objeto, denominacion='" . $denominacion . "', ramo_comercial=$ramo_comercial, direccion_comercial='" . $direccion_comercial . "', codigo_area_empresa=$codigo_area_empresa, telefonos='" . $telefonos . "', codigo_postal='" . $codigo_postal . "', correo_electronico_empresa='" . $correo_electronico_empresa . "', equipos_disponibles='" . $equipos_disponibles . "', actividades_principales='" . $actividades_principales . "', capacidad_financiera=$capacidad_financiera, registro_mercantil='" . $registro_mercantil . "', socios='" . $socios . "', representante_legal='" . $representante_legal . "', direccion_representante='" . $direccion_representante . "', codigo_area_representante='" . $codigo_area_representante . "', telefonos_fijos_representante='" . $telefonos_fijos_representante . "', telefonos_moviles_representante='" . $telefonos_moviles_representante . "', correo_electronico_representante='" . $correo_electronico_representante . "', numero_ocei='" . $numero_ocei . "', fecha_vencimiento_ocei='" . $fecha_vencimiento_ocei . "', numero_solvencia_laboral='" . $numero_solvencia_laboral . "', fecha_vencimiento_laboral='" . $fecha_vencimiento_laboral . "', numero_solvencia_seguro='" . $numero_solvencia_seguro . "', fecha_vencimiento_seguro='" . $fecha_vencimiento_seguro . "', numero_solvencia_ince='" . $numero_solvencia_ince . "', fecha_vencimiento_ince='" . $fecha_vencimiento_ince . "', numero_solvencia_municipal='" . $numero_solvencia_municipal . "', fecha_vencimiento_municipal='" . $fecha_vencimiento_municipal . "', observacion='" . $observacion . "', numero_empleados=$numero_empleados, inscripcion_sunacoop='" . $inscripcion_sunacoop . "', cod_estado=$cod_estado, cod_municipio=$cod_municipio, numero_expediente=$numero_expediente, fecha_inscripcion='" . $fecha_inscripcion . "', fecha_actualizacion='" . $fecha_actualizacion . "', cedula_identidad=$cedula_identidad, exento_islr_cooperativa=" . $exento . ", fecha_inscrip_inicial_snc='".$fecha_inscrip_inicial_snc."', categoria_suministro='".$categoria_suministro."', suministro_cliente_similar='".$suministro_cliente_similar. "', registrado_institucion='".$registrado_institucion. "', registrado_dependencia='".$registrado_dependencia. "', registrado_username='".$registrado_username. "', registrado_fecha='".$registrado_fecha. "', condicion_actividad=$condicion_actividad where rif='$rif'";
            $vvv = $this->cpcd02->execute($sql);
            $this->data = null;
            $this->set('Message_existe', 'Registro Modificado con exito.');
            $this->consultar($pagina);
            $this->render("consultar");
        }
    }

//fin guardar_modificar

    function radio($var2) {
        $this->layout = "ajax";
        //	$this->set('action', $var);
        //	$this->set('var', $var);
        //  echo
        $this->set('mensaje', 'POR FAVOR INGRESE EL CODIGO');
        $this->set('datos', array());
        $this->set('tipo', array());

        if ($var2 == 1) {
            $ss = $this->cpcd02->findAll(null, array('numero_expediente'), 'numero_expediente DESC', 1, 1, null);
            if ($ss == null) {
                $new_numero = 1;
            } else {
                $new_numero = $ss[0]["cpcd02"]["numero_expediente"] + 1;
            }
            $this->set('numero', $new_numero); //$numero
        }
        if ($var2 == 2) {
            $this->set('numero', "");
        }
    }

    function query($var = null) {
        $this->layout = "ajax";
        $this->set('tipo', $var);
    }

    function datos($tipo = null, $pista = null) {
        $this->layout = "ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst . " and cod_dep = " . $cod_dep;

        $pista2 = strtoupper($pista);
        if ($tipo == 'numero_expediente') {
            $busq = "numero_expediente=" . $pista2;
        } else if ($tipo == 'denominacion' or $tipo == 'rif' or $tipo == 'representante_legal') {
            $busq = "upper($tipo) LIKE '%$pista2%'";
        }


        if ($tipo != null && $pista != null) {
            $exe = "select * from cpcd02 where " . $busq;
            $datos = $this->cpcd02->execute($exe);
            $this->set('datos', $datos);
        }
    }

    function preconsulta() {
        $this->layout = "ajax";
        $opciones = array('rif' => 'R.I.F', 'denominacion' => 'Razon Social', 'representante_legal' => 'Representante Legal', 'numero_expediente' => 'Numero de Expediente');
        $this->set('opcion', $opciones);
    }

    function buscar_rif() {
        $this->layout = "ajax";
    }

    function lista_encontrados($rif) {
        $this->layout = "ajax";
        $cond = "rif='" . $rif . "'"; //echo $cond;
        $num = $this->v_cpcd02->findCount($cond);
        if ($num == 1) {
            $datacpcp01 = $this->v_cpcd02->findAll($cond);
            $vec = $this->cugd10_imagenes->findCount($this->SQLCA() . " and cod_campo=5 and identificacion='" . $rif . "'");
            if ($vec != 0) {
                $this->set('existe_imagen', true);
            } else {
                $this->set('existe_imagen', false);
            }
            $this->set('datos', $datacpcp01);
             $this->set('categoria_suministro', $this->categoria_suministro);

        } else {//echo "no hay dato";
            $this->set('errorMessage', 'No se encontrar&oacute;n datos');
            $this->preconsulta();
            $this->render("preconsulta");
        }//fin function consultar2


    }

    function exento($tipo) {
        $this->layout = "ajax";
        if ($tipo == 3) {
            $marca = 1;
        } else
        if ($tipo != 3) {
            $marca = 2;
        }
        $this->set('marca', $marca);
    }

    function actualizar($objeto) {
        $this->layout = "ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $ob = $this->cpcd02->findAll('objeto=' . $objeto, null, 'rif ASC');
        $cont = 0;
        foreach ($ob as $pv) {
            $cont = $cont + 1;
            $rif = $pv['cpcd02']['rif'];
            $denominacion = $pv['cpcd02']['denominacion'];


/////cuenta 300
            $presu = $this->ccfd01_subdivision->findAll($this->SQLCA() . ' and cod_tipo_cuenta=1 and cod_cuenta=300 and cod_subcuenta=001 and cod_division=' . $objeto . ' and ' . $cont . '=' . $cont, array('cod_subdivision'), 'cod_subdivision DESC', 1, 1, null);
            if ($presu == null) {
                $gasto_presu_subdivision = 1;
            } else {
                $gasto_presu_subdivision = $presu[0]["ccfd01_subdivision"]["cod_subdivision"] + 1;
            }
            $SQL_INSERT300 = "INSERT INTO ccfd01_subdivision (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta, cod_cuenta,
  						cod_subcuenta, cod_division, cod_subdivision, denominacion)";
            $SQL_INSERT300 .=" VALUES ($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,1,300,001,$objeto,$gasto_presu_subdivision,'" . $denominacion . "')";
            $x300 = $this->ccfd01_subdivision->execute($SQL_INSERT300);


/////cuenta 103
            $pagar = $this->ccfd01_subdivision->findAll($this->SQLCA() . ' and cod_tipo_cuenta=2 and cod_cuenta=103 and cod_subcuenta=001 and cod_division=' . $objeto . ' and ' . $cont . '=' . $cont, array('cod_subdivision'), 'cod_subdivision DESC', 1, 1, null);
            if ($pagar == null) {
                $gasto_pagar_subdivision = 1;
            } else {
                $gasto_pagar_subdivision = $pagar[0]["ccfd01_subdivision"]["cod_subdivision"] + 1;
            }
            $SQL_INSERT103 = "INSERT INTO ccfd01_subdivision (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta, cod_cuenta,
  						cod_subcuenta, cod_division, cod_subdivision, denominacion)";
            $SQL_INSERT103 .=" VALUES ($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,2,103,001,$objeto,$gasto_pagar_subdivision,'" . $denominacion . "')";
            $x103 = $this->ccfd01_subdivision->execute($SQL_INSERT103);


/////cuenta 101
            $pago = $this->ccfd01_subdivision->findAll($this->SQLCA() . ' and cod_tipo_cuenta=2 and cod_cuenta=101 and cod_subcuenta=001 and cod_division=' . $objeto . ' and ' . $cont . '=' . $cont, array('cod_subdivision'), 'cod_subdivision DESC', 1, 1, null);
            if ($pago == null) {
                $orden_pago_subdivision = 1;
            } else {
                $orden_pago_subdivision = $pago[0]["ccfd01_subdivision"]["cod_subdivision"] + 1;
            }
            $SQL_INSERT101 = "INSERT INTO ccfd01_subdivision (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta, cod_cuenta,
  						cod_subcuenta, cod_division, cod_subdivision, denominacion)";
            $SQL_INSERT101 .=" VALUES ($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,2,101,001,$objeto,$orden_pago_subdivision,'" . $denominacion . "')";
            $x101 = $this->ccfd01_subdivision->execute($SQL_INSERT101);



/////cuenta 128
            $prov = $this->ccfd01_subdivision->findAll($this->SQLCA() . ' and cod_tipo_cuenta=1 and cod_cuenta=128 and cod_subcuenta=001 and cod_division=' . $objeto . ' and ' . $cont . '=' . $cont, array('cod_subdivision'), 'cod_subdivision DESC', 1, 1, null);
            if ($prov == null) {
                $anticipo_prov_subdivision = 1;
            } else {
                $anticipo_prov_subdivision = $prov[0]["ccfd01_subdivision"]["cod_subdivision"] + 1;
            }
            $SQL_INSERT128 = "INSERT INTO ccfd01_subdivision (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta, cod_cuenta,
  						cod_subcuenta, cod_division, cod_subdivision, denominacion)";
            $SQL_INSERT128 .=" VALUES ($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,1,128,001,$objeto,$anticipo_prov_subdivision,'" . $denominacion . "')";
            $x128 = $this->ccfd01_subdivision->execute($SQL_INSERT128);

            $sql = "update cpcd02 set gasto_presu_tipo=1, gasto_presu_cuenta=300, gasto_presu_subcuenta=001, gasto_presu_division=" . $objeto . ", gasto_presu_subdivision=" . $gasto_presu_subdivision . ", gasto_pagar_tipo=2,
  			 gasto_pagar_cuenta=103, gasto_pagar_subcuenta=001, gasto_pagar_division=" . $objeto . ", gasto_pagar_subdivision=" . $gasto_pagar_subdivision . ", orden_pago_tipo=2, orden_pago_cuenta=101,
  			 orden_pago_subcuenta=001, orden_pago_division=" . $objeto . ", orden_pago_subdivision=" . $orden_pago_subdivision . ", anticipo_prov_tipo=1, anticipo_prov_cuenta=128, anticipo_prov_subcuenta=001,
  			 anticipo_prov_division=" . $objeto . ", anticipo_prov_subdivision=" . $anticipo_prov_subdivision . " where rif='$rif'";
            $vvv = $this->cpcd02->execute($sql);
        }
        echo $cont;
        $this->index();
        $this->render("index");
    }

    function infomacion_faltante($var1 = null, $var2 = null) {

        $this->layout = "ajax";

        $var3 = "";

        switch ($var1) {
            case "ramo": {
                    $this->set('userTable', $this->requestAction('/cpcp01_b/', array('return')));
                }break;
            case "repuesto": {
                    $this->set('userTable', $this->requestAction('/cimp05_conservacion_tipo_repuestos/', array('return')));
                }break;
        }//fin

        $this->set('opcion', $var1);
        $this->set('capa', $var2);
        $this->set('controlador', $var3);
    }

//fin function

    function select_cambio($var1 = null, $var2 = null, $var3 = null) {

        $this->layout = "ajax";

        switch ($var1) {
            case "ramo": {
                    $listaramo = $this->cpcd01->generateList(null, 'codigo ASC', null, '{n}.cpcd01.codigo', '{n}.cpcd01.denominacion');
                    $this->concatena($listaramo, 'lista');
                    $this->set("name", "codigo");
                }break;

            case "repuesto": {
                    $repuestos = $this->cimd05_conservacion_tipo_repuestos->generateList(null, 'cod_repuesto ASC', null, '{n}.cimd05_conservacion_tipo_repuestos.cod_repuesto', '{n}.cimd05_conservacion_tipo_repuestos.denominacion');
                    $this->concatena($repuestos, 'lista');
                    $this->set("name", "sel_repuesto");
                }break;
                $this->set('opcion', $var1);
        }
    }

//fin function

    function imagenes($var = null) {
        $this->layout = "ajax";
        $this->Session->delete('rif');
        //secho $var;
        $rif = $this->formatear_rif($var);
        $this->Session->write('rif', strtoupper($rif));
    }

    function formatear_rif($rif) {
        $rif = str_replace('-', '', $rif);
        $c = strlen($rif);
        $rif2 = "";
        for ($i = 0; $i < $c; $i++) {
            if ($i == 1) {
                $rif2 .='-';
            }
            if ($i == ($c - 1)) {
                $rif2 .='-';
            }
            $rif2 .=$rif[$i];
        }//fin for
        return $rif2;
    }

//fin funcion rif

    function buscar_ramo($var1 = null) {
        $this->layout = "ajax";
        $this->set("opcion", $var1);
        $this->Session->delete('pista');
    }

//fin function

    function buscar_por_pistax($var1 = null, $var2 = null, $var3 = null) {
        $this->layout = "ajax";

        if ($var3 == null) {
            $var2 = strtoupper($var2);
            $this->Session->write('pista', $var2);
            //if(is_int($var2)){$sql   = " ((rif_cedula LIKE '%$var2%') or (razon_social LIKE '%$var2%'))  or   ";}else{ $sql = "";}
            $Tfilas = $this->cpcd01->findCount("(codigo::text LIKE '%$var2%') or (quitar_acentos(denominacion) LIKE quitar_acentos('%$var2%'))");
            if ($Tfilas != 0) {
                $pagina = 1;
                $Tfilas = (int) ceil($Tfilas / 50);
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $this->set('total_paginas', $Tfilas);
                $this->set('pagina_actual', $pagina);
                $this->set('ultimo', $Tfilas);
                $datos_filas = $this->cpcd01->findAll("(codigo::text LIKE '%$var2%') or (quitar_acentos(denominacion) LIKE quitar_acentos('%$var2%'))", null, "denominacion ASC", 50, 1, null);
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
            //if(is_int($var22)){$sql   = " (codigo_prod_serv LIKE '%$var22%')  or   ";}else{ $sql = "";}
            $Tfilas = $this->cpcd01->findCount("(codigo::text LIKE '%$var2%') or (quitar_acentos(denominacion) LIKE quitar_acentos('%$var2%'))");
            if ($Tfilas != 0) {
                $pagina = $var3;
                $Tfilas = (int) ceil($Tfilas / 50);
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $this->set('total_paginas', $Tfilas);
                $this->set('pagina_actual', $pagina);
                $this->set('ultimo', $Tfilas);
                $datos_filas = $this->cpcd01->findAll("(codigo::text LIKE '%$var2%') or (quitar_acentos(denominacion) LIKE quitar_acentos('%$var2%'))", null, "denominacion ASC", 50, $pagina, null);
                $this->set("datosFILAS", $datos_filas);
                $this->set('siguiente', $pagina + 1);
                $this->set('anterior', $pagina - 1);
                $this->bt_nav($Tfilas, $pagina);
            } else {
                $this->set("datosFILAS", '');
            }
        }//fin else
        $this->set("opcion", $var1);
    }

//fin function

    function funcion($var = null) {
        $this->layout = "ajax";
    }

//fin index

    function selecion_ramo($var = null) {

        $this->layout = "ajax";

        $aux = $this->cpcd01->findAll('codigo=' . $var);
//pr($aux);
        $var1 = "";

        foreach ($aux as $ve) {
            $var1 = $ve["cpcd01"]["denominacion"];
        }//fin foreach
        echo'<script>';
        echo" document.getElementById('select_ramo').value        = '" . mascara($var, 3) . "'; ";
        echo" document.getElementById('denominacion_ramo').value        = '" . $var1 . "'; ";
        echo'</script>';


        $this->render("funcion");
    }

//fin fucnt

    function verificar($var2 = null) {
        $this->layout = "ajax";
        $rif = $this->formatear_rif($var2);
        $rif = strtoupper($rif);
        $a = $this->cpcd02->findCount("rif='" . $rif . "'");
        if ($a == 1) {
            //	$this->set('Message_existe', 'este r.i.f. ya se encuentra registrado');
            echo "<script type='text/javascript'>ver_documento('/cpcp02/lista_encontrados/$rif','principal');</script>";
        }
    }

    function buscar_z($var1 = null) {
        $this->layout = "ajax";
        $this->set("opcion", $var1);
        $this->Session->delete('pista');
    }

//fin function

    function buscar_por_pistaz($var1 = null, $var2 = null, $var3 = null) {
        $this->layout = "ajax";

        if ($var3 == null) {
            $var2 = strtoupper($var2);
            $this->Session->write('pista', $var2);
            //if(is_int($var2)){$sql   = " ((rif_cedula LIKE '%$var2%') or (razon_social LIKE '%$var2%'))  or   ";}else{ $sql = "";}
            $Tfilas = $this->cpcd02->findCount("(rif LIKE '%$var2%') or (quitar_acentos(denominacion) LIKE quitar_acentos('%$var2%'))  or (quitar_acentos(representante_legal) LIKE quitar_acentos('%$var2%'))");
            if ($Tfilas != 0) {
                $pagina = 1;
                $Tfilas = (int) ceil($Tfilas / 50);
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $this->set('total_paginas', $Tfilas);
                $this->set('pagina_actual', $pagina);
                $this->set('ultimo', $Tfilas);
                $datos_filas = $this->cpcd02->findAll("(rif LIKE '%$var2%') or (quitar_acentos(denominacion) LIKE quitar_acentos('%$var2%'))  or (quitar_acentos(representante_legal) LIKE quitar_acentos('%$var2%'))", null, "denominacion ASC", 50, 1, null);
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
            //if(is_int($var22)){$sql   = " (codigo_prod_serv LIKE '%$var22%')  or   ";}else{ $sql = "";}
            $Tfilas = $this->cpcd02->findCount("(rif LIKE '%$var2%') or (quitar_acentos(denominacion) LIKE quitar_acentos('%$var2%'))  or (quitar_acentos(representante_legal) LIKE quitar_acentos('%$var2%'))");
            if ($Tfilas != 0) {
                $pagina = $var3;
                $Tfilas = (int) ceil($Tfilas / 50);
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $this->set('total_paginas', $Tfilas);
                $this->set('pagina_actual', $pagina);
                $this->set('ultimo', $Tfilas);
                $datos_filas = $this->cpcd02->findAll("(rif LIKE '%$var2%') or (quitar_acentos(denominacion) LIKE quitar_acentos('%$var2%'))  or (quitar_acentos(representante_legal) LIKE quitar_acentos('%$var2%'))", null, "denominacion ASC", 50, $pagina, null);
                $this->set("datosFILAS", $datos_filas);
                $this->set('siguiente', $pagina + 1);
                $this->set('anterior', $pagina - 1);
                $this->bt_nav($Tfilas, $pagina);
            } else {
                $this->set("datosFILAS", '');
            }
        }//fin else
        $this->set("opcion", $var1);
    }

//fin function

    function cuentas() {
        $this->layout = 'ajax';
        set_time_limit(0);
        //$cont=0;
        $cod_presi = 1;
        $cod_entidad = 12;
        $cod_tipo_inst = 50;
        $cod_inst = 135;
        $prov = $this->cpcd02->findAll('objeto=6', null, 'objeto,rif ASC');
        //pr($prov);
        foreach ($prov as $p) {
            //$cont++;
            $rif = $p['cpcd02']['rif'];
            $objeto = $p['cpcd02']['objeto'];
            $denominacion = $p['cpcd02']['denominacion'];
            $gasto_presu_tipo = $p['cpcd02']['gasto_presu_tipo'];
            $gasto_presu_cuenta = $p['cpcd02']['gasto_presu_cuenta'];
            $gasto_presu_subcuenta = $p['cpcd02']['gasto_presu_subcuenta'];
            $gasto_presu_division = $p['cpcd02']['gasto_presu_division'];
            $gasto_presu_subdivision = $p['cpcd02']['gasto_presu_subdivision'];
            $gasto_pagar_tipo = $p['cpcd02']['gasto_pagar_tipo'];
            $gasto_pagar_cuenta = $p['cpcd02']['gasto_pagar_cuenta'];
            $gasto_pagar_subcuenta = $p['cpcd02']['gasto_pagar_subcuenta'];
            $gasto_pagar_division = $p['cpcd02']['gasto_pagar_division'];
            $gasto_pagar_subdivision = $p['cpcd02']['gasto_pagar_subdivision'];
            $orden_pago_tipo = $p['cpcd02']['orden_pago_tipo'];
            $orden_pago_cuenta = $p['cpcd02']['orden_pago_cuenta'];
            $orden_pago_subcuenta = $p['cpcd02']['orden_pago_subcuenta'];
            $orden_pago_division = $p['cpcd02']['orden_pago_division'];
            $orden_pago_subdivision = $p['cpcd02']['orden_pago_subdivision'];
            $anticipo_prov_tipo = $p['cpcd02']['anticipo_prov_tipo'];
            $anticipo_prov_cuenta = $p['cpcd02']['anticipo_prov_cuenta'];
            $anticipo_prov_subcuenta = $p['cpcd02']['anticipo_prov_subcuenta'];
            $anticipo_prov_division = $p['cpcd02']['anticipo_prov_division'];
            $anticipo_prov_subdivision = $p['cpcd02']['anticipo_prov_subdivision'];
            //echo $anticipo_prov_subdivision.'<br>';
            /*

              /////////tipo 1
              $cti1 = $this->ccfd01_tipo->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=1');
              if($cti1==0){
              $SQL_INSERT1 ="INSERT INTO ccfd01_tipo (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,denominacion)";
              $SQL_INSERT1 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 1,'ACTIVO')";
              $this->ccfd01_tipo->execute($SQL_INSERT1);
              }



              /////////tipo 2
              $cti2 = $this->ccfd01_tipo->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=2');
              if($cti2==0){
              $SQL_INSERT2 ="INSERT INTO ccfd01_tipo (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,denominacion)";
              $SQL_INSERT2 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 2,'PASIVO')";
              $this->ccfd01_tipo->execute($SQL_INSERT2);
              }




              /////////cuenta 101
              $cc101 = $this->ccfd01_cuenta->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=2 and cod_cuenta=101');
              if($cc101==0){
              $SQL_INSERT101 ="INSERT INTO ccfd01_cuenta (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,cod_cuenta, denominacion)";
              $SQL_INSERT101 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 2, 101,'ORDENES DE PAGO')";
              $this->ccfd01_cuenta->execute($SQL_INSERT101);
              }




              //////////cuenta103
              $cc103 = $this->ccfd01_cuenta->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=2 and cod_cuenta=103');
              if($cc103==0){
              $SQL_INSERT103 ="INSERT INTO ccfd01_cuenta (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,cod_cuenta, denominacion)";
              $SQL_INSERT103 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 2, 103,'GASTOS POR PAGAR')";
              $this->ccfd01_cuenta->execute($SQL_INSERT103);
              }


              /////////cuenta 128
              $cc128 = $this->ccfd01_cuenta->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=1 and cod_cuenta=128');
              if($cc128==0){
              $SQL_INSERT128 ="INSERT INTO ccfd01_cuenta (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,cod_cuenta, denominacion)";
              $SQL_INSERT128 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 1, 128,'ANTICIPOS A PROVEEDORES Y CONTRATISTAS')";
              $this->ccfd01_cuenta->execute($SQL_INSERT128);
              }



              //////////cuenta 300
              $cc300 = $this->ccfd01_cuenta->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=1 and cod_cuenta=300');
              if($cc300==0){
              $SQL_INSERT300 ="INSERT INTO ccfd01_cuenta (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,cod_cuenta, denominacion)";
              $SQL_INSERT300 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 1, 300,'GASTOS PRESUPUESTARIOS')";
              $this->ccfd01_cuenta->execute($SQL_INSERT300);
              }


              ////////subcuenta para la cuenta 101
              $csc1 = $this->ccfd01_subcuenta->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=2 and cod_cuenta=101 and cod_subcuenta=1');
              if($csc1==0){
              $SQL_INSERT3 ="INSERT INTO ccfd01_subcuenta (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,cod_cuenta,cod_subcuenta, denominacion)";
              $SQL_INSERT3 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 2, 101,1,'PROVEEDORES, CONTRATISTAS, COOPERATIVAS Y PERSONAS NATURALES')";
              $this->ccfd01_subcuenta->execute($SQL_INSERT3);
              }


              ////////subcuenta para la cuenta 103
              $csc2 = $this->ccfd01_subcuenta->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=2 and cod_cuenta=103 and cod_subcuenta=1');
              if($csc2==0){
              $SQL_INSERT4 ="INSERT INTO ccfd01_subcuenta (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,cod_cuenta,cod_subcuenta, denominacion)";
              $SQL_INSERT4 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 2, 103,1,'PROVEEDORES, CONTRATISTAS, COOPERATIVAS Y PERSONAS NATURALES')";
              $this->ccfd01_subcuenta->execute($SQL_INSERT4);
              }



              ////////subcuenta para la cuenta 128
              $csc3 = $this->ccfd01_subcuenta->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=1 and cod_cuenta=128 and cod_subcuenta=1');
              if($csc3==0){
              $SQL_INSERT5 ="INSERT INTO ccfd01_subcuenta (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,cod_cuenta,cod_subcuenta, denominacion)";
              $SQL_INSERT5 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 1, 128,1,'PROVEEDORES, CONTRATISTAS, COOPERATIVAS Y PERSONAS NATURALES')";
              $this->ccfd01_subcuenta->execute($SQL_INSERT5);
              }



              ////////subcuenta para la cuenta 300
              $csc4 = $this->ccfd01_subcuenta->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=1 and cod_cuenta=300 and cod_subcuenta=1');
              if($csc4==0){
              $SQL_INSERT6 ="INSERT INTO ccfd01_subcuenta (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,cod_cuenta,cod_subcuenta, denominacion)";
              $SQL_INSERT6 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 1, 300,1,'PROVEEDORES, CONTRATISTAS, COOPERATIVAS Y PERSONAS NATURALES')";
              $this->ccfd01_subcuenta->execute($SQL_INSERT6);
              }



              ////////division para los proveedores
              $cdv1 = $this->ccfd01_division->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=2 and cod_cuenta=101 and cod_subcuenta=1 and cod_division=1');
              if($cdv1==0){
              $SQL_INSERT7 ="INSERT INTO ccfd01_division (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,cod_cuenta,cod_subcuenta, cod_division, denominacion)";
              $SQL_INSERT7 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 2, 101,1,1,'PROVEEDORES')";
              $this->ccfd01_division->execute($SQL_INSERT7);
              }

              $cdv2 = $this->ccfd01_division->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=2 and cod_cuenta=103 and cod_subcuenta=1 and cod_division=1');
              if($cdv2==0){
              $SQL_INSERT8 ="INSERT INTO ccfd01_division (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,cod_cuenta,cod_subcuenta, cod_division, denominacion)";
              $SQL_INSERT8 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 2, 103,1,1,'PROVEEDORES')";
              $this->ccfd01_division->execute($SQL_INSERT8);
              }

              $cdv3 = $this->ccfd01_division->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=1 and cod_cuenta=128 and cod_subcuenta=1 and cod_division=1');
              if($cdv3==0){
              $SQL_INSERT9 ="INSERT INTO ccfd01_division (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,cod_cuenta,cod_subcuenta, cod_division, denominacion)";
              $SQL_INSERT9 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 1, 128,1,1,'PROVEEDORES')";
              $this->ccfd01_division->execute($SQL_INSERT9);
              }

              $cdv4 = $this->ccfd01_division->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=1 and cod_cuenta=300 and cod_subcuenta=1 and cod_division=1');
              if($cdv4==0){
              $SQL_INSERT10 ="INSERT INTO ccfd01_division (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,cod_cuenta,cod_subcuenta, cod_division, denominacion)";
              $SQL_INSERT10 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 1, 300,1,1,'PROVEEDORES')";
              $this->ccfd01_division->execute($SQL_INSERT10);
              }



              ////////division para los contratistas
              $cdv5 = $this->ccfd01_division->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=2 and cod_cuenta=101 and cod_subcuenta=1 and cod_division=2');
              if($cdv5==0){
              $SQL_INSERT11 ="INSERT INTO ccfd01_division (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,cod_cuenta,cod_subcuenta, cod_division, denominacion)";
              $SQL_INSERT11 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 2, 101,1,2,'CONTRATISTAS')";
              $this->ccfd01_division->execute($SQL_INSERT11);
              }

              $cdv6 = $this->ccfd01_division->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=2 and cod_cuenta=103 and cod_subcuenta=1 and cod_division=2');
              if($cdv6==0){
              $SQL_INSERT12 ="INSERT INTO ccfd01_division (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,cod_cuenta,cod_subcuenta, cod_division, denominacion)";
              $SQL_INSERT12 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 2, 103,1,2,'CONTRATISTAS')";
              $this->ccfd01_division->execute($SQL_INSERT12);
              }

              $cdv7 = $this->ccfd01_division->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=1 and cod_cuenta=128 and cod_subcuenta=1 and cod_division=2');
              if($cdv7==0){
              $SQL_INSERT13 ="INSERT INTO ccfd01_division (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,cod_cuenta,cod_subcuenta, cod_division, denominacion)";
              $SQL_INSERT13 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 1, 128,1,2,'CONTRATISTAS')";
              $this->ccfd01_division->execute($SQL_INSERT13);
              }

              $cdv8 = $this->ccfd01_division->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=1 and cod_cuenta=300 and cod_subcuenta=1 and cod_division=2');
              if($cdv8==0){
              $SQL_INSERT14 ="INSERT INTO ccfd01_division (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,cod_cuenta,cod_subcuenta, cod_division, denominacion)";
              $SQL_INSERT14 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 1, 300,1,2,'CONTRATISTAS')";
              $this->ccfd01_division->execute($SQL_INSERT14);
              }


              ////////division para los COOPERATIVAS
              $cdv9 = $this->ccfd01_division->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=2 and cod_cuenta=101 and cod_subcuenta=1 and cod_division=3');
              if($cdv9==0){
              $SQL_INSERT15 ="INSERT INTO ccfd01_division (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,cod_cuenta,cod_subcuenta, cod_division, denominacion)";
              $SQL_INSERT15 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 2, 101,1,3,'COOPERATIVAS')";
              $this->ccfd01_division->execute($SQL_INSERT15);
              }

              $cdv10 = $this->ccfd01_division->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=2 and cod_cuenta=103 and cod_subcuenta=1 and cod_division=3');
              if($cdv10==0){
              $SQL_INSERT16 ="INSERT INTO ccfd01_division (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,cod_cuenta,cod_subcuenta, cod_division, denominacion)";
              $SQL_INSERT16 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 2, 103,1,3,'COOPERATIVAS')";
              $this->ccfd01_division->execute($SQL_INSERT16);
              }

              $cdv11 = $this->ccfd01_division->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=1 and cod_cuenta=128 and cod_subcuenta=1 and cod_division=3');
              if($cdv11==0){
              $SQL_INSERT17 ="INSERT INTO ccfd01_division (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,cod_cuenta,cod_subcuenta, cod_division, denominacion)";
              $SQL_INSERT17 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 1, 128,1,3,'COOPERATIVAS')";
              $this->ccfd01_division->execute($SQL_INSERT17);
              }

              $cdv12 = $this->ccfd01_division->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=1 and cod_cuenta=300 and cod_subcuenta=1 and cod_division=3');
              if($cdv12==0){
              $SQL_INSERT18 ="INSERT INTO ccfd01_division (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,cod_cuenta,cod_subcuenta, cod_division, denominacion)";
              $SQL_INSERT18 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 1, 300,1,3,'COOPERATIVAS')";
              $this->ccfd01_division->execute($SQL_INSERT18);
              }


              ////////division para los PERSONAS NATURALES
              $cdv12 = $this->ccfd01_division->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=2 and cod_cuenta=101 and cod_subcuenta=1 and cod_division=4');
              if($cdv12==0){
              $SQL_INSERT19 ="INSERT INTO ccfd01_division (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,cod_cuenta,cod_subcuenta, cod_division, denominacion)";
              $SQL_INSERT19 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 2, 101,1,4,'PERSONAS NATURALES')";
              $this->ccfd01_division->execute($SQL_INSERT19);
              }

              $cdv14 = $this->ccfd01_division->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=2 and cod_cuenta=103 and cod_subcuenta=1 and cod_division=4');
              if($cdv14==0){
              $SQL_INSERT20 ="INSERT INTO ccfd01_division (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,cod_cuenta,cod_subcuenta, cod_division, denominacion)";
              $SQL_INSERT20 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 2, 103,1,4,'PERSONAS NATURALES')";
              $this->ccfd01_division->execute($SQL_INSERT20);
              }

              $cdv15 = $this->ccfd01_division->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=1 and cod_cuenta=128 and cod_subcuenta=1 and cod_division=4');
              if($cdv15==0){
              $SQL_INSERT21 ="INSERT INTO ccfd01_division (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,cod_cuenta,cod_subcuenta, cod_division, denominacion)";
              $SQL_INSERT21 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 1, 128,1,4,'PERSONAS NATURALES')";
              $this->ccfd01_division->execute($SQL_INSERT21);
              }

              $cdv16 = $this->ccfd01_division->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=1 and cod_cuenta=300 and cod_subcuenta=1 and cod_division=4');
              if($cdv16==0){
              $SQL_INSERT22 ="INSERT INTO ccfd01_division (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,cod_cuenta,cod_subcuenta, cod_division, denominacion)";
              $SQL_INSERT22 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 1, 300,1,4,'PERSONAS NATURALES')";
              $this->ccfd01_division->execute($SQL_INSERT22);
              }



              ////////division para los CONCEJOS COMUNALES
              $cdv17 = $this->ccfd01_division->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=2 and cod_cuenta=101 and cod_subcuenta=1 and cod_division=5');
              if($cdv17==0){
              $SQL_INSERT23 ="INSERT INTO ccfd01_division (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,cod_cuenta,cod_subcuenta, cod_division, denominacion)";
              $SQL_INSERT23 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 2, 101,1,5,'CONCEJOS COMUNALES')";
              $this->ccfd01_division->execute($SQL_INSERT23);
              }

              $cdv18 = $this->ccfd01_division->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=2 and cod_cuenta=103 and cod_subcuenta=1 and cod_division=5');
              if($cdv18==0){
              $SQL_INSERT24 ="INSERT INTO ccfd01_division (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,cod_cuenta,cod_subcuenta, cod_division, denominacion)";
              $SQL_INSERT24 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 2, 103,1,5,'CONCEJOS COMUNALES')";
              $this->ccfd01_division->execute($SQL_INSERT24);
              }

              $cdv19 = $this->ccfd01_division->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=1 and cod_cuenta=128 and cod_subcuenta=1 and cod_division=5');
              if($cdv19==0){
              $SQL_INSERT25 ="INSERT INTO ccfd01_division (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,cod_cuenta,cod_subcuenta, cod_division, denominacion)";
              $SQL_INSERT25 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 1, 128,1,5,'CONCEJOS COMUNALES')";
              $this->ccfd01_division->execute($SQL_INSERT25);
              }

              $cdv20 = $this->ccfd01_division->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=1 and cod_cuenta=300 and cod_subcuenta=1 and cod_division=5');
              if($cdv20==0){
              $SQL_INSERT26 ="INSERT INTO ccfd01_division (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,cod_cuenta,cod_subcuenta, cod_division, denominacion)";
              $SQL_INSERT26 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 1, 300,1,5,'CONCEJOS COMUNALES')";
              $this->ccfd01_division->execute($SQL_INSERT26);
              }


              ////////division para los ALCALDIAS
              $cdv21 = $this->ccfd01_division->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=2 and cod_cuenta=101 and cod_subcuenta=1 and cod_division=6');
              if($cdv21==0){
              $SQL_INSERT27 ="INSERT INTO ccfd01_division (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,cod_cuenta,cod_subcuenta, cod_division, denominacion)";
              $SQL_INSERT27 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 2, 101,1,6,'ALCALDIAS')";
              $this->ccfd01_division->execute($SQL_INSERT27);
              }

              $cdv22 = $this->ccfd01_division->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=2 and cod_cuenta=103 and cod_subcuenta=1 and cod_division=6');
              if($cdv22==0){
              $SQL_INSERT28 ="INSERT INTO ccfd01_division (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,cod_cuenta,cod_subcuenta, cod_division, denominacion)";
              $SQL_INSERT28 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 2, 103,1,6,'ALCALDIAS')";
              $this->ccfd01_division->execute($SQL_INSERT28);
              }

              $cdv23 = $this->ccfd01_division->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=1 and cod_cuenta=128 and cod_subcuenta=1 and cod_division=6');
              if($cdv23==0){
              $SQL_INSERT29 ="INSERT INTO ccfd01_division (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,cod_cuenta,cod_subcuenta, cod_division, denominacion)";
              $SQL_INSERT29 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 1, 128,1,6,'ALCALDIAS')";
              $this->ccfd01_division->execute($SQL_INSERT29);
              }

              $cdv24 = $this->ccfd01_division->findCount($this->condicionNDEP().' and cod_dep=1 and cod_tipo_cuenta=1 and cod_cuenta=300 and cod_subcuenta=1 and cod_division=6');
              if($cdv24==0){
              $SQL_INSERT30 ="INSERT INTO ccfd01_division (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,cod_cuenta,cod_subcuenta, cod_division, denominacion)";
              $SQL_INSERT30 .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, 1, 1, 300,1,6,'ALCALDIAS')";
              $this->ccfd01_division->execute($SQL_INSERT30);
              }

             */

///////////sub division
//echo 'cod_presi='.$cod_presi.' and cod_entidad='.$cod_entidad.' and cod_tipo_inst='.$cod_tipo_inst.' and cod_inst='.$cod_inst.' and cod_dep=1 and cod_tipo_cuenta=1 and cod_cuenta=300 and cod_subcuenta=1 and cod_division='.$objeto.' and cod_subdivision='.$gasto_presu_subdivision.'<br>';
            //$cdv25 = $this->ccfd01_subdivision->findCount('cod_presi='.$cod_presi.' and cod_entidad='.$cod_entidad.' and cod_tipo_inst='.$cod_tipo_inst.' and cod_inst='.$cod_inst.' and cod_dep=1 and cod_tipo_cuenta=1 and cod_cuenta=300 and cod_subcuenta=1 and cod_division='.$objeto.' and cod_subdivision='.$gasto_presu_subdivision);
            //	if($cdv25==0){
            $SQL_INSERT300 = "INSERT INTO ccfd01_subdivision (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta, cod_cuenta,
  			cod_subcuenta, cod_division, cod_subdivision, denominacion)";
            $SQL_INSERT300 .=" VALUES ($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,1,1,300,001,$objeto,$gasto_presu_subdivision,'" . $denominacion . "')";
            $x300 = $this->ccfd01_subdivision->execute($SQL_INSERT300);
            //	}

            $cdv26 = $this->ccfd01_subdivision->findCount('cod_presi=' . $cod_presi . ' and cod_entidad=' . $cod_entidad . ' and cod_tipo_inst=' . $cod_tipo_inst . ' and cod_inst=' . $cod_inst . ' and cod_dep=1 and cod_tipo_cuenta=2 and cod_cuenta=103 and cod_subcuenta=1 and cod_division=' . $objeto . ' and cod_subdivision=' . $gasto_pagar_subdivision);
//		if($cdv26==0){
            $SQL_INSERT103 = "INSERT INTO ccfd01_subdivision (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta, cod_cuenta,
  			cod_subcuenta, cod_division, cod_subdivision, denominacion)";
            $SQL_INSERT103 .=" VALUES ($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,1,2,103,001,$objeto,$gasto_pagar_subdivision,'" . $denominacion . "')";
            $x103 = $this->ccfd01_subdivision->execute($SQL_INSERT103);
//		}
//		$cdv27 = $this->ccfd01_subdivision->findCount('cod_presi='.$cod_presi.' and cod_entidad='.$cod_entidad.' and cod_tipo_inst='.$cod_tipo_inst.' and cod_inst='.$cod_inst.' and cod_dep=1 and cod_tipo_cuenta=2 and cod_cuenta=101 and cod_subcuenta=1 and cod_division='.$objeto.' and cod_subdivision='.$orden_pago_subdivision);
//		if($cdv27==0){
            $SQL_INSERT101 = "INSERT INTO ccfd01_subdivision (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta, cod_cuenta,
  			cod_subcuenta, cod_division, cod_subdivision, denominacion)";
            $SQL_INSERT101 .=" VALUES ($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,1,2,101,001,$objeto,$orden_pago_subdivision,'" . $denominacion . "')";
            $x101 = $this->ccfd01_subdivision->execute($SQL_INSERT101);
//		}
//		$cdv28 = $this->ccfd01_subdivision->findCount('cod_presi='.$cod_presi.' and cod_entidad='.$cod_entidad.' and cod_tipo_inst='.$cod_tipo_inst.' and cod_inst='.$cod_inst.' and cod_dep=1 and cod_tipo_cuenta=1 and cod_cuenta=128 and cod_subcuenta=1 and cod_division='.$objeto.' and cod_subdivision='.$anticipo_prov_subdivision);
//		if($cdv28==0){
            $SQL_INSERT128 = "INSERT INTO ccfd01_subdivision (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta, cod_cuenta,
  			cod_subcuenta, cod_division, cod_subdivision, denominacion)";
            $SQL_INSERT128 .=" VALUES ($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,1,1,128,001,$objeto,$anticipo_prov_subdivision,'" . $denominacion . "')";
            $x128 = $this->ccfd01_subdivision->execute($SQL_INSERT128);
            //	}
//*/
        }
//echo $cont;
        //}
        $this->index();
        $this->render("index");
    }

}

//fin class
?>
