function valida_cpop04_problemas_areas_gestion(){

	var problema_area_gestion = document.getElementById('problema_area_gestion').value;
	problema_area_gestion = problema_area_gestion.replace(/\./g, '');
	problema_area_gestion = problema_area_gestion.trim();

	if(document.getElementById('ano').value==""){

			fun_msj('Ingrese el año a formular');
			document.getElementById('ano').focus();
			return false;

	}

	if(document.getElementById('numero_objetivo').value==""){

			fun_msj('Seleccione un Objetivo a Relacionar');
			document.getElementById('numero_objetivo').focus();
			return false;

	}

	if(problema_area_gestion=="" || problema_area_gestion < 2){

			fun_msj('Ingrese la Descripción del Problema o Área de Gestión');
			document.getElementById('problema_area_gestion').focus();
			return false;

	}

	return true;

}