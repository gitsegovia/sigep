function valida_cepp01_denominacion(){
 if(document.getElementById('denominacion').value==''){
			fun_msj('Inserte la Denominaci&oacute;n');
			fondoCampo('denominacion',1);
			setTimeout("fondoCampo('denominacion',2);", 3000);
			document.getElementById('denominacion').focus();
			return false;
}
if((document.getElementById('sujeto_retencion_1').checked==false) && (document.getElementById('sujeto_retencion_2').checked==false)){
			fun_msj('Seleccione si el tipo de compromiso esta sujeto a retenci&oacute;n');
			return false;
}
}//fin  valida_cepp01_tipo_documento



function valida_cepp01_denominacion2(){
 if(document.getElementById('denominacion').value==''){
			fun_msj('Inserte la Denominaci&oacute;n');
			fondoCampo('denominacion',1);
			setTimeout("fondoCampo('denominacion',2);", 3000);
			document.getElementById('denominacion').focus();
			return false;
}
}//fin  valida_cepp01_tipo_documento


function validacion_presupuestaria1(){

   if(document.getElementById('seleccion_5').value == ""){

			fun_msj('Seleccione el c&oacute;digo de la Actividad u Obra');
			document.getElementById('actividad_5').focus();
			return false;

}else if(document.getElementById('seleccion_10').value == "" && document.getElementById('seleccion_10').length >=1){
            fun_msj('Seleccione el c&oacute;digo de la auxiliar');
            setTimeout("fondoCampo('seleccion_10',2);", 1500);
			document.getElementById('seleccion_10').focus();
			return false;
}else if(document.getElementById('monto').value == ""){

			fun_msj('Inserte el monto');
			setTimeout("fondoCampo('monto',2);", 1500);
			document.getElementById('monto').focus();
			return false;
}else{
document.getElementById('monto').value="";
	}
}//fin funtion














function validacion_presupuestaria_servicio(){

   if(document.getElementById('seleccion_5').value == ""){

			fun_msj('Seleccione el c&oacute;digo de la Actividad u Obra');
			document.getElementById('seleccion_5').focus();
			return false;

}else if(document.getElementById('seleccion_10').value == "" && document.getElementById('seleccion_10').length >=1){
            fun_msj('Seleccione el c&oacute;digo del auxiliar');
            setTimeout("fondoCampo('seleccion_10',2);", 1500);
			document.getElementById('seleccion_10').focus();
			return false;
}else if(document.getElementById('monto').value == ""){

			fun_msj('Ingrese el monto');
			setTimeout("fondoCampo('monto',2);", 1500);
			document.getElementById('monto').focus();
			return false;
}else{
document.getElementById('monto').value="";
	}
}//fin funtion















function validacion_presupuestaria2(){

if(document.getElementById('seleccion_1').value == ""){

			fun_msj('Seleccione el c&oacute;digo del sector');
			document.getElementById('seleccion_1').focus();
			return false;

}else if(document.getElementById('seleccion_2').value == ""){

			fun_msj('Seleccione el c&oacute;digo del programa');
			document.getElementById('seleccion_2').focus();
			return false;

}else if(document.getElementById('seleccion_3').value == ""){

			fun_msj('Seleccione el c&oacute;digo del sub-programa');
			document.getElementById('seleccion_3').focus();
			return false;

}else if(document.getElementById('seleccion_4').value == ""){

			fun_msj('Seleccione el c&oacute;digo del proyecto');
			document.getElementById('seleccion_4').focus();
			return false;

}else if(document.getElementById('seleccion_5').value == ""){

			fun_msj('Seleccione el c&oacute;digo de la Actividad u Obra');
			document.getElementById('seleccion_5').focus();
			return false;

}else if(document.getElementById('seleccion_6').value == ""){

			fun_msj('Seleccione el c&oacute;digo de la partida');
			document.getElementById('seleccion_6').focus();
			return false;

}else if(document.getElementById('seleccion_7').value == ""){

			fun_msj('Seleccione el c&oacute;digo de la generica');
			document.getElementById('seleccion_7').focus();
			return false;

}else if(document.getElementById('seleccion_8').value == ""){

			fun_msj('Seleccione el c&oacute;digo de la especifica');
			document.getElementById('seleccion_8').focus();
			return false;

}else if(document.getElementById('seleccion_9').value == ""){

			fun_msj('Seleccione el c&oacute;digo de la sub-especifica');
			document.getElementById('seleccion_9').focus();
			return false;

}else if(document.getElementById('seleccion_10').value == "" && document.getElementById('seleccion_10').length >=1){
            fun_msj('Seleccione el c&oacute;digo del auxiliar');
            setTimeout("fondoCampo('seleccion_10',2);", 1500);
			document.getElementById('seleccion_10').focus();
			return false;
}else if(document.getElementById('monto').value == ""){

			fun_msj('Inserte el monto');
			setTimeout("fondoCampo('monto',2);", 1500);
			document.getElementById('monto').focus();
			return false;
}else{
document.getElementById('monto').value="";
	}
}//fin funtion2

