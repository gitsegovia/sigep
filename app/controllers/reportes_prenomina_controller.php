<?php

 class ReportesPrenominaController extends AppController{


 	var $uses = array('ccfd04_cierre_mes','Cnmd01',"cnmd03_conexion_transacciones",'cnmd09_numero_nominas_canceladas','cnmd07_calculo_aguinaldos','cnmd07_calculo_bonovaca','cnmd09_lunes_ejercicio','cnmd09_incidencia_sueldo_sugerido','v_cnmd07_transacciones_actuales_frecuencias2','v_distribucion_asignacion_deduccion','costo_presupuestario_p2','trasacciones_no_conectadas','cargos_anos_diferentes','v_distribucion_asignacion_deduccion_historico','v_cnmd08_historia_trans_con','costo_presupuestario_p2_historico','cfpd01_formulacion','arrd05');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf','Form');


function checkSession(){
	if (!$this->Session->check('Usuario')){
			$this->redirect('/salir/');
			exit();
	}else{
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

function AddCero2($numero=null,$extra=null){

      if($extra==null){
          if($numero<10){
             $numero="0".$numero;
          }else{
             $numero;
          }
      }else{
          if($numero<10){
             $numero=$extra.".".$numero;
          }else{
             $numero=$extra.".".$numero;
          }

      }
      return $numero;
   }//fin AddCero2

function distribucion_asignacion_deduccion ($generar=null) {
    $this->layout="ajax";
    if($generar==''){
        $lista = $this->Cnmd01->generateList($this->SQLCA()." and status_nomina!=0", 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		if($this->Cnmd01->findCount($this->SQLCA()." and status_nomina!=0")!=0){
			$this->concatena($lista, 'tipo_nomina');
		}else{
			$this->set('tipo_nomina', array());
		}

    }else if($generar=='pdf') {

    $ano_ejecucion = $this->ano_ejecucion();

		 $datos=$this->data["cnmp99_prenomina"];
         $cod_tipo_nomina      = $datos["cod_tipo_nomina"];

         $data_trans = $this->cnmd03_conexion_transacciones->findAll($this->SQLCA($ano_ejecucion)." and cod_tipo_nomina=".$cod_tipo_nomina);

         $data = $this->v_distribucion_asignacion_deduccion->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
    foreach($data as $key => $r){
            $data[$key]["v_distribucion_asignacion_deduccion"]["partida"]="";
      foreach ($data_trans as $t) {
         if($r["v_distribucion_asignacion_deduccion"]["cod_tipo_transaccion"]==$t["cnmd03_conexion_transacciones"]["cod_tipo_transaccion"]  && $r["v_distribucion_asignacion_deduccion"]["cod_transaccion"]==$t["cnmd03_conexion_transacciones"]["cod_transaccion"] ){
          
            $data[$key]["v_distribucion_asignacion_deduccion"]["partida"]=
            $this->AddCero2(substr($t["cnmd03_conexion_transacciones"]["cod_partida"], -2), substr($t["cnmd03_conexion_transacciones"]["cod_partida"], 0, 1 ))."-".
              $this->AddCero2($t["cnmd03_conexion_transacciones"]["cod_generica"])."-".
              $this->AddCero2($t["cnmd03_conexion_transacciones"]["cod_especifica"])."-".
              $this->AddCero2($t["cnmd03_conexion_transacciones"]["cod_sub_espec"])."-".
              $this->AddCero2($t["cnmd03_conexion_transacciones"]["cod_auxiliar"]);
            break;
         }         
      }
    }
         $this->set('nomina',$this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina,'cod_tipo_nomina,status_nomina,denominacion,numero_nomina,periodo_desde,periodo_hasta,correspondiente,cantidad_pagos,frecuencia_pago'));
         $this->set('data',$data);
         $this->render('pdf_distribucion_asignacion_deduccion','pdf');
    }

}//fin distribucion_asignacion_deduccion

function deno_nomina ($cod_tipo_nomina=null) {
     $this->layout="ajax";
     if (isset($cod_tipo_nomina)) {
         $lista = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina,'cod_tipo_nomina, denominacion');
		if($this->Cnmd01->findCount($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina)!=0){
			$this->set( 'nomina',$lista);

		}else{
			$this->set('nomina', array());
		}
	}
	            $c=$this->trasacciones_no_conectadas->findCount($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina);
             	$car=$this->cargos_anos_diferentes->findCount($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and ano_transaccion=".date('Y'));

             	if($c==0 && $car==0){

             	}else{
             		if($car!=0){
                        $this->set('errorMessage','Hay cargos no conectados para el año actual');
             		}else{
             			$this->set('errorMessage','Hay transacciones sin conectar al presupuesto');
             			$this->set('data',$this->trasacciones_no_conectadas->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina));
             		}
             	}
}

function costo_presupuestario ($generar=null) {
    $this->layout="ajax";
    if($generar==''){
        $lista = $this->Cnmd01->generateList($this->SQLCA()." and (status_nomina=1 or status_nomina=2)", 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		if($this->Cnmd01->findCount($this->SQLCA()." and status_nomina!=0")!=0){
			$this->concatena($lista, 'tipo_nomina');
		}else{
			$this->set('tipo_nomina', array());
		}
		
    }else if($generar=='pdf') {
		 $datos=$this->data["cnmp99_prenomina"];
         $cod_tipo_nomina      = $datos["cod_tipo_nomina"];
         $cnmd01 = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina,'cod_tipo_nomina,status_nomina,denominacion,numero_nomina,periodo_desde,periodo_hasta,correspondiente,cantidad_pagos,frecuencia_pago');
         $this->set('nomina',$cnmd01);
         $this->set('data',$this->costo_presupuestario_p2->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina." and ano=".divide_fecha($cnmd01[0]['Cnmd01']['periodo_desde'],'ANO')));
         $this->set('no_conectadas',$this->trasacciones_no_conectadas->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina));
         $this->render('pdf_costo_presupuestario','pdf');
    }

}//fin distribucion_asignacion_deduccion

