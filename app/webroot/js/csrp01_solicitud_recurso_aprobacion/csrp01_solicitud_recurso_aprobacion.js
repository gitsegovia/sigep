function valida_csrp01_solicitud_recurso_aprobacion(){

 if(document.getElementById('select_1').value==""){

			fun_msj('Seleccione la Entidad Bancaria');
			document.getElementById('select_1').focus();
			return false;
}else  if(document.getElementById('select_2').value==""){

			fun_msj('Seleccione la Sucursal Bancaria');
			document.getElementById('select_2').focus();
			return false;
}else  if(document.getElementById('select_3').value==""){

			fun_msj('Seleccione El Numero de Cuenta');
			document.getElementById('select_3').focus();
			return false;
}else  if(document.getElementById('numero_cheque').value==""){

			fun_msj('Seleccione El Numero de Cheque');
			document.getElementById('numero_cheque').focus();
			return false;
}else  if(document.getElementById('fecha_cheque').value==""){

			fun_msj('Seleccione la Fecha Cheque');
			document.getElementById('fecha_cheque').focus();
			return false;
}else  if(document.getElementById('monto_cheque').value==""){

			fun_msj('Seleccione El Monto del Cheque');
			document.getElementById('monto_cheque').focus();
			return false;
}


}


