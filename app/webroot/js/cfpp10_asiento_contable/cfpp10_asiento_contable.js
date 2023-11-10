
function valida_agregar_asiento_detalle(){
	if(document.getElementById('dia').value==''){
			fun_msj('Debe ingresar el d&iacute;a');
			document.getElementById('dia').focus();
			return false;
	}else if(eval(document.getElementById('dia').value) <=0 || eval(document.getElementById('dia').value) > 31){
			fun_msj('Debe ingresar un d&iacute;a valido');
			document.getElementById('dia').value='';
			document.getElementById('dia').focus();
			return false;
	}else if(document.getElementById('mes').value==''){
			fun_msj('Seleccione el mes');
			document.getElementById('mes').focus();
			return false;
	}else if(document.getElementById('ano').value==''){
			fun_msj('Debe ingresar el a&ntilde;o');
			document.getElementById('ano').focus();
			return false;
	}else if(document.getElementById('select').value==''){
			fun_msj('Seleccione el tipo de documento');
			document.getElementById('select').focus();
			return false;
	}else if(document.getElementById('numero').value==''){
			fun_msj('debe ingresar el n&uacute;mero del documento');
			document.getElementById('numero').focus();
			return false;
	}else if(document.getElementById('fecha').value==''){
			fun_msj('debe seleccionar la fecha');
			document.getElementById('fecha').focus();
			return false;
	}else if(verifica_cierre_ano_ejecucion('fecha')==false){
			fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
			return false;
	}else if(document.getElementById('linea').value==''){
			fun_msj('debe existir el n&uacute;mero de linea');
			document.getElementById('linea').focus();
			return false;
	}if((document.getElementById('radio_tipo_1').checked==false) && (document.getElementById('radio_tipo_2').checked==false)){
			fun_msj('Seleccione el tipo de movimiento');
			return false;
	}else if(document.getElementById('radio_tipo_1').checked==true && (document.getElementById('debe').value=='' || document.getElementById('debe').value=='0,00')){
			fun_msj('debe ingresar un monto valido para el debe');
			document.getElementById('debe').focus();
			return false;
	}else if(document.getElementById('radio_tipo_2').checked==true && (document.getElementById('haber').value=='' || document.getElementById('haber').value=='0,00')){
			fun_msj('debe ingresar un monto valido para el haber');
			document.getElementById('haber').focus();
			return false;
	}else if(document.getElementById('concepto').value==''){
			fun_msj('debe ingresar el concepto del asiento');
			document.getElementById('concepto').focus();
			return false;
	}

}


function valida_registro_asiento_contable12(){
	if(document.getElementById('dia').value==''){
			fun_msj('Debe ingresar el d&iacute;a');
			document.getElementById('dia').focus();
			return false;
	}else if(eval(document.getElementById('dia').value) <=0 || eval(document.getElementById('dia').value) > 31){
			fun_msj('Debe ingresar un d&iacute;a valido');
			document.getElementById('dia').value='';
			document.getElementById('dia').focus();
			return false;
	}else if(document.getElementById('select').value==''){
			fun_msj('Seleccione el tipo de documento');
			document.getElementById('select').focus();
			return false;
	}else if(document.getElementById('numero').value==''){
			fun_msj('debe ingresar el n&uacute;mero del documento');
			document.getElementById('numero').focus();
			return false;
	}else if(document.getElementById('concepto').value==''){
			fun_msj('debe ingresar el concepto del asiento');
			document.getElementById('concepto').focus();
			return false;
	}else if(eval(document.getElementById('monto_debe').value) != eval(document.getElementById('monto_haber').value)){
			fun_msj('el monto total del debe y el haber deben ser iguales');
			document.getElementById('concepto').focus();
			return false;
	}

}


function valida_concepto_registro_asiento_contable123(){
	if(document.getElementById('concepto').value==''){
			fun_msj('debe ingresar el concepto del asiento');
			document.getElementById('concepto').focus();
			return false;
	}

}


function confirma_limpiar_lista_completa(){
	var confirma_lip = false;
	confirma_lip = confirm('*** Desea Realmente Eliminar todos los Registros de la Lista? ***');
    if(confirma_lip==false){
		return false;
    }else if(confirma_lip==true){
    	return true;
    }
}
