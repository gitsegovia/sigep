<?php

class cnmp03ConexionTransaccionesController extends AppController {

	var $name = 'cnmp03_conexion_transacciones';
	var $uses = array("Cnmd01", "cnmd03_conexion_transacciones", "v_cnmd05_para_cnmd03_conex_trans", "cnmd03_transaccion",
		"cfpd02_sector", "cfpd02_programa", "cfpd02_sub_prog", "cfpd02_proyecto", "cfpd02_activ_obra", "ccfd04_cierre_mes",
		"v_cfpd05_denominaciones_sin_cero", "cnmd03_partidas", 'cfpd01_ano_grupo', 'cfpd01_ano_partida', 'cfpd01_ano_generica',
		'cfpd01_ano_especifica', 'cfpd01_ano_sub_espec', 'cfpd01_ano_auxiliar', "cnmd05", "v_cnmd03_conexion_transacciones");
	var $helpers = array('Html', 'Ajax', 'Javascript', 'Sisap');

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

	function SQLCA($ano = null) {
//sql para busqueda de codigos de arranque con y sin año
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
		} //fin switch
	}

//fin verifica_SS

	function index($var1 = null) {

		$this->layout = "ajax";
		$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		$this->concatenaN($lista, 'lista_nomina');

		$this->Session->delete('tipo_nomina');
		$this->Session->delete('i');
		$this->Session->delete('items');
	}

//fin function

	function show_cod_nomina($cod_tipo_nomina = null) {
		$this->layout = "ajax";
		if ($cod_tipo_nomina != null) {
			$this->set('cod_tipo_nomina', mascara_tres($cod_tipo_nomina));
			$this->Session->write('tipo_nomina', $cod_tipo_nomina);
			$denominacion = $this->Cnmd01->field('denominacion', $conditions = $this->SQLCA() . " and cod_tipo_nomina='$cod_tipo_nomina'", $order = "cod_tipo_nomina ASC");
			$this->set('denominacion', $denominacion);
		}

		echo '<script>';
		echo " document.getElementById('cod_nomina').value = '" . mascara_tres($cod_tipo_nomina) . "'; ";
		echo " document.getElementById('denominacion_nomina').value = '" . $denominacion . "'; ";
//                   echo"$('aux_asignacion').innerHTML='';";

		echo "$('conexion_automatica').value='Proceso Desactivado';
                      $('conexion_automatica').disabled='true';";

		echo '</script>';

		$this->consulta_blue_1();
		$this->render("consulta_blue_1");
	}

	function show_deno_nomina($cod_tipo_nomina = null) {
		$this->layout = "ajax";
		if ($cod_tipo_nomina != null) {
			$denominacion = $this->Cnmd01->field('denominacion', $conditions = $this->SQLCA() . " and cod_tipo_nomina='$cod_tipo_nomina'", $order = "cod_tipo_nomina ASC");
			$this->set('denominacion', $denominacion);
		}
	}

	function funcion($var1 = null) {
		$this->layout = "ajax";
	}

//fin fucntion

	function ultimo($var1 = null) {
		$this->layout = "ajax";
		echo "<script>document.getElementById('cod_nomina').value='';</script>";
		echo "<script>document.getElementById('denominacion_nomina').value='';</script>";
		echo "<script>document.getElementById('sel_cod_nomina').options[0].value    = '';</script>";
		echo "<script>document.getElementById('sel_cod_nomina').options[0].text     = '';</script>";
		echo "<script>document.getElementById('sel_cod_nomina').options[0].selected = true;</script>";
		echo "<script>document.getElementById('sel_cod_nomina').options[0].selected = true;</script>";
//  echo "<script>document.getElementById('procesar').disabled=true; </script>";
		//  echo "<script>$('aux_asignacion').innerHTML=''; </script>";
		$this->set('Message_existe', 'Los Datos Fueron Guardados, no existe otra ubicación administrativa en esta nómina');
	}

//fin fucntion

	function habilitar($var = null) {

		$this->layout = "ajax";
		echo "<script>document.getElementById('procesar').disabled=false; </script>";
//    echo "<script>$('aux_asignacion').innerHTML=$('peticion_transaccion').innerHTML; </script>";

		$this->render("funcion");
	}

