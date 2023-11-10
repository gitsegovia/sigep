function valida_cpcp01(){

    if(document.getElementById('denominacion').value==""){

        fun_msj('Inserte la Denominaci&oacute;n');
        document.getElementById('denominacion').focus();
        return false;
    }

}

function valida_busqueda(){

    if(document.getElementById('buscar_pista').value==""){

        fun_msj('Inserte la pista');
        document.getElementById('buscar_pista').focus();
        return false;
    }

}

/*
function valida_inventario_muebles(){


    if(document.getElementById('pista').value==""){

        fun_msj('Por favor realice la busqueda del bien mueble que desea registrar');
        document.getElementById('pista').focus();
        return false;


    }else if(document.getElementById('denominacion').value==""){

        fun_msj('Inserte la denominacion del Mueble');
        document.getElementById('denominacion').focus();
        return false;

    }else if(document.getElementById('numero_a_registrar').value==""){

        fun_msj('Inserte el numero de bienes a registrar');
        document.getElementById('numero_a_registrar').focus();
        return false;

    }else if(document.getElementById('numero_a_registrar').value==""){

        fun_msj('Inserte el numero de bienes a registrar');
        document.getElementById('numero_a_registrar').focus();
        return false;

    }else if(document.getElementById('valor_unitario').value==""){

        fun_msj('Inserte el valor unitario');
        document.getElementById('valor_unitario').focus();
        return false;

    }else if(document.getElementById('select_incorporacion').value==""){

        fun_msj('Seleccione el codigo de incorporacion');
        document.getElementById('select_incorporacion').focus();
        return false;

    }else if(document.getElementById('fecha_incorporacion').value==""){

        fun_msj('Seleccione la Fecha de incorporacion');
        document.getElementById('fecha_incorporacion').focus();
        return false;

    }else if(document.getElementById('s_1').value==""){

        fun_msj('Seleccione el estado');
        document.getElementById('s_1').focus();
        return false;

    }else if(document.getElementById('s_2').value==""){

        fun_msj('Seleccione el municipio');
        document.getElementById('s_2').focus();
        return false;

    }else if(document.getElementById('s_3').value==""){

        fun_msj('Seleccione la parroquia');
        document.getElementById('s_3').focus();
        return false;

    }else if(document.getElementById('s_4').value==""){

        fun_msj('Seleccione el centro poblado');
        document.getElementById('s_4').focus();
        return false;

    }else if(document.getElementById('x_5').value==""){

        fun_msj('Seleccione la institucion');
        document.getElementById('x_5').focus();
        return false;

    }else if(document.getElementById('x_6').value==""){

        fun_msj('Seleccione la dependencia');
        document.getElementById('x_6').focus();
        return false;

    }else if(document.getElementById('x_7').value==""){

        fun_msj('Seleccione la direccion superior');
        document.getElementById('x_7').focus();
        return false;

    }else if(document.getElementById('x_8').value==""){

        fun_msj('Seleccione la coordinacion');
        document.getElementById('x_8').focus();
        return false;

    }else if(document.getElementById('x_9').value==""){

        fun_msj('Seleccione la secretaria');
        document.getElementById('x_9').focus();
        return false;

    }else if(document.getElementById('x_10').value==""){

        fun_msj('Seleccione la direccion');
        document.getElementById('x_10').focus();
        return false;

    }else if(document.getElementById('x_11').value==""){

        fun_msj('Seleccione la division');
        document.getElementById('x_11').focus();
        return false;

    }else if(document.getElementById('x_12').value==""){

        fun_msj('Seleccione el departamento');
        document.getElementById('x_12').focus();
        return false;

    }else if(document.getElementById('x_13').value==""){

        fun_msj('Seleccione la oficina');
        document.getElementById('x_13').focus();
        return false;

    }else if(eval(document.getElementById('select_incorporacion').value)!=eval(1)){

        if(verifica_cierre_ano_ejecucion('fecha_incorporacion')==false){
            fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA DE INCORPORACI&Oacute;N NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
            return false;
        }
    }

}
*/

