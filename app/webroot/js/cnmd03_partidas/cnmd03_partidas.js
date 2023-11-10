function cnmp03_partidas() {
    if (document.getElementById('cod_denominacion').value == "") {

        fun_msj('Debe Seleccionar una Opci&oacute;n');
        document.getElementById('cod_denominacion').focus();
        return false;

    } else {

        var opcion = "";

        if (document.getElementById('empleados')) {
            if (document.getElementById('empleados').checked != false) {
                opcion = opcion + "empleados.";
                for (i = 1; i <= 4; i++) {
                    if (document.getElementById('empleados_' + i)) {
                        if (document.getElementById('empleados_' + i).value == "") {
                            fun_msj('Debe Seleccionar una Opci&oacute;n');
                            document.getElementById('empleados_' + i).focus();
                            return false;
                        }//fin if
                    }//fin if
                }//fin for
                if (!document.getElementById('empleados_4')) {
                    fun_msj('Debe Seleccionar una Opci&oacute;n');
                    return false;
                }//fin if
            }
        }//fin if



        if (document.getElementById('obreros')) {
            if (document.getElementById('obreros').checked != false) {
                opcion = opcion + "obreros.";
                for (i = 1; i <= 4; i++) {
                    if (document.getElementById('obreros_' + i)) {
                        if (document.getElementById('obreros_' + i).value == "") {
                            fun_msj('Debe Seleccionar una Opci&oacute;n');
                            document.getElementById('obreros_' + i).focus();
                            return false;
                        }//fin if
                    }//fin if
                }//fin for
                if (!document.getElementById('obreros_4')) {
                    fun_msj('Debe Seleccionar una Opci&oacute;n');
                    return false;
                }//fin if
            }
        }//fin if



        if (document.getElementById('militares_profesionales')) {
            if (document.getElementById('militares_profesionales').checked != false) {
                opcion = opcion + "militares_profesionales.";
                for (i = 1; i <= 4; i++) {
                    if (document.getElementById('militares_profesionales_' + i)) {
                        if (document.getElementById('militares_profesionales_' + i).value == "") {
                            fun_msj('Debe Seleccionar una Opci&oacute;n');
                            document.getElementById('militares_profesionales_' + i).focus();
                            return false;
                        }//fin if
                    }//fin if
                }//fin for
                if (!document.getElementById('militares_profesionales_4')) {
                    fun_msj('Debe Seleccionar una Opci&oacute;n');
                    return false;
                }//fin if
            }
        }//fin if



        if (document.getElementById('militares_no_profesionales')) {
            if (document.getElementById('militares_no_profesionales').checked != false) {
                opcion = opcion + "militares_no_profesionales.";
                for (i = 1; i <= 4; i++) {
                    if (document.getElementById('militares_no_profesionales_' + i)) {
                        if (document.getElementById('militares_no_profesionales_' + i).value == "") {
                            fun_msj('Debe Seleccionar una Opci&oacute;n');
                            document.getElementById('militares_no_profesionales_' + i).focus();
                            return false;
                        }//fin if
                    }//fin if
                }//fin for
                if (!document.getElementById('militares_no_profesionales_4')) {
                    fun_msj('Debe Seleccionar una Opci&oacute;n');
                    return false;
                }//fin if
            }
        }//fin if



        if (document.getElementById('contratados_empleados')) {
            if (document.getElementById('contratados_empleados').checked != false) {
                opcion = opcion + "contratados_empleados.";
                for (i = 1; i <= 4; i++) {
                    if (document.getElementById('contratados_empleados_' + i)) {
                        if (document.getElementById('contratados_empleados_' + i).value == "") {
                            fun_msj('Debe Seleccionar una Opci&oacute;n');
                            document.getElementById('contratados_empleados_' + i).focus();
                            return false;
                        }//fin if
                    }//fin if
                }//fin for
                if (!document.getElementById('contratados_empleados_4')) {
                    fun_msj('Debe Seleccionar una Opci&oacute;n');
                    return false;
                }//fin if
            }
        }//fin if



        if (document.getElementById('suplencias_empleados')) {
            if (document.getElementById('suplencias_empleados').checked != false) {
                opcion = opcion + "suplencias_empleados.";
                for (i = 1; i <= 4; i++) {
                    if (document.getElementById('suplencias_empleados_' + i)) {
                        if (document.getElementById('suplencias_empleados_' + i).value == "") {
                            fun_msj('Debe Seleccionar una Opci&oacute;n');
                            document.getElementById('suplencias_empleados_' + i).focus();
                            return false;
                        }//fin if
                    }//fin if
                }//fin for
                if (!document.getElementById('suplencias_empleados_4')) {
                    fun_msj('Debe Seleccionar una Opci&oacute;n');
                    return false;
                }//fin if
            }
        }//fin if



        if (document.getElementById('jubilados_empleados')) {
            if (document.getElementById('jubilados_empleados').checked != false) {
                opcion = opcion + "jubilados_empleados.";
                for (i = 1; i <= 4; i++) {
                    if (document.getElementById('jubilados_empleados_' + i)) {
                        if (document.getElementById('jubilados_empleados_' + i).value == "") {
                            fun_msj('Debe Seleccionar una Opci&oacute;n');
                            document.getElementById('jubilados_empleados_' + i).focus();
                            return false;
                        }//fin if
                    }//fin if
                }//fin for
                if (!document.getElementById('jubilados_empleados_4')) {
                    fun_msj('Debe Seleccionar una Opci&oacute;n');
                    return false;
                }//fin if
            }
        }//fin if



        if (document.getElementById('jubilados_obreros')) {
            if (document.getElementById('jubilados_obreros').checked != false) {
                opcion = opcion + "jubilados_obreros.";
                for (i = 1; i <= 4; i++) {
                    if (document.getElementById('jubilados_obreros_' + i)) {
                        if (document.getElementById('jubilados_obreros_' + i).value == "") {
                            fun_msj('Debe Seleccionar una Opci&oacute;n');
                            document.getElementById('jubilados_obreros_' + i).focus();
                            return false;
                        }//fin if
                    }//fin if
                }//fin for
                if (!document.getElementById('jubilados_obreros_4')) {
                    fun_msj('Debe Seleccionar una Opci&oacute;n');
                    return false;
                }//fin if
            }
        }//fin if



        if (document.getElementById('pensionados_empleados')) {
            if (document.getElementById('pensionados_empleados').checked != false) {
                opcion = opcion + "pensionados_empleado.";
                for (i = 1; i <= 4; i++) {
                    if (document.getElementById('pensionados_empleados_' + i)) {
                        if (document.getElementById('pensionados_empleados_' + i).value == "") {
                            fun_msj('Debe Seleccionar una Opci&oacute;n');
                            document.getElementById('pensionados_empleados_' + i).focus();
                            return false;
                        }//fin if
                    }//fin if
                }//fin for
                if (!document.getElementById('pensionados_empleados_4')) {
                    fun_msj('Debe Seleccionar una Opci&oacute;n');
                    return false;
                }//fin if
            }
        }//fin if



        if (document.getElementById('pensionados_obreros')) {
            if (document.getElementById('pensionados_obreros').checked != false) {
                opcion = opcion + "pensionados_obreros.";
                for (i = 1; i <= 4; i++) {
                    if (document.getElementById('pensionados_obreros_' + i)) {
                        if (document.getElementById('pensionados_obreros_' + i).value == "") {
                            fun_msj('Debe Seleccionar una Opci&oacute;n');
                            document.getElementById('pensionados_obreros_' + i).focus();
                            return false;
                        }//fin if
                    }//fin if
                }//fin for
                if (!document.getElementById('pensionados_obreros_4')) {
                    fun_msj('Debe Seleccionar una Opci&oacute;n');
                    return false;
                }//fin if
            }
        }//fin if



        if (document.getElementById('dietas')) {
            if (document.getElementById('dietas').checked != false) {
                opcion = opcion + "dietas.";
                for (i = 1; i <= 4; i++) {
                    if (document.getElementById('dietas_' + i)) {
                        if (document.getElementById('dietas_' + i).value == "") {
                            fun_msj('Debe Seleccionar una Opci&oacute;n');
                            document.getElementById('dietas_' + i).focus();
                            return false;
                        }//fin if
                    }//fin if
                }//fin for
                if (!document.getElementById('dietas_4')) {
                    fun_msj('Debe Seleccionar una Opci&oacute;n');
                    return false;
                }//fin if
            }
        }//fin if



        if (document.getElementById('comision_de_servicio')) {
            if (document.getElementById('comision_de_servicio').checked != false) {
                opcion = opcion + "comision_de_servicio.";
                for (i = 1; i <= 4; i++) {
                    if (document.getElementById('comision_de_servicio_' + i)) {
                        if (document.getElementById('comision_de_servicio_' + i).value == "") {
                            fun_msj('Debe Seleccionar una Opci&oacute;n');
                            document.getElementById('comision_de_servicio_' + i).focus();
                            return false;
                        }//fin if
                    }//fin if
                }//fin for
                if (!document.getElementById('comision_de_servicio_4')) {
                    fun_msj('Debe Seleccionar una Opci&oacute;n');
                    return false;
                }//fin if
            }
        }//fin if



        if (document.getElementById('becas')) {
            if (document.getElementById('becas').checked != false) {
                opcion = opcion + "becas.";
                for (i = 1; i <= 4; i++) {
                    if (document.getElementById('becas_' + i)) {
                        if (document.getElementById('becas_' + i).value == "") {
                            fun_msj('Debe Seleccionar una Opci&oacute;n');
                            document.getElementById('becas_' + i).focus();
                            return false;
                        }//fin if
                    }//fin if
                }//fin for
                if (!document.getElementById('becas_4')) {
                    fun_msj('Debe Seleccionar una Opci&oacute;n');
                    return false;
                }//fin if
            }
        }//fin if



        if (document.getElementById('ayudas')) {
            if (document.getElementById('ayudas').checked != false) {
                opcion = opcion + "ayudas.";
                for (i = 1; i <= 4; i++) {
                    if (document.getElementById('ayudas_' + i)) {
                        if (document.getElementById('ayudas_' + i).value == "") {
                            fun_msj('Debe Seleccionar una Opci&oacute;n');
                            document.getElementById('ayudas_' + i).focus();
                            return false;
                        }//fin if
                    }//fin if
                }//fin for
                if (!document.getElementById('ayudas_4')) {
                    fun_msj('Debe Seleccionar una Opci&oacute;n');
                    return false;
                }//fin if
            }
        }//fin if



        if (document.getElementById('suplencias_obreros')) {
            if (document.getElementById('suplencias_obreros').checked != false) {
                opcion = opcion + "suplencias_obreros.";
                for (i = 1; i <= 4; i++) {
                    if (document.getElementById('suplencias_obreros_' + i)) {
                        if (document.getElementById('suplencias_obreros_' + i).value == "") {
                            fun_msj('Debe Seleccionar una Opci&oacute;n');
                            document.getElementById('suplencias_obreros_' + i).focus();
                            return false;
                        }//fin if
                    }//fin if
                }//fin for
                if (!document.getElementById('suplencias_obreros_4')) {
                    fun_msj('Debe Seleccionar una Opci&oacute;n');
                    return false;
                }//fin if
            }
        }//fin if



        if (document.getElementById('contratados_obreros')) {
            if (document.getElementById('contratados_obreros').checked != false) {
                opcion = opcion + "contratados_obreros.";
                for (i = 1; i <= 4; i++) {
                    if (document.getElementById('contratados_obreros_' + i)) {
                        if (document.getElementById('contratados_obreros_' + i).value == "") {
                            fun_msj('Debe Seleccionar una Opci&oacute;n');
                            document.getElementById('contratados_obreros_' + i).focus();
                            return false;
                        }//fin if
                    }//fin if
                }//fin for
                if (!document.getElementById('contratados_obreros_4')) {
                    fun_msj('Debe Seleccionar una Opci&oacute;n');
                    return false;
                }//fin if
            }
        }//fin if



        if (document.getElementById('altos_funcionarios')) {
            if (document.getElementById('altos_funcionarios').checked != false) {
                opcion = opcion + "altos_funcionarios.";
                for (i = 1; i <= 4; i++) {
                    if (document.getElementById('altos_funcionarios_' + i)) {
                        if (document.getElementById('altos_funcionarios_' + i).value == "") {
                            fun_msj('Debe Seleccionar una Opci&oacute;n');
                            document.getElementById('altos_funcionarios_' + i).focus();
                            return false;
                        }//fin if
                    }//fin if
                }//fin for
                if (!document.getElementById('altos_funcionarios_4')) {
                    fun_msj('Debe Seleccionar una Opci&oacute;n');
                    return false;
                }//fin if
            }
        }//fin if



        if (document.getElementById('eleccion_popular')) {
            if (document.getElementById('eleccion_popular').checked != false) {
                opcion = opcion + "eleccion_popular.";
                for (i = 1; i <= 4; i++) {
                    if (document.getElementById('eleccion_popularr_' + i)) {
                        if (document.getElementById('eleccion_popular_' + i).value == "") {
                            fun_msj('Debe Seleccionar una Opci&oacute;n');
                            document.getElementById('eleccion_popular_' + i).focus();
                            return false;
                        }//fin if
                    }//fin if
                }//fin for
                if (!document.getElementById('eleccion_popular_4')) {
                    fun_msj('Debe Seleccionar una Opci&oacute;n');
                    return false;
                }//fin if
            }
        }//fin if





        if (opcion != "") {

            fun_msj2('Fue Insertada La Partida Presupuestaria Seg&uacute;n las Transacciones');

        } else {

            fun_msj('Debe Seleccionar una Opci&oacute;n');
            return false;

        }






    }//fin else

}//fin funcion





