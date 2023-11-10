function cnmp02_valida_grados_pasos(){
	//alert("hola");
	if(document.getElementById("secuencia").value!='' && document.getElementById("paso1").value!=''){
		var num=eval(reemplazarPC(document.getElementById("secuencia").value));
		var monto=eval(reemplazarPC(document.getElementById("paso1").value));
		var j=1;
			for(i=1;i<16;i++){
				if(i==1){
					var monto_paso=monto;
					//document.getElementById("paso"+i).readOnly=true;
				}else{
					var monto_paso=monto+(num*j);
					/////////////////////////////aqui redondeo/////////////////////////////////
					 var str =  monto_paso+'';
					 for(x=0; x<str.length; x++){if(str.charAt(x)=="."){
					    monto_paso=str.substr(0,eval(x)+eval(6));
					    break;
					    }//fin if
					   }//fin for
					 var monto_paso = redondear(monto_paso,2);
					//////////////////////////////////////////////////////////////
					j=j+1;
				}
				pag="../../include/cfpp05/moneda.php?monto="+monto_paso;
	        	cargarMonto("paso"+i,pag);
	        	//document.getElementById("secuencia").readOnly=true;
	        	//document.getElementById("paso"+i).readOnly=true;
			}//fin for
	}else{
		//document.getElementById("paso1").value='';
	}//fin if

}//fin cnmp02_valida_grados_pasos


function cnmp02_limpia_grados_pasos(){
	 document.getElementById("secuencia").value='';
	  document.getElementById("secuencia").readOnly=false;
	   document.getElementById("paso1").readOnly=false;
	 document.getElementById("paso1").disabled="";
	 document.getElementById("cod_tabla").value='';
	 document.getElementById("denominacion").value='';
	for(i=1;i<16;i++){
	     document.getElementById("paso"+i).value='';
	}//fin for

}// cnmp02_limpia_grados_pasos



