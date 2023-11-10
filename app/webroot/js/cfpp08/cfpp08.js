
function valida_cfpp08_ano(){


	if(document.getElementById('ano_presupuesto').value==''){

			fun_msj('Ingrese el a&ntilde;o presupuestario');
			document.getElementById('ano_presupuesto').focus();
			return false;

	}else if(document.getElementById('ano_presupuesto').value.length<4){

			fun_msj('Ingrese un a&ntilde;o correcto');
			document.getElementById('ano_presupuesto').focus();
			return false;


	}


}


function vaciar_ejercicio(){

		document.getElementById('ano_presupuesto').value="";


}




function valida_cfpp08(){


if(document.getElementById('domicilio_legal').value==''){

			fun_msj('Ingrese el Domicilio Legal');
			document.getElementById('domicilio_legal').focus();
			return false;

}else if(document.getElementById('ciudad').value == ""){

			fun_msj('Ingrese la Ciudad');
			document.getElementById('ciudad').focus();
			return false;


}else if(document.getElementById('telefono').value == ""){

			fun_msj('Ingrese el Telefono');
			document.getElementById('telefono').focus();
			return false;


}else if(document.getElementById('email').value == ""){

			fun_msj('Ingrese el Email');
			document.getElementById('email').focus();
			return false;


}else if ( (document.getElementById('email').value.search("@") == -1 ) || ( document.getElementById('email').value.search("[.*]" ) == -1 ) ) {

		fun_msj( "Por favor, verifique el Email." );
		document.getElementById('email').focus();
		return false;


}else if(document.getElementById('fax').value == ""){

			fun_msj('Ingrese el Fax');
			document.getElementById('fax').focus();
			return false;

}else if(document.getElementById('cod_postal').value == ""){

			fun_msj('Ingrese el Codigo Postal');
			document.getElementById('cod_postal').focus();
			return false;


}else if(document.getElementById('nombre_gobernador').value == ""){

			fun_msj('Ingrese Nombre del Gobernador');
			document.getElementById('nombre_gobernador').focus();
			return false;


}else if(document.getElementById('nombre_contralor').value == ""){

			fun_msj('Ingrese el Nombre Contralor');
			document.getElementById('nombre_contralor').focus();
			return false;



}else if(document.getElementById('nombre_presidente_consejo_legislativo').value == ""){

			fun_msj('Ingrese el Nombre del Presidente del Consejo Legislativo');
			document.getElementById('nombre_presidente_consejo_legislativo').focus();
			return false;


}else if(document.getElementById('director_presupuesto').value == ""){

			fun_msj('Ingrese el Nombre del Director de Presupuesto');
			document.getElementById('director_presupuesto').focus();
			return false;


}else{







	}


}//fin funtion