function valida_cimp04_vehiculo_asegurado(){


    if(document.getElementById('pista').value==""){

        fun_msj('Por favor realice la busqueda del vehiculo que desea registrar');
        document.getElementById('pista').focus();
        return false;


    }else if(document.getElementById('placa').value==""){

        fun_msj('Inserte la placa del vehiculo');
        document.getElementById('placa').focus();
        return false;

    }else if(document.getElementById('numero_poliza').value==""){

        fun_msj('Inserte el numero de la poliza');
        document.getElementById('numero_poliza').focus();
        return false;

    }else if(document.getElementById('monto_cobertura').value==""){

        fun_msj('Ingrese el monto de la cobertura');
        document.getElementById('monto_cobertura').focus();
        return false;

    }else if(document.getElementById('compania_aseguradora').value==""){

        fun_msj('Ingrese la compania aseguradora');
        document.getElementById('compania_aseguradora').focus();
        return false;

    }else if(document.getElementById('descripcion_cobertura').value==""){

        fun_msj('Ingrese la desacripcion de la cobertura');
        document.getElementById('descripcion_cobertura').focus();
        return false;

    }else if(document.getElementById('vehiculo_asignado').value==""){

        fun_msj('Ingrese a quien fue asignado el vehiculo');
        document.getElementById('vehiculo_asignado').focus();
        return false;

    }

}
/*
function valida_inventario_inmuebles(){


    if(document.getElementById('pista').value==""){

        fun_msj('Por favor realice la busqueda del bien inmueble que desea registrar');
        document.getElementById('pista').focus();
        return false;


    }else if(document.getElementById('denominacion').value==""){

        fun_msj('Inserte la denominacion del Inmueble');
        document.getElementById('denominacion').focus();
        return false;

    }else if(document.getElementById('s_1').value==""){

        fun_msj('Seleccione el estado');
        document.getElementById('s_1').focus();
        return false;

    }else if(document.getElementById('s_2').value==""){

        fun_msj('Seleccione el municipio');
        document.getElementById('s_2').focus();
        return false;

    }else if(document.getElementById('s_3').value==""){

        fun_msj('Seleccione la parroquia');
        document.getElementById('s_3').focus();
        return false;

    }else if(document.getElementById('s_4').value==""){

        fun_msj('Seleccione el centro poblado');
        document.getElementById('s_4').focus();
        return false;

    }else if(document.getElementById('area_total_terreno').value==""){

        fun_msj('Ingrese el area total de terreno');
        document.getElementById('area_total_terreno').focus();
        return false;

    }else if(document.getElementById('area_cubierta').value==""){

        fun_msj('Ingrese el area cubierta');
        document.getElementById('area_cubierta').focus();
        return false;

    }else if(document.getElementById('area_construccion').value==""){

        fun_msj('Ingrese el area de la construccion');
        document.getElementById('area_construccion').focus();
        return false;

    }else if(document.getElementById('area_otras_instalaciones').value==""){

        fun_msj('Ingrese el area de otras instalaciones');
        document.getElementById('area_otras_instalaciones').focus();
        return false;

    }else if(document.getElementById('area_total_construida').value==""){

        fun_msj('Ingrese el area total construida');
        document.getElementById('area_total_construida').focus();
        return false;

    }else if(document.getElementById('avaluo_actual').value==""){

        fun_msj('Ingrese el avaluo actual');
        document.getElementById('avaluo_actual').focus();
        return false;

    }else if(document.getElementById('descripcion_inmueble').value==""){

        fun_msj('Ingrese la descripcion del inmueble');
        document.getElementById('descripcion_inmueble').focus();
        return false;

    }else if(document.getElementById('linderos').value==""){

        fun_msj('Ingrese los linderos');
        document.getElementById('linderos').focus();
        return false;

    }else if(document.getElementById('estudio_legal_propiedad').value==""){

        fun_msj('Ingrese el estudio legal de la propiedad');
        document.getElementById('estudio_legal_propiedad').focus();
        return false;

    }else if(document.getElementById('avaluo_comision').value==""){

        fun_msj('Ingrese avaluo de la comision');
        document.getElementById('avaluo_comision').focus();
        return false;

    }else if(document.getElementById('select_incorporacion').value==""){

        fun_msj('Seleccione el codigo de incorporacion');
        document.getElementById('select_incorporacion').focus();
        return false;

    }else if(document.getElementById('fecha_incorporacion').value==""){

        fun_msj('Seleccione la Fecha de incorporacion');
        document.getElementById('fecha_incorporacion').focus();
        return false;

    }else if(verifica_cierre_ano_ejecucion('fecha_incorporacion')==false){
        fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA DE INCORPORACI&Oacute;N NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
        return false;
    }
}
*/
/*
function valida_cimp05_equipos_mantenimiento(){


    if(document.getElementById('pista').value==""){

        fun_msj('Por favor realice la busqueda del Equipo que desea registrar');
        document.getElementById('pista').focus();
        return false;


    }else if(document.getElementById('fecha_reparacion').value==""){

        fun_msj('Ingrese la fecha de la reparacion');
        document.getElementById('fecha_reparacion').focus();
        return false;

    }else if(document.getElementById('select_repa').value==""){

        fun_msj('Seleccione el tipo de reparacion');
        document.getElementById('select_repa').focus();
        return false;

    }else if(document.getElementById('select_repu').value==""){

        fun_msj('Seleccione el tipo de repuesto');
        document.getElementById('select_repu').focus();
        return false;

    }else if(document.getElementById('cantidad').value==""){

        fun_msj('Ingrese la cantidad');
        document.getElementById('cantidad').focus();
        return false;

    }else if(document.getElementById('costo_unitario').value==""){

        fun_msj('Ingrese el costo unitario');
        document.getElementById('costo_unitario').focus();
        return false;

    }else if(document.getElementById('total').value==""){

        fun_msj('Ingrese el total');
        document.getElementById('total').focus();
        return false;

    }else if(document.getElementById('tiempo_garantia').value==""){

        fun_msj('Ingrese el tiempo de garantia');
        document.getElementById('tiempo_garantia').focus();
        return false;

    }else if(document.getElementById('tienda_taller').value==""){

        fun_msj('Ingrese la tienda o taller');
        document.getElementById('tienda_taller').focus();
        return false;

    }else if(document.getElementById('tecnico_mecanico').value==""){

        fun_msj('Ingrese el tecnico o mecanico');
        document.getElementById('tecnico_mecanico').focus();
        return false;

    }else if(document.getElementById('reparacion_efectuada').value==""){

        fun_msj('Ingrese la reparacion efectuada al equipo');
        document.getElementById('reparacion_efectuada').focus();
        return false;

    }
}
*/
function calculo_total_mantenimiento(){
    a = document.getElementById('cantidad').value;
    b = document.getElementById('costo_unitario').value;
    var str = b;
    for(i=0; i<b.length; i++){
        str = str.replace('.','');
    }//fin for
    str = str.replace(',','.');
    var b = redondear(str,2);
    c= eval(a) * eval(b);
    pag="../../include/cfpp05/moneda.php?monto="+c;
    cargarMonto("total",pag);
    document.getElementById('total').value=c;
}

