function valida_cnmp15_rango(){
	if(document.getElementById('cod_nomina').value == ""){
            fun_msj('Seleccione el codigo de nomina');
			document.getElementById('cod_nomina').focus();
			return false;
	}else if(document.getElementById('fecha1').value == ""){
            fun_msj('Inserte fecha de inicio');
			document.getElementById('fecha1').focus();
			return false;
	}else if(document.getElementById('fecha2').value == ""){
            fun_msj('Inserte la fecha de terminaci&oacute;n');
			document.getElementById('fecha2').focus();
			return false;
   }else

 		var fecha_actual = document.getElementById('fecha2').value;
		var datearray = fecha_actual.split("/");
		var fecha1 = datearray[2] + '/' + datearray[1] + '/' + datearray[0]; // INVERTIR FECHA YYYY/MM/DD

		var fecha_comparar = document.getElementById('fecha1').value;
		var datearray = fecha_comparar.split("/");
		var fecha2 = datearray[2] + '/' + datearray[1] + '/' + datearray[0]; // INVERTIR FECHA YYYY/MM/DD

		if (fecha2>fecha1){
            fun_msj('la Fecha de inicio no debe ser mayor a la fecha de culminacion');
            return false;
   }


}//fin valida_cnmp15_rango



function calcula_monto_bono_anticipo_transfe(){
	pag='../../include/cfpp05/moneda.php?monto=';
	var bono_transfe=0;
  	var bono_antici=0;
  	var resta=0;
  	var total=0;

      bono_transfe = reemplazarPC(document.getElementById("id_bono_transferencia").value);
      bono_antici = reemplazarPC(document.getElementById('id_bono_anticipo').value);

      if(eval(bono_antici)>eval(bono_transfe)){
      	fun_msj('El monto del bono de anticipo no puede ser mayor que el bono de transferencia');
      }else{
	  	resta = eval(bono_transfe)-eval(bono_antici);
    	resta = redondear(resta, 2);
      	total=reemplazarPC(resta);
      }

	cargarMonto('id_deuda_anticipo', pag+total);

}//fin calcula_monto_bono_anticipo_transfe


function valida_guardar_bono_anticipo_transfe(){
	var bono_transfe=0;
  	var bono_antici=0;

      bono_transfe = reemplazarPC(document.getElementById("id_bono_transferencia").value);
      bono_antici = reemplazarPC(document.getElementById('id_bono_anticipo').value);

	  if(document.getElementById('id_fecha_anticipo').value == ""){
            fun_msj('Ingrese la fecha del anticipo');
			document.getElementById('id_fecha_anticipo').focus();
			return false;
	  }else if(document.getElementById('id_bono_anticipo').value == "0" || document.getElementById('id_bono_anticipo').value == "0,00"){
      		fun_msj('Ingrese el monto del bono de anticipo');
			document.getElementById('id_bono_anticipo').focus();
			return false;
      }else if(eval(bono_antici)>eval(bono_transfe)){
      		fun_msj('El monto del bono de anticipo no puede ser mayor que el bono de transferencia');
			document.getElementById('id_bono_anticipo').focus();
			return false;
      }else{
			return true;
      }

}// fin valida_guardar_bono_anticipo_transfe


function valida_gm_firmas_reporte_btransfe(){
	if(document.getElementById('nombre_primera_firma').value == "" && document.getElementById('nombre_segunda_firma').value == "" && document.getElementById('nombre_tercera_firma').value == "" && document.getElementById('nombre_cuarta_firma').value == "" && document.getElementById('cargo_primera_firma').value == "" && document.getElementById('cargo_segunda_firma').value == "" && document.getElementById('cargo_tercera_firma').value == "" && document.getElementById('cargo_cuarta_firma').value == "" && document.getElementById('nombre_quinta_firma').value == "" && document.getElementById('nombre_sexta_firma').value == "" && document.getElementById('nombre_septima_firma').value == "" && document.getElementById('nombre_octava_firma').value == "" && document.getElementById('cargo_quinta_firma').value == "" && document.getElementById('cargo_sexta_firma').value == "" && document.getElementById('cargo_septima_firma').value == "" && document.getElementById('cargo_octava_firma').value == ""){
            fun_msj('Por favor, Ingrese alguna firma');
			document.getElementById('nombre_primera_firma').focus();
			return false;
	}else{
			return true;
	}
}//fin valida_gm_firmas_reporte_btransfe


