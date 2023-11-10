function valida_ccfp03instalacion(){

if(document.getElementById('ano_ejecucion').value==""){
			fun_msj('Ingrese el a&ntilde;o de ejecucion');
			document.getElementById('ano_ejecucion').focus();
			return false;

}else if(document.getElementById('ano_ejecucion').value.length<4){

			fun_msj('Ingrese un a&ntilde;o correcto');
			document.getElementById('ano_ejecucion').focus();
			return false;

}else if(document.getElementById('mes_ejecucion').value==""){

			fun_msj('Ingrese el mes de ejecucion');
			document.getElementById('mes_ejecucion').focus();
			return false;

}else if(document.getElementById('mes_ejecucion').value.length<2){

			fun_msj('Ingrese un mes correcto');
			document.getElementById('mes_ejecucion').focus();
			return false;

}else if(document.getElementById('mes_ejecucion').value.length>2){

			fun_msj('Ingrese un mes correcto');
			document.getElementById('mes_ejecucion').focus();
			return false;

}else if((document.getElementById('mes_ejecucion').value!="01")&&(document.getElementById('mes_ejecucion').value!="02")&&(document.getElementById('mes_ejecucion').value!="03")&&(document.getElementById('mes_ejecucion').value!="04")&&(document.getElementById('mes_ejecucion').value!="05")&&(document.getElementById('mes_ejecucion').value!="06")&&(document.getElementById('mes_ejecucion').value!="07")&&(document.getElementById('mes_ejecucion').value!="08")&&(document.getElementById('mes_ejecucion').value!="09")&&(document.getElementById('mes_ejecucion').value!="10")&&(document.getElementById('mes_ejecucion').value!="11")&&(document.getElementById('mes_ejecucion').value!="12")){

			fun_msj('Ingrese un mes valido entre "01 y 12"');
			document.getElementById('mes_ejecucion').focus();
			return false;

}

}//fin funtion

