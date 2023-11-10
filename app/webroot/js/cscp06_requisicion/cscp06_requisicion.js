function valida_cscp06_requisicion(){

	if(document.getElementById('ano_requisicion').value==''){

				fun_msj('Inserte el a&ntilde;o de la Solicitud');
				document.getElementById('ano_requisicion').focus();
				return false;

	}else if(document.getElementById('numero_requisicion').value==''){

				fun_msj('Inserte El N&uacute;mero de la requisición');
				document.getElementById('numero_requisicion').focus();
				return false;

	}else if(document.getElementById('select_1').value==''){

				fun_msj('Seleccione la Direcci&oacute;n Superior');
				document.getElementById('select_1').focus();
				return false;

	}else if(document.getElementById('select_2').value == ""){

				fun_msj('Seleccione la Coordinac&oacute;n');
				document.getElementById('select_2').focus();
				return false;

	}else if(document.getElementById('select_3').value == ""){

				fun_msj('Seleccione la Secretaria');
				document.getElementById('select_3').focus();
				return false;

	}else if(document.getElementById('select_4').value == ""){

				fun_msj('Seleccione la Direcci&oacute;n');
				document.getElementById('select_4').focus();
				return false;

	}else if(document.getElementById('descripcion_bienes').value == ""){

				fun_msj('Inserte la Descripci&oacute;n de los Bienes o Servicios');
				document.getElementById('descripcion_bienes').focus();
				return false;

	}else if(document.getElementById('cantidad_estimada2').value == "" && document.getElementById('cuenta_i').value==0){

				fun_msj('Inserte la Cantidad');
				document.getElementById('cantidad_estimada2').focus();
				return false;

	}else if(document.getElementById('cuenta_i').value==0){

				fun_msj('Inserte un producto a la requisición');
				return false;
	
	}//FIN ELSE

}//fin function


//funcionando bien
function valida_cscp06_requisicion2(){
   var mydate=new Date()
   var year1=mydate.getYear()
   var year=mydate.getYear()
      if (year < 1000)
           year+=1901

	if(document.getElementById('select_1').value==''){

				fun_msj('Seleccione la Direcci&oacute;n Superior');
				document.getElementById('select_1').focus();
				return false;

	}else if(document.getElementById('select_2').value == ""){

				fun_msj('Seleccione la Coordinac&oacute;n');
				document.getElementById('select_2').focus();
				return false;


	}else if(document.getElementById('select_3').value == ""){

				fun_msj('Seleccione la Secretaria');
				document.getElementById('select_3').focus();
				return false;


	}else if(document.getElementById('select_4').value == ""){

				fun_msj('Seleccione la Direcci&oacute;n');
				document.getElementById('select_4').focus();
				return false;

	}else if(document.getElementById('cod_prod').value == ""){

				fun_msj('Seleccione el Producto');
				document.getElementById('cod_prod').focus();
				return false;


	}else if(document.getElementById('descripcion_bienes').value == ""){

				fun_msj('Inserte la Descripci&oacute;n de los Bienes o Servicios');
				document.getElementById('descripcion_bienes').focus();
				return false;



	}else if(document.getElementById('cantidad_estimada2').value == ""){

				fun_msj('Inserte la Cantidad');
				document.getElementById('cantidad_estimada2').focus();
				return false;



	}
}

//revisar
function valida_cscp06_requisicion3(){
   var mydate=new Date()
   var year1=mydate.getYear()
   var year=mydate.getYear()
      if (year < 1000)
           year+=1901

	if(document.getElementById('uso').value==""){

				fun_msj('Ingrese el Uso que se le Dar&aacute; a los Bienes O Servicios');
				document.getElementById('uso').focus();
				return false;

	}else if(document.getElementById('numero_requisicion').value==''){
			fun_msj('Por Favor seleccione el n&uacute;mero de requisici&oacute;n.');
			document.getElementById('numero_requisicion').focus();
			return false;
	}else if(document.getElementById('unidad_solicitante').value==''){
			fun_msj('Por Favor seleccione la unidad solicitante.');
			document.getElementById('unidad_solicitante').focus();
			return false;
	}else if(document.getElementById('condiciones_entrega').value == ""){

				fun_msj('Ingrese las Condiciones de Entrega...');
				document.getElementById('condiciones_entrega').focus();
				return false;

	}else if(document.getElementById('validez_oferta').value == ""){

				fun_msj('Ingrese la V&aacute;lidez de la Oferta...');
				document.getElementById('validez_oferta').focus();
				return false;

	}else if(document.getElementById('lapso_entrega').value == ""){

				fun_msj('Ingrese los Lapsos de Entrega...');
				document.getElementById('lapso_entrega').focus();
				return false;

	}else if(document.getElementById('aclaratorias').value == ""){

				fun_msj('Ingrese las Aclaratorias...');
				document.getElementById('aclaratorias').focus();
				return false;

	}else if(document.getElementById('base_legal').value == ""){

				fun_msj('Ingrese la Base Legal...');
				document.getElementById('base_legal').focus();
				return false;

	}
}

//revisar
function valida_cscp06_agregar_requisicion(){
	if(document.getElementById('cod_prod').value == ""){
		fun_msj('Seleccione el Producto');
		document.getElementById('cod_prod').focus();
		return false;

	}else if(document.getElementById('descripcion_bienes').value == ""){

		fun_msj('Inserte la Descripci&oacute;n de los Bienes o Servicios');
		document.getElementById('descripcion_bienes').focus();
		return false;

	}else if(document.getElementById('cantidad_estimada2').value == ""){

		fun_msj('Inserte la Cantidad');
		document.getElementById('cantidad_estimada2').focus();
		return false;
	}else{
		document.getElementById('descripcion_bienes').value = "";
		document.getElementById('cantidad_estimada2').value = "";
		document.getElementById('unidad_medida2').value = "";
		// document.getElementById('cod_snc3').value = "";
		document.getElementById('cod_prod').options[1].selected=true;
		document.getElementById('partida_producto2').innerHTML = "";
		return;
	}
}
