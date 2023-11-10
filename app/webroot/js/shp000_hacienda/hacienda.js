function valida_codigo_ingresos(){
   if(document.getElementById('cod_ingreso').value==''){

			fun_msj('Ingrese el c&oacute;digo');
			document.getElementById('cod_ingreso').focus();
			return false;

	}else if(document.getElementById('deno_ingreso').value==''){

			fun_msj('Ingrese la denominaci&oacute;n');
			document.getElementById('deno_ingreso').focus();
			return false;


	}else if(document.getElementById('seleccion_1').value==''){

			fun_msj('Por favor seleccione el ramo correspondiente');
			document.getElementById('seleccion_1').focus();
			return false;


	}else if(document.getElementById('seleccion_2').value==''){

			fun_msj('Por favor seleccione la generica correspondiente');
			document.getElementById('seleccion_2').focus();
			return false;

	}else if(document.getElementById('seleccion_3').value==''){

			fun_msj('Por favor seleccione la especifica correspondiente');
			document.getElementById('seleccion_3').focus();
			return false;


	}else if(document.getElementById('seleccion_4').value==''){

			fun_msj('Por favor seleccione la sub especifica correspondiente');
			document.getElementById('seleccion_4').focus();
			return false;


	}else if(document.getElementById('seleccion_5').value==''){

			fun_msj('Por favor seleccione el auxiliar correspondiente');
			document.getElementById('seleccion_5').focus();
			return false;


	}

}//fin funtion


function valida_codigo_ingresos2(){
  if(document.getElementById('select_1').value==''){

			fun_msj('Por favor seleccione el ramo correspondiente');
			document.getElementById('select_1').focus();
			return false;


	}else if(document.getElementById('select_2').value==''){

			fun_msj('Por favor seleccione la generica correspondiente');
			document.getElementById('select_2').focus();
			return false;

	}else if(document.getElementById('select_3').value==''){

			fun_msj('Por favor seleccione la especifica correspondiente');
			document.getElementById('select_3').focus();
			return false;


	}else if(document.getElementById('select_4').value==''){

			fun_msj('Por favor seleccione la sub especifica correspondiente');
			document.getElementById('select_4').focus();
			return false;


	}else if(document.getElementById('select_5').value==''){

			fun_msj('Por favor seleccione el auxiliar correspondiente');
			document.getElementById('select_5').focus();
			return false;


	}

}//fin funtion



function valida_cobradores(){
   if(document.getElementById('rif').value==""){//arranca

			fun_msj('Inserte el Rif o Cedula de Identidad del cobrador');
			document.getElementById('rif').focus();
			return false;

	}else if(document.getElementById('nombre_razon').value==''){

			fun_msj('Por favor ingrese el nombre y apellellido o razon social del cobrador');
			document.getElementById('nombre_razon').focus();
			return false;


	}else if(document.getElementById('fecha_ingreso').value==''){

			fun_msj('Por favor seleccione la fecha de ingreso del cobrador');
			document.getElementById('fecha_ingreso').focus();
			return false;


	}

}//fin funtion

function valida_actividades(){
   if(document.getElementById('cod_actividad').value==''){

			fun_msj('Ingrese el c&oacute;digo de la actividad');
			document.getElementById('cod_actividad').focus();
			return false;

	}else if(document.getElementById('denominacion').value==''){

			fun_msj('Ingrese la denominaci&oacute;n');
			document.getElementById('denominacion').focus();
			return false;
	}/*else if(document.getElementById('alicuota').value!=''){
		if(document.getElementById('unidades').value==''){
				fun_msj('Ingrese las unidades tributarias');
				document.getElementById('unidades').focus();
				return false;
		}else if(document.getElementById('minimo').value==''){
				fun_msj('Ingrese el minimo tributario anual');
				document.getElementById('minimo').focus();
				return false;
		}
	}*/

}//fin funtion



function valida_contribuyentes(){
   if(document.getElementById('rif_cedula').value==''){

			fun_msj('Por favor ingrese el R.I.F. o la cedula de identidad del contribuyente');
			document.getElementById('rif_cedula').focus();
			return false;

	}else if(document.getElementById('razon_social').value==''){

			fun_msj('Por favor ingrese nombres y apellidos o la razon social del contribuyente');
			document.getElementById('razon_social').focus();
			return false;


	}else if(document.getElementById('fecha_inscripcion').value==''){

			fun_msj('Por favor seleccione la fecha de inscripci&oacute;n del contributente');
			document.getElementById('fecha_inscripcion').focus();
			return false;


	}else if((document.getElementById('personalidad_1').checked==true) &&  (document.getElementById('sel_prof').value=='')){

			fun_msj('Por favor seleccione la profesi&oacute;n del contribuyente');
			document.getElementById('sel_prof').focus();
			return false;

	}else if(document.getElementById('pais').value==''){

			fun_msj('Por favor seleccione el pais correspondiente al contribuyente');
			document.getElementById('pais').focus();
			return false;


	}else if(document.getElementById('estados').value==''){

			fun_msj('Por favor seleccione el estado correspondiente al contribuyente');
			document.getElementById('estados').focus();
			return false;


	}else if(document.getElementById('municipios').value==''){

			fun_msj('Por favor seleccione el municipio correspondiente al contribuyente');
			document.getElementById('municipios').focus();
			return false;


	}else if(document.getElementById('parroquias').value==''){

			fun_msj('Por favor seleccione la parroquia correspondiente al contribuyente');
			document.getElementById('parroquias').focus();
			return false;


	}else if(document.getElementById('centros').value==''){

			fun_msj('Por favor seleccione el centro poblado correspondiente al contribuyente');
			document.getElementById('centros').focus();
			return false;


	}else if(document.getElementById('calles').value==''){

			fun_msj('Por favor seleccione la calle o avenida correspondiente al contribuyente');
			document.getElementById('calles').focus();
			return false;


	}else if(document.getElementById('veredas').value==''){

			fun_msj('Por favor seleccione la vereda o edificio correspondiente al contribuyente');
			document.getElementById('veredas').focus();
			return false;


	}else if(document.getElementById('numero_local').value==''){

			fun_msj('Por favor ingrese el n&uacute;mero de casa o local correspondiente al contribuyente');
			document.getElementById('numero_local').focus();
			return false;


	}else if(document.getElementById('rif_cedula').value != "0" && document.getElementById('personalidad_2').checked==true){
                  var elRIF = document.getElementById('rif_cedula').value;
                  var temp = elRIF.toUpperCase();
				  if (!/^[JVEGPIRC]/.test(temp)){
				      alert("El primer caracter es incorrecto, debe ser una letra de las siguientes: J,V,E,G,P,I,R,C");
				      return false;
				  }
				  /*
					  else if (!/^[JVEGPIR][-][0-9]{8}[-][0-9]$/.test(temp)){ // Son 9 dígitos?
					     alert ("Por Favor Verifique que el RIF tenga el siguiente formato: J-12345678-9 \nSi su Rif es menor a 9 digitos rellene con 0 a la izquierda Ej: J-00123456-7\n Su rif actual es: "+temp);
					     return false;
					  }
				  */

    }
}//fin funtion

