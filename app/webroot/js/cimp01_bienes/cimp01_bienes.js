function valida_cimp01_clasificacion_tipo(){
	if(document.getElementById('codigo').value==''){

			fun_msj('Por favor ingrese el c&oacute;digo de la clasificaci&oacute;n tipo');
			document.getElementById('codigo').focus();
			return false;

	}else if(document.getElementById('denominacion').value==''){

			fun_msj('Por favor ingrese la denominaci&oacute;n');
			document.getElementById('denominacion').focus();
			return false;

	}

}//fin valida_cfpp10_reform_tipo



function valida_cimp01_clasificacion_grupo(){
	if(document.getElementById('x_1').value==''){

			fun_msj('Por favor seleccione la clasificaci&oacute;n - grupo');
			document.getElementById('x_1').focus();
			return false;

	}else if(document.getElementById('cod_grupo').value==''){

			fun_msj('Por favor ingrese el c&oacute;digo de la clasificaci&oacute;n - subgrupo');
			document.getElementById('cod_grupo').focus();
			return false;

	}else if(document.getElementById('deno_grupo').value==''){

			fun_msj('Por favor ingrese la denominaci&oacute;n de la clasificaci&oacute;n - subgrupo');
			document.getElementById('deno_grupo').focus();
			return false;

	}

}//fin valida_cfpp10_reform_tipo

function valida_cimp01_clasificacion_subgrupo(){
	if(document.getElementById('x_1').value==''){

			fun_msj('Por favor seleccione la clasificaci&oacute;n - grupo');
			document.getElementById('x_1').focus();
			return false;

	}else if(document.getElementById('x_2').value==''){

			fun_msj('Por favor seleccione la clasificaci&oacute;n - subgrupo');
			document.getElementById('x_2').focus();
			return false;

	}else if(document.getElementById('cod_subgrupo').value==''){

			fun_msj('Por favor ingrese el c&oacute;digo de la clasificaci&oacute;n - secci&oacute;n');
			document.getElementById('cod_subgrupo').focus();
			return false;

	}else if(document.getElementById('deno_subgrupo').value==''){

			fun_msj('Por favor ingrese la denominaci&oacute;n de la clasificaci&oacute;n - secci&oacute;n');
			document.getElementById('deno_subgrupo').focus();
			return false;

	}

}//fin valida_cfpp10_reform_tipo

function valida_cimp01_clasificacion_seccion(){
	if(document.getElementById('x_1').value==''){

			fun_msj('Por favor seleccione la clasificaci&oacute;n - grupo');
			document.getElementById('x_1').focus();
			return false;

	}else if(document.getElementById('x_2').value==''){

			fun_msj('Por favor seleccione la clasificaci&oacute;n - subgrupo');
			document.getElementById('x_2').focus();
			return false;

	}else if(document.getElementById('x_3').value==''){

			fun_msj('Por favor seleccione la clasificaci&oacute;n - secci&oacute;n');
			document.getElementById('x_2').focus();
			return false;

	}else if(document.getElementById('cod_seccion').value==''){

			fun_msj('Por favor ingrese el c&oacute;digo de la clasificaci&oacute;n - subsecci&oacute;n');
			document.getElementById('cod_seccion').focus();
			return false;

	}else if(document.getElementById('cod_seccion').value==0){

			fun_msj('Por favor ingrese un c&oacute;digo v&aacute;lido de la clasificaci&oacute;n - subsecci&oacute;n');
			document.getElementById('cod_seccion').focus();
			return false;

	}else if(document.getElementById('deno_seccion').value==''){

			fun_msj('Por favor ingrese la denominaci&oacute;n de la clasificaci&oacute;n - subsecci&oacute;n');
			document.getElementById('deno_seccion').focus();
			return false;

	}else if(document.getElementById('especificaciones').value==''){

			fun_msj('Por favor ingrese las especificaciones');
			document.getElementById('especificaciones').focus();
			return false;

	}

}//fin valida_cfpp10_reform_tipo

