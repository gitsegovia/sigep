function cnmp15_tasa_interes_valida() {


    if (document.getElementById('ano').value == "") {

        fun_msj('Inserte el a&ntilde;o ');
        document.getElementById('ano').focus();
        return false;

    } else if (document.getElementById('ene').value == "") {

        fun_msj('Inserte el Porcentaje de Enero');
        document.getElementById('ene').focus();
        return false;

    } else if (document.getElementById('feb').value == "") {

        fun_msj('Inserte el Porcentaje de Febrero');
        document.getElementById('feb').focus();
        return false;

    } else if (document.getElementById('mar').value == "") {

        fun_msj('Inserte el Porcentaje de Marzo');
        document.getElementById('mar').focus();
        return false;

    } else if (document.getElementById('abr').value == "") {

        fun_msj('Inserte el Porcentaje de Abril');
        document.getElementById('abr').focus();
        return false;

    } else if (document.getElementById('may').value == "") {

        fun_msj('Inserte el Porcentaje de Mayo');
        document.getElementById('may').focus();
        return false;

    } else if (document.getElementById('jun').value == "") {

        fun_msj('Inserte el Porcentaje de Junio');
        document.getElementById('jun').focus();
        return false;

    } else if (document.getElementById('jul').value == "") {

        fun_msj('Inserte el Porcentaje de Julio');
        document.getElementById('jul').focus();
        return false;

    } else if (document.getElementById('ago').value == "") {

        fun_msj('Inserte el Porcentaje de Agosto');
        document.getElementById('ago').focus();
        return false;

    } else if (document.getElementById('sep').value == "") {

        fun_msj('Inserte el Porcentaje de Septiembre');
        document.getElementById('sep').focus();
        return false;

    } else if (document.getElementById('oct').value == "") {

        fun_msj('Inserte el Porcentaje de Octubre');
        document.getElementById('oct').focus();
        return false;

    } else if (document.getElementById('nov').value == "") {

        fun_msj('Inserte el Porcentaje de Noviembre');
        document.getElementById('nov').focus();
        return false;


    } else if (document.getElementById('dic').value == "") {

        fun_msj('Inserte el Porcentaje de Diciembre');
        document.getElementById('dic').focus();
        return false;


    }//fin else



}//fin function


function cscd01_catalogo_inflacion_valida() {
    if (document.getElementById('ano').value == "") {
        fun_msj('Inserte el a&ntilde;o ');
        document.getElementById('ano').focus();
        return false;
    } else if (document.getElementById('ene').value == "") {

        fun_msj('Inserte el Porcentaje de Enero');
        document.getElementById('ene').focus();
        return false;

    } else if (document.getElementById('feb').value == "") {

        fun_msj('Inserte el Porcentaje de Febrero');
        document.getElementById('feb').focus();
        return false;

    } else if (document.getElementById('mar').value == "") {

        fun_msj('Inserte el Porcentaje de Marzo');
        document.getElementById('mar').focus();
        return false;

    } else if (document.getElementById('abr').value == "") {

        fun_msj('Inserte el Porcentaje de Abril');
        document.getElementById('abr').focus();
        return false;

    } else if (document.getElementById('may').value == "") {

        fun_msj('Inserte el Porcentaje de Mayo');
        document.getElementById('may').focus();
        return false;

    } else if (document.getElementById('jun').value == "") {

        fun_msj('Inserte el Porcentaje de Junio');
        document.getElementById('jun').focus();
        return false;

    } else if (document.getElementById('jul').value == "") {

        fun_msj('Inserte el Porcentaje de Julio');
        document.getElementById('jul').focus();
        return false;

    } else if (document.getElementById('ago').value == "") {

        fun_msj('Inserte el Porcentaje de Agosto');
        document.getElementById('ago').focus();
        return false;

    } else if (document.getElementById('sep').value == "") {

        fun_msj('Inserte el Porcentaje de Septiembre');
        document.getElementById('sep').focus();
        return false;

    } else if (document.getElementById('oct').value == "") {

        fun_msj('Inserte el Porcentaje de Octubre');
        document.getElementById('oct').focus();
        return false;

    } else if (document.getElementById('nov').value == "") {

        fun_msj('Inserte el Porcentaje de Noviembre');
        document.getElementById('nov').focus();
        return false;


    } else if (document.getElementById('dic').value == "") {

        fun_msj('Inserte el Porcentaje de Diciembre');
        document.getElementById('dic').focus();
        return false;
    }//fin else



}//fin function


function total_inflacion(){
    
    ene= document.getElementById('ene').value;
    feb= document.getElementById('feb').value;
    mar= document.getElementById('mar').value;
    abr= document.getElementById('abr').value;
    may= document.getElementById('may').value;
    jun= document.getElementById('jun').value;
    jul= document.getElementById('jul').value;
    ago= document.getElementById('ago').value;
    sep= document.getElementById('sep').value;
    oct= document.getElementById('oct').value;
    nov= document.getElementById('nov').value;
    dic= document.getElementById('dic').value;

    ene= ene.toString().replace(",",".");
    feb= feb.toString().replace(",",".");
    mar= mar.toString().replace(",",".");
    abr= abr.toString().replace(",",".");
    may= may.toString().replace(",",".");
    jun= jun.toString().replace(",",".");
    jul= jul.toString().replace(",",".");
    ago= ago.toString().replace(",",".");
    sep= sep.toString().replace(",",".");
    oct= oct.toString().replace(",",".");
    nov= nov.toString().replace(",",".");
    dic= dic.toString().replace(",",".");
    
    ene =parseFloat(ene);    
    feb =parseFloat(feb);    
    mar =parseFloat(mar);    
    abr =parseFloat(abr);    
    may =parseFloat(may);    
    jun =parseFloat(jun);    
    jul =parseFloat(jul);    
    ago =parseFloat(ago);    
    sep =parseFloat(sep);    
    oct =parseFloat(oct);    
    nov =parseFloat(nov);    
    dic =parseFloat(dic);    
    
    total=parseFloat(eval(ene+feb+mar+abr+may+jun+jul+ago+sep+oct+nov+dic));
    total=total.toFixed(2);
    total= total.toString().replace(".",",");
    document.getElementById('inflacion_acumulada').value=total;
    
}