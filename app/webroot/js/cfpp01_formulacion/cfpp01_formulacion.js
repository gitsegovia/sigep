
function valida_cfpp01_ano_formulacion(){


	if(document.getElementById('ano_formular').value==''){

			fun_msj('Inserte ejercicio presupuestario');
			document.getElementById('ano_formular').focus();
			return false;

	}else if(document.getElementById('ano_formular').value.length<4){

			fun_msj('Inserte un a&ntilde;o correcto');
			document.getElementById('ano_formular').focus();
			return false;

	}//fin


}



function vaciar_ejercicio(){

		document.getElementById('ano_formular').value="";


}





function valida_cfpp01_formulacion(){


if(document.getElementById('valida').value==''){

			fun_msj('Inserte el codigo');
			document.getElementById('valida').focus();
			return false;


}else if(document.getElementById('valida').value != document.getElementById('aux_codigo').value){

			fun_msj('Compruebe el Nuevo Codigo');
			document.getElementById('valida').focus();
			return false;


}else if(document.getElementById('existe').value=='si'){

			fun_msj('Inserte un codigo valido');
			document.getElementById('valida').focus();
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




 		   var checkOK = " 0123456789.";
           var checkStr = document.getElementById('codigo').value;
           var Validcodigo = true;
           var validGroups = true;

           for (i = 0;  i < checkStr.length;  i++){
             ch = checkStr.charAt(i);
             for (j = 0;  j < checkOK.length;  j++)
                 if (ch == checkOK.charAt(j)){break;}
	            if (j == checkOK.length){
	             Validcodigo = false;
	            break;}
			  }



if (!Validcodigo){

						fun_msj("El  campo codigo es n&uacute;merico");
						document.getElementById('codigo').focus();
						return false;

}


}//fin else




}//fin function











function traspaso_siguiente(){

	var actual = document.getElementById('ano_presupuesto').value;

	actual =eval(actual) + 1;

	document.getElementById('ano_presupuesto').value = actual;

	}