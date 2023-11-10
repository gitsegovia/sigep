<?php
header("Content-type: text/javascript");
?>

function ayuda_ocupacion(){
new Window({url: "./cnmp05/ayuda_ocupacion", className: "alphacube",title: "Ayuda Ocupaci&oacute;n", width:400, height:400, top:500, left:100 }).show()
WindowCloseKey.init();
}





function ayuda_ocupacion_cfpp97(){
new Window({url: "./cfpp97/ayuda_ocupacion", className: "alphacube",title: "Ayuda Ocupaci&oacute;n", width:400, height:400, top:500, left:100 }).show()
WindowCloseKey.init();
}


//win1 = new Window('1', {className: "alphacube", title: "Sample1", width:200, height:150, top:70, left:100}); win1.getContent().innerHTML = "<h1>1</h1>";



function Buscar(){
   if(document.getElementById('dbus').value== ''){
			fun_msj('Ingrese datos a buscar');
			return false;
	}
}
function mandar_codigo(codigo){
	var x = window.self;
    x.document.getElementById("ocupacion").value=codigo;
	alert('codigo: '+codigo);
}

function valida_codigo_cargo(){
           if(document.getElementById('valida').value==""){
	        fun_msj("Inserte el c&oacute;digo del cargo");
	        fondoCampo('valida',1);
			setTimeout("fondoCampo('valida',2);", 3000);
			document.getElementById('valida').focus();
			return false;
	}else if(document.getElementById('ubicacionadmin_4').value==""){
	        fun_msj("Seleccione los datos Ubicaci&oacute;n Administrativa");
			setTimeout("fondoCampo('ubicacionadmin_1',2);", 3000);
			setTimeout("fondoCampo('ubicacionadmin_2',2);", 3000);
			setTimeout("fondoCampo('ubicacionadmin_3',2);", 3000);
			setTimeout("fondoCampo('ubicacionadmin_4',2);", 3000);
			setTimeout("fondoCampo('ubicacionadmin_5',2);", 3000);
			setTimeout("fondoCampo('ubicacionadmin_6',2);", 3000);
			setTimeout("fondoCampo('ubicacionadmin_7',2);", 3000);
			document.getElementById('ubicacionadmin_7').focus();
			return false;
	}else if(document.getElementById('ubicaciongeo_4').value==""){
	        fun_msj("Seleccione los datos Ubicaci&oacute;n Geogr&aacute;fica");
			setTimeout("fondoCampo('ubicaciongeo_1',2);", 3000);
			setTimeout("fondoCampo('ubicaciongeo_2',2);", 3000);
			setTimeout("fondoCampo('ubicaciongeo_3',2);", 3000);
			setTimeout("fondoCampo('ubicaciongeo_4',2);", 3000);
			document.getElementById('ubicaciongeo_4').focus();
			return false;
	}else if(document.getElementById('codpresupuestarios_10').value==""){
	        fun_msj("Seleccione los C&oacute;digos Presupuestarios");
			setTimeout("fondoCampo('codpresupuestarios_1',2);", 3000);
			setTimeout("fondoCampo('codpresupuestarios_2',2);", 3000)
			setTimeout("fondoCampo('codpresupuestarios_3',2);", 3000)
			setTimeout("fondoCampo('codpresupuestarios_4',2);", 3000)
			setTimeout("fondoCampo('codpresupuestarios_5',2);", 3000)
			setTimeout("fondoCampo('codpresupuestarios_6',2);", 3000)
			setTimeout("fondoCampo('codpresupuestarios_7',2);", 3000)
			setTimeout("fondoCampo('codpresupuestarios_8',2);", 3000)
			setTimeout("fondoCampo('codpresupuestarios_9',2);", 3000)
			setTimeout("fondoCampo('codpresupuestarios_10',2);", 3000)
			document.getElementById('codpresupuestarios_10').focus();
			return false;
	}else if(document.getElementById('ocupacion').value==""){
	        fun_msj("Seleccione la Ocupaci&oacute;n");
			setTimeout("fondoCampo('ocupacion',2);", 3000);
			document.getElementById('ocupacion').focus();
			return false;
	}else if(document.getElementById('ciclo').value==""){
	        fun_msj("Inserte la cantidad de cargos a definir");
	        fondoCampo('ciclo',1);
			setTimeout("fondoCampo('ciclo',2);", 3000);
			document.getElementById('ciclo').focus();
			return false;
	}
}


