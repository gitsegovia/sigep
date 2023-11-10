function valida_catp01_tipo(){

    if($('ano_ordenanza').value == ""){
			fun_msj('Inserte El A&ntilde;o de la Ordenanza');
			setTimeout("fondoCampo('ano_ordenanza',2);", 1500);
			$('ano_ordenanza').focus();
			return false;
    }else if($('lista_tipo').value == "0"){
			fun_msj('Debe agregar datos a la lista');
			return false;
    }else{
       $('plus').disabled="";
    }

}//fin valida_fila zona

function verifica_plus(){
	   if($('ano_ordenanza').value!=""){
	       $('select_1').disabled='';
		   $('select_2').disabled='';
		   $('select_3').disabled='';
		   $('select_4').disabled='';
	   }else{
	      $('select_1').disabled='disabled';
		  $('select_2').disabled='disabled';
		  $('select_3').disabled='disabled';
		  $('select_4').disabled='disabled';
		  $('codigo_zona').disabled='disabled';
		  $('especificacion_zona').disabled='disabled';
		  $('valor').disabled='disabled';
	   }
	   if($('ano_ordenanza').value!="" && $('codigo_zona').value!="" && $('especificacion_zona').value!="" && $('valor_ut').value!="" && $('n_variable').value!="" && $('valor_plus').value!="" && $('valor_utm').value!="" && $('arrendamiento').value!="" && $('parcela').value!="" && $('valor').value!="" &&  $('select_4').value!=""){
	        $('plus').disabled="";
	   }else{
	      $('plus').disabled="disabled";
	   }
	}//verifica_plus

	function valida_catp01_plan_save(){

	    if($('valor_utm').value == "0,00"){
				fun_msj('No puede ser 0 el VALOR UNIDAD TRIBUTARIA POR M2');
				$('valor_utm').focus();
				return false;
	    }else if($('parcela').value == "0"){
				fun_msj('No puede ser 0 el VALOR de la parcelas');
				$('parcela').focus();
				return false;
	    }else{
	    	return true;
	    }

	}//fin valida_catp01_plan_save

	function calculo_catp01_plan(){

		a=$('valor_ut').value;
		b=$('valor_utm').value;
		total = 0;

	var str = a;
	for(i=0; i<a.length; i++){
	    str = str.replace('.','');
	}
	str = str.replace(',','.');
	var a = str;

	var str = b;
	for(i=0; i<b.length; i++){
	    str = str.replace('.','');
	}
	str = str.replace(',','.');
	var b = str;




		total = redondear(eval(a)*eval(b),4);
		$('arrendamiento').value = redondear(eval(total) * 0.5,4);
		$('valor').value = total;
	     monedapp("valor",4);
	     monedapp("arrendamiento",4);



	}//fin calculo_catp01_plan

function calculo_catp01_plan2(){

		a=$('valor').value;
		total = 0;

	var str = a;
	for(i=0; i<a.length; i++){
	    str = str.replace('.','');
	}
	str = str.replace(',','.');
	var a = str;

	$('arrendamiento').value = redondear(eval(a) * 0.5,4);
		 monedapp("valor",4);
		 monedapp("arrendamiento",4);

	}//fin calculo_catp01_plan

	function calculo_catp01_plan_edt(){

		a=$('valor_ut_edt').value;
		b=$('valor_utm_edt').value;
		total = 0;

	var str = a;
	for(i=0; i<a.length; i++){
	    str = str.replace('.','');
	}
	str = str.replace(',','.');
	var a = str;

	var str = b;
	for(i=0; i<b.length; i++){
	    str = str.replace('.','');
	}
	str = str.replace(',','.');
	var b = str;

	    total = redondear(eval(a)*eval(b),4);

		$('valor_edt').value = total;
		$('arrendamiento_edt').value = redondear(total * 0.5,4);
	    monedapp("valor_edt",4);
	    monedapp("arrendamiento_edt",4);


	}//fin calculo_catp01_plan

function calculo_catp01_plan_edt2(){

		a=$('valor_edt').value;
		total = 0;

	var str = a;
	for(i=0; i<a.length; i++){
	    str = str.replace('.','');
	}
	str = str.replace(',','.');
	var a = str;

	  $('arrendamiento_edt').value = redondear(eval(a) * 0.5,4);
	  monedapp("valor_edt",4);
	  monedapp("arrendamiento_edt",4);




	}//fin calculo_catp01_plan


function verifica_plus_edt(){
   if($('codigo_zona_edt').value!="" && $('especificacion_zona_edt').value!="" && $('valor_edt').value!="" ){
        $('guardar_editar').disabled="";
   }else{
      $('guardar_editar').disabled="disabled";
   }
}//verifica_plus

function verifica_plus_tipo(){
   if($('opcion_programa').value==1){
	   if($('ano_ordenanza').value!="" && $('cod_tipo').value!="" && $('deno_tipo').value!="" && $('valor_tipo').value!=""){
          $('plus').disabled="";
	   }else{
	      $('plus').disabled="disabled";
	   }
   }else if($('opcion_programa').value==2){
	   if($('ano_ordenanza').value!="" && $('cod_tipo').value!="" && $('deno_tipo').value!="" && $('cara_tipo').value!="" && $('cod_caracteristicas').value!=""){
          $('plus').disabled="";
	   }else{
	      $('plus').disabled="disabled";
	   }
   }

}//verifica_plus

function valida_catp01_tipo_save(){

    if($('valor_utm').value == "0,00"){
			fun_msj('No puede ser 0 el VALOR UNIDAD TRIBUTARIA POR M2');
			$('valor_utm').focus();
			return false;

    }else{
    	return true;
    }

}//fin valida_catp01_plan_save


function calculo_catp01_tipo(){

	a=$('valor_ut').value;
	b=$('valor_utm').value;
	total = 0;

var str = a;
for(i=0; i<a.length; i++){
    str = str.replace('.',',');
}
str = str.replace(',','.');
var a = str;

var str = b;
for(i=0; i<b.length; i++){
    str = str.replace('.','');
}
str = str.replace(',','.');
var b = str;

      total = redondear(eval(a)*eval(b),4);

	if($('valor_utm').value!=''){
		$('valor_tipo').value = total;
		$('valor_tipo').focus();
		$('valor_ut').focus();
		monedapp("valor_tipo",4);
	}
}//fin calculo_catp01_plan

function calculo_catp01_tipo_edt(){

	a=$('valor_ut_edt').value;
	b=$('valor_utm_edt').value;
	total = 0;

var str = a;
for(i=0; i<a.length; i++){
    str = str.replace('.',',');
}
str = str.replace(',','.');
var a = str;

var str = b;
for(i=0; i<b.length; i++){
    str = str.replace('.','');
}
str = str.replace(',','.');
var b = str;

	$('valor_tipo_edt').value = redondear(eval(a)*eval(b),4);

	if($('valor_utm_edt').value!=''){
		$('valor_tipo_edt').focus();
		$('valor_ut_edt').focus();
		monedapp("valor_tipo_edt",4);
	}

}//fin calculo_catp01_plan


function verifica_plus_tipo_edt(){
   if($('cod_tipo_edt').value!="" && $('deno_tipo_edt').value!="" && $('cara_tipo_edt').value!="" && $('valor_tipo_edt').value!=""){
        $('guardar_editar').disabled="";
   }else{
      $('guardar_editar').disabled="disabled";
   }
}//verifica_plus


function verifica_plus_factor(){
   if($('ano_ordenanza').value!="" && $('edad').value!="" && $('factor_excelente').value!="" && $('factor_bueno').value!="" && $('factor_regular').value!="" && $('factor_malo').value!=""){
        $('plus').disabled="";
        $('plus').focus();
   }else{
      $('plus').disabled="disabled";
   }
}//verifica_plus_factor

function verifica_plus_factor_edt(){
   if($('edad_edt').value!="" && $('factor_excelente_edt').value!="" && $('factor_bueno_edt').value!="" && $('factor_regular_edt').value!="" && $('factor_malo_edt').value!=""){
        $('guardar_editar').disabled="";
   }else{
      $('guardar_editar').disabled="disabled";
   }
}//verifica_plus_factor


function verifica_plus_escala(){
   if($('ano_ordenanza').value!="" && $('escala').value!="" && $('monto_desde').value!="" && $('monto_hasta').value!="" && $('porcentaje').value!="" && $('sustraendo').value!=""){
         var monto_desde   = reemplazarPC($('monto_desde').value);//----
         var monto_hasta   = reemplazarPC($('monto_hasta').value);//----
         if(eval(monto_desde)<eval(monto_hasta)){
            $('plus').disabled="";
         }else{
            fun_msj('El monto hasta debe ser mayor al monto desde');
			setTimeout("fondoCampo('monto_hasta',2);", 1500);
			$('monto_hasta').focus();
         }

   }else{
      $('plus').disabled="disabled";
   }
}//verifica_plus_escala

function verifica_plus_escala_edt(){
   if($('escala_edt').value!="" && $('monto_desde_edt').value!="" && $('monto_hasta_edt').value!="" && $('porcentaje_edt').value!="" && $('sustraendo_edt').value!=""){
         var monto_desde   = reemplazarPC($('monto_desde_edt').value);//----
         var monto_hasta   = reemplazarPC($('monto_hasta_edt').value);//----
         if(eval(monto_desde)<eval(monto_hasta)){
            $('guardar_editar').disabled="";
         }else{
            fun_msj('El monto hasta debe ser mayor al monto desde');
			setTimeout("fondoCampo('monto_hasta_edt',2);", 1500);
			$('monto_hasta_edt').focus();
         }

   }else{
      $('guardar_editar').disabled="disabled";
   }
}//verifica_plus_escala

function verifica_plus_escala2(){
	   if($('ano_ordenanza').value!="" && $('escala').value!="" && $('metros_desde').value!="" && $('metros_hasta').value!="" && $('porcentaje').value!="" && $('sustraendo').value!=""){
	         var metros_desde   = reemplazarPC($('metros_desde').value);//----
	         var metros_hasta   = reemplazarPC($('metros_hasta').value);//----
	         if(eval(metros_desde)<eval(metros_hasta)){
	            $('plus').disabled="";
	         }else{
	            fun_msj('Los metros hasta debe ser mayor al metros desde');
				setTimeout("fondoCampo('metros_hasta',2);", 1500);
				$('metros_hasta').focus();
	         }

	   }else{
	      $('plus').disabled="disabled";
	   }
	}//verifica_plus_escala

function verifica_plus_escala_edt2(){
	   if($('escala_edt').value!="" && $('metros_desde_edt').value!="" && $('metros_hasta_edt').value!="" && $('porcentaje_edt').value!="" && $('sustraendo_edt').value!=""){
	         var monto_desde   = reemplazarPC($('metros_desde_edt').value);//----
	         var monto_hasta   = reemplazarPC($('metros_hasta_edt').value);//----
	         if(eval(monto_desde)<eval(monto_hasta)){
	            $('guardar_editar').disabled="";
	         }else{
	            fun_msj('El monto hasta debe ser mayor al monto desde');
	            $('metros_hasta_edt').focus();
	            setTimeout("fondoCampo('metros_hasta_edt',2);", 1500);

	         }

	   }else{
	      $('guardar_editar').disabled="disabled";
	   }
	}//verifica_plus_escala



function verifica_plus_recargos(){
   if($('ano_ordenanza').value!="" && $('porcentaje_industria').value!="" && $('porcentaje_servicios').value!="" && $('porcentaje_comercial').value!="" && $('porcentaje_arrendado').value!="" && $('porcentaje_otro').value!=""){
             if(reemplazarPC($('porcentaje_industria').value)>100){
                $('plus').disabled="disabled";
               fun_msj('El porcentaje ingresado para industria no puede ser mayor a 100');
               $('porcentaje_industria').focus();

            }else if(reemplazarPC($('porcentaje_servicios').value)>100){
                $('plus').disabled="disabled";
               fun_msj('El porcentaje ingresado para servicios no puede ser mayor a 100');
               $('porcentaje_servicios').focus();

            }else if(reemplazarPC($('porcentaje_comercial').value)>100){
             $('plus').disabled="disabled";
               fun_msj('El porcentaje ingresado para el comercio no puede ser mayor a 100');
               $('porcentaje_comercial').focus();

            }else if(reemplazarPC($('porcentaje_arrendado').value)>100){
               $('plus').disabled="disabled";
               fun_msj('El porcentaje ingresado para arrendado no puede ser mayor a 100');
               $('porcentaje_arrendado').focus();

            }else if(reemplazarPC($('porcentaje_otro').value)>100){
               $('plus').disabled="disabled";
               fun_msj('El porcentaje ingresado para otros no puede ser mayor a 100');
               $('porcentaje_otro').focus();

            }else{
                $('plus').disabled="";
            }
   }else{
      $('plus').disabled="disabled";
   }
}//verifica_plus_recargos


