function cscp04_ordencompra_servicio_bienes(){


 if(document.getElementById('ano')){

           if(document.getElementById('ano').value==""){
			fun_msj('Inserte el a&ntilde;o');
			document.getElementById('ano').focus();
			return false;

	  }else if(document.getElementById('numero_a')){

                 if(document.getElementById('numero_a').value==""){
			         fun_msj('Inserte el dato correspondiente');
			         document.getElementById('numero_a').focus();
			         return false;


			       }else if(document.getElementById('numero_b')){

                            if(document.getElementById('numero_b').value==""){
			                   fun_msj('Inserte el dato correspondiente');
			                  document.getElementById('numero_b').focus();
			                  return false;
                           }//fin if
			         }//fin if
			}//fin if
 }//fin if

}//fin funtion


function valida_reporte_solicitud_recurso(){
 	if(document.getElementById('ano').value==""){
			fun_msj('El a&ntilde;o no puede estar vacio');
			document.getElementById('ano').focus();
			return false;
	}
	if(document.getElementById('cod_solicitud').value==""){
           	fun_msj('Debe seleccionar un n&uacute;mero de solicitud');
			document.getElementById('cod_solicitud').focus();
			return false;
	}
}//valida_reporte_solicitud_recurso







function reporte_valida_comprobantes_cheque_libre(){

document.getElementById('distribuir').innerHTML='<table width="100%" cellspacing="0" cellpadding="0" id="grid"><tr bgcolor="#333"><td colspan="5" class="td2center" height="22">COLA DE IMPRESI&Oacute;N</td></tr><tr bgcolor="#333"><td class="td4"  height="22">A&ntilde;o movimiento</td><td class="td4" >C&oacute;digo Entidad Bancaria</td><td class="td4" >Cuenta Bancaria</td><td class="td4" >N&uacute;mero cheque</td></tr><tr bgcolor="#CDF2FF" class="textNegro2"><td class="td2center" height="22"  width="2">--</td><td class="td2center">--</td><td class="td2center">--</td><td class="td2center">--</td></tr></table>';
document.getElementById('enviar').disabled=true;

}//fin if





function reporte_valida_comprobantes_cheque(){

if(document.getElementById('ano').value!=""){

if(document.getElementById('opcion_1')){

document.getElementById('opcion_1').checked = false;
document.getElementById('opcion_2').checked = true;

 if(document.getElementById('opcion').value==""){
   document.getElementById('opcion').value="1";
   document.getElementById('salir').disabled=true;

   document.getElementById('send_window_pdf_impress').style.zIndex='5';
   document.getElementById('enviar').style.zIndex='10000';

   document.getElementById('send_window_pdf_impress').disabled=true;
   document.getElementById('enviar').disabled=false;

   Windows.close(document.getElementById('capa_ventana').value);

   fun_msj2('ahora genere por favor los comprobantes de retenciones');
 }else{
      document.getElementById('opcion').value="2";
      document.getElementById('salir').disabled=false;
 }//fin else

}else{

document.getElementById('distribuir').innerHTML='<table width="100%" cellspacing="0" cellpadding="0" id="grid"><tr bgcolor="#333"><td colspan="5" class="td2center" height="22">COLA DE IMPRESI&Oacute;N</td></tr><tr bgcolor="#333"><td class="td4"  height="22">A&ntilde;o movimiento</td><td class="td4" >C&oacute;digo Entidad Bancaria</td><td class="td4" >Cuenta Bancaria</td><td class="td4" >N&uacute;mero cheque</td></tr><tr bgcolor="#CDF2FF" class="textNegro2"><td class="td2center" height="22"  width="2">--</td><td class="td2center">--</td><td class="td2center">--</td><td class="td2center">--</td></tr></table>';
document.getElementById('enviar').disabled=true;

}//fin else

if(document.getElementById('salir').disable==true){
//document.getElementById('salir').disabled=false;
document.getElementById('distribuir').innerHTML='<table width="100%" cellspacing="0" cellpadding="0" id="grid"><tr bgcolor="#333"><td colspan="5" class="td2center" height="22">COLA DE IMPRESI&Oacute;N</td></tr><tr bgcolor="#333"><td class="td4"  height="22">A&ntilde;o movimiento</td><td class="td4" >C&oacute;digo Entidad Bancaria</td><td class="td4" >Cuenta Bancaria</td><td class="td4" >N&uacute;mero cheque</td></tr><tr bgcolor="#CDF2FF" class="textNegro2"><td class="td2center" height="22"  width="2">--</td><td class="td2center">--</td><td class="td2center">--</td><td class="td2center">--</td></tr></table>';
document.getElementById('enviar').disabled=true;
}//fin if


}else{
			fun_msj('Inserte el a&ntilde;o');
			document.getElementById('ano').focus();
			return false;
}//fin else

}//fin



