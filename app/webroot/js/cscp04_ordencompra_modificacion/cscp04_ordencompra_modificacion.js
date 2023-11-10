function cscp04_ordencompra_modificacion_valida_obra(){

if(verifica_cierre_ano_ejecucion('fecha_modificacion')==false){
	fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA DE MODIFICACI&Oacute;N NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
	return false;
}else{

if(document.getElementById('ano_orden_compra_modificacion').value==''){

			fun_msj('Inserte el a&ntilde;o de la modificaci&oacute;n');
			document.getElementById('ano_orden_compra_modificacion').focus();
			return false;

	}else if(document.getElementById('numero_orden_compra_modificacion').value==''){

			fun_msj('Inserte el n&uacute;mero de modificaci&oacute;n ');
			document.getElementById('numero_orden_compra_modificacion').focus();
			return false;

	}else if(document.getElementById('fecha_modificacion').value==''){

			fun_msj('Inserte la fecha de la modificaci&oacute;n');
			document.getElementById('fecha_modificacion').focus();
			return false;

    }else if(verifica_cierre_ano_ejecucion('fecha_modificacion')==false){
			fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA DE MODIFICACI&Oacute;N NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
			return false;
	}else if(document.getElementById('tipo_modificacion_1').checked!=false || document.getElementById('tipo_modificacion_2').checked!=false){


total = 0;
for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
 a = document.getElementById('modificacion_'+ii).value;
var str = a;
for(i=0; i<a.length; i++){
    str = str.replace('.','');
}//fin for
str = str.replace(',','.');
var a = redondear(str,2);
total =  eval(total) + eval(a);
}//fin for

var str =  total+'';for(x=0; x<str.length; x++){if(str.charAt(x)=="."){total=str.substr(0,eval(x)+eval(6));break;}} var total = redondear(total,2);




			if(document.getElementById('tipo_modificacion_1').checked!=false){

						total2 = 0;
						input="aumento_obras_extras"; opcion="Monto del Aumento";
						aa = document.getElementById(input).value;
						var str = aa;
						for(i=0; i<aa.length; i++){
						    str = str.replace('.','');
						}
						str = str.replace(',','.');
						var aa = redondear(str,2);
						total2 = aa;

						input="aumento_reconsideracion"; opcion="Monto del Aumento";
						aa = document.getElementById(input).value;
						var str = aa;
						for(i=0; i<aa.length; i++){
						    str = str.replace('.','');
						}
						str = str.replace(',','.');
						var aa = redondear(str,2);
						aa = eval(aa) + eval(total2);
						total2 = eval(aa);

						input="aumento_obras"; opcion="Monto del Aumento";
						aa = document.getElementById(input).value;
						var str = aa;
						for(i=0; i<aa.length; i++){
						    str = str.replace('.','');
						}
						str = str.replace(',','.');
						var aa = redondear(str,2);
						aa = eval(aa) + eval(total2);


			}else{input="disminucion"; opcion="Monto de la Disminuci&oacute;n";

						aa = document.getElementById(input).value;
						var str = aa;
						for(i=0; i<aa.length; i++){
						    str = str.replace('.','');
						}//fin for
						str = str.replace(',','.');
						var aa = redondear(str,2);

			}//FIN


//if(aa=="0,00"){aa="0";}




               if(document.getElementById('tipo_modificacion_validacion').value==1){

               opcion =  " la orden de compra";

         }else if(document.getElementById('tipo_modificacion_validacion').value==2){

                opcion =  " el contrato";

         }





      if(diferenciaFecha(document.getElementById('dia_actual').value, document.getElementById('fecha_modificacion').value)){

                               fun_msj('La fecha de la modificaci&oacute;n no puede ser mayor a la del dia');  return false;

}else if(diferenciaFecha(document.getElementById('fecha_modificacion').value, document.getElementById('fecha_validacion').value)){

                              fun_msj('La fecha de la modificaci&oacute;n es menor a la fecha de registro  de '+opcion);  return false;

}else if((total==0 && aa==0)){fun_msj('No a realizado ninguna modificaci&oacute;n'); cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total); return false;

}else if(total<aa){fun_msj('El total es menor al '+opcion); cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total); return false;

}else if(total>aa){fun_msj('El total es mayor al '+opcion); cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total); return false;

}else if(document.getElementById('observaciones').value==''){

			fun_msj('Inserte las observaci&oacute;nes de la modificaci&oacute;n');
			document.getElementById('observaciones').focus();
			return false;

}//fin else





	}else{

	        fun_msj('Selecione el tipo de modificaci&oacute;n');
			return false;

	}//fin else

}// FIN ELSE VERIFICANDO ANO EJEC <> ANO FECHA DOC.

}//fin function

























