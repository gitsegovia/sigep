function cscp04_ordencompra_autorizacion_pagos_valida(){

if(verifica_cierre_ano_ejecucion('fecha_autorizacion_pagos')==false){
	fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA DE AUTORIZACI&Oacute;N DEL PAGO NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
	return false;
}else{

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

            /* if(document.getElementById('monto_orden_de_pago_monto_iva').value != document.getElementById('TOTALINGRESOS').innerHTML){
                         fun_msj('El monto a pagar con I.V.A no es igual al de las partidas');
                         return false;
                      }//fin else*/

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

             /* if(document.getElementById('monto_orden_de_pago_monto_iva').value != document.getElementById('TOTALINGRESOS').innerHTML){
                         fun_msj('El monto a pagar con I.V.A no es igual al de las partidas');
                         return false;
                      }//fin else*/


         }//fin else

    }else{   fun_msj('Selecione una opci&oacute;n');
             return false;
         }//fin else


}else{ fun_msj('Indique si la asignaci&oacute;n sera parcial');  return false;}//fin else



// Comparamos si la fecha de actualizacion del proveedor es menor a la fecha de la autorizacion de pago. Si es asi imprimimos el mensaje.
if(diferenciaFecha(document.getElementById('fecha_actualizacion').value, document.getElementById('fecha_autorizacion_pagos').value)){
	//fun_msj('PROVEEDOR NO ESTA ACTUALIZADO EN EL REGISTRO DE PROVEEDORES Y CONTRATISTAS');
	if (confirm("PROVEEDOR NO ESTA ACTUALIZADO EN EL REGISTRO DE PROVEEDORES Y CONTRATISTAS")) {

	}else{
		document.getElementById('fecha_autorizacion_pagos').focus();
		return false;
    		}
		}

	}// FIN ELSE VERIFICANDO ANO EJEC <> ANO FECHA DOC.

}//fin functiontr rth



function pregunta_pago_parcial(opc){

        if(opc=="2"){

        porcentaje_iva = document.getElementById('porcentaje_iva').value;
        porcentaje_iva = eval(porcentaje_iva) / eval(100);

        total = 0;
        var monto_iva_var = 0;

     for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
         document.getElementById('pago_'+ii).value = document.getElementById('monto_actual_'+ii).value;
         a = document.getElementById('monto_actual_'+ii).value;
		 var str = a;
		 for(i=0; i<a.length; i++){str = str.replace('.','');}//fin for
		 str   = str.replace(',','.');
		 var a = redondear(str,2);
		total =  eval(total) + eval(a);
		document.getElementById('pago_'+ii).readOnly = true;
		document.getElementById('pago_'+ii).disabled = false;
		document.getElementById('pago_aux_'+ii).readOnly = true;
		document.getElementById('pago_aux_'+ii).disabled = false;

           if(document.getElementById('partida_iva_'+ii)){
               a = document.getElementById('monto_actual_'+ii).value;
		       var str = a;
		       for(i=0; i<a.length; i++){str = str.replace('.','');}//fin for
		        str   = str.replace(',','.');
		        var a = redondear(str,2);
		        //total =  eval(total) + eval(a);
		        monto_iva_var = eval(monto_iva_var) + eval(a);
		        document.getElementById('partida_iva_'+ii).readOnly = true;
		        document.getElementById('partida_iva_'+ii).disabled = false;
           }//fin partida iva
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
//////////////////////////////////////////////////////////////
		 var str =  monto_iva_var+'';
		 for(x=0; x<str.length; x++){if(str.charAt(x)=="."){
		    monto_iva_var=str.substr(0,eval(x)+eval(6));
		    break;
		    }//fin if
		   }//fin for


		 monto_iva_411  = 0;
		 for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
		           if(document.getElementById('partida_411_'+ii)){
		                            a = retornar_valor_calculo(document.getElementById('pago_'+ii).value);
				        monto_iva_411 = eval(monto_iva_411) + eval(a);
		           }//fin partida iva
		 }//fin

	  	 monto_iva_411    = redondear(monto_iva_411,2);
	     factor_reversion = redondear(eval(1) + eval(porcentaje_iva),2);
	  	 base             = redondear(eval(monto_iva_411) / eval(factor_reversion),2);
		 monto_iva_411    = eval(monto_iva_411) - eval(base);
		 monto_iva_var    = eval(monto_iva_var) +  eval(monto_iva_411);
		 monto_iva_var    = redondear(monto_iva_var,2);



//////////////////////////////////////////////////////////////
         document.getElementById('monto_a_pagar_con_iva').value= document.getElementById('saldo_orden').value;
		 moneda('monto_a_pagar_con_iva');
	     cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total);
	      document.getElementById('monto_a_pagar_con_iva').readOnly = true;
		  document.getElementById('monto_a_pagar_con_iva').disabled = false;
		  document.getElementById('monto_iva').readOnly = true;
		  document.getElementById('monto_iva').disabled = false;
		  document.getElementById('monto_iva').value = monto_iva_var;
		  moneda('monto_iva');
	   // document.getElementById('monto_iva_var').value = eval(total) * eval(porcentaje_iva);
	   // moneda('monto_iva_var');
         detalles_del_pago();


   }else if(opc=="1"){////////////////////////////////////////////////////////////////////////////////////////////////


         total = 0;
         for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
            document.getElementById('pago_'+ii).value = "0,00";
            // document.getElementById('pago_aux_'+ii).value = "0,00";
            document.getElementById('pago_'+ii).readOnly = true;
            document.getElementById('pago_'+ii).disabled = false;
			document.getElementById('pago_aux_'+ii).readOnly = true;
			document.getElementById('pago_aux_'+ii).disabled = false;
      	}//fin

         document.getElementById('monto_a_pagar_con_iva').value=redondear(total, 2);
		 moneda('monto_a_pagar_con_iva');
		 cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total);
         document.getElementById('monto_a_pagar_con_iva').readOnly = true;
		 document.getElementById('monto_a_pagar_con_iva').disabled = false;
		 document.getElementById('monto_iva').readOnly = true;
		 document.getElementById('monto_iva').disabled = false;
		 document.getElementById('monto_iva').value = "0,00";


        detalles_del_pago();

   }//fin
}//fin function