function valida_frm_reporte_proveedores_cpcd02(){

if((document.getElementById('tipo_proveedores_1').checked==false) && (document.getElementById('tipo_proveedores_2').checked==false) && (document.getElementById('tipo_proveedores_3').checked==false) && (document.getElementById('tipo_proveedores_4').checked==false) && (document.getElementById('tipo_proveedores_5').checked==false)){
	fun_msj('Seleccione que reporte desea generar, por favor');
	return false;
}

if((document.getElementById('ordenados_por_1').checked==false) && (document.getElementById('ordenados_por_2').checked==false) && (document.getElementById('ordenados_por_3').checked==false) && (document.getElementById('ordenados_por_4').checked==false)){
	fun_msj('Seleccione de que manera desea ordenar el reporte, RIF, Ramo Comercial...');
	return false;
}
}//valida_frm_reporte_proveedores_cpcd02




function valida_reporte_relacion_ordenpago(opcion){
if(opcion==1){
		if(document.getElementById('ano').value==""){
					fun_msj('El a&ntilde;o no puede estar vacio');
					document.getElementById('ano').focus();
					return false;
		}if(document.getElementById('ano').value.length<4){
					fun_msj('El a&ntilde;o debe contener 4 d&iacute;gitos');
					document.getElementById('ano').focus();
					return false;
		}if((document.getElementById('estilo_reporte_1').checked==false) && (document.getElementById('estilo_reporte_2').checked==false)){
					fun_msj('Seleccione como desea generar el reporte, por favor');
					return false;
		}

		if(document.getElementById('estilo_reporte_2').checked==true){
			if(document.getElementById('beneficiarios').value==""){
					fun_msj('Seleccione el beneficiario, por favor');
					document.getElementById('beneficiarios').focus();
					return false;
			}
		}
}else if(opcion==2){
		if(document.getElementById('ano').value==""){
					fun_msj('El a&ntilde;o no puede estar vacio');
					document.getElementById('ano').focus();
					return false;
		}if(document.getElementById('ano').value.length<4){
					fun_msj('El a&ntilde;o debe contener 4 d&iacute;gitos');
					document.getElementById('ano').focus();
					return false;
		}if((document.getElementById('estilo_reporte_1').checked==false) && (document.getElementById('estilo_reporte_2').checked==false) && (document.getElementById('estilo_reporte_3').checked==false)){
					fun_msj('Seleccione como desea generar el reporte, por favor');
					return false;
		}

		if(document.getElementById('estilo_reporte_2').checked==true){
			if(document.getElementById('razonsocial').value==""){
					fun_msj('Seleccione la razon social, por favor');
					document.getElementById('razonsocial').focus();
					return false;
			}
		}


		if((document.getElementById('opcionfecha_1').checked==false) && (document.getElementById('opcionfecha_2').checked==false)){
					fun_msj('Desea generar todos, o generar por fecha');
					return false;
		}

		if(document.getElementById('opcionfecha_2').checked==true){
			if(document.getElementById('fecha_inicial').value==""){
					fun_msj('Por favor seleccione la fecha de inicio');
					document.getElementById('fecha_inicial').focus();
					return false;
			}else if(document.getElementById('fecha_final').value==""){
					fun_msj('Por favor seleccione la fecha final');
					document.getElementById('fecha_final').focus();
					return false;
			}
		}

}else if(opcion==3){//reporte especial de relacion de orden de pago donde pide tambien la opcion del tipo de documento.
		if(document.getElementById('ano').value==""){
					fun_msj('El a&ntilde;o no puede estar vacio');
					document.getElementById('ano').focus();
					return false;
		}if(document.getElementById('ano').value.length<4){
					fun_msj('El a&ntilde;o debe contener 4 d&iacute;gitos');
					document.getElementById('ano').focus();
					return false;
		}if((document.getElementById('estilo_reporte_1').checked==false) && (document.getElementById('estilo_reporte_2').checked==false) && (document.getElementById('estilo_reporte_3').checked==false) && (document.getElementById('estilo_reporte_4').checked==false) && (document.getElementById('estilo_reporte_5').checked==false)){
					fun_msj('Seleccione como desea generar el reporte, por favor');
					return false;
		}

		if(document.getElementById('estilo_reporte_2').checked==true){
			if(document.getElementById('beneficiarios').value==""){
					fun_msj('Seleccione el beneficiario, por favor');
					document.getElementById('beneficiarios').focus();
					return false;
			}
		}

		if(document.getElementById('estilo_reporte_3').checked==true){
			if(document.getElementById('tipopago').value==""){
					fun_msj('Seleccione el tipo de pago, por favor');
					document.getElementById('tipopago').focus();
					return false;
			}
		}



		if((document.getElementById('opcionfecha_1').checked==false) && (document.getElementById('opcionfecha_2').checked==false)){
					fun_msj('Desea generar todos, o generar por fecha');
					return false;
		}

		if(document.getElementById('opcionfecha_2').checked==true){
			if(document.getElementById('fecha_inicial').value==""){
					fun_msj('Por favor seleccione la fecha de inicio');
					document.getElementById('fecha_inicial').focus();
					return false;
			}else if(document.getElementById('fecha_final').value==""){
					fun_msj('Por favor seleccione la fecha final');
					document.getElementById('fecha_final').focus();
					return false;
			}
		}

}else if(opcion==4){//reporte especial de relacion de compromisos donde pide tambien la opcion del tipo de documento.
		if(document.getElementById('ano').value==""){
					fun_msj('El a&ntilde;o no puede estar vacio');
					document.getElementById('ano').focus();
					return false;
		}if(document.getElementById('ano').value.length<4){
					fun_msj('El a&ntilde;o debe contener 4 d&iacute;gitos');
					document.getElementById('ano').focus();
					return false;
		}if((document.getElementById('estilo_reporte_1').checked==false) && (document.getElementById('estilo_reporte_2').checked==false) && (document.getElementById('estilo_reporte_3').checked==false)){
					fun_msj('Seleccione que como desea generar el reporte, por favor');
					return false;
		}

		if(document.getElementById('estilo_reporte_2').checked==true){
			if(document.getElementById('beneficiarios').value==""){
					fun_msj('Seleccione el beneficiario, por favor');
					document.getElementById('beneficiarios').focus();
					return false;
			}
		}

		if(document.getElementById('estilo_reporte_3').checked==true){
			if(document.getElementById('tipocompromiso').value==""){
					fun_msj('Seleccione el tipo de Compromiso, por favor');
					document.getElementById('tipocompromiso').focus();
					return false;
			}
		}

		if((document.getElementById('opcionfecha_1').checked==false) && (document.getElementById('opcionfecha_2').checked==false)){
					fun_msj('Desea generar todos, o generar por fecha');
					return false;
		}

		if(document.getElementById('opcionfecha_2').checked==true){
			if(document.getElementById('fecha_inicial').value==""){
					fun_msj('Por favor seleccione la fecha de inicio');
					document.getElementById('fecha_inicial').focus();
					return false;
			}else if(document.getElementById('fecha_final').value==""){
					fun_msj('Por favor seleccione la fecha final');
					document.getElementById('fecha_final').focus();
					return false;
			}
		}
}

}//valida_reporte_relacion_ordenpago



