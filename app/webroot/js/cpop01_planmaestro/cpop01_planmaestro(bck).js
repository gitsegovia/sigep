function capturar_contenido_editor_planmaestro(){
	var fckEditor1val_aux = FCKeditorAPI.__Instances['Contenido_FCK'].GetHTML();
	if(fckEditor1val_aux==''){
		return 1;
	}else{
		contenido = FCKeditorAPI.__Instances['Contenido_FCK'].GetHTML();
		document.getElementById('Contenido_FCK').value = contenido;
		return 0;
	}
}

function validar_cpop01_planmaestro_alcance(){
	if($('codigo_plan').value==''){
	        fun_msj('Debe ingresar el c&oacute;digo del alcance');
			$('codigo_plan').focus();
			return false;
	}else if($('descripcion').value==''){
	        fun_msj('Debe ingresar la descripci&oacute;n del alcance');
			$('descripcion').focus();
			return false;
	}else if($('ano_desde').value==''){
	        fun_msj('Debe ingresar el a&ntilde;o desde');
			$('ano_desde').focus();
			return false;
	}else if($('ano_desde').value.length<4){
			fun_msj('El a&ntilde;o desde debe contener cuatro digitos');
			$('ano_desde').focus();
			return false;
	}else if($('ano_hasta').value==''){
			fun_msj('Debe ingresar el a&ntilde;o hasta');
			$('ano_hasta').focus();
			return false;
	}else if($('ano_hasta').value.length<4){
			fun_msj('El a&ntilde;o hasta debe contener cuatro digitos');
			$('ano_hasta').focus();
			return false;
	}else{
		var editor = capturar_contenido_editor_planmaestro();
		if(editor==1){
			fun_msj('Atencion: Debe especificar el alcance del plan');
			return false;
		}
		var cont = $('control').value;
		cont = eval(cont)+eval(1);
		$('control').value = cont;
		$('control_editor').value = cont;
		return true;
	}
}

function validar_cpop01_planmaestro_introduccion(){
	if($('cod_plan').value==''){
	        fun_msj('Debe seleccionar el alcance');
			$('cod_plan').focus();
			return false;
	}else if($('cod_introd').value==''){
	        fun_msj('Seleccione agregar una nueva introducci&oacute;n por favor');
			$('cod_introd').focus();
			return false;
	}else if($('codigo_introd').value==''){
	        fun_msj('Debe ingresar el c&oacute;digo de la introducci&oacute;n');
			$('codigo_introd').focus();
			return false;
	}else if($('descripcion_introd').value==''){
	        fun_msj('Debe ingresar la descripci&oacute;n de la introducci&oacute;n');
			$('descripcion_introd').focus();
			return false;
	}else{
		var editor = capturar_contenido_editor_planmaestro();
		if(editor==1){
			fun_msj('Atencion: Debe especificar la introducci&oacute;n');
			return false;
		}
		var cont = $('control').value;
		cont = eval(cont)+eval(1);
		$('control').value = cont;
		$('control_editor').value = cont;
		return true;
	}
}

function validar_cpop01_planmaestro_introduccion_mod(){
	if($('codigo_introd').value==''){
	        fun_msj('Debe ingresar el c&oacute;digo de la introducci&oacute;n');
			$('codigo_introd').focus();
			return false;
	}else if($('descripcion_introd').value==''){
	        fun_msj('Debe ingresar la descripci&oacute;n de la introducci&oacute;n');
			$('descripcion').focus();
			return false;
	}else{
		var editor = capturar_contenido_editor_planmaestro();
		if(editor==1){
			fun_msj('Atencion: Debe especificar la introducci&oacute;n');
			return false;
		}
		var cont = $('control').value;
		cont = eval(cont)+eval(1);
		$('control').value = cont;
		$('control_editor').value = cont;
		return true;
	}
}


function validar_cpop01_planmaestro_marco(){
	if($('cod_plan').value==''){
	        fun_msj('Debe seleccionar el alcance');
			$('cod_plan').focus();
			return false;
	}else if($('cod_introd').value==''){
	        fun_msj('Debe seleccionar la introducci&oacute;n');
			$('cod_introd').focus();
			return false;
	}else if($('cod_marco').value==''){
	        fun_msj('Seleccione agregar un nuevo marco metodol&oacute;gico por favor');
			$('cod_marco').focus();
			return false;
	}else if($('codigo_marco').value==''){
	        fun_msj('Debe ingresar el c&oacute;digo del marco metodol&oacute;gico');
			$('codigo_marco').focus();
			return false;
	}else if($('descripcion_marco').value==''){
	        fun_msj('Debe ingresar la descripci&oacute;n del marco metodol&oacute;gico');
			$('descripcion_marco').focus();
			return false;
	}else{
		var editor = capturar_contenido_editor_planmaestro();
		if(editor==1){
			fun_msj('Atencion: Debe especificar el marco metodol&oacute;gico');
			return false;
		}
		var cont = $('control').value;
		cont = eval(cont)+eval(1);
		$('control').value = cont;
		$('control_editor').value = cont;
		return true;
	}
}

function validar_cpop01_planmaestro_marco_mod(){
	if($('codigo_plan').value==''){
	        fun_msj('Debe seleccionar el alcance');
			$('codigo_plan').focus();
			return false;
	}else if($('codigo_introd').value==''){
	        fun_msj('Debe seleccionar la introducci&oacute;n');
			$('codigo_introd').focus();
			return false;
	}else if($('codigo_marco').value==''){
	        fun_msj('Debe ingresar el c&oacute;digo del marco metodol&oacute;gico');
			$('codigo_marco').focus();
			return false;
	}else if($('descripcion_marco').value==''){
	        fun_msj('Debe ingresar la descripci&oacute;n del marco metodol&oacute;gico');
			$('descripcion_marco').focus();
			return false;
	}else{
		var editor = capturar_contenido_editor_planmaestro();
		if(editor==1){
			fun_msj('Atencion: Debe especificar el marco metodol&oacute;gico');
			return false;
		}
		var cont = $('control').value;
		cont = eval(cont)+eval(1);
		$('control').value = cont;
		$('control_editor').value = cont;
		return true;
	}
}

function validar_cpop01_planmaestro_vision(){
	if($('cod_plan').value==''){
	        fun_msj('Debe seleccionar el alcance');
			$('cod_plan').focus();
			return false;
	}else if($('cod_introd').value==''){
	        fun_msj('Debe seleccionar la introducci&oacute;n');
			$('cod_introd').focus();
			return false;
	}else if($('cod_marco').value==''){
	        fun_msj('Debe seleccionar el marco metodol&oacute;gico');
			$('cod_marco').focus();
			return false;
	}else if($('cod_vision').value==''){
	        fun_msj('Seleccione agregar una nueva visi&oacute;n compartida por favor');
			$('cod_vision').focus();
			return false;
	}else if($('codigo_vision').value==''){
	        fun_msj('Debe ingresar el c&oacute;digo de la visi&oacute;n compartida');
			$('codigo_vision').focus();
			return false;
	}else if($('descripcion_vision').value==''){
	        fun_msj('Debe ingresar la descripci&oacute;n de la visi&oacute;n compartida');
			$('descripcion_vision').focus();
			return false;
	}else{
		var editor = capturar_contenido_editor_planmaestro();
		if(editor==1){
			fun_msj('Atencion: Debe especificar la visi&oacute;n compartida');
			return false;
		}
		var cont = $('control').value;
		cont = eval(cont)+eval(1);
		$('control').value = cont;
		$('control_editor').value = cont;
		return true;
	}
}

