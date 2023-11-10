function valida_cpod05_control_metas(){

	if(document.getElementById('ano').value==""){

			fun_msj('Ingrese el año a formular');
			document.getElementById('ano').focus();
			return false;

	}

	if(!document.getElementById("radio_GESTION").checked){

			fun_msj('Seleccione un Tipo de Proyecto');
			return false;

	}

	if(document.getElementById('numero_proyecto').value==""){

			fun_msj('Seleccione un Proyecto');
			document.getElementById('numero_proyecto').focus();
			return false;

	}

	if(document.getElementById('descripcion_meta').value == ""){
			fun_msj('Debe Ingresar la Meta a Registrar');
			document.getElementById('descripcion_meta').focus();
			return false;
	}

	if(document.getElementById('costo_total_meta').value == ""){
			fun_msj('Debe Ingresar el Costo de la Meta a Registrar');
			document.getElementById('costo_total_meta').focus();
			return false;
	}

	if(document.getElementById('actividad').value == ""){
			fun_msj('Debe Ingresar la Actividad a Registrar');
			document.getElementById('actividad').focus();
			return false;
	}

	if(document.getElementById('indicador_resultados').value == ""){
			fun_msj('Debe Ingresar el Indicador de Resultados');
			document.getElementById('indicador_resultados').focus();
			return false;
	}

	if(document.getElementById('metas_fisicas_1er_trim').value == ""){
			fun_msj('Debe Ingresar la Meta Fisica del 1er Trimestre');
			document.getElementById('metas_fisicas_1er_trim').focus();
			return false;
	}

	if(document.getElementById('metas_fisicas_2do_trim').value == ""){
			fun_msj('Debe Ingresar la Meta Fisica del 2do Trimestre');
			document.getElementById('metas_fisicas_2do_trim').focus();
			return false;
	}

	if(document.getElementById('metas_fisicas_3er_trim').value == ""){
			fun_msj('Debe Ingresar la Meta Fisica del 3er Trimestre');
			document.getElementById('metas_fisicas_3er_trim').focus();
			return false;
	}

	if(document.getElementById('metas_fisicas_4to_trim').value == ""){
			fun_msj('Debe Ingresar la Meta Fisica del 4to Trimestre');
			document.getElementById('metas_fisicas_4to_trim').focus();
			return false;
	}

	return true;

}

function suma_cpod05_resultado_trimestres(){

	if(document.getElementById('metas_fisicas_1er_trim').value==""){
		primer = 0;
	}else{
		primer = document.getElementById('metas_fisicas_1er_trim').value;
	}
	if(document.getElementById('metas_fisicas_2do_trim').value==""){
		segundo = 0;
	}else{
		segundo = document.getElementById('metas_fisicas_2do_trim').value;
	}
	if(document.getElementById('metas_fisicas_3er_trim').value==""){
		tercer = 0;
	}else{
		tercer = document.getElementById('metas_fisicas_3er_trim').value;
	}
	if(document.getElementById('metas_fisicas_4to_trim').value==""){
		cuarto = 0;
	}else{
		cuarto = document.getElementById('metas_fisicas_4to_trim').value;
	}

	total = parseInt(primer) + parseInt(segundo) + parseInt(tercer) + parseInt(cuarto);

	document.getElementById('metas_fisicas_total').value = total;
}


function valida_cpod05_situacion_actual(){

	if(document.getElementById('ano').value==""){

			fun_msj('Ingrese el año a formular');
			document.getElementById('ano').focus();
			return false;

	}

	if(!document.getElementById("radio_GESTION").checked){

			fun_msj('Seleccione un Tipo de Proyecto');
			return false;

	}

	if(document.getElementById('numero_proyecto').value==""){

			fun_msj('Seleccione un Proyecto');
			document.getElementById('numero_proyecto').focus();
			return false;

	}

	if(document.getElementById('situacion_actual').value == ""){
			fun_msj('Debe Ingresar la Situación Actual a Registrar');
			document.getElementById('situacion_actual').focus();
			return false;
	}

	var supuesto = document.getElementById('supuestos').value;
	supuesto = supuesto.replace(/\./g, '');
	supuesto = supuesto.trim();

	if(supuesto == "" || supuesto < 2){
			fun_msj('Debe Ingresar un Supuestos');
			document.getElementById('supuestos').focus();
			return false;
	}

	return true;
}