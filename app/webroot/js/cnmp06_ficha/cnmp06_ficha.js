function valida_cnmp06_ficha(){
       if(document.getElementById('select_nomina').value==""){

			fun_msj('Seleccione el Tipo de N&oacute;mina');
			document.getElementById('select_nomina').focus();
			return false;

}else if(document.getElementById('i_cod_cargo').value==""){

			fun_msj('Por favor realice la busqueda del cargo');
			document.getElementById('i_cod_cargo').focus();
			return false;

}else if(document.getElementById('cedula').value==""){

			fun_msj('Por favor realice la busqueda de la persona');
			document.getElementById('cedula').focus();
			return false;

}else if(document.getElementById('fecha_ingreso').value==""){

			fun_msj('Ingrese la Fecha de Ingreso');
			document.getElementById('fecha_ingreso').focus();
			return false;


}else if((document.getElementById('forma_de_pago').value=="3") && (document.getElementById('y_1').value=="")){

			fun_msj('Seleccione la entidad bancaria');
			document.getElementById('y_1').focus();
			return false;
}else if((document.getElementById('forma_de_pago').value=="3") && (document.getElementById('y_2').value=="")){

			fun_msj('Seleccione la sucursal bancaria');
			document.getElementById('y_2').focus();
			return false;
}else if((document.getElementById('forma_de_pago').value=="3") && (document.getElementById('cod_cuenta').value=="")){

			fun_msj('Ingrese la cuenta bancaria');
			document.getElementById('cod_cuenta').focus();
			return false;
}else if(document.getElementById('condicion').value==""){

			fun_msj('Ingrese la Condici&oacute;n de Actividad');
			document.getElementById('condicion').focus();
			return false;

}else if(document.getElementById('funciones_realizar').value==""){

			fun_msj('Ingrese las Funciones a Realidad');
			document.getElementById('funciones_realizar').focus();
			return false;

}else if(document.getElementById('responsabilidad').value==""){

			fun_msj('Ingrese la Responsabilidad');
			document.getElementById('responsabilidad').focus();
			return false;

}else if(document.getElementById('horas_laborar').value==""){

			fun_msj('Ingrese las Horas Laborables');
			document.getElementById('horas_laborar').focus();
			return false;

}else if(document.getElementById('horas_laborar').value=="0,00"){

			fun_msj('Las Horas Laborables deben ser mayor de cero');
			document.getElementById('horas_laborar').value="";
			document.getElementById('horas_laborar').focus();
			return false;

}else if(document.getElementById('paso_input').value==""){

			fun_msj('Ingrese el paso');
			document.getElementById('paso_input').focus();
			return false;

}else if(document.getElementById('tipo_contrato').value==""){

			fun_msj('Ingrese el tipo de contrato');
			document.getElementById('tipo_contrato').focus();
			return false;

}else{


    if(document.getElementById('radio_2')){
		 if(document.getElementById('radio_2').checked==true){
				if(document.getElementById('numero_input').value==""){
							fun_msj('Ingrese el C&oacute;digo de la Ficha');
							document.getElementById('numero_input').focus();
							return false;
				}//fin if
		}//fin if
	  }//fin if


}//fin else



}//fin funcion validar
function limpia_b(){
  document.getElementById('buscar2').innerHTML="";
}







function desbloquear_motivo_ficha(id, var1){


		if(document.getElementById(id).value=="6"){
                 if(document.getElementById("motivo")){             document.getElementById("motivo").disabled               = false;}
                 if(document.getElementById("fecha_retiro")){       document.getElementById("fecha_retiro").value            = "";}
                 if(document.getElementById("fecha_retiro_imagen")){document.getElementById("fecha_retiro_imagen").disabled  = false;}
		}else{
                 if(document.getElementById("motivo")){             document.getElementById("motivo").disabled               = true;}
                 if(document.getElementById("fecha_retiro")){       document.getElementById("fecha_retiro").value            = "";}
                 if(document.getElementById("fecha_retiro_imagen")){document.getElementById("fecha_retiro_imagen").disabled  = true;}

		}//fin else



       if(var1){
		        if(document.getElementById(id).value!=var1){
		            ver_documento("/cnmp06_ficha/funcion_1/1", "funcion2");
		        }
		}


}//fin function






function desbloquear_cuentas(id){

		if(document.getElementById(id).value!="3"){

                 document.getElementById("y_1").disabled               = true;
                 document.getElementById("y_2").disabled               = true;
                 document.getElementById("cod_cuenta").disabled        = true;
                 document.getElementById("cod_cuenta").value           = "";


                 document.getElementById("y_1").options[0].value = "";
				 document.getElementById("y_1").options[0].text = "";
				 document.getElementById("y_1").options[0].selected = true;

				 document.getElementById("y_2").options[0].value = "";
				 document.getElementById("y_2").options[0].text = "";
				 document.getElementById("y_2").options[0].selected = true;

				 if(document.getElementById("cuenta_separada")){document.getElementById("cuenta_separada").innerHTML="";}

				 document.getElementById("cod_cuenta").className="inputtext";



		}else{

                 document.getElementById("y_1").disabled               = false;
                 document.getElementById("y_2").disabled               = false;
                 document.getElementById("cod_cuenta").disabled        = false;
                 document.getElementById("cod_cuenta").value           = "";


                 document.getElementById("y_1").options[0].value = "";
				 document.getElementById("y_1").options[0].text = "";
				 document.getElementById("y_1").options[0].selected = true;

				 document.getElementById("y_2").options[0].value = "";
				 document.getElementById("y_2").options[0].text = "";
				 document.getElementById("y_2").options[0].selected = true;

				 if(document.getElementById("cuenta_separada")){document.getElementById("cuenta_separada").innerHTML="";}

				 document.getElementById("cod_cuenta").className="inputtext";





		}//fin else

}//fin function










