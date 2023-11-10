function valida_monto1_segun_puesto_vacio(){
		if(document.getElementById("transaccion").value==''){
		fun_msj('Debes Seleccionar el c&oacute;digo de la transacci&oacute;n');
		document.getElementById('transaccion').focus();
		return false;
		}else if(document.getElementById("cod_puesto").value==''){
		fun_msj('Debes Seleccionar el c&oacute;digo del puesto');
		document.getElementById('cod_puesto').focus();
		return false;
		}else if(document.getElementById("monto").value==''){
		fun_msj('Debe ingresar un monto');
		document.getElementById('monto').focus();
		return false;
		}
}//fin valida_monto1_segun_puesto_vacio

function valida_porcentaje1_segun_puesto_vacio(){
		if(document.getElementById("transaccion").value==''){
		fun_msj('Debes Seleccionar el c&oacute;digo de la transacci&oacute;n');
		document.getElementById('transaccion').focus();
		return false;
		}else if(document.getElementById("cod_puesto").value==''){
		fun_msj('Debes Seleccionar el c&oacute;digo del puesto');
		document.getElementById('cod_puesto').focus();
		return false;
		}else if(document.getElementById("monto").value==''){
		fun_msj('Debe ingresar un porcentaje');
		document.getElementById('monto').focus();
		return false;
		}
}//fin valida_porcentaje1_segun_puesto_vacio

function valida_porcentaje1_escala_sueldo(){
//	alert("entra");
		 if(document.getElementById("desde_sueldo").value==''){
		fun_msj('POR FAVOR INGRESE DESDE QUE sueldo o salario');
		document.getElementById('desde_sueldo').focus();
		return false;
		}
		else if(document.getElementById("hasta_sueldo").value==''){
		fun_msj('POR FAVOR INGRESE HASTA QUE sueldo o salario');
		document.getElementById('hasta_sueldo').focus();
		return false;
		}else if(document.getElementById("monto").value==''){
		fun_msj('POR FAVOR INGRESE EL porcentaje asignar');
		document.getElementById('monto').focus();
		return false;
		}else  if(eval(reemplazarPC(document.getElementById("hasta_sueldo").value)) < eval(reemplazarPC(document.getElementById("desde_sueldo").value))){
			document.getElementById("hasta_sueldo").value="";
			fun_msj('el monto hasta sueldo debe ser mayor al monto de inicio');
			document.getElementById('hasta_sueldo').focus();
			return false;
 		 }//valida_menor_igual_sueldo
}//valida_porcentaje1_escala_sueldo

function show_save_transferir(){
    document.getElementById('save_transferir').disabled="";
}

function hide_save_transferir(){
    document.getElementById('save_transferir').disabled="disabled";
}


function cnmp10_valida_montof_montom(){
		if(document.getElementById("monto").value=='' || document.getElementById("monto2").value==''){
			fun_msj('Asegurese de ingresar un monto femenino y un monto masculino');
			document.getElementById('monto').focus();
			return false;
		}

}//fin cnmp10_valida_montof_montom


function cnmp10_valida_porcentajef_porcentajem(){
		if(document.getElementById("monto").value=='' || document.getElementById("monto2").value==''){
			fun_msj('Asegurese de ingresar un porcentaje femenino y un porcentaje masculino');
			document.getElementById('monto').focus();
			return false;
		}

}//fin cnmp10_valida_montof_montom



