function valida_guardar_rac(){

	if(document.getElementById('tipo') == 0){
		fun_msj('Se de be de seleccionar un tipo de cargo.');
		return false;
	}

	if(document.getElementById('descripcion').value == ''){
		fun_msj('Debe de ingresar una descripción de cargo.');
		return false;
	}

	if(document.getElementById('cedula').value == ''){
		fun_msj('Debe de ingresar una cédula.');
		return false;
	}

	if(document.getElementById('nombre1').value == ''){
		fun_msj('Debe de ingresar el primer nombre.');
		return false;
	}

	if(document.getElementById('nombre2').value == ''){
		fun_msj('Debe de ingresar el segundo nombre.');
		return false;
	}

	if(document.getElementById('apellido1').value == ''){
		fun_msj('Debe de ingresar el primer apellido.');
		return false;
	}

	if(document.getElementById('apellido2').value == ''){
		fun_msj('Debe de ingresar el segundo apellido.');
		return false;
	}

	if(document.getElementById('grado').value == ''){
		fun_msj('Debe de ingresar el grado.');
		return false;
	}

	if(document.getElementById('clase').value == ''){
		fun_msj('Debe de ingresar la clase.');
		return false;
	}

	if(document.getElementById('ano_grado').value == ''){
		fun_msj('Debe de ingresar los años en el grado.');
		return false;
	}

	if(document.getElementById('paso').value == ''){
		fun_msj('Debe de ingresar el número de paso.');
		return false;
	}

	if(document.getElementById('numero_cargo').value == ''){
		fun_msj('Debe de ingresar el número de cargo.');
		return false;
	}

	if(document.getElementById('su_ba_men').value == ''){
		fun_msj('Debe de ingresar el sueldo básico mensual.');
		return false;
	}

	if(document.getElementById('mon_anu_su_ba').value == ''){
		fun_msj('Debe de ingresar el monto anual del sueldo básico.');
		return false;
	}

	if(document.getElementById('com_men').value == ''){
		fun_msj('Debe de ingresar la compensación mensual.');
		return false;
	}

	if(document.getElementById('com_anu').value == ''){
		fun_msj('Debe de ingresar la compensación anual.');
		return false;
	}
	
}//fin funcion

