function condicion_show1(){
	if(document.getElementById('condicion_2').checked==true){
		document.getElementById('tipo_trans_1').disabled="";
		document.getElementById('tipo_trans_2').disabled="";
		document.getElementById('select_4').disabled="disabled";
	}else{
		document.getElementById('tipo_trans_1').disabled="disabled";
		document.getElementById('tipo_trans_2').disabled="disabled";
		document.getElementById('tipo_trans_1').checked=false;
		document.getElementById('tipo_trans_2').checked=false;
		document.getElementById('codi_trans1').value="";
		document.getElementById('denomi_trans1').value="";
		document.getElementById('select_4').value="";
		document.getElementById('select_4').disabled="disabled";
	}
}

function valida_escenario_escala_sueldo(){
		if(document.getElementById("desde_sueldo").value==''){
			fun_msj('INGRESE DESDE QUE sueldo o salario');
			document.getElementById('desde_sueldo').focus();
			return false;
		}else if(document.getElementById("hasta_sueldo").value==''){
			fun_msj('INGRESE HASTA QUE sueldo o salario');
			document.getElementById('hasta_sueldo').focus();
			return false;
		}else if(document.getElementById("monto").value==''){
			fun_msj('POR FAVOR INGRESE EL MONTO a deducir');
			document.getElementById('monto').focus();
			return false;
		}else if(eval(reemplazarPC(document.getElementById("hasta_sueldo").value)) < eval(reemplazarPC(document.getElementById("desde_sueldo").value))){
			document.getElementById("hasta_sueldo").value="";
			fun_msj('El sueldo hasta debe ser mayor al sueldo desde');
			document.getElementById('hasta_sueldo').focus();
			return false;
 		 }
}//valida_escenario_escala_sueldo

function valida_escenario_escala_sueldo1(){
		if(document.getElementById("desde_sueldo").value==''){
			fun_msj('INGRESE DESDE QUE sueldo o salario');
			document.getElementById('desde_sueldo').focus();
			return false;
		}else if(document.getElementById("hasta_sueldo").value==''){
			fun_msj('INGRESE HASTA QUE sueldo o salario');
			document.getElementById('hasta_sueldo').focus();
			return false;
		}else if(document.getElementById("monto").value==''){
			fun_msj('POR FAVOR INGRESE EL MONTO a asignar');
			document.getElementById('monto').focus();
			return false;
		}else if(eval(reemplazarPC(document.getElementById("hasta_sueldo").value)) < eval(reemplazarPC(document.getElementById("desde_sueldo").value))){
			document.getElementById("hasta_sueldo").value="";
			fun_msj('El sueldo hasta debe ser mayor al sueldo desde');
			document.getElementById('hasta_sueldo').focus();
			return false;
 		 }
}//valida_escenario_escala_sueldo



function valida_monto1_escala_sueldo(){
		if(document.getElementById("desde_sueldo").value==''){
			fun_msj('INGRESE DESDE QUE sueldo o salario');
			document.getElementById('desde_sueldo').focus();
			return false;
		}else if(document.getElementById("hasta_sueldo").value==''){
			fun_msj('INGRESE HASTA QUE sueldo o salario');
			document.getElementById('hasta_sueldo').focus();
			return false;
		}else if(document.getElementById("monto").value==''){
			fun_msj('POR FAVOR INGRESE EL MONTO a deducir');
			document.getElementById('monto').focus();
			return false;
		}else if(eval(reemplazarPC(document.getElementById("hasta_sueldo").value)) < eval(reemplazarPC(document.getElementById("desde_sueldo").value))){
			document.getElementById("hasta_sueldo").value="";
			fun_msj('El sueldo hasta debe ser mayor al sueldo desde');
			document.getElementById('hasta_sueldo').focus();
			return false;
 		 }
}//valida_escenario_escala_sueldo



function valida_menor_igual_sueldo1(){
		 if(eval(reemplazarPC(document.getElementById("hasta_sueldo").value)) < eval(reemplazarPC(document.getElementById("desde_sueldo").value))){
			document.getElementById("hasta_sueldo").value="";
			fun_msj('el sueldo ingresado debe ser mayor al sueldo de inicio');
			document.getElementById('hasta_sueldo').focus();
			return false;
 		 }//valida_menor_igual_sueldo
 }



