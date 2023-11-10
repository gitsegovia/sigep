function valida_cfpp07_ano(){
   var mydate=new Date()
   var year1=mydate.getYear()
   var year=mydate.getYear()
      if (year < 1000)
           year+=1901

           if(document.getElementById('ano').value==''){

			fun_msj('Ingrese el a&ntilde;o');
			document.getElementById('ano').focus();
			return false;

	}else if(document.getElementById('ano').value.length<4){

			fun_msj('Ingrese un a&ntilde;o correcto');
			document.getElementById('ano').focus();
			return false;


	}else if(document.getElementById('ano').value< 2000 || document.getElementById('ano').value>year){
	fun_msj("Ingrese un a&ntilde;o correcto ");
			document.getElementById('ano').focus();
			return false;
	}
}



function valida_cfpp07_sin(){
   var mydate=new Date()
   var year1=mydate.getYear()
   var year=mydate.getYear()
      if (year < 1000)
           year+=1901




      if(document.getElementById('cod_obra').value==""){

			fun_msj('Ingrese el C&oacute;digo');
			document.getElementById('cod_obra').focus();
			return false;


}else if(document.getElementById('fecha_inicio').value==""){

			fun_msj('Ingrese la Fecha de inicio');
			document.getElementById('fecha_inicio').focus();
			return false;

}else if(document.getElementById('fecha_conclusion').value==""){

			fun_msj('Ingrese la Fecha de conclusi&oacute;n');
			document.getElementById('fecha_conclusion').focus();
			return false;



}else if(document.getElementById('terminado').checked!=false || document.getElementById('paralizado').checked!=false || document.getElementById('eejecucion').checked!=false || document.getElementById('aejecutarse').checked!=false){




     if(document.getElementById('denominacion').value==""){

			fun_msj('Ingrese la Denominaci&oacute;n de la obra');
			document.getElementById('denominacion').focus();
			return false;

}else if(document.getElementById('funcionario_responsable').value==""){

			fun_msj('Ingrese el Funcionario Responsable');
			document.getElementById('funcionario_responsable').focus();
			return false;



}else  if(document.getElementById('compro_ano_ante').value==""){

			fun_msj('Ingrese Comprometidas a&ntilde;os anteriores');
			document.getElementById('compro_ano_ante').focus();
			return false;



}else if(document.getElementById('costo_total').value==""){

			fun_msj('Ingrese el costo total');
			document.getElementById('costo_total').focus();
			return false;

}else if(document.getElementById('compro_ano_vige').value==""){

			fun_msj('Ingrese Comprometidas a&ntilde;os vigente');
			document.getElementById('compro_ano_vige').focus();
			return false;


}else if(document.getElementById('ejecuta_ano_ante').value==""){

			fun_msj('Ingrese Ejecutadas a&ntilde;os anteriores');
			document.getElementById('ejecuta_ano_ante').focus();
			return false;

}else if(document.getElementById('ejecuta_ano_vige').value==""){

			fun_msj('Ingrese Ejecutadas a&ntilde;os vigente');
			document.getElementById('ejecuta_ano_vige').focus();
			return false;


}else if(document.getElementById('estimado_presu').value==""){

			fun_msj('Ingrese Estimadas a&ntilde;os anteriores');
			document.getElementById('estimado_presu').focus();
			return false;

}else if(document.getElementById('estimado_presu').value=="0,00"){

			fun_msj('Ingrese Estimadas a&ntilde;os anteriores');
			document.getElementById('estimado_presu').focus();
			return false;

}else if(document.getElementById('estimado_ano_posterior').value==""){

			fun_msj('Ingrese Estimadas a&ntilde;os vigente');
			document.getElementById('estimado_ano_posterior').focus();
			return false;


}else if(document.getElementById('cuenta_i').value==""){

			fun_msj('No existen partidas en la imputaci&oacute;n presupuestaria');
			document.getElementById('cuenta_i').focus();
			return false;


}else if(document.getElementById('tipo_recurso_1').checked!=false || document.getElementById('tipo_recurso_2').checked!=false || document.getElementById('tipo_recurso_3').checked!=false || document.getElementById('tipo_recurso_4').checked!=false  || document.getElementById('tipo_recurso_5').checked!=false){


   if(document.getElementById('plan_inversion_1').checked==true){

      if(document.getElementById('select_prueba').value==''){

            fun_msj('Ingrese el plan de inversi&oacute;n');
			return false;

      }else{
            if(document.getElementById('acepta').value=='no'){
                 fun_msj('El monto presupuestado es mayor que la disponiblidad');
			     return false;
            }else{
                 fun_msj2('Fue Guardado el Registro');
            }//fin else
      }//fin else
   }else{

    //selec_desmarcar('select_1');
    fun_msj2('Fue Guardado el Registro');


    }//fin else

}else{

            fun_msj('Ingrese el tipo de registro');
			return false;


      }



}else{
             fun_msj('Selecione la situaci&oacute;n');
			 return false;

	}//fin else


}//fin funtion










