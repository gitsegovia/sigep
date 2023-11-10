<?php
class cfpp17InversionCoordinadaController extends AppController{

     var $uses = array('v_cfpd17_inversion_coordinada','cfpd17_inversion_coordinada_estado','cfpd17_inversion_coordinada_municipio','cfpd17_inversion_coordinada_organismo','cfpd17_inversion_coordinada','cfpd16','cfpd06', 'cfpd02_sector', 'cfpd02_programa', 'cfpd05', 'cfpd05_auxiliar', 'cfpd02_sub_prog', 'cfpd02_proyecto', 'cfpd02_activ_obra', 'cfpd01_ano_grupo', 'cfpd01_ano_partida', 'cfpd01_ano_generica', 'cfpd01_ano_especifica', 'cfpd01_ano_sub_espec', 'cfpd01_ano_auxiliar', 'cfpd01_formulacion');
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

	$listaSector=$this->cfpd02_sector->generateList($this->SQLCA(), 'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.denominacion');
	$this->concatena($listaSector, 'sector');
 }


function index2($ano=null){
    $this->layout="ajax";
	$a = $this->cfpd01_formulacion->findAll($this->SQLCX());
	$ano_formulacion=$this->data['cfpp17_inversion_coordinada']['presupuesto'];
	if($ano_formulacion==null){
		$ano_formulacion =  $this->Session->read('ano');
	}
	$this->data=null;
	for($minCount = 2007; $minCount < 2030; $minCount++) {
    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
    $this->set('anos',$anos);
    $this->set('ano_formulacion',$ano_formulacion);
    	$this->Session->write('ano',$ano_formulacion);
	}
	$listaSector=$this->cfpd02_sector->generateList($this->SQLCA(), 'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.denominacion');
	$this->concatena($listaSector, 'vector');

	$esta = $this->cfpd17_inversion_coordinada_estado->findAll($this->SQLCA(),null,'cod_estado ASC');
	$cod_esta = $esta[0]['cfpd17_inversion_coordinada_estado']['cod_estado'];
	$den_esta = $esta[0]['cfpd17_inversion_coordinada_estado']['denominacion'];
	$this->set('cod_esta',$cod_esta);
	$this->Session->write('cod_esta',$cod_esta);
	$this->set('den_esta',$den_esta);
	$listaestado=$this->cfpd17_inversion_coordinada_estado->generateList($this->SQLCA(), 'cod_estado ASC', null, '{n}.cfpd17_inversion_coordinada_estado.cod_estado', '{n}.cfpd17_inversion_coordinada_estado.denominacion');
	$this->concatena($listaestado, 'estado');

	$orga = $this->cfpd17_inversion_coordinada_organismo->findAll($this->SQLCA(),null,'cod_organismo ASC');
	$cod_orga = $orga[0]['cfpd17_inversion_coordinada_organismo']['cod_organismo'];
	$den_orga = $orga[0]['cfpd17_inversion_coordinada_organismo']['denominacion'];
	$this->set('cod_orga',$cod_orga);
	$this->Session->write('cod_orga',$cod_orga);
	$this->set('den_orga',$den_orga);
	$listaorganismo=$this->cfpd17_inversion_coordinada_organismo->generateList($this->SQLCA(), 'cod_organismo ASC', null, '{n}.cfpd17_inversion_coordinada_organismo.cod_organismo', '{n}.cfpd17_inversion_coordinada_organismo.denominacion');
	$this->concatena($listaorganismo, 'organismo');

	$muni = $this->cfpd17_inversion_coordinada_municipio->findAll($this->SQLCA(),null,'cod_municipio ASC');
	$cod_muni = $muni[0]['cfpd17_inversion_coordinada_municipio']['cod_municipio'];
	$den_muni = $muni[0]['cfpd17_inversion_coordinada_municipio']['denominacion'];
	$this->set('cod_muni',$cod_muni);
	$this->Session->write('cod_muni',$cod_muni);
	$this->set('den_muni',$den_muni);
	$listamunicipio=$this->cfpd17_inversion_coordinada_municipio->generateList($this->SQLCA(), 'cod_municipio ASC', null, '{n}.cfpd17_inversion_coordinada_municipio.cod_municipio', '{n}.cfpd17_inversion_coordinada_municipio.denominacion');
	$this->concatena($listamunicipio, 'municipio');
	          echo "<script>";
		  		echo "document.getElementById('ejercicio').disabled='true';  ";
        	echo "</script>";
}//fin index2

function cod_estado($var=null){
	$this->layout='ajax';
	$this->set('var',$var);
	$this->Session->write('cod_esta',$var);
}

