function valida_cfpp07_plan_inversion(){

if(document.getElementById('ano_formulacion').value==""){

			fun_msj('Ingrese el A&ntilde;o');
			document.getElementById('ano_formulacion').focus();
			return false;

}if(document.getElementById('clasificacion_recurso').value==""){

			fun_msj('Ingrese el codigo de clasificacion');
			document.getElementById('clasificacion_recurso').focus();
			return false;

}else if(document.getElementById('denominacion').value==""){

			fun_msj('Ingrese la Denominacion de la Clasificacion del recurso');
			document.getElementById('denominacion').focus();
			return false;
}else if(document.getElementById('asignacion_total').value=="0,00"){

			fun_msj('Ingrese el monto total asignado');
			document.getElementById('asignacion_total').focus();
			return false;
}else if(document.getElementById('monto_presupuestado').value==""){

			fun_msj('Ingrese el monto presupuestado');
			document.getElementById('monto_presupuestado').focus();
			return false;
}

}//fin funtion


function verifica_radio_seleccionado_inversion(){
	if((document.getElementById('radio_si_no_1').checked=false) && (document.getElementById('radio_si_no_2').checked=false) && (document.getElementById('radio_si_no_3').checked=false) && (document.getElementById('radio_si_no_4').checked=false) && (document.getElementById('radio_si_no_5').checked=false)){
		fun_msj('seleccione el tipo de recurso');
		document.getElementById('radio_si_no').focus();
		return false;
		}


}//fin verifica_radio_seleccionado_inversion

function valida_plan_inversion_monto(){
	if(document.getElementById('monto_asignado').value==""){
			fun_msj('debe ingresar el monto que desea asignar');
			document.getElementById('monto_asignado').focus();
			return false;
}

}