function verifica_plus_recargos_edt(){
   if($('ano_ordenanza').value!="" && $('porcentaje_industria_edt').value!="" && $('porcentaje_servicios_edt').value!="" && $('porcentaje_comercial_edt').value!="" && $('porcentaje_arrendado_edt').value!="" && $('porcentaje_otro_edt').value!=""){
            if(reemplazarPC($('porcentaje_industria_edt').value)>100){
               $('guardar_editar').style.opacity=0.50;
               $('guardar_editar').disabled="disabled";
               fun_msj('El porcentaje ingresado para industria no puede ser mayor a 100');
               $('porcentaje_industria_edt').focus();

            }else if(reemplazarPC($('porcentaje_servicios_edt').value)>100){
               $('guardar_editar').style.opacity=0.50;
               $('guardar_editar').disabled="disabled";
               fun_msj('El porcentaje ingresado para servicios no puede ser mayor a 100');
               $('porcentaje_servicios_edt').focus();

            }else if(reemplazarPC($('porcentaje_comercial_edt').value)>100){
              $('guardar_editar').style.opacity=0.50;
               $('guardar_editar').disabled="disabled";
               fun_msj('El porcentaje ingresado para el comercio no puede ser mayor a 100');
               $('porcentaje_comercial_edt').focus();

            }else if(reemplazarPC($('porcentaje_arrendado_edt').value)>100){
              $('guardar_editar').style.opacity=0.50;
               $('guardar_editar').disabled="disabled";
               fun_msj('El porcentaje ingresado para arrendado no puede ser mayor a 100');
               $('porcentaje_arrendado_edt').focus();

            }else if(reemplazarPC($('porcentaje_otro_edt').value)>100){
              $('guardar_editar').style.opacity=0.50;
               $('guardar_editar').disabled="disabled";
               fun_msj('El porcentaje ingresado para otros no puede ser mayor a 100');
               $('porcentaje_otro_edt').focus();

            }else{
               $('guardar_editar').style.opacity=100;
               $('guardar_editar').disabled="";
            }
   }else{
      $('guardar_editar').disabled="disabled";
   }
}//verifica_plus_recargos_edt


function verifica_plus_variable_principal(){
   if($('ano_ordenanza').value!=""){
       $('select_1').disabled='';
   }else{
      $('select_1').disabled='disabled';
	  $('cod_variable_principal').disabled='disabled';
	  $('denominacion_principal').disabled='disabled';
   }
   if($('ano_ordenanza').value!="" && $('cod_variable_principal').value!="" && $('denominacion_principal').value!="" &&  $('select_1').value!=""){
        $('plus').disabled="";
   }else{
      $('plus').disabled="disabled";
   }
}//verifica_plus

function verifica_plus_variable_principal_edt(){
   if($('ano_ordenanza').value!=""){
       $('select_1').disabled='';
   }else{
      $('select_1').disabled='disabled';
	  $('cod_variable_principal_edt').disabled='disabled';
	  $('denominacion_principal_edt').disabled='disabled';
   }
   if($('ano_ordenanza').value!="" && $('cod_variable_principal_edt').value!="" && $('denominacion_principal_edt').value!="" &&  $('select_1').value!=""){
        $('guardar_editar').style.opacity=1;
        $('guardar_editar').disabled="";
   }else{
      $('guardar_editar').style.opacity=0.50;
      $('guardar_editar').disabled="disabled";
   }
}//verifica_plus




function verifica_plus_variable_primaria(){

   if($('ano_ordenanza').value!=""){
       $('select_1').disabled='';
       $('select_2').disabled='';
   }else{
      $('select_1').disabled='disabled';
      $('select_2').disabled='disabled';
	  $('cod_variable_primaria').disabled='disabled';
	  $('denominacion_principal').disabled='disabled';
   }
   if($('monto').value!="" && $('ano_ordenanza').value!="" && $('cod_variable_primaria').value!="" && $('denominacion_principal').value!="" &&  $('select_2').value!=""){
        $('plus').disabled="";
   }else{
      $('plus').disabled="disabled";
   }
}//verifica_plus_variable_primaria

function verifica_plus_variable_primaria_edt(){
   if($('monto_edt').value==""){$('monto_edt').value="0,00";}
   if($('ano_ordenanza').value!=""){
       $('select_1').disabled='';
       $('select_2').disabled='';
   }else{
      $('select_1').disabled='disabled';
      $('select_2').disabled='disabled';
	  $('cod_variable_primaria_edt').disabled='disabled';
	  $('denominacion_principal_edt').disabled='disabled';
   }
   if($('ano_ordenanza').value!="" && $('cod_variable_primaria_edt').value!="" && $('denominacion_principal_edt').value!="" &&  $('select_2').value!=""){
        $('guardar_editar').style.opacity=1;
        $('guardar_editar').disabled="";
   }else{
      $('guardar_editar').style.opacity=0.50;
      $('guardar_editar').disabled="disabled";
   }
}//verifica_plus_variable_primaria



function verifica_plus_variable_secundaria(){
   if($('ano_ordenanza').value!=""){
   }else{
      $('select_1').disabled='disabled';
      $('select_2').disabled='disabled';
      $('select_3').disabled='';
	  $('cod_variable_secundaria').disabled='disabled';
	  $('denominacion_principal').disabled='disabled';
   }
   if($('ano_ordenanza').value!="" && $('cod_variable_secundaria').value!="" && $('denominacion_principal').value!="" && $('select_1').value!="" && $('select_2').value!="" && $('select_3').value!=""){
        $('plus').disabled="";
   }else{
      $('plus').disabled="disabled";
   }
}//verifica_plus_variable_secundaria



function verifica_plus_variable_secundaria_edt(){
   if($('ano_ordenanza').value!=""){
       $('select_1').disabled='';
       $('select_2').disabled='';
       $('select_3').disabled='';
   }else{
      $('select_1').disabled='disabled';
      $('select_2').disabled='disabled';
      $('select_3').disabled='';
	  $('cod_variable_secundaria_edt').disabled='disabled';
	  $('denominacion_principal_edt').disabled='disabled';
   }
   if($('ano_ordenanza').value!="" && $('select_1').value!="" && $('select_2').value!="" && $('select_3').value!="" && $('denominacion_principal_edt').value!="" && $('cod_variable_secundaria_edt').value!=""){
        $('guardar_editar').style.opacity=1;
        $('guardar_editar').disabled="";
   }else{
      $('guardar_editar').style.opacity=0.50;
      $('guardar_editar').disabled="disabled";
   }
}//verifica_plus_variable_secundaria_edt

function modificar_municipio_defecto(){
    $('guardar').disabled='';
    $('select_1').disabled='';
    $('select_2').disabled='';
    $('select_3').disabled='';
}


function picar_catp01(ID,len){
    var valor = $(ID).value;
     if(ID=='select2_4'){
        if(valor<10){
           valor='0'+valor;
        }
        valor= valor.length==2?'0'+valor:valor;
     }else if(ID=='select2_1' || ID=='select2_2' || ID=='select2_3'){
         var lc;
         if(valor<10){
			valor='0'+valor;
		 }
		 lc = valor;
         for(i=1;i<=len;i++){
            $(ID +'_c'+i).value = lc[i-1];
            if($(ID +'_c'+i+'_m')) $(ID +'_c'+i+'_m').value = lc[i-1];
         }
     }
     if(valor.length==len){
         var lc = valor;
         for(i=1;i<=len;i++){
            $(ID +'_c'+i).value = lc[i-1];
            if($(ID +'_c'+i+'_m')) $(ID +'_c'+i+'_m').value = lc[i-1];
         }
     }else{
     	switch(valor.length){
     	case 1:valor='00'+valor;break;
     	case 2:valor='0'+valor;break;
     }
         var lc = valor;
         for(i=1;i<=len;i++){
            $(ID +'_c'+i).value = lc[i-1];
            if($(ID +'_c'+i+'_m')) $(ID +'_c'+i+'_m').value = lc[i-1];
         }
     }
}

function calcular_edad_efectiva(con_c){
  	fechaActual = new Date();
  	anoActual = fechaActual.getFullYear();
     if($('tilde_conserva_1').checked==true){
        var factor=1;
     }else if($('tilde_conserva_2').checked==true){
        var factor=2;
     }else if($('tilde_conserva_3').checked==true){
        var factor=3;
     }else if($('tilde_conserva_4').checked==true){
        var factor=4;
     }else{
        var factor=0;
     }
	 var ano_ordenanza2=$('ano_ordenanza').value;
     var ano_ordenanza=anoActual;
     var ano_construccion=$('ano_construccion').value;
     var porce_refaccion=reemplazarPC($('porce_refraccion').value);
     var ano_refaccion=$('anio_refaccion').value;
     	if(eval(porce_refaccion)>=eval(50) && eval(porce_refaccion)<=eval(100) && eval(ano_refaccion)>eval(ano_construccion)){
		     var edad_efectiva=eval(ano_ordenanza)-eval(ano_refaccion);}
		else{
			 var edad_efectiva=eval(ano_ordenanza)-eval(ano_construccion);}
     $('edad_efectiva').value=edad_efectiva;
     if(edad_efectiva==0){
        edad_efectiva=1;
     }
     if(edad_efectiva<0){
     	fun_msj('Debe ingresar un a&ntilde;o v&aacute;lido para la construcci&oacute;n');
     }else if(eval(factor)>eval(0) && ano_construccion!=''){
     	document.getElementById('select_tipologia').disabled = false;
	   	con_c = con_c==2 ? 2 : 0;
		ver_documento('/catp02_ficha_datos/traer_factor_depreciacion/'+edad_efectiva+'/'+factor+'/'+ano_ordenanza2+'/'+con_c,'traer_depreciacion');
     }else if(eval(factor)==eval(0)){
     	fun_msj('Recuerde Seleccionar el <BLINK>Estado de Conservaci&oacute;n</BLINK>');
     	document.getElementById('select_tipologia').disabled = true;
     }else{
     	document.getElementById('select_tipologia').disabled = true;
     }
}

function recalcular_edad_efectiva(con_c){
  if($('anio_refaccion').value!='' && eval($('anio_refaccion').value) < eval(1000)){
		fun_msj('Ingrese un a&ntilde;o v&aacute;lido para la refacci&oacute;n');
		$('anio_refaccion').value='';
  }else if($('anio_refaccion').value!='' && eval($('anio_refaccion').value)!=eval(0)){
  	fechaActual = new Date();
  	anoActual = fechaActual.getFullYear();
     if($('tilde_conserva_1').checked==true){
        var factor=1;
     }else if($('tilde_conserva_2').checked==true){
        var factor=2;
     }else if($('tilde_conserva_3').checked==true){
        var factor=3;
     }else if($('tilde_conserva_4').checked==true){
        var factor=4;
     }else{
        var factor=0;
     }
	 var ano_ordenanza2=$('ano_ordenanza').value;
     var porce_refaccion=reemplazarPC($('porce_refraccion').value);
     if(eval(porce_refaccion)==eval(0)){
     	fun_msj('Debe ingresar el porcentaje <BLINK>(%) de la refacci&oacute;n</BLINK>');
  	 }
	if(eval(porce_refaccion)>=eval(50) && eval(porce_refaccion)<=eval(100)){
     var ano_ordenanza=anoActual;
     var ano_refaccion=$('anio_refaccion').value;
     var edad_efectiva=eval(ano_ordenanza)-eval(ano_refaccion);
     $('edad_efectiva').value=edad_efectiva;
     if(edad_efectiva==0){
        edad_efectiva=1;
     }
     if(edad_efectiva<0){
     	fun_msj('Debe ingresar un a&ntilde;o v&aacute;lido para la refacci&oacute;n');
     }else if(eval(factor)>eval(0) && ano_refaccion!=''){
     	con_c = con_c==2 ? 2 : 0;
		ver_documento('/catp02_ficha_datos/traer_factor_depreciacion/'+edad_efectiva+'/'+factor+'/'+ano_ordenanza2+'/'+con_c,'traer_depreciacion');
     }
  }else if($('ano_construccion').value!=''){
  	con_c = con_c==2 ? 2 : 0;
	calcular_edad_efectiva(con_c);
  }
  }else{
	$('porce_refraccion').value='0,00';
	con_c = con_c==2 ? 2 : 0;
	calcular_edad_efectiva(con_c);
  }
}

