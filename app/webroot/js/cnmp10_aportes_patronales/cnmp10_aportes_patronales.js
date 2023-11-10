function valida_cnmp10_aportes_patronales(){

   if(document.getElementById('select_1').value==''){

	        fun_msj('Por Favor seleccione el tipo de n&oacute;mina');
			document.getElementById('select_1').focus();
			return false;

	}if(document.getElementById('select_2').value==''){

	        fun_msj('Por Favor seleccione la Transacci&oacute;n del aporte del Trabajador');
			document.getElementById('select_2').focus();
			return false;

	}if(document.getElementById('select_3').value==''){

	        fun_msj('No existe c&oacute;digo de Transacci&oacute;n del aporte Patronal');
			document.getElementById('select_3').focus();
			return false;

	}if(document.getElementById('porcentaje_patronal').value==''){

	        fun_msj('Por Favor ingrese el porcentaje');
			document.getElementById('porcentaje_patronal').focus();
			return false;

	}if(document.getElementById('cuarta_semana_patronal').value==''){

	        fun_msj('Por Favor ingrese el Tope de la Cuarta Semana');
			document.getElementById('cuarta_semana_patronal').focus();
			return false;

	}if(document.getElementById('quinta_semana_patronal').value==''){

	        fun_msj('Por Favor ingrese el Tope de la Quinta Semana');
			document.getElementById('quinta_semana_patronal').focus();
			return false;

	}

}//valida_cnmp10_aportes_patronales

function aportes_patronales_modificado(){
	if(document.getElementById('porcentaje_patrono').value=='0,00'){

	        fun_msj('Atenci&oacute;n, el porcentaje esta en 0,00');
			document.getElementById('porcentaje_patrono').focus();
			return false;

	}if(document.getElementById('tope_cuarta_semana').value=='0,00'){

	        fun_msj('Atenci&oacute;n, el Tope de la Cuarta Semana esta en 0,00');
			document.getElementById('tope_cuarta_semana').focus();
			return false;

	}if(document.getElementById('tope_quinta_semana').value=='0,00'){

	        fun_msj('Atenci&oacute;n, el Tope de la Quinta Semana esta en 0,00');
			document.getElementById('tope_quinta_semana').focus();
			return false;

	}
}//aportes_patronales_modificado