function valida_solicitud(){

         if(document.getElementById('numero_solicitud').value==''){

			fun_msj('Por favor ingrese el n&uacute;mero de solicitud correspondiente a la planilla');
			document.getElementById('numero_solicitud').focus();
			return false;

	}else if(document.getElementById('fecha_solicitud').value==''){

			fun_msj('Por favor seleccione la fecha de la solicitud de licencia');
			document.getElementById('fecha_solicitud').focus();
			return false;


	}else if(document.getElementById('rif_constribuyente').value==''){

			fun_msj('Por favor ingrese el R.I.F. o la c&eacute;dula de identidad del contribuyente');
			document.getElementById('rif_constribuyente').focus();
			return false;


	}else if(document.getElementById('capital').value==''){

			fun_msj('Por favor ingrese el capital del solicitante');
			document.getElementById('capital').focus();
			return false;


	}else if(document.getElementById('desde').value==''){

			fun_msj('Por favor ingrese la hora de inicio del trabajo del solicitante');
			document.getElementById('desde').focus();
			return false;


	}else if(document.getElementById('hasta').value==''){

			fun_msj('Por favor ingrese la hora de culminaci&oacute;n del trabajo del solicitante');
			document.getElementById('hasta').focus();
			return false;


	}else if(document.getElementById('cedula_representante').value==''){

			fun_msj('Por favor ingrese la c&eacute;dula de identidad del representante o propietario');
			document.getElementById('cedula_representante').focus();
			return false;


	}else if(document.getElementById('apellidos_nombres').value==''){

			fun_msj('Por favor ingrese la c&eacute;dula apellidos y nombres del representante o propietario');
			document.getElementById('apellidos_nombres').focus();
			return false;


	}else if(document.getElementById('pais').value==''){

			fun_msj('Por favor seleccione el pais correspondiente al representante');
			document.getElementById('pais').focus();
			return false;


	}else if(document.getElementById('estados').value==''){

			fun_msj('Por favor seleccione el estado correspondiente al representante');
			document.getElementById('estados').focus();
			return false;


	}else if(document.getElementById('municipios').value==''){

			fun_msj('Por favor seleccione el municipio correspondiente al representante');
			document.getElementById('municipios').focus();
			return false;


	}else if(document.getElementById('parroquias').value==''){

			fun_msj('Por favor seleccione la parroquia correspondiente al representante');
			document.getElementById('parroquias').focus();
			return false;


	}else if(document.getElementById('centros').value==''){

			fun_msj('Por favor seleccione el centro poblado correspondiente al representante');
			document.getElementById('centros').focus();
			return false;


	}else if(document.getElementById('calles').value==''){

			fun_msj('Por favor seleccione la calle o avenida correspondiente al representante');
			document.getElementById('calles').focus();
			return false;


	}else if(document.getElementById('veredas').value==''){

			fun_msj('Por favor seleccione la vereda o edificio correspondiente al representante');
			document.getElementById('veredas').focus();
			return false;


	}else if(document.getElementById('numero_local_repre').value==''){

			fun_msj('Por favor ingrese el n&uacute;mero de casa o local correspondiente al representante');
			document.getElementById('numero_local_repre').focus();
			return false;


	}else if(document.getElementById('inicio_constitucion').value==''){

			fun_msj('Por favor seleccione la fecha de inicio de constituci&oacute;n de la empresa');
			document.getElementById('inicio_constitucion').focus();
			return false;


	}else if(document.getElementById('cierre_constitucion').value==''){

			fun_msj('Por favor seleccione la fecha de cierre de constituci&oacute;n de la empresa');
			document.getElementById('cierre_constitucion').focus();
			return false;


	}else if(document.getElementById('inicio_ejercicio').value==''){

			fun_msj('Por favor seleccione la fecha de inicio del primer ejercicio economico de la empresa');
			document.getElementById('inicio_ejercicio').focus();
			return false;


	}else if(document.getElementById('cierre_ejercicio').value==''){

			fun_msj('Por favor seleccione la fecha de cierre del primer ejercicio econ&oacute;mico de la empresa');
			document.getElementById('cierre_ejercicio').focus();
			return false;


	}else if(document.getElementById('registro_mercantil').value==''){

			fun_msj('Por favor ingrese el registro mercantil de la empresa');
			document.getElementById('registro_mercantil').focus();
			return false;


	}else if(document.getElementById('cuenta_grilla').value==0){

			fun_msj('Por favor seleccione las actividades econ&oacute;micas de la empresa');
			document.getElementById('registro_mercantil').focus();
			return false;


	}/*else if(document.getElementById('c1').checked==false){

			fun_msj('marque el Registro mercantil en los RECAUDOS RECIBIDOS');
			document.getElementById('c1').focus();
			return false;


   }else if(document.getElementById('c2').checked==false){

			fun_msj('marque la Fotocopia de la c&eacute;dula de identidad en los RECAUDOS RECIBIDOS');
			document.getElementById('c2').focus();
			return false;


    }else if(document.getElementById('c3').checked==false){

			fun_msj('marque el Acta constitutiva en los RECAUDOS RECIBIDOS');
			document.getElementById('c3').focus();
			return false;


    }else if(document.getElementById('c4').checked==false){

			fun_msj('marque el Uso conforme aprobado en los RECAUDOS RECIBIDOS');
			document.getElementById('c4').focus();
			return false;


	}else if(document.getElementById('c5').checked==false){

			fun_msj('marque el Croquis ubicaci&oacute;n en los RECAUDOS RECIBIDOS');
			document.getElementById('c5').focus();
			return false;

    }else if(document.getElementById('c6').checked==false){

			fun_msj('marque la Certificaci&oacute;n de los bomberos en los RECAUDOS RECIBIDOS');
			document.getElementById('c6').focus();
			return false;

    }else if(document.getElementById('c7').checked==false){

			fun_msj('marque el Registro de informaci&oacute;n fiscal en los RECAUDOS RECIBIDOS');
			document.getElementById('c7').focus();
			return false;


    }else if(document.getElementById('c8').checked==false){

			fun_msj('marque la Solvencia del impuesto inmobiliario en los RECAUDOS RECIBIDOS');
			document.getElementById('c8').focus();
			return false;


    }else if(document.getElementById('c9').checked==false){

			fun_msj('marque la Carta de aprobaci&oacute;n del consejo comunal en los RECAUDOS RECIBIDOS');
			document.getElementById('c9').focus();
			return false;


    }else if(document.getElementById('c10').checked==false){

			fun_msj('marque el Recibo de pago de la tasa en los RECAUDOS RECIBIDOS');
			document.getElementById('c10').focus();
			return false;

    }else if(document.getElementById('c11').checked==false){

			fun_msj('marque la Planilla de la solicitud de la licencia en los RECAUDOS RECIBIDOS');
			document.getElementById('c11').focus();
			return false;

    }else if(document.getElementById('c12').checked==false){

			fun_msj('marque los Permiso expedido en los RECAUDOS RECIBIDOS');
			document.getElementById('c12').focus();
			return false;


	}*/



}//fin funtion







function guardar_vehiculos_contribuyentes(){
   if(document.getElementById('rif_constribuyente').value==""){//arranca

			fun_msj('Inserte el Rif o Cedula de Identidad del contribuyente');
			document.getElementById('rif_constribuyente').focus();
			return false;

	}else if(document.getElementById('numero_placa').value==''){

			fun_msj('Por favor ingrese el n&uacute;mero de la placa del veh&iacute;culo');
			document.getElementById('numero_placa').focus();
			return false;


	}else if(document.getElementById('fecha_registro').value==''){

			fun_msj('Por favor seleccione la fecha de registro del veh&iacute;culo');
			document.getElementById('fecha_registro').focus();
			return false;


	}else if(document.getElementById('select_marca').value==''){

			fun_msj('Por favor seleccione la marca del veh&iacute;culo');
			document.getElementById('select_marca').focus();
			return false;


	}else if(document.getElementById('select_modelo').value==''){

			fun_msj('Por favor seleccione el modelo del veh&iacute;culo');
			document.getElementById('select_modelo').focus();
			return false;


	}else if(document.getElementById('select_color').value==''){

			fun_msj('Por favor seleccione el color del veh&iacute;culo');
			document.getElementById('select_color').focus();
			return false;


	}else if(document.getElementById('select_clase').value==''){

			fun_msj('Por favor seleccione la clase del veh&iacute;culo');
			document.getElementById('select_clase').focus();
			return false;


	}else if(document.getElementById('select_tipo').value==''){

			fun_msj('Por favor seleccione el tipo del veh&iacute;culo');
			document.getElementById('select_tipo').focus();
			return false;


	}else if(document.getElementById('select_uso').value==''){

			fun_msj('Por favor seleccione el uso del veh&iacute;culo');
			document.getElementById('select_uso').focus();
			return false;


	}else if(document.getElementById('seria_carroceria').value==''){

			fun_msj('Por favor ingrese el serial de la carroceria del veh&iacute;culo');
			document.getElementById('seria_carroceria').focus();
			return false;


	}else if(document.getElementById('serial_motor').value==''){

			fun_msj('Por favor ingrese el serial del motor del veh&iacute;culo');
			document.getElementById('serial_motor').focus();
			return false;


	}else if(document.getElementById('ano_adquisicion').value==''){

			fun_msj('Por favor ingrese el a&ntilde;o de adquisicion del veh&iacute;culo');
			document.getElementById('ano_adquisicion').focus();
			return false;


	}else if(document.getElementById('valor_adquisicion').value==''){

			fun_msj('Por favor ingrese el valor de adquisicion del veh&iacute;culo');
			document.getElementById('valor_adquisicion').focus();
			return false;


	}else if(document.getElementById('fecha_adquisicion').value==''){

			fun_msj('Por favor ingrese la fecha de adquisicion del veh&iacute;culo');
			document.getElementById('fecha_adquisicion').focus();
			return false;


	}else if(document.getElementById('select_clasificacion').value==''){

			fun_msj('Por favor seleccione la clasificaci&oacute;n automotriz del veh&iacute;culo');
			document.getElementById('select_clasificacion').focus();
			return false;


	}else if(document.getElementById('select_rif').value==''){

			fun_msj('Por favor seleccione el cobrador');
			document.getElementById('select_rif').focus();
			return false;


	}

}//fin funtion


