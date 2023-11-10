function cnmp09_tqcs_valida(){


         if(document.getElementById('select_1').value==""){


            fun_msj('Inserte el C&oacute;digo de la N&oacute;mina');
			document.getElementById('select_1').focus();
			return false;

   }else if(document.getElementById('select2_1').value==""){

            fun_msj('Inserte el C&oacute;digo de la transacci&oacute;n');
			document.getElementById('select2_1').focus();
			return false;

	}//fin else if



}//fin function