function valida_menor_igual_mes1_dias(){
	if(eval(document.getElementById("desde_mes").value) > eval(12)){
			fun_msj('ingrese un mes de inicio valido');
			document.getElementById("desde_mes").value='';
			document.getElementById('desde_mes').focus();
			return false;
	}else if(eval(document.getElementById("hasta_mes").value) > eval(12)){
			fun_msj('ingrese un mes de culminaci&oacute;n valido');
			document.getElementById("hasta_mes").value='';
			document.getElementById('hasta_mes').focus();
			return false;
	}else if(eval(document.getElementById("desde_dia").value) > eval(31)){
			fun_msj('ingrese un dia de inicio valido');
			document.getElementById("desde_dia").value='';
			document.getElementById('desde_dia').focus();
			return false;
	}else if(eval(document.getElementById("hasta_dia").value) > eval(31)){
			fun_msj('ingrese un dia de culminaci&oacute;n valido');
			document.getElementById("hasta_dia").value='';
			document.getElementById('hasta_dia').focus();
			return false;
	}else if(eval(document.getElementById("hasta_mes").value) < eval(document.getElementById("desde_mes").value)){
			fun_msj('El mes de culminaci&oacute;n debe ser mayor al mes de inicio');
			document.getElementById("desde_mes").value='';
			document.getElementById("hasta_mes").value='';
			document.getElementById('desde_mes').focus();
			return false;
		}else if(eval(document.getElementById("hasta_dia").value) < eval(document.getElementById("desde_dia").value)){
			fun_msj('El dia de culminaci&oacute;n debe ser mayor al dia de inicio');
			document.getElementById("desde_dia").value='';
			document.getElementById("hasta_dia").value='';
			document.getElementById('desde_dia').focus();
			return false;
		}
}// fin valida_menor_igual_mes1



function valida_cnmp10_escala_mes_dia_vacio(){
		if(document.getElementById("desde_mes").value==''){
			fun_msj('Debe ingresar un mes de inicio');
			document.getElementById('desde_mes').focus();
			return false;
		}else if(document.getElementById("hasta_mes").value==''){
			fun_msj('Debe ingresar un mes de culminaci&oacute;n');
			document.getElementById('hasta_mes').focus();
			return false;
		}else if(document.getElementById("desde_dia").value==''){
			fun_msj('Debe ingresar un dia de inicio');
			document.getElementById('desde_dia').focus();
			return false;
		}else if(document.getElementById("hasta_dia").value==''){
			fun_msj('Debe ingresar un dia de culminaci&oacute;n');
			document.getElementById('hasta_dia').focus();
			return false;
		}else if(document.getElementById("monto").value==''){
			fun_msj('Debe ingresar la cantidad de dias');
			document.getElementById('monto').focus();
			return false;
		}else if(eval(document.getElementById("desde_mes").value) > eval(12)){
			fun_msj('Debe ingresar un mes de inicio valido');
			document.getElementById('desde_mes').focus();
			return false;
		}else if(eval(document.getElementById("hasta_mes").value) > eval(12)){
			fun_msj('Debe ingresar un mes de culminaci&oacute;n valido');
			document.getElementById('hasta_mes').focus();
			return false;
		}else if(eval(document.getElementById("desde_dia").value) > eval(31)){
			fun_msj('Debe ingresar un dia de inicio valido');
			document.getElementById('desde_dia').focus();
			return false;
		}else if(eval(document.getElementById("hasta_dia").value) > eval(31)){
			fun_msj('Debe ingresar un dia de culminaci&oacute;n valido');
			document.getElementById('hasta_dia').focus();
			return false;
		}
}// fin valida_cnmp10_escala_mes_dia_vacio


function condicion_show_escenarios_2(){
	if(document.getElementById('condicion_2').checked==true){
		document.getElementById('tipo_trans_1').disabled="";
		document.getElementById('tipo_trans_2').disabled="";
		document.getElementById('select_4').disabled="disabled";
		document.getElementById('pedro').disabled="";
	}else{
		document.getElementById('tipo_trans_1').disabled="disabled";
		document.getElementById('tipo_trans_2').disabled="disabled";
		document.getElementById('tipo_trans_1').checked=false;
		document.getElementById('tipo_trans_2').checked=false;
		document.getElementById('codi_trans1').value="";
		document.getElementById('denomi_trans1').value="";
		document.getElementById('select_4').disabled="disabled";
		document.getElementById('pedro').disabled="disabled";
	}
}


