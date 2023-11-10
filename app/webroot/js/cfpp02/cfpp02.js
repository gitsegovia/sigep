function valida_cfpp02(){


if(document.getElementById('codigo').value==''){

			fun_msj('Inserte el c&oacute;digo');
			document.getElementById('codigo').focus();
			return false;


}/*else if(document.getElementById('codigo').value == document.getElementById('aux_codigo').value){

			fun_msj('Compruebe el Nuevo Codigo');
			document.getElementById('codigo').focus();
			return false;


}else if(document.getElementById('existe').value=='si'){

			fun_msj('Inserte un codigo valido');
			document.getElementById('codigo').focus();
			return false;


}*/else if(document.getElementById('denominacion').value==''){

			fun_msj('Inserte la Denominaci&oacute;n');
			document.getElementById('denominacion').focus();
			return false;


}else if(document.getElementById('unidad_ejecutora').value==''){

			fun_msj('Inserte La Unidad Ejecutora');
			document.getElementById('unidad_ejecutora').focus();
			return false;



}else if(document.getElementById('objetivo').value==''){

			fun_msj('Inserte el objetivo');
			document.getElementById('objetivo').focus();
			return false;



}else if(document.getElementById('funcionario_responsable').value==''){

			fun_msj('Inserte el Funcionario Responsable');
			document.getElementById('funcionario_responsable').focus();
			return false;



}else{




 		   var checkOK = " 0123456789";
           var checkStr = document.getElementById('codigo').value;
           var Validcodigo = true;
           var validGroups = true;

           for (i = 0;  i < checkStr.length;  i++){
             ch = checkStr.charAt(i);
             for (j = 0;  j < checkOK.length;  j++)
                 if (ch == checkOK.charAt(j)){break;}
	            if (j == checkOK.length){
	             Validcodigo = false;
	            break;
	            }
			  }



if (!Validcodigo){

						fun_msj("El  campo c&oacute;digo es n&uacute;merico");
						document.getElementById('codigo').focus();
						return false;

}


}//fin else




}//fin function

function valida2_cfpp02(){


if(document.getElementById('codigo').value==''){

			fun_msj('Inserte el c&oacute;digo');
			document.getElementById('codigo').focus();
			return false;


}/*else if(document.getElementById('codigo').value == document.getElementById('aux_codigo').value){

			fun_msj('Compruebe el Nuevo Codigo');
			document.getElementById('codigo').focus();
			return false;


}*/else if(document.getElementById('existe').value=='si'){

			fun_msj('Inserte un c&oacute;digo valido');
			document.getElementById('codigo').focus();
			return false;


}else if(document.getElementById('denominacion').value==''){

			fun_msj('Inserte la Denominaci&oacute;n');
			document.getElementById('denominacion').focus();
			return false;


}else if(document.getElementById('unidad_ejecutora').value==''){

			fun_msj('Inserte La Unidad Ejecutora');
			document.getElementById('unidad_ejecutora').focus();
			return false;



}else if(document.getElementById('objetivo').value==''){

			fun_msj('Inserte el objetivo');
			document.getElementById('objetivo').focus();
			return false;



}else if(document.getElementById('funcionario_responsable').value==''){

			fun_msj('Inserte el Funcionario Responsable');
			document.getElementById('funcionario_responsable').focus();
			return false;



}else{




 		   var checkOK = " 0123456789";
           var checkStr = document.getElementById('codigo').value;
           var Validcodigo = true;
           var validGroups = true;

           for (i = 0;  i < checkStr.length;  i++){
             ch = checkStr.charAt(i);
             for (j = 0;  j < checkOK.length;  j++)
                 if (ch == checkOK.charAt(j)){break;}
	            if (j == checkOK.length){
	             Validcodigo = false;
	            break;
	            }
			  }


}//fin else




}//fin function



