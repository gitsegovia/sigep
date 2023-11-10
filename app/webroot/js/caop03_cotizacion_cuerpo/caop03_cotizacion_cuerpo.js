




function cambia_campos_caop03_registro_cotizacion(){
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
    setTimeout("cambia_campos_caop03_registro_cotizacion()", 1000);
    }//fin if
}//fin function



function calcular_total_caop04_ordencompra2(i){
	//alert('i es: '+i+'; '+eval(document.getElementById('total_manual').value)+'-'+eval(document.getElementById('monto2_'+i).value));
	var valor = eval(document.getElementById('total_manual').value) - eval(document.getElementById('monto2_'+i).value);
	valor = redondear(valor, 2);
	document.getElementById('total_manual').value = valor;
	cscp03_cotizacion_cuerpo_moneda('total2_manual',valor);
//	document.getElementById('total').innerHTML=valor;
//	moneda('total');
}















function caop03_cotizacion_cuerpo_cargar_monto_id2(id_a, id_b, id_c){


	a=document.getElementById(id_a).value;
	b=document.getElementById(id_b).value;
	total = 0;

var str = a;
for(i=0; i<a.length; i++){
    str = str.replace('.','');
}
str = str.replace(',','.');
var a = redondear(str,6);

var str = b;
for(i=0; i<b.length; i++){
    str = str.replace('.','');
}
str = str.replace(',','.');
var b = str;


	c=redondear(eval(a)*eval(b),2);
	//document.getElementById(id_c).innerHTML=c;












caop03_cotizacion_cuerpo_moneda(id_c,c);



for (ii=0; ii<document.getElementById('cuenta_ii12').value; ii++){

 a = document.getElementById('cantidad2_'+ii).value;
 b = document.getElementById('precio2_'+ii).value;

 var str = a;
for(i=0; i<a.length; i++){
    str = str.replace('.','');
}
str = str.replace(',','.');
var a = redondear(str,6);

var str = b;
for(i=0; i<b.length; i++){
    str = str.replace('.','');
}
str = str.replace(',','.');
var b = str;

 c=redondear(eval(a)*eval(b), 2);

 total =  eval(total) + eval(c);



}//fin for


//////////////////////////////////////////////////////////////
		 var str =  total+'';
		 for(x=0; x<str.length; x++){if(str.charAt(x)=="."){
		    total=str.substr(0,eval(x)+eval(6));
		    break;
		    }//fin if
		   }//fin for
		 var total = redondear(total,2);
//////////////////////////////////////////////////////////////


	caop03_cotizacion_cuerpo_moneda('TOTALINGRESOS2', total);

}//fin function









function caop03_cotizacion_cuerpo_cargar_monto_id(id_a, id_b, id_c){


	a=document.getElementById(id_a).value;
	b=document.getElementById(id_b).value;
	total = 0;

var str = a;
for(i=0; i<a.length; i++){
    str = str.replace('.','');
}
str = str.replace(',','.');
var a = redondear(str,6);

var str = b;
for(i=0; i<b.length; i++){
    str = str.replace('.','');
}
str = str.replace(',','.');
var b = str;


	c=redondear(eval(a)*eval(b),2);
	//document.getElementById(id_c).innerHTML=c;




				cuenta = 0;
				limite = eval(document.getElementById('cuenta_i').value) -eval(1);
				iva    = 0;

				for (ii=0; ii<limite; ii++){
					bb = document.getElementById('precio_'+ii).value;
					var str = bb;
					for(i=0; i<bb.length; i++){
					    str = str.replace('.','');
					}
					str = str.replace(',','.');
					var bb = redondear(str,6);

					 aa = document.getElementById('cantidad_'+ii).value;
					 var str = aa;
					 for(i=0; i<aa.length; i++){
					    str = str.replace('.','');
					 }
					 str = str.replace(',','.');
					 var aa = redondear(str,6);

					  cc=redondear(eval(aa)*eval(bb), 2);


					   if(document.getElementById('exento_iva_'+ii).value=="2"){
					         	bbb = document.getElementById('alicuota_iva_'+ii).value;
								var str = bbb;
								str = str.replace(',','.');
								var bbb = redondear(str,6);
                                iva = eval(iva) +  ( eval(cc) * (eval(bbb) / eval(100)) );

					   }
				       if(bb=="0"){cuenta++;}
				}//fin foreach



				if($('existe_iva')){
						    bb = document.getElementById('precio_'+limite).value;
							var str = bb;
							for(i=0; i<bb.length; i++){
							    str = str.replace('.','');
							}
							str = str.replace(',','.');
							var bb = redondear(str,6);

							  // if(bb=="0"){
		                         document.getElementById('precio_'+limite).value = redondear(iva, 6);
		                         precio_unitario('precio_'+limite);
		                         cscp03_cotizacion_cuerpo_moneda('monto_'+limite, redondear(iva, 6));
		                      // }
			    }//fin if


cscp03_cotizacion_cuerpo_moneda(id_c,c);
for (ii=0; ii<document.getElementById('cuenta_i').value; ii++){
				if(limite==ii){
						      	 if($('existe_iva')){
						      	   c = redondear(iva,2);
						      	 }else{
						      	   a = retornar_valor_calculo(document.getElementById('cantidad_'+ii).value);
								   b = retornar_valor_calculo(document.getElementById('precio_'+ii).value);
								   c = redondear(eval(a)*eval(b), 2);
						      	 }
				}else{
								 a = retornar_valor_calculo(document.getElementById('cantidad_'+ii).value);
								 b = retornar_valor_calculo(document.getElementById('precio_'+ii).value);
								 c = redondear(eval(a)*eval(b), 2);
                }//FIN ELSE
 total =  eval(total) + eval(c);
}//fin for
	var total = redondear(total,2);
	cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total);
}//fin function













