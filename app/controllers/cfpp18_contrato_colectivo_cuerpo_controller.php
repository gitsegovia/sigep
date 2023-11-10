<?php
class cfpp18ContratoColectivoCuerpoController extends AppController{

     var $uses = array('cfpd18_contrato_colectivo_cuerpo','cfpd18_contrato_colectivo_detalles','cfpd18_contrato_colectivo_clausulas','v_cfpd17_inversion_coordinada','cfpd17_inversion_coordinada_estado','cfpd18_contrato_colectivo_sindicato','cfpd17_inversion_coordinada_municipio','cfpd17_inversion_coordinada_organismo','cfpd17_inversion_coordinada','cfpd16','cfpd06', 'cfpd02_sector', 'cfpd02_programa', 'cfpd05', 'cfpd05_auxiliar', 'cfpd02_sub_prog', 'cfpd02_proyecto', 'cfpd02_activ_obra', 'cfpd01_ano_grupo', 'cfpd01_ano_partida', 'cfpd01_ano_generica', 'cfpd01_ano_especifica', 'cfpd01_ano_sub_espec', 'cfpd01_ano_auxiliar', 'cfpd01_formulacion');
     var $helpers = array('Html','Ajax','Javascript', 'Sisap');
    // var $paginate = array('limit' => 3, 'page' => 1);
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

   	  $Var = substr($Var, - 2);

   	return $Var;
   	  }else{
   	  	  //return $Var;
   	  }



   }//fin AddCero


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


   function Formato1($monto) {
    $monto = preg_replace("/[^0-9\.]/", "", str_replace(',','.',$monto));
    if (substr($monto,-3,1)=='.') {
        $sents = '.'.substr($monto,-2);
        $monto = substr($monto,0,strlen($monto)-3);
    } elseif (substr($monto,-2,1)=='.') {
        $sents = '.'.substr($monto,-1);
        $monto = substr($monto,0,strlen($monto)-2);
    } else {
        $sents = '.00';
    }
    $monto = preg_replace("/[^0-9]/", "", $monto);
    return number_format($monto.$sents,2,'.','');
    }

function Formato2($monto){
    	return number_format($monto,2,",",".");
    }

   function SQLCX($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)." ";
		 return $sql_re;


    }//fin funcion SQLX


 function index(){
 	$this->layout ="ajax";
 	$this->data=null;
	$a = $this->cfpd01_formulacion->findAll($this->SQLCX());
	if($a != null){
		$ano_formulacion = $a[0]['cfpd01_formulacion']['ano_formular'];
	}else{
		$ano_formulacion='';
	}
	for($minCount = 2007; $minCount < 2030; $minCount++) {
    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
    $this->set('anos',$anos);
    $this->set('ano_formulacion',$ano_formulacion);
    	$this->Session->write('ano',$ano_formulacion);
	}
 }


function index2(){
    $this->layout="ajax";
    $ano_formulacion = $this->data['cfpp18_contrato_colectivo_cuerpo']['presupuesto'];
    if($ano_formulacion==null){
    	$ano_formulacion =  $this->Session->read('ano');
    }
    //pr($this->data);
	$this->data=null;
	for($minCount = 2007; $minCount < 2030; $minCount++) {
    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
    $this->set('anos',$anos);

    $this->set('ano_formulacion',$ano_formulacion);
    $this->Session->write('ano',$ano_formulacion);
	}
	$listasindicato=$this->cfpd18_contrato_colectivo_sindicato->generateList($this->SQLCA(), 'cod_sindicato ASC', null, '{n}.cfpd18_contrato_colectivo_sindicato.cod_sindicato', '{n}.cfpd18_contrato_colectivo_sindicato.denominacion');
	$this->concatena($listasindicato, 'sindicato');
	$cond2 ="ejercicio=".$ano_formulacion." and cod_grupo=".CE." and (cod_partida=1 or cod_partida=3 or cod_partida=7)";
	$lista=  $this->cfpd01_ano_partida->generateList($cond2, 'cod_partida ASC', null, '{n}.cfpd01_ano_partida.cod_partida', '{n}.cfpd01_ano_partida.denominacion');
    $this->concatena($lista, 'vector', 4);


}//fin index2



function ejercicio($var=null){
	$this->layout='ajax';
	$this->Session->write('ano',$var);
	$this->render('funcion');
}

