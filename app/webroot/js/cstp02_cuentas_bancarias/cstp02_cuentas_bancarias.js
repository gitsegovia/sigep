function valida_cstp02_cuentas_bancarias(){

if(document.getElementById('cuenta_bancaria').value==''){

			fun_msj('La cuenta bancaria no puede estar vac&iacute;a');
			document.getElementById('cuenta_bancaria').focus();
			return false;

	}if(document.getElementById('cuenta_bancaria').value.length<12){

			fun_msj('La cuenta bancaria no puede ser menor de 12 digitos');
			document.getElementById('cuenta_bancaria').focus();
			return false;

	}if(document.getElementById('fecha_apertura').value==''){

			fun_msj('La fecha de apertura no puede estar vac&iacute;a');
			document.getElementById('fecha_apertura').focus();
			return false;

	}if(document.getElementById('concepto_manejo').value==''){

			fun_msj('el concepto de manejo no puede estar vac&iacute;o');
			document.getElementById('concepto_manejo').focus();
			return false;

	}if((document.getElementById('radio_tipocuenta_1').checked==false) && (document.getElementById('radio_tipocuenta_2').checked==false)){
		fun_msj('Debe seleccionar el tipo de cuenta');
		return false;
 	}if(document.getElementById('responsable_manejo').value==''){

			fun_msj('el Nombre Responsable  del Manejo no puede estar vac&iacute;o');
			document.getElementById('responsable_manejo').focus();
			return false;

	}if(document.getElementById('cargo_responable').value==''){

			fun_msj('el Cargo que Ocupa el Responsable no puede estar vac&iacute;o');
			document.getElementById('cargo_responable').focus();
			return false;

	}//**********************************************************************************


}//fin funtion

function moneda_(id){
    var monto = document.getElementById(id).value;
    if(monto!=""){
       pag="../../include/cscp04_ordencompra_formato/moneda.php?monto="+monto;
       cargarMonto(id,pag);
    }else{
      document.getElementById(id).value=0;
    }
}