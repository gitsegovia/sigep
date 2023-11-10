/** XHConn - Simple XMLHTTP Interface - bfults@gmail.com - 2005-04-08        **
 ** Code licensed under Creative Commons Attribution-ShareAlike License      **
 ** http://creativecommons.org/licenses/by-sa/2.0/                           **/



function observar_table(){
	if(!document.getElementById('1_b')){document.getElementById('1').innerHTML = ''; }//fin if
	if(!document.getElementById('2_b')){document.getElementById('2').innerHTML = ''; }//fin if
	if(!document.getElementById('3_b')){document.getElementById('3').innerHTML = ''; }//fin if
	if(!document.getElementById('4_b')){document.getElementById('4').innerHTML = ''; }//fin if
	if(!document.getElementById('5_b')){document.getElementById('5').innerHTML = ''; }//fin if
	if(!document.getElementById('6_b')){document.getElementById('6').innerHTML = ''; }//fin if
	if(!document.getElementById('7_b')){document.getElementById('7').innerHTML = ''; }//fin if
	if(!document.getElementById('8_b')){document.getElementById('8').innerHTML = ''; }//fin if
	if(!document.getElementById('55_b')){document.getElementById('55').innerHTML = ''; }//fin if
	if(!document.getElementById('56_b')){document.getElementById('56').innerHTML = ''; }//fin if
}//fin if





function XHConn()
{
  var miAleatorio=parseInt(Math.random()*99999999);
  var xmlhttp, bComplete = false;
  try { xmlhttp = new ActiveXObject("Msxml2.XMLHTTP"); }
  catch (e) { try { xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); }
  catch (e) { try { xmlhttp = new XMLHttpRequest(); }
  catch (e) { xmlhttp = false; }}}
  if (!xmlhttp) return null;
  this.connect = function(sURL, sMethod, sVars, fnDone){

    if (!xmlhttp) return false;
    bComplete = false;
    sMethod = sMethod.toUpperCase();

    try {

      if (sMethod == "GET"){
        xmlhttp.open(sMethod, sURL+"?"+sVars+ "&rand=" + miAleatorio, true);
	    sVars = "";

      }else{
        xmlhttp.open(sMethod, sURL+"?rand=" + miAleatorio, true);
        xmlhttp.setRequestHeader("Method", "POST "+sURL+"?rand=" + miAleatorio+" HTTP/1.1");
        xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            }//FIN ELSE

      xmlhttp.onreadystatechange = function(){

        if (xmlhttp.readyState == 4 && !bComplete){
          bComplete = true;
          fnDone(xmlhttp);

		                                         }

		  };
      xmlhttp.send(sVars);

         }//FIN TRY


    catch(z) { return false; }
    return true;
  };
  return this;
}


function cargar_contenido(target,pagina){
	//document.getElementById(target).innerHTML = '<p class=\"load\">Cargando...</p>';


	var myConn = new XHConn();
		if (!myConn) fun_msj("XMLHTTP no esta disponible. Intï¿½ntalo con un navegador mï¿½s nuevo.");
		var peticion = function (oXML) {document.getElementById(target).innerHTML = oXML.responseText; };
		myConn.connect(pagina, "GET", "", peticion);
		;
}







function mensaje_con_monto_iva_incorrecto(monto){

        pag="../../include/cfpp05/moneda.php?monto="+monto;

	   var myConn = new XHConn();
		if (!myConn) fun_msj("XMLHTTP no esta disponible. Intentalo con un navegador mas nuevo.");
        var peticion = function (oXML) {
                                          fun_msj('El porcentaje del i.v.a no corresponde  ..Monto correcto.. '+oXML.responseText);
                                       };
		myConn.connect(pag, "GET", "", peticion);
        ;


}











function cargarMonto(id,pagina){
	//document.getElementById(target).innerHTML = '<p class=\"load\">Cargando...</p>';
	var myConn = new XHConn();
		if (!myConn) fun_msj("XMLHTTP no esta disponible. Intentalo con un navegador mas nuevo.");
        var peticion = function (oXML) {document.getElementById(id).value = oXML.responseText; };
		myConn.connect(pagina, "GET", "", peticion);
		;
}
function cargarMonto2(pagina){
	//document.getElementById(target).innerHTML = '<p class=\"load\">Cargando...</p>';
	var myConn = new XHConn();
		if (!myConn) fun_msj("XMLHTTP no esta disponible. Intentalo con un navegador mas nuevo.");
		var peticion = function (oXML) {document.getElementById('montoedit').value = oXML.responseText; };
		myConn.connect(pagina, "GET", "", peticion);
		;
}

function cargarMonto3(pagina){
	//document.getElementById(target).innerHTML = '<p class=\"load\">Cargando...</p>';
	var myConn = new XHConn();
		if (!myConn) fun_msj("XMLHTTP no esta disponible. Intentalo con un navegador mas nuevo.");
		var peticion = function (oXML) {oXML.responseText; };
		myConn.connect(pagina, "GET", "", peticion);
		;
}

function sprintfphp(pagina){
	//document.getElementById(target).innerHTML = '<p class=\"load\">Cargando...</p>';
	//alert("S");
	//var retorna=0;
	var myConn = new XHConn();
		if (!myConn) fun_msj("XMLHTTP no esta disponible. Intentalo con un navegador mas nuevo.");
		peticion = function (oXML) {oXML.responseText;};
		myConn.connect(pagina, "GET", "", peticion);
		;

}
function cargar_formulario(targ,cadena,pagina){

	//document.getElementById(target).innerHTML = '<p class=\"load\">Cargando...</p>';


	target = document.getElementById(targ);

	paramdef=cadena;

	var myConn = new XHConn();
	if (!myConn) fun_msj("XMLHTTP not available. Try a newer/better browser.");
	var submit = function (oXML) {
		if(oXML.responseText == "") {
			msg.innerHTML = '<p class="error">Imposible ordenar citas.</p>';
		} else {

			target.innerHTML = oXML.responseText;
			}
		};
		//enviamos datos
		//usamos escape() para no tener problemas con los caracteres raros
		myConn.connect(pagina, "POST", paramdef, submit);


}