function cod_sindicato($var=null){
	$this->layout='ajax';
	$this->set('var',$var);
	    $this->Session->write('sindi',$var);
	$ano_formulacion =  $this->Session->read('ano');
	$num = $this->cfpd18_contrato_colectivo_clausulas->findAll($this->SQLCA().' and ano_formulacion='.$ano_formulacion.' and cod_sindicato='.$var,null,'cod_clausula DESC');
	if($num==null){
		$numero = 1;
	}else{
		$numero = $num[0]['cfpd18_contrato_colectivo_clausulas']['cod_clausula'] + 1;
	}
//	echo'<script>';
 //      echo" document.getElementById('cod_clausula').value           	= '".mascara($numero,2)."'; ";
 //      echo" document.getElementById('deno_clausula').value           	= ''; ";
//	echo'</script>';
}

function den_sindicato($var=null){
	$this->layout='ajax';
	$datos = $this->cfpd18_contrato_colectivo_sindicato->findAll($this->SQLCA().' and cod_sindicato='.$var);
	$den = $datos[0]['cfpd18_contrato_colectivo_sindicato']['denominacion'];
	$this->set('var',$den);
}

function clausula($var=null){
	$this->layout='ajax';
	$ano =  $this->Session->read('ano');
	$lista=  $this->cfpd18_contrato_colectivo_clausulas->generateList($this->condicion().' and ano_formulacion='.$ano.' and cod_sindicato='.$var, 'cod_clausula ASC', null, '{n}.cfpd18_contrato_colectivo_clausulas.cod_clausula', '{n}.cfpd18_contrato_colectivo_clausulas.denominacion_clausula');
    $this->concatena($lista, 'clausula');
}

function concatenaCE_v2($vector1=null, $nomVar=null){
	if($vector1 != null){
		foreach($vector1 as $x => $y){
			$cod[CE.$this->zero($x)] =CE.$this->zero($x).' - '.$y;
		}
		//print_r($cod);
		$this->set($nomVar, $cod);
	}
}

function concatena4($vector1=null, $nomVar=null, $extra=null){
	$cod = array();
	if($vector1 != null){
		foreach($vector1 as $x => $y){
			if($extra!=null){
				if($x<10){
					$cod[$x] = $extra.'.0'.$x.' - '.$y;
				}else if($x>=10 && $x<=99){
					$cod[$x] = $extra.'.'.$x.' - '.$y;
				}
			}else{

				if($x<10){
					$cod[$x] = '0'.$x.' - '.$y;
				}else if($x>=10 && $x<=9999){
					$cod[$x] = $x.' - '.$y;
				}
			}
		}
	}

	$this->set($nomVar, $cod);
}//fin function


function funcion(){
	$this->layout='ajax';
}

