<?php


class ReporteFormulacionController extends AppController{

    var $uses = array( 'cfpd01_formulacion','cfpd97','arrd05','v_cfpd97_reporte2_dep_final','v_cfpd97_reporte2_inst_final','v_cnmd04_ocupacion','v_cfpd97_reporte2_dep_s_final','v_cfpd97_reporte2_inst_s_final','v_cfpd97_reporte2_dep_sp_final','v_cfpd97_reporte2_inst_sp_final','v_cfpd97_reporte2_dep_spsp_final','v_cfpd97_reporte2_inst_spsp_final','v_cfpd97_reporte2_dep_finals1','v_cfpd97_reporte2_inst_finals1',
    					'v_forma_2107_inst','v_forma_2107_dep','v_forma_2114_dep_s','v_forma_2114_dep_sp','v_forma_2114_dep_spsp','v_forma_2114_inst_s','v_forma_2114_inst_sp','v_forma_2114_inst_spsp','v_forma_2126_inst','v_forma_2126_dep','v_forma_2126_instv2','v_forma_2126_depv2','v_forma_2107_dep_vision','v_forma_2107_inst_vision','v_forma_2113_dep_vision','v_forma_2113_inst_vision','v_forma_2113_a_dep_vision','v_forma_2113_a_inst_vision','v_forma_2113_b_dep_vision','v_forma_2113_b_inst_vision',
    					'v_forma_2114_dep_vision','v_forma_2114_inst_vision','v_forma_2114_a_dep_vision','v_forma_2114_a_inst_vision','v_forma_2114_b_dep_vision','v_forma_2114_b_inst_vision','v_forma_2106_dep_vision','v_forma_2106_inst_vision'
	                  );