function den_estado($var=null){
	$this->layout='ajax';
	$datos = $this->cfpd17_inversion_coordinada_estado->findAll($this->SQLCA().' and cod_estado='.$var);
	$den = $datos[0]['cfpd17_inversion_coordinada_estado']['denominacion'];
	$this->set('var',$den);
}

function cod_organismo($var=null){
	$this->layout='ajax';
	$this->set('var',$var);
	$this->Session->write('cod_orga',$var);
}

function den_organismo($var=null){
	$this->layout='ajax';
	$datos = $this->cfpd17_inversion_coordinada_organismo->findAll($this->SQLCA().' and cod_organismo='.$var);
	$den = $datos[0]['cfpd17_inversion_coordinada_organismo']['denominacion'];
	$this->set('var',$den);
}


function cod_municipio($var=null){
	$this->layout='ajax';
	$this->set('var',$var);
	$this->Session->write('cod_muni',$var);
}

function den_municipio($var=null){
	$this->layout='ajax';
	$datos = $this->cfpd17_inversion_coordinada_municipio->findAll($this->SQLCA().' and cod_municipio='.$var);
	$den = $datos[0]['cfpd17_inversion_coordinada_municipio']['denominacion'];
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
/*
function select3($select=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";
	$ano = $this->Session->read('ano');
	if($ano == null){
		$ano = $this->data['cfpp06']['presupuesto'];
		$this->Session->write('ano',$ano);
	}
if($select!=null && $var!=null){
    $cond =$this->SQLCA();
	switch($select){
		case 'sector':
		  $this->set('SELECT','programa');
		  $this->set('codigo','sector');
		  $this->set('seleccion','');
		  $this->set('n',1);
		  $this->Session->write('ano',$var);
		  $cond .=" and ano=".$var;
		  $lista = $this->cfpd02_sector->generateList($cond, 'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.denominacion');
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
       //   echo $this->Session->read('sec');
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
       //   echo $this->Session->read('sec').".".$this->Session->read('prog');
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
        //  echo $this->Session->read('sec').".".$this->Session->read('prog').".".$this->Session->read('subp');
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
         //  echo $this->Session->read('sec').".".$this->Session->read('prog').".".$this->Session->read('subp').".".$this->Session->read('proy');
		break;
		case 'partida':
		  $this->set('SELECT','generica');
		  $this->set('codigo','partida');
		  $this->set('seleccion','');
		  $this->set('n',6);
		  $this->Session->write('activ',$var);
		  $ano =  $this->Session->read('ano');
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE."";
		  $lista=  $this->cfpd01_ano_partida->generateList($cond2, 'cod_partida ASC', null, '{n}.cfpd01_ano_partida.cod_partida', '{n}.cfpd01_ano_partida.denominacion');
          $this->concatena($lista, 'vector', 4);
		break;
		case 'generica':
		  $this->set('SELECT','especifica');
		  $this->set('codigo','generica');
		  $this->set('seleccion','');
		  $this->set('n',7);
		  $ano =  $this->Session->read('ano');
		  $this->Session->write('cpar2',$var);
		  $var = substr($var,1);
		  $this->Session->write('cpar',$var);
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$var;
		  $lista=  $this->cfpd01_ano_generica->generateList($cond2, 'cod_generica ASC', null, '{n}.cfpd01_ano_generica.cod_generica', '{n}.cfpd01_ano_generica.denominacion');
          $this->concatena($lista, 'vector');
         // echo $this->Session->read('sec').".".$this->Session->read('prog').".".$this->Session->read('subp').".".$this->Session->read('proy').".".$this->Session->read('activ').".".$this->Session->read('cpar');
 		break;
		case 'especifica':
		  $this->set('SELECT','subespecifica');
		  $this->set('codigo','especifica');
		  $this->set('seleccion','');
		  $this->set('n',8);
		  $ano =  $this->Session->read('ano');
		  $cpar =  $this->Session->read('cpar');
		  $this->Session->write('cgen',$var);
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$cpar." and cod_generica=".$var;
		  $lista = $this->cfpd01_ano_especifica->generateList($cond2, 'cod_especifica ASC', null, '{n}.cfpd01_ano_especifica.cod_especifica', '{n}.cfpd01_ano_especifica.denominacion');
          $this->concatena($lista, 'vector');
          //echo $this->Session->read('sec').".".$this->Session->read('prog').".".$this->Session->read('subp').".".$this->Session->read('proy').".".$this->Session->read('activ').".".$this->Session->read('cpar').".".$this->Session->read('cgen');
		break;
		case 'subespecifica':
		  $this->set('SELECT','auxiliar');
		  $this->set('codigo','subespecifica');
		  $this->set('seleccion','');
		  $this->set('n',9);
		  $ano =  $this->Session->read('ano');
		  $cpar =  $this->Session->read('cpar');
		  $cgen =  $this->Session->read('cgen');
		  $var= $var=="" ? 0 : $var;
		  $this->Session->write('cesp',$var);

		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$var;
		  $lista=  $this->cfpd01_ano_sub_espec->generateList($cond2, 'cod_sub_espec ASC', null, '{n}.cfpd01_ano_sub_espec.cod_sub_espec', '{n}.cfpd01_ano_sub_espec.denominacion');
          $this->concatena($lista, 'vector');
         // echo $this->Session->read('sec').".".$this->Session->read('prog').".".$this->Session->read('subp').".".$this->Session->read('proy').".".$this->Session->read('activ').".".$this->Session->read('cpar').".".$this->Session->read('cgen').".".$this->Session->read('cesp');
		break;
		case 'auxiliar':
		  $this->set('SELECT','grilla');
		  $this->set('codigo','auxiliar');
		  $this->set('seleccion','');
		  $this->set('n',10);
		  $this->set('aux','si');
		  $this->Session->write('csesp',$var);
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('sec');
		  $prog =  $this->Session->read('prog');
		  $subp =  $this->Session->read('subp');
		  $proy =  $this->Session->read('proy');
		  $activ =  $this->Session->read('activ');
		  $cpar =  $this->Session->read('cpar2');
		  $cgen =  $this->Session->read('cgen');
		  $cesp =  $this->Session->read('cesp');
		  $cond2 ="ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$activ." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$var;
		  //echo $cond2;
		  $lista=  $this->cfpd05_auxiliar->generateList($cond2, 'cod_auxiliar ASC', null, '{n}.cfpd05_auxiliar.cod_auxiliar', '{n}.cfpd05_auxiliar.denominacion');
            if($lista!=null){
            	$this->concatena_aux($lista, 'vector');
            }else{
            	$this->set('vector',array('000'=>'0000'));
            }
		break;
		case 'grilla':
		  $this->set('SELECT','grilla');
		  $this->set('codigo','grilla');
		  $this->set('seleccion','');
		  $this->set('n',11);
		  $this->set('ver',true);
		  $this->set('no','no');
		  $this->Session->write('caux',$var);
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
*/


function select3($select=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";
	if($var!=null){
    $cond =$this->SQLCA();
	switch($select){
		case 'sector':
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
		case 'partida':
		  $this->set('SELECT','generica');
		  $this->set('codigo','partida');
		  $this->set('seleccion','');
		  $this->set('n',6);
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
		  $this->set('n',7);
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
		  $this->set('n',8);
		  $ano =  $this->Session->read('ano');
		  $cpar =  $this->Session->read('cpar');
		  $this->Session->write('cgen',$var);
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$cpar." and cod_generica=".$var;
		  $lista = $this->cfpd01_ano_especifica->generateList($cond2, 'cod_especifica ASC', null, '{n}.cfpd01_ano_especifica.cod_especifica', '{n}.cfpd01_ano_especifica.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'subespecifica':
		  $this->set('SELECT','auxiliar');
		  $this->set('codigo','subespecifica');
		  $this->set('seleccion2','seleccion2');
		  $this->set('seleccion','');
		  $this->set('n',9);
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


function validar($var){
	$this->layout = "ajax";
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
		  $cond2 = $cond." and cod_estado=".$cod_esta." and cod_organismo=".$cod_orga." and cod_municipio=".$cod_muni." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$csesp." and cod_auxiliar=".$var;
		//  echo $cond2;
		  $regis = $this->cfpd17_inversion_coordinada->findCount($cond2);
		  if($regis =='0'){//echo '1';
			echo "<script>";
		  		echo "document.getElementById('todo').disabled='';  ";
        	echo "</script>";
		  }elseif($regis !='0'){//echo '2';
				$this->set('errorMessage','CÓDIGO PRESUPUESTARIO YA ESTA REGISTRADO.');
			echo "<script>";
		  		echo "document.getElementById('todo').disabled='true';  ";
        	echo "</script>";
		  }
}


function guardar(){
	$this->layout = "ajax";//pr($this->data);
	if(!empty($this->data)){
	$k =  $this->Session->read('ano');
	$a=$this->data['cfpp17_inversion_coordinada']['cod_sector'];
	$b=$this->data['cfpp17_inversion_coordinada']['cod_programa'];
	$c=$this->data['cfpp17_inversion_coordinada']['cod_subprograma'];
	$d=$this->data['cfpp17_inversion_coordinada']['cod_proyecto'];
	$e=$this->data['cfpp17_inversion_coordinada']['cod_actividad'];
	$f=$this->data['cfpp17_inversion_coordinada']['cod_partida'];
	$f = $f >10 ? CE.$f : CE."0".$f ;
	$g=$this->data['cfpp17_inversion_coordinada']['cod_generica'];
	$h=$this->data['cfpp17_inversion_coordinada']['cod_especifica'];
	$i=$this->data['cfpp17_inversion_coordinada']['cod_subespecifica'];
	$j=isset($this->data['cfpp17_inversion_coordinada']['cod_auxiliar']) ? $this->data['cfpp17_inversion_coordinada']['cod_auxiliar'] : 0;
	$n=$this->Formato1($this->data['cfpp17_inversion_coordinada']['aporte_total']);
	if($j == null){
		$j=0;
	}
	$cod_estado 			= $this->data['cfpp17_inversion_coordinada']['cod_estado'];
	$cod_organismo 			= $this->data['cfpp17_inversion_coordinada']['cod_organismo'];
	$cod_municipio 			= $this->data['cfpp17_inversion_coordinada']['cod_municipio'];
	$aporte_municipio 		= $this->Formato1($this->data['cfpp17_inversion_coordinada']['aporte_municipio']);
	$aporte_organismo 		= $this->Formato1($this->data['cfpp17_inversion_coordinada']['aporte_organismo']);
	$aporte_gobernacion 	= $this->Formato1($this->data['cfpp17_inversion_coordinada']['aporte_gobernacion']);
	$a1=$this->verifica_SS(1);
	$a2=$this->verifica_SS(2);
	$a3=$this->verifica_SS(3);
	$a4=$this->verifica_SS(4);
	$a5=$this->verifica_SS(5);


//$sql_re  =" cod_presi=".$a1." and cod_entidad=".$a2." and cod_tipo_inst=".$a3." and cod_inst=".$a4." and cod_dep=".$a5." and ano=".$k;
//$sql_re .=" and cod_sector=".$a." and cod_programa=".$b." and cod_sub_prog=".$c." and cod_proyecto=".$d." and cod_activ_obra=".$e;
//$sql_re .=" and cod_partida=".$f." and cod_generica=".$g." and cod_especifica=".$h." and cod_sub_espec=".$i." and cod_auxiliar=".$j."";
//$sql_re .=" and cod_estado=".$cod_estado." and cod_organismo=".$cod_organismo." and cod_municipio=".$cod_municipio."";
//$regis = $this->cfpd17_inversion_coordinada->findCount($sql_re);



//if($regis =='0'){



	$SQL_INSERT ="INSERT INTO cfpd17_inversion_coordinada (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_estado, cod_organismo, cod_municipio, ano,
  cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica,
  cod_especifica, cod_sub_espec,cod_auxiliar, aporte_municipio, aporte_organismo, aporte_gobernacion)";
	$SQL_INSERT .=" VALUES ($a1, $a2, $a3, $a4, $a5, $cod_estado, $cod_organismo, $cod_municipio,$k, $a, $b, $c,
  							$d, $e, $f, $g, $h, $i, $j,
  							$aporte_municipio, $aporte_organismo, $aporte_gobernacion)";

	if($this->cfpd17_inversion_coordinada->execute($SQL_INSERT)>1){

//Inicio de lo que va para la tabla cfpd05
$sql_verificar  =" cod_presi=".$a1." and cod_entidad=".$a2." and cod_tipo_inst=".$a3." and cod_inst=".$a4." and cod_dep=".$a5." and ano=".$k;
$sql_verificar .=" and cod_sector=".$a." and cod_programa=".$b." and cod_sub_prog=".$c." and cod_proyecto=".$d." and cod_activ_obra=".$e;
$sql_verificar .=" and cod_partida=".$f." and cod_generica=".$g." and cod_especifica=".$h." and cod_sub_espec=".$i." and cod_auxiliar=".$j."";
$d31 = '1';
$n = $this->Formato1($this->data['cfpp17_inversion_coordinada']['aporte_total']);
if($this->cfpd05->findCount($sql_verificar)==0){
        if($f=='404'){$cod_tipo_gasto='2';}else if($f=='407'){$cod_tipo_gasto='4';}else{$cod_tipo_gasto='1';}
$SQLINSERT="INSERT INTO cfpd05 (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,
cod_tipo_gasto, tipo_presupuesto, asignacion_anual, aumento_traslado_anual, disminucion_traslado_anual, credito_adicional_anual, rebaja_anual,
compromiso_anual, causado_anual, pagado_anual, asignacion_ene, aumento_traslado_ene, disminucion_traslado_ene, credito_adicional_ene, rebaja_ene,
compromiso_ene, causado_ene, pagado_ene, asignacion_feb, aumento_traslado_feb, disminucion_traslado_feb, credito_adicional_feb, rebaja_feb,
compromiso_feb, causado_feb, pagado_feb, asignacion_mar, aumento_traslado_mar, disminucion_traslado_mar, credito_adicional_mar, rebaja_mar,
compromiso_mar, causado_mar, pagado_mar, asignacion_abr, aumento_traslado_abr, disminucion_traslado_abr, credito_adicional_abr, rebaja_abr,
compromiso_abr, causado_abr, pagado_abr, asignacion_may, aumento_traslado_may, disminucion_traslado_may, credito_adicional_may, rebaja_may,
compromiso_may, causado_may, pagado_may, asignacion_jun, aumento_traslado_jun, disminucion_traslado_jun, credito_adicional_jun, rebaja_jun,
compromiso_jun, causado_jun, pagado_jun, asignacion_jul, disminucion_traslado_jul, credito_adicional_jul, rebaja_jul, compromiso_jul,
causado_jul, pagado_jul, asignacion_ago, aumento_traslado_ago, disminucion_traslado_ago, credito_adicional_ago, rebaja_ago, compromiso_ago,
causado_ago, pagado_ago, asignacion_sep, aumento_traslado_sep, disminucion_traslado_sep, credito_adicional_sep, rebaja_sep, compromiso_sep,
causado_sep, pagado_sep, asignacion_oct, aumento_traslado_oct, disminucion_traslado_oct, credito_adicional_oct, rebaja_oct, compromiso_oct,
causado_oct, pagado_oct, asignacion_nov, aumento_traslado_nov, disminucion_traslado_nov, credito_adicional_nov, rebaja_nov, compromiso_nov,
causado_nov, pagado_nov, asignacion_dic, aumento_traslado_dic, disminucion_traslado_dic, credito_adicional_dic, rebaja_dic, compromiso_dic,
causado_dic, pagado_dic, precompromiso_congelado, precompromiso_requisicion, precompromiso_obras, precompromiso_fondo_avance) VALUES";
$SQLINSERT .="($a1,$a2,$a3,$a4,$a5,$k,$a,$b,$c,$d,$e,$f,$g,$h,$i,$j,$cod_tipo_gasto,$d31,$n,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)";
$this->cfpd05->execute($SQLINSERT);
}else{
$sql_verificar ="cod_presi=".$a1." and cod_entidad=".$a2." and cod_tipo_inst=".$a3." and cod_inst=".$a4." and cod_dep=".$a5." and ano=".$k;
$sql_verificar .=" and cod_sector=".$a." and cod_programa=".$b." and cod_sub_prog=".$c." and cod_proyecto=".$d." and cod_activ_obra=".$e;
$sql_verificar .=" and cod_partida=".$f." and cod_generica=".$g." and cod_especifica=".$h." and cod_sub_espec=".$i." and cod_auxiliar=".$j."";
        $data = $this->cfpd05->findAll($sql_verificar, null, null, null);
        foreach($data as $datos){$asignacion_anual = $datos['cfpd05']['asignacion_anual'];}
        	  $asignacion_anual = $asignacion_anual+$n;
              $sql = "UPDATE cfpd05 SET asignacion_anual=".$asignacion_anual."  where ".$sql_verificar;
              $this->cfpd05->execute($sql);
}//fin else

	}
    $this->set('Message_existe', 'Los datos fueron guardados con exito.');
//	}elseif($regis !='0'){
//		$this->set('errorMessage','CÓDIGO PRESUPUESTARIO YA ESTA REGISTRADO.');
//	}//fin si existe

	}else{
		$this->set('errorMessage', 'No Pudieron ser guardo los datos.');
}//fin si llegaron datos
				$this->index2();
				$this->render("index2");
}//fin function


function funcion(){
	$this->layout='ajax';
}


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



function concatena_aux($vector1=null, $nomVar=null, $extra=null){
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
					$y=($y!="")?$y:"N/A";
					$cod[$x] = '000'.$x.' - '.$y;
				}else if($x>=10 && $x<=99){
					$y=($y!="")?$y:"N/A";
					$cod[$x] = '00'.$x.' - '.$y;
				}else if($x > 99 && $x <= 999){
					$y=($y!="")?$y:"N/A";
					$cod[$x] = '0'.$x.' - '.$y;
				}else if($x > 999){
					$y=($y!="")?$y:"N/A";
					$cod[$x] = $x.' - '.$y;
				}
			}
		}
		//print_r($cod);
	}

	$this->set($nomVar, $cod);
}//fin function