function agregar_grilla(){
	$this->layout='ajax';
	$cod_presi 				= $this->Session->read('SScodpresi');
	$cod_entidad 			= $this->Session->read('SScodentidad');
	$cod_tipo_inst 			= $this->Session->read('SScodtipoinst');
	$cod_inst 				= $this->Session->read('SScodinst');
	$cod_dep 				= $this->Session->read('SScoddep');
	$ano 					= $this->Session->read('ano');
	$sindicato 				= $this->data['cfpp18_contrato_colectivo_cuerpo']['cod_sindicato'];
	$clausula 				= $this->data['cfpp18_contrato_colectivo_cuerpo']['cod_clausula'];
	$fecha_inicio 			= $this->data['cfpp18_contrato_colectivo_cuerpo']['fecha_inicio'];
	$fecha_concusion 		= $this->data['cfpp18_contrato_colectivo_cuerpo']['fecha_conclusion'];
	$cod_partida			= $this->data['cfpp18_contrato_colectivo_cuerpo']['cod_partida'];
	$cod_partida = $cod_partida >10 ? CE.$cod_partida : CE."0".$cod_partida ;
	$cod_generica			= $this->data['cfpp18_contrato_colectivo_cuerpo']['cod_generica'];
	$cod_especifica			= $this->data['cfpp18_contrato_colectivo_cuerpo']['cod_especifica'];
	$cod_sub_espec			= $this->data['cfpp18_contrato_colectivo_cuerpo']['cod_subespecifica'];
	$revisado_anterior		= $this->Formato1($this->data['cfpp18_contrato_colectivo_cuerpo']['revisado']);
	$presupuestado_actual	= $this->Formato1($this->data['cfpp18_contrato_colectivo_cuerpo']['presupuesto']);
	$base_calculo			= $this->data['cfpp18_contrato_colectivo_cuerpo']['bases'];
	if($base_calculo == null){
		$base_calculo='0';
	}

	$ya = $this->cfpd18_contrato_colectivo_cuerpo->findCount($this->SQLCA()." and ano_formulacion=$ano and cod_sindicato=".$sindicato);
	if($ya =='0'){
		$sql ="INSERT INTO cfpd18_contrato_colectivo_cuerpo (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep,
  			ano_formulacion, cod_sindicato, fecha_contrato_inicio, fecha_contrato_conclusion)";
		$sql.="VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep,
		 	$ano, $sindicato,'".$fecha_inicio."','".$fecha_concusion."');";
		$sw1 = $this->cfpd18_contrato_colectivo_cuerpo->execute($sql);
	}
	//cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_formulacion, cod_sindicato, cod_clausula, cod_partida,
	//cod_generica, cod_especifica, cod_sub_espec, revisado_anterior, presupuestado_actual, base_calculo

		$sql2 ="INSERT INTO cfpd18_contrato_colectivo_detalles (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep,
				 ano_formulacion, cod_sindicato, cod_clausula, cod_partida,
				cod_generica, cod_especifica, cod_sub_espec, revisado_anterior, presupuestado_actual, base_calculo)";
		$sql2.="VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep,
				 $ano, $sindicato, $clausula, $cod_partida,
				$cod_generica, $cod_especifica, $cod_sub_espec, $revisado_anterior, $presupuestado_actual, '".$base_calculo."')";
		$sw2 = $this->cfpd18_contrato_colectivo_detalles->execute($sql2);

		$this->set('Message_existe', 'Los datos fueron guardados con exito.');
	$datos  =$this->cfpd18_contrato_colectivo_detalles->findAll($this->SQLCA().' and ano_formulacion='.$ano.' and cod_sindicato='.$sindicato);
	$this->set('datos',$datos);
	echo'<script>';
       echo" document.getElementById('cod_clausula').value           	= ''; ";
       echo" document.getElementById('select_1').value           		= ''; ";
       echo" document.getElementById('select_2').value           		= ''; ";
       echo" document.getElementById('select_3').value           		= ''; ";
       echo" document.getElementById('select_4').value           		= ''; ";
       echo" document.getElementById('revisado').value           		= ''; ";
       echo" document.getElementById('presupuesto').value          		= ''; ";
       echo" document.getElementById('bases').value           			= ''; ";
	echo'</script>';

}

function grilla($sindicato=null){
	$this->layout='ajax';
	$ano =  $this->Session->read('ano');
	$datos  =$this->cfpd18_contrato_colectivo_detalles->findAll($this->SQLCA().' and ano_formulacion='.$ano.' and cod_sindicato='.$sindicato);
	$this->set('datos',$datos);
	$datos2  =$this->cfpd18_contrato_colectivo_cuerpo->findAll($this->SQLCA().' and ano_formulacion='.$ano.' and cod_sindicato='.$sindicato);
	if($datos2 != null){
		$fecha_inicio		 = cambia_fecha($datos2[0]['cfpd18_contrato_colectivo_cuerpo']['fecha_contrato_inicio']);
		$fecha_conclusion	 = cambia_fecha($datos2[0]['cfpd18_contrato_colectivo_cuerpo']['fecha_contrato_conclusion']);
	}else{
		$fecha_inicio	  = '';
		$fecha_conclusion = '';
	}

	echo'<script>';
       echo" document.getElementById('fecha_inicio').value           		= '".$fecha_inicio."'; ";
       echo" document.getElementById('fecha_conclusion').value           	= '".$fecha_conclusion."'; ";
	echo'</script>';

}

