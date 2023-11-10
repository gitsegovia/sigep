<?php

 class Cnmp99PrenominaController extends AppController{


 	var $uses = array('ccfd04_cierre_mes','Cnmd01','cnmd03_transacciones','cnmd09_numero_nominas_canceladas','cnmd07_calculo_aguinaldos','cnmd07_calculo_bonovaca','cnmd16_identif_transa','cnmd09_lunes_ejercicio','cnmd09_incidencia_sueldo_sugerido','cnmd09_dias_trabajados_ingreso_egreso',
 						'v_cnmp99_nomina_costo_municipios', 'v_cnmp99_costo_nominas','costo_presupuestario_p2');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf','Form');


function checkSession(){
	if (!$this->Session->check('Usuario')){
			$this->redirect('/salir/');
			exit();
	}else{
		$this->requestAction('/usuarios/actualizar_user');
	}
}//fin checksession

function beforeFilter(){
	$this->checkSession();
	if($this->ano_ejecucion()!=""){
		return;
	}else{
		echo "<h3>Por Favor, Registre el Año de Ejecuci&oacute;n de Presupuesto<br>Ingrese al M&oacute;dulo de Uso General</h3>";
		exit();
	}
}

function verifica_SS($i){
			/**
			 * Funcion que permite leer las varibles de session. la cual permite capturar los codigos del usuario
			 * para ser insertados en todas las tablas.
			 * */
			switch ($i){
				case 1:return $this->Session->read('SScodpresi');break;
				case 2:return $this->Session->read('SScodentidad');break;
				case 3:return $this->Session->read('SScodtipoinst');break;
				case 4:return $this->Session->read('SScodinst');break;
				case 5:return $this->Session->read('SScoddep');break;
				case 6:return $this->Session->read('entidad_federal');break;
				default:
					 return "NULO";
			}//fin switch
}//fin verifica_SS

function SQLCA($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
         if($ano!=null){
         	$sql_re .= "cod_dep=".$this->verifica_SS(5)."  and  ";
            $sql_re .= "ano=".$ano."  ";
         }else{
         	$sql_re .= "cod_dep=".$this->verifica_SS(5)." ";
         }
         return $sql_re;
}//fin funcion SQLCA

function index () {
    $this->layout="ajax";
    $lista = $this->Cnmd01->generateList($this->SQLCA()." and status_nomina IN (0,1)", 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($this->Cnmd01->findCount($this->SQLCA())!=0){
		$this->concatenaN($lista, 'tipo_nomina');
	}else{
		$this->set('tipo_nomina', array());
	}
}//fin index

function deno_nomina ($cod_tipo_nomina=null) {
     $this->layout="ajax";
     if (isset($cod_tipo_nomina)) {
         $lista = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina,'cod_tipo_nomina, denominacion');
		if($this->Cnmd01->findCount($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina)!=0){
			$this->set( 'tipo_nomina',$lista);
$this->Session->write('nomina', $cod_tipo_nomina);
			$c=$this->cnmd09_numero_nominas_canceladas->findCount($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
		    if($c==0){
		    	$this->set('numero_nomina',1);
		    }else{
		    	$n=$this->cnmd09_numero_nominas_canceladas->execute("SELECT numero_nomina FROM cnmd09_numero_nominas_canceladas WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." ORDER BY numero_nomina DESC LIMIT 1");
		        $this->set('numero_nomina',$n[0][0]["numero_nomina"]+1);
		    }
		}else{
			$this->set('tipo_nomina', array());
		}

			echo "<script type='text/javascript'>
				document.getElementById('boton_vppnr').disabled = false;
			</script>";

	}
}



// ******** LA SIG. FUNCION CHEQUEA PROCESOS PRE Y POST DE LA PRENOMINA VERSION IMPRESA *********

function verificarp_prenomina_print ($cod_tipo_nomina = null, $procesada = 0){

    $cod_presi     = $this->Session->read('SScodpresi');
	$cod_entidad   = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst      = $this->Session->read('SScodinst');
	$cod_dep       = $this->Session->read('SScoddep');
	$cod_tipo_nomina=$this->Session->read('nomina');
	$status_actual = $this->Cnmd01->field("status_nomina", $this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
    if ($status_actual==0 || $status_actual==1){

    $this->layout="pdf";

	$procedimiento = 0;
	$finalizo=0;
	$exe_cnmd01 = 0;

	$datos_pnomina_a = 0;
	$datos_pnomina_b = 0;
	$datos_pnomina_c = 0;
	$datos_pnomina_d = 0;
	$datos_pnomina_e = 0;
	$datos_pnomina_f = 0;
	$datos_pnomina_g = 0;
	$datos_pnomina_h = 0;
	$datos_pnomina_j = 0;
	$datos_pnomina_k = 0;
	$datos_pnomina_l = 0;
	$datos_pnomina_n = 0;
	$datos_pnomina_m = 0;
	$datos_pnomina_s = 0;

if($cod_tipo_nomina != null){

	/** ******* INICIO PRE *********** */

// if($procesada == 0){

	$datos_pnomina_a = $this->Cnmd01->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_entidad_bancaria, banco FROM v_cnmp99_prenomina_banco_canc_nomina WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
	if(!empty($datos_pnomina_a)){
		$procedimiento=1;
		$status_nomina = 0;
	}

		$datos_pnomina_b = $this->Cnmd01->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, tipo, cod_transaccion, transaccion FROM v_cnmp99_prenomina_frecuencia WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
		if(!empty($datos_pnomina_b)){
			$procedimiento=2;
			$status_nomina = 0;
		}


		$datos_pnomina_g = $this->Cnmd01->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, tipo, cod_transaccion, transaccion FROM v_cnmp99_prenomina_asignaciones WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
		if(!empty($datos_pnomina_g)){
			$procedimiento=3;
			$status_nomina = 0;
		}


		$datos_pnomina_h = $this->Cnmd01->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, tipo, cod_transaccion, transaccion FROM v_cnmp99_prenomina_deducciones WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
		if(!empty($datos_pnomina_h)){
			$procedimiento=4;
			$status_nomina = 0;
		}

		$datos_pnomina_c = $this->Cnmd01->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_transaccion, transaccion FROM v_cnmp99_prenomina_banco_canc_transa WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
		if(!empty($datos_pnomina_c)){
			$procedimiento=5;
			$status_nomina = 0;
		}



// }
	/** ******* FIN PRE *********** */


// -.-.-.-.-.-.-.-.-.-.-.- .. [PASANDO A PROCESOS POST] ... -.-.-.-.-.-.-.-.-.-.-.----- //

// else if((int) $procesada == 1){

	/** ******* INICIO POST *********** */



		$datos_pnomina_c = $this->Cnmd01->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_transaccion, transaccion FROM v_cnmp99_prenomina_banco_canc_transa WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
		if(!empty($datos_pnomina_c)){
			$procedimiento=6;
			$status_nomina = 0;
		}


		$datos_pnomina_d = $this->Cnmd01->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, tipo, cod_transaccion, transaccion FROM v_cnmp99_prenomina_conexion WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
		if(!empty($datos_pnomina_d)){
			$procedimiento=7;
			$status_nomina = 0;
		}


		$datos_pnomina_e = $this->Cnmd01->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar FROM v_cnmp99_prenomina_part_conecta_no_presu WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
		if(!empty($datos_pnomina_e)){
			$procedimiento=8;
			$status_nomina = 0;
		}


		$datos_pnomina_f = $this->Cnmd01->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, nombre, neto_cobrar FROM v_cnmp99_prenomina_negativos WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
		if(!empty($datos_pnomina_f)){
			$procedimiento=9;
			$status_nomina = 0;
		}


		$datos_pnomina_l = $this->Cnmd01->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar FROM v_cnmp99_prenomina_part_mal_conectadas WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
		if(!empty($datos_pnomina_l)){
			$procedimiento=10;
			$status_nomina = 0;
		}


		$datos_pnomina_j = $this->Cnmd01->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, denominacion, neto FROM v_cnmp99_prenomina_neto_orden_pago WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
		if(!empty($datos_pnomina_j)){
			if ($procedimiento==0){$status_nomina=1; $procedimiento=11; $finalizo=1;
			}else{
			$status_nomina=0;}
		}


		$datos_pnomina_k = $this->Cnmd01->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, nombre, forma_pago, neto FROM v_cnmp99_prenomina_forma_pago WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
		if(!empty($datos_pnomina_k)){
		}

        $datos_pnomina_n = $this->Cnmd01->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, tipo, cod_transaccion, transaccion FROM v_cnmp99_prenomina_transa_ocultas WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
		if(!empty($datos_pnomina_n)){
		}

		$datos_pnomina_m = $this->Cnmd01->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, tipo, cod_transaccion, transaccion FROM v_cnmp99_prenomina_escenario_desactivado WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
		if(!empty($datos_pnomina_m)){
		}

		$datos_pnomina_s = $this->Cnmd01->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, nombre, fecha_ingreso FROM v_cnmp99_prenomina_otras_experiencias WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
		if(!empty($datos_pnomina_s)){
		}

	// }

	/** ******* FIN POST *********** */


	if($procedimiento > 0){
		$exe_cnmd01 = 2;
		if($exe_cnmd01 > 1){
			$this->set('procedimiento', $procedimiento);
			$this->set('finalizo', $finalizo);
			$this->set('cod_presi', $cod_presi);
			$this->set('cod_entidad', $cod_entidad);
			$this->set('cod_tipo_inst', $cod_tipo_inst);
			$this->set('cod_inst', $cod_inst);
			$this->set('cod_dep', $cod_dep);
			$this->set('cod_tipo_nomina', $cod_tipo_nomina);

			$this->set('datos_pnomina_a', $datos_pnomina_a);
			$this->set('datos_pnomina_b', $datos_pnomina_b);
			$this->set('datos_pnomina_c', $datos_pnomina_c);
			$this->set('datos_pnomina_d', $datos_pnomina_d);
			$this->set('datos_pnomina_e', $datos_pnomina_e);
			$this->set('datos_pnomina_f', $datos_pnomina_f);
			$this->set('datos_pnomina_g', $datos_pnomina_g);
			$this->set('datos_pnomina_h', $datos_pnomina_h);
			$this->set('datos_pnomina_j', $datos_pnomina_j);
			$this->set('datos_pnomina_k', $datos_pnomina_k);
			$this->set('datos_pnomina_l', $datos_pnomina_l);
			$this->set('datos_pnomina_n', $datos_pnomina_n);
			$this->set('datos_pnomina_m', $datos_pnomina_m);
			$this->set('datos_pnomina_s', $datos_pnomina_s);

		}else{
			$this->set('procedimiento', $procedimiento);
			$this->set('finalizo', $finalizo);
			$this->set('cod_presi', $cod_presi);
			$this->set('cod_entidad', $cod_entidad);
			$this->set('cod_tipo_inst', $cod_tipo_inst);
			$this->set('cod_inst', $cod_inst);
			$this->set('cod_dep', $cod_dep);
			$this->set('cod_tipo_nomina', $cod_tipo_nomina);

			$this->set('datos_pnomina_a', $datos_pnomina_a);
			$this->set('datos_pnomina_b', $datos_pnomina_b);
			$this->set('datos_pnomina_c', $datos_pnomina_c);
			$this->set('datos_pnomina_d', $datos_pnomina_d);
			$this->set('datos_pnomina_e', $datos_pnomina_e);
			$this->set('datos_pnomina_f', $datos_pnomina_f);
			$this->set('datos_pnomina_g', $datos_pnomina_g);
			$this->set('datos_pnomina_h', $datos_pnomina_h);
			$this->set('datos_pnomina_j', $datos_pnomina_j);
			$this->set('datos_pnomina_k', $datos_pnomina_k);
			$this->set('datos_pnomina_l', $datos_pnomina_l);
			$this->set('datos_pnomina_n', $datos_pnomina_n);
			$this->set('datos_pnomina_m', $datos_pnomina_m);
			$this->set('datos_pnomina_s', $datos_pnomina_s);
		}
	}else{
		$this->set('procedimiento', 0);
		$this->set('finalizo', 0);
	}

	}else{
		$this->set('procedimiento', 0);
		$this->set('finalizo', 0);
	}

	$this->render('verificarp_prenomina_print','pdf');

    }// status nomina
    else{
        $this->set('errorMessage','La Corrida definitiva ya fue efectuada.');
    }

} // End Function verificarp_prenomina_print



// ******** LA SIG. FUNCION CHEQUEA PROCESOS PRE Y POST DE LA PRENOMINA *********

function verificarp_prenomina ($cod_tipo_nomina = null, $procesada = 0) {

	$cod_presi     = $this->Session->read('SScodpresi');
	$cod_entidad   = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst      = $this->Session->read('SScodinst');
	$cod_dep       = $this->Session->read('SScoddep');
	$cod_tipo_nomina=$this->Session->read('nomina');
	$this->layout="ajax";

    $procedimiento = 0;
	$finalizo=0;


	$status_actual = $this->Cnmd01->field("status_nomina", $this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);

    if ($status_actual==0 || $status_actual==1){

	$datos_pnomina_a = 0;
	$datos_pnomina_b = 0;
	$datos_pnomina_c = 0;
	$datos_pnomina_d = 0;
	$datos_pnomina_e = 0;
	$datos_pnomina_f = 0;
	$datos_pnomina_g = 0;
	$datos_pnomina_h = 0;
	$datos_pnomina_j = 0;
	$datos_pnomina_k = 0;
	$datos_pnomina_l = 0;
	$datos_pnomina_n = 0;
	$datos_pnomina_m = 0;
	$datos_pnomina_s = 0;

if($cod_tipo_nomina != null){

	/** ******* INICIO PRE *********** */


if($procesada == 0){

	$datos_pnomina_a = $this->Cnmd01->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_entidad_bancaria, banco FROM v_cnmp99_prenomina_banco_canc_nomina WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
	if(!empty($datos_pnomina_a)){
		$procedimiento=1;
		$status_nomina = 0;
	}

		$datos_pnomina_b = $this->Cnmd01->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, tipo, cod_transaccion, transaccion FROM v_cnmp99_prenomina_frecuencia WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
		if(!empty($datos_pnomina_b)){
			$procedimiento=2;
			$status_nomina = 0;
		}


		$datos_pnomina_g = $this->Cnmd01->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, tipo, cod_transaccion, transaccion FROM v_cnmp99_prenomina_asignaciones WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
		if(!empty($datos_pnomina_g)){
			$procedimiento=3;
			$status_nomina = 0;
		}


		$datos_pnomina_h = $this->Cnmd01->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, tipo, cod_transaccion, transaccion FROM v_cnmp99_prenomina_deducciones WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
		if(!empty($datos_pnomina_h)){
			$procedimiento=4;
			$status_nomina = 0;
		}

		$datos_pnomina_c = $this->Cnmd01->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_transaccion, transaccion FROM v_cnmp99_prenomina_banco_canc_transa WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
		if(!empty($datos_pnomina_c)){
			$procedimiento=5;
			$status_nomina = 0;
		}



}
	/** ******* FIN PRE *********** */


// -.-.-.-.-.-.-.-.-.-.-.- .. [PASANDO A PROCESOS POST] ... -.-.-.-.-.-.-.-.-.-.-.----- //

else if($procesada == 1){

	/** ******* INICIO POST *********** */

		$datos_pnomina_c = $this->Cnmd01->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_transaccion, transaccion FROM v_cnmp99_prenomina_banco_canc_transa WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
		if(!empty($datos_pnomina_c)){
			$procedimiento=6;
			$status_nomina = 0;
		}


		$datos_pnomina_d = $this->Cnmd01->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, tipo, cod_transaccion, transaccion FROM v_cnmp99_prenomina_conexion WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
		if(!empty($datos_pnomina_d)){
			$procedimiento=7;
			$status_nomina = 0;
		}


		$datos_pnomina_e = $this->Cnmd01->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar FROM v_cnmp99_prenomina_part_conecta_no_presu WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
		if(!empty($datos_pnomina_e)){
			$procedimiento=8;
			$status_nomina = 0;
		}


		$datos_pnomina_f = $this->Cnmd01->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, nombre, neto_cobrar FROM v_cnmp99_prenomina_negativos WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
		if(!empty($datos_pnomina_f)){
			$procedimiento=9;
			$status_nomina = 0;
		}


		$datos_pnomina_l = $this->Cnmd01->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar FROM v_cnmp99_prenomina_part_mal_conectadas WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
		if(!empty($datos_pnomina_l)){
			$procedimiento=10;
			$status_nomina = 0;
		}


		$datos_pnomina_j = $this->Cnmd01->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, denominacion, neto FROM v_cnmp99_prenomina_neto_orden_pago WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
		if(!empty($datos_pnomina_j)){
			if ($procedimiento==0){$status_nomina=1; $procedimiento=11; $finalizo=1;
			}else{
			$status_nomina=0;}
		}


		$datos_pnomina_k = $this->Cnmd01->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, nombre, forma_pago, neto FROM v_cnmp99_prenomina_forma_pago WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
		if(!empty($datos_pnomina_k)){
		}

		$datos_pnomina_n = $this->Cnmd01->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, tipo, cod_transaccion, transaccion FROM v_cnmp99_prenomina_transa_ocultas WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
		if(!empty($datos_pnomina_n)){
		}

		$datos_pnomina_m = $this->Cnmd01->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, tipo, cod_transaccion, transaccion FROM v_cnmp99_prenomina_escenario_desactivado WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
		if(!empty($datos_pnomina_m)){
		}

		$datos_pnomina_s = $this->Cnmd01->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, nombre, fecha_ingreso FROM v_cnmp99_prenomina_otras_experiencias WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
		if(!empty($datos_pnomina_s)){
		}

	}

	/** ******* FIN POST *********** */


	if($procedimiento > 0){
		echo "<script type='text/javascript'>
				document.getElementById('procesar').disabled = true;
			</script>";
		$sqlup_cnmd01 = "UPDATE cnmd01 SET status_nomina=$status_nomina WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina;
		$exe_cnmd01 = $this->Cnmd01->execute($sqlup_cnmd01); // AQUI ACTUALIZA EL ESTATUS DE LA NOMINA ...
		if($exe_cnmd01 > 1){
			$this->set('procedimiento', $procedimiento);
			$this->set('finalizo', $finalizo);
			$this->set('cod_presi', $cod_presi);
			$this->set('cod_entidad', $cod_entidad);
			$this->set('cod_tipo_inst', $cod_tipo_inst);
			$this->set('cod_inst', $cod_inst);
			$this->set('cod_dep', $cod_dep);
			$this->set('cod_tipo_nomina', $cod_tipo_nomina);

			$this->set('datos_pnomina_a', $datos_pnomina_a);
			$this->set('datos_pnomina_b', $datos_pnomina_b);
			$this->set('datos_pnomina_c', $datos_pnomina_c);
			$this->set('datos_pnomina_d', $datos_pnomina_d);
			$this->set('datos_pnomina_e', $datos_pnomina_e);
			$this->set('datos_pnomina_f', $datos_pnomina_f);
			$this->set('datos_pnomina_g', $datos_pnomina_g);
			$this->set('datos_pnomina_h', $datos_pnomina_h);
			$this->set('datos_pnomina_j', $datos_pnomina_j);
			$this->set('datos_pnomina_k', $datos_pnomina_k);
			$this->set('datos_pnomina_l', $datos_pnomina_l);
			$this->set('datos_pnomina_n', $datos_pnomina_n);
			$this->set('datos_pnomina_m', $datos_pnomina_m);
			$this->set('datos_pnomina_s', $datos_pnomina_s);
		}else{
			$this->set('procedimiento', $procedimiento);
			$this->set('finalizo', $finalizo);
			$this->set('cod_presi', $cod_presi);
			$this->set('cod_entidad', $cod_entidad);
			$this->set('cod_tipo_inst', $cod_tipo_inst);
			$this->set('cod_inst', $cod_inst);
			$this->set('cod_dep', $cod_dep);
			$this->set('cod_tipo_nomina', $cod_tipo_nomina);

			$this->set('datos_pnomina_a', $datos_pnomina_a);
			$this->set('datos_pnomina_b', $datos_pnomina_b);
			$this->set('datos_pnomina_c', $datos_pnomina_c);
			$this->set('datos_pnomina_d', $datos_pnomina_d);
			$this->set('datos_pnomina_e', $datos_pnomina_e);
			$this->set('datos_pnomina_f', $datos_pnomina_f);
			$this->set('datos_pnomina_g', $datos_pnomina_g);
			$this->set('datos_pnomina_h', $datos_pnomina_h);
			$this->set('datos_pnomina_j', $datos_pnomina_j);
			$this->set('datos_pnomina_k', $datos_pnomina_k);
			$this->set('datos_pnomina_l', $datos_pnomina_l);
			$this->set('datos_pnomina_n', $datos_pnomina_n);
			$this->set('datos_pnomina_m', $datos_pnomina_m);
			$this->set('datos_pnomina_s', $datos_pnomina_s);

			$this->set('errorMessage',"El estatus de la n&oacute;mina no pudo ser actualizado a la modalidad Cierre[$status_nomina]...");
		}
	}else if($procedimiento == 0){
		$this->set('procedimiento', 0);
		$this->set('finalizo', 0);
		echo "<script type='text/javascript'>
				document.getElementById('procesar').disabled = false;
			</script>";
		}
	}else{
		$this->set('procedimiento', 0);
		$this->set('finalizo', 0);
		$this->set('errorMessage', "Por favor seleccione una n&oacute;mina...");
		}

    }// status nomina
    else{
        $this->set('errorMessage','La Corrida definitiva ya fue efectuada.');
    }

} // End Function verificarp_prenomina


function procesar () {
	set_time_limit(0);
	$hora_inicio = date("h:i:s a");
	$this->set('hora_inicio',$hora_inicio);
	$this->layout="ajax";
	$cod_presi     = $this->Session->read('SScodpresi');
	$cod_entidad   = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst      = $this->Session->read('SScodinst');
	$cod_dep       = $this->Session->read('SScoddep');

         $vacios=array();
		 $i=0;
		 foreach($this->data["cnmp99_prenomina"] as $k=>$v){
              if(empty($v)){
                   $vacios[]=$k;
                   $i++;
              }
		 }
	if($i==0 && isset($this->data["cnmp99_prenomina"]["frecuencia_pago"])){
		 $datos=$this->data["cnmp99_prenomina"];
         $cod_tipo_nomina      = $datos["cod_tipo_nomina"];
         $numero_nomina        = $datos["numero_nomina"];
         $periodo_desde        = $this->Cfecha($datos["desde_periodo"],"A-M-D");
         $periodo_hasta        = $this->Cfecha($datos["hasta_periodo"],"A-M-D");
         $cantidad_pagos       = $datos["cantidad_pagos"];
         $correspondientes     = $datos["correspondientes"];
         $frecuencia_pago      = $datos["frecuencia_pago"];
         if(isset($this->data["cnmp99_prenomina"]["modalidad"])){
         	$modalidad_pago      = $datos["modalidad"];         	
         }else{
         	$modalidad_pago      = 2;
     	 }
     	 
         $mes = explode('-',$periodo_desde);

	        $sql_dias_cnmd01= $this->Cnmd01->execute(" SELECT dias_laborables FROM cnmd01 WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);

         //La pre-nómina debe correr unicamente cuando el campo status_nomina de la tabla cnmd01 tenga los siguientes valores 0 Cierre de nómina 1 Pre-nomina
            $status_actual = $this->Cnmd01->field("status_nomina", $this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);

			//VERIFICACION QUE LAS PARTIDAS NO ESTEN EN NEGATIVO
         $presupuesto_negativo=$this->costo_presupuestario_p2->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and ano=".divide_fecha($desde_periodo,'ANO'));
         	$negativo = 0;
         /*foreach ($presupuesto_negativo as $pnegativo) {
				if($pnegativo["costo_presupuestario_p2"]["diferencia"]<0){
         			$negativo++;
         		}
         	}*/

            if (($status_actual==0 || $status_actual==1) && $negativo==0){
         		$sql_update_cnmd01="UPDATE cnmd01 SET status_nomina=1, numero_nomina=".$numero_nomina." , cantidad_pagos=".$cantidad_pagos." , frecuencia_pago=".$frecuencia_pago." , periodo_desde='".$periodo_desde."' , periodo_hasta='".$periodo_hasta."', correspondiente='".$correspondientes."', modalidad_pago=".$modalidad_pago." WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina;
	        	$this->Cnmd01->execute($sql_update_cnmd01);

		         //Limpiar las siguientes tablas según el tipo de nómina:
		         $this->cnmd07_calculo_aguinaldos->execute("DELETE FROM cnmd07_calculo_aguinaldos WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
		         $this->cnmd07_calculo_bonovaca->execute("DELETE FROM cnmd07_calculo_bonovaca WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);

		         //verificar si existe los numeros de lunes que existen por mes
		         $clunes=$this->cnmd09_lunes_ejercicio->findCount("ano=".divide_fecha($periodo_desde,'ANO')." and mes=".divide_fecha($periodo_desde,'MES'));
		         $numero_lunes=$clunes!=0?$this->cnmd09_lunes_ejercicio->field("numero_lunes"," ano=".divide_fecha($periodo_desde,'ANO')." and mes=".divide_fecha($periodo_desde,'MES'),null):0;

		         //verificar la incidiencia del sueldo sugerido
		         $csueldo_sugerido=$this->cnmd09_incidencia_sueldo_sugerido->findCount($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
		         $incidencia_sueldo_sugerido=$csueldo_sugerido!=0?$this->cnmd09_incidencia_sueldo_sugerido->field("sueldo_sugerido",$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina,null):0;

	             //bajar los registros de las tablas cnmd07_transacciones_viadiskette y cnmd07_transacciones_suspendidas hacia la tabla cnmd07_transacciones_actuales...
	             $parametros_bajar=$cod_presi.",".$cod_entidad.",".$cod_tipo_inst.",".$cod_inst.",".$cod_dep.",".$cod_tipo_nomina;
	             $parametros_bajar_fecha=$cod_presi.",".$cod_entidad.",".$cod_tipo_inst.",".$cod_inst.",".$cod_dep.",".$cod_tipo_nomina.",'".$periodo_desde."','".$periodo_hasta."'";
	             $this->Cnmd01->execute("SELECT bajar_trans_viadiskette_trans_actuales(".$parametros_bajar.");");
	             $this->Cnmd01->execute("SELECT bajar_trans_suspen_trans_actuales(".$parametros_bajar.");");
	             $this->Cnmd01->execute("SELECT bajar_trans_prenomina_nopagounico(".$parametros_bajar.");");
	             $this->Cnmd01->execute("SELECT bajar_trans_actuales_prenomina(".$parametros_bajar_fecha.");");
             	 $this->Cnmd01->execute("SELECT bajar_trans_prenomina_actuales(".$parametros_bajar_fecha.");");


             	$this->Cnmd01->execute("DELETE FROM cnmd07_transacciones_actuales_faov_tmp WHERE  ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina."");

	             //verificacion de fechas de contratos
	             $fc_sql="SELECT count(*) as cantidad FROM  cnmd06_fichas_clasi_personal  WHERE ".$this->SQLCA()." AND cod_tipo_nomina=".$cod_tipo_nomina."   and fecha_terminacion_contrato!='1900-01-01'  AND fecha_terminacion_contrato > '".$periodo_desde."' AND fecha_terminacion_contrato <= '".$periodo_hasta."'";
	             $rs_fc1=$this->Cnmd01->execute($fc_sql);
             	if($rs_fc1[0][0]['cantidad']!=0){
                	 $fc_sql="SELECT * FROM  cnmd06_fichas_clasi_personal  WHERE ".$this->SQLCA()." AND cod_tipo_nomina=".$cod_tipo_nomina."   and fecha_terminacion_contrato!='1900-01-01'  AND fecha_terminacion_contrato > '".$periodo_desde."' AND fecha_terminacion_contrato <= '".$periodo_hasta."'";
                 	$rs_fc2=$this->Cnmd01->execute($fc_sql);
                 	foreach($rs_fc2 as $rs1){
                 		$c=$this->cnmd09_dias_trabajados_ingreso_egreso->findCount($this->SQLCA()." AND cod_tipo_nomina=".$rs1[0]['cod_tipo_nomina']." AND cod_cargo=".$rs1[0]['cod_cargo']." AND cod_ficha=".$rs1[0]['cod_ficha']);
	                 	if($rs1[0]['fecha_terminacion_contrato']==$periodo_hasta){
	                 	    $dias = $sql_dias_cnmd01[0][0]['dias_laborables'];
	                 	}else{
	                 		$dias=restar_fechas($periodo_desde, $rs1[0]['fecha_terminacion_contrato']);
	                 	}

	                 	if($c!=0){
	                 		$sql_up="UPDATE cnmd09_dias_trabajados_ingreso_egreso SET dias=$dias  WHERE ".$this->SQLCA()." AND cod_tipo_nomina=".$rs1[0]['cod_tipo_nomina']." AND cod_cargo=".$rs1[0]['cod_cargo']." AND cod_ficha=".$rs1[0]['cod_ficha'];
	                 	}else{
	                 		$sql_up="INSERT INTO cnmd09_dias_trabajados_ingreso_egreso(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina,cod_cargo, cod_ficha, dias)
	                                 VALUES (".$rs1[0]['cod_presi'].",".$rs1[0]['cod_entidad'].", ".$rs1[0]['cod_tipo_inst'].",".$rs1[0]['cod_inst'].",".$rs1[0]['cod_dep'].", ".$rs1[0]['cod_tipo_nomina'].", ".$rs1[0]['cod_cargo'].", ".$rs1[0]['cod_ficha'].", ".$dias.");";
	                 	}
                 		$this->cnmd09_dias_trabajados_ingreso_egreso->execute($sql_up);
                 	}
				}

					// TERMINACIÓN DE CONTRATO (NO ELIMINAR)
					/*
					             $fc_sqla="SELECT count(*) as cantidad FROM  cnmd06_fichas  WHERE ".$this->SQLCA()." AND cod_tipo_nomina=".$cod_tipo_nomina."   and fecha_terminacion_contrato!='1900-01-01'  AND fecha_terminacion_contrato < '".$periodo_desde."'";
					             $rs_fc1a=$this->Cnmd01->execute($fc_sqla);
					             if($rs_fc1a[0][0]['cantidad']){
					                 $fc_sqla="SELECT * FROM  cnmd06_fichas  WHERE ".$this->SQLCA()." AND cod_tipo_nomina=".$cod_tipo_nomina."   and fecha_terminacion_contrato!='1900-01-01'  AND fecha_terminacion_contrato < '".$periodo_desde."'";
					                 $rs_fc2a=$this->Cnmd01->execute($fc_sqla);
					                 foreach($rs_fc2a as $rs1a){
					                 		$sql_up2="UPDATE cnmd06_fichas SET condicion_actividad=5  WHERE ".$this->SQLCA()." AND cod_tipo_nomina=".$rs1a[0]['cod_tipo_nomina']." AND cod_cargo=".$rs1a[0]['cod_cargo']." AND cod_ficha=".$rs1a[0]['cod_ficha'];
					                    	$this->cnmd09_dias_trabajados_ingreso_egreso->execute($sql_up2);
					                 }
					             }
					*/

             	$parametros_prenomina=$cod_presi.",".$cod_entidad.",".$cod_tipo_inst.",".$cod_inst.",".$cod_dep.",".$cod_tipo_nomina.",'".$periodo_desde."','".$periodo_hasta."'";
             	$parametros_revision=$cod_presi.",".$cod_entidad.",".$cod_tipo_inst.",".$cod_inst.",".$cod_dep.",".$cod_tipo_nomina;
             	$parametros_revision_cantidad_pagos=$cod_presi.",".$cod_entidad.",".$cod_tipo_inst.",".$cod_inst.",".$cod_dep.",".$cod_tipo_nomina.",".$cantidad_pagos;
                $parametros_elimina_ap=$cod_presi.",".$cod_entidad.",".$cod_tipo_inst.",".$cod_inst.",".$cod_dep.",".$cod_tipo_nomina;



                // CREAR ESTRUCTURA PARA PAGINAR LA EJECUCION DE LA PRENOMINA
                $LIMITE = 50;
               	//$SQL_PAGINAR_PRENOMINA = "SELECT count(*) as cantidad FROM cnmd06_fichas_clasi_personal WHERE cod_presi=$cod_presi AND cod_entidad=$cod_entidad AND cod_tipo_inst=$cod_tipo_inst AND cod_inst=$cod_inst AND cod_dep=$cod_dep AND cod_tipo_nomina=$cod_tipo_nomina OR (clasificacion_personal=5 AND fecha_terminacion_contrato > '$periodo_hasta' AND cod_tipo_nomina=$cod_tipo_nomina)";
             	$SQL_PAGINAR_PRENOMINA = "SELECT count(*) as cantidad FROM cnmd06_fichas_clasi_personal WHERE cod_presi=$cod_presi AND cod_entidad=$cod_entidad AND cod_tipo_inst=$cod_tipo_inst AND cod_inst=$cod_inst AND cod_dep=$cod_dep AND cod_tipo_nomina=$cod_tipo_nomina";
				$SPP=$this->Cnmd01->execute($SQL_PAGINAR_PRENOMINA);
				$cantidad_paginas = $SPP[0][0]['cantidad'];
				$this->set('cantidad_de_registros',$cantidad_paginas);
				$Tpag = (int)ceil($cantidad_paginas/$LIMITE);

				if($Tpag == 0){
					$Tpag = 1;
				}

			     $this->set("total_paginas",$Tpag);
                 $this->set("pagina",1);
                 $this->set("limite",$LIMITE);
                 $this->set("cod_presi",$cod_presi);
                 $this->set("cod_entidad",$cod_entidad);
                 $this->set("cod_tipo_inst",$cod_tipo_inst);
                 $this->set("cod_inst",$cod_inst);
                 $this->set("cod_dep",$cod_dep);
                 $this->set("cod_tipo_nomina",$cod_tipo_nomina);
                 $this->set("cantidad_pagos",$cantidad_pagos);
                 $this->set("periodo_desde",$periodo_desde);
                 $this->set("periodo_hasta",$periodo_hasta);

         	}
         	else
         	{
         		if($negativo!=0)
         		{
	        		$this->set('errorMessage','Existen '. $negativo . ' partidas en negativo. Revise el costo presupuesto');
         		}else{
	        		$this->set('errorMessage','La Corrida definitiva ya fue efectuada.');
         		}
         	}
	}else{
         $this->set('errorMessage','Hay campos sin llenar, Verifique el formulario');
	}
}//fin procesar


function procesar_prenomina_por_parte ($limite=null,$page=null,$total=null,$cod_presi=null,$cod_entidad=null,$cod_tipo_inst=null,$cod_inst=null,$cod_dep=null,$cod_tipo_nomina=null,$periodo_desde=null,$periodo_hasta=null,$cantidad_pagos=null) {
   $this->layout="ajax";

	if(isset($page) && isset($total)){
		$parametros_prenomina=$cod_presi.",".$cod_entidad.",".$cod_tipo_inst.",".$cod_inst.",".$cod_dep.",".$cod_tipo_nomina.",'".$periodo_desde."','".$periodo_hasta."'";
		$parametros_revision=$cod_presi.",".$cod_entidad.",".$cod_tipo_inst.",".$cod_inst.",".$cod_dep.",".$cod_tipo_nomina;
		$parametros_revision_cantidad_pagos=$cod_presi.",".$cod_entidad.",".$cod_tipo_inst.",".$cod_inst.",".$cod_dep.",".$cod_tipo_nomina.",".$cantidad_pagos;
		$parametros_elimina_ap=$cod_presi.",".$cod_entidad.",".$cod_tipo_inst.",".$cod_inst.",".$cod_dep.",".$cod_tipo_nomina;
		$PAGINA = $this->devolver_pagina ($page,$limite);
		$ffunc_prenomina = 0;
		//var_dump("SELECT proceso_prenomina_paginada($parametros_prenomina,$PAGINA,$limite);");exit();
		// PRIMER PROCESO
		$func_prenomina2=$this->Cnmd01->execute("SELECT proceso_parte_individual($parametros_prenomina);");
		$func_prenomina5=$this->Cnmd01->execute("SELECT proceso_parte_individual_cantidad_corresponda($parametros_prenomina);");
		$func_prenomina3=$this->Cnmd01->execute("SELECT proceso_parte_individual2($parametros_prenomina);");
		$func_prenomina3_ded=$this->Cnmd01->execute("SELECT proceso_parte_individual2_ded($parametros_prenomina);");
		$func_prenomina4=$this->Cnmd01->execute("SELECT proceso_parte_individual3($parametros_prenomina);");
		$func_prenomina6=$this->Cnmd01->execute("SELECT proceso_parte_individual_cantidad_corresponda_ded($parametros_prenomina);");
		$func_prenomina6=$this->Cnmd01->execute("SELECT proceso_parte_individual_ca_ho_ca($parametros_prenomina);");
		// SEGUNDO PROCESO
		$func_prenomina2=$this->Cnmd01->execute("SELECT proceso_parte_individual($parametros_prenomina);");
		$func_prenomina5=$this->Cnmd01->execute("SELECT proceso_parte_individual_cantidad_corresponda($parametros_prenomina);");
		$func_prenomina3=$this->Cnmd01->execute("SELECT proceso_parte_individual2($parametros_prenomina);");
		$func_prenomina3_ded=$this->Cnmd01->execute("SELECT proceso_parte_individual2_ded($parametros_prenomina);");
		$func_prenomina4=$this->Cnmd01->execute("SELECT proceso_parte_individual3($parametros_prenomina);");
		$func_prenomina6=$this->Cnmd01->execute("SELECT proceso_parte_individual_cantidad_corresponda_ded($parametros_prenomina);");
		$func_prenomina6=$this->Cnmd01->execute("SELECT proceso_parte_individual_ca_ho_ca($parametros_prenomina);");

		$func_prenomina=$this->Cnmd01->execute("SELECT proceso_prenomina_paginada($parametros_prenomina,$PAGINA,$limite);");

    if($func_prenomina>0){
    	$ffunc_prenomina++;
	  }
    unset($func_prenomina);

    if($page==1){
    	//echo "SELECT proceso_prenomina_paginada($parametros_prenomina,$PAGINA,$limite);";
    }

    if($page==$total){
    	if($ffunc_prenomina>0){
	    	$func_prenomina_faltas=$this->Cnmd01->execute("SELECT proceso_cuando_hay_faltas($parametros_revision);");
	    	$func_prenomina_faltas=$this->Cnmd01->execute("SELECT proceso_ingreso_egreso($parametros_revision);");
	    	$func_prenomina_revision=$this->Cnmd01->execute("SELECT revision_saldo_transacciones($parametros_revision);");

				//NO ELIMINAR
			    //$func_bono_vac = $this->Cnmd01->execute("SELECT cnmp99_bono_vacacional($parametros_revision);"); // ACTUALIZA ASIGNACIONES Y DEDUCCIONES DE VACACIONES O BONO VACACIONAL

        $this->dias_sueldo_bono_vaca($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $cod_tipo_nomina);

				$func_prenominafaov=$this->Cnmd01->execute("SELECT proceso_prenomina_1faov($parametros_prenomina);");
				$_SESSION['_recalcular_faov'] =0;
				$_SESSION['_recalcular_faov_ap'] =0;
				$this->_recalcular_faov ($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,$cod_tipo_nomina,1,3);
				$this->_recalcular_faov_ap ($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,$cod_tipo_nomina,2,402);
				$this->_recalcular_faov ($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,$cod_tipo_nomina,4,452);
				$this->_recalcular_faov_ap ($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,$cod_tipo_nomina,3,410);
				$_SESSION['_recalcular_faov'] =0;
				$_SESSION['_recalcular_faov_ap'] =0;

				$parametros_frecue=$cod_presi.",".$cod_entidad.",".$cod_tipo_inst.",".$cod_inst.",".$cod_dep.",".$cod_tipo_nomina;
				$E = $this->Cnmd01->execute("SELECT elimina_transacciones_nopagounico($parametros_frecue);");
				$func_prenomina_revision_cantidad_pagos=$this->Cnmd01->execute("SELECT revision_cantidad_pagos($parametros_revision_cantidad_pagos);");

        if($cod_presi==1 && $cod_entidad==11 && $cod_tipo_inst==30 && $cod_inst==11 && $cod_dep==1 && ($cod_tipo_nomina==300 || $cod_tipo_nomina==301 || $cod_tipo_nomina==302 || $cod_tipo_nomina==304)){
           	//aumento para la nominas especificas
	       		$actualizar_monto = "UPDATE cnmd07_transacciones_actuales SET monto_cuota=monto_cuota+0.05 WHERE cod_presi=1 and cod_entidad=11 and cod_tipo_inst=30 and cod_inst=11 and cod_dep=1 and cod_tipo_nomina=$cod_tipo_nomina and cod_tipo_transaccion=1 and cod_transaccion=101";
	       		$this->Cnmd01->execute($actualizar_monto);
        }

				//echo $func_elimina_ap_incompletas;
				$this->set('Message_existe','Prenomina Procesada Exitosamente');

				$data3_count=$this->Cnmd01->execute("SELECT count(*) as cantidad FROM v_cnmd06_fichas_asig_ded WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and asignaciones<deducciones");
				$data3_count[0][0]['cantidad'] = (int) $data3_count[0][0]['cantidad'];

				if($data3_count[0][0]['cantidad']!=0){
					$data3=$this->Cnmd01->execute("SELECT * FROM v_cnmd06_fichas_asig_ded WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina."   and asignaciones<deducciones ORDER BY cod_cargo,cod_ficha,cedula_identidad ASC");
					$this->set('data_negativos',$data3);

					$sql_update_cnmd01="UPDATE cnmd01 SET status_nomina=0 WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina;
					$this->Cnmd01->execute($sql_update_cnmd01);
					$this->set('errorMessage','Prenomina Procesada Exitosamente - Existen Deducciones mayor a las Asignaciones la lista');
				}
        
        //1.Primas 2.Compensaciones 3.Bonos
        $campo_update = array(1=>'primas',2=>'compensaciones',3=>'bonos');
        for($actualizar_campos_xx=1;$actualizar_campos_xx<=3;$actualizar_campos_xx++){
          $DATA_UPDATE_PBC=$this->Cnmd01->execute("SELECT a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.cod_tipo_nomina,a.cod_cargo,a.cod_ficha,sum(a.monto_cuota) as monto,b.tipo_asignacion
								FROM
								  cnmd07_transacciones_actuales a,cnmd03_transacciones b
								WHERE
								  a.cod_tipo_transaccion = b.cod_tipo_transaccion AND
								  a.cod_transaccion = b.cod_transaccion and
								  b.tipo_asignacion = $actualizar_campos_xx and
								  a.cod_presi=$cod_presi and
								  a.cod_entidad=$cod_entidad and
								  a.cod_tipo_inst=$cod_tipo_inst and
								  a.cod_inst=$cod_inst and
								  a.cod_dep=$cod_dep and
								  a.cod_tipo_nomina=$cod_tipo_nomina
								GROUP BY
								  a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.cod_tipo_nomina,a.cod_cargo,a.cod_ficha,b.tipo_asignacion;");
					if(count($DATA_UPDATE_PBC)>0){
						foreach($DATA_UPDATE_PBC as $PBC){
							$cod_cargo = $PBC[0]['cod_cargo'];
							$cod_ficha = $PBC[0]['cod_ficha'];
							$monto     = $PBC[0]['monto'];
							$campo_update_c = $campo_update[$actualizar_campos_xx];
							$this->Cnmd01->execute("UPDATE cnmd05 SET $campo_update_c = $monto WHERE cod_presi=$cod_presi and  cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha");
						}//fin foreach $DATA_UPDATE_PBC
					}//fin $DATA_UPDATE_PBC
        }//fin for


				echo "<script language='JavaScript' type='text/javascript'>
						ver_documento('/cnmp99_prenomina/verificarp_prenomina/'+$cod_tipo_nomina+'/1','verificarp_prenomina');
					</script>";


	    }else{
	      $this->set('errorMessage','No se pudo ejecutar la Prenomina');
	    }

	                $this->set('ejecutar',false);
        }else{
        	     $this->set("total_paginas",$total);
                 $this->set("pagina",$page+1);
                 $this->set("limite",$limite);
                 $this->set("cod_presi",$cod_presi);
                 $this->set("cod_entidad",$cod_entidad);
                 $this->set("cod_tipo_inst",$cod_tipo_inst);
                 $this->set("cod_inst",$cod_inst);
                 $this->set("cod_dep",$cod_dep);
                 $this->set("cod_tipo_nomina",$cod_tipo_nomina);
                 $this->set("cantidad_pagos",$cantidad_pagos);
                 $this->set("periodo_desde",$periodo_desde);
                 $this->set("periodo_hasta",$periodo_hasta);
        	     //$this->set('pagina',$page+1);
        	     $this->set('ejecutar',true);
        }

  }

}//fin funcion procesar_prenomina_por_parte






function devolver_pagina ($page,$limite) {
    if ($page > 1) {
			$offset = ($page - 1) * $limite;
	}else{
			$offset = 0;
	}
    return $offset;
}//fin funcion devolver_limite





//BUSCA LOS DIAS A COBRAR PARA ACTUALIZAR LA TRANSACCION SUELDO O SALARIO

function dias_sueldo_bono_vaca ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $cod_tipo_nomina){

$transaccion_sueldo   = $this->Cnmd01->execute("SELECT * FROM cnmd07_transacciones_actuales WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and cod_tipo_transaccion=1 and cod_transaccion=1;");
	foreach($transaccion_sueldo as $trans_sueldo){
$cod_cargo  = $trans_sueldo[0]['cod_cargo'];
$cod_ficha  = $trans_sueldo[0]['cod_ficha'];

		$dias_cobro_trab = $this->Cnmd01->execute("SELECT devolver_dias_t_i_e($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $cod_tipo_nomina, $cod_cargo, $cod_ficha) as dias_cobro;");
		$dias_cobro = $dias_cobro_trab[0][0]['dias_cobro'];
		if ($dias_cobro==0){
				$dias_cobro_trab = $this->Cnmd01->execute("SELECT devolver_dias_faltas($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $cod_tipo_nomina, $cod_cargo, $cod_ficha) as dias_cobro;");
				$dias_cobro = $dias_cobro_trab[0][0]['dias_cobro'];
				if ($dias_cobro==0){
						$dias_cobro_trab = $this->Cnmd01->execute("SELECT devolver_dias_cobro_cnmd01($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $cod_tipo_nomina) as dias_cobro;");
						$dias_cobro = $dias_cobro_trab[0][0]['dias_cobro'];
				}
			}
$actual_sueldo = $this->Cnmd01->execute("UPDATE cnmd07_transacciones_actuales SET dias_horas=$dias_cobro WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cod_tipo_transaccion=1 and cod_transaccion=1;");
	}

//ACTUALIZA EL CAMPO DIAS_HORAS DE AQUELLOS CAMPOS REGISTRADOS MANUALMENTE
$transaccion_vacia   = $this->Cnmd01->execute("SELECT * FROM cnmd07_transacciones_actuales WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and (dias_horas is null OR dias_horas=0);");
	foreach($transaccion_vacia as $trans_vacia){
$cod_cargo            = $trans_vacia[0]['cod_cargo'];
$cod_ficha            = $trans_vacia[0]['cod_ficha'];
$cod_tipo_transaccion = $trans_vacia[0]['cod_tipo_transaccion'];
$cod_transaccion      = $trans_vacia[0]['cod_transaccion'];
$dias_cobro=1;
$actual_vacia = $this->Cnmd01->execute("UPDATE cnmd07_transacciones_actuales SET dias_horas=$dias_cobro WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cod_tipo_transaccion=$cod_tipo_transaccion and cod_transaccion=$cod_transaccion;");
	}


//BORRA LAS TRANSACCIONES CON CUOTA EN CERO
$transaccion_eliminar   = $this->Cnmd01->execute("SELECT * FROM cnmd07_transacciones_actuales WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and monto_cuota=0;");
	foreach($transaccion_eliminar as $trans_eliminar){
$cod_cargo            = $trans_eliminar[0]['cod_cargo'];
$cod_ficha            = $trans_eliminar[0]['cod_ficha'];
$cod_tipo_transaccion = $trans_eliminar[0]['cod_tipo_transaccion'];
$cod_transaccion      = $trans_eliminar[0]['cod_transaccion'];
$actual_eliminar = $this->Cnmd01->execute("DELETE from cnmd07_transacciones_actuales WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cod_tipo_transaccion=$cod_tipo_transaccion and cod_transaccion=$cod_transaccion;");
	}


//LIMPIA EL ARCHIVO TEMPORAL DE BONO VACACIONAL
$bono_vaca_temporal = $this->Cnmd01->execute("SELECT * FROM cnmd16_vacaciones_bonos_temporal WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina;");
if(!empty($bono_vaca_temporal)){
	foreach($bono_vaca_temporal as $bono_temporal){
$cod_cargo          = $bono_temporal[0]['cod_cargo'];
$cod_ficha          = $bono_temporal[0]['cod_ficha'];
$cedula_identidad   = $bono_temporal[0]['cedula_identidad'];
$ano                = $bono_temporal[0]['ano'];
$numero             = $bono_temporal[0]['numero'];
$eliminar_temporal  = $this->Cnmd01->execute("DELETE from cnmd16_vacaciones_bonos_temporal WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cedula_identidad=$cedula_identidad and ano=$ano and numero=$numero;");
$eliminar_permanen  = $this->Cnmd01->execute("DELETE from cnmd16_vacaciones_bonos_perma WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cedula_identidad=$cedula_identidad and ano=$ano and numero=$numero;");
		}
	}


// ACTUALIZA BONO VACACIONAL AL EXPEDIENTE
$bono_vaca_nomina   = $this->Cnmd01->execute("SELECT * FROM cnmd07_transacciones_actuales WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and cod_tipo_transaccion=1 and cod_transaccion=3;");
if(!empty($bono_vaca_nomina)){
	foreach($bono_vaca_nomina as $bono_nomina){
$cod_cargo   = $bono_nomina[0]['cod_cargo'];
$cod_ficha   = $bono_nomina[0]['cod_ficha'];
$fecha_tran  = $bono_nomina[0]['fecha_transaccion'];
$dias_vac    = $bono_nomina[0]['dias_horas'];
$ano_vac     = substr($fecha_tran, 0, 4);


$cedula  = $this->Cnmd01->execute("SELECT cedula_identidad FROM cnmd06_fichas WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha;");
if(!empty($cedula)){
$cedula_id = $cedula[0][0]['cedula_identidad'];
}

$sql_max_numero  = "SELECT count(*) as cantidad from cnmd16_vacaciones_bonos_perma WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cedula_identidad=$cedula_id;";
$max_numero=$this->Cnmd01->execute($sql_max_numero);
if(!empty($max_numero)){
$sig_numero = $max_numero[0][0]['cantidad'];
}

$sql_salario_mensual_va = "SELECT f_cnmp16_devolver_salario_asig_vision($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $cod_tipo_nomina, $cod_cargo, $cod_ficha, 1, 2) AS salario FROM cnmd06_fichas a WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha AND cedula_identidad=$cedula_id;";
$salario_mensual_va = $this->Cnmd01->execute($sql_salario_mensual_va);
if(!empty($salario_mensual_va)){
		$sal_mensual_va = $salario_mensual_va[0][0]['salario'];
		$sueldo_diario_va = ($sal_mensual_va/30);
	}

	if ($sal_mensual_va==null){
		$sal_mensual_va=0;
		$sueldo_diario_va=0;
	}


$sql_salario_mensual_bv = "SELECT f_cnmp16_devolver_salario_asig_vision($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $cod_tipo_nomina, $cod_cargo, $cod_ficha, 1, 3) AS salario_bono_vaca FROM cnmd06_fichas a WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha AND cedula_identidad=$cedula_id;";
$salario_mensual_bv = $this->Cnmd01->execute($sql_salario_mensual_bv);
if(!empty($salario_mensual_bv)){
		$sal_mensual_bv = $salario_mensual_bv[0][0]['salario_bono_vaca'];
		$sal_diario_bv = ($sal_mensual_bv/30);
	}

	if ($sal_mensual_bv==null){
		$sal_mensual_bv=0;
		$sal_diario_bv=0;
	}

			if ($sal_mensual_bv!=0 && $sal_mensual_va==0){
				$sal_mensual_va=$sal_mensual_bv;
				$sueldo_diario_va=$sal_diario_bv;
			}



//echo "<script>alert('Transacciones  ".$ano_vac."  ".$fecha_tran."  ".$dias_vac."')</script>";


$cedula_identidad = $cedula_id;
$ano = $ano_vac;
$numero = ($sig_numero+1);
$fecha_inicio = $fecha_tran;
$fecha_termina = $fecha_tran;
$periodo_desde = ($ano_vac-1);
$periodo_hasta = $ano_vac;
$dias_inhabiles = 0;
$numero_lunes = 0;
$fecha_calculo = $fecha_tran;
$cantidad_vacaciones = 0;
$salario_mensual = $sal_mensual_va;
$salario_diario = $sueldo_diario_va;
$dias_vacaciones = 0;
$salario_diario_vaca = 0;
$dias_adicio_vacaciones = 0;
$dias_bonificacion = 0;
$sueldo_diario_bonifica = 0;
$dias_bono_vacacional = $dias_vac;
$salario_diario_bono_vac = $sal_diario_bv;
$dias_adicio_bono_vaca = 0;
$dias_sab_dom_fer = 0;
$porc_seguro_social = 0;
$monto_seguro_social = 0;
$porc_paro_forzoso = 0;
$monto_paro_forzoso = 0;
$porc_fondo_ahorro = 0;
$monto_fondo_ahorro = 0;
$porc_fondo_jub = 0;
$monto_fondo_jub = 0;
$porc_cuota_sindical = 0;
$cuota_sindical = 0;
$monto_cuota_sindical = 0;
$porc_caja_ahorro = 0;
$monto_caja_ahorro = 0;
$cuota_prestamo = 0;
$monto_prestamo_caja = 0;
$cuota_vivienda = 0;
$monto_credito_vivienda = 0;
$porc_aporte_seguro_social = 0;
$monto_aporte_seguro_social = 0;
$porc_aporte_paro_forzoso = 0;
$monto_aporte_paro_forzoso = 0;
$porc_aporte_fondo_ahorro = 0;
$monto_aporte_fondo_ahorro = 0;
$porc_aporte_fondo_jub = 0;
$monto_aporte_fondo_jub = 0;
$porc_aporte_ahorro = 0;
$monto_aporte_ahorro = 0;
$observaciones = 'NO TIENE BONO VACACIONAL PENDIENTE';
$tipo_operacion = 3;



$sql_insertar_temporal  = "INSERT INTO cnmd16_vacaciones_bonos_temporal (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep,
cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, ano, numero, fecha_inicio, fecha_termina, periodo_desde, periodo_hasta, dias_inhabiles,
numero_lunes, fecha_calculo, cantidad_vacaciones, salario_mensual, salario_diario, dias_vacaciones, salario_diario_vaca, dias_adicio_vacaciones,
dias_bonificacion, sueldo_diario_bonifica, dias_bono_vacacional, salario_diario_bono_vac, dias_adicio_bono_vaca, dias_sab_dom_fer, porc_seguro_social,
monto_seguro_social, porc_paro_forzoso, monto_paro_forzoso, porc_fondo_ahorro, monto_fondo_ahorro, porc_fondo_jub, monto_fondo_jub, porc_cuota_sindical,
cuota_sindical, monto_cuota_sindical, porc_caja_ahorro, monto_caja_ahorro, cuota_prestamo, monto_prestamo_caja, cuota_vivienda, monto_credito_vivienda,
porc_aporte_seguro_social, monto_aporte_seguro_social, porc_aporte_paro_forzoso, monto_aporte_paro_forzoso, porc_aporte_fondo_ahorro,
monto_aporte_fondo_ahorro, porc_aporte_fondo_jub, monto_aporte_fondo_jub, porc_aporte_ahorro, monto_aporte_ahorro, observaciones, tipo_operacion)
VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep','$cod_tipo_nomina', '$cod_cargo', '$cod_ficha', '$cedula_identidad', '$ano',
'$numero', '$fecha_inicio', '$fecha_termina', '$periodo_desde', '$periodo_hasta', '$dias_inhabiles', '$numero_lunes', '$fecha_tran', '$cantidad_vacaciones',
'$salario_mensual', '$salario_diario', '$dias_vacaciones', '$salario_diario_vaca', '$dias_adicio_vacaciones', '$dias_bonificacion', '$sueldo_diario_bonifica',
'$dias_bono_vacacional', '$salario_diario_bono_vac', '$dias_adicio_bono_vaca', '$dias_sab_dom_fer', '$porc_seguro_social', '$monto_seguro_social',
'$porc_paro_forzoso', '$monto_paro_forzoso', '$porc_fondo_ahorro', '$monto_fondo_ahorro', '$porc_fondo_jub', '$monto_fondo_jub', '$porc_cuota_sindical',
'$cuota_sindical', '$monto_cuota_sindical', '$porc_caja_ahorro', '$monto_caja_ahorro', '$cuota_prestamo', '$monto_prestamo_caja', '$cuota_vivienda',
'$monto_credito_vivienda', '$porc_aporte_seguro_social', '$monto_aporte_seguro_social', '$porc_aporte_paro_forzoso', '$monto_aporte_paro_forzoso',
'$porc_aporte_fondo_ahorro', '$monto_aporte_fondo_ahorro', '$porc_aporte_fondo_jub', '$monto_aporte_fondo_jub', '$porc_aporte_ahorro','$monto_aporte_ahorro',
'$observaciones','$tipo_operacion');";
$insertar_temporal  = $this->Cnmd01->execute($sql_insertar_temporal);


$sql_insertar_permanente  = "INSERT INTO cnmd16_vacaciones_bonos_perma (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep,
cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, ano, numero, fecha_inicio, fecha_termina, periodo_desde, periodo_hasta, dias_inhabiles,
numero_lunes, fecha_calculo, cantidad_vacaciones, salario_mensual, salario_diario, dias_vacaciones, salario_diario_vaca, dias_adicio_vacaciones,
dias_bonificacion, sueldo_diario_bonifica, dias_bono_vacacional, salario_diario_bono_vac, dias_adicio_bono_vaca, dias_sab_dom_fer, porc_seguro_social,
monto_seguro_social, porc_paro_forzoso, monto_paro_forzoso, porc_fondo_ahorro, monto_fondo_ahorro, porc_fondo_jub, monto_fondo_jub, porc_cuota_sindical,
cuota_sindical, monto_cuota_sindical, porc_caja_ahorro, monto_caja_ahorro, cuota_prestamo, monto_prestamo_caja, cuota_vivienda, monto_credito_vivienda,
porc_aporte_seguro_social, monto_aporte_seguro_social, porc_aporte_paro_forzoso, monto_aporte_paro_forzoso, porc_aporte_fondo_ahorro,
monto_aporte_fondo_ahorro, porc_aporte_fondo_jub, monto_aporte_fondo_jub, porc_aporte_ahorro, monto_aporte_ahorro, observaciones, tipo_operacion)
VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep','$cod_tipo_nomina', '$cod_cargo', '$cod_ficha', '$cedula_identidad', '$ano',
'$numero', '$fecha_inicio', '$fecha_termina', '$periodo_desde', '$periodo_hasta', '$dias_inhabiles', '$numero_lunes', '$fecha_tran', '$cantidad_vacaciones',
'$salario_mensual', '$salario_diario', '$dias_vacaciones', '$salario_diario_vaca', '$dias_adicio_vacaciones', '$dias_bonificacion', '$sueldo_diario_bonifica',
'$dias_bono_vacacional', '$salario_diario_bono_vac', '$dias_adicio_bono_vaca', '$dias_sab_dom_fer', '$porc_seguro_social', '$monto_seguro_social',
'$porc_paro_forzoso', '$monto_paro_forzoso', '$porc_fondo_ahorro', '$monto_fondo_ahorro', '$porc_fondo_jub', '$monto_fondo_jub', '$porc_cuota_sindical',
'$cuota_sindical', '$monto_cuota_sindical', '$porc_caja_ahorro', '$monto_caja_ahorro', '$cuota_prestamo', '$monto_prestamo_caja', '$cuota_vivienda',
'$monto_credito_vivienda', '$porc_aporte_seguro_social', '$monto_aporte_seguro_social', '$porc_aporte_paro_forzoso', '$monto_aporte_paro_forzoso',
'$porc_aporte_fondo_ahorro', '$monto_aporte_fondo_ahorro', '$porc_aporte_fondo_jub', '$monto_aporte_fondo_jub', '$porc_aporte_ahorro','$monto_aporte_ahorro',
'$observaciones','$tipo_operacion');";
$insertar_permanente  = $this->Cnmd01->execute($sql_insertar_permanente);


		}
	}


}// fin function dias_sueldo_bono_vaca






function _recalcular_faov ($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,$cod_tipo_nomina,$tipo,$ctran) {
	set_time_limit(0);
    /**
     * FAOV
     */
     $rand = rand();

     $sum_faov_count = $this->Cnmd01->execute("SELECT count(*) as c FROM transacciones_actuales_faov_tmp WHERE tipo=$tipo and cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and $rand=$rand;");
	if($sum_faov_count[0][0]['c']!=0){
     $sum_faov = $this->Cnmd01->execute("SELECT * FROM transacciones_actuales_faov_tmp WHERE tipo=$tipo and cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and $rand=$rand;");
     $diferencia_faov = $sum_faov[0][0]['suma_monto_cuota'] - $sum_faov[0][0]['sueldo_integral'];
     $diferencia_faov =  sprintf("%01.2f",$diferencia_faov);
     $diferencia_faov = $diferencia_faov * 100;
     $i_d = 1;


     if($diferencia_faov<0){
     	$diferencia_faov = $diferencia_faov * (-1);
     	$campo_diferencia = "<5";
     	$xcampo_diferencia = "6";
     	$signo = "+";
     }else{
     	$campo_diferencia = ">=5";
     	$signo = "-";
     	$xcampo_diferencia = "0";
     }

     $data_faov1 = $this->Cnmd01->execute("SELECT * FROM cnmd07_transacciones_actuales_faov_tmp WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and cod_tipo_transaccion=2 and cod_transaccion=$ctran and \"decimal\"$campo_diferencia;");

     foreach($data_faov1 as $data_faov1r){
     	$cod_cargo = $data_faov1r[0]['cod_cargo'];
     	$cod_ficha = $data_faov1r[0]['cod_ficha'];
     	if($i_d<=$diferencia_faov){
            $UPDATE_FAOV="UPDATE cnmd07_transacciones_actuales SET monto_cuota=monto_cuota$signo(0.01)  WHERE cod_presi=$cod_presi AND cod_entidad=$cod_entidad AND cod_tipo_inst=$cod_tipo_inst AND cod_inst=$cod_inst AND cod_dep=$cod_dep AND cod_tipo_nomina=$cod_tipo_nomina AND cod_cargo=$cod_cargo AND cod_ficha=$cod_ficha AND cod_tipo_transaccion=2 AND cod_transaccion=$ctran";
        	$this->Cnmd01->execute($UPDATE_FAOV);
        	$UPDATE_FAOV="UPDATE cnmd07_transacciones_actuales_faov_tmp SET monto_cuota=monto_cuota$signo(0.01), \"decimal\"=$xcampo_diferencia  WHERE cod_presi=$cod_presi AND cod_entidad=$cod_entidad AND cod_tipo_inst=$cod_tipo_inst AND cod_inst=$cod_inst AND cod_dep=$cod_dep AND cod_tipo_nomina=$cod_tipo_nomina AND cod_cargo=$cod_cargo AND cod_ficha=$cod_ficha AND cod_tipo_transaccion=2 AND cod_transaccion=$ctran";
        	$this->Cnmd01->execute($UPDATE_FAOV);
        	//echo "<br>".$UPDATE_FAOV;
     	}
     	$i_d++;
     }
     unset($sum_faov);
     unset($data_faov1);
     unset($data_faov1r);
     /*fin faov */


     $rand = rand();
     $sum_faov = $this->Cnmd01->execute("SELECT * FROM transacciones_actuales_faov_tmp WHERE tipo=$tipo and cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and $rand=$rand;");
     $diferencia_faov = $sum_faov[0][0]['suma_monto_cuota'] - $sum_faov[0][0]['sueldo_integral'];
     $diferencia_faov =  sprintf("%01.2f",$diferencia_faov);
     $diferencia_faov = $diferencia_faov * 100;

	     if($diferencia_faov>0 || $diferencia_faov<0){
	         if($diferencia_faov==1){
	     	 	$this->_recalcular_faov_uno ($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,$cod_tipo_nomina,$tipo,$ctran);
	     	 }else{
	     	 	$this->_recalcular_faov ($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,$cod_tipo_nomina,$tipo,$ctran);
	     	 }
	     }

	}//fin if count
}


function _recalcular_faov_uno ($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,$cod_tipo_nomina,$tipo,$ctran) {
	set_time_limit(0);
    /**
     * FAOV
     */
     $rand = rand();

     $sum_faov_count = $this->Cnmd01->execute("SELECT count(*) as c FROM transacciones_actuales_faov_tmp WHERE tipo=$tipo and cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and $rand=$rand;");
	if($sum_faov_count[0][0]['c']!=0){
     $sum_faov = $this->Cnmd01->execute("SELECT * FROM transacciones_actuales_faov_tmp WHERE tipo=$tipo and cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and $rand=$rand;");
     $diferencia_faov = $sum_faov[0][0]['suma_monto_cuota'] - $sum_faov[0][0]['sueldo_integral'];
     $diferencia_faov =  sprintf("%01.2f",$diferencia_faov);
     $diferencia_faov = $diferencia_faov * 100;
     $i_d = 1;


     if($diferencia_faov<0){
     	$diferencia_faov = $diferencia_faov * (-1);
     	$campo_diferencia = "<5";
     	$xcampo_diferencia = "6";
     	$signo = "+";
     }else{
     	$campo_diferencia = ">=5";
     	$signo = "-";
     	$xcampo_diferencia = "0";
     }




     $data_faov1 = $this->Cnmd01->execute("SELECT * FROM cnmd07_transacciones_actuales_faov_tmp WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and cod_tipo_transaccion=2 and cod_transaccion=$ctran;");

//     foreach($data_faov1 as $data_faov1r){
     	$cod_cargo = $data_faov1[0][0]['cod_cargo'];
     	$cod_ficha = $data_faov1[0][0]['cod_ficha'];
     	if($i_d<=$diferencia_faov){
            $UPDATE_FAOV="UPDATE cnmd07_transacciones_actuales SET monto_cuota=monto_cuota$signo(0.01)  WHERE cod_presi=$cod_presi AND cod_entidad=$cod_entidad AND cod_tipo_inst=$cod_tipo_inst AND cod_inst=$cod_inst AND cod_dep=$cod_dep AND cod_tipo_nomina=$cod_tipo_nomina AND cod_cargo=$cod_cargo AND cod_ficha=$cod_ficha AND cod_tipo_transaccion=2 AND cod_transaccion=$ctran";
        	$this->Cnmd01->execute($UPDATE_FAOV);
        	$UPDATE_FAOV="UPDATE cnmd07_transacciones_actuales_faov_tmp SET monto_cuota=monto_cuota$signo(0.01), \"decimal\"=$xcampo_diferencia  WHERE cod_presi=$cod_presi AND cod_entidad=$cod_entidad AND cod_tipo_inst=$cod_tipo_inst AND cod_inst=$cod_inst AND cod_dep=$cod_dep AND cod_tipo_nomina=$cod_tipo_nomina AND cod_cargo=$cod_cargo AND cod_ficha=$cod_ficha AND cod_tipo_transaccion=2 AND cod_transaccion=$ctran";
        	$this->Cnmd01->execute($UPDATE_FAOV);
        	//echo "<br>".$UPDATE_FAOV;
     	}
     	$i_d++;
//     }
     unset($sum_faov);
     unset($data_faov1);
     /*fin faov */
	}

}






function _recalcular_faov_ap ($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,$cod_tipo_nomina,$tipo,$ctran) {
	set_time_limit(0);
     /**
     * FAOV aporte del patrono
     */
     $rand = rand();
     $sum_faov_ap_count = $this->Cnmd01->execute("SELECT count(*) as c FROM transacciones_actuales_faov_tmp WHERE tipo=$tipo and cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and $rand=$rand;");
	if($sum_faov_ap_count[0][0]['c']!=0){
     $negativos = $this->Cnmd01->execute("SELECT * FROM transacciones_actuales_faov_tmp WHERE tipo=$tipo and cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and $rand=$rand;");
     $sum_faov_ap = $this->Cnmd01->execute("SELECT * FROM transacciones_actuales_faov_tmp WHERE tipo=$tipo and cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and $rand=$rand;");

    if ($sum_faov_ap[0][0]['sueldo_integral']>=$sum_faov_ap[0][0]['suma_monto_cuota']){
     	$diferencia_faov = $sum_faov_ap[0][0]['sueldo_integral'] - $sum_faov_ap[0][0]['suma_monto_cuota'];
     }else{
     	$diferencia_faov = $sum_faov_ap[0][0]['suma_monto_cuota'] - $sum_faov_ap[0][0]['sueldo_integral'];
     }

     $diferencia_faov =  sprintf("%01.2f",$diferencia_faov);
     $diferencia_faov = $diferencia_faov * 100;


     for($iii=1;$iii<=$diferencia_faov;$iii++){
		$this->_recalcular_faov_ap_uno_a_uno ($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,$cod_tipo_nomina,$tipo,$ctran);
     }

	}
}

function _recalcular_faov_ap_uno_a_uno ($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,$cod_tipo_nomina,$tipo,$ctran) {
	set_time_limit(0);
     /**
     * FAOV aporte del patrono
     */
     $rand = rand();
     $sum_faov_ap_count = $this->Cnmd01->execute("SELECT count(*) as c FROM transacciones_actuales_faov_tmp WHERE tipo=$tipo and cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and $rand=$rand;");
	if($sum_faov_ap_count[0][0]['c']!=0){
     $negativos = $this->Cnmd01->execute("SELECT * FROM transacciones_actuales_faov_tmp WHERE tipo=$tipo and cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and $rand=$rand;");
     $sum_faov_ap = $this->Cnmd01->execute("SELECT * FROM transacciones_actuales_faov_tmp WHERE tipo=$tipo and cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and $rand=$rand;");
     $diferencia_faov = $sum_faov_ap[0][0]['suma_monto_cuota'] - $sum_faov_ap[0][0]['sueldo_integral'];
     $diferencia_faov =  sprintf("%01.2f",$diferencia_faov);
     $diferencia_faov = $diferencia_faov * 100;

     $i_d = 1;
     if($diferencia_faov<0){
     	$diferencia_faov = $diferencia_faov * (-1);
     	$campo_diferencia = "<5";
     	$xcampo_diferencia = "6";
     	$signo = "+";
     }else{
     	$campo_diferencia = ">=5";
     	$xcampo_diferencia = "0";
     	$signo = "-";

     }

     $data_faov1_ap_count = $this->Cnmd01->execute("SELECT count(*) as c FROM cnmd07_transacciones_actuales_faov_tmp WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and cod_tipo_transaccion=2 and cod_transaccion=$ctran and \"decimal\"$campo_diferencia;");
	 if($data_faov1_ap_count[0][0]['c']==0){
		 $data_faov1_ap = $this->Cnmd01->execute("SELECT * FROM cnmd07_transacciones_actuales_faov_tmp WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and cod_tipo_transaccion=2 and cod_transaccion=$ctran and \"decimal\" = 0 ;");

	     foreach($data_faov1_ap as $data_faov1r){
	     	$cod_cargo = $data_faov1r[0]['cod_cargo'];
	     	$cod_ficha = $data_faov1r[0]['cod_ficha'];
	     	if($i_d<=$diferencia_faov){
	            $UPDATE_FAOV="UPDATE cnmd07_transacciones_actuales SET monto_cuota=monto_cuota$signo(0.01)  WHERE cod_presi=$cod_presi AND cod_entidad=$cod_entidad AND cod_tipo_inst=$cod_tipo_inst AND cod_inst=$cod_inst AND cod_dep=$cod_dep AND cod_tipo_nomina=$cod_tipo_nomina AND cod_cargo=$cod_cargo AND cod_ficha=$cod_ficha AND cod_tipo_transaccion=2 AND cod_transaccion=$ctran";
	        	$this->Cnmd01->execute($UPDATE_FAOV);
	        	$UPDATE_FAOV="UPDATE cnmd07_transacciones_actuales_faov_tmp SET monto_cuota=monto_cuota$signo(0.01), \"decimal\"=1  WHERE cod_presi=$cod_presi AND cod_entidad=$cod_entidad AND cod_tipo_inst=$cod_tipo_inst AND cod_inst=$cod_inst AND cod_dep=$cod_dep AND cod_tipo_nomina=$cod_tipo_nomina AND cod_cargo=$cod_cargo AND cod_ficha=$cod_ficha AND cod_tipo_transaccion=2 AND cod_transaccion=$ctran";
	        	$this->Cnmd01->execute($UPDATE_FAOV);
	        	//echo "<br>".$UPDATE_FAOV;
	     	}
	     	$i_d++;
	     }
	 }else{
	     $data_faov1_ap = $this->Cnmd01->execute("SELECT * FROM cnmd07_transacciones_actuales_faov_tmp WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and cod_tipo_transaccion=2 and cod_transaccion=$ctran and \"decimal\"$campo_diferencia;");

	     foreach($data_faov1_ap as $data_faov1r){
	     	$cod_cargo = $data_faov1r[0]['cod_cargo'];
	     	$cod_ficha = $data_faov1r[0]['cod_ficha'];
	     	if($i_d<=$diferencia_faov){
	            $UPDATE_FAOV="UPDATE cnmd07_transacciones_actuales SET monto_cuota=monto_cuota$signo(0.01)  WHERE cod_presi=$cod_presi AND cod_entidad=$cod_entidad AND cod_tipo_inst=$cod_tipo_inst AND cod_inst=$cod_inst AND cod_dep=$cod_dep AND cod_tipo_nomina=$cod_tipo_nomina AND cod_cargo=$cod_cargo AND cod_ficha=$cod_ficha AND cod_tipo_transaccion=2 AND cod_transaccion=$ctran";
	        	$this->Cnmd01->execute($UPDATE_FAOV);
	        	$UPDATE_FAOV="UPDATE cnmd07_transacciones_actuales_faov_tmp SET monto_cuota=monto_cuota$signo(0.01), \"decimal\"=$xcampo_diferencia  WHERE cod_presi=$cod_presi AND cod_entidad=$cod_entidad AND cod_tipo_inst=$cod_tipo_inst AND cod_inst=$cod_inst AND cod_dep=$cod_dep AND cod_tipo_nomina=$cod_tipo_nomina AND cod_cargo=$cod_cargo AND cod_ficha=$cod_ficha AND cod_tipo_transaccion=2 AND cod_transaccion=$ctran";
	        	$this->Cnmd01->execute($UPDATE_FAOV);
	        	//echo "<br>".$UPDATE_FAOV;
	     	}
	     	$i_d++;
	     }

	 }


     unset($sum_faov_ap);
     unset($data_faov1_ap);
     unset($data_faov1r);
	}
}

function _recalcular_faov_ap_uno ($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,$cod_tipo_nomina,$tipo,$ctran) {
	set_time_limit(0);
     /**
     * FAOV aporte del patrono
     */
     $rand = rand();
     $sum_faov_ap_count = $this->Cnmd01->execute("SELECT count(*) as c FROM transacciones_actuales_faov_tmp WHERE tipo=$tipo and cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and $rand=$rand;");
	if($sum_faov_ap_count[0][0]['c']!=0){
     $negativos = $this->Cnmd01->execute("SELECT * FROM transacciones_actuales_faov_tmp WHERE tipo=$tipo and cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and $rand=$rand;");
     $sum_faov_ap = $this->Cnmd01->execute("SELECT * FROM transacciones_actuales_faov_tmp WHERE tipo=$tipo and cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and $rand=$rand;");
     $diferencia_faov = $sum_faov_ap[0][0]['suma_monto_cuota'] - $sum_faov_ap[0][0]['sueldo_integral'];
     $diferencia_faov =  sprintf("%01.2f",$diferencia_faov);
     $diferencia_faov = $diferencia_faov * 100;

     $i_d = 1;
     if($diferencia_faov<0){
     	$diferencia_faov = $diferencia_faov * (-1);
     	$campo_diferencia = "<5";
     	$xcampo_diferencia = "6";
     	$signo = "+";
     }else{
     	$campo_diferencia = ">=5";
     	$xcampo_diferencia = "0";
     	$signo = "-";

     }
     $data_faov1_ap = $this->Cnmd01->execute("SELECT * FROM cnmd07_transacciones_actuales_faov_tmp WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and cod_tipo_transaccion=2 and cod_transaccion=$ctran;");

     //foreach($data_faov1_ap as $data_faov1r){
     	$cod_cargo = $data_faov1_ap[0][0]['cod_cargo'];
     	$cod_ficha = $data_faov1_ap[0][0]['cod_ficha'];
     	if($i_d<=$diferencia_faov){
            $UPDATE_FAOV="UPDATE cnmd07_transacciones_actuales SET monto_cuota=monto_cuota$signo(0.01)  WHERE cod_presi=$cod_presi AND cod_entidad=$cod_entidad AND cod_tipo_inst=$cod_tipo_inst AND cod_inst=$cod_inst AND cod_dep=$cod_dep AND cod_tipo_nomina=$cod_tipo_nomina AND cod_cargo=$cod_cargo AND cod_ficha=$cod_ficha AND cod_tipo_transaccion=2 AND cod_transaccion=$ctran";
        	$this->Cnmd01->execute($UPDATE_FAOV);
        	$UPDATE_FAOV="UPDATE cnmd07_transacciones_actuales_faov_tmp SET monto_cuota=monto_cuota$signo(0.01), \"decimal\"=$xcampo_diferencia  WHERE cod_presi=$cod_presi AND cod_entidad=$cod_entidad AND cod_tipo_inst=$cod_tipo_inst AND cod_inst=$cod_inst AND cod_dep=$cod_dep AND cod_tipo_nomina=$cod_tipo_nomina AND cod_cargo=$cod_cargo AND cod_ficha=$cod_ficha AND cod_tipo_transaccion=2 AND cod_transaccion=$ctran";
        	$this->Cnmd01->execute($UPDATE_FAOV);
        	//echo "<br>".$UPDATE_FAOV;
     	}
     	$i_d++;
     //}
     unset($sum_faov_ap);
     unset($data_faov1_ap);



	}
}








function seleccion_nomina() {
     $this->layout="ajax";
    $lista = $this->Cnmd01->generateList($this->SQLCA()." and status_nomina IN (0,1)", 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($this->Cnmd01->findCount($this->SQLCA())!=0){
		$this->concatena($lista, 'tipo_nomina');
	}else{
		$this->set('tipo_nomina', array());
	}
}//fin seleccion_nomina

function salir_prenomina ($numero) {
       $this->layout="ajax";
}//fin salir_prenomina



function subir_archivo_transacciones ($var=null) {
   $this->layout="ajax";
//    $lista = $this->Cnmd01->generateList($this->SQLCA()." and status_nomina IN (0,1)", 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
    $lista = $this->Cnmd01->generateList($this->SQLCA(), 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($this->Cnmd01->findCount($this->SQLCA())!=0){
		$this->concatenaN($lista, 'nomina');
	}else{
		$this->set('nomina', array());
	}

	$cnmd03 = $this->cnmd03_transacciones->generateList2($conditions ='cod_tipo_transaccion = 2', $order = null, $limit = null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
	$this->concatenaN($cnmd03, 'transaccion');
	$this->set('opcion',$var);
}//fin funcion subir_archivo_transacciones




function subir_archivo_cuentas ($var=null) {
   $this->layout="ajax";
	$this->set('opcion',$var);
}//fin funcion subir_archivo_transacciones

function cod_transa ($var=null) {
   $this->layout="ajax";

	$cnmd03 = $this->cnmd03_transacciones->generateList2($conditions ='cod_tipo_transaccion = '.$var, $order = null, $limit = null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
	$this->concatenaN($cnmd03, 'transaccion');


}//fin funcion subir_archivo_transacciones

function descargar_formato () {
   $this->layout="ajax";
   if(!empty($this->data['pre']['cod_tipo_nomina']) && !empty($this->data['pre']['cod_transaccion']) && !empty($this->data['pre']['cod_tipo_transaccion'])){
       $cod_tipo_nomina      = $this->data['pre']['cod_tipo_nomina'];
       $cod_tipo_transaccion = $this->data['pre']['cod_tipo_transaccion'];
       $cod_transaccion      = $this->data['pre']['cod_transaccion'];

       $data = $this->Cnmd01->execute("SELECT a.cod_cargo,a.cod_ficha,a.cedula_identidad,(b.primer_apellido || ' '|| b.segundo_apellido  || ' '|| b.primer_nombre  || ' '|| b.segundo_nombre) as nombre_completo FROM cnmd06_fichas a,cnmd06_datos_personales b WHERE b.cedula_identidad=a.cedula_identidad and ".$this->SQLCA()." and cod_tipo_nomina=$cod_tipo_nomina and condicion_actividad=1 ORDER BY a.cod_cargo,a.cod_ficha,a.cedula_identidad ASC");

       $this->set('DATA',$data);
       $this->set('cod_tipo_nomina',$cod_tipo_nomina);
       $this->set('cod_tipo_transaccion',$cod_tipo_transaccion);
       $this->set('cod_transaccion',$cod_transaccion);
   }

}//fin funcion descargar_formato




function cargar_archivo_transaccion ($aleatorio) {
   $this->layout="ajax";
    $cod_presi     = $this->Session->read('SScodpresi');
	$cod_entidad   = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst      = $this->Session->read('SScodinst');
	$cod_dep       = $this->Session->read('SScoddep');
	$username      = $this->Session->read('nom_usuario');


   if (!empty($this->params['form']) && is_uploaded_file($this->params['form']['File']['tmp_name'])){
             $fileOK = $this->uploadFiles('files', $this->params['form']['File']);

			if(array_key_exists('urls', $fileOK)) {
			    $archivo_url=$fileOK['urls'][0];
			    $archivo = file($fileOK['urls'][0]);
				$lineas = count($archivo);
				$cod_tipo_nomina =$this->data['pre']['cod_tipo_nomina'];//
				$cod_tipo_transaccion = 2;//deduccion
				$cod_transaccion = $this->data['pre']['cod_tipo_transaccion'];
vendor('Excel/reader');
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
$data->read($archivo_url);
error_reporting(E_ALL ^ E_NOTICE);

$datos_incompletos = array();
for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
	$dd=0;
	for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {

		if($data->sheets[0]['cells'][$i][$j]==''){
           $dd++;
		}
	}
	    $cod_tipo_transaccion   = $data->sheets[0]['cells'][$i][2];
		$cod_transaccion        = $data->sheets[0]['cells'][$i][3];
		$monto_original         = isset($data->sheets[0]['cells'][$i][7])?$data->sheets[0]['cells'][$i][8]:0;
		$monto_cuota            = isset($data->sheets[0]['cells'][$i][8])?$data->sheets[0]['cells'][$i][9]:0;
		$numero_cuotas_cancelar = isset($data->sheets[0]['cells'][$i][9])?$data->sheets[0]['cells'][$i][10]:0;
		$tipo_actualizacion = $this->cnmd03_transacciones->execute("SELECT tipo_actualizacion FROM cnmd03_transacciones WHERE cod_tipo_transaccion=$cod_tipo_transaccion and cod_transaccion=$cod_transaccion;");
		$t_a = (int) $tipo_actualizacion[0][0]['tipo_actualizacion'];
        if($t_a==2){//deductiva
             if($dd==0  && ($monto_original!=0 && $monto_original!='0' && $monto_original!='0.00' && $monto_original!='0,0' && $monto_original!='0,00')){
				$cod_tipo_nomina        = $data->sheets[0]['cells'][$i][1];
				$cod_tipo_transaccion   = $data->sheets[0]['cells'][$i][2];
				$cod_transaccion        = $data->sheets[0]['cells'][$i][3];
				$cod_cargo              = $data->sheets[0]['cells'][$i][4];
				$cod_ficha              = $data->sheets[0]['cells'][$i][5];
				$cedula                 = $data->sheets[0]['cells'][$i][6];
				$monto_original         = $data->sheets[0]['cells'][$i][8];
				$monto_cuota            = $data->sheets[0]['cells'][$i][9];
				$numero_cuotas_cancelar = $data->sheets[0]['cells'][$i][10];
				$correcto = 'SI';
				$sql = "INSERT INTO cnmd07_transacciones_viadiskette VALUES(
		            $cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $cod_tipo_nomina,
		            $cod_cargo, $cod_ficha, $cod_tipo_transaccion, $cod_transaccion,
		            CURRENT_DATE, $monto_original, 1, $numero_cuotas_cancelar,
		            0, $monto_cuota, $monto_original, '0',
		            CURRENT_DATE, '$username', 0)
		     ;";
		     $insertar = true;
			}else{
				$cod_tipo_nomina        = (int) $data->sheets[0]['cells'][$i][1];
				$cod_tipo_transaccion   = (int) $data->sheets[0]['cells'][$i][2];
				$cod_transaccion        = (int) $data->sheets[0]['cells'][$i][3];
				$cod_cargo              = $data->sheets[0]['cells'][$i][4];
				$cod_ficha              = $data->sheets[0]['cells'][$i][5];
				$cedula                 = $data->sheets[0]['cells'][$i][6];
				$monto_original         = $data->sheets[0]['cells'][$i][8];
				$monto_cuota            = $data->sheets[0]['cells'][$i][9];
				$numero_cuotas_cancelar = $data->sheets[0]['cells'][$i][10];
				$correcto = 'NO';
				$sql ="Select 12*-4;";

				$insertar = false;
			}
        }else{
        	if($dd==0 && isset($data->sheets[0]['cells'][$i][9]) && ($data->sheets[0]['cells'][$i][9]!=0 && $data->sheets[0]['cells'][$i][9]!='0' && $data->sheets[0]['cells'][$i][9]!='0.00' && $data->sheets[0]['cells'][$i][9]!='0,0' && $data->sheets[0]['cells'][$i][9]!='0,00')){
				$cod_tipo_nomina        = $data->sheets[0]['cells'][$i][1];
				$cod_tipo_transaccion   = $data->sheets[0]['cells'][$i][2];
				$cod_transaccion        = $data->sheets[0]['cells'][$i][3];
				$cod_cargo              = $data->sheets[0]['cells'][$i][4];
				$cod_ficha              = $data->sheets[0]['cells'][$i][5];
				$cedula                 = $data->sheets[0]['cells'][$i][6];
				$monto_original         = $data->sheets[0]['cells'][$i][8];
				$monto_cuota            = $data->sheets[0]['cells'][$i][9];
				$numero_cuotas_cancelar = $data->sheets[0]['cells'][$i][10];
				$correcto = 'SI';
				$sql = "INSERT INTO cnmd07_transacciones_viadiskette VALUES(
		            $cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $cod_tipo_nomina,
		            $cod_cargo, $cod_ficha, $cod_tipo_transaccion, $cod_transaccion,
		            CURRENT_DATE, $monto_original, 1, $numero_cuotas_cancelar,
		            0, $monto_cuota, 0, '0',
		            CURRENT_DATE, '$username', 0)
		     ;";
		     $insertar = true;
			}else{
				$cod_tipo_nomina        = (int) $data->sheets[0]['cells'][$i][1];
				$cod_tipo_transaccion   = (int) $data->sheets[0]['cells'][$i][2];
				$cod_transaccion        = (int) $data->sheets[0]['cells'][$i][3];
				$cod_cargo              = $data->sheets[0]['cells'][$i][4];
				$cod_ficha              = $data->sheets[0]['cells'][$i][5];
				$cedula                 = $data->sheets[0]['cells'][$i][6];
				$monto_original         = $data->sheets[0]['cells'][$i][8];
				$monto_cuota            = $data->sheets[0]['cells'][$i][9];
				$numero_cuotas_cancelar = $data->sheets[0]['cells'][$i][10];
				$correcto = 'NO';
				$sql ="Select 12*-4;";

				$insertar = false;
			}
        }



	//echo $sql."<br>\n";

	$existe = $this->cnmd03_transacciones->execute("SELECT cant_trans_viadiskette($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $cod_tipo_nomina, $cod_cargo, $cod_ficha, $cod_tipo_transaccion, $cod_transaccion);");

	if(((int) $existe[0][0]['cant_trans_viadiskette'])==0){

		$existe_frecuencia = $this->cnmd03_transacciones->execute("SELECT verificar_si_existe_frecuencia($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $cod_tipo_nomina, $cod_tipo_transaccion, $cod_transaccion);");
        $existe_frecuencia_d = (int) $existe_frecuencia[0][0]['verificar_si_existe_frecuencia'];
        if($existe_frecuencia_d!=0){

		$result = $this->cnmd03_transacciones->execute($sql);
		if($result>0 && $insertar==true){
	       $datos_completos[]=array(
	       "nomina"=>$cod_tipo_nomina,
	       "tipo_transaccion"=>$cod_tipo_transaccion,
	       "transaccion"=>$cod_transaccion,
	       "cargo"=>$cod_cargo,
	       "ficha"=>$cod_ficha,
           "cedula"=>$cedula,
	       "monto_original"=>$monto_original,
	       "monto_cuota"=>$monto_cuota,
	       "numero_cuotas_cancelar"=>$numero_cuotas_cancelar,
	       "completo"=>$correcto,
	       "guardado"=>'SI'
	       );
		}else{
			$datos_completos[]=array(
		    "nomina"=>$cod_tipo_nomina,
	       "tipo_transaccion"=>$cod_tipo_transaccion,
	       "transaccion"=>$cod_transaccion,
	       "cargo"=>$cod_cargo,
	       "ficha"=>$cod_ficha,
           "cedula"=>$cedula,
	       "monto_original"=>$monto_original,
	       "monto_cuota"=>$monto_cuota,
	       "numero_cuotas_cancelar"=>$numero_cuotas_cancelar,
	       "completo"=>$correcto,
	       "guardado"=>'NO'
	       );
		}


	}else{
         $datos_completos[]=array(
		   "nomina"=>$cod_tipo_nomina,
	       "tipo_transaccion"=>$cod_tipo_transaccion,
	       "transaccion"=>$cod_transaccion,
	       "cargo"=>$cod_cargo,
	       "ficha"=>$cod_ficha,
           "cedula"=>$cedula,
	       "monto_original"=>$monto_original,
	       "monto_cuota"=>$monto_cuota,
	       "numero_cuotas_cancelar"=>$numero_cuotas_cancelar,
	       "completo"=>'Sin<br>Frecuencia',
	       "guardado"=>'NO'
	       );
	}
	}else{
		$datos_completos[]=array(
		   "nomina"=>$cod_tipo_nomina,
	       "tipo_transaccion"=>$cod_tipo_transaccion,
	       "transaccion"=>$cod_transaccion,
	       "cargo"=>$cod_cargo,
	       "ficha"=>$cod_ficha,
           "cedula"=>$cedula,
	       "monto_original"=>$monto_original,
	       "monto_cuota"=>$monto_cuota,
	       "numero_cuotas_cancelar"=>$numero_cuotas_cancelar,
	       "completo"=>$correcto,
	       "guardado"=>'Existe'
	       );
	}

}
$this->set('DATA',$datos_completos);
//print_r($datos_completos);
echo "<center><h2>Transacciones cargadas</h2></center>";


/*
 * for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
	for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
		//echo "\"".$data->sheets[0]['cells'][$i][$j]."\",";
	}
	//echo "<br>\n";

}
 */

					/**
					 * solo para archivo de textos
					 *
					for($i=0; $i < $lineas; $i++){
				            $campos = explode(';',$archivo[$i]);
				            $c_campos = count($campos);
				            if($c_campos!=1 && $c_campos==21){
				                for($j=0; $j<$c_campos;$j++){
				                   $campos_aux[$j] = "'".str_replace('"','',$campos[$j])."'";
				                }
				                echo "<br>(".implode(',',$campos_aux).")";
				            }
					    echo "<br/>[$i] <- ".$archivo[$i] ." campos[".$c_campos."]" ;
					}
					/**/

			}//fin file ok

    }


}//fin funcion cargar_archivo_transaccion



function uploadFiles($folder, $formdata, $itemId = null) {
	// setup dir names absolute and relative
	$folder_url = WWW_ROOT.$folder;
	$rel_url = $folder;

	// create the folder if it does not exist
	if(!is_dir($folder_url)) {
	mkdir($folder_url);
	}

	// if itemId is set create an item folder
	if($itemId) {
	// set new absolute folder
	$folder_url = WWW_ROOT.$folder.'/'.$itemId;
	// set new relative folder
	$rel_url = $folder.'/'.$itemId;
	// create directory
	if(!is_dir($folder_url)) {
	mkdir($folder_url);
	}
	}

	// list of permitted file types, this is only images but documents can be added
	//$permitted = array('image/gif','image/jpeg','image/pjpeg','image/png');
	$permitted = array('application/vnd.ms-excel','text/plain');

	// loop through and deal with the files
	$file = $formdata;
//	foreach($formdata as $file) {
		// replace spaces with underscores
		$filename = str_replace(' ', '_', $file['name']);
		// assume filetype is false
		$typeOK = false;
		// check filetype is ok
		foreach($permitted as $type) {
			// echo $file['type'];
		if($type == $file['type']) {
		$typeOK = true;
		break;
		}
		}

		// if file type ok upload the file
		if($typeOK) {
		// switch based on error code
		switch($file['error']) {
		case 0:
		// check filename already exists
		if(!file_exists($folder_url.'/'.$filename)) {
		// create full filename
		$full_url = $folder_url.'/'.$filename;
		$url = $rel_url.'/'.$filename;
		// upload the file
		$success = move_uploaded_file($file['tmp_name'], $url);
		} else {
		// create unique filename and upload file
		ini_set('date.timezone', 'Europe/London');
		$now = date('Y-m-d-His');
		$full_url = $folder_url.'/'.$now.$filename;
		$url = $rel_url.'/'.$now.$filename;
		$success = move_uploaded_file($file['tmp_name'], $url);
		}
		// if upload was successful
		if($success) {
		// save the url of the file
		$result['urls'][] = $url;
		} else {
		$result['errors'][] = "Error uploaded $filename. Please try again.";
		}
		break;
		case 3:
		// an error occured
		$result['errors'][] = "Error uploading $filename. Please try again.";
		break;
		default:
		// an error occured
		$result['errors'][] = "System error uploading $filename. Contact webmaster.";
		break;
		}
		} elseif($file['error'] == 4) {
		// no file was selected for upload
		$result['nofiles'][] = "No file Selected";
		} else {
		// unacceptable file type
		$result['errors'][] = "$filename cannot be uploaded. Acceptable file types: gif, jpg, png.";
		}
//	}
	return $result;
}


function _insertar_transacciones () {
   $this->layout="txt";
/*
   $cedulas[2] = array(8193723,8196370,8197340,9594416,9596292,9596489,9871649,9874080,9875279,9891077,10620395,10622008,10922232,11240121,11241874,12901372,13805960,14219686,15998275,16976726,22576036);*/


   //$sql_insert="INSERT INTO cnmd10_individual_porcentaje_cantidad_ded (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina,cod_cargo, cod_ficha, cod_tipo_transaccion, cod_transaccion,cantidad) VALUES ";
   $sql_insert="INSERT INTO cnmd10_individual_bolivares_cantidad (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina,cod_cargo, cod_ficha, cod_tipo_transaccion, cod_transaccion,cantidad) VALUES ";

   $sql_insert2="";
   foreach($cedulas as $no=>$cedula){
        $cod_tipo_nomina = $no;
        foreach($cedula as $index_key =>$cedula_identidad){
              $sql_fichas = "SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina,cod_cargo, cod_ficha, cedula_identidad FROM cnmd06_fichas WHERE cod_tipo_nomina = $cod_tipo_nomina AND cedula_identidad=$cedula_identidad;";
              $da = $this->Cnmd01->execute($sql_fichas);
              foreach($da as $result_f){
              	     extract($result_f[0]);
              	     $cod_tipo_transaccion=1;
              	     $cod_transaccion = 202;
              	     $cantidad_escenario = 1;
                     $sql_insert2 .=$sql_insert. " ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $cod_tipo_nomina,$cod_cargo, $cod_ficha,$cod_tipo_transaccion, $cod_transaccion,$cantidad_escenario);\n";
              }
        }
   }

    $_SESSION['nombre_txt'] = "transa_".$cod_tipo_transaccion."_".$cod_transaccion.".txt";
    $this->set('sql_insert',$sql_insert2);
    $this->render('vacio');



}//fin funcion insertar_transacciones

function insertar_transacciones_2 () {
   $this->layout="txt";/*
   $cedulas[8] = array(
    1=>array(2230890=>221.49,4669439=>198.15,4671246=>205.15,4998549=>212.15,5359806=>233.15,5361524=>212.15,6348170=>251.82,6363812=>209.82,6935804=>198.15,6935947=>205.15,6937987=>258.72,6942627=>352.15,8152680=>212.15,8160842=>212.15,8164215=>211.69,8168909=>241.27,8192706=>390.89,8193229=>306.83,8196623=>298.49,8196638=>212.15,8198491=>233.15,8794495=>336.74,9592934=>293.82,9594282=>299.34,9596715=>345.15,9597831=>345.68,9868018=>213.32,9868207=>208.65,9870478=>296.54,9872342=>323.22,9873360=>265.77,9875185=>302.14,9875761=>212.15,9876346=>360.9,9877135=>205.15,10615173=>358.06,10616848=>262.64,10617730=>212.15,10618812=>240.15,10619468=>263.08,10620611=>327.03,10620985=>264.25,10623573=>247.15,10623666=>212.15,10624281=>209.82,10927562=>208.65,11235351=>198.15,11235936=>319.29,11237486=>209.82,11238784=>256.49,11243750=>221.49,11244221=>303.74,11753002=>209.82,11754129=>319.86,11757605=>212.15,11759818=>212.15,12321741=>251.82,12322226=>367.92,12322709=>349.82,12324901=>219.15,12581050=>298.76,12585069=>349.08,12585236=>284.66,13255495=>362.41,13433130=>198.15,13559869=>252.95,13805019=>260.78,14812578=>209.82,14947728=>303.15,15513272=>198.15,15513493=>208.65,15681796=>333.89,16528360=>291.49,16529039=>198.15,18016832=>221.49),
    3=>array(249070=>319.67,878816=>193.8,3770685=>351.17,4138385=>245,4139426=>205.15,4139443=>198.15,4140142=>273,4141853=>205.15,4169454=>358.17,4667296=>245,4667337=>226.15,4667740=>332.27,4668595=>205.15,4668989=>209.82,4670526=>351.17,4785669=>344.3,4997503=>207.49,4998285=>198.15,5096041=>205.15,5361185=>205.15,5610195=>205.15,5838935=>175,6687276=>198.15,7182744=>245,7210433=>306.13,8151424=>351.17,8152033=>205.15,8153063=>205.15,8155558=>205.15,8162842=>198.15,8168126=>351.17,8168726=>207.49,8168865=>245,8190988=>207.49,8191653=>207.49,8193677=>205.15,8194914=>205.15,8196465=>205.15,8197808=>205.15,8199786=>207.49,8337706=>205.15,8615945=>205.15,8625846=>209.82,8784403=>207.49,8831856=>390,8906904=>235.49,9054053=>83.32,9087504=>205.15,9250225=>245,9591153=>198.15,9593397=>536.67,9594377=>207.49,9594460=>209.82,9594772=>205.15,9595031=>84.9,9595355=>63.28,9596241=>205.15,9596968=>205.15,9597170=>351.17,9869663=>532.47,9870652=>205.15,9871323=>205.15,9871784=>351.17,9872000=>388.5,9872424=>205.15,9873996=>169.8,9875083=>198.15,10103573=>169.8,10615009=>235.67,10615468=>207.49,10616363=>253.87,10618787=>226.15,10619631=>205.15,10619678=>198.15,10620168=>245,10623590=>205.15,10624021=>207.49,10624212=>277.49,10624839=>245,10624994=>205.15,10640322=>245,11235938=>209.82,11236490=>209.82,11238133=>263.67,11238759=>198.15,11238803=>529.67,11238843=>198.15,11238967=>282.15,11240263=>205.15,11241195=>198.15,11241752=>198.15,11242040=>325.27,11753171=>209.82,11754218=>245,11754679=>205.15,11755023=>205.15,11755523=>209.82,11756718=>226.15,11757215=>205.15,11757253=>205.15,11757572=>207.49,11757754=>209.82,11758173=>169.8,11759329=>207.49,11759491=>379.17,11760064=>281.87,11760118=>293.07,11760776=>207.49,11760888=>251.82,11762244=>198.15,11762684=>198.33,11764015=>240.33,12321489=>205.15,12322150=>379.17,12322412=>207.49,12323142=>198.15,12323655=>319.67,12324922=>205.15,12389775=>242.67,12581849=>209.82,12583445=>198.15,12583655=>205.15,12584083=>235.49,12584250=>235.49,12584744=>205.15,12901966=>216.82,12902432=>207.49,12902563=>205.15,12902588=>245,12903504=>270.67,12903688=>198.15,12903802=>198.15,12903951=>235.49,12904041=>198.15,12904401=>501.67,12904763=>244.82,12904792=>205.15,13254595=>237.82,13255022=>205.15,13426664=>205.15,13433538=>198.15,13459273=>205.15,13488978=>318.64,13559798=>207.49,13559932=>198.16,13560110=>205.15,13560363=>230.82,13560383=>205.15,13640676=>198.15,13805930=>207.49,13806549=>222.64,13806705=>270.67,13806815=>265.82,13937033=>389.67,13937201=>233.15,13937718=>205.15,13938121=>209.82,13938431=>207.49,13938596=>360.27,14218131=>205.15,14218135=>207.49,14241981=>382.67,14343395=>169.8,14343717=>207.49,14343815=>205.15,14520422=>228.49,14520754=>293.97,14521853=>207.67,14693325=>275.15,14694265=>238,14694285=>226.15,14812871=>76.66,14882451=>198.15,14947015=>207.49,14948142=>226.15,14973137=>198.15,15046573=>422.8,15047234=>209.82,15047497=>216.64,15144069=>263.67,15144199=>205.15,15144260=>245,15146522=>198.15,15375634=>233.15,15512580=>205.15,15512614=>70,15513281=>198.15,15513995=>263.67,15681703=>209.82,15682397=>389.67,15682437=>226.15,15682521=>205.15,15682822=>228.49,15998070=>309.96,15998348=>205.15,15998597=>233.15,15998685=>205.15,15999208=>245,15999243=>205.15,15999313=>150.48,15999553=>263.67,15999600=>245,16000012=>235.49,16000764=>205.15,16000803=>244.82,16270204=>198.15,16271225=>226.15,16272520=>205.15,16272839=>207.49,16511860=>207.49,16511903=>235.67,16511954=>361.67,16512072=>235.67,16529382=>291.67,16529809=>205.15,16529828=>198.15,16638881=>231,16976141=>207.67,16976192=>198.15,16977897=>361.67,16977971=>169.8,16980685=>210,17200014=>198.15,17200188=>261.15,17202306=>228.49,17202316=>3763.67,17202681=>206.64,17395602=>205.15,17395789=>169.8,17395972=>198.15,17396659=>226.15,17607713=>169.8,17609629=>205.15,17609747=>251.82,17850360=>205.15,17851110=>198.15,17851384=>226.15,17851471=>64.6,18016356=>245,18017166=>226.15,18086947=>205.15,18148351=>221.49,18326744=>198.15,18327841=>169.8,18375979=>245,18543050=>64.6,18545335=>198.15,19250434=>198.15,19406537=>198.15,19601071=>198.16,19815087=>198.15,19816644=>198.15,20233801=>198.16,21315365=>198.15,25336893=>205.15),
    6=>array(1759038=>186.49,1852713=>186.49,2229521=>186.49,2229767=>186.49,3310937=>341.21,3350684=>186.49,3768372=>186.49,4138268=>186.49,4998780=>262.56,5307525=>186.49,8162961=>186.49,8165439=>186.49,8407921=>186.49,9591653=>186.49,9592421=>186.49,9877700=>186.49,13258876=>346.31),
    5=>array(345779=>186.49,365242=>186.49,882418=>186.49,883935=>186.49,1564258=>186.49,1830084=>186.49,1832833=>186.49,1834515=>186.49,1844441=>186.49,2220830=>186.49,2225609=>186.49,2225682=>186.49,2231180=>186.49,2233487=>189.53,2244209=>186.49,2966914=>186.49,3156520=>527.33,3432145=>339.44,3768897=>195.82,3769285=>186.49,3769975=>186.49,3770146=>186.49,3770434=>300.53,3770507=>300.53,3770711=>193.76,4138022=>242.14,4138027=>186.49,4139655=>208.7,4139914=>186.49,4141826=>186.49,4142282=>335.58,4142925=>272.36,4231113=>279.41,4367422=>186.49,4487929=>359.79,4667070=>463.68,4667535=>186.49,4667856=>186.49,4669337=>195.44,4670513=>186.49,4999234=>373.24,5330130=>195.97,5359819=>186.49,5359910=>240.45,5360960=>188.37,5361620=>186.49,5362951=>192.57,6083466=>326.2,8150006=>186.49,8151164=>194.9,8152880=>502.13,8154019=>516.13,8155228=>219.15,8156434=>186.49,8158618=>342.56,8161587=>272.56,8162020=>186.49,8162613=>502.13,8163591=>188.82,8165237=>186.49,8168850=>186.49,8169293=>269.95,8190497=>186.49,8190544=>229.74,8190761=>186.49,8191624=>251.78,8192478=>226.8,8195990=>230.16,8196224=>230.8,8196284=>230.8,8199939=>186.49,9590059=>186.49,9592871=>228.94,9594852=>258.8,9596677=>224.82,9597169=>230.8,9599823=>186.49,9663691=>186.49,9868657=>233.9,9870645=>230.8,9870879=>241.27,9870964=>186.49,9872885=>579.14,9874750=>230.8,9877268=>195.82,9877862=>186.49,9877891=>209.82,10057365=>230.8,10526571=>195.77,10616620=>187.53,10617589=>299.61,10619549=>230.8,10624971=>304.43,11244518=>230.8),
    11=>array(1566038=>351.17,3844973=>379.17,4669094=>358.17,4998074=>358.17,8151517=>532.47,8196163=>532.47,8900172=>504.47,9590112=>250.85,9868536=>390.83,9873898=>546.47,9876973=>504.47,10133418=>386.17,10622754=>388.5,10623995=>569.8,12143738=>216.21,12583527=>543.67,13639145=>504.47,14693818=>380.35,14948769=>351.17,15144487=>539.47),
    7=>array(315218=>186.49,880538=>186.49,881290=>186.49,882639=>186.49,884671=>186.49,884707=>186.49,884907=>186.49,885113=>186.49,885342=>186.49,886390=>186.49,889945=>186.49,939310=>186.49,1563369=>186.49,1615633=>203.78,1617671=>186.49,1830086=>186.49,1831155=>207.98,1832374=>186.49,1832594=>186.49,1832787=>186.49,1835384=>186.49,1835471=>186.49,1835499=>186.49,1837329=>186.49,1838103=>186.49,1839830=>186.49,1839986=>186.49,1840718=>186.49,1841088=>186.49,1841992=>186.49,2006235=>186.49,2102069=>186.49,2203846=>186.49,2208845=>186.49,2220951=>186.49,2222104=>186.49,2222535=>188.94,2224176=>186.49,2224272=>186.49,2224367=>186.49,2227523=>207.98,2229408=>186.49,2229631=>186.49,2230360=>186.49,2230997=>186.49,2234404=>186.49,2339587=>186.49,2470210=>186.49,2476276=>191.41,2514422=>192.3,2716126=>186.49,3287041=>186.49,3349384=>186.49,3349814=>186.49,3350344=>186.49,3350588=>191.46,3350614=>191.42,3350952=>215.51,3364949=>188.1,3365001=>186.49,3365157=>205.06,3365519=>186.49,3567851=>202.1,3768436=>186.49,3768678=>186.49,3769260=>186.49,3985646=>186.49,4138073=>186.49,4138207=>195.44,4138255=>186.49,4139224=>189.58,4139751=>186.49,4139886=>186.49,4139981=>186.49,4140312=>186.85,4140351=>186.49,4140698=>186.49,4141962=>186.49,4142846=>337.18,4393252=>186.49,4421881=>186.49,4551530=>186.49,4562453=>186.49,4667622=>374.78,4667930=>186.49,4668135=>186.49,4668314=>199.88,4668925=>186.49,4669654=>199.58,4670024=>200.02,4670905=>186.49,4671523=>194.82,4671582=>186.49,4671809=>186.49,4834997=>191.46,4998407=>186.49,4998488=>186.49,5272335=>216.35,5358358=>186.49,5358485=>186.49,5358934=>186.49,5361265=>207.14,5361322=>186.49,5361779=>196.78,5361851=>186.49,5362061=>186.49,5362172=>186.49,5362291=>216.35,5430638=>186.49,5955053=>186.49,6132019=>186.49,6160495=>186.49,6570559=>186.49,6630830=>210.75,6718482=>201.49,6769770=>186.49,6769779=>186.49,6936509=>201.49,7230975=>208.23,7261540=>186.49,7334670=>186.49,7653105=>198.46,8150533=>186.49,8151377=>186.49,8151905=>186.49,8152003=>186.49,8152667=>186.49,8152687=>186.49,8153412=>186.49,8153438=>186.49,8153808=>216.38,8154689=>194.82,8155153=>186.49,8155901=>186.49,8157072=>327.5,8157574=>186.49,8157675=>186.49,8158456=>336.18,8159497=>186.49,8159574=>186.49,8160033=>186.49,8160708=>186.49,8160924=>186.49,8161983=>201.7,8162658=>186.49,8162848=>186.49,8162892=>186.49,8163260=>186.49,8164192=>186.49,8165459=>186.49,8165475=>186.49,8165623=>216.38,8168350=>186.49,8168462=>216.38,8168637=>202.49,8168877=>186.49,8169286=>339.2,8169483=>186.49,8173709=>186.49,8190693=>186.49,8190886=>223.91,8192932=>218.06,8193012=>208.42,8193040=>216.35,8193357=>186.49,8193382=>337.19,8193874=>196.78,8194229=>244.38,8197681=>251.35,8198464=>218.06,8198765=>213.02,8357875=>186.49,8551711=>186.49,9053983=>186.49,9054121=>186.49,9108590=>186.49,9590171=>198.46,9591007=>204.87,9592927=>357.61,9594416=>219.15,9594986=>222.18,9595345=>201.49,9595744=>186.49,9596839=>201.49,9598385=>198.46,9599038=>209.35,9868900=>215.88,9870506=>186.49,9870761=>208.42,9871878=>186.49,9873027=>199.47,9873218=>200.14,9873373=>202.49,9875279=>213.27,9875306=>355.78,9875446=>186.49,9877576=>186.49,10016091=>336.18,10615721=>206.09,10615936=>197.62,10615993=>362.67,10616375=>186.49,10616566=>376.67,10617497=>229.49,10620100=>217.75,10621729=>186.49,10622009=>504,10622111=>209.07,11235168=>208.42,11235930=>201.49,11236973=>186.49,11237543=>375.16,11237581=>201.49,11237966=>201.49,11239292=>209.26,11239763=>246.16,11240121=>219.15,11241671=>210.94,11243438=>224.75,11754612=>224.75,11754626=>201.49,11757490=>251.35,11761722=>353.76,12325564=>339.2,13433788=>218.06,25289462=>200.86),
    8=>array(11235395=>186.49,12325242=>186.49)
   );
   $cedulas[7] = array(
     2=>array(12430271=>150,12323651=>300,11758856=>300,12322601=>150,9594021=>150,11758775=>300,10620328=>300,10922232=>300,6936488=>150,13639880=>450,11239109=>300,12323882=>150,11241874=>150,12585946=>450,14521083=>300,13805960=>150,10620395=>150,5362262=>300,12903875=>300,13805613=>300,9876504=>150,11758565=>150,9870707=>150,11235143=>150,12904981=>600,11761373=>150,12901372=>750,9874753=>150,8196370=>300,10617974=>150,13937695=>150,22576036=>300,15998275=>600,12322264=>450,5362315=>150,14948655=>300,12901395=>300,15680797=>900,9598875=>150),
     11=>array(4669094=>150,8196663=>300,8196633=>300,9873898=>300,9590112=>300,10133418=>300,10622754=>300,14693818=>150,15144487=>150,8151517=>300,9868536=>150),
     4=>array(9591765=>450,13465183=>450,11760367=>450,13640272=>300,11758132=>300,10754456=>450,6935912=>150,20089256=>150,14811447=>600,13938210=>150,14521474=>300,8192397=>750,10620766=>600,16767297=>600,10722223=>450,12231445=>300,12584663=>150,16272339=>300,10576438=>150,16528242=>300,13254597=>300,16270053=>150,19406710=>300,14330202=>300,4997368=>300,15047069=>600,11754357=>300,19430164=>150,11243117=>450,11758370=>300,9691888=>150,12901210=>300,18543342=>450,16270522=>300,18327798=>300,16000622=>300,8632610=>450,22576532=>750,9871507=>150,13806131=>150,15682848=>300,17396375=>300,14694692=>300,11242044=>450,10616396=>300,13806070=>450,11239707=>150,9870671=>300,16976108=>150,10618887=>450,16986240=>150,10616019=>600,12928447=>600,11754993=>150,12424689=>300,15513219=>450,16475588=>300,14520550=>300,11240054=>450,8197767=>300,9875347=>450,11242371=>450,6630858=>150,13938435=>450,17850849=>300,17609364=>300,18146990=>300,15680645=>300,10615917=>300,9876763=>300,11619775=>150,14224002=>450,9596489=>150,13560127=>150,10615711=>300,13937591=>300,11761103=>150,11755412=>150,10265587=>600,18146613=>300,12200627=>300,8192723=>450,17395825=>150,14948612=>600,12903914=>150,13507878=>300,6935937=>150,13390212=>450,16270965=>300,10623380=>450,14520248=>900,14520822=>300,11760380=>150,6494936=>450,17609745=>150),
     7=>array(5538485=>450,8150533=>150,9592927=>300,13433788=>600,9873027=>300,12325564=>300,8168350=>450,11235930=>150,7261540=>150,8194229=>300,8153808=>150,5361779=>300,6718482=>150,8159574=>150,10620100=>450,10615721=>150,4142846=>300,11237543=>150,11761722=>300,11239763=>300,8165475=>150,11243438=>150,9877576=>300,10016091=>150,5362291=>150,7334670=>150,9590171=>300,10621729=>600,5361265=>150,2234404=>150,5955053=>450,11237581=>150,5272335=>450,8357875=>150,11754612=>300,8162892=>300,11240121=>600,8193382=>450,9875279=>300,9873373=>300,9599038=>150,9870506=>150,9875306=>150,6160495=>150,2102069=>150,8193012=>150),
     3=>array(4138385=>150,4668595=>150,6687276=>150,7210433=>150,8153063=>300,8155558=>600,8168126=>150,8168726=>150,8193677=>300,8196465=>150,8197808=>450,8337706=>150,9593397=>300,9594772=>450,9870652=>150,9871323=>150,9871784=>150,9872000=>300,9872424=>300,9873996=>150,9874842=>150,10615468=>150,10619631=>600,10623390=>300,10624839=>150,11236490=>300,11238759=>300,11240263=>450,11241195=>150,11241752=>300,11242040=>150,11753171=>150,11754218=>150,11754679=>600,11755523=>150,11757215=>300,11757253=>450,11757572=>300,11757173=>450,11759491=>150,11760064=>300,11760118=>150,11764015=>300,12322412=>300,12323655=>300,12324922=>150,12389775=>150,12581849=>300,12583445=>450,12583655=>150,12584083=>150,12584250=>150,12584744=>150,12903504=>300,12903802=>600,12903951=>450,12904763=>600,13254595=>300,13426664=>300,13433538=>150,13459273=>150,13488978=>300,13559798=>300,13559932=>150,13560110=>300,12560363=>150,13560383=>300,13640676=>300,13805930=>150,13806705=>300,13806815=>150,13937201=>150,13937718=>150,13938121=>150,13938431=>150,14218135=>450,14343717=>300,14343815=>300,14520422=>450,14694265=>150,14694285=>300,14973137=>300,15144199=>150,15144260=>150,15146522=>450,15375634=>450,15512580=>150,15513281=>300,15681703=>150,15682397=>150,15682521=>150,15047234=>450,15998685=>150,15999208=>300,16000012=>150,16270204=>300,16272520=>150,16272839=>150,16976172=>300,16977897=>150,16977971=>150,16511860=>150,16529382=>150,16529809=>150,11757572=>450,16980685=>150,16529828=>300,17200188=>300,17202681=>150,17395789=>300,17607723=>300,17609629=>150,17850360=>450,18016356=>150,18326744=>150,18327841=>300,18375979=>150,19815087=>150,19601071=>150,25336893=>150),
     1=>array(4998549=>150,6363812=>150,6937987=>150,6942627=>150,8168909=>150,8196623=>150,8196638=>150,8198491=>300,8794495=>300,9597831=>150,9872342=>300,9876346=>150,10526571=>150,10615173=>450,10616848=>300,10617730=>150,10618812=>150,10619468=>150,10620985=>150,10620611=>300,10624281=>150,11235936=>300,11237486=>450,11243750=>150,11244221=>150,11759818=>300,12321741=>600,12322709=>150,12324901=>150,12581050=>600,12585236=>300,13433130=>300,13559869=>150,14947728=>300,14812578=>150,18016832=>150),
     5=>array(3770146=>150,3770507=>150,4667070=>300,4487929=>150,8151165=>300,8190497=>150,8191624=>300,8195990=>150,8196224=>150,9597169=>150,9663691=>150,9868657=>150,9870645=>150,9870964=>150,9877862=>150,9877891=>300,10617589=>150,10623666=>150,10624971=>150),
     6=>array(9591653=>300)


   );*/

   $sql_insert="INSERT INTO cnmd07_transacciones_actuales(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina,cod_cargo, cod_ficha, cod_tipo_transaccion, cod_transaccion,fecha_transaccion, monto_original, numero_cuotas_descontar, numero_cuotas_cancelar,numero_cuotas_canceladas, monto_cuota, saldo, marca_fin_descuento,fecha_proceso, username) VALUES ";
   $sql_insert2 = "";
   foreach($cedulas as $transaccion=>$nom_cedula){
   	    $cod_tipo_transaccion = 1;
        $cod_transaccion = $transaccion;
        $values=array();
        foreach($nom_cedula as $nominass =>$cedula_monto){
              $cod_tipo_nomina = $nominass;
        	  foreach($cedula_monto as $cedula_identidad=>$monto_cuota){
                 $sql_fichas = "SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina,cod_cargo, cod_ficha, cedula_identidad FROM cnmd06_fichas WHERE cod_tipo_nomina=$cod_tipo_nomina AND cedula_identidad=$cedula_identidad;";
                 $da = $this->Cnmd01->execute($sql_fichas);
	             foreach($da as $result_f){
	              	     extract($result_f[0]);
	                     $sql_insert2 .= $sql_insert." ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $cod_tipo_nomina,$cod_cargo, $cod_ficha, $cod_tipo_transaccion, $cod_transaccion,CURRENT_DATE, 0, 0, 1,0, $monto_cuota, 0, '0',CURRENT_DATE, 'ADMIN_APURE_ASF');\n";
	              }
        	  }

        }
        //$sql_insert = $sql_insert." ".implode(",\n",$values);
        //$sql_insert."; \n\n";
        //$sql_insert2 =$sql_insert;
   }
    $_SESSION['nombre_txt'] = 'transa.txt';
    $this->set('sql_insert',$sql_insert2);
    $this->render('vacio');




}//fin funcion insertar_transacciones





function cargar_archivo_cuentas ($aleatorio) {
    $this->layout="txt";
    $cod_presi     = $this->Session->read('SScodpresi');
	$cod_entidad   = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst      = $this->Session->read('SScodinst');
	$cod_dep       = $this->Session->read('SScoddep');
	$username      = $this->Session->read('nom_usuario');

   //pr($this->params);
   //pr($this->data);

   if (!empty($this->params['form']) && is_uploaded_file($this->params['form']['File']['tmp_name'])){
             $fileOK = $this->uploadFiles('files', $this->params['form']['File']);
			// si el archivo fue subido correctamente
			//echo "hola<br>";
			if(array_key_exists('urls', $fileOK)) {
			    //guardar la url en la informacion del form
			    //pr($fileOK);
			    $archivo_url=$fileOK['urls'][0];
			    $archivo = file($fileOK['urls'][0]);
				$lineas = count($archivo);

				$nombre_archivo = 'REGISTROS_NO_ACTUALIZADOS_'.date('d_m_Y_h:i:sa').'';
				$_SESSION["nombre_txt"]=$nombre_archivo.".txt";
                $t_up = 0;
                $t_up_no = 0;
                $t_cu_no = 0;
                $fila="-------------------NO ACTUALIZADOS----------------------\n";
                $fila2="";
				for($i=0; $i < $lineas; $i++){
				        /**/
				        $campos = explode(";",$archivo[$i]);
				        $cedula = trim($campos[0]);
				        $cedula = (int) $cedula;
				        $cuenta = trim($campos[1]);
				        $cod_entidad_bancaria = (int) substr($cuenta,0,4);
						$cod_sucursal = (int) substr($cuenta,4,4);
						$numero_cuenta = substr($cuenta,8,12);
						//echo "<br>".$cedula." / ".$cod_entidad_bancaria."-".$cod_sucursal."-".$numero_cuenta;
                        if(strlen($cuenta)==20){
//							$contar_existe = $this->cnmd03_transacciones->execute("SELECT count(*) as c FROM cnmd06_fichas WHERE cedula_identidad=$cedula;");
							$contar_existe = $this->cnmd03_transacciones->execute("SELECT cedula_identidad FROM cnmd06_fichas WHERE cedula_identidad=$cedula;");
	                        if(isset($contar_existe[0][0]['cedula_identidad'])){
	                        	$t_up++;
								$this->cnmd03_transacciones->execute("UPDATE cnmd06_fichas SET cod_entidad_bancaria=$cod_entidad_bancaria, cod_sucursal=$cod_sucursal, cuenta_bancaria='$cuenta' WHERE cedula_identidad=$cedula;");
	                        }else{
                                $t_up_no++;
                                $fila .= "".$cedula.";".$cuenta."\n";
	                        }
                        }else{
                        	$t_cu_no++;
                            $fila2 .= "".$cedula.";".$cuenta."\n";
                        }

				        //*/
					}

					$fila .= "-------------NUMERO CUENTA INVALIDO---------------------\n";
					$fila .= $fila2;
					$fila .= "--------------------------------------------------------\n";
					$fila .= "Total Acutalizados   :".$t_up."\n";
					$fila .= "Total No Acutalizados:".$t_up_no."\n";
					$fila .= "Total Registros con cuentas que incumplen los 20 digitos:".$t_cu_no."\n";

					$this->wFile($nombre_archivo, $fila);
		            $this->set('filas_archivo',$fila);


				/**
//error_reporting(E_ALL);
//set_include_path(get_include_path() . PATH_SEPARATOR . ROOT.'/excel/');
vendor('Excel/reader');
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
$data->read($archivo_url);
error_reporting(E_ALL ^ E_NOTICE);
//pr($data);
$datos_incompletos = array();
for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
	$dd=0;
	for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
		//echo "\"".$data->sheets[0]['cells'][$i][$j]."\",";
		if($data->sheets[0]['cells'][$i][$j]==''){
           $dd++;
		}
	}
	    $cedula   = $data->sheets[0]['cells'][$i][1];
		$cuenta        = $data->sheets[0]['cells'][$i][2];
             if(strlen($cuenta)==20){
				$cod_entidad_bancaria = substr($cuenta,0,4);
				$cod_sucursal = substr($cuenta,4,4);
				$numero_cuenta = substr($cuenta,2,12);
				$correcto = 'SI';
				$sql = "";
		     $insertar = true;
			}else{
				$cod_entidad_bancaria = substr($cuenta,0,4);
				$cod_sucursal = substr($cuenta,4,4);
				$numero_cuenta = substr($cuenta,2,12);
				$correcto = 'NO';
				$sql ="Select 12*-4;";
				$insertar = false;
			}

			//echo "<br>".$cod_entidad_bancaria."-".$cod_sucursal."-".$numero_cuenta."-".$cedula;

		$result = $this->cnmd03_transacciones->execute($sql);
		if($insertar==true){
	       $datos_completos[]=array(
	       "cedula"=>$cedula,
	       "cuenta"=>$cuenta,
	       "completo"=>$correcto,
	       "guardado"=>'SI'
	       );
		}else{
			$datos_completos[]=array(
		    "cedula"=>$cedula,
	       "cuenta"=>$cuenta,
	       "completo"=>$correcto,
	       "guardado"=>'NO'
	       );
		}





}
//echo $i;
//$this->set('DATA',$datos_completos);
//print_r($datos_completos);
echo "<center><h2>Cuentas cargadas</h2></center>";

/***/

/*
 * for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
	for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
		echo "\"".$data->sheets[0]['cells'][$i][$j]."\",";
	}
	echo "<br>\n";

}
 */

					/**
					 * solo para archivo de textos
					 */
//					for($i=0; $i < $lineas; $i++){
				            /*$campos = explode(';',$archivo[$i]);
				            $c_campos = count($campos);
				            if($c_campos!=1 && $c_campos==21){
				                for($j=0; $j<$c_campos;$j++){
				                   $campos_aux[$j] = "'".str_replace('"','',$campos[$j])."'";
				                }
				                echo "<br>(".implode(',',$campos_aux).")";
				            }*/
				        /*$cedula = substr($archivo[$i],3,8);
				        $pre = substr($cedula,0,4);
				        $cuenta = substr($archivo[$i],109,20);
				        if(strlen($archivo[$i])==134){
				        	if($pre == "ente" || $pre== "0009" || $pre == "M413" || $pre == "----" || $pre == ".DE "){
				        		//echo "<br/>".substr($cedula,0,4)."/".$cedula." -".$cuenta;
				        	}else{
				        		echo "<br/>".$cedula." -".$cuenta;
				        	}
				        }
				        */
				        /**
				        $campos = explode("\t",$archivo[$i]);
				        echo "<br/>".$campos[0]."-".'"'.trim($campos[1]).'"';
				        */
//					}
					/**/
			 //unlink($fileOK['urls'][0]);
			}//fin file ok
			//pr($fileOK);
			//echo "HOLA jose";
    }


}//fin funcion cargar_archivo_cuentas


function costo_municipio ($generar=null) {
    $this->layout="pdf";
	if($generar=='pdf') {
		 $nom_cmun = $this->v_cnmp99_nomina_costo_municipios->findAll($this->SQLCA(), null, null);
		 $this->set("nom_cmun", $nom_cmun);
         $this->render('costo_municipio','pdf');
    }
} // fin function costo_municipio


function costo_total_nomina ($generar=null) {
    $this->layout="pdf";
	if($generar=='pdf') {
		 $nom_ctotal = $this->v_cnmp99_costo_nominas->findAll($this->SQLCA(), null, null);
		 $this->set("nom_ctotal", $nom_ctotal);
         $this->render('costo_total_nomina','pdf');
   			 }

		} // fin function costo_total_nomina

}//fin class
?>
