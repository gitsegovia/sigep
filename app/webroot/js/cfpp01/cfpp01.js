function valida_cfpp01_traspaso(){


		if(document.getElementById('ejercicio_de').value==''){

			fun_msj('Inserte el ejercicio que desea traspasar');
			document.getElementById('ejercicio_de').focus();
			return false;

	}else if(document.getElementById('ejercicio_de').value.length<4){

			fun_msj('Inserte un a&ntilde;o correcto');
			document.getElementById('ejercicio_de').focus();
			return false;


	}else if(document.getElementById('ejercicio_al').value==''){

			fun_msj('Inserte el ejercicio al que desea traspasar');
			document.getElementById('ejercicio_al').focus();
			return false;

	}else if(document.getElementById('ejercicio_al').value.length<4){

			fun_msj('Inserte un a&ntilde;o correcto');
			document.getElementById('ejercicio_al').focus();
			return false;


	}else{


		}


}


function valida_cfpp01_ano(){


	if(document.getElementById('ano_presupuesto').value==''){

			fun_msj('Inserte el a&ntilde;o presupuestario');
			document.getElementById('ano_presupuesto').focus();
			return false;

	}else if(document.getElementById('ano_presupuesto').value.length<4){

			fun_msj('Inserte un a&ntilde;o correcto');
			document.getElementById('ano_presupuesto').focus();
			return false;

	}//fin


}



function vaciar_ejercicio(){

		document.getElementById('ano_presupuesto').value="";


}





function valida_cfpp01(){


      if(document.getElementById('codigo').value==''){

			fun_msj('Inserte el c&oacute;digo');
			document.getElementById('codigo').focus();
			return false;



}else if(document.getElementById('denominacion').value==''){

			fun_msj('Inserte la Denominaci&oacute;n');
			document.getElementById('denominacion').focus();
			return false;


}else if(document.getElementById('concepto').value==''){

			fun_msj('Inserte el Concepto');
			document.getElementById('concepto').focus();
			return false;



}else{

}//fin else




}//fin function











function traspaso_siguiente(){

	var actual = document.getElementById('ano_presupuesto').value;

	actual =eval(actual) + 1;

	document.getElementById('ano_presupuesto').value = actual;

	}