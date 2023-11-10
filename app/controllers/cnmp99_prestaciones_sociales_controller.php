<?php

 class Cnmp99PrestacionesSocialesController extends AppController{

	var $name = 'cnmp99_prestaciones_sociales';
 	var $uses = array('ccfd04_cierre_mes','Cnmd01','cnmd15_datos_personales','cnmd15_devengado','cnmd15_datos_prestaciones','cnmd15_datos_intereses','cnmd15_datos_intereses_mora','cnmd15_anticipo_bono_transf','cnmd15_firmas_informes','cnmd03_transacciones','cnmd09_numero_nominas_canceladas','cnmd07_calculo_aguinaldos','cnmd07_calculo_bonovaca','cnmd09_lunes_ejercicio','cnmd09_incidencia_sueldo_sugerido','cnmd09_dias_trabajados_ingreso_egreso','cugd01_estados','cugd02_institucion');
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


function compara_fechas_completa($fecha1,$fecha2){

// La funcion usa expresiones regulares para que admita fechas tanto en formato: dd-mm-aaaa como con formato: dd/mm/aaaa

      if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha1))


              list($dia1,$mes1,$año1)=split("/",$fecha1);


      if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha1))


              list($dia1,$mes1,$año1)=split("-",$fecha1);
        if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha2))


              list($dia2,$mes2,$año2)=split("/",$fecha2);


      if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha2))


              list($dia2,$mes2,$año2)=split("-",$fecha2);
        $dif = mktime(0,0,0,$mes1,$dia1,$año1) - mktime(0,0,0, $mes2,$dia2,$año2);
        return ($dif);
}


function index ($enviard=null) {
    $this->layout="ajax";
    $this->Session->delete('codigo_tipo_nomina');
    $lista = $this->Cnmd01->generateList($this->SQLCA()."", 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($this->Cnmd01->findCount($this->SQLCA())!=0){
		$this->concatenaN($lista, 'tipo_nomina');
	}else{
		$this->set('tipo_nomina', array());
	}
	if($enviard!=null){
		$this->set('tipo_reporte', $enviard);
		if($enviard==1 || $enviard=='1'){
			$this->envia_form_firmas(23101); // Detalles de Prestaciones Sociales
		}else{
			$this->envia_form_firmas(23102); // Detalles de Intereses
		}
	} // fin if tipo de reporte

}//fin index


function index_resumido ($enviarda=null) {
    $this->layout="ajax";
    $this->Session->delete('codi_tipo_nomina');
    $this->Session->delete('tipo_busqueda_op');
    $this->Session->delete('datos_imp');
    $lista = $this->Cnmd01->generateList($this->SQLCA()."", 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($this->Cnmd01->findCount($this->SQLCA())!=0){
		$this->concatenaN($lista, 'tipo_nomina');
	}else{
		$this->set('tipo_nomina', array());
	}
	if($enviarda!=null){
		$this->set('tipo_reportea', $enviarda);
		if($enviarda==3 || $enviarda=='3'){
			$this->envia_form_firmas(23103); // Resumen de Prestaciones Sociales
		}else{
			$this->envia_form_firmas(23104); // Resumen de Intereses
		}
	} // fin if tipo de reporte

}//fin index_resumido



function deno_nomina ($cod_tipo_nomina=null) {
     $this->layout="ajax";
     if (isset($cod_tipo_nomina)) {
         $lista = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina,'cod_tipo_nomina, denominacion');
		if($this->Cnmd01->findCount($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina)!=0){
			$this->Session->write('codigo_tipo_nomina', $lista[0]['Cnmd01']['cod_tipo_nomina']);
			$this->set('tipo_nomina',$lista);
		}else{
			$this->Session->write('codigo_tipo_nomina', 0);
			$this->set('tipo_nomina', array());
		}
	if($this->cnmd15_datos_personales->findCount($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina)==0){

		echo "<script> fun_msj('NO SE ENCONTRAR&Oacute;N DATOS PARA PROCESAR');</script>";
			echo '<script>document.getElementById("empleado_ide").style.visibility="hidden";</script>';
			echo "<script> document.getElementById('empleado_ide').innerHTML='';
							if(document.getElementById('id_cedula_identidad')){ document.getElementById('id_cedula_identidad').value=''; }
						   	if(document.getElementById('id_nombre_empleado')){ document.getElementById('id_nombre_empleado').value=''; }
						   	document.getElementById('id_enviar_generar').disabled=true;
				</script>";
	}else{
		$this->tipo_proceso_envio(2);
	}
	}
}



function deno_nomina2 ($cod_tipo_nomina=null) {
     $this->layout="ajax";
     if (isset($cod_tipo_nomina)) {
         $lista = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina,'cod_tipo_nomina, denominacion');
		if($this->Cnmd01->findCount($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina)!=0){
			$this->Session->write('codi_tipo_nomina', $lista[0]['Cnmd01']['cod_tipo_nomina']);
			$this->set('tipo_nomina',$lista);
		}else{
			$this->Session->write('codi_tipo_nomina', 0);
			$this->set('tipo_nomina', array());
		}
		if($this->cnmd15_datos_personales->findCount($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina)==0){
			echo "<script> fun_msj('NO SE ENCONTRAR&Oacute;N DATOS PARA PROCESAR');
				document.getElementById('tipo_proceso_1').disabled=true;
  				document.getElementById('tipo_proceso_2').disabled=true;
  				document.getElementById('tipo_proceso_3').disabled=true;
  				document.getElementById('tipo_proceso_1').checked=false;
  				document.getElementById('tipo_proceso_2').checked=false;
  				document.getElementById('tipo_proceso_3').checked=false;
  				document.getElementById('id_enviar_generar_rp').disabled=true;
			</script>";
		}else{
			echo "<script>
				document.getElementById('tipo_proceso_1').disabled=false;
  				document.getElementById('tipo_proceso_2').disabled=false;
  				document.getElementById('tipo_proceso_3').disabled=false;
  				document.getElementById('tipo_proceso_1').checked=false;
  				document.getElementById('tipo_proceso_2').checked=false;
  				document.getElementById('tipo_proceso_3').checked=false;
  			</script>";

		}
	}
}


function deno_nomina3 ($tipo_envio=null, $cod_tipo_nomina=null) {
     $this->layout="ajax";
     if (isset($cod_tipo_nomina)) {
         $lista = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina,'cod_tipo_nomina, denominacion');
		if($this->Cnmd01->findCount($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina)!=0){
			$this->Session->write('cons_codigo_tipo_nomina', $lista[0]['Cnmd01']['cod_tipo_nomina']);
			$this->set('tipo_nomina',$lista);
		}else{
			$this->Session->write('cons_codigo_tipo_nomina', 0);
			$this->set('tipo_nomina', array());
		}
	if($this->cnmd15_datos_personales->findCount($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina)==0){

		echo "<script> fun_msj('NO SE ENCONTRAR&Oacute;N DATOS PARA PROCESAR');</script>";
			echo '<script>document.getElementById("empleado_ide").style.visibility="hidden";</script>';
			echo "<script> document.getElementById('empleado_ide').innerHTML='';
							if(document.getElementById('id_cedula_identidad')){ document.getElementById('id_cedula_identidad').value=''; }
						   	if(document.getElementById('id_nombre_empleado')){ document.getElementById('id_nombre_empleado').value=''; }
						   	document.getElementById('submit_pdf').disabled=true;
				</script>";
	}else if($tipo_envio==3 || $tipo_envio=='3'){
		$this->tipo_proceso_envio(3); // Envio por Constancia de Prestaciones Sociales
	}else if($tipo_envio==4 || $tipo_envio=='4'){
		$this->tipo_proceso_envio(4); // Envio por Antecedente de Servicio
	}else{
		echo "<script> fun_msj('NO LLEG&Oacute; EL TIPO DE ENVIO PARA PROCESAR');</script>";
	}
	}
}


function envia_form_firmas($num_tipo_doc=null){
    $this->layout="ajax";

	if($num_tipo_doc!=null){

	$firmantes = $this->cnmd15_firmas_informes->findAll($this->SQLCA()." and tipo_documento=".$num_tipo_doc);

	if($firmantes!=null){
		$this->set('firma_existe','si');
		$this->set('b_readonly','readonly');
		$this->set('tipo_documento',$firmantes[0]['cnmd15_firmas_informes']['tipo_documento']);
		$this->set('nombre_primera_firma',$firmantes[0]['cnmd15_firmas_informes']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cnmd15_firmas_informes']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cnmd15_firmas_informes']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cnmd15_firmas_informes']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cnmd15_firmas_informes']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cnmd15_firmas_informes']['cargo_tercera_firma']);
		$this->set('nombre_cuarta_firma',$firmantes[0]['cnmd15_firmas_informes']['nombre_cuarta_firma']);
		$this->set('cargo_cuarta_firma',$firmantes[0]['cnmd15_firmas_informes']['cargo_cuarta_firma']);
		$this->set('nombre_quinta_firma',$firmantes[0]['cnmd15_firmas_informes']['nombre_quinta_firma']);
		$this->set('cargo_quinta_firma',$firmantes[0]['cnmd15_firmas_informes']['cargo_quinta_firma']);
		$this->set('nombre_sexta_firma',$firmantes[0]['cnmd15_firmas_informes']['nombre_sexta_firma']);
		$this->set('cargo_sexta_firma',$firmantes[0]['cnmd15_firmas_informes']['cargo_sexta_firma']);
		$this->set('nombre_septima_firma',$firmantes[0]['cnmd15_firmas_informes']['nombre_septima_firma']);
		$this->set('cargo_septima_firma',$firmantes[0]['cnmd15_firmas_informes']['cargo_septima_firma']);
		$this->set('nombre_octava_firma',$firmantes[0]['cnmd15_firmas_informes']['nombre_octava_firma']);
		$this->set('cargo_octava_firma',$firmantes[0]['cnmd15_firmas_informes']['cargo_octava_firma']);
		$this->set('primera_copia',$firmantes[0]['cnmd15_firmas_informes']['primera_copia']);
		$this->set('segunda_copia',$firmantes[0]['cnmd15_firmas_informes']['segunda_copia']);
		$this->set('tercera_copia',$firmantes[0]['cnmd15_firmas_informes']['tercera_copia']);
		$this->set('cuarta_copia',$firmantes[0]['cnmd15_firmas_informes']['cuarta_copia']);
		$this->set('quinta_copia',$firmantes[0]['cnmd15_firmas_informes']['quinta_copia']);
		$this->set('sexta_copia',$firmantes[0]['cnmd15_firmas_informes']['sexta_copia']);
		$this->set('septima_copia',$firmantes[0]['cnmd15_firmas_informes']['septima_copia']);
		$this->set('octava_copia',$firmantes[0]['cnmd15_firmas_informes']['octava_copia']);
		$pie_pagina_doc = str_replace("\t"," ",$firmantes[0]['cnmd15_firmas_informes']['pie_pagina']);
		$pie_pagina_doc = str_replace("\n"," ",$pie_pagina_doc);
		$this->set('pie_pagina',$pie_pagina_doc);
	}else{
		$this->set('Message_existe','POR FAVOR, INGRESE LOS NOMBRES Y CARGO DE LOS FIRMANTES');
		$this->set('firma_existe','no');
		$this->set('b_readonly','');
		$this->set('tipo_documento',$num_tipo_doc);
		$this->set('nombre_primera_firma','');
		$this->set('cargo_primera_firma','');
		$this->set('nombre_segunda_firma','');
		$this->set('cargo_segunda_firma','');
		$this->set('nombre_tercera_firma','');
		$this->set('cargo_tercera_firma','');
		$this->set('nombre_cuarta_firma','');
		$this->set('cargo_cuarta_firma','');
		$this->set('nombre_quinta_firma','');
		$this->set('cargo_quinta_firma','');
		$this->set('nombre_sexta_firma','');
		$this->set('cargo_sexta_firma','');
		$this->set('nombre_septima_firma','');
		$this->set('cargo_septima_firma','');
		$this->set('nombre_octava_firma','');
		$this->set('cargo_octava_firma','');
		$this->set('primera_copia','');
		$this->set('segunda_copia','');
		$this->set('tercera_copia','');
		$this->set('cuarta_copia','');
		$this->set('quinta_copia','');
		$this->set('sexta_copia','');
		$this->set('septima_copia','');
		$this->set('octava_copia','');
		$this->set('pie_pagina','');
	}

	}else{
		$this->set('errorMessage','Disculpe, No llego el N&uacute;mero del Tipo de Documento para realizar el proceso de firmas');
	} // fin else num_tipo_doc dif. null

} // fin funcion envia_form_firmas



function guardar_editar_firmas($reporte_tipo=null){
	$this->layout="ajax";

	$cp  = $this->Session->read('SScodpresi');
	$ce  = $this->Session->read('SScodentidad');
	$cti = $this->Session->read('SScodtipoinst');
	$ci  = $this->Session->read('SScodinst');
	$cd  = $this->Session->read('SScoddep');

	$tipo_doc = $this->data['cnmd15_firmas_informes']['tipo_documento'];
	$nombre_primera_firma = $this->data['cnmd15_firmas_informes']['nombre_primera_firma'];
	$cargo_primera_firma  = $this->data['cnmd15_firmas_informes']['cargo_primera_firma'];
	$nombre_segunda_firma = $this->data['cnmd15_firmas_informes']['nombre_segunda_firma'];
	$cargo_segunda_firma  = $this->data['cnmd15_firmas_informes']['cargo_segunda_firma'];
	$nombre_tercera_firma = $this->data['cnmd15_firmas_informes']['nombre_tercera_firma'];
	$cargo_tercera_firma  = $this->data['cnmd15_firmas_informes']['cargo_tercera_firma'];
	$nombre_cuarta_firma = $this->data['cnmd15_firmas_informes']['nombre_cuarta_firma'];
	$cargo_cuarta_firma  = $this->data['cnmd15_firmas_informes']['cargo_cuarta_firma'];
	$nombre_quinta_firma = $this->data['cnmd15_firmas_informes']['nombre_quinta_firma'];
	$cargo_quinta_firma  = $this->data['cnmd15_firmas_informes']['cargo_quinta_firma'];
	$nombre_sexta_firma = $this->data['cnmd15_firmas_informes']['nombre_sexta_firma'];
	$cargo_sexta_firma  = $this->data['cnmd15_firmas_informes']['cargo_sexta_firma'];
	$nombre_septima_firma = $this->data['cnmd15_firmas_informes']['nombre_septima_firma'];
	$cargo_septima_firma  = $this->data['cnmd15_firmas_informes']['cargo_septima_firma'];
	$nombre_octava_firma = $this->data['cnmd15_firmas_informes']['nombre_octava_firma'];
	$cargo_octava_firma  = $this->data['cnmd15_firmas_informes']['cargo_octava_firma'];

	$primera_cc = isset($this->data['cnmd15_firmas_informes']['primera_copia'])? $this->data['cnmd15_firmas_informes']['primera_copia'] : '';
	$segunda_cc = isset($this->data['cnmd15_firmas_informes']['segunda_copia'])? $this->data['cnmd15_firmas_informes']['segunda_copia'] : '';
	$tercera_cc = isset($this->data['cnmd15_firmas_informes']['tercera_copia'])? $this->data['cnmd15_firmas_informes']['tercera_copia'] : '';
	$cuarta_cc  = isset($this->data['cnmd15_firmas_informes']['cuarta_copia'])? $this->data['cnmd15_firmas_informes']['cuarta_copia'] : '';
	$quinta_cc  = isset($this->data['cnmd15_firmas_informes']['quinta_copia'])? $this->data['cnmd15_firmas_informes']['quinta_copia'] : '';
	$sexta_cc   = isset($this->data['cnmd15_firmas_informes']['sexta_copia'])? $this->data['cnmd15_firmas_informes']['sexta_copia'] : '';
	$septima_cc = isset($this->data['cnmd15_firmas_informes']['septima_copia'])? $this->data['cnmd15_firmas_informes']['septima_copia'] : '';
	$octava_cc  = isset($this->data['cnmd15_firmas_informes']['octava_copia'])? $this->data['cnmd15_firmas_informes']['octava_copia'] : '';

	$pie_pagina = $this->data['cnmd15_firmas_informes']['pie_pagina'];
	$pie_pagina = str_replace("\t"," ",$pie_pagina);
	$pie_pagina = str_replace("\n"," ",$pie_pagina);

	$enc_td_firma = $this->cnmd15_firmas_informes->findCount($this->SQLCA()." and tipo_documento=$tipo_doc");

	if($enc_td_firma==0){
		$muestr_accion = 'Registradas';
		$sql_ejecutar = "INSERT INTO cnmd15_firmas_informes VALUES ($cp, $ce, $cti, $ci, $cd, $tipo_doc,'$nombre_primera_firma', '$cargo_primera_firma', '$nombre_segunda_firma', '$cargo_segunda_firma', '$nombre_tercera_firma', '$cargo_tercera_firma', '$nombre_cuarta_firma', '$cargo_cuarta_firma', '$nombre_quinta_firma', '$cargo_quinta_firma', '$nombre_sexta_firma', '$cargo_sexta_firma', '$nombre_septima_firma', '$cargo_septima_firma', '$nombre_octava_firma', '$cargo_octava_firma', '$primera_cc', '$segunda_cc', '$tercera_cc', '$cuarta_cc', '$quinta_cc', '$sexta_cc', '$septima_cc', '$octava_cc', '$pie_pagina');";
	}else{
		$muestr_accion = 'Modificadas';
		$sql_ejecutar = "UPDATE cnmd15_firmas_informes SET nombre_primera_firma='$nombre_primera_firma', cargo_primera_firma='$cargo_primera_firma', nombre_segunda_firma='$nombre_segunda_firma', cargo_segunda_firma='$cargo_segunda_firma', nombre_tercera_firma='$nombre_tercera_firma', cargo_tercera_firma='$cargo_tercera_firma', nombre_cuarta_firma='$nombre_cuarta_firma', cargo_cuarta_firma='$cargo_cuarta_firma', nombre_quinta_firma='$nombre_quinta_firma', cargo_quinta_firma='$cargo_quinta_firma', nombre_sexta_firma='$nombre_sexta_firma', cargo_sexta_firma='$cargo_sexta_firma', nombre_septima_firma='$nombre_septima_firma', cargo_septima_firma='$cargo_septima_firma', nombre_octava_firma='$nombre_octava_firma', cargo_octava_firma='$cargo_octava_firma', primera_copia='$primera_cc', segunda_copia='$segunda_cc', tercera_copia='$tercera_cc', cuarta_copia='$cuarta_cc', quinta_copia='$quinta_cc', sexta_copia='$sexta_cc', septima_copia='$septima_cc', octava_copia='$octava_cc', pie_pagina='$pie_pagina' WHERE ".$this->SQLCA()." and tipo_documento=".$tipo_doc;
	}

	$swi = $this->cnmd15_firmas_informes->execute($sql_ejecutar);

	if($swi>1){
		$this->set('Message_existe','Las firmas fuer&oacute;n '.$muestr_accion.' correctamente');
	}else{
		$this->set('errorMessage','Las firmas no fuer&oacute;n '.$muestr_accion.'');
	}

	if($reporte_tipo==1 || $reporte_tipo==2){
		$this->index($reporte_tipo);
		$this->render('index');
	}else if($reporte_tipo==3 || $reporte_tipo==4){
		$this->index_resumido($reporte_tipo);
		$this->render('index_resumido');
	}else if($reporte_tipo==5 || $reporte_tipo=='5'){
		$this->index_constancia_prestac_soc($reporte_tipo);
		$this->render('index_constancia_prestac_soc');
	}else if($reporte_tipo==6 || $reporte_tipo=='6'){
		$this->index_antecedente_servicio($reporte_tipo);
		$this->render('index_antecedente_servicio');
	}

} // fin funcion guardar_editar_firmas


function modificar_firmas_form($reportem_tipo=null){
	$this->layout="ajax";
	$this->set('reportem_tipo',$reportem_tipo);
	$this->set('Message_existe','Puede modificar los nombres y cargos de los firmantes');
}


