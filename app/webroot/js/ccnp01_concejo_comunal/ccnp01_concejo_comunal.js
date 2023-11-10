function calcular_porcentaje_comunal(){

a = document.getElementById('num_votantes').value;
b = document.getElementById('resultado').value;


    if(a!="" && b!=""){


         c = eval((eval(b)/eval(a)) * 100);

        document.getElementById('porcentaje').value = redondear(c, 2);

        moneda('porcentaje');

    }//fin if


}//fin function




function ver_documento_pestana_concejos_1(url_busqueda,ID){


document.getElementById('tab_pestana_1').innerHTML="";
document.getElementById('tab_pestana_2').innerHTML="";
document.getElementById('tab_pestana_3').innerHTML="";
document.getElementById('tab_pestana_4').innerHTML="";
document.getElementById('tab_pestana_5').innerHTML="";
document.getElementById('tab_pestana_6').innerHTML="";

ver_documento(url_busqueda,ID);


}//fin function








function ver_documento_pestana_concejos_descripcion_proyecto_1(url_busqueda,ID){


document.getElementById('tab_pestana_descripcion_proyecto_1').innerHTML="";
document.getElementById('tab_pestana_descripcion_proyecto_2').innerHTML="";
document.getElementById('tab_pestana_descripcion_proyecto_3').innerHTML="";
document.getElementById('tab_pestana_descripcion_proyecto_4').innerHTML="";
document.getElementById('tab_pestana_descripcion_proyecto_5').innerHTML="";
document.getElementById('tab_pestana_descripcion_proyecto_6').innerHTML="";
//document.getElementById('tab_pestana_descripcion_proyecto_7').innerHTML="";

ver_documento(url_busqueda,ID);


}//fin function


function recursos_necesarios_comunales1(){
	if(document.getElementById('descripcion_equipo').value==''){
		fun_msj('debe ingresar la descripci&oacute;n del equipo');
		document.getElementById('descripcion_equipo').focus();
		return false;
	}else if(document.getElementById('costo_equipo').value==''){
		fun_msj('debe ingresar el costo del equipo');
		document.getElementById('costo_equipo').focus();
		return false;
	}

}

function recursos_necesarios_comunales2(){
	if(document.getElementById('descripcion_material').value==''){
		fun_msj('debe ingresar la descripci&oacute;n del material');
		document.getElementById('descripcion_material').focus();
		return false;
	}else if(document.getElementById('costo_material').value==''){
		fun_msj('debe ingresar el costo del material');
		document.getElementById('costo_material').focus();
		return false;
	}

}

function recursos_necesarios_comunales3(){
	if(document.getElementById('descripcion_obra').value==''){
		fun_msj('debe ingresar la descripci&oacute;n de la mano de obra');
		document.getElementById('descripcion_obra').focus();
		return false;
	}else if(document.getElementById('costo_obra').value==''){
		fun_msj('debe ingresar el costo de la mano de obra');
		document.getElementById('costo_obra').focus();
		return false;
	}

}



function verfica_donde_trabaja(){

	if($('donde_trabaja6').checked==true){
		$('otro_tipo_trabajo').value='';
		$('otro_tipo_trabajo').disabled=false;
	}else{
		$('otro_tipo_trabajo').value='';
		$('otro_tipo_trabajo').disabled='disabled';
	}

}


function verfica_actividad_comercial(){

	if($('actividad_comercial_vivienda2').checked==true){
		$('venta_de1').checked=false;
		$('venta_de2').checked=false;
		$('venta_de3').checked=false;
		$('venta_de4').checked=false;
		$('venta_de5').checked=false;
		$('venta_de6').checked=false;
		$('venta_de7').checked=false;
		$('venta_de8').checked=false;
		$('venta_de1').disabled='disabled';
		$('venta_de2').disabled='disabled';
		$('venta_de3').disabled='disabled';
		$('venta_de4').disabled='disabled';
		$('venta_de5').disabled='disabled';
		$('venta_de6').disabled='disabled';
		$('venta_de7').disabled='disabled';
		$('venta_de8').disabled='disabled';
	}else{
		$('venta_de1').disabled=false;
		$('venta_de2').disabled=false;
		$('venta_de3').disabled=false;
		$('venta_de4').disabled=false;
		$('venta_de5').disabled=false;
		$('venta_de6').disabled=false;
		$('venta_de7').disabled=false;
		$('venta_de8').disabled=false;
	}

}