/*
aqui esta una funcion guia que valida un formulario

function valida_guardar_orden_pago(){

        var mydate=new Date();
        var year1=mydate.getYear();
        var year=mydate.getYear();

        var fecha_actual = document.getElementById('fecha_documento_orden').value;
		var datearray = fecha_actual.split("/");
		var fecha1 = datearray[2] + '/' + datearray[1] + '/' + datearray[0]; // INVERTIR FECHA YYYY/MM/DD

		var fecha_comparar = document.getElementById('fecha_documento_anterior').value;
		var datearray = fecha_comparar.split("/");
		var fecha2 = datearray[2] + '/' + datearray[1] + '/' + datearray[0]; // INVERTIR FECHA YYYY/MM/DD

		if (fecha1<fecha2){
            documento_anterior  = document.getElementById('numero_documento_anterior').value;
            fecha_anterior      = document.getElementById('fecha_documento_anterior').value;
            numero_documento    = document.getElementById('numero_orden_pago').value;
				if (documento_anterior!=0){
            fun_msj('Fecha orden '+numero_documento+' menor a fecha '+fecha_anterior+' de orden '+documento_anterior);
            return false;
            	}

   }else

  if(document.getElementById('401_existe')){
   var existe = "1";
  }else{
   var existe = "0";
  }


  if(document.getElementById('403_existe')){
   var existe2 = "1";
  }else{
   var existe2 = "0";
  }


  if(document.getElementById('403_iva_existe')){
   var existe3 = "1";
  }else{
   var existe3 = "0";
  }



	if(document.getElementById('fecha_documento_orden').value==''){
			fun_msj('Por favor seleccione la fecha de la orden de pago');
			document.getElementById('fecha_documento_orden').focus();
			return false;

	}else if(verifica_cierre_ano_ejecucion('fecha_documento_orden')==false){
				fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA DE LA ORDEN DE PAGO NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
				return false;

	}else if(document.getElementById('cta_banc_beneficiario').value!='' && document.getElementById('cta_banc_beneficiario').value.length < 20){
			fun_msj('Por favor la cuenta bancaria debe ser de 20 digitos');
			document.getElementById('cta_banc_beneficiario').focus();
			return false;

	}else if(valida_fechas_menores_documentos(3)==2){
      			return false;

   }else
 		var fecha_actual = document.getElementById('fecha_documento_orden').value;
		var datearray = fecha_actual.split("/");
		var fecha1 = datearray[2] + '/' + datearray[1] + '/' + datearray[0]; // INVERTIR FECHA YYYY/MM/DD

		var fecha_comparar = document.getElementById('fecha_documento').value;
		var datearray = fecha_comparar.split("/");
		var fecha2 = datearray[2] + '/' + datearray[1] + '/' + datearray[0]; // INVERTIR FECHA YYYY/MM/DD

		if (fecha2>fecha1){
	        fun_msj('la Fecha de la orden de pago debe ser mayor a la fecha del documento adjunto');
            return false;

	}else if(document.getElementById('tipo_documento_st').value==''){
			fun_msj('Por favor seleccione el tipo de documento');
			document.getElementById('tipo_documento_st').focus();
			return false;
	}else if(document.getElementById('tipo_documento_st').value==''){
	    //     if(document.getElementById('tipo_documento_st3').value==''){
			     fun_msj('Por favor seleccione el tipo de documento adjunto');
			     document.getElementById('tipo_documento_st3').focus();
			     return false;
			//     }
	}else if(document.getElementById('cod_tipo_pago_select').value==''){
			fun_msj('Por favor seleccione el tipo de pago');
			document.getElementById('cod_tipo_pago_select').focus();
			return false;

	}else if(document.getElementById('concepto').value==''){
			fun_msj('Por favor ingrese el concepto de la orden de pago');
			document.getElementById('concepto').focus();
			return false;


/*
    }else if((document.getElementById('cod_tipo_pago_select').value!='3' && document.getElementById('cod_tipo_pago_select').value!='6') && existe=="1" && existe2=="0" && existe3=="1"){
			fun_msj('Esta orden corresponde a servicio &oacute; aportes, favor revisar Tipo de Pago');
			return false;
* /



    }else if(valida_fechas_documentos_mayores(5)==2){

			return false;

	}else if(eval(reemplazarPC(document.getElementById('t_monto_iva').value))>0){

         if(eval(reemplazarPC(document.getElementById('tmontoivahh').value))!=eval(reemplazarPC(document.getElementById('t_monto_iva').value))){
            fun_msj('Monto IVA no cuadra con monto total iva de las facturas, Por favor revise');
            //alert(document.getElementById('tmontoivahh').value+" "+document.getElementById('t_monto_iva').value);
			//document.getElementById('concepto').focus();
			return false;
         }else{


			        tota_grilla_partidas = document.getElementById('tota_grilla_partidas').value;
			        monto_orden_pago     = document.getElementById('monto_orden_pago').value;

			        var str = monto_orden_pago;for(i=0; i<monto_orden_pago.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var monto_orden_pago = str;


			             if(monto_orden_pago!=tota_grilla_partidas){
			                   fun_msj('El total de las partidas es diferente al monto de la orden de pago');
						       return false;
			             }

          }//fin else


	}else{


			        tota_grilla_partidas = document.getElementById('tota_grilla_partidas').value;
			        monto_orden_pago     = document.getElementById('monto_orden_pago').value;

			        var str = monto_orden_pago;for(i=0; i<monto_orden_pago.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var monto_orden_pago = str;


			             if(monto_orden_pago!=tota_grilla_partidas){
			                   fun_msj('El total de las partidas es diferente al monto de la orden de pago');
						       return false;
			             }

	}//fin else


	if(document.getElementById('fecha_documento_orden').value!=""){
           return verifica_cierre_mes_ejecucion('fecha_documento_orden');
    }


	if(document.getElementById('cod_tipo_pago_select').value!='12' && existe=="1" && existe2=="1" && existe3=="1"){
			var confir_tpago = confirm('Esta Orden Corresponde a CESTA TICKET, desea continuar con el registro en base a este Tipo de Pago');
			if(confir_tpago == true){
				return true;
			}else{
				fun_msj('Esta orden corresponde a CESTA TICKET, favor revisar Tipo de Pago');
				return false;
			}
	}

}//fin funcion
*/