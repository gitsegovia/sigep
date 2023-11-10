function reporte_relacion_obras_proyecto_valida_radio_nivel_consulta(){


		      if(document.getElementById('ano_estimacion').value==''){

             		 error('seleccione el a&ntilde;o');
             		 return false;

		}else if(document.getElementById('radio_nivel_consulta_1').checked == true){

               if(document.getElementById('select_1').value == ''){

			   	  	  error('seleccione la rep&uacute;blica');
			   	  	  return false;

			   }

		}else if(document.getElementById('radio_nivel_consulta_2').checked == true){

			        if(document.getElementById('select_1').value == ''){

			   	  	  error('seleccione la rep&uacute;blica');
			   	  	  return false;

			   }else if(document.getElementById('select_2').value == ''){

	                  error('seleccione el estado');
				   	  return false;

			   }

	    }else if(document.getElementById('radio_nivel_consulta_3').checked == true){

	    	    if(document.getElementById('select_1').value == ''){

			   	  	  error('seleccione la rep&uacute;blica');
			   	  	  return false;

			   }else if(document.getElementById('select_2').value == ''){

			   	  	  error('seleccione el tipo de instituci&oacute;n');
			   	  	  return false;

			   }

	    }else if(document.getElementById('radio_nivel_consulta_4').checked == true){

                      if(document.getElementById('select_1').value == ''){

			   	  	  error('seleccione la rep&uacute;blica');
			   	  	  return false;

			   }else if(document.getElementById('select_2').value == ''){

			   	        error('seleccione el estado');
			   	        return false;

			   	}else if(document.getElementById('select_3').value == ''){

			   	        error('seleccione el tipo de instituci&oacute;n');
			   	        return false;

			    }else if(document.getElementById('select_4').value == ''){

			   	        error('seleccione la instituci&oacute;n');
			   	        return false;

			   }

		}else{

		                error('seleccione una opci&oacute;n en el nivel de consulta');
			   	        return false;

		}


}//fin function