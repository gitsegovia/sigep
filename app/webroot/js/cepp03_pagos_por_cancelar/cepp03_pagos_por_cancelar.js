
function cepp03_pagos_por_cancelar_editar_2(){

total = 0;

for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){

           if(document.getElementById('monto_'+ii)){
               a = document.getElementById('monto_'+ii).value;
		       var str = a;
		       for(i=0; i<a.length; i++){str = str.replace('.','');}//fin for
		        str   = str.replace(',','.');
		        var a = redondear(str,2);
		        //total =  eval(total) + eval(a);
		        total = eval(total) + eval(a);
		     }//fin if

     }//fin



//////////////////////////////////////////////////////////////
		 var str =  total+'';
		 for(x=0; x<str.length; x++){if(str.charAt(x)=="."){
		    total=str.substr(0,eval(x)+eval(6));
		    break;
		    }//fin if
		   }//fin for
		 var total = redondear(total,2);
//////////////////////////////////////////////////////////////


 cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total);


}//fin function
















function cepp03_pagos_montar_valor(valor){


 document.getElementById('monto').disabled = false;
 document.getElementById('monto').value = valor;
 // moneda("monto");


}///fin function












function cepp03_pagos_agregar_orden_session(){


       if(document.getElementById('select_num_orden').value == ""){

            fun_msj('Inserte n&uacute;mero de la orden');
			document.getElementById('select_num_orden').focus();
			return false;

  }else if(document.getElementById('bene').value==""){

            fun_msj('Insertter el Autorizado a Cobrar');
			document.getElementById('bene').focus();
			return false;

  }else if(diferenciaFecha(document.getElementById('fecha').value, document.getElementById('fecha_orden_pago_aux').value)){

            fun_msj('la Fecha del de la Orden de Pago es mayor a la fecha de cheque.');
            return false;

  }else{




	var band = false;

if(document.getElementById('cuenta_iii') && document.getElementById('cuenta_iii').value!="" && document.getElementById('cuenta_iii').value!="0"){

		 var str =  document.getElementById('TOTALINGRESOS_ANTERIORES').innerHTML;
		 for(i=0; i<str.length; i++){str = str.replace('.','');}//fin for
		 str   = str.replace(',','.');
		 var total = redondear(str,2);

         a = document.getElementById('dispo').value;
		 var str = a;
		 for(i=0; i<a.length; i++){str = str.replace('.','');}//fin for
		 str   = str.replace(',','.');
		 var a = redondear(str,2);

         m = document.getElementById('monto').value;
		 var str = m;
		 for(i=0; i<m.length; i++){str = str.replace('.','');}//fin for
		 str   = str.replace(',','.');
		 var m = redondear(str,2);

		total_monto = eval(total) + eval(m);
		var total_monto = redondear(total_monto,2);

		if(eval(total_monto) > eval(a)){
			// El TOTAL Bs + El Monto, se pasa de la disponibilidad...
			band = true;
		}
}



if(band == true){

	fun_msj('La disponibilidad de la cuenta no es suficiente...');
	return false;

}else{



total = 0;

for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){

          if(document.getElementById('monto_'+ii)){
               a = document.getElementById('monto_'+ii).value;
		       var str = a;
		       for(i=0; i<a.length; i++){str = str.replace('.','');}//fin for
		        str   = str.replace(',','.');
		        var a = redondear(str,2);
		        //total =  eval(total) + eval(a);
		        total = eval(total) + eval(a);
         }//fin if
     }//fin



//////////////////////////////////////////////////////////////
		 var str =  total+'';
		 for(x=0; x<str.length; x++){if(str.charAt(x)=="."){
		    total=str.substr(0,eval(x)+eval(6));
		    break;
		    }//fin if
		   }//fin for
		 var total = redondear(total,2);
//////////////////////////////////////////////////////////////


               a = document.getElementById('monto').value;
		       var str = a;
		       for(i=0; i<a.length; i++){str = str.replace('.','');}//fin for
		        str   = str.replace(',','.');
		        var a = redondear(str,2);

		        ///alert(total); alert(a);

      // if(total>a  ||  total<a){

            // fun_msj('El monto de la orden no cuadra con la imputaci&oacute;n');
			//document.getElementById('monto').focus();
			//return false;

       ///}else{





		 for(x=0; x<str.length; x++){if(str.charAt(x)=="."){
		    total=str.substr(0,eval(x)+eval(6));
		    break;
		    }//fin if
		   }//fin for


		 var total = redondear(total,2);



               a = document.getElementById('dispo').value;
		       var str = a;
		       for(i=0; i<a.length; i++){str = str.replace('.','');}//fin for
		        str   = str.replace(',','.');
		        var a = redondear(str,2);

       if(total>a){

             fun_msj('La disponibilidad de la cuenta no es suficiente');
			document.getElementById('monto').focus();
			return false;

       }else{
                //alert(document.getElementById('monto').value+' +++ '+document.getElementById('TOTALINGRESOS').innerHTML)

             if(document.getElementById('monto').value==document.getElementById('TOTALINGRESOS').innerHTML){

                   document.getElementById('enviar_orden').disabled=true;

             }
             /*else{

                    fun_msj('El monto de la orden de pago no es igual al de las partidas');
			        document.getElementById('monto').focus();
			        return false;

                   }//fin else
             */

       }//fin else


       //}//fin else

	}
   }//fin else aLUUUUU

}//fin function








