function valida_ccfp01_cuenta (){

	if(document.getElementById('select_1').value==''){

			fun_msj('Debe seleccionar el tipo de cuenta');
			document.getElementById('select_1').focus();
			return false;

	}if(document.getElementById('cod_tipo_cuenta').value==''){

			fun_msj('El c&oacute;digo del tipo de cuenta no puede estar vac&iacute;a');
			document.getElementById('cod_tipo_cuenta').focus();
			return false;

	}


	if(document.getElementById('select_2').value==''){

			fun_msj('Debe seleccionar la cuenta contable');
			document.getElementById('select_2').focus();
			return false;

	}if(document.getElementById('cod_cuenta_contable').value==''){

			fun_msj('El c&oacute;digo de la cuenta contable no puede estar vac&iacute;a');
			document.getElementById('cod_cuenta_contable').focus();
			return false;

	}if(document.getElementById('deno_cuenta_contable').value==''){

			fun_msj('La denominaci&oacute;n del tipo de cuenta contable no puede estar vac&iacute;a');
			document.getElementById('deno_cuenta_contable').focus();
			return false;

	}

	/*if(document.getElementById('concepto_cuentacontable').value==''){

			fun_msj('El concepto del tipo de cuenta contable no puede estar vac&iacute;a');
			document.getElementById('concepto_cuentacontable').focus();
			return false;

	}*/
}// fin function

function valida_ccfp01_cuenta_modificar(){

	if(document.getElementById('cod_cuenta_contable').value==''){

			fun_msj('El c&oacute;digo de la cuenta contable no puede estar vac&iacute;a');
			document.getElementById('cod_cuenta_contable').focus();
			return false;

	}if(document.getElementById('deno_cuenta_contable').value==''){

			fun_msj('La denominaci&oacute;n del tipo de cuenta contable no puede estar vac&iacute;a');
			document.getElementById('deno_cuenta_contable').focus();
			return false;

	}
	/*if(document.getElementById('concepto_cuentacontable').value==''){

			fun_msj('El concepto del tipo de cuenta contable no puede estar vac&iacute;a');
			document.getElementById('concepto_cuentacontable').focus();
			return false;
	}*/
}


function valida_ccfp01_subcuenta2(){

if(document.getElementById('cod_subcuenta_contable').value==''){

			fun_msj('El c&oacute;digo de la subcuenta contable no puede estar vac&iacute;a');
			document.getElementById('cod_subcuenta_contable').focus();
			return false;

	}else if(document.getElementById('deno_subcuenta_contable').value==''){

			fun_msj('La denominaci&oacute;n del tipo de subcuenta contable no puede estar vac&iacute;a');
			document.getElementById('deno_subcuenta_contable').focus();
			return false;

	}


}

function valida_ccfp01_subcuenta_modificar2(){

if(document.getElementById('deno_subcuenta_contable').value==''){

			fun_msj('El c&oacute;digo de la subcuenta contable no puede estar vac&iacute;a');
			document.getElementById('deno_subcuenta_contable').focus();
			return false;

	}else if(document.getElementById('concepto_subcuentacontable').value==''){

			fun_msj('La denominaci&oacute;n del tipo de subcuenta contable no puede estar vac&iacute;a');
			document.getElementById('concepto_subcuentacontable').focus();
			return false;

	}


}