function valida_cepp01_compromiso_beneficiario_cedula_rif(){
	if(document.getElementById('cedula').value==''){

	        fun_msj('Ingrese la cedula del Beneficiario');
			document.getElementById('cedula').focus();
			return false;

	}if(document.getElementById('denominacion').value==''){

	        fun_msj('Ingrese el nombre del Beneficiario');
			document.getElementById('denominacion').focus();
			return false;
	}
	document.getElementById('cedula').value="";
	document.getElementById('denominacion').value="";
}

function cepp01_compromiso_beneficiario_b_modificar(){
	document.getElementById('b_guardar').disabled=false;
	document.getElementById('b_modificar').disabled=true;
	document.getElementById('denominacion').readOnly=false;
	fun_msj2('Puede proceder a modificar el Beneficiario');
}


function valida_cepp01_compromiso_beneficiario_rif(){
	if(document.getElementById('rif').value==''){

	        fun_msj('Ingrese el rif del Beneficiario');
			document.getElementById('rif').focus();
			return false;

	}if(document.getElementById('denominacion').value==''){

	        fun_msj('Ingrese el nombre del Beneficiario');
			document.getElementById('denominacion').focus();
			return false;
	}
	document.getElementById('rif').value="";
	document.getElementById('denominacion').value="";
}