function valida_cnmp02_obreros_puestos(){

if(document.getElementById('select_1').value==''){

			fun_msj('ingrese la Descripci&oacute;n del Tipo de Personal');
			document.getElementById('select_1').focus();
			return false;

}else if(document.getElementById('select_2').value==''){

			fun_msj('ingrese la Descripci&oacute;n del &Aacute;rea Ocupacional');
			document.getElementById('select_2').focus();
			return false;

}else if(document.getElementById('select_3').value==''){

			fun_msj('ingrese la Descripci&oacute;n de Clasificaci&oacute;n de Personal');
			document.getElementById('select_3').focus();
			return false;

}else if(document.getElementById('cod_puesto').value==''){

			fun_msj('ingrese EL C&Oacute;DIGO de la Clase');
			document.getElementById('cod_puesto').focus();
			return false;


}else if($('cod_puesto').value.length<=3){

			fun_msj('EL C&Oacute;DIGO DEBE SER DE 4 DIGITOS');
			document.getElementById('cod_puesto').focus();
			return false;


}else if(document.getElementById('title').value==''){

			fun_msj('ingrese el titulo del Puesto');
			document.getElementById('title').focus();
			return false;


}else if(document.getElementById('grado').value==''){

			fun_msj('ingrese el grado del puesto');
			document.getElementById('grado').focus();
			return false;


}else if(document.getElementById('text1').value==''){

			fun_msj('ingrese la labor general');
			document.getElementById('text1').focus();
			return false;


}else if(document.getElementById('text2').value==''){

			fun_msj('ingrese labor especifica');
			document.getElementById('text2').focus();
			return false;


}else if(document.getElementById('text3').value==''){

			fun_msj('ingrese el nivel educativo y conocimientos requeridos');
			document.getElementById('text3').focus();
			return false;


}else if(document.getElementById('text4').value==''){

			fun_msj('ingrese la experiencia');
			document.getElementById('text4').focus();
			return false;


}else if(document.getElementById('text5').value==''){

			fun_msj('ingrese las licencias y/o certificados');
			document.getElementById('text5').focus();
			return false;


}else if(document.getElementById('text6').value==''){

			fun_msj('ingrese habilidades y/o destrezas');
			document.getElementById('text6').focus();
			return false;


}else if(document.getElementById('text7').value==''){

			fun_msj('ingrese las condiciones fisicas');
			document.getElementById('text7').focus();
			return false;


}else if(document.getElementById('text8').value==''){

			fun_msj('ingrese las condiciones AMBIENTALES');
			document.getElementById('text8').focus();
			return false;


}




}//fin function













function valida_cnmp02_obreros_puestos_editar(){

       if(document.getElementById('title').value==''){

			fun_msj('ingrese el titulo del Puesto');
			document.getElementById('title').focus();
			return false;


}else if(document.getElementById('grado').value==''){

			fun_msj('ingrese el grado del puesto');
			document.getElementById('grado').focus();
			return false;


}else if(document.getElementById('text1').value==''){

			fun_msj('ingrese labor general');
			document.getElementById('text1').focus();
			return false;


}else if(document.getElementById('text2').value==''){

			fun_msj('ingrese la labor especifica');
			document.getElementById('text2').focus();
			return false;


}else if(document.getElementById('text3').value==''){

			fun_msj('ingrese el nivel educativo y conocimientos requeridos');
			document.getElementById('text3').focus();
			return false;


}else if(document.getElementById('text4').value==''){

			fun_msj('ingrese la experiencia');
			document.getElementById('text4').focus();
			return false;


}else if(document.getElementById('text5').value==''){

			fun_msj('ingrese las licencias y/o certificados');
			document.getElementById('text5').focus();
			return false;


}else if(document.getElementById('text6').value==''){

			fun_msj('ingrese habilidades y/o destrezas');
			document.getElementById('text6').focus();
			return false;


}else if(document.getElementById('text7').value==''){

			fun_msj('ingrese las condiciones fisicas');
			document.getElementById('text7').focus();
			return false;


}else if(document.getElementById('text8').value==''){

			fun_msj('ingrese las condiciones AMBIENTALES');
			document.getElementById('text8').focus();
			return false;


}



}//fin function
















