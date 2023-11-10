function grilla_declaracion_ingresos(){
   if($('cod_actividad').value==''){
			error('Por favor seleccione la actividad');
			$('cod_actividad').focus();
			return false;
	}else if($('ingresosx').value==''){
			error('Por favor ingrese el monto de ingresos que obtuvo por la actividad correspondiente');
			$('ingresosx').focus();
			return false;
	}

}//fin funtion

function reemplazarPC(a){
var str = a;
for(i=0; i<a.length; i++){
    str = str.replace('.','');
}
var b=str;
var str = b;
for(i=0; i<b.length; i++){
    str = str.replace(',','.');
}
return str;
}



function guardar_declaracion_ingresos_xjgha(){


if($('monto_impuesto')){
monto_impuesto = eval($('monto_impuesto').value);
}else{
monto_impuesto=5;
}




         if($('rif_constribuyente').value==""){//arranca
			error('Inserte el Rif o Cedula de Identidad del contribuyente');
			$('rif_constribuyente').focus();
			return false;
	}else if(eval(monto_impuesto)==eval(0)){
			error('El monto de impuesto no puede ser igual a cero');
			return false;
	}else if($('numero_declaracion').value==''){
			error('Por favor ingrese el n&uacute;mero de declaraci&oacute;n');
			$('numero_declaracion').focus();
			return false;
	}else if($('fecha_declaracion').value==''){
			error('Por favor seleccione la fecha de declaraci&oacute;n');
			$('fecha_declaracion').focus();
			return false;
	}else if($('periodo_desde').value==''){
			error('Por favor seleccione el peri&oacute;do desde');
			$('periodo_desde').focus();
			return false;
	}else if($('periodo_hasta').value==''){
			error('Por favor seleccione el peri&oacute;do hasta');
			$('periodo_hasta').focus();
			return false;
	}else if($('capital').value==''){
			error('Por favor ingrese el capital');
			$('capital').focus();
			return false;
	}else if($('numero_empleados').value==''){
			error('Por favor ingrese el n&uacute;mero de empleados');
			$('numero_empleados').focus();
			return false;
	}else if($('numero_obreros').value==''){
			error('Por favor ingrese el n&uacute;mero de obreros');
			$('numero_obreros').focus();
			return false;
	}
}//fin funtion


function calcular_declaracion_ingreso_v2_impuesto(i){
     var desde=$('periodo_desde').value;
     var hasta=$('periodo_hasta').value;
     var monto_ingresos = reemplazarPC($('ingresos'+i).value);
     if(desde!='' && hasta!=''){
         if(monto_ingresos!=''){
             desde = desde.replace('/','-');
     		desde = desde.replace('/','-');
     		hasta = hasta.replace('/','-');
     		hasta = hasta.replace('/','-');
             ver_documento('/shp100_declaracion_ingresos_v2/antiguedad/'+desde+'/'+hasta+'/'+i,'antiguedad');

         }
     }else{
        if(desde==''){
            error('Debe seleccionar el periodo desde');
			$('periodo_desde').focus();
        }else{
            error('Debe seleccionar el periodo hasta');
			$('periodo_hasta').focus();
        }
        $('ingresos'+i).value='';
     }
}