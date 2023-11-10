function valida_cpop02_recurso_humano(){

	/*var denominacion_cargo = document.getElementById('denominacion_cargo').value;
	denominacion_cargo = denominacion_cargo.replace(/\./g, '');
	denominacion_cargo = denominacion_cargo.trim();

	if(document.getElementById('numero_cargos').value=="" || document.getElementById('numero_cargos').value==0){

			fun_msj('Ingrese la Cantidad de Cargos a Solicitar');
			document.getElementById('numero_cargos').focus();
			return false;

	}

	if(denominacion_cargo=="" || denominacion_cargo < 2){

			fun_msj('Ingrese la Denominación del Cargo');
			document.getElementById('denominacion_cargo').focus();
			return false;

	}*/

	if(document.getElementById('remuneracion_mensual').value==""){

			fun_msj('Ingrese la Remuneración Mensual');
			document.getElementById('remuneracion_mensual').focus();
			return false;

	}

	if(document.getElementById('situacion_laboral').value==""){

			fun_msj('Ingrese la Situacion Laboral del Cargo');
			document.getElementById('situacion_laboral').focus();
			return false;

	}

	if(document.getElementById('grado').value==""){

			fun_msj('Ingrese el Grado del Cargo');
			document.getElementById('grado').focus();
			return false;

	}

	if(document.getElementById('paso').value==""){

			fun_msj('Ingrese el Paso del Cargo');
			document.getElementById('paso').focus();
			return false;

	}

		return true;

}

function valida_cpop02_recurso_humano_pensionados(){


	if(document.getElementById('situacion_laboral').value==""){
		fun_msj('Ingrese la Situacion Laboral del Cargo');
		document.getElementById('situacion_laboral').focus();
		return false;
	}

	if(document.getElementById('numero_cargos').value=="" || document.getElementById('numero_cargos').value==0){
		fun_msj('Ingrese la Cantidad de Cargos a Solicitar');
		document.getElementById('numero_cargos').focus();
		return false;
	}

	if(document.getElementById('remuneracion_mensual').value==""){
	 	fun_msj('Ingrese la Remuneración Mensual');
	 	document.getElementById('remuneracion_mensual').focus();
	 	return false;
	}

	return true;

}

function valida_cnmd20_alimentacion_apoyo_institucional(){

	var cedula_identidad = document.getElementById('cedula_identidad').value;
	cedula_identidad = cedula_identidad.replace(/\./g, '');
	cedula_identidad = cedula_identidad.trim();

	var funcion = document.getElementById('funcion').value;
	funcion = funcion.replace(/\./g, '');
	funcion = funcion.trim();

	var primer_nombre = document.getElementById('primer_nombre').value;
	primer_nombre = primer_nombre.replace(/\./g, '');
	primer_nombre = primer_nombre.trim();

	var primer_apellido = document.getElementById('primer_apellido').value;
	primer_apellido = primer_apellido.replace(/\./g, '');
	primer_apellido = primer_apellido.trim();


	if(document.getElementById('cedula_identidad').value==""){
			fun_msj('Ingrese la cedula de identidad');
			document.getElementById('cedula_identidad').focus();
			return false;
	}

	if(primer_nombre=="" || primer_nombre < 2){
			fun_msj('Ingrese el primer nombre');
			document.getElementById('primer_nombre').focus();
			return false;
	}

	if(primer_apellido=="" || primer_apellido < 2){
			fun_msj('Ingrese el primer apellido');
			document.getElementById('primer_apellido').focus();
			return false;
	}

	if(funcion=="" || funcion < 2){
			fun_msj('Ingrese la función que desempeña');
			document.getElementById('funcion').focus();
			return false;
	}

		return true;

}