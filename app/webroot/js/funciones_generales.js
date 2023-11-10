
function limpiar_msj(v){
    if(v==2){
       id='msj_internal_exito';
    }else{
       id='msj_internal_error';
    }
      new Effect.Fade(id);
     //$("bloque_mensajes").innerHTML="";
}

function error(MSJ){
	document.getElementById("bloque_mensajes").innerHTML='<div id="msj_internal_error" class="error">'+MSJ+'<div>';
	nMiliSegundos=5000;
	window.setTimeout("limpiar_msj(1)", nMiliSegundos);
}

function exito(MSJ){
	document.getElementById("bloque_mensajes").innerHTML='<div id="msj_internal_exito" class="exito">'+MSJ+'<div>';
	nMiliSegundos=5000;
	window.setTimeout("limpiar_msj(2)", nMiliSegundos);
}

function vemail(ID){
   if ((document.getElementById(ID).value.search("@") == -1 ) || ( document.getElementById(ID).value.search("[.*]" ) == -1 ) ) {
		error("Por favor, Revise el Email." );
		document.getElementById(ID).focus();
		return false;
   }else{
      return true;
   }
}

function diferenciaFecha(CfechaNew, CFechaOrig){
  // RESPALDO DE FUNCION ANTERIOR DE CALCULO DE DIFERENCIA DE FECHA
	/*
  var fecha1 = new fecha( CfechaNew );
	var fecha2 = new fecha( CFechaOrig );

	var miFecha1 = new Date( fecha1.anio, fecha1.mes, fecha1.dia );
	var miFecha2 = new Date( fecha2.anio, fecha2.mes, fecha2.dia );

	var diferencia = miFecha1.getTime() - miFecha2.getTime();

	if(diferencia < 0){
		return true;
	}else{
		return false;
	}
  */

  // NUEVO FORMULA DE CALCULO DE FECHAS. Dir. Gral. Informatica
  var NewMonth=CfechaNew.substring(3, 5);
  var NewDay=CfechaNew.substring(0, 2);
  var NewYear=CfechaNew.substring(6,10);

  var OrigMonth=CFechaOrig.substring(3, 5);
  var OrigDay=CFechaOrig.substring(0, 2);
  var OrigYear=CFechaOrig.substring(6,10);

  if (NewYear < OrigYear) {
      return true;
  } else {
    if (NewYear == OrigYear) {
      if (NewMonth < OrigMonth) {

        return true;
      } else {
        if (NewMonth == OrigMonth) {
          if (NewDay < OrigDay) {

            return true;
          } else {

            return false;
          }
        } else {

          return false;
        }
      }
    } else {
      return false;
    }
  }

}

function fecha(cadena){
	var separador= "/";

	if(cadena.indexOf(separador) != -1){
		var posi1 = 0;
		var posi2 = cadena.indexOf(separador, posi1 + 1);
		var posi3 = cadena.indexOf(separador, posi2 + 1);
		this.dia = cadena.substring(posi1, posi2);
		this.mes = cadena.substring(posi2 + 1, posi3);
		this.anio = cadena.substring(posi3 + 1, cadena.length);
	}else{
		this.dia = 0;
		this.mes = 0;
		this.anio = 0;
	}
}

function moneda(id){
    var monto = document.getElementById(id).value;
    if(monto!=""){

       pag="../../include/cfpp05/moneda.php?monto="+monto;
       cargarMonto(id,pag);
    }else{
      document.getElementById(id).value='0,00';
    }
}

function cargarMonto(id,pagina){
	//document.getElementById(target).innerHTML = '<p class=\"load\">Cargando...</p>';
	var myConn = new XHConn();
		if (!myConn) fun_msj("XMLHTTP no esta disponible. Intentalo con un navegador mas nuevo.");
        var peticion = function (oXML) {document.getElementById(id).value = oXML.responseText; };
		myConn.connect(pagina, "GET", "", peticion);
		;
}

function muestra_pista_actas(tipo_acta){
   if(tipo_acta==3){
       document.getElementById('mostrar_defucion').style.display='inline';
       document.getElementById('mostrar_matrimonio').style.display='none';
       document.getElementById('mostrar_naciemiento').style.display='none';
   }else if(tipo_acta==5){
       document.getElementById('mostrar_defucion').style.display='none';
       document.getElementById('mostrar_matrimonio').style.display='inline';
       document.getElementById('mostrar_naciemiento').style.display='none';
   }else if(tipo_acta==6){
       document.getElementById('mostrar_defucion').style.display='none';
       document.getElementById('mostrar_matrimonio').style.display='none';
       document.getElementById('mostrar_naciemiento').style.display='inline';
   }
}


function cambiar_rci(){
     if($('condicion_juridica_1').checked==true){
         $('srif').innerHTML='C&eacute;dula Identidad:';
         $('campo_cirif').innerHTML = '<input type="text" name="data[cugp_usuarios][cedula]" value=""  id="cedula" size="20" maxlength="10" class="input_1" onKeyPress="return solonumeros(event);"/>';
         $('td_apellidos').innerHTML = 'Apellidos:';
         $('tr_nombres').style.visibility = 'visible';
     }else{
         $('srif').innerHTML='R.I.F.:';
         $('campo_cirif').innerHTML = '<input type="text" name="data[cugp_usuarios][cedula]" value=""  id="cedula" size="20" maxlength="10" class="input_1" onChange="mascara_rif(\'cedula\');"/>';
         $('td_apellidos').innerHTML = 'Raz&oacute;n Social:';
         $('tr_nombres').style.visibility = 'hidden';
     }
}

