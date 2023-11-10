<?php

class ReporteContabilidadController extends AppController{


    var $name    = "reporte_contabilidad";
    var $uses    = array('ccfd04_cierre_mes','ccfd02','cugd07_firmas_oficio_anulacion','ccfd01_cuenta','ccfd10_descripcion','ccfd10_detalles',
    					'ccfd01_tipo','ccfd01_cuenta','ccfd01_subcuenta','ccfd01_division','ccfd01_subdivision');

// agregar estos modelos para los reportes de hacienda	'shd100_patente','shd001_registro_contribuyentes','shd100_solicitud','v_shd100_declaracion_ingreso'

    var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap');

//,'v_balance_ejecucion_partidas_inst','v_balance_ejecucion_partidas_dep'




function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
}//fin checksession




   function ultimoDiaMes($mes,$año){
      for ($dia=28;$dia<=31;$dia++)
         if(checkdate($mes,$dia,$año)) $fecha="$dia";
      return $fecha;
   }




function beforeFilter(){$this->checkSession();}



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


function balance_comprobacion_cuentas_mayor($ir=null){
	if($ir=='no'){
		$this->layout="ajax";

		$this->set('year',$this->ano_ejecucion());
		$this->set('m',date('m'));
		$meses= array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
		$this->concatena($meses, 'mes');
		$this->set('ir','no');

		$cont = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA()." and tipo_documento='700'");

    	if($cont==0){
    		$this->set('nombre_primera_firma','');
			$this->set('cargo_primera_firma','');
			$this->set('nombre_segunda_firma','');
			$this->set('cargo_segunda_firma','');
			$this->set('nombre_tercera_firma','');
			$this->set('cargo_tercera_firma','');
			$this->set('tipo_doc_anul',700);
			$this->set('firma',1);

    	}else{
			$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=700");
			$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
			$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
			$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
			$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
			$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
			$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
			$this->set('tipo_doc_anul',700);
			$this->set('firma',2);
    	}

	}else if($ir=='si'){
		$this->layout="pdf";
		$this->set('ir','si');
		$filtro='';


		if(!empty($this->data['cfpp00']['ano']) && !empty($this->data['cfpp00']['mes'])){
			$ano=$this->data['cfpp00']['ano'];
			$mes=$this->data['cfpp00']['mes'];
			if(isset($this->data['cfpp00']['peticion'])){
				if($this->data['cfpp00']['peticion']==1){
//					$filtro=$this->condicionNDEP();
					$filtro=$this->SQLCA_consolidado($this->data['cfpp00']['peticion']);
					$group="a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.ano_fiscal,a.cod_tipo_cuenta,a.cod_cuenta";
				}else{
//					$filtro=$this->SQLCA();
					$filtro=$this->SQLCA_consolidado($this->data['cfpp00']['peticion']);
					$group="a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.ano_fiscal,a.cod_tipo_cuenta,a.cod_cuenta";
				}
			}else{
//				$filtro=$this->SQLCA();
				$filtro=$this->SQLCA_consolidado(2);
				$group="a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.ano_fiscal,a.cod_tipo_cuenta,a.cod_cuenta";
			}
//			echo "aaaaaaaaa<br>".$filtro.=" and ano_fiscal=".$ano;
			$filtro.=" and ano_fiscal=".$ano;

			switch($mes){
				case 1:
					//////SALDOS ANTERIORES////////////
					$debito_anterior='debito_acumulado';
					$credito_anterior='credito_acumulado';
					/////MOVIMIENTOS DEL MES///////////
					$debito_mes='debito_ene';
					$credito_mes='credito_ene';
					////TOTALES///////////////////////
					$debito_total='total_debito_acumulado_ene';
					$credito_total='total_credito_acumulado_ene';
					$name_mes='ENERO';
				break;
				case 2:
					//////SALDOS ANTERIORES////////////
					$debito_anterior='total_debito_acumulado_ene';
					$credito_anterior='total_credito_acumulado_ene';
					/////MOVIMIENTOS DEL MES///////////
					$debito_mes='debito_feb';
					$credito_mes='credito_feb';
					////TOTALES///////////////////////
					$debito_total='total_debito_acumulado_feb';
					$credito_total='total_credito_acumulado_feb';
					$name_mes='FEBRERO';
				break;
				case 3:
					//////SALDOS ANTERIORES////////////
					$debito_anterior='total_debito_acumulado_feb';
					$credito_anterior='total_credito_acumulado_feb';
					/////MOVIMIENTOS DEL MES///////////
					$debito_mes='debito_mar';
					$credito_mes='credito_mar';
					////TOTALES///////////////////////
					$debito_total='total_debito_acumulado_mar';
					$credito_total='total_credito_acumulado_mar';
					$name_mes='MARZO';
				break;
				case 4:
					//////SALDOS ANTERIORES////////////
					$debito_anterior='total_debito_acumulado_mar';
					$credito_anterior='total_credito_acumulado_mar';
					/////MOVIMIENTOS DEL MES///////////
					$debito_mes='debito_abr';
					$credito_mes='credito_abr';
					////TOTALES///////////////////////
					$debito_total='total_debito_acumulado_abr';
					$credito_total='total_credito_acumulado_abr';
					$name_mes='ABRIL';
				break;
				case 5:
					//////SALDOS ANTERIORES////////////
					$debito_anterior='total_debito_acumulado_abr';
					$credito_anterior='total_credito_acumulado_abr';
					/////MOVIMIENTOS DEL MES///////////
					$debito_mes='debito_may';
					$credito_mes='credito_may';
					////TOTALES///////////////////////
					$debito_total='total_debito_acumulado_may';
					$credito_total='total_credito_acumulado_may';
					$name_mes='MAYO';
				break;
				case 6:
					//////SALDOS ANTERIORES////////////
					$debito_anterior='total_debito_acumulado_may';
					$credito_anterior='total_credito_acumulado_may';
					/////MOVIMIENTOS DEL MES///////////
					$debito_mes='debito_jun';
					$credito_mes='credito_jun';
					////TOTALES///////////////////////
					$debito_total='total_debito_acumulado_jun';
					$credito_total='total_credito_acumulado_jun';
					$name_mes='JUNIO';
				break;
				case 7:
					//////SALDOS ANTERIORES////////////
					$debito_anterior='total_debito_acumulado_jun';
					$credito_anterior='total_credito_acumulado_jun';
					/////MOVIMIENTOS DEL MES///////////
					$debito_mes='debito_jul';
					$credito_mes='credito_jul';
					////TOTALES///////////////////////
					$debito_total='total_debito_acumulado_jul';
					$credito_total='total_credito_acumulado_jul';
					$name_mes='JULIO';
				break;
				case 8:
					//////SALDOS ANTERIORES////////////
					$debito_anterior='total_debito_acumulado_jul';
					$credito_anterior='total_credito_acumulado_jul';
					/////MOVIMIENTOS DEL MES///////////
					$debito_mes='debito_ago';
					$credito_mes='credito_ago';
					////TOTALES///////////////////////
					$debito_total='total_debito_acumulado_ago';
					$credito_total='total_credito_acumulado_ago';
					$name_mes='AGOSTO';
				break;
				case 9:
					//////SALDOS ANTERIORES////////////
					$debito_anterior='total_debito_acumulado_ago';
					$credito_anterior='total_credito_acumulado_ago';
					/////MOVIMIENTOS DEL MES///////////
					$debito_mes='debito_sep';
					$credito_mes='credito_sep';
					////TOTALES///////////////////////
					$debito_total='total_debito_acumulado_sep';
					$credito_total='total_credito_acumulado_sep';
					$name_mes='SEPTIEMBRE';
				break;
				case 10:
					//////SALDOS ANTERIORES////////////
					$debito_anterior='total_debito_acumulado_sep';
					$credito_anterior='total_credito_acumulado_sep';
					/////MOVIMIENTOS DEL MES///////////
					$debito_mes='debito_oct';
					$credito_mes='credito_oct';
					////TOTALES///////////////////////
					$debito_total='total_debito_acumulado_oct';
					$credito_total='total_credito_acumulado_oct';
					$name_mes='OCTUBRE';
				break;
				case 11:
					//////SALDOS ANTERIORES////////////
					$debito_anterior='total_debito_acumulado_oct';
					$credito_anterior='total_credito_acumulado_oct';
					/////MOVIMIENTOS DEL MES///////////
					$debito_mes='debito_nov';
					$credito_mes='credito_nov';
					////TOTALES///////////////////////
					$debito_total='total_debito_acumulado_nov';
					$credito_total='total_credito_acumulado_nov';
					$name_mes='NOVIEMBRE';
				break;
				case 12:
					//////SALDOS ANTERIORES////////////
					$debito_anterior='total_debito_acumulado_nov';
					$credito_anterior='total_credito_acumulado_nov';
					/////MOVIMIENTOS DEL MES///////////
					$debito_mes='debito_dic';
					$credito_mes='credito_dic';
					////TOTALES///////////////////////
					$debito_total='total_debito_acumulado_dic';
					$credito_total='total_credito_acumulado_dic';
					$name_mes='DICIEMBRE';
				break;
			}//fin switch
			$this->set('debito_anterior',$debito_anterior);
			$this->set('credito_anterior',$credito_anterior);
			$this->set('debito_mes',$debito_mes);
			$this->set('credito_mes',$credito_mes);
			$this->set('debito_total',$debito_total);
			$this->set('credito_total',$credito_total);
			$this->Session->write('name_mes',$name_mes);
			$this->Session->write('valor_ano',$ano);

			$firmantes= $this->cugd07_firmas_oficio_anulacion->execute("select * from cugd07_firmas_oficio_anulacion where ".$this->SQLCA()." and tipo_documento=700");
			$this->Session->write('firma1',$firmantes[0][0]['nombre_primera_firma']);
			$this->Session->write('firma2',$firmantes[0][0]['nombre_segunda_firma']);
			$this->Session->write('firma3',$firmantes[0][0]['cargo_primera_firma']);
			$this->Session->write('firma4',$firmantes[0][0]['cargo_segunda_firma']);
			$sql="SELECT ".$group."
						,(select b.denominacion from ccfd01_cuenta b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=1 and b.cod_tipo_cuenta=a.cod_tipo_cuenta and b.cod_cuenta=a.cod_cuenta) as denominacion_cuenta,
						sum(a.debito_acumulado) as debito_acumulado,
						sum(a.credito_acumulado) as credito_acumulado,
						sum(a.debito_ene) as debito_ene,
						sum(a.credito_ene) as credito_ene,
						sum(a.debito_feb) as debito_feb,
						sum(a.credito_feb) as credito_feb,
						sum(a.debito_mar) as debito_mar,
						sum(a.credito_mar) as credito_mar,
						sum(a.debito_abr) as debito_abr,
						sum(a.credito_abr) as credito_abr,
						sum(a.debito_may) as debito_may,
						sum(a.credito_may) as credito_may,
						sum(a.debito_jun) as debito_jun,
						sum(a.credito_jun) as credito_jun,
						sum(a.debito_jul) as debito_jul,
						sum(a.credito_jul) as credito_jul,
						sum(a.debito_ago) as debito_ago,
						sum(a.credito_ago) as credito_ago,
						sum(a.debito_sep) as debito_sep,
						sum(a.credito_sep) as credito_sep,
						sum(a.debito_oct) as debito_oct,
						sum(a.credito_oct) as credito_oct,
						sum(a.debito_nov) as debito_nov,
						sum(a.credito_nov) as credito_nov,
						sum(a.debito_dic) as debito_dic,
						sum(a.credito_dic) as credito_dic,
						sum(a.debito_acumulado + a.debito_ene)::numeric(22,2) AS total_debito_acumulado_ene,
						sum(a.credito_acumulado + a.credito_ene)::numeric(22,2) AS total_credito_acumulado_ene,
						sum(a.debito_acumulado + a.debito_ene + a.debito_feb)::numeric(22,2) AS total_debito_acumulado_feb,
						sum(a.credito_acumulado + a.credito_ene + a.credito_feb)::numeric(22,2) AS total_credito_acumulado_feb,
						sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar)::numeric(22,2) AS total_debito_acumulado_mar,
						sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar)::numeric(22,2) AS total_credito_acumulado_mar,
						sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr)::numeric(22,2) AS total_debito_acumulado_abr,
						sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr)::numeric(22,2) AS total_credito_acumulado_abr,
						sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may)::numeric(22,2) AS total_debito_acumulado_may,
						sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may)::numeric(22,2) AS total_credito_acumulado_may,
						sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun)::numeric(22,2) AS total_debito_acumulado_jun,
						sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun)::numeric(22,2) AS total_credito_acumulado_jun,
						sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul)::numeric(22,2) AS total_debito_acumulado_jul,
						sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul)::numeric(22,2) AS total_credito_acumulado_jul,
						sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago)::numeric(22,2) AS total_debito_acumulado_ago,
						sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago)::numeric(22,2) AS total_credito_acumulado_ago,
						sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago + a.debito_sep)::numeric(22,2) AS total_debito_acumulado_sep,
						sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago + a.credito_sep)::numeric(22,2) AS total_credito_acumulado_sep,
						sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago + a.debito_sep + a.debito_oct)::numeric(22,2) AS total_debito_acumulado_oct,
						sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago + a.credito_sep + a.credito_oct)::numeric(22,2) AS total_credito_acumulado_oct,
						sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago + a.debito_sep + a.debito_oct + a.debito_nov)::numeric(22,2) AS total_debito_acumulado_nov,
						sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago + a.credito_sep + a.credito_oct + a.credito_nov)::numeric(22,2) AS total_credito_acumulado_nov,
						sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago + a.debito_sep + a.debito_oct + a.debito_nov + a.debito_dic)::numeric(22,2) AS total_debito_acumulado_dic,
						sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago + a.credito_sep + a.credito_oct + a.credito_nov + a.credito_dic)::numeric(22,2) AS total_credito_acumulado_dic
						  FROM ccfd02 a WHERE ".$filtro."
						  GROUP BY ".$group."
						  ORDER BY a.cod_tipo_cuenta,a.cod_cuenta ASC";

			$datos=$this->ccfd02->execute($sql);
			/*$datos11=$this->ccfd02->execute("select * from aa_contabilidad1 where ano_fiscal=2009");
			$verifica=0;
			$debe_saldo=0;
			$debe_saldo1=0;
			$debe_saldo11=0;
			$vector=array();
			$k=0;
				for($i=0;$i<count($datos11);$i++){

					$verifica1=$datos11[$i][0]['cod_dep'];
					if($verifica!=$verifica1){
						if($verifica==0){
//							$debe_saldo+=$saldo;
						}else{
							$vector[$k]['cod_dep']=$verifica;
							$vector[$k]['total_ene']=$debe_saldo;
							$vector[$k]['total_feb']=$debe_saldo1;
							$vector[$k]['saldo']=$debe_saldo11;
							$debe_saldo=0;
							$debe_saldo1=0;
							$debe_saldo11=0;
//							$debe_saldo+=$saldo;
							$k++;
						}

						$debe=$datos11[$i][0]['debito_ene'];
						$haber=$datos11[$i][0]['credito_ene'];
						if($debe>$haber){
							$saldo=($debe-$haber);
							$debe_saldo+=$saldo;
						}

						$debe1=$datos11[$i][0]['debito_feb'];
						$haber1=$datos11[$i][0]['credito_feb'];
						if($debe1>$haber1){
							$saldo1=($debe1-$haber1);
							$debe_saldo1+=$saldo1;
						}

						$debe11=$datos11[$i][0]['total_debito_acumulado_feb'];
						$haber11=$datos11[$i][0]['total_credito_acumulado_feb'];
						if($debe11>$haber11){
							$saldo11=($debe11-$haber11);
							$debe_saldo11+=$saldo11;
						}

						$verifica=$verifica1;

					}else{
						$debe=$datos11[$i][0]['debito_ene'];
						$haber=$datos11[$i][0]['credito_ene'];
						if($debe>$haber){
							$saldo=($debe-$haber);
							$debe_saldo+=$saldo;
						}

						$debe1=$datos11[$i][0]['debito_feb'];
						$haber1=$datos11[$i][0]['credito_feb'];
						if($debe1>$haber1){
							$saldo1=($debe1-$haber1);
							$debe_saldo1+=$saldo1;
						}

						$debe11=$datos11[$i][0]['total_debito_acumulado_feb'];
						$haber11=$datos11[$i][0]['total_credito_acumulado_feb'];
						if($debe11>$haber11){
							$saldo11=($debe11-$haber11);
							$debe_saldo11+=$saldo11;
						}
//							$debe_saldo+=$saldo;
							$verifica=$verifica1;



					}

				}
					$vector[$k]['cod_dep']=$verifica;
					$vector[$k]['total_ene']=$debe_saldo;
					$vector[$k]['total_feb']=$debe_saldo1;
					$vector[$k]['saldo']=$debe_saldo11;
				pr($vector);

				for($i=0;$i<count($vector);$i++){

					$suma=$vector[$i]['total_ene']+$vector[$i]['total_feb'];
					if($vector[$i]['saldo']!=$suma){
						$resto=$suma-$vector[$i]['saldo'];
						echo "<br> ALERTA ESTOS SON: cod_dep=".$vector[$i]['cod_dep']." meses=".$this->Formato2($suma)."  saldo=".$this->Formato2($vector[$i]['saldo'])." ***  ".$this->Formato2($resto)."<br> ";
					}
				}*/

			if($datos!=null){
				$this->set('datos',$datos);
			}else{
				$this->set('datos',null);
			}
		}else{
//			echo'<script>history.back(1);</script>';
			$this->set('datos',null);
		}//fin if ano y mes vacio
	}//fin ir si


}//fin funcion


