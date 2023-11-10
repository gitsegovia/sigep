

function cobp01_contratoobras_valuacion_uso_general_iva_valida(){



monto_partidas_sin_iva = 0;
monto_partidas_iva     = 0;

for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
	if(!document.getElementById('partida_iva_'+ii)){
		a = document.getElementById('pago_'+ii).value;str = a;acepto="no";
		for(i=0; i<a.length; i++){if(str.charAt(i)==","){acepto="si";}}
		if(acepto=="si"){for(i=0; i<a.length; i++){str = str.replace('.','');}
		str = str.replace(',','.');}var a = redondear(str,2);
		monto_partidas_sin_iva = eval(monto_partidas_sin_iva) + eval(a);
	}
}


for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
	if(document.getElementById('partida_iva_'+ii)){
		a = document.getElementById('pago_'+ii).value;str = a;acepto="no";
		for(i=0; i<a.length; i++){if(str.charAt(i)==","){acepto="si";}}
		if(acepto=="si"){for(i=0; i<a.length; i++){str = str.replace('.','');}
		str = str.replace(',','.');}var a = redondear(str,2);
		monto_partidas_iva = eval(monto_partidas_iva) + eval(a);
	}
}


monto_partidas_iva      = redondear(monto_partidas_iva , 2);
monto_partidas_sin_iva  = redondear(monto_partidas_sin_iva , 2);


porcentaje_iva_input = document.getElementById("porcentaje_iva").value;
var str = porcentaje_iva_input;for(i=0; i<porcentaje_iva_input.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var porcentaje_iva_input = str;

porcentaje_iva_input = eval(porcentaje_iva_input) / eval(100);

iva_monto =  redondear((eval(monto_partidas_sin_iva) * eval(porcentaje_iva_input)) , 2);


monto_a_pagar_con_iva_valida = document.getElementById("monto_a_pagar_con_iva").value;
var str = monto_a_pagar_con_iva_valida;for(i=0; i<monto_a_pagar_con_iva_valida.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var monto_a_pagar_con_iva_valida = str;


monto_iva_valida = document.getElementById("monto_iva").value;
var str = monto_iva_valida;for(i=0; i<monto_iva_valida.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var monto_iva_valida = str;





monto_compara_a = redondear(eval(iva_monto) + eval(monto_partidas_sin_iva), 2);

monto_partidas_iva = redondear(monto_partidas_iva, 2);

if(eval(monto_iva_valida)==0){

  monto_compara_a = monto_a_pagar_con_iva_valida;

}else{

                 aux_resta_iva = redondear(eval(monto_partidas_iva) - eval(iva_monto), 2);



      if(eval(monto_partidas_iva) > eval(iva_monto)){


		      if(eval(aux_resta_iva)==eval(0.01)  ||  eval(aux_resta_iva)==eval(-0.01)){

                  monto_compara_a = redondear(eval(monto_compara_a) + eval(0.01), 2);

		      }//fin if


      }else if(eval(monto_partidas_iva) < eval(iva_monto)){



              if(eval(aux_resta_iva)==eval(0.01)  ||  eval(aux_resta_iva)==eval(-0.01)){

                  monto_compara_a = redondear(eval(monto_compara_a) - eval(0.01), 2);



		      }//fin if



      }//fin else if

}//fin else




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



     }else if(document.getElementById('porce_retencion_laboral').value == ""){

            fun_msj('Inserte el porcentaje de retenci&oacute;n laboral');
			document.getElementById('porce_retencion_laboral').focus();
			return false;



     }else if(document.getElementById('porce_retencion_fiel_cumplimiento').value == ""){

            fun_msj('Inserte el porcentaje de fiel cumplimiento');
			document.getElementById('porce_retencion_fiel_cumplimiento').focus();
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



	  }else if(document.getElementById('numero_valuacion').value == ""){

            fun_msj('Inserte el n&uacute;mero de valiaci&oacute;n');
			document.getElementById('numero_valuacion').focus();
			return false;


     }else if(document.getElementById('numero_aprobacion').value == ""){

            fun_msj('Inserte el n&uacute;mero de aprobaci&oacute;n');
			document.getElementById('numero_aprobacion').focus();
			return false;


       }else if(document.getElementById('fecha_aprobacion').value == ""){

            fun_msj('Inserte la fecha de aprobaci&oacute;n');
			document.getElementById('fecha_aprobacion').focus();
			return false;


	   }else if(document.getElementById('desde_periodo').value == ""){

            fun_msj('Inserte desde periodo');
			document.getElementById('desde_periodo').focus();
			return false;


       }else if(document.getElementById('hasta_periodo').value == ""){

            fun_msj('Inserte hasta periodo');
			document.getElementById('hasta_periodo').focus();
			return false;



       }else if(document.getElementById('concepto').value == ""){

          fun_msj('Inserte el concepto de la autorizacion de pago');
		  document.getElementById('concepto').focus();
		  return false;


       }else{

                  if(monto_a_pagar_con_iva_valida!=monto_compara_a){

				               mensaje_con_monto_iva_incorrecto(monto_partidas_iva);
						       return false;


		          }else{

		                        document.getElementById('guardar').disabled=true;
		          }//fin else

       }//fin guardar










}//fin functiontr rth























function cobp01_contratoobras_valuacion_uso_general_iva_pregunta_pago_parcial(opc){

        if(opc=="2"){

        porcentaje_iva = document.getElementById('porcentaje_iva').value;
        porcentaje_iva = eval(porcentaje_iva) / eval(100);

        total = 0;
        monto_iva_var = 0;

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
		 var monto_iva_var = redondear(monto_iva_var,2);
//////////////////////////////////////////////////////////////


         document.getElementById('monto_a_pagar_con_iva').value=total;
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

         cobp01_contratoobras_valuacion_uso_general_iva_detalles_del_pago();

   }else if(opc=="1"){


         total = 0;
         for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
            document.getElementById('pago_'+ii).value = "0,00";
            document.getElementById('pago_'+ii).readOnly = false;
            document.getElementById('pago_'+ii).disabled = false;
            }//fin

         document.getElementById('monto_a_pagar_con_iva').value=total;
		 moneda('monto_a_pagar_con_iva');
		 cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total);

         document.getElementById('monto_a_pagar_con_iva').readOnly = true;
		 document.getElementById('monto_a_pagar_con_iva').disabled = false;
		 document.getElementById('monto_iva').readOnly = true;
		 document.getElementById('monto_iva').disabled = false;

		 document.getElementById('monto_iva').value = "0,00";


        cobp01_contratoobras_valuacion_uso_general_iva_detalles_del_pago();

   }//fin
}//fin function




















