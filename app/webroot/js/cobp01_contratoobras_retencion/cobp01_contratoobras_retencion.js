function cobp01_contratoobras_retencion_valida(){

if(verifica_cierre_ano_ejecucion('fecha_retencion')==false){
	fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA DE RETENCI&Oacute;N NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
	return false;
}else{

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



     }else if(document.getElementById('timbre_fiscal').value == ""){

            fun_msj('Inserte el timbre fiscal');
			document.getElementById('timbre_fiscal').focus();
			return false;


     }else if(document.getElementById('impuesto_municipal').value == ""){

           fun_msj('Inserte el porcentaje del impuesto municipal');
		   document.getElementById('impuesto_municipal').focus();
		   return false;



     }else if(document.getElementById('total_retencion_monto_iva').value == ""){

          fun_msj('Inserte el total de las retenciones');
		  document.getElementById('total_retencion_monto_iva').focus();
		  return false;



	  }else if(document.getElementById('numero_retencion').value == ""){

            fun_msj('Inserte el n&uacute;mero de retenci&oacute;n');
			document.getElementById('numero_retencion').focus();
			return false;


     }else if(document.getElementById('numero_aprobacion').value == ""){

            fun_msj('Inserte el n&uacute;mero de aprobaci&oacute;n');
			document.getElementById('numero_aprobacion').focus();
			return false;


       }else if(document.getElementById('fecha_aprobacion').value == ""){

            fun_msj('Inserte la fecha de aprobaci&oacute;n');
			document.getElementById('fecha_aprobacion').focus();
			return false;

	   }

	   /* else if(document.getElementById('porcentaje_iva').value == "" || document.getElementById('porcentaje_iva').value == "0,00"){

            fun_msj('Inserte el porcentaje del i.v.a');
			document.getElementById('porcentaje_iva').focus();
			return false;


       } */

       else if(document.getElementById('concepto').value == ""){

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


       }//fin else

}// FIN ELSE VERIFICANDO ANO EJEC <> ANO FECHA DOC.

}//fin function














