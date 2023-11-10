


function valida_fechas_menores_documentos(op){

//las opcion son
  //  1) RC
  //  2) OC
  //  3) OP
  //  4) cheque

opcion = 1;


              if(op==1){

var fecha_actual = document.getElementById('fecha_documento').value;
var datearray = fecha_actual.split("/");
var fecha1 = datearray[2] + '/' + datearray[1] + '/' + datearray[0]; // INVERTIR FECHA YYYY/MM/DD

var fecha_anterior = document.getElementById('fecha_documento_anterior').value;
var datearray = fecha_anterior.split("/");
var fecha2 = datearray[2] + '/' + datearray[1] + '/' + datearray[0]; // INVERTIR FECHA YYYY/MM/DD

                 if (fecha1<fecha2){
		            documento_anterior  = document.getElementById('numero_documento_anterior').value;
		            fecha_anterior      = document.getElementById('fecha_documento_anterior').value;
		            numero_documento    = document.getElementById('numero_compromiso').value;
		            if (documento_anterior!=0){
		            fun_msj('Fecha compromiso '+numero_documento+' menor a fecha '+fecha_anterior+' de compromiso '+documento_anterior);
		            opcion = 2;
		            }
		           }//fin if

		}else if(op==2){

var fecha_actual = document.getElementById('fechacompra').value;
var datearray = fecha_actual.split("/");
var fecha1 = datearray[2] + '/' + datearray[1] + '/' + datearray[0]; // INVERTIR FECHA YYYY/MM/DD

var fecha_anterior = document.getElementById('fecha_orden_compra_anterior').value;
var datearray = fecha_anterior.split("/");
var fecha2 = datearray[2] + '/' + datearray[1] + '/' + datearray[0]; // INVERTIR FECHA YYYY/MM/DD

                 if (fecha1<fecha2){
		            documento_anterior  = document.getElementById('numero_documento_anterior').value;
		            fecha_anterior      = document.getElementById('fecha_orden_compra_anterior').value;
		            numero_documento    = document.getElementById('num_ordencompra').value;
/*
		            if (documento_anterior!=0){
		            fun_msj('Fecha orden '+numero_documento+' menor a fecha '+fecha_anterior+' de orden '+documento_anterior);
                    opcion = 2;
                    }
*/
                   }//fin if

		}else if(op==3){

var fecha_actual = document.getElementById('fecha_documento_orden').value;
var datearray = fecha_actual.split("/");
var fecha1 = datearray[2] + '/' + datearray[1] + '/' + datearray[0]; // INVERTIR FECHA YYYY/MM/DD

var fecha_anterior = document.getElementById('fecha_documento_anterior').value;
var datearray = fecha_anterior.split("/");
var fecha2 = datearray[2] + '/' + datearray[1] + '/' + datearray[0]; // INVERTIR FECHA YYYY/MM/DD

                 if (fecha1<fecha2){
		            documento_anterior  = document.getElementById('numero_documento_anterior').value;
		            fecha_anterior      = document.getElementById('fecha_documento_anterior').value;
		            numero_documento    = document.getElementById('numero_orden_pago').value;
		            if (documento_anterior!=0){
		            fun_msj('Fecha orden '+numero_documento+' menor a fecha '+fecha_anterior+' de orden '+documento_anterior);
		            opcion = 2;
		            }
		           }//fin if


/*               }else if(op==4){

		         if(diferenciaFecha(document.getElementById('fecha').value, document.getElementById('fecha_documento_anterior').value)){
		            documento_anterior  = document.getElementById('numero_documento_anterior').value;
		            fecha_anterior      = document.getElementById('fecha_documento_anterior').value;
		            numero_documento    = document.getElementById('numero_cheque').value;
		            fun_msj('Fecha cheque '+numero_documento+' menor a fecha '+fecha_anterior+' de cheque '+documento_anterior);
		            opcion = 2;
		          }//fin if
*/

		}//fin else







return opcion;





}//fin funtion




function retornar_valor_calculo(monto){

var var_aux = monto+'';
var str     = var_aux;
var acepta  = "no";

for(i=0; i<var_aux.length; i++){
 ch = var_aux.charAt(i);
 if(ch==","){acepta  = "si";}
}

if(acepta=="si"){
	for(i=0; i<var_aux.length; i++){str = str.replace('.','');}
	str   = str.replace(',','.');
}


var var_aux = str;

return var_aux;

}//fin funtion