function valida_cimp02_tipo_movimiento(){
	if(document.getElementById('x_1').value==''){

			fun_msj('Por favor seleccione el tipo de movimiento');
			document.getElementById('x_1').focus();
			return false;

	}else if(document.getElementById('cod_mov').value==''){

			fun_msj('Por favor ingrese el c&oacute;digo del movimiento');
			document.getElementById('cod_mov').focus();
			return false;

	}else if(document.getElementById('deno_mov').value==''){

			fun_msj('Por favor ingrese la denominaci&oacute;n del movimiento');
			document.getElementById('deno_mov').focus();
			return false;

	}

}//fin valida_cfpp10_reform_tipo


function valida_inventario_muebles_modi(){
	 if(document.getElementById('cod_tipo').value==''){

			fun_msj('Por favor realice la busqueda del clasificador');
			document.getElementById('cod_tipo').focus();
			return false;

	}if(document.getElementById('numero_identificacion').value==''){

			fun_msj('Por favor ingrese el numero de identificaci&oacute;n');
			document.getElementById('numero_identificacion').focus();
			return false;

	}else if(document.getElementById('denominacion').value==''){

			fun_msj('Por favor ingrese la denominaci&oacute;n bien mueble');
			document.getElementById('denominacion').focus();
			return false;

	}else if(document.getElementById('numero_a_registrar').value==''){

			fun_msj('Por favor ingrese el n&uacute;mero de bienes a registrar');
			document.getElementById('numero_a_registrar').focus();
			return false;

	}else if(document.getElementById('cantidad').value==''){

			fun_msj('Por favor ingrese la cantidad');
			document.getElementById('cantidad').focus();
			return false;

	}else if(eval(document.getElementById('numero_a_registrar').value)==eval(0)){

			fun_msj('Por favor ingrese el n&uacute;mero de bienes a registrar');
			document.getElementById('numero_a_registrar').focus();
			return false;

	}else if(eval(document.getElementById('cantidad').value)==eval(0)){

			fun_msj('Por favor ingrese la cantidad');
			document.getElementById('cantidad').focus();
			return false;

	}else if(document.getElementById('valor_unitario').value==''){

			fun_msj('Por favor ingrese el valor unitario del bien mueble');
			document.getElementById('valor_unitario').focus();
			return false;

	}else if(document.getElementById('select_incorporacion').value==''){

			fun_msj('Por favor seleccione el c&oacute;digo de la incorporaci&oacute;n');
			document.getElementById('select_incorporacion').focus();
			return false;

	}else if(document.getElementById('fecha_incorporacion').value==''){

			fun_msj('Por favor seleccione la fecha de la incorporaci&oacute;n');
			document.getElementById('fecha_incorporacion').focus();
			return false;

	}else if(document.getElementById('select_desincorporacion').value=='' && (document.getElementById('fecha_desincorporacion').value!='' || document.getElementById('observaciones').value!='')){

			fun_msj('Por favor seleccione el c&oacute;digo de la desincorporaci&oacute;n');
			document.getElementById('select_desincorporacion').focus();
			return false;

	}else if(document.getElementById('select_desincorporacion').value!='' && document.getElementById('fecha_desincorporacion').value==''){

			fun_msj('Por favor seleccione la fecha de la desincorporaci&oacute;n');
			document.getElementById('fecha_desincorporacion').focus();
			return false;

	}else if(document.getElementById('select_desincorporacion').value!='' && document.getElementById('observaciones').value==''){

			fun_msj('Por favor ingrese las observaciones para la desincorporaci&oacute;n');
			document.getElementById('observaciones').focus();
			return false;

	}else if(document.getElementById('s_1').value==''){

			fun_msj('Por favor seleccione el estado correspondiente');
			document.getElementById('s_1').focus();
			return false;

	}else if(document.getElementById('s_2').value==''){

			fun_msj('Por favor seleccione el municipio correspondiente');
			document.getElementById('s_2').focus();
			return false;

	}else if(document.getElementById('s_3').value==''){

			fun_msj('Por favor seleccione la parroquia correspondiente');
			document.getElementById('s_3').focus();
			return false;

	}else if(document.getElementById('s_4').value==''){

			fun_msj('Por favor seleccione el centro poblado correspondiente');
			document.getElementById('s_4').focus();
			return false;

	}else if(document.getElementById('x_5').value==''){

			fun_msj('Por favor seleccione la instituc&oacute;n correspondiente');
			document.getElementById('x_5').focus();
			return false;

	}else if(document.getElementById('x_6').value==''){

			fun_msj('Por favor seleccione la dependencia correspondiente');
			document.getElementById('x_6').focus();
			return false;

	}else if(document.getElementById('x_7').value==''){

			fun_msj('Por favor seleccione la direcci&oacute;n superior correspondiente');
			document.getElementById('x_7').focus();
			return false;

	}else if(document.getElementById('x_8').value==''){

			fun_msj('Por favor seleccione la coordinaci&oacute;n correspondiente');
			document.getElementById('x_8').focus();
			return false;

	}else if(document.getElementById('x_9').value==''){

			fun_msj('Por favor seleccione la secretaria correspondiente');
			document.getElementById('x_9').focus();
			return false;

	}else if(document.getElementById('x_10').value==''){

			fun_msj('Por favor seleccione la direcci&oacute;n correspondiente');
			document.getElementById('x_10').focus();
			return false;

	}else if(document.getElementById('x_11').value==''){

			fun_msj('Por favor seleccione la divisi&oacute;n correspondiente');
			document.getElementById('x_11').focus();
			return false;

	}else if(document.getElementById('x_12').value==''){

			fun_msj('Por favor seleccione el departamento correspondiente');
			document.getElementById('x_12').focus();
			return false;

	}else if(document.getElementById('x_13').value==''){

			fun_msj('Por favor seleccione la oficina correspondiente');
			document.getElementById('x_13').focus();
			return false;

	//desincorporacion año ejecucion
	}else if(eval(document.getElementById('select_incorporacion').value)!=eval(0) && eval(document.getElementById('select_desincorporacion').value)!=eval(0)){

		if(document.getElementById('select_desincorporacion').value!='' && verifica_cierre_ano_ejecucion('fecha_desincorporacion')==false){
			fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA DE DESINCORPORACI&Oacute;N NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
			return false;
		}
	}

}//fin valida_inventario_muebles_modi


