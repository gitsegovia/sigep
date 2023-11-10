<?php

class CorrerScriptsController extends AppController {

   var $uses = array('cobd01_contratoobras_anticipo_cuerpo','cobd01_contratoobras_anticipo_partidas',
                     'cfpd07_obras_cuerpo','cobd01_contratoobras_cuerpo','cobd01_co_modificacion_cuerpo',
                     'cfpd05','cfpd22_numero_asiento_causado','cobd01_co_modificacion_partidas',
                     'cobd01_contratoobras_partidas','cugd04', 'cfpd22','cstd03_cheque_cuerpo',
                     'cobd01_contratoobras_valuacion_cuerpo','cobd01_contratoobras_valuacion_partidas',
                     'cugd03_acta_anulacion_numero','cugd03_acta_anulacion_cuerpo','cfpd21_numero_asiento_compromiso',
                     'cfpd21','cepd02_contratoservicio_anticipo_cuerpo','cepd02_contratoservicio_anticipo_partidas',
                     'cepd02_contratoservicio_cuerpo','cepd02_cs_modificacion_cuerpo','cepd02_cs_modificacion_partidas',
                     'cepd02_contratoservicio_partidas','cepd02_contratoservicio_valuacion_cuerpo',
                     'cepd02_contratoservicio_valuacion_partidas','cfpd23_numero_asiento_pagado','cfpd23','cstd03_cheque_partidas',
                     'cepd01_compromiso_cuerpo','cepd01_compromiso_partidas','cepd03_ordenpago_cuerpo',
'cepd03_ordenpago_partidas', 'cepd03_ordenpago_cuerpo', 'cepd03_ordenpago_facturas','cscd04_ordencompra_encabezado',
'cscd04_ordencompra_partidas','cscd04_ordencompra_anticipo_cuerpo','cscd04_ordencompra_a_pago_partidas','cscd04_ordencompra_autorizacion_cuerpo'

                    );
   var $helpers = array('Html','Ajax','Javascript','Sisap');




function SQLCA($ano=null){//sql para busqueda de codigos de arranque con y sin año
				 $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
				 $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
				 $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
				 $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
				 if($ano!=null){
					 $sql_re .= "cod_dep=".$this->verifica_SS(5)."  and  ";
						$sql_re .= "ano=".$ano."  ";
				 }else{
					 $sql_re .= "cod_dep=".$this->verifica_SS(5)." ";
				 }
				 return $sql_re;
		}//fin funcion SQLCA