function SQLCA_Institucion($ano=null){//sql para busqueda de codigos de arranque con y sin año
  $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
  $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
  $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
  $sql_re .= "cod_inst=".$this->verifica_SS(4)." ";

  if($ano!=null){
    $sql_re .= " and ano=".$ano."  ";
  }
  return $sql_re;
}

function distribucion_asignacion_deduccion_resumen($pdf=null) {
  if(!$pdf){

    $this->layout = "ajax";

    $mes= array('1'=>'ENERO','2'=>'FEBRERO','3'=>'MARZO','4'=>'ABRIL','5'=>'MAYO','6'=>'JUNIO','7'=>'JULIO','8'=>'AGOSTO','9'=>'SEPTIEMBRE','10'=>'OCTUBRE','11'=>'NOVIEMBRE','12'=>'DICIEMBRE');
    $this->set('mes',$mes);

    $quincena= array('1'=>'1ERA QUINCENA','2'=>'2DA QUINCENA');
    $this->set('quincena',$quincena);
    
    $year = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
    $this->set('ano', $year[0]['cfpd01_formulacion']['ano_formular']);
    $this->set('pdf',false);
    
    $this->set('cod_dep',$this->Session->read('SScoddep'));
    $this->set('select_dependencia',$this->data['reporte_resumen']['select_dependencia']);
    $lista = $this->arrd05->generateList('where cod_presi=1  and cod_entidad=12 and cod_tipo_inst=30 and cod_inst=12', 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
    if($lista !=null){
        $this->concatena($lista, 'listadependencia');
    }else{
        $this->set('listadependencia','');
    }
       
  }else{

      $this->layout = "pdf";
      $year = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
      
      $cod_dep = $this->data["reporte_resumen"]["select_dependencia"];
      $ano_nomina = $this->data["reporte_resumen"]["ano"];
      $mes_nomina = $this->data["reporte_resumen"]["mes"];
      $quincena_nomina = $this->data["reporte_resumen"]["quincena"];
      if($cod_dep==''){$cod_dep=1;}
      if($ano_nomina==''){ $ano_nomina=$year[0]['cfpd01_formulacion']['ano_formular']; }
      if($mes_nomina==""){ $mes_nomina=1;}
      if($quincena_nomina==''){$quincena_nomina=1;}

      $name_dep=$this->cfpd01_formulacion->execute("select denominacion from arrd05 where cod_dep=".$cod_dep);
      $this->set('nombre_dependencia',$name_dep[0][0]['denominacion']);

      $periodo_desde=$ano_nomina."-".$mes_nomina."-01";
      $periodo_hasta=$ano_nomina."-".$mes_nomina."-15";

      if($quincena_nomina==2){
        $periodo_desde=$ano_nomina."-".$mes_nomina."-16";
        switch($mes_nomina){
          case '1':
          case '3':
          case '5':
          case '7':
          case '8':
          case '10':
          case '12':
            $periodo_hasta=$ano_nomina."-".$mes_nomina."-31";
          break;;
          case '2':
            $periodo_hasta=$ano_nomina."-".$mes_nomina."-28";
          break;;
          case '4':
          case '6':
          case '9':
          case '11':
            $periodo_hasta=$ano_nomina."-".$mes_nomina."-30";
          break;;
        }
      }

      /*
      $datos = $this->cfpd01_formulacion->execute("SELECT * FROM v_distribucion_asignacion_deduccion_historico WHERE cod_dep=".$cod_dep." and ano=".$ano_nomina." and numero_nomina in (SELECT DISTINCT numero_nomina FROM v_cnmd08_historia_transacciones_condicion WHERE cod_dep=".$cod_dep." and ano=".$ano_nomina." and periodo_desde>=".$periodo_desde." and periodo_hasta<=".$periodo_hasta.") ORDER BY cod_tipo_nomina, ano, numero_nomina, cod_tipo_transac, cod_transaccion;");
      */
      $datos = $this->cfpd01_formulacion->execute("SELECT *
  FROM v_distribucion_asignacion_deduccion_historico
  WHERE  cod_dep=".$cod_dep." and ano=".$ano_nomina." and numero_nomina in (SELECT DISTINCT numero_nomina
  FROM v_cnmd08_historia_transacciones_condicion
  WHERE  cod_dep=".$cod_dep." and ano=".$ano_nomina." and periodo_desde>='".$periodo_desde."' and periodo_hasta<='".$periodo_hasta."');");

  //$data_trans = $this->cnmd03_conexion_transacciones->findAll("cod_dep=".$cod_dep." and ano=".$ano_nomina." and cod_tipo_nomina in (1,2)");
  $data_trans = $this->cnmd03_conexion_transacciones->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_tipo_transaccion, cod_transaccion, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar
  FROM cnmd03_conexion_transacciones  where cod_dep=".$cod_dep." and ano=".$ano_nomina."
  GROUP BY cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_tipo_transaccion, cod_transaccion, ano, cod_sector, 
       cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, 
       cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar;");

      foreach($datos as $key => $r){
        $datos[$key][0]["partida"]="";
        $datosarray[$key]=$datos[$key][0];
      foreach ($data_trans as $t) {
        if($r[0]["cod_tipo_nomina"]==$t["cnmd03_conexion_transacciones"]["cod_tipo_nomina"] && $r[0]["cod_transaccion"]==$t["cnmd03_conexion_transacciones"]["cod_transaccion"] && $r[0]["cod_tipo_transac"]==$t["cnmd03_conexion_transacciones"]["cod_tipo_transaccion"]){
            $datosarray[$key]["partida"]=
            $this->AddCero2(substr($t["cnmd03_conexion_transacciones"]["cod_partida"], -2), substr($t["cnmd03_conexion_transacciones"]["cod_partida"], 0, 1 ))."-".
              $this->AddCero2($t["cnmd03_conexion_transacciones"]["cod_generica"])."-".
              $this->AddCero2($t["cnmd03_conexion_transacciones"]["cod_especifica"])."-".
              $this->AddCero2($t["cnmd03_conexion_transacciones"]["cod_sub_espec"])."-".
              $this->AddCero2($t["cnmd03_conexion_transacciones"]["cod_auxiliar"]);
            break;
        }
      }
    }

    $paso = '';
    foreach($datosarray as $key => $value){
      if(count($nDatos)==0 && $value['partida']!==''){
        $nDatos[]= $value;
      }else{
        $paso = '';
        foreach($nDatos as $k => $v){

          if($value['partida']==$v['partida']){
              $text1 = $v['deno_transaccion'];
              $find = $value['deno_transaccion'];
              if(strpos($text1, $find)){
                $nDatos[$k]['deno_transaccion']=$nDatos[$k]['deno_transaccion'].", ".$value['deno_transaccion']; 
              }
            
            $nDatos[$k]['monto_asignacion']=(float)$nDatos[$k]['monto_asignacion']+(float)$value['monto_asignacion'];
            $nDatos[$k]['monto_deduccion']=(float)$nDatos[$k]['monto_deduccion']+(float)$value['monto_deduccion'];
            $paso='1';            
          }
        }
        if($paso=='' && $value['partida']!==''){
          $nDatos[]= $value;
        }
      }
    }
    /*$partida  = array_column($nDatos, 'partida');
    array_multisort($partida, SORT_DESC, $nDatos);*/

    $this->set('data',$nDatos);
    $this->set('desde',$periodo_desde);
    $this->set('hasta',$periodo_hasta);
    $this->render('pdf_distribucion_asignacion_deduccion_resumen','pdf');

  }
}

function distribucion_asignacion_deduccion_historico ($generar=null) {
    $this->layout="ajax";
    if($generar==''){
		if($this->Cnmd01->findCount($this->SQLCA())!=0){
			$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
			$this->concatena($lista, 'lista_nomina');
			$this->Session->delete('tipo_nomina');
		}else{
			$this->set('lista_nomina', array());
		}

	}else if($generar=='pdf') {
		//$datos=$this->data["cnmp99_prenomina"];
		//$cod_tipo_nomina = $datos["cod_tipo_nomina"];
		$cod_tipo_nomina = $this->data["cnmp06_diskett_historico"]["cod_nomina"];
		$ano_nomina = $this->data["cnmp06_diskett_historico"]["ano_nomina"];
		$numero_nomina = $this->data["cnmp06_diskett_historico"]["numero_nomina"];
		$condicion = $this->SQLCA()." and cod_tipo_nomina='$cod_tipo_nomina' and ano='$ano_nomina' and numero_nomina='$numero_nomina'";
		$nomina = $this->v_cnmd08_historia_trans_con->findAll($condicion," DISTINCT numero_nomina, frecuencia_pago, periodo_desde, periodo_hasta, correspondiente", ' numero_nomina ASC');
		$this->set("nomina", $nomina);
		//$this->set('nomina',$this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina,'cod_tipo_nomina, denominacion,numero_nomina,periodo_desde,periodo_hasta,correspondiente,cantidad_pagos,frecuencia_pago'));
		$datos = $this->v_distribucion_asignacion_deduccion_historico->findAll($condicion, null, "cod_tipo_nomina, ano, numero_nomina, cod_tipo_transac, cod_transaccion ASC");

		$data_trans = $this->cnmd03_conexion_transacciones->findAll($this->SQLCA($ano_nomina)." and cod_tipo_nomina=".$cod_tipo_nomina);
    
    foreach($datos as $key => $r){
      $datos[$key]["v_distribucion_asignacion_deduccion_historico"]["partida"]="";
      foreach ($data_trans as $t) {
        if($r["v_distribucion_asignacion_deduccion_historico"]["cod_tipo_transac"]==$t["cnmd03_conexion_transacciones"]["cod_tipo_transaccion"]  && $r["v_distribucion_asignacion_deduccion_historico"]["cod_transaccion"]==$t["cnmd03_conexion_transacciones"]["cod_transaccion"]){
            $datos[$key]["v_distribucion_asignacion_deduccion_historico"]["partida"]=
            $this->AddCero2(substr($t["cnmd03_conexion_transacciones"]["cod_partida"], -2), substr($t["cnmd03_conexion_transacciones"]["cod_partida"], 0, 1 ))."-".
              $this->AddCero2($t["cnmd03_conexion_transacciones"]["cod_generica"])."-".
              $this->AddCero2($t["cnmd03_conexion_transacciones"]["cod_especifica"])."-".
              $this->AddCero2($t["cnmd03_conexion_transacciones"]["cod_sub_espec"])."-".
              $this->AddCero2($t["cnmd03_conexion_transacciones"]["cod_auxiliar"]);
            break;
        }
			}
		}
		$denominacion_nomina = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina='$cod_tipo_nomina'", 'cod_tipo_nomina, denominacion', ' cod_tipo_nomina ASC');
		$this->set("cod_tipo_nomina_reporte", $denominacion_nomina[0]['Cnmd01']['cod_tipo_nomina']);
		$this->set("denominacion_nomina_reporte", $denominacion_nomina[0]['Cnmd01']['denominacion']);
		$this->set('data',$datos);
		$this->render('pdf_distribucion_asignacion_deduccion_historico','pdf');
    }
}//fin distribucion_asignacion_deduccion_historico


function costo_presupuestario_historico ($generar=null) {
    $this->layout="ajax";
    if($generar==''){
		if($this->Cnmd01->findCount($this->SQLCA())!=0){
			$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
			$this->concatena($lista, 'lista_nomina');
			$this->Session->delete('tipo_nomina');
		}else{
			$this->set('lista_nomina', array());
		}
    }else if($generar=='pdf') {
		 //$datos=$this->data["cnmp99_prenomina"];
         //$cod_tipo_nomina      = $datos["cod_tipo_nomina"];
         $cod_tipo_nomina = $this->data["cnmp06_diskett_historico"]["cod_nomina"];
		 $ano_nomina = $this->data["cnmp06_diskett_historico"]["ano_nomina"];
		 $numero_nomina = $this->data["cnmp06_diskett_historico"]["numero_nomina"];
		 $condicion = $this->SQLCA()." and cod_tipo_nomina='$cod_tipo_nomina' and ano='$ano_nomina' and numero_nomina='$numero_nomina'";
		 $nomina = $this->v_cnmd08_historia_trans_con->findAll($condicion," DISTINCT numero_nomina, frecuencia_pago, periodo_desde, periodo_hasta, correspondiente", ' numero_nomina ASC');
		 $this->set("nomina", $nomina);
         //$cnmd01 = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina,'cod_tipo_nomina, denominacion,numero_nomina,periodo_desde,periodo_hasta,correspondiente,cantidad_pagos,frecuencia_pago');
         //$this->set('nomina',$cnmd01);
         $data = $this->costo_presupuestario_p2_historico->findAll($this->SQLCA()." and cod_tipo_nomina='$cod_tipo_nomina' and ano='$ano_nomina' and numero_nomina='$numero_nomina'");
         //pr($data);
         $denominacion_nomina = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina='$cod_tipo_nomina'", 'cod_tipo_nomina, denominacion', ' cod_tipo_nomina ASC');
		 $this->set("cod_tipo_nomina_reporte", $denominacion_nomina[0]['Cnmd01']['cod_tipo_nomina']);
		 $this->set("denominacion_nomina_reporte", $denominacion_nomina[0]['Cnmd01']['denominacion']);
         $this->set('data',$data);
         $this->set('no_conectadas',$this->trasacciones_no_conectadas->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina));
         $this->render('pdf_costo_presupuestario_historico','pdf');
    }

}//fin distribucion_asignacion_deduccion



}//fin class
?>