function guardar_firmas_balance_mayor($var=null){
	$this->layout="ajax";
	$cp  = $this->Session->read('SScodpresi');
	$ce  = $this->Session->read('SScodentidad');
	$cti = $this->Session->read('SScodtipoinst');
	$ci  = $this->Session->read('SScodinst');
	$cd  = $this->Session->read('SScoddep');

	if(!empty($this->data['cfpp00']['nombre_primera_firma']) && !empty($this->data['cfpp00']['cargo_primera_firma']) && !empty($this->data['cfpp00']['nombre_segunda_firma']) && !empty($this->data['cfpp00']['cargo_segunda_firma'])){
		$tipo_doc_anul = $this->data['cugp03_acta_anulacion']['tipo_doc_anul'];

		$tipo_doc_anul=700;
		$nombre_primera_firma = $this->data['cfpp00']['nombre_primera_firma'];
		$cargo_primera_firma  = $this->data['cfpp00']['cargo_primera_firma'];
		$nombre_segunda_firma = $this->data['cfpp00']['nombre_segunda_firma'];
		$cargo_segunda_firma  = $this->data['cfpp00']['cargo_segunda_firma'];
		$nombre_tercera_firma = "0";
		$cargo_tercera_firma  = "0";
		$nombre_cuarta_firma = "0";
		$cargo_cuarta_firma  = "0";

		$primera_cc = "0";
		$segunda_cc = "0";
		$tercera_cc = "0";
		$cuarta_cc  = "0";
		$quinta_cc  = "0";
		$sexta_cc   = "0";
		$septima_cc = "0";
		$octava_cc  = "0";


		$cont = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA()." and tipo_documento='700'");
    	if($cont==0){
    		$insert = "INSERT INTO cugd07_firmas_oficio_anulacion VALUES ($cp, $ce, $cti, $ci, $cd, $tipo_doc_anul,'$nombre_primera_firma', '$cargo_primera_firma', '$nombre_segunda_firma', '$cargo_segunda_firma', '$nombre_tercera_firma', '$cargo_tercera_firma', '$nombre_cuarta_firma', '$cargo_cuarta_firma', '$primera_cc', '$segunda_cc', '$tercera_cc', '$cuarta_cc', '$quinta_cc', '$sexta_cc')";
			$this->cugd07_firmas_oficio_anulacion->execute($insert);

    	}else{
			$insert = "UPDATE cugd07_firmas_oficio_anulacion set nombre_primera_firma='$nombre_primera_firma',cargo_primera_firma='$cargo_primera_firma',nombre_segunda_firma='$nombre_segunda_firma',cargo_segunda_firma='$cargo_segunda_firma' WHERE ".$this->SQLCA()." and tipo_documento=700";
			$this->cugd07_firmas_oficio_anulacion->execute($insert);
    	}



	$this->set('Message_existe','Las firmas fuer&oacute;n registradas correctamente');
//	echo" <script> ver_documento('/reporte_contabilidad/seleccion_actividad/$cod_actividad','tab_pestana_descripcion_proyecto_2'); </script>";
$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=700");
			$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
			$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
			$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
			$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
			$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
			$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
			$this->set('tipo_doc_anul',700);
			$this->set('firma',2);
	}else{
		$this->set('errorMessage','debe ingresar las firmas y cargos');

	}

	echo "<script>document.getElementById('b_modificar_firmas').disabled=false;</script>";


}//fin guardar_firmas_balance_mayor






function balance_comprobacion_nivel_auxiliares($ir=null){
	if($ir=='no'){
		$this->layout="ajax";

		$this->set('year',$this->ano_ejecucion());
		$this->set('m',date('m'));
		$meses= array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
		$this->concatena($meses, 'mes');
		$this->set('ir','no');

		$cont = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA()." and tipo_documento='701'");

    	if($cont==0){
    		$this->set('nombre_primera_firma','');
			$this->set('cargo_primera_firma','');
			$this->set('nombre_segunda_firma','');
			$this->set('cargo_segunda_firma','');
			$this->set('nombre_tercera_firma','');
			$this->set('cargo_tercera_firma','');
			$this->set('tipo_doc_anul',701);
			$this->set('firma',1);

    	}else{
			$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=701");
			$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
			$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
			$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
			$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
			$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
			$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
			$this->set('tipo_doc_anul',701);
			$this->set('firma',2);
    	}

	}else if($ir=='si'){
		$this->layout="pdf";
		$this->set('ir','si');
		$filtro='';

		if(!empty($this->data['cfpp00']['ano']) && !empty($this->data['cfpp00']['mes'])){
			$ano=$this->data['cfpp00']['ano'];
			$mes=$this->data['cfpp00']['mes'];
			if(isset($this->data['cfpp00']['peticion'])){
				if($this->data['cfpp00']['peticion']==1){
//					$filtro=$this->condicionNDEP();
					$filtro=$this->SQLCA_consolidado($this->data['cfpp00']['peticion']);
					$group="a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.ano_fiscal,a.cod_tipo_cuenta,a.cod_cuenta,a.cod_subcuenta,a.cod_division,a.cod_subdivision";
				}else{
//					$filtro=$this->SQLCA();
					$filtro=$this->SQLCA_consolidado($this->data['cfpp00']['peticion']);
					$group="a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.ano_fiscal,a.cod_tipo_cuenta,a.cod_cuenta,a.cod_subcuenta,a.cod_division,a.cod_subdivision";
				}
			}else{
//				$filtro=$this->SQLCA();
				$filtro=$this->SQLCA_consolidado(2);
				$group="a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.ano_fiscal,a.cod_tipo_cuenta,a.cod_cuenta,a.cod_subcuenta,a.cod_division,a.cod_subdivision";
			}
			$filtro.=" and ano_fiscal=".$ano;
			switch($mes){
				case 1:
					//////SALDOS ANTERIORES////////////
					$debito_anterior='debito_acumulado';
					$credito_anterior='credito_acumulado';
					/////MOVIMIENTOS DEL MES///////////
					$debito_mes='debito_ene';
					$credito_mes='credito_ene';
					////TOTALES///////////////////////
					$debito_total='total_debito_acumulado_ene';
					$credito_total='total_credito_acumulado_ene';
					$name_mes='ENERO';
				break;
				case 2:
					//////SALDOS ANTERIORES////////////
					$debito_anterior='total_debito_acumulado_ene';
					$credito_anterior='total_credito_acumulado_ene';
					/////MOVIMIENTOS DEL MES///////////
					$debito_mes='debito_feb';
					$credito_mes='credito_feb';
					////TOTALES///////////////////////
					$debito_total='total_debito_acumulado_feb';
					$credito_total='total_credito_acumulado_feb';
					$name_mes='FEBRERO';
				break;
				case 3:
					//////SALDOS ANTERIORES////////////
					$debito_anterior='total_debito_acumulado_feb';
					$credito_anterior='total_credito_acumulado_feb';
					/////MOVIMIENTOS DEL MES///////////
					$debito_mes='debito_mar';
					$credito_mes='credito_mar';
					////TOTALES///////////////////////
					$debito_total='total_debito_acumulado_mar';
					$credito_total='total_credito_acumulado_mar';
					$name_mes='MARZO';
				break;
				case 4:
					//////SALDOS ANTERIORES////////////
					$debito_anterior='total_debito_acumulado_mar';
					$credito_anterior='total_credito_acumulado_mar';
					/////MOVIMIENTOS DEL MES///////////
					$debito_mes='debito_abr';
					$credito_mes='credito_abr';
					////TOTALES///////////////////////
					$debito_total='total_debito_acumulado_abr';
					$credito_total='total_credito_acumulado_abr';
					$name_mes='ABRIL';
				break;
				case 5:
					//////SALDOS ANTERIORES////////////
					$debito_anterior='total_debito_acumulado_abr';
					$credito_anterior='total_credito_acumulado_abr';
					/////MOVIMIENTOS DEL MES///////////
					$debito_mes='debito_may';
					$credito_mes='credito_may';
					////TOTALES///////////////////////
					$debito_total='total_debito_acumulado_may';
					$credito_total='total_credito_acumulado_may';
					$name_mes='MAYO';
				break;
				case 6:
					//////SALDOS ANTERIORES////////////
					$debito_anterior='total_debito_acumulado_may';
					$credito_anterior='total_credito_acumulado_may';
					/////MOVIMIENTOS DEL MES///////////
					$debito_mes='debito_jun';
					$credito_mes='credito_jun';
					////TOTALES///////////////////////
					$debito_total='total_debito_acumulado_jun';
					$credito_total='total_credito_acumulado_jun';
					$name_mes='JUNIO';
				break;
				case 7:
					//////SALDOS ANTERIORES////////////
					$debito_anterior='total_debito_acumulado_jun';
					$credito_anterior='total_credito_acumulado_jun';
					/////MOVIMIENTOS DEL MES///////////
					$debito_mes='debito_jul';
					$credito_mes='credito_jul';
					////TOTALES///////////////////////
					$debito_total='total_debito_acumulado_jul';
					$credito_total='total_credito_acumulado_jul';
					$name_mes='JULIO';
				break;
				case 8:
					//////SALDOS ANTERIORES////////////
					$debito_anterior='total_debito_acumulado_jul';
					$credito_anterior='total_credito_acumulado_jul';
					/////MOVIMIENTOS DEL MES///////////
					$debito_mes='debito_ago';
					$credito_mes='credito_ago';
					////TOTALES///////////////////////
					$debito_total='total_debito_acumulado_ago';
					$credito_total='total_credito_acumulado_ago';
					$name_mes='AGOSTO';
				break;
				case 9:
					//////SALDOS ANTERIORES////////////
					$debito_anterior='total_debito_acumulado_ago';
					$credito_anterior='total_credito_acumulado_ago';
					/////MOVIMIENTOS DEL MES///////////
					$debito_mes='debito_sep';
					$credito_mes='credito_sep';
					////TOTALES///////////////////////
					$debito_total='total_debito_acumulado_sep';
					$credito_total='total_credito_acumulado_sep';
					$name_mes='SEPTIEMBRE';
				break;
				case 10:
					//////SALDOS ANTERIORES////////////
					$debito_anterior='total_debito_acumulado_sep';
					$credito_anterior='total_credito_acumulado_sep';
					/////MOVIMIENTOS DEL MES///////////
					$debito_mes='debito_oct';
					$credito_mes='credito_oct';
					////TOTALES///////////////////////
					$debito_total='total_debito_acumulado_oct';
					$credito_total='total_credito_acumulado_oct';
					$name_mes='OCTUBRE';
				break;
				case 11:
					//////SALDOS ANTERIORES////////////
					$debito_anterior='total_debito_acumulado_oct';
					$credito_anterior='total_credito_acumulado_oct';
					/////MOVIMIENTOS DEL MES///////////
					$debito_mes='debito_nov';
					$credito_mes='credito_nov';
					////TOTALES///////////////////////
					$debito_total='total_debito_acumulado_nov';
					$credito_total='total_credito_acumulado_nov';
					$name_mes='NOVIEMBRE';
				break;
				case 12:
					//////SALDOS ANTERIORES////////////
					$debito_anterior='total_debito_acumulado_nov';
					$credito_anterior='total_credito_acumulado_nov';
					/////MOVIMIENTOS DEL MES///////////
					$debito_mes='debito_dic';
					$credito_mes='credito_dic';
					////TOTALES///////////////////////
					$debito_total='total_debito_acumulado_dic';
					$credito_total='total_credito_acumulado_dic';
					$name_mes='DICIEMBRE';
				break;
			}//fin switch
			$this->set('debito_anterior',$debito_anterior);
			$this->set('credito_anterior',$credito_anterior);
			$this->set('debito_mes',$debito_mes);
			$this->set('credito_mes',$credito_mes);
			$this->set('debito_total',$debito_total);
			$this->set('credito_total',$credito_total);
			$this->Session->write('name_mes',$name_mes);
			$this->Session->write('valor_ano',$ano);

			$firmantes= $this->cugd07_firmas_oficio_anulacion->execute("select * from cugd07_firmas_oficio_anulacion where ".$this->SQLCA()." and tipo_documento=701");
			$this->Session->write('firma1',$firmantes[0][0]['nombre_primera_firma']);
			$this->Session->write('firma2',$firmantes[0][0]['nombre_segunda_firma']);
			$this->Session->write('firma3',$firmantes[0][0]['cargo_primera_firma']);
			$this->Session->write('firma4',$firmantes[0][0]['cargo_segunda_firma']);
			$sql="SELECT ".$group.",
					sum(a.debito_acumulado) as debito_acumulado,
					sum(a.credito_acumulado) as credito_acumulado,
					sum(a.debito_ene) as debito_ene,
					sum(a.credito_ene) as credito_ene,
					sum(a.debito_feb) as debito_feb,
					sum(a.credito_feb) as credito_feb,
					sum(a.debito_mar) as debito_mar,
					sum(a.credito_mar) as credito_mar,
					sum(a.debito_abr) as debito_abr,
					sum(a.credito_abr) as credito_abr,
					sum(a.debito_may) as debito_may,
					sum(a.credito_may) as credito_may,
					sum(a.debito_jun) as debito_jun,
					sum(a.credito_jun) as credito_jun,
					sum(a.debito_jul) as debito_jul,
					sum(a.credito_jul) as credito_jul,
					sum(a.debito_ago) as debito_ago,
					sum(a.credito_ago) as credito_ago,
					sum(a.debito_sep) as debito_sep,
					sum(a.credito_sep) as credito_sep,
					sum(a.debito_oct) as debito_oct,
					sum(a.credito_oct) as credito_oct,
					sum(a.debito_nov) as debito_nov,
					sum(a.credito_nov) as credito_nov,
					sum(a.debito_dic) as debito_dic,
					sum(a.credito_dic) as credito_dic,
					(select b.denominacion from ccfd01_cuenta b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=1 and b.cod_tipo_cuenta=a.cod_tipo_cuenta and b.cod_cuenta=a.cod_cuenta) as denominacion_cuenta,
					(select c.denominacion from ccfd01_subcuenta c where c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=1 and c.cod_tipo_cuenta=a.cod_tipo_cuenta and c.cod_cuenta=a.cod_cuenta and c.cod_subcuenta=a.cod_subcuenta) as denominacion_subcuenta,
					(select d.denominacion from ccfd01_division d where d.cod_presi=a.cod_presi and d.cod_entidad=a.cod_entidad and d.cod_tipo_inst=a.cod_tipo_inst and d.cod_inst=a.cod_inst and d.cod_dep=1 and d.cod_tipo_cuenta=a.cod_tipo_cuenta and d.cod_cuenta=a.cod_cuenta and d.cod_subcuenta=a.cod_subcuenta and d.cod_division=a.cod_division) as denominacion_division,
					(select e.denominacion from ccfd01_subdivision e where e.cod_presi=a.cod_presi and e.cod_entidad=a.cod_entidad and e.cod_tipo_inst=a.cod_tipo_inst and e.cod_inst=a.cod_inst and e.cod_dep=1 and e.cod_tipo_cuenta=a.cod_tipo_cuenta and e.cod_cuenta=a.cod_cuenta and e.cod_subcuenta=a.cod_subcuenta and e.cod_division=a.cod_division and e.cod_subdivision=a.cod_subdivision) as denominacion_subdivision,
					sum(a.debito_acumulado + a.debito_ene)::numeric(22,2) AS total_debito_acumulado_ene,
					sum(a.credito_acumulado + a.credito_ene)::numeric(22,2) AS total_credito_acumulado_ene,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb)::numeric(22,2) AS total_debito_acumulado_feb,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb)::numeric(22,2) AS total_credito_acumulado_feb,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar)::numeric(22,2) AS total_debito_acumulado_mar,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar)::numeric(22,2) AS total_credito_acumulado_mar,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr)::numeric(22,2) AS total_debito_acumulado_abr,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr)::numeric(22,2) AS total_credito_acumulado_abr,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may)::numeric(22,2) AS total_debito_acumulado_may,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may)::numeric(22,2) AS total_credito_acumulado_may,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun)::numeric(22,2) AS total_debito_acumulado_jun,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun)::numeric(22,2) AS total_credito_acumulado_jun,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul)::numeric(22,2) AS total_debito_acumulado_jul,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul)::numeric(22,2) AS total_credito_acumulado_jul,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago)::numeric(22,2) AS total_debito_acumulado_ago,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago)::numeric(22,2) AS total_credito_acumulado_ago,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago + a.debito_sep)::numeric(22,2) AS total_debito_acumulado_sep,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago + a.credito_sep)::numeric(22,2) AS total_credito_acumulado_sep,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago + a.debito_sep + a.debito_oct)::numeric(22,2) AS total_debito_acumulado_oct,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago + a.credito_sep + a.credito_oct)::numeric(22,2) AS total_credito_acumulado_oct,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago + a.debito_sep + a.debito_oct + a.debito_nov)::numeric(22,2) AS total_debito_acumulado_nov,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago + a.credito_sep + a.credito_oct + a.credito_nov)::numeric(22,2) AS total_credito_acumulado_nov,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago + a.debito_sep + a.debito_oct + a.debito_nov + a.debito_dic)::numeric(22,2) AS total_debito_acumulado_dic,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago + a.credito_sep + a.credito_oct + a.credito_nov + a.credito_dic)::numeric(22,2) AS total_credito_acumulado_dic
					  FROM ccfd02 a where ".$filtro."
					  GROUP BY ".$group."
					ORDER BY
					a.cod_tipo_cuenta,
					a.cod_cuenta,
					a.cod_subcuenta,
					a.cod_division,
					a.cod_subdivision
					ASC
					";

			$datos=$this->ccfd02->execute($sql);
			if($datos!=null){
				$this->set('datos',$datos);
			}else{
				$this->set('datos',null);
			}
		}else{
//			echo'<script>history.back(1);</script>';
			$this->set('datos',null);
		}//fin if ano y mes vacio
	}//fin ir si


}//fin funcion