function editar($sindicato=null,$clausula=null,$ano=null,$i=null){
$this->layout = "ajax";
	$d 				= $this->cfpd18_contrato_colectivo_detalles->findAll($this->SQLCA().' and ano_formulacion='.$ano.' and cod_sindicato='.$sindicato.' and cod_clausula='.$clausula);
    $partida 		= $d[0]['cfpd18_contrato_colectivo_detalles']['cod_partida'];
	$generica 		= $d[0]['cfpd18_contrato_colectivo_detalles']['cod_generica'];
	$especifica 	= $d[0]['cfpd18_contrato_colectivo_detalles']['cod_especifica'];
	$sub_espec 		= $d[0]['cfpd18_contrato_colectivo_detalles']['cod_sub_espec'];
	$revisado 		= $d[0]['cfpd18_contrato_colectivo_detalles']['revisado_anterior'];
	$presupuesto 	= $d[0]['cfpd18_contrato_colectivo_detalles']['presupuestado_actual'];
	$base 			= $d[0]['cfpd18_contrato_colectivo_detalles']['base_calculo'];
	if($base=='0'){
		$base='';
	}
	$this->set('revisado',$revisado);
	$this->set('presupuesto',$presupuesto);
	$this->set('base',$base);
	$this->set('partida',$partida);
	$this->set('generica',$generica);
	$this->set('especifica',$especifica);
	$this->set('sub_espec',$sub_espec);
	$this->set('i',$i);
	$this->set('sindicato',$sindicato);
	$this->set('clausula',$clausula);
	$this->set('ano',$ano);
}//fin function

function cancelar($sindicato=null,$clausula=null,$ano=null,$i=null){
$this->layout = "ajax";
	$d 				= $this->cfpd18_contrato_colectivo_detalles->findAll($this->SQLCA().' and ano_formulacion='.$ano.' and cod_sindicato='.$sindicato.' and cod_clausula='.$clausula);
    $partida 		= $d[0]['cfpd18_contrato_colectivo_detalles']['cod_partida'];
	$generica 		= $d[0]['cfpd18_contrato_colectivo_detalles']['cod_generica'];
	$especifica 	= $d[0]['cfpd18_contrato_colectivo_detalles']['cod_especifica'];
	$sub_espec 		= $d[0]['cfpd18_contrato_colectivo_detalles']['cod_sub_espec'];
	$revisado 		= $d[0]['cfpd18_contrato_colectivo_detalles']['revisado_anterior'];
	$presupuesto 	= $d[0]['cfpd18_contrato_colectivo_detalles']['presupuestado_actual'];
	$base 			= $d[0]['cfpd18_contrato_colectivo_detalles']['base_calculo'];
	if($base=='0'){
		$base='';
	}
	$this->set('revisado',$revisado);
	$this->set('presupuesto',$presupuesto);
	$this->set('base',$base);
	$this->set('partida',$partida);
	$this->set('generica',$generica);
	$this->set('especifica',$especifica);
	$this->set('sub_espec',$sub_espec);
	$this->set('i',$i);
	$this->set('sindicato',$sindicato);
	$this->set('clausula',$clausula);
	$this->set('ano',$ano);
}//fin function

function eliminar($sindicato=null,$clausula=null,$ano=null,$i=null){
	$this->layout='ajax';
	$upd="DELETE FROM cfpd18_contrato_colectivo_detalles WHERE ano_formulacion=$ano and cod_sindicato=$sindicato and cod_clausula=$clausula and ".$this->SQLCA();
	$sw1 = $this->cfpd18_contrato_colectivo_detalles->execute($upd);
	$datos  =$this->cfpd18_contrato_colectivo_detalles->findAll($this->SQLCA().' and ano_formulacion='.$ano.' and cod_sindicato='.$sindicato);
	$this->set('datos',$datos);
	$this->set('Message_existe', 'LOS DATOS FUERON ELIMINADOS');
}

function guardar_editar($sindicato=null,$clausula=null,$ano=null,$i=null){
	$this->layout='ajax';
	//pr($this->data);
	$revisado  		= $this->Formato1($this->data['cfpp18_contrato_colectivo_cuerpo']["revisado_".$i]);
	$presupuesto  	= $this->Formato1($this->data['cfpp18_contrato_colectivo_cuerpo']["presupuesto_".$i]);
	$base 		 	= $this->data['cfpp18_contrato_colectivo_cuerpo']["bases_".$i];
	if($base == null){
		$base='0';
	}

	$upd="update cfpd18_contrato_colectivo_detalles set revisado_anterior=$revisado, presupuestado_actual=$presupuesto,base_calculo='".$base."' WHERE ano_formulacion=$ano and cod_sindicato=$sindicato and cod_clausula=$clausula and ".$this->SQLCA();
	$sw1 = $this->cfpd18_contrato_colectivo_detalles->execute($upd);
	$this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
	$d 				= $this->cfpd18_contrato_colectivo_detalles->findAll($this->SQLCA().' and ano_formulacion='.$ano.' and cod_sindicato='.$sindicato.' and cod_clausula='.$clausula);
    $partida 		= $d[0]['cfpd18_contrato_colectivo_detalles']['cod_partida'];
	$generica 		= $d[0]['cfpd18_contrato_colectivo_detalles']['cod_generica'];
	$especifica 	= $d[0]['cfpd18_contrato_colectivo_detalles']['cod_especifica'];
	$sub_espec 		= $d[0]['cfpd18_contrato_colectivo_detalles']['cod_sub_espec'];
	$revisado 		= $d[0]['cfpd18_contrato_colectivo_detalles']['revisado_anterior'];
	$presupuesto 	= $d[0]['cfpd18_contrato_colectivo_detalles']['presupuestado_actual'];
	$base 			= $d[0]['cfpd18_contrato_colectivo_detalles']['base_calculo'];
	if($base=='0'){
		$base='';
	}
	$this->set('revisado',$revisado);
	$this->set('presupuesto',$presupuesto);
	$this->set('base',$base);
	$this->set('partida',$partida);
	$this->set('generica',$generica);
	$this->set('especifica',$especifica);
	$this->set('sub_espec',$sub_espec);
	$this->set('i',$i);
	$this->set('sindicato',$sindicato);
	$this->set('clausula',$clausula);
	$this->set('ano',$ano);

}