function calcular_fila_valor_actual(){
pag='../../include/cfpp05/moneda.php?monto=';
     if($('area_m2').value!=""){
           var area_m2=eval($('area_m2').value);
           var valor_m2=eval($('valor_m2').value);
           var porc_depreciacion=eval($('porc_depreciacion').value);
           var valor_actual=redondear(area_m2*(valor_m2*(porc_depreciacion)),2);
           var valor_contruccie = redondear(eval($('area_m2').value) * eval($('valor_m2').value),2);
           cargarMonto('valor_actual',pag+redondear(valor_actual,2));
           cargarMonto('valor_construcci',pag+redondear(valor_contruccie,2));
     }

}

function plus_selecction_tpg(){
 if($('ide_aconst').value!='0,00'){
 if($('select_tipologia').value!=''){
  if($('nume_niveles').value!='' && $('nume_edificaciones').value!=''){
	if(eval($('nume_niveles').value) >= eval($('nume_edificaciones').value)){
		control_nlist = $('nume_niveles').value;
	}else if(eval($('nume_edificaciones').value) >= eval($('nume_niveles').value)){
		control_nlist = $('nume_edificaciones').value;
	}else{
		control_nlist = 0;
	}
	if(eval($('ctrl_num_ne').value) == eval(control_nlist)){
     	fun_msj('Disculpe no se puede a&ntilde;adir m&aacute;s tipolog&iacute;as a la lista debido al n&uacute;mero de niveles y edificaciones');
  		return false;
	}else{
  		return true;
	}
  }else{
     	fun_msj('Debe ingresar el n&uacute;mero de niveles y edificaciones');
  		return false;
  }
 }else{
	fun_msj('Debe seleccionar la tipolog&iacute;a');
	$('select_tipologia').focus();
	setTimeout("fondoCampo('select_tipologia',2);", 2000);
	return false;
 }
 }else{
	fun_msj('Debe Ingresar el &Aacute;rea (m<sup>2</sup>) de la Construcci&oacute;n');
	return false;
 }
}


function calcular_val_terrrenom(){

if(document.getElementById('ide_ater').value.indexOf(",")!=-1){
  document.getElementById('ide_ater').value = reemplazarPC(document.getElementById('ide_ater').value);
}
	var valor_t = $('ide_ater').value;
	$('valoracion_area').value=valor_t;
	$('valoracion_ajuste_area').value=valor_t;

	if($('valoracion_valor_ajustado').value!='0,0000'){
	document.getElementById('valoracion_valor_total').value = redondear(eval(valor_t) * eval(document.getElementById('valoracion_valor_ajustado').value),2);
	document.getElementById('calculo_terreno').value = document.getElementById('valoracion_valor_total').value;
    moneda('ide_ater');
	moneda('valoracion_area');
	moneda('valoracion_ajuste_area');
	calcular_distribucion_imp();
}
}



function calcular_distribucion_imp(){

//Terreno
if(document.getElementById('valoracion_ajuste_area').value.indexOf(",")!=-1){
  document.getElementById('valoracion_ajuste_area').value = reemplazarPC(document.getElementById('valoracion_ajuste_area').value);
}
if(document.getElementById('valoracion_valor_ajustado').value.indexOf(",")!=-1){
  document.getElementById('valoracion_valor_ajustado').value = reemplazarPC(document.getElementById('valoracion_valor_ajustado').value);
}
 document.getElementById('valoracion_valor_total').value = redondear(eval(document.getElementById('valoracion_ajuste_area').value) * eval(document.getElementById('valoracion_valor_ajustado').value),2);
 document.getElementById('calculo_terreno').value = document.getElementById('valoracion_valor_total').value;



//Construccion
if(document.getElementById('area_total').value.indexOf(",")!=-1){
  document.getElementById('area_total').value = reemplazarPC(document.getElementById('area_total').value);
}
if(document.getElementById('monto_tota_variables_c').value.indexOf(",")!=-1){
  document.getElementById('monto_tota_variables_c').value = reemplazarPC(document.getElementById('monto_tota_variables_c').value);
}
if(document.getElementById('c_valor_construccion').value.indexOf(",")!=-1){
  document.getElementById('c_valor_construccion').value = reemplazarPC(document.getElementById('c_valor_construccion').value);
}
if(document.getElementById('d_valor_montoajust').value.indexOf(",")!=-1){
  document.getElementById('d_valor_montoajust').value = reemplazarPC(document.getElementById('d_valor_montoajust').value);
}
if(document.getElementById('porc_depreciacion').value.indexOf(",")!=-1){
  document.getElementById('porc_depreciacion').value = reemplazarPC(document.getElementById('porc_depreciacion').value);
}
  document.getElementById('monto_total_de_construccion').value = redondear((eval($('area_total').value) * eval($('monto_tota_variables_c').value)) * eval($('porc_depreciacion').value),2);
  document.getElementById('idim_tc').value = redondear(eval(document.getElementById('valoracion_valor_total').value) +  eval(document.getElementById('monto_total_de_construccion').value),2);


   if($('tilde_reg_prop_1').checked==true){
        var factor=1;
     }else if($('tilde_reg_prop_2').checked==true){
        var factor=2;
     }else if($('tilde_reg_prop_3').checked==true){
        var factor=3;
     }else if($('tilde_reg_prop_4').checked==true){
        var factor=4;
     }else if($('tilde_reg_prop_5').checked==true){
        var factor=5;
     }else if($('tilde_reg_prop_6').checked==true){
        var factor=6;
     }else if($('tilde_reg_prop_7').checked==true){
        var factor=7;
     }else if($('tilde_reg_prop_8').checked==true){
        var factor=8;
     }else{
        var factor=0;
     }

	ver_documento('/catp02_ficha_datos/calculo_impuesto_anual_trim/'+eval(reemplazarPC(document.getElementById('idim_tc').value))+'/'+eval(document.getElementById('monto_total_de_construccion').value)+'/0/'+(document.getElementById('valoracion_valor_ajustado').value)+'/'+eval((document.getElementById('valoracion_ajuste_area').value))+'/'+factor+'/'+eval(document.getElementById('calculo_terreno').value), 'carga_dimp_anutrim');

  moneda('area_total');
  moneda('valoracion_valor_total');
  moneda('calculo_terreno');
  moneda('monto_total_de_construccion');
  moneda('idim_tc');
  moneda('d_valor_montoajust');
  monedapp('c_valor_construccion',4);
  monedapp('monto_tota_variables_c',4);
}



function valid_limplista_filas(){
	var confirma_lip = false;
	confirma_lip = confirm('Desea Realmente Eliminar toda la Lista de la Construccion?');
    if(confirma_lip==false){
		return false;
    }else if(confirma_lip==true){
    	return true;
    }
}

function valid_limplista_filas_var(){
	var confirma_lip = false;
	confirma_lip = confirm('Desea Realmente Eliminar toda la Lista de las Variables de la Construccion?');
    if(confirma_lip==false){
		return false;
    }else if(confirma_lip==true){
    	return true;
    }
}


function limplista_fpa(){
	var confirma_lip = false;
	confirma_lip = confirm('Desea Realmente Eliminar toda la Lista de la Poblacion Adulta?');
    if(confirma_lip==false){
		return false;
    }else if(confirma_lip==true){
    	return true;
    }
}

function limplista_fpi(){
	var confirma_lip = false;
	confirma_lip = confirm('Desea Realmente Eliminar toda la Lista de la Poblacion Infantil?');
    if(confirma_lip==false){
		return false;
    }else if(confirma_lip==true){
    	return true;
    }
}