function valida_cfpp07(){


fecha_a = new Array();
fecha_b = new Array();

var checkStr = document.getElementById('fecha_inicio').value;
var fecha_a=checkStr.split("/");

var checkStr = document.getElementById('fecha_conclusion').value;
var fecha_b=checkStr.split("/");

a = eval(fecha_a[0]) + eval(fecha_a[1]) + eval(fecha_a[2]);
b = eval(fecha_b[0]) + eval(fecha_b[1]) + eval(fecha_b[2]);




     if(document.getElementById('cod_obra').value==""){

			fun_msj('Ingrese el C&oacute;digo de la obra');
			document.getElementById('cod_obra').focus();
			return false;



}else if(document.getElementById('denominacion').value==""){

			fun_msj('Ingrese la Denominaci&oacute;n de la obra');
			document.getElementById('denominacion').focus();
			return false;

}else if(document.getElementById('funcionario_responsable').value==""){

			fun_msj('Ingrese el Funcionario Responsable');
			document.getElementById('funcionario_responsable').focus();
			return false;

}else if(document.getElementById('cod_snc').value==""){

			fun_msj('Seleciones el Servicio Nacional de Contratista');
			document.getElementById('cod_snc').focus();
			return false;

}else if(document.getElementById('fecha_inicio').value==""){

			fun_msj('Ingrese la Fecha de inicio');
			document.getElementById('fecha_inicio').focus();
			return false;



/*
}else if(verifica_cierre_ano_ejecucion('fecha_inicio')==false){
	fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA DE INICIO NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
	return false;
*/

}else if(document.getElementById('fecha_conclusion').value=="" ){

			fun_msj('Ingrese la Fecha de conclusi&oacute;n');
			document.getElementById('fecha_conclusion').focus();
			return false;


}else if(diferenciaFecha(document.getElementById('fecha_conclusion').value, document.getElementById('fecha_inicio').value)){

fun_msj('la Fecha de conclusi&oacute;n debe ser mayor a la de inicio');
return false;


}else if(document.getElementById('terminado').checked!=false || document.getElementById('paralizado').checked!=false || document.getElementById('eejecucion').checked!=false || document.getElementById('aejecutarse').checked!=false){

if(document.getElementById('compro_ano_ante').value==""){

			fun_msj('Ingrese Comprometidas a&ntilde;os anteriores');
			document.getElementById('compro_ano_ante').focus();
			return false;



}else if(document.getElementById('costo_total').value==""){

			fun_msj('Ingrese el costo total');
			document.getElementById('costo_total').focus();
			return false;

}else if(document.getElementById('compro_ano_vige').value==""){

			fun_msj('Ingrese Comprometidas a&ntilde;os vigente');
			document.getElementById('compro_ano_vige').focus();
			return false;


}else if(document.getElementById('ejecuta_ano_ante').value==""){

			fun_msj('Ingrese Ejecutadas a&ntilde;os anteriores');
			document.getElementById('ejecuta_ano_ante').focus();
			return false;

}else if(document.getElementById('ejecuta_ano_vige').value==""){

			fun_msj('Ingrese Ejecutadas a&ntilde;os vigente');
			document.getElementById('ejecuta_ano_vige').focus();
			return false;


}else if(document.getElementById('estimado_presu').value==""){

			fun_msj('Ingrese Estimadas a&ntilde;os anteriores');
			document.getElementById('estimado_presu').focus();
			return false;



}else if(document.getElementById('estimado_ano_posterior').value==""){

			fun_msj('Ingrese Estimadas a&ntilde;os vigente');
			document.getElementById('estimado_ano_posterior').focus();
			return false;

}else if(document.getElementById('tipo_recurso_1').checked!=false || document.getElementById('tipo_recurso_2').checked!=false || document.getElementById('tipo_recurso_3').checked!=false || document.getElementById('tipo_recurso_4').checked!=false  || document.getElementById('tipo_recurso_5').checked!=false || document.getElementById('tipo_recurso_6').checked!=false){


  if(document.getElementById('cuenta_i')){

      if(document.getElementById('cuenta_i').value!="" && document.getElementById('cuenta_i').value!="0"){
        var str =  document.getElementById('TOTALINGRESOS').innerHTML;
		for(i=0; i<str.length; i++){str = str.replace('.','');}//fin for
		 str   = str.replace(',','.');
		 var total = redondear(str,2);


         a = document.getElementById('estimado_presu').value;
		 var str = a;
		 for(i=0; i<a.length; i++){str = str.replace('.','');}//fin for
		 str   = str.replace(',','.');
		 var a = redondear(str,2);

       if(total!=a){
             fun_msj('El monto del presupuesto estimado y el de las partidas no son iguales');
			 return false;
       }else{


                  if(document.getElementById('clasificacion_recurso_saldo_del_plan')){

         b = document.getElementById('clasificacion_recurso_saldo_del_plan').value;
		 var str = b;
		 for(i=0; i<b.length; i++){str = str.replace('.','');}//fin for
		 str   = str.replace(',','.');
		 var b = redondear(str,2);

                if(a>b){
                       fun_msj('El monto del presupuesto estimado es mayor al saldo del plan');
			            return false;
                     }//fin


                  }//fin if


       }//fin else







      }else{
                   fun_msj('No ha insertado ninguna partida presupuestaria');
			      return false;


      }//fin else


  }//fin if



}else{

            fun_msj('Ingrese el tipo de registro');
			return false;


      }



}else{

             fun_msj('Selecione la situaci&oacute;n');
			 return false;

	}//fin else


}//fin funtion






