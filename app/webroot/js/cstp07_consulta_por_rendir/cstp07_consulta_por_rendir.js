
function cstp07_consulta_por_rendir_valida(){



                          if(document.getElementById('desde_periodo').value==''){

			                   fun_msj('Inserte la fecha del Periodo Desde');
			                   document.getElementById('desde_periodo').focus();
			                   return false;


			           }else if(document.getElementById('hasta_periodo').value==''){

			                   fun_msj('Inserte la fecha del Periodo Hasta');
			                   document.getElementById('hasta_periodo').focus();
			                   return false;

			            }else if(diferenciaFecha(document.getElementById('hasta_periodo').value, document.getElementById('desde_periodo').value)){

                           fun_msj('la Fecha desde no debe ser mayor a la fecha hasta');
                           return false;

                           }//fin else


}//fin fucntion