function reporte_cobd01_saldo_contrato(opcion){
	if(opcion==1){
		if(document.getElementById('ano').value==""){
					fun_msj('El a&ntilde;o no puede estar vacio');
					document.getElementById('ano').focus();
					return false;
		}if(document.getElementById('ano').value.length<4){
					fun_msj('El a&ntilde;o debe contener 4 d&iacute;gitos');
					document.getElementById('ano').focus();
					return false;
		}
	}
}//fin reporte_cobd01_saldo_contrato



function valida_reporte_cuadro_disponibilidadbanc(){
		if(document.getElementById('ano').value==""){
					fun_msj('El a&ntilde;o no puede estar vacio');
					document.getElementById('ano').focus();
					return false;
		}if(document.getElementById('fecha').value==""){
					fun_msj('La fecha no puede estar vacia, por favor');
					document.getElementById('fecha').focus();
					return false;
		}
}


//**************************************
//      Funciones del reporte2
//**************************************
function mostrar_b_modifar_cugd07_firmas(){
	mo = document.getElementById('mostOc'); // este es nuestro objeto
	mo22 = document.getElementById('mostOc-22');
	if(mo.style.display==""){
	mo.style.display = "none" // ocultamos
	mo22.style.display = ""
	} else {
	mo.style.display = "" // mostramos
	mo22.style.display = "none"
	}

	document.getElementById('nombre_primera_firma').readOnly=false;
	document.getElementById('cargo_primera_firma').readOnly=false;
	document.getElementById('nombre_segunda_firma').readOnly=false;
	document.getElementById('cargo_segunda_firma').readOnly=false;
	document.getElementById('nombre_tercera_firma').readOnly=false;
	document.getElementById('cargo_tercera_firma').readOnly=false;
	document.getElementById('nombre_cuarta_firma').readOnly=false;
	document.getElementById('cargo_cuarta_firma').readOnly=false;

	document.getElementById('primera_copia').readOnly=false;
	document.getElementById('segunda_copia').readOnly=false;
	document.getElementById('tercera_copia').readOnly=false;
	document.getElementById('cuarta_copia').readOnly=false;
	document.getElementById('quinta_copia').readOnly=false;
	document.getElementById('sexta_copia').readOnly=false;
	document.getElementById('septima_copia').readOnly=false;
	document.getElementById('octava_copia').readOnly=false;
	fun_msj2('Puede modificar los datos');
}

