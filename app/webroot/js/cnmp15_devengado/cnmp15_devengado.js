function cnmp15_devengado_valida(){

          if(document.getElementById('escala').value == ""){

            fun_msj('Inserte la Escala ');
			document.getElementById('escala').focus();
			return false;

	}else if(document.getElementById('fecha_desde').value == ""){

            fun_msj('Inserte la Fecha Desde');
			document.getElementById('fecha_desde').focus();
			return false;

	}else if(document.getElementById('fecha_hasta').value == ""){

            fun_msj('Inserte la Fecha Hasta');
			document.getElementById('fecha_hasta').focus();
			return false;

	}else if(diferenciaFecha(document.getElementById('fecha_hasta').value, document.getElementById('fecha_desde').value)){

                          fun_msj('la Fecha Hasta debe ser mayor a la fecha Desde');
                           return false;

	}else if(document.getElementById('sueldo_salario').value == ""){

            fun_msj('Inserte el Sueldo o Salario');
			document.getElementById('sueldo_salario').focus();
			return false;

	}else if(document.getElementById('compensaciones').value == ""){

            fun_msj('Inserte las Compensaciones');
			document.getElementById('compensaciones').focus();
			return false;

	}else if(document.getElementById('sueldo_salario').value == "0,00"){

            fun_msj('Inserte el Sueldo o Salario');
			document.getElementById('sueldo_salario').focus();
			return false;

    }else if(document.getElementById('compensaciones').value == "0,00"){

            fun_msj('Inserte el Sueldo o Salario');
			document.getElementById('compensaciones').focus();
			return false;



	}//fin else



}//fin function









function calcular_semana_adicional(id){

	if(id=="no"){

				sueldo_salario  = retornar_valor_calculo(document.getElementById('sueldo_salario').value);
				sueldo_salario2 = eval(sueldo_salario)/eval(30);

				calculo_6      = retornar_valor_calculo(document.getElementById('calculo_6').value);
				calculo_8      = retornar_valor_calculo(document.getElementById('calculo_8').value);

				calculo_1 = eval(document.getElementById('calculo_9').value)/eval(12);
				calculo_1 = redondear(calculo_1,3);

				document.getElementById('calculo_4').value=calculo_1;

				calculo_10 = eval(calculo_1) * eval(sueldo_salario2);
				calculo_10 = redondear(calculo_10,2);

				compensaciones = eval(sueldo_salario) + eval(calculo_6) + eval(calculo_8) + eval(calculo_10);
				compensaciones = redondear(compensaciones,2);

                document.getElementById('calculo_10').value     = calculo_10;
				document.getElementById('compensaciones').value = compensaciones;

				moneda('calculo_10');
				moneda('compensaciones');


	}else{

	            sueldo_salario  = retornar_valor_calculo(document.getElementById('sueldo_salario_'+id).value);
	            sueldo_salario2 = eval(sueldo_salario)/eval(30);

				calculo_6      = retornar_valor_calculo(document.getElementById('calculo_6_'+id).value);
				calculo_8      = retornar_valor_calculo(document.getElementById('calculo_8_'+id).value);

				calculo_1 = eval(document.getElementById('calculo_9_'+id).value)/eval(12);
				calculo_1 = redondear(calculo_1,3);

				document.getElementById('calculo_4_'+id).value=calculo_1;

				calculo_10 = eval(calculo_1) * eval(sueldo_salario2);
				calculo_10 = redondear(calculo_10,2);


				compensaciones = eval(sueldo_salario) + eval(calculo_6) + eval(calculo_8) + eval(calculo_10);
				compensaciones = redondear(compensaciones,2);

                document.getElementById('calculo_10_'+id).value     = calculo_10;
				document.getElementById('compensaciones_'+id).value = compensaciones;

				moneda('calculo_10_'+id);
				moneda('compensaciones_'+id);



	}
}







function cnmp15_devengado_calcular(){


         if(document.getElementById('fecha_desde').value == ""){

            fun_msj('Inserte la Fecha Desde');
			document.getElementById('fecha_desde').focus();
			return false;

	}else if(document.getElementById('fecha_hasta').value == ""){

            fun_msj('Inserte la Fecha Hasta');
			document.getElementById('fecha_hasta').focus();
			return false;

	}else if(diferenciaFecha(document.getElementById('fecha_hasta').value, document.getElementById('fecha_desde').value)){

                          fun_msj('la Fecha Hasta debe ser mayor a la fecha Desde');
                           return false;

	}else if(document.getElementById('sueldo_salario').value == ""){

            fun_msj('Inserte el Sueldo o Salario');
			document.getElementById('sueldo_salario').focus();
			return false;

	}else if(document.getElementById('sueldo_salario').value == "0,00"){

            fun_msj('Inserte el Sueldo o Salario');
			document.getElementById('sueldo_salario').focus();
			return false;


	}//fin else


}//fin function



function redondeocampo_sueldo_basico(id_cam){

	moneda(id_cam);

	/*
	id_sueldo_basico = eval(document.getElementById('id_sueldo_basico').value);
	redondeadavar = redondear(id_sueldo_basico,2);
	document.getElementById('id_sueldo_basico').value = redondeadavar;
	*/

}

