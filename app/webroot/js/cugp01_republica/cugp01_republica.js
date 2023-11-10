function cugp01_republica(){




     if(document.getElementById('denominacion').value==""){

			fun_msj('Ingrese la Denominaci&oacute;n de la Rep&uacute;blica');
			document.getElementById('denominacion').focus();
			return false;

}else if(document.getElementById('ct_republica').value==""){

			fun_msj('Ingrese las Principales Caracterisaticas de la Rep&uacute;blica');
			document.getElementById('ct_republica').focus();
			return false;

}else if(document.getElementById('f_economica').value==""){

			fun_msj('Ingrese las Fuentes Principales Econ&oacute;micas');
			document.getElementById('f_economica').focus();
			return false;

}else if(document.getElementById('poblacion').value==""){

			fun_msj('Ingrese la Poblaci&oacute;n/Habitantes');
			document.getElementById('poblacion').focus();
			return false;

}else if(document.getElementById('moneda').value==""){

			fun_msj('Ingrese la Moneda');
			document.getElementById('moneda').focus();
			return false;

}else if(document.getElementById('s_monetario').value==""){

			fun_msj('Ingrese el Signo Monetario');
			document.getElementById('s_monetario').focus();
			return false;

}else if(document.getElementById('d_territorial').value==""){

			fun_msj('Ingrese la Dimension Territorial');
			document.getElementById('d_territorial').focus();
			return false;


	}else{

		fun_msj2('Fue Guardado el Registro de la Rep&uacute;blica');


		}


}


function mensajes_cugp01_republica_eliminar(){


	fun_msj('Fue Eliminado el Registro de la Rep&uacute;blica');


}
