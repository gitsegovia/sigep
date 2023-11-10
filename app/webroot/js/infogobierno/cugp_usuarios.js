function validar_registro(){
	if(document.getElementById('email').value==''){
		error('Ingrese el E-mail');
		document.getElementById('email').focus();
		return false;
	}else if ((document.getElementById('email').value.search("@") == -1 ) || ( document.getElementById('email').value.search("[.*]" ) == -1 ) ) {
		error("Por favor, Revise el Email." );
		document.getElementById('email').focus();
		return false;
   }else if(document.getElementById('password').value==''){
		error('Ingrese password');
		document.getElementById('password').focus();
		return false;
	}else if(document.getElementById('password2').value==''){
		error('Ingrese repetir password');
		document.getElementById('password2').focus();
		return false;
	}else if(document.getElementById('password').value!='' && document.getElementById('password2').value!='' && document.getElementById('password').value!=document.getElementById('password2').value){
		error('Repetir Clave no coincide con la Clave, verifique por favor');
		document.getElementById('password').focus();
		return false;
	}else if(document.getElementById('apellidos').value==''){
	   if($('condicion_juridica_2').checked==true){
	       error('Ingrese la Razon Social');
	   }else{
	      error('Ingrese apellidos');
	   }

		document.getElementById('apellidos').focus();
		return false;
	}else if(document.getElementById('nombres').value=='' && $('condicion_juridica_1').checked==true){
		error('Ingrese nombres');
		document.getElementById('nombres').focus();
		return false;
	}else if(document.getElementById('cedula').value==''){
	   if($('condicion_juridica_2').checked==true){
	       error('Ingrese el RIF');
	   }else{
	       error('Ingrese cedula de identidad');
	   }

		document.getElementById('cedula').focus();
		return false;
	}else if($('condicion_juridica_2').checked==true){
                  var elRIF = document.getElementById('cedula').value;
                  var temp = elRIF.toUpperCase();
				  if (!/^[JVEGPIRC]/.test(temp)){
				      alert("El primer caracter es incorrecto, debe ser una letra de las siguientes: J,V,E,G,P,I,R,C");
				      return false;
				  }else if (!/^[JVEGPIRC][-][0-9]{8}[-][0-9]$/.test(temp)){ // Son 9 dígitos?
				     alert ("Por Favor Verifique que el RIF tenga el siguiente formato: J-12345678-9 \nSi su Rif es menor a 9 digitos rellene con 0 a la izquierda Ej: J-00123456-7\n Su rif actual es: "+temp);
				     return false;
				  }

    }else if(document.getElementById('pregunta_secreta').value==''){
		error('Seleccione pregunta secreta');
		document.getElementById('pregunta_secreta').focus();
		return false;
	}else if(document.getElementById('pregunta_secreta').value=='00-OTRA' && document.getElementById('otra_pregunta_secreta').value==''){
		error('Escriba la pregunta secreta');
		document.getElementById('otra_pregunta_secreta').focus();
		return false;
	}else if(document.getElementById('pregunta_secreta').value=='00-OTRA' && document.getElementById('otra_pregunta_secreta').value!='' && document.getElementById('otra_pregunta_secreta').value.length<5){
		error('Escriba una pregunta secreta mas segura!');
		document.getElementById('otra_pregunta_secreta').focus();
		return false;
	}else if(document.getElementById('respuesta_secreta').value==''){
		error('Ingrese la respuesta secreta');
		document.getElementById('respuesta_secreta').focus();
		return false;
	}else if(document.getElementById('respuesta_secreta').value.length<3){
		error('Ingrese una respuesta secreta mas segura!');
		document.getElementById('respuesta_secreta').focus();
		return false;
	}
}

function validar_info_olvido_usua(){
	if(document.getElementById('cedula_identidad').value==''){
		error('Ingrese c&eacute;dula de identidad');
		document.getElementById('cedula_identidad').focus();
		return false;
	}else if(document.getElementById('fecha_nacimiento').value==''){
		error('Ingrese fecha de nacimiento');
		document.getElementById('fecha_nacimiento').focus();
		return false;
	}else if(document.getElementById('respuesta_secreta') && document.getElementById('respuesta_secreta').value==''){
		error('Ingrese la respuesta secreta');
		document.getElementById('respuesta_secreta').focus();
		return false;
	}else{
		return true;
	}
}