function validar_cpop01_planmaestro_vision_mod(){
	if($('codigo_plan').value==''){
	        fun_msj('Debe seleccionar el alcance');
			$('codigo_plan').focus();
			return false;
	}else if($('codigo_introd').value==''){
	        fun_msj('Debe seleccionar la introducci&oacute;n');
			$('codigo_introd').focus();
			return false;
	}else if($('codigo_marco').value==''){
	        fun_msj('Debe seleccionar el marco metodol&oacute;gico');
			$('codigo_marco').focus();
			return false;
	}else if($('codigo_vision').value==''){
	        fun_msj('Debe ingresar el c&oacute;digo de la visi&oacute;n compartida');
			$('codigo_vision').focus();
			return false;
	}else if($('descripcion_vision').value==''){
	        fun_msj('Debe ingresar la descripci&oacute;n de la visi&oacute;n compartida');
			$('descripcion_vision').focus();
			return false;
	}else{
		var editor = capturar_contenido_editor_planmaestro();
		if(editor==1){
			fun_msj('Atencion: Debe especificar la visi&oacute;n compartida');
			return false;
		}
		var cont = $('control').value;
		cont = eval(cont)+eval(1);
		$('control').value = cont;
		$('control_editor').value = cont;
		return true;
	}
}

function validar_cpop01_planmaestro_equilibrio(){
	if($('cod_plan').value==''){
	        fun_msj('Debe seleccionar el alcance');
			$('cod_plan').focus();
			return false;
	}else if($('cod_introd').value==''){
	        fun_msj('Debe seleccionar la introducci&oacute;n');
			$('cod_introd').focus();
			return false;
	}else if($('cod_marco').value==''){
	        fun_msj('Debe seleccionar el marco metodol&oacute;gico');
			$('cod_marco').focus();
			return false;
	}else if($('cod_vision').value==''){
	        fun_msj('Debe seleccionar la visi&oacute;n compartida');
			$('cod_vision').focus();
			return false;
	}else if($('cod_equilibrio').value==''){
	        fun_msj('Seleccione agregar un nuevo equilibrio por favor');
			$('cod_equilibrio').focus();
			return false;
	}else if($('codigo_equilibrio').value==''){
	        fun_msj('Debe ingresar el c&oacute;digo del equilibrio');
			$('codigo_equilibrio').focus();
			return false;
	}else if($('descripcion_equilibrio').value==''){
	        fun_msj('Debe ingresar la descripci&oacute;n del equilibrio');
			$('descripcion_equilibrio').focus();
			return false;
	}else{
		var editor = capturar_contenido_editor_planmaestro();
		if(editor==1){
			fun_msj('Atencion: Debe especificar el equilibrio');
			return false;
		}
		var cont = $('control').value;
		cont = eval(cont)+eval(1);
		$('control').value = cont;
		$('control_editor').value = cont;
		return true;
	}
}

function validar_cpop01_planmaestro_equilibrio_mod(){
	if($('codigo_plan').value==''){
	        fun_msj('Debe seleccionar el alcance');
			$('codigo_plan').focus();
			return false;
	}else if($('codigo_introd').value==''){
	        fun_msj('Debe seleccionar la introducci&oacute;n');
			$('codigo_introd').focus();
			return false;
	}else if($('codigo_marco').value==''){
	        fun_msj('Debe seleccionar el marco metodol&oacute;gico');
			$('codigo_marco').focus();
			return false;
	}else if($('codigo_vision').value==''){
	        fun_msj('Debe seleccionar la visi&oacute;n compartida');
			$('codigo_vision').focus();
			return false;
	}else if($('codigo_equilibrio').value==''){
	        fun_msj('Debe ingresar el c&oacute;digo del equilibrio');
			$('codigo_equilibrio').focus();
			return false;
	}else if($('descripcion_equilibrio').value==''){
	        fun_msj('Debe ingresar la descripci&oacute;n del equilibrio');
			$('descripcion_equilibrio').focus();
			return false;
	}else{
		var editor = capturar_contenido_editor_planmaestro();
		if(editor==1){
			fun_msj('Atencion: Debe especificar el equilibrio');
			return false;
		}
		var cont = $('control').value;
		cont = eval(cont)+eval(1);
		$('control').value = cont;
		$('control_editor').value = cont;
		return true;
	}
}

function validar_cpop01_planmaestro_premisa(){
	if($('cod_plan').value==''){
	        fun_msj('Debe seleccionar el alcance');
			$('cod_plan').focus();
			return false;
	}else if($('cod_introd').value==''){
	        fun_msj('Debe seleccionar la introducci&oacute;n');
			$('cod_introd').focus();
			return false;
	}else if($('cod_marco').value==''){
	        fun_msj('Debe seleccionar el marco metodol&oacute;gico');
			$('cod_marco').focus();
			return false;
	}else if($('cod_vision').value==''){
	        fun_msj('Debe seleccionar la visi&oacute;n compartida');
			$('cod_vision').focus();
			return false;
	}else if($('cod_equilibrio').value==''){
	        fun_msj('Debe seleccionar el equilibrio');
			$('cod_equilibrio').focus();
			return false;
	}else if($('cod_premisa').value==''){
	        fun_msj('Seleccione agregar una nueva premisa por favor');
			$('cod_premisa').focus();
			return false;
	}else if($('codigo_premisa').value==''){
	        fun_msj('Debe ingresar el c&oacute;digo la premisa');
			$('codigo_premisa').focus();
			return false;
	}else if($('descripcion_premisa').value==''){
	        fun_msj('Debe ingresar la descripci&oacute;n de la premisa');
			$('descripcion_premisa').focus();
			return false;
	}else{
		var editor = capturar_contenido_editor_planmaestro();
		if(editor==1){
			fun_msj('Atencion: Debe especificar la premisa');
			return false;
		}
		var cont = $('control').value;
		cont = eval(cont)+eval(1);
		$('control').value = cont;
		$('control_editor').value = cont;
		return true;
	}
}

function validar_cpop01_planmaestro_premisa_mod(){
	if($('codigo_plan').value==''){
	        fun_msj('Debe seleccionar el alcance');
			$('codigo_plan').focus();
			return false;
	}else if($('codigo_introd').value==''){
	        fun_msj('Debe seleccionar la introducci&oacute;n');
			$('codigo_introd').focus();
			return false;
	}else if($('codigo_marco').value==''){
	        fun_msj('Debe seleccionar el marco metodol&oacute;gico');
			$('codigo_marco').focus();
			return false;
	}else if($('codigo_vision').value==''){
	        fun_msj('Debe seleccionar la visi&oacute;n compartida');
			$('codigo_vision').focus();
			return false;
	}else if($('codigo_equilibrio').value==''){
	        fun_msj('Debe seleccionar el equilibrio');
			$('codigo_equilibrio').focus();
			return false;
	}else if($('codigo_premisa').value==''){
	        fun_msj('Debe ingresar el c&oacute;digo la premisa');
			$('codigo_premisa').focus();
			return false;
	}else if($('descripcion_premisa').value==''){
	        fun_msj('Debe ingresar la descripci&oacute;n de la premisa');
			$('descripcion_premisa').focus();
			return false;
	}else{
		var editor = capturar_contenido_editor_planmaestro();
		if(editor==1){
			fun_msj('Atencion: Debe especificar la premisa');
			return false;
		}
		var cont = $('control').value;
		cont = eval(cont)+eval(1);
		$('control').value = cont;
		$('control_editor').value = cont;
		return true;
	}
}