function verfica_insectos(){

	if($('presencia_insectos2').checked==true){
		$('insectos1').checked=false;
		$('insectos2').checked=false;
		$('insectos3').checked=false;
		$('insectos4').checked=false;
		$('insectos5').checked=false;
		$('insectos6').checked=false;
		$('insectos1').disabled='disabled';
		$('insectos2').disabled='disabled';
		$('insectos3').disabled='disabled';
		$('insectos4').disabled='disabled';
		$('insectos5').disabled='disabled';
		$('insectos6').disabled='disabled';
	}else{
		$('insectos1').disabled=false;
		$('insectos2').disabled=false;
		$('insectos3').disabled=false;
		$('insectos4').disabled=false;
		$('insectos5').disabled=false;
		$('insectos6').disabled=false;
	}

}


function verfica_animales(){

	if($('animales_domesticos2').checked==true){
		$('animales1').checked=false;
		$('animales2').checked=false;
		$('animales3').checked=false;
		$('animales4').checked=false;
		$('animales5').checked=false;
		$('animales6').checked=false;
		$('animales7').checked=false;
		$('animales1').disabled='disabled';
		$('animales2').disabled='disabled';
		$('animales3').disabled='disabled';
		$('animales4').disabled='disabled';
		$('animales5').disabled='disabled';
		$('animales6').disabled='disabled';
		$('animales7').disabled='disabled';
	}else{
		$('animales1').disabled=false;
		$('animales2').disabled=false;
		$('animales3').disabled=false;
		$('animales4').disabled=false;
		$('animales5').disabled=false;
		$('animales6').disabled=false;
		$('animales7').disabled=false;
	}

}

function verfica_necesita_ayuda(){

	if($('requeriere_ayuda_especial1').checked==true){
		$('cual_ayuda_especial').value='';
		$('cual_ayuda_especial').disabled=false;
	}else{
		$('cual_ayuda_especial').value='';
		$('cual_ayuda_especial').disabled='disabled';
	}

}


