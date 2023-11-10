function cscp04_registro_anticipo_ordencompra_uso_general_valida(){

							if(document.getElementById('ano_orden_compra_anticipo').value==''){

										fun_msj('Inserte el a&ntilde;o del anticipo');
										document.getElementById('ano_orden_compra_anticipo').focus();
										return false;

								}else if(document.getElementById('numero_orden_compra_anticipo').value==''){

										fun_msj('Inserte el n&uacute;mero del anticipo');
										document.getElementById('numero_orden_compra_anticipo').focus();
										return false;

								}else if(document.getElementById('fecha_anticipo').value==''){

										fun_msj('Inserte la fecha de orden del anticipo');
										document.getElementById('fecha_anticipo').focus();
										return false;

							    }else if(document.getElementById('incluye_iva_1').checked!=false || document.getElementById('incluye_iva_2').checked!=false){

							total = 0;
							var str = document.getElementById("TOTALINGRESOS").innerHTML;
							for(i=0; i<str.length; i++){
							    str = str.replace('.','');
							}//fin for
							str = str.replace(',','.');
							var total = redondear(str,2);

							if(document.getElementById('incluye_iva_1').checked!=false){opcion="Monto del Anticipo";}else{opcion="Monto del Anticipo";}
							aa = 0;
							var str = document.getElementById("monto_anticipo").value;
							for(i=0; i<str.length; i++){
							    str = str.replace('.','');
							}//fin for
							str = str.replace(',','.');
							var aa = redondear(str,2);



							bb = 0;
							var str = document.getElementById("monto_actual").value;
							for(i=0; i<str.length; i++){
							    str = str.replace('.','');
							}//fin for
							str = str.replace(',','.');
							var bb = redondear(str,2);


							bbbb = 0;
							var str = document.getElementById("saldo_orden").value;
							for(i=0; i<str.length; i++){
							    str = str.replace('.','');
							}//fin for
							str = str.replace(',','.');
							var bbbb = redondear(str,2);


							//bb = eval(bb) - eval(bbbb);


							cc = 0;
							var str = document.getElementById("monto_anticipo_input").value;
							for(i=0; i<str.length; i++){
							    str = str.replace('.','');
							}//fin for
							str = str.replace(',','.');
							var cc = redondear(str,2);



							dd = eval(aa) + eval(cc);


							val=0;

									          for(ii=0; ii<=document.getElementById('cuenta_i').value; ii++){

												    ax = document.getElementById('anticipo_'+ii).value;
													var str = ax;
													for(i=0; i<ax.length; i++){str = str.replace('.','');}//fin for
													str   = str.replace(',','.');
													var ax = redondear(str,2);

									                 if(ax==0){val=1;}

												}//fin for










                                            if(document.getElementById('tipo_anticipo_2').value==1){

							               opcion =  " la orden de compra";

							         }else if(document.getElementById('tipo_anticipo_2').value==2){

							                opcion =  " el contrato";

							         }






							       if(diferenciaFecha(document.getElementById('dia_actual').value, document.getElementById('fecha_anticipo').value)){

							                               fun_msj('La fecha del anticipo no puede ser mayor a la del dia');  return false;

							}else  if(diferenciaFecha(document.getElementById('fecha_anticipo').value, document.getElementById('fecha_documento_registro_contrato').value)){

							                               fun_msj('La fecha del anticipo no puede ser menor a la de '+opcion);  return false;

							}else if((val==1)){ fun_msj('Una partida no tiene monto');  return false;

 							}else if((total==0 && aa==0)){fun_msj('No a realizado ningun anticipo');  return false;

							}else if(total<aa){fun_msj('El total es menor al '+opcion); return false;

							}else if(total>aa){fun_msj('El total es mayor al '+opcion);  return false;

							}else if(dd>bbbb){fun_msj('EL ANTICIPO ES MAYOR AL SALDO DEL CONTRATO');  return false;

							}else if(dd>bb){fun_msj('ANTICIPO MAYOR A LO PERMITIDO POR EL CONTRATO');  return false;


							}else if(document.getElementById('observaciones').value==''){

										fun_msj('Inserte las observaci&oacute;nes del anticipo');
										document.getElementById('observaciones').focus();
										return false;

							}//fin else if


								}else{

								        fun_msj('Selecione el si incluye o no I.V.A el anticipo');
										return false;

								}//fin else
}//fin function


