function selec_desmarcar(id){
	var checkStr = id;
        var ch = '';
        var aux = '';
        var act = 'no';

x = checkStr.length;
aux_1 = checkStr.charAt(x);
if(checkStr.charAt(x-1)=='_'){
    ch = checkStr.charAt(x);
    for(i=0; i<checkStr.length-1; i++){aux=aux+checkStr.charAt(i);}

}else{
    ch = checkStr.charAt(x-1);
    ch = ch + checkStr.charAt(x);
    for(i=0; i<checkStr.length-2; i++){aux=aux+checkStr.charAt(i);}
}

		   var checkOK = " 0123456789";
           var checkStr = ch;
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
ch = eval(ch);

ch++;
for(i=ch; i<=30; i++){if(document.getElementById(aux+'_'+i)){document.getElementById(aux+'_'+i).innerHTML = '<br>'; }else{break;}}//fin for
deno_limpiar(id);
codigo_limpiar(id);
}
}//fin funtion

function deno_limpiar(id){
        var id = 'deno_'+id;
	var checkStr = id;
        var ch = '';
        var aux = '';
        var act = 'no';
x = checkStr.length;
aux_1 = checkStr.charAt(x);
if(checkStr.charAt(x-1)=='_'){
    ch = checkStr.charAt(x);
    for(i=0; i<checkStr.length-1; i++){aux=aux+checkStr.charAt(i);}

}else{
    ch = checkStr.charAt(x-1);
    ch = ch + checkStr.charAt(x);
    for(i=0; i<checkStr.length-2; i++){aux=aux+checkStr.charAt(i);}
}

ch = eval(ch);
ch++;
for(i=ch; i<=30; i++){if(document.getElementById(aux+'_'+i)){document.getElementById(aux+'_'+i).innerHTML = '<br>';}else{break;}}//fin for
}//fin funtion




function codigo_limpiar(id){
        var id = 'codigo_'+id;
	var checkStr = id;
        var ch = '';
        var aux = '';
        var act = 'no';
x = checkStr.length;
aux_1 = checkStr.charAt(x);
if(checkStr.charAt(x-1)=='_'){
    ch = checkStr.charAt(x);
    for(i=0; i<checkStr.length-1; i++){aux=aux+checkStr.charAt(i);}

}else{
    ch = checkStr.charAt(x-1);
    ch = ch + checkStr.charAt(x);
    for(i=0; i<checkStr.length-2; i++){aux=aux+checkStr.charAt(i);}
}

ch = eval(ch);
ch++;
for(i=ch; i<=30; i++){if(document.getElementById(aux+'_'+i)){document.getElementById(aux+'_'+i).innerHTML = '<br>';}else{break;}}//fin for
}//fin funtion



function desactiva_mensaje(){
	  if(document.getElementById('msj_cancelar')){
	     //document.getElementById('msj_cancelar').style.display = "none";
	     Effect.BlindUp('msj_cancelar', { duration: 1.0 });
	  }

	  if(document.getElementById('msj_aceptar')){
	      //document.getElementById('msj_aceptar').style.display = "none";
	      Effect.BlindUp('msj_aceptar', { duration: 1.0 });
	  }
}





function msj_none(){
	document.getElementById('msj_cancelar').style.display = 'none';
	document.getElementById('msj_aceptar').style.display = 'none';
	document.getElementById('valida_codigo').style.display = 'none';

	//Effect.BlindUp('msj_cancelar', { duration: 1.0 });
	//Effect.BlindUp('msj_aceptar', { duration: 1.0 });

}


function fun_msj(mensaje){

	// Forma en Ventana:
	  document.getElementById('msj_cancelar').style.display = "block";
	  //Effect.BlindDown('msj_cancelar', { duration: 1.0 });

	  nMiliSegundos=5000;
      document.getElementById('box1').innerHTML= mensaje;
 	  window.setTimeout("msj_none();", nMiliSegundos);

	/* // Forma en Texto:
	  document.getElementById('msj_cancelar').style.display = "block";
	  //Effect.BlindDown('msj_cancelar', { duration: 1.0 });

	  nMiliSegundos=5000;
      document.getElementById('msj_cancelar').innerHTML= mensaje;
 	  window.setTimeout("msj_none();", nMiliSegundos);
	*/

}//FIN MENSAJE de ERROR




function fun_msj2(mensaje){

	// Forma en Ventana:
	  document.getElementById('msj_aceptar').style.display = "block";
	  //Effect.BlindDown('msj_aceptar', { duration: 1.0 });

	  nMiliSegundos=5000;
      document.getElementById('box2').innerHTML= mensaje;
 	  window.setTimeout("msj_none();", nMiliSegundos);

	/* // Forma en Texto:
	  document.getElementById('msj_aceptar').style.display = "block";
	  //Effect.BlindDown('msj_aceptar', { duration: 1.0 });

	  nMiliSegundos=5000;
      document.getElementById('msj_aceptar').innerHTML= mensaje;
 	  window.setTimeout("msj_none();", nMiliSegundos);
	*/

 	  if(document.getElementById("valida_codigo")){
			document.getElementById("valida_codigo").innerHTML = "";
		    document.getElementById("valida_codigo").style.display = "none";
			}

}//FIN MENSAJE de ERROR






