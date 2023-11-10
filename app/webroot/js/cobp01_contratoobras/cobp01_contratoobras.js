function cobp01_contratoobras_calcula_total() {
    total = 0;
    monto_iva_var = 0;
    for (ii = 0; ii < document.getElementById('cuenta_i').value; ii++) {
        a = document.getElementById('monto_' + ii).value;
        var str = a;
        for (i = 0; i < a.length; i++) {
            str = str.replace('.', '');
        }//fin for
        str = str.replace(',', '.');
        var a = redondear(str, 2);
        total = eval(total) + eval(a);
    }//fin

//////////////////////////////////////////////////////////////
    var str = total + '';
    for (x = 0; x < str.length; x++) {
        if (str.charAt(x) == ".") {
            total = str.substr(0, eval(x) + eval(6));
            break;
        }//fin if
    }//fin for
    var total = redondear(total, 2);
//////////////////////////////////////////////////////////////


    estimado_presu = document.getElementById('estimado_presu').value;


    if (estimado_presu < total) {
        fun_msj('El monto es mayor al presupuesto estimado');
    }//fin if

    cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total);


}//fin function









function cobp01_contratoobras_valida() {

    if (verifica_cierre_ano_ejecucion_msj() == false) {
        return false;
    } else {

        fecha_a = new Array();
        fecha_b = new Array();
        fecha_c = new Array();

        var checkStr = document.getElementById('fecha_inicio').value;
        var fecha_a = checkStr.split("/");

        var checkStr = document.getElementById('fecha_terminacion').value;
        var fecha_b = checkStr.split("/");

        var checkStr = document.getElementById('fecha_contrato').value;
        var fecha_c = checkStr.split("/");

        aaa = eval(fecha_a[0]) + eval(fecha_a[1]) + eval(fecha_a[2]);
        bbb = eval(fecha_b[0]) + eval(fecha_b[1]) + eval(fecha_b[2]);
        ccc = eval(fecha_c[0]) + eval(fecha_c[1]) + eval(fecha_c[2]);




        monto_partidas_sin_iva = 0;
        monto_partidas_iva = 0;

        for (ii = 0; ii < document.getElementById('cuenta_i').value; ii++) {
            if (!document.getElementById('partida_iva_' + ii)) {
                if (document.getElementById('partida_op_' + ii).value == '1') {
                    a = document.getElementById('monto_' + ii).value;
                    str = a;
                    acepto = "no";
                    for (i = 0; i < a.length; i++) {
                        if (str.charAt(i) == ",") {
                            acepto = "si";
                        }
                    }
                    if (acepto == "si") {
                        for (i = 0; i < a.length; i++) {
                            str = str.replace('.', '');
                        }
                        str = str.replace(',', '.');
                    }
                    var a = redondear(str, 2);
                    monto_partidas_sin_iva = eval(monto_partidas_sin_iva) + eval(a);
                }
            }
        }


        for (ii = 0; ii < document.getElementById('cuenta_i').value; ii++) {
            if (document.getElementById('partida_iva_' + ii)) {
                if (document.getElementById('partida_op_' + ii).value == '1') {
                    a = document.getElementById('monto_' + ii).value;
                    str = a;
                    acepto = "no";
                    for (i = 0; i < a.length; i++) {
                        if (str.charAt(i) == ",") {
                            acepto = "si";
                        }
                    }
                    if (acepto == "si") {
                        for (i = 0; i < a.length; i++) {
                            str = str.replace('.', '');
                        }
                        str = str.replace(',', '.');
                    }
                    var a = redondear(str, 2);
                    monto_partidas_iva = eval(monto_partidas_iva) + eval(a);
                }
            }
        }

        porcentaje_iva_input = retornar_valor_calculo(document.getElementById("porcentaje_iva_parametro").value);
        monto_excento = retornar_valor_calculo(document.getElementById("saldo_excento").value);
        porcentaje_iva_input = eval(porcentaje_iva_input) / eval(100);
        iva_monto = redondear(((eval(monto_partidas_sin_iva) - eval(monto_excento) ) * eval(porcentaje_iva_input)), 2);

        monto_partidas_iva = redondear(monto_partidas_iva, 2);
        monto_partidas_sin_iva = redondear(monto_partidas_sin_iva, 2);




        if (eval(monto_partidas_iva) == 0) {

            iva_monto = 0;

        } else {

            aux_resta_iva = redondear(eval(monto_partidas_iva) - eval(iva_monto), 2);



            if (eval(monto_partidas_iva) > eval(iva_monto)) {


                if (eval(aux_resta_iva) == eval(0.01) || eval(aux_resta_iva) == eval(-0.01)) {

                    iva_monto = monto_partidas_iva;

                }//fin if


            } else if (eval(monto_partidas_iva) < eval(iva_monto)) {



                if (eval(aux_resta_iva) == eval(0.01) || eval(aux_resta_iva) == eval(-0.01)) {

                    iva_monto = monto_partidas_iva;



                }//fin if



            }//fin else if

        }//fin else




        if (document.getElementById('ano').value == '') {

            fun_msj('Inserte el a&ntilde;o del contrato');
            document.getElementById('ano').focus();
            return false;

        } else if (document.getElementById('numero_contrato').value == '') {

            fun_msj('Inserte el n&uacute;mero del contrato');
            document.getElementById('numero_contrato').focus();
            return false;

        } else if (document.getElementById('ano_obra').value == '') {

            fun_msj('Inserte el a&ntilde;o de la obra');
            document.getElementById('ano_obra').focus();
            return false;

        } else if (document.getElementById('input_cod_obra').value == '') {

            fun_msj('Inserte el c&oacute;digo de la obra');
            document.getElementById('input_cod_obra').focus();
            return false;

        } else if (document.getElementById('denominacion_obra').value == '') {

            fun_msj('Inserte la denominaci&oacute;n de la obra');
            document.getElementById('denominacion_obra').focus();
            return false;

        } else {

            opcion = 0;
            for (ii = 0; ii < document.getElementById('cuenta_i').value; ii++) {
                if (document.getElementById('monto_' + ii).value == "") {
                    opcion = 1;
                }
            }//fin for
            total = 0;
            monto_iva_var = 0;

            for (ii = 0; ii < document.getElementById('cuenta_i').value; ii++) {
                a = document.getElementById('monto_' + ii).value;
                var str = a;
                for (i = 0; i < a.length; i++) {
                    str = str.replace('.', '');
                }//fin for
                str = str.replace(',', '.');
                var a = redondear(str, 2);
                total = eval(total) + eval(a);
            }//fin


//////////////////////////////////////////////////////////////
            var str = total + '';
            for (x = 0; x < str.length; x++) {
                if (str.charAt(x) == ".") {
                    total = str.substr(0, eval(x) + eval(6));
                    break;
                }//fin if
            }//fin for
            var total = redondear(total, 2);
//////////////////////////////////////////////////////////////


            estimado_presu = document.getElementById('estimado_presu').value;

            if (estimado_presu < total) {
                fun_msj('El monto es mayor al presupuesto estimado');
                return false;

            } else if (opcion == 1) {

                fun_msj('Inserte el monto en la partida presupuestaria');
                return false;


            } else if (document.getElementById('TOTALINGRESOS').innerHTML == "0,00") {

                fun_msj('inserte monto a las partidas');
                return false;

            } else if (eval(monto_partidas_iva) != eval(iva_monto)) {

                //fun_msj('MONTO DEL I.V.A NO CUADRA, MONTO CORRECTO ');
                mensaje_con_monto_iva_contrato_obra(iva_monto);
                return false;

            } else if (document.getElementById('tipo_otorgamiento_1').checked != false || document.getElementById('tipo_otorgamiento_2').checked != false || document.getElementById('tipo_otorgamiento_3').checked != false || document.getElementById('tipo_otorgamiento_4').checked != false || document.getElementById('tipo_otorgamiento_5').checked != false) {


                if (document.getElementById('rif_numero').value == '') {

                    fun_msj('Inserte el rif de la constructora');
                    document.getElementById('rif_numero').focus();
                    return false;

                } else if (document.getElementById('rif_nombre').value == '') {

                    fun_msj('Inserte la denominaci&oacute;n de la constructora');
                    document.getElementById('rif_nombre').focus();
                    return false;

                    //  }else if(document.getElementById('numero_anticipo').value==''){

                    //          fun_msj('Inserte el n&uacute;mero de anticipo de fianza');
                    //        document.getElementById('numero_anticipo').focus();
                    //       return false;


                    //     }else if(document.getElementById('fecha_anticipo').value==''){

                    //            fun_msj('Inserte fecha del anticipo de fianza');
                    //          document.getElementById('fecha_anticipo').focus();
                    //         return false;


                } else if (document.getElementById('x_2').value == '') {

                    fun_msj('Inserte el Estado');
                    document.getElementById('x_2').focus();
                    return false;

                } else if (document.getElementById('ubicacion_detallada_obra').value == '') {

                    fun_msj('Inserte la ubicaci&oacute;n detallada de la obra');
                    document.getElementById('ubicacion_detallada_obra').focus();
                    return false;


                } else if (document.getElementById('fecha_contrato').value == '') {

                    fun_msj('Inserte la fecha del contrato');
                    document.getElementById('fecha_contrato').focus();
                    return false;

                } else if (valida_fechas_documentos_mayores(3) == 2) {

                    return false;


                } else if (document.getElementById('fecha_inicio').value == '') {

                    fun_msj('Inserte la fecha de inicio');
                    document.getElementById('fecha_inicio').focus();
                    return false;


                } else if (document.getElementById('fecha_terminacion').value == '') {

                    fun_msj('Inserte la fecha de terminaci&oacute;n');
                    document.getElementById('fecha_terminacion').focus();
                    return false;


                } else if (diferenciaFecha(document.getElementById('fecha_inicio').value, document.getElementById('fecha_contrato').value)) {


                    if (confirm("la Fecha del contrato no debe ser mayor a la fecha de inicio")) {
                        if (diferenciaFecha(document.getElementById('fecha_terminacion').value, document.getElementById('fecha_inicio').value)) {
                            fun_msj('la Fecha de terminaci&oacute;n debe ser mayor a la de inicio');
                            return false;
                        }//fin else
                    } else {
                        return false;
                    }//fin elswe


                } else if (diferenciaFecha(document.getElementById('fecha_terminacion').value, document.getElementById('fecha_inicio').value)) {

                    fun_msj('la Fecha de terminaci&oacute;n debe ser mayor a la de inicio');
                    return false;


                }//fin else


            } else {
                fun_msj('Inserte el tipo de otorgamiento');
                return false;
            }//fin else

        }//fin else



        if (diferenciaFecha(document.getElementById('fecha_contrato').value, document.getElementById('fecha_comparar').value)) {

            if (document.getElementById('fecha_actual').value != "") {
                return verifica_cierre_mes_ejecucion('fecha_actual');
            }//fin if

        } else {

            if (document.getElementById('fecha_contrato').value != "") {
                return verifica_cierre_mes_ejecucion('fecha_contrato');
            }//fin if

        }// fin else



    }// FIN ELSE VERIFICANDO ANO EJEC <> ANO SERVER.




}//fin function