function valida_cnmp02_empleados_puestos(){


if(document.getElementById('select_1').value==''){

			fun_msj('ingrese la Descripci&oacute;n del Tipo de Personal');
			document.getElementById('select_1').focus();
			return false;

}else if(document.getElementById('select_2').value==''){

			fun_msj('ingrese la Descripci&oacute;n del &Aacute;rea Ocupacional');
			document.getElementById('select_2').focus();
			return false;

}else if(document.getElementById('select_3').value==''){

			fun_msj('ingrese la Descripci&oacute;n de Clasificaci&oacute;n de Personal');
			document.getElementById('select_3').focus();
			return false;

}else if(document.getElementById('cod_puesto').value==''){

			fun_msj('ingrese EL C&Oacute;DIGO de la Clase');
			document.getElementById('cod_puesto').focus();
			return false;


}else if($('cod_puesto').value.length<=4){

			fun_msj('EL C&Oacute;DIGO DEBE SER DE 5 DIGITOS');
			document.getElementById('cod_puesto').focus();
			return false;


}else if(document.getElementById('title').value==''){

			fun_msj('Ingrese el t&iacute;tulo de la clase');
			document.getElementById('title').focus();
			return false;


}else if(document.getElementById('grado').value==''){

			fun_msj('Ingrese el grado de la clase');
			document.getElementById('grado').focus();
			return false;


}else if(document.getElementById('text1').value==''){

			fun_msj('Ingrese las caracteristicas del trabajo');
			document.getElementById('text1').focus();
			return false;


}else if(document.getElementById('text2').value==''){

			fun_msj('Ingrese las tareas tipicas');
			document.getElementById('text2').focus();
			return false;


}else if(document.getElementById('text3').value==''){

			fun_msj('Ingrese los requisitos minimos');
			document.getElementById('text3').focus();
			return false;


}else if(document.getElementById('text4').value==''){

			fun_msj('Ingrese la educaci&Oacute;n');
			document.getElementById('text4').focus();
			return false;


}else if(document.getElementById('text5').value==''){

			fun_msj('Ingrese los conocimientos, habilidades y destrezas');
			document.getElementById('text5').focus();
			return false;


}else if(document.getElementById('text6').value==''){

			fun_msj('Ingrese la clase de cargo');
			document.getElementById('text6').focus();
			return false;


}




}//fin function




















function valida_cnmp02_empleados_puestos_editar(){


       if(document.getElementById('title').value==''){

			fun_msj('Ingrese el t&iacute;tulo de la clase');
			document.getElementById('title').focus();
			return false;


}else if(document.getElementById('grado').value==''){

			fun_msj('Ingrese el grado de la clase');
			document.getElementById('grado').focus();
			return false;


}else if(document.getElementById('text1').value==''){

			fun_msj('Ingrese las caracteristicas del trabajo');
			document.getElementById('text1').focus();
			return false;


}else if(document.getElementById('text2').value==''){

			fun_msj('Ingrese las tareas tipicas');
			document.getElementById('text2').focus();
			return false;


}else if(document.getElementById('text3').value==''){

			fun_msj('Ingrese los requisitos minimos');
			document.getElementById('text3').focus();
			return false;


}else if(document.getElementById('text4').value==''){

			fun_msj('Ingrese la educaci&Oacute;n');
			document.getElementById('text4').focus();
			return false;


}else if(document.getElementById('text5').value==''){

			fun_msj('Ingrese los conocimientos, habilidades y destrezas');
			document.getElementById('text5').focus();
			return false;


}else if(document.getElementById('text6').value==''){

			fun_msj('Ingrese la clase de cargo');
			document.getElementById('text6').focus();
			return false;


}




}//fin function











function valida_cnmp02_confianza_puestos(){



      if(document.getElementById('cod_puesto').value==''){

            fun_msj('Ingrese el c&oacute;digo del puesto');
			document.getElementById('cod_puesto').focus();
			return false;


}else if(document.getElementById('title').value==''){

			fun_msj('Ingrese el titulo de la clase');
			document.getElementById('title').focus();
			return false;


}else if(document.getElementById('grado').value==''){

			fun_msj('Ingrese el grado de la clase');
			document.getElementById('grado').focus();
			return false;


}else if(document.getElementById('text1').value==''){

			fun_msj('Ingrese las caracteristicas del trabajo');
			document.getElementById('text1').focus();
			return false;


}else if(document.getElementById('text2').value==''){

			fun_msj('Ingrese las tareas tipicas');
			document.getElementById('text2').focus();
			return false;


}else if(document.getElementById('text3').value==''){

			fun_msj('Ingrese los requisitos minimos');
			document.getElementById('text3').focus();
			return false;


}else if(document.getElementById('text4').value==''){

			fun_msj('Ingrese la educaci&oacute;n');
			document.getElementById('text4').focus();
			return false;


}else if(document.getElementById('text5').value==''){

			fun_msj('Ingrese los conocimientos, habilidades y destrezas');
			document.getElementById('text5').focus();
			return false;


}else if(document.getElementById('text6').value==''){

			fun_msj('Ingrese la clase de cargo');
			document.getElementById('text6').focus();
			return false;




}else{




}//fin else




}



function valida_cfpp02_ano(){


	if(document.getElementById('ano_presupuesto').value==''){

			fun_msj('Inserte el a&ntilde;o presupuestario');
			document.getElementById('ano_presupuesto').focus();
			return false;

	}else if(document.getElementById('ano_presupuesto').value.length<4){

			fun_msj('Inserte un a&ntilde;o correcto');
			document.getElementById('ano_presupuesto').focus();
			return false;

	}//fin


}