function guardar_firmas_balance_auxiliares($var=null){
	$this->layout="ajax";
	$cp  = $this->Session->read('SScodpresi');
	$ce  = $this->Session->read('SScodentidad');
	$cti = $this->Session->read('SScodtipoinst');
	$ci  = $this->Session->read('SScodinst');
	$cd  = $this->Session->read('SScoddep');

	if(!empty($this->data['cfpp00']['nombre_primera_firma']) && !empty($this->data['cfpp00']['cargo_primera_firma']) && !empty($this->data['cfpp00']['nombre_segunda_firma']) && !empty($this->data['cfpp00']['cargo_segunda_firma'])){
		$tipo_doc_anul = $this->data['cugp03_acta_anulacion']['tipo_doc_anul'];

		$tipo_doc_anul=701;
		$nombre_primera_firma = $this->data['cfpp00']['nombre_primera_firma'];
		$cargo_primera_firma  = $this->data['cfpp00']['cargo_primera_firma'];
		$nombre_segunda_firma = $this->data['cfpp00']['nombre_segunda_firma'];
		$cargo_segunda_firma  = $this->data['cfpp00']['cargo_segunda_firma'];
		$nombre_tercera_firma = "0";
		$cargo_tercera_firma  = "0";
		$nombre_cuarta_firma = "0";
		$cargo_cuarta_firma  = "0";

		$primera_cc = "0";
		$segunda_cc = "0";
		$tercera_cc = "0";
		$cuarta_cc  = "0";
		$quinta_cc  = "0";
		$sexta_cc   = "0";
		$septima_cc = "0";
		$octava_cc  = "0";


		$cont = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA()." and tipo_documento='701'");
    	if($cont==0){
    		$insert = "INSERT INTO cugd07_firmas_oficio_anulacion VALUES ($cp, $ce, $cti, $ci, $cd, $tipo_doc_anul,'$nombre_primera_firma', '$cargo_primera_firma', '$nombre_segunda_firma', '$cargo_segunda_firma', '$nombre_tercera_firma', '$cargo_tercera_firma', '$nombre_cuarta_firma', '$cargo_cuarta_firma', '$primera_cc', '$segunda_cc', '$tercera_cc', '$cuarta_cc', '$quinta_cc', '$sexta_cc')";
			$this->cugd07_firmas_oficio_anulacion->execute($insert);

    	}else{
			$insert = "UPDATE cugd07_firmas_oficio_anulacion set nombre_primera_firma='$nombre_primera_firma',cargo_primera_firma='$cargo_primera_firma',nombre_segunda_firma='$nombre_segunda_firma',cargo_segunda_firma='$cargo_segunda_firma' WHERE ".$this->SQLCA()." and tipo_documento=701";
			$this->cugd07_firmas_oficio_anulacion->execute($insert);
    	}



	$this->set('Message_existe','Las firmas fuer&oacute;n registradas correctamente');
//	echo" <script> ver_documento('/reporte_contabilidad/seleccion_actividad/$cod_actividad','tab_pestana_descripcion_proyecto_2'); </script>";
$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=701");
			$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
			$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
			$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
			$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
			$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
			$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
			$this->set('tipo_doc_anul',701);
			$this->set('firma',2);
	}else{
		$this->set('errorMessage','debe ingresar las firmas y cargos');

	}

	echo "<script>document.getElementById('b_modificar_firmas').disabled=false;</script>";


}




function balance_comprobacion_mes($ir=null){
	if($ir=='no'){
		$this->layout="ajax";

		$this->set('year',$this->ano_ejecucion());
		$this->set('m',date('m'));
		$meses= array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
		$this->concatena($meses, 'mes');
		$this->set('ir','no');

		$cont = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA()." and tipo_documento='702'");

    	if($cont==0){
    		$this->set('nombre_primera_firma','');
			$this->set('cargo_primera_firma','');
			$this->set('nombre_segunda_firma','');
			$this->set('cargo_segunda_firma','');
			$this->set('nombre_tercera_firma','');
			$this->set('cargo_tercera_firma','');
			$this->set('tipo_doc_anul',702);
			$this->set('firma',1);

    	}else{
			$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=702");
			$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
			$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
			$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
			$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
			$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
			$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
			$this->set('tipo_doc_anul',702);
			$this->set('firma',2);
    	}

	}else if($ir=='si'){
		$this->layout="pdf";
		$this->set('ir','si');
		$filtro='';

		if(!empty($this->data['cfpp00']['ano']) && !empty($this->data['cfpp00']['mes'])){
			$ano=$this->data['cfpp00']['ano'];
			$mes=$this->data['cfpp00']['mes'];
			if(isset($this->data['cfpp00']['peticion'])){
				if($this->data['cfpp00']['peticion']==1){
//					$filtro=$this->condicionNDEP();
					$filtro=$this->SQLCA_consolidado($this->data['cfpp00']['peticion']);
					$group="a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.ano_fiscal,a.cod_tipo_cuenta,a.cod_cuenta";
				}else{
//					$filtro=$this->SQLCA();
					$filtro=$this->SQLCA_consolidado($this->data['cfpp00']['peticion']);
					$group="a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.ano_fiscal,a.cod_tipo_cuenta,a.cod_cuenta";
				}
			}else{
//				$filtro=$this->SQLCA();
				$filtro=$this->SQLCA_consolidado(2);
				$group="a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.ano_fiscal,a.cod_tipo_cuenta,a.cod_cuenta";
			}
			$filtro.=" and ano_fiscal=".$ano;

			switch($mes){
				case 1:
					$debito_mes='debito_ene';
					$credito_mes='credito_ene';
					$name_mes='ENERO';
				break;
				case 2:
					$debito_mes='debito_feb';
					$credito_mes='credito_feb';
					$name_mes='FEBRERO';
				break;
				case 3:
					$debito_mes='debito_mar';
					$credito_mes='credito_mar';
					$name_mes='MARZO';
				break;
				case 4:
					$debito_mes='debito_abr';
					$credito_mes='credito_abr';
					$name_mes='ABRIL';
				break;
				case 5:
					$debito_mes='debito_may';
					$credito_mes='credito_may';
					$name_mes='MAYO';
				break;
				case 6:
					$debito_mes='debito_jun';
					$credito_mes='credito_jun';
					$name_mes='JUNIO';
				break;
				case 7:
					$debito_mes='debito_jul';
					$credito_mes='credito_jul';
					$name_mes='JULIO';
				break;
				case 8:
					$debito_mes='debito_ago';
					$credito_mes='credito_ago';
					$name_mes='AGOSTO';
				break;
				case 9:
					$debito_mes='debito_sep';
					$credito_mes='credito_sep';
					$name_mes='SEPTIEMBRE';
				break;
				case 10:
					$debito_mes='debito_oct';
					$credito_mes='credito_oct';
					$name_mes='OCTUBRE';
				break;
				case 11:
					$debito_mes='debito_nov';
					$credito_mes='credito_nov';
					$name_mes='NOVIEMBRE';
				break;
				case 12:
					$debito_mes='debito_dic';
					$credito_mes='credito_dic';
					$name_mes='DICIEMBRE';
				break;
			}//fin switch
			$this->set('debito_mes',$debito_mes);
			$this->set('credito_mes',$credito_mes);
			$this->Session->write('name_mes',$name_mes);
			$this->Session->write('valor_ano',$ano);
			$this->Session->write('hasta_dia',$this->ultimoDiaMes($mes,$ano));

			$firmantes= $this->cugd07_firmas_oficio_anulacion->execute("select * from cugd07_firmas_oficio_anulacion where ".$this->SQLCA()." and tipo_documento=702");
			$this->Session->write('firma1',$firmantes[0][0]['nombre_primera_firma']);
			$this->Session->write('firma2',$firmantes[0][0]['nombre_segunda_firma']);
			$this->Session->write('firma3',$firmantes[0][0]['cargo_primera_firma']);
			$this->Session->write('firma4',$firmantes[0][0]['cargo_segunda_firma']);

			 $sql="SELECT ".$group.",
						(select b.denominacion from ccfd01_cuenta b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=1 and b.cod_tipo_cuenta=a.cod_tipo_cuenta and b.cod_cuenta=a.cod_cuenta) as denominacion_cuenta,
						sum(a.debito_acumulado) as debito_acumulado,
						sum(a.credito_acumulado) as credito_acumulado,
						sum(a.debito_ene) as debito_ene,
						sum(a.credito_ene) as credito_ene,
						sum(a.debito_feb) as debito_feb,
						sum(a.credito_feb) as credito_feb,
						sum(a.debito_mar) as debito_mar,
						sum(a.credito_mar) as credito_mar,
						sum(a.debito_abr) as debito_abr,
						sum(a.credito_abr) as credito_abr,
						sum(a.debito_may) as debito_may,
						sum(a.credito_may) as credito_may,
						sum(a.debito_jun) as debito_jun,
						sum(a.credito_jun) as credito_jun,
						sum(a.debito_jul) as debito_jul,
						sum(a.credito_jul) as credito_jul,
						sum(a.debito_ago) as debito_ago,
						sum(a.credito_ago) as credito_ago,
						sum(a.debito_sep) as debito_sep,
						sum(a.credito_sep) as credito_sep,
						sum(a.debito_oct) as debito_oct,
						sum(a.credito_oct) as credito_oct,
						sum(a.debito_nov) as debito_nov,
						sum(a.credito_nov) as credito_nov,
						sum(a.debito_dic) as debito_dic,
						sum(a.credito_dic) as credito_dic
						  FROM ccfd02 a WHERE ".$filtro."
						  GROUP BY ".$group."
						  ORDER BY
						a.cod_tipo_cuenta,
						a.cod_cuenta
						ASC";
			$datos=$this->ccfd02->execute($sql);
			if($datos!=null){
				$this->set('datos',$datos);
			}else{
				$this->set('datos',null);
			}
		}else{
//			echo'<script>history.back(1);</script>';
			$this->set('datos',null);
		}//fin if ano y mes vacio
	}//fin ir si


}//fin funcion balance_comprobacion_mes



function firmas_balance_comprobacion_mes($var=null){
	$this->layout="ajax";
	$cp  = $this->Session->read('SScodpresi');
	$ce  = $this->Session->read('SScodentidad');
	$cti = $this->Session->read('SScodtipoinst');
	$ci  = $this->Session->read('SScodinst');
	$cd  = $this->Session->read('SScoddep');

	if(!empty($this->data['cfpp00']['nombre_primera_firma']) && !empty($this->data['cfpp00']['cargo_primera_firma']) && !empty($this->data['cfpp00']['nombre_segunda_firma']) && !empty($this->data['cfpp00']['cargo_segunda_firma'])){
		$tipo_doc_anul = $this->data['cugp03_acta_anulacion']['tipo_doc_anul'];

		$tipo_doc_anul=702;
		$nombre_primera_firma = $this->data['cfpp00']['nombre_primera_firma'];
		$cargo_primera_firma  = $this->data['cfpp00']['cargo_primera_firma'];
		$nombre_segunda_firma = $this->data['cfpp00']['nombre_segunda_firma'];
		$cargo_segunda_firma  = $this->data['cfpp00']['cargo_segunda_firma'];
		$nombre_tercera_firma = "0";
		$cargo_tercera_firma  = "0";
		$nombre_cuarta_firma = "0";
		$cargo_cuarta_firma  = "0";

		$primera_cc = "0";
		$segunda_cc = "0";
		$tercera_cc = "0";
		$cuarta_cc  = "0";
		$quinta_cc  = "0";
		$sexta_cc   = "0";
		$septima_cc = "0";
		$octava_cc  = "0";


		$cont = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA()." and tipo_documento='702'");
    	if($cont==0){
    		$insert = "INSERT INTO cugd07_firmas_oficio_anulacion VALUES ($cp, $ce, $cti, $ci, $cd, $tipo_doc_anul,'$nombre_primera_firma', '$cargo_primera_firma', '$nombre_segunda_firma', '$cargo_segunda_firma', '$nombre_tercera_firma', '$cargo_tercera_firma', '$nombre_cuarta_firma', '$cargo_cuarta_firma', '$primera_cc', '$segunda_cc', '$tercera_cc', '$cuarta_cc', '$quinta_cc', '$sexta_cc')";
			$this->cugd07_firmas_oficio_anulacion->execute($insert);

    	}else{
			$insert = "UPDATE cugd07_firmas_oficio_anulacion set nombre_primera_firma='$nombre_primera_firma',cargo_primera_firma='$cargo_primera_firma',nombre_segunda_firma='$nombre_segunda_firma',cargo_segunda_firma='$cargo_segunda_firma' WHERE ".$this->SQLCA()." and tipo_documento=702";
			$this->cugd07_firmas_oficio_anulacion->execute($insert);
    	}



	$this->set('Message_existe','Las firmas fuer&oacute;n registradas correctamente');
//	echo" <script> ver_documento('/reporte_contabilidad/seleccion_actividad/$cod_actividad','tab_pestana_descripcion_proyecto_2'); </script>";
$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=702");
			$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
			$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
			$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
			$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
			$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
			$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
			$this->set('tipo_doc_anul',702);
			$this->set('firma',2);
	}else{
		$this->set('errorMessage','debe ingresar las firmas y cargos');

	}

	echo "<script>document.getElementById('b_modificar_firmas').disabled=false;</script>";


}