function guardar_shp400_propiedad(){
   if(document.getElementById('rif_constribuyente').value==''){

			fun_msj('POR FAVOR REGISTRE EL CONTRIBUYENTE EN EL REGISTRO GENERAL');
			document.getElementById('rif_constribuyente').focus();
			return false;

	}else if(document.getElementById('select_rif').value==''){

			fun_msj('Por favor seleccione el cobrador correspondiente');
			document.getElementById('select_rif').focus();
			return false;
	}
}//fin funtion

function guardar_shd600_solicitud_arrendamiento(){
   if(document.getElementById('rif_constribuyente').value==''){

			fun_msj('POR FAVOR REGISTRE EL CONTRIBUYENTE EN EL REGISTRO GENERAL');
			document.getElementById('rif_constribuyente').focus();
			return false;

	}else if(document.getElementById('numero_solicitud').value==''){

			fun_msj('Por favor ingrese el n&uacute;mero de solicitud');
			document.getElementById('numero_solicitud').focus();
			return false;

	}else if(document.getElementById('fecha_solicitud').value==''){

			fun_msj('Por favor la fecha de la solicitud');
			document.getElementById('fecha_solicitud').focus();
			return false;

	}else if(document.getElementById('expectativa').value==''){

			fun_msj('Por favor ingrese la expectativa de construcci&oacute;n');
			document.getElementById('expectativa').focus();
			return false;


	}
}//fin funtion

function guardar_shp600_aprobacion_arrendamiento(){
   if(document.getElementById('rif_constribuyente').value==''){

			fun_msj('Por favor ingrese el RIF / Cedula del contribuyente');
			document.getElementById('rif_constribuyente').focus();
			return false;

	}else if(document.getElementById('fecha_aprobacion').value==''){

			fun_msj('Por favor ingrese la fecha de aprobaci&oacute;n');
			document.getElementById('fecha_aprobacion').focus();
			return false;

	}else if(document.getElementById('datos_legales').value==''){

			fun_msj('Por favor ingrese datos legales del documento de arrendamiento');
			document.getElementById('datos_legales').focus();
			return false;

	}else if(document.getElementById('select_rif').value==''){

			fun_msj('Por favor seleccione el cobrador');
			document.getElementById('select_rif').focus();
			return false;


	}else if(document.getElementById('monto_pagar').value==''){

			fun_msj('Por favor ingrese el monto a pagar mensual');
			document.getElementById('monto_pagar').focus();
			return false;


	}
}//fin funtion



function guardar_shp600_compra_terreno(){
   if(document.getElementById('rif_constribuyente').value==''){

			fun_msj('Por favor ingrese el RIF / Cedula del contribuyente');
			document.getElementById('rif_constribuyente').focus();
			return false;

	}else if(document.getElementById('fecha_compra').value==''){

			fun_msj('Por favor ingrese la fecha de compra');
			document.getElementById('fecha_compra').focus();
			return false;

	}else if(document.getElementById('monto_venta').value==''){

			fun_msj('Por favor ingrese el monto de la venta');
			document.getElementById('monto_venta').focus();
			return false;

	}else if(document.getElementById('datos_venta').value==''){

			fun_msj('Por favor ingrese los datos legales de la venta del terreno');
			document.getElementById('datos_venta').focus();
			return false;

	}
}//fin funtion

function agregar_parentesco(){
   if(document.getElementById('cod_parentesco').value==''){

			fun_msj('Por favor seleccione el parentesco');
			document.getElementById('cod_parentesco').focus();
			return false;

	}else if(document.getElementById('nombre_parentesco').value==''){

			fun_msj('Por favor ingrese el nombre y apellido del parentesco');
			document.getElementById('nombre_parentesco').focus();
			return false;


	}else if(document.getElementById('fecha_nacimiento_parentesco').value==''){

			fun_msj('Por favor seleccione la fecha de nacimiento del parentezco');
			document.getElementById('fecha_nacimiento_parentesco').focus();
			return false;
	}

}//fin funtion


function guardar_shp700_credito_vivienda(){
    if(document.getElementById('numero_solicitud').value==''){

			fun_msj('Por favor ingrese el numero de solicitud');
			document.getElementById('numero_solicitud').focus();
			return false;

	}else if(document.getElementById('fecha_solicitud').value==''){

			fun_msj('Por favor seleccione la fecha de la solicitud');
			document.getElementById('fecha_solicitud').focus();
			return false;

	}else if(document.getElementById('rif_constribuyente').value==''){

			fun_msj('Por favor ingrese el RIF / Cedula del contribuyente');
			document.getElementById('rif_constribuyente').focus();
			return false;


	}else if(document.getElementById('grupo_familiar').value==''){

			fun_msj('Por favor ingrese grupo familiar');
			document.getElementById('grupo_familiar').focus();
			return false;
	}else if(document.getElementById('ingreso_mensual').value==''){

			fun_msj('Por favor ingrese el ingreso mensual');
			document.getElementById('ingreso_mensual').focus();
			return false;
	}else if(document.getElementById('tipo_vivienda').value==''){

			fun_msj('Por favor seleccione el tipo de vivienda');
			document.getElementById('tipo_vivienda').focus();
			return false;
	}else if(document.getElementById('area_construccion').value==''){

			fun_msj('Por favor ingrese el area de construcci&oacute;n');
			document.getElementById('area_construccion').focus();
			return false;
	}else if(document.getElementById('area_terreno').value==''){

			fun_msj('Por favor ingrese el area de terreno');
			document.getElementById('area_terreno').focus();
			return false;
	}else if(document.getElementById('norte').value==''){

			fun_msj('Por favor ingrese el lindero norte de la vivienda');
			document.getElementById('norte').focus();
			return false;
	}else if(document.getElementById('sur').value==''){

			fun_msj('Por favor ingrese el lindero sur de la vivienda');
			document.getElementById('sur').focus();
			return false;
	}else if(document.getElementById('este').value==''){

			fun_msj('Por favor ingrese el lindero norte de la vivienda');
			document.getElementById('este').focus();
			return false;
	}else if(document.getElementById('oeste').value==''){

			fun_msj('Por favor ingrese el lindero oeste de la vivienda');
			document.getElementById('oeste').focus();
			return false;
	}else if(document.getElementById('direccion_vivienda_credito').value==''){

			fun_msj('Por favor ingrese la direcci&oacute;n de la vivienda sujeta al credito');
			document.getElementById('direccion_vivienda_credito').focus();
			return false;
	}else if(document.getElementById('costo_vivienda').value==''){

			fun_msj('Por favor ingrese el costo de la vivienda');
			document.getElementById('costo_vivienda').focus();
			return false;
	}else if(document.getElementById('monto_cuota_inicial').value==''){

			fun_msj('Por favor ingrese el monto de la cuota inicial de la vivienda');
			document.getElementById('monto_cuota_inicial').focus();
			return false;
	}else if(document.getElementById('monto_restante').value==''){

			fun_msj('Por favor ingrese el monto restante de la vivienda');
			document.getElementById('monto_restante').focus();
			return false;
	}else if(document.getElementById('tasa_interes').value==''){

			fun_msj('Por favor ingrese la tasa de interes de la vivienda');
			document.getElementById('tasa_interes').focus();
			return false;
	}else if(document.getElementById('factor').value==''){

			fun_msj('Por favor ingrese el factor de calculo');
			document.getElementById('factor').focus();
			return false;
	}else if(document.getElementById('plazo_anos').value==''){

			fun_msj('Por favor ingrese los a&ntilde;os de plazo de la vivienda');
			document.getElementById('plazo_anos').focus();
			return false;
	}else if(document.getElementById('numero_cuotas').value==''){

			fun_msj('Por favor ingrese el numero de cuotas de la vivienda');
			document.getElementById('numero_cuotas').focus();
			return false;
	}else if(document.getElementById('monto_mensual').value==''){

			fun_msj('Por favor ingrese el monto de la cuota mensual de la vivienda');
			document.getElementById('monto_mensual').focus();
			return false;
	}else if(document.getElementById('numero_contrato').value==''){

			fun_msj('Por favor ingrese el numero de contrato de la vivienda');
			document.getElementById('numero_contrato').focus();
			return false;
	}else if(document.getElementById('fecha_contrato').value==''){

			fun_msj('Por favor seleccione la fecha del contrato');
			document.getElementById('fecha_contrato').focus();
			return false;
	}else if(document.getElementById('fecha_entrega_contrato').value==''){

			fun_msj('Por favor seleccione la fecha de terminaci&oacute;n del contrato');
			document.getElementById('fecha_entrega_contrato').focus();
			return false;
	}else if(document.getElementById('select_rif').value==''){

			fun_msj('Por favor seleccione el cobrador');
			document.getElementById('select_rif').focus();
			return false;
	}

}//fin funtion