function fun_msj3(mensaje, activa){

     if(activa=='2'){
	  document.getElementById('valida_codigo').style.display = "block";
	  document.getElementById('valida_codigo').innerHTML=mensaje;
	  nMiliSegundos=5000;
      window.setTimeout("msj_none();", nMiliSegundos);


}else if(activa=='1'){
      document.getElementById('valida_codigo').style.display = "none";
      document.getElementById('valida_codigo').innerHTML= mensaje;
      }


}//FIN MENSAJE de ERROR







function activa_link_correr_script(id){

 document.getElementById(id).style.display = "none";
 document.getElementById(id+'_b').style.display = "block";

 document.getElementById(id+'_b').className = "a_usado_script";


}//fin function











function cambia_campos_cscp03_registro_cotizacion(){



if(document.getElementById("solicitud_cotizacion_ano_aux")){


              document.getElementById("solicitud_cotizacion_ano_aux").value = document.getElementById("solicitud_cotizacion_ano").value;

   			  document.getElementById("solicitud_cotizacion_numero_aux").value = document.getElementById("solicitud_cotizacion_numero").value;

   			  document.getElementById("solicitud_cotizacion_fecha_aux").value = document.getElementById("solicitud_cotizacion_fecha").value;

              document.getElementById("cotizacion_ano_aux").value = document.getElementById("cotizacion_ano").value;

   			  document.getElementById("cotizacion_numero_aux").value = document.getElementById("cotizacion_numero").value;

   			  document.getElementById("cotizacion_fecha_aux").value = document.getElementById("cotizacion_fecha").value;

              document.getElementById("rif_numero_aux").value = document.getElementById("rif_numero").value;

   			  document.getElementById("rif_nombre_aux").value = document.getElementById("rif_nombre").value;

   			  document.getElementById("rif_direccion_aux").value = document.getElementById("rif_direccion").value;

    setTimeout("cambia_campos_cscp03_registro_cotizacion()", 1000);

    }//fin if
}//fin function




function display(){
   if(!document.getElementById("aux")){
            document.getElementById("valida_codigo").style.display = "none";
   }else if(!document.getElementById("aux_existe")){
   			document.getElementById("valida_codigo").style.display = "none";
   }else{
   			setTimeout("display()", 1000);
   		}//fin else
}//fin function

function valida_codigo_div(var1, var2){

document.getElementById("existe").value = ""+var1+"";
document.getElementById("aux_codigo").value = ""+var2+"";
display();


}//fin function


var tecla= window.event ? true:false;



function solonumeros(e){

 /**var key=tecla ? evt.which:evt.keyCode;
 return (key==13 || (key>=48 && key<=57));*/

 tecla_codigo = (document.all) ? e.keyCode : e.which;
if(tecla_codigo==8 || tecla_codigo==0 || tecla_codigo==13)return true;
patron =/[0-9\-]/;
tecla_valor = String.fromCharCode(tecla_codigo);
return patron.test(tecla_valor);

}

function solonumeros_enteros(e){

 /**var key=tecla ? evt.which:evt.keyCode;
 return (key==13 || (key>=48 && key<=57));*/

 tecla_codigo = (document.all) ? e.keyCode : e.which;
if(tecla_codigo==8 || tecla_codigo==0 || tecla_codigo==13)return true;
patron =/[0-9]/;
tecla_valor = String.fromCharCode(tecla_codigo);
return patron.test(tecla_valor);

}

function solonumeros_con_punto(e){
   ncomas = new Array(0,0);
   tecla_codigo = (document.all) ? e.keyCode : e.which;
   //alert(tecla_codigo);
   if(tecla_codigo==8 || tecla_codigo==0 || tecla_codigo==13 || tecla_codigo==46 || tecla_codigo==45){
     if(tecla_codigo==46){
          if(document.getElementById(e.target.id).value.length==0){
              document.getElementById(e.target.id).value=document.getElementById(e.target.id).value+'0,';
          }else{
              document.getElementById(e.target.id).value=document.getElementById(e.target.id).value+',';
          }
               document.getElementById(e.target.id).value=document.getElementById(e.target.id).value.replace(",,",",");
               for (i=0; i < document.getElementById(e.target.id).value.length; i++){
                 letra = document.getElementById(e.target.id).value.charAt(i);
                 ncomas[0] = (letra==",")? ncomas[0] + 1: ncomas[0];
               }
               for(i=1;i<=ncomas[0]-1;i++){
                  document.getElementById(e.target.id).value=document.getElementById(e.target.id).value.replace(",","");
               }

               return false;
      }

     if(tecla_codigo==45){
         if(document.getElementById(e.target.id).value.length==0){
              document.getElementById(e.target.id).value=document.getElementById(e.target.id).value+'-';
          }else{
              document.getElementById(e.target.id).value=document.getElementById(e.target.id).value+'-';
          }
               document.getElementById(e.target.id).value=document.getElementById(e.target.id).value.replace("--","-");
               for (i=0; i < document.getElementById(e.target.id).value.length; i++){
                 letra = document.getElementById(e.target.id).value.charAt(i);
                 ncomas[1] = (letra=="-")? ncomas[1] + 1: ncomas[1];
               }
               for(i=1;i<=ncomas[1];i++){
                  document.getElementById(e.target.id).value=document.getElementById(e.target.id).value.replace("-","");
               }
               document.getElementById(e.target.id).value="-"+document.getElementById(e.target.id).value;
         return false;
     }
      return true;
   }
   patron =/[0-9]/;
   tecla_valor = String.fromCharCode(tecla_codigo);
   return patron.test(tecla_valor);

}//fin solo numeros con punto

/*function solonumeros_con_punto(e){

   tecla_codigo = (document.all) ? e.keyCode : e.which;
if(tecla_codigo==8 || tecla_codigo==0 || tecla_codigo==13 || tecla_codigo==44)return true;
patron =/[0-9\-]/;tecla_valor = String.fromCharCode(tecla_codigo);return patron.test(tecla_valor);

}*/

