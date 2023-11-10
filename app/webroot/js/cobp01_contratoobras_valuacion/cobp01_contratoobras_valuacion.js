

function cobp01_contratoobras_valuacion_valida(){

if(verifica_cierre_ano_ejecucion('fecha_valuacion')==false){
	fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA DE VALUACI&Oacute;N DEL DOCUMENTO NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
	return false;
}else{

var total_grilla_aux                       = retornar_valor_calculo(document.getElementById("TOTALINGRESOS").innerHTML);
var monto_orden_de_pago_monto_iva_aux      = retornar_valor_calculo(document.getElementById("monto_orden_de_pago_monto_iva").value);
var porce_retencion_fiel_cumplimiento_aux  = retornar_valor_calculo(document.getElementById("porce_retencion_fiel_cumplimiento").value);
var monto_excento  = retornar_valor_calculo(document.getElementById("monto_excento").value);

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

iva_monto            =  redondear(((eval(monto_partidas_sin_iva) - eval(monto_excento) ) * eval(porcentaje_iva_input)) , 2);



monto_a_pagar_con_iva_valida = document.getElementById("monto_a_pagar_con_iva").value;
var str = monto_a_pagar_con_iva_valida;for(i=0; i<monto_a_pagar_con_iva_valida.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var monto_a_pagar_con_iva_valida = str;

monto_iva_valida = document.getElementById("monto_iva").value;
var str = monto_iva_valida;for(i=0; i<monto_iva_valida.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var monto_iva_valida = str;

monto_compara_a    = redondear(eval(iva_monto) + eval(monto_partidas_sin_iva), 2);
monto_partidas_iva = redondear(monto_partidas_iva, 2);





if(eval(monto_iva_valida)==0){
  monto_partidas_iva = 0;
}else{

aux_resta_iva = redondear(eval(monto_partidas_iva) - eval(iva_monto), 2);
          if(eval(monto_partidas_iva) > eval(iva_monto)){
		      if(eval(aux_resta_iva)==eval(0.01)  ||  eval(aux_resta_iva)==eval(-0.01)){
                  monto_partidas_iva = redondear(eval(monto_partidas_iva) - eval(0.01), 2);
		      }//fin if
      }else if(eval(monto_partidas_iva) < eval(iva_monto)){
              if(eval(aux_resta_iva)==eval(0.01)  ||  eval(aux_resta_iva)==eval(-0.01)){
                  monto_partidas_iva = redondear(eval(monto_partidas_iva) + eval(0.01), 2);
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

	   }else if(document.getElementById('numero_fianza_fielcumplimiento').value == ""  && eval(porce_retencion_fiel_cumplimiento_aux)==0){

          fun_msj('El contrato no posee fianza de fiel cumplimiento, debe insertar porcentaje Retenci&oacute;n Fiel Cumplimiento');
		  return false;


       }else if(document.getElementById('concepto').value == ""){

          fun_msj('Inserte el concepto de la autorizacion de pago');
		  document.getElementById('concepto').focus();
		  return false;

	   }else if(total_grilla_aux != monto_orden_de_pago_monto_iva_aux){

          //fun_msj('El total de las partidas es diferente al monto de la orden de pago');
		  //return false;


       }else{
       	// DESCOMENTAR LINEA document.getElementById('guardar').disabled=true; Y COMENTAR LAS OTRAS 2 
       	// EN LA CONDICION VERDADERA PARA QUE DEJE GUARDAR EL CONTRATO
		         if(monto_partidas_iva!=iva_monto){

							   mensaje_con_monto_iva_incorrecto(monto_partidas_iva);
						       return false;
                               //document.getElementById('guardar').disabled=true;
		          }else{
                                document.getElementById('guardar').disabled=true;

		          }//fin else


       }//fin guardar


}// FIN ELSE VERIFICANDO ANO EJEC <> ANO FECHA DOC.






}//fin functiontr rth























function cobp01_contratoobras_valuacion_pregunta_pago_parcial(opc){

        if(opc=="2"){

        porcentaje_iva = document.getElementById('porcentaje_iva').value;
        porcentaje_iva = eval(porcentaje_iva) / eval(100);

        total = 0;
        monto_iva_var = 0;

     for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
         document.getElementById('pago_'+ii).value     = document.getElementById('monto_actual_'+ii).value;
         document.getElementById('pago_aux_'+ii).value = document.getElementById('monto_actual_'+ii).value;
         a = document.getElementById('monto_actual_'+ii).value;
		 var str = a;
		 for(i=0; i<a.length; i++){str = str.replace('.','');}//fin for
		 str   = str.replace(',','.');
		 var a = redondear(str,2);
		total =  eval(total) + eval(a);

		document.getElementById('pago_'+ii).readOnly = true;
		document.getElementById('pago_'+ii).disabled = false;
		//document.getElementById('pago_aux_'+ii).readOnly = true;
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

		  //moneda('monto_iva');

		// document.getElementById('monto_iva_var').value = eval(total) * eval(porcentaje_iva);
		// moneda('monto_iva_var');

         cobp01_contratoobras_valuacion_detalles_del_pago();

   }else if(opc=="1"){


         total = 0;
         for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
            document.getElementById('pago_'+ii).value = "0,00";
            document.getElementById('pago_'+ii).readOnly = false;
            document.getElementById('pago_'+ii).disabled = false;

            document.getElementById('pago_aux_'+ii).value = "0,00";
            //document.getElementById('pago_aux_'+ii).readOnly = false;
            document.getElementById('pago_aux_'+ii).disabled = false;

         }//fin

         document.getElementById('monto_a_pagar_con_iva').value=total;
		 moneda('monto_a_pagar_con_iva');
		 cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total);

         document.getElementById('monto_a_pagar_con_iva').readOnly = true;
		 document.getElementById('monto_a_pagar_con_iva').disabled = false;
		 document.getElementById('monto_iva').readOnly = true;
		 document.getElementById('monto_iva').disabled = false;

		 document.getElementById('monto_iva').value = "0,00";


        cobp01_contratoobras_valuacion_detalles_del_pago();

   }//fin
}//fin function




















function cobp01_contratoobras_valuacion_monto_id_editar_2(id_i,op_i){

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
// document.getElementById('pago_'+ii).value = document.getElementById('pago_aux_'+ii).value;
//  a                                        = document.getElementById('pago_aux_'+ii).value;
a  = document.getElementById('pago_'+ii).value;
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

cobp01_contratoobras_valuacion_detalles_del_pago();

 }//fin else
}///fin function


























function cobp01_contratoobras_valuacion_colocar_iva(id_i,op_i){


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
               // document.getElementById('pago_'+ii).value = document.getElementById('pago_aux_'+ii).value;
              //  a                                         = document.getElementById('pago_aux_'+ii).value;
               a  = document.getElementById('pago_'+ii).value;
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
//moneda('monto_iva');


cobp01_contratoobras_valuacion_detalles_del_pago();

  }//fin else
}//fin function