function info_valida_cnmp06_datos_educativos(){

 if(document.getElementById('cedula').value==''){
			error('Ingrese la c&eacute;dula');
			document.getElementById('cedula').focus();
			return false;
}else if(document.getElementById('select_nivel').value==''){
			error('Seleccione el nivel educativo');
			document.getElementById('select_nivel').focus();
			return false;
}else if(document.getElementById('select_institucion').value==''){
			error('Seleccione la instituci&oacute;n educativa');
			document.getElementById('select_institucion').focus();
			return false;
}else if(document.getElementById('select_1').value==''){
			error('Seleccione el pa&iacute;s');
			document.getElementById('select_1').focus();
			return false;
}else if(document.getElementById('select_2').value==''){
			error('Seleccione el estado');
			document.getElementById('select_2').focus();
			return false;
}else if(document.getElementById('select_3').value==''){
			error('Seleccione el municipio');
			document.getElementById('select_3').focus();
			return false;
}else if(document.getElementById('select_4').value==''){
			error('Seleccione la parroquia');
			document.getElementById('select_4').focus();
			return false;
}else if(document.getElementById('select_5').value==''){
			error('Seleccione urbanizaci&oacute;n, barrio, caserio, poblado');
			document.getElementById('select_5').focus();
			return false;
}else if(document.getElementById('fecha_inicio').value==''){
			error('Seleccione la fecha de inicio');
			document.getElementById('fecha_inicio').focus();
			return false;
}else if(document.getElementById('fecha_fin').value==''){
			error('Seleccione la fecha de culminaci&oacute;n');
			document.getElementById('fecha_fin').focus();
			return false;
}else if(document.getElementById('observaciones').value==''){
			error('Ingrese las observaciones');
			document.getElementById('observaciones').focus();
			return false;
}
}


function info_valida_cnmp06_formacion_profesional(){

 if(document.getElementById('cedula').value==''){
			error('Ingrese la c&eacute;dula');
			document.getElementById('cedula').focus();
			return false;
}else if(document.getElementById('select_curso').value==''){
			error('Seleccione el curso');
			document.getElementById('select_curso').focus();
			return false;
}else if(document.getElementById('select_institucion').value==''){
			error('Seleccione el instituto o instructor');
			document.getElementById('select_institucion').focus();
			return false;
}else if(document.getElementById('duracion').value==''){
			error('Ingrese el tiempo de duraci&oacute;n del curso');
			document.getElementById('duracion').focus();
			return false;
}else if(document.getElementById('desde').value==''){
			error('Seleccione la fecha de inicio');
			document.getElementById('desde').focus();
			return false;
}else if(document.getElementById('hasta').value==''){
			error('Seleccione la fecha de culminaci&oacute;n');
			document.getElementById('hasta').focus();
			return false;
}else if(document.getElementById('observaciones').value==''){
			error('Ingrese las observaciones');
			document.getElementById('observaciones').focus();
			return false;
}
}


function info_valida_cnmp06_registro_titulo(){

 if(document.getElementById('cedula').value==''){
			error('Ingrese la c&eacute;dula');
			document.getElementById('cedula').focus();
			return false;
}else if(document.getElementById('select_1').value==''){
			error('Seleccione la profesi&oacute;n');
			document.getElementById('select_1').focus();
			return false;
}else if(document.getElementById('select_2').value==''){
			error('Seleccione la especialidad');
			document.getElementById('select_2').focus();
			return false;
}else if(document.getElementById('colegio').value==''){
			error('seleccione el colegio profesional');
			document.getElementById('colegio').focus();
			return false;
}else if(document.getElementById('numero_colegio').value==''){
			error('Ingrese el numero de colegio');
			document.getElementById('numero_colegio').focus();
			return false;
}else if(document.getElementById('numero_registro').value==''){
			error('Ingrese el numero de registro');
			document.getElementById('numero_registro').focus();
			return false;
}else if(document.getElementById('tomo').value==''){
			error('Ingrese tomo');
			document.getElementById('tomo').focus();
			return false;
}else if(document.getElementById('folio').value==''){
			error('Ingrese folios');
			document.getElementById('folio').focus();
			return false;
}else if(document.getElementById('fecha_registro').value==''){
			error('Seleccione la fecha de registro');
			document.getElementById('fecha_registro').focus();
			return false;
}
}



