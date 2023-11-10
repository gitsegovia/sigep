function valida_cfpp10_reformulacion_funcionarios(){

if(document.getElementById('titulo_nombres_originar').value==''){

			fun_msj('Por Favor Intruduzca los Datos del Funcionario Responsable de Originar el Oficio');
			document.getElementById('titulo_nombres_originar').focus();
			return false;

}else if(document.getElementById('cargo_originar').value == ""){

			fun_msj('Por Favor Intruduzca el Cargo del Funcionario Responsable de Originar el Oficio');
			document.getElementById('cargo_originar').focus();
			return false;


}else if(document.getElementById('titulo_nombres_enviar').value==''){

			fun_msj('Por Favor Intruduzca los Datos del Funcionario Responsable de Enviar el Oficio');
			document.getElementById('titulo_nombres_enviar').focus();
			return false;

}else if(document.getElementById('cargo_enviar').value == ""){

			fun_msj('Por Favor Intruduzca el Cargo del Funcionario Responsable de Enviar el Oficio');
			document.getElementById('cargo_enviar').focus();
			return false;


}else if(document.getElementById('titulo_nombres_remitir').value==''){

			fun_msj('Por Favor Intruduzca los Datos del Funcionario Responsable de Remitir el Oficio');
			document.getElementById('titulo_nombres_remitir').focus();
			return false;

}else if(document.getElementById('cargo_remitir').value == ""){

			fun_msj('Por Favor Intruduzca el Cargo del Funcionario Responsable de Remitir el Oficio');
			document.getElementById('cargo_remitir').focus();
			return false;


}else if(document.getElementById('titulo_nombres_aprobar').value==''){

			fun_msj('Por Favor Intruduzca los Datos del Funcionario Responsable de Aprobar el Oficio');
			document.getElementById('titulo_nombres_aprobar').focus();
			return false;

}else if(document.getElementById('cargo_aprobar').value == ""){

			fun_msj('Por Favor Intruduzca el Cargo del Funcionario Responsable de Aprobar el Oficio');
			document.getElementById('cargo_aprobar').focus();
			return false;


}else if(document.getElementById('titulo_nombres_firmar').value==''){

			fun_msj('Por Favor Intruduzca los Datos del Funcionario Responsable de Firmar el Decreto');
			document.getElementById('titulo_nombres_firmar').focus();
			return false;

}else if(document.getElementById('cargo_firmar').value == ""){

			fun_msj('Por Favor Intruduzca el Cargo del Funcionario Responsable de Firmar el Decreto');
			document.getElementById('cargo_firmar').focus();
			return false;


}else if(document.getElementById('titulo_nombres_refrendar').value==''){

			fun_msj('Por Favor Intruduzca los Datos del Funcionario Responsable de Refrendar el Decreto');
			document.getElementById('titulo_nombres_refrendar').focus();
			return false;

}else if(document.getElementById('cargo_refrendar').value == ""){

			fun_msj('Por Favor Intruduzca el Cargo del Funcionario Responsable de Refrendar el Decreto');
			document.getElementById('cargo_refrendar').focus();
			return false;


}

}//fin funtion