function cscp04_ordencompra_modificacion_valida(){

if(verifica_cierre_ano_ejecucion('fecha_modificacion')==false){
	fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA DE MODIFICACI&Oacute;N NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
	return false;
}else{

if(document.getElementById('ano_orden_compra_modificacion').value==''){

			fun_msj('Inserte el a&ntilde;o de la modificaci&oacute;n');
			document.getElementById('ano_orden_compra_modificacion').focus();
			return false;

	}else if(document.getElementById('numero_orden_compra_modificacion').value==''){

			fun_msj('Inserte el n&uacute;mero de modificaci&oacute;n ');
			document.getElementById('numero_orden_compra_modificacion').focus();
			return false;

	}else if(document.getElementById('fecha_modificacion').value==''){

			fun_msj('Inserte la fecha de la modificaci&oacute;n');
			document.getElementById('fecha_modificacion').focus();
			return false;

    }else if(document.getElementById('tipo_modificacion_1').checked!=false || document.getElementById('tipo_modificacion_2').checked!=false){


total = 0;
for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
 a = document.getElementById('modificacion_'+ii).value;
var str = a;
for(i=0; i<a.length; i++){
    str = str.replace('.','');
}//fin for
str = str.replace(',','.');
var a = redondear(str,2);
total =  eval(total) + eval(a);
}//fin for

if(document.getElementById('tipo_modificacion_1').checked!=false){input="aumento"; opcion="Monto del Aumento";}else{input="disminucion"; opcion="Monto de la Disminuci&oacute;n";}
 aa = document.getElementById(input).value;
var str = aa;
for(i=0; i<aa.length; i++){
    str = str.replace('.','');
}//fin for
str = str.replace(',','.');
var aa = redondear(str,2);

//if(aa=="0,00"){aa="0";}


var str =  total+'';for(x=0; x<str.length; x++){if(str.charAt(x)=="."){total=str.substr(0,eval(x)+eval(6));break;}} var total = redondear(total,2);


              if(document.getElementById('tipo_modificacion_validacion').value==1){

               opcion =  " la orden de compra";

         }else if(document.getElementById('tipo_modificacion_validacion').value==2){

                opcion =  " el contrato";

         }

      if(diferenciaFecha(document.getElementById('dia_actual').value, document.getElementById('fecha_modificacion').value)){

                               fun_msj('La fecha de la modificaci&oacute;n no puede ser mayor a la del dia');  return false;

}else if(diferenciaFecha(document.getElementById('fecha_modificacion').value, document.getElementById('fecha_validacion').value)){

                              fun_msj('La fecha de la modificaci&oacute;n es menor a la fecha de registro a la de '+opcion);  return false;

}else if((total==0 && aa==0)){fun_msj('No a realizado ninguna modificaci&oacute;n'); cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total); return false;

}else if(total<aa){fun_msj('El total es menor al '+opcion); cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total); return false;

}else if(total>aa){fun_msj('El total es mayor al '+opcion); cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total); return false;

}else if(document.getElementById('observaciones').value==''){

			fun_msj('Inserte las observaci&oacute;nes de la modificaci&oacute;n');
			document.getElementById('observaciones').focus();
			return false;

}//fin else





	}else{

	        fun_msj('Selecione el tipo de modificaci&oacute;n');
			return false;

	}//fin else


	if(document.getElementById('fecha_modificacion').value!=""){
           return verifica_cierre_mes_ejecucion('fecha_modificacion');
    }

}// FIN ELSE VERIFICANDO ANO EJEC <> ANO FECHA DOC.

}//fin function