function valida_gm_firmas_vacaciones(){
	if(document.getElementById('nombre_primera_firma').value == "" && document.getElementById('nombre_segunda_firma').value == "" && document.getElementById('cargo_primera_firma').value == "" && document.getElementById('cargo_segunda_firma').value == ""){
            fun_msj('Por favor, Ingrese alguna firma');
			document.getElementById('nombre_primera_firma').focus();
			return false;
	}else{
			return true;
	}
}//fin valida_gm_firmas_vacaciones


function vvalida_cnmp15_rango_2(){
 var i=document.getElementById('mmod').value;
//alert("esto"+i);
//var fecha4="fecha4"+eval(i);
//var fecha3="fecha3"+i;
//alert(eval(i));
 if(diferenciaFecha(document.getElementById('fecha4'+eval(i)).value, document.getElementById('fecha3'+eval(i)).value)==true){
            fun_msj('la Fecha de culminacion no debe ser mayor a la fecha de inicio');
            return false;
   }


}//fin valida_cnmp15_rango_2




function valida_fecha_escalas_varios(){

		if(eval(document.getElementById('ano1').value) < eval(1000)){
            fun_msj('Debe ingresar un a&ntilde;o de inicio valido');
			document.getElementById('ano1').focus();
			return false;
		}else if(eval(document.getElementById('ano2').value) < eval(1000)){
            fun_msj('Debe ingresar un a&ntilde;o de culminacion valido');
			document.getElementById('ano2').focus();
			return false;
		}else if(eval(document.getElementById('mes1').value) > eval(12)){
            fun_msj('Debe ingresar un mes de inicio valido');
			document.getElementById('mes1').focus();
			return false;
		}else if(eval(document.getElementById('mes2').value) > eval(12)){
            fun_msj('Debe ingresar un mes de culminacion valido');
			document.getElementById('mes2').focus();
			return false;
		}else if(eval(document.getElementById('dia1').value) > eval(31)){
            fun_msj('Debe ingresar un dia de inicio valido');
			document.getElementById('dia1').focus();
			return false;
		}else if(eval(document.getElementById('dia2').value) > eval(31)){
            fun_msj('Debe ingresar un dia de culminacion valido');
			document.getElementById('dia2').focus();
			return false;
		}else if(eval(document.getElementById('hasta').value) < eval(document.getElementById('desde').value)){
            fun_msj('el campo hasta antiguedad no puede ser menor al campo desde antiguedad');
			document.getElementById('hasta').focus();
			return false;
		}else if(eval(document.getElementById('ano1').value) > eval(document.getElementById('ano2').value) ){
            fun_msj('la fecha de inicio no debe ser mayor a la de culminacion, a&ntilde;o');
			document.getElementById('ano1').focus();
			return false;
		}else if(eval(document.getElementById('ano1').value) == eval(document.getElementById('ano2').value) ){
				if(eval(document.getElementById('mes1').value) > eval(document.getElementById('mes2').value) ){
		            fun_msj('la fecha de inicio no debe ser mayor a la de culminacion, mes');
					document.getElementById('mes1').focus();
					return false;
				}else if(eval(document.getElementById('mes1').value) == eval(document.getElementById('mes2').value) ){
		            if(eval(document.getElementById('dia1').value) > eval(document.getElementById('dia2').value) ){
			            fun_msj('la fecha de inicio no debe ser mayor a la de culminacion, dias');
						document.getElementById('mes1').focus();
						return false;
					}
				}
		}



}//fin valida_fecha_escalas_varios





