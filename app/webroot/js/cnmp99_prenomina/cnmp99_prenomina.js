
  function prenomina(){
    if($('in_cod_tipo_nomina').value==''){

  	 fun_msj('Seleccione el tipo de n&oacute;mina');
	 return false;
    }else if($('correspondientes').value==''){

  	 fun_msj('ingrese Pagos correspondientes a:');
	 return false;
    }else if($('frecuencia_pago_1').checked==false || $('frecuencia_pago_2').checked==false || $('frecuencia_pago_3').checked==false || $('frecuencia_pago_4').checked==false || $('frecuencia_pago_5').checked==false || $('frecuencia_pago_7').checked==false || $('frecuencia_pago_8').checked==false || $('frecuencia_pago_10').checked==false){

  	 fun_msj('Seleccione la Frecuencia');
	 return false;

  }
}