function cnmp02_valida_grados_pasos_2(){
	 if(eval(reemplazarPC(document.getElementById('paso2').value)) < eval(reemplazarPC(document.getElementById('paso1').value))){
		document.getElementById('paso2').value='';
		fun_msj('EL PASO 02 NO DEBE SER MENOR AL PASO 01');
		document.getElementById('paso2').focus();
		return false;
	}else if(eval(reemplazarPC(document.getElementById('paso3').value)) < eval(reemplazarPC(document.getElementById('paso2').value))){
		document.getElementById('paso3').value='';
		fun_msj('EL PASO 03 NO DEBE SER MENOR AL PASO 02');
		document.getElementById('paso2').focus();
		return false;
	}else if(eval(reemplazarPC(document.getElementById('paso4').value)) < eval(reemplazarPC(document.getElementById('paso3').value))){
		document.getElementById('paso4').value='';
		fun_msj('EL PASO 04 NO DEBE SER MENOR AL PASO 03');
		document.getElementById('paso2').focus();
		return false;
	}else if(eval(reemplazarPC(document.getElementById('paso5').value)) < eval(reemplazarPC(document.getElementById('paso4').value))){
		document.getElementById('paso5').value='';
		fun_msj('EL PASO 05 NO DEBE SER MENOR AL PASO 04');
		document.getElementById('paso2').focus();
		return false;
	}else if(eval(reemplazarPC(document.getElementById('paso6').value)) < eval(reemplazarPC(document.getElementById('paso5').value))){
		document.getElementById('paso6').value='';
		fun_msj('EL PASO 06 NO DEBE SER MENOR AL PASO 05');
		document.getElementById('paso2').focus();
		return false;
	}else if(eval(reemplazarPC(document.getElementById('paso7').value)) < eval(reemplazarPC(document.getElementById('paso6').value))){
		document.getElementById('paso7').value='';
		fun_msj('EL PASO 07 NO DEBE SER MENOR AL PASO 06');
		document.getElementById('paso2').focus();
		return false;
	}else if(eval(reemplazarPC(document.getElementById('paso8').value)) < eval(reemplazarPC(document.getElementById('paso7').value))){
		document.getElementById('paso8').value='';
		fun_msj('EL PASO 08 NO DEBE SER MENOR AL PASO 07');
		document.getElementById('paso2').focus();
		return false;
	}else if(eval(reemplazarPC(document.getElementById('paso9').value)) < eval(reemplazarPC(document.getElementById('paso8').value))){
		document.getElementById('paso9').value='';
		fun_msj('EL PASO 09 NO DEBE SER MENOR AL PASO 08');
		document.getElementById('paso2').focus();
		return false;
	}else if(eval(reemplazarPC(document.getElementById('paso10').value)) < eval(reemplazarPC(document.getElementById('paso9').value))){
		document.getElementById('paso10').value='';
		fun_msj('EL PASO 10 NO DEBE SER MENOR AL PASO 09');
		document.getElementById('paso2').focus();
		return false;
	}else if(eval(reemplazarPC(document.getElementById('paso11').value)) < eval(reemplazarPC(document.getElementById('paso10').value))){
		document.getElementById('paso11').value='';
		fun_msj('EL PASO 11 NO DEBE SER MENOR AL PASO 10');
		document.getElementById('paso2').focus();
		return false;
	}else if(eval(reemplazarPC(document.getElementById('paso12').value)) < eval(reemplazarPC(document.getElementById('paso11').value))){
		document.getElementById('paso12').value='';
		fun_msj('EL PASO 12 NO DEBE SER MENOR AL PASO 11');
		document.getElementById('paso2').focus();
		return false;
	}else if(eval(reemplazarPC(document.getElementById('paso13').value)) < eval(reemplazarPC(document.getElementById('paso12').value))){
		document.getElementById('paso13').value='';
		fun_msj('EL PASO 13 NO DEBE SER MENOR AL PASO 12');
		document.getElementById('paso2').focus();
		return false;
	}else if(eval(reemplazarPC(document.getElementById('paso14').value)) < eval(reemplazarPC(document.getElementById('paso13').value))){
		document.getElementById('paso14').value='';
		fun_msj('EL PASO 14 NO DEBE SER MENOR AL PASO 13');
		document.getElementById('paso2').focus();
		return false;
	}else if(eval(reemplazarPC(document.getElementById('paso15').value)) < eval(reemplazarPC(document.getElementById('paso14').value))){
		document.getElementById('paso15').value='';
		fun_msj('EL PASO 15 NO DEBE SER MENOR AL PASO 14');
		document.getElementById('paso2').focus();
		return false;
	}
}// cnmp02_limpia_grados_pasos




function cnmp09_frecuencia_pago_valida(){
		if(document.getElementById('cod_nomina').value==''){
			fun_msj('Seleccione el codigo de nomina');
			document.getElementById('cod_nomina').focus();
			return false;
		}else if(document.getElementById('co_transaccion_1').checked==false && document.getElementById('co_transaccion_2').checked==false){
			fun_msj('Seleccione el tipo de transacci&oacute;n');
			return false;
		}else if(document.getElementById('cod_transaccion').value==''){
			fun_msj('Seleccione el codigo de transacci&oacute;n');
			document.getElementById('cod_transaccion').focus();
			return false;
		}else if(document.getElementById('fre1').checked==false && document.getElementById('fre2').checked==false && document.getElementById('fre3').checked==false && document.getElementById('fre4').checked==false && document.getElementById('fre5').checked==false && document.getElementById('fre6').checked==false && document.getElementById('fre7').checked==false && document.getElementById('fre8').checked==false && document.getElementById('fre9').checked==false && document.getElementById('fre10').checked==false && document.getElementById('fre11').checked==false){
			fun_msj('Seleccione la frecuencia de pago');
			return false;
		}

}// cnmp09_frecuencia_pago_valida


function mostrar_frecuencia(cod_transaccion,frec){
   $('div_frecuencias').style.display='block';
   $('fre'+frec).checked=true;
   $('cod_transaccion').value=cod_transaccion;
   $('deno_transaccionx').value=$('deno'+cod_transaccion).innerHTML;
     $('botones_navegacion2').style.display='block';
     $('botones_navegacion1').style.display='none';
}





