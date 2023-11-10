function valida_cspp01_reconocedor(){

	if(document.getElementById('cedula').value==''){
			fun_msj('Ingrese la C&eacute;dula del Reconocedor');
			document.getElementById('cedula').focus();
			return false;
	}else if(document.getElementById('nombre').value==''){
			fun_msj('Ingrese el Nombre del reconocedor');
			document.getElementById('nombre').focus();
			return false;
	}else if(document.getElementById('cargo').value==''){
			fun_msj('Ingrese el cargo del reconocedor');
			document.getElementById('cargo').focus();
			return false;
	}
}

function valida_reconocimiento(){

	if(document.getElementById('cedula_reconocimiento').value==''){
			fun_msj('Ingrese la C&eacute;dula del reconocedor');
			document.getElementById('cedula_reconocimiento').focus();
			return false;
	}else if(!document.getElementById('radio1_1').checked && !document.getElementById('radio1_2').checked ){
			fun_msj('Debe selecionar la APROBACI&Oacute;N');
			document.getElementById('observ_evaluacion').focus();
			return false;
		}else if(document.getElementById('observ_reconocimiento').value==''){
			fun_msj('Ingrese la observaci&oacute;n del reconocedor');
			document.getElementById('observ_reconocimiento').focus();
			return false;
	}
}
