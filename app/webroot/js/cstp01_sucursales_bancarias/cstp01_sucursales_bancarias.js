function valida_cstp01_sucursales_bancarias_(){


    if(document.getElementById('cod_sucursal').value==''){

			fun_msj('El c&oacute;digo de la sucursal no puede estar vac&iacute;o');
			document.getElementById('cod_sucursal').focus();
			return false;

	}
	 if(document.getElementById('cod_sucursal').value.length<4){

			fun_msj('El c&oacute;digo de la sucursal no puede ser menor de 4 digitos');
			document.getElementById('cod_sucursal').focus();
			return false;

	} if(document.getElementById('deno_sucursal').value==''){

			fun_msj('Debe ingresar la denominaci&oacute;n de la sucursal bancaria');
			document.getElementById('deno_sucursal').focus();
			return false;

	}
}//fin funtion

function cstp01_sucursales_bancarias_modificar(){
	document.getElementById('b_modificar').disabled=true;
	document.getElementById('b_guardar').disabled=false;
	document.getElementById('denominacion_sucursal').readOnly=false;
	fun_msj2('Puede proceder a modificar la Sucursal Bancaria');
}

