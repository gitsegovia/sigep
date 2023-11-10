function canp00ReporteOnClickEstados(){
	document.getElementById('radio_tipo_instituciones_1').checked=false;
	document.getElementById('radio_estados_instituciones_1').checked=false;
	document.getElementById('radio_2').checked=false;
	document.getElementById('capa-select-remote-estado').style.display="none";
	document.getElementById('capa-select-remote-institucion').style.display="none";
	document.getElementById('div-estado').style.display="none";
	document.getElementById('div-tipo-instituciones').style.display="none";
	document.getElementById('div-institucion').innerHTML = '';
}


function canp00ReporteOnClickTipoInstituciones(){
	document.getElementById('radio_estados_1').checked=false;
	document.getElementById('radio_estados_instituciones_1').checked=false;
	document.getElementById('radio_2').checked=false;
	document.getElementById('capa-select-remote-estado').style.display="none";
	document.getElementById('capa-select-remote-institucion').style.display="none";
	document.getElementById('div-estado').style.display="none";
	document.getElementById('div-tipo-instituciones').style.display="none";
	document.getElementById('div-institucion').innerHTML = '';
}

function canp00ReporteOnClickEstadosInstituciones(){
	document.getElementById('radio_estados_1').checked=false;
	document.getElementById('radio_tipo_instituciones_1').checked=false;
	document.getElementById('radio_2').checked=false;
	document.getElementById('div-estado').style.display="";
	document.getElementById('capa-select-remote-estado').style.display="none";
	document.getElementById('capa-select-remote-institucion').style.display="none";
	document.getElementById('div-tipo-instituciones').style.display="";
	document.getElementById('div-institucion').innerHTML = '';
	fun_msj2('Seleccione el estado y el tipo de instituci&oacute;n');
}

function canp00ReporteOnClickInstitucion(){
	document.getElementById('radio_estados_1').checked=false;
	document.getElementById('radio_tipo_instituciones_1').checked=false;
	document.getElementById('radio_estados_instituciones_1').checked=false;
	document.getElementById('div-estado').style.display="none";
	document.getElementById('div-tipo-instituciones').style.display="none";
	document.getElementById('capa-select-remote-estado').style.display="";
	document.getElementById('capa-select-remote-institucion').style.display="";
	document.getElementById('div-institucion').innerHTML = '';
}

function validar_reporte_consumo_canp00(){
	if($('radio_estados_1').checked==true){

	}else if($('radio_tipo_instituciones_1').checked==true){

	}else if($('radio_estados_instituciones_1').checked==true){
		if($('canp00_reporteCodEntidad').value==''){
			fun_msj('Por favor, seleccione el estado');
			$('canp00_reporteCodEntidad').focus();
			return false;
		}else if($('cod_tipo_inst').value==''){
			fun_msj('Por favor, seleccione el tipo de instituci&oacute;n');
			$('cod_tipo_inst').focus();
			return false;
		}
	}else if($('radio_2').checked==true){
		if($('select_entidad').value==''){
			fun_msj('Por favor, seleccione el estado');
			$('select_entidad').focus();
			return false;
		}else if($('select_tipo_institucion').value==''){
			fun_msj('Por favor, seleccione el tipo de instituci&oacute;n');
			$('select_tipo_institucion').focus();
			return false;
		}else if($('select_institucion').value==''){
			fun_msj('Por favor, seleccione la instituci&oacute;n');
			$('select_institucion').focus();
			return false;
		}

	}else{
		fun_msj('Por favor, especifique como desea emitir el reporte');
		$('radio_republica_1').focus();
		return false;
	}

}

