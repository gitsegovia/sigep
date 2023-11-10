function valida_cscp01_unidad_medida(){

if(document.getElementById('expresion').value==""){
			fun_msj('Inserte la expresi&oacute;n de la medida');
			document.getElementById('expresion').focus();
			return false;

}else if(document.getElementById('denominacion').value==""){

			fun_msj('Inserte la denominaci&oacute;n');
			document.getElementById('denominacion').focus();
			return false;

}

}//fin funtion

function validaReporteCscp01_catalogo(){
	if(document.getElementById('tipo_1').checked==false || document.getElementById('tipo_2').checked==false || document.getElementById('tipo_3').checked==false || document.getElementById('tipo_4').checked==false){
			fun_msj('Debe seleccionar el tipo de Reporte');
			return false;
	}
}

/* habilita_b_modificar_cscp01_unidad_medida es */
function habilita_b_modificar_cscp01(){
	fun_msj2('Puede modificar la Unidad de Medida');
	document.getElementById('expresion').readOnly=false;
	document.getElementById('denominacion').readOnly=false;
	document.getElementById('b_guardar').disabled=false;
	document.getElementById('b_modificar').disabled=true;
}


/*Funcion para habilitar el boton de modificacion del programa cepp01_tipo_compromiso*/
function habilita_b_modificar_cepp01tipocomp(){
	fun_msj2('Puede modificar el registro de compromiso');
	document.getElementById('denominacion').readOnly=false;
	document.getElementById('sujeto_retencion_1').disabled=false;
	document.getElementById('sujeto_retencion_2').disabled=false;
	document.getElementById('b_guardar').disabled=false;
	document.getElementById('b_modificar').disabled=true;
}

function habilita_b_modificar_cepp03_tipopago(){
	fun_msj2('Puede modificar el tipo de pago');
	document.getElementById('denominacion').readOnly=false;
	document.getElementById('b_guardar').disabled=false;
	document.getElementById('b_modificar').disabled=true;
}

function habilita_b_modificar_solicitud_recurso(){
	fun_msj2('Puede modificar el tipo de solicitud');
	document.getElementById('denominacion').readOnly=false;
	document.getElementById('b_guardar').disabled=false;
	document.getElementById('b_modificar').disabled=true;
}

function habilita_b_modificar_cpcp01(){
	fun_msj2('Puede modificar el ramo comercial');
	document.getElementById('denominacion').readOnly=false;
	document.getElementById('b_guardar').disabled=false;
	document.getElementById('b_modificar').disabled=true;
}