function grilla_9999(){
	if(document.getElementById('cod_parentesco').value==''){

			fun_msj('Por favor seleccione el parentesco');
			document.getElementById('cod_parentesco').focus();
			return false;

	}else if(document.getElementById('nombre_parentesco').value==''){

			fun_msj('Por favor ingrese el nombre y apellido del parentesco');
			document.getElementById('nombre_parentesco').focus();
			return false;


	}else if(document.getElementById('fecha_nacimiento_parentesco').value==''){

			fun_msj('Por favor seleccione la fecha de nacimiento del parentezco');
			document.getElementById('fecha_nacimiento_parentesco').focus();
			return false;
	}
}

function restante_credito(){
a = retornar_valor_calculo( $("costo_vivienda").value);
b = retornar_valor_calculo( $("monto_cuota_inicial").value);
d = retornar_valor_calculo( $("tasa_interes").value);
e = retornar_valor_calculo( $("plazo_anos").value);

if(a == null || a == 0 || a == ''){
	a = 0;
}
if(b == null || b == 0 || b == ''){
	b = 0;
}
if(d == null || d == 0 || d == ''){
	f = 0;
}else{
	f = eval(d) / 1200;
}
c = eval(a) - eval(b);
if(e == null || e == 0 || e == ''){
	g = 0;
}else{
	g = eval(e) * 12;
}
c = redondear(c,2);

ca = eval(c) * eval(f);
cb = 1 + eval(f);
cc = Math.pow(eval(cb),-(eval(g)));
cd = 1 - eval(cc);
ce = eval(ca) / eval(cd);
ce = redondear(ce,2);
$('monto_restante').value=c;
$('factor').value=f;
$('numero_cuotas').value=g;
$('monto_mensual').value=ce;

moneda("monto_restante");
moneda("costo_vivienda");
moneda("monto_cuota_inicial");
//moneda("factor");
moneda("monto_mensual");

}

function calcular_impuesto992(){

//alert(i);
//alert("ingresos_"+i);
ing = document.getElementById("ingresos").value;
ali = document.getElementById("alicuota").value;
/*
alert(ing);
alert('a');
alert(ali);
alert('b');
alert(mut);
alert('c');
alert(vut);
*/

imp = ((eval(ing) * eval(ali))/100);

//if( (eval(imp)) <  (eval(mut)) ){
//	imp = vut;
//}

document.getElementById("impuestos").value      = imp;



moneda("ingresos");
moneda("impuestos");


}//fin function

function calcular_impuesto99(i){

//alert(i);
//alert("ingresos_"+i);
ing = document.getElementById("ingresos_"+i).value;
ali = document.getElementById("alicuota_"+i).value;
/*
alert(ing);
alert('a');
alert(ali);
alert('b');
alert(mut);
alert('c');
alert(vut);
*/

imp = ((eval(ing) * eval(ali))/100);

//if( (eval(imp)) <  (eval(mut)) ){
//	imp = vut;
//}

document.getElementById("impuestos_"+i).value      = imp;



moneda("ingresos_"+i);
moneda("impuestos_"+i);


}//fin function

function grilla_declaracion_ingresos(){
   if(document.getElementById('cod_actividad').value==''){
			fun_msj('Por favor seleccione la actividad');
			document.getElementById('cod_actividad').focus();
			return false;
	}else if(document.getElementById('ingresosx').value==''){
			fun_msj('Por favor ingrese el monto de ingresos que obtuvo por la actividad correspondiente');
			document.getElementById('ingresosx').focus();
			return false;
	}

}//fin funtion


function guardar_declaracion_ingresos_xjgha(){


if($('monto_impuesto')){
monto_impuesto = eval($('monto_impuesto').value);
}else{
monto_impuesto=5;
}


   if(document.getElementById('rif_constribuyente').value==""){//arranca

			fun_msj('Inserte el Rif o Cedula de Identidad del contribuyente');
			document.getElementById('rif_constribuyente').focus();
			return false;

	}else if(eval(monto_impuesto)==eval(0)){
			fun_msj('El monto de impuesto no puede ser igual a cero');
			return false;

	}else if(document.getElementById('numero_declaracion').value==''){

			fun_msj('Por favor ingrese el n&uacute;mero de declaraci&oacute;n');
			document.getElementById('numero_declaracion').focus();
			return false;


	}else if(document.getElementById('fecha_declaracion').value==''){

			fun_msj('Por favor seleccione la fecha de declaraci&oacute;n');
			document.getElementById('fecha_declaracion').focus();
			return false;


	}else if(document.getElementById('periodo_desde').value==''){

			fun_msj('Por favor seleccione el peri&oacute;do desde');
			document.getElementById('periodo_desde').focus();
			return false;


	}else if(document.getElementById('periodo_hasta').value==''){

			fun_msj('Por favor seleccione el peri&oacute;do hasta');
			document.getElementById('periodo_hasta').focus();
			return false;


	}else if(document.getElementById('capital').value==''){

			fun_msj('Por favor ingrese el capital');
			document.getElementById('capital').focus();
			return false;


	}else if(document.getElementById('numero_empleados').value==''){

			fun_msj('Por favor ingrese el n&uacute;mero de empleados');
			document.getElementById('numero_empleados').focus();
			return false;


	}else if(document.getElementById('numero_obreros').value==''){

			fun_msj('Por favor ingrese el n&uacute;mero de obreros');
			document.getElementById('numero_obreros').focus();
			return false;


	}
}//fin funtion


function distribucion_deuda(){
        pag='../../include/cfpp05/moneda.php?monto=';
    var vigente   = retornar_valor_calculo($('vigente_bs').value);
    var deuda     = retornar_valor_calculo($('deuda_bs').value);
    var recargo   = retornar_valor_calculo($('recargo_bs').value);
    var multa     = retornar_valor_calculo($('multa_bs').value);
    var intereses = retornar_valor_calculo($('intereses_bs').value);
    var descuento = retornar_valor_calculo($('descuento_bs').value);
    var monto_deposito_t     = retornar_valor_calculo($('monto_deposito').value);
    var monto_nota_credito_t = retornar_valor_calculo($('monto_nota_credito').value);
    var monto_cheque_t       = retornar_valor_calculo($('monto_cheque').value);
    var total_dntch          = 0;
    var total     = 0;
    var efectivo  = 0;
    total_dntch = eval(monto_deposito_t)+eval(monto_nota_credito_t)+eval(monto_cheque_t);
    total = eval(vigente)+eval(deuda)+eval(recargo)+eval(multa)+eval(intereses);
    if(eval(descuento)>=eval(total)){
    	    fun_msj('El descuento no puede ser mayor o igual a la sumatoria de los montos');
			cargarMonto('descuento_bs',pag+0);
			descuento = 0;
			document.getElementById('descuento_bs').focus();
    }
    total    = eval(total) - eval(descuento);
    efectivo = eval(total) - eval(total_dntch);

    $('total').value=redondear(total,2);
    $('efectivo_t').value=redondear(efectivo,2);
    moneda("efectivo_t");
    moneda("total");
}

