function sin_eslas(e){

 tecla_codigo = (document.all) ? e.keyCode : e.which;
if(tecla_codigo!=47 && tecla_codigo!=32){ return true;}
patron =/[0-9\-]/;
tecla_valor = String.fromCharCode(tecla_codigo);
return patron.test(tecla_valor);

}//fin function

function caracter_sin_punto(e){

 tecla_codigo = (document.all) ? e.keyCode : e.which;
if(tecla_codigo!=46){ return true;}
patron =/[0-9\-]/;
tecla_valor = String.fromCharCode(tecla_codigo);
return patron.test(tecla_valor);

}//fin function

function caracter_sin_punto_eslas(e){
 tecla_codigo = (document.all) ? e.keyCode : e.which;
if(tecla_codigo!=46 && tecla_codigo!=47 && tecla_codigo!=32){ return true;}
patron =/[0-9\-]/;
tecla_valor = String.fromCharCode(tecla_codigo);
return patron.test(tecla_valor);

}//fin function

function validar_sin_cero_cod_serie(){

num = eval(document.getElementById('cod_serie').value);
dis = document.getElementById('cod_serie').value;
pas = 0;
for(j=0; j<dis.length; j++){
		ch =  dis.charAt(j);
		if(ch!="0"){pas=1;}

}

if((num<10 && dis.length==1) || pas==0){
            fun_msj('Inserte un n&uacute;mero');
			document.getElementById('cod_serie').focus();
			document.getElementById('guardar').disabled = true;
			return false;
}else{
           	document.getElementById('guardar').disabled = false;

}

}//fin function

function link_javascript_visor(id, type, funcion, http_uses, http_respuesta, mensaje,
								url1,  div1,
								url2,  div2,
								url3,  div3,
								url4,  div4,
								url5,  div5,
								url6,  div6,
								url7,  div7,
								url8,  div8,
								url9,  div9,
								url10, div10
){
if(mensaje){

			if (confirm(mensaje)) {

					if(url1){ new Ajax.Updater(id, type, funcion   , div1 , url1,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, requestHeaders:['X-Update', div1]},  http_uses, http_respuesta)}
					if(url2){ new Ajax.Updater(id, type, funcion   , div2 , url2,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, requestHeaders:['X-Update', div2]},  http_uses, http_respuesta)}
					if(url3){ new Ajax.Updater(id, type, funcion   , div3 , url3,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, requestHeaders:['X-Update', div3]},  http_uses, http_respuesta)}
					if(url4){ new Ajax.Updater(id, type, funcion   , div4 , url4,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, requestHeaders:['X-Update', div4]},  http_uses, http_respuesta)}
					if(url5){ new Ajax.Updater(id, type, funcion   , div5 , url5,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, requestHeaders:['X-Update', div5]},  http_uses, http_respuesta)}
					if(url6){ new Ajax.Updater(id, type, funcion   , div6 , url6,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, requestHeaders:['X-Update', div6]},  http_uses, http_respuesta)}
					if(url7){ new Ajax.Updater(id, type, funcion   , div7 , url7,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, requestHeaders:['X-Update', div7]},  http_uses, http_respuesta)}
					if(url8){ new Ajax.Updater(id, type, funcion   , div8 , url8,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, requestHeaders:['X-Update', div8]},  http_uses, http_respuesta)}
					if(url9){ new Ajax.Updater(id, type, funcion   , div9 , url9,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, requestHeaders:['X-Update', div9]},  http_uses, http_respuesta)}
					if(url10){new Ajax.Updater(id, type, funcion   , div10, url10, {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, requestHeaders:['X-Update', div10]}, http_uses, http_respuesta)}

			}else{ return false; }


}else{
                    if(url1){ new Ajax.Updater(id, type, funcion   , div1 , url1,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, requestHeaders:['X-Update', div1]},  http_uses, http_respuesta)}
					if(url2){ new Ajax.Updater(id, type, funcion   , div2 , url2,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, requestHeaders:['X-Update', div2]},  http_uses, http_respuesta)}
					if(url3){ new Ajax.Updater(id, type, funcion   , div3 , url3,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, requestHeaders:['X-Update', div3]},  http_uses, http_respuesta)}
					if(url4){ new Ajax.Updater(id, type, funcion   , div4 , url4,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, requestHeaders:['X-Update', div4]},  http_uses, http_respuesta)}
					if(url5){ new Ajax.Updater(id, type, funcion   , div5 , url5,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, requestHeaders:['X-Update', div5]},  http_uses, http_respuesta)}
					if(url6){ new Ajax.Updater(id, type, funcion   , div6 , url6,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, requestHeaders:['X-Update', div6]},  http_uses, http_respuesta)}
					if(url7){ new Ajax.Updater(id, type, funcion   , div7 , url7,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, requestHeaders:['X-Update', div7]},  http_uses, http_respuesta)}
					if(url8){ new Ajax.Updater(id, type, funcion   , div8 , url8,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, requestHeaders:['X-Update', div8]},  http_uses, http_respuesta)}
					if(url9){ new Ajax.Updater(id, type, funcion   , div9 , url9,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, requestHeaders:['X-Update', div9]},  http_uses, http_respuesta)}
					if(url10){new Ajax.Updater(id, type, funcion   , div10, url10, {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, requestHeaders:['X-Update', div10]}, http_uses, http_respuesta)}

}//fin else

}//fin function