		function SQLCAIN($ano=null){//sql para busqueda de codigos de arranque con y sin año
				 $sql_re = $this->verifica_SS(1).",";
				 $sql_re .= $this->verifica_SS(2).",";
				 $sql_re .=  $this->verifica_SS(3).",";
				 $sql_re .= $this->verifica_SS(4).",";
				 if($ano!=null){
					 $sql_re .= $this->verifica_SS(5).",";
						$sql_re .= $ano."";
				 }else{
					 $sql_re .=  $this->verifica_SS(5)."";
				 }
				 return $sql_re;
		}//fin funcion SQLCAIN

function SQLCA_noDEP(){//sql para busqueda de codigos de arranque con y sin año
				 $sql_re = "cod_presi=1  and    ";
				 $sql_re .= "cod_entidad=11  and  ";
				 $sql_re .= "cod_tipo_inst=30  and ";
				 $sql_re .= "cod_inst=11 ";

				 return $sql_re;
		}//fin funcion SQLCA




function verifica_SS($i){
			/**
			 * Funcion que permite leer las varibles de session. la cual permite capturar los codigos del usuario
			 * para ser insertados en todas las tablas.
			 * */
			switch ($i){
				case 1:return $this->Session->read('SScodpresi');break;
				case 2:return $this->Session->read('SScodentidad');break;
				case 3:return $this->Session->read('SScodtipoinst');break;
				case 4:return $this->Session->read('SScodinst');break;
				case 5:return $this->Session->read('SScoddep');break;
				case 6:return $this->Session->read('entidad_federal');break;
				default:
					 return "NULO";


			}//fin switch
		}//fin verifica_SS

function index () {
   //  $this->layout="ajax";
     echo '<br><a href="/correr_scripts/limpiar_tablas/'.rand().'">VACIAR TABLAS</a>';
     echo '<br><a href="/correr_scripts/reactualizacion_rcompromisos/'.rand().'">Compromisos - RC</a>';
     echo '<br><a href="/correr_scripts/reactualizacion_orden_compra_parte1/'.rand().'">Compromisos - OC</a>';
     echo '<br><a href="/correr_scripts/index_obra/'.rand().'">Compromisos - OB</a>';
     echo '<br><a href="/correr_scripts/index_servicio/'.rand().'">Compromisos - Srv</a>';
     echo '<br><a href="/correr_scripts/reactualizacion_ordenpago_todo/'.rand().'">Causados - Orden de pago completa</a>';
     //echo '<br><a href="/correr_scripts/reactualizacion_causado_compra_anticipo/'.rand().'">Causados - OC</a>';
     //echo '<br><a href="/correr_scripts/reactualizacion_obras_anticipo/'.rand().'">Causados - OB</a>';
     //echo '<br><a href="/correr_scripts/reactualizacion_servicios_anticipo/'.rand().'">Causados - Srv</a>';
     echo '<br><a href="/correr_scripts/reactualizacion_pagado/'.rand().'">Pagados</a>';
     echo '<br><br><a href="/correr_scripts/reactualizacion_rendiciones/'.rand().'">Rendiciones</a>';
     echo '<br><a href="/correr_scripts/reactualizacion_nota_debito/'.rand().'">Nota de debitos especiales</a>';
     echo '<br><a href="/correr_scripts/reactualizacion_reintegro/'.rand().'">Reintegro</a>';
     //echo '<br><br><br><a href="/correr_scripts/reactualizacion_todo">Reactualizar Todo </a>';
}

function reactualizacion_todo () {
	 echo "<br>Inicio ".date("h:i:s:u a");
     $this->reactualizacion_rcompromisos();
     echo "<br>RC-compromisos ".date("h:i:s:u a");
     $this->reactualizacion_orden_compra_parte1();
     echo "<br>OC-compromisos ".date("h:i:s:u a");
     $this->index_obra();
     echo "<br>OB-compromisos ".date("h:i:s:u a");
     $this->index_servicio();
     echo "<br>Srv-compromisos ".date("h:i:s:u a");
     $this->reactualizacion_ordenpago();
     echo "<br>OP-causados ".date("h:i:s:u a");
     $this->reactualizacion_causado_compra_anticipo();
     echo "<br>OC-causados ".date("h:i:s:u a");
     $this->reactualizacion_obras_anticipo();
     echo "<br>OB-causados ".date("h:i:s:u a");
     $this->reactualizacion_servicios_anticipo();
     echo "<br>Srv-causados ".date("h:i:s:u a");
     $this->reactualizacion_pagado();
     echo "<br>Pagado ".date("h:i:s:u a");
     $this->reactualizacion_rendiciones();
     echo "<br>Rendiciones ".date("h:i:s:u a");
     $this->reactualizacion_nota_debito();
     echo "<br>Nota de debito ".date("h:i:s:u a");
     $this->reactualizacion_reintegro();
     echo "<br>Reintegro ".date("h:i:s:u a");

}
function limpiar_tablas () {
     echo '<br><a href="/correr_scripts/">REGRESAR</a>';
     $this->cugd04->execute("UPDATE cfpd05 SET
compromiso_anual = 0 ,
causado_anual = 0 ,
pagado_anual = 0 ,
compromiso_ene = 0 ,
causado_ene = 0 ,
pagado_ene = 0 ,
compromiso_feb = 0 ,
causado_feb = 0 ,
pagado_feb = 0 ,
compromiso_mar = 0 ,
causado_mar = 0 ,
pagado_mar = 0 ,
compromiso_abr = 0 ,
causado_abr = 0 ,
pagado_abr = 0 ,
compromiso_may = 0 ,
causado_may = 0 ,
pagado_may = 0 ,
compromiso_jun = 0 ,
causado_jun = 0 ,
pagado_jun = 0 ,
compromiso_jul = 0 ,
causado_jul = 0 ,
pagado_jul = 0 ,
compromiso_ago = 0 ,
causado_ago = 0 ,
pagado_ago = 0 ,
compromiso_sep = 0 ,
causado_sep = 0 ,
pagado_sep = 0 ,
compromiso_oct = 0 ,
causado_oct = 0 ,
pagado_oct = 0 ,
compromiso_nov = 0 ,
causado_nov = 0 ,
pagado_nov = 0 ,
compromiso_dic = 0 ,
causado_dic = 0 ,
pagado_dic = 0, precompromiso_congelado = 0;
");
     $this->cugd04->execute("DELETE FROM cfpd21;");
     $this->cugd04->execute("DELETE FROM cfpd22;");
     $this->cugd04->execute("DELETE FROM cfpd23;");
     $this->cugd04->execute("DELETE FROM cfpd21_numero_asiento_compromiso;");
     $this->cugd04->execute("DELETE FROM cfpd22_numero_asiento_causado;");
     $this->cugd04->execute("DELETE FROM cfpd23_numero_asiento_pagado;");
     $this->cugd04->execute("ALTER SEQUENCE cfpd21_consecutivo_seq RESTART WITH 1;");
     $this->cugd04->execute("ALTER SEQUENCE cfpd22_consecutivo_seq RESTART WITH 1;");
     $this->cugd04->execute("ALTER SEQUENCE cfpd23_consecutivo_seq RESTART WITH 1;");
}



function index_obra(){



$this->reactualizacion_obrascompromisos();


}//fin function


function index_servicio(){
$this->reactualizacion_serviciocompromisos();


}//fin function
/**
 *
 * OBRAS COMPROMISOS
 */



function reactualizacion_obrascompromisos(){
  echo "<h4>reactualizacion_obrascompromisos</h4>";
  $partidas=$this->cobd01_contratoobras_cuerpo->execute("select
  a.*
  b.fecha_contrato_obra,
  b.fecha_proceso_registro,
  b.fecha_proceso_anulacion,
  b.condicion_actividad,
  b.denominacion_obra as concepto
   from
  cobd01_contratoobras_partidas a,cobd01_contratoobras_cuerpo b
where
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_contrato_obra = b.ano_contrato_obra and
  a.numero_contrato_obra = b.numero_contrato_obra");
  $camposT2="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_documento,numero_documento,cod_tipo_compromiso,fecha_documento,tipo_recurso,rif,cedula_identidad,cod_dir_superior,cod_coordinacion,cod_secretaria,cod_direccion,concepto,monto,condicion_actividad,dia_asiento_registro,mes_asiento_registro,ano_asiento_registro, numero_asiento_registro,username_registro,ano_anulacion,numero_anulacion,dia_asiento_anulacion,mes_asiento_anulacion,ano_asiento_anulacion,numero_asiento_anulacion,username_anulacion,ano_orden_pago,numero_orden_pago,beneficiario,condicion_juridica,fecha_proceso_registro,fecha_proceso_anulacion";
  $camposT3="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_documento,numero_documento,ano,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,monto,numero_control_compromiso";
  $values="";
  $monto=0;
  $i=0;
  $ann=2008;
  $j=1;
  $x=0;
  $suma = count($partidas);
  $numero_compromiso= $this->cfpd21_numero_asiento_compromiso->field('cfpd21_numero_asiento_compromiso.numero_compromiso', "ano_compromiso='$ann'", $order =null);
	if(!empty($numero_compromiso)){
		$num_base = $numero_compromiso;
		$numero_compromiso += $suma ;
		$sql_numero_compromiso = "UPDATE cfpd21_numero_asiento_compromiso SET numero_compromiso='$numero_compromiso' WHERE ano_compromiso='$ann' ;COMMIT;";
	}else{
		$num_base = 1;
		$numero_compromiso = $num_base;
		$sql_numero_compromiso = "INSERT INTO cfpd21_numero_asiento_compromiso VALUES('1', '11', '30', '11', '$ann', '$numero_compromiso')";
	}
    $sw_numero_compromiso = $this->cfpd21_numero_asiento_compromiso->query($sql_numero_compromiso);
	  foreach($partidas as $partida){
		  $cod_presi                           =         $partida[0]['cod_presi'];
		  $cod_entidad                         =         $partida[0]['cod_entidad'];
		  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
		  $cod_inst                            =         $partida[0]['cod_inst'];
		  $cod_dep                             =         $partida[0]['cod_dep'];
		  $this->Session->write('SScodpresi', $cod_presi);
		  $this->Session->write('SScodentidad', $cod_entidad);
		  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
		  $this->Session->write('SScodinst', $cod_inst);
		  $this->Session->write('SScoddep', $cod_dep);
		  $ano_documento                       =         $partida[0]['ano_contrato_obra'];
		  $fr                      =         $partida[0]['fecha_proceso_registro'];
		  $fecha_registro          =$fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
		  $fd                                  =         $partida[0]['fecha_contrato_obra'];//0123-56-89
		  $ano_fd=$fd[0].$fd[1].$fd[2].$fd[3];
		  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
		  $ano_fd= (int) $ano_fd;
		  $ano_fdpartida=(int) $partida[0]['ano'];
		  if($ano_fd!=$ano_fdpartida){
		     $fecha_documento=$fecha_registro;
		     $fd=$fecha_registro;
		  }else{
		  	$fecha_documento=$fd;
		  }
		  $numero_documento                    =         $partida[0]['numero_contrato_obra'];
		  $ano                                 =         $partida[0]['ano'];
		  $cod_sector                          =         $partida[0]['cod_sector'];
		  $cod_programa                        =         $partida[0]['cod_programa'];
		  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
		  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
		  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
		  $cod_partida                         =         $partida[0]['cod_partida'];
		  $cod_generica                        =         $partida[0]['cod_generica'];
		  $cod_especifica                      =         $partida[0]['cod_especifica'];
		  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
		  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
		  $monto                               =         $partida[0]['monto'];
		  $numero_control_compromiso           =         $partida[0]['numero_control_compromiso'];
		  $concepto = $partida[0]['concepto'];
 	      $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
		  $to = 1;
		  $td = 3;
		  $ta = 3;
		  $mt = $monto;
		  $ccp = str_replace("'","",$concepto);
		  $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, $fecha_documento, $mt, $ccp,$ann,$numero_documento,null, null, null,null, null, null,null, null,null, $num_base, $x);
          $j++;
          $x++;
          $this->cepd01_compromiso_partidas->execute("UPDATE cobd01_contratoobras_partidas SET numero_control_compromiso=".$dnco." WHERE cod_dep=".$cod_dep." and ano_contrato_obra=".$ano." and numero_contrato_obra='".$numero_documento."'");
          $this->cobd01_contratoobras_anticipo_partidas->execute("UPDATE cobd01_contratoobras_anticipo_partidas SET numero_control_compromiso = '".$dnco."'  where numero_contrato_obra='".$numero_documento."' and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
          $this->cobd01_contratoobras_valuacion_partidas->execute("UPDATE cobd01_contratoobras_valuacion_partidas SET numero_control_compromiso = '".$dnco."'  where numero_contrato_obra='".$numero_documento."' and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
         //$this->cepd03_ordenpago_partidas->execute("UPDATE cepd03_ordenpago_partidas SET numero_control_compromiso=".$dnco." WHERE cod_dep=".$cod_dep." and ano_orden_pago=".$ano_orden_pago." and numero_orden_pago=".$numero_orden_pago."");
         //echo "<br>".$j."F:".$fd." NC:".$dnco;
	}//fin foreach
$this->reactualizacion_obrascompromisos_anulados();
}//obras compromisos

function reactualizacion_obrascompromisos_anulados(){
echo "<h4>reactualizacion_obrascompromisos_anulados</h4>";
  $partidas=$this->cobd01_contratoobras_cuerpo->execute("select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_contrato_obra,
  a.numero_contrato_obra,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto,
  a.numero_control_compromiso,
  b.fecha_contrato_obra,
  b.fecha_proceso_registro,
  b.fecha_proceso_anulacion,
  b.numero_anulacion
   from
  cobd01_contratoobras_partidas a,cobd01_contratoobras_cuerpo b
where
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_contrato_obra = b.ano_contrato_obra and
  a.numero_contrato_obra = b.numero_contrato_obra and
  b.condicion_actividad=2");
$j=1;
  foreach($partidas as $partida){
  $cod_presi                           =         $partida[0]['cod_presi'];
  $cod_entidad                         =         $partida[0]['cod_entidad'];
  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
  $cod_inst                            =         $partida[0]['cod_inst'];
  $cod_dep                             =         $partida[0]['cod_dep'];
  $this->Session->write('SScodpresi', $cod_presi);
  $this->Session->write('SScodentidad', $cod_entidad);
  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
  $this->Session->write('SScodinst', $cod_inst);
  $this->Session->write('SScoddep', $cod_dep);
  $ano_documento                       =         $partida[0]['ano_contrato_obra'];
  $fr                      =         $partida[0]['fecha_proceso_registro'];
  $fecha_registro          =$fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
  $fd                                  =         $partida[0]['fecha_contrato_obra'];//0123-56-89
    $ano_fd=$fd[0].$fd[1].$fd[2].$fd[3];
  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
  $ano_fd= (int) $ano_fd;
  $ano_fdpartida=(int) $partida[0]['ano'];
  if($ano_fd!=$ano_fdpartida){
     $fecha_documento=$fecha_registro;
     $fd=$fecha_registro;
  }else{
  	$fecha_documento=$fd;
  }

  $numero_documento                    =         $partida[0]['numero_contrato_obra'];
  $ano                                 =         $partida[0]['ano'];
  $cod_sector                          =         $partida[0]['cod_sector'];
  $cod_programa                        =         $partida[0]['cod_programa'];
  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
  $cod_partida                         =         $partida[0]['cod_partida'];
  $cod_generica                        =         $partida[0]['cod_generica'];
  $cod_especifica                      =         $partida[0]['cod_especifica'];
  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
  $monto                               =         $partida[0]['monto'];
  $numero_control_compromiso           =         $partida[0]['numero_control_compromiso'];
  $numero_anulacion           =         $partida[0]['numero_anulacion'];
  //str_replace("'","",$concepto); = $partida[0]['concepto'];
$busca_concepto_anulacion=$this->cugd03_acta_anulacion_cuerpo->findAll("cod_dep='".$cod_dep."' and numero_acta_anulacion='".$numero_anulacion."' and tipo_operacion='233' and numero_documento='".$numero_documento."'");
$concepto_anulacion= $busca_concepto_anulacion[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"];
////echo "<br>".$concepto_anulacion;
                                   $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
								   $to = 2;
								   $td = 3;
								   $ta = 3;
								   $nda=$numero_control_compromiso;
								   $mt = $monto;
								   $ccp = str_replace("'","",$concepto_anulacion);
								   $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, $fecha_documento, $mt, $ccp,$ano,$numero_documento,$nda, null, null,null, null, null,null, null,null, null, null,null);
//echo "<br>".$j."F:".$fd." NC:".$dnco;
$j++;
}
$this->reactualizacion_modificacion_obrascompromisos();
}//compromisos obras

/**
 * COMPROMISOS SERVICIOS
 *
 */

function reactualizacion_serviciocompromisos(){
	echo "<h4>reactualizacion_serviciocompromisos</h4>";
  $partidas=$this->cepd02_contratoservicio_cuerpo->execute("select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_contrato_servicio,
  a.numero_contrato_servicio,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto,
  a.numero_control_compromiso,
  b.fecha_contrato_servicio,
  b.fecha_proceso_registro,
  b.fecha_proceso_anulacion,
  b.concepto
   from
  cepd02_contratoservicio_partidas a,cepd02_contratoservicio_cuerpo b
where
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_contrato_servicio = b.ano_contrato_servicio and
  a.numero_contrato_servicio = b.numero_contrato_servicio");
             $camposT2="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_documento,numero_documento,cod_tipo_compromiso,fecha_documento,tipo_recurso,rif,cedula_identidad,cod_dir_superior,cod_coordinacion,cod_secretaria,cod_direccion,concepto,monto,condicion_actividad,dia_asiento_registro,mes_asiento_registro,ano_asiento_registro, numero_asiento_registro,username_registro,ano_anulacion,numero_anulacion,dia_asiento_anulacion,mes_asiento_anulacion,ano_asiento_anulacion,numero_asiento_anulacion,username_anulacion,ano_orden_pago,numero_orden_pago,beneficiario,condicion_juridica,fecha_proceso_registro,fecha_proceso_anulacion";
			 $camposT3="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_documento,numero_documento,ano,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,monto,numero_control_compromiso";
			 $values="";
			 $monto=0;
			 $i=0;
             $ann=2008;

					  $j=1;
                      $x=0;
                      $suma = count($partidas);
					  $numero_compromiso= $this->cfpd21_numero_asiento_compromiso->field('cfpd21_numero_asiento_compromiso.numero_compromiso', "ano_compromiso='$ann'", $order =null);
						if(!empty($numero_compromiso)){
							$num_base = $numero_compromiso;
							$numero_compromiso += $suma ;
							$sql_numero_compromiso = "UPDATE cfpd21_numero_asiento_compromiso SET numero_compromiso='$numero_compromiso' WHERE ano_compromiso='$ann' ;COMMIT;";
						}else{
							$num_base = 1;
							$numero_compromiso = $num_base;
							$sql_numero_compromiso = "INSERT INTO cfpd21_numero_asiento_compromiso VALUES('1', '11', '30', '11', '$ann', '$numero_compromiso')";
						}
					    $sw_numero_compromiso = $this->cfpd21_numero_asiento_compromiso->query($sql_numero_compromiso);
  foreach($partidas as $partida){
  $cod_presi                           =         $partida[0]['cod_presi'];
  $cod_entidad                         =         $partida[0]['cod_entidad'];
  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
  $cod_inst                            =         $partida[0]['cod_inst'];
  $cod_dep                             =         $partida[0]['cod_dep'];
  $this->Session->write('SScodpresi', $cod_presi);
  $this->Session->write('SScodentidad', $cod_entidad);
  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
  $this->Session->write('SScodinst', $cod_inst);
  $this->Session->write('SScoddep', $cod_dep);
  $ano_documento                       =         $partida[0]['ano_contrato_servicio'];
  $fr                      =         $partida[0]['fecha_proceso_registro'];
  $fecha_registro          =$fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
  $fd                                  =         $partida[0]['fecha_contrato_servicio'];//0123-56-89
  $ano_fd=$fd[0].$fd[1].$fd[2].$fd[3];
  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
  $ano_fd= (int) $ano_fd;
  $ano_fdpartida=(int) $partida[0]['ano'];
  if($ano_fd!=$ano_fdpartida){
     $fecha_documento=$fecha_registro;
     $fd=$fecha_registro;
  }else{
  	$fecha_documento=$fd;
  }

  $numero_documento                    =         $partida[0]['numero_contrato_servicio'];
  $ano                                 =         $partida[0]['ano'];
  $cod_sector                          =         $partida[0]['cod_sector'];
  $cod_programa                        =         $partida[0]['cod_programa'];
  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
  $cod_partida                         =         $partida[0]['cod_partida'];
  $cod_generica                        =         $partida[0]['cod_generica'];
  $cod_especifica                      =         $partida[0]['cod_especifica'];
  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
  $monto                               =         $partida[0]['monto'];
  $numero_control_compromiso           =         $partida[0]['numero_control_compromiso'];
  $concepto = $partida[0]['concepto'];
					     	       $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
								   $to = 1;
								   $td = 3;
								   $ta = 4;
								   $mt = $monto;
								   $ccp = str_replace("'","",$concepto);
								   $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, $fecha_documento, $mt, $ccp,$ann,$numero_documento,null, null, null,null, null, null,null, null,null, $num_base, $x);
					         $j++;
					         $x++;
					         $this->cepd02_contratoservicio_partidas->execute("UPDATE cepd02_contratoservicio_partidas SET numero_control_compromiso=".$dnco." WHERE cod_dep=".$cod_dep." and ano_contrato_servicio=".$ano." and numero_contrato_servicio='".$numero_documento."'");
					         $this->cepd02_contratoservicio_anticipo_partidas->execute("UPDATE cepd02_contratoservicio_anticipo_partidas SET numero_control_compromiso = '".$dnco."'  where numero_contrato_servicio='".$numero_documento."' and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
					         $this->cepd02_contratoservicio_valuacion_partidas->execute("UPDATE cepd02_contratoservicio_valuacion_partidas SET numero_control_compromiso = '".$dnco."'  where numero_contrato_servicio='".$numero_documento."' and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
					         //$this->cepd03_ordenpago_partidas->execute("UPDATE cepd03_ordenpago_partidas SET numero_control_compromiso=".$dnco." WHERE cod_dep=".$cod_dep." and ano_orden_pago=".$ano_orden_pago." and numero_orden_pago=".$numero_orden_pago."");
					         //echo "<br>".$j."F:".$fd." NC:".$dnco;
}//fin foreach
$this->reactualizacion_servicioscompromisos_anulados();
}//compromisos servicio

function reactualizacion_servicioscompromisos_anulados(){
	echo "<h4>reactualizacion_servicioscompromisos_anulados</h4>";
  //cepd02_contratoservicio_cuerpo cepd02_contratoservicio_partidas
  $partidas=$this->cepd02_contratoservicio_cuerpo->execute("select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_contrato_servicio,
  a.numero_contrato_servicio,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto,
  a.numero_control_compromiso,
  b.fecha_contrato_servicio,
  b.fecha_proceso_registro,
  b.fecha_proceso_anulacion,
  b.numero_anulacion
   from
  cepd02_contratoservicio_partidas a,cepd02_contratoservicio_cuerpo b
where
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_contrato_servicio = b.ano_contrato_servicio and
  a.numero_contrato_servicio = b.numero_contrato_servicio and
  b.condicion_actividad=2");
  $j=1;
  foreach($partidas as $partida){
  $cod_presi                           =         $partida[0]['cod_presi'];
  $cod_entidad                         =         $partida[0]['cod_entidad'];
  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
  $cod_inst                            =         $partida[0]['cod_inst'];
  $cod_dep                             =         $partida[0]['cod_dep'];
  $this->Session->write('SScodpresi', $cod_presi);
  $this->Session->write('SScodentidad', $cod_entidad);
  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
  $this->Session->write('SScodinst', $cod_inst);
  $this->Session->write('SScoddep', $cod_dep);
  $ano_documento                       =         $partida[0]['ano_contrato_servicio'];
  $fr                      =         $partida[0]['fecha_proceso_registro'];
  $fecha_registro          =$fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
  $fd                                  =         $partida[0]['fecha_contrato_servicio'];//0123-56-89
  $ano_fd=$fd[0].$fd[1].$fd[2].$fd[3];
  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
  $ano_fd= (int) $ano_fd;
  $ano_fdpartida=(int) $partida[0]['ano'];
  if($ano_fd!=$ano_fdpartida){
     $fecha_documento=$fecha_registro;
     $fd=$fecha_registro;
  }else{
  	$fecha_documento=$fd;
  }

  $numero_documento                    =         $partida[0]['numero_contrato_servicio'];
  $ano                                 =         $partida[0]['ano'];
  $cod_sector                          =         $partida[0]['cod_sector'];
  $cod_programa                        =         $partida[0]['cod_programa'];
  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
  $cod_partida                         =         $partida[0]['cod_partida'];
  $cod_generica                        =         $partida[0]['cod_generica'];
  $cod_especifica                      =         $partida[0]['cod_especifica'];
  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
  $monto                               =         $partida[0]['monto'];
  $numero_control_compromiso           =         $partida[0]['numero_control_compromiso'];
  $numero_anulacion           =         $partida[0]['numero_anulacion'];
  //$concepto = $partida[0]['concepto'];
$busca_concepto_anulacion=$this->cugd03_acta_anulacion_cuerpo->findAll("cod_dep='".$cod_dep."' and numero_acta_anulacion='".$numero_anulacion."' and tipo_operacion='234' and numero_documento='".$numero_documento."'");
$concepto_anulacion= $busca_concepto_anulacion[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"];
//echo "<br>".$concepto_anulacion;
                                   $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
								   $to = 2;
								   $td = 3;
								   $ta = 4;
								   $nda=$numero_control_compromiso;
								   $mt = $monto;
								   $ccp = str_replace("'","",$concepto_anulacion);
								   $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, $fecha_documento, $mt, $ccp,$ano,$numero_documento,$nda, null, null,null, null, null,null, null,null, null, null,null);
//echo "<br>".$j."F:".$fd." NC:".$dnco;
$j++;
}

}//compromisos servicios anulado


/*********************************************
 * MODIFICACIONES OBRAS COMPROMISOS
 * **************************************
 */

function reactualizacion_modificacion_obrascompromisos(){
  echo "<h4>reactualizacion_modificacion_obrascompromisos</h4>";
  $partidas=$this->cobd01_co_modificacion_partidas->execute("select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_contrato_obra,
  a.numero_contrato_obra,
  a.numero_modificacion,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto,
  a.numero_control_compromiso,
  c.fecha_contrato_obra as fecha_modificacion,
  c.fecha_proceso_registro,
  b.fecha_proceso_anulacion,
  b.condicion_actividad,b.tipo_modificacion,
  b.observaciones as concepto
   from
  cobd01_contratoobras_modificacion_partidas a,cobd01_contratoobras_modificacion_cuerpo b,cobd01_contratoobras_cuerpo c
where
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and b.cod_dep = c.cod_dep and
  a.ano_contrato_obra = b.ano_contrato_obra and b.ano_contrato_obra = c.ano_contrato_obra and
  a.numero_contrato_obra = b.numero_contrato_obra and b.numero_contrato_obra = c.numero_contrato_obra");
             $camposT2="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_documento,numero_documento,cod_tipo_compromiso,fecha_documento,tipo_recurso,rif,cedula_identidad,cod_dir_superior,cod_coordinacion,cod_secretaria,cod_direccion,concepto,monto,condicion_actividad,dia_asiento_registro,mes_asiento_registro,ano_asiento_registro, numero_asiento_registro,username_registro,ano_anulacion,numero_anulacion,dia_asiento_anulacion,mes_asiento_anulacion,ano_asiento_anulacion,numero_asiento_anulacion,username_anulacion,ano_orden_pago,numero_orden_pago,beneficiario,condicion_juridica,fecha_proceso_registro,fecha_proceso_anulacion";
			 $camposT3="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_documento,numero_documento,ano,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,monto,numero_control_compromiso";
			 $values="";
			 $monto=0;
			 $i=0;
             $ann=2008;

					  $j=1;
                      $x=0;
                      $suma = count($partidas);
					  $numero_compromiso= $this->cfpd21_numero_asiento_compromiso->field('cfpd21_numero_asiento_compromiso.numero_compromiso', "ano_compromiso='$ann'", $order =null);
						if(!empty($numero_compromiso)){
							$num_base = $numero_compromiso;
							$numero_compromiso += $suma ;
							$sql_numero_compromiso = "UPDATE cfpd21_numero_asiento_compromiso SET numero_compromiso='$numero_compromiso' WHERE ano_compromiso='$ann' ;COMMIT;";
						}else{
							$num_base = 1;
							$numero_compromiso = $num_base;
							$sql_numero_compromiso = "INSERT INTO cfpd21_numero_asiento_compromiso VALUES('1', '11', '30', '11', '$ann', '$numero_compromiso')";
						}
					    $sw_numero_compromiso = $this->cfpd21_numero_asiento_compromiso->query($sql_numero_compromiso);
  foreach($partidas as $partida){
  $cod_presi                           =         $partida[0]['cod_presi'];
  $cod_entidad                         =         $partida[0]['cod_entidad'];
  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
  $cod_inst                            =         $partida[0]['cod_inst'];
  $cod_dep                             =         $partida[0]['cod_dep'];
  $this->Session->write('SScodpresi', $cod_presi);
  $this->Session->write('SScodentidad', $cod_entidad);
  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
  $this->Session->write('SScodinst', $cod_inst);
  $this->Session->write('SScoddep', $cod_dep);
  $ano_documento                       =         $partida[0]['ano_contrato_obra'];
  $fr                      =         $partida[0]['fecha_proceso_registro'];//0123-56-89
  $fecha_registro          =$fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
  $fd                                  =         $partida[0]['fecha_modificacion'];//0123-56-89 esta fecha es la del contrato de la obra pero tiene un alias fecha_modificacion
    $ano_fd=$fd[0].$fd[1].$fd[2].$fd[3];
  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
  $ano_fd= (int) $ano_fd;
  $ano_fdpartida=(int) $partida[0]['ano'];
  if($ano_fd!=$ano_fdpartida){
     $fecha_documento=$fecha_registro;
     $fd=$fecha_registro;
  }else{
  	$fecha_documento=$fd;
  	$fd=$fd;
  }

  $numero_documento                    =         $partida[0]['numero_contrato_obra'];
  $ano                                 =         $partida[0]['ano'];
  $cod_sector                          =         $partida[0]['cod_sector'];
  $cod_programa                        =         $partida[0]['cod_programa'];
  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
  $cod_partida                         =         $partida[0]['cod_partida'];
  $cod_generica                        =         $partida[0]['cod_generica'];
  $cod_especifica                      =         $partida[0]['cod_especifica'];
  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
  $monto                               =         $partida[0]['monto'];
  $numero_control_compromiso           =         $partida[0]['numero_control_compromiso'];
  $concepto = $partida[0]['concepto'];
  $tipo_modificacion =  $partida[0]['tipo_modificacion'];
  if($tipo_modificacion==1){
  	$tm='3';
  }else if($tipo_modificacion==2){
  	$tm='4';
  }
       $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
	   $to = 1;
	   $td = 2;
	   $ta = $tm;
	   $mt = $monto;
	   $ccp = str_replace("'","",$concepto);
	   $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, $fecha_documento, $mt, $ccp,$ann,$numero_documento,null, null, null,null, null, null,null, null,null, $num_base, $x);
 $j++;
 $x++;
 $this->cobd01_co_modificacion_partidas->execute("UPDATE cobd01_contratoobras_modificacion_partidas SET numero_control_compromiso=".$dnco." WHERE  ano_contrato_obra=".$ano." and numero_contrato_obra='".$numero_documento."' and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
 echo "<br>".$to.$td.$ta." _ ".$fecha_documento." _ ".$numero_documento;
}//fin foreach


$this->reactualizacion_modificacion_obrascompromisos_anulados();
}//obras compromisos

function reactualizacion_modificacion_obrascompromisos_anulados(){
echo "<h4>reactualizacion_modificacion_obrascompromisos_anulados</h4>";
  $partidas=$this->cobd01_contratoobras_cuerpo->execute("select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_contrato_obra,
  a.numero_contrato_obra,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto,
  a.numero_control_compromiso,
   c.fecha_contrato_obra as fecha_modificacion,b.numero_modificacion,b.tipo_modificacion,
  c.fecha_proceso_registro,
  b.fecha_proceso_anulacion,
  b.numero_anulacion
   from
  cobd01_contratoobras_modificacion_partidas a,cobd01_contratoobras_modificacion_cuerpo b,cobd01_contratoobras_cuerpo c
where
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and b.cod_dep = c.cod_dep and
  a.ano_contrato_obra = b.ano_contrato_obra and b.ano_contrato_obra = c.ano_contrato_obra and
  a.numero_contrato_obra = b.numero_contrato_obra and b.numero_contrato_obra = c.numero_contrato_obra and
  b.condicion_actividad=2");
$j=1;
  foreach($partidas as $partida){
  $cod_presi                           =         $partida[0]['cod_presi'];
  $cod_entidad                         =         $partida[0]['cod_entidad'];
  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
  $cod_inst                            =         $partida[0]['cod_inst'];
  $cod_dep                             =         $partida[0]['cod_dep'];
  $this->Session->write('SScodpresi', $cod_presi);
  $this->Session->write('SScodentidad', $cod_entidad);
  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
  $this->Session->write('SScodinst', $cod_inst);
  $this->Session->write('SScoddep', $cod_dep);
  $ano_documento                       =         $partida[0]['ano_contrato_obra'];
 $fr                      =         $partida[0]['fecha_proceso_registro'];
  $fecha_registro          =$fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
  $fd                                  =         $partida[0]['fecha_modificacion'];//0123-56-89
    $ano_fd=$fd[0].$fd[1].$fd[2].$fd[3];
  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
  $ano_fd= (int) $ano_fd;
  $ano_fdpartida=(int) $partida[0]['ano'];
  if($ano_fd!=$ano_fdpartida){
     $fecha_documento=$fecha_registro;
     $fd=$fecha_registro;
  }else{
  	$fecha_documento=$fd;
  }

  $numero_documento                    =         $partida[0]['numero_contrato_obra'];
  $ano                                 =         $partida[0]['ano'];
  $cod_sector                          =         $partida[0]['cod_sector'];
  $cod_programa                        =         $partida[0]['cod_programa'];
  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
  $cod_partida                         =         $partida[0]['cod_partida'];
  $cod_generica                        =         $partida[0]['cod_generica'];
  $cod_especifica                      =         $partida[0]['cod_especifica'];
  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
  $monto                               =         $partida[0]['monto'];
  $numero_control_compromiso           =         $partida[0]['numero_control_compromiso'];
  $numero_anulacion           =         $partida[0]['numero_anulacion'];
  $tipo_modificacion           =         $partida[0]['tipo_modificacion'];
  $nro_modificacion           =         $partida[0]['numero_modificacion'];
  if($tipo_modificacion==1){
  	 $tm='3';
  }else if($tipo_modificacion==2){
  	 $tm='4';
  }
  //$concepto = $partida[0]['concepto'];
$busca_concepto_anulacion=$this->cugd03_acta_anulacion_cuerpo->findAll("cod_dep='".$cod_dep."' and numero_acta_anulacion='".$numero_anulacion."' and tipo_operacion='22".$tm."' and numero_documento='".$numero_documento."'");
$concepto_anulacion= $busca_concepto_anulacion[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"];
//echo "<br>".$concepto_anulacion;
                                   $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
								   $to = 2;
								   $td = 2;
								   $ta = $tm;
								   $nda=$numero_control_compromiso;
								   $mt = $monto;
								   $ccp = str_replace("'","",$concepto_anulacion);
								   $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, $fecha_documento, $mt, $ccp,$ano,$numero_documento,$nro_modificacion, null, null,null, null, null,null, $nda,null, null, null);
//echo "<br>".$j."F:".$fd." NC:".$dnco;
echo "<br>".$to.$td.$ta." _ ".$fecha_documento." _ ".$numero_documento;
$j++;
}

}//compromisos obras moddificacion

/**
 * COMPROMISOS SERVICIOS modificacion
 *
 */

function reactualizacion_modificacion_serviciocompromisos(){
	echo "<h4>reactualizacion_modificacion_serviciocompromisos</h4>";
  $partidas=$this->cepd02_contratoservicio_cuerpo->execute("select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_contrato_servicio,
  a.numero_contrato_servicio,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto,
  a.numero_control_compromiso,
  c.fecha_contrato_servicio as fecha_modificacion,b.numero_modificacion,b.tipo_modificacion,
  c.fecha_proceso_registro,
  b.fecha_proceso_anulacion,
  b.numero_anulacion
   from
  cobd01_contratoservicio_modificacion_partidas a,cobd01_contratoservicio_modificacion_cuerpo b,cobd01_contratoservicio_cuerpo c
where
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and b.cod_dep = c.cod_dep and
  a.ano_contrato_servicio = b.ano_contrato_servicio and b.ano_contrato_obra = c.ano_contrato_servicio and
  a.numero_contrato_servicio = b.numero_contrato_servicio and b.numero_contrato_servicio = c.numero_contrato_servicio");
  $camposT2="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_documento,numero_documento,cod_tipo_compromiso,fecha_documento,tipo_recurso,rif,cedula_identidad,cod_dir_superior,cod_coordinacion,cod_secretaria,cod_direccion,concepto,monto,condicion_actividad,dia_asiento_registro,mes_asiento_registro,ano_asiento_registro, numero_asiento_registro,username_registro,ano_anulacion,numero_anulacion,dia_asiento_anulacion,mes_asiento_anulacion,ano_asiento_anulacion,numero_asiento_anulacion,username_anulacion,ano_orden_pago,numero_orden_pago,beneficiario,condicion_juridica,fecha_proceso_registro,fecha_proceso_anulacion";
  $camposT3="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_documento,numero_documento,ano,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,monto,numero_control_compromiso";
  $values="";
  $monto=0;
  $i=0;
  $ann=2008;
  $j=1;
  $x=0;
  $suma = count($partidas);
  $numero_compromiso= $this->cfpd21_numero_asiento_compromiso->field('cfpd21_numero_asiento_compromiso.numero_compromiso', "ano_compromiso='$ann'", $order =null);
	if(!empty($numero_compromiso)){
		$num_base = $numero_compromiso;
		$numero_compromiso += $suma ;
		$sql_numero_compromiso = "UPDATE cfpd21_numero_asiento_compromiso SET numero_compromiso='$numero_compromiso' WHERE ano_compromiso='$ann' ;COMMIT;";
	}else{
		$num_base = 1;
		$numero_compromiso = $num_base;
		$sql_numero_compromiso = "INSERT INTO cfpd21_numero_asiento_compromiso VALUES('1', '11', '30', '11', '$ann', '$numero_compromiso')";
	}
    $sw_numero_compromiso = $this->cfpd21_numero_asiento_compromiso->query($sql_numero_compromiso);
  foreach($partidas as $partida){
  $cod_presi                           =         $partida[0]['cod_presi'];
  $cod_entidad                         =         $partida[0]['cod_entidad'];
  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
  $cod_inst                            =         $partida[0]['cod_inst'];
  $cod_dep                             =         $partida[0]['cod_dep'];
  $this->Session->write('SScodpresi', $cod_presi);
  $this->Session->write('SScodentidad', $cod_entidad);
  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
  $this->Session->write('SScodinst', $cod_inst);
  $this->Session->write('SScoddep', $cod_dep);
  $ano_documento                       =         $partida[0]['ano_contrato_servicio'];
  $fecha_documento                     =         $this->Cfecha($partida[0]['fecha_contrato_servicio'],"D/M/A");
  $fd                                  =         $this->Cfecha($partida[0]['fecha_contrato_servicio'],"D/M/A");
  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
  $fecha_documento=$fd;
  $numero_documento                    =         $partida[0]['numero_contrato_servicio'];
  $ano                                 =         $partida[0]['ano'];
  $cod_sector                          =         $partida[0]['cod_sector'];
  $cod_programa                        =         $partida[0]['cod_programa'];
  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
  $cod_partida                         =         $partida[0]['cod_partida'];
  $cod_generica                        =         $partida[0]['cod_generica'];
  $cod_especifica                      =         $partida[0]['cod_especifica'];
  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
  $monto                               =         $partida[0]['monto'];
  $numero_control_compromiso           =         $partida[0]['numero_control_compromiso'];
  $concepto = $partida[0]['concepto'];
  $tipo_modificacion =  $partida[0]['tipo_modificacion'];
  if($tipo_modificacion==1){
  	$tm='5';
  }else if($tipo_modificacion==2){
  	$tm='6';
  }
					     	       $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
								   $to = 1;
								   $td = 3;
								   $ta = $tm;
								   $mt = $monto;
								   $ccp = str_replace("'","",$concepto);
								   $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, $fecha_documento, $mt, $ccp,$ann,$numero_documento,null, null, null,null, null, null,null, null,null, $num_base, $x);
					         $j++;
					         $x++;
					         $this->cepd02_contratoservicio_partidas->execute("UPDATE cepd02_contratoservicio_partidas SET numero_control_compromiso=".$dnco." WHERE cod_dep=".$cod_dep." and ano_contrato_servicio=".$ano." and numero_contrato_servicio='".$numero_documento."'");
					         $this->cepd02_contratoservicio_anticipo_partidas->execute("UPDATE cepd02_contratoservicio_anticipo_partidas SET numero_control_compromiso = '".$dnco."'  where numero_contrato_servicio='".$numero_documento."' and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
					         $this->cepd02_contratoservicio_valuacion_partidas->execute("UPDATE cepd02_contratoservicio_valuacion_partidas SET numero_control_compromiso = '".$dnco."'  where numero_contrato_servicio='".$numero_documento."' and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
					         //$this->cepd03_ordenpago_partidas->execute("UPDATE cepd03_ordenpago_partidas SET numero_control_compromiso=".$dnco." WHERE cod_dep=".$cod_dep." and ano_orden_pago=".$ano_orden_pago." and numero_orden_pago=".$numero_orden_pago."");
					         echo "<br>".$j."F:".$fd." NC:".$dnco;
}//fin foreach

$this->reactualizacion_modificacion_servicioscompromisos_anulados();
}//compromisos servicio modificaciones

function reactualizacion_modificacion_servicioscompromisos_anulados(){
	echo "<h4>reactualizacion_modificacion_servicioscompromisos_anulados</h4>";
  //cepd02_contratoservicio_cuerpo cepd02_contratoservicio_partidas

  $partidas=$this->cepd02_contratoservicio_cuerpo->execute("select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_contrato_servicio,
  a.numero_contrato_servicio,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto,
  a.numero_control_compromiso,
   c.fecha_contrato_obra as fecha_modificacion,b.numero_modificacion,b.tipo_modificacion,
  c.fecha_proceso_registro,
  b.fecha_proceso_anulacion,
  b.numero_anulacion
   from
  cepd02_contratoservicio_modificacion_partidas a,cepd02_contratoservicio_modificacion_cuerpo b,cepd02_contratoservicio_cuerpo c
where
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and b.cod_dep = c.cod_dep and
  a.ano_contrato_servicio = b.ano_contrato_servicio and b.ano_contrato_servicio = c.ano_contrato_servicio and
  a.numero_contrato_servicio = b.numero_contrato_servicio and b.numero_contrato_servicio = c.numero_contrato_servicio and
  b.condicion_actividad=2");
  $j=1;
  foreach($partidas as $partida){
  $cod_presi                           =         $partida[0]['cod_presi'];
  $cod_entidad                         =         $partida[0]['cod_entidad'];
  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
  $cod_inst                            =         $partida[0]['cod_inst'];
  $cod_dep                             =         $partida[0]['cod_dep'];
  $this->Session->write('SScodpresi', $cod_presi);
  $this->Session->write('SScodentidad', $cod_entidad);
  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
  $this->Session->write('SScodinst', $cod_inst);
  $this->Session->write('SScoddep', $cod_dep);
  $ano_documento                       =         $partida[0]['ano_contrato_servicio'];
  $fecha_documento                     =         $this->Cfecha($partida[0]['fecha_contrato_servicio'],"D/M/A");
  $fd                                  =         $this->Cfecha($partida[0]['fecha_contrato_servicio'],"D/M/A");
  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
  $fecha_documento=$fd;
  $numero_documento                    =         $partida[0]['numero_contrato_servicio'];
  $ano                                 =         $partida[0]['ano'];
  $cod_sector                          =         $partida[0]['cod_sector'];
  $cod_programa                        =         $partida[0]['cod_programa'];
  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
  $cod_partida                         =         $partida[0]['cod_partida'];
  $cod_generica                        =         $partida[0]['cod_generica'];
  $cod_especifica                      =         $partida[0]['cod_especifica'];
  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
  $monto                               =         $partida[0]['monto'];
  $numero_control_compromiso           =         $partida[0]['numero_control_compromiso'];
  $numero_anulacion           =         $partida[0]['numero_anulacion'];
  $tipo_modificacion           =         $partida[0]['tipo_modificacion'];
  $nro_modificacion           =         $partida[0]['numero_modificacion'];
  if($tipo_modificacion==1){
  	 $tm='3';
  }else if($tipo_modificacion==2){
  	 $tm='4';
  }
  $busca_concepto_anulacion=$this->cugd03_acta_anulacion_cuerpo->findAll("cod_dep='".$cod_dep."' and numero_acta_anulacion='".$numero_anulacion."' and tipo_operacion='234' and numero_documento='".$numero_documento."'");
  $concepto_anulacion= $busca_concepto_anulacion[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"];

  $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
  $to = 2;
  $td = 3;
  $ta = $tm;
  $nda=$numero_control_compromiso;
  $mt = $monto;
  $ccp = str_replace("'","",$concepto_anulacion);
  $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, $fecha_documento, $mt, $ccp,$ano,$numero_documento,$nda, null, null,null, null, null,null, null,null, null, null,null);
  //echo "<br>".$j."F:".$fd." NC:".$dnco;
  $j++;
}

}//compromisos modificacion servicios anulado


//################################3
/**
 *
 * COMPROMISOS RC
 */

function reactualizacion_rcompromisos(){
  echo "compromisos-rc";
  $partidas=$this->cepd01_compromiso_cuerpo->execute("select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_documento,
  a.numero_documento,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto,
  a.numero_control_compromiso,
  b.fecha_documento,
  b.ano_orden_pago,
  b.numero_orden_pago,
  b.fecha_proceso_registro,
  b.fecha_proceso_anulacion,
  b.condicion_actividad,
  b.concepto
   from
  cepd01_compromiso_partidas a,cepd01_compromiso_cuerpo b
where
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_documento = b.ano_documento and
  a.numero_documento = b.numero_documento");
             $camposT2="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_documento,numero_documento,cod_tipo_compromiso,fecha_documento,tipo_recurso,rif,cedula_identidad,cod_dir_superior,cod_coordinacion,cod_secretaria,cod_direccion,concepto,monto,condicion_actividad,dia_asiento_registro,mes_asiento_registro,ano_asiento_registro, numero_asiento_registro,username_registro,ano_anulacion,numero_anulacion,dia_asiento_anulacion,mes_asiento_anulacion,ano_asiento_anulacion,numero_asiento_anulacion,username_anulacion,ano_orden_pago,numero_orden_pago,beneficiario,condicion_juridica,fecha_proceso_registro,fecha_proceso_anulacion";
			 $camposT3="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_documento,numero_documento,ano,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,monto,numero_control_compromiso";
			 $values="";
			 $monto=0;
			 $i=0;
             $ann=2008;

					  $j=1;
                      $x=0;
                      $suma = count($partidas);
					  $numero_compromiso= $this->cfpd21_numero_asiento_compromiso->field('cfpd21_numero_asiento_compromiso.numero_compromiso', "ano_compromiso='$ann'", $order =null);
						if(!empty($numero_compromiso)){
							$num_base = $numero_compromiso;
							$numero_compromiso += $suma ;
							$sql_numero_compromiso = "UPDATE cfpd21_numero_asiento_compromiso SET numero_compromiso='$numero_compromiso' WHERE ano_compromiso='$ann' ;COMMIT;";
						}else{
							$num_base = 1;
							$numero_compromiso = $num_base;
							$sql_numero_compromiso = "INSERT INTO cfpd21_numero_asiento_compromiso VALUES('1', '11', '30', '11', '$ann', '$numero_compromiso')";
						}
					    $sw_numero_compromiso = $this->cfpd21_numero_asiento_compromiso->query($sql_numero_compromiso);
  //echo count($partidas);
  foreach($partidas as $partida){
  $cod_presi                           =         $partida[0]['cod_presi'];
  $cod_entidad                         =         $partida[0]['cod_entidad'];
  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
  $cod_inst                            =         $partida[0]['cod_inst'];
  $cod_dep                             =         $partida[0]['cod_dep'];
  $this->Session->write('SScodpresi', $cod_presi);
  $this->Session->write('SScodentidad', $cod_entidad);
  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
  $this->Session->write('SScodinst', $cod_inst);
  $this->Session->write('SScoddep', $cod_dep);
  $ano_documento                       =         $partida[0]['ano_documento'];
  $fr                      =         $partida[0]['fecha_proceso_registro'];
  $fecha_registro          =$fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
  //$fecha_documento                     =         $this->Cfecha($partida[0]['fecha_contrato_obra'],"D/M/A");
  $fd                                  =         $partida[0]['fecha_documento'];//0123-56-89
  $ano_fd=$fd[0].$fd[1].$fd[2].$fd[3];
  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
  $ano_fd= (int) $ano_fd;
  $ano_fdpartida=(int) $partida[0]['ano'];
  if($ano_fd!=$ano_fdpartida){
     $fecha_documento=$fecha_registro;
     $fd=$fecha_registro;
  }else{
  	$fecha_documento=$fd;
  }
  $numero_documento                    =         $partida[0]['numero_documento'];
  $ano                                 =         $partida[0]['ano'];
  $cod_sector                          =         $partida[0]['cod_sector'];
  $cod_programa                        =         $partida[0]['cod_programa'];
  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
  $cod_partida                         =         $partida[0]['cod_partida'];
  $cod_generica                        =         $partida[0]['cod_generica'];
  $cod_especifica                      =         $partida[0]['cod_especifica'];
  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
  $monto                               =         $partida[0]['monto'];
  $numero_control_compromiso           =         $partida[0]['numero_control_compromiso'];
  $numero_orden_pago                   =         $partida[0]['numero_orden_pago'];
  $ano_orden_pago                      =         $partida[0]['ano_orden_pago'];
  $concepto = $partida[0]['concepto'];
					     	       $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
								   $to = 1;
								   $td = 3;
								   $ta = 1;
								   $mt = $monto;
								   $ccp = str_replace("'","",$concepto);
								   $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, $fecha_documento, $mt, $ccp,$ann,$numero_documento,null, null, null,null, null, null,null, null,null, $num_base, $x);
					         $j++;
					         $x++;
					         $this->cepd01_compromiso_partidas->execute("UPDATE cepd01_compromiso_partidas SET numero_control_compromiso=".$dnco." WHERE cod_dep=".$cod_dep." and ano_documento=".$ano." and numero_documento=".$numero_documento."");
					         $this->cepd03_ordenpago_partidas->execute("UPDATE cepd03_ordenpago_partidas SET numero_control_compromiso=".$dnco." WHERE cod_dep=".$cod_dep." and ano_orden_pago=".$ano_orden_pago." and numero_orden_pago=".$numero_orden_pago."");
					         //echo "<br>".$j."F:".$fecha_documento." NC:".$dnco;
					         //echo ".".$j;
}//fin foreach
$this->reactualizacion_rcompromisos_anulados();
}//function correr script compromisos


function reactualizacion_rcompromisos_anulados(){

  $partidas=$this->cepd01_compromiso_cuerpo->execute("select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_documento,
  a.numero_documento,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto,
  a.numero_control_compromiso,
  b.fecha_documento,
  b.fecha_proceso_registro,
  b.fecha_proceso_anulacion,
  b.numero_anulacion,
  b.concepto
   from
  cepd01_compromiso_partidas a,cepd01_compromiso_cuerpo b
where
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_documento = b.ano_documento and
  a.numero_documento = b.numero_documento and
  b.condicion_actividad=2");
$j=1;
  foreach($partidas as $partida){
  $cod_presi                           =         $partida[0]['cod_presi'];
  $cod_entidad                         =         $partida[0]['cod_entidad'];
  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
  $cod_inst                            =         $partida[0]['cod_inst'];
  $cod_dep                             =         $partida[0]['cod_dep'];
  $this->Session->write('SScodpresi', $cod_presi);
  $this->Session->write('SScodentidad', $cod_entidad);
  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
  $this->Session->write('SScodinst', $cod_inst);
  $this->Session->write('SScoddep', $cod_dep);
  $ano_documento                       =         $partida[0]['ano_documento'];
  $fr                      =         $partida[0]['fecha_proceso_registro'];
  $fecha_registro          =$fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
  $fd                                  =         $partida[0]['fecha_documento'];//0123-56-89
  $ano_fd=$fd[0].$fd[1].$fd[2].$fd[3];
  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
  $ano_fd= (int) $ano_fd;
  $ano_fdpartida=(int) $partida[0]['ano'];
  if($ano_fd!=$ano_fdpartida){
     $fecha_documento=$fecha_registro;
     $fd=$fecha_registro;
  }else{
  	$fecha_documento=$fd;
  }
  $numero_documento                    =         $partida[0]['numero_documento'];
  $ano                                 =         $partida[0]['ano'];
  $cod_sector                          =         $partida[0]['cod_sector'];
  $cod_programa                        =         $partida[0]['cod_programa'];
  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
  $cod_partida                         =         $partida[0]['cod_partida'];
  $cod_generica                        =         $partida[0]['cod_generica'];
  $cod_especifica                      =         $partida[0]['cod_especifica'];
  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
  $monto                               =         $partida[0]['monto'];
  $numero_control_compromiso           =         $partida[0]['numero_control_compromiso'];
  $numero_anulacion           =         $partida[0]['numero_anulacion'];
  $concepto = $partida[0]['concepto'];
$busca_concepto_anulacion=$this->cugd03_acta_anulacion_cuerpo->findAll("cod_dep='".$cod_dep."' and numero_acta_anulacion='".$numero_anulacion."' and (tipo_operacion='231' or tipo_operacion='5') and numero_documento='".$numero_documento."'");
$concepto_anulacion= $busca_concepto_anulacion[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"];
//echo "<br>".$concepto_anulacion;
                                   $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
								   $to = 2;
								   $td = 3;
								   $ta = 1;
								   $nda=$numero_control_compromiso;
								   $mt = $monto;
								   $ccp = str_replace("'","",$concepto_anulacion);
								   $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, $fecha_documento, $mt, $ccp,$ano,$numero_documento,$nda, null, null,null, null, null,null, null,null, null, null,null);
//echo "<br>".$j."F:".$fecha_documento." NC:".$dnco;
//echo ".".$j;
$j++;
}

}//function correr script compromisos


///////////////////////
function reactualizacion_ordenpago(){
//causa solo los registros de compromisos
//numero_documento_adjunto = 0
  $partidas=$this->cepd03_ordenpago_cuerpo->execute("select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_orden_pago,
  a.numero_orden_pago,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto,
  a.numero_control_compromiso,
  a.numero_control_causado,
  b.fecha_orden_pago,
  b.fecha_documento,
  b.numero_documento_origen,
  b.numero_documento_adjunto,
  b.fecha_proceso_registro,
  b.fecha_proceso_anulacion,
  b.condicion_actividad,
  b.concepto,b.numero_cheque
   from
  cepd03_ordenpago_partidas a,cepd03_ordenpago_cuerpo b
where
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_orden_pago = b.ano_orden_pago and
  a.numero_orden_pago = b.numero_orden_pago and b.numero_documento_adjunto='0'");
            $i=1;
			$j =0;
				$suma = count($partidas);
				$numero_causado= $this->cfpd22_numero_asiento_causado->field('cfpd22_numero_asiento_causado.numero_causado'," ano_causado='2008'", $order =null);
				if(!empty($numero_causado)){
					$num_base = $numero_causado;
					$numero_causado += $suma;
					$sql_numero_causado = "UPDATE cfpd22_numero_asiento_causado SET numero_causado='$numero_causado' WHERE ano_causado='$ann';COMMIT;";
				}else{
					$num_base = 1;
					$numero_causado = $num_base+$suma;
					$sql_numero_causado = "INSERT INTO cfpd22_numero_asiento_causado VALUES('1', '11', '30', '11', '2008', '$numero_causado')";
				}
				$sw_numero_causado = $this->cfpd22_numero_asiento_causado->query($sql_numero_causado);

  foreach($partidas as $partida){
  $cod_presi                           =         $partida[0]['cod_presi'];
  $cod_entidad                         =         $partida[0]['cod_entidad'];
  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
  $cod_inst                            =         $partida[0]['cod_inst'];
  $cod_dep                             =         $partida[0]['cod_dep'];
  $this->Session->write('SScodpresi', $cod_presi);
  $this->Session->write('SScodentidad', $cod_entidad);
  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
  $this->Session->write('SScodinst', $cod_inst);
  $this->Session->write('SScoddep', $cod_dep);
  $ano_orden_pago                      =         $partida[0]['ano_orden_pago'];
  $foo                                 =         $partida[0]['fecha_orden_pago'];
  $fd                                  =         $partida[0]['fecha_orden_pago'];
  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
  $fdoo=$foo[8].$foo[9]."/".$foo[5].$foo[6]."/".$foo[0].$foo[1].$foo[2].$foo[3];
  $fecha_orden_pago=$foo;
  $numero_orden_pago                   =         $partida[0]['numero_orden_pago'];
  $numero_documento_origen             =         $partida[0]['numero_documento_origen'];
  $numero_documento_adjunto            =         $partida[0]['numero_documento_adjunto'];
  $ano                                 =         $partida[0]['ano'];
  $cod_sector                          =         $partida[0]['cod_sector'];
  $cod_programa                        =         $partida[0]['cod_programa'];
  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
  $cod_partida                         =         $partida[0]['cod_partida'];
  $cod_generica                        =         $partida[0]['cod_generica'];
  $cod_especifica                      =         $partida[0]['cod_especifica'];
  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
  $monto                               =         $partida[0]['monto'];
  $numero_control_compromiso           =         $partida[0]['numero_control_compromiso'];
  $concepto = $partida[0]['concepto'];
  $numero_cheque = $partida[0]['numero_cheque'];


                $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
				$to = 1;
				$td = 4;
				$ta = 1;
				$mt = $monto;
				$ndo = $numero_documento_origen;
                $ccp=str_replace("'","",$concepto);

				$rnco = $numero_control_compromiso;
				$dnca = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp, $ano, $ndo, null, $numero_orden_pago, $fdoo, null, null, null, null, $rnco, null, $num_base, $i);
				$ncc=$dnca;
				$this->cepd03_ordenpago_partidas->execute("UPDATE cepd03_ordenpago_partidas SET numero_control_causado=".$ncc." WHERE cod_dep=".$cod_dep." and ano_orden_pago=".$ano." and numero_orden_pago=".$numero_orden_pago."");
				$this->cstd03_cheque_partidas->execute("UPDATE cstd03_cheque_partidas SET numero_control_compromiso=".$numero_control_compromiso.",numero_control_causado=".$ncc." WHERE ano_orden_pago=".$ano." and numero_orden_pago=".$numero_orden_pago." and numero_cheque=".$numero_cheque." and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
                //echo "<br>".$j."F:".$fd." NCC:".$ncc;
                $i++;
		 $j++;
 }//fin foreach
 $this->reactualizacion_ordenpago_anulado();
}//function correr script compromisos


function reactualizacion_ordenpago_anulado(){
//causa solo los registros de compromisos
//numero_documento_adjunto = 0
  $partidas=$this->cepd03_ordenpago_cuerpo->execute("select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_orden_pago,
  a.numero_orden_pago,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto,
  a.numero_control_compromiso,
  a.numero_control_causado,b.numero_documento_origen,
  b.numero_documento_adjunto,
  b.fecha_orden_pago,b.fecha_documento,
  b.fecha_proceso_registro,
  b.fecha_proceso_anulacion,
  b.condicion_actividad,
  b.concepto,numero_anulacion
   from
  cepd03_ordenpago_partidas a,cepd03_ordenpago_cuerpo b
where
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_orden_pago = b.ano_orden_pago and
  a.numero_orden_pago = b.numero_orden_pago and
  b.numero_documento_adjunto='0' and b.cod_tipo_documento=1 and
  b.condicion_actividad=2");
$j=1;
  foreach($partidas as $partida){
  $cod_presi                           =         $partida[0]['cod_presi'];
  $cod_entidad                         =         $partida[0]['cod_entidad'];
  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
  $cod_inst                            =         $partida[0]['cod_inst'];
  $cod_dep                             =         $partida[0]['cod_dep'];
  $this->Session->write('SScodpresi', $cod_presi);
  $this->Session->write('SScodentidad', $cod_entidad);
  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
  $this->Session->write('SScodinst', $cod_inst);
  $this->Session->write('SScoddep', $cod_dep);
  $ano_orden_pago                      =         $partida[0]['ano_orden_pago'];
  $foo                                 =         $partida[0]['fecha_orden_pago'];
  $fd                                  =         $partida[0]['fecha_orden_pago'];
  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
  $fdoo=$foo[8].$foo[9]."/".$foo[5].$foo[6]."/".$foo[0].$foo[1].$foo[2].$foo[3];
  $fecha_orden_pago=$fdoo;
  $numero_orden_pago                   =         $partida[0]['numero_orden_pago'];
  $numero_documento_origen             =         $partida[0]['numero_documento_origen'];
  $numero_documento_adjunto            =         $partida[0]['numero_documento_adjunto'];
  $ano                                 =         $partida[0]['ano'];
  $cod_sector                          =         $partida[0]['cod_sector'];
  $cod_programa                        =         $partida[0]['cod_programa'];
  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
  $cod_partida                         =         $partida[0]['cod_partida'];
  $cod_generica                        =         $partida[0]['cod_generica'];
  $cod_especifica                      =         $partida[0]['cod_especifica'];
  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
  $monto                               =         $partida[0]['monto'];
  $numero_control_compromiso           =         $partida[0]['numero_control_compromiso'];
  $numero_control_causado              =         $partida[0]['numero_control_causado'];
  $numero_anulacion                    =         $partida[0]['numero_anulacion'];
  $concepto = $partida[0]['concepto'];

   $busca_concepto_anulacion=$this->cugd03_acta_anulacion_cuerpo->findAll("cod_dep='".$cod_dep."' and numero_acta_anulacion='".$numero_anulacion."'");
   $concepto_anulacion= $busca_concepto_anulacion[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"];
   //print_r($busca_concepto_anulacion);
   //echo "<br>".$concepto_anulacion;
   $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
   $to = 2;
   $td = 4;
   $ta = 1;
   $rnco=$numero_control_compromiso;
   $rnca=$numero_control_causado;
   $mt = $monto;
   $ccp = str_replace("'","",$concepto_anulacion);
   $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, $fecha_orden_pago, $mt, $ccp,$ano,$numero_orden_pago,null, null, null,null, null, null,null,$rnco, $rnca, null,null,null);
   //echo "<br>".$j."F:".$fd." NC:".$dnco;
$j++;
 }//fin foreach


}//function correr script ordenpago




function reactualizacion_pagado(){

  $partidas=$this->cstd03_cheque_cuerpo->execute("select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_movimiento,
  a.cod_entidad_bancaria,
  a.cod_sucursal,
  a.cuenta_bancaria,
  a.numero_cheque,
  a.clase_orden,
  a.ano_orden_pago,
  a.numero_orden_pago,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto,
  a.numero_control_compromiso,
  a.numero_control_causado,
  a.numero_control_pagado,
  b.fecha_proceso_registro,
  b.fecha_proceso_anulacion,
  b.condicion_actividad,
  b.concepto,b.fecha_cheque " .
		" from
  cstd03_cheque_partidas a,cstd03_cheque_cuerpo b
where
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_movimiento = b.ano_movimiento and
  a.cod_entidad_bancaria = b.cod_entidad_bancaria and
  a.cod_sucursal = b.cod_sucursal and
  a.cuenta_bancaria = b.cuenta_bancaria and
  a.numero_cheque = b.numero_cheque");
  //print_r($partidas);

  	$suma = count($partidas);
	$numero_pagado= $this->cfpd23_numero_asiento_pagado->field('cfpd23_numero_asiento_pagado.numero_pagado', "ano_pagado='2008'", $order =null);
	if(!empty($numero_pagado)){
		$num_base = $numero_pagado;
		$numero_pagado += $suma ;
		$sql_numero_pagado = "UPDATE cfpd23_numero_asiento_pagado SET numero_pagado='$numero_pagado' WHERE ano_pagado='2008';COMMIT;";
	}else{
		$num_base = 1;
		$numero_pagado = $num_base;
		$sql_numero_pagado = "INSERT INTO cfpd23_numero_asiento_pagado VALUES('1', '11', '30', '11', '2008', '$numero_pagado')";
	}
	$sw_numero_pagado = $this->cfpd23_numero_asiento_pagado->query($sql_numero_pagado);


$x=0;
  foreach($partidas as $partida){
        //echo "<br>"." ".$partida[0]["fecha_proceso_registro"]." ".$partida[0]["monto"];
  $cod_presi                           =         $partida[0]['cod_presi'];
  $cod_entidad                         =         $partida[0]['cod_entidad'];
  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
  $cod_inst                            =         $partida[0]['cod_inst'];
  $cod_dep                             =         $partida[0]['cod_dep'];
  $this->Session->write('SScodpresi', $cod_presi);
  $this->Session->write('SScodentidad', $cod_entidad);
  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
  $this->Session->write('SScodinst', $cod_inst);
  $this->Session->write('SScoddep', $cod_dep);
  $ano_movimiento                      =         $partida[0]['ano_movimiento'];
  $foo                    =         $partida[0]['fecha_cheque'];
  $fd                                  =         $partida[0]['fecha_cheque'];
  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
  $fdoo=$foo[8].$foo[9]."/".$foo[5].$foo[6]."/".$foo[0].$foo[1].$foo[2].$foo[3];
  $fecha_orden_pago=$fdoo;
  $cod_entidad_bancaria                =         $partida[0]['cod_entidad_bancaria'];
  $cod_sucursal                        =         $partida[0]['cod_sucursal'];
  $cuenta_bancaria                     =         $partida[0]['cuenta_bancaria'];
  $numero_cheque                       =         $partida[0]['numero_cheque'];
  $clase_orden                         =         $partida[0]['clase_orden'];
  $ano_orden_pago                      =         $partida[0]['ano_orden_pago'];
  $ano                                 =         $partida[0]['ano'];
  $cod_sector                          =         $partida[0]['cod_sector'];
  $cod_programa                        =         $partida[0]['cod_programa'];
  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
  $cod_partida                         =         $partida[0]['cod_partida'];
  $cod_generica                        =         $partida[0]['cod_generica'];
  $cod_especifica                      =         $partida[0]['cod_especifica'];
  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
  $monto                               =         $partida[0]['monto'];
  $numero_control_compromiso           =         $partida[0]['numero_control_compromiso'];
  $numero_control_causado              =         $partida[0]['numero_control_causado'];
  $numero_control_pagado               =         "0";
  $numero_orden_pago              =         $partida[0]['numero_orden_pago'];
  $concepto = $partida[0]['concepto'];

 $sql_verificar ="  and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra;
 $sql_verificar .=" and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."";


	$cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
	$to = 1;
	$td = 5;
	$ta = 1;
	$mt = $monto;
	$ccp = str_replace("'","",$concepto);
	$dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp,$ano,$ndo=null,$nda=null, $opago=$numero_orden_pago, $opfecha=$fecha_orden_pago, $cbanco=$cod_entidad_bancaria, $ccuenta=$cuenta_bancaria, $ccheque=$numero_cheque, $fechache=$fd, $numero_control_compromiso, $numero_control_causado, $num_base, $x, null);

//  cstd03_cheque_partidas

 $sql_cstd03_cheque_partidas = "UPDATE cstd03_cheque_partidas SET numero_control_pagado='".$dnco."' ";
 $sql_cstd03_cheque_partidas.= "WHERE  ano_orden_pago='".$ano_orden_pago."' AND numero_orden_pago='".$numero_orden_pago."' AND numero_control_compromiso='".$numero_control_compromiso."' AND numero_control_causado='".$numero_control_causado."' and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ";
 $x++;
  if($this->cstd03_cheque_partidas->execute($sql_cstd03_cheque_partidas)>=1){echo" SI.".$x;}else{$opcion = 'no';}//fin else
  }

  $this->reactualizacion_pagado_anulado();
}//function correr script pagado


function reactualizacion_pagado_anulado(){

  $partidas=$this->cstd03_cheque_cuerpo->execute("select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_movimiento,
  a.cod_entidad_bancaria,
  a.cod_sucursal,
  a.cuenta_bancaria,
  a.numero_cheque,
  a.clase_orden,
  a.ano_orden_pago,
  a.numero_orden_pago,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto,
  a.numero_control_compromiso,
  a.numero_control_causado,
  a.numero_control_pagado,
  b.fecha_proceso_registro,
  b.fecha_proceso_anulacion,
  b.condicion_actividad,
  b.numero_anulacion,
  b.concepto,b.fecha_cheque,a.numero_control_pagado
from
  cstd03_cheque_partidas a,cstd03_cheque_cuerpo b
where
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_movimiento = b.ano_movimiento and
  a.cod_entidad_bancaria = b.cod_entidad_bancaria and
  a.cod_sucursal = b.cod_sucursal and
  a.cuenta_bancaria = b.cuenta_bancaria and
  a.numero_cheque = b.numero_cheque and
  b.condicion_actividad=2");
  //print_r($partidas);

$x=0;
  foreach($partidas as $partida){
        //echo "<br>"." ".$partida[0]["fecha_proceso_registro"]." ".$partida[0]["monto"];
  $cod_presi                           =         $partida[0]['cod_presi'];
  $cod_entidad                         =         $partida[0]['cod_entidad'];
  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
  $cod_inst                            =         $partida[0]['cod_inst'];
  $cod_dep                             =         $partida[0]['cod_dep'];
  $this->Session->write('SScodpresi', $cod_presi);
  $this->Session->write('SScodentidad', $cod_entidad);
  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
  $this->Session->write('SScodinst', $cod_inst);
  $this->Session->write('SScoddep', $cod_dep);
  $ano_movimiento                      =         $partida[0]['ano_movimiento'];
  $foo                    =         $partida[0]['fecha_cheque'];
  $fd                                  =         $partida[0]['fecha_cheque'];
  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
  $fdoo=$foo[8].$foo[9]."/".$foo[5].$foo[6]."/".$foo[0].$foo[1].$foo[2].$foo[3];
  $fecha_orden_pago=$fdoo;
  $cod_entidad_bancaria                =         $partida[0]['cod_entidad_bancaria'];
  $cod_sucursal                        =         $partida[0]['cod_sucursal'];
  $cuenta_bancaria                     =         $partida[0]['cuenta_bancaria'];
  $numero_cheque                       =         $partida[0]['numero_cheque'];
  $clase_orden                         =         $partida[0]['clase_orden'];
  $ano_orden_pago                      =         $partida[0]['ano_orden_pago'];
  $ano                                 =         $partida[0]['ano'];
  $cod_sector                          =         $partida[0]['cod_sector'];
  $cod_programa                        =         $partida[0]['cod_programa'];
  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
  $cod_partida                         =         $partida[0]['cod_partida'];
  $cod_generica                        =         $partida[0]['cod_generica'];
  $cod_especifica                      =         $partida[0]['cod_especifica'];
  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
  $monto                               =         $partida[0]['monto'];
  $numero_control_compromiso           =         $partida[0]['numero_control_compromiso'];
  $numero_control_causado              =         $partida[0]['numero_control_causado'];
  $numero_control_pagado               =         $partida[0]['numero_control_pagado'];
  $numero_orden_pago              =         $partida[0]['numero_orden_pago'];
  $numero_anulacion              =         $partida[0]['numero_anulacion'];
  $busca_concepto_anulacion=$this->cugd03_acta_anulacion_cuerpo->findAll("cod_dep='".$cod_dep."' and numero_acta_anulacion='".$numero_anulacion."' and tipo_operacion='251' and numero_documento='".$numero_cheque."'");
  $concepto_anulacion= $busca_concepto_anulacion[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"];
  //echo "<br>".$concepto_anulacion;

 $sql_verificar ="  and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra;
 $sql_verificar .=" and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."";

 $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
 $num_asiento_compromiso = $this->motor_presupuestario($cp, 2, 5, 1, $fd, $monto, str_replace("'","",$concepto_anulacion), $ano, $numero_orden_compra=null, $numero_orden_compra_autorizacion_pagos=null, $numero_orden_pago, $opfecha=$fecha_orden_pago, $cod_entidad_bancaria, $cuenta_bancaria, $numero_cheque, $fechache=$fd,$numero_control_compromiso, $numero_control_causado, $numero_control_pagado, null,null);
 //echo "<br>".$num_asiento_compromiso;
 $x++;
 //echo"<br>".$x;

  }//fin foreach
}//function correr script pagado anulado

################






function reactualizacion_orden_compra_parte1(){//activos

$partidas=$this->cscd04_ordencompra_encabezado->execute("SELECT
a.cod_presi,
a.cod_entidad,
a.cod_tipo_inst,
a.cod_inst,
a.cod_dep,
a.ano_orden_compra,
a.numero_orden_compra,
a.ano,
a.cod_sector,
a.cod_programa,
a.cod_sub_prog,
a.cod_proyecto,
a.cod_activ_obra,
a.cod_partida,
a.cod_generica,
a.cod_especifica,
a.cod_sub_espec,
a.cod_auxiliar,
a.monto,
a.aumento,
a.disminucion,
a.anticipo,
a.amortizacion,
a.cancelado,
a.numero_asiento_compromiso,
b.fecha_orden_compra,
b.ano_orden_compra,
b.numero_orden_compra,
b.fecha_proceso_registro,
b.fecha_proceso_anulacion,
b.condicion_actividad,
b.ano_cotizacion,
b.numero_cotizacion,
upper(b.rif),
(SELECT x.uso_destino from cscd02_solicitud_encabezado x WHERE x.cod_dep=a.cod_dep AND x.numero_solicitud=(SELECT y.numero_solicitud FROM cscd03_cotizacion_encabezado y WHERE y.cod_dep=a.cod_dep AND y.ano_cotizacion=2008 AND y.numero_cotizacion=b.numero_cotizacion AND upper(y.rif)=upper(b.rif) AND y.numero_ordencompra=b.numero_orden_compra) limit 1)::text as concepto

FROM cscd04_ordencompra_partidas a,cscd04_ordencompra_encabezado b
 WHERE
  b.condicion_actividad=1 and
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_orden_compra = b.ano_orden_compra and
  a.numero_orden_compra = b.numero_orden_compra");

            $j =0;
            $ano=2008;
			$suma = count($partidas);
			$numero_compromiso= $this->cfpd21_numero_asiento_compromiso->field('cfpd21_numero_asiento_compromiso.numero_compromiso', " ano_compromiso='$ano'", $order =null);
			if(!empty($numero_compromiso)){
				$num_base = $numero_compromiso;
				$numero_compromiso += $suma;
				$sql_numero_compromiso = "UPDATE cfpd21_numero_asiento_compromiso SET numero_compromiso='$numero_compromiso' WHERE ano_compromiso='$ano';COMMIT;";
			}else{
				$num_base = 1;
				$numero_compromiso = $num_base + $suma;
			    $sql_numero_compromiso = "INSERT INTO cfpd21_numero_asiento_compromiso VALUES('1', '11', '30', '11', '$ano', '$numero_compromiso')";
			}
			$sw_numero_compromiso = $this->cfpd21_numero_asiento_compromiso->query($sql_numero_compromiso);
			$i=0;
			foreach($partidas as $partida){
				  $cod_presi                           =         $partida[0]['cod_presi'];
				  $cod_entidad                         =         $partida[0]['cod_entidad'];
				  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
				  $cod_inst                            =         $partida[0]['cod_inst'];
				  $cod_dep                             =         $partida[0]['cod_dep'];
				  $this->Session->write('SScodpresi', $cod_presi);
				  $this->Session->write('SScodentidad', $cod_entidad);
				  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
				  $this->Session->write('SScodinst', $cod_inst);
				  $this->Session->write('SScoddep', $cod_dep);
				  $ano_documento                       =         $partida[0]['ano_orden_compra'];
				  $fr                      =         $partida[0]['fecha_proceso_registro'];
				  $fecha_registro=$fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
				  $fd                                  =         $partida[0]['fecha_orden_compra'];
				  $ano_fd=$fd[0].$fd[1].$fd[2].$fd[3];
				  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
				  $ano_fd= (int) $ano_fd;
				  $ano_fdpartida=(int) $partida[0]['ano'];
				  if($ano_fd!=$ano_fdpartida){
				     $fecha_documento=$fecha_registro;
				     $fd=$fecha_registro;
				  }else{
				  	$fecha_documento=$fd;
				  }
				  $numero_documento                    =         $partida[0]['numero_orden_compra'];
				  $ano                                 =         $partida[0]['ano'];
				  $cod_sector                          =         $partida[0]['cod_sector'];
				  $cod_programa                        =         $partida[0]['cod_programa'];
				  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
				  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
				  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
				  $cod_partida                         =         $partida[0]['cod_partida'];
				  $cod_generica                        =         $partida[0]['cod_generica'];
				  $cod_especifica                      =         $partida[0]['cod_especifica'];
				  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
				  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
				  $monto                               =         $partida[0]['monto'];
				  $numero_control_compromiso           =         $partida[0]['numero_asiento_compromiso'];
				  $concepto = $partida[0]['concepto'];
				  //echo $ano.'-'.$cod_sector.'-'.$cod_programa.'-'.$cod_sub_prog.'-'.$cod_proyecto.'-'.$cod_activ_obra.'-'.$cod_partida.'-'.$cod_generica.'-'.$cod_especifica.'-'.$cod_sub_espec.'-'.$cod_auxiliar.'-'.$monto;
					$cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
					$to = 1;
					$td = 3;
					$ta = 2;
					$mt = $monto;
					$ccp = str_replace("'","",$concepto);
					$dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp, $ano, $numero_documento, null, null, null, null, null, null, null, null, null, $num_base, $j);
					$j++;
					$this->cscd04_ordencompra_partidas->execute("UPDATE cscd04_ordencompra_partidas SET numero_asiento_compromiso=".$dnco." WHERE cod_dep=".$cod_dep." and ano_orden_compra=".$ano." and numero_orden_compra=".$numero_documento."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
                    $sql4  = "UPDATE cscd04_ordencompra_autorizacion_pago_partidas SET numero_control_compromiso = '".$dnco."'  where numero_orden_compra=".$numero_documento ." and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ";
		            $this->cscd04_ordencompra_a_pago_partidas->execute($sql4);
					//echo "<br>".$j."F:".$fd." NC:".$dnco;


			}//fin foreach

    $this->reactualizacion_orden_compra_parte2();
}//fin reactualizacion_orden_compra // activos

function reactualizacion_orden_compra_parte2(){//activos

$partidas=$this->cscd04_ordencompra_encabezado->execute("SELECT
a.cod_presi,
a.cod_entidad,
a.cod_tipo_inst,
a.cod_inst,
a.cod_dep,
a.ano_orden_compra,
a.numero_orden_compra,
a.ano,
a.cod_sector,
a.cod_programa,
a.cod_sub_prog,
a.cod_proyecto,
a.cod_activ_obra,
a.cod_partida,
a.cod_generica,
a.cod_especifica,
a.cod_sub_espec,
a.cod_auxiliar,
a.monto,
a.aumento,
a.disminucion,
a.anticipo,
a.amortizacion,
a.cancelado,
a.numero_asiento_compromiso,
b.fecha_orden_compra,
b.ano_orden_compra,
b.numero_orden_compra,
b.fecha_proceso_registro,
b.fecha_proceso_anulacion,
b.condicion_actividad,
b.ano_cotizacion,
b.numero_cotizacion,
upper(b.rif),
(SELECT x.uso_destino from cscd02_solicitud_encabezado_anulado x WHERE x.cod_dep=a.cod_dep AND x.numero_solicitud=(SELECT y.numero_solicitud FROM cscd03_cotizacion_encabezado_anulado y WHERE y.cod_dep=a.cod_dep AND y.ano_cotizacion=2008 AND y.numero_cotizacion=b.numero_cotizacion AND upper(y.rif)=upper(b.rif) AND y.numero_ordencompra=b.numero_orden_compra) limit 1)::text as concepto

FROM cscd04_ordencompra_partidas a,cscd04_ordencompra_encabezado b
 where
  b.condicion_actividad=2 and
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_orden_compra = b.ano_orden_compra and
  a.numero_orden_compra = b.numero_orden_compra");

            $j =0;
            $ano=2008;
			$suma = count($partidas);
			$numero_compromiso= $this->cfpd21_numero_asiento_compromiso->field('cfpd21_numero_asiento_compromiso.numero_compromiso'," ano_compromiso='$ano'", $order =null);
			if(!empty($numero_compromiso)){
				$num_base = $numero_compromiso;
				$numero_compromiso += $suma;
				$sql_numero_compromiso = "UPDATE cfpd21_numero_asiento_compromiso SET numero_compromiso='$numero_compromiso' WHERE ano_compromiso='$ano';COMMIT;";
			}else{
				$num_base = 1;
				$numero_compromiso = $num_base + $suma;
			    $sql_numero_compromiso = "INSERT INTO cfpd21_numero_asiento_compromiso VALUES('1', '11', '30', '11', '$ano', '$numero_compromiso')";
			}
			$sw_numero_compromiso = $this->cfpd21_numero_asiento_compromiso->query($sql_numero_compromiso);
			$i=0;
			foreach($partidas as $partida){
				  $cod_presi                           =         $partida[0]['cod_presi'];
				  $cod_entidad                         =         $partida[0]['cod_entidad'];
				  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
				  $cod_inst                            =         $partida[0]['cod_inst'];
				  $cod_dep                             =         $partida[0]['cod_dep'];
				  $this->Session->write('SScodpresi', $cod_presi);
				  $this->Session->write('SScodentidad', $cod_entidad);
				  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
				  $this->Session->write('SScodinst', $cod_inst);
				  $this->Session->write('SScoddep', $cod_dep);
				  $ano_documento                       =         $partida[0]['ano_orden_compra'];
				  $fr                      =         $partida[0]['fecha_proceso_registro'];
				  $fecha_registro=$fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
				  $fd                                  =         $partida[0]['fecha_orden_compra'];
				  $ano_fd=$fd[0].$fd[1].$fd[2].$fd[3];
				  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
				  $ano_fd= (int) $ano_fd;
				  $ano_fdpartida=(int) $partida[0]['ano'];
				  if($ano_fd!=$ano_fdpartida){
				     $fecha_documento=$fecha_registro;
				     $fd=$fecha_registro;
				  }else{
				  	$fecha_documento=$fd;
				  }
				  $numero_documento                    =         $partida[0]['numero_orden_compra'];
				  $ano                                 =         $partida[0]['ano'];
				  $cod_sector                          =         $partida[0]['cod_sector'];
				  $cod_programa                        =         $partida[0]['cod_programa'];
				  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
				  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
				  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
				  $cod_partida                         =         $partida[0]['cod_partida'];
				  $cod_generica                        =         $partida[0]['cod_generica'];
				  $cod_especifica                      =         $partida[0]['cod_especifica'];
				  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
				  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
				  $monto                               =         $partida[0]['monto'];
				  $numero_control_compromiso           =         $partida[0]['numero_asiento_compromiso'];
				  $concepto = $partida[0]['concepto'];
				  //echo $ano.'-'.$cod_sector.'-'.$cod_programa.'-'.$cod_sub_prog.'-'.$cod_proyecto.'-'.$cod_activ_obra.'-'.$cod_partida.'-'.$cod_generica.'-'.$cod_especifica.'-'.$cod_sub_espec.'-'.$cod_auxiliar.'-'.$monto;

					$cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
					$to = 1;
					$td = 3;
					$ta = 2;
					$mt = $monto;
					$ccp = str_replace("'","",$concepto);
					$dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp, $ano, $numero_documento, null, null, null, null, null, null, null, null, null, $num_base, $j);
					$j++;
					$this->cscd04_ordencompra_partidas->execute("UPDATE cscd04_ordencompra_partidas SET numero_asiento_compromiso=".$dnco." WHERE cod_dep=".$cod_dep." and ano_orden_compra=".$ano." and numero_orden_compra=".$numero_documento." and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
					$sql4  = "UPDATE cscd04_ordencompra_autorizacion_pago_partidas SET numero_control_compromiso = '".$dnco."'  where numero_orden_compra=".$numero_documento ." and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ";
		            $this->cscd04_ordencompra_a_pago_partidas->execute($sql4);
					//echo "<br>".$j."F:".$fd." NC:".$dnco;
			}//fin foreach
$this->reactualizacion_orden_compra_anulados();
}//fin reactualizacion_orden_compra_parte2 // activos

///anulados
function reactualizacion_orden_compra_anulados(){//anulados

$partidas=$this->cscd04_ordencompra_encabezado->execute("SELECT
a.cod_presi,
a.cod_entidad,
a.cod_tipo_inst,
a.cod_inst,
a.cod_dep,
a.ano_orden_compra,
a.numero_orden_compra,
a.ano,
a.cod_sector,
a.cod_programa,
a.cod_sub_prog,
a.cod_proyecto,
a.cod_activ_obra,
a.cod_partida,
a.cod_generica,
a.cod_especifica,
a.cod_sub_espec,
a.cod_auxiliar,
a.monto,
a.aumento,
a.disminucion,
a.anticipo,
a.amortizacion,
a.cancelado,
a.numero_asiento_compromiso,
b.fecha_orden_compra,
b.fecha_proceso_registro,
b.fecha_proceso_anulacion,
b.condicion_actividad,
b.ano_cotizacion,
b.numero_cotizacion,b.numero_asiento_anulacion,
upper(b.rif) as rif,
(SELECT x.uso_destino from cscd02_solicitud_encabezado_anulado x WHERE x.cod_dep=a.cod_dep AND x.numero_solicitud=(SELECT y.numero_solicitud FROM cscd03_cotizacion_encabezado_anulado y WHERE y.cod_dep=a.cod_dep AND y.ano_cotizacion=2008 AND y.numero_cotizacion=b.numero_cotizacion AND upper(y.rif)=upper(b.rif) AND y.numero_ordencompra=b.numero_orden_compra) limit 1)::text as concepto

FROM cscd04_ordencompra_partidas a,cscd04_ordencompra_encabezado b
 where
  b.condicion_actividad=2 and
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_orden_compra = b.ano_orden_compra and
  a.numero_orden_compra = b.numero_orden_compra");

            $j =0;
            $ano=2008;
			$i=0;
			foreach($partidas as $partida){
				  $cod_presi                           =         $partida[0]['cod_presi'];
				  $cod_entidad                         =         $partida[0]['cod_entidad'];
				  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
				  $cod_inst                            =         $partida[0]['cod_inst'];
				  $cod_dep                             =         $partida[0]['cod_dep'];
				  $this->Session->write('SScodpresi', $cod_presi);
				  $this->Session->write('SScodentidad', $cod_entidad);
				  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
				  $this->Session->write('SScodinst', $cod_inst);
				  $this->Session->write('SScoddep', $cod_dep);
				  $ano_documento                       =         $partida[0]['ano_orden_compra'];
				  $fr                      =         $partida[0]['fecha_proceso_registro'];
				  $fecha_registro=$fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
				  $fd                                  =         $partida[0]['fecha_orden_compra'];
				  $ano_fd=$fd[0].$fd[1].$fd[2].$fd[3];
				  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
				  $ano_fd= (int) $ano_fd;
				  $ano_fdpartida=(int) $partida[0]['ano'];
				  if($ano_fd!=$ano_fdpartida){
				     $fecha_documento=$fecha_registro;
				     $fd=$fecha_registro;
				  }else{
				  	$fecha_documento=$fd;
				  }
				  $numero_documento                    =         $partida[0]['numero_orden_compra'];
				  $ano                                 =         $partida[0]['ano'];
				  $cod_sector                          =         $partida[0]['cod_sector'];
				  $cod_programa                        =         $partida[0]['cod_programa'];
				  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
				  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
				  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
				  $cod_partida                         =         $partida[0]['cod_partida'];
				  $cod_generica                        =         $partida[0]['cod_generica'];
				  $cod_especifica                      =         $partida[0]['cod_especifica'];
				  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
				  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
				  $monto                               =         $partida[0]['monto'];
				  $numero_control_compromiso           =         $partida[0]['numero_asiento_compromiso'];
				  $concepto = $partida[0]['concepto'];
				  $numero_anulacion = $partida[0]['numero_asiento_anulacion'];
				   $busca_concepto_anulacion=$this->cugd03_acta_anulacion_cuerpo->findAll("cod_dep='".$cod_dep."'  and tipo_operacion='232' and numero_documento='".$numero_documento."'");
                   $concepto_anulacion= $busca_concepto_anulacion[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"];
				   //echo "<br>".$cod_dep." ".$concepto_anulacion;
                   $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
				   $to = 2;
				   $td = 3;
				   $ta = 2;
				   $nda=$numero_control_compromiso;
				   $mt = $monto;
				   $ccp = str_replace("'","",$concepto_anulacion);
				   $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, $fecha_documento, $mt, $ccp,$ano,$numero_documento,$nda, null, null,null, null, null,null, null,null, null, null,null);

				   //echo "<br>".$j."F:".$fd." NC:".$dnco;
$j++;
			}//fin foreach

}//fin reactualizacion_orden_compra_anulados


function reactualizacion_causado_compra_anticipo () {//anticipos
    $partidas=$this->cscd04_ordencompra_anticipo_cuerpo->execute("SELECT
a.cod_presi,
a.cod_entidad,
a.cod_tipo_inst,
a.cod_inst,
a.cod_dep,
a.ano_orden_compra,
a.numero_orden_compra,
a.numero_anticipo,
a.ano,
a.cod_sector,
a.cod_programa,
a.cod_sub_prog,
a.cod_proyecto,
a.cod_activ_obra,
a.cod_partida,
a.cod_generica,
a.cod_especifica,
a.cod_sub_espec,
a.cod_auxiliar,
a.monto,
a.numero_control_compromiso,
a.numero_control_causado,
b.fecha_anticipo,
b.numero_anticipo,
b.ano_orden_compra,
b.numero_orden_compra,
b.fecha_proceso_registro,
b.fecha_proceso_anulacion,
b.condicion_actividad,
b.observaciones as  concepto

FROM cscd04_ordencompra_anticipo_partidas a,cscd04_ordencompra_anticipo_cuerpo b
 where
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_orden_compra = b.ano_orden_compra and
  a.numero_orden_compra = b.numero_orden_compra and
  a.numero_anticipo=b.numero_anticipo

");



	$j =0;
	$i =0;
	$ano=2008;
	$suma = count($partidas);
	$numero_causado= $this->cfpd22_numero_asiento_causado->field('cfpd22_numero_asiento_causado.numero_causado', " ano_causado='$ano'", $order =null);
	if(!empty($numero_causado)){
		$num_base = $numero_causado;
		$numero_causado += $suma;
		$sql_numero_causado = "UPDATE cfpd22_numero_asiento_causado SET numero_causado='$numero_causado' WHERE ano_causado='$ano' ;COMMIT;";
	}else{
		$num_base = 1;
		$numero_causado = $num_base+$suma;
		$sql_numero_causado = "INSERT INTO cfpd22_numero_asiento_causado VALUES('1', '11', '30', '11', '$ano', '$numero_causado')";
	}
	$sw_numero_causado = $this->cfpd22_numero_asiento_causado->query($sql_numero_causado);
//print_r($partidas);
	foreach($partidas as $partida){
		  $cod_presi                           =         $partida[0]['cod_presi'];
		  $cod_entidad                         =         $partida[0]['cod_entidad'];
		  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
		  $cod_inst                            =         $partida[0]['cod_inst'];
		  $cod_dep                             =         $partida[0]['cod_dep'];
		  $this->Session->write('SScodpresi', $cod_presi);
		  $this->Session->write('SScodentidad', $cod_entidad);
		  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
		  $this->Session->write('SScodinst', $cod_inst);
		  $this->Session->write('SScoddep', $cod_dep);
		  $ano_movimiento                      =         $partida[0]['ano_orden_compra'];
		  //$fecha_orden_pago                    =         $partida[0]['fecha_anticipo'];
		  //$fd                                  =         $partida[0]['fecha_proceso_registro'];
		  //$fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
		  $ano_orden_compra                    =         $partida[0]['ano_orden_compra'];
				  $fr                      =         $partida[0]['fecha_proceso_registro'];
				  $fecha_registro=$fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
				  $fd                                  =         $partida[0]['fecha_anticipo'];
				  $ano_fd=$fd[0].$fd[1].$fd[2].$fd[3];
				  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
				  $ano_fd= (int) $ano_fd;
				  $ano_fdpartida=(int) $partida[0]['ano'];
				  if($ano_fd!=$ano_fdpartida){
				     $fecha_documento=$fecha_registro;
				     $fd=$fecha_registro;
				  }else{
				  	$fecha_documento=$fd;
				  }



		  $ndo                                 =         $partida[0]['numero_orden_compra'];
		  $nda                                 =         $partida[0]['numero_anticipo'];
		  $ano                                 =         $partida[0]['ano'];
		  $cod_sector                          =         $partida[0]['cod_sector'];
		  $cod_programa                        =         $partida[0]['cod_programa'];
		  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
		  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
		  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
		  $cod_partida                         =         $partida[0]['cod_partida'];
		  $cod_generica                        =         $partida[0]['cod_generica'];
		  $cod_especifica                      =         $partida[0]['cod_especifica'];
		  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
		  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
		  $monto                               =         $partida[0]['monto'];
		  $numero_control_compromiso           =         $partida[0]['numero_control_compromiso'];
		  $numero_control_causado              =         $partida[0]['numero_control_causado'];
		  $numero_control_pagado               =         "0";
		  $concepto = $partida[0]['concepto'];


		$cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
		$to = 1;
		$td = 4;
		$ta = 2;
		$mt = $monto;
	    $ccp = str_replace("'","",$concepto);
		$rnco = $numero_control_compromiso;
		$dnca = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp, $ano, $ndo, $nda, null, null, null, null, null, null, $rnco, null, $num_base, $i);
		$sql4  = "UPDATE cscd04_ordencompra_anticipo_partidas SET numero_control_causado = '".$numero_control_causado."'  where numero_orden_compra=".$ndo." and numero_anticipo=".$nda." and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ";
		$this->cscd04_ordencompra_partidas->execute($sql4);
	}//fin for


  //$this->reactualizacion_causado_compra_anticipo_anulado() //falta crearlo

  $this->reactualizacion_causado_compra_autorizacion();
}//fin reactualizacion_causado_compra


function reactualizacion_causado_compra_autorizacion () {

    $partidas=$this->cscd04_ordencompra_anticipo_cuerpo->execute("SELECT
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_orden_compra,
  a.numero_orden_compra,
  a.numero_pago,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto,
  a.numero_control_compromiso,
  a.numero_control_causado,
  a.amortizacion,
  b.condicion_actividad,
  b.fecha_autorizacion,
  b.concepto,b.ano_orden_pago,b.numero_orden_pago

FROM
  cscd04_ordencompra_autorizacion_pago_partidas a,cscd04_ordencompra_autorizacion_pago_cuerpo b
WHERE
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_orden_compra = b.ano_orden_compra and
  a.numero_orden_compra = b.numero_orden_compra and
  a.numero_pago=b.numero_pago ");

    $j =0;
	$i =0;
	$ano=2008;
	$suma = count($partidas);
	$numero_causado= $this->cfpd22_numero_asiento_causado->field('cfpd22_numero_asiento_causado.numero_causado', " ano_causado='$ano'", $order =null);
	if(!empty($numero_causado)){
		$num_base = $numero_causado;
		$numero_causado += $suma;
		$sql_numero_causado = "UPDATE cfpd22_numero_asiento_causado SET numero_causado='$numero_causado' WHERE ano_causado='$ano' ;COMMIT;";
	}else{
		$num_base = 1;
		$numero_causado = $num_base+$suma;
		$sql_numero_causado = "INSERT INTO cfpd22_numero_asiento_causado VALUES('1', '11', '30', '11', '$ano', '$numero_causado')";
	}
	$sw_numero_causado = $this->cfpd22_numero_asiento_causado->query($sql_numero_causado);
//print_r($partidas);
	foreach($partidas as $partida){
		  $cod_presi                           =         $partida[0]['cod_presi'];
		  $cod_entidad                         =         $partida[0]['cod_entidad'];
		  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
		  $cod_inst                            =         $partida[0]['cod_inst'];
		  $cod_dep                             =         $partida[0]['cod_dep'];
		  $this->Session->write('SScodpresi', $cod_presi);
		  $this->Session->write('SScodentidad', $cod_entidad);
		  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
		  $this->Session->write('SScodinst', $cod_inst);
		  $this->Session->write('SScoddep', $cod_dep);
		  $fecha_orden_pago                    =         $partida[0]['fecha_autorizacion'];
		  $fd                                  =         $partida[0]['fecha_autorizacion'];
		  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
		  $ano_orden_compra                    =         $partida[0]['ano_orden_compra'];
		  $ndo                                 =         $partida[0]['numero_orden_compra'];
		  $nda                                 =         $partida[0]['numero_pago'];
		  $ano                                 =         $partida[0]['ano'];
		  $cod_sector                          =         $partida[0]['cod_sector'];
		  $cod_programa                        =         $partida[0]['cod_programa'];
		  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
		  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
		  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
		  $cod_partida                         =         $partida[0]['cod_partida'];
		  $cod_generica                        =         $partida[0]['cod_generica'];
		  $cod_especifica                      =         $partida[0]['cod_especifica'];
		  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
		  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
		  $monto                               =         $partida[0]['monto'];
		  $numero_control_compromiso           =         $partida[0]['numero_control_compromiso'];
		  $numero_control_causado              =         $partida[0]['numero_control_causado'];
		  $numero_control_pagado               =         "0";
		  $concepto = $partida[0]['concepto'];
		  $numero_orden_pago                   =         $partida[0]['numero_orden_pago'];
		  $ano_orden_pago                      =         $partida[0]['ano_orden_pago'];


		$cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
		$to = 1;
		$td = 4;
		$ta = 3;
		$mt = $monto;
	    $ccp = str_replace("'","",$concepto);
		$rnco = $numero_control_compromiso;
		$dnca = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp, $ano, $ndo, $nda, null, null, null, null, null, null, $rnco, null, $num_base, $i);
		$sql4  = "UPDATE cscd04_ordencompra_autorizacion_pago_partidas SET numero_control_causado = '".$numero_control_causado."'  where numero_orden_compra=".$ndo." and numero_pago=".$nda." and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ";
		$this->cscd04_ordencompra_a_pago_partidas->execute($sql4);
		$SQLcepd03_ordenpago_partidas = "UPDATE cepd03_ordenpago_partidas SET numero_control_compromiso='".$numero_control_compromiso."' , numero_control_causado='".$numero_control_causado."'";
        $SQLcepd03_ordenpago_partidas.= "WHERE  ano_orden_pago='".$ano_orden_pago."' AND numero_orden_pago='".$numero_orden_pago."' AND  cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ";
        $this->cepd03_ordenpago_partidas->execute($SQLcepd03_ordenpago_partidas);
	    //echo "<br>dep:".$cod_dep." OC:".$ndo." AU:".$nda." dnca:".$dnca;
	    $j++;
	    $i++;
	}//fin for


  $this->reactualizacion_causado_compra_autorizacion_anulados();
}//fin reactualizacion_causado_compra_autorizacion


function reactualizacion_causado_compra_autorizacion_anulados () {

$partidas=$this->cscd04_ordencompra_anticipo_cuerpo->execute("SELECT
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_orden_compra,
  a.numero_orden_compra,
  a.numero_pago,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto,
  a.numero_control_compromiso,
  a.numero_control_causado,
  a.amortizacion,
  b.condicion_actividad,
  b.fecha_autorizacion,
  b.concepto

FROM
  cscd04_ordencompra_autorizacion_pago_partidas a,cscd04_ordencompra_autorizacion_pago_cuerpo b
WHERE
  b.condicion_actividad=2 and
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_orden_compra = b.ano_orden_compra and
  a.numero_orden_compra = b.numero_orden_compra and
  a.numero_pago=b.numero_pago ");

    $j =0;
	$i =0;
//print_r($partidas);
	foreach($partidas as $partida){
		  $cod_presi                           =         $partida[0]['cod_presi'];
		  $cod_entidad                         =         $partida[0]['cod_entidad'];
		  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
		  $cod_inst                            =         $partida[0]['cod_inst'];
		  $cod_dep                             =         $partida[0]['cod_dep'];
		  $this->Session->write('SScodpresi', $cod_presi);
		  $this->Session->write('SScodentidad', $cod_entidad);
		  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
		  $this->Session->write('SScodinst', $cod_inst);
		  $this->Session->write('SScoddep', $cod_dep);
		  $fecha_orden_pago                    =         $partida[0]['fecha_autorizacion'];
		  $fd                                  =         $partida[0]['fecha_autorizacion'];
		  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
		  $ano_orden_compra                    =         $partida[0]['ano_orden_compra'];
		  $ndo                                 =         $partida[0]['numero_orden_compra'];
		  $nda                                 =         $partida[0]['numero_pago'];
		  $ano                                 =         $partida[0]['ano'];
		  $cod_sector                          =         $partida[0]['cod_sector'];
		  $cod_programa                        =         $partida[0]['cod_programa'];
		  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
		  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
		  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
		  $cod_partida                         =         $partida[0]['cod_partida'];
		  $cod_generica                        =         $partida[0]['cod_generica'];
		  $cod_especifica                      =         $partida[0]['cod_especifica'];
		  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
		  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
		  $monto                               =         $partida[0]['monto'];
		  $numero_control_compromiso           =         $partida[0]['numero_control_compromiso'];
		  $numero_control_causado              =         $partida[0]['numero_control_causado'];
		  $numero_control_pagado               =         "0";
		  //$concepto = $partida[0]['concepto'];
          //$numero_anulacion = $partida[0]['numero_asiento_anulacion'];
		  $busca_concepto_anulacion=$this->cugd03_acta_anulacion_cuerpo->findAll("cod_dep='".$cod_dep."'  and tipo_operacion='243' and numero_documento='".$ndo."'");
          //pr($busca_concepto_anulacion);
          $concepto_anulacion= $busca_concepto_anulacion[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"];

		  $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
		  $x = $this->motor_presupuestario($cp,2, 4, 3, $fd, $monto, str_replace("'","",$concepto_anulacion), $ano, $ndo, $nda, null, null, null, null, null, null,$numero_control_compromiso, $numero_control_causado, null, null);
          //echo "<br>dep:".$cod_dep." OC:".$ndo." AU:".$nda." dnca:".$x." Cto:".$concepto_anulacion;
	    $j++;
	    $i++;
	}//fin for
    //pr($partidas);
}//fin reactualizacion_causado_compra_autorizacion

/**
 * OBRAS ANTICIPOS
 */

function reactualizacion_obras_anticipo () {
  //'cobd01_contratoobras_cuerpo', 'cobd01_contratoobras_partidas'
  $partidas=$this->cobd01_contratoobras_anticipo_cuerpo->execute("select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_contrato_obra,
  a.numero_contrato_obra,
  a.numero_anticipo,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto,
  a.numero_control_compromiso,
  a.numero_control_causado,
  b.fecha_anticipo,
  b.condicion_actividad,
  b.ano_orden_pago,
  b.numero_orden_pago,b.fecha_proceso_registro,
  b.observaciones as concepto
   from
  cobd01_contratoobras_anticipo_partidas a,cobd01_contratoobras_anticipo_cuerpo b
where
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_contrato_obra = b.ano_contrato_obra and
  a.numero_contrato_obra = b.numero_contrato_obra and a.numero_anticipo=b.numero_anticipo");

    $j =0;
	$i =0;
	$ano=2008;
	$suma = count($partidas);
	$numero_causado= $this->cfpd22_numero_asiento_causado->field('cfpd22_numero_asiento_causado.numero_causado', " ano_causado='$ano'", $order =null);
	if(!empty($numero_causado)){
		$num_base = $numero_causado;
		$numero_causado += $suma;
		$sql_numero_causado = "UPDATE cfpd22_numero_asiento_causado SET numero_causado='$numero_causado' WHERE ano_causado='$ano' ;COMMIT;";
	}else{
		$num_base = 1;
		$numero_causado = $num_base+$suma;
		$sql_numero_causado = "INSERT INTO cfpd22_numero_asiento_causado VALUES('1', '11', '30', '11', '$ano', '$numero_causado')";
	}
	$sw_numero_causado = $this->cfpd22_numero_asiento_causado->query($sql_numero_causado);
//print_r($partidas);
	foreach($partidas as $partida){
		  $cod_presi                           =         $partida[0]['cod_presi'];
		  $cod_entidad                         =         $partida[0]['cod_entidad'];
		  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
		  $cod_inst                            =         $partida[0]['cod_inst'];
		  $cod_dep                             =         $partida[0]['cod_dep'];
		  $this->Session->write('SScodpresi', $cod_presi);
		  $this->Session->write('SScodentidad', $cod_entidad);
		  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
		  $this->Session->write('SScodinst', $cod_inst);
		  $this->Session->write('SScoddep', $cod_dep);
		  $ano_movimiento                      =         $partida[0]['ano_contrato_obra'];
		  $fd                                  =         $partida[0]['fecha_anticipo'];
		  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
				  $fr                      =         $partida[0]['fecha_proceso_registro'];
				  $fecha_registro=$fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
				  $fd                                  =         $partida[0]['fecha_anticipo'];
				  $ano_fd=$fd[0].$fd[1].$fd[2].$fd[3];
				  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
				  $ano_fd= (int) $ano_fd;
				  $ano_fdpartida=(int) $partida[0]['ano'];
				  if($ano_fd!=$ano_fdpartida){
				     $fecha_documento=$fecha_registro;
				     $fd=$fecha_registro;
				  }else{
				  	$fecha_documento=$fd;
				  }
		  $ano_orden_compra                    =         $partida[0]['ano_contrato_obra'];
		  $ndo                                 =         $partida[0]['numero_contrato_obra'];
		  $nda                                 =         $partida[0]['numero_anticipo'];
		  $ano                                 =         $partida[0]['ano'];
		  $cod_sector                          =         $partida[0]['cod_sector'];
		  $cod_programa                        =         $partida[0]['cod_programa'];
		  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
		  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
		  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
		  $cod_partida                         =         $partida[0]['cod_partida'];
		  $cod_generica                        =         $partida[0]['cod_generica'];
		  $cod_especifica                      =         $partida[0]['cod_especifica'];
		  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
		  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
		  $monto                               =         $partida[0]['monto'];
		  $numero_control_compromiso           =         $partida[0]['numero_control_compromiso'];
		  $numero_control_causado              =         $partida[0]['numero_control_causado'];
		  $numero_control_pagado               =         "0";
		  $concepto = $partida[0]['concepto'];
		  $ano_orden_pago = $partida[0]['ano_orden_pago'];
		  $numero_orden_pago = $partida[0]['numero_orden_pago'];


		$cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
		$to = 1;
		$td = 4;
		$ta = 4;
		$mt = $monto;
	    $ccp = str_replace("'","",$concepto);
		$rnco = $numero_control_compromiso;
		$dnca = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp, $ano, $ndo, $nda, null, null, null, null, null, null, $rnco, null, $num_base, $i);
		$this->cobd01_contratoobras_anticipo_partidas->execute("UPDATE cobd01_contratoobras_anticipo_partidas SET numero_control_causado = '".$numero_control_causado."'  where numero_contrato_obra='".$ndo."' and numero_anticipo=".$nda." and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
        $SQLcepd03_ordenpago_partidas = "UPDATE cepd03_ordenpago_partidas SET numero_control_compromiso='".$numero_control_compromiso."' , numero_control_causado='".$numero_control_causado."'";
        $SQLcepd03_ordenpago_partidas.= "WHERE  ano_orden_pago='".$ano_orden_pago."' AND numero_orden_pago='".$numero_orden_pago."' AND  cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ";
        $this->cepd03_ordenpago_partidas->execute($SQLcepd03_ordenpago_partidas);
        //echo "<br>dep:".$cod_dep." OA:".$ndo." AU:".$nda." dnca:".$dnca;
	    $j++;
	    $i++;
	}//fin for


   $this->reactualizacion_obras_anticipo_anulado();
}//reactualizacion_obras_anticipo


function reactualizacion_obras_anticipo_anulado () {
  //'cobd01_contratoobras_cuerpo', 'cobd01_contratoobras_partidas'
  $partidas=$this->cobd01_contratoobras_anticipo_cuerpo->execute("select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_contrato_obra,
  a.numero_contrato_obra,
  a.numero_anticipo,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto,
  a.numero_control_compromiso,
  a.numero_control_causado,
  b.fecha_anticipo,b.fecha_proceso_registro,
  b.condicion_actividad,
  b.ano_orden_pago,
  b.numero_orden_pago,
  b.observaciones as concepto
   from
  cobd01_contratoobras_anticipo_partidas a,cobd01_contratoobras_anticipo_cuerpo b
where
  b.condicion_actividad=2 and
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_contrato_obra = b.ano_contrato_obra and
  a.numero_contrato_obra = b.numero_contrato_obra and a.numero_anticipo=b.numero_anticipo");

    $j =0;
	$i =0;

	foreach($partidas as $partida){
		  $cod_presi                           =         $partida[0]['cod_presi'];
		  $cod_entidad                         =         $partida[0]['cod_entidad'];
		  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
		  $cod_inst                            =         $partida[0]['cod_inst'];
		  $cod_dep                             =         $partida[0]['cod_dep'];
		  $this->Session->write('SScodpresi', $cod_presi);
		  $this->Session->write('SScodentidad', $cod_entidad);
		  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
		  $this->Session->write('SScodinst', $cod_inst);
		  $this->Session->write('SScoddep', $cod_dep);
		  $ano_movimiento                      =         $partida[0]['ano_contrato_obra'];
				  $fr                      =         $partida[0]['fecha_proceso_registro'];
				  $fecha_registro=$fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
				  $fd                                  =         $partida[0]['fecha_anticipo'];
				  $ano_fd=$fd[0].$fd[1].$fd[2].$fd[3];
				  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
				  $ano_fd= (int) $ano_fd;
				  $ano_fdpartida=(int) $partida[0]['ano'];
				  if($ano_fd!=$ano_fdpartida){
				     $fecha_documento=$fecha_registro;
				     $fd=$fecha_registro;
				  }else{
				  	$fecha_documento=$fd;
				  }
		  $ano_orden_compra                    =         $partida[0]['ano_contrato_obra'];
		  $ndo                                 =         $partida[0]['numero_contrato_obra'];
		  $nda                                 =         $partida[0]['numero_anticipo'];
		  $ano                                 =         $partida[0]['ano'];
		  $cod_sector                          =         $partida[0]['cod_sector'];
		  $cod_programa                        =         $partida[0]['cod_programa'];
		  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
		  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
		  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
		  $cod_partida                         =         $partida[0]['cod_partida'];
		  $cod_generica                        =         $partida[0]['cod_generica'];
		  $cod_especifica                      =         $partida[0]['cod_especifica'];
		  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
		  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
		  $monto                               =         $partida[0]['monto'];
		  $numero_control_compromiso           =         $partida[0]['numero_control_compromiso'];
		  $numero_control_causado              =         $partida[0]['numero_control_causado'];
		  $numero_control_pagado               =         "0";
		  $concepto = $partida[0]['concepto'];
		  $ano_orden_pago = $partida[0]['ano_orden_pago'];
		  $numero_orden_pago = $partida[0]['numero_orden_pago'];

          $busca_concepto_anulacion=$this->cugd03_acta_anulacion_cuerpo->findAll("cod_dep='".$cod_dep."'  and tipo_operacion='244' and numero_documento='".$ndo."'");
          //pr($busca_concepto_anulacion);
          $concepto_anulacion= $busca_concepto_anulacion[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"];

		  $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
		  $x = $this->motor_presupuestario($cp,2, 4, 4, $fd, $monto, str_replace("'","",$concepto_anulacion), $ano, $ndo, $nda, null, null, null, null, null, null,$numero_control_compromiso, $numero_control_causado, null, null);
          //echo "<br>dep:".$cod_dep." OC:".$ndo." AU:".$nda." dnca:".$x." Cto:".$concepto_anulacion;
	    $j++;
	    $i++;
	}//fin for


	$this->reactualizacion_obras_valuacion();
}//reactualizacion_obras_anticipo_anulado


/**
 * SERVICIOS ANTICIPOS
 *
 *
 *
 */
function reactualizacion_servicios_anticipo () {
  //'cepd02_contratoservicio_anticipo_cuerpo',''cepd02_contratoservicio_anticipo_partidas''
  $partidas=$this->cepd02_contratoservicio_anticipo_cuerpo->execute("select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_contrato_servicio,
  a.numero_contrato_servicio,
  a.numero_anticipo,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto,
  a.numero_control_compromiso,
  a.numero_control_causado,
  b.fecha_anticipo,b.fecha_proceso_registro,
  b.condicion_actividad,
  b.ano_orden_pago,
  b.numero_orden_pago,
  b.observaciones as concepto
   from
  cepd02_contratoservicio_anticipo_partidas a,cepd02_contratoservicio_anticipo_cuerpo b
where
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_contrato_servicio = b.ano_contrato_servicio and
  a.numero_contrato_servicio = b.numero_contrato_servicio and a.numero_anticipo=b.numero_anticipo");

    $j =0;
	$i =0;
	$ano=2008;
	$suma = count($partidas);
	$numero_causado= $this->cfpd22_numero_asiento_causado->field('cfpd22_numero_asiento_causado.numero_causado', " ano_causado='$ano'", $order =null);
	if(!empty($numero_causado)){
		$num_base = $numero_causado;
		$numero_causado += $suma;
		$sql_numero_causado = "UPDATE cfpd22_numero_asiento_causado SET numero_causado='$numero_causado' WHERE ano_causado='$ano' ;COMMIT;";
	}else{
		$num_base = 1;
		$numero_causado = $num_base+$suma;
		$sql_numero_causado = "INSERT INTO cfpd22_numero_asiento_causado VALUES('1', '11', '30', '11', '$ano', '$numero_causado')";
	}
	$sw_numero_causado = $this->cfpd22_numero_asiento_causado->query($sql_numero_causado);
//print_r($partidas);
	foreach($partidas as $partida){
		  $cod_presi                           =         $partida[0]['cod_presi'];
		  $cod_entidad                         =         $partida[0]['cod_entidad'];
		  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
		  $cod_inst                            =         $partida[0]['cod_inst'];
		  $cod_dep                             =         $partida[0]['cod_dep'];
		  $this->Session->write('SScodpresi', $cod_presi);
		  $this->Session->write('SScodentidad', $cod_entidad);
		  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
		  $this->Session->write('SScodinst', $cod_inst);
		  $this->Session->write('SScoddep', $cod_dep);
		  $ano_movimiento                      =         $partida[0]['ano_contrato_servicio'];
				  $fr                      =         $partida[0]['fecha_proceso_registro'];
				  $fecha_registro=$fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
				  $fd                                  =         $partida[0]['fecha_anticipo'];
				  $ano_fd=$fd[0].$fd[1].$fd[2].$fd[3];
				  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
				  $ano_fd= (int) $ano_fd;
				  $ano_fdpartida=(int) $partida[0]['ano'];
				  if($ano_fd!=$ano_fdpartida){
				     $fecha_documento=$fecha_registro;
				     $fd=$fecha_registro;
				  }else{
				  	$fecha_documento=$fd;
				  }
		  $ano_orden_compra                    =         $partida[0]['ano_contrato_servicio'];
		  $ndo                                 =         $partida[0]['numero_contrato_servicio'];
		  $nda                                 =         $partida[0]['numero_anticipo'];
		  $ano                                 =         $partida[0]['ano'];
		  $cod_sector                          =         $partida[0]['cod_sector'];
		  $cod_programa                        =         $partida[0]['cod_programa'];
		  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
		  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
		  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
		  $cod_partida                         =         $partida[0]['cod_partida'];
		  $cod_generica                        =         $partida[0]['cod_generica'];
		  $cod_especifica                      =         $partida[0]['cod_especifica'];
		  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
		  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
		  $monto                               =         $partida[0]['monto'];
		  $numero_control_compromiso           =         $partida[0]['numero_control_compromiso'];
		  $numero_control_causado              =         $partida[0]['numero_control_causado'];
		  $numero_control_pagado               =         "0";
		  $concepto = $partida[0]['concepto'];
		  $ano_orden_pago = $partida[0]['ano_orden_pago'];
		  $numero_orden_pago = $partida[0]['numero_orden_pago'];


		$cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
		$to = 1;
		$td = 4;
		$ta = 6;
		$mt = $monto;
	    $ccp = str_replace("'","",$concepto);
		$rnco = $numero_control_compromiso;
		$dnca = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp, $ano, $ndo, $nda, null, null, null, null, null, null, $rnco, null, $num_base, $i);
		$this->cepd02_contratoservicio_anticipo_partidas->execute("UPDATE cepd02_contratoservicio_anticipo_partidas SET numero_control_causado = '".$numero_control_causado."'  where numero_contrato_servicio='".$ndo."' and numero_anticipo=".$nda." and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
        $SQLcepd03_ordenpago_partidas = "UPDATE cepd03_ordenpago_partidas SET numero_control_compromiso='".$numero_control_compromiso."' , numero_control_causado='".$numero_control_causado."'";
        $SQLcepd03_ordenpago_partidas.= "WHERE  ano_orden_pago='".$ano_orden_pago."' AND numero_orden_pago='".$numero_orden_pago."' AND  cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ";
        $this->cepd03_ordenpago_partidas->execute($SQLcepd03_ordenpago_partidas);
        //echo "<br>dep:".$cod_dep." OA:".$ndo." AU:".$nda." dnca:".$dnca;
	    $j++;
	    $i++;
	}//fin for


  $this->reactualizacion_servicios_anticipo_anulado();
}//reactualizacion_obras_anticipo


function reactualizacion_servicios_anticipo_anulado () {
  //'cobd01_contratoobras_cuerpo', 'cobd01_contratoobras_partidas'
  $partidas=$this->cepd02_contratoservicio_anticipo_cuerpo->execute("select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_contrato_servicio,
  a.numero_contrato_servicio,
  a.numero_anticipo,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto,
  a.numero_control_compromiso,
  a.numero_control_causado,
  b.fecha_anticipo,b.fecha_proceso_registro,
  b.condicion_actividad,
  b.ano_orden_pago,
  b.numero_orden_pago,
  b.observaciones as concepto
   from
  cepd02_contratoservicio_anticipo_partidas a,cepd02_contratoservicio_anticipo_cuerpo b
where
  b.condicion_actividad=2 and
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_contrato_servicio = b.ano_contrato_servicio and
  a.numero_contrato_servicio = b.numero_contrato_servicio and a.numero_anticipo=b.numero_anticipo");

    $j =0;
	$i =0;

	foreach($partidas as $partida){
		  $cod_presi                           =         $partida[0]['cod_presi'];
		  $cod_entidad                         =         $partida[0]['cod_entidad'];
		  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
		  $cod_inst                            =         $partida[0]['cod_inst'];
		  $cod_dep                             =         $partida[0]['cod_dep'];
		  $this->Session->write('SScodpresi', $cod_presi);
		  $this->Session->write('SScodentidad', $cod_entidad);
		  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
		  $this->Session->write('SScodinst', $cod_inst);
		  $this->Session->write('SScoddep', $cod_dep);
		  $ano_movimiento                      =         $partida[0]['ano_contrato_servicio'];
				  $fr                      =         $partida[0]['fecha_proceso_registro'];
				  $fecha_registro=$fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
				  $fd                                  =         $partida[0]['fecha_anticipo'];
				  $ano_fd=$fd[0].$fd[1].$fd[2].$fd[3];
				  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
				  $ano_fd= (int) $ano_fd;
				  $ano_fdpartida=(int) $partida[0]['ano'];
				  if($ano_fd!=$ano_fdpartida){
				     $fecha_documento=$fecha_registro;
				     $fd=$fecha_registro;
				  }else{
				  	$fecha_documento=$fd;
				  }
		  $ano_orden_compra                    =         $partida[0]['ano_contrato_servicio'];
		  $ndo                                 =         $partida[0]['numero_contrato_servicio'];
		  $nda                                 =         $partida[0]['numero_anticipo'];
		  $ano                                 =         $partida[0]['ano'];
		  $cod_sector                          =         $partida[0]['cod_sector'];
		  $cod_programa                        =         $partida[0]['cod_programa'];
		  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
		  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
		  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
		  $cod_partida                         =         $partida[0]['cod_partida'];
		  $cod_generica                        =         $partida[0]['cod_generica'];
		  $cod_especifica                      =         $partida[0]['cod_especifica'];
		  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
		  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
		  $monto                               =         $partida[0]['monto'];
		  $numero_control_compromiso           =         $partida[0]['numero_control_compromiso'];
		  $numero_control_causado              =         $partida[0]['numero_control_causado'];
		  $numero_control_pagado               =         "0";
		  $concepto = $partida[0]['concepto'];
		  $ano_orden_pago = $partida[0]['ano_orden_pago'];
		  $numero_orden_pago = $partida[0]['numero_orden_pago'];

          $busca_concepto_anulacion=$this->cugd03_acta_anulacion_cuerpo->findAll("cod_dep='".$cod_dep."'  and tipo_operacion='246' and numero_documento='".$ndo."'");
          //pr($busca_concepto_anulacion);
          $concepto_anulacion= $busca_concepto_anulacion[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"];

		  $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
		  $x = $this->motor_presupuestario($cp,2, 4, 6, $fd, $monto, str_replace("'","",$concepto_anulacion), $ano, $ndo, $nda, null, null, null, null, null, null,$numero_control_compromiso, $numero_control_causado, null, null);
          //echo "<br>dep:".$cod_dep." OC:".$ndo." AU:".$nda." dnca:".$x." Cto:".$concepto_anulacion;
	    $j++;
	    $i++;
	}//fin for

	$this->reactualizacion_servicios_valuacion();
}//reactualizacion_servicios_anticipo_anulado


/**
 *
 * VALUACIONES OBRAS
 *
 */

function reactualizacion_obras_valuacion () {
  //'cobd01_contratoobras_valuacion_partidas','cobd01_contratoobras_valuacion_cuerpo'
  $partidas=$this->cobd01_contratoobras_valuacion_cuerpo->execute("select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_contrato_obra,
  a.numero_contrato_obra,
  a.numero_valuacion,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto,
  a.numero_control_compromiso,
  a.numero_control_causado,
  b.fecha_valuacion,b.fecha_proceso_registro,
  b.condicion_actividad,
  b.ano_orden_pago,
  b.numero_orden_pago,
  b.concepto
   from
  cobd01_contratoobras_valuacion_partidas a,cobd01_contratoobras_valuacion_cuerpo b
where
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_contrato_obra = b.ano_contrato_obra and
  a.numero_contrato_obra = b.numero_contrato_obra and a.numero_valuacion=b.numero_valuacion");

    $j =0;
	$i =0;
	$ano=2008;
	$suma = count($partidas);
	$numero_causado= $this->cfpd22_numero_asiento_causado->field('cfpd22_numero_asiento_causado.numero_causado', " ano_causado='$ano'", $order =null);
	if(!empty($numero_causado)){
		$num_base = $numero_causado;
		$numero_causado += $suma;
		$sql_numero_causado = "UPDATE cfpd22_numero_asiento_causado SET numero_causado='$numero_causado' WHERE ano_causado='$ano' ;COMMIT;";
	}else{
		$num_base = 1;
		$numero_causado = $num_base+$suma;
		$sql_numero_causado = "INSERT INTO cfpd22_numero_asiento_causado VALUES('1', '11', '30', '11', '$ano', '$numero_causado')";
	}
	$sw_numero_causado = $this->cfpd22_numero_asiento_causado->query($sql_numero_causado);
//print_r($partidas);
	foreach($partidas as $partida){
		  $cod_presi                           =         $partida[0]['cod_presi'];
		  $cod_entidad                         =         $partida[0]['cod_entidad'];
		  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
		  $cod_inst                            =         $partida[0]['cod_inst'];
		  $cod_dep                             =         $partida[0]['cod_dep'];
		  $this->Session->write('SScodpresi', $cod_presi);
		  $this->Session->write('SScodentidad', $cod_entidad);
		  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
		  $this->Session->write('SScodinst', $cod_inst);
		  $this->Session->write('SScoddep', $cod_dep);
		  $ano_movimiento                      =         $partida[0]['ano_contrato_obra'];
				  $fr                      =         $partida[0]['fecha_proceso_registro'];
				  $fecha_registro=$fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
				  $fd                                  =         $partida[0]['fecha_valuacion'];
				  $ano_fd=$fd[0].$fd[1].$fd[2].$fd[3];
				  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
				  $ano_fd= (int) $ano_fd;
				  $ano_fdpartida=(int) $partida[0]['ano'];
				  if($ano_fd!=$ano_fdpartida){
				     $fecha_documento=$fecha_registro;
				     $fd=$fecha_registro;
				  }else{
				  	$fecha_documento=$fd;
				  }
		  $ano_orden_compra                    =         $partida[0]['ano_contrato_obra'];
		  $ndo                                 =         $partida[0]['numero_contrato_obra'];
		  $nda                                 =         $partida[0]['numero_valuacion'];
		  $ano                                 =         $partida[0]['ano'];
		  $cod_sector                          =         $partida[0]['cod_sector'];
		  $cod_programa                        =         $partida[0]['cod_programa'];
		  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
		  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
		  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
		  $cod_partida                         =         $partida[0]['cod_partida'];
		  $cod_generica                        =         $partida[0]['cod_generica'];
		  $cod_especifica                      =         $partida[0]['cod_especifica'];
		  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
		  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
		  $monto                               =         $partida[0]['monto'];
		  $numero_control_compromiso           =         $partida[0]['numero_control_compromiso'];
		  $numero_control_causado              =         $partida[0]['numero_control_causado'];
		  $numero_control_pagado               =         "0";
		  $concepto = $partida[0]['concepto'];
		  $ano_orden_pago = $partida[0]['ano_orden_pago'];
		  $numero_orden_pago = $partida[0]['numero_orden_pago'];


		$cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
		$to = 1;
		$td = 4;
		$ta = 5;
		$mt = $monto;
	    $ccp = str_replace("'","",$concepto);
		$rnco = $numero_control_compromiso;
		$dnca = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp, $ano, $ndo, $nda, null, null, null, null, null, null, $rnco, null, $num_base, $i);
		$this->cobd01_contratoobras_valuacion_partidas->execute("UPDATE cobd01_contratoobras_valuacion_partidas SET numero_control_causado = '".$numero_control_causado."'  where numero_contrato_obra='".$ndo."' and numero_valuacion=".$nda." and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and  ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
        $SQLcepd03_ordenpago_partidas = "UPDATE cepd03_ordenpago_partidas SET numero_control_compromiso='".$numero_control_compromiso."' , numero_control_causado='".$numero_control_causado."'";
        $SQLcepd03_ordenpago_partidas.= "WHERE  ano_orden_pago='".$ano_orden_pago."' AND numero_orden_pago='".$numero_orden_pago."' AND  cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ";
        $this->cepd03_ordenpago_partidas->execute($SQLcepd03_ordenpago_partidas);
        //echo "<br>dep:".$cod_dep." OA:".$ndo." AU:".$nda." dnca:".$dnca;
	    $j++;
	    $i++;
	}//fin for


$this->reactualizacion_obras_valuacion_anulado();
}//reactualizacion_obras_anticipo


function reactualizacion_obras_valuacion_anulado () {
  //'cobd01_contratoobras_cuerpo', 'cobd01_contratoobras_partidas'
  $partidas=$this->cobd01_contratoobras_valuacion_cuerpo->execute("select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_contrato_obra,
  a.numero_contrato_obra,
  a.numero_valuacion,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto,
  a.numero_control_compromiso,
  a.numero_control_causado,
  b.fecha_valuacion,b.fecha_proceso_registro,
  b.condicion_actividad,
  b.ano_orden_pago,
  b.numero_orden_pago,
  b.concepto
   from
  cobd01_contratoobras_valuacion_partidas a,cobd01_contratoobras_valuacion_cuerpo b
where
  b.condicion_actividad=2 and
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_contrato_obra = b.ano_contrato_obra and
  a.numero_contrato_obra = b.numero_contrato_obra and a.numero_valuacion=b.numero_valuacion");

    $j =0;
	$i =0;

	foreach($partidas as $partida){
		  $cod_presi                           =         $partida[0]['cod_presi'];
		  $cod_entidad                         =         $partida[0]['cod_entidad'];
		  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
		  $cod_inst                            =         $partida[0]['cod_inst'];
		  $cod_dep                             =         $partida[0]['cod_dep'];
		  $this->Session->write('SScodpresi', $cod_presi);
		  $this->Session->write('SScodentidad', $cod_entidad);
		  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
		  $this->Session->write('SScodinst', $cod_inst);
		  $this->Session->write('SScoddep', $cod_dep);
		  $ano_movimiento                      =         $partida[0]['ano_contrato_obra'];
				  $fr                      =         $partida[0]['fecha_proceso_registro'];
				  $fecha_registro=$fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
				  $fd                                  =         $partida[0]['fecha_valuacion'];
				  $ano_fd=$fd[0].$fd[1].$fd[2].$fd[3];
				  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
				  $ano_fd= (int) $ano_fd;
				  $ano_fdpartida=(int) $partida[0]['ano'];
				  if($ano_fd!=$ano_fdpartida){
				     $fecha_documento=$fecha_registro;
				     $fd=$fecha_registro;
				  }else{
				  	$fecha_documento=$fd;
				  }
		  $ano_orden_compra                    =         $partida[0]['ano_contrato_obra'];
		  $ndo                                 =         $partida[0]['numero_contrato_obra'];
		  $nda                                 =         $partida[0]['numero_valuacion'];
		  $ano                                 =         $partida[0]['ano'];
		  $cod_sector                          =         $partida[0]['cod_sector'];
		  $cod_programa                        =         $partida[0]['cod_programa'];
		  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
		  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
		  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
		  $cod_partida                         =         $partida[0]['cod_partida'];
		  $cod_generica                        =         $partida[0]['cod_generica'];
		  $cod_especifica                      =         $partida[0]['cod_especifica'];
		  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
		  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
		  $monto                               =         $partida[0]['monto'];
		  $numero_control_compromiso           =         $partida[0]['numero_control_compromiso'];
		  $numero_control_causado              =         $partida[0]['numero_control_causado'];
		  $numero_control_pagado               =         "0";
		  $concepto = $partida[0]['concepto'];
		  $ano_orden_pago = $partida[0]['ano_orden_pago'];
		  $numero_orden_pago = $partida[0]['numero_orden_pago'];

          $busca_concepto_anulacion=$this->cugd03_acta_anulacion_cuerpo->findAll("cod_dep='".$cod_dep."'  and tipo_operacion='245' and numero_documento='".$ndo."'");
          //pr($busca_concepto_anulacion);
          $concepto_anulacion= $busca_concepto_anulacion[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"];//aqui arroja error.offset 0

		  $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
		  $x = $this->motor_presupuestario($cp,2, 4, 5, $fd, $monto, str_replace("'","",$concepto_anulacion), $ano, $ndo, $nda, null, null, null, null, null, null,$numero_control_compromiso, $numero_control_causado, null, null);
          //echo "<br>dep:".$cod_dep." OC:".$ndo." AU:".$nda." dnca:".$x." Cto:".$concepto_anulacion;
	    $j++;
	    $i++;
	}//fin for
}//reactualizacion_obras_anticipo_anulado


/**
 * VALUACIONES SERVICIOS
 *
 *
 *
 */
function reactualizacion_servicios_valuacion () {
  //'cepd02_contratoservicio_valuacion_cuerpo','cepd02_contratoservicio_valuacion_partidas'
  $partidas=$this->cepd02_contratoservicio_valuacion_cuerpo->execute("select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_contrato_servicio,
  a.numero_contrato_servicio,
  a.numero_valuacion,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto,
  a.numero_control_compromiso,
  a.numero_control_causado,
  b.fecha_valuacion,b.fecha_proceso_registro,
  b.condicion_actividad,
  b.ano_orden_pago,
  b.numero_orden_pago,
  b.concepto
   from
  cepd02_contratoservicio_valuacion_partidas a,cepd02_contratoservicio_valuacion_cuerpo b
where
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_contrato_servicio = b.ano_contrato_servicio and
  a.numero_contrato_servicio = b.numero_contrato_servicio and a.numero_valuacion=b.numero_valuacion");

    $j =0;
	$i =0;
	$ano=2008;
	$suma = count($partidas);
	$numero_causado= $this->cfpd22_numero_asiento_causado->field('cfpd22_numero_asiento_causado.numero_causado', " ano_causado='$ano'", $order =null);
	if(!empty($numero_causado)){
		$num_base = $numero_causado;
		$numero_causado += $suma;
		$sql_numero_causado = "UPDATE cfpd22_numero_asiento_causado SET numero_causado='$numero_causado' WHERE ano_causado='$ano' ;COMMIT;";
	}else{
		$num_base = 1;
		$numero_causado = $num_base+$suma;
		$sql_numero_causado = "INSERT INTO cfpd22_numero_asiento_causado VALUES('1', '11', '30', '11', '$ano', '$numero_causado')";
	}
	$sw_numero_causado = $this->cfpd22_numero_asiento_causado->query($sql_numero_causado);
//print_r($partidas);
	foreach($partidas as $partida){
		  $cod_presi                           =         $partida[0]['cod_presi'];
		  $cod_entidad                         =         $partida[0]['cod_entidad'];
		  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
		  $cod_inst                            =         $partida[0]['cod_inst'];
		  $cod_dep                             =         $partida[0]['cod_dep'];
		  $this->Session->write('SScodpresi', $cod_presi);
		  $this->Session->write('SScodentidad', $cod_entidad);
		  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
		  $this->Session->write('SScodinst', $cod_inst);
		  $this->Session->write('SScoddep', $cod_dep);
		  $ano_movimiento                      =         $partida[0]['ano_contrato_servicio'];
				  $fr                      =         $partida[0]['fecha_proceso_registro'];
				  $fecha_registro=$fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
				  $fd                                  =         $partida[0]['fecha_valuacion'];
				  $ano_fd=$fd[0].$fd[1].$fd[2].$fd[3];
				  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
				  $ano_fd= (int) $ano_fd;
				  $ano_fdpartida=(int) $partida[0]['ano'];
				  if($ano_fd!=$ano_fdpartida){
				     $fecha_documento=$fecha_registro;
				     $fd=$fecha_registro;
				  }else{
				  	$fecha_documento=$fd;
				  }
		  $ano_orden_compra                    =         $partida[0]['ano_contrato_servicio'];
		  $ndo                                 =         $partida[0]['numero_contrato_servicio'];
		  $nda                                 =         $partida[0]['numero_valuacion'];
		  $ano                                 =         $partida[0]['ano'];
		  $cod_sector                          =         $partida[0]['cod_sector'];
		  $cod_programa                        =         $partida[0]['cod_programa'];
		  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
		  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
		  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
		  $cod_partida                         =         $partida[0]['cod_partida'];
		  $cod_generica                        =         $partida[0]['cod_generica'];
		  $cod_especifica                      =         $partida[0]['cod_especifica'];
		  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
		  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
		  $monto                               =         $partida[0]['monto'];
		  $numero_control_compromiso           =         $partida[0]['numero_control_compromiso'];
		  $numero_control_causado              =         $partida[0]['numero_control_causado'];
		  $numero_control_pagado               =         "0";
		  $concepto = $partida[0]['concepto'];
		  $ano_orden_pago = $partida[0]['ano_orden_pago'];
		  $numero_orden_pago = $partida[0]['numero_orden_pago'];


		$cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
		$to = 1;
		$td = 4;
		$ta = 7;
		$mt = $monto;
	    $ccp = str_replace("'","",$concepto);
		$rnco = $numero_control_compromiso;
		$dnca = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp, $ano, $ndo, $nda, null, null, null, null, null, null, $rnco, null, $num_base, $i);
		$this->cepd02_contratoservicio_valuacion_partidas->execute("UPDATE cepd02_contratoservicio_valuacion_partidas SET numero_control_causado = '".$numero_control_causado."'  where numero_contrato_servicio='".$ndo."' and numero_valuacion=".$nda." and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
        $SQLcepd03_ordenpago_partidas = "UPDATE cepd03_ordenpago_partidas SET numero_control_compromiso='".$numero_control_compromiso."' , numero_control_causado='".$numero_control_causado."'";
        $SQLcepd03_ordenpago_partidas.= "WHERE  ano_orden_pago='".$ano_orden_pago."' AND numero_orden_pago='".$numero_orden_pago."' AND  cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ";
        $this->cepd03_ordenpago_partidas->execute($SQLcepd03_ordenpago_partidas);
        //echo "<br>dep:".$cod_dep." OA:".$ndo." AU:".$nda." dnca:".$dnca;
	    $j++;
	    $i++;
	}//fin for


$this->reactualizacion_servicios_valuacion_anulado();
}//reactualizacion_obras_anticipo


function reactualizacion_servicios_valuacion_anulado () {
  //'cobd01_contratoobras_cuerpo', 'cobd01_contratoobras_partidas'
  $partidas=$this->cepd02_contratoservicio_valuacion_cuerpo->execute("select
  a.cod_presi,
  a.cod_entidad,
  a.cod_tipo_inst,
  a.cod_inst,
  a.cod_dep,
  a.ano_contrato_servicio,
  a.numero_contrato_servicio,
  a.numero_valuacion,
  a.ano,
  a.cod_sector,
  a.cod_programa,
  a.cod_sub_prog,
  a.cod_proyecto,
  a.cod_activ_obra,
  a.cod_partida,
  a.cod_generica,
  a.cod_especifica,
  a.cod_sub_espec,
  a.cod_auxiliar,
  a.monto,
  a.numero_control_compromiso,
  a.numero_control_causado,
  b.fecha_valuacion,b.fecha_proceso_registro,
  b.condicion_actividad,
  b.ano_orden_pago,
  b.numero_orden_pago,
  b.concepto
   from
  cepd02_contratoservicio_valuacion_partidas a,cepd02_contratoservicio_valuacion_cuerpo b
where
  b.condicion_actividad=2 and
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_contrato_servicio = b.ano_contrato_servicio and
  a.numero_contrato_servicio = b.numero_contrato_servicio and a.numero_valuacion=b.numero_valuacion");

    $j =0;
	$i =0;

	foreach($partidas as $partida){
		  $cod_presi                           =         $partida[0]['cod_presi'];
		  $cod_entidad                         =         $partida[0]['cod_entidad'];
		  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
		  $cod_inst                            =         $partida[0]['cod_inst'];
		  $cod_dep                             =         $partida[0]['cod_dep'];
		  $this->Session->write('SScodpresi', $cod_presi);
		  $this->Session->write('SScodentidad', $cod_entidad);
		  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
		  $this->Session->write('SScodinst', $cod_inst);
		  $this->Session->write('SScoddep', $cod_dep);
		  $ano_movimiento                      =         $partida[0]['ano_contrato_servicio'];
				  $fr                      =         $partida[0]['fecha_proceso_registro'];
				  $fecha_registro=$fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
				  $fd                                  =         $partida[0]['fecha_valuacion'];
				  $ano_fd=$fd[0].$fd[1].$fd[2].$fd[3];
				  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
				  $ano_fd= (int) $ano_fd;
				  $ano_fdpartida=(int) $partida[0]['ano'];
				  if($ano_fd!=$ano_fdpartida){
				     $fecha_documento=$fecha_registro;
				     $fd=$fecha_registro;
				  }else{
				  	$fecha_documento=$fd;
				  }
		  $ano_orden_compra                    =         $partida[0]['ano_contrato_servicio'];
		  $ndo                                 =         $partida[0]['numero_contrato_servicio'];
		  $nda                                 =         $partida[0]['numero_valuacion'];
		  $ano                                 =         $partida[0]['ano'];
		  $cod_sector                          =         $partida[0]['cod_sector'];
		  $cod_programa                        =         $partida[0]['cod_programa'];
		  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
		  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
		  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
		  $cod_partida                         =         $partida[0]['cod_partida'];
		  $cod_generica                        =         $partida[0]['cod_generica'];
		  $cod_especifica                      =         $partida[0]['cod_especifica'];
		  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
		  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
		  $monto                               =         $partida[0]['monto'];
		  $numero_control_compromiso           =         $partida[0]['numero_control_compromiso'];
		  $numero_control_causado              =         $partida[0]['numero_control_causado'];
		  $numero_control_pagado               =         "0";
		  $concepto = $partida[0]['concepto'];
		  $ano_orden_pago = $partida[0]['ano_orden_pago'];
		  $numero_orden_pago = $partida[0]['numero_orden_pago'];

          $busca_concepto_anulacion=$this->cugd03_acta_anulacion_cuerpo->findAll("cod_dep='".$cod_dep."'  and tipo_operacion='247' and numero_documento='".$ndo."'");
          //pr($busca_concepto_anulacion);
          $concepto_anulacion= $busca_concepto_anulacion[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"];//arroja error

		  $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
		  $x = $this->motor_presupuestario($cp,2, 4, 7, $fd, $monto, str_replace("'","",$concepto_anulacion), $ano, $ndo, $nda, null, null, null, null, null, null,$numero_control_compromiso, $numero_control_causado, null, null);
         // echo "<br>dep:".$cod_dep." OC:".$ndo." AU:".$nda." dnca:".$x." Cto:".$concepto_anulacion;
	    $j++;
	    $i++;
	}//fin for
}//reactualizacion_servicios_anticipo_anulado


###COMPROMISO CUASADO PAGADO:RENDICIONES

function reactualizacion_rendiciones(){
  echo "rendiones";
  $partidas=$this->cepd01_compromiso_cuerpo->execute("SELECT * FROM cfpd30_rendiciones_partidas a,cfpd30_rendiciones_cuerpo b
WHERE
a.cod_presi=b.cod_presi and
a.cod_entidad=b.cod_entidad and
a.cod_tipo_inst=b.cod_tipo_inst and
a.cod_inst=b.cod_inst and
a.cod_dep=b.cod_dep and
a.ano_rendicion=b.ano_rendicion and
a.numero_rendicion=b.numero_rendicion");
             //$camposT2="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_documento,numero_documento,cod_tipo_compromiso,fecha_documento,tipo_recurso,rif,cedula_identidad,cod_dir_superior,cod_coordinacion,cod_secretaria,cod_direccion,concepto,monto,condicion_actividad,dia_asiento_registro,mes_asiento_registro,ano_asiento_registro, numero_asiento_registro,username_registro,ano_anulacion,numero_anulacion,dia_asiento_anulacion,mes_asiento_anulacion,ano_asiento_anulacion,numero_asiento_anulacion,username_anulacion,ano_orden_pago,numero_orden_pago,beneficiario,condicion_juridica,fecha_proceso_registro,fecha_proceso_anulacion";
			 //$camposT3="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_documento,numero_documento,ano,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,monto,numero_control_compromiso";
			 $j =0;
			 $ano=2008;
      $suma = count($partidas);
      $numero_compromiso= $this->cfpd21_numero_asiento_compromiso->field('cfpd21_numero_asiento_compromiso.numero_compromiso', $conditions = $this->SQLCA_noDEP()." and ano_compromiso='$ano'", $order =null);
      if(!empty($numero_compromiso)){
        $num_base = $numero_compromiso;
        $numero_compromiso += $suma;
        $sql_numero_compromiso = "UPDATE cfpd21_numero_asiento_compromiso SET numero_compromiso='$numero_compromiso' WHERE ano_compromiso='$ano' and ".$this->SQLCA_noDEP().";";
      }else{
        $num_base = 1;
        $numero_compromiso = $num_base + $suma;
        $sql_numero_compromiso = "INSERT INTO cfpd21_numero_asiento_compromiso VALUES('1', '11', '30', '11', '$ano', '$numero_compromiso');";
      }
      $sw_numero_compromiso = $this->cfpd21_numero_asiento_compromiso->query($sql_numero_compromiso);
      if($sw_numero_compromiso > 1){
	  foreach($partidas as $partida){
		  $cod_presi                           =         $partida[0]['cod_presi'];
		  $cod_entidad                         =         $partida[0]['cod_entidad'];
		  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
		  $cod_inst                            =         $partida[0]['cod_inst'];
		  $cod_dep                             =         $partida[0]['cod_dep'];
		  $this->Session->write('SScodpresi', $cod_presi);
		  $this->Session->write('SScodentidad', $cod_entidad);
		  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
		  $this->Session->write('SScodinst', $cod_inst);
		  $this->Session->write('SScoddep', $cod_dep);
		  $ano_documento                       =         $partida[0]['ano_rendicion'];
				  $fr                      =         $partida[0]['fecha_proceso_registro'];
				  $fecha_registro=$fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
				  $fd                                  =         $partida[0]['fecha_rendicion'];
				  $ano_fd=$fd[0].$fd[1].$fd[2].$fd[3];
				  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
				  $ano_fd= (int) $ano_fd;
				  $ano_fdpartida=(int) $partida[0]['ano'];
				  if($ano_fd!=$ano_fdpartida){
				     $fecha_documento=$fecha_registro;
				     $fd=$fecha_registro;
				  }else{
				  	$fecha_documento=$fd;
				  }

		  $numero_documento                    =         $partida[0]['numero_rendicion'];
		  $ano                                 =         $partida[0]['ano'];
		  $cod_sector                          =         $partida[0]['cod_sector'];
		  $cod_programa                        =         $partida[0]['cod_programa'];
		  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
		  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
		  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
		  $cod_partida                         =         $partida[0]['cod_partida'];
		  $cod_generica                        =         $partida[0]['cod_generica'];
		  $cod_especifica                      =         $partida[0]['cod_especifica'];
		  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
		  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
		  $monto                               =         $partida[0]['monto'];
		  //$numero_control_compromiso           =         $partida[0]['numero_control_compromiso'];
		  $concepto = $partida[0]['concepto'];
	      $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
	      $to = 1;
	      $td = 3;
	      $ta = 6;
	      $mt = $monto;
	      $ccp = str_replace("'","",$concepto);
	      $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, $fecha_documento, $mt, $ccp,$ano,$numero_documento,null, null, null,null, null, null,null, null,null, $num_base, $j);
          if($dnco){
            $suma = count($partidas);
            $numero_causado= $this->cfpd22_numero_asiento_causado->field('cfpd22_numero_asiento_causado.numero_causado', $conditions =$this->SQLCA_noDEP()." and ano_causado='$ano'", $order =null);
            if(!empty($numero_causado)){
              $num_base = $numero_causado;
              $numero_causado += $suma;
              $sql_numero_causado = "UPDATE cfpd22_numero_asiento_causado SET numero_causado='$numero_causado' WHERE ano_causado='$ano' and ".$this->SQLCA_noDEP().";";
            }else{
              $num_base = 1;
              $numero_causado = $num_base+$suma;
              $sql_numero_causado = "INSERT INTO cfpd22_numero_asiento_causado VALUES('1', '11', '30', '11', '$ano', '$numero_causado');";
            }//fin if
            $sw_numero_causado = $this->cfpd22_numero_asiento_causado->query($sql_numero_causado);
            if($sw_numero_causado > 1){
              $rnco = $nda = $dnco;
              $to = 1;
              $td = 4;
              $ta = 9;
              $dnca = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp='', $ano, $numero_documento, $nda, null, null, null, null, null, null, $rnco, null, $num_base, $j);

              if($dnca){
                $to = 1;
                $td = 5;
                $ta = 3;
                $numero_control_pagado = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp,$ano,$ndo=null,$nda=null, $opago=null, $opfecha=$fd, $cbanco=null, $ccuenta=null, $ccheque=$numero_documento, $fechache=$fd, $dnco, $dnca, $num_base, $j, null);
                if($numero_control_pagado != false){
                  $sql_up_notadebito_partidas = "UPDATE cfpd30_rendiciones_partidas SET numero_control_compromiso=$dnco , numero_control_causado=$dnca ,numero_control_pagado=$numero_control_pagado WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and ano_rendicion=$ano_documento and numero_rendicion=$numero_documento and ano=$ano and cod_sector=$cod_sector and cod_programa=$cod_programa and cod_sub_prog=$cod_sub_prog and cod_proyecto=$cod_proyecto and cod_activ_obra=$cod_activ_obra and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_sub_espec and cod_auxiliar=$cod_auxiliar";
                  $sw4 = $this->cepd01_compromiso_cuerpo->execute($sql_up_notadebito_partidas);
                 // echo "<br>".$j;
                }
              }//dnca
            }//sw
          }//dnco
          $j++;
        }//fin del foreach
      }
    $this->reactualizacion_anular_rendiciones();
}//FIN RENDIONES

function reactualizacion_anular_rendiciones(){
  echo "anular_rendiones";
  $partidas=$this->cepd01_compromiso_cuerpo->execute("SELECT * FROM cfpd30_rendiciones_partidas a,cfpd30_rendiciones_cuerpo b
WHERE
a.cod_presi=b.cod_presi and
a.cod_entidad=b.cod_entidad and
a.cod_tipo_inst=b.cod_tipo_inst and
a.cod_inst=b.cod_inst and
a.cod_dep=b.cod_dep and
a.ano_rendicion=b.ano_rendicion and
a.numero_rendicion=b.numero_rendicion and b.condicion_actividad=2");
             //$camposT2="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_documento,numero_documento,cod_tipo_compromiso,fecha_documento,tipo_recurso,rif,cedula_identidad,cod_dir_superior,cod_coordinacion,cod_secretaria,cod_direccion,concepto,monto,condicion_actividad,dia_asiento_registro,mes_asiento_registro,ano_asiento_registro, numero_asiento_registro,username_registro,ano_anulacion,numero_anulacion,dia_asiento_anulacion,mes_asiento_anulacion,ano_asiento_anulacion,numero_asiento_anulacion,username_anulacion,ano_orden_pago,numero_orden_pago,beneficiario,condicion_juridica,fecha_proceso_registro,fecha_proceso_anulacion";
			 //$camposT3="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_documento,numero_documento,ano,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,monto,numero_control_compromiso";
			 $j =0;
			 $ano=2008;
      $suma = count($partidas);

	  foreach($partidas as $partida){
		  $cod_presi                           =         $partida[0]['cod_presi'];
		  $cod_entidad                         =         $partida[0]['cod_entidad'];
		  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
		  $cod_inst                            =         $partida[0]['cod_inst'];
		  $cod_dep                             =         $partida[0]['cod_dep'];
		  $this->Session->write('SScodpresi', $cod_presi);
		  $this->Session->write('SScodentidad', $cod_entidad);
		  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
		  $this->Session->write('SScodinst', $cod_inst);
		  $this->Session->write('SScoddep', $cod_dep);
		  $ano_documento                       =         $partida[0]['ano_rendicion'];
				  $fr                      =         $partida[0]['fecha_proceso_registro'];
				  $fecha_registro=$fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
				  $fd                                  =         $partida[0]['fecha_rendicion'];
				  $ano_fd=$fd[0].$fd[1].$fd[2].$fd[3];
				  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
				  $ano_fd= (int) $ano_fd;
				  $ano_fdpartida=(int) $partida[0]['ano'];
				  if($ano_fd!=$ano_fdpartida){
				     $fecha_documento=$fecha_registro;
				     $fd=$fecha_registro;
				  }else{
				  	$fecha_documento=$fd;
				  }

		  $numero_documento                    =         $partida[0]['numero_rendicion'];
		  $ano                                 =         $partida[0]['ano'];
		  $cod_sector                          =         $partida[0]['cod_sector'];
		  $cod_programa                        =         $partida[0]['cod_programa'];
		  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
		  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
		  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
		  $cod_partida                         =         $partida[0]['cod_partida'];
		  $cod_generica                        =         $partida[0]['cod_generica'];
		  $cod_especifica                      =         $partida[0]['cod_especifica'];
		  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
		  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
		  $monto                               =         $partida[0]['monto'];
		  $numero_control_compromiso = $partida[0]['numero_control_compromiso'];
		  $numero_control_causado = $partida[0]['numero_control_causado'];
		  $numero_control_pagado = $partida[0]['numero_control_pagado'];
           $numero_anulacion           =         $partida[0]['numero_acta_anulacion'];
           $concepto = $partida[0]['concepto'];
           $busca_concepto_anulacion=$this->cugd03_acta_anulacion_cuerpo->findAll("cod_dep='".$cod_dep."' and numero_acta_anulacion='".$numero_anulacion."'");
           $concepto_anulacion= $busca_concepto_anulacion[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"];
          // echo "<br>".$concepto_anulacion;

           $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);

	          $num_asiento_compromiso = $this->motor_presupuestario($cp,2, 3, 5, $fd, $monto, str_replace("'","",$concepto_anulacion), $ano, $numero_documento, $numero_control_compromiso, null, null, null, null, null, null, null, null, null, null);
	          if($num_asiento_compromiso){
	            //echo "anulo compromiso<br/>";
	            $num_asiento_causado = $this->motor_presupuestario($cp,2, 4, 8, $fd, $monto, str_replace("'","",$concepto_anulacion), $ano, $numero_documento, $numero_documento, null, null, null, null, null, null,$numero_control_compromiso, $numero_control_causado, null, null);
	            if($num_asiento_causado){
	              //echo "anulo causado<br/>";
	              $numero_control_pagado = $this->motor_presupuestario($cp, 2, 5, 2, $fd, $monto, str_replace("'","",$concepto_anulacion), $ano, $numero_orden_compra=null, $numero_orden_compra_autorizacion_pagos=null, $numero_documento, $opfecha=$fd, $cod_entidad_bancaria=0, $cuenta_bancaria=0, $numero_documento, $fechache=$fd,$numero_control_compromiso, $numero_control_causado, $numero_control_pagado, null,null);
	              if($numero_control_pagado){
	                //echo "anulo pagado<br/>";
	              }
	            }
	          }

	      $j++;
        }//fin del foreach

}//FIN anulacion RENDIONES


##
# REINTEGROS
##

###COMPROMISO CUASADO PAGADO PRECOMPROMISO: Reintegros

function reactualizacion_reintegro(){
  echo "reintegro";
  $partidas=$this->cepd01_compromiso_cuerpo->execute("SELECT * FROM cfpd30_reintegro_partidas a,cfpd30_reintegro_cuerpo b
WHERE
a.cod_presi=b.cod_presi and
a.cod_entidad=b.cod_entidad and
a.cod_tipo_inst=b.cod_tipo_inst and
a.cod_inst=b.cod_inst and
a.cod_dep=b.cod_dep and
a.ano_reintegro=b.ano_reintegro and
a.numero_reintegro=b.numero_reintegro");
			 $j =0;
			 $ano=2008;
             $suma = count($partidas);

		    $numero_compromiso= $this->cfpd21_numero_asiento_compromiso->field('cfpd21_numero_asiento_compromiso.numero_compromiso', $conditions = $this->condicionNDEP()." and ano_compromiso='$ano'", $order =null);
		    if(!empty($numero_compromiso)){
		      $num_base = $numero_compromiso;
		      $numero_compromiso += $suma;
		      $sql_numero_compromiso = "UPDATE cfpd21_numero_asiento_compromiso SET numero_compromiso='$numero_compromiso' WHERE ano_compromiso='$ano' and ".$this->condicionNDEP().";";
		    }else{
		      $num_base = 1;
		      $numero_compromiso = $num_base + $suma;
		      $sql_numero_compromiso = "INSERT INTO cfpd21_numero_asiento_compromiso VALUES('1', '11', '30', '11', '$ano', '$numero_compromiso');";
		    }
		    $sw_numero_compromiso = $this->cfpd21_numero_asiento_compromiso->query($sql_numero_compromiso);
      if($sw_numero_compromiso > 1){
	  foreach($partidas as $partida){
		  $cod_presi                           =         $partida[0]['cod_presi'];
		  $cod_entidad                         =         $partida[0]['cod_entidad'];
		  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
		  $cod_inst                            =         $partida[0]['cod_inst'];
		  $cod_dep                             =         $partida[0]['cod_dep'];
		  $this->Session->write('SScodpresi', $cod_presi);
		  $this->Session->write('SScodentidad', $cod_entidad);
		  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
		  $this->Session->write('SScodinst', $cod_inst);
		  $this->Session->write('SScoddep', $cod_dep);
		  $ano_documento                       =         $partida[0]['ano_reintegro'];
				  $fr                      =         $partida[0]['fecha_proceso_registro'];
				  $fecha_registro=$fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
				  $fd                                  =         $partida[0]['fecha_reintegro'];
				  $ano_fd=$fd[0].$fd[1].$fd[2].$fd[3];
				  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
				  $ano_fd= (int) $ano_fd;
				  $ano_fdpartida=(int) $partida[0]['ano'];
				  if($ano_fd!=$ano_fdpartida){
				     $fecha_documento=$fecha_registro;
				     $fd=$fecha_registro;
				  }else{
				  	$fecha_documento=$fd;
				  }

		  $numero_documento                    =         $partida[0]['numero_reintegro'];
		  $ano                                 =         $partida[0]['ano'];
		  $cod_sector                          =         $partida[0]['cod_sector'];
		  $cod_programa                        =         $partida[0]['cod_programa'];
		  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
		  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
		  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
		  $cod_partida                         =         $partida[0]['cod_partida'];
		  $cod_generica                        =         $partida[0]['cod_generica'];
		  $cod_especifica                      =         $partida[0]['cod_especifica'];
		  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
		  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
		  $monto_pre                           =         $partida[0]['monto_pre_compromiso'];
		  $monto_com                           =         $partida[0]['monto_compromiso'];
		  $monto_cau                           =         $partida[0]['monto_causado'];
		  $monto_pag                           =         $partida[0]['monto_pagado'];
		  //$numero_control_compromiso           =         $partida[0]['numero_control_compromiso'];
		  $update_pre_compromiso = "UPDATE cfpd05 SET precompromiso_congelado=precompromiso_congelado - $monto_pre WHERE ".$this->condicion()." and ano='$ano' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar' ;";
          $sw_pre = $this->cfpd05->execute($update_pre_compromiso);
		  $concepto = $partida[0]['concepto'];
	      $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
	      $to = 1;
	      $td = 3;
	      $ta = 7;
	      //$mt = $monto_com;
	      $ccp = str_replace("'","",$concepto);
	      $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, $fecha_documento, $monto_com, $ccp,$ano,$numero_documento,null, null, null,null, null, null,null, null,null, $num_base, $j);

          if($dnco){
            $suma = count($partidas);
            $numero_causado= $this->cfpd22_numero_asiento_causado->field('cfpd22_numero_asiento_causado.numero_causado', $conditions =$this->SQLCA_noDEP()." and ano_causado='$ano'", $order =null);
            if(!empty($numero_causado)){
              $num_base = $numero_causado;
              $numero_causado += $suma;
              $sql_numero_causado = "UPDATE cfpd22_numero_asiento_causado SET numero_causado='$numero_causado' WHERE ano_causado='$ano' and ".$this->SQLCA_noDEP().";";
            }else{
              $num_base = 1;
              $numero_causado = $num_base+$suma;
              $sql_numero_causado = "INSERT INTO cfpd22_numero_asiento_causado VALUES('1', '11', '30', '11', '$ano', '$numero_causado');";
            }//fin if
            $sw_numero_causado = $this->cfpd22_numero_asiento_causado->query($sql_numero_causado);
            if($sw_numero_causado > 1){
              $rnco = $nda = $dnco;
              $to = 1;
              $td = 4;
              $ta = 10;
              $dnca = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $monto_cau, $ccp='', $ano, $numero_documento, $nda, null, null, null, null, null, null, $rnco, null, $num_base, $j);
                $suma = count($partidas);
		        $numero_pagado= $this->cfpd23_numero_asiento_pagado->field('cfpd23_numero_asiento_pagado.numero_pagado', "cod_presi=1 and cod_entidad=11 and cod_tipo_inst=30 and cod_inst=11 and ano_pagado='$ano'", $order =null);
		        if(!empty($numero_pagado)){
		          $num_base = $numero_pagado;
		          $numero_pagado += $suma ;
		          $sql_numero_pagado = "UPDATE cfpd23_numero_asiento_pagado SET numero_pagado='$numero_pagado' WHERE ano_pagado='$ano' and cod_presi=1 and cod_entidad=11 and cod_tipo_inst=30 and cod_inst=11;";
		        }else{
		          $num_base = 1;
		          $numero_pagado = $num_base;
		          $sql_numero_pagado = "INSERT INTO cfpd23_numero_asiento_pagado VALUES('1', '11', '30', '11', '$ano', '$numero_pagado');";
		        }
		       $sw2 = $this->cfpd23_numero_asiento_pagado->query($sql_numero_pagado);
		       if($sw2 > 1){
	              if($dnca){
	                $to = 1;
	                $td = 5;
	                $ta = 4;
	                $numero_control_pagado = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $monto_pag, $ccp,$ano,$ndo=null,$nda=null, $opago=null, $opfecha=$fd, $cbanco=null, $ccuenta=null, $ccheque=$numero_documento, $fechache=$fd, $dnco, $dnca, $num_base, $j, null);
	                if($numero_control_pagado != false){
	                 // $sql_up_notadebito_partidas = "UPDATE cfpd30_reintegro_partidas SET numero_control_compromiso=$dnco , numero_control_causado=$dnca ,numero_control_pagado=$numero_control_pagado WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and ano_rendicion=$ano_documento and numero_rendicion=$numero_documento and ano=$ano and cod_sector=$cod_sector and cod_programa=$cod_programa and cod_sub_prog=$cod_sub_prog and cod_proyecto=$cod_proyecto and cod_activ_obra=$cod_activ_obra and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_sub_espec and cod_auxiliar=$cod_auxiliar";
	                  //$sw4 = $this->cepd01_compromiso_cuerpo->execute($sql_up_notadebito_partidas);
	                  //echo "<br>".$j;
	                }
	              }//dnca
		       }//sw2
            }//sw
          }//dnco
          $j++;
        }//fin del foreach
      }
   $this->reactualizacion_anular_reintegro();
}//FIN reintegro

function reactualizacion_anular_reintegro(){
  echo "anular_rendiones";
  $partidas=$this->cepd01_compromiso_cuerpo->execute("SELECT * FROM cfpd30_reintegro_partidas a,cfpd30_reintegro_cuerpo b
WHERE
a.cod_presi=b.cod_presi and
a.cod_entidad=b.cod_entidad and
a.cod_tipo_inst=b.cod_tipo_inst and
a.cod_inst=b.cod_inst and
a.cod_dep=b.cod_dep and
a.ano_reintegro=b.ano_reintegro and
a.numero_reintegro=b.numero_reintegro and b.condicion_actividad=2");
             //$camposT2="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_documento,numero_documento,cod_tipo_compromiso,fecha_documento,tipo_recurso,rif,cedula_identidad,cod_dir_superior,cod_coordinacion,cod_secretaria,cod_direccion,concepto,monto,condicion_actividad,dia_asiento_registro,mes_asiento_registro,ano_asiento_registro, numero_asiento_registro,username_registro,ano_anulacion,numero_anulacion,dia_asiento_anulacion,mes_asiento_anulacion,ano_asiento_anulacion,numero_asiento_anulacion,username_anulacion,ano_orden_pago,numero_orden_pago,beneficiario,condicion_juridica,fecha_proceso_registro,fecha_proceso_anulacion";
			 //$camposT3="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_documento,numero_documento,ano,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,monto,numero_control_compromiso";
			 $j =0;
			 $ano=2008;
      $suma = count($partidas);

	  foreach($partidas as $partida){
		  $cod_presi                           =         $partida[0]['cod_presi'];
		  $cod_entidad                         =         $partida[0]['cod_entidad'];
		  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
		  $cod_inst                            =         $partida[0]['cod_inst'];
		  $cod_dep                             =         $partida[0]['cod_dep'];
		  $this->Session->write('SScodpresi', $cod_presi);
		  $this->Session->write('SScodentidad', $cod_entidad);
		  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
		  $this->Session->write('SScodinst', $cod_inst);
		  $this->Session->write('SScoddep', $cod_dep);
		  $ano_documento                       =         $partida[0]['ano_reintegro'];
				  $fr                      =         $partida[0]['fecha_proceso_registro'];
				  $fecha_registro=$fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
				  $fd                                  =         $partida[0]['fecha_reintegro'];
				  $ano_fd=$fd[0].$fd[1].$fd[2].$fd[3];
				  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
				  $ano_fd= (int) $ano_fd;
				  $ano_fdpartida=(int) $partida[0]['ano'];
				  if($ano_fd!=$ano_fdpartida){
				     $fecha_documento=$fecha_registro;
				     $fd=$fecha_registro;
				  }else{
				  	$fecha_documento=$fd;
				  }

		  $numero_documento                    =         $partida[0]['numero_reintegro'];
		  $ano                                 =         $partida[0]['ano'];
		  $cod_sector                          =         $partida[0]['cod_sector'];
		  $cod_programa                        =         $partida[0]['cod_programa'];
		  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
		  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
		  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
		  $cod_partida                         =         $partida[0]['cod_partida'];
		  $cod_generica                        =         $partida[0]['cod_generica'];
		  $cod_especifica                      =         $partida[0]['cod_especifica'];
		  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
		  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
		  $monto                               =         $partida[0]['monto'];
		  $numero_control_compromiso = 0;
		  $numero_control_causado = 0;
		  $numero_control_pagado = 0;
           $numero_anulacion           =         $partida[0]['numero_acta_anulacion'];
           $concepto = $partida[0]['concepto'];
            $update_pre_compromiso = "UPDATE cfpd05 SET precompromiso_congelado=precompromiso_congelado + $monto_pre WHERE ".$this->condicion()." and ano='$ano' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog' and cod_proyecto='$cod_proyecto' and cod_activ_obra='$cod_activ_obra' and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar' ;";
            $sw_pre = $this->cfpd05->execute($update_pre_compromiso);
           $busca_concepto_anulacion=$this->cugd03_acta_anulacion_cuerpo->findAll("cod_dep='".$cod_dep."' and numero_acta_anulacion='".$numero_anulacion."'");
           $concepto_anulacion= $busca_concepto_anulacion[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"];
          // echo "<br>".$concepto_anulacion;

           $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);

	          $num_asiento_compromiso = $this->motor_presupuestario($cp,2, 3, 7, $fd, $monto, str_replace("'","",$concepto_anulacion), $ano, $numero_documento, $numero_control_compromiso, null, null, null, null, null, null, null, null, null, null);
	          if($num_asiento_compromiso){
	            //echo "anulo compromiso<br/>";
	            $num_asiento_causado = $this->motor_presupuestario($cp,2, 4, 10, $fd, $monto, str_replace("'","",$concepto_anulacion), $ano, $numero_documento, $numero_documento, null, null, null, null, null, null,$numero_control_compromiso, $numero_control_causado, null, null);
	            if($num_asiento_causado){
	              //echo "anulo causado<br/>";
	              $numero_control_pagado = $this->motor_presupuestario($cp, 2, 5, 4, $fd, $monto, str_replace("'","",$concepto_anulacion), $ano, $numero_orden_compra=null, $numero_orden_compra_autorizacion_pagos=null, $numero_documento, $opfecha=$fd, $cod_entidad_bancaria=0, $cuenta_bancaria=0, $numero_documento, $fechache=$fd,$numero_control_compromiso, $numero_control_causado, $numero_control_pagado, null,null);
	              if($numero_control_pagado){
	                //echo "anulo pagado<br/>";
	              }
	            }
	          }

	      $j++;
        }//fin del foreach

}//FIN anulacion REINTEGRO


#######
####
#######NOTA DE DEBITO ESPECIALES

function reactualizacion_nota_debito(){
  echo "Nota de debito especiales, compromiso - causado - pagado";
  $partidas=$this->cepd01_compromiso_cuerpo->execute("SELECT a.*,b.numero_orden_pago,b.fecha_nota_debito,b.fecha_proceso_registro,b.concepto FROM cstd09_notadebito_especial_partidas a,cstd09_notadebito_especial_cuerpo b
WHERE
a.cod_presi=b.cod_presi and
a.cod_entidad=b.cod_entidad and
a.cod_tipo_inst=b.cod_tipo_inst and
a.cod_inst=b.cod_inst and
a.cod_dep=b.cod_dep and
a.ano_movimiento=b.ano_movimiento and
a.cod_entidad_bancaria=b.cod_entidad_bancaria and
a.cod_sucursal=b.cod_sucursal and
a.cuenta_bancaria=b.cuenta_bancaria and
a.tipo_documento=b.tipo_documento and
a.numero_documento=b.numero_documento and b.numero_orden_pago=0");
             //$camposT2="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_documento,numero_documento,cod_tipo_compromiso,fecha_documento,tipo_recurso,rif,cedula_identidad,cod_dir_superior,cod_coordinacion,cod_secretaria,cod_direccion,concepto,monto,condicion_actividad,dia_asiento_registro,mes_asiento_registro,ano_asiento_registro, numero_asiento_registro,username_registro,ano_anulacion,numero_anulacion,dia_asiento_anulacion,mes_asiento_anulacion,ano_asiento_anulacion,numero_asiento_anulacion,username_anulacion,ano_orden_pago,numero_orden_pago,beneficiario,condicion_juridica,fecha_proceso_registro,fecha_proceso_anulacion";
			 //$camposT3="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano_documento,numero_documento,ano,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,monto,numero_control_compromiso";
			 $j =0;
			 $ano=2008;
      $suma = count($partidas);
      $numero_compromiso= $this->cfpd21_numero_asiento_compromiso->field('cfpd21_numero_asiento_compromiso.numero_compromiso', "cod_presi=1 and cod_entidad=11 and cod_tipo_inst=30 and cod_inst=11 and ano_compromiso='$ano'", $order =null);
      if(!empty($numero_compromiso)){
        $num_base = $numero_compromiso;
        $numero_compromiso += $suma;
        $sql_numero_compromiso = "UPDATE cfpd21_numero_asiento_compromiso SET numero_compromiso='$numero_compromiso' WHERE ano_compromiso='$ano' and cod_presi=1 and cod_entidad=11 and cod_tipo_inst=30 and cod_inst=11;";
      }else{
        $num_base = 1;
        $numero_compromiso = $num_base + $suma;
        $sql_numero_compromiso = "INSERT INTO cfpd21_numero_asiento_compromiso VALUES('1', '11', '30', '11', '$ano', '$numero_compromiso');";
      }
      $sw_numero_compromiso = $this->cfpd21_numero_asiento_compromiso->query($sql_numero_compromiso);
      if($sw_numero_compromiso > 1){
	  foreach($partidas as $partida){
		  $cod_presi                           =         $partida[0]['cod_presi'];
		  $cod_entidad                         =         $partida[0]['cod_entidad'];
		  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
		  $cod_inst                            =         $partida[0]['cod_inst'];
		  $cod_dep                             =         $partida[0]['cod_dep'];
		  $this->Session->write('SScodpresi', $cod_presi);
		  $this->Session->write('SScodentidad', $cod_entidad);
		  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
		  $this->Session->write('SScodinst', $cod_inst);
		  $this->Session->write('SScoddep', $cod_dep);
		  $ano_documento                       =         $partida[0]['ano_movimiento'];
				  $fr                      =         $partida[0]['fecha_proceso_registro'];
				  $fecha_registro=$fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
				  $fd                                  =         $partida[0]['fecha_nota_debito'];
				  $ano_fd=$fd[0].$fd[1].$fd[2].$fd[3];
				  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
				  $ano_fd= (int) $ano_fd;
				  $ano_fdpartida=(int) $partida[0]['ano'];
				  if($ano_fd!=$ano_fdpartida){
				     $fecha_documento=$fecha_registro;
				     $fd=$fecha_registro;
				  }else{
				  	$fecha_documento=$fd;
				  }

		  $cod_entidad_bancaria                =         $partida[0]['cod_entidad_bancaria'];
		  $cod_sucursal                        =         $partida[0]['cod_sucursal'];
		  $cuenta_bancaria                     =         $partida[0]['cuenta_bancaria'];
		  $tipo_documento                      =         $partida[0]['tipo_documento'];
		  $numero_documento                    =         $partida[0]['numero_documento'];
		  $ano                                 =         $partida[0]['ano'];
		  $cod_sector                          =         $partida[0]['cod_sector'];
		  $cod_programa                        =         $partida[0]['cod_programa'];
		  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
		  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
		  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
		  $cod_partida                         =         $partida[0]['cod_partida'];
		  $cod_generica                        =         $partida[0]['cod_generica'];
		  $cod_especifica                      =         $partida[0]['cod_especifica'];
		  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
		  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
		  $monto                               =         $partida[0]['monto'];
		  $numero_control_compromiso           =         $partida[0]['numero_control_compromiso'];
		  $numero_control_causado              =         $partida[0]['numero_control_causado'];
		  $numero_control_compromiso           =         $partida[0]['numero_control_pagado'];
		  $numero_orden_pago                   =         $partida[0]['numero_orden_pago'];
		  //$numero_control_compromiso           =         $partida[0]['numero_control_compromiso'];
		  $concepto = $partida[0]['concepto'];
	      $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
	      $to = 1;
	      $td = 3;
	      $ta = 5;
	      $mt = $monto;
	      $ccp = str_replace("'","",$concepto);
	      $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, $fecha_documento, $mt, $ccp,$ano,$numero_documento,null, null, null,null, null, null,null, null,null, $num_base, $j);
          //$numero_control_pagado = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp,$ano,$ndo=null,$nda=null, $opago=$numero_orden_pago, $opfecha=$fd, $cbanco=$cod_entidad_bancaria, $ccuenta=$cuenta_bancaria, $ccheque=$numero_documento, $fechache=$fd, $numero_control_compromiso, $numero_control_causado, $num_base, $j, null);
          if($dnco){
            $suma = count($partidas);
            $numero_causado= $this->cfpd22_numero_asiento_causado->field('cfpd22_numero_asiento_causado.numero_causado', $conditions =$this->SQLCA_noDEP()." and ano_causado='$ano'", $order =null);
            if(!empty($numero_causado)){
              $num_base = $numero_causado;
              $numero_causado += $suma;
              $sql_numero_causado = "UPDATE cfpd22_numero_asiento_causado SET numero_causado='$numero_causado' WHERE ano_causado='$ano' and ".$this->SQLCA_noDEP().";";
            }else{
              $num_base = 1;
              $numero_causado = $num_base+$suma;
              $sql_numero_causado = "INSERT INTO cfpd22_numero_asiento_causado VALUES('1', '11', '30', '11', '$ano', '$numero_causado');";
            }//fin if
            $sw_numero_causado = $this->cfpd22_numero_asiento_causado->query($sql_numero_causado);
            if($sw_numero_causado > 1){
              $rnco = $nda = $dnco;
              $to = 1;
              $td = 4;
              $ta = 8;
              $dnca = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp='', $ano, $numero_documento, $nda, null, null, null, null, null, null, $rnco, null, $num_base, $j);
                $suma = count($partidas);
		        $numero_pagado= $this->cfpd23_numero_asiento_pagado->field('cfpd23_numero_asiento_pagado.numero_pagado', "cod_presi=1 and cod_entidad=11 and cod_tipo_inst=30 and cod_inst=11 and ano_pagado='$ano'", $order =null);
		        if(!empty($numero_pagado)){
		          $num_base = $numero_pagado;
		          $numero_pagado += $suma ;
		          $sql_numero_pagado = "UPDATE cfpd23_numero_asiento_pagado SET numero_pagado='$numero_pagado' WHERE ano_pagado='$ano' and cod_presi=1 and cod_entidad=11 and cod_tipo_inst=30 and cod_inst=11;";
		        }else{
		          $num_base = 1;
		          $numero_pagado = $num_base;
		          $sql_numero_pagado = "INSERT INTO cfpd23_numero_asiento_pagado VALUES('1', '11', '30', '11', '$ano', '$numero_pagado');";
		        }
		       $sw2 = $this->cfpd23_numero_asiento_pagado->query($sql_numero_pagado);
		       if($sw2 > 1){
	              if($dnca){
	                $to = 1;
	                $td = 5;
	                $ta = 2;
	                $numero_control_pagado = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp,$ano,$ndo=null,$nda=null, $opago=$numero_orden_pago, $opfecha=$fd, $cbanco=$cod_entidad_bancaria, $ccuenta=$cuenta_bancaria, $ccheque=$numero_documento, $fechache=$fd, $dnco, $dnca, $num_base, $j, null);
	                //$numero_control_pagado = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp,$ano,$ndo=null,$nda=null, $opago=null, $opfecha=$fd, $cbanco=null, $ccuenta=null, $ccheque=$numero_documento, $fechache=$fd, $dnco, $dnca, $num_base, $j, null);
	                if($numero_control_pagado != false){
	                  $sql_up_notadebito_partidas = "UPDATE cstd09_notadebito_especial_partidas SET numero_control_compromiso=$dnco , numero_control_causado=$dnca ,numero_control_pagado=$numero_control_pagado WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and ano_movimiento=$ano_documento and cod_entidad_bancaria=$cod_entidad_bancaria and cod_sucursal=$cod_sucursal and cuenta_bancaria=$cuenta_bancaria and tipo_documento=$tipo_documento and numero_documento=$numero_documento and ano=$ano and cod_sector=$cod_sector and cod_programa=$cod_programa and cod_sub_prog=$cod_sub_prog and cod_proyecto=$cod_proyecto and cod_activ_obra=$cod_activ_obra and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_sub_espec and cod_auxiliar=$cod_auxiliar";
	                  $sw4 = $this->cepd01_compromiso_cuerpo->execute($sql_up_notadebito_partidas);
	                  //echo "<br>".$j;
	                }
	              }//dnca
		       }
            }//sw
          }//dnco
          $j++;
        }//fin del foreach
      }

      //reactualizacion_anular_nota_debito();
      //al parecer no se hace anulacion hay que preguntar OJO

      $this->reactualizacion_nota_debito2();
}//FIN nota_debito

function reactualizacion_nota_debito2(){
  echo "<br>Nota de debito especiales - Pagado";
  $partidas=$this->cepd01_compromiso_cuerpo->execute("SELECT a.*,b.numero_orden_pago,b.fecha_nota_debito,b.fecha_proceso_registro,b.concepto FROM cstd09_notadebito_especial_partidas a,cstd09_notadebito_especial_cuerpo b
WHERE
a.cod_presi=b.cod_presi and
a.cod_entidad=b.cod_entidad and
a.cod_tipo_inst=b.cod_tipo_inst and
a.cod_inst=b.cod_inst and
a.cod_dep=b.cod_dep and
a.ano_movimiento=b.ano_movimiento and
a.cod_entidad_bancaria=b.cod_entidad_bancaria and
a.cod_sucursal=b.cod_sucursal and
a.cuenta_bancaria=b.cuenta_bancaria and
a.tipo_documento=b.tipo_documento and
a.numero_documento=b.numero_documento and b.numero_orden_pago!=0");
			 $j =0;
			 $ano=2008;
                $suma = count($partidas);
		        $numero_pagado= $this->cfpd23_numero_asiento_pagado->field('cfpd23_numero_asiento_pagado.numero_pagado', "cod_presi=1 and cod_entidad=11 and cod_tipo_inst=30 and cod_inst=11 and ano_pagado='$ano'", $order =null);
		        if(!empty($numero_pagado)){
		          $num_base = $numero_pagado;
		          $numero_pagado += $suma ;
		          $sql_numero_pagado = "UPDATE cfpd23_numero_asiento_pagado SET numero_pagado='$numero_pagado' WHERE ano_pagado='$ano' and cod_presi=1 and cod_entidad=11 and cod_tipo_inst=30 and cod_inst=11;";
		        }else{
		          $num_base = 1;
		          $numero_pagado = $num_base;
		          $sql_numero_pagado = "INSERT INTO cfpd23_numero_asiento_pagado VALUES('1', '11', '30', '11', '$ano', '$numero_pagado');";
		        }
		      $sw2 = $this->cfpd23_numero_asiento_pagado->query($sql_numero_pagado);
      if($sw2 > 1){
	  foreach($partidas as $partida){
		  $cod_presi                           =         $partida[0]['cod_presi'];
		  $cod_entidad                         =         $partida[0]['cod_entidad'];
		  $cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
		  $cod_inst                            =         $partida[0]['cod_inst'];
		  $cod_dep                             =         $partida[0]['cod_dep'];
		  $this->Session->write('SScodpresi', $cod_presi);
		  $this->Session->write('SScodentidad', $cod_entidad);
		  $this->Session->write('SScodtipoinst', $cod_tipo_inst);
		  $this->Session->write('SScodinst', $cod_inst);
		  $this->Session->write('SScoddep', $cod_dep);
		  $ano_documento                       =         $partida[0]['ano_movimiento'];
				  $fr                      =         $partida[0]['fecha_proceso_registro'];
				  $fecha_registro=$fr[8].$fr[9]."/".$fr[5].$fr[6]."/".$fr[0].$fr[1].$fr[2].$fr[3];
				  $fd                                  =         $partida[0]['fecha_nota_debito'];
				  $ano_fd=$fd[0].$fd[1].$fd[2].$fd[3];
				  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
				  $ano_fd= (int) $ano_fd;
				  $ano_fdpartida=(int) $partida[0]['ano'];
				  if($ano_fd!=$ano_fdpartida){
				     $fecha_documento=$fecha_registro;
				     $fd=$fecha_registro;
				  }else{
				  	$fecha_documento=$fd;
				  }

		  $cod_entidad_bancaria                =         $partida[0]['cod_entidad_bancaria'];
		  $cod_sucursal                        =         $partida[0]['cod_sucursal'];
		  $cuenta_bancaria                     =         $partida[0]['cuenta_bancaria'];
		  $tipo_documento                      =         $partida[0]['tipo_documento'];
		  $numero_documento                    =         $partida[0]['numero_documento'];
		  $ano                                 =         $partida[0]['ano'];
		  $cod_sector                          =         $partida[0]['cod_sector'];
		  $cod_programa                        =         $partida[0]['cod_programa'];
		  $cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
		  $cod_proyecto                        =         $partida[0]['cod_proyecto'];
		  $cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
		  $cod_partida                         =         $partida[0]['cod_partida'];
		  $cod_generica                        =         $partida[0]['cod_generica'];
		  $cod_especifica                      =         $partida[0]['cod_especifica'];
		  $cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
		  $cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
		  $monto                               =         $partida[0]['monto'];
		  $numero_control_compromiso           =         $partida[0]['numero_control_compromiso'];
		  $numero_control_causado              =         $partida[0]['numero_control_causado'];
		  $numero_control_compromiso           =         $partida[0]['numero_control_pagado'];
		  $numero_orden_pago                   =         $partida[0]['numero_orden_pago'];
		  //$numero_control_compromiso           =         $partida[0]['numero_control_compromiso'];
		  $concepto = $partida[0]['concepto'];
                $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
                $to = 1;
                $td = 5;
                $ta = 2;
                $numero_control_pagado = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $monto, str_replace("'","",$concepto),$ano,$ndo=null,$nda=null, $opago=$numero_orden_pago, $opfecha=$fd, $cbanco=$cod_entidad_bancaria, $ccuenta=$cuenta_bancaria, $ccheque=$numero_documento, $fechache=$fd, $numero_control_compromiso, $numero_control_causado, $num_base, $j, null);
                //$numero_control_pagado = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp,$ano,$ndo=null,$nda=null, $opago=null, $opfecha=$fd, $cbanco=null, $ccuenta=null, $ccheque=$numero_documento, $fechache=$fd, $dnco, $dnca, $num_base, $j, null);
                if($numero_control_pagado != false){
                  $sql_up_notadebito_partidas = "UPDATE cstd09_notadebito_especial_partidas SET numero_control_compromiso=$numero_control_compromiso , numero_control_causado=$numero_control_causado ,numero_control_pagado=$numero_control_pagado WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and ano_movimiento=$ano_documento and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and tipo_documento='$tipo_documento' and numero_documento='$numero_documento' and ano='$ano' and cod_sector=$cod_sector and cod_programa=$cod_programa and cod_sub_prog=$cod_sub_prog and cod_proyecto=$cod_proyecto and cod_activ_obra=$cod_activ_obra and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_sub_espec and cod_auxiliar=$cod_auxiliar";
                  $sw4 = $this->cepd01_compromiso_cuerpo->execute($sql_up_notadebito_partidas);
                  //echo "<br>".$j;
                }

          $j++;
        }//fin del foreach
      }

      //reactualizacion_anular_nota_debito();
      //al parecer no se hace anulacion hay que preguntar OJO
}//FIN nota_debito2

///
///
///
/****************************************************
 * REACTULIZACION ORDEN DE PAGO TODOS LOS DOCUMENTOS
 *
 **************************************************/

function reactualizacion_ordenpago_todo(){

  $partidas=$this->cepd03_ordenpago_cuerpo->execute("select a.*,b.* from cepd03_ordenpago_partidas a,cepd03_ordenpago_cuerpo b where
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_orden_pago = b.ano_orden_pago and
  a.numero_orden_pago = b.numero_orden_pago");
  $i=1;
  $j =0;
  $suma = count($partidas);
  $numero_causado= $this->cfpd22_numero_asiento_causado->field('cfpd22_numero_asiento_causado.numero_causado'," ano_causado='2008'", $order =null);
  if(!empty($numero_causado)){
	$num_base = $numero_causado;
	$numero_causado += $suma;
	$sql_numero_causado = "UPDATE cfpd22_numero_asiento_causado SET numero_causado='$numero_causado' WHERE ano_causado='$ann';COMMIT;";
  }else{
	$num_base = 1;
	$numero_causado = $num_base+$suma;
	$sql_numero_causado = "INSERT INTO cfpd22_numero_asiento_causado VALUES('1', '11', '30', '11', '2008', '$numero_causado')";
  }
  $sw_numero_causado = $this->cfpd22_numero_asiento_causado->query($sql_numero_causado);

  foreach($partidas as $partida){
  	$cod_presi                           =         $partida[0]['cod_presi'];
  	$cod_entidad                         =         $partida[0]['cod_entidad'];
  	$cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
  	$cod_inst                            =         $partida[0]['cod_inst'];
  	$cod_dep                             =         $partida[0]['cod_dep'];
  	$this->Session->write('SScodpresi', $cod_presi);
  	$this->Session->write('SScodentidad', $cod_entidad);
  	$this->Session->write('SScodtipoinst', $cod_tipo_inst);
  	$this->Session->write('SScodinst', $cod_inst);
  	$this->Session->write('SScoddep', $cod_dep);
  	$ano_orden_pago                      =         $partida[0]['ano_orden_pago'];
  	$foo                                 =         $partida[0]['fecha_documento'];
  	$fd                                  =         $partida[0]['fecha_orden_pago'];
  	$fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
  	$fdoo=$foo[8].$foo[9]."/".$foo[5].$foo[6]."/".$foo[0].$foo[1].$foo[2].$foo[3];
  	$fecha_orden_pago=$foo;
  	$numero_orden_pago                   =         $partida[0]['numero_orden_pago'];
  	$numero_documento_origen             =         $partida[0]['numero_documento_origen'];
  	$numero_documento_adjunto            =         $partida[0]['numero_documento_adjunto'];
  	$ano                                 =         $partida[0]['ano'];
  	$cod_sector                          =         $partida[0]['cod_sector'];
  	$cod_programa                        =         $partida[0]['cod_programa'];
  	$cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
  	$cod_proyecto                        =         $partida[0]['cod_proyecto'];
  	$cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
  	$cod_partida                         =         $partida[0]['cod_partida'];
  	$cod_generica                        =         $partida[0]['cod_generica'];
  	$cod_especifica                      =         $partida[0]['cod_especifica'];
  	$cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
  	$cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
  	$monto                               =         $partida[0]['monto'];
  	$numero_control_compromiso           =         $partida[0]['numero_control_compromiso'];
  	$concepto = $partida[0]['concepto'];
    $numero_cheque = $partida[0]['numero_cheque'];

    switch($partida[0]['cod_tipo_documento']){
    	case 1: $ta=1;  break;//RC
    	case 2: $ta=2;  break;//OC anticipos
    	case 3: $ta=3;  break;//OC autorizacion
    	case 4: $ta=4;  break;//OB anticipos
    	case 5: $ta=5;  break;//OB valuaciones
    	case 6: $ta=12; break;//OB retenciones
    	case 7: $ta=6;  break;//SR anticipos
    	case 8: $ta=7;  break;//SR valuacion
    	case 9: $ta=11; break;//SR retencion
    }//fin switch

    $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
	$to = 1;
	$td = 4;
	$mt = $monto;
	$ndo = $numero_documento_origen;
	$nda = $numero_documento_adjunto;
    $ccp=str_replace("'","",$concepto);
	$rnco = $numero_control_compromiso;
	$dnca = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp, $ano, $ndo, $nda, $numero_orden_pago, $fdoo, null, null, null, null, $rnco, null, $num_base, $i);
	$ncc=$dnca;
	//echo " ".$ncc;
	$this->cepd03_ordenpago_partidas->execute("UPDATE cepd03_ordenpago_partidas SET numero_control_causado=".$ncc." WHERE ano_orden_pago=".$ano." and numero_orden_pago=".$numero_orden_pago."  and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
	///$cant=$this->cstd03_cheque_partidas->findCount();
	$this->cstd03_cheque_partidas->execute("UPDATE cstd03_cheque_partidas SET numero_control_compromiso=".$numero_control_compromiso.",numero_control_causado=".$ncc." WHERE ano_orden_pago=".$ano." and numero_orden_pago=".$numero_orden_pago." and numero_cheque=".$numero_cheque." and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");

    $i++;
    $j++;
 }//fin foreach
 $this->reactualizacion_ordenpago_anulado_todo();
}//fin reactualizacion orden pago completa

function reactualizacion_ordenpago_anulado_todo(){
  $partidas=$this->cepd03_ordenpago_cuerpo->execute("select a.*,b.* from cepd03_ordenpago_partidas a,cepd03_ordenpago_cuerpo b where
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_orden_pago = b.ano_orden_pago and
  a.numero_orden_pago = b.numero_orden_pago and
  b.condicion_actividad=2");
  $j=1;
  foreach($partidas as $partida){
  	$cod_presi                           =         $partida[0]['cod_presi'];
  	$cod_entidad                         =         $partida[0]['cod_entidad'];
  	$cod_tipo_inst                       =         $partida[0]['cod_tipo_inst'];
  	$cod_inst                            =         $partida[0]['cod_inst'];
  	$cod_dep                             =         $partida[0]['cod_dep'];
  	$this->Session->write('SScodpresi', $cod_presi);
  	$this->Session->write('SScodentidad', $cod_entidad);
  	$this->Session->write('SScodtipoinst', $cod_tipo_inst);
  	$this->Session->write('SScodinst', $cod_inst);
  	$this->Session->write('SScoddep', $cod_dep);
  	$ano_orden_pago                      =         $partida[0]['ano_orden_pago'];
  	$foo                                 =         $partida[0]['fecha_documento'];
  	$fd                                  =         $partida[0]['fecha_orden_pago'];
  	$fd   = $fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
  	$fdoo = $foo[8].$foo[9]."/".$foo[5].$foo[6]."/".$foo[0].$foo[1].$foo[2].$foo[3];
  	$fecha_orden_pago=$fdoo;
  	$opago                               =         $partida[0]['numero_orden_pago'];
  	$ndo                                 =         $partida[0]['numero_documento_origen'];
  	$nda                                 =         $partida[0]['numero_documento_adjunto'];
  	$ano                                 =         $partida[0]['ano'];
  	$cod_sector                          =         $partida[0]['cod_sector'];
  	$cod_programa                        =         $partida[0]['cod_programa'];
  	$cod_sub_prog                        =         $partida[0]['cod_sub_prog'];
  	$cod_proyecto                        =         $partida[0]['cod_proyecto'];
  	$cod_activ_obra                      =         $partida[0]['cod_activ_obra'];
  	$cod_partida                         =         $partida[0]['cod_partida'];
  	$cod_generica                        =         $partida[0]['cod_generica'];
  	$cod_especifica                      =         $partida[0]['cod_especifica'];
  	$cod_sub_espec                       =         $partida[0]['cod_sub_espec'];
  	$cod_auxiliar                        =         $partida[0]['cod_auxiliar'];
  	$monto                               =         $partida[0]['monto'];
  	$numero_control_compromiso           =         $partida[0]['numero_control_compromiso'];
  	$numero_control_causado              =         $partida[0]['numero_control_causado'];
  	$numero_anulacion                    =         $partida[0]['numero_anulacion'];
  	$concepto = $partida[0]['concepto'];

  	switch($partida[0]['cod_tipo_documento']){
    	case 1: $ta=1;  break;//RC
    	case 2: $ta=2;  break;//OC anticipos
    	case 3: $ta=3;  break;//OC autorizacion
    	case 4: $ta=4;  break;//OB anticipos
    	case 5: $ta=5;  break;//OB valuaciones
    	case 6: $ta=12; break;//OB retenciones
    	case 7: $ta=6;  break;//SR anticipos
    	case 8: $ta=7;  break;//SR valuacion
    	case 9: $ta=11; break;//SR retencion
    }//fin switch

   $busca_concepto_anulacion=$this->cugd03_acta_anulacion_cuerpo->findAll("cod_dep='".$cod_dep."' and numero_acta_anulacion='".$numero_anulacion."'");
   $concepto_anulacion= $busca_concepto_anulacion[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"];
   //print_r($busca_concepto_anulacion);
   //echo "<br>".$concepto_anulacion;
   $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
   $to   = 2;
   $td   = 4;
   $rnco =$numero_control_compromiso;
   $rnca =$numero_control_causado;
   $mt   = $monto;
   $ccp  = str_replace("'","",$concepto_anulacion);
   $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp,$ano,$ndo,$nda, $opago, $foo,null, null, null,null,$rnco, $rnca, null,null,null);
   $j++;
 }//fin foreach


}//function correr script ordenpago








}//fin class

?>