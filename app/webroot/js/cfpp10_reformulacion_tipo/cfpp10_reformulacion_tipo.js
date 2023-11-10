function valida_cfpp10_reform_tipo(){
	if(document.getElementById('cod_tipo_reformulacion').value==''){

			fun_msj('Por favor ingrese el codigo de la reformualci&oacute;n presupuestaria');
			document.getElementById('cod_tipo_reformulacion').focus();
			return false;

	}else if(document.getElementById('denominacion').value.length<4){

			fun_msj('Por favor ingrese la denominaci&oacute;n');
			document.getElementById('denominacion').focus();
			return false;

	}

}//fin valida_cfpp10_reform_tipo