function validar_cpop01_planmaestro_punto_premisa(){
	if($('cod_plan').value==''){
	        fun_msj('Debe seleccionar el alcance');
			$('cod_plan').focus();
			return false;
	}else if($('cod_introd').value==''){
	        fun_msj('Debe seleccionar la introducci&oacute;n');
			$('cod_introd').focus();
			return false;
	}else if($('cod_marco').value==''){
	        fun_msj('Debe seleccionar el marco metodol&oacute;gico');
			$('cod_marco').focus();
			return false;
	}else if($('cod_vision').value==''){
	        fun_msj('Debe seleccionar la visi&oacute;n compartida');
			$('cod_vision').focus();
			return false;
	}else if($('cod_equilibrio').value==''){
	        fun_msj('Debe seleccionar el equilibrio');
			$('cod_equilibrio').focus();
			return false;
	}else if($('cod_premisa').value==''){
	        fun_msj('Debe seleccionar la premisa');
			$('cod_premisa').focus();
			return false;
	}else if($('cod_punto_premisa').value==''){
	        fun_msj('Seleccione agregar un nuevo punto de premisa por favor');
			$('cod_premisa').focus();
			return false;
	}else if($('codigo_punto_premisa').value==''){
	        fun_msj('Debe ingresar el c&oacute;digo del punto de la premisa');
			$('codigo_punto_premisa').focus();
			return false;
	}else if($('descripcion_punto_premisa').value==''){
	        fun_msj('Debe ingresar la descripci&oacute;n del punto de la premisa');
			$('descripcion_punto_premisa').focus();
			return false;
	}else{
		var editor = capturar_contenido_editor_planmaestro();
		if(editor==1){
			fun_msj('Atencion: Debe especificar el punto de la premisa');
			return false;
		}
		var cont = $('control').value;
		cont = eval(cont)+eval(1);
		$('control').value = cont;
		$('control_editor').value = cont;
		return true;
	}
}


function validar_cpop01_planmaestro_punto_premisa_mod(){
	if($('codigo_plan').value==''){
	        fun_msj('Debe seleccionar el alcance');
			$('codigo_plan').focus();
			return false;
	}else if($('codigo_introd').value==''){
	        fun_msj('Debe seleccionar la introducci&oacute;n');
			$('codigo_introd').focus();
			return false;
	}else if($('codigo_marco').value==''){
	        fun_msj('Debe seleccionar el marco metodol&oacute;gico');
			$('codigo_marco').focus();
			return false;
	}else if($('codigo_vision').value==''){
	        fun_msj('Debe seleccionar la visi&oacute;n compartida');
			$('codigo_vision').focus();
			return false;
	}else if($('codigo_equilibrio').value==''){
	        fun_msj('Debe seleccionar el equilibrio');
			$('codigo_equilibrio').focus();
			return false;
	}else if($('codigo_premisa').value==''){
	        fun_msj('Debe seleccionar la premisa');
			$('codigo_premisa').focus();
			return false;
	}else if($('codigo_punto_premisa').value==''){
	        fun_msj('Debe ingresar el c&oacute;digo del punto de la premisa');
			$('codigo_punto_premisa').focus();
			return false;
	}else if($('descripcion_punto_premisa').value==''){
	        fun_msj('Debe ingresar la descripci&oacute;n del punto de la premisa');
			$('descripcion_punto_premisa').focus();
			return false;
	}else{
		var editor = capturar_contenido_editor_planmaestro();
		if(editor==1){
			fun_msj('Atencion: Debe especificar el punto de la premisa');
			return false;
		}
		var cont = $('control').value;
		cont = eval(cont)+eval(1);
		$('control').value = cont;
		$('control_editor').value = cont;
		return true;
	}
}

function validar_cpop01_planmaestro_diagnostico(){
	if($('cod_plan').value==''){
	        fun_msj('Debe seleccionar el alcance');
			$('cod_plan').focus();
			return false;
	}else if($('cod_introd').value==''){
	        fun_msj('Debe seleccionar la introducci&oacute;n');
			$('cod_introd').focus();
			return false;
	}else if($('cod_marco').value==''){
	        fun_msj('Debe seleccionar el marco metodol&oacute;gico');
			$('cod_marco').focus();
			return false;
	}else if($('cod_vision').value==''){
	        fun_msj('Debe seleccionar la visi&oacute;n compartida');
			$('cod_vision').focus();
			return false;
	}else if($('cod_equilibrio').value==''){
	        fun_msj('Debe seleccionar el equilibrio');
			$('cod_equilibrio').focus();
			return false;
	}else if($('cod_diagnostico').value==''){
	        fun_msj('Seleccione agregar un nuevo diagn&oacute;stico por favor');
			$('cod_diagnostico').focus();
			return false;
	}else if($('codigo_diagnostico').value==''){
	        fun_msj('Debe ingresar el c&oacute;digo del diagn&oacute;stico');
			$('codigo_diagnostico').focus();
			return false;
	}else if($('descripcion_diagnostico').value==''){
	        fun_msj('Debe ingresar el c&oacute;digo del diagn&oacute;stico');
			$('descripcion_diagnostico').focus();
			return false;
	}else{
		var editor = capturar_contenido_editor_planmaestro();
		if(editor==1){
			fun_msj('Atencion: Debe especificar el diagn&oacute;stico');
			return false;
		}
		var cont = $('control').value;
		cont = eval(cont)+eval(1);
		$('control').value = cont;
		$('control_editor').value = cont;
		return true;
	}
}

function validar_cpop01_planmaestro_diagnostico_mod(){
	if($('codigo_plan').value==''){
	        fun_msj('Debe seleccionar el alcance');
			$('codigo_plan').focus();
			return false;
	}else if($('codigo_introd').value==''){
	        fun_msj('Debe seleccionar la introducci&oacute;n');
			$('codigo_introd').focus();
			return false;
	}else if($('codigo_marco').value==''){
	        fun_msj('Debe seleccionar el marco metodol&oacute;gico');
			$('codigo_marco').focus();
			return false;
	}else if($('codigo_vision').value==''){
	        fun_msj('Debe seleccionar la visi&oacute;n compartida');
			$('codigo_vision').focus();
			return false;
	}else if($('codigo_equilibrio').value==''){
	        fun_msj('Debe seleccionar el equilibrio');
			$('codigo_equilibrio').focus();
			return false;
	}else if($('codigo_diagnostico').value==''){
	        fun_msj('Debe ingresar el c&oacute;digo del diagn&oacute;stico');
			$('codigo_diagnostico').focus();
			return false;
	}else if($('descripcion_diagnostico').value==''){
	        fun_msj('Debe ingresar el c&oacute;digo del diagn&oacute;stico');
			$('descripcion_diagnostico').focus();
			return false;
	}else{
		var editor = capturar_contenido_editor_planmaestro();
		if(editor==1){
			fun_msj('Atencion: Debe especificar el diagn&oacute;stico');
			return false;
		}
		var cont = $('control').value;
		cont = eval(cont)+eval(1);
		$('control').value = cont;
		$('control_editor').value = cont;
		return true;
	}
}


