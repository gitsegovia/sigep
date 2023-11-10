
function valida_cspp02_datos_solicitante(){

	if(document.getElementById('cedula').value==''){
			fun_msj('Ingrese la C&eacute;dula del Solicitante');
			document.getElementById('cedula').focus();
			return false;
	}else if(document.getElementById('nombre').value==''){
			fun_msj('Ingrese el Nombre del Solicitante');
			document.getElementById('nombre').focus();
			return false;
	}else if(document.getElementById('estado').value==''){
			fun_msj('Ingrese el Estado en la DIRECCI&oacute;N DEL SOLICITANTE');
			document.getElementById('estado').focus();
			return false;
	}else if(document.getElementById('municipio').value==''){
			fun_msj('Ingrese el Municipio en la DIRECCI&oacute;N DEL SOLICITANTE');
			document.getElementById('municipio').focus();
			return false;
	}else if(document.getElementById('parroquia').value==''){
			fun_msj('Ingrese la Parroquia en la DIRECCI&oacute;N DEL SOLICITANTE');
			document.getElementById('parroquia').focus();
			return false;
	}else if(document.getElementById('centropoblado').value==''){
			fun_msj('Ingrese el Centro Poblado en la DIRECCI&oacute;N DEL SOLICITANTE');
			document.getElementById('centropoblado').focus();
			return false;
	}else if(document.getElementById('calle').value==''){
			fun_msj('Ingrese la CALLE / AVDA en la DIRECCI&oacute;N DEL SOLICITANTE');
			document.getElementById('calle').focus();
			return false;
	}else if(document.getElementById('direccion').value==''){
			fun_msj('Ingrese el complemento de DIRECCI&oacute;N DEL SOLICITANTE');
			document.getElementById('direccion').focus();
			return false;
	}else if(document.getElementById('telefono').value==''){
			fun_msj('Ingrese el tel&eacute;fono DEL SOLICITANTE');
			document.getElementById('telefono').focus();
			return false;
	}
}

