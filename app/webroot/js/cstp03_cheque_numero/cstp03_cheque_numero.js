function valida_cstp03_cheque_numero_(){

 if(document.getElementById('codigo_sucursal').value==''){

			fun_msj('El c&oacute;digo de la sucursal no puede ser vac&iacute;a');
			document.getElementById('codigo_sucursal').focus();
			return false;

	}
	 if(document.getElementById('codigo_sucursal').value.length<4){

			fun_msj('El c&oacute;digo de la sucursal no puede ser menor de 4 digitos');
			document.getElementById('codigo_sucursal').focus();
			return false;

	} if(document.getElementById('select_cuenta').value==''){

			fun_msj('La cuenta bancaria no puede estar vac&iacute;a, Seleccione una cuenta');
			document.getElementById('select_cuenta').focus();
			return false;

	}else if(document.getElementById('select_cuenta').value=='no'){

			fun_msj('No hay registros de cuentas bancarias para esta sucursal');
			document.getElementById('select_cuenta').focus();
			return false;

	}
	var mydate=new Date()
   var year1=mydate.getYear()
   var year=mydate.getYear()
      if (year < 1000)
           year+=1901

           if(document.getElementById('ano').value==''){

			fun_msj('Inserte el a&ntilde;o');
			document.getElementById('ano').focus();
			return false;

	}else if(document.getElementById('ano').value.length<4){

			fun_msj('Inserte un a&ntilde;o correcto');
			document.getElementById('ano').focus();
			return false;


	}else if(document.getElementById('ano').value< 2000 || document.getElementById('ano').value>year){
	fun_msj("Inserte un a&ntilde;o correcto ");
			document.getElementById('ano').focus();
			return false;
	}if(document.getElementById('comienzo_cheque').value==''){

			fun_msj('Inserte el n&uacute;mero de comienzo del Cheque');
			document.getElementById('comienzo_cheque').focus();
			return false;

	}



}//fin funtion

function valida_cstp03_cheque_numero_nuevo(){
	if(document.getElementById('cod_entidad_bancaria').value==''){

			fun_msj('Por favor, Debe Seleccionar la Entidad Bancaria');
			document.getElementById('cod_entidad_bancaria').focus();
			return false;

	}if(document.getElementById('select_2').value==''){

			fun_msj('Por favor, Debe Seleccionar la Sucursal Bancaria');
			document.getElementById('select_2').focus();
			return false;

	}if(document.getElementById('select_3').value==''){

			fun_msj('Por favor, Debe Seleccionar una Cuenta Bancaria');
			document.getElementById('select_3').focus();
			return false;

	}if(document.getElementById('nuevo_numero').value==''){

			fun_msj('Por Favor, debe ingresar el numero del cheque a Generar');
			document.getElementById('nuevo_numero').focus();
			return false;

	}
}

function cstp03_cheque_agregar_nuevonum(){
	if(document.getElementById('cod_entidad_bancaria').value==''){

			fun_msj('Debe seleccionar la Entidad Bancaria');
			document.getElementById('cod_entidad_bancaria').focus();
			return false;

	}if(document.getElementById('select_2').value==''){

			fun_msj('Debe seleccionar la Sucursal Bancaria');
			document.getElementById('select_2').focus();
			return false;

	}if(document.getElementById('select_3').value==''){

			fun_msj('Debe seleccionar una Cuenta Bancaria');
			document.getElementById('select_3').focus();
			return false;

	}if(document.getElementById('select_3').value=='no'){

			fun_msj('Atencion, No hay registros seleccionados en la Cuenta Bancaria');
			document.getElementById('select_3').focus();
			return false;

	}
}



function valida_cstp03_cheque_numero_nuevo_continuo(){
	if(document.getElementById('cod_entidad_bancaria').value==''){

			fun_msj('Por favor, Debe Seleccionar la Entidad Bancaria');
			document.getElementById('cod_entidad_bancaria').focus();
			return false;

	}if(document.getElementById('select_2').value==''){

			fun_msj('Por favor, Debe Seleccionar la Sucursal Bancaria');
			document.getElementById('select_2').focus();
			return false;

	}if(document.getElementById('select_3').value==''){

			fun_msj('Por favor, Debe Seleccionar una Cuenta Bancaria');
			document.getElementById('select_3').focus();
			return false;

	}if(document.getElementById('nuevo_numero_desde').value==''){

			fun_msj('Por Favor, debe ingresar desde que numero se va a Generar el Cheque');
			document.getElementById('nuevo_numero_desde').focus();
			return false;

	}if(document.getElementById('nuevo_numero_hasta').value==''){

			fun_msj('Por Favor, debe ingresar hasta que numero se va a Generar el Cheque');
			document.getElementById('nuevo_numero_hasta').focus();
			return false;

	}

	if(eval(document.getElementById('nuevo_numero_desde').value) >= eval(document.getElementById('nuevo_numero_hasta').value)){
			fun_msj('El cheque inicial (desde) es mayor que el numero de cheque final (hasta), revise por favor');
			document.getElementById('nuevo_numero_hasta').focus();
			return false;
	}
}



function seleccionar_cheque(ent, suc, cuenta, consecutivo, num_cheque, numero_mascara,  i){
	document.getElementById('actual_'+i).innerHTML="<center><font color='000'>"+numero_mascara+"&nbsp;&nbsp;</font></center>";
	document.getElementById('sinutilizar_'+i).innerHTML="&nbsp;";
	document.getElementById('seleccionado_'+i).innerHTML="<font color='000'><b>X</b>";
	ver_documento('/cstp03_cheque_numero/seleccionarcheque/'+ent+'/'+suc+'/'+cuenta+'/'+consecutivo+'/'+num_cheque, 'capa_cheque');
}
