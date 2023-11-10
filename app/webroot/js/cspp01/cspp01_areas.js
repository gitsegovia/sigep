
function valida_cspp01_areas(){

	if(document.getElementById('denominacion').value==''){
			fun_msj('Ingrese la denominaci&oacute;n');
			document.getElementById('denominacion').focus();
			return false;
	}
}


function valida_cspp01_derivada(){

	if(document.getElementById('deno_derivada').value==''){
			fun_msj('Ingrese la denominaci&oacute;n');
			document.getElementById('deno_derivada').focus();
			return false;
	}
}