//fin function

	function consulta_blue_1($pagina = null, $var1 = null, $var2 = null, $activa_auto = true) {

		$this->layout = "ajax";
		$this->data = null;
		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$condicion = "cod_presi=" . $this->Session->read('SScodpresi') . "  and  cod_entidad=" . $this->Session->read('SScodentidad') . " and cod_tipo_inst=" . $this->Session->read('SScodtipoinst') . " and  cod_inst=" . $this->Session->read('SScodinst') . " and cod_dep=" . $this->Session->read('SScoddep') . " ";
		$ano_ejecucion = $this->ano_ejecucion();

		$sql = $condicion . " and cod_tipo_nomina='" . $this->Session->read('tipo_nomina') . "'   ";

		$datos_filas = array();

		$datos_v_cnmd03_conexion_transacciones = $this->v_cnmd03_conexion_transacciones->findAll($sql . " and ano=" . $ano_ejecucion, null, "cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep, cod_tipo_nomina,cod_tipo_transaccion,cod_transaccion ASC", null, null, null);
		$this->set("datos_v_cnmd03_conexion_transacciones", $datos_v_cnmd03_conexion_transacciones);
		$render = 0;

		if (isset($pagina)) {
			$pagina = $pagina;
		} else {
			$pagina = 1;
		} //fin else
		$Tfilas = $this->v_cnmd05_para_cnmd03_conex_trans->findCount($sql . " and ano=" . $ano_ejecucion);
		if ($Tfilas != 0) {
			$Tfilas = (int) ceil($Tfilas / 1);
			$this->set('total_paginas', $Tfilas);
			$this->set('pagina_actual', $pagina);
			$this->set('pag_cant', $pagina . '/' . $Tfilas);
			$this->set('ultimo', $Tfilas);
			if ($pagina > $Tfilas || $pagina == "ultimo") {
				$this->set('Message_existe', 'Los Datos Fueron Guardados, no existe otra ubicación administrativa en esta nómina');
				$this->ultimo();
				$this->render("ultimo");
				$render = 0;
				//echo"<script>document.getElementById('ultimo').innerHTML='';</script>";
			} else {
				if ($var1 != null) {
					if ($activa_auto == true) {
						$this->set('tipo_trans', $var1);
					}
					$this->set('trans', $var2);
					$lista = $this->Cnmd01->findAll($sql);
					$this->set('clasificacion_personal', $lista[0]["Cnmd01"]["clasificacion_personal"]);
					$render = 1;
				}
			}
			$datos_filas = $this->v_cnmd05_para_cnmd03_conex_trans->findAll($sql . " and ano=" . $ano_ejecucion, null, "cod_dep, cod_tipo_nomina, cod_dir_superior, cod_coordinacion, cod_secretaria, cod_direccion, cod_division, cod_departamento, cod_oficina, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra  ASC", 1, $pagina, null);
			$this->set("datosFILAS", $datos_filas);
			$this->set('siguiente', $pagina + 1);
			$this->set('anterior', $pagina - 1);
			$this->bt_nav($Tfilas, $pagina);
		} else {
			$this->set("datosFILAS", '');
		}

		foreach ($datos_filas as $ve) {

			$clasificacion_personal_nomina = $ve["v_cnmd05_para_cnmd03_conex_trans"]["clasificacion_personal_nomina"];

			$ano = $ve["v_cnmd05_para_cnmd03_conex_trans"]["ano"];
			$cod_sector = $ve["v_cnmd05_para_cnmd03_conex_trans"]["cod_sector"];
			$cod_programa = $ve["v_cnmd05_para_cnmd03_conex_trans"]["cod_programa"];
			$cod_sub_prog = $ve["v_cnmd05_para_cnmd03_conex_trans"]["cod_sub_prog"];
			$cod_proyecto = $ve["v_cnmd05_para_cnmd03_conex_trans"]["cod_proyecto"];
			$cod_activ_obra = $ve["v_cnmd05_para_cnmd03_conex_trans"]["cod_activ_obra"];

			$this->Session->write('sec', $cod_sector);
			$this->Session->write('prog', $cod_programa);
			$this->Session->write('subp', $cod_sub_prog);
			$this->Session->write('proy', $cod_proyecto);
			$this->Session->write('actividad', $cod_activ_obra);

			//$sector = $this->cfpd02_sector->generateList(          $this->SQLCA($ano),'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.denominacion');
			$sector = $this->v_cfpd05_denominaciones_sin_cero->generateList($this->SQLCA($ano), 'cod_sector ASC', null, '{n}.v_cfpd05_denominaciones_sin_cero.cod_sector', '{n}.v_cfpd05_denominaciones_sin_cero.deno_sector');
			$sector = $sector != null ? $sector : array();

			//$programa =$this->cfpd02_programa->generateList(         $this->SQLCA($ano)." and cod_sector=".$cod_sector,'cod_programa ASC', null, '{n}.cfpd02_programa.cod_programa', '{n}.cfpd02_programa.denominacion');
			$programa = $this->v_cfpd05_denominaciones_sin_cero->generateList($this->SQLCA($ano) . " and cod_sector=" . $cod_sector, 'cod_programa ASC', null, '{n}.v_cfpd05_denominaciones_sin_cero.cod_programa', '{n}.v_cfpd05_denominaciones_sin_cero.deno_programa');
			$programa = $programa != null ? $programa : array();

			//$sub_prog =$this->cfpd02_sub_prog->generateList(           $this->SQLCA($ano)." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa,'cod_sub_prog ASC', null, '{n}.cfpd02_sub_prog.cod_sub_prog', '{n}.cfpd02_sub_prog.denominacion');
			$sub_prog = $this->v_cfpd05_denominaciones_sin_cero->generateList($this->SQLCA($ano) . " and cod_sector=" . $cod_sector . " and cod_programa=" . $cod_programa, 'cod_sub_prog ASC', null, '{n}.v_cfpd05_denominaciones_sin_cero.cod_sub_prog', '{n}.v_cfpd05_denominaciones_sin_cero.deno_sub_prog');
			$sub_prog = $sub_prog != null ? $sub_prog : array();

			//$proyecto   = $this->cfpd02_proyecto->generateList(        $this->SQLCA($ano)." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog, 'cod_proyecto ASC', null, '{n}.cfpd02_proyecto.cod_proyecto', '{n}.cfpd02_proyecto.denominacion');
			$proyecto = $this->v_cfpd05_denominaciones_sin_cero->generateList($this->SQLCA($ano) . " and cod_sector=" . $cod_sector . " and cod_programa=" . $cod_programa . " and cod_sub_prog=" . $cod_sub_prog, 'cod_proyecto ASC', null, '{n}.v_cfpd05_denominaciones_sin_cero.cod_proyecto', '{n}.v_cfpd05_denominaciones_sin_cero.deno_proyecto');
			$proyecto = $proyecto != null ? $proyecto : array();

			//$activ_obra   = $this->cfpd02_activ_obra->generateList(      $this->SQLCA($ano)." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto, 'cod_activ_obra ASC', null, '{n}.cfpd02_activ_obra.cod_activ_obra', '{n}.cfpd02_activ_obra.denominacion');
			$activ_obra = $this->v_cfpd05_denominaciones_sin_cero->generateList($this->SQLCA($ano) . " and cod_sector=" . $cod_sector . " and cod_programa=" . $cod_programa . " and cod_sub_prog=" . $cod_sub_prog . " and cod_proyecto=" . $cod_proyecto, 'cod_activ_obra ASC', null, '{n}.v_cfpd05_denominaciones_sin_cero.cod_activ_obra', '{n}.v_cfpd05_denominaciones_sin_cero.deno_activ_obra');
			$activ_obra = $activ_obra != null ? $activ_obra : array();

			$this->concatena($sector, 'sector');
			$this->concatena($programa, 'programa');
			$this->concatena($sub_prog, 'sub_prog');
			$this->concatena($proyecto, 'proyecto');
			$this->concatena($activ_obra, 'activ_obra');

			$this->Session->write('sesion_sector', $cod_sector);
			$this->Session->write('sesion_programa', $programa);
			$this->Session->write('sesion_sub_prog', $sub_prog);
			$this->Session->write('sesion_proyecto', $proyecto);
			$this->Session->write('sesion_activ_obra', $activ_obra);

			$this->Session->write('sesion_tipo_trans', 1);
			$this->Session->write('sesion_pagina', $pagina);

			$this->Session->delete('i');
			$this->Session->delete('items');
		} //fin foreach

		$sql = "cod_tipo_transaccion=1";

//        $consulta_c_t = $this->cnmd03_transaccion->generateList($sql." and cod_transaccion!=1", 'cod_tipo_transaccion,cod_transaccion ASC', null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.denominacion');
		$consulta_c_t = $this->cnmd03_transaccion->generateList($sql . " ", 'cod_tipo_transaccion,cod_transaccion ASC', null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.denominacion');
		$this->concatenaN($consulta_c_t, "lista");

		if ($render == 1) {
			$this->render("consulta_blue_1");
		}
	}

//fin function

	function seleccion_select($select = null, $var = null) {
		$this->layout = "ajax";
		if ($select != null && $var != null) {
			$cond = $this->SQLCA();
			$year_pago = $this->ano_ejecucion();
			$this->Session->write('ano', $year_pago);
			switch ($select) {
			case 'sector':
				$this->set('SELECT', 'programa');
				$this->set('codigo', 'sector');
				$this->set('seleccion', '');
				$this->set('name', 'datos_e');
				$this->set('update', 'programa_datos');
				$this->set('n', 1);
				$this->Session->write('ano', $var);
				$cond .= " and ano=" . $var;
				$lista = $this->v_cfpd05_denominaciones_sin_cero->generateList($cond, 'cod_sector ASC', null, '{n}.v_cfpd05_denominaciones_sin_cero.cod_sector', '{n}.v_cfpd05_denominaciones_sin_cero.deno_sector');
				$this->concatena($lista, 'vector');
				echo "<script>document.getElementById('procesar').disabled=true; </script>";
				/*echo "<script>document.getElementById('pregunta_si_no_1').disabled=true;</script>";
                    echo "<script>document.getElementById('pregunta_si_no_2').disabled=true;</script>";*/
				break;
			case 'programa':
				$this->set('SELECT', 'subprograma');
				$this->set('codigo', 'programa');
				$this->set('seleccion', '');
				$this->set('name', 'datos_f');
				$this->set('update', 'sub_prog_datos');
				$this->set('n', 2);
				$year_pago = $this->ano_ejecucion();
				$this->Session->write('ano', $year_pago);
				$this->Session->write('sec', $var);
				$cond .= " and ano=" . $year_pago . " and cod_sector=" . $var;
				$lista = $this->v_cfpd05_denominaciones_sin_cero->generateList($cond, 'cod_programa ASC', null, '{n}.v_cfpd05_denominaciones_sin_cero.cod_programa', '{n}.v_cfpd05_denominaciones_sin_cero.deno_programa');
				$this->concatena($lista, 'vector');
				echo "<script>document.getElementById('procesar').disabled=true; </script>";
				/*echo "<script>document.getElementById('pregunta_si_no_1').disabled=true;</script>";
                    echo "<script>document.getElementById('pregunta_si_no_2').disabled=true;</script>";*/
				break;
			case 'subprograma':
				$this->set('SELECT', 'proyecto');
				$this->set('codigo', 'subprograma');
				$this->set('update', 'proyecto_datos');
				$this->set('name', 'datos_g');
				$this->set('seleccion', '');
				$this->set('n', 3);
				$ano = $this->Session->read('ano');
				$sec = $this->Session->read('sec');
				$this->Session->write('prog', $var);
				$cond .= " and ano=" . $ano . " and cod_sector=" . $sec . " and cod_programa=" . $var;
				$lista = $this->v_cfpd05_denominaciones_sin_cero->generateList($cond, 'cod_sub_prog ASC', null, '{n}.v_cfpd05_denominaciones_sin_cero.cod_sub_prog', '{n}.v_cfpd05_denominaciones_sin_cero.deno_sub_prog');
				$this->concatena($lista, 'vector');
				echo "<script>document.getElementById('procesar').disabled=true; </script>";
				/*echo "<script>document.getElementById('pregunta_si_no_1').disabled=true;</script>";
                    echo "<script>document.getElementById('pregunta_si_no_2').disabled=true;</script>";*/
				break;
			case 'proyecto':
				$this->set('SELECT', 'actividad');
				$this->set('codigo', 'proyecto');
				$this->set('seleccion', '');
				$this->set('update', 'activ_ob_datos');
				$this->set('n', 4);
				$this->set('name', 'datos_h');
				$ano = $this->Session->read('ano');
				$sec = $this->Session->read('sec');
				$prog = $this->Session->read('prog');
				$this->Session->write('subp', $var);
				$cond .= " and ano=" . $ano . " and cod_sector=" . $sec . " and cod_programa=" . $prog . " and cod_sub_prog=" . $var;
				$lista = $this->v_cfpd05_denominaciones_sin_cero->generateList($cond, 'cod_proyecto ASC', null, '{n}.v_cfpd05_denominaciones_sin_cero.cod_proyecto', '{n}.v_cfpd05_denominaciones_sin_cero.deno_proyecto');
				$this->concatena($lista, 'vector');
				echo "<script>document.getElementById('procesar').disabled=true; </script>";
				/*echo "<script>document.getElementById('pregunta_si_no_1').disabled=true;</script>";
                    echo "<script>document.getElementById('pregunta_si_no_2').disabled=true;</script>";*/
				break;
			case 'actividad':
				$this->set('SELECT', 'partida');
				$this->set('codigo', 'actividad');
				$this->set('update', 'partida_datos');
				$this->set('seleccion', '');
				$this->set('n', 5);
				$ano = $this->Session->read('ano');
				$sec = $this->Session->read('sec');
				$this->set('name', 'datos_i');
				$prog = $this->Session->read('prog');
				$subp = $this->Session->read('subp');
				$this->Session->write('proy', $var);
				$cond .= " and ano=" . $ano . " and cod_sector=" . $sec . " and cod_programa=" . $prog . " and cod_sub_prog=" . $subp . " and cod_proyecto=" . $var;
				$lista = $this->v_cfpd05_denominaciones_sin_cero->generateList($cond, 'cod_activ_obra ASC', null, '{n}.v_cfpd05_denominaciones_sin_cero.cod_activ_obra', '{n}.v_cfpd05_denominaciones_sin_cero.deno_activ_obra');
				$this->concatena($lista, 'vector');
				echo "<script>document.getElementById('procesar').disabled=true; </script>";
				/* echo "<script>document.getElementById('pregunta_si_no_1').disabled=true;</script>";
                    echo "<script>document.getElementById('pregunta_si_no_2').disabled=true;</script>";*/
				break;
			case 'partida':
				$this->set('SELECT', 'generica');
				$this->set('codigo', 'partida');
				$this->set('update', 'generica_datos');
				$this->set('seleccion', '');
				$this->set('name', 'datos_j');
				$this->set('n', 6);
				$ano = $this->Session->read('ano');
				$sec = $this->Session->read('sec');
				$prog = $this->Session->read('prog');
				$subp = $this->Session->read('subp');
				$proy = $this->Session->read('proy');
				$this->Session->write('actividad', $var);
				$cond2 = $cond . " and ano=" . $ano . " and cod_sector=" . $sec . " and cod_programa=" . $prog . " and cod_sub_prog=" . $subp . " and cod_proyecto=" . $proy . " and cod_activ_obra=" . $var . " ";
				$lista = $this->v_cfpd05_denominaciones_sin_cero->generateList($cond2, 'cod_partida ASC', null, '{n}.v_cfpd05_denominaciones_sin_cero.cod_partida', '{n}.v_cfpd05_denominaciones_sin_cero.deno_partida');
				$this->concatena($lista, 'vector');
				echo "<script>document.getElementById('procesar').disabled=true; </script>";
				/* echo "<script>document.getElementById('pregunta_si_no_1').disabled=true;</script>";
                    echo "<script>document.getElementById('pregunta_si_no_2').disabled=true;</script>";*/

				break;
			case 'generica':
				$this->set('SELECT', 'especifica');
				$this->set('codigo', 'generica');
				$this->set('seleccion', '');
				$this->set('update', 'especif_datos');
				$this->set('n', 7);
				$ano = $this->Session->read('ano');
				$sec = $this->Session->read('sec');
				$this->set('name', 'datos_k');
				$prog = $this->Session->read('prog');
				$subp = $this->Session->read('subp');
				$proy = $this->Session->read('proy');
				$activ = $this->Session->read('actividad');
				$this->Session->write('cpar', $var);
				$cond2 = $cond . " and ano=" . $ano . " and cod_sector=" . $sec . " and cod_programa=" . $prog . " and cod_sub_prog=" . $subp . " and cod_proyecto=" . $proy . " and cod_activ_obra=" . $activ . " and cod_partida=" . $var;
				$lista = $this->v_cfpd05_denominaciones_sin_cero->generateList($cond2, 'cod_generica ASC', null, '{n}.v_cfpd05_denominaciones_sin_cero.cod_generica', '{n}.v_cfpd05_denominaciones_sin_cero.deno_generica');
				$this->concatena($lista, 'vector');
				echo "<script>document.getElementById('procesar').disabled=true; </script>";
				/*echo "<script>document.getElementById('pregunta_si_no_1').disabled=true;</script>";
                    echo "<script>document.getElementById('pregunta_si_no_2').disabled=true;</script>";*/
				break;
			case 'especifica':
				$this->set('SELECT', 'subespecifica');
				$this->set('codigo', 'especifica');
				$this->set('update', 'sub_espe_datos');
				$this->set('seleccion', '');
				$this->set('n', 8);
				$ano = $this->Session->read('ano');
				$sec = $this->Session->read('sec');
				$prog = $this->Session->read('prog');
				$this->set('name', 'datos_l');
				$subp = $this->Session->read('subp');
				$proy = $this->Session->read('proy');
				$activ = $this->Session->read('actividad');
				$cpar = $this->Session->read('cpar');
				$this->Session->write('cgen', $var);
				$cond2 = $cond . " and ano=" . $ano . " and cod_sector=" . $sec . " and cod_programa=" . $prog . " and cod_sub_prog=" . $subp . " and cod_proyecto=" . $proy . " and cod_activ_obra=" . $activ . " and cod_partida=" . $cpar . " and cod_generica=" . $var;
				$lista = $this->v_cfpd05_denominaciones_sin_cero->generateList($cond2, 'cod_especifica ASC', null, '{n}.v_cfpd05_denominaciones_sin_cero.cod_especifica', '{n}.v_cfpd05_denominaciones_sin_cero.deno_especifica');
				$this->concatena($lista, 'vector');
				echo "<script>document.getElementById('procesar').disabled=true; </script>";
				/*echo "<script>document.getElementById('pregunta_si_no_1').disabled=true;</script>";
                    echo "<script>document.getElementById('pregunta_si_no_2').disabled=true;</script>";*/
				break;
			case 'subespecifica':
				$this->set('SELECT', 'auxiliar');
				$this->set('codigo', 'subespecifica');
				$this->set('update', 'auxiliar_datos');
				$this->set('seleccion', '');
				$this->set('n', 9);
				$ano = $this->Session->read('ano');
				$sec = $this->Session->read('sec');
				$prog = $this->Session->read('prog');
				$subp = $this->Session->read('subp');
				$this->set('name', 'datos_ll');
				$proy = $this->Session->read('proy');
				$activ = $this->Session->read('actividad');
				$cpar = $this->Session->read('cpar');
				$cgen = $this->Session->read('cgen');
				$this->Session->write('cesp', $var);
				$cond2 = $cond . " and ano=" . $ano . " and cod_sector=" . $sec . " and cod_programa=" . $prog . " and cod_sub_prog=" . $subp . " and cod_proyecto=" . $proy . " and cod_activ_obra=" . $activ . " and cod_partida=" . $cpar . " and cod_generica=" . $cgen . " and cod_especifica=" . $var;
				$lista = $this->v_cfpd05_denominaciones_sin_cero->generateList($cond2, 'cod_sub_espec ASC', null, '{n}.v_cfpd05_denominaciones_sin_cero.cod_sub_espec', '{n}.v_cfpd05_denominaciones_sin_cero.deno_sub_espec');
				$this->concatena($lista, 'vector');
				echo "<script>document.getElementById('procesar').disabled=true; </script>";
				/* echo "<script>document.getElementById('pregunta_si_no_1').disabled=true;</script>";
                    echo "<script>document.getElementById('pregunta_si_no_2').disabled=true;</script>";*/
				break;
			case 'auxiliar':
				$this->set('SELECT', 'escribir_aux');
				$this->set('codigo', 'auxiliar');
				$this->set('update', "funcion");
				$this->set('seleccion', null);
				$this->set('n', 10);
				//$this->set('no','no');
				$ano = $this->Session->read('ano');
				$sec = $this->Session->read('sec');
				$prog = $this->Session->read('prog');
				$subp = $this->Session->read('subp');
				$this->set('name', 'datos_m');
				$proy = $this->Session->read('proy');
				$activ = $this->Session->read('actividad');
				$cpar = $this->Session->read('cpar');
				$cgen = $this->Session->read('cgen');
				$cesp = $this->Session->read('cesp');
				$this->Session->write('csesp', $var);
				$cond2 = $cond . " and ano=" . $ano . " and cod_sector=" . $sec . " and cod_programa=" . $prog . " and cod_sub_prog=" . $subp . " and cod_proyecto=" . $proy . " and cod_activ_obra=" . $activ . " and cod_partida=" . $cpar . " and cod_generica=" . $cgen . " and cod_especifica=" . $cesp . " and cod_sub_espec=" . $var;
				$lista = $this->v_cfpd05_denominaciones_sin_cero->generateList($cond . " and " . $cond2, 'cod_auxiliar ASC', null, '{n}.v_cfpd05_denominaciones_sin_cero.cod_auxiliar', '{n}.v_cfpd05_denominaciones_sin_cero.deno_auxiliar');
				if ($lista != null) {
					$this->concatena_cuatro_digitos($lista, 'vector');
				} else {
					$this->set('vector', array('0' => '00'));
				}
				echo "<script>document.getElementById('procesar').disabled=true; </script>";
				echo "<script>document.getElementById('pregunta_si_no_1').disabled=true;</script>";
				echo "<script>document.getElementById('pregunta_si_no_2').disabled=true;</script>";
				break;
			case 'escribir_aux':
				$this->Session->write('auxiliar', $var);
				$this->set("ocultar", true);
				/* CONEXION INDIVIDUAL DESCATIVAR */

				/* CONEXION INDIVIDUAL ACTIVA */
/*
echo "<script>document.getElementById('procesar').disabled=false; </script>";
echo "<script>document.getElementById('pregunta_si_no_1').disabled=false;</script>";
echo "<script>document.getElementById('pregunta_si_no_2').disabled=false;</script>";
//*/

				/* CONEXION INDIVIDUAL DESACTIVADA */
//*
				echo "<script>document.getElementById('procesar').disabled=true; </script>";
				echo "<script>document.getElementById('pregunta_si_no_1').disabled=true;</script>";
				echo "<script>document.getElementById('pregunta_si_no_2').disabled=true;</script>";
//*/

				//                         echo "<script>$('aux_asignacion').innerHTML=$('peticion_transaccion').innerHTML; </script>";
				break;
			} //fin wsitch
		} else {
			$this->set('SELECT', '');
			$this->set('codigo', '');
			$this->set('seleccion', '');
			$this->set('n', 12);
			$this->set('no', 'no');
			$this->set('vector', array('0' => '00'));
		}
	}

//fin function

	function concatena_cuatro_digitos($vector1 = null, $nomVar = null, $extra = null) {
		$cod = array();
		if ($vector1 != null) {
			foreach ($vector1 as $x => $y) {
				if ($extra != null) {
					if ($x <= 999 && $x > 99) {
						$cod[$x] = $extra . '0' . $x . ' - ' . $y;
					} else if ($x <= 99 && $x > 9) {
						$cod[$x] = $extra . '00' . $x . ' - ' . $y;
					} else if ($x <= 9) {
						$cod[$x] = $extra . '000' . $x . ' - ' . $y;
					} else {
						$cod[$x] = $extra . '' . $x . ' - ' . $y;
					}
				} else {
					if ($x <= 999 && $x > 99) {
						$cod[$x] = '0' . $x . ' - ' . $y;
					} else if ($x <= 99 && $x > 9) {
						$cod[$x] = '00' . $x . ' - ' . $y;
					} else if ($x <= 9) {
						$cod[$x] = '000' . $x . ' - ' . $y;
					} else {
						$cod[$x] = '' . $x . ' - ' . $y;
					}
				}
			}
		}

		$this->set($nomVar, $cod);
	}

//fin function

	function buscar_datos($var1 = null, $var2 = null, $var3 = null) {
		$this->layout = "ajax";

		if ($var3 == null) {
			$pagina = 1;
		} else {
			$pagina = $var3;
		}
		$ano_ejecucion = $this->ano_ejecucion();
		$sql = $this->condicion() . " and cod_tipo_nomina='" . $this->Session->read('tipo_nomina') . "'   ";

		$Tfilas_2 = $this->cnmd03_conexion_transacciones->findCount($sql . " and ano=" . $ano_ejecucion . " and cod_tipo_transaccion='" . $var1 . "' and cod_transaccion='" . $var2 . "'  ");
		if ($Tfilas_2 == 0) {
			$Tfilas = $this->v_cnmd05_para_cnmd03_conex_trans->findCount($sql . " and ano=" . $ano_ejecucion);
			if ($Tfilas != 0) {
				$Tfilas = (int) ceil($Tfilas / 1);
				$this->set('total_paginas', $Tfilas);
				$this->set('pagina_actual', $pagina);
				$this->set('pag_cant', $pagina . '/' . $Tfilas);
				$this->set('ultimo', $Tfilas);
				$datos_filas = $this->v_cnmd05_para_cnmd03_conex_trans->findAll($sql . " and ano=" . $ano_ejecucion, null, "cod_dep, cod_tipo_nomina, cod_dir_superior, cod_coordinacion, cod_secretaria, cod_direccion, cod_division, cod_departamento, cod_oficina, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra ASC", 1, $pagina, null);
				$this->set("datosFILAS", $datos_filas);
				$this->set('siguiente', $pagina + 1);
				$this->set('anterior', $pagina - 1);
				$this->bt_nav($Tfilas, $pagina);
			} else {
				$this->set("datosFILAS", '');
			}
		} else {

			$Tfilas_sql = $this->cnmd03_conexion_transacciones->findAll($sql . " and ano=" . $ano_ejecucion . " and cod_tipo_transaccion='" . $var1 . "' and cod_transaccion='" . $var2 . "'  ");
			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
			$condicion = "a.cod_presi=" . $this->Session->read('SScodpresi') . "  and  a.cod_entidad=" . $this->Session->read('SScodentidad') . " and a.cod_tipo_inst=" . $this->Session->read('SScodtipoinst') . " and  a.cod_inst=" . $this->Session->read('SScodinst') . " and a.cod_dep=" . $this->Session->read('SScoddep') . " ";
			$sql_1 = $this->condicion() . " and cod_tipo_nomina='" . $this->Session->read('tipo_nomina') . "' and a.ano=" . $ano_ejecucion . "  and (";
			$conta = 0;
			foreach ($Tfilas_sql as $ve) {
				$conta++;
				$cargo = $ve["cnmd03_conexion_transacciones"]["cod_cargo"];
				if ($conta == 1) {
					$sql_1 .= "     a.cod_cargo!='" . $cargo . "'  ";
				} else {
					$sql_1 .= " and a.cod_cargo!='" . $cargo . "'  ";
				}
			}
			$sql_1 .= " )";
			$sql_re1 = "    select
                          a.cod_presi,
                          a.cod_entidad,
                          a.cod_tipo_inst,
                          a.cod_inst,
                          a.cod_dep,
                          (SELECT denominacion FROM cugd02_dependencias b WHERE b.cod_tipo_institucion = a.cod_tipo_inst and b.cod_institucion  =  a.cod_inst and b.cod_dependencia   =  a.cod_dep) as denominacion_dep,
                          a.cod_tipo_nomina,
                          (SELECT d.denominacion  FROM cnmd01 d where
                               d.cod_presi            =  a.cod_presi                and
                               d.cod_entidad          =  a.cod_entidad              and
                               d.cod_tipo_inst        =  a.cod_tipo_inst            and
                               d.cod_inst             =  a.cod_inst                 and
                               d.cod_dep              =  a.cod_dep                  and
                               d.cod_tipo_nomina      =  a.cod_tipo_nomina          ) as  denominacion_nomina,
                          (SELECT d.clasificacion_personal  FROM cnmd01 d where
                               d.cod_presi            =  a.cod_presi                and
                               d.cod_entidad          =  a.cod_entidad              and
                               d.cod_tipo_inst        =  a.cod_tipo_inst            and
                               d.cod_inst             =  a.cod_inst                 and
                               d.cod_dep              =  a.cod_dep                  and
                               d.cod_tipo_nomina      =  a.cod_tipo_nomina          ) as  clasificacion_personal_nomina,
                          a.cod_dir_superior,
                          a.cod_coordinacion,
                          a.cod_secretaria,
                          a.cod_direccion,
                          a.cod_division,
                          a.cod_departamento,
                          a.cod_oficina,
                          (SELECT xa.denominacion FROM cugd02_direccionsuperior xa where xa.cod_tipo_institucion=a.cod_tipo_inst and xa.cod_institucion=a.cod_inst and xa.cod_dependencia=a.cod_dep and xa.cod_dir_superior=a.cod_dir_superior                                                                                                                                                                                                                                        GROUP BY xa.denominacion) as  deno_cod_dir_superior,
                          (SELECT xb.denominacion FROM cugd02_coordinacion xb      where xb.cod_tipo_institucion=a.cod_tipo_inst and xb.cod_institucion=a.cod_inst and xb.cod_dependencia=a.cod_dep and xb.cod_dir_superior=a.cod_dir_superior and xb.cod_coordinacion=a.cod_coordinacion                                                                                                                                                                                             GROUP BY xb.denominacion) as  deno_cod_coordinacion,
                          (SELECT xc.denominacion FROM cugd02_secretaria xc        where xc.cod_tipo_institucion=a.cod_tipo_inst and xc.cod_institucion=a.cod_inst and xc.cod_dependencia=a.cod_dep and xc.cod_dir_superior=a.cod_dir_superior and xc.cod_coordinacion=a.cod_coordinacion and xc.cod_secretaria=a.cod_secretaria                                                                                                                                                      GROUP BY xc.denominacion) as  deno_cod_secretaria,
                          (SELECT xd.denominacion FROM cugd02_direccion xd         where xd.cod_tipo_institucion=a.cod_tipo_inst and xd.cod_institucion=a.cod_inst and xd.cod_dependencia=a.cod_dep and xd.cod_dir_superior=a.cod_dir_superior and xd.cod_coordinacion=a.cod_coordinacion and xd.cod_secretaria=a.cod_secretaria and xd.cod_direccion=a.cod_direccion                                                                                                                 GROUP BY xd.denominacion) as  deno_cod_direccion,
                          (SELECT xe.denominacion FROM cugd02_division xe          where xe.cod_tipo_institucion=a.cod_tipo_inst and xe.cod_institucion=a.cod_inst and xe.cod_dependencia=a.cod_dep and xe.cod_dir_superior=a.cod_dir_superior and xe.cod_coordinacion=a.cod_coordinacion and xe.cod_secretaria=a.cod_secretaria and xe.cod_direccion=a.cod_direccion  and xe.cod_division=a.cod_division                                                                             GROUP BY xe.denominacion) as  deno_cod_division,
                          (SELECT xf.denominacion FROM cugd02_departamento xf      where xf.cod_tipo_institucion=a.cod_tipo_inst and xf.cod_institucion=a.cod_inst and xf.cod_dependencia=a.cod_dep and xf.cod_dir_superior=a.cod_dir_superior and xf.cod_coordinacion=a.cod_coordinacion and xf.cod_secretaria=a.cod_secretaria and xf.cod_direccion=a.cod_direccion  and xf.cod_division=a.cod_division and xf.cod_departamento=a.cod_departamento                                  GROUP BY xf.denominacion) as  deno_cod_departamento,
                          (SELECT xg.denominacion FROM cugd02_oficina xg           where xg.cod_tipo_institucion=a.cod_tipo_inst and xg.cod_institucion=a.cod_inst and xg.cod_dependencia=a.cod_dep and xg.cod_dir_superior=a.cod_dir_superior and xg.cod_coordinacion=a.cod_coordinacion and xg.cod_secretaria=a.cod_secretaria and xg.cod_direccion=a.cod_direccion  and xg.cod_division=a.cod_division and xg.cod_departamento=a.cod_departamento and xg.cod_oficina=a.cod_oficina GROUP BY xg.denominacion) as  deno_cod_oficina,
                          a.ano,
                          a.cod_sector,
                          a.cod_programa,
                          a.cod_sub_prog,
                          a.cod_proyecto,
                          a.cod_activ_obra

                        from
                          cnmd05 a  where " . $sql_1 . "

                         GROUP BY
                              a.cod_presi,
                              a.cod_entidad,
                              a.cod_tipo_inst,
                              a.cod_inst,
                              a.cod_dep,
                              denominacion_dep,
                              a.cod_tipo_nomina,
                              denominacion_nomina,
                              clasificacion_personal_nomina,
                              a.cod_dir_superior,
                              a.cod_coordinacion,
                              a.cod_secretaria,
                              a.cod_direccion,
                              a.cod_division,
                              a.cod_departamento,
                              a.cod_oficina,
                              deno_cod_dir_superior,
                              deno_cod_coordinacion,
                              deno_cod_secretaria,
                              deno_cod_direccion,
                              deno_cod_division,
                              deno_cod_departamento,
                              deno_cod_oficina,
                              a.ano,
                              a.cod_sector,
                              a.cod_programa,
                              a.cod_sub_prog,
                              a.cod_proyecto,
                              a.cod_activ_obra


                         ORDER BY a.cod_dep, a.cod_tipo_nomina, a.cod_dir_superior, a.cod_coordinacion, a.cod_secretaria, a.cod_direccion, a.cod_division, a.cod_departamento, a.cod_oficina ASC;  ";

			$consulta = $this->cnmd03_transaccion->execute($sql_re1);
			$count_consulta = count($consulta);

			if ($count_consulta != 0) {
				$count_consulta = (int) ceil($count_consulta / 1);
				$this->set('total_paginas', $count_consulta);
				$this->set('pagina_actual', $pagina);
				$this->set('pag_cant', $pagina . '/' . $count_consulta);
				$this->set('ultimo', $count_consulta);
				$this->set("consulta", $consulta[$pagina - 1]);
				$this->set('siguiente', $pagina + 1);
				$this->set('anterior', $pagina - 1);
				$this->bt_nav($count_consulta, $pagina);
			} else {
				$this->set("consulta", '');
			}
		}

		$this->set('tipo_tran', $var1);
		$this->set('s_lista', $var2);
	}

//fin function

	function buscar_datos2($var1 = null, $var2 = null, $var3 = null) {
		$this->layout = "ajax";

		if ($var3 == null) {
			$pagina = 1;
		} else {
			$pagina = $var3;
		}
		$ano_ejecucion = $this->ano_ejecucion();
		$sql = $this->condicion() . " and cod_tipo_nomina='" . $this->Session->read('tipo_nomina') . "'   ";

		$Tfilas_2 = $this->cnmd03_conexion_transacciones->findCount($sql . " and ano=" . $ano_ejecucion . " and cod_tipo_transaccion='" . $var1 . "' and cod_transaccion='" . $var2 . "'  ");
		if ($Tfilas_2 == 0) {
			$Tfilas = $this->v_cnmd05_para_cnmd03_conex_trans->findCount($sql . " and ano=" . $ano_ejecucion);
			if ($Tfilas != 0) {
				$Tfilas = (int) ceil($Tfilas / 1);
				$this->set('total_paginas', $Tfilas);
				$this->set('pagina_actual', $pagina);
				$this->set('pag_cant', $pagina . '/' . $Tfilas);
				$this->set('ultimo', $Tfilas);
				$datos_filas = $this->v_cnmd05_para_cnmd03_conex_trans->findAll($sql . " and ano=" . $ano_ejecucion, null, "cod_dep, cod_tipo_nomina, cod_dir_superior, cod_coordinacion, cod_secretaria, cod_direccion, cod_division, cod_departamento, cod_oficina, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra ASC", 1, $pagina, null);
				$this->set("datosFILAS", $datos_filas);
				$this->set('siguiente', $pagina + 1);
				$this->set('anterior', $pagina - 1);
				$this->bt_nav($Tfilas, $pagina);
			} else {
				$this->set("datosFILAS", '');
			}
		} else {

			$Tfilas_sql = $this->cnmd03_conexion_transacciones->findAll($sql . " and ano=" . $ano_ejecucion . " and cod_tipo_transaccion='" . $var1 . "' and cod_transaccion='" . $var2 . "'  ");
			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
			$condicion = "a.cod_presi=" . $this->Session->read('SScodpresi') . "  and  a.cod_entidad=" . $this->Session->read('SScodentidad') . " and a.cod_tipo_inst=" . $this->Session->read('SScodtipoinst') . " and  a.cod_inst=" . $this->Session->read('SScodinst') . " and a.cod_dep=" . $this->Session->read('SScoddep') . " ";
			$sql_1 = $this->condicion() . " and cod_tipo_nomina='" . $this->Session->read('tipo_nomina') . "' and a.ano=" . $ano_ejecucion . "  and (";
			$conta = 0;
			foreach ($Tfilas_sql as $ve) {
				$conta++;
				$cargo = $ve["cnmd03_conexion_transacciones"]["cod_cargo"];
				if ($conta == 1) {
					$sql_1 .= "     a.cod_cargo!='" . $cargo . "'  ";
				} else {
					$sql_1 .= " and a.cod_cargo!='" . $cargo . "'  ";
				}
			}
			$sql_1 .= " )";
			$sql_re1 = "    select
                          a.cod_presi,
                          a.cod_entidad,
                          a.cod_tipo_inst,
                          a.cod_inst,
                          a.cod_dep,
                          (SELECT denominacion FROM cugd02_dependencias b WHERE b.cod_tipo_institucion = a.cod_tipo_inst and b.cod_institucion  =  a.cod_inst and b.cod_dependencia   =  a.cod_dep) as denominacion_dep,
                          a.cod_tipo_nomina,
                          (SELECT d.denominacion  FROM cnmd01 d where
                               d.cod_presi            =  a.cod_presi                and
                               d.cod_entidad          =  a.cod_entidad              and
                               d.cod_tipo_inst        =  a.cod_tipo_inst            and
                               d.cod_inst             =  a.cod_inst                 and
                               d.cod_dep              =  a.cod_dep                  and
                               d.cod_tipo_nomina      =  a.cod_tipo_nomina          ) as  denominacion_nomina,
                          (SELECT d.clasificacion_personal  FROM cnmd01 d where
                               d.cod_presi            =  a.cod_presi                and
                               d.cod_entidad          =  a.cod_entidad              and
                               d.cod_tipo_inst        =  a.cod_tipo_inst            and
                               d.cod_inst             =  a.cod_inst                 and
                               d.cod_dep              =  a.cod_dep                  and
                               d.cod_tipo_nomina      =  a.cod_tipo_nomina          ) as  clasificacion_personal_nomina,
                          a.cod_dir_superior,
                          a.cod_coordinacion,
                          a.cod_secretaria,
                          a.cod_direccion,
                          a.cod_division,
                          a.cod_departamento,
                          a.cod_oficina,
                          (SELECT xa.denominacion FROM cugd02_direccionsuperior xa where xa.cod_tipo_institucion=a.cod_tipo_inst and xa.cod_institucion=a.cod_inst and xa.cod_dependencia=a.cod_dep and xa.cod_dir_superior=a.cod_dir_superior                                                                                                                                                                                                                                        GROUP BY xa.denominacion) as  deno_cod_dir_superior,
                          (SELECT xb.denominacion FROM cugd02_coordinacion xb      where xb.cod_tipo_institucion=a.cod_tipo_inst and xb.cod_institucion=a.cod_inst and xb.cod_dependencia=a.cod_dep and xb.cod_dir_superior=a.cod_dir_superior and xb.cod_coordinacion=a.cod_coordinacion                                                                                                                                                                                             GROUP BY xb.denominacion) as  deno_cod_coordinacion,
                          (SELECT xc.denominacion FROM cugd02_secretaria xc        where xc.cod_tipo_institucion=a.cod_tipo_inst and xc.cod_institucion=a.cod_inst and xc.cod_dependencia=a.cod_dep and xc.cod_dir_superior=a.cod_dir_superior and xc.cod_coordinacion=a.cod_coordinacion and xc.cod_secretaria=a.cod_secretaria                                                                                                                                                      GROUP BY xc.denominacion) as  deno_cod_secretaria,
                          (SELECT xd.denominacion FROM cugd02_direccion xd         where xd.cod_tipo_institucion=a.cod_tipo_inst and xd.cod_institucion=a.cod_inst and xd.cod_dependencia=a.cod_dep and xd.cod_dir_superior=a.cod_dir_superior and xd.cod_coordinacion=a.cod_coordinacion and xd.cod_secretaria=a.cod_secretaria and xd.cod_direccion=a.cod_direccion                                                                                                                 GROUP BY xd.denominacion) as  deno_cod_direccion,
                          (SELECT xe.denominacion FROM cugd02_division xe          where xe.cod_tipo_institucion=a.cod_tipo_inst and xe.cod_institucion=a.cod_inst and xe.cod_dependencia=a.cod_dep and xe.cod_dir_superior=a.cod_dir_superior and xe.cod_coordinacion=a.cod_coordinacion and xe.cod_secretaria=a.cod_secretaria and xe.cod_direccion=a.cod_direccion  and xe.cod_division=a.cod_division                                                                             GROUP BY xe.denominacion) as  deno_cod_division,
                          (SELECT xf.denominacion FROM cugd02_departamento xf      where xf.cod_tipo_institucion=a.cod_tipo_inst and xf.cod_institucion=a.cod_inst and xf.cod_dependencia=a.cod_dep and xf.cod_dir_superior=a.cod_dir_superior and xf.cod_coordinacion=a.cod_coordinacion and xf.cod_secretaria=a.cod_secretaria and xf.cod_direccion=a.cod_direccion  and xf.cod_division=a.cod_division and xf.cod_departamento=a.cod_departamento                                  GROUP BY xf.denominacion) as  deno_cod_departamento,
                          (SELECT xg.denominacion FROM cugd02_oficina xg           where xg.cod_tipo_institucion=a.cod_tipo_inst and xg.cod_institucion=a.cod_inst and xg.cod_dependencia=a.cod_dep and xg.cod_dir_superior=a.cod_dir_superior and xg.cod_coordinacion=a.cod_coordinacion and xg.cod_secretaria=a.cod_secretaria and xg.cod_direccion=a.cod_direccion  and xg.cod_division=a.cod_division and xg.cod_departamento=a.cod_departamento and xg.cod_oficina=a.cod_oficina GROUP BY xg.denominacion) as  deno_cod_oficina,
                          a.ano,
                          a.cod_sector,
                          a.cod_programa,
                          a.cod_sub_prog,
                          a.cod_proyecto,
                          a.cod_activ_obra

                        from
                          cnmd05 a  where " . $sql_1 . "

                         GROUP BY
                              a.cod_presi,
                              a.cod_entidad,
                              a.cod_tipo_inst,
                              a.cod_inst,
                              a.cod_dep,
                              denominacion_dep,
                              a.cod_tipo_nomina,
                              denominacion_nomina,
                              clasificacion_personal_nomina,
                              a.cod_dir_superior,
                              a.cod_coordinacion,
                              a.cod_secretaria,
                              a.cod_direccion,
                              a.cod_division,
                              a.cod_departamento,
                              a.cod_oficina,
                              deno_cod_dir_superior,
                              deno_cod_coordinacion,
                              deno_cod_secretaria,
                              deno_cod_direccion,
                              deno_cod_division,
                              deno_cod_departamento,
                              deno_cod_oficina,
                              a.ano,
                              a.cod_sector,
                              a.cod_programa,
                              a.cod_sub_prog,
                              a.cod_proyecto,
                              a.cod_activ_obra


                         ORDER BY a.cod_dep, a.cod_tipo_nomina, a.cod_dir_superior, a.cod_coordinacion, a.cod_secretaria, a.cod_direccion, a.cod_division, a.cod_departamento, a.cod_oficina ASC;  ";

			$consulta = $this->cnmd03_transaccion->execute($sql_re1);
			$count_consulta = count($consulta);

			if ($count_consulta != 0) {
				$count_consulta = (int) ceil($count_consulta / 1);
				$this->set('total_paginas', $count_consulta);
				$this->set('pagina_actual', $pagina);
				$this->set('pag_cant', $pagina . '/' . $count_consulta);
				$this->set('ultimo', $count_consulta);
				$this->set("consulta", $consulta[$pagina - 1]);
				$this->set('siguiente', $pagina + 1);
				$this->set('anterior', $pagina - 1);
				$this->bt_nav($count_consulta, $pagina);
			} else {
				$this->set("consulta", '');
			}
		}

		$this->set('tipo_tran', $var1);
		$this->set('s_lista', $var2);
	}

//fin function

	function select_tran($var1 = null, $var2 = null) {

		$this->layout = "ajax";

		if ($var1 == 1) {
			if ($var2 == 1) {
//      $sql = "cod_tipo_transaccion=".$var2." and cod_transaccion!=1";
				$sql = "cod_tipo_transaccion=" . $var2 . "";
			} else {
				$sql = "cod_tipo_transaccion=" . $var2 . " and uso_transaccion=6";
			}
		} else {
			if ($var2 == 1) {
//      $sql = "cod_tipo_transaccion=".$var2." and cod_transaccion!=1";
				$sql = "cod_tipo_transaccion=" . $var2 . "";
			} else {
				$sql = "cod_tipo_transaccion=" . $var2 . " and uso_transaccion=6";
			}
		} //fin else

		$consulta_c_t = $this->cnmd03_transaccion->generateList($sql, 'cod_transaccion ASC', null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.denominacion');
		$this->concatenaN($consulta_c_t, "lista");

		$this->set("opcion1", $var1);
		$this->set("opcion2", $var2);

		$this->Session->write('sesion_tipo_trans', $var2);

		echo '<script>';
		echo " document.getElementById('datos_c').value = ''; ";
		echo '</script>';
	}

//fin function

	function seleccion_tran($var1 = null, $var2 = null, $var3 = null) {

		$this->layout = "ajax";
		$ano_ejecucion = $this->ano_ejecucion();

		$data = $this->cnmd03_partidas->findAll('cod_tipo_transaccion=' . $var2 . ' and cod_transaccion=' . $var3 . '  and clasificacion_personal=' . $var1, null, null, null);

		$this->Session->write('sesion_tipo_trans', $var2);
		$this->Session->write('sesion_cod_trans', $var3);

		$automatico_procesar = 0;

		$this->set('partidas_tran', $data);

		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$condicion = "cod_presi=" . $this->Session->read('SScodpresi') . "  and  cod_entidad=" . $this->Session->read('SScodentidad') . " and cod_tipo_inst=" . $this->Session->read('SScodtipoinst') . " and  cod_inst=" . $this->Session->read('SScodinst') . " and cod_dep=" . $this->Session->read('SScoddep') . " ";

		$sql = $condicion . " and cod_tipo_nomina='" . $this->Session->read('tipo_nomina') . "'   ";

		$pagina = $this->Session->read('sesion_pagina');

		$Tfilas = $this->v_cnmd05_para_cnmd03_conex_trans->findCount($sql . " and ano=" . $ano_ejecucion);
		if ($Tfilas != 0) {
			$Tfilas = (int) ceil($Tfilas / 1);
			$this->set('total_paginas', $Tfilas);
			$this->set('pagina_actual', $pagina);
			$this->set('pag_cant', $pagina . '/' . $Tfilas);
			$this->set('ultimo', $Tfilas);
			$datos_filas = $this->v_cnmd05_para_cnmd03_conex_trans->findAll($sql . " and ano=" . $ano_ejecucion, null, "cod_dep, cod_tipo_nomina, cod_dir_superior, cod_coordinacion, cod_secretaria, cod_direccion, cod_division, cod_departamento, cod_oficina, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra ASC", 1, $pagina, null);
			$this->set("datosFILAS", $datos_filas);
			$this->set('siguiente', $pagina + 1);
			$this->set('anterior', $pagina - 1);
			$this->bt_nav($Tfilas, $pagina);
		} else {
			$this->set("datosFILAS", '');
		}

		if ($var2 == 1) {
//  $sql = "cod_tipo_transaccion=".$var2." and cod_transaccion!=1";
			$sql = "cod_tipo_transaccion=" . $var2 . "";
		} else {
			$sql = "cod_tipo_transaccion=" . $var2 . " and uso_transaccion=6";
		}

		$consulta_c_t = $this->cnmd03_transaccion->generateList($sql, 'cod_transaccion ASC', null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.denominacion');
		$this->concatenaN($consulta_c_t, "lista");

		$data2 = $this->cnmd03_transaccion->findAll('cod_tipo_transaccion=' . $var2 . ' and cod_transaccion=' . $var3, null, null, null);

		$this->set('tipo_tran', $var2);
		$this->set('s_lista', $var3);
		$this->set('denominacion', $data2[0]["cnmd03_transaccion"]["denominacion"]);

		foreach ($datos_filas as $ve) {

			$ano = $ve["v_cnmd05_para_cnmd03_conex_trans"]["ano"];
			$cod_sector = $ve["v_cnmd05_para_cnmd03_conex_trans"]["cod_sector"];
			$cod_programa = $ve["v_cnmd05_para_cnmd03_conex_trans"]["cod_programa"];
			$cod_sub_prog = $ve["v_cnmd05_para_cnmd03_conex_trans"]["cod_sub_prog"];
			$cod_proyecto = $ve["v_cnmd05_para_cnmd03_conex_trans"]["cod_proyecto"];
			$cod_activ_obra = $ve["v_cnmd05_para_cnmd03_conex_trans"]["cod_activ_obra"];

			//$sector = $this->cfpd02_sector->generateList(          $this->SQLCA($ano),'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.denominacion');
			$sector = $this->v_cfpd05_denominaciones_sin_cero->generateList($this->SQLCA($ano), 'cod_sector ASC', null, '{n}.v_cfpd05_denominaciones_sin_cero.cod_sector', '{n}.v_cfpd05_denominaciones_sin_cero.deno_sector');
			$sector = $sector != null ? $sector : array();

			//$programa =$this->cfpd02_programa->generateList(         $this->SQLCA($ano)." and cod_sector=".$cod_sector,'cod_programa ASC', null, '{n}.cfpd02_programa.cod_programa', '{n}.cfpd02_programa.denominacion');
			$programa = $this->v_cfpd05_denominaciones_sin_cero->generateList($this->SQLCA($ano) . " and cod_sector=" . $cod_sector, 'cod_programa ASC', null, '{n}.v_cfpd05_denominaciones_sin_cero.cod_programa', '{n}.v_cfpd05_denominaciones_sin_cero.deno_programa');
			$programa = $programa != null ? $programa : array();

			//$sub_prog =$this->cfpd02_sub_prog->generateList(           $this->SQLCA($ano)." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa,'cod_sub_prog ASC', null, '{n}.cfpd02_sub_prog.cod_sub_prog', '{n}.cfpd02_sub_prog.denominacion');
			$sub_prog = $this->v_cfpd05_denominaciones_sin_cero->generateList($this->SQLCA($ano) . " and cod_sector=" . $cod_sector . " and cod_programa=" . $cod_programa, 'cod_sub_prog ASC', null, '{n}.v_cfpd05_denominaciones_sin_cero.cod_sub_prog', '{n}.v_cfpd05_denominaciones_sin_cero.deno_sub_prog');
			$sub_prog = $sub_prog != null ? $sub_prog : array();

			//$proyecto   = $this->cfpd02_proyecto->generateList(        $this->SQLCA($ano)." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog, 'cod_proyecto ASC', null, '{n}.cfpd02_proyecto.cod_proyecto', '{n}.cfpd02_proyecto.denominacion');
			$proyecto = $this->v_cfpd05_denominaciones_sin_cero->generateList($this->SQLCA($ano) . " and cod_sector=" . $cod_sector . " and cod_programa=" . $cod_programa . " and cod_sub_prog=" . $cod_sub_prog, 'cod_proyecto ASC', null, '{n}.v_cfpd05_denominaciones_sin_cero.cod_proyecto', '{n}.v_cfpd05_denominaciones_sin_cero.deno_proyecto');
			$proyecto = $proyecto != null ? $proyecto : array();

			//$activ_obra   = $this->cfpd02_activ_obra->generateList(      $this->SQLCA($ano)." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto, 'cod_activ_obra ASC', null, '{n}.cfpd02_activ_obra.cod_activ_obra', '{n}.cfpd02_activ_obra.denominacion');
			$activ_obra = $this->v_cfpd05_denominaciones_sin_cero->generateList($this->SQLCA($ano) . " and cod_sector=" . $cod_sector . " and cod_programa=" . $cod_programa . " and cod_sub_prog=" . $cod_sub_prog . " and cod_proyecto=" . $cod_proyecto, 'cod_activ_obra ASC', null, '{n}.v_cfpd05_denominaciones_sin_cero.cod_activ_obra', '{n}.v_cfpd05_denominaciones_sin_cero.deno_activ_obra');
			$activ_obra = $activ_obra != null ? $activ_obra : array();

			$cond = $this->SQLCA($ano) . " and cod_sector=" . $cod_sector . " and cod_programa=" . $cod_programa . " and cod_sub_prog=" . $cod_sub_prog . " and cod_proyecto=" . $cod_proyecto . " and cod_activ_obra=" . $cod_activ_obra;

			$this->concatena($sector, 'sector');
			$this->concatena($programa, 'programa');
			$this->concatena($sub_prog, 'sub_prog');
			$this->concatena($proyecto, 'proyecto');
			$this->concatena($activ_obra, 'activ_obra');

			if (!empty($data)) {
				foreach ($data as $ve2) {

					//$cod_partida    =  separa_partida_de_grupo($ve2["cnmd03_partidas"]["cod_partida"]);
					$cod_partida = $ve2["cnmd03_partidas"]["cod_partida"];
					$cod_generica = $ve2["cnmd03_partidas"]["cod_generica"];
					$cod_especifica = $ve2["cnmd03_partidas"]["cod_especifica"];
					$cod_sub_espec = $ve2["cnmd03_partidas"]["cod_sub_espec"];
					$cod_auxiliar = $ve2["cnmd03_partidas"]["cod_auxiliar"];
				} //fin function

				$this->Session->write('sec', $cod_sector);
				$this->Session->write('prog', $cod_programa);
				$this->Session->write('subp', $cod_sub_prog);
				$this->Session->write('proy', $cod_proyecto);
				$this->Session->write('actividad', $cod_activ_obra);
				$this->Session->write('cpar', $cod_partida);
				$this->Session->write('cgen', $cod_generica);
				$this->Session->write('cesp', $cod_especifica);
				$this->Session->write('csesp', $cod_sub_espec);

				$cond2 = "ano=" . $ano . " ";
				$partida = $this->v_cfpd05_denominaciones_sin_cero->generateList($cond . " and " . $cond2 . " ", 'cod_partida ASC', null, '{n}.v_cfpd05_denominaciones_sin_cero.cod_partida', '{n}.v_cfpd05_denominaciones_sin_cero.deno_partida');

				$cond2 = "ano=" . $ano . " and cod_partida=" . $cod_partida;
				$generica = $this->v_cfpd05_denominaciones_sin_cero->generateList($cond . " and " . $cond2, 'cod_generica ASC', null, '{n}.v_cfpd05_denominaciones_sin_cero.cod_generica', '{n}.v_cfpd05_denominaciones_sin_cero.deno_generica');

				$cond2 = "ano=" . $ano . "  and cod_partida=" . $cod_partida . " and cod_generica=" . $cod_generica;
				$especifica = $this->v_cfpd05_denominaciones_sin_cero->generateList($cond . " and " . $cond2, 'cod_especifica ASC', null, '{n}.v_cfpd05_denominaciones_sin_cero.cod_especifica', '{n}.v_cfpd05_denominaciones_sin_cero.deno_especifica');

				$cond2 = "ano=" . $ano . "  and cod_partida=" . $cod_partida . " and cod_generica=" . $cod_generica . " and cod_especifica=" . $cod_especifica;
				$sub_espec = $this->v_cfpd05_denominaciones_sin_cero->generateList($cond . " and " . $cond2, 'cod_sub_espec ASC', null, '{n}.v_cfpd05_denominaciones_sin_cero.cod_sub_espec', '{n}.v_cfpd05_denominaciones_sin_cero.deno_sub_espec');

				$cond2 = " ano=" . $ano . " and cod_partida=" . $cod_partida . " and cod_generica=" . $cod_generica . " and cod_especifica=" . $cod_especifica . " and cod_sub_espec=" . $cod_sub_espec;
				$auxiliar = $this->v_cfpd05_denominaciones_sin_cero->generateList($cond . " and " . $cond2, 'cod_auxiliar ASC', null, '{n}.v_cfpd05_denominaciones_sin_cero.cod_auxiliar', '{n}.v_cfpd05_denominaciones_sin_cero.deno_auxiliar');

				$cond2 = " ano=" . $ano . " and cod_partida=" . $cod_partida . " and cod_generica=" . $cod_generica . " and cod_especifica=" . $cod_especifica . " and cod_sub_espec=" . $cod_sub_espec;
				$sub_espec_contar = 0;
				$sub_espec_contar = $this->v_cfpd05_denominaciones_sin_cero->findCount($cond . " and " . $cond2, 'cod_sub_espec ASC');

				$auxiliar_contar = 0;
				$auxiliar_contar = $this->v_cfpd05_denominaciones_sin_cero->findCount($cond . " and " . $cond2, 'cod_auxiliar ASC');

				$sub_espec_contar = (int) ceil($sub_espec_contar);
				$auxiliar_contar = (int) ceil($auxiliar_contar);

				$this->set("auxiliar_contar", $auxiliar_contar);
				$this->set("sub_espec_contar", $sub_espec_contar);

				if ($sub_espec_contar == 0) {
					$this->set("errorMessage", "1.La partida presupuestaria no esta registrada en el presupuesto " . $ano);
				} else {
					$cod_auxiliar_aux = "";
					if ($auxiliar_contar == 0 && $sub_espec_contar != 0) {
						$cod_auxiliar_aux = 0;
					} else if ($auxiliar_contar == 0 && $sub_espec_contar == 0) {

					} else if ($auxiliar_contar == 1) {
						$cod_auxiliar_aux = $cod_auxiliar;
					} else {

					}
					if ($cod_auxiliar_aux != "") {
						$cond2 = " ano=" . $ano . " and cod_partida=" . $cod_partida . " and cod_generica=" . $cod_generica . " and cod_especifica=" . $cod_especifica . " and cod_sub_espec=" . $cod_sub_espec . " and cod_auxiliar=" . $cod_auxiliar_aux;
						$sub_espec_contar_2 = 0;
						$sub_espec_contar_2 = $this->v_cfpd05_denominaciones_sin_cero->findCount($cond . " and " . $cond2, 'cod_auxiliar ASC');
						$sub_espec_contar_2 = (int) ceil($sub_espec_contar_2);
						if ($sub_espec_contar_2 == 0) {
							$this->set("errorMessage", "2.La partida presupuestaria no esta registrada en el presupuesto " . $ano);
						} else {
							$automatico_procesar = 1;
						}
					}
				} //fin else

				$this->concatena($partida, 'partida');
				$this->concatena($generica, 'generica');
				$this->concatena($especifica, 'especifica');
				$this->concatena($sub_espec, 'sub_espec');
				$this->concatena_cuatro_digitos($auxiliar, 'auxiliar');
			} else {

				$cond2 = "ano=" . $ano . " ";
				$partida = $this->v_cfpd05_denominaciones_sin_cero->generateList($cond . " and " . $cond2 . " ", 'cod_partida ASC', null, '{n}.v_cfpd05_denominaciones_sin_cero.cod_partida', '{n}.v_cfpd05_denominaciones_sin_cero.deno_partida');

				$this->set("auxiliar_contar", 0);
				$this->set("sub_espec_contar", 0);

				$this->set("errorMessage", "Esta Transacción no tiene partidas registradas");

				$this->concatena($partida, 'partida');
				$this->concatena("", 'generica');
				$this->concatena("", 'especifica');
				$this->concatena("", 'sub_espec');
				$this->concatena_cuatro_digitos("", 'auxiliar');
			} //fin else
		} //fin foreach

		$this->set("automatico_procesar", $automatico_procesar);
	}

//fin fucntion

	function agregar_partidas($var = null) {

		$this->layout = "ajax";

		if (isset($var) && !empty($var)) {

			if (isset($this->data["cnmp03_conexion_transacciones"])) {

				$cod[0] = $this->data["cnmp03_conexion_transacciones"]["datos_d"];
				$cod[1] = $this->data["cnmp03_conexion_transacciones"]["datos_e"];
				$cod[2] = $this->data["cnmp03_conexion_transacciones"]["datos_f"];
				$cod[3] = $this->data["cnmp03_conexion_transacciones"]["datos_g"];
				$cod[4] = $this->data["cnmp03_conexion_transacciones"]["datos_h"];
				$cod[5] = $this->data["cnmp03_conexion_transacciones"]["datos_i"];
				$cod[6] = $this->data["cnmp03_conexion_transacciones"]["datos_j"];
				$cod[7] = $this->data["cnmp03_conexion_transacciones"]["datos_k"];
				$cod[8] = $this->data["cnmp03_conexion_transacciones"]["datos_l"];
				$cod[9] = $this->data["cnmp03_conexion_transacciones"]["datos_ll"];
				$cod[10] = $this->data["cnmp03_conexion_transacciones"]["datos_m"];
				$cod[11] = $this->data["cnmp03_conexion_transacciones"]["tipo_transaccion"];
				$cod[12] = $this->data["cnmp03_conexion_transacciones"]["select_tra"];
				$cod[13] = $this->data["cnmp03_conexion_transacciones"]["datos_c"];
				if (isset($_SESSION["i"])) {
					$i = $this->Session->read("i") + 1;
					$this->Session->write("i", $i);
				} else {
					$this->Session->write("i", 0);
					$i = 0;
				}
				$vec[$i][0] = $cod[0];
				$vec[$i][1] = $this->AddCeroR($cod[1]);
				$vec[$i][2] = $this->AddCeroR($cod[2]);
				$vec[$i][3] = $this->AddCeroR($cod[3]);
				$vec[$i][4] = $this->AddCeroR($cod[4]);
				$vec[$i][5] = $this->AddCeroR($cod[5]);
				$vec[$i][6] = $cod[6];
				$vec[$i][7] = $this->AddCeroR($cod[7]);
				$vec[$i][8] = $this->AddCeroR($cod[8]);
				$vec[$i][9] = $this->AddCeroR($cod[9]);
				$vec[$i][10] = $this->mascara_cuatro($cod[10]);
				$vec[$i][11] = $cod[11];
				$vec[$i][12] = $this->AddCeroR($cod[12]);
				$vec[$i][13] = $cod[13];
				$vec[$i]["id"] = $i;

				if (isset($_SESSION["items"])) {
					foreach ($_SESSION["items"] as $codi) {
						if (($codi['id'] != "no" || $codi['id'] == "0") && ($codi[0] == $cod[0] && $codi[1] == $cod[1] && $codi[2] == $cod[2] && $codi[3] == $cod[3] && $codi[4] == $cod[4] && $codi[5] == $cod[5] && $codi[6] == $cod[6] && $codi[7] == $cod[7] && $codi[8] == $cod[8] && $codi[9] == $cod[9] && $codi[10] == $cod[10] && $codi[11] == $cod[11] && $codi[12] == $cod[12])) {
							$est = true;
							break;
						} else {
							$est = false;
						}
					} //fin foreach
					if ($est == true) {
						$i = $this->Session->read("i") - 1;
						$this->Session->write("i", $i);
						$this->set('errorMessage', 'Los codigos seleccionados ya existen en la lista');
					} else {
						$_SESSION["items"] = $_SESSION["items"] + $vec;
					}
				} else {
					$_SESSION["items"] = $vec;
				}
			} //isset data
		} else {

			$cod[0] = $this->data["cnmp03_conexion_transacciones"]["datos_d"];
			$cod[1] = $this->data["cnmp03_conexion_transacciones"]["datos_e"];
			$cod[2] = $this->data["cnmp03_conexion_transacciones"]["datos_f"];
			$cod[3] = $this->data["cnmp03_conexion_transacciones"]["datos_g"];
			$cod[4] = $this->data["cnmp03_conexion_transacciones"]["datos_h"];
			$cod[5] = $this->data["cnmp03_conexion_transacciones"]["datos_i"];
			$cod[6] = $this->data["cnmp03_conexion_transacciones"]["datos_j"];
			$cod[7] = $this->data["cnmp03_conexion_transacciones"]["datos_k"];
			$cod[8] = $this->data["cnmp03_conexion_transacciones"]["datos_l"];
			$cod[9] = $this->data["cnmp03_conexion_transacciones"]["datos_ll"];
			$cod[10] = $this->data["cnmp03_conexion_transacciones"]["datos_m"];
			$cod[11] = $this->data["cnmp03_conexion_transacciones"]["tipo_transaccion"];
			$cod[12] = $this->data["cnmp03_conexion_transacciones"]["select_tra"];
			$cod[13] = $this->data["cnmp03_conexion_transacciones"]["datos_c"];

			if (isset($_SESSION["i"])) {
				$i = $this->Session->read("i") + 1;
				$this->Session->write("i", $i);
			} else {
				$this->Session->write("i", 0);
				$i = 0;
			}
			$vec[$i][0] = $cod[0];
			$vec[$i][1] = $this->AddCeroR($cod[1]);
			$vec[$i][2] = $this->AddCeroR($cod[2]);
			$vec[$i][3] = $this->AddCeroR($cod[3]);
			$vec[$i][4] = $this->AddCeroR($cod[4]);
			$vec[$i][5] = $this->AddCeroR($cod[5]);
			$vec[$i][6] = $cod[6];
			$vec[$i][7] = $this->AddCeroR($cod[7]);
			$vec[$i][8] = $this->AddCeroR($cod[8]);
			$vec[$i][9] = $this->AddCeroR($cod[9]);
			$vec[$i][10] = $this->mascara_cuatro($cod[10]);
			$vec[$i][11] = $cod[11];
			$vec[$i][12] = $this->AddCeroR($cod[12]);
			$vec[$i][13] = $cod[13];
			$vec[$i]["id"] = $i;

			if (isset($_SESSION["items"])) {
				foreach ($_SESSION["items"] as $codi) {
					if (($codi['id'] != "no" || $codi['id'] == "0") && ($codi[0] == $cod[0] && $codi[1] == $cod[1] && $codi[2] == $cod[2] && $codi[3] == $cod[3] && $codi[4] == $cod[4] && $codi[5] == $cod[5] && $codi[6] == $cod[6] && $codi[7] == $cod[7] && $codi[8] == $cod[8] && $codi[9] == $cod[9] && $codi[10] == $cod[10] && $codi[11] == $cod[11] && $codi[12] == $cod[12])) {
						$est = true;
						break;
					} else {
						$est = false;
					}
				} //fin foreach
				if ($est == true) {
					$i = $this->Session->read("i") - 1;
					$this->Session->write("i", $i);
					$this->set('errorMessage', 'Los codigos seleccionados ya existen en la lista');
				} else {
					$_SESSION["items"] = $_SESSION["items"] + $vec;
					//  echo "si";
				}
			} else {
				$_SESSION["items"] = $vec;
			}
		} //fin else

		$this->set('datos_sesion', $_SESSION["items"]);
	}

//fin function

	function eliminar_items($id) {
		$this->layout = "ajax";
		$_SESSION["items"][$id]['id'] = 'no';

		$activar = 0;

		foreach ($_SESSION["items"] as $codigos) {
			if ($codigos['id'] == "no" && $codigos['id'] != "0") {

			} else {
				$activar = 1;
			} //fin if
		} //fin foreach

		if ($activar == 0) {
			echo "<script>document.getElementById('procesar').disabled=true; </script>";
		} else {
			echo "<script>document.getElementById('procesar').disabled=false; </script>";
		} //fin if

		$this->render("funcion");
	}

//fin funtion

	function procesar_grillas() {

		$this->layout = "ajax";

		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$pagina = $this->Session->read('sesion_pagina');

		$cod_dir_superior = $this->data["cnmp03_conexion_transacciones"]["campo_a_1"];
		$ano = $this->data["cnmp03_conexion_transacciones"]["campo_c_1"];

		$cod_coordinacion = $this->data["cnmp03_conexion_transacciones"]["campo_a_2"];
		$cod_sector = $this->data["cnmp03_conexion_transacciones"]["campo_c_2"];

		$cod_secretaria = $this->data["cnmp03_conexion_transacciones"]["campo_a_3"];
		$cod_programa = $this->data["cnmp03_conexion_transacciones"]["campo_c_3"];

		$cod_direccion = $this->data["cnmp03_conexion_transacciones"]["campo_a_4"];
		$cod_sub_prog = $this->data["cnmp03_conexion_transacciones"]["campo_c_4"];

		$cod_division = $this->data["cnmp03_conexion_transacciones"]["campo_a_5"];
		$cod_proyecto = $this->data["cnmp03_conexion_transacciones"]["campo_c_5"];

		$cod_departamento = $this->data["cnmp03_conexion_transacciones"]["campo_a_6"];
		$cod_activ_obra = $this->data["cnmp03_conexion_transacciones"]["campo_c_6"];
		$cod_oficina = $this->data["cnmp03_conexion_transacciones"]["campo_a_7"];

		$cod_nomina = $this->data["cnmp03_conexion_transacciones"]["cod_nomina"];
		$tipo = $this->data["cnmp03_conexion_transacciones"]["pregunta_si_no"];

		$var1 = $cod_presi . ", " . $cod_entidad . ", " . $cod_tipo_inst . ", " . $cod_inst . ", " . $cod_dep . ", " . $cod_nomina . ", " . $cod_dir_superior . ", " . $cod_coordinacion . ", " . $cod_secretaria . ", " . $cod_direccion . ", " . $cod_division . ", " . $cod_departamento . ", " . $cod_oficina . ",  " . $ano . ", " . $cod_sector . ", " . $cod_programa . ", " . $cod_sub_prog . ", " . $cod_proyecto . ", " . $cod_activ_obra . "     ";

		$this->cnmd05->execute("BEGIN;");

		$cod_tipo_transaccion = $this->data["cnmp03_conexion_transacciones"]["tipo_transaccion"];
		$cod_transaccion = $this->data["cnmp03_conexion_transacciones"]["select_tra"];
		$ano = $this->data["cnmp03_conexion_transacciones"]["datos_d"];
		$cod_sector = $this->data["cnmp03_conexion_transacciones"]["datos_e"];
		$cod_programa = $this->data["cnmp03_conexion_transacciones"]["datos_f"];
		$cod_sub_prog = $this->data["cnmp03_conexion_transacciones"]["datos_g"];
		$cod_proyecto = $this->data["cnmp03_conexion_transacciones"]["datos_h"];
		$cod_activ_obra = $this->data["cnmp03_conexion_transacciones"]["datos_i"];
		$cod_partida = $this->data["cnmp03_conexion_transacciones"]["datos_j"];
		$cod_generica = $this->data["cnmp03_conexion_transacciones"]["datos_k"];
		$cod_especifica = $this->data["cnmp03_conexion_transacciones"]["datos_l"];
		$cod_sub_espec = $this->data["cnmp03_conexion_transacciones"]["datos_ll"];
		$cod_auxiliar = $this->data["cnmp03_conexion_transacciones"]["datos_m"];
		$var2 = $cod_tipo_transaccion . ", " . $cod_transaccion . ", " . $ano . ", " . $cod_sector . ", " . $cod_programa . ", " . $cod_sub_prog . ", " . $cod_proyecto . ", " . $cod_activ_obra . ", " . $cod_partida . ", " . $cod_generica . ", " . $cod_especifica . ", " . $cod_sub_espec . ", " . $cod_auxiliar;

		$cond = $this->SQLCA($ano) . " and cod_sector=" . $cod_sector . " and cod_programa=" . $cod_programa . " and cod_sub_prog=" . $cod_sub_prog . " and cod_proyecto=" . $cod_proyecto . " and cod_activ_obra=" . $cod_activ_obra;
		$cond2 = " ano=" . $ano . " and cod_partida=" . $cod_partida . " and cod_generica=" . $cod_generica . " and cod_especifica=" . $cod_especifica . " and cod_sub_espec=" . $cod_sub_espec . " and cod_auxiliar=" . $cod_auxiliar;
		$sub_espec_contar_2 = 0;
		$sub_espec_contar_2 = $this->v_cfpd05_denominaciones_sin_cero->findCount($cond . " and " . $cond2, 'cod_auxiliar ASC');
		$sub_espec_contar_2 = (int) ceil($sub_espec_contar_2);
		if ($sub_espec_contar_2 == 0) {
			$this->set("errorMessage", "La partida presupuestaria no esta registrada en el presupuesto  " . $ano);
		} else {
			if ($tipo == 1) {
				$f = $this->cnmd05->execute(" select insetar_en_cnmd03_transacciones_direcciones('{" . $var1 . "}', '{" . $var2 . "}'); ");
				if ($f[0][0]["insetar_en_cnmd03_transacciones_direcciones"] == "si") {
					$sw = 2;
				} else {
					$sw = 0;
				}
			} else {
				$f = $this->cnmd05->execute(" select insetar_en_cnmd03_transacciones('{" . $var1 . "}', '{" . $var2 . "}');");
				if ($f[0][0]["insetar_en_cnmd03_transacciones"] == "si") {
					$sw = 2;
				} else {
					$sw = 0;
				}
			} //fin else

			if ($sw > 1) {
				$this->cnmd05->execute("COMMIT;");
				$this->set('Message_existe', 'Los Datos Fueron Guardados ');
				if ($tipo == 1) {
					$pagina = 1;
				} else {
					$pagina++;
				}
			} else {
				$this->cnmd05->execute("ROLLBACK;");
				$this->set('errorMessage', 'Los Datos no Fueron Guardados ');
			} //fin else
		}

		$this->consulta_blue_1($pagina, $cod_tipo_transaccion, $cod_transaccion);
	}

//fin funcion

	function procesar_grillas_sueldo() {

		$this->layout = "ajax";

		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$pagina = $this->Session->read('sesion_pagina');

		$cod_dir_superior = $this->data["cnmp03_conexion_transacciones"]["campo_a_1"];
		$ano = $this->data["cnmp03_conexion_transacciones"]["campo_c_1"];

		$cod_coordinacion = $this->data["cnmp03_conexion_transacciones"]["campo_a_2"];
		$cod_sector = $this->data["cnmp03_conexion_transacciones"]["campo_c_2"];

		$cod_secretaria = $this->data["cnmp03_conexion_transacciones"]["campo_a_3"];
		$cod_programa = $this->data["cnmp03_conexion_transacciones"]["campo_c_3"];

		$cod_direccion = $this->data["cnmp03_conexion_transacciones"]["campo_a_4"];
		$cod_sub_prog = $this->data["cnmp03_conexion_transacciones"]["campo_c_4"];

		$cod_division = $this->data["cnmp03_conexion_transacciones"]["campo_a_5"];
		$cod_proyecto = $this->data["cnmp03_conexion_transacciones"]["campo_c_5"];

		$cod_departamento = $this->data["cnmp03_conexion_transacciones"]["campo_a_6"];
		$cod_activ_obra = $this->data["cnmp03_conexion_transacciones"]["campo_c_6"];
		$cod_oficina = $this->data["cnmp03_conexion_transacciones"]["campo_a_7"];

		$cod_nomina = $this->data["cnmp03_conexion_transacciones"]["cod_nomina"];

		$var1 = $cod_presi . ", " . $cod_entidad . ", " . $cod_tipo_inst . ", " . $cod_inst . ", " . $cod_dep . ", " . $cod_nomina . ", " . $cod_dir_superior . ", " . $cod_coordinacion . ", " . $cod_secretaria . ", " . $cod_direccion . ", " . $cod_division . ", " . $cod_departamento . ", " . $cod_oficina . ",  " . $ano . ", " . $cod_sector . ", " . $cod_programa . ", " . $cod_sub_prog . ", " . $cod_proyecto . ", " . $cod_activ_obra . "     ";

		$this->cnmd05->execute("BEGIN;");

		$cod_tipo_transaccion = 1;
		$cod_transaccion = 1;

		$sql_1 = "cod_presi=" . $cod_presi . " and cod_entidad=" . $cod_entidad . " and cod_tipo_inst=" . $cod_tipo_inst . " and cod_inst=" . $cod_inst . " and cod_dep=" . $cod_dep . " and cod_tipo_nomina=" . $cod_nomina . " and cod_dir_superior=" . $cod_dir_superior . " and cod_coordinacion=" . $cod_coordinacion . " and cod_secretaria=" . $cod_secretaria . " and cod_direccion=" . $cod_direccion . " and cod_division=" . $cod_division . " and cod_departamento=" . $cod_departamento . " and cod_oficina=" . $cod_oficina . " and ano=" . $ano . " and cod_sector=" . $cod_sector . " and cod_programa=" . $cod_programa . " and cod_sub_prog=" . $cod_sub_prog . " and cod_proyecto=" . $cod_proyecto . " and cod_activ_obra=" . $cod_activ_obra . "     ";
		$cnmd05_datos = $this->cnmd05->findAll($sql_1);

		$sw = 0;

		foreach ($cnmd05_datos as $ve2) {
			$cod_tipo_nomina = $ve2["cnmd05"]["cod_tipo_nomina"];
			$cod_cargo = $ve2["cnmd05"]["cod_cargo"];
			$cod_puesto = $ve2["cnmd05"]["cod_puesto"];
			$cod_partida = $ve2["cnmd05"]["cod_partida"];
			$cod_generica = $ve2["cnmd05"]["cod_generica"];
			$cod_especifica = $ve2["cnmd05"]["cod_especifica"];
			$cod_sub_espec = $ve2["cnmd05"]["cod_sub_espec"];
			$cod_auxiliar = $ve2["cnmd05"]["cod_auxiliar"];
			$sql_cargo = "cod_presi=" . $cod_presi . " and cod_entidad=" . $cod_entidad . " and cod_tipo_inst=" . $cod_tipo_inst . " and cod_inst=" . $cod_inst . " and cod_dep=" . $cod_dep . " and cod_tipo_nomina=" . $cod_nomina . " and cod_cargo=" . $cod_cargo . " and cod_tipo_transaccion=" . $cod_tipo_transaccion . " and cod_transaccion=" . $cod_transaccion;

			$contar_2 = 0;
			$contar_2 = $this->cnmd03_conexion_transacciones->findCount($sql_cargo);
			$contar_2 = (int) ceil($contar_2);
			if ($contar_2 == 0) {

				$sql = "
                INSERT INTO  cnmd03_conexion_transacciones VALUES (
                                                                    " . $cod_presi . ",
                                                                    " . $cod_entidad . ",
                                                                    " . $cod_tipo_inst . ",
                                                                    " . $cod_inst . ",
                                                                    " . $cod_dep . ",
                                                                    " . $cod_tipo_nomina . ",
                                                                    " . $cod_cargo . ",
                                                                    " . $cod_tipo_transaccion . ",
                                                                    " . $cod_transaccion . ",
                                                                    " . $ano . ",
                                                                    " . $cod_sector . ",
                                                                    " . $cod_programa . ",
                                                                    " . $cod_sub_prog . ",
                                                                    " . $cod_proyecto . ",
                                                                    " . $cod_activ_obra . ",
                                                                    " . $cod_partida . ",
                                                                    " . $cod_generica . ",
                                                                    " . $cod_especifica . ",
                                                                    " . $cod_sub_espec . ",
                                                                    " . $cod_auxiliar . ");
             ";
			} else {

				$sql = "
                                  UPDATE cnmd03_conexion_transacciones set
                                                                        ano            = " . $ano . ",
                                                                        cod_sector     = " . $cod_sector . ",
                                                                        cod_programa   = " . $cod_programa . ",
                                                                        cod_sub_prog   = " . $cod_sub_prog . ",
                                                                        cod_proyecto   = " . $cod_proyecto . ",
                                                                        cod_activ_obra = " . $cod_activ_obra . ",
                                                                        cod_partida    = " . $cod_partida . ",
                                                                        cod_generica   = " . $cod_generica . ",
                                                                        cod_especifica = " . $cod_especifica . ",
                                                                        cod_sub_espec  = " . $cod_sub_espec . ",
                                                                        cod_auxiliar   = " . $cod_auxiliar . "
                                                                WHERE
                                                                        cod_presi             = " . $cod_presi . "       and
                                                                        cod_entidad           = " . $cod_entidad . "     and
                                                                        cod_tipo_inst         = " . $cod_tipo_inst . "   and
                                                                        cod_inst              = " . $cod_inst . "        and
                                                                        cod_dep               = " . $cod_dep . "         and
                                                                        cod_tipo_nomina       = " . $cod_tipo_nomina . " and
                                                                        cod_cargo             = " . $cod_cargo . "       and
                                                                        cod_tipo_transaccion  = " . $cod_tipo_transaccion . " and
                                                                        cod_transaccion       = " . $cod_transaccion . ";
                                ";
			}

			$cond = $this->SQLCA($ano) . " and cod_sector=" . $cod_sector . " and cod_programa=" . $cod_programa . " and cod_sub_prog=" . $cod_sub_prog . " and cod_proyecto=" . $cod_proyecto . " and cod_activ_obra=" . $cod_activ_obra;
			$cond2 = "   ano=" . $ano . "    and cod_partida=" . $cod_partida . " and cod_generica=" . $cod_generica . " and cod_especifica=" . $cod_especifica . " and cod_sub_espec=" . $cod_sub_espec . " and cod_auxiliar=" . $cod_auxiliar;
			$sub_espec_contar_2 = 0;
			$sub_espec_contar_2 = $this->v_cfpd05_denominaciones_sin_cero->findCount($cond . " and " . $cond2, 'cod_auxiliar ASC');
			$sub_espec_contar_2 = (int) ceil($sub_espec_contar_2);

			if ($sub_espec_contar_2 == 0) {
				$this->set("errorMessage", "La partida presupuestaria no esta registrada en el presupuesto  " . $ano);
				break;
			} else {

				$sw = $this->cnmd03_conexion_transacciones->execute($sql);
				if ($sw > 1) {

				} else {
					break;
				}
			}
		} //fin foreach

		if ($sw > 1) {
			$this->cnmd05->execute("COMMIT;");
			$this->set('Message_existe', 'Los Datos Fueron Guardados ');
		} else {
			$this->cnmd05->execute("ROLLBACK;");
			$this->set('errorMessage', 'Los Datos no Fueron Guardados ');
		} //fin else

		$this->consulta_blue_1($pagina, $cod_tipo_transaccion, $cod_transaccion, false);
	}

//fin funcion

	function eliminar_conexion($var1 = null, $var2 = null, $var3 = null, $var4 = null, $var5 = null, $var6 = null, $var7 = null, $var8 = null, $var9 = null, $var10 = null, $var11 = null, $var12 = null, $var13 = null) {

		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$this->layout = "ajax";
		$sql = $this->condicion() . " and cod_tipo_nomina='" . $this->Session->read('tipo_nomina') . "'   ";

		$sql_2 = "WHERE
              cod_presi               = '" . $cod_presi . "'      and
              cod_entidad             = '" . $cod_entidad . "'    and
              cod_tipo_inst           = '" . $cod_tipo_inst . "'  and
              cod_inst                = '" . $cod_inst . "'       and
              cod_dep                 = '" . $cod_dep . "'        and
              cod_tipo_nomina         = '" . $this->Session->read('tipo_nomina') . "'  and
              cod_tipo_transaccion    = '" . $var1 . "'           and
              cod_transaccion         = '" . $var2 . "'           and
              ano                     = '" . $var3 . "'           and
              cod_sector              = '" . $var4 . "'           and
              cod_programa            = '" . $var5 . "'           and
              cod_sub_prog            = '" . $var6 . "'           and
              cod_proyecto            = '" . $var7 . "'           and
              cod_activ_obra          = '" . $var8 . "'           and
              cod_partida             = '" . $var9 . "'           and
              cod_generica            = '" . $var10 . "'          and
              cod_especifica          = '" . $var11 . "'          and
              cod_sub_espec           = '" . $var12 . "'          and
              cod_auxiliar            = '" . $var13 . "'
        ";

		$sw = $this->cnmd05->execute("DELETE FROM cnmd03_conexion_transacciones " . $sql_2);

		if ($sw > 1) {
			$this->set('Message_existe', 'El registro fue eliminado ');
		} else {
			$this->set('errorMessage', 'El registro no fue eliminado');
		} //fin else

		$datos_filas = array();
		$ano_ejecucion = $this->ano_ejecucion();
		$datos_v_cnmd03_conexion_transacciones = $this->v_cnmd03_conexion_transacciones->findAll($sql . " and ano=" . $ano_ejecucion, null, "cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep, cod_tipo_nomina,cod_tipo_transaccion,cod_transaccion ASC", null, null, null);
		$this->set("datos_v_cnmd03_conexion_transacciones", $datos_v_cnmd03_conexion_transacciones);
	}

	function conexion_automatica() {
		$this->layout = "ajax";
		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$cod_tipo_nomina = $this->Session->read('tipo_nomina');

		$sql_ano_formulacion = $this->cnmd05->execute("SELECT ano_formular FROM cfpd01_formulacion WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst");
		if (!empty($sql_ano_formulacion)) {
			$ano_formular = $sql_ano_formulacion[0][0]['ano_formular'];
		}

		$sql_ano_ejecucion = $this->cnmd05->execute("SELECT ano_arranque FROM ccfd03_instalacion WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep");
		if (!empty($sql_ano_ejecucion)) {
			$ano_ejecucion = $sql_ano_ejecucion[0][0]['ano_arranque'];
		}

		if ($ano_formular == $ano_ejecucion) {

			$cfpd97_transaccion = $this->cnmd05->execute("SELECT * FROM cfpd97_transacciones WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina ORDER BY cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_tipo_transaccion, cod_transaccion;");
			if (!empty($cfpd97_transaccion)) {
				foreach ($cfpd97_transaccion as $transa) {
					$tipo_nomina = $transa[0]['cod_tipo_nomina'];
					$cod_cargo = $transa[0]['cod_cargo'];
					$cod_tipo_transa = $transa[0]['cod_tipo_transaccion'];
					$cod_transa = $transa[0]['cod_transaccion'];
					$ano = $transa[0]['ano'];
					$sector = $transa[0]['cod_sector'];
					$programa = $transa[0]['cod_programa'];
					$sub_programa = $transa[0]['cod_sub_prog'];
					$proyecto = $transa[0]['cod_proyecto'];
					$actividad = $transa[0]['cod_activ_obra'];
					$partida = $transa[0]['cod_partida'];
					$generica = $transa[0]['cod_generica'];
					$especifica = $transa[0]['cod_especifica'];
					$sub_especifica = $transa[0]['cod_sub_espec'];
					$auxiliar = $transa[0]['cod_auxiliar'];

					if ($ano == $ano_ejecucion) {
						$sql_delete_conexion = "DELETE from cnmd03_conexion_transacciones WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep and cod_tipo_nomina=$tipo_nomina and cod_cargo=$cod_cargo and cod_tipo_transaccion=$cod_tipo_transa and cod_transaccion=$cod_transa";
						$this->cnmd05->execute($sql_delete_conexion);

						$parametros = $cod_presi . "," . $cod_entidad . "," . $cod_tipo_inst . "," . $cod_inst . "," . $cod_dep . "," . $tipo_nomina . "," . $cod_cargo . "," . $cod_tipo_transa . "," . $cod_transa . "," . $ano . "," . $sector . "," . $programa . "," . $sub_programa . "," . $proyecto . "," . $actividad . "," . $partida . "," . $generica . "," . $especifica . "," . $sub_especifica . "," . $auxiliar;
						$update_cargo = $this->cnmd05->execute("SELECT update_cfpd97_conexion_automatica($parametros);");
					}
				} //transacciones
			} // IF empty

			echo "<script>
            $('conexion_automatica').value='Proceso Realizado';
            $('conexion_automatica').disabled='true';
        </script>";

		} else {
			$this->set('errorMessage', 'No se puede correr este proceso mientras se esta formulando.');
			echo "<script>
            $('conexion_automatica').value='PROCESO NO REALIZADO';
            $('conexion_automatica').disabled='true';
        </script>";

		}
	}

//fin conexion_automatica
}

//fin clase
?>
