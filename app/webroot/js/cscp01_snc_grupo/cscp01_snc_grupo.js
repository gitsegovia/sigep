function cscp01_snc_grupo_valida(){

         if(document.getElementById('cod_grupo').value==''){

			fun_msj('Inserte el c&oacute;digo');
			document.getElementById('cod_grupo').focus();
			return false;

	}else if(document.getElementById('denominacion').value==''){

			fun_msj('Inserte la denominaci&oacute;n');
			document.getElementById('denominacion').focus();
			return false;

    }else if(document.getElementById('descripcion').value==''){

			fun_msj('Inserte la descripci&oacute;n');
			document.getElementById('descripcion').focus();
			return false;


	}//fin function


}//fin function