function canp00_graficos(){


	if(document.getElementById('ano_estimacion').value==''){
    		 fun_msj('seleccione el a&ntilde;o');
     		 return false;
	}


}




function anterior_grafica(){
	$('siguiente').style.display="block";
	$('anterior').style.display="none";

	$('grafica1').style.display="block";
	$('grafica2').style.display="none";
}



function siguiente_grafica(){
	$('siguiente').style.display="none";
	$('anterior').style.display="block";

	$('grafica1').style.display="none";
	$('grafica2').style.display="block";
}






function select_grafica_1(){
    if(document.getElementById('select').value==''){
    		 fun_msj('Faltan datos para generar la gr&aacute;fica');
     		 return false;
	}
}