function cscp04_ordencompra_autorizacion_pagos_monto_id_editar_2(id_i,op_i){


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

fun_msj('El monto insertado es mayor al monto de la partida');
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







function cscp04_ordencompra_autorizacion_pagos_colocar_iva(id_i,op_i){


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






function cal_civil_social(a){

    var Msi   = reemplazarPC(document.getElementById('monto_sin_iva').value);
    var Prc   = reemplazarPC(document.getElementById('rcivil').value);
    var Prs   = reemplazarPC(document.getElementById('rsocial').value);
	var calculo=0;

if(a==1){
	calculo=(Msi*Prc)/100
		$('retencion_multa_monto').value=redondear(calculo,2);
		moneda('retencion_multa_monto');

}else{
	calculo=(Msi*Prs)/100
		$('retencion_responsabilidad_social').value=redondear(calculo,2);
		moneda('retencion_responsabilidad_social');

}
 	detalles_del_pago();


}












function detalles_del_pago(lineap){


	var monto_iva_var_401 = 0;
	var monto_mano_obra = 0.00;
    var porc_retencion_laboral   = 0.00;
    var monto_retencion_laboral  = 0.00;
    var porc_retencion_fielc     = 0.00;
    var monto_retencion_fielc    = 0.00;

for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
	if(document.getElementById('partida_401_'+ii)){
		if(document.getElementById('partida_401_'+ii).value=="si"){a = document.getElementById('pago_'+ii).value; var str = a;
			acepto="no";
				for(i=0; i<a.length; i++){if(str.charAt(i)==","){acepto="si";}}if(acepto=="si"){
					for(i=0; i<a.length; i++){str = str.replace('.','');}str   = str.replace(',','.'); }
						var a = redondear(str,2);monto_iva_var_401 = eval(monto_iva_var_401) + eval(a);}}}
						var str =  monto_iva_var_401+'';
							for(x=0; x<str.length; x++){if(str.charAt(x)=="."){ monto_iva_var_401=str.substr(0,eval(x)+eval(6));break;}}
								monto_iva_var_401 = redondear(monto_iva_var_401,2);

monto_iva_var_401 = 0;


monto_pago_sin_403 = 0;
for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
             a = retornar_valor_calculo(document.getElementById('pago_'+ii).value);
		 var a = redondear(a,2);
		   if(!document.getElementById('partida_iva_'+ii)){
                                 a = retornar_valor_calculo(document.getElementById('pago_'+ii).value);
		        monto_pago_sin_403 = eval(monto_pago_sin_403) + eval(a);
           }//fin partida iva
}//fin
monto_pago_sin_403  = redondear(monto_pago_sin_403,2);

porcentaje_iva = retornar_valor_calculo($('porcentaje_iva').value);
porcentaje_iva = eval(porcentaje_iva) / eval(100);

monto_iva_411  = 0;
for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
           if(document.getElementById('partida_411_'+ii)){
                a = retornar_valor_calculo(document.getElementById('pago_'+ii).value);
		        monto_iva_411 = eval(monto_iva_411) + eval(a);
           }//fin partida iva
}//fin
monto_iva_411    = redondear(monto_iva_411,2);


	     factor_reversion = redondear(eval(1) + eval(porcentaje_iva),2);
	  	 base             = redondear(eval(monto_iva_411) / eval(factor_reversion),2);
		 monto_iva_411    = eval(monto_iva_411) - eval(base);
		 monto_iva_var    = eval(monto_iva_var) +  eval(monto_iva_411);
		 monto_iva_var    = redondear(monto_iva_var, 2);

if(monto_iva_var=='0.00'){
document.getElementById('retencion_incluye_iva').options[0].text =  '0';
document.getElementById('retencion_incluye_iva').options[0].value = '0';
document.getElementById('retencion_incluye_iva').options[0].selected = true;
 for (var i = 1; i < document.getElementById('retencion_incluye_iva').length; i++){
  document.getElementById('retencion_incluye_iva').options[i].text =  '';
  document.getElementById('retencion_incluye_iva').options[i].value = '0';
  ii++;
 }//fin for
}//fin if
acepto="no";

sustraendo = document.getElementById("sustraendo").value;
var str = sustraendo;for(i=0; i<sustraendo.length; i++){if(str.charAt(i)==","){acepto="si";}}if(acepto=="si"){for(i=0; i<sustraendo.length; i++){str = str.replace('.','');}str = str.replace(',','.');}
var sustraendo = redondear(str,2);


  document.getElementById('porc_retencion_laboral').disabled = false;
  document.getElementById('monto_retencion_laboral').disabled = false;
  document.getElementById('porc_retencion_fielc').disabled = false;
  document.getElementById('monto_retencion_fielc').disabled = false;
  document.getElementById('monto_sin_iva').disabled = false;
  document.getElementById('retencion_incluye_iva').disabled = false;
  document.getElementById('retencion_incluye_iva_monto_iva').disabled = false;
  document.getElementById('impuesto_sobre_la_renta').disabled = false;
  document.getElementById('impuesto_sobre_la_renta_monto_iva').disabled = false;
  document.getElementById('timbre_fiscal').disabled = false;
  document.getElementById('sustraendo').disabled = false;
  document.getElementById('timbre_fiscal_monto_iva').disabled = false;
  document.getElementById('impuesto_municipal').disabled = false;
  document.getElementById('impuesto_municipal_monto_iva').disabled = false;
  document.getElementById('amortizacion_del_anticipo').disabled = false;
  document.getElementById('amortizacion_del_anticipo_monto_iva').disabled = false;
  document.getElementById('total_retencion_monto_iva').disabled = false;
  document.getElementById('monto_orden_de_pago_monto_iva').disabled = false;
  document.getElementById('monto_a_pagar_monto_iva').disabled = false;
  document.getElementById('monto_a_pagar_con_iva').readOnly = true;
  document.getElementById('monto_iva').readOnly = true;
  document.getElementById('total_factura').disabled = false;
  document.getElementById('porcentaje_iva').readOnly = false;
  document.getElementById('monto_mano_obra').readOnly = false;
  document.getElementById('porc_retencion_laboral').readOnly = false;
  document.getElementById('porc_retencion_fielc').readOnly = false;
  document.getElementById('rcivil').disabled = false;
  document.getElementById('retencion_multa_monto').disabled = false;
  document.getElementById('retencion_responsabilidad_social').disabled = false;
  document.getElementById('rsocial').disabled = false;

	porc_retencion_laboral  = reemplazarPC(document.getElementById('porc_retencion_laboral').value);
    monto_retencion_laboral = reemplazarPC(document.getElementById('monto_retencion_laboral').value);
    porc_retencion_fielc    = reemplazarPC(document.getElementById('porc_retencion_fielc').value);
    monto_retencion_fielc   = reemplazarPC(document.getElementById('monto_retencion_fielc').value);
    porcentaje_amortizacion_input = reemplazarPC(document.getElementById('amortizacion_del_anticipo').value);

							acepto="no";
							monto_retencion_multa_monto = document.getElementById("retencion_multa_monto").value;
							var str = monto_retencion_multa_monto;
							for(i=0; i<monto_retencion_multa_monto.length; i++){
							   if(str.charAt(i)==","){acepto="si";}
							}//fin for
							if(acepto=="si"){
							  for(i=0; i<monto_retencion_multa_monto.length; i++){str = str.replace('.','');}//fin for
							  str = str.replace(',','.');

							}//fin
							var monto_retencion_multa_monto = redondear(str,2);

							acepto="no";
							monto_retencion_responsabilidad_social = document.getElementById("retencion_responsabilidad_social").value;
							var str = monto_retencion_responsabilidad_social;
							for(i=0; i<monto_retencion_responsabilidad_social.length; i++){
							   if(str.charAt(i)==","){acepto="si";}
							}//fin for
							if(acepto=="si"){
							  for(i=0; i<monto_retencion_responsabilidad_social.length; i++){str = str.replace('.','');}//fin for
							  str = str.replace(',','.');

							}//fin
							var monto_retencion_responsabilidad_social = redondear(str,2);

	retener_multa_y_responsabilidad = eval(monto_retencion_multa_monto) + eval(monto_retencion_responsabilidad_social);

	var monto_a_pagar_con_iva = 0;
	var monto_iva_var  = 0;
	acepto="no";
	monto_a_pagar_con_iva = document.getElementById("monto_a_pagar_con_iva").value;
	var str = monto_a_pagar_con_iva;
	for(i=0; i<monto_a_pagar_con_iva.length; i++){
   		if(str.charAt(i)==","){acepto="si";}
	}//fin for
	if(acepto=="si"){
  		for(i=0; i<monto_a_pagar_con_iva.length; i++){str = str.replace('.','');}//fin for
  		str = str.replace(',','.');
	}//fin
	var monto_a_pagar_con_iva = redondear(str,2);

	acepto="no";
	monto_iva_var = document.getElementById("monto_iva").value;
	var str = monto_iva_var;
	for(i=0; i<monto_iva_var.length; i++){
   		if(str.charAt(i)==","){acepto="si";}
	}//fin for
	if(acepto=="si"){
	monto_iva_var = reemplazarPC(monto_iva_var);
	}//fin








