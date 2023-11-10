<?php


class ConsultaGeneralPresupuestoController extends AppController{

    var $uses = array('v_cfpd05_denominaciones','cfpd05','arrd05','ccfd04_cierre_mes','v_consulta_cauxiliar_v2','v_deno_disponibilidad','v_balance_ejecucion','v_balance_ejecucion2');
	var $helpers = array('Html','Ajax','Javascript', 'Sisap', 'Fpdf');


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

    }

function add_c_c($var){
		if($var<=9 && strlen($var)==1){
				$codigo = '0'.$var;
			}else{$codigo = ''.$var;}
		return $codigo;
}//fin AddCero


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
    function AddCero($nomVar,$vector=object,$extra=null){
   	  if($vector!=null){
   	  	  if($extra==null){
   	  	foreach($vector as $x){
        	if($x<10){
        	   $Var[$x]="0".$x;
        	}else{
	           $Var[$x]=$x;
        	}
	    }//fin each
   	  }else{
          foreach($vector as $x){
        	if($x<10){
        	   $Var[$x]=$extra.".0".$x;
        	}else{
	           $Var[$x]=$extra.".".$x;
        	}
	    }//fin each
   	  }
   	  $this->set($nomVar,$Var);
   	  }else{
   	  	  $this->set($nomVar,'');
   	  }



   }//fin AddCero
    function AddCeroR($n,$extra=null){
   	  if($n!=null){
   	  	  if($extra==null){
        	if($n<10){
        	   $Var="0".$n;
        	}else{
	           $Var=$n;
        	}
   	  }else{
        	if($n<10){
        	   $Var=$extra.".0".$n;
        	}else{
	           $Var=$extra.".".$n;
        	}
   	  }
   	return $Var;
   	  }else{
   	  	  //return $Var;
   	  }

    }

function index($var=null) {
	$this->layout="ajax";
	//$this->limpia_menu();
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$ano = $this->ano_ejecucion();
	$this->set('ano', $ano);

}//fin index


function buscar_pista ($pagina=null){
	$this->layout="ajax";
	$cod_presi     = $this->Session->read('SScodpresi');
	$cod_entidad   = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst      = $this->Session->read('SScodinst');
	$cod_dep       = $this->Session->read('SScoddep');

	$sql_cond = " cod_presi in (0,$cod_presi) and cod_entidad in (0,$cod_entidad) and cod_tipo_inst in (0,$cod_tipo_inst) and cod_inst in (0,$cod_inst) and cod_dep in (0,$cod_dep) ";

	if($pagina!=null){
		$pagina=$pagina;
	}else{
		$pagina=1;
	}
	if(isset($this->data["consulta2"]["ano"]) && isset($this->data["consulta2"]["pista"]) && !empty($this->data["consulta2"]["ano"]) && !empty($this->data["consulta2"]["pista"])){
         $this->data["consulta"]["ano"]=$this->data["consulta2"]["ano"];
         $this->data["consulta"]["pista"]=$this->data["consulta2"]["pista"];
         $otra="si";
	}else{
	  $otra="no";
	}
	if((isset($this->data["consulta"]["ano"]) && isset($this->data["consulta"]["pista"]) && !empty($this->data["consulta"]["ano"]) && !empty($this->data["consulta"]["pista"])) || $otra=="si"){
         $ano=$this->data["consulta"]["ano"];
         $pista=strtoupper($this->data["consulta"]["pista"]);
        // echo $pista;
         $cantidad_resultado=$this->v_consulta_cauxiliar_v2->findCount("$sql_cond and ano=".$ano." and ".$this->busca_separado(array("denominacion_busqueda"), $pista)." ");
         $resultado=$this->v_consulta_cauxiliar_v2->findAll("$sql_cond and ano=".$ano." and ".$this->busca_separado(array("denominacion_busqueda"), $pista)." ",null,null,1,$pagina);
         if($cantidad_resultado!=0){
           $this->set("cantidad_resultado",$cantidad_resultado);
           $this->set("resultado",$resultado);
           $this->set("ano",$ano);
           $this->set("pista",$pista);

           $this->set('siguiente',$pagina+1);
           $this->set('anterior',$pagina-1);
		   $this->set('actual',$pagina);
		   $this->bt_nav($cantidad_resultado,$pagina);
         }else{
           $this->set("cantidad_resultado",$cantidad_resultado);
           $this->set("resultado",array(0=>array("v_consulta_cauxiliar_v2"=>array("cod_grupo"=>0,"cod_partida"=>0,"cod_generica"=>0,"cod_especifica"=>0,"cod_sub_espec"=>0,"cod_auxiliar"=>0,"concepto"=>"","denominacion"=>"No se encontraron datos para la pista indicada, ".$pista))));
           $this->set("ano",$ano);
           $this->set("pista",$pista);
           $this->set('siguiente',$pagina+1);
           $this->set('anterior',$pagina-1);
		   $this->set('actual',$pagina);
           $this->bt_nav(1,1);
         }
         $this->set("MUESTRAME","");


	}else{
		if(isset($this->data["consulta"]["ano"]) && !empty($this->data["consulta"]["ano"])){
			echo "<h4>Faltan Datos para las busqueda, por favor indique pista.</h4>";
		}else if(isset($this->data["consulta"]["pista"]) && !empty($this->data["consulta"]["pista"])){
			echo "<h4>Faltan Datos para las busqueda, por favor indique año.</h4>";
		}else{
			echo "<h4>Faltan Datos para las busqueda, por favor indique año y pista.</h4>";
		}
		//echo "<h4>Faltan Datos para las busqueda, por favor indique pista.</h4>";
	}

}//fin buscar_pista

function mostrar_distribucion () {
	$this->layout="ajax";
	if(isset($this->data["consultar2"])){
		$ano=$this->data["consulta2"]["ano"];
		$cod_partida=$this->data["consultar2"]["cod_partida"];
		$cod_generica=$this->data["consultar2"]["cod_generica"];
		$cod_especifica=$this->data["consultar2"]["cod_especifica"];
		$cod_sub_espec=$this->data["consultar2"]["cod_sub_espec"];
		$cod_auxiliar=$this->data["consultar2"]["cod_auxiliar"];
		$condicion2=" and cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar;
        $cantidad_encontrados=$this->v_deno_disponibilidad->findCount($this->SQLCA()." and ano=".$ano.$condicion2);
	    if($cantidad_encontrados!=0){
			$resultado1=$this->v_deno_disponibilidad->findAll($this->SQLCA()." and ano=".$ano.$condicion2,null,"cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida,cod_generica,cod_especifica,cod_auxiliar ASC");
			$this->set("resultado1",$resultado1);
	    }
	}else{
		echo "$nbsp;";
	}
}//fin mostrar_distribucion

