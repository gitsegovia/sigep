function cnmp06_datos_amonestaciones_valida(){



if(document.getElementById('cod_amonestacion').value==""){

            fun_msj('Ingrese el c&oacute;digo de la amonestaci&oacute;n');
			document.getElementById('cod_amonestacion').focus();
			return false;

}else if(document.getElementById('nombre_apellido').value==""){

            fun_msj('Ingrese el nombre y apellido');
			document.getElementById('nombre_apellido').focus();
			return false;


}else if(document.getElementById('fecha_amonestacion').value==""){

            fun_msj('Ingrese la fecha de amonestaci&oacute;n');
			document.getElementById('fecha_amonestacion').focus();
			return false;



}else if(document.getElementById('cargo_ocupado').value==""){

            fun_msj('Ingrese el cargo que ocupa');
			document.getElementById('cargo_ocupado').focus();
			return false;


}else if(document.getElementById('concepto').value==""){

            fun_msj('Ingrese el concepto');
			document.getElementById('concepto').focus();
			return false;


}//fin




}//fin funcion validar



