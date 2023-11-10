<?php
class cfpp18ContratoColectivoClausulasController extends AppController{

     var $uses = array('cfpd18_contrato_colectivo_clausulas','v_cfpd17_inversion_coordinada','cfpd17_inversion_coordinada_estado','cfpd18_contrato_colectivo_sindicato','cfpd17_inversion_coordinada_municipio','cfpd17_inversion_coordinada_organismo','cfpd17_inversion_coordinada','cfpd16','cfpd06', 'cfpd02_sector', 'cfpd02_programa', 'cfpd05', 'cfpd05_auxiliar', 'cfpd02_sub_prog', 'cfpd02_proyecto', 'cfpd02_activ_obra', 'cfpd01_ano_grupo', 'cfpd01_ano_partida', 'cfpd01_ano_generica', 'cfpd01_ano_especifica', 'cfpd01_ano_sub_espec', 'cfpd01_ano_auxiliar', 'cfpd01_formulacion');
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
    $this->layout="ajax";
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
	$listasindicato=$this->cfpd18_contrato_colectivo_sindicato->generateList($this->SQLCA(), 'cod_sindicato ASC', null, '{n}.cfpd18_contrato_colectivo_sindicato.cod_sindicato', '{n}.cfpd18_contrato_colectivo_sindicato.denominacion');
	$this->concatena($listasindicato, 'sindicato');

}//fin index2



function ejercicio($var=null){
	$this->layout='ajax';
	$this->Session->write('ano',$var);
	$this->render('funcion');
}

function cod_sindicato($var=null){
	$this->layout='ajax';
	$this->set('var',$var);
	$ano_formulacion =  $this->Session->read('ano');
	$num = $this->cfpd18_contrato_colectivo_clausulas->findAll($this->SQLCA().' and ano_formulacion='.$ano_formulacion.' and cod_sindicato='.$var,null,'cod_clausula DESC');
	if($num==null){
		$numero = 1;
	}else{
		$numero = $num[0]['cfpd18_contrato_colectivo_clausulas']['cod_clausula'] + 1;
	}
	echo'<script>';
       echo" document.getElementById('cod_clausula').value           	= '".mascara($numero,2)."'; ";
       echo" document.getElementById('deno_clausula').value           	= ''; ";
	echo'</script>';
}

function den_sindicato($var=null){
	$this->layout='ajax';
	$datos = $this->cfpd18_contrato_colectivo_sindicato->findAll($this->SQLCA().' and cod_sindicato='.$var);
	$den = $datos[0]['cfpd18_contrato_colectivo_sindicato']['denominacion'];
	$this->set('var',$den);
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
	$cod_presi 					= $this->Session->read('SScodpresi');
	$cod_entidad 				= $this->Session->read('SScodentidad');
	$cod_tipo_inst 				= $this->Session->read('SScodtipoinst');
	$cod_inst 					= $this->Session->read('SScodinst');
	$cod_dep 					= $this->Session->read('SScoddep');
	$ano 			= $this->data['cfpp18_contrato_colectivo_clausulas']['ejercicio'];
	$sindicato 		= $this->data['cfpp18_contrato_colectivo_clausulas']['cod_sindicato'];
	$clausula 		= $this->data['cfpp18_contrato_colectivo_clausulas']['cod_clausula'];
	$deno_clausula 	= $this->data['cfpp18_contrato_colectivo_clausulas']['deno_clausula'];


    $cont = $this->cfpd18_contrato_colectivo_clausulas->findCount($this->SQLCA().' and ano_formulacion='.$ano.' and cod_sindicato='.$sindicato.' and cod_clausula='.$clausula);
	if($cont==0){
		$sql ="INSERT INTO cfpd18_contrato_colectivo_clausulas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep,
  				cod_sindicato, ano_formulacion, cod_clausula, denominacion_clausula)";
		$sql.="VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep,
				 $sindicato, $ano, $clausula, '".$deno_clausula."');";
		$sw1 = $this->cfpd18_contrato_colectivo_clausulas->execute($sql);
		$this->set('Message_existe', 'Los datos fueron guardados con exito.');
	}else{
		$this->set("errorMessage", "EL REGISTRO YA EXISTE");
	}
	$num = $this->cfpd18_contrato_colectivo_clausulas->findAll($this->SQLCA().' and ano_formulacion='.$ano.' and cod_sindicato='.$sindicato,null,'cod_clausula DESC');
	if($num==null){
		$numero = 1;
	}else{
		$numero = $num[0]['cfpd18_contrato_colectivo_clausulas']['cod_clausula'] + 1;
	}
	$datos  =$this->cfpd18_contrato_colectivo_clausulas->findAll($this->SQLCA().' and ano_formulacion='.$ano.' and cod_sindicato='.$sindicato);
	$this->set('datos',$datos);
	echo'<script>';
       echo" document.getElementById('cod_clausula').value           	= '".mascara($numero,2)."'; ";
       echo" document.getElementById('deno_clausula').value           	= ''; ";
	echo'</script>';

}

