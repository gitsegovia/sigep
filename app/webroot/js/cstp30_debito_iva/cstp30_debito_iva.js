function cstp30_debito_iva_valida(){



         if(document.getElementById('numero_debito').value==""){


            fun_msj('Faltan datos para generar el n&uacute;mero de Debito');
			document.getElementById('numero_debito').focus();
			return false;

   }else if(document.getElementById('numero_debito').value=="0"){

            fun_msj('Debe generar n&uacute;mero de Debito');
			document.getElementById('numero_debito').focus();
			return false;


   }else if(document.getElementById('concepto').value==""){

            fun_msj('El concepto de la orden');
			document.getElementById('concepto').focus();
			return false;

   }else if(valida_fechas_documentos_mayores(6)==2){
      			return false;

   }else{

      if(document.getElementById('cuenta_i_orden_pago')){


        if(document.getElementById('cuenta_i_orden_pago').value!="" && document.getElementById('cuenta_i_orden_pago').value!="0"){


		 var str =  document.getElementById('TOTALINGRESOS2').innerHTML;
		 for(i=0; i<str.length; i++){str = str.replace('.','');}//fin for
		 str   = str.replace(',','.');
		 var total = redondear(str,2);


         a = document.getElementById('dispo').value;
		 var str = a;
		 for(i=0; i<a.length; i++){str = str.replace('.','');}//fin for
		 str   = str.replace(',','.');
		 var a = redondear(str,2);


       if(total>a){

             fun_msj('Sobre pasa la disponibilidad de la cuenta');
			 //document.getElementById('monto').focus();
			 return false;

       }else{



      if(document.getElementById('cuenta_i_partidas')){

        if(document.getElementById('cuenta_i_partidas').value!="" && document.getElementById('cuenta_i_partidas').value!="0"){

         var str =  document.getElementById('TOTALINGRESOS2').innerHTML;
		 for(i=0; i<str.length; i++){str = str.replace('.','');}//fin for
		 str   = str.replace(',','.');
		 var a = redondear(str,2);

		 var str =  document.getElementById('TOTALINGRESOS').innerHTML;
		 for(i=0; i<str.length; i++){str = str.replace('.','');}//fin for
		 str   = str.replace(',','.');
		 var total = redondear(str,2);


       if(total>a || total<a){
             fun_msj('El monto de las ordenes de pago no cuadra con el de las partidas');
             return false;
       }//fin


         }else{

               fun_msj('No hay partidas agregadas');
		       return false;


            }//fin else


       }else{

               fun_msj('No hay partidas agregadas');
		       return false;


            }//fin else



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



}//fin function





function validar_concepto_anulacion_debito(){



    if(document.getElementById('concepto_anulacion').value == ""){
			fun_msj('Ingrese el concepto de la anulaci&oacute;n');
			setTimeout("fondoCampo('concepto_anulacion',2);", 3500);
	        document.getElementById('concepto_anulacion').focus();
			return false;
    }else if(!confirm("Esta Seguro que desea anular este registro")){
         return false;
    }else{

                 document.getElementById('guardar').disabled=true;
    }//fin



}//fin function