function valida_inventario_muebles(){
	 if(document.getElementById('cod_tipo').value==''){

			fun_msj('Por favor realice la busqueda del clasificador');
			document.getElementById('cod_tipo').focus();
			return false;

	}if(document.getElementById('numero_identificacion').value==''){

			fun_msj('Por favor ingrese el numero de identificaci&oacute;n');
			document.getElementById('numero_identificacion').focus();
			return false;

	}else if(document.getElementById('denominacion').value==''){

			fun_msj('Por favor ingrese la denominaci&oacute;n bien mueble');
			document.getElementById('denominacion').focus();
			return false;

	}else if(document.getElementById('numero_a_registrar').value==''){

			fun_msj('Por favor ingrese el n&uacute;mero de bienes a registrar');
			document.getElementById('numero_a_registrar').focus();
			return false;

	}else if(document.getElementById('cantidad').value==''){

			fun_msj('Por favor ingrese la cantidad');
			document.getElementById('cantidad').focus();
			return false;

	}else if(eval(document.getElementById('numero_a_registrar').value)==eval(0)){

			fun_msj('Por favor ingrese el n&uacute;mero de bienes a registrar');
			document.getElementById('numero_a_registrar').focus();
			return false;

	}else if(eval(document.getElementById('cantidad').value)==eval(0)){

			fun_msj('Por favor ingrese la cantidad');
			document.getElementById('cantidad').focus();
			return false;

	}else if(document.getElementById('valor_unitario').value==''){

			fun_msj('Por favor ingrese el valor unitario del bien mueble');
			document.getElementById('valor_unitario').focus();
			return false;

	}else if(document.getElementById('select_incorporacion').value==''){

			fun_msj('Por favor seleccione el c&oacute;digo de la incorporaci&oacute;n');
			document.getElementById('select_incorporacion').focus();
			return false;

	}else if(document.getElementById('fecha_incorporacion').value==''){

			fun_msj('Por favor seleccione la fecha de la incorporaci&oacute;n');
			document.getElementById('fecha_incorporacion').focus();
			return false;

	}else if(document.getElementById('s_1').value==''){

			fun_msj('Por favor seleccione el estado correspondiente');
			document.getElementById('s_1').focus();
			return false;

	}else if(document.getElementById('s_2').value==''){

			fun_msj('Por favor seleccione el municipio correspondiente');
			document.getElementById('s_2').focus();
			return false;

	}else if(document.getElementById('s_3').value==''){

			fun_msj('Por favor seleccione la parroquia correspondiente');
			document.getElementById('s_3').focus();
			return false;

	}else if(document.getElementById('s_4').value==''){

			fun_msj('Por favor seleccione el centro poblado correspondiente');
			document.getElementById('s_4').focus();
			return false;

	}else if(document.getElementById('x_5').value==''){

			fun_msj('Por favor seleccione la instituc&oacute;n correspondiente');
			document.getElementById('x_5').focus();
			return false;

	}else if(document.getElementById('x_6').value==''){

			fun_msj('Por favor seleccione la dependencia correspondiente');
			document.getElementById('x_6').focus();
			return false;

	}else if(document.getElementById('x_7').value==''){

			fun_msj('Por favor seleccione la direcci&oacute;n superior correspondiente');
			document.getElementById('x_7').focus();
			return false;

	}else if(document.getElementById('x_8').value==''){

			fun_msj('Por favor seleccione la coordinaci&oacute;n correspondiente');
			document.getElementById('x_8').focus();
			return false;

	}else if(document.getElementById('x_9').value==''){

			fun_msj('Por favor seleccione la secretaria correspondiente');
			document.getElementById('x_9').focus();
			return false;

	}else if(document.getElementById('x_10').value==''){

			fun_msj('Por favor seleccione la direcci&oacute;n correspondiente');
			document.getElementById('x_10').focus();
			return false;

	}else if(document.getElementById('x_11').value==''){

			fun_msj('Por favor seleccione la divisi&oacute;n correspondiente');
			document.getElementById('x_11').focus();
			return false;

	}else if(document.getElementById('x_12').value==''){

			fun_msj('Por favor seleccione el departamento correspondiente');
			document.getElementById('x_12').focus();
			return false;

	}else if(document.getElementById('x_13').value==''){

			fun_msj('Por favor seleccione la oficina correspondiente');
			document.getElementById('x_13').focus();
			return false;

	//incorporacion año ejecucion
	}else if(eval(document.getElementById('select_incorporacion').value)!=eval(0)){
/*
		if(verifica_cierre_ano_ejecucion('fecha_incorporacion')==false){
			fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA DE INCORPORACI&Oacute;N NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
			return false;
		}
*/
	}


}//fin valida_cfpp10_reform_tipo