function valida_fecha_escalas_varios_modificar(){

		var i=document.getElementById('mmod').value;
		var fecha_esca_mo=document.getElementById('anos2').value+document.getElementById('mess2').value+document.getElementById('dias2').value;
		var fecha_esca_anterio=document.getElementById('fecha_escala_anteri').value;

		if(eval(document.getElementById('anos1').value) < eval(1000)){
            fun_msj('Debe ingresar un a&ntilde;o de inicio valido');
			document.getElementById('anos1').focus();
			return false;
		}else if(eval(document.getElementById('anos2').value) < eval(1000)){
            fun_msj('Debe ingresar un a&ntilde;o de culminacion valido');
			document.getElementById('anos2').focus();
			return false;
		}else if(eval(document.getElementById('mess1').value) > eval(12)){
            fun_msj('Debe ingresar un mes de inicio valido');
			document.getElementById('mess1').focus();
			return false;
		}else if(eval(document.getElementById('mess2').value) > eval(12)){
            fun_msj('Debe ingresar un mes de culminacion valido');
			document.getElementById('mess2').focus();
			return false;
		}else if(eval(document.getElementById('dias1').value) > eval(31)){
            fun_msj('Debe ingresar un dia de inicio valido');
			document.getElementById('dias1').focus();
			return false;
		}else if(eval(document.getElementById('dias2').value) > eval(31)){
            fun_msj('Debe ingresar un dia de culminacion valido');
			document.getElementById('dias2').focus();
			return false;
		}else if(eval(document.getElementById('hastas').value) < eval(document.getElementById('desdes').value)){
            fun_msj('el campo hasta antiguedad no puede ser menor al campo desde antiguedad');
			document.getElementById('hastas').focus();
			return false;
		}else if(eval(document.getElementById('anos1').value) > eval(document.getElementById('anos2').value) ){
            fun_msj('la fecha de inicio no debe ser mayor a la de culminacion, a&ntilde;o');
			document.getElementById('anos1').focus();
			return false;
		}else if(eval(document.getElementById('anos1').value) == eval(document.getElementById('anos2').value) ){
				if(eval(document.getElementById('mess1').value) > eval(document.getElementById('mess2').value) ){
		            fun_msj('la fecha de inicio no debe ser mayor a la de culminacion, mes');
					document.getElementById('mess1').focus();
					return false;
				}else if(eval(document.getElementById('mess1').value) == eval(document.getElementById('mess2').value) ){
		            if(eval(document.getElementById('dias1').value) > eval(document.getElementById('dias2').value) ){
			            fun_msj('la fecha de inicio no debe ser mayor a la de culminacion, dias');
						document.getElementById('mess1').focus();
						return false;
					}
				}
		}else if(eval(i) > eval(0)){

		 	if(diferenciaFecha(fecha_esca_mo, fecha_esca_anterio)==true){
            	fun_msj('La fecha de terminacion de la escala debe ser mayor a la fecha de la escala anterior');
	            return false;
		    }
/*
			if(eval(fecha_esca_mo) < eval(fecha_esca_anterio)){
				fun_msj('La fecha de terminacion de la escala debe ser mayor a la fecha de la escala anterior');
				document.getElementById('dias2').focus();
				return false;
			} */
		}
}//fin valida_fecha_escalas_varios_modificar


