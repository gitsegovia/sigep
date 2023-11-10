function valida_cmcp01_registro_trimestre (){

	if(document.getElementById('file_trimestre').value==''){

			fun_msj('Debe seleccionar un documento');
			document.getElementById('file_trimestre').focus();
			return false;

	}
	
	return true;

}// fin function

function valida_cierre_trimestre_memoria_cuenta(){
	if(document.getElementById("select_1").value==''){
		fun_msj('seleccione el c&oacute;digo de la dependencia');
		document.getElementById('select_1').focus();
		return false;
	}else if(document.getElementById("mes_1").value==''){
		fun_msj('seleccione el trimestre');
		document.getElementById('mes_1').focus();
		return false;
	}
	return true;

}

function valida_cierre_trimestre_memoria_cuenta2(){
	if(document.getElementById("trimestre_solicitud").value==''){
		fun_msj('seleccione el c&oacute;digo de la dependencia');
		document.getElementById('select_1').focus();
		return false;
	}
	return true;

}