function valida_cfpp10_reformulacion_partidas(){
   if(document.getElementById('oficio').value==''){

			fun_msj('Por Favor Seleccione el Numero del Oficio');
			document.getElementById('oficio').focus();
			return false;

	}else if(document.getElementById('seleccion_1').value==''){

			fun_msj('Por Favor Seleccione el Sector Correspondiente');
			document.getElementById('seleccion_1').focus();
			return false;


	}else if(document.getElementById('seleccion_2').value==''){

			fun_msj('Por Favor Seleccione el Programa Correspondiente');
			document.getElementById('seleccion_2').focus();
			return false;


	}else if(document.getElementById('seleccion_3').value==''){

			fun_msj('Por Favor Seleccione el Sub Programa Correspondiente');
			document.getElementById('seleccion_3').focus();
			return false;

	}else if(document.getElementById('seleccion_4').value==''){

			fun_msj('Por Favor Seleccione el Proyecto Correspondiente');
			document.getElementById('seleccion_4').focus();
			return false;


	}else if(document.getElementById('seleccion_5').value==''){

			fun_msj('Por Favor Seleccione la Actividad u Obra Correspondiente');
			document.getElementById('seleccion_5').focus();
			return false;


	}else if(document.getElementById('seleccion_6').value==''){

			fun_msj('Por Favor Seleccione la Partida Correspondiente');
			document.getElementById('seleccion_6').focus();
			return false;


	}else if(document.getElementById('seleccion_7').value==''){

			fun_msj('Por Favor Seleccione la Generica Correspondiente');
			document.getElementById('seleccion_7').focus();
			return false;


	}else if(document.getElementById('seleccion_8').value==''){

			fun_msj('Por Favor Seleccione la Especifica Correspondiente');
			document.getElementById('seleccion_8').focus();
			return false;


	}else if(document.getElementById('seleccion_9').value==''){

			fun_msj('Por Favor Seleccione la Sub Especifica Correspondiente');
			document.getElementById('seleccion_9').focus();
			return false;


	}else if(document.getElementById('seleccion_10').value==''){

			fun_msj('Por Favor Seleccione el Auxiliar Correspondiente');
			document.getElementById('seleccion_10').focus();
			return false;


	}else if(document.getElementById('seleccion_11').value==''){

			fun_msj('Por Favor Seleccione la Dependencia Correspondiente');
			document.getElementById('seleccion_11').focus();
			return false;


	}else if((document.getElementById('monto_deduccion').value=='') && (document.getElementById('monto_aumento').value=='')){

			fun_msj('Por Favor Ingrese el Monto');
			document.getElementById('monto_aumento').focus();
			return false;


	}

}//fin funtion

function bloquearCRR(actual,id){
    if((document.getElementById(actual).value!="" && document.getElementById(actual).value!="0") && (document.getElementById(id).value=="" || document.getElementById(id).value=="0")){
         document.getElementById(id).value=0;
         document.getElementById(id).disabled="disabled";
         //document.getElementById('bene').focus();

    }else{
        document.getElementById(id).value="";
        document.getElementById(id).disabled="";
    }
}

function validar_tipo_reformulacion(){
		a=eval(document.getElementById('monto1').value)  +  eval(document.getElementById('monto2').value);
		b=eval(document.getElementById('monto_b').value);
		c=eval(document.getElementById('cod_tipo_reformulacion').value);
		d=eval(document.getElementById('monto1').value);
		e=eval(document.getElementById('monto2').value);

if(document.getElementById('cod_tipo_reformulacion').value==1 && (d !=e )){
	fun_msj('Por favor verifique, los Montos de Aumento Y A Disminuir No son Iguales');
	return false;
}else if(document.getElementById('cod_tipo_reformulacion').value==1 && (b !=d )){
	fun_msj('Por favor verifique, El Monto a reformular y El Aumento y A Disminuir No Cuadran');
	return false;
}else if(document.getElementById('cod_tipo_reformulacion').value==2 && (e !=b )){
	fun_msj('Por favor verifique, El Monto a reformular y El Aumento No Cuadran');
	return false;
}else if(document.getElementById('cod_tipo_reformulacion').value==2 && (d !=0 )){
	fun_msj('Por favor verifique, El Monto A Disminuir No es Cero');
	return false;
}else if(document.getElementById('cod_tipo_reformulacion').value==3 && (d !=b )){
	fun_msj('Por favor verifique, El Monto a reformular y A Disminuir No Cuadran');
	return false;
}else if(document.getElementById('cod_tipo_reformulacion').value==3 && (e !=0 )){
	fun_msj('Por favor verifique, El Monto Aumento No es Cero');
	return false;
}else if(document.getElementById('cod_tipo_reformulacion').value==4 && (e !=b )){
	fun_msj('Por favor verifique, El Monto a reformular y El Aumento No Cuadran');
	return false;
}else if(document.getElementById('cod_tipo_reformulacion').value==4 && (d !=0 )){
	fun_msj('Por favor verifique, El Monto A Disminuir No es Cero');
	return false;
}else if(document.getElementById('cod_tipo_reformulacion').value==5 && (e !=b )){
	fun_msj('Por favor verifique, El Monto a reformular y El Aumento No Cuadran');
	return false;
}else if(document.getElementById('cod_tipo_reformulacion').value==5 && (d !=0 )){
	fun_msj('Por favor verifique, El Monto A Disminuir No es Cero');
	return false;
}

}