function distribuir_montos(){
        pag='../../include/cfpp05/moneda.php?monto=';
        //distribucion_deuda();
    var monto_deposito     = retornar_valor_calculo($('monto_deposito').value);
    var monto_nota_credito = retornar_valor_calculo($('monto_nota_credito').value);
    var monto_cheque       = retornar_valor_calculo($('monto_cheque').value);
    var efectivo_t         = retornar_valor_calculo($('total').value);
    var total     = 0;
    var efectivo  = 0;
    total = eval(monto_deposito)+eval(monto_nota_credito)+eval(monto_cheque);

    if(eval(monto_deposito)>eval(efectivo_t)){
		    fun_msj('El monto deposito no puede ser mayor al total');
			cargarMonto('monto_deposito',pag+0);
			monto_deposito = 0;
			document.getElementById('monto_deposito').focus();
    }else if(eval(monto_nota_credito)>eval(efectivo_t)){
		    fun_msj('El monto nota de cr&eacute;dito no puede ser mayor al total');
			cargarMonto('monto_nota_credito',pag+0);
			monto_nota_credito = 0;
			document.getElementById('monto_nota_credito').focus();
    }else if(eval(monto_cheque)>eval(efectivo_t)){
		    fun_msj('El monto del cheque no puede ser mayor al total');
			cargarMonto('monto_cheque',pag+0);
			monto_cheque = 0;
			document.getElementById('monto_cheque').focus();
    }else if(eval(total)>eval(efectivo_t)){
		    fun_msj('La sumatoria de la distribuci&oacute;n del pago no puede ser mayor al total');
    }
      total = eval(monto_deposito)+eval(monto_nota_credito)+eval(monto_cheque);
      efectivo = eval(efectivo_t)-eval(total);

      total    = redondear(total,2);
      efectivo = redondear(efectivo,2);

      monto_deposito     = redondear(monto_deposito,2);
      monto_nota_credito = redondear(monto_nota_credito,2);
      monto_cheque       = redondear(monto_cheque,2);

    cargarMonto('efectivo_t',pag+efectivo);
    cargarMonto('monto_deposito_t',pag+monto_deposito);
    cargarMonto('monto_nota_credito_t',pag+monto_nota_credito);
    cargarMonto('monto_cheque_t',pag+monto_cheque);

}


function distribuir_montos2(){
        pag='../../include/cfpp05/moneda.php?monto=';
    var monto_deposito     = reemplazarPC($('monto_deposito').value);
    var monto_nota_credito = reemplazarPC($('monto_nota_credito').value);
    var monto_cheque       = reemplazarPC($('monto_cheque').value);
    var efectivo_t         = reemplazarPC($('total').value);
    var total     = 0;
    var efectivo  = 0;
    total = eval(monto_deposito)+eval(monto_nota_credito)+eval(monto_cheque);

    if(eval(monto_deposito)>eval(efectivo_t)){
		    fun_msj('El monto deposito no puede ser mayor al total');
			cargarMonto('monto_deposito',pag+0);
			monto_deposito = 0;
			document.getElementById('monto_deposito').focus();
    }else if(eval(monto_nota_credito)>eval(efectivo_t)){
		    fun_msj('El monto nota de cr&eacute;dito no puede ser mayor al total');
			cargarMonto('monto_nota_credito',pag+0);
			monto_nota_credito = 0;
			document.getElementById('monto_nota_credito').focus();
    }else if(eval(monto_cheque)>eval(efectivo_t)){
		    fun_msj('El monto del cheque no puede ser mayor al total');
			cargarMonto('monto_cheque',pag+0);
			monto_cheque = 0;
			document.getElementById('monto_cheque').focus();
    }else if(eval(total)>eval(efectivo_t)){
		    fun_msj('La sumatoria de la distribuci&oacute;n del pago no puede ser mayor al total');
    }
      total = eval(monto_deposito)+eval(monto_nota_credito)+eval(monto_cheque);
      efectivo = eval(efectivo_t)-eval(total);


      total    = redondear(total,2);
      efectivo = redondear(efectivo,2);

      monto_deposito     = redondear(monto_deposito,2);
      monto_nota_credito = redondear(monto_nota_credito,2);
      monto_cheque       = redondear(monto_cheque,2);



    cargarMonto('efectivo_t',pag+efectivo);
    cargarMonto('monto_deposito_t',pag+monto_deposito);
    cargarMonto('monto_nota_credito_t',pag+monto_nota_credito);
    cargarMonto('monto_cheque_t',pag+monto_cheque);
}

function guardar_shp901_otros_ingresos_cobro(){
    pag='../../include/cfpp05/moneda.php?monto=';
    var monto_deposito     = reemplazarPC($('monto_deposito').value);
    var monto_nota_credito = reemplazarPC($('monto_nota_credito').value);
    var monto_cheque       = reemplazarPC($('monto_cheque').value);
    var efectivo_t         = reemplazarPC($('total').value);
    var total     = 0;
    var efectivo  = 0;
    total = eval(monto_deposito)+eval(monto_nota_credito)+eval(monto_cheque);

	if($('rif_constribuyente').value==""){
		fun_msj('Por favor ingrese el rif o c&eacute;dula de identidad');
		$('rif_constribuyente').focus();
		return false;
	}else if($('cod_ingreso').value==""){
		fun_msj('Por favor seleccione el c&oacute;digo del ingreso');
		$('cod_ingreso').focus();
		return false;
	}else if($('concepto_cobro').value==""){
		fun_msj('Por favor ingrese el concepto de cobro');
		$('concepto_cobro').focus();
		return false;
	}else if($('vigente_bs').value=="0,00" && $('deuda_bs').value=="0,00" && $('recargo_bs').value=="0,00" && $('multa_bs').value=="0,00" && $('intereses_bs').value=="0,00" && $('descuento_bs').value=="0,00"){
		fun_msj('Por favor ingrese los montos de la distribuci&oacute;n de la deuda');
		$('cod_ingreso').focus();
		return false;
	}else if($('numero_cuenta1').value!=""){
		if($('numero_deposito').value==""){
			fun_msj('Por favor ingrese el n&uacute;mero del deposito');
			$('numero_deposito').focus();
			return false;
		}else if($('monto_deposito').value=="0,00"){
			fun_msj('Por favor ingrese el monto del deposito');
			$('monto_deposito').focus();
			return false;
		}else if($('fecha_deposito').value==""){
			fun_msj('Por favor seleccione la fecha del deposito');
			$('fecha_deposito').focus();
			return false;
		}else if($('numero_cuenta2').value!=""){
			if($('numero_nota_credito').value==""){
				fun_msj('Por favor ingrese el n&uacute;mero de la nota de cr&eacute;dito');
				$('numero_nota_credito').focus();
				return false;
			}else if($('monto_nota_credito').value=="0,00"){
				fun_msj('Por favor ingrese el monto de la nota de cr&eacute;dito');
				$('monto_nota_credito').focus();
				return false;
			}else if($('fecha_nota_credito').value==""){
				fun_msj('Por favor seleccione la fecha de la nota de cr&eacute;dito');
				$('fecha_nota_credito').focus();
				return false;
			}
		}else if($('numero_cuenta3').value!=""){
			if($('numero_cheque').value==""){
				fun_msj('Por favor ingrese el n&uacute;mero del cheque');
				$('numero_cheque').focus();
				return false;
			}else if($('monto_cheque').value=="0,00"){
				fun_msj('Por favor ingrese el monto del cheque');
				$('monto_cheque').focus();
				return false;
			}else if($('fecha_cheque').value==""){
				fun_msj('Por favor seleccione la fecha del cheque');
				$('fecha_cheque').focus();
				return false;
			}
		}
	}else if($('numero_cuenta2').value!=""){
		if($('numero_nota_credito').value==""){
			fun_msj('Por favor ingrese el n&uacute;mero de la nota de cr&eacute;dito');
			$('numero_nota_credito').focus();
			return false;
		}else if($('monto_nota_credito').value=="0,00"){
			fun_msj('Por favor ingrese el monto de la nota de cr&eacute;dito');
			$('monto_nota_credito').focus();
			return false;
		}else if($('fecha_nota_credito').value==""){
			fun_msj('Por favor seleccione la fecha de la nota de cr&eacute;dito');
			$('fecha_nota_credito').focus();
			return false;
		}else if($('numero_cuenta3').value!=""){
			if($('numero_cheque').value==""){
				fun_msj('Por favor ingrese el n&uacute;mero del cheque');
				$('numero_cheque').focus();
				return false;
			}else if($('monto_cheque').value=="0,00"){
				fun_msj('Por favor ingrese el monto del cheque');
				$('monto_cheque').focus();
				return false;
			}else if($('fecha_cheque').value==""){
				fun_msj('Por favor seleccione la fecha del cheque');
				$('fecha_cheque').focus();
				return false;
			}
		}
	}else if($('numero_cuenta3').value!=""){
		if($('numero_cheque').value==""){
			fun_msj('Por favor ingrese el n&uacute;mero del cheque');
			$('numero_cheque').focus();
			return false;
		}else if($('monto_cheque').value=="0,00"){
			fun_msj('Por favor ingrese el monto del cheque');
			$('monto_cheque').focus();
			return false;
		}else if($('fecha_cheque').value==""){
			fun_msj('Por favor seleccione la fecha del cheque');
			$('fecha_cheque').focus();
			return false;
		}
	}else if($('lista_cobradores').value==""){
			fun_msj('Por favor seleccione rif o c&eacute;dula del Recaudador');
			$('lista_cobradores').focus();
			return false;
	}else if(eval(total)>eval(efectivo_t)){
		    fun_msj('La sumatoria de la distribuci&oacute;n del pago no puede ser mayor al total');
            return false;
	}

}