function valida_inventario_inmuebles(){
	if(document.getElementById('numero_identificacion').value==''){

			fun_msj('Por favor ingrese el numero de identificaci&oacute;n');
			document.getElementById('numero_identificacion').focus();
			return false;

	}else if(document.getElementById('cod_tipo').value==''){

			fun_msj('Por favor realice la busqueda del clasificador');
			document.getElementById('cod_tipo').focus();
			return false;

	}else if(document.getElementById('denominacion').value==''){

			fun_msj('Por favor ingrese la denominaci&oacute;n bien inmueble');
			document.getElementById('denominacion').focus();
			return false;

	}else if(document.getElementById('s_1').value==''){

			fun_msj('Por favor seleccione el estado correspondiente');
			document.getElementById('s_1').focus();
			return false;

	}else if(document.getElementById('s_2').value==''){

			fun_msj('Por favor seleccione el municipio correspondiente');
			document.getElementById('s_2').focus();
			return false;

	}else if(document.getElementById('s_3').value==''){

			fun_msj('Por favor seleccione la parroquia correspondiente');
			document.getElementById('s_3').focus();
			return false;

	}else if(document.getElementById('s_4').value==''){

			fun_msj('Por favor seleccione el centro poblado correspondiente');
			document.getElementById('s_4').focus();
			return false;

	}else if(document.getElementById('s_5').value==''){

			fun_msj('Por favor seleccione la calle o avenida correspondiente');
			document.getElementById('s_5').focus();
			return false;

	}else if(document.getElementById('area_total_terreno').value==''){

			fun_msj('Por favor ingrese el &aacute;rea total de terreno');
			document.getElementById('area_total_terreno').focus();
			return false;

	}else if(document.getElementById('area_cubierta').value==''){

			fun_msj('Por favor ingrese el &aacute;rea cubierta');
			document.getElementById('area_cubierta').focus();
			return false;

	}else if(document.getElementById('area_cubierta').value==''){

			fun_msj('Por favor ingrese el &aacute;rea cubierta');
			document.getElementById('area_cubierta').focus();
			return false;

	}else if(document.getElementById('area_construccion').value==''){

			fun_msj('Por favor ingrese el &aacute;rea de la construcci&oacute;n');
			document.getElementById('area_construccion').focus();
			return false;

	}else if(document.getElementById('area_otras_instalaciones').value==''){

			fun_msj('Por favor ingrese el &aacute;rea de otras instalaciones');
			document.getElementById('area_otras_instalaciones').focus();
			return false;

	}else if(document.getElementById('area_total_construida').value==''){

			fun_msj('Por favor ingrese el &aacute;rea total construida');
			document.getElementById('area_total_construida').focus();
			return false;

	}else if(document.getElementById('avaluo_actual').value==''){

			fun_msj('Por favor ingrese avaluo actual');
			document.getElementById('avaluo_actual').focus();
			return false;

	}else if(document.getElementById('descripcion_inmueble').value==''){

			fun_msj('Por favor ingrese la descripci&oacute:n del inmueble');
			document.getElementById('descripcion_inmueble').focus();
			return false;

	}else if(document.getElementById('linderos').value==''){

			fun_msj('Por favor ingrese los linderos del inmueble');
			document.getElementById('linderos').focus();
			return false;

	}else if(document.getElementById('estudio_legal_propiedad').value==''){

			fun_msj('Por favor ingrese lo refrente al estudio legal de la propiedad');
			document.getElementById('estudio_legal_propiedad').focus();
			return false;

	}else if(document.getElementById('avaluo_comision').value==''){

			fun_msj('Por favor ingrese avaluo de la comisi&oacute;n');
			document.getElementById('avaluo_comision').focus();
			return false;

	}else if(document.getElementById('select_incorporacion').value==''){

			fun_msj('Por favor seleccione el c&oacute;digo de la incorporaci&oacute;n');
			document.getElementById('select_incorporacion').focus();
			return false;

	}else if(document.getElementById('fecha_incorporacion').value==''){

			fun_msj('Por favor seleccione la fecha de la incorporaci&oacute;n');
			document.getElementById('fecha_incorporacion').focus();
			return false;
/*
	}else if(verifica_cierre_ano_ejecucion('fecha_incorporacion')==false){
			fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA DE INCORPORACI&Oacute;N NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
			return false;
	}
*/
    }

}//fin valida_cfpp10_reform_tipo