function validar_cpop01_planmaestro_puntos_diagnostico(){
	if($('cod_plan').value==''){
	        fun_msj('Debe seleccionar el alcance');
			$('cod_plan').focus();
			return false;
	}else if($('cod_introd').value==''){
	        fun_msj('Debe seleccionar la introducci&oacute;n');
			$('cod_introd').focus();
			return false;
	}else if($('cod_marco').value==''){
	        fun_msj('Debe seleccionar el marco metodol&oacute;gico');
			$('cod_marco').focus();
			return false;
	}else if($('cod_vision').value==''){
	        fun_msj('Debe seleccionar la visi&oacute;n compartida');
			$('cod_vision').focus();
			return false;
	}else if($('cod_equilibrio').value==''){
	        fun_msj('Debe seleccionar el equilibrio');
			$('cod_equilibrio').focus();
			return false;
	}else if($('cod_diagnostico').value==''){
	        fun_msj('Debe seleccionar un diagn&oacute;stico');
			$('cod_diagnostico').focus();
			return false;
	}else if($('cod_puntos_diagnostico').value==''){
	        fun_msj('Seleccione agregar un nuevo punto de diagn&oacute;stico por favor');
			$('cod_puntos_diagnostico').focus();
			return false;
	}else if($('codigo_puntos_diagnostico').value==''){
	        fun_msj('Debe ingresar el c&oacute;digo del punto de diagn&oacute;stico');
			$('codigo_puntos_diagnostico').focus();
			return false;
	}else if($('descripcion_puntos_diagnostico').value==''){
	        fun_msj('Debe ingresar la descripci&oacute;n del punto de diagn&oacute;stico');
			$('descripcion_puntos_diagnostico').focus();
			return false;
	}else{
		var editor = capturar_contenido_editor_planmaestro();
		if(editor==1){
			fun_msj('Atencion: Debe especificar el punto del diagn&oacute;stico');
			return false;
		}
		var cont = $('control').value;
		cont = eval(cont)+eval(1);
		$('control').value = cont;
		$('control_editor').value = cont;
		return true;
	}
}

function validar_cpop01_planmaestro_puntos_diagnostico_mod(){
	if($('codigo_plan').value==''){
	        fun_msj('Debe seleccionar el alcance');
			$('codigo_plan').focus();
			return false;
	}else if($('codigo_introd').value==''){
	        fun_msj('Debe seleccionar la introducci&oacute;n');
			$('codigo_introd').focus();
			return false;
	}else if($('codigo_marco').value==''){
	        fun_msj('Debe seleccionar el marco metodol&oacute;gico');
			$('codigo_marco').focus();
			return false;
	}else if($('codigo_vision').value==''){
	        fun_msj('Debe seleccionar la visi&oacute;n compartida');
			$('codigo_vision').focus();
			return false;
	}else if($('codigo_equilibrio').value==''){
	        fun_msj('Debe seleccionar el equilibrio');
			$('codigo_equilibrio').focus();
			return false;
	}else if($('codigo_diagnostico').value==''){
	        fun_msj('Debe seleccionar un diagn&oacute;stico');
			$('codigo_diagnostico').focus();
			return false;
	}else if($('codigo_puntos_diagnostico').value==''){
	        fun_msj('Debe ingresar el c&oacute;digo del punto de diagn&oacute;stico');
			$('codigo_puntos_diagnostico').focus();
			return false;
	}else if($('descripcion_puntos_diagnostico').value==''){
	        fun_msj('Debe ingresar la descripci&oacute;n del punto de diagn&oacute;stico');
			$('descripcion_puntos_diagnostico').focus();
			return false;
	}else{
		var editor = capturar_contenido_editor_planmaestro();
		if(editor==1){
			fun_msj('Atencion: Debe especificar el punto del diagn&oacute;stico');
			return false;
		}
		var cont = $('control').value;
		cont = eval(cont)+eval(1);
		$('control').value = cont;
		$('control_editor').value = cont;
		return true;
	}
}

function validar_cpop01_planmaestro_politicas_propuestas(){
	if($('cod_plan').value==''){
	        fun_msj('Debe seleccionar el alcance');
			$('cod_plan').focus();
			return false;
	}else if($('cod_introd').value==''){
	        fun_msj('Debe seleccionar la introducci&oacute;n');
			$('cod_introd').focus();
			return false;
	}else if($('cod_marco').value==''){
	        fun_msj('Debe seleccionar el marco metodol&oacute;gico');
			$('cod_marco').focus();
			return false;
	}else if($('cod_vision').value==''){
	        fun_msj('Debe seleccionar la visi&oacute;n compartida');
			$('cod_vision').focus();
			return false;
	}else if($('cod_equilibrio').value==''){
	        fun_msj('Debe seleccionar el equilibrio');
			$('cod_equilibrio').focus();
			return false;
	}else if($('cod_politicas_propuestas').value==''){
	        fun_msj('Seleccione agregar una nueva pol&iacute;tica/propuesta por favor');
			$('cod_politicas_propuestas').focus();
			return false;
	}else if($('codigo_politicas_propuestas').value==''){
	        fun_msj('Debe ingresar el c&oacute;digo la pol&iacute;tica/propuesta');
			$('codigo_politicas_propuestas').focus();
			return false;
	}else if($('descripcion_politicas_propuestas').value==''){
	        fun_msj('Debe ingresar la descripci&oacute;n de la pol&iacute;tica/propuesta');
			$('descripcion_politicas_propuestas').focus();
			return false;
	}else{
		var editor = capturar_contenido_editor_planmaestro();
		if(editor==1){
			fun_msj('Atencion: Debe especificar las pol&iacute;ticas/propuestas');
			return false;
		}
		var cont = $('control').value;
		cont = eval(cont)+eval(1);
		$('control').value = cont;
		$('control_editor').value = cont;
		return true;
	}
}

function validar_cpop01_planmaestro_politicas_propuestas_mod(){
	if($('codigo_plan').value==''){
	        fun_msj('Debe seleccionar el alcance');
			$('codigo_plan').focus();
			return false;
	}else if($('codigo_introd').value==''){
	        fun_msj('Debe seleccionar la introducci&oacute;n');
			$('codigo_introd').focus();
			return false;
	}else if($('codigo_marco').value==''){
	        fun_msj('Debe seleccionar el marco metodol&oacute;gico');
			$('codigo_marco').focus();
			return false;
	}else if($('codigo_vision').value==''){
	        fun_msj('Debe seleccionar la visi&oacute;n compartida');
			$('codigo_vision').focus();
			return false;
	}else if($('codigo_equilibrio').value==''){
	        fun_msj('Debe seleccionar el equilibrio');
			$('codigo_equilibrio').focus();
			return false;
	}else if($('codigo_politicas_propuestas').value==''){
	        fun_msj('Debe ingresar el c&oacute;digo la pol&iacute;tica/propuesta');
			$('codigo_politicas_propuestas').focus();
			return false;
	}else if($('descripcion_politicas_propuestas').value==''){
	        fun_msj('Debe ingresar la descripci&oacute;n de la pol&iacute;tica/propuesta');
			$('descripcion_politicas_propuestas').focus();
			return false;
	}else{
		var editor = capturar_contenido_editor_planmaestro();
		if(editor==1){
			fun_msj('Atencion: Debe especificar las pol&iacute;ticas/propuestas');
			return false;
		}
		var cont = $('control').value;
		cont = eval(cont)+eval(1);
		$('control').value = cont;
		$('control_editor').value = cont;
		return true;
	}
}

