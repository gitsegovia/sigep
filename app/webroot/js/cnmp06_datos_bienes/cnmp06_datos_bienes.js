function cnmp06_datos_bienes_valida(){


              if(document.getElementById('cod_bien').value==''){
					fun_msj('Ingrese la c&oacute;digo del bien');
					document.getElementById('cod_bien').focus();
					return false;

		}else if(document.getElementById('ano_compra').value==''){
					fun_msj('ingrese el a&ntilde;o de la compra');
					document.getElementById('ano_compra').focus();
					return false;

		}else if(document.getElementById('costo').value==''){
					fun_msj('ingrese el costo de la compra');
					document.getElementById('costo').focus();
					return false;

		}//fin else




}//fin function