function valida_fechas_documentos_mayores(op){

//las opcion son
  //  1) RC
  //  2) OC
  //  3) OP
  //  4) cheque

var fecha_actual = document.getElementById('dia_actual').value;
var datearray = fecha_actual.split("/");
var fecha1 = datearray[2] + '/' + datearray[1] + '/' + datearray[0]; // INVERTIR FECHA YYYY/MM/DD

opcion = 1;

              if(op==1){
			var fecha_dato = document.getElementById('fecha_documento').value;

		}else if(op==2){
			var fecha_dato = document.getElementById('fechacompra').value;

		}else if(op==3){
			var fecha_dato = document.getElementById('fecha_contrato').value;

		}else if(op==4){
			var fecha_dato = document.getElementById('fecha_contrato').value;

		 }else if(op==5){
			var fecha_dato = document.getElementById('fecha_documento_orden').value;

		}else if(op==6){
			var fecha_dato = document.getElementById('fecha').value;
		}//fin else

		var datearray = fecha_dato.split("/");
		var fecha2 = datearray[2] + '/' + datearray[1] + '/' + datearray[0];

		          if(fecha2>fecha1){
                            opcion = 2;
                            fun_msj('La fecha del documento no puede ser mayor a la del dia actual');
		          }//fin if

return opcion;

}//fin funtion









function abre_ventana_porcentaje(){
     doc  = '<div id="cuerpo_ventana_porcentaje">';
		           doc += '<table width="100%" border="0"  class="admin_porcentaje_barra">';
		           doc += '<tr><th></th></tr>';
		           doc += '<tr><td  align="right" id="f" width="380"><br></td></tr>';
		           doc += '<tr><td  align="center"id="a"><img src="/img/barra_proceso/carpeta.gif" /><br></td></tr>';
		           doc += '<tr><td  align="right" id="b"><br><br></td></tr>';

		           doc += '<tr><td  align="left">';
				             doc  += '<table width="100%">';
				              doc  += '<tr><td id="c_1" align="left" width="90%"></td><td id="c_2" align="right" valign="top" width="10%"></td></tr>';
				             doc  += '</table>';
		           doc += '</td></tr>';

		           doc += '<tr><td  align="left"  id="d">';

				             doc  += '<table width="100%" class="barra_porcentaje_border">';
				              doc  += '<tr><td>';
				                doc += '<img src="/img/barra_proceso/mini_barra.png" id="barra_png" height="4px"/>';
				              doc  += '</td></tr>';
				             doc  += '</table>';

		           doc += '</td></tr>';
		           doc += '<tr><td  align="right" id="e"><br></td></tr>';
		           doc += '</table>';
	           doc += '</div>';

              Control.Modal.open(doc, {overlayCloseOnClick:false});
              document.getElementById('inicio_barra').value=1;
              document.getElementById('c_1').innerHTML       = "Buscando en la base de datos, Espere porfavor...";
              document.getElementById('titulo_barra').value  = "";
		      document.getElementById('c_2').innerHTML            = '0%';
		      document.getElementById('barra_png').style.width    = '0%';
}//fin funtion








function abre_ventana_pdf(){

    doc  = '<div id="cuerpo_ventana_porcentaje" style="background-color:#FFF;">';
    /*doc += '<table width="100%" border="0"  class="admin_porcentaje_barra">';
	doc += '<tr><th></th></tr>';
	doc += '<tr><td  align="right" id="f" width="380"><br></td></tr>';
	doc += '<tr><td  align="center"id="a"><img src="/img/barra_proceso/carpeta.gif" /><br></td></tr>';
	doc += '<tr><td  align="right" id="b"><br><br></td></tr>';

	doc += '<tr><td  align="left">';
	doc += '<table width="100%">';
	doc += '<tr><td id="c_1" align="left" width="90%"></td><td id="c_2" align="right" valign="top" width="10%"></td></tr>';
	doc += '</table>';
	doc += '</td></tr>';

	doc += '<tr><td  align="left"  id="d">';

	doc += '<table width="100%" class="barra_porcentaje_border">';
	doc += '<tr><td>';
	doc += '<img src="/img/barra_proceso/mini_barra.png" id="barra_png" height="4px"/>';
	doc += '</td></tr>';
	doc += '</table>';

	doc += '</td></tr>';
	doc += '<tr><td  align="right" id="e"><br></td></tr>';
	doc += '</table>';*/
	doc +='<div id="progreso" style="position:relative;overflow:hidden;border:1px #c21602 solid;width:350px;"><div id="c_2" style="position:absolute;left:160;"> </div><div id="barra_png" style="background-image:url(/img/barra_proceso/bar.png);width:1px;height:15px;"></div></div>';
    doc +='<div id="c_1" style="position:relative;color: #c21602;">Buscando datos, Espere por favor...</div>';

	doc += '</div>';
              Control.Modal.open(doc, {overlayCloseOnClick:false});
              document.getElementById('inicio_barra').value=1;
              document.getElementById('c_1').innerHTML       = "Buscando datos, Espere por favor...";
              document.getElementById('titulo_barra').value  = "";
		      document.getElementById('c_2').innerHTML            = ' ';
		      document.getElementById('barra_png').style.width    = '0%';
		      //observa_porcentaje_pdf
		      //new Ajax.PeriodicalUpdater('funcion_capa_pdf_ajax_2', '/ventana_pdf_reporte_ajax/actualiza_porcentaje_reporte', {method: 'get', frequency: 2, decay: 1}, session_update());


}//fin funtion
