// ---------------------
// FUNCIONES REPETIDAS
/*

function activex_tabg_datos_ocupante(){
	var valida_tab = false;
	if(document.getElementById('cod_unidad').value!='')
		if(document.getElementById('dir_inm_descripcionren').value!='' && document.getElementById('dir_inm_descripcionren').value!='ESPECIFIQUE...')
			if(document.getElementById('dir_inm_descripcionrene').value!='' && document.getElementById('dir_inm_descripcionrene').value!='ESPECIFIQUE...')
				if(document.getElementById('dir_inm_descripcionreney').value!='' && document.getElementById('dir_inm_descripcionreney').value!='ESPECIFIQUE...')
					if($('condicion_ocup_1').checked==true || $('condicion_ocup_2').checked==true || $('condicion_ocup_3').checked==true || $('condicion_ocup_4').checked==true || $('condicion_ocup_5').checked==true || $('condicion_ocup_6').checked==true || $('condicion_ocup_7').checked==true || $('condicion_ocup_8').checked==true || $('condicion_ocup_9').checked==true || $('condicion_ocup_10').checked==true || $('condicion_ocup_11').checked==true || $('condicion_ocup_12').checked==true)
					valida_tab = true;
	if(valida_tab == true){document.getElementById('tabg_datos_ocupante').style.display="block"; document.getElementById('errorcatp_1').style.display="none";}
	else{
		fun_msj('Por favor debe ingresar todos los datos requeridos en Datos Generales');
		document.getElementById('tabg_datos_ocupante').style.display="none";
		document.getElementById('errorcatp_1').style.display="block";
	}
	return valida_tab;
}


function activex_tabg_datos_terreno(){
	var captura_vtabg = activex_tabg_datos_ocupante();
	var valida_tab = false;
	if(captura_vtabg == true){
	if($('datos_ocupante_personalidad_1').checked==true || $('datos_ocupante_personalidad_2').checked==true)
		if(document.getElementById('datos_ocupante_ci_rif').value!='')
			if(document.getElementById('datos_ocupante_nombre').value!='')
				if(document.getElementById('dir_inm_descripcionren22').value!='' && document.getElementById('dir_inm_descripcionren2').value!='ESPECIFIQUE...')
					if(document.getElementById('dir_inm_descripcionrene22').value!='' && document.getElementById('dir_inm_descripcionrene2').value!='ESPECIFIQUE...')
						if(document.getElementById('dir_inm_descripcionreney22').value!='' && document.getElementById('dir_inm_descripcionreney2').value!='ESPECIFIQUE...')
	if($('datos_ocupante_personalidad_p_1').checked==true || $('datos_ocupante_personalidad_p_2').checked==true)
		if(document.getElementById('datos_ocupante_ci_rif_p').value!='')
			if(document.getElementById('datos_ocupante_nombre_p').value!='')
				if(document.getElementById('dir_inm_descripcionren2').value!='' && document.getElementById('dir_inm_descripcionren2').value!='ESPECIFIQUE...')
					if(document.getElementById('dir_inm_descripcionrene2').value!='' && document.getElementById('dir_inm_descripcionrene2').value!='ESPECIFIQUE...')
						if(document.getElementById('dir_inm_descripcionreney2').value!='' && document.getElementById('dir_inm_descripcionreney2').value!='ESPECIFIQUE...')
							valida_tab = true;
	if(valida_tab == true){document.getElementById('tabg_datos_terreno').style.display="block"; document.getElementById('errorcatp_2').style.display="none";}
	else{
		fun_msj('Por favor debe ingresar todos los datos requeridos en Datos del Ocupante');
		document.getElementById('tabg_datos_terreno').style.display="none";
		document.getElementById('errorcatp_2').style.display="block";
	}
	}else{
		document.getElementById('tabg_datos_terreno').style.display="none";
	}
	return valida_tab;
}


function activex_tabg_datos_gen_const(){
	var captura_vtabg = activex_tabg_datos_terreno();
	var valida_tab = false;
	if(captura_vtabg == true){
	if($('tilde_topo_1').checked==true || $('tilde_topo_2').checked==true || $('tilde_topo_3').checked==true || $('tilde_topo_4').checked==true || $('tilde_topo_5').checked==true || $('tilde_topo_6').checked==true || $('tilde_topo_7').checked==true || $('tilde_topo_8').checked==true)
		if($('tilde_acceso_1').checked==true || $('tilde_acceso_2').checked==true || $('tilde_acceso_3').checked==true || $('tilde_acceso_4').checked==true || $('tilde_acceso_5').checked==true || $('tilde_acceso_6').checked==true || $('tilde_acceso_7').checked==true || $('tilde_acceso_8').checked==true || $('tilde_acceso_9').checked==true)
			if($('tilde_forma_regular_1').checked==true || $('tilde_forma_regular_2').checked==true || $('tilde_forma_regular_3').checked==true)
				if($('tilde_ubica_1').checked==true || $('tilde_ubica_2').checked==true || $('tilde_ubica_3').checked==true || $('tilde_ubica_4').checked==true)
					if($('tilde_entorno_zona_1').checked==true || $('tilde_entorno_zona_2').checked==true || $('tilde_entorno_zona_3').checked==true || $('tilde_entorno_zona_4').checked==true || $('tilde_entorno_zona_5').checked==true)
						if($('tilde_uso_residencial').checked==true || $('tilde_uso_religioso').checked==true || $('tilde_uso_comercial').checked==true || $('tilde_uso_sin_uso').checked==true || $('tilde_uso_industrial').checked==true || $('tilde_uso_otro').checked==true || $('tilde_uso_recreativo').checked==true || $('tilde_uso_asistencia').checked==true || $('tilde_uso_educacional').checked==true || $('tilde_uso_turistico').checked==true || $('tilde_uso_social_cultural').checked==true || $('tilde_uso_gubernamental').checked==true)
							if($('tilde_mejoras_muro_contencion').checked==true || $('tilde_mejoras_nivelacion').checked==true || $('tilde_mejoras_cercado').checked==true || $('tilde_mejoras_pozo_septico').checked==true || $('tilde_mejoras_lagunas_art').checked==true || $('tilde_mejoras_otro').checked==true)
								if($('tilde_tenencia_1').checked==true || $('tilde_tenencia_2').checked==true || $('tilde_tenencia_3').checked==true || $('tilde_tenencia_4').checked==true || $('tilde_tenencia_5').checked==true || $('tilde_tenencia_6').checked==true || $('tilde_tenencia_7').checked==true || $('tilde_tenencia_8').checked==true || $('tilde_tenencia_9').checked==true)
									if($('tilde_reg_prop_1').checked==true || $('tilde_reg_prop_2').checked==true || $('tilde_reg_prop_3').checked==true || $('tilde_reg_prop_4').checked==true || $('tilde_reg_prop_5').checked==true || $('tilde_reg_prop_6').checked==true || $('tilde_reg_prop_7').checked==true || $('tilde_reg_prop_8').checked==true)
										if($('tilde_serv_acueducto').checked==true || $('tilde_serv_transporte').checked==true || $('tilde_serv_cloacas').checked==true || $('tilde_serv_telefono').checked==true || $('tilde_serv_drenaje_art').checked==true || $('tilde_serv_cable_tv').checked==true || $('tilde_serv_elect_residencial').checked==true || $('tilde_serv_tvsate').checked==true || $('tilde_serv_elect_indus').checked==true || $('tilde_serv_correo_telec').checked==true || $('tilde_serv_alumbrado').checked==true || $('tilde_serv_gas').checked==true || $('tilde_serv_vialidad').checked==true || $('tilde_serv_aseo').checked==true || $('tilde_serv_pavimento').checked==true || $('tilde_serv_otro').checked==true || $('tilde_serv_acera').checked==true)
											if($('radio_afect_leg_1').checked==true || $('radio_afect_leg_2').checked==true || $('radio_afect_leg_3').checked==true || $('radio_afect_leg_4').checked==true || $('radio_afect_leg_5').checked==true)
												if(document.getElementById('normativa_gaceta').value!='')
													if(document.getElementById('normativa_resolucion').value!='')
														if(document.getElementById('normativa_fecha').value!='')
															if(document.getElementById('ubic_zonificacion').value!='')
																valida_tab = true;
	if(valida_tab == true){document.getElementById('tabg_datos_gen_const').style.display="block"; document.getElementById('errorcatp_3').style.display="none";}
	else{
		fun_msj('Por favor debe ingresar todos los datos requeridos en Datos del Terreno');
		document.getElementById('tabg_datos_gen_const').style.display="none";
		document.getElementById('errorcatp_3').style.display="block";
	}
	}else{
		document.getElementById('tabg_datos_gen_const').style.display="none";
	}
	return valida_tab;
}


function activex_tabg_datos_reg_valor_emco(){
	var captura_vtabg = activex_tabg_datos_gen_const();
	var valida_tab = false;
	if(captura_vtabg == true){
	if($('tilde_tipo_1').checked==true || $('tilde_tipo_2').checked==true || $('tilde_tipo_3').checked==true || $('tilde_tipo_4').checked==true || $('tilde_tipo_5').checked==true || $('tilde_tipo_6').checked==true || $('tilde_tipo_7').checked==true || $('tilde_tipo_8').checked==true || $('tilde_tipo_9').checked==true || $('tilde_tipo_10').checked==true || $('tilde_tipo_11').checked==true || $('tilde_tipo_12').checked==true || $('tilde_tipo_13').checked==true || $('tilde_tipo_14').checked==true || $('tilde_tipo_15').checked==true || $('tilde_tipo_16').checked==true || $('tilde_tipo_17').checked==true || $('tilde_tipo_18').checked==true)
		if($('tilde_desc_uso_1').checked==true || $('tilde_desc_uso_2').checked==true || $('tilde_desc_uso_3').checked==true || $('tilde_desc_uso_4').checked==true || $('tilde_desc_uso_5').checked==true || $('tilde_desc_uso_6').checked==true || $('tilde_desc_uso_7').checked==true || $('tilde_desc_uso_8').checked==true || $('tilde_desc_uso_9').checked==true || $('tilde_desc_uso_10').checked==true)
			if($('tilde_tenencia_cons_1').checked==true || $('tilde_tenencia_cons_2').checked==true || $('tilde_tenencia_cons_3').checked==true || $('tilde_tenencia_cons_4').checked==true || $('tilde_tenencia_cons_5').checked==true || $('tilde_tenencia_cons_6').checked==true || $('tilde_tenencia_cons_7').checked==true || $('tilde_tenencia_cons_8').checked==true || $('tilde_tenencia_cons_9').checked==true)
				if($('tilde_regimen_prop_1').checked==true || $('tilde_regimen_prop_2').checked==true || $('tilde_regimen_prop_3').checked==true || $('tilde_regimen_prop_4').checked==true || $('tilde_regimen_prop_5').checked==true || $('tilde_regimen_prop_6').checked==true)
					if($('tilde_soporte_concreto_armado').checked==true || $('tilde_soporte_metalica').checked==true || $('tilde_soporte_madera').checked==true || $('tilde_soporte_paredes_carga').checked==true || $('tilde_soporte_prefrabicado').checked==true || $('tilde_soporte_machones').checked==true || $('tilde_soporte_otro').checked==true)
						if($('tilde_techo_concreto_armado').checked==true || $('tilde_techo_metalica').checked==true || $('tilde_techo_madera').checked==true || $('tilde_techo_varas').checked==true || $('tilde_techo_cerchas').checked==true || $('tilde_techo_al').checked==true || $('tilde_techo_otro').checked==true)
							if($('tilde_cubierta_madera_teja').checked==true || $('tilde_cubierta_acerolit').checked==true || $('tilde_cubierta_placa_teja').checked==true || $('tilde_cubierta_palma').checked==true || $('tilde_cubierta_tejas_canabrava').checked==true || $('tilde_cubierta_tabelon').checked==true || $('tilde_cubierta_platabanda').checked==true || $('tilde_cubierta_barro').checked==true || $('tilde_cubierta_asbesto').checked==true || $('tilde_cubierta_riple').checked==true || $('tilde_cubierta_aluminio').checked==true || $('tilde_cubierta_cinduteja').checked==true || $('tilde_cubierta_zinc').checked==true || $('tilde_cubierta_otro').checked==true)
								if($('tilde_cubiertai_machi').checked==true || $('tilde_cubiertai_plafon').checked==true || $('tilde_cubiertai_crl').checked==true || $('tilde_cubiertai_cre').checked==true || $('tilde_cubiertai_sinc').checked==true || $('tilde_cubiertai_otro').checked==true)
									if($('tilde_pared_tipo_bloque_cemento').checked==true || $('tilde_pared_tipo_bloque_arcilla').checked==true || $('tilde_pared_tipo_ladrillo').checked==true || $('tilde_pared_tipo_adobe').checked==true || $('tilde_pared_tipo_tapia').checked==true || $('tilde_pared_tipo_bahareque').checked==true || $('tilde_pared_tipo_prefabricada').checked==true || $('tilde_pared_tipo_vidrio').checked==true || $('tilde_pared_tipo_madera_aserrada').checked==true || $('tilde_pared_tipo_zinc_laton').checked==true || $('tilde_pared_tipo_carton').checked==true || $('tilde_pared_tipo_sin_paredes').checked==true || $('tilde_pared_tipo_otro').checked==true)
										if($('tilde_pared_acabado_friso_liso').checked==true || $('tilde_pared_acabado_friso_rustico').checked==true || $('tilde_pared_acabado_ol').checked==true || $('tilde_pared_acabado_c').checked==true || $('tilde_pared_acabado_porc').checked==true || $('tilde_pared_acabado_cemenb').checked==true || $('tilde_pared_acabado_papelt').checked==true || $('tilde_pared_acabado_vidrio').checked==true || $('tilde_pared_acabado_yeso_cal').checked==true || $('tilde_pared_acabado_baldosa').checked==true || $('tilde_pared_acabado_sin_friso').checked==true || $('tilde_pared_acabado_otro').checked==true)
											if($('tilde_pintura_caucho').checked==true || $('tilde_pintura_oleo').checked==true || $('tilde_pintura_pasta').checked==true || $('tilde_pintura_asbestina').checked==true || $('tilde_pintura_lechada').checked==true || $('tilde_pintura_sinpintura').checked==true || $('tilde_pintura_otro').checked==true)
												if($('tilde_instalac_embutida').checked==true || $('tilde_instalac_externa').checked==true || $('tilde_instalac_industrial').checked==true)
													if($('tilde_piso_ceramica').checked==true || $('tilde_piso_porcelanato').checked==true || $('tilde_piso_granito').checked==true || $('tilde_piso_parquet').checked==true || $('tilde_piso_marmol').checked==true || $('tilde_piso_vinil').checked==true || $('tilde_piso_mosaico').checked==true || $('tilde_piso_madera').checked==true || $('tilde_piso_cemento_pulido').checked==true || $('tilde_piso_cemento_rustico').checked==true || $('tilde_piso_ladrillo').checked==true || $('tilde_piso_terracota').checked==true || $('tilde_piso_sinpiso').checked==true || $('tilde_piso_otro').checked==true)
														if($('tilde_conserva_1').checked==true || $('tilde_conserva_2').checked==true || $('tilde_conserva_3').checked==true || $('tilde_conserva_4').checked==true)
															if(document.getElementById('ano_construccion').value!='' && eval(document.getElementById('ano_construccion').value)!=eval(0))
																if(document.getElementById('nume_niveles').value!='' && eval(document.getElementById('nume_niveles').value)!=eval(0))
																	if(document.getElementById('nume_edificaciones').value!='' && eval(document.getElementById('nume_edificaciones').value)!=eval(0))
																		if(eval(reemplazarPC($('ide_ater').value))>eval(0))
																			if(eval(reemplazarPC($('ide_aconst').value))>eval(0))
																				if(eval(reemplazarPC($('total_porc_const').value))==eval(100))
																					if($('serv_inm_abast_agua_1').checked==true || $('serv_inm_abast_agua_2').checked==true || $('serv_inm_abast_agua_3').checked==true || $('serv_inm_abast_agua_4').checked==true || $('serv_inm_abast_agua_5').checked==true || $('serv_inm_abast_agua_6').checked==true)
																						if($('serv_inm_electrico_1').checked==true || $('serv_inm_electrico_2').checked==true || $('serv_inm_electrico_3').checked==true)
																							if($('serv_inm_energia_1').checked==true || $('serv_inm_energia_2').checked==true || $('serv_inm_energia_3').checked==true || $('serv_inm_energia_4').checked==true || $('serv_inm_energia_5').checked==true)
																								// if(document.getElementById('numero_serv_inm_telef').value!='' || document.getElementById('numero_serv_inm_resid').value!='' || document.getElementById('numero_serv_inm_com').value!='' || document.getElementById('numero_serv_inm_otro').value!='' || document.getElementById('numero_serv_inm_sinserv').value!='')
																									if($('serv_inm_internet_1').checked==true || $('serv_inm_internet_2').checked==true || $('serv_inm_internet_3').checked==true || $('serv_inm_internet_4').checked==true || $('serv_inm_internet_5').checked==true || $('serv_inm_internet_6').checked==true)
																										if($('serv_inm_agua_servida_1').checked==true || $('serv_inm_agua_servida_2').checked==true || $('serv_inm_agua_servida_3').checked==true || $('serv_inm_agua_servida_4').checked==true || $('serv_inm_agua_servida_5').checked==true)
																											if($('serv_inm_aseo_urbano_1').checked==true || $('serv_inm_aseo_urbano_2').checked==true || $('serv_inm_aseo_urbano_3').checked==true || $('serv_inm_aseo_urbano_4').checked==true)
																												if($('serv_inm_abastag_dist_1').checked==true || $('serv_inm_abastag_dist_2').checked==true || $('serv_inm_abastag_dist_3').checked==true || $('serv_inm_abastag_dist_4').checked==true || $('serv_inm_abastag_dist_5').checked==true)
																													if($('serv_inm_aseou_dist_1').checked==true || $('serv_inm_aseou_dist_2').checked==true || $('serv_inm_aseou_dist_3').checked==true || $('serv_inm_aseou_dist_4').checked==true)
																														if(document.getElementById('inum_pa').value!='0')
																															if($('tilde_tenencia_cons_2').checked==true){
																																if(document.getElementById('canon_mensual').value!='0,00' && document.getElementById('fecha_contrato').value!='')
																																	valida_tab = true;
																																else
																																	valida_tab = false;
																															}else{valida_tab = true;}
	if(valida_tab == true){document.getElementById('tabg_datos_reg_valor_emco').style.display="block"; document.getElementById('errorcatp_4').style.display="none";}
	else{
		fun_msj('Por favor debe ingresar todos los datos requeridos en Datos Generales de la Construcci&oacute;n');
		document.getElementById('tabg_datos_reg_valor_emco').style.display="none";
		document.getElementById('errorcatp_4').style.display="block";
	}
	}else{
		document.getElementById('tabg_datos_reg_valor_emco').style.display="none";
	}
	return valida_tab;
}


function activex_tabg_linderos_coord(){
	var captura_vtabg = activex_tabg_datos_reg_valor_emco();
	var valida_tab = false;
	if(captura_vtabg == true){
		if(document.getElementById('observaciones_ficha').value!='')
			if(document.getElementById('impe_anual').value!='')
				if(document.getElementById('impe_trime').value!='')
					valida_tab = true;
	if(valida_tab == true){document.getElementById('tabg_linderos_coord').style.display="block"; document.getElementById('errorcatp_5').style.display="none";}
	else{
		fun_msj('Por favor debe ingresar todos los datos requeridos en Valoraci&oacute;n Econ&oacute;mica');
		document.getElementById('tabg_linderos_coord').style.display="none";
		document.getElementById('errorcatp_5').style.display="block";
	}
	}else{
		document.getElementById('tabg_linderos_coord').style.display="none";
	}
	return valida_tab;
}


function activex_tabg_croquis(){
	var captura_vtabg = activex_tabg_linderos_coord();
	var valida_tab = false;
	if(captura_vtabg == true){
		if(document.getElementById('lindero_norte').value!='')
			if(document.getElementById('lindero_sur').value!='')
				if(document.getElementById('lindero_este').value!='')
					if(document.getElementById('lindero_oeste').value!='')
						if(document.getElementById('situacion_relativa').value!='')
							if(document.getElementById('coordenada_norte').value!='')
								if(document.getElementById('coordenada_este').value!='')
									if(document.getElementById('huso').value!='')
										if(document.getElementById('observaciones_lind_coord').value!='')
											if(document.getElementById('fecha_primera_visita').value!='')
												if(document.getElementById('fecha_levantamiento').value!='')
													if(document.getElementById('elaborado_nombre').value!='')
														if(document.getElementById('elaborado_ci').value!='')
															if(document.getElementById('revisado_nombre').value!='')
																if(document.getElementById('revisado_ci').value!='')
																	valida_tab = true;
	if(valida_tab == true){document.getElementById('tabg_croquis').style.display="block"; document.getElementById('errorcatp_6').style.display="none";}
	else{
		fun_msj('Por favor debe ingresar todos los datos requeridos en Linderos y Coordenadas');
		document.getElementById('tabg_croquis').style.display="none";
		document.getElementById('errorcatp_6').style.display="block";
	}
	}else{
		document.getElementById('tabg_croquis').style.display="none";
	}
	return valida_tab;
}


function guardar_ficha_catastral(){
	var captura_vtabg = activex_tabg_croquis();
	var valida_tab = false;
	if(captura_vtabg == true){
	if(eval(reemplazarPC($('ide_aconst').value))>eval(0)){
	if(eval($('nume_niveles').value) >= eval($('nume_edificaciones').value)){
		control_nlist = $('nume_niveles').value;
		denomina_tpgn = 'niveles';
	}else if(eval($('nume_edificaciones').value) >= eval($('nume_niveles').value)){
		control_nlist = $('nume_edificaciones').value;
		denomina_tpgn = 'edificaciones';
	}else{
		control_nlist = 0;
		denomina_tpgn = 'niveles y edificaciones';
	}
	if(eval($('ctrl_num_ne').value) < eval(control_nlist)){
     	fun_msj('Disculpe hay ['+$('ctrl_num_ne').value+'] tipolog&iacute;as agregadas, debe a&ntilde;adir m&aacute;s a la lista debido al n&uacute;mero de '+denomina_tpgn+' ['+control_nlist+']');
		valida_tab = false;
	}else{

	if(document.getElementById('numero_catastral').value!='')
		if(document.getElementById('direccion_inmb').value!='')
			if(document.getElementById('croquis_fecha').value!='')
				if(document.getElementById('croquis_escala').value!='')
					if(document.getElementById('croquis_observaciones').value!='')
						if(document.getElementById('calculo_terreno').value!='')
							if(document.getElementById('calculo_construccion').value!='')
								if(document.getElementById('elaborado_nombre_c').value!='')
									if(document.getElementById('elaborado_ci_c').value!='')
										if(document.getElementById('revisado_nombre_c').value!='')
											if(document.getElementById('revisado_ci_c').value!='')
												valida_tab = true;
	if(valida_tab == true){}
	else{
		fun_msj('Por favor debe ingresar todos los datos requeridos en Croquis');
		document.getElementById('errorcatp_7').style.display="block";
	}
	}
	}else{
	if(document.getElementById('numero_catastral').value!='')
		if(document.getElementById('direccion_inmb').value!='')
			if(document.getElementById('croquis_fecha').value!='')
				if(document.getElementById('croquis_escala').value!='')
					if(document.getElementById('croquis_observaciones').value!='')
						if(document.getElementById('calculo_terreno').value!='')
							if(document.getElementById('calculo_construccion').value!='')
								if(document.getElementById('elaborado_nombre_c').value!='')
									if(document.getElementById('elaborado_ci_c').value!='')
										if(document.getElementById('revisado_nombre_c').value!='')
											if(document.getElementById('revisado_ci_c').value!='')
												valida_tab = true;
	if(valida_tab == true){}
	else{
		fun_msj('Por favor debe ingresar todos los datos requeridos en Croquis');
		document.getElementById('errorcatp_7').style.display="block";
	}
	}
	}
	return valida_tab;
}


*/
// FIN FUNCIONES REPETIDAS
// --------------------------


