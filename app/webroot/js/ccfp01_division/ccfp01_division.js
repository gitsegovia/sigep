function valida_ccfp01_division(){

   if(document.getElementById('select_1').value==''){

	        fun_msj('Debe seleccionar el tipo de cuenta');
			document.getElementById('select_1').focus();
			return false;

	}

	if(document.getElementById('cod_tipo_cuenta').value==''){

	        fun_msj('El c&oacute;digo del tipo de cuenta no puede estar vacio');
			document.getElementById('cod_tipo_cuenta').focus();
			return false;

	}if(document.getElementById('deno_tipo_cuenta').value==''){

	        fun_msj('La denominaci&oacute;n del tipo de cuenta no puede estar vac&iacute;a');
			document.getElementById('deno_tipo_cuenta').focus();
			return false;

	}


	/*if(document.getElementById('concepto_tipo_cuenta').value==''){

	        fun_msj('El concepto del tipo de cuenta no puede estar vac&iacute;o');
			document.getElementById('concepto_tipo_cuenta').focus();
			return false;

	}*/



	if(document.getElementById('select_2').value==''){

	        fun_msj('Debe seleccionar la Cuenta Contable');
			document.getElementById('select_2').focus();
			return false;

	}if(document.getElementById('cod_cuenta_contable').value==''){

	        fun_msj('El c&oacute;digo de la Cuenta Contable no puede estar vacio');
			document.getElementById('cod_cuenta_contable').focus();
			return false;

	}if(document.getElementById('deno_cuenta_contable').value==''){

	        fun_msj('La denominaci&oacute;n de la Cuenta Contable no puede estar vacia');
			document.getElementById('deno_cuenta_contable').focus();
			return false;

	}

	/*if(document.getElementById('concepto_cuentacontable').value==''){

	        fun_msj('El Concepto de la Cuenta Contable no puede estar vacio');
			document.getElementById('concepto_cuentacontable').focus();
			return false;

	}*/


	if(document.getElementById('select_3').value==''){

	        fun_msj('Debe seleccionar la Subcuenta Contable');
			document.getElementById('select_3').focus();
			return false;

	}if(document.getElementById('cod_subcuenta_contable').value==''){

	        fun_msj('El c&oacute;digo de la Subcuenta Contable no puede estar vacio');
			document.getElementById('cod_subcuenta_contable').focus();
			return false;

	}if(document.getElementById('deno_subcuenta_contable').value==''){

	        fun_msj('La denominaci&oacute;n de la Subcuenta Contable no puede estar vacia');
			document.getElementById('deno_subcuenta_contable').focus();
			return false;

	}


	/*if(document.getElementById('concepto_subcuentacontable').value==''){

	        fun_msj('El Concepto de la Subcuenta Contable no puede estar vacio');
			document.getElementById('concepto_subcuentacontable').focus();
			return false;

	}*/


	if(document.getElementById('cod_div_contable').value==''){

	        fun_msj('El c&oacute;digo de la Divisi&oacute;n Estadistica Contable no puede estar vacio');
			document.getElementById('cod_div_contable').focus();
			return false;

	}if(document.getElementById('deno_div_contable').value==''){

	        fun_msj('La denominaci&oacute;n de la Divisi&oacute;n Estadistica Contable no puede estar vacia');
			document.getElementById('deno_div_contable').focus();
			return false;

	}

	/*if(document.getElementById('concepto_div_contable').value==''){

	        fun_msj('El Concepto de la Divisi&oacute;n Estadistica Contable no puede estar vacio');
			document.getElementById('concepto_div_contable').focus();
			return false;

	}*/
}//valida_ccfp01_division



function valida_ccfp01_division_modificar(){

	if(document.getElementById('cod_tipo_cuenta').value==''){

	        fun_msj('El c&oacute;digo del tipo de cuenta no puede estar vacio');
			document.getElementById('cod_tipo_cuenta').focus();
			return false;

	}

	if(document.getElementById('cod_cuenta_contable').value==''){

	        fun_msj('El c&oacute;digo de la Cuenta Contable no puede estar vacio');
			document.getElementById('cod_cuenta_contable').focus();
			return false;

	}

	if(document.getElementById('cod_subcuenta_contable').value==''){

	        fun_msj('El c&oacute;digo de la Subcuenta Contable no puede estar vacio');
			document.getElementById('cod_subcuenta_contable').focus();
			return false;

	}

	if(document.getElementById('cod_div_contable').value==''){

	        fun_msj('El c&oacute;digo de la Divisi&oacute;n Estadistica Contable no puede estar vacio');
			document.getElementById('cod_div_contable').focus();
			return false;

	}if(document.getElementById('deno_div_contable').value==''){

	        fun_msj('La denominaci&oacute;n de la Divisi&oacute;n Estadistica Contable no puede estar vacia');
			document.getElementById('deno_div_contable').focus();
			return false;

	}if(document.getElementById('concepto_div_contable').value==''){

	        fun_msj('El Concepto de la Divisi&oacute;n Estadistica Contable no puede estar vacio');
			document.getElementById('concepto_div_contable').focus();
			return false;

	}
}//valida_ccfp01_division_modificar