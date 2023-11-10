function validar(){
   if(document.getElementById('tipo_transaccion_1').checked==false && document.getElementById('tipo_transaccion_2').checked==false){
			fun_msj('Seleccione el tipo de transacci&oacute;n');
			return false;
	}else if(document.getElementById('codigo_transaccion').value==''){
			fun_msj('Ingrese el c&oacute;digo de la transacci&oacute;n');
			fondoCampo('codigo_transaccion',1);
			setTimeout("fondoCampo('codigo_transaccion',2);", 3000);
			document.getElementById('codigo_transaccion').focus();
			return false;
	}else if(document.getElementById('codigo_transaccion').value< 1){
	fun_msj("Ingrese un c&oacute;digo de transacci&oacute;n correcto ");
	        fondoCampo('codigo_transaccion',1);
			setTimeout("fondoCampo('codigo_transaccion',2);", 3000);
			document.getElementById('codigo_transaccion').focus();
			return false;
	}else if(document.getElementById('cnmp03transaccionesDenominaciont').value==''){
	        fun_msj("Ingrese la denominaci&oacute;n de la transacci&oacute;n");
	        fondoCampo('cnmp03transaccionesDenominaciont',1);
			setTimeout("fondoCampo('cnmp03transaccionesDenominaciont',2);", 3000);
			document.getElementById('cnmp03transaccionesDenominaciont').focus();
			return false;
	}else if(document.getElementById('cnmp03transaccionesDenominacionp').value==''){
	        fun_msj("Ingrese la denominaci&oacute;n de la transacci&oacute;n para el recibo");
	        fondoCampo('cnmp03transaccionesDenominacionp',1);
			setTimeout("fondoCampo('cnmp03transaccionesDenominacionp',2);", 3000);
			document.getElementById('cnmp03transaccionesDenominacionp').focus();
			return false;
	}else if(document.getElementById('uso_transaccion_1').checked==false && document.getElementById('uso_transaccion_2').checked==false && document.getElementById('uso_transaccion_3').checked==false && document.getElementById('uso_transaccion_4').checked==false && document.getElementById('uso_transaccion_5').checked==false && document.getElementById('uso_transaccion_6').checked==false && document.getElementById('uso_transaccion_7').checked==false && document.getElementById('uso_transaccion_77').checked==false && document.getElementById('uso_transaccion_8').checked==false){
			fun_msj('Seleccione el Uso de la transacci&oacute;n');
			return false;
	}else if((document.getElementById('uso_transaccion_6').checked==true || document.getElementById('uso_transaccion_8').checked==true) && document.getElementById('select_1').value==''){
			fun_msj('Seleccione el c&oacute;digo de la transacci&oacute;n padre');
			fondoCampo('select_1',1);
			setTimeout("fondoCampo('select_1',2);", 3000);
			document.getElementById('select_1').focus();
			return false;
	}else if(document.getElementById('tipo_transaccion_2').checked==true && document.getElementById('enlace_contable').value==''){
			fun_msj('Seleccione el c&oacute;digo del GRUPO CONTABLE - DEDUCCIONES');
			fondoCampo('enlace_contable',1);
			document.getElementById('enlace_contable').focus();
			return false;
	}
}
function fondoCampo(id,color){
    if(color==1)
       new Effect.Highlight(id);  //document.getElementById(id).style.background='RED';
    else
	    new Effect.Highlight(id);//document.getElementById(id).style.background='#FFF';
}

