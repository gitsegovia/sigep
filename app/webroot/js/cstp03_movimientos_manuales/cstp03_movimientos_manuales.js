function valida_cstp03_movimientos_manuales(){

   if(document.getElementById('ano_1').value==''){
	        fun_msj('El a&ntilde;o no puede estar vac&iacute;o');
			document.getElementById('ano_1').focus();
			return false;
	}if(document.getElementById('select_1').value==''){
	        fun_msj('Seleccione la entidad bancaria');
			document.getElementById('select_1').focus();
			return false;
	}if(document.getElementById('deno_entidad_bancaria').value==''){
	        fun_msj('La denominaci&oacute;n de la Entidad Bancaria no puede estar vac&iacute;a');
			document.getElementById('deno_entidad_bancaria').focus();
			return false;
	}if(document.getElementById('select_2').value==''){
	        fun_msj('Seleccione la sucursal bancaria');
			document.getElementById('select_2').focus();
			return false;
	}if(document.getElementById('deno_sucursal_bancaria').value==''){
	        fun_msj('La denominaci&oacute;n de la Sucursal Bancaria no puede estar vac&iacute;o');
			document.getElementById('deno_sucursal_bancaria').focus();
			return false;
	}if(document.getElementById('cuenta_bancaria').value==''){
	        fun_msj('Debe seleccionar el n&uacute;mero de la cuenta bancaria');
			document.getElementById('cuenta_bancaria').focus();
			return false;
	}

	if(isNaN(document.getElementById('cuenta_bancaria').value)){
	        fun_msj('Atenci&oacute;n revise el n&uacute;mero de cuenta bancaria, no hay registros');
			document.getElementById('cuenta_bancaria').focus();
			return false;
	}

	//--------------------tipo de documento-------------------
	if((document.getElementById('tipo_documento_1').checked==false) && (document.getElementById('tipo_documento_2').checked==false) && (document.getElementById('tipo_documento_3').checked==false) && (document.getElementById('tipo_documento_4').checked==false)){
		fun_msj('DEBE SELECCIONAR EL TIPO DE DOCUMENTO');
		return false;
	}

	if((document.getElementById('tipo_documento_1').checked==true) || (document.getElementById('tipo_documento_2').checked==true)){
		if((document.getElementById('colocacion_1').checked==false) && (document.getElementById('colocacion_2').checked==false)){
			fun_msj('DEBE INDICAR SI EL DOCUMENTO ES UNA COLOCACI&Oacute;N ?');
			return false;
		}

		if((document.getElementById('tipo_recurso_1').checked==false) && (document.getElementById('tipo_recurso_2').checked==false) && (document.getElementById('tipo_recurso_3').checked==false) && (document.getElementById('tipo_recurso_4').checked==false) && (document.getElementById('tipo_recurso_5').checked==false) && (document.getElementById('tipo_recurso_6').checked==false)){
			fun_msj('DEBE SELECCIONAR EL TIPO DE RECURSO');
			return false;
		}else{
			if(document.getElementById('select_5').value==''){
				fun_msj('seleccione la clasificaci&oacute;n de los recursos');
				return false;
			}
			if(document.getElementById('cod_tipo_recurso').value==''){
				fun_msj('Atenci&oacute;n debe seleccionar la clasificaci&oacute;n de los recursos');
				return false;
			}
		}
	}

	if(document.getElementById('tipo_documento_4').checked==true){
		if((document.getElementById('pagotransferencia_1').checked==false) && (document.getElementById('pagotransferencia_2').checked==false)){
			fun_msj('Por favor, Marque si el cheque paga orden pago de transferencia o no');
			return false;
		}
		if((document.getElementById('numero_automatico_1').checked==false) && (document.getElementById('numero_automatico_2').checked==false)){
			fun_msj('Por favor, Marque la opci&oacute;n para generar el n&uacute;mero de cheque autom&aacute;tico');
			return false;
		}
	}
	//--------------------------------------------------------

	if(document.getElementById('numero_documento').value==''){
	        fun_msj('Debe ingresar el n&uacute;mero del documento');
			document.getElementById('numero_documento').focus();
			return false;
	}if(document.getElementById('fecha_documento').value==''){
	        fun_msj('Debe ingresar la fecha del documento');
			document.getElementById('fecha_documento').focus();
			return false;
	}else if(verifica_cierre_ano_ejecucion('fecha_documento')==false){
		fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA DEL DOCUMENTO NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
		return false;
	}
	if(document.getElementById('beneficiario').value==''){
	        fun_msj('Debe ingresar el Beneficiario');
			document.getElementById('beneficiario').focus();
			return false;
	}if(document.getElementById('monto').value==''){
	        fun_msj('Ingrese el monto');
			document.getElementById('monto').focus();
			return false;
	}if(document.getElementById('concepto').value==''){
	        fun_msj('Debe ingresar el concepto del monto');
			document.getElementById('concepto').focus();
			return false;
	}

   if((document.getElementById('tipo_documento_3').checked==true) || (document.getElementById('tipo_documento_4').checked==true)){
		a = document.getElementById('monto').value;
		var str = a;
		for(i=0; i<a.length; i++){str = str.replace('.','');}//fin for
		str   = str.replace(',','.');
		var a = redondear(str,2);

		if(a > document.getElementById('disponibilidad').value){
	        fun_msj('El monto supera la disponibilidad de la cuenta bancaria');
			document.getElementById('monto').focus();
			return false;

		}
	}

//////////////////*******************************///////////////
	if(document.getElementById('tipo_documento_4').checked==true){
		if(document.getElementById('select_dependencias')){
			fun_msj('Atenci&oacute;n: no a procesado la orden de transferencia, revise por favor');
			return false;
		}
	}
/////////////////*******************************/////////////////

	/* Activar para cuentas contables */

	if(document.getElementById('tipo_cuenta').value==1){
		cant = document.getElementById('cant_registros_ingresos').value;
		monto= retornar_valor_calculo(document.getElementById('monto').value);
		var i;
		var monto_ingresos=0;
		for(i=0; i<cant; i++){
			valor_calculo=retornar_valor_calculo(document.getElementById('monto_tipo_ingreso_'+i).value);
			if(valor_calculo!=''){
				monto_ingresos = eval(monto_ingresos) + eval(retornar_valor_calculo(document.getElementById('monto_tipo_ingreso_'+i).value));
			}
		}
		monto_ingresos_2 = redondear(monto_ingresos,2);
		if(monto_ingresos_2!=monto){
			fun_msj('Atenci&oacute;n: la sumatoria de las cuentas '+monto_ingresos_2+' no coincide con el monto del documento '+monto);
			document.getElementById('monto').focus();
			return false;
		}
	}

	document.getElementById('select_1').value='';
	document.getElementById('cod_entidad_bancaria').value='';
	document.getElementById('deno_entidad_bancaria').value='';

	document.getElementById('select_2').value='';
	document.getElementById('cod_sucursal_bancaria').value='';
	document.getElementById('deno_sucursal_bancaria').value='';

	document.getElementById('cuenta_bancaria').value='';
	document.getElementById('tipo_documento_1').checked=false;
	document.getElementById('tipo_documento_2').checked=false;
	document.getElementById('tipo_documento_3').checked=false;
	document.getElementById('tipo_documento_4').checked=false;
	document.getElementById('numero_automatico_1').checked=false;
	document.getElementById('numero_automatico_2').checked=false;
	document.getElementById('numero_documento').value='';
	document.getElementById('fecha_documento').value='';
	document.getElementById('beneficiario').value='';
	document.getElementById('monto').value='';
	document.getElementById('concepto').value='';
	document.getElementById('td_disponibilidad').innerHTML='';
	document.getElementById('tipo_recurso_1').checked=false;
	document.getElementById('tipo_recurso_2').checked=false;
	document.getElementById('tipo_recurso_3').checked=false;
	document.getElementById('tipo_recurso_4').checked=false;
	document.getElementById('tipo_recurso_5').checked=false;
	document.getElementById('tipo_recurso_6').checked=false;
	document.getElementById('cod_tipo_recurso').value='';
	document.getElementById('deno_tipo_recurso').value='';
	document.getElementById('colocacion_1').checked=false;
	document.getElementById('colocacion_2').checked=true;

	if($("select_5")){document.getElementById('select_5').value='';}
	document.getElementById('td_cheques_fondoterceros').innerHTML='&nbsp;';
	document.getElementById('td_listado_tipo_ingreso').innerHTML='&nbsp;';
}//valida_cstp03_movimientos_manuales


