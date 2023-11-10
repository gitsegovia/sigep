
function reporte_ingreso_mensual(){


   if(document.getElementById('ano').value.length==""){

			fun_msj('Inserte un a&ntilde;o correcto');
			document.getElementById('ano').focus();
			return false;

	}//fin


}




function valida_consumo(){


if(document.getElementById('ano').value==""){

			fun_msj('Inserte un a&ntilde;o correcto');
			document.getElementById('ano').focus();
			return false;
}else{
           if(document.getElementById('select_lista_reporte')){
                 if(document.getElementById('select_lista_reporte').value==""){
					fun_msj('Inserte un opci&oacute;n');
					document.getElementById('select_lista_reporte').focus();
					return false;
			    }//fin if
			}//fin if
}//fin funcion



}//fin funcion