function mostrar_distribucion_2 ($ano=null,$cod_sector=null,$cod_programa=null,$cod_sub_prog=null,$cod_proyecto=null,$cod_activ_obra=null,$cod_partida=null,$cod_generica=null,$cod_especifica=null,$cod_sub_espec=null,$cod_auxiliar=null) {
	$this->layout="ajax";
	if($ano!=null && $cod_sector!=null && $cod_programa!=null && $cod_sub_prog!=null && $cod_proyecto!=null && $cod_activ_obra!=null && $cod_partida!=null && $cod_generica!=null && $cod_especifica!=null && $cod_sub_espec!=null && $cod_auxiliar!=null){
		$condicion ="ano=".$ano;
		$condicion .=" and cod_sector=".$cod_sector;
		$condicion .=" and cod_programa=".$cod_programa;
		$condicion .=" and cod_sub_prog=".$cod_sub_prog;
		$condicion .=" and cod_proyecto=".$cod_proyecto;
		$condicion .=" and cod_activ_obra=".$cod_activ_obra;
		$condicion .=" and cod_partida=".$cod_partida;
		$condicion .=" and cod_generica=".$cod_generica;
		$condicion .=" and cod_especifica=".$cod_especifica;
		$condicion .=" and cod_sub_espec=".$cod_sub_espec;
		$condicion .=" and cod_auxiliar=".$cod_auxiliar;
		$resultado=$this->v_balance_ejecucion->findAll($this->SQLCA()." and ".$condicion,array('asignacion_anual','aumento','disminucion','total_asignacion','pre_compromiso','compromiso_anual','causado_anual','pagado_anual','deuda','disponibilidad'));
		//$resultado2=$this->v_balance_ejecucion2->findAll($this->SQLCA()." and ".$condicion,array('asignacion_anual','aumento_ene','aumento_feb','aumento_mar','aumento_abr','aumento_may','aumento_jun','aumento_jul','aumento_ago','aumento_sep','aumento_oct','aumento_nov','aumento_dic','disminucion_ene','disminucion_feb','disminucion_mar','disminucion_abr','disminucion_may','disminucion_jun','disminucion_jul','disminucion_ago','disminucion_sep','disminucion_oct','disminucion_nov','disminucion_dic','compromiso_ene','compromiso_feb','compromiso_mar','compromiso_abr','compromiso_may','compromiso_jun','compromiso_jul','compromiso_ago','compromiso_sep','compromiso_oct','compromiso_nov','compromiso_dic'));
		$resultado2=$this->v_balance_ejecucion2->execute("SELECT
((asignacion_anual/12)+((aumento_ene-disminucion_ene)/12))::numeric(22,2) AS dm_ene,
((asignacion_anual/12)+((aumento_ene-disminucion_ene)/12)+((aumento_feb-disminucion_feb)/11))::numeric(22,2) AS dm_feb,
((asignacion_anual/12)+((aumento_ene-disminucion_ene)/12)+((aumento_feb-disminucion_feb)/11)+((aumento_mar-disminucion_mar)/10))::numeric(22,2) AS dm_mar,
((asignacion_anual/12)+((aumento_ene-disminucion_ene)/12)+((aumento_feb-disminucion_feb)/11)+((aumento_mar-disminucion_mar)/10)+((aumento_abr-disminucion_abr)/9))::numeric(22,2) AS dm_abr,
((asignacion_anual/12)+((aumento_ene-disminucion_ene)/12)+((aumento_feb-disminucion_feb)/11)+((aumento_mar-disminucion_mar)/10)+((aumento_abr-disminucion_abr)/9)+((aumento_may-disminucion_may)/8))::numeric(22,2) AS dm_may,
((asignacion_anual/12)+((aumento_ene-disminucion_ene)/12)+((aumento_feb-disminucion_feb)/11)+((aumento_mar-disminucion_mar)/10)+((aumento_abr-disminucion_abr)/9)+((aumento_may-disminucion_may)/8)+((aumento_jun-disminucion_jun)/7))::numeric(22,2) AS dm_jun,
((asignacion_anual/12)+((aumento_ene-disminucion_ene)/12)+((aumento_feb-disminucion_feb)/11)+((aumento_mar-disminucion_mar)/10)+((aumento_abr-disminucion_abr)/9)+((aumento_may-disminucion_may)/8)+((aumento_jun-disminucion_jun)/7)+((aumento_jul-disminucion_jul)/6))::numeric(22,2) AS dm_jul,
((asignacion_anual/12)+((aumento_ene-disminucion_ene)/12)+((aumento_feb-disminucion_feb)/11)+((aumento_mar-disminucion_mar)/10)+((aumento_abr-disminucion_abr)/9)+((aumento_may-disminucion_may)/8)+((aumento_jun-disminucion_jun)/7)+((aumento_jul-disminucion_jul)/6)+((aumento_ago-disminucion_ago)/5))::numeric(22,2) AS dm_ago,
((asignacion_anual/12)+((aumento_ene-disminucion_ene)/12)+((aumento_feb-disminucion_feb)/11)+((aumento_mar-disminucion_mar)/10)+((aumento_abr-disminucion_abr)/9)+((aumento_may-disminucion_may)/8)+((aumento_jun-disminucion_jun)/7)+((aumento_jul-disminucion_jul)/6)+((aumento_ago-disminucion_ago)/5)+((aumento_sep-disminucion_sep)/4))::numeric(22,2) AS dm_sep,
((asignacion_anual/12)+((aumento_ene-disminucion_ene)/12)+((aumento_feb-disminucion_feb)/11)+((aumento_mar-disminucion_mar)/10)+((aumento_abr-disminucion_abr)/9)+((aumento_may-disminucion_may)/8)+((aumento_jun-disminucion_jun)/7)+((aumento_jul-disminucion_jul)/6)+((aumento_ago-disminucion_ago)/5)+((aumento_sep-disminucion_sep)/4)+((aumento_oct-disminucion_oct)/3))::numeric(22,2) AS dm_oct,
((asignacion_anual/12)+((aumento_ene-disminucion_ene)/12)+((aumento_feb-disminucion_feb)/11)+((aumento_mar-disminucion_mar)/10)+((aumento_abr-disminucion_abr)/9)+((aumento_may-disminucion_may)/8)+((aumento_jun-disminucion_jun)/7)+((aumento_jul-disminucion_jul)/6)+((aumento_ago-disminucion_ago)/5)+((aumento_sep-disminucion_sep)/4)+((aumento_oct-disminucion_oct)/3)+((aumento_nov-disminucion_nov)/2))::numeric(22,2) AS dm_nov,
((asignacion_anual/12)+((aumento_ene-disminucion_ene)/12)+((aumento_feb-disminucion_feb)/11)+((aumento_mar-disminucion_mar)/10)+((aumento_abr-disminucion_abr)/9)+((aumento_may-disminucion_may)/8)+((aumento_jun-disminucion_jun)/7)+((aumento_jul-disminucion_jul)/6)+((aumento_ago-disminucion_ago)/5)+((aumento_sep-disminucion_sep)/4)+((aumento_oct-disminucion_oct)/3)+((aumento_nov-disminucion_nov)/2)+((aumento_dic-disminucion_dic)/1))::numeric(22,2) AS dm_dic,
compromiso_ene,
compromiso_feb,
compromiso_mar,
compromiso_abr,
compromiso_may,
compromiso_jun,
compromiso_jul,
compromiso_ago,
compromiso_sep,
compromiso_oct,
compromiso_nov,
compromiso_dic FROM v_balance_ejecucion2 WHERE ". $this->SQLCA()." and ".$condicion);
        $asignacion_inicial=$resultado[0]["v_balance_ejecucion"]["asignacion_anual"];
        $this->set("resultado_mensual",$resultado2);
        $aumento=$resultado[0]["v_balance_ejecucion"]["aumento"];
        $disminucion=$resultado[0]["v_balance_ejecucion"]["disminucion"];
        $monto_actualizado=$resultado[0]["v_balance_ejecucion"]["total_asignacion"];
        if($asignacion_inicial==0 && ($aumento!=0 || $disminucion!=0)){
        	$porcentaje_modificado=100;
        }else{
            $porcentaje_modificado=(($aumento-$disminucion)/$asignacion_inicial)*100;
            $porcentaje_modificado=$porcentaje_modificado<0?$porcentaje_modificado*(-1):$porcentaje_modificado;
        }
        $porcentaje_modificado=sprintf("%01.2f",$porcentaje_modificado);
        $precompromiso=$resultado[0]["v_balance_ejecucion"]["pre_compromiso"];
        $compromiso=$resultado[0]["v_balance_ejecucion"]["compromiso_anual"];
        $causado=$resultado[0]["v_balance_ejecucion"]["causado_anual"];
        $pagado=$resultado[0]["v_balance_ejecucion"]["pagado_anual"];
        $disponibilidad=$resultado[0]["v_balance_ejecucion"]["disponibilidad"];
        $monto_actualizado2=$monto_actualizado==0?1:$monto_actualizado;
        $porcentaje_ejecutado=($compromiso/$monto_actualizado2)*100;

        $porcentaje_ejecutado=sprintf("%01.2f",$porcentaje_ejecutado);
        $disponibilidad_causar=$compromiso-$causado;
        $disponibilidad_pagar=$causado-$pagado;
        $this->set("asignacion_inicial",$asignacion_inicial);
        $this->set("aumento",$aumento);
        $this->set("disminucion",$disminucion);
        $this->set("monto_actualizado",$monto_actualizado);
        $this->set("porcentaje_modificado",$porcentaje_modificado);
        $this->set("precompromiso",$precompromiso);
        $this->set("compromiso",$compromiso);
        $this->set("causado",$causado);
        $this->set("pagado",$pagado);
        $this->set("disponibilidad",$disponibilidad);
        $this->set("porcentaje_ejecutado",$porcentaje_ejecutado);
        $this->set("disponibilidad_causar",$disponibilidad_causar);
        $this->set("disponibilidad_pagar",$disponibilidad_pagar);

$resultado3=$this->v_balance_ejecucion2->execute("SELECT * FROM v_balance_ejecucion2 WHERE ". $this->SQLCA()." and ".$condicion);
$this->set("RESULTADO3",$resultado3);

$resultados_mov=$this->v_balance_ejecucion2->execute("SELECT * FROM v_cfpd05_movimientos_vision WHERE ". $this->SQLCA()." and ".$condicion);
$this->set("MOV_CFPD",$resultados_mov);


/*
$resultado20=$this->v_balance_ejecucion2->execute("SELECT * FROM cfpd20 WHERE ". $this->SQLCA()." and ".$condicion);
$resultado21=$this->v_balance_ejecucion2->execute("SELECT * FROM cfpd21 WHERE ". $this->SQLCA()." and ".$condicion);
$resultado22=$this->v_balance_ejecucion2->execute("SELECT * FROM cfpd22 WHERE ". $this->SQLCA()." and ".$condicion);
$resultado23=$this->v_balance_ejecucion2->execute("SELECT * FROM cfpd23 WHERE ". $this->SQLCA()." and ".$condicion);
$this->set("CFPD20",$resultado20);
$this->set("CFPD21",$resultado21);
$this->set("CFPD22",$resultado22);
$this->set("CFPD23",$resultado23);
*/

	}//fin if

	    $this->set("cod_sector",$cod_sector);
		$this->set("cod_programa",$cod_programa);
		$this->set("cod_sub_prog",$cod_sub_prog);
		$this->set("cod_proyecto",$cod_proyecto);
		$this->set("cod_activ_obra",$cod_activ_obra);
		$this->set("cod_partida",$cod_partida);
		$this->set("cod_generica",$cod_generica);
		$this->set("cod_especifica",$cod_especifica);
		$this->set("cod_sub_espec",$cod_sub_espec);
		$this->set("cod_auxiliar",$cod_auxiliar);

}//fin funcion


function consulta_disponibilidad_presupuestaria_select () {
	$this->layout="ajax";
	$ano=$this->ano_ejecucion();
	$rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT cod_sector,deno_sector FROM v_balance_ejecucion WHERE ". $this->SQLCA()." and  ano=".$ano." ORDER BY cod_sector ASC");
    foreach($rs as $l){
		$v[]=$l[0]["cod_sector"];
		$d[]=$l[0]["deno_sector"];
	}
	$sector = array_combine($v, $d);
	$this->concatena($sector, 'sector');
	$this->set('ano',$ano);
}//fin consulta_disponibilidad_presupuestaria

function mostrar_cod_deno ($tipo,$ano,$cod_sector=null) {
	$this->layout="ajax";
	if($cod_sector!=''){
		$rs=$this->v_balance_ejecucion->execute("SELECT cod_sector,deno_sector FROM v_balance_ejecucion WHERE ". $this->SQLCA()." and cod_sector=".$cod_sector." and ano=".$ano." limit 1");
		$cod=$rs[0][0]["cod_sector"];
		$deno=$rs[0][0]["deno_sector"];
	    if($tipo=="codigo"){
	    	echo "".$this->AddCeroR($cod);
	    }
	    if($tipo=="deno"){
	    	echo "".$deno;
	    }
	}else{
		echo "";
	}


}//fin consulta_disponibilidad_presupuestaria

function consulta_disponibilidad_presupuestaria ($cod_sector=null) {
	$this->layout="ajax";
	$ano=$this->ano_ejecucion();
	if($cod_sector!=null){
		$resultado=$this->v_balance_ejecucion->execute("SELECT * FROM v_balance_ejecucion WHERE ". $this->SQLCA()." and ano=".$ano."  and cod_sector=".$cod_sector." ORDER BY cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC");
    	$this->set("resultado",$resultado);
	}else{
		$this->set("resultado",null);
	}

}//fin consulta_disponibilidad_presupuestaria



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

function escribir_ano($var){
	$this->layout="ajax";

	if(isset($var) && $var!=null){
	    $this->Session->write('ano_reporte',$var);
	    $ano=$var;
	}else{
	    $this->Session->write('ano_reporte',$this->ano_ejecucion());
	    $ano=$this->ano_ejecucion();

	}
	if($this->verifica_SS(5)==1){
    	    $cond=$this->SQLCA_report(1);
    	}else{
    		$cond=$this->SQLCA_report();
    	}
	$lista=  $this->v_cfpd05_denominaciones->generateList($cond." and ano=".$var, 'cod_sector ASC', null, '{n}.v_cfpd05_denominaciones.cod_sector', '{n}.v_cfpd05_denominaciones.deno_sector');
	$this->concatena($lista, 'sector');
}

function select3($select=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";

if($select!=null && $var!=null){
		if($this->verifica_SS(5)==1){
    	    $cond=$this->SQLCA_report(1);
    	}else{
    		$cond=$this->SQLCA_report();
    	}
	switch($select){
		case 'sector':
			$this->set('SELECT','programa');
			$this->set('codigo','sector');
			$this->set('seleccion','');
			$this->set('n',1);
			$ano=$this->Session->read('ano_reporte');
			$this->Session->write('ano',$ano);
			$cond .=" and ano=".$ano;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_sector ASC', null, '{n}.v_cfpd05_denominaciones.cod_sector', '{n}.v_cfpd05_denominaciones.deno_sector');
			$this->concatena($lista, 'vector');
		break;
		case 'programa':

			$this->set('SELECT','subprograma');
			$this->set('codigo','programa');
			$this->set('seleccion','');
			$this->set('n',2);
			$ano=$this->Session->read('ano_reporte');
			$this->Session->write('ano',$ano);
			$this->Session->write('sec',$var);
			$cond .=" and ano=".$ano." and cod_sector=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_programa ASC', null, '{n}.v_cfpd05_denominaciones.cod_programa', '{n}.v_cfpd05_denominaciones.deno_programa');
					$this->concatena($lista, 'vector');
		break;
		case 'subprograma':
			$this->set('SELECT','proyecto');
			$this->set('codigo','subprograma');
			$this->set('seleccion','');
			$this->set('n',3);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$this->Session->write('prog',$var);
			$cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_sub_prog ASC', null, '{n}.v_cfpd05_denominaciones.cod_sub_prog', '{n}.v_cfpd05_denominaciones.deno_sub_prog');
					$this->concatena($lista, 'vector');
		break;
		case 'proyecto':
			$this->set('SELECT','actividad');
			$this->set('codigo','proyecto');
			$this->set('seleccion','');
			 $this->set('n',4);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$this->Session->write('subp',$var);
			$cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_proyecto ASC', null, '{n}.v_cfpd05_denominaciones.cod_proyecto', '{n}.v_cfpd05_denominaciones.deno_proyecto');
					$this->concatena($lista, 'vector');
		break;
		case 'actividad':
			$this->set('SELECT','partida');
			$this->set('codigo','actividad');
			$this->set('seleccion','');
			$this->set('n',5);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$this->Session->write('proy',$var);
			$cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_activ_obra ASC', null, '{n}.v_cfpd05_denominaciones.cod_activ_obra', '{n}.v_cfpd05_denominaciones.deno_activ_obra');
			$this->concatena($lista, 'vector');
		break;
		case 'partida':
			$this->set('SELECT','generica');
			$this->set('codigo','partida');
			$this->set('seleccion','');
			$this->set('n',6);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy =  $this->Session->read('proy');
			$this->Session->write('actividad',$var);
			$cond2 =$cond." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_partida ASC', null, '{n}.v_cfpd05_denominaciones.cod_partida', '{n}.v_cfpd05_denominaciones.deno_partida');
			$this->concatena($lista, 'vector');

		break;
		case 'generica':
			$this->set('SELECT','especifica');
			$this->set('codigo','generica');
			$this->set('seleccion','');
			$this->set('n',7);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy =  $this->Session->read('proy');
			$activ=$this->Session->read('actividad');
			$this->Session->write('cpar',$var);
			$cond2 =$cond." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$activ." and cod_partida=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_generica ASC', null, '{n}.v_cfpd05_denominaciones.cod_generica', '{n}.v_cfpd05_denominaciones.deno_generica');
					$this->concatena($lista, 'vector');
 		break;
		case 'especifica':
			$this->set('SELECT','subespecifica');
			$this->set('codigo','especifica');
			$this->set('seleccion','');
			$this->set('n',8);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy =  $this->Session->read('proy');
			$activ=$this->Session->read('actividad');
			$cpar =  $this->Session->read('cpar');
			$this->Session->write('cgen',$var);
			$cond2 =$cond." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$activ." and cod_partida=".$cpar." and cod_generica=".$var;
			$lista = $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_especifica ASC', null, '{n}.v_cfpd05_denominaciones.cod_especifica', '{n}.v_cfpd05_denominaciones.deno_especifica');
					$this->concatena($lista, 'vector');
		break;
		case 'subespecifica':
			$this->set('SELECT','auxiliar');
			$this->set('codigo','subespecifica');
			$this->set('seleccion','');
			$this->set('n',9);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy =  $this->Session->read('proy');
			$activ=$this->Session->read('actividad');
			$cpar =  $this->Session->read('cpar');
			$cgen =  $this->Session->read('cgen');
			$this->Session->write('cesp',$var);
			$cond2 =$cond." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$activ." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_sub_espec ASC', null, '{n}.v_cfpd05_denominaciones.cod_sub_espec', '{n}.v_cfpd05_denominaciones.deno_sub_espec');
		    $this->concatena($lista, 'vector');
		break;
		case 'auxiliar':

			$this->set('SELECT','escribir_aux');
			$this->set('codigo','auxiliar');
			$this->set('seleccion','');
			$this->set('n',10);
			//$this->set('no','no');
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy =  $this->Session->read('proy');
			$activ=$this->Session->read('actividad');
			$cpar =  $this->Session->read('cpar');
			$cgen =  $this->Session->read('cgen');
			$cesp =  $this->Session->read('cesp');
			$this->Session->write('csesp',$var);
			$cond2 =$cond." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$activ." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond." and ".$cond2, 'cod_auxiliar ASC', null, '{n}.v_cfpd05_denominaciones.cod_auxiliar', '{n}.v_cfpd05_denominaciones.deno_auxiliar');
						if($lista!=null){
							$this->concatena_auxiliar($lista, 'vector');
						}else{
							$this->set('vector',array('0'=>'00'));

						}
         //echo "muestra";
		break;
		case 'auxiliar2':
		 //echo "hola auxiliar 2";
			$this->set('SELECT','auxiliar');
			$this->set('codigo','auxiliar');
			$this->set('seleccion','');
			$this->set('n',10);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy =  $this->Session->read('proy');
			$cpar =  $this->Session->read('cpar');
			$cgen =  $this->Session->read('cgen');
			$cesp =  $this->Session->read('cesp');
			$this->Session->write('actividad',$var);
			$f=$this->Session->read('CodigosDireccion');
			$p=$this->Session->read('partidas');
			$cond2 =$cond." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$var." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$p[0]["cscd01_catalogo"]["cod_sub_espec"];

			$lista=  $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_auxiliar ASC', null, '{n}.v_cfpd05_denominaciones.cod_auxiliar', '{n}.v_cfpd05_denominaciones.deno_auxiliar');
			/**			if($lista!=null){
							$this->AddCero('vector',$lista);
						}else{
							$this->set('vector',array('0'=>'00'));
						}*/
						if($lista!=null){
							$this->concatena_auxiliar($lista, 'vector');
							//echo count($lista);
						}else{
							$this->set('vector',array('0'=>'00'));
							//echo "cero";
							$disponibilidad = $this->disponibilidad($_SESSION["ano"], $_SESSION["sec"], $_SESSION["prog"], $_SESSION["subp"], $_SESSION["proy"], $_SESSION["actividad"], $_SESSION["cpar"], $_SESSION["cgen"], $_SESSION["cesp"], $_SESSION["csesp"], 0);

				       echo "<script>" .
				 		"document.getElementById('td_disponibilidad').innerHTML='".$this->Formato2($disponibilidad)."'; " .
				 		"</script>";
						}
		break;
		case 'escribir_aux':
       /// echo "saaaaaaaaaaa";
				 $this->Session->write('auxiliar',$var);
				 $disponibilidad = $this->disponibilidad($_SESSION["ano"], $_SESSION["sec"], $_SESSION["prog"], $_SESSION["subp"], $_SESSION["proy"], $_SESSION["actividad"], $_SESSION["cpar"], $_SESSION["cgen"], $_SESSION["cesp"], $_SESSION["csesp"], $_SESSION["auxiliar"]);

				 echo "<script>" .
				 		"document.getElementById('td_disponibilidad').innerHTML='".$this->Formato2($disponibilidad)."';" .
				 		"</script>";
				 $this->set("ocultar",true);
		break;
	}//fin wsitch
	}else{
			$this->set('SELECT','');
			$this->set('codigo','');
			$this->set('seleccion','');
			$this->set('n',12);
			$this->set('no','no');
		    $this->set('vector',array('0'=>'00'));
	}
}//fin select codigos presupuestarios