function activex_tabg_datos_ocupante(){
	var valida_tab = false;
	if(document.getElementById('cod_unidad').value!='')
		if(document.getElementById('dir_inm_descripcionren').value!='' && document.getElementById('dir_inm_descripcionren').value!='ESPECIFIQUE...')
			if(document.getElementById('dir_inm_descripcionrene').value!='' && document.getElementById('dir_inm_descripcionrene').value!='ESPECIFIQUE...')
				if(document.getElementById('dir_inm_descripcionreney').value!='' && document.getElementById('dir_inm_descripcionreney').value!='ESPECIFIQUE...')
					if($('condicion_ocup_1').checked==true || $('condicion_ocup_2').checked==true || $('condicion_ocup_3').checked==true || $('condicion_ocup_4').checked==true || $('condicion_ocup_5').checked==true || $('condicion_ocup_6').checked==true || $('condicion_ocup_7').checked==true || $('condicion_ocup_8').checked==true || $('condicion_ocup_9').checked==true || $('condicion_ocup_10').checked==true || $('condicion_ocup_11').checked==true || $('condicion_ocup_12').checked==true)
					valida_tab = true;
	if(valida_tab == true){document.getElementById('tabg_datos_ocupante').style.display="block"; document.getElementById('errorcatp_1').style.display="none";}
	else{
		fun_msj('Por favor debe ingresar todos los datos requeridos en Datos Generales');
		document.getElementById('tabg_datos_ocupante').style.display="none";
		document.getElementById('errorcatp_1').style.display="block";
	}
	return valida_tab;
}


function activex_tabg_datos_terreno(){
	var captura_vtabg = activex_tabg_datos_ocupante();
	var valida_tab = false;
	if(captura_vtabg == true){
	if($('datos_ocupante_personalidad_1').checked==true || $('datos_ocupante_personalidad_2').checked==true)
		if(document.getElementById('datos_ocupante_ci_rif').value!='')
			if(document.getElementById('datos_ocupante_nombre').value!='')
				if(document.getElementById('dir_inm_descripcionren22').value!='' && document.getElementById('dir_inm_descripcionren2').value!='ESPECIFIQUE...')
					if(document.getElementById('dir_inm_descripcionrene22').value!='' && document.getElementById('dir_inm_descripcionrene2').value!='ESPECIFIQUE...')
						if(document.getElementById('dir_inm_descripcionreney22').value!='' && document.getElementById('dir_inm_descripcionreney2').value!='ESPECIFIQUE...')
	if($('datos_ocupante_personalidad_p_1').checked==true || $('datos_ocupante_personalidad_p_2').checked==true)
		if(document.getElementById('datos_ocupante_ci_rif_p').value!='')
			if(document.getElementById('datos_ocupante_nombre_p').value!='')
				if(document.getElementById('dir_inm_descripcionren2').value!='' && document.getElementById('dir_inm_descripcionren2').value!='ESPECIFIQUE...')
					if(document.getElementById('dir_inm_descripcionrene2').value!='' && document.getElementById('dir_inm_descripcionrene2').value!='ESPECIFIQUE...')
						if(document.getElementById('dir_inm_descripcionreney2').value!='' && document.getElementById('dir_inm_descripcionreney2').value!='ESPECIFIQUE...')
							valida_tab = true;
	if(valida_tab == true){document.getElementById('tabg_datos_terreno').style.display="block"; document.getElementById('errorcatp_2').style.display="none";}
	else{
		fun_msj('Por favor debe ingresar todos los datos requeridos en Datos del Ocupante');
		document.getElementById('tabg_datos_terreno').style.display="none";
		document.getElementById('errorcatp_2').style.display="block";
	}
	}else{
		document.getElementById('tabg_datos_terreno').style.display="none";
	}
	return valida_tab;
}


function activex_tabg_datos_gen_const(){
	var captura_vtabg = activex_tabg_datos_terreno();
	var valida_tab = false;
	if(captura_vtabg == true){
	if($('tilde_topo_1').checked==true || $('tilde_topo_2').checked==true || $('tilde_topo_3').checked==true || $('tilde_topo_4').checked==true || $('tilde_topo_5').checked==true || $('tilde_topo_6').checked==true || $('tilde_topo_7').checked==true || $('tilde_topo_8').checked==true)
		if($('tilde_acceso_1').checked==true || $('tilde_acceso_2').checked==true || $('tilde_acceso_3').checked==true || $('tilde_acceso_4').checked==true || $('tilde_acceso_5').checked==true || $('tilde_acceso_6').checked==true || $('tilde_acceso_7').checked==true || $('tilde_acceso_8').checked==true || $('tilde_acceso_9').checked==true)
			if($('tilde_forma_regular_1').checked==true || $('tilde_forma_regular_2').checked==true || $('tilde_forma_regular_3').checked==true)
				if($('tilde_ubica_1').checked==true || $('tilde_ubica_2').checked==true || $('tilde_ubica_3').checked==true || $('tilde_ubica_4').checked==true)
					if($('tilde_entorno_zona_1').checked==true || $('tilde_entorno_zona_2').checked==true || $('tilde_entorno_zona_3').checked==true || $('tilde_entorno_zona_4').checked==true || $('tilde_entorno_zona_5').checked==true)
						if($('tilde_uso_residencial').checked==true || $('tilde_uso_religioso').checked==true || $('tilde_uso_comercial').checked==true || $('tilde_uso_sin_uso').checked==true || $('tilde_uso_industrial').checked==true || $('tilde_uso_otro').checked==true || $('tilde_uso_recreativo').checked==true || $('tilde_uso_asistencia').checked==true || $('tilde_uso_educacional').checked==true || $('tilde_uso_turistico').checked==true || $('tilde_uso_social_cultural').checked==true || $('tilde_uso_gubernamental').checked==true)
							if($('tilde_mejoras_muro_contencion').checked==true || $('tilde_mejoras_nivelacion').checked==true || $('tilde_mejoras_cercado').checked==true || $('tilde_mejoras_pozo_septico').checked==true || $('tilde_mejoras_lagunas_art').checked==true || $('tilde_mejoras_otro').checked==true)
								if($('tilde_tenencia_1').checked==true || $('tilde_tenencia_2').checked==true || $('tilde_tenencia_3').checked==true || $('tilde_tenencia_4').checked==true || $('tilde_tenencia_5').checked==true || $('tilde_tenencia_6').checked==true || $('tilde_tenencia_7').checked==true || $('tilde_tenencia_8').checked==true || $('tilde_tenencia_9').checked==true)
									if($('tilde_reg_prop_1').checked==true || $('tilde_reg_prop_2').checked==true || $('tilde_reg_prop_3').checked==true || $('tilde_reg_prop_4').checked==true || $('tilde_reg_prop_5').checked==true || $('tilde_reg_prop_6').checked==true || $('tilde_reg_prop_7').checked==true || $('tilde_reg_prop_8').checked==true)
										if($('tilde_serv_acueducto').checked==true || $('tilde_serv_transporte').checked==true || $('tilde_serv_cloacas').checked==true || $('tilde_serv_telefono').checked==true || $('tilde_serv_drenaje_art').checked==true || $('tilde_serv_cable_tv').checked==true || $('tilde_serv_elect_residencial').checked==true || $('tilde_serv_tvsate').checked==true || $('tilde_serv_elect_indus').checked==true || $('tilde_serv_correo_telec').checked==true || $('tilde_serv_alumbrado').checked==true || $('tilde_serv_gas').checked==true || $('tilde_serv_vialidad').checked==true || $('tilde_serv_aseo').checked==true || $('tilde_serv_pavimento').checked==true || $('tilde_serv_otro').checked==true || $('tilde_serv_acera').checked==true)
											if($('radio_afect_leg_1').checked==true || $('radio_afect_leg_2').checked==true || $('radio_afect_leg_3').checked==true || $('radio_afect_leg_4').checked==true || $('radio_afect_leg_5').checked==true)
												if(document.getElementById('normativa_gaceta').value!='')
													if(document.getElementById('normativa_resolucion').value!='')
														if(document.getElementById('normativa_fecha').value!='')
															if(document.getElementById('ubic_zonificacion').value!='')
																valida_tab = true;
	if(valida_tab == true){document.getElementById('tabg_datos_gen_const').style.display="block"; document.getElementById('errorcatp_3').style.display="none";}
	else{
		fun_msj('Por favor debe ingresar todos los datos requeridos en Datos del Terreno');
		document.getElementById('tabg_datos_gen_const').style.display="none";
		document.getElementById('errorcatp_3').style.display="block";
	}
	}else{
		document.getElementById('tabg_datos_gen_const').style.display="none";
	}
	return valida_tab;
}