function habilita_compromiso(){
    document.getElementById("ano").disabled="";
    document.getElementById("numero_compromiso").disabled="";
    document.getElementById("nombre_id_select").disabled="";
    document.getElementById("fecha_documento").disabled="";
    document.getElementById("concepto").disabled="";
    document.getElementById("rif").disabled="";
    document.getElementById("cedula").disabled="";
    document.getElementById("bene").disabled="";
    document.getElementById("select_1").disabled="";
    document.getElementById("select_2").disabled="";
    document.getElementById("select_3").disabled="";
    document.getElementById("select_4").disabled="";
    document.getElementById("catalogo_5").disabled="";
    document.getElementById("tipo_recurso_1").disabled="";
    document.getElementById("tipo_recurso_2").disabled="";
    document.getElementById("tipo_recurso_3").disabled="";
    document.getElementById("tipo_recurso_4").disabled="";
    document.getElementById("tipo_recurso_5").disabled="";
}


function habilita_compromiso_caop01(){
    document.getElementById("ano").disabled="";
    document.getElementById("numero_compromiso").disabled="";
    document.getElementById("nombre_id_select").disabled="";
    document.getElementById("fecha_documento").disabled="";
    document.getElementById("concepto").disabled="";
    document.getElementById("rif").disabled="";
    document.getElementById("cedula").disabled="";
    document.getElementById("bene").disabled="";
    document.getElementById("select_1").disabled="";
    document.getElementById("select_2").disabled="";
    document.getElementById("select_3").disabled="";
    document.getElementById("select_4").disabled="";
    document.getElementById("tipo_recurso_1").disabled="";
    document.getElementById("tipo_recurso_2").disabled="";
    document.getElementById("tipo_recurso_3").disabled="";
    document.getElementById("tipo_recurso_4").disabled="";
    document.getElementById("tipo_recurso_5").disabled="";
}