function solonumeros_con_punto_neg(e){
   ncomas = new Array(0,0);
   tecla_codigo = (document.all) ? e.keyCode : e.which;
   //alert(tecla_codigo);
   if(tecla_codigo==8 || tecla_codigo==0 || tecla_codigo==13 || tecla_codigo==46){
     if(tecla_codigo==46){
          if(document.getElementById(e.target.id).value.length==0){
              document.getElementById(e.target.id).value=document.getElementById(e.target.id).value+'0,';
          }else{
              document.getElementById(e.target.id).value=document.getElementById(e.target.id).value+',';
          }
               document.getElementById(e.target.id).value=document.getElementById(e.target.id).value.replace(",,",",");
               for (i=0; i < document.getElementById(e.target.id).value.length; i++){
                 letra = document.getElementById(e.target.id).value.charAt(i);
                 ncomas[0] = (letra==",")? ncomas[0] + 1: ncomas[0];
               }
               for(i=1;i<=ncomas[0]-1;i++){
                  document.getElementById(e.target.id).value=document.getElementById(e.target.id).value.replace(",","");
               }

               return false;
      }


      return true;
   }
   patron =/[0-9]/;
   tecla_valor = String.fromCharCode(tecla_codigo);
   return patron.test(tecla_valor);

}//fin solo numeros con punto


function solonumeros_con_punto2(e){

 /**var key=tecla ? evt.which:evt.keyCode;
 return (key==13 || (key>=48 && key<=57));*/

 tecla_codigo = (document.all) ? e.keyCode : e.which;
 //alert(tecla_codigo);
if(tecla_codigo==8 || tecla_codigo==0 || tecla_codigo==13 || tecla_codigo==46)return true;
patron =/[0-9\-]/;
tecla_valor = String.fromCharCode(tecla_codigo);
return patron.test(tecla_valor);

}


function sololetras(evt){

 var key=tecla ? evt.which:evt.keyCode;

 return (key<=13 || (key>=65 && key<=122));

}


function link(link){

	if(link=='control'){

	  // window.location='../../';

	}else if(link=='logout'){

		//msj_none();

		//window.location='salida.php';

	}else if(link=='cancel'){


/*pagina1='principal.php';
pagina2='links.php';

cargar_contenido('link',pagina2);
cargar_contenido('principal',pagina1);*/


	}else if(link=='ayuda'){

		alert("Zona en Construcion");

	}

}



function populate_inscripcion(objForm,selectIndex, id) {

if(id){id = '_'+id; }else{id='';}

if(document.getElementById('year'+id).value!="" && document.getElementById('mes'+id).value!=""){
timeA = new Date(document.getElementById('year'+id).options[document.getElementById('year'+id).selectedIndex].text, document.getElementById('mes'+id).options[document.getElementById('mes'+id).selectedIndex].value,1);
timeDifference = timeA - 86400000;
timeB = new Date(timeDifference);
var daysInMonth = timeB.getDate();
for (var i = 0; i < document.getElementById('dia'+id).length; i++){document.getElementById('dia'+id).options[0] = null;}
for (var i = 0; i < daysInMonth; i++) {document.getElementById('dia'+id).options[i] = new Option(i+1);
                                       document.getElementById('dia'+id).options[i].value=i+1;}
document.getElementById('dia'+id).options[0].selected = true;
 }//FIN IF


}//FIN FUNCION

//FIN FECHA


function confirmar(mensaje){alert('hola'); if(mensaje!=''){return false;}}


function cargar_monto_id(id_a, id_b, id_c){


  a = document.getElementById(id_a).value;
  b = document.getElementById(id_b).value;


var str = a;
for(i=0; i<a.length; i++){
    str = str.replace('.','');
}
str = str.replace(',','.');
var a = redondear(str,2);

var str = b;
for(i=0; i<b.length; i++){
    str = str.replace('.','');
}
str = str.replace(',','.');
var b = redondear(str,2);


  document.getElementById(id_c).value = eval(a) + eval(b);
  moneda(id_c);


}





function mascara_rif(id){
	    var checkStr = document.getElementById(id).value;
        var ch = '';
		var value="";

	for(j=0; j<checkStr.length; j++){
		ch =  checkStr.charAt(j);
		str = ch.replace(',','.');
       var b = redondear(str,2);

		ch_aux = checkStr.charAt(j-1);
		if(j==1 && ch!='-'){ value = value+'-'+ch;
		}else if(j==(checkStr.length-1) && ch_aux!='-'){value = value+'-'+ch;
		}else{ value = value+ch;}
}//fin for
document.getElementById(id).value=value;
CompruebaDatos(value);
}//fin funtion

function CompruebaDatos(elRIF){
  var resul = false;
  // pasar a mayúsculas
  var temp = elRIF.toUpperCase();
  if (!/^[JVEGPIRC]/.test(temp))
   // Es una letra de las admitidas?
   alert("El primer caracter es incorrecto, debe ser una letra de las siguientes: J,V,E,G,P,I,R,C");
  else if (!/^[JVEGPIRC][-][0-9]{8}[-][0-9]$/.test(temp)) // Son 9 dígitos?
   alert ("Por Favor Verifique que el RIF tenga el siguiente formato: J-12345678-9 \nSi su Rif es menor a 9 digitos rellene con 0 a la izquierda Ej: J-00123456-7\n Su rif actual es: "+temp);
  else
  resul = true;
   return resul;
 }


  function iniciar(){
  	this.document.getElementById('anoPresupuesto').focus()
  	//alert("hola");
  }
      function limpia_cod_auxiliar(){
    	document.getElementById('cod_auxiliar').value="";
    	document.getElementById('cod_auxiliar').disabled="disabled";
    }




