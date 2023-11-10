
function valida_cspp01_ejecutores(){

	if(document.getElementById('cedula').value==''){
			fun_msj('Ingrese la C&eacute;dula del Ejecutor');
			document.getElementById('cedula').focus();
			return false;
	}else if(document.getElementById('nombre').value==''){
			fun_msj('Ingrese el Nombre del Ejecutor');
			document.getElementById('nombre').focus();
			return false;
	}else if(document.getElementById('cargo').value==''){
			fun_msj('Ingrese el cargo del Ejecutor');
			document.getElementById('cargo').focus();
			return false;
	}
}



function valida_ejecucion(){

	if(document.getElementById('cedula_ejecutor').value==''){
			fun_msj('Ingrese la C&eacute;dula del ejecutor');
			document.getElementById('cedula_ejecutor').focus();
			return false;
	}else if(document.getElementById('observ_ejecutor').value==''){
			fun_msj('Ingrese la observaci&oacute;n del ejecutor');
			document.getElementById('observ_ejecutor').focus();
			return false;
	}else if(document.getElementById('monto').value==''){
			fun_msj('Ingrese el monto de la ejecutoci&oacute;n');
			document.getElementById('monto').focus();
			return false;
	}else if(!document.getElementById('radio2_1').checked && !document.getElementById('radio2_2').checked ){
			fun_msj('Debe selecionar la APROBACI&Oacute;N');
			document.getElementById('observ_evaluacion').focus();
			return false;

	}else if(document.getElementById('radio2_1').checked==true){
				if(document.getElementById('monto').value=='0,00'){
					fun_msj('Ingrese un monto valido');
					document.getElementById('monto').focus();
					return false;
				}
	}
}