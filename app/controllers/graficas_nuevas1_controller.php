<?php


class graficasNuevas1Controller extends AppController{
    var $name = "graficas_nuevas1";
    var $uses = array('cfpd07_clasificacion_recurso', 'cfpd07_plan_inversion', 'cfpd07_obras_cuerpo', 'ccfd04_cierre_mes', 'cfpd01_formulacion',
                       'v_cfpd05_asignacion_corriente_capital', 'cfpd05', 'v_balance_ejecucion', 'v_cfpd05_tipo_gasto2','v_casp01_relacion_solicitudes',
                        'arrd01', 'arrd02', 'arrd03', 'arrd04', 'arrd05','v_shd900_cobranza_acumulada_deno_part', 'shd900_planillas_deuda_cobro_detalles',
                        'shd000_arranque', 'v_shd900_planillas_deuda_cobro_detalles_cobradores_2');
	var $helpers = array('Html','Ajax','Javascript', 'Sisap', 'Fpdf');


function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
}//fin checksession
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
function SQLCA_report($pre=null){
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         if($pre!=null && $pre==1){
         $sql_re .= "cod_inst=".$this->verifica_SS(4)." ";
         //$sql_re .= "cod_dep=0";
         }else{
         	$sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
            $sql_re .= "cod_dep=".$this->verifica_SS(5)." ";
         }

         return $sql_re;
}//fin funcion SQLCA
function SQLCA_report_a($pre=null){
         $sql_re = "a.cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "a.cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "a.cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         if($pre!=null && $pre==1){
         $sql_re .= "a.cod_inst=".$this->verifica_SS(4)." ";
         //$sql_re .= "cod_dep=0";
         }else{
         	$sql_re .= "a.cod_inst=".$this->verifica_SS(4)."  and  ";
            $sql_re .= "a.cod_dep=".$this->verifica_SS(5)." ";
         }

         return $sql_re;
}//fin funcion SQLCA

function SQLCA_report_in($pre=null){
         $sql_re = $this->verifica_SS(1).",";
         $sql_re .= $this->verifica_SS(2).",";
         $sql_re .= $this->verifica_SS(3).",";
         if($pre!=null && $pre==1){
         $sql_re .= $this->verifica_SS(4).",";
         $sql_re .= 0;
         }else{
         	$sql_re .= $this->verifica_SS(4).",";
            $sql_re .= $this->verifica_SS(5)." ";
         }

         return $sql_re;
}//fin funcion SQLCA


	function mes_letra($mes){
		switch ($mes) {
            case "01": {
                    $var = "Enero";
                }break;
            case "02": {
                    $var = "Febrero";
                }break;
            case "03": {
                    $var = "Marzo";
                }break;
            case "04": {
                    $var = "Abril";
                }break;
            case "05": {
                    $var = "Mayo";
                }break;
            case "06": {
                    $var = "Junio";
                }break;
            case "07": {
                    $var = "Julio";
                }break;
            case "08": {
                    $var = "Agosto";
                }break;
            case "09": {
                    $var = "Septiembre";
                }break;
            case "10": {
                    $var = "Octubre";
                }break;
            case "11": {
                    $var = "Noviembre";
                }break;
            case "12": {
                    $var = "Diciembre";
                }break;
            default: $var = "";
            		 break;
        }//fin

        return $var;
	}


 function genera_grafica_1($opcion=null, $parametros=array(), $variables_aux=array(), $parametros2=array(), $variables_aux2=array()){

  $this->layout="ajax";

  $rdm = mt_rand();

  $this->set('username', $this->Session->read('nom_usuario'));
  $this->set('rdm', $rdm);

  echo "<script> if(document.getElementById('continuar')){document.getElementById('continuar').disabled=false;}</script>";
  $this->set('opcion', $opcion);

  $this->Session->write('parametros_graficas',    $parametros);
  $this->Session->write('variables_aux_graficas', $variables_aux);
  $this->Session->write('rdm_graficas',           $rdm);

  $this->Session->delete('parametros2_graficas');
  $this->Session->delete('variables_aux2_graficas');

  if(isset($parametros["torta"])){$this->set('torta', $parametros["torta"]);}
  if(isset($parametros["tipo_cantidad"])){
  	$this->set('tipo_cantidad',      $parametros["tipo_cantidad"]);
  	$this->set('value_cantidad_aux', $variables_aux["cantidad"]);
  	$this->set('cantidad_total',     $variables_aux["cantidad_total"]);
  	$value_monto = implode(',', $variables_aux["cantidad"]);
  }else{
  	$value_monto = implode(',', $variables_aux["monto"]);
  }
			  $value_porce = implode(',', $variables_aux["porcentaje"]);
			  $value_titul = implode(',', $variables_aux["nombre"]);

			  $value_monto_aux = $variables_aux["monto"];
			  $value_porce_aux = $variables_aux["porcentaje"];
			  $value_titul_aux = $variables_aux["nombre"];

			  $nombre_total     = $variables_aux["nombre_total"];
			  $porcentaje_total = $variables_aux["porcentaje_total"];
			  $monto_total      = $variables_aux["monto_total"];


			  $this->set('value_monto', $value_monto);
			  $this->set('value_porce', $value_porce);
			  $this->set('value_titul', $value_titul);

			  $this->set('titulo_grafica', $parametros["titulo"]);

			  $this->set('value_monto_aux', $value_monto_aux);
			  $this->set('value_porce_aux', $value_porce_aux);
			  $this->set('value_titul_aux', $value_titul_aux);

			  $this->set('nombre_total',     $nombre_total);
			  $this->set('porcentaje_total', $porcentaje_total);
			  $this->set('monto_total',      $monto_total);

			  $this->set('cuenta_monto',     count($variables_aux["monto"]));

if(isset($parametros2["titulo"])){
   $this->Session->write('parametros2_graficas',    $parametros2);
   $this->Session->write('variables_aux2_graficas', $variables_aux2);
   $this->set('grafica_2', "si");
              $value_monto2 = implode(',', $variables_aux2["monto"]);
			  $value_porce2 = implode(',', $variables_aux2["porcentaje"]);
			  $value_titul2 = implode(',', $variables_aux2["nombre"]);

			  $value_monto_aux2 = $variables_aux2["monto"];
			  $value_porce_aux2 = $variables_aux2["porcentaje"];
			  $value_titul_aux2 = $variables_aux2["nombre"];

			  $nombre_total2     = $variables_aux2["nombre_total"];
			  $porcentaje_total2 = $variables_aux2["porcentaje_total"];
			  $monto_total2      = $variables_aux2["monto_total"];


			  $this->set('value_monto2', $value_monto2);
			  $this->set('value_porce2', $value_porce2);
			  $this->set('value_titul2', $value_titul2);

			  $this->set('titulo_grafica2', $parametros2["titulo"]);

			  $this->set('value_monto_aux2', $value_monto_aux2);
			  $this->set('value_porce_aux2', $value_porce_aux2);
			  $this->set('value_titul_aux2', $value_titul_aux2);

			  $this->set('nombre_total2',     $nombre_total2);
			  $this->set('porcentaje_total2', $porcentaje_total2);
			  $this->set('monto_total2',      $monto_total2);

			  $this->set('cuenta_monto2',     count($variables_aux2["monto"]));
}//fin if






  $this->render("genera_grafica_1");
 }//fin funtion



function genera_grafica_2(){
  $this->layout="pdf";

  $rdm = $this->Session->read('rdm_graficas');

  $this->set('username', $this->Session->read('nom_usuario'));
  $this->set('rdm', $rdm);

  $parametros    = $this->Session->read('parametros_graficas');
  $variables_aux = $this->Session->read('variables_aux_graficas');

 if(isset($parametros["torta"])){$this->set('torta', $parametros["torta"]);}
 if(isset($parametros["tipo_cantidad"])){
  	$this->set('tipo_cantidad',      $parametros["tipo_cantidad"]);
  	$this->set('value_cantidad_aux', $variables_aux["cantidad"]);
  	$this->set('cantidad_total',     $variables_aux["cantidad_total"]);
  	$value_monto = implode(',', $variables_aux["cantidad"]);
  }else{
  	$value_monto = implode(',', $variables_aux["monto"]);
  }

 if(isset($parametros["tipo_top"])){
    $_SESSION["tipo_top"]           = $parametros["tipo_top"];
	$_SESSION["DENO_REPUBLICA"]     = $parametros["DENO_REPUBLICA"];
	$_SESSION["DENO_ESTADO"]        = $parametros["DENO_ESTADO"];
	$_SESSION["DENO_COD_TIPO_INST"] = $parametros["DENO_COD_TIPO_INST"];
	$_SESSION["DENO_INST"]          = $parametros["DENO_INST"];
 }else{
 	$this->Session->delete('DENO_REPUBLICA');
 	$this->Session->delete('DENO_ESTADO');
 	$this->Session->delete('DENO_COD_TIPO_INST');
 	$this->Session->delete('DENO_INST');
    $_SESSION["tipo_top"]  = 6;
 }

			  $value_porce = implode(',', $variables_aux["porcentaje"]);
			  $value_titul = implode(',', $variables_aux["nombre"]);

			  $value_monto_aux = $variables_aux["monto"];
			  $value_porce_aux = $variables_aux["porcentaje"];
			  $value_titul_aux = $variables_aux["nombre"];

			  $nombre_total     = $variables_aux["nombre_total"];
			  $porcentaje_total = $variables_aux["porcentaje_total"];
			  $monto_total      = $variables_aux["monto_total"];

			  $this->set('value_monto', $value_monto);
			  $this->set('value_porce', $value_porce);
			  $this->set('value_titul', $value_titul);

			  if(count($parametros["titulo"])==1){
			  	$parametros["titulo"][1] = "";
			  	$parametros["titulo"][2] = "";
			  }else if(count($parametros["titulo"])==2){
			  	$parametros["titulo"][2] = "";
			  }

			  $this->set('titulo_grafica', $parametros["titulo"]);

			  $this->set('value_monto_aux', $value_monto_aux);
			  $this->set('value_porce_aux', $value_porce_aux);
			  $this->set('value_titul_aux', $value_titul_aux);

			  $this->set('nombre_total',     $nombre_total);
			  $this->set('porcentaje_total', $porcentaje_total);
			  $this->set('monto_total',      $monto_total);

			  $this->set('cuenta_monto',     count($variables_aux["monto"]));



if(isset($_SESSION["parametros2_graficas"])){
  $parametros2    = $this->Session->read('parametros2_graficas');
  $variables_aux2 = $this->Session->read('variables_aux2_graficas');
		if(isset($parametros2["titulo"])){
		   $this->set('grafica_2', "si");
		              $value_monto2 = implode(',', $variables_aux2["monto"]);
					  $value_porce2 = implode(',', $variables_aux2["porcentaje"]);
					  $value_titul2 = implode(',', $variables_aux2["nombre"]);

					  $value_monto_aux2 = $variables_aux2["monto"];
					  $value_porce_aux2 = $variables_aux2["porcentaje"];
					  $value_titul_aux2 = $variables_aux2["nombre"];

					  $nombre_total2     = $variables_aux2["nombre_total"];
					  $porcentaje_total2 = $variables_aux2["porcentaje_total"];
					  $monto_total2      = $variables_aux2["monto_total"];


					  $this->set('value_monto2', $value_monto2);
					  $this->set('value_porce2', $value_porce2);
					  $this->set('value_titul2', $value_titul2);

					       if(count($parametros2["titulo"])==1){
					  	$parametros2["titulo"][1] = "";
					  	$parametros2["titulo"][2] = "";
					  }else if(count($parametros2["titulo"])==2){
					  	$parametros2["titulo"][2] = "";
					  }

					  $this->set('titulo_grafica2', $parametros2["titulo"]);

					  $this->set('value_monto_aux2', $value_monto_aux2);
					  $this->set('value_porce_aux2', $value_porce_aux2);
					  $this->set('value_titul_aux2', $value_titul_aux2);

					  $this->set('nombre_total2',     $nombre_total2);
					  $this->set('porcentaje_total2', $porcentaje_total2);
					  $this->set('monto_total2',      $monto_total2);

					  $this->set('cuenta_monto2',     count($variables_aux2["monto"]));
		}//fin if
}//fin else


}//fin funtion







function genera_grafica_3($opcion=null){
  $this->layout="ajax";

  $rdm = $this->Session->read('rdm_graficas');

  $this->set('opcion',      $opcion);

  $this->set('username', $this->Session->read('nom_usuario'));
  $this->set('rdm', $rdm);

  $parametros    = $this->Session->read('parametros_graficas');
  $variables_aux = $this->Session->read('variables_aux_graficas');

  if(isset($parametros["torta"])){$this->set('torta', $parametros["torta"]);}
  if(isset($parametros["tipo_cantidad"])){
  	$this->set('tipo_cantidad',      $parametros["tipo_cantidad"]);
  	$this->set('value_cantidad_aux', $variables_aux["cantidad"]);
  	$this->set('cantidad_total',     $variables_aux["cantidad_total"]);
  	$value_monto = implode(',', $variables_aux["cantidad"]);
  }else{
  	$value_monto = implode(',', $variables_aux["monto"]);
  }

			  $value_porce = implode(',', $variables_aux["porcentaje"]);
			  $value_titul = implode(',', $variables_aux["nombre"]);

			  $value_monto_aux = $variables_aux["monto"];
			  $value_porce_aux = $variables_aux["porcentaje"];
			  $value_titul_aux = $variables_aux["nombre"];

			  $nombre_total     = $variables_aux["nombre_total"];
			  $porcentaje_total = $variables_aux["porcentaje_total"];
			  $monto_total      = $variables_aux["monto_total"];

			  $this->set('value_monto', $value_monto);
			  $this->set('value_porce', $value_porce);
			  $this->set('value_titul', $value_titul);

			  $this->set('titulo_grafica', $parametros["titulo"]);

			  $this->set('value_monto_aux', $value_monto_aux);
			  $this->set('value_porce_aux', $value_porce_aux);
			  $this->set('value_titul_aux', $value_titul_aux);

			  $this->set('nombre_total',     $nombre_total);
			  $this->set('porcentaje_total', $porcentaje_total);
			  $this->set('monto_total',      $monto_total);

			  $this->set('cuenta_monto',     count($variables_aux["monto"]));

if(isset($_SESSION["parametros2_graficas"])){
  $parametros2    = $this->Session->read('parametros2_graficas');
  $variables_aux2 = $this->Session->read('variables_aux2_graficas');

		if(isset($parametros2["titulo"])){
		   $this->set('grafica_2', "si");
		              $value_monto2 = implode(',', $variables_aux2["monto"]);
					  $value_porce2 = implode(',', $variables_aux2["porcentaje"]);
					  $value_titul2 = implode(',', $variables_aux2["nombre"]);

					  $value_monto_aux2 = $variables_aux2["monto"];
					  $value_porce_aux2 = $variables_aux2["porcentaje"];
					  $value_titul_aux2 = $variables_aux2["nombre"];

					  $nombre_total2     = $variables_aux2["nombre_total"];
					  $porcentaje_total2 = $variables_aux2["porcentaje_total"];
					  $monto_total2      = $variables_aux2["monto_total"];


					  $this->set('value_monto2', $value_monto2);
					  $this->set('value_porce2', $value_porce2);
					  $this->set('value_titul2', $value_titul2);

					  $this->set('titulo_grafica2', $parametros2["titulo"]);

					  $this->set('value_monto_aux2', $value_monto_aux2);
					  $this->set('value_porce_aux2', $value_porce_aux2);
					  $this->set('value_titul_aux2', $value_titul_aux2);

					  $this->set('nombre_total2',     $nombre_total2);
					  $this->set('porcentaje_total2', $porcentaje_total2);
					  $this->set('monto_total2',      $monto_total2);

					  $this->set('cuenta_monto2',     count($variables_aux2["monto"]));
		}//fin if
}




}//fin funtion



function grafica_1($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){
				      for($minCount = 2007; $minCount < 2030; $minCount++) {
					    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
					    $this->set('anos',$anos);
				       }

				        $year = $this->ano_ejecucion();
				        $this->Session->write('ano_recurso_asignacion', $year);
				        $this->set('year', $year);
	}else if($var1==2){
                       $consolidacion= !isset($this->data['datos']['consolidacion'])?2:$this->data['datos']['consolidacion'];
                       if(isset($this->data['datos']['ano'])){           $year          = $this->data["datos"]["ano"];}
                       if(isset($this->data['datos']['tipo_recurso'])){  $tipo_recurso  = $this->data["datos"]["tipo_recurso"];}

			            if($consolidacion==1){
			            	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." ";
						 	$cod_dep_sql = "";
						    $cod_dep_sql2  = "";
						    $cod_dep_sql22 = "";
						}else{
							$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst."  and a.cod_dep_original = ".$this->cod_dep_consolidado()." ";
							$cod_dep_sql  = "  and x.cod_dep_original=".$this->cod_dep_consolidado()." ";
						    $cod_dep_sql2 = " , a.cod_dep";
						    $cod_dep_sql22 = " , x.cod_dep";
						}//fin else


						if($tipo_recurso!=9){

							$cod_dep_sql  .= " and x.tipo_recurso=".$tipo_recurso." ";
							$cond         .= " and a.tipo_recurso=".$tipo_recurso." ";
							$cod_dep_sql2 .= " , a.tipo_recurso";
							$cod_dep_sql22 .= " , x.tipo_recurso";
						}

				 $datos = $this->cfpd07_plan_inversion->execute("SELECT
									a.cod_presi,
									a.cod_entidad,
									a.cod_tipo_inst,
									a.cod_inst ".$cod_dep_sql2.",
									(SELECT SUM(estimado_presu)     FROM cfpd07_obras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql."  and  x.ano_estimacion    =    ".$year." and status=1 group by a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst ".$cod_dep_sql22.") as asignacion_inicial,
									(SELECT SUM(aumento_obras)      FROM cfpd07_obras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql."  and  x.ano_estimacion    =    ".$year." and status=1 group by a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst ".$cod_dep_sql22.") as asignacion_inicial_aumento,
									(SELECT SUM(disminucion_obras)  FROM cfpd07_obras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql."  and  x.ano_estimacion    =    ".$year." and status=1 group by a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst ".$cod_dep_sql22.") as asignacion_inicial_disminucion,
									(SELECT SUM(estimado_presu)     FROM cfpd07_obras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql."  and  x.ano_estimacion    =    ".$year." and status=2 group by a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst ".$cod_dep_sql22.") as credito_adicional,
									(SELECT SUM(aumento_obras)      FROM cfpd07_obras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql."  and  x.ano_estimacion    =    ".$year." and status=2 group by a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst ".$cod_dep_sql22.") as credito_adicional_aumento,
									(SELECT SUM(disminucion_obras)  FROM cfpd07_obras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql."  and  x.ano_estimacion    =    ".$year." and status=2 group by a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst ".$cod_dep_sql22.") as credito_adicional_disminucion,
									(SELECT SUM(estimado_presu)     FROM cfpd07_obras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql."  and  x.ano_estimacion    =    ".$year."              group by a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst ".$cod_dep_sql22.") as total,
                                    (SELECT SUM(aumento_obras)      FROM  cfpd07_obras_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql." and  x.ano_estimacion    =    ".$year."        group by a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst ".$cod_dep_sql22.") as aumento_obras,
                                    (SELECT SUM(disminucion_obras)  FROM  cfpd07_obras_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst ".$cod_dep_sql." and  x.ano_estimacion    =    ".$year."        group by a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst ".$cod_dep_sql22.") as disminucion_obras
									FROM
									       cfpd07_obras_cuerpo a
									WHERE
									  ".$cond." and  a.ano_estimacion    =    ".$year."
									group by
										a.cod_presi,
										a.cod_entidad,
										a.cod_tipo_inst,
										a.cod_inst ".$cod_dep_sql2."
									order by
										a.cod_presi,
										a.cod_entidad,
										a.cod_tipo_inst,
										a.cod_inst ".$cod_dep_sql2.";	");
				       if($tipo_recurso==1){  $opcion1_aux = "TIPO DEL RECURSO: Ordinario,";
				 }else if($tipo_recurso==2){  $opcion1_aux = "TIPO DEL RECURSO: Coordinado,";
				 }else if($tipo_recurso==3){  $opcion1_aux = "TIPO DEL RECURSO: Fci,";
				 }else if($tipo_recurso==4){  $opcion1_aux = "TIPO DEL RECURSO: Mpps,";
				 }else if($tipo_recurso==5){  $opcion1_aux = "TIPO DEL RECURSO: Ingresos Extraordinarios,";
				 }else if($tipo_recurso==6){  $opcion1_aux = "TIPO DEL RECURSO: Ingresos propios,";
				 }else if($tipo_recurso==7){  $opcion1_aux = "TIPO DEL RECURSO: Laee,";
				 }else if($tipo_recurso==8){  $opcion1_aux = "TIPO DEL RECURSO: Fides,";
				 }else if($tipo_recurso==9){  $opcion1_aux = ""; }
				 $parametros["titulo"][]  = "ASIGNACIÓN INICIAL VS CRÉDITOS ADICIONALES";
				 $parametros["titulo"][]  = $opcion1_aux." AÑO RECURSO: ".$year;
                 $variables["nombre_total"] = "Total presupuestado";
                 $variables["nombre"][] = "Asignación inicial";
                 $variables["nombre"][] = "Credito adicional";
                 if(isset($datos[0][0]['total'])){
		                 $asignacion_inicial = ($datos[0][0]['asignacion_inicial']+$datos[0][0]['asignacion_inicial_aumento'])- $datos[0][0]['asignacion_inicial_disminucion'];
		                 $credito_adicional  = ($datos[0][0]['credito_adicional'] +$datos[0][0]['credito_adicional_aumento']) - $datos[0][0]['credito_adicional_disminucion'];
		                 $total              = ($datos[0][0]['total'] + $datos[0][0]['aumento_obras']) - $datos[0][0]['disminucion_obras'];
		                 $variables["porcentaje_total"] = ($total * 100) / $total;
		                 $variables["porcentaje"][] = ($asignacion_inicial * 100) / $total;
		                 $variables["porcentaje"][] = ($credito_adicional * 100) / $total;
		                 $variables["monto_total"] = $total;
		                 $variables["monto"][] = $asignacion_inicial;
		                 $variables["monto"][] = $credito_adicional;
                 }else{
                 	     $variables["porcentaje_total"] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["monto_total"]  = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
                 }//fin else
                 $this->genera_grafica_1(1, $parametros, $variables);

	}//fin else if

$this->set('opcion', $var1);


}//fin function











function grafica_2($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){
				      for($minCount = 2007; $minCount < 2030; $minCount++) {
					    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
					    $this->set('anos',$anos);
				       }

				        $year = $this->ano_ejecucion();
				        $this->Session->write('ano_recurso_asignacion', $year);
				        $this->set('year', $year);
	}else if($var1==2){
                       $consolidacion= !isset($this->data['datos']['consolidacion'])?2:$this->data['datos']['consolidacion'];
                       if(isset($this->data['datos']['ano'])){           $year          = $this->data["datos"]["ano"];}
                       if(isset($this->data['datos']['tipo_recurso'])){  $tipo_recurso  = $this->data["datos"]["tipo_recurso"];}

			            if($consolidacion==1){
			            	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." ";
						}else{
							$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst."  and a.cod_dep_original = ".$this->cod_dep_consolidado()." ";
						}//fin else

						if($tipo_recurso!=9){
							$cond         .= " and a.tipo_recurso=".$tipo_recurso." ";
						}
                 $datos = $this->cfpd07_plan_inversion->execute("SELECT
    					 a.cod_presi,
    					 a.cod_entidad,
    					 a.cod_tipo_inst,
    					 a.cod_inst,
    					 a.ano_estimacion,
				 	     SUM(a.estimado_presu)    as  asignacion_total,
				 	     SUM(a.monto_contratado)  as  monto_presupuestado,
				 	     SUM(a.aumento_obras)     as  aumento_obras,
				 	     SUM(a.disminucion_obras) as  disminucion_obras

				 		      FROM cfpd07_obras_cuerpo a where ".$cond." and  a.ano_estimacion  =   ".$year."

                        GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst,  a.ano_estimacion
    					ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst DESC;");


				       if($tipo_recurso==1){  $opcion1_aux = "TIPO DEL RECURSO: Ordinario,";
				 }else if($tipo_recurso==2){  $opcion1_aux = "TIPO DEL RECURSO: Coordinado,";
				 }else if($tipo_recurso==3){  $opcion1_aux = "TIPO DEL RECURSO: Fci,";
				 }else if($tipo_recurso==4){  $opcion1_aux = "TIPO DEL RECURSO: Mpps,";
				 }else if($tipo_recurso==5){  $opcion1_aux = "TIPO DEL RECURSO: Ingresos Extraordinarios,";
				 }else if($tipo_recurso==6){  $opcion1_aux = "TIPO DEL RECURSO: Ingresos propios,";
				 }else if($tipo_recurso==7){  $opcion1_aux = "TIPO DEL RECURSO: Laee,";
				 }else if($tipo_recurso==8){  $opcion1_aux = "TIPO DEL RECURSO: Fides,";
				 }else if($tipo_recurso==9){  $opcion1_aux = ""; }
				 $parametros["titulo"][]  = "CONTRATADO VS POR CONTRATAR";
				 $parametros["titulo"][]  = $opcion1_aux." AÑO RECURSO: ".$year;
                 $variables["nombre_total"] = "Total presupuestado";
                 $variables["nombre"][] = "Monto contratado";
                 $variables["nombre"][] = "Monto por contratar";
                 if(isset($datos[0][0]['asignacion_total'])){
		                 $total_presupuestado = ($datos[0][0]['asignacion_total'] + $datos[0][0]['aumento_obras']) -  $datos[0][0]['disminucion_obras'];
		                 $monto_contratado    = ($datos[0][0]['monto_presupuestado'] + $datos[0][0]['aumento_obras']) -  $datos[0][0]['disminucion_obras'];
		                 $diferencia          =  $total_presupuestado - $monto_contratado;

		                 $variables["porcentaje_total"] = ($total_presupuestado * 100) / $total_presupuestado;
		                 $variables["porcentaje"][] = ($monto_contratado * 100) / $total_presupuestado;
		                 $variables["porcentaje"][] = ($diferencia * 100) / $total_presupuestado;

		                 $variables["monto_total"] = $total_presupuestado;
		                 $variables["monto"][] = $monto_contratado;
		                 $variables["monto"][] = $diferencia;
                 }else{
                 	     $variables["porcentaje_total"] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["monto_total"]  = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
                 }//fin else
                 $this->genera_grafica_1(1, $parametros, $variables);

	}//fin else if

$this->set('opcion', $var1);


}//fin function









function grafica_3($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){
				      for($minCount = 2007; $minCount < 2030; $minCount++) {
					    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
					    $this->set('anos',$anos);
				       }

				        $year = $this->ano_ejecucion();
				        $this->Session->write('ano_recurso_asignacion', $year);
				        $this->set('year', $year);
	}else if($var1==2){
                       $consolidacion= !isset($this->data['datos']['consolidacion'])?2:$this->data['datos']['consolidacion'];
                       if(isset($this->data['datos']['ano'])){           $year          = $this->data["datos"]["ano"];}
                       if(isset($this->data['datos']['tipo_recurso'])){  $tipo_recurso  = $this->data["datos"]["tipo_recurso"];}

			            if($consolidacion==1){
			            	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." ";
						}else{
							$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst."  and a.cod_dep_original = ".$this->cod_dep_consolidado()." ";
						}//fin else

						if($tipo_recurso!=9){
							$cond          .= " and a.tipo_recurso=".$tipo_recurso." ";
						}

    			$datos = $this->cfpd07_plan_inversion->execute("SELECT
    					 a.cod_presi,
    					 a.cod_entidad,
    					 a.cod_tipo_inst,
    					 a.cod_inst,
    					 a.ano_estimacion,
				 	     SUM(a.estimado_presu)    as  asignacion_total,
				 	     SUM(a.monto_contratado)  as  monto_presupuestado,
				 	     SUM(a.aumento_obras)     as  aumento_obras,
				 	     SUM(a.disminucion_obras) as  disminucion_obras,
                         (SELECT SUM(monto_anticipo)     FROM  cobd01_contratoobras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.ano_estimacion=a.ano_estimacion  and x.condicion_actividad=1) as monto_anticipo,
	                     (SELECT SUM(monto_cancelado)    FROM  cobd01_contratoobras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.ano_estimacion=a.ano_estimacion  and x.condicion_actividad=1) as monto_cancelado,
	                     (SELECT SUM(monto_amortizacion) FROM  cobd01_contratoobras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.ano_estimacion=a.ano_estimacion  and x.condicion_actividad=1) as monto_amortizacion,
                         (SELECT SUM(monto_contratado)   FROM  cfpd07_obras_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.ano_estimacion=a.ano_estimacion) as monto_contratado

				 		      FROM cfpd07_obras_cuerpo a where ".$cond." and  a.ano_estimacion  =   ".$year."

                         GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano_estimacion
    					 ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst DESC;");


				       if($tipo_recurso==1){  $opcion1_aux = "TIPO DEL RECURSO: Ordinario,";
				 }else if($tipo_recurso==2){  $opcion1_aux = "TIPO DEL RECURSO: Coordinado,";
				 }else if($tipo_recurso==3){  $opcion1_aux = "TIPO DEL RECURSO: Fci,";
				 }else if($tipo_recurso==4){  $opcion1_aux = "TIPO DEL RECURSO: Mpps,";
				 }else if($tipo_recurso==5){  $opcion1_aux = "TIPO DEL RECURSO: Ingresos Extraordinarios,";
				 }else if($tipo_recurso==6){  $opcion1_aux = "TIPO DEL RECURSO: Ingresos propios,";
				 }else if($tipo_recurso==7){  $opcion1_aux = "TIPO DEL RECURSO: Laee,";
				 }else if($tipo_recurso==8){  $opcion1_aux = "TIPO DEL RECURSO: Fides,";
				 }else if($tipo_recurso==9){  $opcion1_aux = ""; }
				 $parametros["titulo"][]  = "CONTRATADO VS PAGADO";
				 $parametros["titulo"][]  = $opcion1_aux." AÑO RECURSO: ".$year;
                 $variables["nombre_total"] = "Total Contratado";
                 $variables["nombre"][] = "Total Pagado";
                 $variables["nombre"][] = "Total por pagar";


                 if(isset($datos[0][0]['monto_contratado']) && $datos[0][0]['monto_contratado']!=0){

		                 $vara = ($datos[0][0]['monto_contratado']);
						 $varb = ($datos[0][0]['monto_anticipo']    + $datos[0][0]['monto_cancelado']) -  $datos[0][0]['monto_amortizacion'];

		                 $total_contratado = $vara;
		                 $pagado           = $varb;
		                 $por_pagar        = $vara - $varb;

		                 $variables["porcentaje_total"] = ($total_contratado * 100) / $total_contratado;
		                 $variables["porcentaje"][] = ($pagado * 100) / $total_contratado;
		                 $variables["porcentaje"][] = ($por_pagar * 100) / $total_contratado;

		                 $variables["monto_total"] = $total_contratado;
		                 $variables["monto"][] = $pagado;
		                 $variables["monto"][] = $por_pagar;
                 }else{
                 	     $variables["porcentaje_total"] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["monto_total"]  = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
                 }//fin else
                 $this->genera_grafica_1(1, $parametros, $variables);

	}//fin else if

$this->set('opcion', $var1);


}//fin function










function grafica_4($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	     if($var1==1){
				      for($minCount = 2007; $minCount < 2030; $minCount++) {
					    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
					    $this->set('anos',$anos);
				       }

				        $year = $this->ano_ejecucion();
				        $this->Session->write('ano_recurso_asignacion', $year);
				        $this->set('year', $year);
	}else if($var1==2){
                       $consolidacion= !isset($this->data['datos']['consolidacion'])?2:$this->data['datos']['consolidacion'];
                       if(isset($this->data['datos']['ano'])){           $year          = $this->data["datos"]["ano"];}
                       if(isset($this->data['datos']['tipo_recurso'])){  $tipo_recurso  = $this->data["datos"]["tipo_recurso"];}

			            if($consolidacion==1){
			            	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." ";
						}else{
                            $cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." ";
						}//fin else

						if($tipo_recurso!=9){
							$cond          .= " and a.tipo_recurso=".$tipo_recurso." ";
						}

			    $sql_1 = "SELECT a.ano_recurso,
			    		     SUM(a.asignacion_total) as asignacion_total,
			    		     SUM(a.monto_presupuestado) as monto_presupuestado,
			    		     SUM((a.asignacion_total - a.monto_presupuestado)) as diferencia
			    		  FROM cfpd07_plan_inversion a
			    		  WHERE ".$cond." and  a.ano_recurso  =   ".$year."
			    		   GROUP BY a.cod_presi,
			    		            a.cod_entidad,
			    		            a.cod_tipo_inst,
			    		            a.cod_inst,
			    		            a.ano_recurso
			    		   ORDER BY a.cod_presi,
			    		            a.cod_entidad,
			    		            a.cod_tipo_inst,
			    		            a.ano_recurso";
	             $datos = $this->cfpd07_plan_inversion->execute($sql_1);


				       if($tipo_recurso==1){  $opcion1_aux = "TIPO DEL RECURSO: Ordinario,";
				 }else if($tipo_recurso==2){  $opcion1_aux = "TIPO DEL RECURSO: Coordinado,";
				 }else if($tipo_recurso==3){  $opcion1_aux = "TIPO DEL RECURSO: Fci,";
				 }else if($tipo_recurso==4){  $opcion1_aux = "TIPO DEL RECURSO: Mpps,";
				 }else if($tipo_recurso==5){  $opcion1_aux = "TIPO DEL RECURSO: Ingresos Extraordinarios,";
				 }else if($tipo_recurso==6){  $opcion1_aux = "TIPO DEL RECURSO: Ingresos propios,";
				 }else if($tipo_recurso==7){  $opcion1_aux = "TIPO DEL RECURSO: Laee,";
				 }else if($tipo_recurso==8){  $opcion1_aux = "TIPO DEL RECURSO: Fides,";
				 }else if($tipo_recurso==9){  $opcion1_aux = ""; }
				 $parametros["titulo"][]  = "RECURSO PROYECTADO VS PRESUPUESTADO";
				 $parametros["titulo"][]  = $opcion1_aux." AÑO RECURSO: ".$year;
                 $variables["nombre_total"] = "Asignación total";
                 $variables["nombre"][] = "Presupuestado";
                 $variables["nombre"][] = "No Presupuestado";
                 if(isset($datos[0][0]['asignacion_total']) && $datos[0][0]['asignacion_total']!=0){
		                 $asignacion_total        = $datos[0][0]['asignacion_total'];
		                 $monto_presupuestado     = $datos[0][0]['monto_presupuestado'];
		                 $diferencia              = $datos[0][0]['diferencia'];

		                 $variables["porcentaje_total"] = ($asignacion_total * 100) / $asignacion_total;
		                 $variables["porcentaje"][] = ($monto_presupuestado * 100) / $asignacion_total;
		                 $variables["porcentaje"][] = ($diferencia * 100) / $asignacion_total;

		                 $variables["monto_total"] = $asignacion_total;
		                 $variables["monto"][] = $monto_presupuestado;
		                 $variables["monto"][] = $diferencia;
                 }else{
                 	     $variables["porcentaje_total"] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["monto_total"]  = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
                 }//fin else
                 $this->genera_grafica_1(1, $parametros, $variables);

	}//fin else if

$this->set('opcion', $var1);


}//fin function













function grafica_5($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){
				      for($minCount = 2007; $minCount < 2030; $minCount++) {
					    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
					    $this->set('anos',$anos);
				       }

				        $year = $this->ano_ejecucion();
				        $this->Session->write('ano_recurso_asignacion', $year);
				        $this->set('year', $year);
	}else if($var1==2){
                       $consolidacion= !isset($this->data['datos']['consolidacion'])?2:$this->data['datos']['consolidacion'];
                       if(isset($this->data['datos']['ano'])){           $year          = $this->data["datos"]["ano"];}

					    $cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." ";


    					$sql_2          = "SELECT SUM(ordinario)::numeric(22,2) as ordinario,
    							                  SUM(coordinado)::numeric(22,2) as coordinado,
    							                  SUM(fci)::numeric(22,2) as fci,
    							                  SUM(mpps)::numeric(22,2) as mpps,
    							                  SUM(ingreso_extraordinario)::numeric(22,2) as ingreso_extraordinario,
    							              	  SUM(ingreso_propio)::numeric(22,2) as ingreso_propio,
    							                  SUM(laee)::numeric(22,2) as laee,
    							                  SUM(fides)::numeric(22,2) as fides

    							           FROM cfpd07_tipo_recurso_proyectado a

    							           WHERE a.cod_presi     ='$cod_presi' AND
    							                 a.cod_entidad   ='$cod_entidad' AND
    							                 a.cod_tipo_inst ='$cod_tipo_inst' AND
    							                 a.cod_inst      ='$cod_inst' AND
    							                 a.ano_recurso   ='$year'

    							            GROUP BY a.cod_presi,
    							                     a.cod_entidad,
    							                     a.cod_tipo_inst,
    							                     a.cod_inst,
    							                     a.ano_recurso";

					    $sql_2_asignado = "SELECT
					    		                    SUM(ordinario_asignado)::numeric(22,2) as ordinario,
					    		                    SUM(coordinado_asignado)::numeric(22,2) as coordinado,
					    		                    SUM(fci_asignado)::numeric(22,2) as fci,
					    		                    SUM(mpps_asignado)::numeric(22,2) as mpps,
					    		                    SUM(ingreso_extraordinario_asignado)::numeric(22,2) as ingreso_extraordinario,
					    		                    SUM(ingreso_propio)::numeric(22,2) as ingreso_propio,
					    		                    SUM(laee)::numeric(22,2) as laee,
					    		                    SUM(fides)::numeric(22,2) as fides
					    		           FROM cfpd07_tipo_recurso_proyectado a  WHERE a.cod_presi='$cod_presi' AND a.cod_entidad='$cod_entidad' AND a.cod_tipo_inst='$cod_tipo_inst' AND a.cod_inst='$cod_inst' AND a.ano_recurso='$year' GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano_recurso";

						$sql_3          = "SELECT a.cod_presi,
								                           a.cod_entidad,
								                           a.cod_tipo_inst,
								                           a.cod_inst,
								                           a.ano_recurso,
								                           a.tipo_recurso,
								                           a.clasificacion_recurso,
								                           b.denominacion,
								                           a.monto_presupuestado
								                     FROM cfpd07_plan_inversion a, cfpd07_clasificacion_recurso b WHERE a.cod_presi='$cod_presi' AND a.cod_entidad='$cod_entidad' AND a.cod_tipo_inst='$cod_tipo_inst' AND a.cod_inst='$cod_inst' AND a.ano_recurso='$year' AND a.cod_presi=b.cod_presi AND a.cod_entidad=b.cod_entidad AND a.cod_tipo_inst=b.cod_tipo_inst AND a.cod_inst=b.cod_inst AND a.tipo_recurso=b.tipo_recurso AND a.clasificacion_recurso=b.clasificacion_recurso AND a.tipo_recurso=5";
						$sql_1          = "SELECT a.ano_recurso,
						                          SUM(a.asignacion_total) as asignacion_total,
						                          SUM(a.monto_presupuestado) as monto_presupuestado,
						                          SUM((a.asignacion_total - a.monto_presupuestado)) as diferencia
						                          FROM cfpd07_plan_inversion a WHERE a.cod_presi='$cod_presi' AND a.cod_entidad='$cod_entidad' AND a.cod_tipo_inst='$cod_tipo_inst' AND a.cod_inst='$cod_inst' AND a.ano_recurso='$year' GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano_recurso ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.ano_recurso";

					  	$datos_grap1               = $this->cfpd07_plan_inversion->execute($sql_1);
						$datos_grap2_presupuestado = $this->cfpd07_plan_inversion->execute($sql_2);
						$datos_grap2_asignado      = $this->cfpd07_plan_inversion->execute($sql_2_asignado);
						$datos_grap3               = $this->cfpd07_plan_inversion->execute($sql_3);


				 $parametros["titulo"][]  = "DISTRIBUCIÓN DEL PLAN DE INVERSIÓN EN EL PRESUPUESTO";
				 $parametros["titulo"][]  = " Recursos ".$year." (Presupuestado), AÑO RECURSO: ".$year;

				 $variables["nombre_total"] = "Total Presupuestado";
                 $variables["nombre"][] = "Ordinario";
                 $variables["nombre"][] = "Coordinado";
                 $variables["nombre"][] = "Fci";
                 $variables["nombre"][] = "Mpps";
                 $variables["nombre"][] = "Ingresos extraordinarios";
                 $variables["nombre"][] = "Ingresos propios";
                 $variables["nombre"][] = "Laee";
                 $variables["nombre"][] = "Fides";

                 $parametros2 = null;
                 $variables2  = null;

                 if(isset($datos_grap1[0][0]['monto_presupuestado'])){


	                 	   foreach($datos_grap1 as $row){
								$asignacion_total    = $row[0]['asignacion_total'];
								$monto_presupuestado = $row[0]['monto_presupuestado'];
								$diferencia          = $row[0]['diferencia'];
							 }
		                   foreach ($datos_grap2_presupuestado as $row1){
								$ordinario      = $row1[0]['ordinario'];
								$coordinado     = $row1[0]['coordinado'];
								$fci            = $row1[0]['fci'];
								$mpps           = $row1[0]['mpps'];
								$extraordinario = $row1[0]['ingreso_extraordinario'];
								$propios        = $row1[0]['ingreso_propio'];
								$laee           = $row1[0]['laee'];
								$fides          = $row1[0]['fides'];
							}
                           if($extraordinario!=0){
                           	     $parametros2["titulo"][]  = "DISTRIBUCIÓN DEL PLAN DE INVERSIÓN EN EL PRESUPUESTO";
								 $parametros2["titulo"][]  = " Recursos Extraordinarios ".$year." AÑO RECURSO: ".$year;

								 $variables2["nombre_total"] = "Ingreso Extraordinario";
								 $variables2["porcentaje_total"] = ($extraordinario * 100) / $extraordinario;
			                     $variables2["monto_total"]      =  $extraordinario;

	                           	foreach($datos_grap3 as $row3){
									$variables2["nombre"][]     = $row3[0]['denominacion'];
									$variables2["porcentaje"][] = ($row3[0]['monto_presupuestado']* 100) / $extraordinario;
									$variables2["monto"][]      = $row3[0]['monto_presupuestado'];
								}
                           }



			                 $variables["porcentaje_total"] = ($monto_presupuestado * 100) / $monto_presupuestado;
			                 $variables["monto_total"]      =  $monto_presupuestado;

			                 $variables["porcentaje"][] = ($ordinario * 100) / $monto_presupuestado;
			                 $variables["porcentaje"][] = ($coordinado * 100) / $monto_presupuestado;
			                 $variables["porcentaje"][] = ($fci * 100) / $monto_presupuestado;
			                 $variables["porcentaje"][] = ($mpps * 100) / $monto_presupuestado;
			                 $variables["porcentaje"][] = ($extraordinario * 100) / $monto_presupuestado;
			                 $variables["porcentaje"][] = ($propios * 100) / $monto_presupuestado;
			                 $variables["porcentaje"][] = ($laee * 100) / $monto_presupuestado;
			                 $variables["porcentaje"][] = ($fides * 100) / $monto_presupuestado;

			                 $variables["monto"][] = $ordinario;
			                 $variables["monto"][] = $coordinado;
			                 $variables["monto"][] = $fci;
			                 $variables["monto"][] = $mpps;
			                 $variables["monto"][] = $extraordinario;
			                 $variables["monto"][] = $propios;
			                 $variables["monto"][] = $laee;
			                 $variables["monto"][] = $fides;
                 }else{
                 	     $variables["porcentaje_total"] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;

		                 $variables["monto_total"]  = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;

                 }//fin else
                 $this->genera_grafica_1(1, $parametros, $variables, $parametros2, $variables2);

	}//fin else if

$this->set('opcion', $var1);


}//fin function

















function grafica_6($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){
				      for($minCount = 2007; $minCount < 2030; $minCount++) {
					    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
					    $this->set('anos',$anos);
				       }
				        $dato = $this->cfpd01_formulacion->findAll($this->condicionNDEP(), null, 'ano_formular ASC', null);
						foreach($dato as $ve){
							$year = $ve['cfpd01_formulacion']['ano_formular'];
						}
//				        $year = $this->ano_ejecucion();
				        $this->Session->write('ano_recurso_asignacion', $year);
				        $this->set('year', $year);
	}else if($var1==2){
                       $consolidacion= !isset($this->data['datos']['consolidacion'])?2:$this->data['datos']['consolidacion'];
                       if(isset($this->data['datos']['ano'])){           $year          = $this->data["datos"]["ano"];}
					    if($consolidacion==1){
							$condicion  = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
							$condicion .= " and ano=".$year." GROUP BY cod_presi, cod_entidad, cod_tipo_inst, cod_inst, ano";
							$fields = "SUM(gasto_inversion) as gasto_inversion, SUM(gasto_corriente) as gasto_corriente, SUM(total) as total";
						}else{

							$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$this->cod_dep_consolidado()." and ano=".$year."";
						    $fields = "SUM(gasto_inversion) as gasto_inversion, SUM(gasto_corriente) as gasto_corriente, SUM(total) as total";
						}
						$datos = $this->v_cfpd05_asignacion_corriente_capital->findAll($condicion, $fields, $order = null, $limit = null, $page = null, $recursive = null);

                         foreach($datos as $row){
						 	$gasto_inversion = $row[0]['gasto_inversion'];
						 	$gasto_corriente = $row[0]['gasto_corriente'];
						 	$total = $row[0]['total'];
						 }

				 $parametros["titulo"][]  = "TIPOS DE GASTOS";
				 $parametros["titulo"][]  = " AÑO RECURSO: ".$year;
                 $variables["nombre_total"] = "Gasto Total";
                 $variables["nombre"][] = "Gasto Corriente";
                 $variables["nombre"][] = "Gasto Inversión";

                 if(isset($datos[0][0]['total'])){
		                 $variables["porcentaje_total"] = ($total * 100) / $total;
		                 $variables["porcentaje"][] = ($gasto_corriente * 100) / $total;
		                 $variables["porcentaje"][] = ($gasto_inversion * 100) / $total;
		                 $variables["monto_total"] = $total;
		                 $variables["monto"][] = $gasto_corriente;
		                 $variables["monto"][] = $gasto_inversion;
                 }else{
                 	     $variables["porcentaje_total"] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["monto_total"]  = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
                 }//fin else
                 $this->genera_grafica_1(1, $parametros, $variables);

	}//fin else if

$this->set('opcion', $var1);


}//fin function






function grafica_7($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){
				      for($minCount = 2007; $minCount < 2030; $minCount++) {
					    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
					    $this->set('anos',$anos);
				       }
				        $dato = $this->cfpd01_formulacion->findAll($this->condicionNDEP(), null, 'ano_formular ASC', null);
						foreach($dato as $ve){
							$year = $ve['cfpd01_formulacion']['ano_formular'];
						}
//				        $year = $this->ano_ejecucion();
				        $this->Session->write('ano_recurso_asignacion', $year);
				        $this->set('year', $year);
	}else if($var1==2){
                       $consolidacion= !isset($this->data['datos']['consolidacion'])?2:$this->data['datos']['consolidacion'];
                       if(isset($this->data['datos']['ano'])){           $year          = $this->data["datos"]["ano"];}

						if($year<=2011){
							$var_a = "Laee";
							$var_b = "Fides";
						}else{
							$var_a = "Fci";
							$var_b = "Mpps";
						}

                        if(isset($consolidacion) && $this->verifica_SS(5)==1){
								        if($consolidacion==1){
								           $cod_dep="";
								           $cod_dep2="";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror="";
								           $this->set("opcion",1);
								        }else{
								           $cod_dep="a.cod_dep,";
								           $cod_dep2="x.cod_dep=a.cod_dep and";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror=",a.cod_dep";
								           $this->set("opcion",2);
								        }
								    }else{
								        $cod_dep="a.cod_dep,";
								           $cod_dep2="x.cod_dep=a.cod_dep and";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror=",a.cod_dep";
								           $this->set("opcion",2);
								    }

								    $tipo_presupuesto=$this->cfpd05->execute("SELECT
																						a.cod_presi,
																						a.cod_entidad,
																						a.cod_tipo_inst,
																						a.cod_inst,
																						".$cod_dep."
																						(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.tipo_presupuesto=1 and x.ano=".$year.") as ordinario,
																						(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.tipo_presupuesto=2 and x.ano=".$year.") as coordinado,
																						(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.tipo_presupuesto=3 and x.ano=".$year.") as laee,
																						(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.tipo_presupuesto=4 and x.ano=".$year.") as fides,
																						(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.tipo_presupuesto=5 and x.ano=".$year.") as ingresos_extra,
																						(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.tipo_presupuesto=6 and x.ano=".$year.") as ingresos_propios,
																						(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.ano=".$year.") as asignacion_total
																						   FROM
																						cfpd05 a
																						WHERE
																						   	".$con." and a.ano=".$year."
																						group by
																						a.cod_presi,
																						a.cod_entidad,
																						a.cod_tipo_inst,
																						a.cod_inst
																						".$gror."
																						order by
																						a.cod_presi,
																						a.cod_entidad,
																						a.cod_tipo_inst,
																						a.cod_inst
																						".$gror.";");




				 $parametros["titulo"][]  = "TIPOS DE RECURSOS";
				 $parametros["titulo"][]  = " AÑO RECURSO: ".$year;


                 $variables["nombre_total"] = "Total Presupuesto";
                 $variables["nombre"][] = "Ordinario";
                 $variables["nombre"][] = "Coordinado";
                 $variables["nombre"][] = $var_a;
                 $variables["nombre"][] = $var_b;
                 $variables["nombre"][] = "Ingresos extraordinarios";
                 $variables["nombre"][] = "Ingresos propios";

                 if(isset($tipo_presupuesto[0][0])){
                 	    $tp               = $tipo_presupuesto[0][0];
						$ordinario         = $tp["ordinario"]==null?0.00:$tp["ordinario"];
						$coordinado        = $tp["coordinado"]==null?0.00:$tp["coordinado"];
						$laee              = $tp["laee"]==null?0.00:$tp["laee"];
						$fides             = $tp["fides"]==null?0.00:$tp["fides"];
						$ingresos_extra    = $tp["ingresos_extra"]==null?0.00:$tp["ingresos_extra"];
						$ingresos_propios  = $tp["ingresos_propios"]==null?0.00:$tp["ingresos_propios"];
						$total_presupuesto = $ordinario+$coordinado+$laee+$fides+$ingresos_extra+$ingresos_propios;

		                 $variables["porcentaje_total"] = ($total_presupuesto * 100) / $total_presupuesto;
		                 $variables["porcentaje"][] = ($ordinario * 100) / $total_presupuesto;
		                 $variables["porcentaje"][] = ($coordinado * 100) / $total_presupuesto;
		                 $variables["porcentaje"][] = ($laee * 100) / $total_presupuesto;
		                 $variables["porcentaje"][] = ($fides * 100) / $total_presupuesto;
		                 $variables["porcentaje"][] = ($ingresos_extra * 100) / $total_presupuesto;
		                 $variables["porcentaje"][] = ($ingresos_propios * 100) / $total_presupuesto;

		                 $variables["monto_total"] = $total_presupuesto;
		                 $variables["monto"][] = $ordinario;
		                 $variables["monto"][] = $coordinado;
		                 $variables["monto"][] = $laee;
		                 $variables["monto"][] = $fides;
		                 $variables["monto"][] = $ingresos_extra;
		                 $variables["monto"][] = $ingresos_propios;
                 }else{
                 	     $variables["porcentaje_total"] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["monto_total"]  = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
                 }//fin else
                 $this->genera_grafica_1(1, $parametros, $variables);

	}//fin else if

$this->set('opcion', $var1);


}//fin function






function grafica_8($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){
				      for($minCount = 2007; $minCount < 2030; $minCount++) {
					    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
					    $this->set('anos',$anos);
				       }
				        $dato = $this->cfpd01_formulacion->findAll($this->condicionNDEP(), null, 'ano_formular ASC', null);
						foreach($dato as $ve){
							$year = $ve['cfpd01_formulacion']['ano_formular'];
						}
//				        $year = $this->ano_ejecucion();
				        $this->Session->write('ano_recurso_asignacion', $year);
				        $this->set('year', $year);
	}else if($var1==2){
                       $consolidacion= !isset($this->data['datos']['consolidacion'])?2:$this->data['datos']['consolidacion'];
                       if(isset($this->data['datos']['ano'])){           $year          = $this->data["datos"]["ano"];}

                        if(isset($consolidacion) && $this->verifica_SS(5)==1){
								        if($consolidacion==1){
								           $cod_dep="";
								           $cod_dep2="";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror="";
								           $this->set("opcion",1);
								        }else{
								           $cod_dep="a.cod_dep,";
								           $cod_dep2="x.cod_dep=a.cod_dep and";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror=",a.cod_dep";
								           $this->set("opcion",2);
								        }
								    }else{
								        $cod_dep="a.cod_dep,";
								           $cod_dep2="x.cod_dep=a.cod_dep and";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror=",a.cod_dep";
								           $this->set("opcion",2);
								    }

								    $tipo_sector=$this->cfpd05->execute("SELECT
																				a.cod_presi,
																				a.cod_entidad,
																				a.cod_tipo_inst,
																				a.cod_inst,
																				".$cod_dep."
																						(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_sector=1 and x.ano=".$year.") as sector_1,
																						(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_sector=2 and x.ano=".$year.") as sector_2,
																						(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_sector=3 and x.ano=".$year.") as sector_3,
																						(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_sector=4 and x.ano=".$year.") as sector_4,
																						(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_sector=5 and x.ano=".$year.") as sector_5,
																						(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_sector=6 and x.ano=".$year.") as sector_6,
																						(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_sector=7 and x.ano=".$year.") as sector_7,
																						(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_sector=8 and x.ano=".$year.") as sector_8,
																						(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_sector=9 and x.ano=".$year.") as sector_9,
																						(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_sector=10 and x.ano=".$year.") as sector_10,
																						(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_sector=11 and x.ano=".$year.") as sector_11,
																						(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_sector=12 and x.ano=".$year.") as sector_12,
																						(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_sector=13 and x.ano=".$year.") as sector_13,
																						(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_sector=14 and x.ano=".$year.") as sector_14,
																						(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_sector=15 and x.ano=".$year.") as sector_15,
																						(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.ano=".$year.") as asignacion_total
																				 	 FROM
																				cfpd05 a
																				WHERE
																				   	".$con." and a.ano=".$year."
																				group by
																						a.cod_presi,
																						a.cod_entidad,
																						a.cod_tipo_inst,
																						a.cod_inst
																				".$gror."
																				order by
																						a.cod_presi,
																						a.cod_entidad,
																						a.cod_tipo_inst,
																						a.cod_inst
																						".$gror.";   ");

																				$rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_sector,a.deno_sector FROM v_balance_ejecucion a WHERE ". $con." and a.ano=".$year." ORDER BY ".cod_sector);
																				    foreach($rs as $l){
																						$v[]=$l[0]["cod_sector"];
																						$d[]=$l[0]["deno_sector"];
																					}


                 if(isset($v)){$sector = array_combine($v, $d); }else{ $sector = array();}

				 $parametros["titulo"][]  = "PRESUPUESTO POR SECTORES";
				 $parametros["titulo"][]  = " AÑO RECURSO: ".$year;
				 $parametros["torta"][]   = "no";

                 $variables["nombre_total"]     = "Total Presupuesto";

                 if(count($tipo_sector)!=0){
                 	    $tp=$tipo_sector[0][0];
						foreach($sector as $k=>$v){
						  	$kk[]=$k;
						  	$variables["nombre"][] = mascara2($k)." - ".$v;
						}
						  $total_presupuesto             =  $tp["asignacion_total"];
						  $variables["porcentaje_total"] = ($total_presupuesto * 100) / $total_presupuesto;
						  $variables["monto_total"]      =  $total_presupuesto;
						for($i=0;$i<count($kk);$i++){
						  $sector[$kk[$i]]           =  $tp["sector_".$kk[$i]]==null?0.00:$tp["sector_".$kk[$i]];
						  $variables["monto"][]      =  $tp["sector_".$kk[$i]]==null?0.00:$tp["sector_".$kk[$i]];
						  $variables["porcentaje"][] = ($tp["sector_".$kk[$i]]==null?0.00:$tp["sector_".$kk[$i]] * 100) / $total_presupuesto;
						}
                 }else{
                 	     $variables["porcentaje_total"] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["monto_total"]  = 0;
		                 $variables["monto"][] = 0;
		                 $variables["nombre"][] = "Sector";
                 }//fin else
                 $this->genera_grafica_1(1, $parametros, $variables);

	}//fin else if

$this->set('opcion', $var1);


}//fin function









function grafica_9($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){
				      for($minCount = 2007; $minCount < 2030; $minCount++) {
					    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
					    $this->set('anos',$anos);
				       }
				        $dato = $this->cfpd01_formulacion->findAll($this->condicionNDEP(), null, 'ano_formular ASC', null);
						foreach($dato as $ve){
							$year = $ve['cfpd01_formulacion']['ano_formular'];
						}
//				        $year = $this->ano_ejecucion();
				        $this->Session->write('ano_recurso_asignacion', $year);
				        $this->set('year', $year);
	}else if($var1==2){
                       $consolidacion= !isset($this->data['datos']['consolidacion'])?2:$this->data['datos']['consolidacion'];
                       if(isset($this->data['datos']['ano'])){           $year          = $this->data["datos"]["ano"];}

                        if(isset($consolidacion) && $this->verifica_SS(5)==1){
								        if($consolidacion==1){
								           $cod_dep="";
								           $cod_dep2="";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror="";
								           $this->set("opcion",1);
								        }else{
								           $cod_dep="a.cod_dep,";
								           $cod_dep2="x.cod_dep=a.cod_dep and";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror=",a.cod_dep";
								           $this->set("opcion",2);
								        }
								    }else{
								        $cod_dep="a.cod_dep,";
								           $cod_dep2="x.cod_dep=a.cod_dep and";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror=",a.cod_dep";
								           $this->set("opcion",2);
								    }

								        $tipo_partida=$this->cfpd05->execute("SELECT
																						a.cod_presi,
																						a.cod_entidad,
																						a.cod_tipo_inst,
																						a.cod_inst,
																						".$cod_dep."
																								(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_partida=401 and x.ano=".$year.") as partida_401,
																								(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_partida=402 and x.ano=".$year.") as partida_402,
																								(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_partida=403 and x.ano=".$year.") as partida_403,
																								(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_partida=404 and x.ano=".$year.") as partida_404,
																								(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_partida=405 and x.ano=".$year.") as partida_405,
																								(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_partida=406 and x.ano=".$year.") as partida_406,
																								(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_partida=407 and x.ano=".$year.") as partida_407,
																								(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_partida=408 and x.ano=".$year.") as partida_408,
																								(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_partida=409 and x.ano=".$year.") as partida_409,
																								(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_partida=410 and x.ano=".$year.") as partida_410,
																								(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_partida=411 and x.ano=".$year.") as partida_411,
																								(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_partida=412 and x.ano=".$year.") as partida_412,
																								(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_partida=498 and x.ano=".$year.") as partida_498,
																								(SELECT SUM(x.asignacion_anual) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.ano=".$year.") as asignacion_total
																						  FROM
																						cfpd05 a
																						WHERE
																						   	".$con." and a.ano=".$year."
																						group by
																								a.cod_presi,
																								a.cod_entidad,
																								a.cod_tipo_inst,
																								a.cod_inst
																						".$gror."
																						order by
																								a.cod_presi,
																								a.cod_entidad,
																								a.cod_tipo_inst,
																								a.cod_inst
																						".$gror.";");

																						$rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_partida,a.deno_partida FROM v_cfpd05_denominaciones a WHERE ". $con." and a.ano=".$year." ORDER BY ".cod_partida);
																						    foreach($rs as $l){
																								$v[]=$l[0]["cod_partida"];
																								$d[]=$l[0]["deno_partida"];
																							}
																							if(isset($v)){$PARTIDA = array_combine($v, $d); }else{ $PARTIDA = array();}



				 $parametros["titulo"][]  = "PRESUPUESTO POR PARTIDAS";
				 $parametros["titulo"][]  = " AÑO RECURSO: ".$year;
				 $parametros["torta"][]   = "no";

                 $variables["nombre_total"]     = "Total Presupuesto";

                 if(count($tipo_partida)!=0){
                 	    $tp=$tipo_partida[0][0];
						foreach($PARTIDA as $k=>$v){
						  	$kk[]=$k;
						  	$variables["nombre"][] = CE.".".separa_partida_de_grupo($k)." - ".$v;
						}
						  $total_presupuesto             =  $tp["asignacion_total"];
						  $variables["porcentaje_total"] = ($total_presupuesto * 100) / $total_presupuesto;
						  $variables["monto_total"]      =  $total_presupuesto;
						for($i=0;$i<count($kk);$i++){
						  $sector[$kk[$i]]           =  $tp["partida_".$kk[$i]]==null?0.00:$tp["partida_".$kk[$i]];
						  $variables["monto"][]      =  $tp["partida_".$kk[$i]]==null?0.00:$tp["partida_".$kk[$i]];
						  $variables["porcentaje"][] = ($tp["partida_".$kk[$i]]==null?0.00:$tp["partida_".$kk[$i]] * 100) / $total_presupuesto;
						}
                 }else{
                 	     $variables["porcentaje_total"] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["monto_total"]  = 0;
		                 $variables["monto"][] = 0;
		                 $variables["nombre"][] = "Partida";
                 }//fin else
                 $this->genera_grafica_1(1, $parametros, $variables);

	}//fin else if

$this->set('opcion', $var1);


}//fin function










function grafica_10($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){
				      for($minCount = 2007; $minCount < 2030; $minCount++) {
					    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
					    $this->set('anos',$anos);
				       }
				        $dato = $this->cfpd01_formulacion->findAll($this->condicionNDEP(), null, 'ano_formular ASC', null);
						foreach($dato as $ve){
							$year = $ve['cfpd01_formulacion']['ano_formular'];
						}
//				        $year = $this->ano_ejecucion();
				        $this->Session->write('ano_recurso_asignacion', $year);
				        $this->set('year', $year);
	}else if($var1==2){
                       $consolidacion= !isset($this->data['datos']['consolidacion'])?2:$this->data['datos']['consolidacion'];
                       if(isset($this->data['datos']['ano'])){           $year          = $this->data["datos"]["ano"];}
                        if($consolidacion==1){
								 $condicion = $this->SQLCA_consolidado($consolidacion);
								 $condicion .= " and ano=".$year." GROUP BY cod_presi, cod_entidad, cod_tipo_inst, cod_inst, ano";
								$fields = "SUM(gasto_inversion) as gasto_inversion, SUM(gasto_corriente) as gasto_corriente, SUM(total) as total";
							}else{
						        $condicion = $this->SQLCA_consolidado($consolidacion);
								$condicion.= " and ano=".$year."";
							    $fields = "SUM(gasto_inversion) as gasto_inversion, SUM(gasto_corriente) as gasto_corriente, SUM(total) as total";
							}
							$datos = $this->v_cfpd05_tipo_gasto2->findAll($condicion, $fields, $order = null, $limit = null, $page = null, $recursive = null);
                         foreach($datos as $row){
						 	$gasto_inversion = $row[0]['gasto_inversion'];
						 	$gasto_corriente = $row[0]['gasto_corriente'];
						 	$total = $row[0]['total'];
						 }

				 $parametros["titulo"][]  = "TIPOS DE GASTOS";
				 $parametros["titulo"][]  = " AÑO RECURSO: ".$year;
                 $variables["nombre_total"] = "Gasto Total";
                 $variables["nombre"][] = "Gasto Corriente";
                 $variables["nombre"][] = "Gasto Inversión";

                 if(isset($datos[0][0]['total'])){
		                 $variables["porcentaje_total"] = ($total * 100) / $total;
		                 $variables["porcentaje"][] = ($gasto_corriente * 100) / $total;
		                 $variables["porcentaje"][] = ($gasto_inversion * 100) / $total;
		                 $variables["monto_total"] = $total;
		                 $variables["monto"][] = $gasto_corriente;
		                 $variables["monto"][] = $gasto_inversion;
                 }else{
                 	     $variables["porcentaje_total"] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["monto_total"]  = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
                 }//fin else
                 $this->genera_grafica_1(1, $parametros, $variables);

	}//fin else if

$this->set('opcion', $var1);


}//fin function




function grafica_11($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){
				      for($minCount = 2007; $minCount < 2030; $minCount++) {
					    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
					    $this->set('anos',$anos);
				       }
				        $dato = $this->cfpd01_formulacion->findAll($this->condicionNDEP(), null, 'ano_formular ASC', null);
						foreach($dato as $ve){
							$year = $ve['cfpd01_formulacion']['ano_formular'];
						}
//				        $year = $this->ano_ejecucion();
				        $this->Session->write('ano_recurso_asignacion', $year);
				        $this->set('year', $year);
	}else if($var1==2){
                       $consolidacion= !isset($this->data['datos']['consolidacion'])?2:$this->data['datos']['consolidacion'];
                       if(isset($this->data['datos']['ano'])){           $year          = $this->data["datos"]["ano"];}

						if($year<=2011){
							$var_a = "Laee";
							$var_b = "Fides";
						}else{
							$var_a = "Fci";
							$var_b = "Mpps";
						}

                        if(isset($consolidacion) && $this->verifica_SS(5)==1){
								        if($consolidacion==1){
								           $cod_dep="";
								           $cod_dep2="";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror="";
								           $this->set("opcion",1);
								        }else{
								           $cod_dep="a.cod_dep,";
								           $cod_dep2="x.cod_dep=a.cod_dep and";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror=",a.cod_dep";
								           $this->set("opcion",2);
								        }
								    }else{
								        $cod_dep="a.cod_dep,";
								           $cod_dep2="x.cod_dep=a.cod_dep and";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror=",a.cod_dep";
								           $this->set("opcion",2);
								    }

								        $tipo_presupuesto=$this->cfpd05->execute("SELECT
																							a.cod_presi,
																							a.cod_entidad,
																							a.cod_tipo_inst,
																							a.cod_inst,
																							".$cod_dep."
																									(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.tipo_presupuesto=1 and x.ano=".$year.") as ordinario,
																									(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.tipo_presupuesto=2 and x.ano=".$year.") as coordinado,
																									(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.tipo_presupuesto=3 and x.ano=".$year.") as laee,
																									(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.tipo_presupuesto=4 and x.ano=".$year.") as fides,
																									(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.tipo_presupuesto=5 and x.ano=".$year.") as ingresos_extra,
																									(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.tipo_presupuesto=6 and x.ano=".$year.") as ingresos_propios,
																									(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.ano=".$year.") as asignacion_total
																							   FROM
																							cfpd05 a
																							WHERE
																							   	".$con." and a.ano=".$year."
																							group by
																									a.cod_presi,
																									a.cod_entidad,
																									a.cod_tipo_inst,
																									a.cod_inst
																							".$gror."
																							order by
																									a.cod_presi,
																									a.cod_entidad,
																									a.cod_tipo_inst,
																									a.cod_inst
																							".$gror.";");




				 $parametros["titulo"][]  = "TIPOS DE RECURSOS";
				 $parametros["titulo"][]  = " AÑO RECURSO: ".$year;


                 $variables["nombre_total"] = "Total Presupuesto";
                 $variables["nombre"][] = "Ordinario";
                 $variables["nombre"][] = "Coordinado";
                 $variables["nombre"][] = $var_a;
                 $variables["nombre"][] = $var_b;
                 $variables["nombre"][] = "Ingresos extraordinarios";
                 $variables["nombre"][] = "Ingresos propios";

                 if(isset($tipo_presupuesto[0][0])){
                 	    $tp               = $tipo_presupuesto[0][0];
						$ordinario         = $tp["ordinario"]==null?0.00:$tp["ordinario"];
						$coordinado        = $tp["coordinado"]==null?0.00:$tp["coordinado"];
						$laee              = $tp["laee"]==null?0.00:$tp["laee"];
						$fides             = $tp["fides"]==null?0.00:$tp["fides"];
						$ingresos_extra    = $tp["ingresos_extra"]==null?0.00:$tp["ingresos_extra"];
						$ingresos_propios  = $tp["ingresos_propios"]==null?0.00:$tp["ingresos_propios"];
						$total_presupuesto = $ordinario+$coordinado+$laee+$fides+$ingresos_extra+$ingresos_propios;

		                 $variables["porcentaje_total"] = ($total_presupuesto * 100) / $total_presupuesto;
		                 $variables["porcentaje"][] = ($ordinario * 100) / $total_presupuesto;
		                 $variables["porcentaje"][] = ($coordinado * 100) / $total_presupuesto;
		                 $variables["porcentaje"][] = ($laee * 100) / $total_presupuesto;
		                 $variables["porcentaje"][] = ($fides * 100) / $total_presupuesto;
		                 $variables["porcentaje"][] = ($ingresos_extra * 100) / $total_presupuesto;
		                 $variables["porcentaje"][] = ($ingresos_propios * 100) / $total_presupuesto;

		                 $variables["monto_total"] = $total_presupuesto;
		                 $variables["monto"][] = $ordinario;
		                 $variables["monto"][] = $coordinado;
		                 $variables["monto"][] = $laee;
		                 $variables["monto"][] = $fides;
		                 $variables["monto"][] = $ingresos_extra;
		                 $variables["monto"][] = $ingresos_propios;
                 }else{
                 	     $variables["porcentaje_total"] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["monto_total"]  = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
                 }//fin else
                 $this->genera_grafica_1(1, $parametros, $variables);

	}//fin else if

$this->set('opcion', $var1);


}//fin function










function grafica_12($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){
				      for($minCount = 2007; $minCount < 2030; $minCount++) {
					    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
					    $this->set('anos',$anos);
				       }
				        $dato = $this->cfpd01_formulacion->findAll($this->condicionNDEP(), null, 'ano_formular ASC', null);
						foreach($dato as $ve){
							$year = $ve['cfpd01_formulacion']['ano_formular'];
						}
//				        $year = $this->ano_ejecucion();
				        $this->Session->write('ano_recurso_asignacion', $year);
				        $this->set('year', $year);
	}else if($var1==2){
                       $consolidacion= !isset($this->data['datos']['consolidacion'])?2:$this->data['datos']['consolidacion'];
                       if(isset($this->data['datos']['ano'])){           $year          = $this->data["datos"]["ano"];}

                        if(isset($consolidacion) && $this->verifica_SS(5)==1){
								        if($consolidacion==1){
								           $cod_dep="";
								           $cod_dep2="";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror="";
								           $this->set("opcion",1);
								        }else{
								           $cod_dep="a.cod_dep,";
								           $cod_dep2="x.cod_dep=a.cod_dep and";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror=",a.cod_dep";
								           $this->set("opcion",2);
								        }
								    }else{
								        $cod_dep="a.cod_dep,";
								           $cod_dep2="x.cod_dep=a.cod_dep and";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror=",a.cod_dep";
								           $this->set("opcion",2);
								    }

								        $tipo_sector=$this->cfpd05->execute("SELECT
																						a.cod_presi,
																						a.cod_entidad,
																						a.cod_tipo_inst,
																						a.cod_inst,
																						".$cod_dep."
																								(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_sector=1 and x.ano=".$year.") as sector_1,
																								(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_sector=2 and x.ano=".$year.") as sector_2,
																								(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_sector=3 and x.ano=".$year.") as sector_3,
																								(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_sector=4 and x.ano=".$year.") as sector_4,
																								(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_sector=5 and x.ano=".$year.") as sector_5,
																								(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_sector=6 and x.ano=".$year.") as sector_6,
																								(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_sector=7 and x.ano=".$year.") as sector_7,
																								(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_sector=8 and x.ano=".$year.") as sector_8,
																								(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_sector=9 and x.ano=".$year.") as sector_9,
																								(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_sector=10 and x.ano=".$year.") as sector_10,
																								(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_sector=11 and x.ano=".$year.") as sector_11,
																								(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_sector=12 and x.ano=".$year.") as sector_12,
																								(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_sector=13 and x.ano=".$year.") as sector_13,
																								(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_sector=14 and x.ano=".$year.") as sector_14,
																								(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_sector=15 and x.ano=".$year.") as sector_15,
																								(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.ano=".$year.") as asignacion_total
																						 	 FROM
																						cfpd05 a
																						WHERE
																						   	".$con." and a.ano=".$year."
																						group by
																								a.cod_presi,
																								a.cod_entidad,
																								a.cod_tipo_inst,
																								a.cod_inst
																						".$gror."
																						order by
																								a.cod_presi,
																								a.cod_entidad,
																								a.cod_tipo_inst,
																								a.cod_inst
																						        ".$gror.";");

																				$rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_sector,a.deno_sector FROM v_balance_ejecucion a WHERE ". $con." and a.ano=".$year." ORDER BY ".cod_sector);
																				    foreach($rs as $l){
																						$v[]=$l[0]["cod_sector"];
																						$d[]=$l[0]["deno_sector"];
																					}


                 if(isset($v)){$sector = array_combine($v, $d); }else{ $sector = array();}

				 $parametros["titulo"][]  = "PRESUPUESTO POR SECTORES";
				 $parametros["titulo"][]  = " AÑO RECURSO: ".$year;
				 $parametros["torta"][]   = "no";

                 $variables["nombre_total"]     = "Total Presupuesto";

                 if(count($tipo_sector)!=0){
                 	    $tp=$tipo_sector[0][0];
						foreach($sector as $k=>$v){
						  	$kk[]=$k;
						  	$variables["nombre"][] = mascara2($k)." - ".$v;
						}
						  $total_presupuesto             =  $tp["asignacion_total"];
						  $variables["porcentaje_total"] = ($total_presupuesto * 100) / $total_presupuesto;
						  $variables["monto_total"]      =  $total_presupuesto;
						for($i=0;$i<count($kk);$i++){
						  $sector[$kk[$i]]           =  $tp["sector_".$kk[$i]]==null?0.00:$tp["sector_".$kk[$i]];
						  $variables["monto"][]      =  $tp["sector_".$kk[$i]]==null?0.00:$tp["sector_".$kk[$i]];
						  $variables["porcentaje"][] = ($tp["sector_".$kk[$i]]==null?0.00:$tp["sector_".$kk[$i]] * 100) / $total_presupuesto;
						}
                 }else{
                 	     $variables["porcentaje_total"] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["monto_total"]  = 0;
		                 $variables["monto"][] = 0;
		                 $variables["nombre"][] = "Sector";
                 }//fin else
                 $this->genera_grafica_1(1, $parametros, $variables);

	}//fin else if

$this->set('opcion', $var1);


}//fin function







function grafica_13($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){
				      for($minCount = 2007; $minCount < 2030; $minCount++) {
					    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
					    $this->set('anos',$anos);
				       }
				        $dato = $this->cfpd01_formulacion->findAll($this->condicionNDEP(), null, 'ano_formular ASC', null);
						foreach($dato as $ve){
							$year = $ve['cfpd01_formulacion']['ano_formular'];
						}
//				        $year = $this->ano_ejecucion();
				        $this->Session->write('ano_recurso_asignacion', $year);
				        $this->set('year', $year);
	}else if($var1==2){
                       $consolidacion= !isset($this->data['datos']['consolidacion'])?2:$this->data['datos']['consolidacion'];
                       if(isset($this->data['datos']['ano'])){           $year          = $this->data["datos"]["ano"];}

                        if(isset($consolidacion) && $this->verifica_SS(5)==1){
								        if($consolidacion==1){
								           $cod_dep="";
								           $cod_dep2="";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror="";
								           $this->set("opcion",1);
								        }else{
								           $cod_dep="a.cod_dep,";
								           $cod_dep2="x.cod_dep=a.cod_dep and";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror=",a.cod_dep";
								           $this->set("opcion",2);
								        }
								    }else{
								        $cod_dep="a.cod_dep,";
								           $cod_dep2="x.cod_dep=a.cod_dep and";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror=",a.cod_dep";
								           $this->set("opcion",2);
								    }

								        $tipo_partida=$this->cfpd05->execute("SELECT
																						a.cod_presi,
																						a.cod_entidad,
																						a.cod_tipo_inst,
																						a.cod_inst,
																						".$cod_dep."
																								(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_partida=401 and x.ano=".$year.") as partida_401,
																								(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_partida=402 and x.ano=".$year.") as partida_402,
																								(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_partida=403 and x.ano=".$year.") as partida_403,
																								(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_partida=404 and x.ano=".$year.") as partida_404,
																								(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_partida=405 and x.ano=".$year.") as partida_405,
																								(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_partida=406 and x.ano=".$year.") as partida_406,
																								(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_partida=407 and x.ano=".$year.") as partida_407,
																								(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_partida=408 and x.ano=".$year.") as partida_408,
																								(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_partida=409 and x.ano=".$year.") as partida_409,
																								(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_partida=410 and x.ano=".$year.") as partida_410,
																								(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_partida=411 and x.ano=".$year.") as partida_411,
																								(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_partida=412 and x.ano=".$year.") as partida_412,
																								(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.cod_partida=498 and x.ano=".$year.") as partida_498,
																								(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.ano=".$year.") as asignacion_total
																						  FROM
																						cfpd05 a
																						WHERE
																						   	".$con." and a.ano=".$year."
																						group by
																								a.cod_presi,
																								a.cod_entidad,
																								a.cod_tipo_inst,
																								a.cod_inst
																						".$gror."
																						order by
																								a.cod_presi,
																								a.cod_entidad,
																								a.cod_tipo_inst,
																								a.cod_inst
																						".$gror.";");

																						$rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_partida,a.deno_partida FROM v_cfpd05_denominaciones a WHERE ". $con." and a.ano=".$year." ORDER BY ".cod_partida);
																						    foreach($rs as $l){
																								$v[]=$l[0]["cod_partida"];
																								$d[]=$l[0]["deno_partida"];
																							}
																							if(isset($v)){$PARTIDA = array_combine($v, $d); }else{ $PARTIDA = array();}



				 $parametros["titulo"][]  = "PRESUPUESTO POR PARTIDAS";
				 $parametros["titulo"][]  = " AÑO RECURSO: ".$year;
				 $parametros["torta"][]   = "no";

                 $variables["nombre_total"]     = "Total Presupuesto";

                 if(count($tipo_partida)!=0){
                 	    $tp=$tipo_partida[0][0];
						foreach($PARTIDA as $k=>$v){
						  	$kk[]=$k;
						  	$variables["nombre"][] = CE.".".separa_partida_de_grupo($k)." - ".$v;
						}
						  $total_presupuesto             =  $tp["asignacion_total"];
						  $variables["porcentaje_total"] = ($total_presupuesto * 100) / $total_presupuesto;
						  $variables["monto_total"]      =  $total_presupuesto;
						for($i=0;$i<count($kk);$i++){
						  $sector[$kk[$i]]           =  $tp["partida_".$kk[$i]]==null?0.00:$tp["partida_".$kk[$i]];
						  $variables["monto"][]      =  $tp["partida_".$kk[$i]]==null?0.00:$tp["partida_".$kk[$i]];
						  $variables["porcentaje"][] = ($tp["partida_".$kk[$i]]==null?0.00:$tp["partida_".$kk[$i]] * 100) / $total_presupuesto;
						}
                 }else{
                 	     $variables["porcentaje_total"] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["monto_total"]  = 0;
		                 $variables["monto"][] = 0;
		                 $variables["nombre"][] = "Partida";
                 }//fin else
                 $this->genera_grafica_1(1, $parametros, $variables);

	}//fin else if

$this->set('opcion', $var1);


}//fin function




function grafica_14($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	$var2 = $this->data["datos"]["radio_opcion"];
	$mes = ((int) $this->data["datos"]["mes"] * 1);

	if ($var2=='1'){ // TODO
		$mes_letra = "";
	}else{ // MES
		$mes_letra = $this->mes_letra(mascara($mes, 2));
		if ($mes>1){$mes_letra="ENERO A ".$mes_letra;}
	}

	    if($var1==1){
				      for($minCount = 2007; $minCount < 2050; $minCount++) {
					    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
					    $this->set('anos',$anos);
				       }
				        $year = $this->ano_ejecucion();
				        $this->Session->write('ano_recurso_asignacion', $year);
				        $this->set('year', $year);
	}else if($var1==2){
                       $consolidacion= !isset($this->data['datos']['consolidacion'])?2:$this->data['datos']['consolidacion'];
                       if(isset($this->data['datos']['ano'])){           $year          = $this->data["datos"]["ano"];}

                       if($consolidacion==1){
						 	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." ";
						    $this->set('global', 'si');
					   }else{
							$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." and a.cod_dep = ".$this->cod_dep_consolidado()." ";
						    $this->set('global', 'no');
					   }//fin else

						$datos = $this->cfpd05->execute("SELECT

		    						  SUM(a.asignacion_anual)           as asignacion_anual,
									  SUM(a.aumento_traslado_anual)     as aumento_traslado_anual,
									  SUM(a.disminucion_traslado_anual) as disminucion_traslado_anual,
									  SUM(a.credito_adicional_anual)    as credito_adicional_anual,
									  SUM(a.rebaja_anual)               as rebaja_anual,
									  SUM(a.compromiso_anual)           as compromiso_anual,
									  SUM(a.causado_anual)              as causado_anual,
									  SUM(a.pagado_anual)               as pagado_anual,

									  SUM(a.aumento_traslado_ene)     as aumento_traslado_ene,
									  SUM(a.disminucion_traslado_ene) as disminucion_traslado_ene,
									  SUM(a.credito_adicional_ene)    as credito_adicional_ene,
									  SUM(a.rebaja_ene)               as rebaja_ene,
									  SUM(a.compromiso_ene)           as compromiso_ene,
									  SUM(a.causado_ene)              as causado_ene,
									  SUM(a.pagado_ene)               as pagado_ene,

									  SUM(a.aumento_traslado_feb)     as aumento_traslado_feb,
									  SUM(a.disminucion_traslado_feb) as disminucion_traslado_feb,
									  SUM(a.credito_adicional_feb)    as credito_adicional_feb,
									  SUM(a.rebaja_feb)               as rebaja_feb,
									  SUM(a.compromiso_feb)           as compromiso_feb,
									  SUM(a.causado_feb)              as causado_feb,
									  SUM(a.pagado_feb)               as pagado_feb,

									  SUM(a.aumento_traslado_mar)     as aumento_traslado_mar,
									  SUM(a.disminucion_traslado_mar) as disminucion_traslado_mar,
									  SUM(a.credito_adicional_mar)    as credito_adicional_mar,
									  SUM(a.rebaja_mar)               as rebaja_mar,
									  SUM(a.compromiso_mar)           as compromiso_mar,
									  SUM(a.causado_mar)              as causado_mar,
									  SUM(a.pagado_mar)               as pagado_mar,

									  SUM(a.aumento_traslado_abr)     as aumento_traslado_abr,
									  SUM(a.disminucion_traslado_abr) as disminucion_traslado_abr,
									  SUM(a.credito_adicional_abr)    as credito_adicional_abr,
									  SUM(a.rebaja_abr)               as rebaja_abr,
									  SUM(a.compromiso_abr)           as compromiso_abr,
									  SUM(a.causado_abr)              as causado_abr,
									  SUM(a.pagado_abr)               as pagado_abr,

									  SUM(a.aumento_traslado_may)     as aumento_traslado_may,
									  SUM(a.disminucion_traslado_may) as disminucion_traslado_may,
									  SUM(a.credito_adicional_may)    as credito_adicional_may,
									  SUM(a.rebaja_may)               as rebaja_may,
									  SUM(a.compromiso_may)           as compromiso_may,
									  SUM(a.causado_may)              as causado_may,
									  SUM(a.pagado_may)               as pagado_may,

									  SUM(a.aumento_traslado_jun)     as aumento_traslado_jun,
									  SUM(a.disminucion_traslado_jun) as disminucion_traslado_jun,
									  SUM(a.credito_adicional_jun)    as credito_adicional_jun,
									  SUM(a.rebaja_jun)               as rebaja_jun,
									  SUM(a.compromiso_jun)           as compromiso_jun,
									  SUM(a.causado_jun)              as causado_jun,
									  SUM(a.pagado_jun)               as pagado_jun,

									  SUM(a.aumento_traslado_jul)     as aumento_traslado_jul,
									  SUM(a.disminucion_traslado_jul) as disminucion_traslado_jul,
									  SUM(a.credito_adicional_jul)    as credito_adicional_jul,
									  SUM(a.rebaja_jul)               as rebaja_jul,
									  SUM(a.compromiso_jul)           as compromiso_jul,
									  SUM(a.causado_jul)              as causado_jul,
									  SUM(a.pagado_jul)               as pagado_jul,

									  SUM(a.aumento_traslado_ago)     as aumento_traslado_ago,
									  SUM(a.disminucion_traslado_ago) as disminucion_traslado_ago,
									  SUM(a.credito_adicional_ago)    as credito_adicional_ago,
									  SUM(a.rebaja_ago)               as rebaja_ago,
									  SUM(a.compromiso_ago)           as compromiso_ago,
									  SUM(a.causado_ago)              as causado_ago,
									  SUM(a.pagado_ago)               as pagado_ago,

									  SUM(a.aumento_traslado_sep)     as aumento_traslado_sep,
									  SUM(a.disminucion_traslado_sep) as disminucion_traslado_sep,
									  SUM(a.credito_adicional_sep)    as credito_adicional_sep,
									  SUM(a.rebaja_sep)               as rebaja_sep,
									  SUM(a.compromiso_sep)           as compromiso_sep,
									  SUM(a.causado_sep)              as causado_sep,
									  SUM(a.pagado_sep)               as pagado_sep,

									  SUM(a.aumento_traslado_oct)     as aumento_traslado_oct,
									  SUM(a.disminucion_traslado_oct) as disminucion_traslado_oct,
									  SUM(a.credito_adicional_oct)    as credito_adicional_oct,
									  SUM(a.rebaja_oct)               as rebaja_oct,
									  SUM(a.compromiso_oct)           as compromiso_oct,
									  SUM(a.causado_oct)              as causado_oct,
									  SUM(a.pagado_oct)               as pagado_oct,

									  SUM(a.aumento_traslado_nov)     as aumento_traslado_nov,
									  SUM(a.disminucion_traslado_nov) as disminucion_traslado_nov,
									  SUM(a.credito_adicional_nov)    as credito_adicional_nov,
									  SUM(a.rebaja_nov)               as rebaja_nov,
									  SUM(a.compromiso_nov)           as compromiso_nov,
									  SUM(a.causado_nov)              as causado_nov,
									  SUM(a.pagado_nov)               as pagado_nov,

									  SUM(a.aumento_traslado_dic)     as aumento_traslado_dic,
									  SUM(a.disminucion_traslado_dic) as disminucion_traslado_dic,
									  SUM(a.credito_adicional_dic)    as credito_adicional_dic,
									  SUM(a.rebaja_dic)               as rebaja_dic,
									  SUM(a.compromiso_dic)           as compromiso_dic,
									  SUM(a.causado_dic)              as causado_dic,
									  SUM(a.pagado_dic)               as pagado_dic

						 		      FROM cfpd05 a where ".$cond." and  a.ano  =  ".$year."

		                         GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano");


				 $parametros["titulo"][]  = "EJECUCIÓN ANUAL";
				 $parametros["titulo"][]  = " AÑO RECURSO: ".$year;
				 if($mes_letra != ""){
				 	$parametros["titulo"][]  = " INFORMACIÓN DE: ".$mes_letra;
				 }
                 $variables["nombre_total"] = "Asignación Ajustada";
                 $variables["nombre"][] = "Compromisos";
                 $variables["nombre"][] = "Causados";
                 $variables["nombre"][] = "Pagado";
                 $variables["nombre"][] = "Disponibilidad";
                 $variables["nombre"][] = "Deuda";

                 if(!empty($datos[0][0]['asignacion_anual']) && $datos[0][0]['asignacion_anual']!=0){

						$asignacion_anual            =  $datos[0][0]['asignacion_anual'];

                 if ($var2=='1'){ // TODO
						$aumento_traslado_anual      =  $datos[0][0]['aumento_traslado_anual'];
						$credito_adicional_anual     =  $datos[0][0]['credito_adicional_anual'];
						$disminucion_traslado_anual  =  $datos[0][0]['disminucion_traslado_anual'];
						$rebaja_anual                =  $datos[0][0]['rebaja_anual'];
						$compromiso_anual    =  $datos[0][0]['compromiso_anual'];
						$causado_anual       =  $datos[0][0]['causado_anual'];
						$pagado_anual        =  $datos[0][0]['pagado_anual'];
						$asignacion_ajustada =  (($asignacion_anual+$aumento_traslado_anual+$credito_adicional_anual)-($disminucion_traslado_anual+$rebaja_anual));
						$deuda = ($causado_anual-$pagado_anual);
						$disponibilidad =  ($asignacion_ajustada-$compromiso_anual);

                 }else{ // MES
						$aumento_traslado_ene      =  $datos[0][0]['aumento_traslado_ene'];
						$credito_adicional_ene     =  $datos[0][0]['credito_adicional_ene'];
						$disminucion_traslado_ene  =  $datos[0][0]['disminucion_traslado_ene'];
						$rebaja_ene                =  $datos[0][0]['rebaja_ene'];
						$compromiso_ene    =  $datos[0][0]['compromiso_ene'];
						$causado_ene       =  $datos[0][0]['causado_ene'];
						$pagado_ene        =  $datos[0][0]['pagado_ene'];
						$modificacion_ene =  (($aumento_traslado_ene+$credito_adicional_ene)-($disminucion_traslado_ene+$rebaja_ene));
						$deuda_ene = ($causado_ene-$pagado_ene);

						$aumento_traslado_feb      =  $datos[0][0]['aumento_traslado_feb'];
						$credito_adicional_feb     =  $datos[0][0]['credito_adicional_feb'];
						$disminucion_traslado_feb  =  $datos[0][0]['disminucion_traslado_feb'];
						$rebaja_feb                =  $datos[0][0]['rebaja_feb'];
						$compromiso_feb    =  $datos[0][0]['compromiso_feb'];
						$causado_feb       =  $datos[0][0]['causado_feb'];
						$pagado_feb        =  $datos[0][0]['pagado_feb'];
						$modificacion_feb =  (($aumento_traslado_feb+$credito_adicional_feb)-($disminucion_traslado_feb+$rebaja_feb));
						$deuda_feb = ($causado_feb-$pagado_feb);

						$aumento_traslado_mar      =  $datos[0][0]['aumento_traslado_mar'];
						$credito_adicional_mar     =  $datos[0][0]['credito_adicional_mar'];
						$disminucion_traslado_mar  =  $datos[0][0]['disminucion_traslado_mar'];
						$rebaja_mar                =  $datos[0][0]['rebaja_mar'];
						$compromiso_mar    =  $datos[0][0]['compromiso_mar'];
						$causado_mar       =  $datos[0][0]['causado_mar'];
						$pagado_mar        =  $datos[0][0]['pagado_mar'];
						$modificacion_mar =  (($aumento_traslado_mar+$credito_adicional_mar)-($disminucion_traslado_mar+$rebaja_mar));
						$deuda_mar = ($causado_mar-$pagado_mar);

						$aumento_traslado_abr      =  $datos[0][0]['aumento_traslado_abr'];
						$credito_adicional_abr     =  $datos[0][0]['credito_adicional_abr'];
						$disminucion_traslado_abr  =  $datos[0][0]['disminucion_traslado_abr'];
						$rebaja_abr                =  $datos[0][0]['rebaja_abr'];
						$compromiso_abr    =  $datos[0][0]['compromiso_abr'];
						$causado_abr       =  $datos[0][0]['causado_abr'];
						$pagado_abr        =  $datos[0][0]['pagado_abr'];
						$modificacion_abr =  (($aumento_traslado_abr+$credito_adicional_abr)-($disminucion_traslado_abr+$rebaja_abr));
						$deuda_abr = ($causado_abr-$pagado_abr);

						$aumento_traslado_may      =  $datos[0][0]['aumento_traslado_may'];
						$credito_adicional_may     =  $datos[0][0]['credito_adicional_may'];
						$disminucion_traslado_may  =  $datos[0][0]['disminucion_traslado_may'];
						$rebaja_may                =  $datos[0][0]['rebaja_may'];
						$compromiso_may    =  $datos[0][0]['compromiso_may'];
						$causado_may       =  $datos[0][0]['causado_may'];
						$pagado_may        =  $datos[0][0]['pagado_may'];
						$modificacion_may =  (($aumento_traslado_may+$credito_adicional_may)-($disminucion_traslado_may+$rebaja_may));
						$deuda_may = ($causado_may-$pagado_may);

						$aumento_traslado_jun      =  $datos[0][0]['aumento_traslado_jun'];
						$credito_adicional_jun     =  $datos[0][0]['credito_adicional_jun'];
						$disminucion_traslado_jun  =  $datos[0][0]['disminucion_traslado_jun'];
						$rebaja_jun                =  $datos[0][0]['rebaja_jun'];
						$compromiso_jun    =  $datos[0][0]['compromiso_jun'];
						$causado_jun       =  $datos[0][0]['causado_jun'];
						$pagado_jun        =  $datos[0][0]['pagado_jun'];
						$modificacion_jun =  (($aumento_traslado_jun+$credito_adicional_jun)-($disminucion_traslado_jun+$rebaja_jun));
						$deuda_jun = ($causado_jun-$pagado_jun);

						$aumento_traslado_jul      =  $datos[0][0]['aumento_traslado_jul'];
						$credito_adicional_jul     =  $datos[0][0]['credito_adicional_jul'];
						$disminucion_traslado_jul  =  $datos[0][0]['disminucion_traslado_jul'];
						$rebaja_jul                =  $datos[0][0]['rebaja_jul'];
						$compromiso_jul    =  $datos[0][0]['compromiso_jul'];
						$causado_jul       =  $datos[0][0]['causado_jul'];
						$pagado_jul        =  $datos[0][0]['pagado_jul'];
						$modificacion_jul =  (($aumento_traslado_jul+$credito_adicional_jul)-($disminucion_traslado_jul+$rebaja_jul));
						$deuda_jul = ($causado_jul-$pagado_jul);

						$aumento_traslado_ago      =  $datos[0][0]['aumento_traslado_ago'];
						$credito_adicional_ago     =  $datos[0][0]['credito_adicional_ago'];
						$disminucion_traslado_ago  =  $datos[0][0]['disminucion_traslado_ago'];
						$rebaja_ago                =  $datos[0][0]['rebaja_ago'];
						$compromiso_ago    =  $datos[0][0]['compromiso_ago'];
						$causado_ago       =  $datos[0][0]['causado_ago'];
						$pagado_ago        =  $datos[0][0]['pagado_ago'];
						$modificacion_ago =  (($aumento_traslado_ago+$credito_adicional_ago)-($disminucion_traslado_ago+$rebaja_ago));
						$deuda_ago = ($causado_ago-$pagado_ago);

						$aumento_traslado_sep      =  $datos[0][0]['aumento_traslado_sep'];
						$credito_adicional_sep     =  $datos[0][0]['credito_adicional_sep'];
						$disminucion_traslado_sep  =  $datos[0][0]['disminucion_traslado_sep'];
						$rebaja_sep                =  $datos[0][0]['rebaja_sep'];
						$compromiso_sep    =  $datos[0][0]['compromiso_sep'];
						$causado_sep       =  $datos[0][0]['causado_sep'];
						$pagado_sep        =  $datos[0][0]['pagado_sep'];
						$modificacion_sep =  (($aumento_traslado_sep+$credito_adicional_sep)-($disminucion_traslado_sep+$rebaja_sep));
						$deuda_sep = ($causado_sep-$pagado_sep);

						$aumento_traslado_oct      =  $datos[0][0]['aumento_traslado_oct'];
						$credito_adicional_oct     =  $datos[0][0]['credito_adicional_oct'];
						$disminucion_traslado_oct  =  $datos[0][0]['disminucion_traslado_oct'];
						$rebaja_oct                =  $datos[0][0]['rebaja_oct'];
						$compromiso_oct    =  $datos[0][0]['compromiso_oct'];
						$causado_oct       =  $datos[0][0]['causado_oct'];
						$pagado_oct        =  $datos[0][0]['pagado_oct'];
						$modificacion_oct =  (($aumento_traslado_oct+$credito_adicional_oct)-($disminucion_traslado_oct+$rebaja_oct));
						$deuda_oct = ($causado_oct-$pagado_oct);

						$aumento_traslado_nov      =  $datos[0][0]['aumento_traslado_nov'];
						$credito_adicional_nov     =  $datos[0][0]['credito_adicional_nov'];
						$disminucion_traslado_nov  =  $datos[0][0]['disminucion_traslado_nov'];
						$rebaja_nov                =  $datos[0][0]['rebaja_nov'];
						$compromiso_nov    =  $datos[0][0]['compromiso_nov'];
						$causado_nov       =  $datos[0][0]['causado_nov'];
						$pagado_nov        =  $datos[0][0]['pagado_nov'];
						$modificacion_nov =  (($aumento_traslado_nov+$credito_adicional_nov)-($disminucion_traslado_nov+$rebaja_nov));
						$deuda_nov = ($causado_nov-$pagado_nov);

						$aumento_traslado_dic      =  $datos[0][0]['aumento_traslado_dic'];
						$credito_adicional_dic     =  $datos[0][0]['credito_adicional_dic'];
						$disminucion_traslado_dic  =  $datos[0][0]['disminucion_traslado_dic'];
						$rebaja_dic                =  $datos[0][0]['rebaja_dic'];
						$compromiso_dic    =  $datos[0][0]['compromiso_dic'];
						$causado_dic       =  $datos[0][0]['causado_dic'];
						$pagado_dic        =  $datos[0][0]['pagado_dic'];
						$modificacion_dic =  (($aumento_traslado_dic+$credito_adicional_dic)-($disminucion_traslado_dic+$rebaja_dic));
						$deuda_dic = ($causado_dic-$pagado_dic);


						if ($mes==1){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene);
							$compromiso_anual    = $compromiso_ene;
							$causado_anual       = $causado_ene;
							$pagado_anual        = $pagado_ene;
							$disponibilidad      = (($asignacion_anual+$modificacion_ene)-$compromiso_ene);
							$deuda               = $deuda_ene;
						}else if ($mes==2){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb);
							$causado_anual       = ($causado_ene+$causado_feb);
							$pagado_anual        = ($pagado_ene+$pagado_feb);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb)-($compromiso_ene+$compromiso_feb));
							$deuda               = ($deuda_ene+$deuda_feb);
						}else if ($mes==3){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar)-($compromiso_ene+$compromiso_feb+$compromiso_mar));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar);
						}else if ($mes==4){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr);
						}else if ($mes==5){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may);
						}else if ($mes==6){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun);
						}else if ($mes==7){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun+$causado_jul);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun+$pagado_jul);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun+$deuda_jul);
						}else if ($mes==8){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun+$causado_jul+$causado_ago);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun+$pagado_jul+$pagado_ago);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun+$deuda_jul+$deuda_ago);
						}else if ($mes==9){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun+$causado_jul+$causado_ago+$causado_sep);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun+$pagado_jul+$pagado_ago+$pagado_sep);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun+$deuda_jul+$deuda_ago+$deuda_sep);
						}else if ($mes==10){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep+$modificacion_oct);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep+$compromiso_oct);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun+$causado_jul+$causado_ago+$causado_sep+$causado_oct);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun+$pagado_jul+$pagado_ago+$pagado_sep+$pagado_oct);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep+$modificacion_oct)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep+$compromiso_oct));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun+$deuda_jul+$deuda_ago+$deuda_sep+$deuda_oct);
						}else if ($mes==11){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep+$modificacion_oct+$modificacion_nov);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep+$compromiso_oct+$compromiso_nov);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun+$causado_jul+$causado_ago+$causado_sep+$causado_oct+$causado_nov);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun+$pagado_jul+$pagado_ago+$pagado_sep+$pagado_oct+$pagado_nov);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep+$modificacion_oct+$modificacion_nov)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep+$compromiso_oct+$compromiso_nov));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun+$deuda_jul+$deuda_ago+$deuda_sep+$deuda_oct+$deuda_nov);
						}else if ($mes==12){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep+$modificacion_oct+$modificacion_nov+$modificacion_dic);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep+$compromiso_oct+$compromiso_nov+$compromiso_dic);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun+$causado_jul+$causado_ago+$causado_sep+$causado_oct+$causado_nov+$causado_dic);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun+$pagado_jul+$pagado_ago+$pagado_sep+$pagado_oct+$pagado_nov+$pagado_dic);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep+$modificacion_oct+$modificacion_nov+$modificacion_dic)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep+$compromiso_oct+$compromiso_nov+$compromiso_dic));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun+$deuda_jul+$deuda_ago+$deuda_sep+$deuda_oct+$deuda_nov+$deuda_dic);
						}
               }



		                 $variables["porcentaje_total"] = ($asignacion_ajustada * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($compromiso_anual * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($causado_anual * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($pagado_anual * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($disponibilidad * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($deuda * 100) / $asignacion_ajustada;
		                 $variables["monto_total"] = $asignacion_ajustada;
		                 $variables["monto"][] = $compromiso_anual;
		                 $variables["monto"][] = $causado_anual;
		                 $variables["monto"][] = $pagado_anual;
		                 $variables["monto"][] = $disponibilidad;
		                 $variables["monto"][] = $deuda;
                 }else{
                 	     $variables["porcentaje_total"] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["monto_total"]  = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
                 }//fin else
                 $this->genera_grafica_1(1, $parametros, $variables);

	}//fin else if

$this->set('opcion', $var1);


}//fin function








function grafica_15($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	$var2 = $this->data["datos"]["radio_opcion"];
	$mes = ((int) $this->data["datos"]["mes"] * 1);

	if ($var2=='1'){ // TODO
		$mes_letra = "";
	}else{ // MES
		$mes_letra = $this->mes_letra(mascara($mes, 2));
		if ($mes>1){$mes_letra="ENERO A ".$mes_letra;}
	}


	    if($var1==1){
				      for($minCount = 2007; $minCount < 2050; $minCount++) {
					    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
					    $this->set('anos',$anos);
				       }
				        $year = $this->ano_ejecucion();
				        $this->Session->write('ano_recurso_asignacion', $year);
				        $this->set('year', $year);
	}else if($var1==2){
                       $consolidacion= !isset($this->data['datos']['consolidacion'])?2:$this->data['datos']['consolidacion'];
                       if(isset($this->data['datos']['ano'])){           $year          = $this->data["datos"]["ano"];}
                       if(isset($this->data["datos"]["tipo_gasto"])){$tipo_gasto=$this->data["datos"]["tipo_gasto"];}else{$tipo_gasto=3;}
                       if($consolidacion==1){
						 	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." ";
						    $this->set('global', 'si');
					   }else{
							$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." and a.cod_dep = ".$this->cod_dep_consolidado()." ";
						    $this->set('global', 'no');
					   }//fin else

					         if($tipo_gasto==1){
					         $tipo_gasto_sql = " and cod_tipo_gasto = 2 ";
					   }else if($tipo_gasto==3){
					         $tipo_gasto_sql = " ";
					   }else{
					         $tipo_gasto_sql = "  and cod_tipo_gasto != 2 ";
					   }//fin if

                       $datos = $this->cfpd05->execute("SELECT

		    						  SUM(a.asignacion_anual)           as asignacion_anual,
									  SUM(a.aumento_traslado_anual)     as aumento_traslado_anual,
									  SUM(a.disminucion_traslado_anual) as disminucion_traslado_anual,
									  SUM(a.credito_adicional_anual)    as credito_adicional_anual,
									  SUM(a.rebaja_anual)               as rebaja_anual,
									  SUM(a.compromiso_anual)           as compromiso_anual,
									  SUM(a.causado_anual)              as causado_anual,
									  SUM(a.pagado_anual)               as pagado_anual,

									  SUM(a.aumento_traslado_ene)     as aumento_traslado_ene,
									  SUM(a.disminucion_traslado_ene) as disminucion_traslado_ene,
									  SUM(a.credito_adicional_ene)    as credito_adicional_ene,
									  SUM(a.rebaja_ene)               as rebaja_ene,
									  SUM(a.compromiso_ene)           as compromiso_ene,
									  SUM(a.causado_ene)              as causado_ene,
									  SUM(a.pagado_ene)               as pagado_ene,

									  SUM(a.aumento_traslado_feb)     as aumento_traslado_feb,
									  SUM(a.disminucion_traslado_feb) as disminucion_traslado_feb,
									  SUM(a.credito_adicional_feb)    as credito_adicional_feb,
									  SUM(a.rebaja_feb)               as rebaja_feb,
									  SUM(a.compromiso_feb)           as compromiso_feb,
									  SUM(a.causado_feb)              as causado_feb,
									  SUM(a.pagado_feb)               as pagado_feb,

									  SUM(a.aumento_traslado_mar)     as aumento_traslado_mar,
									  SUM(a.disminucion_traslado_mar) as disminucion_traslado_mar,
									  SUM(a.credito_adicional_mar)    as credito_adicional_mar,
									  SUM(a.rebaja_mar)               as rebaja_mar,
									  SUM(a.compromiso_mar)           as compromiso_mar,
									  SUM(a.causado_mar)              as causado_mar,
									  SUM(a.pagado_mar)               as pagado_mar,

									  SUM(a.aumento_traslado_abr)     as aumento_traslado_abr,
									  SUM(a.disminucion_traslado_abr) as disminucion_traslado_abr,
									  SUM(a.credito_adicional_abr)    as credito_adicional_abr,
									  SUM(a.rebaja_abr)               as rebaja_abr,
									  SUM(a.compromiso_abr)           as compromiso_abr,
									  SUM(a.causado_abr)              as causado_abr,
									  SUM(a.pagado_abr)               as pagado_abr,

									  SUM(a.aumento_traslado_may)     as aumento_traslado_may,
									  SUM(a.disminucion_traslado_may) as disminucion_traslado_may,
									  SUM(a.credito_adicional_may)    as credito_adicional_may,
									  SUM(a.rebaja_may)               as rebaja_may,
									  SUM(a.compromiso_may)           as compromiso_may,
									  SUM(a.causado_may)              as causado_may,
									  SUM(a.pagado_may)               as pagado_may,

									  SUM(a.aumento_traslado_jun)     as aumento_traslado_jun,
									  SUM(a.disminucion_traslado_jun) as disminucion_traslado_jun,
									  SUM(a.credito_adicional_jun)    as credito_adicional_jun,
									  SUM(a.rebaja_jun)               as rebaja_jun,
									  SUM(a.compromiso_jun)           as compromiso_jun,
									  SUM(a.causado_jun)              as causado_jun,
									  SUM(a.pagado_jun)               as pagado_jun,

									  SUM(a.aumento_traslado_jul)     as aumento_traslado_jul,
									  SUM(a.disminucion_traslado_jul) as disminucion_traslado_jul,
									  SUM(a.credito_adicional_jul)    as credito_adicional_jul,
									  SUM(a.rebaja_jul)               as rebaja_jul,
									  SUM(a.compromiso_jul)           as compromiso_jul,
									  SUM(a.causado_jul)              as causado_jul,
									  SUM(a.pagado_jul)               as pagado_jul,

									  SUM(a.aumento_traslado_ago)     as aumento_traslado_ago,
									  SUM(a.disminucion_traslado_ago) as disminucion_traslado_ago,
									  SUM(a.credito_adicional_ago)    as credito_adicional_ago,
									  SUM(a.rebaja_ago)               as rebaja_ago,
									  SUM(a.compromiso_ago)           as compromiso_ago,
									  SUM(a.causado_ago)              as causado_ago,
									  SUM(a.pagado_ago)               as pagado_ago,

									  SUM(a.aumento_traslado_sep)     as aumento_traslado_sep,
									  SUM(a.disminucion_traslado_sep) as disminucion_traslado_sep,
									  SUM(a.credito_adicional_sep)    as credito_adicional_sep,
									  SUM(a.rebaja_sep)               as rebaja_sep,
									  SUM(a.compromiso_sep)           as compromiso_sep,
									  SUM(a.causado_sep)              as causado_sep,
									  SUM(a.pagado_sep)               as pagado_sep,

									  SUM(a.aumento_traslado_oct)     as aumento_traslado_oct,
									  SUM(a.disminucion_traslado_oct) as disminucion_traslado_oct,
									  SUM(a.credito_adicional_oct)    as credito_adicional_oct,
									  SUM(a.rebaja_oct)               as rebaja_oct,
									  SUM(a.compromiso_oct)           as compromiso_oct,
									  SUM(a.causado_oct)              as causado_oct,
									  SUM(a.pagado_oct)               as pagado_oct,

									  SUM(a.aumento_traslado_nov)     as aumento_traslado_nov,
									  SUM(a.disminucion_traslado_nov) as disminucion_traslado_nov,
									  SUM(a.credito_adicional_nov)    as credito_adicional_nov,
									  SUM(a.rebaja_nov)               as rebaja_nov,
									  SUM(a.compromiso_nov)           as compromiso_nov,
									  SUM(a.causado_nov)              as causado_nov,
									  SUM(a.pagado_nov)               as pagado_nov,

									  SUM(a.aumento_traslado_dic)     as aumento_traslado_dic,
									  SUM(a.disminucion_traslado_dic) as disminucion_traslado_dic,
									  SUM(a.credito_adicional_dic)    as credito_adicional_dic,
									  SUM(a.rebaja_dic)               as rebaja_dic,
									  SUM(a.compromiso_dic)           as compromiso_dic,
									  SUM(a.causado_dic)              as causado_dic,
									  SUM(a.pagado_dic)               as pagado_dic

						 		      FROM cfpd05 a where ".$cond." and  a.ano  =  ".$year."  ".$tipo_gasto_sql."

		                         GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano");

		                               if($tipo_gasto==1){  $opcion1_aux = "TIPO DEL GASTO: Capital,";
								 }else if($tipo_gasto==2){  $opcion1_aux = "TIPO DEL GASTO: Corriente,";
								 }else if($tipo_gasto==3){  $opcion1_aux = ""; }

				 $parametros["titulo"][]  = "TIPOS DE GASTOS";
				 $parametros["titulo"][]  = $opcion1_aux." AÑO RECURSO: ".$year;

			if($mes_letra != ""){
				 $parametros["titulo"][]  = " INFORMACIÓN DE: ".$mes_letra;
			}

                 $variables["nombre_total"] = "Asignación Ajustada";
                 $variables["nombre"][] = "Compromisos";
                 $variables["nombre"][] = "Causados";
                 $variables["nombre"][] = "Pagado";
                 $variables["nombre"][] = "Disponibilidad";
                 $variables["nombre"][] = "Deuda";

                        $asignacion_anual            =  $datos[0][0]['asignacion_anual'];

          if(!empty($datos[0][0]['asignacion_anual']) && $datos[0][0]['asignacion_anual']!=0){

				 if ($var2=='1'){ // TODO
						$aumento_traslado_anual      =  $datos[0][0]['aumento_traslado_anual'];
						$credito_adicional_anual     =  $datos[0][0]['credito_adicional_anual'];
						$disminucion_traslado_anual  =  $datos[0][0]['disminucion_traslado_anual'];
						$rebaja_anual                =  $datos[0][0]['rebaja_anual'];
						$compromiso_anual    =  $datos[0][0]['compromiso_anual'];
						$causado_anual       =  $datos[0][0]['causado_anual'];
						$pagado_anual        =  $datos[0][0]['pagado_anual'];
						$asignacion_ajustada =  (($asignacion_anual+$aumento_traslado_anual+$credito_adicional_anual)-($disminucion_traslado_anual+$rebaja_anual));
						$deuda = ($causado_anual-$pagado_anual);
						$disponibilidad =  ($asignacion_ajustada-$compromiso_anual);

                 }else{ // MES
						$aumento_traslado_ene      =  $datos[0][0]['aumento_traslado_ene'];
						$credito_adicional_ene     =  $datos[0][0]['credito_adicional_ene'];
						$disminucion_traslado_ene  =  $datos[0][0]['disminucion_traslado_ene'];
						$rebaja_ene                =  $datos[0][0]['rebaja_ene'];
						$compromiso_ene    =  $datos[0][0]['compromiso_ene'];
						$causado_ene       =  $datos[0][0]['causado_ene'];
						$pagado_ene        =  $datos[0][0]['pagado_ene'];
						$modificacion_ene =  (($aumento_traslado_ene+$credito_adicional_ene)-($disminucion_traslado_ene+$rebaja_ene));
						$deuda_ene = ($causado_ene-$pagado_ene);

						$aumento_traslado_feb      =  $datos[0][0]['aumento_traslado_feb'];
						$credito_adicional_feb     =  $datos[0][0]['credito_adicional_feb'];
						$disminucion_traslado_feb  =  $datos[0][0]['disminucion_traslado_feb'];
						$rebaja_feb                =  $datos[0][0]['rebaja_feb'];
						$compromiso_feb    =  $datos[0][0]['compromiso_feb'];
						$causado_feb       =  $datos[0][0]['causado_feb'];
						$pagado_feb        =  $datos[0][0]['pagado_feb'];
						$modificacion_feb =  (($aumento_traslado_feb+$credito_adicional_feb)-($disminucion_traslado_feb+$rebaja_feb));
						$deuda_feb = ($causado_feb-$pagado_feb);

						$aumento_traslado_mar      =  $datos[0][0]['aumento_traslado_mar'];
						$credito_adicional_mar     =  $datos[0][0]['credito_adicional_mar'];
						$disminucion_traslado_mar  =  $datos[0][0]['disminucion_traslado_mar'];
						$rebaja_mar                =  $datos[0][0]['rebaja_mar'];
						$compromiso_mar    =  $datos[0][0]['compromiso_mar'];
						$causado_mar       =  $datos[0][0]['causado_mar'];
						$pagado_mar        =  $datos[0][0]['pagado_mar'];
						$modificacion_mar =  (($aumento_traslado_mar+$credito_adicional_mar)-($disminucion_traslado_mar+$rebaja_mar));
						$deuda_mar = ($causado_mar-$pagado_mar);

						$aumento_traslado_abr      =  $datos[0][0]['aumento_traslado_abr'];
						$credito_adicional_abr     =  $datos[0][0]['credito_adicional_abr'];
						$disminucion_traslado_abr  =  $datos[0][0]['disminucion_traslado_abr'];
						$rebaja_abr                =  $datos[0][0]['rebaja_abr'];
						$compromiso_abr    =  $datos[0][0]['compromiso_abr'];
						$causado_abr       =  $datos[0][0]['causado_abr'];
						$pagado_abr        =  $datos[0][0]['pagado_abr'];
						$modificacion_abr =  (($aumento_traslado_abr+$credito_adicional_abr)-($disminucion_traslado_abr+$rebaja_abr));
						$deuda_abr = ($causado_abr-$pagado_abr);

						$aumento_traslado_may      =  $datos[0][0]['aumento_traslado_may'];
						$credito_adicional_may     =  $datos[0][0]['credito_adicional_may'];
						$disminucion_traslado_may  =  $datos[0][0]['disminucion_traslado_may'];
						$rebaja_may                =  $datos[0][0]['rebaja_may'];
						$compromiso_may    =  $datos[0][0]['compromiso_may'];
						$causado_may       =  $datos[0][0]['causado_may'];
						$pagado_may        =  $datos[0][0]['pagado_may'];
						$modificacion_may =  (($aumento_traslado_may+$credito_adicional_may)-($disminucion_traslado_may+$rebaja_may));
						$deuda_may = ($causado_may-$pagado_may);

						$aumento_traslado_jun      =  $datos[0][0]['aumento_traslado_jun'];
						$credito_adicional_jun     =  $datos[0][0]['credito_adicional_jun'];
						$disminucion_traslado_jun  =  $datos[0][0]['disminucion_traslado_jun'];
						$rebaja_jun                =  $datos[0][0]['rebaja_jun'];
						$compromiso_jun    =  $datos[0][0]['compromiso_jun'];
						$causado_jun       =  $datos[0][0]['causado_jun'];
						$pagado_jun        =  $datos[0][0]['pagado_jun'];
						$modificacion_jun =  (($aumento_traslado_jun+$credito_adicional_jun)-($disminucion_traslado_jun+$rebaja_jun));
						$deuda_jun = ($causado_jun-$pagado_jun);

						$aumento_traslado_jul      =  $datos[0][0]['aumento_traslado_jul'];
						$credito_adicional_jul     =  $datos[0][0]['credito_adicional_jul'];
						$disminucion_traslado_jul  =  $datos[0][0]['disminucion_traslado_jul'];
						$rebaja_jul                =  $datos[0][0]['rebaja_jul'];
						$compromiso_jul    =  $datos[0][0]['compromiso_jul'];
						$causado_jul       =  $datos[0][0]['causado_jul'];
						$pagado_jul        =  $datos[0][0]['pagado_jul'];
						$modificacion_jul =  (($aumento_traslado_jul+$credito_adicional_jul)-($disminucion_traslado_jul+$rebaja_jul));
						$deuda_jul = ($causado_jul-$pagado_jul);

						$aumento_traslado_ago      =  $datos[0][0]['aumento_traslado_ago'];
						$credito_adicional_ago     =  $datos[0][0]['credito_adicional_ago'];
						$disminucion_traslado_ago  =  $datos[0][0]['disminucion_traslado_ago'];
						$rebaja_ago                =  $datos[0][0]['rebaja_ago'];
						$compromiso_ago    =  $datos[0][0]['compromiso_ago'];
						$causado_ago       =  $datos[0][0]['causado_ago'];
						$pagado_ago        =  $datos[0][0]['pagado_ago'];
						$modificacion_ago =  (($aumento_traslado_ago+$credito_adicional_ago)-($disminucion_traslado_ago+$rebaja_ago));
						$deuda_ago = ($causado_ago-$pagado_ago);

						$aumento_traslado_sep      =  $datos[0][0]['aumento_traslado_sep'];
						$credito_adicional_sep     =  $datos[0][0]['credito_adicional_sep'];
						$disminucion_traslado_sep  =  $datos[0][0]['disminucion_traslado_sep'];
						$rebaja_sep                =  $datos[0][0]['rebaja_sep'];
						$compromiso_sep    =  $datos[0][0]['compromiso_sep'];
						$causado_sep       =  $datos[0][0]['causado_sep'];
						$pagado_sep        =  $datos[0][0]['pagado_sep'];
						$modificacion_sep =  (($aumento_traslado_sep+$credito_adicional_sep)-($disminucion_traslado_sep+$rebaja_sep));
						$deuda_sep = ($causado_sep-$pagado_sep);

						$aumento_traslado_oct      =  $datos[0][0]['aumento_traslado_oct'];
						$credito_adicional_oct     =  $datos[0][0]['credito_adicional_oct'];
						$disminucion_traslado_oct  =  $datos[0][0]['disminucion_traslado_oct'];
						$rebaja_oct                =  $datos[0][0]['rebaja_oct'];
						$compromiso_oct    =  $datos[0][0]['compromiso_oct'];
						$causado_oct       =  $datos[0][0]['causado_oct'];
						$pagado_oct        =  $datos[0][0]['pagado_oct'];
						$modificacion_oct =  (($aumento_traslado_oct+$credito_adicional_oct)-($disminucion_traslado_oct+$rebaja_oct));
						$deuda_oct = ($causado_oct-$pagado_oct);

						$aumento_traslado_nov      =  $datos[0][0]['aumento_traslado_nov'];
						$credito_adicional_nov     =  $datos[0][0]['credito_adicional_nov'];
						$disminucion_traslado_nov  =  $datos[0][0]['disminucion_traslado_nov'];
						$rebaja_nov                =  $datos[0][0]['rebaja_nov'];
						$compromiso_nov    =  $datos[0][0]['compromiso_nov'];
						$causado_nov       =  $datos[0][0]['causado_nov'];
						$pagado_nov        =  $datos[0][0]['pagado_nov'];
						$modificacion_nov =  (($aumento_traslado_nov+$credito_adicional_nov)-($disminucion_traslado_nov+$rebaja_nov));
						$deuda_nov = ($causado_nov-$pagado_nov);

						$aumento_traslado_dic      =  $datos[0][0]['aumento_traslado_dic'];
						$credito_adicional_dic     =  $datos[0][0]['credito_adicional_dic'];
						$disminucion_traslado_dic  =  $datos[0][0]['disminucion_traslado_dic'];
						$rebaja_dic                =  $datos[0][0]['rebaja_dic'];
						$compromiso_dic    =  $datos[0][0]['compromiso_dic'];
						$causado_dic       =  $datos[0][0]['causado_dic'];
						$pagado_dic        =  $datos[0][0]['pagado_dic'];
						$modificacion_dic =  (($aumento_traslado_dic+$credito_adicional_dic)-($disminucion_traslado_dic+$rebaja_dic));
						$deuda_dic = ($causado_dic-$pagado_dic);


						if ($mes==1){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene);
							$compromiso_anual    = $compromiso_ene;
							$causado_anual       = $causado_ene;
							$pagado_anual        = $pagado_ene;
							$disponibilidad      = (($asignacion_anual+$modificacion_ene)-$compromiso_ene);
							$deuda               = $deuda_ene;
						}else if ($mes==2){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb);
							$causado_anual       = ($causado_ene+$causado_feb);
							$pagado_anual        = ($pagado_ene+$pagado_feb);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb)-($compromiso_ene+$compromiso_feb));
							$deuda               = ($deuda_ene+$deuda_feb);
						}else if ($mes==3){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar)-($compromiso_ene+$compromiso_feb+$compromiso_mar));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar);
						}else if ($mes==4){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr);
						}else if ($mes==5){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may);
						}else if ($mes==6){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun);
						}else if ($mes==7){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun+$causado_jul);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun+$pagado_jul);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun+$deuda_jul);
						}else if ($mes==8){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun+$causado_jul+$causado_ago);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun+$pagado_jul+$pagado_ago);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun+$deuda_jul+$deuda_ago);
						}else if ($mes==9){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun+$causado_jul+$causado_ago+$causado_sep);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun+$pagado_jul+$pagado_ago+$pagado_sep);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun+$deuda_jul+$deuda_ago+$deuda_sep);
						}else if ($mes==10){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep+$modificacion_oct);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep+$compromiso_oct);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun+$causado_jul+$causado_ago+$causado_sep+$causado_oct);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun+$pagado_jul+$pagado_ago+$pagado_sep+$pagado_oct);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep+$modificacion_oct)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep+$compromiso_oct));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun+$deuda_jul+$deuda_ago+$deuda_sep+$deuda_oct);
						}else if ($mes==11){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep+$modificacion_oct+$modificacion_nov);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep+$compromiso_oct+$compromiso_nov);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun+$causado_jul+$causado_ago+$causado_sep+$causado_oct+$causado_nov);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun+$pagado_jul+$pagado_ago+$pagado_sep+$pagado_oct+$pagado_nov);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep+$modificacion_oct+$modificacion_nov)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep+$compromiso_oct+$compromiso_nov));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun+$deuda_jul+$deuda_ago+$deuda_sep+$deuda_oct+$deuda_nov);
						}else if ($mes==12){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep+$modificacion_oct+$modificacion_nov+$modificacion_dic);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep+$compromiso_oct+$compromiso_nov+$compromiso_dic);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun+$causado_jul+$causado_ago+$causado_sep+$causado_oct+$causado_nov+$causado_dic);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun+$pagado_jul+$pagado_ago+$pagado_sep+$pagado_oct+$pagado_nov+$pagado_dic);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep+$modificacion_oct+$modificacion_nov+$modificacion_dic)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep+$compromiso_oct+$compromiso_nov+$compromiso_dic));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun+$deuda_jul+$deuda_ago+$deuda_sep+$deuda_oct+$deuda_nov+$deuda_dic);
						}
               }

		                 $variables["porcentaje_total"] = ($asignacion_ajustada * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($compromiso_anual * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($causado_anual * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($pagado_anual * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($disponibilidad * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($deuda * 100) / $asignacion_ajustada;
		                 $variables["monto_total"] = $asignacion_ajustada;
		                 $variables["monto"][] = $compromiso_anual;
		                 $variables["monto"][] = $causado_anual;
		                 $variables["monto"][] = $pagado_anual;
		                 $variables["monto"][] = $disponibilidad;
		                 $variables["monto"][] = $deuda;
                 }else{
                 	     $variables["porcentaje_total"] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["monto_total"]  = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
                 }//fin else
                 $this->genera_grafica_1(1, $parametros, $variables);

	}//fin else if

$this->set('opcion', $var1);


}//fin function
















function grafica_16($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	$var2 = $this->data["datos"]["radio_opcion"];
	$mes = ((int) $this->data["datos"]["mes"] * 1);

	if ($var2=='1'){ // TODO
		$mes_letra = "";
	}else{ // MES
		$mes_letra = $this->mes_letra(mascara($mes, 2));
		if ($mes>1){$mes_letra="ENERO A ".$mes_letra;}
	}

	    if($var1==1){
				      for($minCount = 2007; $minCount < 2050; $minCount++) {
					    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
					    $this->set('anos',$anos);
				       }
				        $year = $this->ano_ejecucion();
				        $this->Session->write('ano_recurso_asignacion', $year);
				        $this->set('year', $year);
	}else if($var1==2){
                       $consolidacion= !isset($this->data['datos']['consolidacion'])?2:$this->data['datos']['consolidacion'];
                       if(isset($this->data['datos']['ano'])){           $year          = $this->data["datos"]["ano"];}
                       if(isset($this->data['datos']['tipo_recurso'])){  $tipo_recurso  = $this->data["datos"]["tipo_recurso"];}

						if($year<=2011){
							$var_a = "Laee";
							$var_b = "Fides";
						}else{
							$var_a = "Fci";
							$var_b = "Mpps";
						}

                        if(isset($consolidacion) && $this->verifica_SS(5)==1){
								        if($consolidacion==1){
								           $cod_dep="";
								           $cod_dep2="";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror="";
								           $this->set("opcion",1);
								        }else{
								           $cod_dep="a.cod_dep,";
								           $cod_dep2="x.cod_dep=a.cod_dep and";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror=",a.cod_dep";
								           $this->set("opcion",2);
								        }
								    }else{
								        $cod_dep="a.cod_dep,";
								           $cod_dep2="x.cod_dep=a.cod_dep and";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror=",a.cod_dep";
								           $this->set("opcion",2);
								    }
								    if($tipo_recurso==9){
								   	 $sql_aux = "";
								   }else{
								   	 $sql_aux = " and a.tipo_presupuesto = ".$tipo_recurso;
								   }

						    				$datos = $this->cfpd05->execute("SELECT
															    						 SUM(a.asignacion_anual)           as asignacion_anual,
									  													 SUM(a.aumento_traslado_anual)     as aumento_traslado_anual,
																						 SUM(a.disminucion_traslado_anual) as disminucion_traslado_anual,
																						 SUM(a.credito_adicional_anual)    as credito_adicional_anual,
																						 SUM(a.rebaja_anual)               as rebaja_anual,
																						 SUM(a.compromiso_anual)           as compromiso_anual,
																						 SUM(a.causado_anual)              as causado_anual,
																						 SUM(a.pagado_anual)               as pagado_anual,

																						 SUM(a.aumento_traslado_ene)     as aumento_traslado_ene,
																						 SUM(a.disminucion_traslado_ene) as disminucion_traslado_ene,
																						 SUM(a.credito_adicional_ene)    as credito_adicional_ene,
																						 SUM(a.rebaja_ene)               as rebaja_ene,
																						 SUM(a.compromiso_ene)           as compromiso_ene,
																						 SUM(a.causado_ene)              as causado_ene,
																						 SUM(a.pagado_ene)               as pagado_ene,

																						 SUM(a.aumento_traslado_feb)     as aumento_traslado_feb,
																						 SUM(a.disminucion_traslado_feb) as disminucion_traslado_feb,
																						 SUM(a.credito_adicional_feb)    as credito_adicional_feb,
																						 SUM(a.rebaja_feb)               as rebaja_feb,
																						 SUM(a.compromiso_feb)           as compromiso_feb,
																						 SUM(a.causado_feb)              as causado_feb,
																						 SUM(a.pagado_feb)               as pagado_feb,

																						 SUM(a.aumento_traslado_mar)     as aumento_traslado_mar,
																						 SUM(a.disminucion_traslado_mar) as disminucion_traslado_mar,
																						 SUM(a.credito_adicional_mar)    as credito_adicional_mar,
																						 SUM(a.rebaja_mar)               as rebaja_mar,
																						 SUM(a.compromiso_mar)           as compromiso_mar,
																						 SUM(a.causado_mar)              as causado_mar,
																						 SUM(a.pagado_mar)               as pagado_mar,

																						 SUM(a.aumento_traslado_abr)     as aumento_traslado_abr,
																						 SUM(a.disminucion_traslado_abr) as disminucion_traslado_abr,
																						 SUM(a.credito_adicional_abr)    as credito_adicional_abr,
																						 SUM(a.rebaja_abr)               as rebaja_abr,
																						 SUM(a.compromiso_abr)           as compromiso_abr,
																						 SUM(a.causado_abr)              as causado_abr,
																						 SUM(a.pagado_abr)               as pagado_abr,

																						 SUM(a.aumento_traslado_may)     as aumento_traslado_may,
																						 SUM(a.disminucion_traslado_may) as disminucion_traslado_may,
																						 SUM(a.credito_adicional_may)    as credito_adicional_may,
																						 SUM(a.rebaja_may)               as rebaja_may,
																						 SUM(a.compromiso_may)           as compromiso_may,
																						 SUM(a.causado_may)              as causado_may,
																						 SUM(a.pagado_may)               as pagado_may,

																						 SUM(a.aumento_traslado_jun)     as aumento_traslado_jun,
																						 SUM(a.disminucion_traslado_jun) as disminucion_traslado_jun,
																						 SUM(a.credito_adicional_jun)    as credito_adicional_jun,
																						 SUM(a.rebaja_jun)               as rebaja_jun,
																						 SUM(a.compromiso_jun)           as compromiso_jun,
																						 SUM(a.causado_jun)              as causado_jun,
																						 SUM(a.pagado_jun)               as pagado_jun,

																						 SUM(a.aumento_traslado_jul)     as aumento_traslado_jul,
																						 SUM(a.disminucion_traslado_jul) as disminucion_traslado_jul,
																						 SUM(a.credito_adicional_jul)    as credito_adicional_jul,
																						 SUM(a.rebaja_jul)               as rebaja_jul,
																						 SUM(a.compromiso_jul)           as compromiso_jul,
																						 SUM(a.causado_jul)              as causado_jul,
																						 SUM(a.pagado_jul)               as pagado_jul,

																						 SUM(a.aumento_traslado_ago)     as aumento_traslado_ago,
																						 SUM(a.disminucion_traslado_ago) as disminucion_traslado_ago,
																						 SUM(a.credito_adicional_ago)    as credito_adicional_ago,
																						 SUM(a.rebaja_ago)               as rebaja_ago,
																						 SUM(a.compromiso_ago)           as compromiso_ago,
																						 SUM(a.causado_ago)              as causado_ago,
																						 SUM(a.pagado_ago)               as pagado_ago,

																						 SUM(a.aumento_traslado_sep)     as aumento_traslado_sep,
																						 SUM(a.disminucion_traslado_sep) as disminucion_traslado_sep,
																						 SUM(a.credito_adicional_sep)    as credito_adicional_sep,
																						 SUM(a.rebaja_sep)               as rebaja_sep,
																						 SUM(a.compromiso_sep)           as compromiso_sep,
																						 SUM(a.causado_sep)              as causado_sep,
																						 SUM(a.pagado_sep)               as pagado_sep,

																						 SUM(a.aumento_traslado_oct)     as aumento_traslado_oct,
																						 SUM(a.disminucion_traslado_oct) as disminucion_traslado_oct,
																						 SUM(a.credito_adicional_oct)    as credito_adicional_oct,
																						 SUM(a.rebaja_oct)               as rebaja_oct,
																						 SUM(a.compromiso_oct)           as compromiso_oct,
																						 SUM(a.causado_oct)              as causado_oct,
																						 SUM(a.pagado_oct)               as pagado_oct,

																						 SUM(a.aumento_traslado_nov)     as aumento_traslado_nov,
																						 SUM(a.disminucion_traslado_nov) as disminucion_traslado_nov,
																						 SUM(a.credito_adicional_nov)    as credito_adicional_nov,
																						 SUM(a.rebaja_nov)               as rebaja_nov,
																						 SUM(a.compromiso_nov)           as compromiso_nov,
																						 SUM(a.causado_nov)              as causado_nov,
																						 SUM(a.pagado_nov)               as pagado_nov,

																						 SUM(a.aumento_traslado_dic)     as aumento_traslado_dic,
																						 SUM(a.disminucion_traslado_dic) as disminucion_traslado_dic,
																						 SUM(a.credito_adicional_dic)    as credito_adicional_dic,
																						 SUM(a.rebaja_dic)               as rebaja_dic,
																						 SUM(a.compromiso_dic)           as compromiso_dic,
																						 SUM(a.causado_dic)              as causado_dic,
																						 SUM(a.pagado_dic)               as pagado_dic

																		 	  FROM cfpd05 a where ".$con." and  a.ano  =  ".$year."  ".$sql_aux."
						                        							  GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano");


                       if($tipo_recurso==1){  $opcion1_aux = "TIPO DEL RECURSO: Ordinario,";
				 }else if($tipo_recurso==2){  $opcion1_aux = "TIPO DEL RECURSO: Coordinado,";
				 }else if($tipo_recurso==3){  $opcion1_aux = "TIPO DEL RECURSO: $var_a,";
				 }else if($tipo_recurso==4){  $opcion1_aux = "TIPO DEL RECURSO: $var_b,";
				 }else if($tipo_recurso==5){  $opcion1_aux = "TIPO DEL RECURSO: Ingresos Extraordinarios,";
				 }else if($tipo_recurso==6){  $opcion1_aux = "TIPO DEL RECURSO: Ingresos Propios,";
				 }else if($tipo_recurso==7){  $opcion1_aux = "TIPO DEL RECURSO: Laee,";
				 }else if($tipo_recurso==8){  $opcion1_aux = "TIPO DEL RECURSO: Fides,";
				 }else if($tipo_recurso==9){  $opcion1_aux = ""; }
				 $parametros["titulo"][]  = "TIPOS DE RECURSOS";
				 $parametros["titulo"][]  = $opcion1_aux." AÑO RECURSO: ".$year;

			if($mes_letra != ""){
				 $parametros["titulo"][]  = " INFORMACIÓN DE: ".$mes_letra;
			}

                 $variables["nombre_total"] = "Asignación Ajustada";
                 $variables["nombre"][] = "Compromisos";
                 $variables["nombre"][] = "Causados";
                 $variables["nombre"][] = "Pagado";
                 $variables["nombre"][] = "Disponibilidad";
                 $variables["nombre"][] = "Deuda";


        if(!empty($datos[0][0]['asignacion_anual']) && $datos[0][0]['asignacion_anual']!=0){

                 	    $asignacion_anual            =  $datos[0][0]['asignacion_anual'];

 				if ($var2=='1'){ // TODO
						$aumento_traslado_anual      =  $datos[0][0]['aumento_traslado_anual'];
						$credito_adicional_anual     =  $datos[0][0]['credito_adicional_anual'];
						$disminucion_traslado_anual  =  $datos[0][0]['disminucion_traslado_anual'];
						$rebaja_anual                =  $datos[0][0]['rebaja_anual'];
						$compromiso_anual    =  $datos[0][0]['compromiso_anual'];
						$causado_anual       =  $datos[0][0]['causado_anual'];
						$pagado_anual        =  $datos[0][0]['pagado_anual'];
						$asignacion_ajustada =  (($asignacion_anual+$aumento_traslado_anual+$credito_adicional_anual)-($disminucion_traslado_anual+$rebaja_anual));
						$deuda = ($causado_anual-$pagado_anual);
						$disponibilidad =  ($asignacion_ajustada-$compromiso_anual);

                 }else{ // MES
						$aumento_traslado_ene      =  $datos[0][0]['aumento_traslado_ene'];
						$credito_adicional_ene     =  $datos[0][0]['credito_adicional_ene'];
						$disminucion_traslado_ene  =  $datos[0][0]['disminucion_traslado_ene'];
						$rebaja_ene                =  $datos[0][0]['rebaja_ene'];
						$compromiso_ene    =  $datos[0][0]['compromiso_ene'];
						$causado_ene       =  $datos[0][0]['causado_ene'];
						$pagado_ene        =  $datos[0][0]['pagado_ene'];
						$modificacion_ene =  (($aumento_traslado_ene+$credito_adicional_ene)-($disminucion_traslado_ene+$rebaja_ene));
						$deuda_ene = ($causado_ene-$pagado_ene);

						$aumento_traslado_feb      =  $datos[0][0]['aumento_traslado_feb'];
						$credito_adicional_feb     =  $datos[0][0]['credito_adicional_feb'];
						$disminucion_traslado_feb  =  $datos[0][0]['disminucion_traslado_feb'];
						$rebaja_feb                =  $datos[0][0]['rebaja_feb'];
						$compromiso_feb    =  $datos[0][0]['compromiso_feb'];
						$causado_feb       =  $datos[0][0]['causado_feb'];
						$pagado_feb        =  $datos[0][0]['pagado_feb'];
						$modificacion_feb =  (($aumento_traslado_feb+$credito_adicional_feb)-($disminucion_traslado_feb+$rebaja_feb));
						$deuda_feb = ($causado_feb-$pagado_feb);

						$aumento_traslado_mar      =  $datos[0][0]['aumento_traslado_mar'];
						$credito_adicional_mar     =  $datos[0][0]['credito_adicional_mar'];
						$disminucion_traslado_mar  =  $datos[0][0]['disminucion_traslado_mar'];
						$rebaja_mar                =  $datos[0][0]['rebaja_mar'];
						$compromiso_mar    =  $datos[0][0]['compromiso_mar'];
						$causado_mar       =  $datos[0][0]['causado_mar'];
						$pagado_mar        =  $datos[0][0]['pagado_mar'];
						$modificacion_mar =  (($aumento_traslado_mar+$credito_adicional_mar)-($disminucion_traslado_mar+$rebaja_mar));
						$deuda_mar = ($causado_mar-$pagado_mar);

						$aumento_traslado_abr      =  $datos[0][0]['aumento_traslado_abr'];
						$credito_adicional_abr     =  $datos[0][0]['credito_adicional_abr'];
						$disminucion_traslado_abr  =  $datos[0][0]['disminucion_traslado_abr'];
						$rebaja_abr                =  $datos[0][0]['rebaja_abr'];
						$compromiso_abr    =  $datos[0][0]['compromiso_abr'];
						$causado_abr       =  $datos[0][0]['causado_abr'];
						$pagado_abr        =  $datos[0][0]['pagado_abr'];
						$modificacion_abr =  (($aumento_traslado_abr+$credito_adicional_abr)-($disminucion_traslado_abr+$rebaja_abr));
						$deuda_abr = ($causado_abr-$pagado_abr);

						$aumento_traslado_may      =  $datos[0][0]['aumento_traslado_may'];
						$credito_adicional_may     =  $datos[0][0]['credito_adicional_may'];
						$disminucion_traslado_may  =  $datos[0][0]['disminucion_traslado_may'];
						$rebaja_may                =  $datos[0][0]['rebaja_may'];
						$compromiso_may    =  $datos[0][0]['compromiso_may'];
						$causado_may       =  $datos[0][0]['causado_may'];
						$pagado_may        =  $datos[0][0]['pagado_may'];
						$modificacion_may =  (($aumento_traslado_may+$credito_adicional_may)-($disminucion_traslado_may+$rebaja_may));
						$deuda_may = ($causado_may-$pagado_may);

						$aumento_traslado_jun      =  $datos[0][0]['aumento_traslado_jun'];
						$credito_adicional_jun     =  $datos[0][0]['credito_adicional_jun'];
						$disminucion_traslado_jun  =  $datos[0][0]['disminucion_traslado_jun'];
						$rebaja_jun                =  $datos[0][0]['rebaja_jun'];
						$compromiso_jun    =  $datos[0][0]['compromiso_jun'];
						$causado_jun       =  $datos[0][0]['causado_jun'];
						$pagado_jun        =  $datos[0][0]['pagado_jun'];
						$modificacion_jun =  (($aumento_traslado_jun+$credito_adicional_jun)-($disminucion_traslado_jun+$rebaja_jun));
						$deuda_jun = ($causado_jun-$pagado_jun);

						$aumento_traslado_jul      =  $datos[0][0]['aumento_traslado_jul'];
						$credito_adicional_jul     =  $datos[0][0]['credito_adicional_jul'];
						$disminucion_traslado_jul  =  $datos[0][0]['disminucion_traslado_jul'];
						$rebaja_jul                =  $datos[0][0]['rebaja_jul'];
						$compromiso_jul    =  $datos[0][0]['compromiso_jul'];
						$causado_jul       =  $datos[0][0]['causado_jul'];
						$pagado_jul        =  $datos[0][0]['pagado_jul'];
						$modificacion_jul =  (($aumento_traslado_jul+$credito_adicional_jul)-($disminucion_traslado_jul+$rebaja_jul));
						$deuda_jul = ($causado_jul-$pagado_jul);

						$aumento_traslado_ago      =  $datos[0][0]['aumento_traslado_ago'];
						$credito_adicional_ago     =  $datos[0][0]['credito_adicional_ago'];
						$disminucion_traslado_ago  =  $datos[0][0]['disminucion_traslado_ago'];
						$rebaja_ago                =  $datos[0][0]['rebaja_ago'];
						$compromiso_ago    =  $datos[0][0]['compromiso_ago'];
						$causado_ago       =  $datos[0][0]['causado_ago'];
						$pagado_ago        =  $datos[0][0]['pagado_ago'];
						$modificacion_ago =  (($aumento_traslado_ago+$credito_adicional_ago)-($disminucion_traslado_ago+$rebaja_ago));
						$deuda_ago = ($causado_ago-$pagado_ago);

						$aumento_traslado_sep      =  $datos[0][0]['aumento_traslado_sep'];
						$credito_adicional_sep     =  $datos[0][0]['credito_adicional_sep'];
						$disminucion_traslado_sep  =  $datos[0][0]['disminucion_traslado_sep'];
						$rebaja_sep                =  $datos[0][0]['rebaja_sep'];
						$compromiso_sep    =  $datos[0][0]['compromiso_sep'];
						$causado_sep       =  $datos[0][0]['causado_sep'];
						$pagado_sep        =  $datos[0][0]['pagado_sep'];
						$modificacion_sep =  (($aumento_traslado_sep+$credito_adicional_sep)-($disminucion_traslado_sep+$rebaja_sep));
						$deuda_sep = ($causado_sep-$pagado_sep);

						$aumento_traslado_oct      =  $datos[0][0]['aumento_traslado_oct'];
						$credito_adicional_oct     =  $datos[0][0]['credito_adicional_oct'];
						$disminucion_traslado_oct  =  $datos[0][0]['disminucion_traslado_oct'];
						$rebaja_oct                =  $datos[0][0]['rebaja_oct'];
						$compromiso_oct    =  $datos[0][0]['compromiso_oct'];
						$causado_oct       =  $datos[0][0]['causado_oct'];
						$pagado_oct        =  $datos[0][0]['pagado_oct'];
						$modificacion_oct =  (($aumento_traslado_oct+$credito_adicional_oct)-($disminucion_traslado_oct+$rebaja_oct));
						$deuda_oct = ($causado_oct-$pagado_oct);

						$aumento_traslado_nov      =  $datos[0][0]['aumento_traslado_nov'];
						$credito_adicional_nov     =  $datos[0][0]['credito_adicional_nov'];
						$disminucion_traslado_nov  =  $datos[0][0]['disminucion_traslado_nov'];
						$rebaja_nov                =  $datos[0][0]['rebaja_nov'];
						$compromiso_nov    =  $datos[0][0]['compromiso_nov'];
						$causado_nov       =  $datos[0][0]['causado_nov'];
						$pagado_nov        =  $datos[0][0]['pagado_nov'];
						$modificacion_nov =  (($aumento_traslado_nov+$credito_adicional_nov)-($disminucion_traslado_nov+$rebaja_nov));
						$deuda_nov = ($causado_nov-$pagado_nov);

						$aumento_traslado_dic      =  $datos[0][0]['aumento_traslado_dic'];
						$credito_adicional_dic     =  $datos[0][0]['credito_adicional_dic'];
						$disminucion_traslado_dic  =  $datos[0][0]['disminucion_traslado_dic'];
						$rebaja_dic                =  $datos[0][0]['rebaja_dic'];
						$compromiso_dic    =  $datos[0][0]['compromiso_dic'];
						$causado_dic       =  $datos[0][0]['causado_dic'];
						$pagado_dic        =  $datos[0][0]['pagado_dic'];
						$modificacion_dic =  (($aumento_traslado_dic+$credito_adicional_dic)-($disminucion_traslado_dic+$rebaja_dic));
						$deuda_dic = ($causado_dic-$pagado_dic);


						if ($mes==1){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene);
							$compromiso_anual    = $compromiso_ene;
							$causado_anual       = $causado_ene;
							$pagado_anual        = $pagado_ene;
							$disponibilidad      = (($asignacion_anual+$modificacion_ene)-$compromiso_ene);
							$deuda               = $deuda_ene;
						}else if ($mes==2){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb);
							$causado_anual       = ($causado_ene+$causado_feb);
							$pagado_anual        = ($pagado_ene+$pagado_feb);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb)-($compromiso_ene+$compromiso_feb));
							$deuda               = ($deuda_ene+$deuda_feb);
						}else if ($mes==3){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar)-($compromiso_ene+$compromiso_feb+$compromiso_mar));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar);
						}else if ($mes==4){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr);
						}else if ($mes==5){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may);
						}else if ($mes==6){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun);
						}else if ($mes==7){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun+$causado_jul);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun+$pagado_jul);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun+$deuda_jul);
						}else if ($mes==8){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun+$causado_jul+$causado_ago);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun+$pagado_jul+$pagado_ago);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun+$deuda_jul+$deuda_ago);
						}else if ($mes==9){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun+$causado_jul+$causado_ago+$causado_sep);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun+$pagado_jul+$pagado_ago+$pagado_sep);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun+$deuda_jul+$deuda_ago+$deuda_sep);
						}else if ($mes==10){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep+$modificacion_oct);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep+$compromiso_oct);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun+$causado_jul+$causado_ago+$causado_sep+$causado_oct);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun+$pagado_jul+$pagado_ago+$pagado_sep+$pagado_oct);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep+$modificacion_oct)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep+$compromiso_oct));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun+$deuda_jul+$deuda_ago+$deuda_sep+$deuda_oct);
						}else if ($mes==11){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep+$modificacion_oct+$modificacion_nov);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep+$compromiso_oct+$compromiso_nov);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun+$causado_jul+$causado_ago+$causado_sep+$causado_oct+$causado_nov);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun+$pagado_jul+$pagado_ago+$pagado_sep+$pagado_oct+$pagado_nov);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep+$modificacion_oct+$modificacion_nov)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep+$compromiso_oct+$compromiso_nov));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun+$deuda_jul+$deuda_ago+$deuda_sep+$deuda_oct+$deuda_nov);
						}else if ($mes==12){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep+$modificacion_oct+$modificacion_nov+$modificacion_dic);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep+$compromiso_oct+$compromiso_nov+$compromiso_dic);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun+$causado_jul+$causado_ago+$causado_sep+$causado_oct+$causado_nov+$causado_dic);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun+$pagado_jul+$pagado_ago+$pagado_sep+$pagado_oct+$pagado_nov+$pagado_dic);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep+$modificacion_oct+$modificacion_nov+$modificacion_dic)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep+$compromiso_oct+$compromiso_nov+$compromiso_dic));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun+$deuda_jul+$deuda_ago+$deuda_sep+$deuda_oct+$deuda_nov+$deuda_dic);
						}
               }
		                 $variables["porcentaje_total"] = ($asignacion_ajustada * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($compromiso_anual * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($causado_anual * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($pagado_anual * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($disponibilidad * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($deuda * 100) / $asignacion_ajustada;
		                 $variables["monto_total"] = $asignacion_ajustada;
		                 $variables["monto"][] = $compromiso_anual;
		                 $variables["monto"][] = $causado_anual;
		                 $variables["monto"][] = $pagado_anual;
		                 $variables["monto"][] = $disponibilidad;
		                 $variables["monto"][] = $deuda;
                 }else{
                 	     $variables["porcentaje_total"] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["monto_total"]  = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
                 }//fin else
                 $this->genera_grafica_1(1, $parametros, $variables);

	}//fin else if

$this->set('opcion', $var1);


}//fin function











function grafica_17($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

	$this->layout="ajax";

	$var2 = $this->data["datos"]["radio_opcion"];
	$mes = ((int) $this->data["datos"]["mes"] * 1);

	if ($var2=='1'){ // TODO
		$mes_letra = "";
	}else{ // MES
		$mes_letra = $this->mes_letra(mascara($mes, 2));
		if ($mes>1){$mes_letra="ENERO A ".$mes_letra;}
	}


	    if($var1==1){
				      for($minCount = 2007; $minCount < 2050; $minCount++) {
					    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
					    $this->set('anos',$anos);
				       }

				        $year = $this->ano_ejecucion();
				        $this->Session->write('ano_recurso_asignacion', $year);
				        $this->set('year', $year);
					        if($cod_dep==1){
	                            $rs=$this->v_balance_ejecucion->execute(("SELECT DISTINCT a.cod_sector,a.deno_sector FROM v_balance_ejecucion a WHERE " . $this->condicionNDEP()). " ORDER BY " .cod_sector);
					        }else{
	                            $rs=$this->v_balance_ejecucion->execute(("SELECT DISTINCT a.cod_sector, a.deno_sector FROM v_balance_ejecucion a WHERE " . $this->condicion()). " ORDER BY " .cod_sector);
					        }
						    foreach($rs as $l){
								$v[]=$l[0]["cod_sector"];
								$d[]=$l[0]["deno_sector"];
							}
							$SECTOR = array_combine($v, $d);
						    $this->concatena($SECTOR, 'lista_numero');

	}else if($var1==2){
                       $consolidacion= !isset($this->data['datos']['consolidacion'])?2:$this->data['datos']['consolidacion'];
                       if(isset($this->data['datos']['ano'])){         $year       = $this->data["datos"]["ano"];}
                       if(!empty($this->data['datos']['cod_sector'])){ $cod_sector = $this->data["datos"]["cod_sector"];}

                        if(isset($consolidacion) && $this->verifica_SS(5)==1){
								        if($consolidacion==1){
								           $cod_dep="";
								           $cod_dep2="";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror="";
								           $this->set("opcion",1);
								        }else{
								           $cod_dep="a.cod_dep,";
								           $cod_dep2="x.cod_dep=a.cod_dep and";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror=",a.cod_dep";
								           $this->set("opcion",2);
								        }
								    }else{
								        $cod_dep="a.cod_dep,";
								           $cod_dep2="x.cod_dep=a.cod_dep and";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror=",a.cod_dep";
								           $this->set("opcion",2);
								    }
								   if($cod_sector==""){
								   	 $sql_aux = "";
								   }else{
								   	 $sql_aux = " and a.cod_sector = ".$cod_sector;
								   }

			    				$datos = $this->cfpd05->execute("SELECT
												    						  			 SUM(a.asignacion_anual)           as asignacion_anual,
									  													 SUM(a.aumento_traslado_anual)     as aumento_traslado_anual,
																						 SUM(a.disminucion_traslado_anual) as disminucion_traslado_anual,
																						 SUM(a.credito_adicional_anual)    as credito_adicional_anual,
																						 SUM(a.rebaja_anual)               as rebaja_anual,
																						 SUM(a.compromiso_anual)           as compromiso_anual,
																						 SUM(a.causado_anual)              as causado_anual,
																						 SUM(a.pagado_anual)               as pagado_anual,

																						 SUM(a.aumento_traslado_ene)     as aumento_traslado_ene,
																						 SUM(a.disminucion_traslado_ene) as disminucion_traslado_ene,
																						 SUM(a.credito_adicional_ene)    as credito_adicional_ene,
																						 SUM(a.rebaja_ene)               as rebaja_ene,
																						 SUM(a.compromiso_ene)           as compromiso_ene,
																						 SUM(a.causado_ene)              as causado_ene,
																						 SUM(a.pagado_ene)               as pagado_ene,

																						 SUM(a.aumento_traslado_feb)     as aumento_traslado_feb,
																						 SUM(a.disminucion_traslado_feb) as disminucion_traslado_feb,
																						 SUM(a.credito_adicional_feb)    as credito_adicional_feb,
																						 SUM(a.rebaja_feb)               as rebaja_feb,
																						 SUM(a.compromiso_feb)           as compromiso_feb,
																						 SUM(a.causado_feb)              as causado_feb,
																						 SUM(a.pagado_feb)               as pagado_feb,

																						 SUM(a.aumento_traslado_mar)     as aumento_traslado_mar,
																						 SUM(a.disminucion_traslado_mar) as disminucion_traslado_mar,
																						 SUM(a.credito_adicional_mar)    as credito_adicional_mar,
																						 SUM(a.rebaja_mar)               as rebaja_mar,
																						 SUM(a.compromiso_mar)           as compromiso_mar,
																						 SUM(a.causado_mar)              as causado_mar,
																						 SUM(a.pagado_mar)               as pagado_mar,

																						 SUM(a.aumento_traslado_abr)     as aumento_traslado_abr,
																						 SUM(a.disminucion_traslado_abr) as disminucion_traslado_abr,
																						 SUM(a.credito_adicional_abr)    as credito_adicional_abr,
																						 SUM(a.rebaja_abr)               as rebaja_abr,
																						 SUM(a.compromiso_abr)           as compromiso_abr,
																						 SUM(a.causado_abr)              as causado_abr,
																						 SUM(a.pagado_abr)               as pagado_abr,

																						 SUM(a.aumento_traslado_may)     as aumento_traslado_may,
																						 SUM(a.disminucion_traslado_may) as disminucion_traslado_may,
																						 SUM(a.credito_adicional_may)    as credito_adicional_may,
																						 SUM(a.rebaja_may)               as rebaja_may,
																						 SUM(a.compromiso_may)           as compromiso_may,
																						 SUM(a.causado_may)              as causado_may,
																						 SUM(a.pagado_may)               as pagado_may,

																						 SUM(a.aumento_traslado_jun)     as aumento_traslado_jun,
																						 SUM(a.disminucion_traslado_jun) as disminucion_traslado_jun,
																						 SUM(a.credito_adicional_jun)    as credito_adicional_jun,
																						 SUM(a.rebaja_jun)               as rebaja_jun,
																						 SUM(a.compromiso_jun)           as compromiso_jun,
																						 SUM(a.causado_jun)              as causado_jun,
																						 SUM(a.pagado_jun)               as pagado_jun,

																						 SUM(a.aumento_traslado_jul)     as aumento_traslado_jul,
																						 SUM(a.disminucion_traslado_jul) as disminucion_traslado_jul,
																						 SUM(a.credito_adicional_jul)    as credito_adicional_jul,
																						 SUM(a.rebaja_jul)               as rebaja_jul,
																						 SUM(a.compromiso_jul)           as compromiso_jul,
																						 SUM(a.causado_jul)              as causado_jul,
																						 SUM(a.pagado_jul)               as pagado_jul,

																						 SUM(a.aumento_traslado_ago)     as aumento_traslado_ago,
																						 SUM(a.disminucion_traslado_ago) as disminucion_traslado_ago,
																						 SUM(a.credito_adicional_ago)    as credito_adicional_ago,
																						 SUM(a.rebaja_ago)               as rebaja_ago,
																						 SUM(a.compromiso_ago)           as compromiso_ago,
																						 SUM(a.causado_ago)              as causado_ago,
																						 SUM(a.pagado_ago)               as pagado_ago,

																						 SUM(a.aumento_traslado_sep)     as aumento_traslado_sep,
																						 SUM(a.disminucion_traslado_sep) as disminucion_traslado_sep,
																						 SUM(a.credito_adicional_sep)    as credito_adicional_sep,
																						 SUM(a.rebaja_sep)               as rebaja_sep,
																						 SUM(a.compromiso_sep)           as compromiso_sep,
																						 SUM(a.causado_sep)              as causado_sep,
																						 SUM(a.pagado_sep)               as pagado_sep,

																						 SUM(a.aumento_traslado_oct)     as aumento_traslado_oct,
																						 SUM(a.disminucion_traslado_oct) as disminucion_traslado_oct,
																						 SUM(a.credito_adicional_oct)    as credito_adicional_oct,
																						 SUM(a.rebaja_oct)               as rebaja_oct,
																						 SUM(a.compromiso_oct)           as compromiso_oct,
																						 SUM(a.causado_oct)              as causado_oct,
																						 SUM(a.pagado_oct)               as pagado_oct,

																						 SUM(a.aumento_traslado_nov)     as aumento_traslado_nov,
																						 SUM(a.disminucion_traslado_nov) as disminucion_traslado_nov,
																						 SUM(a.credito_adicional_nov)    as credito_adicional_nov,
																						 SUM(a.rebaja_nov)               as rebaja_nov,
																						 SUM(a.compromiso_nov)           as compromiso_nov,
																						 SUM(a.causado_nov)              as causado_nov,
																						 SUM(a.pagado_nov)               as pagado_nov,

																						 SUM(a.aumento_traslado_dic)     as aumento_traslado_dic,
																						 SUM(a.disminucion_traslado_dic) as disminucion_traslado_dic,
																						 SUM(a.credito_adicional_dic)    as credito_adicional_dic,
																						 SUM(a.rebaja_dic)               as rebaja_dic,
																						 SUM(a.compromiso_dic)           as compromiso_dic,
																						 SUM(a.causado_dic)              as causado_dic,
																						 SUM(a.pagado_dic)               as pagado_dic

															 	  FROM cfpd05 a where ".$con." and  a.ano  =  ".$year."  ".$sql_aux."
			                        							  GROUP BY a.cod_presi,
			                        							  		   a.cod_entidad,
			                        							  		   a.cod_tipo_inst,
			                        							  		   a.cod_inst,
			                        							  		   a.ano");


                 $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_sector,a.deno_sector FROM v_balance_ejecucion a WHERE  ". $con." ".$sql_aux." and a.ano=".$year." ORDER BY ".cod_sector);

				 $parametros["titulo"][]  = "EJECUCIÓN POR SECTORES";
				 if(isset($rs[0][0]['cod_sector']) && !empty($this->data['datos']['cod_sector'])){
				  $parametros["titulo"][]  = "SECTOR: ".mascara2($rs[0][0]['cod_sector'])." - ".$rs[0][0]['deno_sector'].", AÑO RECURSO: ".$year;
				 }else{
				  $parametros["titulo"][]  = " AÑO RECURSO: ".$year;
				 }
				 if($mes_letra != ""){
				 	$parametros["titulo"][]  = " INFORMACIÓN DE: ".$mes_letra;
				 }
                 $variables["nombre_total"] = "Asignación Ajustada";
                 $variables["nombre"][] = "Compromisos";
                 $variables["nombre"][] = "Causados";
                 $variables["nombre"][] = "Pagado";
                 $variables["nombre"][] = "Disponibilidad";
                 $variables["nombre"][] = "Deuda";

                 if(!empty($datos[0][0]['asignacion_anual']) && $datos[0][0]['asignacion_anual']!=0){

                 	    $asignacion_anual            =  $datos[0][0]['asignacion_anual'];

				if ($var2=='1'){ // TODO
						$aumento_traslado_anual      =  $datos[0][0]['aumento_traslado_anual'];
						$credito_adicional_anual     =  $datos[0][0]['credito_adicional_anual'];
						$disminucion_traslado_anual  =  $datos[0][0]['disminucion_traslado_anual'];
						$rebaja_anual                =  $datos[0][0]['rebaja_anual'];
						$compromiso_anual    =  $datos[0][0]['compromiso_anual'];
						$causado_anual       =  $datos[0][0]['causado_anual'];
						$pagado_anual        =  $datos[0][0]['pagado_anual'];
						$asignacion_ajustada =  (($asignacion_anual+$aumento_traslado_anual+$credito_adicional_anual)-($disminucion_traslado_anual+$rebaja_anual));
						$deuda = ($causado_anual-$pagado_anual);
						$disponibilidad =  ($asignacion_ajustada-$compromiso_anual);

                 }else{ // MES
						$aumento_traslado_ene      =  $datos[0][0]['aumento_traslado_ene'];
						$credito_adicional_ene     =  $datos[0][0]['credito_adicional_ene'];
						$disminucion_traslado_ene  =  $datos[0][0]['disminucion_traslado_ene'];
						$rebaja_ene                =  $datos[0][0]['rebaja_ene'];
						$compromiso_ene    =  $datos[0][0]['compromiso_ene'];
						$causado_ene       =  $datos[0][0]['causado_ene'];
						$pagado_ene        =  $datos[0][0]['pagado_ene'];
						$modificacion_ene =  (($aumento_traslado_ene+$credito_adicional_ene)-($disminucion_traslado_ene+$rebaja_ene));
						$deuda_ene = ($causado_ene-$pagado_ene);

						$aumento_traslado_feb      =  $datos[0][0]['aumento_traslado_feb'];
						$credito_adicional_feb     =  $datos[0][0]['credito_adicional_feb'];
						$disminucion_traslado_feb  =  $datos[0][0]['disminucion_traslado_feb'];
						$rebaja_feb                =  $datos[0][0]['rebaja_feb'];
						$compromiso_feb    =  $datos[0][0]['compromiso_feb'];
						$causado_feb       =  $datos[0][0]['causado_feb'];
						$pagado_feb        =  $datos[0][0]['pagado_feb'];
						$modificacion_feb =  (($aumento_traslado_feb+$credito_adicional_feb)-($disminucion_traslado_feb+$rebaja_feb));
						$deuda_feb = ($causado_feb-$pagado_feb);

						$aumento_traslado_mar      =  $datos[0][0]['aumento_traslado_mar'];
						$credito_adicional_mar     =  $datos[0][0]['credito_adicional_mar'];
						$disminucion_traslado_mar  =  $datos[0][0]['disminucion_traslado_mar'];
						$rebaja_mar                =  $datos[0][0]['rebaja_mar'];
						$compromiso_mar    =  $datos[0][0]['compromiso_mar'];
						$causado_mar       =  $datos[0][0]['causado_mar'];
						$pagado_mar        =  $datos[0][0]['pagado_mar'];
						$modificacion_mar =  (($aumento_traslado_mar+$credito_adicional_mar)-($disminucion_traslado_mar+$rebaja_mar));
						$deuda_mar = ($causado_mar-$pagado_mar);

						$aumento_traslado_abr      =  $datos[0][0]['aumento_traslado_abr'];
						$credito_adicional_abr     =  $datos[0][0]['credito_adicional_abr'];
						$disminucion_traslado_abr  =  $datos[0][0]['disminucion_traslado_abr'];
						$rebaja_abr                =  $datos[0][0]['rebaja_abr'];
						$compromiso_abr    =  $datos[0][0]['compromiso_abr'];
						$causado_abr       =  $datos[0][0]['causado_abr'];
						$pagado_abr        =  $datos[0][0]['pagado_abr'];
						$modificacion_abr =  (($aumento_traslado_abr+$credito_adicional_abr)-($disminucion_traslado_abr+$rebaja_abr));
						$deuda_abr = ($causado_abr-$pagado_abr);

						$aumento_traslado_may      =  $datos[0][0]['aumento_traslado_may'];
						$credito_adicional_may     =  $datos[0][0]['credito_adicional_may'];
						$disminucion_traslado_may  =  $datos[0][0]['disminucion_traslado_may'];
						$rebaja_may                =  $datos[0][0]['rebaja_may'];
						$compromiso_may    =  $datos[0][0]['compromiso_may'];
						$causado_may       =  $datos[0][0]['causado_may'];
						$pagado_may        =  $datos[0][0]['pagado_may'];
						$modificacion_may =  (($aumento_traslado_may+$credito_adicional_may)-($disminucion_traslado_may+$rebaja_may));
						$deuda_may = ($causado_may-$pagado_may);

						$aumento_traslado_jun      =  $datos[0][0]['aumento_traslado_jun'];
						$credito_adicional_jun     =  $datos[0][0]['credito_adicional_jun'];
						$disminucion_traslado_jun  =  $datos[0][0]['disminucion_traslado_jun'];
						$rebaja_jun                =  $datos[0][0]['rebaja_jun'];
						$compromiso_jun    =  $datos[0][0]['compromiso_jun'];
						$causado_jun       =  $datos[0][0]['causado_jun'];
						$pagado_jun        =  $datos[0][0]['pagado_jun'];
						$modificacion_jun =  (($aumento_traslado_jun+$credito_adicional_jun)-($disminucion_traslado_jun+$rebaja_jun));
						$deuda_jun = ($causado_jun-$pagado_jun);

						$aumento_traslado_jul      =  $datos[0][0]['aumento_traslado_jul'];
						$credito_adicional_jul     =  $datos[0][0]['credito_adicional_jul'];
						$disminucion_traslado_jul  =  $datos[0][0]['disminucion_traslado_jul'];
						$rebaja_jul                =  $datos[0][0]['rebaja_jul'];
						$compromiso_jul    =  $datos[0][0]['compromiso_jul'];
						$causado_jul       =  $datos[0][0]['causado_jul'];
						$pagado_jul        =  $datos[0][0]['pagado_jul'];
						$modificacion_jul =  (($aumento_traslado_jul+$credito_adicional_jul)-($disminucion_traslado_jul+$rebaja_jul));
						$deuda_jul = ($causado_jul-$pagado_jul);

						$aumento_traslado_ago      =  $datos[0][0]['aumento_traslado_ago'];
						$credito_adicional_ago     =  $datos[0][0]['credito_adicional_ago'];
						$disminucion_traslado_ago  =  $datos[0][0]['disminucion_traslado_ago'];
						$rebaja_ago                =  $datos[0][0]['rebaja_ago'];
						$compromiso_ago    =  $datos[0][0]['compromiso_ago'];
						$causado_ago       =  $datos[0][0]['causado_ago'];
						$pagado_ago        =  $datos[0][0]['pagado_ago'];
						$modificacion_ago =  (($aumento_traslado_ago+$credito_adicional_ago)-($disminucion_traslado_ago+$rebaja_ago));
						$deuda_ago = ($causado_ago-$pagado_ago);

						$aumento_traslado_sep      =  $datos[0][0]['aumento_traslado_sep'];
						$credito_adicional_sep     =  $datos[0][0]['credito_adicional_sep'];
						$disminucion_traslado_sep  =  $datos[0][0]['disminucion_traslado_sep'];
						$rebaja_sep                =  $datos[0][0]['rebaja_sep'];
						$compromiso_sep    =  $datos[0][0]['compromiso_sep'];
						$causado_sep       =  $datos[0][0]['causado_sep'];
						$pagado_sep        =  $datos[0][0]['pagado_sep'];
						$modificacion_sep =  (($aumento_traslado_sep+$credito_adicional_sep)-($disminucion_traslado_sep+$rebaja_sep));
						$deuda_sep = ($causado_sep-$pagado_sep);

						$aumento_traslado_oct      =  $datos[0][0]['aumento_traslado_oct'];
						$credito_adicional_oct     =  $datos[0][0]['credito_adicional_oct'];
						$disminucion_traslado_oct  =  $datos[0][0]['disminucion_traslado_oct'];
						$rebaja_oct                =  $datos[0][0]['rebaja_oct'];
						$compromiso_oct    =  $datos[0][0]['compromiso_oct'];
						$causado_oct       =  $datos[0][0]['causado_oct'];
						$pagado_oct        =  $datos[0][0]['pagado_oct'];
						$modificacion_oct =  (($aumento_traslado_oct+$credito_adicional_oct)-($disminucion_traslado_oct+$rebaja_oct));
						$deuda_oct = ($causado_oct-$pagado_oct);

						$aumento_traslado_nov      =  $datos[0][0]['aumento_traslado_nov'];
						$credito_adicional_nov     =  $datos[0][0]['credito_adicional_nov'];
						$disminucion_traslado_nov  =  $datos[0][0]['disminucion_traslado_nov'];
						$rebaja_nov                =  $datos[0][0]['rebaja_nov'];
						$compromiso_nov    =  $datos[0][0]['compromiso_nov'];
						$causado_nov       =  $datos[0][0]['causado_nov'];
						$pagado_nov        =  $datos[0][0]['pagado_nov'];
						$modificacion_nov =  (($aumento_traslado_nov+$credito_adicional_nov)-($disminucion_traslado_nov+$rebaja_nov));
						$deuda_nov = ($causado_nov-$pagado_nov);

						$aumento_traslado_dic      =  $datos[0][0]['aumento_traslado_dic'];
						$credito_adicional_dic     =  $datos[0][0]['credito_adicional_dic'];
						$disminucion_traslado_dic  =  $datos[0][0]['disminucion_traslado_dic'];
						$rebaja_dic                =  $datos[0][0]['rebaja_dic'];
						$compromiso_dic    =  $datos[0][0]['compromiso_dic'];
						$causado_dic       =  $datos[0][0]['causado_dic'];
						$pagado_dic        =  $datos[0][0]['pagado_dic'];
						$modificacion_dic =  (($aumento_traslado_dic+$credito_adicional_dic)-($disminucion_traslado_dic+$rebaja_dic));
						$deuda_dic = ($causado_dic-$pagado_dic);


						if ($mes==1){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene);
							$compromiso_anual    = $compromiso_ene;
							$causado_anual       = $causado_ene;
							$pagado_anual        = $pagado_ene;
							$disponibilidad      = (($asignacion_anual+$modificacion_ene)-$compromiso_ene);
							$deuda               = $deuda_ene;
						}else if ($mes==2){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb);
							$causado_anual       = ($causado_ene+$causado_feb);
							$pagado_anual        = ($pagado_ene+$pagado_feb);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb)-($compromiso_ene+$compromiso_feb));
							$deuda               = ($deuda_ene+$deuda_feb);
						}else if ($mes==3){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar)-($compromiso_ene+$compromiso_feb+$compromiso_mar));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar);
						}else if ($mes==4){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr);
						}else if ($mes==5){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may);
						}else if ($mes==6){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun);
						}else if ($mes==7){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun+$causado_jul);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun+$pagado_jul);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun+$deuda_jul);
						}else if ($mes==8){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun+$causado_jul+$causado_ago);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun+$pagado_jul+$pagado_ago);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun+$deuda_jul+$deuda_ago);
						}else if ($mes==9){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun+$causado_jul+$causado_ago+$causado_sep);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun+$pagado_jul+$pagado_ago+$pagado_sep);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun+$deuda_jul+$deuda_ago+$deuda_sep);
						}else if ($mes==10){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep+$modificacion_oct);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep+$compromiso_oct);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun+$causado_jul+$causado_ago+$causado_sep+$causado_oct);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun+$pagado_jul+$pagado_ago+$pagado_sep+$pagado_oct);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep+$modificacion_oct)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep+$compromiso_oct));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun+$deuda_jul+$deuda_ago+$deuda_sep+$deuda_oct);
						}else if ($mes==11){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep+$modificacion_oct+$modificacion_nov);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep+$compromiso_oct+$compromiso_nov);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun+$causado_jul+$causado_ago+$causado_sep+$causado_oct+$causado_nov);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun+$pagado_jul+$pagado_ago+$pagado_sep+$pagado_oct+$pagado_nov);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep+$modificacion_oct+$modificacion_nov)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep+$compromiso_oct+$compromiso_nov));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun+$deuda_jul+$deuda_ago+$deuda_sep+$deuda_oct+$deuda_nov);
						}else if ($mes==12){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep+$modificacion_oct+$modificacion_nov+$modificacion_dic);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep+$compromiso_oct+$compromiso_nov+$compromiso_dic);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun+$causado_jul+$causado_ago+$causado_sep+$causado_oct+$causado_nov+$causado_dic);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun+$pagado_jul+$pagado_ago+$pagado_sep+$pagado_oct+$pagado_nov+$pagado_dic);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep+$modificacion_oct+$modificacion_nov+$modificacion_dic)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep+$compromiso_oct+$compromiso_nov+$compromiso_dic));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun+$deuda_jul+$deuda_ago+$deuda_sep+$deuda_oct+$deuda_nov+$deuda_dic);
						}
               }

		                 $variables["porcentaje_total"] = ($asignacion_ajustada * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($compromiso_anual * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($causado_anual * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($pagado_anual * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($disponibilidad * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($deuda * 100) / $asignacion_ajustada;
		                 $variables["monto_total"] = $asignacion_ajustada;
		                 $variables["monto"][] = $compromiso_anual;
		                 $variables["monto"][] = $causado_anual;
		                 $variables["monto"][] = $pagado_anual;
		                 $variables["monto"][] = $disponibilidad;
		                 $variables["monto"][] = $deuda;
                 }else{
                 	     $variables["porcentaje_total"] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["monto_total"]  = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
                 }//fin else
                 $this->genera_grafica_1(1, $parametros, $variables);

	}//fin else if

$this->set('opcion', $var1);


}//fin function








function grafica_18($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	$var2 = $this->data["datos"]["radio_opcion"];
	$mes = ((int) $this->data["datos"]["mes"] * 1);

	if ($var2=='1'){ // TODO
		$mes_letra = "";
	}else{ // MES
		$mes_letra = $this->mes_letra(mascara($mes, 2));
		if ($mes>1){$mes_letra="ENERO A ".$mes_letra;}
	}

	    if($var1==1){
				      for($minCount = 2007; $minCount < 2050; $minCount++) {
					    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
					    $this->set('anos',$anos);
				       }

				        $year = $this->ano_ejecucion();
				        $this->Session->write('ano_recurso_asignacion', $year);
				        $this->set('year', $year);
					        if($cod_dep==1){
	                            $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_partida,a.deno_partida FROM v_cfpd05_denominaciones a WHERE ". $this->condicionNDEP(). " ORDER BY " .cod_partida);
					        }else{
	                            $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_partida,a.deno_partida FROM v_cfpd05_denominaciones a WHERE ". $this->condicion(). " ORDER BY " .cod_partida);
					        }
						    foreach($rs as $l){
								$SECTOR[$l[0]["cod_partida"]]= CE.".".separa_partida_de_grupo($l[0]["cod_partida"])." - ".$l[0]["deno_partida"] ;
							}
						    $this->set('lista_numero', $SECTOR);

	}else if($var1==2){
                       $consolidacion= !isset($this->data['datos']['consolidacion'])?2:$this->data['datos']['consolidacion'];
                       if(isset($this->data['datos']['ano'])){           $year        = $this->data["datos"]["ano"];}
                       if(!empty($this->data['datos']['cod_partida'])){  $cod_partida = $this->data["datos"]["cod_partida"];}

                        if(isset($consolidacion) && $this->verifica_SS(5)==1){
								        if($consolidacion==1){
								           $cod_dep="";
								           $cod_dep2="";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror="";
								           $this->set("opcion",1);
								        }else{
								           $cod_dep="a.cod_dep,";
								           $cod_dep2="x.cod_dep=a.cod_dep and";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror=",a.cod_dep";
								           $this->set("opcion",2);
								        }
								    }else{
								        $cod_dep="a.cod_dep,";
								           $cod_dep2="x.cod_dep=a.cod_dep and";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror=",a.cod_dep";
								           $this->set("opcion",2);
								    }
								   if($cod_partida==""){
								   	 $sql_aux = "";
								   }else{
								   	 $sql_aux = " and a.cod_partida = ".$cod_partida;
								   }

			    				$datos = $this->cfpd05->execute("SELECT
												    						  			 SUM(a.asignacion_anual)           as asignacion_anual,
									  													 SUM(a.aumento_traslado_anual)     as aumento_traslado_anual,
																						 SUM(a.disminucion_traslado_anual) as disminucion_traslado_anual,
																						 SUM(a.credito_adicional_anual)    as credito_adicional_anual,
																						 SUM(a.rebaja_anual)               as rebaja_anual,
																						 SUM(a.compromiso_anual)           as compromiso_anual,
																						 SUM(a.causado_anual)              as causado_anual,
																						 SUM(a.pagado_anual)               as pagado_anual,

																						 SUM(a.aumento_traslado_ene)     as aumento_traslado_ene,
																						 SUM(a.disminucion_traslado_ene) as disminucion_traslado_ene,
																						 SUM(a.credito_adicional_ene)    as credito_adicional_ene,
																						 SUM(a.rebaja_ene)               as rebaja_ene,
																						 SUM(a.compromiso_ene)           as compromiso_ene,
																						 SUM(a.causado_ene)              as causado_ene,
																						 SUM(a.pagado_ene)               as pagado_ene,

																						 SUM(a.aumento_traslado_feb)     as aumento_traslado_feb,
																						 SUM(a.disminucion_traslado_feb) as disminucion_traslado_feb,
																						 SUM(a.credito_adicional_feb)    as credito_adicional_feb,
																						 SUM(a.rebaja_feb)               as rebaja_feb,
																						 SUM(a.compromiso_feb)           as compromiso_feb,
																						 SUM(a.causado_feb)              as causado_feb,
																						 SUM(a.pagado_feb)               as pagado_feb,

																						 SUM(a.aumento_traslado_mar)     as aumento_traslado_mar,
																						 SUM(a.disminucion_traslado_mar) as disminucion_traslado_mar,
																						 SUM(a.credito_adicional_mar)    as credito_adicional_mar,
																						 SUM(a.rebaja_mar)               as rebaja_mar,
																						 SUM(a.compromiso_mar)           as compromiso_mar,
																						 SUM(a.causado_mar)              as causado_mar,
																						 SUM(a.pagado_mar)               as pagado_mar,

																						 SUM(a.aumento_traslado_abr)     as aumento_traslado_abr,
																						 SUM(a.disminucion_traslado_abr) as disminucion_traslado_abr,
																						 SUM(a.credito_adicional_abr)    as credito_adicional_abr,
																						 SUM(a.rebaja_abr)               as rebaja_abr,
																						 SUM(a.compromiso_abr)           as compromiso_abr,
																						 SUM(a.causado_abr)              as causado_abr,
																						 SUM(a.pagado_abr)               as pagado_abr,

																						 SUM(a.aumento_traslado_may)     as aumento_traslado_may,
																						 SUM(a.disminucion_traslado_may) as disminucion_traslado_may,
																						 SUM(a.credito_adicional_may)    as credito_adicional_may,
																						 SUM(a.rebaja_may)               as rebaja_may,
																						 SUM(a.compromiso_may)           as compromiso_may,
																						 SUM(a.causado_may)              as causado_may,
																						 SUM(a.pagado_may)               as pagado_may,

																						 SUM(a.aumento_traslado_jun)     as aumento_traslado_jun,
																						 SUM(a.disminucion_traslado_jun) as disminucion_traslado_jun,
																						 SUM(a.credito_adicional_jun)    as credito_adicional_jun,
																						 SUM(a.rebaja_jun)               as rebaja_jun,
																						 SUM(a.compromiso_jun)           as compromiso_jun,
																						 SUM(a.causado_jun)              as causado_jun,
																						 SUM(a.pagado_jun)               as pagado_jun,

																						 SUM(a.aumento_traslado_jul)     as aumento_traslado_jul,
																						 SUM(a.disminucion_traslado_jul) as disminucion_traslado_jul,
																						 SUM(a.credito_adicional_jul)    as credito_adicional_jul,
																						 SUM(a.rebaja_jul)               as rebaja_jul,
																						 SUM(a.compromiso_jul)           as compromiso_jul,
																						 SUM(a.causado_jul)              as causado_jul,
																						 SUM(a.pagado_jul)               as pagado_jul,

																						 SUM(a.aumento_traslado_ago)     as aumento_traslado_ago,
																						 SUM(a.disminucion_traslado_ago) as disminucion_traslado_ago,
																						 SUM(a.credito_adicional_ago)    as credito_adicional_ago,
																						 SUM(a.rebaja_ago)               as rebaja_ago,
																						 SUM(a.compromiso_ago)           as compromiso_ago,
																						 SUM(a.causado_ago)              as causado_ago,
																						 SUM(a.pagado_ago)               as pagado_ago,

																						 SUM(a.aumento_traslado_sep)     as aumento_traslado_sep,
																						 SUM(a.disminucion_traslado_sep) as disminucion_traslado_sep,
																						 SUM(a.credito_adicional_sep)    as credito_adicional_sep,
																						 SUM(a.rebaja_sep)               as rebaja_sep,
																						 SUM(a.compromiso_sep)           as compromiso_sep,
																						 SUM(a.causado_sep)              as causado_sep,
																						 SUM(a.pagado_sep)               as pagado_sep,

																						 SUM(a.aumento_traslado_oct)     as aumento_traslado_oct,
																						 SUM(a.disminucion_traslado_oct) as disminucion_traslado_oct,
																						 SUM(a.credito_adicional_oct)    as credito_adicional_oct,
																						 SUM(a.rebaja_oct)               as rebaja_oct,
																						 SUM(a.compromiso_oct)           as compromiso_oct,
																						 SUM(a.causado_oct)              as causado_oct,
																						 SUM(a.pagado_oct)               as pagado_oct,

																						 SUM(a.aumento_traslado_nov)     as aumento_traslado_nov,
																						 SUM(a.disminucion_traslado_nov) as disminucion_traslado_nov,
																						 SUM(a.credito_adicional_nov)    as credito_adicional_nov,
																						 SUM(a.rebaja_nov)               as rebaja_nov,
																						 SUM(a.compromiso_nov)           as compromiso_nov,
																						 SUM(a.causado_nov)              as causado_nov,
																						 SUM(a.pagado_nov)               as pagado_nov,

																						 SUM(a.aumento_traslado_dic)     as aumento_traslado_dic,
																						 SUM(a.disminucion_traslado_dic) as disminucion_traslado_dic,
																						 SUM(a.credito_adicional_dic)    as credito_adicional_dic,
																						 SUM(a.rebaja_dic)               as rebaja_dic,
																						 SUM(a.compromiso_dic)           as compromiso_dic,
																						 SUM(a.causado_dic)              as causado_dic,
																						 SUM(a.pagado_dic)               as pagado_dic

															 	  FROM cfpd05 a where ".$con." and  a.ano  =  ".$year."  ".$sql_aux."
			                        							  GROUP BY a.cod_presi,
			                        							  		   a.cod_entidad,
			                        							  		   a.cod_tipo_inst,
			                        							  		   a.cod_inst,
			                        							  		   a.ano");


                 $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_partida, a.deno_partida FROM v_cfpd05_denominaciones a WHERE  ". $con." ".$sql_aux." and a.ano=".$year." ORDER BY ".cod_partida);

				 $parametros["titulo"][]  = "EJECUCIÓN POR PARTIDAS";
				 if(isset($rs[0][0]['cod_partida']) && !empty($this->data['datos']['cod_partida'])){
				  $parametros["titulo"][]  = "PARTIDA: ".CE.".".separa_partida_de_grupo($rs[0][0]['cod_partida'])." - ".$rs[0][0]['deno_partida'].", AÑO RECURSO: ".$year;
				 }else{
				  $parametros["titulo"][]  = "AÑO RECURSO: ".$year;
				 }
				 if($mes_letra != ""){
				 	$parametros["titulo"][]  = " INFORMACIÓN DE: ".$mes_letra;
				 }
                 $variables["nombre_total"] = "Asignación Ajustada";
                 $variables["nombre"][] = "Compromisos";
                 $variables["nombre"][] = "Causados";
                 $variables["nombre"][] = "Pagado";
                 $variables["nombre"][] = "Disponibilidad";
                 $variables["nombre"][] = "Deuda";

                 if(!empty($datos[0][0]['asignacion_anual']) && $datos[0][0]['asignacion_anual']!=0){
                 	    $asignacion_anual            =  $datos[0][0]['asignacion_anual'];

				 if ($var2=='1'){ // TODO
						$aumento_traslado_anual      =  $datos[0][0]['aumento_traslado_anual'];
						$credito_adicional_anual     =  $datos[0][0]['credito_adicional_anual'];
						$disminucion_traslado_anual  =  $datos[0][0]['disminucion_traslado_anual'];
						$rebaja_anual                =  $datos[0][0]['rebaja_anual'];
						$compromiso_anual    =  $datos[0][0]['compromiso_anual'];
						$causado_anual       =  $datos[0][0]['causado_anual'];
						$pagado_anual        =  $datos[0][0]['pagado_anual'];
						$asignacion_ajustada =  (($asignacion_anual+$aumento_traslado_anual+$credito_adicional_anual)-($disminucion_traslado_anual+$rebaja_anual));
						$deuda = ($causado_anual-$pagado_anual);
						$disponibilidad =  ($asignacion_ajustada-$compromiso_anual);

                 }else{ // MES
						$aumento_traslado_ene      =  $datos[0][0]['aumento_traslado_ene'];
						$credito_adicional_ene     =  $datos[0][0]['credito_adicional_ene'];
						$disminucion_traslado_ene  =  $datos[0][0]['disminucion_traslado_ene'];
						$rebaja_ene                =  $datos[0][0]['rebaja_ene'];
						$compromiso_ene    =  $datos[0][0]['compromiso_ene'];
						$causado_ene       =  $datos[0][0]['causado_ene'];
						$pagado_ene        =  $datos[0][0]['pagado_ene'];
						$modificacion_ene =  (($aumento_traslado_ene+$credito_adicional_ene)-($disminucion_traslado_ene+$rebaja_ene));
						$deuda_ene = ($causado_ene-$pagado_ene);

						$aumento_traslado_feb      =  $datos[0][0]['aumento_traslado_feb'];
						$credito_adicional_feb     =  $datos[0][0]['credito_adicional_feb'];
						$disminucion_traslado_feb  =  $datos[0][0]['disminucion_traslado_feb'];
						$rebaja_feb                =  $datos[0][0]['rebaja_feb'];
						$compromiso_feb    =  $datos[0][0]['compromiso_feb'];
						$causado_feb       =  $datos[0][0]['causado_feb'];
						$pagado_feb        =  $datos[0][0]['pagado_feb'];
						$modificacion_feb =  (($aumento_traslado_feb+$credito_adicional_feb)-($disminucion_traslado_feb+$rebaja_feb));
						$deuda_feb = ($causado_feb-$pagado_feb);

						$aumento_traslado_mar      =  $datos[0][0]['aumento_traslado_mar'];
						$credito_adicional_mar     =  $datos[0][0]['credito_adicional_mar'];
						$disminucion_traslado_mar  =  $datos[0][0]['disminucion_traslado_mar'];
						$rebaja_mar                =  $datos[0][0]['rebaja_mar'];
						$compromiso_mar    =  $datos[0][0]['compromiso_mar'];
						$causado_mar       =  $datos[0][0]['causado_mar'];
						$pagado_mar        =  $datos[0][0]['pagado_mar'];
						$modificacion_mar =  (($aumento_traslado_mar+$credito_adicional_mar)-($disminucion_traslado_mar+$rebaja_mar));
						$deuda_mar = ($causado_mar-$pagado_mar);

						$aumento_traslado_abr      =  $datos[0][0]['aumento_traslado_abr'];
						$credito_adicional_abr     =  $datos[0][0]['credito_adicional_abr'];
						$disminucion_traslado_abr  =  $datos[0][0]['disminucion_traslado_abr'];
						$rebaja_abr                =  $datos[0][0]['rebaja_abr'];
						$compromiso_abr    =  $datos[0][0]['compromiso_abr'];
						$causado_abr       =  $datos[0][0]['causado_abr'];
						$pagado_abr        =  $datos[0][0]['pagado_abr'];
						$modificacion_abr =  (($aumento_traslado_abr+$credito_adicional_abr)-($disminucion_traslado_abr+$rebaja_abr));
						$deuda_abr = ($causado_abr-$pagado_abr);

						$aumento_traslado_may      =  $datos[0][0]['aumento_traslado_may'];
						$credito_adicional_may     =  $datos[0][0]['credito_adicional_may'];
						$disminucion_traslado_may  =  $datos[0][0]['disminucion_traslado_may'];
						$rebaja_may                =  $datos[0][0]['rebaja_may'];
						$compromiso_may    =  $datos[0][0]['compromiso_may'];
						$causado_may       =  $datos[0][0]['causado_may'];
						$pagado_may        =  $datos[0][0]['pagado_may'];
						$modificacion_may =  (($aumento_traslado_may+$credito_adicional_may)-($disminucion_traslado_may+$rebaja_may));
						$deuda_may = ($causado_may-$pagado_may);

						$aumento_traslado_jun      =  $datos[0][0]['aumento_traslado_jun'];
						$credito_adicional_jun     =  $datos[0][0]['credito_adicional_jun'];
						$disminucion_traslado_jun  =  $datos[0][0]['disminucion_traslado_jun'];
						$rebaja_jun                =  $datos[0][0]['rebaja_jun'];
						$compromiso_jun    =  $datos[0][0]['compromiso_jun'];
						$causado_jun       =  $datos[0][0]['causado_jun'];
						$pagado_jun        =  $datos[0][0]['pagado_jun'];
						$modificacion_jun =  (($aumento_traslado_jun+$credito_adicional_jun)-($disminucion_traslado_jun+$rebaja_jun));
						$deuda_jun = ($causado_jun-$pagado_jun);

						$aumento_traslado_jul      =  $datos[0][0]['aumento_traslado_jul'];
						$credito_adicional_jul     =  $datos[0][0]['credito_adicional_jul'];
						$disminucion_traslado_jul  =  $datos[0][0]['disminucion_traslado_jul'];
						$rebaja_jul                =  $datos[0][0]['rebaja_jul'];
						$compromiso_jul    =  $datos[0][0]['compromiso_jul'];
						$causado_jul       =  $datos[0][0]['causado_jul'];
						$pagado_jul        =  $datos[0][0]['pagado_jul'];
						$modificacion_jul =  (($aumento_traslado_jul+$credito_adicional_jul)-($disminucion_traslado_jul+$rebaja_jul));
						$deuda_jul = ($causado_jul-$pagado_jul);

						$aumento_traslado_ago      =  $datos[0][0]['aumento_traslado_ago'];
						$credito_adicional_ago     =  $datos[0][0]['credito_adicional_ago'];
						$disminucion_traslado_ago  =  $datos[0][0]['disminucion_traslado_ago'];
						$rebaja_ago                =  $datos[0][0]['rebaja_ago'];
						$compromiso_ago    =  $datos[0][0]['compromiso_ago'];
						$causado_ago       =  $datos[0][0]['causado_ago'];
						$pagado_ago        =  $datos[0][0]['pagado_ago'];
						$modificacion_ago =  (($aumento_traslado_ago+$credito_adicional_ago)-($disminucion_traslado_ago+$rebaja_ago));
						$deuda_ago = ($causado_ago-$pagado_ago);

						$aumento_traslado_sep      =  $datos[0][0]['aumento_traslado_sep'];
						$credito_adicional_sep     =  $datos[0][0]['credito_adicional_sep'];
						$disminucion_traslado_sep  =  $datos[0][0]['disminucion_traslado_sep'];
						$rebaja_sep                =  $datos[0][0]['rebaja_sep'];
						$compromiso_sep    =  $datos[0][0]['compromiso_sep'];
						$causado_sep       =  $datos[0][0]['causado_sep'];
						$pagado_sep        =  $datos[0][0]['pagado_sep'];
						$modificacion_sep =  (($aumento_traslado_sep+$credito_adicional_sep)-($disminucion_traslado_sep+$rebaja_sep));
						$deuda_sep = ($causado_sep-$pagado_sep);

						$aumento_traslado_oct      =  $datos[0][0]['aumento_traslado_oct'];
						$credito_adicional_oct     =  $datos[0][0]['credito_adicional_oct'];
						$disminucion_traslado_oct  =  $datos[0][0]['disminucion_traslado_oct'];
						$rebaja_oct                =  $datos[0][0]['rebaja_oct'];
						$compromiso_oct    =  $datos[0][0]['compromiso_oct'];
						$causado_oct       =  $datos[0][0]['causado_oct'];
						$pagado_oct        =  $datos[0][0]['pagado_oct'];
						$modificacion_oct =  (($aumento_traslado_oct+$credito_adicional_oct)-($disminucion_traslado_oct+$rebaja_oct));
						$deuda_oct = ($causado_oct-$pagado_oct);

						$aumento_traslado_nov      =  $datos[0][0]['aumento_traslado_nov'];
						$credito_adicional_nov     =  $datos[0][0]['credito_adicional_nov'];
						$disminucion_traslado_nov  =  $datos[0][0]['disminucion_traslado_nov'];
						$rebaja_nov                =  $datos[0][0]['rebaja_nov'];
						$compromiso_nov    =  $datos[0][0]['compromiso_nov'];
						$causado_nov       =  $datos[0][0]['causado_nov'];
						$pagado_nov        =  $datos[0][0]['pagado_nov'];
						$modificacion_nov =  (($aumento_traslado_nov+$credito_adicional_nov)-($disminucion_traslado_nov+$rebaja_nov));
						$deuda_nov = ($causado_nov-$pagado_nov);

						$aumento_traslado_dic      =  $datos[0][0]['aumento_traslado_dic'];
						$credito_adicional_dic     =  $datos[0][0]['credito_adicional_dic'];
						$disminucion_traslado_dic  =  $datos[0][0]['disminucion_traslado_dic'];
						$rebaja_dic                =  $datos[0][0]['rebaja_dic'];
						$compromiso_dic    =  $datos[0][0]['compromiso_dic'];
						$causado_dic       =  $datos[0][0]['causado_dic'];
						$pagado_dic        =  $datos[0][0]['pagado_dic'];
						$modificacion_dic =  (($aumento_traslado_dic+$credito_adicional_dic)-($disminucion_traslado_dic+$rebaja_dic));
						$deuda_dic = ($causado_dic-$pagado_dic);


						if ($mes==1){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene);
							$compromiso_anual    = $compromiso_ene;
							$causado_anual       = $causado_ene;
							$pagado_anual        = $pagado_ene;
							$disponibilidad      = (($asignacion_anual+$modificacion_ene)-$compromiso_ene);
							$deuda               = $deuda_ene;
						}else if ($mes==2){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb);
							$causado_anual       = ($causado_ene+$causado_feb);
							$pagado_anual        = ($pagado_ene+$pagado_feb);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb)-($compromiso_ene+$compromiso_feb));
							$deuda               = ($deuda_ene+$deuda_feb);
						}else if ($mes==3){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar)-($compromiso_ene+$compromiso_feb+$compromiso_mar));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar);
						}else if ($mes==4){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr);
						}else if ($mes==5){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may);
						}else if ($mes==6){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun);
						}else if ($mes==7){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun+$causado_jul);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun+$pagado_jul);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun+$deuda_jul);
						}else if ($mes==8){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun+$causado_jul+$causado_ago);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun+$pagado_jul+$pagado_ago);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun+$deuda_jul+$deuda_ago);
						}else if ($mes==9){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun+$causado_jul+$causado_ago+$causado_sep);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun+$pagado_jul+$pagado_ago+$pagado_sep);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun+$deuda_jul+$deuda_ago+$deuda_sep);
						}else if ($mes==10){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep+$modificacion_oct);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep+$compromiso_oct);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun+$causado_jul+$causado_ago+$causado_sep+$causado_oct);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun+$pagado_jul+$pagado_ago+$pagado_sep+$pagado_oct);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep+$modificacion_oct)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep+$compromiso_oct));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun+$deuda_jul+$deuda_ago+$deuda_sep+$deuda_oct);
						}else if ($mes==11){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep+$modificacion_oct+$modificacion_nov);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep+$compromiso_oct+$compromiso_nov);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun+$causado_jul+$causado_ago+$causado_sep+$causado_oct+$causado_nov);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun+$pagado_jul+$pagado_ago+$pagado_sep+$pagado_oct+$pagado_nov);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep+$modificacion_oct+$modificacion_nov)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep+$compromiso_oct+$compromiso_nov));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun+$deuda_jul+$deuda_ago+$deuda_sep+$deuda_oct+$deuda_nov);
						}else if ($mes==12){
							$asignacion_ajustada = ($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep+$modificacion_oct+$modificacion_nov+$modificacion_dic);
							$compromiso_anual    = ($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep+$compromiso_oct+$compromiso_nov+$compromiso_dic);
							$causado_anual       = ($causado_ene+$causado_feb+$causado_mar+$causado_abr+$causado_may+$causado_jun+$causado_jul+$causado_ago+$causado_sep+$causado_oct+$causado_nov+$causado_dic);
							$pagado_anual        = ($pagado_ene+$pagado_feb+$pagado_mar+$pagado_abr+$pagado_may+$pagado_jun+$pagado_jul+$pagado_ago+$pagado_sep+$pagado_oct+$pagado_nov+$pagado_dic);
							$disponibilidad      = (($asignacion_anual+$modificacion_ene+$modificacion_feb+$modificacion_mar+$modificacion_abr+$modificacion_may+$modificacion_jun+$modificacion_jul+$modificacion_ago+$modificacion_sep+$modificacion_oct+$modificacion_nov+$modificacion_dic)-($compromiso_ene+$compromiso_feb+$compromiso_mar+$compromiso_abr+$compromiso_may+$compromiso_jun+$compromiso_jul+$compromiso_ago+$compromiso_sep+$compromiso_oct+$compromiso_nov+$compromiso_dic));
							$deuda               = ($deuda_ene+$deuda_feb+$deuda_mar+$deuda_abr+$deuda_may+$deuda_jun+$deuda_jul+$deuda_ago+$deuda_sep+$deuda_oct+$deuda_nov+$deuda_dic);
						}
               }

		                 $variables["porcentaje_total"] = ($asignacion_ajustada * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($compromiso_anual * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($causado_anual * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($pagado_anual * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($disponibilidad * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($deuda * 100) / $asignacion_ajustada;
		                 $variables["monto_total"] = $asignacion_ajustada;
		                 $variables["monto"][] = $compromiso_anual;
		                 $variables["monto"][] = $causado_anual;
		                 $variables["monto"][] = $pagado_anual;
		                 $variables["monto"][] = $disponibilidad;
		                 $variables["monto"][] = $deuda;
                 }else{
                 	     $variables["porcentaje_total"] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["monto_total"]  = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
                 }//fin else
                 $this->genera_grafica_1(1, $parametros, $variables);

	}//fin else if

$this->set('opcion', $var1);


}//fin function













function grafica_19($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){



  }else if($var1==2){

				   $peticion      = $this->data['casp01']['peticion'];
				   $tipo_peticion = $this->data['casp01']['tipo_peticion'];
				if($tipo_peticion==1){
												if(empty($this->data['casp01']['rango'])){
														if($peticion==1){
															$organismo='';
														}else if($peticion==2){
															$organismo=" where ".$this->condicionNDEP();
														}else{
															$organismo=" where ".$this->SQLCA();
														}
										//			$ver1=$this->v_casd01_ubicacion_geografica->execute("select sum(numero_solicitudes) as solicitudes,sum(numero_ayudas) as ayudas,sum(monto_ayudas) as monto_ayudas from v_casd01_ubicacion_geografica where ".$organismo." and cod_municipio=0 and cod_parroquia=0 and cod_centro_poblado=0");
													$ver1=$this->v_casp01_relacion_solicitudes->execute("select count(numero_ocacion) as solicitudes,count(numero_documento_ayuda) as ayudas,sum(monto_total) as monto_ayudas from v_casp01_relacion_solicitudes ".$organismo);
												}else{
													if($peticion==1){
														$organismo='';
													}else if($peticion==2){
														$organismo=$this->condicionNDEP()." and ";
													}else{
														$organismo=$this->SQLCA()." and ";
													}
													$fecha_inicial=$this->data['casp01']['fecha_inicial'];
													$fecha_final=$this->data['casp01']['fecha_final'];
													//v_casp01_relacion_solicitudes
													$ver1=$this->v_casp01_relacion_solicitudes->execute("select count(numero_ocacion) as solicitudes,count(numero_documento_ayuda) as ayudas,sum(monto_total) as monto_ayudas from v_casp01_relacion_solicitudes where ".$organismo." (fecha_solicitud BETWEEN '$fecha_inicial' AND '$fecha_final')");

												}

												 $parametros["titulo"][]      = "SOLICITUDES Y AYUDAS";
								                 $variables["nombre_total"]   = "Total";
								                 $variables["nombre"][]       = "Atendidas";
								                 $variables["nombre"][]       = "No atendidas";
								                 $parametros["tipo_cantidad"] = true;

												if(!empty($ver1[0][0]['solicitudes'])){
														 $resta=$ver1[0][0]['solicitudes']-$ver1[0][0]['ayudas'];

										                 $variables["porcentaje_total"] = ($ver1[0][0]['solicitudes'] * 100) / $ver1[0][0]['solicitudes'];
										                 $variables["porcentaje"][] = ($ver1[0][0]['ayudas'] * 100) / $ver1[0][0]['solicitudes'];
										                 $variables["porcentaje"][] = ($resta * 100) / $ver1[0][0]['solicitudes'];

                                                         $variables["cantidad_total"]   = $ver1[0][0]['solicitudes'];
										                 $variables["cantidad"][]       = $ver1[0][0]['ayudas'];
										                 $variables["cantidad"][]       = $resta;

										                 $variables["monto_total"]   = 0;
													     $variables["monto"][]       = $ver1[0][0]['monto_ayudas'];
													     $variables["monto"][]       = 0;

												}else{
													     $variables["porcentaje_total"] = 0;
													     $variables["porcentaje"][] = 0;
													     $variables["porcentaje"][] = 0;

													     $variables["monto_total"]   = 0;
													     $variables["monto"][]       = 0;
													     $variables["monto"][]       = 0;

													     $variables["cantidad_total"]   = 0;
													     $variables["cantidad"][]       = 0;
													     $variables["cantidad"][]       = 0;
												}//fin else

				}else{
											if($peticion==1){
												$organismo='';
											}else if($peticion==2){
												$organismo=" and ".$this->condicionNDEP();
											}else{
												$organismo=" and ".$this->SQLCA();
											}

											if(empty($this->data['casp01']['rango'])){
												$tabla='v_casp01_relacion_solicitudes';
												$filtro=$organismo;
												$campos="count(numero_ocacion) as numero_solicitudes,count(numero_documento_ayuda) as numero_ayudas,sum(monto_total) as monto_ayudas";
											}else{
												$fecha_inicial=$this->data['casp01']['fecha_inicial'];
												$fecha_final=$this->data['casp01']['fecha_final'];
												$tabla='v_casp01_relacion_solicitudes';
												$filtro=$organismo." and (fecha_solicitud BETWEEN '$fecha_inicial' AND '$fecha_final')";
												$campos="count(numero_ocacion) as numero_solicitudes,count(numero_documento_ayuda) as numero_ayudas,sum(monto_total) as monto_ayudas";
											}

											if(!empty($this->data['casp01']['estado']) && empty($this->data['casp01']['cod_municipio']) && empty($this->data['casp01']['cod_parroquia']) && empty($this->data['casp01']['cod_centro'])){
												$estado=$this->data['casp01']['estado'];
												$ver1=$this->$tabla->execute("select ".$campos." from ".$tabla." where cod_estado=".$estado.$filtro);
											}else if(!empty($this->data['casp01']['estado']) && !empty($this->data['casp01']['cod_municipio']) && empty($this->data['casp01']['cod_parroquia']) && empty($this->data['casp01']['cod_centro'])){
												//solo el estado y municipio
												$estado=$this->data['casp01']['estado'];
												$municipio=$this->data['casp01']['cod_municipio'];
												$ver1=$this->$tabla->execute("select ".$campos." from ".$tabla." where cod_estado=".$estado." and cod_municipio=".$municipio.$filtro);
											}else if(!empty($this->data['casp01']['estado']) && !empty($this->data['casp01']['cod_municipio']) && !empty($this->data['casp01']['cod_parroquia']) && empty($this->data['casp01']['cod_centro'])){
												//solo el estado y municipio y parroquia
												$estado=$this->data['casp01']['estado'];
												$municipio=$this->data['casp01']['cod_municipio'];
												$parroquia=$this->data['casp01']['cod_parroquia'];
												$ver1=$this->$tabla->execute("select ".$campos." from ".$tabla." where cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia.$filtro);
											}else if(!empty($this->data['casp01']['estado']) && !empty($this->data['casp01']['cod_municipio']) && !empty($this->data['casp01']['cod_parroquia']) && !empty($this->data['casp01']['cod_centro'])){
												//solo el estado y municipio y parroquia y centro
												$estado=$this->data['casp01']['estado'];
												$municipio=$this->data['casp01']['cod_municipio'];
												$parroquia=$this->data['casp01']['cod_parroquia'];
												$centro=$this->data['casp01']['cod_centro'];
												$ver1=$this->$tabla->execute("select ".$campos." from ".$tabla." where cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia." and cod_centro_poblado=".$centro.$filtro);
											}else{
											}

											     $parametros["titulo"][]      = "SOLICITUDES Y AYUDAS";
								                 $variables["nombre_total"]   = "Total";
								                 $variables["nombre"][]       = "Atendidas";
								                 $variables["nombre"][]       = "No atendidas";
								                 $parametros["tipo_cantidad"] = true;

												if(!empty($ver1[0][0]['numero_solicitudes'])){
														 $resta=$ver1[0][0]['numero_solicitudes']-$ver1[0][0]['numero_ayudas'];

										                 $variables["porcentaje_total"] = ($ver1[0][0]['numero_solicitudes'] * 100) / $ver1[0][0]['numero_solicitudes'];
										                 $variables["porcentaje"][] = ($ver1[0][0]['numero_ayudas'] * 100) / $ver1[0][0]['numero_solicitudes'];
										                 $variables["porcentaje"][] = ($resta * 100) / $ver1[0][0]['numero_solicitudes'];

                                                         $variables["cantidad_total"] = $ver1[0][0]['numero_solicitudes'];
										                 $variables["cantidad"][]       = $ver1[0][0]['numero_ayudas'];
										                 $variables["cantidad"][]       = $resta;

										                 $variables["monto_total"]   = 0;
													     $variables["monto"][]       = $ver1[0][0]['monto_ayudas'];
													     $variables["monto"][]       = 0;

												}else{
													     $variables["porcentaje_total"] = 0;
													     $variables["porcentaje"][] = 0;
													     $variables["porcentaje"][] = 0;

													     $variables["monto_total"]   = 0;
													     $variables["monto"][]       = 0;
													     $variables["monto"][]       = 0;

													     $variables["cantidad_total"]   = 0;
													     $variables["cantidad"][]       = 0;
													     $variables["cantidad"][]       = 0;
												}//fin else


				}//fin if

           $this->genera_grafica_1(1, $parametros, $variables);


	}//fin else if

$this->set('opcion', $var1);


}//fin function
















function grafica_20($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){



  }else if($var1==2){

			$peticion=$this->data['casp01']['peticion'];
			$tipo_peticion=$this->data['casp01']['tipo_peticion'];
			$_SESSION['grafico']=array();
			if($tipo_peticion==1){
									if(empty($this->data['casp01']['rango'])){
										if($peticion==1){
											$organismo='';
										}else if($peticion==2){
											$organismo=" where ".$this->condicionNDEP();
										}else{
											$organismo=" where ".$this->SQLCA();
										}
										$sql=" SELECT
												 a.cod_tipo_ayuda,
												 quitar_acentos(a.tipo_ayuda::text) AS denominacion_ayuda,
												 count(a.numero_ocacion) AS solicitudes,
												 count(a.numero_documento_ayuda) AS ayudas,
												 sum(a.monto_total) AS monto_total
												 FROM v_casp01_relacion_solicitudes a ".$organismo."
												 GROUP BY
												 a.cod_tipo_ayuda,
												 quitar_acentos(a.tipo_ayuda::text)
												 ORDER BY
												 count(a.numero_ocacion)
												 DESC";
										$ver1=$this->v_casp01_relacion_solicitudes->execute($sql);
										$sumatoria=$this->v_casp01_relacion_solicitudes->execute("select count(numero_ocacion) as total_solicitudes from v_casp01_relacion_solicitudes ".$organismo);
							//			$ver1=$this->v_casd01_ubicacion_geografica_tipo_2->execute("select cod_tipo_ayuda,denominacion_ayuda,sum(numero_solicitudes) as solicitudes,sum(numero_ayudas) as ayudas,sum(monto_ayudas) as monto_total from v_casd01_ubicacion_geografica_tipo_2 group by cod_tipo_ayuda,denominacion_ayuda order by sum(numero_solicitudes) DESC");
							//			$sumatoria=$this->v_casd01_ubicacion_geografica_tipo_2->execute("select sum(numero_solicitudes) as total_solicitudes from v_casd01_ubicacion_geografica_tipo_2 ");
									}else{

										$fecha_inicial=$this->data['casp01']['fecha_inicial'];
										$fecha_final=$this->data['casp01']['fecha_final'];

										if($peticion==1){
											$organismo="(fecha_solicitud BETWEEN '$fecha_inicial' AND '$fecha_final')";
										}else if($peticion==2){
											$organismo=$this->condicionNDEP()." and (fecha_solicitud BETWEEN '$fecha_inicial' AND '$fecha_final')";
										}else{
											$organismo=$this->SQLCA()." and (fecha_solicitud BETWEEN '$fecha_inicial' AND '$fecha_final')";
										}

										//v_casp01_relacion_solicitudes
							//			$ver1=$this->v_casp01_relacion_solicitudes->execute("select count(numero_ocacion) as solicitudes,count(numero_documento_ayuda) as ayudas,sum(monto_total) as monto_ayudas from v_casp01_relacion_solicitudes where (fecha_solicitud BETWEEN '$fecha_inicial' AND '$fecha_final')");

										$sql=" SELECT
												 a.cod_tipo_ayuda,
												 quitar_acentos(a.tipo_ayuda::text) AS denominacion_ayuda,
												 count(a.numero_ocacion) AS solicitudes,
												 count(a.numero_documento_ayuda) AS ayudas,
												 sum(a.monto_total) AS monto_total
												 FROM v_casp01_relacion_solicitudes a where ".$organismo."
												 GROUP BY
												 a.cod_tipo_ayuda,
												 quitar_acentos(a.tipo_ayuda::text)
												 ORDER BY
												 count(a.numero_ocacion)
												 DESC";
											 $ver1=$this->v_casp01_relacion_solicitudes->execute($sql);
											 $sumatoria=$this->v_casp01_relacion_solicitudes->execute("select count(numero_ocacion) as total_solicitudes,count(numero_documento_ayuda) as ayudas,sum(monto_total) as monto_ayudas from v_casp01_relacion_solicitudes where ".$organismo);
									}

									$this->set('grafico',$ver1);
									$this->set('sumatoria',$sumatoria[0][0]['total_solicitudes']);
									$_SESSION['grafico']=$ver1;
								}else{
									if($peticion==1){
										$organismo='';
									}else if($peticion==2){
										$organismo=$this->condicionNDEP()." and ";
									}else{
										$organismo=$this->SQLCA()." and ";
									}


									if(empty($this->data['casp01']['rango'])){
										$filtro='';
										if(!empty($this->data['casp01']['estado']) && empty($this->data['casp01']['cod_municipio']) && empty($this->data['casp01']['cod_parroquia']) && empty($this->data['casp01']['cod_centro'])){
											$estado=$this->data['casp01']['estado'];
											$filtro="cod_estado=".$estado;
							//				$ver1=$this->v_casd01_ubicacion_geografica_tipo_2->execute("select cod_tipo_ayuda,denominacion_ayuda,sum(numero_solicitudes) as solicitudes,sum(numero_ayudas) as ayudas,sum(monto_ayudas) as monto_total  from v_casd01_ubicacion_geografica_tipo_2 where cod_estado=".$estado." group by cod_tipo_ayuda,denominacion_ayuda order by sum(numero_solicitudes) DESC");
							//				$sumatoria=$this->v_casd01_ubicacion_geografica_tipo_2->execute("select sum(numero_solicitudes) as total_solicitudes from v_casd01_ubicacion_geografica_tipo_2 where cod_estado=".$estado);
										}else if(!empty($this->data['casp01']['estado']) && !empty($this->data['casp01']['cod_municipio']) && empty($this->data['casp01']['cod_parroquia']) && empty($this->data['casp01']['cod_centro'])){
											//solo el estado y municipio
											$estado=$this->data['casp01']['estado'];
											$municipio=$this->data['casp01']['cod_municipio'];
											$filtro="cod_estado=".$estado." and cod_municipio=".$municipio;
							//				$ver1=$this->v_casd01_ubicacion_geografica_tipo_2->execute("select cod_tipo_ayuda,denominacion_ayuda,sum(numero_solicitudes) as solicitudes,sum(numero_ayudas) as ayudas,sum(monto_ayudas) as monto_total  from v_casd01_ubicacion_geografica_tipo_2 where cod_estado=".$estado." and cod_municipio=".$municipio." group by cod_tipo_ayuda,denominacion_ayuda order by sum(numero_solicitudes) DESC");
							//				$sumatoria=$this->v_casd01_ubicacion_geografica_tipo_2->execute("select sum(numero_solicitudes) as total_solicitudes from v_casd01_ubicacion_geografica_tipo_2 where cod_estado=".$estado." and cod_municipio=".$municipio);
										}else if(!empty($this->data['casp01']['estado']) && !empty($this->data['casp01']['cod_municipio']) && !empty($this->data['casp01']['cod_parroquia']) && empty($this->data['casp01']['cod_centro'])){
											//solo el estado y municipio y parroquia
											$estado=$this->data['casp01']['estado'];
											$municipio=$this->data['casp01']['cod_municipio'];
											$parroquia=$this->data['casp01']['cod_parroquia'];
											$filtro="cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia;
							//				$ver1=$this->v_casd01_ubicacion_geografica_tipo_2->execute("select cod_tipo_ayuda,denominacion_ayuda,sum(numero_solicitudes) as solicitudes,sum(numero_ayudas) as ayudas,sum(monto_ayudas) as monto_total  from v_casd01_ubicacion_geografica_tipo_2 where cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia." group by cod_tipo_ayuda,denominacion_ayuda order by sum(numero_solicitudes) DESC");
							//				$sumatoria=$this->v_casd01_ubicacion_geografica_tipo_2->execute("select sum(numero_solicitudes) as total_solicitudes from v_casd01_ubicacion_geografica_tipo_2 where cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia);
										}else if(!empty($this->data['casp01']['estado']) && !empty($this->data['casp01']['cod_municipio']) && !empty($this->data['casp01']['cod_parroquia']) && !empty($this->data['casp01']['cod_centro'])){
											//solo el estado y municipio y parroquia y centro
											$estado=$this->data['casp01']['estado'];
											$municipio=$this->data['casp01']['cod_municipio'];
											$parroquia=$this->data['casp01']['cod_parroquia'];
											$centro=$this->data['casp01']['cod_centro'];
											$filtro="cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia." and cod_centro_poblado=".$centro;
							//				$ver1=$this->v_casd01_ubicacion_geografica_tipo_2->execute("select cod_tipo_ayuda,denominacion_ayuda,sum(numero_solicitudes) as solicitudes,sum(numero_ayudas) as ayudas,sum(monto_ayudas) as monto_total  from v_casd01_ubicacion_geografica_tipo_2 where cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia." and cod_centro_poblado=".$centro." group by cod_tipo_ayuda,denominacion_ayuda order by ORDER BY sum(numero_solicitudes) DESC");
							//				$sumatoria=$this->v_casd01_ubicacion_geografica_tipo_2->execute("select sum(numero_solicitudes) as total_solicitudes from v_casd01_ubicacion_geografica_tipo_2 where cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia." and cod_centro_poblado=".$centro);
										}else{

										}

										$sql=" SELECT
												 a.cod_tipo_ayuda,
												 quitar_acentos(a.tipo_ayuda::text) AS denominacion_ayuda,
												 count(a.numero_ocacion) AS solicitudes,
												 count(a.numero_documento_ayuda) AS ayudas,
												 sum(a.monto_total) AS monto_total
												 FROM v_casp01_relacion_solicitudes a where ".$organismo.$filtro."
												 GROUP BY
												 a.cod_tipo_ayuda,
												 quitar_acentos(a.tipo_ayuda::text)
												 ORDER BY
												 count(a.numero_ocacion)
												 DESC";
											 $ver1=$this->v_casp01_relacion_solicitudes->execute($sql);
											 $sumatoria=$this->v_casp01_relacion_solicitudes->execute("select count(numero_ocacion) as total_solicitudes,count(numero_documento_ayuda) as ayudas,sum(monto_total) as monto_ayudas from v_casp01_relacion_solicitudes where ".$organismo.$filtro);

									}else{
										if($peticion==1){
											$organismo='';
										}else if($peticion==2){
											$organismo=$this->condicionNDEP()." and ";
										}else{
											$organismo=$this->SQLCA()." and ";
										}
										$fecha_inicial=$this->data['casp01']['fecha_inicial'];
										$fecha_final=$this->data['casp01']['fecha_final'];

										if(!empty($this->data['casp01']['estado']) && empty($this->data['casp01']['cod_municipio']) && empty($this->data['casp01']['cod_parroquia']) && empty($this->data['casp01']['cod_centro'])){
											$estado=$this->data['casp01']['estado'];
											$filtro="cod_estado=".$estado." and (fecha_solicitud BETWEEN '$fecha_inicial' AND '$fecha_final')";
										}else if(!empty($this->data['casp01']['estado']) && !empty($this->data['casp01']['cod_municipio']) && empty($this->data['casp01']['cod_parroquia']) && empty($this->data['casp01']['cod_centro'])){
											//solo el estado y municipio
											$estado=$this->data['casp01']['estado'];
											$municipio=$this->data['casp01']['cod_municipio'];
											$filtro="cod_estado=".$estado." and cod_municipio=".$municipio." and (fecha_solicitud BETWEEN '$fecha_inicial' AND '$fecha_final')";
										}else if(!empty($this->data['casp01']['estado']) && !empty($this->data['casp01']['cod_municipio']) && !empty($this->data['casp01']['cod_parroquia']) && empty($this->data['casp01']['cod_centro'])){
											//solo el estado y municipio y parroquia
											$estado=$this->data['casp01']['estado'];
											$municipio=$this->data['casp01']['cod_municipio'];
											$parroquia=$this->data['casp01']['cod_parroquia'];
											$filtro="cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia." and (fecha_solicitud BETWEEN '$fecha_inicial' AND '$fecha_final')";
										}else if(!empty($this->data['casp01']['estado']) && !empty($this->data['casp01']['cod_municipio']) && !empty($this->data['casp01']['cod_parroquia']) && !empty($this->data['casp01']['cod_centro'])){
											//solo el estado y municipio y parroquia y centro
											$estado=$this->data['casp01']['estado'];
											$municipio=$this->data['casp01']['cod_municipio'];
											$parroquia=$this->data['casp01']['cod_parroquia'];
											$centro=$this->data['casp01']['cod_centro'];
											$filtro="cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia." and cod_centro_poblado=".$centro." and (fecha_solicitud BETWEEN '$fecha_inicial' AND '$fecha_final')";
										}else{
										}

										$sql="SELECT
											 a.cod_tipo_ayuda,
											 quitar_acentos(a.tipo_ayuda::text) AS denominacion_ayuda,
											 count(a.numero_ocacion) AS solicitudes,
											 count(a.numero_documento_ayuda) AS ayudas,
											 sum(a.monto_total) AS monto_total
											   FROM v_casp01_relacion_solicitudes a where ".$organismo.$filtro."
											  GROUP BY
											 a.cod_tipo_ayuda,
											 quitar_acentos(a.tipo_ayuda::text)
											  ORDER BY
											  count(a.numero_ocacion)
												 DESC";
											 $ver1=$this->v_casp01_relacion_solicitudes->execute($sql);
											 $sumatoria=$this->v_casp01_relacion_solicitudes->execute("select count(numero_ocacion) as total_solicitudes,count(numero_documento_ayuda) as ayudas,sum(monto_total) as monto_ayudas from v_casp01_relacion_solicitudes where ".$organismo.$filtro);

									}

									$this->set('grafico',$ver1);
									$this->set('sumatoria',$sumatoria[0][0]['total_solicitudes']);
									$_SESSION['grafico']=$ver1;


								}// fin else




								$this->set('grafico',$ver1);
								$this->set('sumatoria',$sumatoria[0][0]['total_solicitudes']);

                 $parametros["titulo"][]      = "TIPO DE AYUDA SOLICITADA";
                 $parametros["tipo_cantidad"] = true;
                 $variables["nombre_total"]   = "Total";

                 if(!empty($sumatoria[0][0]['total_solicitudes']) && $sumatoria[0][0]['total_solicitudes']!=0){

                         $variables["monto_total"] = $sumatoria[0][0]['total_solicitudes'];
		                 $variables["porcentaje_total"] = ($sumatoria[0][0]['total_solicitudes'] * 100) / $sumatoria[0][0]['total_solicitudes'];

                         $cantidad_total = 0;
                         $monto_total = 0;
                        for($i=0;$i<count($ver1);$i++){
		                 $variables["porcentaje"][] = (($ver1[$i][0]['solicitudes']*100)/$sumatoria[0][0]['total_solicitudes']);
		                 $variables["cantidad"][]   = $ver1[$i][0]['solicitudes'];
		                 $variables["monto"][]      = $ver1[$i][0]['monto_total'];
		                 $variables["nombre"][]     = $ver1[$i][0]['denominacion_ayuda'];
		                 $cantidad_total += $ver1[$i][0]['solicitudes'];
		                 $monto_total    += $ver1[$i][0]['monto_total'];
		                 $variables["monto_total"]      = $monto_total;
		                 $variables["cantidad_total"]   = $cantidad_total;
                        }
                 }else{
                 	     $variables["porcentaje_total"] = 0;
		                 $variables["monto_total"]      = 0;
		                 $variables["cantidad_total"]   = 0;

		                 $variables["porcentaje"][] = 0;
		                 $variables["nombre"][]     = "";
		                 $variables["monto"][]      = 0;
		                 $variables["cantidad"][]   = 0;
                 }//fin else
                 $parametros["torta"] = "no";
                 $this->genera_grafica_1(1, $parametros, $variables);




  }//fin else if

$this->set('opcion', $var1);


}//fin function

























function grafica_21($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){


						$datos  = $this->arrd01->execute(" SELECT DISTINCT substr(a.fecha_solicitud::text,0,5)::integer as ano FROM casd01_solicitud_ayuda a ");
					  	$datos2 = $this->arrd01->execute(" SELECT DISTINCT ano_arranque    FROM ccfd03_instalacion  ORDER BY ano_arranque DESC");
						if(count($datos)!=0){
								foreach($datos as $n){
									$cod[]  = $n[0]['ano'];
									$deno[] = $n[0]['ano'];
								}
								$lista=array_combine($cod, $deno);
							}else{
								$lista=array();
							}
						$this->set("ano_estimacion", $lista);
						$this->set("ano_ejecucion" , '2009');

		                $lista =  $this->arrd01->generateList(null, 'denominacion ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.denominacion');
			            $this->set('vector_presi', $lista);
			            $this->set("cod_presi_seleccion", 1);



  }else if($var1==2){

  	$sql ='';
  	$tipo=0;
	$deno_presi      = '';
	$deno_entidad    = '';
	$deno_tipo_inst  = '';
	$deno_inst       = '';


					  if(!empty($this->data["datos"]["ano_consolidado"])){
					  	if($this->data["datos"]["ano_consolidado"]!="TODO"){
					 				$sql .="substr(fecha_solicitud::text,0,5)::integer ='".$this->data["datos"]["ano_consolidado"]."' ";
					  	}else{
					  		$sql="1=1";
					  	}
					  }else{
							return;
					  }

						if(!empty($this->data["datos"]["cod_presi"])){
                               $lista_1    =  $this->arrd01->findAll("cod_presi=".$this->data["datos"]["cod_presi"]);
						       $deno_presi = $lista_1[0]["arrd01"]["denominacion"];
					           $sql .= " and cod_presi=".$this->data["datos"]["cod_presi"];

					  }
					  if(!empty($this->data["datos"]["cod_entidad"])){
					  	if($this->data["datos"]["cod_entidad"]!="TODO"){
                                  $lista_2      =  $this->arrd02->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]);
						          $deno_entidad = $lista_2[0]["arrd02"]["denominacion"];
					              $sql .=" and cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";
					              $tipo = 1;

					  	}
					  }
					   if(!empty($this->data["datos"]["cod_tipo_inst"])){
					  	if($this->data["datos"]["cod_tipo_inst"]!="TODO"){
                                  $lista_3        =  $this->arrd03->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_tipo_inst=".$this->data["datos"]["cod_tipo_inst"]);
						          $deno_tipo_inst = $lista_3[0]["arrd03"]["denominacion"];
					              $sql .=" and cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";
					              $tipo = 2;

					  	 }
					   }
					  if(!empty($this->data["datos"]["cod_inst"])){
					  	if($this->data["datos"]["cod_inst"]!="TODO"){
                                  $lista_4   =  $this->arrd04->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]." and cod_inst=".$this->data["datos"]["cod_inst"]);
						          $deno_inst = $lista_4[0]["arrd04"]["denominacion"];
					              $sql .=" and cod_inst = '".$this->data["datos"]["cod_inst"]."' ";
					              $tipo = 3;

					  	}
					  }
                           $ver1=$this->v_casp01_relacion_solicitudes->execute("select count(numero_ocacion) as solicitudes,count(numero_documento_ayuda) as ayudas,sum(monto_total) as monto_ayudas from v_casp01_relacion_solicitudes where ".$sql);


												 $parametros["titulo"][]      = "SOLICITUDES Y AYUDAS";
								                 $variables["nombre_total"]   = "Total";
								                 $variables["nombre"][]       = "Atendidas";
								                 $variables["nombre"][]       = "No atendidas";
								                 $parametros["tipo_cantidad"] = true;

												if(!empty($ver1[0][0]['solicitudes'])){
														 $resta=$ver1[0][0]['solicitudes']-$ver1[0][0]['ayudas'];

										                 $variables["porcentaje_total"] = ($ver1[0][0]['solicitudes'] * 100) / $ver1[0][0]['solicitudes'];
										                 $variables["porcentaje"][] = ($ver1[0][0]['ayudas'] * 100) / $ver1[0][0]['solicitudes'];
										                 $variables["porcentaje"][] = ($resta * 100) / $ver1[0][0]['solicitudes'];

                                                         $variables["cantidad_total"]   = $ver1[0][0]['solicitudes'];
										                 $variables["cantidad"][]       = $ver1[0][0]['ayudas'];
										                 $variables["cantidad"][]       = $resta;

										                 $variables["monto_total"]   = 0;
													     $variables["monto"][]       = $ver1[0][0]['monto_ayudas'];
													     $variables["monto"][]       = 0;

													     $parametros["tipo_top"]           = $tipo;
													     $parametros["DENO_REPUBLICA"]     = $deno_presi;
													     $parametros["DENO_ESTADO"]        = $deno_entidad;
													     $parametros["DENO_COD_TIPO_INST"] = $deno_tipo_inst;
													     $parametros["DENO_INST"]          = $deno_inst;

												}else{
													     $variables["porcentaje_total"] = 0;
													     $variables["porcentaje"][] = 0;
													     $variables["porcentaje"][] = 0;

													     $variables["monto_total"]   = 0;
													     $variables["monto"][]       = 0;
													     $variables["monto"][]       = 0;

													     $variables["cantidad_total"]   = 0;
													     $variables["cantidad"][]       = 0;
													     $variables["cantidad"][]       = 0;
												}//fin else



									$this->genera_grafica_1(1, $parametros, $variables);

	}//fin else if

$this->set('opcion', $var1);


}//fin function













function grafica_22($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){


						$datos  = $this->arrd01->execute(" SELECT DISTINCT substr(a.fecha_solicitud::text,0,5)::integer as ano FROM casd01_solicitud_ayuda a ");
					  	$datos2 = $this->arrd01->execute(" SELECT DISTINCT ano_arranque    FROM ccfd03_instalacion  ORDER BY ano_arranque DESC");
						if(count($datos)!=0){
								foreach($datos as $n){
									$cod[]  = $n[0]['ano'];
									$deno[] = $n[0]['ano'];
								}
								$lista=array_combine($cod, $deno);
							}else{
								$lista=array();
							}
						$this->set("ano_estimacion", $lista);
						$this->set("ano_ejecucion" , '2009');

		                $lista =  $this->arrd01->generateList(null, 'denominacion ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.denominacion');
			            $this->set('vector_presi', $lista);
			            $this->set("cod_presi_seleccion", 1);



  }else if($var1==2){

  	$sql ='';
  	$tipo=0;
	$deno_presi      = '';
	$deno_entidad    = '';
	$deno_tipo_inst  = '';
	$deno_inst       = '';


					  if(!empty($this->data["datos"]["ano_consolidado"])){
					  	if($this->data["datos"]["ano_consolidado"]!="TODO"){
					 				$sql .="substr(fecha_solicitud::text,0,5)::integer ='".$this->data["datos"]["ano_consolidado"]."' ";
					  	}else{
					  		$sql="1=1";
					  	}
					  }else{
							return;
					  }
					  if(!empty($this->data["datos"]["cod_presi"])){
                               $lista_1    =  $this->arrd01->findAll("cod_presi=".$this->data["datos"]["cod_presi"]);
						       $deno_presi = $lista_1[0]["arrd01"]["denominacion"];
					           $sql .= " and cod_presi=".$this->data["datos"]["cod_presi"];

					  }
					  if(!empty($this->data["datos"]["cod_entidad"])){
					  	if($this->data["datos"]["cod_entidad"]!="TODO"){
                                  $lista_2      =  $this->arrd02->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]);
						          $deno_entidad = $lista_2[0]["arrd02"]["denominacion"];
					              $sql .=" and cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";
					              $tipo = 1;

					  	}
					  }
					   if(!empty($this->data["datos"]["cod_tipo_inst"])){
					  	if($this->data["datos"]["cod_tipo_inst"]!="TODO"){
                                  $lista_3        =  $this->arrd03->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_tipo_inst=".$this->data["datos"]["cod_tipo_inst"]);
						          $deno_tipo_inst = $lista_3[0]["arrd03"]["denominacion"];
					              $sql .=" and cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";
					              $tipo = 2;

					  	 }
					   }
					  if(!empty($this->data["datos"]["cod_inst"])){
					  	if($this->data["datos"]["cod_inst"]!="TODO"){
                                  $lista_4   =  $this->arrd04->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]." and cod_inst=".$this->data["datos"]["cod_inst"]);
						          $deno_inst = $lista_4[0]["arrd04"]["denominacion"];
					              $sql .=" and cod_inst = '".$this->data["datos"]["cod_inst"]."' ";
					              $tipo = 3;

					  	}
					  }
					    	$sql1=" SELECT
											 cod_tipo_ayuda,
											 quitar_acentos(tipo_ayuda::text) AS denominacion_ayuda,
											 count(numero_ocacion) AS solicitudes,
											 count(numero_documento_ayuda) AS ayudas,
											 sum(monto_total) AS monto_total
											 FROM v_casp01_relacion_solicitudes where ".$sql."
											 GROUP BY
											 cod_tipo_ayuda,
											 quitar_acentos(tipo_ayuda::text)
											 ORDER BY
											 count(numero_ocacion)
											 DESC";
											$ver1=$this->v_casp01_relacion_solicitudes->execute($sql1);
											$sumatoria=$this->v_casp01_relacion_solicitudes->execute("select count(numero_ocacion) as total_solicitudes from v_casp01_relacion_solicitudes where ".$sql);


							                 $parametros["titulo"][]      = "TIPO DE AYUDA SOLICITADA";
							                 $parametros["tipo_cantidad"] = true;
							                 $variables["nombre_total"]   = "Total";

							                 if(!empty($sumatoria[0][0]['total_solicitudes']) && $sumatoria[0][0]['total_solicitudes']!=0){

							                         $variables["monto_total"] = $sumatoria[0][0]['total_solicitudes'];
									                 $variables["porcentaje_total"] = ($sumatoria[0][0]['total_solicitudes'] * 100) / $sumatoria[0][0]['total_solicitudes'];

							                         $cantidad_total = 0;
							                         $monto_total = 0;
							                        for($i=0;$i<count($ver1);$i++){
									                 $variables["porcentaje"][] = (($ver1[$i][0]['solicitudes']*100)/$sumatoria[0][0]['total_solicitudes']);
									                 $variables["cantidad"][]   = $ver1[$i][0]['solicitudes'];
									                 $variables["monto"][]      = $ver1[$i][0]['monto_total'];
									                 $variables["nombre"][]     = $ver1[$i][0]['denominacion_ayuda'];
									                 $cantidad_total += $ver1[$i][0]['solicitudes'];
									                 $monto_total    += $ver1[$i][0]['monto_total'];
									                 $variables["monto_total"]      = $monto_total;
									                 $variables["cantidad_total"]   = $cantidad_total;
							                        }

							                         $parametros["tipo_top"]           = $tipo;
													 $parametros["DENO_REPUBLICA"]     = $deno_presi;
													 $parametros["DENO_ESTADO"]        = $deno_entidad;
													 $parametros["DENO_COD_TIPO_INST"] = $deno_tipo_inst;
													 $parametros["DENO_INST"]          = $deno_inst;
							                 }else{
							                 	     $variables["porcentaje_total"] = 0;
									                 $variables["monto_total"]      = 0;
									                 $variables["cantidad_total"]   = 0;

									                 $variables["porcentaje"][] = 0;
									                 $variables["nombre"][]     = "";
									                 $variables["monto"][]      = 0;
									                 $variables["cantidad"][]   = 0;
							                 }//fin else
							                $parametros["torta"] = "no";


									$this->genera_grafica_1(1, $parametros, $variables);

	}//fin else if

$this->set('opcion', $var1);


}//fin function






function grafica_23($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){

            $datos  = $this->v_cfpd05_tipo_gasto2->execute(" SELECT DISTINCT ano  FROM cfpd05 ");
			$datos2 = $this->arrd01->execute(" SELECT DISTINCT ano_arranque    FROM ccfd03_instalacion  ORDER BY ano_arranque DESC");

						if(count($datos)!=0){
							foreach($datos as $n){
								$cod[]  = $n[0]['ano'];
								$deno[] = $n[0]['ano'];
							}
							$lista=array_combine($cod, $deno);
						}else{
							$lista=array('0'=>'No se encuentra presente en ninguna nomina');
						}

					$this->set("ano_lista", $lista);


			$this->set("ano_ejecucion" , $datos2[0][0]["ano_arranque"]);

			        $lista =  $this->arrd01->generateList(null, 'denominacion ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.denominacion');
		            $this->set('vector_presi', $lista);
		            $this->set("cod_presi_seleccion", 1);

  }else if($var1==2){

	      $sql = "";

	      $group_by = " GROUP BY ";
	      $tipo=0;
	      $deno_presi   = "";
	      $deno_entidad = "";
	      $deno_inst    = "";
	      $deno_tipo_inst = "";

	                                 if(!empty($this->data["datos"]["cod_presi"])){
									  	if($this->data["datos"]["cod_presi"]!="TODO"){
						                          $sql      .= " a.cod_presi = '".$this->data["datos"]["cod_presi"]."' ";
						                          $group_by .= " a.cod_presi";

						                          $lista_1    =  $this->arrd01->findAll("cod_presi=".$this->data["datos"]["cod_presi"]);
						                          $deno_presi = $lista_1[0]["arrd01"]["denominacion"];
									  	}else{
									  		     $sql .= " 1=1 ";

									  	}
									  }

                                      if(!empty($this->data["datos"]["cod_entidad"])){
									  	if($this->data["datos"]["cod_entidad"]!="TODO"){
						                          $sql .=" and a.cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";
						                          $group_by .= ", a.cod_entidad";
						                          $tipo = 1;

						                          $lista_2      =  $this->arrd02->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]);
						                          $deno_entidad = $lista_2[0]["arrd02"]["denominacion"];
									  	}
									  }

                                       if(!empty($this->data["datos"]["cod_tipo_inst"])){
									  	if($this->data["datos"]["cod_tipo_inst"]!="TODO"){
						                          $sql .=" and a.cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";
						                          $group_by .= ", a.cod_tipo_inst";
						                          $tipo = 2;

						                          $lista_3        =  $this->arrd03->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_tipo_inst=".$this->data["datos"]["cod_tipo_inst"]);
						                          $deno_tipo_inst = $lista_3[0]["arrd03"]["denominacion"];
									  	 }
                                       }

                                      if(!empty($this->data["datos"]["cod_inst"])){
									  	if($this->data["datos"]["cod_inst"]!="TODO"){
						                          $sql .=" and a.cod_inst = '".$this->data["datos"]["cod_inst"]."' ";
						                          $group_by .= ", a.cod_inst";
						                          $tipo = 3;

						                          $lista_4   =  $this->arrd04->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]." and cod_inst=".$this->data["datos"]["cod_inst"]);
						                          $deno_inst = $lista_4[0]["arrd04"]["denominacion"];
									  	}
									  }
									  if(!empty($this->data["datos"]["ano_consolidado"])){
									  	if($this->data["datos"]["ano_consolidado"]!="TODO"){
									  		      $year = $this->data["datos"]["ano_consolidado"];
						                          $sql .=" and a.ano_estimacion = '".$this->data["datos"]["ano_consolidado"]."' ";
						                          $group_by .= ", a.ano_estimacion";
									  	}
									  }
									  if(!empty($this->data["datos"]["tipo_presupuesto"])){
									  	             $tipo_presupuesto_aux = $this->data["datos"]["tipo_presupuesto"];
							  	             if($this->data["datos"]["tipo_presupuesto"]==6){
											   	 $sql_aux = "";
											   }else{
											   	 $sql_aux = " and tipo_recurso = ".$this->data["datos"]["tipo_presupuesto"];
											   }
									   }




                 $condicion        = $sql.$sql_aux.$group_by;


                 $this->layout="ajax";


    				$datos = $this->cfpd07_plan_inversion->execute("SELECT
													    					 SUM(a.estimado_presu)    as  asignacion_total,
																	 	     SUM(a.monto_contratado)  as  monto_presupuestado,
																	 	     SUM(a.aumento_obras)     as  aumento_obras,
																	 	     SUM(a.disminucion_obras) as  disminucion_obras

																    FROM cfpd07_obras_cuerpo a where ".$condicion." ;");



                       if($tipo_presupuesto_aux==1){  $opcion1_aux = "TIPO DEL RECURSO: Ordinario,";
				 }else if($tipo_presupuesto_aux==2){  $opcion1_aux = "TIPO DEL RECURSO: Coordinado,";
				 }else if($tipo_presupuesto_aux==3){  $opcion1_aux = "TIPO DEL RECURSO: Laee,";
				 }else if($tipo_presupuesto_aux==4){  $opcion1_aux = "TIPO DEL RECURSO: Fides,";
				 }else if($tipo_presupuesto_aux==5){  $opcion1_aux = "TIPO DEL RECURSO: Ingreso Extraordinario,";
				 }else if($tipo_presupuesto_aux==6){  $opcion1_aux = ""; }
				 $parametros["titulo"][]  = "CONTRATADO VS POR CONTRATAR";
				 $parametros["titulo"][]  = $opcion1_aux." AÑO RECURSO: ".$year;
                 $variables["nombre_total"] = "Total presupuestado";
                 $variables["nombre"][] = "Monto contratado";
                 $variables["nombre"][] = "Monto por contratar";
                 if(isset($datos[0][0]['asignacion_total'])){
		                 $total_presupuestado = ($datos[0][0]['asignacion_total'] + $datos[0][0]['aumento_obras']) -  $datos[0][0]['disminucion_obras'];
		                 $monto_contratado    = ($datos[0][0]['monto_presupuestado'] + $datos[0][0]['aumento_obras']) -  $datos[0][0]['disminucion_obras'];
		                 $diferencia          =  $total_presupuestado - $monto_contratado;

		                 $variables["porcentaje_total"] = ($total_presupuestado * 100) / $total_presupuestado;
		                 $variables["porcentaje"][] = ($monto_contratado * 100) / $total_presupuestado;
		                 $variables["porcentaje"][] = ($diferencia * 100) / $total_presupuestado;

		                 $variables["monto_total"] = $total_presupuestado;
		                 $variables["monto"][] = $monto_contratado;
		                 $variables["monto"][] = $diferencia;

		                 $parametros["tipo_top"]           = $tipo;
						 $parametros["DENO_REPUBLICA"]     = $deno_presi;
						 $parametros["DENO_ESTADO"]        = $deno_entidad;
						 $parametros["DENO_COD_TIPO_INST"] = $deno_tipo_inst;
						 $parametros["DENO_INST"]          = $deno_inst;
                 }else{
                 	     $variables["porcentaje_total"] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["monto_total"]  = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
                 }//fin else
                 $this->genera_grafica_1(1, $parametros, $variables);



  }//fin else if

$this->set('opcion', $var1);


}//fin function















function grafica_24($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){

            $datos  = $this->v_cfpd05_tipo_gasto2->execute(" SELECT DISTINCT ano  FROM cfpd05 ");
			$datos2 = $this->arrd01->execute(" SELECT DISTINCT ano_arranque    FROM ccfd03_instalacion  ORDER BY ano_arranque DESC");

						if(count($datos)!=0){
							foreach($datos as $n){
								$cod[]  = $n[0]['ano'];
								$deno[] = $n[0]['ano'];
							}
							$lista=array_combine($cod, $deno);
						}else{
							$lista=array('0'=>'No se encuentra presente en ninguna nomina');
						}

					$this->set("ano_lista", $lista);


			$this->set("ano_ejecucion" , $datos2[0][0]["ano_arranque"]);

			        $lista =  $this->arrd01->generateList(null, 'denominacion ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.denominacion');
		            $this->set('vector_presi', $lista);
		            $this->set("cod_presi_seleccion", 1);

  }else if($var1==2){

	      $sql = "";

	      $group_by = " GROUP BY ";
	      $tipo=0;
	      $deno_presi   = "";
	      $deno_entidad = "";
	      $deno_inst    = "";
	      $deno_tipo_inst = "";

	                                 if(!empty($this->data["datos"]["cod_presi"])){
									  	if($this->data["datos"]["cod_presi"]!="TODO"){
						                          $sql      .= " a.cod_presi = '".$this->data["datos"]["cod_presi"]."' ";
						                          $group_by .= " a.cod_presi";

						                          $lista_1    =  $this->arrd01->findAll("cod_presi=".$this->data["datos"]["cod_presi"]);
						                          $deno_presi = $lista_1[0]["arrd01"]["denominacion"];
									  	}else{
									  		     $sql .= " 1=1 ";

									  	}
									  }

                                      if(!empty($this->data["datos"]["cod_entidad"])){
									  	if($this->data["datos"]["cod_entidad"]!="TODO"){
						                          $sql .=" and a.cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";
						                          $group_by .= ", a.cod_entidad";
						                          $tipo = 1;

						                          $lista_2      =  $this->arrd02->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]);
						                          $deno_entidad = $lista_2[0]["arrd02"]["denominacion"];
									  	}
									  }

                                       if(!empty($this->data["datos"]["cod_tipo_inst"])){
									  	if($this->data["datos"]["cod_tipo_inst"]!="TODO"){
						                          $sql .=" and a.cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";
						                          $group_by .= ", a.cod_tipo_inst";
						                          $tipo = 2;

						                          $lista_3        =  $this->arrd03->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_tipo_inst=".$this->data["datos"]["cod_tipo_inst"]);
						                          $deno_tipo_inst = $lista_3[0]["arrd03"]["denominacion"];
									  	 }
                                       }

                                      if(!empty($this->data["datos"]["cod_inst"])){
									  	if($this->data["datos"]["cod_inst"]!="TODO"){
						                          $sql .=" and a.cod_inst = '".$this->data["datos"]["cod_inst"]."' ";
						                          $group_by .= ", a.cod_inst";
						                          $tipo = 3;

						                          $lista_4   =  $this->arrd04->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]." and cod_inst=".$this->data["datos"]["cod_inst"]);
						                          $deno_inst = $lista_4[0]["arrd04"]["denominacion"];
									  	}
									  }
									  if(!empty($this->data["datos"]["ano_consolidado"])){
									  	if($this->data["datos"]["ano_consolidado"]!="TODO"){
									  		      $year = $this->data["datos"]["ano_consolidado"];
						                          $sql .=" and a.ano_estimacion = '".$this->data["datos"]["ano_consolidado"]."' ";
						                          $group_by .= ", a.ano_estimacion";
									  	}
									  }
									  if(!empty($this->data["datos"]["tipo_presupuesto"])){
									  	             $tipo_presupuesto_aux = $this->data["datos"]["tipo_presupuesto"];
							  	             if($this->data["datos"]["tipo_presupuesto"]==6){
											   	 $sql_aux = "";
											   }else{
											   	 $sql_aux = " and tipo_recurso = ".$this->data["datos"]["tipo_presupuesto"];
											   }
									   }

                    $condicion  = $sql.$sql_aux." GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano_estimacion, a.cod_obra";
    				$datos = $this->cfpd07_plan_inversion->execute("SELECT
    					 a.cod_presi,
    					 a.cod_entidad,
    					 a.cod_tipo_inst,
    					 a.cod_inst,
    					 a.ano_estimacion,
    					 a.cod_obra,
				 	     SUM(a.estimado_presu)    as  asignacion_total,
				 	     SUM(a.monto_contratado)  as  monto_presupuestado,
				 	     SUM(a.aumento_obras)     as  aumento_obras,
				 	     SUM(a.disminucion_obras) as  disminucion_obras,
                         (SELECT SUM(monto_anticipo)     FROM  cobd01_contratoobras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.ano_estimacion=a.ano_estimacion  and x.condicion_actividad=1 and x.cod_obra=a.cod_obra) as monto_anticipo,
	                     (SELECT SUM(monto_cancelado)    FROM  cobd01_contratoobras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.ano_estimacion=a.ano_estimacion  and x.condicion_actividad=1 and x.cod_obra=a.cod_obra) as monto_cancelado,
	                     (SELECT SUM(monto_amortizacion) FROM  cobd01_contratoobras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.ano_estimacion=a.ano_estimacion  and x.condicion_actividad=1 and x.cod_obra=a.cod_obra) as monto_amortizacion,
                         (SELECT SUM(monto_contratado)   FROM  cfpd07_obras_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_obra=a.cod_obra and x.ano_estimacion=a.ano_estimacion) as monto_contratado

				 		      FROM cfpd07_obras_cuerpo a where ".$condicion."; ");

                       if($tipo_presupuesto_aux==1){  $opcion1_aux = "TIPO DEL RECURSO: Ordinario,";
				 }else if($tipo_presupuesto_aux==2){  $opcion1_aux = "TIPO DEL RECURSO: Coordinado,";
				 }else if($tipo_presupuesto_aux==3){  $opcion1_aux = "TIPO DEL RECURSO: Laee,";
				 }else if($tipo_presupuesto_aux==4){  $opcion1_aux = "TIPO DEL RECURSO: Fides,";
				 }else if($tipo_presupuesto_aux==5){  $opcion1_aux = "TIPO DEL RECURSO: Ingreso Extraordinario,";
				 }else if($tipo_presupuesto_aux==6){  $opcion1_aux = ""; }

				 $parametros["titulo"][]  = "CONTRATADO VS PAGADO";
				 $parametros["titulo"][]  = $opcion1_aux." AÑO RECURSO: ".$year;
                 $variables["nombre_total"] = "Total Contratado";
                 $variables["nombre"][] = "Total Pagado";
                 $variables["nombre"][] = "Total por pagar";
                 if(isset($datos[0][0]['monto_contratado']) && $datos[0][0]['monto_contratado']!=0){

		                 $vara = ($datos[0][0]['monto_contratado']);
						 $varb = ($datos[0][0]['monto_anticipo']    + $datos[0][0]['monto_cancelado']) -  $datos[0][0]['monto_amortizacion'];

		                 $total_contratado = $vara;
		                 $pagado           = $varb;
		                 $por_pagar        = $vara - $varb;

		                 $variables["porcentaje_total"] = ($total_contratado * 100) / $total_contratado;
		                 $variables["porcentaje"][] = ($pagado * 100) / $total_contratado;
		                 $variables["porcentaje"][] = ($por_pagar * 100) / $total_contratado;

		                 $variables["monto_total"] = $total_contratado;
		                 $variables["monto"][] = $pagado;
		                 $variables["monto"][] = $por_pagar;

		                 $parametros["tipo_top"]           = $tipo;
						 $parametros["DENO_REPUBLICA"]     = $deno_presi;
						 $parametros["DENO_ESTADO"]        = $deno_entidad;
						 $parametros["DENO_COD_TIPO_INST"] = $deno_tipo_inst;
						 $parametros["DENO_INST"]          = $deno_inst;
                 }else{
                 	     $variables["porcentaje_total"] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["monto_total"]  = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
                 }//fin else
                 $this->genera_grafica_1(1, $parametros, $variables);





  }//fin else if

$this->set('opcion', $var1);


}//fin function















function grafica_25($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){

                    $tipo_impuesto=array(1=>'PATENTE DE INDUSTRIA Y COMERCIO',2=>'VEHÍCULOS',3=>'PROPAGANDA COMERCIAL',4=>'INMUEBLES URBANOS',5=>'ASEO DOMICILIARIO',6=>'ARRENDAMIENTO DE TIERRAS',7=>'CRÉDITOS DE VIVIENDA');
                    $this->set('tipo_impuesto',  $tipo_impuesto);

                    $datos  = $this->v_shd900_cobranza_acumulada_deno_part->execute(" SELECT DISTINCT ano FROM v_shd900_cobranza_acumulada_denominacion_partida  ORDER BY ano ASC");
					if(count($datos)!=0){
						foreach($datos as $n){
							$lista[$n[0]['ano']]=$n[0]['ano'];
					    }
					}else{
						$lista=array();
					}
					$this->set("ano_lista", $lista);


		            $datos2 = $this->arrd01->execute(" SELECT DISTINCT ano_arranque    FROM ccfd03_instalacion  ORDER BY ano_arranque DESC");
		            $this->set("ano_ejecucion" , $datos2[0][0]["ano_arranque"]);

		            $lista =  $this->arrd01->generateList(null, 'denominacion ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.denominacion');
		            $this->set('vector_presi', $lista);
		            $this->set("cod_presi_seleccion", 1);

  }else if($var1==2){

	      $sql    = " ";
	      $sql2   = " ";

	      $campos    = "  ";
          $group_by  = " GROUP BY ";
          $group_by2 = " GROUP BY ";
	      $tipo=0;
	      $deno_presi   = "";
	      $deno_entidad = "";
	      $deno_inst    = "";
	      $deno_tipo_inst = "";
	      $cod_partida_aux = "";



	                                 if(!empty($this->data["datos"]["cod_presi"])){
									  	if($this->data["datos"]["cod_presi"]!="TODO"){
						                          $sql       .= " a.cod_presi = '".$this->data["datos"]["cod_presi"]."' ";
						                          $sql2      .= " b.cod_presi = '".$this->data["datos"]["cod_presi"]."' ";
						                          $campos    .= " a.cod_presi";
						                          $group_by  .= " a.cod_presi";
						                          $group_by2 .= " b.cod_presi";

						                          $lista_1    =  $this->arrd01->findAll("cod_presi=".$this->data["datos"]["cod_presi"]);
						                          $deno_presi = $lista_1[0]["arrd01"]["denominacion"];
									  	}else{
									  		     $sql  .= " 1=1 ";
									  		     $sql2 .= " 1=1 ";

									  	}
									  }

                                      if(!empty($this->data["datos"]["cod_entidad"])){
									  	if($this->data["datos"]["cod_entidad"]!="TODO"){
						                          $sql  .=" and a.cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";
						                          $sql2 .=" and b.cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";
						                          $campos    .= ", a.cod_entidad";
						                          $group_by  .= ", a.cod_entidad";
						                          $group_by2 .= ", b.cod_entidad";
						                          $tipo = 1;

						                          $lista_2      =  $this->arrd02->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]);
						                          $deno_entidad = $lista_2[0]["arrd02"]["denominacion"];
									  	}
									  }

                                       if(!empty($this->data["datos"]["cod_tipo_inst"])){
									  	if($this->data["datos"]["cod_tipo_inst"]!="TODO"){
						                          $sql  .=" and a.cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";
						                          $sql2 .=" and b.cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";
						                          $campos    .= ", a.cod_tipo_inst";
						                          $group_by  .= ", a.cod_tipo_inst";
						                          $group_by2 .= ", b.cod_tipo_inst";
						                          $tipo = 2;

						                          $lista_3        =  $this->arrd03->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_tipo_inst=".$this->data["datos"]["cod_tipo_inst"]);
						                          $deno_tipo_inst = $lista_3[0]["arrd03"]["denominacion"];
									  	 }
                                       }

                                      if(!empty($this->data["datos"]["cod_inst"])){
									  	if($this->data["datos"]["cod_inst"]!="TODO"){
						                          $sql  .=" and a.cod_inst = '".$this->data["datos"]["cod_inst"]."' ";
						                          $sql2 .=" and b.cod_inst = '".$this->data["datos"]["cod_inst"]."' ";
						                          $campos    .= ", a.cod_inst";
						                          $group_by  .= ", a.cod_inst";
						                          $group_by2 .= ", b.cod_inst";
						                          $tipo = 3;

						                          $lista_4   =  $this->arrd04->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]." and cod_inst=".$this->data["datos"]["cod_inst"]);
						                          $deno_inst = $lista_4[0]["arrd04"]["denominacion"];
									  	}
									  }
									  if(!empty($this->data["datos"]["ano_consolidado"])){
									  	if($this->data["datos"]["ano_consolidado"]!="TODO"){
						                          $sql  .=" and a.ano = '".$this->data["datos"]["ano_consolidado"]."' ";
						                          $sql2 .=" and b.ano = '".$this->data["datos"]["ano_consolidado"]."' ";
						                          $campos    .= ", a.ano";
						                          $group_by  .= ", a.ano";
						                          $group_by2 .= ", b.ano";
									  	}
									  }

             if($this->data['datos']['tipo_impuesto']==1){
              $sql          .= "";
              $sql2         .= "";
              $this->set('tipo_impuesto', 0);
              $denominacion_impuesto = "";
            }else if($this->data['datos']['tipo_impuesto']==2){
           	 if(!empty($this->data['datos']['impuesto'])){
           	  $impuesto = split("-",$this->data['datos']['impuesto']);
              $sql          .= " and a.cod_partida='".$impuesto[0]."' and a.cod_generica='".$impuesto[1]."' and a.cod_especifica='".$impuesto[2]."' and a.cod_sub_espec='".$impuesto[3]."' and a.cod_auxiliar='".$impuesto[4]."'";
              $sql2         .= " and b.cod_partida='".$impuesto[0]."' and b.cod_generica='".$impuesto[1]."' and b.cod_especifica='".$impuesto[2]."' and b.cod_sub_espec='".$impuesto[3]."' and b.cod_auxiliar='".$impuesto[4]."'";
              $sql2_aux      = " and a.cod_partida='".$impuesto[0]."' and a.cod_generica='".$impuesto[1]."' and a.cod_especifica='".$impuesto[2]."' and a.cod_sub_espec='".$impuesto[3]."' and a.cod_auxiliar='".$impuesto[4]."'";
              $this->set('tipo_impuesto', $this->data['datos']['impuesto']);
              $datos_aux  = $this->v_shd900_cobranza_acumulada_deno_part->execute(" SELECT DISTINCT a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.deno_partida, a.deno_generica, a.deno_especifica, a.deno_sub_espe, a.deno_auxiliar FROM v_shd900_cobranza_acumulada_denominacion_partida a  WHERE ".$sql." ".$sql2_aux." ORDER BY a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar ASC");
			  $deno_partida    = $datos_aux[0][0]['deno_partida'];
			  $deno_generica   = $datos_aux[0][0]['deno_generica'];
			  $deno_especifica = $datos_aux[0][0]['deno_especifica'];
			  $deno_sub_espe   = $datos_aux[0][0]['deno_sub_espe'];
			  $deno_auxiliar   = $datos_aux[0][0]['deno_auxiliar'];

										  if($deno_auxiliar==null || $deno_auxiliar==""){
										  	 if($deno_sub_espe==null || $deno_sub_espe==""){
										  	 	if($deno_especifica==null || $deno_especifica==""){
										  	 		if($deno_generica==null || $deno_generica==""){
										  	 			if($deno_partida==null || $deno_partida==""){
								                               $denominacion_impuesto = "";
														  }else{
														  	 $denominacion_impuesto = $datos_aux[0][0]['deno_partida'];
														  }
													  }else{
													  	 $denominacion_impuesto = $datos_aux[0][0]['deno_generica'];
													  }
												  }else{
												  	 $denominacion_impuesto = $datos_aux[0][0]['deno_especifica'];
												  }
											  }else{
											  	 $denominacion_impuesto = $datos_aux[0][0]['deno_sub_espe'];
											  }
										  }else{
										  	 $denominacion_impuesto = $datos_aux[0][0]['deno_auxiliar'];
										  }
           	 }else{
           	  $denominacion_impuesto = "";
           	  $this->set('tipo_impuesto', 0);
           	 }
            }



            $sql1 ="SELECT
        		 ".$campos.",";
		    $sql1.=" SUM((a.deuda_vigente+a.monto_recargo+a.monto_multa+a.monto_intereses)-a.monto_descuento) as monto_total, ";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where  ".$sql2." and b.mes=1 ".$group_by2.")  as mes_1,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where  ".$sql2." and b.mes=2 ".$group_by2.")  as mes_2,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where  ".$sql2." and b.mes=3 ".$group_by2.")  as mes_3,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where  ".$sql2." and b.mes=4 ".$group_by2.")  as mes_4,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where  ".$sql2." and b.mes=5 ".$group_by2.")  as mes_5,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where  ".$sql2." and b.mes=6 ".$group_by2.")  as mes_6,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where  ".$sql2." and b.mes=7 ".$group_by2.")  as mes_7,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where  ".$sql2." and b.mes=8 ".$group_by2.")  as mes_8,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where  ".$sql2." and b.mes=9 ".$group_by2.")  as mes_9,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where  ".$sql2." and b.mes=10 ".$group_by2.") as mes_10,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where  ".$sql2." and b.mes=11 ".$group_by2.") as mes_11,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where  ".$sql2." and b.mes=12 ".$group_by2.") as mes_12";
         $sql1 .= " FROM v_shd900_cobranza_acumulada_denominacion_partida a WHERE ".$sql." ".$group_by." ";


		$ejecuta=$this->v_shd900_cobranza_acumulada_deno_part->execute($sql1);
		$datos  = $ejecuta;
		$this->set('deno',$denominacion_impuesto);

				 $parametros["titulo"][]  = "COBRANZA";
				 if($denominacion_impuesto!=""){
				 	 $parametros["titulo"][] = $denominacion_impuesto;
				 }
				 $variables["nombre_total"] = "Total";
				 $variables["nombre"][] = "Enero";
				 $variables["nombre"][] = "Febrero";
				 $variables["nombre"][] = "Marzo";
				 $variables["nombre"][] = "Abril";
				 $variables["nombre"][] = "Mayo";
				 $variables["nombre"][] = "Junio";
				 $variables["nombre"][] = "Julio";
				 $variables["nombre"][] = "Agosto";
				 $variables["nombre"][] = "Septiembre";
				 $variables["nombre"][] = "Octubre";
				 $variables["nombre"][] = "Noviembre";
				 $variables["nombre"][] = "Diciembre";

                 if(isset($datos[0][0]["monto_total"])){

					if($datos[0][0]["mes_1"]!=0  && $datos[0][0]["mes_1"]!=null){$variables["monto"][]    = $datos[0][0]["mes_1"];}else{$variables["monto"][]=0;}
					if($datos[0][0]["mes_2"]!=0  && $datos[0][0]["mes_2"]!=null){$variables["monto"][]    = $datos[0][0]["mes_2"];}else{$variables["monto"][]=0;}
					if($datos[0][0]["mes_3"]!=0  && $datos[0][0]["mes_3"]!=null){$variables["monto"][]    = $datos[0][0]["mes_3"];}else{$variables["monto"][]=0;}
					if($datos[0][0]["mes_4"]!=0  && $datos[0][0]["mes_4"]!=null){$variables["monto"][]    = $datos[0][0]["mes_4"];}else{$variables["monto"][]=0;}
					if($datos[0][0]["mes_5"]!=0  && $datos[0][0]["mes_5"]!=null){$variables["monto"][]    = $datos[0][0]["mes_5"];}else{$variables["monto"][]=0;}
					if($datos[0][0]["mes_6"]!=0  && $datos[0][0]["mes_6"]!=null){$variables["monto"][]    = $datos[0][0]["mes_6"];}else{$variables["monto"][]=0;}
					if($datos[0][0]["mes_7"]!=0  && $datos[0][0]["mes_7"]!=null){$variables["monto"][]    = $datos[0][0]["mes_7"];}else{$variables["monto"][]=0;}
					if($datos[0][0]["mes_8"]!=0  && $datos[0][0]["mes_8"]!=null){$variables["monto"][]    = $datos[0][0]["mes_8"];}else{$variables["monto"][]=0;}
					if($datos[0][0]["mes_9"]!=0  && $datos[0][0]["mes_9"]!=null){$variables["monto"][]    = $datos[0][0]["mes_9"];}else{$variables["monto"][]=0;}
					if($datos[0][0]["mes_10"]!=0 && $datos[0][0]["mes_10"]!=null){$variables["monto"][]  = $datos[0][0]["mes_10"];}else{$variables["monto"][]=0;}
					if($datos[0][0]["mes_11"]!=0 && $datos[0][0]["mes_11"]!=null){$variables["monto"][]  = $datos[0][0]["mes_11"];}else{$variables["monto"][]=0;}
					if($datos[0][0]["mes_12"]!=0 && $datos[0][0]["mes_12"]!=null){$variables["monto"][]  = $datos[0][0]["mes_12"];}else{$variables["monto"][]=0;}
					if($datos[0][0]["monto_total"]!=0 && $datos[0][0]["monto_total"]!=null){$variables["monto_total"]  = $datos[0][0]["monto_total"];}else{$variables["monto_total"]=0;}


					$variables["porcentaje"][] = ($variables["monto"][0] * 100) /  $datos[0][0]["monto_total"];
					$variables["porcentaje"][] = ($variables["monto"][1] * 100) /  $datos[0][0]["monto_total"];
					$variables["porcentaje"][] = ($variables["monto"][2] * 100) /  $datos[0][0]["monto_total"];
					$variables["porcentaje"][] = ($variables["monto"][3] * 100) /  $datos[0][0]["monto_total"];
					$variables["porcentaje"][] = ($variables["monto"][4] * 100) /  $datos[0][0]["monto_total"];
					$variables["porcentaje"][] = ($variables["monto"][5] * 100) /  $datos[0][0]["monto_total"];
					$variables["porcentaje"][] = ($variables["monto"][6] * 100) /  $datos[0][0]["monto_total"];
					$variables["porcentaje"][] = ($variables["monto"][7] * 100) /  $datos[0][0]["monto_total"];
					$variables["porcentaje"][] = ($variables["monto"][8] * 100) /  $datos[0][0]["monto_total"];
					$variables["porcentaje"][] = ($variables["monto"][9] * 100) /  $datos[0][0]["monto_total"];
					$variables["porcentaje"][] = ($variables["monto"][10] * 100) /  $datos[0][0]["monto_total"];
					$variables["porcentaje"][] = ($variables["monto"][11] * 100) /  $datos[0][0]["monto_total"];
					$variables["porcentaje_total"]  = ($variables["monto_total"] * 100) /  $datos[0][0]["monto_total"];

					     $parametros["tipo_top"]           = $tipo;
						 $parametros["DENO_REPUBLICA"]     = $deno_presi;
						 $parametros["DENO_ESTADO"]        = $deno_entidad;
						 $parametros["DENO_COD_TIPO_INST"] = $deno_tipo_inst;
						 $parametros["DENO_INST"]          = $deno_inst;

			      }else{

					$variables["porcentaje"][] = 0;
					$variables["porcentaje"][] = 0;
					$variables["porcentaje"][] = 0;
					$variables["porcentaje"][] = 0;
					$variables["porcentaje"][] = 0;
					$variables["porcentaje"][] = 0;
					$variables["porcentaje"][] = 0;
					$variables["porcentaje"][] = 0;
					$variables["porcentaje"][] = 0;
					$variables["porcentaje"][] = 0;
					$variables["porcentaje"][] = 0;
					$variables["porcentaje"][] = 0;
					$variables["porcentaje_total"]  = 0;

					$variables["monto"][] = 0;
					$variables["monto"][] = 0;
					$variables["monto"][] = 0;
					$variables["monto"][] = 0;
					$variables["monto"][] = 0;
					$variables["monto"][] = 0;
					$variables["monto"][] = 0;
					$variables["monto"][] = 0;
					$variables["monto"][] = 0;
					$variables["monto"][] = 0;
					$variables["monto"][] = 0;
					$variables["monto"][] = 0;
					$variables["monto_total"]  = 0;

			      }//fin else


                 $parametros["torta"] = "no";
                 $this->genera_grafica_1(1, $parametros, $variables);





  }//fin else if

$this->set('opcion', $var1);


}//fin function







function grafica_26($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){
				      $datos  = $this->v_cfpd05_tipo_gasto2->execute(" SELECT DISTINCT ano  FROM cfpd05 ");
			          $datos2 = $this->arrd01->execute(" SELECT DISTINCT ano_arranque    FROM ccfd03_instalacion  ORDER BY ano_arranque DESC");

						if(count($datos)!=0){
							foreach($datos as $n){
								$cod[]  = $n[0]['ano'];
								$deno[] = $n[0]['ano'];
							}
							$lista=array_combine($cod, $deno);
						}else{
							$lista=array('0'=>'No se encuentra presente en ninguna nomina');
						}

					$this->set("ano_lista", $lista);
			        $this->set("ano_ejecucion" , $datos2[0][0]["ano_arranque"]);

			        $lista =  $this->arrd01->generateList(null, 'denominacion ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.denominacion');
		            $this->set('vector_presi', $lista);
		            $this->set("cod_presi_seleccion", 1);

	}else if($var1==2){
		  $sql = "";
	      $group_by = " GROUP BY ";
	      $tipo=0;
	      $deno_presi   = "";
	      $deno_entidad = "";
	      $deno_inst    = "";
	      $deno_tipo_inst = "";

	                                 if(!empty($this->data["datos"]["cod_presi"])){
									  	if($this->data["datos"]["cod_presi"]!="TODO"){
						                          $sql      .= " cod_presi = '".$this->data["datos"]["cod_presi"]."' ";
						                          $group_by .= " cod_presi";

						                          $lista_1    =  $this->arrd01->findAll("cod_presi=".$this->data["datos"]["cod_presi"]);
						                          $deno_presi = $lista_1[0]["arrd01"]["denominacion"];
									  	}else{
									  		     $sql .= " 1=1 ";

									  	}
									  }

                                      if(!empty($this->data["datos"]["cod_entidad"])){
									  	if($this->data["datos"]["cod_entidad"]!="TODO"){
						                          $sql .=" and cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";
						                          $group_by .= ", cod_entidad";
						                          $tipo = 1;

						                          $lista_2      =  $this->arrd02->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]);
						                          $deno_entidad = $lista_2[0]["arrd02"]["denominacion"];
									  	}
									  }

                                       if(!empty($this->data["datos"]["cod_tipo_inst"])){
									  	if($this->data["datos"]["cod_tipo_inst"]!="TODO"){
						                          $sql .=" and cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";
						                          $group_by .= ", cod_tipo_inst";
						                          $tipo = 2;

						                          $lista_3        =  $this->arrd03->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_tipo_inst=".$this->data["datos"]["cod_tipo_inst"]);
						                          $deno_tipo_inst = $lista_3[0]["arrd03"]["denominacion"];
									  	 }
                                       }

                                      if(!empty($this->data["datos"]["cod_inst"])){
									  	if($this->data["datos"]["cod_inst"]!="TODO"){
						                          $sql .=" and cod_inst = '".$this->data["datos"]["cod_inst"]."' ";
						                          $group_by .= ", cod_inst";
						                          $tipo = 3;

						                          $lista_4   =  $this->arrd04->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]." and cod_inst=".$this->data["datos"]["cod_inst"]);
						                          $deno_inst = $lista_4[0]["arrd04"]["denominacion"];
									  	}
									  }
									  if(!empty($this->data["datos"]["ano_consolidado"])){
									  	if($this->data["datos"]["ano_consolidado"]!="TODO"){
									  		      $year = $this->data["datos"]["ano_consolidado"];
						                          $sql .=" and ano = '".$this->data["datos"]["ano_consolidado"]."' ";
						                          $group_by .= ", ano";
									  	}
									  }

							$condicion  = $sql.$group_by;
				            $fields     = "SUM(gasto_inversion) as gasto_inversion, SUM(gasto_corriente) as gasto_corriente, SUM(total) as total";

							$datos = $this->v_cfpd05_tipo_gasto2->findAll($condicion, $fields, $order = null, $limit = null, $page = null, $recursive = null);
                         foreach($datos as $row){
						 	$gasto_inversion = $row[0]['gasto_inversion'];
						 	$gasto_corriente = $row[0]['gasto_corriente'];
						 	$total = $row[0]['total'];
						 }

				 $parametros["titulo"][]  = "TIPOS DE GASTOS";
				 $parametros["titulo"][]  = " AÑO RECURSO: ".$year;
                 $variables["nombre_total"] = "Gasto Total";
                 $variables["nombre"][] = "Gasto Corriente";
                 $variables["nombre"][] = "Gasto Inversión";

                 if(isset($datos[0][0]['total'])){
		                 $variables["porcentaje_total"] = ($total * 100) / $total;
		                 $variables["porcentaje"][] = ($gasto_corriente * 100) / $total;
		                 $variables["porcentaje"][] = ($gasto_inversion * 100) / $total;
		                 $variables["monto_total"] = $total;
		                 $variables["monto"][] = $gasto_corriente;
		                 $variables["monto"][] = $gasto_inversion;

		                 $parametros["tipo_top"]           = $tipo;
						 $parametros["DENO_REPUBLICA"]     = $deno_presi;
						 $parametros["DENO_ESTADO"]        = $deno_entidad;
						 $parametros["DENO_COD_TIPO_INST"] = $deno_tipo_inst;
						 $parametros["DENO_INST"]          = $deno_inst;
                 }else{
                 	     $variables["porcentaje_total"] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["monto_total"]  = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
                 }//fin else
                 $this->genera_grafica_1(1, $parametros, $variables);

	}//fin else if

$this->set('opcion', $var1);


}//fin function



















function grafica_27($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){
				      $datos  = $this->v_cfpd05_tipo_gasto2->execute(" SELECT DISTINCT ano  FROM cfpd05 ");
			          $datos2 = $this->arrd01->execute(" SELECT DISTINCT ano_arranque    FROM ccfd03_instalacion  ORDER BY ano_arranque DESC");

						if(count($datos)!=0){
							foreach($datos as $n){
								$cod[]  = $n[0]['ano'];
								$deno[] = $n[0]['ano'];
							}
							$lista=array_combine($cod, $deno);
						}else{
							$lista=array('0'=>'No se encuentra presente en ninguna nomina');
						}

					$this->set("ano_lista", $lista);


			        $this->set("ano_ejecucion" , $datos2[0][0]["ano_arranque"]);
			        $lista =  $this->arrd01->generateList(null, 'denominacion ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.denominacion');
		            $this->set('vector_presi', $lista);
		            $this->set("cod_presi_seleccion", 1);

	}else if($var1==2){
                      $sql = "";

	      $group_by = " GROUP BY ";
	      $tipo=0;
	      $deno_presi   = "";
	      $deno_entidad = "";
	      $deno_inst    = "";
	      $deno_tipo_inst = "";

	      $sql_year_sub   = "";
	      $sql_year_sub_2 = "";
	      $sql_where_sub  = "";

	                                 if(!empty($this->data["datos"]["cod_presi"])){
									  	if($this->data["datos"]["cod_presi"]!="TODO"){
						                          $sql      .= " a.cod_presi = '".$this->data["datos"]["cod_presi"]."' ";
						                          $group_by .= " a.cod_presi";
						                          $sql_where_sub .= " x.cod_presi=a.cod_presi and ";

						                          $lista_1    =  $this->arrd01->findAll("cod_presi=".$this->data["datos"]["cod_presi"]);
						                          $deno_presi = $lista_1[0]["arrd01"]["denominacion"];
									  	}else{
									  		     $sql .= " 1=1 ";

									  	}
									  }

                                      if(!empty($this->data["datos"]["cod_entidad"])){
									  	if($this->data["datos"]["cod_entidad"]!="TODO"){
						                          $sql .=" and a.cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";
						                          $group_by .= ", a.cod_entidad";
						                          $tipo = 1;
						                          $sql_where_sub .= " x.cod_entidad=a.cod_entidad and ";

						                          $lista_2      =  $this->arrd02->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]);
						                          $deno_entidad = $lista_2[0]["arrd02"]["denominacion"];
									  	}
									  }

                                       if(!empty($this->data["datos"]["cod_tipo_inst"])){
									  	if($this->data["datos"]["cod_tipo_inst"]!="TODO"){
						                          $sql .=" and a.cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";
						                          $group_by .= ", a.cod_tipo_inst";
						                          $tipo = 2;
						                          $sql_where_sub .= " x.cod_tipo_inst=a.cod_tipo_inst and ";

						                          $lista_3        =  $this->arrd03->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_tipo_inst=".$this->data["datos"]["cod_tipo_inst"]);
						                          $deno_tipo_inst = $lista_3[0]["arrd03"]["denominacion"];
									  	 }
                                       }

                                      if(!empty($this->data["datos"]["cod_inst"])){
									  	if($this->data["datos"]["cod_inst"]!="TODO"){
						                          $sql .=" and a.cod_inst = '".$this->data["datos"]["cod_inst"]."' ";
						                          $group_by .= ", a.cod_inst";
						                          $tipo = 3;
						                          $sql_where_sub .= " x.cod_inst=a.cod_inst and ";

						                          $lista_4   =  $this->arrd04->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]." and cod_inst=".$this->data["datos"]["cod_inst"]);
						                          $deno_inst = $lista_4[0]["arrd04"]["denominacion"];
									  	}
									  }
									  if(!empty($this->data["datos"]["ano_consolidado"])){
									  	if($this->data["datos"]["ano_consolidado"]!="TODO"){
									  		      $year = $this->data["datos"]["ano_consolidado"];
						                          $sql .=" and a.ano = '".$this->data["datos"]["ano_consolidado"]."' ";
						                          $group_by .= ", a.ano";
						                          $sql_year_sub_2 = "     x.ano= ".$this->data["datos"]["ano_consolidado"]." and ";
						                          $sql_year_sub   = " and x.ano= ".$this->data["datos"]["ano_consolidado"];
									  	}
									  }

                 if($sql_where_sub!="" || $sql_year_sub_2!=""){
                    $sql_asignacion_total = " WHERE ".$sql_where_sub." ".$sql_year_sub_2."  1=1 ";
                 }else{
                    $sql_asignacion_total = "";
                 }

                 $condicion        = $sql.$group_by;
				 $tipo_presupuesto = $this->cfpd05->execute("SELECT
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub."  x.tipo_presupuesto=1 ".$sql_year_sub.") as ordinario,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub."  x.tipo_presupuesto=2 ".$sql_year_sub.") as coordinado,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub."  x.tipo_presupuesto=3 ".$sql_year_sub.") as laee,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub."  x.tipo_presupuesto=4 ".$sql_year_sub.") as fides,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub."  x.tipo_presupuesto=5 ".$sql_year_sub.") as ingresos_extra,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub."  x.tipo_presupuesto=6 ".$sql_year_sub.") as ingresos_propios,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x ".$sql_asignacion_total.") as asignacion_total
															FROM cfpd05 a

															WHERE ".$condicion.";
														   ");




				 $parametros["titulo"][]  = "TIPOS DE RECURSOS";
				 $parametros["titulo"][]  = " AÑO RECURSO: ".$year;


                 $variables["nombre_total"] = "Total Presupuesto";
                 $variables["nombre"][] = "Ordinario";
                 $variables["nombre"][] = "Coordinado";
                 $variables["nombre"][] = "Laee";
                 $variables["nombre"][] = "Fides";
                 $variables["nombre"][] = "Ingresos extraordinarios";
                 $variables["nombre"][] = "Ingresos propios";

                 if(isset($tipo_presupuesto[0][0])){
                 	    $tp               = $tipo_presupuesto[0][0];
						$ordinario         = $tp["ordinario"]==null?0.00:$tp["ordinario"];
						$coordinado        = $tp["coordinado"]==null?0.00:$tp["coordinado"];
						$laee              = $tp["laee"]==null?0.00:$tp["laee"];
						$fides             = $tp["fides"]==null?0.00:$tp["fides"];
						$ingresos_extra    = $tp["ingresos_extra"]==null?0.00:$tp["ingresos_extra"];
						$ingresos_propios  = $tp["ingresos_propios"]==null?0.00:$tp["ingresos_propios"];
						$total_presupuesto = $ordinario+$coordinado+$laee+$fides+$ingresos_extra+$ingresos_propios;

		                 $variables["porcentaje_total"] = ($total_presupuesto * 100) / $total_presupuesto;
		                 $variables["porcentaje"][] = ($ordinario * 100) / $total_presupuesto;
		                 $variables["porcentaje"][] = ($coordinado * 100) / $total_presupuesto;
		                 $variables["porcentaje"][] = ($laee * 100) / $total_presupuesto;
		                 $variables["porcentaje"][] = ($fides * 100) / $total_presupuesto;
		                 $variables["porcentaje"][] = ($ingresos_extra * 100) / $total_presupuesto;
		                 $variables["porcentaje"][] = ($ingresos_propios * 100) / $total_presupuesto;

		                 $variables["monto_total"] = $total_presupuesto;
		                 $variables["monto"][] = $ordinario;
		                 $variables["monto"][] = $coordinado;
		                 $variables["monto"][] = $laee;
		                 $variables["monto"][] = $fides;
		                 $variables["monto"][] = $ingresos_extra;
		                 $variables["monto"][] = $ingresos_propios;

		                 $parametros["tipo_top"]           = $tipo;
						 $parametros["DENO_REPUBLICA"]     = $deno_presi;
						 $parametros["DENO_ESTADO"]        = $deno_entidad;
						 $parametros["DENO_COD_TIPO_INST"] = $deno_tipo_inst;
						 $parametros["DENO_INST"]          = $deno_inst;
                 }else{
                 	     $variables["porcentaje_total"] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["monto_total"]  = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
                 }//fin else
                 $this->genera_grafica_1(1, $parametros, $variables);

	}//fin else if

$this->set('opcion', $var1);


}//fin function



















function grafica_28($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){
				      $datos  = $this->v_cfpd05_tipo_gasto2->execute(" SELECT DISTINCT ano  FROM cfpd05 ");
			          $datos2 = $this->arrd01->execute(" SELECT DISTINCT ano_arranque    FROM ccfd03_instalacion  ORDER BY ano_arranque DESC");
						if(count($datos)!=0){
							foreach($datos as $n){
								$cod[]  = $n[0]['ano'];
								$deno[] = $n[0]['ano'];
							}
							$lista=array_combine($cod, $deno);
						}else{
							$lista=array('0'=>'No se encuentra presente en ninguna nomina');
						}
					$this->set("ano_lista", $lista);
			        $this->set("ano_ejecucion" , $datos2[0][0]["ano_arranque"]);
			        $lista =  $this->arrd01->generateList(null, 'denominacion ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.denominacion');
		            $this->set('vector_presi', $lista);
		            $this->set("cod_presi_seleccion", 1);
	}else if($var1==2){
                       $sql = "";

	      $group_by = " GROUP BY ";
	      $tipo=0;
	      $deno_presi   = "";
	      $deno_entidad = "";
	      $deno_inst    = "";
	      $deno_tipo_inst = "";

	      $sql_year_sub   = "";
	      $sql_year_sub_2 = "";
	      $sql_where_sub  = "";

	                                 if(!empty($this->data["datos"]["cod_presi"])){
									  	if($this->data["datos"]["cod_presi"]!="TODO"){
						                          $sql      .= " a.cod_presi = '".$this->data["datos"]["cod_presi"]."' ";
						                          $group_by .= " a.cod_presi";
						                          $sql_where_sub .= " x.cod_presi=a.cod_presi and ";

						                          $lista_1    =  $this->arrd01->findAll("cod_presi=".$this->data["datos"]["cod_presi"]);
						                          $deno_presi = $lista_1[0]["arrd01"]["denominacion"];
									  	}else{
									  		     $sql .= " 1=1 ";

									  	}
									  }

                                      if(!empty($this->data["datos"]["cod_entidad"])){
									  	if($this->data["datos"]["cod_entidad"]!="TODO"){
						                          $sql .=" and a.cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";
						                          $group_by .= ", a.cod_entidad";
						                          $tipo = 1;
						                          $sql_where_sub .= " x.cod_entidad=a.cod_entidad and ";

						                          $lista_2      =  $this->arrd02->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]);
						                          $deno_entidad = $lista_2[0]["arrd02"]["denominacion"];
									  	}
									  }

                                       if(!empty($this->data["datos"]["cod_tipo_inst"])){
									  	if($this->data["datos"]["cod_tipo_inst"]!="TODO"){
						                          $sql .=" and a.cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";
						                          $group_by .= ", a.cod_tipo_inst";
						                          $tipo = 2;
						                          $sql_where_sub .= " x.cod_tipo_inst=a.cod_tipo_inst and ";

						                          $lista_3        =  $this->arrd03->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_tipo_inst=".$this->data["datos"]["cod_tipo_inst"]);
						                          $deno_tipo_inst = $lista_3[0]["arrd03"]["denominacion"];
									  	 }
                                       }

                                      if(!empty($this->data["datos"]["cod_inst"])){
									  	if($this->data["datos"]["cod_inst"]!="TODO"){
						                          $sql .=" and a.cod_inst = '".$this->data["datos"]["cod_inst"]."' ";
						                          $group_by .= ", a.cod_inst";
						                          $tipo = 3;
						                          $sql_where_sub .= " x.cod_inst=a.cod_inst and ";

						                          $lista_4   =  $this->arrd04->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]." and cod_inst=".$this->data["datos"]["cod_inst"]);
						                          $deno_inst = $lista_4[0]["arrd04"]["denominacion"];
									  	}
									  }
									  if(!empty($this->data["datos"]["ano_consolidado"])){
									  	if($this->data["datos"]["ano_consolidado"]!="TODO"){
									  		      $year = $this->data["datos"]["ano_consolidado"];
						                          $sql .=" and a.ano = '".$this->data["datos"]["ano_consolidado"]."' ";
						                          $group_by .= ", a.ano";
						                          $sql_year_sub_2 = "     x.ano= ".$this->data["datos"]["ano_consolidado"]." and ";
						                          $sql_year_sub   = " and x.ano= ".$this->data["datos"]["ano_consolidado"];
									  	}
									  }

                 if($sql_where_sub!="" || $sql_year_sub_2!=""){
                    $sql_asignacion_total = " WHERE ".$sql_where_sub." ".$sql_year_sub_2."  1=1 ";
                 }else{
                    $sql_asignacion_total = "";
                 }

                 $condicion        = $sql.$group_by;
				 $tipo_sector      = $this->cfpd05->execute("SELECT
															    (SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_sector=1 ".$sql_year_sub.") as sector_1,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_sector=2 ".$sql_year_sub.") as sector_2,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_sector=3 ".$sql_year_sub.") as sector_3,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_sector=4 ".$sql_year_sub.") as sector_4,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_sector=5 ".$sql_year_sub.") as sector_5,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_sector=6 ".$sql_year_sub.") as sector_6,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_sector=7 ".$sql_year_sub.") as sector_7,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_sector=8 ".$sql_year_sub.") as sector_8,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_sector=9 ".$sql_year_sub.") as sector_9,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_sector=10 ".$sql_year_sub.") as sector_10,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_sector=11 ".$sql_year_sub.") as sector_11,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_sector=12 ".$sql_year_sub.") as sector_12,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_sector=13 ".$sql_year_sub.") as sector_13,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_sector=14 ".$sql_year_sub.") as sector_14,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_sector=15 ".$sql_year_sub.") as sector_15,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x ".$sql_asignacion_total.") as asignacion_total
 															  FROM cfpd05 a

																WHERE ".$condicion.";");

																				$rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_sector,a.deno_sector FROM v_balance_ejecucion a WHERE ". $sql."");
																				    foreach($rs as $l){
																						$v[]=$l[0]["cod_sector"];
																						$d[]=$l[0]["deno_sector"];
																					}


                 if(isset($v)){$sector = array_combine($v, $d); }else{ $sector = array();}

				 $parametros["titulo"][]  = "PRESUPUESTO POR SECTORES";
				 $parametros["titulo"][]  = " AÑO RECURSO: ".$year;
				 $parametros["torta"][]   = "no";

                 $variables["nombre_total"]     = "Total Presupuesto";

                 if(count($tipo_sector)!=0){
                 	    $tp=$tipo_sector[0][0];
						foreach($sector as $k=>$v){
						  	$kk[]=$k;
						  	$variables["nombre"][] = mascara2($k)." - ".$v;
						}
						  $total_presupuesto             =  $tp["asignacion_total"];
						  $variables["porcentaje_total"] = ($total_presupuesto * 100) / $total_presupuesto;
						  $variables["monto_total"]      =  $total_presupuesto;
						for($i=0;$i<count($kk);$i++){
						  $sector[$kk[$i]]           =  $tp["sector_".$kk[$i]]==null?0.00:$tp["sector_".$kk[$i]];
						  $variables["monto"][]      =  $tp["sector_".$kk[$i]]==null?0.00:$tp["sector_".$kk[$i]];
						  $variables["porcentaje"][] = ($tp["sector_".$kk[$i]]==null?0.00:$tp["sector_".$kk[$i]] * 100) / $total_presupuesto;
						}

						 $parametros["tipo_top"]           = $tipo;
						 $parametros["DENO_REPUBLICA"]     = $deno_presi;
						 $parametros["DENO_ESTADO"]        = $deno_entidad;
						 $parametros["DENO_COD_TIPO_INST"] = $deno_tipo_inst;
						 $parametros["DENO_INST"]          = $deno_inst;

                 }else{
                 	     $variables["porcentaje_total"] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["monto_total"]  = 0;
		                 $variables["monto"][] = 0;
		                 $variables["nombre"][] = "Sector";
                 }//fin else
                 $this->genera_grafica_1(1, $parametros, $variables);

	}//fin else if

$this->set('opcion', $var1);


}//fin function







function grafica_29($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){
				      $datos  = $this->v_cfpd05_tipo_gasto2->execute(" SELECT DISTINCT ano  FROM cfpd05 ");
			          $datos2 = $this->arrd01->execute(" SELECT DISTINCT ano_arranque    FROM ccfd03_instalacion  ORDER BY ano_arranque DESC");

						if(count($datos)!=0){
							foreach($datos as $n){
								$cod[]  = $n[0]['ano'];
								$deno[] = $n[0]['ano'];
							}
							$lista=array_combine($cod, $deno);
						}else{
							$lista=array('0'=>'No se encuentra presente en ninguna nomina');
						}
					$this->set("ano_lista", $lista);
			        $this->set("ano_ejecucion" , $datos2[0][0]["ano_arranque"]);

			        $lista =  $this->arrd01->generateList(null, 'denominacion ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.denominacion');
		            $this->set('vector_presi', $lista);
		            $this->set("cod_presi_seleccion", 1);
	}else if($var1==2){
                       $sql = "";

	      $group_by = " GROUP BY ";
	      $tipo=0;
	      $deno_presi   = "";
	      $deno_entidad = "";
	      $deno_inst    = "";
	      $deno_tipo_inst = "";

	      $sql_year_sub   = "";
	      $sql_year_sub_2 = "";
	      $sql_where_sub  = "";

	                                 if(!empty($this->data["datos"]["cod_presi"])){
									  	if($this->data["datos"]["cod_presi"]!="TODO"){
						                          $sql      .= " a.cod_presi = '".$this->data["datos"]["cod_presi"]."' ";
						                          $group_by .= " a.cod_presi";
						                          $sql_where_sub .= " x.cod_presi=a.cod_presi and ";

						                          $lista_1    =  $this->arrd01->findAll("cod_presi=".$this->data["datos"]["cod_presi"]);
						                          $deno_presi = $lista_1[0]["arrd01"]["denominacion"];
									  	}else{
									  		     $sql .= " 1=1 ";

									  	}
									  }

                                      if(!empty($this->data["datos"]["cod_entidad"])){
									  	if($this->data["datos"]["cod_entidad"]!="TODO"){
						                          $sql .=" and a.cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";
						                          $group_by .= ", a.cod_entidad";
						                          $tipo = 1;
						                          $sql_where_sub .= " x.cod_entidad=a.cod_entidad and ";

						                          $lista_2      =  $this->arrd02->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]);
						                          $deno_entidad = $lista_2[0]["arrd02"]["denominacion"];
									  	}
									  }

                                       if(!empty($this->data["datos"]["cod_tipo_inst"])){
									  	if($this->data["datos"]["cod_tipo_inst"]!="TODO"){
						                          $sql .=" and a.cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";
						                          $group_by .= ", a.cod_tipo_inst";
						                          $tipo = 2;
						                          $sql_where_sub .= " x.cod_tipo_inst=a.cod_tipo_inst and ";

						                          $lista_3        =  $this->arrd03->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_tipo_inst=".$this->data["datos"]["cod_tipo_inst"]);
						                          $deno_tipo_inst = $lista_3[0]["arrd03"]["denominacion"];
									  	 }
                                       }

                                      if(!empty($this->data["datos"]["cod_inst"])){
									  	if($this->data["datos"]["cod_inst"]!="TODO"){
						                          $sql .=" and a.cod_inst = '".$this->data["datos"]["cod_inst"]."' ";
						                          $group_by .= ", a.cod_inst";
						                          $tipo = 3;
						                          $sql_where_sub .= " x.cod_inst=a.cod_inst and ";

						                          $lista_4   =  $this->arrd04->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]." and cod_inst=".$this->data["datos"]["cod_inst"]);
						                          $deno_inst = $lista_4[0]["arrd04"]["denominacion"];
									  	}
									  }
									  if(!empty($this->data["datos"]["ano_consolidado"])){
									  	if($this->data["datos"]["ano_consolidado"]!="TODO"){
									  		      $year = $this->data["datos"]["ano_consolidado"];
						                          $sql .=" and a.ano = '".$this->data["datos"]["ano_consolidado"]."' ";
						                          $group_by .= ", a.ano";
						                          $sql_year_sub_2 = "     x.ano= ".$this->data["datos"]["ano_consolidado"]." and ";
						                          $sql_year_sub   = " and x.ano= ".$this->data["datos"]["ano_consolidado"];
									  	}
									  }

                 if($sql_where_sub!="" || $sql_year_sub_2!=""){
                    $sql_asignacion_total = " WHERE ".$sql_where_sub." ".$sql_year_sub_2."  1=1 ";
                 }else{
                    $sql_asignacion_total = "";
                 }

                 $condicion        = $sql.$group_by;
				 $tipo_partida     = $this->cfpd05->execute("SELECT

																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_partida=401 ".$sql_year_sub.") as partida_401,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_partida=402 ".$sql_year_sub.") as partida_402,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_partida=403 ".$sql_year_sub.") as partida_403,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_partida=404 ".$sql_year_sub.") as partida_404,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_partida=405 ".$sql_year_sub.") as partida_405,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_partida=406 ".$sql_year_sub.") as partida_406,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_partida=407 ".$sql_year_sub.") as partida_407,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_partida=408 ".$sql_year_sub.") as partida_408,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_partida=409 ".$sql_year_sub.") as partida_409,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_partida=410 ".$sql_year_sub.") as partida_410,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_partida=411 ".$sql_year_sub.") as partida_411,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_partida=412 ".$sql_year_sub.") as partida_412,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_partida=498 ".$sql_year_sub.") as partida_498,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x ".$sql_asignacion_total.") as asignacion_total
																FROM cfpd05 a

																WHERE ".$condicion.";");

			$rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_partida,a.deno_partida FROM v_cfpd05_denominaciones a WHERE ". $sql."  ");
			    foreach($rs as $l){
					$v[]=$l[0]["cod_partida"];
					$d[]=$l[0]["deno_partida"];
				}
				if(isset($v)){$PARTIDA = array_combine($v, $d); }else{ $PARTIDA = array();}



				 $parametros["titulo"][]  = "PRESUPUESTO POR PARTIDAS";
				 $parametros["titulo"][]  = " AÑO RECURSO: ".$year;
				 $parametros["torta"][]   = "no";

                 $variables["nombre_total"]     = "Total Presupuesto";

                 if(count($tipo_partida)!=0){
                 	    $tp=$tipo_partida[0][0];
						foreach($PARTIDA as $k=>$v){
						  	$kk[]=$k;
						  	$variables["nombre"][] = CE.".".separa_partida_de_grupo($k)." - ".$v;
						}
						  $total_presupuesto             =  $tp["asignacion_total"];
						  $variables["porcentaje_total"] = ($total_presupuesto * 100) / $total_presupuesto;
						  $variables["monto_total"]      =  $total_presupuesto;
						for($i=0;$i<count($kk);$i++){
						  $sector[$kk[$i]]           =  $tp["partida_".$kk[$i]]==null?0.00:$tp["partida_".$kk[$i]];
						  $variables["monto"][]      =  $tp["partida_".$kk[$i]]==null?0.00:$tp["partida_".$kk[$i]];
						  $variables["porcentaje"][] = ($tp["partida_".$kk[$i]]==null?0.00:$tp["partida_".$kk[$i]] * 100) / $total_presupuesto;
						}
						 $parametros["tipo_top"]           = $tipo;
						 $parametros["DENO_REPUBLICA"]     = $deno_presi;
						 $parametros["DENO_ESTADO"]        = $deno_entidad;
						 $parametros["DENO_COD_TIPO_INST"] = $deno_tipo_inst;
						 $parametros["DENO_INST"]          = $deno_inst;
                 }else{
                 	     $variables["porcentaje_total"] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["monto_total"]  = 0;
		                 $variables["monto"][] = 0;
		                 $variables["nombre"][] = "Partida";
                 }//fin else
                 $this->genera_grafica_1(1, $parametros, $variables);

	}//fin else if

$this->set('opcion', $var1);


}//fin function


















function grafica_30($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){
				      $datos  = $this->v_cfpd05_tipo_gasto2->execute(" SELECT DISTINCT ano  FROM cfpd05 ");
			          $datos2 = $this->arrd01->execute(" SELECT DISTINCT ano_arranque    FROM ccfd03_instalacion  ORDER BY ano_arranque DESC");

						if(count($datos)!=0){
							foreach($datos as $n){
								$cod[]  = $n[0]['ano'];
								$deno[] = $n[0]['ano'];
							}
							$lista=array_combine($cod, $deno);
						}else{
							$lista=array('0'=>'No se encuentra presente en ninguna nomina');
						}

					$this->set("ano_lista", $lista);
   			        $this->set("ano_ejecucion" , $datos2[0][0]["ano_arranque"]);

			        $lista =  $this->arrd01->generateList(null, 'denominacion ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.denominacion');
		            $this->set('vector_presi', $lista);
		            $this->set("cod_presi_seleccion", 1);
	}else if($var1==2){
                       $sql = "";

	      $group_by = " GROUP BY ";
	      $tipo=0;
	      $deno_presi   = "";
	      $deno_entidad = "";
	      $deno_inst    = "";
	      $deno_tipo_inst = "";

	                                 if(!empty($this->data["datos"]["cod_presi"])){
									  	if($this->data["datos"]["cod_presi"]!="TODO"){
						                          $sql      .= " a.cod_presi = '".$this->data["datos"]["cod_presi"]."' ";
						                          $group_by .= " a.cod_presi";

						                          $lista_1    =  $this->arrd01->findAll("cod_presi=".$this->data["datos"]["cod_presi"]);
						                          $deno_presi = $lista_1[0]["arrd01"]["denominacion"];
									  	}else{
									  		     $sql .= " 1=1 ";

									  	}
									  }

                                      if(!empty($this->data["datos"]["cod_entidad"])){
									  	if($this->data["datos"]["cod_entidad"]!="TODO"){
						                          $sql .=" and a.cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";
						                          $group_by .= ", a.cod_entidad";
						                          $tipo = 1;

						                          $lista_2      =  $this->arrd02->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]);
						                          $deno_entidad = $lista_2[0]["arrd02"]["denominacion"];
									  	}
									  }

                                       if(!empty($this->data["datos"]["cod_tipo_inst"])){
									  	if($this->data["datos"]["cod_tipo_inst"]!="TODO"){
						                          $sql .=" and a.cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";
						                          $group_by .= ", a.cod_tipo_inst";
						                          $tipo = 2;

						                          $lista_3        =  $this->arrd03->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_tipo_inst=".$this->data["datos"]["cod_tipo_inst"]);
						                          $deno_tipo_inst = $lista_3[0]["arrd03"]["denominacion"];
									  	 }
                                       }

                                      if(!empty($this->data["datos"]["cod_inst"])){
									  	if($this->data["datos"]["cod_inst"]!="TODO"){
						                          $sql .=" and a.cod_inst = '".$this->data["datos"]["cod_inst"]."' ";
						                          $group_by .= ", a.cod_inst";
						                          $tipo = 3;

						                          $lista_4   =  $this->arrd04->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]." and cod_inst=".$this->data["datos"]["cod_inst"]);
						                          $deno_inst = $lista_4[0]["arrd04"]["denominacion"];
									  	}
									  }
									  if(!empty($this->data["datos"]["ano_consolidado"])){
									  	if($this->data["datos"]["ano_consolidado"]!="TODO"){
									  		      $year = $this->data["datos"]["ano_consolidado"];
						                          $sql .=" and a.ano = '".$this->data["datos"]["ano_consolidado"]."' ";
						                          $group_by .= ", a.ano";
									  	}
									  }
									  if(!empty($this->data["datos"]["tipo_gasto"])){
									  	             $tipo_gasto_aux = $this->data["datos"]["tipo_gasto"];
                                                     if($this->data["datos"]["tipo_gasto"]==1){
											         $tipo_gasto = " and cod_tipo_gasto = 2 ";
											   }else if($this->data["datos"]["tipo_gasto"]==3){
											         $tipo_gasto = " ";
											   }else{
											         $tipo_gasto = "  and cod_tipo_gasto != 2 ";
											   }//fin if
									   }
            $condicion        = $sql.$tipo_gasto.$group_by;
            $datos = $this->cfpd05->execute("SELECT

		    						  SUM(a.asignacion_anual)           as asignacion_anual,
									  SUM(a.aumento_traslado_anual)     as aumento_traslado_anual,
									  SUM(a.disminucion_traslado_anual) as disminucion_traslado_anual,
									  SUM(a.credito_adicional_anual)    as credito_adicional_anual,
									  SUM(a.rebaja_anual)               as rebaja_anual,
									  SUM(a.compromiso_anual)           as compromiso_anual,
									  SUM(a.causado_anual)              as causado_anual,
									  SUM(a.pagado_anual)               as pagado_anual

						 		      FROM cfpd05 a where ".$condicion.";");


				                       if($this->data["datos"]["tipo_gasto"]==1){  $opcion1_aux = "TIPO DEL GASTO: Capital,";
								 }else if($this->data["datos"]["tipo_gasto"]==2){  $opcion1_aux = "TIPO DEL GASTO: Corriente,";
								 }else if($this->data["datos"]["tipo_gasto"]==3){  $opcion1_aux = ""; }

				 $parametros["titulo"][]  = "TIPOS DE GASTOS";
				 $parametros["titulo"][]  = $opcion1_aux." AÑO RECURSO: ".$year;
                 $variables["nombre_total"] = "Asignación Ajustada";
                 $variables["nombre"][] = "Compromisos";
                 $variables["nombre"][] = "Causados";
                 $variables["nombre"][] = "Pagado";
                 $variables["nombre"][] = "Disponibilidad";
                 $variables["nombre"][] = "Deuda";

                 if(!empty($datos[0][0]['asignacion_anual']) && $datos[0][0]['asignacion_anual']!=0){
                 	    $asignacion_anual            =  $datos[0][0]['asignacion_anual'];
						$aumento_traslado_anual      =  $datos[0][0]['aumento_traslado_anual'];
						$credito_adicional_anual     =  $datos[0][0]['credito_adicional_anual'];
						$disminucion_traslado_anual  =  $datos[0][0]['disminucion_traslado_anual'];
						$rebaja_anual                =  $datos[0][0]['rebaja_anual'];
						$compromiso_anual    =  $datos[0][0]['compromiso_anual'];
						$causado_anual       =  $datos[0][0]['causado_anual'];
						$pagado_anual        =  $datos[0][0]['pagado_anual'];
						$asignacion_ajustada =  (($asignacion_anual+$aumento_traslado_anual+$credito_adicional_anual)-($disminucion_traslado_anual+$rebaja_anual));
						$deuda = ($causado_anual-$pagado_anual);
						$disponibilidad =  ($asignacion_ajustada-$compromiso_anual);

		                 $variables["porcentaje_total"] = ($asignacion_ajustada * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($compromiso_anual * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($causado_anual * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($pagado_anual * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($disponibilidad * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($deuda * 100) / $asignacion_ajustada;
		                 $variables["monto_total"] = $asignacion_ajustada;
		                 $variables["monto"][] = $compromiso_anual;
		                 $variables["monto"][] = $causado_anual;
		                 $variables["monto"][] = $pagado_anual;
		                 $variables["monto"][] = $disponibilidad;
		                 $variables["monto"][] = $deuda;

		                 $parametros["tipo_top"]           = $tipo;
						 $parametros["DENO_REPUBLICA"]     = $deno_presi;
						 $parametros["DENO_ESTADO"]        = $deno_entidad;
						 $parametros["DENO_COD_TIPO_INST"] = $deno_tipo_inst;
						 $parametros["DENO_INST"]          = $deno_inst;

                 }else{
                 	     $variables["porcentaje_total"] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["monto_total"]  = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
                 }//fin else
                 $this->genera_grafica_1(1, $parametros, $variables);

	}//fin else if

$this->set('opcion', $var1);


}//fin function








function grafica_31($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){
				      $datos  = $this->v_cfpd05_tipo_gasto2->execute(" SELECT DISTINCT ano  FROM cfpd05 ");
			          $datos2 = $this->arrd01->execute(" SELECT DISTINCT ano_arranque    FROM ccfd03_instalacion  ORDER BY ano_arranque DESC");

						if(count($datos)!=0){
							foreach($datos as $n){
								$cod[]  = $n[0]['ano'];
								$deno[] = $n[0]['ano'];
							}
							$lista=array_combine($cod, $deno);
						}else{
							$lista=array('0'=>'No se encuentra presente en ninguna nomina');
						}

					$this->set("ano_lista", $lista);


			$this->set("ano_ejecucion" , $datos2[0][0]["ano_arranque"]);
			        $lista =  $this->arrd01->generateList(null, 'denominacion ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.denominacion');
		            $this->set('vector_presi', $lista);
		            $this->set("cod_presi_seleccion", 1);
	}else if($var1==2){
                       $sql = "";

	      $group_by = " GROUP BY ";
	      $tipo=0;
	      $deno_presi   = "";
	      $deno_entidad = "";
	      $deno_inst    = "";
	      $deno_tipo_inst = "";

	                                 if(!empty($this->data["datos"]["cod_presi"])){
									  	if($this->data["datos"]["cod_presi"]!="TODO"){
						                          $sql      .= " a.cod_presi = '".$this->data["datos"]["cod_presi"]."' ";
						                          $group_by .= " a.cod_presi";

						                          $lista_1    =  $this->arrd01->findAll("cod_presi=".$this->data["datos"]["cod_presi"]);
						                          $deno_presi = $lista_1[0]["arrd01"]["denominacion"];
									  	}else{
									  		     $sql .= " 1=1 ";

									  	}
									  }

                                      if(!empty($this->data["datos"]["cod_entidad"])){
									  	if($this->data["datos"]["cod_entidad"]!="TODO"){
						                          $sql .=" and a.cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";
						                          $group_by .= ", a.cod_entidad";
						                          $tipo = 1;

						                          $lista_2      =  $this->arrd02->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]);
						                          $deno_entidad = $lista_2[0]["arrd02"]["denominacion"];
									  	}
									  }

                                       if(!empty($this->data["datos"]["cod_tipo_inst"])){
									  	if($this->data["datos"]["cod_tipo_inst"]!="TODO"){
						                          $sql .=" and a.cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";
						                          $group_by .= ", a.cod_tipo_inst";
						                          $tipo = 2;

						                          $lista_3        =  $this->arrd03->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_tipo_inst=".$this->data["datos"]["cod_tipo_inst"]);
						                          $deno_tipo_inst = $lista_3[0]["arrd03"]["denominacion"];
									  	 }
                                       }

                                      if(!empty($this->data["datos"]["cod_inst"])){
									  	if($this->data["datos"]["cod_inst"]!="TODO"){
						                          $sql .=" and a.cod_inst = '".$this->data["datos"]["cod_inst"]."' ";
						                          $group_by .= ", a.cod_inst";
						                          $tipo = 3;

						                          $lista_4   =  $this->arrd04->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]." and cod_inst=".$this->data["datos"]["cod_inst"]);
						                          $deno_inst = $lista_4[0]["arrd04"]["denominacion"];
									  	}
									  }
									  if(!empty($this->data["datos"]["ano_consolidado"])){
									  	if($this->data["datos"]["ano_consolidado"]!="TODO"){
									  		      $year = $this->data["datos"]["ano_consolidado"];
						                          $sql .=" and a.ano = '".$this->data["datos"]["ano_consolidado"]."' ";
						                          $group_by .= ", a.ano";
									  	}
									  }
									  if(!empty($this->data["datos"]["tipo_presupuesto"])){
									  	             $tipo_presupuesto_aux = $this->data["datos"]["tipo_presupuesto"];
							  	             if($this->data["datos"]["tipo_presupuesto"]==7){
											   	 $sql_aux = "";
											   }else{
											   	 $sql_aux = " and tipo_presupuesto = ".$this->data["datos"]["tipo_presupuesto"];
											   }
									   }




                 $condicion        = $sql.$sql_aux.$group_by;


            $datos = $this->cfpd05->execute("SELECT

		    						  SUM(a.asignacion_anual)           as asignacion_anual,
									  SUM(a.aumento_traslado_anual)     as aumento_traslado_anual,
									  SUM(a.disminucion_traslado_anual) as disminucion_traslado_anual,
									  SUM(a.credito_adicional_anual)    as credito_adicional_anual,
									  SUM(a.rebaja_anual)               as rebaja_anual,
									  SUM(a.compromiso_anual)           as compromiso_anual,
									  SUM(a.causado_anual)              as causado_anual,
									  SUM(a.pagado_anual)               as pagado_anual

						 		      FROM cfpd05 a where ".$condicion.";");


                       if($tipo_presupuesto_aux==1){  $opcion1_aux = "TIPO DEL RECURSO: Ordinario,";
				 }else if($tipo_presupuesto_aux==2){  $opcion1_aux = "TIPO DEL RECURSO: Coordinado,";
				 }else if($tipo_presupuesto_aux==3){  $opcion1_aux = "TIPO DEL RECURSO: Laee,";
				 }else if($tipo_presupuesto_aux==4){  $opcion1_aux = "TIPO DEL RECURSO: Fides,";
				 }else if($tipo_presupuesto_aux==5){  $opcion1_aux = "TIPO DEL RECURSO: Ingreso Extraordinario,";
				 }else if($tipo_presupuesto_aux==6){  $opcion1_aux = "TIPO DEL RECURSO: Ingreso Propio,";
				 }else if($tipo_presupuesto_aux==7){  $opcion1_aux = ""; }
				 $parametros["titulo"][]  = "TIPOS DE RECURSOS";
				 $parametros["titulo"][]  = $opcion1_aux." AÑO RECURSO: ".$year;
                 $variables["nombre_total"] = "Asignación Ajustada";
                 $variables["nombre"][] = "Compromisos";
                 $variables["nombre"][] = "Causados";
                 $variables["nombre"][] = "Pagado";
                 $variables["nombre"][] = "Disponibilidad";
                 $variables["nombre"][] = "Deuda";

                 if(!empty($datos[0][0]['asignacion_anual']) && $datos[0][0]['asignacion_anual']!=0){
                 	    $asignacion_anual            =  $datos[0][0]['asignacion_anual'];
						$aumento_traslado_anual      =  $datos[0][0]['aumento_traslado_anual'];
						$credito_adicional_anual     =  $datos[0][0]['credito_adicional_anual'];
						$disminucion_traslado_anual  =  $datos[0][0]['disminucion_traslado_anual'];
						$rebaja_anual                =  $datos[0][0]['rebaja_anual'];
						$compromiso_anual    =  $datos[0][0]['compromiso_anual'];
						$causado_anual       =  $datos[0][0]['causado_anual'];
						$pagado_anual        =  $datos[0][0]['pagado_anual'];
						$asignacion_ajustada =  (($asignacion_anual+$aumento_traslado_anual+$credito_adicional_anual)-($disminucion_traslado_anual+$rebaja_anual));
						$deuda = ($causado_anual-$pagado_anual);
						$disponibilidad =  ($asignacion_ajustada-$compromiso_anual);

		                 $variables["porcentaje_total"] = ($asignacion_ajustada * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($compromiso_anual * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($causado_anual * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($pagado_anual * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($disponibilidad * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($deuda * 100) / $asignacion_ajustada;
		                 $variables["monto_total"] = $asignacion_ajustada;
		                 $variables["monto"][] = $compromiso_anual;
		                 $variables["monto"][] = $causado_anual;
		                 $variables["monto"][] = $pagado_anual;
		                 $variables["monto"][] = $disponibilidad;
		                 $variables["monto"][] = $deuda;

		                 $parametros["tipo_top"]           = $tipo;
						 $parametros["DENO_REPUBLICA"]     = $deno_presi;
						 $parametros["DENO_ESTADO"]        = $deno_entidad;
						 $parametros["DENO_COD_TIPO_INST"] = $deno_tipo_inst;
						 $parametros["DENO_INST"]          = $deno_inst;
                 }else{
                 	     $variables["porcentaje_total"] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["monto_total"]  = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
                 }//fin else
                 $this->genera_grafica_1(1, $parametros, $variables);

	}//fin else if

$this->set('opcion', $var1);


}//fin function














function grafica_32($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){
				      $datos  = $this->v_cfpd05_tipo_gasto2->execute(" SELECT DISTINCT ano  FROM cfpd05 ");
			$datos2 = $this->arrd01->execute(" SELECT DISTINCT ano_arranque    FROM ccfd03_instalacion  ORDER BY ano_arranque DESC");

						if(count($datos)!=0){
							foreach($datos as $n){
								$cod[]  = $n[0]['ano'];
								$deno[] = $n[0]['ano'];
							}
							$lista=array_combine($cod, $deno);
						}else{
							$lista=array('0'=>'No se encuentra presente en ninguna nomina');
						}

					$this->set("ano_lista", $lista);
			        $this->set("ano_ejecucion" , $datos2[0][0]["ano_arranque"]);
			        $lista =  $this->arrd01->generateList(null, 'denominacion ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.denominacion');
		            $this->set('vector_presi', $lista);
		            $this->set("cod_presi_seleccion", 1);

						    $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_sector, a.denominacion FROM cfpd02_sector a ");
						    foreach($rs as $l){
						    	if(!isset($seleccion)){$seleccion = mascara2($l[0]["cod_sector"]);}
								$v[]=mascara2($l[0]["cod_sector"]);
								$d[]=mascara2($l[0]["cod_sector"])." - ".$l[0]["denominacion"];
							}
							$sector = array_combine($v, $d);
					        $this->set('lista_numero', $sector);
					        $this->set("seleccion" , $seleccion);


	}else if($var1==2){
                       $sql = "";

	      $group_by = " GROUP BY ";
	      $tipo=0;
	      $deno_presi   = "";
	      $deno_entidad = "";
	      $deno_inst    = "";
	      $deno_tipo_inst = "";
	      $cod_partida_aux = "";

	                                 if(!empty($this->data["datos"]["cod_presi"])){
									  	if($this->data["datos"]["cod_presi"]!="TODO"){
						                          $sql      .= " a.cod_presi = '".$this->data["datos"]["cod_presi"]."' ";
						                          $group_by .= " a.cod_presi";

						                          $lista_1    =  $this->arrd01->findAll("cod_presi=".$this->data["datos"]["cod_presi"]);
						                          $deno_presi = $lista_1[0]["arrd01"]["denominacion"];
									  	}else{
									  		     $sql .= " 1=1 ";

									  	}
									  }

                                      if(!empty($this->data["datos"]["cod_entidad"])){
									  	if($this->data["datos"]["cod_entidad"]!="TODO"){
						                          $sql .=" and a.cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";
						                          $group_by .= ", a.cod_entidad";
						                          $tipo = 1;

						                          $lista_2      =  $this->arrd02->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]);
						                          $deno_entidad = $lista_2[0]["arrd02"]["denominacion"];
									  	}
									  }

                                       if(!empty($this->data["datos"]["cod_tipo_inst"])){
									  	if($this->data["datos"]["cod_tipo_inst"]!="TODO"){
						                          $sql .=" and a.cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";
						                          $group_by .= ", a.cod_tipo_inst";
						                          $tipo = 2;

						                          $lista_3        =  $this->arrd03->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_tipo_inst=".$this->data["datos"]["cod_tipo_inst"]);
						                          $deno_tipo_inst = $lista_3[0]["arrd03"]["denominacion"];
									  	 }
                                       }

                                      if(!empty($this->data["datos"]["cod_inst"])){
									  	if($this->data["datos"]["cod_inst"]!="TODO"){
						                          $sql .=" and a.cod_inst = '".$this->data["datos"]["cod_inst"]."' ";
						                          $group_by .= ", a.cod_inst";
						                          $tipo = 3;

						                          $lista_4   =  $this->arrd04->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]." and cod_inst=".$this->data["datos"]["cod_inst"]);
						                          $deno_inst = $lista_4[0]["arrd04"]["denominacion"];
									  	}
									  }
									  if(!empty($this->data["datos"]["ano_consolidado"])){
									  	if($this->data["datos"]["ano_consolidado"]!="TODO"){
									  		      $year = $this->data["datos"]["ano_consolidado"];
						                          $sql .=" and a.ano = '".$this->data["datos"]["ano_consolidado"]."' ";
						                          $group_by .= ", a.ano";
									  	}
									  }
									  if(!empty($this->data["datos"]["cod_sector"])){
									  	             $cod_sector_aux = $this->data["datos"]["cod_sector"];
									  	             $sql .=" and a.cod_sector = '".$this->data["datos"]["cod_sector"]."' ";

									   }

           $condicion = $sql.$group_by;
           $datos     =  $this->cfpd05->execute("SELECT
						    						  SUM(a.asignacion_anual)           as asignacion_anual,
													  SUM(a.aumento_traslado_anual)     as aumento_traslado_anual,
													  SUM(a.disminucion_traslado_anual) as disminucion_traslado_anual,
													  SUM(a.credito_adicional_anual)    as credito_adicional_anual,
													  SUM(a.rebaja_anual)               as rebaja_anual,
													  SUM(a.compromiso_anual)           as compromiso_anual,
													  SUM(a.causado_anual)              as causado_anual,
													  SUM(a.pagado_anual)               as pagado_anual
						 		                  FROM cfpd05 a where ".$condicion.";");

                 $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_sector,a.deno_sector FROM v_balance_ejecucion a WHERE  ". $sql." and  a.ano=".$year);

				 $parametros["titulo"][]  = "EJECUCIÓN POR SECTORES";
				 if(isset($rs[0][0]['cod_sector']) && !empty($this->data["datos"]["cod_sector"])){
				  $parametros["titulo"][]  = "SECTOR: ".mascara2($rs[0][0]['cod_sector'])." - ".$rs[0][0]['deno_sector'].", AÑO RECURSO: ".$year;
				 }else{
				  $parametros["titulo"][]  = " AÑO RECURSO: ".$year;
				 }
                 $variables["nombre_total"] = "Asignación Ajustada";
                 $variables["nombre"][] = "Compromisos";
                 $variables["nombre"][] = "Causados";
                 $variables["nombre"][] = "Pagado";
                 $variables["nombre"][] = "Disponibilidad";
                 $variables["nombre"][] = "Deuda";

                 if(!empty($datos[0][0]['asignacion_anual']) && $datos[0][0]['asignacion_anual']!=0){
                 	    $asignacion_anual            =  $datos[0][0]['asignacion_anual'];
						$aumento_traslado_anual      =  $datos[0][0]['aumento_traslado_anual'];
						$credito_adicional_anual     =  $datos[0][0]['credito_adicional_anual'];
						$disminucion_traslado_anual  =  $datos[0][0]['disminucion_traslado_anual'];
						$rebaja_anual                =  $datos[0][0]['rebaja_anual'];
						$compromiso_anual    =  $datos[0][0]['compromiso_anual'];
						$causado_anual       =  $datos[0][0]['causado_anual'];
						$pagado_anual        =  $datos[0][0]['pagado_anual'];
						$asignacion_ajustada =  (($asignacion_anual+$aumento_traslado_anual+$credito_adicional_anual)-($disminucion_traslado_anual+$rebaja_anual));
						$deuda               = ($causado_anual-$pagado_anual);
						$disponibilidad      = ($asignacion_ajustada-$compromiso_anual);

		                 $variables["porcentaje_total"] = ($asignacion_ajustada * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($compromiso_anual * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($causado_anual * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($pagado_anual * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($disponibilidad * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($deuda * 100) / $asignacion_ajustada;
		                 $variables["monto_total"] = $asignacion_ajustada;
		                 $variables["monto"][] = $compromiso_anual;
		                 $variables["monto"][] = $causado_anual;
		                 $variables["monto"][] = $pagado_anual;
		                 $variables["monto"][] = $disponibilidad;
		                 $variables["monto"][] = $deuda;

		                 $parametros["tipo_top"]           = $tipo;
						 $parametros["DENO_REPUBLICA"]     = $deno_presi;
						 $parametros["DENO_ESTADO"]        = $deno_entidad;
						 $parametros["DENO_COD_TIPO_INST"] = $deno_tipo_inst;
						 $parametros["DENO_INST"]          = $deno_inst;

                 }else{
                 	     $variables["porcentaje_total"] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["monto_total"]  = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
                 }//fin else
                 $this->genera_grafica_1(1, $parametros, $variables);

	}//fin else if

$this->set('opcion', $var1);


}//fin function





function grafica_33($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){
				      $datos  = $this->v_cfpd05_tipo_gasto2->execute(" SELECT DISTINCT ano  FROM cfpd05 ");
			          $datos2 = $this->arrd01->execute(" SELECT DISTINCT ano_arranque    FROM ccfd03_instalacion  ORDER BY ano_arranque DESC");

						if(count($datos)!=0){
							foreach($datos as $n){
								$cod[]  = $n[0]['ano'];
								$deno[] = $n[0]['ano'];
							}
							$lista=array_combine($cod, $deno);
						}else{
							$lista=array('0'=>'No se encuentra presente en ninguna nomina');
						}

					$this->set("ano_lista", $lista);
			        $this->set("ano_ejecucion" , $datos2[0][0]["ano_arranque"]);

			        $lista =  $this->arrd01->generateList(null, 'denominacion ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.denominacion');
		            $this->set('vector_presi', $lista);
		            $this->set("cod_presi_seleccion", 1);

						    $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_grupo, a.cod_partida, a.descripcion FROM cfpd01_partida a where a.cod_grupo=4 ");
						    foreach($rs as $l){
						    	if(!isset($seleccion)){$seleccion = $l[0]["cod_grupo"].mascara2($l[0]["cod_partida"]);}
								$v[]=$l[0]["cod_grupo"].mascara2($l[0]["cod_partida"]);
								$d[]=$l[0]["cod_grupo"].".".mascara2($l[0]["cod_partida"])." - ".$l[0]["descripcion"];
							}
							$partida = array_combine($v, $d);
					        $this->set('lista_numero', $partida);
					        $this->set("seleccion" , $seleccion);

	}else if($var1==2){
                       $sql = "";

	      $group_by = " GROUP BY ";
	      $tipo=0;
	      $deno_presi   = "";
	      $deno_entidad = "";
	      $deno_inst    = "";
	      $deno_tipo_inst = "";
	      $cod_partida_aux = "";

	                                 if(!empty($this->data["datos"]["cod_presi"])){
									  	if($this->data["datos"]["cod_presi"]!="TODO"){
						                          $sql      .= " a.cod_presi = '".$this->data["datos"]["cod_presi"]."' ";
						                          $group_by .= " a.cod_presi";

						                          $lista_1    =  $this->arrd01->findAll("cod_presi=".$this->data["datos"]["cod_presi"]);
						                          $deno_presi = $lista_1[0]["arrd01"]["denominacion"];
									  	}else{
									  		     $sql .= " 1=1 ";

									  	}
									  }

                                      if(!empty($this->data["datos"]["cod_entidad"])){
									  	if($this->data["datos"]["cod_entidad"]!="TODO"){
						                          $sql .=" and a.cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";
						                          $group_by .= ", a.cod_entidad";
						                          $tipo = 1;

						                          $lista_2      =  $this->arrd02->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]);
						                          $deno_entidad = $lista_2[0]["arrd02"]["denominacion"];
									  	}
									  }

                                       if(!empty($this->data["datos"]["cod_tipo_inst"])){
									  	if($this->data["datos"]["cod_tipo_inst"]!="TODO"){
						                          $sql .=" and a.cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";
						                          $group_by .= ", a.cod_tipo_inst";
						                          $tipo = 2;

						                          $lista_3        =  $this->arrd03->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_tipo_inst=".$this->data["datos"]["cod_tipo_inst"]);
						                          $deno_tipo_inst = $lista_3[0]["arrd03"]["denominacion"];
									  	 }
                                       }

                                      if(!empty($this->data["datos"]["cod_inst"])){
									  	if($this->data["datos"]["cod_inst"]!="TODO"){
						                          $sql .=" and a.cod_inst = '".$this->data["datos"]["cod_inst"]."' ";
						                          $group_by .= ", a.cod_inst";
						                          $tipo = 3;

						                          $lista_4   =  $this->arrd04->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]." and cod_inst=".$this->data["datos"]["cod_inst"]);
						                          $deno_inst = $lista_4[0]["arrd04"]["denominacion"];
									  	}
									  }
									  if(!empty($this->data["datos"]["ano_consolidado"])){
									  	if($this->data["datos"]["ano_consolidado"]!="TODO"){
									  		      $year = $this->data["datos"]["ano_consolidado"];
						                          $sql .=" and a.ano = '".$this->data["datos"]["ano_consolidado"]."' ";
						                          $group_by .= ", a.ano";
									  	}
									  }
									  if(!empty($this->data["datos"]["cod_partida"])){
									  	             $cod_partida_aux = $this->data["datos"]["cod_partida"];
									  	             $sql .=" and a.cod_partida = '".$this->data["datos"]["cod_partida"]."' ";

									   }




                 $condicion        = $sql.$group_by;


            $datos = $this->cfpd05->execute("SELECT

		    						  SUM(a.asignacion_anual)           as asignacion_anual,
									  SUM(a.aumento_traslado_anual)     as aumento_traslado_anual,
									  SUM(a.disminucion_traslado_anual) as disminucion_traslado_anual,
									  SUM(a.credito_adicional_anual)    as credito_adicional_anual,
									  SUM(a.rebaja_anual)               as rebaja_anual,
									  SUM(a.compromiso_anual)           as compromiso_anual,
									  SUM(a.causado_anual)              as causado_anual,
									  SUM(a.pagado_anual)               as pagado_anual

						 		      FROM cfpd05 a where ".$condicion.";");


                 $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_partida, a.deno_partida FROM v_cfpd05_denominaciones a WHERE  ". $sql." and  a.ano=".$year);

				 $parametros["titulo"][]  = "EJECUCIÓN POR PARTIDAS";
				 if(isset($rs[0][0]['cod_partida']) && !empty($this->data["datos"]["cod_partida"])){
				  $parametros["titulo"][]  = "PARTIDA: ".CE.".".separa_partida_de_grupo($rs[0][0]['cod_partida'])." - ".$rs[0][0]['deno_partida'].", AÑO RECURSO: ".$year;
				 }else{
				  $parametros["titulo"][]  = "AÑO RECURSO: ".$year;
				 }
                 $variables["nombre_total"] = "Asignación Ajustada";
                 $variables["nombre"][] = "Compromisos";
                 $variables["nombre"][] = "Causados";
                 $variables["nombre"][] = "Pagado";
                 $variables["nombre"][] = "Disponibilidad";
                 $variables["nombre"][] = "Deuda";

                 if(!empty($datos[0][0]['asignacion_anual']) && $datos[0][0]['asignacion_anual']!=0){
                 	    $asignacion_anual            =  $datos[0][0]['asignacion_anual'];
						$aumento_traslado_anual      =  $datos[0][0]['aumento_traslado_anual'];
						$credito_adicional_anual     =  $datos[0][0]['credito_adicional_anual'];
						$disminucion_traslado_anual  =  $datos[0][0]['disminucion_traslado_anual'];
						$rebaja_anual                =  $datos[0][0]['rebaja_anual'];
						$compromiso_anual    =  $datos[0][0]['compromiso_anual'];
						$causado_anual       =  $datos[0][0]['causado_anual'];
						$pagado_anual        =  $datos[0][0]['pagado_anual'];
						$asignacion_ajustada =  (($asignacion_anual+$aumento_traslado_anual+$credito_adicional_anual)-($disminucion_traslado_anual+$rebaja_anual));
						$deuda               = ($causado_anual-$pagado_anual);
						$disponibilidad      = ($asignacion_ajustada-$compromiso_anual);

		                 $variables["porcentaje_total"] = ($asignacion_ajustada * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($compromiso_anual * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($causado_anual * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($pagado_anual * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($disponibilidad * 100) / $asignacion_ajustada;
		                 $variables["porcentaje"][] = ($deuda * 100) / $asignacion_ajustada;
		                 $variables["monto_total"] = $asignacion_ajustada;
		                 $variables["monto"][] = $compromiso_anual;
		                 $variables["monto"][] = $causado_anual;
		                 $variables["monto"][] = $pagado_anual;
		                 $variables["monto"][] = $disponibilidad;
		                 $variables["monto"][] = $deuda;

		                 $parametros["tipo_top"]           = $tipo;
						 $parametros["DENO_REPUBLICA"]     = $deno_presi;
						 $parametros["DENO_ESTADO"]        = $deno_entidad;
						 $parametros["DENO_COD_TIPO_INST"] = $deno_tipo_inst;
						 $parametros["DENO_INST"]          = $deno_inst;

                 }else{
                 	     $variables["porcentaje_total"] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["monto_total"]  = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
                 }//fin else
                 $this->genera_grafica_1(1, $parametros, $variables);

	}//fin else if

$this->set('opcion', $var1);


}//fin function






function grafica_34($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){



  }else if($var1==2){

		$peticion=$this->data['casp01']['peticion'];
		$tipo_peticion=$this->data['casp01']['tipo_peticion'];
		$sexo=$this->data['casp01']['sexo'];
		if($sexo==1){
			$condsex=' 1=1';
			$titulo='TODAS LAS SOLICITUDES';
		}else if($sexo==2){
			$condsex="fecha_ayuda is not null";
			$titulo='SOLICITUDES ATENDIDAS';
		}else{
			$condsex="fecha_ayuda is null";
			$titulo='SOLICITUDES NO ATENDIDAS';
		}
		if($tipo_peticion==1){
			if(empty($this->data['casp01']['rango'])){
					if($peticion==1){
						$organismo=" where ".$condsex;
					}else if($peticion==2){
						$organismo=" where ".$this->condicionNDEP()." and ".$condsex;
					}else{
						$organismo=" where ".$this->SQLCA()." and ".$condsex;
					}
					$ver1=$this->v_casp01_relacion_solicitudes->execute("select count(numero_ocacion) as solicitudes_f,count(numero_documento_ayuda) as ayudas_f,sum(monto_total) as monto_ayudas_f from v_casp01_relacion_solicitudes ".$organismo." and sexo=2");
					$ver2=$this->v_casp01_relacion_solicitudes->execute("select count(numero_ocacion) as solicitudes_m,count(numero_documento_ayuda) as ayudas_m,sum(monto_total) as monto_ayudas_m from v_casp01_relacion_solicitudes ".$organismo." and sexo=1");
			}else{
				if($peticion==1){
					$organismo=" where ".$condsex;
				}else if($peticion==2){
					$organismo=" where ".$this->condicionNDEP()." and ".$condsex;
				}else{
					$organismo=" where ".$this->SQLCA()." and ".$condsex;
				}
				$fecha_inicial=$this->data['casp01']['fecha_inicial'];
				$fecha_final=$this->data['casp01']['fecha_final'];
				//v_casp01_relacion_solicitudes
//				$ver1=$this->v_casp01_relacion_solicitudes->execute("select count(numero_ocacion) as solicitudes,count(numero_documento_ayuda) as ayudas,sum(monto_total) as monto_ayudas from v_casp01_relacion_solicitudes where ".$organismo." (fecha_solicitud BETWEEN '$fecha_inicial' AND '$fecha_final')");

				$ver1=$this->v_casp01_relacion_solicitudes->execute("select count(numero_ocacion) as solicitudes_f,count(numero_documento_ayuda) as ayudas_f,sum(monto_total) as monto_ayudas_f from v_casp01_relacion_solicitudes ".$organismo." and sexo=2 and (fecha_solicitud BETWEEN '$fecha_inicial' AND '$fecha_final')");
				$ver2=$this->v_casp01_relacion_solicitudes->execute("select count(numero_ocacion) as solicitudes_m,count(numero_documento_ayuda) as ayudas_m,sum(monto_total) as monto_ayudas_m from v_casp01_relacion_solicitudes ".$organismo." and sexo=1 and (fecha_solicitud BETWEEN '$fecha_inicial' AND '$fecha_final')");


			}

			 $parametros["titulo"][]  = "AYUDAS ORDENADAS POR GÉNERO";
			 $parametros["titulo"][]  = $titulo;
             $variables["nombre_total"] = "Total";
             $variables["nombre"][] = "Femenino";
             $variables["nombre"][] = "Masculino";
             $parametros["tipo_cantidad"] = true;

			if(!empty($ver1[0][0]['solicitudes_f']) || !empty($ver2[0][0]['solicitudes_m'])){
					$total=($ver1[0][0]['solicitudes_f']+$ver2[0][0]['solicitudes_m']);
					$monto_total=($ver2[0][0]['monto_ayudas_m']+$ver1[0][0]['monto_ayudas_f']);
					if($sexo==1){
						$a=$ver2[0][0]['solicitudes_m'];
						$b=$ver1[0][0]['solicitudes_f'];
					}else if($sexo==2){
						$a=$ver2[0][0]['ayudas_m'];
						$b=$ver1[0][0]['ayudas_f'];
					}else{
						$a=$ver2[0][0]['solicitudes_m'];
						$b=$ver1[0][0]['solicitudes_f'];
					}

	                 $variables["porcentaje_total"] = ($total * 100) / $total;
	                 $variables["porcentaje"][] = ($b * 100) / $total;
	                 $variables["porcentaje"][] = ($a * 100) / $total;


                     $variables["monto_total"] = $monto_total;
                     $variables["monto"][] = $ver1[0][0]['monto_ayudas_f'];
	                 $variables["monto"][] = $ver2[0][0]['monto_ayudas_m'];


	                 $variables["cantidad_total"]   = $total;
	                 $variables["cantidad"][]       = $ver1[0][0]['solicitudes_f'];
	                 $variables["cantidad"][]       = $ver2[0][0]['solicitudes_m'];


			}else{
				     $variables["porcentaje_total"] = 0;
				     $variables["porcentaje"][] = 0;
				     $variables["porcentaje"][] = 0;

				     $variables["monto_total"] = 0;
				     $variables["monto"][]       = 0;
				     $variables["monto"][]       = 0;

				     $variables["cantidad_total"]   = 0;
	                 $variables["cantidad"][]       = 0;
	                 $variables["cantidad"][]       = 0;
			}//fin else

}else{
		if($peticion==1){
			$organismo=" and ".$condsex;
		}else if($peticion==2){
			$organismo=" and ".$this->condicionNDEP()." and ".$condsex;
		}else{
			$organismo=" and ".$this->SQLCA()." and ".$condsex;
		}

		if(empty($this->data['casp01']['rango'])){
			$tabla='v_casp01_relacion_solicitudes';
			$filtro=$organismo;
			$campos1="count(numero_ocacion) as solicitudes_f,count(numero_documento_ayuda) as ayudas_f,sum(monto_total) as monto_ayudas_f";
			$campos2="count(numero_ocacion) as solicitudes_m,count(numero_documento_ayuda) as ayudas_m,sum(monto_total) as monto_ayudas_m";
		}else{
			$fecha_inicial=$this->data['casp01']['fecha_inicial'];
			$fecha_final=$this->data['casp01']['fecha_final'];
			$tabla='v_casp01_relacion_solicitudes';
			$filtro=$organismo." and (fecha_solicitud BETWEEN '$fecha_inicial' AND '$fecha_final')";
			$campos1="count(numero_ocacion) as solicitudes_f,count(numero_documento_ayuda) as ayudas_f,sum(monto_total) as monto_ayudas_f";
			$campos2="count(numero_ocacion) as solicitudes_m,count(numero_documento_ayuda) as ayudas_m,sum(monto_total) as monto_ayudas_m";
		}

		if(!empty($this->data['casp01']['estado']) && empty($this->data['casp01']['cod_municipio']) && empty($this->data['casp01']['cod_parroquia']) && empty($this->data['casp01']['cod_centro'])){
			$estado=$this->data['casp01']['estado'];
			$ver2=$this->$tabla->execute("select ".$campos2." from ".$tabla." where cod_estado=".$estado.$filtro." and sexo=1");
			$ver1=$this->$tabla->execute("select ".$campos1." from ".$tabla." where cod_estado=".$estado.$filtro." and sexo=2");
		}else if(!empty($this->data['casp01']['estado']) && !empty($this->data['casp01']['cod_municipio']) && empty($this->data['casp01']['cod_parroquia']) && empty($this->data['casp01']['cod_centro'])){
			//solo el estado y municipio
			$estado=$this->data['casp01']['estado'];
			$municipio=$this->data['casp01']['cod_municipio'];
			$ver2=$this->$tabla->execute("select ".$campos2." from ".$tabla." where cod_estado=".$estado." and cod_municipio=".$municipio.$filtro." and sexo=1");
			$ver1=$this->$tabla->execute("select ".$campos1." from ".$tabla." where cod_estado=".$estado." and cod_municipio=".$municipio.$filtro." and sexo=2");
		}else if(!empty($this->data['casp01']['estado']) && !empty($this->data['casp01']['cod_municipio']) && !empty($this->data['casp01']['cod_parroquia']) && empty($this->data['casp01']['cod_centro'])){
			//solo el estado y municipio y parroquia
			$estado=$this->data['casp01']['estado'];
			$municipio=$this->data['casp01']['cod_municipio'];
			$parroquia=$this->data['casp01']['cod_parroquia'];
			$ver2=$this->$tabla->execute("select ".$campos2." from ".$tabla." where cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia.$filtro." and sexo=1");
			$ver1=$this->$tabla->execute("select ".$campos1." from ".$tabla." where cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia.$filtro." and sexo=2");
		}else if(!empty($this->data['casp01']['estado']) && !empty($this->data['casp01']['cod_municipio']) && !empty($this->data['casp01']['cod_parroquia']) && !empty($this->data['casp01']['cod_centro'])){
			//solo el estado y municipio y parroquia y centro
			$estado=$this->data['casp01']['estado'];
			$municipio=$this->data['casp01']['cod_municipio'];
			$parroquia=$this->data['casp01']['cod_parroquia'];
			$centro=$this->data['casp01']['cod_centro'];
			$ver2=$this->$tabla->execute("select ".$campos2." from ".$tabla." where cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia." and cod_centro_poblado=".$centro.$filtro." and sexo=1");
			$ver1=$this->$tabla->execute("select ".$campos1." from ".$tabla." where cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia." and cod_centro_poblado=".$centro.$filtro." and sexo=2");
		}else{
		}

		     $parametros["titulo"][]  = "AYUDAS ORDENADAS POR GÉNERO";
		     $parametros["titulo"][]  = $titulo;
             $variables["nombre_total"] = "Total";
             $variables["nombre"][] = "Femenino";
             $variables["nombre"][] = "Masculino";
             $parametros["tipo_cantidad"] = true;

			if(!empty($ver1[0][0]['solicitudes_f']) || !empty($ver2[0][0]['solicitudes_m'])){
					$total=($ver1[0][0]['solicitudes_f']+$ver2[0][0]['solicitudes_m']);
					$monto_total=($ver2[0][0]['monto_ayudas_m']+$ver1[0][0]['monto_ayudas_f']);
					if($sexo==1){
						$a=$ver2[0][0]['solicitudes_m'];
						$b=$ver1[0][0]['solicitudes_f'];
					}else if($sexo==2){
						$a=$ver2[0][0]['ayudas_m'];
						$b=$ver1[0][0]['ayudas_f'];
					}else{
						$a=$ver2[0][0]['solicitudes_m'];
						$b=$ver1[0][0]['solicitudes_f'];
					}

	                 $variables["porcentaje_total"] = ($total * 100) / $total;
	                 $variables["porcentaje"][] = ($b * 100) / $total;
	                 $variables["porcentaje"][] = ($a * 100) / $total;


                     $variables["monto_total"] = $monto_total;
                     $variables["monto"][] = $ver1[0][0]['monto_ayudas_f'];
	                 $variables["monto"][] = $ver2[0][0]['monto_ayudas_m'];


	                 $variables["cantidad_total"]   = $total;
	                 $variables["cantidad"][]       = $ver1[0][0]['solicitudes_f'];
	                 $variables["cantidad"][]       = $ver2[0][0]['solicitudes_m'];


			}else{
				     $variables["porcentaje_total"] = 0;
				     $variables["porcentaje"][] = 0;
				     $variables["porcentaje"][] = 0;

				     $variables["monto_total"] = 0;
				     $variables["monto"][]       = 0;
				     $variables["monto"][]       = 0;

				     $variables["cantidad_total"]   = 0;
	                 $variables["cantidad"][]       = 0;
	                 $variables["cantidad"][]       = 0;
			}//fin else


}//fin if

           $this->genera_grafica_1(1, $parametros, $variables);


	}//fin else if

$this->set('opcion', $var1);


}//fin function






function grafica_35($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){

	    	$this->layout="ajax";

					$tipo_impuesto=array(1=>'PATENTE DE INDUSTRIA Y COMERCIO',2=>'VEHÍCULOS',3=>'PROPAGANDA COMERCIAL',4=>'INMUEBLES URBANOS',5=>'ASEO DOMICILIARIO',6=>'ARRENDAMIENTO DE TIERRAS',7=>'CRÉDITOS DE VIVIENDA');
                    $this->set('tipo_impuesto',  $tipo_impuesto);

                    $datos  = $this->shd900_planillas_deuda_cobro_detalles->execute(" SELECT DISTINCT ano FROM shd900_planillas_deuda_cobro_detalles WHERE ".$this->condicion()." ORDER BY ano ASC");
					if(count($datos)!=0){
						foreach($datos as $n){
							$lista[$n[0]['ano']]=$n[0]['ano'];
					    }
					}else{
						$lista=array('0'=>'No existen datos');
					}
					$this->set("lista_ano", $lista);

					$ano_arranque = $this->shd000_arranque->ano($this->condicion());

		            $this->set("ano_arranque",$ano_arranque);

	}else if($var1==2){
					      $sql          = "";
			              $sql2         = "";

			                  if($this->data['reporte_hacienda2']['tipo_impuesto']==1){
			              $sql          = "";
			              $sql2         = "";
			              $this->set('tipo_impuesto', 0);
			            }else if($this->data['reporte_hacienda2']['tipo_impuesto']==2){
			           	 if(!empty($this->data['reporte_hacienda2']['impuesto'])){
			              $sql          .= " and a.cod_ingreso='".$this->data['reporte_hacienda2']['impuesto']."' ";
			              $sql2         .= " and b.cod_ingreso='".$this->data['reporte_hacienda2']['impuesto']."' ";
			              $this->set('tipo_impuesto', $this->data['reporte_hacienda2']['impuesto']);
			           	 }else{
			           	  $this->set('tipo_impuesto', 0);
			           	 }
			            }
			            if(!empty($this->data['reporte_hacienda2']['ano'])){
			              $sql          .= " and a.ano='".$this->data['reporte_hacienda2']['ano']."' ";
			              $sql2         .= " and b.ano='".$this->data['reporte_hacienda2']['ano']."' ";
			           	}
			            if(!empty($this->data['reporte_hacienda2']['impuesto'])){
			              $sql          .= " and a.cod_ingreso='".$this->data['reporte_hacienda2']['impuesto']."' ";
			              $sql2         .= " and b.cod_ingreso='".$this->data['reporte_hacienda2']['impuesto']."' ";
			              $tipo_impuesto = $this->data['reporte_hacienda2']['impuesto'];
			           	}else{
			              $tipo_impuesto = 0;
			           	}
			        $condicion  = 'a.cod_presi='.$this->Session->read('SScodpresi').'  and  a.cod_entidad='.$this->Session->read('SScodentidad').' and a.cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  a.cod_inst='.$this->Session->read('SScodinst').' and a.cod_dep='.$this->Session->read('SScoddep').' ';
			        $condicion2 = 'b.cod_presi='.$this->Session->read('SScodpresi').'  and  b.cod_entidad='.$this->Session->read('SScodentidad').' and b.cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  b.cod_inst='.$this->Session->read('SScodinst').' and b.cod_dep='.$this->Session->read('SScoddep').' ';

			        $group_by  ="  GROUP BY a.cod_presi,
										   a.cod_entidad,
										   a.cod_tipo_inst,
										   a.cod_inst ,
										   a.cod_dep";
					$group_by2 =" GROUP BY b.cod_presi,
										   b.cod_entidad,
										   b.cod_tipo_inst,
										   b.cod_inst ,
										   b.cod_dep";

			        $sql1 ="SELECT
			        		 a.cod_presi,
							 a.cod_entidad,
							 a.cod_tipo_inst,
							 a.cod_inst ,
							 a.cod_dep,";
					    $sql1.=" SUM((a.deuda_vigente+a.monto_recargo+a.monto_multa+a.monto_intereses)-a.monto_descuento) as monto_total, ";
						$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." and b.mes=1 ".$group_by2.")  as mes_1,";
						$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." and b.mes=2 ".$group_by2.")  as mes_2,";
						$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." and b.mes=3 ".$group_by2.")  as mes_3,";
						$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." and b.mes=4 ".$group_by2.")  as mes_4,";
						$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." and b.mes=5 ".$group_by2.")  as mes_5,";
						$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." and b.mes=6 ".$group_by2.")  as mes_6,";
						$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." and b.mes=7 ".$group_by2.")  as mes_7,";
						$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." and b.mes=8 ".$group_by2.")  as mes_8,";
						$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." and b.mes=9 ".$group_by2.")  as mes_9,";
						$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." and b.mes=10 ".$group_by2.") as mes_10,";
						$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." and b.mes=11 ".$group_by2.") as mes_11,";
						$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." and b.mes=12 ".$group_by2.") as mes_12";
			         $sql1 .= " FROM v_shd900_planillas_deuda_cobro_detalles_cobradores_2 a WHERE ".$condicion." ".$sql." ".$group_by." ";



					$ejecuta=$this->v_shd900_planillas_deuda_cobro_detalles_cobradores_2->execute($sql1);


			        if($ejecuta!=null){
						$this->set('datos',$ejecuta);
					}else{
						$this->set('datos',null);
					}
                    $tipo_impuesto_array=array(1=>'INDUSTRIA Y COMERCIO',2=>'VEHÍCULOS',3=>'PROPAGANDA COMERCIAL',4=>'INMUEBLES URBANOS',5=>'ASEO DOMICILIARIO',6=>'ARRENDAMIENTO DE TIERRAS',7=>'CRÉDITOS DE VIVIENDA');

					                  if($tipo_impuesto==1){$denominacion_impuesto = "TIPO DE IMPUESTO: ".$tipo_impuesto_array[1];
								}else if($tipo_impuesto==2){$denominacion_impuesto = "TIPO DE IMPUESTO: ".$tipo_impuesto_array[2];
								}else if($tipo_impuesto==3){$denominacion_impuesto = "TIPO DE IMPUESTO: ".$tipo_impuesto_array[3];
								}else if($tipo_impuesto==4){$denominacion_impuesto = "TIPO DE IMPUESTO: ".$tipo_impuesto_array[4];
								}else if($tipo_impuesto==5){$denominacion_impuesto = "TIPO DE IMPUESTO: ".$tipo_impuesto_array[5];
								}else if($tipo_impuesto==6){$denominacion_impuesto = "TIPO DE IMPUESTO: ".$tipo_impuesto_array[6];
								}else if($tipo_impuesto==7){$denominacion_impuesto = "TIPO DE IMPUESTO: ".$tipo_impuesto_array[7];
								}else{$denominacion_impuesto="TODOS LOS IMPUESTOS";}


					$parametros["titulo"][]  = "FACTURACIÓN";
					 if($denominacion_impuesto!=""){
					 	 $parametros["titulo"][] = $denominacion_impuesto;
					 }
					 $variables["nombre_total"] = "Total";
					 $variables["nombre"][] = "Enero";
					 $variables["nombre"][] = "Febrero";
					 $variables["nombre"][] = "Marzo";
					 $variables["nombre"][] = "Abril";
					 $variables["nombre"][] = "Mayo";
					 $variables["nombre"][] = "Junio";
					 $variables["nombre"][] = "Julio";
					 $variables["nombre"][] = "Agosto";
					 $variables["nombre"][] = "Septiembre";
					 $variables["nombre"][] = "Octubre";
					 $variables["nombre"][] = "Noviembre";
					 $variables["nombre"][] = "Diciembre";

					 $datos = $ejecuta;

					if(isset($datos[0][0]["monto_total"])){
							if($datos[0][0]["mes_1"]!=0  && $datos[0][0]["mes_1"]!=null){$monto_mes_1    = $datos[0][0]["mes_1"];}else{$monto_mes_1=0;}
							if($datos[0][0]["mes_2"]!=0  && $datos[0][0]["mes_2"]!=null){$monto_mes_2    = $datos[0][0]["mes_2"];}else{$monto_mes_2=0;}
							if($datos[0][0]["mes_3"]!=0  && $datos[0][0]["mes_3"]!=null){$monto_mes_3    = $datos[0][0]["mes_3"];}else{$monto_mes_3=0;}
							if($datos[0][0]["mes_4"]!=0  && $datos[0][0]["mes_4"]!=null){$monto_mes_4    = $datos[0][0]["mes_4"];}else{$monto_mes_4=0;}
							if($datos[0][0]["mes_5"]!=0  && $datos[0][0]["mes_5"]!=null){$monto_mes_5    = $datos[0][0]["mes_5"];}else{$monto_mes_5=0;}
							if($datos[0][0]["mes_6"]!=0  && $datos[0][0]["mes_6"]!=null){$monto_mes_6    = $datos[0][0]["mes_6"];}else{$monto_mes_6=0;}
							if($datos[0][0]["mes_7"]!=0  && $datos[0][0]["mes_7"]!=null){$monto_mes_7    = $datos[0][0]["mes_7"];}else{$monto_mes_7=0;}
							if($datos[0][0]["mes_8"]!=0  && $datos[0][0]["mes_8"]!=null){$monto_mes_8    = $datos[0][0]["mes_8"];}else{$monto_mes_8=0;}
							if($datos[0][0]["mes_9"]!=0  && $datos[0][0]["mes_9"]!=null){$monto_mes_9    = $datos[0][0]["mes_9"];}else{$monto_mes_9=0;}
							if($datos[0][0]["mes_10"]!=0 && $datos[0][0]["mes_10"]!=null){$monto_mes_10  = $datos[0][0]["mes_10"];}else{$monto_mes_10=0;}
							if($datos[0][0]["mes_11"]!=0 && $datos[0][0]["mes_11"]!=null){$monto_mes_11  = $datos[0][0]["mes_11"];}else{$monto_mes_11=0;}
							if($datos[0][0]["mes_12"]!=0 && $datos[0][0]["mes_12"]!=null){$monto_mes_12  = $datos[0][0]["mes_12"];}else{$monto_mes_12=0;}
							if($datos[0][0]["monto_total"]!=0 && $datos[0][0]["monto_total"]!=null){$monto_total  = $datos[0][0]["monto_total"];}else{$monto_total=0;}


							$por_mes_1 = ($monto_mes_1 * 100) /  $datos[0][0]["monto_total"];
							$por_mes_2 = ($monto_mes_2 * 100) /  $datos[0][0]["monto_total"];
							$por_mes_3 = ($monto_mes_3 * 100) /  $datos[0][0]["monto_total"];
							$por_mes_4 = ($monto_mes_4 * 100) /  $datos[0][0]["monto_total"];
							$por_mes_5 = ($monto_mes_5 * 100) /  $datos[0][0]["monto_total"];
							$por_mes_6 = ($monto_mes_6 * 100) /  $datos[0][0]["monto_total"];
							$por_mes_7 = ($monto_mes_7 * 100) /  $datos[0][0]["monto_total"];
							$por_mes_8 = ($monto_mes_8 * 100) /  $datos[0][0]["monto_total"];
							$por_mes_9 = ($monto_mes_9 * 100) /  $datos[0][0]["monto_total"];
							$por_mes_10 = ($monto_mes_10 * 100) /  $datos[0][0]["monto_total"];
							$por_mes_11 = ($monto_mes_11 * 100) /  $datos[0][0]["monto_total"];
							$por_mes_12 = ($monto_mes_12 * 100) /  $datos[0][0]["monto_total"];
							$por_total  = ($monto_total * 100) /  $datos[0][0]["monto_total"];

							}else{

							$por_mes_1 = 0;
							$por_mes_2 = 0;
							$por_mes_3 = 0;
							$por_mes_4 = 0;
							$por_mes_5 = 0;
							$por_mes_6 = 0;
							$por_mes_7 = 0;
							$por_mes_8 = 0;
							$por_mes_9 = 0;
							$por_mes_10 = 0;
							$por_mes_11 = 0;
							$por_mes_12 = 0;
							$por_total  = 0;

							$monto_mes_1 = 0;
							$monto_mes_2 = 0;
							$monto_mes_3 = 0;
							$monto_mes_4 = 0;
							$monto_mes_5 = 0;
							$monto_mes_6 = 0;
							$monto_mes_7 = 0;
							$monto_mes_8 = 0;
							$monto_mes_9 = 0;
							$monto_mes_10 = 0;
							$monto_mes_11 = 0;
							$monto_mes_12 = 0;
							$monto_total  = 0;
							}


                    $variables["monto_total"]  = $monto_total;
                    $variables["monto"][]      = $monto_mes_1;
                    $variables["monto"][]      = $monto_mes_2;
                    $variables["monto"][]      = $monto_mes_3;
                    $variables["monto"][]      = $monto_mes_4;
                    $variables["monto"][]      = $monto_mes_5;
                    $variables["monto"][]      = $monto_mes_6;
                    $variables["monto"][]      = $monto_mes_7;
                    $variables["monto"][]      = $monto_mes_8;
                    $variables["monto"][]      = $monto_mes_9;
                    $variables["monto"][]      = $monto_mes_10;
                    $variables["monto"][]      = $monto_mes_11;
                    $variables["monto"][]      = $monto_mes_12;

                    $variables["porcentaje_total"]  = $por_total;
					$variables["porcentaje"][] = $por_mes_1;
					$variables["porcentaje"][] = $por_mes_2;
					$variables["porcentaje"][] = $por_mes_3;
					$variables["porcentaje"][] = $por_mes_4;
					$variables["porcentaje"][] = $por_mes_5;
					$variables["porcentaje"][] = $por_mes_6;
					$variables["porcentaje"][] = $por_mes_7;
					$variables["porcentaje"][] = $por_mes_8;
					$variables["porcentaje"][] = $por_mes_9;
					$variables["porcentaje"][] = $por_mes_10;
					$variables["porcentaje"][] = $por_mes_11;
					$variables["porcentaje"][] = $por_mes_12;

					$parametros["torta"] = "no";
                    $this->genera_grafica_1(1, $parametros, $variables);


	}//fin else if

$this->set('opcion', $var1);


}//fin function




















function grafica_36($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){

	    	$this->layout="ajax";

					$tipo_impuesto=array(1=>'PATENTE DE INDUSTRIA Y COMERCIO',2=>'VEHÍCULOS',3=>'PROPAGANDA COMERCIAL',4=>'INMUEBLES URBANOS',5=>'ASEO DOMICILIARIO',6=>'ARRENDAMIENTO DE TIERRAS',7=>'CRÉDITOS DE VIVIENDA');
                    $this->set('tipo_impuesto',  $tipo_impuesto);

                    $datos  = $this->v_shd900_cobranza_acumulada_deno_part->execute(" SELECT DISTINCT ano FROM v_shd900_cobranza_acumulada_denominacion_partida WHERE ".$this->condicion()." ORDER BY ano ASC");
					if(count($datos)!=0){
						foreach($datos as $n){
							$lista[$n[0]['ano']]=$n[0]['ano'];
					    }
					}else{
						$lista=array('0'=>'No existen datos');
					}
					$this->set("lista_ano", $lista);

					$ano_arranque = $this->shd000_arranque->ano($this->condicion());

		            $this->set("ano_arranque",$ano_arranque);

	}else if($var1==2){
					      $sql          = " ";
			              $sql2         = " ";
			                  if($this->data['reporte_hacienda2']['tipo_impuesto']==1){
			              $sql          .= "";
			              $sql2         .= "";
			              $this->set('tipo_impuesto', 0);
			              $denominacion_impuesto = "";
			              $tipo_impuesto = 0;
			            }else if($this->data['reporte_hacienda2']['tipo_impuesto']==2){
			           	 if(!empty($this->data['reporte_hacienda2']['impuesto'])){
			           	  $impuesto = split("-",$this->data['reporte_hacienda2']['impuesto']);
			           	  $tipo_impuesto = $this->data['reporte_hacienda2']['impuesto'];
			              $sql          .= " and a.cod_partida='".$impuesto[0]."' and a.cod_generica='".$impuesto[1]."' and a.cod_especifica='".$impuesto[2]."' and a.cod_sub_espec='".$impuesto[3]."' and a.cod_auxiliar='".$impuesto[4]."'";
			              $sql2         .= " and b.cod_partida='".$impuesto[0]."' and b.cod_generica='".$impuesto[1]."' and b.cod_especifica='".$impuesto[2]."' and b.cod_sub_espec='".$impuesto[3]."' and b.cod_auxiliar='".$impuesto[4]."'";
			              $sql2_aux      = " and   cod_partida='".$impuesto[0]."' and   cod_generica='".$impuesto[1]."' and   cod_especifica='".$impuesto[2]."' and   cod_sub_espec='".$impuesto[3]."' and   cod_auxiliar='".$impuesto[4]."'";
			              $this->set('tipo_impuesto', $this->data['reporte_hacienda2']['impuesto']);
			              $datos_aux  = $this->v_shd900_cobranza_acumulada_deno_part->execute(" SELECT DISTINCT cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, deno_partida, deno_generica, deno_especifica, deno_sub_espe, deno_auxiliar FROM v_shd900_cobranza_acumulada_denominacion_partida WHERE ".$this->condicion()." ".$sql2_aux." ORDER BY cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC");
						  $deno_partida    = $datos_aux[0][0]['deno_partida'];
						  $deno_generica   = $datos_aux[0][0]['deno_generica'];
						  $deno_especifica = $datos_aux[0][0]['deno_especifica'];
						  $deno_sub_espe   = $datos_aux[0][0]['deno_sub_espe'];
						  $deno_auxiliar   = $datos_aux[0][0]['deno_auxiliar'];
													  if($deno_auxiliar==null || $deno_auxiliar==""){
													  	 if($deno_sub_espe==null || $deno_sub_espe==""){
													  	 	if($deno_especifica==null || $deno_especifica==""){
													  	 		if($deno_generica==null || $deno_generica==""){
													  	 			if($deno_partida==null || $deno_partida==""){
											                               $denominacion_impuesto = "";
																	  }else{
																	  	 $denominacion_impuesto = $datos_aux[0][0]['deno_partida'];
																	  }
																  }else{
																  	 $denominacion_impuesto = $datos_aux[0][0]['deno_generica'];
																  }
															  }else{
															  	 $denominacion_impuesto = $datos_aux[0][0]['deno_especifica'];
															  }
														  }else{
														  	 $denominacion_impuesto = $datos_aux[0][0]['deno_sub_espe'];
														  }
													  }else{
													  	 $denominacion_impuesto = $datos_aux[0][0]['deno_auxiliar'];
													  }
			           	 }else{
			           	  $denominacion_impuesto = "";
			           	  $this->set('tipo_impuesto', 0);
			           	  $tipo_impuesto = 0;
			           	 }
			            }
			            if(!empty($this->data['reporte_hacienda2']['ano'])){
			              $sql          .= " and a.ano='".$this->data['reporte_hacienda2']['ano']."' ";
			              $sql2         .= " and b.ano='".$this->data['reporte_hacienda2']['ano']."' ";
			           	}
			        $condicion  = 'a.cod_presi='.$this->Session->read('SScodpresi').'  and  a.cod_entidad='.$this->Session->read('SScodentidad').' and a.cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  a.cod_inst='.$this->Session->read('SScodinst').' and a.cod_dep='.$this->Session->read('SScoddep').' ';
			        $condicion2 = 'b.cod_presi='.$this->Session->read('SScodpresi').'  and  b.cod_entidad='.$this->Session->read('SScodentidad').' and b.cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  b.cod_inst='.$this->Session->read('SScodinst').' and b.cod_dep='.$this->Session->read('SScoddep').' ';

			        $group_by  ="  GROUP BY a.cod_presi,
										   a.cod_entidad,
										   a.cod_tipo_inst,
										   a.cod_inst ,
										   a.cod_dep";
					$group_by2 =" GROUP BY b.cod_presi,
										   b.cod_entidad,
										   b.cod_tipo_inst,
										   b.cod_inst ,
										   b.cod_dep";

			        $sql1 ="SELECT
			        		 a.cod_presi,
							 a.cod_entidad,
							 a.cod_tipo_inst,
							 a.cod_inst ,
							 a.cod_dep,";
					    $sql1.=" SUM((a.deuda_vigente+a.monto_recargo+a.monto_multa+a.monto_intereses)-a.monto_descuento) as monto_total, ";
						$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where ".$condicion2." ".$sql2." and b.mes=1 ".$group_by2.")  as mes_1,";
						$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where ".$condicion2." ".$sql2." and b.mes=2 ".$group_by2.")  as mes_2,";
						$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where ".$condicion2." ".$sql2." and b.mes=3 ".$group_by2.")  as mes_3,";
						$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where ".$condicion2." ".$sql2." and b.mes=4 ".$group_by2.")  as mes_4,";
						$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where ".$condicion2." ".$sql2." and b.mes=5 ".$group_by2.")  as mes_5,";
						$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where ".$condicion2." ".$sql2." and b.mes=6 ".$group_by2.")  as mes_6,";
						$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where ".$condicion2." ".$sql2." and b.mes=7 ".$group_by2.")  as mes_7,";
						$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where ".$condicion2." ".$sql2." and b.mes=8 ".$group_by2.")  as mes_8,";
						$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where ".$condicion2." ".$sql2." and b.mes=9 ".$group_by2.")  as mes_9,";
						$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where ".$condicion2." ".$sql2." and b.mes=10 ".$group_by2.") as mes_10,";
						$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where ".$condicion2." ".$sql2." and b.mes=11 ".$group_by2.") as mes_11,";
						$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where ".$condicion2." ".$sql2." and b.mes=12 ".$group_by2.") as mes_12";
			         $sql1 .= " FROM v_shd900_cobranza_acumulada_denominacion_partida a WHERE ".$condicion." ".$sql." ".$group_by." ";

					$ejecuta=$this->v_shd900_cobranza_acumulada_deno_part->execute($sql1);


					$this->set('deno',$denominacion_impuesto);
			        if($ejecuta!=null){
						$this->set('datos',$ejecuta);
					}else{
						$this->set('datos',null);
					}
                    $tipo_impuesto_array=array(1=>'INDUSTRIA Y COMERCIO',2=>'VEHÍCULOS',3=>'PROPAGANDA COMERCIAL',4=>'INMUEBLES URBANOS',5=>'ASEO DOMICILIARIO',6=>'ARRENDAMIENTO DE TIERRAS',7=>'CRÉDITOS DE VIVIENDA');

					              if($denominacion_impuesto!=""){$denominacion_impuesto = "TIPO DE INGRESO: ".$denominacion_impuesto;
								}else{$denominacion_impuesto="TODOS LOS INGRESOS";}

					$parametros["titulo"][]  = "COBRANZA";
					 if($denominacion_impuesto!=""){
					 	 $parametros["titulo"][] = $denominacion_impuesto;
					 }
					 $variables["nombre_total"] = "Total";
					 $variables["nombre"][] = "Enero";
					 $variables["nombre"][] = "Febrero";
					 $variables["nombre"][] = "Marzo";
					 $variables["nombre"][] = "Abril";
					 $variables["nombre"][] = "Mayo";
					 $variables["nombre"][] = "Junio";
					 $variables["nombre"][] = "Julio";
					 $variables["nombre"][] = "Agosto";
					 $variables["nombre"][] = "Septiembre";
					 $variables["nombre"][] = "Octubre";
					 $variables["nombre"][] = "Noviembre";
					 $variables["nombre"][] = "Diciembre";

					 $datos = $ejecuta;

					if(isset($datos[0][0]["monto_total"])){
							if($datos[0][0]["mes_1"]!=0  && $datos[0][0]["mes_1"]!=null){$monto_mes_1    = $datos[0][0]["mes_1"];}else{$monto_mes_1=0;}
							if($datos[0][0]["mes_2"]!=0  && $datos[0][0]["mes_2"]!=null){$monto_mes_2    = $datos[0][0]["mes_2"];}else{$monto_mes_2=0;}
							if($datos[0][0]["mes_3"]!=0  && $datos[0][0]["mes_3"]!=null){$monto_mes_3    = $datos[0][0]["mes_3"];}else{$monto_mes_3=0;}
							if($datos[0][0]["mes_4"]!=0  && $datos[0][0]["mes_4"]!=null){$monto_mes_4    = $datos[0][0]["mes_4"];}else{$monto_mes_4=0;}
							if($datos[0][0]["mes_5"]!=0  && $datos[0][0]["mes_5"]!=null){$monto_mes_5    = $datos[0][0]["mes_5"];}else{$monto_mes_5=0;}
							if($datos[0][0]["mes_6"]!=0  && $datos[0][0]["mes_6"]!=null){$monto_mes_6    = $datos[0][0]["mes_6"];}else{$monto_mes_6=0;}
							if($datos[0][0]["mes_7"]!=0  && $datos[0][0]["mes_7"]!=null){$monto_mes_7    = $datos[0][0]["mes_7"];}else{$monto_mes_7=0;}
							if($datos[0][0]["mes_8"]!=0  && $datos[0][0]["mes_8"]!=null){$monto_mes_8    = $datos[0][0]["mes_8"];}else{$monto_mes_8=0;}
							if($datos[0][0]["mes_9"]!=0  && $datos[0][0]["mes_9"]!=null){$monto_mes_9    = $datos[0][0]["mes_9"];}else{$monto_mes_9=0;}
							if($datos[0][0]["mes_10"]!=0 && $datos[0][0]["mes_10"]!=null){$monto_mes_10  = $datos[0][0]["mes_10"];}else{$monto_mes_10=0;}
							if($datos[0][0]["mes_11"]!=0 && $datos[0][0]["mes_11"]!=null){$monto_mes_11  = $datos[0][0]["mes_11"];}else{$monto_mes_11=0;}
							if($datos[0][0]["mes_12"]!=0 && $datos[0][0]["mes_12"]!=null){$monto_mes_12  = $datos[0][0]["mes_12"];}else{$monto_mes_12=0;}
							if($datos[0][0]["monto_total"]!=0 && $datos[0][0]["monto_total"]!=null){$monto_total  = $datos[0][0]["monto_total"];}else{$monto_total=0;}


							$por_mes_1 = ($monto_mes_1 * 100) /  $datos[0][0]["monto_total"];
							$por_mes_2 = ($monto_mes_2 * 100) /  $datos[0][0]["monto_total"];
							$por_mes_3 = ($monto_mes_3 * 100) /  $datos[0][0]["monto_total"];
							$por_mes_4 = ($monto_mes_4 * 100) /  $datos[0][0]["monto_total"];
							$por_mes_5 = ($monto_mes_5 * 100) /  $datos[0][0]["monto_total"];
							$por_mes_6 = ($monto_mes_6 * 100) /  $datos[0][0]["monto_total"];
							$por_mes_7 = ($monto_mes_7 * 100) /  $datos[0][0]["monto_total"];
							$por_mes_8 = ($monto_mes_8 * 100) /  $datos[0][0]["monto_total"];
							$por_mes_9 = ($monto_mes_9 * 100) /  $datos[0][0]["monto_total"];
							$por_mes_10 = ($monto_mes_10 * 100) /  $datos[0][0]["monto_total"];
							$por_mes_11 = ($monto_mes_11 * 100) /  $datos[0][0]["monto_total"];
							$por_mes_12 = ($monto_mes_12 * 100) /  $datos[0][0]["monto_total"];
							$por_total  = ($monto_total * 100) /  $datos[0][0]["monto_total"];

							}else{

							$por_mes_1 = 0;
							$por_mes_2 = 0;
							$por_mes_3 = 0;
							$por_mes_4 = 0;
							$por_mes_5 = 0;
							$por_mes_6 = 0;
							$por_mes_7 = 0;
							$por_mes_8 = 0;
							$por_mes_9 = 0;
							$por_mes_10 = 0;
							$por_mes_11 = 0;
							$por_mes_12 = 0;
							$por_total  = 0;

							$monto_mes_1 = 0;
							$monto_mes_2 = 0;
							$monto_mes_3 = 0;
							$monto_mes_4 = 0;
							$monto_mes_5 = 0;
							$monto_mes_6 = 0;
							$monto_mes_7 = 0;
							$monto_mes_8 = 0;
							$monto_mes_9 = 0;
							$monto_mes_10 = 0;
							$monto_mes_11 = 0;
							$monto_mes_12 = 0;
							$monto_total  = 0;
							}


                    $variables["monto_total"]  = $monto_total;
                    $variables["monto"][]      = $monto_mes_1;
                    $variables["monto"][]      = $monto_mes_2;
                    $variables["monto"][]      = $monto_mes_3;
                    $variables["monto"][]      = $monto_mes_4;
                    $variables["monto"][]      = $monto_mes_5;
                    $variables["monto"][]      = $monto_mes_6;
                    $variables["monto"][]      = $monto_mes_7;
                    $variables["monto"][]      = $monto_mes_8;
                    $variables["monto"][]      = $monto_mes_9;
                    $variables["monto"][]      = $monto_mes_10;
                    $variables["monto"][]      = $monto_mes_11;
                    $variables["monto"][]      = $monto_mes_12;

                    $variables["porcentaje_total"]  = $por_total;
					$variables["porcentaje"][] = $por_mes_1;
					$variables["porcentaje"][] = $por_mes_2;
					$variables["porcentaje"][] = $por_mes_3;
					$variables["porcentaje"][] = $por_mes_4;
					$variables["porcentaje"][] = $por_mes_5;
					$variables["porcentaje"][] = $por_mes_6;
					$variables["porcentaje"][] = $por_mes_7;
					$variables["porcentaje"][] = $por_mes_8;
					$variables["porcentaje"][] = $por_mes_9;
					$variables["porcentaje"][] = $por_mes_10;
					$variables["porcentaje"][] = $por_mes_11;
					$variables["porcentaje"][] = $por_mes_12;

					$parametros["torta"] = "no";
                    $this->genera_grafica_1(1, $parametros, $variables);


	}//fin else if

$this->set('opcion', $var1);


}//fin function





function grafica_37($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){
				      /* for($minCount = 2007; $minCount < 2030; $minCount++) {
					    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
					    $this->set('anos',$anos);
				       }
				        $dato = $this->cfpd01_formulacion->findAll($this->condicionNDEP(), null, 'ano_formular ASC', null);
						foreach($dato as $ve){
							$year = $ve['cfpd01_formulacion']['ano_formular'];
						}
			        $year = $this->ano_ejecucion();
				        $this->Session->write('ano_recurso_asignacion', $year);
				         $this->set('year', $year!=null ? $year : date('Y'));*/
	}else if($var1==2){
                       /* $consolidacion= !isset($this->data['datos']['consolidacion'])?2:$this->data['datos']['consolidacion'];
                       if(isset($this->data['datos']['ano'])){           $year          = $this->data["datos"]["ano"];}

                        if(isset($consolidacion) && $this->verifica_SS(5)==1){
								        if($consolidacion==1){
								           $cod_dep="";
								           $cod_dep2=""; and x.ano_ordenanza=".$year."
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror="";
								           $this->set("opcion",1);
								        }else{
								           $cod_dep="a.cod_dep,";
								           $cod_dep2="x.cod_dep=a.cod_dep and";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror=",a.cod_dep";
								           $this->set("opcion",2);
								        }
								    }else{
								        $cod_dep="a.cod_dep,";
								           $cod_dep2="x.cod_dep=a.cod_dep and";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror=",a.cod_dep";
								           $this->set("opcion",2);
								    }

*/

								           $cod_dep="a.cod_dep,";
								           $cod_dep2="x.cod_dep=a.cod_dep and";
								           $con=$this->SQLCA_consolidado_opcion(1, "a");
								           $gror=",a.cod_dep";

								        $datos=$this->cfpd05->execute("SELECT
																						a.cod_presi,
																						a.cod_entidad,
																						a.cod_tipo_inst,
																						a.cod_inst,
																						".$cod_dep."
																								(SELECT COUNT(x.radio_tenencia) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia=1) as propiedad,     (SELECT SUM(x.terreno_valor_total) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia=1) AS monto_propiedad,
																								(SELECT COUNT(x.radio_tenencia) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia=2) as arrendamiento, (SELECT SUM(x.terreno_valor_total) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia=2) AS monto_arrendamiento,
																								(SELECT COUNT(x.radio_tenencia) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia=3) as comodato,      (SELECT SUM(x.terreno_valor_total) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia=3) AS monto_comodato,
																								(SELECT COUNT(x.radio_tenencia) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia=4) as anticresis,	(SELECT SUM(x.terreno_valor_total) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia=4) AS monto_anticresis,
																								(SELECT COUNT(x.radio_tenencia) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia=5) as enfiteusis,    (SELECT SUM(x.terreno_valor_total) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia=5) AS monto_enfiteusis,
																								(SELECT COUNT(x.radio_tenencia) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia=6) as usufructo,     (SELECT SUM(x.terreno_valor_total) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia=6) AS monto_usufructo,
																								(SELECT COUNT(x.radio_tenencia) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia=7) as derecho_uso,   (SELECT SUM(x.terreno_valor_total) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia=7) AS monto_derecho_uso,
																								(SELECT COUNT(x.radio_tenencia) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia=8) as derecho_hab,   (SELECT SUM(x.terreno_valor_total) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia=8) AS monto_derecho_hab,
																								(SELECT COUNT(x.radio_tenencia) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia=9) as otros,         (SELECT SUM(x.terreno_valor_total) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia=9) AS monto_otros,
																								(SELECT COUNT(x.radio_tenencia) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." 1=1) as total, 					    (SELECT SUM(x.terreno_valor_total) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." 1=1) AS monto_total
																						  FROM
																						catd02_ficha_datos a
																						WHERE
																						   	".$con."
																						group by
																								a.cod_presi,
																								a.cod_entidad,
																								a.cod_tipo_inst,
																								a.cod_inst
																						".$gror."
																						order by
																								a.cod_presi,
																								a.cod_entidad,
																								a.cod_tipo_inst,
																								a.cod_inst
																						".$gror.";");

                         foreach($datos as $row){
						 	$propiedad = $row[0]['propiedad'];
						 	$arrendamiento = $row[0]['arrendamiento'];
						 	$comodato = $row[0]['comodato'];
						 	$anticresis = $row[0]['anticresis'];
						 	$enfiteusis = $row[0]['enfiteusis'];
						 	$usufructo = $row[0]['usufructo'];
						 	$derecho_uso = $row[0]['derecho_uso'];
						 	$derecho_hab = $row[0]['derecho_hab'];
						 	$otros = $row[0]['otros'];
						 	$total = $row[0]['total'];
						 	$monto_propiedad = $row[0]['monto_propiedad'];
						 	$monto_arrendamiento = $row[0]['monto_arrendamiento'];
						 	$monto_comodato = $row[0]['monto_comodato'];
						 	$monto_anticresis = $row[0]['monto_anticresis'];
						    $monto_enfiteusis = $row[0]['monto_enfiteusis'];
						 	$monto_usufructo = $row[0]['monto_usufructo'];
						 	$monto_derecho_uso = $row[0]['monto_derecho_uso'];
						 	$monto_derecho_hab = $row[0]['monto_derecho_hab'];
						 	$monto_otros = $row[0]['monto_otros'];
						 	$monto_total = $row[0]['monto_total'];
						 }

				 $parametros["titulo"][]  = "TENENCIA DEL TERRENO";
				// $parametros["titulo"][]  = " AÑO: ".$year;
				 $parametros["tipo_cantidad"] = true;
                 $variables["nombre_total"] = "Total";
                 $variables["nombre"][] = "Propiedad";
                 $variables["nombre"][] = "Arrendamiento";
                 $variables["nombre"][] = "Comodato";
                 $variables["nombre"][] = "Anticresis";
                 $variables["nombre"][] = "Enfiteusis";
                 $variables["nombre"][] = "Usufructo";
                 $variables["nombre"][] = "Derecho de Uso";
                 $variables["nombre"][] = "Derecho de Habitación";
                 $variables["nombre"][] = "Otros";

                 if(isset($datos[0][0]['total'])){
		                 $variables["porcentaje_total"] = ($total * 100) / $total;
		                 $variables["porcentaje"][] = ($propiedad * 100) / $total;
		                 $variables["porcentaje"][] = ($arrendamiento * 100) / $total;
		                 $variables["porcentaje"][] = ($comodato * 100) / $total;
		                 $variables["porcentaje"][] = ($anticresis * 100) / $total;
		                 $variables["porcentaje"][] = ($enfiteusis * 100) / $total;
		                 $variables["porcentaje"][] = ($usufructo * 100) / $total;
		                 $variables["porcentaje"][] = ($derecho_uso * 100) / $total;
		                 $variables["porcentaje"][] = ($derecho_hab * 100) / $total;
		                 $variables["porcentaje"][] = ($otros * 100) / $total;
		                 $variables["cantidad_total"] = $total;
		                 $variables["cantidad"][] = $propiedad;
		                 $variables["cantidad"][] = $arrendamiento;
		                 $variables["cantidad"][] = $comodato;
		                 $variables["cantidad"][] = $anticresis;
		                 $variables["cantidad"][] = $enfiteusis;
		                 $variables["cantidad"][] = $usufructo;
		                 $variables["cantidad"][] = $derecho_uso;
		                 $variables["cantidad"][] = $derecho_hab;
		                 $variables["cantidad"][] = $otros;
		                 $variables["monto_total"]  = $monto_total;
		                 $variables["monto"][] = $monto_propiedad;
		                 $variables["monto"][] = $monto_arrendamiento;
		                 $variables["monto"][] = $monto_comodato;
		                 $variables["monto"][] = $monto_anticresis;
		                 $variables["monto"][] = $monto_enfiteusis;
						 $variables["monto"][] = $monto_usufructo;
		                 $variables["monto"][] = $monto_derecho_uso;
		                 $variables["monto"][] = $monto_derecho_hab;
		                 $variables["monto"][] = $monto_otros;
                 }else{
                 	     $variables["porcentaje_total"] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["cantidad_total"] = 0;
		                 $variables["cantidad"][] = 0;
		                 $variables["cantidad"][] = 0;
		                 $variables["cantidad"][] = 0;
		                 $variables["cantidad"][] = 0;
		                 $variables["cantidad"][] = 0;
		                 $variables["cantidad"][] = 0;
		                 $variables["cantidad"][] = 0;
		                 $variables["cantidad"][] = 0;
		                 $variables["monto_total"]  = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
						 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
                 }//fin else
                 $this->genera_grafica_1(1, $parametros, $variables);

	}//fin else if

$this->set('opcion', $var1);


}//fin function





function grafica_38($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){
				      /* for($minCount = 2007; $minCount < 2030; $minCount++) {
					    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
					    $this->set('anos',$anos);
				       }
				         $dato = $this->cfpd01_formulacion->findAll($this->condicionNDEP(), null, 'ano_formular ASC', null);
						foreach($dato as $ve){
							$year = $ve['cfpd01_formulacion']['ano_formular'];
						}
 				        $year = $this->ano_ejecucion();
				        $this->Session->write('ano_recurso_asignacion', $year);
				        $this->set('year', $year!=null ? $year : date('Y'));*/
	}else if($var1==2){
                       /* $consolidacion= !isset($this->data['datos']['consolidacion'])?2:$this->data['datos']['consolidacion'];
                       if(isset($this->data['datos']['ano'])){           $year          = $this->data["datos"]["ano"];}

                        if(isset($consolidacion) && $this->verifica_SS(5)==1){
								        if($consolidacion==1){
								           $cod_dep="";
								           $cod_dep2="";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror="";
								           $this->set("opcion",1);
								        }else{
								           $cod_dep="a.cod_dep,";
								           $cod_dep2="x.cod_dep=a.cod_dep and";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror=",a.cod_dep";
								           $this->set("opcion",2);
								        }
								    }else{
								        $cod_dep="a.cod_dep,";
								           $cod_dep2="x.cod_dep=a.cod_dep and";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror=",a.cod_dep";
								           $this->set("opcion",2);
								    }*/

								     $cod_dep="a.cod_dep,";
								           $cod_dep2="x.cod_dep=a.cod_dep and";
								           $con=$this->SQLCA_consolidado_opcion(1, "a");
								           $gror=",a.cod_dep";

								        $datos=$this->cfpd05->execute("SELECT
																						a.cod_presi,
																						a.cod_entidad,
																						a.cod_tipo_inst,
																						a.cod_inst,
																						".$cod_dep."
																								(SELECT COUNT(x.radio_regimen) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_regimen=1 ) as ejido,            (SELECT SUM(x.terreno_valor_total) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia=1 ) AS monto_ejido,
																								(SELECT COUNT(x.radio_regimen) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_regimen=2 ) as municipal_propio, (SELECT SUM(x.terreno_valor_total) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia=2 ) AS monto_municipal_propio,
																								(SELECT COUNT(x.radio_regimen) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_regimen=3 ) as nacional,         (SELECT SUM(x.terreno_valor_total) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia=3 ) AS monto_nacional,
																								(SELECT COUNT(x.radio_regimen) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_regimen=4 ) as baldio,			  (SELECT SUM(x.terreno_valor_total) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia=4 ) AS monto_baldio,
																								(SELECT COUNT(x.radio_regimen) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_regimen=5 ) as estatal,		   (SELECT SUM(x.terreno_valor_total) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia=5 ) AS monto_estatal,
																								(SELECT COUNT(x.radio_regimen) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_regimen=6 ) as privado_industrial,(SELECT SUM(x.terreno_valor_total) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia=6 ) AS monto_privado_industrial,
																								(SELECT COUNT(x.radio_regimen) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_regimen=7 ) as privado_condominio,(SELECT SUM(x.terreno_valor_total) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia=7 ) AS monto_privado_condominio,
																								(SELECT COUNT(x.radio_regimen) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_regimen=8 ) as otros,             (SELECT SUM(x.terreno_valor_total) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia=8 ) AS monto_otros,
																								(SELECT COUNT(x.radio_regimen) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." 1=1) as total,							   (SELECT SUM(x.terreno_valor_total) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." 1=1) monto_total
																						  FROM
																						catd02_ficha_datos a
																						WHERE
																						   	".$con."
																						group by
																								a.cod_presi,
																								a.cod_entidad,
																								a.cod_tipo_inst,
																								a.cod_inst
																						".$gror."
																						order by
																								a.cod_presi,
																								a.cod_entidad,
																								a.cod_tipo_inst,
																								a.cod_inst
																						".$gror.";");

                         foreach($datos as $row){
						 	$ejido = $row[0]['ejido'];
						 	$municipal_propio = $row[0]['municipal_propio'];
						 	$nacional = $row[0]['nacional'];
						 	$baldio = $row[0]['baldio'];
						 	$estatal = $row[0]['estatal'];
						 	$privado_industrial = $row[0]['privado_industrial'];
						 	$privado_condominio = $row[0]['privado_condominio'];
						 	$otros = $row[0]['otros'];
						 	$total = $row[0]['total'];
						 	$monto_ejido = $row[0]['monto_ejido'];
						 	$monto_municipal_propio = $row[0]['monto_municipal_propio'];
						 	$monto_nacional = $row[0]['monto_nacional'];
						 	$monto_baldio = $row[0]['monto_baldio'];
						 	$monto_estatal = $row[0]['monto_estatal'];
						 	$monto_privado_industrial = $row[0]['monto_privado_industrial'];
						 	$monto_privado_condominio = $row[0]['monto_privado_condominio'];
						 	$monto_otros = $row[0]['monto_otros'];
						 	$monto_total = $row[0]['monto_total'];
						 }

				 $parametros["titulo"][]  = "RÉGIMEN PROPIEDAD DEL TERRENO";
				 // $parametros["titulo"][]  = " AÑO: ".$year;
				 $parametros["tipo_cantidad"] = true;
                 $variables["nombre_total"] = "Total";
                 $variables["nombre"][] = "Ejido";
                 $variables["nombre"][] = "Municipal Propio";
                 $variables["nombre"][] = "Nacional";
                 $variables["nombre"][] = "Baldío";
                 $variables["nombre"][] = "Estatal";
                 $variables["nombre"][] = "Privado Industrial";
                 $variables["nombre"][] = "Privado Condominio";
                 $variables["nombre"][] = "Otros";

                 if(isset($datos[0][0]['total'])){
		                 $variables["porcentaje_total"] = ($total * 100) / $total;
		                 $variables["porcentaje"][] = ($ejido * 100) / $total;
		                 $variables["porcentaje"][] = ($municipal_propio * 100) / $total;
		                 $variables["porcentaje"][] = ($nacional * 100) / $total;
		                 $variables["porcentaje"][] = ($baldio * 100) / $total;
		                 $variables["porcentaje"][] = ($estatal * 100) / $total;
		                 $variables["porcentaje"][] = ($privado_industrial * 100) / $total;
		                 $variables["porcentaje"][] = ($privado_condominio * 100) / $total;
		                 $variables["porcentaje"][] = ($otros * 100) / $total;
		                 $variables["cantidad_total"] = $total;
		                 $variables["cantidad"][] = $ejido;
		                 $variables["cantidad"][] = $municipal_propio;
		                 $variables["cantidad"][] = $nacional;
		                 $variables["cantidad"][] = $baldio;
		                 $variables["cantidad"][] = $estatal;
		                 $variables["cantidad"][] = $privado_industrial;
		                 $variables["cantidad"][] = $privado_condominio;
		                 $variables["cantidad"][] = $otros;
		                 $variables["monto_total"] =$monto_total;
		                 $variables["monto"][] = $monto_ejido;
		                 $variables["monto"][] = $monto_municipal_propio;
		                 $variables["monto"][] = $monto_nacional;
		                 $variables["monto"][] = $monto_baldio;
		                 $variables["monto"][] = $monto_estatal;
		                 $variables["monto"][] = $monto_privado_industrial;
		                 $variables["monto"][] = $monto_privado_condominio;
		                 $variables["monto"][] = $monto_otros;


                 }else{
                 	     $variables["porcentaje_total"] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                  $variables["cantidad_total"] = 0;
		                 $variables["cantidad"][] = 0;
		                 $variables["cantidad"][] = 0;
		                 $variables["cantidad"][] = 0;
		                 $variables["cantidad"][] = 0;
		                 $variables["cantidad"][] = 0;
		                 $variables["cantidad"][] = 0;
		                 $variables["cantidad"][] = 0;
		                 $variables["cantidad"][] = 0;
		                 $variables["monto_total"]  = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
						 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
                 }//fin else
                 $this->genera_grafica_1(1, $parametros, $variables);

	}//fin else if

$this->set('opcion', $var1);


}//fin function

function grafica_39($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){
				     /* for($minCount = 2007; $minCount < 2030; $minCount++) {
					    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
					    $this->set('anos',$anos);
				       }
				        $dato = $this->cfpd01_formulacion->findAll($this->condicionNDEP(), null, 'ano_formular ASC', null);
						foreach($dato as $ve){
							$year = $ve['cfpd01_formulacion']['ano_formular'];
						}
 				        $year = $this->ano_ejecucion();
				        $this->Session->write('ano_recurso_asignacion', $year);
				        $this->set('year', $year!=null ? $year : date('Y')); */
	}else if($var1==2){
                       /* $consolidacion= !isset($this->data['datos']['consolidacion'])?2:$this->data['datos']['consolidacion'];
                       if(isset($this->data['datos']['ano'])){           $year          = $this->data["datos"]["ano"];}

                        if(isset($consolidacion) && $this->verifica_SS(5)==1){
								        if($consolidacion==1){
								           $cod_dep="";
								           $cod_dep2="";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror="";
								           $this->set("opcion",1);
								        }else{
								           $cod_dep="a.cod_dep,";
								           $cod_dep2="x.cod_dep=a.cod_dep and";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror=",a.cod_dep";
								           $this->set("opcion",2);
								        }
								    }else{
								        $cod_dep="a.cod_dep,";
								           $cod_dep2="x.cod_dep=a.cod_dep and";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror=",a.cod_dep";
								           $this->set("opcion",2);
								    } */

								     $cod_dep="a.cod_dep,";
								           $cod_dep2="x.cod_dep=a.cod_dep and";
								           $con=$this->SQLCA_consolidado_opcion(1, "a");
								           $gror=",a.cod_dep";

								        $datos=$this->cfpd05->execute("SELECT
																						a.cod_presi,
																						a.cod_entidad,
																						a.cod_tipo_inst,
																						a.cod_inst,
																						".$cod_dep."
																								(SELECT COUNT(x.radio_tenencia_const) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia_const=1 ) as propiedad,     (SELECT SUM(x.construccion_valor_total)  FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia_const=1 ) as monto_propiedad,
																								(SELECT COUNT(x.radio_tenencia_const) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia_const=2 ) as arrendamiento, (SELECT SUM(x.construccion_valor_total)  FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia_const=2 ) as monto_arrendamiento,
																								(SELECT COUNT(x.radio_tenencia_const) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia_const=3 ) as comodato, 	 (SELECT SUM(x.construccion_valor_total)  FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia_const=3 ) as monto_comodato,
																								(SELECT COUNT(x.radio_tenencia_const) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia_const=4 ) as anticresis,	 (SELECT SUM(x.construccion_valor_total)  FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia_const=4 ) as monto_anticresis,
																								(SELECT COUNT(x.radio_tenencia_const) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia_const=5 ) as enfiteusis,    (SELECT SUM(x.construccion_valor_total)  FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia_const=5 ) as monto_enfiteusis,
																								(SELECT COUNT(x.radio_tenencia_const) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia_const=6 ) as esufructo,	 (SELECT SUM(x.construccion_valor_total)  FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia_const=6 ) as monto_esufructo,
																								(SELECT COUNT(x.radio_tenencia_const) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia_const=7 ) as derecho_de_uso, (SELECT SUM(x.construccion_valor_total)  FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia_const=7 ) as monto_derecho_de_uso,
																								(SELECT COUNT(x.radio_tenencia_const) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia_const=8 ) as derecho_de_habitacion, (SELECT SUM(x.construccion_valor_total)  FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia_const=8 ) as monto_derecho_de_habitacion,
																								(SELECT COUNT(x.radio_tenencia_const) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia_const=9 ) as otros,				 (SELECT SUM(x.construccion_valor_total)  FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.radio_tenencia_const=9 ) as monto_otros,
																								(SELECT COUNT(x.radio_tenencia_const) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." 1=1) as total,										 (SELECT SUM(x.construccion_valor_total)  FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." 1=1) as monto_total
																						  FROM
																						catd02_ficha_datos a
																						WHERE
																						   	".$con."
																						group by
																								a.cod_presi,
																								a.cod_entidad,
																								a.cod_tipo_inst,
																								a.cod_inst
																						".$gror."
																						order by
																								a.cod_presi,
																								a.cod_entidad,
																								a.cod_tipo_inst,
																								a.cod_inst
																						".$gror.";");

                         foreach($datos as $row){
						 	$propiedad = $row[0]['propiedad'];
						 	$arrendamiento = $row[0]['arrendamiento'];
						 	$comodato = $row[0]['comodato'];
						 	$anticresis = $row[0]['anticresis'];
						 	$enfiteusis = $row[0]['enfiteusis'];
						 	$esufructo = $row[0]['esufructo'];
						 	$derecho_de_uso = $row[0]['derecho_de_uso'];
						 	$derecho_de_habitacion = $row[0]['derecho_de_habitacion '];
						 	$otros = $row[0]['otros'];
						 	$total = $row[0]['total'];
						 	$monto_propiedad = $row[0]['monto_propiedad'];
						 	$monto_arrendamiento = $row[0]['monto_arrendamiento'];
						 	$monto_comodato = $row[0]['monto_comodato'];
						 	$monto_anticresis = $row[0]['monto_anticresis'];
						    $monto_enfiteusis = $row[0]['monto_enfiteusis'];
						 	$monto_usufructo = $row[0]['monto_usufructo'];
						 	$monto_derecho_uso = $row[0]['monto_derecho_uso'];
						 	$monto_derecho_hab = $row[0]['monto_derecho_hab'];
						 	$monto_otros = $row[0]['monto_otros'];
						 	$monto_total = $row[0]['monto_total'];
						 }

				 $parametros["titulo"][]  = "TENENCIA DE LA CONSTRUCCIÓN";
				// $parametros["titulo"][]  = " AÑO: ".$year;
				 $parametros["tipo_cantidad"] = true;
                 $variables["nombre_total"] = "Total";
                 $variables["nombre"][] = "Propiedad";
                 $variables["nombre"][] = "Arrendamiento";
                 $variables["nombre"][] = "Comodato";
                 $variables["nombre"][] = "Anticresis";
                 $variables["nombre"][] = "Enfiteusis";
                 $variables["nombre"][] = "Esufructo";
                 $variables["nombre"][] = "Derecho de Uso";
                 $variables["nombre"][] = "Derecho de Habitación ";
                 $variables["nombre"][] = "Otros";

                 if(isset($datos[0][0]['total'])){
		                 $variables["porcentaje_total"] = ($total * 100) / $total;
		                 $variables["porcentaje"][] = ($propiedad * 100) / $total;
		                 $variables["porcentaje"][] = ($arrendamiento * 100) / $total;
		                 $variables["porcentaje"][] = ($comodato * 100) / $total;
		                 $variables["porcentaje"][] = ($anticresis * 100) / $total;
		                 $variables["porcentaje"][] = ($enfiteusis * 100) / $total;
		                 $variables["porcentaje"][] = ($esufructo * 100) / $total;
		                 $variables["porcentaje"][] = ($derecho_de_uso * 100) / $total;
		                 $variables["porcentaje"][] = ($derecho_de_habitacion * 100) / $total;
		                 $variables["porcentaje"][] = ($otros * 100) / $total;
		                 $variables["cantidad_total"] = $total;
		                 $variables["cantidad"][] = $propiedad;
		                 $variables["cantidad"][] = $arrendamiento;
		                 $variables["cantidad"][] = $comodato;
		                 $variables["cantidad"][] = $anticresis;
		                 $variables["cantidad"][] = $enfiteusis;
		                 $variables["cantidad"][] = $esufructo;
		                 $variables["cantidad"][] = $derecho_de_uso;
		                 $variables["cantidad"][] = $derecho_de_habitacion;
		                 $variables["cantidad"][] = $otros;
		                 $variables["monto_total"]  = $monto_total;
		                 $variables["monto"][] = $monto_propiedad;
		                 $variables["monto"][] = $monto_arrendamiento;
		                 $variables["monto"][] = $monto_comodato;
		                 $variables["monto"][] = $monto_anticresis;
		                 $variables["monto"][] = $monto_enfiteusis;
						 $variables["monto"][] = $monto_usufructo;
		                 $variables["monto"][] = $monto_derecho_uso;
		                 $variables["monto"][] = $monto_derecho_hab;
		                 $variables["monto"][] = $monto_otros;


                 }else{
                 	     $variables["porcentaje_total"] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                  $variables["cantidad_total"] = 0;
		                 $variables["cantidad"][] = 0;
		                 $variables["cantidad"][] = 0;
		                 $variables["cantidad"][] = 0;
		                 $variables["cantidad"][] = 0;
		                 $variables["cantidad"][] = 0;
		                 $variables["cantidad"][] = 0;
		                 $variables["cantidad"][] = 0;
		                 $variables["cantidad"][] = 0;
		                 $variables["cantidad"][] = 0;
		                 $variables["monto_total"]  = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
						 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
                 }//fin else
                 $this->genera_grafica_1(1, $parametros, $variables);

	}//fin else if

$this->set('opcion', $var1);


}//fin function


function grafica_40($var1=null){

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

$this->layout="ajax";

	    if($var1==1){
				      /* for($minCount = 2007; $minCount < 2030; $minCount++) {
					    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
					    $this->set('anos',$anos);
				       }
				       $dato = $this->cfpd01_formulacion->findAll($this->condicionNDEP(), null, 'ano_formular ASC', null);
						foreach($dato as $ve){
							$year = $ve['cfpd01_formulacion']['ano_formular'];
						}
 				        $year = $this->ano_ejecucion();
				        $this->Session->write('ano_recurso_asignacion', $year);
				        $this->set('year', $year!=null ? $year : date('Y'));*/
	}else if($var1==2){
                      /* $consolidacion= !isset($this->data['datos']['consolidacion'])?2:$this->data['datos']['consolidacion'];
                       if(isset($this->data['datos']['ano'])){           $year          = $this->data["datos"]["ano"];}

                        if(isset($consolidacion) && $this->verifica_SS(5)==1){
								        if($consolidacion==1){
								           $cod_dep="";
								           $cod_dep2="";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror="";
								           $this->set("opcion",1);
								        }else{
								           $cod_dep="a.cod_dep,";
								           $cod_dep2="x.cod_dep=a.cod_dep and";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror=",a.cod_dep";
								           $this->set("opcion",2);
								        }
								    }else{
								        $cod_dep="a.cod_dep,";
								           $cod_dep2="x.cod_dep=a.cod_dep and";
								           $con=$this->SQLCA_consolidado_opcion($consolidacion, "a");
								           $gror=",a.cod_dep";
								           $this->set("opcion",2);
								    } */

								     $cod_dep="a.cod_dep,";
								           $cod_dep2="x.cod_dep=a.cod_dep and";
								           $con=$this->SQLCA_consolidado_opcion(1, "a");
								           $gror=",a.cod_dep";

								        $datos=$this->cfpd05->execute("SELECT
																						a.cod_presi,
																						a.cod_entidad,
																						a.cod_tipo_inst,
																						a.cod_inst,
																						".$cod_dep."
																								(SELECT COUNT(x.cod_ficha) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.construccion_area_total=0 ) as sincons,  (SELECT SUM(x.construccion_valor_total) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.construccion_area_total=0 ) as monto_sincons,
																								(SELECT COUNT(x.cod_ficha) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.construccion_area_total!=0 ) as concons, (SELECT SUM(x.construccion_valor_total) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." x.construccion_area_total=0 ) as monto_concons,
																								(SELECT COUNT(x.cod_ficha) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." 1=1) as total,							    (SELECT SUM(x.construccion_valor_total) FROM catd02_ficha_datos x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and ".$cod_dep2." 1=1) as monto_total
																						  FROM
																						catd02_ficha_datos a
																						WHERE
																						   	".$con."
																						group by
																								a.cod_presi,
																								a.cod_entidad,
																								a.cod_tipo_inst,
																								a.cod_inst
																						".$gror."
																						order by
																								a.cod_presi,
																								a.cod_entidad,
																								a.cod_tipo_inst,
																								a.cod_inst
																						".$gror.";");

                         foreach($datos as $row){
						 	$sincons = $row[0]['sincons'];
						 	$concons = $row[0]['concons'];
						 	$total = $row[0]['total'];
						 	$monto_sincons = $row[0]['monto_sincons'];
						 	$monto_concons = $row[0]['monto_concons'];
						 	$monto_total = $row[0]['monto_total'];
						 }

				 $parametros["titulo"][]  = "CONSTRUCCIONES VS POR CONSTRUIR";
				 // $parametros["titulo"][]  = " AÑO: ".$year;
				 $parametros["tipo_cantidad"] = true;
                 $variables["nombre_total"] = "Total";
                 $variables["nombre"][] = "POR CONSTRUIR";
                 $variables["nombre"][] = "CONSTRUCCIONES";

                 if(isset($datos[0][0]['total'])){
		                 $variables["porcentaje_total"] = ($total * 100) / $total;
		                 $variables["porcentaje"][] = ($sincons * 100) / $total;
		                 $variables["porcentaje"][] = ($concons * 100) / $total;
		                 $variables["cantidad_total"] = $total;
		                 $variables["cantidad"][] = $sincons;
		                 $variables["cantidad"][] = $concons;
		                 $variables["monto_total"] = $monto_total;
		                 $variables["monto"][] = $monto_sincons;
		                 $variables["monto"][] = $monto_concons;


                 }else{
                 	     $variables["porcentaje_total"] = 0;
		                 $variables["porcentaje"][] = 0;
		                 $variables["porcentaje"][] = 0;
		                  $variables["cantidad_total"] = 0;
		                 $variables["cantidad"][] = 0;
		                 $variables["cantidad"][] = 0;
		                 $variables["monto_total"]  = 0;
		                 $variables["monto"][] = 0;
		                 $variables["monto"][] = 0;
                 }//fin else
                 $this->genera_grafica_1(1, $parametros, $variables);

	}//fin else if

$this->set('opcion', $var1);


}//fin function


}//fin class
?>