
reportes_cierre

function reportes_cierre(){
	if(document.getElementById('fecha_desde').value==''){
			fun_msj('Ingrese la fecha desde');
			document.getElementById('fecha_desde').focus();
			return false;
	}else if(document.getElementById('fecha_hasta').value==''){
			fun_msj('Ingrese la fecha hasta');
			document.getElementById('fecha_hasta').focus();
			return false;
	}

}




function valida_dep_arrp01(){
	if(document.getElementById('valida').value==''){
			fun_msj('Ingrese el c&oacute;digo de la dependencia');
			document.getElementById('valida').focus();
			return false;
	}else if(document.getElementById('denominacion').value==''){
			fun_msj('Ingrese la denominaci&oacute;n de la dependencia');
			document.getElementById('denominacion').focus();
			return false;
	}else if(document.getElementById('tipo_dep_1').checked==false && document.getElementById('tipo_dep_2').checked==false ){
			fun_msj('Indique el tipo de dependencia');
			return false;
	}


}




function valida_arrp00(){


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


}else if(document.getElementById('tipo_dep_1').checked==false && document.getElementById('tipo_dep_2').checked==false ){

			fun_msj('Inserte el tipo de dependencia');
			return false;


}else{




 		   var checkOK = " 0123456789";
           var checkStr = document.getElementById('valida').value;
           var Validcodigo = true;
           var validGroups = true;

           for (i = 0;  i < checkStr.length;  i++){
             ch = checkStr.charAt(i);
             for (j = 0;  j < checkOK.length;  j++)
                 if (ch == checkOK.charAt(j)){break;}
	            if (j == checkOK.length){
	             Validcodigo = false;
	            break;
	            }
			  }



if (!Validcodigo){

						fun_msj("El  campo codigo es n&uacute;merico");
						document.getElementById('valida').focus();
						return false;

}


}//fin else




}//fin function

function valida2_arrp00(){


if(document.getElementById('codigo').value==''){

			fun_msj('Inserte el codigo');
			document.getElementById('codigo').focus();
			return false;


}/*else if(document.getElementById('codigo').value == document.getElementById('aux_codigo').value){

			fun_msj('Compruebe el Nuevo Codigo');
			document.getElementById('codigo').focus();
			return false;


}else if(document.getElementById('existe').value=='si'){

			fun_msj('Inserte un codigo valido');
			document.getElementById('codigo').focus();
			return false;


}*/else if(document.getElementById('denominacion').value==''){

			fun_msj('Inserte la Denominaci&oacute;n');
			document.getElementById('denominacion').focus();
			return false;


}else if(document.getElementById('tipo_dep_1').checked==false && document.getElementById('tipo_dep_2').checked==false ){

			fun_msj('Inserte el tipo de dependencia');
			return false;


}else{




 		   var checkOK = " 0123456789";
           var checkStr = document.getElementById('codigo').value;
           var Validcodigo = true;
           var validGroups = true;

           for (i = 0;  i < checkStr.length;  i++){
             ch = checkStr.charAt(i);
             for (j = 0;  j < checkOK.length;  j++)
                 if (ch == checkOK.charAt(j)){break;}
	            if (j == checkOK.length){
	             Validcodigo = false;
	            break;
	            }
			  }



if (Validcodigo){

						return true;

}


}//fin else




}//fin function


function valida3_arrp00(){


if(document.getElementById('funcionario').value==''){

			fun_msj('Por favor ingrese el nomre del funcionario');
			document.getElementById('funcionario').focus();
			return false;


}else if(document.getElementById('cedula').value==''){

			fun_msj('Por favor ingrese la cedula del funcionario');
			document.getElementById('cedula').focus();
			return false;


}else if(document.getElementById('valida').value==''){

			fun_msj('Ingrese el usuario(Login)');
			document.getElementById('valida').focus();
			return false;

}else if(document.getElementById('valida').value.length<6){

			fun_msj('El usuario(Login) debe tener minimo 6 caracteres');
			document.getElementById('pass').focus();
			return false;


}else if(document.getElementById('pass').value==''){

			fun_msj('Por Favor ingrese la contrase&ntilde;a');
			document.getElementById('pass').focus();
			return false;


}else if(document.getElementById('pass').value.length<6){

			fun_msj('la contrase&ntilde;a debe tener minimo 6 caracteres');
			document.getElementById('pass2').focus();
			return false;


}else if(document.getElementById('pass2').value==''){

			fun_msj('Por Favor ingrese la confirmaci&oacute;n de la contrase&ntilde;a');
			document.getElementById('pass2').focus();
			return false;


}else if(document.getElementById('pass').value != document.getElementById('pass2').value){

			fun_msj('Las contrase&ntilde;as deben coincidir - Por Favor verifique!');
			document.getElementById('pass2').focus();
			return false;


}




}//fin function




