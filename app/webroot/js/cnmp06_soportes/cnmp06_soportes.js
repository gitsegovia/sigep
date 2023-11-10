function valida_cedula_soporte(){

    if(document.getElementById('cedula').value == ''){
			fun_msj('Inserte La Cedula');
			document.getElementById('cedula').focus();
			return false;
    }//fin if

}//fin valida_fila zona