function cobp01_contratoobras_retencion_detalles_del_pago(){

	monto_iva_var_401 = 0;
	monto_iva_var = 0;

  document.getElementById('monto_sin_iva').disabled = false;
  document.getElementById('retencion_incluye_iva_monto_iva').disabled = false;
  document.getElementById('impuesto_sobre_la_renta_monto_iva').disabled = false;
  document.getElementById('timbre_fiscal_monto_iva').disabled = false;
  document.getElementById('impuesto_municipal_monto_iva').disabled = false;
  document.getElementById('total_retencion_monto_iva').disabled = false;
  document.getElementById('monto_orden_de_pago_monto_iva').disabled = false;
  document.getElementById('monto_a_pagar_monto_iva').disabled = false;
  document.getElementById('monto_a_pagar_con_iva').readOnly = true;
  document.getElementById('monto_a_pagar_con_iva').disabled = false;
  document.getElementById('monto_iva').readOnly = true;
  document.getElementById('monto_iva').disabled = false;
  document.getElementById('retencion_multa_monto').disabled = false;
  document.getElementById('retencion_responsabilidad_social').disabled = false;


//GOB. GUARICO

  document.getElementById('porcentaje_iva').disabled = true;
  document.getElementById('retencion_incluye_iva').disabled = true;
  document.getElementById('sustraendo').disabled = true;
  document.getElementById('impuesto_sobre_la_renta').disabled = true;
  document.getElementById('impuesto_municipal').disabled = true;
  document.getElementById('timbre_fiscal').disabled = true;
  document.getElementById('rsocial').disabled = true;
  document.getElementById('rcivil').disabled = true;

//FIN GUARICO


//ORIGINAL

/*

  document.getElementById('porcentaje_iva').disabled = false;
  document.getElementById('retencion_incluye_iva').disabled = false;
  document.getElementById('sustraendo').disabled = false;
  document.getElementById('impuesto_sobre_la_renta').disabled = false;
  document.getElementById('impuesto_municipal').disabled = false;
  document.getElementById('timbre_fiscal').disabled = false;
  document.getElementById('rsocial').disabled = false;
  document.getElementById('rcivil').disabled = false;

*/

//FIN ORIGINAL




  	rcivil  = reemplazarPC(document.getElementById('rcivil').value);
    retencion_multa_monto = reemplazarPC(document.getElementById('retencion_multa_monto').value);
    rsocial    = reemplazarPC(document.getElementById('rsocial').value);
    retencion_responsabilidad_social   = reemplazarPC(document.getElementById('retencion_responsabilidad_social').value);

  	desde_monto_timbre_fiscal = document.getElementById("desde_monto_timbre_fiscal").value;


	acepto="no";
	monto_iva_var = document.getElementById("monto_iva").value;
	var str = monto_iva_var;
	for(i=0; i<monto_iva_var.length; i++){
   		if(str.charAt(i)==","){acepto="si";}
		}//fin for
		if(acepto=="si"){
  	for(i=0; i<monto_iva_var.length; i++){str = str.replace('.','');}//fin for
  		str = str.replace(',','.');
		}//fin

	var monto_iva_var = redondear(str,2);

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

if(monto_sin_iva < desde_monto_timbre_fiscal){
	document.getElementById("timbre_fiscal").value = '0';
}



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
	impuesto_sobre_la_renta = ((eval(monto_sin_iva) *  eval(porcentaje_impuesto_sobre_la_renta))/100)   -   eval(sustraendo);



document.getElementById("sustraendo").value = redondear(sustraendo, 2);
moneda('sustraendo');
//////////////////////////////////////////////////////////////
var str =  impuesto_sobre_la_renta+''; for(x=0; x<str.length; x++){if(str.charAt(x)=="."){ impuesto_sobre_la_renta=str.substr(0,eval(x)+eval(6));break;}}var impuesto_sobre_la_renta = redondear(impuesto_sobre_la_renta,2);
//////////////////////////////////////////////////////////////


porcentaje_timbre_input = document.getElementById("timbre_fiscal").value;
var str = porcentaje_timbre_input;for(i=0; i<porcentaje_timbre_input.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var porcentaje_timbre_input = str;

porcentaje_impuesto_muni_input = document.getElementById("impuesto_municipal").value;
var str = porcentaje_impuesto_muni_input;for(i=0; i<porcentaje_impuesto_muni_input.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var porcentaje_impuesto_muni_input = str;

document.getElementById("monto_iva").value = redondear(monto_iva_var, 2);
moneda('monto_a_pagar_con_iva');
moneda('monto_iva');

total_retencion =  (eval(monto_a_pagar_con_iva)  - (eval(monto_iva_var) + eval(monto_iva_var_401)));
total_retencion2 =  eval(monto_a_pagar_con_iva)  - (eval(monto_iva_var) );
document.getElementById('total_retencion_monto_iva').value = redondear(total_retencion2, 2);
moneda('total_retencion_monto_iva');


if ((eval(total_retencion) < eval(document.getElementById('desde_monto_islr').value)) || eval(document.getElementById('exento_islr_cooperativa').value=="1")){
  document.getElementById('impuesto_sobre_la_renta').value = "0.00";
  document.getElementById('impuesto_sobre_la_renta_monto_iva').value = "0.00";
  document.getElementById('impuesto_sobre_la_renta').disabled = true;
  document.getElementById('impuesto_sobre_la_renta_monto_iva').disabled = true;
  var impuesto_sobre_la_renta = 0;
  var impuesto_sobre_la_renta_monto_iva = 0;
  }

if (eval(total_retencion) < eval(document.getElementById('desde_monto_timbre').value)){
  document.getElementById('timbre_fiscal').value = "0.00";
  document.getElementById('timbre_fiscal_monto_iva').value = "0.00";
  document.getElementById('timbre_fiscal').disabled = true;
  document.getElementById('timbre_fiscal_monto_iva').disabled = true;
  var timbre_fiscal = 0;
  var timbre_fiscal_monto_iva = 0;
  var porcentaje_timbre_input = 0;
}

if (eval(total_retencion) < eval(document.getElementById('desde_monto_impuesto_municipal').value)){
  document.getElementById('impuesto_municipal').value = "0.00";
  document.getElementById('impuesto_municipal_monto_iva').value = "0.00";
  document.getElementById('impuesto_municipal').disabled = true;
  document.getElementById('impuesto_municipal_monto_iva').disabled = true;
  var impuesto_municipal = 0;
  var impuesto_municipal_monto_iva = 0;
  var porcentaje_impuesto_muni_input = 0;
}


if(total_retencion==0){sustraendo=0;}
porcentaje_impuesto_sobre_la_renta =  eval(porcentaje_islr_input) / eval(100);
impuesto_sobre_la_renta = ((eval(total_retencion) *  eval(porcentaje_impuesto_sobre_la_renta))   -   eval(sustraendo));

document.getElementById('monto_sin_iva').value = monto_sin_iva;moneda('monto_sin_iva');
document.getElementById('retencion_incluye_iva_monto_iva').value =  redondear((( eval(monto_iva_var) *  eval(document.getElementById("retencion_incluye_iva").value)) / eval(100)) , 2);moneda('retencion_incluye_iva_monto_iva');
document.getElementById('impuesto_sobre_la_renta_monto_iva').value = redondear(impuesto_sobre_la_renta  , 2);moneda('impuesto_sobre_la_renta_monto_iva');
document.getElementById('timbre_fiscal_monto_iva').value =  redondear( (eval(total_retencion) / eval(1000)) *  (eval(porcentaje_timbre_input)) , 2);moneda('timbre_fiscal_monto_iva');
document.getElementById('impuesto_municipal_monto_iva').value = redondear( ((eval(total_retencion) *  eval(porcentaje_impuesto_muni_input)) / eval(100)), 2);moneda('impuesto_municipal_monto_iva');

retencion_multa_monto = redondear((eval(total_retencion) * eval(rcivil/100)),2);
retencion_responsabilidad_social = redondear((eval(total_retencion) * eval(rsocial/100)),2);

impuesto_sobre_la_renta = redondear(impuesto_sobre_la_renta, 2);
retencion_incluye_iva_monto_iva =  redondear( eval(monto_iva_var) *  (eval(document.getElementById("retencion_incluye_iva").value) / eval(100)) , 2);
timbre_fiscal_monto_iva =  redondear((eval(total_retencion) / eval(1000)) *  (eval(porcentaje_timbre_input))  , 2);
impuesto_municipal_monto_iva = redondear( eval(total_retencion) *  (eval(porcentaje_impuesto_muni_input) / eval(100)), 2);
if(impuesto_sobre_la_renta<0){ impuesto_sobre_la_renta = eval(impuesto_sobre_la_renta) * eval(-1);}//fin if

total_retenten = eval(impuesto_sobre_la_renta) +  eval(retencion_incluye_iva_monto_iva)  + eval(timbre_fiscal_monto_iva)  + eval(impuesto_municipal_monto_iva) + eval(retencion_multa_monto) + eval(retencion_responsabilidad_social);

document.getElementById('monto_orden_de_pago_monto_iva').value = redondear(eval(monto_a_pagar_con_iva), 2);
moneda('monto_orden_de_pago_monto_iva');
monto_orde = redondear(eval(monto_a_pagar_con_iva), 2);

document.getElementById('monto_a_pagar_monto_iva').value = redondear(eval(monto_orde) - eval(total_retenten), 2);
moneda('monto_a_pagar_monto_iva');

document.getElementById('retencion_multa_monto').value = eval(retencion_multa_monto);
moneda('retencion_multa_monto');
document.getElementById('retencion_responsabilidad_social').value = eval(retencion_responsabilidad_social);
moneda('retencion_responsabilidad_social');

}//fin function