function venta_procesos_informacion(){

    doc  = '<div id="cuerpo_ventana_porcentaje" style="background-color:#FFF;">';
    doc +=' <div id="progreso" style="position:relative;overflow:hidden;border:1px #c21602 solid;width:230px;">';
    doc +='     <div id="c_2" style="position:absolute;left:160;"> </div>';
    doc +='     <div>';
     doc +='       <img src="/img/loadingbar-deep-orange.gif" id="barra_png" width="230px" height="8px"/>';
    doc +='    </div>';
    doc +=' </div>';
    doc +='<div id="c_1" style="position:relative;color: #c21602;">Procesando, Espere por favor...</div>';
	doc += '</div>';
              Control.Modal.open(doc, {overlayCloseOnClick:false});

}//fin funtion


function evitar_doble_envio(){
	$("bt_procesar").value = "Procesando...";
    $("bt_procesar").disabled = true;
    return true;
}//fin funtion






function observa_porcentaje_pdf(){
	if(document.getElementById('inicio_barra').value==1){
       var myConn = new XHConn();
	    var peticion = function (oXML) {
		   if($('c_2')){      $('c_2').innerHTML         = oXML.responseText+'%';}
	       if($('barra_png')){$('barra_png').style.width = oXML.responseText+'%';}
	       observa_porcentaje_pdf();
		};
		myConn.connect('/ventana_pdf_reporte_ajax/actualiza_porcentaje_reporte', "GET", "", peticion);
		;

	}//fin if
}//fin function





function js_porcentaje_barra(TITULO,X){
	if(TITULO!=''){$('c_1').innerHTML         = ''+TITULO;}
	$('c_2').innerHTML         = X+'%';
	$('barra_png').style.width = X+'%';
	//alert('hooa');
}





function cerrar_ventana_pdf(){
	document.getElementById('submit_pdf').disabled=false;
	document.getElementById('inicio_barra').value='0';
    document.getElementById('titulo_barra').value='0';
	Control.Modal.close(true);
}





function bloqueo_de_fck_editor(id, op){
// OP == 1 es para bloquear
// OP == 2 es para desbloquear
edo = FCKeditorAPI.GetInstance(id);

if(op==1) {
        if (document.all){
			edo.EditorDocument.body.disabled = true;
		}else{
			edo.EditorDocument.designMode = "off";
		}

}else{

   	    if (document.all){
			edo.EditorDocument.body.disabled = false;
		}else{
			edo.EditorDocument.designMode = "on";
		}

}//fin else


}//fin function

function limpia_cero(x){
     	if(x.value=='0,00' || x.value=='0')x.value=''
 }

    function coloca_cero(x){
    	if(x.value=='')x.value='0,00';
    }




function formato_cantidades(id,precision,mensaje){
	var str=document.getElementById(id).value;
	str=retornar_valor_calculo(str);
	var c=0;
	for(i=0; i<str.length; i++){
		if(str.charAt( i )!="."){
			c++;
		}else{
			var aux=i+1;
			break;
		}
	}

	var d=0;
	for(i=aux; i<str.length; i++){
			d++;
	}

	if(c<=precision && d<=2){
		moneda(id);
	}else{
		if(mensaje){
			fun_msj(mensaje);
		}
		document.getElementById(id).value='';
		document.getElementById(id).focus();
		return false;
	}


}