function mensaje_con_monto_iva_contrato_obra(monto) {

    pag = "../../include/cfpp05/moneda.php?monto=" + monto;

    var myConn = new XHConn();
    if (!myConn)
        fun_msj("XMLHTTP no esta disponible. Intentalo con un navegador mas nuevo.");
    var peticion = function (oXML) {
        fun_msj('El monto del i.v.a no cuadra  ..Monto correcto.. ' + oXML.responseText);
    };
    myConn.connect(pag, "GET", "", peticion);
    ;


}





function cobp01_contratoobras_valida_consulta() {


    if (document.getElementById('concepto_anulacion').value == "") {

        fun_msj('Inserte el concepto de anulacion');
        document.getElementById('concepto_anulacion').focus();
        return false;

    }//fin if


}//fin funciton









function validar_concepto_anulacion_obras() {
    if (verifica_cierre_ano_ejecucion_msj() == false) {
        return false;
    } else if (document.getElementById('concepto_anulacion').value == "") {
        fun_msj('Ingrese el concepto de la anulaci&oacute;n');
        setTimeout("fondoCampo('concepto_anulacion',2);", 3500);
        document.getElementById('concepto_anulacion').focus();
        return false;
    } else if (!confirm("Esta Seguro que desea anular este registro")) {
        return false;
    }
}//fin