function valida_ano_menor_igual(){
if(eval(document.getElementById("hasta_ano").value) < eval(document.getElementById("desde_ano").value)){
		fun_msj('el a&ntilde;o ingresado debe ser mayor al a&ntilde;o de inicio');
		document.getElementById("hasta_ano").value="";
		document.getElementById('hasta_ano').focus();
		return false;

}else if(eval(document.getElementById("hasta_ano").value)<=0){
		fun_msj('ingrese un a&ntilde;o valido');
		document.getElementById("hasta_ano").value="";
		document.getElementById('hasta_ano').focus();
		return false;

}
}//valida_ano_menor_igual

function valida_escala_ano_monto(){
//	alert("entra");
		 if(document.getElementById("desde_ano").value==''){
		fun_msj('POR FAVOR INGRESE DESDE QUE A&Ntilde;O DE SERVICIO');
		document.getElementById('desde_ano').focus();
		return false;
		}else if(document.getElementById("hasta_ano").value==''){
		fun_msj('POR FAVOR INGRESE HASTA QUE A&Ntilde;O DE SERVICIO');
		document.getElementById('hasta_ano').focus();
		return false;
		}else if(document.getElementById("monto").value==''){
		fun_msj('POR FAVOR INGRESE EL MONTO A DEDUCIR');
		document.getElementById('monto').focus();
		return false;
		}
}//valida_monto1


function valida_escala_ano_monto1(){
//	alert("entra");
		 if(document.getElementById("desde_ano").value==''){
		fun_msj('POR FAVOR INGRESE DESDE QUE A&Ntilde;O DE SERVICIO');
		document.getElementById('desde_ano').focus();
		return false;
		}else if(document.getElementById("hasta_ano").value==''){
		fun_msj('POR FAVOR INGRESE HASTA QUE A&Ntilde;O DE SERVICIO');
		document.getElementById('hasta_ano').focus();
		return false;
		}else if(document.getElementById("monto").value==''){
		fun_msj('POR FAVOR INGRESE EL MONTO A ASIGNAR');
		document.getElementById('monto').focus();
		return false;
		}
}//valida_monto1



function valida_escenario_escala_sueldo_porcentaje(){
		if(document.getElementById("desde_sueldo").value==''){
			fun_msj('INGRESE DESDE QUE sueldo o salario');
			document.getElementById('desde_sueldo').focus();
			return false;
		}else if(document.getElementById("hasta_sueldo").value==''){
			fun_msj('INGRESE HASTA QUE sueldo o salario');
			document.getElementById('hasta_sueldo').focus();
			return false;
		}else if(document.getElementById("monto").value==''){
			fun_msj('POR FAVOR INGRESE EL PORCENTAJE a deducir');
			document.getElementById('monto').focus();
			return false;
		}else if(eval(reemplazarPC(document.getElementById("hasta_sueldo").value)) < eval(reemplazarPC(document.getElementById("desde_sueldo").value))){
			document.getElementById("hasta_sueldo").value="";
			fun_msj('El sueldo hasta debe ser mayor al sueldo desde');
			document.getElementById('hasta_sueldo').focus();
			return false;
 		 }
}//valida_escenario_escala_sueldo

function valida_escenario_escala_sueldo_porcentaje1(){
		if(document.getElementById("desde_sueldo").value==''){
			fun_msj('INGRESE DESDE QUE sueldo o salario');
			document.getElementById('desde_sueldo').focus();
			return false;
		}else if(document.getElementById("hasta_sueldo").value==''){
			fun_msj('INGRESE HASTA QUE sueldo o salario');
			document.getElementById('hasta_sueldo').focus();
			return false;
		}else if(document.getElementById("monto").value==''){
			fun_msj('POR FAVOR INGRESE EL PORCENTAJE a asignar');
			document.getElementById('monto').focus();
			return false;
		}else if(eval(reemplazarPC(document.getElementById("hasta_sueldo").value)) < eval(reemplazarPC(document.getElementById("desde_sueldo").value))){
			document.getElementById("hasta_sueldo").value="";
			fun_msj('El sueldo hasta debe ser mayor al sueldo desde');
			document.getElementById('hasta_sueldo').focus();
			return false;
 		 }
}//valida_escenario_escala_sueldo