function cscp04_ordencompra_modificacion_cargar_monto_id_editar(id_a, c){
//alert('epale');
if(document.getElementById('tipo_modificacion_1').checked!=false || document.getElementById('tipo_modificacion_2').checked!=false){
if(c!=null){
 a = document.getElementById('modificacion_'+c).value;
 b = document.getElementById('monto_actual_'+c).value;
//alert('modificacion = '+a+' y monto actual = '+b+';c es '+c)
	if((eval(a) > eval(b)) && document.getElementById('tipo_modificacion_2').checked!=false){
		//alert('entre en la condicion');
		fun_msj('EL MONTO DE LA MODIFICACION NO PUEDE SER MAYOR AL MONTO ACTUAL');
		document.getElementById('modificacion_'+c).value='';
		moneda('modificacion_'+c);
		document.getElementById('modificacion_'+c).focus();
		return;
	}
}
	total = 0;
	for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
	 a = document.getElementById('modificacion_'+ii).value;
	 moneda('modificacion_'+ii);
		var str = a;
		for(i=0; i<a.length; i++){
		    str = str.replace('.','');
		}//fin for
		str   = str.replace(',','.');
		var a = redondear(str,2);
		total =  eval(total) + eval(a);
}//fin for

if(document.getElementById('tipo_modificacion_1').checked!=false){
	input="aumento"; opcion="Monto del Aumento";
}else{
	input="disminucion"; opcion="Monto de la Disminucion";
}
 aa = document.getElementById(input).value;
 bb = document.getElementById('monto_actual_base').value;
 //alert(aa+'>'+bb);
 if((eval(aa) > eval(bb)) && document.getElementById('tipo_modificacion_2').checked!=false){
		fun_msj('EL MONTO DE LA MODIFICACION NO PUEDE SER MAYOR AL MONTO ACTUAL');
		document.getElementById(input).value='';
		moneda(input);
		document.getElementById(input).focus();
		return;
 }else{
 	moneda(input);
 }

var str = aa;
for(i=0; i<aa.length; i++){
    str = str.replace('.','');
}//fin for
str = str.replace(',','.');
var aa = redondear(str,2);

//if(aa=="0,00"){aa="0";}

var str =  total+'';for(x=0; x<str.length; x++){if(str.charAt(x)=="."){total=str.substr(0,eval(x)+eval(6));break;}} var total = redondear(total,2);

      if(total<=aa){ cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total);

}else if(total>aa){fun_msj('El total es mayor al '+opcion); cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total);}//fin else


}else{

     document.getElementById(id_a).value="";
     fun_msj('Selecione el tipo de modificaci&oacute;n');


    }//fin else

}//fin function





function cscp04_ordencompra_modificacion_valida_consulta(){

	if(verifica_cierre_ano_ejecucion_msj()==false){
		return false;
	}else if(document.getElementById('concepto_anulacion').value == ""){
            fun_msj('Inserte el concepto de anulacion');
			document.getElementById('concepto_anulacion').focus();
			return false;
   	}//fin if

}//fin function







function cscp04_ordencompra_modificacion_cargar_monto_id_editars(id_a, c){

if(document.getElementById('tipo_modificacion_1').checked!=false || document.getElementById('tipo_modificacion_2').checked!=false){
if(c!=null){
 a = document.getElementById('modificacion_'+c).value;
 b = document.getElementById('monto_actual_'+c).value;
//alert('modificacion = '+a+' y monto actual = '+b+';c es '+c)
	if((eval(a) > eval(b)) && document.getElementById('tipo_modificacion_2').checked!=false){
		//alert('entre en la condicion');
		fun_msj('EL MONTO DE LA MODIFICACION NO PUEDE SER MAYOR AL MONTO ACTUAL');
		document.getElementById('modificacion_'+c).value='';
		moneda('modificacion_'+c);
		document.getElementById('modificacion_'+c).focus();
		return;
	}
}
	total = 0;
	for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
	 a = document.getElementById('modificacion_'+ii).value;
	 moneda('modificacion_'+ii);
		var str = a;
		for(i=0; i<a.length; i++){
		    str = str.replace('.','');
		}//fin for
		str   = str.replace(',','.');
		var a = redondear(str,2);
		total =  eval(total) + eval(a);
}//fin for




 bb = document.getElementById('monto_actual_base').value;

var str = bb;
for(i=0; i<bb.length; i++){
    str = str.replace('.','');
}//fin for
str = str.replace(',','.');
var bb = redondear(str,2);




if(document.getElementById('tipo_modificacion_1').checked!=false){
	input  = "aumento_obras_extras";
	input2 = "aumento_reconsideracion";
	input3 = "aumento_obras";
	opcion="Monto del Aumento";


x = document.getElementById(input).value;
y = document.getElementById(input2).value;
z = document.getElementById(input3).value;


var str = x+'';
for(i=0; i<x.length; i++){str = str.replace('.','');}//fin for
str = str.replace(',','.');
var x = redondear(str,2);



var str = y+'';
for(i=0; i<y.length; i++){str = str.replace('.','');}//fin for
str = str.replace(',','.');
var y = redondear(str,2);

var str = z+'';
for(i=0; i<z.length; i++){str = str.replace('.','');}//fin for
str = str.replace(',','.');
var z = redondear(str,2);



 aa = eval(x) + eval(y) + eval(z);


document.getElementById('consulta_monto_Aumento').value=aa;
moneda('consulta_monto_Aumento');


 if((eval(aa) > eval(bb))){
		fun_msj('EL MONTO DE LA MODIFICACION NO PUEDE SER MAYOR AL MONTO ACTUAL');
		document.getElementById(input).value='';
		document.getElementById(input2).value='';
		document.getElementById(input3).value='';
		moneda(input);
		moneda(input2);
		moneda(input3);


		return;
 }else{
 	moneda(input);
 	moneda(input2);
 	moneda(input3);
 }







}else{


	input="disminucion"; opcion="Monto de la Disminucion";
 aa = document.getElementById(input).value;

var str = aa;
for(i=0; i<aa.length; i++){
    str = str.replace('.','');
}//fin for
str = str.replace(',','.');
var aa = redondear(str,2);


 if((eval(aa) > eval(bb))){
		fun_msj('EL MONTO DE LA MODIFICACION NO PUEDE SER MAYOR AL MONTO ACTUAL');
		document.getElementById(input).value='';
		moneda(input);
		document.getElementById(input).focus();
		return;
 }else{
 	moneda(input);
 }


}//fin else


var str =  total+'';for(x=0; x<str.length; x++){if(str.charAt(x)=="."){total=str.substr(0,eval(x)+eval(6));break;}} var total = redondear(total,2);

      if(total<=aa){ cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total);

}else if(total>aa){fun_msj('El total es mayor al '+opcion); cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total);}//fin else


}else{

     document.getElementById(id_a).value="";
     fun_msj('Selecione el tipo de modificaci&oacute;n');


    }//fin else

}//fin function