function info_valida_cnmp06_datos_familiares(){

						 if(document.getElementById('cedula').value==''){
									error('Ingrese la c&eacute;dula');
									document.getElementById('cedula').focus();
									return false;
						}else if(document.getElementById('select_parentesco').value==''){
									error('Seleccione el parentesco');
									document.getElementById('select_parentesco').focus();
									return false;
						}else if(document.getElementById('nombres_apellidos').value==''){
									error('Ingrese nombres y apellidos del pariente');
									document.getElementById('nombres_apellidos').focus();
									return false;
						}else if(document.getElementById('fecha_nacimiento').value==''){
									error('Ingrese la fecha de nacimiento del pariente');
									document.getElementById('fecha_nacimiento').focus();
									return false;
						}else if(document.getElementById('sexo_M').checked==false && document.getElementById('sexo_F').checked==false){
									error('Seleccione el sexo del pariente');
									return false;
						}//fin else
}//FIN IF


function info_cnmp06_experiencia_administrativa_valida(){

      if(document.getElementById('entidad_federal').value==""){

			error('Ingrese el nombre de la instituci&oacute;n');
			document.getElementById('entidad_federal').focus();
			return false;


}else if(document.getElementById('cargo_desempenado').value==""){

			error('Ingrese el nombre del cargo');
			document.getElementById('cargo_desempenado').focus();
			return false;


}else if(document.getElementById('fecha_ingreso').value==""){

			error('Ingrese la fecha de Ingreso');
			document.getElementById('fecha_ingreso').focus();
			return false;


}else if(document.getElementById('fecha_egreso').value==""){

			error('Ingrese la fecha de egreso');
			document.getElementById('fecha_egreso').focus();
			return false;

}else if(diferenciaFecha(document.getElementById('fecha_egreso').value, document.getElementById('fecha_ingreso').value)){

           error('la Fecha de ingreso no debe ser mayor a la fecha de egreso');
           return false;

}else if(document.getElementById('motivo_salida').value==""){

			error('Ingrese el motivo de la salida');
			document.getElementById('motivo_salida').focus();
			return false;


			}//fin else




}//fin function

function info_cnmp06_datos_bienes_valida(){


              if(document.getElementById('cod_bien').value==''){
					error('Ingrese la c&oacute;digo del bien');
					document.getElementById('cod_bien').focus();
					return false;

		}else if(document.getElementById('ano_compra').value==''){
					error('ingrese el a&ntilde;o de la compra');
					document.getElementById('ano_compra').focus();
					return false;

		}else if(document.getElementById('costo').value==''){
					error('ingrese el costo de la compra');
					document.getElementById('costo').focus();
					return false;

		}//fin else




}//fin function

function info_valida_cedula_soporte(){

    if(document.getElementById('cedula').value == ''){
			error('Inserte La Cedula');
			document.getElementById('cedula').focus();
			return false;
    }//fin if

}


function info_cnmp06_datos_amonestaciones_valida(){



if(document.getElementById('cod_amonestacion').value==""){

            error('Ingrese el c&oacute;digo de la amonestaci&oacute;n');
			document.getElementById('cod_amonestacion').focus();
			return false;

}else if(document.getElementById('nombre_apellido').value==""){

            error('Ingrese el nombre y apellido');
			document.getElementById('nombre_apellido').focus();
			return false;


}else if(document.getElementById('fecha_amonestacion').value==""){

            error('Ingrese la fecha de amonestaci&oacute;n');
			document.getElementById('fecha_amonestacion').focus();
			return false;



}else if(document.getElementById('cargo_ocupado').value==""){

            error('Ingrese el cargo que ocupa');
			document.getElementById('cargo_ocupado').focus();
			return false;


}else if(document.getElementById('concepto').value==""){

            error('Ingrese el concepto');
			document.getElementById('concepto').focus();
			return false;


}//fin

}//fin funcion validar



