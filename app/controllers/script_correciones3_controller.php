<?php

class ScriptCorreciones3Controller extends AppController {



    var $name = "script_correciones3";
    var $uses    = array('v_inventario_inmuebles_todo', 'v_inventario_muebles_todo', 'cugd04', 'Usuario', 'cimd01_clasificacion_seccion',
                         'ccfd05_numero_asiento', 'ccfd04_cuentas_enlace', 'ccfd02', 'ccfd10_descripcion', 'ccfd10_detalles', 'cimd03_inventario_muebles',
                          'cimd03_inventario_numero', 'cimd03_inventario_inmuebles','cimd01_clasificacion_grupo','cimd01_clasificacion_subgrupo',
                         'ccfd04_cierre_mes', 'v_restaurar_causados_op', 'v_restaurar_compromisos', 'v_restaurar_pagados', 'arrd05');
    var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap');
    var $layout  = "script_correciones";




function reactualizacion_causado($var=null,$page=null){

        if ($page > 1) {
			$offset = ($page - 1) * 20000;
		}else{
			$offset = 0;
		}

        $PAGINAR="LIMIT 20000 OFFSET ".$offset;

set_time_limit(0);
$ano_select = YEAR_REACTUALIZACION;
$ann=YEAR_REACTUALIZACION;

  $partidas=$this->cepd03_ordenpago_cuerpo->execute("select a.*,b.* from cepd03_ordenpago_partidas a,cepd03_ordenpago_cuerpo b where
  a.cod_presi = b.cod_presi and
  a.cod_entidad = b.cod_entidad and
  a.cod_tipo_inst = b.cod_tipo_inst and
  a.cod_inst = b.cod_inst and
  a.cod_dep = b.cod_dep and
  a.ano_orden_pago = b.ano_orden_pago and
  a.numero_orden_pago = b.numero_orden_pago ".$PAGINAR);
  $i=1;
  $j =0;
  $suma = count($partidas);
  $numero_causado= $this->cfpd22_numero_asiento_causado->field('cfpd22_numero_asiento_causado.numero_causado'," cod_presi=1 and cod_entidad=11 and cod_tipo_inst=30 and cod_inst=11 and ano_causado='".YEAR_REACTUALIZACION."'", $order =null);
  if(!empty($numero_causado)){
	$num_base = $numero_causado;
	$numero_causado += $suma;
	$sql_numero_causado = "UPDATE cfpd22_numero_asiento_causado SET numero_causado='$numero_causado' WHERE  cod_presi=1 and cod_entidad=11 and cod_tipo_inst=30 and cod_inst=11 and ano_causado='$ann';";
  }else{
	$num_base = 1;
	$numero_causado = $num_base+$suma;
	$sql_numero_causado = "INSERT INTO cfpd22_numero_asiento_causado VALUES('1', '11', '30', '11', '".YEAR_REACTUALIZACION."', '$numero_causado')";
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
  	$cod_entidad_bancaria                =         $partida[0]['cod_entidad_bancaria'];
  	$cod_sucursal                        =         $partida[0]['cod_sucursal'];
  	$cuenta_bancaria                     =         $partida[0]['cuenta_bancaria'];
  	$concepto = $partida[0]['concepto'];
    $numero_cheque = $partida[0]['numero_cheque'];
    $ndo = $numero_documento_origen;
    switch($partida[0]['cod_tipo_documento']){
    	case 1: $ta=1;
    	//$numero_control_compromiso=$numero_control_compromiso;
    	$rs_ncc=$this->cepd03_ordenpago_partidas->execute("SELECT numero_control_compromiso FROM cepd01_compromiso_partidas WHERE numero_documento=".$ndo." and
    			 cod_presi=".$cod_presi." and
    			 cod_entidad=".$cod_entidad." and
    			 cod_tipo_inst=".$cod_tipo_inst." and
    			 cod_inst=".$cod_inst." and
    			 cod_dep=".$cod_dep."  and
    			 ano_documento=".$ano." and
    			 cod_sector=".$cod_sector." and
    			 cod_programa=".$cod_programa." and
    			 cod_sub_prog=".$cod_sub_prog." and
    			 cod_proyecto=".$cod_proyecto." and
    			 cod_activ_obra=".$cod_activ_obra." and
    			 cod_partida=".$cod_partida." and
    			 cod_generica=".$cod_generica." and
    			 cod_especifica=".$cod_especifica." and
    			 cod_sub_espec=".$cod_sub_espec." and
    			 cod_auxiliar=".$cod_auxiliar."");
    	$numero_control_compromiso=$rs_ncc[0][0]["numero_control_compromiso"];
    	break;//RC
    	case 2: $ta=2;
    	$rs_ncc=$this->cepd03_ordenpago_partidas->execute("SELECT numero_asiento_compromiso FROM cscd04_ordencompra_partidas WHERE numero_orden_compra=".$ndo." and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano_orden_compra=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."");
    	$numero_control_compromiso=$rs_ncc[0][0]["numero_asiento_compromiso"];
    	break;//OC anticipos
    	case 3: $ta=3;
    	$rs_ncc=$this->cepd03_ordenpago_partidas->execute("SELECT numero_asiento_compromiso FROM cscd04_ordencompra_partidas WHERE numero_orden_compra=".$ndo." and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano_orden_compra=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."");
    	$numero_control_compromiso=$rs_ncc[0][0]["numero_asiento_compromiso"];
    	break;//OC autorizacion
    	case 4: $ta=4;
    	$rs_ncc=$this->cepd03_ordenpago_partidas->execute("SELECT numero_control_compromiso FROM cobd01_contratoobras_partidas WHERE upper(numero_contrato_obra)=upper('".$ndo."') and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano_contrato_obra=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."");
    	$numero_control_compromiso=$rs_ncc[0][0]["numero_control_compromiso"];
    	break;//OB anticipos
    	case 5: $ta=5;
    	$rs_ncc=$this->cepd03_ordenpago_partidas->execute("SELECT numero_control_compromiso FROM cobd01_contratoobras_partidas WHERE upper(numero_contrato_obra)=upper('".$ndo."') and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano_contrato_obra=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."");
    	$numero_control_compromiso=$rs_ncc[0][0]["numero_control_compromiso"];
    	break;//OB valuaciones
    	case 6: $ta=12;
    	$rs_ncc=$this->cepd03_ordenpago_partidas->execute("SELECT numero_control_compromiso FROM cobd01_contratoobras_partidas WHERE upper(numero_contrato_obra)=upper('".$ndo."') and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano_contrato_obra=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."");
    	$numero_control_compromiso=$rs_ncc[0][0]["numero_control_compromiso"];
    	break;//OB retenciones
    	case 7: $ta=6;
    	$rs_ncc=$this->cepd03_ordenpago_partidas->execute("SELECT numero_control_compromiso FROM cepd02_contratoservicio_partidas WHERE upper(numero_contrato_servicio)=upper('".$ndo."') and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano_contrato_servicio=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."");
    	$numero_control_compromiso=$rs_ncc[0][0]["numero_control_compromiso"];
    	break;//SR anticipos
    	case 8: $ta=7;
    	$rs_ncc=$this->cepd03_ordenpago_partidas->execute("SELECT numero_control_compromiso FROM cepd02_contratoservicio_partidas WHERE upper(numero_contrato_servicio)=upper('".$ndo."') and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano_contrato_servicio=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."");
    	$numero_control_compromiso=$rs_ncc[0][0]["numero_control_compromiso"];
    	break;//SR valuacion
    	case 9: $ta=11;
    	$rs_ncc=$this->cepd03_ordenpago_partidas->execute("SELECT numero_control_compromiso FROM cepd02_contratoservicio_partidas WHERE upper(numero_contrato_servicio)=upper('".$ndo."') and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano_contrato_servicio=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."");
    	$numero_control_compromiso=$rs_ncc[0][0]["numero_control_compromiso"];
    	break;//SR retencion
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
	/*if($partida[0]['cod_tipo_documento']==1){
         $this->cepd03_ordenpago_partidas->execute("UPDATE cepd03_ordenpago_partidas SET numero_control_causado=".$ncc." WHERE ano_orden_pago=".$ano." and numero_orden_pago=".$numero_orden_pago."  and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
	     $this->cstd03_cheque_partidas->execute("UPDATE cstd03_cheque_partidas SET numero_control_causado=".$ncc." WHERE ano_orden_pago=".$ano." and numero_orden_pago=".$numero_orden_pago." and numero_cheque=".$numero_cheque." and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
	}else{*/
		 $this->cepd03_ordenpago_partidas->execute("UPDATE cepd03_ordenpago_partidas SET numero_control_compromiso=".$rnco." ,numero_control_causado=".$ncc." WHERE ano_orden_pago=".$ano." and numero_orden_pago=".$numero_orden_pago."  and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
	    // $this->cstd03_cheque_partidas->execute("UPDATE cstd03_cheque_partidas SET numero_control_compromiso=".$rnco." ,numero_control_causado=".$ncc." WHERE ano_orden_pago=".$ano." and numero_orden_pago=".$numero_orden_pago." and numero_cheque=".$numero_cheque."  and cod_entidad_bancaria=$cod_entidad_bancaria and cod_sucursal=$cod_sucursal and cuenta_bancaria='$cuenta_bancaria' and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
	     $this->cstd03_cheque_partidas->execute("UPDATE cstd03_cheque_partidas SET numero_control_compromiso=".$rnco." ,numero_control_causado=".$ncc." WHERE ano_orden_pago=".$ano." and numero_orden_pago=".$numero_orden_pago." and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
	//}
	//echo " ".$ncc;
	/*switch($partida[0]['cod_tipo_documento']){
    	case 1:
    	$tabla_up="";
    	$this->cepd03_ordenpago_partidas->execute("UPDATE cepd03_ordenpago_partidas SET numero_control_causado=".$ncc." WHERE
    			ano_orden_pago=".$ano." and
    			numero_orden_pago=".$numero_orden_pago."  and
    			cod_presi=".$cod_presi." and
    			cod_entidad=".$cod_entidad." and
    			cod_tipo_inst=".$cod_tipo_inst." and
    			cod_inst=".$cod_inst." and
    			cod_dep=".$cod_dep."  and
    			ano=".$ano." and
    			cod_sector=".$cod_sector." and
    			cod_programa=".$cod_programa." and
    			cod_sub_prog=".$cod_sub_prog." and
    			cod_proyecto=".$cod_proyecto." and
    			cod_activ_obra=".$cod_activ_obra." and
    			cod_partida=".$cod_partida." and
    			cod_generica=".$cod_generica." and
    			cod_especifica=".$cod_especifica." and
    			cod_sub_espec=".$cod_sub_espec." and
    			cod_auxiliar=".$cod_auxiliar." ");
	    $this->cstd03_cheque_partidas->execute("UPDATE cstd03_cheque_partidas SET numero_control_compromiso=$numero_control_compromiso,numero_control_causado=".$ncc." WHERE ano_orden_pago=".$ano." and numero_orden_pago=".$numero_orden_pago." and numero_cheque=".$numero_cheque." and cod_entidad_bancaria=$cod_entidad_bancaria and cod_sucursal=$cod_sucursal and cuenta_bancaria='$cuenta_bancaria' and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
    	break;//RC
    	case 2:
    	$this->cepd03_ordenpago_partidas->execute("UPDATE cepd03_ordenpago_partidas SET numero_control_causado=".$ncc." WHERE ano_orden_pago=".$ano." and numero_orden_pago=".$numero_orden_pago."  and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
	    $this->cstd03_cheque_partidas->execute("UPDATE cstd03_cheque_partidas SET numero_control_compromiso=$numero_control_compromiso,numero_control_causado=".$ncc." WHERE ano_orden_pago=".$ano." and numero_orden_pago=".$numero_orden_pago." and numero_cheque=".$numero_cheque." and cod_entidad_bancaria=$cod_entidad_bancaria and cod_sucursal=$cod_sucursal and cuenta_bancaria='$cuenta_bancaria' and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
    	break;//OC anticipos
    	case 3:
    	$this->cepd03_ordenpago_partidas->execute("UPDATE cepd03_ordenpago_partidas SET numero_control_causado=".$ncc." WHERE ano_orden_pago=".$ano." and numero_orden_pago=".$numero_orden_pago."  and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
	    $this->cstd03_cheque_partidas->execute("UPDATE cstd03_cheque_partidas SET numero_control_compromiso=$numero_control_compromiso,numero_control_causado=".$ncc." WHERE ano_orden_pago=".$ano." and numero_orden_pago=".$numero_orden_pago." and numero_cheque=".$numero_cheque." and cod_entidad_bancaria=$cod_entidad_bancaria and cod_sucursal=$cod_sucursal and cuenta_bancaria='$cuenta_bancaria' and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
    	break;//OC autorizacion
    	case 4:
    	$this->cepd03_ordenpago_partidas->execute("UPDATE cepd03_ordenpago_partidas SET numero_control_causado=".$ncc." WHERE ano_orden_pago=".$ano." and numero_orden_pago=".$numero_orden_pago."  and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
	    $this->cstd03_cheque_partidas->execute("UPDATE cstd03_cheque_partidas SET numero_control_compromiso=$numero_control_compromiso,numero_control_causado=".$ncc." WHERE ano_orden_pago=".$ano." and numero_orden_pago=".$numero_orden_pago." and numero_cheque=".$numero_cheque." and cod_entidad_bancaria=$cod_entidad_bancaria and cod_sucursal=$cod_sucursal and cuenta_bancaria='$cuenta_bancaria' and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
    	break;//OB anticipos
    	case 5:
    	$this->cepd03_ordenpago_partidas->execute("UPDATE cepd03_ordenpago_partidas SET numero_control_causado=".$ncc." WHERE ano_orden_pago=".$ano." and numero_orden_pago=".$numero_orden_pago."  and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
	    $this->cstd03_cheque_partidas->execute("UPDATE cstd03_cheque_partidas SET numero_control_compromiso=$numero_control_compromiso,numero_control_causado=".$ncc." WHERE ano_orden_pago=".$ano." and numero_orden_pago=".$numero_orden_pago." and numero_cheque=".$numero_cheque." and cod_entidad_bancaria=$cod_entidad_bancaria and cod_sucursal=$cod_sucursal and cuenta_bancaria='$cuenta_bancaria' and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
    	break;//OB valuaciones
    	case 6:
    	$this->cepd03_ordenpago_partidas->execute("UPDATE cepd03_ordenpago_partidas SET numero_control_causado=".$ncc." WHERE ano_orden_pago=".$ano." and numero_orden_pago=".$numero_orden_pago."  and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
	    $this->cstd03_cheque_partidas->execute("UPDATE cstd03_cheque_partidas SET numero_control_compromiso=$numero_control_compromiso,numero_control_causado=".$ncc." WHERE ano_orden_pago=".$ano." and numero_orden_pago=".$numero_orden_pago." and numero_cheque=".$numero_cheque." and cod_entidad_bancaria=$cod_entidad_bancaria and cod_sucursal=$cod_sucursal and cuenta_bancaria='$cuenta_bancaria' and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
    	break;//OB retenciones
    	case 7:
    	$this->cepd03_ordenpago_partidas->execute("UPDATE cepd03_ordenpago_partidas SET numero_control_causado=".$ncc." WHERE ano_orden_pago=".$ano." and numero_orden_pago=".$numero_orden_pago."  and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
	    $this->cstd03_cheque_partidas->execute("UPDATE cstd03_cheque_partidas SET numero_control_compromiso=$numero_control_compromiso,numero_control_causado=".$ncc." WHERE ano_orden_pago=".$ano." and numero_orden_pago=".$numero_orden_pago." and numero_cheque=".$numero_cheque." and cod_entidad_bancaria=$cod_entidad_bancaria and cod_sucursal=$cod_sucursal and cuenta_bancaria='$cuenta_bancaria' and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
    	break;//SR anticipos
    	case 8:
    	$this->cepd03_ordenpago_partidas->execute("UPDATE cepd03_ordenpago_partidas SET numero_control_causado=".$ncc." WHERE ano_orden_pago=".$ano." and numero_orden_pago=".$numero_orden_pago."  and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
	    $this->cstd03_cheque_partidas->execute("UPDATE cstd03_cheque_partidas SET numero_control_compromiso=$numero_control_compromiso,numero_control_causado=".$ncc." WHERE ano_orden_pago=".$ano." and numero_orden_pago=".$numero_orden_pago." and numero_cheque=".$numero_cheque." and cod_entidad_bancaria=$cod_entidad_bancaria and cod_sucursal=$cod_sucursal and cuenta_bancaria='$cuenta_bancaria' and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
    	break;//SR valuacion
    	case 9:
    	$this->cepd03_ordenpago_partidas->execute("UPDATE cepd03_ordenpago_partidas SET numero_control_causado=".$ncc." WHERE ano_orden_pago=".$ano." and numero_orden_pago=".$numero_orden_pago."  and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
	    $this->cstd03_cheque_partidas->execute("UPDATE cstd03_cheque_partidas SET numero_control_compromiso=$numero_control_compromiso,numero_control_causado=".$ncc." WHERE ano_orden_pago=".$ano." and numero_orden_pago=".$numero_orden_pago." and numero_cheque=".$numero_cheque." and cod_entidad_bancaria=$cod_entidad_bancaria and cod_sucursal=$cod_sucursal and cuenta_bancaria='$cuenta_bancaria' and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ");
    	break;//SR retencion
    }fin switch*/



    $i++;
    $j++;
 }//fin foreach





unset($partidas);
unset($partida);
unset($cp);


if($var!=null){
	//$this->reactualizacion_pagado($var);
	     $this->layout = "ajax";
     	 $this->set('mensaje', "EL causado fue realizado con exito");
         $this->render('vista_index');
}else{
     	 $this->layout = "ajax";
     	 $this->set('mensaje', "EL causado fue realizado con exito");
         $this->render('vista_index');
}//fin if



}//fin reactualizacion orden pago completa

function reactualizacion_causado_anulado () {
     	 $this->layout = "ajax";
     	 $this->set('mensaje', "EL causado fue realizado con exito");
         $this->render('vista_index');

///////////////////////////////////////////////ANULADO///////////////////////////////////////////////
set_time_limit(0);
$ano_select = YEAR_REACTUALIZACION;
$ann=YEAR_REACTUALIZACION;
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
  	$foo                                 =         $partida[0]['fecha_proceso_anulacion'];
  	$fd                                  =         $partida[0]['fecha_proceso_anulacion'];
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



}//fin reactualizacion_causado_anulado





















function reactualizacion_pagado($var=null,$page=null){

if ($page > 1) {
			$offset = ($page - 1) * 50000;
		}else{
			$offset = 0;
		}

        $PAGINAR="LIMIT 50000 OFFSET ".$offset;

set_time_limit(0);
$ano_select = YEAR_REACTUALIZACION;




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
  b.concepto,
  b.fecha_cheque,
  b.ano_anterior,
  b.clase_beneficiario

 from cstd03_cheque_partidas a, cstd03_cheque_cuerpo b

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
  a.numero_cheque = b.numero_cheque ".$PAGINAR);
  //print_r($partidas);

  	$suma = count($partidas);
	$numero_pagado= $this->cfpd23_numero_asiento_pagado->field('cfpd23_numero_asiento_pagado.numero_pagado', "ano_pagado='".YEAR_REACTUALIZACION."'", $order =null);
	if(!empty($numero_pagado)){
		$num_base = $numero_pagado;
		$numero_pagado += $suma ;
		$sql_numero_pagado = "UPDATE cfpd23_numero_asiento_pagado SET numero_pagado='$numero_pagado' WHERE ano_pagado='".YEAR_REACTUALIZACION."';";
	}else{
		$num_base = 1;
		$numero_pagado = $num_base;
		$sql_numero_pagado = "INSERT INTO cfpd23_numero_asiento_pagado VALUES('1', '11', '30', '11', '".YEAR_REACTUALIZACION."', '$numero_pagado')";
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
  $foo                                 =         $partida[0]['fecha_cheque'];
  $fd                                  =         $partida[0]['fecha_cheque'];
  $fd   = $fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
  $fdoo = $foo[8].$foo[9]."/".$foo[5].$foo[6]."/".$foo[0].$foo[1].$foo[2].$foo[3];
  $fecha_orden_pago=$fdoo;

  $ano_anterior                        =         $partida[0]['ano_anterior'];
  $clase_beneficiario                  =         $partida[0]['clase_beneficiario'];

  $cod_entidad_bancaria                =         $partida[0]['cod_entidad_bancaria'];
  $cod_sucursal                        =         $partida[0]['cod_sucursal'];
  $cuenta_bancaria                     =         $partida[0]['cuenta_bancaria'];
  $numero_cheque                       =         $partida[0]['numero_cheque'];
  $clase_orden                         =         $partida[0]['clase_orden'];
  $ano_orden_pago                      =         $partida[0]['ano_orden_pago'];
  $numero_orden_pago                   =         $partida[0]['numero_orden_pago'];
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
  $numero_orden_pago                   =         $partida[0]['numero_orden_pago'];
  $concepto = $partida[0]['concepto'];

 $sql_verificar ="  and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra;
 $sql_verificar .=" and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."";
 /*$rs_o_p=$this->cepd03_ordenpago_partidas->execute("SELECT numero_control_compromiso,numero_control_causado  FROM cepd03_ordenpago_partidas WHERE  ano_orden_pago='".$ano_orden_pago."' AND numero_orden_pago='".$numero_orden_pago."' and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."; ");
 if(count($rs_o_p)!=0){
  $numero_control_compromiso           =         $rs_o_p[0][0]['numero_control_compromiso'];
  $numero_control_causado              =         $rs_o_p[0][0]['numero_control_causado'];
 }*/





	$cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
	$to = 1;
	$td = 5;
	$ta = 1;
	$mt = $monto;
	$ccp = str_replace("'","",$concepto);

	$c = $this->cstd01_entidades_bancarias->findByCod_entidad_bancaria($cod_entidad_bancaria);
	$cod_entidad_bancaria_aux = $c["cstd01_entidades_bancarias"]["denominacion"];

    $condicion  = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
    $sql_actual = $condicion." and ano_orden_pago='".$ano_orden_pago."' and numero_orden_pago='".$numero_orden_pago."' ";

       if($clase_beneficiario==1){

 }else if($clase_beneficiario==2){

 	$resul        = $this->cstd07_retenciones_cuerpo_islr->findAll($sql_actual);
    $ano_anterior = $resul[0]["cstd07_retenciones_cuerpo_islr"]['ano_anterior'];

 }else if($clase_beneficiario==3){

 	$resul        = $this->cstd07_retenciones_cuerpo_timbre->findAll($sql_actual);
    $ano_anterior = $resul[0]["cstd07_retenciones_cuerpo_timbre"]['ano_anterior'];

 }else if($clase_beneficiario==4){

 	$resul        = $this->cstd07_retenciones_cuerpo_municipal->findAll($sql_actual);
    $ano_anterior = $resul[0]["cstd07_retenciones_cuerpo_municipal"]['ano_anterior'];

 }else if($clase_beneficiario==5){

 	$resul        = $this->cstd07_retenciones_cuerpo_iva->findAll($sql_actual);
    $ano_anterior = $resul[0]["cstd07_retenciones_cuerpo_iva"]['ano_anterior'];

 }//fin else




		if($ano_anterior==1){
		    $dnco = 0;
		}else{
			$dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp,$ano,$ndo=null,$nda=null, $opago=$numero_orden_pago, $opfecha=$fecha_orden_pago, $cbanco=$cod_entidad_bancaria_aux, $ccuenta=$cuenta_bancaria, $ccheque=$numero_cheque, $fechache=$fd, $numero_control_compromiso, $numero_control_causado, $num_base, $x, null);
		}//fin else





//  cstd03_cheque_partidas

 $sql_cstd03_cheque_partidas = "UPDATE cstd03_cheque_partidas SET  numero_control_pagado=".$dnco." ";
 $sql_cstd03_cheque_partidas.= "WHERE  cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and numero_cheque='$numero_cheque' and ano_orden_pago='".$ano_orden_pago."' AND numero_orden_pago='".$numero_orden_pago."' AND cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep."  and ano=".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." ";
 $this->cstd03_cheque_cuerpo->execute($sql_cstd03_cheque_partidas);
 $x++;

}//fin foreach



unset($partidas);
unset($partida);
unset($cp);



//if($var!=null){$this->reactualizacion_rendiciones($var);}else{
     	 $this->layout = "ajax";
     	 $this->set('mensaje', "EL pagado fue realizado con exito");
         $this->render('vista_index');
//}//fin if



}//function correr script pagado


function anulacion_pagados () {
  set_time_limit(0);
$ano_select = YEAR_REACTUALIZACION;

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
  b.concepto,
  b.fecha_cheque,
  b.ano_anterior,
  b.clase_beneficiario,
  a.numero_control_pagado
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
  b.condicion_actividad=2 and a.ano_movimiento=".YEAR_REACTUALIZACION);
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
  $foo                    =         $partida[0]['fecha_proceso_anulacion'];
  $fd                                  =         $partida[0]['fecha_proceso_anulacion'];
  $fd=$fd[8].$fd[9]."/".$fd[5].$fd[6]."/".$fd[0].$fd[1].$fd[2].$fd[3];
  $fdoo=$foo[8].$foo[9]."/".$foo[5].$foo[6]."/".$foo[0].$foo[1].$foo[2].$foo[3];
  $fecha_orden_pago=$fdoo;

  $ano_anterior                        =         $partida[0]['ano_anterior'];
  $clase_beneficiario                  =         $partida[0]['clase_beneficiario'];

  $cod_entidad_bancaria                =         $partida[0]['cod_entidad_bancaria'];
  $cod_sucursal                        =         $partida[0]['cod_sucursal'];
  $cuenta_bancaria                     =         $partida[0]['cuenta_bancaria'];
  $numero_cheque                       =         $partida[0]['numero_cheque'];
  $clase_orden                         =         $partida[0]['clase_orden'];
  $ano_orden_pago                      =         $partida[0]['ano_orden_pago'];
  $numero_orden_pago                   =         $partida[0]['numero_orden_pago'];
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
  $numero_anulacion                    =         $partida[0]['numero_anulacion'];

  $busca_concepto_anulacion=$this->cugd03_acta_anulacion_cuerpo->findAll("cod_dep='".$cod_dep."' and numero_acta_anulacion='".$numero_anulacion."' and tipo_operacion='251' and numero_documento='".$numero_cheque."'");
  $concepto_anulacion= $busca_concepto_anulacion[0]["cugd03_acta_anulacion_cuerpo"]["motivo_anulacion"];
  //echo "<br>".$concepto_anulacion;

 $sql_verificar ="  and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra;
 $sql_verificar .=" and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar."";

 $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);


    $condicion  = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
    $sql_actual = $condicion." and ano_orden_pago='".$ano_orden_pago."' and numero_orden_pago='".$numero_orden_pago."' ";

       if($clase_beneficiario==1){

 }else if($clase_beneficiario==2){

 	$resul        = $this->cstd07_retenciones_cuerpo_islr->findAll($sql_actual);
    $ano_anterior = $resul[0]["cstd07_retenciones_cuerpo_islr"]['ano_anterior'];

 }else if($clase_beneficiario==3){

 	$resul        = $this->cstd07_retenciones_cuerpo_timbre->findAll($sql_actual);
    $ano_anterior = $resul[0]["cstd07_retenciones_cuerpo_timbre"]['ano_anterior'];

 }else if($clase_beneficiario==4){

 	$resul        = $this->cstd07_retenciones_cuerpo_municipal->findAll($sql_actual);
    $ano_anterior = $resul[0]["cstd07_retenciones_cuerpo_municipal"]['ano_anterior'];

 }else if($clase_beneficiario==5){

 	$resul        = $this->cstd07_retenciones_cuerpo_iva->findAll($sql_actual);
    $ano_anterior = $resul[0]["cstd07_retenciones_cuerpo_iva"]['ano_anterior'];

 }//fin else




		if($ano_anterior==1){
		    $num_asiento_compromiso = 0;
		}else{
 			$num_asiento_compromiso = $this->motor_presupuestario($cp, 2, 5, 1, $fd, $monto, str_replace("'","",$concepto_anulacion), $ano, $numero_orden_compra=null, $numero_orden_compra_autorizacion_pagos=null, $numero_orden_pago, $opfecha=$fecha_orden_pago, $cod_entidad_bancaria, $cuenta_bancaria, $numero_cheque, $fechache=$fd,$numero_control_compromiso, $numero_control_causado, $numero_control_pagado, null,null);
		}//fin if



 //echo "<br>".$num_asiento_compromiso;
 $x++;
 //echo"<br>".$x;

  }//fin foreach


         $this->layout = "ajax";
     	 $this->set('mensaje', "Anulacion del pagado fue realizado con exito");
         $this->render('vista_index');


}//fin_anualcion_pagados














function barra_proceso(){


 $this->layout = "ajax";

                      for($i=0; $i<10000000; $i++) {

                  	        if($i==0){porcentaje_barra($i, 10000000, "Compromisos de orden de compra anulación de solicitudes anuladas", 1); }
                  	        if($i==5000000){porcentaje_barra($i, 10000000, "Compromisos de orden de compra", 1); }


			                  	if ($i%10000 == 0){
								    	porcentaje_barra($i, 10000000);
			                  	}
					  }

    $this->set('termino', true);
	$this->render('vista_index');

}//fin function






function reactualizacion_total(){


set_time_limit(0);

					//$this->limpiar_tablas($var=1);
					/*$this->reactualizacion_rcompromisos();
					$this->reactualizacion_orden_compra();
					$this->reactualizacion_obrascompromisos();
					$this->reactualizacion_serviciocompromisos();
					$this->reactualizacion_causado();
					$this->reactualizacion_pagado();
					$this->reactualizacion_rendiciones();
					$this->reactualizacion_reintegro();
					$this->reactualizacion_nota_debito_especial();
					$this->reactualizacion_pagado_cstd09_notadebito_cuerpo();
					$this->reactualizacion_pagado_cstp30_debito();
					$this->reformulacion();*/



	$this->set('mensaje', "La Reactualizacion a terminado");


}//fin function

















function eliminar_bienes_inmuebles($opcion=null){

  $this->layout="ajax";

    set_time_limit(0);
	ini_set("memory_limit","2000M");

  $cod_presi     = $this->Session->read('SScodpresi');
  $cod_entidad   = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst      = $this->Session->read('SScodinst');
  $ano_ejecucion = $this->Session->read('ano_ejecucion');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

    $nom = $this->arrd05->generateList($this->condicionNDEP(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
    $this->concatena($nom, 'arr05');


    if($opcion!=null){

    	$this->set('opcion', $opcion);
    	$cod_dep   =  $this->data['datos']['cod_dep'];
    	$this->Session->write('SScoddep', $cod_dep);
        $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;

        $datos=$this->v_inventario_inmuebles_todo->findAll($condicion);


        foreach($datos as $row){


			  $cod_tipo               = $row['v_inventario_inmuebles_todo']['cod_tipo'];
			  $deno_tipo              = $row['v_inventario_inmuebles_todo']['deno_tipo'];
			  $cod_grupo              = $row['v_inventario_inmuebles_todo']['cod_grupo'];
			  $deno_grupo             = $row['v_inventario_inmuebles_todo']['deno_grupo'];
			  $cod_subgrupo           = $row['v_inventario_inmuebles_todo']['cod_subgrupo'];
			  $cod_seccion            = $row['v_inventario_inmuebles_todo']['cod_seccion'];
			  $numero_identificacion  = $row['v_inventario_inmuebles_todo']['numero_identificacion'];
			  $denominacion           = $row['v_inventario_inmuebles_todo']['denominacion_inmueble'];
			  $avaluo_actual          = $row['v_inventario_inmuebles_todo']['avaluo_actual'];
			  $fecha_incorporacion    = cambiar_formato_fecha($row['v_inventario_inmuebles_todo']['fecha_incorporacion']);


					        $aux_datos = $this->cimd01_clasificacion_seccion->findAll(" cod_tipo='".$cod_tipo."' and cod_grupo='".$cod_grupo."' and cod_subgrupo='".$cod_subgrupo."' and cod_seccion='".$cod_seccion."'");

					        $parametro_bienes_aux["denominacion"]            = $denominacion;
					        $parametro_bienes_aux["numero_identificacion"]   = $numero_identificacion;
					        $parametro_bienes_aux["fecha_identificacion"]    = $fecha_incorporacion;
					        $parametro_bienes_aux["concepto"]                = "ELIMINACIÒN";
							$parametro_bienes_aux["monto"]                   = $avaluo_actual;

					        $parametro_bienes_aux["cod_tipo_cuenta"]         = 1;
					        $parametro_bienes_aux["cod_cuenta"]              = 212;
					        $parametro_bienes_aux["cod_subcuenta"]           = $cod_grupo;
					        $parametro_bienes_aux["cod_division"]            = 0;
					        $parametro_bienes_aux["cod_subdivision"]         = 0;

					        $parametro_bienes_aux["cod_tipo"]              = $cod_tipo;
					        $parametro_bienes_aux["cod_grupo"]             = $cod_grupo;
					        $parametro_bienes_aux["cod_subgrupo"]          = $cod_subgrupo;
					        $parametro_bienes_aux["cod_seccion"]           = $cod_seccion;


												             $valor_motor_contabilidad = $this->motor_contabilidad_fiscal(
																													      $to      = 2,
																													      $td      = 17,
																													      $rif_doc = null,
																													      $ano_dc  = $this->ano_ejecucion(),
																													      $n_dc    = $numero_identificacion,
																													      $f_dc    = date('d/m/Y'),
																													      $cpt_dc  = null,
																													      $ben_dc  = null,
																													      $mon_dc  = array(),

																													      $ano_op   = null,
																													      $n_op     = null,
																													      $f_op     = null,

																													      $a_adj_op = null,
																													      $n_adj_op = null,
																													      $f_adj_op = null,
																													      $tp_op    = null,

																													      $deno_ban_pago  = null,
																													      $ano_movimiento = null,
																													      $cod_ent_pago   = null,
																													      $cod_suc_pago   = null,
																													      $cod_cta_pago   = null,

																													      $num_che_o_debi  = null,
																													      $fec_che_o_debi  = null,
																													      $clas_che_o_debi = null,
																													      $tipo_che_o_debi = null,

																													      $ano_dc_array_pago    = array(),
																													      $n_dc_array_pago      = array(),
																													      $n_dc_adj_array_pago  = array(),
																													      $f_dc_array_pago      = array(),

																													      $ano_op_array_pago  = array(),
																													      $n_op_array_pago    = array(),
																													      $f_op_array_pago    = array(),
																													      $tipo_op_array_pago = array(),
																													      $tipo_modificacion  = null,
																													      $f_dc_adj_array_pago= array(),
																													      $parametro_bienes   = $parametro_bienes_aux
																													  );

															    if($valor_motor_contabilidad==true){
															    	    $cond=" numero_identificacion =".$numero_identificacion." and ".$condicion;
                                                                 	    $this->cimd03_inventario_inmuebles->execute("DELETE FROM cimd03_inventario_inmuebles  WHERE ".$cond." and cod_tipo=".$cod_tipo." and cod_grupo=".$cod_grupo." and cod_subgrupo=".$cod_subgrupo." and cod_seccion=".$cod_seccion);
																	    $this->cimd03_inventario_numero->execute("COMMIT;");
																}else{
																	    $this->cimd03_inventario_numero->execute("ROLLBACK;");
																}//fin else

        }//fin foreach


             $this->set('Message_existe', 'Registros eliminados con exito.');



    }//fin if





}//fin function






























function eliminar_bienes_muebles($opcion=null){

  $this->layout="ajax";

    set_time_limit(0);
	ini_set("memory_limit","2000M");

  $cod_presi     = $this->Session->read('SScodpresi');
  $cod_entidad   = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst      = $this->Session->read('SScodinst');
  $ano_ejecucion = $this->Session->read('ano_ejecucion');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

    $nom = $this->arrd05->generateList($this->condicionNDEP(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
    $this->concatena($nom, 'arr05');


    if($opcion!=null){

    	$this->set('opcion', $opcion);
    	$cod_dep   =  $this->data['datos']['cod_dep'];
    	$this->Session->write('SScoddep', $cod_dep);
        $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;

        $datos=$this->v_inventario_muebles_todo->findAll($condicion);


        foreach($datos as $row){


			  $cod_tipo               = $row['v_inventario_muebles_todo']['cod_tipo'];
			  $deno_tipo              = $row['v_inventario_muebles_todo']['deno_tipo'];
			  $cod_grupo              = $row['v_inventario_muebles_todo']['cod_grupo'];
			  $deno_grupo             = $row['v_inventario_muebles_todo']['deno_grupo'];
			  $cod_subgrupo           = $row['v_inventario_muebles_todo']['cod_subgrupo'];
			  $cod_seccion            = $row['v_inventario_muebles_todo']['cod_seccion'];
			  $numero_identificacion  = $row['v_inventario_muebles_todo']['numero_identificacion'];
			  $denominacion           = $row['v_inventario_muebles_todo']['denominacion'];
			  $valor_unitario         = $row['v_inventario_muebles_todo']['valor_unitario'];
			  $fecha_incorporacion    = cambiar_formato_fecha($row['v_inventario_muebles_todo']['fecha_incorporacion']);



					        $aux_datos = $this->cimd01_clasificacion_seccion->findAll(" cod_tipo='".$cod_tipo."' and cod_grupo='".$cod_grupo."' and cod_subgrupo='".$cod_subgrupo."' and cod_seccion='".$cod_seccion."'");

					        $parametro_bienes_aux["denominacion"]            = $denominacion;
					        $parametro_bienes_aux["numero_identificacion"]   = $numero_identificacion;
					        $parametro_bienes_aux["fecha_identificacion"]    = $fecha_incorporacion;
					        $parametro_bienes_aux["concepto"]                = "ELIMINACIÒN";
							$parametro_bienes_aux["monto"]                   = $valor_unitario;
					        $parametro_bienes_aux["cod_tipo_cuenta"]         = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_tipo"];
					        $parametro_bienes_aux["cod_cuenta"]              = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_cuenta"];
					        $parametro_bienes_aux["cod_subcuenta"]           = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_grupo_subcuenta"];
					        $parametro_bienes_aux["cod_division"]            = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_subgrupo_division"];
					        $parametro_bienes_aux["cod_subdivision"]         = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_seccion_subdivision"];

					        $parametro_bienes_aux["cod_tipo"]              = $cod_tipo;
					        $parametro_bienes_aux["cod_grupo"]             = $cod_grupo;
					        $parametro_bienes_aux["cod_subgrupo"]          = $cod_subgrupo;
					        $parametro_bienes_aux["cod_seccion"]           = $cod_seccion;


												             $valor_motor_contabilidad = $this->motor_contabilidad_fiscal(
																													      $to      = 2,
																													      $td      = 16,
																													      $rif_doc = null,
																													      $ano_dc  = $this->ano_ejecucion(),
																													      $n_dc    = $numero_identificacion,
																													      $f_dc    = date('d/m/Y'),
																													      $cpt_dc  = null,
																													      $ben_dc  = null,
																													      $mon_dc  = array(),

																													      $ano_op   = null,
																													      $n_op     = null,
																													      $f_op     = null,

																													      $a_adj_op = null,
																													      $n_adj_op = null,
																													      $f_adj_op = null,
																													      $tp_op    = null,

																													      $deno_ban_pago  = null,
																													      $ano_movimiento = null,
																													      $cod_ent_pago   = null,
																													      $cod_suc_pago   = null,
																													      $cod_cta_pago   = null,

																													      $num_che_o_debi  = null,
																													      $fec_che_o_debi  = null,
																													      $clas_che_o_debi = null,
																													      $tipo_che_o_debi = null,

																													      $ano_dc_array_pago    = array(),
																													      $n_dc_array_pago      = array(),
																													      $n_dc_adj_array_pago  = array(),
																													      $f_dc_array_pago      = array(),

																													      $ano_op_array_pago  = array(),
																													      $n_op_array_pago    = array(),
																													      $f_op_array_pago    = array(),
																													      $tipo_op_array_pago = array(),
																													      $tipo_modificacion  = null,
																													      $f_dc_adj_array_pago= array(),
																													      $parametro_bienes   = $parametro_bienes_aux
																													  );

															    if($valor_motor_contabilidad==true){
                                                                 	    $cond=" numero_identificacion =".$numero_identificacion." and ".$condicion;
 	                                                                    $this->cimd03_inventario_muebles->execute("DELETE FROM cimd03_inventario_muebles  WHERE ".$cond." and cod_tipo=".$cod_tipo." and cod_grupo=".$cod_grupo." and cod_subgrupo=".$cod_subgrupo." and cod_seccion=".$cod_seccion);
																	    $this->cimd03_inventario_numero->execute("COMMIT;");
																}else{
																	    $this->cimd03_inventario_numero->execute("ROLLBACK;");
																}//fin else

        }//fin foreach


           $this->set('Message_existe', 'Registros eliminados con exito.');



    }//fin if





}//fin function






function eliminar_bienes_muebles_espec($opcion=null){

  $this->layout="ajax";

    set_time_limit(0);
	ini_set("memory_limit","2000M");

  $cod_presi     = $this->Session->read('SScodpresi');
  $cod_entidad   = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst      = $this->Session->read('SScodinst');
  $ano_ejecucion = $this->Session->read('ano_ejecucion');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

    $nom = $this->arrd05->generateList($this->condicionNDEP(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
    $this->concatena($nom, 'arr05');


    if($opcion!=null){

    	$this->set('opcion', $opcion);
    	//pr($this->data);
    	$cod_dep   =  $this->data['datos']['cod_dep'];
    	$cod_grupo   =  $this->data['datos']['cod_grupo'];
    	$cod_subgrupo   =  $this->data['datos']['cod_subgrupo'];
    	$cod_seccion   =  $this->data['datos']['cod_seccion'];
    	$num_ide   =  $this->data['datos']['cod_identificacion'];
    	$this->Session->write('SScoddep', $cod_dep);
        $condicion  = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
		$condicion .= " and cod_tipo=2 and cod_grupo=".$cod_grupo." and cod_subgrupo=".$cod_subgrupo." and cod_seccion=".$cod_seccion." and numero_identificacion=".$num_ide;
        //echo $condicion;
        $datos=$this->v_inventario_muebles_todo->findAll($condicion);


        foreach($datos as $row){


			  $cod_tipo               = $row['v_inventario_muebles_todo']['cod_tipo'];
			  $deno_tipo              = $row['v_inventario_muebles_todo']['deno_tipo'];
			  $cod_grupo              = $row['v_inventario_muebles_todo']['cod_grupo'];
			  $deno_grupo             = $row['v_inventario_muebles_todo']['deno_grupo'];
			  $cod_subgrupo           = $row['v_inventario_muebles_todo']['cod_subgrupo'];
			  $cod_seccion            = $row['v_inventario_muebles_todo']['cod_seccion'];
			  $numero_identificacion  = $row['v_inventario_muebles_todo']['numero_identificacion'];
			  $denominacion           = $row['v_inventario_muebles_todo']['denominacion'];
			  $valor_unitario         = $row['v_inventario_muebles_todo']['valor_unitario'];
			  $fecha_incorporacion    = cambiar_formato_fecha($row['v_inventario_muebles_todo']['fecha_incorporacion']);



					        $aux_datos = $this->cimd01_clasificacion_seccion->findAll(" cod_tipo='".$cod_tipo."' and cod_grupo='".$cod_grupo."' and cod_subgrupo='".$cod_subgrupo."' and cod_seccion='".$cod_seccion."'");

					        $parametro_bienes_aux["denominacion"]            = $denominacion;
					        $parametro_bienes_aux["numero_identificacion"]   = $numero_identificacion;
					        $parametro_bienes_aux["fecha_identificacion"]    = $fecha_incorporacion;
					        $parametro_bienes_aux["concepto"]                = "ELIMINACIÒN";
							$parametro_bienes_aux["monto"]                   = $valor_unitario;
					        $parametro_bienes_aux["cod_tipo_cuenta"]         = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_tipo"];
					        $parametro_bienes_aux["cod_cuenta"]              = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_cuenta"];
					        $parametro_bienes_aux["cod_subcuenta"]           = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_grupo_subcuenta"];
					        $parametro_bienes_aux["cod_division"]            = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_subgrupo_division"];
					        $parametro_bienes_aux["cod_subdivision"]         = $aux_datos[0]["cimd01_clasificacion_seccion"]["bienes_seccion_subdivision"];

					        $parametro_bienes_aux["cod_tipo"]              = $cod_tipo;
					        $parametro_bienes_aux["cod_grupo"]             = $cod_grupo;
					        $parametro_bienes_aux["cod_subgrupo"]          = $cod_subgrupo;
					        $parametro_bienes_aux["cod_seccion"]           = $cod_seccion;


												             $valor_motor_contabilidad = $this->motor_contabilidad_fiscal(
																													      $to      = 2,
																													      $td      = 16,
																													      $rif_doc = null,
																													      $ano_dc  = $this->ano_ejecucion(),
																													      $n_dc    = $numero_identificacion,
																													      $f_dc    = date('d/m/Y'),
																													      $cpt_dc  = null,
																													      $ben_dc  = null,
																													      $mon_dc  = array(),

																													      $ano_op   = null,
																													      $n_op     = null,
																													      $f_op     = null,

																													      $a_adj_op = null,
																													      $n_adj_op = null,
																													      $f_adj_op = null,
																													      $tp_op    = null,

																													      $deno_ban_pago  = null,
																													      $ano_movimiento = null,
																													      $cod_ent_pago   = null,
																													      $cod_suc_pago   = null,
																													      $cod_cta_pago   = null,

																													      $num_che_o_debi  = null,
																													      $fec_che_o_debi  = null,
																													      $clas_che_o_debi = null,
																													      $tipo_che_o_debi = null,

																													      $ano_dc_array_pago    = array(),
																													      $n_dc_array_pago      = array(),
																													      $n_dc_adj_array_pago  = array(),
																													      $f_dc_array_pago      = array(),

																													      $ano_op_array_pago  = array(),
																													      $n_op_array_pago    = array(),
																													      $f_op_array_pago    = array(),
																													      $tipo_op_array_pago = array(),
																													      $tipo_modificacion  = null,
																													      $f_dc_adj_array_pago= array(),
																													      $parametro_bienes   = $parametro_bienes_aux
																													  );

															    if($valor_motor_contabilidad==true){
                                                                 	    $cond=" numero_identificacion =".$numero_identificacion." and ".$condicion;
 	                                                                    $this->cimd03_inventario_muebles->execute("DELETE FROM cimd03_inventario_muebles  WHERE ".$cond." and cod_tipo=".$cod_tipo." and cod_grupo=".$cod_grupo." and cod_subgrupo=".$cod_subgrupo." and cod_seccion=".$cod_seccion);
																	    $this->cimd03_inventario_numero->execute("COMMIT;");
																}else{
																	    $this->cimd03_inventario_numero->execute("ROLLBACK;");
																}//fin else

        }//fin foreach


           $this->set('Message_existe', 'Registros eliminados con exito.');



    }//fin if





}//fin function





function select_bienes_muebles($dep=null){

  $this->layout="ajax";

    set_time_limit(0);
	ini_set("memory_limit","2000M");

  $cod_presi     = $this->Session->read('SScodpresi');
  $cod_entidad   = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst      = $this->Session->read('SScodinst');
  $cod_dep 		 = $dep;
  $ano_ejecucion = $this->Session->read('ano_ejecucion');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;

    $nom = $this->cimd03_inventario_muebles->generateList($condicion, 'cod_tipo,cod_grupo,cod_subgrupo,cod_seccion,numero_identificacion ASC', null, '{n}.cimd03_inventario_muebles.numero_identificacion', '{n}.cimd03_inventario_muebles.denominacion');
    $this->concatena($nom, 'identi');

}

function select3($select=null,$var=null) {
	$this->layout = "ajax";
	if($var!=null){
	switch($select){
		case 'grupo':
		  $this->set('SELECT','subgrupo');
		  $this->set('codigo','grupo');
		  $this->set('seleccion','');
		  $this->set('op','si');
		  $this->set('n',2);
		  $this->Session->write('cod_depe',$var);
		  $cond2 ="cod_tipo=2";
		  $lista=  $this->cimd01_clasificacion_grupo->generateList($cond2, 'cod_grupo ASC', null, '{n}.cimd01_clasificacion_grupo.cod_grupo', '{n}.cimd01_clasificacion_grupo.denominacion');
          $this->concatena($lista,'vector');
 		break;
		case 'subgrupo':
		  $this->set('SELECT','seccion');
		  $this->set('codigo','subgrupo');
		  $this->set('seleccion','');
		  $this->set('op','no');
		  $this->set('n',3);
		  $this->Session->write('codigo_grupo',$var);
		  //$num=$this->cimd01_clasificacion_seccion->findCount('cod_tipo='.$ctipo.' and cod_grupo='.$var.' and cod_subgrupo=0');
		  $cond2 ="cod_tipo=2 and cod_grupo=".$var;
		  $lista = $this->cimd01_clasificacion_subgrupo->generateList($cond2, 'cod_subgrupo ASC', null, '{n}.cimd01_clasificacion_subgrupo.cod_subgrupo', '{n}.cimd01_clasificacion_subgrupo.denominacion');
          $this->concatena($lista,'vector');
		break;
		case 'seccion':
		  $this->set('SELECT','identificacion');
		  $this->set('codigo','seccion');
		  $this->set('seleccion','');
		  $this->set('n',4);
		  $cgru =  $this->Session->read('codigo_grupo');
		  $this->Session->write('codigo_subgrupo',$var);
		  $cond2 ="cod_tipo=2 and cod_grupo=".$cgru." and cod_subgrupo=".$var;
		  $lista=  $this->cimd01_clasificacion_seccion->generateList($cond2, 'cod_seccion ASC', null, '{n}.cimd01_clasificacion_seccion.cod_seccion', '{n}.cimd01_clasificacion_seccion.denominacion');
          $this->concatena($lista,'vector');
		break;
		case 'identificacion':
		  $this->set('SELECT','identificacion');
		  $this->set('codigo','identificacion');
		  $this->set('seleccion','');
		  $this->set('n',5);
		  $cod_depe  =  $this->Session->read('cod_depe');
		  $cgru  =  $this->Session->read('codigo_grupo');
		  $csgru =  $this->Session->read('codigo_subgrupo');
		  $condi = $this->condicionNDEP()." and cod_dep=".$cod_depe;
		  $cond2 ="cod_tipo=2 and cod_grupo=".$cgru." and cod_subgrupo=".$csgru." and cod_seccion=".$var;
		  //echo $condi." and ".$cond2;
		  $lista=  $this->cimd03_inventario_muebles->generateList($condi." and ".$cond2." and cod_tipo_desincorporacion=0", 'numero_identificacion ASC', null, '{n}.cimd03_inventario_muebles.numero_identificacion', '{n}.cimd03_inventario_muebles.denominacion');
          $this->concatena($lista,'vector');
		break;
	}//fin wsitch
	}else{
		  $this->set('SELECT','');
		  $this->set('codigo','');
		  $this->set('seleccion','');
		  $this->set('n',10);
		  $this->set('no','no');
		 $this->set('vector','');
	}
}//fin select codigos presupuestarios


function eliminar_bienes_inmuebles_espec($opcion=null){

  $this->layout="ajax";

    set_time_limit(0);
	ini_set("memory_limit","2000M");

  $cod_presi     = $this->Session->read('SScodpresi');
  $cod_entidad   = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst      = $this->Session->read('SScodinst');
  $ano_ejecucion = $this->Session->read('ano_ejecucion');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

    $nom = $this->arrd05->generateList($this->condicionNDEP(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
    $this->concatena($nom, 'arr05');


    if($opcion!=null){

    	$this->set('opcion', $opcion);
    	//pr($this->data);
    	$cod_dep   =  $this->data['datos']['cod_dep'];
    	$cod_grupo   =  $this->data['datos']['cod_grupo'];
    	$cod_subgrupo   =  $this->data['datos']['cod_subgrupo'];
    	$cod_seccion   =  $this->data['datos']['cod_seccion'];
    	$num_ide   =  $this->data['datos']['cod_identificacion'];
    	$this->Session->write('SScoddep', $cod_dep);
        $condicion  = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
		$condicion .= " and cod_tipo=1 and cod_grupo=".$cod_grupo." and cod_subgrupo=".$cod_subgrupo." and cod_seccion=".$cod_seccion." and numero_identificacion=".$num_ide;
        //echo $condicion;
        $datos=$this->v_inventario_inmuebles_todo->findAll($condicion);


        foreach($datos as $row){


			  $cod_tipo               = $row['v_inventario_inmuebles_todo']['cod_tipo'];
			  $deno_tipo              = $row['v_inventario_inmuebles_todo']['deno_tipo'];
			  $cod_grupo              = $row['v_inventario_inmuebles_todo']['cod_grupo'];
			  $deno_grupo             = $row['v_inventario_inmuebles_todo']['deno_grupo'];
			  $cod_subgrupo           = $row['v_inventario_inmuebles_todo']['cod_subgrupo'];
			  $cod_seccion            = $row['v_inventario_inmuebles_todo']['cod_seccion'];
			  $numero_identificacion  = $row['v_inventario_inmuebles_todo']['numero_identificacion'];
			  $denominacion           = $row['v_inventario_inmuebles_todo']['denominacion_inmueble'];
			  $avaluo_actual          = $row['v_inventario_inmuebles_todo']['avaluo_actual'];
			  $fecha_incorporacion    = cambiar_formato_fecha($row['v_inventario_inmuebles_todo']['fecha_incorporacion']);


					        $aux_datos = $this->cimd01_clasificacion_seccion->findAll(" cod_tipo='".$cod_tipo."' and cod_grupo='".$cod_grupo."' and cod_subgrupo='".$cod_subgrupo."' and cod_seccion='".$cod_seccion."'");

					        $parametro_bienes_aux["denominacion"]            = $denominacion;
					        $parametro_bienes_aux["numero_identificacion"]   = $numero_identificacion;
					        $parametro_bienes_aux["fecha_identificacion"]    = $fecha_incorporacion;
					        $parametro_bienes_aux["concepto"]                = "ELIMINACIÒN";
							$parametro_bienes_aux["monto"]                   = $avaluo_actual;

					        $parametro_bienes_aux["cod_tipo_cuenta"]         = 1;
					        $parametro_bienes_aux["cod_cuenta"]              = 212;
					        $parametro_bienes_aux["cod_subcuenta"]           = $cod_grupo;
					        $parametro_bienes_aux["cod_division"]            = 0;
					        $parametro_bienes_aux["cod_subdivision"]         = 0;

					        $parametro_bienes_aux["cod_tipo"]              = $cod_tipo;
					        $parametro_bienes_aux["cod_grupo"]             = $cod_grupo;
					        $parametro_bienes_aux["cod_subgrupo"]          = $cod_subgrupo;
					        $parametro_bienes_aux["cod_seccion"]           = $cod_seccion;


												             $valor_motor_contabilidad = $this->motor_contabilidad_fiscal(
																													      $to      = 2,
																													      $td      = 17,
																													      $rif_doc = null,
																													      $ano_dc  = $this->ano_ejecucion(),
																													      $n_dc    = $numero_identificacion,
																													      $f_dc    = date('d/m/Y'),
																													      $cpt_dc  = null,
																													      $ben_dc  = null,
																													      $mon_dc  = array(),

																													      $ano_op   = null,
																													      $n_op     = null,
																													      $f_op     = null,

																													      $a_adj_op = null,
																													      $n_adj_op = null,
																													      $f_adj_op = null,
																													      $tp_op    = null,

																													      $deno_ban_pago  = null,
																													      $ano_movimiento = null,
																													      $cod_ent_pago   = null,
																													      $cod_suc_pago   = null,
																													      $cod_cta_pago   = null,

																													      $num_che_o_debi  = null,
																													      $fec_che_o_debi  = null,
																													      $clas_che_o_debi = null,
																													      $tipo_che_o_debi = null,

																													      $ano_dc_array_pago    = array(),
																													      $n_dc_array_pago      = array(),
																													      $n_dc_adj_array_pago  = array(),
																													      $f_dc_array_pago      = array(),

																													      $ano_op_array_pago  = array(),
																													      $n_op_array_pago    = array(),
																													      $f_op_array_pago    = array(),
																													      $tipo_op_array_pago = array(),
																													      $tipo_modificacion  = null,
																													      $f_dc_adj_array_pago= array(),
																													      $parametro_bienes   = $parametro_bienes_aux
																													  );

															    if($valor_motor_contabilidad==true){
															    	    $cond=" numero_identificacion =".$numero_identificacion." and ".$condicion;
                                                                 	    $this->cimd03_inventario_inmuebles->execute("DELETE FROM cimd03_inventario_inmuebles  WHERE ".$cond." and cod_tipo=".$cod_tipo." and cod_grupo=".$cod_grupo." and cod_subgrupo=".$cod_subgrupo." and cod_seccion=".$cod_seccion);
																	    $this->cimd03_inventario_numero->execute("COMMIT;");
																}else{
																	    $this->cimd03_inventario_numero->execute("ROLLBACK;");
																}//fin else

        }//fin foreach


           $this->set('Message_existe', 'Registros eliminados con exito.');



    }//fin if





}//fin function



function select4($select=null,$var=null) {
	$this->layout = "ajax";
	if($var!=null){
	switch($select){
		case 'grupo':
		  $this->set('SELECT','subgrupo');
		  $this->set('codigo','grupo');
		  $this->set('seleccion','');
		  $this->set('op','si');
		  $this->set('n',2);
		  $this->Session->write('cod_depe',$var);
		  $cond2 ="cod_tipo=1";
		  $lista=  $this->cimd01_clasificacion_grupo->generateList($cond2, 'cod_grupo ASC', null, '{n}.cimd01_clasificacion_grupo.cod_grupo', '{n}.cimd01_clasificacion_grupo.denominacion');
          $this->concatena($lista,'vector');
 		break;
		case 'subgrupo':
		  $this->set('SELECT','seccion');
		  $this->set('codigo','subgrupo');
		  $this->set('seleccion','');
		  $this->set('op','no');
		  $this->set('n',3);
		  $this->Session->write('codigo_grupo',$var);
		  //$num=$this->cimd01_clasificacion_seccion->findCount('cod_tipo='.$ctipo.' and cod_grupo='.$var.' and cod_subgrupo=0');
		  $cond2 ="cod_tipo=1 and cod_grupo=".$var;
		  $lista = $this->cimd01_clasificacion_subgrupo->generateList($cond2, 'cod_subgrupo ASC', null, '{n}.cimd01_clasificacion_subgrupo.cod_subgrupo', '{n}.cimd01_clasificacion_subgrupo.denominacion');
          $this->concatena($lista,'vector');
		break;
		case 'seccion':
		  $this->set('SELECT','identificacion');
		  $this->set('codigo','seccion');
		  $this->set('seleccion','');
		  $this->set('n',4);
		  $cgru =  $this->Session->read('codigo_grupo');
		  $this->Session->write('codigo_subgrupo',$var);
		  $cond2 ="cod_tipo=1 and cod_grupo=".$cgru." and cod_subgrupo=".$var;
		  $lista=  $this->cimd01_clasificacion_seccion->generateList($cond2, 'cod_seccion ASC', null, '{n}.cimd01_clasificacion_seccion.cod_seccion', '{n}.cimd01_clasificacion_seccion.denominacion');
          $this->concatena($lista,'vector');
		  if($lista!=null){
				$this->concatena($lista,'vector');
			}else{
				$this->set('vector',array('0'=>'00'));
			}

		break;
		case 'identificacion':
		  $this->set('SELECT','identificacion');
		  $this->set('codigo','identificacion');
		  $this->set('seleccion','');
		  $this->set('n',5);
		  $cod_depe  =  $this->Session->read('cod_depe');
		  $cgru  =  $this->Session->read('codigo_grupo');
		  $csgru =  $this->Session->read('codigo_subgrupo');
		  $condi = $this->condicionNDEP()." and cod_dep=".$cod_depe;
		  $cond2 ="cod_tipo=1 and cod_grupo=".$cgru." and cod_subgrupo=".$csgru." and cod_seccion=".$var;
		  //echo $condi." and ".$cond2;
		  $lista=  $this->cimd03_inventario_inmuebles->generateList($condi." and ".$cond2." and cod_tipo_desincorporacion=0", 'numero_identificacion ASC', null, '{n}.cimd03_inventario_inmuebles.numero_identificacion', '{n}.cimd03_inventario_inmuebles.denominacion_inmueble');
          $this->concatena($lista,'vector');
		break;
	}//fin wsitch
	}else{
		  $this->set('SELECT','');
		  $this->set('codigo','');
		  $this->set('seleccion','');
		  $this->set('n',10);
		  $this->set('no','no');
		 $this->set('vector','');
	}
}//fin select codigos presupuestarios


}//fin class



?>