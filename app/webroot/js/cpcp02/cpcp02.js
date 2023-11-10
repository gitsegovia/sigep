function ventanaSecundaria2(URL) {
    window.open(URL, "requisitos", "width=950,height=700,fullscreen=no,scrollbars=yes,resizable=yes,location=no,status=no,menubar=no,toolbar=no,titlebar=no")
}

function valida_cpcp02() {

    if (document.getElementById('objeto_5').checked == true || document.getElementById('objeto_6').checked == true) {

        if (document.getElementById('rif').value == "") {
            fun_msj('Ingrese el Rif de la Empresa');
            document.getElementById('rif').focus();
            return false;

        } else if (document.getElementById('razon').value == "") {

            fun_msj('Ingrese la Razon Social');
            document.getElementById('razon').focus();
            return false;

        } else if (document.getElementById('numero2_1').checked == false && document.getElementById('numero2_2').checked == false) {

            fun_msj('Ingrese el Numero de Expediente');
            return false;

        } else if (document.getElementById('expediente_2').value == "") {
            fun_msj('Ingrese el Numero de Expediente');
            document.getElementById('expediente_2').focus();
            return false;

        } else if (document.getElementById('objeto_1').checked == false && document.getElementById('objeto_2').checked == false && document.getElementById('objeto_3').checked == false && document.getElementById('objeto_4').checked == false && document.getElementById('objeto_5').checked == false && document.getElementById('objeto_6').checked == false) {

            fun_msj('Por Favor Seleccione el Objeto ');
            //document.getElementById('x_1').focus();
            return false;

        } else if (document.getElementById('select_ramo').value == "") {

            fun_msj('Seleccione el Ramo Comercial');
            document.getElementById('select_ramo').focus();
            return false;

        } else if (document.getElementById('direccion_comercial').value == "") {

            fun_msj('Ingrese la Direccion exacta de la sede');
            document.getElementById('direccion_comercial').focus();
            return false;

        } else if (document.getElementById('descripcion_objeto').value == "") {

            fun_msj('Ingrese la Descripcion Breve del Objeto');
            document.getElementById('descripcion_objeto').focus();
            return false;

        } else if (document.getElementById('select_1').value == "") {

            fun_msj('Seleccione El Estado Correspondiente');
            document.getElementById('select_1').focus();
            return false;

        } else if (document.getElementById('select_2').value == "") {

            fun_msj('seleccione El Municipio Correspondiente');
            document.getElementById('select_2').focus();
            return false;

        } else if (document.getElementById('rif').value != "0") {
            var elRIF = document.getElementById('rif').value;
            var temp = elRIF.toUpperCase();
            if (!/^[JVEGPIRC]/.test(temp)) {
                alert("El primer caracter es incorrecto, debe ser una letra de las siguientes: J,V,E,G,P,I,R,C");
                return false;
            } else if (!/^[JVEGPIRC][-][0-9]{8}[-][0-9]$/.test(temp)) { // Son 9 d�gitos?
                alert("Por Favor Verifique que el RIF tenga el siguiente formato: J-12345678-9 \nSi su Rif es menor a 9 digitos rellene con 0 a la izquierda Ej: J-00123456-7\n Su rif actual es: " + temp);
                return false;
            }

        }

    } else {
        if (document.getElementById('rif').value == "") {

            fun_msj('Ingrese el Rif de la Empresa');
            document.getElementById('rif').focus();
            return false;

        } else if (document.getElementById('razon').value == "") {

            fun_msj('Ingrese la Razon Social');
            document.getElementById('razon').focus();
            return false;

        } else if (document.getElementById('numero2_1').checked == false && document.getElementById('numero2_2').checked == false) {

            fun_msj('Ingrese el Numero de Expediente');
            return false;

        } else if (document.getElementById('expediente_2').value == "") {
            fun_msj('Ingrese el Numero de Expediente');
            document.getElementById('expediente_2').focus();
            return false;

        }
        if (document.getElementById('numero_empleados').value == "") {

            fun_msj('Ingrese el numero de Empleados');
            document.getElementById('numero_empleados').focus();
            return false;

        } else if (document.getElementById('objeto_1').checked == false && document.getElementById('objeto_2').checked == false && document.getElementById('objeto_3').checked == false && document.getElementById('objeto_4').checked == false) {

            fun_msj('Por Favor Seleccione el Objeto ');
            //document.getElementById('x_1').focus();
            return false;

        } else if (document.getElementById('select_ramo').value == "") {

            fun_msj('Seleccione el Ramo Comercial');
            document.getElementById('select_ramo').focus();
            return false;

        } else if (document.getElementById('direccion_comercial').value == "") {

            fun_msj('Ingrese la Direccion exacta de la sede');
            document.getElementById('direccion_comercial').focus();
            return false;

        } else if (document.getElementById('descripcion_objeto').value == "") {

            fun_msj('Ingrese la Descripcion Breve del Objeto');
            document.getElementById('descripcion_objeto').focus();
            return false;

        } else if (document.getElementById('select_1').value == "") {

            fun_msj('Seleccione El Estado Correspondiente');
            document.getElementById('select_1').focus();
            return false;

        } else if (document.getElementById('select_2').value == "") {

            fun_msj('seleccione El Municipio Correspondiente');
            document.getElementById('select_2').focus();
            return false;

        }//else if(document.getElementById('objeto_3').checked==true){

        //		alert('si es 3');

//}
        else if (document.getElementById('objeto_3').checked == true && document.getElementById('numero_sunacoop').value == "") {

            fun_msj('Ingrese el Numero de Sunacoop');
            document.getElementById('numero_sunacoop').focus();
            return false;

        } else if (document.getElementById('codigo_area_empresa').value == "") {

            fun_msj('Ingrese el Codigo de area del objeto');
            document.getElementById('codigo_area_empresa').focus();
            return false;

        } else if (document.getElementById('telefonos_empresa').value == "") {

            fun_msj('Ingrese telefonos del objeto');
            document.getElementById('telefonos_empresa').focus();
            return false;

        } else if (document.getElementById('zona_postal_empresa').value == "") {

            fun_msj('Ingrese la zona postal de la empresa');
            document.getElementById('zona_postal_empresa').focus();
            return false;

        } else if ((document.getElementById('correo_empresa').value.search("@") == -1) || (document.getElementById('correo_empresa').value.search("[.*]") == -1)) {

            fun_msj("Por favor, revise el Email de la Empresa.");
            document.getElementById('correo_empresa').focus();
            return false;


        } else if (document.getElementById('equipos_disponibles').value == "") {

            fun_msj('Ingrese los Equipos y/o Materiales disponibles del objeto');
            document.getElementById('equipos_disponibles').focus();
            return false;

        } else if (document.getElementById('capacidad_financiera').value == "") {

            fun_msj('Ingrese la capacidad financiera del objeto');
            document.getElementById('capacidad_financiera').focus();
            return false;

        } else if (document.getElementById('registro_mercantil').value == "") {

            fun_msj('Ingrese el registro mercantil');
            document.getElementById('registro_mercantil').focus();
            return false;

        } else if (document.getElementById('socios').value == "") {

            fun_msj('Ingrese los socios del objeto');
            document.getElementById('socios').focus();
            return false;

        } else if (document.getElementById('nombre_representante').value == "") {

            fun_msj('Ingrese Nombre y Apellido del Representante Legal del objeto');
            document.getElementById('nombre_representante').focus();
            return false;

        } else if (document.getElementById('direccion_representante').value == "") {

            fun_msj('Ingrese la Direccion del Representante Legal');
            document.getElementById('direccion_representante').focus();
            return false;

        } else if (document.getElementById('cedula_ide').value == "") {

            fun_msj('Ingrese la c&eacute;dula de Identidad del Representante Legal del Objeto');
            document.getElementById('cedula_ide').focus();
            return false;

        } else if (document.getElementById('codigo_area_representante').value == "") {

            fun_msj('Ingrese el codigo de area del representante legal');
            document.getElementById('codigo_area_representante').focus();
            return false;

        } else if (document.getElementById('telefonos_fijos').value == "") {

            fun_msj('Ingrese los telefonos fijos del representante legal');
            document.getElementById('telefonos_fijos').focus();
            return false;

        } else if (document.getElementById('telefonos_moviles').value == "") {

            fun_msj('Ingrese los telefonos moviles del representante legal');
            document.getElementById('telefonos_moviles').focus();
            return false;

        } else if ((document.getElementById('correo_representante').value.search("@") == -1) || (document.getElementById('correo_representante').value.search("[.*]") == -1)) {

            fun_msj("Por favor, revise el Email del Representante.");
            document.getElementById('correo_representante').focus();
            return false;


        }
        else if (document.getElementById('numero_ocei').value == "") {

            fun_msj('Ingrese el numero de solvencia S.N.C');
            document.getElementById('numero_ocei').focus();
            return false;

        } else if (document.getElementById('fecha_ocei').value == "") {

            fun_msj('Ingrese la fecha de S.N.C');
            document.getElementById('fecha_ocei').focus();
            return false;

        } else if (document.getElementById('numero_laboral').value == "") {

            fun_msj('Ingrese el numero se la solvencia laboral');
            document.getElementById('numero_laboral').focus();
            return false;

        } else if (document.getElementById('fecha_laboral').value == "") {

            fun_msj('Ingrese la fecha laboral');
            document.getElementById('fecha_laboral').focus();
            return false;

        } else if (document.getElementById('numero_seguro').value == "") {

            fun_msj('Ingrese numero de solvecia del S.S.O');
            document.getElementById('numero_seguro').focus();
            return false;

        } else if (document.getElementById('fecha_seguro').value == "") {

            fun_msj('Ingrese la fecha del S.S.O');
            document.getElementById('fecha_seguro').focus();
            return false;

        } else if (document.getElementById('numero_ince').value == "") {

            fun_msj('Ingrese numero de solvecia del INCE');
            document.getElementById('numero_ince').focus();
            return false;

        } else if (document.getElementById('fecha_ince').value == "") {

            fun_msj('Ingrese la fecha del INCE');
            document.getElementById('fecha_ince').focus();
            return false;

        } else if (document.getElementById('numero_municipal').value == "") {

            fun_msj('Ingrese numero de solvecia municipal');
            document.getElementById('numero_municipal').focus();
            return false;

        } else if (document.getElementById('fecha_municipal').value == "") {

            fun_msj('Ingrese la fecha Municipal');
            document.getElementById('fecha_municipal').focus();
            return false;

        } else if (document.getElementById('observacion').value == "") {
            fun_msj('Ingrese las observaciones que desee hacer');
            document.getElementById('observacion').focus();
            return false;
        } else if (document.getElementById('fecha_inscripcion').value == "") {

            fun_msj('Ingrese la fecha de inscripcion');
            document.getElementById('fecha_inscripcion').focus();
            return false;

        } else if (verifica_cierre_ano_ejecucion('fecha_inscripcion') == false) {
            fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA DE INSCRIPCI&Oacute;N NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
            return false;
        } else if (document.getElementById('fecha_actualizacion').value == "") {
            fun_msj('Ingrese la fecha de actualizacion');
            document.getElementById('fecha_actualizacion').focus();
            return false;

        } else if (document.getElementById('fecha_inscrip_inicial_snc').value == "") {
            fun_msj('Ingrese la fecha de Inscripcion Inicial SNC');
            document.getElementById('fecha_inscrip_inicial_snc').focus();
            return false;

        } else if (document.getElementById('categoria_suministro').value == "") {
            fun_msj('Seleccione la Categoria del Suministro ');
            document.getElementById('categoria_suministro').focus();
            return false;

        } else if (document.getElementById('suministro_cliente_similar').value == "") {
            fun_msj('Seleccione Suministro Similar');
            document.getElementById('suministro_cliente_similar').focus();
            return false;
            
        }else if (document.getElementById('rif').value != "0") {
            var elRIF = document.getElementById('rif').value;
            var temp = elRIF.toUpperCase();
            if (!/^[JVEGPIRC]/.test(temp)) {
                alert("El primer caracter es incorrecto, debe ser una letra de las siguientes: J,V,E,G,P,I,R,C");
                return false;
            } else if (!/^[JVEGPIRC][-][0-9]{8}[-][0-9]$/.test(temp)) { // Son 9 d�gitos?
                alert("Por Favor Verifique que el RIF tenga el siguiente formato: J-12345678-9 \nSi su Rif es menor a 9 digitos rellene con 0 a la izquierda Ej: J-00123456-7\n Su rif actual es: " + temp);
                return false;
            }

        } 



    }

}