//ORIGINAL CUERPO

    moneda('monto_a_pagar_con_iva');
	monto_mano_obra = monto_a_pagar_con_iva;

if(lineap!=null){
	monto_mano_obra = reemplazarPC(document.getElementById('monto_mano_obra').value);
}

	pl=redondear((porc_retencion_laboral/100), 4);
	pf=redondear((porc_retencion_fielc/100), 4);

	monto_retencion_laboral = redondear((eval(monto_mano_obra) * pl), 2);
	monto_retencion_fielc   = redondear((eval(monto_a_pagar_con_iva) * pf), 2);
	total_retenciones  =  eval(monto_retencion_laboral) + eval(monto_retencion_fielc);


// ORIGINAL
/*

	total_factura      =  eval(monto_a_pagar_con_iva) - eval(total_retenciones);

if(monto_retencion_laboral==0 && monto_retencion_fielc==0){
		 monto_iva_cuerpo = monto_iva_var;
		 base_iva_factura = redondear(eval(total_factura) - eval(monto_iva_cuerpo),2);
		 factura_sin_iva  = base_iva_factura;
}else{
	     factor_reversion = redondear(eval(1) + eval(porcentaje_iva),2);
	  	 base_iva_factura = redondear(eval(total_factura)  / eval(factor_reversion),2);
	  	 factura_sin_iva  = redondear(eval(monto_a_pagar_con_iva)  / eval(factor_reversion),2);
		 monto_iva_cuerpo = eval(base_iva_factura) * eval(porcentaje_iva);
		 monto_iva_cuerpo = redondear(monto_iva_cuerpo, 2);
		 monto_iva_var = monto_iva_cuerpo;
}

*/

//FIN ORIGINAL



// GOB.GUARICO


	total_factura      =  eval(monto_a_pagar_con_iva);

if(monto_retencion_laboral==0 && monto_retencion_fielc==0){
		 monto_iva_cuerpo = monto_iva_var;
		 base_iva_factura = redondear(eval(total_factura) - eval(monto_iva_cuerpo),2);
		 factura_sin_iva  = base_iva_factura;
}else{
	     factor_reversion = redondear(eval(1) + eval(porcentaje_iva),2);
	  	 base_iva_factura = redondear(eval(total_factura)  / eval(factor_reversion),2);
	  	 factura_sin_iva  = redondear(eval(monto_a_pagar_con_iva)  / eval(factor_reversion),2);
		 monto_iva_cuerpo = eval(base_iva_factura) * eval(porcentaje_iva);
		 monto_iva_cuerpo = redondear(monto_iva_cuerpo, 2);
		 monto_iva_var = monto_iva_cuerpo;
}