function valida_escala_ano_porcentaje(){
//	alert("entra");
		 if(document.getElementById("desde_ano").value==''){
		fun_msj('POR FAVOR INGRESE DESDE QUE A&Ntilde;O DE SERVICIO');
		document.getElementById('desde_ano').focus();
		return false;
		}else if(document.getElementById("hasta_ano").value==''){
		fun_msj('POR FAVOR INGRESE HASTA QUE A&Ntilde;O DE SERVICIO');
		document.getElementById('hasta_ano').focus();
		return false;
		}else if(document.getElementById("monto").value==''){
		fun_msj('POR FAVOR INGRESE EL PORCENTAJE A DEDUCIR');
		document.getElementById('monto').focus();
		return false;
		}
}//valida_monto1

function valida_escala_ano_porcentaje1(){
//	alert("entra");
		 if(document.getElementById("desde_ano").value==''){
		fun_msj('POR FAVOR INGRESE DESDE QUE A&Ntilde;O DE SERVICIO');
		document.getElementById('desde_ano').focus();
		return false;
		}else if(document.getElementById("hasta_ano").value==''){
		fun_msj('POR FAVOR INGRESE HASTA QUE A&Ntilde;O DE SERVICIO');
		document.getElementById('hasta_ano').focus();
		return false;
		}else if(document.getElementById("monto").value==''){
		fun_msj('POR FAVOR INGRESE EL PORCENTAJE A ASIGNAR');
		document.getElementById('monto').focus();
		return false;
		}
}//valida_monto1



function valida_escala_ano_dias(){
//	alert("entra");
		 if(document.getElementById("desde_ano").value==''){
		fun_msj('POR FAVOR INGRESE DESDE QUE A&Ntilde;O DE SERVICIO');
		document.getElementById('desde_ano').focus();
		return false;
		}else if(document.getElementById("hasta_ano").value==''){
		fun_msj('POR FAVOR INGRESE HASTA QUE A&Ntilde;O DE SERVICIO');
		document.getElementById('hasta_ano').focus();
		return false;
		}else if(document.getElementById("monto").value==''){
		fun_msj('POR FAVOR INGRESE LOS DIAS A DEDUCIR');
		document.getElementById('monto').focus();
		return false;
		}
}//valida_monto1


function valida_escala_ano_dias1(){
//	alert("entra");
		 if(document.getElementById("desde_ano").value==''){
		fun_msj('POR FAVOR INGRESE DESDE QUE A&Ntilde;O DE SERVICIO');
		document.getElementById('desde_ano').focus();
		return false;
		}else if(document.getElementById("hasta_ano").value==''){
		fun_msj('POR FAVOR INGRESE HASTA QUE A&Ntilde;O DE SERVICIO');
		document.getElementById('hasta_ano').focus();
		return false;
		}else if(document.getElementById("monto").value==''){
		fun_msj('POR FAVOR INGRESE LOS DIAS A ASIGNAR');
		document.getElementById('monto').focus();
		return false;
		}
}//valida_monto1



function formato_cantidades2(id,precision,mensaje){
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
		//moneda(id);
	}else{
		if(mensaje){
			fun_msj(mensaje);
		}
		document.getElementById(id).value='';
		document.getElementById(id).focus();
		return false;
	}


}



function cnmp10_escala_sueldo_puesto_deduccion(){
//	alert("entra");
		if(document.getElementById("cod_puesto").value==''){
			fun_msj('debe ingresar el puesto');
			return false;
		}else if(document.getElementById("monto").value==''){
			fun_msj('debe ingresar el monto a deducir');
			document.getElementById('monto').focus();
			return false;
		}
}//cnmp10_escala_sueldo_puesto_deduccion


