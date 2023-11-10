function validar_monto_cfpp03(){
    if(document.getElementById('monto').value=='' || document.getElementById('monto').value=='0,00'){
			fun_msj('Inserte un monto correcto.');
			document.getElementById('monto').focus();
			return false;
	}else if(document.getElementById('select_6').value == ""){
			fun_msj('Seleccione Partida');
			document.getElementById('select_6').focus();
			return false;
}else if(document.getElementById('select_7').value == ""){
			fun_msj('Seleccione Generica');
			document.getElementById('select_7').focus();
			return false;
}else if(document.getElementById('select_8').value == ""){
			fun_msj('Seleccione Especifica');
			document.getElementById('select_8').focus();
			return false;
}else if(document.getElementById('select_9').value == ""){
			fun_msj('Seleccione Sub-Especifica');
			document.getElementById('select_9').focus();
			return false;
}else if(document.getElementById('select_10').value == "" && document.getElementById('select_10').length >=1){
			fun_msj('Seleccione Auxiliar');
			document.getElementById('select_10').focus();
			return false;
}else{
}
}//fin funcion

function valida_cfpp03_ano(){
   var mydate=new Date()
   var year1=mydate.getYear()
   var year=mydate.getYear()
      if (year < 1000)
           year+=1901

           if(document.getElementById('ano_presupuesto').value==''){

			fun_msj('Inserte el a&ntilde;o');
			document.getElementById('ano_presupuesto').focus();
			return false;

	}else if(document.getElementById('ano_presupuesto').value.length<4){

			fun_msj('Inserte un a&ntilde;o correcto');
			document.getElementById('ano_presupuesto').focus();
			return false;


	}else if(document.getElementById('ano_presupuesto').value< 2000 || document.getElementById('ano_presupuesto').value>year){
	fun_msj("Inserte un a&ntilde;o correcto ");
			document.getElementById('ano_presupuesto').focus();
			return false;
	}
}