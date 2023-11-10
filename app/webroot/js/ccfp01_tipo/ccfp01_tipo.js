function valida_ccfp01_tipo_(){

 if(document.getElementById('codigo_tipo').value==''){

			fun_msj('El c&oacute;digo del tipo de cuenta contable no puede ser vac&iacute;a');
			document.getElementById('codigo_tipo').focus();
			return false;

	}
	 if((document.getElementById('codigo_tipo').value!=1)&&(document.getElementById('codigo_tipo').value!=2)){

			fun_msj('El c&oacute;digo del tipo de cuenta contable es diferente de 1 y de 2');
			document.getElementById('codigo_tipo').focus();
			return false;

	} if(document.getElementById('denominacion').value==''){

			fun_msj('La denominaci&oacute;n de la cuenta contable no puede ser vac&iacute;a');
			document.getElementById('denominacion').focus();
			return false;

	}

}//fin funtion




function valida_reporte_contabilidad(){

 if(eval(document.getElementById('firma').value)==1){

			fun_msj('Por favor debe registrar a los firmantes');
			document.getElementById('nombre_primera_firma').focus();
			return false;

	}else if(document.getElementById('ano').value==''){

			fun_msj('Por favor debe ingresar el a&tilde;o');
			document.getElementById('ano').focus();
			return false;

	}else if(document.getElementById('mes').value==''){

			fun_msj('Por favor debe seleccionar el mes');
			document.getElementById('mes').focus();
			return false;

	}

}//fin funtion


function valida_firmas_contabilidad(){

	if(document.getElementById('nombre_primera_firma').value==''){

			fun_msj('ingrese el nombre de la primera firma');
			document.getElementById('nombre_primera_firma').focus();
			return false;

	}else if(document.getElementById('cargo_primera_firma').value==''){

			fun_msj('ingrese el cargo de la primera firma');
			document.getElementById('cargo_primera_firma').focus();
			return false;

	}else if(document.getElementById('nombre_segunda_firma').value==''){

			fun_msj('ingrese el nombre de la segunda firma');
			document.getElementById('nombre_segunda_firma').focus();
			return false;

	}else if(document.getElementById('cargo_segunda_firma').value==''){

			fun_msj('ingrese el cargo de la segunda firma');
			document.getElementById('cargo_segunda_firma').focus();
			return false;

	}


}