function cnmp10_valida_porcentaje_cantidad(){

	if(document.getElementById("cod_nomina").value==''){
		fun_msj('Debes Seleccionar el c&oacute;digo de n&oacute;mina');
		document.getElementById('cod_nomina').focus();
		return false;
	}else if(document.getElementById("transaccion").value==''){
		fun_msj('Debes Seleccionar el c&oacute;digo de la transacci&oacute;n');
		document.getElementById('transaccion').focus();
		return false;
	}else if(document.getElementById("cedula").value==''){
		fun_msj('Debes Seleccionar un c&oacute;digo de cargo y un c&oacute;digo de ficha');
		document.getElementById('cedula').focus();
		return false;
	}else if(document.getElementById("porcentaje").value==''){
			fun_msj('Debe ingresar un porcentaje');
			document.getElementById('porcentaje').focus();
			return false;
	}else if(document.getElementById("cantidad").value==''){
			fun_msj('Debe ingresar una cantidad');
			document.getElementById('cantidad').focus();
			return false;
	}
}// cnmp10_valida_porcentaje_cantidad


function cnmp10_valida_monto_cantidad(){

	if(document.getElementById("cod_nomina").value==''){
		fun_msj('Debes Seleccionar el c&oacute;digo de n&oacute;mina');
		document.getElementById('cod_nomina').focus();
		return false;
	}else if(document.getElementById("transaccion").value==''){
		fun_msj('Debes Seleccionar el c&oacute;digo de la transacci&oacute;n');
		document.getElementById('transaccion').focus();
		return false;
	}else if(document.getElementById("cedula").value==''){
		fun_msj('Debes Seleccionar un c&oacute;digo de cargo y un c&oacute;digo de ficha');
		document.getElementById('cedula').focus();
		return false;
	}else if(document.getElementById("porcentaje").value==''){
			fun_msj('Debe ingresar un monto');
			document.getElementById('porcentaje').focus();
			return false;
	}else if(document.getElementById("monto_tope") && document.getElementById("monto_tope").value==''){
			fun_msj('Debe ingresar un monto tope');
			document.getElementById('monto_tope').focus();
			return false;

	}else if(document.getElementById("cantidad").value==''){
			fun_msj('Debe ingresar una cantidad');
			document.getElementById('cantidad').focus();
			return false;
	}


}// cnmp10_valida_porcentaje_cantidad




function cnmp10_valida_individual_dias(){

	if(document.getElementById("cod_nomina").value==''){
		fun_msj('Debes Seleccionar el c&oacute;digo de n&oacute;mina');
		document.getElementById('cod_nomina').focus();
		return false;
	}else if(document.getElementById("transaccion").value==''){
		fun_msj('Debes Seleccionar el c&oacute;digo de la transacci&oacute;n');
		document.getElementById('transaccion').focus();
		return false;
	}else if(document.getElementById("cedula").value==''){
		fun_msj('Debes Seleccionar un c&oacute;digo de cargo y un c&oacute;digo de ficha');
		document.getElementById('cedula').focus();
		return false;
	}else if(document.getElementById("cantidad").value==''){
			fun_msj('Debe ingresar una cantidad');
			document.getElementById('cantidad').focus();
			return false;
	}
}// cnmp10_valida_porcentaje_cantidad

function cnmp10_valida_grilla_cantidad(){

	if(document.getElementById("cod_nomina").value==''){
		fun_msj('Debes Seleccionar el c&oacute;digo de n&oacute;mina');
		document.getElementById('cod_nomina').focus();
		return false;
	}else if(document.getElementById("transaccion").value==''){
		fun_msj('Debes Seleccionar el c&oacute;digo de la transacci&oacute;n');
		document.getElementById('transaccion').focus();
		return false;
	}else if(document.getElementById("cedula").value==''){
		fun_msj('Debes Seleccionar un c&oacute;digo de cargo y un c&oacute;digo de ficha');
		document.getElementById('cedula').focus();
		return false;
	}else if(document.getElementById("cantidad").value==''){
			fun_msj('Debe ingresar una cantidad');
			document.getElementById('cantidad').focus();
			return false;
	}
}// cnmp10_valida_porcentaje_cantidad



