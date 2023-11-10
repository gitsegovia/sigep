function valida_cstp01_entidades_bancarias_(){

 if(document.getElementById('codigo_entidad').value==''){

			fun_msj('El c&oacute;digo de la entidad no puede estar vac&iacute;o');
			document.getElementById('codigo_entidad').focus();
			return false;

	}
	 if(document.getElementById('codigo_entidad').value.length<4){

			fun_msj('El c&oacute;digo de la entidad no puede ser menor de 4 d&iacute;gitos');
			document.getElementById('codigo_entidad').focus();
			return false;

	} if(document.getElementById('denominacion').value==''){

			fun_msj('La denominaci&oacute;n de la entidad no puede estar vac&iacute;a');
			document.getElementById('denominacion').focus();
			return false;

	}
	document.getElementById('codigo_entidad').value="";
	document.getElementById('denominacion').value="";

}//fin funtion

function cstp01_entidades_bancarias_modificar(){
	document.getElementById('b_modificar').disabled=true;
	document.getElementById('b_guardar').disabled=false;
	document.getElementById('denominacion').readOnly=false;
	fun_msj2('Puede proceder a modificar la Entidad Bancaria');
}