function cnmp10_escala_porcentaje_puesto_deduccion(){
		if(document.getElementById("cod_puesto").value==''){
			fun_msj('debe ingresar el puesto');
			return false;
		}else if(document.getElementById("monto").value==''){
			fun_msj('debe ingresar el porcentaje a deducir');
			document.getElementById('monto').focus();
			return false;
		}
}//cnmp10_escala_porcentaje_puesto_deduccion


function cnmp10_escala_porcentaje_puesto_asignacion(){
		if(document.getElementById("cod_puesto").value==''){
			fun_msj('debe ingresar el puesto');
			return false;
		}else if(document.getElementById("monto").value==''){
			fun_msj('debe ingresar el porcentaje a asignar');
			document.getElementById('monto').focus();
			return false;
		}
}//cnmp10_escala_porcentaje_puesto_asignacion


function cnmp10_escala_sueldo_puesto_asignacion(){
		if(document.getElementById("cod_puesto").value==''){
			fun_msj('debe ingresar el puesto');
			return false;
		}else if(document.getElementById("monto").value==''){
			fun_msj('debe ingresar el monto a asignar');
			document.getElementById('monto').focus();
			return false;
		}
}//cnmp10_escala_sueldo_puesto_asignacion


function cnmp10_escala_individual_dias(){
		if(document.getElementById("cod_cargo").value==''){
			fun_msj('debe buscar y seleccionar un trabajador');
			return false;
		}else if(document.getElementById("cantidad").value==''){
			fun_msj('debe ingresar la cantidad de dias');
			document.getElementById('cantidad').focus();
			return false;
		}

}//fin cnmp10_escala_individual_dias

function cnmp10_escala_individual_porcentaje(){
		if(document.getElementById("cod_cargo").value==''){
			fun_msj('debe buscar y seleccionar un trabajador');
			return false;
		}else if(document.getElementById("cantidad").value==''){
			fun_msj('debe ingresar la cantidad de horas');
			document.getElementById('cantidad').focus();
			return false;
		}

}//fin cnmp10_escala_individual_porcentaje


function cnmp10_escala_individual_porcentaje2(){
		if(document.getElementById("cod_cargo").value==''){
			fun_msj('debe buscar y seleccionar un trabajador');
			return false;
		}else if(document.getElementById("cantidad").value==''){
			fun_msj('debe ingresar la cantidad');
			document.getElementById('cantidad').focus();
			return false;
		}

}//fin cnmp10_escala_individual_porcentaje


function cnmp10_escala_individual_bolivares(){
		if(document.getElementById("cod_cargo").value==''){
			fun_msj('debe buscar y seleccionar un trabajador');
			return false;
		}else if(document.getElementById("cantidad").value==''){
			fun_msj('debe ingresar una cantidad');
			document.getElementById('cantidad').focus();
			return false;
		}

}//fin cnmp10_escala_individual_bolivares


function cnmp10_escala_individual_porcentaje2_deduccion(){
		if(document.getElementById("cod_cargo").value==''){
			fun_msj('debe buscar y seleccionar un trabajador');
			return false;
		}else if(document.getElementById("cantidad").value==''){
			fun_msj('debe ingresar la cantidad');
			document.getElementById('cantidad').focus();
			return false;
		}

}//fin cnmp10_escala_individual_porcentaje2_deduccion



function cnmp10_escala_individual_bolivares_deduccion(){
		if(document.getElementById("cod_cargo").value==''){
			fun_msj('debe buscar y seleccionar un trabajador');
			return false;
		}else if(document.getElementById("cantidad").value==''){
			fun_msj('debe ingresar una cantidad');
			document.getElementById('cantidad').focus();
			return false;
		}

}//fin cnmp10_escala_individual_bolivares



function cnmp10_escala_individual_cantidad(){
		if(document.getElementById("cod_cargo").value==''){
			fun_msj('debe buscar y seleccionar un trabajador');
			return false;
		}else if(document.getElementById("cantidad").value==''){
			fun_msj('debe ingresar la cantidad de horas');
			document.getElementById('cantidad').focus();
			return false;
		}

}//fin cnmp10_escala_individual_porcentaje


function cnmp10_activa_radios(){


}



////////////////////////////////////////////////////////////////////////////////////////////////////////////