function editar($id=null,$a1=null,$a2=null,$a3=null,$a4=null,$a5=null,$a6=null,$a7=null,$a8=null,$a9=null,$a10=null,$a11=null,$i=null){
	$this->layout = "ajax";
	$d = $this->cfpd06->findAll($this->SQLCA().' and cod_sector='.$a1.' and cod_programa='.$a2.' and cod_sub_prog='.$a3.' and cod_proyecto='.$a4.' and cod_activ_obra='.$a5.' and cod_partida='.$a6.' and cod_generica='.$a7.' and cod_especifica='.$a8.' and cod_sub_espec='.$a9.' and cod_auxiliar='.$a10.' and ano='.$a11.' and numero_linea='.$id);
	$reemplazo 		= $d[0]['cfpd06']['cantidad_reemplazo'];
	$deficiencia 	= $d[0]['cfpd06']['cantidad_deficiencia'];
	$total_equipos  = $reemplazo + $deficiencia;
	$costo_unitario	= $d[0]['cfpd06']['costo_unitario'];
	$total_monto 	= $total_equipos * $costo_unitario;
	$denominacion	= $d[0]['cfpd06']['denominacion'];
	if($reemplazo=='0'){
		$reemplazo='';
	}
	if($deficiencia=='0'){
		$deficiencia='';
	}
	$this->set('reemplazo',$reemplazo);
	$this->set('deficiencia',$deficiencia);
	$this->set('total_equipos',$total_equipos);
	$this->set('costo_unitario',$costo_unitario);
	$this->set('total_monto',$total_monto);
	$this->set('denominacion',$denominacion);
	$this->set('i',$i);
	$this->set('id',$id);
	$this->set('a1',$a1);
	$this->set('a2',$a2);
	$this->set('a3',$a3);
	$this->set('a4',$a4);
	$this->set('a5',$a5);
	$this->set('a6',$a6);
	$this->set('a7',$a7);
	$this->set('a8',$a8);
	$this->set('a9',$a9);
	$this->set('a10',$a10);
	$this->set('a11',$a11);
}//fin function



	function consultar($pagina=null,$ano=null){
 		$this->layout = "ajax";
 		if($ano==null){
			$ano = $this->data['cfpp17_inversion_coordinada']['presupuesto'];
 		}
		$cond = $this->SQLCA()." and ano=".$ano;
        if($pagina!=null){
        	$pagina=$pagina;
          	$this->set('pagina',$pagina);
          	$Tfilas=$this->v_cfpd17_inversion_coordinada->findCount($cond);
          	if($Tfilas==0){
          		$this->set('Message_existe', 'No se encontraron datos.');
				$this->index();
				$this->render("index");
          	}
          	if($Tfilas!=0){
          		$this->set('pag_cant',$pagina.'/'.$Tfilas);
          		$datos=$this->v_cfpd17_inversion_coordinada->findAll($cond,null,'cod_estado,cod_organismo,cod_municipio,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',1,$pagina,null);
          	 	$this->set('datos',$datos);
          	 	$this->set('siguiente',$pagina+1);
          	 	$this->set('anterior',$pagina-1);
             	$this->bt_nav($Tfilas,$pagina);
           	}
 		}else{
 			$pagina=1;
 			$this->set('pagina',$pagina);
          	$Tfilas=$this->v_cfpd17_inversion_coordinada->findCount($cond);
          	if($Tfilas==0){
          		$this->set('Message_existe', 'No se encontraron datos.');
				$this->index();
				$this->render("index");
          	}
          	if($Tfilas!=0){
          		$this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 	$datos=$this->v_cfpd17_inversion_coordinada->findAll($cond,null,'cod_estado,cod_organismo,cod_municipio,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',1,$pagina,null);
          	 	$this->set('datos',$datos);
          	 	$this->set('siguiente',$pagina+1);
          	 	$this->set('anterior',$pagina-1);
             	$this->bt_nav($Tfilas,$pagina);
			}
        }
}//fin function consultar2