function limpia_cstp03_monto_listado(i){
	document.getElementById('monto_tipo_ingreso_'+i).value='';
}

function limpiar_cstp03_sucursal(){
		document.getElementById('cod_sucursal_bancaria').value='';
		document.getElementById('deno_sucursal_bancaria').value='';
}

function cstp03_movimientos_manuales_anulacion(){
	if(verifica_cierre_ano_ejecucion_msj()==false){
		return false;
	}else if(document.getElementById('concepto_anulacion').value==''){
	        fun_msj('Debe ingresar el concepto de la anulaci&uacute;n');
			document.getElementById('concepto_anulacion').focus();
			return false;

	}
	document.getElementById('botonanulacion').disabled=true;
}//cstp03_movimientos_manuales_anulacion

function cstp03_movimientos_manuales_num_cheque(){
	var tipo = document.getElementById('tipo_documento').value;
	alert('tipo documento: '+tipo);
}

function numero_automatico(){
	if(document.getElementById('numero_automatico_1').checked){
		document.getElementById('tipo_documento_4').checked=true;
		document.getElementById('tipo_documento_1').disabled="";
		document.getElementById('tipo_documento_2').disabled="";
		document.getElementById('tipo_documento_3').disabled="";
	}else{
		document.getElementById('tipo_documento_1').disabled="";
		document.getElementById('tipo_documento_2').disabled="";
		document.getElementById('tipo_documento_3').disabled="";
		document.getElementById('tipo_documento_4').disabled="";
		document.getElementById('tipo_documento_1').checked=false;
		document.getElementById('tipo_documento_2').checked=false;
		document.getElementById('tipo_documento_3').checked=false;
		document.getElementById('tipo_documento_4').checked=false;
	}
}

