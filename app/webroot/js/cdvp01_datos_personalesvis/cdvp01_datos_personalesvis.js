function valida_datos_visitante(){
 if(document.getElementById('vi_cedula').value==''){
			fun_msj('DEBE INGRESAR LA C&Eacute;DULA DE IDENTIDAD DEL VISITANTE');
			document.getElementById('vi_cedula').focus();
			return false;
	}else if(document.getElementById('vi_nombre').value==''){
			fun_msj('DEBE INGRESAR NOMBRES Y APELLIDOS');
			document.getElementById('vi_nombre').focus();
			return false;
	}else if(document.getElementById('sexo_1').checked == false && document.getElementById('sexo_2').checked == false){
			fun_msj('DEBE SELECCIONAR EL SEXO');
			document.getElementById('sexo_1').focus();
			return false;
	}else if(document.getElementById('vi_direccion').value==''){
			fun_msj('DEBE INGRESAR LA DIRECCI&Oacute;N');
			document.getElementById('vi_direccion').focus();
			return false;
	}else if(document.getElementById('ubicacionadmin_1').value==''){
			fun_msj('DEBE SELECCIONAR LA DIRECCI&Oacute;N SUPERIOR');
			setTimeout("fondoCampo('ubicacionadmin_1',2);", 3000);
			document.getElementById('ubicacionadmin_1').focus();
			return false;
	}else if(document.getElementById('ubicacionadmin_2').value==''){
			fun_msj('DEBE SELECCIONAR LA COORDINACI&Oacute;N');
			setTimeout("fondoCampo('ubicacionadmin_2',2);", 3000);
			document.getElementById('ubicacionadmin_2').focus();
			return false;
	}else if(document.getElementById('ubicacionadmin_3').value==''){
			fun_msj('DEBE SELECCIONAR LA SECRETAR&Iacute;A');
			setTimeout("fondoCampo('ubicacionadmin_3',2);", 3000);
			document.getElementById('ubicacionadmin_3').focus();
			return false;
	}else if(document.getElementById('ubicacionadmin_4').value==''){
			fun_msj('DEBE SELECCIONAR LA DIRECCI&Oacute;N');
			setTimeout("fondoCampo('ubicacionadmin_4',2);", 3000);
			document.getElementById('ubicacionadmin_4').focus();
			return false;
	}else if(document.getElementById('vi_observaciones').value==''){
			fun_msj('DEBE INGRESAR LAS OBSERVACIONES');
			document.getElementById('vi_observaciones').focus();
			return false;
	}else if(document.getElementById('vi_rif').value!=''){
		if(CompruebaDatos(document.getElementById('vi_rif').value)==false){
			fun_msj('POR FAVOR VERIFIQUE EL R.I.F.');
			document.getElementById('vi_rif').focus();
			return false;
		}else if(document.getElementById('vi_razon_social').value==''){
			fun_msj('DEBE INGRESAR LA RAZ&Oacute;N SOCIAL');
			document.getElementById('vi_razon_social').focus();
			return false;
		}
	}else if(document.getElementById('vi_razon_social').value!=''){
		if(document.getElementById('vi_rif').value==''){
			fun_msj('POR FAVOR INGRESE EL R.I.F.');
			document.getElementById('vi_rif').focus();
			return false;
		}
	}else return true;
}

function valida_agregar_vihistorial(){
	if(document.getElementById('ubicacionadmin_1').value==''){
			fun_msj('DEBE SELECCIONAR LA DIRECCI&Oacute;N SUPERIOR');
			setTimeout("fondoCampo('ubicacionadmin_1',2);", 3000);
			document.getElementById('ubicacionadmin_1').focus();
			return false;
	}else if(document.getElementById('ubicacionadmin_2').value==''){
			fun_msj('DEBE SELECCIONAR LA COORDINACI&Oacute;N');
			setTimeout("fondoCampo('ubicacionadmin_2',2);", 3000);
			document.getElementById('ubicacionadmin_2').focus();
			return false;
	}else if(document.getElementById('ubicacionadmin_3').value==''){
			fun_msj('DEBE SELECCIONAR LA SECRETAR&Iacute;A');
			setTimeout("fondoCampo('ubicacionadmin_3',2);", 3000);
			document.getElementById('ubicacionadmin_3').focus();
			return false;
	}else if(document.getElementById('ubicacionadmin_4').value==''){
			fun_msj('DEBE SELECCIONAR LA DIRECCI&Oacute;N');
			setTimeout("fondoCampo('ubicacionadmin_4',2);", 3000);
			document.getElementById('ubicacionadmin_4').focus();
			return false;
	}else if(document.getElementById('vi_observaciones').value==''){
			fun_msj('DEBE INGRESAR LAS OBSERVACIONES');
			document.getElementById('vi_observaciones').focus();
			return false;
	}else return true;
}

