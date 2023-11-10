<?php
/*
 * Created on 16/04/2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class Cfpp30RendicionesController extends AppController{


   var $uses = array('ccfd03_instalacion','cfpd30_rendiciones_cuerpo','cfpd30_rendiciones_partidas','ccfd04_cierre_mes',
                     'v_cfpd02_sector', 'cfpd23_numero_asiento_pagado', 'cfpd05', 'cugd04', 'cfpd23', 'cstd03_movimientos_manuales',
                     'cstd04_movimientos_generales', 'v_cstd09_notadebito_especial', 'cfpd22_numero_asiento_causado',
                     'cfpd21_numero_asiento_compromiso', 'cfpd21', 'cfpd22', 'cugd03_acta_anulacion_numero', 'cugd03_acta_anulacion_cuerpo',
                     'cstd01_entidades_bancarias','cstd01_sucursales_bancarias', 'cstd02_cuentas_bancarias',

                        'cstd01_entidades_bancarias', 'ccfd10_descripcion', 'ccfd10_detalles', 'ccfd02', 'ccfd05_numero_asiento',
                        'ccfd04_cuentas_enlace', 'cpcd02', 'cepd01_compromiso_cuerpo', 'cscd04_ordencompra_anticipo_cuerpo', 'cstd30_debito_cuerpo',
                        'cscd04_ordencompra_encabezado', 'cscd04_ordencompra_autorizacion_cuerpo', 'cscd04_ordcom_modificacion_cuerpo',
					    'cscd04_ordencompra_encabezado', 'cobd01_contratoobras_anticipo_cuerpo', 'cobd01_contratoobras_valuacion_cuerpo', 'cstd09_notadebito_ordenes',
					    'cobd01_contratoobras_retencion_cuerpo', 'cepd02_contratoservicio_anticipo_cuerpo', 'cepd02_contratoservicio_valuacion_cuerpo',
					    'cepd02_contratoservicio_retencion_cuerpo','cobd01_contratoobras_cuerpo', 'cepd02_contratoservicio_cuerpo', 'cscd02_solicitud_encabezado',
					    'v_cstd03_cheque_orden_pago', 'v_cstd09_notadebito_orden_pago', 'v_inventario_muebles_todo', 'v_inventario_inmuebles_todo',
					    'cimd01_clasificacion_seccion','cugd05_restriccion_clave'
					    );
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

function index(){

$this->verifica_entrada('85');

	$this->layout="ajax";
	$this->limpiar_lista();
	$ano = $this->ano_ejecucion();
	$this->set('ano', $ano);
	$numero_rendicion = $this->cfpd30_rendiciones_cuerpo->field('numero_rendicion', $this->condicion()." and ano_rendicion='$ano'", 'numero_rendicion DESC');

	if(empty($numero_rendicion)){
  		$numero_rendicion = 1;
	}else{
		$numero_rendicion += 1;
	}

	$this->set('numero_rendicion', $numero_rendicion);
	$lista2=  $this->v_cfpd02_sector->generateList($this->condicion()." and ano='$ano'", 'cod_sector ASC', null, '{n}.v_cfpd02_sector.cod_sector', '{n}.v_cfpd02_sector.denominacion');
    $this->concatena($lista2, 'sector');

    $this->set('cod_entidad_lista', $this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'));


}//fin function

function rendicion_caja_chica($var=null) {
	$this->layout="ajax";
	$this->set('var_cach', $var);
	$this->set('cod_entidad_lista', $this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'));
}

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
           $vec[$i][10]=$this->mascara_cuatro($this->data["cscp04_ordencompra"]["cod_auxiliar"]);
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
           $vec[$i][10]=$this->mascara_cuatro($cod[10]);
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
  $this->render("agregar_partidas");

}

function limpiar_lista(){
  $this->layout = "ajax";
  $this->Session->delete("codigos");
  $this->Session->delete("i");
}





function select_para_cuenta_bancaria($var1=null, $var2=null, $var3=null){


$this->layout= "ajax";

			     if($var1==1){
                    $this->set('var1', $var2);
                    $lista = "";
		            if($var2!=""){
					  $lista = $this->cstd01_sucursales_bancarias->generateList("cod_entidad_bancaria=".$var2, 'cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion');
					}//fin if
					if(!is_array($lista)){
						$this->set('cod_sucursal_lista', array());
					}else{
						$this->set('cod_sucursal_lista', $lista);
					}//fin else
					    $c=$this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$var2);
		                $this->set("deno", $c[0]["cstd01_entidades_bancarias"]["denominacion"]);
		                $this->set("cod",  mascara($c[0]["cstd01_entidades_bancarias"]["cod_entidad_bancaria"],4));
			}else if($var1==2){
                    $this->set('var1', $var2);
                    $this->set('var2', $var3);
                    $lista = "";
                    if($var2!=""){
					    $lista =  $this->cstd02_cuentas_bancarias->generateList($this->condicion()." and cod_entidad_bancaria=".$var2." and cod_sucursal=".$var3, 'cuenta_bancaria ASC', null, '{n}.cstd02_cuentas_bancarias.cuenta_bancaria', '{n}.cstd02_cuentas_bancarias.concepto_manejo');
					}//fin if
					if(!is_array($lista)){
						$this->set('cod_cuenta_lista', array());
					}else{
						$this->concatena($lista, 'cod_cuenta_lista');
					}//fin else
					    $c=$this->cstd01_sucursales_bancarias->findAll("cod_entidad_bancaria=".$var2." and cod_sucursal=".$var3);
		                $this->set("deno", $c[0]["cstd01_sucursales_bancarias"]["denominacion"]);
		                $this->set("cod",  mascara($c[0]["cstd01_sucursales_bancarias"]["cod_sucursal"],4));
			}//fin else


$this->set('opcion', $var1);

}//fin function




	function select_cheques($var1=null, $var2=null, $var3=null, $var4=null){
		$this->layout= "ajax";

			if($var1==1){
                    $this->set('var1', $var2);
                    $lista = "";
		            if($var2!=""){
					  $lista = $this->cstd01_sucursales_bancarias->generateList("cod_entidad_bancaria=".$var2, 'cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion');
					}//fin if
					if(!is_array($lista)){
						$this->set('cod_sucursal_lista', array());
					}else{
						$this->set('cod_sucursal_lista', $lista);
					}//fin else
					    $c=$this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$var2);
		                $this->set("deno", $c[0]["cstd01_entidades_bancarias"]["denominacion"]);
		                $this->set("cod",  mascara($c[0]["cstd01_entidades_bancarias"]["cod_entidad_bancaria"],4));
			}else if($var1==2){
                    $this->set('var1', $var2);
                    $this->set('var2', $var3);
                    $lista = "";
                    if($var3!=""){
					    $lista =  $this->cstd02_cuentas_bancarias->generateList($this->condicion()." and cod_entidad_bancaria=".$var2." and cod_sucursal=".$var3, 'cuenta_bancaria ASC', null, '{n}.cstd02_cuentas_bancarias.cuenta_bancaria', '{n}.cstd02_cuentas_bancarias.concepto_manejo');
					}//fin if
					if(!is_array($lista)){
						$this->set('cod_cuenta_lista', array());
					}else{
						$this->concatena($lista, 'cod_cuenta_lista');
					}//fin else
					    $c=$this->cstd01_sucursales_bancarias->findAll("cod_entidad_bancaria=".$var2." and cod_sucursal=".$var3);
		                $this->set("deno", $c[0]["cstd01_sucursales_bancarias"]["denominacion"]);
		                $this->set("cod",  mascara($c[0]["cstd01_sucursales_bancarias"]["cod_sucursal"],4));
			}else if($var1==3){
                    $this->set('var1', $var2);
                    $this->set('var2', $var3);
                    $this->set('var3', $var4);
                    $lista = "";
                    if($var4!=""){
                    	$ano = $this->ano_ejecucion();
					    $lista =  $this->cstd03_movimientos_manuales->generateList($this->condicion()." and ano_movimiento=".$ano." and cod_entidad_bancaria=".$var2." and cod_sucursal=".$var3." and cuenta_bancaria='$var4'"." and tipo_documento=4 and caja_chica=1 and caja_chica_rendida=2", 'numero_documento ASC', null, '{n}.cstd03_movimientos_manuales.numero_documento', '{n}.cstd03_movimientos_manuales.numero_documento');
					}//fin if
					if(!is_array($lista)){
						$this->set('cod_cheque_lista', array());
					}else{
						$this->concatena($lista, 'cod_cheque_lista');
					}//fin else
			}//fin else

		$this->set('opcion', $var1);
	}//fin function




function guardar(){
	$this->layout= "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst = $this->Session->read('SScodinst');
    $cod_dep = $this->Session->read('SScoddep');

if(!empty($this->data['cfpp30_rendiciones'])){
		$ann = $ano_rendicion    = $this->data['cfpp30_rendiciones']['ano_rendicion'];
		$ndo = $numero_rendicion = $this->data['cfpp30_rendiciones']['numero_rendicion'];
		$fd = $fecha_rendicion   = $this->data['cfpp30_rendiciones']['fecha_rendicion'];
		$funcionario_responsable = $this->data['cfpp30_rendiciones']['funcionario_responsable'];
		$concepto                = $this->data['cfpp30_rendiciones']['concepto'];

		$cod_ent_pago_aux        = $this->data['cfpp30_rendiciones']['cod_entidad_bancaria'];
        $cod_suc_pago_aux        = $this->data['cfpp30_rendiciones']['cod_sucursal_bancaria'];
 	    $cod_cta_pago_aux        = $this->data['cfpp30_rendiciones']['cod_cuenta_bancaria'];

		$rendicion_cach = $this->data['cfpp30_rendiciones']['rendicion_cach'];

		if($rendicion_cach == '1'){
			$cod_ent_banc_cach = $this->data['cfpp30_rendiciones']['cod_entidad_bancaria_cach'];
        	$cod_suc_banc_cach = $this->data['cfpp30_rendiciones']['cod_sucursal_bancaria_cach'];
 	    	$cod_cta_banc_cach = $this->data['cfpp30_rendiciones']['cod_cuenta_bancaria_cach'];
 	    	$cod_che_banc_cach = $this->data['cfpp30_rendiciones']['cod_cheque_cach'];
 	    	$ano_mov_banc_cach = $ano_rendicion != null ? $ano_rendicion : $this->ano_ejecucion();
		}else{
			$cod_ent_banc_cach = 0;
        	$cod_suc_banc_cach = 0;
 	    	$cod_cta_banc_cach = '';
 	    	$cod_che_banc_cach = 0;
			$ano_mov_banc_cach = 0;
		}

		$numero_cheque = $this->data['cfpp30_rendiciones']['numero_cheque'];
		$fecha_cheque = $this->Cfecha($this->data['cfpp30_rendiciones']['fecha_cheque'], 'A-M-D');
		$monto_cheque = $this->Formato1($this->data['cfpp30_rendiciones']['monto_cheque']);

		$fecha_proceso_registro   = date('d/m/Y');
		$numero_asiento_registro  = 0;
		$dia_asiento_registro     = 0;
		$mes_asiento_registro     = 0;
		$ano_asiento_registro     = 0;
		$condicion_actividad      = 1;
		$username_registro        = strtoupper($this->Session->read('nom_usuario'));
		$dia_asiento_anulacion    = 0;
		$mes_asiento_anulacion    = 0;
		$ano_asiento_anulacion    = 0;
		$numero_asiento_anulacion = 0;
		$ano_acta_anulacion       = 0;
		$numero_acta_anulacion    = "0";
		$username_anulacion       = "0";
		$fecha_proceso_anulacion  = "1900/01/01";

		$sql_insert_rendiciones="BEGIN; INSERT INTO cfpd30_rendiciones_cuerpo VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano_rendicion', '$numero_rendicion', '$fecha_rendicion', '$funcionario_responsable', '$concepto', '$fecha_proceso_registro', '$dia_asiento_registro', '$mes_asiento_registro', '$ano_asiento_registro', '$numero_asiento_registro', '$username_registro', '$condicion_actividad', '$dia_asiento_anulacion', '$mes_asiento_anulacion', '$ano_asiento_anulacion', '$numero_asiento_anulacion', '$ano_acta_anulacion', '$numero_acta_anulacion', '$username_anulacion', '$fecha_proceso_anulacion', '".$cod_ent_pago_aux."', '".$cod_suc_pago_aux."', '".$cod_cta_pago_aux."', '".$numero_cheque."', '".$fecha_cheque."', '".$monto_cheque."', '".$ano_mov_banc_cach."', '".$cod_ent_banc_cach."', '".$cod_suc_banc_cach."', '".$cod_cta_banc_cach."', '".$cod_che_banc_cach."', '".$rendicion_cach."');";

		$this->cfpd30_rendiciones_cuerpo->execute($sql_insert_rendiciones);
	$i=0;
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
      $j =0;
      $suma_para_contabilidad = 0;


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




		      if($sw_numero_compromiso > 1){
					      foreach($new_array as $cod){
					          $ano = $cod['ano'];
					          $cod_sector= $cod['cod_sector'];
					          $cod_programa = $cod['cod_programa'];
					          $cod_sub_prog = $cod['cod_sub_prog'];
					          $cod_proyecto = $cod['cod_proyecto'];
					          $cod_activ_obra = $cod['cod_activ_obra'];
					          $cod_partida = $cod['cod_partida'];
					          $cod_generica = $cod['cod_generica'];
					          $cod_especifica = $cod['cod_especifica'];
					          $cod_sub_espec = $cod['cod_sub_espec'];
					          $cod_auxiliar = $cod['cod_auxiliar'];
					          $monto = $cod['monto'];

					          $suma_para_contabilidad += $monto;
					          $ndo = $numero_rendicion;
					          $ccp = $concepto;

					          $cp   = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
					          $to   = 1;
					          $td   = 3;
					          $ta   = 6;
					          $mt   = $monto;
					          $rnco = $numero_compromiso;

		     $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp, $ann, $ndo, null, null, null, null, null, null, null, $rnco, null, null, null, $j);

			 if($dnco == true){
							  $to   = 1;
							  $td   = 4;
							  $ta   = 9;
							  $rnca = $numero_causado;

				    $dnca = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp, $ann, $ndo, null, null, null, null, null, null, null, $rnco, $rnca, null, null, $j);
			    if($dnca == true ){

					          $to   = 1;
							  $td   = 5;
							  $ta   = 3;
							  $rnpa = $numero_pagado;

					 $dnpa = $this->motor_presupuestario($cp, $to ,$td, $ta, $fd, $mt, $ccp, $ann, $ndo, null, null, null, $cbanco=$cod_ent_pago_aux, $ccuenta=$cod_cta_pago_aux, null, null, $rnco, $rnca, $rnpa, null, $j);
				   if($dnpa == true){
							  $sql_insert_notadebito_partidas = "INSERT INTO cfpd30_rendiciones_partidas VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano_rendicion', '$numero_rendicion', '$ano', '$cod_sector', '$cod_programa', '$cod_sub_prog', '$cod_proyecto', '$cod_activ_obra', '$cod_partida', '$cod_generica', '$cod_especifica', '$cod_sub_espec', '$cod_auxiliar', '$monto', '$rnco', '$rnca', '$rnpa');";
			                  $sw4 = $this->cfpd30_rendiciones_partidas->execute($sql_insert_notadebito_partidas);
						 if($sw4 > 1){

							}else{
						         $this->cfpd30_rendiciones_partidas->execute("ROLLBACK;");
								 $this->set('msg_error', "NO SE LOGRO REALIZAR LA RENDICIÓN GENERAL");
					             $this->data=null;
							     $this->index();
								 $this->render('index');
							     return;
							     }
					 }else{
				                $this->cfpd30_rendiciones_partidas->execute("ROLLBACK;");
								$this->set('msg_error', "NO SE LOGRO REALIZAR LA RENDICIÓN GENERAL");
								}//fin else

			   }else{//ROLLBACK SI NO EJECUTA EL NUMERO DE CONTROL DE PAGADO
							    $this->cfpd30_rendiciones_partidas->execute("ROLLBACK;");
							    $this->set('msg_error', "NO SE LOGRO REALIZAR LA RENDICIÓN GENERAL");
							    }//fin else

		    }else{
					            $this->cfpd30_rendiciones_partidas->execute("ROLLBACK;");
					            $this->set('msg_error', "NO SE LOGRO REALIZAR LA RENDICIÓN GENERAL");
					            $this->data=null;
					            $this->index();
					            $this->render('index');
					            return;
					          }//fin else
					          $j++;
		}//fin del foreach

		               if($sw4 > 1){

		        	   $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
			                                                                         $to              = 1,
			                                                                         $td              = 19,
			                                                                         $rif_doc         = null,
			                                                                         $ano_dc          = $ano_rendicion,
			                                                                         $n_dc            = $numero_rendicion,
			                                                                         $f_dc            = $this->data['cfpp30_rendiciones']['fecha_rendicion'],
			                                                                         $cpt_dc          = $concepto,
			                                                                         $ben_dc          = $funcionario_responsable,
			                                                                         $mon_dc          = array("monto"  => $suma_para_contabilidad),

			                                                                         $ano_op          = null,
			                                                                         $n_op            = null,
			                                                                         $f_op            = null,

			                                                                         $a_adj_op        = null,
			                                                                         $n_adj_op        = null,
			                                                                         $f_adj_op        = null,
			                                                                         $tp_op           = null,

			                                                                         $deno_ban_pago   = null,
			                                                                         $ano_movimiento  = null,
			                                                                         $cod_ent_pago    = $cod_ent_pago_aux,
			                                                                         $cod_suc_pago    = $cod_suc_pago_aux,
			                                                                         $cod_cta_pago    = $cod_cta_pago_aux,

			                                                                         $num_che_o_debi  = null,
			                                                                         $fec_che_o_debi  = null,
			                                                                         $clas_che_o_debi = null
			                                                                     );
			                    if($valor_motor_contabilidad==true){

			                    	$sw_cach = $this->cstd03_movimientos_manuales->execute("UPDATE cstd03_movimientos_manuales SET caja_chica_rendida=1 WHERE ".$this->condicion()." and ano_movimiento=".$ano_mov_banc_cach." and cod_entidad_bancaria=".$cod_ent_banc_cach." and cod_sucursal=".$cod_suc_banc_cach." and cuenta_bancaria='$cod_cta_banc_cach' and numero_documento=".$cod_che_banc_cach." and tipo_documento=4 and caja_chica=1 and caja_chica_rendida=2;");
			                    	if($sw_cach > 1){
							        	$this->cfpd30_rendiciones_partidas->execute("COMMIT;");
						        		$this->set('msg', "EL REGISTRO FUE GUARDADO EXITOSAMENTE");
			                    	}else{
										$this->cfpd30_rendiciones_partidas->execute("ROLLBACK;");
						            	$this->set('msg_error', "NO SE LOGRO REALIZAR LA RENDICIÓN GENERAL");
			                    	}

						        }else{
									$this->cfpd30_rendiciones_partidas->execute("ROLLBACK;");
						            $this->set('msg_error', "NO SE LOGRO REALIZAR LA RENDICIÓN GENERAL");
						        }//fin else


		        }else{
					$this->cfpd30_rendiciones_partidas->execute("ROLLBACK;");
		            $this->set('msg_error', "NO SE LOGRO REALIZAR LA RENDICIÓN GENERAL");
		        }//fin else

	  }else{
      	$this->cfpd30_rendiciones_partidas->query("ROLLBACK;");
      	$this->set('msg_error', "NO SE LOGRO REALIZAR LA RENDICIÓN GENERAL");
      }//fin else


}//fin if

	$this->data = null;
	$this->index();
	$this->render('index');


}//fin function







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


function ano_consultar($year){
    $this->layout="ajax";
	$_SESSION["ano_consulta_rendiciones"] = $year;

}



function consulta_index(){
	$this->layout="ajax";
	$_SESSION["ano_consulta_rendiciones"] = $this->ano_ejecucion();
    $this->set('ANO',$this->ano_ejecucion());
}






function consulta($pagina=null){
	$this->layout= "ajax";

	if(isset($pagina)){
        $pagina=$pagina;
    }else{
         $pagina=1;
    }//fin else


    if(isset($this->data['cfpp30_rendiciones']['ano_consulta'])){
         $_SESSION["ano_consulta_rendiciones"] = $this->data['cfpp30_rendiciones']['ano_consulta'];
    }

    if(!empty($_SESSION["ano_consulta_rendiciones"])){
        $ano_consulta=$_SESSION["ano_consulta_rendiciones"];
    }else{
         $ano_consulta=$this->ano_ejecucion();
    }//fin else
    $this->set('ano_ejecucion',$this->ano_ejecucion());




    $Tfilas = $this->cfpd30_rendiciones_cuerpo->findCount($this->condicion()." and ano_rendicion='".$ano_consulta."'   ");
    if($Tfilas!=0){
	    $this->set('pag_cant',$pagina.'/'.$Tfilas);
	    $this->set('ultimo',$Tfilas);
	    $data =$this->cfpd30_rendiciones_cuerpo->findAll($this->condicion()." and ano_rendicion='".$ano_consulta."'   ",null,'numero_rendicion ASC',1,$pagina,null);
	    foreach ($data as $row){
	       $ano_movimiento = $row['cfpd30_rendiciones_cuerpo']['ano_rendicion'];
	       $concepto_manejo = $row['cfpd30_rendiciones_cuerpo']['concepto'];
	       $numero_documento = $row['cfpd30_rendiciones_cuerpo']['numero_rendicion'];
	       $condicion_actividad = $row['cfpd30_rendiciones_cuerpo']['condicion_actividad'];
	       $ano_acta_anulacion = $row['cfpd30_rendiciones_cuerpo']['ano_acta_anulacion'];
	       $numero_acta_anulacion = $row['cfpd30_rendiciones_cuerpo']['numero_acta_anulacion'];

	       $cod_entidad_bancaria = $row['cfpd30_rendiciones_cuerpo']['cod_entidad_bancaria'];
	       $cod_sucursal         = $row['cfpd30_rendiciones_cuerpo']['cod_sucursal'];
	       $cuenta_bancaria      = $row['cfpd30_rendiciones_cuerpo']['cuenta_bancaria'];

		   $cod_ent_banc_cach = $row['cfpd30_rendiciones_cuerpo']['cod_entidad_bancaria_cach'];
		   $cod_suc_cach = $row['cfpd30_rendiciones_cuerpo']['cod_sucursal_cach'];
	       $rendicion_caja_chica = $row['cfpd30_rendiciones_cuerpo']['rendicion_caja_chica'];
	    }

                  if($cuenta_bancaria!=""){

	                    $c=$this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$cod_entidad_bancaria);
		                $this->set("deno_enti", $c[0]["cstd01_entidades_bancarias"]["denominacion"]);
		                $this->set("cod_enti",  $c[0]["cstd01_entidades_bancarias"]["cod_entidad_bancaria"]);

					    $c=$this->cstd01_sucursales_bancarias->findAll("cod_entidad_bancaria=".$cod_entidad_bancaria." and cod_sucursal=".$cod_sucursal);
		                $this->set("deno_sucu", $c[0]["cstd01_sucursales_bancarias"]["denominacion"]);
		                $this->set("cod_sucu",  $c[0]["cstd01_sucursales_bancarias"]["cod_sucursal"]);

		                $this->set("cuenta",    $cuenta_bancaria);
                  }else{

                  	    $this->set("deno_enti", "");
		                $this->set("cod_enti",  "");
                        $this->set("deno_sucu", "");
		                $this->set("cod_sucu",  "");
		                $this->set("cuenta",    "");
                  }//fin else

                  if((int) $rendicion_caja_chica == 1){

	                    $c=$this->cstd01_entidades_bancarias->findAll("cod_entidad_bancaria=".$cod_ent_banc_cach);
		                $this->set("deno_enti_cach", $c[0]["cstd01_entidades_bancarias"]["denominacion"]);
		                $this->set("cod_enti_cach",  $c[0]["cstd01_entidades_bancarias"]["cod_entidad_bancaria"]);

					    $c=$this->cstd01_sucursales_bancarias->findAll("cod_entidad_bancaria=".$cod_ent_banc_cach." and cod_sucursal=".$cod_suc_cach);
		                $this->set("deno_sucu_cach", $c[0]["cstd01_sucursales_bancarias"]["denominacion"]);
		                $this->set("cod_sucu_cach",  $c[0]["cstd01_sucursales_bancarias"]["cod_sucursal"]);
                  }else{

                  	    $this->set("deno_enti_cach", "");
		                $this->set("cod_enti_cach",  "");
                        $this->set("deno_sucu_cach", "");
		                $this->set("cod_sucu_cach",  "");
                  }//fin else

	    $tipo_operacion = 253;
	    if($condicion_actividad==2){
	    	$motivo_anulacion = $this->cugd03_acta_anulacion_cuerpo->field('cugd03_acta_anulacion_cuerpo.motivo_anulacion', $conditions = $this->condicion()." and ano_acta_anulacion='$ano_acta_anulacion' and numero_acta_anulacion='$numero_acta_anulacion' and tipo_operacion='$tipo_operacion' and ano_documento='$ano_movimiento' and numero_documento='$numero_documento'", $order =null);
			//$motivo_anulacion="";
	    }else{
	    	$motivo_anulacion="";
	    }
	    //echo $motivo_anulacion;
	    $this->set('motivo_anulacion', $motivo_anulacion);
	    $this->set('data', $data);
	    $datos = $this->cfpd30_rendiciones_partidas->findAll($conditions = $this->condicion()." and ano_rendicion='$ano_movimiento' and numero_rendicion='$numero_documento'", $fields = null, $order = 'ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar', $limit = null, $page = null, $recursive = null);
	    $this->set('datos', $datos);

	    $this->set('siguiente',$pagina+1);
	    $this->set('anterior',$pagina-1);
	    $this->bt_nav($Tfilas,$pagina);

	}else{
		$this->set('NOTA_DEBITO','');
	    $this->set('msg_error', 'No se encontrar&oacute;n datos en la consulta');
	}


}//FIN CONSULTA

function preanular(){
  $this->layout="ajax";

}

function anular($ano_rendicion=null, $numero_rendicion=null, $page=null){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst = $this->Session->read('SScodinst');
    $cod_dep = $this->Session->read('SScoddep');
    $username = $this->Session->read('nom_usuario');

	if($ano_rendicion != null && $numero_rendicion != null){

		$ccp = $concepto_anulacion = $this->data['cfpp30_rendicion']['concepto_anulacion'];
		$ano = $ano_rendicion;
		$fecha_proceso_anulacion = date('d/m/Y');

		if(isset($this->data['cfpp30_rendiciones']['num_cheque_cach'])){
			$cod_entidad_cach = $this->data['cfpp30_rendiciones']['cod_entidad_cach'];
			$cod_sucu_cach = $this->data['cfpp30_rendiciones']['cod_sucu_cach'];
			$cuenta_banc_cach = $this->data['cfpp30_rendiciones']['cuenta_banc_cach'];
			$num_cheque_cach = $this->data['cfpp30_rendiciones']['num_cheque_cach'];
		}else{
			$cod_entidad_cach = "";
			$cod_sucu_cach = "";
			$cuenta_banc_cach = "";
			$num_cheque_cach = "";
		}

		$partidas = $this->cfpd30_rendiciones_partidas->findAll($conditions = $this->condicion()." and ano_rendicion='$ano_rendicion' and numero_rendicion='$numero_rendicion'", $fields = null, $order = null, $limit = null, $page = null, $recursive = null);

		$fecha_rendicion    = $this->cfpd30_rendiciones_cuerpo->field('cfpd30_rendiciones_cuerpo.fecha_rendicion', $conditions, $order =null);
		$fd                 = $fecha_rendicion[8].$fecha_rendicion[9].'/'.$fecha_rendicion[5].$fecha_rendicion[6].'/'.$fecha_rendicion[0].$fecha_rendicion[1].$fecha_rendicion[2].$fecha_rendicion[3];



		$nro_acta_anulacion = $this->cugd03_acta_anulacion_numero->field('cugd03_acta_anulacion_numero.numero_acta_anulacion', $conditions = $this->condicion()." and ano_acta_anulacion='$ano'", $order=null);

		if(empty($nro_acta_anulacion)){
			$nro_acta_anulacion = 1;
			$sql_update_numero_anulacion = "INSERT INTO cugd03_acta_anulacion_numero VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano', '$nro_acta_anulacion');";
		}else{
			$nro_acta_anulacion += 1;
			$sql_update_numero_anulacion = "UPDATE cugd03_acta_anulacion_numero SET numero_acta_anulacion='$nro_acta_anulacion' WHERE ".$this->condicion()." and ano_acta_anulacion='$ano';";
		}
		$sql_insert_anulacion = "INSERT INTO cugd03_acta_anulacion_cuerpo VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano', '$nro_acta_anulacion', '253', '$ano', '$numero_rendicion', '$fd', '$concepto_anulacion');";
		$sw2 = $this->cugd03_acta_anulacion_numero->execute("BEGIN; ".$sql_update_numero_anulacion.$sql_insert_anulacion);


		if($sw2 > 1){
			$suma_para_contabilidad = 0;
			foreach($partidas as $row){
		        $ano = $row['cfpd30_rendiciones_partidas']['ano_rendicion'];
		        $numero_rendicion          = $row['cfpd30_rendiciones_partidas']['numero_rendicion'];
		        $ano                       = $row['cfpd30_rendiciones_partidas']['ano'];
		        $cod_sector                = $row['cfpd30_rendiciones_partidas']['cod_sector'];
		        $cod_programa              = $row['cfpd30_rendiciones_partidas']['cod_programa'];
		        $cod_sub_prog              = $row['cfpd30_rendiciones_partidas']['cod_sub_prog'];
		        $cod_proyecto              = $row['cfpd30_rendiciones_partidas']['cod_proyecto'];
		        $cod_activ_obra            = $row['cfpd30_rendiciones_partidas']['cod_activ_obra'];
		        $cod_partida               = $row['cfpd30_rendiciones_partidas']['cod_partida'];
		        $cod_generica              = $row['cfpd30_rendiciones_partidas']['cod_generica'];
		        $cod_especifica            = $row['cfpd30_rendiciones_partidas']['cod_especifica'];
		        $cod_sub_espec             = $row['cfpd30_rendiciones_partidas']['cod_sub_espec'];
		        $cod_auxiliar              = $row['cfpd30_rendiciones_partidas']['cod_auxiliar'];
		        $monto                     = $row['cfpd30_rendiciones_partidas']['monto'];
		        $numero_control_compromiso = $row['cfpd30_rendiciones_partidas']['numero_control_compromiso'];
		        $numero_control_causado    = $row['cfpd30_rendiciones_partidas']['numero_control_causado'];
		        $numero_control_pagado     = $row['cfpd30_rendiciones_partidas']['numero_control_pagado'];

		        $suma_para_contabilidad += $monto;

		        $cp = $this->crear_partida($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);

	         $num_asiento_compromiso = $this->motor_presupuestario($cp, 2, 3, 6, $fd, $monto, $ccp, $ano, $numero_rendicion, null, null, null, null, null, null, null, $numero_control_compromiso, null, null, null, null);
	          if($num_asiento_compromiso == true){
	              $num_asiento_causado = $this->motor_presupuestario($cp, 2, 4, 9, $fd, $monto, $ccp, $ano, $numero_rendicion, null, null, null, null, null, null, null, $numero_control_compromiso, $numero_control_causado, null, null, null);
	            if($num_asiento_causado == true){
	              $numero_control_pagado = $this->motor_presupuestario($cp, 2, 5, 3, $fd, $monto, $ccp, $ano, $numero_rendicion, null, null, null, null, null, null, null, $numero_control_compromiso, $numero_control_causado, $numero_control_pagado, null, null);
	              if($numero_control_pagado == true){
	              }else{
	                $this->cfpd05->execute("ROLLBACK;");
	                $this->set('msg_error', 'LO SIENTO NO SE LOGRO ANULAR LA RENDICION PRESUPUESTARIA');
	              }
	            }else{
	              $this->cfpd05->execute("ROLLBACK;");
	              $this->set('msg_error', 'LO SIENTO NO SE LOGRO ANULAR LA RENDICION PRESUPUESTARIA');
	            }
	          }else{
	            $this->cfpd05->execute("ROLLBACK;");
	            $this->set('msg_error', 'LO SIENTO NO SE LOGRO ANULAR LA RENDICION PRESUPUESTARIA');
	          }

			}

				$sw3 = $this->cfpd30_rendiciones_cuerpo->execute("UPDATE cfpd30_rendiciones_cuerpo SET condicion_actividad=2, username_anulacion='$username', numero_acta_anulacion='$nro_acta_anulacion', ano_acta_anulacion='$ano_rendicion', fecha_proceso_anulacion='$fecha_proceso_anulacion', ano_cach=0, cod_entidad_bancaria_cach=0, cod_sucursal_cach=0, cuenta_bancaria_cach='', numero_cheque_cach=0, rendicion_caja_chica=2 WHERE ".$this->condicion()." and ano_rendicion='$ano_rendicion' and numero_rendicion='$numero_rendicion';");
				if($sw3>1){

					if($num_cheque_cach!=""){
						$sw_cmm = $this->cstd03_movimientos_manuales->execute("UPDATE cstd03_movimientos_manuales SET caja_chica_rendida=2 WHERE ".$this->condicion()." and ano_movimiento='$ano_rendicion' and cod_entidad_bancaria='$cod_entidad_cach' and cod_sucursal='$cod_sucu_cach' and cuenta_bancaria='$cuenta_banc_cach' and tipo_documento=4 and numero_documento='$num_cheque_cach';");
					}

					if(isset($sw_cmm)){
						$sw_cmm2 = $sw_cmm;
					}else{
						$sw_cmm2 = $sw3;
					}

					if($sw_cmm2>1){

						$funcionario_responsable = $this->data['cfpp30_rendiciones']['funcionario_responsable'];
						$concepto                = $this->data['cfpp30_rendiciones']['concepto'];

						$cod_ent_pago_aux        = $this->data['cfpp30_rendiciones']['cod_entidad_bancaria'];
				        $cod_suc_pago_aux        = $this->data['cfpp30_rendiciones']['cod_sucursal_bancaria'];
				 	    $cod_cta_pago_aux        = $this->data['cfpp30_rendiciones']['cod_cuenta_bancaria'];

					  $valor_motor_contabilidad =  $this->motor_contabilidad_fiscal(
			                                                                         $to              = 2,
			                                                                         $td              = 19,
			                                                                         $rif_doc         = null,
			                                                                         $ano_dc          = $ano_rendicion,
			                                                                         $n_dc            = $numero_rendicion,
			                                                                         $f_dc            = date("d/m/Y"),
			                                                                         $cpt_dc          = $concepto,
			                                                                         $ben_dc          = $funcionario_responsable,
			                                                                         $mon_dc          = array("monto"  => $suma_para_contabilidad),

			                                                                         $ano_op          = null,
			                                                                         $n_op            = null,
			                                                                         $f_op            = null,

			                                                                         $a_adj_op        = null,
			                                                                         $n_adj_op        = null,
			                                                                         $f_adj_op        = null,
			                                                                         $tp_op           = null,

			                                                                         $deno_ban_pago   = null,
			                                                                         $ano_movimiento  = null,
			                                                                         $cod_ent_pago    = $cod_ent_pago_aux,
			                                                                         $cod_suc_pago    = $cod_suc_pago_aux,
			                                                                         $cod_cta_pago    = $cod_cta_pago_aux,

			                                                                         $num_che_o_debi  = null,
			                                                                         $fec_che_o_debi  = null,
			                                                                         $clas_che_o_debi = null
			                                                                     );

			                    if($valor_motor_contabilidad==true){
							        $this->cfpd30_rendiciones_partidas->execute("COMMIT;");
						        	$this->set('msg', 'LA RENDICION PRESUPUESTARIA FUE ANULADA CON EXITO');
						        }else{
									$this->cfpd30_rendiciones_partidas->execute("ROLLBACK;");
						            $this->set('msg_error', 'LO SIENTO NO SE LOGRO ANULAR LA RENDICION PRESUPUESTARIA');
						        }//fin else

					}else{
						$this->cfpd30_rendiciones_cuerpo->execute("ROLLBACK;");
						$this->set('msg_error', 'LO SIENTO NO SE LOGRO ANULAR LA RENDICION PRESUPUESTARIA');
					}
				}else{
					$this->cfpd30_rendiciones_cuerpo->execute("ROLLBACK;");
					$this->set('msg_error', 'LO SIENTO NO SE LOGRO ANULAR LA RENDICION PRESUPUESTARIA');
				}

		}else{
			$this->cugd03_acta_anulacion_numero->execute("ROLLBACK;");
		}

	}

	$this->consulta($page);
	$this->render('consulta');
}

function automatico($opc=null){
	$this->layout = "ajax";
	//echo $opc;
	if($opc != null){
		if($opc == 0){
			$readonly = "";
			$numero = "";
		}else{
			$readonly = "readonly";
			$ano = $this->ano_ejecucion();
			$numero_rendicion = $this->cfpd30_rendiciones_cuerpo->execute("SELECT MAX(numero_rendicion) as numero_rendicion FROM cfpd30_rendiciones_cuerpo WHERE ".$this->condicion()." and ano_rendicion=$ano;");
			//pr($numero_rendicion);
			$numero = $numero_rendicion[0][0]['numero_rendicion'] + 1;
		}
	}else{
		$readonly = "";
		$numero = "";
	}
	$this->set('numero', $this->zero($numero));
	$this->set('readonly', $readonly);

}


function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cfpp30_rendiciones']['login']) && isset($this->data['cfpp30_rendiciones']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cfpp30_rendiciones']['login']);
		$paswd=addslashes($this->data['cfpp30_rendiciones']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=85 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('msg_error',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('autor_valido',false);
			$this->index("autor_valido");
			$this->render("index");
		}
	}
}


}//FIN DE LA CLASE
?>
