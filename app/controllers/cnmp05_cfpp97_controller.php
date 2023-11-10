<?php

 class Cnmp05Cfpp97Controller extends AppController{


 	var $uses = array('ccfd04_cierre_mes','cfpd97','cnmd05','cfpd01_formulacion' );
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf');


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
 	 $this->set("cod_dep",$cod_dep);
 	 $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

	 $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
	 $dato = null;
	 foreach($year as $year){
		$dato = $year['cfpd01_formulacion']['ano_formular'];
	 }
    if(!empty($dato)){
         $this->Session->write('ANO_EJECUCION',$this->ano_ejecucion());
         $this->Session->write('ANO_FORMULAR',$dato);
		 $this->set('year', $dato);
	}else{
		$this->set('year', '');
	}
}//fin index
/*
          $r["cod_presi"]
          $r["cod_entidad"]
          $r["cod_tipo_inst"]
          $r["cod_inst"]
          $r["cod_dep"]
          $r["cod_tipo_nomina"]
          $r["cod_cargo"]
          $r["cod_puesto"]
          $r["sueldo_basico"]
          $r["compensaciones"]
          $r["primas"]
          $r["bonos"]
          $r["cod_dir_superior"]
          $r["cod_coordinacion"]
          $r["cod_secretaria"]
          $r["cod_direccion"]
          $r["cod_division"]
          $r["cod_departamento"]
          $r["cod_oficina"]
          $r["cod_estado"]
          $r["cod_municipio"]
          $r["cod_parroquia"]
          $r["cod_centro"]
          $r["condicion_actividad
          $r["ano"]
          $r["cod_sector"]
          $r["cod_programa"]
          $r["cod_sub_prog"]
          $r["cod_proyecto"]
          $r["cod_activ_obra"]
          $r["cod_partida"]
          $r["cod_generica"]
          $r["cod_especifica"]
          $r["cod_sub_espec"]
          $r["cod_auxiliar"]
          $r["cod_nivel_i"]
          $r["cod_nivel_ii"]
          $r["cod_ficha"]
 */
function pasar_cargos() {
    $this->layout="ajax";
     $cod_presi = $this->Session->read('SScodpresi');
 	 $cod_entidad = $this->Session->read('SScodentidad');
 	 $cod_tipo_inst = $this->Session->read('SScodtipoinst');
 	 $cod_inst = $this->Session->read('SScodinst');
 	 $cod_dep = $this->Session->read('SScoddep');
 	 $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
         $ANO_EJECUCION = $this->Session->read('ANO_EJECUCION');
         $ANO_FORMULAR  = $this->Session->read('ANO_FORMULAR');



    if(isset($this->data["cnmp05_cfpp97"]["ano"])){
       $ano_a_formular=$this->data["cnmp05_cfpp97"]["ano"];
       if($this->cfpd97->findCount($condicion." and ano=".$ano_a_formular)==0){
       	  $this->cfpd97->execute("DELETE FROM cfpd97 WHERE $condicion;");
       	  $this->cfpd97->execute("select pasar_personal_presupuesto($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$ano_a_formular);");
        //$this->cfpd97->execute("DELETE FROM cnmd03_conexion_transacciones WHERE ".$condicion." and ano!=".$ANO_FORMULAR." ;");
       	 echo "<h3>Datos Procesados Correctamente</h3>";
      }else{
      	echo "<h3>DISCULPE, ESTE PROCESO FUE REALIZADO ANTERIORMENTE</h3>";
      }
    }//fin isset
}//fin pasar_cargos


function pasar_presupuesto_personal_index () {
     $this->layout = "ajax";
     $cod_presi = $this->Session->read('SScodpresi');
 	 $cod_entidad = $this->Session->read('SScodentidad');
 	 $cod_tipo_inst = $this->Session->read('SScodtipoinst');
 	 $cod_inst = $this->Session->read('SScodinst');
 	 $cod_dep = $this->Session->read('SScoddep');
 	 $this->set("cod_dep",$cod_dep);
 	 $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

	 $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
	 $dato = null;
	 foreach($year as $year){
		$dato = $year['cfpd01_formulacion']['ano_formular'];
	 }
    if(!empty($dato)){
         $this->Session->write('ANO_EJECUCION',$this->ano_ejecucion());
         $this->Session->write('ANO_FORMULAR',$dato);
		 $this->set('year', $dato);
	}else{
		$this->set('year', '');
	}
}

function pasar_presupuesto_personal () {
	$this->layout="ajax";
	 $cod_presi = $this->Session->read('SScodpresi');
 	 $cod_entidad = $this->Session->read('SScodentidad');
 	 $cod_tipo_inst = $this->Session->read('SScodtipoinst');
 	 $cod_inst = $this->Session->read('SScodinst');
     $rs=$this->cfpd97->execute("select pasar_presupuesto_personal($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,".date("Y").");");
   // echo strtoupper($rs[0][0]["pasar_presupuesto_personal"]);
    if($rs){
    	echo "<h3>Datos Procesados Correctamente</h3>";
    	echo "<script>fun_msj2('Los Datos Fueron Pasados Correctamente');</script>";
    }else{
    	echo "<h3>Proceso No Ejecutado Correctamente</h3>";
    	echo "<script>fun_msj('Los Datos No Fueron Pasados');</script>";
    }
}





}//fin class
?>