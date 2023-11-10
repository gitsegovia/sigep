function valida_cfpp09_ano(){
   var mydate=new Date()
   var year1=mydate.getYear()
   var year=mydate.getYear()
      if (year < 1000)
           year+=1901

           if(document.getElementById('ano').value==''){

			fun_msj('Inserte el a&ntilde;o');
			document.getElementById('ano').focus();
			return false;

	}else if(document.getElementById('ano').value.length<4){

			fun_msj('Inserte un a&ntilde;o correcto');
			document.getElementById('ano').focus();
			return false;


	}else if(document.getElementById('ano').value< 2000 || document.getElementById('ano').value>year){
	fun_msj("Inserte un a&ntilde;o correcto ");
			document.getElementById('ano').focus();
			return false;
	}
}



function valida_cfpp09(){
   var mydate=new Date()
   var year1=mydate.getYear()
   var year=mydate.getYear()
      if (year < 1000)
           year+=1901

   if(document.getElementById('anoPresupuesto').value==''){

			fun_msj('Inserte el a&ntilde;o presupuestario');
			document.getElementById('anoPresupuesto').focus();
			return false;

	}else if(document.getElementById('anoPresupuesto').value.length<4){

			fun_msj('Inserte un a&ntilde;o correcto');
			document.getElementById('anoPresupuesto').focus();
			return false;


	}else if(document.getElementById('anoPresupuesto').value< 2000 || document.getElementById('anoPresupuesto').value>year){
	fun_msj("Inserte un a&ntilde;o correcto ");
			document.getElementById('anoPresupuesto').focus();
			return false;
	}else if(document.getElementById('select_1').value==''){

			fun_msj('Seleccione Sector');
			document.getElementById('select_1').focus();
			return false;

}else if(document.getElementById('select_2').value == ""){

			fun_msj('Seleccione Programa');
			document.getElementById('select_2').focus();
			return false;


}else if(document.getElementById('select_3').value == ""){

			fun_msj('Seleccione Sub Programa');
			document.getElementById('select_3').focus();
			return false;


}else if(document.getElementById('select_4').value == ""){

			fun_msj('Seleccione Proyecto');
			document.getElementById('select_4').focus();
			return false;

}else if(document.getElementById('select_5').value == ""){

			fun_msj('Seleccione Actividad u Obra');
			document.getElementById('select_5').focus();
			return false;

}else if(document.getElementById('select_6').value == ""){

			fun_msj('Seleccione Partida');
			document.getElementById('select_6').focus();
			return false;


}else if(document.getElementById('select_7').value == ""){

			fun_msj('Seleccione Generica');
			document.getElementById('select_7').focus();
			return false;


}else if(document.getElementById('select_8').value == ""){

			fun_msj('Seleccione Especifica');
			document.getElementById('select_8').focus();
			return false;



}else if(document.getElementById('select_9').value == ""){

			fun_msj('Seleccione Sub-Especifica');
			document.getElementById('select_9').focus();
			return false;



}else if(document.getElementById('select_10').value == ""){

			fun_msj('Seleccione auxiliar');
			document.getElementById('select_10').focus();
			return false;



}else if(document.getElementById('denominacion').value==""){

			fun_msj('Inserte la Denominaci&oacute;n');
			document.getElementById('denominacion').focus();
			return false;

}else if(document.getElementById('unidad_medida').value==""){

			fun_msj('Inserte la Unidad de Medida');
			document.getElementById('unidad_medida').focus();
			return false;




}else if(document.getElementById('cantidad_estimada').value==""){

			fun_msj('Inserte la Cantidad Estimada');
			document.getElementById('cantidad_estimada').focus();
			return false;



}else if(document.getElementById('costo_financiero').value==""){

			fun_msj('Inserte el Costo Financiero');
			document.getElementById('costo_financiero').focus();
			return false;

}else{





      }





}//fin funtion




function valida2_cfpp09(){
   var mydate=new Date()
   var year1=mydate.getYear()
   var year=mydate.getYear()
      if (year < 1000)
           year+=1901

    if(document.getElementById('denominacion').value==""){

			fun_msj('Inserte la Denominaci&oacute;n');
			document.getElementById('denominacion').focus();
			return false;

}else if(document.getElementById('unidad_medida').value==""){

			fun_msj('Inserte la Unidad de Medida');
			document.getElementById('unidad_medida').focus();
			return false;




}else if(document.getElementById('cantidad_estimada').value==""){

			fun_msj('Inserte la Cantidad Estimada');
			document.getElementById('cantidad_entidad').focus();
			return false;



}else if(document.getElementById('costo_financiero').value==""){

			fun_msj('Inserte el Costo Financiero');
			document.getElementById('costo_total').focus();
			return false;

}else{





      }





}//fin funtion



















