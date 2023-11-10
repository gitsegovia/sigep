function cugp01_centro_poblados(){


 	  if(document.getElementById('c_republica').value==""){

			fun_msj('Seleccione una Rep&uacute;blica');
			//document.getElementById('c_republica').focus();
			return false;


}else if(document.getElementById('c_estado').value==""){

			fun_msj('Seleccione un Estado');
			//document.getElementById('c_republica').focus();
			return false;

}else if(document.getElementById('c_municipio').value==""){

			fun_msj('Seleccione un Municipio');
			//document.getElementById('c_republica').focus();
			return false;

}else if(document.getElementById('c_parroquia').value==""){

			fun_msj('Seleccione una Parroquia');
			//document.getElementById('c_republica').focus();
			return false;


}else if(document.getElementById('denominacion').value==""){

			fun_msj('Ingrese la Denominaci&oacute;n del Centro Poblado');
			document.getElementById('denominacion').focus();
			return false;


}else if(document.getElementById('conocido').value==""){

			fun_msj('Ingrese Conocido Como');
			document.getElementById('conocido').focus();
			return false;

}else if(document.getElementById('ct_centropoblados').value==""){

			fun_msj('Ingrese las principales Caracter&iacute;sticas del centro poblado');
			document.getElementById('ct_centropoblados').focus();
			return false;

}else if(document.getElementById('f_economica').value==""){

			fun_msj('Ingrese las Fuentes Principales Econ&oacute;micas');
			document.getElementById('f_economica').focus();
			return false;

}else if(document.getElementById('poblacion').value==""){

			fun_msj('Ingrese la Poblaci&oacute;n/habitantes');
			document.getElementById('poblacion').focus();
			return false;

}else if(document.getElementById('orientacion').value==""){

			fun_msj('Ingrese la  Orientaci&oacute;n Cardinal');
			document.getElementById('orientacion').focus();
			return false;

}else if(document.getElementById('d_territorial').value==""){

			fun_msj('Ingrese la Dimensi&oacute;n territorial');
			document.getElementById('d_territorial').focus();
			return false;


}else if(document.getElementById('z_postal').value==""){

			fun_msj('Ingrese la Zona Postal');
			document.getElementById('z_postal').focus();
			return false;

}else if(document.getElementById('limites').value==""){

			fun_msj('Ingrese los L&iacute;mites y Linderos');
			document.getElementById('limites').focus();
			return false;

}else if(document.getElementById('urbana').checked!=false || document.getElementById('rural').checked!=false || document.getElementById('agricola').checked!=false || document.getElementById('frontera').checked!=false){

			fun_msj2('el Registro Fue Guardado');
			//document.getElementById('limites').focus();
			//return false;

	}else{

			fun_msj('Seleccione la clasificaci&oacute;n Territorial del Centro Poblado');
			return false;

		}
}//fin function








function mensajes_cugp01_centro_poblados_eliminar(){
	fun_msj(' el Registro Fue eliminado');
}


