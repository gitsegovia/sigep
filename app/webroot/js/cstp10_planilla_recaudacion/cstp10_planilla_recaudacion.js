function valida_cstp10_planilla_liquidacion() {
  if (
    document.getElementById("tipo_acto_administrativo_1").checked == false &&
    document.getElementById("tipo_acto_administrativo_2").checked == false &&
    document.getElementById("tipo_acto_administrativo_3").checked == false &&
    document.getElementById("tipo_acto_administrativo_4").checked == false
  ) {
    fun_msj("Debe seleccionar el tipo de acto administrativo");
    return false;
  }

  if (document.getElementById("tipo_acto_administrativo_1").checked == false) {
    if (document.getElementById("entidad").value == "") {
      fun_msj("Debe indicar una entidad");
      return false;
    }

    if (document.getElementById("numero_decreto_acto").value == "" && document.getElementById("motivado").value == "") {
      fun_msj("Debe indicar el numero del acto administrativo ó indicar un motivo");
      return false;
    }

    if (document.getElementById("fecha_acto").value == "" && document.getElementById("motivado").value == "") {
      fun_msj("Debe indicar la fecha del acto administrativo ó indicar un motivo");
      return false;
    }

    if (document.getElementById("monto_acto").value == "" || document.getElementById("monto_acto").value == "0,00") {
      fun_msj("Debe ingresar el monto de la planilla");
      return false;
    }
  } else {
    if (document.getElementById("ciudadano").value == "") {
      fun_msj("Debe registrar el nombre del ciudadano");
      return false;
    }

    if (document.getElementById("cedula_identidad").value == "") {
      fun_msj("Debe registrar el numero de cedula");
      return false;
    }

    if (document.getElementById("numero_decreto_multa").value == "") {
      fun_msj("Debe indicar el numero del acto administrativo");
      return false;
    }

    if (document.getElementById("fecha_acto_multa").value == "") {
      fun_msj("Debe indicar la fecha del acto administrativo");
      return false;
    }

    if (document.getElementById("monto_multa").value == "") {
      fun_msj("Debe ingresar el monto de la planilla");
      return false;
    }
  }

  if (document.getElementById("concepto").value == "") {
    fun_msj("Debe registrar el concepto de la planilla");
    return false;
  }

  if (document.getElementById("tipo_ramo").value == "") {
    fun_msj("Debe seleccionar el tipo de ingreso");
    return false;
  }

  if (document.getElementById("partida").value == "") {
    fun_msj("Debe seleccionar el tipo de ingreso");
    return false;
  }

  if (document.getElementById("tipo_ramo").value == "7" && document.getElementById("credito_adicional_1").checked == true && document.getElementById("tipo_credito").value == "") {
    fun_msj("Debe seleccionar la categoria del credito adicional");
    return false;
  }

  return true;
} //fin function

function activar_datos_actos() {
  if (document.getElementById("tipo_acto_administrativo_1").checked == true) {
    var result_label_style = document.getElementById("tr_label_datos_actos").style;
    result_label_style.display = "none";
    var result_input_style = document.getElementById("tr_input_datos_actos").style;
    result_input_style.display = "none";
    var result_label_style = document.getElementById("tr_label_datos_multa").style;
    result_label_style.display = "table-row";
    var result_input_style = document.getElementById("tr_input_datos_multa").style;
    result_input_style.display = "table-row";
  } else {
    var result_label_style = document.getElementById("tr_label_datos_multa").style;
    result_label_style.display = "none";
    var result_input_style = document.getElementById("tr_input_datos_multa").style;
    result_input_style.display = "none";
    var result_label_style = document.getElementById("tr_label_datos_actos").style;
    result_label_style.display = "table-row";
    var result_input_style = document.getElementById("tr_input_datos_actos").style;
    result_input_style.display = "table-row";
  }
}

function activar_datos_creditos() {
  if (document.getElementById("credito_adicional_1").checked == true) {
    var result_label_style = document.getElementById("td_tipo_credito").style;
    result_label_style.display = "table-cell";
    var result_input_style = document.getElementById("td_tipo_credito_2").style;
    result_input_style.display = "table-cell";
  } else {
    var result_label_style = document.getElementById("td_tipo_credito").style;
    result_label_style.display = "none";
    var result_input_style = document.getElementById("td_tipo_credito_2").style;
    result_input_style.display = "none";
  }
}