function codigo_ventana(url, width_aux, height_aux, title_aux, resizable_aux, maximizable_aux, minimizable_aux, closable_aux){

//bluelighting
//Vista_Basic
//mac_os_x
        if(document.getElementById('capa_ventana').value==""){
			//var win = new Window({className: "mac_os_x", width:width_aux, height:height_aux, zIndex: 10000, resizable: resizable_aux, title: title_aux, showEffect:Effect.BlindDown, hideEffect: Effect.SwitchOff, draggable:true, wiredDrag: true})
			var win = new Window({className: "mac_os_x",
			                                            width:width_aux,
			                                            height:height_aux,
			                                            zIndex: 40000,
			                                            resizable: resizable_aux,
			                                            title: title_aux,
			                                            draggable:true,
			                                            wiredDrag: true,
			                                            hideEffect:Element.hide,
			                                            showEffect:Element.show,
			                                            maximizable: maximizable_aux,
			                                            minimizable: minimizable_aux,
			                                            closable: closable_aux})
			win.getContent().update("");
            id_aux = document.getElementById('capa_ventana').value;
			ver_documento_ventana2(url, id_aux+'_content' );
			win.showCenter();
     }//fin function

 }

function ver_documento_ventana2(url_busqueda, ID, after){
new Ajax.Updater('', '',   ''   , ID ,url_busqueda, {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, requestHeaders:['X-Update', ID]}, session_update());
}








function mascara_select(objForm,selectIndex, id) {

valor_ver = document.getElementById(id).options[document.getElementById(id).selectedIndex].value;

           var checkOK = "01234567899";
           var checkStr = valor_ver;
           var allValid = true;
           var validGroups = true;
           for (i = 0;  i < checkStr.length;  i++)
            {
             ch = checkStr.charAt(i);
              for (j = 0;  j < checkOK.length;  j++)
                 if (ch == checkOK.charAt(j))
	break;
	if (j == checkOK.length)
	{
	 allValid = false;
	break;
	}
    }

    checkStr = document.getElementById(id).options[document.getElementById(id).selectedIndex].text;

if (!allValid) {


}else{

      if((checkStr.charAt(0)=="4" && checkStr.charAt(1)==".")){
  if(eval(valor_ver)<=9){  valor_ver = '4.0'+valor_ver;}else{valor_ver = '4.'+valor_ver;}
}else if( (checkStr.charAt(0)=="3" && checkStr.charAt(1)==".")){
  if(eval(valor_ver)<=9){  valor_ver = '3.0'+valor_ver;}else{valor_ver = '3.'+valor_ver;}
}else if(eval(valor_ver)<=9){

if(valor_ver.length==1){
   valor_ver = '0'+valor_ver;
}else{
   valor_ver = ''+valor_ver;
}




}


}//fin else





document.getElementById(id).options[0].value = document.getElementById(id).options[document.getElementById(id).selectedIndex].value;
document.getElementById(id).options[0].text = valor_ver;
document.getElementById(id).options[0].selected = true;


if(document.getElementById(id).options[document.getElementById(id).selectedIndex].value!=""){

 var aux_text = new Array();
 var aux_value = new Array();

for (var i = 0; i < document.getElementById(id).length; i++){
  aux_text[i] = document.getElementById(id).options[i].text;
  aux_value[i] = document.getElementById(id).options[i].value;
}//fin for


if(document.getElementById(id).options[1].text!="----"){

document.getElementById(id).options[document.getElementById(id).length] = new Option(eval(document.getElementById(id).length)+1);

ii = 1;

 document.getElementById(id).options[1].text =  "----";
 document.getElementById(id).options[1].value =  "";

for (var i = 2; i < document.getElementById(id).length; i++){
  document.getElementById(id).options[i].text =  aux_text[ii];
  document.getElementById(id).options[i].value = aux_value[ii];
  ii++;
}//fin for


}//fin if



}//fin else





}//FIN FUNCION
function CargarImagen (URL){
  window.open(URL,"ventanaImagen","width=490,height=220,fullscreen=no,scrollbars=yes,resizable=no,location=no,status=no,menubar=no,toolbar=no,titlebar=no")
}

function con_punto(e){

 /**var key=tecla ? evt.which:evt.keyCode;
 return (key==13 || (key>=48 && key<=57));*/

     tecla_codigo = (document.all) ? e.keyCode : e.which;

     if(tecla_codigo==8 || tecla_codigo==0 || tecla_codigo==13 || tecla_codigo==46)return true;
     patron =/[0-9\-]/;
     tecla_valor = String.fromCharCode(tecla_codigo);
     return patron.test(tecla_valor);
}



function verificaNumero(objEvent) {

var reKeyboardChars = /[\x00\x0D\x2D\x2F\x2E\x2C\x25]/;
var reValidChars = /[\x09\x0B\d]/;

var iKeyCode, strKey, objInput;

    if (isIE) {
      iKeyCode = objEvent.keyCode;
      objInput = objEvent.srcElement;
    } else {
      iKeyCode = objEvent.which;
      objInput = objEvent.target;
    }

    strKey = String.fromCharCode(iKeyCode);

    strKey = String.fromCharCode(iKeyCode);

if (iKeyCode == 9) return true;

    if (!reValidChars.test(strKey) &&
      !reKeyboardChars.test(strKey)) {
      //alert("Invalid Character Detected!\nKeyCode = " + iKeyCode + "\nCharacter =" + strKey);
      return false;
    }
  }