function valida_vehiculo_asegurado(){
	if(document.getElementById('cod_tipo').value==''){

			fun_msj('Por favor realice la busqueda del vehiculo a registrar');
			document.getElementById('cod_tipo').focus();
			return false;

	}else if(document.getElementById('placa').value==''){

			fun_msj('Por favor ingrese la placa del vehiculo');
			document.getElementById('placa').focus();
			return false;

	}else if(document.getElementById('numero_poliza').value==''){

			fun_msj('Por favor ingrese el n&uacute;mero de la poliza');
			document.getElementById('numero_poliza').focus();
			return false;

	}else if(document.getElementById('monto_cobertura').value==''){

			fun_msj('Por favor ingrese el el monto de la cobertura');
			document.getElementById('monto_cobertura').focus();
			return false;

	}else if(document.getElementById('compania_aseguradora').value==''){

			fun_msj('Por favor ingrese la compa&ntilde;ia aseguradora');
			document.getElementById('compania_aseguradora').focus();
			return false;

	}else if(document.getElementById('descripcion_cobertura').value==''){

			fun_msj('Por favor ingrese la descripci&oacute;n de la cobertura');
			document.getElementById('descripcion_cobertura').focus();
			return false;

	}else if(document.getElementById('vehiculo_asignado').value==''){

			fun_msj('Por favor ingrese a qui&eacute;n fue asignado el vehiculo');
			document.getElementById('vehiculo_asignado').focus();
			return false;

	}


}//fin valida_cfpp10_reform_tipo