function valida_catalogo(){



if(document.getElementById('cod_snc').value==''){

			fun_msj('Ingrese el Codigo SNC');
			document.getElementById('cod_snc').focus();
			return false;


}else if(document.getElementById('denominacion').value==''){

			fun_msj('Ingrese la denominaci&oacute;n del tipo de producto o servicio');
			document.getElementById('denominacion').focus();
			return false;


}else if(document.getElementById('cod_medida').value==''){

			fun_msj('seleccione la unidad de medida');
			document.getElementById('cod_medida').focus();
			return false;


}else if(document.getElementById('seleccion_1').value==''){

			fun_msj('Inserte el c&oacute;digo de la Partida');
			document.getElementById('seleccion_1').focus();
			return false;


}else if(document.getElementById('seleccion_2').value==''){

			fun_msj('Inserte el c&oacute;digo de la generica');
			document.getElementById('seleccion_2').focus();
			return false;


}else if(document.getElementById('seleccion_3').value==''){

			fun_msj('Inserte el c&oacute;digo de la especifica');
			document.getElementById('seleccion_3').focus();
			return false;


}else if(document.getElementById('alicuota').value==''){

			fun_msj('Inserte la alicuota del iva');
			document.getElementById('alicuota').focus();
			return false;


}/*else if(document.getElementById('valida').value != document.getElementById('aux_codigo').value && document.getElementById('valida').value != 0){

			fun_msj('Compruebe el Nuevo Codigo');
			document.getElementById('valida').focus();
			return false;


}else if(document.getElementById('existe').value=='si'){

			fun_msj('Inserte un codigo valido');
			document.getElementById('valida').focus();
			return false;


}*/else{


}//fin else




}



function valida_cnmp15_datos_personales(){

	if(document.getElementById('sel_cod_nomina').value==''){

			fun_msj('seleccione el c&oacute;digo de n&oacute;mina');
			document.getElementById('sel_cod_nomina').focus();
			return false;

	}else if(document.getElementById('cedula_iden').value==''){

			fun_msj('Ingrese la c&eacute;dula de identidad');
			document.getElementById('cedula_iden').focus();
			return false;

	}else if(document.getElementById('primer_apellido').value==''){

			fun_msj('Ingrese el primer apellido');
			document.getElementById('primer_apellido').focus();
			return false;


	}else if(document.getElementById('primer_nombre').value==''){

			fun_msj('Ingrese el primer nombre');
			document.getElementById('primer_nombre').focus();
			return false;


	}else if(document.getElementById('institucion').value==''){

			fun_msj('Ingrese la instituci&oacute;n');
			document.getElementById('institucion').focus();
			return false;

	}else if(document.getElementById('dependencia').value==''){

			fun_msj('Ingrese la dependencia');
			document.getElementById('dependencia').focus();
			return false;

	}else if(document.getElementById('cargo').value==''){

			fun_msj('Ingrese el cargo ocupado');
			document.getElementById('cargo').focus();
			return false;

	}else if(document.getElementById('fecha_ingreso').value==''){

			fun_msj('Ingrese la fecha de ingreso');
			document.getElementById('fecha_ingreso').focus();
			return false;

	}else if(document.getElementById('fecha_egreso').value==''){

			fun_msj('Ingrese la fecha de egreso');
			document.getElementById('fecha_egreso').focus();
			return false;

	}else if(document.getElementById('motivo_retiro').value==''){

			fun_msj('seleccione el motivo del retiro');
			document.getElementById('motivo_retiro').focus();
			return false;

	}


}//FIN VALIDA cnmp15_datos_presonales








function get_edad(){


			if(document.getElementById('fecha_ingreso').value!="" && document.getElementById('fecha_egreso').value!=""){

						var fecha1 = new fecha(document.getElementById('fecha_ingreso').value);
						var fecha2 = new fecha(document.getElementById('fecha_egreso').value);
						//alert(document.getElementById('fecha_ingreso').value);
						//alert(document.getElementById('fecha_egreso').value);

						var miFecha1 = new Date(fecha1.anio, fecha1.mes, fecha1.dia);
						var miFecha2 = new Date(fecha2.anio, fecha2.mes, fecha2.dia);

						var diferencia = miFecha2.getTime() - miFecha1.getTime();
						var dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
						var meses = Math.floor(diferencia / (1000 * 60 * 60 * 24 * 30));
						var anios = Math.floor(diferencia / (1000 * 60 * 60 * 24 * 30 * 12));
						//alert(dias+' | '+meses+' | '+anios);
						var resta_dia = dias / 30;
						var resta_mes = meses / 12;
						//alert(resta_dia+' | '+resta_mes);
						if( eval(resta_dia) > eval(1)) dias = dias - (meses * 30);
						if( eval(resta_mes) > eval(1)) meses = meses - (anios * 12);

						document.getElementById('dias').value= dias;
						document.getElementById('meses').value= meses;
						document.getElementById('anios').value= anios;

			  }//fin if

	return;

}//fin function