function Peso(obj,tammax,teclapres) {
var tecla = teclapres.keyCode;
vr = obj.value;
vr = vr.replace( "/", "" );
vr = vr.replace( "/", "" );
vr = vr.replace( ",", "" );
vr = vr.replace( ".", "" );
vr = vr.replace( ".", "" );
vr = vr.replace( ".", "" );
vr = vr.replace( ".", "" );
tam = vr.length;

if (tam < tammax && tecla != 8){ tam = vr.length + 1 ; }

if (tecla == 8 ){ tam = tam - 1 ; }

if ( tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 ){
  if ( tam <= 2 ){
    obj.value = vr ; }
  if ( (tam > 2) && (tam <= 5) ){
    obj.value = vr.substr( 0, tam - 2 ) + '.' + vr.substr( tam - 2, tam ) ; }
  /*if ( (tam >= 6) && (tam <= 8) ){
    obj.value = vr.substr( 0, tam - 5 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ) ; }
  if ( (tam >= 9) && (tam <= 11) ){
    obj.value = vr.substr( 0, tam - 8 ) + '.' + vr.substr( tam - 8, 3 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ) ; }
  if ( (tam >= 12) && (tam <= 14) ){
    obj.value = vr.substr( 0, tam - 11 ) + '.' + vr.substr( tam - 11, 3 ) + '.' + vr.substr( tam - 8, 3 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ) ; }
  if ( (tam >= 15) && (tam <= 17) ){
    obj.value = vr.substr( 0, tam - 14 ) + '.' + vr.substr( tam - 14, 3 ) + '.' + vr.substr( tam - 11, 3 ) + '.' + vr.substr( tam - 8, 3 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ) ;}
*/
}

}//fin

function Estatura(obj,tammax,teclapres) {
var tecla = teclapres.keyCode;
vr = obj.value;
vr = vr.replace( "/", "" );
vr = vr.replace( "/", "" );
vr = vr.replace( ",", "" );
vr = vr.replace( ".", "" );
vr = vr.replace( ".", "" );
vr = vr.replace( ".", "" );
vr = vr.replace( ".", "" );
tam = vr.length;

if (tam < tammax && tecla != 8){ tam = vr.length + 1 ; }

if (tecla == 8 ){ tam = tam - 1 ; }

if ( tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 ){
  if ( tam <= 2 ){
    obj.value = vr ; }
  if ( (tam > 1)){
    obj.value = vr.substr( 0, tam - 1 ) + '.' + vr.substr( tam - 1, tam ) ; }

  /*if ( (tam >= 6) && (tam <= 8) ){
    obj.value = vr.substr( 0, tam - 5 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ) ; }
  if ( (tam >= 9) && (tam <= 11) ){
    obj.value = vr.substr( 0, tam - 8 ) + '.' + vr.substr( tam - 8, 3 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ) ; }
  if ( (tam >= 12) && (tam <= 14) ){
    obj.value = vr.substr( 0, tam - 11 ) + '.' + vr.substr( tam - 11, 3 ) + '.' + vr.substr( tam - 8, 3 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ) ; }
  if ( (tam >= 15) && (tam <= 17) ){
    obj.value = vr.substr( 0, tam - 14 ) + '.' + vr.substr( tam - 14, 3 ) + '.' + vr.substr( tam - 11, 3 ) + '.' + vr.substr( tam - 8, 3 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ) ;}*/
}

}//fin


/*
copia
*/
function FormataValorNew_copia(obj,tammax,teclapres) {
var tecla = teclapres.keyCode;
vr = obj.value;
vr = vr.replace( "/", "" );
vr = vr.replace( "/", "" );
vr = vr.replace( ",", "" );
vr = vr.replace( ".", "" );
vr = vr.replace( ".", "" );
vr = vr.replace( ".", "" );
vr = vr.replace( ".", "" );
tam = vr.length;

if (tam < tammax && tecla != 8){ tam = vr.length + 1 ; }

if (tecla == 8 ){ tam = tam - 1 ; }

if ( tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 ){
  if ( tam <= 2 ){
    obj.value = vr ; }
  if ( (tam > 2) && (tam <= 5) ){
    obj.value = vr.substr( 0, tam - 2 ) + ',' + vr.substr( tam - 2, tam ) ; }
  if ( (tam >= 6) && (tam <= 8) ){
    obj.value = vr.substr( 0, tam - 5 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ) ; }
  if ( (tam >= 9) && (tam <= 11) ){
    obj.value = vr.substr( 0, tam - 8 ) + '.' + vr.substr( tam - 8, 3 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ) ; }
  if ( (tam >= 12) && (tam <= 14) ){
    obj.value = vr.substr( 0, tam - 11 ) + '.' + vr.substr( tam - 11, 3 ) + '.' + vr.substr( tam - 8, 3 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ) ; }
  if ( (tam >= 15) && (tam <= 17) ){
    obj.value = vr.substr( 0, tam - 14 ) + '.' + vr.substr( tam - 14, 3 ) + '.' + vr.substr( tam - 11, 3 ) + '.' + vr.substr( tam - 8, 3 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ) ;}
}

}//fin


function precio_unitario(id){
    var monto = document.getElementById(id).value;
    if(monto!=""){
       pag="../../include/precio_unitario.php?monto="+monto;
       cargarMonto(id,pag);
    }else{
      document.getElementById(id).value='0,000';
    }
}

