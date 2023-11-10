function valida_cstp04_movimientos_generales(){

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
	if((document.getElementById('por_ano_1').checked==false) && (document.getElementById('por_ano_2').checked==false)){
		 fun_msj('Seleccione como desea generar el Reporte, todo o un mes especifico');
		 return false;
	}
	if(document.getElementById('por_ano_2').checked==true){
		if(document.getElementById('selectmes').value==''){
			fun_msj('Por favor, seleccione el Mes a generar');
			document.getElementById('selectmes').focus();
			return false;
		}
	}
	document.getElementById('selectmes');
}

function cstp04_mov_gen_ano(){
	if(document.getElementById('por_ano_1').checked==true){
		document.getElementById('selectmes').disabled=true;
	}else if(document.getElementById('por_ano_2').checked==true){
		document.getElementById('selectmes').disabled=false;
		fun_msj2('Seleccione el mes de cual desea generar el reporte');
	}
}

function valida_cstp04_movimientos_generales_porfecha(){
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

	if(document.getElementById('fecha_inicial').value==''){
	        fun_msj('Debe ingresar la fecha inicial');
			document.getElementById('fecha_inicial').focus();
			return false;
	}if(document.getElementById('fecha_final').value==''){
	        fun_msj('Debe ingresar la fecha final');
			document.getElementById('fecha_final').focus();
			return false;
	}
}

function valida_cstp05_estado_cuentas_guardar(){

	if(document.getElementById('select_1').value==''){
	        fun_msj('Seleccione la entidad bancaria');
			document.getElementById('select_1').focus();
			return false;
	}if(document.getElementById('select_2').value==''){
	        fun_msj('Seleccione la sucursal bancaria');
			document.getElementById('select_2').focus();
			return false;
	}if(document.getElementById('select_3').value==''){
	        fun_msj('Por favor, seleccione la cuenta bancaria');
			document.getElementById('select_3').focus();
			return false;
	}if(document.getElementById('ano_1').value==''){
	        fun_msj('Por favor, ingrese el a&ntilde;o de movimiento bancario');
			document.getElementById('ano_1').focus();
			return false;
	}if(document.getElementById('mes').value==''){
	        fun_msj('Por favor, ingrese el mes de movimiento bancario');
			document.getElementById('mes').focus();
			return false;
	}

	if((document.getElementById('tipodocumento_1').checked==false) && (document.getElementById('tipodocumento_2').checked==false) && (document.getElementById('tipodocumento_3').checked==false) && (document.getElementById('tipodocumento_4').checked==false)){
		 fun_msj('Por favor seleccione el tipo de documento a registrar');
		 return false;
	}

	if(document.getElementById('numero_documento_banco').value==''){
	        fun_msj('Por favor ingrese el numero de documento del banco');
			document.getElementById('numero_documento_banco').focus();
			return false;
	}if(document.getElementById('fecha_documento_banco').value==''){
	        fun_msj('Por favor ingrese la fecha del documento');
			document.getElementById('fecha_documento_banco').focus();
			document.getElementById('fecha_documento_banco').onclick();
			return false;
	}if(document.getElementById('monto_documento_banco').value==''){
	        fun_msj('Por favor ingrese el monto del documento');
			document.getElementById('monto_documento_banco').focus();
			return false;
	}

	var str_fecha_docu=document.getElementById('fecha_documento_banco').value;
	array_fecha_docu = str_fecha_docu.split("/");
	if (array_fecha_docu.length!=3)
       return false

    if(array_fecha_docu[2] > document.getElementById('ANO_ACTUAL_SERVIDOR').value){
	        fun_msj('Lo siento, el a&ntilde;o '+array_fecha_docu[2]+' del documento no puede ser mayor a el a&ntilde;o actual '+document.getElementById('ANO_ACTUAL_SERVIDOR').value);
			document.getElementById('fecha_documento_banco').focus();
			return false;
	}

/*
    if(document.getElementById('ano_1').value != array_fecha_docu[2]){
	        fun_msj('Lo siento, el a&ntilde;o '+array_fecha_docu[2]+' del documento no coincide con el a&ntilde;o '+document.getElementById('ano_1').value+' del estado de cuenta');
			document.getElementById('fecha_documento_banco').focus();
			return false;
	}

	if(document.getElementById('mes').value != array_fecha_docu[1]){
	        fun_msj('Lo siento, el mes '+array_fecha_docu[1]+' del documento no coincide con el mes '+document.getElementById('mes').value+' del estado de cuenta');
			document.getElementById('fecha_documento_banco').focus();
			return false;
	}
*/

	else if(diferenciaFecha(document.getElementById('date_actual_server').value, document.getElementById('fecha_documento_banco').value)){
		fun_msj('Lo siento, la fecha del documento: '+document.getElementById('fecha_documento_banco').value+' no puede ser mayor a la fecha actual '+document.getElementById('date_actual_server').value);
		return false;
	}
}

function limpia_documento(){
document.getElementById('fecha_documento_banco').value='';
document.getElementById('monto_documento_banco').value='';
}

