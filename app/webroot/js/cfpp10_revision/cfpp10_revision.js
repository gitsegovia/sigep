function valida_cfpp10_revision(){
	var flag="malo";


	if(document.getElementById('select_1').value==''){

			fun_msj('Por favor seleccione un oficio de reformulacion presupuestaria');
			document.getElementById('select_1').focus();
			return false;

	}


	if(document.getElementById('cod_tipo_refo').value==1){
		//alert('es uno (1) traslado entre partidas');
		if(eval(document.getElementById('total1').value) != eval(document.getElementById('total2').value)){
			fun_msj('Lo siento no puede avanzar, los totales no coinciden entre si');
			document.getElementById('monto_reformulacion').focus();
			return false;
		}else{
			if(eval(document.getElementById('monto_reformulacion').value) != eval(document.getElementById('total1').value)){
				fun_msj('No puede avanzar, el monto de la reformulacion es diferente al total');
				document.getElementById('monto_reformulacion').focus();
				return false;
			}else{
				var flag="bueno";
			}
		}
	}




	if((document.getElementById('cod_tipo_refo').value==2) || (document.getElementById('cod_tipo_refo').value==4) || (document.getElementById('cod_tipo_refo').value==5) || (document.getElementById('cod_tipo_refo').value==6) || (document.getElementById('cod_tipo_refo').value==7) || (document.getElementById('cod_tipo_refo').value==8)){
		//alert('es dos (2) -- creditos adicionales');
		if(eval(document.getElementById('total2').value) != eval(document.getElementById('monto_reformulacion').value)){
			fun_msj('No puede avanzar, el total del aumento es diferente al monto de la reformulacion');
			document.getElementById('monto_reformulacion').focus();
			return false;
		}else if(document.getElementById('total1').value!=0){
			fun_msj('No puede avanzar, no puede haber un monto en la parte de la disminucion');
			document.getElementById('monto_reformulacion').focus();
			return false;
		}else{
				var flag="bueno";
		}
	}



	if(document.getElementById('cod_tipo_refo').value==3){
		//alert('es tres (3) -- rebaja presupuestaria');
		if(eval(document.getElementById('total1').value) != eval(document.getElementById('monto_reformulacion').value)){
			fun_msj('No puede avanzar, el total de la disminucion es diferente al monto de la reformulacion');
			document.getElementById('monto_reformulacion').focus();
			return false;
		}else if(document.getElementById('total2').value!=0){
			fun_msj('No puede avanzar, no puede haber un monto en la parte de incremento');
			document.getElementById('monto_reformulacion').focus();
			return false;
		}else{
				var flag="bueno";
		}
	}



	if(flag=='malo'){
		fun_msj('Lo siento no puede avanzar, los datos del oficio de reformulacion no estan correctos');
		document.getElementById('monto_reformulacion').focus();
		return false;
	}
}


function valida_cfpp10_revision_aprob(){
	var flag="malo";

	if(document.getElementById('select_1').value==''){

			fun_msj('Por favor seleccione un oficio de reformulacion presupuestaria');
			document.getElementById('select_1').focus();
			return false;

	}

	if(document.getElementById('cod_tipo_refo').value==1){
		//alert('es uno (1) traslado entre partidas');
		if(eval(document.getElementById('total1').value) != eval(document.getElementById('total2').value)){
			fun_msj('Lo siento no puede avanzar, los totales no coinciden entre si');
			document.getElementById('monto_reformulacion').focus();
			return false;
		}else{
			if(eval(document.getElementById('monto_reformulacion').value) != eval(document.getElementById('total1').value)){
				fun_msj('No puede avanzar, el monto de la reformulacion es diferente al total');
				document.getElementById('monto_reformulacion').focus();
				return false;
			}else{
				var flag="bueno";
			}
		}
	}

	if((document.getElementById('cod_tipo_refo').value==2) || (document.getElementById('cod_tipo_refo').value==4) || (document.getElementById('cod_tipo_refo').value==5) || (document.getElementById('cod_tipo_refo').value==6) || (document.getElementById('cod_tipo_refo').value==7) || (document.getElementById('cod_tipo_refo').value==8)){
		//alert('es dos (2) -- creditos adicionales');
		if(eval(document.getElementById('total2').value) != eval(document.getElementById('monto_reformulacion').value)){
			fun_msj('No puede avanzar, el total del aumento es diferente al monto de la reformulacion');
			document.getElementById('monto_reformulacion').focus();
			return false;
		}else if(document.getElementById('total1').value!=0){
			fun_msj('No puede avanzar, no puede haber un monto en la parte de la disminucion');
			document.getElementById('monto_reformulacion').focus();
			return false;
		}else{
				var flag="bueno";
		}
	}

	if(document.getElementById('cod_tipo_refo').value==3){
		//alert('es tres (3) -- rebaja presupuestaria');
		if(eval(document.getElementById('total1').value) != eval(document.getElementById('monto_reformulacion').value)){
			fun_msj('No puede avanzar, el total de la disminucion es diferente al monto de la reformulacion');
			document.getElementById('monto_reformulacion').focus();
			return false;
		}else if(document.getElementById('total2').value!=0){
			fun_msj('No puede avanzar, no puede haber un monto en la parte de incremento');
			document.getElementById('monto_reformulacion').focus();
			return false;
		}else{
				var flag="bueno";
		}
	}

	if(flag=='malo'){
		fun_msj('Lo siento no puede avanzar, los datos del oficio de reformulacion no estan correctos');
		document.getElementById('monto_reformulacion').focus();
		return false;
	}



	if(document.getElementById('numero_apro').value==''){

			fun_msj('Debe Ingresar el numero de aprobacion de la reformulacion presupuestaria');
			document.getElementById('numero_apro').focus();
			return false;

	}if(document.getElementById('fecha_apro').value==''){

			fun_msj('Debe Seleccionar la fecha de aprobacion de la reformulacion presupuestaria');
			document.getElementById('fecha_apro').focus();
			return false;

	}
}


