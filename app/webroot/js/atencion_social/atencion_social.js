function casd01_reporte_solicitudes(){

//alert("hola");
if(document.getElementById('fecha_inicial') && document.getElementById('fecha_final')){
	if(document.getElementById('fecha_inicial').value=='' || document.getElementById('fecha_final').value==''){
			fun_msj('debe ingresar las fechas');
             return false;
	}else if(diferenciaFecha(document.getElementById('fecha_final').value, document.getElementById('fecha_inicial').value)){
            fun_msj('la Fecha de terminaci&oacute;n debe ser mayor a la de inicio');
             return false;
	}

}else if(document.getElementById('ano')){
	if(document.getElementById('ano').value==''){
			fun_msj('ingrese el a&ntilde;o');
             return false;
	}
}



}// fin casd01_reporte_solicitudes





function valida_casp01_datos_personales(){
	if(document.getElementById('cedula').value==''){
        fun_msj('debe ingresar una cedula de identidad');
		document.getElementById('cedula').focus();
		return false;
	}else if(document.getElementById('nacionalidad').value==''){
        fun_msj('seleccione la nacionalidad');
		document.getElementById('nacionalidad').focus();
		return false;
	}else if(document.getElementById('ape_nom').value==''){
        fun_msj('debe ingresar apellidos y nombres');
		document.getElementById('ape_nom').focus();
		return false;
	}else if(document.getElementById('fecha_nacimiento').value==''){
        fun_msj('debe ingresar la fecha de nacimiento');
		document.getElementById('fecha_nacimiento').focus();
		return false;
	}else if(document.getElementById('sexo').value==''){
        fun_msj('seleccione el sexo');
		document.getElementById('sexo').focus();
		return false;
	}else if(document.getElementById('estado_civil').value==''){
        fun_msj('seleccione el estado civil');
		document.getElementById('estado_civil').focus();
		return false;
	}else if(document.getElementById('profesion').value==''){
        fun_msj('seleccione una profesi&oacute;n');
		document.getElementById('profesion').focus();
		return false;
	}else if(document.getElementById('oficio').value==''){
        fun_msj('seleccione una destreza u oficio');
		document.getElementById('oficio').focus();
		return false;
	}else if(document.getElementById('ambito').value==''){
        fun_msj('seleccione el tipo de  ambito');
		document.getElementById('ambito').focus();
		return false;
	}else if(document.getElementById('zonificacion').value==''){
        fun_msj('seleccione el tipo de zona');
		document.getElementById('zonificacion').focus();
		return false;
	}else if(document.getElementById('vivienda').value==''){
        fun_msj('seleccione el tipo de vivienda');
		document.getElementById('vivienda').focus();
		return false;
	}else if(document.getElementById('select_1').value==''){
        fun_msj('seleccione el estado');
		document.getElementById('select_1').focus();
		return false;
	}else if(document.getElementById('select_2').value==''){
        fun_msj('seleccione el municipio');
		document.getElementById('select_2').focus();
		return false;
	}else if(document.getElementById('select_3').value==''){
        fun_msj('seleccione la parroquia');
		document.getElementById('select_3').focus();
		return false;
	}else if(document.getElementById('select_4').value==''){
        fun_msj('seleccione el centro poblado');
		document.getElementById('select_4').focus();
		return false;
	}else if(document.getElementById('direccion').value==''){
        fun_msj('debe ingresar la direcci&oacute;n');
		document.getElementById('direccion').focus();
		return false;
	}else if(eval(document.getElementById('familia').value)==0){
        fun_msj('debe ingresar un familiar');
		return false;
	}

}///valida_casp01_datos_personales



function casd01_reporte_cumpleano(){

	if(document.getElementById('fecha_nacimiento').value==''){
        fun_msj('debe ingresar una fecha');
		return false;
	}



}//casd01_reporte_cumpleano




function casp01_confirm_fusion_tipo_ayuda(){

	if(confirm("Esta realmente seguro de reemplazar estos tipos de ayudas")){
		return true;
	}else{
		return false;
	}

}


function funcion_emitir_ayuda_acta_entrega(){

if(document.getElementById('acta_entrega_1') && document.getElementById('acta_entrega_2')){
	if((document.getElementById('acta_entrega_1').checked==false && document.getElementById('acta_entrega_2').checked==false)){
        fun_msj('verifique si desea emitir o no el acta de entrega');
		return false;
	}
}

}