function grilla($sindicato=null){
	$this->layout='ajax';
	$ano =  $this->Session->read('ano');
	$datos  =$this->cfpd18_contrato_colectivo_clausulas->findAll($this->SQLCA().' and ano_formulacion='.$ano.' and cod_sindicato='.$sindicato);
	$this->set('datos',$datos);
}

function editar($sindicato=null,$clausula=null,$ano=null,$i=null){
$this->layout = "ajax";
	$d = $this->cfpd18_contrato_colectivo_clausulas->findAll($this->SQLCA().' and ano_formulacion='.$ano.' and cod_sindicato='.$sindicato.' and cod_clausula='.$clausula);
    $den_clausula = $d[0]['cfpd18_contrato_colectivo_clausulas']['denominacion_clausula'];
	$this->set('den_clausula',$den_clausula);
	$this->set('i',$i);
	$this->set('sindicato',$sindicato);
	$this->set('clausula',$clausula);
	$this->set('ano',$ano);
}//fin function

function cancelar($sindicato=null,$clausula=null,$ano=null,$i=null){
$this->layout = "ajax";
	$d = $this->cfpd18_contrato_colectivo_clausulas->findAll($this->SQLCA().' and ano_formulacion='.$ano.' and cod_sindicato='.$sindicato.' and cod_clausula='.$clausula);
    $den_clausula = $d[0]['cfpd18_contrato_colectivo_clausulas']['denominacion_clausula'];
	$this->set('den_clausula',$den_clausula);
	$this->set('i',$i);
	$this->set('sindicato',$sindicato);
	$this->set('clausula',$clausula);
	$this->set('ano',$ano);
}//fin function

function eliminar($sindicato=null,$clausula=null,$ano=null,$i=null){
	$this->layout='ajax';
	$upd="DELETE FROM cfpd18_contrato_colectivo_clausulas WHERE ano_formulacion=$ano and cod_sindicato=$sindicato and cod_clausula=$clausula and ".$this->SQLCA();
	$sw1 = $this->cfpd18_contrato_colectivo_clausulas->execute($upd);
	$num = $this->cfpd18_contrato_colectivo_clausulas->findAll($this->SQLCA().' and ano_formulacion='.$ano.' and cod_sindicato='.$sindicato,null,'cod_clausula DESC');
	if($num==null){
		$numero = 1;
	}else{
		$numero = $num[0]['cfpd18_contrato_colectivo_clausulas']['cod_clausula'] + 1;
	}
	$datos  =$this->cfpd18_contrato_colectivo_clausulas->findAll($this->SQLCA().' and ano_formulacion='.$ano.' and cod_sindicato='.$sindicato);
	$this->set('datos',$datos);
		$this->set('Message_existe', 'LOS DATOS FUERON ELIMINADOS');
	echo'<script>';
       echo" document.getElementById('cod_clausula').value           	= '".mascara($numero,2)."'; ";
       echo" document.getElementById('deno_clausula').value           	= ''; ";
	echo'</script>';
}

function guardar_editar($sindicato=null,$clausula=null,$ano=null,$i=null){
	$this->layout='ajax';
	$deno_clausula  = $this->data['cfpp18_contrato_colectivo_clausulas']["deno_clausula_".$i];
	$upd="update cfpd18_contrato_colectivo_clausulas set denominacion_clausula='".$deno_clausula."' WHERE ano_formulacion=$ano and cod_sindicato=$sindicato and cod_clausula=$clausula and ".$this->SQLCA();
	$sw1 = $this->cfpd18_contrato_colectivo_clausulas->execute($upd);
	$this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
	$this->set('den_clausula',$deno_clausula);
	$this->set('i',$i);
	$this->set('sindicato',$sindicato);
	$this->set('clausula',$clausula);
	$this->set('ano',$ano);

}

 }//fin clase cfpp06Controller

 ?>