function valida4_arrp00(){


if(document.getElementById('valida').value==''){

			fun_msj('Ingrese el nombre del usuario');
			document.getElementById('valida').focus();
			return false;


/*

}else if(document.getElementById('valida').value.length<6){

			fun_msj('la nombre del usuario debe tener minimo 6 caracteres');
			document.getElementById('valida').focus();
			return false;

*/

}else if(document.getElementById('cod_dep_origen')){


    if(document.getElementById('cod_dep_origen').value==""){

			fun_msj('Por favor ingrese la dependencia de origen');
			return false;


   }else if(document.getElementById('funcionario').value==''){

			fun_msj('Por favor ingrese el nombre del funcionario');
			document.getElementById('funcionario').focus();
			return false;


}else if(document.getElementById('cedula').value==''){

			fun_msj('Por favor ingrese la c&eacute;dula funcionario');
			document.getElementById('cedula').focus();
			return false;



}else if(document.getElementById('pass').value==''  &&  document.getElementById('valida_clave').value=="si"){

			fun_msj('Por Favor ingrese la contrase&ntilde;a');
			document.getElementById('pass').focus();
			return false;


}else if(document.getElementById('pass2').value==''  &&  document.getElementById('pass').value!=""){

			fun_msj('Por Favor ingrese la confirmaci&oacute;n de la contrase&ntilde;a');
			document.getElementById('pass2').focus();
			return false;


}else if(document.getElementById('pass').value != document.getElementById('pass2').value){

			fun_msj('Las contrase&ntilde;as no coinciden - Por Favor verifique!');
			document.getElementById('pass2').focus();
			return false;

/*
}else if(document.getElementById('pass').value.length<6){

			fun_msj('la contrase&ntilde;a debe tener minimo 6 caracteres');
			document.getElementById('pass2').focus();
			return false;
*/

}else if(document.getElementById('SHPP00')){

 if(document.getElementById('CATSP0').checked == false && document.getElementById('CUGP00').checked == false && document.getElementById('CFP000').checked == false && document.getElementById('CSIP00').checked == false && document.getElementById('CSCP00').checked == false && document.getElementById('COBP00').checked == false && document.getElementById('CEPP00').checked == false && document.getElementById('CSTP00').checked == false && document.getElementById('CNP000').checked == false && document.getElementById('CIPP00').checked == false && document.getElementById('CFPP00').checked == false && document.getElementById('CIAP01').checked == false && document.getElementById('CATP00').checked == false && document.getElementById('SHPP00').checked == false && document.getElementById('CAP000').checked == false && document.getElementById('CMCP00').checked == false){
	fun_msj('POR FAVOR ELIJA UNO O MAS MODULOS PARA EL USUARIO');
	return false;
}//fin if


}else{

 if(document.getElementById('CATSP0').checked == false && document.getElementById('CUGP00').checked == false && document.getElementById('CFP000').checked == false && document.getElementById('CSIP00').checked == false && document.getElementById('CSCP00').checked == false && document.getElementById('COBP00').checked == false && document.getElementById('CEPP00').checked == false && document.getElementById('CSTP00').checked == false && document.getElementById('CNP000').checked == false && document.getElementById('CIPP00').checked == false && document.getElementById('CFPP00').checked == false && document.getElementById('CIAP01').checked == false &&  document.getElementById('CAP000').checked == false && document.getElementById('CMCP00').checked == false && document.getElementById('CAP000').checked == false && document.getElementById('CATSP0').checked == false){
	fun_msj('POR FAVOR ELIJA UNO O MAS MODULOS PARA EL USUARIO');
	return false;
}//fin if


}//fin else









}else{


 if(document.getElementById('funcionario').value==''){

			fun_msj('Por favor ingrese el nombre del funcionario');
			document.getElementById('funcionario').focus();
			return false;


}else if(document.getElementById('cedula').value==''){

			fun_msj('Por favor ingrese la c&eacute;;dula funcionario');
			document.getElementById('cedula').focus();
			return false;



}else if(document.getElementById('pass').value==''  &&  document.getElementById('valida_clave').value=="si"){

			fun_msj('Por Favor ingrese la contrase&ntilde;a');
			document.getElementById('pass').focus();
			return false;


}else if(document.getElementById('pass2').value==''  &&  document.getElementById('pass').value!=""){

			fun_msj('Por Favor ingrese la confirmaci&oacute;n de la contrase&ntilde;a');
			document.getElementById('pass2').focus();
			return false;


}else if(document.getElementById('pass').value != document.getElementById('pass2').value){

			fun_msj('Las contrase&ntilde;as no coinciden - Por Favor verifique!');
			document.getElementById('pass2').focus();
			return false;



}else if(document.getElementById('pass').value.length<6){

			fun_msj('la contrase&ntilde;a debe tener minimo 6 caracteres');
			document.getElementById('pass2').focus();
			return false;


}else if(document.getElementById('CATSP0').checked == false && document.getElementById('CUGP00').checked == false && document.getElementById('CFP000').checked == false && document.getElementById('CSIP00').checked == false && document.getElementById('CSCP00').checked == false && document.getElementById('COBP00').checked == false && document.getElementById('CEPP00').checked == false && document.getElementById('CSTP00').checked == false && document.getElementById('CNP000').checked == false && document.getElementById('CIPP00').checked == false && document.getElementById('CFPP00').checked == false && document.getElementById('CIAP01').checked == false && document.getElementById('CAP000').checked == false && document.getElementById('CMCP00').checked == false){

	if(document.getElementById('CATP00')){
	  if(document.getElementById('CATP00').checked == false && document.getElementById('SHPP00').checked == false){

           fun_msj('POR FAVOR ELIJA UNO O MAS MODULOS PARA EL USUARIO');
		   return false;

	  }//fin if
	}else{

		fun_msj('POR FAVOR ELIJA UNO O MAS MODULOS PARA EL USUARIO');
		return false;

    }//fin else


}


}//fin else















}//fin function