function validar_cpop01_planmaestro_politicas_propuestas_politicas(){
	if($('cod_plan').value==''){
	        fun_msj('Debe seleccionar el alcance');
			$('cod_plan').focus();
			return false;
	}else if($('cod_introd').value==''){
	        fun_msj('Debe seleccionar la introducci&oacute;n');
			$('cod_introd').focus();
			return false;
	}else if($('cod_marco').value==''){
	        fun_msj('Debe seleccionar el marco metodol&oacute;gico');
			$('cod_marco').focus();
			return false;
	}else if($('cod_vision').value==''){
	        fun_msj('Debe seleccionar la visi&oacute;n compartida');
			$('cod_vision').focus();
			return false;
	}else if($('cod_equilibrio').value==''){
	        fun_msj('Debe seleccionar el equilibrio');
			$('cod_equilibrio').focus();
			return false;
	}else if($('cod_politicas_propuestas').value==''){
	        fun_msj('Debe seleccionar la pol&iacute;tica/propuesta');
			$('cod_equilibrio').focus();
			return false;
	}else if($('cod_politica').value==''){
	        fun_msj('Seleccione agregar una nueva pol&iacute;tica por favor');
			$('cod_politica').focus();
			return false;
	}else if($('codigo_politica').value==''){
	        fun_msj('Debe ingresar el c&oacute;digo de la pol&iacute;tica');
			$('codigo_politica').focus();
			return false;
	}else if($('descripcion_politicas').value==''){
	        fun_msj('Debe ingresar la descripci&oacute;n de la pol&iacute;tica');
			$('descripcion_politicas').focus();
			return false;
	}else{
		var editor = capturar_contenido_editor_planmaestro();
		if(editor==1){
			fun_msj('Atencion: Debe especificar la pol&iacute;tica');
			return false;
		}
		var cont = $('control').value;
		cont = eval(cont)+eval(1);
		$('control').value = cont;
		$('control_editor').value = cont;
		return true;
	}
}

function validar_cpop01_planmaestro_politicas_propuestas_politicas_mod(){
	if($('codigo_plan').value==''){
	        fun_msj('Debe seleccionar el alcance');
			$('codigo_plan').focus();
			return false;
	}else if($('codigo_introd').value==''){
	        fun_msj('Debe seleccionar la introducci&oacute;n');
			$('codigo_introd').focus();
			return false;
	}else if($('codigo_marco').value==''){
	        fun_msj('Debe seleccionar el marco metodol&oacute;gico');
			$('codigo_marco').focus();
			return false;
	}else if($('codigo_vision').value==''){
	        fun_msj('Debe seleccionar la visi&oacute;n compartida');
			$('codigo_vision').focus();
			return false;
	}else if($('codigo_equilibrio').value==''){
	        fun_msj('Debe seleccionar el equilibrio');
			$('codigo_equilibrio').focus();
			return false;
	}else if($('codigo_politicas_propuestas').value==''){
	        fun_msj('Debe seleccionar la pol&iacute;tica/propuesta');
			$('codigo_equilibrio').focus();
			return false;
	}else if($('codigo_politica').value==''){
	        fun_msj('Debe ingresar el c&oacute;digo de la pol&iacute;tica');
			$('codigo_politica').focus();
			return false;
	}else if($('descripcion_politicas').value==''){
	        fun_msj('Debe ingresar la descripci&oacute;n de la pol&iacute;tica');
			$('descripcion_politicas').focus();
			return false;
	}else{
		var editor = capturar_contenido_editor_planmaestro();
		if(editor==1){
			fun_msj('Atencion: Debe especificar la pol&iacute;tica');
			return false;
		}
		var cont = $('control').value;
		cont = eval(cont)+eval(1);
		$('control').value = cont;
		$('control_editor').value = cont;
		return true;
	}
}

function validar_cpop01_planmaestro_politicas_propuestas_propuestas(){
	if($('cod_plan').value==''){
	        fun_msj('Debe seleccionar el alcance');
			$('cod_plan').focus();
			return false;
	}else if($('cod_introd').value==''){
	        fun_msj('Debe seleccionar la introducci&oacute;n');
			$('cod_introd').focus();
			return false;
	}else if($('cod_marco').value==''){
	        fun_msj('Debe seleccionar el marco metodol&oacute;gico');
			$('cod_marco').focus();
			return false;
	}else if($('cod_vision').value==''){
	        fun_msj('Debe seleccionar la visi&oacute;n compartida');
			$('cod_vision').focus();
			return false;
	}else if($('cod_equilibrio').value==''){
	        fun_msj('Debe seleccionar el equilibrio');
			$('cod_equilibrio').focus();
			return false;
	}else if($('cod_politicas_propuestas').value==''){
	        fun_msj('Debe seleccionar la pol&iacute;tica/propuesta');
			$('cod_equilibrio').focus();
			return false;
	}else if($('cod_propuestas').value==''){
	        fun_msj('Seleccione agregar una nueva propuesta por favor');
			$('cod_propuestas').focus();
			return false;
	}else if($('codigo_propuestas').value==''){
	        fun_msj('Debe ingresar el c&oacute;digo de la propuesta');
			$('codigo_propuestas').focus();
			return false;
	}else if($('descripcion_propuestas').value==''){
	        fun_msj('Debe ingresar la descripci&oacute;n de la propuesta');
			$('descripcion_propuestas').focus();
			return false;
	}else{
		var editor = capturar_contenido_editor_planmaestro();
		if(editor==1){
			fun_msj('Atencion: Debe especificar la propuesta');
			return false;
		}
		var cont = $('control').value;
		cont = eval(cont)+eval(1);
		$('control').value = cont;
		$('control_editor').value = cont;
		return true;
	}
}

function validar_cpop01_planmaestro_politicas_propuestas_propuestas_mod(){
	if($('codigo_plan').value==''){
	        fun_msj('Debe seleccionar el alcance');
			$('codigo_plan').focus();
			return false;
	}else if($('codigo_introd').value==''){
	        fun_msj('Debe seleccionar la introducci&oacute;n');
			$('codigo_introd').focus();
			return false;
	}else if($('codigo_marco').value==''){
	        fun_msj('Debe seleccionar el marco metodol&oacute;gico');
			$('codigo_marco').focus();
			return false;
	}else if($('codigo_vision').value==''){
	        fun_msj('Debe seleccionar la visi&oacute;n compartida');
			$('codigo_vision').focus();
			return false;
	}else if($('codigo_equilibrio').value==''){
	        fun_msj('Debe seleccionar el equilibrio');
			$('codigo_equilibrio').focus();
			return false;
	}else if($('codigo_politicas_propuestas').value==''){
	        fun_msj('Debe seleccionar la pol&iacute;tica/propuesta');
			$('codigo_equilibrio').focus();
			return false;
	}else if($('codigo_propuestas').value==''){
	        fun_msj('Debe ingresar el c&oacute;digo de la propuesta');
			$('codigo_propuestas').focus();
			return false;
	}else if($('descripcion_propuestas').value==''){
	        fun_msj('Debe ingresar la descripci&oacute;n de la propuesta');
			$('descripcion_propuestas').focus();
			return false;
	}else{
		var editor = capturar_contenido_editor_planmaestro();
		if(editor==1){
			fun_msj('Atencion: Debe especificar la propuesta');
			return false;
		}
		var cont = $('control').value;
		cont = eval(cont)+eval(1);
		$('control').value = cont;
		$('control_editor').value = cont;
		return true;
	}
}

function oculta_mensaje_fckeditor (kpa) {
   var capa = $(kpa);
   capa.style.display="none";
   //setTimeout(capa.style.display="none",5000);
}
function muestra_mensaje_fckeditor (kpa) {
   document.getElementById(kpa).style.visibility="visible";
}



//FUNCIONES DEL PLAN DE GOBIERNO