function validare_campos_deposito_fideco(){
	if(eval(document.getElementById('id_depof_ano').value.length) < eval(4)){
            fun_msj('Ingrese un a&ntilde;o v&aacute;lido');
			document.getElementById('id_depof_ano').focus();
			return false;
	}else if(document.getElementById('id_depof_ano').value == ""){
            fun_msj('Ingrese el a&ntilde;o de dep&oacute;sito');
			document.getElementById('id_depof_ano').focus();
			return false;
	}else if(document.getElementById('id_depof_ene').value == "" && document.getElementById('id_depof_feb').value == "" && document.getElementById('id_depof_mar').value == "" && document.getElementById('id_depof_abr').value == "" && document.getElementById('id_depof_may').value == "" && document.getElementById('id_depof_jun').value == "" && document.getElementById('id_depof_jul').value == "" && document.getElementById('id_depof_ago').value == "" && document.getElementById('id_depof_sep').value == "" && document.getElementById('id_depof_oct').value == "" && document.getElementById('id_depof_nov').value == "" && document.getElementById('id_depof_dic').value == ""){
            fun_msj('Debe marcar alg&uacute;n mes');
			document.getElementById('id_promt_depof').focus();
			return false;
	}
}


function validare_campos_deposito_fideco_mod(){
	if(document.getElementById('id_depof_anom').value == ""){
            fun_msj('Ingrese el a&ntilde;o de dep&oacute;sito');
			document.getElementById('id_depof_anom').focus();
			return false;
	}else if(document.getElementById('id_depof_enem').value == "" && document.getElementById('id_depof_febm').value == "" && document.getElementById('id_depof_marm').value == "" && document.getElementById('id_depof_abrm').value == "" && document.getElementById('id_depof_maym').value == "" && document.getElementById('id_depof_junm').value == "" && document.getElementById('id_depof_julm').value == "" && document.getElementById('id_depof_agom').value == "" && document.getElementById('id_depof_sepm').value == "" && document.getElementById('id_depof_octm').value == "" && document.getElementById('id_depof_novm').value == "" && document.getElementById('id_depof_dicm').value == ""){
            fun_msj('Debe marcar alg&uacute;n mes');
			document.getElementById('id_promt_depof').focus();
			return false;
	}
}


