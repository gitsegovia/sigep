function valida_cpod06_distribucion_ingresos_propios(){

	if(document.getElementById('ano').value==""){

			fun_msj('Ingrese el año a formular');
			document.getElementById('ano').focus();
			return false;

	}

	if(!document.getElementById("radio_GESTION").checked){

			fun_msj('Seleccione un Tipo de Proyecto');
			return false;

	}

	if(document.getElementById('numero_proyecto').value==""){

			fun_msj('Seleccione un Proyecto');
			document.getElementById('numero_proyecto').focus();
			return false;

	}

	if(document.getElementById('select_6').value == ""){
			fun_msj('Seleccione Partida');
			document.getElementById('select_6').focus();
			return false;
	}else if(document.getElementById('select_7').value == ""){
			fun_msj('Seleccione Generica');
			document.getElementById('select_7').focus();
			return false;
	}else if(document.getElementById('select_8').value == ""){
			fun_msj('Seleccione Especifica');
			document.getElementById('select_8').focus();
			return false;
	}else if(document.getElementById('select_9').value == ""){
			fun_msj('Seleccione Sub-Especifica');
			document.getElementById('select_9').focus();
			return false;
	}else if(document.getElementById('select_10').value == "" && document.getElementById('select_10').length >=1){
			fun_msj('Seleccione Auxiliar');
			document.getElementById('select_10').focus();
			return false;
	}

	if(document.getElementById('monto').value=='' || document.getElementById('monto').value=='0,00'){
			fun_msj('Inserte un monto correcto.');
			document.getElementById('monto').focus();
			return false;
	}

	return true;

}