function activex_tabg_datos_reg_valor_emco(){
	var captura_vtabg = activex_tabg_datos_gen_const();
	var valida_tab = false;
	if(captura_vtabg == true){
	if($('tilde_tipo_1').checked==true || $('tilde_tipo_2').checked==true || $('tilde_tipo_3').checked==true || $('tilde_tipo_4').checked==true || $('tilde_tipo_5').checked==true || $('tilde_tipo_6').checked==true || $('tilde_tipo_7').checked==true || $('tilde_tipo_8').checked==true || $('tilde_tipo_9').checked==true || $('tilde_tipo_10').checked==true || $('tilde_tipo_11').checked==true || $('tilde_tipo_12').checked==true || $('tilde_tipo_13').checked==true || $('tilde_tipo_14').checked==true || $('tilde_tipo_15').checked==true || $('tilde_tipo_16').checked==true || $('tilde_tipo_17').checked==true || $('tilde_tipo_18').checked==true)
		if($('tilde_desc_uso_1').checked==true || $('tilde_desc_uso_2').checked==true || $('tilde_desc_uso_3').checked==true || $('tilde_desc_uso_4').checked==true || $('tilde_desc_uso_5').checked==true || $('tilde_desc_uso_6').checked==true || $('tilde_desc_uso_7').checked==true || $('tilde_desc_uso_8').checked==true || $('tilde_desc_uso_9').checked==true || $('tilde_desc_uso_10').checked==true)
			if($('tilde_tenencia_cons_1').checked==true || $('tilde_tenencia_cons_2').checked==true || $('tilde_tenencia_cons_3').checked==true || $('tilde_tenencia_cons_4').checked==true || $('tilde_tenencia_cons_5').checked==true || $('tilde_tenencia_cons_6').checked==true || $('tilde_tenencia_cons_7').checked==true || $('tilde_tenencia_cons_8').checked==true || $('tilde_tenencia_cons_9').checked==true)
				if($('tilde_regimen_prop_1').checked==true || $('tilde_regimen_prop_2').checked==true || $('tilde_regimen_prop_3').checked==true || $('tilde_regimen_prop_4').checked==true || $('tilde_regimen_prop_5').checked==true || $('tilde_regimen_prop_6').checked==true)
					if($('tilde_soporte_concreto_armado').checked==true || $('tilde_soporte_metalica').checked==true || $('tilde_soporte_madera').checked==true || $('tilde_soporte_paredes_carga').checked==true || $('tilde_soporte_prefrabicado').checked==true || $('tilde_soporte_machones').checked==true || $('tilde_soporte_otro').checked==true)
						if($('tilde_techo_concreto_armado').checked==true || $('tilde_techo_metalica').checked==true || $('tilde_techo_madera').checked==true || $('tilde_techo_varas').checked==true || $('tilde_techo_cerchas').checked==true || $('tilde_techo_al').checked==true || $('tilde_techo_otro').checked==true)
							if($('tilde_cubierta_madera_teja').checked==true || $('tilde_cubierta_acerolit').checked==true || $('tilde_cubierta_placa_teja').checked==true || $('tilde_cubierta_palma').checked==true || $('tilde_cubierta_tejas_canabrava').checked==true || $('tilde_cubierta_tabelon').checked==true || $('tilde_cubierta_platabanda').checked==true || $('tilde_cubierta_barro').checked==true || $('tilde_cubierta_asbesto').checked==true || $('tilde_cubierta_riple').checked==true || $('tilde_cubierta_aluminio').checked==true || $('tilde_cubierta_cinduteja').checked==true || $('tilde_cubierta_zinc').checked==true || $('tilde_cubierta_otro').checked==true)
								if($('tilde_cubiertai_machi').checked==true || $('tilde_cubiertai_plafon').checked==true || $('tilde_cubiertai_crl').checked==true || $('tilde_cubiertai_cre').checked==true || $('tilde_cubiertai_sinc').checked==true || $('tilde_cubiertai_otro').checked==true)
									if($('tilde_pared_tipo_bloque_cemento').checked==true || $('tilde_pared_tipo_bloque_arcilla').checked==true || $('tilde_pared_tipo_ladrillo').checked==true || $('tilde_pared_tipo_adobe').checked==true || $('tilde_pared_tipo_tapia').checked==true || $('tilde_pared_tipo_bahareque').checked==true || $('tilde_pared_tipo_prefabricada').checked==true || $('tilde_pared_tipo_vidrio').checked==true || $('tilde_pared_tipo_madera_aserrada').checked==true || $('tilde_pared_tipo_zinc_laton').checked==true || $('tilde_pared_tipo_carton').checked==true || $('tilde_pared_tipo_sin_paredes').checked==true || $('tilde_pared_tipo_otro').checked==true)
										if($('tilde_pared_acabado_friso_liso').checked==true || $('tilde_pared_acabado_friso_rustico').checked==true || $('tilde_pared_acabado_ol').checked==true || $('tilde_pared_acabado_c').checked==true || $('tilde_pared_acabado_porc').checked==true || $('tilde_pared_acabado_cemenb').checked==true || $('tilde_pared_acabado_papelt').checked==true || $('tilde_pared_acabado_vidrio').checked==true || $('tilde_pared_acabado_yeso_cal').checked==true || $('tilde_pared_acabado_baldosa').checked==true || $('tilde_pared_acabado_sin_friso').checked==true || $('tilde_pared_acabado_otro').checked==true)
											if($('tilde_pintura_caucho').checked==true || $('tilde_pintura_oleo').checked==true || $('tilde_pintura_pasta').checked==true || $('tilde_pintura_asbestina').checked==true || $('tilde_pintura_lechada').checked==true || $('tilde_pintura_sinpintura').checked==true || $('tilde_pintura_otro').checked==true)
												if($('tilde_instalac_embutida').checked==true || $('tilde_instalac_externa').checked==true || $('tilde_instalac_industrial').checked==true)
													if($('tilde_piso_ceramica').checked==true || $('tilde_piso_porcelanato').checked==true || $('tilde_piso_granito').checked==true || $('tilde_piso_parquet').checked==true || $('tilde_piso_marmol').checked==true || $('tilde_piso_vinil').checked==true || $('tilde_piso_mosaico').checked==true || $('tilde_piso_madera').checked==true || $('tilde_piso_cemento_pulido').checked==true || $('tilde_piso_cemento_rustico').checked==true || $('tilde_piso_ladrillo').checked==true || $('tilde_piso_terracota').checked==true || $('tilde_piso_sinpiso').checked==true || $('tilde_piso_otro').checked==true)
														if($('tilde_conserva_1').checked==true || $('tilde_conserva_2').checked==true || $('tilde_conserva_3').checked==true || $('tilde_conserva_4').checked==true)
															if(document.getElementById('ano_construccion').value!='' && eval(document.getElementById('ano_construccion').value)!=eval(0))
																if(document.getElementById('nume_niveles').value!='' && eval(document.getElementById('nume_niveles').value)!=eval(0))
																	if(document.getElementById('nume_edificaciones').value!='' && eval(document.getElementById('nume_edificaciones').value)!=eval(0))
																		if(eval(reemplazarPC($('ide_ater').value))>eval(0))
																			if(eval(reemplazarPC($('ide_aconst').value))>eval(0))
																				if(eval(reemplazarPC($('total_porc_const').value))==eval(100))
																					if($('serv_inm_abast_agua_1').checked==true || $('serv_inm_abast_agua_2').checked==true || $('serv_inm_abast_agua_3').checked==true || $('serv_inm_abast_agua_4').checked==true || $('serv_inm_abast_agua_5').checked==true || $('serv_inm_abast_agua_6').checked==true)
																						if($('serv_inm_electrico_1').checked==true || $('serv_inm_electrico_2').checked==true || $('serv_inm_electrico_3').checked==true)
																							if($('serv_inm_energia_1').checked==true || $('serv_inm_energia_2').checked==true || $('serv_inm_energia_3').checked==true || $('serv_inm_energia_4').checked==true || $('serv_inm_energia_5').checked==true)
																								// if(document.getElementById('numero_serv_inm_telef').value!='' || document.getElementById('numero_serv_inm_resid').value!='' || document.getElementById('numero_serv_inm_com').value!='' || document.getElementById('numero_serv_inm_otro').value!='' || document.getElementById('numero_serv_inm_sinserv').value!='')
																									if($('serv_inm_internet_1').checked==true || $('serv_inm_internet_2').checked==true || $('serv_inm_internet_3').checked==true || $('serv_inm_internet_4').checked==true || $('serv_inm_internet_5').checked==true || $('serv_inm_internet_6').checked==true)
																										if($('serv_inm_agua_servida_1').checked==true || $('serv_inm_agua_servida_2').checked==true || $('serv_inm_agua_servida_3').checked==true || $('serv_inm_agua_servida_4').checked==true || $('serv_inm_agua_servida_5').checked==true)
																											if($('serv_inm_aseo_urbano_1').checked==true || $('serv_inm_aseo_urbano_2').checked==true || $('serv_inm_aseo_urbano_3').checked==true || $('serv_inm_aseo_urbano_4').checked==true)
																												if($('serv_inm_abastag_dist_1').checked==true || $('serv_inm_abastag_dist_2').checked==true || $('serv_inm_abastag_dist_3').checked==true || $('serv_inm_abastag_dist_4').checked==true || $('serv_inm_abastag_dist_5').checked==true)
																													if($('serv_inm_aseou_dist_1').checked==true || $('serv_inm_aseou_dist_2').checked==true || $('serv_inm_aseou_dist_3').checked==true || $('serv_inm_aseou_dist_4').checked==true)
																														if(document.getElementById('inum_pa').value!='0')
																															if($('tilde_tenencia_cons_2').checked==true){
																																if(document.getElementById('canon_mensual').value!='0,00' && document.getElementById('fecha_contrato').value!='')
																																	valida_tab = true;
																																else
																																	valida_tab = false;
																															}else{valida_tab = true;}
	if(valida_tab == true){document.getElementById('tabg_datos_reg_valor_emco').style.display="block"; document.getElementById('errorcatp_4').style.display="none";}
	else{
		fun_msj('Por favor debe ingresar todos los datos requeridos en Datos Generales de la Construcci&oacute;n');
		document.getElementById('tabg_datos_reg_valor_emco').style.display="none";
		document.getElementById('errorcatp_4').style.display="block";
	}
	}else{
		document.getElementById('tabg_datos_reg_valor_emco').style.display="none";
	}
	return valida_tab;
}


