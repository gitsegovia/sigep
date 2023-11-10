function valida_cnmp06_datos_personales(){

var tiene_cedula= false;
if (document.getElementById('cedula_img')) {
    tiene_cedula= false;
}else{
	tiene_cedula= true;
}

if(document.getElementById('cedula').value==""){
showTab('dhtmlgoodies_tabView2',0);
			fun_msj('Ingrese la C&eacute;dula');
			document.getElementById('cedula').focus();
			return false;

}else if(eval(document.getElementById('cedula').value) <= eval(0)){
showTab('dhtmlgoodies_tabView2',0);
			fun_msj('Ingrese una C&eacute;dula Correcta');
			document.getElementById('cedula').focus();
			return false;
}else if(document.getElementById('nacionalidad_V').checked==false && document.getElementById('nacionalidad_E').checked==false){
showTab('dhtmlgoodies_tabView2',0);
			fun_msj('Por Favor Seleccione La Nacionalidad Venezolano o Extranjero ');
			//document.getElementById('x_1').focus();
			return false;

}else if(document.getElementById('papellido').value==""){
showTab('dhtmlgoodies_tabView2',0);
			fun_msj('Ingrese el Primer Apellido');
			document.getElementById('papellido').focus();
			return false;

}else if(document.getElementById('pnombre').value==""){
showTab('dhtmlgoodies_tabView2',0);
			fun_msj('Ingrese el Primer Nombre');
			document.getElementById('pnombre').focus();
			return false;

}else if(tiene_cedula==false){
showTab('dhtmlgoodies_tabView2',0);
			fun_msj('Ingrese una foto');
			return false;

}else if(document.getElementById('fecha_nacimiento').value==""){
showTab('dhtmlgoodies_tabView2',0);
			fun_msj('Ingrese la Fecha de Nacimiento');
			document.getElementById('fecha_nacimiento').focus();
			return false;

}else if(document.getElementById('sexo_M').checked==false && document.getElementById('sexo_F').checked==false){
showTab('dhtmlgoodies_tabView2',0);
			fun_msj('Por Favor Seleccione El Sexo Femenino o Masculino ');
			//document.getElementById('x_1').focus();
			return false;

}else if(document.getElementById('estado_civil_C').checked==false && document.getElementById('estado_civil_S').checked==false && document.getElementById('estado_civil_D' ).checked==false && document.getElementById('estado_civil_V').checked==false && document.getElementById('estado_civil_O').checked==false){
showTab('dhtmlgoodies_tabView2',0);
			fun_msj('Por Favor Seleccione El Estado Civil ');
			//document.getElementById('x_1').focus();
			return false;

}else if(document.getElementById('grupo_sanguineo').value==""){
showTab('dhtmlgoodies_tabView2',0);
			fun_msj('Por Favor Ingrese el Grupo Sanguineo');
			document.getElementById('grupo_sanguineo').focus();
			return false;

}else if(document.getElementById('peso').value==""){
showTab('dhtmlgoodies_tabView2',0);
			fun_msj('Por Favor Ingrese el Peso En Kilos');
			document.getElementById('peso').focus();
			return false;

}else if(document.getElementById('estatura').value==""){
showTab('dhtmlgoodies_tabView2',0);
			fun_msj('Por Favor Ingrese la Estatura en Metros');
			document.getElementById('estatura').focus();
			return false;

}else if(document.getElementById('x_1').value==""){
showTab('dhtmlgoodies_tabView2',0);
			fun_msj('Ingrese el Pais de Origen');
			document.getElementById('x_1').focus();
			return false;

}else if(document.getElementById('x_2').value==""){
showTab('dhtmlgoodies_tabView2',0);
			fun_msj('Ingrese el Estado de origen');
			document.getElementById('x_2').focus();
			return false;

}else if(document.getElementById('x_3').value==""){
showTab('dhtmlgoodies_tabView2',0);
			fun_msj('Ingrese el Municipio de origen');
			document.getElementById('x_3').focus();
			return false;

}else if(document.getElementById('x_4').value==""){
showTab('dhtmlgoodies_tabView2',0);
			fun_msj('Ingrese la Parroquia de origen');
			document.getElementById('x_4').focus();
			return false;

}else if(document.getElementById('x_5').value==""){
showTab('dhtmlgoodies_tabView2',0);
			fun_msj('Ingrese el Centro Poblado de origen');
			document.getElementById('x_5').focus();
			return false;


}else if(document.getElementById('y_6').value==""){
showTab('dhtmlgoodies_tabView2',0);
			fun_msj('Ingrese la Profesion');
			document.getElementById('y_6').focus();
			return false;

}else if(document.getElementById('y_7').value==""){
showTab('dhtmlgoodies_tabView2',0);
			fun_msj('Ingrese la Especialidad');
			document.getElementById('y_7').focus();
			return false;

}else if(document.getElementById('direccion_habitacion').value==""){
            showTab('dhtmlgoodies_tabView2',1);
			fun_msj('Ingrese la direcci&oacute;n Actual de Habitaci&oacute;n');
			document.getElementById('direccion_habitacion').focus();
			return false;

}else if(document.getElementById('telefonos').value==""){
showTab('dhtmlgoodies_tabView2',1);
			fun_msj('Ingrese Tel&eacute;fonos');
			document.getElementById('telefonos').focus();
			return false;

}else if(document.getElementById('z_8').value==""){
showTab('dhtmlgoodies_tabView2',1);
			fun_msj('Ingrese el Estado de Habitaci&oacute;n');
			document.getElementById('z_8').focus();
			return false;

}else if(document.getElementById('z_9').value==""){
showTab('dhtmlgoodies_tabView2',1);
			fun_msj('Ingrese el Municipio de Habitaci&oacute;n');
			document.getElementById('z_9').focus();
			return false;

}else if(document.getElementById('z_10').value==""){
showTab('dhtmlgoodies_tabView2',1);
			fun_msj('Ingrese la Parroquia de Habitaci&oacute;n');
			document.getElementById('z_10').focus();
			return false;

}else if(document.getElementById('z_11').value==""){
showTab('dhtmlgoodies_tabView2',1);
			fun_msj('Ingrese el Centro Poblado de Habitaci&oacute;n');
			document.getElementById('z_11').focus();
			return false;

}else if(document.getElementById('lentes_S').checked==false && document.getElementById('lentes_N').checked==false){
showTab('dhtmlgoodies_tabView2',1);
			fun_msj('Por Favor Seleccione si usa lente si o no ');
			//document.getElementById('x_1').focus();
			return false;

}else if(document.getElementById('talla_camisa').value==""){
showTab('dhtmlgoodies_tabView2',1);
			fun_msj('Ingrese la Talla de Camisa o Blusa');
			document.getElementById('talla_camisa').focus();
			return false;

}else if(document.getElementById('talla_pantalon').value==""){
showTab('dhtmlgoodies_tabView2',1);
			fun_msj('Ingrese la Talla de Pantalon o Falda');
			document.getElementById('talla_pantalon').focus();
			return false;

}else if(document.getElementById('talla_calzado').value==""){
showTab('dhtmlgoodies_tabView2',1);
			fun_msj('Ingrese la Talla Calzado');
			document.getElementById('talla_calzado').focus();
			return false;

}
}//fin funcion validar