function valida22_cfpp07(){


fecha_a = new Array();
fecha_b = new Array();

var checkStr = document.getElementById('fecha_inicio').value;
var fecha_a=checkStr.split("/");

var checkStr = document.getElementById('fecha_conclusion').value;
var fecha_b=checkStr.split("/");

a = eval(fecha_a[0]) + eval(fecha_a[1]) + eval(fecha_a[2]);
b = eval(fecha_b[0]) + eval(fecha_b[1]) + eval(fecha_b[2]);




     if(document.getElementById('cod_obra').value==""){

			fun_msj('Ingrese el C&oacute;digo de la obra');
			document.getElementById('cod_obra').focus();
			return false;



}else if(document.getElementById('denominacion').value==""){

			fun_msj('Ingrese la Denominaci&oacute;n de la obra');
			document.getElementById('denominacion').focus();
			return false;

}else if(document.getElementById('funcionario_responsable').value==""){

			fun_msj('Ingrese el Funcionario Responsable');
			document.getElementById('funcionario_responsable').focus();
			return false;

}else if(document.getElementById('cod_snc').value==""){

			fun_msj('Seleciones el Servicio Nacional de Contratista');
			document.getElementById('cod_snc').focus();
			return false;

}else if(document.getElementById('fecha_inicio').value==""){

			fun_msj('Ingrese la Fecha de inicio');
			document.getElementById('fecha_inicio').focus();
			return false;

/*
}else if(verifica_cierre_ano_ejecucion('fecha_inicio')==false){
	fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA DE INICIO NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
	return false;
*/
}else if(document.getElementById('fecha_conclusion').value=="" ){

			fun_msj('Ingrese la Fecha de conclusi&oacute;n');
			document.getElementById('fecha_conclusion').focus();
			return false;


}else if(diferenciaFecha(document.getElementById('fecha_conclusion').value, document.getElementById('fecha_inicio').value)){

fun_msj('la Fecha de conclusi&oacute;n debe ser mayor a la de inicio');
return false;


}else if(document.getElementById('terminado').checked!=false || document.getElementById('paralizado').checked!=false || document.getElementById('eejecucion').checked!=false || document.getElementById('aejecutarse').checked!=false){

if(document.getElementById('compro_ano_ante').value==""){

			fun_msj('Ingrese Comprometidas a&ntilde;os anteriores');
			document.getElementById('compro_ano_ante').focus();
			return false;



}else if(document.getElementById('costo_total').value==""){

			fun_msj('Ingrese el costo total');
			document.getElementById('costo_total').focus();
			return false;

}else if(document.getElementById('compro_ano_vige').value==""){

			fun_msj('Ingrese Comprometidas a&ntilde;os vigente');
			document.getElementById('compro_ano_vige').focus();
			return false;


}else if(document.getElementById('ejecuta_ano_ante').value==""){

			fun_msj('Ingrese Ejecutadas a&ntilde;os anteriores');
			document.getElementById('ejecuta_ano_ante').focus();
			return false;

}else if(document.getElementById('ejecuta_ano_vige').value==""){

			fun_msj('Ingrese Ejecutadas a&ntilde;os vigente');
			document.getElementById('ejecuta_ano_vige').focus();
			return false;


}else if(document.getElementById('estimado_presu').value==""){

			fun_msj('Ingrese Estimadas a&ntilde;os anteriores');
			document.getElementById('estimado_presu').focus();
			return false;



}else if(document.getElementById('estimado_ano_posterior').value==""){

			fun_msj('Ingrese Estimadas a&ntilde;os vigente');
			document.getElementById('estimado_ano_posterior').focus();
			return false;

}else if(document.getElementById('tipo_recurso_1').checked!=false || document.getElementById('tipo_recurso_2').checked!=false || document.getElementById('tipo_recurso_3').checked!=false || document.getElementById('tipo_recurso_4').checked!=false  || document.getElementById('tipo_recurso_5').checked!=false || document.getElementById('tipo_recurso_6').checked!=false || document.getElementById('tipo_recurso_7').checked!=false || document.getElementById('tipo_recurso_8').checked!=false){


  if(document.getElementById('cuenta_i')){

      if(document.getElementById('cuenta_i').value!="" && document.getElementById('cuenta_i').value!="0"){
        var str =  document.getElementById('TOTALINGRESOS').innerHTML;
		for(i=0; i<str.length; i++){str = str.replace('.','');}//fin for
		 str   = str.replace(',','.');
		 var total = redondear(str,2);


         a = document.getElementById('estimado_presu').value;
		 var str = a;
		 for(i=0; i<a.length; i++){str = str.replace('.','');}//fin for
		 str   = str.replace(',','.');
		 var a = redondear(str,2);

       if(total!=a){
             fun_msj('El monto del presupuesto estimado y el de las partidas no son iguales');
			 return false;
       }else{


                  if(document.getElementById('clasificacion_recurso_saldo_del_plan')){

         b = document.getElementById('clasificacion_recurso_saldo_del_plan').value;
		 var str = b;
		 for(i=0; i<b.length; i++){str = str.replace('.','');}//fin for
		 str   = str.replace(',','.');
		 var b = redondear(str,2);

                if(a>b){
                       fun_msj('El monto del presupuesto estimado es mayor al saldo del plan');
			            return false;
                     }//fin


                  }//fin if


       }//fin else







      }else{
                   fun_msj('No ha insertado ninguna partida presupuestaria');
			      return false;


      }//fin else


  }//fin if



}else{

            fun_msj('Ingrese el tipo de registro');
			return false;


      }



}else{

             fun_msj('Selecione la situaci&oacute;n');
			 return false;

	}//fin else


}//fin funtion










