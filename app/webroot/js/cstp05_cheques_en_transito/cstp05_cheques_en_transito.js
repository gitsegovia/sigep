function cstp05_cheques_en_transito_valida(){
	if(verifica_cierre_ano_ejecucion_msj()==false){
		return false;
	}else if(document.getElementById('persona_receptor').value==''){

						fun_msj('Inserte el nombre de la persona');
						document.getElementById('persona_receptor').focus();
						return false;

			}else if(document.getElementById('cedula_identidad').value==''){

						fun_msj('Inserte la c&eacute;dula de identidad');
						document.getElementById('cedula_identidad').focus();
						return false;

			}//fin else
}//fin function