function mostrar_partida() {
  var tipo_ramo = document.getElementById("tipo_ramo").value;
  var partida = "";
  var result_label_style = document.getElementById("tr_input_tipo_credito").style;
  result_label_style.display = "none";
  var result_labelExcedente_style = document.getElementById("tr_input_excedente").style;
  result_labelExcedente_style.display = "none";
  switch (tipo_ramo) {
    case "1":
    case "2":
      partida = "301.03.33.00";
      break;
    case "3":
    case "4":
      partida = "301.10.04.00";
      break;
    case "5":
      partida = "301.99.01.00";
      break;
    case "6":
      partida = "302.03.05.01";
      break;
    case "7":
      partida = "302.99.01.00";
      break;
    case "8":
      partida = "305.03.01.01";
      break;
    case "9":
      partida = "305.08.01.00";
      break;
  }
  switch (tipo_ramo) {
    case "1":
    case "2":
    case "4":
      result_labelExcedente_style.display = "table-row";
      break;
    case "7":
      result_label_style.display = "table-row";
      break;
  }

  document.getElementById("partida").value = partida;
}

function valida_cstp10_planilla_recaudacion() {
  if (document.getElementById("numero_planilla_liquidacion").value == "") {
    fun_msj("Debe indicar el numero de la planilla de liquidación");
    return false;
  }

  if (document.getElementById("monto").value == "" || document.getElementById("monto").value == "0,00") {
    fun_msj("Debe indicar el monto de la planilla de recaudación");
    return false;
  }

  var monto_por_recaudar = document.getElementById("monto_por_recaudar").value;
  var monto = document.getElementById("monto").value;

  /////////////////////////////////////////
  var str = monto_por_recaudar;
  for (i = 0; i < monto_por_recaudar.length; i++) {
    str = str.replace(".", "");
  } //fin for
  str = str.replace(",", ".");
  var monto_por_recaudar = str;
  /////////////////////////////////////////

  /////////////////////////////////////////
  var str = monto;
  for (i = 0; i < monto.length; i++) {
    str = str.replace(".", "");
  } //fin for
  str = str.replace(",", ".");
  var monto = str;
  /////////////////////////////////////////

  if (eval(monto) > eval(monto_por_recaudar)) {
    fun_msj("El monto de la recaudación no debe ser mayor al de la liquidación");
    return false;
  }

  if (document.getElementById("concepto").value == "") {
    fun_msj("Debe indicar el concepto de la planilla de recaudación");
    return false;
  }

  if (document.getElementById("cuenta_bancaria").value == "") {
    fun_msj("Debe indicar el numero de cuenta bancaria");
    return false;
  }

  if (document.getElementById("numero_deposito").value == "") {
    fun_msj("Debe indicar el numero del deposito");
    return false;
  }

  if (document.getElementById("fecha_deposito").value == "") {
    fun_msj("Debe indicar la fecha del deposito");
    return false;
  }

  return true;
} //fin function

function valida_cstp10_recaudacion_banco() {
  if (document.getElementById("cuenta_bancaria").value == "") {
    fun_msj("Debe indicar el numero de cuenta bancaria");
    return false;
  }

  if (document.getElementById("numero_deposito").value == "") {
    fun_msj("Debe indicar el numero del deposito");
    return false;
  }

  if (document.getElementById("fecha_deposito").value == "") {
    fun_msj("Debe indicar la fecha del deposito");
    return false;
  }

  return true;
}

function generar_planilla_pdf() {
  if (document.getElementById("ano_planilla").value == "") {
    fun_msj("Debe ingresar el ano de la planilla");
    return false;
  }

  if (document.getElementById("numero_planilla").value == "") {
    fun_msj("Debe seleccionar una planilla");
    return false;
  }

  return true;
}

function valida_cstp10_planilla_firmas() {
  if (document.getElementById("nombre_primera_firma").value == "") {
    fun_msj("Debe ingresar nombre del primer firmante");
    return false;
  }
  if (document.getElementById("nombre_segunda_firma").value == "") {
    fun_msj("Debe ingresar nombre del segundo firmante");
    return false;
  }
  if (document.getElementById("cedula_primera_firma").value == "") {
    fun_msj("Debe ingresar cedula del primer firmante");
    return false;
  }
  if (document.getElementById("cedula_segunda_firma").value == "") {
    fun_msj("Debe ingresar cedula del segundo firmante");
    return false;
  }
  if (document.getElementById("cargo_primera_firma").value == "") {
    fun_msj("Debe ingresar cargo del primer firmante");
    return false;
  }
  if (document.getElementById("cargo_segunda_firma").value == "") {
    fun_msj("Debe ingresar cargo del segundo firmante");
    return false;
  }
  if (document.getElementById("decreto_primera_firma").value == "") {
    fun_msj("Debe ingresar decreto del primer firmante");
    return false;
  }
  if (document.getElementById("decreto_segunda_firma").value == "") {
    fun_msj("Debe ingresar decreto del segundo firmante");
    return false;
  }
  return true;
}

function cstp10_planilla_ver_planilla_liquidacion() {
  if (document.getElementById("numero_planilla").value == "") {
    fun_msj("Debe seleccionar una planilla");
    return false;
  }
  return true;
}

function actualiza_cstp10_planilla_liquidacion() {
  if (document.getElementById("concepto").value == "") {
    fun_msj("Debe registrar el concepto de la planilla");
    return false;
  }
  return true;
} //fin function