//FIN GUARICO




			document.getElementById('monto_mano_obra').value = monto_mano_obra;
			moneda('monto_mano_obra');

			document.getElementById('monto_retencion_laboral').value = monto_retencion_laboral;
			moneda('monto_retencion_laboral');

			document.getElementById('monto_retencion_fielc').value = monto_retencion_fielc;
			moneda('monto_retencion_fielc');

  			document.getElementById("total_factura").value = redondear(eval(total_factura),2);
  			moneda('total_factura');

			monto_sin_iva  = redondear(eval(base_iva_factura), 2);
			monto_sin_iva2 = redondear(eval(base_iva_factura), 2);

	porcentaje_islr_input = document.getElementById("impuesto_sobre_la_renta").value;
	var str = porcentaje_islr_input;for(i=0; i<porcentaje_islr_input.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var porcentaje_islr_input = str;

	porcentaje_impuesto_sobre_la_renta =  eval(porcentaje_islr_input);

	var sustraendo = 0;
	var sustraendo_tresporciento = 0;
	if(document.getElementById("objeto_rif").value=="4"){
		sustraendo_neto = retornar_valor_calculo(document.getElementById("sustraendo_neto").value);
		sustraendo_tresporciento = retornar_valor_calculo(document.getElementById("sustraendo_tresporciento").value);

		if(porcentaje_impuesto_sobre_la_renta!="3"){sustraendo = eval(sustraendo_neto) * eval(porcentaje_impuesto_sobre_la_renta);}
			else {sustraendo=sustraendo_tresporciento;
		}
	}

	if(document.getElementById("tipo_ordencompra_3").checked){
		impuesto_sobre_la_renta = ((eval(document.getElementById("monto_total_servicios").value) *  eval(porcentaje_impuesto_sobre_la_renta)/100))   -   eval(sustraendo);
	}else{
		impuesto_sobre_la_renta = ((eval(monto_sin_iva) *  eval(porcentaje_impuesto_sobre_la_renta)/100))   -   eval(sustraendo);
	}


	document.getElementById("sustraendo").value = redondear(sustraendo, 2);
	moneda('sustraendo');
//////////////////////////////////////////////////////////////
	var str =  impuesto_sobre_la_renta+''; for(x=0; x<str.length; x++){if(str.charAt(x)=="."){ impuesto_sobre_la_renta=str.substr(0,eval(x)+eval(6));break;}}var impuesto_sobre_la_renta = redondear(impuesto_sobre_la_renta,2);
//////////////////////////////////////////////////////////////


	document.getElementById('monto_sin_iva').value = monto_sin_iva2;
	moneda('monto_sin_iva');

	if ((eval(monto_sin_iva2) < eval(document.getElementById('desde_monto_islr').value)) || eval(document.getElementById('exento_islr_cooperativa').value=="1")){
		document.getElementById('impuesto_sobre_la_renta').value = "0.00";
  		document.getElementById('impuesto_sobre_la_renta_monto_iva').value = "0.00";
  		document.getElementById('impuesto_sobre_la_renta').disabled = true;
  		document.getElementById('impuesto_sobre_la_renta_monto_iva').disabled = true;
  		var impuesto_sobre_la_renta = 0;
  		var impuesto_sobre_la_renta_monto_iva = 0;
  	}

	if (eval(monto_sin_iva2) < eval(document.getElementById('desde_monto_timbre').value)){
  		document.getElementById('timbre_fiscal').value = "0.00";
  		document.getElementById('timbre_fiscal_monto_iva').value = "0.00";
  		document.getElementById('timbre_fiscal').disabled = true;
  		document.getElementById('timbre_fiscal_monto_iva').disabled = true;
  		var timbre_fiscal = 0;
  		var timbre_fiscal_monto_iva = 0;
  		var porcentaje_timbre_input = 0;
	}

	if (eval(monto_sin_iva2) < eval(document.getElementById('desde_monto_impuesto_municipal').value)){
  		document.getElementById('impuesto_municipal').value = "0.00";
  		document.getElementById('impuesto_municipal_monto_iva').value = "0.00";
  		document.getElementById('impuesto_municipal').disabled = true;
  		document.getElementById('impuesto_municipal_monto_iva').disabled = true;
  		var impuesto_municipal = 0;
  		var impuesto_municipal_monto_iva = 0;
  		var porcentaje_impuesto_muni_input = 0;
	}

		aux_retencion = redondear( eval(monto_iva_cuerpo) * (eval(document.getElementById("retencion_incluye_iva").value) / eval(100)) , 2);
		document.getElementById('retencion_incluye_iva_monto_iva').value = aux_retencion;

		moneda('retencion_incluye_iva_monto_iva');
		document.getElementById('impuesto_sobre_la_renta_monto_iva').value = redondear(impuesto_sobre_la_renta  , 2);moneda('impuesto_sobre_la_renta_monto_iva');

		porcentaje_timbre_input = document.getElementById("timbre_fiscal").value;
		var str = porcentaje_timbre_input;for(i=0; i<porcentaje_timbre_input.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var porcentaje_timbre_input = str;
		document.getElementById('timbre_fiscal_monto_iva').value =  redondear( (eval(monto_sin_iva) / eval(1000)) *  (eval(porcentaje_timbre_input)) , 2);moneda('timbre_fiscal_monto_iva');

		porcentaje_impuesto_muni_input = document.getElementById("impuesto_municipal").value;
		var str = porcentaje_impuesto_muni_input;for(i=0; i<porcentaje_impuesto_muni_input.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var porcentaje_impuesto_muni_input = str;
		document.getElementById('impuesto_municipal_monto_iva').value = redondear((eval(monto_sin_iva) *  (eval(porcentaje_impuesto_muni_input) / eval(100)) ), 2);moneda('impuesto_municipal_monto_iva');

		porcentaje_amortizacion_input = document.getElementById("amortizacion_del_anticipo").value;
		var str = porcentaje_amortizacion_input;for(i=0; i<porcentaje_amortizacion_input.length; i++){str = str.replace('.','');} str = str.replace(',','.');var porcentaje_amortizacion_input = str;

		acepto="no";
		var str = porcentaje_amortizacion_input;

		for(i=0; i<porcentaje_amortizacion_input.length; i++){if(str.charAt(i)==","){acepto="si";}}
			if(acepto=="si"){
				for(i=0; i<porcentaje_amortizacion_input.length; i++){str = str.replace('.','');}str = str.replace(',','.');}var porcentaje_amortizacion_input = redondear(str,2);

		acepto="no";
		saldo_orden = document.getElementById("saldo_orden").value;
		var str = saldo_orden;
		for(i=0; i<saldo_orden.length; i++){
   			if(str.charAt(i)==","){acepto="si";}
		}//fin for

		if(acepto=="si"){
  			for(i=0; i<saldo_orden.length; i++){str = str.replace('.','');}//fin for
  				str = str.replace(',','.');
		}//fin

		var saldo_orden = redondear(str,2);
		nuevo_monto =  redondear(eval(saldo_orden) - eval(monto_a_pagar_con_iva), 2);
		document.getElementById('nuevo_monto_pagar').value = redondear(eval(saldo_orden) - eval(monto_a_pagar_con_iva), 2);
		moneda('nuevo_monto_pagar');

		if(nuevo_monto=="0"){
		saldo_anticipo = document.getElementById('saldo_anticipo').value;
		document.getElementById('amortizacion_del_anticipo_monto_iva').value = saldo_anticipo;
		amortizacion_del_anticipo_monto_iva = saldo_anticipo;
		acepto="no";
		var str = amortizacion_del_anticipo_monto_iva;

		for(i=0; i<amortizacion_del_anticipo_monto_iva.length; i++){
   			if(str.charAt(i)==","){acepto="si";}
		}//fin for

		if(acepto=="si"){
  			for(i=0; i<amortizacion_del_anticipo_monto_iva.length; i++){str = str.replace('.','');}//fin for
  				str = str.replace(',','.');
		}//fin

		var amortizacion_del_anticipo_monto_iva = redondear(str,2);
		}else{
///////////// amortizacion_del_anticipo_monto_iva ///////////////
		if(document.getElementById('anticipo_con_iva').value=="1"){
    	amortizacion_del_anticipo_monto_iva = redondear( eval(monto_a_pagar_con_iva) *  (eval(porcentaje_amortizacion_input) / eval(100)), 2);
		}else{
    	amortizacion_del_anticipo_monto_iva = redondear( eval(monto_sin_iva) *  (eval(porcentaje_amortizacion_input) / eval(100)), 2);
		}//fin else
		document.getElementById('amortizacion_del_anticipo_monto_iva').value = amortizacion_del_anticipo_monto_iva;
		moneda('amortizacion_del_anticipo_monto_iva');
////////////////////////////////////////////////////////////////
		}//fin  else


		impuesto_sobre_la_renta = redondear(impuesto_sobre_la_renta, 2);
		retencion_incluye_iva_monto_iva =  redondear( eval(monto_iva_var) * (eval(document.getElementById("retencion_incluye_iva").value) / eval(100)) , 2);
		timbre_fiscal_monto_iva =  redondear( (eval(monto_sin_iva) / eval(1000)) *  (eval(porcentaje_timbre_input)) , 2);
		impuesto_municipal_monto_iva = redondear( eval(monto_sin_iva) *  (eval(porcentaje_impuesto_muni_input) / eval(100)), 2);

		if(impuesto_sobre_la_renta<0){ impuesto_sobre_la_renta = eval(impuesto_sobre_la_renta) * eval(-1);}//fin if

		total_retencion = eval(monto_retencion_laboral) + eval(monto_retencion_fielc) +eval(retencion_incluye_iva_monto_iva) + eval(impuesto_sobre_la_renta) + eval(timbre_fiscal_monto_iva) + eval(impuesto_municipal_monto_iva) + eval(amortizacion_del_anticipo_monto_iva) + eval(retener_multa_y_responsabilidad);
		document.getElementById('total_retencion_monto_iva').value = redondear(total_retencion, 2);
		moneda('total_retencion_monto_iva');

		document.getElementById('monto_orden_de_pago_monto_iva').value = redondear(eval(monto_a_pagar_con_iva) - (eval(monto_retencion_laboral) + eval(monto_retencion_fielc) + eval(amortizacion_del_anticipo_monto_iva)), 2);
		moneda('monto_orden_de_pago_monto_iva');

		document.getElementById('monto_a_pagar_monto_iva').value = redondear(eval(monto_a_pagar_con_iva) - eval(total_retencion), 2);
		moneda('monto_a_pagar_monto_iva');

		moneda('retencion_multa_monto');
		moneda('retencion_responsabilidad_social');

//FIN ORIGINAL CUERPO





//PARTIDAS


var var_retencion_laboral  = new Array();
var var_retencion_fiel     = new Array();
var var_retencion_amortiza = new Array();
var valor_aux_pago         = new Array();

	monto_de_la_orden_pago = redondear(eval(monto_a_pagar_con_iva) - eval(amortizacion_del_anticipo_monto_iva) -  eval(monto_retencion_fielc) -  eval(monto_retencion_laboral), 2);

         if(porc_retencion_laboral!=0){
				for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
				                var_pago                  =  retornar_valor_calculo(document.getElementById('pago_'+ii).value);
				                var_retencion_laboral[ii] = redondear(  (eval(var_pago) * eval(monto_retencion_laboral)) / eval(monto_a_pagar_con_iva), 2);
				}//fin
		 }//fin

         if(porc_retencion_fielc!=0){
				for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
				                var_pago               = retornar_valor_calculo(document.getElementById('pago_'+ii).value);
				                var_retencion_fiel[ii] = redondear(  (eval(var_pago) * eval(monto_retencion_fielc)) / eval(monto_a_pagar_con_iva), 2);
				}//fin
		  }//fin



				p_a_i = retornar_valor_calculo(document.getElementById("amortizacion_del_anticipo_monto_iva").value);
				p_i_i = retornar_valor_calculo(document.getElementById("porcentaje_iva").value);



             if(p_a_i!=0){
					for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){

						if(!document.getElementById('partida_iva_'+ii)){

							if(document.getElementById('anticipo_con_iva').value=="1"){
						  		var_pago = retornar_valor_calculo(document.getElementById('pago_'+ii).value);

						  		var_retencion_amortiza[ii] = redondear( ((eval(var_pago) + (eval(var_pago) * (eval(p_a_i)/eval(100)))) * eval(factura_sin_iva)) / 100 , 2);
						  			if(nuevo_monto=="0"){
						   				var_retencion_amortiza[ii] = retornar_valor_calculo(document.getElementById('m_p_a_a_'+ii).value);
						  			}
							}else{
						  		var_pago                   = retornar_valor_calculo(document.getElementById('pago_'+ii).value);
                                var_retencion_amortiza[ii] = redondear(  (eval(var_pago) * eval(p_a_i)) / eval(factura_sin_iva), 2);
					      			if(nuevo_monto=="0"){
						   				var_retencion_amortiza[ii] = retornar_valor_calculo(document.getElementById('m_p_a_a_'+ii).value);
						  			}
							}//fin else
						}//fin id
					}//for
			 }//fin


				for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
					var_pago         = retornar_valor_calculo(document.getElementById('pago_'+ii).value);
					valor_aux_pago_2 = eval(var_pago);

						if(var_retencion_amortiza[ii]){ valor_aux_pago_2 = eval(valor_aux_pago_2) - eval(var_retencion_amortiza[ii]);
							valor_aux_pago[ii] = redondear(valor_aux_pago_2, 2);
						}



