function valida_cpop04_objetivos(){

	var objetivo = document.getElementById('objetivo').value;
	objetivo = objetivo.replace(/\./g, '');
	objetivo = objetivo.trim();
	
	if(document.getElementById('ano').value==""){

			fun_msj('Ingrese el año a formular');
			document.getElementById('ano').focus();
			return false;

	}

	if(!document.getElementById("radio_GESTION_GESTION").checked){

			fun_msj('Seleccione un Tipo de Proyecto');
			return false;

	}

	if(document.getElementById('numero_proyecto').value==""){

			fun_msj('Seleccione un Proyecto');
			document.getElementById('numero_proyecto').focus();
			return false;

	}

	if(objetivo=="" || objetivo < 2){

			fun_msj('Ingrese el objetivo');
			document.getElementById('objetivo').focus();
			return false;

	}

	return true;

}