function balance_general($ir=null){
	if($ir=='no'){
		$this->layout="ajax";

		$this->set('year',$this->ano_ejecucion());
		$this->set('m',date('m'));
		$meses= array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
		$this->concatena($meses, 'mes');
		$this->set('ir','no');

		$cont = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA()." and tipo_documento='703'");

    	if($cont==0){
    		$this->set('nombre_primera_firma','');
			$this->set('cargo_primera_firma','');
			$this->set('nombre_segunda_firma','');
			$this->set('cargo_segunda_firma','');
			$this->set('nombre_tercera_firma','');
			$this->set('cargo_tercera_firma','');
			$this->set('tipo_doc_anul',703);
			$this->set('firma',1);

    	}else{
			$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=703");
			$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
			$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
			$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
			$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
			$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
			$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
			$this->set('tipo_doc_anul',703);
			$this->set('firma',2);
    	}

	}else if($ir=='si'){

		$this->layout="pdf";
		$this->set('ir','si');
		$filtro='';
		if(!empty($this->data['cfpp00']['ano']) && !empty($this->data['cfpp00']['mes'])){
			$ano=$this->data['cfpp00']['ano'];
			$mes=$this->data['cfpp00']['mes'];

			if(isset($this->data['cfpp00']['peticion'])){
				if($this->data['cfpp00']['peticion']==1){
//					$filtro=$this->condicionNDEP();
					$filtro=$this->SQLCA_consolidado($this->data['cfpp00']['peticion']);
					$group="a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.ano_fiscal,a.cod_tipo_cuenta,a.cod_cuenta";
				}else{
//					$filtro=$this->SQLCA();
					$filtro=$this->SQLCA_consolidado($this->data['cfpp00']['peticion']);
					$group="a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.ano_fiscal,a.cod_tipo_cuenta,a.cod_cuenta";
				}
			}else{
//				$filtro=$this->SQLCA();
				$filtro=$this->SQLCA_consolidado(2);
				$group="a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.ano_fiscal,a.cod_tipo_cuenta,a.cod_cuenta";
			}
			$filtro.=" and ano_fiscal=".$ano;
			switch($mes){
				case 1:
					$debito_total='total_debito_acumulado_ene';
					$credito_total='total_credito_acumulado_ene';
					$name_mes='ENERO';
				break;
				case 2:
					$debito_total='total_debito_acumulado_feb';
					$credito_total='total_credito_acumulado_feb';
					$name_mes='FEBRERO';
				break;
				case 3:
					$debito_total='total_debito_acumulado_mar';
					$credito_total='total_credito_acumulado_mar';
					$name_mes='MARZO';
				break;
				case 4:
					$debito_total='total_debito_acumulado_abr';
					$credito_total='total_credito_acumulado_abr';
					$name_mes='ABRIL';
				break;
				case 5:
					$debito_total='total_debito_acumulado_may';
					$credito_total='total_credito_acumulado_may';
					$name_mes='MAYO';
				break;
				case 6:
					$debito_total='total_debito_acumulado_jun';
					$credito_total='total_credito_acumulado_jun';
					$name_mes='JUNIO';
				break;
				case 7:
					$debito_total='total_debito_acumulado_jul';
					$credito_total='total_credito_acumulado_jul';
					$name_mes='JULIO';
				break;
				case 8:
					$debito_total='total_debito_acumulado_ago';
					$credito_total='total_credito_acumulado_ago';
					$name_mes='AGOSTO';
				break;
				case 9:
					$debito_total='total_debito_acumulado_sep';
					$credito_total='total_credito_acumulado_sep';
					$name_mes='SEPTIEMBRE';
				break;
				case 10:
					$debito_total='total_debito_acumulado_oct';
					$credito_total='total_credito_acumulado_oct';
					$name_mes='OCTUBRE';
				break;
				case 11:
					$debito_total='total_debito_acumulado_nov';
					$credito_total='total_credito_acumulado_nov';
					$name_mes='NOVIEMBRE';
				break;
				case 12:
					$debito_total='total_debito_acumulado_dic';
					$credito_total='total_credito_acumulado_dic';
					$name_mes='DICIEMBRE';
				break;
			}//fin switch
			$this->set('debito_total',$debito_total);
			$this->set('credito_total',$credito_total);
			$this->Session->write('name_mes',$name_mes);
			$this->Session->write('valor_ano',$ano);
			$this->Session->write('valor_dia',date('d'));
			$this->Session->write('hasta_dia',$this->ultimoDiaMes($mes,$ano));

			$cuentas1=$this->ccfd01_cuenta->findAll($this->condicionNDEP()." and cod_dep=1 and cod_tipo_cuenta=1",null,'cod_tipo_cuenta,cod_cuenta ASC',null,null,null);
			$cuentas2=$this->ccfd01_cuenta->findAll($this->condicionNDEP()." and cod_dep=1 and cod_tipo_cuenta=2",null,'cod_tipo_cuenta,cod_cuenta ASC',null,null,null);
			$this->set('cuentas1',$cuentas1);
			$this->set('cuentas2',$cuentas2);

			$firmantes= $this->cugd07_firmas_oficio_anulacion->execute("select * from cugd07_firmas_oficio_anulacion where ".$this->SQLCA()." and tipo_documento=703");
			$this->Session->write('firma1',$firmantes[0][0]['nombre_primera_firma']);
			$this->Session->write('firma2',$firmantes[0][0]['nombre_segunda_firma']);
			$this->Session->write('firma3',$firmantes[0][0]['cargo_primera_firma']);
			$this->Session->write('firma4',$firmantes[0][0]['cargo_segunda_firma']);
			$sql="SELECT
						".$group.",
						(select b.denominacion from ccfd01_cuenta b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=1 and b.cod_tipo_cuenta=a.cod_tipo_cuenta and b.cod_cuenta=a.cod_cuenta) as denominacion_cuenta,
						sum(a.debito_acumulado) as debito_acumulado,
						sum(a.credito_acumulado) as credito_acumulado,
						sum(a.debito_acumulado + a.debito_ene)::numeric(22,2) AS total_debito_acumulado_ene,
						sum(a.credito_acumulado + a.credito_ene)::numeric(22,2) AS total_credito_acumulado_ene,
						sum(a.debito_acumulado + a.debito_ene + a.debito_feb)::numeric(22,2) AS total_debito_acumulado_feb,
						sum(a.credito_acumulado + a.credito_ene + a.credito_feb)::numeric(22,2) AS total_credito_acumulado_feb,
						sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar)::numeric(22,2) AS total_debito_acumulado_mar,
						sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar)::numeric(22,2) AS total_credito_acumulado_mar,
						sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr)::numeric(22,2) AS total_debito_acumulado_abr,
						sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr)::numeric(22,2) AS total_credito_acumulado_abr,
						sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may)::numeric(22,2) AS total_debito_acumulado_may,
						sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may)::numeric(22,2) AS total_credito_acumulado_may,
						sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun)::numeric(22,2) AS total_debito_acumulado_jun,
						sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun)::numeric(22,2) AS total_credito_acumulado_jun,
						sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul)::numeric(22,2) AS total_debito_acumulado_jul,
						sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul)::numeric(22,2) AS total_credito_acumulado_jul,
						sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago)::numeric(22,2) AS total_debito_acumulado_ago,
						sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago)::numeric(22,2) AS total_credito_acumulado_ago,
						sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago + a.debito_sep)::numeric(22,2) AS total_debito_acumulado_sep,
						sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago + a.credito_sep)::numeric(22,2) AS total_credito_acumulado_sep,
						sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago + a.debito_sep + a.debito_oct)::numeric(22,2) AS total_debito_acumulado_oct,
						sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago + a.credito_sep + a.credito_oct)::numeric(22,2) AS total_credito_acumulado_oct,
						sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago + a.debito_sep + a.debito_oct + a.debito_nov)::numeric(22,2) AS total_debito_acumulado_nov,
						sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago + a.credito_sep + a.credito_oct + a.credito_nov)::numeric(22,2) AS total_credito_acumulado_nov,
						sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago + a.debito_sep + a.debito_oct + a.debito_nov + a.debito_dic)::numeric(22,2) AS total_debito_acumulado_dic,
						sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago + a.credito_sep + a.credito_oct + a.credito_nov + a.credito_dic)::numeric(22,2) AS total_credito_acumulado_dic
						  FROM ccfd02 a WHERE ".$filtro."
						  GROUP BY ".$group."
						  ORDER BY a.cod_tipo_cuenta,a.cod_cuenta
						ASC";
		   $datos=$this->ccfd02->execute($sql);
		   if($datos!=null){
				$this->set('datos',$datos);
//pr($datos);
				$v1=array();
				$v2=array();
				$v3=array();
				$v4=array();
				$v5=array();
				$v6=array();
				$v7=array();
				$v8=array();
				$cuentas_100=array();
				$cuentas_200=array();
				$cuentas_300=array();
				$cuentas_400=array();
				$j=0;
				$k=0;
				$x=0;
				$z=0;
				for($i=0;$i<count($datos);$i++){
					if($datos[$i][0]['cod_tipo_cuenta']==1 && ($datos[$i][0]['cod_cuenta']>=100 && $datos[$i][0]['cod_cuenta']<200)){
						$v1[$j]['cod_tipo_cuenta_activo']=$datos[$i][0]['cod_tipo_cuenta'];
						$v1[$j]['cod_cuenta_activo']=$datos[$i][0]['cod_cuenta'];
						$v1[$j]['cuenta_deno_activo']=$datos[$i][0]['denominacion_cuenta'];
						$v1[$j]['debito_total_activo']=$datos[$i][0][$debito_total];
						$v1[$j]['credito_total_activo']=$datos[$i][0][$credito_total];
						$j++;
					}else if($datos[$i][0]['cod_tipo_cuenta']==1 && ($datos[$i][0]['cod_cuenta']>=200 && $datos[$i][0]['cod_cuenta']<300)){
						$v3[$k]['cod_tipo_cuenta_activo']=$datos[$i][0]['cod_tipo_cuenta'];
						$v3[$k]['cod_cuenta_activo']=$datos[$i][0]['cod_cuenta'];
						$v3[$k]['cuenta_deno_activo']=$datos[$i][0]['denominacion_cuenta'];
						$v3[$k]['debito_total_activo']=$datos[$i][0][$debito_total];
						$v3[$k]['credito_total_activo']=$datos[$i][0][$credito_total];
						$k++;
					}else if($datos[$i][0]['cod_tipo_cuenta']==1 && ($datos[$i][0]['cod_cuenta']>=300 && $datos[$i][0]['cod_cuenta']<400)){
						$v5[$x]['cod_tipo_cuenta_activo']=$datos[$i][0]['cod_tipo_cuenta'];
						$v5[$x]['cod_cuenta_activo']=$datos[$i][0]['cod_cuenta'];
						$v5[$x]['cuenta_deno_activo']=$datos[$i][0]['denominacion_cuenta'];
						$v5[$x]['debito_total_activo']=$datos[$i][0][$debito_total];
						$v5[$x]['credito_total_activo']=$datos[$i][0][$credito_total];
						$x++;
					}else if($datos[$i][0]['cod_tipo_cuenta']==1 && ($datos[$i][0]['cod_cuenta']>=400 && $datos[$i][0]['cod_cuenta']<500)){
						$v7[$z]['cod_tipo_cuenta_activo']=$datos[$i][0]['cod_tipo_cuenta'];
						$v7[$z]['cod_cuenta_activo']=$datos[$i][0]['cod_cuenta'];
						$v7[$z]['cuenta_deno_activo']=$datos[$i][0]['denominacion_cuenta'];
						$v7[$z]['debito_total_activo']=$datos[$i][0][$debito_total];
						$v7[$z]['credito_total_activo']=$datos[$i][0][$credito_total];
						$z++;
					}
				}
				$j=0;
				$k=0;
				$x=0;
				$z=0;
				for($i=0;$i<count($datos);$i++){
					if($datos[$i][0]['cod_tipo_cuenta']==2 && ($datos[$i][0]['cod_cuenta']>=100 && $datos[$i][0]['cod_cuenta']<200)){
						$v2[$j]['cod_tipo_cuenta_pasivo']=$datos[$i][0]['cod_tipo_cuenta'];
						$v2[$j]['cod_cuenta_pasivo']=$datos[$i][0]['cod_cuenta'];
						$v2[$j]['cuenta_deno_pasivo']=$datos[$i][0]['denominacion_cuenta'];
						$v2[$j]['debito_total_pasivo']=$datos[$i][0][$debito_total];
						$v2[$j]['credito_total_pasivo']=$datos[$i][0][$credito_total];
						$j++;
					}else if($datos[$i][0]['cod_tipo_cuenta']==2 && ($datos[$i][0]['cod_cuenta']>=200 && $datos[$i][0]['cod_cuenta']<300)){
						$v4[$k]['cod_tipo_cuenta_pasivo']=$datos[$i][0]['cod_tipo_cuenta'];
						$v4[$k]['cod_cuenta_pasivo']=$datos[$i][0]['cod_cuenta'];
						$v4[$k]['cuenta_deno_pasivo']=$datos[$i][0]['denominacion_cuenta'];
						$v4[$k]['debito_total_pasivo']=$datos[$i][0][$debito_total];
						$v4[$k]['credito_total_pasivo']=$datos[$i][0][$credito_total];
						$k++;
					}else if($datos[$i][0]['cod_tipo_cuenta']==2 && ($datos[$i][0]['cod_cuenta']>=300 && $datos[$i][0]['cod_cuenta']<400)){
						$v6[$x]['cod_tipo_cuenta_pasivo']=$datos[$i][0]['cod_tipo_cuenta'];
						$v6[$x]['cod_cuenta_pasivo']=$datos[$i][0]['cod_cuenta'];
						$v6[$x]['cuenta_deno_pasivo']=$datos[$i][0]['denominacion_cuenta'];
						$v6[$x]['debito_total_pasivo']=$datos[$i][0][$debito_total];
						$v6[$x]['credito_total_pasivo']=$datos[$i][0][$credito_total];
						$x++;
					}else if($datos[$i][0]['cod_tipo_cuenta']==2 && ($datos[$i][0]['cod_cuenta']>=400 && $datos[$i][0]['cod_cuenta']<500)){
						$v8[$z]['cod_tipo_cuenta_pasivo']=$datos[$i][0]['cod_tipo_cuenta'];
						$v8[$z]['cod_cuenta_pasivo']=$datos[$i][0]['cod_cuenta'];
						$v8[$z]['cuenta_deno_pasivo']=$datos[$i][0]['denominacion_cuenta'];
						$v8[$z]['debito_total_pasivo']=$datos[$i][0][$debito_total];
						$v8[$z]['credito_total_pasivo']=$datos[$i][0][$credito_total];
						$z++;
					}
				}

				if(count($v1)>count($v2)){
					for($i=0;$i<count($v1);$i++){
							$cuentas_100[$i]['cod_tipo_cuenta_activo']=$v1[$i]['cod_tipo_cuenta_activo'];
							$cuentas_100[$i]['cod_cuenta_activo']=$v1[$i]['cod_cuenta_activo'];
							$cuentas_100[$i]['cuenta_deno_activo']=$v1[$i]['cuenta_deno_activo'];
							$cuentas_100[$i]['debito_total_activo']=$v1[$i]['debito_total_activo'];
							$cuentas_100[$i]['credito_total_activo']=$v1[$i]['credito_total_activo'];
							$cuentas_100[$i]['cod_tipo_cuenta_pasivo']=$v2[$i]['cod_tipo_cuenta_pasivo'];
							$cuentas_100[$i]['cod_cuenta_pasivo']=$v2[$i]['cod_cuenta_pasivo'];
							$cuentas_100[$i]['cuenta_deno_pasivo']=$v2[$i]['cuenta_deno_pasivo'];
							$cuentas_100[$i]['debito_total_pasivo']=$v2[$i]['debito_total_pasivo'];
							$cuentas_100[$i]['credito_total_pasivo']=$v2[$i]['credito_total_pasivo'];
					}
				}else if(count($v2)>count($v1)){
					for($i=0;$i<count($v2);$i++){
							$cuentas_100[$i]['cod_tipo_cuenta_activo']=$v1[$i]['cod_tipo_cuenta_activo'];
							$cuentas_100[$i]['cod_cuenta_activo']=$v1[$i]['cod_cuenta_activo'];
							$cuentas_100[$i]['cuenta_deno_activo']=$v1[$i]['cuenta_deno_activo'];
							$cuentas_100[$i]['debito_total_activo']=$v1[$i]['debito_total_activo'];
							$cuentas_100[$i]['credito_total_activo']=$v1[$i]['credito_total_activo'];
							$cuentas_100[$i]['cod_tipo_cuenta_pasivo']=$v2[$i]['cod_tipo_cuenta_pasivo'];
							$cuentas_100[$i]['cod_cuenta_pasivo']=$v2[$i]['cod_cuenta_pasivo'];
							$cuentas_100[$i]['cuenta_deno_pasivo']=$v2[$i]['cuenta_deno_pasivo'];
							$cuentas_100[$i]['debito_total_pasivo']=$v2[$i]['debito_total_pasivo'];
							$cuentas_100[$i]['credito_total_pasivo']=$v2[$i]['credito_total_pasivo'];
					}
				}else{
					for($i=0;$i<count($v1);$i++){
							$cuentas_100[$i]['cod_tipo_cuenta_activo']=$v1[$i]['cod_tipo_cuenta_activo'];
							$cuentas_100[$i]['cod_cuenta_activo']=$v1[$i]['cod_cuenta_activo'];
							$cuentas_100[$i]['cuenta_deno_activo']=$v1[$i]['cuenta_deno_activo'];
							$cuentas_100[$i]['debito_total_activo']=$v1[$i]['debito_total_activo'];
							$cuentas_100[$i]['credito_total_activo']=$v1[$i]['credito_total_activo'];
							$cuentas_100[$i]['cod_tipo_cuenta_pasivo']=$v2[$i]['cod_tipo_cuenta_pasivo'];
							$cuentas_100[$i]['cod_cuenta_pasivo']=$v2[$i]['cod_cuenta_pasivo'];
							$cuentas_100[$i]['cuenta_deno_pasivo']=$v2[$i]['cuenta_deno_pasivo'];
							$cuentas_100[$i]['debito_total_pasivo']=$v2[$i]['debito_total_pasivo'];
							$cuentas_100[$i]['credito_total_pasivo']=$v2[$i]['credito_total_pasivo'];
					}
				}



				if(count($v3)>count($v4)){
					for($i=0;$i<count($v3);$i++){
							$cuentas_200[$i]['cod_tipo_cuenta_activo']=$v3[$i]['cod_tipo_cuenta_activo'];
							$cuentas_200[$i]['cod_cuenta_activo']=$v3[$i]['cod_cuenta_activo'];
							$cuentas_200[$i]['cuenta_deno_activo']=$v3[$i]['cuenta_deno_activo'];
							$cuentas_200[$i]['debito_total_activo']=$v3[$i]['debito_total_activo'];
							$cuentas_200[$i]['credito_total_activo']=$v3[$i]['credito_total_activo'];
							$cuentas_200[$i]['cod_tipo_cuenta_pasivo']=$v4[$i]['cod_tipo_cuenta_pasivo'];
							$cuentas_200[$i]['cod_cuenta_pasivo']=$v4[$i]['cod_cuenta_pasivo'];
							$cuentas_200[$i]['cuenta_deno_pasivo']=$v4[$i]['cuenta_deno_pasivo'];
							$cuentas_200[$i]['debito_total_pasivo']=$v4[$i]['debito_total_pasivo'];
							$cuentas_200[$i]['credito_total_pasivo']=$v4[$i]['credito_total_pasivo'];
					}
				}else if(count($v4)>count($v3)){
					for($i=0;$i<count($v4);$i++){
							$cuentas_200[$i]['cod_tipo_cuenta_activo']=$v3[$i]['cod_tipo_cuenta_activo'];
							$cuentas_200[$i]['cod_cuenta_activo']=$v3[$i]['cod_cuenta_activo'];
							$cuentas_200[$i]['cuenta_deno_activo']=$v3[$i]['cuenta_deno_activo'];
							$cuentas_200[$i]['debito_total_activo']=$v3[$i]['debito_total_activo'];
							$cuentas_200[$i]['credito_total_activo']=$v3[$i]['credito_total_activo'];
							$cuentas_200[$i]['cod_tipo_cuenta_pasivo']=$v4[$i]['cod_tipo_cuenta_pasivo'];
							$cuentas_200[$i]['cod_cuenta_pasivo']=$v4[$i]['cod_cuenta_pasivo'];
							$cuentas_200[$i]['cuenta_deno_pasivo']=$v4[$i]['cuenta_deno_pasivo'];
							$cuentas_200[$i]['debito_total_pasivo']=$v4[$i]['debito_total_pasivo'];
							$cuentas_200[$i]['credito_total_pasivo']=$v4[$i]['credito_total_pasivo'];
					}
				}else{
					for($i=0;$i<count($v3);$i++){
							$cuentas_200[$i]['cod_tipo_cuenta_activo']=$v3[$i]['cod_tipo_cuenta_activo'];
							$cuentas_200[$i]['cod_cuenta_activo']=$v3[$i]['cod_cuenta_activo'];
							$cuentas_200[$i]['cuenta_deno_activo']=$v3[$i]['cuenta_deno_activo'];
							$cuentas_200[$i]['debito_total_activo']=$v3[$i]['debito_total_activo'];
							$cuentas_200[$i]['credito_total_activo']=$v3[$i]['credito_total_activo'];
							$cuentas_200[$i]['cod_tipo_cuenta_pasivo']=$v4[$i]['cod_tipo_cuenta_pasivo'];
							$cuentas_200[$i]['cod_cuenta_pasivo']=$v4[$i]['cod_cuenta_pasivo'];
							$cuentas_200[$i]['cuenta_deno_pasivo']=$v4[$i]['cuenta_deno_pasivo'];
							$cuentas_200[$i]['debito_total_pasivo']=$v4[$i]['debito_total_pasivo'];
							$cuentas_200[$i]['credito_total_pasivo']=$v4[$i]['credito_total_pasivo'];
					}
				}


				if(count($v5)>count($v6)){
					for($i=0;$i<count($v5);$i++){
							$cuentas_300[$i]['cod_tipo_cuenta_activo']=$v5[$i]['cod_tipo_cuenta_activo'];
							$cuentas_300[$i]['cod_cuenta_activo']=$v5[$i]['cod_cuenta_activo'];
							$cuentas_300[$i]['cuenta_deno_activo']=$v5[$i]['cuenta_deno_activo'];
							$cuentas_300[$i]['debito_total_activo']=$v5[$i]['debito_total_activo'];
							$cuentas_300[$i]['credito_total_activo']=$v5[$i]['credito_total_activo'];
							$cuentas_300[$i]['cod_tipo_cuenta_pasivo']=$v6[$i]['cod_tipo_cuenta_pasivo'];
							$cuentas_300[$i]['cod_cuenta_pasivo']=$v6[$i]['cod_cuenta_pasivo'];
							$cuentas_300[$i]['cuenta_deno_pasivo']=$v6[$i]['cuenta_deno_pasivo'];
							$cuentas_300[$i]['debito_total_pasivo']=$v6[$i]['debito_total_pasivo'];
							$cuentas_300[$i]['credito_total_pasivo']=$v6[$i]['credito_total_pasivo'];
					}
				}else if(count($v6)>count($v5)){
					for($i=0;$i<count($v4);$i++){
							$cuentas_300[$i]['cod_tipo_cuenta_activo']=$v5[$i]['cod_tipo_cuenta_activo'];
							$cuentas_300[$i]['cod_cuenta_activo']=$v5[$i]['cod_cuenta_activo'];
							$cuentas_300[$i]['cuenta_deno_activo']=$v5[$i]['cuenta_deno_activo'];
							$cuentas_300[$i]['debito_total_activo']=$v5[$i]['debito_total_activo'];
							$cuentas_300[$i]['credito_total_activo']=$v5[$i]['credito_total_activo'];
							$cuentas_300[$i]['cod_tipo_cuenta_pasivo']=$v6[$i]['cod_tipo_cuenta_pasivo'];
							$cuentas_300[$i]['cod_cuenta_pasivo']=$v6[$i]['cod_cuenta_pasivo'];
							$cuentas_300[$i]['cuenta_deno_pasivo']=$v6[$i]['cuenta_deno_pasivo'];
							$cuentas_300[$i]['debito_total_pasivo']=$v6[$i]['debito_total_pasivo'];
							$cuentas_300[$i]['credito_total_pasivo']=$v6[$i]['credito_total_pasivo'];
					}
				}else{
					for($i=0;$i<count($v5);$i++){
							$cuentas_300[$i]['cod_tipo_cuenta_activo']=$v5[$i]['cod_tipo_cuenta_activo'];
							$cuentas_300[$i]['cod_cuenta_activo']=$v5[$i]['cod_cuenta_activo'];
							$cuentas_300[$i]['cuenta_deno_activo']=$v5[$i]['cuenta_deno_activo'];
							$cuentas_300[$i]['debito_total_activo']=$v5[$i]['debito_total_activo'];
							$cuentas_300[$i]['credito_total_activo']=$v5[$i]['credito_total_activo'];
							$cuentas_300[$i]['cod_tipo_cuenta_pasivo']=$v6[$i]['cod_tipo_cuenta_pasivo'];
							$cuentas_300[$i]['cod_cuenta_pasivo']=$v6[$i]['cod_cuenta_pasivo'];
							$cuentas_300[$i]['cuenta_deno_pasivo']=$v6[$i]['cuenta_deno_pasivo'];
							$cuentas_300[$i]['debito_total_pasivo']=$v6[$i]['debito_total_pasivo'];
							$cuentas_300[$i]['credito_total_pasivo']=$v6[$i]['credito_total_pasivo'];
					}
				}


				if(count($v7)>count($v8)){
					for($i=0;$i<=count($v7);$i++){
							$cuentas_400[$i]['cod_tipo_cuenta_activo']=$v7[$i]['cod_tipo_cuenta_activo'];
							$cuentas_400[$i]['cod_cuenta_activo']=$v7[$i]['cod_cuenta_activo'];
							$cuentas_400[$i]['cuenta_deno_activo']=$v7[$i]['cuenta_deno_activo'];
							$cuentas_400[$i]['debito_total_activo']=$v7[$i]['debito_total_activo'];
							$cuentas_400[$i]['credito_total_activo']=$v7[$i]['credito_total_activo'];
							$cuentas_400[$i]['cod_tipo_cuenta_pasivo']=$v8[$i]['cod_tipo_cuenta_pasivo'];
							$cuentas_400[$i]['cod_cuenta_pasivo']=$v8[$i]['cod_cuenta_pasivo'];
							$cuentas_400[$i]['cuenta_deno_pasivo']=$v8[$i]['cuenta_deno_pasivo'];
							$cuentas_400[$i]['debito_total_pasivo']=$v8[$i]['debito_total_pasivo'];
							$cuentas_400[$i]['credito_total_pasivo']=$v8[$i]['credito_total_pasivo'];
					}
				}else if(count($v8)>count($v7)){
					for($i=0;$i<count($v8);$i++){
							$cuentas_400[$i]['cod_tipo_cuenta_activo']=$v7[$i]['cod_tipo_cuenta_activo'];
							$cuentas_400[$i]['cod_cuenta_activo']=$v7[$i]['cod_cuenta_activo'];
							$cuentas_400[$i]['cuenta_deno_activo']=$v7[$i]['cuenta_deno_activo'];
							$cuentas_400[$i]['debito_total_activo']=$v7[$i]['debito_total_activo'];
							$cuentas_400[$i]['credito_total_activo']=$v7[$i]['credito_total_activo'];
							$cuentas_400[$i]['cod_tipo_cuenta_pasivo']=$v8[$i]['cod_tipo_cuenta_pasivo'];
							$cuentas_400[$i]['cod_cuenta_pasivo']=$v8[$i]['cod_cuenta_pasivo'];
							$cuentas_400[$i]['cuenta_deno_pasivo']=$v8[$i]['cuenta_deno_pasivo'];
							$cuentas_400[$i]['debito_total_pasivo']=$v8[$i]['debito_total_pasivo'];
							$cuentas_400[$i]['credito_total_pasivo']=$v8[$i]['credito_total_pasivo'];
					}
				}else{
					for($i=0;$i<count($v7);$i++){
							$cuentas_400[$i]['cod_tipo_cuenta_activo']=$v7[$i]['cod_tipo_cuenta_activo'];
							$cuentas_400[$i]['cod_cuenta_activo']=$v7[$i]['cod_cuenta_activo'];
							$cuentas_400[$i]['cuenta_deno_activo']=$v7[$i]['cuenta_deno_activo'];
							$cuentas_400[$i]['debito_total_activo']=$v7[$i]['debito_total_activo'];
							$cuentas_400[$i]['credito_total_activo']=$v7[$i]['credito_total_activo'];
							$cuentas_400[$i]['cod_tipo_cuenta_pasivo']=$v8[$i]['cod_tipo_cuenta_pasivo'];
							$cuentas_400[$i]['cod_cuenta_pasivo']=$v8[$i]['cod_cuenta_pasivo'];
							$cuentas_400[$i]['cuenta_deno_pasivo']=$v8[$i]['cuenta_deno_pasivo'];
							$cuentas_400[$i]['debito_total_pasivo']=$v8[$i]['debito_total_pasivo'];
							$cuentas_400[$i]['credito_total_pasivo']=$v8[$i]['credito_total_pasivo'];
					}
				}

//pr($v5);
//pr($v6);
//pr($cuentas_100);
//pr($datos);
				$this->set('datos',$datos);
				$this->set('cuentas_100',$cuentas_100);
				$this->set('cuentas_200',$cuentas_200);
				$this->set('cuentas_300',$cuentas_300);
				$this->set('cuentas_400',$cuentas_400);
			}else{
				$this->set('datos',null);
				$this->set('cuentas_100',null);
				$this->set('cuentas_200',null);
				$this->set('cuentas_300',null);
				$this->set('cuentas_400',null);
			}
		   /* $a=$this->ccfd02->execute("SELECT a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.ano_fiscal,a.cod_tipo_cuenta,a.cod_cuenta from ccfd02 a WHERE ".$filtro." and cod_tipo_cuenta=1 GROUP BY a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.ano_fiscal,a.cod_tipo_cuenta,a.cod_cuenta");
		    $b=$this->ccfd02->execute("SELECT a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.ano_fiscal,a.cod_tipo_cuenta,a.cod_cuenta from ccfd02 a WHERE ".$filtro." and cod_tipo_cuenta=2 GROUP BY a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.ano_fiscal,a.cod_tipo_cuenta,a.cod_cuenta");
			$n1=count($a);
			$n2=count($b);
			$datos=$this->ccfd02->execute($sql);
			if($datos!=null){
				$this->set('datos',$datos);
			if($n1>=$n2){
				for($i=0;$i<count($datos);$i++){
					if($datos[$i][0]['cod_tipo_cuenta']==1){
						$vec[$i]['cod_activo']=$datos[$i][0]['cod_cuenta'];
						$vec[$i]['denominacion_activo']=$datos[$i][0]['denominacion_cuenta'];
						$vec[$i]['debe_activo']=$datos[$i][0][$debito_total];
						$vec[$i]['haber_activo']=$datos[$i][0][$credito_total];
						$vec[$i]['cod_pasivo']='';
						$vec[$i]['denominacion_pasivo']='';
						$vec[$i]['debe_pasivo']='';
						$vec[$i]['haber_pasivo']='';
					}
				}//fin foreach
				$k=0;
				for($i=0;$i<count($datos);$i++){
					if($datos[$i][0]['cod_tipo_cuenta']==2){
						$vec[$k]['cod_activo']=$vec[$k]['cod_activo'];
						$vec[$k]['denominacion_activo']=$vec[$k]['denominacion_activo'];
						$vec[$k]['debe_activo']=$vec[$k]['debe_activo'];
						$vec[$k]['haber_activo']=$vec[$k]['haber_activo'];
						$vec[$k]['cod_pasivo']=$datos[$i][0]['cod_cuenta'];
						$vec[$k]['denominacion_pasivo']=$datos[$i][0]['denominacion_cuenta'];
						$vec[$k]['debe_pasivo']=$datos[$i][0][$debito_total];
						$vec[$k]['haber_pasivo']=$datos[$i][0][$credito_total];
						$k++;
					}

				}//fin foreach
			}else{
				$p=0;
				for($i=0;$i<count($datos);$i++){
					if($datos[$i][0]['cod_tipo_cuenta']==2){
						$vec[$p]['cod_activo']='';
						$vec[$p]['denominacion_activo']='';
						$vec[$p]['debe_activo']='';
						$vec[$p]['haber_activo']='';
						$vec[$p]['cod_pasivo']=$datos[$i][0]['cod_cuenta'];
						$vec[$p]['denominacion_pasivo']=$datos[$i][0]['denominacion_cuenta'];
						$vec[$p]['debe_pasivo']=$datos[$i][0][$debito_total];
						$vec[$p]['haber_pasivo']=$datos[$i][0][$credito_total];
						$p++;
					}
				}//fin foreach
					$k=0;
				for($i=0;$i<count($datos);$i++){
					if($datos[$i][0]['cod_tipo_cuenta']==1){
						$vec[$k]['cod_activo']=$datos[$i][0]['cod_cuenta'];
						$vec[$k]['denominacion_activo']=$datos[$i][0]['denominacion_cuenta'];
						$vec[$k]['debe_activo']=$datos[$i][0][$debito_total];
						$vec[$k]['haber_activo']=$datos[$i][0][$credito_total];
						$vec[$k]['cod_pasivo']=$vec[$k]['cod_pasivo'];
						$vec[$k]['denominacion_pasivo']=$vec[$k]['denominacion_pasivo'];
						$vec[$k]['debe_pasivo']=$vec[$k]['debe_pasivo'];
						$vec[$k]['haber_pasivo']=$vec[$k]['haber_pasivo'];
						$k++;
					}

				}//fin foreach
			}

//			pr($vec);
			$this->set('datos',$vec);
			}else{
				$this->set('datos',null);
			}*/
		}else{
//			echo'<script>history.back(1);</script>';
			$this->set('datos',null);
		}//fin if ano y mes vacio
	}//fin ir si


}//fin funcion