function validar_compromiso(){
   var mydate=new Date()
   var year1=mydate.getYear()
   var year=mydate.getYear()
      if (year < 1000)
           year+=1901

	if(document.getElementById('fecha_documento').value==''){
			fun_msj('Por favor seleccione la fecha del documento');
			document.getElementById('fecha_documento').focus();
			return false;
	}else if(verifica_cierre_ano_ejecucion('fecha_documento')==false){
				fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA DEL DOCUMENTO NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
				return false;
	}else if(document.getElementById('ano').value == ""){
			fun_msj('Inserte un a&ntilde;o');
			setTimeout("fondoCampo('ano',2);", 1500);
			document.getElementById('ano').focus();
			return false;
    }else if(document.getElementById('ano').value.length<4){
			fun_msj('Inserte un a&ntilde;o correcto');
			setTimeout("fondoCampo('ano',2);", 1500);
			document.getElementById('ano').focus();
			return false;
	}else if(document.getElementById('numero_compromiso').value == ""){
			fun_msj('Inserte el N&uacute;mero de compromiso');
			setTimeout("fondoCampo('numero_compromiso',2);", 1500);
			document.getElementById('numero_compromiso').focus();
			return false;

  }else

  		var fecha_actual = document.getElementById('fecha_documento').value;
		var datearray = fecha_actual.split("/");
		var fecha1 = datearray[2] + '/' + datearray[1] + '/' + datearray[0]; // INVERTIR FECHA YYYY/MM/DD

		var fecha_comparar = document.getElementById('fecha_documento_anterior').value;
		var datearray = fecha_comparar.split("/");
		var fecha2 = datearray[2] + '/' + datearray[1] + '/' + datearray[0]; // INVERTIR FECHA YYYY/MM/DD

		if (fecha1<fecha2){
              documento_anterior  = document.getElementById('numero_documento_anterior').value;
              fecha_anterior      = document.getElementById('fecha_documento_anterior').value;
              numero_documento    = document.getElementById('numero_compromiso').value;
              if (documento_anterior!=0){
              fun_msj('Fecha compromiso '+numero_documento+' menor a fecha '+fecha_anterior+' de compromiso '+documento_anterior);
              return false;
              }

    }else if(valida_fechas_menores_documentos(1)==2){
      			return false;

    }else if(document.getElementById('nombre_id_select').value == ""){
			fun_msj('Seleccione el tipo de documento');
			setTimeout("fondoCampo('nombre_id_select',2);", 1500);
			document.getElementById('nombre_id_select').focus();
			return false;
    }else if(document.getElementById('rif').value == "" || document.getElementById('cedula').value == ""){
            fun_msj('Inserte el rif o c&eacute;dula');
			setTimeout("fondoCampo('rif',2);", 1500);
			document.getElementById('rif').focus();
			return false;
    }else if(document.getElementById('bene').value == ""){
			fun_msj('Inserte el beneficiario');
			setTimeout("fondoCampo('bene',2);", 1500);
			document.getElementById('bene').focus();
			return false;
    }else if(document.getElementById('select_1').value == ""){
			fun_msj('Seleccione Direcci&oacute;n Superior');
			setTimeout("fondoCampo('select_1',2);", 1500);
			document.getElementById('select_1').focus();
			return false;
    }else if(document.getElementById('select_2').value == ""){
			fun_msj('Seleccione Coordinaci&oacute;n');
			setTimeout("fondoCampo('select_2',2);", 1500);
			document.getElementById('select_2').focus();
			return false;
    }else if(document.getElementById('select_3').value == ""){
			fun_msj('Seleccione Secretaria');
			setTimeout("fondoCampo('select_3',2);", 1500);
			document.getElementById('select_3').focus();
			return false;
    }else if(document.getElementById('select_4').value == ""){
			fun_msj('Seleccione Direcci&oacute;n');
			setTimeout("fondoCampo('select_4',2);", 1500);
			document.getElementById('select_4').focus();
			return false;
    }else if(document.getElementById('concepto').value == ""){
			fun_msj('Ingrese el concepto del compromiso');
			setTimeout("fondoCampo('concepto',2);", 1500);
			document.getElementById('concepto').focus();
			return false;
    }else if(document.getElementById('condicion_juridica_1').checked==false && document.getElementById('condicion_juridica_2').checked==false){
            fun_msj('Seleccione Personalidad Juridica');
			return false;
    }else if(document.getElementById('lista_partidas').value==0){
         fun_msj('Debe agregar las partidas.');
	     return false;
    }else if(document.getElementById('rif').value != "0"){
                  var elRIF = document.getElementById('rif').value;
                  var temp = elRIF.toUpperCase();
				  if (!/^[JVEGPIRC]/.test(temp)){
				      alert("El primer caracter es incorrecto, debe ser una letra de las siguientes: J,V,E,G,P,I,R,C");
				      return false;
				  }else if (!/^[JVEGPIRC][-][0-9]{8}[-][0-9]$/.test(temp)){ // Son 9 dígitos?
				     alert ("Por Favor Verifique que el RIF tenga el siguiente formato: J-12345678-9 \nSi su Rif es menor a 9 digitos rellene con 0 a la izquierda Ej: J-00123456-7\n Su rif actual es: "+temp);
				     return false;
				  }

    }else if(valida_fechas_documentos_mayores(1)==2){
      			return false;

    }



    if(document.getElementById('fecha_documento').value!=""){
           return verifica_cierre_mes_ejecucion('fecha_documento');
    }






}//fin funcion