function valida_cfpp10_revision2(){
	var flag="malo";

	if(document.getElementById('numero_consejo').value==''){

			fun_msj('Por favor ingrese el numero de oficio del Consejo Legislativo');
			document.getElementById('numero_consejo').focus();
			return false;

	}

	if(document.getElementById('select_1').value==''){

			fun_msj('Por favor seleccione un oficio de reformulacion presupuestaria');
			document.getElementById('select_1').focus();
			return false;

	}


	if(document.getElementById('cod_tipo_refo').value==1){
		//alert('es uno (1) traslado entre partidas');
		if(eval(document.getElementById('total1').value) != eval(document.getElementById('total2').value)){
			fun_msj('Lo siento no puede avanzar, los totales no coinciden entre si');
			document.getElementById('monto_reformulacion').focus();
			return false;
		}else{
			if(eval(document.getElementById('monto_reformulacion').value) != eval(document.getElementById('total1').value)){
				fun_msj('No puede avanzar, el monto de la reformulacion es diferente al total');
				document.getElementById('monto_reformulacion').focus();
				return false;
			}else{
				var flag="bueno";
			}
		}
	}




	if((document.getElementById('cod_tipo_refo').value==2) || (document.getElementById('cod_tipo_refo').value==4) || (document.getElementById('cod_tipo_refo').value==5) || (document.getElementById('cod_tipo_refo').value==6) || (document.getElementById('cod_tipo_refo').value==7) || (document.getElementById('cod_tipo_refo').value==8)){
		//alert('es dos (2) -- creditos adicionales');
		if(eval(document.getElementById('total2').value) != eval(document.getElementById('monto_reformulacion').value)){
			fun_msj('No puede avanzar, el total del aumento es diferente al monto de la reformulacion');
			document.getElementById('monto_reformulacion').focus();
			return false;
		}else if(document.getElementById('total1').value!=0){
			fun_msj('No puede avanzar, no puede haber un monto en la parte de la disminucion');
			document.getElementById('monto_reformulacion').focus();
			return false;
		}else{
				var flag="bueno";
		}
	}



	if(document.getElementById('cod_tipo_refo').value==3){
		//alert('es tres (3) -- rebaja presupuestaria');
		if(eval(document.getElementById('total1').value) != eval(document.getElementById('monto_reformulacion').value)){
			fun_msj('No puede avanzar, el total de la disminucion es diferente al monto de la reformulacion');
			document.getElementById('monto_reformulacion').focus();
			return false;
		}else if(document.getElementById('total2').value!=0){
			fun_msj('No puede avanzar, no puede haber un monto en la parte de incremento');
			document.getElementById('monto_reformulacion').focus();
			return false;
		}else{
				var flag="bueno";
		}
	}

	if(flag=='malo'){
		fun_msj('Lo siento no puede avanzar, los datos del oficio de reformulacion no estan correctos');
		document.getElementById('monto_reformulacion').focus();
		return false;
	}
}