	var $helpers = array('Html','Ajax','Javascript', 'Sisap', 'Fpdf');


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


function limpia_menu(){
/*
    	 echo'<script>
							 document.getElementById("valida_codigo").innerHTML = "";
							 document.getElementById("valida_codigo").style.display = "none";
							 if(document.getElementById("registro")){document.getElementById("registro").style.visibility = "hidden";}
                             if(document.getElementById("nomina")){document.getElementById("nomina").style.visibility = "hidden";}
                             if(document.getElementById("dependencia")){document.getElementById("dependencia").style.visibility = "hidden";}
                             if(document.getElementById("sistema_general")){document.getElementById("sistema_general").style.visibility = "hidden";}
               </script>';

*/
    }

function forma_2106 ($year=null) {

set_time_limit(0);
//echo $year."   si";

	$sql='';

  if(!$year){

    $this->layout = "ajax";
    $this->limpia_menu();
    $this->set('ir', 'no');

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year = $this->cfpd01_formulacion->ano_formulacion($condicion);
	if(!empty($year)){
		$this->set('year', $year);
	}else{
		$this->set('year', '');
	}
}else{
  	$this->layout = "pdf";
  	$ano = '';
  	$ano = $this->data['cnmp05']['ano'];
  	$consolidado = 2;
  	 if(isset($this->data['cnmp05']['consolidacion'])){
  	         	$consolidado = $this->data['cnmp05']['consolidacion'];
  	 }

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
    $ejercicio = $ano;
	$year = $ejercicio;
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year_dato = $this->cfpd01_formulacion->ano_formulacion($condicion);
	$ano_formular = $year_dato;
	$i  = 0;
	$xi = 0;
	$j  = 0;
	$k  = 0;
	$l  = 0;
	$year = $ano;
	$condicion .= " and ano=".$year;

    if($consolidado==2){
  	     	//$sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst."and cod_dep = ".$cod_dep;
            $sql = $this->SQLCA_consolidado();
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_forma_2106_dep_vision";
            // $modelo="v_cfpd97_reporte2_dep_final";
  	}else if($consolidado==1){
  		    //$sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
            $sql = $this->SQLCA_consolidado($consolidado);
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_forma_2106_inst_vision";
            // $modelo="v_cfpd97_reporte2_inst_final";
  	}
            $DATOS_res = $this->$modelo->findAll($sql);
            $this->set('data', $DATOS_res);
            $this->set('titulo_a', $titulo_a);
            $this->set('modelo', $modelo);
            $this->set('ano_presupuesto', $ano_formular);
            $this->set('ocupacion',$this->v_cnmd04_ocupacion->findAll("cod_nivel_i<=4",null,"cod_nivel_i,cod_nivel_ii ASC"));

  }
$this->set('entidad_federal', $this->Session->read('entidad_federal'));


}//fin funcion forma_2106


function forma_2107 ($year=null) {
set_time_limit(0);
	$sql='';
  if(!$year){

    $this->layout = "ajax";
    $this->limpia_menu();
    $this->set('ir', 'no');

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year = $this->cfpd01_formulacion->ano_formulacion($condicion);
	if(!empty($year)){
		$this->set('year', $year);
	}else{
		$this->set('year', '');
	}
}else{
  	$this->layout = "pdf";
  	$ano = '';
  	$ano = $this->data['cnmp05']['ano'];
  	$consolidado = 2;
  	 if(isset($this->data['cnmp05']['consolidacion'])){
  	         	$consolidado = $this->data['cnmp05']['consolidacion'];
  	 }

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
    $ejercicio = $ano;
	$year = $ejercicio;
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year_dato = $this->cfpd01_formulacion->ano_formulacion($condicion);
	$ano_formular = $year_dato;
	$year = $ano;
	$condicion .= " and ano=".$year;

    if($consolidado==2){
  	     	//$sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst."and cod_dep = ".$cod_dep;
            $sql = $this->SQLCA_consolidado();
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_forma_2107_dep_vision";
            // $modelo="v_forma_2107_dep";
  	}else if($consolidado==1){
  		    //$sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
            $sql = $this->SQLCA_consolidado($consolidado);
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_forma_2107_inst_vision";
            // $modelo="v_forma_2107_inst";
  	}
            $DATOS_res = $this->$modelo->findAll($sql,null,'escala ASC');
            $this->set('data', $DATOS_res);
            $this->set('titulo_a', $titulo_a);
            $this->set('modelo', $modelo);
            $this->set('ano_presupuesto', $ano_formular);

  }
$this->set('entidad_federal', $this->Session->read('entidad_federal'));
}//fin funcion forma_2107



function forma_2113 ($year=null) {

set_time_limit(0);
//echo $year."   si";

	$sql='';

  if(!$year){

    $this->layout = "ajax";
    $this->limpia_menu();
    $this->set('ir', 'no');

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year = $this->cfpd01_formulacion->ano_formulacion($condicion);
	if(!empty($year)){
		$this->set('year', $year);
	}else{
		$this->set('year', '');
	}
	if($cod_dep!=1){
		$condicion.=" and cod_dep=$cod_dep";
	}

    $rs=$this->v_cfpd97_reporte2_inst_spsp_final->execute("SELECT DISTINCT cod_sector,denominacion FROM cfpd02_sector WHERE ". $condicion." ORDER BY cod_sector ASC");
		    foreach($rs as $l){
				$v[]=$l[0]["cod_sector"];
				$d[]=$l[0]["denominacion"];
			}
			$lista = array_combine($v, $d);
			$this->concatena($lista, 'vector');
}else{
  	$this->layout = "pdf";
  	$ano = '';
  	$ano = $this->data['cnmp05']['ano'];
  	$consolidado = 2;
  	 if(isset($this->data['cnmp05']['consolidacion'])){
  	         	$consolidado = $this->data['cnmp05']['consolidacion'];
  	 }

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
    $ejercicio = $ano;
	$year = $ejercicio;
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year_dato = $this->cfpd01_formulacion->ano_formulacion($condicion);
	$ano_formular = $year_dato;
	$i  = 0;
	$xi = 0;
	$j  = 0;
	$k  = 0;
	$l  = 0;
	$year = $ano;
	$condicion .= " and ano=".$year;

    if($consolidado==2){
  	     	//$sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst."and cod_dep = ".$cod_dep;
            $sql = $this->SQLCA_consolidado();
            $titulo_a = $this->Session->read('dependencia');
			$modelo="v_forma_2113_dep_vision";
            // $modelo="v_cfpd97_reporte2_dep_s_final";
  	}else if($consolidado==1){
  		    //$sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
            $sql = $this->SQLCA_consolidado($consolidado);
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_forma_2113_inst_vision";
            // $modelo="v_cfpd97_reporte2_inst_s_final";
  	}
  	if(isset($this->data['reporte']['tipo']) && $this->data['reporte']['tipo']==2){
         $cod_sector = isset($this->data['reporte']['cod_sector']) && !empty($this->data['reporte']['cod_sector']) ? " cod_sector=".$this->data['reporte']['cod_sector']:"1=1";
  	     $sql .= " and ".$cod_sector;
  	}

  	        $DATOS_sectores= $this->$modelo->execute("select cod_sector,deno_sector FROM $modelo WHERE $sql group by cod_sector,deno_sector order by cod_sector ASC");
            $DATOS_res = $this->$modelo->findAll($sql);
            $this->set('data_sectores', $DATOS_sectores);
            $this->set('data', $DATOS_res);
            $this->set('titulo_a', $titulo_a);
            $this->set('modelo', $modelo);
            $this->set('ano_presupuesto', $ano_formular);
            $this->set('ocupacion',$this->v_cnmd04_ocupacion->findAll("cod_nivel_i<=4",null,"cod_nivel_i,cod_nivel_ii ASC"));

  }

$this->set('entidad_federal', $this->Session->read('entidad_federal'));


}//fin funcion forma_2113

function forma_2113_a ($year=null) {

set_time_limit(0);
//echo $year."   si";

	$sql='';

  if(!$year){

    $this->layout = "ajax";
    $this->limpia_menu();
    $this->set('ir', 'no');

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year = $this->cfpd01_formulacion->ano_formulacion($condicion);
	if(!empty($year)){
		$this->set('year', $year);
	}else{
		$this->set('year', '');
	}
	if($cod_dep!=1){
		$condicion.=" and cod_dep=$cod_dep";
	}

    $rs=$this->v_cfpd97_reporte2_inst_spsp_final->execute("SELECT DISTINCT cod_sector,denominacion FROM cfpd02_sector WHERE ". $condicion." ORDER BY cod_sector ASC");
		    foreach($rs as $l){
				$v[]=$l[0]["cod_sector"];
				$d[]=$l[0]["denominacion"];
			}
			$lista = array_combine($v, $d);
			$this->concatena($lista, 'vector');
}else{
  	$this->layout = "pdf";
  	$ano = '';
  	$ano = $this->data['cnmp05']['ano'];
  	$consolidado = 2;
  	 if(isset($this->data['cnmp05']['consolidacion'])){
  	         	$consolidado = $this->data['cnmp05']['consolidacion'];
  	 }

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
    $ejercicio = $ano;
	$year = $ejercicio;
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year_dato = $this->cfpd01_formulacion->ano_formulacion($condicion);
	$ano_formular = $year_dato;
	$i  = 0;
	$xi = 0;
	$j  = 0;
	$k  = 0;
	$l  = 0;
	$year = $ano;
	$condicion .= " and ano=".$year;

    if($consolidado==2){
  	     	//$sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst."and cod_dep = ".$cod_dep;
            $sql = $this->SQLCA_consolidado();
            $titulo_a = $this->Session->read('dependencia');
			$modelo="v_forma_2113_a_dep_vision";
            // $modelo="v_cfpd97_reporte2_dep_sp_final";
  	}else if($consolidado==1){
  		    //$sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
            $sql = $this->SQLCA_consolidado($consolidado);
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_forma_2113_a_inst_vision";
            // $modelo="v_cfpd97_reporte2_inst_sp_final";
  	}
  	if(isset($this->data['reporte']['tipo']) && $this->data['reporte']['tipo']==2){
         $cod_sector = isset($this->data['reporte']['cod_sector']) && !empty($this->data['reporte']['cod_sector']) ? " cod_sector=".$this->data['reporte']['cod_sector']:"1=1";
         $cod_programa = isset($this->data['reporte']['cod_programa']) && !empty($this->data['reporte']['cod_programa']) ? " cod_programa=".$this->data['reporte']['cod_programa']:"2=2";
  	     $sql .= " and ".$cod_sector ." and ".$cod_programa;
  	}

  	        $DATOS_sectores= $this->$modelo->execute("select cod_sector,cod_programa,deno_sector,deno_programa FROM $modelo WHERE $sql group by cod_sector,cod_programa,deno_sector,deno_programa order by cod_sector,cod_programa ASC");
            $DATOS_res = $this->$modelo->findAll($sql);
            $this->set('data_sectores', $DATOS_sectores);
            $this->set('data', $DATOS_res);
            $this->set('titulo_a', $titulo_a);
            $this->set('modelo', $modelo);
            $this->set('ano_presupuesto', $ano_formular);
            $this->set('ocupacion',$this->v_cnmd04_ocupacion->findAll("cod_nivel_i<=4",null,"cod_nivel_i,cod_nivel_ii ASC"));

  }

$this->set('entidad_federal', $this->Session->read('entidad_federal'));


}//fin funcion forma_2113a



function forma_2113_b ($year=null) {

set_time_limit(0);
//echo $year."   si";

	$sql='';

  if(!$year){

    $this->layout = "ajax";
    $this->limpia_menu();
    $this->set('ir', 'no');

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year = $this->cfpd01_formulacion->ano_formulacion($condicion);
	if(!empty($year)){
		$this->set('year', $year);
	}else{
		$this->set('year', '');
	}
	if($cod_dep!=1){
		$condicion.=" and cod_dep=$cod_dep";
	}

    $rs=$this->v_cfpd97_reporte2_inst_spsp_final->execute("SELECT DISTINCT cod_sector,denominacion FROM cfpd02_sector WHERE ". $condicion." ORDER BY cod_sector ASC");
		    foreach($rs as $l){
				$v[]=$l[0]["cod_sector"];
				$d[]=$l[0]["denominacion"];
			}
			$lista = array_combine($v, $d);
			$this->concatena($lista, 'vector');
}else{
  	$this->layout = "pdf";
  	$ano = '';
  	$ano = $this->data['cnmp05']['ano'];
  	$consolidado = 2;
  	 if(isset($this->data['cnmp05']['consolidacion'])){
  	         	$consolidado = $this->data['cnmp05']['consolidacion'];
  	 }

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
    $ejercicio = $ano;
	$year = $ejercicio;
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year_dato = $this->cfpd01_formulacion->ano_formulacion($condicion);
	$ano_formular = $year_dato;
	$i  = 0;
	$xi = 0;
	$j  = 0;
	$k  = 0;
	$l  = 0;
	$year = $ano;
	$condicion .= " and ano=".$year;

    if($consolidado==2){
  	     	//$sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst."and cod_dep = ".$cod_dep;
            $sql = $this->SQLCA_consolidado();
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_forma_2113_b_dep_vision";
            // $modelo="v_cfpd97_reporte2_dep_spsp_final";
  	}else if($consolidado==1){
  		    //$sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
            $sql = $this->SQLCA_consolidado($consolidado);
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_forma_2113_b_inst_vision";
            // $modelo="v_cfpd97_reporte2_inst_spsp_final";
  	}
  	if(isset($this->data['reporte']['tipo']) && $this->data['reporte']['tipo']==2){
         $cod_sector = isset($this->data['reporte']['cod_sector']) && !empty($this->data['reporte']['cod_sector']) ? " cod_sector=".$this->data['reporte']['cod_sector']:"1=1";
         $cod_programa = isset($this->data['reporte']['cod_programa']) && !empty($this->data['reporte']['cod_programa']) ? " cod_programa=".$this->data['reporte']['cod_programa']:"2=2";
         $cod_sub_prog = isset($this->data['reporte']['cod_subprograma']) && !empty($this->data['reporte']['cod_subprograma']) ? " cod_sub_prog=".$this->data['reporte']['cod_subprograma']:"3=3";
  	     $sql .= " and ".$cod_sector ." and ".$cod_programa." and ".$cod_sub_prog;
  	}

  	        $DATOS_sectores= $this->$modelo->execute("select cod_sector,cod_programa,cod_sub_prog,deno_sector,deno_programa,deno_sub_prog FROM $modelo WHERE $sql group by cod_sector,cod_programa,cod_sub_prog,deno_sector,deno_programa,deno_sub_prog order by cod_sector,cod_programa,cod_sub_prog ASC");
            $DATOS_res = $this->$modelo->findAll($sql);
            $this->set('data_sectores', $DATOS_sectores);
            $this->set('data', $DATOS_res);
            $this->set('titulo_a', $titulo_a);
            $this->set('modelo', $modelo);
            $this->set('ano_presupuesto', $ano_formular);
            $this->set('ocupacion',$this->v_cnmd04_ocupacion->findAll("cod_nivel_i<=4",null,"cod_nivel_i,cod_nivel_ii ASC"));

  }

$this->set('entidad_federal', $this->Session->read('entidad_federal'));


}//fin funcion forma_2113b



function forma_2131 ($year=null) {

set_time_limit(0);
//echo $year."   si";

	$sql='';

  if(!$year){

    $this->layout = "ajax";
    $this->limpia_menu();
    $this->set('ir', 'no');

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year = $this->cfpd01_formulacion->ano_formulacion($condicion);
	if(!empty($year)){
		$this->set('year', $year);
	}else{
		$this->set('year', '');
	}
}else{
  	$this->layout = "pdf";
  	$ano = '';
  	$ano = $this->data['cnmp05']['ano'];
  	$consolidado = 2;
  	 if(isset($this->data['cnmp05']['consolidacion'])){
  	         	$consolidado = $this->data['cnmp05']['consolidacion'];
  	 }

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
    $ejercicio = $ano;
	$year = $ejercicio;
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year_dato = $this->cfpd01_formulacion->ano_formulacion($condicion);
	$ano_formular = $year_dato;
	$i  = 0;
	$xi = 0;
	$j  = 0;
	$k  = 0;
	$l  = 0;
	$year = $ano;
	$condicion .= " and ano=".$year;

    if($consolidado==2){
  	     	//$sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst."and cod_dep = ".$cod_dep;
            $sql = $this->SQLCA_consolidado();
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_cfpd97_reporte2_dep_finals1";
  	}else if($consolidado==1){
  		    //$sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
            $sql = $this->SQLCA_consolidado($consolidado);
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_cfpd97_reporte2_inst_finals1";
  	}
            $DATOS_res = $this->$modelo->findAll($sql);
            $this->set('data', $DATOS_res);
            $this->set('titulo_a', $titulo_a);
            $this->set('modelo', $modelo);
            $this->set('ano_presupuesto', $ano_formular);
            $this->set('ocupacion',$this->v_cnmd04_ocupacion->findAll("cod_nivel_i<=4",null,"cod_nivel_i,cod_nivel_ii ASC"));

  }
$this->set('entidad_federal', $this->Session->read('entidad_federal'));


}//fin funcion forma_2131

function forma_2114 ($year=null) {
set_time_limit(0);
	$sql='';
  if(!$year){

    $this->layout = "ajax";
    $this->limpia_menu();
    $this->set('ir', 'no');

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year = $this->cfpd01_formulacion->ano_formulacion($condicion);
	if(!empty($year)){
		$this->set('year', $year);
	}else{
		$this->set('year', '');
	}

	        $rs=$this->v_cfpd97_reporte2_inst_spsp_final->execute("SELECT DISTINCT cod_sector,denominacion FROM cfpd02_sector WHERE ". $condicion." ORDER BY cod_sector ASC");
		    foreach($rs as $l){
				$v[]=$l[0]["cod_sector"];
				$d[]=$l[0]["denominacion"];
			}
			$lista = array_combine($v, $d);
			$this->concatena($lista, 'vector');
}else{
  	$this->layout = "pdf";
  	$ano = '';
  	$ano = $this->data['cnmp05']['ano'];
  	$consolidado = 2;
  	 if(isset($this->data['cnmp05']['consolidacion'])){
  	         	$consolidado = $this->data['cnmp05']['consolidacion'];
  	 }

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
    $ejercicio = $ano;
	$year = $ejercicio;
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year_dato = $this->cfpd01_formulacion->ano_formulacion($condicion);
	$ano_formular = $year_dato;
	$year = $ano;
	$condicion .= " and ano=".$year;

    if($consolidado==2){
  	     	//$sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst."and cod_dep = ".$cod_dep;
            $sql = $this->SQLCA_consolidado();
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_forma_2114_dep_vision";
            // $modelo="v_forma_2114_dep_s";
  	}else if($consolidado==1){
  		    //$sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
            $sql = $this->SQLCA_consolidado($consolidado);
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_forma_2114_inst_vision";
            // $modelo="v_forma_2114_inst_s";
  	}
  	if(isset($this->data['reporte']['tipo']) && $this->data['reporte']['tipo']==2){
         $cod_sector = isset($this->data['reporte']['cod_sector']) && !empty($this->data['reporte']['cod_sector']) ? " cod_sector=".$this->data['reporte']['cod_sector']:"1=1";
  	     $sql .= " and ".$cod_sector;
  	}
  	        $DATOS_sectores= $this->$modelo->execute("select cod_sector,deno_sector FROM $modelo WHERE $sql group by cod_sector,deno_sector order by cod_sector ASC");
            $this->set('data_sectores', $DATOS_sectores);
            $DATOS_res = $this->$modelo->findAll($sql,null,'escala ASC');
            $this->set('data', $DATOS_res);
            $this->set('titulo_a', $titulo_a);
            $this->set('modelo', $modelo);
            $this->set('ano_presupuesto', $ano_formular);

  }
$this->set('entidad_federal', $this->Session->read('entidad_federal'));
}//fin funcion forma_2114



function forma_2114_a ($year=null) {
set_time_limit(0);
	$sql='';
  if(!$year){

    $this->layout = "ajax";
    $this->limpia_menu();
    $this->set('ir', 'no');

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year = $this->cfpd01_formulacion->ano_formulacion($condicion);
	if(!empty($year)){
		$this->set('year', $year);
	}else{
		$this->set('year', '');
	}

	        $rs=$this->v_cfpd97_reporte2_inst_spsp_final->execute("SELECT DISTINCT cod_sector,denominacion FROM cfpd02_sector WHERE ". $condicion." ORDER BY cod_sector ASC");
		    foreach($rs as $l){
				$v[]=$l[0]["cod_sector"];
				$d[]=$l[0]["denominacion"];
			}
			$lista = array_combine($v, $d);
			$this->concatena($lista, 'vector');
}else{
  	$this->layout = "pdf";
  	$ano = '';
  	$ano = $this->data['cnmp05']['ano'];
  	$consolidado = 2;
  	 if(isset($this->data['cnmp05']['consolidacion'])){
  	         	$consolidado = $this->data['cnmp05']['consolidacion'];
  	 }

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
    $ejercicio = $ano;
	$year = $ejercicio;
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year_dato = $this->cfpd01_formulacion->ano_formulacion($condicion);
	$ano_formular = $year_dato;
	$year = $ano;
	$condicion .= " and ano=".$year;

    if($consolidado==2){
  	     	//$sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst."and cod_dep = ".$cod_dep;
            $sql = $this->SQLCA_consolidado();
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_forma_2114_a_dep_vision";
            // $modelo="v_forma_2114_dep_sp";
  	}else if($consolidado==1){
  		    //$sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
            $sql = $this->SQLCA_consolidado($consolidado);
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_forma_2114_a_inst_vision";
            // $modelo="v_forma_2114_inst_sp";
  	}
  	if(isset($this->data['reporte']['tipo']) && $this->data['reporte']['tipo']==2){
         $cod_sector = isset($this->data['reporte']['cod_sector']) && !empty($this->data['reporte']['cod_sector']) ? " cod_sector=".$this->data['reporte']['cod_sector']:"1=1";
         $cod_programa = isset($this->data['reporte']['cod_programa']) && !empty($this->data['reporte']['cod_programa']) ? " cod_programa=".$this->data['reporte']['cod_programa']:"2=2";
  	     $sql .= " and ".$cod_sector ." and ".$cod_programa;
  	}
  	        $DATOS_sectores= $this->$modelo->execute("select cod_sector,cod_programa,deno_sector,deno_programa FROM $modelo WHERE $sql group by cod_sector,cod_programa,deno_sector,deno_programa order by cod_sector,cod_programa ASC");
            $this->set('data_sectores', $DATOS_sectores);
            $DATOS_res = $this->$modelo->findAll($sql,null,'escala ASC');
            $this->set('data', $DATOS_res);
            $this->set('titulo_a', $titulo_a);
            $this->set('modelo', $modelo);
            $this->set('ano_presupuesto', $ano_formular);

  }
$this->set('entidad_federal', $this->Session->read('entidad_federal'));
}//fin funcion forma_2114_a



function forma_2114_b ($year=null) {
set_time_limit(0);
	$sql='';
  if(!$year){

    $this->layout = "ajax";
    $this->limpia_menu();
    $this->set('ir', 'no');

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year = $this->cfpd01_formulacion->ano_formulacion($condicion);
	if(!empty($year)){
		$this->set('year', $year);
	}else{
		$this->set('year', '');
	}

	        $rs=$this->v_cfpd97_reporte2_inst_spsp_final->execute("SELECT DISTINCT cod_sector,denominacion FROM cfpd02_sector WHERE ". $condicion." ORDER BY cod_sector ASC");
		    foreach($rs as $l){
				$v[]=$l[0]["cod_sector"];
				$d[]=$l[0]["denominacion"];
			}
			$lista = array_combine($v, $d);
			$this->concatena($lista, 'vector');
}else{
  	$this->layout = "pdf";
  	$ano = '';
  	$ano = $this->data['cnmp05']['ano'];
  	$consolidado = 2;
  	 if(isset($this->data['cnmp05']['consolidacion'])){
  	         	$consolidado = $this->data['cnmp05']['consolidacion'];
  	 }

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
    $ejercicio = $ano;
	$year = $ejercicio;
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year_dato = $this->cfpd01_formulacion->ano_formulacion($condicion);
	$ano_formular = $year_dato;
	$year = $ano;
	$condicion .= " and ano=".$year;

    if($consolidado==2){
  	     	//$sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst."and cod_dep = ".$cod_dep;
            $sql = $this->SQLCA_consolidado();
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_forma_2114_b_dep_vision";
            // $modelo="v_forma_2114_dep_spsp";
  	}else if($consolidado==1){
  		    //$sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
            $sql = $this->SQLCA_consolidado($consolidado);
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_forma_2114_b_inst_vision";
            // $modelo="v_forma_2114_inst_spsp";
  	}
  	if(isset($this->data['reporte']['tipo']) && $this->data['reporte']['tipo']==2){
         $cod_sector = isset($this->data['reporte']['cod_sector']) && !empty($this->data['reporte']['cod_sector']) ? " cod_sector=".$this->data['reporte']['cod_sector']:"1=1";
         $cod_programa = isset($this->data['reporte']['cod_programa']) && !empty($this->data['reporte']['cod_programa']) ? " cod_programa=".$this->data['reporte']['cod_programa']:"2=2";
         $cod_sub_prog = isset($this->data['reporte']['cod_subprograma']) && !empty($this->data['reporte']['cod_subprograma']) ? " cod_sub_prog=".$this->data['reporte']['cod_subprograma']:"3=3";
  	     $sql .= " and ".$cod_sector ." and ".$cod_programa." and ".$cod_sub_prog;
  	}
  	        $DATOS_sectores= $this->$modelo->execute("select cod_sector,cod_programa,cod_sub_prog,deno_sector,deno_programa,deno_sub_prog FROM $modelo WHERE $sql group by cod_sector,cod_programa,cod_sub_prog,deno_sector,deno_programa,deno_sub_prog order by cod_sector,cod_programa,cod_sub_prog ASC");
            $this->set('data_sectores', $DATOS_sectores);
            $DATOS_res = $this->$modelo->findAll($sql,null,'escala ASC');
            $this->set('data', $DATOS_res);
            $this->set('titulo_a', $titulo_a);
            $this->set('modelo', $modelo);
            $this->set('ano_presupuesto', $ano_formular);

  }
$this->set('entidad_federal', $this->Session->read('entidad_federal'));
}//fin funcion forma_2114_b



function select_reporte_clasificacion ($select=null,$var=null) {
   $this->layout="ajax";
   if($select!=null && $var!=null){
		if($this->verifica_SS(5)==1){
    	    $cond=$this->SQLCA_report(1);
    	}else{
    		$cond=$this->SQLCA_report();
    	}
    	$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
	switch($select){
		case 'programa':

			$this->set('SELECT','subprograma');
			$this->set('codigo','programa');
			$this->set('seleccion','');
			$this->set('n',2);
			$ano=$this->cfpd01_formulacion->ano_formulacion($condicion);
			$this->Session->write('ano',$ano);
			$this->Session->write('sec',$var);
			$cond .=" and ano=".$ano." and cod_sector=".$var;
			//$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_programa ASC', null, '{n}.v_cfpd05_denominaciones.cod_programa', '{n}.v_cfpd05_denominaciones.deno_programa');
		    //$this->concatena($lista, 'vector');
		    $rs=$this->cfpd01_formulacion->execute("SELECT DISTINCT cod_programa,denominacion FROM cfpd02_programa WHERE ". $cond." ORDER BY cod_programa ASC");
 		   foreach($rs as $l){
				$v[]=$l[0]["cod_programa"];
				$d[]=$l[0]["denominacion"];
			}
			$lista = array_combine($v, $d);
			$this->concatena($lista, 'vector');
		break;
		case 'subprograma':
		    //$this->set('no','no');
			$this->set('SELECT','proyecto');
			$this->set('codigo','subprograma');
			$this->set('seleccion','');
			$this->set('n',3);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$this->Session->write('prog',$var);
			$cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$var;
			$rs=$this->cfpd01_formulacion->execute("SELECT DISTINCT cod_sub_prog,denominacion FROM cfpd02_sub_prog WHERE ". $cond." ORDER BY cod_sub_prog ASC");
		    foreach($rs as $l){
				$v[]=$l[0]["cod_sub_prog"];
				$d[]=$l[0]["denominacion"];
			}
			$lista = array_combine($v, $d);
			$this->concatena($lista, 'vector');
		break;
		case 'proyecto':
		    $this->set('no','no');
			$this->set('SELECT','proyecto');
			$this->set('codigo','proyecto');
			$this->set('seleccion','');
			$this->set('n',4);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$this->Session->write('sprog',$var);
			$cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=$prog and cod_sub_prog=".$var;
			$rs=$this->cfpd01_formulacion->execute("SELECT DISTINCT cod_proyecto,denominacion FROM cfpd02_proyecto WHERE ". $cond." ORDER BY cod_proyecto ASC");
		    foreach($rs as $l){
				$v[]=$l[0]["cod_proyecto"];
				$d[]=$l[0]["denominacion"];
			}
			$lista = array_combine($v, $d);
			$this->concatena($lista, 'vector');
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
}//fin funcion select_reporte_clasificacion


function forma_2126 ($year=null) {
set_time_limit(0);
	$sql='';
  if(!$year){

    $this->layout = "ajax";
    $this->limpia_menu();
    $this->set('ir', 'no');

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year = $this->cfpd01_formulacion->ano_formulacion($condicion);
	if(!empty($year)){
		$this->set('year', $year);
	}else{
		$this->set('year', '');
	}

	        $rs=$this->v_cfpd97_reporte2_inst_spsp_final->execute("SELECT DISTINCT cod_sector,denominacion FROM cfpd02_sector WHERE ". $condicion." ORDER BY cod_sector ASC");
		    foreach($rs as $l){
				$v[]=$l[0]["cod_sector"];
				$d[]=$l[0]["denominacion"];
			}
			$lista = array_combine($v, $d);
			$this->concatena($lista, 'vector');
}else{
  	$this->layout = "pdf";
  	$ano = '';
  	$ano = $this->data['cnmp05']['ano'];
  	$consolidado = 2;
  	 if(isset($this->data['cnmp05']['consolidacion'])){
  	         	$consolidado = $this->data['cnmp05']['consolidacion'];
  	 }
    $this->set('iniciorep',date('his'));
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
    $ejercicio = $ano;
	$year = $ejercicio;
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year_dato = $this->cfpd01_formulacion->ano_formulacion($condicion);
	$ano_formular = $year_dato;
	$year = $ano;
	$condicion .= " and ano=".$year;
    if(isset($this->data['reporte']['tipo']) && $this->data['reporte']['tipo']==2){
         $cod_sector = isset($this->data['reporte']['cod_sector']) && !empty($this->data['reporte']['cod_sector']) ? " cod_sector=".$this->data['reporte']['cod_sector']:"1=1";
         $cod_programa = isset($this->data['reporte']['cod_programa']) && !empty($this->data['reporte']['cod_programa']) ? " cod_programa=".$this->data['reporte']['cod_programa']:"2=2";
         $cod_sub_prog = isset($this->data['reporte']['cod_subprograma']) && !empty($this->data['reporte']['cod_subprograma']) ? " cod_sub_prog=".$this->data['reporte']['cod_subprograma']:"3=3";
         $cod_proyecto = isset($this->data['reporte']['cod_proyecto']) && !empty($this->data['reporte']['cod_proyecto']) ? " cod_proyecto=".$this->data['reporte']['cod_proyecto']:"4=4";
  	     $sql = "".$cod_sector ." and ".$cod_programa." and ".$cod_sub_prog." and $cod_proyecto";
  	}else{
  		$sql = "1=1 ";
  	}
    if($consolidado==2){
  	     	//$sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst."and cod_dep = ".$cod_dep;
            $sql .= " and ".$this->SQLCA_consolidado();
            $sql .= " and ano=$year";
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_forma_2126_depv2";
            //$SQL_CONSULTA = "SELECT * FROM v_forma_2126_depv2 WHERE $sql  ORDER BY  cod_dep,ano,cod_sector,cod_programa,cod_proyecto,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar; ";
  	}else if($consolidado==1){
  		    //$sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
            $sql .= " and ".$this->SQLCA_consolidado($consolidado);
            $sql .= " and ano=$year";
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_forma_2126_instv2";
            /*$SQL_CONSULTA = "SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, deno_sector, deno_programa, deno_sub_prog, denominacion, sum(activ51) AS activ51, sum(activ52) AS activ52, sum(activ53) AS activ53, sum(activ54) AS activ54, sum(activ55) AS activ55, sum(activ56) AS activ56, sum(activ57) AS activ57, sum(activ58) AS activ58, sum(activ59) AS activ59, sum(activ60) AS activ60, sum(activ61) AS activ61, sum(activ62) AS activ62, sum(activ63) AS activ63, sum(activ64) AS activ64, sum(activ65) AS activ65, sum(activ66) AS activ66, sum(activ67) AS activ67, sum(activ68) AS activ68, sum(activ69) AS activ69, sum(activ70) AS activ70
                                    FROM v_forma_2126_depv2 l WHERE $sql
                                    GROUP BY cod_presi, cod_entidad, cod_tipo_inst, cod_inst, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, deno_sector, deno_programa, deno_sub_prog, denominacion ORDER BY ano,cod_sector,cod_programa,cod_proyecto,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar;";
  	         /**/
  	}
            //pr($this->data);
            //echo $sql."<br>".$SQL_CONSULTA;
  	        //$DATOS_sectores= $this->$modelo->execute("select cod_sector,cod_programa,cod_sub_prog,cod_proyecto,deno_sector,deno_programa,deno_sub_prog,deno_proyecto FROM $modelo WHERE $sql group by cod_sector,cod_programa,cod_sub_prog,cod_proyecto,deno_sector,deno_programa,deno_sub_prog,deno_proyecto order by cod_sector,cod_programa,cod_sub_prog,cod_proyecto ASC");
  	        $DATOS_sectores= $this->$modelo->execute("select cod_sector,cod_programa,cod_sub_prog,cod_proyecto,deno_sector,deno_programa,deno_sub_prog,deno_proyecto,deno_activ51,deno_activ52,deno_activ53,deno_activ54,deno_activ55,deno_activ56,deno_activ57,deno_activ58,deno_activ59,deno_activ60,deno_activ61,deno_activ62,deno_activ63,deno_activ64,deno_activ65,deno_activ66,deno_activ67,deno_activ68,deno_activ69,deno_activ70 FROM $modelo WHERE $sql group by cod_sector,cod_programa,cod_sub_prog,cod_proyecto,deno_sector,deno_programa,deno_sub_prog,deno_proyecto,deno_activ51,deno_activ52,deno_activ53,deno_activ54,deno_activ55,deno_activ56,deno_activ57,deno_activ58,deno_activ59,deno_activ60,deno_activ61,deno_activ62,deno_activ63,deno_activ64,deno_activ65,deno_activ66,deno_activ67,deno_activ68,deno_activ69,deno_activ70 order by cod_sector,cod_programa,cod_sub_prog,cod_proyecto ASC");
            $this->set('data_sectores', $DATOS_sectores);
            //pr($DATOS_sectores);
            $DATOS_res = $this->$modelo->findAll($sql,null,'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
            $this->set('data', $DATOS_res);
            //$DATOS_res = $this->$modelo->execute($SQL_CONSULTA);
            $this->set('data', $DATOS_res);
            $this->set('titulo_a', $titulo_a);
            $this->set('modelo', $modelo);
            $this->set('ano_presupuesto', $ano_formular);

  }
$this->set('entidad_federal', $this->Session->read('entidad_federal'));
}//fin funcion forma_2126



function forma_2106_v2 ($year=null) {
set_time_limit(0);

  $sql='';
  if(!$year){
    $this->layout = "ajax";
    $this->limpia_menu();
    $this->set('ir', 'no');
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year = $this->cfpd01_formulacion->ano_formulacion($condicion);
	if(!empty($year)){
		$this->set('year', $year);
	}else{
		$this->set('year', '');
	}

}else{

  	$this->layout = "pdf";
  	$ano = '';
  	$ano = $this->data['cnmp05']['ano'];
  	$consolidado = 2;
  	 if(isset($this->data['cnmp05']['consolidacion'])){
  	         	$consolidado = $this->data['cnmp05']['consolidacion'];
  	 }
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
    $ejercicio = $ano;
	$year = $ejercicio;
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year_dato = $this->cfpd01_formulacion->ano_formulacion($condicion);
	$ano_formular = $year_dato;
	$i  = 0;
	$xi = 0;
	$j  = 0;
	$k  = 0;
	$l  = 0;
	$year = $ano;
	$condicion .= " and ano=".$year;

    if($consolidado==2){
  	     	//$sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst."and cod_dep = ".$cod_dep;
            $sql = $this->SQLCA_consolidado();
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_forma_2106_dep_vision";
            // $modelo="v_cfpd97_reporte2_dep_final";
  	}else if($consolidado==1){
  		    //$sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
            $sql = $this->SQLCA_consolidado($consolidado);
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_forma_2106_inst_vision";
            // $modelo="v_cfpd97_reporte2_inst_final";
  	}
            $DATOS_res = $this->$modelo->findAll($sql);
            $this->set('data', $DATOS_res);
            $this->set('titulo_a', $titulo_a);
            $this->set('modelo', $modelo);
            $this->set('ano_presupuesto', $ano_formular);
            $this->set('ocupacion',$this->v_cnmd04_ocupacion->findAll("cod_nivel_i<=4",null,"cod_nivel_i,cod_nivel_ii ASC"));

  }
$this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin funcion forma_2106_v2

function forma_2126_gob ($year=null) {
set_time_limit(0);
	$sql='';
  if(!$year){

    $this->layout = "ajax";
    $this->limpia_menu();
    $this->set('ir', 'no');

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year = $this->cfpd01_formulacion->ano_formulacion($condicion);
	if(!empty($year)){
		$this->set('year', $year);
	}else{
		$this->set('year', '');
	}

	        $rs=$this->v_cfpd97_reporte2_inst_spsp_final->execute("SELECT DISTINCT cod_sector,denominacion FROM cfpd02_sector WHERE ". $condicion." ORDER BY cod_sector ASC");
		    foreach($rs as $l){
				$v[]=$l[0]["cod_sector"];
				$d[]=$l[0]["denominacion"];
			}
			$lista = array_combine($v, $d);
			$this->concatena($lista, 'vector');
}else{
  	$this->layout = "pdf";
  	$ano = '';
  	$ano = $this->data['cnmp05']['ano'];
  	$consolidado = 2;
  	 if(isset($this->data['cnmp05']['consolidacion'])){
  	         	$consolidado = $this->data['cnmp05']['consolidacion'];
  	 }
    $this->set('iniciorep',date('his'));
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
    $ejercicio = $ano;
	$year = $ejercicio;
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
    $year_dato = $this->cfpd01_formulacion->ano_formulacion($condicion);
	$ano_formular = $year_dato;
	$year = $ano;
	$condicion .= " and ano=".$year;
    if(isset($this->data['reporte']['tipo']) && $this->data['reporte']['tipo']==2){
         $cod_sector = isset($this->data['reporte']['cod_sector']) && !empty($this->data['reporte']['cod_sector']) ? " cod_sector=".$this->data['reporte']['cod_sector']:"1=1";
         $cod_programa = isset($this->data['reporte']['cod_programa']) && !empty($this->data['reporte']['cod_programa']) ? " cod_programa=".$this->data['reporte']['cod_programa']:"2=2";
         $cod_sub_prog = isset($this->data['reporte']['cod_subprograma']) && !empty($this->data['reporte']['cod_subprograma']) ? " cod_sub_prog=".$this->data['reporte']['cod_subprograma']:"3=3";
         $cod_proyecto = isset($this->data['reporte']['cod_proyecto']) && !empty($this->data['reporte']['cod_proyecto']) ? " cod_proyecto=".$this->data['reporte']['cod_proyecto']:"4=4";
  	     $sql = "".$cod_sector ." and ".$cod_programa." and ".$cod_sub_prog." and $cod_proyecto";
  	}else{
  		$sql = "1=1 ";
  	}
    if($consolidado==2){
  	     	//$sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst."and cod_dep = ".$cod_dep;
            $sql .= " and ".$this->SQLCA_consolidado();
            $sql .= " and ano=$year";
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_forma_2126_depv2";

  	}else if($consolidado==1){
  		    //$sql = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
            $sql .= " and ".$this->SQLCA_consolidado($consolidado);
            $sql .= " and ano=$year";
            $titulo_a = $this->Session->read('dependencia');
            $modelo="v_forma_2126_instv2";

  	}

  	        $DATOS_sectores= $this->$modelo->execute("select cod_sector,cod_programa,cod_sub_prog,cod_proyecto,deno_sector,deno_programa,deno_sub_prog,deno_proyecto,deno_activ51,deno_activ52,deno_activ53,deno_activ54,deno_activ55,deno_activ56,deno_activ57,deno_activ58,deno_activ59,deno_activ60,deno_activ61,deno_activ62,deno_activ63,deno_activ64,deno_activ65,deno_activ66,deno_activ67,deno_activ68,deno_activ69,deno_activ70 FROM $modelo WHERE $sql group by cod_sector,cod_programa,cod_sub_prog,cod_proyecto,deno_sector,deno_programa,deno_sub_prog,deno_proyecto,deno_activ51,deno_activ52,deno_activ53,deno_activ54,deno_activ55,deno_activ56,deno_activ57,deno_activ58,deno_activ59,deno_activ60,deno_activ61,deno_activ62,deno_activ63,deno_activ64,deno_activ65,deno_activ66,deno_activ67,deno_activ68,deno_activ69,deno_activ70 order by cod_sector,cod_programa,cod_sub_prog,cod_proyecto ASC");
            $this->set('data_sectores', $DATOS_sectores);
            $DATOS_res = $this->$modelo->findAll($sql,null,'cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
            $this->set('data', $DATOS_res);
            $this->set('titulo_a', $titulo_a);
            $this->set('modelo', $modelo);
            $this->set('ano_presupuesto', $ano_formular);

  }
$this->set('entidad_federal', $this->Session->read('entidad_federal'));
}//fin funcion forma_2126_gob

}//fin class
?>