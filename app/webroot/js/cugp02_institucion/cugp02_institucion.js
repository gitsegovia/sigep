function cugp02_institucion(){


 	 if(document.getElementById('valida').value==""){

			fun_msj('Ingrese el C&oacute;digo');
			document.getElementById('valida').focus();
			return false;

}else if(document.getElementById('valida').value != document.getElementById('aux_codigo').value){

			fun_msj('Compruebe el Nuevo C&oacute;digo');
			document.getElementById('valida').focus();
			return false;


}else if(document.getElementById('existe').value=='si'){

			fun_msj('Ingrese un C&oacute;digo valido');
			document.getElementById('valida').focus();
			return false;


}else if(document.getElementById('denominacion').value==""){

			fun_msj('Ingrese la Denominaci&oacute;n');
			document.getElementById('denominacion').focus();
			return false;

}else if(document.getElementById('funcionario_responsable').value==""){

			fun_msj('Ingrese el Funcionario Responsable');
			document.getElementById('funcionario_responsable').focus();
			return false;


}else if(document.getElementById('direccion').value==""){

			fun_msj('Ingrese la Direcci&oacute;n');
			document.getElementById('direccion').focus();
			return false;

}else if(document.getElementById('cod_area').value==""){

			fun_msj('Ingrese el C&oacute;digo de Area');
			document.getElementById('cod_area').focus();
			return false;

}else if(document.getElementById('telefonos').value==""){

			fun_msj('Ingrese el N&uacute;mero de Tel&eacute;fono');
			document.getElementById('telefonos').focus();
			return false;

}else if(document.getElementById('fax').value==""){

			fun_msj('Ingrese el Fax');
			document.getElementById('fax').focus();
			return false;

}else if ( (document.getElementById('email').value.search("@") == -1 ) || ( document.getElementById('email').value.search("[.*]" ) == -1 ) ) {

		fun_msj( "Por favor, revise el Email." );
		document.getElementById('email').focus();
		return false;


}else if(document.getElementById('rif').value==""){

			fun_msj('Ingrese el Rif');
			document.getElementById('rif').focus();
			return false;

}else if(document.getElementById('nit').value==""){

			fun_msj('Ingrese el Nit');
			document.getElementById('nit').focus();
			return false;


}else if(document.getElementById('agente_retencion').value==""){

			fun_msj('Ingrese el Agente de Retenci&oacute;n');
			document.getElementById('agente_retencion').focus();
			return false;

}else if(document.getElementById('fiscal_rentas').value==""){

			fun_msj('Ingrese el Fiscal de Rentas');
			document.getElementById('fiscal_rentas').focus();
			return false;

}else if(document.getElementById('decreto_gaceta').value==""){

			fun_msj('Ingrese el Decreto o Gaceta');
			document.getElementById('decreto_gaceta').focus();
			return false;

}else if(document.getElementById('fecha').value==""){

			fun_msj('Ingrese la Fecha de Decreto o Gaceta');
			document.getElementById('fecha').focus();
			return false;


}else{

			fun_msj2('Fue Almacenado el Registro de Instituci&oacute;n');


		}

}

function mensajes_cugp02_institucion_eliminar(){
	fun_msj('Fue Eliminado el Registro de Instituci&oacute;n');
}
