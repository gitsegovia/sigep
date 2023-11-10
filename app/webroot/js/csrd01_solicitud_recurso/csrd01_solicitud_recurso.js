
function valida_csrp01_monto1_solicitud_recurso(){
		 if(document.getElementById("monto1").value==''){
			fun_msj('debe insertar un monto');
			document.getElementById('monto1').focus();
			return false;
		}
}//valida_csrp01_monto1_solicitud_recurso




function valida_guardar_solicitud_rei(){


monto_a_rei = document.getElementById("monto_a_rei").value;
var str = monto_a_rei;for(i=0; i<monto_a_rei.length; i++){str = str.replace('.','');}str   = str.replace(',','.');
var monto_a_rei = eval(str);


mc = document.getElementById("mc").value;
var str = mc;for(i=0; i<mc.length; i++){str = str.replace('.','');}str   = str.replace(',','.');
var mc = eval(str);



		if(document.getElementById('numero_solicitud').value==""){
					fun_msj('seleccione el numero de la solicitud');
					document.getElementById('numero_solicitud').focus();
					return false;
		}else if(document.getElementById('monto_a_rei').value==""){
					fun_msj('Ingrese el monto reintegro');
					document.getElementById('monto_a_rei').focus();
					return false;
		}else if(monto_a_rei > mc){
					fun_msj('El monto reintegro no puede ser mayor al monto del cheque');
					document.getElementById('monto_a_rei').focus();
					return false;
		}


}// fin valida_guardar_solicitud_2




function valida_guardar_solicitud_1(){

		if(document.getElementById('concepto').value==""){
					//alert("guardar");
					fun_msj('Ingrese el Concepto de la Solicitud');
					//document.getElementById('radio_si_no').focus();
					return false;
		}else if(document.getElementById('tipo_1').value==""){
					//alert("guardar");
					fun_msj('Seleccione el tipo de recurso');
					//document.getElementById('radio_si_no').focus();
					return false;
		}else if(document.getElementById('mes_1').value==""){
					fun_msj('Seleccione el Mes');
					//document.getElementById('radio_si_no').focus();
					return false;
		}else if(document.getElementById('radio_si_no_1').checked==true && document.getElementById('quincena_1').checked==false && document.getElementById('quincena_2').checked==false){
					fun_msj('Seleccione si es la primera o segunda quincena');
					//document.getElementById('radio_si_no').focus();
					return false;
		}


}


function valida_guardar_solicitud_2(){

		if(document.getElementById('concepto').value==""){
					//alert("guardar");
					fun_msj('Ingrese el Concepto de la Solicitud');
					//document.getElementById('radio_si_no').focus();
					return false;
		}


}// fin valida_guardar_solicitud_2





function habilita_solicitud_quincena_mes(){
alert("si");
/*document.getElementById('quincena_1').disabled=false;
			document.getElementById('quincena_2').disabled=false;
			document.getElementById('mes_1').disabled=false;*/
			if(document.getElementById('quincena_1').checked==true){
			alert("disabled");
			document.getElementById('quincena_1').disabled="disabled";
			document.getElementById('quincena_2').disabled="disabled";
			document.getElementById('mes_1').disabled="disabled";
		}

		if(document.getElementById('condicion_2').checked==true){
			alert("disabled");
			document.getElementById('quincena_1').disabled="disabled";
			document.getElementById('quincena_2').disabled="disabled";
			document.getElementById('mes_1').disabled="disabled";
		}else{
			alert("enabled");
			document.getElementById('quincena_1').disabled=false;
			document.getElementById('quincena_2').disabled=false;
			document.getElementById('mes_1').disabled=false;
		}



	/*if(document.getElementById('radio_2').checked==true){
		document.getElementById('quincena').disabled="";
		document.getElementById('tipo_trans_1').checked=true;
		document.getElementById('tipo_trans_2').disabled="";
		document.getElementById('select_4').disabled="";
	}else{
		document.getElementById('tipo_trans_1').disabled="disabled";
		document.getElementById('tipo_trans_2').disabled="disabled";
		document.getElementById('tipo_trans_1').checked=false;
		document.getElementById('select_4').disabled="disabled";

		}*/

}// fin habilita_solicitud_quincena_mes