function valida_chequecaja(){
	if(document.getElementById('ano').value == ''){
	        fun_msj('Ingrese el a&ntilde;o de movimiento por favor');
			document.getElementById('ano').focus();
			return false;
	}if(document.getElementById('fecha').value == ''){
	        fun_msj('Debe Ingresar la fecha del reporte a generar');
			document.getElementById('fecha').focus();
			return false;
	}
}

/**********************************************************************
Funciones nuevas del programa de reporte del libro de cuentas bancarias
***********************************************************************/
function cstp04_mov_gen_ano_2(){
	if(document.getElementById('por_ano_1').checked==true){
		//document.getElementById('selectmes').disabled=true;
		//document.getElementById('selectmes').disabled=false;
		document.getElementById('titulo-seleccion').innerHTML="SELECCIONE EL MES";
		mes = document.getElementById('mes')
		fec = document.getElementById('fecha')
		if(mes.style.display==""){
		mes.style.display = "none" // ocultamos el mes
		} else {
		fec.style.display = "none" // ocultamos la fecha
		mes.style.display = ""     // mostramos el mes
		fun_msj2('Seleccione el mes de cual desea generar el reporte');
		}


	}else if(document.getElementById('por_ano_2').checked==true){
		document.getElementById('titulo-seleccion').innerHTML="FECHA INICIAL&nbsp;&nbsp;&nbsp;&nbsp;FECHA FINAL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		mes = document.getElementById('mes')
		fec = document.getElementById('fecha')
		if(fec.style.display==""){
		fec.style.display = "none" // ocultamos la fecha
		} else {
		mes.style.display = "none" // ocultamos la fecha
		fec.style.display = ""     // mostramos el mes
		}
	}
}

function valida_cstp04_mov_generales(){
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

	if((document.getElementById('por_ano_1').checked==false) && (document.getElementById('por_ano_2').checked==false)){
		 fun_msj('Seleccione como desea generar el Reporte, un mes especifico o por fecha ');
		 return false;
	}

	if(document.getElementById('por_ano_1').checked==true){
		if(document.getElementById('selectmes').value==''){
			fun_msj('Por favor, seleccione el Mes a generar');
			document.getElementById('selectmes').focus();
			return false;
		}
	}

	if(document.getElementById('por_ano_2').checked==true){
		if(document.getElementById('fecha_inicial').value==''){
			fun_msj('Por favor, seleccione la fecha inicial');
			document.getElementById('selectmes').focus();
			return false;
		}
		if(document.getElementById('fecha_final').value==''){
			fun_msj('Por favor, seleccione la fecha final');
			document.getElementById('selectmes').focus();
			return false;
		}
	}
}

function mostrar_capa_fecha_cimd03(){
	if(document.getElementById('por_ano_2').checked==true){
		mes = document.getElementById('mes')
		fec = document.getElementById('fecha')
		if(mes.style.display==""){
		mes.style.display = "none" // ocultamos el mes
		} else {
		fec.style.display = "none" // ocultamos la fecha
		mes.style.display = ""     // mostramos el mes
		fun_msj2('Seleccione el mes de cual desea generar el reporte');
		}
	}else if(document.getElementById('por_ano_2').checked==true){
		mes = document.getElementById('mes')
		fec = document.getElementById('fecha')
		if(fec.style.display==""){
		fec.style.display = "none" // ocultamos la fecha
		} else {
		mes.style.display = "none" // ocultamos la fecha
		fec.style.display = ""     // mostramos el mes
		}
	}
}

//Function usada en: reporte_bienes, por el programa: reporte_movimiento_bienes_muebles
function mostrar_capa_ubicaciones_ga_bienes(){
	if(document.getElementById('select_ubicaciones_1').checked==true){
		ubicaciones = document.getElementById('capa-ubicaciones');
		ubicaciones.style.display = "none"; // ocultamos
	}else if(document.getElementById('select_ubicaciones_2').checked==true){
		ubicaciones = document.getElementById('capa-ubicaciones');
		ubicaciones.style.display = ""; // mostramos
		fun_msj2('Puede proceder a seleccionar las ubicaciones');
	}
}

//Function usada en: proveedores y contratistas, por el programa: frm_reporte_proveedores_cpcd02
function mostrar_capa_fecha_reporteproveedores(){
	if(document.getElementById('por_fecha_1').checked==true){
		ubicaciones = document.getElementById('fecha');
		ubicaciones.style.display = "none"; // ocultamos
	}else if(document.getElementById('por_fecha_2').checked==true){
		ubicaciones = document.getElementById('fecha');
		ubicaciones.style.display = ""; // mostramos
		fun_msj2('Puede proceder a seleccionar las ubicaciones');
	}
}

//Function usada en: reporte_personal, por el programa de emision de recibos genericos
function mostrar_capa_rango_recibos(){
	if(document.getElementById('rango_recibos_1').checked==true){
		capa_recibos = document.getElementById('capa_1');
		capa_recibos.style.display = "none"; // ocultamos

	}else if(document.getElementById('rango_recibos_2').checked==true){
		capa_recibos = document.getElementById('capa_1');
		capa_recibos.style.display = ""; // mostramos
		fun_msj2('Seleccione el rango de recibos que desea generar');
	}
}