function guardar_shp900_cobranza(){
    pag='../../include/cfpp05/moneda.php?monto=';
    var monto_deposito     = reemplazarPC($('monto_deposito').value);
    var monto_nota_credito = reemplazarPC($('monto_nota_credito').value);
    var monto_cheque       = reemplazarPC($('monto_cheque').value);
    var efectivo_t         = reemplazarPC($('total').value);
    var total     = 0;
    var efectivo  = 0;
    total = eval(monto_deposito)+eval(monto_nota_credito)+eval(monto_cheque);

	if($('rif_constribuyente').value==""){
		fun_msj('Por favor ingrese el rif o c&eacute;dula de identidad');
		$('rif_constribuyente').focus();
		return false;
	}else if($('cod_ingreso').value==""){
		fun_msj('Por favor seleccione el c&oacute;digo del ingreso');
		$('cod_ingreso').focus();
		return false;
	}else if($('concepto_cobro').value==""){
		fun_msj('Por favor ingrese el concepto de cobro');
		$('concepto_cobro').focus();
		return false;
	}else if($('vigente_bs').value=="0,00" && $('deuda_bs').value=="0,00" && $('recargo_bs').value=="0,00" && $('multa_bs').value=="0,00" && $('intereses_bs').value=="0,00" && $('descuento_bs').value=="0,00"){
		fun_msj('Por favor selecione la deuda pendiente');
		$('cod_ingreso').focus();
		return false;
	}else if($('numero_cuenta1').value!=""){
		if($('numero_deposito').value==""){
			fun_msj('Por favor ingrese el n&uacute;mero del deposito');
			$('numero_deposito').focus();
			return false;
		}else if($('monto_deposito').value=="0,00"){
			fun_msj('Por favor ingrese el monto del deposito');
			$('monto_deposito').focus();
			return false;
		}else if($('fecha_deposito').value==""){
			fun_msj('Por favor seleccione la fecha del deposito');
			$('fecha_deposito').focus();
			return false;
		}else if($('numero_cuenta2').value!=""){
			if($('numero_nota_credito').value==""){
				fun_msj('Por favor ingrese el n&uacute;mero de la nota de cr&eacute;dito');
				$('numero_nota_credito').focus();
				return false;
			}else if($('monto_nota_credito').value=="0,00"){
				fun_msj('Por favor ingrese el monto de la nota de cr&eacute;dito');
				$('monto_nota_credito').focus();
				return false;
			}else if($('fecha_nota_credito').value==""){
				fun_msj('Por favor seleccione la fecha de la nota de cr&eacute;dito');
				$('fecha_nota_credito').focus();
				return false;
			}
		}else if($('numero_cuenta3').value!=""){
			if($('numero_cheque').value==""){
				fun_msj('Por favor ingrese el n&uacute;mero del cheque');
				$('numero_cheque').focus();
				return false;
			}else if($('monto_cheque').value=="0,00"){
				fun_msj('Por favor ingrese el monto del cheque');
				$('monto_cheque').focus();
				return false;
			}else if($('fecha_cheque').value==""){
				fun_msj('Por favor seleccione la fecha del cheque');
				$('fecha_cheque').focus();
				return false;
			}
		}
	}else if($('numero_cuenta2').value!=""){
		if($('numero_nota_credito').value==""){
			fun_msj('Por favor ingrese el n&uacute;mero de la nota de cr&eacute;dito');
			$('numero_nota_credito').focus();
			return false;
		}else if($('monto_nota_credito').value=="0,00"){
			fun_msj('Por favor ingrese el monto de la nota de cr&eacute;dito');
			$('monto_nota_credito').focus();
			return false;
		}else if($('fecha_nota_credito').value==""){
			fun_msj('Por favor seleccione la fecha de la nota de cr&eacute;dito');
			$('fecha_nota_credito').focus();
			return false;
		}else if($('numero_cuenta3').value!=""){
			if($('numero_cheque').value==""){
				fun_msj('Por favor ingrese el n&uacute;mero del cheque');
				$('numero_cheque').focus();
				return false;
			}else if($('monto_cheque').value=="0,00"){
				fun_msj('Por favor ingrese el monto del cheque');
				$('monto_cheque').focus();
				return false;
			}else if($('fecha_cheque').value==""){
				fun_msj('Por favor seleccione la fecha del cheque');
				$('fecha_cheque').focus();
				return false;
			}
		}
	}else if($('numero_cuenta3').value!=""){
		if($('numero_cheque').value==""){
			fun_msj('Por favor ingrese el n&uacute;mero del cheque');
			$('numero_cheque').focus();
			return false;
		}else if($('monto_cheque').value=="0,00"){
			fun_msj('Por favor ingrese el monto del cheque');
			$('monto_cheque').focus();
			return false;
		}else if($('fecha_cheque').value==""){
			fun_msj('Por favor seleccione la fecha del cheque');
			$('fecha_cheque').focus();
			return false;
		}
	}else if(eval(total)>eval(efectivo_t)){
		    fun_msj('La sumatoria de la distribuci&oacute;n del pago no puede ser mayor al total');
            return false;
	}

}



function validar_cambio_cobrador(){
   if(document.getElementById('rif_constribuyente').value==""){//arranca

			fun_msj('Inserte el Rif o Cedula de Identidad del contribuyente');
			document.getElementById('rif_constribuyente').focus();
			return false;

	}else if(document.getElementById('sel_cobrador_nuevo').value==''){

			fun_msj('Por favor seleccione para cual cobrador se desea cambiar');
			document.getElementById('sel_cobrador_nuevo').focus();
			return false;


	}
}//fin funtion

function guardar_aseo_domiciliario(){
   if(document.getElementById('rif_constribuyente').value==""){//arranca

			fun_msj('Inserte el Rif o C&eacute;dula de Identidad del contribuyente');
			document.getElementById('rif_constribuyente').focus();
			return false;

	}else if(document.getElementById('select_cla').value==''){

			fun_msj('Por favor seleccione la clasificaci&oacute;n del servicio');
			document.getElementById('select_cla').focus();
			return false;


	}else if(document.getElementById('select_rif').value==''){

			fun_msj('Por favor seleccione el cobrador');
			document.getElementById('select_rif').focus();
			return false;

	}else if(document.getElementById('fecha_registro').value==''){

			fun_msj('Por favor seleccione la fecha de registro');
			document.getElementById('fecha_registro').focus();
			return false;


	}

}//fin funtion


