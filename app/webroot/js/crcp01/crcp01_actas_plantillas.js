
function capturar_contenido_editor_crcp01(){
	var fckEditor1val_aux = FCKeditorAPI.__Instances['Contenido_FCK'].GetHTML();
	if(fckEditor1val_aux==''){
		return 1;
	}else{
		//document.getElementById('Contenido_FCK').value = fckEditor1val_aux;
		contenido = FCKeditorAPI.__Instances['Contenido_FCK'].GetHTML();
		document.getElementById('Contenido_FCK').value = contenido;
		return 0;
	}
}

function validar_crcp01_actas_plantillas(){
	if($('titulo_tipo_acta').value==''){
	        fun_msj('Debe ingresar la descripcion breve de la plantilla');
			$('titulo_tipo_acta').focus();
			return false;
	}else if($('tipo_plantilla').value==''){
	        fun_msj('Seleccione el tipo de plantilla');
			$('tipo_plantilla').focus();
			return false;
	}else{
		var editor = capturar_contenido_editor_crcp01();
		if(editor==1){
			fun_msj('Atencion: Debe ingresar el contenido de la plantilla');
			return false;
		}
		var cont = $('control').value;
		cont = eval(cont)+eval(1);
		$('control').value = cont;
		return true;
	}
}

function validar_crcp01_actas_defuncion(){
	if($('ano_acta').value==''){
	        fun_msj('Debe ingresar el año del acta');
			$('ano_acta').focus();
			return false;
	}else if($('tomo').value==''){
	        fun_msj('Debe ingresar el tomo del acta');
			$('tomo').focus();
			return false;
	}else if($('folio').value==''){
	        fun_msj('Debe ingresar el folio del acta');
			$('folio').focus();
			return false;
	}else if($('cedula_difunto').value==''){
	        fun_msj('Debe ingresar la cédula de indentidad del difunto');
			$('cedula_difunto').focus();
			return false;
	}else if($('nombres_difunto').value==''){
	        fun_msj('Debe ingresar los nombres y apellidos del difunto');
			$('nombres_difunto').focus();
			return false;
	}else if($('cedula_exponente').value==''){
	        fun_msj('Debe ingresar la cédula de identidad del exponente');
			$('cedula_exponente').focus();
			return false;
	}else if($('nombres_exponente').value==''){
	        fun_msj('Debe ingresar los nombres y apellidos del exponente');
			$('nombres_exponente').focus();
			return false;
	}else if($('cedula_testigo').value==''){
	        fun_msj('Debe ingresar la cédula de identidad del testigo');
			$('cedula_testigo').focus();
			return false;
	}else if($('nombres_testigo').value==''){
	        fun_msj('Debe ingresar los nombres y apellidos del testigo');
			$('nombres_testigo').focus();
			return false;
	}else{
		var editor = capturar_contenido_editor_crcp01();
		if(editor==1){
			fun_msj('Atencion: Debe ingresar el contenido del acta');
			return false;
		}
		var cont = $('control').value;
		cont = eval(cont)+eval(1);
		$('control').value = cont;
		return true;
	}
}


function validar_crcp01_actas_matrimonio(){
	if($('ano_acta').value==''){
	        fun_msj('Debe ingresar el año del acta');
			$('ano_acta').focus();
			return false;
	}else if($('tomo').value==''){
	        fun_msj('Debe ingresar el tomo del acta');
			$('tomo').focus();
			return false;
	}else if($('folio').value==''){
	        fun_msj('Debe ingresar el folio del acta');
			$('folio').focus();
			return false;
	}else if($('cedula_novia').value==''){
	        fun_msj('Debe ingresar la cédula de indentidad de la novia');
			$('cedula_novia').focus();
			return false;
	}else if($('nombres_novia').value==''){
	        fun_msj('Debe ingresar los nombres y apellidos de la novia');
			$('nombres_novia').focus();
			return false;
	}else if($('cedula_novio').value==''){
	        fun_msj('Debe ingresar la cédula de identidad del novio');
			$('cedula_novio').focus();
			return false;
	}else if($('nombres_novio').value==''){
	        fun_msj('Debe ingresar los nombres y apellidos del novio');
			$('nombres_novio').focus();
			return false;
	}else if($('cedula_testigo').value==''){
	        fun_msj('Debe ingresar la cédula de identidad del testigo');
			$('cedula_testigo').focus();
			return false;
	}else if($('nombres_testigo').value==''){
	        fun_msj('Debe ingresar los nombres y apellidos del testigo');
			$('nombres_testigo').focus();
			return false;
	}else{
		var editor = capturar_contenido_editor_crcp01();
		if(editor==1){
			fun_msj('Atencion: Debe ingresar el contenido del acta');
			return false;
		}
		var cont = $('control').value;
		cont = eval(cont)+eval(1);
		$('control').value = cont;
		return true;
	}
}



function validar_crcp01_actas_nacimiento(){
	if($('ano_acta').value==''){
	        fun_msj('Debe ingresar el año del acta');
			$('ano_acta').focus();
			return false;
	}else if($('tomo').value==''){
	        fun_msj('Debe ingresar el tomo del acta');
			$('tomo').focus();
			return false;
	}else if($('folio').value==''){
	        fun_msj('Debe ingresar el folio del acta');
			$('folio').focus();
			return false;
	}else if($('sexo_1').checked==false && $('sexo_2').checked==false){
	        fun_msj('Debe seleccionar el sexo');
			return false;
	}else if($('nombre_nacido').value==''){
	        fun_msj('Debe ingresar los nombres de niña o niño');
			$('nombre_nacido').focus();
			return false;
	}else if($('cedula_madre').value==''){
	        fun_msj('Debe ingresar la cédula de indentidad de la madre');
			$('cedula_madre').focus();
			return false;
	}else if($('nombres_madre').value==''){
	        fun_msj('Debe ingresar los nombres y apellidos de la madre');
			$('nombres_madre').focus();
			return false;
	}else if($('cedula_padre').value==''){
	        fun_msj('Debe ingresar la cédula de identidad del padre');
			$('cedula_padre').focus();
			return false;
	}else if($('nombres_padre').value==''){
	        fun_msj('Debe ingresar los nombres y apellidos del padre');
			$('nombres_padre').focus();
			return false;
	}else if($('cedula_testigo').value==''){
	        fun_msj('Debe ingresar la cédula de identidad del testigo');
			$('cedula_testigo').focus();
			return false;
	}else if($('nombres_testigo').value==''){
	        fun_msj('Debe ingresar los nombres y apellidos del testigo');
			$('nombres_testigo').focus();
			return false;
	}else{
		var editor = capturar_contenido_editor_crcp01();
		if(editor==1){
			fun_msj('Atencion: Debe ingresar el contenido del acta');
			return false;
		}
		var cont = $('control').value;
		cont = eval(cont)+eval(1);
		$('control').value = cont;
		return true;
	}
}