// GOB.GUARICO
							valor_aux_pago[ii] = redondear(valor_aux_pago_2,2);
						if(document.getElementById('partida_iva_'+ii)){
							valor_aux_pago[ii] = redondear(valor_aux_pago_2,2);
						}else{
							if(var_retencion_fiel[ii]){
								monto_iva_fiel = redondear(eval(var_retencion_fiel[ii]) * eval(p_i_i/100), 2);
								valor_aux_pago_2 = (eval(valor_aux_pago_2) - (eval(var_retencion_fiel[ii]) + eval(monto_iva_fiel)));
								valor_aux_pago[ii] = redondear(valor_aux_pago_2,2);
							}
							if(var_retencion_laboral[ii]){
								monto_iva_laboral = redondear(eval(var_retencion_laboral[ii]) * eval(p_i_i/100), 2);
								valor_aux_pago_2 = (eval(valor_aux_pago_2) - (eval(var_retencion_laboral[ii]) + eval(monto_iva_laboral)));
								valor_aux_pago[ii] = redondear(valor_aux_pago_2,2);
							}
						}
//FIN GOB.GUARICO




// ORIGINAL
/*

					if(var_retencion_fiel[ii]){     valor_aux_pago_2 = eval(valor_aux_pago_2) - eval(var_retencion_fiel[ii]);}
					if(var_retencion_laboral[ii]){  valor_aux_pago_2 = eval(valor_aux_pago_2) - eval(var_retencion_laboral[ii]);}
					valor_aux_pago[ii] = redondear(valor_aux_pago_2,2);
*/
//FIN ORIGINAL

				}//for