function link_javascript_visor_submit(id, type, funcion, http_uses, http_respuesta, event, mensaje,
								url1,  div1,
								url2,  div2,
								url3,  div3,
								url4,  div4,
								url5,  div5,
								url6,  div6,
								url7,  div7,
								url8,  div8,
								url9,  div9,
								url10, div10
){

 if(mensaje){
			if (confirm(mensaje)) {
					if(url1){ new Ajax.Updater(id, type, funcion   , div1 , url1,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, parameters:Form.serialize(Event.element(event).form), requestHeaders:['X-Update', div1]},  http_uses, http_respuesta)}
					if(url2){ new Ajax.Updater(id, type, funcion   , div2 , url2,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, parameters:Form.serialize(Event.element(event).form), requestHeaders:['X-Update', div2]},  http_uses, http_respuesta)}
					if(url3){ new Ajax.Updater(id, type, funcion   , div3 , url3,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, parameters:Form.serialize(Event.element(event).form), requestHeaders:['X-Update', div3]},  http_uses, http_respuesta)}
					if(url4){ new Ajax.Updater(id, type, funcion   , div4 , url4,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, parameters:Form.serialize(Event.element(event).form), requestHeaders:['X-Update', div4]},  http_uses, http_respuesta)}
					if(url5){ new Ajax.Updater(id, type, funcion   , div5 , url5,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, parameters:Form.serialize(Event.element(event).form), requestHeaders:['X-Update', div5]},  http_uses, http_respuesta)}
					if(url6){ new Ajax.Updater(id, type, funcion   , div6 , url6,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, parameters:Form.serialize(Event.element(event).form), requestHeaders:['X-Update', div6]},  http_uses, http_respuesta)}
					if(url7){ new Ajax.Updater(id, type, funcion   , div7 , url7,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, parameters:Form.serialize(Event.element(event).form), requestHeaders:['X-Update', div7]},  http_uses, http_respuesta)}
					if(url8){ new Ajax.Updater(id, type, funcion   , div8 , url8,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, parameters:Form.serialize(Event.element(event).form), requestHeaders:['X-Update', div8]},  http_uses, http_respuesta)}
					if(url9){ new Ajax.Updater(id, type, funcion   , div9 , url9,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, parameters:Form.serialize(Event.element(event).form), requestHeaders:['X-Update', div9]},  http_uses, http_respuesta)}
					if(url10){new Ajax.Updater(id, type, funcion   , div10, url10, {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, parameters:Form.serialize(Event.element(event).form), requestHeaders:['X-Update', div10]}, http_uses, http_respuesta)}

			}else{ return false; }
}else{
                    if(url1){ new Ajax.Updater(id, type, funcion   , div1 , url1,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, parameters:Form.serialize(Event.element(event).form), requestHeaders:['X-Update', div1]},  http_uses, http_respuesta)}
					if(url2){ new Ajax.Updater(id, type, funcion   , div2 , url2,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, parameters:Form.serialize(Event.element(event).form), requestHeaders:['X-Update', div2]},  http_uses, http_respuesta)}
					if(url3){ new Ajax.Updater(id, type, funcion   , div3 , url3,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, parameters:Form.serialize(Event.element(event).form), requestHeaders:['X-Update', div3]},  http_uses, http_respuesta)}
					if(url4){ new Ajax.Updater(id, type, funcion   , div4 , url4,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, parameters:Form.serialize(Event.element(event).form), requestHeaders:['X-Update', div4]},  http_uses, http_respuesta)}
					if(url5){ new Ajax.Updater(id, type, funcion   , div5 , url5,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, parameters:Form.serialize(Event.element(event).form), requestHeaders:['X-Update', div5]},  http_uses, http_respuesta)}
					if(url6){ new Ajax.Updater(id, type, funcion   , div6 , url6,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, parameters:Form.serialize(Event.element(event).form), requestHeaders:['X-Update', div6]},  http_uses, http_respuesta)}
					if(url7){ new Ajax.Updater(id, type, funcion   , div7 , url7,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, parameters:Form.serialize(Event.element(event).form), requestHeaders:['X-Update', div7]},  http_uses, http_respuesta)}
					if(url8){ new Ajax.Updater(id, type, funcion   , div8 , url8,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, parameters:Form.serialize(Event.element(event).form), requestHeaders:['X-Update', div8]},  http_uses, http_respuesta)}
					if(url9){ new Ajax.Updater(id, type, funcion   , div9 , url9,  {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, parameters:Form.serialize(Event.element(event).form), requestHeaders:['X-Update', div9]},  http_uses, http_respuesta)}
					if(url10){new Ajax.Updater(id, type, funcion   , div10, url10, {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, parameters:Form.serialize(Event.element(event).form), requestHeaders:['X-Update', div10]}, http_uses, http_respuesta)}

}//fin else

}//fin function

