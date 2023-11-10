function valida_cfpp15(){
   var mydate=new Date()
   var year1=mydate.getYear()
   var year=mydate.getYear()
      if (year < 1000)
           year+=1901

   if(document.getElementById('anoPresupuesto').value==''){

			fun_msj('Inserte el a&ntilde;o presupuestario');
			document.getElementById('anoPresupuesto').focus();
			return false;

	}else if(document.getElementById('anoPresupuesto').value.length<4){

			fun_msj('Inserte un a&ntilde;o correcto');
			document.getElementById('anoPresupuesto').focus();
			return false;


	}else if(document.getElementById('anoPresupuesto').value< 2000 || document.getElementById('anoPresupuesto').value>year){
	fun_msj("Inserte un a&ntilde;o correcto ");
			document.getElementById('anoPresupuesto').focus();
			return false;
	}else if(document.getElementById('select_1').value==''){

			fun_msj('Seleccione Sector');
			document.getElementById('select_1').focus();
			return false;

}else if(document.getElementById('select_2').value == ""){

			fun_msj('Seleccione Programa');
			document.getElementById('select_2').focus();
			return false;


}else if(document.getElementById('select_3').value == ""){

			fun_msj('Seleccione Sub Programa');
			document.getElementById('select_3').focus();
			return false;


}else if(document.getElementById('select_4').value == ""){

			fun_msj('Seleccione Proyecto');
			document.getElementById('select_4').focus();
			return false;

}else if(document.getElementById('select_5').value == ""){

			fun_msj('Seleccione Actividad u Obra');
			document.getElementById('select_5').focus();
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



}else if(document.getElementById('select_10').value == ""){

			fun_msj('Seleccione auxiliar');
			document.getElementById('select_10').focus();
			return false;



}else if(document.getElementById('programa_social').value==""){

			fun_msj('Inserte el programa social');
			document.getElementById('programa_social').focus();
			return false;

}else if(document.getElementById('organismo').value==""){

			fun_msj('Inserte el organismo');
			document.getElementById('organismo').focus();
			return false;



}else if(document.getElementById('asignacion_anual').value==""){

			fun_msj('Inserte asignaci&oacute;n anual');
			document.getElementById('asignacion_anual').focus();
			return false;

}else{





      }





}//fin funtion