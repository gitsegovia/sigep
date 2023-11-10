function s_factura(){
   pag='../../include/cfpp05/moneda.php?monto=';
   if(document.getElementById("num_factura").value!=""){
      document.getElementById("num_control").value=document.getElementById("num_factura").value;
      var m_base=0;
      var m_iva=0;
      var exc=0;
      var suma1=0;
      var suma2=0;
      var suma3=0;
      var suma4=0;
      var suma=0;
      var base=reemplazarPC(document.getElementById("monto_descontar_impuesto").value);
      var total=reemplazarPC(document.getElementById("monto_orden_pago").value);

//GOB.GUARICO
	  suma1 = reemplazarPC(document.getElementById("monto_orden_pago").value);
      suma2 = reemplazarPC(document.getElementById('monto_amortizacion_antipo').value);
      suma3 = reemplazarPC(document.getElementById('monto_laboral').value);
      suma4 = reemplazarPC(document.getElementById('monto_fiel_cumplimiento').value);
      suma = eval(suma1)+eval(suma2)+eval(suma3)+eval(suma4);
      suma = redondear(suma, 2);
      total=reemplazarPC(suma);
//FIN GOB.GUARICO


//ORIGINAL
/*
if(document.getElementById("monto_amortizacion_antipo").value!="" && (document.getElementById("monto_laboral").value!="" || document.getElementById("monto_fiel_cumplimiento").value!="")){
      suma1 = reemplazarPC(document.getElementById("monto_orden_pago").value);
      suma2 = reemplazarPC(document.getElementById('monto_amortizacion_antipo').value);
      suma = eval(suma1)+eval(suma2);
      suma = redondear(suma, 2);
      total=reemplazarPC(suma);
}

if(document.getElementById("monto_amortizacion_antipo").value!="" && document.getElementById("monto_laboral").value=="" && document.getElementById("monto_fiel_cumplimiento").value==""){
     total=reemplazarPC(document.getElementById("monto_total_cancelar").value);
}
*/
//FIN ORIGINAL

      cargarMonto('monto_total',pag+total);

      if(document.getElementById("f_iva").value!=""){
           m_base=base;
           m_iva=reemplazarPC(document.getElementById('t_monto_iva').value);
           m_iva = redondear(m_iva, 2);
           exc=eval(total)-(eval(m_base)+eval(m_iva));
           exc = redondear(exc, 2);
           cargarMonto('monto_base',pag+redondear(m_base,2));
           cargarMonto('monto_iva',pag+m_iva);
           cargarMonto('excento',pag+exc);
           cargarMonto('f_iva',pag+document.getElementById("f_iva").value);
      }else{
         document.getElementById("f_iva").value=reemplazarPC(document.getElementById("porcentaje_iva").value);
         m_base=base;
         m_iva=reemplazarPC(document.getElementById('t_monto_iva').value);
         m_iva = redondear(m_iva, 2);
         exc=eval(total)-(eval(m_base)+eval(m_iva));
         exc = redondear(exc, 2);
         cargarMonto('monto_base',pag+redondear(m_base,2));
         cargarMonto('monto_iva',pag+m_iva);
         cargarMonto('excento',pag+exc);
         cargarMonto('f_iva',pag+document.getElementById("f_iva").value);
       }
  	}
//s_factura();
}


function s_factura_cambio(){
   pag='../../include/cfpp05/moneda.php?monto=';
   //alert(document.getElementById("monto_total").value);
   var X0=reemplazarPC(document.getElementById("monto_total").value);//reemplazarPC(document.getElementById("monto_total").value);
   var X1=reemplazarPC(document.getElementById("monto_base").value);
   var X2=reemplazarPC(document.getElementById("monto_iva").value);
   var X3=reemplazarPC(document.getElementById("excento").value);
   var X4=reemplazarPC(document.getElementById("f_iva").value);
   var X5=reemplazarPC(document.getElementById("porcentaje_iva").value);
   //alert(X0+" "+X1+" "+X2+" "+X3+" "+X4+" "+X5+" ");
  if(document.getElementById("monto_base").value!=""){
    document.getElementById("plus").disabled="";
      if(document.getElementById("f_iva").value!=""){
           X2=eval(X1)*(X4/100);
           X2 = redondear(X2, 2);
           X3=eval(X0)-(eval(X1)+eval(X2));
           X1=redondear(X1,2);
           X3=X3;
           X3 = redondear(X3, 2);
           cargarMonto('monto_base',pag+X1);
           cargarMonto('monto_iva',pag+X2);
           cargarMonto('excento',pag+X3);
           cargarMonto('f_iva',pag+X4);
           //alert("UNO "+X0+" "+X1+" "+X2+" "+X3+" "+X4+" "+X5+" ");
      }else{
         document.getElementById("f_iva").value=reemplazarPC(document.getElementById("porcentaje_iva").value);
         X2=eval(X1)*(X5/100);
         X2 = redondear(X2, 2);
         X3=eval(X0)-(eval(X1)+eval(X2));
         X3=X3;
         X3 = redondear(X3, 2);
         cargarMonto('monto_base',pag+X1);
         cargarMonto('monto_iva',pag+X2);
         cargarMonto('excento',pag+X3);
         cargarMonto('f_iva',pag+X4);
        // alert("DOS "+X0+" "+X1+" "+X2+" "+X3+" "+X4+" "+X5+" ");
      }
  }
 // alert("TRES "+X0+" "+X1+" "+X2+" "+X3+" "+X4+" "+X5+" ");
}//
function s_factura_cambio2(){
  pag='../../include/cfpp05/moneda.php?monto=';
  var  X0=reemplazarPC(document.getElementById("monto_total").value);
   var X1=reemplazarPC(document.getElementById("monto_base").value);
   var X2=reemplazarPC(document.getElementById("monto_iva").value);
   var X3=reemplazarPC(document.getElementById("excento").value);
   var X4=reemplazarPC(document.getElementById("f_iva").value);
   var X5=reemplazarPC(document.getElementById("porcentaje_iva").value);
  if(document.getElementById("monto_total").value!=""){
    document.getElementById("plus").disabled="";
      if(document.getElementById("f_iva").value!=""){
           X2 = redondear(X2, 2);
           X3=eval(X0)-(eval(X1)+eval(X2));
           X3 = redondear(X3, 2);
           cargarMonto('excento',pag+X3);
           cargarMonto('monto_total',pag+redondear(X0,2));
      }else{
         //document.getElementById("excento").value=eval(document.getElementById("monto_total").value)-(eval(document.getElementById("monto_base").value)+eval(document.getElementById("monto_iva").value));
         //document.getElementById("excento").value=redondear_dec(document.getElementById("excento").value,2);
          X2 = redondear(X2, 2);
          X3=eval(X0)-(eval(X1)+eval(X2));
          X3 = redondear(X3, 2);
          cargarMonto('excento',pag+X3);
          cargarMonto('monto_total',pag+redondear(X0,2));
      }
  }
}//
function s_factura_cambio3(){
  pag='../../include/cfpp05/moneda.php?monto=';
   var X0=reemplazarPC(document.getElementById("monto_total").value);
   var X1=reemplazarPC(document.getElementById("monto_base").value);
   var X2=reemplazarPC(document.getElementById("monto_iva").value);
   var X3=reemplazarPC(document.getElementById("excento").value);
   var X4=reemplazarPC(document.getElementById("f_iva").value);
   var X5=reemplazarPC(document.getElementById("porcentaje_iva").value);
  if(document.getElementById("f_iva").value!=""){
    document.getElementById("plus").disabled="";
      if(document.getElementById("monto_base").value!=""){
           X2=eval(X1)*(X4/100);
           X2 = redondear(X2, 2);
           X3=eval(X0)-(eval(X1)+eval(X2));
           X3 = redondear(X3, 2);
           cargarMonto('monto_base',pag+X1);
           cargarMonto('monto_iva',pag+X2);
           cargarMonto('excento',pag+X3);
           cargarMonto('f_iva',pag+X4);

      }else{
         if(document.getElementById("monto_total").value!=""){
	         X1=eval(X0)/((X4/100)+1);
	         X2=eval(X1)*(X4/100);
	         X2 = redondear(X2, 2);
	         X3=eval(X0)-(eval(X1)+eval(X2));
	         X3 = redondear(X3, 2);
	         cargarMonto('monto_base',pag+redondear(X1,2));
             cargarMonto('monto_iva',pag+X2);
             cargarMonto('excento',pag+X3);
             cargarMonto('f_iva',pag+X4);
	     }else{
             fun_msj('no se puede realizar ninguna operacion, hace falta monto base o total');
		     //document.getElementById('plazo').focus();
	     }
      }
  }
}//