function documento_link(id, type, funcion, http_uses, http_respuesta, fila_efecto, mensaje, url1,  div1){
      if(mensaje){
			if (confirm(mensaje)) {
			      new Ajax.Updater(id, type, funcion, div1, url1,  {asynchronous:true, evalScripts:true,  onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')},  requestHeaders:['X-Update', div1]}, http_uses, http_respuesta);
			      if(fila_efecto){ setTimeout(fila_efecto,1000); }
			}else{ return false; }
	}else{
			     new Ajax.Updater(id, type, funcion, div1,  url1,  {asynchronous:true, evalScripts:true,  onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')},  requestHeaders:['X-Update', div1]}, http_uses, http_respuesta);
	             if(fila_efecto){ setTimeout(fila_efecto,1000); }
	}//fin else

}//fin function

function ver_documento_prestaciones(url_busqueda,ID){
		document.getElementById('tab_tasa_interes').innerHTML="";
		document.getElementById('tab_dias_antiguedad').innerHTML="";
		document.getElementById('tab_bono_vacacional').innerHTML="";
		document.getElementById('tab_disfrute_vacacional').innerHTML="";
		document.getElementById('tab_aguinaldo').innerHTML="";
		document.getElementById('tab_semana_salarial').innerHTML="";
		document.getElementById('tab_rango').innerHTML="";
		document.getElementById('tab_depositos_fideicomisos').innerHTML="";
		document.getElementById('tab_datos_personales').innerHTML="";
		document.getElementById('tab_devengado').innerHTML="";
		document.getElementById('tab_parametro_cobro').innerHTML="";
		document.getElementById('tab_anticipos').innerHTML="";
		document.getElementById('tab_adicionales').innerHTML="";
		document.getElementById('tab_anticipo_bono_transfe').innerHTML="";
new Ajax.Updater('', '',   ''   , ID ,url_busqueda, {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, requestHeaders:['X-Update', ID]}, session_update());
}//fin function

function ver_documento_vacaciones_pestana(url_busqueda,ID){
		document.getElementById('tab_bono_vacacional').innerHTML="";
		document.getElementById('tab_disfrute_vacacional').innerHTML="";
		document.getElementById('tab_dias_adic_bono_vacacional').innerHTML="";
		document.getElementById('tab_disfrute_vacaciones_dias_adic').innerHTML="";
		document.getElementById('tab_bonificacion_vacaciones').innerHTML="";
		document.getElementById('tab_dias_jornada_extra').innerHTML="";
		document.getElementById('tab_identificacion_transacciones').innerHTML="";
		document.getElementById('tab_registro_vacaciones').innerHTML="";
new Ajax.Updater('', '',   ''   , ID ,url_busqueda, {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, requestHeaders:['X-Update', ID]}, session_update());
}//fin function

function ver_documento_fideicomisos(url_busqueda,ID){
		document.getElementById('tab_transa_inclu').innerHTML="";
		document.getElementById('tab_transa_noin').innerHTML="";
		document.getElementById('tab_bono_vacacional').innerHTML="";
		document.getElementById('tab_semana_salarial').innerHTML="";
		document.getElementById('tab_aguinaldo').innerHTML="";
		document.getElementById('tab_registro_cuentas_fidei').innerHTML="";
		document.getElementById('tab_trimestre_arranque').innerHTML="";
		document.getElementById('tab_calculo_deposito').innerHTML="";
		document.getElementById('tab_cerrar_fideicomiso').innerHTML="";
		document.getElementById('tab_generar_txt').innerHTML="";
new Ajax.Updater('', '',   ''   , ID ,url_busqueda, {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, requestHeaders:['X-Update', ID]}, session_update());
}//fin function

function ver_documento_atencion(url_busqueda,ID){
		document.getElementById('tab_datos_personales').innerHTML="";
		document.getElementById('tab_solicitud').innerHTML="";
		document.getElementById('tab_evaluacion').innerHTML="";
		document.getElementById('tab_ayuda').innerHTML="";
new Ajax.Updater('', '',   ''   , ID ,url_busqueda, {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, requestHeaders:['X-Update', ID]}, session_update());
}//fin function

function checkAll(IDFROM,bool) {

	var inputs = document.getElementById(IDFROM).getElementsByTagName('input');
    if($('check_seleccion_todo').checked==true){
       bool = true;
    }else{
       bool = false;
    }
	for (var i=0; i<inputs.length; i++) {
		if (inputs[i].type == 'checkbox')
			inputs[i].checked = bool;
	}
}

function validacom_email(ID){
	if(ID!=null){
		var mensj = '';
		if(ID=='email_ocupa') mensj = "Ocupante.";
		else if(ID=='pto_refe2_k') mensj = "Propietario / Representante Legal / Administrador.";
		if ((document.getElementById(ID).value.search("@") == -1 ) || ( document.getElementById(ID).value.search("[.*]" ) == -1 )){
			alert("Por favor, Revise el Email:\n\n\t" + mensj);
			document.getElementById(ID).focus();
			return false;
		}else{
    	  return true;
		}
	}else{
		return true;
	}
}

function verifica_riff(id_rif) {
	if(document.getElementById(id_rif).value != "0"){
                  var elRIF = document.getElementById(id_rif).value;
                  var temp = elRIF.toUpperCase();
				  if (!/^[JVEGPIRC]/.test(temp)){
				      alert("El primer caracter es incorrecto, debe ser una letra de las siguientes: J,V,E,G,P,I,R,C");
				      return false;
				  }else if (!/^[JVEGPIRC][-][0-9]{8}[-][0-9]$/.test(temp)){ // Son 9 d�gitos?
				     alert ("Por Favor Verifique que el RIF tenga el siguiente formato: J-12345678-9 \nSi su Rif es menor a 9 digitos rellene con 0 a la izquierda Ej: J-00123456-7\n Su rif actual es: "+temp);
				     return false;
				  }else{
				     return true;
				  }

    }
}

function verifica_cierre_ano_ejecucion(ID){
	var fecha_documento=document.getElementById(ID).value;
	a_fecha=fecha_documento.split('/');
	fecha_documento=a_fecha[2];
    var ano_cerrado=document.getElementById('ANO_CERRADO_EJECUCION').value;
	if(eval(fecha_documento)!=eval(ano_cerrado)){
		// fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA DEL DOCUMENTO NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
		document.getElementById(ID).focus();
		// false
		return true;
	}else{
		return true;
	}
}

function verifica_cierre_ano_ejecucion_msj(){
  	// fechaActual = new Date();
  	// var anoActual = fechaActual.getFullYear();
    var ano_cerrado=document.getElementById('ANO_CERRADO_EJECUCION').value;
    var anoActual=document.getElementById('ANO_ACTUAL_SERVIDOR').value;
	if(eval(anoActual)!=eval(ano_cerrado)){
		fun_msj('POR FAVOR VERIFIQUE EL A&Ntilde;O DE LA FECHA ACTUAL DEL SERVIDOR, NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
		return false;
	}else{
		return true;
	}
}


    function set(){
        new Draggable('box', {
         onEnd: function() {
                                var x=$('box').offsetLeft;
                                var y=$('box').offsetTop;
                                $('box1').morph('left:'+x+'px; top:'+y+'px;',{duration: 0});
                            }

        });
         // new Ajax.InPlaceEditor('editme2','test.php', {rows:3,cols:21,savingText:'guardando ...'});

    }

    function set_acept(){
        new Draggable('box_acept', {
         onEnd: function() {
                                var x=$('box_acept').offsetLeft;
                                var y=$('box_acept').offsetTop;
                                $('box2').morph('left:'+x+'px; top:'+y+'px;',{duration: 0});
                            }

        });
         // new Ajax.InPlaceEditor('editme2','test.php', {rows:3,cols:21,savingText:'guardando ...'});

    }

function valida_cscd02_criterio_tiempo(){

if(document.getElementById('parametro').value==""){
			fun_msj('Inserte paramtero');
			document.getElementById('parametro').focus();
			return false;

}else if(document.getElementById('porcentaje').value==""){

			fun_msj('Inserte el Porcentaje');
			document.getElementById('porcentaje').focus();
			return false;

}

}//fin funtion