function cnmp09_nomina_cancela_banco_valida(){
	if(document.getElementById("select_1").value==''){
		fun_msj('debe seleccionar el c&oacute;digo de n&oacute;mina');
		document.getElementById('select_1').focus();
		return false;
	}else if(document.getElementById("select_2").value==''){
		fun_msj('debe seleccionar el banco');
		document.getElementById('select_2').focus();
		return false;
	}else if(document.getElementById("select_3").value==''){
		fun_msj('debe seleccionar la sucursal');
		document.getElementById('select_3').focus();
		return false;
	}else if(document.getElementById("select_4").value==''){
		fun_msj('debe seleccionar la cuenta');
		document.getElementById('select_4').focus();
		return false;
	}else if(document.getElementById("rif").value==''){
		fun_msj('debe ingresar el rif o c&eacute;dula');
		document.getElementById('rif').focus();
		return false;
	}else if(document.getElementById("beneficiario").value==''){
		fun_msj('debe ingresar el beneficiario del neto a cobrar');
		document.getElementById('beneficiario').focus();
		return false;
	}
}// fin cnmp09_nomina_cancela_banco_valida



function cnmp09_nomina_cancela_banco_valida_modifica(){
	if(document.getElementById("select_1").value==''){
		fun_msj('debe seleccionar el c&oacute;digo de n&oacute;mina');
		document.getElementById('select_1').focus();
		return false;
	}else if(document.getElementById("beneficiario").value==''){
		fun_msj('debe ingresar el beneficiario del neto a cobrar');
		document.getElementById('beneficiario').focus();
		return false;
	}else if(document.getElementById("rif").value==''){
		fun_msj('debe ingresar el rif o c&eacute;dula');
		document.getElementById('rif').focus();
		return false;
	}
}// fin cnmp09_nomina_cancela_banco_valida



function cnmp09_nomina_cancela_fondos_terceros(){
	if(document.getElementById("select_1").value==''){
		fun_msj('seleccione el c&oacute;digo de n&oacute;mina');
		document.getElementById('select_1').focus();
		return false;
	}else if(document.getElementById("select_2").value==''){
		fun_msj('seleccione el c&oacute;digo de transacci&oacute;n');
		document.getElementById('select_2').focus();
		return false;
	}else if(document.getElementById("select_3").value==''){
		fun_msj('seleccione el banco');
		document.getElementById('select_3').focus();
		return false;
	}else if(document.getElementById("select_4").value==''){
		fun_msj('seleccione la sucursal');
		document.getElementById('select_4').focus();
		return false;
	}else if(document.getElementById("select_5").value==''){
		fun_msj('seleccione la cuenta');
		document.getElementById('select_5').focus();
		return false;
	}else if(document.getElementById("persona_1").checked==false && document.getElementById("persona_2").checked==false){
		fun_msj('seleccione si la persona es natural o juridica');
		return false;
	}else if(document.getElementById("rif").value==''){
		fun_msj('debe ingresar el rif o c&eacute;dula');
		document.getElementById('rif').focus();
		return false;
	}else if(document.getElementById("beneficiario").value==''){
		fun_msj('debe ingresar el beneficiario');
		document.getElementById('beneficiario').focus();
		return false;
	}else if(document.getElementById("cedula_autorizado").value!='' && document.getElementById("autorizado_cobrar").value==''){
		fun_msj('debe ingresar el autorizado a cobrar');
		document.getElementById('autorizado_cobrar').focus();
		return false;
	}else if(document.getElementById("cedula_autorizado").value=='' && document.getElementById("autorizado_cobrar").value!=''){
		fun_msj('debe ingresar la cedula de identidad del autorizado');
		document.getElementById('cedula_autorizado').focus();
		return false;
	}else if(document.getElementById("sselect_1").value!='' && document.getElementById("cod_sucursal_autor").value==''){
		fun_msj('debe seleccionar la sucursal bancaria del autorizado a cobrar');
		document.getElementById('cod_sucursal_autor').focus();
		return false;
	}else if(document.getElementById("sselect_1").value!='' && document.getElementById("scod_cuenta_autor").value.length < 12){
		fun_msj('El n&uacute;mero de la cuenta bancaria del autorizado a cobrar es incorrecto');
		document.getElementById('scod_cuenta_autor').focus();
		return false;
	}
}// fin cnmp09_nomina_cancela_fondos_terceros