function validare_camp_bono_vacacional(){
	if(document.getElementById('id_fecha_ingreso').value == ""){
            fun_msj('El campo fecha de ingreso no puede estar vacio');
			document.getElementById('id_fecha_ingreso').focus();
			return false;
	}else if(document.getElementById('id_ano').value == ""){
            fun_msj('El campo a&ntilde;o no puede estar vacio');
			document.getElementById('id_ano').focus();
			return false;
	}else if(document.getElementById('id_numero').value == ""){
            fun_msj('El campo n&uacute;mero no puede estar vacio');
			document.getElementById('id_numero').focus();
			return false;
	}else if(document.getElementById('fecha_desde').value == ""){
            fun_msj('Ingrese la fecha vacaciones desde');
			document.getElementById('fecha_desde').focus();
			return false;
	}else if(document.getElementById('fecha_hasta').value == ""){
            fun_msj('Ingrese la fecha vacaciones hasta');
			document.getElementById('fecha_hasta').focus();
			return false;
	}else if(document.getElementById('id_periodo_desde').value == ""){
            fun_msj('Ingrese el periodo desde');
			document.getElementById('id_periodo_desde').focus();
			return false;
	}else if(document.getElementById('id_periodo_hasta').value == ""){
            fun_msj('Ingrese el periodo hasta');
			document.getElementById('id_periodo_hasta').focus();
			return false;
	}else if(document.getElementById('fecha_calculo').value == ""){
            fun_msj('Ingrese la fecha de c&aacute;lculo');
			document.getElementById('fecha_calculo').focus();
			return false;
	}else if(document.getElementById('id_cantidad_vacaciones').value == ""){
            fun_msj('Ingrese la cantidad de vacaciones');
			document.getElementById('id_cantidad_vacaciones').focus();
			return false;
	}else if(document.getElementById('id_dias_inhabiles').value == ""){
            fun_msj('Ingrese los d&iacute;as inhabiles');
			document.getElementById('id_dias_inhabiles').focus();
			return false;
	}else if(document.getElementById('id_numero_lunes').value == ""){
            fun_msj('Ingrese los n&uacute;meros de lunes');
			document.getElementById('id_numero_lunes').focus();
			return false;
	}else if(document.getElementById('id_tdias').value == ""){
            fun_msj('El campo d&iacute;as del tiempo de servicio no puede estar vacio');
			document.getElementById('id_tdias').focus();
			return false;
	}else if(document.getElementById('id_tmeses').value == ""){
            fun_msj('El campo meses del tiempo de servicio no puede estar vacio');
			document.getElementById('id_tmeses').focus();
			return false;
	}else if(document.getElementById('id_tanios').value == ""){
            fun_msj('El campo a&ntilde;os del tiempo de servicio no puede estar vacio');
			document.getElementById('id_tanios').focus();
			return false;
	}else if(document.getElementById('id_anos_anteriores').value == ""){
            fun_msj('El campo a&ntilde;os de antiguedad anteriores del tiempo de servicio no puede estar vacio');
			document.getElementById('id_anos_anteriores').focus();
			return false;
	}else if(document.getElementById('id_anos_antiguedad').value == ""){
            fun_msj('El campo total a&ntilde;os de antiguedad del tiempo de servicio no puede estar vacio');
			document.getElementById('id_anos_antiguedad').focus();
			return false;
	}else if(document.getElementById('id_salario_mensual').value == ""){
            fun_msj('El campo salario mensual no puede estar vacio');
			document.getElementById('id_salario_mensual').focus();
			return false;
	}else if(document.getElementById('id_salario_diario').value == ""){
            fun_msj('El campo salario diario no puede estar vacio');
			document.getElementById('id_salario_diario').focus();
			return false;
	}else if(diferenciaFecha(document.getElementById('fecha_hasta').value, document.getElementById('fecha_desde').value)){
			fun_msj('La fecha de vacaciones desde no puede ser mayor a la de vacaciones hasta');
			return false;
	}else if(eval(document.getElementById('id_periodo_desde').value.length) < eval(4)){
            fun_msj('Ingrese un a&ntilde;o v&aacute;lido para el periodo desde');
			document.getElementById('id_periodo_desde').focus();
			return false;
	}else if(eval(document.getElementById('id_periodo_desde').value) < eval(1000)){
            fun_msj('Ingrese un a&ntilde;o v&aacute;lido para el periodo desde');
			document.getElementById('id_periodo_desde').focus();
			return false;
	}else if(eval(document.getElementById('id_periodo_hasta').value.length) < eval(4)){
            fun_msj('Ingrese un a&ntilde;o v&aacute;lido para el periodo hasta');
			document.getElementById('id_periodo_hasta').focus();
			return false;
	}else if(eval(document.getElementById('id_periodo_hasta').value) < eval(1000)){
            fun_msj('Ingrese un a&ntilde;o v&aacute;lido para el periodo hasta');
			document.getElementById('id_periodo_hasta').focus();
			return false;
	}else if(eval(document.getElementById('id_periodo_desde').value) >= eval(document.getElementById('id_periodo_hasta').value)){
            fun_msj('Disculpe, el Periodo Desde No puede ser mayor o igual al Periodo Hasta');
			document.getElementById('id_periodo_desde').focus();
			return false;
	}else if(eval(document.getElementById('id_periodo_hasta').value) <= eval(document.getElementById('id_periodo_desde').value)){
            fun_msj('Disculpe, el Periodo Hasta No puede ser menor o igual al Periodo Desde');
			document.getElementById('id_periodo_hasta').focus();
			return false;
	}else if(eval(document.getElementById("id_tdias").value) > eval(31)){
            fun_msj('El campo d&iacute;as del tiempo de servicio no es v&aacute;lido');
			document.getElementById('id_tdias').focus();
			return false;
	}else if(eval(document.getElementById("id_tmeses").value) > eval(31)){
            fun_msj('El campo meses del tiempo de servicio no es v&aacute;lido');
			document.getElementById('id_tmeses').focus();
			return false;
	}else if(document.getElementById('vacacion_1').checked==false && document.getElementById('vacacion_2').checked==false && document.getElementById('vacacion_3').checked==false){
			fun_msj("Debe seleccionar la acci&oacute;n del proceso ( <BLINK>AMBAS, VACACIONES O BONO VACACIONAL</BLINK> )");
			return false;
	}
}


