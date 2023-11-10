function cugp02_direccionsuperior(){

if(document.getElementById('cod_institucion').value==""){

			fun_msj('Ingrese la Instituci&oacute;n');
			document.getElementById('cod_institucion').focus();
			return false;

}else if(document.getElementById('cod_dependencia').value==""){

			fun_msj('Ingrese la Dependencia');
			document.getElementById('cod_dependencia').focus();
			return false;


 //}else if(document.getElementById('valida').value==""){
//
	//		fun_msj('Ingrese el C&oacute;digo');
		//	document.getElementById('valida').focus();
			//return false;

//}else if(document.getElementById('valida').value != document.getElementById('aux_codigo').value){
//
//			fun_msj('Compruebe el Nuevo C&oacute;digo');
//			document.getElementById('valida').focus();
//			return false;
//
//
//}else if(document.getElementById('existe').value=='si'){
//
	//		fun_msj('Ingrese un C&oacute;digo Valido');
		//	document.getElementById('valida').focus();
		//	return false;
//

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



}else{

			fun_msj2('Fue Almacenado el Registro de la Direcci&oacute;n Superior');


		}

}

function mensajes_cugp02_direccionsuperior_eliminar(){
	fun_msj('Fue Eliminado el Registro de la Direcci&oacute;n Superior');
}
