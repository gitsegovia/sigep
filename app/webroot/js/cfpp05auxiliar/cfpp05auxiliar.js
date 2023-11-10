
function valida_cfpp05_ano(){
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


	}
	/* else if(document.getElementById('ano').value< 2000 || document.getElementById('ano').value>year){
	fun_msj("Inserte un a&ntilde;o correcto ");
			document.getElementById('ano').focus();
			return false;
	} */
}

function valida_cfpp05auxiliar(){
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


}else if(document.getElementById('cod_auxiliar').value == ""){
            fun_msj('Inserte el codigo de la auxiliar');
			document.getElementById('cod_auxiliar').focus();
			return false;
}else if(document.getElementById('Auxiliar').value == ""){

			fun_msj('Inserte Auxiliar');
			document.getElementById('Auxiliar').focus();
			return false;
}else{
     //selec_desmarcar('select_1');
    // document.getElementById('select_1').seleted.value="";
     //document.getElementById('select_1').options[0].selected=true;
     //document.getElementById('Auxiliar').value="";
     //document.getElementById('cod_auxiliar').value = "";
     //document.getElementById('d_sector').innerHTML="";
     //document.getElementById('d_programa').innerHTML="";
     //document.getElementById('d_subprograma').innerHTML="";
     //document.getElementById('d_proyecto').innerHTML="";
     //document.getElementById('d_actividad').innerHTML="";
     //document.getElementById('d_partida').innerHTML="";
     //document.getElementById('d_generica').innerHTML="";
     //document.getElementById('d_especifica').innerHTML="";
     //document.getElementById('d_subespecifica').innerHTML="";
	}
}//fin funtion

function valida_cfpp05auxiliar_ano(){
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
}//fin funcion valida_cfpp05auxilair



function moneda(id){
    var monto = document.getElementById(id).value;
    if(monto!=""){

       pag="../../include/cfpp05/moneda.php?monto="+monto;
       cargarMonto(id,pag);
    }else{
      document.getElementById(id).value='0,00';
    }
}