function activex_tabg_linderos_coord(){
	var captura_vtabg = activex_tabg_datos_reg_valor_emco();
	var valida_tab = false;
	if(captura_vtabg == true){
		if(document.getElementById('observaciones_ficha').value!='')
			if(document.getElementById('impe_anual').value!='')
				if(document.getElementById('impe_trime').value!='')
					valida_tab = true;
	if(valida_tab == true){document.getElementById('tabg_linderos_coord').style.display="block"; document.getElementById('errorcatp_5').style.display="none";}
	else{
		fun_msj('Por favor debe ingresar todos los datos requeridos en Valoraci&oacute;n Econ&oacute;mica');
		document.getElementById('tabg_linderos_coord').style.display="none";
		document.getElementById('errorcatp_5').style.display="block";
	}
	}else{
		document.getElementById('tabg_linderos_coord').style.display="none";
	}
	return valida_tab;
}


function activex_tabg_croquis(){
	var captura_vtabg = activex_tabg_linderos_coord();
	var valida_tab = false;
	if(captura_vtabg == true){
		if(document.getElementById('lindero_norte').value!='')
			if(document.getElementById('lindero_sur').value!='')
				if(document.getElementById('lindero_este').value!='')
					if(document.getElementById('lindero_oeste').value!='')
						if(document.getElementById('situacion_relativa').value!='')
							if(document.getElementById('coordenada_norte').value!='')
								if(document.getElementById('coordenada_este').value!='')
									if(document.getElementById('huso').value!='')
										if(document.getElementById('observaciones_lind_coord').value!='')
											if(document.getElementById('fecha_primera_visita').value!='')
												if(document.getElementById('fecha_levantamiento').value!='')
													if(document.getElementById('elaborado_nombre').value!='')
														if(document.getElementById('elaborado_ci').value!='')
															if(document.getElementById('revisado_nombre').value!='')
																if(document.getElementById('revisado_ci').value!='')
																	valida_tab = true;
	if(valida_tab == true){document.getElementById('tabg_croquis').style.display="block"; document.getElementById('errorcatp_6').style.display="none";}
	else{
		fun_msj('Por favor debe ingresar todos los datos requeridos en Linderos y Coordenadas');
		document.getElementById('tabg_croquis').style.display="none";
		document.getElementById('errorcatp_6').style.display="block";
	}
	}else{
		document.getElementById('tabg_croquis').style.display="none";
	}
	return valida_tab;
}


function guardar_ficha_catastral(){
	var captura_vtabg = activex_tabg_croquis();
	var valida_tab = false;
	if(captura_vtabg == true){
	if(eval(reemplazarPC($('ide_aconst').value))>eval(0)){
	if(eval($('nume_niveles').value) >= eval($('nume_edificaciones').value)){
		control_nlist = $('nume_niveles').value;
		denomina_tpgn = 'niveles';
	}else if(eval($('nume_edificaciones').value) >= eval($('nume_niveles').value)){
		control_nlist = $('nume_edificaciones').value;
		denomina_tpgn = 'edificaciones';
	}else{
		control_nlist = 0;
		denomina_tpgn = 'niveles y edificaciones';
	}
	if(eval($('ctrl_num_ne').value) < eval(control_nlist)){
     	fun_msj('Disculpe hay ['+$('ctrl_num_ne').value+'] tipolog&iacute;as agregadas, debe a&ntilde;adir m&aacute;s a la lista debido al n&uacute;mero de '+denomina_tpgn+' ['+control_nlist+']');
		valida_tab = false;
	}else{

	if(document.getElementById('numero_catastral').value!='')
		if(document.getElementById('direccion_inmb').value!='')
			if(document.getElementById('croquis_fecha').value!='')
				if(document.getElementById('croquis_escala').value!='')
					if(document.getElementById('croquis_observaciones').value!='')
						if(document.getElementById('calculo_terreno').value!='')
							if(document.getElementById('calculo_construccion').value!='')
								if(document.getElementById('elaborado_nombre_c').value!='')
									if(document.getElementById('elaborado_ci_c').value!='')
										if(document.getElementById('revisado_nombre_c').value!='')
											if(document.getElementById('revisado_ci_c').value!='')
												valida_tab = true;
	if(valida_tab == true){}
	else{
		fun_msj('Por favor debe ingresar todos los datos requeridos en Croquis');
		document.getElementById('errorcatp_7').style.display="block";
	}
	}
	}else{
	if(document.getElementById('numero_catastral').value!='')
		if(document.getElementById('direccion_inmb').value!='')
			if(document.getElementById('croquis_fecha').value!='')
				if(document.getElementById('croquis_escala').value!='')
					if(document.getElementById('croquis_observaciones').value!='')
						if(document.getElementById('calculo_terreno').value!='')
							if(document.getElementById('calculo_construccion').value!='')
								if(document.getElementById('elaborado_nombre_c').value!='')
									if(document.getElementById('elaborado_ci_c').value!='')
										if(document.getElementById('revisado_nombre_c').value!='')
											if(document.getElementById('revisado_ci_c').value!='')
												valida_tab = true;
	if(valida_tab == true){}
	else{
		fun_msj('Por favor debe ingresar todos los datos requeridos en Croquis');
		document.getElementById('errorcatp_7').style.display="block";
	}
	}
	}
	if(valida_tab == true){
		if(document.getElementById('datos_ocupante_personalidad_2').checked==true && document.getElementById('datos_ocupante_ci_rif').value!=''){
			if(verifica_riff('datos_ocupante_ci_rif')==false){
				fun_msj('Por favor revise el Rif del Ocupante.');
				valida_tab = false;
			}
		}
		else if(document.getElementById('datos_ocupante_personalidad_p_2').checked==true && document.getElementById('datos_ocupante_ci_rif_p').value!=''){
			if(verifica_riff('datos_ocupante_ci_rif_p')==false){
				fun_msj('Por favor revise el Rif del Propietario / Representante Legal / Administrador.');
				valida_tab = false;
			}
		}
	}
	if(valida_tab == true){
		if(validacom_email('email_ocupa')==false){
			valida_tab = false;
		}
		else if(validacom_email('pto_refe2_k')==false){
			valida_tab = false;
		}
	}
	return valida_tab;
}


function disabledAllCradio(IDFROM,bool) {
	var inputs = document.getElementById(IDFROM).getElementsByTagName('input');
	for (var i=0; i<inputs.length; i++) {
		if (inputs[i].type == 'radio'){
			if(inputs[i].checked==true)
				inputs[i].style.outline='1px solid #F00';
			inputs[i].disabled = bool;
		}
	}
}

function disabledAllCcheckbox(IDFROM,bool) {
	var inputs = document.getElementById(IDFROM).getElementsByTagName('input');
	for (var i=0; i<inputs.length; i++) {
		if (inputs[i].type == 'checkbox'){
			if(inputs[i].checked==true)
				inputs[i].style.outline='1px solid #F00';
			inputs[i].disabled = bool;
		}
	}
}

function activexAllCElements(boole) {
	$('modificar').disabled=(!boole);
	$('guardar').disabled=boole;
	$('modificar').blur();
	$('guardar').blur();
	$('ano_ordenanza').disabled=boole;
	$('select_3').disabled=boole;
	$('select_4').disabled=boole;

	$('select2_3').disabled=boole;
	$('select2_4').disabled=boole;

	$('cod_ant_manzana').readOnly=boole;
	$('cod_ant_parcela').readOnly=boole;
	$('cod_bloque').readOnly=boole;
	$('cod_piso').readOnly=boole;
	$('cod_apto').readOnly=boole;

	$('cod_manzana').readOnly=boole;
	$('cod_parcela').readOnly=boole;
	$('cod_sub_parcela').readOnly=boole;
	$('cod_nivel').readOnly=boole;
	$('cod_unidad').readOnly=boole;

	$('dir_inm_descripcionren').readOnly=boole;
	$('dir_inm_descripcionrene').readOnly=boole;
	$('dir_inm_descripcionreney').readOnly=boole;
	$('nombre_imb_k').readOnly=boole;
	$('num_civico_k').readOnly=boole;
	$('telefono_k').readOnly=boole;
	$('pto_refe_k').readOnly=boole;

	$('datos_ocupante_ci_rif').readOnly=boole;
	$('dir_inm_descripcionren22').readOnly=boole;
	$('dir_inm_descripcionrene22').readOnly=boole;
	$('dir_inm_descripcionreney22').readOnly=boole;
	$('email_ocupa').readOnly=boole;

	$('datos_ocupante_ci_rif_p').readOnly=boole;
	$('dir_inm_descripcionren2').readOnly=boole;
	$('dir_inm_descripcionrene2').readOnly=boole;
	$('dir_inm_descripcionreney2').readOnly=boole;
	$('pto_refe2_k').readOnly=boole;

	$('normativa_gaceta').readOnly=boole;
	$('normativa_resolucion').readOnly=boole;
	$('ubic_zonificacion').readOnly=boole;

	$('canon_mensual').readOnly=boole;
	$('ingreso_familiar').readOnly=boole;

	$('numero_banera').readOnly=boole;
	$('numero_wc').readOnly=boole;
	$('numero_bidet').readOnly=boole;
	$('numero_lavamanos').readOnly=boole;
	$('numero_duchas').readOnly=boole;
	$('numero_ceramica_uno').readOnly=boole;
	$('numero_ceramica_dos').readOnly=boole;
	$('numero_bano_otro').readOnly=boole;

	$('numero_ventanal').readOnly=boole;
	$('numero_celosia').readOnly=boole;
	$('numero_corredera').readOnly=boole;
	$('numero_basculante').readOnly=boole;
	$('numero_batiente').readOnly=boole;
	$('numero_panoramica').readOnly=boole;

	$('numero_entamborad').readOnly=boole;
	$('numero_entamborad_econ').readOnly=boole;
	$('numero_madera_cepillada').readOnly=boole;
	$('numero_metalicas').readOnly=boole;
	$('numero_seguridad').readOnly=boole;
	$('numero_vidrio').readOnly=boole;
	$('numero_puertas_otro').readOnly=boole;

	$('numero_dorm_hab').readOnly=boole;
	$('numero_comedor').readOnly=boole;
	$('numero_sala').readOnly=boole;
	$('numero_bano_econ').readOnly=boole;
	$('numero_bano_lujoso').readOnly=boole;
	$('numero_cocina').readOnly=boole;
	$('numero_area_serv').readOnly=boole;
	$('numero_estudio').readOnly=boole;
	$('numero_garaje').readOnly=boole;
	$('numero_estacionamiento').readOnly=boole;
	$('numero_maletero').readOnly=boole;
	$('numero_sotano').readOnly=boole;
	$('numero_letrinap').readOnly=boole;
	$('numero_otro_ambiente').readOnly=boole;

	$('numero_ascensor').readOnly=boole;
	$('numero_aire_acond').readOnly=boole;
	$('numero_rejas_puertas').readOnly=boole;
	$('numero_rejas_vent').readOnly=boole;
	$('numero_closet').readOnly=boole;
	$('numero_ceramicas').readOnly=boole;
	$('numero_puerta_stam').readOnly=boole;
	$('numero_neumatico').readOnly=boole;
	$('numero_jacuzzi').readOnly=boole;
	$('numero_calentador').readOnly=boole;
	$('numero_piscina').readOnly=boole;
	$('cant_ag_piscina').readOnly=boole;
	$('numero_tanque_tc').readOnly=boole;
	$('espec_capac_tanque_tc').readOnly=boole;
	$('capacidad_tanque_tc').readOnly=boole;
	$('numero_otro_complemento').readOnly=boole;

	$('numero_construccion').readOnly=boole;
	$('numero_habitabilidad').readOnly=boole;
	$('numero_demolicion').readOnly=boole;

	$('ano_construccion').readOnly=boole;
	$('anio_refaccion').readOnly=boole;
	$('porce_refraccion').readOnly=boole;
	$('nume_niveles').readOnly=boole;
	$('nume_edificaciones').readOnly=boole;
	$('nro_familias').readOnly=boole;
	$('ide_ater').readOnly=boole;

	$('monto_hipoteca').readOnly=boole;
	$('monto_expropiacion').readOnly=boole;

	$('area_cc_a').readOnly=boole;
	$('area_cc_b').readOnly=boole;
	$('area_cc_c').readOnly=boole;
	$('area_cc_d').readOnly=boole;
	$('area_cc_e').readOnly=boole;
	$('area_cc_f').readOnly=boole;

	if(eval(reemplazarPC($('area_cc_a').value))>eval(0)){
		$('porc_cc_a').readOnly=false;
	}else{
		$('porc_cc_a').readOnly=true;
	}

	if(eval(reemplazarPC($('area_cc_b').value))>eval(0)){
		$('porc_cc_b').readOnly=false;
	}else{
		$('porc_cc_b').readOnly=true;
	}

	if(eval(reemplazarPC($('area_cc_c').value))>eval(0)){
		$('porc_cc_c').readOnly=false;
	}else{
		$('porc_cc_c').readOnly=true;
	}

	if(eval(reemplazarPC($('area_cc_d').value))>eval(0)){
		$('porc_cc_d').readOnly=false;
	}else{
		$('porc_cc_d').readOnly=true;
	}

	if(eval(reemplazarPC($('area_cc_e').value))>eval(0)){
		$('porc_cc_e').readOnly=false;
	}else{
		$('porc_cc_e').readOnly=true;
	}

	if(eval(reemplazarPC($('area_cc_f').value))>eval(0)){
		$('porc_cc_f').readOnly=false;
	}else{
		$('porc_cc_f').readOnly=true;
	}

/*
	$('porc_cc_a').readOnly=boole;
	$('porc_cc_b').readOnly=boole;
	$('porc_cc_c').readOnly=boole;
	$('porc_cc_d').readOnly=boole;
	$('porc_cc_e').readOnly=boole;
	$('porc_cc_f').readOnly=boole;
*/

	$('espec_electric').readOnly=boole;

	$('numero_serv_inm_telef').readOnly=boole;
	$('numero_serv_inm_resid').readOnly=boole;
	$('numero_serv_inm_com').readOnly=boole;
	$('numero_serv_inm_otro').readOnly=boole;
	$('numero_serv_inm_sinserv').readOnly=boole;

	$('pob_adulta_edad').readOnly=boole;
	$('pob_adulta_neduc').readOnly=boole;
	$('pob_adulta_oprof').readOnly=boole;
	$('pob_adulta_ltrab').readOnly=boole;
	$('pob_adulta_trans').readOnly=boole;
	$('boton_add_pa').disabled=boole;
	if($('limpiar_lfpa')) $('limpiar_lfpa').disabled=boole;

	$('pob_infantil_edad').readOnly=boole;
	$('pob_infantil_neduc').readOnly=boole;
	$('pob_infantil_nomb_inst').readOnly=boole;
	$('pob_infantil_trans').readOnly=boole;
	$('boton_add_pi').disabled=boole;
	if($('limpiar_lfpi')) $('limpiar_lfpi').disabled=boole;
	$('observaciones_poblac').readOnly=boole;

	$('vselect2_4').disabled=boole;
	$('select_tipologia').disabled=boole;
	$('plus2').disabled=boole;
	if($('limpiar_lc')) $('limpiar_lc').disabled=boole;
	if($('limpiar_lv')) $('limpiar_lv').disabled=boole;
	$('select_tipologia_dos').disabled=boole;
	$('valoracion_ajuste_area').readOnly=boole;
	$('valoracion_ajuste_forma').readOnly=boole;
	$('valoracion_valor_ajustado').readOnly=boole;
	$('area_m2').readOnly=boole;
	$('valor_construcci').readOnly=boole;
	$('monto_tota_variables_c').readOnly=boole;
	$('observaciones_ficha').readOnly=boole;
	$('lindero_norte').readOnly=boole;
	$('lindero_sur').readOnly=boole;
	$('lindero_este').readOnly=boole;
	$('lindero_oeste').readOnly=boole;
	$('situacion_relativa').readOnly=boole;
	$('coordenada_norte').readOnly=boole;
	$('coordenada_este').readOnly=boole;
	$('huso').readOnly=boole;
	$('observaciones_lind_coord').readOnly=boole;
	$('elaborado_nombre').readOnly=boole;
	$('elaborado_ci').readOnly=boole;
	$('revisado_nombre').readOnly=boole;
	$('revisado_ci').readOnly=boole;
	$('croquis_escala').readOnly=boole;
	$('croquis_observaciones').readOnly=boole;
	$('elaborado_nombre_c').readOnly=boole;
	$('elaborado_ci_c').readOnly=boole;
	$('revisado_nombre_c').readOnly=boole;
	$('revisado_ci_c').readOnly=boole;
	if(boole==false) var dplay = 'block'; else var dplay = 'none';
	if($('capad_sin_foto')) document.getElementById('capad_sin_foto').style.display='none';
	document.getElementById('capa_imag_cro').style.display=dplay;
}

