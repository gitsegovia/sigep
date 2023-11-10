function valida_cnmp06_profesiones(){

 if(document.getElementById('denominacion').value==''){
			fun_msj('Inserte la Denominaci&oacute;n');
			document.getElementById('denominacion').focus();
			return false;
}
}



function cnmp06_modificar(){
	document.getElementById('b_guardar').disabled=false;
	document.getElementById('b_modificar').disabled=true;
	document.getElementById('denominacion').readOnly=false;
	fun_msj2('Puede proceder a modificar la Profesion');
}

function valida_cnmp06_profesiones2(){
	if(document.getElementById('cedula').value==''){

	        fun_msj('Ingrese el Codigo de la Profesion');
			document.getElementById('cedula').focus();
			return false;

	}if(document.getElementById('denominacion').value==''){

	        fun_msj('Ingrese la denominacion de la Profesion');
			document.getElementById('denominacion').focus();
			return false;
	}
	document.getElementById('cedula').value="";
	document.getElementById('denominacion').value="";
}


///////////////////////////////////////////////////////////////////////


function valida_cnmp06_clubes2(){
	if(document.getElementById('cedula').value==''){

	        fun_msj('Ingrese el Codigo del Club');
			document.getElementById('cedula').focus();
			return false;

	}if(document.getElementById('denominacion').value==''){

	        fun_msj('Ingrese la denominacion del Club');
			document.getElementById('denominacion').focus();
			return false;
	}
	document.getElementById('cedula').value="";
	document.getElementById('denominacion').value="";
}


function cnmp06_modificar_clubes(){
	document.getElementById('b_guardar').disabled=false;
	document.getElementById('b_modificar').disabled=true;
	document.getElementById('denominacion').readOnly=false;
	fun_msj2('Puede proceder a modificar el club');
}



////////////////////////////////////////////////////////////////////

function valida_cnmp06_deportes2(){
	if(document.getElementById('cedula').value==''){

	        fun_msj('Ingrese el Codigo del Deporte');
			document.getElementById('cedula').focus();
			return false;

	}if(document.getElementById('denominacion').value==''){

	        fun_msj('Ingrese la denominacion del Deporte');
			document.getElementById('denominacion').focus();
			return false;
	}
	document.getElementById('cedula').value="";
	document.getElementById('denominacion').value="";
}


function cnmp06_modificar_deportes(){
	document.getElementById('b_guardar').disabled=false;
	document.getElementById('b_modificar').disabled=true;
	document.getElementById('denominacion').readOnly=false;
	fun_msj2('Puede proceder a modificar el Deporte');
}




////////////////////////////////////////////////////////////////////

function valida_cnmp06_colores2(){
	if(document.getElementById('cedula').value==''){

	        fun_msj('Ingrese el Codigo del Color');
			document.getElementById('cedula').focus();
			return false;

	}if(document.getElementById('denominacion').value==''){

	        fun_msj('Ingrese la denominacion del Color');
			document.getElementById('denominacion').focus();
			return false;
	}
	document.getElementById('cedula').value="";
	document.getElementById('denominacion').value="";
}


function cnmp06_modificar_colores(){
	document.getElementById('b_guardar').disabled=false;
	document.getElementById('b_modificar').disabled=true;
	document.getElementById('denominacion').readOnly=false;
	fun_msj2('Puede proceder a modificar el Color');
}






////////////////////////////////////////////////////////////////////

function valida_cnmp06_colegio_profesional2(){
	if(document.getElementById('cedula').value==''){

	        fun_msj('Ingrese el Codigo del Colegio');
			document.getElementById('cedula').focus();
			return false;

	}if(document.getElementById('denominacion').value==''){

	        fun_msj('Ingrese la denominacion del Colegio');
			document.getElementById('denominacion').focus();
			return false;
	}
	document.getElementById('cedula').value="";
	document.getElementById('denominacion').value="";
}


function cnmp06_modificar_colegios(){
	document.getElementById('b_guardar').disabled=false;
	document.getElementById('b_modificar').disabled=true;
	document.getElementById('denominacion').readOnly=false;
	fun_msj2('Puede proceder a modificar el Colegio');
}