function limpia_b(){
  document.getElementById('buscar2').innerHTML="";
}



function valida_cnmp06_datos_educativos(){

 if(document.getElementById('cedula').value==''){
			fun_msj('Ingrese la c&eacute;dula');
			document.getElementById('cedula').focus();
			return false;
}else if(document.getElementById('select_nivel').value==''){
			fun_msj('Seleccione el nivel educativo');
			document.getElementById('select_nivel').focus();
			return false;
}else if(document.getElementById('select_institucion').value==''){
			fun_msj('Seleccione la instituci&oacute;n educativa');
			document.getElementById('select_institucion').focus();
			return false;
}else if(document.getElementById('select_1').value==''){
			fun_msj('Seleccione el pa&iacute;s');
			document.getElementById('select_1').focus();
			return false;
}else if(document.getElementById('select_2').value==''){
			fun_msj('Seleccione el estado');
			document.getElementById('select_2').focus();
			return false;
}else if(document.getElementById('select_3').value==''){
			fun_msj('Seleccione el municipio');
			document.getElementById('select_3').focus();
			return false;
}else if(document.getElementById('select_4').value==''){
			fun_msj('Seleccione la parroquia');
			document.getElementById('select_4').focus();
			return false;
}else if(document.getElementById('select_5').value==''){
			fun_msj('Seleccione urbanizaci&oacute;n, barrio, caserio, poblado');
			document.getElementById('select_5').focus();
			return false;
}else if(document.getElementById('fecha_inicio').value==''){
			fun_msj('Seleccione la fecha de inicio');
			document.getElementById('fecha_inicio').focus();
			return false;
}else if(document.getElementById('fecha_fin').value==''){
			fun_msj('Seleccione la fecha de culminaci&oacute;n');
			document.getElementById('fecha_fin').focus();
			return false;
}else if(document.getElementById('observaciones').value==''){
			fun_msj('Ingrese las observaciones');
			document.getElementById('observaciones').focus();
			return false;
}
}