/*
function sprintf() {
	if (!arguments || arguments.length < 1 || !RegExp) {
		return;
	}
	var str = arguments[0];
	var re = /([^%]*)%('.|0|\x20)?(-)?(\d+)?(\.\d+)?(%|b|c|d|u|f|o|s|x|X)(.*)/;
	var a = b = [], numSubstitutions = 0, numMatches = 0;
	while (a = re.exec(str)) {
		var leftpart = a[1], pPad = a[2], pJustify = a[3], pMinLength = a[4];
		var pPrecision = a[5], pType = a[6], rightPart = a[7];
		numMatches++;
		if (pType == '%') {
			subst = '%';
		} else {
			numSubstitutions++;
			if (numSubstitutions >= arguments.length) {
				alert('Error! Not enough function arguments (' + (arguments.length - 1) + ', excluding the string)\nfor the number of substitution parameters in string (' + numSubstitutions + ' so far).');
			}
			var param = arguments[numSubstitutions];
			var pad = "\"";
			if (pPad && pPad.substr(0,1) == "'") pad = leftpart.substr(1,1);
			else if (pPad) pad = pPad;
			var justifyRight = true;
			if (pJustify && pJustify === "-") justifyRight = false;
			var minLength = -1;
			if (pMinLength) minLength = parseInt(pMinLength);
			var precision = -1;
			if (pPrecision && pType == 'f') precision = parseInt(pPrecision.substring(1));
			var subst = param;
			if (pType == 'b') subst = parseInt(param).toString(2);
			else if (pType == 'c') subst = String.fromCharCode(parseInt(param));
			else if (pType == 'd') subst = parseInt(param) ? parseInt(param) : 0;
			else if (pType == 'u') subst = Math.abs(param);
			else if (pType == 'f') subst = (precision > -1) ? Math.round(parseFloat(param) * Math.pow(10, precision)) / Math.pow(10, precision): parseFloat(param);
			else if (pType == 'o') subst = parseInt(param).toString(8);
			else if (pType == 's') subst = param;
			else if (pType == 'x') subst = ("\"" + parseInt(param).toString(16)).toLowerCase();
			else if (pType == 'X') subst = ("\"" + parseInt(param).toString(16)).toUpperCase();
		}
		str = leftpart + subst + rightPart;
	}
	return str;
}//fin sprintf
*/

function str_repeat(i, m) { for (var o = []; m > 0; o[--m] = i); return(o.join('')); }

function sprintf () {
  var i = 0, a, f = arguments[i++], o = [], m, p, c, x;
  while (f) {
    if (m = /^[^\x25]+/.exec(f)) o.push(m[0]);
    else if (m = /^\x25{2}/.exec(f)) o.push('%');
    else if (m = /^\x25(?:(\d+)\$)?(\+)?(0|'[^$])?(-)?(\d+)?(?:\.(\d+))?([b-fosuxX])/.exec(f)) {
      if (((a = arguments[m[1] || i++]) == null) || (a == undefined)) throw("Too few arguments.");
      if (/[^s]/.test(m[7]) && (typeof(a) != 'number'))
        throw("Expecting number but found " + typeof(a));
      switch (m[7]) {
        case 'b': a = a.toString(2); break;
        case 'c': a = String.fromCharCode(a); break;
        case 'd': a = parseInt(a); break;
        case 'e': a = m[6] ? a.toExponential(m[6]) : a.toExponential(); break;
        case 'f': a = m[6] ? parseFloat(a).toFixed(m[6]) : parseFloat(a); break;
        case 'o': a = a.toString(8); break;
        case 's': a = ((a = String(a)) && m[6] ? a.substring(0, m[6]) : a); break;
        case 'u': a = Math.abs(a); break;
        case 'x': a = a.toString(16); break;
        case 'X': a = a.toString(16).toUpperCase(); break;
      }
      a = (/[def]/.test(m[7]) && m[2] && a > 0 ? '+' + a : a);
      c = m[3] ? m[3] == '0' ? '0' : m[3].charAt(1) : ' ';
      x = m[5] - String(a).length;
      p = m[5] ? str_repeat(c, x) : '';
      o.push(m[4] ? a + p : p + a);
    }
    else throw ("Huh ?!");
    f = f.substring(m[0].length);
  }
  return o.join('');
}//fin sprintf
//nueva


function redondear(cantidad, decimales){


		/*var   cantidad  = parseFloat(cantidad);
		var decimales2  = 4;
		var    formato  = '%01.'+decimales2+'f';
		var   cantidad  = eval(sprintf(formato,cantidad));
			     str =  cantidad+'';
				 for(x=0; x<str.length; x++){if(str.charAt(x)=="."){
				    //if(str.charAt(eval(x)+eval(4))=="5"){ cantidad = eval(cantidad) + eval(0.0001);}
				    break;
				      }//fin if
				   }//fin for



               str =  cantidad+'';
                 cantidad_aux = '';
				 for(x=0; x<str.length; x++){
				              cantidad_aux = cantidad_aux+str.charAt(x);
				      if(str.charAt(x)=="."){
				          for(y=1; y<5; y++){
                              cantidad_aux = cantidad_aux+str.charAt(eval(x)+eval(y));
				          }//fin if
				          break;
                      }//fin if
				   }//fin for

                  cantidad  = cantidad_aux;*/



	    var   cantidad  = parseFloat(cantidad);
		var decimales2  = 6;
		var    formato  = '%01.'+decimales2+'f';
		var   cantidad  = eval(sprintf(formato, cantidad));
			     str =  cantidad+'';
				 for(x=0; x<str.length; x++){if(str.charAt(x)=="."){
				    if(str.charAt(eval(x)+eval(3))=="5"){ cantidad = eval(cantidad) + eval(0.001);}
				    break;
				      }//fin if
				   }//fin for




		var cantidad = parseFloat(cantidad);
		  decimales = (!decimales ? 2 : parseInt(decimales));
		var formato = '%01.'+decimales+'f';
		      valor = sprintf(formato,cantidad);
		return eval(valor);

}//fin function



/*/antigua
function redondear(cantidad, decimales) {
var cantidad = parseFloat(cantidad);
var decimales = parseFloat(decimales);
decimales = (!decimales ? 2 : decimales);
return Math.round(cantidad * Math.pow(10, decimales)) / Math.pow(10, decimales);
}//*/




function moneda_negativo(id){
    var monto = document.getElementById(id).value;
    if(monto!=""){

       pag="../../include/cfpp05/moneda2.php?monto="+monto;
       cargarMonto(id,pag);
    }else{
      document.getElementById(id).value='0,00';
    }
}

 function c_msj(){
 	//Control.Modal.open(document.getElementById('c_usuarios').innerHTML);
 	//document.getElementById('c_usuarios').style.display="block";
 	if(document.getElementById('c_usuarios').style.display=="block"){
 	   document.getElementById('c_usuarios').style.display="none";
 	}else{
 	  document.getElementById('c_usuarios').style.display="block";
 	}
 }//fin c_msj
 function c_msj2(){
 	//Control.Modal.open(document.getElementById('c_usuarios').innerHTML);
 	//document.getElementById('c_usuarios').style.display="block";
 	if(document.getElementById('c_usuarios').style.display=="block"){
 	   document.getElementById('c_usuarios').style.display="none";
 	}
 }//fin c_msj
 function cambia_top(tema){
      switch(tema){
         case 'original':
             document.getElementById('top_izq').className='top_izq_1';
             document.getElementById('top_centro').className='top_centro_1';
             document.getElementById('top_der').className='top_der_1';
         break;
         case 'color1':
             document.getElementById('top_izq').className='top_izq_2';
             document.getElementById('top_centro').className='top_centro_2';
             document.getElementById('top_der').className='top_der_2';

         break;
      }
 }


 function ver_documento_ventana(url_busqueda,ID){
	new Ajax.Updater('', '',   ''   , ID ,url_busqueda, {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show('mini_loading');}, onComplete:function(request){Element.hide('mini_loading')}, requestHeaders:['X-Update', ID]});
    //Control.Modal.open(document.getElementById('contenido_programa_ventana').innerHTML);
 	setTimeout("Control.Modal.open(document.getElementById('contenido_programa_ventana').innerHTML);", 1000);
	}