function cnmp09_nomina_cancela_fondos_terceros_1(){
	if(document.getElementById("beneficiario").value==''){
		fun_msj('debe ingresar el beneficiario');
		document.getElementById('beneficiario').focus();
		return false;
	}else if(document.getElementById("rif").value==''){
		fun_msj('debe ingresar el rif o c&eacute;dula');
		document.getElementById('rif').focus();
		return false;
	}
}// fin cnmp09_nomina_cancela_fondos_terceros





function solo_cuatro_cinco(e){

	tecla_codigo = (document.all) ? e.keyCode : e.which;
	if(tecla_codigo==8 || tecla_codigo==0 || tecla_codigo==13)return true;
	patron =/[4-5\-]/;
	tecla_valor = String.fromCharCode(tecla_codigo);
	return patron.test(tecla_valor);

}//fin solo_cuatro_cinco



function cnmp09_valida_lunes_vigente(e){
	if(document.getElementById("enero").value==''){
		fun_msj('debe ingresar la cantidad de lunes para enero');
		document.getElementById('enero').focus();
		return false;
	}else if(document.getElementById("febrero").value==''){
		fun_msj('debe ingresar la cantidad de lunes para febrero');
		document.getElementById('febrero').focus();
		return false;
	}else if(document.getElementById("marzo").value==''){
		fun_msj('debe ingresar la cantidad de lunes para marzo');
		document.getElementById('marzo').focus();
		return false;
	}else if(document.getElementById("abril").value==''){
		fun_msj('debe ingresar la cantidad de lunes para abril');
		document.getElementById('abril').focus();
		return false;
	}else if(document.getElementById("mayo").value==''){
		fun_msj('debe ingresar la cantidad de lunes para mayo');
		document.getElementById('mayo').focus();
		return false;
	}else if(document.getElementById("junio").value==''){
		fun_msj('debe ingresar la cantidad de lunes para junio');
		document.getElementById('junio').focus();
		return false;
	}else if(document.getElementById("julio").value==''){
		fun_msj('debe ingresar la cantidad de lunes para julio');
		document.getElementById('julio').focus();
		return false;
	}else if(document.getElementById("agosto").value==''){
		fun_msj('debe ingresar la cantidad de lunes para agosto');
		document.getElementById('agosto').focus();
		return false;
	}else if(document.getElementById("septiembre").value==''){
		fun_msj('debe ingresar la cantidad de lunes para septiembre');
		document.getElementById('septiembre').focus();
		return false;
	}else if(document.getElementById("octubre").value==''){
		fun_msj('debe ingresar la cantidad de lunes para octubre');
		document.getElementById('octubre').focus();
		return false;
	}else if(document.getElementById("noviembre").value==''){
		fun_msj('debe ingresar la cantidad de lunes para noviembre');
		document.getElementById('noviembre').focus();
		return false;
	}else if(document.getElementById("diciembre").value==''){
		fun_msj('debe ingresar la cantidad de lunes para diciembre');
		document.getElementById('diciembre').focus();
		return false;
	}
}// fin cnmp09_valida_lunes_vigente



