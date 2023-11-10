function ciap01_productos_materiales_consulta(){
	if(document.getElementById('select_1').value==''){

			fun_msj('antes debe seleccionar el c&oacute;digo del almac&eacute;n');
			document.getElementById('select_1').focus();
			return false;

	}

}//fin ciap01_productos_materiales_consulta



function cantidad_producto_inventario(id){
    var monto = document.getElementById(id).value;
    if(monto!=""){
       pag="../../include/cantidad_productos.php?monto="+monto;
       cargarMonto(id,pag);
    }else{
      document.getElementById(id).value='0,000000';
    }
}



function verifica_crear_hasta_almacen(){
    var a=eval(document.getElementById("crear_hasta").value);
    var b=eval(document.getElementById("crear_desde").value);
    if(document.getElementById('select_1').value==''){

			fun_msj('debe seleccionar el c&oacute;digo del almac&eacute;n');
			document.getElementById('select_1').focus();
			return false;

	}else if(document.getElementById("crear_hasta").value==""){
            fun_msj('Inserte el valor Crear hasta');
			setTimeout("fondoCampo('crear_hasta',2);", 1500);
			document.getElementById('crear_hasta').focus();
			return false;
    }else if(a <= b){
            fun_msj('El valor Crear hasta ('+a+') debe ser mayor a '+b);
			setTimeout("fondoCampo('crear_hasta',2);", 1500);
			document.getElementById('crear_hasta').focus();
			return false;
    }else{}
}




function valida_cip01_inventario_ano(){
	if(document.getElementById('ano').value==''){

			fun_msj('Debe seleccionar el a&ntilde;o');
			document.getElementById('ano').focus();
			return false;
	}
}