function valida_cugd07_firmas(){
	if(document.getElementById('nombre_primera_firma').readOnly=false){

	}

	if(document.getElementById('nombre_primera_firma').value==""){
					fun_msj('Debe ingresar el nombre de la primera firma');
					document.getElementById('nombre_primera_firma').focus();
					return false;
		}if(document.getElementById('cargo_primera_firma').value==""){
					fun_msj('Debe ingresar el cargo de la primera firma');
					document.getElementById('cargo_primera_firma').focus();
					return false;
		}
	if(document.getElementById('nombre_segunda_firma').value==""){
					fun_msj('Debe ingresar el nombre de la segunda firma');
					document.getElementById('nombre_segunda_firma').focus();
					return false;
		}if(document.getElementById('cargo_segunda_firma').value==""){
					fun_msj('Debe ingresar el cargo de la segunda firma');
					document.getElementById('cargo_segunda_firma').focus();
					return false;
		}
	if(document.getElementById('nombre_tercera_firma').value==""){
					fun_msj('Debe ingresar el nombre de la tercera firma');
					document.getElementById('nombre_tercera_firma').focus();
					return false;
		}if(document.getElementById('cargo_tercera_firma').value==""){
					fun_msj('Debe ingresar el cargo de la tercera firma');
					document.getElementById('cargo_tercera_firma').focus();
					return false;
		}

		if(document.getElementById('primera_copia').value==""){
					fun_msj('Debe ingresar la oficina que recibira la primera copia');
					document.getElementById('primera_copia').focus();
					return false;
		}else if(document.getElementById('segunda_copia').value==""){
					fun_msj('Debe ingresar la oficina que recibira la segunda copia');
					document.getElementById('segunda_copia').focus();
					return false;
		}else if(document.getElementById('tercera_copia').value==""){
					fun_msj('Debe ingresar la oficina que recibira la tercera copia');
					document.getElementById('tercera_copia').focus();
					return false;
		}else if(document.getElementById('cuarta_copia').value==""){
					fun_msj('Debe ingresar la oficina que recibira la cuarta copia');
					document.getElementById('cuarta_copia').focus();
					return false;
		}else if(document.getElementById('quinta_copia').value==""){
					fun_msj('Debe ingresar la oficina que recibira la quinta copia');
					document.getElementById('quinta_copia').focus();
					return false;
		}else if(document.getElementById('sexta_copia').value==""){
					fun_msj('Debe ingresar la oficina que recibira la sexta copia');
					document.getElementById('sexta_copia').focus();
					return false;
		}else if(document.getElementById('septima_copia').value==""){
					fun_msj('Debe ingresar la oficina que recibira la septima copia');
					document.getElementById('septima_copia').focus();
					return false;
		}else if(document.getElementById('octava_copia').value==""){
					fun_msj('Debe ingresar la oficina que recibira la octava copia');
					document.getElementById('octava_copia').focus();
					return false;
		}
}

