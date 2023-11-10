function cugp01_vialidad(){


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


}else if(document.getElementById('valida').value==""){

			fun_msj('Ingrese el C&oacute;digo');
			document.getElementById('valida').focus();
			return false;


}else if(document.getElementById('denominacion').value==""){

			fun_msj('Ingrese la Denominaci&oacute;n de la Vialidad');
			document.getElementById('denominacion').focus();
			return false;


}else{

			fun_msj2('el Registro Fue Almacenado ');


		}

}


function mensajes_cugp01_vialidad_eliminar(){


	fun_msj('el Registro Fue Eliminado ');


}

