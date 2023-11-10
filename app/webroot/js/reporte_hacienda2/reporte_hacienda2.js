function reporte_hacienda2_valida_recibos(){

                    if($("tipo_year_2").checked==true && $("ano").value==""){
                      fun_msj('Por favor inserte el a&ntilde;o');
                      document.getElementById('ano').focus();
			          return false;
			  }else if($("impuesto").value==""){
			          fun_msj('Por favor inserte el tipo de impuesto');
                      document.getElementById('impuesto').focus();
			          return false;
			  }

}//fin function





function reporte_hacienda2_boletin_not_pat_1(){
editor = capturar_contenido_editor();
                if($("ano").value==""){
             fun_msj('Por favor inserte el a&ntilde;o');
             document.getElementById('ano').focus();
             return false;
		  }else if($("ano_arranque").value<$("ano").value){
             fun_msj('Por favor el a&ntilde;o debe ser mayor al de arranque');
             document.getElementById('ano').focus();
             return false;
          }else if(editor==1){
			fun_msj('Por favor inserte los articulos');
			return false;
		  }
}//fin function



function reporte_hacienda2_boletin_not_pat_2(){
editor = capturar_contenido_editor();
		if(editor==1){
			fun_msj('Por favor inserte los articulos');
			return false;
        }
}//fin function








function reporte_hacienda2_grafico_facturacion(){
                    if($("ano").value==""){
                      fun_msj('Por favor inserte el a&ntilde;o');
                      document.getElementById('ano').focus();
			          return false;
			  }else if($("tipo_impuesto_2").checked==true && $("impuesto").value==""){
			          fun_msj('Por favor inserte el tipo de impuesto');
                      document.getElementById('impuesto').focus();
			          return false;
			  }

}






function reporte_hacienda2_grafico_cobranza(){
                    if($("ano").value==""){
                      fun_msj('Por favor inserte el a&ntilde;o');
                      document.getElementById('ano').focus();
			          return false;
			  }else if($("tipo_impuesto_2").checked==true && $("impuesto").value==""){
			          fun_msj('Por favor inserte el tipo de impuesto');
                      document.getElementById('impuesto').focus();
			          return false;
			  }

}




function reporte_hacienda2_grafico_deuda(){
                    if($("ano").value==""){
                      fun_msj('Por favor inserte el a&ntilde;o');
                      document.getElementById('ano').focus();
			          return false;
			  }else if($("tipo_impuesto_2").checked==true && $("impuesto").value==""){
			          fun_msj('Por favor inserte el tipo de impuesto');
                      document.getElementById('impuesto').focus();
			          return false;
			  }

}






function reporte_hacienda2_decla_ingre_bruto(){
              if($("tipo_year_2").checked==true && $("ano").value==""){
		          fun_msj('Por favor inserte el a&ntilde;o');
		          return false;
			  }

}