function moneda3(id,monto){
    //var monto = document.getElementById(id).value;
    if(monto!=""){

       pag="../../include/cfpp05/moneda.php?monto="+monto;
       cargarMonto(id,pag);
    }else{
      document.getElementById(id).value=0;
    }
}
function moneda2(){
    var monto = document.getElementById('montoedit').value;
    if(monto!=""){

       pag='../../include/cfpp05/moneda.php?monto='+monto;
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
function cambiarTipoGastos(){
     var funcionamiento=document.getElementById('tipo_gasto_1');
     var inversion=document.getElementById('tipo_gasto_2');
     var situadoaentes=document.getElementById('tipo_gasto_3');
     var transferencias=document.getElementById('tipo_gasto_4');
   if(document.getElementById('control_tipo_si').checked==true){
          if(SS()==404){
               inversion.checked=true;
          }else if(SS(transferencias)==407){
               transferencias.checked=true;
          }else{
               funcionamiento.checked=true;
          }
    }else{

          }//fin else pricipal
}
function SS(xx){
    var ssx=document.getElementById('select_6').options[document.getElementById('select_6').selectedIndex].value;
     //var ssx=document.getElementById(xx).value;
    return ssx;
}

function validar_monto(){
    if(document.getElementById('monto').value==''){
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
}else if(document.getElementById('select_10').value == "" && document.getElementById('select_10').length >=1){
			fun_msj('Seleccione Auxiliar');
			document.getElementById('select_10').focus();
			return false;
}else{
}
}//fin funcion


function sig(n){
    	//var n;
    	switch(n){
    	 case 1:
    	    if(document.getElementById('codigo1').value.length==1){
    		    document.getElementById('codigo2').focus();
    	    }

    	 break;
    	 case 2:
    	    if(document.getElementById('codigo2').value.length==1){
    		    document.getElementById('codigo3').focus();
    	    }

    	 break;
    	 case 3:
    	    if(document.getElementById('codigo3').value.length==1){
    		    document.getElementById('codigo4').focus();
    	    }

    	 break;
    	 case 4:
    	    if(document.getElementById('codigo4').value.length==1){
    		    document.getElementById('codigo5').focus();
    	    }

    	 break;
    	 case 5:
    	    if(document.getElementById('codigo5').value.length==1){
    		    document.getElementById('codigo6').focus();
    	    }

    	 break;
    	 case 6:
    	    if(document.getElementById('codigo6').value.length==2){
    		    document.getElementById('codigo7').focus();
    	    }

    	 break;
    	 case 7:
    	    if(document.getElementById('codigo7').value.length==1){
    		    document.getElementById('codigo8').focus();
    	    }

    	 break;
    	 case 8:
    	    if(document.getElementById('codigo8').value.length==1){
    		    document.getElementById('codigo9').focus();
    	    }

    	 break;
    	 case 9:
    	    if(document.getElementById('codigo9').value.length==1){
    		    document.getElementById('codigo10').focus();
    	    }

    	 break;

    	}
    }

/****
+
+Funcion para el reporte2 / balance de ejecucion
+
****/

function condicion_balance_ejecucion(){
     if(document.getElementById('modo_1').checked==true){
        for(var i=1;i<11;i++)
           document.getElementById('seleccion_'+i).disabled="disabled";
     }else if(document.getElementById('modo_2').checked==true){
        for(var i=1;i<6;i++)
           document.getElementById('seleccion_'+i).disabled="";

        for(var i=6;i<11;i++)
           document.getElementById('seleccion_'+i).disabled="disabled";
     }else if(document.getElementById('modo_3').checked==true){
        for(var i=1;i<11;i++)
           document.getElementById('seleccion_'+i).disabled="";

     }else if(document.getElementById('modo_4').checked==true){
        for(var i=1;i<11;i++)
           document.getElementById('seleccion_'+i).disabled="disabled";


           document.getElementById('seleccion_6').disabled="";
     }else if(document.getElementById('modo_5').checked==true){
       for(var i=1;i<6;i++)
           document.getElementById('seleccion_'+i).disabled="disabled";

        for(var i=6;i<11;i++)
           document.getElementById('seleccion_'+i).disabled="";
     }


}//fin function

function condicion_balance_ejecucion2(){
     if(document.getElementById('modo_2').checked==true){
        for(var i=1;i<6;i++)
           document.getElementById('seleccion_'+i).disabled="";

        for(var i=6;i<11;i++)
           document.getElementById('seleccion_'+i).disabled="disabled";
     }else if(document.getElementById('modo_3').checked==true){
        for(var i=1;i<11;i++)
           document.getElementById('seleccion_'+i).disabled="";

     }else if(document.getElementById('modo_4').checked==true){
        for(var i=1;i<11;i++)
           document.getElementById('seleccion_'+i).disabled="disabled";


           document.getElementById('seleccion_6').disabled="";
     }else if(document.getElementById('modo_5').checked==true){
       for(var i=1;i<6;i++)
           document.getElementById('seleccion_'+i).disabled="disabled";

        for(var i=6;i<11;i++)
           document.getElementById('seleccion_'+i).disabled="";
     }


}//fin function


function add_cero(n){
    if(n<10){
       return '0'+n;
    }else{
       return ''+n;
    }
}///

function elimina_fila_auxiliar(fila,a_i){
    document.getElementById('a_spacer_gif'+a_i).style.display="none";
    document.getElementById('agregar_fila_'+a_i).style.display="inline";
	var tabla = document.getElementById('grid_auxiliares');
	tabla.deleteRow(fila.rowIndex);
}

function agregar_fila_auxiliar(fila,codigo_nuevo_aux,url,a_i){
    //document.getElementById('agregar_fila_'+a_i).onclick="return false;";
    var separar_url=url.split('/');
    document.getElementById('agregar_fila_'+a_i).style.display="none";
    document.getElementById('a_spacer_gif'+a_i).style.display="inline";
	var tabla = document.getElementById('grid_auxiliares');
	var nuevaFila = tabla.insertRow(fila.rowIndex+1);
        nuevaFila.className=fila.className=="tr_grid_c1"?"tr_grid_c2":"tr_grid_c1";
        var X=''+Math.random();
        R=X.replace('.','');
        nuevaFila.id="nueva_fila_"+R;
	var celda;

    celda = nuevaFila.insertCell(-1);
    celda.innerHTML = ''+add_cero(separar_url[1]);
    celda.className="mensaje_resaltado";
    celda.style.textAlign="center";
	for (var i=2; i<=10; i++){
		celda = nuevaFila.insertCell(-1);
		celda.innerHTML = ''+add_cero(separar_url[i]);
		celda.style.textAlign="center";
	}

	celda = nuevaFila.insertCell(-1);
	celda.style.textAlign="center";
	c='<input type="submit" url1="/cfpp05auxiliarv2/guardar_auxiliar_grid/'+url+'/'+R+'/nueva_fila_'+R+'" update1="nueva_fila_'+R+'" id="guardar_auxiliar_'+R+'" class="guardar_fila" value="" loading="Element.show(\'mini_loading\');" complete="Element.hide(\'mini_loading\');" onclick="return guardar_auxiliar_grid(event,\'guardar_auxiliar_'+R+'\',\'nueva_fila_'+R+'\',\'/cfpp05auxiliarv2/guardar_auxiliar_grid/'+url+'/'+R+'/nueva_fila_'+R+'\');" />';
//    c2='<script type="text/javascript">  Event.observe(\'guardar_auxiliar_'+R+'\', \'click\', function(event){ new Ajax.Updater(\'guardar_auxiliar_'+R+'\', \'click\',   \'\'   , \'nueva_fila_'+R+'\',\'/cfpp05auxiliarv2/guardar_auxiliar_grid/'+url+'\', {asynchronous:true, evalScripts:true, onLoading:function(request){Element.show(\'mini_loading\');}, onComplete:function(request){Element.hide(\'mini_loading\');}, parameters:Form.serialize(Event.element(event).form), requestHeaders:[\'X-Update\',\'nueva_fila_'+R+'\']}) }, false);</script>';
	celda.innerHTML = c+'<a href="#delRow" onclick="elimina_fila_auxiliar(this.parentNode.parentNode,'+a_i+');"><img src="/img/cancela_fila.png" title="Cancelar Registro" border="0" /></a><img src="/img/spacer.gif" width="20" height="20" id="a_spacer_gif'+a_i+'" style="display:none;"';
    celda = nuevaFila.insertCell(-1);
    celda.style.textAlign="center";
    celda.innerHTML = '<input name="data[cfpp05auxiliarv2][cod_auxiliar_'+R+']" value="'+codigo_nuevo_aux+'" size="2" maxlength="4" id="cod_auxiliar" onBlur="no_cero_aux(this);" onKeyPress="return solonumeros(event);" />';
    celda = nuevaFila.insertCell(-1);
    celda.innerHTML = '<textarea name="data[cfpp05auxiliarv2][auxiliar_'+R+']" cols="29%" wrap="on" id="Auxiliar" class="select100" onclick="if(this.value.toUpperCase()==\'ESCRIBA NUEVO AUXILIAR\')this.value=\'\';" onblur="if(this.value==\'\')this.value=\'Escriba Nuevo Auxiliar\';">Escriba Nuevo Auxiliar</textarea>';
//alert(url);
}//fin agregar


function guardar_auxiliar_grid(event,id_boton,id_cargar,url){
//alert(url);
       new Ajax.Updater(id_boton,
       'click',
       ''   ,
       id_cargar,
       url,
       {asynchronous:true,
       	evalScripts:true,
       	onLoading:function(request){Element.show('mini_loading');},
       	onComplete:function(request){Element.hide('mini_loading');},
       	parameters:Form.serialize(Event.element(event).form),
       	requestHeaders:['X-Update', id_cargar]}, session_update());
       	return false;
}


function eliminar_registro_auxiliar_grid(url, id_update){
         if(!confirm("Esta seguro que desea eliminar este registro")){
         	return false;
         }else{
         	ver_documento(url,id_update);
         }
}

function valida_pasar_ano_ejercicio(){
    if(document.getElementById('anoPresupuestoPasar').value!='' && document.getElementById('anoPresupuesto').value!='' && eval(document.getElementById('anoPresupuestoPasar').value)>eval(document.getElementById('anoPresupuesto').value)){
       //return true;
    }else{
       if(document.getElementById('anoPresupuestoPasar').value==''){
       	fun_msj('Ingrese el a&ntilde;o del ejercicio fiscal a pasar');
       }
       if(document.getElementById('anoPresupuesto').value==''){
       	fun_msj('Ingrese el a&ntilde;o del ejercicio fiscal');
       }
       if(document.getElementById('anoPresupuestoPasar').value>document.getElementById('anoPresupuesto').value){
       	fun_msj('El a&ntilde;o del ejercicio fiscal debe ser menor al a&ntilde;o a pasar');
       }
       return false;
    }
}

function no_cero_aux(obj){
    if(obj.value=="0" || obj.value=="00"  || obj.value=="000" || obj.value=="0000"){
          fun_msj('Por favor, ingrese El Codigo para el Auxiliar mayor que cero');
    }
}

function adp_seleccion(){
     if(document.getElementById('modo_1').checked==true){
        for(var i=1;i<10;i++)
           document.getElementById('seleccion_'+i).disabled="disabled";
     }else if(document.getElementById('modo_2').checked==true){
        for(var i=1;i<6;i++)
           document.getElementById('seleccion_'+i).disabled="";

        for(var i=6;i<10;i++)
           document.getElementById('seleccion_'+i).disabled="disabled";
     }else if(document.getElementById('modo_3').checked==true){
        for(var i=1;i<10;i++)
           document.getElementById('seleccion_'+i).disabled="";

     }else if(document.getElementById('modo_4').checked==true){
        for(var i=1;i<10;i++){
           document.getElementById('seleccion_'+i).disabled="disabled";
           }

           document.getElementById('seleccion_6').disabled="";
     }else if(document.getElementById('modo_5').checked==true){
       for(var i=1;i<6;i++)
           document.getElementById('seleccion_'+i).disabled="disabled";

        for(var i=6;i<10;i++)
           document.getElementById('seleccion_'+i).disabled="";
     }


}//fin function



function validar_pista_busqueda(){
    var pb=$('pista_busqueda').value;
    if(pb==""){
       fun_msj('Por favor, ingrese la pista a buscar');
       $('pista_busqueda').focus();
       return false;
    }
}