function cscp04_registro_anticipo_ordencompra_uso_general_cargar_monto_id_editar(id_a){
							monto_anticipo_iva = 0;
							fr = document.getElementById("iva").value;
							str = fr;for(i=0; i<str.length; i++){str = str.replace('.','');}str = str.replace(',','.');  fr = str;
							if(document.getElementById('incluye_iva_1').checked!=false || document.getElementById('incluye_iva_2').checked!=false){
							total = 0;
							for(ii=0; ii<=document.getElementById('cuenta_i').value; ii++){
							 a = document.getElementById('anticipo_'+ii).value;
							var str = a;
							for(i=0; i<a.length; i++){str = str.replace('.','');}//fin for
							str   = str.replace(',','.');var a = redondear(str,2);
							total =  eval(total) + eval(a);
							}//fin for
							var str =  total+'';
							for(x=0; x<str.length; x++){
							 if(str.charAt(x)=="."){
							          total=str.substr(0,eval(x)+eval(6));
							       break;
							   }//fin if
							}//fin for
							var total = redondear(total,2);



							if(document.getElementById('incluye_iva_1').checked!=false){opcion="Monto del Anticipo";}else{opcion="Monto del Anticipo";}
							aa = document.getElementById("monto_anticipo").value;
							var str = aa;
							for(i=0; i<aa.length; i++){
							    str = str.replace('.','');
							}//fin for
							str = str.replace(',','.');
							var aa = redondear(str,2);

							//if(aa=="0,00"){aa="0";}

							monto_anticipo_input_aux = aa;

							/////////// DIFERENCIA ///////////
							          aux=0;
							          aux = eval(monto_anticipo_input_aux) - eval(total);
							if(aux>0){

							           aux = eval(aux) * eval(1);
							            a = document.getElementById("anticipo_"+0).value;
										var str = a;
										for(ii=0; ii<a.length; ii++){str = str.replace(',','.');}//fin for
							            str   = str.replace(',','.');
									    var a = redondear(str,2);
										a =  eval(a) - eval(aux);
										var a = redondear(a,2);
										document.getElementById("anticipo_"+0).value=a;
							            moneda("anticipo_"+0);
							            total = eval(total) + eval(aux);

							}else if(aux<0){

							            a = document.getElementById("anticipo_"+0).value;
										var str = a;
										for(ii=0; ii<a.length; ii++){str = str.replace(',','.');}//fin for
							            str   = str.replace(',','.');
									    var a = redondear(str,2);
										a =  eval(a) + eval(aux);
										var a = redondear(a,2);
										document.getElementById("anticipo_"+0).value=a;
							            moneda("anticipo_"+0);
							            total = eval(total) + eval(aux);


							}//fin else
							/////////// DIFERENCIA ///////////


							      if(total<=aa){ cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total);

							}else if(total>aa){fun_msj('El total es mayor al '+opcion); cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total);}//fin else


							}else{

							     document.getElementById(id_a).value="";
							     fun_msj('Selecione el si incluye o no I.V.A el anticipo');


							    }//fin else
}//fin function





















function cscp04_registro_anticipo_ordencompra_uso_general_cargar_monto_id_editar_2(id_a){
							monto_anticipo_iva = 0;
							fr = document.getElementById("iva").value;
							str = fr;for(i=0; i<str.length; i++){str = str.replace('.','');}str = str.replace(',','.');  fr = str;
							if(document.getElementById('incluye_iva_1').checked!=false || document.getElementById('incluye_iva_2').checked!=false){
							total = 0;
							for(ii=0; ii<=document.getElementById('cuenta_i').value; ii++){
							 a = document.getElementById('anticipo_'+ii).value;
							var str = a;
							for(i=0; i<a.length; i++){str = str.replace('.','');}//fin for
							str   = str.replace(',','.'); var a = redondear(str,2);
							total =  eval(total) + eval(a);
							var total = redondear(total,2);
							}//fin for

							var str =  total+'';


							for(x=0; x<str.length; x++){
							 if(str.charAt(x)=="."){
							          total=str.substr(0,eval(x)+eval(6));
							       break;
							   }//fin if
							}//fin for

							var total = redondear(total,2);

							document.getElementById("monto_anticipo").value = total; moneda('monto_anticipo');

							if(document.getElementById('incluye_iva_1').checked!=false){opcion="Monto del Anticipo";}else{opcion="Monto del Anticipo";}
							aa = document.getElementById("monto_anticipo").value;
							var str = aa;
							for(i=0; i<aa.length; i++){
							    str = str.replace('.','');
							}//fin for
							str = str.replace(',','.');
							var aa = redondear(str,2);


							monto_anticipo_input_aux = aa;




							bb = 0;
							var str = document.getElementById("monto_actual").value;
							for(i=0; i<str.length; i++){
							    str = str.replace('.','');
							}//fin for
							str = str.replace(',','.');
							var bb = redondear(str,2);




							porcentaje_input = ((eval(total)/eval(bb)) * eval(100));
							porcentaje_input = redondear(porcentaje_input,2);

							      if(total<=aa){
							         cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total);
							         document.getElementById("iva_anticipo").value = porcentaje_input; moneda('iva_anticipo');

							}else if(total>aa){
							         fun_msj('El total es mayor al '+opcion);
							         cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total);
							         document.getElementById("iva_anticipo").value = porcentaje_input; moneda('iva_anticipo');
							         }//fin else


							}else{

							     document.getElementById(id_a).value="";
							     fun_msj('Selecione el si incluye o no I.V.A el anticipo');


							    }//fin else
}//fin function






































