<?php

 class Cnmp99CorridaDefinitivaController extends AppController{


 	var $uses = array('ccfd04_cierre_mes','Cnmd01','cnmd09_numero_nominas_canceladas','cnmd07_calculo_aguinaldos','cnmd07_calculo_bonovaca','cnmd09_lunes_ejercicio','cnmd09_incidencia_sueldo_sugerido','v_cnmd07_transacciones_actuales_frecuencias2','trasacciones_no_conectadas','cargos_anos_diferentes','fichas_generar_recibo','cugd05_restriccion_clave','costo_presupuestario_p2');
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

$this->verifica_entrada('94');

    $this->layout="ajax";
    $lista = $this->Cnmd01->generateList($this->SQLCA()." and status_nomina=1", 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($this->Cnmd01->findCount($this->SQLCA()." and status_nomina=1")!=0){
		$this->concatenaN($lista, 'tipo_nomina');
	}else{
		$this->set('tipo_nomina', array());
	}
}//fin index

function deno_nomina ($cod_tipo_nomina=null) {
     $this->layout="ajax";
     if (isset($cod_tipo_nomina)) {
         $lista = $this->Cnmd01->findAll($this->SQLCA()." and status_nomina=1 and cod_tipo_nomina=".$cod_tipo_nomina,'cod_tipo_nomina, denominacion,numero_nomina,periodo_desde,periodo_hasta,correspondiente,cantidad_pagos,frecuencia_pago,modalidad_pago');
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
             	$c=$this->trasacciones_no_conectadas->findCount($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_tipo_transaccion<>1 and cod_transaccion<>1");
             	$car=$this->cargos_anos_diferentes->findCount($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and ano_transaccion=".divide_fecha($desde_periodo,'ANO'));
				

				//VERIFICACION QUE LAS PARTIDAS NO ESTEN EN NEGATIVO
             	$presupuesto_negativo=$this->costo_presupuestario_p2->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and ano=".divide_fecha($desde_periodo,'ANO'));
             	$negativo = 0;
             	foreach ($presupuesto_negativo as $pnegativo) {
             		if($pnegativo["costo_presupuestario_p2"]["diferencia"]<0)
             		{
             			$negativo++;
             		}
             	}


             	if($c==0 && $car==0 && $negativo==0){
             		$cantidad_conex1 = $this->Cnmd01->execute("SELECT count(*) as c FROM cantidad_cnmd05_cfpd05 where ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cantidad_cfpd05=0  and condicion_actividad=2");

                    if($cantidad_conex1[0][0]['c']==0 || $cantidad_conex1[0][0]['c']=='0'){
                    	$cantidad_conex2 = $this->Cnmd01->execute("SELECT count(*) as c FROM cantidad_conexion_cfpd05 where ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cantidad_cfpd05=0");
	                    if($cantidad_conex2[0][0]['c']==0 || $cantidad_conex2[0][0]['c']=='0'){
			             	$parametros_corrida=$cod_presi.",".$cod_entidad.",".$cod_tipo_inst.",".$cod_inst.",".$cod_dep.",".$cod_tipo_nomina;
			                $func_prenomina_revision=$this->Cnmd01->execute("SELECT corrida_definitiva_nomina($parametros_corrida);");
			                if($func_prenomina_revision>0){
			                    $sql_update_cnmd01="UPDATE cnmd01 SET correspondiente='".$correspondientes."', status_nomina=2  WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina;
			                    $this->Cnmd01->execute($sql_update_cnmd01);
			                    $data_recibo=$this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina,'numero_recibo');
		                        $ultimo_recibo = $data_recibo[0]['Cnmd01']['numero_recibo'];
			                    $cantidad_fichas= $this->fichas_generar_recibo->findCount($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
			                    $fichas=$this->fichas_generar_recibo->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
			                    $sql_update ="";
			                    foreach($fichas as $rs_fichas) {
			                    	$rsf=$rs_fichas['fichas_generar_recibo'];
			                    	$ultimo_recibo++;
		                            $sql_update .="UPDATE cnmd06_fichas SET ultimo_recibo=".$ultimo_recibo."   WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=".$rsf['cod_cargo']." and cod_ficha=".$rsf['cod_ficha'].";";
			                    }
			                    $this->Cnmd01->execute($sql_update);
			                    $sql_update_cnmd01_2="UPDATE cnmd01 SET numero_recibo=".$ultimo_recibo." WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina;
			                    $this->Cnmd01->execute($sql_update_cnmd01_2);
			                	$this->set('Message_existe','Corrida definitiva de N&oacute;mina realizada Exitosamente');
			                }else{
			                	$this->set('errorMessage','No se pudo realizar la corrida definitiva de n&oacute;mina');
			                }/**/

                       }else{
	                       $data_conex2 = $this->Cnmd01->execute("SELECT * FROM cantidad_conexion_cfpd05 where ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cantidad_cfpd05=0");
	                       $this->set('DATA_CONEX1',$data_conex2);
	                       $this->set('errorMessage','Conexión de la Clasificación presupuestaria no formulada para el ejercicio');
			         }
                    }else{
                       $data_conex1 = $this->Cnmd01->execute("SELECT * FROM cantidad_cnmd05_cfpd05 where ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cantidad_cfpd05=0  and condicion_actividad=2");
                       $this->set('DATA_CONEX1',$data_conex1);
                       $this->set('errorMessage','Cargos de la institución - Clasificación presupuestaria no formulada para el ejercicio');
                    }


             	}else{
             		if($car!=0){
                        $this->set('errorMessage','Hay cargos no conectados para el año actual');
             		}else if($c!=0){
             			$this->set('errorMessage','Hay transacciones sin conectar al presupuesto');
             			$this->set('data',$this->trasacciones_no_conectadas->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina));
             		}else if($negativo!=0){
             			$this->set('errorMessage','Existen '. $negativo . ' partidas en negativo. Revise el costo presupuesto');
             		}

             	}

	}else{
         $this->set('errorMessage','Hay campos sin llenar, Verifique el formulario');
	}
}//fin procesar

function salir_prenomina ($numero=null) {
       $this->layout="ajax";
       $this->Session->delete("autor_valido");
}//fin salir_prenomina

function seleccion_nomina () {
    $this->layout="ajax";
    $lista = $this->Cnmd01->generateList($this->SQLCA()." and status_nomina=1", 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($this->Cnmd01->findCount($this->SQLCA()." and status_nomina=1")!=0){
		$this->concatena($lista, 'tipo_nomina');
	}else{
		$this->set('tipo_nomina', array());
	}
}//fin seleccion_nomina



function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cnmp99_corrida_definitiva']['login']) && isset($this->data['cnmp99_corrida_definitiva']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cnmp99_corrida_definitiva']['login']);
		$paswd=addslashes($this->data['cnmp99_corrida_definitiva']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=94 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->Session->write('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->Session->write('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('errorMessage',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->index("autor_valido");
			$this->render("index");
		}
	}
}

function entrar_reverso(){
	$this->layout="ajax";
	if(isset($this->data['cnmp99_corrida_definitiva']['login']) && isset($this->data['cnmp99_corrida_definitiva']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cnmp99_corrida_definitiva']['login']);
		$paswd=addslashes($this->data['cnmp99_corrida_definitiva']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=121 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->Session->write('autor_valido',true);
			$this->index_reverso_corrida("autor_valido");
			$this->render("index_reverso_corrida");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->Session->write('autor_valido',true);
			$this->index_reverso_corrida("autor_valido");
			$this->render("index_reverso_corrida");
		}else{
			$this->set('errorMessage',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->index_reverso_corrida("autor_valido");
			$this->render("index_reverso_corrida");
		}
	}
}


function index_reverso_corrida () {

$this->verifica_entrada('121');

    $this->layout="ajax";
    $lista = $this->Cnmd01->generateList($this->SQLCA()." and status_nomina=2", 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($this->Cnmd01->findCount($this->SQLCA()." and status_nomina=2")!=0){
		$this->concatenaN($lista, 'tipo_nomina');
	}else{
		$this->set('tipo_nomina', array());
	}
}//fin index_reverso_corrida

function deno_nomina_reverso_corrida ($cod_tipo_nomina=null) {
     $this->layout="ajax";
     if (isset($cod_tipo_nomina)) {
         $lista = $this->Cnmd01->findAll($this->SQLCA()." and status_nomina=2 and cod_tipo_nomina=".$cod_tipo_nomina,'cod_tipo_nomina, denominacion,numero_nomina,periodo_desde,periodo_hasta,correspondiente,cantidad_pagos,frecuencia_pago,modalidad_pago');
		if($this->Cnmd01->findCount($this->SQLCA()." and status_nomina=2 and cod_tipo_nomina=".$cod_tipo_nomina)!=0){
			$this->set( 'nomina',$lista);

		}else{
			$this->set('nomina', array());
		}
	}

}

function seleccion_nomina_reverso_corrida () {
    $this->layout="ajax";
    $lista = $this->Cnmd01->generateList($this->SQLCA()." and status_nomina=2", 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($this->Cnmd01->findCount($this->SQLCA()." and status_nomina=2")!=0){
		$this->concatena($lista, 'tipo_nomina');
	}else{
		$this->set('tipo_nomina', array());
	}
}//fin seleccion_nomina


function procesar_reverso_corrida () {
	$this->layout="ajax";
	$cod_presi     = $this->Session->read('SScodpresi');
	$cod_entidad   = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst      = $this->Session->read('SScodinst');
	$cod_dep       = $this->Session->read('SScoddep');


	if(!empty($this->data["cnmp99_prenomina"]["correspondientes"])){
		 $datos=$this->data["cnmp99_prenomina"];
         $cod_tipo_nomina      = $datos["cod_tipo_nomina"];
         $desde_periodo        = $datos["desde_periodo"];
         $correspondientes     = $datos["correspondientes"];

			             	$parametros_corrida=$cod_presi.",".$cod_entidad.",".$cod_tipo_inst.",".$cod_inst.",".$cod_dep.",".$cod_tipo_nomina;
			                $func_prenomina_revision=$this->Cnmd01->execute("SELECT deshacer_corrida_definitiva_nomina($parametros_corrida);");
			                if($func_prenomina_revision>0){
			                    $sql_update_cnmd01="UPDATE cnmd01 SET status_nomina=1  WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina;
			                    $this->Cnmd01->execute($sql_update_cnmd01);
			                	$this->set('Message_existe','Reverso de Corrida definitiva de N&oacute;mina realizada Exitosamente');
			                }else{
			                	$this->set('errorMessage','No se pudo realizar el Reverso de la corrida definitiva de n&oacute;mina');
			                }/**/



	}else{
         $this->set('errorMessage','Hay campos sin llenar, Verifique el formulario');
	}
}//fin procesar_reverso_corrida




}//fin class
?>