function modificar($sector=null,$programa=null,$sub_pro=null,$proyecto=null,$actividad=null,$partida=null,$generica=null,$especifica=null,$sub_espe=null,$auxiliar=null,$ano=null,$estado=null,$organismo=null,$municipio=null,$pagina=null){
 	$this->layout = "ajax";
	$cond = $this->SQLCA()." and ano=".$ano;
    $this->set('pagina',$pagina);
   	$datos=$this->v_cfpd17_inversion_coordinada->findAll($cond." and cod_sector=$sector and cod_programa=$programa and cod_sub_prog=$sub_pro and cod_proyecto=$proyecto and cod_activ_obra=$actividad and cod_partida=$partida and cod_generica=$generica and cod_especifica=$especifica and cod_sub_espec=$sub_espe and cod_auxiliar=$auxiliar and cod_estado=$estado and cod_organismo=$organismo and cod_municipio=$municipio");
   	$this->set('datos',$datos);
}//fin function consultar2

function guardar_modificar($sector=null,$programa=null,$sub_pro=null,$proyecto=null,$actividad=null,$partida=null,$generica=null,$especifica=null,$sub_espe=null,$auxiliar=null,$ano=null,$estado=null,$organismo=null,$municipio=null,$pagina=null){
 	$this->layout = "ajax";
 	$aporte_municipio 	= $this->Formato1($this->data['cfpp17_inversion_coordinada']['aporte_municipio']);
 	$aporte_organismo	= $this->Formato1($this->data['cfpp17_inversion_coordinada']['aporte_organismo']);
 	$aporte_gobernacion 	= $this->Formato1($this->data['cfpp17_inversion_coordinada']['aporte_gobernacion']);
 	$monto 				= $this->Formato1($this->data['cfpp17_inversion_coordinada']['aporte_total']);
	$cond 		= $this->SQLCA()." and ano=".$ano;
    $this->set('pagina',$pagina);
   	$vali 		= $cond." and cod_sector=$sector and cod_programa=$programa and cod_sub_prog=$sub_pro and cod_proyecto=$proyecto and cod_activ_obra=$actividad and cod_partida=$partida and cod_generica=$generica and cod_especifica=$especifica and cod_sub_espec=$sub_espe and cod_auxiliar=$auxiliar and cod_estado=$estado and cod_organismo=$organismo and cod_municipio=$municipio";
   	$data2 		= $this->cfpd05->findAll($cond." and cod_sector=$sector and cod_programa=$programa and cod_sub_prog=$sub_pro and cod_proyecto=$proyecto and cod_activ_obra=$actividad and cod_partida=$partida and cod_generica=$generica and cod_especifica=$especifica and cod_sub_espec=$sub_espe and cod_auxiliar=$auxiliar", null, null, null);
	$data_sacar = $this->cfpd17_inversion_coordinada->findAll($vali);
	$monto_a	= $data_sacar[0]['cfpd17_inversion_coordinada']['aporte_municipio'];
	$monto_b	= $data_sacar[0]['cfpd17_inversion_coordinada']['aporte_organismo'];
	$monto_c	= $data_sacar[0]['cfpd17_inversion_coordinada']['aporte_gobernacion'];
	$monto_s 	= $monto_a + $monto_b + $monto_c;
	$asignacion	=$data2[0]['cfpd05']['asignacion_anual'];
	$asignacion = $asignacion - $monto_s;
	$asignacion = $asignacion + $monto;
	if($asignacion=='0'){
		//$upd05="DELETE FROM cfpd05 WHERE $cond and cod_sector=$sector and cod_programa=$programa and cod_sub_prog=$sub_pro and cod_proyecto=$proyecto and cod_activ_obra=$actividad and cod_partida=$partida and cod_generica=$generica and cod_especifica=$especifica and cod_sub_espec=$sub_espe and cod_auxiliar=$auxiliar";
		$upd05="UPDATE cfpd05 set asignacion_anual=$asignacion WHERE $cond and cod_sector=$sector and cod_programa=$programa and cod_sub_prog=$sub_pro and cod_proyecto=$proyecto and cod_activ_obra=$actividad and cod_partida=$partida and cod_generica=$generica and cod_especifica=$especifica and cod_sub_espec=$sub_espe and cod_auxiliar=$auxiliar";
	}else{
		$upd05="UPDATE cfpd05 set asignacion_anual=$asignacion WHERE $cond and cod_sector=$sector and cod_programa=$programa and cod_sub_prog=$sub_pro and cod_proyecto=$proyecto and cod_activ_obra=$actividad and cod_partida=$partida and cod_generica=$generica and cod_especifica=$especifica and cod_sub_espec=$sub_espe and cod_auxiliar=$auxiliar";
	}
   	$upd16="UPDATE cfpd17_inversion_coordinada set aporte_municipio=$aporte_municipio, aporte_organismo=$aporte_organismo, aporte_gobernacion=$aporte_gobernacion  WHERE $vali";
   	$sw1 = $this->cfpd05->execute($upd05);
   	if($sw1>1){
   	$sw2 = $this->cfpd17_inversion_coordinada->execute($upd16);
   		if($sw2>1){
   			$this->set('Message_existe', 'Registro Modificado con exito.');
   			$this->consultar($pagina,$ano);
   			$this->render("consultar");
  		}else if ($sw2 <= 1){
  			$this->set('Message_existe', 'Disculpe, El Registro no fue Modificado.');
  			$this->consultar($pagina,$ano);
  			$this->render("consultar");
		}
   	}else{
   		$this->set('Message_existe', 'Disculpe, El Registro no fue Modificado.');
  		$this->consultar($pagina,$ano);
  		$this->render("consultar");
   	}
}//fin function consultar2

