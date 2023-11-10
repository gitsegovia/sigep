function caspp01_documento_origen(){

//alert("hola");

	 if(document.getElementById('tipo_documento').value==''){
            fun_msj('seleccione el tipo de documento');
            document.getElementById('tipo_documento').focus();
             return false;
	}else if(document.getElementById('codigo').value==''){
            fun_msj('seleccione el n&uacute;mero del documento');
            document.getElementById('codigo').focus();
             return false;
	}else if(document.getElementById('cedula_rif').value==''){
			fun_msj('debe ingresar el rif o cedula');
			document.getElementById('cedula_rif').focus();
             return false;
	}else if(document.getElementById('beneficiario').value==''){
            fun_msj('debe ingresar el beneficiario');
            document.getElementById('beneficiario').focus();
             return false;
	}else if(document.getElementById('selectt_1').value==''){
            fun_msj('seleccione el c&oacute;digo de direcci&oacute;n superior');
            document.getElementById('selectt_1').focus();
             return false;
	}else if(document.getElementById('selectt_2').value==''){
            fun_msj('seleccione el c&oacute;digo de la coordinaci&oacute;n');
            document.getElementById('selectt_2').focus();
             return false;
	}else if(document.getElementById('selectt_3').value==''){
            fun_msj('seleccione el c&oacute;digo de la secretaria');
            document.getElementById('selectt_3').focus();
             return false;
	}else if(document.getElementById('selectt_4').value==''){
            fun_msj('seleccione el c&oacute;digo de la direcci&oacute;n');
            document.getElementById('selectt_4').focus();
             return false;
	}else if(document.getElementById('monto').value==''){
            fun_msj('ingrese el monto');
            document.getElementById('monto').focus();
             return false;
	}else if(document.getElementById('observacion').value==''){
            fun_msj('debe ingresar las observaciones');
            document.getElementById('observacion').focus();
             return false;
	}


}// fin casd01_reporte_solicitudes


function solo_menores_siete(e){

	 /**var key=tecla ? evt.which:evt.keyCode;
	 return (key==13 || (key>=48 && key<=57));*/

	 tecla_codigo = (document.all) ? e.keyCode : e.which;
	if(tecla_codigo==8 || tecla_codigo==0 || tecla_codigo==13)return true;
	patron =/[1-2-3-4-5-6-7\-]/;
	tecla_valor = String.fromCharCode(tecla_codigo);
	return patron.test(tecla_valor);

}//fin solo_menores_siete


function valida_menor_igual_59(){
if(eval(document.getElementById("minutos").value) > 59 || eval(document.getElementById("minutos").value) <= 0){
		fun_msj('ingrese una cantidad de minutos menor o igual a 59');
		document.getElementById("minutos").value="";
		document.getElementById('minutos').focus();
		return false;

}
}//valida_menor_igual_59


function valida_menor_igual_59_2(){
if(eval(document.getElementById("minutoss").value) > 59 || eval(document.getElementById("minutoss").value) <= 0){
		fun_msj('ingrese una cantidad de minutos menor o igual a 59');
		document.getElementById("minutoss").value="";
		document.getElementById('minutoss').focus();
		return false;

}
}//valida_menor_igual_59