function validar_cpop02_plangobierno_alcance(){
	if($('codigo_plan').value==''){
	        fun_msj('Debe ingresar el c&oacute;digo del alcance');
			$('codigo_plan').focus();
			return false;
	}else if($('descripcion').value==''){
	        fun_msj('Debe ingresar la descripci&oacute;n del alcance');
			$('descripcion').focus();
			return false;
	}else if($('ano_desde').value==''){
	        fun_msj('Debe ingresar el a&ntilde;o desde');
			$('ano_desde').focus();
			return false;
	}else if($('ano_desde').value.length<4){
			fun_msj('El a&ntilde;o desde debe contener cuatro digitos');
			$('ano_desde').focus();
			return false;
	}else if($('ano_hasta').value==''){
			fun_msj('Debe ingresar el a&ntilde;o hasta');
			$('ano_hasta').focus();
			return false;
	}else if($('ano_hasta').value.length<4){
			fun_msj('El a&ntilde;o hasta debe contener cuatro digitos');
			$('ano_hasta').focus();
			return false;
	}else{
		var editor = capturar_contenido_editor_planmaestro();
		if(editor==1){
			fun_msj('Atencion: Debe especificar la mision, vision regional e institucional entre otras');
			return false;
		}
		var cont = $('control').value;
		cont = eval(cont)+eval(1);
		$('control').value = cont;
		$('control_editor').value = cont;
		return true;
	}
}


function validar_cpop02_plangobierno_estructura(){
	if($('cod_plan').value==''){
	        fun_msj('Debe seleccionar el alcance del plan de gobierno');
			$('cod_plan').focus();
			return false;
	}else if($('codigo_estructura').value==''){
	        fun_msj('Debe ingresar el c&oacute;digo de la estructura');
			$('codigo_estructura').focus();
			return false;
	}else if($('denominacion_estructura').value==''){
	        fun_msj('Debe ingresar la denominaci&oacute;n de la estructura');
			$('denominacion_estructura').focus();
			return false;
	}else if($('responsable_estructura').value.length<4){
			fun_msj('Ingrese el responsable de la estructura');
			$('responsable_estructura').focus();
			return false;
	}
}


function validar_cpop02_plangobierno_estrategias(){

	if($('codigo_plan').value==''){
	        fun_msj('Debe seleccionar el alcance del plan de gobierno');
			$('cod_plan').focus();
			return false;
	}else if($('codigo_estructura').value==''){
	        fun_msj('Debe seleccionar la estructura del plan de gobierno');
			$('cod_estructura').focus();
			return false;
	}else if($('codigo_estrategia').value==''){
	        fun_msj('Debe ingresar el c&oacute;digo de la estrategia');
			$('codigo_estrategia').focus();
			return false;
	}else if($('denominacion_estrategia').value==''){
	        fun_msj('Debe ingresar la denominaci&oacute;n de la estrategia');
			$('denominacion_estrategia').focus();
			return false;
	}else if($('cod_plan_maestro').value==''){
	        fun_msj('Debe seleccionar el alcance');
			$('cod_plan_maestro').focus();
			return false;
	}else if($('codigo_introd').value==''){
	        fun_msj('Debe seleccionar la introducci&oacute;n');
			$('codigo_introd').focus();
			return false;
	}else if($('codigo_marco').value==''){
	        fun_msj('Debe seleccionar el marco metodol&oacute;gico');
			$('codigo_marco').focus();
			return false;
	}else if($('codigo_vision').value==''){
	        fun_msj('Debe seleccionar la visi&oacute;n compartida');
			$('codigo_vision').focus();
			return false;
	}else if($('codigo_equilibrio').value==''){
	        fun_msj('Debe seleccionar el equilibrio');
			$('codigo_equilibrio').focus();
			return false;
	}

}

function validar_modificacion_cpop02_plangobierno_estrategias(){

	if($('codigo_plan').value==''){
	        fun_msj('Debe seleccionar el alcance del plan de gobierno');
			$('cod_plan').focus();
			return false;
	}else if($('codigo_estructura').value==''){
	        fun_msj('Debe seleccionar la estructura del plan de gobierno');
			$('cod_estructura').focus();
			return false;
	}else if($('codigo_estrategia').value==''){
	        fun_msj('Debe ingresar el c&oacute;digo de la estrategia');
			$('codigo_estrategia').focus();
			return false;
	}else if($('denominacion_estrategia').value==''){
	        fun_msj('Debe ingresar la denominaci&oacute;n de la estrategia');
			$('denominacion_estrategia').focus();
			return false;
	}else if($('codigo_plan_maestro').value==''){
	        fun_msj('Debe seleccionar el alcance');
			$('cod_plan_maestro').focus();
			return false;
	}else if($('codigo_introd').value==''){
	        fun_msj('Debe seleccionar la introducci&oacute;n');
			$('cod_introd').focus();
			return false;
	}else if($('codigo_marco').value==''){
	        fun_msj('Debe seleccionar el marco metodol&oacute;gico');
			$('cod_marco').focus();
			return false;
	}else if($('codigo_vision').value==''){
	        fun_msj('Debe seleccionar la visi&oacute;n compartida');
			$('cod_vision').focus();
			return false;
	}else if($('codigo_equilibrio').value==''){
	        fun_msj('Debe seleccionar el equilibrio');
			$('cod_equilibrio').focus();
			return false;
	}

}

function validar_cpop00_tipo_beneficiario(){
	if($('cod_tipo').value==''){
	        fun_msj('Debe haberse generado el c&oacute;digo del tipo automaticamente, revise por favor');
			$('cod_tipo').focus();
			return false;
	}else if($('denominacion').value==''){
	        fun_msj('Debe ingresar la denominaci&oacute;n del tipo de beneficiario');
			$('denominacion').focus();
			return false;
	}
}

function validar_cpop00_unidad_medida(){
	if($('cod_unidad').value==''){
	        fun_msj('Debe haberse generado el c&oacute;digo de la unidad automaticamente, revise por favor');
			$('cod_unidad').focus();
			return false;
	}else if($('denominacion').value==''){
	        fun_msj('Debe ingresar la denominaci&oacute;n de la unidad de medida');
			$('denominacion').focus();
			return false;
	}
}

function validar_cpop03_filosofia_gestion(){

	if($('codigo_actor').value==''){
	        fun_msj('Debe seleccionar para agregar un c&oacute;digo de filosof&iacute;a');
			$('codigo_actor').focus();
			return false;
	}else if($('denominacion_actor').value==''){
	        fun_msj('Debe ingresar la denominaci&oacute;n del actor responsable');
			$('denominacion_actor').focus();
			return false;
	}else if($('siglas').value==''){
	        fun_msj('Debe ingresar las siglas');
			$('siglas').focus();
			return false;
	}else if($('mision').value==''){
	        fun_msj('Debe ingresar la misi&oacute;n');
			$('mision').focus();
			return false;
	}else if($('vision').value==''){
	        fun_msj('Debe ingresar la visi&oacute;n');
			$('vision').focus();
			return false;
	}else if($('objetivos').value==''){
	        fun_msj('Debe ingresar los objetivos');
			$('objetivos').focus();
			return false;
	}else if($('datos_generales').value==''){
	        fun_msj('Ingrese los datos generales');
			$('datos_generales').focus();
			return false;
	}
}