//Funcion del programa arrp05_busqueda usuario.
//Descripcion: se encarga de habilitar y deshabilitar el select.
function arrp05_habilita_select_dep(){
	select_vacio = document.getElementById('select_dep_vacio')
	select_lleno = document.getElementById('select_dep')
	if(document.getElementById('por_dependencia_1').checked==true){
		fun_msj2('Ahora puede seleccionar la dependencia');
		select_vacio.style.display = "none"
		select_lleno.style.display = ""
		document.getElementById('busquedadep').readOnly=false;
	}else if(document.getElementById('por_dependencia_2').checked==true){
		select_lleno.style.display = "none"
		select_vacio.style.display = ""
		document.getElementById('busquedadep').readOnly=true;
	}
}


//Funcion del programa constancia_proveedores_cont para modificar los firmantes.
function firmas_cpcp02_modificado(){
 	document.getElementById('firmas_modificadas').value='1';
}


function radio_reporte_diario_nomina_1(){
	document.getElementById('opcion_ordenar_3_3').checked=false;
}

function radio_reporte_diario_nomina_2(){
	document.getElementById('opcion_ordenar_1_1').checked='';
	document.getElementById('opcion_ordenar_2_2').checked='';
}

function condicion_balance_personal(){
    var seccion_ejecucion = document.getElementById("seccion_ejecucion").style;
    var seccion_fecha = document.getElementById("seccion_fecha").style;
	if(document.getElementById('modo_1').checked==true){
    seccion_ejecucion.display = "table-row";
    seccion_fecha.display = "none";
  }else if(document.getElementById('modo_2').checked==true){
    seccion_ejecucion.display = "none";
    seccion_fecha.display = "table-row";
  }
}

function validar_pdf_balance_personal(){
	if (
    document.getElementById('modo_1').checked == false &&
    document.getElementById('modo_2').checked == false
  ) {
    fun_msj("Debe seleccionar el tipo de formato");
    return false;
  }

  if(document.getElementById('modo_1').checked == true && document.getElementById('ano').value=='') {
  	fun_msj("Debe indicar el Año");
  	return false;
  }

  return true;
}