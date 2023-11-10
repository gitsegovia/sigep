function cnmp15_act_escalas_valida(){
	if(document.getElementById('fecha_actual').value == ""){
        fun_msj('Seleccione la fecha actual');
		document.getElementById('fecha_actual').focus();
		return false;
	}else if(document.getElementById('fecha_actualizar').value == ""){
        fun_msj('Seleccione la fecha actualizar');
		document.getElementById('fecha_actualizar').focus();
		return false;
	}else if(verifica_cierre_ano_ejecucion('fecha_actualizar')==false){
		fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA ACTUALIZAR NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
		return false;
	}else{
		var fecha_a=document.getElementById('fecha_actual').value;
		var fecha_b=document.getElementById('fecha_actualizar').value;
		a_fecha=fecha_a.split('/');
		b_fecha=fecha_b.split('/');
		fecha_a=a_fecha[2];
		fecha_b=b_fecha[2];
		if(eval(fecha_a)==eval(fecha_b)){
			fun_msj('El a&ntilde;o de las fechas no pueden ser iguales');
			return false;
		}
	}
}


/*
function cnmp15_act_escalas_valida22(){
	if(document.getElementById('tipo_proceso_2') && document.getElementById('tipo_proceso_2').checked==true && document.getElementById('cod_nomina').value == ""){
    	fun_msj('Por favor seleccione el tipo de n&oacute;mina');
		document.getElementById('ano').focus();
		return false;
	}else if(document.getElementById('fecha_actual').value == ""){
        fun_msj('Seleccione la fecha actual');
		document.getElementById('fecha_actual').focus();
		return false;
	}else if(document.getElementById('fecha_actualizar').value == ""){
        fun_msj('Seleccione la fecha actualizar');
		document.getElementById('fecha_actualizar').focus();
		return false;
	}else if(verifica_cierre_ano_ejecucion('fecha_actualizar')==false){
		fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA ACTUALIZAR NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
		return false;
	}else{
		var fecha_a=document.getElementById('fecha_actual').value;
		var fecha_b=document.getElementById('fecha_actualizar').value;
		a_fecha=fecha_a.split('/');
		b_fecha=fecha_b.split('/');
		fecha_a=a_fecha[2];
		fecha_b=b_fecha[2];
		if(eval(fecha_a)==eval(fecha_b)){
			fun_msj('El a&ntilde;o de las fechas no pueden ser iguales');
			return false;
		}
	}
}
*/
