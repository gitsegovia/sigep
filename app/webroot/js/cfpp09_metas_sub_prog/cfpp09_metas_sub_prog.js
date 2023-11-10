function valida_cfpp09_metas_sub_prog(){

if(document.getElementById('select_1').value==''){

			fun_msj('Seleccione Sector');
			document.getElementById('select_1').focus();
			return false;

}else if(document.getElementById('select_2').value == ""){

			fun_msj('Seleccione Programa');
			document.getElementById('select_2').focus();
			return false;


}else if(document.getElementById('select_3').value == ""){

			fun_msj('Seleccione Sub Programa');
			document.getElementById('select_3').focus();
			return false;


}else if(document.getElementById('metas').value==""){

			fun_msj('Inserte la Descripci&oacute;n de la Meta');
			document.getElementById('metas').focus();
			return false;

}else if(document.getElementById('unidad_medida').value==""){

			fun_msj('Inserte la Unidad de Medida');
			document.getElementById('unidad_medida').focus();
			return false;

}else if(document.getElementById('cantidad').value==""){

			fun_msj('Inserte la Cantidad');
			document.getElementById('cantidad').focus();
			return false;

      }


}//fin funtion

function valida_cfpp09_metas_sub_prog2(){

if(document.getElementById('metas').value==""){

			fun_msj('Inserte la Descripci&oacute;n de la Meta');
			document.getElementById('metas').focus();
			return false;

}else if(document.getElementById('unidad_medida').value==""){

			fun_msj('Inserte la Unidad de Medida');
			document.getElementById('unidad_medida').focus();
			return false;

}else if(document.getElementById('cantidad').value==""){

			fun_msj('Inserte la Cantidad');
			document.getElementById('cantidad').focus();
			return false;

      }





}//fin funtion