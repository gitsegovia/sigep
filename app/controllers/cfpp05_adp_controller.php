<?php

 class Cfpp05AdpController extends AppController{


 	var $uses = array('v_cfpd05_denominaciones','ccfd04_cierre_mes','cfpd05','cfpd05_requerimiento','cfpd05_auxiliar','cfpp05auxiliar','cfpd02_sector', 'cfpd02_programa', 'cfpd02_sub_prog', 'cfpd02_proyecto', 'cfpd02_activ_obra', 'arrd05','cfpd01_ano_grupo', 'cfpd01_ano_partida', 'cfpd01_ano_generica', 'cfpd01_ano_especifica', 'cfpd01_ano_sub_espec', 'cfpd01_ano_auxiliar', 'cfpd01_formulacion' );
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');


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
    function SQLCA_admin($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
         if($ano!=null){
	        if($this->verifica_SS(5)!=1){
         	$sql_re .= "cod_dep=".$this->verifica_SS(5)."  and  ";
                }
                $sql_re .= "ano=".$ano."  ";
         }else{
         	if($this->verifica_SS(5)!=1){
         	$sql_re .= "cod_dep=".$this->verifica_SS(5)."  ";
                }
         }
         return $sql_re;
    }//fin funcion SQLCA
    function SQLCA_reque($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
         if($ano!=null){
         	$sql_re .= "ano=".$ano."  ";
         }else{

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



   }//fin AddCero


   function concatenaCE_v2($vector1=null, $nomVar=null){
	if($vector1 != null){
		foreach($vector1 as $x => $y){
			$cod[CE.$this->zero($x)] =CE.$this->zero($x).' - '.$y;
		}
		//print_r($cod);
		$this->set($nomVar, $cod);
	}
}
	function index(){
	$this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
	$condicion2 = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;

	$year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
	$dato = null;
	foreach($year as $year){
		$dato = $year['cfpd01_formulacion']['ano_formular'];
	}

	if(!empty($dato)){
         $this->Session->write('ANO_EJECUCION',$this->ano_ejecucion());
         $this->Session->write('ANO_FORMULAR',$dato);
		 $this->set('year', $dato);
         $this->Session->write('ano',$dato);
	}else{
		$this->set('year', '');
	}


    $rs=$this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_sector,deno_sector FROM v_cfpd05_denominaciones WHERE ". $condicion2." and ano=".$dato." ORDER BY cod_sector ASC");
    foreach($rs as $l){
		$v[]=$l[0]["cod_sector"];
		$d[]=$l[0]["deno_sector"];
	}
	if(isset($v) &&  is_array($v)){
		$lista = array_combine($v, $d);
	}else{
       $lista = array("0"=>"N/A");
	}

	$rsp=$this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_partida,deno_partida FROM v_cfpd05_denominaciones  WHERE ". $condicion2." and ano=".$dato." ORDER BY cod_partida ASC");
    foreach($rsp as $lp){
		$vp[]=$lp[0]["cod_partida"];
		$dp[]=$lp[0]["deno_partida"];
	}

	if(isset($vp) &&  is_array($vp)){
		$partida = array_combine($vp, $dp);
	}else{
       $partida = array("0"=>"N/A");
	}
	$this->concatena($lista, 'sector');
	$this->concatena($partida, 'partida');
	$this->Session->delete('sec');
	$this->Session->delete('prog');
	$this->Session->delete('subp');
	$this->Session->delete('proy');
	$this->Session->delete('actividad');
	$this->Session->delete('cpar');
	$this->Session->delete('cgen');
	$this->Session->delete('cesp');


}//fin index


function aumento_disminucion_porcentual() {
	$this->layout="ajax";
    $this->set('entidad_federal', $this->Session->read('entidad_federal'));
        $Ano=$this->Session->read('ANO_FORMULAR');

    	$this->set('ANO',$Ano);
    		$con=$this->SQLCA_report();
    		//$con_a=$this->SQLCA_report_a();

        $titulo_a = $this->Session->read('dependencia');
  	    $this->set('titulo_a',$titulo_a);

        if(isset($this->data["reporte"]["cod_sector"]) && $this->data["reporte"]["cod_sector"]!=""){
        	$cod_sector=" cod_sector=".$this->data["reporte"]["cod_sector"]." and ";
        	$cod_sector_a=" a.cod_sector=".$this->data["reporte"]["cod_sector"]." and ";
        }else{
        	$cod_sector=" 1=1 and ";
        	$cod_sector_a=" 1=1 and ";
        }
        if(isset($this->data["reporte"]["cod_programa"]) && $this->data["reporte"]["cod_programa"]!=""){
        	$cod_programa=" cod_programa=".$this->data["reporte"]["cod_programa"]." and ";
        	$cod_programa_a=" a.cod_programa=".$this->data["reporte"]["cod_programa"]." and ";
        }else{
        	$cod_programa=" 1=1 and ";
        	$cod_programa_a=" 1=1 and ";
        }
        if(isset($this->data["reporte"]["cod_subprograma"]) && $this->data["reporte"]["cod_subprograma"]!=""){
        	$cod_sub_prog=" cod_sub_prog=".$this->data["reporte"]["cod_subprograma"]." and ";
        	$cod_sub_prog_a=" a.cod_sub_prog=".$this->data["reporte"]["cod_subprograma"]." and ";
        }else{
        	$cod_sub_prog=" 1=1 and ";
        	$cod_sub_prog_a=" 1=1 and ";
        }
        if(isset($this->data["reporte"]["cod_proyecto"]) && $this->data["reporte"]["cod_proyecto"]!=""){
        	$cod_proyecto=" cod_proyecto=".$this->data["reporte"]["cod_proyecto"]." and ";
        	$cod_proyecto_a=" a.cod_proyecto=".$this->data["reporte"]["cod_proyecto"]." and ";
        }else{
        	$cod_proyecto=" 1=1 and ";
        	$cod_proyecto_a=" 1=1 and ";
        }
        if(isset($this->data["reporte"]["cod_actividad"]) && $this->data["reporte"]["cod_actividad"]!=""){
        	$cod_activ_obra=" cod_activ_obra=".$this->data["reporte"]["cod_actividad"]." ";
        	$cod_activ_obra_a=" a.cod_activ_obra=".$this->data["reporte"]["cod_actividad"]." ";
        }else{
        	$cod_activ_obra=" 1=1 ";
        	$cod_activ_obra_a=" 1=1 ";
        }
        if(isset($this->data["reporte"]["cod_partida"]) && $this->data["reporte"]["cod_partida"]!=""){
        	$cod_partida=" cod_partida=".$this->data["reporte"]["cod_partida"]." ";
        	$cod_partida_a=" a.cod_partida=".$this->data["reporte"]["cod_partida"]." ";
        }else{
        	$cod_partida=" 1=1 ";
        	$cod_partida_a=" 1=1 ";
        }
        if(isset($this->data["reporte"]["cod_generica"]) && $this->data["reporte"]["cod_generica"]!=""){
        	$cod_generica=" cod_generica=".$this->data["reporte"]["cod_generica"]." and ";
        	$cod_generica_a=" a.cod_generica=".$this->data["reporte"]["cod_generica"]." and ";
        }else{
        	$cod_generica=" 1=1 and ";
        	$cod_generica_a=" 1=1 and ";
        }
        if(isset($this->data["reporte"]["cod_especifica"]) && $this->data["reporte"]["cod_especifica"]!=""){
        	$cod_especifica=" cod_especifica=".$this->data["reporte"]["cod_especifica"]." and ";
        	$cod_especifica_a=" a.cod_especifica=".$this->data["reporte"]["cod_especifica"]." and ";
        }else{
        	$cod_especifica=" 1=1 and ";
        	$cod_especifica_a=" 1=1 and ";
        }
        if(isset($this->data["reporte"]["cod_subespecifica"]) && $this->data["reporte"]["cod_subespecifica"]!=""){
        	$cod_sub_espec=" cod_sub_espec=".$this->data["reporte"]["cod_subespecifica"]." and ";
        	$cod_sub_espec_a=" a.cod_sub_espec=".$this->data["reporte"]["cod_subespecifica"]." and ";
        }else{
        	$cod_sub_espec=" 1=1 and ";
        	$cod_sub_espec_a=" 1=1 and ";
        }
        if(isset($this->data["reporte"]["cod_auxiliar"]) && $this->data["reporte"]["cod_auxiliar"]!=""){
        	$cod_auxiliar=" cod_auxiliar=".$this->data["reporte"]["cod_auxiliar"]." ";
        	$cod_auxiliar_a=" a.cod_auxiliar=".$this->data["reporte"]["cod_auxiliar"]." ";
        }else{
        	$cod_auxiliar=" 1=1 ";
        	$cod_auxiliar_a=" 1=1 ";
        }
  	    $modo= (int) $this->data["cfpp05_adp"]["modo"];
  	    $modo_adp=(int) $this->data["cfpp05_adp"]["modo_adp"];
  	    if(isset($this->data["cfpp05_adp"]["porcentaje"]) && !empty($this->data["cfpp05_adp"]["porcentaje"])){
  	    	 $porcentaje=$this->Formato1($this->data["cfpp05_adp"]["porcentaje"]);
  	    	 $this->set('porcentaje',$porcentaje);
  	    }else{
  	    	$porcentaje=0;
  	    	$this->set('porcentaje',$porcentaje);
  	    }
  	    if($modo_adp==1){
  	    	$signo="+";
  	    	$this->set("modo_adp"," del aumento de ");
  	    }else{
  	    	$signo="-";
  	    	$this->set("modo_adp"," de la disminuci&oacute;n de ");
  	    }

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
  	    	break;
  	    	case 3:
  	    	    //por categoria y partida
  	    	    $condicion=" ".$cod_sector.$cod_programa.$cod_sub_prog.$cod_proyecto.$cod_activ_obra." and ".$cod_partida." and ".$cod_generica.$cod_especifica.$cod_sub_espec.$cod_auxiliar;
  	    	    $condicion_a=" ".$cod_sector_a.$cod_programa_a.$cod_sub_prog_a.$cod_proyecto_a.$cod_activ_obra_a." and ".$cod_partida_a." and ".$cod_generica_a.$cod_especifica_a.$cod_sub_espec_a.$cod_auxiliar_a;
            break;
            case 4:
                 $condicion=" ".$cod_partida;
                 $condicion_a=" ".$cod_partida_a;
            break;
            case 5:
                 $condicion=" ".$cod_partida." and ".$cod_generica.$cod_especifica.$cod_sub_espec.$cod_auxiliar;
                 $condicion_a=" ".$cod_partida_a." and ".$cod_generica_a.$cod_especifica_a.$cod_sub_espec_a.$cod_auxiliar_a;
            break;
            default: $condicion=" 1=1";
                     $condicion_a=" 1=1";
                     break;
  	    }//fin switch
        //echo "<script>alert('".$con_a.$condicion_a."');</script>";
        $sin_afectadas=" and aumento_traslado_anual=0 and disminucion_traslado_anual=0 and credito_adicional_anual=0 and rebaja_anual=0 and compromiso_anual=0";
        $this->cfpd05->execute("BEGIN;");
        $sumatoria_sin_actualizar = $this->cfpd05->execute("SELECT sum(asignacion_anual) as asignacion_anual from cfpd05  WHERE ".$con." and ano=".$Ano." and ".$condicion.$sin_afectadas);
	    $actualizando = $this->cfpd05->execute("UPDATE cfpd05 SET asignacion_anual=(asignacion_anual)".$signo."((asignacion_anual*".$porcentaje.")/100)  WHERE ".$con." and ano=".$Ano." and ".$condicion.$sin_afectadas);
        $this->cfpd05->execute("COMMIT;");
        $sumatoria_actualizada = $this->cfpd05->execute("SELECT sum(asignacion_anual+0) as asignacion_anual from cfpd05  WHERE ".$con." and ano=".$Ano." and ".$condicion.$sin_afectadas);

        $this->set('acumulado_sin_actualizar',$sumatoria_sin_actualizar[0][0]["asignacion_anual"]);
        $this->set('acumulado_actualizado',$sumatoria_actualizada[0][0]["asignacion_anual"]);


}//fin amuento_disminucion_porcentual

function select3($select=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";

if($select!=null && $var!=null){
    		$cond=$this->SQLCA_report();

	switch($select){
		case 'sector':
			$this->set('SELECT','programa');
			$this->set('codigo','sector');
			$this->set('seleccion','');
			$this->set('n',1);
			$ano=$this->Session->read('ANO_FORMULAR');
			$this->Session->write('ano',$ano);
			$cond .=" and ano=".$ano;
			//$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_sector ASC', null, '{n}.v_cfpd05_denominaciones.cod_sector', '{n}.v_cfpd05_denominaciones.deno_sector');
			//$this->concatena($lista, 'vector');
			$rs=$this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_sector,deno_sector FROM v_cfpd05_denominaciones WHERE ". $cond." ORDER BY cod_sector ASC");
		    foreach($rs as $l){
				$v[]=$l[0]["cod_sector"];
				$d[]=$l[0]["deno_sector"];
			}
			$lista = array_combine($v, $d);
			$this->concatena($lista, 'vector');
		break;
		case 'programa':

			$this->set('SELECT','subprograma');
			$this->set('codigo','programa');
			$this->set('seleccion','');
			$this->set('n',2);
			$ano=$this->Session->read('ANO_FORMULAR');
			$this->Session->write('ano',$ano);
			$this->Session->write('sec',$var);
			$cond .=" and ano=".$ano." and cod_sector=".$var;
			//$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_programa ASC', null, '{n}.v_cfpd05_denominaciones.cod_programa', '{n}.v_cfpd05_denominaciones.deno_programa');
		    //$this->concatena($lista, 'vector');
		    $rs=$this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_programa,deno_programa FROM v_cfpd05_denominaciones WHERE ". $cond." ORDER BY cod_programa ASC");
		    foreach($rs as $l){
				$v[]=$l[0]["cod_programa"];
				$d[]=$l[0]["deno_programa"];
			}
			$lista = array_combine($v, $d);
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
			//$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_sub_prog ASC', null, '{n}.v_cfpd05_denominaciones.cod_sub_prog', '{n}.v_cfpd05_denominaciones.deno_sub_prog');
			//$this->concatena($lista, 'vector');
			$rs=$this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_sub_prog,deno_sub_prog FROM v_cfpd05_denominaciones WHERE ". $cond." ORDER BY cod_sub_prog ASC");
		    foreach($rs as $l){
				$v[]=$l[0]["cod_sub_prog"];
				$d[]=$l[0]["deno_sub_prog"];
			}
			$lista = array_combine($v, $d);
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
			//$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_proyecto ASC', null, '{n}.v_cfpd05_denominaciones.cod_proyecto', '{n}.v_cfpd05_denominaciones.deno_proyecto');
			//$this->concatena($lista, 'vector');
			$rs=$this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_proyecto,deno_proyecto FROM v_cfpd05_denominaciones WHERE ". $cond." ORDER BY cod_proyecto ASC");
		    foreach($rs as $l){
				$v[]=$l[0]["cod_proyecto"];
				$d[]=$l[0]["deno_proyecto"];
			}
			$lista = array_combine($v, $d);
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
			//$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_activ_obra ASC', null, '{n}.v_cfpd05_denominaciones.cod_activ_obra', '{n}.v_cfpd05_denominaciones.deno_activ_obra');
			//$this->concatena($lista, 'vector');
            $rs=$this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_activ_obra,deno_activ_obra FROM v_cfpd05_denominaciones WHERE ". $cond." ORDER BY cod_activ_obra ASC");
		    foreach($rs as $l){
				$v[]=$l[0]["cod_activ_obra"];
				$d[]=$l[0]["deno_activ_obra"];
			}
			$lista = array_combine($v, $d);
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
			//$lista=  $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_partida ASC', null, '{n}.v_cfpd05_denominaciones.cod_partida', '{n}.v_cfpd05_denominaciones.deno_partida');
			//$this->concatena($lista, 'vector');
            $rs=$this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_partida,deno_partida FROM v_cfpd05_denominaciones WHERE ". $cond2." ORDER BY cod_partida ASC");
		    foreach($rs as $l){
				$v[]=$l[0]["cod_partida"];
				$d[]=$l[0]["deno_partida"];
			}
			$lista = array_combine($v, $d);
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
			if($activ==""){
	            $cond2 =$cond." and ano=".$ano." and cod_partida=".$var;
			}else{
				$cond2 =$cond." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$activ." and cod_partida=".$var;
			}
			//$cond2 =$cond." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$activ." and cod_partida=".$var;

			//$lista=  $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_generica ASC', null, '{n}.v_cfpd05_denominaciones.cod_generica', '{n}.v_cfpd05_denominaciones.deno_generica');
			//$this->concatena($lista, 'vector');
            $rs=$this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_generica,deno_generica FROM v_cfpd05_denominaciones WHERE ". $cond2." ORDER BY cod_generica ASC");
		    foreach($rs as $l){
				$v[]=$l[0]["cod_generica"];
				$d[]=$l[0]["deno_generica"];
			}
			if(isset($v) && is_array($v)){
				$lista = array_combine($v, $d);
			}else{
				$lista = array('0'=>'N/A');
			}
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
			if($activ==""){
	            $cond2 =$cond." and ano=".$ano." and cod_partida=".$cpar." and cod_generica=".$var;
			}else{
				$cond2 =$cond." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$activ." and cod_partida=".$cpar." and cod_generica=".$var;
			}
			//$lista = $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_especifica ASC', null, '{n}.v_cfpd05_denominaciones.cod_especifica', '{n}.v_cfpd05_denominaciones.deno_especifica');
		    //$this->concatena($lista, 'vector');
		    $rs=$this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_especifica,deno_especifica FROM v_cfpd05_denominaciones WHERE ". $cond2." ORDER BY cod_especifica ASC");
		    foreach($rs as $l){
				$v[]=$l[0]["cod_especifica"];
				$d[]=$l[0]["deno_especifica"];
			}
			if(isset($v) && is_array($v)){
				$lista = array_combine($v, $d);
			}else{
				$lista = array('0'=>'N/A');
			}

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
			if($activ==""){
	            $cond2 =$cond." and ano=".$ano." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$var;
			}else{
				$cond2 =$cond." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$activ." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$var;
			}
			//$lista=  $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_sub_espec ASC', null, '{n}.v_cfpd05_denominaciones.cod_sub_espec', '{n}.v_cfpd05_denominaciones.deno_sub_espec');
		    //$this->concatena($lista, 'vector');
		    $rs=$this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_sub_espec,deno_sub_espec FROM v_cfpd05_denominaciones WHERE ". $cond2." ORDER BY cod_sub_espec ASC");
		    foreach($rs as $l){
				$v[]=$l[0]["cod_sub_espec"];
				$d[]=$l[0]["deno_sub_espec"];
			}
			if(isset($v) && is_array($v)){
				$lista = array_combine($v, $d);
			}else{
				$lista = array('0'=>'N/A');
			}

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
			//$cpar=$cpar<9 ? "40".$cpar  : "4".$cpar;
			//$cond2 ="ano=".$ano." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$var;
			//echo "AUX1".$cond2;
			$cond2 =$cond." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$activ." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$var;
			//echo $cond2;
			//$lista=  $this->v_cfpd05_denominaciones->generateList($cond." and ".$cond2, 'cod_auxiliar ASC', null, '{n}.v_cfpd05_denominaciones.cod_auxiliar', '{n}.v_cfpd05_denominaciones.deno_auxiliar');
            $rs=$this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_auxiliar,deno_auxiliar FROM v_cfpd05_denominaciones WHERE ". $cond2." ORDER BY cod_auxiliar ASC");
		    foreach($rs as $l){
				$v[]=$l[0]["cod_auxiliar"];
				$d[]=$l[0]["deno_auxiliar"];
			}
			$lista = array_combine($v, $d);
			//$this->concatena($lista, 'vector');
			if($lista!=null){
				$this->concatena($lista, 'vector');
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
			//$this->set('no','no');
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy =  $this->Session->read('proy');
			//$activ=$this->Session->read('actividad');
			$cpar =  $this->Session->read('cpar');
			$cgen =  $this->Session->read('cgen');
			$cesp =  $this->Session->read('cesp');
			$this->Session->write('actividad',$var);
			$f=$this->Session->read('CodigosDireccion');
			$p=$this->Session->read('partidas');
			 //print_r($p);
			/*$part= $p[0]['cscd01_catalogo']['cod_partida']<9 ? "40".$p[0]['cscd01_catalogo']['cod_partida']:$p[0]['cscd01_catalogo']['cod_partida'];
					$part= $part <400 ? "4".$part : $part;
					if($this->Session->read("year_pago")>date("Y")){
								$ano= 1+date("Y");
			}else{
							$ano=date("Y");
			}
			$cond2 =" cod_sector=".$f[0]["cugd02_direccion"]["cod_sector"]." and cod_programa=".$f[0]["cugd02_direccion"]["cod_programa"]." and cod_sub_prog=".$f[0]["cugd02_direccion"]["cod_sub_prog"]." and cod_proyecto=".$f[0]["cugd02_direccion"]["cod_proyecto"]." and cod_activ_obra=".$var." and ano=".$ano." and cod_partida=".$part." and cod_generica=".$p[0]["cscd01_catalogo"]["cod_generica"]." and cod_especifica=".$p[0]["cscd01_catalogo"]["cod_especifica"]." and cod_sub_espec=".$p[0]["cscd01_catalogo"]["cod_sub_espec"];
			//echo "AUX2".$cond2;*/
			$cond2 =$cond." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$var." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$p[0]["cscd01_catalogo"]["cod_sub_espec"];
			//echo $cond2;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_auxiliar ASC', null, '{n}.v_cfpd05_denominaciones.cod_auxiliar', '{n}.v_cfpd05_denominaciones.deno_auxiliar');
			/**			if($lista!=null){
							$this->AddCero('vector',$lista);
						}else{
							$this->set('vector',array('0'=>'00'));
						}*/
						if($lista!=null){
							$this->concatena($lista, 'vector');
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

    		$cond=$this->SQLCA_report();
	switch($select){
		case 'sector':
		  $ano=$this->Session->read('ANO_FORMULAR');
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


}//fin class


?>