function add_new_infog_institution(){
	if(document.getElementById('ejercicio').value==''){
			fun_msj('Por favor seleccione el a&ntilde;o del presupuesto');
			document.getElementById('ejercicio').focus();
			return false;
	}
	/* else if(eval(document.getElementById('ejercicio').value) > eval(document.getElementById('ANO_ACTUAL_SERVIDOR').value)){
		fun_msj('POR FAVOR VERIFIQUE EL A&Ntilde;O DEL Presupuesto, NO puede ser mayor AL A&Ntilde;O ACTUAL');
		document.getElementById('ejercicio').focus();
		return false;
	} */
	else if(document.getElementById('base_legal').value==''){
			fun_msj('Por favor ingrese base legal');
			document.getElementById('base_legal').focus();
			return false;
	}else if(document.getElementById('domicilio_legal').value==''){
			fun_msj('Por favor ingrese domicilio legal');
			document.getElementById('domicilio_legal').focus();
			return false;
	}else if(document.getElementById('telefonos').value==''){
			fun_msj('Por favor ingrese tel&eacute;fono(s)');
			document.getElementById('telefonos').focus();
			return false;
	}else if(document.getElementById('direccion_internet').value==''){
			fun_msj('Por favor ingrese pagina web');
			document.getElementById('direccion_internet').focus();
			return false;
	}else if(document.getElementById('fax').value==''){
			fun_msj('Por favor ingrese fax');
			document.getElementById('fax').focus();
			return false;
	}else if(document.getElementById('codigo_postal').value==''){
			fun_msj('Por favor ingrese c&oacute;digo postal');
			document.getElementById('codigo_postal').focus();
			return false;
	}else if(document.getElementById('nombre_alc_gob').value==''){
			fun_msj('Por favor ingrese nombres y apellidos');
			document.getElementById('nombre_alc_gob').focus();
			return false;
	}else if(document.getElementById('cuenta1').value=='0'){
			fun_msj('Por favor ingrese Personal Directivo');
			document.getElementById('direccion_administrativa').focus();
			return false;
	}else if(document.getElementById('nombres_contralor').value==''){
			fun_msj('Por favor ingrese nombres y apellidos del contralor(a)');
			document.getElementById('nombres_contralor').focus();
			return false;
	}else if(document.getElementById('domicilio_contralor').value==''){
			fun_msj('Por favor ingrese domicilio legal contraloria');
			document.getElementById('domicilio_contralor').focus();
			return false;
	}else if(document.getElementById('telefonos_contraloria').value==''){
			fun_msj('Por favor ingrese tel&eacute;fono(s) contraloria');
			document.getElementById('telefonos_contraloria').focus();
			return false;
	}else if(document.getElementById('pagina_web_contraloria').value==''){
			fun_msj('Por favor ingrese pagina web contraloria');
			document.getElementById('pagina_web_contraloria').focus();
			return false;
	}else if(document.getElementById('fax_contraloria').value==''){
			fun_msj('Por favor ingrese fax contraloria');
			document.getElementById('fax_contraloria').focus();
			return false;
	}else if(document.getElementById('nombres_presidente_consejo').value==''){
			fun_msj('Por favor ingrese nombres y apellidos del persidente(a) de consejo');
			document.getElementById('nombres_presidente_consejo').focus();
			return false;
	}else if(document.getElementById('nombres_secretario_consejo').value==''){
			fun_msj('Por favor ingrese nombres y apellidos del secretario(a) de consejo');
			document.getElementById('nombres_secretario_consejo').focus();
			return false;
	}else if(document.getElementById('domicilio_consejo').value==''){
			fun_msj('Por favor ingrese el domicilio legal del consejo');
			document.getElementById('domicilio_consejo').focus();
			return false;
	}else if(document.getElementById('telefonos_consejo').value==''){
			fun_msj('Por favor ingrese tel&eacute;fono(s) consejo');
			document.getElementById('telefonos_consejo').focus();
			return false;
	}else if(document.getElementById('pagina_web_consejo').value==''){
			fun_msj('Por favor ingrese pagina web consejo');
			document.getElementById('pagina_web_consejo').focus();
			return false;
	}else if(document.getElementById('fax_consejo').value==''){
			fun_msj('Por favor ingrese fax consejo');
			document.getElementById('fax_consejo').focus();
			return false;
	}else if(document.getElementById('cuenta2').value=='0'){
			fun_msj('Por favor ingrese Concejales');
			document.getElementById('nombres_cpp').focus();
			return false;
	}else{return true;}
}

function personal_directivo_a_infog(){
	if(document.getElementById('codigo_directivos').value==''){
			fun_msj('El c&oacute;digo del directivo no puede estar vacio.');
			document.getElementById('codigo_directivos').focus();
			return false;
	}else if(document.getElementById('direccion_administrativa').value==''){
			fun_msj('Por favor ingrese la direcci&oacute;n administrativa.');
			document.getElementById('direccion_administrativa').focus();
			return false;
	}else if(document.getElementById('nombres_directivo').value==''){
			fun_msj('Por favor ingrese nombres y apellidos del directivo.');
			document.getElementById('nombres_directivo').focus();
			return false;
	}else if(document.getElementById('correo_directivos').value==''){
			fun_msj('Por favor ingrese correo electr&oacute;nico del directivo.');
			document.getElementById('correo_directivos').focus();
			return false;
	}else if((document.getElementById('correo_directivos').value.search("@") == -1 ) || ( document.getElementById('correo_directivos').value.search("[.*]" ) == -1 )){
			alert("Por favor, Revise el correo electronico del directivo.");
			document.getElementById('correo_directivos').focus();
			return false;
	}else if(document.getElementById('telefonos_directivos').value==''){
			fun_msj('Por favor ingrese tel&eacute;fono(s) del directivo.');
			document.getElementById('telefonos_directivos').focus();
			return false;
	}else{return true;}
}