function cscp04_ordencompra_modificacion_validas(){

if(document.getElementById('ano_orden_compra_modificacion').value==''){

			fun_msj('Inserte el a&ntilde;o de orden de compra de modificaci&oacute;n');
			document.getElementById('ano_orden_compra_modificacion').focus();
			return false;

	}else if(document.getElementById('numero_orden_compra_modificacion').value==''){

			fun_msj('Inserte el n&uacute;mero de orden de compra');
			document.getElementById('numero_orden_compra_modificacion').focus();
			return false;

	}else if(document.getElementById('fecha_modificacion').value==''){

			fun_msj('Inserte la fecha de orden de compra de modificaci&oacute;n');
			document.getElementById('fecha_modificacion').focus();
			return false;

    }else if(document.getElementById('tipo_modificacion_1').checked!=false || document.getElementById('tipo_modificacion_2').checked!=false){


total = 0;
for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
 a = document.getElementById('modificacion_'+ii).value;
var str = a;
for(i=0; i<a.length; i++){
    str = str.replace('.','');
}//fin for
str = str.replace(',','.');
var a = redondear(str,2);
total =  eval(total) + eval(a);
}//fin for

if(document.getElementById('tipo_modificacion_1').checked!=false){input="aumento"; opcion="Monto del Aumento";}else{input="disminucion"; opcion="Monto de la Disminuci&oacute;n";}
 aa = document.getElementById(input).value;
var str = aa;
for(i=0; i<aa.length; i++){
    str = str.replace('.','');
}//fin for
str = str.replace(',','.');
var aa = redondear(str,2);

//if(aa=="0,00"){aa="0";}



var str =  total+'';for(x=0; x<str.length; x++){if(str.charAt(x)=="."){total=str.substr(0,eval(x)+eval(6));break;}} var total = redondear(total,2);

      if((total==0 && aa==0)){fun_msj('No a realizado ninguna modificaci&oacute;n'); cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total); return false;

}else if(total<aa){fun_msj('El total es menor al '+opcion); cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total); return false;

}else if(total>aa){fun_msj('El total es mayor al '+opcion); cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total); return false;

}else if(document.getElementById('observaciones').value==''){

			fun_msj('Inserte las observaci&oacute;nes de la modificaci&oacute;n');
			document.getElementById('observaciones').focus();
			return false;

}//fin else





	}else{

	        fun_msj('Selecione el tipo de modificaci&oacute;n');
			return false;

	}//fin else
}//fin function