//////////////////////////////////REDONDEO I.V.A/////////////////////////////////////////
                  monto_iva_pago_aux_1 = redondear(monto_iva_var,2); //Monto del iva en el cuerpo de la valuación
                  monto_iva_pago_aux_2 = 0;
				  for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
					if(document.getElementById('partida_iva_'+ii)){
                         monto_iva_pago_aux_2 = eval(monto_iva_pago_aux_2) + eval(valor_aux_pago[ii]);
					}//fin id
			      }//fin

                  monto_iva_pago_aux_2 =  redondear(monto_iva_pago_aux_2,2);

                  // PRIMER REDONDEO POS
                      if(eval(monto_iva_pago_aux_1)>eval(monto_iva_pago_aux_2)  && eval(monto_iva_var)!=eval(0)){
	                      for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
							if(document.getElementById('partida_iva_'+ii) && eval(monto_iva_pago_aux_1)!=eval(monto_iva_pago_aux_2)){
                               valor_aux_pago[ii]   = eval(valor_aux_pago[ii])   + eval(0.01);
                               monto_iva_pago_aux_2 = eval(monto_iva_pago_aux_2) + eval(0.01);
                               monto_iva_pago_aux_2 =  redondear(monto_iva_pago_aux_2,2);
							}//fin id
					      }//fin
			      // SEGUNDO REDONDEO POS
                      		if(eval(monto_iva_pago_aux_1)>eval(monto_iva_pago_aux_2)  && eval(monto_iva_var)!=eval(0)){
	                      		for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
									if(document.getElementById('partida_iva_'+ii) && eval(monto_iva_pago_aux_1)!=eval(monto_iva_pago_aux_2)){
                               			valor_aux_pago[ii]   = eval(valor_aux_pago[ii])   + eval(0.01);
                               			monto_iva_pago_aux_2 = eval(monto_iva_pago_aux_2) + eval(0.01);
                               			monto_iva_pago_aux_2 =  redondear(monto_iva_pago_aux_2,2);
										}//fin if
					      			}//for
					    		}//if
			      // TERCER REDONDEO POS
                      		if(eval(monto_iva_pago_aux_1)>eval(monto_iva_pago_aux_2)  && eval(monto_iva_var)!=eval(0)){
	                      		for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
									if(document.getElementById('partida_iva_'+ii) && eval(monto_iva_pago_aux_1)!=eval(monto_iva_pago_aux_2)){
                               			valor_aux_pago[ii]   = eval(valor_aux_pago[ii])   + eval(0.01);
                               			monto_iva_pago_aux_2 = eval(monto_iva_pago_aux_2) + eval(0.01);
                               			monto_iva_pago_aux_2 =  redondear(monto_iva_pago_aux_2,2);
										}//fin if
					      			}//for
					   			}//if	monto_iva_var = reemplazarPC(monto_iva_var);
                	}else
					// PRIMER REDONDEO NEG
                	if(eval(monto_iva_pago_aux_1)<eval(monto_iva_pago_aux_2)  && eval(monto_iva_var)!=eval(0)){
                         for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
							if(document.getElementById('partida_iva_'+ii)  && eval(monto_iva_pago_aux_1)!=eval(monto_iva_pago_aux_2)){
                               valor_aux_pago[ii]   = eval(valor_aux_pago[ii])   - eval(0.01);
                               monto_iva_pago_aux_2 = eval(monto_iva_pago_aux_2) - eval(0.01);
                               monto_iva_pago_aux_2 =  redondear(monto_iva_pago_aux_2,2);
							}//fin if
					      }//for
					// SEGUNDO REDONDEO NEG
                			if(eval(monto_iva_pago_aux_1)<eval(monto_iva_pago_aux_2)  && eval(monto_iva_var)!=eval(0)){
                         		for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
									if(document.getElementById('partida_iva_'+ii)  && eval(monto_iva_pago_aux_1)!=eval(monto_iva_pago_aux_2)){
                               			valor_aux_pago[ii]   = eval(valor_aux_pago[ii])   - eval(0.01);
                               			monto_iva_pago_aux_2 = eval(monto_iva_pago_aux_2) - eval(0.01);
                               			monto_iva_pago_aux_2 =  redondear(monto_iva_pago_aux_2,2);
										}//fin if
					      			}//for
					    		}//if
					// TERCER REDONDEO NEG
                			if(eval(monto_iva_pago_aux_1)<eval(monto_iva_pago_aux_2)  && eval(monto_iva_var)!=eval(0)){
                         		for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
									if(document.getElementById('partida_iva_'+ii)  && eval(monto_iva_pago_aux_1)!=eval(monto_iva_pago_aux_2)){
                               			valor_aux_pago[ii]   = eval(valor_aux_pago[ii])   - eval(0.01);
                               			monto_iva_pago_aux_2 = eval(monto_iva_pago_aux_2) - eval(0.01);
                               			monto_iva_pago_aux_2 =  redondear(monto_iva_pago_aux_2,2);
									}//fin if
					      		}//for
					    	}//if
                }//fin else


//////////////////////////////////REDONDEO CANCELADO/////////////////////////////////////////
total_cancelado_aux = 0;
	                  for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
					      total_cancelado_aux = eval(total_cancelado_aux) + eval(valor_aux_pago[ii]);
			          }//fin

                      total_cancelado_aux =  redondear(total_cancelado_aux,2);

				      // PRIMER REDONDEO POS
                      if(eval(monto_de_la_orden_pago)>eval(total_cancelado_aux)){
	                      for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
							if(!document.getElementById('partida_iva_'+ii) && eval(monto_de_la_orden_pago)!=eval(total_cancelado_aux)){
                               valor_aux_pago[ii]   = eval(valor_aux_pago[ii])  + eval(0.01);
                               total_cancelado_aux  = eval(total_cancelado_aux) + eval(0.01);
                               total_cancelado_aux =  redondear(total_cancelado_aux,2);
							}//fin id
					      }//fin
					 // SEGUNDO REDONDEO POS
                      		if(eval(monto_de_la_orden_pago)>eval(total_cancelado_aux)){
	                      		for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
									if(!document.getElementById('partida_iva_'+ii) && eval(monto_de_la_orden_pago)!=eval(total_cancelado_aux)){
                               			valor_aux_pago[ii]   = eval(valor_aux_pago[ii])  + eval(0.01);
                               			total_cancelado_aux  = eval(total_cancelado_aux) + eval(0.01);
                               			total_cancelado_aux =  redondear(total_cancelado_aux,2);
									}//fin if
					      		}//for
					   		}//if
					 // TERCER REDONDEO POS
                      		if(eval(monto_de_la_orden_pago)>eval(total_cancelado_aux)){
	                      		for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
									if(!document.getElementById('partida_iva_'+ii) && eval(monto_de_la_orden_pago)!=eval(total_cancelado_aux)){
                               			valor_aux_pago[ii]   = eval(valor_aux_pago[ii])  + eval(0.01);
                               			total_cancelado_aux  = eval(total_cancelado_aux) + eval(0.01);
                               			total_cancelado_aux =  redondear(total_cancelado_aux,2);
									}//fin if
					      		}//for
					   		}//if
                	}else
					// PRIMER REDONDEO NEG
                	if(eval(monto_de_la_orden_pago)<eval(total_cancelado_aux)){
                         for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
							if(!document.getElementById('partida_iva_'+ii)  && eval(monto_de_la_orden_pago)!=eval(total_cancelado_aux)){
                               valor_aux_pago[ii]   = eval(valor_aux_pago[ii])  - eval(0.01);
                               total_cancelado_aux  = eval(total_cancelado_aux) - eval(0.01);
                               total_cancelado_aux =  redondear(total_cancelado_aux,2);
							}//fin id
					      }//fin
					// SEGUNDO REDONDEO NEG
                			if(eval(monto_de_la_orden_pago)<eval(total_cancelado_aux)){
                         		for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
									if(!document.getElementById('partida_iva_'+ii)  && eval(monto_de_la_orden_pago)!=eval(total_cancelado_aux)){
                               			valor_aux_pago[ii]   = eval(valor_aux_pago[ii])  - eval(0.01);
                               			total_cancelado_aux  = eval(total_cancelado_aux) - eval(0.01);
                               			total_cancelado_aux =  redondear(total_cancelado_aux,2);
							}//fin if
					      }//for
					   }//if
					// TERCER REDONDEO NEG
                			if(eval(monto_de_la_orden_pago)<eval(total_cancelado_aux)){
                         		for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
									if(!document.getElementById('partida_iva_'+ii)  && eval(monto_de_la_orden_pago)!=eval(total_cancelado_aux)){
                               			valor_aux_pago[ii]   = eval(valor_aux_pago[ii])  - eval(0.01);
                               			total_cancelado_aux  = eval(total_cancelado_aux) - eval(0.01);
                               			total_cancelado_aux =  redondear(total_cancelado_aux,2);
							}//fin if
					      }//for
					   }//if
                	}//else


//////////////////////////////////ACTUALIZAR PAGO_AUX/////////////////////////////////////////
                total2_aux = 0;
                for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){

					    document.getElementById('pago_aux_'+ii).value = redondear(valor_aux_pago[ii],2);
					    total3_aux                                    = redondear(valor_aux_pago[ii],2);
				        total2_aux                                    = redondear(eval(total2_aux) + eval(total3_aux),2);
				        moneda('pago_aux_'+ii);
			      }//fin
				cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total2_aux);