function valida_cimp05_equipos_mantenimiento(){

if(document.getElementById('cod_tipo').value==''){

			fun_msj('Por favor realice la busqueda del equipo a registrar');
			document.getElementById('cod_tipo').focus();
			return false;

}else if(document.getElementById('fecha_reparacion').value==""){

			fun_msj('Ingrese la fecha de la reparacion');
			document.getElementById('fecha_reparacion').focus();
			return false;

}else if(document.getElementById('select_repa').value==""){

			fun_msj('Seleccione el tipo de reparacion');
			document.getElementById('select_repa').focus();
			return false;

}else if(document.getElementById('select_repu').value==""){

			fun_msj('Seleccione el tipo de repuesto');
			document.getElementById('select_repu').focus();
			return false;

}else if(document.getElementById('cantidad').value==""){

			fun_msj('Ingrese la cantidad');
			document.getElementById('cantidad').focus();
			return false;

}else if(document.getElementById('costo_unitario').value==""){

			fun_msj('Ingrese el costo unitario');
			document.getElementById('costo_unitario').focus();
			return false;

}else if(document.getElementById('total').value==""){

			fun_msj('Ingrese el total');
			document.getElementById('total').focus();
			return false;

}else if(document.getElementById('tiempo_garantia').value==""){

			fun_msj('Ingrese el tiempo de garantia');
			document.getElementById('tiempo_garantia').focus();
			return false;

}else if(document.getElementById('tienda_taller').value==""){

			fun_msj('Ingrese la tienda o taller');
			document.getElementById('tienda_taller').focus();
			return false;

}else if(document.getElementById('tecnico_mecanico').value==""){

			fun_msj('Ingrese el tecnico o mecanico');
			document.getElementById('tecnico_mecanico').focus();
			return false;

}else if(document.getElementById('reparacion_efectuada').value==""){

			fun_msj('Ingrese la reparacion efectuada al equipo');
			document.getElementById('reparacion_efectuada').focus();
			return false;

}
}//fin valida_cimp05_equipos_mantenimiento


function v_firmantes_r_bienes(){
	if(document.getElementById('nombre_primera_firma').value==''){

			fun_msj('Por favor ingrese PREPARADOR POR');
			document.getElementById('nombre_primera_firma').focus();
			return false;

	}else if(document.getElementById('nombre_segunda_firma').value==''){

			fun_msj('Por favor ingrese APROBADO POR');
			document.getElementById('nombre_segunda_firma').focus();
			return false;

	}

}//fin valida_cfpp10_reform_tipo