function escribir(){
    document.getElementById('codigo_transaccion').readonly=false;
    document.getElementById('cnmp03transaccionesDenominaciont').readonly=false;
    document.getElementById('cnmp03transaccionesDenominacionp').readonly=false;
    //document.getElementById('cod_transaccion_padre').readonly=false;
   // document.getElementById('cnmp03transaccionesDenominaciontp').readonly=false;
}
function TipoTransaccion2(){
    if(document.getElementById('tipo_transaccion_1').checked==true){
        document.getElementById('tipo_asignacion_1').checked=true;
        document.getElementById('tipo_asignacion_3').disabled="";
        document.getElementById('tipo_asignacion_2').disabled="";
        document.getElementById('tipo_asignacion_1').disabled="";
        document.getElementById('tipo_asignacion_4').disabled="";
        document.getElementById('codigo_transaccion').disabled="";
	    document.getElementById('cnmp03transaccionesDenominaciont').disabled="";
	    document.getElementById('cnmp03transaccionesDenominacionp').disabled="";
	    //uso transaccion asignacion
	    document.getElementById('uso_transaccion_1').disabled="";
	    document.getElementById('uso_transaccion_2').disabled="";
	    document.getElementById('uso_transaccion_7').disabled="";
	    document.getElementById('uso_transaccion_77').disabled="disabled";
	    for(i=3;i<7;i++){
	    document.getElementById('uso_transaccion_'+i).disabled="disabled";
	    document.getElementById('uso_transaccion_'+i).checked=false;
	    }
	    document.getElementById('uso_transaccion_8').disabled="disabled";
	    document.getElementById('uso_transaccion_8').checked=false;

    }else if(document.getElementById('tipo_transaccion_2').checked==true){
        document.getElementById('tipo_asignacion_4').checked=true;
        document.getElementById('tipo_asignacion_4').disabled="";
        document.getElementById('tipo_asignacion_3').disabled="disabled";
        document.getElementById('tipo_asignacion_2').disabled="disabled";
        document.getElementById('tipo_asignacion_1').disabled="disabled";
        document.getElementById('codigo_transaccion').disabled="";
	    document.getElementById('cnmp03transaccionesDenominaciont').disabled="";
	    document.getElementById('cnmp03transaccionesDenominacionp').disabled="";
	    //uso transaccion deduccion
	    document.getElementById('uso_transaccion_1').disabled="disabled";
	    document.getElementById('uso_transaccion_2').disabled="disabled";
	    document.getElementById('uso_transaccion_7').disabled="disabled";
	    document.getElementById('uso_transaccion_1').checked=false;
	    document.getElementById('uso_transaccion_2').checked=false;
	    document.getElementById('uso_transaccion_7').checked=false;
	    document.getElementById('uso_transaccion_77').disabled="";
	    for(i=3;i<7;i++){
	    document.getElementById('uso_transaccion_'+i).disabled="";
	    }
	    document.getElementById('uso_transaccion_8').disabled="";

    }

}



function comprobar_tipo(){
	if(document.getElementById('tipo_transaccion_1').checked==false && document.getElementById('tipo_transaccion_2').checked==false){
		 fun_msj("Seleccione el tipo de transacci&oacute;n");
	}
}//fin comprobar_tipo



function UsoTransaccion(){
     if($('uso_transaccion_6').checked==true || $('uso_transaccion_8').checked==true){
          $('TP1').style.display='block';
          $('TP2').style.display='block';
          $('TP3').style.display='block';
         //--$('cod_tipo_transaccion_padre_1').disabled="disabled";
         // $('tipo_actualizacion_1').disabled="disabled";
          //$('tipo_actualizacion_2').checked=true;
          if($('uso_transaccion_6').checked==true){
				if($('tipo_transaccion_padre_1')){
          	       $('tipo_transaccion_padre_1').disabled="disabled";
          	       $('tipo_transaccion_padre_2').disabled=false;
          	       $('tipo_transaccion_padre_2').checked=true;
          	       ver_documento('/cnmp03transacciones/select_tipo_trans/2','TP2');
                }
          }
          if($('uso_transaccion_8').checked==true){
              if($('tipo_transaccion_padre_1')){
          	       $('tipo_transaccion_padre_2').disabled="disabled";
          	       $('tipo_transaccion_padre_1').disabled=false;
          	       $('tipo_transaccion_padre_1').checked=true;
          	       ver_documento('/cnmp03transacciones/select_tipo_trans/1','TP2');
              }
          }



         //--$('tipo_padre1').style.display='table-row';
         //$('tipo_padre2').style.display='table-row';

         //--$('tipo_padre0').style.display='table-row';
     }else{

           if($('uso_transaccion_5').checked==true){
                 $('tipo_actualizacion_1').checked=false;
                 $('tipo_actualizacion_2').checked=true;
           }//fin uf


         //$('tipo_padre1').style.display='none';
         //$('tipo_padre2').style.display='none';
         $('TP1').style.display='none';
         $('TP2').style.display='none';
         $('TP3').style.display='none';
         //$('tipo_padre0').style.display='none';
     }
}