function cobp01_contratoobras_valuacion_uso_general_iva_monto_id_editar_2(id_i,op_i){


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
monto_iva_var= 0;

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

cobp01_contratoobras_valuacion_uso_general_iva_detalles_del_pago();

 }//fin else
}///fin function


























function cobp01_contratoobras_valuacion_uso_general_iva_colocar_iva(id_i,op_i){


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
        monto_iva_var = 0;



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
		 var monto_iva_var = redondear(monto_iva_var,2);
//////////////////////////////////////////////////////////////



document.getElementById('monto_iva').value = monto_iva_var;
moneda('monto_iva');


cobp01_contratoobras_valuacion_uso_general_iva_detalles_del_pago();

  }//fin else
}//fin function








function cobp01_contratoobras_valuacion_uso_general_iva_detalles_del_pago(){


monto_iva_var_401 = 0;
monto_iva_var = 0;
for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){if(document.getElementById('partida_401_'+ii)){  if(document.getElementById('partida_401_'+ii).value=="si"){a = document.getElementById('pago_'+ii).value; var str = a;
acepto="no";for(i=0; i<a.length; i++){if(str.charAt(i)==","){acepto="si";}}if(acepto=="si"){for(i=0; i<a.length; i++){str = str.replace('.','');}str   = str.replace(',','.'); }var a = redondear(str,2);monto_iva_var_401 = eval(monto_iva_var_401) + eval(a);}}}
var str =  monto_iva_var_401+'';for(x=0; x<str.length; x++){if(str.charAt(x)=="."){ monto_iva_var_401=str.substr(0,eval(x)+eval(6));break;}}var monto_iva_var_401 = redondear(monto_iva_var_401,2);

//monto_iva_var_401 =  eval(monto_iva_var_401) * eval(-1);
//alert(monto_iva_var_401);

for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
if(document.getElementById('partida_iva_'+ii)){a = document.getElementById('pago_'+ii).value;str = a;acepto="no";
for(i=0; i<a.length; i++){if(str.charAt(i)==","){acepto="si";}}
if(acepto=="si"){for(i=0; i<a.length; i++){str = str.replace('.','');}
str = str.replace(',','.');}var a = redondear(str,2);monto_iva_var = eval(monto_iva_var) + eval(a);}}
//monto_iva_var =  eval(monto_iva_var) - eval(monto_iva_var_401);
acepto="no";var str = monto_iva_var;
for(i=0; i<monto_iva_var.length; i++){if(str.charAt(i)==","){acepto="si";}}
if(acepto=="si"){for(i=0; i<monto_iva_var.length; i++){str = str.replace('.','');}str = str.replace(',','.');}var monto_iva_var = redondear(str,2);



porce_retencion_laboral = 0;
porce_retencion_fiel_cumplimiento = 0;
retencion_incluye_iva = 0;
impuesto_sobre_la_renta = 0;
timbre_fiscal = 0;
impuesto_municipal = 0;