function marca_nro_auto(){

	if((document.getElementById('tipo_documento_1').checked==false) && (document.getElementById('tipo_documento_2').checked==false) && (document.getElementById('tipo_documento_3').checked==false) && (document.getElementById('tipo_documento_4').checked==false)){
	       fun_msj('Por favor seleccione el tipo de documento');
	}else{
			if((document.getElementById('tipo_documento_1').checked==true) || (document.getElementById('tipo_documento_2').checked==true) || (document.getElementById('tipo_documento_3').checked==true)){
				//alert('si estan marcados alguno de ellos');
				//document.getElementById('numero_automatico_2').disabled="";
				document.getElementById('numero_automatico_2').checked=true;
				document.getElementById('numero_automatico_1').checked=false;
				document.getElementById('numero_automatico_1').disabled="disabled";
				//document.getElementById('numero_documento').disabled=false;
				fun_msj2('Recuerde que esta opcion solo es aplicable a los cheques bancarios');

			}else if(document.getElementById('tipo_documento_4').checked==true){
				//alert('no estan marcados alguno de ellos');
				document.getElementById('numero_automatico_1').disabled=false;
				document.getElementById('numero_automatico_2').checked=false;
				document.getElementById('numero_automatico_2').disabled=true;
			}
	}
}

function marca_nro_auto2(){
	if((document.getElementById('tipo_documento_1').checked==true) || (document.getElementById('tipo_documento_2').checked==true) || (document.getElementById('tipo_documento_3').checked==true)){

		//Activar los radios para seleccionar el tipo de recurso
		if((document.getElementById('tipo_documento_1').checked==true) || (document.getElementById('tipo_documento_2').checked==true)){
				document.getElementById('tipo_recurso_1').disabled=false;
				document.getElementById('tipo_recurso_2').disabled=false;
				document.getElementById('tipo_recurso_3').disabled=false;
				document.getElementById('tipo_recurso_4').disabled=false;
				document.getElementById('tipo_recurso_5').disabled=false;
				document.getElementById('tipo_recurso_6').disabled=false;
				document.getElementById('tipo_recurso_1').checked=false;
				document.getElementById('tipo_recurso_2').checked=false;
				document.getElementById('tipo_recurso_3').checked=false;
				document.getElementById('tipo_recurso_4').checked=false;
				document.getElementById('tipo_recurso_5').checked=false;
				document.getElementById('tipo_recurso_6').checked=false;
				document.getElementById('cod_tipo_recurso').value='';
				document.getElementById('deno_tipo_recurso').value='';
				document.getElementById('colocacion_1').checked=false;
				document.getElementById('colocacion_2').checked=true;
				document.getElementById('colocacion_1').disabled=false;
				document.getElementById('colocacion_2').disabled=false;


				document.getElementById('cheque_cach_1').checked=false;
				document.getElementById('cheque_cach_2').checked=true;
				document.getElementById('cheque_cach_1').disabled=true;
				document.getElementById('cheque_cach_2').disabled=true;

				document.getElementById('numero_automatico_2').disabled=false;
				document.getElementById('numero_automatico_2').checked=true;
				document.getElementById('numero_automatico_1').checked=false;
				document.getElementById('numero_automatico_1').disabled=true;

				document.getElementById('pagotransferencia_1').checked=false;
				document.getElementById('pagotransferencia_2').checked=true;
				document.getElementById('pagotransferencia_1').disabled=true;
				document.getElementById('pagotransferencia_2').disabled=true;

				document.getElementById('monto').value='';
				document.getElementById('concepto').value='';
				document.getElementById('fecha_documento').value=''
				document.getElementById('numero_documento').value='';
				document.getElementById('numero_documento').readOnly=false;
				document.getElementById('b_cheque').disabled=true;

		document.getElementById('td_datos_pagotransferencia2233').innerHTML="";


		}else{
				document.getElementById('tipo_recurso_1').checked=false;
				document.getElementById('tipo_recurso_2').checked=false;
				document.getElementById('tipo_recurso_3').checked=false;
				document.getElementById('tipo_recurso_4').checked=false;
				document.getElementById('tipo_recurso_5').checked=false;
				document.getElementById('tipo_recurso_6').checked=false;
				document.getElementById('tipo_recurso_1').disabled=true;
				document.getElementById('tipo_recurso_2').disabled=true;
				document.getElementById('tipo_recurso_3').disabled=true;
				document.getElementById('tipo_recurso_4').disabled=true;
				document.getElementById('tipo_recurso_5').disabled=true;
				document.getElementById('tipo_recurso_6').disabled=true;
				document.getElementById('cod_tipo_recurso').value='';
				document.getElementById('deno_tipo_recurso').value='';
				document.getElementById('colocacion_1').checked=false;
				document.getElementById('colocacion_2').checked=true;
				document.getElementById('colocacion_1').disabled=true;
				document.getElementById('colocacion_2').disabled=true;


				document.getElementById('cheque_cach_1').checked=false;
				document.getElementById('cheque_cach_2').checked=true;
				document.getElementById('cheque_cach_1').disabled=true;
				document.getElementById('cheque_cach_2').disabled=true;

				document.getElementById('numero_automatico_2').disabled=false;
				document.getElementById('numero_automatico_2').checked=true;
				document.getElementById('numero_automatico_1').checked=false;
				document.getElementById('numero_automatico_1').disabled=true;

				
				document.getElementById('pagotransferencia_1').disabled=false;
				document.getElementById('pagotransferencia_2').disabled=false;

				document.getElementById('monto').value='';
				document.getElementById('concepto').value='';
				document.getElementById('fecha_documento').value=''
				document.getElementById('numero_documento').value='';
				document.getElementById('numero_documento').readOnly=false;
				document.getElementById('b_cheque').disabled=true;

		document.getElementById('td_datos_pagotransferencia2233').innerHTML="";
		}

		

	}else if(document.getElementById('tipo_documento_4').checked==true){  //-----------opcion de cheque caja chica o solicitud de recurso.

		//Se desactivan los radios para seleccionar el tipo de recurso
		document.getElementById('tipo_recurso_1').checked=false;
		document.getElementById('tipo_recurso_2').checked=false;
		document.getElementById('tipo_recurso_3').checked=false;
		document.getElementById('tipo_recurso_4').checked=false;
		document.getElementById('tipo_recurso_5').checked=false;
		document.getElementById('tipo_recurso_6').checked=false;
		document.getElementById('tipo_recurso_1').disabled=true;
		document.getElementById('tipo_recurso_2').disabled=true;
		document.getElementById('tipo_recurso_3').disabled=true;
		document.getElementById('tipo_recurso_4').disabled=true;
		document.getElementById('tipo_recurso_5').disabled=true;
		document.getElementById('tipo_recurso_6').disabled=true;
		document.getElementById('cod_tipo_recurso').value='';
		document.getElementById('deno_tipo_recurso').value='';

		// document.getElementById('pagotransferencia_1').checked=false;
		// document.getElementById('pagotransferencia_2').checked=true;
		document.getElementById('pagotransferencia_1').disabled=false;
		document.getElementById('pagotransferencia_2').disabled=false;

		document.getElementById('numero_automatico_2').checked=false;
		document.getElementById('numero_automatico_2').disabled=true;
		//document.getElementById('numero_automatico_1').checked=false;
		document.getElementById('numero_automatico_1').disabled=false;
		document.getElementById('numero_automatico_1').checked=true;

		document.getElementById('monto').value='';
		document.getElementById('concepto').value='';
		document.getElementById('fecha_documento').value=''
		document.getElementById('numero_documento').value='';
		document.getElementById('numero_documento').readOnly=true;

		// document.getElementById('cheque_cach_1').checked=false;
		// document.getElementById('cheque_cach_2').checked=true;
		document.getElementById('cheque_cach_1').disabled=false;
		document.getElementById('cheque_cach_2').disabled=false;

		document.getElementById('colocacion_1').checked=false;
		document.getElementById('colocacion_2').checked=true;
		document.getElementById('colocacion_1').disabled=true;
		document.getElementById('colocacion_2').disabled=true;
		document.getElementById('b_cheque').disabled=false;
	}
}

