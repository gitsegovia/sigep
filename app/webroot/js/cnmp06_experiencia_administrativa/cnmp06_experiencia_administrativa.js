function cnmp06_datos_permisos_valida(){

      if(document.getElementById('entidad_federal').value==""){

			fun_msj('Ingrese el Tipo de permiso');
			document.getElementById('entidad_federal').focus();
			return false;


}else if(document.getElementById('cargo_desempenado').value==""){

			fun_msj('Ingrese el nombre del cargo');
			document.getElementById('cargo_desempenado').focus();
			return false;


}else if(document.getElementById('fecha_ingreso').value==""){

			fun_msj('Ingrese la fecha de Salida');
			document.getElementById('fecha_ingreso').focus();
			return false;


}else if(document.getElementById('fecha_egreso').value==""){

			fun_msj('Ingrese la fecha de reintegro');
			document.getElementById('fecha_egreso').focus();
			return false;

}else if(diferenciaFecha(document.getElementById('fecha_egreso').value, document.getElementById('fecha_ingreso').value)){

           fun_msj('la Fecha de salida no debe ser mayor a la fecha de reintegro');
           return false;

}else if(document.getElementById('motivo_salida').value==""){

			fun_msj('Ingrese la observaci&oacute;n');
			document.getElementById('motivo_salida').focus();
			return false;


			}//fin else




}//fin function















function cnmp06_experiencia_administrativa_valida(){

      if(document.getElementById('entidad_federal').value==""){

			fun_msj('Ingrese el nombre de la instituci&oacute;n');
			document.getElementById('entidad_federal').focus();
			return false;


}else if(document.getElementById('cargo_desempenado').value==""){

			fun_msj('Ingrese el nombre del cargo');
			document.getElementById('cargo_desempenado').focus();
			return false;


}else if(document.getElementById('fecha_ingreso').value==""){

			fun_msj('Ingrese la fecha de Ingreso');
			document.getElementById('fecha_ingreso').focus();
			return false;


}else if(document.getElementById('fecha_egreso').value==""){

			fun_msj('Ingrese la fecha de egreso');
			document.getElementById('fecha_egreso').focus();
			return false;

}else if(diferenciaFecha(document.getElementById('fecha_egreso').value, document.getElementById('fecha_ingreso').value)){

           fun_msj('la Fecha de ingreso no debe ser mayor a la fecha de egreso');
           return false;

}else if(document.getElementById('motivo_salida').value==""){

			fun_msj('Ingrese el motivo de la salida');
			document.getElementById('motivo_salida').focus();
			return false;


			}//fin else




}//fin function