function caop03_cotizacion_cuerpo_cargar_monto_id_iva(id_a, id_b, id_c){
	a=document.getElementById(id_a).value;
	b=document.getElementById(id_b).value;
	total = 0;

var str = a;
for(i=0; i<a.length; i++){
    str = str.replace('.','');
}
str = str.replace(',','.');
var a = redondear(str,6);

var str = b;
for(i=0; i<b.length; i++){
    str = str.replace('.','');
}
str = str.replace(',','.');
var b = str;


c_aux=redondear(eval(a)*eval(b),2);
                cuenta = 0;
				limite = eval(document.getElementById('cuenta_i').value) -eval(1);
				iva    = 0;
cscp03_cotizacion_cuerpo_moneda(id_c,c_aux);
for (ii=0; ii<document.getElementById('cuenta_i').value; ii++){
				if(limite==ii){
						      	 c = c_aux;
				}else{
								 a = retornar_valor_calculo(document.getElementById('cantidad_'+ii).value);
								 b = retornar_valor_calculo(document.getElementById('precio_'+ii).value);
								 c = redondear(eval(a)*eval(b), 2);
                }//FIN ELSE
 total =  eval(total) + eval(c);
}//fin for
	var total = redondear(total,2);
	cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total);
}//fin function















function caop03_cotizacion_cuerpo_cargar_monto_id_editar(id_a, id_b, id_c){


	a=document.getElementById(id_a).value;
	b=document.getElementById(id_b).value;
	total = 0;


var str = a;
for(i=0; i<a.length; i++){
    str = str.replace('.','');
}
str = str.replace(',','.');
var a = redondear(str,6);

var str = b;
for(i=0; i<b.length; i++){
    str = str.replace('.','');
}
str = str.replace(',','.');
var b = str;


	c=redondear(eval(a)*eval(b),2);
	//document.getElementById(id_c).innerHTML=c;
	caop03_cotizacion_cuerpo_moneda(id_c,c);

for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){

  aux = 'monto_'+ii;

  if(aux == id_c){a = c;

  }else{

 a = document.getElementById('monto_'+ii).innerHTML;

var str = a;
for(i=0; i<a.length; i++){
    str = str.replace('.','');
}//fin for

str = str.replace(',','.');
var a = redondear(str,2);

}//fin else

 total =  eval(total) + eval(a);

}//fin for


//////////////////////////////////////////////////////////////
		 var str =  total+'';
		 for(x=0; x<str.length; x++){if(str.charAt(x)=="."){
		    total=str.substr(0,eval(x)+eval(6));
		    break;
		    }//fin if
		   }//fin for
		 var total = redondear(total,2);
//////////////////////////////////////////////////////////////


	//caop03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total);

}//fin function






function caop03_cotizacion_cuerpo_cargar_cargarMonto(id,pagina){
	  var myConn = new XHConn();
		if (!myConn) fun_msj("XMLHTTP no esta disponible. Intentalo con un navegador mas nuevo.");
		var peticion = function (oXML) {document.getElementById(id).innerHTML = oXML.responseText; };
		myConn.connect(pagina, "GET", "", peticion);
		;
}




