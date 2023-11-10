function valida_caop04_ordencompra_parametros(){

if(document.getElementById('porcentaje_fiel_cumplimiento').value==""){
			fun_msj('Inserte el porcentaje fiel cumplimiento');
			document.getElementById('porcentaje_fiel_cumplimiento').focus();
			return false;

} if(document.getElementById('porcentaje_timbre_fiscal').value==""){

			fun_msj('Inserte el procentaje de retenci&oacute;n de timbre fiscal');
			document.getElementById('porcentaje_timbre_fiscal').focus();
			return false;

} if(document.getElementById('porcentaje_laboral').value==""){

			fun_msj('Inserte el procentaje de retenci&oacute;n laboral');
			document.getElementById('porcentaje_laboral').focus();
			return false;

} if(document.getElementById('desde_monto_timbre').value==""){

			fun_msj('Inserte el monto del timbre fiscal');
			document.getElementById('desde_monto_timbre').focus();
			return false;

}
if(document.getElementById('retencion_incluye_iva_1').checked==false && document.getElementById('retencion_incluye_iva_2').checked==false){

    fun_msj('Incluye el i.v.a. en retencion de garantias?');
    document.getElementById('retencion_incluye_iva_1').focus();
			return false;
}
if(document.getElementById('aplica_retencion_iva_1').checked==false && document.getElementById('aplica_retencion_iva_2').checked==false){

    fun_msj('retiene i.v.a.?');
			return false;

}
if(document.getElementById('porcentaje_islr_natural').value==""){

			fun_msj('Inserte el procentaje de retenci&oacute;n de i.s.r.l personas naturales');
			document.getElementById('porcentaje_islr_natural').focus();
			return false;

} if(document.getElementById('porcentaje_retencion_iva').value==""){

			fun_msj('Inserte el procentaje de retenci&oacute;n del i.v.a.');
			document.getElementById('porcentaje_retencion_iva').focus();
			return false;

} if(document.getElementById('desde_monto_natural').value==""){

			fun_msj('Inserte el monto de i.s.r.l persona natural');
			document.getElementById('desde_monto_natural').focus();
			return false;

} if(document.getElementById('porcentaje_iva').value==""){

			fun_msj('Inserte el porcentaje i.v.a.');
			document.getElementById('porcentaje_iva').focus();
			return false;

} if(document.getElementById('sustraendo').value==""){

			fun_msj('Inserte el sustraendo');
			document.getElementById('sustraendo').focus();
			return false;

} if(document.getElementById('factor_reversion').value==""){

			fun_msj('Inserte el factor conversi&oacute;n');
			document.getElementById('factor_reversion').focus();
			return false;

} if(document.getElementById('porcentaje_islr_juridico').value==""){

			fun_msj('Inserte el porcentaje i.s.l.r personas jur&iacute;dicas');
			document.getElementById('porcentaje_islr_juridico').focus();
			return false;

} if(document.getElementById('porcentaje_anticipo').value==""){

			fun_msj('Inserte el porcentaje de anticipo');
			document.getElementById('porcentaje_anticipo').focus();
			return false;

}
if(document.getElementById('anticipo_incluye_iva_1').checked==false && document.getElementById('anticipo_incluye_iva_2').checked==false){
    fun_msj('incluye i.v.a. en anticipo?');
	return false;
}
if(document.getElementById('desde_monto_juridico').value==""){

			fun_msj('Inserte el monto i.s.r.l personas jur&iacute;dicas');
			document.getElementById('desde_monto_juridico').focus();
			return false;

} if(document.getElementById('porcentaje_impuesto_municipal').value==""){

			fun_msj('Inserte porcentaje de retenci&oacute;n impuesto municipal');
			document.getElementById('porcentaje_impuesto_municipal').focus();
			return false;

} if(document.getElementById('unidad_tributaria').value==""){

			fun_msj('Inserte la unidad tributaria');
			document.getElementById('unidad_tributaria').focus();
			return false;

} if(document.getElementById('desde_monto_impuesto_municipal').value==""){

			fun_msj('Inserte el monto de impuesto municipal');
			document.getElementById('desde_monto_impuesto_municipal').focus();
			return false;

}

}//fin funtion


function valida_caop04_ordencompra_parametros_s(){

if(document.getElementById('porcentaje_fiel_cumplimiento').value==""){
			fun_msj('Inserte el porcentaje fiel cumplimiento');
			document.getElementById('porcentaje_fiel_cumplimiento').focus();
			return false;

} if(document.getElementById('porcentaje_timbre_fiscal').value==""){

			fun_msj('Inserte el procentaje de retenci&oacute;n de timbre fiscal');
			document.getElementById('porcentaje_timbre_fiscal').focus();
			return false;

} if(document.getElementById('porcentaje_laboral').value==""){

			fun_msj('Inserte el procentaje de retenci&oacute;n laboral');
			document.getElementById('porcentaje_laboral').focus();
			return false;

} if(document.getElementById('desde_monto_timbre').value==""){

			fun_msj('Inserte el monto del timbre fiscal');
			document.getElementById('desde_monto_timbre').focus();
			return false;

} if(document.getElementById('porcentaje_islr_natural').value==""){

			fun_msj('Inserte el procentaje de retenci&oacute;n de i.s.r.l personas naturales');
			document.getElementById('porcentaje_islr_natural').focus();
			return false;

} if(document.getElementById('porcentaje_retencion_iva').value==""){

			fun_msj('Inserte el procentaje de retenci&oacute;n del i.v.a.');
			document.getElementById('porcentaje_retencion_iva').focus();
			return false;

} if(document.getElementById('desde_monto_natural').value==""){

			fun_msj('Inserte el monto de i.s.r.l persona natural');
			document.getElementById('desde_monto_natural').focus();
			return false;

} if(document.getElementById('porcentaje_iva').value==""){

			fun_msj('Inserte el porcentaje i.v.a.');
			document.getElementById('porcentaje_iva').focus();
			return false;

} if(document.getElementById('sustraendo').value==""){

			fun_msj('Inserte el sustraendo');
			document.getElementById('sustraendo').focus();
			return false;

} if(document.getElementById('factor_reversion').value==""){

			fun_msj('Inserte el factor conversi&oacute;n');
			document.getElementById('factor_reversion').focus();
			return false;

} if(document.getElementById('porcentaje_islr_juridico').value==""){

			fun_msj('Inserte el porcentaje i.s.l.r personas jur&iacute;dicas');
			document.getElementById('porcentaje_islr_juridico').focus();
			return false;

} if(document.getElementById('porcentaje_anticipo').value==""){

			fun_msj('Inserte el porcentaje de anticipo');
			document.getElementById('porcentaje_anticipo').focus();
			return false;

}if(document.getElementById('desde_monto_juridico').value==""){

			fun_msj('Inserte el monto i.s.r.l personas jur&iacute;dicas');
			document.getElementById('desde_monto_juridico').focus();
			return false;

} if(document.getElementById('porcentaje_impuesto_municipal').value==""){

			fun_msj('Inserte porcentaje de retenci&oacute;n impuesto municipal');
			document.getElementById('porcentaje_impuesto_municipal').focus();
			return false;

} if(document.getElementById('unidad_tributaria').value==""){

			fun_msj('Inserte la unidad tributaria');
			document.getElementById('unidad_tributaria').focus();
			return false;

} if(document.getElementById('desde_monto_impuesto_municipal').value==""){

			fun_msj('Inserte el monto de impuesto municipal');
			document.getElementById('desde_monto_impuesto_municipal').focus();
			return false;

}

}//fin funtion