function valida_cambio_inventario_bm(){
	 if(document.getElementById('x_5').value==''){

			fun_msj('Por favor seleccione la instituc&oacute;n actual correspondiente');
			document.getElementById('x_5').focus();
			return false;

	}else if(document.getElementById('x_6').value==''){

			fun_msj('Por favor seleccione la dependencia actual correspondiente');
			document.getElementById('x_6').focus();
			return false;

	}else if(document.getElementById('x_7').value==''){

			fun_msj('Por favor seleccione la direcci&oacute;n superior actual correspondiente');
			document.getElementById('x_7').focus();
			return false;

	}else if(document.getElementById('x_8').value==''){

			fun_msj('Por favor seleccione la coordinaci&oacute;n actual correspondiente');
			document.getElementById('x_8').focus();
			return false;

	}else if(document.getElementById('x_9').value==''){

			fun_msj('Por favor seleccione la secretaria actual correspondiente');
			document.getElementById('x_9').focus();
			return false;

	}else if(document.getElementById('x_10').value==''){

			fun_msj('Por favor seleccione la direcci&oacute;n actual correspondiente');
			document.getElementById('x_10').focus();
			return false;

	}else if(document.getElementById('x_11').value==''){

			fun_msj('Por favor seleccione la divisi&oacute;n actual correspondiente');
			document.getElementById('x_11').focus();
			return false;

	}else if(document.getElementById('x_12').value==''){

			fun_msj('Por favor seleccione el departamento actual correspondiente');
			document.getElementById('x_12').focus();
			return false;

	}else if(document.getElementById('x_13').value==''){

			fun_msj('Por favor seleccione la oficina actual correspondiente');
			document.getElementById('x_13').focus();
			return false;

	}else if(document.getElementById('cod_institucion2').value==''){

			fun_msj('Por favor seleccione la instituc&oacute;n correspondiente');
			document.getElementById('x2_5').focus();
			return false;

	}else if(document.getElementById('x2_6').value==''){

			fun_msj('Por favor seleccione la dependencia correspondiente');
			document.getElementById('x2_6').focus();
			return false;

	}else if(document.getElementById('x2_7').value==''){

			fun_msj('Por favor seleccione la direcci&oacute;n superior correspondiente');
			document.getElementById('x2_7').focus();
			return false;

	}else if(document.getElementById('x2_8').value==''){

			fun_msj('Por favor seleccione la coordinaci&oacute;n correspondiente');
			document.getElementById('x2_8').focus();
			return false;

	}else if(document.getElementById('x2_9').value==''){

			fun_msj('Por favor seleccione la secretaria correspondiente');
			document.getElementById('x2_9').focus();
			return false;

	}else if(document.getElementById('x2_10').value==''){

			fun_msj('Por favor seleccione la direcci&oacute;n correspondiente');
			document.getElementById('x2_10').focus();
			return false;

	}else if(document.getElementById('x2_11').value==''){

			fun_msj('Por favor seleccione la divisi&oacute;n correspondiente');
			document.getElementById('x2_11').focus();
			return false;

	}else if(document.getElementById('x2_12').value==''){

			fun_msj('Por favor seleccione el departamento correspondiente');
			document.getElementById('x2_12').focus();
			return false;

	}else if(document.getElementById('x2_13').value==''){

			fun_msj('Por favor seleccione la oficina correspondiente');
			document.getElementById('x2_13').focus();
			return false;

	}
}//fin valida_cambio_inventario_bm

function valida_cambio_clasif(){
	 if(document.getElementById('x_5').value==''){

			fun_msj('Por favor seleccione el grupo del clasificador actual');
			document.getElementById('x_5').focus();
			return false;

	}else if(document.getElementById('x_6').value==''){

			fun_msj('Por favor seleccione el subgrupo del clasificador actual');
			document.getElementById('x_6').focus();
			return false;

	}else if(document.getElementById('x_7').value==''){

			fun_msj('Por favor seleccione la secci&oacute;n del clasificador actual');
			document.getElementById('x_7').focus();
			return false;

	}else if(document.getElementById('x_8').value==''){

			fun_msj('Por favor seleccione la subsecci&oacute;n del clasificador actual');
			document.getElementById('x_8').focus();
			return false;

	}else if(document.getElementById('x2_5').value==''){

			fun_msj('Por favor seleccione el grupo del clasificador a cambiar');
			document.getElementById('x2_5').focus();
			return false;

	}else if(document.getElementById('x2_6').value==''){

			fun_msj('Por favor seleccione el subgrupo del clasificador a cambiar');
			document.getElementById('x2_6').focus();
			return false;

	}else if(document.getElementById('x2_7').value==''){

			fun_msj('Por favor seleccione la secci&oacute;n del clasificador a cambiar');
			document.getElementById('x2_7').focus();
			return false;

	}else if(document.getElementById('x2_8').value==''){

			fun_msj('Por favor seleccione la subsecci&oacute;n del clasificador a cambiar');
			document.getElementById('x2_8').focus();
			return false;

	}
}//fin valida_cambio_clasif
