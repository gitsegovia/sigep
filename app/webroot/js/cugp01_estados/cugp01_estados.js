function cugp01_estados(){


 if(document.getElementById('c_republica').value==""){

			fun_msj('Seleccione una Rep&uacute;blica');
			//document.getElementById('c_republica').focus();
			return false;



}else if(document.getElementById('denominacion').value==""){

			fun_msj('Ingrese la Denominaci&oacute;n del Estado');
			document.getElementById('denominacion').focus();
			return false;

}else if(document.getElementById('ct_estados').value==""){

			fun_msj('Ingrese las Principales Caracter&iacute;sticas del Estado');
			document.getElementById('ct_estados').focus();
			return false;

}else if(document.getElementById('f_economica').value==""){

			fun_msj('Ingrese las Fuentes Principales Econ&oacute;micas');
			document.getElementById('f_economica').focus();
			return false;

}else if(document.getElementById('poblacion').value==""){

			fun_msj('Ingrese la Poblaci&oacute;n/Habitantes');
			document.getElementById('poblacion').focus();
			return false;

}else if(document.getElementById('orientacion').value==""){

			fun_msj('Ingrese la  Orientaci&oacute;n Cardinal');
			document.getElementById('orientacion').focus();
			return false;

}else if(document.getElementById('d_territorial').value==""){

			fun_msj('Ingrese la Dimensi&oacute;n Territorial');
			document.getElementById('d_territorial').focus();
			return false;

}else if(document.getElementById('limites').value==""){

			fun_msj('Ingrese los L&iacute;mites y Linderos');
			document.getElementById('limites').focus();
			return false;


	}else{

		fun_msj2('El registro fue guardado');


		}

}


function mensajes_cugp01_estados_eliminar(){


	fun_msj('El registro fue eliminado');


}

