function valida_cimp06_firmas_acta(){
	if(document.getElementById('cimp_anio').value==''){
			fun_msj('El campo a&ntilde;o no puede estar vacio');
			document.getElementById('cimp_anio').focus();
			return false;
	}else if(document.getElementById('cimp_numero').value==''){
			fun_msj('El campo n&uacute;mero del acta no puede estar vacio');
			document.getElementById('cimp_numero').focus();
			return false;
	}else if(document.getElementById('cimp_func_primero').value==''){
			fun_msj('Por favor ingrese nombres y apellidos del primer firmante');
			document.getElementById('cimp_func_primero').focus();
			return false;
	}else if(document.getElementById('cimp_ced_primero').value==''){
			fun_msj('Por favor ingrese la c&eacute;dula del primer firmante');
			document.getElementById('cimp_ced_primero').focus();
			return false;
	}else if(document.getElementById('cimp_ced_primero').value.length<7){
			fun_msj('Por favor ingrese una c&eacute;dula v&aacute;lida para el primer firmante');
			document.getElementById('cimp_ced_primero').focus();
			return false;
	}else if(document.getElementById('cimp_cargo_primero').value==''){
			fun_msj('Por favor ingrese el cargo del primer firmante');
			document.getElementById('cimp_cargo_primero').focus();
			return false;
	}else if(document.getElementById('cimp_func_segundo').value==''){
			fun_msj('Por favor ingrese nombres y apellidos del segundo firmante');
			document.getElementById('cimp_func_segundo').focus();
			return false;
	}else if(document.getElementById('cimp_ced_segundo').value==''){
			fun_msj('Por favor ingrese la c&eacute;dula del segundo firmante');
			document.getElementById('cimp_ced_segundo').focus();
			return false;
	}else if(document.getElementById('cimp_ced_segundo').value.length<7){
			fun_msj('Por favor ingrese una c&eacute;dula v&aacute;lida para el segundo firmante');
			document.getElementById('cimp_ced_segundo').focus();
			return false;
	}else if(document.getElementById('cimp_cargo_segundo').value==''){
			fun_msj('Por favor ingrese el cargo del segundo firmante');
			document.getElementById('cimp_cargo_segundo').focus();
			return false;
	}else if(document.getElementById('cimp_func_tercer').value==''){
			fun_msj('Por favor ingrese nombres y apellidos del tercer firmante');
			document.getElementById('cimp_func_tercer').focus();
			return false;
	}else if(document.getElementById('cimp_ced_tercer').value==''){
			fun_msj('Por favor ingrese la c&eacute;dula del tercer firmante');
			document.getElementById('cimp_ced_tercer').focus();
			return false;
	}else if(document.getElementById('cimp_ced_tercer').value.length<7){
			fun_msj('Por favor ingrese una c&eacute;dula v&aacute;lida para el tercer firmante');
			document.getElementById('cimp_ced_tercer').focus();
			return false;
	}else if(document.getElementById('cimp_cargo_tercer').value==''){
			fun_msj('Por favor ingrese el cargo del tercer firmante');
			document.getElementById('cimp_cargo_tercer').focus();
			return false;
	}else if(document.getElementById('cimp_func_cuarto').value==''){
			fun_msj('Por favor ingrese nombres y apellidos del cuarto firmante');
			document.getElementById('cimp_func_cuarto').focus();
			return false;
	}else if(document.getElementById('cimp_ced_cuarto').value==''){
			fun_msj('Por favor ingrese la c&eacute;dula del cuarto firmante');
			document.getElementById('cimp_ced_cuarto').focus();
			return false;
	}else if(document.getElementById('cimp_ced_cuarto').value.length<7){
			fun_msj('Por favor ingrese una c&eacute;dula v&aacute;lida para el cuarto firmante');
			document.getElementById('cimp_ced_cuarto').focus();
			return false;
	}else if(document.getElementById('cimp_cargo_cuarto').value==''){
			fun_msj('Por favor ingrese el cargo del cuarto firmante');
			document.getElementById('cimp_cargo_cuarto').focus();
			return false;
	}
}//fin valida_cimp06_firmas_acta