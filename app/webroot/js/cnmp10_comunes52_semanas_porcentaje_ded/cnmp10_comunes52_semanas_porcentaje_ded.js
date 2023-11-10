function cnmp10_comunes52_semanas_porcentaje_ded_valida(){




         if(document.getElementById('porcentaje').value==''){

			fun_msj('Inserte el porcentaje');
			document.getElementById('porcentaje').focus();
			return false;

	}else if(document.getElementById('tope_cuarta_semana').value==''){

			fun_msj('Inserte el tope de la cuarta semana');
			document.getElementById('tope_cuarta_semana').focus();
			return false;


	}else if(document.getElementById('tope_quinta_semana').value==''){

			fun_msj('Inserte el tope de la quinta semana');
			document.getElementById('tope_quinta_semana').focus();
			return false;


	}//fin else





}//fin function