function valida_porcentaje_asig_sueldo(){
	if(document.getElementById("nomina_1").value==''){
		fun_msj('seleccione el c&oacute;digo de n&oacute;mina');
		document.getElementById('nomina_1').focus();
		return false;
	}else if(document.getElementById("select_2").value==''){
		fun_msj('seleccione el c&oacute;digo de Transacci&oacute;n');
		document.getElementById('select_2').focus();
		return false;
	}else if(document.getElementById("monto").value==''){
		fun_msj('debe ingresar un porcentaje');
		document.getElementById('monto').focus();
		return false;
	}else if(document.getElementById("tope").value==''){
		fun_msj('debe ingresar un monto tope');
		document.getElementById('tope').focus();
		return false;
	}
}//fin valida_porcentaje_asig_sueldo


function valida_porcentaje_asig_sueldo_2(){
	 if(document.getElementById("monto").value==''){
		fun_msj('debe ingresar un porcentaje');
		document.getElementById('monto').focus();
		return false;
	}else if(document.getElementById("tope").value==''){
		fun_msj('debe ingresar un monto tope');
		document.getElementById('tope').focus();
		return false;
	}

}//fin valida_porcentaje_asig_sueldo_2



function valida_cierre_presupuestario(){
	if(document.getElementById("select_1").value==''){
		fun_msj('seleccione el c&oacute;digo de la dependencia');
		document.getElementById('select_1').focus();
		return false;
	}else if(document.getElementById("mes_1").value==''){
		fun_msj('seleccione el mes');
		document.getElementById('mes_1').focus();
		return false;
	}else if(document.getElementById("denominacion").value==''){
		fun_msj('REGISTRE EL RESPONSABLE DEL CIERRE');
		document.getElementById('denominacion').focus();
		return false;
	}

}

function valida_cierre_presupuestario_2(){
n=eval(document.getElementById("mmod").value);
	 if(document.getElementById("mes_2"+n).value==''){
		fun_msj('seleccione el mes');
		document.getElementById('mes_2'+n).focus();
		return false;
	}else if(document.getElementById("denominacion"+n).value==''){
		fun_msj('REGISTRE EL RESPONSABLE DEL CIERRE');
		document.getElementById('denominacion'+n).focus();
		return false;
	}

}


function valida_porcentaje_asig1(){
	if(document.getElementById("desde_ano").value==''){
		fun_msj('POR FAVOR INGRESE DESDE QUE A&Ntilde;O DE SERVICIO');
		document.getElementById('desde_ano').focus();
		return false;
	}else if(document.getElementById("hasta_ano").value==''){
		fun_msj('POR FAVOR INGRESE HASTA QUE A&Ntilde;O DE SERVICIO');
		document.getElementById('hasta_ano').focus();
		return false;
	}else if(document.getElementById("monto").value==''){
		fun_msj('POR FAVOR INGRESE EL porcentaje asignar');
		document.getElementById('monto').focus();
		return false;
	}
}//valida_porcentaje_asig1

function valida_dias_asig1(){
	if(document.getElementById("desde_ano").value==''){
		fun_msj('POR FAVOR INGRESE DESDE QUE A&Ntilde;O');
		document.getElementById('desde_ano').focus();
		return false;
	}else if(document.getElementById("hasta_ano").value==''){
		fun_msj('POR FAVOR INGRESE HASTA QUE A&Ntilde;O');
		document.getElementById('hasta_ano').focus();
		return false;
	}else if(document.getElementById("monto").value==''){
		fun_msj('POR FAVOR INGRESE los dias asignar');
		document.getElementById('monto').focus();
		return false;
	}
}//valida_dias_asig1



function deduccion_conecta_asignacion(){
		if(document.getElementById('select_1').value==''){
            fun_msj('seleccione el c&oacute;digo de n&oacute;mina');
			document.getElementById('select_1').focus();
			return false;
		}else if(document.getElementById('select_2').value==''){
            fun_msj('seleccione el c&oacute;digo de deducci&oacute;n');
			document.getElementById('select_1').focus();
			return false;
		}else if(document.getElementById('select_3').value==''){
            fun_msj('seleccione el c&oacute;digo de asignaci&oacute;n');
			document.getElementById('select_1').focus();
			return false;
		}

}




function bt_plus_show(){
    document.getElementById('plus').disabled="";
}