function s_factura_cambio4(){
           pag='../../include/cfpp05/moneda.php?monto=';
           var E=eval(reemplazarPC(document.getElementById("monto_total").value))-(eval(reemplazarPC(document.getElementById("monto_base").value))+eval(reemplazarPC(document.getElementById("monto_iva").value)));
           //document.getElementById("excento").value=redondear(E,2);
           cargarMonto('excento',pag+redondear(E,2));
}//

function verificar_factura(){
    if(document.getElementById("num_factura").value!="" && document.getElementById("num_control").value!="" && document.getElementById("monto_total").value!="" && document.getElementById("monto_base").value!="" && document.getElementById("monto_iva").value!="" && document.getElementById("fecha_factura").value!=""){
          /*if(eval(reemplazarPC(document.getElementById("monto_iva").value))>eval(reemplazarPC(document.getElementById("t_monto_iva").value))){
              //document.getElementById("plus").disabled="disabled";
              fun_msj('Por favor, verifique el monto del iva, execede del monto asignado en la partida');
              return false;
          }else{*/
              document.getElementById("plus").disabled="";
              document.getElementById("fecha_factura").value=""
              document.getElementById("td_num_factura").innerHTML='<input type="text" name="data[cepp03_ordenpago][num_factura]" id="num_factura" value="" maxlength="40" class="inputText" onBlur="igual_num_control()"/>';
              document.getElementById("td_num_control").innerHTML='<input type="text" name="data[cepp03_ordenpago][num_control]" id="num_control" value="" maxlength="40" class="inputText"/>';
         // }
    }else{
       if(document.getElementById("fecha_factura").value==""){
        fun_msj('Por favor, ingrese la fecha de la factura');
        return false;
       }
       document.getElementById("plus").disabled="";
       return false;
    }

}
function retencion_iva(x){
    if(x==""){
	   z=75;
    }else{
	    z=x;
    }
    return z;
}
function igual_num_control(){
    document.getElementById("num_control").value=document.getElementById("num_factura").value;
    document.getElementById("monto_total").focus();
}
function verifica_crear_hasta(){
    var a=eval(document.getElementById("crear_hasta").value);
    var b=eval(document.getElementById("crear_desde").value);
    if(document.getElementById("crear_hasta").value==""){
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
function iva411(){
    var MTC   = reemplazarPC(document.getElementById('monto_total_cancelar').value);
    var p_i   = reemplazarPC(document.getElementById('porcentaje_iva').value);
    var Mi    = reemplazarPC(document.getElementById('t_monto_iva').value);//---
    var bas411=0;
    base411 = MTC / (1+(p_i / 100));
    m_iva_411 = MTC - base411;
    pag='../../include/cfpp05/moneda.php?monto=';
    cargarMonto('t_monto_iva',pag+redondear(m_iva_411,2));
    //alert(m_iva_411);
    nMiliSegundos=500;
    window.setTimeout("re_calcular();", nMiliSegundos);
    //re_calcular();

}

///re-calcular los paramentros
function re_calcular(){
    pagina='../../include/sprintf.php?valor=';
    var Mtc   = reemplazarPC(document.getElementById('monto_total_cancelar').value);//----
    var prl   = reemplazarPC(document.getElementById('porce_retencion_laboral').value);
    var Ml    = reemplazarPC(document.getElementById('monto_laboral').value);//----
    var prfc  = reemplazarPC(document.getElementById('porce_retencion_fiel_cumplimiento').value);
    var Mfc   = reemplazarPC(document.getElementById('monto_fiel_cumplimiento').value);//---
    var p_i   = reemplazarPC(document.getElementById('porcentaje_iva').value);
    var Mi    = reemplazarPC(document.getElementById('t_monto_iva').value);//---
    var Monto401= reemplazarPC(document.getElementById('monto_401').value);//---
    var Mdi   = reemplazarPC(document.getElementById('monto_descontar_impuesto').value);//----
    var paa   = reemplazarPC(document.getElementById('porce_amortizacion_anticipo').value);
    var Maa   = reemplazarPC(document.getElementById('monto_amortizacion_antipo').value);//---
    var Mop   = reemplazarPC(document.getElementById('monto_orden_pago').value);//---
    var pri   = retencion_iva(reemplazarPC(document.getElementById('porce_retencion_iva').value));
    var Mri   = reemplazarPC(document.getElementById('monto_retencion_iva').value);//----
    var pdislr= reemplazarPC(document.getElementById('porce_deduccion_isrl').value);
    var Mislr = reemplazarPC(document.getElementById('monto_isrl').value);//---
    var Su    = reemplazarPC(document.getElementById('sustraendo').value);//---
    var SuO   = reemplazarPC(document.getElementById('sustraendo_original').value);//---sustraendo_original
    var pdtf  = reemplazarPC(document.getElementById('porce_deduccion_timbre_fiscal').value);
    var Mtf   = reemplazarPC(document.getElementById('monto_timbre_fiscal').value);//---
    var pdim  = reemplazarPC(document.getElementById('porce_deduccion_impuesto_municipal').value);
    var Mim   = reemplazarPC(document.getElementById('monto_impuesto_municipal').value);//---
    var Mnc   = reemplazarPC(document.getElementById('monto_neto_cobrar').value);//---
    var RII   = document.getElementById('retencion_incluye_iva').value;// 1.si  2.no
    var DMT   = document.getElementById('desde_monto_timbre').value;
    var DMIM  = document.getElementById('desde_monto_impuesto_municipal').value;
    var O_R   = document.getElementById('objeto_rif').value;
    var DMJ   = document.getElementById('desde_monto_juridico').value;
    var DMN   = document.getElementById('desde_monto_natural').value;
    var ISLR  = document.getElementById('impuesto_sobre_la_renta').value;
    var AII   = document.getElementById('anticipo_incluye_iva').value;
    var restar_401 = document.getElementById('id_restar_401').value;
    if(RII==1){
       Ml=(Mtc*prl)/100;
       Mfc=(Mtc*prfc)/100;
    }else{
       Ml=((Mtc-Mi)*prl)/100;
       Mfc=((Mtc-Mi)*prfc)/100;
    }
    Mdi=Mtc-Mfc-Ml-Mi;
    if(restar_401=='si'){
       Mdi=Mdi-Monto401;
    }

   // alert('mtc:'+Mtc+' - dmt:'+DMT);
    if(eval(Mtc)<eval(DMT)){
		Mtf=0;
		//alert('uno '+Mtf);
		}else{
		  Mtf=((Mdi/1000)*pdtf);
    }
    if(eval(Mtc)<eval(DMIM)){
		Mim=0;
		}else{
		Mim=((Mdi/100)*pdim);
	  }
    		//alert(O_R);
    		switch(eval(O_R)){
			case 1:
									if(eval(Mtc)<eval(DMJ)){
											 Mislr=0;
									}else{
										Mislr=Mdi*pdislr/100;
									}
			break;
			case 2:
							//alert("Mtc:"+Mtc+"\nDMJ:"+DMJ+"\nMdi:"+Mdi+"\nISLR:"+ISLR);
									if(eval(Mtc)<eval(DMJ)){
											 Mislr=0;
									}else{
										Mislr=eval(Mdi)*eval(pdislr)/100;
									}
									//alert(Mislr);

			break;
			case 3:

			break;
			case 4:
					sustraendo_tresporciento = reemplazarPC(document.getElementById("sustraendo_tresporciento").value);

							 if(eval(Mtc)<eval(DMN)){
											 Mislr=0;
									}else{

										Mislr=Mdi*pdislr/100;
										if(pdislr==3){Su=sustraendo_tresporciento;}
										else{
										     Su=SuO*pdislr;
										   }

										}

										Mislr=Mislr-Su;

			break;
		}//switch
        if(eval(AII)==1){
					Maa=Mtc*paa/100;
				}else{
					Maa=((Mtc-Mi)*paa)/100;
				}
        Mop=Mtc-Maa;
        //alert(Mi+' * '+pri);
        Mri=Mi*pri/100;

       // sprintfphp(pagina+Mri);
        //FF=function (oXML) {oXML.responseText; alert(peticion);};
        //FF= new peticion();
        //alert(peticion().responseText);



							acepto="no";
							monto_retencion_multa_monto = document.getElementById("monto_retencion_multa").value;
							var str = monto_retencion_multa_monto;
							for(i=0; i<monto_retencion_multa_monto.length; i++){
							   if(str.charAt(i)==","){acepto="si";}
							}//fin for
							if(acepto=="si"){
							  for(i=0; i<monto_retencion_multa_monto.length; i++){str = str.replace('.','');}//fin for
							  str = str.replace(',','.');

							}//fin
							var monto_retencion_multa_monto = redondear(str,2);



							acepto="no";
							monto_retencion_responsabilidad_social = document.getElementById("monto_retencion_responsabilidad_social").value;
							var str = monto_retencion_responsabilidad_social;
							for(i=0; i<monto_retencion_responsabilidad_social.length; i++){
							   if(str.charAt(i)==","){acepto="si";}
							}//fin for
							if(acepto=="si"){
							  for(i=0; i<monto_retencion_responsabilidad_social.length; i++){str = str.replace('.','');}//fin for
							  str = str.replace(',','.');

							}//fin
							var monto_retencion_responsabilidad_social = redondear(str,2);

rmr = eval(monto_retencion_multa_monto) + eval(monto_retencion_responsabilidad_social);



moneda('monto_retencion_multa');
moneda('monto_retencion_responsabilidad_social');



        Mnc=   Mop -(redondear(Mislr,2)+redondear(Mri,2)+redondear(Mtf,2)+redondear(Mim,2)+redondear(rmr,2));




        //Mnc=Mop -(eval(Mislr)+eval(Mri)+eval(Mtf)+eval(Mim));
        pag='../../include/cfpp05/moneda.php?monto=';
        cargarMonto('monto_laboral',pag+redondear(Ml,2));
        cargarMonto('monto_fiel_cumplimiento',pag+redondear(Mfc,2));
        cargarMonto('t_monto_iva',pag+redondear(Mi,2));
        cargarMonto('monto_descontar_impuesto',pag+redondear(Mdi,2));
        cargarMonto('monto_amortizacion_antipo',pag+redondear(Maa,2));
        cargarMonto('monto_orden_pago',pag+redondear(Mop,2));
        cargarMonto('monto_retencion_iva',pag+redondear(Mri,2));
        cargarMonto('monto_isrl',pag+redondear(Mislr,2));
        cargarMonto('sustraendo',pag+redondear(Su,2));
        cargarMonto('monto_timbre_fiscal',pag+redondear(Mtf,2));
        cargarMonto('monto_impuesto_municipal',pag+redondear(Mim,2));
        cargarMonto('monto_neto_cobrar',pag+redondear(Mnc,2));
        cargarMonto('c_monto_total',pag+redondear(Mop,2));
        var CMPT=Mop * eval(document.getElementById('c_numero_pago').value);
        cargarMonto('c_monto_parcial',pag+CMPT);
        cargarMonto('porce_retencion_laboral',pag+prl);
        cargarMonto('porce_retencion_fiel_cumplimiento',pag+prfc);
        cargarMonto('porcentaje_iva',pag+p_i);
        cargarMonto('porce_amortizacion_anticipo',pag+paa);
        /*
          COMENTADO POR PROBLEMAS DE CARGA CON LA VERSION DE FIREFOX V46
         cargarMonto('porce_retencion_iva',pag+pri);*/
        cargarMonto('porce_deduccion_isrl',pag+pdislr);
        cargarMonto('porce_deduccion_timbre_fiscal',pag+pdtf);
        cargarMonto('porce_deduccion_impuesto_municipal',pag+pdim);

/*
 document.getElementById('monto_laboral').value=redondear(Ml,2);
        document.getElementById('monto_fiel_cumplimiento').value=redondear(Mfc,2);
        document.getElementById('t_monto_iva').value=redondear(Mi,2);
        document.getElementById('monto_descontar_impuesto').value=redondear(Mdi,2);
        document.getElementById('monto_amortizacion_antipo').value=redondear(Maa,2);
        document.getElementById('monto_orden_pago').value=redondear(Mop,2);
        document.getElementById('monto_retencion_iva').value=redondear(Mri,2);
        document.getElementById('monto_isrl').value=redondear(Mislr,2);
        document.getElementById('sustraendo').value=redondear(Su,2);
        document.getElementById('monto_timbre_fiscal').value=redondear(Mtf,2);
        document.getElementById('monto_impuesto_municipal').value=redondear(Mim,2);
        document.getElementById('monto_neto_cobrar').value=redondear(Mnc,2);
        document.getElementById('c_monto_total').value=redondear(Mop,2);
        document.getElementById('c_monto_parcial').value=Mop * eval(document.getElementById('c_numero_pago').value);
*/


var civil_social=0;
var total_reten = 0;
var a =0;
var b = 0;

a=document.getElementById('monto_retencion_multa').value;

if(a.indexOf(",")!=-1){
a=reemplazarPC(document.getElementById('monto_retencion_multa').value);
}
b=document.getElementById('monto_retencion_responsabilidad_social').value;

if(b.indexOf(",")!=-1){
b=reemplazarPC(document.getElementById('monto_retencion_responsabilidad_social').value);
}
				total_reten= eval(Ml) +  eval(Mfc) +  eval(Maa) +  eval(Mri) +  eval(Mislr) + eval(Mtf) + eval(Mim);
				total_reten=redondear(eval(total_reten),2) + redondear(eval(a),2) + redondear(eval(b),2);
				document.getElementById('total_retenciones').value=redondear(eval(total_reten),2);

moneda('total_retenciones');


}//fin funtion

function cal_por_multa(a){

    var Mtc   = reemplazarPC(document.getElementById('monto_total_cancelar').value);//----
    var Mri   = reemplazarPC(document.getElementById('monto_retencion_iva').value);//----
    var Prc   = reemplazarPC(document.getElementById('rcivil').value);//----
    var Prs   = reemplazarPC(document.getElementById('rsocial').value);//----
	var calculo=0;

if(a==1){
	calculo=((Mtc-Mri)*Prc)/100
		$('monto_retencion_multa').value=redondear(calculo,2);
		moneda('monto_retencion_multa');

}else{
	calculo=((Mtc-Mri)*Prs)/100
		$('monto_retencion_responsabilidad_social').value=redondear(calculo,2);
		moneda('monto_retencion_responsabilidad_social');

}
 	re_calcular();

}


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

function valida_guardar_orden_pago(){

        var mydate=new Date();
        var year1=mydate.getYear();
        var year=mydate.getYear();

        var fecha_actual = document.getElementById('fecha_documento_orden').value;
		var datearray = fecha_actual.split("/");
		var fecha1 = datearray[2] + '/' + datearray[1] + '/' + datearray[0]; // INVERTIR FECHA YYYY/MM/DD

		var fecha_comparar = document.getElementById('fecha_documento_anterior').value;
		var datearray = fecha_comparar.split("/");
		var fecha2 = datearray[2] + '/' + datearray[1] + '/' + datearray[0]; // INVERTIR FECHA YYYY/MM/DD

		if (fecha1<fecha2){
            documento_anterior  = document.getElementById('numero_documento_anterior').value;
            fecha_anterior      = document.getElementById('fecha_documento_anterior').value;
            numero_documento    = document.getElementById('numero_orden_pago').value;
				if (documento_anterior!=0){
            fun_msj('Fecha orden '+numero_documento+' menor a fecha '+fecha_anterior+' de orden '+documento_anterior);
            return false;
            	}

   }else

  if(document.getElementById('401_existe')){
   var existe = "1";
  }else{
   var existe = "0";
  }


  if(document.getElementById('403_existe')){
   var existe2 = "1";
  }else{
   var existe2 = "0";
  }


  if(document.getElementById('403_iva_existe')){
   var existe3 = "1";
  }else{
   var existe3 = "0";
  }



	if(document.getElementById('fecha_documento_orden').value==''){
			fun_msj('Por favor seleccione la fecha de la orden de pago');
			document.getElementById('fecha_documento_orden').focus();
			return false;

	}else if(verifica_cierre_ano_ejecucion('fecha_documento_orden')==false){
				fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA DE LA ORDEN DE PAGO NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
				return false;

	}else if(document.getElementById('cta_banc_beneficiario').value!='' && document.getElementById('cta_banc_beneficiario').value.length < 20){
			fun_msj('Por favor la cuenta bancaria debe ser de 20 digitos');
			document.getElementById('cta_banc_beneficiario').focus();
			return false;

	}else if(valida_fechas_menores_documentos(3)==2){
      			return false;

   }else
 		var fecha_actual = document.getElementById('fecha_documento_orden').value;
		var datearray = fecha_actual.split("/");
		var fecha1 = datearray[2] + '/' + datearray[1] + '/' + datearray[0]; // INVERTIR FECHA YYYY/MM/DD

		var fecha_comparar = document.getElementById('fecha_documento').value;
		var datearray = fecha_comparar.split("/");
		var fecha2 = datearray[2] + '/' + datearray[1] + '/' + datearray[0]; // INVERTIR FECHA YYYY/MM/DD

		if (fecha2>fecha1){
	        fun_msj('la Fecha de la orden de pago debe ser mayor a la fecha del documento adjunto');
            return false;

	}else if(document.getElementById('tipo_documento_st').value==''){
			fun_msj('Por favor seleccione el tipo de documento');
			document.getElementById('tipo_documento_st').focus();
			return false;
	}else if(document.getElementById('tipo_documento_st').value==''){
	    //     if(document.getElementById('tipo_documento_st3').value==''){
			     fun_msj('Por favor seleccione el tipo de documento adjunto');
			     document.getElementById('tipo_documento_st3').focus();
			     return false;
			//     }
	}else if(document.getElementById('cod_tipo_pago_select').value==''){
			fun_msj('Por favor seleccione el tipo de pago');
			document.getElementById('cod_tipo_pago_select').focus();
			return false;

	}else if(document.getElementById('concepto').value==''){
			fun_msj('Por favor ingrese el concepto de la orden de pago');
			document.getElementById('concepto').focus();
			return false;


/*
    }else if((document.getElementById('cod_tipo_pago_select').value!='3' && document.getElementById('cod_tipo_pago_select').value!='6') && existe=="1" && existe2=="0" && existe3=="1"){
			fun_msj('Esta orden corresponde a servicio &oacute; aportes, favor revisar Tipo de Pago');
			return false;
*/



    }else if(valida_fechas_documentos_mayores(5)==2){

			return false;

	}else if(eval(reemplazarPC(document.getElementById('t_monto_iva').value))>0){

        /*if(eval(reemplazarPC(document.getElementById('tmontoivahh').value))!=eval(reemplazarPC(document.getElementById('t_monto_iva').value))){
            fun_msj('Monto IVA no cuadra con monto total iva de las facturas, Por favor revise');
            //alert(document.getElementById('tmontoivahh').value+" "+document.getElementById('t_monto_iva').value);
			      //document.getElementById('concepto').focus();
			      return false;
        }else{*/

		        tota_grilla_partidas = document.getElementById('tota_grilla_partidas').value;
		        monto_orden_pago     = document.getElementById('monto_orden_pago').value;

		        var str = monto_orden_pago;for(i=0; i<monto_orden_pago.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var monto_orden_pago = str;
	          /*if(monto_orden_pago!=tota_grilla_partidas){
	            fun_msj('El total de las partidas es diferente al monto de la orden de pago');
              return false;
			      }*/
       /* }//fin else*/
	}else{


			        tota_grilla_partidas = document.getElementById('tota_grilla_partidas').value;
			        monto_orden_pago     = document.getElementById('monto_orden_pago').value;

			        var str = monto_orden_pago;for(i=0; i<monto_orden_pago.length; i++){str = str.replace('.','');}str   = str.replace(',','.');var monto_orden_pago = str;


			             if(monto_orden_pago!=tota_grilla_partidas){
			                   fun_msj('El total de las partidas es diferente al monto de la orden de pago');
						       return false;
			             }

	}//fin else


	if(document.getElementById('fecha_documento_orden').value!=""){
           return verifica_cierre_mes_ejecucion('fecha_documento_orden');
    }


	if(document.getElementById('cod_tipo_pago_select').value!='12' && existe=="1" && existe2=="1" && existe3=="1"){
			var confir_tpago = confirm('Esta Orden Corresponde a CESTA TICKET, desea continuar con el registro en base a este Tipo de Pago');
			if(confir_tpago == true){
				return true;
			}else{
				fun_msj('Esta orden corresponde a CESTA TICKET, favor revisar Tipo de Pago');
				return false;
			}
	}

}//fin funcion




function redondear_dec(cantidad, decimales) {
var cantidad = parseFloat(cantidad);
var decimales = parseFloat(decimales);
decimales = (!decimales ? 2 : decimales);
return Math.round(cantidad * Math.pow(10, decimales)) / Math.pow(10, decimales);
}

function valida_notadebito_especial(){
	if(document.getElementById('ano_movimiento').value==''){
			fun_msj('Por favor seleccione el a&ntilde;o de movimiento');
			document.getElementById('ano_movimiento').focus();
			return false;
	}else if(document.getElementById('sl_cod_entidad').value==''){
			fun_msj('Por favor seleccione la entidad bancaria');
			document.getElementById('sl_cod_entidad').focus();
			return false;
	}else if(document.getElementById('sl_cod_sucursal').value==''){
			fun_msj('Por favor seleccione la sucursal bancaria');
			document.getElementById('sl_cod_sucursal').focus();
			return false;
	}else if(document.getElementById('sl_nro_cuenta').value==''){
			fun_msj('Por favor seleccione la cuenta bancaria');
			document.getElementById('sl_nro_cuenta').focus();
			return false;
	}else if(document.getElementById('nro_notadebito').value==''){
			fun_msj('Por favor ingrese el numero de la nota de debito');
			document.getElementById('nro_notadebito').focus();
			return false;
	}else if(document.getElementById('fechanotadebito').value==''){
			fun_msj('Por favor ingrese la fecha de la nota de debito');
			document.getElementById('fechanotadebito').focus();
			return false;
	}else if(verifica_cierre_ano_ejecucion('fechanotadebito')==false){
		fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA DE LA NOTA DE D&Eacute;BITO NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
		return false;
	}else if(document.getElementById('monto2').value==''){
			fun_msj('Por favor ingrese el monto de la nota de debito');
			document.getElementById('monto2').focus();
			return false;
	}/*else if(document.getElementById('select_num_orden').value==''){
			fun_msj('Por favor seleccione el numero de la orden de pago');
			document.getElementById('select_num_orden').focus();
			return false;
	}*/else if(document.getElementById('beneficiario').value==''){
			fun_msj('Por favor ingrese el nombre del beneficiario');
			document.getElementById('beneficiario').focus();
			return false;
	}else if(document.getElementById('concepto_notadebito').value==''){
			fun_msj('Por favor ingrese el concepto de la nota de debito');
			document.getElementById('concepto_notadebito').focus();
			return false;
	}else if(document.getElementById('monto2').value != document.getElementById('total_manual2').value){
			//alert(document.getElementById('monto2').value+" - "+document.getElementById('total_manual').value);
			fun_msj('Los montos de la nota de debito no coinciden');
			document.getElementById('monto2').focus();
			return false;
	}

	if(document.getElementById('fechanotadebito').value!=""){
           return verifica_cierre_mes_ejecucion('fechanotadebito');
    }
}

function valida_cfpp30_rendiciones(){
	if(document.getElementById('ano_ejecucion').value==''){
			fun_msj('Por favor seleccione el a&ntilde;o');
			document.getElementById('ano_ejecucion').focus();
			return false;
	}else if(document.getElementById('fecharendicion').value==''){
			fun_msj('Por favor seleccione la fecha del documento');
			document.getElementById('fecharendicion').focus();
			return false;
	}else if(verifica_cierre_ano_ejecucion('fecharendicion')==false){
				fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA DE LA RENDICI&Oacute;N NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
				return false;
	}else if(document.getElementById('funcionario').value==''){
			fun_msj('Por favor ingrese el responsable de la rendici&oacute;n');
			document.getElementById('funcionario').focus();
			return false;
	}else if(document.getElementById('rendicion_cach_1').checked==true){
    	if(document.getElementById('select_cach_1').value==''){
			fun_msj('Por favor seleccione la entidad bancaria');
			document.getElementById('select_cach_1').focus();
			return false;
		}else if(document.getElementById('select_cach_2').value==''){
			fun_msj('Por favor seleccione la sucursal bancaria');
			document.getElementById('select_cach_2').focus();
			return false;
		}else if(document.getElementById('select_cach_3').value==''){
			fun_msj('Por favor seleccione la cuenta bancaria');
			document.getElementById('select_cach_3').focus();
			return false;
		}else if(document.getElementById('select_cach_4').value==''){
			fun_msj('Por favor seleccione el n&uacute;mero del cheque');
			document.getElementById('select_cach_4').focus();
			return false;
		}else if(document.getElementById('concepto').value==''){
			fun_msj('Por favor ingrese el concepto de la rendici&oacute;n');
			document.getElementById('concepto').focus();
			return false;
    	}else if(document.getElementById('select_3').value==''){
			fun_msj('Por favor ingrese la cuenta bancaria');
			document.getElementById('select_3').focus();
			return false;
		}else if(document.getElementById('numero_cheque').value==''){
			fun_msj('Por favor ingrese el N&uacute;mero del Cheque');
			document.getElementById('numero_cheque').focus();
			return false;
	}else if(document.getElementById('numero_cheque').value.length < 3){
			fun_msj('Por favor ingrese un N&uacute;mero de Cheque valido');
			document.getElementById('numero_cheque').focus();
			return false;
	}else if(document.getElementById('fecha_cheque').value==''){
			fun_msj('Por favor seleccione la fecha del Cheque');
			document.getElementById('fecha_cheque').focus();
			return false;
	}else if(document.getElementById('monto_cheque').value=='' || document.getElementById('monto_cheque').value=='0,00'){
			fun_msj('Por favor ingrese el monto del Cheque');
			document.getElementById('monto_cheque').focus();
			return false;
	}else if(eval(document.getElementById('total_manual').value)==eval(0)){
			fun_msj('Por favor ingrese un monto');
			document.getElementById('total_manual').focus();
			return false;
		}else{
           return verifica_cierre_mes_ejecucion('fecharendicion');
    	}
      }

      else{

	if(document.getElementById('concepto').value==''){
			fun_msj('Por favor ingrese el concepto de la rendici&oacute;n');
			document.getElementById('concepto').focus();
			return false;
    }else if(document.getElementById('select_3').value==''){
			fun_msj('Por favor ingrese la cuenta bancaria');
			document.getElementById('select_3').focus();
			return false;
	}else if(document.getElementById('numero_cheque').value==''){
			fun_msj('Por favor ingrese el N&uacute;mero del Cheque');
			document.getElementById('numero_cheque').focus();
			return false;
	}else if(document.getElementById('numero_cheque').value.length < 3){
			fun_msj('Por favor ingrese un N&uacute;mero de Cheque valido');
			document.getElementById('numero_cheque').focus();
			return false;
	}else if(document.getElementById('fecha_cheque').value==''){
			fun_msj('Por favor seleccione la fecha del Cheque');
			document.getElementById('fecha_cheque').focus();
			return false;
	}else if(document.getElementById('monto_cheque').value=='' || document.getElementById('monto_cheque').value=='0,00'){
			fun_msj('Por favor ingrese el monto del Cheque');
			document.getElementById('monto_cheque').focus();
			return false;
	}else if(eval(document.getElementById('total_manual').value)==eval(0)){
			fun_msj('Por favor ingrese un monto');
			document.getElementById('total_manual').focus();
			return false;
	}

		if(document.getElementById('fecharendicion').value!=""){
           return verifica_cierre_mes_ejecucion('fecharendicion');
    	}
    }
}

function validacion_presupuestaria3(){

if(document.getElementById('seleccion_1').value == ""){

			fun_msj('Seleccione el c&oacute;digo del sector');
			document.getElementById('seleccion_1').focus();
			return false;

}else if(document.getElementById('seleccion_2').value == ""){

			fun_msj('Seleccione el c&oacute;digo del programa');
			document.getElementById('seleccion_2').focus();
			return false;

}else if(document.getElementById('seleccion_3').value == ""){

			fun_msj('Seleccione el c&oacute;digo del sub-programa');
			document.getElementById('seleccion_3').focus();
			return false;

}else if(document.getElementById('seleccion_4').value == ""){

			fun_msj('Seleccione el c&oacute;digo del proyecto');
			document.getElementById('seleccion_4').focus();
			return false;

}else if(document.getElementById('seleccion_5').value == ""){

			fun_msj('Seleccione el c&oacute;digo de la Actividad u Obra');
			document.getElementById('seleccion_5').focus();
			return false;

}else if(document.getElementById('seleccion_6').value == ""){

			fun_msj('Seleccione el c&oacute;digo de la partida');
			document.getElementById('seleccion_6').focus();
			return false;

}else if(document.getElementById('seleccion_7').value == ""){

			fun_msj('Seleccione el c&oacute;digo de la generica');
			document.getElementById('seleccion_7').focus();
			return false;

}else if(document.getElementById('seleccion_8').value == ""){

			fun_msj('Seleccione el c&oacute;digo de la especifica');
			document.getElementById('seleccion_8').focus();
			return false;

}else if(document.getElementById('seleccion_9').value == ""){

			fun_msj('Seleccione el c&oacute;digo de la sub-especifica');
			document.getElementById('seleccion_9').focus();
			return false;

}else if(document.getElementById('seleccion_10').value == "" && document.getElementById('seleccion_10').length >=1){
            fun_msj('Seleccione el c&oacute;digo de la auxiliar');
            setTimeout("fondoCampo('seleccion_10',2);", 1500);
			document.getElementById('seleccion_10').focus();
			return false;
}else if(document.getElementById('pre_monto').value == "" && document.getElementById('comp_monto').value == "" && document.getElementById('cau_monto').value == "" && document.getElementById('pag_monto').value == ""){
			fun_msj('Inserte el monto');
			setTimeout("fondoCampo('pre_monto',2);", 1500);
			document.getElementById('pre_monto').focus();
			return false;
}else{
document.getElementById('pre_monto').value="";
document.getElementById('comp_monto').value="";
document.getElementById('cau_monto').value="";
document.getElementById('pag_monto').value="";
document.getElementById('pre_monto').readOnly=false;
document.getElementById('comp_monto').readOnly=false;
document.getElementById('cau_monto').readOnly=false;
document.getElementById('pag_monto').readOnly=false;
}
}//fin funtion3

function valida_cfpp30_reintegro(){
//	alert(document.getElementById('cant_items').value);
	if(document.getElementById('ano_ejecucion').value==''){
			fun_msj('Por favor seleccione el a&ntilde;o');
			document.getElementById('ano_ejecucion').focus();
			return false;
	}else if(document.getElementById('numero_reintegro').value==''){
			fun_msj('Por favor ingrese el numero del Reintegro');
			document.getElementById('numero_reintegro').focus();
			return false;
	}else if(verifica_cierre_ano_ejecucion('fechareintegro')==false){
			fun_msj('LO SIENTO EL A&Ntilde;O DE LA FECHA DEL REINTEGRO NO CORRESPONDE AL A&Ntilde;O DE EJECUCI&Oacute;N');
			return false;
	}else if(document.getElementById('funcionario').value==''){
			fun_msj('Por favor ingrese el responsable del Reintegro');
			document.getElementById('funcionario').focus();
			return false;
	}else if(document.getElementById('concepto').value==''){
			fun_msj('Por favor ingrese el concepto de la rendici&oacute;n');
			document.getElementById('concepto').focus();
			return false;
	}else if(document.getElementById('cant_items').value=='-1'){
			fun_msj('Por favor ingrese una partida');
			document.getElementById('seleccion_1').focus();
			return false;
    }else if(eval(document.getElementById('total_manual').value)==eval(0)){
			fun_msj('Por favor ingrese un monto');
			document.getElementById('total_manual').focus();
			return false;
	}else if(eval(document.getElementById('pagado_grilla').value)!=eval(0)){
	            if(document.getElementById('select_3').value==''){
				fun_msj('Por favor ingrese la cuenta bancaria');
				document.getElementById('select_3').focus();
				return false;
	      }else if(eval(document.getElementById('num_cheque').value)==""){
				fun_msj('Por favor ingrese el n&uacute;mero del cheque');
				document.getElementById('num_cheque').focus();
				return false;
		   }
	}

	if(document.getElementById('fechareintegro').value!=""){
           return verifica_cierre_mes_ejecucion('fechareintegro');
    }
}

///evaluar fechas
function esDigito(sChr){
    var sCod = sChr.charCodeAt(0);
    return ((sCod > 47) && (sCod < 58));
   }

   function valSep(oTxt){
    var bOk = false;
    bOk = bOk || ((oTxt.value.charAt(2) == "-") && (oTxt.value.charAt(5) == "-"));
    bOk = bOk || ((oTxt.value.charAt(2) == "/") && (oTxt.value.charAt(5) == "/"));
    return bOk;
   }

   function finMes(oTxt){
    var nMes = parseInt(oTxt.value.substr(3, 2), 10);
    var nAno = parseInt(oTxt.value.substr(6), 10);
    var nRes = 0;
    switch (nMes){
     case 1: nRes = 31; break;
     case 2: nRes = 28; break;
     case 3: nRes = 31; break;
     case 4: nRes = 30; break;
     case 5: nRes = 31; break;
     case 6: nRes = 30; break;
     case 7: nRes = 31; break;
     case 8: nRes = 31; break;
     case 9: nRes = 30; break;
     case 10: nRes = 31; break;
     case 11: nRes = 30; break;
     case 12: nRes = 31; break;
    }
    return nRes + (((nMes == 2) && (nAno % 4) == 0)? 1: 0);
   }

   function valDia(oTxt){
    var bOk = false;
    var nDia = parseInt(oTxt.value.substr(0, 2), 10);
    bOk = bOk || ((nDia >= 1) && (nDia <= finMes(oTxt)));
    return bOk;
   }

   function valMes(oTxt){
    var bOk = false;
    var nMes = parseInt(oTxt.value.substr(3, 2), 10);
    bOk = bOk || ((nMes >= 1) && (nMes <= 12));
    return bOk;
   }

   function valAno(oTxt){
    var bOk = true;
    var nAno = oTxt.value.substr(6);
    bOk = bOk && ((nAno.length == 2) || (nAno.length == 4));
    if (bOk){
     for (var i = 0; i < nAno.length; i++){
      bOk = bOk && esDigito(nAno.charAt(i));
     }
    }
    return bOk;
   }

   function valFecha(oTxt){
    var bOk = true;
    if (oTxt.value != ""){
     bOk = bOk && (valAno(oTxt));
     bOk = bOk && (valMes(oTxt));
     bOk = bOk && (valDia(oTxt));
     bOk = bOk && (valSep(oTxt));
     return bOk;
    }
   }

   function fechaMayorOIgualQue(fec0, fec1){
    var bRes = false;
    var sDia0 = fec0.value.substr(0, 2);
    var sMes0 = fec0.value.substr(3, 2);
    var sAno0 = fec0.value.substr(6, 4);
    var sDia1 = fec1.value.substr(0, 2);
    var sMes1 = fec1.value.substr(3, 2);
    var sAno1 = fec1.value.substr(6, 4);
    if (sAno0 > sAno1) bRes = true;
    else {
     if (sAno0 == sAno1){
      if (sMes0 > sMes1) bRes = true;
      else {
       if (sMes0 == sMes1)
        if (sDia0 >= sDia1) bRes = true;
      }
     }
    }
    return bRes;
   }

function valFechas2(obj1,obj2){
    var bOk = false;
    if (valFecha(obj1)){
     if (valFecha(obj2)){
      if (fechaMayorOIgualQue(obj2, obj1)){
       bOk = true;
       //alert("Ok");
       return false;
      } else {
       //alert("Rango inv�lido");
       return true;
       //obj2.focus();
      }
     } else {
      //alert("Fecha inv�lida1");
      return true;
      //obj2.focus();
     }
    } else {
     //alert("Fecha inv�lida2");
     return true;
     //obj1.focus();
    }
   }



   function s_factura_sin_iva_con_retencion(){
   	   pag='../../include/cfpp05/moneda.php?monto=';
	   if(document.getElementById("num_factura").value!=""){
	      document.getElementById("num_control").value=document.getElementById("num_factura").value;
	      //var total=reemplazarPC(document.getElementById("monto_total_cancelar").value)-reemplazarPC(document.getElementById("monto_laboral").value)-reemplazarPC(document.getElementById("monto_fiel_cumplimiento").value);
	      var total=reemplazarPC(document.getElementById("monto_orden_pago").value);
	      var m_base=total;
	      var m_iva=0;
	      var exc=total;
	      //alert(total);
	      cargarMonto('monto_total',pag+total);
	      cargarMonto('monto_base', pag+m_base);
	      cargarMonto('monto_iva',  pag+m_iva);
	      cargarMonto('excento',    pag+exc);
	      cargarMonto('f_iva',      pag+0);
	   }
}//s_factura_sin_iva_con_retencion(){

function s_factura_sin_iva_con_retencion_2(){
   	   pag='../../include/cfpp05/moneda.php?monto=';
	   if(document.getElementById("num_factura").value!=""){
	      document.getElementById("num_control").value=document.getElementById("num_factura").value;
	      //var total=reemplazarPC(document.getElementById("monto_total_cancelar").value)-reemplazarPC(document.getElementById("monto_laboral").value)-reemplazarPC(document.getElementById("monto_fiel_cumplimiento").value);
	      var total=reemplazarPC(document.getElementById("monto_total").value);
	      var m_base=total;
	      var m_iva=0;
	      var exc=total;
	      //alert(total);
	      cargarMonto('monto_total',pag+total);
	      cargarMonto('monto_base', pag+m_base);
	      cargarMonto('monto_iva',  pag+m_iva);
	      cargarMonto('excento',    pag+exc);
	      cargarMonto('f_iva',      pag+0);
	   }
}//s_factura_sin_iva_con_retencion_2(){

function verificar_factura_2(){
    if(document.getElementById("num_factura").value!="" && document.getElementById("num_control").value!="" && document.getElementById("monto_total").value!="" && document.getElementById("monto_base").value!="" && document.getElementById("monto_iva").value!="" && document.getElementById("fecha_factura").value!=""){

    }else{
       if(document.getElementById("fecha_factura").value==""){
        fun_msj('Por favor, ingrese la fecha de la factura');
        return false;
       }
       document.getElementById("plus").disabled="";
       return false;
    }

}

function valida_firmas99_reportes(){
	if(document.getElementById("cedula_primera_firma").value=="" && document.getElementById("cedula_segunda_firma").value=="" && document.getElementById("cedula_tercera_firma").value=="" && document.getElementById("cedula_cuarta_firma").value==""){
		fun_msj('Por favor, debe ingresar al menos un firmante.');
		document.getElementById('responsa_primera_firma').focus();
		return false;
	}

	/* else if(document.getElementById("responsa_primera_firma").value=="" && document.getElementById("funcionario_primera_firma").value=="" && document.getElementById("cargo_primera_firma").value=="" && document.getElementById("cedula_primera_firma").value==""){
		fun_msj('Por favor, ingrese todos los datos de la primera firma.');
		return false;
	}else if(document.getElementById("responsa_segunda_firma").value=="" && document.getElementById("funcionario_segunda_firma").value=="" && document.getElementById("cargo_segunda_firma").value=="" && document.getElementById("cedula_segunda_firma").value==""){
		fun_msj('Por favor, ingrese todos los datos de la segunda firma.');
		return false;
	}else if(document.getElementById("responsa_tercera_firma").value=="" && document.getElementById("funcionario_tercera_firma").value=="" && document.getElementById("cargo_tercera_firma").value=="" && document.getElementById("cedula_tercera_firma").value==""){
		fun_msj('Por favor, ingrese todos los datos de la tercera firma.');
		return false;
	}else if(document.getElementById("responsa_cuarta_firma").value=="" && document.getElementById("funcionario_cuarta_firma").value=="" && document.getElementById("cargo_cuarta_firma").value=="" && document.getElementById("cedula_cuarta_firma").value==""){
		fun_msj('Por favor, ingrese todos los datos de la cuarta firma.');
		return false;
	} */
}


function valida_firmas99_reportes1(){
	if(document.getElementById("cedula_primera_firma1").value=="" && document.getElementById("cedula_segunda_firma1").value=="" && document.getElementById("cedula_tercera_firma1").value=="" && document.getElementById("cedula_cuarta_firma1").value==""){
		fun_msj('Por favor, debe ingresar al menos un firmante del Segundo Formato.');
		document.getElementById('responsa_primera_firma1').focus();
		return false;
	}
}
        
        
function valida2_firmas99_reportes(){
	if(document.getElementById("cedula_primera_firma").value=="" && document.getElementById("cedula_segunda_firma").value==""){
		fun_msj('Por favor, debe ingresar al menos un firmante.');
		document.getElementById('responsa_primera_firma').focus();
		return false;
	}
}