function cscp04_registro_anticipo_ordencompra_uso_general_valida_consulta(){

  if(document.getElementById('concepto_anulacion').value == ""){
            fun_msj('Inserte el concepto de anulacion');
			document.getElementById('concepto_anulacion').focus();
			return false;
   }//fin if

}//fin function









function monto_anticipo_uso_general(op, pa, fr){


total = 0;

     document.getElementById("iva_anticipo").disabled='';
     document.getElementById("monto_anticipo").disabled=false;
     document.getElementById("iva").disabled=false;

        monto_actual  =  document.getElementById("monto_actual").value;
        str = monto_actual;for(i=0; i<str.length; i++){str = str.replace('.','');}str = str.replace(',','.');  monto_actual = str;

         monto_anticipo_input = (monto_actual  * (pa * 0.01));
         str = monto_anticipo_input;
         monto_anticipo_input = redondear(str,2);



         document.getElementById("iva").value=fr;
         str = fr;for(i=0; i<str.length; i++){str = str.replace('.','');}str = str.replace(',','.');  fr = str;
         document.getElementById("iva_anticipo").value=pa;
         document.getElementById("monto_anticipo").value=monto_anticipo_input;
         monto_anticipo_input2 = monto_anticipo_input;
         moneda("monto_anticipo");
         monto_anticipo_input_aux = monto_anticipo_input;
         monto_anticipo_iva = 0;


    for(i=0; i<=document.getElementById("cuenta_i").value; i++){
             document.getElementById("anticipo_"+i).disabled=false;
             monto_actual  =  document.getElementById("monto_actual_"+i).value;
             str = monto_actual;for(j=0; j<str.length; j++){str = str.replace('.','');}str = str.replace(',','.');
             monto_actual = redondear(str,2);

             monto_anticipo_input = (monto_actual  * (pa * 0.01));
            if(document.getElementById("dice_iva").value=="si"){monto_anticipo_iva = monto_anticipo_input * (fr*0.01);}
//////////////////////////////////////////////////////////////
		 var str =  monto_anticipo_iva+'';
		 for(x=0; x<str.length; x++){if(str.charAt(x)=="."){
		    monto_iva_var=str.substr(0,eval(x)+eval(6));
		    break;
		    }//fin if
		   }//fin for
		 var monto_anticipo_iva = redondear(monto_anticipo_iva,2);
//////////////////////////////////////////////////////////////
             str = eval(monto_anticipo_input) + eval(monto_anticipo_iva);

             monto_anticipo_input = redondear(str,2);
             document.getElementById("anticipo_"+i).value=monto_anticipo_input;
             moneda("anticipo_"+i);
 			a = document.getElementById("anticipo_"+i).value;
			var str = a;
			for(ii=0; ii<a.length; ii++){str = str.replace(',','.');}//fin for
            str   = str.replace(',','.');
			var a = redondear(str,2);
			total =  eval(total) + eval(monto_anticipo_input);
     }//fin for






/////////// DIFERENCIA ///////////
          aux=0;
          aux = eval(monto_anticipo_input2) - eval(total);
if(aux<0){

           aux = eval(aux) * eval(1);
            a = document.getElementById("anticipo_"+0).value;
			var str = a;
			for(ii=0; ii<a.length; ii++){str = str.replace(',','.');}//fin for
            str   = str.replace(',','.');
		    var a = redondear(str,2);
			a =  eval(a) - eval(0.01);
			var a = redondear(a,2);
			document.getElementById("anticipo_"+0).value=a;
            moneda("anticipo_"+0);
            total = eval(total) - eval(0.01);

}else if(aux>0){

            a = document.getElementById("anticipo_"+0).value;
			var str = a;
			for(ii=0; ii<a.length; ii++){str = str.replace(',','.');}//fin for
            str   = str.replace(',','.');
		    var a = redondear(str,2);
			a =  eval(a) + eval(0.01);
			var a = redondear(a,2);
			document.getElementById("anticipo_"+0).value=a;
            moneda("anticipo_"+0);
            total = eval(total) + eval(0.01);


}//fin else
/////////// DIFERENCIA ///////////


     cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total);


if(eval(total)>eval(monto_anticipo_input_aux)){fun_msj('FAVOR REVISE EL PORCENTAJE DE IVA'); }


}//fin function