function validares_campos_fideicomisos(){
	if(document.getElementById('cod_tipo_nomina').value == ""){
            fun_msj('Debe seleccionar la n&oacute;mina');
			document.getElementById('cod_tipo_nomina').focus();
			return false;
	}else if(document.getElementById('ano_fid').value == ""){
            fun_msj('Debe ingresar el A&ntilde;o');
			document.getElementById('ano_fid').focus();
			return false;
	}else if(eval(document.getElementById('ano_fid').value) < eval(1000)){
            fun_msj('Debe ingresar un A&ntilde;o V&aacute;lido');
			document.getElementById('ano_fid').focus();
			return false;
	}else if(document.getElementById('mes').value == ""){
            fun_msj('Debe seleccionar el Mes');
			document.getElementById('mes').focus();
			return false;
	}else if(document.getElementById('fecha_desde').value == ""){
            fun_msj('Debe seleccionar la fecha desde');
			document.getElementById('fecha_desde').focus();
			return false;
	}else if(document.getElementById('fecha_hasta').value == ""){
            fun_msj('Debe seleccionar la fecha hasta');
			document.getElementById('fecha_hasta').focus();
			return false;
	}else if(diferenciaFecha(document.getElementById('fecha_hasta').value, document.getElementById('fecha_desde').value)){
            fun_msj('Disculpe, el Periodo Desde No puede ser mayor al Periodo Hasta');
			document.getElementById('fecha_desde').focus();
			return false;
	}
}


function blanquear_campo_desde(){
//alert(eval(document.getElementById('desde').value));
	if(eval(document.getElementById('desde').value) != eval(1)){
		document.getElementById('desde').value=1;
	}

}// fin blanquear_campo_desde


function control_nominas_realizadas(){
	if(document.getElementById('select_1').value == ""){
            fun_msj('Seleccione el codigo de n&oacute;mina');
			document.getElementById('cod_nomina').focus();
			return false;
	}else if(document.getElementById('numeros').value == ""){
            fun_msj('debe seleccionar un n&uacute;mero');
			document.getElementById('fecha1').focus();
			return false;
	}else if(document.getElementById('desde').value == ""){
            fun_msj('Inserte fecha de inicio');
			document.getElementById('fecha1').focus();
			return false;
	}else if(document.getElementById('hasta').value == ""){
            fun_msj('Inserte la fecha de culminaci&oacute;n');
			document.getElementById('fecha2').focus();
			return false;
   }else if(diferenciaFecha(document.getElementById('hasta').value, document.getElementById('desde').value)){
            fun_msj('la Fecha de inicio no debe ser mayor a la fecha de culminaci&oacute;n');
            document.getElementById('desde').value='';
            document.getElementById('hasta').value='';
            return false;
   }else if(document.getElementById('concepto').value == ""){
            fun_msj('ingrese el concepto');
			document.getElementById('concepto').focus();
			return false;
   }

}// control_nominas_realizadas



function valida_fecha_nominas_realizadas(){
	if(diferenciaFecha(document.getElementById('hasta_1').value, document.getElementById('desde_1').value)){
            fun_msj('la Fecha de inicio no debe ser mayor a la fecha de culminaci&oacute;n');
            document.getElementById('desde').value='';
            document.getElementById('hasta').value='';
            return false;
   }else if(document.getElementById('concepto_1').value == ""){
            fun_msj('ingrese el concepto');
			document.getElementById('concepto').focus();
			return false;
   }
}// valida_fecha_nominas_realizadas


