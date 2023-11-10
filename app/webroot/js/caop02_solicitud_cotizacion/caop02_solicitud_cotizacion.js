function valida_caop02_solicitud_cotizacion(){
   var mydate=new Date()
   var year1=mydate.getYear()
   var year=mydate.getYear()
      if (year < 1000)
           year+=1901

if(document.getElementById('ano').value==''){

			fun_msj('Inserte el a&ntilde;o de la Solicitud');
			document.getElementById('ano').focus();
			return false;

}else if(document.getElementById('numero2').value==''){

			fun_msj('Inserte El N&uacute;mero de Solicitud');
			document.getElementById('numero2').focus();
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

}else if(document.getElementById('numero').value==""){

			fun_msj('Inserte el N&uacute;mero de la Solicitud');
			document.getElementById('numero').focus();
			return false;

}else if(document.getElementById('fecha').value==""){

			fun_msj('Inserte la Fecha de la Solicitud');
			document.getElementById('fecha').focus();
			return false;

}else if(verifica_cierre_ano_ejecucion('fecha')==false){
			fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA DE LA SOLICITUD NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
			return false;
}else if(document.getElementById('uso').value==""){

			fun_msj('Ingrese el Uso que se le Dar&aacute; a los Bienes O Servicios');
			document.getElementById('uso').focus();
			return false;

}else if(document.getElementById('cuenta_i').value==0){

			fun_msj('Inserte un producto a la solicitud');
			return false;

}//FIN ELSE

}//fin funtion



function valida_caop02_solicitud_cotizacion2(){
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

function valida_caop02_solicitud_cotizacion3(){
   var mydate=new Date()
   var year1=mydate.getYear()
   var year=mydate.getYear()
      if (year < 1000)
           year+=1901

if(document.getElementById('uso').value==""){

			fun_msj('Ingrese el Uso que se le Dar&aacute; a los Bienes O Servicios');
			document.getElementById('uso').focus();
			return false;



}
}





function valida_caop02_agregar_solicitud(){
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
		document.getElementById('cod_snc3').value = "";
		document.getElementById('cod_prod').options[1].selected=true;
		document.getElementById('partida_producto2').innerHTML = "";
//		alert();
		return;
	}
}
