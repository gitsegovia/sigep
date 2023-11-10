function reporte_analitico_pago(){



                            if(document.getElementById('desde_periodo').value==''){

			                   fun_msj('Inserte la fecha del Periodo Desde');
			                   document.getElementById('desde_periodo').focus();
			                   return false;


			           }else if(document.getElementById('hasta_periodo').value==''){

			                   fun_msj('Inserte la fecha del Periodo Hasta');
			                   document.getElementById('hasta_periodo').focus();
			                   return false;

			            }else if(diferenciaFecha(document.getElementById('hasta_periodo').value, document.getElementById('desde_periodo').value)){

                           fun_msj('la Fecha desde no debe ser mayor a la fecha hasta');
                           return false;

                           }//fin else


}//fin function






function reporte_analitico_por_cuenta(){



                            if(document.getElementById('select_3').value==''){

			                   fun_msj('Inserte la Cuenta');
			                   document.getElementById('select_3').focus();
			                   return false;



                       }else if(document.getElementById('desde_periodo').value==''){

			                   fun_msj('Inserte la fecha del Periodo Desde');
			                   document.getElementById('desde_periodo').focus();
			                   return false;


			           }else if(document.getElementById('hasta_periodo').value==''){

			                   fun_msj('Inserte la fecha del Periodo Hasta');
			                   document.getElementById('hasta_periodo').focus();
			                   return false;

			            }else if(diferenciaFecha(document.getElementById('hasta_periodo').value, document.getElementById('desde_periodo').value)){

                           fun_msj('la Fecha desde no debe ser mayor a la fecha hasta');
                           return false;

                           }//fin else


}//fin function


function cambiar_frecuencia(){
	/*if(document.getElementById('tipo_gasto_6').checked==true || document.getElementById('tipo_gasto_1').checked==true || document.getElementById('tipo_gasto_2').checked==true){
		document.getElementById('tipo_recurso_7').checked=true;
	}

	if(document.getElementById('tipo_recurso_7').checked==true && document.getElementById('tipo_gasto_3').checked==true){
		document.getElementById('tipo_recurso_6').checked=true;
	}*/

    if(document.getElementById('frecuencia_5').checked==true){
           document.getElementById('seleccion_mes').style.display="none";
           document.getElementById('seleccion_tri').style.display="none";
           document.getElementById('seleccion_sem').style.display="none";
    }else{
        //document.getElementById().style.display="none";
    }
    if(document.getElementById('frecuencia_2').checked==true){
           document.getElementById('seleccion_mes').style.display="block";
    }else{
        document.getElementById('seleccion_mes').style.display="none";
    }
    if(document.getElementById('frecuencia_3').checked==true){
           document.getElementById('seleccion_tri').style.display="block";
    }else{
        document.getElementById('seleccion_tri').style.display="none";
    }
    if(document.getElementById('frecuencia_4').checked==true){
           document.getElementById('seleccion_sem').style.display="block";
    }else{
        document.getElementById('seleccion_sem').style.display="none";
    }
}

function cambiar_radio_gasto(){
	if(document.getElementById('tipo_recurso_1').checked==true || document.getElementById('tipo_recurso_2').checked==true || document.getElementById('tipo_recurso_3').checked==true || document.getElementById('tipo_recurso_4').checked==true || document.getElementById('tipo_recurso_5').checked==true || document.getElementById('tipo_recurso_6').checked==true){
		document.getElementById('tipo_gasto_3').checked=true;
	}
	if(document.getElementById('tipo_recurso_7').checked==true && document.getElementById('tipo_gasto_3').checked==true){
		document.getElementById('tipo_gasto_6').checked=true;
	}
}