function calcular_total_candelaciones_deudas(op){


a =reemplazarPC( $("deuda_vigente").value);
if($("recargo").value==''){
	b=0;
}else{
	b =reemplazarPC( $("recargo").value);
}


if($("multa").value==''){
	c=0;
}else{
	c =reemplazarPC( $("multa").value);
}


if($("intereses").value==''){
	d=0;
}else{
	d =reemplazarPC( $("intereses").value);
}


if($("descuentos").value==''){
	e=0;
}else{
	e =reemplazarPC( $("descuentos").value);
}
//var x = e;
pag='../../include/cfpp05/moneda.php?monto=';
var a = redondear(a,2);
var b = redondear(b,2);
var c = redondear(c,2);
var d = redondear(d,2);
var e = redondear(e,2);

f = (eval(a) + eval(b) + eval(c) + eval(d));
if(eval(f) <= eval(e)){
	fun_msj('El descuento no puede ser mayor o igual al total');
	cargarMonto('descuentos',pag+0);
	g = eval(f)- 0;
}else{
	g = eval(f)- eval(e);
}
	document.getElementById("total").value      = g;
moneda("total");


}//fin function

function valida_cancelaciones_deudas(){
      if(document.getElementById('rif_constribuyente').value==""){//arranca

			fun_msj('Inserte el Rif o C&eacute;dula de Identidad del contribuyente');
			document.getElementById('rif_constribuyente').focus();
			return false;

	}else if(document.getElementById('ano_deuda').value==""){//arranca

			fun_msj('Por favor ingrese el a&ntilde;o de la deuda');
			document.getElementById('ano_deuda').focus();
			return false;

	}else if(document.getElementById('mes').value==""){//arranca

			fun_msj('Por favor seleccione el mes correspondiente');
			document.getElementById('mes').focus();
			return false;

	}else if(document.getElementById('numero_recibo').value==''){

			fun_msj('Por favor ingrese el n&uacute;mero de planilla');
			document.getElementById('numero_recibo').focus();
			return false;


	}else if(document.getElementById('deuda_vigente').value==''){

			fun_msj('Por favor ingrese en monto de la deuda vigente');
			document.getElementById('deuda_vigente').focus();
			return false;

	}

}//fin funtion


function valida_cancelaciones_deudas2(){
      if(document.getElementById('rif_constribuyente').value==""){//arranca

			fun_msj('Inserte el Rif o C&eacute;dula de Identidad del contribuyente');
			document.getElementById('rif_constribuyente').focus();
			return false;

	}else if(document.getElementById('ano_deuda').value==""){//arranca

			fun_msj('Por favor ingrese el a&ntilde;o de la deuda');
			document.getElementById('ano_deuda').focus();
			return false;

	}else if(document.getElementById('placa').value==""){//arranca

			fun_msj('Por favor ingrese la placa del veh&iacute;culo');
			document.getElementById('placa').focus();
			return false;

	}else if(document.getElementById('mes').value==""){//arranca

			fun_msj('Por favor seleccione el mes correspondiente');
			document.getElementById('mes').focus();
			return false;

	}else if(document.getElementById('numero_recibo').value==''){

			fun_msj('Por favor ingrese el n&uacute;mero de planilla');
			document.getElementById('numero_recibo').focus();
			return false;


	}else if(document.getElementById('deuda_vigente').value==''){

			fun_msj('Por favor ingrese en monto de la deuda vigente');
			document.getElementById('deuda_vigente').focus();
			return false;

	}

}//fin funtion

function valida_cancelaciones_deudas3(){
      if(document.getElementById('rif_constribuyente').value==""){//arranca

			fun_msj('Inserte el Rif o C&eacute;dula de Identidad del contribuyente');
			document.getElementById('rif_constribuyente').focus();
			return false;

	}else if(document.getElementById('ano_deuda').value==""){//arranca

			fun_msj('Por favor ingrese el a&ntilde;o de la deuda');
			document.getElementById('ano_deuda').focus();
			return false;

	}else if(document.getElementById('placa').value==""){//arranca

			fun_msj('Por favor ingrese el n&uacute;mero de ficha catastral');
			document.getElementById('placa').focus();
			return false;

	}else if(document.getElementById('mes').value==""){//arranca

			fun_msj('Por favor seleccione el mes correspondiente');
			document.getElementById('mes').focus();
			return false;

	}else if(document.getElementById('numero_recibo').value==''){

			fun_msj('Por favor ingrese el n&uacute;mero de planilla');
			document.getElementById('numero_recibo').focus();
			return false;


	}else if(document.getElementById('deuda_vigente').value==''){

			fun_msj('Por favor ingrese en monto de la deuda vigente');
			document.getElementById('deuda_vigente').focus();
			return false;

	}

}//fin funtion


function guardar_cancelaciones_deudas(){
   if(document.getElementById('rif_constribuyente').value==""){//arranca

			fun_msj('Inserte el Rif o C&eacute;dula de Identidad del contribuyente');
			document.getElementById('rif_constribuyente').focus();
			return false;

	}else if(document.getElementById('ano_deuda').value==""){//arranca

			fun_msj('Por favor ingrese el a&ntilde;o de la deuda');
			document.getElementById('ano_deuda').focus();
			return false;

	}

}//fin funtion

function guardar_cancelaciones_deudas2(){
   if(document.getElementById('rif_constribuyente').value==""){//arranca

			fun_msj('Inserte el Rif o C&eacute;dula de Identidad del contribuyente');
			document.getElementById('rif_constribuyente').focus();
			return false;

	}else if(document.getElementById('placa').value==""){//arranca

			fun_msj('Por favor ingrese la placa del veh&iacute;culo');
			document.getElementById('placa').focus();
			return false;

	}else if(document.getElementById('ano_deuda').value==""){//arranca

			fun_msj('Por favor ingrese el a&ntilde;o de la deuda');
			document.getElementById('ano_deuda').focus();
			return false;

	}

}//fin funtion

function guardar_cancelaciones_deudas3(){
   if(document.getElementById('rif_constribuyente').value==""){//arranca

			fun_msj('Inserte el Rif o C&eacute;dula de Identidad del contribuyente');
			document.getElementById('rif_constribuyente').focus();
			return false;

	}else if(document.getElementById('placa').value==""){//arranca

			fun_msj('Por favor ingrese el n&uacute;mero de ficha catastral');
			document.getElementById('placa').focus();
			return false;

	}else if(document.getElementById('ano_deuda').value==""){//arranca

			fun_msj('Por favor ingrese el a&ntilde;o de la deuda');
			document.getElementById('ano_deuda').focus();
			return false;

	}

}//fin funtion




function shd999_relacion_ingreso_detallado(){

	if(document.getElementById('ano').value==''){

			fun_msj('Debe seleccionar el a&ntilde;o');
			document.getElementById('ano').focus();
			return false;


	}else if(document.getElementById('dia').value!='' && (document.getElementById('ano').value=='' || document.getElementById('mes').value=='')){

			fun_msj('Debe seleccionar el a&ntilde;o y el mes');
			document.getElementById('ano').focus();
			return false;


	}


}


function shd950_valida_solvencia_fecha(){

		if(document.getElementById('fecha_expedicion').value=='' || document.getElementById('valida_hasta').value==''){
				fun_msj('debe ingresar la fecha de expedicion y la fecha valida hasta');
	             return false;
		}else if(diferenciaFecha(document.getElementById('valida_hasta').value, document.getElementById('fecha_expedicion').value)){
	            fun_msj('la Fecha valida hasta debe ser mayor a la fecha de expedici&oacute;n');
	             return false;
		}

}// fin shd950_valida_solvencia_fecha



function rif_hacienda(){
	if(document.getElementById('personalidad_2').checked==true){
	var elRIF = document.getElementById('rif_cedula').value;
                  var temp = elRIF.toUpperCase();
				  if (!/^[JVEGPIRC]/.test(temp)){
				      alert("El primer caracter es incorrecto, debe ser una letra de las siguientes: J,V,E,G,P,I,R,C");
				      return false;
				  }else if (!/^[JVEGPIRC][-][0-9]{8}[-][0-9]$/.test(temp)){ // Son 9 dígitos?
				     alert ("Por Favor Verifique que el RIF tenga el siguiente formato: J-12345678-9 \nSi su Rif es menor a 9 digitos rellene con 0 a la izquierda Ej: J-00123456-7\n Su rif actual es: "+temp);
				     return false;
	}
}
}





