
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


function vpassw(campo, id_passw){
        if (campo.checked) {
        	document.getElementById(id_passw).type="text";
        }else if(campo.checked==false){
			document.getElementById(id_passw).type="password";
        }
    }

function espacio_blanco_str(valor){
var espacios = false;
var campos = false;
var cont = 0;

while (!espacios && (cont < valor.length)) {
  if (valor.charAt(cont) == " ")
    campos = true;
  cont++;
}

if (campos) {
  return false;
}else{return true;}
}

function validarvacio_str(str){
	while(''+str.charAt(0)==' ')
	str=str.substring(1,str.length);
	while(''+str.charAt(str.length-1)==' ')
	str=str.substring(0,str.length-1);
	return str;
}

/*
function validarvacio_str(valor){
	var texto;
	while(''+valor.charAt(0)==' ')
	{
		valor=valor.substring(1,valor.length)
	}
	texto = valor;
	return texto;
}
*/

function valida_cambio_clave(){
	if(document.getElementById('clave_actual').value==''){
			fun_msj('Ingrese Clave Actual!');
			//document.getElementById('cambiar_clave_div').innerHTML="Ingrese Clave Actual!";
	        new Effect.Highlight('clave_actual');
			document.getElementById('clave_actual').focus();
			return false;
	}else if(document.getElementById('clave_nueva1').value==''){
			fun_msj('Ingrese la Nueva Clave!');
			//document.getElementById('cambiar_clave_div').innerHTML="Ingrese la Nueva Clave!";
	        new Effect.Highlight('clave_nueva1');
			document.getElementById('clave_nueva1').focus();
			return false;
	}else if(document.getElementById('clave_nueva2').value==''){
			fun_msj('Ingrese la Confirmaci&oacute;n de la Nueva Clave!');
			//document.getElementById('cambiar_clave_div').innerHTML="Ingrese la Confirmaci&oacute;n de la Nueva Clave!";
	        new Effect.Highlight('clave_nueva2');
			document.getElementById('clave_nueva2').focus();
			return false;
	}else if(document.getElementById('clave_nueva1').value != document.getElementById('clave_nueva2').value){
			fun_msj('Las contrase&ntilde;as no coinciden - Por Favor verifique!');
			//document.getElementById('cambiar_clave_div').innerHTML="Las contrase&ntilde;as no coinciden - Por Favor verifique!";
	        new Effect.Highlight('clave_nueva1');
	        new Effect.Highlight('clave_nueva2');
			document.getElementById('clave_nueva2').focus();
			return false;
	}else if(document.getElementById('clave_nueva1').value.length < 6){
			fun_msj('Ingrese la Nueva Clave con un m&iacute;nimo de 6 caracteres!');
			//document.getElementById('cambiar_clave_div').innerHTML="Ingrese la Nueva Clave con un m&iacute;nimo de 6 caracteres!";
	        new Effect.Highlight('clave_nueva1');
			document.getElementById('clave_nueva1').focus();
			return false;
	}else if(document.getElementById('clave_nueva1').value.length > 25){
			fun_msj('Ingrese la Nueva Clave con un m&aacute;ximo de 25 caracteres!');
			//document.getElementById('cambiar_clave_div').innerHTML="Ingrese la Nueva Clave con un m&aacute;ximo de 25 caracteres!";
	        new Effect.Highlight('clave_nueva1');
			document.getElementById('clave_nueva1').focus();
			return false;
	}else if(validarvacio_str(document.getElementById('clave_nueva1').value) == ""){
			fun_msj('Ingrese la Nueva Clave sin espacios en blanco!');
			//document.getElementById('cambiar_clave_div').innerHTML="Ingrese la Nueva Clave sin espacios en blanco!";
	        new Effect.Highlight('clave_nueva1');
			document.getElementById('clave_nueva1').focus();
			return false;
	}else if(espacio_blanco_str(document.getElementById('clave_nueva1').value) == false){
			fun_msj('La clave no puede contener espacios en blanco!');
			//document.getElementById('cambiar_clave_div').innerHTML="La clave no puede contener espacios en blanco!";
	        new Effect.Highlight('clave_nueva1');
			document.getElementById('clave_nueva1').focus();
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

	re1 = /[A-Za-z]/;
	re2 = /[0-9]/;
	re3 = /[!@#)$%&*<,>;:(-._]/;

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


}else if(document.getElementById('pass').value == document.getElementById('valida').value){

			fun_msj('La contrase&ntilde;a debe de ser diferente al nombre de usuario (Login)');
			document.getElementById('pass').focus();
			return false;


}else if(document.getElementById('pass').value.length<6){

			fun_msj('la contrase&ntilde;a debe tener minimo 6 caracteres');
			document.getElementById('pass2').focus();
			return false;


}else if(document.getElementById('pass').value.length>25){

			fun_msj('la contrase&ntilde;a debe tener un m&aacute;ximo de 25 caracteres');
			document.getElementById('pass2').focus();
			return false;


}else if(validarvacio_str(document.getElementById('pass').value) == ""){
			fun_msj('Ingrese la contrase&ntilde;a sin espacios en blanco!');
			document.getElementById('pass').focus();
			return false;
}else if(espacio_blanco_str(document.getElementById('pass').value) == false){
			fun_msj('La contrase&ntilde;a no puede contener espacios en blanco!');
			document.getElementById('pass').focus();
			return false;
}else if(document.getElementById('pass2').value==''){

			fun_msj('Por Favor ingrese la confirmaci&oacute;n de la contrase&ntilde;a');
			document.getElementById('pass2').focus();
			return false;


}else if(document.getElementById('pass').value != document.getElementById('pass2').value){

			fun_msj('Las contrase&ntilde;as deben coincidir - Por Favor verifique!');
			document.getElementById('pass2').focus();
			return false;

}else if(!re1.test(document.getElementById('pass').value)) {

			fun_msj('La contrase&ntilde;a debe tener al menos una letra (a-z)!');
			document.getElementById('pass').focus();
			return false;
}else if(!re2.test(document.getElementById('pass').value)) {

			fun_msj('La contrase&ntilde;a debe tener al menos un d&iacute;gito (0-9)!');
			document.getElementById('pass').focus();
			return false;
}else if(!re3.test(document.getElementById('pass').value)) {

			fun_msj('La contrase&ntilde;a es incorrecta debe contener una combinaci&oacute;n entre letras(a-z), n&uacute;meros(0-9) y s&iacute;mbolos especiales como: <br>! @ # $ _ % & * ( ) < > . , ; : -');
			document.getElementById('pass').focus();
			return false;
}

}//fin function




function valida4_arrp00(){
var verifica = 1;

	re1 = /[A-Za-z]/;
	re2 = /[0-9]/;
	re3 = /[!@#)$%&*<,>;:(-._]/;

if(document.getElementById('valida').value==''){

			fun_msj('Ingrese el nombre del usuario');
			document.getElementById('valida').focus();
			return false;


}else if(document.getElementById('valida').value.length<6){

			fun_msj('la nombre del usuario debe tener minimo 6 caracteres');
			document.getElementById('valida').focus();
			return false;


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


}else if(document.getElementById('pass').value!="" && document.getElementById('pass').value == document.getElementById('valida').value){

			fun_msj('La contrase&ntilde;a debe de ser diferente al nombre de usuario (Login)');
			document.getElementById('pass').focus();
			return false;


}else if(document.getElementById('pass').value!="" && document.getElementById('pass').value.length<6){

			fun_msj('la contrase&ntilde;a debe tener minimo 6 caracteres');
			document.getElementById('pass2').focus();
			return false;


}else if(document.getElementById('pass').value!="" && document.getElementById('pass').value.length>25){

			fun_msj('la contrase&ntilde;a debe tener un m&aacute;ximo de 25 caracteres');
			document.getElementById('pass2').focus();
			return false;
                        
}

else if(document.getElementById('pass').value!="" && validarvacio_str(document.getElementById('pass').value) == ""){
			fun_msj('Ingrese la contrase&ntilde;a sin espacios en blanco!');
			document.getElementById('pass').focus();
			return false;
}else if(document.getElementById('pass').value!="" && espacio_blanco_str(document.getElementById('pass').value) == false){
			fun_msj('La contrase&ntilde;a no puede contener espacios en blanco!');
			document.getElementById('pass').focus();
			return false;
}

else if(document.getElementById('pass').value!="" && !re1.test(document.getElementById('pass').value)) {

			fun_msj('La contrase&ntilde;a debe tener al menos una letra (a-z)!');
			document.getElementById('pass').focus();
			return false;
}else if(document.getElementById('pass').value!="" && !re2.test(document.getElementById('pass').value)) {

			fun_msj('La contrase&ntilde;a debe tener al menos un d&iacute;gito (0-9)!');
			document.getElementById('pass').focus();
			return false;
}else if(document.getElementById('pass').value!="" && !re3.test(document.getElementById('pass').value)) {

			fun_msj('La contrase&ntilde;a es incorrecta debe contener una combinaci&oacute;n entre letras(a-z), n&uacute;meros(0-9) y s&iacute;mbolos especiales como: <br>! @ # $ _ % & * ( ) < > . , ; : -');
			document.getElementById('pass').focus();
			return false;

}

if(document.getElementById('CUGP00')) {
    if (document.getElementById('CUGP00').checked == true){
        verifica = 0;
    } 
}
if(document.getElementById('CFP000')){
    if(document.getElementById('CFP000').checked == true){
        verifica = 0;
    }
}
if(document.getElementById('CSIP00')){
    if(document.getElementById('CSIP00').checked == true){
        verifica = 0;
    } 
}
if(document.getElementById('CSRP00')){
    if(document.getElementById('CSRP00').checked == true){
        verifica = 0;
    } 
}
if(document.getElementById('CSIP00')){
    if(document.getElementById('CSIP00').checked == true){
        verifica = 0;
    } 
}
if(document.getElementById('CSCP00')){
    if(document.getElementById('CSCP00').checked == true){
        verifica = 0;
    } 
}
if(document.getElementById('COBP00')){
    if(document.getElementById('COBP00').checked == true){
        var verifica = 0;
    } 
}
if(document.getElementById('CEP000')){
    if(document.getElementById('CEP000').checked == true){
        verifica = 0;
    } 
}
if(document.getElementById('CSTP00')){
    if(document.getElementById('CSTP00').checked == true){
        verifica = 0;
    } 
}
if(document.getElementById('CNP000')){
    if(document.getElementById('CNP000').checked == true){
        verifica = 0;
    } 
}
if(document.getElementById('CIPP00')){
    if(document.getElementById('CIPP00').checked == true){
        verifica = 0;
    } 
}
if(document.getElementById('CFPP00')){
    if(document.getElementById('CFPP00').checked == true){
        verifica = 0;
    } 
}
if(document.getElementById('CAO000')){
    if(document.getElementById('CAO000').checked == true){
        verifica = 0;
    }
}
if(document.getElementById('CATP00')){
    if(document.getElementById('CATP00').checked == true){
        verifica = 0;
    }
}
if(document.getElementById('SHPP00')){
    if(document.getElementById('SHPP00').checked == true){
        verifica = 0;
    }
}
if(document.getElementById('CAP000')){
    if(document.getElementById('CAP000').checked == true){
        verifica = 0;
    }
}
if(document.getElementById('CATSP0')){
    if(document.getElementById('CATSP0').checked == true){
        verifica = 0;
    }
}
if(document.getElementById('CMCP00')){
    if(document.getElementById('CMCP00').checked == true){
        verifica = 0;
    }
}
if(document.getElementById('CONP00')){
    if(document.getElementById('CONP00').checked == true){
        verifica = 0;
    }
}
if(document.getElementById('CIAP01')){
    if(document.getElementById('CIAP01').checked == true){
        verifica = 0;
    }
}
if(document.getElementById('CPOP00')){
    if(document.getElementById('CPOP00').checked == true){
        verifica = 0;
    }
}
if(document.getElementById('CSPP00')){
    if(document.getElementById('CSPP00').checked == true){
        verifica = 0;
    }
}
if(document.getElementById('CODVI0')){
    if(document.getElementById('CODVI0').checked == true){
        verifica = 0;
    }
}
if(document.getElementById('CDIP00')){
    if(document.getElementById('CDIP00').checked == true){
        verifica = 0;
    }    
}

if (verifica==1){
	fun_msj('POR FAVOR ELIJA UNO O MAS MODULOS PARA EL USUARIO');
	return false;    
}




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


}else if(document.getElementById('pass').value.length>25){

			fun_msj('la contrase&ntilde;a debe tener un m&aacute;ximo de 25 caracteres');
			document.getElementById('pass2').focus();
			return false;
}

else if(!re1.test(document.getElementById('pass').value)) {

			fun_msj('La contrase&ntilde;a debe tener al menos una letra (a-z)!');
			document.getElementById('pass').focus();
			return false;
}else if(!re2.test(document.getElementById('pass').value)) {

			fun_msj('La contrase&ntilde;a debe tener al menos un d&iacute;gito (0-9)!');
			document.getElementById('pass').focus();
			return false;
}else if(!re3.test(document.getElementById('pass').value)) {

			fun_msj('La contrase&ntilde;a es incorrecta debe contener una combinaci&oacute;n entre letras(a-z), n&uacute;meros(0-9) y s&iacute;mbolos especiales como: <br>! @ # $ _ % & * ( ) < > . , ; : -');
			document.getElementById('pass').focus();
			return false;
}



if(document.getElementById('CUGP00')) {
    if (document.getElementById('CUGP00').checked == true){
        verifica = 0;
    } 
}
if(document.getElementById('CFP000')){
    if(document.getElementById('CFP000').checked == true){
        verifica = 0;
    }
}
if(document.getElementById('CSIP00')){
    if(document.getElementById('CSIP00').checked == true){
        verifica = 0;
    } 
}
if(document.getElementById('CSRP00')){
    if(document.getElementById('CSRP00').checked == true){
        verifica = 0;
    } 
}
if(document.getElementById('CSIP00')){
    if(document.getElementById('CSIP00').checked == true){
        verifica = 0;
    } 
}
if(document.getElementById('CSCP00')){
    if(document.getElementById('CSCP00').checked == true){
        verifica = 0;
    } 
}
if(document.getElementById('COBP00')){
    if(document.getElementById('COBP00').checked == true){
        var verifica = 0;
    } 
}
if(document.getElementById('CEP000')){
    if(document.getElementById('CEP000').checked == true){
        verifica = 0;
    } 
}
if(document.getElementById('CSTP00')){
    if(document.getElementById('CSTP00').checked == true){
        verifica = 0;
    } 
}
if(document.getElementById('CNP000')){
    if(document.getElementById('CNP000').checked == true){
        verifica = 0;
    } 
}
if(document.getElementById('CIPP00')){
    if(document.getElementById('CIPP00').checked == true){
        verifica = 0;
    } 
}
if(document.getElementById('CFPP00')){
    if(document.getElementById('CFPP00').checked == true){
        verifica = 0;
    } 
}
if(document.getElementById('CAO000')){
    if(document.getElementById('CAO000').checked == true){
        verifica = 0;
    }
}
if(document.getElementById('CATP00')){
    if(document.getElementById('CATP00').checked == true){
        verifica = 0;
    }
}
if(document.getElementById('SHPP00')){
    if(document.getElementById('SHPP00').checked == true){
        verifica = 0;
    }
}
if(document.getElementById('CAP000')){
    if(document.getElementById('CAP000').checked == true){
        verifica = 0;
    }
}
if(document.getElementById('CATSP0')){
    if(document.getElementById('CATSP0').checked == true){
        verifica = 0;
    }
}
if(document.getElementById('CMCP00')){
    if(document.getElementById('CMCP00').checked == true){
        verifica = 0;
    }
}
if(document.getElementById('CONP00')){
    if(document.getElementById('CONP00').checked == true){
        verifica = 0;
    }
}
if(document.getElementById('CIAP01')){
    if(document.getElementById('CIAP01').checked == true){
        verifica = 0;
    }
}
if(document.getElementById('CPOP00')){
    if(document.getElementById('CPOP00').checked == true){
        verifica = 0;
    }
}
if(document.getElementById('CSPP00')){
    if(document.getElementById('CSPP00').checked == true){
        verifica = 0;
    }
}
if(document.getElementById('CODVI0')){
    if(document.getElementById('CODVI0').checked == true){
        verifica = 0;
    }
}
if(document.getElementById('CDIP00')){
    if(document.getElementById('CDIP00').checked == true){
        verifica = 0;
    }    
}

if (verifica==1){
	fun_msj('POR FAVOR ELIJA UNO O MAS MODULOS PARA EL USUARIO');
	return false;    
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