function select3($select=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";
	if($var!=null){
    $cond =$this->SQLCA();
	switch($select){
/*		case 'sector':
		  $this->set('SELECT','programa');
		  $this->set('codigo','sector');
		  $this->set('seleccion','');
		  $this->set('n',1);
		  $this->Session->write('ano',$var);
		  $cond .=" and ano=".$var;
		  $lista=  $this->cfpd02_sector->generateList($cond, 'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'programa':
		  $this->set('SELECT','subprograma');
		  $this->set('codigo','programa');
		  $this->set('seleccion','');
		  $this->set('n',2);
		  $ano =  $this->Session->read('ano');
		  $this->Session->write('sec',$var);
		  $cond .=" and ano=".$ano." and cod_sector=".$var;
		  $lista=  $this->cfpd02_programa->generateList($cond, 'cod_programa ASC', null, '{n}.cfpd02_programa.cod_programa', '{n}.cfpd02_programa.denominacion');
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
		  $lista=  $this->cfpd02_sub_prog->generateList($cond, 'cod_sub_prog ASC', null, '{n}.cfpd02_sub_prog.cod_sub_prog', '{n}.cfpd02_sub_prog.denominacion');
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
		  $lista=  $this->cfpd02_proyecto->generateList($cond, 'cod_proyecto ASC', null, '{n}.cfpd02_proyecto.cod_proyecto', '{n}.cfpd02_proyecto.denominacion');
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
		  $lista=  $this->cfpd02_activ_obra->generateList($cond, 'cod_activ_obra ASC', null, '{n}.cfpd02_activ_obra.cod_activ_obra', '{n}.cfpd02_activ_obra.denominacion');
          $this->concatena($lista, 'vector');
		break;
*/
		case 'partida':
		  $this->set('SELECT','generica');
		  $this->set('codigo','partida');
		  $this->set('seleccion','');
		  $this->set('n',1);
		  $ano =  $this->Session->read('ano');
		  $this->Session->write('actv',$var);
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE."";
		  $lista=  $this->cfpd01_ano_partida->generateList($cond2, 'cod_partida ASC', null, '{n}.cfpd01_ano_partida.cod_partida', '{n}.cfpd01_ano_partida.denominacion');
          $this->concatena($lista, 'vector', 4);
		break;
		case 'generica':
		  $this->set('SELECT','especifica');
		  $this->set('codigo','generica');
		  $this->set('seleccion','');
		  $this->set('n',2);
		  $ano =  $this->Session->read('ano');
		  $this->Session->write('cpar',$var);
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$var;
		  $lista=  $this->cfpd01_ano_generica->generateList($cond2, 'cod_generica ASC', null, '{n}.cfpd01_ano_generica.cod_generica', '{n}.cfpd01_ano_generica.denominacion');
          $this->concatena($lista, 'vector');
 		break;
		case 'especifica':
		  $this->set('SELECT','subespecifica');
		  $this->set('codigo','especifica');
		  $this->set('seleccion','');
		  $this->set('n',3);
		  $ano =  $this->Session->read('ano');
		  $cpar =  $this->Session->read('cpar');
		  $this->Session->write('cgen',$var);
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$cpar." and cod_generica=".$var;
		  $lista = $this->cfpd01_ano_especifica->generateList($cond2, 'cod_especifica ASC', null, '{n}.cfpd01_ano_especifica.cod_especifica', '{n}.cfpd01_ano_especifica.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'subespecifica':
		  $this->set('SELECT','subespecifica2');
		  $this->set('codigo','subespecifica');
		  $this->set('seleccion','');
		  $this->set('n',4);
		  $ano =  $this->Session->read('ano');
		  $cpar =  $this->Session->read('cpar');
		  $cgen =  $this->Session->read('cgen');
		  $this->Session->write('cesp',$var);
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$var;
		  $lista=  $this->cfpd01_ano_sub_espec->generateList($cond2, 'cod_sub_espec ASC', null, '{n}.cfpd01_ano_sub_espec.cod_sub_espec', '{n}.cfpd01_ano_sub_espec.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'auxiliar':

		  $this->set('SELECT','auxiliar');
		  $this->set('codigo','auxiliar');
		  $this->set('seleccion','');
		  $this->set('n',10);
//		  $this->set('no','no');
          $this->set('seleccion','seleccion');

          $ano  =  $this->Session->read('ano');
		  $sec  =  $this->Session->read('sec');
		  $prog =  $this->Session->read('prog');
		  $subp =  $this->Session->read('subp');
		  $proy =  $this->Session->read('proy');
		  $actv =  $this->Session->read('actv');
		  $cpar  = $this->Session->read('cpar');
          $cond  = $this->condicion()." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$actv;

		  $ano =  $this->Session->read('ano');
		  $cpar =  $this->Session->read('cpar');
		  $cpar = $cpar < 10 ? CE."0".$cpar : CE.$cpar;
		  $cgen =  $this->Session->read('cgen');
		  $cesp =  $this->Session->read('cesp');
		  $this->Session->write('dsubesp',$var);
		  $cond2 = $cond." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$var;
		  $lista=  $this->cfpd05_auxiliar->generateList($cond2, 'cod_auxiliar ASC', null, '{n}.cfpd05_auxiliar.cod_auxiliar', '{n}.cfpd05_auxiliar.denominacion');
                       if($lista!=null){
							$this->concatena_aux($lista, 'vector');
						}else{
							$this->set('vector',array('0'=>'0000'));
		  $ano  =  $this->Session->read('ano');
		  $sec  =  $this->Session->read('sec');
		  $prog =  $this->Session->read('prog');
		  $subp =  $this->Session->read('subp');
		  $proy =  $this->Session->read('proy');
		  $actv =  $this->Session->read('actv');
          $cond  = $this->condicion()." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$actv;
		  $cpar =  $this->Session->read('cpar');
		  $cpar = $cpar < 10 ? CE."0".$cpar : CE.$cpar;
		  $cgen =  $this->Session->read('cgen');
		  $cesp =  $this->Session->read('cesp');
		  $csesp =  $this->Session->read('dsubesp');
		  $cod_esta  =  $this->Session->read('cod_esta');
		  $cod_orga  =  $this->Session->read('cod_orga');
		  $cod_muni  =  $this->Session->read('cod_muni');
		  $cond2 = $cond." and cod_estado=".$cod_esta." and cod_organismo=".$cod_orga." and cod_municipio=".$cod_muni." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$csesp." and cod_auxiliar=0";
		  $regis = $this->cfpd17_inversion_coordinada->findCount($cond2);
		  if($regis=='0'){
			echo "<script>";
		  		echo "document.getElementById('todo').disabled='';  ";
        	echo "</script>";
		  }elseif($regis !='0'){
				$this->set('errorMessage','CÓDIGO PRESUPUESTARIO YA ESTA REGISTRADO.');
			echo "<script>";
		  		echo "document.getElementById('todo').disabled='true';  ";
        	echo "</script>";
		  }

						}
		break;
		case 'seleccion':
		 $this->Session->write('aux',$var);
		 $this->seleccion("si", $var);
		 $this->render("seleccion");
		break;
	}//fin wsitch
	}else{
		  $this->set('SELECT','');
		  $this->set('codigo','');
		  $this->set('seleccion','');
		  $this->set('n',10);
		  $this->set('no','no');
		 $this->set('vector','');
	}
}//fin select codigos presupuestarios


function validar($var=null){
	$this->layout='ajax';
	$ano =  $this->Session->read('ano');
	$sin =  $this->Session->read('sindi');
	$ya = $this->cfpd18_contrato_colectivo_detalles->findCount($this->SQLCA()." and ano_formulacion=$ano and cod_sindicato=".$sin." and cod_clausula=".$var);
	if($ya == '0'){
			echo "<script>";
		  		echo "document.getElementById('plus').disabled='';  ";
        	echo "</script>";
	}elseif($ya !='0'){
		$this->set('errorMessage','Cl&aacute;usula YA ESTA REGISTRADa.');
			echo "<script>";
		  		echo "document.getElementById('plus').disabled='true';  ";
        	echo "</script>";
	}

}

	function consulta($pagina=null,$ano=null){
 		$this->layout = "ajax";
 		for($minCount = 2007; $minCount < 2030; $minCount++) {
    		$anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
   	 		$this->set('anos',$anos);
 		}
 		if($ano==null){
			$ano = $this->data['cfpp18_contrato_colectivo_cuerpo']['presupuesto'];
 		}
		$cond = $this->SQLCA()." and ano_formulacion=".$ano;
        if($pagina!=null){
        	$pagina=$pagina;
          	$this->set('pagina',$pagina);
          	$Tfilas=$this->cfpd18_contrato_colectivo_cuerpo->findCount($cond);
          	if($Tfilas==0){
          		$this->set('Message_existe', 'No se encontraron datos.');
				$this->index();
				$this->render("index");
          	}
          	if($Tfilas!=0){
          		$this->set('pag_cant',$pagina.'/'.$Tfilas);
          		$datos2		 = $this->cfpd18_contrato_colectivo_cuerpo->findAll($cond,null,'cod_sindicato ASC',1,$pagina,null);
          		//pr($datos2);
          	 	$sindicato	 = $datos2[0]['cfpd18_contrato_colectivo_cuerpo']['cod_sindicato'];
          	 	$deno		 = $this->cfpd18_contrato_colectivo_sindicato->findAll('cod_sindicato='.$sindicato.' and '.$this->SQLCA());
          	 	$deno_sindi	 = $deno[0]['cfpd18_contrato_colectivo_sindicato']['denominacion'];
          	 	$datos		 = $this->cfpd18_contrato_colectivo_detalles->findAll('ano_formulacion='.$ano.' and cod_sindicato='.$sindicato.' and '.$this->SQLCA(),null,'cod_sindicato,cod_clausula ASC',null,null,null);
          	 	$this->set('deno_sindi',$deno_sindi);
          	 	$this->set('datos',$datos);
          	 	$this->set('datos2',$datos2);
          	 	$this->set('siguiente',$pagina+1);
          	 	$this->set('anterior',$pagina-1);
             	$this->bt_nav($Tfilas,$pagina);
           	}
 		}else{
 			$pagina=1;
 			$this->set('pagina',$pagina);
          	$Tfilas=$this->cfpd18_contrato_colectivo_cuerpo->findCount($cond);
          	if($Tfilas==0){
          		$this->set('Message_existe', 'No se encontraron datos.');
				$this->index();
				$this->render("index");
          	}
          	if($Tfilas!=0){
          		$this->set('pag_cant',$pagina.'/'.$Tfilas);
          		$datos2		 = $this->cfpd18_contrato_colectivo_cuerpo->findAll($cond,null,'cod_sindicato ASC',1,$pagina,null);
          	 	$sindicato	 = $datos2[0]['cfpd18_contrato_colectivo_cuerpo']['cod_sindicato'];
          	 	$deno		 = $this->cfpd18_contrato_colectivo_sindicato->findAll('cod_sindicato='.$sindicato.' and '.$this->SQLCA());
          	 	$deno_sindi	 = $deno[0]['cfpd18_contrato_colectivo_sindicato']['denominacion'];
          	 	$datos		 = $this->cfpd18_contrato_colectivo_detalles->findAll('ano_formulacion='.$ano.' and cod_sindicato='.$sindicato.' and '.$this->SQLCA(),null,'cod_sindicato,cod_clausula ASC',null,null,null);
          	 	$this->set('deno_sindi',$deno_sindi);
          	 	$this->set('datos',$datos);
          	 	$this->set('datos2',$datos2);
          	 	$this->set('siguiente',$pagina+1);
          	 	$this->set('anterior',$pagina-1);
             	$this->bt_nav($Tfilas,$pagina);
			}
        }
}//fin function consultar2

function modificar($ano=null,$sindicato=null,$pagina=null){
	$this->layout='ajax';
	$this->Session->write('sindi',$sindicato);
	$this->Session->write('ano',$ano);
	for($minCount = 2007; $minCount < 2030; $minCount++) {
    	$anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
   		$this->set('anos',$anos);
 	}
	$cond 		 = $this->SQLCA().' and ano_formulacion='.$ano.' and cod_sindicato='.$sindicato;
	$datos2		 = $this->cfpd18_contrato_colectivo_cuerpo->findAll($cond,null,'cod_sindicato ASC',1,null,null);
    $sindicato	 = $datos2[0]['cfpd18_contrato_colectivo_cuerpo']['cod_sindicato'];
    $deno		 = $this->cfpd18_contrato_colectivo_sindicato->findAll('cod_sindicato='.$sindicato.' and '.$this->SQLCA());
    $deno_sindi	 = $deno[0]['cfpd18_contrato_colectivo_sindicato']['denominacion'];
    $datos		 = $this->cfpd18_contrato_colectivo_detalles->findAll('ano_formulacion='.$ano.' and cod_sindicato='.$sindicato.' and '.$this->SQLCA(),null,'cod_sindicato,cod_clausula ASC',null,null,null);
    $this->set('deno_sindi',$deno_sindi);
    $this->set('datos',$datos);
    $this->set('datos2',$datos2);
    $this->set('pagina',$pagina);
    $this->set('ano',$ano);
    $this->set('sindicato',$sindicato);
    $lista=  $this->cfpd18_contrato_colectivo_clausulas->generateList('ano_formulacion='.$ano.' and cod_sindicato='.$sindicato.' and '.$this->SQLCA(), 'cod_clausula ASC', null, '{n}.cfpd18_contrato_colectivo_clausulas.cod_clausula', '{n}.cfpd18_contrato_colectivo_clausulas.denominacion_clausula');
    $this->concatena($lista, 'clausula');
    $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and (cod_partida=1 or cod_partida=3 or cod_partida=7)";
	$lista=  $this->cfpd01_ano_partida->generateList($cond2, 'cod_partida ASC', null, '{n}.cfpd01_ano_partida.cod_partida', '{n}.cfpd01_ano_partida.denominacion');
    $this->concatena($lista, 'vector', 4);
}

function guardar_modificar($ano=null,$sindicato=null,$pagina=null){
	$this->layout='ajax';
	$fecha_inicio 			= $this->data['cfpp18_contrato_colectivo_cuerpo']['fecha_inicio'];
	$fecha_conclusion 		= $this->data['cfpp18_contrato_colectivo_cuerpo']['fecha_conclusion'];
	$update = "update cfpd18_contrato_colectivo_cuerpo set fecha_contrato_inicio='".$fecha_inicio."', fecha_contrato_conclusion='".$fecha_conclusion."' where ano_formulacion=$ano and cod_sindicato=$sindicato and ".$this->SQLCA();
	$this->cfpd18_contrato_colectivo_cuerpo->execute($update);
	$this->set('Message_existe', 'Los datos fueron guardados con exito.');
	$this->consulta($pagina,$ano);
	$this->render("consulta");
}

	function eliminar2($ano=null,$sindicato=null,$pagina=null){
 		$this->layout = "ajax";
 			$this->cfpd18_contrato_colectivo_cuerpo->execute("DELETE FROM cfpd18_contrato_colectivo_cuerpo  WHERE ".$this->SQLCA()." and ano_formulacion=$ano and cod_sindicato=$sindicato");
 			$this->cfpd18_contrato_colectivo_detalles->execute("DELETE FROM cfpd18_contrato_colectivo_detalles  WHERE ".$this->SQLCA()." and ano_formulacion=$ano and cod_sindicato=$sindicato");
 			$y=$this->cfpd18_contrato_colectivo_cuerpo->findCount($this->SQLCA()." and ano_formulacion=$ano");
	  		if($y!=0){
	  			if($pagina!=1){
	  				$this->set('errorMessage', 'El registro fue eliminado');
	  				$this->consulta($pagina-1,$ano);
      				$this->render("consulta");
      			}else{
      				$this->set('errorMessage', 'El registro fue eliminado');
      	 			$this->consulta($pagina,$ano);//si es el primero solamente
        			$this->render("consulta");
      			}
			}else if($y==0){
				$this->set('errorMessage', 'El registro fue eliminado');
				$this->index();
				$this->render("index");
			}//fin if
 		}


 }//fin clase cfpp06Controller

 ?>