function validar_tipo_reformulacion2(){
		a=eval(document.getElementById('monto1').value)  +  eval(document.getElementById('monto2').value);
		b=eval(document.getElementById('monto_b').value);
		c=eval(document.getElementById('cod_tipo_reformulacion').value);
		d=eval(document.getElementById('monto1').value);
		e=eval(document.getElementById('monto2').value);

if(document.getElementById('cod_tipo_reformulacion').value==1 && (e !=d )){
	fun_msj('Por favor verifique, El Monto Aumento y A Disminuir No Cuadran');
	return false;
}else if(document.getElementById('cod_tipo_reformulacion').value==2 && (d !=0 )){
	fun_msj('Por favor verifique, El Monto A Disminuir No es Cero');
	return false;
}else if(document.getElementById('cod_tipo_reformulacion').value==3 && (e !=0 )){
	fun_msj('Por favor verifique, El Monto Aumento No es Cero');
	return false;
}else if(document.getElementById('cod_tipo_reformulacion').value==4 && (d !=0 )){
	fun_msj('Por favor verifique, El Monto A Disminuir No es Cero');
	return false;
}else if(document.getElementById('cod_tipo_reformulacion').value==5 && (d !=0 )){
	fun_msj('Por favor verifique, El Monto A Disminuir No es Cero');
	return false;
}

}


function valida_cfpp10_reformulacion_oficios(){

if(document.getElementById('numero_oficio').value==''){

			fun_msj('Por Favor Introduzca el Numero del Oficio');
			document.getElementById('numero_oficio').focus();
			return false;

}else if(document.getElementById('fecha_oficio').value == ""){

			fun_msj('Por Favor Introduzca la Fecha del Oficio');
			document.getElementById('fecha_oficio').focus();
			return false;


}else if(document.getElementById('tipo_reformulacion').value==''){

			fun_msj('Por Favor Seleccione el Tipo de Reformulacion');
			document.getElementById('tipo_reformulacion').focus();
			return false;

}else if(document.getElementById('razones').value == ""){

			fun_msj('Por Favor Introduzca las Razones para Hacer esta Reformulacion');
			document.getElementById('razones').focus();
			return false;


}else if(document.getElementById('monto').value==''){

			fun_msj('Por Favor Introduzca el Monto a Reformular ');
			document.getElementById('monto').focus();
			return false;

}else if(document.getElementById('enca_ofi').value == ""){

			fun_msj('Por Favor Introduzca el Encabezado del Oficio');
			document.getElementById('enca_ofi').focus();
			return false;


}else if(document.getElementById('pie_ofi').value==''){

			fun_msj('Por Favor Introduzca el Pie del Oficio');
			document.getElementById('pie_ofi').focus();
			return false;

}else if(document.getElementById('nota_ofi').value == ""){

			fun_msj('Por Favor Introduzca la Nota Final del Oficio Oficio');
			document.getElementById('nota_ofi').focus();
			return false;


}else if(document.getElementById('enca_decre').value==''){

			fun_msj('Por Favor Introduzca el Encabezado del Decreto');
			document.getElementById('enca_decre').focus();
			return false;

}else if(document.getElementById('pie_decre').value == ""){

			fun_msj('Por Favor Introduzca el Pie del Decreto');
			document.getElementById('pie_decre').focus();
			return false;


}else if(document.getElementById('nota_decre').value==''){

			fun_msj('Por Favor Introduzca la Nota Final del Decreto');
			document.getElementById('nota_decre').focus();
			return false;

}

if(document.getElementById('tipo').value=='1' || document.getElementById('tipo').value=='2'){
	if(document.getElementById('dependencia').value=='1'){
		if(document.getElementById('planilla').value!=''){
			var monto = document.getElementById('monto').value;
			var monto_disponible = document.getElementById('monto_planilla_disponible').value;
			if(monto_disponible=='') monto_disponible='0,00';
			/////////////////////////////////////////
			var str = monto_disponible;
			for(i=0; i<monto_disponible.length; i++){str = str.replace('.','');}//fin for
			str   = str.replace(',','.');
			var monto_disponible = str;
			/////////////////////////////////////////


			/////////////////////////////////////////
			var str = monto;
			for(i=0; i<monto.length; i++){str = str.replace('.','');}//fin for
			str   = str.replace(',','.');
			var monto = str;
			/////////////////////////////////////////
			if(eval(monto)>eval(monto_disponible))
			{
				fun_msj('El monto de la reformulación no debe ser mayor al de la disponibilidad');
				return false;
			}

		}
	}
}

}//fin funtion