function valida_cargos_solicitud(){

		if(document.getElementById('enviado_a').value==""){
					fun_msj('Ingrese el nombre de la persona a la cual va dirigido el oficio');
					document.getElementById('enviado_a').focus();
					return false;
		}else if(document.getElementById('cargo_a').value==""){
					fun_msj('Ingrese el cargo de la persona a la cual va dirigido el oficio');
					document.getElementById('cargo_a').focus();
					return false;
		}else if(document.getElementById('enviado_por').value==""){
					fun_msj('Ingrese el nombre del firmante del oficio');
					document.getElementById('enviado_por').focus();
					return false;
		}else if(document.getElementById('cargo_por').value==""){
					fun_msj('Ingrese el cargo del firmante del oficio');
					document.getElementById('cargo_por').focus();
					return false;
		}
}// fin valida_guardar_solicitud_2

function valida_reporte_solicitud_xx(){

		if(document.getElementById('cod_solicitud').value==""){
					fun_msj('Seleccione el c&oacute;digo de la solicitud de recursos');
					document.getElementById('cod_solicitud').focus();
					return false;
		}


}// fin valida_guardar_solicitud_2

function valida_guardar_solicitud_fina(){


monto_a_sol = document.getElementById("monto_a_sol").value;
var str = monto_a_sol;for(i=0; i<monto_a_sol.length; i++){str = str.replace('.','');}str   = str.replace(',','.');
var monto_a_sol = eval(str);


dispo_anual = document.getElementById("dispo_anual").value;
var str = dispo_anual;for(i=0; i<dispo_anual.length; i++){str = str.replace('.','');}str   = str.replace(',','.');
var dispo_anual = eval(str);

		if(document.getElementById('fecha_solicitud').value==""){
			fun_msj('Ingrese la fecha de la solicitud');
			document.getElementById('fecha_solicitud').focus();
			return false;
		}else if(verifica_cierre_ano_ejecucion('fecha_solicitud')==false){
			fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA DE LA SOLICITUD NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
			return false;
		}else if(document.getElementById('monto_a_sol').value==""){
					fun_msj('Ingrese el monto a solicitar');
					document.getElementById('monto_a_sol').focus();
					return false;
		}else if(monto_a_sol > dispo_anual){
					fun_msj('El monto solicitado es mayor a lo disponible');
					document.getElementById('monto_a_sol').focus();
					return false;
		}else if(document.getElementById('concepto').value==""){
					fun_msj('Ingrese el Concepto de la Solicitud');
					document.getElementById('concepto').focus();
					return false;
		}


}// fin valida_guardar_solicitud_2



function valida_reporte_dep_csrd01(){
		if(document.getElementById('depen').value==""){
					fun_msj('debe seleccionar la dependecia');
					document.getElementById('depen').focus();
					return false;
		}else if(document.getElementById('recurso').value==""){
					fun_msj('debe seleccionar el tipo de recurso');
					document.getElementById('recurso').focus();
					return false;
		}
}


function valida_reporte_dep_csrd01_1(){
		if(document.getElementById('depen').value==""){
					fun_msj('debe seleccionar la dependecia');
					document.getElementById('depen').focus();
					return false;
		}
}


function valida_reporte_dep_csrd01_2(){
	if(document.getElementById('ano').value==""){
		fun_msj('debe ingresar un año');
		document.getElementById('ano').focus();
		return false;
	}else if(document.getElementById('peticion_1') && document.getElementById('peticion_2')){
		if(document.getElementById('peticion_1').checked==false && document.getElementById('peticion_2').checked==false){
			fun_msj('debe seleccionar una de las opciones');
			return false;
		}else if(document.getElementById('peticion_1').checked==true && document.getElementById('tiempo_2').checked==true){
					if(document.getElementById('mes_1').value==""){
						fun_msj('debe seleccionar un mes');
						document.getElementById('mes_1').focus();
						return false;
					}
		}else if(document.getElementById('peticion_2').checked==true){
					if(document.getElementById('mes_1') && document.getElementById('mes_1').value==""){
							fun_msj('debe seleccionar un mes');
							document.getElementById('mes_1').focus();
							return false;
					}else if(document.getElementById('depen').value==""){
						fun_msj('debe seleccionar la dependecia');
						document.getElementById('depen').focus();
						return false;
					}else if(document.getElementById('actividad1').value==""){
						fun_msj('debe seleccionar la actividad');
						document.getElementById('actividad1').focus();
						return false;
					}
		}


	}

}
