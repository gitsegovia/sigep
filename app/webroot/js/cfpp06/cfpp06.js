function valida_cfpp06_ano(){
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



function valida_cfpp06(){
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

			fun_msj('Seleccione Actividad u Obrasssss');
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



}else if(document.getElementById('select_9').value == ""){

			fun_msj('Seleccione Sub-Especifica');
			document.getElementById('select_9').focus();
			return false;



}else if(document.getElementById('cantidad_reemplazo').value==""){

			fun_msj('Inserte la Cantidad a Reemplazo');
			document.getElementById('cantidad_reemplazo').focus();
			return false;
}else if(document.getElementById('cantidad_deficiencia').value==""){
			fun_msj('Inserte la Cantidad por Deficiencia');
			document.getElementById('cantidad_deficiencia').focus();

			return false;



}else if(document.getElementById('numero_total_equipos').value==""){

			fun_msj('Inserte el numero total de equipos');
			document.getElementById('numero_total_equipos').focus();
			return false;

}else if(document.getElementById('costo_unitario').value==""){

			fun_msj('Inserte el costo unitario');
			document.getElementById('costo_unitario').focus();
			return false;

}else if(document.getElementById('monto_total').value==""){

			fun_msj('Inserte el monto total');
			document.getElementById('monto_total').focus();
			return false;

}else if(document.getElementById('denominacion').value==""){

			fun_msj('Inserte la Denominaci&oacute;n');
			document.getElementById('denominacion').focus();
			return false;

}

}//fin funtion


function validar_guardar(){
if(document.getElementById('cantidad_reemplazo').value==""){

			fun_msj('Inserte la Cantidad a Reemplazo');
			document.getElementById('cantidad_reemplazo').focus();
			return false;
}else if(document.getElementById('cantidad_deficiencia').value==""){
			fun_msj('Inserte la Cantidad por Deficiencia');
			document.getElementById('cantidad_deficiencia').focus();

			return false;



}else if(document.getElementById('numero_total_equipos').value==""){

			fun_msj('Inserte el numero total de equipos');
			document.getElementById('numero_total_equipos').focus();
			return false;

}else if(document.getElementById('costo_unitario').value==""){

			fun_msj('Inserte el costo unitario');
			document.getElementById('costo_unitario').focus();
			return false;

}else if(document.getElementById('monto_total').value==""){

			fun_msj('Inserte el monto total');
			document.getElementById('monto_total').focus();
			return false;

}else if(document.getElementById('denominacion').value==""){

			fun_msj('Inserte la Denominaci&oacute;n');
			document.getElementById('denominacion').focus();
			return false;

		}
}//fin funcion


function monto_total(id_a, id_b, id_c){

	a=document.getElementById(id_a).value;
	b=document.getElementById(id_b).value;

	c=eval(a)*eval(b);

	document.getElementById(id_c).value=c;


}

function cargar_suma_m(id_a, id_b, id_c){

  a=0;
  b=0;


  a = document.getElementById(id_a).value;
  b = document.getElementById(id_b).value;

var str = a;
for(i=0; i<a.length; i++){
    str = str.replace('.','');
}
str = str.replace(',','.');
var a = redondear(str,2);

var str = b;
for(i=0; i<b.length; i++){
    str = str.replace('.','');
}
str = str.replace(',','.');
var b = redondear(str,2);



  document.getElementById(id_c).value = eval(a) + eval(b);
  //moneda(id_c);


}





function monto_total_cfpp06(id_a, id_b, id_c){

  a=0;
  b=0;


  a = document.getElementById(id_a).value;
  b = document.getElementById(id_b).value;

  //b =  moneda(id_b);


var str = b;
for(i=0; i<b.length; i++){
    str = str.replace('.','');
}
str = str.replace(',','.');
var b = redondear(str,2);

//alert(a);alert(b);

  document.getElementById(id_c).value = eval(a) * eval(b);
  moneda(id_c);


}







