function reporte5_informacion_impuesto_retenido(){

                        if(document.getElementById('rif_beneficiario').value==""){

						         fun_msj('Ingrese el beneficiario');
						         document.getElementById('rif_beneficiario').focus();
						         return false;

			       }else if(document.getElementById('fecha_desde').value==""){

						         fun_msj('Ingrese fecha desde');
						         document.getElementById('fecha_desde').focus();
						         return false;

                  }else if(document.getElementById('fecha_hasta').value==""){

						         fun_msj('Ingrese fecha hasta');
						         document.getElementById('fecha_hasta').focus();
						         return false;

			      }//fin if

}