function validar_compromiso_caop01(){
   var mydate=new Date()
   var year1=mydate.getYear()
   var year=mydate.getYear()
      if (year < 1000)
           year+=1901

    if(document.getElementById('ano').value == ""){
			fun_msj('Inserte un a&ntilde;o');
			setTimeout("fondoCampo('ano',2);", 1500);
			document.getElementById('ano').focus();
			return false;
    }else if(document.getElementById('ano').value.length<4){
			fun_msj('Inserte un a&ntilde;o correcto');
			setTimeout("fondoCampo('ano',2);", 1500);
			document.getElementById('ano').focus();
			return false;
	}else if(document.getElementById('numero_compromiso').value == ""){
			fun_msj('Inserte el N&uacute;mero de compromiso');
			setTimeout("fondoCampo('numero_compromiso',2);", 1500);
			document.getElementById('numero_compromiso').focus();
			return false;

  }else

        var fecha_actual = document.getElementById('fecha_documento').value;
		var datearray = fecha_actual.split("/");
		var fecha1 = datearray[2] + '/' + datearray[1] + '/' + datearray[0]; // INVERTIR FECHA YYYY/MM/DD

		var fecha_comparar = document.getElementById('fecha_documento_anterior').value;
		var datearray = fecha_comparar.split("/");
		var fecha2 = datearray[2] + '/' + datearray[1] + '/' + datearray[0]; // INVERTIR FECHA YYYY/MM/DD

		if (fecha1<fecha2){
              documento_anterior  = document.getElementById('numero_documento_anterior').value;
              fecha_anterior      = document.getElementById('fecha_documento_anterior').value;
              numero_documento    = document.getElementById('numero_compromiso').value;
				if (documento_anterior!=0){
              fun_msj('Fecha compromiso '+numero_documento+' menor a fecha '+fecha_anterior+' de compromiso '+documento_anterior);
              return false;
              	}

    }else if(valida_fechas_menores_documentos(1)==2){
      			return false;

    }else if(document.getElementById('nombre_id_select').value == ""){
			fun_msj('Seleccione el tipo de documento');
			setTimeout("fondoCampo('nombre_id_select',2);", 1500);
			document.getElementById('nombre_id_select').focus();
			return false;
    }else if(document.getElementById('fecha_documento').value==''){
			fun_msj('Por favor seleccione la fecha del documento');
			document.getElementById('fecha_documento').focus();
			return false;
	}else if(verifica_cierre_ano_ejecucion('fecha_documento')==false){
				fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA DEL DOCUMENTO NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
				return false;
	}else if(document.getElementById('rif').value == "" || document.getElementById('cedula').value == ""){
            fun_msj('Inserte el rif o c&eacute;dula');
			setTimeout("fondoCampo('rif',2);", 1500);
			document.getElementById('rif').focus();
			return false;
    }else if(document.getElementById('bene').value == ""){
			fun_msj('Inserte el beneficiario');
			setTimeout("fondoCampo('bene',2);", 1500);
			document.getElementById('bene').focus();
			return false;
    }else if(document.getElementById('select_1').value == ""){
			fun_msj('Seleccione Direcci&oacute;n Superior');
			setTimeout("fondoCampo('select_1',2);", 1500);
			document.getElementById('select_1').focus();
			return false;
    }else if(document.getElementById('select_2').value == ""){
			fun_msj('Seleccione Coordinaci&oacute;n');
			setTimeout("fondoCampo('select_2',2);", 1500);
			document.getElementById('select_2').focus();
			return false;
    }else if(document.getElementById('select_3').value == ""){
			fun_msj('Seleccione Secretaria');
			setTimeout("fondoCampo('select_3',2);", 1500);
			document.getElementById('select_3').focus();
			return false;
    }else if(document.getElementById('select_4').value == ""){
			fun_msj('Seleccione Direcci&oacute;n');
			setTimeout("fondoCampo('select_4',2);", 1500);
			document.getElementById('select_4').focus();
			return false;
    }else if(document.getElementById('codigo_obra').value == ""){
			fun_msj('Seleccione el c&oacute;digo de la obra');
			setTimeout("fondoCampo('codigo_obra',2);", 1500);
			document.getElementById('codigo_obra').focus();
			return false;
    }else if(document.getElementById('concepto').value == ""){
			fun_msj('Ingrese el concepto del compromiso');
			setTimeout("fondoCampo('concepto',2);", 1500);
			document.getElementById('concepto').focus();
			return false;
    }else if(document.getElementById('condicion_juridica_1').checked==false && document.getElementById('condicion_juridica_2').checked==false){
            fun_msj('Seleccione Personalidad Juridica');
			return false;
    }else if(document.getElementById('lista_partidas').value==0){
         fun_msj('Debe agregar las partidas.');
	     return false;
    }else if(document.getElementById('rif').value != "0"){
                  var elRIF = document.getElementById('rif').value;
                  var temp = elRIF.toUpperCase();
				  if (!/^[JVEGPIRC]/.test(temp)){
				      alert("El primer caracter es incorrecto, debe ser una letra de las siguientes: J,V,E,G,P,I,R,C");
				      return false;
				  }else if (!/^[JVEGPIRC][-][0-9]{8}[-][0-9]$/.test(temp)){ // Son 9 dígitos?
				     alert ("Por Favor Verifique que el RIF tenga el siguiente formato: J-12345678-9 \nSi su Rif es menor a 9 digitos rellene con 0 a la izquierda Ej: J-00123456-7\n Su rif actual es: "+temp);
				     return false;
				  }

    }else if(valida_fechas_documentos_mayores(1)==2){
      			return false;

    }




    if(document.getElementById('fecha_documento').value!=""){
           return verifica_cierre_mes_ejecucion('fecha_documento');
    }

}