function valida_cnmp01(){


if(document.getElementById('valida').value==''){

			fun_msj('Ingrese el c&oacute;digo de n&oacute;mina');
			document.getElementById('valida').focus();
			return false;

}else if(document.getElementById('denominacion').value==''){

			fun_msj('Por Favor ingrese la denominaci&oacute;n');
			document.getElementById('denominacion').focus();
			return false;


}else if(document.getElementById('denominacion_devengado').value==''){

			fun_msj('Por Favor ingrese la denominaci&oacute;n devengado');
			document.getElementById('denominacion_devengado').focus();
			return false;


}else if(document.getElementById('dias_laborales').value==''){

			fun_msj('Por Favor ingrese los dias laborables');
			document.getElementById('dias_laborales').focus();
			return false;


}else if(document.getElementById('dias_laborales').value> 6 ){

			fun_msj('los dias laborables no pueden ser mayor de 6');
			document.getElementById('dias_laborales').focus();
			return false;


}else if(document.getElementById('Horas_laborales').value==''){

			fun_msj('Por Favor ingrese las horas laborables');
			document.getElementById('Horas_laborales').focus();
			return false;


}else if(document.getElementById('Horas_laborales').value>8){

			fun_msj('las horas laborables no pueden ser mayores que 8');
			document.getElementById('Horas_laborales').focus();
			return false;


}



}//fin function


/*

function valida_cnmp01(){


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

			fun_msj('Por Favor ingrese la denominaci&oacute;n');
			document.getElementById('denominacion').focus();
			return false;


}else if(document.getElementById('denominacion_devengado').value==''){

			fun_msj('Por Favor ingrese la denominaci&oacute;n devengado');
			document.getElementById('denominacion_devengado').focus();
			return false;


}else if(document.getElementById('dias_laborales').value==''){

			fun_msj('Por Favor ingrese los dias laborables');
			document.getElementById('dias_laborales').focus();
			return false;


}else if(document.getElementById('dias_laborales').value> 6 ){

			fun_msj('los dias laborables no pueden ser mayor de 6');
			document.getElementById('dias_laborales').focus();
			return false;


}else if(document.getElementById('Horas_laborales').value==''){

			fun_msj('Por Favor ingrese las horas laborables');
			document.getElementById('Horas_laborales').focus();
			return false;


}else if(document.getElementById('Horas_laborales').value>8){

			fun_msj('las horas laborables no pueden ser mayores que 8');
			document.getElementById('Horas_laborales').focus();
			return false;


}



}//fin function


*/

function valida2_cnmp01(){


if(document.getElementById('valida').value==''){

			fun_msj('Inserte el codigo');
			document.getElementById('valida').focus();
			return false;

}else if(document.getElementById('denominacion').value==''){

			fun_msj('Por Favor ingrese la denominaci&oacute;n');
			document.getElementById('denominacion').focus();
			return false;


}else if(document.getElementById('denominacion_devengado').value==''){

			fun_msj('Por Favor ingrese la denominaci&oacute;n devengado');
			document.getElementById('denominacion_devengado').focus();
			return false;


}else if(document.getElementById('dias_laborales').value==''){

			fun_msj('Por Favor ingrese los dias laborables');
			document.getElementById('dias_laborales').focus();
			return false;


}else if(document.getElementById('dias_laborales').value> 6 ){

			fun_msj('los dias laborables no pueden ser mayor de 6');
			document.getElementById('dias_laborales').focus();
			return false;


}else if(document.getElementById('Horas_laborales').value==''){

			fun_msj('Por Favor ingrese las horas laborables');
			document.getElementById('Horas_laborales').focus();
			return false;


}else if(document.getElementById('Horas_laborales').value>8){

			fun_msj('las horas laborables no pueden ser mayores que 8');
			document.getElementById('Horas_laborales').focus();
			return false;


}



}//fin function