function valida_cfpp09_sin(){
   var mydate=new Date()
   var year1=mydate.getYear()
   var year=mydate.getYear()
      if (year < 1000)
           year+=1901

   if(document.getElementById('anoPresupuesto').value==''){

			fun_msj('Inserte el a&ntilde;o presupuestario');
			document.getElementById('anoPresupuesto').focus();
			return false;

	}else if(document.getElementById('anoPresupuesto').value.length<4){

			fun_msj('Inserte un a&ntilde;o correcto');
			document.getElementById('anoPresupuesto').focus();
			return false;


	}else if(document.getElementById('anoPresupuesto').value< 2000 || document.getElementById('anoPresupuesto').value>year){
	fun_msj("Inserte un a&ntilde;o correcto ");
			document.getElementById('anoPresupuesto').focus();
			return false;


}else if(document.getElementById('denominacion').value==""){

			fun_msj('Inserte la Denominaci&oacute;n de la obra');
			document.getElementById('denominacion').focus();
			return false;

}else if(document.getElementById('funcionario_responsable').value==""){

			fun_msj('Inserte el Funcionario Responsable');
			document.getElementById('funcionario_responsable').focus();
			return false;



}else if(document.getElementById('dia_inicio').value=="" || document.getElementById('mes_inicio').value=="" || document.getElementById('year_inicio').value==""){

			fun_msj('Inserte la Fecha de inicio');
			return false;

}else if(document.getElementById('dia_conclusion').value=="" || document.getElementById('mes_conclusion').value=="" || document.getElementById('year_conclusion').value==""){

			fun_msj('Inserte la Fecha de conclusi&oacute;n');
			return false;



}else if(document.getElementById('terminado').checked!=false || document.getElementById('paralizado').checked!=false || document.getElementById('eejecucion').checked!=false || document.getElementById('aejecutarse').checked!=false){

if(document.getElementById('compro_ano_ante').value==""){

			fun_msj('Inserte Comprometidas a&ntilde;os anteriores');
			document.getElementById('compro_ano_ante').focus();
			return false;



}else if(document.getElementById('costo_total').value==""){

			fun_msj('Inserte el costo total');
			document.getElementById('costo_total').focus();
			return false;

}else if(document.getElementById('compro_ano_vige').value==""){

			fun_msj('Inserte Comprometidas a&ntilde;os vigente');
			document.getElementById('compro_ano_vige').focus();
			return false;


}else if(document.getElementById('ejecuta_ano_ante').value==""){

			fun_msj('Inserte Ejecutadas a&ntilde;os anteriores');
			document.getElementById('ejecuta_ano_ante').focus();
			return false;

}else if(document.getElementById('ejecuta_ano_vige').value==""){

			fun_msj('Inserte Ejecutadas a&ntilde;os vigente');
			document.getElementById('ejecuta_ano_vige').focus();
			return false;


}else if(document.getElementById('estimado_presu').value==""){

			fun_msj('Inserte Estimadas a&ntilde;os anteriores');
			document.getElementById('estimado_presu').focus();
			return false;

}else if(document.getElementById('estimado_ano_posterior').value==""){

			fun_msj('Inserte Estimadas a&ntilde;os vigente');
			document.getElementById('estimado_ano_posterior').focus();
			return false;

}else if(document.getElementById('tipo_recurso_1').checked!=false || document.getElementById('tipo_recurso_2').checked!=false || document.getElementById('tipo_recurso_3').checked!=false || document.getElementById('tipo_recurso_4').checked!=false  || document.getElementById('tipo_recurso_5').checked!=false){

    //selec_desmarcar('select_1');
    fun_msj2('Fue Guardado el Registro');

}else{

            fun_msj('Inserte el tipo de registro');
			return false;


      }
}
}//fin funtion







function valida_cfpp09_ano(){
   var mydate=new Date()
   var year1=mydate.getYear()
   var year=mydate.getYear()
      if (year < 1000)
           year+=1901

   if(document.getElementById('anoPresupuesto').value==''){

			fun_msj('Inserte el a&ntilde;o');
			document.getElementById('anoPresupuesto').focus();
			return false;

	}else if(document.getElementById('anoPresupuesto').value.length<4){

			fun_msj('Inserte un a&ntilde;o correcto');
			document.getElementById('anoPresupuesto').focus();
			return false;


	}else if(document.getElementById('anoPresupuesto').value< 2000 || document.getElementById('anoPresupuesto').value>year){
	fun_msj("Inserte un a&ntilde;o correcto ");
			document.getElementById('anoPresupuesto').focus();
			return false;
	}
}//fin funcion valida_cfpp09auxilair





/*function moneda(id){
    var monto = document.getElementById(id).value;
    if(monto!=""){
       pag="include/cfpp09/moneda.php?monto="+monto;
       cargarMonto(id,pag);
    }else{
      document.getElementById(id).value=0;
    }
}*/
function moneda3(id,monto){
    //var monto = document.getElementById(id).value;
    if(monto!=""){
       pag="include/cfpp09/moneda.php?monto="+monto;
       cargarMonto(id,pag);
    }else{
      document.getElementById(id).value=0;
    }
}