////////////////////////////////////////////////////////////////////

function valida_cnmp06_nivel_educacion2(){
	if(document.getElementById('cedula').value==''){

	        fun_msj('Ingrese el Codigo del Nivel Educativo');
			document.getElementById('cedula').focus();
			return false;

	}if(document.getElementById('denominacion').value==''){

	        fun_msj('Ingrese la denominacion del Nivel Educativo');
			document.getElementById('denominacion').focus();
			return false;
	}
	document.getElementById('cedula').value="";
	document.getElementById('denominacion').value="";
}


function cnmp06_modificar_nivel_educacion(){
	document.getElementById('b_guardar').disabled=false;
	document.getElementById('b_modificar').disabled=true;
	document.getElementById('denominacion').readOnly=false;
	fun_msj2('Puede proceder a modificar el Nivel Educativo');
}



////////////////////////////////////////////////////////////////////

function valida_cnmp06_parentesco2(){
	if(document.getElementById('cedula').value==''){

	        fun_msj('Ingrese el Codigo del Parentesco');
			document.getElementById('cedula').focus();
			return false;

	}if(document.getElementById('denominacion').value==''){

	        fun_msj('Ingrese la denominacion del Parentesco');
			document.getElementById('denominacion').focus();
			return false;
	}
	document.getElementById('cedula').value="";
	document.getElementById('denominacion').value="";
}


function cnmp06_modificar_parentesco(){
	document.getElementById('b_guardar').disabled=false;
	document.getElementById('b_modificar').disabled=true;
	document.getElementById('denominacion').readOnly=false;
	fun_msj2('Puede proceder a modificar el Parentesco');
}



////////////////////////////////////////////////////////////////////

function valida_cnmp06_religiones2(){
	if(document.getElementById('cedula').value==''){

	        fun_msj('Ingrese el Codigo de la Religion');
			document.getElementById('cedula').focus();
			return false;

	}if(document.getElementById('denominacion').value==''){

	        fun_msj('Ingrese la denominacion de la Religion');
			document.getElementById('denominacion').focus();
			return false;
	}
	document.getElementById('cedula').value="";
	document.getElementById('denominacion').value="";
}


function cnmp06_modificar_religion(){
	document.getElementById('b_guardar').disabled=false;
	document.getElementById('b_modificar').disabled=true;
	document.getElementById('denominacion').readOnly=false;
	fun_msj2('Puede proceder a modificar la Religion');
}



////////////////////////////////////////////////////////////////////

function valida_cnmp06_hobby2(){
	if(document.getElementById('cedula').value==''){

	        fun_msj('Ingrese el Codigo del Hobby');
			document.getElementById('cedula').focus();
			return false;

	}if(document.getElementById('denominacion').value==''){

	        fun_msj('Ingrese la denominacion del Hobby');
			document.getElementById('denominacion').focus();
			return false;
	}
	document.getElementById('cedula').value="";
	document.getElementById('denominacion').value="";
}


function cnmp06_modificar_hobby(){
	document.getElementById('b_guardar').disabled=false;
	document.getElementById('b_modificar').disabled=true;
	document.getElementById('denominacion').readOnly=false;
	fun_msj2('Puede proceder a modificar el Hobby');
}



////////////////////////////////////////////////////////////////////

function valida_cnmp06_oficio2(){
	if(document.getElementById('cedula').value==''){

	        fun_msj('Ingrese el Codigo del Oficio');
			document.getElementById('cedula').focus();
			return false;

	}if(document.getElementById('denominacion').value==''){

	        fun_msj('Ingrese la denominacion del Oficio');
			document.getElementById('denominacion').focus();
			return false;
	}
	document.getElementById('cedula').value="";
	document.getElementById('denominacion').value="";
}


function cnmp06_modificar_oficio(){
	document.getElementById('b_guardar').disabled=false;
	document.getElementById('b_modificar').disabled=true;
	document.getElementById('denominacion').readOnly=false;
	fun_msj2('Puede proceder a modificar el Oficio');
}

