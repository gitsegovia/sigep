function valida_cfpp07_clasificacion_recurso(){

if(document.getElementById('clasificacion_recurso').value==""){

			fun_msj('Ingrese la clasificaci&oacute;n del recurso');
			document.getElementById('clasificacion_recurso').focus();
			return false;

}else if(document.getElementById('denominacion').value==""){

			fun_msj('Ingrese la denominaci&oacute;n');
			document.getElementById('denominacion').focus();
			return false;

}

}//fin funtion