function moneda2(){
    var monto = document.getElementById('montoedit').value;
    if(monto!=""){
     pag="../../include/precio_unitario.php?monto="+monto;
     cargarMonto('montoedit',pag);

        return false;
    }else{
      document.getElementById('montoedit').value=0;
      //return false;
    }
}
function limpia1(id){
    var monto = document.getElementById(id).value;
    monto == "0,00" ? document.getElementById(id).value="" : document.getElementById(id).value=monto;

}
function limpia2(id){
    var monto = document.getElementById(id).value;
    monto == "" ? document.getElementById(id).value="0,00" : document.getElementById(id).value=monto;
}

function calcular() {
   var monto = document.getElementById('monto').value;
   var monto_mes = monto / 12;
        monto_mes = redondear(monto_mes,2);
       document.getElementById('a_ene').value = monto_mes;
       document.getElementById('a_feb').value = monto_mes;
       document.getElementById('a_mar').value = monto_mes;
       document.getElementById('a_abr').value = monto_mes;
       document.getElementById('a_may').value = monto_mes;
       document.getElementById('a_jun').value = monto_mes;
       document.getElementById('a_jul').value = monto_mes;
       document.getElementById('a_ago').value = monto_mes;
       document.getElementById('a_sep').value = monto_mes;
       document.getElementById('a_oct').value = monto_mes;
       document.getElementById('a_nov').value = monto_mes;
       document.getElementById('a_dic').value = monto_mes;
       //moneda3('a_ene',monto_mes);
       //moneda3('a_feb',monto_mes);
       //moneda3('a_mar',monto_mes);
       //moneda3('a_abr',monto_mes);
       //moneda3('a_may',monto_mes);
       //moneda3('a_jun',monto_mes);
       //moneda3('a_jul',monto_mes);
       //moneda3('a_ago',monto_mes);
       //moneda3('a_sep',monto_mes);
       //moneda3('a_oct',monto_mes);
       //moneda3('a_nov',monto_mes);
       //moneda3('a_dic',monto_mes);
}
function actualiza_monto(){

        a1 = document.getElementById('a_ene').value;
        a2 = document.getElementById('a_feb').value;
        a3 = document.getElementById('a_mar').value;
        a4 = document.getElementById('a_abr').value;
        a5 = document.getElementById('a_may').value;
        a6 = document.getElementById('a_jun').value;
        a7 = document.getElementById('a_jul').value;
        a8 = document.getElementById('a_ago').value;
        a9 = document.getElementById('a_sep').value;
        a10 = document.getElementById('a_oct').value;
        a11 = document.getElementById('a_nov').value;
        a12 = document.getElementById('a_dic').value;
        var nuevo=0;
        nuevo= eval(a1)+eval(a2)+eval(a3)+eval(a4)+eval(a5)+eval(a6)+eval(a7)+eval(a8)+eval(a9)+eval(a10)+eval(a11)+eval(a12);
        //nuevo =a1+a2+a3+a4+a5+a6+a7+a8+a9+a10+a11+a12;
        document.getElementById('monto').value="";
        document.getElementById('monto').value = nuevo;

}
function saludar_hola(){
     alert('Hola has tomado la opcion de partida');
}


function validar_monto(){
    if(document.getElementById('monto').value=='' || document.getElementById('monto').value=='0,00'){
			fun_msj('Inserte un monto correcto.');
			document.getElementById('monto').focus();
			return false;
	}else if(document.getElementById('select_1').value==''){
			fun_msj('Seleccione Sector');
			document.getElementById('select_1').focus();
			return false;
}else if(document.getElementById('select_2').value == ""){
			fun_msj('Seleccione Programa');
			document.getElementById('select_2').focus();
			return false;
}else if(document.getElementById('select_3').value == ""){
			fun_msj('Seleccione Sub Programa');
			document.getElementById('select_3').focus();
			return false;
}else if(document.getElementById('select_4').value == ""){
			fun_msj('Seleccione Proyecto');
			document.getElementById('select_4').focus();
			return false;
}else if(document.getElementById('select_5').value == ""){
			fun_msj('Seleccione Actividad u Obra');
			document.getElementById('select_5').focus();
			return false;
}else if(document.getElementById('select_6').value == ""){
			fun_msj('Seleccione Partida');
			document.getElementById('select_6').focus();
			return false;
}else if(document.getElementById('select_7').value == ""){
			fun_msj('Seleccione Generica');
			document.getElementById('select_7').focus();
			return false;
}else if(document.getElementById('select_8').value == ""){
			fun_msj('Seleccione Especifica');
			document.getElementById('select_8').focus();
			return false;
}else if(document.getElementById('select_9').value == ""){
			fun_msj('Seleccione Sub-Especifica');
			document.getElementById('select_9').focus();
			return false;
}else{
}
}//fin funcion




function valida_cfpp09_eliminar(){


	fun_msj('Fue Eliminado el Registro ');


}
 function bloquear(){
 	document.getElementById("select_1").diseabled;
 }