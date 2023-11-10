function reporte_analitico_pago(tipo, valor_0, valor_1, valor_2, valor_3, valor_4, valor_5, valor_6){


      if(tipo=="1"){

           document.getElementById('campo_0').value = valor_0;
           document.getElementById('campo_1').value = valor_1;
           document.getElementById('campo_2').value = valor_2;
           document.getElementById('campo_3').value = valor_3;
           document.getElementById('campo_4').value = valor_4;
           document.getElementById('campo_5').value = valor_5;
           document.getElementById('campo_6').value = valor_6;

}else if(tipo=="2"){

           document.getElementById('campo_0').value  = valor_0;
           document.getElementById('campo_7').value  = valor_1;
           document.getElementById('campo_8').value  = valor_2;
           document.getElementById('campo_9').value  = valor_3;
           document.getElementById('campo_10').value = valor_4;
           document.getElementById('campo_11').value = valor_5;

}//fin else





      if(document.getElementById('campo_1').value==""){

}else if(document.getElementById('campo_2').value==""){

}else if(document.getElementById('campo_3').value==""){

}else if(document.getElementById('campo_4').value==""){

}else if(document.getElementById('campo_5').value==""){

}else if(document.getElementById('campo_6').value==""){

}else if(document.getElementById('campo_7').value==""){

}else if(document.getElementById('campo_8').value==""){

}else if(document.getElementById('campo_9').value==""){

}else if(document.getElementById('campo_10').value==""){

}else if(document.getElementById('campo_11').value==""){

}else if(document.getElementById('campo_0').value==""){

}else{




      if(tipo=="1"){

           valor_0_input  = valor_0;
           valor_1_input  = valor_1;
           valor_2_input  = valor_2;
           valor_3_input  = valor_3;
           valor_4_input  = valor_4;
           valor_5_input  = valor_5;
           valor_6_input  = valor_6;
           valor_7_input  = document.getElementById('campo_7').value;
           valor_8_input  = document.getElementById('campo_8').value;
           valor_9_input  = document.getElementById('campo_9').value;
           valor_10_input = document.getElementById('campo_10').value;
           valor_11_input = document.getElementById('campo_11').value;

}else if(tipo=="2"){

           valor_0_input  = valor_0;
           valor_1_input  = document.getElementById('campo_1').value;
           valor_2_input  = document.getElementById('campo_2').value;
           valor_3_input  = document.getElementById('campo_3').value;
           valor_4_input  = document.getElementById('campo_4').value;
           valor_5_input  = document.getElementById('campo_5').value;
           valor_6_input  = document.getElementById('campo_6').value;
           valor_7_input  = valor_1;
           valor_8_input  = valor_2;
           valor_9_input  = valor_3;
           valor_10_input = valor_4;
           valor_11_input = valor_5;


}//fin else


//alert('/cfpp05_asignacion_ingreso_gasto/procesar/'+valor_0_input+'/'+valor_1_input+'/'+valor_2_input+'/'+valor_3_input+'/'+valor_4_input+'/'+valor_5_input+'/'+valor_6_input+'/'+valor_7_input+'/'+valor_8_input+'/'+valor_9_input+'/'+valor_10_input+'/'+valor_11_input);

ver_documento('/cfpp05_asignacion_ingreso_gasto/procesar/'+valor_0_input+'/'+valor_1_input+'/'+valor_2_input+'/'+valor_3_input+'/'+valor_4_input+'/'+valor_5_input+'/'+valor_6_input+'/'+valor_7_input+'/'+valor_8_input+'/'+valor_9_input+'/'+valor_10_input+'/'+valor_11_input, 'cuerpo_asociacion');


}//fin else






}//fin function