function valida_modificar_visitante(){
 if(document.getElementById('vi_cedula').value==''){
			fun_msj('DEBE INGRESAR LA C&Eacute;DULA DE IDENTIDAD DEL VISITANTE');
			document.getElementById('vi_cedula').focus();
			return false;
	}else if(document.getElementById('vi_nombre').value==''){
			fun_msj('DEBE INGRESAR NOMBRES Y APELLIDOS');
			document.getElementById('vi_nombre').focus();
			return false;
	}else if(document.getElementById('sexo_1').checked == false && document.getElementById('sexo_2').checked == false){
			fun_msj('DEBE SELECCIONAR EL SEXO');
			document.getElementById('sexo_1').focus();
			return false;
	}else if(document.getElementById('vi_direccion').value==''){
			fun_msj('DEBE INGRESAR LA DIRECCI&Oacute;N');
			document.getElementById('vi_direccion').focus();
			return false;
	}else if(document.getElementById('vi_rif').value!=''){
		if(CompruebaDatos(document.getElementById('vi_rif').value)==false){
			fun_msj('POR FAVOR VERIFIQUE EL R.I.F.');
			document.getElementById('vi_rif').focus();
			return false;
		}else if(document.getElementById('vi_razon_social').value==''){
			fun_msj('DEBE INGRESAR LA RAZ&Oacute;N SOCIAL');
			document.getElementById('vi_razon_social').focus();
			return false;
		}
	}else if(document.getElementById('vi_razon_social').value!=''){
		if(document.getElementById('vi_rif').value==''){
			fun_msj('POR FAVOR INGRESE EL R.I.F.');
			document.getElementById('vi_rif').focus();
			return false;
		}
	}else return true;
}

function valida_agregar_vihistorial_item(){
	if(document.getElementById('ubicacionadminitiva_1').value==''){
			fun_msj('DEBE SELECCIONAR LA DIRECCI&Oacute;N SUPERIOR');
			setTimeout("fondoCampo('ubicacionadminitiva_1',2);", 3000);
			document.getElementById('ubicacionadminitiva_1').focus();
			return false;
	}else if(document.getElementById('ubicacionadminitiva_2').value==''){
			fun_msj('DEBE SELECCIONAR LA COORDINACI&Oacute;N');
			setTimeout("fondoCampo('ubicacionadminitiva_2',2);", 3000);
			document.getElementById('ubicacionadminitiva_2').focus();
			return false;
	}else if(document.getElementById('ubicacionadminitiva_3').value==''){
			fun_msj('DEBE SELECCIONAR LA SECRETAR&Iacute;A');
			setTimeout("fondoCampo('ubicacionadminitiva_3',2);", 3000);
			document.getElementById('ubicacionadminitiva_3').focus();
			return false;
	}else if(document.getElementById('ubicacionadminitiva_4').value==''){
			fun_msj('DEBE SELECCIONAR LA DIRECCI&Oacute;N');
			setTimeout("fondoCampo('ubicacionadminitiva_4',2);", 3000);
			document.getElementById('ubicacionadminitiva_4').focus();
			return false;
	}else if(document.getElementById('item_observaciones').value==''){
			fun_msj('DEBE INGRESAR LAS OBSERVACIONES');
			document.getElementById('item_observaciones').focus();
			return false;
	}else return true;
}