porce_retencion_laboral = document.getElementById("porce_retencion_laboral").value;
var str = porce_retencion_laboral;for(i=0; i<porce_retencion_laboral.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var porce_retencion_laboral = str;

porce_retencion_fiel_cumplimiento = document.getElementById("porce_retencion_fiel_cumplimiento").value;
var str = porce_retencion_fiel_cumplimiento;for(i=0; i<porce_retencion_fiel_cumplimiento.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var porce_retencion_fiel_cumplimiento = str;

retencion_incluye_iva = document.getElementById("retencion_incluye_iva").value;
var str = retencion_incluye_iva;for(i=0; i<retencion_incluye_iva.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var retencion_incluye_iva = str;

impuesto_sobre_la_renta = document.getElementById("impuesto_sobre_la_renta").value;
var str = impuesto_sobre_la_renta;for(i=0; i<impuesto_sobre_la_renta.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var impuesto_sobre_la_renta = str;

timbre_fiscal = document.getElementById("timbre_fiscal").value;
var str = timbre_fiscal;for(i=0; i<timbre_fiscal.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var timbre_fiscal = str;

impuesto_municipal = document.getElementById("impuesto_municipal").value;
var str = impuesto_municipal;for(i=0; i<impuesto_municipal.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var impuesto_municipal = str;

amortizacion_del_anticipo = document.getElementById("amortizacion_del_anticipo").value;
var str = amortizacion_del_anticipo;for(i=0; i<amortizacion_del_anticipo.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var amortizacion_del_anticipo = str;

porcentaje_iva = document.getElementById("porcentaje_iva").value;
var str = porcentaje_iva;for(i=0; i<porcentaje_iva.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var porcentaje_iva = str;





if(porce_retencion_laboral!=0){document.getElementById('monto_laboral').readOnly=false; }else{document.getElementById('monto_laboral').readOnly=true; }
if(porce_retencion_fiel_cumplimiento!=0){document.getElementById('monto_fiel_cumplimiento').readOnly=false; }else{document.getElementById('monto_fiel_cumplimiento').readOnly=true; }
if(retencion_incluye_iva!=0){document.getElementById('retencion_incluye_iva_monto_iva').readOnly=false; }else{document.getElementById('retencion_incluye_iva_monto_iva').readOnly=true; }
if(impuesto_sobre_la_renta!=0){document.getElementById('impuesto_sobre_la_renta_monto_iva').readOnly=false; }else{document.getElementById('impuesto_sobre_la_renta_monto_iva').readOnly=true; }
if(timbre_fiscal!=0){document.getElementById('timbre_fiscal_monto_iva').readOnly=false; }else{document.getElementById('timbre_fiscal_monto_iva').readOnly=true; }
if(impuesto_municipal!=0){document.getElementById('impuesto_municipal_monto_iva').readOnly=false; }else{document.getElementById('impuesto_municipal_monto_iva').readOnly=true; }

if(amortizacion_del_anticipo!=0){document.getElementById('amortizacion_del_anticipo_monto_iva').readOnly=false; }else{document.getElementById('amortizacion_del_anticipo_monto_iva').readOnly=true; }

if(porce_retencion_laboral!=0 || porce_retencion_fiel_cumplimiento!=0 || porcentaje_iva!=0 || amortizacion_del_anticipo!=0){

   document.getElementById('total_retencion_monto_iva').readOnly=false;

}else{

   document.getElementById('total_retencion_monto_iva').readOnly=true;

    }//fin else




if(eval(monto_iva_var)==0){

  document.getElementById("porcentaje_iva").value = 0;

}


  document.getElementById('monto_sin_iva').disabled = false;
  document.getElementById('porcentaje_iva').disabled = false;
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
  document.getElementById('porce_retencion_fiel_cumplimiento').disabled = false;
  document.getElementById('porce_retencion_laboral').disabled = false;
  document.getElementById("monto_fiel_cumplimiento").disabled = false;
  document.getElementById("monto_laboral").disabled = false;

   document.getElementById('monto_a_pagar_con_iva').readOnly = true;
   document.getElementById('monto_iva').readOnly = true;







document.getElementById('retencion_multa_monto').disabled = false;
document.getElementById('retencion_responsabilidad_social').disabled = false;

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





//moneda('monto_a_pagar_con_iva');
//moneda('monto_iva');

monto_a_pagar_con_iva = 0;



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




if(monto_iva_var=='0.00'){
document.getElementById('retencion_incluye_iva').options[0].text =  '0';
document.getElementById('retencion_incluye_iva').options[0].value = '0';
document.getElementById('retencion_incluye_iva').options[0].selected = true;

 for (var i = 1; i < document.getElementById('retencion_incluye_iva').length; i++){
  document.getElementById('retencion_incluye_iva').options[i].text =  '';
  document.getElementById('retencion_incluye_iva').options[i].value = '0';
  ii++;
 }//fin for
}else{


  document.getElementById('retencion_incluye_iva').options[0].text =  '0';
  document.getElementById('retencion_incluye_iva').options[0].value = '0';

  document.getElementById('retencion_incluye_iva').options[1].text =  '75';
  document.getElementById('retencion_incluye_iva').options[1].value = '75';

  document.getElementById('retencion_incluye_iva').options[2].text =  '100';
  document.getElementById('retencion_incluye_iva').options[2].value = '100';

}//fin else


acepto="no";
sustraendo = document.getElementById("sustraendo").value;
var str = sustraendo;
for(i=0; i<sustraendo.length; i++){
   if(str.charAt(i)==","){acepto="si";}
}//fin for
if(acepto=="si"){
  for(i=0; i<sustraendo.length; i++){str = str.replace('.','');}//fin for
  str = str.replace(',','.');
}//fin
var sustraendo = redondear(str,2);





monto_sin_iva = redondear( eval(monto_a_pagar_con_iva)  -  eval(monto_iva_var) , 2);



porce_retencion_laboral_input = document.getElementById("porce_retencion_laboral").value;
var str = porce_retencion_laboral_input;for(i=0; i<porce_retencion_laboral_input.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var porce_retencion_laboral_input = str;
//////////////// RETENCION LABORAL //////////////////////////
if(document.getElementById('anticipo_con_iva2').value=="1"){monto_laboral = redondear((eval(monto_a_pagar_con_iva) *  (eval(porce_retencion_laboral_input) / eval(100))  ), 2 );}else{monto_laboral = redondear(eval(monto_sin_iva) *  (eval(porce_retencion_laboral_input) / eval(100)), 2);}document.getElementById('monto_laboral').value = monto_laboral;moneda('monto_laboral');
////////////////////////////////////////////////////////////////



porce_retencion_fiel_cumplimiento_input = document.getElementById("porce_retencion_fiel_cumplimiento").value;
var str = porce_retencion_fiel_cumplimiento_input;for(i=0; i<porce_retencion_fiel_cumplimiento_input.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var porce_retencion_fiel_cumplimiento_input = str;
///////////// RETENCION FIEL CUMPLIMIENTO ///////////////
if(document.getElementById('anticipo_con_iva2').value=="1"){monto_fiel_cumplimiento = redondear((eval(monto_a_pagar_con_iva) *  (eval(porce_retencion_fiel_cumplimiento_input) / eval(100)) ), 2 );}else{monto_fiel_cumplimiento = redondear(eval(monto_sin_iva) *  (eval(porce_retencion_fiel_cumplimiento_input) / eval(100)), 2);}document.getElementById('monto_fiel_cumplimiento').value = monto_fiel_cumplimiento;moneda('monto_fiel_cumplimiento');
////////////////////////////////////////////////////////////////

///////////// amortizacion_del_anticipo_monto_iva ///////////////
//if(document.getElementById('anticipo_con_iva').value=="1"){amortizacion_del_anticipo_monto_iva = redondear( eval(monto_a_pagar_con_iva) *  (eval(document.getElementById("amortizacion_del_anticipo").value) / eval(100)), 2);}else{amortizacion_del_anticipo_monto_iva = redondear( eval(monto_sin_iva) *  (eval(document.getElementById("amortizacion_del_anticipo").value) / eval(100)), 2);}document.getElementById('amortizacion_del_anticipo_monto_iva').value = amortizacion_del_anticipo_monto_iva;moneda('amortizacion_del_anticipo_monto_iva');
////////////////////////////////////////////////////////////////


porcentaje_islr_input = document.getElementById("impuesto_sobre_la_renta").value;
var str = porcentaje_islr_input;for(i=0; i<porcentaje_islr_input.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var porcentaje_islr_input = str;



porcentaje_impuesto_sobre_la_renta =  eval(porcentaje_islr_input);
if(document.getElementById("objeto_rif").value=="4"){ sustraendo_neto = document.getElementById("sustraendo_neto").value; sustraendo = eval(sustraendo_neto) * eval(porcentaje_impuesto_sobre_la_renta);}//fin else

if(porcentaje_impuesto_sobre_la_renta=="3" && sustraendo!=0 && sustraendo_neto==38.33){sustraendo=115;}
if(porcentaje_impuesto_sobre_la_renta=="3" && sustraendo!=0 && sustraendo_neto==45.83){sustraendo=137.50;}
if(porcentaje_impuesto_sobre_la_renta=="3" && sustraendo!=0 && sustraendo_neto==54.16){sustraendo=162.50;}
if(porcentaje_impuesto_sobre_la_renta=="3" && sustraendo!=0 && sustraendo_neto==63.33){sustraendo=190.00;}

impuesto_sobre_la_renta = ((eval(monto_sin_iva) *  eval(porcentaje_impuesto_sobre_la_renta)) /100)   -   eval(sustraendo);
document.getElementById("sustraendo").value = redondear(sustraendo, 2);
moneda('sustraendo');
//////////////////////////////////////////////////////////////
var str =  impuesto_sobre_la_renta+''; for(x=0; x<str.length; x++){if(str.charAt(x)=="."){ impuesto_sobre_la_renta=str.substr(0,eval(x)+eval(6));break;}}var impuesto_sobre_la_renta = redondear(impuesto_sobre_la_renta,2);
//////////////////////////////////////////////////////////////


porcentaje_timbre_input = document.getElementById("timbre_fiscal").value;
var str = porcentaje_timbre_input;for(i=0; i<porcentaje_timbre_input.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var porcentaje_timbre_input = str;

porcentaje_amortizacion_input = document.getElementById("amortizacion_del_anticipo").value;
//var str = porcentaje_amortizacion_input;for(i=0; i<porcentaje_amortizacion_input.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var porcentaje_amortizacion_input = str;

acepto="no";var str = porcentaje_amortizacion_input;
for(i=0; i<porcentaje_amortizacion_input.length; i++){if(str.charAt(i)==","){acepto="si";}}
if(acepto=="si"){for(i=0; i<porcentaje_amortizacion_input.length; i++){str = str.replace('.','');}str = str.replace(',','.');}var porcentaje_amortizacion_input = redondear(str,2);




porcentaje_impuesto_muni_input = document.getElementById("impuesto_municipal").value;
var str = porcentaje_impuesto_muni_input;for(i=0; i<porcentaje_impuesto_muni_input.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var porcentaje_impuesto_muni_input = str;


porcentaje_iva_input = document.getElementById("porcentaje_iva").value;
var str = porcentaje_iva_input;for(i=0; i<porcentaje_iva_input.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var porcentaje_iva_input = str;




if(document.getElementById('anticipo_con_iva2').value=="1"  && (porce_retencion_laboral_input!=0 || porce_retencion_fiel_cumplimiento_input!=0)){
monto_laboral_iva           =  redondear(eval(monto_iva_var)  *  (eval(porce_retencion_laboral_input) / eval(100)),2);
monto_fiel_cumplimiento_iva =  redondear(eval(monto_iva_var)  *  (eval(porce_retencion_fiel_cumplimiento_input) / eval(100)),2);
monto_iva_var               =   eval(monto_iva_var)  - (eval(monto_laboral_iva) + eval(monto_fiel_cumplimiento_iva));
}//fin if





///////////// amortizacion_del_anticipo_monto_iva ///////////////
acepto="no";saldo_orden = document.getElementById("saldo_contrato").value;var str = saldo_orden;
for(i=0; i<saldo_orden.length; i++){if(str.charAt(i)==","){acepto="si";}}//fin for
if(acepto=="si"){  for(i=0; i<saldo_orden.length; i++){str = str.replace('.','');}str = str.replace(',','.');}//fin
var saldo_orden = redondear(str,2);
nuevo_monto =  redondear(eval(saldo_orden) - eval(monto_a_pagar_con_iva), 2);
document.getElementById('nuevo_monto_pagar').value = redondear(eval(saldo_orden) - eval(monto_a_pagar_con_iva), 2);
moneda('nuevo_monto_pagar');
if(nuevo_monto=="0"){saldo_anticipo = document.getElementById('saldo_anticipo').value;document.getElementById('amortizacion_del_anticipo_monto_iva').value = saldo_anticipo;
amortizacion_del_anticipo_monto_iva = saldo_anticipo;acepto="no";var str = amortizacion_del_anticipo_monto_iva;
for(i=0; i<amortizacion_del_anticipo_monto_iva.length; i++){if(str.charAt(i)==","){acepto="si";}}//fin for
if(acepto=="si"){for(i=0; i<amortizacion_del_anticipo_monto_iva.length; i++){str = str.replace('.','');}str = str.replace(',','.');}//fin
var amortizacion_del_anticipo_monto_iva = redondear(str,2);
}else{if(document.getElementById('anticipo_con_iva').value=="1"){amortizacion_del_anticipo_monto_iva = redondear( eval(monto_a_pagar_con_iva) *  (eval(porcentaje_amortizacion_input) / eval(100)), 2);
}else{amortizacion_del_anticipo_monto_iva = redondear((eval(monto_sin_iva) *  (eval(porcentaje_amortizacion_input) / eval(100))   ), 2);}//fin else
document.getElementById('amortizacion_del_anticipo_monto_iva').value = amortizacion_del_anticipo_monto_iva;moneda('amortizacion_del_anticipo_monto_iva');}//fin else
////////////////////////////////////////////////////////////////



//document.getElementById("amortizacion_del_anticipo").readOnly=false;

total_retencion  = 0;
total_retencion2 = 0;

if(document.getElementById('anticipo_con_iva2').value=="1"  && (porce_retencion_laboral_input!=0 || porce_retencion_fiel_cumplimiento_input!=0)){

    total_retencion  =  redondear((eval(monto_a_pagar_con_iva)  - (eval(monto_fiel_cumplimiento) + eval(monto_laboral) + eval(monto_iva_var_401)   + eval(amortizacion_del_anticipo_monto_iva)  )),2);
	total_retencion2 =  redondear((eval(monto_a_pagar_con_iva)  - (eval(monto_fiel_cumplimiento) + eval(monto_laboral) + eval(monto_iva_var_401)   + eval(amortizacion_del_anticipo_monto_iva)  )),2);
	total_retencion  =  redondear(eval(total_retencion) /  (eval(1) + (eval(porcentaje_iva_input) /eval(100)))  ,2) ;
	total_retencion2 =            eval(total_retencion2) /  (eval(1) + (eval(porcentaje_iva_input) /eval(100)));
    monto_iva_var    =  (eval(total_retencion2) * (eval(porcentaje_iva_input)/eval(100)));



}else{

	total_retencion =  (eval(monto_a_pagar_con_iva)  - (eval(monto_iva_var) + eval(monto_fiel_cumplimiento) + eval(monto_laboral) + eval(monto_iva_var_401)  + eval(amortizacion_del_anticipo_monto_iva) ));
	//total_retencion2 =  eval(monto_a_pagar_con_iva)  - (eval(monto_iva_var) + eval(monto_fiel_cumplimiento) + eval(monto_laboral));
}



//if(document.getElementById('anticipo_con_iva').value=="1"){monto_iva_var = eval(monto_iva_var) - eval(amortizacion_del_anticipo_monto_iva);}

document.getElementById("monto_iva").value = redondear(monto_iva_var, 2);
moneda('monto_a_pagar_con_iva');
moneda('monto_iva');
document.getElementById('total_retencion_monto_iva').value = redondear(total_retencion, 2);
moneda('total_retencion_monto_iva');







if(total_retencion==0){sustraendo=0;}
porcentaje_impuesto_sobre_la_renta =  eval(porcentaje_islr_input) / eval(100);
impuesto_sobre_la_renta = ((eval(total_retencion) *  eval(porcentaje_impuesto_sobre_la_renta))   -   eval(sustraendo));


document.getElementById('monto_sin_iva').value = monto_sin_iva;moneda('monto_sin_iva');


document.getElementById('retencion_incluye_iva_monto_iva').value =  redondear(( eval(monto_iva_var) *  (eval(document.getElementById("retencion_incluye_iva").value) / eval(100)) ) , 2);moneda('retencion_incluye_iva_monto_iva');
document.getElementById('impuesto_sobre_la_renta_monto_iva').value = redondear(impuesto_sobre_la_renta  , 2);moneda('impuesto_sobre_la_renta_monto_iva');
document.getElementById('timbre_fiscal_monto_iva').value =  redondear( (eval(total_retencion) / eval(1000)) *  (eval(porcentaje_timbre_input)) , 2);moneda('timbre_fiscal_monto_iva');
document.getElementById('impuesto_municipal_monto_iva').value = redondear( (eval(total_retencion) *  (eval(porcentaje_impuesto_muni_input) / eval(100))   ), 2);moneda('impuesto_municipal_monto_iva');




impuesto_sobre_la_renta = redondear(impuesto_sobre_la_renta, 2);
retencion_incluye_iva_monto_iva =  redondear(( eval(monto_iva_var) *  (eval(document.getElementById("retencion_incluye_iva").value) / eval(100)) ) , 2);
timbre_fiscal_monto_iva =  redondear((eval(total_retencion) / eval(1000)) *  (eval(porcentaje_timbre_input))  , 2);
impuesto_municipal_monto_iva = redondear( eval(total_retencion) *  (eval(porcentaje_impuesto_muni_input) / eval(100)), 2);
if(impuesto_sobre_la_renta<0){ impuesto_sobre_la_renta = eval(impuesto_sobre_la_renta) * eval(-1);}//fin if




total_retenten = eval(impuesto_sobre_la_renta) +  eval(retencion_incluye_iva_monto_iva)  + eval(timbre_fiscal_monto_iva)  + eval(impuesto_municipal_monto_iva) + eval(retener_multa_y_responsabilidad);
document.getElementById('monto_orden_de_pago_monto_iva').value = redondear(eval(monto_a_pagar_con_iva) - eval(amortizacion_del_anticipo_monto_iva) -  eval(monto_fiel_cumplimiento) -  eval(monto_laboral), 2);
moneda('monto_orden_de_pago_monto_iva');
monto_orde = redondear(eval(monto_a_pagar_con_iva) - eval(amortizacion_del_anticipo_monto_iva) -  eval(monto_fiel_cumplimiento) -  eval(monto_laboral), 2);
document.getElementById('monto_a_pagar_monto_iva').value = redondear(eval(monto_orde) - eval(total_retenten), 2);


moneda('monto_a_pagar_monto_iva');

moneda('retencion_multa_monto');
moneda('retencion_responsabilidad_social');


}//fin function
















function cobp01_contratoobras_valuacion_uso_general_iva_respuesta_pago_parcial(opcion){

total = 0;
var valor_partidas = new Array();

                       if(opcion=="1"){/////////////////////////////////////////////////OPCION I/////////////////////////////////////

monto_opcion_pago = document.getElementById('monto_opcion_pago').value;var str = monto_opcion_pago;for(i=0; i<monto_opcion_pago.length; i++){str = str.replace('.','');}str = str.replace(',','.');var monto_opcion_pago = redondear(str,2);


for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
   document.getElementById('pago_'+ii).value = document.getElementById('monto_actual_'+ii).value;
    a = document.getElementById('monto_actual_'+ii).value;var str = a;
    for(i=0; i<a.length; i++){str = str.replace('.','');}str   = str.replace(',','.');
    var a = redondear(str,2);total =  eval(total) + eval(a);
    document.getElementById('pago_'+ii).readOnly = false;
    document.getElementById('pago_'+ii).disabled = false;
}//fin


var str =  total+'';for(x=0; x<str.length; x++){if(str.charAt(x)=="."){total=str.substr(0,eval(x)+eval(6));break;}}var total = redondear(total,2);
if(monto_opcion_pago>total){fun_msj('La asignaci&oacute;n parcial es mayor al total de la orden');}else{
total2 = 0;
monto_iva_var = 0;

for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
  a = document.getElementById('monto_actual_'+ii).value; var str = a;
  for(i=0; i<a.length; i++){str = str.replace('.','');} str  = str.replace(',','.');var a = redondear(str,2);
  resul = ((eval(monto_opcion_pago) * eval(a)) / eval(total) );
  total2 =  eval(total2) + eval(resul);  valor_partidas[ii]= redondear(resul, 2);
  document.getElementById('pago_'+ii).readOnly = false;
    if(document.getElementById('partida_iva_'+ii)){
      a = resul;monto_iva_var = eval(monto_iva_var) + eval(a);
      document.getElementById('partida_iva_'+ii).readOnly = true;
      document.getElementById('partida_iva_'+ii).disabled = false;
     }
}

var str =  total2+'';for(x=0; x<str.length; x++){if(str.charAt(x)=="."){total2=str.substr(0,eval(x)+eval(6));break;}} var total2 = redondear(total2, 2);
aux = 0;
document.getElementById('monto_iva').disabled = false;document.getElementById('monto_iva').value=monto_iva_var;
document.getElementById('monto_a_pagar_con_iva').disabled = false;document.getElementById('monto_a_pagar_con_iva').value=monto_opcion_pago;
//detalles_del_pago();
/////////////////////////////////////////////// DIFERENCIA INICIO ////////////////////////////////////////////////////////
aa = document.getElementById("monto_opcion_pago").value;
var str = aa;for(i=0; i<aa.length; i++){str = str.replace('.','');}str = str.replace(',','.');var aa = redondear(str,2);
monto_opcion_pago_input = aa;aux=0;aux = eval(monto_opcion_pago_input) - eval(total2);
var str =  aux+'';for(x=0; x<str.length; x++){if(str.charAt(x)=="."){aux=str.substr(0,eval(x)+eval(6));break;}}var aux = redondear(aux, 2);var aux = eval(aux);
for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){var str =  valor_partidas[ii]+'';for(x=0; x<str.length; x++){if(str.charAt(x)=="."){str=str.substr(0,eval(x)+eval(6));break;}}valor_partidas[ii] = redondear(str, 2);valor_partidas[ii] = eval(valor_partidas[ii]);}//fin for
if(monto_opcion_pago_input!=total2){rendondear_decimal_contratos_2(monto_opcion_pago_input,total2, valor_partidas);}else{for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){document.getElementById("pago_"+ii).value=valor_partidas[ii]; cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total2);}}//fin else
/////////////////////////////////////////////// DIFERENCIA FIN /////////////////////////////////////////////////////////
}//fin else

cobp01_contratoobras_valuacion_uso_general_iva_detalles_del_pago();for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){moneda("pago_"+ii);}







}else if(opcion=="2"){}//fin else




}//fin function















function rendondear_decimal_contratos_2(monto_opcion_pago_input, total2, valor_partidas){


//monto_opcion_pago_input   :::::::::::  es el monto de la valuacion

j = 0;///////////////


monto_a_pagar_con_iva = 0;monto_iva_var = 0;acepto="no";monto_a_pagar_con_iva = document.getElementById("monto_a_pagar_con_iva").value;
var str = monto_a_pagar_con_iva;for(i=0; i<monto_a_pagar_con_iva.length; i++){if(str.charAt(i)==","){acepto="si";}}
if(acepto=="si"){for(i=0; i<monto_a_pagar_con_iva.length; i++){str = str.replace('.','');} str = str.replace(',','.');}
var monto_a_pagar_con_iva = redondear(str,2);acepto="no";monto_iva_var = document.getElementById("monto_iva").value;var str = monto_iva_var;for(i=0; i<monto_iva_var.length; i++){if(str.charAt(i)==","){acepto="si";}}
if(acepto=="si"){for(i=0; i<monto_iva_var.length; i++){str = str.replace('.','');}str = str.replace(',','.');}var monto_iva_var = redondear(str,2);
saldo_orden = document.getElementById("saldo_contrato").value;var str = saldo_orden;
for(i=0; i<saldo_orden.length; i++){if(str.charAt(i)==","){acepto="si";}}if(acepto=="si"){for(i=0; i<saldo_orden.length; i++){str = str.replace('.','');}str = str.replace(',','.');}
var saldo_orden = redondear(str,2);
nuevo_monto =  redondear(eval(saldo_orden) - eval(monto_a_pagar_con_iva), 2);
saldo_actual_orden= redondear(eval(saldo_orden) - eval(monto_a_pagar_con_iva), 2);
for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
 a = document.getElementById('monto_actual_'+ii).value; var str = a;for(i=0; i<a.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var a = redondear(str,2);
 c = document.getElementById('monto_partida_anteriores_'+ii).value; var str = c;for(i=0; i<c.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var c = redondear(str,2);
 d = document.getElementById('monto_total_partidas_'+ii).value; var str = d;for(i=0; i<d.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var d = redondear(str,2);

   aux = eval(monto_opcion_pago_input) - eval(total2);
   var str =  valor_partidas[ii]+'';for(x=0; x<str.length; x++){if(str.charAt(x)=="."){str=str.substr(0,eval(x)+eval(6));break;}}valor_partidas[ii] = redondear(str, 2);valor_partidas[ii] = eval(valor_partidas[ii]);

 a_aux = eval(c) + eval(valor_partidas[ii]);

      if(saldo_actual_orden==0 && valor_partidas[ii]>a){ j = eval(valor_partidas[ii]) - eval(a);  valor_partidas[ii] = valor_partidas[ii] - eval(j);  total2 = eval(total2) - eval(j);}//fin fin
      if(saldo_actual_orden==0 && valor_partidas[ii]<a){ j = eval(a) - eval(valor_partidas[ii]);  valor_partidas[ii] = valor_partidas[ii] + eval(j);  total2 = eval(total2) + eval(j);}//fin fin

//alert(monto_opcion_pago_input+'----'+total2+'------'+a_aux+'----'+d+'---'+valor_partidas[ii]);
      if(monto_opcion_pago_input<total2){if(a_aux!= eval(d)){total2 = eval(total2) - eval(0.01);  valor_partidas[ii] = valor_partidas[ii] - eval(0.01);  var str =  valor_partidas[ii]+'';for(x=0; x<str.length; x++){if(str.charAt(x)=="."){valor_partidas[ii]=str.substr(0,eval(x)+eval(6)); break;}}valor_partidas[ii] = redondear(valor_partidas[ii],2);  document.getElementById("pago_"+ii).value=redondear(valor_partidas[ii],2); if(document.getElementById('partida_iva_'+ii)){document.getElementById("partida_iva_"+ii).value=redondear(valor_partidas[ii],2); }}
}else if(monto_opcion_pago_input>total2){if(a_aux!= eval(d)){total2 = eval(total2) + eval(0.01);  valor_partidas[ii] = valor_partidas[ii] + eval(0.01);  var str =  valor_partidas[ii]+'';for(x=0; x<str.length; x++){if(str.charAt(x)=="."){valor_partidas[ii]=str.substr(0,eval(x)+eval(6)); break;}}valor_partidas[ii] = redondear(valor_partidas[ii],2);  document.getElementById("pago_"+ii).value=redondear(valor_partidas[ii],2); if(document.getElementById('partida_iva_'+ii)){document.getElementById("partida_iva_"+ii).value=redondear(valor_partidas[ii],2); }}
}else{document.getElementById("pago_"+ii).value=redondear(valor_partidas[ii],2);}
}//fin for

monto_iva_var = 0;
for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
  if(document.getElementById('partida_iva_'+ii)){a = valor_partidas[ii]; monto_iva_var = eval(monto_iva_var) + eval(a);}}//fin
var str =  monto_iva_var+'';for(x=0; x<str.length; x++){if(str.charAt(x)=="."){monto_iva_var=str.substr(0,eval(x)+eval(6));break;}}var monto_iva_var = redondear(monto_iva_var,2);
document.getElementById('monto_iva').value = monto_iva_var;
document.getElementById('monto_a_pagar_con_iva').value=total2;
cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total2);

}//fin function




















function cobp01_contratoobras_valuacion_uso_general_iva_valida_consulta(){



  if(document.getElementById('concepto_anulacion').value == ""){

            fun_msj('Inserte el concepto de anulaci&oacute;n');
			document.getElementById('concepto_anulacion').focus();
			return false;

   }else{

            document.getElementById('guardar').disabled=true;


   }//fin else





}//fin funciton
























function cobp01_contratoobras_valuacion_uso_general_iva_detalles_del_pago_personalizado(){


monto_retencion_laboral_input = document.getElementById("monto_laboral").value;
var str = monto_retencion_laboral_input;for(i=0; i<monto_retencion_laboral_input.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var monto_retencion_laboral_input = str;


monto_fiel_cumplimiento = document.getElementById("monto_fiel_cumplimiento").value;
var str = monto_fiel_cumplimiento;for(i=0; i<monto_fiel_cumplimiento.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var monto_fiel_cumplimiento = str;


monto_iva_var = document.getElementById("monto_iva").value;
var str = monto_iva_var;for(i=0; i<monto_iva_var.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var monto_iva_var = str;


monto_a_pagar_con_iva = document.getElementById("monto_a_pagar_con_iva").value;
var str = monto_a_pagar_con_iva;for(i=0; i<monto_a_pagar_con_iva.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var monto_a_pagar_con_iva = str;


porcentaje_iva_input = document.getElementById("porcentaje_iva").value;
var str = porcentaje_iva_input;for(i=0; i<porcentaje_iva_input.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var porcentaje_iva_input = str;


monto_iva_var_401 = 0;
for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){if(document.getElementById('partida_401_'+ii)){  if(document.getElementById('partida_401_'+ii).value=="si"){a = document.getElementById('pago_'+ii).value; var str = a;
acepto="no";for(i=0; i<a.length; i++){if(str.charAt(i)==","){acepto="si";}}if(acepto=="si"){for(i=0; i<a.length; i++){str = str.replace('.','');}str   = str.replace(',','.'); }var a = redondear(str,2);monto_iva_var_401 = eval(monto_iva_var_401) + eval(a);}}}
var str =  monto_iva_var_401+'';for(x=0; x<str.length; x++){if(str.charAt(x)=="."){ monto_iva_var_401=str.substr(0,eval(x)+eval(6));break;}}var monto_iva_var_401 = redondear(monto_iva_var_401,2);



amortizacion_del_anticipo_monto_iva = document.getElementById("amortizacion_del_anticipo_monto_iva").value;
var str = amortizacion_del_anticipo_monto_iva;for(i=0; i<amortizacion_del_anticipo_monto_iva.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var amortizacion_del_anticipo_monto_iva = str;


impuesto_sobre_la_renta = document.getElementById("impuesto_sobre_la_renta_monto_iva").value;
var str = impuesto_sobre_la_renta;for(i=0; i<impuesto_sobre_la_renta.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var impuesto_sobre_la_renta = str;


retencion_incluye_iva_monto_iva = document.getElementById("retencion_incluye_iva_monto_iva").value;
var str = retencion_incluye_iva_monto_iva;for(i=0; i<retencion_incluye_iva_monto_iva.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var retencion_incluye_iva_monto_iva = str;


timbre_fiscal_monto_iva = document.getElementById("timbre_fiscal_monto_iva").value;
var str = timbre_fiscal_monto_iva;for(i=0; i<timbre_fiscal_monto_iva.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var timbre_fiscal_monto_iva = str;


impuesto_municipal_monto_iva = document.getElementById("impuesto_municipal_monto_iva").value;
var str = impuesto_municipal_monto_iva;for(i=0; i<impuesto_municipal_monto_iva.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var impuesto_municipal_monto_iva = str;


saldo_orden = document.getElementById("saldo_contrato").value;
var str = saldo_orden;for(i=0; i<saldo_orden.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var saldo_orden = str;



total_retencion  = 0;
total_retencion2 = 0;

if(document.getElementById('anticipo_con_iva2').value=="1"  && (monto_retencion_laboral_input!=0 || monto_fiel_cumplimiento!=0)){
	total_retencion =  (eval(monto_a_pagar_con_iva)  - (eval(monto_fiel_cumplimiento) + eval(monto_retencion_laboral_input) + eval(monto_iva_var_401)  + eval(amortizacion_del_anticipo_monto_iva)   ))   ;
	total_retencion =  eval(total_retencion) /  (eval(1) + (eval(porcentaje_iva_input) /eval(100))) ;
    monto_iva_var_B =  eval(total_retencion) * (eval(porcentaje_iva_input) /eval(100));

monto_iva_var = monto_iva_var_B;

}else{

	total_retencion =  (eval(monto_a_pagar_con_iva)  - (eval(monto_iva_var) + eval(monto_fiel_cumplimiento) + eval(monto_retencion_laboral_input) + eval(monto_iva_var_401)  + eval(amortizacion_del_anticipo_monto_iva)  ));
	//total_retencion2 =  eval(monto_a_pagar_con_iva)  - (eval(monto_iva_var) + eval(monto_fiel_cumplimiento) + eval(monto_laboral));
}



monto_orde = redondear(eval(monto_a_pagar_con_iva) - eval(amortizacion_del_anticipo_monto_iva) -  eval(monto_fiel_cumplimiento) -  eval(monto_retencion_laboral_input), 2);
total_retenten = eval(impuesto_sobre_la_renta) +  eval(retencion_incluye_iva_monto_iva)  + eval(timbre_fiscal_monto_iva)  + eval(impuesto_municipal_monto_iva);



document.getElementById("monto_iva").value = redondear(monto_iva_var, 2);
document.getElementById('total_retencion_monto_iva').value = redondear(total_retencion, 2);
document.getElementById('monto_orden_de_pago_monto_iva').value = redondear(eval(monto_a_pagar_con_iva) - eval(amortizacion_del_anticipo_monto_iva) -  eval(monto_fiel_cumplimiento) -  eval(monto_retencion_laboral_input), 2);
document.getElementById('monto_a_pagar_monto_iva').value = redondear(eval(monto_orde) - eval(total_retenten), 2);
document.getElementById('nuevo_monto_pagar').value = redondear(eval(saldo_orden) - eval(monto_a_pagar_con_iva), 2);


moneda('nuevo_monto_pagar');
moneda('total_retencion_monto_iva');
moneda('monto_a_pagar_monto_iva');
moneda('monto_orden_de_pago_monto_iva');
moneda('monto_a_pagar_con_iva');
moneda('monto_iva');
moneda('total_retencion_monto_iva');

}//fin function














function cobp01_contratoobras_valuacion_uso_general_iva_detalles_del_pago_personalizado_monto_descontar_impuesto(){


monto_retencion_laboral_input = document.getElementById("monto_laboral").value;
var str = monto_retencion_laboral_input;for(i=0; i<monto_retencion_laboral_input.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var monto_retencion_laboral_input = str;


monto_fiel_cumplimiento = document.getElementById("monto_fiel_cumplimiento").value;
var str = monto_fiel_cumplimiento;for(i=0; i<monto_fiel_cumplimiento.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var monto_fiel_cumplimiento = str;


monto_iva_var = document.getElementById("monto_iva").value;
var str = monto_iva_var;for(i=0; i<monto_iva_var.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var monto_iva_var = str;


monto_a_pagar_con_iva = document.getElementById("monto_a_pagar_con_iva").value;
var str = monto_a_pagar_con_iva;for(i=0; i<monto_a_pagar_con_iva.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var monto_a_pagar_con_iva = str;


porcentaje_iva_input = document.getElementById("porcentaje_iva").value;
var str = porcentaje_iva_input;for(i=0; i<porcentaje_iva_input.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var porcentaje_iva_input = str;


monto_iva_var_401 = 0;
for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){if(document.getElementById('partida_401_'+ii)){  if(document.getElementById('partida_401_'+ii).value=="si"){a = document.getElementById('pago_'+ii).value; var str = a;
acepto="no";for(i=0; i<a.length; i++){if(str.charAt(i)==","){acepto="si";}}if(acepto=="si"){for(i=0; i<a.length; i++){str = str.replace('.','');}str   = str.replace(',','.'); }var a = redondear(str,2);monto_iva_var_401 = eval(monto_iva_var_401) + eval(a);}}}
var str =  monto_iva_var_401+'';for(x=0; x<str.length; x++){if(str.charAt(x)=="."){ monto_iva_var_401=str.substr(0,eval(x)+eval(6));break;}}var monto_iva_var_401 = redondear(monto_iva_var_401,2);



amortizacion_del_anticipo_monto_iva = document.getElementById("amortizacion_del_anticipo_monto_iva").value;
var str = amortizacion_del_anticipo_monto_iva;for(i=0; i<amortizacion_del_anticipo_monto_iva.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var amortizacion_del_anticipo_monto_iva = str;


impuesto_sobre_la_renta = document.getElementById("impuesto_sobre_la_renta_monto_iva").value;
var str = impuesto_sobre_la_renta;for(i=0; i<impuesto_sobre_la_renta.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var impuesto_sobre_la_renta = str;


retencion_incluye_iva_monto_iva = document.getElementById("retencion_incluye_iva_monto_iva").value;
var str = retencion_incluye_iva_monto_iva;for(i=0; i<retencion_incluye_iva_monto_iva.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var retencion_incluye_iva_monto_iva = str;


timbre_fiscal_monto_iva = document.getElementById("timbre_fiscal_monto_iva").value;
var str = timbre_fiscal_monto_iva;for(i=0; i<timbre_fiscal_monto_iva.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var timbre_fiscal_monto_iva = str;


impuesto_municipal_monto_iva = document.getElementById("impuesto_municipal_monto_iva").value;
var str = impuesto_municipal_monto_iva;for(i=0; i<impuesto_municipal_monto_iva.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var impuesto_municipal_monto_iva = str;


saldo_orden = document.getElementById("saldo_contrato").value;
var str = saldo_orden;for(i=0; i<saldo_orden.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var saldo_orden = str;



total_retencion_monto_iva = document.getElementById("total_retencion_monto_iva").value;
var str = total_retencion_monto_iva;for(i=0; i<total_retencion_monto_iva.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var total_retencion_monto_iva = str;




porcentaje_islr_input = document.getElementById("impuesto_sobre_la_renta").value;
var str = porcentaje_islr_input;for(i=0; i<porcentaje_islr_input.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var porcentaje_islr_input = str;

timbre_fiscal = document.getElementById("timbre_fiscal").value;
var str = timbre_fiscal;for(i=0; i<timbre_fiscal.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var timbre_fiscal = str;

impuesto_municipal = document.getElementById("impuesto_municipal").value;
var str = impuesto_municipal;for(i=0; i<impuesto_municipal.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var impuesto_municipal = str;

sustraendo = document.getElementById("sustraendo").value;
var str = sustraendo;for(i=0; i<sustraendo.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var sustraendo = str;



total_retencion  = 0;
total_retencion2 = 0;

if(document.getElementById('anticipo_con_iva2').value=="1"  && (monto_retencion_laboral_input!=0 || monto_fiel_cumplimiento!=0)){
	monto_iva_var_B =  eval(total_retencion_monto_iva) * (eval(porcentaje_iva_input) /eval(100));
    monto_iva_var = monto_iva_var_B;
    total_retencion =  total_retencion_monto_iva;
}else{
	total_retencion =  total_retencion_monto_iva;
}


monto_orde = redondear(eval(monto_a_pagar_con_iva) - eval(amortizacion_del_anticipo_monto_iva) -  eval(monto_fiel_cumplimiento) -  eval(monto_retencion_laboral_input), 2);
total_retenten = total_retencion_monto_iva;


if(total_retencion==0){sustraendo=0;}
porcentaje_impuesto_sobre_la_renta =  eval(porcentaje_islr_input) / eval(100);
impuesto_sobre_la_renta = ((eval(total_retencion) *  eval(porcentaje_impuesto_sobre_la_renta))   -   eval(sustraendo));

document.getElementById('impuesto_sobre_la_renta_monto_iva').value = redondear(impuesto_sobre_la_renta  , 2);
document.getElementById('timbre_fiscal_monto_iva').value =  redondear( (eval(total_retencion) / eval(1000)) *  (eval(timbre_fiscal)) , 2);
document.getElementById('impuesto_municipal_monto_iva').value = redondear( (eval(total_retencion) *  (eval(impuesto_municipal) / eval(100))   ), 2);




document.getElementById("monto_iva").value = redondear(monto_iva_var, 2);
document.getElementById('monto_orden_de_pago_monto_iva').value = redondear(eval(monto_a_pagar_con_iva) - eval(amortizacion_del_anticipo_monto_iva) -  eval(monto_fiel_cumplimiento) -  eval(monto_retencion_laboral_input), 2);
document.getElementById('monto_a_pagar_monto_iva').value = redondear(eval(monto_orde) - eval(total_retenten), 2);
document.getElementById('nuevo_monto_pagar').value = redondear(eval(saldo_orden) - eval(monto_a_pagar_con_iva), 2);



moneda('impuesto_sobre_la_renta_monto_iva');
moneda('timbre_fiscal_monto_iva');
moneda('impuesto_municipal_monto_iva');


moneda('nuevo_monto_pagar');
moneda('monto_a_pagar_monto_iva');
moneda('monto_orden_de_pago_monto_iva');
moneda('monto_a_pagar_con_iva');
moneda('monto_iva');
moneda('total_retencion_monto_iva');

}//fin function












