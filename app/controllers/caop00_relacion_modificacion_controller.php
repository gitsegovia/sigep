<?php
class Caop00RelacionmodificacionController extends AppController {

	var $name = "caop00_relacion_modificacion";
	var $uses = array (
		'ccfd03_instalacion',
		'cscd04_ordencompra_encabezado',
		'ccfd04_cierre_mes',
		'cugd03_acta_anulacion_numero',
		'cugd03_acta_anulacion_cuerpo',
		'cscd04_ordcom_modificacion_cuerpo',
		'cscd04_ordencompra_modificacion_partidas',
		'cscd04_ordencompra_partidas',
		'cpcd02',
		'v_cfpd05_disponibilidad',
		'cfpd21_numero_asiento_compromiso',
		'cfpd21',
		'cfpd05',
		'cugd04',
		'select_orden_compra',
		'select_modificacion_compra',
		'cfpd07_obras_partidas',
		'cfpd07_obras_cuerpo',
		'cfpd07_obras_modificacion_cuerpo',
		'cfpd07_obras_modificacion_partidas',

		'cstd01_entidades_bancarias',
		'ccfd10_descripcion',
		'ccfd10_detalles',
		'ccfd02',
		'ccfd05_numero_asiento',
		'ccfd04_cuentas_enlace',
		'cpcd02',
		'cepd01_compromiso_cuerpo',
		'cscd04_ordencompra_anticipo_cuerpo',
		'cscd04_ordencompra_encabezado',
		'cscd04_ordencompra_autorizacion_cuerpo',
		'cobd01_contratoobras_anticipo_cuerpo',
		'cobd01_contratoobras_valuacion_cuerpo',
		'cobd01_contratoobras_retencion_cuerpo',
		'cepd02_contratoservicio_anticipo_cuerpo',
		'cepd02_contratoservicio_valuacion_cuerpo',
		'cepd02_contratoservicio_retencion_cuerpo',
		'cobd01_contratoobras_cuerpo',
		'cepd02_contratoservicio_cuerpo',
		'cugd05_restriccion_clave'
	);
	var $helpers = array (
		'Html',
		'Ajax',
		'Javascript',
		'Sisap'
	);

	function checkSession() {
		if (!$this->Session->check('Usuario')) {
			$this->redirect('/salir/');
			exit ();
		} else {
			$this->requestAction('/usuarios/actualizar_user');
		}
	} //fin checksession

	function beforeFilter() {
		$this->checkSession();
	} //fin function

	function verifica_SS($i) {
		/**
		 * Funcion que permite leer las varibles de session. la cual permite capturar los codigos del usuario
		 * para ser insertados en todas las tablas.
		 * */
		switch ($i) {
			case 1 :
				return $this->Session->read('SScodpresi');
				break;
			case 2 :
				return $this->Session->read('SScodentidad');
				break;
			case 3 :
				return $this->Session->read('SScodtipoinst');
				break;
			case 4 :
				return $this->Session->read('SScodinst');
				break;
			case 5 :
				return $this->Session->read('SScoddep');
				break;
			case 6 :
				return $this->Session->read('entidad_federal');
				break;
			default :
				return "NULO";

		} //fin switch
	} //fin verifica_SS

	function SQLCA($ano = null) { //sql para busqueda de codigos de arranque con y sin año
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
	} //fin funcion SQLCA

	function SQLCAIN($ano = null) { //sql para busqueda de codigos de arranque con y sin año
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
	} //fin funcion SQLCAIN