function valida_cfpp10_reformulacion_oficios2(){

if(document.getElementById('numero_decreto').value==''){

			fun_msj('Por Favor Introduzca el Numero del decreto');
			document.getElementById('numero_decreto').focus();
			return false;

}else if(document.getElementById('fecha_decreto').value == ""){

			fun_msj('Por Favor Introduzca la Fecha del decreto');
			document.getElementById('fecha_decreto').focus();
			return false;
}

}//fin funtion


function valida_cfpp10_reformulacion_decreto(){

if(document.getElementById('numero_decreto').value==''){

			fun_msj('Por Favor Introduzca el Numero del Decreto');
			document.getElementById('numero_decreto').focus();
			return false;

}else if(document.getElementById('fecha_decreto').value == ""){

			fun_msj('Por Favor Introduzca la Fecha del Decreto');
			document.getElementById('fecha_decreto').focus();
			return false;


}
}//fin funtion


function validar_crear_auxiliar_reformulacion(){

if(document.getElementById('seleccion_auxiliar').value==''){

			fun_msj('Por Favor Seleccione la Dedendencia');
			document.getElementById('seleccion_auxiliar').focus();
			return false;

}else if(document.getElementById('concepto_auxiliar').value == ""){

			fun_msj('Por Favor Introduzca la Denominacion del Auxiliar');
			document.getElementById('concepto_auxiliar').focus();
			return false;


}
}//fin funtion


function validar_crear_auxiliar_reformulacion2(){
if(document.getElementById('seleccion_auxiliar2').value==''){
			fun_msj('Por Favor Seleccione la Dedendencia');
			document.getElementById('seleccion_auxiliar2').focus();
			return false;

}
}//fin funtion


function valida_consultar_auxiliares(){
 if(document.getElementById('seleccion_1').value==''){

			fun_msj('Por Favor Seleccione el Sector Correspondiente');
			document.getElementById('seleccion_1').focus();
			return false;


	}else if(document.getElementById('seleccion_2').value==''){

			fun_msj('Por Favor Seleccione el Programa Correspondiente');
			document.getElementById('seleccion_2').focus();
			return false;


	}else if(document.getElementById('seleccion_3').value==''){

			fun_msj('Por Favor Seleccione el Sub Programa Correspondiente');
			document.getElementById('seleccion_3').focus();
			return false;

	}else if(document.getElementById('seleccion_4').value==''){

			fun_msj('Por Favor Seleccione el Proyecto Correspondiente');
			document.getElementById('seleccion_4').focus();
			return false;


	}else if(document.getElementById('seleccion_5').value==''){

			fun_msj('Por Favor Seleccione la Actividad u Obra Correspondiente');
			document.getElementById('seleccion_5').focus();
			return false;


	}else if(document.getElementById('seleccion_6').value==''){

			fun_msj('Por Favor Seleccione la Partida Correspondiente');
			document.getElementById('seleccion_6').focus();
			return false;


	}else if(document.getElementById('seleccion_7').value==''){

			fun_msj('Por Favor Seleccione la Generica Correspondiente');
			document.getElementById('seleccion_7').focus();
			return false;


	}else if(document.getElementById('seleccion_8').value==''){

			fun_msj('Por Favor Seleccione la Especifica Correspondiente');
			document.getElementById('seleccion_8').focus();
			return false;


	}else if(document.getElementById('seleccion_9').value==''){

			fun_msj('Por Favor Seleccione la Sub Especifica Correspondiente');
			document.getElementById('seleccion_9').focus();
			return false;


	}else if(document.getElementById('seleccion_10').value==''){

			fun_msj('Por Favor Seleccione el Auxiliar Correspondiente');
			document.getElementById('seleccion_10').focus();
			return false;


	}

}//fin funtion