function numero_automatico_index(){
	if(document.getElementById('numero_automatico_1').checked){
		fun_msj('Primero debe seleccionar la entidad, la sucursal y la cuenta bancaria');
	}
}

function valida_busqueda_cstp03_movimientos_manuales(){
	if((document.getElementById('tipo_documento_1').checked==false) && (document.getElementById('tipo_documento_2').checked==false) && (document.getElementById('tipo_documento_3').checked==false) && (document.getElementById('tipo_documento_4').checked==false) && (document.getElementById('tipo_documento_5').checked==false)){
	        fun_msj('Debe seleccionar un tipo de documento');
			return false;
	}if(document.getElementById('campobuscar').value==''){
	        fun_msj('Debe ingresar el n&uacute;mero de documento a buscar');
			document.getElementById('campobuscar').focus();
			return false;
	}
}//valida_busqueda_cstp03_movimientos_manuales

function valida_reporte_cheques_movmanuales(){
	if((document.getElementById('preimpreso_1').checked==true)){
		//alert("Formato Libre");
	   Windows.close(document.getElementById('capa_ventana').value);
	}else if((document.getElementById('preimpreso_2').checked==true)){
		//alert("Formato Preimpreso");
	}
}

function cstp03_movimientos_manuales_transferencia(){
	if(document.getElementById('select_dependencias').value==''){
	        fun_msj('Debe seleccionar una dependencia, por favor');
			document.getElementById('select_dependencias').focus();
			return false;

	}if(document.getElementById('select_dependencias').value==00){
	        fun_msj('No existen Dependencias pendientes por solicitud de recursos');
			document.getElementById('select_dependencias').focus();
			return false;

	}if(document.getElementById('ano_2').value==''){
	        fun_msj('El a&ntilde;o de la orden de transferencia no puede estar vac&iacute;o');
			document.getElementById('ano_2').focus();
			return false;

	}if(document.getElementById('numero_solicitud').value==''){
	        fun_msj('Debe seleccionar un numero de solicitud, por favor');
			document.getElementById('numero_solicitud').focus();
			return false;

	}
}

