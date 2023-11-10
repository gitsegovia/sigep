
function valida_cspp03_planteamientos(){

	if(document.getElementById('cedula').value==''){
			fun_msj('Ingrese la C&eacute;dula del Solicitante');
			document.getElementById('cedula').focus();
			return false;
	}else if(document.getElementById('fecha').value==''){
			fun_msj('Ingrese la fecha de la solicitud');
			document.getElementById('fecha').focus();
			return false;
	}else if(verifica_cierre_ano_ejecucion('fecha')==false){
			fun_msj('lo siento, el a&ntilde;o de la fecha no corresponde al a&ntilde;o de ejecuci&oacute;n');
			document.getElementById('fecha').focus();
			return false;
	}else if(document.getElementById('nombre').value==''){
			fun_msj('Ingrese el Nombre del Solicitante');
			document.getElementById('nombre').focus();
			return false;
	}else if(document.getElementById('cod_principal').value==''){
			fun_msj('Ingrese el area primaria de la solicitud');
			document.getElementById('cod_principal').focus();
			return false;
	}else if(document.getElementById('cod_derivada').value==''){
			fun_msj('Ingrese el area derivada de la solicitud');
			document.getElementById('cod_derivada').focus();
			return false;
	}else if(document.getElementById('solicitud').value==''){
			fun_msj('Ingrese el planteamiento de la solicitud');
			document.getElementById('solicitud').focus();
			return false;
	}
}