function valida_busqueda_texto(){
 if(document.getElementById('ano').value==''){

			fun_msj('Por Favor ingrese el a&ntilde;o que desea consultar');
			document.getElementById('ano').focus();
			return false;


	}
}//fin funtion

function validar_camb_dep(){
   if(document.getElementById('seleccion_1').value==''){

			fun_msj('Por Favor Seleccione el Sector');
			document.getElementById('seleccion_1').focus();
			return false;


	}else if(document.getElementById('seleccion_2').value==''){

			fun_msj('Por Favor Seleccione el Programa');
			document.getElementById('seleccion_2').focus();
			return false;


	}else if(document.getElementById('seleccion_3').value==''){

			fun_msj('Por Favor Seleccione el Sub Programa');
			document.getElementById('seleccion_3').focus();
			return false;

	}else if(document.getElementById('seleccion_4').value==''){

			fun_msj('Por Favor Seleccione el Proyecto');
			document.getElementById('seleccion_4').focus();
			return false;


	}else if(document.getElementById('seleccion_5').value==''){

			fun_msj('Por Favor Seleccione la Actividad u Obra');
			document.getElementById('seleccion_5').focus();
			return false;


	}else if(document.getElementById('seleccion_6').value==''){

			fun_msj('Por Favor Seleccione la Partida');
			document.getElementById('seleccion_6').focus();
			return false;


	}else if(document.getElementById('seleccion_7').value==''){

			fun_msj('Por Favor Seleccione la Generica');
			document.getElementById('seleccion_7').focus();
			return false;


	}else if(document.getElementById('seleccion_8').value==''){

			fun_msj('Por Favor Seleccione la Especifica');
			document.getElementById('seleccion_8').focus();
			return false;


	}else if(document.getElementById('seleccion_9').value==''){

			fun_msj('Por Favor Seleccione la Sub Especifica');
			document.getElementById('seleccion_9').focus();
			return false;


	}else if(document.getElementById('seleccion_10').value==''){

			fun_msj('Por Favor Seleccione el Auxiliar');
			document.getElementById('seleccion_10').focus();
			return false;


	}else if(document.getElementById('seleccion_11').value==''){

			fun_msj('Por Favor Seleccione la Dependencia actual');
			document.getElementById('seleccion_11').focus();
			return false;


	}else if(document.getElementById('dep_dos').value==''){

			fun_msj('Por Favor Seleccione la Dependencia hacia donde desea cambiar ESTE C&Oacute;DIGO PRESUPUESTARIO');
			document.getElementById('dep_dos').focus();
			return false;


	}

}//fin funtion

function valida_cambiar_cedula_s(){

if(document.getElementById('cedula_a').value==''){

			fun_msj('Por Favor Ingrese la cedula actual');
			document.getElementById('cedula_a').focus();
			return false;

}else if(document.getElementById('cedula_b').value == ""){

			fun_msj('Por Favor Ingrese la cedula nueva');
			document.getElementById('cedula_b').focus();
			return false;
}
}

function valida_eli_mue_e(){
if(document.getElementById('select_depe').value==''){

			fun_msj('Por Favor seleccione la dependencia');
			document.getElementById('select_depe').focus();
			return false;

}else if(document.getElementById('numero_identificacion').value == ""){

			fun_msj('debe realizar la busqueda de un bien mueble');
			document.getElementById('numero_identificacion').focus();
			return false;
}

}//fin funtion