function firmas_balance_general($var=null){
	$this->layout="ajax";
	$cp  = $this->Session->read('SScodpresi');
	$ce  = $this->Session->read('SScodentidad');
	$cti = $this->Session->read('SScodtipoinst');
	$ci  = $this->Session->read('SScodinst');
	$cd  = $this->Session->read('SScoddep');

	if(!empty($this->data['cfpp00']['nombre_primera_firma']) && !empty($this->data['cfpp00']['cargo_primera_firma']) && !empty($this->data['cfpp00']['nombre_segunda_firma']) && !empty($this->data['cfpp00']['cargo_segunda_firma'])){
		$tipo_doc_anul = $this->data['cugp03_acta_anulacion']['tipo_doc_anul'];

		$tipo_doc_anul=703;
		$nombre_primera_firma = $this->data['cfpp00']['nombre_primera_firma'];
		$cargo_primera_firma  = $this->data['cfpp00']['cargo_primera_firma'];
		$nombre_segunda_firma = $this->data['cfpp00']['nombre_segunda_firma'];
		$cargo_segunda_firma  = $this->data['cfpp00']['cargo_segunda_firma'];
		$nombre_tercera_firma = "0";
		$cargo_tercera_firma  = "0";
		$nombre_cuarta_firma = "0";
		$cargo_cuarta_firma  = "0";

		$primera_cc = "0";
		$segunda_cc = "0";
		$tercera_cc = "0";
		$cuarta_cc  = "0";
		$quinta_cc  = "0";
		$sexta_cc   = "0";
		$septima_cc = "0";
		$octava_cc  = "0";


		$cont = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA()." and tipo_documento='703'");
    	if($cont==0){
    		$insert = "INSERT INTO cugd07_firmas_oficio_anulacion VALUES ($cp, $ce, $cti, $ci, $cd, $tipo_doc_anul,'$nombre_primera_firma', '$cargo_primera_firma', '$nombre_segunda_firma', '$cargo_segunda_firma', '$nombre_tercera_firma', '$cargo_tercera_firma', '$nombre_cuarta_firma', '$cargo_cuarta_firma', '$primera_cc', '$segunda_cc', '$tercera_cc', '$cuarta_cc', '$quinta_cc', '$sexta_cc')";
			$this->cugd07_firmas_oficio_anulacion->execute($insert);

    	}else{
			$insert = "UPDATE cugd07_firmas_oficio_anulacion set nombre_primera_firma='$nombre_primera_firma',cargo_primera_firma='$cargo_primera_firma',nombre_segunda_firma='$nombre_segunda_firma',cargo_segunda_firma='$cargo_segunda_firma' WHERE ".$this->SQLCA()." and tipo_documento=703";
			$this->cugd07_firmas_oficio_anulacion->execute($insert);
    	}



	$this->set('Message_existe','Las firmas fuer&oacute;n registradas correctamente');
//	echo" <script> ver_documento('/reporte_contabilidad/seleccion_actividad/$cod_actividad','tab_pestana_descripcion_proyecto_2'); </script>";
$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=703");
			$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
			$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
			$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
			$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
			$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
			$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
			$this->set('tipo_doc_anul',703);
			$this->set('firma',2);
	}else{
		$this->set('errorMessage','debe ingresar las firmas y cargos');

	}

	echo "<script>document.getElementById('b_modificar_firmas').disabled=false;</script>";


}



