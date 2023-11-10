function valida_cfpp09_metas_sector(){

   if(document.getElementById('select_1').value==''){

			fun_msj('Seleccione Sector');
			document.getElementById('select_1').focus();
			return false;
	}else if(document.getElementById('metas').value==''){
	fun_msj("Inserte la Descripci&oacute;n de la Meta del Sector ");
			document.getElementById('metas').focus();
			return false;
	}else if(document.getElementById('unidad_medida').value==''){

			fun_msj('Inserte la Unidad de Medida de la Meta del Sector');
			document.getElementById('unidad_medida').focus();
			return false;
	}else if(document.getElementById('cantidad').value==''){

			fun_msj('Inserte la Cantidad');
			document.getElementById('cantidad').focus();
			return false;
	}else if(document.getElementById('ano_formulacion').value==''){

			fun_msj('Ingrese por favor el ejercicio fiscal');
			document.getElementById('ano_formulacion').focus();
			return false;
	}
}//fin funtion

function valida_cfpp09_metas_sector2(){

  if(document.getElementById('metas').value==''){
	fun_msj("Inserte la Descripci&oacute;n de la Meta del Sector ");
			document.getElementById('metas').focus();
			return false;
	}else if(document.getElementById('unidad_medida').value==''){

			fun_msj('Inserte la Unidad de Medida de la Meta del Sector');
			document.getElementById('unidad_medida').focus();
			return false;
	}else if(document.getElementById('cantidad').value==''){

			fun_msj('Inserte la Cantidad');
			document.getElementById('cantidad').focus();
			return false;
	}
}//fin funtion