function verifica_cierre_mes_ejecucion(ID){
   /**/ var fecha_documento=document.getElementById(ID).value;
              a_fecha=fecha_documento.split('/');
              fecha_documento=a_fecha[1];
    var mes_cerrado=document.getElementById('MES_CERRADO_EJECUCION').value;
          if(eval(fecha_documento)<=eval(mes_cerrado)){
              fun_msj('LO SIENTO NO PUEDE REGISTRAR NING&Uacute;N DOCUMENTO EN MESES CERRADOS');
			  return false;
          }else{
             return true;
          }/**/
          return true;
}




function Habilita_Anular(){
   /*if(document.getElementById("bt_anular").value=="Anular"){
    document.getElementById("concepto_anulacion").disabled="";
    document.getElementById("guardar").disabled="";
    document.getElementById("condicion_documento_1").checked=false;
    document.getElementById("condicion_documento_2").checked=true;
    document.getElementById("bt_anular").value="Cancelar Anulacion";
    fun_msj('Ingrese el concepto de la anulaci&oacute;n');
	setTimeout("fondoCampo('concepto_anulacion',2);", 3500);
	document.getElementById('concepto_anulacion').focus();
	}else{
	    document.getElementById("concepto_anulacion").disabled="disabled";
	    document.getElementById("guardar").disabled="disabled";
	    document.getElementById("condicion_documento_1").checked=true;
	    document.getElementById("condicion_documento_2").checked=false;
	    document.getElementById("bt_anular").value="Anular";

	}*/
	if(document.getElementById("bt_anular").className=="eliminar_input"){
    document.getElementById("concepto_anulacion").disabled="";
    document.getElementById("guardar").disabled="";
    document.getElementById("condicion_documento_1").checked=false;
    document.getElementById("condicion_documento_2").checked=true;
    document.getElementById("bt_anular").className="cancelar_anular_input";
    document.getElementById("bt_anular").title="Cancelar Anulacion";
    fun_msj('Ingrese el concepto de la anulaci&oacute;n');
	setTimeout("fondoCampo('concepto_anulacion',2);", 3500);
	document.getElementById('concepto_anulacion').focus();
	}else{
	    document.getElementById("concepto_anulacion").disabled="disabled";
	    document.getElementById("guardar").disabled="disabled";
	    document.getElementById("condicion_documento_1").checked=true;
	    document.getElementById("condicion_documento_2").checked=false;
	    document.getElementById("bt_anular").className="eliminar_input";
	    document.getElementById("bt_anular").title="Anular";

	}
}

function validar_concepto_anulacion(){
if(verifica_cierre_ano_ejecucion_msj()==false){
	return false;
}else{
     if(document.getElementById('concepto_anulacion').value == ""){
			fun_msj('Ingrese el concepto de la anulaci&oacute;n');
			setTimeout("fondoCampo('concepto_anulacion',2);", 3500);
	        document.getElementById('concepto_anulacion').focus();
			return false;
    }else if(!confirm("Esta Seguro que desea anular este Registro")){
         return false;
    }
}// FIN ELSE VERIFICANDO ANO EJEC <> ANO SERVER.
}


function limpia_id_semaforo(){
    document.getElementById("semaforo").innerHTML="";
    document.getElementById('monto').value="";
}