function info_cnmp06_datos_permisos_valida(){

      if(document.getElementById('entidad_federal').value==""){

			error('Ingrese el Tipo de permiso');
			document.getElementById('entidad_federal').focus();
			return false;


}else if(document.getElementById('cargo_desempenado').value==""){

			error('Ingrese el nombre del cargo');
			document.getElementById('cargo_desempenado').focus();
			return false;


}else if(document.getElementById('fecha_salida').value==""){

			error('Ingrese la fecha de Salida');
			document.getElementById('fecha_salida').focus();
			return false;


}else if(document.getElementById('fecha_reintegro').value==""){

			error('Ingrese la fecha de reintegro');
			document.getElementById('fecha_reintegro').focus();
			return false;

}else if(diferenciaFecha(document.getElementById('fecha_reintegro').value, document.getElementById('fecha_salida').value)){

           error('la Fecha de salida no debe ser mayor a la fecha de reintegro');
           return false;

}else if(document.getElementById('observaciones').value==""){

			error('Ingrese la observaci&oacute;n');
			document.getElementById('observaciones').focus();
			return false;


			}//fin else




}//fin function

function info_valida_cnmp06_datos_personales(){
if(document.getElementById('cedula').value==""){
showTab('dhtmlgoodies_tabView2',0);
			error('Ingrese la C&eacute;dula');
			document.getElementById('cedula').focus();
			return false;

}else if(document.getElementById('nacionalidad_V').checked==false && document.getElementById('nacionalidad_E').checked==false){
showTab('dhtmlgoodies_tabView2',0);
			error('Por Favor Seleccione La Nacionalidad Venezolano o Extranjero ');
			//document.getElementById('x_1').focus();
			return false;

}else if(document.getElementById('papellido').value==""){
showTab('dhtmlgoodies_tabView2',0);
			error('Ingrese el Primer Apellido');
			document.getElementById('papellido').focus();
			return false;

}else if(document.getElementById('pnombre').value==""){
showTab('dhtmlgoodies_tabView2',0);
			error('Ingrese el Primer Nombre');
			document.getElementById('pnombre').focus();
			return false;

}else if(document.getElementById('fecha_nacimiento').value==""){
showTab('dhtmlgoodies_tabView2',0);
			error('Ingrese la Fecha de Nacimiento');
			document.getElementById('fecha_nacimiento').focus();
			return false;

}else if(document.getElementById('sexo_M').checked==false && document.getElementById('sexo_F').checked==false){
showTab('dhtmlgoodies_tabView2',0);
			error('Por Favor Seleccione El Sexo Femenino o Masculino ');
			//document.getElementById('x_1').focus();
			return false;

}else if(document.getElementById('estado_civil_C').checked==false && document.getElementById('estado_civil_S').checked==false && document.getElementById('estado_civil_D' ).checked==false && document.getElementById('estado_civil_V').checked==false && document.getElementById('estado_civil_O').checked==false){
showTab('dhtmlgoodies_tabView2',0);
			error('Por Favor Seleccione El Estado Civil ');
			//document.getElementById('x_1').focus();
			return false;

}else if(document.getElementById('grupo_sanguineo').value==""){
showTab('dhtmlgoodies_tabView2',0);
			error('Por Favor Ingrese el Grupo Sanguineo');
			document.getElementById('grupo_sanguineo').focus();
			return false;

}else if(document.getElementById('peso').value==""){
showTab('dhtmlgoodies_tabView2',0);
			error('Por Favor Ingrese el Peso En Kilos');
			document.getElementById('peso').focus();
			return false;

}else if(document.getElementById('estatura').value==""){
showTab('dhtmlgoodies_tabView2',0);
			error('Por Favor Ingrese la Estatura en Metros');
			document.getElementById('estatura').focus();
			return false;

}else if(document.getElementById('x_1').value==""){
showTab('dhtmlgoodies_tabView2',0);
			error('Ingrese el Pais de Origen');
			document.getElementById('x_1').focus();
			return false;

}else if(document.getElementById('x_2').value==""){
showTab('dhtmlgoodies_tabView2',0);
			error('Ingrese el Estado de origen');
			document.getElementById('x_2').focus();
			return false;

}else if(document.getElementById('x_3').value==""){
showTab('dhtmlgoodies_tabView2',0);
			error('Ingrese el Municipio de origen');
			document.getElementById('x_3').focus();
			return false;

}else if(document.getElementById('x_4').value==""){
showTab('dhtmlgoodies_tabView2',0);
			error('Ingrese la Parroquia de origen');
			document.getElementById('x_4').focus();
			return false;

}else if(document.getElementById('x_5').value==""){
showTab('dhtmlgoodies_tabView2',0);
			error('Ingrese el Centro Poblado de origen');
			document.getElementById('x_5').focus();
			return false;


}else if(document.getElementById('c1').checked==false && document.getElementById('c2').checked==false && document.getElementById('c3').checked==false && document.getElementById('c4').checked==false && document.getElementById('c5').checked==false && document.getElementById('c6').checked==false){
            showTab('dhtmlgoodies_tabView2',0);
			error('Por Favor Seleccione El idioma');
			return false;

}else if(document.getElementById('y_6').value==""){
showTab('dhtmlgoodies_tabView2',0);
			error('Ingrese la Profesion');
			document.getElementById('y_6').focus();
			return false;

}else if(document.getElementById('y_7').value==""){
showTab('dhtmlgoodies_tabView2',0);
			error('Ingrese la Especialidad');
			document.getElementById('y_7').focus();
			return false;

}else if(document.getElementById('direccion_habitacion').value==""){
            showTab('dhtmlgoodies_tabView2',1);
			error('Ingrese la direcci&oacute;n Actual de Habitaci&oacute;n');
			document.getElementById('direccion_habitacion').focus();
			return false;

}else if(document.getElementById('telefonos').value==""){
showTab('dhtmlgoodies_tabView2',1);
			error('Ingrese Tel&eacute;fonos');
			document.getElementById('telefonos').focus();
			return false;

}else if(document.getElementById('z_8').value==""){
showTab('dhtmlgoodies_tabView2',1);
			error('Ingrese el Estado de Habitaci&oacute;n');
			document.getElementById('z_8').focus();
			return false;

}else if(document.getElementById('z_9').value==""){
showTab('dhtmlgoodies_tabView2',1);
			error('Ingrese el Municipio de Habitaci&oacute;n');
			document.getElementById('z_9').focus();
			return false;

}else if(document.getElementById('z_10').value==""){
showTab('dhtmlgoodies_tabView2',1);
			error('Ingrese la Parroquia de Habitaci&oacute;n');
			document.getElementById('z_10').focus();
			return false;

}else if(document.getElementById('z_11').value==""){
showTab('dhtmlgoodies_tabView2',1);
			error('Ingrese el Centro Poblado de Habitaci&oacute;n');
			document.getElementById('z_11').focus();
			return false;

}else if(document.getElementById('lentes_S').checked==false && document.getElementById('lentes_N').checked==false){
showTab('dhtmlgoodies_tabView2',1);
			error('Por Favor Seleccione si usa lente si o no ');
			//document.getElementById('x_1').focus();
			return false;

}else if(document.getElementById('talla_camisa').value==""){
showTab('dhtmlgoodies_tabView2',1);
			error('Ingrese la Talla de Camisa o Blusa');
			document.getElementById('talla_camisa').focus();
			return false;

}else if(document.getElementById('talla_pantalon').value==""){
showTab('dhtmlgoodies_tabView2',1);
			error('Ingrese la Talla de Pantalon o Falda');
			document.getElementById('talla_pantalon').focus();
			return false;

}else if(document.getElementById('talla_calzado').value==""){
showTab('dhtmlgoodies_tabView2',1);
			error('Ingrese la Talla Calzado');
			document.getElementById('talla_calzado').focus();
			return false;

}
}//fin funcion validar





function validar_recuperacion(){
	if(document.getElementById('email').value==''){
		error('Ingrese el E-mail');
		document.getElementById('email').focus();
		return false;
	}else if ((document.getElementById('email').value.search("@") == -1 ) || ( document.getElementById('email').value.search("[.*]" ) == -1 ) ) {
		error("Por favor, Revise el Email" );
		document.getElementById('email').focus();
		return false;
   }
 }

function nueva_clave(){
	if(document.getElementById('password').value==''){
		error('Ingrese la nueva clave');
		document.getElementById('password').focus();
		return false;
	}else if(document.getElementById('password2').value==''){
		error('Ingrese repetir clave');
		document.getElementById('password2').focus();
		return false;
	}else if(document.getElementById('password').value!='' && document.getElementById('password2').value!='' && document.getElementById('password').value!=document.getElementById('password2').value){
		error('Repetir Clave no coincide con la nueva Clave, verifique por favor');
		document.getElementById('password').focus();
		return false;
	}
}