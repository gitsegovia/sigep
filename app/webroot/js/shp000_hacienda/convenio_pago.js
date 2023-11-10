function funcion_valida_convenio_1(){

         if(document.getElementById('fecha_convenimiento').value==''){

			fun_msj('Ingrese la fecha de convenimiento');
			document.getElementById('fecha_convenimiento').focus();
			return false;

    }else if($('monto_convenimiento').value==""){
			fun_msj('Ingrese el monto de convenimiento');
			document.getElementById('monto_convenimiento').focus();
			return false;

    }else{

            monto_convenimiento   =  retornar_valor_calculo($("monto_convenimiento").value);

            if(eval(monto_convenimiento)==eval(0)){
				fun_msj('Ingrese el monto de convenimiento');
				document.getElementById('monto_convenimiento').focus();
				return false;
			}
    }


}//fin function




function calcular_deuda_pendiente2(id){


	monto_impuesto        =  retornar_valor_calculo($("monto_deuda_"+id).value);
	monto_convenimiento   =  retornar_valor_calculo($("monto_convenimiento_"+id).value);
	resultado = (eval(monto_impuesto)-eval(monto_convenimiento));
	if(eval(resultado)<0){
      resultado = monto_impuesto;
      $("monto_convenimiento_"+id).value="";
      fun_msj('El monto del convenimiento no puede ser mayor al monto de la deuda');
    }else if(monto_convenimiento==""){
      resultado = monto_impuesto;
      fun_msj('inserte El monto del convenimiento');
	}else{
	  moneda('monto_convenimiento_'+id);
	}
    $("deuda_pendiente_"+id).value=redondear(resultado, 2);
	moneda("deuda_pendiente_"+id);


}





function calcular_deuda_pendiente(){
	monto_impuesto        =  retornar_valor_calculo($("monto_deuda").value);
	monto_convenimiento   =  retornar_valor_calculo($("monto_convenimiento").value);
	resultado = (eval(monto_impuesto)-eval(monto_convenimiento));
	if(eval(resultado)<0){
      resultado = monto_impuesto;
      $("monto_convenimiento").value="";
      fun_msj('El monto del convenimiento no puede ser mayor al monto de la deuda');
    }else if(monto_convenimiento==""){
      resultado = monto_impuesto;
      fun_msj('inserte El monto del convenimiento');
	}else{
	  moneda('monto_convenimiento');
	}
    $("deuda_pendiente").value=redondear(resultado, 2);
	moneda("deuda_pendiente");
}//fin function










function verifica_radio(){

contador =  eval($("contador_filas").value);
op       = 0;
	for(ij=0; ij<contador; ij++){
        ano_declaracion    =  $("id1_"+ij).value;
        numero_declaracion =  $("id2_"+ij).value;
        id = "pasar"+ij+"_1";
        monto_deuda        =  retornar_valor_calculo($("deuda_vigente2_"+ano_declaracion+"_"+numero_declaracion).innerHTML);
        if(eval(monto_deuda)!=eval(0)){
              if(op==0){
                if($(id)){$(id).disabled = false;}
                op = 1;
              }else{
                if($(id)){$(id).disabled = true; $(id).checked = false;}
              }

        }else{
                 if($(id)){$(id).disabled = true; $(id).checked = false;}
        }
	 }//fin for


}//fin function












function eliminar_verifica_radio(){

contador =  eval($("contador_filas").value);
op       = 0;
	for(ij=0; ij<contador; ij++){
        ano_declaracion    =  $("id1_"+ij).value;
        numero_declaracion =  $("id2_"+ij).value;
        id = "pasar"+ij+"_1";
        $(id).checked = false;
	 }//fin for

$("pasar_a_convenio").innerHTML="";

}//fin function