function valida_eli_inmue_e(){
if(document.getElementById('select_depe').value==''){

			fun_msj('Por Favor seleccione la dependencia');
			document.getElementById('select_depe').focus();
			return false;

}else if(document.getElementById('numero_identificacion').value == ""){

			fun_msj('debe realizar la busqueda de un inmueble');
			document.getElementById('numero_identificacion').focus();
			return false;
}

}//fin funtion

function valida_t_a(){
if(document.getElementById('ano_a').value==''){

			fun_msj('ingrese de que a&ntilde;o desea traspasar');
			document.getElementById('ano_a').focus();
			return false;

}else if(document.getElementById('ano_b').value == ""){

			fun_msj('ingrese a que a&ntilde;o desea traspasar');
			document.getElementById('ano_b').focus();
			return false;
}else if((document.getElementById('ano_a').value) >=  (document.getElementById('ano_b').value)){

			fun_msj('el a&ntilde;o al cual se desea traspasar debe ser mayor al a&ntilde;o de donde se va a traspasar');
			document.getElementById('ano_b').focus();
			return false;
}

}//fin funtion





function valida_cscd01_requisicion(){
	if(document.getElementById('ano_requisicion').value==''){
			fun_msj('Por Favor ingrese el a&ntilde;o para la partida presupuestaria');
			document.getElementById('ano_requisicion').focus();
			return false;
	}else if(document.getElementById('ano_requisicion').value.length < 4){
			fun_msj('Por Favor ingrese un a&ntilde;o valido para la partida presupuestaria');
			document.getElementById('ano_requisicion').focus();
			return false;
	}else if(document.getElementById('numero_requisicion').value==''){
			fun_msj('Por Favor ingrese el n&uacute;mero de requisici&oacute;n');
			document.getElementById('numero_requisicion').focus();
			return false;
	}else if(document.getElementById('unidad_solicitante').value==''){
			fun_msj('Por Favor ingrese la unidad solicitante');
			document.getElementById('unidad_solicitante').focus();
			return false;
	}else if(document.getElementById('fecha_requisicion').value==''){
			fun_msj('Por Favor ingrese la fecha de la requisici&oacute;n');
			document.getElementById('fecha_requisicion').focus();
			return false;
	}else if(verifica_cierre_ano_ejecucion('fecha_requisicion')==false){
		fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA DE LA REQUISICI&Oacute;N NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
		return false;
	}else if(document.getElementById('descripcion').value==''){
			fun_msj('Por Favor ingrese la descripci&oacute;n');
			document.getElementById('descripcion').focus();
			return false;
	}else if(document.getElementById('codigo_cprecio').value==''){
			fun_msj('Por Favor ingrese el C&oacute;digo de Consulta de Precio');
			document.getElementById('codigo_cprecio').focus();
			return false;
	}else if(document.getElementById('cant_pp').value=='0'){
			fun_msj('Por Favor agregue por lo menos una partida presupuestaria');
			return false;
	}
}