function relacion_asientos_diarios($ir=null,$var=null){
	if($ir=='no'){
			$this->layout="ajax";
			$this->set('ir','no');
			$this->set('year',$this->ano_ejecucion());


			$datos  = $this->ccfd10_descripcion->execute(" SELECT DISTINCT ano_asiento FROM ccfd10_descripcion WHERE ".$this->condicion()." ORDER BY ano_asiento ASC");
			if(count($datos)!=0){
				foreach($datos as $n){
					$lista[$n[0]['ano_asiento']]=$n[0]['ano_asiento'];
			    }
			}else{
				$lista=array('0'=>'No existen datos');
			}
			$this->set("lista_ano", $lista);

			$this->Session->write('ano_asientos_diarios',$this->ano_ejecucion());


	}else if($ir=='peticion'){
		$this->layout="ajax";
		$this->set('ir','peticion');
			if($var==2 || $var==3 || $var==4){
				$meses= array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
				$this->concatena($meses, 'mes');

			}
		$this->set('opcion',$var);

	}else if($ir=='dias'){
		$this->layout="ajax";
		$this->set('ir','dias');
		$ano=$this->Session->read('ano_asientos_diarios');
		$this->Session->write('mes_asientos_diarios',$var);
		$lista=$this->ccfd10_descripcion->execute("select distinct b.dia_asiento from ccfd10_descripcion b
												where ".$this->SQLCA(). " and ano_asiento=".$ano." and mes_asiento=".$var);
//		echo " and ano_asiento=".$ano." and mes_asiento=".$var;
		if($lista!=null){
			$i=1;
			foreach($lista as $l){
				$r[]=$l[0]["dia_asiento"];
				$i++;
			}
			$lista = array_combine($r, $r);
		}else{
			$lista=array();
		}

 		$this->set('dias',$lista);
		$this->set('opcion',$var);

	}else if($ir=='ano'){
		$this->layout="ajax";
		$this->Session->write('ano_asientos_diarios',$var);

		echo "<script>";
			echo "if(document.getElementById('mes'))document.getElementById('mes').value='';";
			echo "if(document.getElementById('mes'))document.getElementById('dia').value='';";
			echo "if(document.getElementById('mes'))document.getElementById('asiento').value='';";

		echo "</script>";


	}else if($ir=='numero'){
		$this->layout="ajax";
		$this->set('ir','numero');
		$ano=$this->Session->read('ano_asientos_diarios');
		$mes=$this->Session->read('mes_asientos_diarios');
		$lista=$this->ccfd10_descripcion->execute("select b.numero_asiento from ccfd10_descripcion b
												where ".$this->SQLCA(). " and ano_asiento=".$ano." and mes_asiento=".$mes." and dia_asiento=".$var);
//		echo " and ano_asiento=".$ano." and mes_asiento=".$mes." and dia_asiento=".$var;
		if($lista!=null){
			$i=1;
			foreach($lista as $l){
				$r[]=$l[0]["numero_asiento"];
				$i++;
			}
			$lista = array_combine($r, $r);
		}else{
			$lista=array();
		}
 		$this->set('numeros',$lista);
		$this->set('opcion',$var);

	}else if($ir=='si'){
		$this->layout="pdf";
		$this->set('ir','si');


		$ano=$this->data['cfpp00']['ano'];

		if($this->data['cfpp00']['tipo_peticion']==2){
			$mes=$this->data['cfpp00']['mes'];
			$filtro=$this->SQLCA()." and ano_asiento=".$ano." and mes_asiento=".$mes;

		}else if($this->data['cfpp00']['tipo_peticion']==3){
			$mes=$this->data['cfpp00']['mes'];
			$dia=$this->data['cfpp00']['dia_day'];
			$filtro=$this->SQLCA()." and ano_asiento=".$ano." and mes_asiento=".$mes." and dia_asiento=".$dia;

		}else if($this->data['cfpp00']['tipo_peticion']==4){
			$mes=$this->data['cfpp00']['mes'];
			$dia=$this->data['cfpp00']['dia_day'];
			$asiento=$this->data['cfpp00']['num_asiento'];
			$filtro=$this->SQLCA()." and ano_asiento=".$ano." and mes_asiento=".$mes." and dia_asiento=".$dia." and numero_asiento=".$asiento;

		}else{
			$filtro=$this->SQLCA()." and ano_asiento=".$ano;
		}

		$sql="SELECT
			a.cod_presi,
			a.cod_entidad,
			a.cod_tipo_inst,
			a.cod_inst,
			a.cod_dep,
			a.ano_asiento,
			a.mes_asiento,
			a.dia_asiento,
			a.numero_asiento,
			a.numero_linea,
			a.debito_credito,
			a.cod_tipo_cuenta,
			a.cod_cuenta,
			a.cod_subcuenta,
			a.cod_division,
			a.cod_subdivision,
			a.monto,
			(select b.instancia_asiento from ccfd10_descripcion b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano_asiento=a.ano_asiento and b.mes_asiento=a.mes_asiento and b.dia_asiento=a.dia_asiento and b.numero_asiento=a.numero_asiento) as instancia_asiento,
			(select b.concepto from ccfd10_descripcion b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano_asiento=a.ano_asiento and b.mes_asiento=a.mes_asiento and b.dia_asiento=a.dia_asiento and b.numero_asiento=a.numero_asiento) as concepto,
			(select b.tipo_documento from ccfd10_descripcion b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano_asiento=a.ano_asiento and b.mes_asiento=a.mes_asiento and b.dia_asiento=a.dia_asiento and b.numero_asiento=a.numero_asiento) as tipo_documento,
			(select b.numero_documento from ccfd10_descripcion b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano_asiento=a.ano_asiento and b.mes_asiento=a.mes_asiento and b.dia_asiento=a.dia_asiento and b.numero_asiento=a.numero_asiento) as numero_documento,
			(select b.fecha_documento from ccfd10_descripcion b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano_asiento=a.ano_asiento and b.mes_asiento=a.mes_asiento and b.dia_asiento=a.dia_asiento and b.numero_asiento=a.numero_asiento) as fecha_documento,
			(select b.denominacion from ccfd01_tipo b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_cuenta=a.cod_tipo_cuenta) as deno_tipo_cuenta,
			(select b.denominacion from ccfd01_cuenta b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_cuenta=a.cod_tipo_cuenta and b.cod_cuenta=a.cod_cuenta) as deno_cuenta,
			(select b.denominacion from ccfd01_subcuenta b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_cuenta=a.cod_tipo_cuenta and b.cod_cuenta=a.cod_cuenta and b.cod_subcuenta=a.cod_subcuenta) as deno_subcuenta,
			(select b.denominacion from ccfd01_division b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_cuenta=a.cod_tipo_cuenta and b.cod_cuenta=a.cod_cuenta and b.cod_subcuenta=a.cod_subcuenta and b.cod_division=a.cod_division) as deno_division,
			(select b.denominacion from ccfd01_subdivision b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_cuenta=a.cod_tipo_cuenta and b.cod_cuenta=a.cod_cuenta and b.cod_subcuenta=a.cod_subcuenta and b.cod_division=a.cod_division and b.cod_subdivision=a.cod_subdivision) as deno_subdivision
			  FROM ccfd10_detalles a where ".$filtro." order by a.ano_asiento,a.mes_asiento,a.dia_asiento,a.numero_asiento,a.numero_linea ASC ;";


			 $datos  = $this->ccfd10_descripcion->execute($sql);
			 if($datos!=null){
			 	$this->set('datos',$datos);
			 }else{
			 	$this->set('datos',null);
			 }
	}

}//fin funcion relacion_asientos_diarios






function comprobante_diario($ir=null){
	$meses= array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
	if($ir=='no'){
		$this->layout="ajax";

		$this->set('year',$this->ano_ejecucion());

		$datos  = $this->ccfd10_descripcion->execute(" SELECT DISTINCT ano_asiento FROM ccfd10_descripcion WHERE ".$this->condicion()." ORDER BY ano_asiento ASC");
		if(count($datos)!=0){
			foreach($datos as $n){
				$lista[$n[0]['ano_asiento']]=$n[0]['ano_asiento'];
		    }
		}else{
			$lista=array('0'=>'No existen datos');
		}
		$this->set("lista_ano", $lista);

		$this->set('m',date('m'));

//		$this->concatena($meses, 'mes');
		$this->set('mes',$meses);
		$this->set('ir','no');


	}else if($ir=='si'){
		$this->layout="pdf";
		$this->set('ir','si');
		$filtro='';
		$_SESSION['ano_diario_c']='';
		$_SESSION['mes_diario_c']='';

		if(!empty($this->data['cfpp00']['ano']) && !empty($this->data['cfpp00']['mes'])){
			$ano=$this->data['cfpp00']['ano'];
			$mes=$this->data['cfpp00']['mes'];
			$_SESSION['ano_diario_c']=$ano;
			$_SESSION['mes_diario_c']=$meses[$mes];
			$filtro=$this->SQLCA()." and ano_asiento=".$ano." and mes_asiento=".$mes;

			$sql="SELECT
			a.cod_presi,
			a.cod_entidad,
			a.cod_tipo_inst,
			a.cod_inst,
			a.cod_dep,
			a.ano_asiento,
			a.mes_asiento,
			a.dia_asiento,
			a.numero_asiento,
			a.numero_linea,
			a.debito_credito,
			a.cod_tipo_cuenta,
			a.cod_cuenta,
			a.cod_subcuenta,
			a.cod_division,
			a.cod_subdivision,
			a.monto,
			(select b.concepto from ccfd10_descripcion b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano_asiento=a.ano_asiento and b.mes_asiento=a.mes_asiento and b.dia_asiento=a.dia_asiento and b.numero_asiento=a.numero_asiento) as concepto,
			(select b.tipo_documento from ccfd10_descripcion b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano_asiento=a.ano_asiento and b.mes_asiento=a.mes_asiento and b.dia_asiento=a.dia_asiento and b.numero_asiento=a.numero_asiento) as tipo_documento,
			(select b.numero_documento from ccfd10_descripcion b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano_asiento=a.ano_asiento and b.mes_asiento=a.mes_asiento and b.dia_asiento=a.dia_asiento and b.numero_asiento=a.numero_asiento) as numero_documento,
			(select b.fecha_documento from ccfd10_descripcion b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano_asiento=a.ano_asiento and b.mes_asiento=a.mes_asiento and b.dia_asiento=a.dia_asiento and b.numero_asiento=a.numero_asiento) as fecha_documento,
			(select b.denominacion from ccfd01_tipo b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_cuenta=a.cod_tipo_cuenta) as deno_tipo_cuenta,
			(select b.denominacion from ccfd01_cuenta b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_cuenta=a.cod_tipo_cuenta and b.cod_cuenta=a.cod_cuenta) as deno_cuenta,
			(select b.denominacion from ccfd01_subcuenta b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_cuenta=a.cod_tipo_cuenta and b.cod_cuenta=a.cod_cuenta and b.cod_subcuenta=a.cod_subcuenta) as deno_subcuenta,
			(select b.denominacion from ccfd01_division b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_cuenta=a.cod_tipo_cuenta and b.cod_cuenta=a.cod_cuenta and b.cod_subcuenta=a.cod_subcuenta and b.cod_division=a.cod_division) as deno_division,
			(select b.denominacion from ccfd01_subdivision b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_cuenta=a.cod_tipo_cuenta and b.cod_cuenta=a.cod_cuenta and b.cod_subcuenta=a.cod_subcuenta and b.cod_division=a.cod_division and b.cod_subdivision=a.cod_subdivision) as deno_subdivision
			  FROM ccfd10_detalles a where ".$filtro." order by a.ano_asiento,a.mes_asiento,a.dia_asiento,a.numero_asiento,a.numero_linea ASC ;";


			 $datos  = $this->ccfd10_descripcion->execute($sql);
			 if($datos!=null){
			 	$this->set('datos',$datos);
			 }else{
			 	$this->set('datos',null);
			 }


		}else{
			$this->set('datos',null);
		}


	}//fin ir si


}//fin funcion comprobante_diario




function resumen_situacion_financiera($ir=null){
	$meses= array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
	if($ir=='no'){
		$this->layout="ajax";

		$this->set('year',$this->ano_ejecucion());

		$datos  = $this->ccfd10_descripcion->execute(" SELECT DISTINCT ano_asiento FROM ccfd10_descripcion WHERE ".$this->condicion()." ORDER BY ano_asiento ASC");
		if(count($datos)!=0){
			foreach($datos as $n){
				$lista[$n[0]['ano_asiento']]=$n[0]['ano_asiento'];
		    }
		}else{
			$lista=array('0'=>'No existen datos');
		}
		$this->set("lista_ano", $lista);

		$this->set('m',date('m'));

//		$this->concatena($meses, 'mes');
		$this->set('mes',$meses);
		$this->set('ir','no');

		$tipo_c = $this->ccfd01_tipo->generateList($this->SQLCA(), 'cod_tipo_cuenta ASC', null, '{n}.ccfd01_tipo.cod_tipo_cuenta', '{n}.ccfd01_tipo.denominacion');
   		if($tipo_c!=null){
   			$this->concatena($tipo_c, 'tipo');
   		}else{
   			$this->set('tipo',array());
   		}

   			$this->set('vector',array());



	}else if($ir=='si'){
		$this->layout="pdf";
		$this->set('ir','si');
		$filtro='';
		$_SESSION['ano_diario_c']='';
		$_SESSION['mes_diario_c']='';



		if(!empty($this->data['cfpp00']['ano']) && !empty($this->data['cfpp00']['mes'])){
			$ano=$this->data['cfpp00']['ano'];
			$mes=$this->data['cfpp00']['mes'];
			$_SESSION['ano_diario_c']=$ano;
			$_SESSION['mes_diario_c']=$meses[$mes];
			$ultimo_dia = date('d/m/Y',mktime(0, 0, 0, $mes, 0, $ano));

			if(!empty($this->data['cfpp00']['codigo_tipo']) && empty($this->data['cfpp00']['cod_contable']) && empty($this->data['cfpp00']['cod_subcuenta_contable']) && empty($this->data['cfpp00']['cod_div_estadistica_contable']) && empty($this->data['cfpp00']['cod_subdiv_estadistica_contable'])){
				$codigo_tipo=$this->data['cfpp00']['codigo_tipo'];
				$filtro=$this->SQLCA()." and ano_asiento=".$ano." and mes_asiento=".$mes." and cod_tipo_cuenta=".$codigo_tipo;
				$filtro2=$this->SQLCA()." and ano_asiento=".$ano." and mes_asiento<".$mes." and cod_tipo_cuenta=".$codigo_tipo;
				$filtro3=$this->SQLCA()." and ano_asiento=".$ano." and cod_tipo_cuenta=".$codigo_tipo;

			}else if(!empty($this->data['cfpp00']['codigo_tipo']) && !empty($this->data['cfpp00']['cod_contable']) && empty($this->data['cfpp00']['cod_subcuenta_contable']) && empty($this->data['cfpp00']['cod_div_estadistica_contable']) && empty($this->data['cfpp00']['cod_subdiv_estadistica_contable'])){
				$codigo_tipo=$this->data['cfpp00']['codigo_tipo'];
				$cod_contable=$this->data['cfpp00']['cod_contable'];
				$filtro=$this->SQLCA()." and ano_asiento=".$ano." and mes_asiento=".$mes." and cod_tipo_cuenta=".$codigo_tipo." and cod_cuenta=".$cod_contable;
				$filtro2=$this->SQLCA()." and ano_asiento=".$ano." and mes_asiento<".$mes." and cod_tipo_cuenta=".$codigo_tipo." and cod_cuenta=".$cod_contable;
				$filtro3=$this->SQLCA()." and ano_asiento=".$ano." and cod_tipo_cuenta=".$codigo_tipo." and cod_cuenta=".$cod_contable;

			}else if(!empty($this->data['cfpp00']['codigo_tipo']) && !empty($this->data['cfpp00']['cod_contable']) && !empty($this->data['cfpp00']['cod_subcuenta_contable']) && empty($this->data['cfpp00']['cod_div_estadistica_contable']) && empty($this->data['cfpp00']['cod_subdiv_estadistica_contable'])){
				$codigo_tipo=$this->data['cfpp00']['codigo_tipo'];
				$cod_contable=$this->data['cfpp00']['cod_contable'];
				$cod_subcuenta_contable=$this->data['cfpp00']['cod_subcuenta_contable'];
				$filtro=$this->SQLCA()." and ano_asiento=".$ano." and mes_asiento=".$mes." and cod_tipo_cuenta=".$codigo_tipo." and cod_cuenta=".$cod_contable." and cod_subcuenta=".$cod_subcuenta_contable;
				$filtro2=$this->SQLCA()." and ano_asiento=".$ano." and mes_asiento<".$mes." and cod_tipo_cuenta=".$codigo_tipo." and cod_cuenta=".$cod_contable." and cod_subcuenta=".$cod_subcuenta_contable;
				$filtro3=$this->SQLCA()." and ano_asiento=".$ano." and cod_tipo_cuenta=".$codigo_tipo." and cod_cuenta=".$cod_contable." and cod_subcuenta=".$cod_subcuenta_contable;

			}else if(!empty($this->data['cfpp00']['codigo_tipo']) && !empty($this->data['cfpp00']['cod_contable']) && !empty($this->data['cfpp00']['cod_subcuenta_contable']) && !empty($this->data['cfpp00']['cod_div_estadistica_contable']) && empty($this->data['cfpp00']['cod_subdiv_estadistica_contable'])){
				$codigo_tipo=$this->data['cfpp00']['codigo_tipo'];
				$cod_contable=$this->data['cfpp00']['cod_contable'];
				$cod_subcuenta_contable=$this->data['cfpp00']['cod_subcuenta_contable'];
				$cod_div_estadistica_contable=$this->data['cfpp00']['cod_div_estadistica_contable'];
				$filtro=$this->SQLCA()." and ano_asiento=".$ano." and mes_asiento=".$mes." and cod_tipo_cuenta=".$codigo_tipo." and cod_cuenta=".$cod_contable." and cod_subcuenta=".$cod_subcuenta_contable." and cod_division=".$cod_div_estadistica_contable;
				$filtro2=$this->SQLCA()." and ano_asiento=".$ano." and mes_asiento<".$mes." and cod_tipo_cuenta=".$codigo_tipo." and cod_cuenta=".$cod_contable." and cod_subcuenta=".$cod_subcuenta_contable." and cod_division=".$cod_div_estadistica_contable;
				$filtro3=$this->SQLCA()." and ano_asiento=".$ano." and cod_tipo_cuenta=".$codigo_tipo." and cod_cuenta=".$cod_contable." and cod_subcuenta=".$cod_subcuenta_contable." and cod_division=".$cod_div_estadistica_contable;

			}else if(!empty($this->data['cfpp00']['codigo_tipo']) && !empty($this->data['cfpp00']['cod_contable']) && !empty($this->data['cfpp00']['cod_subcuenta_contable']) && !empty($this->data['cfpp00']['cod_div_estadistica_contable']) && !empty($this->data['cfpp00']['cod_subdiv_estadistica_contable'])){
				$codigo_tipo=$this->data['cfpp00']['codigo_tipo'];
				$cod_contable=$this->data['cfpp00']['cod_contable'];
				$cod_subcuenta_contable=$this->data['cfpp00']['cod_subcuenta_contable'];
				$cod_div_estadistica_contable=$this->data['cfpp00']['cod_div_estadistica_contable'];
				$cod_subdiv_estadistica_contable=$this->data['cfpp00']['cod_subdiv_estadistica_contable'];
				$filtro=$this->SQLCA()." and ano_asiento=".$ano." and mes_asiento=".$mes." and cod_tipo_cuenta=".$codigo_tipo." and cod_cuenta=".$cod_contable." and cod_subcuenta=".$cod_subcuenta_contable." and cod_division=".$cod_div_estadistica_contable." and cod_subdivision=".$cod_subdiv_estadistica_contable;
				$filtro2=$this->SQLCA()." and ano_asiento=".$ano." and mes_asiento<".$mes." and cod_tipo_cuenta=".$codigo_tipo." and cod_cuenta=".$cod_contable." and cod_subcuenta=".$cod_subcuenta_contable." and cod_division=".$cod_div_estadistica_contable." and cod_subdivision=".$cod_subdiv_estadistica_contable;
				$filtro3=$this->SQLCA()." and ano_asiento=".$ano." and cod_tipo_cuenta=".$codigo_tipo." and cod_cuenta=".$cod_contable." and cod_subcuenta=".$cod_subcuenta_contable." and cod_division=".$cod_div_estadistica_contable." and cod_subdivision=".$cod_subdiv_estadistica_contable;

			}

		$sql="SELECT
			a.cod_presi,
			a.cod_entidad,
			a.cod_tipo_inst,
			a.cod_inst,
			a.cod_dep,
			a.ano_asiento,
			a.mes_asiento,
			a.dia_asiento,
			a.numero_asiento,
			a.numero_linea,
			a.debito_credito,
			a.cod_tipo_cuenta,
			a.cod_cuenta,
			a.cod_subcuenta,
			a.cod_division,
			a.cod_subdivision,
			a.monto,
			(select b.instancia_asiento from ccfd10_descripcion b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano_asiento=a.ano_asiento and b.mes_asiento=a.mes_asiento and b.dia_asiento=a.dia_asiento and b.numero_asiento=a.numero_asiento) as instancia_asiento,
			(select b.concepto from ccfd10_descripcion b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano_asiento=a.ano_asiento and b.mes_asiento=a.mes_asiento and b.dia_asiento=a.dia_asiento and b.numero_asiento=a.numero_asiento) as concepto,
			(select b.tipo_documento from ccfd10_descripcion b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano_asiento=a.ano_asiento and b.mes_asiento=a.mes_asiento and b.dia_asiento=a.dia_asiento and b.numero_asiento=a.numero_asiento) as tipo_documento,
			(select b.numero_documento from ccfd10_descripcion b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano_asiento=a.ano_asiento and b.mes_asiento=a.mes_asiento and b.dia_asiento=a.dia_asiento and b.numero_asiento=a.numero_asiento) as numero_documento,
			(select b.fecha_documento from ccfd10_descripcion b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.ano_asiento=a.ano_asiento and b.mes_asiento=a.mes_asiento and b.dia_asiento=a.dia_asiento and b.numero_asiento=a.numero_asiento) as fecha_documento,
			(select b.denominacion from ccfd01_tipo b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_cuenta=a.cod_tipo_cuenta) as deno_tipo_cuenta,
			(select b.denominacion from ccfd01_cuenta b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_cuenta=a.cod_tipo_cuenta and b.cod_cuenta=a.cod_cuenta) as deno_cuenta,
			(select b.denominacion from ccfd01_subcuenta b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_cuenta=a.cod_tipo_cuenta and b.cod_cuenta=a.cod_cuenta and b.cod_subcuenta=a.cod_subcuenta) as deno_subcuenta,
			(select b.denominacion from ccfd01_division b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_cuenta=a.cod_tipo_cuenta and b.cod_cuenta=a.cod_cuenta and b.cod_subcuenta=a.cod_subcuenta and b.cod_division=a.cod_division) as deno_division,
			(select b.denominacion from ccfd01_subdivision b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_cuenta=a.cod_tipo_cuenta and b.cod_cuenta=a.cod_cuenta and b.cod_subcuenta=a.cod_subcuenta and b.cod_division=a.cod_division and b.cod_subdivision=a.cod_subdivision) as deno_subdivision
			  FROM ccfd10_detalles a where ".$filtro." order by a.ano_asiento,a.cod_tipo_cuenta,a.cod_cuenta,a.cod_subcuenta,a.cod_division,a.cod_subdivision,a.mes_asiento,a.dia_asiento,a.numero_asiento,a.numero_linea ASC ;";


			 $datos  = $this->ccfd10_descripcion->execute($sql);
			 if($datos!=null){
			 	$this->set('datos',$datos);

			 	if ($mes==1){
			 		$saldo_anterior_debe   = $this->ccfd10_descripcion->execute("SELECT debito_acumulado FROM ccfd02 where ".$filtro3);
			 		$saldo_anterior_haber  = $this->ccfd10_descripcion->execute("SELECT credito_acumulado FROM ccfd02 where ".$filtro3);
			 		$this->set('saldo_anterior_debe',$saldo_anterior_debe);
					$this->set('saldo_anterior_haber',$saldo_anterior_haber);
			 		}else{
					$saldo_anterior_debe   = $this->ccfd10_descripcion->execute("SELECT sum(monto) FROM ccfd10_detalles where ".$filtro2. " and debito_credito=1");
					$saldo_anterior_haber  = $this->ccfd10_descripcion->execute("SELECT sum(monto) FROM ccfd10_detalles where ".$filtro2. " and debito_credito=2");
					$this->set('saldo_anterior_debe',$saldo_anterior_debe);
					$this->set('saldo_anterior_haber',$saldo_anterior_haber);
			 			}
				 }else{
			 		$this->set('datos',null);
			 		}


		}else{
			$this->set('datos',null);
		}


	}//fin ir si


}//fin resumen_situacion_financiera



function select3($select=null,$var=null,$var2=null,$var3=null,$var4=null,$var5=null) {
 $this->layout = "ajax";

 	if($var!=null){
 	$cond2 = $this->SQLCA();
 	switch($select){
		case 'tipo':
			          $this->set('SELECT','coordinacion');
			          $this->set('codigo','secretaria');
			          $this->set('seleccion','');
			          $this->set('n',1);
			          break;

		case 'contable':
			  		 if($var==""){
			         	$this->set('vector','');
			  		 }else{
					 $this->set('SELECT','subcuenta_contable');//El parametro que se le pasa para que busque el proximo select (cuando entre en select3)
					 $this->set('codigo','contable');//El nombre que se le asigna al select actual cuando se crea
					 $this->set('cod_tipocuenta',$var);//cod_tipocuenta es para mantener el valor de la variable que llega y pasarselo al paso que viene en select3
					 $this->set('seleccion','');
					 $this->set('n',2);
					 $this->set('var',$var);
					 $this->set('var2',$var2);
					 $this->set('var3',$var3);
					 $this->set('var4',$var4);
					 $this->set('var5',$var5);
					 $cond = $cond2." and cod_tipo_cuenta=".$var;
					 $lista = $this->ccfd01_cuenta->generateList($cond, 'cod_cuenta ASC', null, '{n}.ccfd01_cuenta.cod_cuenta', '{n}.ccfd01_cuenta.denominacion');
			   		 $this->concatena($lista, 'vector');
			 		 }//fin vacio
			  break;

		case 'subcuenta_contable':
			  		 if($var2==""){
						$this->set('vector','');
			  		 }else{
					 $this->set('SELECT','div_estadistica_contable');
					 $this->set('codigo','subcuenta_contable');
					 $this->set('seleccion','');
					 $this->set('n',3);
					 $this->set('var',$var);
					 $this->set('var2',$var2);
					 $this->set('var3',$var3);
					 $this->set('var4',$var4);
					 $this->set('var5',$var5);
					 $cond = $cond2." and cod_tipo_cuenta=".$var." and cod_cuenta=".$var2;
					 $lista=  $this->ccfd01_subcuenta->generateList($cond, 'cod_subcuenta ASC', null, '{n}.ccfd01_subcuenta.cod_subcuenta', '{n}.ccfd01_subcuenta.denominacion');
			         $this->concatena($lista, 'vector');
				}// fin vacio
			    break;

	     case 'div_estadistica_contable':
					if($var3==""){
					    $this->set('vector','');
					}else{
				  		$this->set('SELECT','subdiv_estadistica_contable');
				  		$this->set('codigo','div_estadistica_contable');
				  		$this->set('seleccion','');
				  		//$this->set('no','no');
				  		$this->set('n',4);
				   		$this->set('var',$var);
				   		$this->set('var2',$var2);
				   		$this->set('var3',$var3);
				   		$this->set('var4',$var4);
				   		$this->set('var5',$var5);
				  		$cond = $cond2." and cod_tipo_cuenta=".$var." and cod_cuenta=".$var2." and cod_subcuenta=".$var3;
				  		$lista = $this->ccfd01_division->generateList($cond, 'cod_division ASC', null, '{n}.ccfd01_division.cod_division', '{n}.ccfd01_division.denominacion');
		   		  		$this->concatena($lista, 'vector');
						}
			  		break;

		 case 'subdiv_estadistica_contable':
					if($var4==""){
					    $this->set('vector','');
					}else{
				  		$this->set('SELECT','direccion');
				  		$this->set('codigo','subdiv_estadistica_contable');
				  		$this->set('seleccion','');
				  		$this->set('no','no');
				  		$this->set('n',5);
				   		$this->set('var',$var);
				   		$this->set('var2',$var2);
				   		$this->set('var3',$var3);
				   		$this->set('var4',$var4);
				   		$this->set('var5',$var5);
				  		$cond = $cond2." and cod_tipo_cuenta=".$var." and cod_cuenta=".$var2." and cod_subcuenta=".$var3." and cod_division=".$var4;
				  		$lista = $this->ccfd01_subdivision->generateList($cond, 'cod_division ASC', null, '{n}.ccfd01_subdivision.cod_subdivision', '{n}.ccfd01_subdivision.denominacion');
		   		  		$this->concatena($lista, 'vector');
						}
			  		break;
	}//fin switch
	}else{
		  $this->set('SELECT','');
		  $this->set('codigo','');
		  $this->set('seleccion','');
		  $this->set('n',10);
		  $this->set('no','no');
		  $this->set('vector','');
	}
 }//fin function select3




 function resumen_mensual_situacion_financiera($ir=null){
	$meses= array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
	if($ir=='no'){
		$this->layout="ajax";

		$this->set('year',$this->ano_ejecucion());

		$datos  = $this->ccfd10_descripcion->execute(" SELECT DISTINCT ano_asiento FROM ccfd10_descripcion WHERE ".$this->condicion()." ORDER BY ano_asiento ASC");
		if(count($datos)!=0){
			foreach($datos as $n){
				$lista[$n[0]['ano_asiento']]=$n[0]['ano_asiento'];
		    }
		}else{
			$lista=array('0'=>'No existen datos');
		}
		$this->set("lista_ano", $lista);

		$this->set('m',date('m'));

//		$this->concatena($meses, 'mes');
		$this->set('mes',$meses);
		$this->set('ir','no');


	}else if($ir=='si'){
		$this->layout="pdf";
		$this->set('ir','si');
		$filtro='';
		$_SESSION['ano_diario_c']='';
		$_SESSION['mes_diario_c']='';

		if(!empty($this->data['cfpp00']['ano']) && !empty($this->data['cfpp00']['mes'])){
			$ano=$this->data['cfpp00']['ano'];
			$mes=$this->data['cfpp00']['mes'];
			$_SESSION['ano_diario_c']=$ano;
			$_SESSION['mes_diario_c']=$meses[$mes];
			$filtro=$this->SQLCA()." and ano_fiscal=".$ano." and a.cod_cuenta >= 100 and a.cod_cuenta < 199";

			switch($mes){
				case 1:
					$consul="sum(a.debito_ene) as debito,
							 sum(a.credito_ene) as credito,";
					$debito_total='total_debito_acumulado_ene';
					$credito_total='total_credito_acumulado_ene';
					$name_mes='ENERO';
				break;
				case 2:
					$consul="sum(a.debito_feb) as debito,
							 sum(a.credito_feb) as credito,";
					$debito_total='total_debito_acumulado_feb';
					$credito_total='total_credito_acumulado_feb';
					$name_mes='FEBRERO';
				break;
				case 3:
					$consul="sum(a.debito_mar) as debito,
							 sum(a.credito_mar) as credito,";
					$debito_total='total_debito_acumulado_mar';
					$credito_total='total_credito_acumulado_mar';
					$name_mes='MARZO';
				break;
				case 4:
					$consul="sum(a.debito_abr) as debito,
							 sum(a.credito_abr) as credito,";
					$debito_total='total_debito_acumulado_abr';
					$credito_total='total_credito_acumulado_abr';
					$name_mes='ABRIL';
				break;
				case 5:
					$consul="sum(a.debito_may) as debito,
							 sum(a.credito_may) as credito,";
					$debito_total='total_debito_acumulado_may';
					$credito_total='total_credito_acumulado_may';
					$name_mes='MAYO';
				break;
				case 6:
					$consul="sum(a.debito_jun) as debito,
							 sum(a.credito_jun) as credito,";
					$debito_total='total_debito_acumulado_jun';
					$credito_total='total_credito_acumulado_jun';
					$name_mes='JUNIO';
				break;
				case 7:
					$consul="sum(a.debito_jul) as debito,
							 sum(a.credito_jul) as credito,";
					$debito_total='total_debito_acumulado_jul';
					$credito_total='total_credito_acumulado_jul';
					$name_mes='JULIO';
				break;
				case 8:
					$consul="sum(a.debito_ago) as debito,
						     sum(a.credito_ago) as credito,";
					$debito_total='total_debito_acumulado_ago';
					$credito_total='total_credito_acumulado_ago';
					$name_mes='AGOSTO';
				break;
				case 9:
					$consul="sum(a.debito_sep) as debito,
							 sum(a.credito_sep) as credito,";
					$debito_total='total_debito_acumulado_sep';
					$credito_total='total_credito_acumulado_sep';
					$name_mes='SEPTIEMBRE';
				break;
				case 10:
					$consul="sum(a.debito_oct) as debito,
							 sum(a.credito_oct) as credito,";
					$debito_total='total_debito_acumulado_oct';
					$credito_total='total_credito_acumulado_oct';
					$name_mes='OCTUBRE';
				break;
				case 11:
					$consul="sum(a.debito_nov) as debito,
							 sum(a.credito_nov) as credito,";
					$debito_total='total_debito_acumulado_nov';
					$credito_total='total_credito_acumulado_nov';
					$name_mes='NOVIEMBRE';
				break;
				case 12:
					$consul="sum(a.debito_dic) as debito,
							 sum(a.credito_dic) as credito,";
					$debito_total='total_debito_acumulado_dic';
					$credito_total='total_credito_acumulado_dic';
					$name_mes='DICIEMBRE';
				break;
			}//fin switch
			$this->set('debito_total',$debito_total);
			$this->set('credito_total',$credito_total);


			$sql1="SELECT
					a.cod_presi,
					a.cod_entidad,
					a.cod_tipo_inst,
					a.cod_inst,
					a.cod_dep,
					a.ano_fiscal,
					a.cod_tipo_cuenta,
					a.cod_cuenta,".$consul."
					sum(a.debito_acumulado) as debito_acumulado,
					sum(a.credito_acumulado) as credito_acumulado,
					sum(a.debito_acumulado + a.debito_ene)::numeric(22,2) AS total_debito_acumulado_ene,
					sum(a.credito_acumulado + a.credito_ene)::numeric(22,2) AS total_credito_acumulado_ene,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb)::numeric(22,2) AS total_debito_acumulado_feb,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb)::numeric(22,2) AS total_credito_acumulado_feb,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar)::numeric(22,2) AS total_debito_acumulado_mar,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar)::numeric(22,2) AS total_credito_acumulado_mar,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr)::numeric(22,2) AS total_debito_acumulado_abr,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr)::numeric(22,2) AS total_credito_acumulado_abr,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may)::numeric(22,2) AS total_debito_acumulado_may,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may)::numeric(22,2) AS total_credito_acumulado_may,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun)::numeric(22,2) AS total_debito_acumulado_jun,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun)::numeric(22,2) AS total_credito_acumulado_jun,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul)::numeric(22,2) AS total_debito_acumulado_jul,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul)::numeric(22,2) AS total_credito_acumulado_jul,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago)::numeric(22,2) AS total_debito_acumulado_ago,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago)::numeric(22,2) AS total_credito_acumulado_ago,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago + a.debito_sep)::numeric(22,2) AS total_debito_acumulado_sep,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago + a.credito_sep)::numeric(22,2) AS total_credito_acumulado_sep,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago + a.debito_sep + a.debito_oct)::numeric(22,2) AS total_debito_acumulado_oct,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago + a.credito_sep + a.credito_oct)::numeric(22,2) AS total_credito_acumulado_oct,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago + a.debito_sep + a.debito_oct + a.debito_nov)::numeric(22,2) AS total_debito_acumulado_nov,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago + a.credito_sep + a.credito_oct + a.credito_nov)::numeric(22,2) AS total_credito_acumulado_nov,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago + a.debito_sep + a.debito_oct + a.debito_nov + a.debito_dic)::numeric(22,2) AS total_debito_acumulado_dic,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago + a.credito_sep + a.credito_oct + a.credito_nov + a.credito_dic)::numeric(22,2) AS total_credito_acumulado_dic,
					(select b.denominacion from ccfd01_cuenta b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_cuenta=a.cod_tipo_cuenta and b.cod_cuenta=a.cod_cuenta) as deno_cuenta
					  FROM ccfd02 a where ".$filtro."
					group by
						a.cod_presi,
						a.cod_entidad,
						a.cod_tipo_inst,
						a.cod_inst,
						a.cod_dep,
						a.ano_fiscal,
						a.cod_tipo_cuenta,
						a.cod_cuenta
					order by
						a.cod_presi,
						a.cod_entidad,
						a.cod_tipo_inst,
						a.cod_inst,
						a.cod_dep,
						a.ano_fiscal,
						a.cod_tipo_cuenta,
						a.cod_cuenta";


			$sql2="SELECT
					a.cod_presi,
					a.cod_entidad,
					a.cod_tipo_inst,
					a.cod_inst,
					a.cod_dep,
					a.ano_fiscal,
					a.cod_cuenta,a.cod_subcuenta,".$consul."
					sum(a.debito_acumulado) as debito_acumulado,
					sum(a.credito_acumulado) as credito_acumulado,
					sum(a.debito_acumulado + a.debito_ene)::numeric(22,2) AS total_debito_acumulado_ene,
					sum(a.credito_acumulado + a.credito_ene)::numeric(22,2) AS total_credito_acumulado_ene,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb)::numeric(22,2) AS total_debito_acumulado_feb,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb)::numeric(22,2) AS total_credito_acumulado_feb,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar)::numeric(22,2) AS total_debito_acumulado_mar,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar)::numeric(22,2) AS total_credito_acumulado_mar,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr)::numeric(22,2) AS total_debito_acumulado_abr,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr)::numeric(22,2) AS total_credito_acumulado_abr,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may)::numeric(22,2) AS total_debito_acumulado_may,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may)::numeric(22,2) AS total_credito_acumulado_may,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun)::numeric(22,2) AS total_debito_acumulado_jun,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun)::numeric(22,2) AS total_credito_acumulado_jun,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul)::numeric(22,2) AS total_debito_acumulado_jul,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul)::numeric(22,2) AS total_credito_acumulado_jul,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago)::numeric(22,2) AS total_debito_acumulado_ago,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago)::numeric(22,2) AS total_credito_acumulado_ago,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago + a.debito_sep)::numeric(22,2) AS total_debito_acumulado_sep,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago + a.credito_sep)::numeric(22,2) AS total_credito_acumulado_sep,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago + a.debito_sep + a.debito_oct)::numeric(22,2) AS total_debito_acumulado_oct,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago + a.credito_sep + a.credito_oct)::numeric(22,2) AS total_credito_acumulado_oct,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago + a.debito_sep + a.debito_oct + a.debito_nov)::numeric(22,2) AS total_debito_acumulado_nov,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago + a.credito_sep + a.credito_oct + a.credito_nov)::numeric(22,2) AS total_credito_acumulado_nov,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago + a.debito_sep + a.debito_oct + a.debito_nov + a.debito_dic)::numeric(22,2) AS total_debito_acumulado_dic,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago + a.credito_sep + a.credito_oct + a.credito_nov + a.credito_dic)::numeric(22,2) AS total_credito_acumulado_dic,
					(select b.denominacion from ccfd01_subcuenta b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_cuenta=a.cod_cuenta and b.cod_subcuenta=a.cod_subcuenta) as deno_subcuenta
					  FROM ccfd02 a where ".$filtro."
					group by
						a.cod_presi,
						a.cod_entidad,
						a.cod_tipo_inst,
						a.cod_inst,
						a.cod_dep,
						a.ano_fiscal,
						a.cod_cuenta,a.cod_subcuenta
					order by
						a.cod_presi,
						a.cod_entidad,
						a.cod_tipo_inst,
						a.cod_inst,
						a.cod_dep,
						a.ano_fiscal,
						a.cod_cuenta,a.cod_subcuenta";


			$sql3="SELECT
					a.cod_presi,
					a.cod_entidad,
					a.cod_tipo_inst,
					a.cod_inst,
					a.cod_dep,
					a.ano_fiscal,
					a.cod_cuenta,a.cod_subcuenta,a.cod_division,".$consul."
					sum(a.debito_acumulado) as debito_acumulado,
					sum(a.credito_acumulado) as credito_acumulado,
					sum(a.debito_acumulado + a.debito_ene)::numeric(22,2) AS total_debito_acumulado_ene,
					sum(a.credito_acumulado + a.credito_ene)::numeric(22,2) AS total_credito_acumulado_ene,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb)::numeric(22,2) AS total_debito_acumulado_feb,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb)::numeric(22,2) AS total_credito_acumulado_feb,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar)::numeric(22,2) AS total_debito_acumulado_mar,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar)::numeric(22,2) AS total_credito_acumulado_mar,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr)::numeric(22,2) AS total_debito_acumulado_abr,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr)::numeric(22,2) AS total_credito_acumulado_abr,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may)::numeric(22,2) AS total_debito_acumulado_may,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may)::numeric(22,2) AS total_credito_acumulado_may,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun)::numeric(22,2) AS total_debito_acumulado_jun,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun)::numeric(22,2) AS total_credito_acumulado_jun,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul)::numeric(22,2) AS total_debito_acumulado_jul,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul)::numeric(22,2) AS total_credito_acumulado_jul,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago)::numeric(22,2) AS total_debito_acumulado_ago,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago)::numeric(22,2) AS total_credito_acumulado_ago,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago + a.debito_sep)::numeric(22,2) AS total_debito_acumulado_sep,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago + a.credito_sep)::numeric(22,2) AS total_credito_acumulado_sep,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago + a.debito_sep + a.debito_oct)::numeric(22,2) AS total_debito_acumulado_oct,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago + a.credito_sep + a.credito_oct)::numeric(22,2) AS total_credito_acumulado_oct,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago + a.debito_sep + a.debito_oct + a.debito_nov)::numeric(22,2) AS total_debito_acumulado_nov,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago + a.credito_sep + a.credito_oct + a.credito_nov)::numeric(22,2) AS total_credito_acumulado_nov,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago + a.debito_sep + a.debito_oct + a.debito_nov + a.debito_dic)::numeric(22,2) AS total_debito_acumulado_dic,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago + a.credito_sep + a.credito_oct + a.credito_nov + a.credito_dic)::numeric(22,2) AS total_credito_acumulado_dic,
					(select b.denominacion from ccfd01_division b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_cuenta=a.cod_cuenta and b.cod_subcuenta=a.cod_subcuenta and b.cod_division=a.cod_division) as deno_division
					  FROM ccfd02 a where ".$filtro."
					group by
						a.cod_presi,
						a.cod_entidad,
						a.cod_tipo_inst,
						a.cod_inst,
						a.cod_dep,
						a.ano_fiscal,
						a.cod_cuenta,a.cod_subcuenta,a.cod_division
					order by
						a.cod_presi,
						a.cod_entidad,
						a.cod_tipo_inst,
						a.cod_inst,
						a.cod_dep,
						a.ano_fiscal,
						a.cod_cuenta,a.cod_subcuenta,a.cod_division";


			$sql4="SELECT
					a.cod_presi,
					a.cod_entidad,
					a.cod_tipo_inst,
					a.cod_inst,
					a.cod_dep,
					a.ano_fiscal,
					a.cod_cuenta,a.cod_subcuenta,a.cod_division,a.cod_subdivision,".$consul."
					sum(a.debito_acumulado) as debito_acumulado,
					sum(a.credito_acumulado) as credito_acumulado,
					sum(a.debito_acumulado + a.debito_ene)::numeric(22,2) AS total_debito_acumulado_ene,
					sum(a.credito_acumulado + a.credito_ene)::numeric(22,2) AS total_credito_acumulado_ene,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb)::numeric(22,2) AS total_debito_acumulado_feb,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb)::numeric(22,2) AS total_credito_acumulado_feb,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar)::numeric(22,2) AS total_debito_acumulado_mar,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar)::numeric(22,2) AS total_credito_acumulado_mar,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr)::numeric(22,2) AS total_debito_acumulado_abr,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr)::numeric(22,2) AS total_credito_acumulado_abr,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may)::numeric(22,2) AS total_debito_acumulado_may,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may)::numeric(22,2) AS total_credito_acumulado_may,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun)::numeric(22,2) AS total_debito_acumulado_jun,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun)::numeric(22,2) AS total_credito_acumulado_jun,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul)::numeric(22,2) AS total_debito_acumulado_jul,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul)::numeric(22,2) AS total_credito_acumulado_jul,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago)::numeric(22,2) AS total_debito_acumulado_ago,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago)::numeric(22,2) AS total_credito_acumulado_ago,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago + a.debito_sep)::numeric(22,2) AS total_debito_acumulado_sep,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago + a.credito_sep)::numeric(22,2) AS total_credito_acumulado_sep,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago + a.debito_sep + a.debito_oct)::numeric(22,2) AS total_debito_acumulado_oct,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago + a.credito_sep + a.credito_oct)::numeric(22,2) AS total_credito_acumulado_oct,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago + a.debito_sep + a.debito_oct + a.debito_nov)::numeric(22,2) AS total_debito_acumulado_nov,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago + a.credito_sep + a.credito_oct + a.credito_nov)::numeric(22,2) AS total_credito_acumulado_nov,
					sum(a.debito_acumulado + a.debito_ene + a.debito_feb + a.debito_mar + a.debito_abr + a.debito_may + a.debito_jun + a.debito_jul + a.debito_ago + a.debito_sep + a.debito_oct + a.debito_nov + a.debito_dic)::numeric(22,2) AS total_debito_acumulado_dic,
					sum(a.credito_acumulado + a.credito_ene + a.credito_feb + a.credito_mar + a.credito_abr + a.credito_may + a.credito_jun + a.credito_jul + a.credito_ago + a.credito_sep + a.credito_oct + a.credito_nov + a.credito_dic)::numeric(22,2) AS total_credito_acumulado_dic,
					(select b.denominacion from ccfd01_subdivision b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_cuenta=a.cod_cuenta and b.cod_subcuenta=a.cod_subcuenta and b.cod_division=a.cod_division and b.cod_subdivision=a.cod_subdivision) as deno_subdivision
					  FROM ccfd02 a where ".$filtro."
					group by
						a.cod_presi,
						a.cod_entidad,
						a.cod_tipo_inst,
						a.cod_inst,
						a.cod_dep,
						a.ano_fiscal,
						a.cod_cuenta,a.cod_subcuenta,a.cod_division,a.cod_subdivision
					order by
						a.cod_presi,
						a.cod_entidad,
						a.cod_tipo_inst,
						a.cod_inst,
						a.cod_dep,
						a.ano_fiscal,
						a.cod_cuenta,a.cod_subcuenta,a.cod_division,a.cod_subdivision";


