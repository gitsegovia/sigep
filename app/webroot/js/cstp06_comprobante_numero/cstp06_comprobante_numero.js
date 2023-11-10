


function valida_cstp06_comprobante_numero(){


if(document.getElementById('cod_parentesco').value==''){

	        fun_msj('Inserte el a&ntilde;o');
			document.getElementById('cod_parentesco').focus();
			return false;

	}if(document.getElementById('denominacion').value==''){

	        fun_msj('Inserte el n&uacute;mero');
			document.getElementById('denominacion').focus();
			return false;

	}//fin if







}//fin function