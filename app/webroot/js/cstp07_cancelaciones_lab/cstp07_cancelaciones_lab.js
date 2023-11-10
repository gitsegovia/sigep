

function cstp07_cancelaciones_lab_calcula_total(){


total = 0;


for(ii=0; ii<document.getElementById('cuenta_i_partidas').value; ii++){
   if(document.getElementById('monto_'+ii)){
                a = document.getElementById('monto_'+ii).value;
                var str = a;
		        for(i=0; i<a.length; i++){str = str.replace('.','');}//fin for
		        str   = str.replace(',','.');
		        var a = redondear(str,2);
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





         var str =  document.getElementById('TOTALINGRESOS2').innerHTML;
		 for(i=0; i<str.length; i++){str = str.replace('.','');}//fin for
		 str   = str.replace(',','.');
		 var a = redondear(str,2);

       if(total>a || total<a){
             fun_msj('El monto de las ordenes de pago no cuadra con el de las partidas');
       }//fin

 cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total);
}//fin function

function valida_cfpp07_an2o(){
   var mydate=new Date()
   var year1=mydate.getYear()
   var year=mydate.getYear()
      if (year < 1000)
           year+=1901

   if(document.getElementById('anoPresupuesto').value==''){

			fun_msj('Inserte el a&ntilde;o');
			document.getElementById('anoPresupuesto').focus();
			return false;

	}else if(document.getElementById('anoPresupuesto').value.length<4){

			fun_msj('Inserte un a&ntilde;o correcto');
			document.getElementById('anoPresupuesto').focus();
			return false;


	}else if(document.getElementById('anoPresupuesto').value< 2000 || document.getElementById('anoPresupuesto').value>year){
	fun_msj("Inserte un a&ntilde;o correcto ");
			document.getElementById('anoPresupuesto').focus();
			return false;
	}
}//fin funcion valida_cfpp07auxilair

function cstp07_cancelaciones_lab_valida(){



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











