

function caop05_registro_ordencompra_pago_valida(){

	for(i=0;i<document.getElementById('cuenta_ii').value;i++){
		if(eval(document.getElementById('cantidad2_'+i).value) == 0 || document.getElementById('cantidad2_'+i).value==''){
			fun_msj('LA CANTIDAD ENTREGADA NO PUEDE ESTAR VACIA');
			document.getElementById('cantidad2_'+i).value=0;
			document.getElementById('cantidad2_'+i).focus();
			return false;
		}
	}

if(document.getElementById('ano_orden_compra_pago').value==''){

			fun_msj('Inserte el a&ntilde;o de la nota de entrega');
			document.getElementById('ano_orden_compra_pago').focus();
			return false;

	}else if(document.getElementById('numero_orden_compra_pago').value==''){

			fun_msj('Inserte el n&uacute;mero de la nota de entrega');
			document.getElementById('numero_orden_compra_pago').focus();
			return false;

	}else if(document.getElementById('fecha_pago').value==''){

			fun_msj('Inserte la fecha de la nota de entrega');
			document.getElementById('fecha_pago').focus();
			return false;

	}else if(verifica_cierre_ano_ejecucion('fecha_pago')==false){
			fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA DE LA NOTA DE ENTREGA NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
			return false;
	}else if(diferenciaFecha(document.getElementById('fecha_pago').value, document.getElementById('fecha_ordencompra').value)){
		fun_msj('La fecha de entrega no puede ser menor a la fecha de la orden de compra');
			document.getElementById('fecha_pago').focus();
			return false;
	}else if(document.getElementById('observaciones').value==''){

			fun_msj('Inserte las observaci&oacute;nes de la nota de entrega');
			document.getElementById('observaciones').focus();
			return false;

	}else if(document.getElementById('cuenta_ii').value==0){

			fun_msj('Inserte un producto a la nota de entrega');
			document.getElementById('cuenta_ii').focus();
			return false;


	}//fin else



}//fin function


