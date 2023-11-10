function valida_grilla_patente(){


         if(document.getElementById('num_12').value==""){

			fun_msj('Inserte un c&oacute;digo de actividad');
			document.getElementById('num_12').focus();
			return false;

   }else  if(document.getElementById('activ_deno').value==""){

			fun_msj('Inserte un c&oacute;digo de actividad');
			document.getElementById('activ_deno').focus();
			return false;


   }else  if(document.getElementById('actv_num_afor').value=="" || document.getElementById('actv_num_afor').value=="0"){

			fun_msj('Inserte un n&uacute;mero de aforo');
			document.getElementById('actv_num_afor').focus();
			return false;



   }else  if(document.getElementById('activ_mont_aforo').value=="" || document.getElementById('activ_mont_aforo').value=="0,00"){

			fun_msj('Inserte el monto de aforo');
			document.getElementById('activ_mont_aforo').focus();
			return false;



	}//fin


}//fin function
















function guardar_patente_editar(){



                     if(document.getElementById('rif_constribuyente').value==""){

						fun_msj('Inserte un Constribuyente');
						document.getElementById('rif_constribuyente').focus();
						return false;


			   }else  if(document.getElementById('numero_solicitud').value==""){

						fun_msj('Inserte un n&uacute;mero de solucitud');
						document.getElementById('numero_solicitud').focus();
						return false;


			   }else  if(document.getElementById('numero_patente').value==""){

						fun_msj('Inserte el n&uacute;mero de patente');
						document.getElementById('numero_patente').focus();
						return false;


			  }else  if(document.getElementById('fecha_patente').value==""){

						fun_msj('Inserte la fecha de patente');
						document.getElementById('fecha_patente').focus();
						return false;

			  }else  if(document.getElementById('rif_cobrador').value==""){

						fun_msj('Inserte el cobrador');
						document.getElementById('rif_cobrador').focus();
						return false;

               }else  if(document.getElementById('numero_expediente').value==""){

						fun_msj('Inserte el n&uacute;mero de expediente');
						document.getElementById('numero_expediente').focus();
						return false;


			   }else  if(document.getElementById('monto_mensual').value=="" || document.getElementById('monto_mensual').value=="0,00"){

						fun_msj('Inserte el monto mensual');
						document.getElementById('monto_mensual').focus();
						return false;



			}//fin else


}//fin function




















function guardar_patente(){



                     if(document.getElementById('rif_constribuyente').value==""){

						fun_msj('Inserte un Constribuyente');
						document.getElementById('rif_constribuyente').focus();
						return false;


			   }else  if(document.getElementById('numero_solicitud').value==""){

						fun_msj('Inserte un n&uacute;mero de solucitud');
						document.getElementById('numero_solicitud').focus();
						return false;


			   }else  if(document.getElementById('numero_patente').value==""){

						fun_msj('Inserte el n&uacute;mero de patente');
						document.getElementById('numero_patente').focus();
						return false;


			  }else  if(document.getElementById('numero_expediente').value==""){

						fun_msj('Inserte el n&uacute;mero de expediente');
						document.getElementById('numero_expediente').focus();
						return false;


			  }else  if(document.getElementById('fecha_patente').value==""){

						fun_msj('Inserte la fecha de patente');
						document.getElementById('fecha_patente').focus();
						return false;

			  }else  if(document.getElementById('rif_cobrador').value==""){

						fun_msj('Inserte el cobrador');
						document.getElementById('rif_cobrador').focus();
						return false;

			  }else  if(document.getElementById('cuenta_grilla').value==0){

						fun_msj('Inserte una actividad econ&oacute;mica');
						document.getElementById('cuenta_grilla').focus();
						return false;


			   }else  if(document.getElementById('monto_mensual').value=="" || document.getElementById('monto_mensual').value=="0,00"){

						fun_msj('Inserte el monto mensual');
						document.getElementById('monto_mensual').focus();
						return false;



			}//fin else


}//fin function










function calcular_total_aforo(op){


acepto="no";
actv_num_afor = document.getElementById("actv_num_afor").value;
var str = actv_num_afor;
for(i=0; i<actv_num_afor.length; i++){
   if(str.charAt(i)==","){acepto="si";}
}//fin for
if(acepto=="si"){
  for(i=0; i<actv_num_afor.length; i++){str = str.replace('.','');}//fin for
  str = str.replace(',','.');
}//fin
var actv_num_afor = redondear(str,2);


acepto="no";
activ_mont_aforo = document.getElementById("activ_mont_aforo").value;
var str = activ_mont_aforo;
for(i=0; i<activ_mont_aforo.length; i++){
   if(str.charAt(i)==","){acepto="si";}
}//fin for
if(acepto=="si"){
  for(i=0; i<activ_mont_aforo.length; i++){str = str.replace('.','');}//fin for
  str = str.replace(',','.');
}//fin
var activ_mont_aforo = redondear(str,2);





c_total_aforo = eval(actv_num_afor) * eval(activ_mont_aforo);


document.getElementById("actv_num_afor").value    = actv_num_afor;
document.getElementById("activ_mont_aforo").value = activ_mont_aforo;
document.getElementById("total_aforo").value      = c_total_aforo;



moneda("activ_mont_aforo");
moneda("total_aforo");


}//fin function























function calcular_monto_segun_frecuencia(op){


							acepto="no";
							monto_mensual = document.getElementById("monto_mensual").value;
							var str = monto_mensual;
							for(i=0; i<monto_mensual.length; i++){
							   if(str.charAt(i)==","){acepto="si";}
							}//fin for
							if(acepto=="si"){
							  for(i=0; i<monto_mensual.length; i++){str = str.replace('.','');}//fin for
							  str = str.replace(',','.');
							}//fin
							var monto_mensual = redondear(str,2);




if(document.getElementById("frecuencia_pago_1").checked==true){ frecuencia = 1;}
if(document.getElementById("frecuencia_pago_2").checked==true){ frecuencia = 2;}
if(document.getElementById("frecuencia_pago_3").checked==true){ frecuencia = 3;}
if(document.getElementById("frecuencia_pago_4").checked==true){ frecuencia = 6;}
if(document.getElementById("frecuencia_pago_5").checked==true){ frecuencia = 12;}



            document.getElementById("monto_segun_fre").value = eval(monto_mensual) * eval(frecuencia);

            moneda("monto_segun_fre");


}//fin function

function valida_grilla_solicitud(){


         if(document.getElementById('num_12').value==""){

			fun_msj('Inserte un c&oacute;digo de actividad');
			document.getElementById('num_12').focus();
			return false;


   }


}//fin function