function valida_cpcp022() {//arranca principal
    if (document.getElementById('objeto_5').checked == true || document.getElementById('objeto_6').checked == true) {///si es 5 o 6
        if (document.getElementById('rif').value == "") {//arranca

            fun_msj('Ingrese el Rif de la Empresa');
            document.getElementById('rif').focus();
            return false;

        } else if (document.getElementById('razon').value == "") {

            fun_msj('Ingrese la Razon Social');
            document.getElementById('razon').focus();
            return false;

        } else if (document.getElementById('objeto_1').checked == false && document.getElementById('objeto_2').checked == false && document.getElementById('objeto_3').checked == false && document.getElementById('objeto_4').checked == false && document.getElementById('objeto_5').checked == false && document.getElementById('objeto_6').checked == false) {

            fun_msj('Por Favor Seleccione el Objeto ');
            //document.getElementById('x_1').focus();
            return false;

        } else if (document.getElementById('select_ramo').value == "") {

            fun_msj('Seleccione el Ramo Comercial');
            document.getElementById('select_ramo').focus();
            return false;

        } else if (document.getElementById('direccion_comercial').value == "") {

            fun_msj('Ingrese la Direccion exacta de la sede');
            document.getElementById('direccion_comercial').focus();
            return false;

        } else if (document.getElementById('descripcion_objeto').value == "") {

            fun_msj('Ingrese la Descripcion Breve del Objeto');
            document.getElementById('descripcion_objeto').focus();
            return false;

        } else if (document.getElementById('select_1').value == "") {

            fun_msj('Seleccione El Estado Correspondiente');
            document.getElementById('select_1').focus();
            return false;

        } else if (document.getElementById('select_2').value == "") {

            fun_msj('seleccione El Municipio Correspondiente');
            document.getElementById('select_2').focus();
            return false;

        }
    }//cierra 5 o 6
    else if (document.getElementById('rif').value != "0") {
        var elRIF = document.getElementById('rif').value;
        var temp = elRIF.toUpperCase();
        if (!/^[JVEGPIRC]/.test(temp)) {
            alert("El primer caracter es incorrecto, debe ser una letra de las siguientes: J,V,E,G,P,I,R,C");
            return false;
        } else if (!/^[JVEGPIRC][-][0-9]{8}[-][0-9]$/.test(temp)) { // Son 9 d�gitos?
            alert("Por Favor Verifique que el RIF tenga el siguiente formato: J-12345678-9 \nSi su Rif es menor a 9 digitos rellene con 0 a la izquierda Ej: J-00123456-7\n Su rif actual es: " + temp);
            return false;
        }

    }

    else {//////////////////////////////////////////////////////// es otro
        if (document.getElementById('rif').value == "") {//////////////arranca otro

            fun_msj('Ingrese el Rif de la Empresa');
            document.getElementById('rif').focus();
            return false;

        } else if (document.getElementById('razon').value == "") {

            fun_msj('Ingrese la Razon Social');
            document.getElementById('razon').focus();
            return false;

        } else if (document.getElementById('numero_empleados').value == "") {

            fun_msj('Ingrese el numero de Empleados');
            document.getElementById('numero_empleados').focus();
            return false;

        } else if (document.getElementById('select_ramo').value == "") {

            fun_msj('Seleccione el Ramo Comercial');
            document.getElementById('select_ramo').focus();
            return false;

        } else if (document.getElementById('direccion_comercial').value == "") {

            fun_msj('Ingrese la Direccion exacta de la sede');
            document.getElementById('direccion_comercial').focus();
            return false;

        } else if (document.getElementById('descripcion_objeto').value == "") {

            fun_msj('Ingrese la Descripcion Breve del Objeto');
            document.getElementById('descripcion_objeto').focus();
            return false;

        } else if (document.getElementById('select_1').value == "") {

            fun_msj('Seleccione El Estado Correspondiente');
            document.getElementById('select_1').focus();
            return false;

        } else if (document.getElementById('select_2').value == "") {

            fun_msj('seleccione El Municipio Correspondiente');
            document.getElementById('select_2').focus();
            return false;

        } else if (document.getElementById('codigo_area_empresa').value == "") {

            fun_msj('Ingrese el Codigo de area del objeto');
            document.getElementById('codigo_area_empresa').focus();
            return false;

        } else if (document.getElementById('telefonos_empresa').value == "") {

            fun_msj('Ingrese telefonos del objeto');
            document.getElementById('telefonos_empresa').focus();
            return false;

        } else if (document.getElementById('zona_postal_empresa').value == "") {

            fun_msj('Ingrese la zona postal de la empresa');
            document.getElementById('zona_postal_empresa').focus();
            return false;

        } else if ((document.getElementById('correo_empresa').value.search("@") == -1) || (document.getElementById('correo_empresa').value.search("[.*]") == -1)) {

            fun_msj("Por favor, revise el Email de la Empresa.");
            document.getElementById('correo_empresa').focus();
            return false;


        } else if (document.getElementById('equipos_disponibles').value == "") {

            fun_msj('Ingrese los Equipos y/o Materiales disponibles del objeto');
            document.getElementById('equipos_disponibles').focus();
            return false;

        } else if (document.getElementById('capacidad_financiera').value == "") {

            fun_msj('Ingrese la capacidad financiera del objeto');
            document.getElementById('capacidad_financiera').focus();
            return false;

        } else if (document.getElementById('registro_mercantil').value == "") {

            fun_msj('Ingrese el registro mercantil');
            document.getElementById('registro_mercantil').focus();
            return false;

        } else if (document.getElementById('socios').value == "") {

            fun_msj('Ingrese los socios del objeto');
            document.getElementById('socios').focus();
            return false;

        } else if (document.getElementById('nombre_representante').value == "") {

            fun_msj('Ingrese Nombre y Apellido del Representante Legal del objeto');
            document.getElementById('nombre_representante').focus();
            return false;

        } else if (document.getElementById('direccion_representante').value == "") {

            fun_msj('Ingrese la Direccion del Representante Legal');
            document.getElementById('direccion_representante').focus();
            return false;

        } else if (document.getElementById('cedula_ide').value == "") {

            fun_msj('Ingrese la c&eacute;dula de Identidad del Representante Legal del Objeto');
            document.getElementById('cedula_ide').focus();
            return false;

        } else if (document.getElementById('codigo_area_representante').value == "") {

            fun_msj('Ingrese el codigo de area del representante legal');
            document.getElementById('codigo_area_representante').focus();
            return false;

        } else if (document.getElementById('telefonos_fijos').value == "") {

            fun_msj('Ingrese los telefonos fijos del representante legal');
            document.getElementById('telefonos_fijos').focus();
            return false;

        } else if (document.getElementById('telefonos_moviles').value == "") {

            fun_msj('Ingrese los telefonos moviles del representante legal');
            document.getElementById('telefonos_moviles').focus();
            return false;

        } else if ((document.getElementById('correo_representante').value.search("@") == -1) || (document.getElementById('correo_representante').value.search("[.*]") == -1)) {

            fun_msj("Por favor, revise el Email del Representante.");
            document.getElementById('correo_representante').focus();
            return false;


        }
        else if (document.getElementById('numero_ocei').value == "") {

            fun_msj('Ingrese el numero de solvencia S.N.C');
            document.getElementById('numero_ocei').focus();
            return false;

        } else if (document.getElementById('fecha_ocei').value == "") {

            fun_msj('Ingrese la fecha de S.N.C');
            document.getElementById('fecha_ocei').focus();
            return false;

        } else if (document.getElementById('numero_laboral').value == "") {

            fun_msj('Ingrese el numero se la solvencia laboral');
            document.getElementById('numero_laboral').focus();
            return false;

        } else if (document.getElementById('fecha_laboral').value == "") {

            fun_msj('Ingrese la fecha laboral');
            document.getElementById('fecha_laboral').focus();
            return false;

        } else if (document.getElementById('numero_seguro').value == "") {

            fun_msj('Ingrese numero de solvecia del S.S.O');
            document.getElementById('numero_seguro').focus();
            return false;

        } else if (document.getElementById('fecha_seguro').value == "") {

            fun_msj('Ingrese la fecha del S.S.O');
            document.getElementById('fecha_seguro').focus();
            return false;

        } else if (document.getElementById('numero_ince').value == "") {

            fun_msj('Ingrese numero de solvecia del INCE');
            document.getElementById('numero_ince').focus();
            return false;

        } else if (document.getElementById('fecha_ince').value == "") {

            fun_msj('Ingrese la fecha del INCE');
            document.getElementById('fecha_ince').focus();
            return false;

        } else if (document.getElementById('numero_municipal').value == "") {

            fun_msj('Ingrese numero de solvecia municipal');
            document.getElementById('numero_municipal').focus();
            return false;

        } else if (document.getElementById('fecha_municipal').value == "") {

            fun_msj('Ingrese la fecha Municipal');
            document.getElementById('fecha_municipal').focus();
            return false;

        } else if (document.getElementById('observacion').value == "") {

            fun_msj('Ingrese las observaciones que desee hacer');
            document.getElementById('observacion').focus();
            return false;

        } else if (document.getElementById('fecha_inscripcion').value == "") {

            fun_msj('Ingrese la fecha de inscripcion');
            document.getElementById('fecha_inscripcion').focus();
            return false;

        } else if (verifica_cierre_ano_ejecucion('fecha_inscripcion') == false) {
            fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA DE INSCRIPCI&Oacute;N NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
            return false;
        } else if (document.getElementById('fecha_actualizacion').value == "") {

            fun_msj('Ingrese la fecha de actualizacion');
            document.getElementById('fecha_actualizacion').focus();
            return false;

        } else if (document.getElementById('fecha_inscrip_inicial_snc').value == "") {
            fun_msj('Ingrese la fecha de Inscripcion Inicial SNC');
            document.getElementById('fecha_inscrip_inicial_snc').focus();
            return false;

        } else if (document.getElementById('categoria_suministro').value == "") {
            fun_msj('Seleccione la Categoria del Suministro ');
            document.getElementById('categoria_suministro').focus();
            return false;

        } else if (document.getElementById('suministro_cliente_similar').value == "") {
            fun_msj('Seleccione Suministro Similar');
            document.getElementById('suministro_cliente_similar').focus();
            return false;
            
        }
        else if (document.getElementById('rif').value != "0") {
            var elRIF = document.getElementById('rif').value;
            var temp = elRIF.toUpperCase();
            if (!/^[JVEGPIRC]/.test(temp)) {
                alert("El primer caracter es incorrecto, debe ser una letra de las siguientes: J,V,E,G,P,I,R,C");
                return false;
            } else if (!/^[JVEGPIRC][-][0-9]{8}[-][0-9]$/.test(temp)) { // Son 9 d�gitos?
                alert("Por Favor Verifique que el RIF tenga el siguiente formato: J-12345678-9 \nSi su Rif es menor a 9 digitos rellene con 0 a la izquierda Ej: J-00123456-7\n Su rif actual es: " + temp);
                return false;
            }

        }

    }/////cierre otro


}///////////////cierra principal