function radio_tipo_recurso_cstd03_mov_bancarios(){
		document.getElementById('cod_tipo_recurso').value='';
		document.getElementById('deno_tipo_recurso').value='';
}

/*
function limpia_campos(){
  document.getElementById('buscar2').innerHTML="";
}
*/

//Esta funcion captura el contenido del FCKEditor y verifica que el mismo no este vacio
function capturar_contenido_editor(){
	var fckEditor1val_aux = FCKeditorAPI.__Instances['Contenido_FCK'].GetHTML();
	//alert(fckEditor1val_aux);
	//var conten = document.getElementById('Contenido___Config').value;
	if(fckEditor1val_aux==''){
		return 1;
	}else{
		document.getElementById('Contenido_FCK').value = fckEditor1val_aux;
		return 0;
	}
	//document.getElementById('contenido2').value = fckEditor1val_aux;
}

function valida_casp01_comunicacion_invitacion(){
	if(document.getElementById('fecha_oficio').value==''){
	        fun_msj('Debe ingresar la fecha del oficio');
			document.getElementById('fecha_oficio').focus();
			return false;
	}else{
		editor = capturar_contenido_editor();
		if(editor==1){
			fun_msj('Atencion: Debe ingresar el texto del oficio, por favor');
			return false;
		}
		var cont = document.getElementById('control').value;
		cont = eval(cont)+eval(1);
		document.getElementById('control').value = cont;
		return true;
	}
}


