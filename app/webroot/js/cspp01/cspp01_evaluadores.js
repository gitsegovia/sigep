
function valida_cspp01_evaluadores(){

	if(document.getElementById('cedula').value==''){
			fun_msj('Ingrese la C&eacute;dula del Evaluador');
			document.getElementById('cedula').focus();
			return false;
	}else if(document.getElementById('nombre').value==''){
			fun_msj('Ingrese el Nombre del Evaluador');
			document.getElementById('nombre').focus();
			return false;
	}else if(document.getElementById('cargo').value==''){
			fun_msj('Ingrese el cargo del Evaluador');
			document.getElementById('cargo').focus();
			return false;
	}
}

function valida_evaluacion(){

	if(document.getElementById('cedula_evaluador').value==''){
			fun_msj('Ingrese la C&eacute;dula del Evaluador');
			document.getElementById('cedula_evaluador').focus();
			return false;
	}else if(!document.getElementById('radio_1').checked && !document.getElementById('radio_2').checked ){
			fun_msj('Debe selecionar la APROBACI&Oacute;N');
			document.getElementById('observ_evaluacion').focus();
			return false;
	}else if(document.getElementById('observ_evaluacion').value==''){
			fun_msj('Ingrese la observaci&oacute;n del Evaluador');
			document.getElementById('observ_evaluacion').focus();
			return false;
	}
}
