function shp002_cobranza_pendiente_guardar(){


                   if($("rif").value==""){
                      fun_msj('Debe insertar un cobrador');
                      document.getElementById('rif').focus();
			          return false;
			  }else if($("ano").value==""){
			          fun_msj('Por favor inserte el a&ntilde;o');
                      document.getElementById('ano').focus();
			          return false;
			  }else if($("cobranza_pendiente_acumulada").value==""){
			          fun_msj('Por favor inserte el monto acumulado');
                      document.getElementById('cobranza_pendiente_acumulada').focus();
			          return false;
			  }else if($("enero").value==""){
			          fun_msj('Por favor inserte el monto del mes de enero');
                      document.getElementById('enero').focus();
			          return false;
			  }else if($("febrero").value==""){
			          fun_msj('Por favor inserte el monto del mes de febrero');
                      document.getElementById('febrero').focus();
			          return false;
			  }else if($("marzo").value==""){
			          fun_msj('Por favor inserte el monto del mes de marzo');
                      document.getElementById('marzo').focus();
			          return false;
			  }else if($("abril").value==""){
			          fun_msj('Por favor inserte el monto del mes de abril');
                      document.getElementById('abril').focus();
			          return false;
			  }else if($("mayo").value==""){
			          fun_msj('Por favor inserte el monto del mes de mayo');
                      document.getElementById('mayo').focus();
			          return false;
			  }else if($("junio").value==""){
			          fun_msj('Por favor inserte el monto del mes de junio');
                      document.getElementById('junio').focus();
			          return false;
			  }else if($("julio").value==""){
			          fun_msj('Por favor inserte el monto del mes de julio');
                      document.getElementById('julio').focus();
			          return false;
			  }else if($("agosto").value==""){
			          fun_msj('Por favor inserte el monto del mes de agosto');
                      document.getElementById('agosto').focus();
			          return false;
			  }else if($("septiembre").value==""){
			          fun_msj('Por favor inserte el monto del mes de septiembre');
                      document.getElementById('septiembre').focus();
			          return false;
			  }else if($("octubre").value==""){
			          fun_msj('Por favor inserte el monto del mes de octubre');
                      document.getElementById('octubre').focus();
			          return false;
			  }else if($("noviembre").value==""){
			          fun_msj('Por favor inserte el monto del mes de noviembre');
                      document.getElementById('noviembre').focus();
			          return false;
			  }else if($("diciembre").value==""){
			          fun_msj('Por favor inserte el monto del mes de diciembre');
                      document.getElementById('diciembre').focus();
			          return false;
			  }else{
					  $("ano").readOnly=true;
					  $("cobranza_pendiente_acumulada").readOnly=true;
				      $("enero").readOnly=true;
				      $("febrero").readOnly=true;
				      $("marzo").readOnly=true;
				      $("abril").readOnly=true;
				      $("mayo").readOnly=true;
				      $("junio").readOnly=true;
				      $("julio").readOnly=true;
				      $("agosto").readOnly=true;
				      $("septiembre").readOnly=true;
				      $("octubre").readOnly=true;
				      $("noviembre").readOnly=true;
				      $("diciembre").readOnly=true;

				      if($("guardar")){  $("guardar").disabled=true;}
				      if($("eliminar")){ $("eliminar").disabled=false;}
				      if($("modificar")){$("modificar").disabled=false;}
				      if($("funcion_1")){$("funcion_1").style.display = "none";}
                      if($("funcion_2")){$("funcion_2").style.display = "block";}
               }//fin else
}//fin guardar


function shp002_cobranza_pendiente_editar(){
      $("cobranza_pendiente_acumulada").readOnly=false;
      $("enero").readOnly=false;
      $("febrero").readOnly=false;
      $("marzo").readOnly=false;
      $("abril").readOnly=false;
      $("mayo").readOnly=false;
      $("junio").readOnly=false;
      $("julio").readOnly=false;
      $("agosto").readOnly=false;
      $("septiembre").readOnly=false;
      $("octubre").readOnly=false;
      $("noviembre").readOnly=false;
      $("diciembre").readOnly=false;

      $("guardar").disabled=false;
      $("eliminar").disabled=true;
      $("modificar").disabled=true;
      if($("funcion_1")){$("funcion_1").style.display = "block";}
      if($("funcion_2")){$("funcion_2").style.display = "none";}
}//fin guardar


function shp002_cobranza_pendiente_cancelar(){
      $("cobranza_pendiente_acumulada").readOnly=true;
      $("enero").readOnly=true;
      $("febrero").readOnly=true;
      $("marzo").readOnly=true;
      $("abril").readOnly=true;
      $("mayo").readOnly=true;
      $("junio").readOnly=true;
      $("julio").readOnly=true;
      $("agosto").readOnly=true;
      $("septiembre").readOnly=true;
      $("octubre").readOnly=true;
      $("noviembre").readOnly=true;
      $("diciembre").readOnly=true;

      $("guardar").disabled=true;
      $("eliminar").disabled=false;
      $("modificar").disabled=false;
      if($("funcion_1")){$("funcion_1").style.display = "none";}
      if($("funcion_2")){$("funcion_2").style.display = "block";}
}//fin guardar