function valida_cnmp06_formacion_profesional(){

 if(document.getElementById('cedula').value==''){
			fun_msj('Ingrese la c&eacute;dula');
			document.getElementById('cedula').focus();
			return false;
}else if(document.getElementById('select_curso').value==''){
			fun_msj('Seleccione el curso');
			document.getElementById('select_curso').focus();
			return false;
}else if(document.getElementById('select_institucion').value==''){
			fun_msj('Seleccione el instituto o instructor');
			document.getElementById('select_institucion').focus();
			return false;
}else if(document.getElementById('duracion').value==''){
			fun_msj('Ingrese el tiempo de duraci&oacute;n del curso');
			document.getElementById('duracion').focus();
			return false;
}else if(document.getElementById('desde').value==''){
			fun_msj('Seleccione la fecha de inicio');
			document.getElementById('desde').focus();
			return false;
}else if(document.getElementById('hasta').value==''){
			fun_msj('Seleccione la fecha de culminaci&oacute;n');
			document.getElementById('hasta').focus();
			return false;
}else if(document.getElementById('observaciones').value==''){
			fun_msj('Ingrese las observaciones');
			document.getElementById('observaciones').focus();
			return false;
}
}

function valida_cnmp06_registro_titulo(){

 if(document.getElementById('cedula').value==''){
			fun_msj('Ingrese la c&eacute;dula');
			document.getElementById('cedula').focus();
			return false;
}else if(document.getElementById('select_1').value==''){
			fun_msj('Seleccione la profesi&oacute;n');
			document.getElementById('select_1').focus();
			return false;
}else if(document.getElementById('select_2').value==''){
			fun_msj('Seleccione la especialidad');
			document.getElementById('select_2').focus();
			return false;
}else if(document.getElementById('colegio').value==''){
			fun_msj('seleccione el colegio profesional');
			document.getElementById('colegio').focus();
			return false;
}else if(document.getElementById('numero_colegio').value==''){
			fun_msj('Ingrese el numero de colegio');
			document.getElementById('numero_colegio').focus();
			return false;
}else if(document.getElementById('numero_registro').value==''){
			fun_msj('Ingrese el numero de registro');
			document.getElementById('numero_registro').focus();
			return false;
}else if(document.getElementById('tomo').value==''){
			fun_msj('Ingrese tomo');
			document.getElementById('tomo').focus();
			return false;
}else if(document.getElementById('folio').value==''){
			fun_msj('Ingrese folios');
			document.getElementById('folio').focus();
			return false;
}else if(document.getElementById('fecha_registro').value==''){
			fun_msj('Seleccione la fecha de registro');
			document.getElementById('fecha_registro').focus();
			return false;
}
}