function valida_cnmp02_obreros_ramos(){


if(document.getElementById('codigo').value==""){

			fun_msj('Por favor ingrese el c&oacute;digo del tipo de personal');
			document.getElementById('codigo').focus();
			return false;


}else if(document.getElementById('denominacion').value==""){

			fun_msj('por favor ingrese la denominaci&oacute;n del tipo de personal');
			document.getElementById('denominacion').focus();
			return false;

}
}

function valida_cnmp02_obreros_grupos(){


if(document.getElementById('x_1').value==""){

			fun_msj('Por favor seleccione el c&oacute;digo del tipo de personal');
			document.getElementById('x_1').focus();
			return false;


}else if(document.getElementById('cod_grupo').value==""){

			fun_msj('por favor ingrese el c&oacute;digo del &aacute;rea ocupacional');
			document.getElementById('cod_grupo').focus();
			return false;

}else if(document.getElementById('deno_grupo').value==""){

			fun_msj('por favor ingrese la denominaci&oacute;n del &aacute;rea ocupacional');
			document.getElementById('deno_grupo').focus();
			return false;

}
}


function valida_cnmp02_obreros_series(){


if(document.getElementById('x_1').value==""){

			fun_msj('Por favor seleccione el c&oacute;digo del tipo de personal');
			document.getElementById('x_1').focus();
			return false;


}else if(document.getElementById('x_2').value==""){

			fun_msj('Por favor seleccione el c&oacute;digo del &aacute;rea ocupacional');
			document.getElementById('x_2').focus();
			return false;


}else if(document.getElementById('cod_serie').value==""){

			fun_msj('por favor ingrese el c&oacute;digo de la clasificaci&oacute;n de personal');
			document.getElementById('cod_serie').focus();
			return false;


}else if(document.getElementById('deno_serie').value==""){

			fun_msj('por favor ingrese la denominaci&oacute;n de la clasificaci&oacute;n de personal');
			document.getElementById('deno_serie').focus();
			return false;

}
}

function valida_cnmp02_empleados_ramos(){


if(document.getElementById('codigo').value==""){

			fun_msj('Por favor ingrese el c&oacute;digo del ramo');
			document.getElementById('codigo').focus();
			return false;


}else if(document.getElementById('denominacion').value==""){

			fun_msj('por favor ingrese la denominaci&oacute;n del ramo');
			document.getElementById('denominacion').focus();
			return false;

}
}
function valida_cnmp02_empleados_series(){


if(document.getElementById('x_1').value==""){

			fun_msj('Por favor seleccione el c&oacute;digo del ramo');
			document.getElementById('x_1').focus();
			return false;


}else if(document.getElementById('x_2').value==""){

			fun_msj('Por favor seleccione el c&oacute;digo del grupo');
			document.getElementById('x_2').focus();
			return false;


}else if(document.getElementById('cod_serie').value==""){

			fun_msj('por favor ingrese el c&oacute;digo de la serie');
			document.getElementById('cod_serie').focus();
			return false;


}else if(document.getElementById('deno_serie').value==""){

			fun_msj('por favor ingrese la denominaci&oacute;n de la serie');
			document.getElementById('deno_serie').focus();
			return false;

}
}

function valida_cnmp02_empleados_grupos(){


if(document.getElementById('x_1').value==""){

			fun_msj('Por favor seleccione el c&oacute;digo del ramo');
			document.getElementById('x_1').focus();
			return false;


}else if(document.getElementById('cod_grupo').value==""){

			fun_msj('por favor ingrese el c&oacute;digo del grupo');
			document.getElementById('cod_grupo').focus();
			return false;

}else if(document.getElementById('deno_grupo').value==""){

			fun_msj('por favor ingrese la denominaci&oacute;n del grupo');
			document.getElementById('deno_grupo').focus();
			return false;

}
}