function plus_fpa(){
 if($('pob_adulta_edad').value==''){
	fun_msj('por favor ingrese la edad');
	$('pob_adulta_edad').focus();
	return false;
 }else if(eval($('pob_adulta_edad').value) < eval(18)){
	fun_msj('por favor ingrese una edad v&aacute;lida');
	$('pob_adulta_edad').focus();
	return false;
 }else if($('pob_adulta_sexo_1').checked==false && $('pob_adulta_sexo_2').checked==false){
	fun_msj('Por favor seleccione el sexo');
	$('pob_adulta_sexo_1').focus();
	return false;
 }else if($('pob_adulta_neduc').value==''){
	fun_msj('por favor ingrese el nivel educativo');
	$('pob_adulta_neduc').focus();
	return false;
 }else if($('pob_adulta_oprof').value==''){
	fun_msj('por favor ingrese la Profesi&oacute;n u Oficio');
	$('pob_adulta_oprof').focus();
	return false;
 }else if($('pob_adulta_ltrab').value==''){
	fun_msj('por favor ingrese el Lugar de Trabajo');
	$('pob_adulta_ltrab').focus();
	return false;
 }else if($('pob_adulta_trans').value==''){
	fun_msj('por favor ingrese el Medio de Transporte');
	$('pob_adulta_trans').focus();
	return false;
 }
}


function plus_fpi(){
 if($('pob_infantil_edad').value==''){
	fun_msj('por favor ingrese la edad');
	$('pob_infantil_edad').focus();
	return false;
 }else if(eval($('pob_infantil_edad').value)<=eval(0)){
	fun_msj('por favor ingrese una edad v&aacute;lida');
	$('pob_infantil_edad').focus();
	return false;
 }else if($('pob_infantil_sexo_1').checked==false && $('pob_infantil_sexo_2').checked==false){
	fun_msj('Por favor seleccione el sexo');
	$('pob_infantil_sexo_1').focus();
	return false;
 }else if($('pob_infantil_neduc').value==''){
	fun_msj('por favor ingrese el nivel educativo');
	$('pob_infantil_neduc').focus();
	return false;
 }else if($('pob_infantil_nomb_inst').value==''){
	fun_msj('por favor ingrese el Lugar y Nombre de la Instituci&oacute;n');
	$('pob_infantil_nomb_inst').focus();
	return false;
 }else if($('pob_infantil_trans').value==''){
	fun_msj('por favor ingrese el Medio de Transporte');
	$('pob_infantil_trans').focus();
	return false;
 }
}


function plus_fpan(idn){

 if(document.getElementById('ceditfpa')){
 	idn=document.getElementById('ceditfpa').value;
 }else{idn=null;}

if(idn!=null){
 if($('eedad_'+idn).value==''){
	fun_msj('por favor ingrese la edad');
	$('eedad_'+idn).focus();
	return false;
 }else if(eval($('eedad_'+idn).value) < eval(18)){
	fun_msj('por favor ingrese una edad v&aacute;lida');
	$('eedad_'+idn).focus();
	return false;
 }else if($('ssexo_'+idn+'_1').checked==false && $('ssexo_'+idn+'_2').checked==false){
	fun_msj('Por favor seleccione el sexo');
	$('ssexo_'+idn+'_1').focus();
	return false;
 }else if($('neduc_'+idn).value==''){
	fun_msj('por favor ingrese el nivel educativo');
	$('neduc_'+idn).focus();
	return false;
 }else if($('oprof_'+idn).value==''){
	fun_msj('por favor ingrese la Profesi&oacute;n u Oficio');
	$('oprof_'+idn).focus();
	return false;
 }else if($('ltrab_'+idn).value==''){
	fun_msj('por favor ingrese el Lugar de Trabajo');
	$('ltrab_'+idn).focus();
	return false;
 }else if($('trans_'+idn).value==''){
	fun_msj('por favor ingrese el Medio de Transporte');
	$('trans_'+idn).focus();
	return false;
 }
}else{
	return true;
}
}


function plus_fpin(idn){

 if(document.getElementById('ceditfpi')){
 	idn=document.getElementById('ceditfpi').value;
 }else{idn=null;}

if(idn!=null){
 if($('eedad2_'+idn).value==''){
	fun_msj('por favor ingrese la edad');
	$('eedad2_'+idn).focus();
	return false;
 }else if(eval($('eedad2_'+idn).value)<=eval(0)){
	fun_msj('por favor ingrese una edad v&aacute;lida');
	$('eedad2_'+idn).focus();
	return false;
 }else if($('ssexo2_'+idn+'_1').checked==false && $('ssexo2_'+idn+'_2').checked==false){
	fun_msj('Por favor seleccione el sexo');
	$('ssexo2_'+idn+'_1').focus();
	return false;
 }else if($('neduc2_'+idn).value==''){
	fun_msj('por favor ingrese el nivel educativo');
	$('neduc2_'+idn).focus();
	return false;
 }else if($('lnomb_inst2_'+idn).value==''){
	fun_msj('por favor ingrese el Lugar de Trabajo');
	$('lnomb_inst2_'+idn).focus();
	return false;
 }else if($('trans2_'+idn).value==''){
	fun_msj('por favor ingrese el Medio de Transporte');
	$('trans2_'+idn).focus();
	return false;
 }
}else{
	return true;
}
}


function tarea_xcategoria(IDP,Var){

	totalaxc = redondear(eval(reemplazarPC($('area_cc_a').value)) + (eval(reemplazarPC($('area_cc_b').value))) + (eval(reemplazarPC($('area_cc_c').value))) + (eval(reemplazarPC($('area_cc_d').value))) + (eval(reemplazarPC($('area_cc_e').value))) + (eval(reemplazarPC($('area_cc_f').value))),2);
	if(eval(totalaxc)<=eval(0)){
		fun_msj('El total del &aacute;rea entre las categor&iacute;as es: '+totalaxc+' no puede ser menor a 0,00 ... verifique el &aacute;rea de las categor&iacute;as');
		totalaxc = 0;
		document.getElementById('ide_aconst').value = totalaxc;
		$('area_m2').value=totalaxc;
	  //moneda('area_m2');
		calcular_fila_valor_actual();
		moneda('ide_aconst');
	}else{
		if(IDP!=null){document.getElementById(IDP).readOnly=false;}
		document.getElementById('ide_aconst').value = totalaxc;
		$('area_m2').value=totalaxc;
      //moneda('area_m2');
		calcular_fila_valor_actual();
		moneda('ide_aconst');
	}

	if(document.getElementById(Var).value=='0,00' || document.getElementById(Var).value=='' || document.getElementById(Var).value=='0'){
		document.getElementById(IDP).value = "0,00";
		document.getElementById(IDP).readOnly=true;
		}

}

function escribir_sn(Var){

	if(document.getElementById(Var).value=='' || document.getElementById(Var).value=='0,00'){
		document.getElementById('ide_aconst').readOnly=true;
	}else{
		document.getElementById('ide_aconst').readOnly=false;

	}

}

function tporc_xcategoria(){
	totalpcc = redondear(eval(reemplazarPC($('porc_cc_a').value)) + (eval(reemplazarPC($('porc_cc_b').value))) + (eval(reemplazarPC($('porc_cc_c').value))) + (eval(reemplazarPC($('porc_cc_d').value))) + (eval(reemplazarPC($('porc_cc_e').value))) + (eval(reemplazarPC($('porc_cc_f').value))),2);
	if(eval(totalpcc)>eval(100)){
		fun_msj('El total del porcentaje entre las categor&iacute;as es: '+totalpcc+' no puede ser mayor a 100,00 % ... verifique el porcentaje de las categor&iacute;as');
		totalpcc = 0;
		document.getElementById('total_porc_const').value = totalpcc;
	    moneda('total_porc_const');
	}else{
		document.getElementById('total_porc_const').value = totalpcc;
		moneda('total_porc_const');
	}
}


function ubicpc(IDE){
	document.getElementById(IDE).focus();
	document.getElementById(IDE).style.background='#008b00';
	document.getElementById(IDE).style.color='#ffffff';
	document.getElementById(IDE).style.fontWeight='bold';
}

function ubixpc(IDE){
	if(document.getElementById(IDE).value!=''){
	}else{
		document.getElementById(IDE).style.background='#ffffff';
		document.getElementById(IDE).style.color='#000000';
		document.getElementById(IDE).style.fontWeight='normal';
	}
}

function ubibpc(IDE){
	if(document.getElementById(IDE).value!=''){
		document.getElementById(IDE).style.background='#008b00';
		document.getElementById(IDE).style.color='#ffffff';
		document.getElementById(IDE).style.fontWeight='bold';
	}else{
		document.getElementById(IDE).style.background='#ffffff';
		document.getElementById(IDE).style.color='#000000';
		document.getElementById(IDE).style.fontWeight='normal';
	}
}