function validar_cpod03_marco_estrategico(){
	if($('cod_actor').value==''){
	        fun_msj('Debe seleccionar el actor responsable');
			$('cod_actor').focus();
			return false;
	}else if($('codigo_area_estrategica').value==''){
	        fun_msj('Debe agregar el c&oacute;digo del &aacute;rea estrat&eacute;gica');
			$('codigo_area_estrategica').focus();
			return false;
	}else if($('denominacion_area_estrategica').value==''){
	        fun_msj('Debe ingresar la denominaci&oacute;n del &aacute;rea estrat&eacute;gica');
			$('denominacion_area_estrategica').focus();
			return false;
	}else if($('directriz_estrategica').value==''){
	        fun_msj('Debe ingresar la directriz del &aacute;rea estrat&eacute;gica');
			$('directriz_estrategica').focus();
			return false;
	}else if($('cod_plan').value==''){
	        fun_msj('Debe seleccionar el alcance del plan de gobierno');
			$('cod_plan').focus();
			return false;
	}else if($('cod_estructura').value==''){
	        fun_msj('Debe seleccionar la estructura del plan de gobierno');
			$('cod_estructura').focus();
			return false;
	}else if($('cod_estrategia').value==''){
	        fun_msj('Debe seleccionar el c&oacute;digo de la estrategia');
			$('cod_estrategia').focus();
			return false;
	}
	/*
	else if($('cod_plan_maestro').value==''){
	        fun_msj('Debe seleccionar el alcance');
			$('cod_plan_maestro').focus();
			return false;
	}else if($('codigo_introd').value==''){
	        fun_msj('Debe seleccionar la introducci&oacute;n');
			$('codigo_introd').focus();
			return false;
	}else if($('codigo_marco').value==''){
	        fun_msj('Debe seleccionar el marco metodol&oacute;gico');
			$('codigo_marco').focus();
			return false;
	}else if($('codigo_vision').value==''){
	        fun_msj('Debe seleccionar la visi&oacute;n compartida');
			$('codigo_vision').focus();
			return false;
	}else if($('codigo_equilibrio').value==''){
	        fun_msj('Debe seleccionar el equilibrio');
			$('codigo_equilibrio').focus();
			return false;
	}
	*/
}

function validar_modificacion_cpod03_marco_estrategico(){
	if($('codigo_actor').value==''){
	        fun_msj('Debe seleccionar el actor responsable');
			$('cod_actor').focus();
			return false;
	}else if($('codigo_area_estrategica').value==''){
	        fun_msj('Debe agregar el codigo del &aacute;rea estrategica');
			$('codigo_area_estrategica').focus();
			return false;
	}else if($('denominacion_area_estrategica').value==''){
	        fun_msj('Debe ingresar la denominaci&oacute;n del area estrat&eacute;gica');
			$('denominacion_area_estrategica').focus();
			return false;
	}else if($('directriz_estrategica').value==''){
	        fun_msj('Debe ingresar la directriz del &aacute;rea estrat&eacute;gica');
			$('directriz_estrategica').focus();
			return false;
	}else if($('codigo_plan').value==''){
	        fun_msj('Debe seleccionar el alcance del plan de gobierno');
			$('cod_plan').focus();
			return false;
	}else if($('codigo_estructura').value==''){
	        fun_msj('Debe seleccionar la estructura del plan de gobierno');
			$('cod_estructura').focus();
			return false;
	}else if($('codigo_estrategia').value==''){
	        fun_msj('Debe seleccionar el c&oacute;digo de la estrategia');
			$('cod_estrategia').focus();
			return false;
	}else if($('codigo_plan_maestro').value==''){
	        fun_msj('Debe seleccionar el alcance');
			$('cod_plan_maestro').focus();
			return false;
	}else if($('codigo_introd').value==''){
	        fun_msj('Debe seleccionar la introducci&oacute;n');
			$('codigo_introd').focus();
			return false;
	}else if($('codigo_marco').value==''){
	        fun_msj('Debe seleccionar el marco metodol&oacute;gico');
			$('codigo_marco').focus();
			return false;
	}else if($('codigo_vision').value==''){
	        fun_msj('Debe seleccionar la visi&oacute;n compartida');
			$('codigo_vision').focus();
			return false;
	}else if($('codigo_equilibrio').value==''){
	        fun_msj('Debe seleccionar el equilibrio');
			$('codigo_equilibrio').focus();
			return false;
	}
}

function validar_cpop03_vinculacion_plan_nacional(){
	if($('cod_actor').value==''){
	        fun_msj('Debe seleccionar el actor responsable');
			$('cod_actor').focus();
			return false;
	}else if($('cod_area_estrategica').value==''){
	        fun_msj('Debe seleccionar el &aacute;area estrat&eacute;gica');
			$('cod_area_estrategica').focus();
			return false;
	}else if($('cod_area_estrategica').value==''){
	        fun_msj('Debe seleccionar el &aacute;rea estrat&eacute;gica');
			$('cod_area_estrategica').focus();
			return false;
	}else if($('cod_objetivo_nacional').value==''){
	        fun_msj('Verifique que se genero el c&oacute;digo del objetivo nacional correctamente');
			$('cod_objetivo_nacional').focus();
			return false;
	}else if($('descripcion').value==''){
	        fun_msj('Debe ingresar la descripci&oacute;n de los objetivos, estrat&eacute;gias y pol&iacute;ticas');
			$('descripcion').focus();
			return false;
	}
}

function validar_cpop03_planificacion_programatica_anual(){
	if($('cod_actor').value==''){
	        fun_msj('Debe seleccionar el actor responsable');
			$('cod_actor').focus();
			return false;
	}else if($('cod_area_estrategica').value==''){
	        fun_msj('Debe seleccionar el &aacute;rea estrategica');
			$('cod_area_estrategica').focus();
			return false;
	}else if($('cod_objetivo_nacional').value==''){
	        fun_msj('Debe seleccionar el objetivo nacional');
			$('cod_objetivo_nacional').focus();
			return false;
	}else if($('cod_programa_proyecto').value==''){
	        fun_msj('Debe ingresar el c&oacute;digo del programa o proyecto');
			$('cod_programa_proyecto').focus();
			return false;
	}else if($('denominacion').value==''){
	        fun_msj('Debe ingresar la denominaci&oacute;n del programa o proyecto');
			$('denominacion').focus();
			return false;
	}else if($('objetivo').value==''){
	        fun_msj('Debe ingresar el objetivo del programa o proyecto');
			$('objetivo').focus();
			return false;
	}
}

function validar_cpop03_control_metas(){
	if($('cod_actor').value==''){
	        fun_msj('Debe seleccionar el actor responsable');
			$('cod_actor').focus();
			return false;
	}else if($('cod_area_estrategica').value==''){
	        fun_msj('Debe seleccionar el &aacute;rea estrategica');
			$('cod_area_estrategica').focus();
			return false;
	}else if($('cod_objetivo_nacional').value==''){
	        fun_msj('Debe seleccionar el objetivo nacional');
			$('cod_objetivo_nacional').focus();
			return false;
	}else if($('cod_programa_proyecto').value==''){
	        fun_msj('Debe seleccionar el codigo del programa o proyecto');
			$('cod_programa_proyecto').focus();
			return false;
	}else if($('codigo_meta').value==''){
	        fun_msj('Verifique que se genero el c&oacute;digo de la meta automaticamente');
			$('codigo_meta').focus();
			return false;
	}else if($('descripcion_meta').value==''){
	        fun_msj('Ingrese la descripci&oacute;n de la meta');
			$('descripcion_meta').focus();
			return false;
	}else if($('costo_asignado').value==''){
	        fun_msj('Ingrese el costo asignado');
			$('costo_asignado').focus();
			return false;
	}else if($('acciones_ejecutar').value==''){
	        fun_msj('Ingrese las acciones a ejecutar');
			$('acciones_ejecutar').focus();
			return false;
	}else if($('productos_generados').value==''){
	        fun_msj('Ingrese los productos esperados');
			$('productos_generados').focus();
			return false;
	}else if($('cod_unidad').value==''){
	        fun_msj('Selecciones la unidad de medida');
			$('cod_unidad').focus();
			return false;
	}else if($('cod_tipo_beneficiario').value==''){
	        fun_msj('Seleccione el tipo de beneficiario');
			$('cod_tipo_beneficiario').focus();
			return false;
	}else if($('numero_beneficiarios').value==''){
	        fun_msj('Ingrese el total a atender');
			$('numero_beneficiarios').focus();
			return false;
	}else if($('cod_estado').value==''){
	        fun_msj('Seleccione el estado');
			$('cod_estado').focus();
			return false;
	}else if($('cod_municipio').value==''){
	        fun_msj('Seleccione el municipio');
			$('cod_municipio').focus();
			return false;
	}else if($('indicador_resultados').value==''){
	        fun_msj('Ingrese los indicadores de resultados');
			$('indicador_resultados').focus();
			return false;
	}
}