function validar_tipo_movimiento(){


    if(document.getElementById('tipo').value==""){

        fun_msj('Por favor seleccione el tipo de movimiento que desea registrar');
        document.getElementById('tipo').focus();
        return false;


    }else if(document.getElementById('codigo').value==""){

        fun_msj('Ingrese el codigo del movimiento');
        document.getElementById('codigo').focus();
        return false;

    }else if(document.getElementById('denominacion').value==""){

        fun_msj('ingrese la denominacion del movimiento');
        document.getElementById('denominacion').focus();
        return false;

    }
}

function valida_grupo(){


    if(document.getElementById('x_1').value==""){

        fun_msj('Por favor seleccione el tipo');
        document.getElementById('x_1').focus();
        return false;


    }else if(document.getElementById('cod_grupo').value==""){

        fun_msj('Ingrese el codigo del grupo');
        document.getElementById('cod_grupo').focus();
        return false;

    }else if(document.getElementById('deno_grupo').value==""){

        fun_msj('ingrese la denominacion del grupo');
        document.getElementById('deno_grupo').focus();
        return false;

    }
}

function valida_subgrupo(){


    if(document.getElementById('x_1').value==""){

        fun_msj('Por favor seleccione el tipo');
        document.getElementById('x_1').focus();
        return false;


    }else if(document.getElementById('x_2').value==""){

        fun_msj('Por favor seleccione el grupo');
        document.getElementById('x_2').focus();
        return false;


    }else if(document.getElementById('cod_subgrupo').value==""){

        fun_msj('Ingrese el codigo del subgrupo');
        document.getElementById('cod_subgrupo').focus();
        return false;

    }else if(document.getElementById('deno_subgrupo').value==""){

        fun_msj('ingrese la denominacion del subgrupo');
        document.getElementById('deno_subgrupo').focus();
        return false;

    }
}


function valida_institutos(){

    if(document.getElementById('denominacion').value==""){

        fun_msj('Inserte la Denominaci&oacute;n del Instituto');
        document.getElementById('denominacion').focus();
        return false;
    }

}

function valida_grilla_visitas(){


    if(document.getElementById('cedula').value==""){

        fun_msj('Por favor ingrese c&eacute;dula de identidad');
        document.getElementById('cedula').focus();
        return false;


    }else if(document.getElementById('nombres').value==""){

        fun_msj('Por favor ingrese nombres y apellidos');
        document.getElementById('nombres').focus();
        return false;


    }else if(document.getElementById('direccion').value==""){

        fun_msj('por favor Ingrese direcci&oacute;n');
        document.getElementById('direccion').focus();
        return false;

    }else if(document.getElementById('fecha').value==""){

        fun_msj('Por favor seleccione fecha');
        document.getElementById('fecha').focus();
        return false;

    }else if(document.getElementById('hora').value==""){

        fun_msj('Por favor seleccione hora');
        document.getElementById('hora').focus();
        return false;

    }else if(document.getElementById('x_7').value==""){

        fun_msj('Por favor seleccione Dir. Superior');
        document.getElementById('x_7').focus();
        return false;

    }else if(document.getElementById('x_8').value==""){

        fun_msj('Por favor seleccione coordinaci&oacute;n');
        document.getElementById('x_8').focus();
        return false;

    }else if(document.getElementById('x_9').value==""){

        fun_msj('Por favor seleccione secretaria');
        document.getElementById('x_9').focus();
        return false;

    }else if(document.getElementById('x_10').value==""){

        fun_msj('Por favor seleccione direcci&oacute;n');
        document.getElementById('x_10').focus();
        return false;

    }
}

function valida_grilla_visitas2(){


    if(document.getElementById('cedula').value==""){

        fun_msj('Por favor ingrese c&eacute;dula de identidad');
        document.getElementById('cedula').focus();
        return false;


    }else if(document.getElementById('nombres').value==""){

        fun_msj('Por favor ingrese nombres y apellidos');
        document.getElementById('nombres').focus();
        return false;


    }else if(document.getElementById('direccion').value==""){

        fun_msj('por favor Ingrese direcci&oacute;n');
        document.getElementById('direccion').focus();
        return false;

    }
}