function cepp03_pagos_por_cancelar_valida_consulta(){

	if(verifica_cierre_ano_ejecucion_msj()==false){
		return false;
	}else if(document.getElementById('concepto_anulacion').value==""){

            fun_msj('Inserte el concepto de anulaci&oacute;n');
			document.getElementById('numero_cheque').focus();
			return false;


   	}//fin if

}//fin function









function validar_concepto_anulacion_cheque(){



	if(verifica_cierre_ano_ejecucion_msj()==false){
		return false;
	}else if(document.getElementById('concepto_anulacion').value == ""){
			fun_msj('Ingrese el concepto de la anulaci&oacute;n');
			setTimeout("fondoCampo('concepto_anulacion',2);", 3500);
	        document.getElementById('concepto_anulacion').focus();
			return false;
    }else if(!confirm("Esta Seguro que desea anular este cheque")){
         return false;
    }else{

                 document.getElementById('guardar').disabled=true;
    }//fin



}//fin function











function cepp03_pagos_por_cancelar_valida(){

   if(document.getElementById('numero_cheque').value==""){


            fun_msj('Faltan datos para generar el n&uacute;mero de cheque');
			//document.getElementById('numero_cheque').focus();
			return false;

   }else if(document.getElementById('numero_cheque').value=="0"){

            fun_msj('Debe generar n&uacute;mero de cheques');
			document.getElementById('numero_cheque').focus();
			return false;


   }else if(document.getElementById('fecha').value==""){

            fun_msj('Debe seleccionar la fecha');
			document.getElementById('fecha').focus();
			return false;

   }else if(verifica_cierre_ano_ejecucion('fecha')==false){
		fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
		return false;
   }else if(document.getElementById('concepto').value==""){

            fun_msj('Debe Ingresar el concepto de la orden');
			document.getElementById('concepto').focus();
			return false;

   }else if(document.getElementById('bene').value==""){

            fun_msj('Inserte el Autorizado a Cobrar');
			document.getElementById('bene').focus();
			return false;
/*
    }else if(diferenciaFecha(document.getElementById('fecha').value, document.getElementById('fecha_documento_anterior').value)){
            documento_anterior  = document.getElementById('numero_documento_anterior').value;
            fecha_anterior      = document.getElementById('fecha_documento_anterior').value;
            numero_documento    = document.getElementById('numero_cheque').value;
            fun_msj('Fecha chequeeeee '+numero_documento+' menor a fecha '+fecha_anterior+' de cheque '+documento_anterior);
			return false;
*/
   }else if(valida_fechas_menores_documentos(4)==2){
      			return false;


   }else if(valida_fechas_documentos_mayores(6)==2){
      			return false;

   }else{

      if(document.getElementById('cuenta_iii')){


        if(document.getElementById('cuenta_iii').value!="" && document.getElementById('cuenta_iii').value!="0"){


		 var str =  document.getElementById('TOTALINGRESOS_ANTERIORES').innerHTML;
		 for(i=0; i<str.length; i++){str = str.replace('.','');}//fin for
		 str   = str.replace(',','.');
		 var total = redondear(str,2);


         a = document.getElementById('dispo').value;
		 var str = a;
		 for(i=0; i<a.length; i++){str = str.replace('.','');}//fin for
		 str   = str.replace(',','.');
		 var a = redondear(str,2);


				       opcion_op_fecha = 1;



								  for(iiii=1; iiii<document.getElementById('cuenta_iii').value; iiii++){
								    if(document.getElementById('cuenta_fecha_orden_pago_'+iiii)){
										  if(diferenciaFecha(document.getElementById('fecha').value, document.getElementById('cuenta_fecha_orden_pago_'+iiii).value)){
								            fecha_documento   = document.getElementById('cuenta_fecha_orden_pago_'+iiii).value;
								            numero_documento  = document.getElementById('cuenta_numero_orden_pago_'+iiii).value;
								            opcion_op_fecha   = 2;
					                      }//fin if
				                      }//fin if
								  }//fin


				                             if(opcion_op_fecha==2){

					                             fun_msj('Fecha del cheque es menor a la fecha de la orden de pago '+numero_documento);
					                             return false;

								       }else if(total>a){

									             fun_msj('Cuenta no tiene disponibilidad');
												 //document.getElementById('monto').focus();
												 return false;

									   }else{

									               document.getElementById('guardar').disabled=true;

									         }//fin else


            }else{

               fun_msj('No ha agregado una orden de pago');
		       return false;


            }//fin else


         }else{

               fun_msj('No ha agregado una orden de pago');
		       return false;

         }//fin else

     }//fin else

     if(document.getElementById('fecha').value!=""){
           document.getElementById('guardar').disabled="";
           return verifica_cierre_mes_ejecucion('fecha');
    }

}//fin function





