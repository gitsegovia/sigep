function cnmp15_dias_antiguedad_valida1(){


           if(document.getElementById('select_desde').value == ""){

            fun_msj('Seleccione el c&oacute;digo de n&oacute;mina Desde');
			return false;

	 }else if(document.getElementById('select_hasta').value == ""){

            fun_msj('Seleccione el c&oacute;digo de n&oacute;mina hasta');
			return false;

     }//fin else

}//fin function



function cnmp15_dias_antiguedad_valida2(){


           if(document.getElementById('select').value == ""){

            fun_msj('Seleccione el c&oacute;digo de n&oacute;mina');
			return false;


     }else if(document.getElementById('ano').value == ""){

            fun_msj('Inserte el a&ntilde;o ');
			document.getElementById('ano').focus();
			return false;

	}else if(document.getElementById('ene').value == ""){

            fun_msj('Inserte el Porcentaje de Enero');
			document.getElementById('ene').focus();
			return false;

	}else if(document.getElementById('feb').value == ""){

            fun_msj('Inserte el Porcentaje de Febrero');
			document.getElementById('feb').focus();
			return false;

	}else if(document.getElementById('mar').value == ""){

            fun_msj('Inserte el Porcentaje de Marzo');
			document.getElementById('mar').focus();
			return false;

	}else if(document.getElementById('abr').value == ""){

            fun_msj('Inserte el Porcentaje de Abril');
			document.getElementById('abr').focus();
			return false;

	}else if(document.getElementById('may').value == ""){

            fun_msj('Inserte el Porcentaje de Mayo');
			document.getElementById('may').focus();
			return false;

	}else if(document.getElementById('jun').value == ""){

            fun_msj('Inserte el Porcentaje de Junio');
			document.getElementById('jun').focus();
			return false;

	 }else if(document.getElementById('jul').value == ""){

            fun_msj('Inserte el Porcentaje de Julio');
			document.getElementById('jul').focus();
			return false;

	}else if(document.getElementById('ago').value == ""){

            fun_msj('Inserte el Porcentaje de Agosto');
			document.getElementById('ago').focus();
			return false;

	}else if(document.getElementById('sep').value == ""){

            fun_msj('Inserte el Porcentaje de Septiembre');
			document.getElementById('sep').focus();
			return false;

	}else if(document.getElementById('oct').value == ""){

            fun_msj('Inserte el Porcentaje de Octubre');
			document.getElementById('oct').focus();
			return false;

	}else if(document.getElementById('nov').value == ""){

            fun_msj('Inserte el Porcentaje de Noviembre');
			document.getElementById('nov').focus();
			return false;


	}else if(document.getElementById('dic').value == ""){

            fun_msj('Inserte el Porcentaje de Diciembre');
			document.getElementById('dic').focus();
			return false;


	}//fin else



}//fin function