function valida_codigo_cargo_cfpp97(){
           if(document.getElementById('valida').value==""){
	        fun_msj("Inserte el c&oacute;digo del cargo");
	        fondoCampo('valida',1);
			setTimeout("fondoCampo('valida',2);", 3000);
			document.getElementById('valida').focus();
			return false;
	}else if(document.getElementById('cod_tipo_nomina').value==""){
	        fun_msj("Inserte el c&oacute;digo de nomina");
	        fondoCampo('cod_tipo_nomina',1);
			setTimeout("fondoCampo('cod_tipo_nomina',2);", 3000);
			document.getElementById('cod_tipo_nomina').focus();
			return false;
	}else if(document.getElementById('ubicacionadmin_4').value==""){
	        fun_msj("Seleccione los datos Ubicaci&oacute;n Administrativa");
			setTimeout("fondoCampo('ubicacionadmin_1',2);", 3000);
			setTimeout("fondoCampo('ubicacionadmin_2',2);", 3000);
			setTimeout("fondoCampo('ubicacionadmin_3',2);", 3000);
			setTimeout("fondoCampo('ubicacionadmin_4',2);", 3000);
			setTimeout("fondoCampo('ubicacionadmin_5',2);", 3000);
			setTimeout("fondoCampo('ubicacionadmin_6',2);", 3000);
			setTimeout("fondoCampo('ubicacionadmin_7',2);", 3000);
			document.getElementById('ubicacionadmin_7').focus();
			return false;
	}else if(document.getElementById('ubicaciongeo_4').value==""){
	        fun_msj("Seleccione los datos Ubicaci&oacute;n Geogr&aacute;fica");
			setTimeout("fondoCampo('ubicaciongeo_1',2);", 3000);
			setTimeout("fondoCampo('ubicaciongeo_2',2);", 3000);
			setTimeout("fondoCampo('ubicaciongeo_3',2);", 3000);
			setTimeout("fondoCampo('ubicaciongeo_4',2);", 3000);
			document.getElementById('ubicaciongeo_4').focus();
			return false;
	}else if(document.getElementById('codpresupuestarios_10').value==""){
	        fun_msj("Seleccione los C&oacute;digos Presupuestarios");
			setTimeout("fondoCampo('codpresupuestarios_1',2);", 3000);
			setTimeout("fondoCampo('codpresupuestarios_2',2);", 3000)
			setTimeout("fondoCampo('codpresupuestarios_3',2);", 3000)
			setTimeout("fondoCampo('codpresupuestarios_4',2);", 3000)
			setTimeout("fondoCampo('codpresupuestarios_5',2);", 3000)
			setTimeout("fondoCampo('codpresupuestarios_6',2);", 3000)
			setTimeout("fondoCampo('codpresupuestarios_7',2);", 3000)
			setTimeout("fondoCampo('codpresupuestarios_8',2);", 3000)
			setTimeout("fondoCampo('codpresupuestarios_9',2);", 3000)
			setTimeout("fondoCampo('codpresupuestarios_10',2);", 3000)
			document.getElementById('codpresupuestarios_10').focus();
			return false;
	}else if(document.getElementById('tipo').value==""){
	        fun_msj("Seleccione el tipo de personal");
			setTimeout("fondoCampo('tipo',2);", 3000);
			document.getElementById('tipo').focus();
			return false;
	}else if(document.getElementById('ocupacion').value==""){
	        fun_msj("Seleccione la Ocupaci&oacute;n");
			setTimeout("fondoCampo('ocupacion',2);", 3000);
			document.getElementById('ocupacion').focus();
			return false;
	}else if(document.getElementById('sueldo_basico').value==""){
	        fun_msj("Ingrese el sueldo basico");
			setTimeout("fondoCampo('sueldo_basico',2);", 3000);
			return false;
//			 || document.getElementById('sueldo_basico').value=="0" || document.getElementById('sueldo_basico').value=="0,00"  //esto formaba parte de la condición, lo quite para que pudieran guardar el sueldo con 0
	}else if(document.getElementById('ciclo').value==""){
	        fun_msj("Inserte la cantidad de cargos a definir");
	        fondoCampo('ciclo',1);
			setTimeout("fondoCampo('ciclo',2);", 3000);
			document.getElementById('ciclo').focus();
			return false;
	}
}


function update_total(){
		pag='../../include/cfpp05/moneda.php?monto=';
	    var a1 = reemplazarPC(document.getElementById('sueldo_basico').value);
        var a2 = reemplazarPC(document.getElementById('compensacion').value);
        var a3 = reemplazarPC(document.getElementById('bonos').value);
        var a4 = reemplazarPC(document.getElementById('primas').value);
        var TOTAL=eval(redondear(a1,2)) + eval(redondear(a2,2)) + eval(redondear(a3,2)) + eval(redondear(a4,2));
        cargarMonto('total',pag+redondear(TOTAL,2));
        //alert(eval(redondear(TOTAL,2)));
        //document.getElementById('total').value =eval(a1) + eval(a2) + eval(a3) + eval(a4);
        //moneda('total');
}
function m(a){
var str = a;
for(i=0; i<a.length; i++){
    str = str.replace('.','');
}
str = str.replace(',','.');
var a = redondear(str,2);

return a;
}




function valida_nomina_cargos(){

		if(document.getElementById('cod_tipo_nomina').value==""){
	        fun_msj("Antes debe seleccionar el código de nómina");
			setTimeout("fondoCampo('cod_tipo_nomina',2);", 3000);
			return false;
		}

}