function eliminar($sector=null,$programa=null,$sub_pro=null,$proyecto=null,$actividad=null,$partida=null,$generica=null,$especifica=null,$sub_espe=null,$auxiliar=null,$ano=null,$estado=null,$organismo=null,$municipio=null,$pagina=null){
 	$this->layout = "ajax";
	$cond 		= $this->SQLCA()." and ano=".$ano;
    $this->set('pagina',$pagina);
   	$vali 		= $cond." and cod_sector=$sector and cod_programa=$programa and cod_sub_prog=$sub_pro and cod_proyecto=$proyecto and cod_activ_obra=$actividad and cod_partida=$partida and cod_generica=$generica and cod_especifica=$especifica and cod_sub_espec=$sub_espe and cod_auxiliar=$auxiliar and cod_estado=$estado and cod_organismo=$organismo and cod_municipio=$municipio";
   	$data2 		= $this->cfpd05->findAll($cond." and cod_sector=$sector and cod_programa=$programa and cod_sub_prog=$sub_pro and cod_proyecto=$proyecto and cod_activ_obra=$actividad and cod_partida=$partida and cod_generica=$generica and cod_especifica=$especifica and cod_sub_espec=$sub_espe and cod_auxiliar=$auxiliar", null, null, null);
	$data_sacar = $this->cfpd17_inversion_coordinada->findAll($vali);
	$monto_a	= $data_sacar[0]['cfpd17_inversion_coordinada']['aporte_municipio'];
	$monto_b	= $data_sacar[0]['cfpd17_inversion_coordinada']['aporte_organismo'];
	$monto_c	= $data_sacar[0]['cfpd17_inversion_coordinada']['aporte_gobernacion'];
	$monto_s 	= $monto_a + $monto_b + $monto_c;
	$asignacion	= $data2[0]['cfpd05']['asignacion_anual'];
	$asignacion = $asignacion - $monto_s;
	if($asignacion=='0'){
		$upd05="DELETE FROM cfpd05 WHERE $cond and cod_sector=$sector and cod_programa=$programa and cod_sub_prog=$sub_pro and cod_proyecto=$proyecto and cod_activ_obra=$actividad and cod_partida=$partida and cod_generica=$generica and cod_especifica=$especifica and cod_sub_espec=$sub_espe and cod_auxiliar=$auxiliar";
		//$upd05="UPDATE cfpd05 set asignacion_anual=$asignacion WHERE $cond and cod_sector=$sector and cod_programa=$programa and cod_sub_prog=$sub_pro and cod_proyecto=$proyecto and cod_activ_obra=$actividad and cod_partida=$partida and cod_generica=$generica and cod_especifica=$especifica and cod_sub_espec=$sub_espe and cod_auxiliar=$auxiliar";
	}else{
		$upd05="UPDATE cfpd05 set asignacion_anual=$asignacion WHERE $cond and cod_sector=$sector and cod_programa=$programa and cod_sub_prog=$sub_pro and cod_proyecto=$proyecto and cod_activ_obra=$actividad and cod_partida=$partida and cod_generica=$generica and cod_especifica=$especifica and cod_sub_espec=$sub_espe and cod_auxiliar=$auxiliar";
	}
   	$upd16="DELETE FROM cfpd17_inversion_coordinada   WHERE $vali";
   	$sw1 = $this->cfpd05->execute($upd05);
   	if($sw1>1){
   	$sw2 = $this->cfpd17_inversion_coordinada->execute($upd16);
   		if($sw2>1){
   			$y=$this->cfpd17_inversion_coordinada->findCount($this->SQLCA().' and ano='.$ano);
   			$this->set('Message_existe', 'Registro eliminado con exito.');
   			if($y!='0'){
	  			if($pagina!=1){
	  				$this->set('errorMessage', 'El registro fue eliminado');
	  				$this->consultar($pagina-1,$ano);
      				$this->render("consultar");
      			}else{
      				$this->set('errorMessage', 'El registro fue eliminado');
      	 			$this->consultar($pagina,$ano);//si es el primero solamente
        			$this->render("consultar");
      			}
			}else if($y=='0'){
				$this->set('errorMessage', 'El registro fue eliminado');
				$this->index();
				$this->render("index");
			}//fin if
   		}else{
   		$this->set('Message_existe', 'Disculpe, El Registro no fue eliminado.');
  		$this->consultar($pagina,$ano);
  		$this->render("consultar");
   	}
   	}else{
   		$this->set('Message_existe', 'Disculpe, El Registro no fue eliminado.');
  		$this->consultar($pagina,$ano);
  		$this->render("consultar");
   	}
}//fin function consultar2


 }//fin clase cfpp06Controller

 ?>