function cepp02_contratoservicio_valida(){


          if(document.getElementById('ano').value == ""){

            fun_msj('Inserte el a&ntilde;o del contrato');
			document.getElementById('ano').focus();
			return false;



	}else if(document.getElementById('numero_contrato').value==""){

			fun_msj('Inserte el N&uacute;mero de contrato');
			document.getElementById('numero_contrato').focus();
			return false;



	}else if(document.getElementById('select_1').value == ""){

            fun_msj('Inserte la direcci&oacute;n superior');
			document.getElementById('select_1').focus();
			return false;

	}else if(document.getElementById('select_2').value == ""){

            fun_msj('Inserte la coordinaci&oacute;n');
			document.getElementById('select_2').focus();
			return false;

	}else if(document.getElementById('select_3').value == ""){

            fun_msj('Inserte la secretaria');
			document.getElementById('select_3').focus();
			return false;

	}else if(document.getElementById('select_4').value == ""){

            fun_msj('Inserte la direcci&oacute;n');
			document.getElementById('select_4').focus();
			return false;

	}else if(document.getElementById('rif').value == ""){

            fun_msj('Inserte el Rif del proveedor');
			document.getElementById('rif').focus();
			return false;

	}else if(document.getElementById('bene').value == ""){

            fun_msj('Inserte el nombre del proveedor o Raz&oacute;n Social');
			document.getElementById('bene').focus();
			return false;

	}else if(document.getElementById('concepto').value == ""){

            fun_msj('Inserte el concepto del contrato');
			document.getElementById('concepto').focus();
			return false;

	}else if(document.getElementById('fecha_contrato').value == ""){

            fun_msj('Inserte la fecha del contrato');
			document.getElementById('fecha_contrato').focus();
			return false;

	}else if(verifica_cierre_ano_ejecucion('fecha_contrato')==false){
		fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA DEL CONTRATO NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
		return false;
	}else if(document.getElementById('fecha_inicio').value == ""){

            fun_msj('Inserte fecha de inicio');
			document.getElementById('fecha_inicio').focus();
			return false;

	}else if(document.getElementById('fecha_terminacion').value == ""){

            fun_msj('Inserte la fecha de terminaci&oacute;n');
			document.getElementById('fecha_terminacion').focus();
			return false;


     }else if(valida_fechas_documentos_mayores(4)==2){

      						 return false;


	 }else if(diferenciaFecha(document.getElementById('fecha_inicio').value, document.getElementById('fecha_contrato').value)){

             if (confirm("la Fecha de inicio es mayor a la fecha de contrato, desea aceptar ")) {
							          if(diferenciaFecha(document.getElementById('fecha_terminacion').value, document.getElementById('fecha_inicio').value)){
							            fun_msj('la Fecha de terminaci&oacute;n debe ser mayor a la de inicio');
							             return false;
								}else if(document.getElementById('cuenta_i')){
										if(document.getElementById('cuenta_i').value=="0"){
							              fun_msj('No existe imputaci&oacute;n presupuestaria');
										  return false;
										}//fin if
							     }else if(!document.getElementById('cuenta_i')){
							              fun_msj('No existe imputaci&oacute;n presupuestaria');
										  return false;
								}//fin else
              }else{
			            return false;
	                }//fin elswe




   }else if(diferenciaFecha(document.getElementById('fecha_terminacion').value, document.getElementById('fecha_inicio').value)){
             fun_msj('la Fecha de terminaci&oacute;n debe ser mayor a la de inicio');
             return false;
	}else if(document.getElementById('cuenta_i')){
			if(document.getElementById('cuenta_i').value=="0"){
              fun_msj('No existe imputaci&oacute;n presupuestaria');
			  return false;
			}//fin if
     }else if(!document.getElementById('cuenta_i')){
              fun_msj('No existe imputaci&oacute;n presupuestaria');
			  return false;
	}//fin else




if(diferenciaFecha(document.getElementById('fecha_contrato').value, document.getElementById('fecha_comparar').value)){

         if(document.getElementById('fecha_actual').value!=""){
           return verifica_cierre_mes_ejecucion('fecha_actual');
         }//fin if

}else{

         if(document.getElementById('fecha_contrato').value!=""){
           return verifica_cierre_mes_ejecucion('fecha_contrato');
         }//fin if

}// fin else







}//fin function
















