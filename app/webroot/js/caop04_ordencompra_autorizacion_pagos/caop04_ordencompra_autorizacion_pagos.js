

function caop04_ordencompra_autorizacion_pagos_valida(){




if(document.getElementById('pregunta_pago_parcial_2_1').checked!=false || document.getElementById('pregunta_pago_parcial_2_2').checked!=false){

   if(document.getElementById('pregunta_pago_parcial_2_1').checked!=false){


        if(document.getElementById('pregunta_pago_parcial_1').checked!=false || document.getElementById('pregunta_pago_parcial_2').checked!=false){


          if(document.getElementById('monto_opcion_pago').value == ""){

            fun_msj('Inserte el monto a asignar');
			document.getElementById('monto_opcion_pago').focus();
			return false;



     }else if(document.getElementById('monto_a_pagar_con_iva').value == ""){

            fun_msj('Inserte el monto a pagar con i.v.a');
			document.getElementById('monto_a_pagar_con_iva').focus();
			return false;


     }else if(document.getElementById('monto_iva').value == ""){

            fun_msj('Inserte el monto i.v.a');
			document.getElementById('monto_iva').focus();
			return false;


     }else if(document.getElementById('monto_sin_iva').value == ""){

            fun_msj('Inserte el monto sin i.v.a');
			document.getElementById('monto_sin_iva').focus();
			return false;

     }else  if(document.getElementById('retencion_incluye_iva').value == ""){

            fun_msj('Inserte el porcentaje de retenci&oacute;n');
			document.getElementById('retencion_incluye_iva').focus();
			return false;


     }else if(document.getElementById('impuesto_sobre_la_renta').value == ""){


            fun_msj('Inserte el porcentaje de impuesto sobre la renta');
			document.getElementById('impuesto_sobre_la_renta').focus();
			return false;


     }else if(document.getElementById('timbre_fiscal').value == ""){

            fun_msj('Inserte el timbre fiscal');
			document.getElementById('timbre_fiscal').focus();
			return false;


     }else if(document.getElementById('impuesto_municipal').value == ""){

           fun_msj('Inserte el porcentaje del impuesto municipal');
		   document.getElementById('impuesto_municipal').focus();
		   return false;


     }else if(document.getElementById('amortizacion_del_anticipo').value == ""){

          fun_msj('Inserte el porcentaje del anticipo');
		  document.getElementById('amortizacion_del_anticipo').focus();
		  return false;


     }else if(document.getElementById('total_retencion_monto_iva').value == ""){

          fun_msj('Inserte el total de las retenciones');
		  document.getElementById('total_retencion_monto_iva').focus();
		  return false;


       }else if(document.getElementById('concepto').value == ""){

          fun_msj('Inserte el concepto de la autorizacion de pago');
		  document.getElementById('concepto').focus();
		  return false;


       }else{

             if(document.getElementById('monto_a_pagar_con_iva').value != document.getElementById('TOTALINGRESOS').innerHTML){
                         fun_msj('El monto a pagar con I.V.A no es igual al de las partidas');
                         return false;
                      }//fin else

            }//fin else

        }else{ fun_msj('Indique forma de asignaci&oacute;n parcial');  return false;}//fin else



   }else if(document.getElementById('pregunta_pago_parcial_2_2').checked!=false){



     if(document.getElementById('monto_a_pagar_con_iva').value == ""){

            fun_msj('Inserte el monto a pagar con i.v.a');
			document.getElementById('monto_a_pagar_con_iva').focus();
			return false;


     }else if(document.getElementById('monto_iva').value == ""){

            fun_msj('Inserte el monto i.v.a');
			document.getElementById('monto_iva').focus();
			return false;


     }else if(document.getElementById('monto_sin_iva').value == ""){

            fun_msj('Inserte el monto sin i.v.a');
			document.getElementById('monto_sin_iva').focus();
			return false;

     }else  if(document.getElementById('retencion_incluye_iva').value == ""){

            fun_msj('Inserte el porcentaje de retenci&oacute;n');
			document.getElementById('retencion_incluye_iva').focus();
			return false;


     }else if(document.getElementById('impuesto_sobre_la_renta').value == ""){


            fun_msj('Inserte el porcentaje de impuesto sobre la renta');
			document.getElementById('impuesto_sobre_la_renta').focus();
			return false;


     }else if(document.getElementById('timbre_fiscal').value == ""){

            fun_msj('Inserte el timbre fiscal');
			document.getElementById('timbre_fiscal').focus();
			return false;


     }else if(document.getElementById('impuesto_municipal').value == ""){

           fun_msj('Inserte el porcentaje del impuesto municipal');
		   document.getElementById('impuesto_municipal').focus();
		   return false;


     }else if(document.getElementById('amortizacion_del_anticipo').value == ""){

          fun_msj('Inserte el porcentaje del anticipo');
		  document.getElementById('amortizacion_del_anticipo').focus();
		  return false;


     }else if(document.getElementById('total_retencion_monto_iva').value == ""){

          fun_msj('Inserte el total de las retenciones');
		  document.getElementById('total_retencion_monto_iva').focus();
		  return false;


       }else if(document.getElementById('concepto').value == ""){

          fun_msj('Inserte el concepto de la autorizacion de pago');
		  document.getElementById('concepto').focus();
		  return false;


        }else{

            if(document.getElementById('monto_a_pagar_con_iva').value != document.getElementById('TOTALINGRESOS').innerHTML){
                         fun_msj('El monto a pagar con I.V.A no es igual al de las partidas');
                         return false;
                      }//fin else


         }//fin else

    }else{   fun_msj('Selecione una opci&oacute;n');
             return false;
         }//fin else


}else{ fun_msj('Indique si la asignaci&oacute;n sera parcial');  return false;}//fin else









}//fin functiontr rth




