function valida_ordencompra(){

if(verifica_cierre_ano_ejecucion('fechacompra')==false){
	fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA DEL DOCUMENTO NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
	return false;
}else{

	//var i=document.getElementById("contador").value;
	if(document.getElementById("num_1").value==''){
		fun_msj('POR FAVOR INGRESE EL N&uacute;MERO DE LA COTIZACI&Oacute;N');
		document.getElementById('num_1').focus();
		return false;
	}else if(document.getElementById("lugar").value==''){
		fun_msj('POR FAVOR especifique un lugar de entrega');
		document.getElementById('lugar').focus();
		return false;
	}else if(document.getElementById("plazo").value==''){
		fun_msj('por favor especifique el plazo de entrega');
		document.getElementById('plazo').focus();
		return false;

      }else

        var fecha_actual = document.getElementById('fechacompra').value;
		var datearray = fecha_actual.split("/");
		var fecha1 = datearray[2] + '/' + datearray[1] + '/' + datearray[0]; // INVERTIR FECHA YYYY/MM/DD

		var fecha_comparar = document.getElementById('fecha_orden_compra_anterior').value;
		var datearray = fecha_comparar.split("/");
		var fecha2 = datearray[2] + '/' + datearray[1] + '/' + datearray[0]; // INVERTIR FECHA YYYY/MM/DD

		if (fecha1<fecha2){
              documento_anterior  = document.getElementById('numero_documento_anterior').value;
              fecha_anterior      = document.getElementById('fecha_orden_compra_anterior').value;
              numero_documento    = document.getElementById('num_ordencompra').value;
              if (documento_anterior!=0){
              fun_msj('Fecha orden '+numero_documento+' menor a fecha '+fecha_anterior+' de orden '+documento_anterior);
              return false;
              }


    }else if(valida_fechas_documentos_mayores(2)==2){
      			return false;

	}else if(eval(document.getElementById("total_manual").value) != eval(document.getElementById("total_cotizacion").value)){
			fun_msj('EL MONTO DE LA ORDEN DE COMPRA NO COINCIDE CON EL MONTO DE LA COTIZACI&Oacute;N');
			return false;

	}else if(valida_fechas_menores_documentos(2)==2){
      			return false;

    }





if(diferenciaFecha(document.getElementById('fechacompra').value, document.getElementById('fecha_comparar').value)){

         if(document.getElementById('fecha_actual').value!=""){
           return verifica_cierre_mes_ejecucion('fecha_actual');
         }//fin if

}else{

         if(document.getElementById('fechacompra').value!=""){
           return verifica_cierre_mes_ejecucion('fechacompra');
         }//fin if

}// fin else

}// FIN ELSE VERIFICANDO ANO EJEC <> ANO FECHA DOC.

}







function bloquearCR(actual,id){
    if((document.getElementById(actual).value!="" && document.getElementById(actual).value!="0") && (document.getElementById(id).value=="" || document.getElementById(id).value=="0")){
         document.getElementById(id).value=0;
         document.getElementById(id).disabled="disabled";
         //document.getElementById('bene').focus();
         if(actual=="cedula"){
            document.getElementById('condicion_juridica_1').checked=true;
         }else{
            document.getElementById('condicion_juridica_2').checked=true;
         }

    }else{
        document.getElementById(id).value="";
        document.getElementById(id).disabled="";
    }
}

function calcular_total_cscp04_ordencompra(i){
	var valor = eval(document.getElementById('total2').value) - eval(document.getElementById('monto_'+i).value);
	valor = redondear(valor, 2);
	document.getElementById('total2').value = valor;
	cscp03_cotizacion_cuerpo_moneda('total',valor);
//	document.getElementById('total').innerHTML=valor;
//	moneda('total');
}

function calcular_total_cscp04_ordencompra2(i){
	//alert('i es: '+i+'; '+eval(document.getElementById('total_manual').value)+'-'+eval(document.getElementById('monto2_'+i).value));
	var valor = eval(document.getElementById('total_manual').value) - eval(document.getElementById('monto2_'+i).value);
	valor = redondear(valor, 2);
	document.getElementById('total_manual').value = valor;
	cscp03_cotizacion_cuerpo_moneda('total2_manual',valor);
//	document.getElementById('total').innerHTML=valor;
//	moneda('total');
}

function ver_documento(url_busqueda,ID){
	new Ajax.Updater('', '',   ''   , ID ,url_busqueda, {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, requestHeaders:['X-Update', ID]}, session_update())
}


function ver_documento_pestana_expediente(url_busqueda,ID){


document.getElementById('tab_datos_personales').innerHTML="";
document.getElementById('tab_datos_educativos').innerHTML="";
document.getElementById('tab_datos_formacion').innerHTML="";
document.getElementById('tab_datos_registro_titulo').innerHTML="";
document.getElementById('tab_datos_familiares').innerHTML="";
document.getElementById('tab_experiencia_administrativa').innerHTML="";
document.getElementById('tab_cnmp06_soportes').innerHTML="";
document.getElementById('tab_datos_personales_consulta').innerHTML="";
document.getElementById('tab_datos_permisos').innerHTML="";
document.getElementById('tab_datos_amonestaciones').innerHTML="";
document.getElementById('tab_datos_bienes').innerHTML="";


	new Ajax.Updater('', '',   ''   , ID ,url_busqueda, {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, requestHeaders:['X-Update', ID]}, session_update());


}//fin function