function valida_cnmp06_datos_familiares(){

						 if(document.getElementById('cedula').value==''){
									fun_msj('Ingrese la c&eacute;dula');
									document.getElementById('cedula').focus();
									return false;
						}else if(document.getElementById('select_parentesco').value==''){
									fun_msj('Seleccione el parentesco');
									document.getElementById('select_parentesco').focus();
									return false;
						}else if(document.getElementById('nombres_apellidos').value==''){
									fun_msj('Ingrese nombres y apellidos del pariente');
									document.getElementById('nombres_apellidos').focus();
									return false;
						}else if(document.getElementById('fecha_nacimiento').value==''){
									fun_msj('Ingrese la fecha de nacimiento del pariente');
									document.getElementById('fecha_nacimiento').focus();
									return false;
						}else if(document.getElementById('sexo_M').checked==false && document.getElementById('sexo_F').checked==false){
									fun_msj('Seleccione el sexo del pariente');
									return false;
						}//fin else
}//FIN IF

function valida_cnmp06_datos_hijos(){
	if(document.getElementById('nombres_apellidos').value==''){
				fun_msj('Ingrese el nombre del Pariente');
				document.getElementById('nombres_apellidos').focus();
				return false;
	}
	if(document.getElementById('numero_cedula').value==''){
				fun_msj('Ingrese un numero de cedula');
				document.getElementById('numero_cedula').focus();
				return false;
	}
	if(document.getElementById('fecha_nacimiento').value==''){
				fun_msj('Ingrese fecha de nacimiento');
				document.getElementById('fecha_nacimiento').focus();
				return false;
	}
	if(document.getElementById('sexo_M').checked==false && document.getElementById('sexo_F').checked==false){
				fun_msj('Seleccione el Sexo del pariente');
				return false;
	}//fin else
	if(document.getElementById('afiliado_1').checked==false && document.getElementById('afiliado_2').checked==false){
				fun_msj('Indique si esta afiliado.');
				return false;
	}//fin else
}






function Peso(obj,tammax,teclapres) {
var tecla = teclapres.keyCode;

vr = obj.value;
vr = vr.replace( "/", "" );
vr = vr.replace( "/", "" );
vr = vr.replace( ",", "" );
vr = vr.replace( ".", "" );
vr = vr.replace( ".", "" );
vr = vr.replace( ".", "" );
vr = vr.replace( ".", "" );
tam = vr.length;
//alert(tam);
if (tam < tammax && tecla != 8){ tam = vr.length + 1 ; }

if (tecla == 8 ){ tam = tam - 1 ; }

if ( tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 ){
  if ( (tam <= 2)){
    obj.value = vr + '.' + 000 ; }
  if ( tam == 3 ){
    obj.value = vr.substr( 0, tam - 1 ) + '.' + vr.substr( tam - 1, tam ) ; }
  if ( (tam > 3)){
    obj.value = vr.substr( 0, tam - 1 ) + '.' + vr.substr( tam - 1, tam ) ; }

  /*if ( (tam >= 6) && (tam <= 8) ){
    obj.value = vr.substr( 0, tam - 5 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ) ; }
  if ( (tam >= 9) && (tam <= 11) ){
    obj.value = vr.substr( 0, tam - 8 ) + '.' + vr.substr( tam - 8, 3 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ) ; }
  if ( (tam >= 12) && (tam <= 14) ){
    obj.value = vr.substr( 0, tam - 11 ) + '.' + vr.substr( tam - 11, 3 ) + '.' + vr.substr( tam - 8, 3 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ) ; }
  if ( (tam >= 15) && (tam <= 17) ){
    obj.value = vr.substr( 0, tam - 14 ) + '.' + vr.substr( tam - 14, 3 ) + '.' + vr.substr( tam - 11, 3 ) + '.' + vr.substr( tam - 8, 3 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ) ;}*/
}

}//fin