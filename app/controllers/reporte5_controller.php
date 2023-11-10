<?php

class Reporte5Controller extends AppController{


    var $name    = "reporte5";
    var $uses    = array('arrd05','cugd02_dependencia', 'cugd02_institucion', 'cfpd05', 'ccfd04_cierre_mes', 'v_balance_ejecucion', 'cpcd02',
                         'v_impuestos_retenidos');
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





function beforeFilter(){$this->checkSession();}

function verifica_SS($i){
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










function buscar_benefeciario_1($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function



function buscar_benefeciario_2($var1=null, $var2=null, $var3=null){
$this->layout="ajax";

    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					//if(is_int($var2)){$sql   = " ((rif_cedula LIKE '%$var2%') or (razon_social LIKE '%$var2%'))  or   ";}else{ $sql = "";}
					$Tfilas=$this->cpcd02->findCount("((rif LIKE '%$var2%') or (denominacion LIKE '%$var2%'))");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->cpcd02->findAll("((rif LIKE '%$var2%') or (denominacion LIKE '%$var2%'))   ",null,"rif ASC",100,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);
						//if(is_int($var22)){$sql   = " (codigo_prod_serv LIKE '%$var22%')  or   ";}else{ $sql = "";}
						$Tfilas=$this->cpcd02->findCount("((rif LIKE '%$var22%') or (denominacion LIKE '%$var22%'))");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->cpcd02->findAll("((rif LIKE '%$var22%') or (denominacion LIKE '%$var22%'))  ",null,"rif ASC",100,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else
$this->set("opcion",$var1);
}//fin function


function buscar_benefeciario_3($var1=null){
	$this->layout="ajax";
	$this->set("beneficiario", $var1);
}//fin function


function informacion_impuesto_retenido($op=null){
    $cod_presi     = $this->Session->read('SScodpresi');
	$cod_entidad   = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst      = $this->Session->read('SScodinst');
	$cod_dep       = $this->Session->read('SScoddep');
if($op==1){ $this->layout="ajax";

}else{      $this->layout="pdf";

$rif_beneficiario = $this->data["datos"]["rif_beneficiario"];
$fecha_desde      = $this->data["datos"]["fecha_desde"];
$fecha_hasta      = $this->data["datos"]["fecha_hasta"];

$this->set("fecha_desde", $fecha_desde);
$this->set("fecha_hasta", $fecha_hasta);

if(isset($this->data["datos"]["consolidacion"])){
	if($this->data["datos"]["consolidacion"]==1){
       $sql = $this->condicionNDEP();
       $sql.= " and rif='".$rif_beneficiario."' and fecha_documento between '$fecha_desde' and '$fecha_hasta'  ";
	}else{
       $sql = $this->SQLCA_consolidado_($this->data["datos"]["consolidacion"]);
       $sql.= " and rif='".$rif_beneficiario."' and fecha_documento between '$fecha_desde' and '$fecha_hasta'  ";
	}
}else{
	$sql = $this->condicion();
	$sql.= " and rif='".$rif_beneficiario."' and fecha_documento between '$fecha_desde' and '$fecha_hasta'  ";
}

$datos_cpcd02              =  $this->cpcd02->findAll("rif='".$rif_beneficiario."' ");
$datos_filas               = $this->v_impuestos_retenidos->findAll($sql,null,"fecha_documento, tipo_retencion ASC");
$datos_cugd02_dependencias = $this->cugd02_dependencia->findAll("cod_tipo_institucion='".$this->Session->read('SScodtipoinst')."' and cod_institucion='".$this->Session->read('SScodinst')."' and cod_dependencia='".$this->Session->read('SScoddep')."'  ");
$datos_cugd02_institucion  = $this->cugd02_institucion->findAll("cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." ");

$this->set("datos_filas",               $datos_filas);
$this->set("datos_cpcd02",              $datos_cpcd02);
$this->set("datos_cugd02_dependencias", $datos_cugd02_dependencias);
$this->set("datos_cugd02_institucion",  $datos_cugd02_institucion);





}//fin else
$this->set("opcion", $op);
}// fin function


















function session_tipo($var){
$_SESSION['tipo_consolidado'] = $var;
$this->funcion();
$this->render('funcion');
}//fin function



function session_year($var){



 $_SESSION['session_year'] = $var;


$this->funcion();
$this->render('funcion');


}//fin function





function session_tipo_gasto($var){



 $_SESSION['session_tipo_gasto'] = $var;


$this->funcion();
$this->render('funcion');


}//fin function










function funcion(){$this->layout="ajax"; echo "<script>document.getElementById('ir').disabled=false; </script>";}





function year_recurso($var=null, $var2=null){

    $this->layout="ajax";
    $this->set('funcion', $var);
    $this->set('var_inst', $var2);
    $this->set('year', $this->ano_ejecucion());

}//fin funtion







function partida_lista($var1=null, $var2=null){

    $this->layout="ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');


    if($var2==null){$ano = $this->ano_ejecucion();}else{$ano = $var2;}


if($var1==1){

$cond = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

   $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_partida,a.deno_partida FROM v_cfpd05_denominaciones a WHERE  ". $cond." and a.ano=".$ano);
    foreach($rs as $l){
		$v[]=$l[0]["cod_partida"];
		$d[]=$l[0]["deno_partida"];
	}
	if(isset($v)){$PARTIDA = array_combine($v, $d);}else{$PARTIDA = array();}

}else{


$cond = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;

   $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_partida,a.deno_partida FROM v_cfpd05_denominaciones a WHERE ". $cond." and a.ano=".$ano);
    foreach($rs as $l){
		$v[]=$l[0]["cod_partida"];
		$d[]=$l[0]["deno_partida"];
	}
	if(isset($v)){$PARTIDA = array_combine($v, $d);}else{$PARTIDA = array();}

}//fin

$this->concatena($PARTIDA, 'lista_numero');

}//fin funtion





function sector_lista($var1=null, $var2=null){

    $this->layout="ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');


    if($var2==null){$ano = $this->ano_ejecucion();}else{$ano = $var2;}


if($var1==1){

$cond = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

   $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_sector,a.deno_sector FROM v_balance_ejecucion a WHERE  ". $cond." and a.ano=".$ano);
    foreach($rs as $l){
		$v[]=$l[0]["cod_sector"];
		$d[]=$l[0]["deno_sector"];
	}

	if(isset($v)){$SECTOR = array_combine($v, $d);}else{$SECTOR = array();}


}else{


$cond = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;

   $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_sector,a.deno_sector FROM v_balance_ejecucion a WHERE ". $cond." and a.ano=".$ano);
    foreach($rs as $l){
		$v[]=$l[0]["cod_sector"];
		$d[]=$l[0]["deno_sector"];
	}
	if(isset($v)){$SECTOR = array_combine($v, $d);}else{$SECTOR = array();}

}//fin

$this->concatena($SECTOR, 'lista_numero');

}//fin funtion

















function tipo_sector($var1=null, $var2=null, $var3=null){




	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $ano = $this->ano_ejecucion();
    $rdm = mt_rand();

      $SScoddeporig             =       $this->Session->read('SScoddeporig');
	  $SScoddep                 =       $this->Session->read('SScoddep');
	  $Modulo                   =       $this->Session->read('Modulo');
	  $cond3                    =       "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst;
	  $lista = "";

      $vara = 0;
      $varb = 0;

      $cod_sector = "";


	if(isset($this->data["reporte3"]["consolidacion"])){if($this->data["reporte3"]["consolidacion"]==2){$Modulo="2";} $consolidado = $this->data["reporte3"]["consolidacion"];  }else{$consolidado=1;}
	if(isset($this->data["reporte3"]["year"])){$ano=$this->data["reporte3"]["year"];}
	if(isset($this->data["graficos1"]["year"])){$ano=$this->data["graficos1"]["year"];}




	if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
	 	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." and a.ano=".$ano." ";
	    $this->set('global', 'si');
	}else{
		$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." and a.cod_dep = ".$this->cod_dep_consolidado()."  and a.ano=".$ano." ";
	    $this->set('global', 'no');
	}//fin else

	if(isset($this->data["reporte3"]["cod_sector"])){$cod_sector=$this->data["reporte3"]["cod_sector"];}else{

          $rs=$this->v_balance_ejecucion->execute("SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a. cod_dep, a.ano, a.cod_sector FROM cfpd02_sector a WHERE ". $cond." order by a.cod_sector asc; ");
         foreach($rs as $vee){if($cod_sector==""){ $cod_sector=$vee[0]['cod_sector'];}}


		}//fin if



   $this->set('consolidado', $consolidado);
   $this->set('cod_sector', $cod_sector);

          if($var1==null ){$var1=1;}

        $this->set('opcion', $var1);





         if($var1==1){
				   $this->layout="ajax";


				   if($var1==1){
						//$cond = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
						   $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_sector,a.deno_sector FROM v_balance_ejecucion a WHERE ". $cond);
						    foreach($rs as $l){
								$v[]=$l[0]["cod_sector"];
								$d[]=$l[0]["deno_sector"];
							}
							$SECTOR = array_combine($v, $d);
						}else{
						 //  $cond = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
						   $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_sector,a.deno_sector FROM v_balance_ejecucion a WHERE ". $cond);
						    foreach($rs as $l){
								$v[]=$l[0]["cod_sector"];
								$d[]=$l[0]["deno_sector"];
							}
							$SECTOR = array_combine($v, $d);
						}//fin
						$this->concatena($SECTOR, 'lista_numero');


		    				$datos = $this->cfpd05->execute("SELECT

		    						  SUM(a.asignacion_anual)           as asignacion_anual,
									  SUM(a.aumento_traslado_anual)     as aumento_traslado_anual,
									  SUM(a.disminucion_traslado_anual) as disminucion_traslado_anual,
									  SUM(a.credito_adicional_anual)    as credito_adicional_anual,
									  SUM(a.rebaja_anual)               as rebaja_anual,
									  SUM(a.compromiso_anual)           as compromiso_anual,
									  SUM(a.causado_anual)              as causado_anual,
									  SUM(a.pagado_anual)               as pagado_anual

						 		      FROM cfpd05 a where ".$cond." and  a.ano  =  ".$ano." and cod_sector = ".$cod_sector."

		                         GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano");


                    if(!isset($datos[0][0]['asignacion_anual'])){
		                             $datos[0][0]['asignacion_anual'] = 0;
		                             $datos[0][0]['aumento_traslado_anual'] = 0;
		                             $datos[0][0]['credito_adicional_anual'] = 0;
		                             $datos[0][0]['disminucion_traslado_anual'] = 0;
		                             $datos[0][0]['rebaja_anual'] = 0;
		                             $datos[0][0]['compromiso_anual'] = 0;
		                             $datos[0][0]['causado_anual'] = 0;
		                             $datos[0][0]['pagado_anual'] = 0;
                    }//fin if


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



					if($asignacion_ajustada==0){
						$asignacion_anual_por           = 0;
						$aumento_traslado_anual_por     = 0;
						$credito_adicional_anual_por    = 0;
						$disminucion_traslado_anual_por = 0;
						$rebaja_anual_por               = 0;
						$compromiso_anual_por           = 0;
						$causado_anual_por              = 0;
						$pagado_anual_por               = 0;
						$asignacion_ajustada_por        = 0;
						$deuda_por                      = 0;
						$disponibilidad_por             = 0;
					       }else{
						$asignacion_anual_por           = ($asignacion_anual * 100) / $asignacion_ajustada;
						$aumento_traslado_anual_por     = ($aumento_traslado_anual* 100) / $asignacion_ajustada;
						$credito_adicional_anual_por    = ($credito_adicional_anual* 100) / $asignacion_ajustada;
						$disminucion_traslado_anual_por = ($disminucion_traslado_anual* 100) / $asignacion_ajustada;
						$rebaja_anual_por               = ($rebaja_anual* 100) / $asignacion_ajustada;
						$compromiso_anual_por           = ($compromiso_anual* 100) / $asignacion_ajustada;
						$causado_anual_por              = ($causado_anual* 100) / $asignacion_ajustada;
						$pagado_anual_por               = ($pagado_anual * 100) / $asignacion_ajustada;
						$asignacion_ajustada_por        = ($asignacion_ajustada * 100) / $asignacion_ajustada;
						$deuda_por                      = ($deuda* 100) / $asignacion_ajustada;
						$disponibilidad_por             = ($disponibilidad * 100) / $asignacion_ajustada;
					}//fin else



                   $this->set('asignacion_anual',           $asignacion_anual);
                   $this->set('aumento_traslado_anual',     $aumento_traslado_anual);
                   $this->set('credito_adicional_anual',    $credito_adicional_anual);
                   $this->set('disminucion_traslado_anual', $disminucion_traslado_anual);
                   $this->set('rebaja_anual',               $rebaja_anual);
                   $this->set('compromiso_anual',           $compromiso_anual);
                   $this->set('causado_anual',              $causado_anual);
                   $this->set('pagado_anual',               $pagado_anual);
                   $this->set('asignacion_ajustada',        $asignacion_ajustada);
                   $this->set('deuda',                      $deuda);
                   $this->set('disponibilidad',             $disponibilidad);


                   $this->set('asignacion_anual_por',           $asignacion_anual_por);
                   $this->set('aumento_traslado_anual_por',     $aumento_traslado_anual_por);
                   $this->set('credito_adicional_anual_por',    $credito_adicional_anual_por);
                   $this->set('disminucion_traslado_anual_por', $disminucion_traslado_anual_por);
                   $this->set('rebaja_anual_por',               $rebaja_anual_por);
                   $this->set('compromiso_anual_por',           $compromiso_anual_por);
                   $this->set('causado_anual_por',              $causado_anual_por);
                   $this->set('pagado_anual_por',               $pagado_anual_por);
                   $this->set('asignacion_ajustada_por',        $asignacion_ajustada_por);
                   $this->set('deuda_por',                      $deuda_por);
                   $this->set('disponibilidad_por',             $disponibilidad_por);



         }else  if($var1==2){
				   $this->layout="ajax";
		    				$datos = $this->cfpd05->execute("SELECT

		    						  SUM(a.asignacion_anual)           as asignacion_anual,
									  SUM(a.aumento_traslado_anual)     as aumento_traslado_anual,
									  SUM(a.disminucion_traslado_anual) as disminucion_traslado_anual,
									  SUM(a.credito_adicional_anual)    as credito_adicional_anual,
									  SUM(a.rebaja_anual)               as rebaja_anual,
									  SUM(a.compromiso_anual)           as compromiso_anual,
									  SUM(a.causado_anual)              as causado_anual,
									  SUM(a.pagado_anual)               as pagado_anual

						 		      FROM cfpd05 a where ".$cond." and  a.ano  =  ".$ano." and cod_sector = ".$cod_sector."

		                         GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano");


                    if(!isset($datos[0][0]['asignacion_anual'])){
		                             $datos[0][0]['asignacion_anual'] = 0;
		                             $datos[0][0]['aumento_traslado_anual'] = 0;
		                             $datos[0][0]['credito_adicional_anual'] = 0;
		                             $datos[0][0]['disminucion_traslado_anual'] = 0;
		                             $datos[0][0]['rebaja_anual'] = 0;
		                             $datos[0][0]['compromiso_anual'] = 0;
		                             $datos[0][0]['causado_anual'] = 0;
		                             $datos[0][0]['pagado_anual'] = 0;
                    }//fin if


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



					if($asignacion_ajustada==0){
						$asignacion_anual_por           = 0;
						$aumento_traslado_anual_por     = 0;
						$credito_adicional_anual_por    = 0;
						$disminucion_traslado_anual_por = 0;
						$rebaja_anual_por               = 0;
						$compromiso_anual_por           = 0;
						$causado_anual_por              = 0;
						$pagado_anual_por               = 0;
						$asignacion_ajustada_por        = 0;
						$deuda_por                      = 0;
						$disponibilidad_por             = 0;
					       }else{
						$asignacion_anual_por           = ($asignacion_anual * 100) / $asignacion_ajustada;
						$aumento_traslado_anual_por     = ($aumento_traslado_anual* 100) / $asignacion_ajustada;
						$credito_adicional_anual_por    = ($credito_adicional_anual* 100) / $asignacion_ajustada;
						$disminucion_traslado_anual_por = ($disminucion_traslado_anual* 100) / $asignacion_ajustada;
						$rebaja_anual_por               = ($rebaja_anual* 100) / $asignacion_ajustada;
						$compromiso_anual_por           = ($compromiso_anual* 100) / $asignacion_ajustada;
						$causado_anual_por              = ($causado_anual* 100) / $asignacion_ajustada;
						$pagado_anual_por               = ($pagado_anual * 100) / $asignacion_ajustada;
						$asignacion_ajustada_por        = ($asignacion_ajustada * 100) / $asignacion_ajustada;
						$deuda_por                      = ($deuda* 100) / $asignacion_ajustada;
						$disponibilidad_por             = ($disponibilidad * 100) / $asignacion_ajustada;
					}//fin else



                   $this->set('asignacion_anual',           $asignacion_anual);
                   $this->set('aumento_traslado_anual',     $aumento_traslado_anual);
                   $this->set('credito_adicional_anual',    $credito_adicional_anual);
                   $this->set('disminucion_traslado_anual', $disminucion_traslado_anual);
                   $this->set('rebaja_anual',               $rebaja_anual);
                   $this->set('compromiso_anual',           $compromiso_anual);
                   $this->set('causado_anual',              $causado_anual);
                   $this->set('pagado_anual',               $pagado_anual);
                   $this->set('asignacion_ajustada',        $asignacion_ajustada);
                   $this->set('deuda',                      $deuda);
                   $this->set('disponibilidad',             $disponibilidad);


                   $this->set('asignacion_anual_por',           $asignacion_anual_por);
                   $this->set('aumento_traslado_anual_por',     $aumento_traslado_anual_por);
                   $this->set('credito_adicional_anual_por',    $credito_adicional_anual_por);
                   $this->set('disminucion_traslado_anual_por', $disminucion_traslado_anual_por);
                   $this->set('rebaja_anual_por',               $rebaja_anual_por);
                   $this->set('compromiso_anual_por',           $compromiso_anual_por);
                   $this->set('causado_anual_por',              $causado_anual_por);
                   $this->set('pagado_anual_por',               $pagado_anual_por);
                   $this->set('asignacion_ajustada_por',        $asignacion_ajustada_por);
                   $this->set('deuda_por',                      $deuda_por);
                   $this->set('disponibilidad_por',             $disponibilidad_por);



	}else{



                     $this->layout="pdf";
                     $rdm                       =   $this->data["graficos1"]["rdm"];
		         	 $year                      =   $this->data["graficos1"]["year"];
		         	 $ano = $year;


		    				$datos = $this->cfpd05->execute("SELECT

		    						  SUM(a.asignacion_anual)           as asignacion_anual,
									  SUM(a.aumento_traslado_anual)     as aumento_traslado_anual,
									  SUM(a.disminucion_traslado_anual) as disminucion_traslado_anual,
									  SUM(a.credito_adicional_anual)    as credito_adicional_anual,
									  SUM(a.rebaja_anual)               as rebaja_anual,
									  SUM(a.compromiso_anual)           as compromiso_anual,
									  SUM(a.causado_anual)              as causado_anual,
									  SUM(a.pagado_anual)               as pagado_anual

						 		      FROM cfpd05 a where ".$cond." and  a.ano  =  ".$ano." and cod_sector = ".$cod_sector."

		                         GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano");


		             $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_sector,a.deno_sector FROM v_balance_ejecucion a WHERE  ". $cond." and cod_sector=".$cod_sector." and a.ano=".$ano);

                     $this->set('cod_sector',             $rs[0][0]['cod_sector']);
                     $this->set('deno_sector',            $rs[0][0]['deno_sector']);

                    if(!isset($datos[0][0]['asignacion_anual'])){
		                             $datos[0][0]['asignacion_anual'] = 0;
		                             $datos[0][0]['aumento_traslado_anual'] = 0;
		                             $datos[0][0]['credito_adicional_anual'] = 0;
		                             $datos[0][0]['disminucion_traslado_anual'] = 0;
		                             $datos[0][0]['rebaja_anual'] = 0;
		                             $datos[0][0]['compromiso_anual'] = 0;
		                             $datos[0][0]['causado_anual'] = 0;
		                             $datos[0][0]['pagado_anual'] = 0;
                    }//fin if


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



					if($asignacion_ajustada==0){
						$asignacion_anual_por           = 0;
						$aumento_traslado_anual_por     = 0;
						$credito_adicional_anual_por    = 0;
						$disminucion_traslado_anual_por = 0;
						$rebaja_anual_por               = 0;
						$compromiso_anual_por           = 0;
						$causado_anual_por              = 0;
						$pagado_anual_por               = 0;
						$asignacion_ajustada_por        = 0;
						$deuda_por                      = 0;
						$disponibilidad_por             = 0;
					       }else{
						$asignacion_anual_por           = ($asignacion_anual * 100) / $asignacion_ajustada;
						$aumento_traslado_anual_por     = ($aumento_traslado_anual* 100) / $asignacion_ajustada;
						$credito_adicional_anual_por    = ($credito_adicional_anual* 100) / $asignacion_ajustada;
						$disminucion_traslado_anual_por = ($disminucion_traslado_anual* 100) / $asignacion_ajustada;
						$rebaja_anual_por               = ($rebaja_anual* 100) / $asignacion_ajustada;
						$compromiso_anual_por           = ($compromiso_anual* 100) / $asignacion_ajustada;
						$causado_anual_por              = ($causado_anual* 100) / $asignacion_ajustada;
						$pagado_anual_por               = ($pagado_anual * 100) / $asignacion_ajustada;
						$asignacion_ajustada_por        = ($asignacion_ajustada * 100) / $asignacion_ajustada;
						$deuda_por                      = ($deuda* 100) / $asignacion_ajustada;
						$disponibilidad_por             = ($disponibilidad * 100) / $asignacion_ajustada;
					}//fin else



                   $this->set('asignacion_anual',           $asignacion_anual);
                   $this->set('aumento_traslado_anual',     $aumento_traslado_anual);
                   $this->set('credito_adicional_anual',    $credito_adicional_anual);
                   $this->set('disminucion_traslado_anual', $disminucion_traslado_anual);
                   $this->set('rebaja_anual',               $rebaja_anual);
                   $this->set('compromiso_anual',           $compromiso_anual);
                   $this->set('causado_anual',              $causado_anual);
                   $this->set('pagado_anual',               $pagado_anual);
                   $this->set('asignacion_ajustada',        $asignacion_ajustada);
                   $this->set('deuda',                      $deuda);
                   $this->set('disponibilidad',             $disponibilidad);


                   $this->set('asignacion_anual_por',           $asignacion_anual_por);
                   $this->set('aumento_traslado_anual_por',     $aumento_traslado_anual_por);
                   $this->set('credito_adicional_anual_por',    $credito_adicional_anual_por);
                   $this->set('disminucion_traslado_anual_por', $disminucion_traslado_anual_por);
                   $this->set('rebaja_anual_por',               $rebaja_anual_por);
                   $this->set('compromiso_anual_por',           $compromiso_anual_por);
                   $this->set('causado_anual_por',              $causado_anual_por);
                   $this->set('pagado_anual_por',               $pagado_anual_por);
                   $this->set('asignacion_ajustada_por',        $asignacion_ajustada_por);
                   $this->set('deuda_por',                      $deuda_por);
                   $this->set('disponibilidad_por',             $disponibilidad_por);


         }//fin else

$this->set('titulo_inst', $this->Session->read('entidad_federal'));
$this->set('titulo_a',$this->Session->read('dependencia'));
$username = $this->Session->read('nom_usuario');
$this->set('user', $username);
$this->set('username', $username);
$this->set('year', $ano);
$this->set('rdm', $rdm);




}//fin function













function tipo_partida($var1=null, $var2=null, $var3=null){


	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $ano = $this->ano_ejecucion();
    $rdm = mt_rand();

      $SScoddeporig             =       $this->Session->read('SScoddeporig');
	  $SScoddep                 =       $this->Session->read('SScoddep');
	  $Modulo                   =       $this->Session->read('Modulo');
	  $cond3                    =       "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst;
	  $lista = "";

      $vara = 0;
      $varb = 0;
      $cod_partida = "";


	if(isset($this->data["reporte3"]["consolidacion"])){if($this->data["reporte3"]["consolidacion"]==2){$Modulo="2";} $consolidado = $this->data["reporte3"]["consolidacion"];  }else{$consolidado=1;}
	if(isset($this->data["reporte3"]["year"])){$ano=$this->data["reporte3"]["year"];}
	if(isset($this->data["graficos1"]["year"])){$ano=$this->data["graficos1"]["year"];}








	if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
	 	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." and a.ano=".$ano." ";
	    $this->set('global', 'si');
	}else{
		$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." and a.cod_dep = ".$this->cod_dep_consolidado()."  and a.ano=".$ano." ";
	    $this->set('global', 'no');
	}//fin else

	if(isset($this->data["reporte3"]["cod_partida"])){$cod_partida=$this->data["reporte3"]["cod_partida"];}else{

          $rs=$this->v_balance_ejecucion->execute("SELECT a.cod_grupo, a.cod_partida FROM cfpd01_partida a WHERE cod_grupo=4 order by a.cod_partida asc; ");
         foreach($rs as $vee){if($cod_partida==""){ $cod_partida='4'.$this->AddCeroR2($vee[0]['cod_partida']);}}


		}//fin if



   $this->set('consolidado', $consolidado);
   $this->set('cod_partida', $cod_partida);


          if($var1==null ){$var1=1;}

        $this->set('opcion', $var1);





         if($var1==1){
				   $this->layout="ajax";


				   if($var1==1){
						//$cond = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
						   $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_partida,a.deno_partida FROM v_cfpd05_denominaciones a WHERE ". $cond);
						    foreach($rs as $l){
								$v[]=$l[0]["cod_partida"];
								$d[]=$l[0]["deno_partida"];
							}
							$partida = array_combine($v, $d);
						}else{
						 //  $cond = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
						   $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_partida,a.deno_partida FROM v_cfpd05_denominaciones a WHERE ". $cond);
						    foreach($rs as $l){
								$v[]=$l[0]["cod_partida"];
								$d[]=$l[0]["deno_partida"];
							}
							$partida = array_combine($v, $d);
						}//fin
						$this->concatena($partida, 'lista_numero');


		    				$datos = $this->cfpd05->execute("SELECT

		    						  SUM(a.asignacion_anual)           as asignacion_anual,
									  SUM(a.aumento_traslado_anual)     as aumento_traslado_anual,
									  SUM(a.disminucion_traslado_anual) as disminucion_traslado_anual,
									  SUM(a.credito_adicional_anual)    as credito_adicional_anual,
									  SUM(a.rebaja_anual)               as rebaja_anual,
									  SUM(a.compromiso_anual)           as compromiso_anual,
									  SUM(a.causado_anual)              as causado_anual,
									  SUM(a.pagado_anual)               as pagado_anual

						 		      FROM cfpd05 a where ".$cond." and  a.ano  =  ".$ano." and cod_partida = ".$cod_partida."

		                         GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano");


                    if(!isset($datos[0][0]['asignacion_anual'])){
		                             $datos[0][0]['asignacion_anual'] = 0;
		                             $datos[0][0]['aumento_traslado_anual'] = 0;
		                             $datos[0][0]['credito_adicional_anual'] = 0;
		                             $datos[0][0]['disminucion_traslado_anual'] = 0;
		                             $datos[0][0]['rebaja_anual'] = 0;
		                             $datos[0][0]['compromiso_anual'] = 0;
		                             $datos[0][0]['causado_anual'] = 0;
		                             $datos[0][0]['pagado_anual'] = 0;
                    }//fin if


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



					if($asignacion_ajustada==0){
						$asignacion_anual_por           = 0;
						$aumento_traslado_anual_por     = 0;
						$credito_adicional_anual_por    = 0;
						$disminucion_traslado_anual_por = 0;
						$rebaja_anual_por               = 0;
						$compromiso_anual_por           = 0;
						$causado_anual_por              = 0;
						$pagado_anual_por               = 0;
						$asignacion_ajustada_por        = 0;
						$deuda_por                      = 0;
						$disponibilidad_por             = 0;
					       }else{
						$asignacion_anual_por           = ($asignacion_anual * 100) / $asignacion_ajustada;
						$aumento_traslado_anual_por     = ($aumento_traslado_anual* 100) / $asignacion_ajustada;
						$credito_adicional_anual_por    = ($credito_adicional_anual* 100) / $asignacion_ajustada;
						$disminucion_traslado_anual_por = ($disminucion_traslado_anual* 100) / $asignacion_ajustada;
						$rebaja_anual_por               = ($rebaja_anual* 100) / $asignacion_ajustada;
						$compromiso_anual_por           = ($compromiso_anual* 100) / $asignacion_ajustada;
						$causado_anual_por              = ($causado_anual* 100) / $asignacion_ajustada;
						$pagado_anual_por               = ($pagado_anual * 100) / $asignacion_ajustada;
						$asignacion_ajustada_por        = ($asignacion_ajustada * 100) / $asignacion_ajustada;
						$deuda_por                      = ($deuda* 100) / $asignacion_ajustada;
						$disponibilidad_por             = ($disponibilidad * 100) / $asignacion_ajustada;
					}//fin else



                   $this->set('asignacion_anual',           $asignacion_anual);
                   $this->set('aumento_traslado_anual',     $aumento_traslado_anual);
                   $this->set('credito_adicional_anual',    $credito_adicional_anual);
                   $this->set('disminucion_traslado_anual', $disminucion_traslado_anual);
                   $this->set('rebaja_anual',               $rebaja_anual);
                   $this->set('compromiso_anual',           $compromiso_anual);
                   $this->set('causado_anual',              $causado_anual);
                   $this->set('pagado_anual',               $pagado_anual);
                   $this->set('asignacion_ajustada',        $asignacion_ajustada);
                   $this->set('deuda',                      $deuda);
                   $this->set('disponibilidad',             $disponibilidad);


                   $this->set('asignacion_anual_por',           $asignacion_anual_por);
                   $this->set('aumento_traslado_anual_por',     $aumento_traslado_anual_por);
                   $this->set('credito_adicional_anual_por',    $credito_adicional_anual_por);
                   $this->set('disminucion_traslado_anual_por', $disminucion_traslado_anual_por);
                   $this->set('rebaja_anual_por',               $rebaja_anual_por);
                   $this->set('compromiso_anual_por',           $compromiso_anual_por);
                   $this->set('causado_anual_por',              $causado_anual_por);
                   $this->set('pagado_anual_por',               $pagado_anual_por);
                   $this->set('asignacion_ajustada_por',        $asignacion_ajustada_por);
                   $this->set('deuda_por',                      $deuda_por);
                   $this->set('disponibilidad_por',             $disponibilidad_por);



         }else  if($var1==2){
				   $this->layout="ajax";
		    				$datos = $this->cfpd05->execute("SELECT

		    						  SUM(a.asignacion_anual)           as asignacion_anual,
									  SUM(a.aumento_traslado_anual)     as aumento_traslado_anual,
									  SUM(a.disminucion_traslado_anual) as disminucion_traslado_anual,
									  SUM(a.credito_adicional_anual)    as credito_adicional_anual,
									  SUM(a.rebaja_anual)               as rebaja_anual,
									  SUM(a.compromiso_anual)           as compromiso_anual,
									  SUM(a.causado_anual)              as causado_anual,
									  SUM(a.pagado_anual)               as pagado_anual

						 		      FROM cfpd05 a where ".$cond." and  a.ano  =  ".$ano." and cod_partida = ".$cod_partida."

		                         GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano");


                    if(!isset($datos[0][0]['asignacion_anual'])){
		                             $datos[0][0]['asignacion_anual'] = 0;
		                             $datos[0][0]['aumento_traslado_anual'] = 0;
		                             $datos[0][0]['credito_adicional_anual'] = 0;
		                             $datos[0][0]['disminucion_traslado_anual'] = 0;
		                             $datos[0][0]['rebaja_anual'] = 0;
		                             $datos[0][0]['compromiso_anual'] = 0;
		                             $datos[0][0]['causado_anual'] = 0;
		                             $datos[0][0]['pagado_anual'] = 0;
                    }//fin if


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



					if($asignacion_ajustada==0){
						$asignacion_anual_por           = 0;
						$aumento_traslado_anual_por     = 0;
						$credito_adicional_anual_por    = 0;
						$disminucion_traslado_anual_por = 0;
						$rebaja_anual_por               = 0;
						$compromiso_anual_por           = 0;
						$causado_anual_por              = 0;
						$pagado_anual_por               = 0;
						$asignacion_ajustada_por        = 0;
						$deuda_por                      = 0;
						$disponibilidad_por             = 0;
					       }else{
						$asignacion_anual_por           = ($asignacion_anual * 100) / $asignacion_ajustada;
						$aumento_traslado_anual_por     = ($aumento_traslado_anual* 100) / $asignacion_ajustada;
						$credito_adicional_anual_por    = ($credito_adicional_anual* 100) / $asignacion_ajustada;
						$disminucion_traslado_anual_por = ($disminucion_traslado_anual* 100) / $asignacion_ajustada;
						$rebaja_anual_por               = ($rebaja_anual* 100) / $asignacion_ajustada;
						$compromiso_anual_por           = ($compromiso_anual* 100) / $asignacion_ajustada;
						$causado_anual_por              = ($causado_anual* 100) / $asignacion_ajustada;
						$pagado_anual_por               = ($pagado_anual * 100) / $asignacion_ajustada;
						$asignacion_ajustada_por        = ($asignacion_ajustada * 100) / $asignacion_ajustada;
						$deuda_por                      = ($deuda* 100) / $asignacion_ajustada;
						$disponibilidad_por             = ($disponibilidad * 100) / $asignacion_ajustada;
					}//fin else



                   $this->set('asignacion_anual',           $asignacion_anual);
                   $this->set('aumento_traslado_anual',     $aumento_traslado_anual);
                   $this->set('credito_adicional_anual',    $credito_adicional_anual);
                   $this->set('disminucion_traslado_anual', $disminucion_traslado_anual);
                   $this->set('rebaja_anual',               $rebaja_anual);
                   $this->set('compromiso_anual',           $compromiso_anual);
                   $this->set('causado_anual',              $causado_anual);
                   $this->set('pagado_anual',               $pagado_anual);
                   $this->set('asignacion_ajustada',        $asignacion_ajustada);
                   $this->set('deuda',                      $deuda);
                   $this->set('disponibilidad',             $disponibilidad);


                   $this->set('asignacion_anual_por',           $asignacion_anual_por);
                   $this->set('aumento_traslado_anual_por',     $aumento_traslado_anual_por);
                   $this->set('credito_adicional_anual_por',    $credito_adicional_anual_por);
                   $this->set('disminucion_traslado_anual_por', $disminucion_traslado_anual_por);
                   $this->set('rebaja_anual_por',               $rebaja_anual_por);
                   $this->set('compromiso_anual_por',           $compromiso_anual_por);
                   $this->set('causado_anual_por',              $causado_anual_por);
                   $this->set('pagado_anual_por',               $pagado_anual_por);
                   $this->set('asignacion_ajustada_por',        $asignacion_ajustada_por);
                   $this->set('deuda_por',                      $deuda_por);
                   $this->set('disponibilidad_por',             $disponibilidad_por);



	}else{



                     $this->layout="pdf";
                     $rdm                       =   $this->data["graficos1"]["rdm"];
		         	 $year                      =   $this->data["graficos1"]["year"];
		         	 $ano = $year;


		    				$datos = $this->cfpd05->execute("SELECT

		    						  SUM(a.asignacion_anual)           as asignacion_anual,
									  SUM(a.aumento_traslado_anual)     as aumento_traslado_anual,
									  SUM(a.disminucion_traslado_anual) as disminucion_traslado_anual,
									  SUM(a.credito_adicional_anual)    as credito_adicional_anual,
									  SUM(a.rebaja_anual)               as rebaja_anual,
									  SUM(a.compromiso_anual)           as compromiso_anual,
									  SUM(a.causado_anual)              as causado_anual,
									  SUM(a.pagado_anual)               as pagado_anual

						 		      FROM cfpd05 a where ".$cond." and  a.ano  =  ".$ano." and cod_partida = ".$cod_partida."

		                         GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano");


		             $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_partida,a.deno_partida FROM v_cfpd05_denominaciones a WHERE  ". $cond." and cod_partida=".$cod_partida." and a.ano=".$ano);

                     $this->set('cod_partida',             $rs[0][0]['cod_partida']);
                     $this->set('deno_partida',            $rs[0][0]['deno_partida']);

                    if(!isset($datos[0][0]['asignacion_anual'])){
		                             $datos[0][0]['asignacion_anual'] = 0;
		                             $datos[0][0]['aumento_traslado_anual'] = 0;
		                             $datos[0][0]['credito_adicional_anual'] = 0;
		                             $datos[0][0]['disminucion_traslado_anual'] = 0;
		                             $datos[0][0]['rebaja_anual'] = 0;
		                             $datos[0][0]['compromiso_anual'] = 0;
		                             $datos[0][0]['causado_anual'] = 0;
		                             $datos[0][0]['pagado_anual'] = 0;
                    }//fin if


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



					if($asignacion_ajustada==0){
						$asignacion_anual_por           = 0;
						$aumento_traslado_anual_por     = 0;
						$credito_adicional_anual_por    = 0;
						$disminucion_traslado_anual_por = 0;
						$rebaja_anual_por               = 0;
						$compromiso_anual_por           = 0;
						$causado_anual_por              = 0;
						$pagado_anual_por               = 0;
						$asignacion_ajustada_por        = 0;
						$deuda_por                      = 0;
						$disponibilidad_por             = 0;
					       }else{
						$asignacion_anual_por           = ($asignacion_anual * 100) / $asignacion_ajustada;
						$aumento_traslado_anual_por     = ($aumento_traslado_anual* 100) / $asignacion_ajustada;
						$credito_adicional_anual_por    = ($credito_adicional_anual* 100) / $asignacion_ajustada;
						$disminucion_traslado_anual_por = ($disminucion_traslado_anual* 100) / $asignacion_ajustada;
						$rebaja_anual_por               = ($rebaja_anual* 100) / $asignacion_ajustada;
						$compromiso_anual_por           = ($compromiso_anual* 100) / $asignacion_ajustada;
						$causado_anual_por              = ($causado_anual* 100) / $asignacion_ajustada;
						$pagado_anual_por               = ($pagado_anual * 100) / $asignacion_ajustada;
						$asignacion_ajustada_por        = ($asignacion_ajustada * 100) / $asignacion_ajustada;
						$deuda_por                      = ($deuda* 100) / $asignacion_ajustada;
						$disponibilidad_por             = ($disponibilidad * 100) / $asignacion_ajustada;
					}//fin else



                   $this->set('asignacion_anual',           $asignacion_anual);
                   $this->set('aumento_traslado_anual',     $aumento_traslado_anual);
                   $this->set('credito_adicional_anual',    $credito_adicional_anual);
                   $this->set('disminucion_traslado_anual', $disminucion_traslado_anual);
                   $this->set('rebaja_anual',               $rebaja_anual);
                   $this->set('compromiso_anual',           $compromiso_anual);
                   $this->set('causado_anual',              $causado_anual);
                   $this->set('pagado_anual',               $pagado_anual);
                   $this->set('asignacion_ajustada',        $asignacion_ajustada);
                   $this->set('deuda',                      $deuda);
                   $this->set('disponibilidad',             $disponibilidad);


                   $this->set('asignacion_anual_por',           $asignacion_anual_por);
                   $this->set('aumento_traslado_anual_por',     $aumento_traslado_anual_por);
                   $this->set('credito_adicional_anual_por',    $credito_adicional_anual_por);
                   $this->set('disminucion_traslado_anual_por', $disminucion_traslado_anual_por);
                   $this->set('rebaja_anual_por',               $rebaja_anual_por);
                   $this->set('compromiso_anual_por',           $compromiso_anual_por);
                   $this->set('causado_anual_por',              $causado_anual_por);
                   $this->set('pagado_anual_por',               $pagado_anual_por);
                   $this->set('asignacion_ajustada_por',        $asignacion_ajustada_por);
                   $this->set('deuda_por',                      $deuda_por);
                   $this->set('disponibilidad_por',             $disponibilidad_por);


         }//fin else

$this->set('titulo_inst', $this->Session->read('entidad_federal'));
$this->set('titulo_a',$this->Session->read('dependencia'));
$username = $this->Session->read('nom_usuario');
$this->set('user', $username);
$this->set('username', $username);
$this->set('year', $ano);
$this->set('rdm', $rdm);





}//fin function










function grafico_de_cosumos($var1=null, $var2=null, $var3=null){

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $ano = $this->ano_ejecucion();
    $rdm = mt_rand();

      $SScoddeporig             =       $this->Session->read('SScoddeporig');
	  $SScoddep                 =       $this->Session->read('SScoddep');
	  $Modulo                   =       $this->Session->read('Modulo');
	  $cond3                    =       "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst;
	  $lista = "";

      $vara = 0;
      $varb = 0;



	if(isset($this->data["reporte3"]["consolidacion"])){if($this->data["reporte3"]["consolidacion"]==2){$Modulo="2";} $consolidado = $this->data["reporte3"]["consolidacion"];  }else{$consolidado=1;}
	if(isset($this->data["reporte3"]["year"])){$ano=$this->data["reporte3"]["year"];}
	if(isset($this->data["reporte3"]["tipo_gasto"])){$tipo_gasto=$this->data["reporte3"]["tipo_gasto"];}else{$tipo_gasto=1;}


   $this->set('consolidado', $consolidado);
   $this->set('tipo_gasto', $tipo_gasto);

	if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
	 	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." ";
	    $this->set('global', 'si');
	}else{
		$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." and a.cod_dep = ".$this->cod_dep_consolidado()." ";
	    $this->set('global', 'no');
	}//fin else


          if($var1==null ){$var1=1;}

        $this->set('opcion', $var1);





         if($var1==1){
				   $this->layout="ajax";


		    				$datos = $this->cfpd05->execute("SELECT

		    						  SUM(a.asignacion_anual)           as asignacion_anual,
									  SUM(a.aumento_traslado_anual)     as aumento_traslado_anual,
									  SUM(a.disminucion_traslado_anual) as disminucion_traslado_anual,
									  SUM(a.credito_adicional_anual)    as credito_adicional_anual,
									  SUM(a.rebaja_anual)               as rebaja_anual,
									  SUM(a.compromiso_anual)           as compromiso_anual,
									  SUM(a.causado_anual)              as causado_anual,
									  SUM(a.pagado_anual)               as pagado_anual

						 		      FROM cfpd05 a where ".$cond." and  a.ano  =  ".$ano."

		                         GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano");


                    if(!isset($datos[0][0]['asignacion_anual'])){
		                             $datos[0][0]['asignacion_anual'] = 0;
		                             $datos[0][0]['aumento_traslado_anual'] = 0;
		                             $datos[0][0]['credito_adicional_anual'] = 0;
		                             $datos[0][0]['disminucion_traslado_anual'] = 0;
		                             $datos[0][0]['rebaja_anual'] = 0;
		                             $datos[0][0]['compromiso_anual'] = 0;
		                             $datos[0][0]['causado_anual'] = 0;
		                             $datos[0][0]['pagado_anual'] = 0;
                    }//fin if


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



					if($asignacion_ajustada==0){
						$asignacion_anual_por           = 0;
						$aumento_traslado_anual_por     = 0;
						$credito_adicional_anual_por    = 0;
						$disminucion_traslado_anual_por = 0;
						$rebaja_anual_por               = 0;
						$compromiso_anual_por           = 0;
						$causado_anual_por              = 0;
						$pagado_anual_por               = 0;
						$asignacion_ajustada_por        = 0;
						$deuda_por                      = 0;
						$disponibilidad_por             = 0;
					       }else{
						$asignacion_anual_por           = ($asignacion_anual * 100) / $asignacion_ajustada;
						$aumento_traslado_anual_por     = ($aumento_traslado_anual* 100) / $asignacion_ajustada;
						$credito_adicional_anual_por    = ($credito_adicional_anual* 100) / $asignacion_ajustada;
						$disminucion_traslado_anual_por = ($disminucion_traslado_anual* 100) / $asignacion_ajustada;
						$rebaja_anual_por               = ($rebaja_anual* 100) / $asignacion_ajustada;
						$compromiso_anual_por           = ($compromiso_anual* 100) / $asignacion_ajustada;
						$causado_anual_por              = ($causado_anual* 100) / $asignacion_ajustada;
						$pagado_anual_por               = ($pagado_anual * 100) / $asignacion_ajustada;
						$asignacion_ajustada_por        = ($asignacion_ajustada * 100) / $asignacion_ajustada;
						$deuda_por                      = ($deuda* 100) / $asignacion_ajustada;
						$disponibilidad_por             = ($disponibilidad * 100) / $asignacion_ajustada;
					}//fin else



                   $this->set('asignacion_anual',           $asignacion_anual);
                   $this->set('aumento_traslado_anual',     $aumento_traslado_anual);
                   $this->set('credito_adicional_anual',    $credito_adicional_anual);
                   $this->set('disminucion_traslado_anual', $disminucion_traslado_anual);
                   $this->set('rebaja_anual',               $rebaja_anual);
                   $this->set('compromiso_anual',           $compromiso_anual);
                   $this->set('causado_anual',              $causado_anual);
                   $this->set('pagado_anual',               $pagado_anual);
                   $this->set('asignacion_ajustada',        $asignacion_ajustada);
                   $this->set('deuda',                      $deuda);
                   $this->set('disponibilidad',             $disponibilidad);


                   $this->set('asignacion_anual_por',           $asignacion_anual_por);
                   $this->set('aumento_traslado_anual_por',     $aumento_traslado_anual_por);
                   $this->set('credito_adicional_anual_por',    $credito_adicional_anual_por);
                   $this->set('disminucion_traslado_anual_por', $disminucion_traslado_anual_por);
                   $this->set('rebaja_anual_por',               $rebaja_anual_por);
                   $this->set('compromiso_anual_por',           $compromiso_anual_por);
                   $this->set('causado_anual_por',              $causado_anual_por);
                   $this->set('pagado_anual_por',               $pagado_anual_por);
                   $this->set('asignacion_ajustada_por',        $asignacion_ajustada_por);
                   $this->set('deuda_por',                      $deuda_por);
                   $this->set('disponibilidad_por',             $disponibilidad_por);



         }else  if($var1==2){
				   $this->layout="ajax";
		    				$datos = $this->cfpd05->execute("SELECT

		    						  SUM(a.asignacion_anual)           as asignacion_anual,
									  SUM(a.aumento_traslado_anual)     as aumento_traslado_anual,
									  SUM(a.disminucion_traslado_anual) as disminucion_traslado_anual,
									  SUM(a.credito_adicional_anual)    as credito_adicional_anual,
									  SUM(a.rebaja_anual)               as rebaja_anual,
									  SUM(a.compromiso_anual)           as compromiso_anual,
									  SUM(a.causado_anual)              as causado_anual,
									  SUM(a.pagado_anual)               as pagado_anual

						 		      FROM cfpd05 a where ".$cond." and  a.ano  =  ".$ano."

		                         GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano");


                    if(!isset($datos[0][0]['asignacion_anual'])){
		                             $datos[0][0]['asignacion_anual'] = 0;
		                             $datos[0][0]['aumento_traslado_anual'] = 0;
		                             $datos[0][0]['credito_adicional_anual'] = 0;
		                             $datos[0][0]['disminucion_traslado_anual'] = 0;
		                             $datos[0][0]['rebaja_anual'] = 0;
		                             $datos[0][0]['compromiso_anual'] = 0;
		                             $datos[0][0]['causado_anual'] = 0;
		                             $datos[0][0]['pagado_anual'] = 0;
                    }//fin if


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



					if($asignacion_ajustada==0){
						$asignacion_anual_por           = 0;
						$aumento_traslado_anual_por     = 0;
						$credito_adicional_anual_por    = 0;
						$disminucion_traslado_anual_por = 0;
						$rebaja_anual_por               = 0;
						$compromiso_anual_por           = 0;
						$causado_anual_por              = 0;
						$pagado_anual_por               = 0;
						$asignacion_ajustada_por        = 0;
						$deuda_por                      = 0;
						$disponibilidad_por             = 0;
					       }else{
						$asignacion_anual_por           = ($asignacion_anual * 100) / $asignacion_ajustada;
						$aumento_traslado_anual_por     = ($aumento_traslado_anual* 100) / $asignacion_ajustada;
						$credito_adicional_anual_por    = ($credito_adicional_anual* 100) / $asignacion_ajustada;
						$disminucion_traslado_anual_por = ($disminucion_traslado_anual* 100) / $asignacion_ajustada;
						$rebaja_anual_por               = ($rebaja_anual* 100) / $asignacion_ajustada;
						$compromiso_anual_por           = ($compromiso_anual* 100) / $asignacion_ajustada;
						$causado_anual_por              = ($causado_anual* 100) / $asignacion_ajustada;
						$pagado_anual_por               = ($pagado_anual * 100) / $asignacion_ajustada;
						$asignacion_ajustada_por        = ($asignacion_ajustada * 100) / $asignacion_ajustada;
						$deuda_por                      = ($deuda* 100) / $asignacion_ajustada;
						$disponibilidad_por             = ($disponibilidad * 100) / $asignacion_ajustada;
					}//fin else



                   $this->set('asignacion_anual',           $asignacion_anual);
                   $this->set('aumento_traslado_anual',     $aumento_traslado_anual);
                   $this->set('credito_adicional_anual',    $credito_adicional_anual);
                   $this->set('disminucion_traslado_anual', $disminucion_traslado_anual);
                   $this->set('rebaja_anual',               $rebaja_anual);
                   $this->set('compromiso_anual',           $compromiso_anual);
                   $this->set('causado_anual',              $causado_anual);
                   $this->set('pagado_anual',               $pagado_anual);
                   $this->set('asignacion_ajustada',        $asignacion_ajustada);
                   $this->set('deuda',                      $deuda);
                   $this->set('disponibilidad',             $disponibilidad);


                   $this->set('asignacion_anual_por',           $asignacion_anual_por);
                   $this->set('aumento_traslado_anual_por',     $aumento_traslado_anual_por);
                   $this->set('credito_adicional_anual_por',    $credito_adicional_anual_por);
                   $this->set('disminucion_traslado_anual_por', $disminucion_traslado_anual_por);
                   $this->set('rebaja_anual_por',               $rebaja_anual_por);
                   $this->set('compromiso_anual_por',           $compromiso_anual_por);
                   $this->set('causado_anual_por',              $causado_anual_por);
                   $this->set('pagado_anual_por',               $pagado_anual_por);
                   $this->set('asignacion_ajustada_por',        $asignacion_ajustada_por);
                   $this->set('deuda_por',                      $deuda_por);
                   $this->set('disponibilidad_por',             $disponibilidad_por);



	}else{



                     $this->layout="pdf";
                     $rdm                       =   $this->data["graficos1"]["rdm"];
		         	 $year                      =   $this->data["graficos1"]["year"];
		         	 $ano = $year;


		    				$datos = $this->cfpd05->execute("SELECT

		    						  SUM(a.asignacion_anual)           as asignacion_anual,
									  SUM(a.aumento_traslado_anual)     as aumento_traslado_anual,
									  SUM(a.disminucion_traslado_anual) as disminucion_traslado_anual,
									  SUM(a.credito_adicional_anual)    as credito_adicional_anual,
									  SUM(a.rebaja_anual)               as rebaja_anual,
									  SUM(a.compromiso_anual)           as compromiso_anual,
									  SUM(a.causado_anual)              as causado_anual,
									  SUM(a.pagado_anual)               as pagado_anual

						 		      FROM cfpd05 a where ".$cond." and  a.ano  =  ".$ano."

		                         GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano");


                    if(!isset($datos[0][0]['asignacion_anual'])){
		                             $datos[0][0]['asignacion_anual'] = 0;
		                             $datos[0][0]['aumento_traslado_anual'] = 0;
		                             $datos[0][0]['credito_adicional_anual'] = 0;
		                             $datos[0][0]['disminucion_traslado_anual'] = 0;
		                             $datos[0][0]['rebaja_anual'] = 0;
		                             $datos[0][0]['compromiso_anual'] = 0;
		                             $datos[0][0]['causado_anual'] = 0;
		                             $datos[0][0]['pagado_anual'] = 0;
                    }//fin if


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



					if($asignacion_ajustada==0){
						$asignacion_anual_por           = 0;
						$aumento_traslado_anual_por     = 0;
						$credito_adicional_anual_por    = 0;
						$disminucion_traslado_anual_por = 0;
						$rebaja_anual_por               = 0;
						$compromiso_anual_por           = 0;
						$causado_anual_por              = 0;
						$pagado_anual_por               = 0;
						$asignacion_ajustada_por        = 0;
						$deuda_por                      = 0;
						$disponibilidad_por             = 0;
					       }else{
						$asignacion_anual_por           = ($asignacion_anual * 100) / $asignacion_ajustada;
						$aumento_traslado_anual_por     = ($aumento_traslado_anual* 100) / $asignacion_ajustada;
						$credito_adicional_anual_por    = ($credito_adicional_anual* 100) / $asignacion_ajustada;
						$disminucion_traslado_anual_por = ($disminucion_traslado_anual* 100) / $asignacion_ajustada;
						$rebaja_anual_por               = ($rebaja_anual* 100) / $asignacion_ajustada;
						$compromiso_anual_por           = ($compromiso_anual* 100) / $asignacion_ajustada;
						$causado_anual_por              = ($causado_anual* 100) / $asignacion_ajustada;
						$pagado_anual_por               = ($pagado_anual * 100) / $asignacion_ajustada;
						$asignacion_ajustada_por        = ($asignacion_ajustada * 100) / $asignacion_ajustada;
						$deuda_por                      = ($deuda* 100) / $asignacion_ajustada;
						$disponibilidad_por             = ($disponibilidad * 100) / $asignacion_ajustada;
					}//fin else



                   $this->set('asignacion_anual',           $asignacion_anual);
                   $this->set('aumento_traslado_anual',     $aumento_traslado_anual);
                   $this->set('credito_adicional_anual',    $credito_adicional_anual);
                   $this->set('disminucion_traslado_anual', $disminucion_traslado_anual);
                   $this->set('rebaja_anual',               $rebaja_anual);
                   $this->set('compromiso_anual',           $compromiso_anual);
                   $this->set('causado_anual',              $causado_anual);
                   $this->set('pagado_anual',               $pagado_anual);
                   $this->set('asignacion_ajustada',        $asignacion_ajustada);
                   $this->set('deuda',                      $deuda);
                   $this->set('disponibilidad',             $disponibilidad);


                   $this->set('asignacion_anual_por',           $asignacion_anual_por);
                   $this->set('aumento_traslado_anual_por',     $aumento_traslado_anual_por);
                   $this->set('credito_adicional_anual_por',    $credito_adicional_anual_por);
                   $this->set('disminucion_traslado_anual_por', $disminucion_traslado_anual_por);
                   $this->set('rebaja_anual_por',               $rebaja_anual_por);
                   $this->set('compromiso_anual_por',           $compromiso_anual_por);
                   $this->set('causado_anual_por',              $causado_anual_por);
                   $this->set('pagado_anual_por',               $pagado_anual_por);
                   $this->set('asignacion_ajustada_por',        $asignacion_ajustada_por);
                   $this->set('deuda_por',                      $deuda_por);
                   $this->set('disponibilidad_por',             $disponibilidad_por);


         }//fin else

$this->set('titulo_inst', $this->Session->read('entidad_federal'));
$this->set('titulo_a',$this->Session->read('dependencia'));
$username = $this->Session->read('nom_usuario');
$this->set('user', $username);
$this->set('username', $username);
$this->set('year', $ano);
$this->set('rdm', $rdm);





}//fin funcion




































function tipo_gasto($var1=null, $var2=null, $var3=null){



	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $ano = $this->ano_ejecucion();
    $rdm = mt_rand();

      $SScoddeporig             =       $this->Session->read('SScoddeporig');
	  $SScoddep                 =       $this->Session->read('SScoddep');
	  $Modulo                   =       $this->Session->read('Modulo');
	  $cond3                    =       "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst;
	  $lista = "";

      $vara = 0;
      $varb = 0;



	if(isset($this->data["reporte3"]["consolidacion"])){if($this->data["reporte3"]["consolidacion"]==2){$Modulo="2";} $consolidado = $this->data["reporte3"]["consolidacion"];  }else{$consolidado=1;}
	if(isset($this->data["reporte3"]["year"])){$ano=$this->data["reporte3"]["year"];}
	if(isset($this->data["reporte3"]["tipo_gasto"])){$tipo_gasto=$this->data["reporte3"]["tipo_gasto"];}else{$tipo_gasto=3;}


   $this->set('consolidado', $consolidado);
   $this->set('tipo_gasto', $tipo_gasto);


         if($tipo_gasto==1){
         $tipo_gasto = " and cod_tipo_gasto = 2 ";
   }else if($tipo_gasto==3){
         $tipo_gasto = " ";
   }else{
         $tipo_gasto = "  and cod_tipo_gasto != 2 ";
   }//fin if

	if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
	 	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." ";
	    $this->set('global', 'si');
	}else{
		$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." and a.cod_dep = ".$this->cod_dep_consolidado()." ";
	    $this->set('global', 'no');
	}//fin else


          if($var1==null ){$var1=1;}

        $this->set('opcion', $var1);





         if($var1==1){
				   $this->layout="ajax";


		    				$datos = $this->cfpd05->execute("SELECT

		    						  SUM(a.asignacion_anual)           as asignacion_anual,
									  SUM(a.aumento_traslado_anual)     as aumento_traslado_anual,
									  SUM(a.disminucion_traslado_anual) as disminucion_traslado_anual,
									  SUM(a.credito_adicional_anual)    as credito_adicional_anual,
									  SUM(a.rebaja_anual)               as rebaja_anual,
									  SUM(a.compromiso_anual)           as compromiso_anual,
									  SUM(a.causado_anual)              as causado_anual,
									  SUM(a.pagado_anual)               as pagado_anual

						 		      FROM cfpd05 a where ".$cond." and  a.ano  =  ".$ano."  ".$tipo_gasto."

		                         GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano");


                    if(!isset($datos[0][0]['asignacion_anual'])){
		                             $datos[0][0]['asignacion_anual'] = 0;
		                             $datos[0][0]['aumento_traslado_anual'] = 0;
		                             $datos[0][0]['credito_adicional_anual'] = 0;
		                             $datos[0][0]['disminucion_traslado_anual'] = 0;
		                             $datos[0][0]['rebaja_anual'] = 0;
		                             $datos[0][0]['compromiso_anual'] = 0;
		                             $datos[0][0]['causado_anual'] = 0;
		                             $datos[0][0]['pagado_anual'] = 0;
                    }//fin if


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



					if($asignacion_ajustada==0){
						$asignacion_anual_por           = 0;
						$aumento_traslado_anual_por     = 0;
						$credito_adicional_anual_por    = 0;
						$disminucion_traslado_anual_por = 0;
						$rebaja_anual_por               = 0;
						$compromiso_anual_por           = 0;
						$causado_anual_por              = 0;
						$pagado_anual_por               = 0;
						$asignacion_ajustada_por        = 0;
						$deuda_por                      = 0;
						$disponibilidad_por             = 0;
					       }else{
						$asignacion_anual_por           = ($asignacion_anual * 100) / $asignacion_ajustada;
						$aumento_traslado_anual_por     = ($aumento_traslado_anual* 100) / $asignacion_ajustada;
						$credito_adicional_anual_por    = ($credito_adicional_anual* 100) / $asignacion_ajustada;
						$disminucion_traslado_anual_por = ($disminucion_traslado_anual* 100) / $asignacion_ajustada;
						$rebaja_anual_por               = ($rebaja_anual* 100) / $asignacion_ajustada;
						$compromiso_anual_por           = ($compromiso_anual* 100) / $asignacion_ajustada;
						$causado_anual_por              = ($causado_anual* 100) / $asignacion_ajustada;
						$pagado_anual_por               = ($pagado_anual * 100) / $asignacion_ajustada;
						$asignacion_ajustada_por        = ($asignacion_ajustada * 100) / $asignacion_ajustada;
						$deuda_por                      = ($deuda* 100) / $asignacion_ajustada;
						$disponibilidad_por             = ($disponibilidad * 100) / $asignacion_ajustada;
					}//fin else



                   $this->set('asignacion_anual',           $asignacion_anual);
                   $this->set('aumento_traslado_anual',     $aumento_traslado_anual);
                   $this->set('credito_adicional_anual',    $credito_adicional_anual);
                   $this->set('disminucion_traslado_anual', $disminucion_traslado_anual);
                   $this->set('rebaja_anual',               $rebaja_anual);
                   $this->set('compromiso_anual',           $compromiso_anual);
                   $this->set('causado_anual',              $causado_anual);
                   $this->set('pagado_anual',               $pagado_anual);
                   $this->set('asignacion_ajustada',        $asignacion_ajustada);
                   $this->set('deuda',                      $deuda);
                   $this->set('disponibilidad',             $disponibilidad);


                   $this->set('asignacion_anual_por',           $asignacion_anual_por);
                   $this->set('aumento_traslado_anual_por',     $aumento_traslado_anual_por);
                   $this->set('credito_adicional_anual_por',    $credito_adicional_anual_por);
                   $this->set('disminucion_traslado_anual_por', $disminucion_traslado_anual_por);
                   $this->set('rebaja_anual_por',               $rebaja_anual_por);
                   $this->set('compromiso_anual_por',           $compromiso_anual_por);
                   $this->set('causado_anual_por',              $causado_anual_por);
                   $this->set('pagado_anual_por',               $pagado_anual_por);
                   $this->set('asignacion_ajustada_por',        $asignacion_ajustada_por);
                   $this->set('deuda_por',                      $deuda_por);
                   $this->set('disponibilidad_por',             $disponibilidad_por);



         }else  if($var1==2){
				   $this->layout="ajax";
		    				$datos = $this->cfpd05->execute("SELECT

		    						  SUM(a.asignacion_anual)           as asignacion_anual,
									  SUM(a.aumento_traslado_anual)     as aumento_traslado_anual,
									  SUM(a.disminucion_traslado_anual) as disminucion_traslado_anual,
									  SUM(a.credito_adicional_anual)    as credito_adicional_anual,
									  SUM(a.rebaja_anual)               as rebaja_anual,
									  SUM(a.compromiso_anual)           as compromiso_anual,
									  SUM(a.causado_anual)              as causado_anual,
									  SUM(a.pagado_anual)               as pagado_anual

						 		      FROM cfpd05 a where ".$cond." and  a.ano  =  ".$ano."   ".$tipo_gasto."

		                         GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano");


                    if(!isset($datos[0][0]['asignacion_anual'])){
		                             $datos[0][0]['asignacion_anual'] = 0;
		                             $datos[0][0]['aumento_traslado_anual'] = 0;
		                             $datos[0][0]['credito_adicional_anual'] = 0;
		                             $datos[0][0]['disminucion_traslado_anual'] = 0;
		                             $datos[0][0]['rebaja_anual'] = 0;
		                             $datos[0][0]['compromiso_anual'] = 0;
		                             $datos[0][0]['causado_anual'] = 0;
		                             $datos[0][0]['pagado_anual'] = 0;
                    }//fin if


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



					if($asignacion_ajustada==0){
						$asignacion_anual_por           = 0;
						$aumento_traslado_anual_por     = 0;
						$credito_adicional_anual_por    = 0;
						$disminucion_traslado_anual_por = 0;
						$rebaja_anual_por               = 0;
						$compromiso_anual_por           = 0;
						$causado_anual_por              = 0;
						$pagado_anual_por               = 0;
						$asignacion_ajustada_por        = 0;
						$deuda_por                      = 0;
						$disponibilidad_por             = 0;
					       }else{
						$asignacion_anual_por           = ($asignacion_anual * 100) / $asignacion_ajustada;
						$aumento_traslado_anual_por     = ($aumento_traslado_anual* 100) / $asignacion_ajustada;
						$credito_adicional_anual_por    = ($credito_adicional_anual* 100) / $asignacion_ajustada;
						$disminucion_traslado_anual_por = ($disminucion_traslado_anual* 100) / $asignacion_ajustada;
						$rebaja_anual_por               = ($rebaja_anual* 100) / $asignacion_ajustada;
						$compromiso_anual_por           = ($compromiso_anual* 100) / $asignacion_ajustada;
						$causado_anual_por              = ($causado_anual* 100) / $asignacion_ajustada;
						$pagado_anual_por               = ($pagado_anual * 100) / $asignacion_ajustada;
						$asignacion_ajustada_por        = ($asignacion_ajustada * 100) / $asignacion_ajustada;
						$deuda_por                      = ($deuda* 100) / $asignacion_ajustada;
						$disponibilidad_por             = ($disponibilidad * 100) / $asignacion_ajustada;
					}//fin else



                   $this->set('asignacion_anual',           $asignacion_anual);
                   $this->set('aumento_traslado_anual',     $aumento_traslado_anual);
                   $this->set('credito_adicional_anual',    $credito_adicional_anual);
                   $this->set('disminucion_traslado_anual', $disminucion_traslado_anual);
                   $this->set('rebaja_anual',               $rebaja_anual);
                   $this->set('compromiso_anual',           $compromiso_anual);
                   $this->set('causado_anual',              $causado_anual);
                   $this->set('pagado_anual',               $pagado_anual);
                   $this->set('asignacion_ajustada',        $asignacion_ajustada);
                   $this->set('deuda',                      $deuda);
                   $this->set('disponibilidad',             $disponibilidad);


                   $this->set('asignacion_anual_por',           $asignacion_anual_por);
                   $this->set('aumento_traslado_anual_por',     $aumento_traslado_anual_por);
                   $this->set('credito_adicional_anual_por',    $credito_adicional_anual_por);
                   $this->set('disminucion_traslado_anual_por', $disminucion_traslado_anual_por);
                   $this->set('rebaja_anual_por',               $rebaja_anual_por);
                   $this->set('compromiso_anual_por',           $compromiso_anual_por);
                   $this->set('causado_anual_por',              $causado_anual_por);
                   $this->set('pagado_anual_por',               $pagado_anual_por);
                   $this->set('asignacion_ajustada_por',        $asignacion_ajustada_por);
                   $this->set('deuda_por',                      $deuda_por);
                   $this->set('disponibilidad_por',             $disponibilidad_por);



	}else{



                     $this->layout="pdf";
                     $rdm                       =   $this->data["graficos1"]["rdm"];
		         	 $year                      =   $this->data["graficos1"]["year"];
		         	 $ano = $year;


		    				$datos = $this->cfpd05->execute("SELECT

		    						  SUM(a.asignacion_anual)           as asignacion_anual,
									  SUM(a.aumento_traslado_anual)     as aumento_traslado_anual,
									  SUM(a.disminucion_traslado_anual) as disminucion_traslado_anual,
									  SUM(a.credito_adicional_anual)    as credito_adicional_anual,
									  SUM(a.rebaja_anual)               as rebaja_anual,
									  SUM(a.compromiso_anual)           as compromiso_anual,
									  SUM(a.causado_anual)              as causado_anual,
									  SUM(a.pagado_anual)               as pagado_anual

						 		      FROM cfpd05 a where ".$cond." and  a.ano  =  ".$ano."  ".$tipo_gasto."

		                         GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano");


                    if(!isset($datos[0][0]['asignacion_anual'])){
		                             $datos[0][0]['asignacion_anual'] = 0;
		                             $datos[0][0]['aumento_traslado_anual'] = 0;
		                             $datos[0][0]['credito_adicional_anual'] = 0;
		                             $datos[0][0]['disminucion_traslado_anual'] = 0;
		                             $datos[0][0]['rebaja_anual'] = 0;
		                             $datos[0][0]['compromiso_anual'] = 0;
		                             $datos[0][0]['causado_anual'] = 0;
		                             $datos[0][0]['pagado_anual'] = 0;
                    }//fin if


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



					if($asignacion_ajustada==0){
						$asignacion_anual_por           = 0;
						$aumento_traslado_anual_por     = 0;
						$credito_adicional_anual_por    = 0;
						$disminucion_traslado_anual_por = 0;
						$rebaja_anual_por               = 0;
						$compromiso_anual_por           = 0;
						$causado_anual_por              = 0;
						$pagado_anual_por               = 0;
						$asignacion_ajustada_por        = 0;
						$deuda_por                      = 0;
						$disponibilidad_por             = 0;
					       }else{
						$asignacion_anual_por           = ($asignacion_anual * 100) / $asignacion_ajustada;
						$aumento_traslado_anual_por     = ($aumento_traslado_anual* 100) / $asignacion_ajustada;
						$credito_adicional_anual_por    = ($credito_adicional_anual* 100) / $asignacion_ajustada;
						$disminucion_traslado_anual_por = ($disminucion_traslado_anual* 100) / $asignacion_ajustada;
						$rebaja_anual_por               = ($rebaja_anual* 100) / $asignacion_ajustada;
						$compromiso_anual_por           = ($compromiso_anual* 100) / $asignacion_ajustada;
						$causado_anual_por              = ($causado_anual* 100) / $asignacion_ajustada;
						$pagado_anual_por               = ($pagado_anual * 100) / $asignacion_ajustada;
						$asignacion_ajustada_por        = ($asignacion_ajustada * 100) / $asignacion_ajustada;
						$deuda_por                      = ($deuda* 100) / $asignacion_ajustada;
						$disponibilidad_por             = ($disponibilidad * 100) / $asignacion_ajustada;
					}//fin else



                   $this->set('asignacion_anual',           $asignacion_anual);
                   $this->set('aumento_traslado_anual',     $aumento_traslado_anual);
                   $this->set('credito_adicional_anual',    $credito_adicional_anual);
                   $this->set('disminucion_traslado_anual', $disminucion_traslado_anual);
                   $this->set('rebaja_anual',               $rebaja_anual);
                   $this->set('compromiso_anual',           $compromiso_anual);
                   $this->set('causado_anual',              $causado_anual);
                   $this->set('pagado_anual',               $pagado_anual);
                   $this->set('asignacion_ajustada',        $asignacion_ajustada);
                   $this->set('deuda',                      $deuda);
                   $this->set('disponibilidad',             $disponibilidad);


                   $this->set('asignacion_anual_por',           $asignacion_anual_por);
                   $this->set('aumento_traslado_anual_por',     $aumento_traslado_anual_por);
                   $this->set('credito_adicional_anual_por',    $credito_adicional_anual_por);
                   $this->set('disminucion_traslado_anual_por', $disminucion_traslado_anual_por);
                   $this->set('rebaja_anual_por',               $rebaja_anual_por);
                   $this->set('compromiso_anual_por',           $compromiso_anual_por);
                   $this->set('causado_anual_por',              $causado_anual_por);
                   $this->set('pagado_anual_por',               $pagado_anual_por);
                   $this->set('asignacion_ajustada_por',        $asignacion_ajustada_por);
                   $this->set('deuda_por',                      $deuda_por);
                   $this->set('disponibilidad_por',             $disponibilidad_por);


         }//fin else

$this->set('titulo_inst', $this->Session->read('entidad_federal'));
$this->set('titulo_a',$this->Session->read('dependencia'));
$username = $this->Session->read('nom_usuario');
$this->set('user', $username);
$this->set('username', $username);
$this->set('year', $ano);
$this->set('rdm', $rdm);





}//fin funcion






















function tipo_presupuesto($var1=null, $var2=null, $var3=null){



	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
    $ano = $this->ano_ejecucion();
    $rdm = mt_rand();

      $SScoddeporig             =       $this->Session->read('SScoddeporig');
	  $SScoddep                 =       $this->Session->read('SScoddep');
	  $Modulo                   =       $this->Session->read('Modulo');
	  $cond3                    =       "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst;
	  $lista = "";

      $vara = 0;
      $varb = 0;



	if(isset($this->data["reporte3"]["consolidacion"])){if($this->data["reporte3"]["consolidacion"]==2){$Modulo="2";} $consolidado = $this->data["reporte3"]["consolidacion"];  }else{$consolidado=1;}
	if(isset($this->data["reporte3"]["year"])){$ano=$this->data["reporte3"]["year"];}
	if(isset($this->data["reporte3"]["tipo_presupuesto"])){$tipo_presupuesto=$this->data["reporte3"]["tipo_presupuesto"];}else{$tipo_presupuesto=6;}


   $this->set('consolidado', $consolidado);
   $this->set('tipo_presupuesto', $tipo_presupuesto);

	if($SScoddep==1 && $SScoddeporig==1 && $Modulo=="0"){
	 	$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." ";
	    $this->set('global', 'si');
	}else{
		$cond = " a.cod_presi = ".$cod_presi." and a.cod_entidad = ".$cod_entidad." and a.cod_tipo_inst = ".$cod_tipo_inst." and a.cod_inst = ".$cod_inst." and a.cod_dep = ".$this->cod_dep_consolidado()." ";
	    $this->set('global', 'no');
	}//fin else


          if($var1==null ){$var1=1;}

        $this->set('opcion', $var1);





         if($var1==1){
				   $this->layout="ajax";


		    				$datos = $this->cfpd05->execute("SELECT

		    						  SUM(a.asignacion_anual)           as asignacion_anual,
									  SUM(a.aumento_traslado_anual)     as aumento_traslado_anual,
									  SUM(a.disminucion_traslado_anual) as disminucion_traslado_anual,
									  SUM(a.credito_adicional_anual)    as credito_adicional_anual,
									  SUM(a.rebaja_anual)               as rebaja_anual,
									  SUM(a.compromiso_anual)           as compromiso_anual,
									  SUM(a.causado_anual)              as causado_anual,
									  SUM(a.pagado_anual)               as pagado_anual

						 		      FROM cfpd05 a where ".$cond." and  a.ano  =  ".$ano."

		                         GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano");


                    if(!isset($datos[0][0]['asignacion_anual'])){
		                             $datos[0][0]['asignacion_anual'] = 0;
		                             $datos[0][0]['aumento_traslado_anual'] = 0;
		                             $datos[0][0]['credito_adicional_anual'] = 0;
		                             $datos[0][0]['disminucion_traslado_anual'] = 0;
		                             $datos[0][0]['rebaja_anual'] = 0;
		                             $datos[0][0]['compromiso_anual'] = 0;
		                             $datos[0][0]['causado_anual'] = 0;
		                             $datos[0][0]['pagado_anual'] = 0;
                    }//fin if


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



					if($asignacion_ajustada==0){
						$asignacion_anual_por           = 0;
						$aumento_traslado_anual_por     = 0;
						$credito_adicional_anual_por    = 0;
						$disminucion_traslado_anual_por = 0;
						$rebaja_anual_por               = 0;
						$compromiso_anual_por           = 0;
						$causado_anual_por              = 0;
						$pagado_anual_por               = 0;
						$asignacion_ajustada_por        = 0;
						$deuda_por                      = 0;
						$disponibilidad_por             = 0;
					       }else{
						$asignacion_anual_por           = ($asignacion_anual * 100) / $asignacion_ajustada;
						$aumento_traslado_anual_por     = ($aumento_traslado_anual* 100) / $asignacion_ajustada;
						$credito_adicional_anual_por    = ($credito_adicional_anual* 100) / $asignacion_ajustada;
						$disminucion_traslado_anual_por = ($disminucion_traslado_anual* 100) / $asignacion_ajustada;
						$rebaja_anual_por               = ($rebaja_anual* 100) / $asignacion_ajustada;
						$compromiso_anual_por           = ($compromiso_anual* 100) / $asignacion_ajustada;
						$causado_anual_por              = ($causado_anual* 100) / $asignacion_ajustada;
						$pagado_anual_por               = ($pagado_anual * 100) / $asignacion_ajustada;
						$asignacion_ajustada_por        = ($asignacion_ajustada * 100) / $asignacion_ajustada;
						$deuda_por                      = ($deuda* 100) / $asignacion_ajustada;
						$disponibilidad_por             = ($disponibilidad * 100) / $asignacion_ajustada;
					}//fin else



                   $this->set('asignacion_anual',           $asignacion_anual);
                   $this->set('aumento_traslado_anual',     $aumento_traslado_anual);
                   $this->set('credito_adicional_anual',    $credito_adicional_anual);
                   $this->set('disminucion_traslado_anual', $disminucion_traslado_anual);
                   $this->set('rebaja_anual',               $rebaja_anual);
                   $this->set('compromiso_anual',           $compromiso_anual);
                   $this->set('causado_anual',              $causado_anual);
                   $this->set('pagado_anual',               $pagado_anual);
                   $this->set('asignacion_ajustada',        $asignacion_ajustada);
                   $this->set('deuda',                      $deuda);
                   $this->set('disponibilidad',             $disponibilidad);


                   $this->set('asignacion_anual_por',           $asignacion_anual_por);
                   $this->set('aumento_traslado_anual_por',     $aumento_traslado_anual_por);
                   $this->set('credito_adicional_anual_por',    $credito_adicional_anual_por);
                   $this->set('disminucion_traslado_anual_por', $disminucion_traslado_anual_por);
                   $this->set('rebaja_anual_por',               $rebaja_anual_por);
                   $this->set('compromiso_anual_por',           $compromiso_anual_por);
                   $this->set('causado_anual_por',              $causado_anual_por);
                   $this->set('pagado_anual_por',               $pagado_anual_por);
                   $this->set('asignacion_ajustada_por',        $asignacion_ajustada_por);
                   $this->set('deuda_por',                      $deuda_por);
                   $this->set('disponibilidad_por',             $disponibilidad_por);



         }else  if($var1==2){
				   $this->layout="ajax";



				   if($tipo_presupuesto==6){
				   	 $sql_aux = "";
				   }else{
				   	 $sql_aux = " and tipo_presupuesto = ".$tipo_presupuesto;
				   }



		    				$datos = $this->cfpd05->execute("SELECT

		    						  SUM(a.asignacion_anual)           as asignacion_anual,
									  SUM(a.aumento_traslado_anual)     as aumento_traslado_anual,
									  SUM(a.disminucion_traslado_anual) as disminucion_traslado_anual,
									  SUM(a.credito_adicional_anual)    as credito_adicional_anual,
									  SUM(a.rebaja_anual)               as rebaja_anual,
									  SUM(a.compromiso_anual)           as compromiso_anual,
									  SUM(a.causado_anual)              as causado_anual,
									  SUM(a.pagado_anual)               as pagado_anual

						 		      FROM cfpd05 a where ".$cond." and  a.ano  =  ".$ano."  ".$sql_aux."

		                         GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano");


                    if(!isset($datos[0][0]['asignacion_anual'])){
		                             $datos[0][0]['asignacion_anual'] = 0;
		                             $datos[0][0]['aumento_traslado_anual'] = 0;
		                             $datos[0][0]['credito_adicional_anual'] = 0;
		                             $datos[0][0]['disminucion_traslado_anual'] = 0;
		                             $datos[0][0]['rebaja_anual'] = 0;
		                             $datos[0][0]['compromiso_anual'] = 0;
		                             $datos[0][0]['causado_anual'] = 0;
		                             $datos[0][0]['pagado_anual'] = 0;
                    }//fin if


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



					if($asignacion_ajustada==0){
						$asignacion_anual_por           = 0;
						$aumento_traslado_anual_por     = 0;
						$credito_adicional_anual_por    = 0;
						$disminucion_traslado_anual_por = 0;
						$rebaja_anual_por               = 0;
						$compromiso_anual_por           = 0;
						$causado_anual_por              = 0;
						$pagado_anual_por               = 0;
						$asignacion_ajustada_por        = 0;
						$deuda_por                      = 0;
						$disponibilidad_por             = 0;
					       }else{
						$asignacion_anual_por           = ($asignacion_anual * 100) / $asignacion_ajustada;
						$aumento_traslado_anual_por     = ($aumento_traslado_anual* 100) / $asignacion_ajustada;
						$credito_adicional_anual_por    = ($credito_adicional_anual* 100) / $asignacion_ajustada;
						$disminucion_traslado_anual_por = ($disminucion_traslado_anual* 100) / $asignacion_ajustada;
						$rebaja_anual_por               = ($rebaja_anual* 100) / $asignacion_ajustada;
						$compromiso_anual_por           = ($compromiso_anual* 100) / $asignacion_ajustada;
						$causado_anual_por              = ($causado_anual* 100) / $asignacion_ajustada;
						$pagado_anual_por               = ($pagado_anual * 100) / $asignacion_ajustada;
						$asignacion_ajustada_por        = ($asignacion_ajustada * 100) / $asignacion_ajustada;
						$deuda_por                      = ($deuda* 100) / $asignacion_ajustada;
						$disponibilidad_por             = ($disponibilidad * 100) / $asignacion_ajustada;
					}//fin else



                   $this->set('asignacion_anual',           $asignacion_anual);
                   $this->set('aumento_traslado_anual',     $aumento_traslado_anual);
                   $this->set('credito_adicional_anual',    $credito_adicional_anual);
                   $this->set('disminucion_traslado_anual', $disminucion_traslado_anual);
                   $this->set('rebaja_anual',               $rebaja_anual);
                   $this->set('compromiso_anual',           $compromiso_anual);
                   $this->set('causado_anual',              $causado_anual);
                   $this->set('pagado_anual',               $pagado_anual);
                   $this->set('asignacion_ajustada',        $asignacion_ajustada);
                   $this->set('deuda',                      $deuda);
                   $this->set('disponibilidad',             $disponibilidad);


                   $this->set('asignacion_anual_por',           $asignacion_anual_por);
                   $this->set('aumento_traslado_anual_por',     $aumento_traslado_anual_por);
                   $this->set('credito_adicional_anual_por',    $credito_adicional_anual_por);
                   $this->set('disminucion_traslado_anual_por', $disminucion_traslado_anual_por);
                   $this->set('rebaja_anual_por',               $rebaja_anual_por);
                   $this->set('compromiso_anual_por',           $compromiso_anual_por);
                   $this->set('causado_anual_por',              $causado_anual_por);
                   $this->set('pagado_anual_por',               $pagado_anual_por);
                   $this->set('asignacion_ajustada_por',        $asignacion_ajustada_por);
                   $this->set('deuda_por',                      $deuda_por);
                   $this->set('disponibilidad_por',             $disponibilidad_por);



	}else{



                     $this->layout="pdf";
                     $rdm                       =   $this->data["graficos1"]["rdm"];
		         	 $year                      =   $this->data["graficos1"]["year"];
		         	 $ano = $year;

                    if($tipo_presupuesto==6){
				   	 $sql_aux = "";
				    }else{
				   	 $sql_aux = " and tipo_presupuesto = ".$tipo_presupuesto;
				    }



		    				$datos = $this->cfpd05->execute("SELECT

		    						  SUM(a.asignacion_anual)           as asignacion_anual,
									  SUM(a.aumento_traslado_anual)     as aumento_traslado_anual,
									  SUM(a.disminucion_traslado_anual) as disminucion_traslado_anual,
									  SUM(a.credito_adicional_anual)    as credito_adicional_anual,
									  SUM(a.rebaja_anual)               as rebaja_anual,
									  SUM(a.compromiso_anual)           as compromiso_anual,
									  SUM(a.causado_anual)              as causado_anual,
									  SUM(a.pagado_anual)               as pagado_anual

						 		      FROM cfpd05 a where ".$cond." and  a.ano  =  ".$ano." ".$sql_aux."

		                         GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano");


                    if(!isset($datos[0][0]['asignacion_anual'])){
		                             $datos[0][0]['asignacion_anual'] = 0;
		                             $datos[0][0]['aumento_traslado_anual'] = 0;
		                             $datos[0][0]['credito_adicional_anual'] = 0;
		                             $datos[0][0]['disminucion_traslado_anual'] = 0;
		                             $datos[0][0]['rebaja_anual'] = 0;
		                             $datos[0][0]['compromiso_anual'] = 0;
		                             $datos[0][0]['causado_anual'] = 0;
		                             $datos[0][0]['pagado_anual'] = 0;
                    }//fin if


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



					if($asignacion_ajustada==0){
						$asignacion_anual_por           = 0;
						$aumento_traslado_anual_por     = 0;
						$credito_adicional_anual_por    = 0;
						$disminucion_traslado_anual_por = 0;
						$rebaja_anual_por               = 0;
						$compromiso_anual_por           = 0;
						$causado_anual_por              = 0;
						$pagado_anual_por               = 0;
						$asignacion_ajustada_por        = 0;
						$deuda_por                      = 0;
						$disponibilidad_por             = 0;
					       }else{
						$asignacion_anual_por           = ($asignacion_anual * 100) / $asignacion_ajustada;
						$aumento_traslado_anual_por     = ($aumento_traslado_anual* 100) / $asignacion_ajustada;
						$credito_adicional_anual_por    = ($credito_adicional_anual* 100) / $asignacion_ajustada;
						$disminucion_traslado_anual_por = ($disminucion_traslado_anual* 100) / $asignacion_ajustada;
						$rebaja_anual_por               = ($rebaja_anual* 100) / $asignacion_ajustada;
						$compromiso_anual_por           = ($compromiso_anual* 100) / $asignacion_ajustada;
						$causado_anual_por              = ($causado_anual* 100) / $asignacion_ajustada;
						$pagado_anual_por               = ($pagado_anual * 100) / $asignacion_ajustada;
						$asignacion_ajustada_por        = ($asignacion_ajustada * 100) / $asignacion_ajustada;
						$deuda_por                      = ($deuda* 100) / $asignacion_ajustada;
						$disponibilidad_por             = ($disponibilidad * 100) / $asignacion_ajustada;
					}//fin else



                   $this->set('asignacion_anual',           $asignacion_anual);
                   $this->set('aumento_traslado_anual',     $aumento_traslado_anual);
                   $this->set('credito_adicional_anual',    $credito_adicional_anual);
                   $this->set('disminucion_traslado_anual', $disminucion_traslado_anual);
                   $this->set('rebaja_anual',               $rebaja_anual);
                   $this->set('compromiso_anual',           $compromiso_anual);
                   $this->set('causado_anual',              $causado_anual);
                   $this->set('pagado_anual',               $pagado_anual);
                   $this->set('asignacion_ajustada',        $asignacion_ajustada);
                   $this->set('deuda',                      $deuda);
                   $this->set('disponibilidad',             $disponibilidad);


                   $this->set('asignacion_anual_por',           $asignacion_anual_por);
                   $this->set('aumento_traslado_anual_por',     $aumento_traslado_anual_por);
                   $this->set('credito_adicional_anual_por',    $credito_adicional_anual_por);
                   $this->set('disminucion_traslado_anual_por', $disminucion_traslado_anual_por);
                   $this->set('rebaja_anual_por',               $rebaja_anual_por);
                   $this->set('compromiso_anual_por',           $compromiso_anual_por);
                   $this->set('causado_anual_por',              $causado_anual_por);
                   $this->set('pagado_anual_por',               $pagado_anual_por);
                   $this->set('asignacion_ajustada_por',        $asignacion_ajustada_por);
                   $this->set('deuda_por',                      $deuda_por);
                   $this->set('disponibilidad_por',             $disponibilidad_por);


         }//fin else

$this->set('titulo_inst', $this->Session->read('entidad_federal'));
$this->set('titulo_a',$this->Session->read('dependencia'));
$username = $this->Session->read('nom_usuario');
$this->set('user', $username);
$this->set('username', $username);
$this->set('year', $ano);
$this->set('rdm', $rdm);





}//fin funcion











}//fin class


?>