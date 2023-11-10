function valida_operaciones(){
	if(document.getElementById('cod_tipo_nomina').value==''){

			fun_msj('Por favor seleccione el tipo de n&oacute;mina');
			document.getElementById('cod_tipo_nomina').focus();
			return false;

	}else if(document.getElementById('cuanto').value==''){

			fun_msj('Por favor ingrese por cuanto se desea realizar la operaci&oacute;n');
			document.getElementById('cuanto').focus();
			return false;

	}

}//fin valida_cfpp10_reform_tipo



function valida_operaciones2(){
	if(document.getElementById('cod_tipo_nomina').value==''){

			fun_msj('Por favor seleccione el tipo de n&oacute;mina');
			document.getElementById('cod_tipo_nomina').focus();
			return false;

	}else if(document.getElementById('cod_transax').value==''){

			fun_msj('Por favor seleccione la transacci&oacute;n');
			document.getElementById('cod_transax').focus();
			return false;

	}else if(document.getElementById('cuanto').value==''){

			fun_msj('Por favor ingrese por cuanto se desea realizar la operaci&oacute;n');
			document.getElementById('cuanto').focus();
			return false;

	}

}//fin valida_cfpp10_reform_tipo

function valida_operaciones3(){
	if(document.getElementById('cod_tipo_nomina').value==''){

			fun_msj('Por favor seleccione el tipo de n&oacute;mina');
			document.getElementById('cod_tipo_nomina').focus();
			return false;

	}else if(document.getElementById('cod_asig').value==''){

			fun_msj('Por favor seleccione la asignaci&oacute;n');
			document.getElementById('cod_asig').focus();
			return false;

	}
}//fin valida_cfpp10_reform_tipo

function valida_operaciones4(){
	if(document.getElementById('cod_tipo_nomina').value==''){

			fun_msj('Por favor seleccione el tipo de n&oacute;mina');
			document.getElementById('cod_tipo_nomina').focus();
			return false;

	}else if(document.getElementById('cod_transax').value==''){

			fun_msj('Por favor seleccione la transacci&oacute;n');
			document.getElementById('cod_transax').focus();
			return false;

	}

}//fin valida_cfpp10_reform_tipo

function valida_operaciones5(){
	if(document.getElementById('cod_tipo_nomina').value==''){

			fun_msj('Por favor seleccione el tipo de n&oacute;mina');
			document.getElementById('cod_tipo_nomina').focus();
			return false;

	}

}//fin valida_cfpp10_reform_tipo

function valida_pasar_transac_hn(){
	if(document.getElementById('codigo_nomina').value==''){

			fun_msj('Por favor seleccione el tipo de n&oacute;mina');
			document.getElementById('cod_tipo_nomina').focus();
			return false;

	}else if(document.getElementById('numero_nomina').value==''){

			fun_msj('Por favor seleccione el n&uacute;mero de la n&oacute;mina');
			document.getElementById('cod_numero_nom').focus();
			return false;

	}else if(document.getElementById('codigo_transa').value==''){

			fun_msj('Por favor seleccione la transacci&oacute;n');
			// document.getElementById('codigo_transa').focus();
			return false;
	}
}//fin valida_pasar_transac_hn