function cambiar_tipo_gasto_reporte2a(){

   if(document.getElementById('tipo_gasto_0').checked==true || document.getElementById('tipo_gasto_1').checked==true || document.getElementById('tipo_gasto_2').checked==true){
		document.getElementById('tipo_recurso_7').checked=true;
	}

	if(document.getElementById('tipo_recurso_7').checked==true && document.getElementById('tipo_gasto_3').checked==true){
		document.getElementById('tipo_recurso_0').checked=true;
	}

}
function cambiar_tipo_gasto_reporte2b(){

    if(document.getElementById('tipo_recurso_1').checked==true || document.getElementById('tipo_recurso_2').checked==true || document.getElementById('tipo_recurso_3').checked==true || document.getElementById('tipo_recurso_4').checked==true || document.getElementById('tipo_recurso_5').checked==true || document.getElementById('tipo_recurso_0').checked==true){
		document.getElementById('tipo_gasto_3').checked=true;
	}
	if(document.getElementById('tipo_recurso_7').checked==true && document.getElementById('tipo_gasto_3').checked==true){
		document.getElementById('tipo_gasto_0').checked=true;
	}

}


function radio_consolidacion_islr_anual_r3(){
	document.getElementById('radio_empresa_especifica_2').checked=false;
	document.getElementById('radio_empresa_especifica_1').checked=true;
	document.getElementById('cuadro_demostrativo_anual_islr_seniatEmpresas').value='';
	//document.getElementById('cuadro_demostrativo_anual_islr_seniatEmpresas').disabled=true;
}


function radio_empresa_islr_anual_r3(){
	if(document.getElementById('radio_empresa_especifica_1').checked==true){
		document.getElementById('buscar_empresa_ordenpago').value='';
		document.getElementById('buscar_empresa_ordenpago').readOnly=true;
	}else if(document.getElementById('radio_empresa_especifica_2').checked==true){
		document.getElementById('buscar_empresa_ordenpago').readOnly=false;
		document.getElementById('cuadro_demostrativo_anual_islr_seniatEmpresas').disabled=false;
	}
}

function validar_reporte_pagos_subpartidas(){
	if(document.getElementById('ano').value==''){
        fun_msj('Por favor, ingrese el a&ntilde;o a generar.');
		document.getElementById('ano').focus();
		return false;
	}
	if($('opcion_reporte_2').checked==true){
		if(document.getElementById('fecha_inicial').value==''){
	        fun_msj('Por favor, seleccione la fecha desde.');
			document.getElementById('fecha_inicial').focus();
			return false;
		}if(document.getElementById('fecha_final').value==''){
	        fun_msj('Por favor, seleccione la fecha hasta.');
			document.getElementById('fecha_final').focus();
			return false;
		}
	}else if($('opcion_reporte_3').checked==true){
		if(document.getElementById('select_1').value==''){
	        fun_msj('Seleccione la entidad bancaria');
			document.getElementById('select_1').focus();
			return false;
		}if(document.getElementById('deno_entidad_bancaria').value==''){
		        fun_msj('La denominaci&oacute;n de la Entidad Bancaria no puede estar vac&iacute;a');
				document.getElementById('deno_entidad_bancaria').focus();
				return false;
		}if(document.getElementById('select_2').value==''){
		        fun_msj('Seleccione la sucursal bancaria');
				document.getElementById('select_2').focus();
				return false;
		}if(document.getElementById('deno_sucursal_bancaria').value==''){
		        fun_msj('La denominaci&oacute;n de la Sucursal Bancaria no puede estar vac&iacute;o');
				document.getElementById('deno_sucursal_bancaria').focus();
				return false;
		}if(document.getElementById('cuenta_bancaria').value==''){
		        fun_msj('Debe seleccionar el n&uacute;mero de la cuenta bancaria');
				document.getElementById('cuenta_bancaria').focus();
				return false;
		}if(document.getElementById('por_ano_2').checked==true){
				if(document.getElementById('fecha_inicial').value==''){
			        fun_msj('Por favor, seleccione la fecha desde.');
					document.getElementById('fecha_inicial').focus();
					return false;
				}if(document.getElementById('fecha_final').value==''){
			        fun_msj('Por favor, seleccione la fecha hasta.');
					document.getElementById('fecha_final').focus();
					return false;
				}    
		}
	}
}