function mensajes_cnmp03_partidas_eliminar() {
    fun_msj('Fue Eliminada La Partida Presupuestaria Seg�n las Transacciones');
}






function comprueba_cnmp03partidas_codigo() {

    if (document.getElementById('codigo').value != '0') {


        var1 = document.getElementById('codigo').value;

        paramdef = "cod_transaccion=" + var1;
        paramdef = paramdef + "& enviar=si";

        if (document.getElementById('codigo').value != "") {
            pag = '/sisap/include/cnmp03partidas/verifica_codigo.php?' + paramdef;
            cargar_contenido('valida_codigo', pag);
        } else {
            document.getElementById('valida_codigo').innerHTML = "";
        }

    }//fin if

}



function cnmp03partidas_selecion(id) {


    if (document.getElementById(id).checked == true) {


        document.getElementById('st_partida_' + id).style.display = "block";
        document.getElementById('st_generica_' + id).style.display = "block";
        document.getElementById('st_especifica_' + id).style.display = "block";
        document.getElementById('st_sub_especifica_' + id).style.display = "block";
        document.getElementById('st_auxiliar_' + id).style.display = "block";
        document.getElementById('principal_' + id).style.display = "block";


    } else {

        document.getElementById('st_partida_' + id).style.display = "none";
        document.getElementById('st_generica_' + id).style.display = "none";
        document.getElementById('st_especifica_' + id).style.display = "none";
        document.getElementById('st_sub_especifica_' + id).style.display = "none";
        document.getElementById('st_auxiliar_' + id).style.display = "none";
        document.getElementById('principal_' + id).style.display = "none";

    }//fin else



}
