function personal_directivo_a_infog2(){
	a = document.getElementById('campo_editarp').value;
	if(document.getElementById('codigo_directivos_'+a).value==''){
			fun_msj('El c&oacute;digo del directivo no puede estar vacio.');
			document.getElementById('codigo_directivos_'+a).focus();
			return false;
	}else if(document.getElementById('direccion_administrativa_'+a).value==''){
			fun_msj('Por favor ingrese la direcci&oacute;n administrativa.');
			document.getElementById('direccion_administrativa_'+a).focus();
			return false;
	}else if(document.getElementById('nombres_directivo_'+a).value==''){
			fun_msj('Por favor ingrese nombres y apellidos del directivo.');
			document.getElementById('nombres_directivo_'+a).focus();
			return false;
	}else if(document.getElementById('correo_directivos_'+a).value==''){
			fun_msj('Por favor ingrese correo electr&oacute;nico del directivo.');
			document.getElementById('correo_directivos_'+a).focus();
			return false;
	}else if((document.getElementById('correo_directivos_'+a).value.search("@") == -1 ) || ( document.getElementById('correo_directivos_'+a).value.search("[.*]" ) == -1 )){
			alert("Por favor, Revise el correo electronico del directivo.");
			document.getElementById('correo_directivos_'+a).focus();
			return false;
	}else if(document.getElementById('telefonos_directivos_'+a).value==''){
			fun_msj('Por favor ingrese tel&eacute;fono(s) del directivo.');
			document.getElementById('telefonos_directivos_'+a).focus();
			return false;
	}else{return true;}
}

function concejales_a_infog(){
	if(document.getElementById('codigo_cpp').value==''){
			fun_msj('El c&oacute;digo del concejal no puede estar vacio.');
			document.getElementById('codigo_cpp').focus();
			return false;
	}else if(document.getElementById('nombres_cpp').value==''){
			fun_msj('Por favor ingrese nombres y apellidos del concejal.');
			document.getElementById('nombres_cpp').focus();
			return false;
	}else if(document.getElementById('correo_cpp').value==''){
			fun_msj('Por favor ingrese correo electr&oacute;nico del concejal.');
			document.getElementById('correo_cpp').focus();
			return false;
	}else if((document.getElementById('correo_cpp').value.search("@") == -1 ) || ( document.getElementById('correo_cpp').value.search("[.*]" ) == -1 )){
			alert("Por favor, Revise el correo electronico del concejal.");
			document.getElementById('correo_cpp').focus();
			return false;
	}else if(document.getElementById('telefonos_cpp').value==''){
			fun_msj('Por favor ingrese tel&eacute;fono(s) del concejal.');
			document.getElementById('telefonos_cpp').focus();
			return false;
	}
}

function concejales_a_infog2(){
	b = document.getElementById('campo_editarc').value;
	if(document.getElementById('codigo_cpp_'+b).value==''){
			fun_msj('El c&oacute;digo del concejal no puede estar vacio.');
			document.getElementById('codigo_cpp_'+b).focus();
			return false;
	}else if(document.getElementById('nombres_cpp_'+b).value==''){
			fun_msj('Por favor ingrese nombres y apellidos del concejal.');
			document.getElementById('nombres_cpp_'+b).focus();
			return false;
	}else if(document.getElementById('correo_cpp_'+b).value==''){
			fun_msj('Por favor ingrese correo electr&oacute;nico del concejal.');
			document.getElementById('correo_cpp_'+b).focus();
			return false;
	}else if((document.getElementById('correo_cpp_'+b).value.search("@") == -1 ) || ( document.getElementById('correo_cpp_'+b).value.search("[.*]" ) == -1 )){
			alert("Por favor, Revise el correo electronico del concejal.");
			document.getElementById('correo_cpp_'+b).focus();
			return false;
	}else if(document.getElementById('telefonos_cpp_'+b).value==''){
			fun_msj('Por favor ingrese tel&eacute;fono(s) del concejal.');
			document.getElementById('telefonos_cpp_'+b).focus();
			return false;
	}
}

function save_ppf(){
	if(document.getElementById('financiamiento').value==''){
			fun_msj('Por favor ingrese la POL&Iacute;TICA DE FINANCIAMIENTO.');
			document.getElementById('financiamiento').focus();
			return false;
	}else if(document.getElementById('gastos').value==''){
			fun_msj('Por favor ingrese la POL&Iacute;TICA DE GASTOS.');
			document.getElementById('gastos').focus();
			return false;
	}else if(document.getElementById('servicios').value==''){
			fun_msj('Por favor ingrese la POL&Iacute;TICA DE COBERTURA DE LOS SERVICIOS A PRESTAR POR LA ENTIDAD.');
			document.getElementById('servicios').focus();
			return false;
	}else{return true;}
}