function valida_cscd01_requisicion_pp(){
	if(document.getElementById('ano_partidas').value==''){

			fun_msj('Por Favor ingrese el a&ntilde;o para la partida presupuestaria');
			document.getElementById('ano_partidas').focus();
			return false;

	}else if(document.getElementById('ano_partidas').value.length < 4){

			fun_msj('Por Favor ingrese un a&ntilde;o valido para la partida presupuestaria');
			document.getElementById('ano_partidas').focus();
			return false;

	}else if(document.getElementById('seleccion_1').value==''){

			fun_msj('Por Favor Seleccione el Sector Correspondiente');
			document.getElementById('seleccion_1').focus();
			return false;

	}else if(document.getElementById('seleccion_2').value==''){

			fun_msj('Por Favor Seleccione el Programa Correspondiente');
			document.getElementById('seleccion_2').focus();
			return false;

	}else if(document.getElementById('seleccion_3').value==''){

			fun_msj('Por Favor Seleccione el Sub Programa Correspondiente');
			document.getElementById('seleccion_3').focus();
			return false;

	}else if(document.getElementById('seleccion_4').value==''){

			fun_msj('Por Favor Seleccione el Proyecto Correspondiente');
			document.getElementById('seleccion_4').focus();
			return false;

	}else if(document.getElementById('seleccion_5').value==''){

			fun_msj('Por Favor Seleccione la Actividad u Obra Correspondiente');
			document.getElementById('seleccion_5').focus();
			return false;

	}else if(document.getElementById('seleccion_6').value==''){

			fun_msj('Por Favor Seleccione la Partida Correspondiente');
			document.getElementById('seleccion_6').focus();
			return false;

	}else if(document.getElementById('seleccion_7').value==''){

			fun_msj('Por Favor Seleccione la Generica Correspondiente');
			document.getElementById('seleccion_7').focus();
			return false;

	}else if(document.getElementById('seleccion_8').value==''){

			fun_msj('Por Favor Seleccione la Especifica Correspondiente');
			document.getElementById('seleccion_8').focus();
			return false;

	}else if(document.getElementById('seleccion_9').value==''){

			fun_msj('Por Favor Seleccione la Sub Especifica Correspondiente');
			document.getElementById('seleccion_9').focus();
			return false;

	}else if(document.getElementById('seleccion_10').value==''){

			fun_msj('Por Favor Seleccione el Auxiliar Correspondiente');
			document.getElementById('seleccion_10').focus();
			return false;

	}else if((document.getElementById('monto_disponibilidad').value=='') || (document.getElementById('monto_disponibilidad').value=='0,00')){

			fun_msj('Lo siento esta partida presupuestaria no tiene un monto de disponibilidad valido');
			// document.getElementById('monto_disponibilidad').focus();
			return false;
	}
}//fin funtion

function cleanlistCp(){
	var confirma_lip = false;
	confirma_lip = confirm('Desea Realmente Eliminar Todos los Datos de la Lista?');
    if(confirma_lip==false){
		return false;
    }else if(confirma_lip==true){
    	return true;
    }
}

function report_cscd01_requisicion(){
	if(document.getElementById('ano_requisicion').value==''){
		fun_msj('Por Favor seleccione el a&ntilde;o.');
		document.getElementById('ano_requisicion').focus();
		return false;
	}else if(document.getElementById('numero_requisicion').value==''){
		fun_msj('Por Favor seleccione el n&uacute;mero de requisici&oacute;n.');
		document.getElementById('numero_requisicion').focus();
		return false;
	}else if(document.getElementById('unidad_solicitante').value==''){
		fun_msj('Por Favor seleccione la unidad solicitante.');
		document.getElementById('unidad_solicitante').focus();
		return false;
	}else{return true;}
}