function validar_modificacion_cpop03_control_metas(){
	if($('descripcion_meta').value==''){
	        fun_msj('Ingrese la descripci&oacute;n de la meta');
			$('descripcion_meta').focus();
			return false;
	}else if($('costo_asignado').value==''){
	        fun_msj('Ingrese el costo asignado');
			$('costo_asignado').focus();
			return false;
	}else if($('acciones_ejecutar').value==''){
	        fun_msj('Ingrese las acciones a ejecutar');
			$('acciones_ejecutar').focus();
			return false;
	}else if($('productos_generados').value==''){
	        fun_msj('Ingrese los productos esperados');
			$('productos_generados').focus();
			return false;
	}else if($('codigo_unidad').value==''){
	        fun_msj('Selecciones la unidad de medida');
			$('cod_unidad').focus();
			return false;
	}else if($('codigo_tipo_beneficiario').value==''){
	        fun_msj('Seleccione el tipo de beneficiario');
			$('cod_tipo_beneficiario').focus();
			return false;
	}else if($('numero_beneficiarios').value==''){
	        fun_msj('Ingrese el n&uacute;mero de beneficiarios');
			$('numero_beneficiarios').focus();
			return false;
	}else if($('codigo_estado').value==''){
	        fun_msj('Selccione el estado');
			$('cod_estado').focus();
			return false;
	}else if($('codigo_municipio').value==''){
	        fun_msj('Seleccione el municipio');
			$('cod_municipio').focus();
			return false;
	}else if($('indicador_resultados').value==''){
	        fun_msj('Ingrese los indicadores de resultados');
			$('indicador_resultados').focus();
			return false;
	}
}


function cpop03_control_metas_partidas(){
	if($('cod_actor').value==''){
	        fun_msj('Debe seleccionar el actor responsable');
			$('cod_actor').focus();
			return false;
	}else if($('cod_area_estrategica').value==''){
	        fun_msj('Debe seleccionar el &aacute;rea estrategica');
			$('cod_area_estrategica').focus();
			return false;
	}else if($('cod_objetivo_nacional').value==''){
	        fun_msj('Debe seleccionar el objetivo nacional');
			$('cod_objetivo_nacional').focus();
			return false;
	}else if($('cod_programa_proyecto').value==''){
	        fun_msj('Debe seleccionar el c&oacute;digo del programa o proyecto');
			$('cod_programa_proyecto').focus();
			return false;
	}else if($('cod_meta').value==''){
	        fun_msj('Debe seleccionar el c&oacute;digo de la meta');
			$('cod_meta').focus();
			return false;
	}else if((document.getElementById('tipo_gasto_1').checked==false) && (document.getElementById('tipo_gasto_2').checked==false) && (document.getElementById('tipo_gasto_3').checked==false) && (document.getElementById('tipo_gasto_4').checked==false)){
		fun_msj('DEBE INDICAR EL TIPO DE GASTO');
		return false;
	}else if((document.getElementById('tipo_recurso_1').checked==false) && (document.getElementById('tipo_recurso_2').checked==false) && (document.getElementById('tipo_recurso_3').checked==false) && (document.getElementById('tipo_recurso_4').checked==false) && (document.getElementById('tipo_recurso_5').checked==false) && (document.getElementById('tipo_recurso_6').checked==false)){
		fun_msj('DEBE INDICAR EL TIPO DE RECURSO');
		return false;
	}else if(document.getElementById('select_1').value==''){
			fun_msj('Seleccione Sector');
			document.getElementById('select_1').focus();
			return false;
	}else if(document.getElementById('select_2').value == ""){
				fun_msj('Seleccione Programa');
				document.getElementById('select_2').focus();
				return false;
	}else if(document.getElementById('select_3').value == ""){
				fun_msj('Seleccione Sub Programa');
				document.getElementById('select_3').focus();
				return false;
	}else if(document.getElementById('select_4').value == ""){
				fun_msj('Seleccione Proyecto');
				document.getElementById('select_4').focus();
				return false;
	}else if(document.getElementById('select_5').value == ""){
				fun_msj('Seleccione Actividad u Obra');
				document.getElementById('select_5').focus();
				return false;
	}else if(document.getElementById('select_6').value == ""){
				fun_msj('Seleccione Partida');
				document.getElementById('select_6').focus();
				return false;
	}else if(document.getElementById('select_7').value == ""){
				fun_msj('Seleccione Generica');
				document.getElementById('select_7').focus();
				return false;
	}else if(document.getElementById('select_8').value == ""){
				fun_msj('Seleccione Especifica');
				document.getElementById('select_8').focus();
				return false;
	}else if(document.getElementById('select_9').value == ""){
				fun_msj('Seleccione Sub-Especifica');
				document.getElementById('select_9').focus();
				return false;
	}else if(document.getElementById('select_10').value == "" && document.getElementById('select_10').length >=1){
				fun_msj('Seleccione Auxiliar');
				document.getElementById('select_10').focus();
				return false;
	}else if(document.getElementById('monto').value==''){
			fun_msj('Inserte un monto correcto');
			document.getElementById('monto').focus();
			return false;
	}else{
		a = document.getElementById('monto').value;
		var str = a;
		for(i=0; i<a.length; i++){str = str.replace('.','');}//fin for
		str   = str.replace(',','.');
		var a = redondear(str,2);

		b = document.getElementById('costo_asignado').value;
		var str = b;
		for(i=0; i<b.length; i++){str = str.replace('.','');}//fin for
		str   = str.replace(',','.');
		var b = redondear(str,2);

		if(a > b){
	        fun_msj('El monto de la partida supera el costo total de la meta');
			document.getElementById('monto').focus();
			return false;
		}

		c = document.getElementById('monto_partidas').value;
		var str = c;
		for(i=0; i<c.length; i++){str = str.replace('.','');}//fin for
		str   = str.replace(',','.');
		var c = redondear(str,2);

		d = eval(a) + eval(c);

		if(d > b){
	        fun_msj('Si agrega el monto de esta partida superar&aacute; el costo total de la meta');
			document.getElementById('monto').focus();
			return false;
		}
	}
}//fin funcion



function mostrar_select_actor(){

	capa = document.getElementById('capaactor');
	if(document.getElementById('radio_actor_1').checked==true){
		capa.style.display = "none";
		//document.getElementById('cod_actor').disabled=true;
	}else if(document.getElementById('radio_actor_2').checked==true){
		capa.style.display = "";
		//document.getElementById('cod_actor').disabled=false;
		//fun_msj2('Seleccione el actor');
	}
}
