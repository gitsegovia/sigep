function cnmp07_valida(){




         if(document.getElementById('cod_tipo_nomina').value==''){

			fun_msj('Ingrese el tipo de nomina');
			document.getElementById('cod_tipo_nomina').focus();
			return false;

	}else if(document.getElementById('cod_cargo').value==''){

			fun_msj('Ingrese el codigo del cargo');
			document.getElementById('cod_cargo').focus();
			return false;


	}else if(document.getElementById('codigo_ficha2').value==''){

			fun_msj('Ingrese el codigo de la ficha');
			document.getElementById('codigo_ficha2').focus();
			return false;


	}else if(document.getElementById('tipo_transaccion_1').checked!=false ||  document.getElementById('tipo_transaccion_2').checked!=false){



       if(document.getElementById('cod_transaccion').value==''){

			fun_msj('Ingrese el codigo de la transacci&oacute;n');
			document.getElementById('cod_transaccion').focus();
			return false;


	}else if(document.getElementById('fecha_transaccion').value==''){

			fun_msj('Ingrese la fecha de la transacci&oacute;n');
			document.getElementById('fecha_transaccion').focus();
			return false;


	}else if(document.getElementById('monto_origina_deuda').value==''){

			fun_msj('Ingrese el monto original de la deuda');
			document.getElementById('monto_origina_deuda').focus();
			return false;


	}else if(document.getElementById('cantidad_original_deuda').value==''){

			fun_msj('Ingrese la cantidad original de cuotas');
			document.getElementById('cantidad_original_deuda').focus();
			return false;


    }else if(document.getElementById('monto_cuotas_a_cancelar').value==''){

			fun_msj('Ingrese el monto de cuotas a cancelar');
			document.getElementById('monto_cuotas_a_cancelar').focus();
			return false;


	}else if(document.getElementById('monto_cuotas_a_cancelar').value=='0,00' || document.getElementById('monto_cuotas_a_cancelar').value=='0.00'){

			fun_msj('Ingrese el monto de cuotas a cancelar');
			document.getElementById('monto_cuotas_a_cancelar').focus();
			return false;


	 }else if(document.getElementById('cantidad_de_cuotas_canceladas').value==''){

			fun_msj('Ingrese la cantidad de cuotas canceladas');
			document.getElementById('cantidad_de_cuotas_canceladas').focus();
			return false;


	 }else if(document.getElementById('monto_cuotas_a_cancelar').value==''){

			fun_msj('Ingrese monto de cuotas a cancelar');
			document.getElementById('monto_cuotas_a_cancelar').focus();
			return false;



	  }else if($('tipo_actualizacion_transaccion').value=='2'){


             var monto_cuota = reemplazarPC($("monto_cuotas_a_cancelar").value);
             var saldo       = reemplazarPC($("saldo").value);

             if($('saldo').value==''){
                saldo = 0;
             }

             if(saldo=='0.00' || saldo=='0'){
					fun_msj('Por favor Ingrese el monto del saldo');
					$('saldo').focus();
					return false;
		       }else if(eval(saldo) < eval(monto_cuota)){
		            //alert('SALDO:'+saldo+' MONTO CUOTA:'+monto_cuota);
                    fun_msj('Por favor el saldo no puede ser menor al monto de cuota');
					$('saldo').focus();
					return false;
		       }

       }//fin else



	}else{


	  fun_msj('Ingrese el tipo de transacci&oacute;n');
	  return false;



	}//fin else



}//fin function







function calcular_cuotas(){
pag='../../include/cfpp05/moneda.php?monto=';

if(document.getElementById('campo_modificar').value=="no"){
   document.getElementById('cantidad_de_cuotas_cancelar').value  =  eval(document.getElementById('cantidad_original_deuda').value)   -   eval(document.getElementById('cantidad_de_cuotas_canceladas').value);
}//fin


   if(document.getElementById('tipo_transaccion_2').checked==true){
		if(document.getElementById('campo_modificar').value=="no"){
             if(document.getElementById("monto_origina_deuda").readOnly == false){
				   monto_origina_deuda_input = reemplazarPC($("monto_origina_deuda").value);
				   var division = eval(monto_origina_deuda_input) / eval($('cantidad_original_deuda').value);
	               cargarMonto('monto_cuotas_a_cancelar',pag+redondear(division,2));
	               cargarMonto('saldo',pag+redondear(reemplazarPC($('monto_origina_deuda').value), 2));
              }//fin if

                if(document.getElementById("monto_origina_deuda").readOnly == false){
                    document.getElementById("saldo").readOnly = true;
                 }//fin if
         }//fin
   }else{



   }//fin else


}// fin function



function calcular_cuotas_respaldo(){


if(document.getElementById('campo_modificar').value=="no"){
   document.getElementById('cantidad_de_cuotas_cancelar').value  =  eval(document.getElementById('cantidad_original_deuda').value)   -   eval(document.getElementById('cantidad_de_cuotas_canceladas').value);
}//fin


   if(document.getElementById('tipo_transaccion_2').checked==true){
		if(document.getElementById('campo_modificar').value=="no"){
             if(document.getElementById("monto_origina_deuda").readOnly == false){
				   monto_origina_deuda_input = document.getElementById("monto_origina_deuda").value;
				   var str = monto_origina_deuda_input;for(i=0; i<monto_origina_deuda_input.length; i++){str = str.replace('.','');}str   = str.replace(',','.');
				   var monto_origina_deuda_input = str;
	               document.getElementById('monto_cuotas_a_cancelar').value  = redondear( eval(monto_origina_deuda_input) / eval(document.getElementById('cantidad_original_deuda').value), 2);
	               moneda('monto_cuotas_a_cancelar');
	               document.getElementById('saldo').value  = redondear( document.getElementById('monto_origina_deuda').value, 2);
	               moneda('saldo');
              }//fin if

                if(document.getElementById("monto_origina_deuda").readOnly == false){
                    document.getElementById("saldo").readOnly = true;
                 }//fin if
         }//fin
   }else{



   }//fin else


}// fin function