function cobp01_cal_multa(a){

    var Mtc   = reemplazarPC(document.getElementById('monto_a_pagar_con_iva').value);//----
    var Mri   = reemplazarPC(document.getElementById('monto_iva').value);//----
    var Prc   = reemplazarPC(document.getElementById('rcivil').value);//----
    var Prs   = reemplazarPC(document.getElementById('rsocial').value);//----
	var calculo=0;

if(a==1){
	calculo=((Mtc-Mri)*Prc)/100
		$('retencion_multa_monto').value=redondear(calculo,2);
		moneda('retencion_multa_monto');


}else{
	calculo=((Mtc-Mri)*Prs)/100
		$('retencion_responsabilidad_social').value=redondear(calculo,2);

		moneda('retencion_responsabilidad_social');

}

 	cobp01_contratoobras_valuacion_detalles_del_pago();
}














function cobp01_contratoobras_valuacion_detalles_del_pago(){


  monto_iva_var_401 = 0;
  monto_iva_var = 0;
  for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
    if(document.getElementById('partida_401_'+ii)){
      if(document.getElementById('partida_401_'+ii).value=="si"){
        a = document.getElementById('pago_'+ii).value;
        var str = a;
        acepto="no";
        for(i=0; i<a.length; i++){
          if(str.charAt(i)==","){acepto="si";}
        }
        if(acepto=="si"){
          for(i=0; i<a.length; i++){
            str = str.replace('.','');
          }
          str = str.replace(',','.'); 
        }
        var a = redondear(str,2);
        monto_iva_var_401 = eval(monto_iva_var_401) + eval(a);
      }
    }
  }

  var str =  monto_iva_var_401+'';
  for(x=0; x<str.length; x++){
    if(str.charAt(x)=="."){ 
      monto_iva_var_401=str.substr(0,eval(x)+eval(6));break;
    }
  }
  var monto_iva_var_401 = redondear(monto_iva_var_401,2);

  //monto_iva_var_401 =  eval(monto_iva_var_401) * eval(-1);

  for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
    if(document.getElementById('partida_iva_'+ii)){
      a = document.getElementById('pago_'+ii).value;
      str = a;
      acepto="no";
      for(i=0; i<a.length; i++){
        if(str.charAt(i)==","){
          acepto="si";
        }
      }
      if(acepto=="si"){
        for(i=0; i<a.length; i++){
          str = str.replace('.','');
        }
        str = str.replace(',','.');
      }
      var a = redondear(str,2);
      monto_iva_var = eval(monto_iva_var) + eval(a);
    }
  }
  
  //monto_iva_var =  eval(monto_iva_var) - eval(monto_iva_var_401);
  acepto="no";
  var str = monto_iva_var;
  for(i=0; i<monto_iva_var.length; i++){
    if(str.charAt(i)==","){
      acepto="si";
    }
  }
  if(acepto=="si"){
    for(i=0; i<monto_iva_var.length; i++){
      str = str.replace('.','');
    }
    str = str.replace(',','.');
  }
  var monto_iva_var = redondear(str,2);


  monto_opcion_pago222 = retornar_valor_calculo($('monto_opcion_pago').value);
  if(eval(monto_iva_var)==0 && eval(monto_opcion_pago222)!=0){
    document.getElementById("porcentaje_iva").value = 0;
  }

  monto_opcion_pago222 = retornar_valor_calculo($('monto_opcion_pago').value);
  monto_mano_obra      = retornar_valor_calculo($('monto_mano_obra').value);
  monto_excento      = retornar_valor_calculo($('monto_excento').value);

  if(eval(monto_mano_obra)>eval(monto_opcion_pago222)){
    fun_msj('MONTO DE LA MANO DE OBRA NO PUEDE SER MAYOR AL MONTO DE LA VALUACI&oacute;N');
    $('monto_mano_obra').value = "0,00";
    document.getElementById('monto_mano_obra').focus();
    monto_mano_obra            = 0;
  }
  if(eval(monto_excento)>eval(monto_opcion_pago222)){
    fun_msj('MONTO EXCENTO DE LA OBRA NO PUEDE SER MAYOR AL MONTO DE LA VALUACI&oacute;N');
    $('monto_excento').value = "0,00";
    document.getElementById('monto_excento').focus();
    monto_excento            = 0;
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

  if(document.getElementById('numero_fianza_calidad')){
    if(document.getElementById('numero_fianza_calidad').value == ""){
      document.getElementById('porce_retencion_laboral').disabled = false;
    }else{
      document.getElementById('porce_retencion_laboral').disabled = true;
    }
  }
  if(document.getElementById('numero_fianza_fielcumplimiento')){
    if(document.getElementById('numero_fianza_fielcumplimiento').value == ""){
      document.getElementById('porce_retencion_fiel_cumplimiento').disabled = false;
    }else{
      document.getElementById('porce_retencion_fiel_cumplimiento').disabled = true;
    }
  }
    document.getElementById('monto_a_pagar_con_iva').readOnly = true;
  document.getElementById('monto_iva').readOnly = true;
  document.getElementById('retencion_multa_monto').disabled = false;
  document.getElementById('retencion_responsabilidad_social').disabled = false;
  document.getElementById('rcivil').disabled = false;
  document.getElementById('rsocial').disabled = false;

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

  if(monto_iva_var=='0.00' && eval(monto_opcion_pago222)!=0){
    /*document.getElementById('retencion_incluye_iva').options[0].text =  '0';
    document.getElementById('retencion_incluye_iva').options[0].value = '0';
    document.getElementById('retencion_incluye_iva').options[0].selected = true;*/

 for (var i = 1; i < document.getElementById('retencion_incluye_iva').length; i++){
  document.getElementById('retencion_incluye_iva').options[i].text =  '';
  document.getElementById('retencion_incluye_iva').options[i].value = '0';
  ii++;
 }//fin for
}else{


 /* document.getElementById('retencion_incluye_iva').options[0].text =  '0';
  document.getElementById('retencion_incluye_iva').options[0].value = '0';
*/
  document.getElementById('retencion_incluye_iva').options[0].text =  '75';
  document.getElementById('retencion_incluye_iva').options[0].value = '75';

  document.getElementById('retencion_incluye_iva').options[1].text =  '100';
  document.getElementById('retencion_incluye_iva').options[1].value = '100';

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


monto_iva_var_aux_2 = monto_iva_var;
monto_sin_iva       = redondear( eval(monto_a_pagar_con_iva)  -  eval(monto_iva_var) , 2);



monto_laboral = 0;
monto_fiel_cumplimiento = 0;
amortizacion_del_anticipo_monto_iva = 0;
document.getElementById('monto_laboral').value = 0;
document.getElementById('monto_fiel_cumplimiento').value = 0;
document.getElementById('amortizacion_del_anticipo_monto_iva').value = 0;

//Retención laboral
    if(monto_mano_obra==""){monto_mano_obra=0;}
    if(monto_mano_obra==0){
     porce_retencion_laboral_input = 0;
     monto_laboral = 0;
    }else{
     porce_retencion_laboral_input = document.getElementById("porce_retencion_laboral").value;
     var str = porce_retencion_laboral_input;for(i=0; i<porce_retencion_laboral_input.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var porce_retencion_laboral_input = str;
     monto_laboral = redondear(eval(monto_mano_obra) *  eval(porce_retencion_laboral_input/100), 2);
     document.getElementById('monto_laboral').value = monto_laboral;
    }
//Retención fiel cumplimiento
    porce_retencion_fiel_cumplimiento_input = document.getElementById("porce_retencion_fiel_cumplimiento").value;
    var str = porce_retencion_fiel_cumplimiento_input;for(i=0; i<porce_retencion_fiel_cumplimiento_input.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var porce_retencion_fiel_cumplimiento_input = str;
    monto_fiel_cumplimiento = redondear((eval(monto_a_pagar_con_iva)) *  eval(porce_retencion_fiel_cumplimiento_input/100), 2);
    document.getElementById('monto_fiel_cumplimiento').value = monto_fiel_cumplimiento;
//Amortización anticipo
    acepto="no";
    saldo_orden = document.getElementById("saldo_contrato").value;
    var str = saldo_orden;
    for(i=0; i<saldo_orden.length; i++){if(str.charAt(i)==","){acepto="si";}}//fin for
    if(acepto=="si"){  
    for(i=0; i<saldo_orden.length; i++){str = str.replace('.','');}str = str.replace(',','.');}//fin
    var saldo_orden = redondear(str,2);
    nuevo_monto =  redondear(eval(saldo_orden) - eval(monto_a_pagar_con_iva), 2);
    porcentaje_amortizacion_input = document.getElementById("amortizacion_del_anticipo").value;
    var str = porcentaje_amortizacion_input;
    for(i=0; i<porcentaje_amortizacion_input.length; i++){str = str.replace('.','');}str   = str.replace(',','.');
    	var porcentaje_amortizacion_input = str;
    document.getElementById('nuevo_monto_pagar').value = redondear(eval(saldo_orden) - eval(monto_a_pagar_con_iva), 2);

  if(nuevo_monto==0){
    saldo_anticipo = document.getElementById('saldo_anticipo').value;
    var str = saldo_anticipo;for(i=0; i<saldo_anticipo.length; i++){str = str.replace('.','');}str   = str.replace(',','.');
    var saldo_anticipo = str;
    document.getElementById('amortizacion_del_anticipo_monto_iva').value = eval(saldo_anticipo);
    amortizacion_del_anticipo_monto_iva = eval(saldo_anticipo);
  }else{
      if(document.getElementById('anticipo_con_iva').value=="1"){
        amortizacion_del_anticipo_monto_iva = redondear((eval(monto_a_pagar_con_iva) - eval(monto_excento) )*  eval(porcentaje_amortizacion_input/100), 2);
      }else{
        amortizacion_del_anticipo_monto_iva = redondear(eval(monto_sin_iva) *  eval(porcentaje_amortizacion_input/100), 2);
      }
    }

    document.getElementById('amortizacion_del_anticipo_monto_iva').value = eval(amortizacion_del_anticipo_monto_iva);
    amortizacion_del_anticipo_monto_iva_t = eval(amortizacion_del_anticipo_monto_iva);


//islr
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

porcentaje_amortizacion_input = document.getElementById("amortizacion_del_anticipo").value;
//var str = porcentaje_amortizacion_input;for(i=0; i<porcentaje_amortizacion_input.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var porcentaje_amortizacion_input = str;

acepto="no";var str = porcentaje_amortizacion_input;
for(i=0; i<porcentaje_amortizacion_input.length; i++){if(str.charAt(i)==","){acepto="si";}}
if(acepto=="si"){for(i=0; i<porcentaje_amortizacion_input.length; i++){str = str.replace('.','');}str = str.replace(',','.');}var porcentaje_amortizacion_input = redondear(str,2);




porcentaje_impuesto_muni_input = document.getElementById("impuesto_municipal").value;
var str = porcentaje_impuesto_muni_input;for(i=0; i<porcentaje_impuesto_muni_input.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var porcentaje_impuesto_muni_input = str;


porcentaje_iva_input = document.getElementById("porcentaje_iva").value;
var str = porcentaje_iva_input;for(i=0; i<porcentaje_iva_input.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var porcentaje_iva_input = str;



total_retencion  = 0;
total_retencion2 = 0;


if(document.getElementById('anticipo_con_iva2').value=="1"  && (porce_retencion_laboral_input!=0 || porce_retencion_fiel_cumplimiento_input!=0)){

                A  =  eval(monto_fiel_cumplimiento) + eval(monto_laboral);
              //B  =  eval(monto_opcion_pago222)-eval(A);ORIGINAL - GOB.GUARICO LINEA SIGUIENTE
                B  =  eval(monto_opcion_pago222);
                C = eval(monto_excento);
                aux_base = (eval(B)-eval(C)) / (eval(1) + (eval(porcentaje_iva_input) /eval(100)));
     			total_retencion  =  redondear(aux_base+eval(C)	, 2);
     			monto_iva_var = (eval(B)-eval(C)) - eval(aux_base);

}else{
                A  =  eval(monto_fiel_cumplimiento) + eval(monto_laboral);
              //B  =  eval(monto_opcion_pago222)-eval(A);ORIGINAL - GOB.GUARICO LINEA SIGUIENTE
                B  =  eval(monto_opcion_pago222);
                C = eval(monto_excento);
                aux_base = (eval(B)-eval(C)) / (eval(1) + (eval(porcentaje_iva_input) /eval(100)));
          		total_retencion  =  redondear(aux_base+eval(C), 2);
     			monto_iva_var = (eval(B)-eval(C)) - eval(aux_base);

}


monto_iva_var   = redondear(monto_iva_var, 2);
total_retencion = redondear(total_retencion, 2);
var_cuenta_aux  = eval(monto_iva_var) + eval(total_retencion) + eval(monto_fiel_cumplimiento) + eval(monto_laboral) + eval(monto_excento);
var_cuenta_aux  = redondear(var_cuenta_aux, 2);

if(var_cuenta_aux!=monto_a_pagar_con_iva){
           if(porce_retencion_laboral_input!=0){
           document.getElementById('monto_laboral').value = monto_laboral;
     }else if(porce_retencion_fiel_cumplimiento_input!=0){
           document.getElementById('monto_fiel_cumplimiento').value = monto_fiel_cumplimiento;
     }//fin if

}//fin if



acepto="no";saldo_orden = document.getElementById("saldo_contrato").value;var str = saldo_orden;
for(i=0; i<saldo_orden.length; i++){if(str.charAt(i)==","){acepto="si";}}//fin for
if(acepto=="si"){  for(i=0; i<saldo_orden.length; i++){str = str.replace('.','');}str = str.replace(',','.');}//fin
var saldo_orden = redondear(str,2);
nuevo_monto =  redondear(eval(saldo_orden) - eval(monto_a_pagar_con_iva), 2);


if(nuevo_monto=="0" && (porce_retencion_laboral_input==0 && porce_retencion_fiel_cumplimiento_input==0)){
 monto_iva_var = monto_iva_var_aux_2;
}

document.getElementById("monto_iva").value = redondear(monto_iva_var,2);
document.getElementById('total_retencion_monto_iva').value = redondear(total_retencion,2);


    iva_aux_ccc    = eval(monto_a_pagar_con_iva);
  iva_aux_ccc2   = redondear((eval(iva_aux_ccc)-eval(monto_excento)) /  (eval(1) + (eval(porcentaje_iva_input) /eval(100))), 2);
    iva_aux_ccc3   = redondear( eval(monto_a_pagar_con_iva) -  eval(iva_aux_ccc2) , 2);
    iva_aux_ccc4   = redondear( eval(monto_a_pagar_con_iva) -  eval(iva_aux_ccc3) ,2);
    monto_sin_iva  = iva_aux_ccc4;

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

if ((eval(total_retencion) < eval(document.getElementById('desde_monto_islr').value)) || eval(document.getElementById('exento_islr_cooperativa').value=="1")){
  document.getElementById('impuesto_sobre_la_renta').value = "0.00";
  document.getElementById('impuesto_sobre_la_renta_monto_iva').value = "0.00";
  document.getElementById('impuesto_sobre_la_renta').disabled = true;
  document.getElementById('impuesto_sobre_la_renta_monto_iva').disabled = true;
  var impuesto_sobre_la_renta = 0;
  var impuesto_sobre_la_renta_monto_iva = 0;
  }

if (eval(total_retencion) < eval(document.getElementById('desde_monto_timbre').value)){
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

  document.getElementById('monto_sin_iva').value = monto_sin_iva;
  document.getElementById('retencion_incluye_iva_monto_iva').value =  redondear(( eval(monto_iva_var) *  (eval(document.getElementById("retencion_incluye_iva").value) / eval(100)) ) , 2);
  document.getElementById('impuesto_sobre_la_renta_monto_iva').value = redondear(impuesto_sobre_la_renta  , 2);
  document.getElementById('timbre_fiscal_monto_iva').value =  redondear( (eval(total_retencion) / eval(1000)) *  (eval(porcentaje_timbre_input)) , 2);
  document.getElementById('impuesto_municipal_monto_iva').value = redondear( (eval(total_retencion) *  (eval(porcentaje_impuesto_muni_input) / eval(100))   ), 2);

//Responsabilidad civil
    porce_rcivil_input = document.getElementById("rcivil").value;
    var str = porce_rcivil_input;for(i=0; i<porce_rcivil_input.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var porce_rcivil_input = str;
    retencion_multa_monto = redondear(eval(total_retencion) *  eval(porce_rcivil_input/100), 2);
    document.getElementById('retencion_multa_monto').value = eval(retencion_multa_monto);

//Responsabilidad social
    porce_rsocial_input = document.getElementById("rsocial").value;
    var str = porce_rsocial_input;for(i=0; i<porce_rsocial_input.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var porce_rsocial_input = str;
    retencion_responsabilidad_social = redondear(eval(total_retencion) *  eval(porce_rsocial_input/100), 2);
    document.getElementById('retencion_responsabilidad_social').value = eval(retencion_responsabilidad_social);


    impuesto_sobre_la_renta = redondear(impuesto_sobre_la_renta, 2);
    retencion_incluye_iva_monto_iva =  redondear(( eval(monto_iva_var) *  (eval(document.getElementById("retencion_incluye_iva").value) / eval(100)) ) , 2);
    timbre_fiscal_monto_iva =  redondear((eval(total_retencion) / eval(1000)) *  (eval(porcentaje_timbre_input))  , 2);
    impuesto_municipal_monto_iva = redondear( eval(total_retencion) *  (eval(porcentaje_impuesto_muni_input) / eval(100)), 2);

    if(impuesto_sobre_la_renta<0){ impuesto_sobre_la_renta = eval(impuesto_sobre_la_renta) * eval(-1);}//fin if

    total_retenten = redondear((eval(monto_fiel_cumplimiento) +  eval(monto_laboral) + eval(amortizacion_del_anticipo_monto_iva) + eval(impuesto_sobre_la_renta) +  eval(retencion_incluye_iva_monto_iva)  + eval(timbre_fiscal_monto_iva)  + eval(impuesto_municipal_monto_iva) + eval(retencion_multa_monto) + eval(retencion_responsabilidad_social)), 2);

    document.getElementById('monto_orden_de_pago_monto_iva').value = redondear(eval(monto_a_pagar_con_iva) - eval(amortizacion_del_anticipo_monto_iva) -  eval(monto_fiel_cumplimiento) -  eval(monto_laboral), 2);
    monto_orde = redondear(eval(monto_a_pagar_con_iva) - eval(amortizacion_del_anticipo_monto_iva) -  eval(monto_fiel_cumplimiento) -  eval(monto_laboral), 2);
    document.getElementById('monto_a_pagar_monto_iva').value = redondear(eval(monto_a_pagar_con_iva)  - eval(total_retenten), 2);

//if((porce_retencion_laboral_input!=0 || porce_retencion_fiel_cumplimiento_input!=0)){
    var var_retencion_laboral  = new Array();
    var var_retencion_fiel     = new Array();
    var var_retencion_amortiza = new Array();
    var valor_aux_pago         = new Array();

    monto_de_la_orden_pago = redondear(eval(monto_a_pagar_con_iva) - eval(amortizacion_del_anticipo_monto_iva) -  eval(monto_fiel_cumplimiento) -  eval(monto_laboral), 2);

         if(porce_retencion_laboral_input!=0){
        for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
                        var_pago                  = retornar_valor_calculo(document.getElementById('pago_'+ii).value);
                        var_retencion_laboral[ii] = redondear(  (eval(var_pago) * eval(monto_laboral)) / (eval(monto_opcion_pago222)  ), 2);
        }//fin
     }//fin
         if(porce_retencion_fiel_cumplimiento_input!=0){
        for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
                        var_pago               = retornar_valor_calculo(document.getElementById('pago_'+ii).value);
                        var_retencion_fiel[ii] = redondear(  (eval(var_pago) * eval(monto_fiel_cumplimiento)) / (eval(monto_opcion_pago222)  ), 2);
        }//fin
      }//fin
        p_a_i = retornar_valor_calculo(document.getElementById("amortizacion_del_anticipo").value);
        p_i_i = retornar_valor_calculo(document.getElementById("porcentaje_iva").value);
             if(p_a_i!=0){
          for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
          if(!document.getElementById('partida_iva_'+ii)){
            if(document.getElementById('anticipo_con_iva').value=="1"){
              var_pago                   = retornar_valor_calculo(document.getElementById('pago_'+ii).value);
              var_retencion_amortiza[ii] = redondear( ((eval(var_pago) + (eval(var_pago) * (eval(p_i_i)/eval(100)))) * eval(p_a_i)) / 100 , 2);
              if(nuevo_monto=="0"){
               var_retencion_amortiza[ii] = retornar_valor_calculo(document.getElementById('m_p_a_a_'+ii).value);
              }
            }else{
              var_pago                   = retornar_valor_calculo(document.getElementById('pago_'+ii).value);
                var_retencion_amortiza[ii] = redondear(  (eval(var_pago) * eval(p_a_i)) / eval(100), 2);
                if(nuevo_monto=="0"){
               var_retencion_amortiza[ii] = retornar_valor_calculo(document.getElementById('m_p_a_a_'+ii).value);
              }
            }//fin else
          }//fin id
          }//fin
       }//fin

        for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
          var_pago         = retornar_valor_calculo(document.getElementById('pago_'+ii).value);
          valor_aux_pago_2 = eval(var_pago);

            if(var_retencion_amortiza[ii]){ 
            	valor_aux_pago_2 = eval(valor_aux_pago_2) - eval(var_retencion_amortiza[ii]);
                valor_aux_pago[ii] = redondear(valor_aux_pago_2,2);
            }
			// GOB.GUARICO

            valor_aux_pago[ii] = redondear(valor_aux_pago_2,2);

            if(document.getElementById('partida_iva_'+ii)){
              valor_aux_pago[ii] = redondear(valor_aux_pago_2,2);
            }else{
          		if(var_retencion_fiel[ii]){
	              monto_iva_fiel = redondear(eval(var_retencion_fiel[ii]) * eval(porcentaje_iva_input) / eval(100), 2);
	              valor_aux_pago_2 = (eval(valor_aux_pago_2) - (eval(var_retencion_fiel[ii]) + eval(monto_iva_fiel)));
	              if(document.getElementById('partida_numero_'+ii)){
	              	if(document.getElementById('partida_numero_'+ii).value=="4.03.06.01.00"){
	      						valor_aux_pago[ii]=redondear(monto_excento,2);
		      				}else{
	      		  			valor_aux_pago[ii] = redondear(valor_aux_pago_2,2);
	      		  		}
	      		  	}else{
	      		  		valor_aux_pago[ii] = redondear(valor_aux_pago_2,2);
	      		  	}
	            }
	            if(var_retencion_laboral[ii]){
	              monto_iva_laboral = redondear(eval(var_retencion_laboral[ii]) * eval(porcentaje_iva_input) / eval(100), 2);
	              valor_aux_pago_2 = (eval(valor_aux_pago_2) - (eval(var_retencion_laboral[ii]) + eval(monto_iva_laboral)));
	              if(document.getElementById('partida_numero_'+ii)){
		              if(document.getElementById('partida_numero_'+ii).value=="4.03.06.01.00"){
		      					valor_aux_pago[ii]=redondear(monto_excento,2);	      			
		      		  	}else{
		      		  		valor_aux_pago[ii] = redondear(valor_aux_pago_2,2);
		      		  	}
		      		  }else{
		      		  	valor_aux_pago[ii] = redondear(valor_aux_pago_2,2);
		      		  }
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



//////////////////////////////////ACTUALIZAR PAGO_AUX/////////////////////////////////////////
                total2_aux = 0;
                for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){

              document.getElementById('pago_aux_'+ii).value = redondear(valor_aux_pago[ii],2);
              total3_aux                                    = redondear(valor_aux_pago[ii],2);
                total2_aux                                    = redondear(eval(total2_aux) + eval(total3_aux),2);
                moneda('pago_aux_'+ii);
            }//fin
        cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total2_aux);
//}//fin if

        total_reten= redondear((eval(document.getElementById('monto_laboral').value) +  eval(document.getElementById('monto_fiel_cumplimiento').value) +  eval(document.getElementById('amortizacion_del_anticipo_monto_iva').value) +  eval(document.getElementById('retencion_incluye_iva_monto_iva').value) +  eval(document.getElementById('impuesto_sobre_la_renta_monto_iva').value) + eval(document.getElementById('timbre_fiscal_monto_iva').value) + eval(document.getElementById('impuesto_municipal_monto_iva').value) + eval(document.getElementById('retencion_multa_monto').value) + eval(document.getElementById('retencion_responsabilidad_social').value)),2);

        document.getElementById('total_retenciones').value=redondear(eval(total_reten),2);

        moneda('total_retenciones');
                moneda('monto_laboral');
                moneda('monto_fiel_cumplimiento');
                moneda('retencion_multa_monto');
        moneda('retencion_responsabilidad_social');
          moneda('monto_a_pagar_con_iva');
          //moneda('monto_iva');
          moneda('total_retencion_monto_iva');
          moneda('amortizacion_del_anticipo_monto_iva');
          moneda('monto_sin_iva');
          moneda('retencion_incluye_iva_monto_iva');
          moneda('impuesto_sobre_la_renta_monto_iva');
          moneda('timbre_fiscal_monto_iva');
          moneda('impuesto_municipal_monto_iva');
          moneda('monto_orden_de_pago_monto_iva');
          moneda('monto_a_pagar_monto_iva');
          moneda('nuevo_monto_pagar');
          moneda('monto_mano_obra');





}//fin function



function cobp01_contratoobras_valuacion_respuesta_pago_parcial(opcion){

	total = 0;
	var valor_partidas = new Array();
  
  /////////////////////////////////////////////////OPCION I/////////////////////////////////////
  if(opcion=="1"){

    monto_opcion_pago = document.getElementById('monto_opcion_pago').value;
    var str = monto_opcion_pago;
    for(i=0; i<monto_opcion_pago.length; i++){str = str.replace('.','');}
    str = str.replace(',','.');
    var monto_opcion_pago = redondear(str,2);

    monto_excento = document.getElementById('monto_excento').value;
    var str2 = monto_excento;
    for(i=0; i<monto_excento.length; i++){str2 = str2.replace('.','');}
  	str2 = str2.replace(',','.');
  	var monto_excento = redondear(str2,2);
    var countPartIva = 0;

    for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
      document.getElementById('pago_'+ii).value     = document.getElementById('monto_actual_'+ii).value;
      document.getElementById('pago_aux_'+ii).value = document.getElementById('monto_actual_'+ii).value;
      a = document.getElementById('monto_actual_'+ii).value;
      var str = a;
      for(i=0; i<a.length; i++){str = str.replace('.','');}str   = str.replace(',','.');
      var a = redondear(str,2);
      total =  eval(total) + eval(a);
      document.getElementById('pago_'+ii).readOnly = false;
      document.getElementById('pago_'+ii).disabled = false;
      //document.getElementById('pago_aux_'+ii).readOnly = false;
      document.getElementById('pago_aux_'+ii).disabled = false;
      if(document.getElementById('partida_iva_'+ii)){ countPartIva++;}
    }//fin


    var str =  total+'';
    for(x=0; x<str.length; x++){if(str.charAt(x)=="."){total=str.substr(0,eval(x)+eval(6));break;}}
    var total = redondear(total,2);

    saldo_excento      = retornar_valor_calculo($('saldo_excento').value);

    if(monto_opcion_pago>total){
      fun_msj('MONTO DE LA VALUACI&oacute;N ES MAYOR AL SALDO DEL CONTRATO');
    } else if(eval(monto_excento)>eval(saldo_excento)){
      fun_msj('MONTO EXCENTO DE LA VALUACI&oacute;N NO PUEDE SER MAYOR AL MONTO EXCENTO DE LA OBRA');
      document.getElementById('monto_excento').value= "0,00";
      document.getElementById('monto_excento').focus();
      return;
    } else{
      total2 = 0;
      monto_iva_var = 0;
      for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
        a = document.getElementById('monto_actual_'+ii).value; var str = a;
        for(i=0; i<a.length; i++){str = str.replace('.','');} str  = str.replace(',','.');
        var a = redondear(str,2);
        document.getElementById('pago_'+ii).readOnly = false;
      
      	if(document.getElementById('partida_iva_'+ii)){
        	resul = (((eval(monto_opcion_pago) - eval(monto_excento)) * eval(a)) / (eval(total) - eval(monto_excento)) );
        	monto_iva_var = eval(monto_iva_var) + eval(resul);
        	document.getElementById('partida_iva_'+ii).readOnly = true;
        	document.getElementById('partida_iva_'+ii).disabled = false;
      	}else{

      		if(document.getElementById('partida_numero_'+ii).value=="4.03.06.01.00"){
      			resul=monto_excento;
      		}else{
          		resul = (((eval(monto_opcion_pago) - eval(monto_excento)) * eval(a)) / (eval(total) - eval(monto_excento)) );
      				var cantPart = eval(document.getElementById('cuenta_i').value)-countPartIva-1;
      				if (cantPart==0){
      					cantPart=1;
      				}
          		resul = eval(resul) + (eval(monto_excento)/(cantPart));
        	}
      	}

      	
        total2 =  redondear(eval(total2),2) + redondear(resul,2 );
        valor_partidas[ii]= redondear(resul, 2);
        
      }

      var str =  total2+'';
      for(x=0; x<str.length; x++){
        if(str.charAt(x)=="."){total2=str.substr(0,eval(x)+eval(6));break;}
      }
      var total2 = redondear(total2, 2);
      aux = 0;
      document.getElementById('monto_iva').disabled = false;
      document.getElementById('monto_iva').value=redondear(monto_iva_var,2);

      document.getElementById('monto_a_pagar_con_iva').disabled = false;

      document.getElementById('monto_a_pagar_con_iva').value=redondear(monto_opcion_pago,2);

      document.getElementById('monto_excento_iva').disabled = false;
      document.getElementById('monto_excento_iva').value=redondear(monto_excento,2);
      //detalles_del_pago();
      /////////////////////////////////////////////// DIFERENCIA INICIO ////////////////////////////////////////////////////////
      aa = document.getElementById("monto_opcion_pago").value;
      var str = aa;for(i=0; i<aa.length; i++){str = str.replace('.','');}str = str.replace(',','.');var aa = redondear(str,2);

      monto_opcion_pago_input = aa;
      aux=0;
      aux = eval(monto_opcion_pago_input) - eval(total2);
      var str =  aux+'';
      for(x=0; x<str.length; x++){if(str.charAt(x)=="."){aux=str.substr(0,eval(x)+eval(6));break;}}
      var aux = redondear(aux, 2);var aux = eval(aux);

      for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
        var str =  valor_partidas[ii]+'';
        for(x=0; x<str.length; x++){
		      if(str.charAt(x)=="."){
            str=str.substr(0,eval(x)+eval(6));
		        break;
		      }
        }
        valor_partidas[ii] = redondear(str, 2);
        valor_partidas[ii] = eval(valor_partidas[ii]);
      }//fin for

      //alert(monto_opcion_pago_input+"----"+total2);
      if(monto_opcion_pago_input!=total2){
        rendondear_decimal_contratos_1(monto_opcion_pago_input,total2,valor_partidas);
      }else{
        for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){

        	
          document.getElementById("pago_"+ii).value     = valor_partidas[ii];
          document.getElementById("pago_aux_"+ii).value = valor_partidas[ii];
          
          cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total2);
        }
      }//fin else
    }//fin else
    /////////////////////////////////////////////// DIFERENCIA FIN /////////////////////////////////////////////////////////

    cobp01_contratoobras_valuacion_detalles_del_pago();

    for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
    	moneda("pago_"+ii);
    	moneda("pago_aux_"+ii);
    }
  
  }else if(opcion=="2"){}//fin else
}//fin function