function valida_cstp03_movimientos_manuales_index3(){
	var myf = document.getElementById("Contenido2___Frame");
	alert(myf);
	myf = myf.contentWindow.document || myf.contentDocument;
	var myf2=myf.getElementById('xEditingArea').getElementsByTagName('iframe')[0].contentWindow.document || myf.getElementById('xEditingArea').getElementsByTagName('iframe')[0].contentDocument;
	alert(myf2.body.innerHTML+'aquiii');
	document.getElementById('contenido3').value = myf2.body.innerHTML+'aquiii';
	//javascript:var myf = document.getElementById("Contenido2___Frame");myf = myf.contentWindow.document || myf.contentDocument;var myf2=myf.getElementById('xEditingArea').getElementsByTagName('iframe')[0].contentWindow.document || myf.getElementById('xEditingArea').getElementsByTagName('iframe')[0].contentDocument;alert(myf2.body.innerHTML);
}


function radio_reporte_expendiente_geo(){
	document.getElementById('radio_ordenamiento_codigo_1').checked=false;
	document.getElementById('radio_ordenamiento_codigo_2').checked=false;
	document.getElementById('radio_ordenamiento_codigo_3').checked=false;
	document.getElementById('radio_ordenamiento_codigo_4').checked=false;
	document.getElementById('radio_ordenamiento_codigo_5').checked=false;
	document.getElementById('radio_ordenamiento_codigo_6').checked=false;
	document.getElementById('radio_ordenamiento_codigo_7').checked=false;
	document.getElementById('radio_ordenamiento_codigo_8').checked=false;
	document.getElementById('radio_ubicacion_administrativa_1').checked=false;
	document.getElementById('radio_ubicacion_administrativa_2').checked=false;
}

function radio_reporte_expendiente_admin(){
	document.getElementById('radio_ordenamiento_codigo_1').checked=false;
	document.getElementById('radio_ordenamiento_codigo_2').checked=false;
	document.getElementById('radio_ordenamiento_codigo_3').checked=false;
	document.getElementById('radio_ordenamiento_codigo_4').checked=false;
	document.getElementById('radio_ordenamiento_codigo_5').checked=false;
	document.getElementById('radio_ordenamiento_codigo_6').checked=false;
	document.getElementById('radio_ordenamiento_codigo_7').checked=false;
	document.getElementById('radio_ordenamiento_codigo_8').checked=false;
	document.getElementById('radio_ubicacion_geografica_1').checked=false;
	document.getElementById('radio_ubicacion_geografica_2').checked=false;
}


function radio_reporte_expendiente_codigo(){
	document.getElementById('radio_ubicacion_geografica_1').checked=false;
	document.getElementById('radio_ubicacion_geografica_2').checked=false;
	document.getElementById('radio_ubicacion_administrativa_1').checked=false;
	document.getElementById('radio_ubicacion_administrativa_2').checked=false;
}