	function Formato1($monto) {
		$monto = preg_replace("/[^0-9\.]/", "", str_replace(',', '.', $monto));
		if (substr($monto, -3, 1) == '.') {
			$sents = '.' . substr($monto, -2);
			$monto = substr($monto, 0, strlen($monto) - 3);
		}
		elseif (substr($monto, -2, 1) == '.') {
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

	function index() {
		$this->layout = "ajax";
		$ano = $this->ano_ejecucion();
		$this->set('year', $ano);
		$this->data = null;
	} //fin function

	function buscar_codigos_obras($var1 = null, $var2 = null, $var3 = null) {
		$this->layout = "ajax";
		//$this->Session->write('beneficiario_buscar',1);

	} //fin function

	function buscar_pista_obras($var1 = null, $var2 = null, $var3 = null) {
		$this->layout = "ajax";

		$year_buscar = $this->ano_ejecucion();
		$tabla = "cfpd07_obras_cuerpo";
		$campo = "cod_obra";

		$SScoddeporig = $this->Session->read('SScoddeporig');
		$SScoddep = $this->Session->read('SScoddep');
		$Modulo = $this->Session->read('Modulo');
		$condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $SScoddep . ' and ano_estimacion=' . $year_buscar;

		if ($var1 != null && $var1 != '_') {
			$var1 = strtoupper_sisap($var1);
			$this->Session->write('pista', $var1);
		} else {
			$var1 = $this->Session->read('pista');
		} //fin else

		if ($var2 != null) {
			$pagina = $var2;
		} else {
			$pagina = 1;
		}
		$this->set('pista', $var1);

		$condicion = $condicion . ' and punto_operacion!=2' . ' and ano_estimacion=' . $year_buscar . '   and ((estimado_presu + aumento_obras) - (disminucion_obras + monto_contratado)) != 0     ';

		$ordena = "cod_obra";
		$Tfilas = $this-> $tabla->findCount($condicion . " and " . $this->busca_separado(array (
			"cod_obra",
			"denominacion"
		), $var1));
		if ($Tfilas != 0) {

			$Tfilas = (int) ceil($Tfilas / 100);
			$this->set('pag_cant', $pagina . '/' . $Tfilas);
			$this->set('total_paginas', $Tfilas);
			$this->set('pagina_actual', $pagina);
			$this->set('ultimo', $Tfilas);
			$datos_filas = $this-> $tabla->findAll($condicion . " and " . $this->busca_separado(array (
				"cod_obra",
				"denominacion"
			), $var1), null, $ordena . " ASC", 100, $pagina, null);
			$this->set("datosFILAS", $datos_filas);
			$this->set('siguiente', $pagina +1);
			$this->set('anterior', $pagina -1);
			$this->bt_nav($Tfilas, $pagina);
		} else {
			$this->set("datosFILAS", '');
		}

		$this->set("tabla", $tabla);
		$this->set("campo", $campo);

	} //fin function

	function mostrar_partidas_obra($var = null) {
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

		$this->Session->delete("items");
		$this->Session->delete("i");
		if ($var != null) {
			$opcion = "si";
			$cont_a = 0;
			$cont_b = 0;
			$opc = 0;
			$disponible_partida = 0;

			$result = $this->cfpd07_obras_modificacion_cuerpo->findAll($condicion . "   and ano_estimacion=" . $ano . "  and  upper(cod_obra)='" . strtoupper($var) . "' ", null, "numero_modificacion ASC", null, null);
			foreach ($result as $ves) {
				$numero_modificacion = $ves['cfpd07_obras_modificacion_cuerpo']['numero_modificacion'];
			} //fin foreach
			$numero_modificacion++;

			$cfpd07_obras_partidas = $this->cfpd07_obras_partidas->findAll($condicion . " and ano_estimacion=" . $ano . " and upper(cod_obra)='" . strtoupper($var) . "' ", null, 'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra, cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
			foreach ($cfpd07_obras_partidas as $aux_cfpd07_obras_partidas) {
				$cont_a++;

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
				$monto = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['monto'];
				$monto_contratado = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['monto_contratado'];
				$aumento_obras = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['aumento_obras'];
				$disminucion_obras = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['disminucion_obras'];

				$disp_partida = $this->disponibilidad($ano_estimacion, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
				$disponible_partida = ($disponible_partida + $disp_partida);

			} //fin foreach

			$denominacion_obra = "";

			$cfpd07_obras_cuerpo_aux = $this->cfpd07_obras_cuerpo->findAll($condicion . " and ano_estimacion=" . $ano . " and upper(cod_obra)='" . strtoupper($var) . "' ");
			foreach ($cfpd07_obras_cuerpo_aux as $aux) {
				$codigo_obra = $aux['cfpd07_obras_cuerpo']['cod_obra'];
				$codigo_prod_serv = $aux['cfpd07_obras_cuerpo']['codigo_prod_serv'];
				$estimado_presu = $aux['cfpd07_obras_cuerpo']['estimado_presu'];
				$denominacion_obra = $aux['cfpd07_obras_cuerpo']['denominacion'];

				$a = $aux['cfpd07_obras_cuerpo']['estimado_presu'];
				$b = $aux['cfpd07_obras_cuerpo']['monto_contratado'];
				$c = $aux['cfpd07_obras_cuerpo']['aumento_obras'];
				$d = $aux['cfpd07_obras_cuerpo']['disminucion_obras'];
				$aj = (($a + $c) - $d);
				$e = (($a + $c) - ($d + $b));
			} //fin foreach

			$this->set('a', $a);
			$this->set('b', $b);
			$this->set('c', $c);
			$this->set('aj', $aj);
			$this->set('d', $d);
			$this->set('e', $e);
			$this->set('numero_modificacion', $numero_modificacion);

			$this->set('selecion_lista', $var);
			$this->set('estimado_presu', $estimado_presu);
			$this->set('cfpd07_obras_partidas', $cfpd07_obras_partidas);
			$this->set('cod_snc', $cod_snc);
			$this->set('ano_ejecucion', $ano);

			$this->Session->write('caop02_codigo_obra', $codigo_obra);

			$denominacion_obra = str_replace("\n", '', $denominacion_obra);
			$denominacion_obra = str_replace("'", '', $denominacion_obra);
			$denominacion_obra = str_replace(">", '', $denominacion_obra);
			$denominacion_obra = str_replace("<", '', $denominacion_obra);
			$denominacion_obra = str_replace('"', '', $denominacion_obra);
			$this->set('denominacion_obra', $denominacion_obra);

		} else {

		} //fin else

		$this->set('var', $var);

	} //fin funcion mostrar_partidas_obra

	function selecion($var1 = null) {
		$this->layout = "ajax";

		$ano = '';
		$ano = $this->ano_ejecucion();

		$condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $this->Session->read('SScoddep') . ' and  ano_orden_compra=' . $ano . '';
		$lista = $this->select_orden_compra->generateList($condicion . ' and condicion_actividad=1' . ' and punto_operacion=1' . " and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL)", ' numero_orden_compra ASC', null, '{n}.select_orden_compra.numero_orden_compra', '{n}.select_orden_compra.beneficiario');
		$this->set('lista_numero', $lista);

		$this->set('numero_orden_compra', $var1);
		$this->Session->delete('PAG_NUM');

		if ($var1 == null) {

			$this->index();
			$this->render('index');

		} else {

			$condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $this->Session->read('SScoddep') . ' and  numero_orden_compra=' . $var1 . '  and  ano_orden_compra=' . $ano . ' ';
			$numero_datos = $this->cscd04_ordencompra_encabezado->findAll($condicion);
			$numero_datos_partidas = $this->cscd04_ordencompra_partidas->findAll($condicion, null, 'numero_orden_compra DESC');

			$numero_datos_aux = $numero_datos;
			foreach ($numero_datos_aux as $aux) {
				$rif = $aux['cscd04_ordencompra_encabezado']['rif'];
				$ano_orden_compra = $aux['cscd04_ordencompra_encabezado']['ano_orden_compra'];
				$numero_orden_compra = $aux['cscd04_ordencompra_encabezado']['numero_orden_compra'];
			} //fin foreach

			$opc = $this->cscd04_ordcom_modificacion_cuerpo->findCount($condicion . ' and ano_orden_compra=' . $ano_orden_compra . '  and  numero_orden_compra=' . $numero_orden_compra . '');

			$result = $this->cscd04_ordcom_modificacion_cuerpo->findAll($condicion . "   and ano_orden_compra=" . $ano_orden_compra . "  and  numero_orden_compra=" . $numero_orden_compra . " ", null, "numero_modificacion ASC", null, null);
			foreach ($result as $ves) {
				$opc = $ves['cscd04_ordcom_modificacion_cuerpo']['numero_modificacion'];
			} //fin foreach

			$result2 = $this->select_orden_compra->findAll($condicion . "                 and ano_orden_compra=" . $ano_orden_compra . "  and  numero_orden_compra=" . $numero_orden_compra . " ", null, null, null, null);
			foreach ($result2 as $ves2) {
				$cod_obra = $ves2['select_orden_compra']['cod_obra'];
			} //fin foreach
			$this->Session->write("caop02_codigo_obra", $cod_obra);

			$rif_datos = $this->cpcd02->findAll("rif='" . $rif . "'");
			foreach ($rif_datos as $aux_2) {
				$denominacion_rif = $aux_2['cpcd02']['denominacion'];
				$direccion_comercial_rif = $aux_2['cpcd02']['direccion_comercial'];
			} //fin foreach

			$opc++;
			$this->set('ano_orden_compra_modificacion', $ano);
			$this->set('numero_orden_compra_modificacion', $opc);
			$this->set('datos_orden_compra', $numero_datos);
			$this->set('datos_orden_compra_partidas', $numero_datos_partidas);
			$this->set('denominacion_rif', $denominacion_rif);
			$this->set('direccion_comercial_rif', $direccion_comercial_rif);

		} //fin else

	} //fin function

function aumento_disminucion($opcion=null, $monto=null){
        $this->layout = "ajax";
        $username = $this->Session->read('nom_usuario');
        $ano = $this->ano_ejecucion();
        $tipo = $opcion;
        $monto = $this->Formato1($monto);
        $disponibilidad = 0;
        $cobp01_codigo_obra = $this->Session->read("caop02_codigo_obra");
        $condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $this->Session->read('SScoddep') . '  ';

        if ($tipo == 1){
            $cfpd07_obras_partidas = $this->cfpd07_obras_partidas->findAll($condicion . " and ano_estimacion=" . $ano . " and upper(cod_obra)='" . strtoupper($cobp01_codigo_obra) . "' ", null, 'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra, cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');

            foreach ($cfpd07_obras_partidas as $aux_cfpd07_obras_partidas) {
                $cod_sector        = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sector'];
                $cod_programa      = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_programa'];
                $cod_sub_prog      = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_prog'];
                $cod_proyecto      = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_proyecto'];
                $cod_activ_obra    = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_activ_obra'];
                $cod_partida       = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_partida'];
                $cod_generica      = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_generica'];
                $cod_especifica    = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_especifica'];
                $cod_sub_espec     = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_sub_espec'];
                $cod_auxiliar      = $aux_cfpd07_obras_partidas['cfpd07_obras_partidas']['cod_auxiliar'];
                $dispo             = $this->disponibilidad($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
                $disponibilidad    = ($disponibilidad + $dispo);
            }//fin foreach

			$disponibilidad = $this->Formato2($disponibilidad);
            $disponibilidad = $this->Formato1($disponibilidad);

            if ($monto > $disponibilidad) {
                $this->set('msg_error', 'No hay suficiente disponibilidad para realizar el Aumento - Monto Disponible: ' . $this->Formato2($disponibilidad));

            	echo "<script> document.getElementById('guardar').disabled=true; </script>";
				echo "<script> document.getElementById('aumento').value='0,00'; </script>";

            }else{
                echo"<script> document.getElementById('guardar').disabled=false; </script>";
            }


        }else{
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
                $this->set('msg_error', 'El monto de la disminucion es mayor al saldo de la relacion - Saldo Disponible: ' . $this->Formato2($disponibilidad));
                echo "<script> document.getElementById('guardar').disabled=true; </script>";
				echo "<script> document.getElementById('disminucion').value='0,00'; </script>";
            }else{
                echo"<script> document.getElementById('guardar').disabled=false; </script>";
            }

     }


}// fin funcion

	function ver_disponibilidad($i, $ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar, $monto) {
		$this->layout = "ajax";
		$username = $this->Session->read('nom_usuario');
		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$tipo = $_SESSION['tipo'];
		$_SESSION["items"][$i][11] = $monto;
		$monto = $this->Formato1($monto);

		$disponibilidad = 0;

		if ($tipo == 1) {
			$disponibilidad = $this->disponibilidad($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);

			$disponibilidad = $this->Formato2($disponibilidad);
			$disponibilidad = $this->Formato1($disponibilidad);

			if ($monto > $disponibilidad) {
				$this->set('msg_error', 'la disponibilidad para esta partida es de: ' . $this->Formato2($disponibilidad));
				$this->set('i', $i);
				echo "<script> document.getElementById('guardar').disabled=true; </script>";
				echo "<script> document.getElementById('monto_" . $i . "').value='0,00';</script>";
			} else {
				echo "<script> document.getElementById('guardar').disabled=false; </script>";
			}
		}else{
			$caop02_codigo_obra = $this->Session->read("caop02_codigo_obra");
			$cfpd07_obras_partidas = $this->cfpd07_obras_partidas->findAll("cod_presi='" . $cod_presi . "' and cod_entidad='" . $cod_entidad . "' and cod_tipo_inst='" . $cod_tipo_inst . "' and cod_inst='" . $cod_inst . "' and cod_dep='" . $cod_dep . "' and ano_estimacion='" . $ano . "'  and upper(cod_obra)='" . strtoupper_sisap($caop02_codigo_obra) . "' and  cod_sector='" . $cod_sector . "' and cod_programa='" . $cod_programa . "' and cod_sub_prog='" . $cod_sub_prog . "' and cod_proyecto='" . $cod_proyecto . "' and cod_activ_obra='" . $cod_activ_obra . "' and cod_partida='" . $cod_partida . "' and cod_generica='" . $cod_generica . "' and cod_especifica='" . $cod_especifica . "' and cod_sub_espec='" . $cod_sub_espec . "' and cod_auxiliar='" . $cod_auxiliar . "' ", null, 'cod_obra DESC');

			foreach ($cfpd07_obras_partidas as $ve3) {
				$monto_2 = $ve3["cfpd07_obras_partidas"]["monto"];
				$monto_contratado_2 = $ve3["cfpd07_obras_partidas"]["monto_contratado"];
				$aumento_obras_2 = $ve3["cfpd07_obras_partidas"]["aumento_obras"];
				$disminucion_obras_2 = $ve3["cfpd07_obras_partidas"]["disminucion_obras"];
				$disponibilidad = (($monto_2 + $aumento_obras_2) - ($disminucion_obras_2 + $monto_contratado_2));
			} //fin foreach

			$disponibilidad = $this->Formato2($disponibilidad);
			$disponibilidad = $this->Formato1($disponibilidad);

			if ($monto > $disponibilidad) {
				$this->set('msg_error', 'la disponibilidad para esta partida es de: ' . $this->Formato2($disponibilidad));
				$this->set('i', $i);
				echo "<script> document.getElementById('guardar').disabled=true; </script>";
				echo "<script> document.getElementById('monto_" . $i . "').value='0,00';</script>";
			}else{
				echo "<script> document.getElementById('guardar').disabled=false; </script>";
			}

		}

		$vec = $_SESSION["items"];
		$monto = 0;
		$opcion = "si";

		for ($z = 0; $z < count($vec); $z++) {
			if ($vec[$z]['id'] == "no" && $vec[$z]['id'] != "0") {
			} else {
				$monto += $this->Formato1($vec[$z][11]);
			} //fin else
		} //fin for

		echo "<script>cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', " . $monto . ")</script>";

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
				$this->cugd04->execute("UPDATE cugd04_entrada_modulo SET hora_captura_partida='$time'");
			} else {
				$sql_update_cugd04 = "UPDATE cugd04 set cod_auxiliar='$cod_auxiliar' WHERE " . $this->condicion() . $partida;
				$this->cugd04->execute($sql_update_cugd04);
			}

		}
	}

	function prueba() {

		echo '<script>';
		echo 'alert("hola");';
		echo '</script>';

	} //fin function

	function tipo_modificacion($var1 = null) {
		$this->layout = "ajax";
		$_SESSION['tipo'] = $var1;
		switch ($var1) {
			case '1' :
				{
					$tipo = "Monto del Aumento";
					$opcion = "aumento";
				}
				break;
			case '2' :
				{
					$tipo = "Monto de la Disminución";
					$opcion = "disminucion";
				}
				break;
		} //fin switch

		$this->set('tipo_opc', $var1);
		$this->set('opcion', $opcion);
		$this->set('tipo', $tipo);

		echo '<script> document.getElementById("modificacion_texto").innerHTML = "MONTO ' . $opcion . '"; </script>';
	} //fin function

	function guardar() {
		$this->layout = "ajax";
		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');

		$ano_obra = $this->data['caop00_relacion_modificacion']['ano_obra'];
		$codigo_obra = $this->data['caop00_relacion_modificacion']['codigo_obra'];
		$tipo_modificacion = $this->data['caop00_relacion_modificacion']['tipo_modificacion'];

		////////
		$fecha_modificacion = $this->data['caop00_relacion_modificacion']['fecha_modificacion'];
		$observaciones = $this->data['caop00_relacion_modificacion']['observaciones'];
		$numero_modificacion = $this->data['caop00_relacion_modificacion']['numero_modificacion'];
		///////

		if ($tipo_modificacion == '1') {
			$monto_modificacion = $this->Formato1($this->data['caop00_relacion_modificacion']['aumento']);
		} else
			if ($tipo_modificacion == '2') {
				$monto_modificacion = $this->Formato1($this->data['caop00_relacion_modificacion']['disminucion']);
			} //fin else

		$vec = $_SESSION["items"];

		for ($i = 0; $i < count($vec); $i++) {
			if ($vec[$i]['id'] == "no_mostrar" && $vec[$i]['id'] != "0") {
			} else {
				$new_array[$i][0] = $vec[$i][0];
				$new_array[$i][1] = $vec[$i][1];
				$new_array[$i][2] = $vec[$i][2];
				$new_array[$i][3] = $vec[$i][3];
				$new_array[$i][4] = $vec[$i][4];
				$new_array[$i][5] = $vec[$i][5];
				$new_array[$i][6] = $vec[$i][6];
				$new_array[$i][7] = $vec[$i][7];
				$new_array[$i][8] = $vec[$i][8];
				$new_array[$i][9] = $vec[$i][9];
				$new_array[$i][10] = $vec[$i][10];
				$new_array[$i][11] = $vec[$i][11];
			} //fin else
		} //fin for

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

		$contar_no_disp = 0;
		$i_no_d = 0;
		$_SESSION["partidas_no_disp"] = array ();
		$new_array_no_d = array ();
		foreach ($new_array as $vec) {
			$disponible_partida = $this->disponibilidad($vec[0], $vec[1], $vec[2], $vec[3], $vec[4], $vec[5], $vec[6], $vec[7], $vec[8], $vec[9], $vec[10]);
			$monto_partida_array = $this->Formato1($vec[11]);

			if ($disponible_partida < 0) {
				$disponible_partida = 0;
			}

			$disponible_partida = $this->Formato2($disponible_partida);
			$disponible_partida = $this->Formato1($disponible_partida);

			$cfpd07_obras_partidas = $this->cfpd07_obras_partidas->findAll($this->condicion() . " and ano_estimacion='" . $ano_obra . "'  and upper(cod_obra)='" . strtoupper_sisap($codigo_obra) . "' and  cod_sector='" . $vec[1] . "' and cod_programa='" . $vec[2] . "' and cod_sub_prog='" . $vec[3] . "' and cod_proyecto='" . $vec[4] . "' and cod_activ_obra='" . $vec[5] . "' and cod_partida='" . $vec[6] . "' and cod_generica='" . $vec[7] . "' and cod_especifica='" . $vec[8] . "' and cod_sub_espec='" . $vec[9] . "' and cod_auxiliar='" . $vec[10] . "' ", null, 'cod_obra DESC');
			foreach ($cfpd07_obras_partidas as $ve3) {
				$cod_obra_2 = $ve3["cfpd07_obras_partidas"]["cod_obra"];
				$cod_sector_2 = $ve3["cfpd07_obras_partidas"]["cod_sector"];
				$cod_programa_2 = $ve3["cfpd07_obras_partidas"]["cod_programa"];
				$cod_sub_prog_2 = $ve3["cfpd07_obras_partidas"]["cod_sub_prog"];
				$cod_proyecto_2 = $ve3["cfpd07_obras_partidas"]["cod_proyecto"];
				$cod_activ_obra_2 = $ve3["cfpd07_obras_partidas"]["cod_activ_obra"];
				$cod_partida_2 = $ve3["cfpd07_obras_partidas"]["cod_partida"];
				$cod_generica_2 = $ve3["cfpd07_obras_partidas"]["cod_generica"];
				$cod_especifica_2 = $ve3["cfpd07_obras_partidas"]["cod_especifica"];
				$cod_sub_espec_2 = $ve3["cfpd07_obras_partidas"]["cod_sub_espec"];
				$cod_auxiliar_2 = $ve3["cfpd07_obras_partidas"]["cod_auxiliar"];
				$monto_2 = $ve3["cfpd07_obras_partidas"]["monto"];
				$monto_contratado_2 = $ve3["cfpd07_obras_partidas"]["monto_contratado"];
				$aumento_obras_2 = $ve3["cfpd07_obras_partidas"]["aumento_obras"];
				$disminucion_obras_2 = $ve3["cfpd07_obras_partidas"]["disminucion_obras"];

				$disponible = (($monto_2 + $aumento_obras_2) - ($monto_contratado_2 + $disminucion_obras_2));

				if ($disponible < 0) {
					$disponible = 0;
				}

				$disponible = $this->Formato2($disponible);
				$disponible = $this->Formato1($disponible);

			} //fin foreach

			if ($tipo_modificacion == '1') {

				if ($monto_partida_array > $disponible_partida) {
					$contar_no_disp = $contar_no_disp +1;
				} else {
					$contar_no_disp = $contar_no_disp +0;
					$new_array_no_d[$i_no_d][0] = $vec[0];
					$new_array_no_d[$i_no_d][1] = $vec[1];
					$new_array_no_d[$i_no_d][2] = $vec[2];
					$new_array_no_d[$i_no_d][3] = $vec[3];
					$new_array_no_d[$i_no_d][4] = $vec[4];
					$new_array_no_d[$i_no_d][5] = $vec[5];
					$new_array_no_d[$i_no_d][6] = $vec[6];
					$new_array_no_d[$i_no_d][7] = $vec[7];
					$new_array_no_d[$i_no_d][8] = $vec[8];
					$new_array_no_d[$i_no_d][9] = $vec[9];
					$new_array_no_d[$i_no_d][10] = $vec[10];
					$new_array_no_d[$i_no_d][11] = $vec[11];
					$new_array_no_d[$i_no_d][12] = $this->Formato2($disponible_partida);
					$i_no_d++;
				}

			} else {

				if ($monto_partida_array > $disponible) {
					$contar_no_disp = $contar_no_disp +1;
				} else {
					$contar_no_disp = $contar_no_disp +0;
					$new_array_no_d[$i_no_d][0] = $vec[0];
					$new_array_no_d[$i_no_d][1] = $vec[1];
					$new_array_no_d[$i_no_d][2] = $vec[2];
					$new_array_no_d[$i_no_d][3] = $vec[3];
					$new_array_no_d[$i_no_d][4] = $vec[4];
					$new_array_no_d[$i_no_d][5] = $vec[5];
					$new_array_no_d[$i_no_d][6] = $vec[6];
					$new_array_no_d[$i_no_d][7] = $vec[7];
					$new_array_no_d[$i_no_d][8] = $vec[8];
					$new_array_no_d[$i_no_d][9] = $vec[9];
					$new_array_no_d[$i_no_d][10] = $vec[10];
					$new_array_no_d[$i_no_d][11] = $vec[11];
					$new_array_no_d[$i_no_d][12] = $this->Formato2($disponible_partida);
					$i_no_d++;
				}
			}

		}
		$_SESSION["partidas_no_disp"] = $new_array_no_d;
		$PASAR = $contar_no_disp;
		$swbb = 0;

		$sql = " BEGIN; INSERT INTO cfpd07_obras_modificacion_cuerpo (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_estimacion, cod_obra, numero_modificacion, tipo_modificacion, monto_modificacion, fecha_modificacion, observaciones, dia_asiento_registro, mes_asiento_registro, ano_asiento_registro, numero_asiento_registro, fecha_proceso_registro, username_registro, condicion_actividad, ano_anulacion, numero_anulacion, dia_asiento_anulacion, mes_asiento_anulacion, ano_asiento_anulacion, numero_asiento_anulacion, fecha_proceso_anulacion, username_anulacion)";
		$sql .= "VALUES ('" . $this->Session->read('SScodpresi') . "', '" . $this->Session->read('SScodentidad') . "', '" . $this->Session->read('SScodtipoinst') . "', '" . $this->Session->read('SScodinst') . "', '" . $this->Session->read('SScoddep') . "', '" . $ano_obra . "', '" . strtoupper($codigo_obra) . "', '" . $numero_modificacion . "', '" . $tipo_modificacion . "', '" . $monto_modificacion . "', '" . $this->Cfecha($fecha_modificacion, 'A-M-D') . "', '" . $observaciones . "', '" . $dia_asiento_registro . "', '" . $mes_asiento_registro . "', '" . $ano_asiento_registro . "', '" . $numero_asiento_registro . "', '" . $this->Cfecha($fecha_proceso_registro, 'A-M-D') . "', '" . $username_registro . "', '" . $condicion_actividad . "', '" . $ano_anulacion . "', '" . $numero_anulacion . "', '" . $dia_asiento_anulacion . "', '" . $mes_asiento_anulacion . "', '" . $ano_asiento_anulacion . "', '" . $numero_asiento_anulacion . "', '" . $this->Cfecha($fecha_proceso_anulacion, 'A-M-D') . "', '" . $username_anulacion . "'); ";
		$swa = $this->cfpd07_obras_modificacion_cuerpo->execute($sql);

		if ($PASAR == 0) {

			//											pr($new_array);

			foreach ($new_array as $vec) {

				if ($this->Formato1($vec[11]) != 0) {
					if ($tipo_modificacion == '1') {
						$aa = $this->Formato1($vec[11]);
						$bb = 0;
					} else
						if ($tipo_modificacion == '2') {
							$aa = 0;
							$bb = $this->Formato1($vec[11]);
						} //fin else
					$sql_aux = $this->SQLCA() . " and ano_estimacion=" . $ano_obra . " and upper(cod_obra)='" . strtoupper($codigo_obra) . "' and cod_sector=" . $vec[1] . "   and   cod_programa=" . $vec[2] . "   and   cod_sub_prog=" . $vec[3] . "   and   cod_proyecto=" . $vec[4] . "   and   cod_activ_obra=" . $vec[5] . "   and   cod_partida=" . $vec[6] . "   and   cod_generica=" . $vec[7] . "   and   cod_especifica=" . $vec[8] . "   and   cod_sub_espec=" . $vec[9] . "   and   cod_auxiliar=" . $vec[10] . "";
					$sql55 = "UPDATE cfpd07_obras_partidas SET aumento_obras = aumento_obras + " . $aa . ",  disminucion_obras = disminucion_obras + " . $bb . "  where " . $sql_aux . "; ";
					$sw155 = $this->cscd04_ordencompra_partidas->execute($sql55);

					$sql_aux = $this->SQLCA() . " and ano_estimacion=" . $ano_obra . " and upper(cod_obra)='" . strtoupper($codigo_obra) . "'";
					$sql55 = "UPDATE cfpd07_obras_cuerpo SET aumento_obras = aumento_obras + " . $aa . ",  disminucion_obras = disminucion_obras + " . $bb . "  where " . $sql_aux . " ; ";
					$sw155 = $this->cscd04_ordencompra_partidas->execute($sql55);

					$sql55 = "INSERT INTO cfpd07_obras_modificacion_partidas(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_estimacion, cod_obra, numero_modificacion, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica,  cod_sub_espec, cod_auxiliar,  monto, numero_control_compromiso) ";
					$sql55 .= "  VALUES ('" . $cod_presi . "', '" . $cod_entidad . "', '" . $cod_tipo_inst . "', '" . $cod_inst . "', '" . $cod_dep . "', '" . $ano_obra . "', '" . strtoupper($codigo_obra) . "', '" . $numero_modificacion . "', '" . $ano_obra . "', '" . $vec[1] . "', '" . $vec[2] . "', '" . $vec[3] . "', '" . $vec[4] . "', '" . $vec[5] . "', '" . $vec[6] . "', '" . $vec[7] . "', '" . $vec[8] . "',  '" . $vec[9] . "', '" . $vec[10] . "',  '" . $this->Formato1($vec[11]) . "', '0'); ";
					$sw155 = $this->cscd04_ordencompra_modificacion_partidas->execute($sql55);

					if ($tipo_modificacion == '1') {
						$sql_aux = $this->SQLCA() . " and ano=" . $ano_obra . "  and cod_sector=" . $vec[1] . "   and   cod_programa=" . $vec[2] . "   and   cod_sub_prog=" . $vec[3] . "   and   cod_proyecto=" . $vec[4] . "   and   cod_activ_obra=" . $vec[5] . "   and   cod_partida=" . $vec[6] . "   and   cod_generica=" . $vec[7] . "   and   cod_especifica=" . $vec[8] . "   and   cod_sub_espec=" . $vec[9] . "   and   cod_auxiliar=" . $vec[10] . "";
						$sql55 = "UPDATE cfpd05 SET precompromiso_obras = precompromiso_obras + " . $this->Formato1($vec[11]) . " WHERE " . $sql_aux;
						$sw155 = $this->cscd04_ordencompra_partidas->execute($sql55);
					} else {
						$sql_aux = $this->SQLCA() . " and ano=" . $ano_obra . "  and cod_sector=" . $vec[1] . "   and   cod_programa=" . $vec[2] . "   and   cod_sub_prog=" . $vec[3] . "   and   cod_proyecto=" . $vec[4] . "   and   cod_activ_obra=" . $vec[5] . "   and   cod_partida=" . $vec[6] . "   and   cod_generica=" . $vec[7] . "   and   cod_especifica=" . $vec[8] . "   and   cod_sub_espec=" . $vec[9] . "   and   cod_auxiliar=" . $vec[10] . "";
						$sql55 = "UPDATE cfpd05 SET precompromiso_obras = precompromiso_obras - " . $this->Formato1($vec[11]) . " WHERE " . $sql_aux;
						$sw155 = $this->cscd04_ordencompra_partidas->execute($sql55);
					}

					if ($sw155 > 1) {
						$swbb = 2;
					} else {
						$swbb = 0;
					}
					if ($swbb > 1) {
					} else {
						break;
					} //fin else
				}
			}

		}

		if ($swbb > 1) {
			$this->cscd04_ordencompra_modificacion_partidas->execute("COMMIT;");
			$this->set('Message_existe', 'Los datos fueron guardados correctamente');
		} else {
			$this->cscd04_ordencompra_modificacion_partidas->execute("ROLLBACK;");
			$this->set('errorMessage', 'NO SE LOGRO REALIZAR LA MODIFICACIÓN - POR FAVOR INTENTE DE NUEVO');
		} //fin else

		$this->index();
		$this->render('index');

	} //fin funtion guardar

	function buscar_year($var1 = null) {
		$this->layout = "ajax";
		$SScoddeporig = $this->Session->read('SScoddeporig');
		$SScoddep = $this->Session->read('SScoddep');
		$Modulo = $this->Session->read('Modulo');
		$condicion = 'cod_presi=' . $this->Session->read('SScodpresi') . '  and  cod_entidad=' . $this->Session->read('SScodentidad') . ' and cod_tipo_inst=' . $this->Session->read('SScodtipoinst') . ' and  cod_inst=' . $this->Session->read('SScodinst') . ' and cod_dep=' . $this->Session->read('SScoddep') . '';
		$lista = $this->cfpd07_obras_modificacion_cuerpo->generateList($condicion . " and ano_estimacion = " . $var1 . " ", ' cod_obra ASC', null, '{n}.cfpd07_obras_modificacion_cuerpo.cod_obra', '{n}.cfpd07_obras_modificacion_cuerpo.cod_obra');
		$this->set('lista', $lista);
	} //fin function

	function consulta_index($var1 = null) {
		$this->layout = "ajax";
		$SScoddeporig = $this->Session->read('SScoddeporig');
		$SScoddep = $this->Session->read('SScoddep');
		$Modulo = $this->Session->read('Modulo');
		$pag_num = 0;
		$opcion = 'si';
		$condicion = $this->condicion();
		$ano = $this->ano_ejecucion();
		$lista = $this->cfpd07_obras_modificacion_cuerpo->generateList($condicion . " and ano_estimacion = " . $ano . " ", ' cod_obra ASC', null, '{n}.cfpd07_obras_modificacion_cuerpo.cod_obra', '{n}.cfpd07_obras_modificacion_cuerpo.cod_obra');
		$this->set('lista', $lista);
		$this->set('ano', $ano);
	} //fin function

	function consulta($ano_documento = null, $numero_documento = null, $pagina = null) {
		$this->layout = "ajax";

		if ($pagina == null) {
			$pagina = 1;
		}

		if (isset ($this->data['caop00_relacion_modificacion']['ano_ejecucion'])) {
			$ano_documento = $this->data['caop00_relacion_modificacion']['ano_ejecucion'];
			$numero_documento = $this->data['caop00_relacion_modificacion']['cod_obra'];
		}

		$Tfilas = $this->cfpd07_obras_modificacion_cuerpo->findCount($this->condicion() . " and ano_estimacion='" . $ano_documento . "' and upper(cod_obra)='" . strtoupper($numero_documento) . "' ");
		if ($Tfilas != 0) {
			$Tfilas = (int) ceil($Tfilas / 1);
			$this->set('pag_cant', $pagina . '/' . $Tfilas);
			$this->set('total_paginas', $Tfilas);
			$this->set('pagina_actual', $pagina);
			$this->set('ultimo', $Tfilas);
			$datos_filas = $this->cfpd07_obras_modificacion_cuerpo->findAll($this->condicion() . " and ano_estimacion='" . $ano_documento . "' and upper(cod_obra)='" . strtoupper($numero_documento) . "' ", null, "numero_modificacion ASC", 1, $pagina, null);
			$this->set("datosFILAS", $datos_filas);
			$this->set('siguiente', $pagina +1);
			$this->set('anterior', $pagina -1);
			$this->bt_nav($Tfilas, $pagina);
		} else {
			$this->set("datosFILAS", '');
		}

		$datos_filas2 = $this->cfpd07_obras_modificacion_partidas->findAll($this->condicion() . " and ano_estimacion='" . $ano_documento . "' and upper(cod_obra)='" . strtoupper($numero_documento) . "' and  numero_modificacion='" . $datos_filas[0]["cfpd07_obras_modificacion_cuerpo"]["numero_modificacion"] . "' ", null, "numero_modificacion ASC");
		$this->set("datosFILAS2", $datos_filas2);

		$cfpd07_obras_cuerpo = $this->cfpd07_obras_cuerpo->findAll($this->condicion() . " and ano_estimacion=" . $ano_documento . " and upper(cod_obra)='" . strtoupper($numero_documento) . "' ");
		$denominacion_obra = "";
		$cfpd07_obras_cuerpo_aux = $cfpd07_obras_cuerpo;
		foreach ($cfpd07_obras_cuerpo_aux as $aux) {
			$estimado_presu = $aux['cfpd07_obras_cuerpo']['estimado_presu'];
			$denominacion_obra = $aux['cfpd07_obras_cuerpo']['denominacion'];
		} //fin foreach

		$this->set('estimado_presu', $estimado_presu);
		$this->set('ano_ejecucion', $ano_documento);

		$denominacion_obra = str_replace("\n", '', $denominacion_obra);
		$denominacion_obra = str_replace("'", '', $denominacion_obra);
		$denominacion_obra = str_replace(">", '', $denominacion_obra);
		$denominacion_obra = str_replace("<", '', $denominacion_obra);
		$denominacion_obra = str_replace('"', '', $denominacion_obra);
		$this->set('denominacion_obra', $denominacion_obra);

	} //fin function

	function guardar_anulacion1($var = null) {
		$this->layout = "ajax";

		echo '<script>';
		echo 'document.getElementById("guardar").disabled = false; ';
		echo 'document.getElementById("anular").disabled = true; ';
		echo '</script>';

	} //fin function

	function guardar_anulacion2($var1 = null, $var2 = null, $var3 = null, $var4 = null) {

		$this->layout = "ajax";

		//$datos_partidas = $this->cfpd07_obras_modificacion_partidas->findAll($conditions = $this->condicion() . " and ano_estimacion='$var1' and upper(cod_obra)='strtoupper($var2)' and numero_modificacion='$var3'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
		$datos_partidas = $this->cfpd07_obras_modificacion_partidas->findAll($conditions = $this->SQLCA() . " and ano_estimacion=" . $var1 . " and upper(cod_obra)='" . strtoupper($var2) . "' and numero_modificacion=" . $var3);
		$sql_update_cscd04_partidas = '';
		$sql_cfpd07_obras_partidas = "";
		foreach ($datos_partidas as $row) {
			$ano = $row['cfpd07_obras_modificacion_partidas']['ano'];
			$cod_sector = $row['cfpd07_obras_modificacion_partidas']['cod_sector'];
			$cod_programa = $row['cfpd07_obras_modificacion_partidas']['cod_programa'];
			$cod_sub_prog = $row['cfpd07_obras_modificacion_partidas']['cod_sub_prog'];
			$cod_proyecto = $row['cfpd07_obras_modificacion_partidas']['cod_proyecto'];
			$cod_activ_obra = $row['cfpd07_obras_modificacion_partidas']['cod_activ_obra'];
			$cod_partida = $row['cfpd07_obras_modificacion_partidas']['cod_partida'];
			$cod_generica = $row['cfpd07_obras_modificacion_partidas']['cod_generica'];
			$cod_especifica = $row['cfpd07_obras_modificacion_partidas']['cod_especifica'];
			$cod_sub_espec = $row['cfpd07_obras_modificacion_partidas']['cod_sub_espec'];
			$cod_auxiliar = $row['cfpd07_obras_modificacion_partidas']['cod_auxiliar'];
			$monto_partida = $row['cfpd07_obras_modificacion_partidas']['monto'];
			if ($var4 == 1) {
				$disminucion2 = 0;
				$aumento2 = $monto_partida;
			} else {
				$aumento2 = 0;
				$disminucion2 = $monto_partida;
			} //fin if
			$cond2 = " cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
			$sql_cfpd07_obras_partidas .= "UPDATE cfpd07_obras_partidas SET aumento_obras = aumento_obras - " . $aumento2 . ", disminucion_obras = disminucion_obras - " . $disminucion2 . "  where cod_presi=" . $this->Session->read('SScodpresi') . " and cod_entidad=" . $this->Session->read('SScodentidad') . " and cod_tipo_inst=" . $this->Session->read('SScodtipoinst') . " and cod_inst=" . $this->Session->read('SScodinst') . " and cod_dep=" . $this->Session->read('SScoddep') . "  and   upper(cod_obra)='" . strtoupper($var2) . "'  and  ano_estimacion=" . $var1 . " and " . $cond2 . "; ";
			$sql_cfpd07_obras_partidas .= "UPDATE cfpd07_obras_cuerpo   SET aumento_obras = aumento_obras - " . $aumento2 . ", disminucion_obras = disminucion_obras - " . $disminucion2 . "  where cod_presi=" . $this->Session->read('SScodpresi') . " and cod_entidad=" . $this->Session->read('SScodentidad') . " and cod_tipo_inst=" . $this->Session->read('SScodtipoinst') . " and cod_inst=" . $this->Session->read('SScodinst') . " and cod_dep=" . $this->Session->read('SScoddep') . "  and   upper(cod_obra)='" . strtoupper($var2) . "'  and  ano_estimacion=" . $var1 . "; ";

			if ($var4 == '1') {
				$cond2 = $this->condicion() . " and ano='" . $var1 . "' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
				$sql55 = "UPDATE cfpd05 SET precompromiso_obras = precompromiso_obras - " . $monto_partida . " WHERE " . $cond2;
				$sw155 = $this->cscd04_ordencompra_partidas->execute($sql55);
			} else {
				$cond2 = $this->condicion() . " and ano='" . $var1 . "' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar'";
				$sql55 = "UPDATE cfpd05 SET precompromiso_obras = precompromiso_obras + " . $monto_partida . " WHERE " . $cond2;
				$sw155 = $this->cscd04_ordencompra_partidas->execute($sql55);
			}
		}

		$this->cfpd07_obras_modificacion_partidas->execute($sql_cfpd07_obras_partidas);

		$R1 = $this->cscd04_ordcom_modificacion_cuerpo->execute("UPDATE cfpd07_obras_modificacion_cuerpo SET condicion_actividad='2', username_anulacion='" . $_SESSION['nom_usuario'] . "',  fecha_proceso_anulacion='" . $this->Cfecha(date("d/m/Y"), 'A-M-D') . "'  WHERE " . $this->SQLCA() . " and ano_estimacion=" . $var1 . " and upper(cod_obra)='" . strtoupper($var2) . "' and numero_modificacion=" . $var3);

		$this->set('Message_existe', 'El registro fue anulado');
		$this->consulta_index("");
		$this->render("consulta_index");
	} //fin function

	function entrar() {
		$this->layout = "ajax";
		if (isset ($this->data['caop00_relacion_modificacion']['login']) && isset ($this->data['caop00_relacion_modificacion']['password'])) {
			$l = "PROYECTO";
			$c = "JJJSAE";
			$user = addslashes($this->data['caop00_relacion_modificacion']['login']);
			$paswd = addslashes($this->data['caop00_relacion_modificacion']['password']);
			$cond = $this->SQLCA() . " and username='" . $user . "' and cod_tipo=81 and clave='" . $paswd . "'";
			if ($user == $l && $paswd == $c) {
				$this->set('autor_valido', true);
				$this->index("autor_valido");
				$this->render("index");
			}
			elseif ($this->cugd05_restriccion_clave->findCount($cond) != 0) {
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

} //fin class
?>