var civil_social=0;
var total_reten = 0;
var a =0;
var b = 0;
a=document.getElementById('retencion_multa_monto').value;

if(a.indexOf(",")!=-1){
a=reemplazarPC(document.getElementById('retencion_multa_monto').value);
}
b=document.getElementById('retencion_responsabilidad_social').value;

if(b.indexOf(",")!=-1){
b=reemplazarPC(document.getElementById('retencion_responsabilidad_social').value);
}
				total_reten= eval(document.getElementById('monto_retencion_laboral').value) +  eval(document.getElementById('monto_retencion_fielc').value) +  eval(document.getElementById('amortizacion_del_anticipo_monto_iva').value) +  eval(document.getElementById('retencion_incluye_iva_monto_iva').value) +  eval(document.getElementById('impuesto_sobre_la_renta_monto_iva').value) + eval(document.getElementById('timbre_fiscal_monto_iva').value) + eval(document.getElementById('impuesto_municipal_monto_iva').value);
				total_reten=redondear(eval(total_reten),2) + redondear(eval(a),2) + redondear(eval(b),2);
				document.getElementById('total_retenciones').value=redondear(eval(total_reten),2);

moneda('total_retenciones');
moneda('monto_iva');

//FIN PARTIDAS




}//fin function





function respuesta_pago_parcial(opcion){

total = 0;

var valor_partidas = new Array();

                       if(opcion=="1"){/////////////////////////////////////////////////OPCION I/////////////////////////////////////

monto_opcion_pago = document.getElementById('monto_opcion_pago').value;var str = monto_opcion_pago;
for(i=0; i<monto_opcion_pago.length; i++){str = str.replace('.','');}str = str.replace(',','.');var monto_opcion_pago = redondear(str,2);

for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
   document.getElementById('pago_'+ii).value = document.getElementById('monto_actual_'+ii).value;
   a = document.getElementById('monto_actual_'+ii).value;
   var str = a;
   for(i=0; i<a.length; i++){str = str.replace('.','');}str   = str.replace(',','.');
   var a = redondear(str,2);total =  eval(total) + eval(a);
   document.getElementById('pago_'+ii).readOnly = false;
   document.getElementById('pago_'+ii).disabled = false;
}//fin

var str =  total+'';for(x=0; x<str.length; x++){if(str.charAt(x)=="."){total=str.substr(0,eval(x)+eval(6));break;}}var total = redondear(total,2);
if(monto_opcion_pago>total){fun_msj('La asignaci&oacute;n parcial es mayor al total de la orden');}else{
total2 = 0;
var monto_iva_var = 0;

for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
    a = document.getElementById('monto_actual_'+ii).value;
    var str = a;
    for(i=0; i<a.length; i++){str = str.replace('.','');}
    str   = str.replace(',','.');var a = redondear(str,2);
    resul = redondear(((eval(monto_opcion_pago) * eval(a)) / eval(total) ) , 2 );
    total2 =  eval(total2) + eval(resul);
    valor_partidas[ii]= redondear(resul, 2);
    document.getElementById('pago_'+ii).readOnly = false;
	    if(document.getElementById('partida_iva_'+ii)){
		    monto_iva_var = eval(monto_iva_var) + eval(resul);
		    document.getElementById('partida_iva_'+ii).readOnly = true;
		    document.getElementById('partida_iva_'+ii).disabled = false;
	    }
}



monto_iva_var = redondear(monto_iva_var, 2);



var str =  total2+'';for(x=0; x<str.length; x++){if(str.charAt(x)=="."){total2=str.substr(0,eval(x)+eval(6));break;}} var total2 = redondear(total2, 2);
aux = 0;


document.getElementById('monto_iva').disabled = false;  document.getElementById('monto_iva').value=monto_iva_var;

document.getElementById('monto_a_pagar_con_iva').disabled = false;document.getElementById('monto_a_pagar_con_iva').value=monto_opcion_pago;
//detalles_del_pago();
/////////////////////////////////////////////// DIFERENCIA INICIO ////////////////////////////////////////////////////////
aa = document.getElementById("monto_opcion_pago").value;
var str = aa;for(i=0; i<aa.length; i++){str = str.replace('.','');}str = str.replace(',','.');var aa = redondear(str,2);
monto_opcion_pago_input = aa;aux=0;aux = eval(monto_opcion_pago_input) - eval(total2);
var str =  aux+'';for(x=0; x<str.length; x++){if(str.charAt(x)=="."){aux=str.substr(0,eval(x)+eval(6));break;}}var aux = redondear(aux, 2);var aux = eval(aux);
for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
   var str =  valor_partidas[ii]+'';
   for(x=0; x<str.length; x++){
     if(str.charAt(x)=="."){str=str.substr(0,eval(x)+eval(6));break;}}
     valor_partidas[ii] = redondear(str, 2);
     valor_partidas[ii] = eval(valor_partidas[ii]);
   }//fin for
if(monto_opcion_pago_input!=total2){rendondear_decimal(monto_opcion_pago_input,total2, valor_partidas);}else{for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){document.getElementById("pago_"+ii).value=valor_partidas[ii]; cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total2);}}//fin else
/////////////////////////////////////////////// DIFERENCIA FIN /////////////////////////////////////////////////////////
}//fin else

