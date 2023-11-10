<?php

 class Cnmp99PrenominaCorridaDefinitivaController extends AppController{


 	var $uses = array('ccfd04_cierre_mes','Cnmd01','cnmd09_numero_nominas_canceladas','cnmd07_calculo_aguinaldos','cnmd07_calculo_bonovaca','cnmd09_lunes_ejercicio','cnmd09_incidencia_sueldo_sugerido','v_cnmd07_transacciones_actuales_frecuencias2','trasacciones_no_conectadas','cargos_anos_diferentes');
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
    $lista = $this->Cnmd01->generateList($this->SQLCA()." and status_nomina=1", 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
    //$lista = $this->comboBox('v_cnmd07_transacciones_actuales_frecuencias2','cod_tipo_nomina','denominacion_nomina',$this->SQLCA());//generateList($this->SQLCA(), 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($this->Cnmd01->findCount($this->SQLCA()." and status_nomina=1")!=0){
		$this->concatena($lista, 'tipo_nomina');
	}else{
		$this->set('tipo_nomina', array());
	}

	//cnmd09_numero_nominas_canceladas
}//fin index

function deno_nomina ($cod_tipo_nomina=null) {
     $this->layout="ajax";
     if (isset($cod_tipo_nomina)) {
         $lista = $this->Cnmd01->findAll($this->SQLCA()." and status_nomina=1 and cod_tipo_nomina=".$cod_tipo_nomina,'cod_tipo_nomina, denominacion,numero_nomina,periodo_desde,periodo_hasta,correspondiente,cantidad_pagos,frecuencia_pago');
		if($this->Cnmd01->findCount($this->SQLCA()." and status_nomina=1 and cod_tipo_nomina=".$cod_tipo_nomina)!=0){
			$this->set( 'nomina',$lista);

		}else{
			$this->set('nomina', array());
		}
	}
}

function procesar () {
	$this->layout="ajax";
	$cod_presi     = $this->Session->read('SScodpresi');
	$cod_entidad   = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst      = $this->Session->read('SScodinst');
	$cod_dep       = $this->Session->read('SScoddep');


	if(!empty($this->data["cnmp99_prenomina"]["correspondientes"])){
		 $datos=$this->data["cnmp99_prenomina"];
         $cod_tipo_nomina      = $datos["cod_tipo_nomina"];
         $desde_periodo      = $datos["desde_periodo"];
         $correspondientes     = $datos["correspondientes"];
             	$c=$this->trasacciones_no_conectadas->findCount($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
             	$car=$this->cargos_anos_diferentes->findCount($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and ano_transaccion=".divide_fecha($desde_periodo,'ANO'));

             	if($c==0 && $car==0){
	             	$parametros_corrida=$cod_presi.",".$cod_entidad.",".$cod_tipo_inst.",".$cod_inst.",".$cod_dep.",".$cod_tipo_nomina;
	                $func_prenomina_revision=$this->Cnmd01->execute("SELECT corrida_definitiva_nimina($parametros_corrida);");
	                if($func_prenomina_revision>0){
	                    $sql_update_cnmd01="UPDATE cnmd01 SET correspondiente='".$correspondientes."', status_nomina=2  WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina;
	                    $this->Cnmd01->execute($sql_update_cnmd01);
	                	$this->set('Message_existe','Corrida definitiva de N&oacute;mina realizada Exitosamente');
	                }else{
	                	$this->set('errorMessage','No se pudo realizar la corrida definitiva de n&oacute;mina');
	                }/**/

             	}else{
             		if($car!=0){
                        $this->set('errorMessage','Hay cargos no conectados para el año actual');
             		}else{
             			$this->set('errorMessage','Hay transacciones sin conectar al presupuesto');
             			$this->set('data',$this->trasacciones_no_conectadas->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina));
             		}

             	}

	}else{
         $this->set('errorMessage','Hay campos sin llenar, Verifique el formulario');
	}
}//fin procesar

function salir_prenomina ($numero) {
       $this->layout="ajax";
}//fin salir_prenomina


















}//fin class
?>
