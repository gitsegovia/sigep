function valida_cfpp09_metas_actividad(){

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


}else if(document.getElementById('select_4').value == ""){

			fun_msj('Seleccione Proyecto');
			document.getElementById('select_4').focus();
			return false;

}else if(document.getElementById('select_5').value == ""){

			fun_msj('Seleccione Actividad u Obra');
			document.getElementById('select_5').focus();
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

function valida_cfpp09_metas_actividad2(){

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

function sig(n){
    	//var n;
    	switch(n){
    	 case 1:
    	    if(document.getElementById('codigo1').value.length==1){
    		    document.getElementById('codigo2').focus();
    	    }

    	 break;
    	 case 2:
    	    if(document.getElementById('codigo2').value.length==1){
    		    document.getElementById('codigo3').focus();
    	    }

    	 break;
    	 case 3:
    	    if(document.getElementById('codigo3').value.length==1){
    		    document.getElementById('codigo4').focus();
    	    }

    	 break;
    	 case 4:
    	    if(document.getElementById('codigo4').value.length==1){
    		    document.getElementById('codigo5').focus();
    	    }

    	 break;
    	 case 5:
    	    if(document.getElementById('codigo5').value.length==1){
    		    document.getElementById('codigo6').focus();
    	    }

    	 break;


}
}

}//fin funtion