function valida_cfpp07_ano(){
   var mydate=new Date()
   var year1=mydate.getYear()
   var year=mydate.getYear()
      if (year < 1000)
           year+=1901

   if(document.getElementById('anoPresupuesto').value==''){

			fun_msj('Ingrese el a&ntilde;o');
			document.getElementById('anoPresupuesto').focus();
			return false;

	}else if(document.getElementById('anoPresupuesto').value.length<4){

			fun_msj('Ingrese un a&ntilde;o correcto');
			document.getElementById('anoPresupuesto').focus();
			return false;


	}else if(document.getElementById('anoPresupuesto').value< 2000 || document.getElementById('anoPresupuesto').value>year){
	fun_msj("Ingrese un a&ntilde;o correcto ");
			document.getElementById('anoPresupuesto').focus();
			return false;
	}
}//fin funcion valida_cfpp07auxilair
















function calcular_nuevo_monto_cfpp07(){



total = 0;

for(ii=0; ii<document.getElementById('cuenta_i').value; ii++){
   if(document.getElementById('monto_'+ii)){

               a = document.getElementById('monto_'+ii).value;
               var str = a;
		       for(i=0; i<a.length; i++){str = str.replace('.','');}//fin for
		        str   = str.replace(',','.');
		        var a = redondear(str,2);
		        //total =  eval(total) + eval(a);
		        total = eval(total) + eval(a);
		  }//fin if
     }//fin



//////////////////////////////////////////////////////////////
		 var str =  total+'';
		 for(x=0; x<str.length; x++){if(str.charAt(x)=="."){
		    total=str.substr(0,eval(x)+eval(6));
		    break;
		    }//fin if
		   }//fin for
		 var total = redondear(total,2);
//////////////////////////////////////////////////////////////



 cscp03_cotizacion_cuerpo_moneda('TOTALINGRESOS', total);




}//fin function











function valida_cfpp07_eliminar(){


	fun_msj('Fue Eliminado el Registro ');


}