function valida_cfpp06_sin(){
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







function valida_cfpp06_ano(){
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
       pag="include/cfpp06/moneda.php?monto="+monto;
       cargarMonto(id,pag);
    }else{
      document.getElementById(id).value=0;
    }
}*/
function moneda3(id,monto){
    //var monto = document.getElementById(id).value;
    if(monto!=""){
       pag="include/cfpp06/moneda.php?monto="+monto;
       cargarMonto(id,pag);
    }else{
      document.getElementById(id).value=0;
    }
}
function moneda2(){
    var monto = document.getElementById('montoedit').value;
    if(monto!=""){
       pag='include/cfpp06/moneda.php?monto='+monto;
       cargarMonto2(pag);
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
			fun_msj('Seleccione Sectorrrr');
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
			fun_msj('Seleccione Actividad u Obrassss');
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




function valida_cfpp06_eliminar(){


	fun_msj('Fue Eliminado el Registro ');


}


function valida_costo_equip2(){

   if(document.getElementById('ejercicio').value==''){

			fun_msj('Seleccione el ejercicio presupuestario formulado');
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

			fun_msj('Seleccione Sub-Programa');
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

			fun_msj('Seleccione GEN�RICA');
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



	}else if(document.getElementById('select_9').value == ""){

			fun_msj('Seleccione Sub-Especifica');
			document.getElementById('select_9').focus();
			return false;

	}else if(document.getElementById('select_10').value == ""){

			fun_msj('Seleccione auxiliar');
			document.getElementById('select_9').focus();
			return false;

	}else if((document.getElementById('reemplazo').value=="") && (document.getElementById('deficiencia').value=="")){

			fun_msj('Inserte Reemplazo o Deficiencia');
			document.getElementById('reemplazo').focus();
			return false;
	}else if((document.getElementById('deficiencia').value==0) && (document.getElementById('reemplazo').value==0)){

			fun_msj('la Deficiencia y el reemplazo no pueden ser cero las dos cantidades');
			document.getElementById('deficiencia').focus();

			return false;

	}else if(document.getElementById('total_equipos').value==""){

			fun_msj('Inserte total de equipos');
			document.getElementById('total_equipos').focus();
			return false;

	}else if(document.getElementById('costo_unitario').value==""){

			fun_msj('Inserte costo unitario');
			document.getElementById('costo_unitario').focus();
			return false;

	}else if(document.getElementById('descripcion').value==""){

			fun_msj('Inserte Descripci&oacute;n del equipo');
			document.getElementById('descripcion').focus();
			return false;

}

}//fin funtion

function total_equipos9(){

if($("reemplazo").value==''){
	a=0;
}else{
	a =$("reemplazo").value;
}

if($("deficiencia").value==''){
	b=0;
}else{
	b =$("deficiencia").value;
}

c = eval(a) + eval(b);

document.getElementById("total_equipos").value      = c;
document.getElementById("costo_unitario").value      = '';
document.getElementById("total_costo").value      = '';

}

function total_costo9(){

if($("reemplazo").value==''){
	a=0;
}else{
	a =$("reemplazo").value;
}

if($("deficiencia").value==''){
	b=0;
}else{
	b =$("deficiencia").value;
}

c = eval(a) + eval(b);

//a = $("total_equipos").value;
if($("costo_unitario").value==''){
d=0;
}else{
d = retornar_valor_calculo( $("costo_unitario").value);
}
e = eval(c) * eval(d);
document.getElementById("total_equipos").value      = c;
document.getElementById("total_costo").value      = e;

moneda("costo_unitario");
moneda("total_costo");

}










/*
function total_equipos99(i){

if($("reemplazo_"+i).value==''){
	a=0;
}else{
	a =$("reemplazo_"+i).value;
}

if($("deficiencia_"+i).value==''){
	b=0;
}else{
	b =$("deficiencia_"+i).value;
}

c = eval(a) + eval(b);

document.getElementById("total_equipos_"+i).value      = c;
document.getElementById("costo_unitario_"+i).value      = '';
document.getElementById("total_costo_"+i).value      = '';

}

function total_costo99(i){

a = $("total_equipos_"+i).value;
b = retornar_valor_calculo( $("costo_unitario_"+i).value);

c = eval(a) * eval(b);

document.getElementById("total_costo_"+i).value      = c;

moneda("costo_unitario_"+i);
moneda("total_costo_"+i);

}

*/


function total_costo99(i){

if($("reemplazo_"+i).value==''){
	a=0;
}else{
	a =$("reemplazo_"+i).value;
}

if($("deficiencia_"+i).value==''){
	b=0;
}else{
	b =$("deficiencia_"+i).value;
}

c = eval(a) + eval(b);

//a = $("total_equipos").value;
if($("costo_unitario_"+i).value==''){
d=0;
}else{
d = retornar_valor_calculo( $("costo_unitario_"+i).value);
}
e = eval(c) * eval(d);
document.getElementById("total_equipos_"+i).value      = c;
document.getElementById("total_costo_"+i).value      = e;

moneda("costo_unitario_"+i);
moneda("total_costo_"+i);

}


function valida_cfpp16(){

   if(document.getElementById('ejercicio').value==''){

			fun_msj('Seleccione el ejercicio presupuestario formulado');
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

			fun_msj('Seleccione Sub-Programa');
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

			fun_msj('Seleccione GEN&Eacute;RICA');
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



	}else if(document.getElementById('select_9').value == ""){

			fun_msj('Seleccione Sub-Especifica');
			document.getElementById('select_9').focus();
			return false;

	}else if(document.getElementById('select_10').value == ""){

			fun_msj('Seleccione auxiliar');
			document.getElementById('select_9').focus();
			return false;

	}else if(document.getElementById('nombre_consejo').value==""){

			fun_msj('Ingrese NOMBRE DEL CONSEJO COMUNAL O MANCOMUNIDAD DE CONSEJOS COMUNALES');
			document.getElementById('nombre_consejo').focus();
			return false;

	}else if(document.getElementById('nombre_banco').value==""){

			fun_msj('Ingrese NOMBRE DEL BANCO COMUNAL O MANCOMUNICAD DE CONSEJOS COMUNALES');
			document.getElementById('nombre_banco').focus();
			return false;

	}else if(document.getElementById('nombre_proyecto').value==""){

			fun_msj('Ingrese NOMBRE DEL PROYECTO');
			document.getElementById('nombre_proyecto').focus();
			return false;

	}else if(document.getElementById('ente').value==""){

			fun_msj('Ingrese ENTE FINANCIANTE');
			document.getElementById('ente').focus();
			return false;

	}else if(document.getElementById('deno_obra').value==""){

			fun_msj('Ingrese DENOMINACI&Oacute;N DE LA OBRA');
			document.getElementById('deno_obra').focus();
			return false;

	}else if(document.getElementById('monto').value==""){

			fun_msj('Ingrese MONTO');
			document.getElementById('monto').focus();
			return false;

	}

}//fin funtion


function total_17(){

if($("aporte_municipio").value==''){
	a=0;
}else{
	a =retornar_valor_calculo($("aporte_municipio").value);
}

if($("aporte_organismo").value==''){
	b=0;
}else{
	b =retornar_valor_calculo($("aporte_organismo").value);
}

if($("aporte_gobernacion").value==''){
	c=0;
}else{
	c =retornar_valor_calculo($("aporte_gobernacion").value);
}

d = eval(a) + eval(b) + eval(c);

document.getElementById("aporte_total").value      = d;

moneda("aporte_municipio");
moneda("aporte_organismo");
moneda("aporte_gobernacion");
moneda("aporte_total");

}


function valida_cfpp17_inversion_coordinada(){

   if(document.getElementById('ejercicio').value==''){

			fun_msj('Seleccione el ejercicio presupuestario formulado');
			document.getElementById('anoPresupuesto').focus();
			return false;

	}else if(document.getElementById('select_estado').value==''){

			fun_msj('Seleccione estado');
			document.getElementById('select_estado').focus();
			return false;

	}else if(document.getElementById('select_organismo').value==''){

			fun_msj('Seleccione organismo');
			document.getElementById('select_organismo').focus();
			return false;

	}else if(document.getElementById('select_municipio').value==''){

			fun_msj('Seleccione municipio');
			document.getElementById('select_municipio').focus();
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

			fun_msj('Seleccione Sub-Programa');
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

			fun_msj('Seleccione GEN&Eacute;RICA');
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

	}else if(document.getElementById('select_9').value == ""){

			fun_msj('Seleccione Sub-Especifica');
			document.getElementById('select_9').focus();
			return false;

	}else if(document.getElementById('select_10').value == ""){

			fun_msj('Seleccione auxiliar');
			document.getElementById('select_9').focus();
			return false;

	}else if(document.getElementById('aporte_municipio').value==""){

			fun_msj('Ingrese APORTE DEL MUNICIPIO');
			document.getElementById('aporte_municipio').focus();
			return false;

	}else if(document.getElementById('aporte_organismo').value==""){

			fun_msj('Ingrese APORTE DEL ORGANISMO');
			document.getElementById('aporte_organismo').focus();
			return false;

	}else if(document.getElementById('aporte_gobernacion').value==""){

			fun_msj('Ingrese APORTE DE LA GOBERNACI&Oacute;N');
			document.getElementById('aporte_gobernacion').focus();
			return false;

	}

}//fin funtion


function valida_grilla_clausulas(){
    if(document.getElementById('ejercicio').value==''){
			fun_msj('Por favor seleccione el a&ntilde;o');
			document.getElementById('ejercicio').focus();
			return false;
	}else if(document.getElementById('select_sindicato').value==''){
			fun_msj('Por favor seleccione el sindicato');
			document.getElementById('select_sindicato').focus();
			return false;
	}else if(document.getElementById('deno_clausula').value==''){
			fun_msj('Ingrese DENOMINACI&Oacute;N DE LA CL&Aacute;SULA');
			document.getElementById('deno_clausula').focus();
			return false;
	}
}//fin funcion

function valida_grilla_contrato_colectivo(){
    if(document.getElementById('select_sindicato').value==''){
			fun_msj('seleccione el sindicato');
			document.getElementById('select_sindicato').focus();
			return false;
	}else if(document.getElementById('fecha_inicio').value==''){
			fun_msj('seleccione la fecha de inicio');
			document.getElementById('fecha_inicio').focus();
			return false;
	}else if(document.getElementById('fecha_conclusion').value==''){
			fun_msj('seleccione la fecha de conclusi&oacute;n');
			document.getElementById('fecha_conclusion').focus();
			return false;
	}else if(document.getElementById('cod_clausula').value==''){
			fun_msj('seleccione C&Oacute;DIGO DE LA CL&Aacute;SULA');
			document.getElementById('cod_clausula').focus();
			return false;
	}else if(document.getElementById('select_1').value == ""){

			fun_msj('Seleccione Partida');
			document.getElementById('select_1').focus();
			return false;

	}else if(document.getElementById('select_2').value == ""){

			fun_msj('Seleccione GEN&Eacute;RICA');
			document.getElementById('select_2').focus();
			return false;

	}else if(document.getElementById('select_3').value == ""){

			fun_msj('Seleccione Especifica');
			document.getElementById('select_3').focus();
			return false;

	}else if(document.getElementById('select_4').value == ""){

			fun_msj('Seleccione Sub-Especifica');
			document.getElementById('select_4').focus();
			return false;

	}else if(document.getElementById('revisado').value == ""){

			fun_msj('ingrese REVISADO ANTERIOR');
			document.getElementById('revisado').focus();
			return false;
	}else if(document.getElementById('presupuesto').value == ""){

			fun_msj('ingrese PRESUPUESTO ACTUAL');
			document.getElementById('presupuesto').focus();
			return false;
	}

}//fin funcion

function valida_participacion_financiera(){
    if(document.getElementById('nombre').value==''){
			fun_msj('Ingrese nombre del organismo');
			document.getElementById('nombre').focus();
			return false;
	}else if(document.getElementById('ubicacion').value==''){
			fun_msj('Ingrese ubicaci&oacute;n geogr&aacute;fica del organismo');
			document.getElementById('ubicacion').focus();
			return false;
	}else if(document.getElementById('tipo').value==''){
			fun_msj('Ingrese tipo de organismo');
			document.getElementById('tipo').focus();
			return false;
	}else if(document.getElementById('capital').value==''){
			fun_msj('ingrese CAPITAL SOCIAL O PRESUPUESTO ANUAL del municipio');
			document.getElementById('capital').focus();
			return false;
	}else if(document.getElementById('cuota').value==''){
			fun_msj('ingrese CUOTA DE PARTICIPACI&oacute;N del municipio');
			document.getElementById('cuota').focus();
			return false;
	}else if(document.getElementById('porcentaje').value==''){
			fun_msj('ingrese porcentaje');
			document.getElementById('porcentaje').focus();
			return false;
	}else if(document.getElementById('select_1').value == ""){

			fun_msj('Seleccione sector');
			document.getElementById('select_1').focus();
			return false;

	}else if(document.getElementById('select_2').value == ""){

			fun_msj('Seleccione programa');
			document.getElementById('select_2').focus();
			return false;

	}else if(document.getElementById('select_3').value == ""){

			fun_msj('Seleccione sub-programa');
			document.getElementById('select_3').focus();
			return false;

	}else if(document.getElementById('select_4').value == ""){

			fun_msj('Seleccione partida');
			document.getElementById('select_4').focus();
			return false;

	}

}//fin funcion
