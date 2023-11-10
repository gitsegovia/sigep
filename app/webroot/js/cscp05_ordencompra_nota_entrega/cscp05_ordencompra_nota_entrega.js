function diferenciaFecha2(CfechaNew, CFechaOrig){
	var fecha1 = new fecha( CfechaNew );
	var fecha2 = new fecha( CFechaOrig );

	var miFecha1 = new Date( fecha1.anio, fecha1.mes, fecha1.dia );
	var miFecha2 = new Date( fecha2.anio, fecha2.mes, fecha2.dia );

	var diferencia = miFecha1.getTime() - miFecha2.getTime();

	//alert(miFecha1.getTime()+' - '+miFecha2.getTime()+' = '+diferencia);

	if(diferencia < 0){
		return true;
	}else{
		return false;
	}


}



function diferenciaFecha(fecha2, fecha)
{
        var xMonth=fecha.substring(3, 5);
        var xDay=fecha.substring(0, 2);
        var xYear=fecha.substring(6,10);
        var yMonth=fecha2.substring(3, 5);
        var yDay=fecha2.substring(0, 2);
        var yYear=fecha2.substring(6,10);
        if (xYear> yYear)
        {
            return(true)
        }
        else
        {
          if (xYear == yYear)
          {
            if (xMonth> yMonth)
            {
                return(true)
            }
            else
            {
              if (xMonth == yMonth)
              {
                if (xDay> yDay)
                  return(true);
                else
                  return(false);
              }
              else
                return(false);
            }
          }
          else
            return(false);
        }
}




function fecha(cadena){
	var separador= "/";

	if(cadena.indexOf(separador) != -1){
		var posi1 = 0;
		var posi2 = cadena.indexOf(separador, posi1 + 1);
		var posi3 = cadena.indexOf(separador, posi2 + 1);
		this.dia = cadena.substring(posi1, posi2);
		this.mes = cadena.substring(posi2 + 1, posi3);
		this.anio = cadena.substring(posi3 + 1, cadena.length);
	}else{
		this.dia = 0;
		this.mes = 0;
		this.anio = 0;
	}
}

function cscp05_registro_ordencompra_pago_valida(){

if(verifica_cierre_ano_ejecucion('fecha_pago')==false){
	fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA DE LA NOTA DE ENTREGA NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
	return false;
}else{

	for(i=0;i<document.getElementById('cuenta_ii').value;i++){
		if(eval(document.getElementById('cantidad2_'+i).value) == 0 || document.getElementById('cantidad2_'+i).value==''){
			fun_msj('LA CANTIDAD ENTREGADA NO PUEDE ESTAR VACIA');
			document.getElementById('cantidad2_'+i).value=0;
			document.getElementById('cantidad2_'+i).focus();
			return false;
		}
	}

if(document.getElementById('ano_orden_compra_pago').value==''){

			fun_msj('Inserte el a&ntilde;o de la nota de entrega');
			document.getElementById('ano_orden_compra_pago').focus();
			return false;

	}else if(document.getElementById('numero_orden_compra_pago').value==''){

			fun_msj('Inserte el n&uacute;mero de la nota de entrega');
			document.getElementById('numero_orden_compra_pago').focus();
			return false;

	}else if(document.getElementById('fecha_pago').value==''){

			fun_msj('Inserte la fecha de la nota de entrega');
			document.getElementById('fecha_pago').focus();
			return false;

	}else if(diferenciaFecha(document.getElementById('fecha_pago').value, document.getElementById('fecha_ordencompra').value)){
		fun_msj('La fecha de entrega no puede ser menor a la fecha de la orden de compra');
			document.getElementById('fecha_pago').focus();
			return false;
	}else if(document.getElementById('observaciones').value==''){

			fun_msj('Inserte las observaci&oacute;nes de la nota de entrega');
			document.getElementById('observaciones').focus();
			return false;

	}else if(document.getElementById('cuenta_ii').value==0){

			fun_msj('Inserte un producto a la nota de entrega');
			document.getElementById('cuenta_ii').focus();
			return false;


	}//fin else

}// FIN ELSE VERIFICANDO ANO EJEC <> ANO FECHA DOC.

}//fin function

function valida_cantidades_entregadas(){
	var cantidad_solicitada = 0;
	var cantidad_entregada = 0;
	//alert('hola');
	for(i=0;i<document.getElementById('cuenta_i').value;i++){
		cantidad_solicitada += eval(document.getElementById('cantidad_'+i).value);
	}
	//alert('cantidad solicitada='+cantidad_solicitada);
	for(i=0;i<document.getElementById('cuenta_ii').value;i++){
		cantidad_entregada += eval(document.getElementById('cantidad2_'+i).value);
	}

	//alert('cantidad solicitada='+cantidad_solicitada+' | cantidad_entregada='+cantidad_entregada);

	if(eval(cantidad_solicitada)!= eval(cantidad_entregada)){
		document.getElementById('entrega_completa').value="2";
		document.getElementById('entrega_completa_radio_2').checked=true;


	}else{
	    document.getElementById('observaciones').value="ENTREGA COMPLETA";
		document.getElementById('entrega_completa').value="1";
		document.getElementById('entrega_completa_radio_1').checked=true;
	}

	//alert('entrega_completa='+document.getElementById('entrega_completa').value);

}

function valida_cantidades_por_entregar(){
	for(i=0;i<document.getElementById('cuenta_ii').value;i++){
		if(eval(document.getElementById('cantidad2_'+i).value) > eval(document.getElementById('cantidadorig_'+i).value)){
			fun_msj('LA CANTIDAD ENTREGADA NO PUEDE SER MAYOR A LA CANTIDAD SOLICITADA');
//			alert('cantidad original='+document.getElementById('cantidadorig_'+i).value+' cantidad por entregar='+document.getElementById('cantidad2_'+i).value);
			document.getElementById('cantidad2_'+i).value=document.getElementById('cantidadorig_'+i).value;
			document.getElementById('cantidad2_'+i).focus();

		}
	}
}