function envio_calculo_prestacion($tipo_peticion=null) {
	$this->layout="ajax";
	set_time_limit(0);

         if($tipo_peticion==1){ $condi_b = " "; }
         else if($tipo_peticion==2){ $condi_b = " and prestacion_cancelada=1 "; }
         else if($tipo_peticion==3){ $condi_b = " and prestacion_cancelada=2 "; }
	$codigo_nom =	$this->Session->read('codi_tipo_nomina');
	$lista_datos = $this->cnmd15_datos_personales->execute("SELECT cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, primer_apellido, segundo_apellido, primer_nombre, segundo_nombre, fecha_ingreso, fecha_egreso, dia, mes, ano FROM v_cnmd15_datos_personales_prestaciones WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$codigo_nom.$condi_b." ORDER BY cedula_identidad ASC;");
	if($lista_datos!=null){
		$datos_imprimir = array();
		$this->set('cerrar_capa_proceso', true);
	foreach($lista_datos as $datos_calcular){
		$dato_calculo = $this->calculo_prestaciones($datos_calcular[0]['cod_tipo_nomina'], $datos_calcular[0]['cod_cargo'], $datos_calcular[0]['cod_ficha'], $datos_calcular[0]['cedula_identidad']);

		$dato_persona['cedula_identidad'] = $datos_calcular[0]['cedula_identidad'];
		$dato_persona['nombres_apellidos'] = $datos_calcular[0]['primer_apellido']." ".$datos_calcular[0]['segundo_apellido']." ".$datos_calcular[0]['primer_nombre']." ".$datos_calcular[0]['segundo_nombre'];
		$dato_persona['fecha_ingreso'] = $datos_calcular[0]['fecha_ingreso'];
		$dato_persona['fecha_egreso'] = $datos_calcular[0]['fecha_egreso'];
		$dato_persona['dia'] = $datos_calcular[0]['dia'];
		$dato_persona['mes'] = $datos_calcular[0]['mes'];
		$dato_persona['ano'] = $datos_calcular[0]['ano'];
		$dato_persona['interes'] = $dato_calculo[0];
		$dato_persona['totales'] = $dato_calculo[1];
		$datos_imprimir[] = array('datos_persona' => $dato_persona);

	}

	 $_SESSION['datos_imp'] = $datos_imprimir;
	 echo '<script>Control.Modal.close(true);</script>';
			echo "<script>
				document.getElementById('tipo_proceso_1').disabled=false;
  				document.getElementById('tipo_proceso_2').disabled=false;
  				document.getElementById('tipo_proceso_3').disabled=false;
  				document.getElementById('tipo_proceso_$tipo_peticion').checked=true;
  				document.getElementById('id_enviar_generar_rp').disabled=false;
			</script>";
	}else{
		$_SESSION['datos_imp'] = array();
		echo '<script>Control.Modal.close(true);</script>';
			echo "<script> fun_msj('NO SE ENCONTRAR&Oacute;N DATOS PARA PROCESAR');
				document.getElementById('tipo_proceso_1').disabled=false;
  				document.getElementById('tipo_proceso_2').disabled=false;
  				document.getElementById('tipo_proceso_3').disabled=false;
				document.getElementById('tipo_proceso_$tipo_peticion').checked=true;
  				document.getElementById('id_enviar_generar_rp').disabled=true;
			</script>";
	}
	unset($datos_imprimir);
}


function salir_busqueda($var_salida=null) {
	$this->layout="ajax";
	if($var_salida=='si'){
		echo "<script>
				document.getElementById('procesar').disabled=true;
  			</script>";
	}
}


function tipo_proceso_envio($vari=null) {
	// vari = 1: Todos.   2: Uno en Particular.
	$this->layout="ajax";
	if($vari!=null){
		switch($vari){
			case 1: $tipo_ventana_xenvio = '';
				break;
			case 2: $tipo_ventana_xenvio = 'buscar_datos_personales';
				break;
			case 3: $tipo_ventana_xenvio = 'buscar_datos_personales_constancia';
				break;
			case 4: $tipo_ventana_xenvio = 'buscar_datos_antecedente_servicio';
				break;
			default: $tipo_ventana_xenvio = '';
				break;
		}

		if($vari==1){
			echo '<script>document.getElementById("empleado_ide").style.visibility="hidden";</script>';
			echo "<script> document.getElementById('empleado_ide').innerHTML='';
							if(document.getElementById('id_cedula_identidad')){ document.getElementById('id_cedula_identidad').value=''; }
						   	if(document.getElementById('id_nombre_empleado')){ document.getElementById('id_nombre_empleado').value=''; }
				</script>";
			// llamado a la funcion procesar.
		}else{
				$url                  =  "/cnmp99_prestaciones_sociales/$tipo_ventana_xenvio/$vari";
				$width_aux            =  "750px";
				$height_aux           =  "450px";
				$title_aux            =  "Buscar";
				$resizable_aux        =  false;
				$maximizable_aux      =  false;
				$minimizable_aux      =  false;
				$closable_aux         =  false;

			 echo "<script>";
	           echo "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
	         echo "</script>";
		}
	}else{
		$this->set('errorMessage','NO LLEG&Oacute; INFORMACI&Oacute;N COMPLETA PARA PROCESAR');
	}
} // fin funcion


function buscar_datos_personales($var1=null, $cod=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
	echo "<script>$('select_obra_cod_obra').focus();</script>";
}//fin function


function buscar_datos_porpista($var1=null, $var2=null, $var3=null){
	$this->layout="ajax";
	$modelo='cnmd15_datos_personales';
	$cod_nomina = $this->Session->read('codigo_tipo_nomina');
    if($var3==null){ $var2 = strtoupper($var2);
					 $this->Session->write('pista', $var2);
					 $Tfilas=$this->$modelo->findCount($this->SQLCA()." and cod_tipo_nomina=$cod_nomina and prestacion_cancelada=2 and ((cedula_identidad::text LIKE '%$var2%') or (quitar_acentos(primer_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(primer_apellido) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_apellido) LIKE quitar_acentos('%$var2%')))");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
								$datos_filas=$this->$modelo->findAll($this->SQLCA()." and cod_tipo_nomina=$cod_nomina and prestacion_cancelada=2 and ((cedula_identidad::text LIKE '%$var2%') or (quitar_acentos(primer_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(primer_apellido) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_apellido) LIKE quitar_acentos('%$var2%')))",null,"cedula_identidad ASC",50,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					          	echo '<script>document.getElementById("empleado_ide").style.visibility="hidden";</script>';
					          	echo "<script> if(document.getElementById('id_cedula_identidad')){ document.getElementById('id_cedula_identidad').value=''; }
  										if(document.getElementById('id_nombre_empleado')){ document.getElementById('id_nombre_empleado').value=''; }  </script>";
					        	$this->set("datosFILAS",'');
								$this->set('total_paginas','');
								$this->set('pagina_actual','');
							    $this->set('siguiente','');
								$this->set('anterior','');
								$this->set('ultimo','');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);
						$Tfilas=$this->$modelo->findCount($this->SQLCA()." and cod_tipo_nomina=$cod_nomina and prestacion_cancelada=2 and ((cedula_identidad::text LIKE '%$var2%') or (quitar_acentos(primer_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(primer_apellido) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_apellido) LIKE quitar_acentos('%$var2%')))");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
									$datos_filas=$this->$modelo->findAll($this->SQLCA()." and cod_tipo_nomina=$cod_nomina and prestacion_cancelada=2 and ((cedula_identidad::text LIKE '%$var2%') or (quitar_acentos(primer_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(primer_apellido) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_apellido) LIKE quitar_acentos('%$var2%')))",null,"cedula_identidad ASC",50,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						          	echo '<script>document.getElementById("empleado_ide").style.visibility="hidden";</script>';
					          		echo "<script> if(document.getElementById('id_cedula_identidad')){ document.getElementById('id_cedula_identidad').value=''; }
  										if(document.getElementById('id_nombre_empleado')){ document.getElementById('id_nombre_empleado').value=''; }  </script>";
						        	$this->set("datosFILAS",'');
									$this->set('total_paginas','');
									$this->set('pagina_actual','');
							    	$this->set('siguiente','');
									$this->set('anterior','');
									$this->set('ultimo','');
						          }
   		}//fin else
$this->set("opcion",$var1);
$this->set("cod_nomi",$cod_nomina);
} //fin funcion


function buscar_datos_personales_constancia($var1=null, $cod=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
	echo "<script>$('select_obra_cod_obra').focus();</script>";
}//fin function


function buscar_datos_porpista_constancia($var1=null, $var2=null, $var3=null){
	$this->layout="ajax";
	$modelo='cnmd15_datos_personales';
	$cod_nomina = $this->Session->read('cons_codigo_tipo_nomina');
    if($var3==null){ $var2 = strtoupper($var2);
					 $this->Session->write('pista', $var2);
					 $Tfilas=$this->$modelo->findCount($this->SQLCA()." and cod_tipo_nomina=$cod_nomina and prestacion_cancelada=2 and ((cedula_identidad::text LIKE '%$var2%') or (quitar_acentos(primer_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(primer_apellido) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_apellido) LIKE quitar_acentos('%$var2%')))");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
								$datos_filas=$this->$modelo->findAll($this->SQLCA()." and cod_tipo_nomina=$cod_nomina and prestacion_cancelada=2 and ((cedula_identidad::text LIKE '%$var2%') or (quitar_acentos(primer_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(primer_apellido) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_apellido) LIKE quitar_acentos('%$var2%')))",null,"cedula_identidad ASC",50,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					          	echo '<script>document.getElementById("empleado_ide").style.visibility="hidden";</script>';
					          	echo "<script> if(document.getElementById('id_cedula_identidad')){ document.getElementById('id_cedula_identidad').value=''; }
  										if(document.getElementById('id_nombre_empleado')){ document.getElementById('id_nombre_empleado').value=''; }  </script>";
					        	$this->set("datosFILAS",'');
								$this->set('total_paginas','');
								$this->set('pagina_actual','');
							    $this->set('siguiente','');
								$this->set('anterior','');
								$this->set('ultimo','');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);
						$Tfilas=$this->$modelo->findCount($this->SQLCA()." and cod_tipo_nomina=$cod_nomina and prestacion_cancelada=2 and ((cedula_identidad::text LIKE '%$var2%') or (quitar_acentos(primer_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(primer_apellido) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_apellido) LIKE quitar_acentos('%$var2%')))");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
									$datos_filas=$this->$modelo->findAll($this->SQLCA()." and cod_tipo_nomina=$cod_nomina and prestacion_cancelada=2 and ((cedula_identidad::text LIKE '%$var2%') or (quitar_acentos(primer_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(primer_apellido) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_apellido) LIKE quitar_acentos('%$var2%')))",null,"cedula_identidad ASC",50,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						          	echo '<script>document.getElementById("empleado_ide").style.visibility="hidden";</script>';
					          		echo "<script> if(document.getElementById('id_cedula_identidad')){ document.getElementById('id_cedula_identidad').value=''; }
  										if(document.getElementById('id_nombre_empleado')){ document.getElementById('id_nombre_empleado').value=''; }  </script>";
						        	$this->set("datosFILAS",'');
									$this->set('total_paginas','');
									$this->set('pagina_actual','');
							    	$this->set('siguiente','');
									$this->set('anterior','');
									$this->set('ultimo','');
						          }
   		}//fin else
$this->set("opcion",$var1);
$this->set("cod_nomi",$cod_nomina);
} //fin funcion


function buscar_datos_antecedente_servicio($var1=null, $cod=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
	echo "<script>$('select_obra_cod_obra').focus();</script>";
}//fin function


function buscar_datos_porpista_antecedente($var1=null, $var2=null, $var3=null){
	$this->layout="ajax";
	$modelo='cnmd15_datos_personales';
	$cod_nomina = $this->Session->read('cons_codigo_tipo_nomina');
    if($var3==null){ $var2 = strtoupper($var2);
					 $this->Session->write('pista', $var2);
					 $Tfilas=$this->$modelo->findCount($this->SQLCA()." and cod_tipo_nomina=$cod_nomina and prestacion_cancelada=2 and ((cedula_identidad::text LIKE '%$var2%') or (quitar_acentos(primer_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(primer_apellido) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_apellido) LIKE quitar_acentos('%$var2%')))");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
								$datos_filas=$this->$modelo->findAll($this->SQLCA()." and cod_tipo_nomina=$cod_nomina and prestacion_cancelada=2 and ((cedula_identidad::text LIKE '%$var2%') or (quitar_acentos(primer_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(primer_apellido) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_apellido) LIKE quitar_acentos('%$var2%')))",null,"cedula_identidad ASC",50,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					          	echo '<script>document.getElementById("empleado_ide").style.visibility="hidden";</script>';
					          	echo "<script> if(document.getElementById('id_cedula_identidad')){ document.getElementById('id_cedula_identidad').value=''; }
  										if(document.getElementById('id_nombre_empleado')){ document.getElementById('id_nombre_empleado').value=''; }  </script>";
					        	$this->set("datosFILAS",'');
								$this->set('total_paginas','');
								$this->set('pagina_actual','');
							    $this->set('siguiente','');
								$this->set('anterior','');
								$this->set('ultimo','');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);
						$Tfilas=$this->$modelo->findCount($this->SQLCA()." and cod_tipo_nomina=$cod_nomina and prestacion_cancelada=2 and ((cedula_identidad::text LIKE '%$var2%') or (quitar_acentos(primer_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(primer_apellido) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_apellido) LIKE quitar_acentos('%$var2%')))");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
									$datos_filas=$this->$modelo->findAll($this->SQLCA()." and cod_tipo_nomina=$cod_nomina and prestacion_cancelada=2 and ((cedula_identidad::text LIKE '%$var2%') or (quitar_acentos(primer_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_nombre) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(primer_apellido) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(segundo_apellido) LIKE quitar_acentos('%$var2%')))",null,"cedula_identidad ASC",50,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						          	echo '<script>document.getElementById("empleado_ide").style.visibility="hidden";</script>';
					          		echo "<script> if(document.getElementById('id_cedula_identidad')){ document.getElementById('id_cedula_identidad').value=''; }
  										if(document.getElementById('id_nombre_empleado')){ document.getElementById('id_nombre_empleado').value=''; }  </script>";
						        	$this->set("datosFILAS",'');
									$this->set('total_paginas','');
									$this->set('pagina_actual','');
							    	$this->set('siguiente','');
									$this->set('anterior','');
									$this->set('ultimo','');
						          }
   		}//fin else
$this->set("opcion",$var1);
$this->set("cod_nomi",$cod_nomina);
} //fin funcion


function seleccion($opci=null,$cedula=null,$codi_nomina=null,$cod_cargo=null,$cod_ficha=null) {
	$this->layout="ajax";
     if ($cedula!=null && $codi_nomina!=null) {
         $empleado = $this->cnmd15_datos_personales->find($this->SQLCA()." and cod_tipo_nomina=".$codi_nomina." and cod_cargo=".$cod_cargo." and cod_ficha=".$cod_ficha." and cedula_identidad=".$cedula,'cod_cargo, cod_ficha, cedula_identidad, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido');
		if($empleado!=null || $empleado!=""){
			$this->set('datos_empleado',$empleado);
			echo "<script> document.getElementById('id_enviar_generar').disabled=false;
				</script>";
		}else{
			$this->set('datos_empleado', array());
			echo '<script>document.getElementById("empleado_ide").style.visibility="hidden";</script>';
			echo "<script> document.getElementById('empleado_ide').innerHTML='';
							if(document.getElementById('id_cedula_identidad')){ document.getElementById('id_cedula_identidad').value=''; }
						   	if(document.getElementById('id_nombre_empleado')){ document.getElementById('id_nombre_empleado').value=''; }
				</script>";
		}
	}
}


function seleccion_trabajador($opci=null,$cedula=null,$codi_nomina=null,$cod_cargo=null,$cod_ficha=null) {
	$this->layout="ajax";
     if ($cedula!=null && $codi_nomina!=null) {
         $empleado = $this->cnmd15_datos_personales->find($this->SQLCA()." and cod_tipo_nomina=".$codi_nomina." and cod_cargo=".$cod_cargo." and cod_ficha=".$cod_ficha." and cedula_identidad=".$cedula,'cod_cargo, cod_ficha, cedula_identidad, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido');
		if($empleado!=null || $empleado!=""){
			$this->set('datos_empleado',$empleado);
			echo "<script> document.getElementById('submit_pdf').disabled=false;
				</script>";
		}else{
			$this->set('datos_empleado', array());
			echo '<script>document.getElementById("empleado_ide").style.visibility="hidden";</script>';
			echo "<script> document.getElementById('empleado_ide').innerHTML='';
							if(document.getElementById('id_cedula_identidad')){ document.getElementById('id_cedula_identidad').value=''; }
						   	if(document.getElementById('id_nombre_empleado')){ document.getElementById('id_nombre_empleado').value=''; }
				</script>";
		}
	}
}


function seleccion_trabajador2($opci=null,$cedula=null,$codi_nomina=null,$cod_cargo=null,$cod_ficha=null) {
	$this->layout="ajax";
     if ($cedula!=null && $codi_nomina!=null) {
         $empleado = $this->cnmd15_datos_personales->find($this->SQLCA()." and cod_tipo_nomina=".$codi_nomina." and cod_cargo=".$cod_cargo." and cod_ficha=".$cod_ficha." and cedula_identidad=".$cedula,'cod_cargo, cod_ficha, cedula_identidad, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido');
		if($empleado!=null || $empleado!=""){
			$this->set('datos_empleado',$empleado);
			echo "<script> document.getElementById('submit_pdf').disabled=false;
				</script>";
		}else{
			$this->set('datos_empleado', array());
			echo '<script>document.getElementById("empleado_ide").style.visibility="hidden";</script>';
			echo "<script> document.getElementById('empleado_ide').innerHTML='';
							if(document.getElementById('id_cedula_identidad')){ document.getElementById('id_cedula_identidad').value=''; }
						   	if(document.getElementById('id_nombre_empleado')){ document.getElementById('id_nombre_empleado').value=''; }
				</script>";
		}
	}
}


function detalles_prestaciones(){
	$this->layout = "pdf";
	$estado = $this->cugd01_estados->execute("SELECT denominacion FROM cugd01_estados WHERE cod_republica='".$this->verifica_SS(1)."' and cod_estado='".$this->verifica_SS(2)."';");
	$_SESSION['estado'] = $estado[0][0]['denominacion'];
	$institucion = $this->cugd01_estados->execute("SELECT denominacion FROM cugd02_institucion WHERE cod_tipo_institucion='".$this->verifica_SS(3)."' and cod_institucion='".$this->verifica_SS(4)."';");
	$_SESSION['institucion'] = $institucion[0][0]['denominacion'];
	$dependencia = $this->cugd01_estados->execute("SELECT denominacion FROM cugd02_dependencias WHERE cod_tipo_institucion='".$this->verifica_SS(3)."' and cod_institucion='".$this->verifica_SS(4)."' and cod_dependencia='".$this->verifica_SS(5)."';");
	$_SESSION['dependencia'] = $dependencia[0][0]['denominacion'];
		 $datos=$this->data["cnmp99_prestaciones"];
         $cod_tipo_nomina  = $datos["cod_tipo_nomina"];
         $denominacion  = $datos["denominacion_tipo_nomina"];
       	 $codigo_cargo  = $datos["cod_cargo"];
         $codigo_ficha  = $datos["cod_ficha"];
         $ced_identidad = $datos["cedula_identidad"];
         $datos_personales = $this->cnmd15_datos_personales->execute("SELECT cedula_identidad, primer_apellido, segundo_apellido, primer_nombre, segundo_nombre, denominacion_cargo, institucion, dependencia, fecha_ingreso, fecha_egreso, motivo_retiro, observacion_ley_anterior, observacion_lit_ayb, observacion_lit_c FROM cnmd15_datos_personales WHERE ". $this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=".$codigo_cargo." and cod_ficha=".$codigo_ficha." and cedula_identidad=".$ced_identidad." LIMIT 1");
		 $fecha_egreso = $datos_personales[0][0]['fecha_egreso'];
	$dcd=$this->Cnmd01->execute("SELECT sueldo_integral FROM cnmd15_devengado WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=$codigo_cargo and cod_ficha=$codigo_ficha and cedula_identidad=$ced_identidad and fecha_hasta>='$fecha_egreso'");
	if($dcd!=null){
		foreach($dcd as $datosc){
			$sueldo_integral_actual = $this->redondeo(($datosc[0]['sueldo_integral']));
		}
	}else{
		$sueldo_integral_actual = 0;
	}
   	$fecha_egreso_transf = '1997-06-18';
	$ded=$this->Cnmd01->execute("SELECT sueldo_integral FROM cnmd15_devengado WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=$codigo_cargo and cod_ficha=$codigo_ficha and cedula_identidad=$ced_identidad and (fecha_desde<='$fecha_egreso_transf' and fecha_hasta>='$fecha_egreso_transf')");
	if($ded!=null){
		foreach($ded as $datosded){
			$sueldo_integral_anterior = $this->redondeo(($datosded[0]['sueldo_integral']));
		}
	}else{
		$sueldo_integral_anterior = 0;
	}

	$dias_antig = $this->Cnmd01->execute("SELECT * FROM v_cnmd15_datos_personales_prestaciones WHERE ".$this->SQLCA()." and cod_tipo_nomina=$cod_tipo_nomina and cod_cargo=$codigo_cargo and cod_ficha=$codigo_ficha and cedula_identidad=$ced_identidad LIMIT 1;");
	if($dias_antig!=null){
		$ano = $dias_antig[0][0]['ano'];
		$mes = $dias_antig[0][0]['mes'];
		$dia = $dias_antig[0][0]['dia'];
	}else{
		$ano = 0;
		$mes = 0;
		$dia = 0;
	}

	$datos_prestaciones = $this->cnmd15_datos_prestaciones->execute("SELECT concepto, dias, salario_diario, monto FROM cnmd15_datos_prestaciones WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=$codigo_cargo and cod_ficha=$codigo_ficha and cedula_identidad=$ced_identidad");

		$this->set('datos_personales',$datos_personales);
		$this->set('denominacion',$denominacion);
		$this->set('sueldo_integral_actual',$sueldo_integral_actual);
		$this->set('sueldo_integral_anterior',$sueldo_integral_anterior);
		$this->set('ano',$ano);
		$this->set('mes',$mes);
		$this->set('dia',$dia);
		$this->set('datos_prestaciones',$datos_prestaciones);

	$d_firmantes = $this->cnmd15_firmas_informes->findAll($this->SQLCA()." and tipo_documento=23101");
	$this->set('datos_firmantes', $d_firmantes);

}


function detalles_devengado(){
	$this->layout = "pdf";

		 $datos=$this->data["cnmp99_prestaciones"];
         $cod_tipo_nomina  = $datos["cod_tipo_nomina"];
         $denominacion  = $datos["denominacion_tipo_nomina"];

         $codigo_cargo  = $datos["cod_cargo"];
         $codigo_ficha  = $datos["cod_ficha"];
         $ced_identidad = $datos["cedula_identidad"];

         $datos_personales = $this->cnmd15_datos_personales->execute("SELECT cedula_identidad, primer_apellido, segundo_apellido, primer_nombre, segundo_nombre, denominacion_cargo, institucion, dependencia, fecha_ingreso, fecha_egreso, motivo_retiro FROM cnmd15_datos_personales WHERE ". $this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=".$codigo_cargo." and cod_ficha=".$codigo_ficha." and cedula_identidad=".$ced_identidad." LIMIT 1");

         $datos_devengado = $this->cnmd15_devengado->findall($this->SQLCA()." and cedula_identidad=".$ced_identidad." AND cod_tipo_nomina=".$cod_tipo_nomina,null,"escala ASC");

		$this->set('datos_personales',$datos_personales);
		$this->set('denominacion',$denominacion);
/*
		$this->set('sueldo_integral_actual',$sueldo_integral_actual);
		$this->set('sueldo_integral_anterior',$sueldo_integral_anterior);
		$this->set('ano',$ano);
		$this->set('mes',$mes);
		$this->set('dia',$dia);
*/
		$this->set('datos_devengado',$datos_devengado);

		$this->set('cod_nomina',$cod_tipo_nomina);
		$this->set('deno_nomina',$denominacion);
		$this->set('cod_cargo',$codigo_cargo);
		$this->set('cod_ficha',$codigo_ficha);
		$this->set('cedula',$ced_identidad);

		$_SESSION['cod_nomina']=$cod_tipo_nomina;
		$_SESSION['deno_nomina']=$denominacion;
		$_SESSION['cod_cargo']=$codigo_cargo;
		$_SESSION['cod_ficha']=$codigo_ficha;
		$_SESSION['cedula']=$ced_identidad;

		$_SESSION['primer_nombre']=$datos_personales[0][0]['primer_nombre'];
		$_SESSION['segundo_nombre']=$datos_personales[0][0]['segundo_nombre'];
		$_SESSION['primer_apellido']=$datos_personales[0][0]['primer_apellido'];
		$_SESSION['segundo_apellido']=$datos_personales[0][0]['segundo_apellido'];

		$_SESSION['cabeza']=true;


}// fin detalles_devengado


function relacion_devengado () {
    $this->layout="ajax";
    $this->Session->delete('codigo_tipo_nomina');
    $this->Session->delete('cod_nomina');
    $this->Session->delete('deno_nomina');
    $this->Session->delete('cod_cargo');
    $this->Session->delete('cod_ficha');
    $this->Session->delete('cedula');
    $this->Session->delete('primer_nombre');
    $this->Session->delete('segundo_nombre');
    $this->Session->delete('primer_apellido');
    $this->Session->delete('segundo_apellido');
    $this->Session->delete('primer_nombre');
    $this->Session->delete('cabeza');


    $lista = $this->Cnmd01->generateList($this->SQLCA()."", 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');

    if($this->Cnmd01->findCount($this->SQLCA())!=0){
		$this->concatenaN($lista, 'tipo_nomina');
	}else{
		$this->set('tipo_nomina', array());
	}

}//fin relacion_devengado


function intereses_detallados($option=null){
	$this->layout = "pdf";
	$_SESSION['lista_camposrp'] = 1;
	$estado = $this->cugd01_estados->execute("SELECT denominacion FROM cugd01_estados WHERE cod_republica='".$this->verifica_SS(1)."' and cod_estado='".$this->verifica_SS(2)."';");
	$_SESSION['estado'] = $estado[0][0]['denominacion'];
	$institucion = $this->cugd01_estados->execute("SELECT denominacion FROM cugd02_institucion WHERE cod_tipo_institucion='".$this->verifica_SS(3)."' and cod_institucion='".$this->verifica_SS(4)."';");
	$_SESSION['institucion'] = $institucion[0][0]['denominacion'];
	$dependencia = $this->cugd01_estados->execute("SELECT denominacion FROM cugd02_dependencias WHERE cod_tipo_institucion='".$this->verifica_SS(3)."' and cod_institucion='".$this->verifica_SS(4)."' and cod_dependencia='".$this->verifica_SS(5)."';");
	$_SESSION['dependencia'] = $dependencia[0][0]['denominacion'];

		 $datos=$this->data["cnmp99_prestaciones"];
         $cod_tipo_nomina  = $datos["cod_tipo_nomina"];
         $denominacion  = $datos["denominacion_tipo_nomina"];
       	 $codigo_cargo  = $datos["cod_cargo"];
         $codigo_ficha  = $datos["cod_ficha"];
         $ced_identidad = $datos["cedula_identidad"];

         $datos_personales = $this->cnmd15_datos_personales->execute("SELECT cedula_identidad, primer_apellido, segundo_apellido, primer_nombre, segundo_nombre, denominacion_cargo, institucion, dependencia, fecha_ingreso, fecha_egreso, motivo_retiro FROM cnmd15_datos_personales WHERE ". $this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=".$codigo_cargo." and cod_ficha=".$codigo_ficha." and cedula_identidad=".$ced_identidad." LIMIT 1");
		 $fecha_egreso = $datos_personales[0][0]['fecha_egreso'];

	$dcd=$this->Cnmd01->execute("SELECT sueldo_integral FROM cnmd15_devengado WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=$codigo_cargo and cod_ficha=$codigo_ficha and cedula_identidad=$ced_identidad and fecha_hasta>='$fecha_egreso'");
	if($dcd!=null){
		foreach($dcd as $datosc){
			$sueldo_integral_actual = $this->redondeo(($datosc[0]['sueldo_integral']));
		}
	}else{
		$sueldo_integral_actual = 0;
	}
   	$fecha_egreso_transf = '1997-06-18';
	$ded=$this->Cnmd01->execute("SELECT sueldo_integral FROM cnmd15_devengado WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=$codigo_cargo and cod_ficha=$codigo_ficha and cedula_identidad=$ced_identidad and (fecha_desde<='$fecha_egreso_transf' and fecha_hasta>='$fecha_egreso_transf')");
	if($ded!=null){
		foreach($ded as $datosded){
			$sueldo_integral_anterior = $this->redondeo(($datosded[0]['sueldo_integral']));
		}
	}else{
		$sueldo_integral_anterior = 0;
	}

	$dias_antig = $this->Cnmd01->execute("SELECT * FROM v_cnmd15_datos_personales_prestaciones WHERE ".$this->SQLCA()." and cod_tipo_nomina=$cod_tipo_nomina and cod_cargo=$codigo_cargo and cod_ficha=$codigo_ficha and cedula_identidad=$ced_identidad LIMIT 1;");
	if($dias_antig!=null){
		$ano = $dias_antig[0][0]['ano'];
		$mes = $dias_antig[0][0]['mes'];
		$dia = $dias_antig[0][0]['dia'];


	}else{
		$ano = 0;
		$mes = 0;
		$dia = 0;
	}

	$datos_intereses = $this->cnmd15_datos_intereses->execute("SELECT desde, hasta, salario_mensual, salario_diario, dias_antiguedad, monto_antiguedad, prestaciones_acumuladas, anticipo, capital, tasa, interes, intereses_acumulados FROM cnmd15_datos_intereses WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=$codigo_cargo and cod_ficha=$codigo_ficha and cedula_identidad=$ced_identidad ORDER BY consecutivo ASC");

		$this->set('datos_personales',$datos_personales);
		$this->set('denominacion',$denominacion);
		$this->set('sueldo_integral_actual',$sueldo_integral_actual);
		$this->set('sueldo_integral_anterior',$sueldo_integral_anterior);
		$this->set('ano',$ano);
		$this->set('mes',$mes);
		$this->set('dia',$dia);
		$this->set('datos_intereses',$datos_intereses);

	$d_firmantes = $this->cnmd15_firmas_informes->findAll($this->SQLCA()." and tipo_documento=23102");
	$this->set('datos_firmantes', $d_firmantes);
	$this->set('option_f', $option);

}


function intereses_mora(){
	$this->layout = "pdf";
	$_SESSION['lista_camposrp'] = 1;
	$estado = $this->cugd01_estados->execute("SELECT denominacion FROM cugd01_estados WHERE cod_republica='".$this->verifica_SS(1)."' and cod_estado='".$this->verifica_SS(2)."';");
	$_SESSION['estado'] = $estado[0][0]['denominacion'];
	$institucion = $this->cugd01_estados->execute("SELECT denominacion FROM cugd02_institucion WHERE cod_tipo_institucion='".$this->verifica_SS(3)."' and cod_institucion='".$this->verifica_SS(4)."';");
	$_SESSION['institucion'] = $institucion[0][0]['denominacion'];
	$dependencia = $this->cugd01_estados->execute("SELECT denominacion FROM cugd02_dependencias WHERE cod_tipo_institucion='".$this->verifica_SS(3)."' and cod_institucion='".$this->verifica_SS(4)."' and cod_dependencia='".$this->verifica_SS(5)."';");
	$_SESSION['dependencia'] = $dependencia[0][0]['denominacion'];

		 $datos=$this->data["cnmp99_prestaciones"];
         $cod_tipo_nomina  = $datos["cod_tipo_nomina"];
         $denominacion  = $datos["denominacion_tipo_nomina"];
       	 $codigo_cargo  = $datos["cod_cargo"];
         $codigo_ficha  = $datos["cod_ficha"];
         $ced_identidad = $datos["cedula_identidad"];

         $datos_personales = $this->cnmd15_datos_personales->execute("SELECT cedula_identidad, primer_apellido, segundo_apellido, primer_nombre, segundo_nombre, denominacion_cargo, institucion, dependencia, fecha_ingreso, fecha_egreso, motivo_retiro FROM cnmd15_datos_personales WHERE ". $this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=".$codigo_cargo." and cod_ficha=".$codigo_ficha." and cedula_identidad=".$ced_identidad." LIMIT 1");
		 $fecha_egreso = $datos_personales[0][0]['fecha_egreso'];
	$dcd=$this->Cnmd01->execute("SELECT sueldo_integral FROM cnmd15_devengado WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=$codigo_cargo and cod_ficha=$codigo_ficha and cedula_identidad=$ced_identidad and fecha_hasta>='$fecha_egreso'");
	if($dcd!=null){
		foreach($dcd as $datosc){
			$sueldo_integral_actual = $this->redondeo(($datosc[0]['sueldo_integral']));
		}
	}else{
		$sueldo_integral_actual = 0;
	}
   	$fecha_egreso_transf = '1997-06-18';
	$ded=$this->Cnmd01->execute("SELECT sueldo_integral FROM cnmd15_devengado WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=$codigo_cargo and cod_ficha=$codigo_ficha and cedula_identidad=$ced_identidad and (fecha_desde<='$fecha_egreso_transf' and fecha_hasta>='$fecha_egreso_transf')");
	if($ded!=null){
		foreach($ded as $datosded){
			$sueldo_integral_anterior = $this->redondeo(($datosded[0]['sueldo_integral']));
		}
	}else{
		$sueldo_integral_anterior = 0;
	}

	$dias_antig = $this->Cnmd01->execute("SELECT * FROM v_cnmd15_datos_personales_prestaciones WHERE ".$this->SQLCA()." and cod_tipo_nomina=$cod_tipo_nomina and cod_cargo=$codigo_cargo and cod_ficha=$codigo_ficha and cedula_identidad=$ced_identidad LIMIT 1;");
	if($dias_antig!=null){
		$ano = $dias_antig[0][0]['ano'];
		$mes = $dias_antig[0][0]['mes'];
		$dia = $dias_antig[0][0]['dia'];
	}else{
		$ano = 0;
		$mes = 0;
		$dia = 0;
	}

	$datos_intereses = $this->cnmd15_datos_intereses->execute("SELECT desde, hasta, dias, monto_prestaciones, tasa, monto_interes FROM cnmd15_datos_intereses_mora WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=$codigo_cargo and cod_ficha=$codigo_ficha and cedula_identidad=$ced_identidad ORDER BY consecutivo ASC");

		$this->set('datos_personales',$datos_personales);
		$this->set('denominacion',$denominacion);
		$this->set('sueldo_integral_actual',$sueldo_integral_actual);
		$this->set('sueldo_integral_anterior',$sueldo_integral_anterior);
		$this->set('ano',$ano);
		$this->set('mes',$mes);
		$this->set('dia',$dia);
		$this->set('datos_intereses',$datos_intereses);

	$d_firmantes = $this->cnmd15_firmas_informes->findAll($this->SQLCA()." and tipo_documento=23102");
	$this->set('datos_firmantes', $d_firmantes);
}


function resumen_prestaciones_interes(){
	$this->layout = "pdf";
	$_SESSION['lista_camposrp'] = 1;
	$estado = $this->cugd01_estados->execute("SELECT denominacion FROM cugd01_estados WHERE cod_republica='".$this->verifica_SS(1)."' and cod_estado='".$this->verifica_SS(2)."';");
	$_SESSION['estado'] = $estado[0][0]['denominacion'];
	$institucion = $this->cugd01_estados->execute("SELECT denominacion FROM cugd02_institucion WHERE cod_tipo_institucion='".$this->verifica_SS(3)."' and cod_institucion='".$this->verifica_SS(4)."';");
	$_SESSION['institucion'] = $institucion[0][0]['denominacion'];
	$dependencia = $this->cugd01_estados->execute("SELECT denominacion FROM cugd02_dependencias WHERE cod_tipo_institucion='".$this->verifica_SS(3)."' and cod_institucion='".$this->verifica_SS(4)."' and cod_dependencia='".$this->verifica_SS(5)."';");
	$_SESSION['dependencia'] = $dependencia[0][0]['denominacion'];
	$varcontieneda = $_SESSION['datos_imp'];
	$this->set('datos_resumen_prest',$varcontieneda);
	$d_firmantes = $this->cnmd15_firmas_informes->findAll($this->SQLCA()." and tipo_documento=23104");
	$this->set('datos_firmantes', $d_firmantes);
}


function resumen_prestaciones_total(){
	$this->layout = "pdf";
	$_SESSION['lista_camposrp'] = 1;
	$estado = $this->cugd01_estados->execute("SELECT denominacion FROM cugd01_estados WHERE cod_republica='".$this->verifica_SS(1)."' and cod_estado='".$this->verifica_SS(2)."';");
	$_SESSION['estado'] = $estado[0][0]['denominacion'];
	$institucion = $this->cugd01_estados->execute("SELECT denominacion FROM cugd02_institucion WHERE cod_tipo_institucion='".$this->verifica_SS(3)."' and cod_institucion='".$this->verifica_SS(4)."';");
	$_SESSION['institucion'] = $institucion[0][0]['denominacion'];
	$dependencia = $this->cugd01_estados->execute("SELECT denominacion FROM cugd02_dependencias WHERE cod_tipo_institucion='".$this->verifica_SS(3)."' and cod_institucion='".$this->verifica_SS(4)."' and cod_dependencia='".$this->verifica_SS(5)."';");
	$_SESSION['dependencia'] = $dependencia[0][0]['denominacion'];
	$varcontieneda = $_SESSION['datos_imp'];
	$this->set('datos_resumen_prest',$varcontieneda);
	$d_firmantes = $this->cnmd15_firmas_informes->findAll($this->SQLCA()." and tipo_documento=23103");
	$this->set('datos_firmantes', $d_firmantes);
}


function index_constancia_prestac_soc($enviard=null){
    $this->layout="ajax";
	echo "<script>
			document.getElementById('submit_pdf').disabled=true;
		</script>";
    $this->Session->delete('cons_codigo_tipo_nomina');
    $lista = $this->Cnmd01->generateList($this->SQLCA()."", 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($this->Cnmd01->findCount($this->SQLCA())!=0){
		$this->concatenaN($lista, 'tipo_nomina');
	}else{
		$this->set('tipo_nomina', array());
	}
	if($enviard!=null){
		$this->set('tipo_reporte', $enviard);
		if($enviard==5 || $enviard=='5'){
			$this->envia_form_firmas(23105); // Constancia de Prestaciones Sociales
		}else{
			$this->envia_form_firmas(23105); // Constancia de Prestaciones Sociales por Defecto
		}
	} // fin if tipo de reporte
}


function constancia_prestac_soc($cod_tipo_nomina=null, $codigo_cargo=null, $codigo_ficha=null, $ced_identidad=null){
   	$this->layout="ajax";

	// $cod_tipo_nomina =	$this->Session->read('cons_codigo_tipo_nomina');

	if($cod_tipo_nomina!=null && $codigo_cargo!=null && $codigo_ficha!=null && $ced_identidad!=null){

	    $datos_personales = $this->cnmd15_datos_personales->execute("SELECT cedula_identidad, primer_apellido, segundo_apellido, primer_nombre, segundo_nombre, denominacion_cargo, institucion, dependencia, fecha_ingreso, fecha_egreso, motivo_retiro FROM cnmd15_datos_personales WHERE ". $this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=".$codigo_cargo." and cod_ficha=".$codigo_ficha." and cedula_identidad=".$ced_identidad." LIMIT 1");

		if($datos_personales!=null){

			$dato_calculo_psoc = $this->calculo_prestaciones($cod_tipo_nomina, $codigo_cargo, $codigo_ficha, $ced_identidad);

			$fecha_de_ingreso = split("-", $datos_personales[0][0]['fecha_ingreso']);
			$fecha_de_ingreso = $fecha_de_ingreso[2]."-".$fecha_de_ingreso[1]."-".$fecha_de_ingreso[0];
			if ($this->compara_fechas_completa($fecha_de_ingreso,"18-06-1997") > 0)
      			$ley_xfecha_ing = 1;
      		else
      			$ley_xfecha_ing = 0;

			$dato_persona_cons['cedula_identidad'] = $datos_personales[0][0]['cedula_identidad'];
			$dato_persona_cons['nombres_apellidos'] = $datos_personales[0][0]['primer_apellido']." ".$datos_personales[0][0]['segundo_apellido']." ".$datos_personales[0][0]['primer_nombre']." ".$datos_personales[0][0]['segundo_nombre'];
			$dato_persona_cons['ley_xfecha_ingreso'] = $ley_xfecha_ing;
			$dato_persona_cons['interes'] = $dato_calculo_psoc[0];
			$dato_persona_cons['totales'] = $dato_calculo_psoc[1];
			$datos_imprimir_const[] = array('datos_persona_co' => $dato_persona_cons);

	 $_SESSION['datos_imp_constancia'] = $datos_imprimir_const;

	 echo '<script>Control.Modal.close(true);</script>';
			echo "<script>
  				document.getElementById('submit_pdf').disabled=false;
			</script>";
	}else{
		$_SESSION['datos_imp_constancia'] = array();
		echo '<script>Control.Modal.close(true);</script>';
			echo "<script> fun_msj('NO SE ENCONTRAR&Oacute;N DATOS PARA PROCESAR');
  				document.getElementById('submit_pdf').disabled=true;
			</script>";
	}
	}else{
		$_SESSION['datos_imp_constancia'] = array();
		echo '<script>Control.Modal.close(true);</script>';
			echo "<script> fun_msj('NO LLEG&Oacute; INFORMACI&Oacute;N COMPLETA PARA PROCESAR');
  				document.getElementById('submit_pdf').disabled=true;
			</script>";
	}

	unset($datos_imprimir_const);

}


function constancia_prestac_soc_reporte(){
	$this->layout = "ajax";
	// $this->layout = "pdf";
	$this->set('codigo_dependencia', $this->verifica_SS(5));
	$estado = $this->cugd01_estados->execute("SELECT denominacion FROM cugd01_estados WHERE cod_republica='".$this->verifica_SS(1)."' and cod_estado='".$this->verifica_SS(2)."';");
	$_SESSION['estado'] = $estado[0][0]['denominacion'];
	$institucion = $this->cugd01_estados->execute("SELECT denominacion FROM cugd02_institucion WHERE cod_tipo_institucion='".$this->verifica_SS(3)."' and cod_institucion='".$this->verifica_SS(4)."';");
	$_SESSION['institucion'] = $institucion[0][0]['denominacion'];
	$dependencia = $this->cugd01_estados->execute("SELECT denominacion FROM cugd02_dependencias WHERE cod_tipo_institucion='".$this->verifica_SS(3)."' and cod_institucion='".$this->verifica_SS(4)."' and cod_dependencia='".$this->verifica_SS(5)."';");
	$_SESSION['dependencia'] = $dependencia[0][0]['denominacion'];
	$var_datos_const = $_SESSION['datos_imp_constancia'];
	$this->set('datos_constancia_perso',$var_datos_const);
	$d_firmantes = $this->cnmd15_firmas_informes->findAll($this->SQLCA()." and tipo_documento=23105");
	$this->set('datos_firmantes', $d_firmantes);
}


function index_antecedente_servicio($enviard=null){
    $this->layout="ajax";
	echo "<script>
			document.getElementById('submit_pdf').disabled=true;
		</script>";
    $this->Session->delete('cons_codigo_tipo_nomina');
    $lista = $this->Cnmd01->generateList($this->SQLCA()."", 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($this->Cnmd01->findCount($this->SQLCA())!=0){
		$this->concatenaN($lista, 'tipo_nomina');
	}else{
		$this->set('tipo_nomina', array());
	}
	if($enviard!=null){
		$this->set('tipo_reporte', $enviard);
		if($enviard==6 || $enviard=='6'){
			$this->envia_form_firmas(23106); // Antecedente de Servicio
		}else{
			$this->envia_form_firmas(23106); // Antecedente de Servicio por Defecto
		}
	} // fin if tipo de reporte
}


function antecedente_servicio($cod_tipo_nomina=null, $codigo_cargo=null, $codigo_ficha=null, $ced_identidad=null){
   	$this->layout="ajax";

	// $cod_tipo_nomina =	$this->Session->read('cons_codigo_tipo_nomina');

	if($cod_tipo_nomina!=null && $codigo_cargo!=null && $codigo_ficha!=null && $ced_identidad!=null){

	    $datos_personales = $this->cnmd15_datos_personales->execute("SELECT cedula_identidad, primer_apellido, segundo_apellido, primer_nombre, segundo_nombre, denominacion_cargo, fecha_ingreso, fecha_egreso, motivo_retiro, denominacion_cargo_inicio, informacion_desempeno, observaciones FROM cnmd15_datos_personales WHERE ". $this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=".$codigo_cargo." and cod_ficha=".$codigo_ficha." and cedula_identidad=".$ced_identidad." LIMIT 1");

	if($datos_personales!=null){

		$datos_persona_dev = $this->cnmd15_devengado->findAll($this->SQLCA().' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$codigo_cargo.' and cod_ficha='.$codigo_ficha.' and cedula_identidad='.$ced_identidad." and fecha_desde='".$datos_personales[0][0]['fecha_ingreso']."'", 'fecha_desde, sueldo_integral', null);

		$datos_persona_dev2 = $this->cnmd15_devengado->findAll($this->SQLCA().' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$codigo_cargo.' and cod_ficha='.$codigo_ficha.' and cedula_identidad='.$ced_identidad." and fecha_hasta='".$datos_personales[0][0]['fecha_egreso']."'", 'fecha_hasta, sueldo_integral', null);

	if($datos_persona_dev != null && $datos_persona_dev2 != null){
		$fecha_primer_cargo = $datos_persona_dev[0]['cnmd15_devengado']['fecha_desde'];
		$remuneracion_men_ant = $datos_persona_dev[0]['cnmd15_devengado']['sueldo_integral'];
		$fecha_ultimo_cargo = $datos_persona_dev2[0]['cnmd15_devengado']['fecha_hasta'];
		$remuneracion_men_ult = $datos_persona_dev2[0]['cnmd15_devengado']['sueldo_integral'];
	}else{
		$fecha_primer_cargo = "";
		$remuneracion_men_ant = "";
		$fecha_ultimo_cargo = "";
		$remuneracion_men_ult = "";
	}

	$dato_persona_antec['cedula_identidad'] = $datos_personales[0][0]['cedula_identidad'];
	$dato_persona_antec['nombres_apellidos'] = $datos_personales[0][0]['primer_apellido']." ".$datos_personales[0][0]['segundo_apellido']." ".$datos_personales[0][0]['primer_nombre']." ".$datos_personales[0][0]['segundo_nombre'];
	$dato_persona_antec['fecha_primer_cargo'] = $fecha_primer_cargo;
	$dato_persona_antec['remuneracion_primer_cargo'] = $remuneracion_men_ant;
	$dato_persona_antec['fecha_ultimo_cargo'] = $fecha_ultimo_cargo;
	$dato_persona_antec['remuneracion_ultimo_cargo'] = $remuneracion_men_ult;
	$dato_persona_antec['denominacion_cargo'] = $datos_personales[0][0]['denominacion_cargo'];
	$dato_persona_antec['motivo_retiro'] = $datos_personales[0][0]['motivo_retiro'];
	$dato_persona_antec['denominacion_cargo_inicio'] = $datos_personales[0][0]['denominacion_cargo_inicio'];
	$dato_persona_antec['informacion_desempeno'] = $datos_personales[0][0]['informacion_desempeno'];
	$dato_persona_antec['observaciones'] = $datos_personales[0][0]['observaciones'];
	$datos_imprimir_antec[] = array('datos_antec_serv' => $dato_persona_antec);

	$_SESSION['datos_imp_antecedente'] = $datos_imprimir_antec;

	 echo '<script>Control.Modal.close(true);</script>';
			echo "<script>
  				document.getElementById('submit_pdf').disabled=false;
			</script>";
	}else{
		$_SESSION['datos_imp_antecedente'] = array();
		echo '<script>Control.Modal.close(true);</script>';
			echo "<script> fun_msj('NO SE ENCONTRAR&Oacute;N DATOS PERSONALES PARA PROCESAR');
  				document.getElementById('submit_pdf').disabled=true;
			</script>";
	}
	}else{
		$_SESSION['datos_imp_antecedente'] = array();
		echo '<script>Control.Modal.close(true);</script>';
			echo "<script> fun_msj('NO LLEG&Oacute; INFORMACI&Oacute;N COMPLETA PARA PROCESAR');
  				document.getElementById('submit_pdf').disabled=true;
			</script>";
	}

	unset($datos_imprimir_antec);

}


function antecedente_servicio_reporte(){
	$this->layout = "ajax";
	// $this->layout = "pdf";
	$estado = $this->cugd01_estados->execute("SELECT denominacion FROM cugd01_estados WHERE cod_republica='".$this->verifica_SS(1)."' and cod_estado='".$this->verifica_SS(2)."';");
	$_SESSION['estado'] = $estado[0][0]['denominacion'];
	$institucion = $this->cugd01_estados->execute("SELECT denominacion FROM cugd02_institucion WHERE cod_tipo_institucion='".$this->verifica_SS(3)."' and cod_institucion='".$this->verifica_SS(4)."';");
	$_SESSION['institucion'] = $institucion[0][0]['denominacion'];
	$dependencia = $this->cugd01_estados->execute("SELECT denominacion FROM cugd02_dependencias WHERE cod_tipo_institucion='".$this->verifica_SS(3)."' and cod_institucion='".$this->verifica_SS(4)."' and cod_dependencia='".$this->verifica_SS(5)."';");
	$_SESSION['dependencia'] = $dependencia[0][0]['denominacion'];
	$var_datos_antecte = $_SESSION['datos_imp_antecedente'];
	$this->set('datos_antecedente_perso',$var_datos_antecte);
	$d_firmantes = $this->cnmd15_firmas_informes->findAll($this->SQLCA()." and tipo_documento=23106");
	$this->set('datos_firmantes', $d_firmantes);
}


function calculo_prestaciones($cod_tipo_nom=null,$codi_cargo=null,$codi_ficha=null,$cedu=null) {

	$this->layout="ajax";

 if($cod_tipo_nom!=null && $codi_cargo!=null && $codi_ficha!=null && $cedu!=null){
   	$cod_tipo_nomina  = $cod_tipo_nom;
   	$codigo_cargo  = $codi_cargo;
   	$codigo_ficha  = $codi_ficha;
   	$ced_identidad = $cedu;
 }

         $c_status=$this->Cnmd01->findCount("".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina."");
         // DEFINICION DE VARIABLES DE LOS CONCEPTOS:
         $concepto_transf = '';
         $concepto_preaviso_trab = '';
         $concepto_indem = '';
         $concepto_indem_sust = '';

         // DEFINICION DE VARIABLES DE LOS DIAS:
         $dias_transf = 0;
         $dias_preaviso_trab = 0;
         $dias_indem = 0;
         $dias_indem_sust = 0;

         // DEFINICION DE VARIABLES DE LOS SUELDOS:
         $sueldo_diario_integral_transf = 0;
         $sueldo_diario_integral = 0;
         $sueldo_diario_total = 0;
         $calcular_intereses_mora = false;
         $calculo_intereses_total = 0;

         // DEFINICION DE VARIABLES DE LOS MONTOS:
         $monto_transf = 0;
         $monto_preaviso_trab = 0;
         $monto_indem = 0;
         $monto_indem_sust = 0;
         $calculo_interese_total = 0;

         // DEFINICION DE VARIABLES PARA GUARDAR DATOS EN TABLAS
         $dato_presta = 0;
         $dato_interes =0;
         $totales_monto_prestaciones = 0;

		// DEFINICION DE VARIABLES A RETORNAR POR LA FUNCION
		$intereses = 0;
		$totales = 0;

         if($c_status!=0){

         	$d1=$this->Cnmd01->execute("SELECT salario_minimo FROM cscd04_ordencompra_parametros WHERE ".$this->SQLCA());
         	$salario_minimo = $d1[0][0]['salario_minimo'];
		    $this->Cnmd01->execute("DELETE FROM cnmd15_datos_prestaciones WHERE ".$this->SQLCA()." and cod_tipo_nomina=$cod_tipo_nomina and cod_cargo=$codigo_cargo and cod_ficha=$codigo_ficha and cedula_identidad=$ced_identidad");
			$this->Cnmd01->execute("DELETE FROM cnmd15_datos_intereses WHERE ".$this->SQLCA()." and cod_tipo_nomina=$cod_tipo_nomina and cod_cargo=$codigo_cargo and cod_ficha=$codigo_ficha and cedula_identidad=$ced_identidad");
         	$this->Cnmd01->execute("DELETE FROM cnmd15_datos_intereses_mora WHERE ".$this->SQLCA()." and cod_tipo_nomina=$cod_tipo_nomina and cod_cargo=$codigo_cargo and cod_ficha=$codigo_ficha and cedula_identidad=$ced_identidad");
         	$d2 = $this->Cnmd01->execute("SELECT * FROM v_cnmd15_datos_personales_prestaciones WHERE ".$this->SQLCA()." and cod_tipo_nomina=$cod_tipo_nomina and cod_cargo=$codigo_cargo and cod_ficha=$codigo_ficha and cedula_identidad=$ced_identidad");


		if($d2!=null){

            foreach($d2 as $datos1){ // inicio datos1
                $fecha_ingreso     = $datos1[0]['fecha_ingreso'];
               	$fecha_egreso      = $datos1[0]['fecha_egreso'];
                $dia               = $datos1[0]['dia'];
                $mes               = $datos1[0]['mes'];
                $ano               = $datos1[0]['ano'];
                $dia_ingreso       = $datos1[0]['dia_ingreso'];
                $mes_ingreso       = $datos1[0]['mes_ingreso'];
                $ano_ingreso       = $datos1[0]['ano_ingreso'];
                $dia_egreso        = $datos1[0]['dia_egreso'];
                $mes_egreso        = $datos1[0]['mes_egreso'];
                $ano_egreso        = $datos1[0]['ano_egreso'];
                $cod_cargo         = $datos1[0]['cod_cargo'];
                $cod_ficha         = $datos1[0]['cod_ficha'];
                $cedula_identidad  = $datos1[0]['cedula_identidad'];
                $motivo_retiro     = $datos1[0]['motivo_retiro'];
                $cumplio_preaviso  = $datos1[0]['cumplio_preaviso'];


	/********************************************************
	 ********************************************************
	 **  $motivo_retiro = 1  --> DESPIDO JUSTIFICADO       **
	 **  $motivo_retiro = 2  --> DESPIDO INJUSTIFICADO     **
	 **  $motivo_retiro = 3  --> RETIRO JUSTIFICADO        **
	 **  $motivo_retiro = 4  --> RENUNCIA                  **
	 **  $motivo_retiro = 5  --> JUBILACION				   **
	 **  $motivo_retiro = 6  --> PENSIONADO			       **
	 **  $motivo_retiro = 7  --> CULMINACION DE CONTRATO   **
	 **  $motivo_retiro = 8  --> BAJA POR PROPIA SOLICITUD **
	 **  $motivo_retiro = 9  --> FALLECIMIENTO			   **
	 **  $motivo_retiro = 10 --> BAJA POR EXPULSION		   **
	 **  $motivo_retiro = 11 --> REMOCION DEL CARGO		   **
	 **  $motivo_retiro = 12 --> REDUCCION DEL PERSONAL    **
	 ********************************************************
	 ********************************************************
	 *  */



            	// MESES FRACCIONADOS POR VACACIONES POR PERIODOS:


			$mesv=0;
			if(($ano>0 && $mes!=0) || ($ano==0 && $mes>=1)){ $mesv = $mes; }

				if ($dia>=30){ $mesv = $mesv+1; $dia_bv = 0;
				}else{
					$dia_bv = $dia;
				}


				// MESES FRACCIONADOS POR VACACIONES COLECTIVAS:


                $vacaciones_colectivas=$this->Cnmd01->findCount($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and vacaciones_colectivas=1");
				if($vacaciones_colectivas!=0){
					$mesv = 0;
					$dia_vac_colect = 0;
					$d3=$this->Cnmd01->execute("SELECT dia_vaca,mes_vaca FROM cnmd01 WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
                    $dia_vaca_colectiva = mascara($d3[0][0]['dia_vaca'],2);
                    $mes_vaca_colectiva = mascara($d3[0][0]['mes_vaca'],2);

					if($mes_egreso<$mes_vaca_colectiva){ $mesv = 12-($mes_vaca_colectiva-$mes_egreso); }
					if($mes_egreso>$mes_vaca_colectiva){ $mesv = ($mes_egreso-$mes_vaca_colectiva); }
					if($dia_egreso<$dia_vaca_colectiva){ $dia_vac_colect = ($dia_vaca_colectiva-$dia_egreso); }
					if($dia_egreso>$dia_vaca_colectiva){ $dia_vac_colect = ($dia_egreso-$dia_vaca_colectiva); }
					$dia_vac_colect = ($dia_egreso-$dia_vaca_colectiva);
					if ($dia_vac_colect>=30){ $mesv = $mesv+1; $dia_bv = 0;
					}else{
						$dia_bv = $dia_vac_colect;
					}

				}


				// MESES FRACCIONADOS DE AGUINALDOS:


				$fecha_ingreso_ag = $fecha_ingreso;
                if ($ano_ingreso!=$ano_egreso){$fecha_ingreso_ag = $ano_ingreso."-01-01";}

                $d5=$this->Cnmd01->execute("select devolver_edad('".$fecha_egreso."', '".$fecha_ingreso_ag."', 'ANO') as ano_ag,devolver_edad('".$fecha_egreso."', '".$fecha_ingreso_ag."', 'MES') as mes_ag,devolver_edad('".$fecha_egreso."', '".$fecha_ingreso_ag."', 'DIA') as dia_ag");
				$dia_ag = mascara($d5[0][0]['dia_ag'],2);
                $mes_ag = mascara($d5[0][0]['mes_ag'],2);
                $ano_ag = $d5[0][0]['ano_ag'];

				if  ($dia_ag>=30){
					$mes_aguinaldo = ($mes_ag+1); $dia_ag = 0;
				}else{
					$mes_aguinaldo = $mes_ag;
				}


	// SUELDO BASICO, SUELDO INTEGRAL Y SUELDO TOTAL:

 	$d6=$this->Cnmd01->execute("SELECT sueldo_integral, sueldo_total, sueldo_basico FROM cnmd15_devengado WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cedula_identidad=$cedula_identidad and fecha_hasta>='$fecha_egreso'");

	foreach($d6 as $datos6){//datos6
		$sueldo_diario_basico   = $this->redondeo(($datos6[0]['sueldo_basico']/30));
		$sueldo_diario_integral = $this->redondeo(($datos6[0]['sueldo_integral']/30));
		$sueldo_diario_total    = $this->redondeo(($datos6[0]['sueldo_total']/30));





												/**==========================================================
												 ************************ LEY ANTERIOR **********************
												 **========================================================**/




													// COMPENSACION POR TRANSFERENCIA - ART. 666


		if(($ano_ingreso<1997) || ($ano_ingreso==1997 && $mes_ingreso<6) || ($ano_ingreso==1997 && $mes_ingreso==6 && $dia_ingreso<=18)){

					$fecha_anticipo = date('Y-m-d');
                	$fecha_egreso_transf = '1997-06-18';
                	$fecha_egreso_transf2 = '1996-12-31';
                	$d5=$this->Cnmd01->execute("select devolver_edad('".$fecha_egreso_transf."', '".$fecha_ingreso."', 'ANO') as ano_transf,devolver_edad('".$fecha_egreso_transf."', '".$fecha_ingreso."', 'MES') as mes_transf,devolver_edad('".$fecha_egreso_transf."', '".$fecha_ingreso."', 'DIA') as dia_transf");
					$dia_transf = mascara($d5[0][0]['dia_transf'],2);
                	$mes_transf = mascara($d5[0][0]['mes_transf'],2);
                	$ano_transf = $d5[0][0]['ano_transf'];

                		$datos_anticipo_bono = $this->cnmd15_anticipo_bono_transf->findAll($this->SQLCA().' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$cod_cargo.' and cod_ficha='.$cod_ficha.' and cedula_identidad='.$cedula_identidad);
						if(!empty($datos_anticipo_bono)){
							foreach($datos_anticipo_bono as $row_datos_antic){
								$monto_trans = $row_datos_antic['cnmd15_anticipo_bono_transf']['monto_bono'];
								$monto_antic = $row_datos_antic['cnmd15_anticipo_bono_transf']['monto_anticipo'];
								$deuda_bono_anticipo = ($monto_trans-$monto_antic);
								$complementa_conc =  ' ('.$this->Formato2($monto_trans).'-'.$this->Formato2($monto_antic).')';
								}
							}else{
								$monto_trans = 0;
								$monto_antic = 0;
								$deuda_bono_anticipo = 0;
								$complementa_conc =  '';
								}

			if($ano_transf!=0 && $deuda_bono_anticipo==0 && $monto_antic==0 || $ano_transf!=0 && $deuda_bono_anticipo!=0 && $monto_antic!=0 || $ano_transf!=0 && $deuda_bono_anticipo!=0 && $monto_antic==0){
                		$de=$this->Cnmd01->execute("SELECT sueldo_integral FROM cnmd15_devengado WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cedula_identidad=$cedula_identidad and (fecha_desde<='$fecha_egreso_transf2' and fecha_hasta>='$fecha_egreso_transf2')");
						foreach($de as $datosde){
							$sueldo_diario_integral_transf = $this->redondeo(($datosde[0]['sueldo_integral']/30));
						}

						if($ano_transf>13){$ano_transf = 13;}
						if($sueldo_diario_integral_transf<0.5){$sueldo_diario_integral_transf = 0.5;}
						#if($sueldo_diario_integral_transf>10.0){$sueldo_diario_integral_transf = 10.0;}
						#echo $sueldo_diario_integral_transf;
						$dias_transf  = ($ano_transf*30);
						$monto_transf = $this->redondeo(($dias_transf*$sueldo_diario_integral_transf));
						if($deuda_bono_anticipo!=0){$monto_transf = $deuda_bono_anticipo;}
						#echo $monto_transf;
						$intereses_transferencia = $this->calculo_intereses($c, 0, $cod_tipo_nomina, $codigo_cargo, $codigo_ficha, $ced_identidad);


			}// if $ano_transf
		}//ingreso antes del 1997
	}//Busca sueldo



												/**==========================================================
												 ************************* LEY ACTUAL ***********************
												 **========================================================**/


															// ARTICULO 107 - PREAVISO

														// PREAVISO DEL TRABAJADOR AL PATRONO


				if ($cumplio_preaviso == 2 && ($motivo_retiro == 1 || $motivo_retiro == 4)){
						if ($ano>=1){
							$dias_preaviso_trab = 30;
						}
						if ($ano==0 && $mes>6){
							$dias_preaviso_trab = 15;
						}
						if ($ano==0 && $mes==6 && $dia>=1){
							$dias_preaviso_trab = 15;
						}
						if ($ano==0 && $mes>=1 && $mes<=6 && $dia==0){
							$dias_preaviso_trab = 7;
						}
						$monto_preaviso_trab = $this->redondeo(($dias_preaviso_trab*$sueldo_diario_integral)*-1);
						$concepto_preaviso_trab = 'PREAVISO - ART. 107';
						if($dias_preaviso_trab!=0){
						$dato_presta = ($dato_presta+1);
						$sql_insert_comp = "INSERT INTO cnmd15_datos_prestaciones (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, consecutivo, concepto, dias, salario_diario, monto)";
						$sql_insert_comp .= " VALUES ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_presta."','".$concepto_preaviso_trab."','".$dias_preaviso_trab."','".$sueldo_diario_integral."','".$monto_preaviso_trab."');";
						$this->Cnmd01->execute($sql_insert_comp);
						}
				}




														// INDEMNIZACION - ARTICULO 125


				if($motivo_retiro == 2 || $motivo_retiro == 3){

								if ($ano>=5){
									$dias_indem = 150;
								}

								if ($ano>1 && $ano<5 && $mes>6){
									$dias_indem = (($ano*30)+30);
								}
								if ($ano>1 && $ano<5 && $mes==6 && $dia>=1){
									$dias_indem = (($ano*30)+30);
								}
								if ($ano>1 && $ano<5 && $mes==6 && $dia==0){
									$dias_indem = ($ano*30);
								}
								if ($ano>1 && $ano<5 && $mes<6){
									$dias_indem = ($ano*30);
								}

								if ($ano==1 && $mes>6){
									$dias_indem = 60;
								}
								if ($ano==1 && $mes==6 && $dia>=1){
									$dias_indem = 60;
								}
								if ($ano==1 && $mes==6 && $dia==0){
									$dias_indem = 30;
								}
								if ($ano==1 && $mes<6){
									$dias_indem = 30;
								}

								if ($ano==0 && $mes>6){
									$dias_indem = 30;
								}
								if ($ano==0 && $mes==6 && $dia>=1){
									$dias_indem = 30;
								}
								if ($ano==0 && $mes==6 && $dia==0){
									$dias_indem = 10;
								}
								if ($ano==0 && $mes>=3 && $mes<6){
									$dias_indem = 10;
								}
								$monto_indem = $this->redondeo(($dias_indem*$sueldo_diario_total));
								$concepto_indem = 'INDEMNIZACION - ART. 125';
								if($dias_indem!=0){
								$dato_presta = ($dato_presta+1);
								$sql_insert_comp = "INSERT INTO cnmd15_datos_prestaciones (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, consecutivo, concepto, dias, salario_diario, monto)";
								$sql_insert_comp .= " VALUES ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_presta."','".$concepto_indem."','".$dias_indem."','".$sueldo_diario_total."','".$monto_indem."');";
								$this->Cnmd01->execute($sql_insert_comp);
								}
			}




														/** INDEMNIZACION SUSTITUTIVA DEL PREAVISO */


				if($motivo_retiro == 2 || $motivo_retiro == 3){

								if ($ano>=11){
									$dias_indem_sust = 90;
								}
								if ($ano>1 && $ano<=10){
									$dias_indem_sust = 60;
								}
								if ($ano==1){
									$dias_indem_sust = 45;
								}
								if ($ano==0 && $mes>6){
									$dias_indem_sust = 30;
								}
								if ($ano==0 && $mes==6 && $dia>=1){
									$dias_indem_sust = 30;
								}
								if ($ano==0 && $mes==6 && $dia==0){
									$dias_indem_sust = 15;
								}
								if ($ano==0 && $mes>=1 && $mes<6){
									$dias_indem_sust = 15;
								}
								$monto_indem_sust = ($dias_indem_sust*$sueldo_diario_total);
								if($monto_indem_sust > ($salario_minimo*10)){
									$monto_indem_sust = ($salario_minimo*10);
								}
								$monto_indem_sust = $this->redondeo($monto_indem_sust);
								$concepto_indem_sust = 'INDEMNIZACION SUSTITUTIVA DEL PREAVISO';
								if($dias_indem_sust!=0){
								$dato_presta = ($dato_presta+1);
								$sql_insert_comp = "INSERT INTO cnmd15_datos_prestaciones (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, consecutivo, concepto, dias, salario_diario, monto)";
								$sql_insert_comp .= " VALUES ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_presta."','".$concepto_indem_sust."','".$dias_indem_sust."','".$sueldo_diario_total."','".$monto_indem_sust."');";
								$this->Cnmd01->execute($sql_insert_comp);
								}
			}






													   		/** ************ ANTIGUEDAD ************** */


		//if($ano>=1 || ($ano==0 && $mes>3) || ($ano==0 && $mes==3 && $dia>=1)){
		if($ano>=1 || ($ano==0 && $mes>0)){
								$a1 = $a2 = $a3 = $a4 = $a5 = $a6 = $a7 = $a8 = $a9 = $a10 = 0;
								$ta = $anticipo_anterior = $anticipo_actual = $linea = $dias_anterior = $monto_antig_anterior = $continuo = $dias_actual = $dias_lit_c = $monto_antig_actual = $monto_antig_lit_c = $interes_actual = $dato_intereses = $aaa = $mmm = $ddd = $adic = $sueldo_diario_anterior = $sueldo_diario_actual = $dias_adicional = $interes_anterior = 0;
								$concepto_antiguedad_anterior = '';
								$concepto_antiguedad_actual = '';
								$concepto_antiguedad_lit_c = '';
								$tservrn = '';
								$tservrv = '';

								$rango = $this->Cnmd01->execute("SELECT fecha_desde, fecha_hasta FROM cnmd15_rango WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
									//echo "SELECT fecha_desde, fecha_hasta FROM cnmd15_rango WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina;
								//print_r($rango);
								foreach($rango as $datos_rango){
								$fecha_desde_rango = $datos_rango[0]['fecha_desde'];
								$fecha_hasta_rango = $datos_rango[0]['fecha_hasta'];
								$rangod = $fecha_desde_rango[0].$fecha_desde_rango[1].$fecha_desde_rango[2].$fecha_desde_rango[3];
								$rangoh = $fecha_hasta_rango[0].$fecha_hasta_rango[1].$fecha_hasta_rango[2].$fecha_hasta_rango[3];
								}

				//LEY ANTERIOR:

								if(($ano_ingreso<1996) || ($ano_ingreso==1996 && $mes_ingreso<12) || ($ano_ingreso==1996 && $mes_ingreso==12 && $dia_ingreso<=19)){
									$continuo = 1; }



				if(($ano_ingreso<1997) || ($ano_ingreso==1997 && $mes_ingreso<6) || ($ano_ingreso==1997 && $mes_ingreso==6 && $dia_ingreso<=18)){

								if($ano_egreso>=1997){ $ano_egreso_antig = 1997; }else{ $ano_egreso_antig = $ano_egreso; }

								$sql_insert_comp2 = "INSERT INTO cnmd15_datos_intereses (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, consecutivo, desde, hasta, salario_mensual, salario_diario, dias_antiguedad, monto_antiguedad, prestaciones_acumuladas, anticipo, capital, tasa, interes, intereses_acumulados) VALUES ";


							// FOR AÑO:

							for ($k=$ano_ingreso;$k<=$ano_egreso_antig;$k++){$hf=12;

								if($k==$ano_ingreso){$m1 = $mes_ingreso;}else{$m1 = 1;}
								if($k==$ano_egreso){$hf = $mes_egreso;}
								if($k==1997 && $ano_egreso>1997){$hf = 5;}
								if($k==1997 && $ano_egreso==1997 && $mes_egreso>=6){$hf = 5;}
								if($k==1997 && $ano_egreso==1997 && $mes_egreso<6){$hf = $mes_egreso;}

									// FOR MES:

										for ($j=$m1;$j<=$hf;$j++){
											if($ano_egreso==$k && $mes_egreso==$j && (($dia_egreso-$dia_ingreso)!=30)){
											// COMPLETA CICLO.
											}else{
											$fecha_busqueda = $k."-".$j."-".$dia_ingreso;



												// SUELDO BASICO, SUELDO INTEGRAL Y SUELDO TOTAL:

 												$dev=$this->Cnmd01->execute("SELECT sueldo_integral, sueldo_total, sueldo_basico FROM cnmd15_devengado WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cedula_identidad=$cedula_identidad and (fecha_desde<='$fecha_busqueda' and fecha_hasta>='$fecha_busqueda');");
												foreach($dev as $datosv6){
												$sueldo_diario_basico1   = $this->redondeo(($datosv6[0]['sueldo_basico']/30));
												$sueldo_diario_integral1 = $this->redondeo(($datosv6[0]['sueldo_integral']/30));
												$sueldo_diario_total1    = $this->redondeo(($datosv6[0]['sueldo_total']/30));
												$sueldo_mensual_basico   = $datosv6[0]['sueldo_basico'];
												$sueldo_mensual_integral = $datosv6[0]['sueldo_integral'];
												$sueldo_mensual_total    = $datosv6[0]['sueldo_total'];
												}
											}

												if($k<1975 || $k<$rangod || $k>$rangoh){
													//echo $k."---".$rangod."---".$rangoh."<br/>";
													$ta = 0;
													$a8 = 0;
												}
												else{

												// TASAS BANCO CENTRAL:
												$mes_encontrado = $this->fecha_str(''.$j);
												$mes_busqueda = 'tasa_'.$mes_encontrado;

												$tasa = $this->Cnmd01->execute("SELECT $mes_busqueda FROM cnmd15_tasa_interes WHERE ano=$k LIMIT 1;");

													if(!empty($tasa)){$a8 = $tasa[0][0]["$mes_busqueda"];}else{ $a8 = 0;}

												// FIDEICOMISO DEPOSITADO
													$depo = $this->Cnmd01->execute("SELECT $mes_encontrado FROM cnmd15_depo_fideicomiso WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and ano=$k LIMIT 1;");

													if(!empty($depo)){$fidecomiso_depo = $depo[0][0]["$mes_encontrado"]; } else { $fidecomiso_depo = 0; }
													if($fidecomiso_depo==1){ $a8 = 0;}
													//echo $a8."--".$k."<br/>";
												}



												// ANTICIPOS ENTREGADOS
												$a6 = 0;
												$dean = $this->Cnmd01->execute("SELECT monto_anticipo FROM cnmd15_anticipos WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cedula_identidad=$cedula_identidad and ano=$k and mes=$j LIMIT 1;");

													if(!empty($dean)){$a6 = $dean[0][0]["monto_anticipo"];}else{ $a6 = 0;}

													$dia_feb=1;
													$dia_hasta_feb=0;

													if ($dia_ingreso>=30){ $dia_feb=0; }

													if ($j==2 && $dia_ingreso==28){ $dia_feb=0; }
													if ($j==2 && $dia_ingreso==29){ $dia_feb=-1; }
													if ($j==2 && $dia_ingreso==30){ $dia_feb=-2; }
													if ($j==2 && $dia_ingreso==31){ $dia_feb=-3; }

													if ($j==1 && $dia_ingreso==29){ $dia_hasta_feb=-1; }
													if ($j==1 && $dia_ingreso==30){ $dia_hasta_feb=-2; }
													if ($j==1 && $dia_ingreso==31){ $dia_hasta_feb=-3; }

													if($k==$ano_ingreso && $j==$m1){
														$desde = $k."-".$this->zero($j)."-".$this->zero($dia_ingreso);
													}else{
														$desde = $k."-".$this->zero($j)."-".$this->zero($dia_ingreso+$dia_feb);
													}

													$hasta = $k."-".$this->zero($j+1)."-".$this->zero($dia_ingreso+$dia_hasta_feb);
													if($j==12){$hasta = ($k+1)."-01-".$this->zero($dia_ingreso);}
													if($ano_egreso==$k && $mes_egreso==$j && $dia_egreso==31){$hasta = $ano_egreso."-".$this->zero($mes_egreso)."-".$this->zero($dia_egreso);}
													if($j==12){$mesi = -11; $anno = ($k+1);}else{$mesi = 1; $anno = 0;}

													if(($k+$anno)!=$ano_ingreso && ($j+$mesi)==$mes_ingreso){

													$a1 = $sueldo_mensual_total;
													$a2 = ($sueldo_mensual_total/30);
													$a3 = 30;
													$a4 = ($a2*$a3);
													//$a5 = ($a5+$a4);

													$a5 = 0;
													$a7 = (($a7+$a4+$a9)-$a6);
													$a9 = $this->redondeo(((($a7/100)*$a8)/12));
													$a10 = ($a10+$a9);
													$anticipo_anterior = ($anticipo_anterior+$a6);
													$linea = 1;
													}else if($linea==1){

													$a1 = $sueldo_mensual_total;
													$a2 = ($sueldo_mensual_total/30);
													$a3 = 0;
													$a4 = 0;
													//$a5 = ($a5+$a4);
													$a5 = 0;
													$a7 = (($a7+$a9)-$a6);
													$a9 = $this->redondeo(((($a7/100)*$a8)/12));
													$a10 = ($a10+$a9);
													$anticipo_anterior = ($anticipo_anterior+$a6);
													}else if($linea==0){

													$a1 = $sueldo_mensual_total;
													$a2 = ($sueldo_mensual_total/30);
													$a3 = 0;
													$a4 = 0;
													$a5 = 0;
													$a7 = 0;
													$a9 = 0;
													$a10 = 0;
													}

												$dato_intereses = ($dato_intereses+1);

												$valores2[] = " ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_intereses."', '".$desde."', '".$hasta."', '".$a1."','".$a2."','".$a3."','".$a4."','".$a5."','".$a6."','".$a7."','".$a8."','".$a9."','".$a10."')";
												#Ultima Vuelta Antes del 97
												if ($k == $ano_egreso_antig and $j == $hf){

													$a1 = $sueldo_mensual_total;
													$a2 = ($sueldo_mensual_total/30);
													$a3 = 0;
													$a4 = 0;
													//$a5 = ($a5+$a4);
													$a5 = 0;
													$a7 = (($a7+$a9)-$a6);
													$a9 = $this->redondeo(((($a7/100)*$a8)/12));
													$a10 = ($a10+$a9);
													$anticipo_anterior = ($anticipo_anterior+$a6);
													$dato_intereses = ($dato_intereses+1);

													$desde = $k."-".$this->zero($j+1)."-".$this->zero($dia_ingreso+$dia_feb);

													$hasta = $k."-".$this->zero($j+1)."-".$this->zero(18+$dia_hasta_feb);

													$valores2[] = " ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_intereses."', '".$desde."', '".$hasta."', '".$a1."','".$a2."','".$a3."','".$a4."','".$a5."','".$a6."','".$a7."','".$a8."','".$a9."','".$a10."')";
													#echo "Ultima Vuelta ley antes del 97";
												}
										}
							}

									$sql_insert_comp2 .= " ".implode(',', $valores2).";";
									$this->Cnmd01->execute($sql_insert_comp2);

									$interes_anterior = $a10;
									$sueldo_diario_anterior = $sueldo_diario_total1;

							if(($ano_egreso>1997) || ($ano_egreso==1997 && $mes_egreso>6) || ($ano_egreso==1997 && $mes_egreso==6 && $dia_egreso>18)){$rd = 19; $rm = 6;$ra = 1997;}else{$rd = $dia_egreso;	$rm = $mes_egreso; $ra = $ano_egreso;}

                				$fo = $ra."-".$rm."-".$rd;
                				$fs = $ano_ingreso."-".$mes_ingreso."-".$dia_ingreso;
                				$sql_antiguedad = $this->Cnmd01->execute("select devolver_edad('".$fo."', '".$fs."', 'ANO') as aaa,devolver_edad('".$fo."', '".$fs."', 'MES') as mmm,devolver_edad('".$fo."', '".$fs."', 'DIA') as ddd");
								$ddd = $sql_antiguedad[0][0]['ddd'];
                				$mmm = $sql_antiguedad[0][0]['mmm'];
                				$aaa = $sql_antiguedad[0][0]['aaa'];
                				#echo $ddd;
							$dias_anterior=0;
							if($aaa>=1 && $mmm>6){$dias_anterior=(($aaa*30)+30);}else
							if($aaa>=1 && $mmm==6 && $ddd>0){$dias_anterior=(($aaa*30)+30);}else
							if($aaa>=1 && $mmm==6 && $ddd==0){$dias_anterior=(($aaa*30)+($mmm*2.5));}else
							if($aaa>=1 && $mmm<6 && $ddd>=30){$dias_anterior=(($aaa*30)+(($mmm+1)*2.5));}else
							if($aaa>=1 && $mmm<6 && $ddd<30){$dias_anterior=( ($aaa*30) + ($mmm*2.5) + ($ddd*(2.5/30)) );}else

							if($aaa==0 && $mmm>6){$dias_anterior=30;}else
							if($aaa==0 && $mmm==6 && $ddd>0){$dias_anterior=30;}else
							if($aaa==0 && $mmm==6 && $ddd==0){$dias_anterior=($mmm*2.5);}else
							if($aaa==0 && $mmm>=3 && $mmm<6){$dias_anterior=10;}

							$monto_antig_anterior = $this->redondeo(($sueldo_diario_anterior*$dias_anterior));
							$tservrv = "Años=$aaa   Meses=$mmm    Dias=$ddd";

							if(($ano_egreso<1997) || ($ano_egreso==1997 && $mes_egreso<6) || ($ano_egreso==1997 && $mes_egreso==6 && $dia_egreso<=18)){
							$dia_ingreso_actual = $dia_ingreso;
							$mes_ingreso_actual = $mes_ingreso;
							$ano_ingreso_actual = $ano_egreso;
							$i1 = $ano_ingreso;
							$linea = 0;
							$adic = 2;
							$m2 = 0;
							$a3 = 0;
							}
							else{


							$dia_ingreso_actual = 19;
							$mes_ingreso_actual = 6;
							$ano_ingreso_actual = 1997;
							$i1 = $ano_ingreso_actual;
							$m2 = 0;
							$a3 = 0;
							$linea = 0;
							$adic = 2;
							$aa2 = 0;
							$salario_1 = 0;
							$salario_2 = 0;
							}
				}




				// LEY ACTUAL:

						if(($ano_ingreso>1997) || ($ano_ingreso==1997 && $mes_ingreso>6) || ($ano_ingreso==1997 && $mes_ingreso==6 && $dia_ingreso>18)){
							$m2 = 0;
							$linea = 0;
							#$adic = 0;
							$dia_ingreso_actual = $dia_ingreso;
							$mes_ingreso_actual = $mes_ingreso;
							$ano_ingreso_actual = $ano_ingreso;
							$i1 = $ano_ingreso;
							$nueva_ley = 0;
							$test_ = 0;
							$aa2 = 0;
							$salario_1 = 0;
							$salario_2 = 0;
						}
							$prueba_2= 1;
							$sql_insert_comp3 = "INSERT INTO cnmd15_datos_intereses (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, consecutivo, desde, hasta, salario_mensual, salario_diario, dias_antiguedad, monto_antiguedad, prestaciones_acumuladas, anticipo, capital, tasa, interes, intereses_acumulados) VALUES ";

					// FOR AÑO:



						for ($k=$i1;$k<=$ano_egreso;$k++){
							$linea = ($linea+1);
							$hf=12;
							if($k==$ano_ingreso_actual){$m1 = $mes_ingreso_actual;}else{$m1 = 1;}

							if($k==$ano_egreso){$hf = $mes_egreso;}

							//if($k==$ano_egreso && $dia_egreso<$dia_ingreso_actual){echo "string";$hf = ($hf-2);}

							// FOR MES:

								for ($j=$m1;$j<=$hf;$j++){

										if($ano_egreso==$k && $mes_egreso==$j && (($dia_egreso-$dia_ingreso_actual)!=30)){// COMPLETA CICLO.
										}else{

											if($m2!=0){$m2 = ($m2-1); }

										$fecha_busqueda = $k."-".$j."-".$dia_ingreso_actual;


										// SUELDO BASICO, SUELDO INTEGRAL Y SUELDO TOTAL:

 										$dev=$this->Cnmd01->execute("SELECT sueldo_integral, sueldo_total, sueldo_basico FROM cnmd15_devengado WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cedula_identidad=$cedula_identidad and (fecha_desde<='$fecha_busqueda' and fecha_hasta>='$fecha_busqueda');");
										foreach($dev as $datosv6){
										$sueldo_diario_basico1   = $this->redondeo(($datosv6[0]['sueldo_basico']/30));
										$sueldo_diario_integral1 = $this->redondeo(($datosv6[0]['sueldo_integral']/30));
										$sueldo_diario_total1    = $this->redondeo(($datosv6[0]['sueldo_total']/30));
										$sueldo_mensual_basico   = $datosv6[0]['sueldo_basico'];
										$sueldo_mensual_integral = $datosv6[0]['sueldo_integral'];
										$sueldo_mensual_total    = $datosv6[0]['sueldo_total'];

										#echo $sueldo_mensual_total.'<br/>';
										}


									if($k<1975 || $k<$rangod || $k>$rangoh){$ta = 0; $a8 = 0;}else{

										// TASAS BANCO CENTRAL:
										$mes_encontrado = $this->fecha_str(''.$j);
										$mes_busqueda = 'tasa_'.$mes_encontrado;

										$tasa = $this->Cnmd01->execute("SELECT $mes_busqueda FROM cnmd15_tasa_interes WHERE ano=$k LIMIT 1;");

										if(!empty($tasa)){$a8 = $tasa[0][0]["$mes_busqueda"];}else{ $a8 = 0;}

										// FIDEICOMISO DEPOSITADO

										$depo = $this->Cnmd01->execute("SELECT $mes_encontrado FROM cnmd15_depo_fideicomiso WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and ano=$k LIMIT 1;");
										if(!empty($depo)){$fidecomiso_depo = $depo[0][0]["$mes_encontrado"];}else{ $fidecomiso_depo = 0;}
										if($fidecomiso_depo==1){ $a8 = 0;}
									}

										// DIAS ANTIGUEDAD

										$dia_encontrado = $this->fecha_str(''.$j);
										$dia_busqueda = 'dias_'.$dia_encontrado;

										$sql_dia_antig = $this->Cnmd01->execute("SELECT $dia_busqueda FROM cnmd15_dias_antiguedad WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and ano=$k LIMIT 1;");
										#echo "SELECT $dia_busqueda FROM cnmd15_dias_antiguedad WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and ano=$k LIMIT 1;";

										if(!empty($sql_dia_antig)){$a3 = $sql_dia_antig[0][0]["$dia_busqueda"];}else{ $a3 = 0;}

										if($j==1 && $linea>15 && $j==$mes_ingreso_actual){$a3 = ($a3+30); $dias_adicional=($dias_adicional+30);}else
										if($linea>15 && ($j+1)==$mes_ingreso_actual){$a3 = ($a3+30); $dias_adicional=($dias_adicional+30);}


										$test_ = ($mes_ingreso == 1) ? $test_ = 13: $mes_ingreso;
										if($j==1 && ($linea>=1 && $linea<=15) && ($j+1)==$test_){ #aca dias adicionales por año

                                            if ($adic>=30){$adic=30;}
											$a3 = ($a3+$adic);
											$dias_adicional=($dias_adicional+$adic);
											$adic = ($adic+2);

										}else

											if(($linea>=1 && $linea<=15) && ($j+1)==$test_){ # acá mas dos

												if ($adic>=30){$adic=30;}
												$a3 = ($a3+$adic);
												$dias_adicional=($dias_adicional+$adic);
												$adic = ($adic+2);
										}


										// ANTICIPOS ENTREGADOS

										$a6 = 0;
										$dean = $this->Cnmd01->execute("SELECT monto_anticipo FROM cnmd15_anticipos WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cedula_identidad=$cedula_identidad and ano=$k and mes=$j LIMIT 1;");
										if(!empty($dean)){$a6 = $dean[0][0]["monto_anticipo"];}else{ $a6 = 0;}

										$dia_feb=1;
										$dia_hasta_feb=0;

										if ($dia_ingreso>=30){ $dia_feb=0; }
										if ($j==2 && $dia_ingreso==28){ $dia_feb=0; }
									    if ($j==2 && $dia_ingreso==28){ $dia_feb=0; }
										if ($j==2 && $dia_ingreso==29){ $dia_feb=-1; }
										if ($j==2 && $dia_ingreso==30){ $dia_feb=-2; }
										if ($j==2 && $dia_ingreso==31){ $dia_feb=-3; }

										if ($j==1 && $dia_ingreso==29){ $dia_hasta_feb=-1; }
										if ($j==1 && $dia_ingreso==30){ $dia_hasta_feb=-2; }
										if ($j==1 && $dia_ingreso==31){ $dia_hasta_feb=-3; }

										if($k==$ano_ingreso_actual && $j==$m1){
											$desde = $k."-".$this->zero($j)."-".$this->zero($dia_ingreso_actual);
										}else{
											$desde = $k."-".$this->zero($j)."-".$this->zero($dia_ingreso_actual+$dia_feb);
										}

										$hasta = $k."-".$this->zero($j+1)."-".$this->zero($dia_ingreso_actual+$dia_hasta_feb);

										if($j==12){$hasta = ($k+1)."-01-".$this->zero($dia_ingreso_actual); }
										if($ano_egreso==$k && $mes_egreso==$j && $dia_egreso==31){$hasta = $ano_egreso."-".$this->zero($mes_egreso)."-".$this->zero($dia_egreso); }
										if($j==12){$mesi = -11; $anno = ($k+1);}else{$mesi = 1;	$anno = 0;}
										//echo $a3.'<br />';
										//echo $nueva_ley.'<br/>';
										$a1 = $sueldo_mensual_total;
										$a2 = $this->redondeo($sueldo_mensual_total/30);
										$nueva_ley = $nueva_ley + 1; $aa3 = ($aa3+$a3); $a3=0;
										#echo "SELECT sueldo_integral, sueldo_total, sueldo_basico FROM cnmd15_devengado WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cedula_identidad=$cedula_identidad and (fecha_desde<='$fecha_busqueda' and fecha_hasta>='$fecha_busqueda');".$a2.' ---- '. $salario_1.'----'. $salario_2.'<br />';
										if ($nueva_ley == 1){$salario_1=$a2;}
										if ($nueva_ley == 2){$salario_2=$a2;}


										#echo $k.'---'.$j.'---'.$dia_egreso.'<br />';

									if(($k<=2011) || ($k==2012 && $j<=4) || ($k==2012 && $j==4 && $dia_ingreso_actual <= 7)){ # ley 2
										//echo "aqui le vieja ".$k.'<br />';
										if ($nueva_ley == 1 && $salario_1>=$a2){$a2 = $salario_1;}
										if ($nueva_ley == 1 && $salario_2>=$a2){$a2 = $salario_2;}

										if ($nueva_ley == 1){$a3 = $aa3; $aa3 = 0; $nueva_ley = 0;  $sueldo_diario_actual = $a2; $salario_1 = 0; $salario_2 = 0;}
										if ($k == 2012 and $j == 4){
											$hasta = $k."-".$this->zero($j)."-".$this->zero(7+$dia_hasta_feb);
											$dia_ingreso_actual = 6;
											$mes_ingreso_actual = 5;
											$ano_ingreso_actual = 2012;

											$hasta = $k."-".$this->zero($j+1)."-".$this->zero($dia_ingreso_actual+$dia_hasta_feb);

										}
									}else{
										//echo "string";
										if ($nueva_ley == 3 && $salario_1>=$a2){$a2 = $salario_1;}
										if ($nueva_ley == 3 && $salario_2>=$a2){$a2 = $salario_2; }

										if ($nueva_ley == 3){$a3 = $aa3; $aa3 = 0; $nueva_ley = 0;  $sueldo_diario_actual = $a2; $salario_1 = 0; $salario_2 = 0;}


									}


										$a4 = $this->redondeo(($a2*$a3));
										$a5 = ($a5+$a4);
										$a7 = (($a7+$a4+$a9)-$a6);
										$a9 = $this->redondeo(((($a7/100)*$a8)/12));
										$a10 = ($a10+$a9);
										$anticipo_actual = ($anticipo_actual+$a6);
										$dias_actual = ($dias_actual+$a3);
										$monto_antig_actual = ($monto_antig_actual+$a4);
										$interes_actual = ($interes_actual+$a9);

									$dato_intereses = ($dato_intereses+1);
									$valores3[] = " ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_intereses."', '".$desde."', '".$hasta."', '".$a1."','".$a2."','".$a3."','".$a4."','".$a5."','".$a6."','".$a7."','".$a8."','".$a9."','".$a10."')";

									# Aca Ultima vuelta de si termina mes de Enero
									if ($k == $ano_egreso - 1 and $j == 12 and $mes_egreso == 1 and $motivo_retiro != 0){
										echo "Enero";
										# Suma las garantias de prestaciones
										#echo "aqui";
										$fecha_busqueda = ($k+1)."-".(1)."-".$dia_ingreso_actual;
										#echo "SELECT sueldo_integral, sueldo_total, sueldo_basico FROM cnmd15_devengado WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cedula_identidad=$cedula_identidad and (fecha_desde<='$fecha_busqueda' and fecha_hasta>='$fecha_busqueda');";
										$dev=$this->Cnmd01->execute("SELECT sueldo_integral, sueldo_total, sueldo_basico FROM cnmd15_devengado WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cedula_identidad=$cedula_identidad and (fecha_desde<='$fecha_busqueda' and fecha_hasta>='$fecha_busqueda');");
										foreach($dev as $datosv6){
										$sueldo_diario_basico1   = $this->redondeo(($datosv6[0]['sueldo_basico']/30));
										$sueldo_diario_integral1 = $this->redondeo(($datosv6[0]['sueldo_integral']/30));
										$sueldo_diario_total1    = $this->redondeo(($datosv6[0]['sueldo_total']/30));
										$sueldo_mensual_basico   = $datosv6[0]['sueldo_basico'];
										$sueldo_mensual_integral = $datosv6[0]['sueldo_integral'];
										$sueldo_mensual_total    = $datosv6[0]['sueldo_total'];

										#echo $sueldo_mensual_total.'<br/>';
										}

										$a1 = $sueldo_mensual_total;
										$a2 = $this->redondeo($sueldo_mensual_total/30);

										$nueva_ley = 3;
										if ($nueva_ley == 3 && $salario_1>=$a2){$a2 = $salario_1;}
										if ($nueva_ley == 3 && $salario_2>=$a2){$a2 = $salario_2;}

										if ($nueva_ley == 3){$a3 = $aa3; $aa3 = 0; $nueva_ley = 0;  $sueldo_diario_actual = $a2; $salario_1 = 0; $salario_2 = 0;}

										#Fin SUma

										$mes_encontrado = $this->fecha_str(''.(1));
										$mes_busqueda = 'tasa_'.$mes_encontrado;
										$new_ywar = $k + 1;
										$tasa = $this->Cnmd01->execute("SELECT $mes_busqueda FROM cnmd15_tasa_interes WHERE ano=$new_ywar LIMIT 1;");

										if(!empty($tasa)){$a8 = $tasa[0][0]["$mes_busqueda"];}else{ $a8 = 0;}



										#$a3 = 0;
										$dato_intereses = ($dato_intereses+1);
										$desde = ($k+1)."-".$this->zero($mes_egreso)."-".$this->zero($dia_ingreso_actual + 1);
										$hasta = ($k+1)."-".$this->zero(1)."-".$this->zero($dia_egreso+$dia_hasta_feb);


										$fo = $ano_egreso."-".$mes_egreso."-".$dia_egreso;
		                				$fs = $ano_ingreso_actual."-".$mes_ingreso_actual."-".$dia_ingreso_actual;
		                				$sql_antiguedad = $this->Cnmd01->execute("select devolver_edad('".$fo."', '".$fs."', 'ANO') as aaa,devolver_edad('".$fo."', '".$fs."', 'MES') as mmm,devolver_edad('".$fo."', '".$fs."', 'DIA') as ddd");
										$ddd = $sql_antiguedad[0][0]['ddd'];
		                				$mmm = $sql_antiguedad[0][0]['mmm'];
		                				$aaa = $sql_antiguedad[0][0]['aaa'];


										$segundos=strtotime($hasta) - strtotime($desde);
										$diferencia_dias=intval($segundos/60/60/24);
										$a3_new = round((5 * $diferencia_dias) / 30, 1);


										$a4 = $this->redondeo(($a2*($a3+$a3_new)));
										$a5 = ($a5+$a4);
										$a7 = (($a7+$a3_new+$a9)-$a6);
										$a9 = $this->redondeo(((($a7/100)*$a8)/12));
										$a10 = ($a10+$a9);
										$anticipo_actual = ($anticipo_actual+$a6);
										$dias_actual = ($dias_actual+$a3 + $a3_new);
										$monto_antig_actual = ($monto_antig_actual+$a4);
										$interes_actual = ($interes_actual+$a9);
										$a3_new = $a3_new + $a3;

										$sql_sdsa = "INSERT INTO cnmd15_datos_intereses (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, consecutivo, desde, hasta, salario_mensual, salario_diario, dias_antiguedad, monto_antiguedad, prestaciones_acumuladas, anticipo, capital, tasa, interes, intereses_acumulados) VALUES "." ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_intereses."', '".$desde."', '".$hasta."', '".$a1."','".$a2."','".$a3_new."','".$a4."','".$a5."','".$a6."','".$a7."','".$a8."','".$a9."','".$a10."')";

										#echo $sql_sdsa;
									}
									# Aca Ultima vuelta Egreso

									if ($k == $ano_egreso and $j + 1 == $mes_egreso and $motivo_retiro != 0 ){

										//echo "Ultima Vuelta Egreso";
										# Suma las garantias de prestaciones
										#echo "SELECT sueldo_integral, sueldo_total, sueldo_basico FROM cnmd15_devengado WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cedula_identidad=$cedula_identidad and (fecha_desde<='$fecha_busqueda' and fecha_hasta>='$fecha_busqueda');";
										$fecha_busqueda = $k."-".($j+1)."-".$dia_ingreso_actual;
										#echo "SELECT sueldo_integral, sueldo_total, sueldo_basico FROM cnmd15_devengado WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cedula_identidad=$cedula_identidad and (fecha_desde<='$fecha_busqueda' and fecha_hasta>='$fecha_busqueda');";
										$dev=$this->Cnmd01->execute("SELECT sueldo_integral, sueldo_total, sueldo_basico FROM cnmd15_devengado WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cedula_identidad=$cedula_identidad and (fecha_desde<='$fecha_busqueda' and fecha_hasta>='$fecha_busqueda');");
										foreach($dev as $datosv6){
										$sueldo_diario_basico1   = $this->redondeo(($datosv6[0]['sueldo_basico']/30));
										$sueldo_diario_integral1 = $this->redondeo(($datosv6[0]['sueldo_integral']/30));
										$sueldo_diario_total1    = $this->redondeo(($datosv6[0]['sueldo_total']/30));
										$sueldo_mensual_basico   = $datosv6[0]['sueldo_basico'];
										$sueldo_mensual_integral = $datosv6[0]['sueldo_integral'];
										$sueldo_mensual_total    = $datosv6[0]['sueldo_total'];

										#echo $sueldo_mensual_total.'<br/>';
										}

										$a1 = $sueldo_mensual_total;
										$a2 = $this->redondeo($sueldo_mensual_total/30);

										$nueva_ley = 3;
										if ($nueva_ley == 3 && $salario_1>=$a2){$a2 = $salario_1;}
										if ($nueva_ley == 3 && $salario_2>=$a2){$a2 = $salario_2;}

										if ($nueva_ley == 3){$a3 = $aa3; $aa3 = 0; $nueva_ley = 0;  $sueldo_diario_actual = $a2; $salario_1 = 0; $salario_2 = 0;}

										#Fin SUma

										$mes_encontrado = $this->fecha_str(''.$j+1);
										$mes_busqueda = 'tasa_'.$mes_encontrado;

										$tasa = $this->Cnmd01->execute("SELECT $mes_busqueda FROM cnmd15_tasa_interes WHERE ano=$k LIMIT 1;");

										if(!empty($tasa)){$a8 = $tasa[0][0]["$mes_busqueda"];}else{ $a8 = 0;}



										#$a3 = 0;
										$dato_intereses = ($dato_intereses+1);
										$desde = $k."-".$this->zero($mes_egreso)."-".$this->zero($dia_ingreso_actual + 1);
										$hasta = $k."-".$this->zero($j+1)."-".$this->zero($dia_egreso+$dia_hasta_feb);


										$fo = $ano_egreso."-".$mes_egreso."-".$dia_egreso;
		                				$fs = $ano_ingreso."-".$mes_ingreso."-".$dia_ingreso;
		                				$sql_antiguedad = $this->Cnmd01->execute("select devolver_edad('".$fo."', '".$fs."', 'ANO') as aaa,devolver_edad('".$fo."', '".$fs."', 'MES') as mmm,devolver_edad('".$fo."', '".$fs."', 'DIA') as ddd");
										$ddd = $sql_antiguedad[0][0]['ddd'];
		                				$mmm = $sql_antiguedad[0][0]['mmm'];
		                				$aaa = $sql_antiguedad[0][0]['aaa'];


										$segundos=strtotime($hasta) - strtotime($desde);
										$diferencia_dias=intval($segundos/60/60/24);
										$a3_new = round((5 * $diferencia_dias) / 30, 1);
 										if ($mmm >= 6 and $aaa == 1){
 											$a3_new = $a3_new + 2;

 										}
 										#echo $desde.' - '.$diferencia_dias;
										$a4 = $this->redondeo(($a2*($a3+$a3_new)));
										$a5 = ($a5+$a4);
										$a7 = (($a7+$a3_new+$a9)-$a6);
										$a9 = $this->redondeo(((($a7/100)*$a8)/12));
										$a10 = ($a10+$a9);
										$anticipo_actual = ($anticipo_actual+$a6);

										$monto_antig_actual = ($monto_antig_actual+$a4);
										$interes_actual = ($interes_actual+$a9);
										$a3_new = $a3_new + $a3;
										$dias_actual = ($dias_actual + $a3_new);
										$sql_sdsa = "INSERT INTO cnmd15_datos_intereses (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, consecutivo, desde, hasta, salario_mensual, salario_diario, dias_antiguedad, monto_antiguedad, prestaciones_acumuladas, anticipo, capital, tasa, interes, intereses_acumulados) VALUES "." ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_intereses."', '".$desde."', '".$hasta."', '".$a1."','".$a2."','".$a3_new."','".$a4."','".$a5."','".$a6."','".$a7."','".$a8."','".$a9."','".$a10."')";
										//echo $dias_actual;
										break;
										#echo $sql_sdsa;

									} elseif(($k == $ano_egreso and $j + 1 == $mes_egreso and $motivo_retiro == 0)) { # Ultima Vuelta Activo
										//echo "Ultima Vuelta Activo";
										#echo "aqui";
										$dato_intereses = ($dato_intereses+1);
										$desde = $k."-".$this->zero($mes_egreso)."-".$this->zero($dia_ingreso_actual + 1);
										$hasta = $k."-".$this->zero($j+1)."-".$this->zero($dia_egreso+$dia_hasta_feb);

										$sql_sdsa = "INSERT INTO cnmd15_datos_intereses (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, consecutivo, desde, hasta, salario_mensual, salario_diario, dias_antiguedad, monto_antiguedad, prestaciones_acumuladas, anticipo, capital, tasa, interes, intereses_acumulados) VALUES "." ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_intereses."', '".$desde."', '".$hasta."', '".$a1."','".$a2."','".$a3."','".$a4."','".$a5."','".$a6."','".$a7."','".$a8."','".$a9."','".$a10."')";

									}
					  		}
						}

					}
						$sql_insert_comp3 .= " ".implode(',', $valores3).";";
						$this->Cnmd01->execute($sql_insert_comp3);
						$this->Cnmd01->execute($sql_sdsa);

						//print_r($sql_insert_comp3);



				// GARANTÍA DE PRESTACIONES SOCIALES ART. 142 LIT. A y B

						$dias_antiguedad_adicional = 0;

                		$fo = $ano_egreso."-".$mes_egreso."-".$dia_egreso;
                		$fs = $ano_ingreso_actual."-".$mes_ingreso_actual."-".$dia_ingreso_actual;
                		$sql_antiguedad2 = $this->Cnmd01->execute("select devolver_edad('".$fo."', '".$fs."', 'ANO') as aaa,devolver_edad('".$fo."', '".$fs."', 'MES') as mmm,devolver_edad('".$fo."', '".$fs."', 'DIA') as ddd");
						$ddd = $sql_antiguedad2[0][0]['ddd'];
                		$mmm = $sql_antiguedad2[0][0]['mmm'];
                		$aaa = $sql_antiguedad2[0][0]['aaa'];

                		#Acá Sumos dos dias si tiene mas de 1 año y 6 meses
                		if ($dias_adicional and $aaa == 1 and $mmm >=6){
                			$dias_adicional = $dias_adicional + 2;
                		}
                		#fin
						$tservrn = "A=$aaa   M=$mmm   D=$ddd   D.Adic.=$dias_adicional";


						if($aaa>=1 && $ddd>=30){$dias_antiguedad_adicional = (($aaa*60)+(($mmm+1)*5)+$dias_adicional);}else
						if ($aaa>=1 && $ddd<30) {$dias_antiguedad_adicional = (($aaa*60)+($mmm*5)+$dias_adicional);}else
						if($aaa==0 && $ddd>=30){$dias_antiguedad_adicional = (($mmm+1)*5);}else
						if($aaa==0 && $ddd<30){$dias_antiguedad_adicional = ($mmm*5);}


						// DIFERENCIA

						if($dias_antiguedad_adicional>$dias_actual){
							$diferencia = ($dias_antiguedad_adicional-$dias_actual);
							$monto_antig_actual = $this->redondeo(($monto_antig_actual+($diferencia*$a2)));
						    $dias_actual = $dias_antiguedad_adicional;
							}

							$sueldo_diario_actual = 0;

					// GARANTÍA DE PRESTACIONES SOCIALES ART. 142 LIT. C


							    $fo = $ano_egreso."-".$mes_egreso."-".$dia_egreso;
							    if ($ano_ingreso < 1997){
							    	$fs = '1997'."-".'06'."-".'19';
							    } else {
							    	$fs = $ano_ingreso."-".$mes_ingreso."-".$dia_ingreso;
							    }

                				$sql_antiguedad = $this->Cnmd01->execute("select devolver_edad('".$fo."', '".$fs."', 'ANO') as aaa,devolver_edad('".$fo."', '".$fs."', 'MES') as mmm,devolver_edad('".$fo."', '".$fs."', 'DIA') as ddd");
								$ddd = $sql_antiguedad[0][0]['ddd'];
                				$mmm = $sql_antiguedad[0][0]['mmm'];
                				$aaa = $sql_antiguedad[0][0]['aaa'];


							$dias_lit_c=0;
							if($aaa>=1 && $mmm>6){$dias_lit_c=(($aaa*30)+30);}else
							if($aaa>=1 && $mmm==6 && $ddd>0){$dias_lit_c=(($aaa*30)+30);}else
							if($aaa>=1 && $mmm==6 && $ddd==0){$dias_lit_c=(($aaa*30)+($mmm*2.5));}else
							if($aaa>=1 && $mmm<6 && $ddd>=30){$dias_lit_c=(($aaa*30)+(($mmm+1)*2.5));}else
							if($aaa>=1 && $mmm<6 && $ddd<30){$dias_lit_c=(($aaa*30)+($mmm*2.5));}else

							if($aaa==0 && $mmm>6){$dias_lit_c=30;}else
							if($aaa==0 && $mmm==6 && $ddd>0){$dias_lit_c=30;}else
							if($aaa==0 && $mmm==6 && $ddd==0){$dias_lit_c=($mmm*2.5);}else
							if($aaa==0 && $mmm>=3 && $mmm<6){$dias_lit_c=10;}

			$monto_antig_lit_c = $this->redondeo(($sueldo_diario_total1*$dias_lit_c));

			}


			#$total_ayb_int   = ($monto_antig_actual+$a10);
			$total_ayb_int   = ($monto_antig_actual);
			$total_ayb_int_9 = $this->Formato1($total_ayb_int);
			$compara_lit_c_9 = $this->Formato1($monto_antig_lit_c);

//echo " Total ayb+intereses  $total_ayb_int_9   Literal C  $compara_lit_c_9  <br>";

			$compara_interes = $this->Formato1($a10); $compara_interes = $this->Formato2($compara_interes);
            $compara_lit_ayb = $this->Formato1($monto_antig_actual); $compara_lit_ayb = $this->Formato2($compara_lit_ayb);
            $compara_lit_c   = $this->Formato1($monto_antig_lit_c); $compara_lit_c   = $this->Formato2($compara_lit_c);
            $compara_ante    = $this->Formato1($monto_antig_anterior); $compara_ante   = $this->Formato2($compara_ante);
			$total_ayb_int_1 = $this->Formato1($total_ayb_int); $total_ayb_int_1   = $this->Formato2($total_ayb_int_1);

			$observ_ley_ante = "INDEMNIZACION POR ANTIGUEDAD :  Dias = $dias_anterior  Salario Diario =  $sueldo_diario_anterior Monto =  $compara_ante      (Tiempo Serv.VR: $tservrv)";
			#$observ_lit_ayb  = "GARANTÍA DE PRESTACIONES SOCIALES ART. 142 LIT. A y B :  Dias = $dias_actual  Salario Diario =  $sueldo_diario_actual  Monto =  $compara_lit_ayb  + Interes  $compara_interes  = $total_ayb_int_1"
			$observ_lit_ayb  = "GARANTÍA DE PRESTACIONES SOCIALES ART. 142 LIT. A y B :  Dias = $dias_actual  Salario Diario =  $sueldo_diario_actual Monto =  $compara_lit_ayb";
			$observ_lit_c    = "GARANTÍA DE PRESTACIONES SOCIALES ART. 142 LIT. C ......:  Dias = $dias_lit_c  Salario Diario =  $sueldo_diario_total  Monto =  $compara_lit_c   (T.Serv.NR: $tservrn)";

$sql_update = "UPDATE cnmd15_datos_personales SET observacion_ley_anterior='$observ_ley_ante', observacion_lit_ayb='$observ_lit_ayb', observacion_lit_c='$observ_lit_c'  WHERE ".$this->SQLCA()." and cod_tipo_nomina=$cod_tipo_nomina and cod_cargo=$codigo_cargo and cod_ficha=$codigo_ficha and cedula_identidad=$ced_identidad";
	   $sw1 = $this->cnmd15_datos_personales->execute($sql_update);



				if ($compara_lit_c_9<($total_ayb_int_9)){
							if($dias_transf!=0){
								$dato_presta = ($dato_presta+1);
								$concepto_transf = 'COMPENSACION POR TRANSFERENCIA - ART. 666'.$complementa_conc;
								$sql_insert_comp = "INSERT INTO cnmd15_datos_prestaciones (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, consecutivo, concepto, dias, salario_diario, monto)";
								$sql_insert_comp .= " VALUES ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_presta."','".$concepto_transf."','".$dias_transf."','".$sueldo_diario_integral_transf."','".$monto_transf."');";
								$this->Cnmd01->execute($sql_insert_comp);
						}

							if($dias_anterior!=0){
								$dato_presta = ($dato_presta+1);
								$concepto_antiguedad_anterior = 'INDEMNIZACION POR ANTIGUEDAD';
								$sql_insert_comp = "INSERT INTO cnmd15_datos_prestaciones (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, consecutivo, concepto, dias, salario_diario, monto)";
								$sql_insert_comp .= " VALUES ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_presta."','".$concepto_antiguedad_anterior."','".$dias_anterior."','".$sueldo_diario_anterior."','".$monto_antig_anterior."');";
								$this->Cnmd01->execute($sql_insert_comp);
								}

							if($dias_actual!=0){
								$dato_presta = ($dato_presta+1);
								$monto_antig_lit_c = 0;
								$concepto_antiguedad_actual = 'GARANTÍA DE PRESTACIONES SOCIALES ART. 142 LIT. A y B';
								$sql_insert_comp = "INSERT INTO cnmd15_datos_prestaciones (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, consecutivo, concepto, dias, salario_diario, monto)";
								$sql_insert_comp .= " VALUES ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_presta."','".$concepto_antiguedad_actual."','".$dias_actual."','".$sueldo_diario_actual."','".$monto_antig_actual."');";
								$this->Cnmd01->execute($sql_insert_comp);
								}
					}else{
						if($dias_transf!=0){
								$dato_presta = ($dato_presta+1);
								$concepto_transf = 'COMPENSACION POR TRANSFERENCIA - ART. 666'.$complementa_conc;
								$sql_insert_comp = "INSERT INTO cnmd15_datos_prestaciones (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, consecutivo, concepto, dias, salario_diario, monto)";
								$sql_insert_comp .= " VALUES ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_presta."','".$concepto_transf."','".$dias_transf."','".$sueldo_diario_integral_transf."','".$monto_transf."');";
								$this->Cnmd01->execute($sql_insert_comp);
						}
						if($dias_anterior!=0){
								$dato_presta = ($dato_presta+1);
								$concepto_antiguedad_anterior = 'INDEMNIZACION POR ANTIGUEDAD';
								$sql_insert_comp = "INSERT INTO cnmd15_datos_prestaciones (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, consecutivo, concepto, dias, salario_diario, monto)";
								$sql_insert_comp .= " VALUES ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_presta."','".$concepto_antiguedad_anterior."','".$dias_anterior."','".$sueldo_diario_anterior."','".$monto_antig_anterior."');";
								$this->Cnmd01->execute($sql_insert_comp);
								}


							if($dias_lit_c!=0){
								$dato_presta = ($dato_presta+1);
								//$a10 = $interes_anterior = $interes_actual = $dias_anterior = $sueldo_diario_anterior = $dias_transf = $sueldo_diario_integral_transf = $monto_transf = $monto_antig_anterior = $intereses_transferencia = 0;
								$dias_actual=$dias_lit_c; $sueldo_diario_actual=$sueldo_diario_total; $monto_antig_actual=$monto_antig_lit_c;

								$concepto_antiguedad_lit_c = 'GARANTÍA DE PRESTACIONES SOCIALES ART. 142 LIT. C';
								$sql_insert_comp = "INSERT INTO cnmd15_datos_prestaciones (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, consecutivo, concepto, dias, salario_diario, monto)";
								$sql_insert_comp .= " VALUES ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_presta."','".$concepto_antiguedad_lit_c."','".$dias_lit_c."','".$sueldo_diario_total1."','".$monto_antig_lit_c."');";
								$this->Cnmd01->execute($sql_insert_comp);
								}
						}

													   		/** ************ RURALIDAD ************** */

				if ($ano!=0 || $ano_ingreso!=$ano_egreso){
					$dias_r = 7.5;
					$dias_rural_anterior = 0;
					$dias_rural_actual = 0;
					$sueldo_diario_integral_rural_anterior = 0;
					$monto = 0;
					$monto_rural_actual = 0;
					$total_monto_rural_actual = 0;
					$monto_rural_anterior = 0;
					$total_monto_rural_anterior = 0;
					$concepto_rural_anterior = '';
					$concepto_rural_actual = '';
					$ano1 = (($ano_egreso-$ano_ingreso)-1);
					$mes_rural=$mes_ingreso;
					$dia_rural=$dia_ingreso;
					for ($k=1;$k<=$ano1;$k++){
						$ano_rural = ($ano_ingreso+($k-1));
						if (($ano_rural==1997 && $ano_egreso>1997 && $mes_egreso>6) || ($ano_rural==1997 && $ano_egreso>1997 && $mes_egreso==6 && $dia_egreso>18)){
							$mes_rural=6;
							$dia_rural=19;						}
                        $d8=$this->Cnmd01->execute("SELECT count(*) as cantidad FROM cnmd15_parametro_cobro WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cedula_identidad=$cedula_identidad and cobro_ruralidad=1 and ano=$ano_rural");
                        $cantidad_rural = $d8[0][0]['cantidad'];
                        if($cantidad_rural!=0){
                        	if(($ano_rural<1997) || ($ano_rural==1997 && $mes_rural<6) || ($ano_rural==1997 && $mes_rural==6 && $dia_rural<=18)){
                        		$fecha_ruralidad = '1997-06-18';
								$d9=$this->Cnmd01->execute("SELECT sueldo_integral FROM cnmd15_devengado WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cedula_identidad=$cedula_identidad and (fecha_desde<='$fecha_ruralidad' and fecha_hasta>='$fecha_ruralidad')");
								foreach($d9 as $datos9){
									$sueldo_diario_integral_rural_anterior = $this->redondeo(($datos9[0]['sueldo_integral']/30));
								}
								$monto = ($dias_r*$sueldo_diario_integral_rural_anterior);
								$dias_rural_anterior += $dias_r;
								$monto_rural_anterior += $monto;

                        	}else{
								$monto = ($dias_r*$sueldo_diario_integral);
								$dias_rural_actual += $dias_r;
								$monto_rural_actual += $monto;
                        	}
                        }
					}

						if($monto_rural_anterior!=0){
								$monto_rural_anterior = $this->redondeo($monto_rural_anterior);
								$total_monto_rural_anterior = ($total_monto_rural_anterior+$monto_rural_anterior);
								$concepto_rural_anterior = 'RURALIDAD (RÉGIMEN ANTERIOR)';
								$dato_presta = ($dato_presta+1);
								$sql_insert_comp = "INSERT INTO cnmd15_datos_prestaciones (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, consecutivo, concepto, dias, salario_diario, monto)";
								$sql_insert_comp .= " VALUES ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_presta."','".$concepto_rural_anterior."','".$dias_rural_anterior."', '".$sueldo_diario_integral_rural_anterior."','".$monto_rural_anterior."');";
								$this->Cnmd01->execute($sql_insert_comp);
						}

						if($monto_rural_actual!=0){
								$monto_rural_actual  = $this->redondeo($monto_rural_actual);
								$total_monto_rural_actual = ($total_monto_rural_actual+$monto_rural_actual);
								$concepto_rural_actual = 'RURALIDAD (RÉGIMEN ACTUAL)';
								$dato_presta = ($dato_presta+1);
								$sql_insert_comp = "INSERT INTO cnmd15_datos_prestaciones (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, consecutivo, concepto, dias, salario_diario, monto)";
								$sql_insert_comp .= " VALUES ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_presta."','".$concepto_rural_actual."','".$dias_rural_actual."', '".$sueldo_diario_integral."','".$monto_rural_actual."');";
								$this->Cnmd01->execute($sql_insert_comp);
						}
				}


													   		/** ************ RURALIDAD FRACCIONADA ************** */

					$monto_rural_fracc_actual = 0;
					$monto_rural_fracc_anterior = 0;



												/** ****************** BONO VACACIONAL ******************* */

				$sueldo_diario_bono_vaca = 0;
				$descuento_bono_vaca = 0;
				$ano_bono_vaca = 0;
				$concepto_bono_vaca = '';
				$monto_bono_vaca = 0;
				$total_monto_bono_vaca = 0;

				if ($ano!=0 || $ano_ingreso!=$ano_egreso){

					if ($mes_egreso>=$mes_ingreso){ $ano1=$ano; } else { $ano1=($ano+1); }

				for ($k=0;$k<=($ano1);$k++){

                        $ano_bono_vaca=($ano_ingreso+$k);
						$fecha_bono_vaca = $ano_bono_vaca."-".$mes_ingreso."-".$dia_ingreso;
                        $dcpa=$this->Cnmd01->execute("SELECT count(*) as cantidad FROM cnmd15_parametro_cobro WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cedula_identidad=$cedula_identidad and cobro_bono_vacacional=2 and ano=$ano_bono_vaca");
                        $cantidad_bc = $dcpa[0][0]['cantidad'];
                        $dias_bono_vaca = 0;
                        $monto_bono_vacacional_completo = 0;

						if (($ano_bono_vaca == $ano_egreso && $mes_egreso < $mes_ingreso) || ($ano_bono_vaca == $ano_egreso && $mes_egreso == $mes_ingreso && $dia_egreso < $dia_ingreso) || ($ano_bono_vaca == $ano_ingreso)){ $cantidad_bc = 0; }

                        if($cantidad_bc!=0){
  							$execute_1 = $this->Cnmd01->execute("select a.dias, a.basico_bono_vac, a.descuento_bono_vac from v_cnmd15_bono_vaca a where ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and '$fecha_bono_vaca' >= a.fecha_desde_bono_vaca::date and '$fecha_bono_vaca' <= a.fecha_hasta_bono_vaca::date and '$k' >= a.desde_antiguedad and '$k' <= a.hasta_antiguedad limit 1;");

  							$dias_bono_vaca = isset($execute_1[0][0]["dias"])?$execute_1[0][0]["dias"]:0;
  							$basico    = $execute_1[0][0]["basico_bono_vac"];
  							$descuento = $execute_1[0][0]["descuento_bono_vac"];

							$concepto_bono_vaca = 'BONO VACACIONAL '.($ano_bono_vaca-1)." - ".$ano_bono_vaca;
							$monto_bono_vaca = $this->redondeo(($this->redondeo($dias_bono_vaca)*$sueldo_diario_integral));
							$sueldo_diario_bono_vaca = $sueldo_diario_integral;

									if($basico==1 && $descuento==1){
										$concepto_bono_vaca .= ' (BASICO + 2 %)';
										$monto_bono_vaca = ($this->redondeo($dias_bono_vaca)*$sueldo_diario_basico);
										$descuento_bono_vaca = ($monto_bono_vaca*0.02);
										$monto_bono_vaca = $this->redondeo(($monto_bono_vaca+$descuento_bono_vaca));
										$monto_bono_vacacional_completo = $monto_bono_vaca;
										$sueldo_diario_bono_vaca = $sueldo_diario_basico;
									}
									if($basico==2 && $descuento==1){
										$concepto_bono_vaca .= ' (SALARIO + 2 %)';
										$monto_bono_vaca = ($this->redondeo($dias_bono_vaca)*$sueldo_diario_integral);
										$descuento_bono_vaca = ($monto_bono_vaca*0.02);
										$monto_bono_vaca = $this->redondeo(($monto_bono_vaca+$descuento_bono_vaca));
										$monto_bono_vacacional_completo = $monto_bono_vaca;
										$sueldo_diario_bono_vaca = $sueldo_diario_integral;
									}
									if($basico==1 && $descuento==2){
										$concepto_bono_vaca .= ' (BASICO)';
										$monto_bono_vaca = $this->redondeo(($this->redondeo($dias_bono_vaca)*$sueldo_diario_basico));
										$monto_bono_vacacional_completo = $monto_bono_vaca;
										$sueldo_diario_bono_vaca = $sueldo_diario_basico;
									}
									if($basico==2 && $descuento==2){
										$concepto_bono_vaca .= ' (SALARIO)';
										$monto_bono_vaca = $this->redondeo(($this->redondeo($dias_bono_vaca)*$sueldo_diario_integral));
										$monto_bono_vacacional_completo = $monto_bono_vaca;
										$sueldo_diario_bono_vaca = $sueldo_diario_integral;
									}
                        	}
							if($dias_bono_vaca!=0){
								$dato_presta = ($dato_presta+1);
								$total_monto_bono_vaca = ($total_monto_bono_vaca+$monto_bono_vaca);
								$sql_insert_comp = "INSERT INTO cnmd15_datos_prestaciones (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, consecutivo, concepto, dias, salario_diario, monto)";
								$sql_insert_comp .= " VALUES ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_presta."','".$concepto_bono_vaca."','".$dias_bono_vaca."', '".$sueldo_diario_bono_vaca."','".$monto_bono_vaca."');";
								$this->Cnmd01->execute($sql_insert_comp);
							}
					} // fin for
				}






												/** ****************** BONO VACACIONAL FRACCIONADO ******************* */

				$sueldo_diario_bono_vaca_fracc = 0;
				$descuento_bono_vaca_fracc = 0;
				$dias_bono_vaca_fracc = 0;
				$concepto_bono_vaca_fracc = '';
				$monto_bono_vaca_fracc = 0;

				if ($mesv!=0){


							$fecha_bono_vaca_fracc = $ano_egreso."-".$mes_egreso."-".$dia_egreso;
							if ($ano==0){
								$execute_2 = $this->Cnmd01->execute("select a.dias, a.basico_bono_vac, a.descuento_bono_vac from v_cnmd15_bono_vaca a where ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and '$fecha_bono_vaca_fracc' >= a.fecha_desde_bono_vaca::date and '$fecha_bono_vaca_fracc' <= a.fecha_hasta_bono_vaca::date and '1' >= a.desde_antiguedad and '1' <= a.hasta_antiguedad limit 1;");
							}else {
  								$execute_2 = $this->Cnmd01->execute("select a.dias, a.basico_bono_vac, a.descuento_bono_vac from v_cnmd15_bono_vaca a where ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and '$fecha_bono_vaca_fracc' >= a.fecha_desde_bono_vaca::date and '$fecha_bono_vaca_fracc' <= a.fecha_hasta_bono_vaca::date and '$ano' >= a.desde_antiguedad and '$ano' <= a.hasta_antiguedad limit 1;");
							}
  							$dias_bono_vaca_fracc = isset($execute_2[0][0]["dias"])?$execute_2[0][0]["dias"]:0;
  							$basico    = $execute_2[0][0]["basico_bono_vac"];
  							$descuento = $execute_2[0][0]["descuento_bono_vac"];

							$dias_porcion_bv1 = 0;
							$monto_bono_vacacional_fraccionado = 0;
							$monto_bono_vacacional_fraccionado_f = 0;
							$dias_porcion_bv2 = '0.00';
							$dias_porcion_bv  = ($dias_bono_vaca_fracc/12);

							if ($dia_bv !=0 ){
							$dias_porcion_bv1 = ($dias_porcion_bv/30);
							$dias_porcion_bv2 = ($dias_porcion_bv1*($dia_bv));
							}
  							$dias_bono_vaca_fracc = (($dias_porcion_bv*$mesv)+$dias_porcion_bv2);

							$concepto_bono_vaca_fracc = 'BONO VACACIONAL FRACCIONADA '.$ano_egreso;
							$monto_bono_vaca_fracc = $this->redondeo(($this->redondeo($dias_bono_vaca_fracc)*$sueldo_diario_integral));
							$sueldo_diario_bono_vaca_fracc = $sueldo_diario_integral;
							$monto_bono_vacacional_fraccionado_f = $sueldo_diario_bono_vaca_fracc;

									if($basico==1 && $descuento==1){
										$concepto_bono_vaca_fracc .= ' (BASICO + 2 %)';
										$monto_bono_vaca_fracc = ($this->redondeo($dias_bono_vaca_fracc)*$sueldo_diario_basico);
										$descuento_bono_vaca_fracc = ($monto_bono_vaca_fracc*0.02);
										$monto_bono_vaca_fracc = $this->redondeo(($monto_bono_vaca_fracc+$descuento_bono_vaca_fracc));
										$monto_bono_vacacional_fraccionado = $monto_bono_vaca_fracc;
										$sueldo_diario_bono_vaca_fracc = $sueldo_diario_basico;
									}
									if($basico==2 && $descuento==1){
										$concepto_bono_vaca_fracc .= ' (SALARIO + 2 %)';
										$monto_bono_vaca_fracc = ($this->redondeo($dias_bono_vaca_fracc)*$sueldo_diario_integral);
										$descuento_bono_vaca_fracc = ($monto_bono_vaca_fracc*0.02);
										$monto_bono_vaca_fracc = $this->redondeo(($monto_bono_vaca_fracc+$descuento_bono_vaca_fracc));
										$monto_bono_vacacional_fraccionado = $monto_bono_vaca_fracc;
										$sueldo_diario_bono_vaca_fracc = $sueldo_diario_integral;
									}
									if($basico==1 && $descuento==2){
										$concepto_bono_vaca_fracc .= ' (BASICO)';
										$monto_bono_vaca_fracc = $this->redondeo(($this->redondeo($dias_bono_vaca_fracc)*$sueldo_diario_basico));
										$monto_bono_vacacional_fraccionado = $monto_bono_vaca_fracc;
										$sueldo_diario_bono_vaca_fracc = $sueldo_diario_basico;
									}
									if($basico==2 && $descuento==2){
										$concepto_bono_vaca_fracc .= ' (SALARIO)';
										$monto_bono_vaca_fracc = $this->redondeo(($this->redondeo($dias_bono_vaca_fracc)*$sueldo_diario_integral));
										$monto_bono_vacacional_fraccionado = $monto_bono_vaca_fracc;
										$sueldo_diario_bono_vaca_fracc = $sueldo_diario_integral;
									}
									if($dias_bono_vaca_fracc!=0){
										$dato_presta = ($dato_presta+1);
										$sql_insert_comp = "INSERT INTO cnmd15_datos_prestaciones (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, consecutivo, concepto, dias, salario_diario, monto)";
										$sql_insert_comp .= " VALUES ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_presta."','".$concepto_bono_vaca_fracc."','".$dias_bono_vaca_fracc."', '".$sueldo_diario_bono_vaca_fracc."','".$monto_bono_vaca_fracc."');";
										$this->Cnmd01->execute($sql_insert_comp);
									}
                        // }
				}






												/** ****************** DISFRUTE DE VACACION ******************* */

				$sueldo_diario_vaca = 0;
				$descuento_vaca = 0;
				$ano_vaca = 0;
				$concepto_vaca = '';
				$monto_vaca = 0;
				$total_monto_vaca = 0;

				if ($ano!=0 || $ano_ingreso!=$ano_egreso){

					if ($mes_egreso>=$mes_ingreso){ $ano1=$ano; } else { $ano1=($ano+1); }

				for ($k=0;$k<=($ano1);$k++){

						$ano_vaca=($ano_ingreso+$k);
						$fecha_vaca = $ano_vaca."-".$mes_ingreso."-".$dia_ingreso;
                        $dcpa=$this->Cnmd01->execute("SELECT count(*) as cantidad FROM cnmd15_parametro_cobro WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cedula_identidad=$cedula_identidad and disfruto_vacaciones=2 and ano=$ano_vaca");
                        $cantidad_vaca = $dcpa[0][0]['cantidad'];
                        $dias_vaca = 0;

						if (($ano_vaca == $ano_egreso && $mes_egreso < $mes_ingreso) || ($ano_vaca == $ano_egreso && $mes_egreso == $mes_ingreso && $dia_egreso < $dia_ingreso) || ($ano_vaca == $ano_ingreso)){ $cantidad_vaca = 0; }

                        if($cantidad_vaca!=0){
  							$execute_3 = $this->Cnmd01->execute("select a.dias, a.basico_vaca, a.descuento_vaca from v_cnmd15_disfrute_vaca a where ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and '$fecha_vaca' >= a.fecha_desde_vaca::date and '$fecha_vaca' <= a.fecha_hasta_vaca::date and '$k' >= a.desde_antiguedad and '$k' <= a.hasta_antiguedad limit 1;");
  							$dias_vaca = isset($execute_3[0][0]["dias"])?$execute_3[0][0]["dias"]:0;
  							$basico    = $execute_3[0][0]["basico_vaca"];
  							$descuento = $execute_3[0][0]["descuento_vaca"];

							$concepto_vaca = 'VACACIONES '.($ano_vaca-1)." - ".$ano_vaca;
							$monto_vaca = $this->redondeo(($this->redondeo($dias_vaca)*$sueldo_diario_integral));
							$sueldo_diario_vaca = $sueldo_diario_integral;

									if($basico==1 && $descuento==1){
										$concepto_vaca .= ' (BASICO + 2 %)';
										$monto_vaca = ($this->redondeo($dias_vaca)*$sueldo_diario_basico);
										$descuento_vaca = ($monto_vaca*0.02);
										$monto_vaca = $this->redondeo(($monto_vaca+$descuento_vaca));
										$sueldo_diario_vaca = $sueldo_diario_basico;
									}
									if($basico==2 && $descuento==1){
										$concepto_vaca .= ' (SALARIO + 2 %)';
										$monto_vaca = ($this->redondeo($dias_vaca)*$sueldo_diario_integral);
										$descuento_vaca = ($monto_vaca*0.02);
										$monto_vaca = $this->redondeo(($monto_vaca+$descuento_vaca));
										$sueldo_diario_vaca = $sueldo_diario_integral;
									}
									if($basico==1 && $descuento==2){
										$concepto_vaca .= ' (BASICO)';
										$monto_vaca = $this->redondeo(($this->redondeo($dias_vaca)*$sueldo_diario_basico));
										$sueldo_diario_vaca = $sueldo_diario_basico;
									}
									if($basico==2 && $descuento==2){
										$concepto_vaca .= ' (SALARIO)';
										$monto_vaca = $this->redondeo(($this->redondeo($dias_vaca)*$sueldo_diario_integral));
										$sueldo_diario_vaca = $sueldo_diario_integral;
									}
                        	}
							if($dias_vaca!=0){
								$dato_presta = ($dato_presta+1);
								$total_monto_vaca = ($total_monto_vaca+$monto_vaca);
								$sql_insert_comp = "INSERT INTO cnmd15_datos_prestaciones (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, consecutivo, concepto, dias, salario_diario, monto)";
								$sql_insert_comp .= " VALUES ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_presta."','".$concepto_vaca."','".$dias_vaca."', '".$sueldo_diario_vaca."','".$monto_vaca."');";
								$this->Cnmd01->execute($sql_insert_comp);
							}
					} // fin for
				}






												/** ****************** DISFRUTE DE VACACIONES FRACCIONADA ******************* */

				$sueldo_diario_vaca_fracc = 0;
				$descuento_vaca_fracc = 0;
				$dias_vaca_fracc = 0;
				$concepto_vaca_fracc = '';
				$monto_vaca_fracc = 0;

				if ($mesv!=0){


							$fecha_vaca_fracc = $ano_egreso."-".$mes_egreso."-".$dia_egreso;
							if ($ano==0){
								$execute_4 = $this->Cnmd01->execute("select a.dias, a.basico_vaca, a.descuento_vaca from v_cnmd15_disfrute_vaca a where ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and '$fecha_vaca_fracc' >= a.fecha_desde_vaca::date and '$fecha_vaca_fracc' <= a.fecha_hasta_vaca::date and '1' >= a.desde_antiguedad and '1' <= a.hasta_antiguedad limit 1;");
							}else{
								$execute_4 = $this->Cnmd01->execute("select a.dias, a.basico_vaca, a.descuento_vaca from v_cnmd15_disfrute_vaca a where ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and '$fecha_vaca_fracc' >= a.fecha_desde_vaca::date and '$fecha_vaca_fracc' <= a.fecha_hasta_vaca::date and '$ano' >= a.desde_antiguedad and '$ano' <= a.hasta_antiguedad limit 1;");
							}
  							$dias_vaca_fracc = isset($execute_4[0][0]["dias"])?$execute_4[0][0]["dias"]:0;
  							$basico    = $execute_4[0][0]["basico_vaca"];
  							$descuento = $execute_4[0][0]["descuento_vaca"];

  							$dias_porcion_vac1 = 0;
							$dias_porcion_vac2 = '0.00';
							$dias_porcion_vac  = ($dias_vaca_fracc/12);

							if ($dia_bv !=0 ){
							$dias_porcion_vac1 = ($dias_porcion_vac/30);
							$dias_porcion_vac2 = ($dias_porcion_vac1*($dia_bv));
							}
  							$dias_vaca_fracc = (($dias_porcion_vac*$mesv)+$dias_porcion_vac2);


							$concepto_vaca_fracc = 'VACACIONES FRACCIONADA '.$ano_egreso;
							$monto_vaca_fracc = $this->redondeo(($this->redondeo($dias_vaca_fracc)*$sueldo_diario_integral));
							$sueldo_diario_vaca_fracc = $sueldo_diario_integral;

									if($basico==1 && $descuento==1){
										$concepto_vaca_fracc .= ' (BASICO + 2 %)';
										$monto_vaca_fracc = ($this->redondeo($dias_vaca_fracc)*$sueldo_diario_basico);
										$descuento_vaca_fracc = ($monto_vaca_fracc*0.02);
										$monto_vaca_fracc = $this->redondeo(($monto_vaca_fracc+$descuento_vaca_fracc));
										$sueldo_diario_vaca_fracc = $sueldo_diario_basico;
									}
									if($basico==2 && $descuento==1){
										$concepto_vaca_fracc .= ' (SALARIO + 2 %)';
										$monto_vaca_fracc = ($this->redondeo($dias_vaca_fracc)*$sueldo_diario_integral);
										$descuento_vaca_fracc = ($monto_vaca_fracc*0.02);
										$monto_vaca_fracc = $this->redondeo(($monto_vaca_fracc+$descuento_vaca_fracc));
										$sueldo_diario_vaca_fracc = $sueldo_diario_integral;
									}
									if($basico==1 && $descuento==2){
										$concepto_vaca_fracc .= ' (BASICO)';
										$monto_vaca_fracc = $this->redondeo(($this->redondeo($dias_vaca_fracc)*$sueldo_diario_basico));
										$sueldo_diario_vaca_fracc = $sueldo_diario_basico;
									}
									if($basico==2 && $descuento==2){
										$concepto_vaca_fracc .= ' (SALARIO)';
										$monto_vaca_fracc = $this->redondeo(($this->redondeo($dias_vaca_fracc)*$sueldo_diario_integral));
										$sueldo_diario_vaca_fracc = $sueldo_diario_integral;
									}
									if($dias_vaca_fracc!=0){
										$dato_presta = ($dato_presta+1);
										$sql_insert_comp = "INSERT INTO cnmd15_datos_prestaciones (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, consecutivo, concepto, dias, salario_diario, monto)";
										$sql_insert_comp .= " VALUES ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_presta."','".$concepto_vaca_fracc."','".$dias_vaca_fracc."', '".$sueldo_diario_vaca_fracc."','".$monto_vaca_fracc."');";
										$this->Cnmd01->execute($sql_insert_comp);
									}
                        // }
				}







												/** ****************** AGUINALDO ******************* */

				$sueldo_diario_agui = 0;
				$descuento_agui = 0;
				$ano_agui = 0;
				$dias_agui = 0;
				$concepto_agui = '';
				$monto_agui = 0;
				$total_monto_agui = 0;
				if ($ano!=0 || $ano_ingreso!=$ano_egreso){
					$ano1 = ($ano_egreso-$ano_ingreso);
				for ($k=1;$k<=$ano1;$k++){
                        $ano_agui=($ano_ingreso+($k-1));
						$fecha_agui = $ano_agui."-12-31";

                        $dcpagui=$this->Cnmd01->execute("SELECT count(*) as cantidad FROM cnmd15_parametro_cobro WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cedula_identidad=$cedula_identidad and cobro_aguinaldo=2 and ano=$ano_agui");
                        $cantidad_agui = $dcpagui[0][0]['cantidad'];
                        if($cantidad_agui!=0){
  							$execute_5 = $this->Cnmd01->execute("select a.dias, a.basico_agui, a.descuento_agui from v_cnmd15_aguinaldo a where ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and '$fecha_agui' >= a.fecha_desde_aguinaldo::date and '$fecha_agui' <= a.fecha_hasta_aguinaldo::date and '$k' >= a.desde_antiguedad and '$k' <= a.hasta_antiguedad limit 1;");
  							$dias_agui = isset($execute_5[0][0]["dias"])?$execute_5[0][0]["dias"]:0;
  							$basico    = $execute_5[0][0]["basico_agui"];
  							$descuento = $execute_5[0][0]["descuento_agui"];

							$concepto_agui = 'AGUINALDO PERIODO '.$ano_agui;
							$monto_agui = $this->redondeo(($this->redondeo($dias_agui)*$sueldo_diario_integral ));
							$sueldo_diario_agui = $sueldo_diario_integral;

									if($basico==1 && $descuento==1){
										$concepto_agui .= ' (BASICO + 2 %)';
										$monto_agui = ($this->redondeo($dias_agui)*($sueldo_diario_basico + $monto_bono_vacacional_completo));
										$descuento_agui = ($monto_agui*0.02);
										$monto_agui = $this->redondeo(($monto_agui+$descuento_agui));
										$sueldo_diario_agui = $sueldo_diario_basico;
									}
									if($basico==2 && $descuento==1){
										$concepto_agui .= ' (SALARIO + 2 %)';
										$monto_agui = ($this->redondeo($dias_agui)*($sueldo_diario_integral + $monto_bono_vacacional_completo));
										$descuento_agui = ($monto_agui*0.02);
										$monto_agui = $this->redondeo(($monto_agui+$descuento_agui));
										$sueldo_diario_agui = $sueldo_diario_integral;
									}
									if($basico==1 && $descuento==2){
										$concepto_agui .= ' (BASICO)';
										$monto_agui = $this->redondeo(($this->redondeo($dias_agui)*($sueldo_diario_basico + $monto_bono_vacacional_completo)));
										$sueldo_diario_agui = $sueldo_diario_basico;
									}
									if($basico==2 && $descuento==2){
										$concepto_agui .= ' (SALARIO)';
										$monto_agui = $this->redondeo(($this->redondeo($dias_agui)*($sueldo_diario_integral + ($monto_bono_vacacional_completo / 365))));
										$sueldo_diario_agui = $sueldo_diario_integral;
									}
                        	}
							if($dias_agui!=0){
								$dato_presta = ($dato_presta+1);
								$total_monto_vaca = ($total_monto_vaca+$monto_agui);
								$sql_insert_comp = "INSERT INTO cnmd15_datos_prestaciones (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, consecutivo, concepto, dias, salario_diario, monto)";
								$sql_insert_comp .= " VALUES ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_presta."','".$concepto_agui."','".$dias_agui."', '".$sueldo_diario_agui."','".$monto_agui."');";
								$this->Cnmd01->execute($sql_insert_comp);
							}
					}
				}






												/** ****************** AGUINALDO FRACCIONADO ******************* */

				$sueldo_diario_agui_fracc = 0;
				$descuento_agui_fracc = 0;
				$dias_agui_fracc = 0;
				$concepto_agui_fracc = '';
				$monto_agui_fracc = 0;
				if ($mes_aguinaldo!=0){
						$fecha_agui_fracc = $ano_egreso."-".$mes_egreso."-".$dia_egreso;

                        $dcpaguif=$this->Cnmd01->execute("SELECT count(*) as cantidad FROM cnmd15_parametro_cobro WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cedula_identidad=$cedula_identidad and cobro_aguinaldo=2 and ano=$ano_egreso");
                        $cantidad_aguif = $dcpaguif[0][0]['cantidad'];
                        if($cantidad_aguif!=0){

							$execute_6 = $this->Cnmd01->execute("select a.dias, a.basico_agui, a.descuento_agui from v_cnmd15_aguinaldo a where ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and '$fecha_agui_fracc' >= a.fecha_desde_aguinaldo::date and '$fecha_agui_fracc' <= a.fecha_hasta_aguinaldo::date and '$ano' >= a.desde_antiguedad and '$ano' <= a.hasta_antiguedad limit 1;");
  							$dias_agui_fracc = isset($execute_6[0][0]["dias"])?$execute_6[0][0]["dias"]:0;
  							$basico    = $execute_6[0][0]["basico_agui"];
  							$descuento = $execute_6[0][0]["descuento_agui"];

							$dias_porcion1 = 0;
  							$dias_porcion2 = '0.00';
							$dias_porcion  = ($dias_agui_fracc/12);

  							if ($dia_ag !=0 ){
  							$dias_porcion1 = ($dias_porcion/30);
  							$dias_porcion2 = ($dias_porcion1*($dia_ag));
  							}
  							$dias_agui_fracc = (($dias_porcion*$mes_aguinaldo)+$dias_porcion2);

							$concepto_agui_fracc = 'AGUINALDO FRACCIONADO PERIODO '.$ano_egreso;
							$monto_agui_fracc = $this->redondeo(($this->redondeo($dias_agui_fracc)*$sueldo_diario_integral));
							$sueldo_diario_agui_fracc = $sueldo_diario_integral;

									if($basico==1 && $descuento==1){
										$concepto_agui_fracc .= ' (BASICO + 2 %)';
										$monto_agui_fracc = ($this->redondeo($dias_agui_fracc)*$sueldo_diario_basico);
										$descuento_agui_fracc = ($monto_agui_fracc*0.02);
										$monto_agui_fracc = $this->redondeo(($monto_agui_fracc+$descuento_agui_fracc));
										$sueldo_diario_agui_fracc = $sueldo_diario_basico;
									}
									if($basico==2 && $descuento==1){
										$concepto_agui_fracc .= ' (SALARIO + 2 %)';
										$monto_agui_fracc = ($this->redondeo($dias_agui_fracc)*$sueldo_diario_integral);
										$descuento_agui_fracc = ($monto_agui_fracc*0.02);
										$monto_agui_fracc = $this->redondeo(($monto_agui_fracc+$descuento_agui_fracc));
										$sueldo_diario_agui_fracc = $sueldo_diario_integral;
									}
									if($basico==1 && $descuento==2){
										$concepto_agui_fracc .= ' (BASICO)';
										$monto_agui_fracc = $this->redondeo(($this->redondeo($dias_agui_fracc)*($sueldo_diario_basico )));
										$sueldo_diario_agui_fracc = $sueldo_diario_basico;
									}
									# RECUERDA sueldo_diario_total 23-04-2014
									if($basico==2 && $descuento==2){
										$concepto_agui_fracc .= ' (SALARIO)';
										$monto_agui_fracc = $this->redondeo(($this->redondeo($dias_agui_fracc)*($sueldo_diario_integral + ($monto_bono_vacacional_fraccionado / 365)) ));
										$sueldo_diario_agui_fracc = $sueldo_diario_integral + ($monto_bono_vacacional_fraccionado / 365);
										//echo $sueldo_diario_integral;
										//echo $sueldo_diario_total;
										//echo "</br>";
										//echo $sueldo_diario_agui_fracc;
									}
									if($dias_agui_fracc!=0){
										$dato_presta = ($dato_presta+1);
										$sql_insert_comp = "INSERT INTO cnmd15_datos_prestaciones (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, consecutivo, concepto, dias, salario_diario, monto)";
										$sql_insert_comp .= " VALUES ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_presta."','".$concepto_agui_fracc."','".$dias_agui_fracc."', '".$sueldo_diario_agui_fracc."','".$monto_agui_fracc."');";
										$this->Cnmd01->execute($sql_insert_comp);
									}
                        }
				}

				$calculo_prestaciones = ($monto_transf+$monto_preaviso_trab+$monto_indem+$monto_indem_sust+$total_monto_rural_anterior+$total_monto_rural_actual+$monto_rural_fracc_anterior+$monto_rural_fracc_actual+$total_monto_bono_vaca+$monto_bono_vaca_fracc+$total_monto_vaca+$monto_vaca_fracc+$total_monto_agui+$monto_agui_fracc+$monto_antig_anterior+$monto_antig_actual);

				$dato_presta = ($dato_presta+1);
				$sql_insert_comp = "INSERT INTO cnmd15_datos_prestaciones (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, consecutivo, concepto, dias, salario_diario, monto)";
				$sql_insert_comp .= " VALUES ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_presta."', '', 0, 0, 0);";
				$this->Cnmd01->execute($sql_insert_comp);

				$dato_presta = ($dato_presta+1);
				$sql_insert_comp = "INSERT INTO cnmd15_datos_prestaciones (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, consecutivo, concepto, dias, salario_diario, monto)";
				$sql_insert_comp .= " VALUES ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_presta."', '          CÁLCULO PRESTACIONES', 0, 0,'".$calculo_prestaciones."');";
				$this->Cnmd01->execute($sql_insert_comp);

				// ADICIONALES:

				$sql_monto_adicional = $this->Cnmd01->execute("SELECT concepto, monto FROM cnmd15_adicionales WHERE ".$this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cedula_identidad=$cedula_identidad");
				$suma_monto_adicionales = 0;
				if(!empty($sql_monto_adicional)){
					$sql_insert_comp = "INSERT INTO cnmd15_datos_prestaciones (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, consecutivo, concepto, dias, salario_diario, monto) VALUES";
					foreach($sql_monto_adicional as $monto_adic){
						$suma_monto_adicionales += $monto_adic[0]["monto"];
						$dato_presta = ($dato_presta+1);
						$valores[] = " ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_presta."', '".$monto_adic[0]["concepto"]."', 0, 0,'".$monto_adic[0]["monto"]."')";
					}
					$sql_insert_comp .= " ".implode(',', $valores).";";
					$this->Cnmd01->execute($sql_insert_comp);
				}


				$monto_liquidacion = ($calculo_prestaciones+$suma_monto_adicionales);
				$dato_presta = ($dato_presta+1);
				$sql_insert_comp = "INSERT INTO cnmd15_datos_prestaciones (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, consecutivo, concepto, dias, salario_diario, monto)";
				$sql_insert_comp .= " VALUES ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_presta."', '          MONTO LIQUIDACIÓN', 0, 0,'".$monto_liquidacion."');";
				$this->Cnmd01->execute($sql_insert_comp);

				$monto_anticipos   = (($anticipo_anterior+$anticipo_actual)*-1);
				$dato_presta = ($dato_presta+1);
				$sql_insert_comp = "INSERT INTO cnmd15_datos_prestaciones (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, consecutivo, concepto, dias, salario_diario, monto)";
				$sql_insert_comp .= " VALUES ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_presta."', 'ANTICIPOS', 0, 0,'".$monto_anticipos."');";
				$this->Cnmd01->execute($sql_insert_comp);

				$total_liquidacion = ($monto_liquidacion+$monto_anticipos);
				$dato_presta = ($dato_presta+1);
				$sql_insert_comp = "INSERT INTO cnmd15_datos_prestaciones (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, consecutivo, concepto, dias, salario_diario, monto)";
				$sql_insert_comp .= " VALUES ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_presta."', '          TOTAL LIQUIDACIÓN', 0, 0,'".$total_liquidacion."');";
				$this->Cnmd01->execute($sql_insert_comp);


// INTERESES DE MORA

					$interes_mora = 0;
                	$fecha_egreso_mora_v=$fecha_egreso;
                    $fecha_egreso_mora = strtotime ('+7 day' , strtotime ($fecha_egreso_mora_v) ) ;
                    $fecha_egreso_mora = date ('Y-m-j' , $fecha_egreso_mora );

                	$fecha_actual_mora = date('Y-m-d');
                	$d5=$this->Cnmd01->execute("select devolver_edad('".$fecha_actual_mora."', '".$fecha_egreso_mora."', 'ANO') as ano_mora,devolver_edad('".$fecha_actual_mora."', '".$fecha_egreso_mora."', 'MES') as mes_mora,devolver_edad('".$fecha_actual_mora."', '".$fecha_egreso_mora."', 'DIA') as dia_mora");
					$dia_mora = $d5[0][0]['dia_mora'];
                	$mes_mora = $d5[0][0]['mes_mora'];
                	$ano_mora = $d5[0][0]['ano_mora'];

                    $dia_egreso_mora = (int) substr($fecha_egreso_mora, 8, 2);
                    $mes_egreso_mora = (int) substr($fecha_egreso_mora, 5, 2);
                    $ano_egreso_mora = substr($fecha_egreso_mora, 0,4);

                    $dia_actual_mora = (int) substr($fecha_actual_mora, 8, 2);
                    $mes_actual_mora = (int) substr($fecha_actual_mora, 5, 2);
                    $ano_actual_mora = substr($fecha_actual_mora, 0, 4);


                $sql_insert_mora = "INSERT INTO cnmd15_datos_intereses_mora (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, consecutivo, desde, hasta, dias, monto_prestaciones, tasa, monto_interes) VALUES ";

					// FOR AÑO:

						for ($k=$ano_egreso_mora;$k<=$ano_actual_mora;$k++){
							$hf=12;
							if($k==$ano_egreso_mora){$m1 = $mes_egreso_mora;}else{$m1 = 1;}

							if($k==$ano_actual_mora){$hf = $mes_actual_mora;}

							//if($k==$ano_actual_mora && $dia_actual_mora<$dia_egreso_mora){$hf = ($hf-2);}

							// FOR MES:

								for ($j=$m1;$j<=$hf;$j++){

										$fecha_busqueda = $k."-".$j."-".$dia_egreso_mora;

										// TASAS BANCO CENTRAL:
										$mes_encontrado = $this->fecha_str(''.$j);
										$mes_busqueda = 'tasa_'.$mes_encontrado;

										$tasa = $this->Cnmd01->execute("SELECT $mes_busqueda FROM cnmd15_tasa_interes WHERE ano=$k LIMIT 1;");
										if(!empty($tasa)){$a3 = $tasa[0][0]["$mes_busqueda"];}else{ $a3 = 0;}


                                       $resto=$k % 4;
                                       if ($resto==0){$dia_feb="29";}else{$dia_feb="28";}

                                       if ($j==1){$dia_h=31;}
                                       if ($j==2){$dia_h=$dia_feb;}
                                       if ($j==3){$dia_h=31;}
                                       if ($j==4){$dia_h=30;}
                                       if ($j==5){$dia_h=31;}
                                       if ($j==6){$dia_h=30;}
                                       if ($j==7){$dia_h=31;}
                                       if ($j==8){$dia_h=31;}
                                       if ($j==9){$dia_h=30;}
                                       if ($j==10){$dia_h=31;}
                                       if ($j==11){$dia_h=30;}
                                       if ($j==12){$dia_h=31;}

									   $desde = $k."-".$this->zero($j)."-01";
									   $hasta = $k."-".$this->zero($j)."-".$dia_h;

									   if ($j==2){$dia_h=$dia_feb;}

									   $a1 = 30;

                                       if($k==$ano_egreso_mora && $j==$mes_egreso_mora){
                                       	$desde = $k."-".$this->zero($j)."-".$dia_egreso_mora;
                                       	$a1 = (31-$dia_egreso_mora);
                                       }
                                       if($k==$ano_actual_mora && $j==$mes_actual_mora){
                                       	$hasta = $k."-".$this->zero($j)."-".$dia_actual_mora;
                                       	$a1 = $dia_actual_mora;
                                       }

										$a2=$total_liquidacion;
										$a4 = $this->redondeo(((($a2/100)*$a3)/12));
										if ($a1!=30){$a4=(($a4/30)*$a1);}
										$interes_mora = $this->redondeo(($interes_mora+$a4));

									$dato_intereses_mora = ($dato_intereses_mora+1);
									$valores4[] = " ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_intereses_mora."', '".$desde."', '".$hasta."', '".$a1."','".$a2."','".$a3."','".$a4."')";

						}

					}
						$sql_insert_mora .= " ".implode(',', $valores4).";";
						$this->Cnmd01->execute($sql_insert_mora);


				$intereses = ($interes_anterior+$interes_actual);
				if($intereses!=0){
					$dato_presta = ($dato_presta+1);
					$sql_insert_comp = "INSERT INTO cnmd15_datos_prestaciones (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, consecutivo, concepto, dias, salario_diario, monto)";
					$sql_insert_comp .= " VALUES ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_presta."', 'MAS (+) INTERESES', 0, 0,'".$intereses."');";
					$this->Cnmd01->execute($sql_insert_comp);
				}

				if($intereses_transferencia!=0){
					$dato_presta = ($dato_presta+1);
					$sql_insert_comp = "INSERT INTO cnmd15_datos_prestaciones (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, consecutivo, concepto, dias, salario_diario, monto)";
					$sql_insert_comp .= " VALUES ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_presta."', 'MAS (+) INTERESES BONO TRANSFERENCIA', 0, 0,'".$intereses_transferencia."');";
					$this->Cnmd01->execute($sql_insert_comp);
				}

				if($interes_mora!=0){
					$dato_presta = ($dato_presta+1);
					$sql_insert_comp = "INSERT INTO cnmd15_datos_prestaciones (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, consecutivo, concepto, dias, salario_diario, monto)";
					$sql_insert_comp .= " VALUES ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_presta."', 'MAS (+) INTERESES DE MORA', 0, 0,'".$interes_mora."');";
					$this->Cnmd01->execute($sql_insert_comp);
				}


				$totales = ($total_liquidacion+$intereses+$intereses_transferencia);
				$totales_monto_prestaciones = ($total_liquidacion+$intereses+$intereses_transferencia);
				$dato_presta = ($dato_presta+2);
				$sql_insert_comp = "INSERT INTO cnmd15_datos_prestaciones (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, consecutivo, concepto, dias, salario_diario, monto)";
				$sql_insert_comp .= " VALUES ('".$this->verifica_SS(1)."', '".$this->verifica_SS(2)."', '".$this->verifica_SS(3)."', '".$this->verifica_SS(4)."', '".$this->verifica_SS(5)."', '".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula_identidad."','".$dato_presta."', '          T O T A L E S', 0, 0,'".$totales."');";
				$this->Cnmd01->execute($sql_insert_comp);



          }//fin foreach datos1
		}


		return array(0 => $intereses, 1 => $totales + $calculo_interese_total);

         }//fin if status
         else{
			echo "<script> fun_msj('EL C&Oacute;DIGO DE LA N&Oacute;MINA NO FUE ENCONTRADO'); </script>";
			return array(0 => 0, 1 => 0);
         }
}//fin procesar


function calculo_intereses($var_m_transfe=null, $var_m_anticipo=null, $codi_nomina=null, $codi_cargo=null, $codi_ficha=null, $cedu_ide=null){

      $this->layout = "ajax";
      $cod_presi = $this->Session->read('SScodpresi');
	  $cod_entidad = $this->Session->read('SScodentidad');
	  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	  $cod_inst = $this->Session->read('SScodinst');
	  $cod_dep = $this->Session->read('SScoddep');
	  $modulo = $this->Session->read('Modulo');
	  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

	  $cod_tipo_nomina        =  $codi_nomina;
	  $cod_cargo              =  $codi_cargo;
      $cod_ficha              =  $codi_ficha;
	  $cedula                 =  $cedu_ide;


													/** CALCULO DEL */
												// BONO POR TRANSFERENCIA:


         	$d2 = $this->Cnmd01->execute("SELECT * FROM v_cnmd15_datos_personales_prestaciones WHERE ".$condicion." and cod_tipo_nomina=$cod_tipo_nomina and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cedula_identidad=$cedula");
			$monto_transf = 0;
			$ano_transf = 0;

		if($d2!=null){

            foreach($d2 as $datos1){ // inicio datos1
                $fecha_ingreso     = $datos1[0]['fecha_ingreso'];
               	$fecha_egreso      = $datos1[0]['fecha_egreso'];
                $dia               = $datos1[0]['dia'];
                $mes               = $datos1[0]['mes'];
                $ano               = $datos1[0]['ano'];
                $dia_ingreso       = $datos1[0]['dia_ingreso'];
                $mes_ingreso       = $datos1[0]['mes_ingreso'];
                $ano_ingreso       = $datos1[0]['ano_ingreso'];
                $dia_egreso        = $datos1[0]['dia_egreso'];
                $mes_egreso        = $datos1[0]['mes_egreso'];
                $ano_egreso        = $datos1[0]['ano_egreso'];
                $cod_cargo         = $datos1[0]['cod_cargo'];
                $cod_ficha         = $datos1[0]['cod_ficha'];
                $cedula_identidad  = $datos1[0]['cedula_identidad'];
                $motivo_retiro     = $datos1[0]['motivo_retiro'];
                $cumplio_preaviso  = $datos1[0]['cumplio_preaviso'];
            }

 	$d6=$this->Cnmd01->execute("SELECT sueldo_integral, sueldo_total, sueldo_basico FROM cnmd15_devengado WHERE ".$condicion." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cedula_identidad=$cedula and fecha_hasta>='$fecha_egreso'");
	foreach($d6 as $datos6){//busca sueldo
		$sueldo_diario_basico   = $this->redondeo(($datos6[0]['sueldo_basico']/30));
		$sueldo_diario_integral = $this->redondeo(($datos6[0]['sueldo_integral']/30));
		$sueldo_diario_total    = $this->redondeo(($datos6[0]['sueldo_total']/30));


		if(($ano_ingreso<1997) || ($ano_ingreso==1997 && $mes_ingreso<6) || ($ano_ingreso==1997 && $mes_ingreso==6 && $dia_ingreso<=18)){

				$datos_anticipo_bono = $this->cnmd15_anticipo_bono_transf->findAll($this->SQLCA().' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$cod_cargo.' and cod_ficha='.$cod_ficha.' and cedula_identidad='.$cedula_identidad);
						if(!empty($datos_anticipo_bono)){
							foreach($datos_anticipo_bono as $row_datos_antic){
								$monto_trans = $row_datos_antic['cnmd15_anticipo_bono_transf']['monto_bono'];
								$monto_antic = $row_datos_antic['cnmd15_anticipo_bono_transf']['monto_anticipo'];
								$deuda_bono_anticipo = ($monto_trans-$monto_antic);
								}
							}else{
								$monto_trans = 0;
								$monto_antic = 0;
								$deuda_bono_anticipo = 0;								}

                	$fecha_egreso_transf = '1997-06-18';
                	$d5=$this->Cnmd01->execute("select devolver_edad('".$fecha_egreso_transf."', '".$fecha_ingreso."', 'ANO') as ano_transf,devolver_edad('".$fecha_egreso_transf."', '".$fecha_ingreso."', 'MES') as mes_transf,devolver_edad('".$fecha_egreso_transf."', '".$fecha_ingreso."', 'DIA') as dia_transf");
					//echo "select devolver_edad('".$fecha_egreso_transf."', '".$fecha_ingreso."', 'ANO') as ano_transf,devolver_edad('".$fecha_egreso_transf."', '".$fecha_ingreso."', 'MES') as mes_transf,devolver_edad('".$fecha_egreso_transf."', '".$fecha_ingreso."', 'DIA') as dia_transf";
					$dia_transf = mascara($d5[0][0]['dia_transf'],2);
                	$mes_transf = mascara($d5[0][0]['mes_transf'],2);
                	$ano_transf = $d5[0][0]['ano_transf'];

			if($ano_transf!=0 && $deuda_bono_anticipo==0 && $monto_antic==0 || $ano_transf!=0 && $deuda_bono_anticipo!=0 && $monto_antic!=0 || $ano_transf!=0 && $deuda_bono_anticipo!=0 && $monto_antic==0){
                		$de=$this->Cnmd01->execute("SELECT sueldo_integral FROM cnmd15_devengado WHERE ".$condicion." and cod_tipo_nomina=".$cod_tipo_nomina." and cod_cargo=$cod_cargo and cod_ficha=$cod_ficha and cedula_identidad=$cedula and (fecha_desde<='$fecha_egreso_transf' and fecha_hasta>='$fecha_egreso_transf')");
						foreach($de as $datosde){
							$sueldo_diario_integral_transf = $this->redondeo(($datosde[0]['sueldo_integral']/30));
						}
						if($ano_transf>13){$ano_transf = 13;}
						if($sueldo_diario_integral_transf<0.5){$sueldo_diario_integral_transf = 0.5;}
						#if($sueldo_diario_integral_transf>10.0){$sueldo_diario_integral_transf = 10.0;}
						$dias_transf  = ($ano_transf*30);

						$monto_transf = $this->redondeo(($dias_transf*$sueldo_diario_integral_transf));
						if($deuda_bono_anticipo!=0){$monto_transf = $deuda_bono_anticipo;}
			} // if $ano_transfe!=0
		} // if año < 1997
	} // busca sueldo
}// busca al trabajador

							$dia_ingreso_actual = 19;
							$mes_ingreso_actual = 7;
							$ano_ingreso_actual = 1997;
							$i1 = $ano_ingreso;
							$a1 = $a3 =	$a4 = $a5 = 0;
							$a1 = $monto_transf;
							$a5 = $monto_transf;
							$m2 = 4;

					// FOR AÑO:

						for ($k=$ano_ingreso_actual;$k<=$ano_egreso;$k++){
							$hf=12;

							if($k==$ano_ingreso_actual){$m1 = $mes_ingreso_actual;}else{$m1 = 1;}

							if($k==$ano_egreso){$hf = ($mes_egreso-1);}

							// FOR MES:

								for ($j=$m1;$j<=$hf;$j++){

										if($ano_egreso==$k && $mes_egreso==$j && (($dia_egreso-$dia_ingreso_actual)!=30)){// COMPLETA CICLO.
										}else{

											if($m2!=0){$m2 = ($m2-1); }

										$fecha_busqueda = $k."-".$j."-".$dia_ingreso_actual;

										// TASAS BANCO CENTRAL:
										$mes_encontrado = $this->fecha_str(''.$j);
										$mes_busqueda = 'tasa_'.$mes_encontrado;

										$tasa = $this->Cnmd01->execute("SELECT $mes_busqueda FROM cnmd15_tasa_interes WHERE ano=$k LIMIT 1;");

										if(!empty($tasa)){$a2 = $tasa[0][0]["$mes_busqueda"];}else{ $a2 = 0;}

										$dia_feb=1;
										$dia_hasta_feb=0;

							            if ($dia_ingreso>=30){ $dia_feb=0; }
										if ($j==2 && $dia_ingreso==28){ $dia_feb=0; }
										if ($j==2 && $dia_ingreso==29){ $dia_feb=-1; }
										if ($j==2 && $dia_ingreso==30){ $dia_feb=-2; }
										if ($j==2 && $dia_ingreso==31){ $dia_feb=-3; }

										if ($j==1 && $dia_ingreso==29){ $dia_hasta_feb=-1; }
										if ($j==1 && $dia_ingreso==30){ $dia_hasta_feb=-2; }
										if ($j==1 && $dia_ingreso==31){ $dia_hasta_feb=-3; }

										if($k==$ano_ingreso_actual && $j==$m1){
											$desde = $k."-".$this->zero($j)."-".$this->zero($dia_ingreso_actual);
										}else{
											$desde = $k."-".$this->zero($j)."-".$this->zero($dia_ingreso_actual+$dia_feb);
										}

										if($j==12){
											$hasta = ($k+1)."-".$this->zero(1)."-".$this->zero($dia_ingreso_actual);
										}else{
											$hasta = $k."-".$this->zero($j+1)."-".$this->zero($dia_ingreso_actual+$dia_hasta_feb);
										}

										if($k==$ano_egreso && $j==($mes_egreso-1)){
											$hasta = $k."-".$this->zero($j+1)."-".$this->zero($dia_egreso);
										}else{
											$hasta = $k."-".$this->zero($j+1)."-".$this->zero($dia_ingreso_actual);
										}


								if($j>=12){$hasta = ($k+1)."-".$this->zero(1)."-".$this->zero($dia_ingreso_actual);}

											$a3 = $this->redondeo(((($a5/100)*$a2)/12));
											$a4 = ($a4+$a3);
											$a5 = ($a4+$a1);
								}

					  		}
						}

		return $a4;

} // fin funcion calculo_intereses



function _pr ($var) {
	echo "<pre>";
	print_r($var);
	echo "</pre>";
}//fin function pr

function devolver_pagina ($page,$limite) {
    if ($page > 1) {
			$offset = ($page - 1) * $limite;
	}else{
			$offset = 0;
	}
    return $offset;
}//fin funcion devolver_limite




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

function calcular_dia_habiles($fecha){

	$hoy = $fecha;

	$stop_dia = 0;

	while ($stop_dia < 5){

		#date_add($hoy, date_interval_create_from_date_string('1 days'));
		$hoy = date('Y-m-d', strtotime($hoy. ' + 1 days'));
		$fecha_actual = date('N', strtotime($hoy));
		if ($fecha_actual !=6 and $fecha_actual != 7) {
			$stop_dia++;
		}

		$fecha_actual = $hoy;
	}

	return $fecha_actual;
	}


}//fin class
?>