function verfica_organizacion_comunitaria(){

	if($('organizaciones_comunitarias1').checked==true){
		$('participa_organizacion_comunitaria1').checked=false;
		$('participa_organizacion_comunitaria2').checked=false;
		$('participa_organizacion_comunitaria1').disabled=false;
		$('participa_organizacion_comunitaria2').disabled=false;

		$('participa_organizacion_comunitaria_miembro1').checked=false;
		$('participa_organizacion_comunitaria_miembro2').checked=false;
		$('participa_organizacion_comunitaria_miembro1').disabled=false;
		$('participa_organizacion_comunitaria_miembro2').disabled=false;


	}else{
		$('participa_organizacion_comunitaria1').checked=false;
		$('participa_organizacion_comunitaria2').checked=false;
		$('participa_organizacion_comunitaria1').disabled='disabled';
		$('participa_organizacion_comunitaria2').disabled='disabled';

		$('participa_organizacion_comunitaria_miembro1').checked=false;
		$('participa_organizacion_comunitaria_miembro2').checked=false;
		$('participa_organizacion_comunitaria_miembro1').disabled='disabled';
		$('participa_organizacion_comunitaria_miembro2').disabled='disabled';


	}

}

	function verfica_cuales_otras_misiones(){
		if($('cuales_misiones8').checked==true){
//			$('cual_otra_mision').value='';
			$('cual_otra_mision').disabled=false;
		}else{
			$('cual_otra_mision').value='';
			$('cual_otra_mision').disabled='disabled';
		}

	}




	function verfica_creacion_consejo(){
		if($('informacion_creacion_consejo1').checked==true){
			$('como_obtuvo').value='';
			$('como_obtuvo').disabled=false;
		}else{
			$('como_obtuvo').value='';
			$('como_obtuvo').disabled='disabled';
		}

	}




	function guardar_censo_jefe_familia(){
		if($('select1').value==''){
			fun_msj('debe seleccionar el sector');
			document.getElementById('select1').focus();
			return false;
		}else if($('select2').value==''){
			fun_msj('debe seleccionar la calle');
			document.getElementById('select2').focus();
			return false;
		}else if($('nro_casa_parcela').value==''){
			fun_msj('ingrese el n&uacute;mero de casa o parcela');
			document.getElementById('nro_casa_parcela').focus();
			return false;
		}else if($('nombres').value==''){
			fun_msj('ingrese el nombre');
			document.getElementById('nombres').focus();
			return false;
		}else if($('apellidos').value==''){
			fun_msj('ingrese el apellido');
			document.getElementById('apellidos').focus();
			return false;
		}else if($('cedula_identidad').value==''){
			fun_msj('ingrese la c&eacute;dula de identidad');
			document.getElementById('cedula_identidad').focus();
			return false;
		}else if($('nacionalidad').value==''){
			fun_msj('debe seleccionar la nacionalidad');
			document.getElementById('nacionalidad').focus();
			return false;
		}else if($('sexo').value==''){
			fun_msj('seleccione el sexo');
			document.getElementById('sexo').focus();
			return false;
		}else if($('estado_civil1').checked==false && $('estado_civil2').checked==false && $('estado_civil3').checked==false && $('estado_civil4').checked==false && $('estado_civil5').checked==false && $('estado_civil6').checked==false){
			fun_msj('seleccione el estado civil');
			document.getElementById('estado_civil1').focus();
			return false;
		}else if($('fecha_nacimiento').value==''){
			fun_msj('ingrese la fecha de nacimiento');
			document.getElementById('fecha_nacimiento').focus();
			return false;
		}else if($('familia').value==0){
			fun_msj('ingrese los familiares');
			document.getElementById('familia').focus();
			return false;
		}else if($('donde_trabaja1').checked==false && $('donde_trabaja2').checked==false && $('donde_trabaja3').checked==false && $('donde_trabaja4').checked==false && $('donde_trabaja5').checked==false && $('donde_trabaja6').checked==false){
			fun_msj('seleccione donde trabaja');
			document.getElementById('donde_trabaja1').focus();
			return false;
		}else if($('donde_trabaja6').checked==true && $('otro_tipo_trabajo').value=='' ){
			fun_msj('especifique el otro tipo de trabajo');
			document.getElementById('otro_tipo_trabajo').focus();
			return false;
		}else if($('actividad_comercial_vivienda1').checked==false && $('actividad_comercial_vivienda2').checked==false ){
			fun_msj('seleccione si se realiza o no alguna actividad comercial dentro de la vivienda');
			document.getElementById('actividad_comercial_vivienda2').focus();
			return false;
		}else if($('actividad_comercial_vivienda1').checked==true && ($('venta_de1').checked==false && $('venta_de2').checked==false && $('venta_de3').checked==false && $('venta_de4').checked==false && $('venta_de5').checked==false && $('venta_de6').checked==false && $('venta_de7').checked==false && $('venta_de8').checked==false)){
			fun_msj('Especifique que tipo de venta realiza dentro de la vivienda');
			document.getElementById('venta_de8').focus();
			return false;
		}else if($('ingreso_familiar1').checked==false && $('ingreso_familiar2').checked==false && $('ingreso_familiar3').checked==false && $('ingreso_familiar4').checked==false && $('ingreso_familiar5').checked==false){
			fun_msj('seleccione el ingreso familiar');
			document.getElementById('ingreso_familiar1').focus();
			return false;
		}else if($('cuenta_bancaria1').checked==false && $('cuenta_bancaria2').checked==false){
			fun_msj('seleccione si tiene o no cuenta bancaria');
			document.getElementById('ingreso_familiar1').focus();
			return false;
		}else if($('tarjeta_credito1').checked==false && $('tarjeta_credito2').checked==false){
			fun_msj('seleccione si tiene o no trajeta de credito');
			document.getElementById('tarjeta_credito2').focus();
			return false;
		}else if($('cesta_ticket1').checked==false && $('cesta_ticket2').checked==false){
			fun_msj('seleccione si tiene o no cesta ticket');
			document.getElementById('cesta_ticket2').focus();
			return false;
		}else if($('tipo_vivienda').value==''){
			fun_msj('seleccione el tipo de vivienda');
			document.getElementById('tipo_vivienda').focus();
			return false;
		}else if($('forma_tenencia').value==''){
			fun_msj('seleccione la forma de tenencia');
			document.getElementById('forma_tenencia').focus();
			return false;
		}else if($('terreno_propio1').checked==false && $('terreno_propio2').checked==false){
			fun_msj('el terreno es propio, si o no?');
			document.getElementById('terreno_propio2').focus();
			return false;
		}else if($('pertenece_ocv1').checked==false && $('pertenece_ocv2').checked==false){
			fun_msj('pertenece a una ocv, si o no?');
			document.getElementById('pertenece_ocv2').focus();
			return false;
		}else if($('tipo_paredes').value==''){
			fun_msj('seleccione el tipo de paredes');
			document.getElementById('tipo_paredes').focus();
			return false;
		}else if($('tipo_techo').value==''){
			fun_msj('seleccione el tipo de techo');
			document.getElementById('tipo_techo').focus();
			return false;
		}else if($('enseres_vivienda1').checked==false && $('enseres_vivienda2').checked==false && $('enseres_vivienda3').checked==false && $('enseres_vivienda4').checked==false && $('enseres_vivienda5').checked==false && $('enseres_vivienda6').checked==false && $('enseres_vivienda7').checked==false && $('enseres_vivienda8').checked==false && $('enseres_vivienda9').checked==false && $('enseres_vivienda10').checked==false && $('enseres_vivienda11').checked==false){
			fun_msj('Especifique los enseres de la vivienda');
			document.getElementById('enseres_vivienda1').focus();
			return false;
		}else if($('condicion_salubridad').value==''){
			fun_msj('seleccione la condici&oacute;n de salubridad');
			document.getElementById('condicion_salubridad').focus();
			return false;
		}else if($('presencia_insectos1').checked==false && $('presencia_insectos2').checked==false){
			fun_msj('presencia de insectos en la vivienda, si o no?');
			document.getElementById('presencia_insectos2').focus();
			return false;
		}else if($('presencia_insectos1').checked==true && ($('insectos1').checked==false && $('insectos2').checked==false && $('insectos3').checked==false && $('insectos4').checked==false && $('insectos5').checked==false && $('insectos6').checked==false)){
			fun_msj('Especifique que tipo de insectos hay en la vivienda');
			document.getElementById('insectos1').focus();
			return false;
		}else if($('animales_domesticos1').checked==false && $('animales_domesticos2').checked==false){
			fun_msj('presencia de animales domesticos en la vivienda, si o no?');
			document.getElementById('animales_domesticos2').focus();
			return false;
		}else if($('animales_domesticos1').checked==true && ($('animales1').checked==false && $('animales2').checked==false && $('animales3').checked==false && $('animales4').checked==false && $('animales5').checked==false && $('animales6').checked==false && $('animales7').checked==false)){
			fun_msj('Especifique que tipo de animales domesticos hay en vivienda');
			document.getElementById('animales1').focus();
			return false;
		}else if($('requeriere_ayuda_especial1').checked==false && $('requeriere_ayuda_especial2').checked==false){
			fun_msj('Necesita de alguna Ayuda especial para familiares enfermos, si o no?');
			document.getElementById('requeriere_ayuda_especial2').focus();
			return false;
		}else if($('requeriere_ayuda_especial1').checked==true && $('cual_ayuda_especial').value==''){
			fun_msj('Especifique cual(es) ayuda especial');
			document.getElementById('cual_ayuda_especial').focus();
			return false;
		}else if($('aguas_blancas1').checked==false && $('aguas_blancas2').checked==false && $('aguas_blancas3').checked==false && $('aguas_blancas4').checked==false){
			fun_msj('seleccione como obtiene aguas blancas');
			document.getElementById('aguas_blancas1').focus();
			return false;
		}else if($('aguas_blancas_medidor1').checked==false && $('aguas_blancas_medidor2').checked==false){
			fun_msj('tiene medidor de aguas blancas, si o no?');
			document.getElementById('aguas_blancas_medidor2').focus();
			return false;
		}else if($('aguas_servidas1').checked==false && $('aguas_servidas2').checked==false && $('aguas_servidas3').checked==false && $('aguas_servidas4').checked==false && $('aguas_servidas5').checked==false && $('aguas_servidas6').checked==false){
			fun_msj('seleccione como se desechan las aguas servidas');
			document.getElementById('aguas_servidas1').focus();
			return false;
		}else if($('gas1').checked==false && $('gas2').checked==false && $('gas3').checked==false){
			fun_msj('seleccione como obtiene el gas');
			document.getElementById('gas1').focus();
			return false;
		}else if($('electrificado1').checked==false && $('electrificado2').checked==false && $('electrificado3').checked==false){
			fun_msj('seleccione como obtiene la electricidad');
			document.getElementById('electrificado1').focus();
			return false;
		}else if($('tiene_medidor_electricidad1').checked==false && $('tiene_medidor_electricidad2').checked==false){
			fun_msj('tiene medidor de electricidad, si o no?');
			document.getElementById('tiene_medidor_electricidad2').focus();
			return false;
		}else if($('basura1').checked==false && $('basura2').checked==false && $('basura3').checked==false && $('basura4').checked==false && $('basura5').checked==false && $('basura6').checked==false && $('basura7').checked==false){
			fun_msj('seleccione como se recolecta la basura');
			document.getElementById('basura1').focus();
			return false;
		}else if($('telefonia1').checked==false && $('telefonia2').checked==false && $('telefonia3').checked==false && $('telefonia4').checked==false && $('telefonia5').checked==false){
			fun_msj('seleccione como obtiene servicio de telefonia');
			document.getElementById('telefonia1').focus();
			return false;
		}else if($('transporte1').checked==false && $('transporte2').checked==false && $('transporte3').checked==false && $('transporte4').checked==false){
			fun_msj('seleccione como se transporta');
			document.getElementById('transporte1').focus();
			return false;
		}else if($('mecanismo_informacion1').checked==false && $('mecanismo_informacion2').checked==false && $('mecanismo_informacion3').checked==false && $('mecanismo_informacion4').checked==false && $('mecanismo_informacion5').checked==false && $('mecanismo_informacion6').checked==false){
			fun_msj('seleccione por medio de que mecanismo obtiene informaci&oacute;n');
			document.getElementById('mecanismo_informacion1').focus();
			return false;
		}else if($('servicios_comunales1').checked==false && $('servicios_comunales2').checked==false && $('servicios_comunales3').checked==false && $('servicios_comunales4').checked==false && $('servicios_comunales5').checked==false && $('servicios_comunales6').checked==false && $('servicios_comunales7').checked==false && $('servicios_comunales8').checked==false && $('servicios_comunales9').checked==false && $('servicios_comunales10').checked==false && $('servicios_comunales11').checked==false && $('servicios_comunales12').checked==false && $('servicios_comunales13').checked==false){
			fun_msj('seleccione los servicios comunales');
			document.getElementById('servicios_comunales1').focus();
			return false;
		}else if($('organizaciones_comunitarias1').checked==false && $('organizaciones_comunitarias2').checked==false){
			fun_msj('existen organizaciones comunitaria, si o no?');
			document.getElementById('organizaciones_comunitarias2').focus();
			return false;
		}else if($('organizaciones_comunitarias1').checked==true && $('participa_organizacion_comunitaria1').checked==false && $('participa_organizacion_comunitaria2').checked==false){
			fun_msj('participa en alguna de ellas, si o no?');
			document.getElementById('participa_organizacion_comunitaria2').focus();
			return false;
		}else if($('organizaciones_comunitarias1').checked==true && $('participa_organizacion_comunitaria_miembro1').checked==false && $('participa_organizacion_comunitaria_miembro2').checked==false){
			fun_msj('participa alg&uacute;n miembro de la familia en alguna de ellas, si o no?');
			document.getElementById('participa_organizacion_comunitaria_miembro2').focus();
			return false;
		}else if($('cuales_misiones1').checked==false && $('cuales_misiones2').checked==false && $('cuales_misiones3').checked==false && $('cuales_misiones4').checked==false && $('cuales_misiones5').checked==false && $('cuales_misiones6').checked==false && $('cuales_misiones7').checked==false && $('cuales_misiones8').checked==false){
			fun_msj('especifique cuales Misiones se est&aacute;n implementando en su Comunidad');
			document.getElementById('cuales_misiones1').focus();
			return false;
		}else if($('cuales_misiones8').checked==true && $('cual_otra_mision').value==''){
			fun_msj('especifique cual otra misi&oacute;n');
			document.getElementById('cual_otra_mision').focus();
			return false;
		}else if($('interviene_repartir_recursos1').checked==false && $('interviene_repartir_recursos2').checked==false){
			fun_msj('Usted est&aacute; interviniendo en sesiones sobre como deben repartir los recursos de su comunidad?');
			document.getElementById('interviene_repartir_recursos2').focus();
			return false;
		}else if($('protagonismo_presupuestario1').checked==false && $('protagonismo_presupuestario2').checked==false){
			fun_msj('Esta de acuerdo, que seg&uacute;n la Constituci&oacute;n es ahora el Pueblo organizado qui&eacute;n debe tener el protagonismo y el poder para decidir sobre como invertir el presupuesto en su comunidad?');
			document.getElementById('protagonismo_presupuestario2').focus();
			return false;
		}else if($('informacion_creacion_consejo1').checked==false && $('informacion_creacion_consejo2').checked==false){
			fun_msj('Tiene informaci&oacute;n sobre la propuesta de creaci&oacute;n de consejos comunales?');
			document.getElementById('informacion_creacion_consejo2').focus();
			return false;
		}else if($('informacion_creacion_consejo1').checked==true && $('como_obtuvo').value==''){
			fun_msj('especifique como la obtuvo?');
			document.getElementById('informacion_creacion_consejo2').focus();
			return false;
		}else if($('participar_creacion_consejo1').checked==false && $('participar_creacion_consejo2').checked==false){
			fun_msj('Estaria dispuesto(a) a apoyar, participar en la creaci&oacute;n de un consejo comunal en su comunidad?');
			document.getElementById('participar_creacion_consejo2').focus();
			return false;
		}else if($('realizarse_consejo_comunal1').checked==false && $('realizarse_consejo_comunal2').checked==false && $('realizarse_consejo_comunal3').checked==false && $('realizarse_consejo_comunal4').checked==false && $('realizarse_consejo_comunal5').checked==false && $('realizarse_consejo_comunal6').checked==false && $('realizarse_consejo_comunal7').checked==false && $('realizarse_consejo_comunal8').checked==false && $('realizarse_consejo_comunal9').checked==false && $('realizarse_consejo_comunal10').checked==false && $('realizarse_consejo_comunal11').checked==false){
			fun_msj('Realizarse un consejo en su comunidad en cual &aacute;rea de trabajo le gustaria participar (Marque tres)');
			document.getElementById('cuales_misiones1').focus();
			return false;
		}

	}