function caop03_cotizacion_cuerpo_moneda(id, monto){
    if(monto!=""){
//////////////////////////////////////////////////////////////
		 var str =  monto+'';
		 for(x=0; x<str.length; x++){if(str.charAt(x)=="."){
		    monto=str.substr(0,eval(x)+eval(6));
		    break;
		    }//fin if
		   }//fin for
		 var monto = redondear(monto,2);
//////////////////////////////////////////////////////////////

       pag="../../include/cfpp05/moneda.php?monto="+monto;
       caop03_cotizacion_cuerpo_cargar_cargarMonto(id,pag);
    }else{
      document.getElementById(id).innerHTML='0,00';
    }
}//fin function









function caop03_cotizacion_valida(){


         if(document.getElementById('solicitud_cotizacion_ano').value==""){

         	fun_msj('Inserte el a&ntilde;o de solicitud de cotizaci&oacute;n');
			document.getElementById('solicitud_cotizacion_ano').focus();
			return false;


   }else if(document.getElementById('solicitud_cotizacion_numero').value==""){

            fun_msj('Inserte el n&uacute;mero de solicitud de cotizaci&oacute;n');
			document.getElementById('solicitud_cotizacion_numero').focus();
			return false;


	}else if(document.getElementById('solicitud_cotizacion_fecha').value==""){

            fun_msj('Inserte la fecha de solicitud de cotizaci&oacute;n');
			document.getElementById('solicitud_cotizacion_fecha').focus();
			return false;


	}else if(document.getElementById('cotizacion_ano').value==""){

            fun_msj('Inserte el a&ntilde;o de cotizaci&oacute;n');
			document.getElementById('cotizacion_ano').focus();
			return false;


	}else if(document.getElementById('cotizacion_numero').value==""){

            fun_msj('Inserte el n&uacute;mero de cotizaci&oacute;n');
			document.getElementById('cotizacion_numero').focus();
			return false;


	}else if(document.getElementById('cotizacion_fecha').value==""){

            fun_msj('Inserte la fecha de cotizaci&oacute;n');
			document.getElementById('cotizacion_fecha').focus();
			return false;


	}else if(verifica_cierre_ano_ejecucion('cotizacion_fecha')==false){
			fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA DE LA COTIZACI&Oacute;N NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
			return false;
	}else if(document.getElementById('rif_numero').value==""){

            fun_msj('Inserte el n&uacute;mero de rif');
			document.getElementById('rif_numero').focus();
			return false;


	}else if(document.getElementById('rif_nombre').value==""){

            fun_msj('Inserte el nombre de rif');
			document.getElementById('rif_nombre').focus();
			return false;


    }else if(document.getElementById('rif_direccion').value==""){

            fun_msj('Inserte la direcci&oacute;n de rif');
			document.getElementById('rif_direccion').focus();
			return false;


	}else if(document.getElementById('cuenta_i').value!=""){


	   for(i=0; i<document.getElementById('cuenta_i').value; i++){

	     aux = eval(i + 1);

	          if(document.getElementById('cantidad_'+i).value==""){
         			fun_msj('Inserte la cantidad del item '+ aux);
					document.getElementById('cantidad_'+i).focus();
					return false;

		}else if(document.getElementById('descripcion_'+i).value==""){
            		fun_msj('Inserte la descripci&oacute;n  del item '+ aux);
					document.getElementById('descripcion_'+i).focus();
					return false;

  		 }else if(document.getElementById('precio_'+i).value==""){
            		fun_msj('Inserte el precio  del item '+ aux);
					document.getElementById('precio_'+i).focus();
					return false;

		 }//fin else


	   }//fin for

   }//fin else


}//fin if






function caop03_cotizacion_valida_editar(i){


              if(document.getElementById('cantidad').value==""){
         			fun_msj('Inserte la cantidad del item ');
					document.getElementById('cantidad').focus();
					return false;

		}else if(document.getElementById('descripcion').value==""){
            		fun_msj('Inserte la descripci&oacute;n  del item ');
					document.getElementById('descripcion').focus();
					return false;

  		 }else if(document.getElementById('precio').value==""){
            		fun_msj('Inserte el precio  del item ');
					document.getElementById('precio').focus();
					return false;

		 }//fin else



}//fin function