/*
			$sql="SELECT
			a.cod_presi,
			a.cod_entidad,
			a.cod_tipo_inst,
			a.cod_inst,
			a.cod_dep,
			a.ano_asiento,
			a.mes_asiento,
			a.dia_asiento,
			a.numero_asiento,
			a.numero_linea,
			a.debito_credito,
			a.cod_tipo_cuenta,
			a.cod_cuenta,
			a.cod_subcuenta,
			a.cod_division,
			a.cod_subdivision,
			a.monto,
			sum(a.debito_acumulado) as debito_acumulado,
			sum(a.credito_acumulado) as credito_acumulado,
			(select b.denominacion from ccfd01_tipo b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_cuenta=a.cod_tipo_cuenta) as deno_tipo_cuenta,
			(select b.denominacion from ccfd01_cuenta b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_cuenta=a.cod_tipo_cuenta and b.cod_cuenta=a.cod_cuenta) as deno_cuenta,
			(select b.denominacion from ccfd01_subcuenta b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_cuenta=a.cod_tipo_cuenta and b.cod_cuenta=a.cod_cuenta and b.cod_subcuenta=a.cod_subcuenta) as deno_subcuenta,
			(select b.denominacion from ccfd01_division b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_cuenta=a.cod_tipo_cuenta and b.cod_cuenta=a.cod_cuenta and b.cod_subcuenta=a.cod_subcuenta and b.cod_division=a.cod_division) as deno_division,
			(select b.denominacion from ccfd01_subdivision b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_tipo_cuenta=a.cod_tipo_cuenta and b.cod_cuenta=a.cod_cuenta and b.cod_subcuenta=a.cod_subcuenta and b.cod_division=a.cod_division and b.cod_subdivision=a.cod_subdivision) as deno_subdivision
			  FROM ccfd10_detalles a where ".$filtro." order by a.ano_asiento,a.mes_asiento,a.dia_asiento,a.numero_asiento,a.numero_linea ASC ;";

*/
			 $datos1  = $this->ccfd02->execute($sql1);
			 $datos2  = $this->ccfd02->execute($sql2);
			 $datos3  = $this->ccfd02->execute($sql3);
			 $datos4  = $this->ccfd02->execute($sql4);
			 if($datos1!=null){
			 	$this->set('datos1',$datos1);
			 	$this->set('datos2',$datos2);
			 	$this->set('datos3',$datos3);
			 	$this->set('datos4',$datos4);
			 }else{
			 	$this->set('datos1',null);
			 }


		}else{
			$this->set('datos1',null);
		}


	}//fin ir si


}//fin funcion resumen_mensual_situacion_financiera


}//fin class

?>