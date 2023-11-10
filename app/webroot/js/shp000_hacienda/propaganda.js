

function tipo_propaganda(){


          if(document.getElementById('rif_constribuyente').value==''){

			fun_msj('Ingrese el contribuyente');
			document.getElementById('rif_constribuyente').focus();
			return false;

	}else if(document.getElementById('denominacion').value==''){

			fun_msj('Ingrese la denominaci&oacute;n');
			document.getElementById('denominacion').focus();
			return false;

    }else if($('unidad').checked==false && $('metros').checked==false){

			fun_msj('Ingrese el tipo de unidad');
			return false;

    }else if(document.getElementById('articulo').value==''){

			fun_msj('Ingrese articulo');
			document.getElementById('articulo').focus();
			return false;

    }else if(document.getElementById('monto').value==''){

			fun_msj('Ingrese el monto');
			document.getElementById('monto').focus();
			return false;

    }


}//fin function















function tipo_propaganda_agregar(){


          if(document.getElementById('denominacion').value==''){

			fun_msj('Ingrese la denominaci&oacute;n');
			document.getElementById('denominacion').focus();
			return false;

    }else if($('unidad').checked==false && $('metros').checked==false){

			fun_msj('Ingrese el tipo de unidad');
			return false;

    }else if(document.getElementById('articulo').value==''){

			fun_msj('Ingrese articulo');
			document.getElementById('articulo').focus();
			return false;

    }else if(document.getElementById('monto').value==''){

			fun_msj('Ingrese el monto');
			document.getElementById('monto').focus();
			return false;

    }


}//fin function













function funcion_valida_propaganda_2(){


if(document.getElementById('rif_constribuyente').value==''){

			fun_msj('Ingrese el contribuyente');
			document.getElementById('rif_constribuyente').focus();
			return false;

}else{

				       if($("tipo_unidad").value=="2"){

							          if(document.getElementById('largo').value==''){

										fun_msj('Ingrese el largo de la publicidad');
										document.getElementById('largo').focus();
										return false;

								}else if(document.getElementById('alto').value==''){

										fun_msj('Ingrese el alto de la publicidad');
										document.getElementById('alto').focus();
										return false;

							    }else if(document.getElementById('fecha_registro').value==''){

										fun_msj('Ingrese la fecha de la publicidad');
										document.getElementById('fecha_registro').focus();
										return false;

							    }else if(document.getElementById('ubicacion').value==''){

										fun_msj('Ingrese la ubicaci&oacute;n');
										document.getElementById('ubicacion').focus();
										return false;
								}

				}else if($("tipo_unidad").value=="1"){

							          if(document.getElementById('cantidad_area2').value==''){

										fun_msj('Ingrese la cantidad');
										document.getElementById('cantidad_area2').focus();
										return false;

							    }else if(document.getElementById('fecha_registro').value==''){

										fun_msj('Ingrese la fecha de la publicidad');
										document.getElementById('fecha_registro').focus();
										return false;

							    }else if(document.getElementById('ubicacion').value==''){

										fun_msj('Ingrese la ubicaci&oacute;n');
										document.getElementById('ubicacion').focus();
										return false;
								}

				}//fin else if



}//fin else



}// fin function








function validar_propaganda(){



         if(document.getElementById('rif_constribuyente').value==''){

			fun_msj('Ingrese el contribuyente');
			document.getElementById('rif_constribuyente').focus();
			return false;

	}else  if(document.getElementById('monto_mensual_general').value==''){

			fun_msj('Ingrese en monto mensual general');
			document.getElementById('monto_mensual_general').focus();
			return false;

    }else if(document.getElementById('rif_ci_cobrador').value==''){

			fun_msj('Ingrese el cobrador');
			document.getElementById('rif_ci_cobrador').focus();
			return false;

    }



}








function calcular_area_propaganda(){


largo =  $("largo").value;
alto  =  $("alto").value;

if(alto=="" || largo==""){
	$("area").value="";
	$("monto_mensual").value    = "0,00";
	$("total_mensual").value    = "0,00";
	$("monto_adicional").value  = "0,00";
	 if(largo!=""){precio_unitario("largo");}
     if(alto!=""){ precio_unitario("alto");}
}else{
  largo = retornar_valor_calculo(largo);
  alto  = retornar_valor_calculo(alto);
  area                        = redondear(eval(largo) * eval(alto), 3);
  monto_mensual               = eval($("monto_articulo").value) * eval(area);
  monto_mensual               = redondear(monto_mensual, 2);
  porcentaje_recargo          = $("porcentaje_recargo").value;
  monto_adicional             = redondear(eval(monto_mensual) * eval(porcentaje_recargo), 2);
  total_mensual               = eval(monto_mensual) + eval(monto_adicional);
  total_mensual               = redondear(total_mensual, 2);
  $("total_mensual").value    = total_mensual;
  $("monto_mensual").value    = monto_mensual;
  $("area").value             = area;
  $("cantidad_area2").value   = area;
  $("monto_adicional").value  = monto_adicional;
  moneda("total_mensual");
  moneda("monto_mensual");
  moneda("monto_adicional");
  precio_unitario("largo");
  precio_unitario("alto");
  precio_unitario("area");
  precio_unitario("cantidad_area2");
}


}//fin function














function calcular_area_propaganda_cantidad(){


if($("cantidad_area2").value!=""){

  cantidad_area2             =  retornar_valor_calculo($("cantidad_area2").value);
  monto_mensual              = eval($("monto_articulo").value) * eval(cantidad_area2);
  monto_mensual              = redondear(monto_mensual, 2);
  porcentaje_recargo         = $("porcentaje_recargo").value;
  monto_adicional            = redondear(eval(monto_mensual) * eval(porcentaje_recargo), 2);
  total_mensual              = eval(monto_mensual) + eval(monto_adicional);
  total_mensual              = redondear(total_mensual, 2);
  $("total_mensual").value   = total_mensual;
  $("monto_mensual").value   = monto_mensual;
  $("monto_adicional").value = monto_adicional;
  moneda("total_mensual");
  moneda("monto_mensual");
  moneda("monto_adicional");
  precio_unitario("cantidad_area2");
}else{
  $("total_mensual").value   = "0,00";
  $("monto_mensual").value   = "0,00";
  $("monto_adicional").value = "0,00";
}



}//fin function







