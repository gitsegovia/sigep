function valida_cfpp10_reformulacion_clave_generico(){
	if(document.getElementById('usuario').value==''){

			fun_msj('Por Favor, ingrese la Identificaci&oacute;n del Usuario');
			document.getElementById('usuario').focus();
			return false;

	}else if(document.getElementById('clave').value == ""){

			fun_msj('Por Favor, ingrese la Clave del Usuario');
			document.getElementById('clave').focus();
			return false;

	}
}


function frestriccion(idcr, todos){

	var aSelected = new Array();
	var multiList_Select;
	var otorgados;

    multiList_Select = document.getElementById(idcr);
    multiList_Select_aux = document.getElementById(idcr+"_aux");

    if(multiList_Select.value != "" || todos != null){
    // if(eval(multiList_Select.options.length) >= eval(0)){

	var lms = multiList_Select.options.length;
	otorgados = '';

  if(todos == 1){ // Selecciona Todos

    for (var iList=0; iList<lms; iList++)
    {
		aSelected[iList] = true;
		multiList_Select_aux.options[iList].selected = true;
    }

    if(eval(lms) != eval(0)){otorgados += 'Todos los Permisos...';}


  }else if(todos == 2){ // Deselecciona todos

    for (var iList=0; iList<lms; iList++)
    {
		aSelected[iList] = false;
		multiList_Select_aux.options[iList].selected = false;
    }
    if(eval(lms) != eval(0)){otorgados += '';}


  }else{ // Seleccion uno en particular

	if(!multiList_Select_aux.options[multiList_Select.selectedIndex].selected){
		multiList_Select_aux.options[multiList_Select.selectedIndex].selected = true;
	}else{
		multiList_Select_aux.options[multiList_Select.selectedIndex].selected = false;
	}


    for (var iList=0; iList<lms; iList++)
    {
		// aSelected[iList] = multiList_Select.options[iList].selected;
		aSelected[iList] = multiList_Select_aux.options[iList].selected;

		if(aSelected[iList] == true){
			otorgados += multiList_Select_aux.options[iList].text + '<br />';
		}
    }
  }


/*
    // var theIndex = multiList_Select.selectedIndex;
    // aSelected[theIndex] = !aSelected[theIndex];

	var theIndex = multiList_Select.selectedIndex;
	if(multiList_Select.options[theIndex].selected == true){
		aSelected[theIndex] = true;
	}else{
		aSelected[theIndex] = false;
	}
*/

    for (var iLista=0; iLista<lms; iLista++)
    {
        multiList_Select.options[iLista].selected = aSelected[iLista];
    }

    	document.getElementById("otorgar_permisos").innerHTML = ""+otorgados;

    }
}


function valid_cfpp10_restriccion_cgenerico(){

	if(document.getElementById('usuario').value==''){

			fun_msj('Por Favor, ingrese la Identificaci&oacute;n del Usuario');
			document.getElementById('usuario').focus();
			return false;

	}else if(document.getElementById('cod_trestriccion').value == ""){

			fun_msj('Debe seleccionar el(los) tipo(s) de restriccion(es)');
			// document.getElementById('cod_trestriccion').focus();
			return false;

	}
}

function valida_cfpp10_restriccion_clave_generico(){

	if(document.getElementById('usuario').value==''){

			fun_msj('Por Favor, ingrese la Identificaci&oacute;n del Usuario');
			document.getElementById('usuario').focus();
			return false;

	}else if(document.getElementById('denominacion_clave').value == ""){

			fun_msj('Debe seleccionar el tipo de restriccion');
			document.getElementById('select_1').focus();
			return false;

	}
}


function valida_cfpp10_restriccion_clave_generico2(){

	if(document.getElementById('usuario').value==''){

			fun_msj('Por Favor, ingrese la Identificaci&oacute;n del Usuario');
			document.getElementById('usuario').focus();
			return false;

	}else if(document.getElementById('codigo_clave').value == ""){

			fun_msj('Debe, Seleccionar el tipo de restriccion');
			document.getElementById('select_1').focus();
			return false;

	}else if(document.getElementById('denominacion_clave').value == ""){

			fun_msj('Debe, Seleccionar el tipo de restriccion');
			document.getElementById('select_1').focus();
			return false;

	}
}