function submit_window(id,url,id_contenedor,evento){
	var u=$$('#login')[1].value;
	var p=$$('#password')[1].value;
	var cadena='login='+u+'& password='+p;

	cargar_formulario(id_contenedor,cadena,url);
	Control.Modal.current.close(true);
	return false;
}
function entrar_reactualizar(id,url,id_contenedor,evento){
	var u=$$('#login')[1].value;
	var p=$$('#password')[1].value;
	var cadena='login='+u+'& password='+p;

	cargar_formulario(id_contenedor,cadena,url);
	Control.Modal.current.close(true);
	return false;
}
function CambiaBgTr(){
            var items = $$('table tr');
            for (var i = 0; i < items.size(); i++) {
                       if(items[i].bgColor=='#000000'){
                            items[i].className="tr_negro";
                            //alert("Ya se Encontro el TR negro "+items[i].rowIndex);
                           }
                //alert(items[i].bgColor);
             }
             alert(items);
            }

function camAlto(id,alto){
    document.getElementById(id).style.height=alto;
}





function menu_inactivo(){
    //document.write('<div id="menu_sisap_inactivo" class="menu_sisap_inactivo0"></div>');
    document.getElementById('menu_sisap_inactivo').className="menu_sisap_inactive";
}


function menu_activo(){
    document.getElementById('menu_sisap_inactivo').className="menu_sisap_inactivo0";
    //document.getElementById('menu_sisap_inactivo').style.visibility="hidden";

}

function _salir_programa(){
    $('principal').innerHTML = "<br/>";
    menu_activo();
}

function numeros1_9(e){

 tecla_codigo = (document.all) ? e.keyCode : e.which;

if(tecla_codigo==8 || tecla_codigo==0 || tecla_codigo==13)return true;
patron =/[0-9]/;
tecla_valor = String.fromCharCode(tecla_codigo);
return patron.test(tecla_valor);

}//fin function


function CompruebaEstatura(estatura,id){
  //alert(estatura);
  var resul = false;
  var temp = estatura;
  if (!/^[0-9]/.test(temp))
   alert("El primer caracter es incorrecto, debe ser un digito");
  else if (!/^[0-9][.][0-9]{2}$/.test(temp)){
   alert ("Por Favor Verifique la estatura");
   document.getElementById(id).value="";
  }else
  resul = true;
   return resul;
 }

 function cargar_imagenes_cugd10(IDCAPA) {
   alert('uno'+IDCAPA);
   if(frames.UploadTargetImagen.document){
    if(frames.UploadTargetImagen.document.getElementById('codigo_imagen_cugd10')){
            if(frames.UploadTargetImagen.document.getElementById('codigo_imagen_cugd10').value!=""){
        	IDM=frames.UploadTargetImagen.document.getElementById('codigo_imagen_cugd10').value;
        	alert('uno'+IDM);
            ver_documento(IDM,IDCAPA);
            frames.UploadTargetImagen.document.getElementById('codigo_imagen_cugd10').value="";
            Windows.close(document.getElementById('capa_ventana').value);
            }//document.getElementById('id_imagen').focus();
    }//fin if
   }
 }

 function verificar_iframes_prueba(){
     alert("Si funciona");
 }
  function nav_next_prev(elEvento){
     var evento = window.event || elEvento;
     if(evento.keyCode==37){//anterior
        if(document.getElementById('bt_anterior')){
           document.getElementById('bt_anterior').click();
        }
     }
     if(evento.keyCode==38){//anterior
        if(document.getElementById('bt_primero')){
           document.getElementById('bt_primero').click();
        }
     }
     if(evento.keyCode==39){//siguiente
        if(document.getElementById('bt_siguiente')){
           document.getElementById('bt_siguiente').click();
        }

     }
     if(evento.keyCode==40){//anterior
        if(document.getElementById('bt_ultimo')){
           document.getElementById('bt_ultimo').click();
        }
     }
      if(evento.altKey==true  && (evento.charCode==103 || evento.charCode==71)) {
         if(document.getElementById('guardar')){
           document.getElementById('guardar').click();
        }
       }
}//FIN NAV_NEXT_PREV

