<?php
/*
 * Created on 28/02/2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class Cstp09NotadebitoEspecialController extends AppController{


   var $uses = array('cstd01_sucursales_bancarias','cstd01_entidades_bancarias', 'cstd01_entidades_bancarias', 'cstd02_cuentas_bancarias', 'ccfd04_cierre_mes', 'v_cfpd02_sector', 'cstd09_notadebito_cuerpo', 'cstd09_notadebito_partidas', 'cepd03_ordenpago_cuerpo', 'cepd03_ordenpago_partidas', 'cfpd23_numero_asiento_pagado', 'cfpd05', 'cugd04', 'cfpd23', 'cstd03_movimientos_manuales', 'cstd04_movimientos_generales', 'v_cstd09_notadebito_especial', 'cfpd22_numero_asiento_causado', 'cfpd21_numero_asiento_compromiso', 'cfpd21', 'cfpd22');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf','Form');


function checkSession(){
        if (!$this->Session->check('Usuario')){
            $this->redirect('/salir/');
            exit();
        }else{
          //$this->set('userTable', $this->requestAction('/cnmp03partidas/', array('return')));
          //echo "H".$this->requestAction('/usuarios/actualizar_user',array('return'));
          $this->requestAction('/usuarios/actualizar_user');
        }
}//fin checksession


function beforeFilter(){
          $this->checkSession();
          if($this->ano_ejecucion()!=""){
            return;
          }else{
            echo "<h3>Por Favor, Registre el Año de Ejecuci&oacute;n de Presupuesto<br>Ingrese al M&oacute;dulo de Uso General</h3>";
            exit();
          }
}

function index(){

	$this->layout="ajax";
	$this->limpiar_lista();
	$ano = $this->ano_ejecucion();
	$this->set('ano', $ano);
	$this->Session->delete("ano_movdoc");
	//$lista_entidad = $this->cstd01_entidades_bancarias->generateList('',' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion');
	//$this->concatena($lista_entidad, 'tipo_en');

  	$entidades=$this->cstd01_entidades_bancarias->findAll(null, null, 'cod_entidad_bancaria ASC');
    $lista_entidad=array();
    $codEntidad  = array();
    $denoEntidad = array();
    $total_entidades = count($entidades);
	if($total_entidades==0){
		$lista_entidad = array();
	}else{
		for($i=0; $i<$total_entidades; $i++){
			$codEntidad[]  = mascara($entidades[$i]['cstd01_entidades_bancarias']['cod_entidad_bancaria'], 4);
			$denoEntidad[] = mascara($entidades[$i]['cstd01_entidades_bancarias']['cod_entidad_bancaria'], 4)." - ".$entidades[$i]['cstd01_entidades_bancarias']['denominacion'];
		}
		$lista_entidad = array_combine($codEntidad, $denoEntidad);
	}
	$this->set('tipo_en',$lista_entidad);

	$lista2=  $this->v_cfpd02_sector->generateList($this->condicion()." and ano='$ano'", 'cod_sector ASC', null, '{n}.v_cfpd02_sector.cod_sector', '{n}.v_cfpd02_sector.denominacion');
	$this->concatena($lista2, 'sector');
	$listaPago = $this->cepd03_ordenpago_cuerpo->generateList($this->condicion().' and condicion_actividad=1 and numero_cheque=0', 'numero_orden_pago ASC', null, '{n}.cepd03_ordenpago_cuerpo.numero_orden_pago', '{n}.cepd03_ordenpago_cuerpo.beneficiario');
	$this->concatena($listaPago, 'oPago');

}

function cod_entidad($cod_entidad=null){
  $this->layout="ajax";
  if($cod_entidad != null){
    $this->set('cod_entidad', $cod_entidad);
  }

}

function deno_entidad($cod_entidad=null){
  $this->layout="ajax";
  if($cod_entidad != null){
    $deno_entidad = $this->cstd01_entidades_bancarias->field('cstd01_entidades_bancarias.denominacion', $conditions = "cstd01_entidades_bancarias.cod_entidad_bancaria='$cod_entidad'", $order =null);
    $this->set('deno_entidad', $deno_entidad);
  }

}

function sel_sucursal($cod_entidad=null){
  $this->layout="ajax";
  if($cod_entidad != null){
	$this->set('cod_entidad', $cod_entidad);
	//$listaSucursal = $this->cstd01_sucursales_bancarias->generateList("cod_entidad_bancaria='$cod_entidad'",' cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion');
	//$this->concatena($listaSucursal, 'sucursal');
	$sucursales = $this->cstd01_sucursales_bancarias->findAll("cod_entidad_bancaria='$cod_entidad'", null, 'cod_sucursal ASC');
	$listaSucursal=array();
	$codSucursal  = array();
	$denoSucursal = array();
	$total_sucursales = count($sucursales);
	if($total_sucursales==0){
		$listaSucursal = array();
	}else{
		for($i=0; $i<$total_sucursales; $i++){
			$codSucursal[]  = mascara($sucursales[$i]['cstd01_sucursales_bancarias']['cod_sucursal'], 4);
			$denoSucursal[] = mascara($sucursales[$i]['cstd01_sucursales_bancarias']['cod_sucursal'], 4)." - ".$sucursales[$i]['cstd01_sucursales_bancarias']['denominacion'];
		}
		$listaSucursal = array_combine($codSucursal, $denoSucursal);
	}
	$this->set('sucursal',$listaSucursal);
  }
}

function sel_cuenta($cod_entidad=null, $cod_sucursal=null){
  $this->layout="ajax";
  if($cod_entidad != null && $cod_sucursal != null){
    $this->set('cod_entidad', $cod_entidad);
    $this->set('cod_sucursal', $cod_sucursal);
    $listaCuenta = $this->cstd02_cuentas_bancarias->generateList($this->condicion()." and cod_entidad_bancaria='$cod_entidad' and cod_sucursal='$cod_sucursal'",' cuenta_bancaria ASC', null, '{n}.cstd02_cuentas_bancarias.cuenta_bancaria', '{n}.cstd02_cuentas_bancarias.concepto_manejo');
    $this->concatena($listaCuenta, 'cuenta');
  }
}

function cod_sucursal($cod_sucursal=null){
  $this->layout="ajax";
  if($cod_sucursal != null){
    $this->set('cod_sucursal', $cod_sucursal);
  }
}

function deno_sucursal($cod_entidad=null, $cod_sucursal=null){
  $this->layout="ajax";
  if($cod_entidad != null && $cod_sucursal != null){
    $deno_sucursal = $this->cstd01_sucursales_bancarias->field('cstd01_sucursales_bancarias.denominacion', $conditions = "cstd01_sucursales_bancarias.cod_entidad_bancaria='$cod_entidad' and cod_sucursal='$cod_sucursal'", $order =null);
    $this->set('deno_sucursal', $deno_sucursal);
  }
}

function nro_cuenta($nro_cuenta=null){
  $this->layout="ajax";
  if($nro_cuenta  != null){
    $formato_cuenta=substr($nro_cuenta, 0, 4)." ".substr($nro_cuenta, 4, 4)." ".substr($nro_cuenta, 8, 2)." ".substr($nro_cuenta, 10, 10);
    $this->set('nro_cuenta', $formato_cuenta);
  }
}

function concepto_cuenta($cod_entidad=null, $cod_sucursal=null, $nro_cuenta=null){
  $this->layout="ajax";
  if($cod_entidad != null && $cod_sucursal != null && $nro_cuenta != null){
    $concepto_cuenta = $this->cstd02_cuentas_bancarias->field('cstd02_cuentas_bancarias.concepto_manejo', $conditions = $this->condicion()." and cstd02_cuentas_bancarias.cod_entidad_bancaria='$cod_entidad' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$nro_cuenta'", $order =null);
    $this->set('concepto_cuenta', $concepto_cuenta);
  }
}

function bene($num_opago=null){
  $this->layout="ajax";
  if($num_opago!=null){
    $ano = $this->ano_ejecucion();
    $beneficiario = $this->cepd03_ordenpago_cuerpo->field('cepd03_ordenpago_cuerpo.beneficiario', $conditions = $this->condicion()." and ano_orden_pago='$ano' and cepd03_ordenpago_cuerpo.numero_orden_pago='$num_opago'", $order ="fecha_orden_pago ASC");
    $this->set('beneficiario', $beneficiario);
  }

}

function datos_imputacion($num_opago=null){
  $this->layout="ajax";
  $this->eliminar_item();
  if($num_opago != null){
    $ano = $this->ano_ejecucion();
    $datos = $this->cepd03_ordenpago_partidas->findAll($conditions = $this->condicion()." and ano_orden_pago='$ano' and numero_orden_pago='$num_opago'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
    $this->set('datos', $datos);
    //print_r($datos);
  }
}

function denominacion_pago($num_opago=null){
	$this->layout="ajax";
	if($num_opago!=null){
	    $ano = $this->ano_ejecucion();
	    $concepto = $this->cepd03_ordenpago_cuerpo->field('cepd03_ordenpago_cuerpo.concepto', $conditions = $this->condicion()." and ano_orden_pago='$ano' and cepd03_ordenpago_cuerpo.numero_orden_pago='$num_opago'", $order =null);
	    $this->set('concepto', $concepto);
  	}

}

function guardar(){
  $this->layout="ajax";
  $cod_presi       = $this->Session->read('SScodpresi');
    $cod_entidad   = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst      = $this->Session->read('SScodinst');
    $cod_dep       = $this->Session->read('SScoddep');

  if(!empty($this->data['cstp09_notadebito_especial'])){
    $ano_movimiento           = $this->data['cstp09_notadebito_especial']['ano_movimiento'];
    $cod_entidad_bancaria     = $this->data['cstp09_notadebito_especial']['cod_entidad'];
    $cod_sucursal             = $this->data['cstp09_notadebito_especial']['cod_sucursal'];
    $cuenta_bancaria          = $this->data['cstp09_notadebito_especial']['cod_cuenta'];
    $tipo_documento           = 3;
    $numero_documento         = $this->data['cstp09_notadebito_especial']['nro_notadebito'];
    $ndo                      = $numero_documento;
    $ann                      = $ano_movimiento;
    $fecha_nota_debito        = $this->data['cstp09_notadebito_especial']['fecha_notadebito'];
    $fd                       = $fecha_nota_debito;
    $beneficiario             = $this->data['cstp09_notadebito_especial']['beneficiario'];
    $concepto                 = $this->data['cstp09_notadebito_especial']['concepto_notadebito'];
    $monto                    = $this->Formato1($this->data['cstp09_notadebito_especial']['monto']);
    $monto_total              = $monto;
    $ano_orden_pago           = 0;//$this->data['cstp09_notadebito_especial']['ano_orden_pago'];
    $numero_orden_pago        = 0;
    $fecha_proceso_registro   = date('Y/m/d');
    $username_registro        = $this->Session->read('nom_usuario');
    $dia_asiento_registro     = 0;
    $mes_asiento_registro     = 0;
    $ano_asiento_registro     = 0;
    $numero_asiento_registro  = 0;
    $ano_anulacion            = 0;
    $numero_anulacion         = 0;
    $fecha_proceso_anulacion  = "1900/01/01";
    $dia_asiento_anulacion    = 0;
    $mes_asiento_anulacion    = 0;
    $ano_asiento_anulacion    = 0;
    $numero_asiento_anulacion = 0;
    $username_anulacion       = 0;

      foreach($_SESSION["codigos"] as $nss){
        if($nss!=null){
           $new_array[$i]['ano']=$nss[0];
             $new_array[$i]['cod_sector']=$nss[1];
             $new_array[$i]['cod_programa']=$nss[2];
             $new_array[$i]['cod_sub_prog']=$nss[3];
             $new_array[$i]['cod_proyecto']=$nss[4];
             $new_array[$i]['cod_activ_obra']=$nss[5];
             $new_array[$i]['cod_partida']=$nss[6];
             $new_array[$i]['cod_generica']=$nss[7];
             $new_array[$i]['cod_especifica']=$nss[8];
             $new_array[$i]['cod_sub_espec']=$nss[9];
             $new_array[$i]['cod_auxiliar']=$nss[10];
             $new_array[$i]['monto']=$this->Formato1($nss[11]);
             $i++;
        }//null
      }//fin foreach

    $sql_insert_notadebito = "BEGIN; INSERT INTO cstd09_notadebito_especial_cuerpo VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano_movimiento', '$cod_entidad_bancaria', '$cod_sucursal', '$cuenta_bancaria', '$tipo_documento', '$numero_documento', '$fecha_nota_debito', '$beneficiario', '$monto', '$concepto', '$ano_orden_pago', '$numero_orden_pago', '$fecha_proceso_registro', '$dia_asiento_registro', '$mes_asiento_registro', '$ano_asiento_registro', '$numero_asiento_registro', '$username_registro');";
    $sw = $this->cstd09_notadebito_cuerpo->execute($sql_insert_notadebito);



      $i=0;
      $j =0;


// COMPROMISO


      $numero_compromiso= $this->cfpd21_numero_asiento_compromiso->field('cfpd21_numero_asiento_compromiso.numero_compromiso', $conditions = $this->condicionNDEP()." and ano_compromiso='$ann'", $order =null);
      if(!empty($numero_compromiso)){
        $numero_compromiso ++;
        $sql_numero_compromiso = "UPDATE cfpd21_numero_asiento_compromiso SET numero_compromiso='$numero_compromiso' WHERE ano_compromiso='$ann' and ".$this->condicionNDEP().";";
      }else{
        $numero_compromiso = 1;
        $sql_numero_compromiso = "INSERT INTO cfpd21_numero_asiento_compromiso VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$ann', '$numero_compromiso');";
      }
      $sw_numero_compromiso   = $this->cfpd21_numero_asiento_compromiso->query($sql_numero_compromiso);


// CAUSADO


	  $numero_causado= $this->cfpd22_numero_asiento_causado->field('cfpd22_numero_asiento_causado.numero_causado', $conditions = $this->condicionNDEP()." and ano_causado='$ann'", $order =null);

	  if(!empty($numero_causado)){
	  $numero_causado ++;
	  $sql_numero_causado = "UPDATE cfpd22_numero_asiento_causado SET numero_causado='$numero_causado' WHERE ano_causado='$ann' and ".$this->condicionNDEP().";";
	  }else{
	  $numero_causado = 1;
	  $sql_numero_causado = "INSERT INTO cfpd22_numero_asiento_causado VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$ann', '$numero_causado');";
	  }
	  $sw_numero_causado = $this->cfpd22_numero_asiento_causado->query($sql_numero_causado);


// PAGADO


	  $numero_pagado= $this->cfpd23_numero_asiento_pagado->field('cfpd23_numero_asiento_pagado.numero_pagado', $conditions = $this->condicionNDEP()." and ano_pagado='$ann'", $order =null);

	  if(!empty($numero_pagado)){
	  $numero_pagado ++;
	  $sql_numero_pagado = "UPDATE cfpd23_numero_asiento_pagado SET numero_pagado='$numero_pagado' WHERE ano_pagado='$ann' and ".$this->condicionNDEP().";";
	  }else{
	  $numero_pagado = 1;
	  $sql_numero_pagado = "INSERT INTO cfpd23_numero_asiento_pagado VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$ann', '$numero_pagado');";
	  }
	  $sw_numero_pagado = $this->cfpd23_numero_asiento_pagado->query($sql_numero_pagado);



            foreach($new_array as $cod){
            $ano            = $cod['ano'];
            $cod_sector     = $cod['cod_sector'];
            $cod_programa   = $cod['cod_programa'];
            $cod_sub_prog   = $cod['cod_sub_prog'];
            $cod_proyecto   = $cod['cod_proyecto'];
            $cod_activ_obra = $cod['cod_activ_obra'];
            $cod_partida    = $cod['cod_partida'];
            $cod_generica   = $cod['cod_generica'];
            $cod_especifica = $cod['cod_especifica'];
            $cod_sub_espec  = $cod['cod_sub_espec'];
            $cod_auxiliar   = $cod['cod_auxiliar'];
            $mt             = $cod['monto'];

            $ccp            = $concepto;

          $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
          $to   = 1;
          $td   = 3;
          $ta   = 5;
          $rnco = $numero_compromiso;

          $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp, $ann, $ndo, null, null, null, null, null, null, null, $rnco, null, null, null, $j);
          if($dnco == true){
              $to = 1;
              $td = 4;
              $ta = 8;
              $rnca = $numero_causado;

			  $dnca = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp, $ann, $ndo, null, null, null, null, null, null, null, $rnco, $rnca, null, null, $j);
              if($dnca == true){
                $to = 1;
                $td = 5;
                $ta = 2;
                $rnpa = $numero_pagado;

                $dnpa = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp, $ann, $ndo, null, null, null, $cod_entidad_bancaria, $cuenta_bancaria, $numero_documento, $fecha_nota_debito, $rnco, $rnca, $rnpa, null, $j);
                if($dnpa == true){
                  $sql_insert_notadebito_partidas = "INSERT INTO cstd09_notadebito_especial_partidas VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano_movimiento', '$cod_entidad_bancaria', '$cod_sucursal', '$cuenta_bancaria', '$tipo_documento', '$numero_documento', '$ano', '$cod_sector', '$cod_programa', '$cod_sub_prog', '$cod_proyecto', '$cod_activ_obra', '$cod_partida', '$cod_generica', '$cod_especifica', '$cod_sub_espec', '$cod_auxiliar', '$monto', '$rnco', '$rnca', '$rnpa');";
                  $sw4 = $this->cstd09_notadebito_partidas->execute($sql_insert_notadebito_partidas);
                  if($sw4 > 1){

                  }else{
                      //echo "rollback sw2";
                    $this->cstd09_notadebito_partidas->execute("ROLLBACK;");
                    $this->set('msg_error', "NO SE LOGRO REALIZAR LA NOTA DE DEBITO - POR FAVOR INTENTE DE NUEVO");
                    $this->data=null;
                    $this->index();
                    $this->render('index');
                    return;
                  }
                }else{
                  $this->cfpd05->execute("ROLLBACK;");
                  $this->set('msg_error', "NO SE LOGRO REALIZAR LA NOTA DE DEBITO - POR FAVOR INTENTE DE NUEVO");
                }
              }else{
                $this->cfpd05->execute("ROLLBACK;");
                $this->set('msg_error', "NO SE LOGRO REALIZAR LA NOTA DE DEBITO - POR FAVOR INTENTE DE NUEVO");
                $this->data=null;
                $this->index();
                $this->render('index');
                return;
              }

          }else{
            $this->cfpd05->execute("ROLLBACK;");
            $this->set('msg_error', "NO SE LOGRO REALIZAR LA NOTA DE DEBITO - POR FAVOR INTENTE DE NUEVO");
            $this->data=null;
            $this->index();
            $this->render('index');
            return;
          }
          $j++;
        }//fin del foreach


        $condicion_actividad = 1;
        $sql_insert_movimientos_manuales="INSERT INTO cstd03_movimientos_manuales VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano_movimiento', '$cod_entidad_bancaria', '$cod_sucursal', '$cuenta_bancaria', '$tipo_documento', '$numero_documento', '$fecha_nota_debito', '$beneficiario', '$monto_total', '$concepto', '$fecha_proceso_registro', '$dia_asiento_registro', '$mes_asiento_registro', '$ano_asiento_registro', '$numero_asiento_registro', '$username_registro', '$condicion_actividad', '$ano_anulacion', '$numero_anulacion', '$fecha_proceso_anulacion', '$dia_asiento_anulacion', '$mes_asiento_anulacion', '$ano_asiento_anulacion', '$numero_asiento_anulacion', '$username_anulacion');";
        $mes = $fecha_nota_debito[3].$fecha_nota_debito[4];
        $dia= $fecha_nota_debito[0].$fecha_nota_debito[1];
        $sql_insert_movimientos_generales="INSERT INTO cstd04_movimientos_generales VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano_movimiento', '$cod_entidad_bancaria', '$cod_sucursal', '$cuenta_bancaria', '$mes', '$dia', '$tipo_documento', '$numero_documento', '$monto_total');";
        $sql_update_cuenta_bancaria = "UPDATE cstd02_cuentas_bancarias SET nota_debito_dia = nota_debito_dia + '$monto_total', nota_debito_mes = nota_debito_mes + '$monto_total', nota_debito_ano = nota_debito_ano + '$monto_total', disponibilidad_libro = disponibilidad_libro - '$monto_total' WHERE ".$this->condicion()." and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria';";
        $sw5= $this->cstd03_movimientos_manuales->execute($sql_insert_movimientos_manuales.$sql_insert_movimientos_generales.$sql_update_cuenta_bancaria);
        if($sw5 > 1){//finalizo con el commit
          $commit = $this->cstd03_movimientos_manuales->execute("COMMIT;");
          //print_r($commit);
          $this->set('msg', "LA NOTA DE DEBITO FUE REALIZADA CON EXITO");
        }else{//rollback movimientos
          $this->cstd03_movimientos_manuales->execute("ROLLBACK;");
          $this->set('msg_error', "NO SE LOGRO REALIZAR LA NOTA DE DEBITO - POR FAVOR INTENTE DE NUEVO");
        	}


	}//FIN IF EMPTY DATA
  	$this->data=null;
  	$this->index();
  	$this->render('index');
}



function busqueda($ano_pa=null){
	$this->layout="ajax";
	$ano="";
	if($ano_pa!=null)
		$ano = $ano_pa;
	else
		$ano = $this->ano_ejecucion();

	$ano = $ano != "" ? $ano : date("Y");

	$this->Session->write("ano_movdoc",$ano);
	$this->set('ano', $ano);
	$lista_nota = $this->v_cstd09_notadebito_especial->generateList($this->condicion().' and ano_movimiento='.$ano, 'numero_documento ASC', null, '{n}.v_cstd09_notadebito_especial.numero_documento', '{n}.v_cstd09_notadebito_especial.beneficiario');
	$lista_nota != null ? $this->concatena($lista_nota, 'notas_deb') : $this->set('notas_deb', array());
}


function sel_doc_xano($ano_camp=null){
	$this->layout="ajax";
	$ano = $ano_camp != null ? $ano_camp : 0;
	$this->Session->write("ano_movdoc",$ano);
	$lista_nota = $this->v_cstd09_notadebito_especial->generateList($this->condicion().' and ano_movimiento='.$ano, 'numero_documento ASC', null, '{n}.v_cstd09_notadebito_especial.numero_documento', '{n}.v_cstd09_notadebito_especial.beneficiario');
	$lista_nota != null ? $this->concatena($lista_nota, 'notas_deb') : $this->set('notas_deb', array());
}


function seleccion_busqueda($num_doc){
	$this->layout="ajax";
    $ano="";
    $ano_movimiento="";
    $ano=$this->Session->read("ano_movdoc");
    if($ano!="")
    	$ano_movimiento = $ano;
	else
		$ano_movimiento = $this->ano_ejecucion();

	$ano_movimiento = $ano_movimiento != "" ? $ano_movimiento : date("Y");

    $data =$this->v_cstd09_notadebito_especial->findAll($this->condicion()." and ano_movimiento='$ano_movimiento' and numero_documento='$num_doc'",null,null,1);
    if(!empty($data)){
     foreach ($data as $row){
       $ano_movimiento = $row['v_cstd09_notadebito_especial']['ano_movimiento'];
       $cod_entidad_bancaria = $row['v_cstd09_notadebito_especial']['cod_entidad_bancaria'];
       $entidad_bancaria = $row['v_cstd09_notadebito_especial']['entidad_bancaria'];
       $cod_sucursal = $row['v_cstd09_notadebito_especial']['cod_sucursal'];
       $sucursal_bancaria = $row['v_cstd09_notadebito_especial']['sucursal_bancaria'];
       $cuenta_bancaria = $row['v_cstd09_notadebito_especial']['cuenta_bancaria'];
       $concepto_manejo = $row['v_cstd09_notadebito_especial']['concepto_manejo'];
       $tipo_documento = $row['v_cstd09_notadebito_especial']['tipo_documento'];
       $numero_documento = $row['v_cstd09_notadebito_especial']['numero_documento'];
     }
    $this->set('data', $data);
    $datos = $this->cstd09_notadebito_partidas->findAll($conditions = $this->condicion()." and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and tipo_documento='$tipo_documento'  and numero_documento='$numero_documento'", $fields = null, $order = 'ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar', $limit = null, $page = null, $recursive = null);
    $this->set('datos', $datos);

  }else{
    $this->set('NOTA_DEBITO','');
    $this->set('msg_error', 'No se encontrar&oacute;n datos en la consulta');
  }
}




function consulta($pagina=null){
  $this->layout="ajax";

  if(isset($pagina)){
        $pagina=$pagina;
    }else{
         $pagina=1;
    }//fin else

    $Tfilas = $this->v_cstd09_notadebito_especial->findCount($this->condicion());
    if($Tfilas!=0){
    $this->set('pag_cant',$pagina.'/'.$Tfilas);
    $this->set('ultimo',$Tfilas);
    $data =$this->v_cstd09_notadebito_especial->findAll($this->condicion(),null,'numero_documento ASC',1,$pagina,null);
     foreach ($data as $row){
       $ano_movimiento = $row['v_cstd09_notadebito_especial']['ano_movimiento'];
       $cod_entidad_bancaria = $row['v_cstd09_notadebito_especial']['cod_entidad_bancaria'];
       $entidad_bancaria = $row['v_cstd09_notadebito_especial']['entidad_bancaria'];
       $cod_sucursal = $row['v_cstd09_notadebito_especial']['cod_sucursal'];
       $sucursal_bancaria = $row['v_cstd09_notadebito_especial']['sucursal_bancaria'];
       $cuenta_bancaria = $row['v_cstd09_notadebito_especial']['cuenta_bancaria'];
       $concepto_manejo = $row['v_cstd09_notadebito_especial']['concepto_manejo'];
       $tipo_documento = $row['v_cstd09_notadebito_especial']['tipo_documento'];
       $numero_documento = $row['v_cstd09_notadebito_especial']['numero_documento'];
     }
    $this->set('data', $data);
    $datos = $this->cstd09_notadebito_partidas->findAll($conditions = $this->condicion()." and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and tipo_documento='$tipo_documento'  and numero_documento='$numero_documento'", $fields = null, $order = 'ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar', $limit = null, $page = null, $recursive = null);
    $this->set('datos', $datos);

    $this->set('siguiente',$pagina+1);
    $this->set('anterior',$pagina-1);
    $this->bt_nav($Tfilas,$pagina);

  }else{
    $this->set('NOTA_DEBITO','');
    $this->set('msg_error', 'No se encontrar&oacute;n datos en la consulta');
  }


}

function bt_nav($Tfilas,$pagina){
    if($Tfilas==1){
                $this->set('mostrarS',false);
                $this->set('mostrarA',false);
            }else if($Tfilas==2){
              if($pagina==2){
                   $this->set('mostrarS',false);
                   $this->set('mostrarA',true);
              }else{
                 $this->set('mostrarS',true);
                   $this->set('mostrarA',false);
              }
            }else if($Tfilas>=3){
              if($pagina==$Tfilas){
                     $this->set('mostrarS',false);
                     $this->set('mostrarA',true);
              }else if($pagina==1){
                 $this->set('mostrarS',true);
                     $this->set('mostrarA',false);
              }else{
                 $this->set('mostrarS',true);
                     $this->set('mostrarA',true);
              }
            }
}//fin navegacion

function agregar_partidas($var=null) {
  $this->layout="ajax";

  if(isset($var) && !empty($var)){
            $cod[0]=$this->data["cscp04_ordencompra"]["ano_partidas"];
      $cod[1]=$this->data["cscp04_ordencompra"]["cod_sector"];
      $cod[2]=$this->data["cscp04_ordencompra"]["cod_programa"];
      $cod[3]=$this->data["cscp04_ordencompra"]["cod_subprograma"];
      $cod[4]=$this->data["cscp04_ordencompra"]["cod_proyecto"];
      $cod[5]=$this->data["cscp04_ordencompra"]["cod_actividad"];
      $cod[6]=$this->data["cscp04_ordencompra"]["cod_partida"];
      if($cod[6]<9){
        $cod[6]="40".$cod[6];
      }else if($cod[6]<100){
        $cod[6]="4".$cod[6];
      }else{
        $cod[6]=$cod[6];
      }

      $cod[7]=$this->data["cscp04_ordencompra"]["cod_generica"];
      $cod[8]=$this->data["cscp04_ordencompra"]["cod_especifica"];
      $cod[9]=$this->data["cscp04_ordencompra"]["cod_subespecifica"];
      $cod[10]=$this->data["cscp04_ordencompra"]["cod_auxiliar"];//
      $cod[10]=$cod[10]<9?str_replace("0","",$cod[10]):$cod[10];
      $cod[10]=$cod[10]<9?"0".$cod[10]:$cod[10];
      $cod[11]=$this->data["cscp04_ordencompra"]["monto_partidas"];
        if(isset($_SESSION["i"])){
      $i=$this->Session->read("i")+1;
      $this->Session->write("i",$i);
      }else{
       $this->Session->write("i",0);
      $i=0;
    }
        switch($var){
          case 'normal':
           $vec[$i][0]=$this->data["cscp04_ordencompra"]["ano_partidas"];
           $vec[$i][1]=$this->data["cscp04_ordencompra"]["cod_sector"];
           $vec[$i][2]=$this->data["cscp04_ordencompra"]["cod_programa"];
           $vec[$i][3]=$this->data["cscp04_ordencompra"]["cod_subprograma"];
           $vec[$i][4]=$this->data["cscp04_ordencompra"]["cod_proyecto"];
           $vec[$i][5]=$this->data["cscp04_ordencompra"]["cod_actividad"];
           $vec[$i][6]=$this->data["cscp04_ordencompra"]["cod_partida"];//<9 ? "4.0".$this->data["cepp01_compromiso_partidas"]["cod_partida"] : "4.".$this->data["cepp01_compromiso_partidas"]["cod_partida"];
           $vec[$i][7]=$this->data["cscp04_ordencompra"]["cod_generica"];
           $vec[$i][8]=$this->data["cscp04_ordencompra"]["cod_especifica"];
           $vec[$i][9]=$this->data["cscp04_ordencompra"]["cod_subespecifica"];
           $vec[$i][10]=$this->AddCeroR($this->data["cscp04_ordencompra"]["cod_auxiliar"]);
           $vec[$i][11]=$this->data["cscp04_ordencompra"]["monto_partidas"];
           $vec[$i]["id"]=$i;
           if(isset($_SESSION["codigos"])){
            foreach($_SESSION["codigos"] as $codi){
              //echo $codi[0].$cod[0].$codi[1].$cod[1].$codi[2].$cod[2].$codi[3].$cod[3].$codi[4].$cod[4].$codi[5].$cod[5].$codi[6].$cod[6].$codi[7].$cod[7]. $codi[8].$cod[8].$codi[9].$cod[9].$codi[10].$cod[10];
                         if($codi[0]==$cod[0] && $codi[1]==$cod[1] && $codi[2]==$cod[2] && $codi[3]==$cod[3] && $codi[4]==$cod[4] && $codi[5]==$cod[5] && $codi[6]==$cod[6] && $codi[7]==$cod[7] && $codi[8]==$cod[8] && $codi[9]==$cod[9] && $codi[10]==$cod[10]){
                              $est=true;
                              break;
                        }else{
                           $est=false;
                        }
                        }//fin foreach
                        if($est==true){
                           //	echo "no";
                          $i=$this->Session->read("i")-1;
                    $this->Session->write("i",$i);
                    $this->set('errorMessage', 'Los codigos seleccionados ya existen en la lista');
                        }else{
                          $_SESSION["codigos"]=$_SESSION["codigos"]+$vec;
                          //  echo "si";
                        }
           }else{
            $_SESSION["codigos"]=$vec;
           }
          break;
          case 'nuevos':
                     $vec[$i][0]=$cod[0];
           $vec[$i][1]=$this->AddCeroR($cod[1]);
           $vec[$i][2]=$this->AddCeroR($cod[2]);
           $vec[$i][3]=$this->AddCeroR($cod[3]);
           $vec[$i][4]=$this->AddCeroR($cod[4]);
           $vec[$i][5]=$this->AddCeroR($cod[5]);
           $vec[$i][6]=$cod[6];
           $vec[$i][7]=$this->AddCeroR($cod[7]);
           $vec[$i][8]=$this->AddCeroR($cod[8]);
           $vec[$i][9]=$this->AddCeroR($cod[9]);
           $vec[$i][10]=$this->AddCeroR($cod[10]);
           $vec[$i][11]=$cod[11];
           $vec[$i]["id"]=$i;
           if(isset($_SESSION["codigos"])){
            foreach($_SESSION["codigos"] as $codi){
              //echo $codi[0].$cod[0].$codi[1].$cod[1].$codi[2].$cod[2].$codi[3].$cod[3].$codi[4].$cod[4].$codi[5].$cod[5].$codi[6].$cod[6].$codi[7].$cod[7]. $codi[8].$cod[8].$codi[9].$cod[9].$codi[10].$cod[10];
                         if($codi[0]==$cod[0] && $codi[1]==$cod[1] && $codi[2]==$cod[2] && $codi[3]==$cod[3] && $codi[4]==$cod[4] && $codi[5]==$cod[5] && $codi[6]==$cod[6] && $codi[7]==$cod[7] && $codi[8]==$cod[8] && $codi[9]==$cod[9] && $codi[10]==$cod[10]){
                              $est=true;
                              break;
                        }else{
                           $est=false;
                        }
                        }//fin foreach
                        if($est==true){
                           //	echo "no";
                          $i=$this->Session->read("i")-1;
                    $this->Session->write("i",$i);
                    $this->set('errorMessage', 'Los codigos seleccionados ya existen en la lista');
                        }else{
                          $_SESSION["codigos"]=$_SESSION["codigos"]+$vec;
                          //  echo "si";
                        }
           }else{
            $_SESSION["codigos"]=$vec;
           }
          break;

        }//fin switch


    }//
}//fin funcion agregar_partidas

function borrar_items($id){
  $_SESSION["codigos"][$id]=null;
}

function eliminar_item($i=null, $ano=null, $cod_sector=null, $cod_programa=null, $cod_sub_prog=null, $cod_proyecto=null, $cod_activ_obra=null, $cod_partida=null, $cod_generica=null, $cod_especifica=null, $cod_sub_espec=null, $cod_auxiliar=null){
  $this->layout="ajax";
  $sw = $this->borrar_cugd04();
  $this->Session->delete("codigos");
  $this->Session->delete("i");

}

function eliminar_items ($id) {
  $this->layout = "ajax";
  $this->set('i', $id);
  $_SESSION["codigos"][$id]=null;

}

function limpiar_lista(){
  $this->layout = "ajax";
  $this->Session->delete("codigos");
  $this->Session->delete("i");
}

function preanular(){
  $this->layout="ajax";
  echo "<script>document.getElementById('bt_anular').disabled=true;</script>";
}

function anular($ano_movimiento=null, $cod_entidad_bancaria=null, $cod_sucursal=null, $cuenta_bancaria=null, $tipo_documento=null, $numero_documento=null, $ano_orden_pago=null, $numero_orden_pago=null, $page=null, $redirp=null){
  $this->layout="ajax";
  //FALTA ACTUALIZAR NRO ACTA ANULACION
  $cod_presi     = $this->Session->read('SScodpresi');
  $cod_entidad   = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst      = $this->Session->read('SScodinst');
  $cod_dep       = $this->Session->read('SScoddep');
  $username      = $this->Session->read('nom_usuario');

  if($ano_movimiento != null && $cod_entidad_bancaria != null && $cod_sucursal != null && $cuenta_bancaria != null && $tipo_documento != null && $numero_documento != null && $ano_orden_pago != null && $numero_orden_pago != null){
    $condicion_bancaria = $this->condicion()." and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and tipo_documento='$tipo_documento' and numero_documento='$numero_documento'";


    $partidas = $this->cstd09_notadebito_partidas->findAll($conditions = $this->condicion()." and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and tipo_documento='$tipo_documento' and numero_documento='$numero_documento'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
    //print_r($partidas);

    $fecha_nota_debito = $this->cstd09_notadebito_cuerpo->field('cstd09_notadebito_cuerpo.fecha_nota_debito', $conditions = $condicion_bancaria, $order =null);
    $monto = $this->cstd09_notadebito_cuerpo->field('cstd09_notadebito_cuerpo.monto', $conditions = $condicion_bancaria, $order =null);
    $fd = $fecha_nota_debito[8].$fecha_nota_debito[9].'/'.$fecha_nota_debito[5].$fecha_nota_debito[6].'/'.$fecha_nota_debito[0].$fecha_nota_debito[1].$fecha_nota_debito[2].$fecha_nota_debito[3];
	//echo $fd;

    $mes = $fecha_nota_debito[5].$fecha_nota_debito[6];
    $dia = $fecha_nota_debito[8].$fecha_nota_debito[9];

    $sql_update_orden_pago        = "BEGIN; UPDATE cepd03_ordenpago_cuerpo SET ano_movimiento='0', cod_entidad_bancaria='0', cod_sucursal='0', cuenta_bancaria='0', numero_cheque='0', fecha_cheque='1900/01/01', documento_pago=0 WHERE ".$this->condicion()." and ano_orden_pago='$ano_orden_pago' and numero_orden_pago='$numero_orden_pago';";
    $delete_movimientos_manuales  = "DELETE FROM cstd03_movimientos_manuales WHERE ".$condicion_bancaria.";";
    $delete_movimientos_generales = "DELETE FROM cstd04_movimientos_generales WHERE ".$this->condicion()." and ano_movimiento='$ano_movimiento' and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and tipo_documento='$tipo_documento' and numero_documento='$numero_documento' and mes='$mes' and dia='$dia';";
	$sql_update_cuenta_bancaria   = "UPDATE cstd02_cuentas_bancarias SET nota_debito_dia = nota_debito_dia - '$monto', nota_debito_mes = nota_debito_mes - '$monto', nota_debito_ano = nota_debito_ano - '$monto', disponibilidad_libro = disponibilidad_libro + '$monto' WHERE ".$this->condicion()." and cod_entidad_bancaria='$cod_entidad_bancaria' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria';";
    $sw1 = $this->cstd03_movimientos_manuales->execute($sql_update_orden_pago.$delete_movimientos_manuales.$delete_movimientos_generales.$sql_update_cuenta_bancaria);

    if($sw1 > 1){
      foreach($partidas as $row){
        $ano_movimiento            = $row['cstd09_notadebito_partidas']['ano_movimiento'];
        $cod_entidad_bancaria      = $row['cstd09_notadebito_partidas']['cod_entidad_bancaria'];
        $cod_sucursal              = $row['cstd09_notadebito_partidas']['cod_sucursal'];
        $cuenta_bancaria           = $row['cstd09_notadebito_partidas']['cuenta_bancaria'];
        $tipo_documento            = $row['cstd09_notadebito_partidas']['tipo_documento'];
        $numero_documento          = $row['cstd09_notadebito_partidas']['numero_documento'];
        $ano                       = $row['cstd09_notadebito_partidas']['ano'];
        $cod_sector                = $row['cstd09_notadebito_partidas']['cod_sector'];
        $cod_programa              = $row['cstd09_notadebito_partidas']['cod_programa'];
        $cod_sub_prog              = $row['cstd09_notadebito_partidas']['cod_sub_prog'];
        $cod_proyecto              = $row['cstd09_notadebito_partidas']['cod_proyecto'];
        $cod_activ_obra            = $row['cstd09_notadebito_partidas']['cod_activ_obra'];
        $cod_partida               = $row['cstd09_notadebito_partidas']['cod_partida'];
        $cod_generica              = $row['cstd09_notadebito_partidas']['cod_generica'];
        $cod_especifica            = $row['cstd09_notadebito_partidas']['cod_especifica'];
        $cod_sub_espec             = $row['cstd09_notadebito_partidas']['cod_sub_espec'];
        $cod_auxiliar              = $row['cstd09_notadebito_partidas']['cod_auxiliar'];
        $monto                     = $row['cstd09_notadebito_partidas']['monto'];
        $numero_control_compromiso = $row['cstd09_notadebito_partidas']['numero_control_compromiso'];
        $numero_control_causado    = $row['cstd09_notadebito_partidas']['numero_control_causado'];
        $numero_control_pagado     = $row['cstd09_notadebito_partidas']['numero_control_pagado'];

        $cp                        = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
        $ccp = "ELIMINADO POR EL USUARIO: ".$username.", el : ".date('d/m/y');

          $dnco = $this->motor_presupuestario($cp, 2, 3, 5, $fd, $monto, $ccp, $ano, $numero_documento, null, null, null, null, null, null, null, $numero_control_compromiso, null, null, null, null);

          if($dnco == true){

            $dnca = $this->motor_presupuestario($cp, 2, 4, 8, $fd, $monto, $ccp, $ano, $numero_documento, null, null, null, null, null, null, null, $numero_control_compromiso, $numero_control_causado, null, null, null);
            if($dnca == true){

              $dnpa = $this->motor_presupuestario($cp, 2, 5, 2, $fd, $monto, $ccp, $ano, $numero_documento, null, $numero_orden_pago, $fd, $cod_entidad_bancaria, $cuenta_bancaria, $numero_documento, $fd, $numero_control_compromiso, $numero_control_causado, $numero_control_pagado, null, null);
              if($dnpa == true){

              }else{
                $this->cfpd05->execute("ROLLBACK;");
                $this->set('msg_error', 'LO SIENTO NO SE LOGRO ELIMINAR LA NOTA DE DEBITO');
              }
            }else{
              $this->cfpd05->execute("ROLLBACK;");
              $this->set('msg_error', 'LO SIENTO NO SE LOGRO ELIMINAR LA NOTA DE DEBITO');
            }
          }else{
            $this->cfpd05->execute("ROLLBACK;");
            $this->set('msg_error', 'LO SIENTO NO SE LOGRO ELIMINAR LA NOTA DE DEBITO');
          }

      }
      if($numero_control_pagado){
        $sql_delete_notadebito_especial = "DELETE FROM cstd09_notadebito_especial_cuerpo WHERE ".$condicion_bancaria.";";
        $sw2 = $this->cstd09_notadebito_cuerpo->execute($sql_delete_notadebito_especial);
        if($sw2 > 1){
          //echo "elimino nota de debito<br/>";
          $this->cstd09_notadebito_cuerpo->execute("COMMIT;");
          $this->set('msg', 'LA NOTA DE DEBITO FUE ELIMINADA CON EXITO');
        }else{
          $this->cstd09_notadebito_cuerpo->execute("ROLLBACK;");
          $this->set('msg_error', 'LO SIENTO NO SE LOGRO ELIMINAR LA NOTA DE DEBITO');
        }
      }else{
        $this->cstd03_movimientos_manuales->execute("ROLLBACK;");
        $this->set('msg_error', 'LO SIENTO NO SE LOGRO ELIMINAR LA NOTA DE DEBITO');
      }

    }else{
      $this->cstd03_movimientos_manuales->execute("ROLLBACK;");
      $this->set('msg_error', 'LO SIENTO NO SE LOGRO ELIMINAR LA NOTA DE DEBITO');
    }

    $this->consulta($page);
    $this->render('consulta');

  } // IF PRINCIPAL

}// FIN DE FUNCION

function numero_nd($cod_entidad=null, $cod_sucursal=null, $cuenta_bancaria=null){
	$this->layout="ajax";
	if($cod_entidad != null && $cod_sucursal != null && $cuenta_bancaria != null){
		$this->set('cod_entidad', $cod_entidad);
		$this->set('cod_sucursal', $cod_sucursal);
		$this->set('cuenta_bancaria', $cuenta_bancaria);
	}

}

function dispo($cod_entidad=null, $cod_sucursal=null, $cuenta_bancaria=null){
	$this->layout="ajax";
	if($cod_entidad != null && $cod_sucursal != null && $cuenta_bancaria != null){
		$disponibilidad = $this->cstd02_cuentas_bancarias->field('cstd02_cuentas_bancarias.disponibilidad_libro', $conditions = $this->condicion()." and cod_entidad_bancaria='$cod_entidad' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria'", $order =null);
		echo $disponibilidad = $this->Formato2($disponibilidad);
		echo "<script>document.getElementById('disponibilidad').value='$disponibilidad';</script>";
	}

}

function verifica_nd($cod_entidad=null, $cod_sucursal=null, $cuenta_bancaria=null, $num_nd=null){
	$this->layout="ajax";
	if($cod_entidad != null && $cod_sucursal != null && $cuenta_bancaria != null && $num_nd != null){
		$cont_notadebito = $this->cstd09_notadebito_cuerpo->findCount($this->condicion()." and cod_entidad_bancaria='$cod_entidad' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and numero_documento='$num_nd'");
		$cont_movimientosmanuales = $this->cstd03_movimientos_manuales->findCount($this->condicion()." and cod_entidad_bancaria='$cod_entidad' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria' and numero_documento='$num_nd'");

		if($cont_notadebito != 0 && $cont_movimientosmanuales != 0){
			$this->set('msg_error', 'YA EXISTE UNA NOTA DE DEBITO ESPECIAL CON ESE NUMERO');
		}
	}
}

function monto_nd($cod_entidad=null, $cod_sucursal=null, $cuenta_bancaria=null){
	$this->layout="ajax";
	if($cod_entidad != null && $cod_sucursal != null && $cuenta_bancaria != null){
		$this->set('cod_entidad', $cod_entidad);
		$this->set('cod_sucursal', $cod_sucursal);
		$this->set('cuenta_bancaria', $cuenta_bancaria);
	}

}

function verifica_monto($cod_entidad=null, $cod_sucursal=null, $cuenta_bancaria=null, $monto=null){
	$this->layout="ajax";
	if($cod_entidad != null && $cod_sucursal != null && $cuenta_bancaria != null && $monto != null){
		$disponibilidad = $this->cstd02_cuentas_bancarias->field('cstd02_cuentas_bancarias.disponibilidad_libro', $conditions = $this->condicion()." and cod_entidad_bancaria='$cod_entidad' and cod_sucursal='$cod_sucursal' and cuenta_bancaria='$cuenta_bancaria'", $order =null);
		//echo $this->formato1($disponibilidad)." || ".$this->formato1($monto);
		if($this->formato1($disponibilidad) < $this->formato1($monto)){
			$this->set('msg_error', 'EL MONTO INSERTADO ES MAYOR QUE LA DISPONIBILIDAD DE LA CUENTA BANCARIA');
		}
	}

}

}//fin de la clase
?>
