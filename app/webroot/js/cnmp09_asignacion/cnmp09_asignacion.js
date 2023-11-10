function cnmp09_asignacion_valida_aca(){
  if(document.getElementById('select_1').value==""){
     fun_msj("Seleccione el c&oacute;digo de n&oacute;mina");
     document.getElementById('select_1').focus();
     return false;
  }else if (document.getElementById('select_trans').value==""){
     fun_msj("Seleccione el c&oacute;digo de la transacci&oacute;n");
     document.getElementById('select_trans').focus();
     return false;
  }


}//fin valida_aca



function cnmp09_asignacion_validando(){
	if(document.getElementById('select_1').value==""){
	     fun_msj("Seleccione el c&oacute;digo de n&oacute;mina");
	     document.getElementById('select_1').focus();
	     return false;
  	}else if (document.getElementById('select_trans').value==""){
	     fun_msj("Seleccione el c&oacute;digo de la transacci&oacute;n");
	     document.getElementById('select_trans').focus();
	     return false;
  	}


		document.getElementById('select_1').options[0].selected=true;
		document.getElementById('select_1').options[0].value="";
 		document.getElementById('select_1').options[0].text="";
		document.getElementById('cod_nomina').value="";
		document.getElementById('deno_nomina').value="";
  		document.getElementById('select_trans').options[0].selected=true;
  		document.getElementById('select_trans').options[0].value="";
 		document.getElementById('select_trans').options[0].text="";
  		document.getElementById('transaccion').value="";
 		document.getElementById('denominacion').value="";
 		document.getElementById('trans').value="";
 		document.getElementById('deno').value="";
 		if(document.getElementById('select_trans2')){
  			document.getElementById('select_trans2').options[0].selected=true;
	 		document.getElementById('select_trans2').options[0].value="";
	 		document.getElementById('select_trans2').options[0].text="";
  		}
 		//document.getElementById('numero').innerHTML="";


}//fin validando





function cnmp09_asignacion_recargar(){
  		if(document.getElementById('select_trans')){
  			document.getElementById('select_trans').options[0].selected=true;
	  		document.getElementById('select_trans').options[0].value="";
	 		document.getElementById('select_trans').options[0].text="";
  		}

  		document.getElementById('transaccion').value="";
 		document.getElementById('denominacion').value="";
 		document.getElementById('trans').value="";
 		document.getElementById('deno').value="";

 		if(document.getElementById('select_trans2')){
//			document.getElementById('select_trans2').options[0].selected=true;
	 		document.getElementById('select_trans2').value="";
//	 		document.getElementById('select_trans2').options[0].text="";
  		}

 		document.getElementById('numero').innerHTML="";
 		//document.getElementById('radio_si_no_1').checked=false;
 		//cument.getElementById('radio_si_no_2').checked=false;
  }


 function cnmp09_asignacion_limpia(){
 		document.getElementById('select_trans2').options[0].selected=true;
 		document.getElementById('trans').value="";
 		document.getElementById('deno').value="";
 		//document.getElementById('radio_si_no_1').checked=false;
 		//document.getElementById('radio_si_no_2').checked=false;

 }



