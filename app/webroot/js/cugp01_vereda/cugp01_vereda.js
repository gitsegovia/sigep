function cugp01_vereda(){


 	  if(document.getElementById('c_republica').value==""){

			fun_msj('Seleccione una Rep&uacute;blica');
			//document.getElementById('c_republica').focus();
			return false;


}else if(document.getElementById('c_estado').value==""){

			fun_msj('Seleccione un Estado');
			//document.getElementById('c_estado').focus();
			return false;

}else if(document.getElementById('c_municipio').value==""){

			fun_msj('Seleccione un Municipio');
			//document.getElementById('c_municipio').focus();
			return false;

}else if(document.getElementById('c_parroquia').value==""){

			fun_msj('Seleccione una Parroquia');
			//document.getElementById('c_parroquia').focus();
			return false;


}else if(document.getElementById('c_centro').value==""){

			fun_msj('Seleccione un Centro Poblado');
			//document.getElementById('c_centro').focus();
			return false;

}else if(document.getElementById('c_vialidad').value==""){

			fun_msj('Seleccione una Vialidad');
			//document.getElementById('c_centro').focus();
			return false;


}else if(document.getElementById('valida').value==""){

			fun_msj('Ingrese el C&oacute;digo');
			document.getElementById('valida').focus();
			return false;

}else if(document.getElementById('denominacion').value==""){

			fun_msj('Ingrese la Denominaci&oacute;n de la Vereda');
			document.getElementById('denominacion').focus();
			return false;


}else{

			fun_msj2('Fue Almacenado el Registro de la Vereda');


		}


}

function mensajes_cugp01_vereda_eliminar(){


	fun_msj('Fue Eliminado el Registro de Vereda');


}