function mostrar3($select=null,$var=null) { //mostrar3 codigos presupuestarios
	$this->layout = "ajax";
	//echo "mostrar3";
if( $var!=null){
    if($this->verifica_SS(5)==1){
    	    $cond=$this->SQLCA_report(1);
    	}else{
    		$cond=$this->SQLCA_report();
    	}
	switch($select){
		case 'sector':
		  $ano=$this->Session->read('ano_reporte');
		  $this->Session->write('ano',$ano);

		  $this->Session->write('dsec',$var);
		  $cond .=" and ano=".$ano." and cod_sector=".$var;
		  $a=  $this->v_cfpd05_denominaciones->findAll($cond,array('deno_sector'));
          $xdeno= $a[0]['v_cfpd05_denominaciones']['deno_sector'];
          if($xdeno==''){
          	echo "N/A";
          }else{
          	echo $xdeno." &nbsp;";
          }
		break;
		case 'programa':
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('dsec');
		  $this->Session->write('dprog',$var);
		  $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$var;
		  $a=  $this->v_cfpd05_denominaciones->findAll($cond,array('deno_programa'));
          $xdeno=$a[0]['v_cfpd05_denominaciones']['deno_programa'];
          if($xdeno==''){
          	echo "N/A";
          }else{
          	echo $xdeno." &nbsp;";
          }
		break;
		case 'subprograma':
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('dsec');
		  $prog =  $this->Session->read('dprog');
		  $this->Session->write('dsubprog',$var);
		  $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$var;
		  $a=  $this->v_cfpd05_denominaciones->findAll($cond,array('deno_sub_prog'));
          $xdeno= $a[0]['v_cfpd05_denominaciones']['deno_sub_prog'];
          if($xdeno==''){
          	echo "N/A";
          }else{
          	echo $xdeno." &nbsp;";
          }
		break;
		case 'proyecto':
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('dsec');
		  $prog =  $this->Session->read('dprog');
		  $subprog =  $this->Session->read('dsubprog');
		  $this->Session->write('dproy',$var);
		  $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subprog." and cod_proyecto=".$var;
		  $a=  $this->v_cfpd05_denominaciones->findAll($cond,array('deno_proyecto'));
          $xdeno= $a[0]['v_cfpd05_denominaciones']['deno_proyecto'];
          if($xdeno==''){
          	echo "N/A";
          }else{
          	echo $xdeno." &nbsp;";
          }
		break;
		case 'actividad':
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('dsec');
		  $prog =  $this->Session->read('dprog');
		  $subprog =  $this->Session->read('dsubprog');
		  $proy =  $this->Session->read('dproy');
		  $this->Session->write('activ',$var);
		  $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subprog." and cod_proyecto=".$proy." and cod_activ_obra=".$var;
		  $a=  $this->v_cfpd05_denominaciones->findAll($cond,array('deno_activ_obra'));
          $xdeno= $a[0]['v_cfpd05_denominaciones']['deno_activ_obra'];
          if($xdeno==''){
          	echo "N/A";
          }else{
          	echo $xdeno." &nbsp;";
          }
		break;
		case 'partida':
		  $ano =  $this->Session->read('ano');
		  $this->Session->write('dpar',$var);
		  $cond2 ="and ano=".$ano." and cod_partida=".$var;
		  $a=  $this->v_cfpd05_denominaciones->findAll($cond.$cond2,array('deno_partida'));
          $xdeno= $a[0]['v_cfpd05_denominaciones']['deno_partida'];
          if($xdeno==''){
          	echo "N/A";
          }else{
          	echo $xdeno." &nbsp;";
          }
		break;
		case 'generica':
		  $ano =  $this->Session->read('ano');
		  $dpar=  $this->Session->read('dpar');
		  $this->Session->write('dgen',$var);
		  $cond2 =" and ano=".$ano." and cod_partida=".$dpar." and cod_generica=".$var;
		  $a=  $this->v_cfpd05_denominaciones->findAll($cond.$cond2,array('deno_generica'));
          $xdeno= $a[0]['v_cfpd05_denominaciones']['deno_generica'];
          if($xdeno==''){
          	echo "N/A";
          }else{
          	echo $xdeno." &nbsp;";
          }
		break;
		case 'especifica':
		   $ano =  $this->Session->read('ano');
		  $dpar=  $this->Session->read('dpar');
		  $dgen =  $this->Session->read('dgen');
		  $this->Session->write('desp',$var);
		  $cond2 =" and ano=".$ano." and cod_partida=".$dpar." and cod_generica=".$dgen." and cod_especifica=".$var;
		  $a=  $this->v_cfpd05_denominaciones->findAll($cond.$cond2,array('deno_especifica'));
          $xdeno= $a[0]['v_cfpd05_denominaciones']['deno_especifica'];
          if($xdeno==''){
          	echo "N/A";
          }else{
          	echo $xdeno." &nbsp;";
          }
		break;
		case 'subespecifica':
		  $ano =  $this->Session->read('ano');
		  $dpar=  $this->Session->read('dpar');
		  $dgen =  $this->Session->read('dgen');
		  $desp =  $this->Session->read('desp');
		  $this->Session->write('dsubesp',$var);
		  $cond2 =" and ano=".$ano." and cod_partida=".$dpar." and cod_generica=".$dgen." and cod_especifica=".$desp." and cod_sub_espec=".$var;
		  $a=  $this->v_cfpd05_denominaciones->findAll($cond.$cond2,array('deno_sub_espec'));
          $xdeno= $a[0]['v_cfpd05_denominaciones']['deno_sub_espec'];
          if($xdeno==''){
          	echo "N/A";
          }else{
          	echo $xdeno." &nbsp;";
          }
		break;
		case 'auxiliar':
		  $sec =  $this->Session->read('dsec');
		  $prog =  $this->Session->read('dprog');
		  $subprog =  $this->Session->read('dsubprog');
		  $proy =  $this->Session->read('dproy');
		  $activ = $this->Session->read('activ');
		  $ano =  $this->Session->read('ano');
		  $dpar=  $this->Session->read('dpar');
		  $dpar = $dpar; //< 10 ? CE."0".$dpar : CE.$dpar;
		  $dgen =  $this->Session->read('dgen');
		  $desp =  $this->Session->read('desp');
		  $dsubesp =  $this->Session->read('dsubesp');
		  $con3=" and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subprog." and cod_proyecto=".$proy." and cod_activ_obra=".$activ;
		  $cond2 =$con3." and ano=".$ano." and cod_partida=".$dpar." and cod_generica=".$dgen." and cod_especifica=".$desp." and cod_sub_espec=".$dsubesp." and cod_auxiliar=".$var;

		  $a=  $this->v_cfpd05_denominaciones->findAll($cond.$cond2,array('deno_auxiliar'));
		  //print_r($a);
          $xdeno=$a[0]['v_cfpd05_denominaciones']['deno_auxiliar'];
          if($xdeno==''){
          	echo "N/A";
          }else{
          	echo $xdeno." &nbsp;";
          }


		break;
	}//fin wsitch
	}else{
		echo " &nbsp;";
	}
}//fin mostrar3 codigos presupuestarios

