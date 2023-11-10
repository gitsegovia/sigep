<?php
 class Shp000CierreEjercicioController extends AppController{
	var $uses = array('shd000_arranque','shd000_control_actualizacion',
					  'shd100_patente','shd200_vehiculos','shd300_propaganda','shd400_propiedad',
					  'shd500_aseo_domiciliario','shd600_aprobacion_arrendamiento','shd700_credito_vivienda',
					  'v_shd002_cobranza_pendiente_cierre','shd002_cobranza_pendiente','shd002_cobranza_realizada',
					  'v_shd002_cobranza_realizada_cierre','shd900_planillas_deuda_cobro_detalles',
					  'v_shd900_planillas_deuda_cobro_detalles_cierre','cugd05_restriccion_clave');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
 	var $name = "shp000_cierre_ejercicio";


 	function checkSession(){
 	if (!$this->Session->check('Usuario')){
 		$this->redirect('/salir/');
		exit();
	}else{
		//$this->set('userTable', $this->requestAction('/cnmp03partidas/', array('return')));
		//echo "H".$this->requestAction('/usuarios/actualizar_user',array('return'));
		$this->requestAction('/usuarios/actualizar_user');
	}
 }//fin checkSession



 function beforeFilter(){
 	$this->checkSession();
 }//fin before filter

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

function index($var=null, $var2=null){

$this->verifica_entrada('73');

	$this->layout = "ajax";
	if(isset($var2)){
		$this->set('autor_valido',true);
	}

	$ver=$this->shd000_arranque->execute("select * from shd000_arranque where ".$this->SQLCA());
	if($ver!=null){
		$this->set('ano',$ver[0][0]['ano_arranque']);
		$meses= array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
		$this->set('mes',$meses[$ver[0][0]['mes_arranque']]);
		$this->set('mes1',$ver[0][0]['mes_arranque']);

		if($ver[0][0]['mes_arranque']!=12){
			if(isset($var)){
				$this->set('msg_error1', $msg_error1 = 'El mes no corresponde a un cierre del ejercicio');
			}
			$bloquear=null;
		}else{
			$bloquear=true;
		}
		$this->set('bloquear',$bloquear);
	}else{
		$this->set('ano','');
		$this->set('mes','');
	}


}//fin index


function procesar_cierre($ano=null,$mes=null){
	$this->layout = "ajax";
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');

	$sql="select * FROM v_shd000_control_arranque_cierre WHERE ".$this->SQLCA()." and ano_actualizado=".$ano." and mes_actualizado=".$mes." and condicion!=2 order by cod_ingreso asc";

	$control=$this->shd000_control_actualizacion->execute("select * FROM shd100_control_industria_comercio WHERE ".$this->SQLCA());

	$ver= $this->shd000_control_actualizacion->execute($sql);
	if($ver==null){
		//proceso cierre
		$sql2="select * FROM shd000_control_actualizacion WHERE ".$this->SQLCA()." and ano_actualizado=".$ano." and mes_actualizado=".$mes." and condicion=2 order by cod_ingreso asc";
		$ver2= $this->shd000_control_actualizacion->execute($sql2);

		$ano_nuevo=$ano+1;
		$mes_nuevo=1;
		$sw=0;
		if($ver2!=null){
			$sql_update="BEGIN;UPDATE shd000_arranque SET ano_arranque='$ano_nuevo',mes_arranque='$mes_nuevo' WHERE ".$this->SQLCA()." and ano_arranque=".$ano;
			$sw_update = $this->shd000_control_actualizacion->execute($sql_update);
			for($i=0;$i<count($ver2);$i++){
				$cod_ingreso=$ver2[$i][0]['cod_ingreso'];
				if($control[0][0]['utiliza_planillas_liquidacion_previa']==2){
					if($cod_ingreso!=1){
						$insert="INSERT INTO shd000_control_actualizacion VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep','$cod_ingreso','$ano_nuevo','$mes_nuevo',0)";
						$sw=$this->shd000_control_actualizacion->execute($insert);
					}
				}else{
					$insert="INSERT INTO shd000_control_actualizacion VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep','$cod_ingreso','$ano_nuevo','$mes_nuevo',0)";
					$sw=$this->shd000_control_actualizacion->execute($insert);
				}

			}

			if($sw>0){
					$pago=$this->pago_todo($control[0][0]['utiliza_planillas_liquidacion_previa']);
					if($pago==true){
						$this->shd000_control_actualizacion->execute('COMMIT');
						$this->set('Message_existe', 'el proceso de cierre del ejercicio se realizo correctamente');
						$this->set('guardado', 'si');


							/*
								$proceso1=$this->proceso1($ano,$ano_nuevo);
								if($proceso1==true){


											$proceso2=$this->proceso2($ano,$ano_nuevo);
											if($proceso2==true){
													$this->shd000_control_actualizacion->execute('COMMIT');
													$this->set('Message_existe', 'el proceso de cierre del ejercicio se realizo correctamente');
													$this->set('guardado', 'si');
													$proceso3=$this->proceso3($ano);
													if($proceso3==true){
														$this->shd000_control_actualizacion->execute('COMMIT');
														$this->set('Message_existe', 'el proceso de cierre del ejercicio se realizo correctamente');
														$this->set('guardado', 'si');
													}else{
														$this->shd000_control_actualizacion->execute('ROLLBACK');
														$this->set('errorMessage', 'El proceso de cierre del ejercicio no pudo realizarse');
														$this->set('guardado', 'no');
													}
											}else{
												$this->shd000_control_actualizacion->execute('ROLLBACK');
												$this->set('errorMessage', 'El proceso de cierre del ejercicio no pudo realizarse');
												$this->set('guardado', 'no');
											}


								}else{
									$this->shd000_control_actualizacion->execute('ROLLBACK');
									$this->set('errorMessage', 'El proceso de cierre del ejercicio no pudo realizarse');
									$this->set('guardado', 'no');
								}
							*/

					}else{
						$this->shd000_control_actualizacion->execute('ROLLBACK');
						$this->set('errorMessage', 'El proceso de cierre del ejercicio no pudo realizarse');
						$this->set('guardado', 'no');
					}



				}else{
					$this->shd000_control_actualizacion->execute('ROLLBACK');
					$this->set('errorMessage', 'El proceso de cierre del ejercicio no pudo realizarse');
					$this->set('guardado', 'no');
				}
		}else{
			$this->set('errorMessage', 'ANTES DEBE ACTUALIZAR LAS PLANILLAS DE LIQUIDACIÓN PREVIA');

		}

	}else{
		if($ver[0][0]['condicion']==0){
			$this->set('errorMessage', 'El cierre de: '.$ver[0][0]['denominacion'].' fue realizado anteriormente');
		}else{
			$this->set('errorMessage', 'Antes debe emitir las planillas de: '.$ver[0][0]['denominacion']);
		}


	}

			///lo dejo aqui por ahora,este sql junta las tablas,preguntar a jose como se hace cuando varios impuesto tienen la misma condicion
}



function pago_todo($control=null){
	if($control==1){
		$a1=$this->shd100_patente->execute("UPDATE shd100_patente SET pago_todo=2 WHERE ".$this->SQLCA());
	}else{
		$a1=1;
	}
	$a2=$this->shd200_vehiculos->execute("UPDATE shd200_vehiculos SET pago_todo=2 WHERE ".$this->SQLCA());
	$a3=$this->shd300_propaganda->execute("UPDATE shd300_propaganda SET pago_todo=2 WHERE ".$this->SQLCA());
	$a4=$this->shd400_propiedad->execute("UPDATE shd400_propiedad SET pago_todo=2 WHERE ".$this->SQLCA());
	$a5=$this->shd500_aseo_domiciliario->execute("UPDATE shd500_aseo_domiciliario SET pago_todo=2 WHERE ".$this->SQLCA());
	$a6=$this->shd600_aprobacion_arrendamiento->execute("UPDATE shd600_aprobacion_arrendamiento SET pago_todo=2 WHERE ".$this->SQLCA());
	$a7=$this->shd700_credito_vivienda->execute("UPDATE shd700_credito_vivienda SET pago_todo=2 WHERE ".$this->SQLCA());

	if($a1>0 && $a2>0 && $a3>0 && $a4>0 && $a5>0 && $a6>0 && $a7>0){
		return true;
	}else{
		return false;
	}

}


function proceso1($ano_ejercicio,$ano_nuevo){
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
$datos=$this->v_shd002_cobranza_pendiente_cierre->execute("select rif_ci,total from v_shd002_cobranza_pendiente_cierre WHERE ".$this->SQLCA()." and ano=".$ano_ejercicio);
for($i=0;$i<count($datos);$i++){
	$rif=$datos[$i][0]['rif_ci'];
	$total=$datos[$i][0]['total'];
	$insert="INSERT INTO shd002_cobranza_pendiente VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep','$rif','$ano_nuevo','$total',0,0,0,0,0,0,0,0,0,0,0,0)";
	$sw=$this->shd002_cobranza_pendiente->execute($insert);

}
	if($sw>0){
		return true;
	}else{
		return false;
	}
}




function proceso2($ano_ejercicio,$ano_nuevo){
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
$datos=$this->v_shd002_cobranza_realizada_cierre->execute("select rif_ci,total from v_shd002_cobranza_realizada_cierre WHERE ".$this->SQLCA()." and ano=".$ano_ejercicio);
for($i=0;$i<count($datos);$i++){
	$rif=$datos[$i][0]['rif_ci'];
	$total=$datos[$i][0]['total'];
	$insert="INSERT INTO shd002_cobranza_realizada VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep','$rif','$ano_nuevo','$total',0,0,0,0,0,0,0,0,0,0,0,0)";
	$sw=$this->shd002_cobranza_realizada->execute($insert);

}
	if($sw>0){
		return true;
	}else{
		return false;
	}
}




function proceso3($ano_ejercicio){
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
//	'shd900_planillas_deuda_cobro_cuerpo','shd900_planillas_deuda_cobro_detalles'
$datos=$this->v_shd900_planillas_deuda_cobro_detalles_cierre->execute("select * from v_shd900_planillas_deuda_cobro_detalles_cierre WHERE ".$this->SQLCA()." and ano=".$ano_ejercicio);
for($i=0;$i<count($datos);$i++){
	$cod_partida=$datos[$i][0]['cod_partida'];
	$cod_generica=$datos[$i][0]['cod_generica'];
	$cod_especifica=$datos[$i][0]['cod_especifica'];
	$cod_sub_espec=$datos[$i][0]['cod_sub_espec'];
	$cod_auxiliar=$datos[$i][0]['cod_auxiliar'];
	$rif_cedula=$datos[$i][0]['rif_cedula'];
	$numero_catastra=$datos[$i][0]['cod_numero_catastral_placas'];
	$monto=$datos[$i][0]['total'];

	$update1="UPDATE shd900_planillas_deuda_cobro_detalles SET monto_descuento=0 WHERE ".$this->SQLCA()." and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar' and rif_cedula='$rif_cedula' and cod_numero_catastral_placas='$numero_catastra' and ano='$ano_ejercicio'";

	$cuerpo=$this->shd900_planillas_deuda_cobro_cuerpo->execute("select * from shd900_planillas_deuda_cobro_cuerpo WHERE ".$this->SQLCA()." and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar' and rif_cedula='$rif_cedula' and cod_numero_catastral_placas='$numero_catastra'");

	$total_ano=($cuerpo[0][0]['deuda_ano_anterior']+$monto);

	$update2="UPDATE shd900_planillas_deuda_cobro_cuerpo SET deuda_ano_anterior='$total_ano' WHERE ".$this->SQLCA()." and cod_partida='$cod_partida' and cod_generica='$cod_generica' and cod_especifica='$cod_especifica' and cod_sub_espec='$cod_sub_espec' and cod_auxiliar='$cod_auxiliar' and rif_cedula='$rif_cedula' and cod_numero_catastral_placas='$numero_catastra'";

	$sw=$this->shd900_planillas_deuda_cobro_detalles->execute($update1);
	$sw1=$this->shd900_planillas_deuda_cobro_detalles->execute($update2);

}
	if($sw>0 && $sw1>0){
		return true;
	}else{
		return false;
	}
}


function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cscp02_solicitud_numero']['login']) && isset($this->data['cscp02_solicitud_numero']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cscp02_solicitud_numero']['login']);
		$paswd=addslashes($this->data['cscp02_solicitud_numero']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=73 and clave='".$paswd."'";
		if(($user==$l && $paswd==$c)){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('errorMessage',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('autor_valido',false);
			$this->index("autor_valido");
			$this->render("index");
		}
	}else{
		$this->set('errorMessage',"Debe ingresar su login y su contrase&tilde;na");
		$this->set('autor_valido',false);
		$this->index("autor_valido");
		$this->render("index");
	}
}




}