detalles_del_pago();for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){moneda("pago_"+ii);}


                  }else if(opcion=="2"){//////////////////////////////////////////////////////////////////////////////////////////



porcentaje_opcion_pago = document.getElementById('monto_opcion_pago').value;
var str = porcentaje_opcion_pago;for(i=0; i<porcentaje_opcion_pago.length; i++){str = str.replace('.','');}str = str.replace(',','.');var porcentaje_opcion_pago = redondear(str,2);
porcentaje_opcion_pago = eval(porcentaje_opcion_pago) /  eval(100);
monto_opcion_pago_porcentaje = 0;
saldo_orden_value            = 0;
a = document.getElementById('saldo_orden').value;var str = a;for(i=0; i<a.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var saldo_orden_value = redondear(str,2);
monto_opcion_pago_porcentaje = eval(saldo_orden_value) * eval(porcentaje_opcion_pago);

total = 0;
total2 = 0;
var monto_iva_var = 0;
for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){a = document.getElementById('monto_actual_'+ii).value;var str = a;for(i=0; i<a.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var a = redondear(str,2);total =  eval(total) + eval(a);}//fin
for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){ a = document.getElementById('monto_actual_'+ii).value; var str = a; for(i=0; i<a.length; i++){str = str.replace('.','');} str   = str.replace(',','.'); var a = redondear(str,2);resul = redondear(   ((eval(a) * (eval(total) * eval(porcentaje_opcion_pago)) ) / eval(total) ) , 2 );total2 =  eval(total2) + eval(resul); valor_partidas[ii]=redondear(resul, 2); document.getElementById('pago_'+ii).readOnly = false;document.getElementById('pago_'+ii).disabled = false;moneda('pago_'+ii);if(document.getElementById('partida_iva_'+ii)){a = resul;monto_iva_var = redondear( eval(monto_iva_var) + eval(a) );document.getElementById('partida_iva_'+ii).readOnly = true;document.getElementById('partida_iva_'+ii).disabled = false;}}
var str =  total2+'';for(x=0; x<str.length; x++){if(str.charAt(x)=="."){total2=str.substr(0,eval(x)+eval(6));break;}}var total2 = redondear(total2, 2);
aux = 0;
document.getElementById('monto_iva').disabled = false;document.getElementById('monto_iva').value=monto_iva_var;
document.getElementById('monto_a_pagar_con_iva').disabled = false;document.getElementById('monto_a_pagar_con_iva').value=monto_opcion_pago_porcentaje;
//detalles_del_pago();
/////////////////////////////////////////////// DIFERENCIA ////////////////////////////////////////////////////////
ax = document.getElementById('monto_actual').value;var str = ax;for(i=0; i<ax.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var ax = redondear(str,2);monto_opcion_pago_input = eval(ax) * eval(porcentaje_opcion_pago);
aux=0;aux = eval(monto_opcion_pago_input) - eval(total2);
var str =  aux+'';for(x=0; x<str.length; x++){if(str.charAt(x)=="."){aux=str.substr(0,eval(x)+eval(6));break;}}var aux = redondear(aux, 2);var aux = eval(aux);
for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){var str =  valor_partidas[ii]+'';for(x=0; x<str.length; x++){if(str.charAt(x)=="."){str=str.substr(0,eval(x)+eval(6));break;}}valor_partidas[ii] = redondear(str, 2);valor_partidas[ii] = eval(valor_partidas[ii]);}
if(monto_opcion_pago_input!=total2){rendondear_decimal(monto_opcion_pago_input,total2,valor_partidas);}else{for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){document.getElementById("pago_"+ii).value=valor_partidas[ii];cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total2);}}//fin else
//////////////////////////////////// DIFERENCIA ////////////////////////////////////////
detalles_del_pago();for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){moneda("pago_"+ii);}
   }//fin else


}//fin function






function rendondear_decimal(monto_opcion_pago_input, total2, valor_partidas){


//monto_opcion_pago_input   :::::::::::  es el monto de la valuacion

j = 0;///////////////


var monto_a_pagar_con_iva = 0;var monto_iva_var = 0;acepto="no";monto_a_pagar_con_iva = document.getElementById("monto_a_pagar_con_iva").value;
var str = monto_a_pagar_con_iva;for(i=0; i<monto_a_pagar_con_iva.length; i++){if(str.charAt(i)==","){acepto="si";}}
if(acepto=="si"){for(i=0; i<monto_a_pagar_con_iva.length; i++){str = str.replace('.','');} str = str.replace(',','.');}
var monto_a_pagar_con_iva = redondear(str,2);acepto="no";monto_iva_var = document.getElementById("monto_iva").value;var str = monto_iva_var;for(i=0; i<monto_iva_var.length; i++){if(str.charAt(i)==","){acepto="si";}}
if(acepto=="si"){for(i=0; i<monto_iva_var.length; i++){str = str.replace('.','');}str = str.replace(',','.');}monto_iva_var = redondear(str,2);
saldo_orden = document.getElementById("saldo_orden").value;var str = saldo_orden;
for(i=0; i<saldo_orden.length; i++){if(str.charAt(i)==","){acepto="si";}}if(acepto=="si"){for(i=0; i<saldo_orden.length; i++){str = str.replace('.','');}str = str.replace(',','.');}
var saldo_orden = redondear(str,2);
nuevo_monto =  redondear(eval(saldo_orden) - eval(monto_a_pagar_con_iva), 2);
saldo_actual_orden= redondear(eval(saldo_orden) - eval(monto_a_pagar_con_iva), 2);
for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
 a = document.getElementById('monto_actual_'+ii).value; var str = a;for(i=0; i<a.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var a = redondear(str,2);
 b = document.getElementById('monto_amortizacion_anteriores_'+ii).value; var str = b;for(i=0; i<b.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var b = redondear(str,2);
 c = document.getElementById('monto_partida_anteriores_'+ii).value; var str = c;for(i=0; i<c.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var c = redondear(str,2);
 d = document.getElementById('monto_total_partidas_'+ii).value; var str = d;for(i=0; i<d.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var d = redondear(str,2);

   aux = eval(monto_opcion_pago_input) - eval(total2);
   var str =  valor_partidas[ii]+'';for(x=0; x<str.length; x++){if(str.charAt(x)=="."){str=str.substr(0,eval(x)+eval(6));break;}}valor_partidas[ii] = redondear(str, 2);valor_partidas[ii] = eval(valor_partidas[ii]);

 a_aux = eval(c) + eval(valor_partidas[ii]);

      if(saldo_actual_orden==0 && valor_partidas[ii]>a){ j = eval(valor_partidas[ii]) - eval(a);  valor_partidas[ii] = valor_partidas[ii] - eval(j);  total2 = eval(total2) - eval(j);}//fin fin
      if(saldo_actual_orden==0 && valor_partidas[ii]<a){ j = eval(a) - eval(valor_partidas[ii]);  valor_partidas[ii] = valor_partidas[ii] + eval(j);  total2 = eval(total2) + eval(j);}//fin fin
      if(monto_opcion_pago_input<total2){if(a_aux!= eval(d)){total2 = eval(total2) - eval(0.01);  valor_partidas[ii] = valor_partidas[ii] - eval(0.01);  document.getElementById("pago_"+ii).value=redondear(valor_partidas[ii], 2); if(document.getElementById('partida_iva_'+ii)){document.getElementById("partida_iva_"+ii).value=redondear(valor_partidas[ii], 2);}}
}else if(monto_opcion_pago_input>total2){if(a_aux!= eval(d)){total2 = eval(total2) + eval(0.01);  valor_partidas[ii] = valor_partidas[ii] + eval(0.01);  document.getElementById("pago_"+ii).value=redondear(valor_partidas[ii], 2); if(document.getElementById('partida_iva_'+ii)){document.getElementById("partida_iva_"+ii).value=redondear(valor_partidas[ii], 2);}}
}else{document.getElementById("pago_"+ii).value=redondear(valor_partidas[ii],2);}
}//fin for

var monto_iva_var = 0;
for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){if(document.getElementById('partida_iva_'+ii)){a = valor_partidas[ii]; monto_iva_var = eval(monto_iva_var) + eval(a);}}//fin
var str =  monto_iva_var+'';for(x=0; x<str.length; x++){if(str.charAt(x)=="."){monto_iva_var=str.substr(0,eval(x)+eval(6));break;}}monto_iva_var = redondear(monto_iva_var,2);
document.getElementById('monto_iva').value = monto_iva_var;
document.getElementById('monto_a_pagar_con_iva').value=total2;
cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total2);

}//fin function













function cscp04_ordencompra_autorizacion_pagos_valida_consulta(){



if(verifica_cierre_ano_ejecucion_msj()==false){
		return false;
	}else if(document.getElementById('concepto_anulacion').value == ""){

            fun_msj('Inserte el concepto de anulacion');
			document.getElementById('concepto_anulacion').focus();
			return false;

   	}//fin if

}//fin function





