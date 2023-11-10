<?php

 class Shp000ScriptHaciendaController extends AppController{
 	var $name = "shp000_script_hacienda";
	var $uses = array('v_shd001_registro_contribuyentes','shd001_registro_contribuyentes','shd900_cobranza_diaria','shd900_cobranza_numero',
                       'shd100_actividades', 'cnmd06_profesiones','cugd01_republica', 'cugd01_estados','ccfd04_cierre_mes','shd900_cobranza_acumulada',
                      'cugd01_municipios', 'cugd01_parroquias', 'cugd01_centropoblados', 'cugd01_vialidad', 'cugd01_vereda','v_shd900_cobranza_diaria',
                      'cstd02_cuentas_bancarias','cstd01_entidades_bancarias','cstd01_sucursales_bancarias','v_consulta_ingreso','v_cobranza_diaria','shd002_cobradores','shd000_arranque','cfpd03',
                      'shd900_planillas_deuda_cobro_detalles','shd002_cobranza_pendiente','v_shd900_planillas_deuda_cobro_detalles_cobradores','shd100_patente');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap','Form');

	function checkSession(){
		if (!$this->Session->check('Usuario')){
			$this->redirect('/salir/');
			exit();
		}else{
			$this->requestAction('/usuarios/actualizar_user');
		}
	}

	function beforeFilter(){
		$this->checkSession();
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
    	}
    }

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
    }

	function SQLCX($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)." ";
		 return $sql_re;
	}

	function SQLCAX($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_republica=".$this->verifica_SS(1)."  and    ";
         return $sql_re;
	}

	function zero($x=null){
		if($x != null){
			if($x<10){
				$x="0".$x;
			}else if($x>=10 && $x<=99){
				$x=$x;
			}
		}
		return $x;
	}

	function concatena($vector1=null, $nomVar=null){
		if($vector1 != null){
			foreach($vector1 as $x => $y){
				$cod[$x] = $this->zero($x).' - '.$y;
			}
			$this->set($nomVar, $cod);
		}
	}

	function index($var=null){
		$this->layout = "ajax";
	}

	/**
	 * Function: vaciar_planillas_deuda_cobro_detalles
	 * Descripcion:
	 * 		FUNCION GENERADA PARA CANTAURA, BUSCA EN LA TABLA shd900_planillas_deuda_cobro_detalles
	 * 		EN LA MEDIDA QUE VA PRESENTANDO Y ELIMINADO LOS REGISTROS SE DEBE ACTUALIZAR EN LA TABLA shd100_patente
	 * 		LOS CAMPOS ultimo_ano_facturado Y ultimo_mes_facturado DEBEN QUEDAR LIMPIOS Y EN LA TABLA shd002_cobranza_pendiente
	 * 		SE DEBE REBAJAR EN EL CAMPO monto_enero EL MONTO DE LA PLANILLA DE LA TABLA shd900_planillas_deuda_cobro_detalles
	 * NOTA: Deben cargarse manualmente los codigo de la institucion a procesar.
	 * */
	function vaciar_planillas_deuda_cobro_detalles(){
		$cp  = 0;
		$ce  = 0;
		$cti = 0;
		$ci  = 0;
		$cd  = 0;

		$planillas_deuda = $this->v_shd900_planillas_deuda_cobro_detalles_cobradores->findAll("cod_presi='$cp' AND cod_entidad='$ce' AND cod_tipo_inst='$cti' AND cod_inst='$ci' AND cod_dep='$cd'", 'cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_cedula, ano, mes, numero_planilla, deuda_vigente, cancelado, rif_cobrador_1');
		foreach($planillas_deuda as $p){
			$cod_presi  		= $p['v_shd900_planillas_deuda_cobro_detalles_cobradores']['cod_presi'];
			$cod_entidad  		= $p['v_shd900_planillas_deuda_cobro_detalles_cobradores']['cod_entidad'];
			$cod_tipo_inst 		= $p['v_shd900_planillas_deuda_cobro_detalles_cobradores']['cod_tipo_in'];
			$cod_inst  			= $p['v_shd900_planillas_deuda_cobro_detalles_cobradores']['cod_inst'];
			$cod_dep  			= $p['v_shd900_planillas_deuda_cobro_detalles_cobradores']['cod_dep'];

			$rif_cedula  		= $p['v_shd900_planillas_deuda_cobro_detalles_cobradores']['rif_cedula'];
			$ano  				= $p['v_shd900_planillas_deuda_cobro_detalles_cobradores']['ano'];
			$mes 				= $p['v_shd900_planillas_deuda_cobro_detalles_cobradores']['mes'];
			$numero_planilla  	= $p['v_shd900_planillas_deuda_cobro_detalles_cobradores']['numero_plan'];
			$deuda_vigente  	= $p['v_shd900_planillas_deuda_cobro_detalles_cobradores']['deuda_vigen'];
			$cancelado  		= $p['v_shd900_planillas_deuda_cobro_detalles_cobradores']['cancelado'];
			$rif_cobrador  		= $p['v_shd900_planillas_deuda_cobro_detalles_cobradores']['rif_cobrado'];

			$condicion =  "cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep'";

			echo "<br />";
			echo $sql_update_patente = "UPDATE shd100_patente SET ultimo_ano_facturado=0, ultimo_mes_facturado=0 WHERE ".$condicion." AND rif_cedula='$rif_cedula'";
			if($this->shd100_patente->execute($sql_update_patente)>0){
				echo "<br/>Se actualizo la patente";
				$cobranza_pend = '';
				$cobranza_pend = $this->shd002_cobranza_pendiente->findAll($condicion." AND rif_ci='$rif_cobrador'", 'rif_ci, enero');
				if(isset($cobranza_pend[0]['shd002_cobranza_pendiente']['rif_ci'])){
					$rif_ci = $cobranza_pend[0]['shd002_cobranza_pendiente']['rif_ci'];
					$monto_enero = $cobranza_pend[0]['shd002_cobranza_pendiente']['enero'];
					$nuevo_monto_enero = $monto_enero - $deuda_vigente;
					$sql_update_cobranza = "UPDATE shd002_cobranza_pendiente SET enero='$nuevo_monto_enero' WHERE ".$condicion." AND rif_ci='$rif_ci'";
					if($this->shd002_cobranza_pendiente->execute($sql_update_cobranza)>0){
						echo "<br/>Se actualizo la cobranza pendiente en enero";
					}
				}
			}
		}
	}


 }
?>