function caop04_ordencompra_autorizacion_pagos_monto_id_editar_2(id_i,op_i){


opc_a = document.getElementById(id_i).value;
opc_b = document.getElementById('monto_actual_'+op_i).value



/////////////////////////////////////////
var str = opc_a;
for(i=0; i<opc_a.length; i++){str = str.replace('.','');}//fin for
str   = str.replace(',','.');
var opc_a = redondear(str,2);
/////////////////////////////////////////


/////////////////////////////////////////
var str = opc_b;
for(i=0; i<opc_b.length; i++){str = str.replace('.','');}//fin for
str   = str.replace(',','.');
var opc_b = redondear(str,2);
/////////////////////////////////////////



if(eval(opc_a)>eval(opc_b)){

fun_msj('El monto insertado en mayor al monto de la partida');
document.getElementById(id_i).value="0,00";


}else{


total = 0;
var monto_iva_var= 0;

for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
 a = document.getElementById('pago_'+ii).value;
var str = a;
for(i=0; i<a.length; i++){str = str.replace('.','');}//fin for
str   = str.replace(',','.');
var a = redondear(str,2);
total =  eval(total) + eval(a);

if(document.getElementById('partida_iva_'+ii)){
		        monto_iva_var = redondear( eval(monto_iva_var) + eval(a) );
}//fin partida iva
}//fin for



var str =  total+'';


for(x=0; x<str.length; x++){
 if(str.charAt(x)=="."){
          total=str.substr(0,eval(x)+eval(6));
       break;
   }//fin if
}//fin for

var total = redondear(total,2);
aa = document.getElementById("monto_a_pagar_con_iva").value;
var str = aa;
for(i=0; i<aa.length; i++){
    str = str.replace('.','');
}//fin for
str = str.replace(',','.');
var aa = redondear(str,2);

opcion = "monto a pagar con i.v.a";


//porcentaje_iva = document.getElementById('porcentaje_iva').value;
//porcentaje_iva = eval(porcentaje_iva) / eval(100);
//document.getElementById('monto_iva_var').value = redondear(eval(total) * eval(porcentaje_iva),2);
//moneda('monto_iva');


document.getElementById('monto_a_pagar_con_iva').disabled = false;
document.getElementById('monto_a_pagar_con_iva').value=total;



document.getElementById('monto_iva').disabled = false;
document.getElementById('monto_iva').value=monto_iva_var;


//moneda('monto_a_pagar_con_iva');
//moneda('monto_iva');


cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total);

detalles_del_pago();

 }//fin else
}///fin function


























function caop04_ordencompra_autorizacion_pagos_colocar_iva(id_i,op_i){


opc_a = document.getElementById(id_i).value;
opc_b = document.getElementById('monto_actual_'+op_i).value


/////////////////////////////////////////
var str = opc_a;
for(i=0; i<opc_a.length; i++){str = str.replace('.','');}//fin for
str   = str.replace(',','.');
var opc_a = redondear(str,2);
/////////////////////////////////////////


/////////////////////////////////////////
var str = opc_b;
for(i=0; i<opc_b.length; i++){str = str.replace('.','');}//fin for
str   = str.replace(',','.');
var opc_b = redondear(str,2);
/////////////////////////////////////////




if(eval(opc_a)>eval(opc_b)){

fun_msj('El monto insertado en mayor al monto de la partida');
document.getElementById(id_i).value="0,00";


}else{


        total = 0;
        var monto_iva_var = 0;



for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
       if(document.getElementById('partida_iva_'+ii)){
               a = document.getElementById('pago_'+ii).value;
		       var str = a;
		       for(i=0; i<a.length; i++){str = str.replace('.','');}//fin for
		        str   = str.replace(',','.');
		        var a = redondear(str,2);
		        //total =  eval(total) + eval(a);
		        monto_iva_var = eval(monto_iva_var) + eval(a);
           }//fin partida iva
     }//fin




//////////////////////////////////////////////////////////////
		 var str =  monto_iva_var+'';
		 for(x=0; x<str.length; x++){if(str.charAt(x)=="."){
		    monto_iva_var=str.substr(0,eval(x)+eval(6));
		    break;
		    }//fin if
		   }//fin for
		 monto_iva_var = redondear(monto_iva_var,2);
//////////////////////////////////////////////////////////////




document.getElementById('monto_iva').value = monto_iva_var;
moneda('monto_iva');


//detalles_del_pago();

  }//fin else
}//fin function









function caop04_ordencompra_autorizacion_pagos_valida_consulta(){



  if(document.getElementById('concepto_anulacion').value == ""){

            fun_msj('Inserte el concepto de anulacion');
			document.getElementById('concepto_anulacion').focus();
			return false;

   }//fin if





}//fin funciton





