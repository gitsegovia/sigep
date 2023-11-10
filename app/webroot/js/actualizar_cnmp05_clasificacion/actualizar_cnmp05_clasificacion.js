function valida_actualizar_cnmp05_clasificacion_ano(){
   var mydate=new Date()
   var year1=mydate.getYear()
   var year=mydate.getYear()
      if (year < 1000)
           year+=1901

           if(document.getElementById('ano').value==''){

			fun_msj('Inserte el a&ntilde;o');
			document.getElementById('ano').focus();
			return false;

	}else if(document.getElementById('ano').value.length<4){

			fun_msj('Inserte un a&ntilde;o correcto');
			document.getElementById('ano').focus();
			return false;


	}else if(document.getElementById('ano').value< 2000 || document.getElementById('ano').value>year){
	fun_msj("Inserte un a&ntilde;o correcto ");
			document.getElementById('ano').focus();
			return false;
	}
}