function bt_plus_hide2(){
    document.getElementById('plus').disabled="disabled";
}

function bt_plus_hide(){
    document.getElementById('plus').disabled="disabled";
}

function bt_monto_show(){
    document.getElementById('monto').disabled="";
    document.getElementById('monto2').disabled="";
}
function bt_monto_show_2(){
    document.getElementById('monto').disabled="";
}
function show_save(){
    document.getElementById('save').disabled="";
}

function hide_save(){
    document.getElementById('save').disabled="disabled";
}

function show_guardar(){
    document.getElementById('save').disabled="";
}

function hide_guardar(){
    document.getElementById('save').disabled="disabled";
}

function escenario_show1(){
	if(document.getElementById('frecuencia_2').checked==true){
		document.getElementById('escenario_1').disabled="";
		document.getElementById('escenario_2').disabled="";
	}else{
		document.getElementById('escenario_1').disabled="disabled";
		document.getElementById('escenario_2').disabled="disabled";
	}
}

function escenario_show2(){
	if(document.getElementById('frecuencia_2').checked==true){
		document.getElementById('escenario_1').disabled="disabled";
		document.getElementById('escenario_2').disabled="disabled";
	}else{
		document.getElementById('escenario_1').disabled="disabled";
		document.getElementById('escenario_2').disabled="disabled";
	}
}

function escenario_show(){
	if(document.getElementById('frecuencia_2').checked==true){
		document.getElementById('escenario_1').disabled="";
		document.getElementById('escenario_2').disabled="";
		document.getElementById('escenario_1').checked=true;
	}else{
		document.getElementById('escenario_1').disabled="disabled";
		document.getElementById('escenario_2').disabled="disabled";
		document.getElementById('escenario_2').checked=true;
	}
}

function condicion_show(){
	if(document.getElementById('condicion_2').checked==true){
		document.getElementById('tipo_trans_1').disabled="";
		//document.getElementById('tipo_trans_1').checked=true;
		document.getElementById('tipo_trans_2').disabled="";
		document.getElementById('select_4').disabled="disabled";
		//document.getElementById('select_4').disabled="";
	}else{
		document.getElementById('tipo_trans_1').disabled="disabled";
		document.getElementById('tipo_trans_2').disabled="disabled";
		document.getElementById('tipo_trans_1').checked=false;
		document.getElementById('tipo_trans_2').checked=false;
		document.getElementById('codi_trans1').value="";
		document.getElementById('denomi_trans1').value="";
		document.getElementById('select_4').disabled="disabled";
	}
}

function bloquea_botones(){
	document.getElementById('frecuencia_1').disabled="disabled";
	document.getElementById('frecuencia_2').disabled="disabled";
	document.getElementById('condicion_1').disabled="disabled";
	document.getElementById('condicion_2').disabled="disabled";
	document.getElementById('escenario_1').disabled="disabled";
	document.getElementById('escenario_2').disabled="disabled";
	document.getElementById('tipo_trans_1').disabled="disabled";
	document.getElementById('tipo_trans_2').disabled="disabled";
	document.getElementById('select_4').disabled="disabled";
}

function desbloquea_botones(){
	document.getElementById('frecuencia_1').disabled="";
	document.getElementById('frecuencia_2').disabled="";
	document.getElementById('condicion_1').disabled="";
	document.getElementById('condicion_2').disabled="";
}

function desbloquea_botones1(){
	document.getElementById('condicion_1').disabled="";
	document.getElementById('condicion_2').disabled="";
}

function bt_monto_hide(){
    document.getElementById('monto').disabled="disabled";
    document.getElementById('monto').value="";
    document.getElementById('monto2').disabled="disabled";
    document.getElementById('monto2').value="";
}