function rif_hacienda_reemplazo(id){
	if(document.getElementById('personalidad_2').checked==true){
				var elRIF = document.getElementById("rif_cedula").value;
			                  var temp = elRIF.toUpperCase();
			    var elRIF2 = document.getElementById("rif_nuevo").value;
			                  var temp2 = elRIF2.toUpperCase();

							       if (!/^[JVEGPIRC]/.test(temp)){
							      alert("El primer caracter es incorrecto, debe ser una letra de las siguientes: J,V,E,G,P,I,R,C");
							      return false;
							  }else if (!/^[JVEGPIRC][-][0-9]{8}[-][0-9]$/.test(temp)){ // Son 9 dígitos?
							     alert ("Por Favor Verifique que tenga el siguiente formato: J-12345678-9 \nSi su Rif es menor a 9 digitos rellene con 0 a la izquierda Ej: J-00123456-7\n Su rif actual es: "+temp);
							     return false;

							  }else if (!/^[JVEGPIRC]/.test(temp2)){
							      alert("El primer caracter es incorrecto, debe ser una letra de las siguientes: J,V,E,G,P,I,R,C");
							      return false;
							  }else if (!/^[JVEGPIRC][-][0-9]{8}[-][0-9]$/.test(temp2)){ // Son 9 dígitos?
							     alert ("Por Favor Verifique que tenga el siguiente formato: J-12345678-9 \nSi su Rif es menor a 9 digitos rellene con 0 a la izquierda Ej: J-00123456-7\n Su rif actual es: "+temp);
							     return false;

				              }
}
}




function valida_shd900_estado_cuentas(){

	if(document.getElementById('ano').value==''){

			fun_msj('Debe seleccionar el a&ntilde;o');
			document.getElementById('ano').focus();
			return false;


	}else if(document.getElementById('rif_ci').value==''){

			fun_msj('Debe ingresar el rif');
			document.getElementById('rif_ci').focus();
			return false;


	}else if(document.getElementById('cod_ingreso').value==''){

			fun_msj('Debe seleccionar el impuesto');
			document.getElementById('cod_ingreso').focus();
			return false;


	}


}



function valida_shd900_planillas_liquidacion(){

	if(document.getElementById('ano').value==''){

			fun_msj('Debe seleccionar el a&ntilde;o');
			document.getElementById('ano').focus();
			return false;


	}

	/*else if(document.getElementById('mes').value==''){

			fun_msj('Debe seleccionar el mes');
			document.getElementById('mes').focus();
			return false;


	}*/


}


function valida_shd950_solvencia(){
	if(document.getElementById('ano').value==''){

			fun_msj('Debe seleccionar el a&ntilde;o');
			document.getElementById('ano').focus();
			return false;
	}
}





function valida_shd950_contribuyentes_morosos(){

	if(document.getElementById('ano').value==''){

			fun_msj('Debe seleccionar el a&ntilde;o');
			document.getElementById('ano').focus();
			return false;


	} if(document.getElementById('cod_ingreso').value==''){

			fun_msj('Debe seleccionar el impuesto');
			document.getElementById('cod_ingreso').focus();
			return false;


	}

}


function calcular_aforos99(i){
a = retornar_valor_calculo( $("numero_aforos_"+i).value);
b = retornar_valor_calculo( $("monto_aforos_"+i).value);

c = ((eval(a) * eval(b)));


document.getElementById("total_aforos_"+i).value      = c;

moneda("numero_aforos_"+i);
moneda("total_aforos_"+i);


}//fin function

function valida_grilla_solicitud_a(){

	if(document.getElementById('activ_cod').value==''){

			fun_msj('Por favor ingrese la actividad');
			document.getElementById('activ_cod').focus();
			return false;


	}

}

function calcular_declaracion_ingreso_v2_impuesto(i){
     var desde=$('periodo_desde').value;
     var hasta=$('periodo_hasta').value;
     var monto_ingresos = reemplazarPC($('ingresos'+i).value);
     if(desde!='' && hasta!=''){
         if(monto_ingresos!=''){
             desde = desde.replace('/','-');
     		desde = desde.replace('/','-');
     		hasta = hasta.replace('/','-');
     		hasta = hasta.replace('/','-');
             ver_documento('/shp100_declaracion_ingresos_v2/antiguedad/'+desde+'/'+hasta+'/'+i,'antiguedad');

         }
     }else{
        if(desde==''){
            fun_msj('Debe seleccionar el periodo desde');
			document.getElementById('periodo_desde').focus();
        }else{
            fun_msj('Debe seleccionar el periodo hasta');
			document.getElementById('periodo_hasta').focus();
        }
        $('ingresos'+i).value='';
     }
}


function personal_directivo_a(){
	if(document.getElementById('codigo_directivos').value==''){

			fun_msj('Por favor ingrese el c&oacute;digo del directivo');
			document.getElementById('codigo_directivos').focus();
			return false;


	}else if(document.getElementById('nombres_directivo').value==''){

			fun_msj('Por favor ingrese nombres y apellidos del directivo');
			document.getElementById('nombres_directivo').focus();
			return false;


	}else if(document.getElementById('telefonos_directivos').value==''){

			fun_msj('Por favor ingrese tel&eacute;fonos del directivo');
			document.getElementById('telefonos_directivos').focus();
			return false;


	}else if(document.getElementById('direccion_directivos').value==''){

			fun_msj('Por favor ingrese direcci&oacute;n  electr&oacute;nica del directivo');
			document.getElementById('direccion_directivos').focus();
			return false;


	}
}

function concejales_a(){
	if(document.getElementById('codigo_concejales').value==''){

			fun_msj('Por favor ingrese el c&oacute;digo del concejal');
			document.getElementById('codigo_concejales').focus();
			return false;


	}else if(document.getElementById('nombres_concejales').value==''){

			fun_msj('Por favor ingrese nombres y apellidos del concejal');
			document.getElementById('nombres_concejales').focus();
			return false;


	}
}

function nueva_alcaldia(){
	if(document.getElementById('ejercicio').value==''){

			fun_msj('Por favor seleccione el a&ntilde;o del presupuesto');
			document.getElementById('ejercicio').focus();
			return false;


	}else if(document.getElementById('domicilio_legal').value==''){

			fun_msj('Por favor ingrese domicilio legal');
			document.getElementById('domicilio_legal').focus();
			return false;


	}else if(document.getElementById('base_legal').value==''){

			fun_msj('Por favor ingrese base legal');
			document.getElementById('base_legal').focus();
			return false;


	}else if(document.getElementById('fecha_creacion').value==''){

			fun_msj('Por favor seleccione la fecha de creaci&oacute;n');
			document.getElementById('fecha_creacion').focus();
			return false;


	}else if(document.getElementById('ciudad').value==''){

			fun_msj('Por favor ingrese ciudad');
			document.getElementById('ciudad').focus();
			return false;


	}else if(document.getElementById('estado').value==''){

			fun_msj('Por favor ingrese estado');
			document.getElementById('estado').focus();
			return false;


	}else if(document.getElementById('telefonos').value==''){

			fun_msj('Por favor ingrese tel&eacute;fonos de la alcald&iacute;a');
			document.getElementById('telefonos').focus();
			return false;


	}else if(document.getElementById('fax').value==''){

			fun_msj('Por favor ingrese fax');
			document.getElementById('fax').focus();
			return false;


	}else if(document.getElementById('rif').value==''){

			fun_msj('Por favor ingrese r.i.f.');
			document.getElementById('rif').focus();
			return false;


	}else if(document.getElementById('codigo_postal').value==''){

			fun_msj('Por favor ingrese c&oacute;digo postal');
			document.getElementById('codigo_postal').focus();
			return false;


	}else if(document.getElementById('alcalde').value==''){

			fun_msj('Por favor ingrese nombre del alcalde o alcaldesa');
			document.getElementById('alcalde').focus();
			return false;


	}else if(document.getElementById('cuenta1').value=='0'){

			fun_msj('Por favor ingrese PERSONAL DIRECTIVO');
			document.getElementById('cuenta1').focus();
			return false;


	}else if(document.getElementById('cuenta2').value=='0'){

			fun_msj('Por favor ingrese CONCEJALES');
			document.getElementById('cuenta2').focus();
			return false;


	}
}




function valida_shp950_formato_solvencia(){
	if(document.getElementById('radio_formato_1').checked==false && document.getElementById('radio_formato_2').checked==false){
		fun_msj('seleccione en que formato desea emitir la solvencia');
		return false;
	}

}