function rendondear_decimal_contratos_1(monto_opcion_pago_input, total2, valor_partidas){


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

      if(saldo_actual_orden==0 && valor_partidas[ii]>a){ 
      	j = eval(valor_partidas[ii]) - eval(a);
      	valor_partidas[ii] = valor_partidas[ii] - redondear(j,2);  
      	total2 = eval(total2) - redondear(j,2);

      }//fin fin
      if(saldo_actual_orden==0 && valor_partidas[ii]<a){ 
      	j = eval(a) - eval(valor_partidas[ii]);
      	valor_partidas[ii] = valor_partidas[ii] + redondear(j,2);
      	total2 = eval(total2) + redondear(j,2);
      }//fin fin

      if(monto_opcion_pago_input<total2){
          if(a_aux!= eval(d)){
                              total2 = redondear(total2,2);
                              valor_partidas[ii] = valor_partidas[ii] - eval(0);
                              var str =  valor_partidas[ii]+'';
                          for(x=0; x<str.length; x++){
                                 if(str.charAt(x)=="."){
                                                         valor_partidas[ii]=str.substr(0,eval(x)+eval(6));
                                                         break;
                                                       }
                          }
                          valor_partidas[ii] = redondear(valor_partidas[ii],2);
                          document.getElementById("pago_"+ii).value=redondear(valor_partidas[ii],2);
                          document.getElementById("pago_aux_"+ii).value=redondear(valor_partidas[ii],2);
                          
                          if(document.getElementById('partida_iva_'+ii)){
                                       document.getElementById("partida_iva_"+ii).value=redondear(valor_partidas[ii],2);
                          }
        }
}else if(monto_opcion_pago_input>total2){
          if(a_aux!= eval(d)){
                                 total2 = redondear(total2,2);
                                 valor_partidas[ii] = valor_partidas[ii] + eval(0);
                                 var str =  valor_partidas[ii]+'';
                              for(x=0; x<str.length; x++){
                                  if(str.charAt(x)=="."){
                                                          valor_partidas[ii]=str.substr(0,eval(x)+eval(6));
                                                          break;
                                                         }
                              }
                              valor_partidas[ii] = redondear(valor_partidas[ii],2);
                              document.getElementById("pago_"+ii).value=redondear(valor_partidas[ii],2);
                              document.getElementById("pago_aux_"+ii).value=redondear(valor_partidas[ii],2);
                              
                              if(document.getElementById('partida_iva_'+ii)){
                                          document.getElementById("partida_iva_"+ii).value=redondear(valor_partidas[ii],2);
                               }
       }
}else{
       document.getElementById("pago_"+ii).value=redondear(valor_partidas[ii], 2);
       document.getElementById("pago_aux_"+ii).value=redondear(valor_partidas[ii], 2);
       
     }
}//fin for

monto_iva_var = 0;
for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
  if(document.getElementById('partida_iva_'+ii)){
  	a = valor_partidas[ii];
  	monto_iva_var = eval(monto_iva_var) + eval(a);
  }
}//fin

var str =  monto_iva_var+'';for(x=0; x<str.length; x++){if(str.charAt(x)=="."){monto_iva_var=str.substr(0,eval(x)+eval(6));break;}}var monto_iva_var = redondear(monto_iva_var,2);
document.getElementById('monto_iva').value = monto_iva_var;

document.getElementById('monto_a_pagar_con_iva').value=total2;
cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total2);

}//fin function




















function cobp01_contratoobras_valuacion_valida_consulta(){



	if(verifica_cierre_ano_ejecucion_msj()==false){
		return false;
	}else if(document.getElementById('concepto_anulacion').value == ""){

            fun_msj('Inserte el concepto de anulaci&oacute;n');
			document.getElementById('concepto_anulacion').focus();
			return false;

   	}else{

            document.getElementById('guardar').disabled=true;


   	}//fin else





}//fin funciton