function valida_cd_pp(){
	var monto_congelar = reemplazarPC(document.getElementById('monto_congelar').value);
	var monto_descongelar = reemplazarPC(document.getElementById('monto_descongelar').value);
	if(document.getElementById('ano_partidas').value==''){

			fun_msj('Por Favor ingrese el a&ntilde;o para la partida presupuestaria');
			document.getElementById('ano_partidas').focus();
			return false;

	}else if(document.getElementById('ano_partidas').value.length < 4){

			fun_msj('Por Favor ingrese un a&ntilde;o valido para la partida presupuestaria');
			document.getElementById('ano_partidas').focus();
			return false;

	}else if(document.getElementById('seleccion_1').value==''){

			fun_msj('Por Favor Seleccione el Sector Correspondiente');
			document.getElementById('seleccion_1').focus();
			return false;

	}else if(document.getElementById('seleccion_2').value==''){

			fun_msj('Por Favor Seleccione el Programa Correspondiente');
			document.getElementById('seleccion_2').focus();
			return false;

	}else if(document.getElementById('seleccion_3').value==''){

			fun_msj('Por Favor Seleccione el Sub Programa Correspondiente');
			document.getElementById('seleccion_3').focus();
			return false;

	}else if(document.getElementById('seleccion_4').value==''){

			fun_msj('Por Favor Seleccione el Proyecto Correspondiente');
			document.getElementById('seleccion_4').focus();
			return false;

	}else if(document.getElementById('seleccion_5').value==''){

			fun_msj('Por Favor Seleccione la Actividad u Obra Correspondiente');
			document.getElementById('seleccion_5').focus();
			return false;

	}else if(document.getElementById('seleccion_6').value==''){

			fun_msj('Por Favor Seleccione la Partida Correspondiente');
			document.getElementById('seleccion_6').focus();
			return false;

	}else if(document.getElementById('seleccion_7').value==''){

			fun_msj('Por Favor Seleccione la Generica Correspondiente');
			document.getElementById('seleccion_7').focus();
			return false;

	}else if(document.getElementById('seleccion_8').value==''){

			fun_msj('Por Favor Seleccione la Especifica Correspondiente');
			document.getElementById('seleccion_8').focus();
			return false;

	}else if(document.getElementById('seleccion_9').value==''){

			fun_msj('Por Favor Seleccione la Sub Especifica Correspondiente');
			document.getElementById('seleccion_9').focus();
			return false;

	}else if(document.getElementById('seleccion_10').value==''){

			fun_msj('Por Favor Seleccione el Auxiliar Correspondiente');
			document.getElementById('seleccion_10').focus();
			return false;

	}else if(eval(monto_congelar)>eval(0) && ((document.getElementById('monto_disponibilidad').value=='') || (document.getElementById('monto_disponibilidad').value=='0.00'))){

			fun_msj('Lo siento esta partida presupuestaria no tiene un monto de disponibilidad valido');
			// document.getElementById('monto_disponibilidad').focus();
			return false;
	}else if(eval(monto_descongelar)>eval(0) && (document.getElementById('monto_actual').value=='' || document.getElementById('monto_actual').value=='0.00')){

			fun_msj('Lo siento esta partida presupuestaria no posee un monto actual congelado valido . . . Es de '+document.getElementById('monto_actual').value);
			// document.getElementById('monto_disponibilidad').focus();
			return false;
	}else if(document.getElementById('monto_congelar').value=='' && document.getElementById('monto_descongelar').value==''){

			fun_msj('Por Favor ingrese el monto a congelar o descongelar.');
			// document.getElementById('monto_congelar').focus();
			return false;

	}else if(document.getElementById('monto_congelar').value=='0,00' && document.getElementById('monto_descongelar').value=='0,00'){

			fun_msj('Por Favor ingrese el monto a congelar o descongelar.');
			// document.getElementById('monto_descongelar').focus();
			return false;

	}else if(eval(monto_congelar)>eval(0) && eval(monto_descongelar)>eval(0)){

			fun_msj('Por Favor ingrese solo un monto ya sea el de congelar o descongelar . . .');
			// document.getElementById('monto_descongelar').focus();
			return false;

	}else if(eval(monto_congelar)>eval(document.getElementById('monto_disponibilidad').value)){
		fun_msj('El monto a congelar no puede ser mayor al monto disponible');
		setTimeout("fondoCampo('monto_congelar',2);", 1500);
		document.getElementById('monto_congelar').focus();
		return false;
	}else if(eval(monto_descongelar)>eval(document.getElementById('monto_actual').value)){
		fun_msj('El monto a descongelar no puede ser mayor al monto actual congelado a procesar . . . Es de '+document.getElementById('monto_actual').value);
		setTimeout("fondoCampo('monto_descongelar',2);", 1500);
		document.getElementById('monto_descongelar').focus();
		return false;
	}else{
		return true;
	}
}//fin funtion


function activar_tabla_planilla()
{

	var result_table_style = document.getElementById('tb_planilla').style;

	if(document.getElementById('tipo').value=='1' || document.getElementById('tipo').value=='2')
	{
		result_table_style.display = 'table';
	}
	else
	{
		result_table_style.display = 'none';
	}

}