function valida_busqueda_censo_demografico(){
		if($('select1').value==''){
			fun_msj('debe seleccionar el sector');
			document.getElementById('select1').focus();
			return false;
		}else if($('select2').value==''){
			fun_msj('debe seleccionar la calle');
			document.getElementById('select2').focus();
			return false;
		}else if($('select3').value==''){
			fun_msj('debe seleccionar el n&uacute;mero de casa o parcela');
			document.getElementById('select3').focus();
			return false;
		}


}

function validarec_cugp01sector(){
		if($('c_sector').value==''){
			fun_msj('El C&oacute;digo del sector no puede estar vacio');
			document.getElementById('c_sector').focus();
			return false;
		}else if($('denominacion').value==''){
			fun_msj('debe ingresar la denominaci&oacute;n del sector');
			document.getElementById('denominacion').focus();
			return false;
		}
}

function validarec_cugp01calles(){
		if($('c_sector').value==''){
			fun_msj('El C&oacute;digo del sector no puede estar vacio');
			document.getElementById('c_sector').focus();
			return false;
		}else if($('denominacion_sector').value==''){
			fun_msj('debe seleccionar el sector');
			document.getElementById('denominacion_sector').focus();
			return false;
		}else if($('c_calle').value==''){
			fun_msj('El C&oacute;digo de la calle no puede estar vacio');
			document.getElementById('c_calle').focus();
			return false;
		}else if($('denominacion').value==''){
			fun_msj('debe ingresar la denominaci&oacute;n de la calle');
			document.getElementById('denominacion').focus();
			return false;
		}
}

/*
este va antes de donde trabaja
else if($('familia').value==0){
			fun_msj('ingrese los familiares');
			document.getElementById('familia').focus();
			return false;
		}*/
