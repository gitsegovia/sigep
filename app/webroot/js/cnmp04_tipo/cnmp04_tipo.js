function valida_cnmp04_tipo(){


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



if (Validcodigo){

						return true;

}


}//fin else




}









function comprueba_cnmd04_tipo_codigo(){

if(document.getElementById('cod_nivel_i').value!='0'){

    paramdef ="";
    aux = 0;
    paramdef = paramdef+"cod_nivel_i="+document.getElementById('cod_nivel_i').value+"& enviar=si";


	if(document.getElementById('cod_nivel_i').value!="" ){
pag='/sisap/include/cnmp04_tipo/verifica_codigo.php?'+paramdef;
cargar_contenido('valida_codigo',pag);}else{document.getElementById('valida_codigo').innerHTML = "";}

}//fin if

}