///////////////////////////ERICK LAS HIZO///////////////////////////////////////////



	function valida_monto1(){
		 if(document.getElementById("desde_ano").value==''){
		fun_msj('POR FAVOR INGRESE DESDE QUE A&Ntilde;O DE SERVICIO');
		document.getElementById('monto').focus();
		return false;
		}else if(document.getElementById("hasta_ano").value==''){
		fun_msj('POR FAVOR INGRESE HASTA QUE A&Ntilde;O DE SERVICIO');
		document.getElementById('monto').focus();
		return false;
		}else if(document.getElementById("monto").value==''){
		fun_msj('POR FAVOR INGRESE EL MONTO asignar');
		document.getElementById('monto').focus();
		return false;
		}
}//valida_monto1



function valida_monto12(){
	if(document.getElementById("monto").value==''){
		fun_msj('POR FAVOR INGRESE EL MONTO asignar');
		document.getElementById('monto').focus();
		return false;
		}

}//valida_monto1


function valida_monto21(){
	if(document.getElementById("monto").value==''){
		fun_msj('POR FAVOR INGRESE EL MONTO a deducir');
		document.getElementById('monto').focus();
		return false;
		}

}//valida_monto1


function valida_monto125(){
	if(document.getElementById("monto").value==''){
		fun_msj('POR FAVOR INGRESE EL MONTO a deducir');
		document.getElementById('monto').focus();
		return false;
	}if(document.getElementById("cod_nomina").value==''){
		fun_msj('debe seleccionar una transacc&oacute;n a modificar');
		document.getElementById('monto').focus();
		return false;
	}if(document.getElementById("cod_trans").value==''){
		fun_msj('debe seleccionar una transacci&oacute;n a modificar');
		document.getElementById('monto').focus();
		return false;
	}

}//valida_monto125



function valida_dias1(){
	if(document.getElementById("monto").value==''){
		fun_msj('POR FAVOR INGRESE LA CANTIDAD DE DIAS');
		document.getElementById('monto').focus();
		return false;
		}

}//valida_dias1


function  cnmp10_cancelacion_limpiar11(){
  		document.getElementById('transaccion').value="";
 		document.getElementById('denominacion').value="";
 		document.getElementById('monto').value="";


  }//cnmp10_cancelacion_limpiar11




  function cnmp10_cancelacion_limpiar_eliminar1(){
  		document.getElementById('nomina_1').options[0].selected=true;
  		document.getElementById('nomina_1').options[0].value="";
 		document.getElementById('nomina_1').options[0].text="";
 		document.getElementById('cod_nomina').value="";
 		document.getElementById('deno_nomina').value="";
  		document.getElementById('select_2').options[0].selected=true;
  		document.getElementById('select_2').options[0].value="";
 		document.getElementById('select_2').options[0].text="";
  		document.getElementById('transaccion').value="";
 		document.getElementById('denominacion').value="";

  }


    function cnmp10_cancelacion_limpiar_eliminar2(){
  		document.getElementById('select_1').options[0].selected=true;
  		document.getElementById('select_1').options[0].value="";
 		document.getElementById('select_1').options[0].text="";
 		document.getElementById('cod_nomina').value="";
 		document.getElementById('deno_nomina').value="";
  		document.getElementById('select_2').options[0].selected=true;
  		document.getElementById('select_2').options[0].value="";
 		document.getElementById('select_2').options[0].text="";
  		document.getElementById('transaccion').value="";
 		document.getElementById('denominacion').value="";

  }



function validar_eliminar_escenarios_132(){
 	if(!confirm("Esta seguro que desea eliminar este registro")){
         return false;
    }

}/// validar_eliminar_escenarios_132






function frecuencia_focus(){
alert("hola "+document.getElementById('frecuencia_1').checked+"  y  "+document.getElementById('frecuencia_2').checked);

	if(document.getElementById('frecuencia_1').checked==true){
        document.getElementById('frecuencia_2').checked=true;
		document.getElementById('frecuencia_2').focus.blur();
		return false;
	}else if(document.getElementById('frecuencia_2').checked==true){
         document.getElementById('frecuencia_1').checked=true;
		document.getElementById('frecuencia_1').focus.blur();
		return false;
	}

}