function consulta_partidas_form() {
	$this->layout="ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$ano = $this->ano_ejecucion();
	$this->set('ano', $ano);
	$this->Session->write('ano_reporte',$ano);
	if($this->verifica_SS(5)==1){
    	    $cond=$this->SQLCA_report(1);
    	}else{
    		$cond=$this->SQLCA_report();
    	}

	$rs=$this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_sector,deno_sector FROM v_cfpd05_denominaciones WHERE ". $cond." and ano=".$ano." ORDER BY cod_sector ASC");
    foreach($rs as $l){
		$v[]=$l[0]["cod_sector"];
		$d[]=$l[0]["deno_sector"];
	}
	$lista = array_combine($v, $d);
	$rsp=$this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_partida,deno_partida FROM v_cfpd05_denominaciones  WHERE ". $cond." and ano=".$ano." ORDER BY cod_partida ASC");
    foreach($rsp as $lp){
		$vp[]=$lp[0]["cod_partida"];
		$dp[]=$lp[0]["deno_partida"];
	}
	$partida = array_combine($vp, $dp);
	$this->concatena($lista, 'sector');
	$this->concatena($partida, 'partida');

}//fin

function consulta_partidas() {
	$this->layout="ajax";
	//v_deno_analisis
    $this->set('entidad_federal', $this->Session->read('entidad_federal'));
	     if(isset($this->data["reporte"]["ano"]) && !empty($this->data["reporte"]["ano"])){
              $Ano=$this->data["reporte"]["ano"];
	     }else{
	     	$Ano=$this->ano_ejecucion();
	     }
    	$this->set('ANO',$Ano);
    	if(isset($this->data['cfpp05']['consolidacion'])){
    	    $con=$this->SQLCA_report($this->data['cfpp05']['consolidacion']);
    	     $con_a=$this->SQLCA_report_a($this->data['cfpp05']['consolidacion']);
    	}else{
    		$con=$this->SQLCA_report();
    		$con_a=$this->SQLCA_report_a();
    	}
        $titulo_a = $this->Session->read('dependencia');
  	    $this->set('titulo_a',$titulo_a);
         $gr="";
        if(isset($this->data["reporte"]["cod_sector"]) && $this->data["reporte"]["cod_sector"]!=""){
        	$cod_sector=" cod_sector=".$this->data["reporte"]["cod_sector"]." and ";
        	$cod_sector_a=" a.cod_sector=".$this->data["reporte"]["cod_sector"]." and ";
        	$cod_sector_g ="cod_sector,";
        	$sector=$this->data["reporte"]["cod_sector"];
        }else{
        	$cod_sector=" 1=1 and ";
        	$cod_sector_a=" 1=1 and ";
        	$cod_sectro_g="";
        	$sector=0;
        }
        if(isset($this->data["reporte"]["cod_programa"]) && $this->data["reporte"]["cod_programa"]!=""){
        	$cod_programa=" cod_programa=".$this->data["reporte"]["cod_programa"]." and ";
        	$cod_programa_a=" a.cod_programa=".$this->data["reporte"]["cod_programa"]." and ";
            $cod_programa_g="cod_programa,";
            $programa=$this->data["reporte"]["cod_programa"];
        }else{
        	$cod_programa=" 1=1 and ";
        	$cod_programa_a=" 1=1 and ";
        	$cod_programa_g="";
        	$programa=0;
        }
        if(isset($this->data["reporte"]["cod_subprograma"]) && $this->data["reporte"]["cod_subprograma"]!=""){
        	$cod_sub_prog=" cod_sub_prog=".$this->data["reporte"]["cod_subprograma"]." and ";
        	$cod_sub_prog_a=" a.cod_sub_prog=".$this->data["reporte"]["cod_subprograma"]." and ";
            $cod_sub_prog_g="cod_sub_prog,";
            $sub_prog=$this->data["reporte"]["cod_subprograma"];
        }else{
        	$cod_sub_prog=" 1=1 and ";
        	$cod_sub_prog_a=" 1=1 and ";
        	$cod_sub_prog_g="";
        	$sub_prog=0;
        }
        if(isset($this->data["reporte"]["cod_proyecto"]) && $this->data["reporte"]["cod_proyecto"]!=""){
        	$cod_proyecto=" cod_proyecto=".$this->data["reporte"]["cod_proyecto"]." and ";
        	$cod_proyecto_a=" a.cod_proyecto=".$this->data["reporte"]["cod_proyecto"]." and ";
            $cod_proyecto_g="cod_proyecto,";
            $proyecto=$this->data["reporte"]["cod_proyecto"];
        }else{
        	$cod_proyecto=" 1=1 and ";
        	$cod_proyecto_a=" 1=1 and ";
        	$cod_proyecto_g="";
        	$proyecto=0;
        }
        if(isset($this->data["reporte"]["cod_actividad"]) && $this->data["reporte"]["cod_actividad"]!=""){
        	$cod_activ_obra=" cod_activ_obra=".$this->data["reporte"]["cod_actividad"]." ";
        	$cod_activ_obra_a=" a.cod_activ_obra=".$this->data["reporte"]["cod_actividad"]." ";
        	$cod_activ_obra_g="cod_activ_obra,";
        	$activ_obra=$this->data["reporte"]["cod_actividad"];
        }else{
        	$cod_activ_obra=" 1=1 ";
        	$cod_activ_obra_a=" 1=1 ";
        	$cod_activ_obra_g="";
        	$activ_obra=0;
        }
        if(isset($this->data["reporte"]["cod_partida"]) && $this->data["reporte"]["cod_partida"]!=""){
        	$cod_partida=" cod_partida=".$this->data["reporte"]["cod_partida"]." ";
        	$cod_partida_a=" a.cod_partida=".$this->data["reporte"]["cod_partida"]." ";
        	$cod_partida_g="cod_partida,";
        	$partida=$this->data["reporte"]["cod_partida"];
        }else{
        	$cod_partida=" 1=1 ";
        	$cod_partida_a=" 1=1 ";
        	$cod_partida_g="";
        	$partida=0;
        }
        if(isset($this->data["reporte"]["cod_generica"]) && $this->data["reporte"]["cod_generica"]!=""){
        	$cod_generica=" cod_generica=".$this->data["reporte"]["cod_generica"]." and ";
        	$cod_generica_a=" a.cod_generica=".$this->data["reporte"]["cod_generica"]." and ";
        	$cod_generica_g="cod_generica,";
        	$generica=$this->data["reporte"]["cod_generica"];
        }else{
        	$cod_generica=" 1=1 and ";
        	$cod_generica_a=" 1=1 and ";
        	$cod_generica_g="";
        	$generica=0;
        }
        if(isset($this->data["reporte"]["cod_especifica"]) && $this->data["reporte"]["cod_especifica"]!=""){
        	$cod_especifica=" cod_especifica=".$this->data["reporte"]["cod_especifica"]." and ";
        	$cod_especifica_a=" a.cod_especifica=".$this->data["reporte"]["cod_especifica"]." and ";
        	$cod_especifica_g="cod_especifica,";
        	$especifica=$this->data["reporte"]["cod_especifica"];
        }else{
        	$cod_especifica=" 1=1 and ";
        	$cod_especifica_a=" 1=1 and ";
        	$cod_especifica_g="";
        	$especifica=0;
        }
        if(isset($this->data["reporte"]["cod_subespecifica"]) && $this->data["reporte"]["cod_subespecifica"]!=""){
        	$cod_sub_espec=" cod_sub_espec=".$this->data["reporte"]["cod_subespecifica"]." and ";
        	$cod_sub_espec_a=" a.cod_sub_espec=".$this->data["reporte"]["cod_subespecifica"]." and ";
        	$cod_sub_espec_g="cod_sub_espec,";
        	$sub_espec=$this->data["reporte"]["cod_subespecifica"];
        }else{
        	$cod_sub_espec=" 1=1 and ";
        	$cod_sub_espec_a=" 1=1 and ";
        	$cod_sub_espec_g="";
        	$sub_espec=0;
        }
        if(isset($this->data["reporte"]["cod_auxiliar"]) && $this->data["reporte"]["cod_auxiliar"]!=""){
        	$cod_auxiliar=" cod_auxiliar=".$this->data["reporte"]["cod_auxiliar"]." ";
        	$cod_auxiliar_a=" a.cod_auxiliar=".$this->data["reporte"]["cod_auxiliar"]." ";
        	$cod_auxiliar_g="cod_auxiliar";
        	$auxiliar=$this->data["reporte"]["cod_auxiliar"];
        }else{
        	$cod_auxiliar=" 1=1 ";
        	$cod_auxiliar_a=" 1=1 ";
        	$cod_auxiliar_g="";
        	$auxiliar=0;
        }
  	    $modo= (int) $this->data["reporte"]["modo"];
  	    //echo "MODO: ".$modo;
  	    switch($modo){
  	    	case 1:
                 //completo todo
                 $condicion=" 1=1";
                  $condicion_a=" 1=1";
  	    	break;
  	    	case 2:
  	    	      //por categoria
  	    	      $condicion=" ".$cod_sector.$cod_programa.$cod_sub_prog.$cod_proyecto.$cod_activ_obra;
  	    	      $condicion_a=" ".$cod_sector_a.$cod_programa_a.$cod_sub_prog_a.$cod_proyecto_a.$cod_activ_obra_a;
  	    	      $condicion_g=" ".$cod_sector_g.$cod_programa_g.$cod_sub_prog_g.$cod_proyecto_g.$cod_activ_obra_g;
  	    	break;
  	    	case 3:
  	    	    //por categoria y partida
  	    	    $condicion=" ".$cod_sector.$cod_programa.$cod_sub_prog.$cod_proyecto.$cod_activ_obra." and ".$cod_partida." and ".$cod_generica.$cod_especifica.$cod_sub_espec.$cod_auxiliar;
  	    	    $condicion_a=" ".$cod_sector_a.$cod_programa_a.$cod_sub_prog_a.$cod_proyecto_a.$cod_activ_obra_a." and ".$cod_partida_a." and ".$cod_generica_a.$cod_especifica_a.$cod_sub_espec_a.$cod_auxiliar_a;
  	    	    $condicion_g=" ".$cod_sector_g.$cod_programa_g.$cod_sub_prog_g.$cod_proyecto_g.$cod_activ_obra_g.$cod_partida_g.$cod_generica_g.$cod_especifica_g.$cod_sub_espec_g.$cod_auxiliar_g;
            break;
            case 4:
                 $condicion=" ".$cod_partida;
                 $condicion_a=" ".$cod_partida_a;
                 $condicion_g=" ".$cod_partida_g;
            break;
            case 5:
                 $condicion=" ".$cod_partida." and ".$cod_generica.$cod_especifica.$cod_sub_espec.$cod_auxiliar;
                 $condicion_a=" ".$cod_partida_a." and ".$cod_generica_a.$cod_especifica_a.$cod_sub_espec_a.$cod_auxiliar_a;
                 $condicion_g=" ".$cod_partida_g.",".$cod_generica_g.$cod_especifica_g.$cod_sub_espec_g.$cod_auxiliar_g;
            break;
            default: $condicion=" 1=1";
                     $condicion_a=" 1=1";
            break;
  	    }//fin switch
        //echo $con." and ano=".$Ano." and ".$condicion;
		$resultado=$this->v_balance_ejecucion->findAll($con." and ano=".$Ano." and ".$condicion,array('SUM(asignacion_anual) as asignacion_anual','SUM(aumento) as aumento','SUM(disminucion) as disminucion','SUM(total_asignacion) as total_asignacion','SUM(pre_compromiso) as pre_compromiso','SUM(compromiso_anual) as compromiso_anual','SUM(causado_anual) as causado_anual','SUM(pagado_anual) as pagado_anual','SUM(deuda) as deuda','SUM(disponibilidad) as disponibilidad'));
		//pr($resultado);
		//$resultado=$this->v_balance_ejecucion->findAll($this->SQLCA()." and ".$condicion,array('asignacion_anual','aumento','disminucion','total_asignacion','pre_compromiso','compromiso_anual','causado_anual','pagado_anual','deuda','disponibilidad'));
		$resultado2=$this->v_balance_ejecucion2->execute("SELECT
SUM(((asignacion_anual/12)+((aumento_ene-disminucion_ene)/12))::numeric(22,2)) AS dm_ene,
SUM(((asignacion_anual/12)+((aumento_ene-disminucion_ene)/12)+((aumento_feb-disminucion_feb)/11))::numeric(22,2)) AS dm_feb,
SUM(((asignacion_anual/12)+((aumento_ene-disminucion_ene)/12)+((aumento_feb-disminucion_feb)/11)+((aumento_mar-disminucion_mar)/10))::numeric(22,2)) AS dm_mar,
SUM(((asignacion_anual/12)+((aumento_ene-disminucion_ene)/12)+((aumento_feb-disminucion_feb)/11)+((aumento_mar-disminucion_mar)/10)+((aumento_abr-disminucion_abr)/9))::numeric(22,2)) AS dm_abr,
SUM(((asignacion_anual/12)+((aumento_ene-disminucion_ene)/12)+((aumento_feb-disminucion_feb)/11)+((aumento_mar-disminucion_mar)/10)+((aumento_abr-disminucion_abr)/9)+((aumento_may-disminucion_may)/8))::numeric(22,2)) AS dm_may,
SUM(((asignacion_anual/12)+((aumento_ene-disminucion_ene)/12)+((aumento_feb-disminucion_feb)/11)+((aumento_mar-disminucion_mar)/10)+((aumento_abr-disminucion_abr)/9)+((aumento_may-disminucion_may)/8)+((aumento_jun-disminucion_jun)/7))::numeric(22,2)) AS dm_jun,
SUM(((asignacion_anual/12)+((aumento_ene-disminucion_ene)/12)+((aumento_feb-disminucion_feb)/11)+((aumento_mar-disminucion_mar)/10)+((aumento_abr-disminucion_abr)/9)+((aumento_may-disminucion_may)/8)+((aumento_jun-disminucion_jun)/7)+((aumento_jul-disminucion_jul)/6))::numeric(22,2)) AS dm_jul,
SUM(((asignacion_anual/12)+((aumento_ene-disminucion_ene)/12)+((aumento_feb-disminucion_feb)/11)+((aumento_mar-disminucion_mar)/10)+((aumento_abr-disminucion_abr)/9)+((aumento_may-disminucion_may)/8)+((aumento_jun-disminucion_jun)/7)+((aumento_jul-disminucion_jul)/6)+((aumento_ago-disminucion_ago)/5))::numeric(22,2)) AS dm_ago,
SUM(((asignacion_anual/12)+((aumento_ene-disminucion_ene)/12)+((aumento_feb-disminucion_feb)/11)+((aumento_mar-disminucion_mar)/10)+((aumento_abr-disminucion_abr)/9)+((aumento_may-disminucion_may)/8)+((aumento_jun-disminucion_jun)/7)+((aumento_jul-disminucion_jul)/6)+((aumento_ago-disminucion_ago)/5)+((aumento_sep-disminucion_sep)/4))::numeric(22,2)) AS dm_sep,
SUM(((asignacion_anual/12)+((aumento_ene-disminucion_ene)/12)+((aumento_feb-disminucion_feb)/11)+((aumento_mar-disminucion_mar)/10)+((aumento_abr-disminucion_abr)/9)+((aumento_may-disminucion_may)/8)+((aumento_jun-disminucion_jun)/7)+((aumento_jul-disminucion_jul)/6)+((aumento_ago-disminucion_ago)/5)+((aumento_sep-disminucion_sep)/4)+((aumento_oct-disminucion_oct)/3))::numeric(22,2)) AS dm_oct,
SUM(((asignacion_anual/12)+((aumento_ene-disminucion_ene)/12)+((aumento_feb-disminucion_feb)/11)+((aumento_mar-disminucion_mar)/10)+((aumento_abr-disminucion_abr)/9)+((aumento_may-disminucion_may)/8)+((aumento_jun-disminucion_jun)/7)+((aumento_jul-disminucion_jul)/6)+((aumento_ago-disminucion_ago)/5)+((aumento_sep-disminucion_sep)/4)+((aumento_oct-disminucion_oct)/3)+((aumento_nov-disminucion_nov)/2))::numeric(22,2)) AS dm_nov,
SUM(((asignacion_anual/12)+((aumento_ene-disminucion_ene)/12)+((aumento_feb-disminucion_feb)/11)+((aumento_mar-disminucion_mar)/10)+((aumento_abr-disminucion_abr)/9)+((aumento_may-disminucion_may)/8)+((aumento_jun-disminucion_jun)/7)+((aumento_jul-disminucion_jul)/6)+((aumento_ago-disminucion_ago)/5)+((aumento_sep-disminucion_sep)/4)+((aumento_oct-disminucion_oct)/3)+((aumento_nov-disminucion_nov)/2)+((aumento_dic-disminucion_dic)/1))::numeric(22,2)) AS dm_dic,
SUM(compromiso_ene) AS compromiso_ene,
SUM(compromiso_feb) AS compromiso_feb,
SUM(compromiso_mar) AS compromiso_mar,
SUM(compromiso_abr) AS compromiso_abr,
SUM(compromiso_may) AS compromiso_may,
SUM(compromiso_jun) AS compromiso_jun,
SUM(compromiso_jul) AS compromiso_jul,
SUM(compromiso_ago) AS compromiso_ago,
SUM(compromiso_sep) AS compromiso_sep,
SUM(compromiso_oct) AS compromiso_oct,
SUM(compromiso_nov) AS compromiso_nov,
SUM(compromiso_dic) AS compromiso_dic  FROM v_balance_ejecucion2 WHERE ".$con." and ano=".$Ano." and ".$condicion);

        $asignacion_inicial=$resultado[0][0]["asignacion_anual"];
        $this->set("resultado_mensual",$resultado2);
        $aumento=$resultado[0][0]["aumento"];
        $disminucion=$resultado[0][0]["disminucion"];
        $monto_actualizado=$resultado[0][0]["total_asignacion"];
        if($asignacion_inicial==0 && ($aumento!=0 || $disminucion!=0)){
        	$porcentaje_modificado=100;
        }else{
            $porcentaje_modificado=(($aumento-$disminucion)/$asignacion_inicial)*100;
            $porcentaje_modificado=$porcentaje_modificado<0?$porcentaje_modificado*(-1):$porcentaje_modificado;
        }
        $porcentaje_modificado=sprintf("%01.2f",$porcentaje_modificado);
        $precompromiso=$resultado[0][0]["pre_compromiso"];
        $compromiso=$resultado[0][0]["compromiso_anual"];
        $causado=$resultado[0][0]["causado_anual"];
        $pagado=$resultado[0][0]["pagado_anual"];
        $disponibilidad=$resultado[0][0]["disponibilidad"];
        $monto_actualizado2=$monto_actualizado==0?1:$monto_actualizado;
        $porcentaje_ejecutado=($compromiso/$monto_actualizado2)*100;

        $porcentaje_ejecutado=sprintf("%01.2f",$porcentaje_ejecutado);
        $disponibilidad_causar=$compromiso-$causado;
        $disponibilidad_pagar=$causado-$pagado;
        $this->set("asignacion_inicial",$asignacion_inicial);
        $this->set("aumento",$aumento);
        $this->set("disminucion",$disminucion);
        $this->set("monto_actualizado",$monto_actualizado);
        $this->set("porcentaje_modificado",$porcentaje_modificado);
        $this->set("precompromiso",$precompromiso);
        $this->set("compromiso",$compromiso);
        $this->set("causado",$causado);
        $this->set("pagado",$pagado);
        $this->set("disponibilidad",$disponibilidad);
        $this->set("porcentaje_ejecutado",$porcentaje_ejecutado);
        $this->set("disponibilidad_causar",$disponibilidad_causar);
        $this->set("disponibilidad_pagar",$disponibilidad_pagar);


        $this->set("cod_sector",$sector);
		$this->set("cod_programa",$programa);
		$this->set("cod_sub_prog",$sub_prog);
		$this->set("cod_proyecto",$proyecto);
		$this->set("cod_activ_obra",$activ_obra);
		$this->set("cod_partida",$partida);
		$this->set("cod_generica",$generica);
		$this->set("cod_especifica",$especifica);
		$this->set("cod_sub_espec",$sub_espec);
		$this->set("cod_auxiliar",$auxiliar);
        $resultado3=$this->v_balance_ejecucion2->execute("SELECT * FROM v_balance_ejecucion2 WHERE ". $this->SQLCA()." and ".$condicion." and ano=".$Ano);
$this->set("RESULTADO3",$resultado3);

$resultados_mov=$this->v_balance_ejecucion2->execute("SELECT * FROM v_cfpd05_movimientos_vision WHERE ". $this->SQLCA()." and ".$condicion." and ano=".$Ano);
$this->set("MOV_CFPD",$resultados_mov);

/*
$resultado20=$this->v_balance_ejecucion2->execute("SELECT * FROM cfpd20 WHERE ". $this->SQLCA()." and ".$condicion." and ano=".$Ano);
$resultado21=$this->v_balance_ejecucion2->execute("SELECT * FROM cfpd21 WHERE ". $this->SQLCA()." and ".$condicion." and ano=".$Ano);
$resultado22=$this->v_balance_ejecucion2->execute("SELECT * FROM cfpd22 WHERE ". $this->SQLCA()." and ".$condicion." and ano=".$Ano);
$resultado23=$this->v_balance_ejecucion2->execute("SELECT * FROM cfpd23 WHERE ". $this->SQLCA()." and ".$condicion." and ano=".$Ano);
$this->set("CFPD20",$resultado20);
$this->set("CFPD21",$resultado21);
$this->set("CFPD22",$resultado22);
$this->set("CFPD23",$resultado23);
*/



}//fin consulta partidas


function tipo_presupuesto ($var=null, $var2=null) {
    $this->layout="ajax";
     $this->set("cod_dep",$this->verifica_SS(5));
$ver="si";
$dato_ano = $this->ano_ejecucion();
if(isset($this->data["tipo_presupuestoPDF"]["year"])){$year=$this->data["tipo_presupuestoPDF"]["year"];   }else{$year=$dato_ano;}
if(isset($this->data["tipo_presupuestoPDF"]["opcion"])){$var=$this->data["tipo_presupuestoPDF"]["opcion"]; $ver = "no"; }else{$var=1;}
$this->set("year",$year);
$this->set("ver",$ver);

    if(isset($var) && $this->verifica_SS(5)==1){
        if($var==1){//institucion
           $cod_dep="";
           $cod_dep2="";
           $con=$this->SQLCA_consolidado_opcion($var, "a");
           $gror="";
           $this->set("opcion",1);
        }else{
           $var=2;
           $cod_dep="a.cod_dep,";
           $cod_dep2="x.cod_dep=a.cod_dep and";
           $con=$this->SQLCA_consolidado_opcion($var, "a");
           $gror=",a.cod_dep";
           $this->set("opcion",2);
        }
    }else{
           $var=2;
           $cod_dep="a.cod_dep,";
           $cod_dep2="x.cod_dep=a.cod_dep and";
           $con=$this->SQLCA_consolidado_opcion($var, "a");
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

$this->set('tipo_presupuesto',$tipo_presupuesto);
//pr($tipo_presupuesto);

}//fin tipo presupuesto

function tipo_sector ($var=null) {
   $this->layout="ajax";
    	$this->set("cod_dep",$this->verifica_SS(5));

$ver="si";
$dato_ano = $this->ano_ejecucion();
if(isset($this->data["tipo_presupuestoPDF"]["year"])){$year=$this->data["tipo_presupuestoPDF"]["year"];   }else{$year=$dato_ano;}
if(isset($this->data["tipo_presupuestoPDF"]["opcion"])){$var=$this->data["tipo_presupuestoPDF"]["opcion"]; $ver = "no"; }else{$var=1;}
$this->set("year",$year);
$this->set("ver",$ver);

    if(isset($var) && $this->verifica_SS(5)==1){
        if($var==1){//institucion
           $cod_dep="";
           $cod_dep2="";
           $con=$this->SQLCA_consolidado_opcion($var, "a");
           $gror="";
           $this->set("opcion",1);
        }else{
           $var=2;
           $cod_dep="a.cod_dep,";
           $cod_dep2="x.cod_dep=a.cod_dep and";
           $con=$this->SQLCA_consolidado_opcion($var, "a");
           $gror=",a.cod_dep";
           $this->set("opcion",2);
        }
    }else{
           $var=2;
           $cod_dep="a.cod_dep,";
           $cod_dep2="x.cod_dep=a.cod_dep and";
           $con=$this->SQLCA_consolidado_opcion($var, "a");
           $gror=",a.cod_dep";
           $this->set("opcion",2);
    }
$_SESSION["CONDICIONPDF"]=$con;
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

$this->set('tipo_sector',$tipo_sector);
$rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_sector,a.deno_sector FROM v_balance_ejecucion a WHERE ". $con." and a.ano=".$year."");
    foreach($rs as $l){
		$v[]=$l[0]["cod_sector"];
		$d[]=$l[0]["deno_sector"];
	}
	if(isset($v)){$sector = array_combine($v, $d); }else{ $sector = array();}
	$this->set("SECTOR", $sector);

//pr($sector);

}//tipo sector





function tipo_partida ($var=null) {
   $this->layout="ajax";
    	$this->set("cod_dep",$this->verifica_SS(5));

$ver="si";
$dato_ano = $this->ano_ejecucion();
if(isset($this->data["tipo_presupuestoPDF"]["year"])){$year=$this->data["tipo_presupuestoPDF"]["year"];   }else{$year=$dato_ano;}
if(isset($this->data["tipo_presupuestoPDF"]["opcion"])){$var=$this->data["tipo_presupuestoPDF"]["opcion"]; $ver = "no"; }else{$var=1;}
$this->set("year",$year);
$this->set("ver",$ver);


    if(isset($var) && $this->verifica_SS(5)==1){
        if($var==1){//institucion
           $cod_dep="";
           $cod_dep2="";
           $con=$this->SQLCA_consolidado_opcion($var, "a");
           $gror="";
           $this->set("opcion",1);
        }else{
           $var=2;
           $cod_dep="a.cod_dep,";
           $cod_dep2="x.cod_dep=a.cod_dep and";
           $con=$this->SQLCA_consolidado_opcion($var, "a");
           $gror=",a.cod_dep";
           $this->set("opcion",2);
        }
    }else{
           $var=2;
           $cod_dep="a.cod_dep,";
           $cod_dep2="x.cod_dep=a.cod_dep and";
           $con=$this->SQLCA_consolidado_opcion($var, "a");
           $gror=",a.cod_dep";
           $this->set("opcion",2);
    }
$_SESSION["CONDICIONPDF"]=$con;
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

$this->set('tipo_partida',$tipo_partida);
$rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_partida,a.deno_partida FROM v_cfpd05_denominaciones a WHERE ". $con." and a.ano=".$year."");
    foreach($rs as $l){
		$v[]=$l[0]["cod_partida"];
		$d[]=$l[0]["deno_partida"];
	}
	if(isset($v)){$PARTIDA = array_combine($v, $d); }else{ $PARTIDA = array();}
	$this->set("PARTIDA", $PARTIDA);


}//tipo partidas

function prueba_tab () {
	$this->layout="ajax";
   $this->set("ano",date("Y"));
}//fin preuba_tab

function prueba_tab2 () {
	$this->layout="ajax";
   //$this->set("ano",date("Y"));
   //pr($this->data["consulta"]);
   //$this->data["consulta"]=null;
   $this->set("errorMessage","SE CARGO CORRECTAMENTE EN LA PESTAÑA");
}//fin preuba_tab

function consulta_tab($var=null) {
	$this->layout="ajax";
	//$this->limpia_menu();
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$ano = $this->ano_ejecucion();
	$this->set('ano', $ano);

}//fin index









function vacio () {
	$this->layout="ajax";
}

}//fin class
?>