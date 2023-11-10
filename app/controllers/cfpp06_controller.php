<?php
/*
 * Creado el  02/10/2007 a las 12:35:41 PM
 *
 * Por: Miguelangel Cabrera
 *
 *  SISAP - En EasyEclipse for PHP
 *
 * Prov 17:17
 *
 */
class Cfpp06Controller extends AppController{

     var $uses = array('cfpd06', 'cfpd02_sector', 'cfpd02_programa', 'cfpd05', 'cfpd05_auxiliar', 'cfpd02_sub_prog', 'cfpd02_proyecto', 'cfpd02_activ_obra', 'cfpd01_ano_grupo', 'cfpd01_ano_partida', 'cfpd01_ano_generica', 'cfpd01_ano_especifica', 'cfpd01_ano_sub_espec', 'cfpd01_ano_auxiliar', 'cfpd01_formulacion');
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
	$ano_formulacion=$this->data['cfpp06']['presupuesto'];
	for($minCount = 2007; $minCount < 2030; $minCount++) {
    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
    $this->set('anos',$anos);
    $this->set('ano_formulacion',$ano_formulacion);
    	$this->Session->write('ano',$ano_formulacion);
	}

	$listaSector=$this->cfpd02_sector->generateList($this->SQLCA(), 'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.denominacion');
	$this->concatena($listaSector, 'vector');
	          echo "<script>";
		  		echo "document.getElementById('ejercicio').disabled='true';  ";
        	echo "</script>";
}//fin index2

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

		  $this->set('SELECT','seleccion');
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
                       	     echo "<script type='text/javascript'>ver_documento('/cfpp06/seleccion/','grilla');</script>";
							$this->concatena_aux($lista, 'vector');
						}else{
                            echo "<script type='text/javascript'>ver_documento('/cfpp06/seleccion/si/0','grilla');</script>";
							$this->set('vector',array('0'=>'0000'));
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




/*
function mostrar3($select=null,$var=null) { //mostrar3 codigos presupuestarios
	$this->layout = "ajax";
if( $var!=null){
    $cond = $this->SQLCA();
    $cond2 = $this->SQLCA();
	switch($select){
		case 'sector':
		  $ano =  $this->Session->read('ano');
		  $this->Session->write('dsec',$var);
		  $cond .=" and ano=".$ano." and cod_sector=".$var;
		  $a=  $this->cfpd02_sector->findAll($cond);
          echo $a[0]['cfpd02_sector']['denominacion'];
		break;
		case 'programa':
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('dsec');
		  $this->Session->write('dprog',$var);
		  $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$var;
		  $a=  $this->cfpd02_programa->findAll($cond);
          echo $a[0]['cfpd02_programa']['denominacion'];
		break;
		case 'subprograma':
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('dsec');
		  $prog =  $this->Session->read('dprog');
		  $this->Session->write('dsubprog',$var);
		  $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$var;
		  $a=  $this->cfpd02_sub_prog->findAll($cond);
          echo $a[0]['cfpd02_sub_prog']['denominacion'];
		break;
		case 'proyecto':
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('dsec');
		  $prog =  $this->Session->read('dprog');
		  $subprog =  $this->Session->read('dsubprog');
		  $this->Session->write('dproy',$var);
		  $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subprog." and cod_proyecto=".$var;
		  $a=  $this->cfpd02_proyecto->findAll($cond);
          echo $a[0]['cfpd02_proyecto']['denominacion'];
		break;
		case 'actividad':
		  $ano =  $this->Session->read('ano');
		  $sec =  $this->Session->read('dsec');
		  $prog =  $this->Session->read('dprog');
		  $subprog =  $this->Session->read('dsubprog');
		  $proy =  $this->Session->read('dproy');
		  $this->Session->write('activ',$var);
		  $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subprog." and cod_proyecto=".$proy." and cod_activ_obra=".$var;
		  $a=  $this->cfpd02_activ_obra->findAll($cond);
         echo $a[0]['cfpd02_activ_obra']['denominacion'];
		break;
		case 'partida':
		  $ano =  $this->Session->read('ano');
		  $this->Session->write('dpar',$var);
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$var;
		  $a=  $this->cfpd01_ano_partida->findAll($cond2);
          echo $a[0]['cfpd01_ano_partida']['denominacion'];
		break;
		case 'generica':
		  $ano =  $this->Session->read('ano');
		  $dpar=  $this->Session->read('dpar');
		  $this->Session->write('dgen',$var);
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$dpar." and cod_generica=".$var;
		  $a=  $this->cfpd01_ano_generica->findAll($cond2);
          echo $a[0]['cfpd01_ano_generica']['denominacion'];
		break;
		case 'especifica':
		   $ano =  $this->Session->read('ano');
		  $dpar=  $this->Session->read('dpar');
		  $dgen =  $this->Session->read('dgen');
		  $this->Session->write('desp',$var);
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$dpar." and cod_generica=".$dgen." and cod_especifica=".$var;
		  $a=  $this->cfpd01_ano_especifica->findAll($cond2);
          echo $a[0]['cfpd01_ano_especifica']['denominacion'];
		break;
		case 'subespecifica':
		  $ano =  $this->Session->read('ano');
		  $dpar=  $this->Session->read('dpar');
		  $dgen =  $this->Session->read('dgen');
		  $desp =  $this->Session->read('desp');
		  $this->Session->write('dsubesp',$var);
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$dpar." and cod_generica=".$dgen." and cod_especifica=".$desp." and cod_sub_espec=".$var;
		  $a=  $this->cfpd01_ano_sub_espec->findAll($cond2);
          echo $a[0]['cfpd01_ano_sub_espec']['denominacion'];
		break;
		case 'auxiliar':
		  $sec =  $this->Session->read('dsec');
		  $prog =  $this->Session->read('dprog');
		  $subprog =  $this->Session->read('dsubprog');
		  $proy =  $this->Session->read('dproy');
		  $activ = $this->Session->read('activ');
		  $ano =  $this->Session->read('ano');
		  $dpar=  $this->Session->read('dpar');
		  $dpar = $dpar < 10 ? CE."0".$dpar : CE.$dpar;
		  $dgen =  $this->Session->read('dgen');
		  $desp =  $this->Session->read('desp');
		  $dsubesp =  $this->Session->read('dsubesp');
		  $cond2 .="and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subprog." and cod_proyecto=".$proy." and cod_activ_obra=".$activ." and cod_partida=".$dpar." and cod_generica=".$dgen." and cod_especifica=".$desp." and cod_sub_espec=".$dsubesp." and cod_auxiliar=".$var;
		  //$cond2 .="and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subprog." and cod_proyecto=".$proy." and cod_activ_obra=".$activ." and cod_partida=".$dpar." and cod_generica=".$dgen." and cod_especifica=".$desp." and cod_sub_espec=".$dsubesp;
		  //echo $cond2;
		  $a=  $this->cfpd05_auxiliar->findAll($cond2);
		  //pr($a);
          echo $a[0]['cfpd05_auxiliar']['denominacion'];
		break;
	}//fin wsitch
	}else{
		echo "";
	}
}//fin mostrar3 codigos presupuestarios
*/

function consultar($ejercicio=null, $pag_num=null) {
 		$this->layout = "ajax";
 		$this->set('entidadFederal',$this->verifica_SS(6));


	  if($ejercicio!=null){
	  	    $this->set('ejercicio', $ejercicio);
		}else if($this->data['cfpp06']['ano']){
							$this->set('ejercicio', $this->data['cfpp06']['ano']);
							$ejercicio = $this->data['cfpp06']['ano'];
							$ano = $this->data['cfpp06']['ano'];
                            $this->Session->write('ano_r',$ano);
							}else{
					        $ejercicio = $this->Session->read('ano_r');
							$ano = $this->Session->read('ano_r');
							$this->set('ejercicio', $ejercicio );

							}
			  	$tc=$this->cfpd09->findCount($this->SQLCA($ejercicio));
			  if($tc!=0){
			   $DATOS_res = $this->cfpd09->findAll('cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano='.$ejercicio.'', null, 'cod_auxiliar ASC', null, null, null);

			  }else{
			  	$this->index2($ejercicio);
	    		$this->render("index2");
			  }
			   $DATOS_res = $this->cfpd09->findAll('cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano='.$ejercicio.'', null, 'cod_auxiliar ASC', null, null, null);
			   $this->set('sector',$this->cfpd02_sector->findAll('cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano='.$ejercicio,null,null,null,null,null));
			   $this->set('programa',$this->cfpd02_programa->findAll('cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano='.$ejercicio,null,null,null,null,null));
			   $this->set('subprograma',$this->cfpd02_sub_prog->findAll('cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano='.$ejercicio,null,null,null,null,null));
			   $this->set('proyecto',$this->cfpd02_proyecto->findAll('cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano='.$ejercicio,null,null,null,null,null));
			   $this->set('actividad',$this->cfpd02_activ_obra->findAll('cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano='.$ejercicio,null,null,null,null,null));
			   $this->set('partida',$this->cfpd01_ano_partida->findAll('ejercicio='.$ejercicio.' and cod_grupo= '.CE.'',null,null,null, null, null));
			   $this->set('generica',$this->cfpd01_ano_generica->findAll('ejercicio='.$ejercicio.' and cod_grupo= '.CE.'',null,null,null, null, null));
			   $this->set('especifica',$this->cfpd01_ano_especifica->findAll('ejercicio='.$ejercicio.' and cod_grupo= '.CE.'',null,null,null, null, null));
			   $this->set('subespecifica',$this->cfpd01_ano_sub_espec->findAll('ejercicio='.$ejercicio.' and cod_grupo= '.CE.'',null,null,null, null, null));
			    $this->set('auxiliar',$this->cfpd05_auxiliar->findAll('ano='.$ejercicio.'',null,null,null, null, null));
			         $this->set('DATOS',$DATOS_res);



$this->set('ejercicio', $ejercicio);
if($pag_num!=null){$this->set('pagina_actual', $pag_num);  }


 }//fin function consultar


function consultar2($con_ano=null,$pagina=null) {
 		$this->layout = "ajax";
         if($pagina!=null && $con_ano!=null){
          	 $pagina=$pagina;
          	 $Ano=$con_ano;
          	 $ejercicio=$con_ano;
          	 $this->set('ano',$Ano);
          	 $this->set('ejercicio', $Ano);
          	 $Tfilas=$this->cfpd06->findCount($this->SQLCA($Ano));
          	 //echo $Tfilas;
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'No se encontrar&oacute;n datos para el a&ntilde;o '.$Ano);
          	 	$this->index2($Ano);
          		$this->render("index2");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datacfpd06=$this->cfpd06->findAll($this->SQLCA($Ano),array('cod_sector','cod_programa','cod_sub_prog','cod_proyecto','cod_activ_obra','cod_partida','cod_generica','cod_especifica','cod_sub_espec','cod_auxiliar','numero_linea', 'ano', 'cantidad_reemplazo', 'cantidad_deficiencia', 'costo_unitario', 'denominacion'),'numero_linea DESC',1,$pagina,null);

          	 foreach ($datacfpd06 as $YYY);
                 //$YYY['cfpd06']['cod_sector'];

          	   $this->set('sector',$this->cfpd02_sector->findAll($this->SQLCA().' and cod_sector='.$YYY['cfpd06']['cod_sector'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('programa',$this->cfpd02_programa->findAll($this->SQLCA().' and cod_programa='.$YYY['cfpd06']['cod_programa'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('subprograma',$this->cfpd02_sub_prog->findAll($this->SQLCA().' and cod_sub_prog='.$YYY['cfpd06']['cod_sub_prog'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('proyecto',$this->cfpd02_proyecto->findAll($this->SQLCA().' and cod_proyecto='.$YYY['cfpd06']['cod_proyecto'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('actividad',$this->cfpd02_activ_obra->findAll($this->SQLCA().' and cod_activ_obra='.$YYY['cfpd06']['cod_activ_obra'].' and ano='.$ejercicio,null,null,null,null,null));
			   //$this->set('grupo',$this->cfpd01_ano_grupo->findAll());
			   $this->set('partida',$this->cfpd01_ano_partida->findAll('cod_partida='.substr($YYY['cfpd06']['cod_partida'],-2).' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
			   $this->set('generica',$this->cfpd01_ano_generica->findAll('cod_generica='.$YYY['cfpd06']['cod_generica'].' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
			   $this->set('especifica',$this->cfpd01_ano_especifica->findAll('cod_especifica='.$YYY['cfpd06']['cod_especifica'].' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
			   $this->set('subespecifica',$this->cfpd01_ano_sub_espec->findAll('cod_sub_espec='.$YYY['cfpd06']['cod_sub_espec'].' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
               $cp= $YYY['cfpd06']['cod_partida'];
               //$cp = $cp < 10 ? str_replace("0","", $cp) : $cp;

               $this->set('auxiliar',$this->cfpd05_auxiliar->findAll($this->SQLCA($ejercicio).' and cod_sector='.$YYY['cfpd06']['cod_sector'].' and cod_programa='.$YYY['cfpd06']['cod_programa'].' and cod_sub_prog='.$YYY['cfpd06']['cod_sub_prog'].' and cod_proyecto='.$YYY['cfpd06']['cod_proyecto'].' and cod_activ_obra='.$YYY['cfpd06']['cod_activ_obra'].' and cod_partida='.$cp.' and cod_generica='.$YYY['cfpd06']['cod_generica'].' and cod_especifica='.$YYY['cfpd06']['cod_especifica'].' and cod_sub_espec='.$YYY['cfpd06']['cod_sub_espec'].' and cod_auxiliar='.$YYY['cfpd06']['cod_auxiliar'],null,null,null, null, null));

               // print_r($datacfpd06);
          	 $this->set('DATOS',$datacfpd06);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);

            $this->bt_nav($Tfilas,$pagina);
          	}else{
	 	       $this->set('DATAcfpd06','');
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos para el a&ntilde;o '.$Ano);
	 	       //echo "sssss";
	         }///fin else  del if-else que compara las Tfilas
          }else{
          	$pagina=1;
          	$Ano=$this->data['cfpp06']['ano'];
          	$ejercicio=$Ano;
          	$this->set('ejercicio', $Ano);
          	$this->set('ano',$Ano);
          	$Tfilas=$this->cfpd06->findCount($this->SQLCA($Ano));
          	if($Tfilas==0){
          		$this->set('errorMessage', 'No se encontrar&oacute;n datos para el a&ntilde;o '.$Ano);
          	 	$this->index2($Ano);
          		$this->render("index2");
          	 }
             if($Tfilas!=0){
             	$tc=$this->cfpd06->findCount($this->SQLCA($ejercicio));

          	$this->set('pag_cant',$pagina.'/'.$Tfilas);
            $datacfpd06=$this->cfpd06->findAll($this->SQLCA($Ano),array('cod_sector','cod_programa','cod_sub_prog','cod_proyecto','cod_activ_obra','cod_partida','cod_generica','cod_especifica','cod_sub_espec','cod_auxiliar','numero_linea', 'ano', 'cantidad_reemplazo', 'cantidad_deficiencia', 'costo_unitario', 'denominacion'),'numero_linea DESC',1,$pagina,null);

              foreach ($datacfpd06 as $YYY);
                  //$YYY['cfpd06']['cod_sector'];

               $this->set('sector',$this->cfpd02_sector->findAll($this->SQLCA().' and cod_sector='.$YYY['cfpd06']['cod_sector'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('programa',$this->cfpd02_programa->findAll($this->SQLCA().' and cod_programa='.$YYY['cfpd06']['cod_programa'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('subprograma',$this->cfpd02_sub_prog->findAll($this->SQLCA().' and cod_sub_prog='.$YYY['cfpd06']['cod_sub_prog'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('proyecto',$this->cfpd02_proyecto->findAll($this->SQLCA().' and cod_proyecto='.$YYY['cfpd06']['cod_proyecto'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('actividad',$this->cfpd02_activ_obra->findAll($this->SQLCA().' and cod_activ_obra='.$YYY['cfpd06']['cod_activ_obra'].' and ano='.$ejercicio,null,null,null,null,null));
			   //$this->set('grupo',$this->cfpd01_ano_grupo->findAll());
			   $this->set('partida',$this->cfpd01_ano_partida->findAll('cod_partida='.substr($YYY['cfpd06']['cod_partida'],-2).' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
			   $this->set('generica',$this->cfpd01_ano_generica->findAll('cod_generica='.$YYY['cfpd06']['cod_generica'].' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
			   $this->set('especifica',$this->cfpd01_ano_especifica->findAll('cod_especifica='.$YYY['cfpd06']['cod_especifica'].' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
			   $this->set('subespecifica',$this->cfpd01_ano_sub_espec->findAll('cod_sub_espec='.$YYY['cfpd06']['cod_sub_espec'].' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
               $cp= $YYY['cfpd06']['cod_partida'];
               //$cp = $cp < 10 ? str_replace("0","", $cp) : $cp;
              // echo $this->SQLCA($ejercicio).' and cod_sector='.$YYY['cfpd06']['cod_sector'].' and cod_programa='.$YYY['cfpd06']['cod_programa'].' and cod_sub_prog='.$YYY['cfpd06']['cod_sub_prog'].' and cod_proyecto='.$YYY['cfpd06']['cod_proyecto'].' and cod_activ_obra='.$YYY['cfpd06']['cod_activ_obra'].' and cod_partida='.$cp.' and cod_generica='.$YYY['cfpd06']['cod_generica'].' and cod_especifica='.$YYY['cfpd06']['cod_especifica'].' and cod_sub_espec='.$YYY['cfpd06']['cod_sub_espec'].' and cod_auxiliar='.$YYY['cfpd06']['cod_auxiliar'];
               $this->set('auxiliar',$this->cfpd05_auxiliar->findAll($this->SQLCA($ejercicio).' and cod_sector='.$YYY['cfpd06']['cod_sector'].' and cod_programa='.$YYY['cfpd06']['cod_programa'].' and cod_sub_prog='.$YYY['cfpd06']['cod_sub_prog'].' and cod_proyecto='.$YYY['cfpd06']['cod_proyecto'].' and cod_activ_obra='.$YYY['cfpd06']['cod_activ_obra'].' and cod_partida='.$cp.' and cod_generica='.$YYY['cfpd06']['cod_generica'].' and cod_especifica='.$YYY['cfpd06']['cod_especifica'].' and cod_sub_espec='.$YYY['cfpd06']['cod_sub_espec'].' and cod_auxiliar='.$YYY['cfpd06']['cod_auxiliar'],null,null,null, null, null));
              // echo $this->SQLCA($ejercicio).' and cod_sector='.$YYY['cfpd06']['cod_sector'].' and cod_programa='.$YYY['cfpd06']['cod_programa'].' and cod_sub_prog='.$YYY['cfpd06']['cod_sub_prog'].' and cod_proyecto='.$YYY['cfpd06']['cod_proyecto'].' and cod_activ_obra='.$YYY['cfpd06']['cod_activ_obra'].' and cod_partida='.$cp.' and cod_generica='.$YYY['cfpd06']['cod_generica'].' and cod_especifica='.$YYY['cfpd06']['cod_especifica'].' and cod_sub_espec='.$YYY['cfpd06']['cod_sub_espec'].' and cod_auxiliar='.$YYY['cfpd06']['cod_auxiliar'];
          	$this->set('DATOS',$datacfpd06);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	 $this->bt_nav($Tfilas,$pagina);
            }else{
	 	       $this->set('DATOS','');
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos para el a&ntilde;o '.$Ano);
	 	       //echo "sssss";
	         }///fin else  del if-else que compara las Tfilas
          }//fin else

 }//fin function consultar2

function seleccion($op=null, $var=null){


$this->layout = "ajax";

//echo 'var '.$op;

          $this->Session->write('aux',$var);

          $ano  =  $this->Session->read('ano');
		  $sec  =  $this->Session->read('sec');
		  $prog =  $this->Session->read('prog');
		  $subp =  $this->Session->read('subp');
		  $proy =  $this->Session->read('proy');
		  $actv =  $this->Session->read('actv');
		  $cpar  = $this->Session->read('cpar');
		  $cpar  = $cpar < 10 ? CE."0".$cpar : CE.$cpar;
		  $cgen  = $this->Session->read('cgen');
		  $cesp  = $this->Session->read('cesp');
		  $c_sub = $this->Session->read('dsubesp');
		  $aux   = $this->Session->read('aux');
		  $cond  = $this->condicion()." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$actv;
		  $cond2 = $cond."and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$c_sub." and cod_auxiliar=".$aux;

//echo $cond2;

if($op=="si" && $var!=null){
	//echo 'siiii';
	$b=$this->cfpd06->findAll($cond2,null,"numero_linea ASC",null, null, null);
//	pr($b);
	$this->set('datos',$this->cfpd06->findAll($cond2,null,"numero_linea ASC",null, null, null));
}else{
	$this->set('datos',array());
}


}//fin function




function guardar(){
	$this->layout = "ajax";
	if(!empty($this->data)){
	$k =  $this->Session->read('ano');
	$a=$this->data['cfpp06']['cod_sector'];
	$b=$this->data['cfpp06']['cod_programa'];
	$c=$this->data['cfpp06']['cod_subprograma'];
	$d=$this->data['cfpp06']['cod_proyecto'];
	$e=$this->data['cfpp06']['cod_actividad'];
	$f=$this->data['cfpp06']['cod_partida'];
	$f = $f >10 ? CE.$f : CE."0".$f ;
	$g=$this->data['cfpp06']['cod_generica'];
	$h=$this->data['cfpp06']['cod_especifica'];
	$i=$this->data['cfpp06']['cod_subespecifica'];
	$j=isset($this->data['cfpp06']['cod_auxiliar']) ? $this->data['cfpp06']['cod_auxiliar'] : 0;
	$l=$this->data['cfpp06']['reemplazo'];
	if($l==null){
		$l=0;
	}
	$m=$this->data['cfpp06']['deficiencia'];
	if($m==null){
		$m=0;
	}
	$n=$this->Formato1($this->data['cfpp06']['costo_unitario']);
	$o=$this->data['cfpp06']['descripcion'];
	if($j == null){
		$j=0;
	}

	$a1=$this->verifica_SS(1);
	$a2=$this->verifica_SS(2);
	$a3=$this->verifica_SS(3);
	$a4=$this->verifica_SS(4);
	$a5=$this->verifica_SS(5);
	$SQL_INSERT ="INSERT INTO cfpd06 (cod_presi, cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_partida,cod_activ_obra,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,cantidad_reemplazo,cantidad_deficiencia,costo_unitario,denominacion)";
	$SQL_INSERT .=" VALUES (".$a1.",".$a2.",".$a3.",".$a4.",".$a5.",".$k.",".$a.",".$b.",".$c.",".$d.",".$f.",".$e.",".$g.",".$h.",".$i.",".$j.",'".$l."','".$m."',".$n.", '".$o."')";

	if($this->cfpd06->execute($SQL_INSERT)>1){

//Inicio de lo que va para la tabla cfpd05
$sql_verificar  =" cod_presi=".$a1." and cod_entidad=".$a2." and cod_tipo_inst=".$a3." and cod_inst=".$a4." and cod_dep=".$a5." and ano=".$k;
$sql_verificar .=" and cod_sector=".$a." and cod_programa=".$b." and cod_sub_prog=".$c." and cod_proyecto=".$d." and cod_activ_obra=".$e;
$sql_verificar .=" and cod_partida=".$f." and cod_generica=".$g." and cod_especifica=".$h." and cod_sub_espec=".$i." and cod_auxiliar=".$j."";
$d31 = '1';
$n = $this->Formato1($this->data['cfpp06']['total_costo']);
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


         $this->set('Message_existe', 'Los datos fueron guardados con exito.');
	}else{
		$this->set('errorMessage', 'No Pudieron ser guardo los datos.');
	}

   echo "<script>" .
		"document.getElementById('reemplazo').value='';" .
		"document.getElementById('deficiencia').value='';" .
		"document.getElementById('costo_unitario').value='';" .
		"document.getElementById('total_equipos').value='';" .
		"document.getElementById('total_costo').value='';" .
		"document.getElementById('descripcion').value='';" .
		"</script>";
$ver  =" cod_presi=".$a1." and cod_entidad=".$a2." and cod_tipo_inst=".$a3." and cod_inst=".$a4." and cod_dep=".$a5." and ano=".$k;
$ver .=" and cod_sector=".$a." and cod_programa=".$b." and cod_sub_prog=".$c." and cod_proyecto=".$d." and cod_activ_obra=".$e;
$ver .=" and cod_partida=".$f." and cod_generica=".$g." and cod_especifica=".$h." and cod_sub_espec=".$i." and cod_auxiliar=".$j."";
$datos = $this->cfpd06->findAll($ver);
$this->set('datos',$datos);

}//fin guardar

}//fin function

function guardar2($j){
	$this->layout='ajax';
	$k =  $this->Session->read('ano');
	$a =  $this->Session->read('sec');
	$b =  $this->Session->read('prog');
	$c =  $this->Session->read('subp');
	$d =  $this->Session->read('proy');
	$e =  $this->Session->read('activ');
	$f =  $this->Session->read('cpar2');
	$g =  $this->Session->read('cgen');
	$h =  $this->Session->read('cesp');
	$i =  $this->Session->read('csesp');
	$a1=$this->verifica_SS(1);
	$a2=$this->verifica_SS(2);
	$a3=$this->verifica_SS(3);
	$a4=$this->verifica_SS(4);
	$a5=$this->verifica_SS(5);
	//$j =  $this->Session->read('caux');
	$ver  =" cod_presi=".$a1." and cod_entidad=".$a2." and cod_tipo_inst=".$a3." and cod_inst=".$a4." and cod_dep=".$a5." and ano=".$k;
	$ver .=" and cod_sector=".$a." and cod_programa=".$b." and cod_sub_prog=".$c." and cod_proyecto=".$d." and cod_activ_obra=".$e;
	$ver .=" and cod_partida=".$f." and cod_generica=".$g." and cod_especifica=".$h." and cod_sub_espec=".$i." and cod_auxiliar=".$j."";
	//echo $ver;
	$datos = $this->cfpd06->findAll($ver);
	$this->set('datos',$datos);
}

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



function modificar($var,$ejercicio){
       $this->layout = "ajax";
       $cond ="numero_linea=".$var;
	   $a=  $this->cfpd06->findAll($cond,array('cod_sector','cod_programa','cod_sub_prog','cod_proyecto','cod_activ_obra','cod_partida','cod_generica','cod_especifica','cod_sub_espec','cod_auxiliar','numero_linea', 'ano', 'cantidad_reemplazo', 'cantidad_deficiencia', 'costo_unitario', 'denominacion'));
	 foreach ($a as $YYY);
	    $this->set('sector',$this->cfpd02_sector->findAll($this->SQLCA().' and ano='.$ejercicio.' and cod_sector='.$YYY['cfpd06']['cod_sector'].'' ,null,null,null,null,null));
			   $this->set('programa',$this->cfpd02_programa->findAll($this->SQLCA().' and ano='.$ejercicio.' and cod_programa='.$YYY['cfpd06']['cod_programa'].'',null,null,null,null,null));
			   $this->set('subprograma',$this->cfpd02_sub_prog->findAll($this->SQLCA().' and ano='.$ejercicio.' and cod_sub_prog='.$YYY['cfpd06']['cod_sub_prog'].'',null,null,null,null,null));
			   $this->set('proyecto',$this->cfpd02_proyecto->findAll($this->SQLCA().' and ano='.$ejercicio.' and cod_proyecto='.$YYY['cfpd06']['cod_proyecto'].'',null,null,null,null,null));
			   $this->set('actividad',$this->cfpd02_activ_obra->findAll($this->SQLCA().' and ano='.$ejercicio.' and cod_activ_obra='.$YYY['cfpd06']['cod_activ_obra'].'',null,null,null,null,null));
			   //$this->set('grupo',$this->cfpd01_ano_grupo->findAll());
			   $this->set('partida',$this->cfpd01_ano_partida->findAll('cod_partida='.substr($YYY['cfpd06']['cod_partida'],-2).' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
			   $this->set('generica',$this->cfpd01_ano_generica->findAll('cod_generica='.$YYY['cfpd06']['cod_generica'].' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
			   $this->set('especifica',$this->cfpd01_ano_especifica->findAll('cod_especifica='.$YYY['cfpd06']['cod_especifica'].' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
			   $this->set('subespecifica',$this->cfpd01_ano_sub_espec->findAll('cod_sub_espec='.$YYY['cfpd06']['cod_sub_espec'].' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
               $cp= $YYY['cfpd06']['cod_partida'];
               //$cp = $cp < 10 ? str_replace("0","", $cp) : $cp;
              // echo $this->SQLCA($ejercicio).' and cod_sector='.$YYY['cfpd06']['cod_sector'].' and cod_programa='.$YYY['cfpd06']['cod_programa'].' and cod_sub_prog='.$YYY['cfpd06']['cod_sub_prog'].' and cod_proyecto='.$YYY['cfpd06']['cod_proyecto'].' and cod_activ_obra='.$YYY['cfpd06']['cod_activ_obra'].' and cod_partida='.$cp.' and cod_generica='.$YYY['cfpd06']['cod_generica'].' and cod_especifica='.$YYY['cfpd06']['cod_especifica'].' and cod_sub_espec='.$YYY['cfpd06']['cod_sub_espec'].' and cod_auxiliar='.$YYY['cfpd06']['cod_auxiliar'];
               $this->set('auxiliar',$this->cfpd05_auxiliar->findAll($this->SQLCA($ejercicio).' and cod_sector='.$YYY['cfpd06']['cod_sector'].' and cod_programa='.$YYY['cfpd06']['cod_programa'].' and cod_sub_prog='.$YYY['cfpd06']['cod_sub_prog'].' and cod_proyecto='.$YYY['cfpd06']['cod_proyecto'].' and cod_activ_obra='.$YYY['cfpd06']['cod_activ_obra'].' and cod_partida='.$cp.' and cod_generica='.$YYY['cfpd06']['cod_generica'].' and cod_especifica='.$YYY['cfpd06']['cod_especifica'].' and cod_sub_espec='.$YYY['cfpd06']['cod_sub_espec'].' and cod_auxiliar='.$YYY['cfpd06']['cod_auxiliar'],null,null,null, null, null));
              // echo $this->SQLCA($ejercicio).' and cod_sector='.$YYY['cfpd09']['cod_sector'].' and cod_programa='.$YYY['cfpd06']['cod_programa'].' and cod_sub_prog='.$YYY['cfpd06']['cod_sub_prog'].' and cod_proyecto='.$YYY['cfpd06']['cod_proyecto'].' and cod_activ_obra='.$YYY['cfpd09']['cod_activ_obra'].' and cod_partida='.$cp.' and cod_generica='.$YYY['cfpd06']['cod_generica'].' and cod_especifica='.$YYY['cfpd06']['cod_especifica'].' and cod_sub_espec='.$YYY['cfpd06']['cod_sub_espec'].' and cod_auxiliar='.$YYY['cfpd06']['cod_auxiliar'];

	   $this->set('DATOS',$a);

 }
function guardar_modificar($ff,$ee){
	$this->layout = "ajax";
	if(!empty($this->data)){

	 $a=$this->data['cfpp06']['cantidad_reemplazo'];
	 $b=$this->data['cfpp06']['cantidad_deficiencia'];
	 $c=$this->Formato1($this->data['cfpp06']['costo_unitario']);
	 $d=$this->data['cfpp06']['denominacion'];
	 $n = $this->Formato1($this->data['cfpp06']['monto_total']);
	// $e=$this->data['cfpp06']['numero_linea'];
	 //$e=isset($this->data['cfpp06']['numero_linea']) ? $this->data['cfpp06']['numero_linea'] : 1;
	 //$f=$this->data['cfpp06']['ano'];
	}
	$sql3="update cfpd06 set cantidad_reemplazo=$a, cantidad_deficiencia=$b, costo_unitario=$c, denominacion='$d' where numero_linea=$ee";

$num = $ee;
$year = $ff;



$data = $this->cfpd06->findAll('numero_linea='.$ee, null, null, null);
foreach($data as $datos){
     $a=$datos['cfpd06']['cod_sector'];
	 $b=$datos['cfpd06']['cod_programa'];
	 $c=$datos['cfpd06']['cod_sub_prog'];
	 $d=$datos['cfpd06']['cod_proyecto'];
	 $e=$datos['cfpd06']['cod_activ_obra'];
	 $f=$datos['cfpd06']['cod_partida'];
	 $g=$datos['cfpd06']['cod_generica'];
	 $h=$datos['cfpd06']['cod_especifica'];
	 $i=$datos['cfpd06']['cod_sub_espec'];
	 $j=$datos['cfpd06']['cod_auxiliar'];

}



     $aa[1]=$this->verifica_SS(1);
	 $aa[2]=$this->verifica_SS(2);
	 $aa[3]=$this->verifica_SS(3);
	 $aa[4]=$this->verifica_SS(4);
	 $aa[5]=$this->verifica_SS(5);
	 $k=$this->Session->read('ano_r');

$sql_verificar  =" cod_presi=".$aa[1]." and cod_entidad=".$aa[2]." and cod_tipo_inst=".$aa[3]." and cod_inst=".$aa[4]." and cod_dep=".$aa[5]." and ano=".$k;
$sql_verificar .=" and cod_sector=".$a." and cod_programa=".$b." and cod_sub_prog=".$c." and cod_proyecto=".$d." and cod_activ_obra=".$e;
$sql_verificar .=" and cod_partida=".$f." and cod_generica=".$g." and cod_especifica=".$h." and cod_sub_espec=".$i." and cod_auxiliar=".$j."";
$d31 = '1';

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
$SQLINSERT .="($aa[1],$aa[2],$aa[3],$aa[4],$aa[5],$k,$a,$b,$c,$d,$e,$f,$g,$h,$i,$j,$cod_tipo_gasto,$d31,$n,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)";
$this->cfpd05->execute($SQLINSERT);
}else{
$sql_verificar1  =" cod_presi=".$aa[1]." and cod_entidad=".$aa[2]." and cod_tipo_inst=".$aa[3]." and cod_inst=".$aa[4]." and cod_dep=".$aa[5]." and ano=".$k;
$sql_verificar1 .=" and cod_sector=".$a." and cod_programa=".$b." and cod_sub_prog=".$c." and cod_proyecto=".$d." and cod_activ_obra=".$e." and  numero_linea=".$num;
$sql_verificar1 .=" and cod_partida=".$f." and cod_generica=".$g." and cod_especifica=".$h." and cod_sub_espec=".$i." and cod_auxiliar=".$j."";
$sql_verificar  =" cod_presi=".$aa[1]." and cod_entidad=".$aa[2]." and cod_tipo_inst=".$aa[3]." and cod_inst=".$aa[4]." and cod_dep=".$aa[5]." and ano=".$k;
$sql_verificar .=" and cod_sector=".$a." and cod_programa=".$b." and cod_sub_prog=".$c." and cod_proyecto=".$d." and cod_activ_obra=".$e;
$sql_verificar .=" and cod_partida=".$f." and cod_generica=".$g." and cod_especifica=".$h." and cod_sub_espec=".$i." and cod_auxiliar=".$j."";
        $data = $this->cfpd05->findAll($sql_verificar, null, null, null);
        $data2 = $this->cfpd06->findAll($sql_verificar1, null, null, null);
      //  foreach($data as $datos){$asignacion_anual = $datos['cfpd05']['asignacion_anual'];}
        //foreach($data2 as $datos2){
         //    $a=$this->data['cfpd06']['cantidad_reemplazo'];
	 //		 $b=$this->data['cfpd06']['cantidad_deficiencia'];
	  //       $c=$this->Formato1($this->data['cfpd06']['costo_unitario']);
       // 	 $inicio_estimado = $c * ($a + $b);
//}
  //      if($inicio_estimado!=$n){
    //    	 $asignacion_anual = $asignacion_anual - $inicio_estimado;
      //  	 $asignacion_anual = $asignacion_anual+$n;
        //	}
$sql = "UPDATE cfpd05 SET asignacion_anual=".$n."  where ".$sql_verificar;
//echo $sql;
$this->cfpd05->execute($sql);
}//fin else



		if($this->cfpd06->execute($sql3)>1){
		$this->set('Message_existe', 'Los datos fueron actualizados');
		     $pagina=1;
          	 $Ano=$year;
          	 $ejercicio=$year;
          	 $this->set('ano',$Ano);
          	 $this->set('ejercicio', $Ano);
          	 $Tfilas=$this->cfpd06->findCount($this->SQLCA($Ano));
          	 //echo $Tfilas;
          	 if($Tfilas==0){
          	 	$this->index2($Ano);
          		$this->render("index2");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datacfpd06=$this->cfpd06->findAll($this->SQLCA($Ano),array('cod_sector','cod_programa','cod_sub_prog','cod_proyecto','cod_activ_obra','cod_partida','cod_generica','cod_especifica','cod_sub_espec','cod_auxiliar','numero_linea', 'ano', 'cantidad_reemplazo', 'cantidad_deficiencia', 'costo_unitario', 'denominacion'),'numero_linea DESC',1,$pagina,null);

          	 foreach ($datacfpd06 as $YYY);
                 //$YYY['cfpd06']['cod_sector'];

          	   $this->set('sector',$this->cfpd02_sector->findAll($this->SQLCA().' and cod_sector='.$YYY['cfpd06']['cod_sector'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('programa',$this->cfpd02_programa->findAll($this->SQLCA().' and cod_programa='.$YYY['cfpd06']['cod_programa'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('subprograma',$this->cfpd02_sub_prog->findAll($this->SQLCA().' and cod_sub_prog='.$YYY['cfpd06']['cod_sub_prog'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('proyecto',$this->cfpd02_proyecto->findAll($this->SQLCA().' and cod_proyecto='.$YYY['cfpd06']['cod_proyecto'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('actividad',$this->cfpd02_activ_obra->findAll($this->SQLCA().' and cod_activ_obra='.$YYY['cfpd06']['cod_activ_obra'].' and ano='.$ejercicio,null,null,null,null,null));
			   //$this->set('grupo',$this->cfpd01_ano_grupo->findAll());
			   $this->set('partida',$this->cfpd01_ano_partida->findAll('cod_partida='.substr($YYY['cfpd06']['cod_partida'],-2).' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
			   $this->set('generica',$this->cfpd01_ano_generica->findAll('cod_generica='.$YYY['cfpd06']['cod_generica'].' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
			   $this->set('especifica',$this->cfpd01_ano_especifica->findAll('cod_especifica='.$YYY['cfpd06']['cod_especifica'].' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
			   $this->set('subespecifica',$this->cfpd01_ano_sub_espec->findAll('cod_sub_espec='.$YYY['cfpd06']['cod_sub_espec'].' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
               $cp= $YYY['cfpd06']['cod_partida'];
               //$cp = $cp < 10 ? str_replace("0","", $cp) : $cp;

               $this->set('auxiliar',$this->cfpd05_auxiliar->findAll($this->SQLCA($ejercicio).' and cod_sector='.$YYY['cfpd06']['cod_sector'].' and cod_programa='.$YYY['cfpd06']['cod_programa'].' and cod_sub_prog='.$YYY['cfpd06']['cod_sub_prog'].' and cod_proyecto='.$YYY['cfpd06']['cod_proyecto'].' and cod_activ_obra='.$YYY['cfpd06']['cod_activ_obra'].' and cod_partida='.$cp.' and cod_generica='.$YYY['cfpd06']['cod_generica'].' and cod_especifica='.$YYY['cfpd06']['cod_especifica'].' and cod_sub_espec='.$YYY['cfpd06']['cod_sub_espec'].' and cod_auxiliar='.$YYY['cfpd06']['cod_auxiliar'],null,null,null, null, null));

               // print_r($datacfpd06);
          	 $this->set('DATOS',$datacfpd06);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);

            $this->bt_nav($Tfilas,$pagina);
          	}else{
	 	       $this->set('DATAcfpd06','');
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos para el a&ntilde;o '.$Ano);
	 	       //echo "sssss";
	         }///fin else  del if-else que compara las Tfilas

		}

}

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


function cancelar($id=null,$a1=null,$a2=null,$a3=null,$a4=null,$a5=null,$a6=null,$a7=null,$a8=null,$a9=null,$a10=null,$a11=null,$i=null){
	$this->layout = "ajax";//echo 'hello';
	$d = $this->cfpd06->findAll($this->SQLCA().' and cod_sector='.$a1.' and cod_programa='.$a2.' and cod_sub_prog='.$a3.' and cod_proyecto='.$a4.' and cod_activ_obra='.$a5.' and cod_partida='.$a6.' and cod_generica='.$a7.' and cod_especifica='.$a8.' and cod_sub_espec='.$a9.' and cod_auxiliar='.$a10.' and ano='.$a11.' and numero_linea='.$id);
	$reemplazo 		= $d[0]['cfpd06']['cantidad_reemplazo'];
	$deficiencia 	= $d[0]['cfpd06']['cantidad_deficiencia'];
	$total_equipos  = $reemplazo + $deficiencia;
	$costo_unitario	= $d[0]['cfpd06']['costo_unitario'];
	$total_monto 	= $total_equipos * $costo_unitario;
	$denominacion	= $d[0]['cfpd06']['denominacion'];

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

function guardar_editar($id=null,$a1=null,$a2=null,$a3=null,$a4=null,$a5=null,$a6=null,$a7=null,$a8=null,$a9=null,$a10=null,$a11=null,$i=null){
	$this->layout='ajax';
	$a = $this->data['cfpp06']['reemplazo_'.$i];
	if($a==null){
		$a=0;
	}
	$b = $this->data['cfpp06']['deficiencia_'.$i];
	if($b==null){
		$b=0;
	}
	$e = $this->data['cfpp06']['total_equipos_'.$i];
	$c = $this->Formato1($this->data['cfpp06']['costo_unitario_'.$i]);
	$d = $this->data['cfpp06']['descripcion_'.$i];
	$n = $this->Formato1($this->data['cfpp06']['total_costo_'.$i]);
	$upd="update cfpd06 set cantidad_reemplazo=$a,cantidad_deficiencia=$b,costo_unitario=$c,denominacion='".$d."' WHERE numero_linea=$id and cod_sector=$a1 and cod_programa=$a2 and cod_sub_prog=$a3 and cod_proyecto=$a4 and cod_activ_obra=$a5 and cod_partida=$a6 and cod_generica=$a7 and cod_especifica=$a8 and cod_sub_espec=$a9 and cod_auxiliar=$a10 and ano=$a11 and ".$this->SQLCA();

	$data = $this->cfpd06->findAll("numero_linea=$id and cod_sector=$a1 and cod_programa=$a2 and cod_sub_prog=$a3 and cod_proyecto=$a4 and cod_activ_obra=$a5 and cod_partida=$a6 and cod_generica=$a7 and cod_especifica=$a8 and cod_sub_espec=$a9 and cod_auxiliar=$a10 and ano=$a11 and ".$this->SQLCA(), null, null, null);
	$re=$data[0]['cfpd06']['cantidad_reemplazo'];
	$de=$data[0]['cfpd06']['cantidad_deficiencia'];
	$cu=$data[0]['cfpd06']['costo_unitario'];
	$te=$re+$de;
	$tm=$te*$cu;
	$data2 = $this->cfpd05->findAll("cod_sector=$a1 and cod_programa=$a2 and cod_sub_prog=$a3 and cod_proyecto=$a4 and cod_activ_obra=$a5 and cod_partida=$a6 and cod_generica=$a7 and cod_especifica=$a8 and cod_sub_espec=$a9 and cod_auxiliar=$a10 and ano=$a11 and ".$this->SQLCA(), null, null, null);
	$asignacion=$data2[0]['cfpd05']['asignacion_anual'];
	if($tm != $n){
		$asignacion = $asignacion - $tm;
		$asignacion = $asignacion + $n;
	}

	$upd05="update cfpd05 set asignacion_anual=$asignacion WHERE cod_sector=$a1 and cod_programa=$a2 and cod_sub_prog=$a3 and cod_proyecto=$a4 and cod_activ_obra=$a5 and cod_partida=$a6 and cod_generica=$a7 and cod_especifica=$a8 and cod_sub_espec=$a9 and cod_auxiliar=$a10 and ano=$a11 and ".$this->SQLCA();
	$sw2 = $this->cfpd05->execute($upd05);
	$sw1 = $this->cfpd06->execute($upd);
	$this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
	$this->set('reemplazo',$a);
	$this->set('deficiencia',$b);
	$this->set('total_equipos',$e);
	$this->set('costo_unitario',$c);
	$this->set('total_monto',$n);
	$this->set('denominacion',$d);
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
	$this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');


}

function eliminar($id=null,$a1=null,$a2=null,$a3=null,$a4=null,$a5=null,$a6=null,$a7=null,$a8=null,$a9=null,$a10=null,$a11=null){
	$this->layout='ajax';
	$upd="DELETE FROM cfpd06 WHERE numero_linea=$id and cod_sector=$a1 and cod_programa=$a2 and cod_sub_prog=$a3 and cod_proyecto=$a4 and cod_activ_obra=$a5 and cod_partida=$a6 and cod_generica=$a7 and cod_especifica=$a8 and cod_sub_espec=$a9 and cod_auxiliar=$a10 and ano=$a11 and ".$this->SQLCA();
	$data = $this->cfpd06->findAll("numero_linea=$id and cod_sector=$a1 and cod_programa=$a2 and cod_sub_prog=$a3 and cod_proyecto=$a4 and cod_activ_obra=$a5 and cod_partida=$a6 and cod_generica=$a7 and cod_especifica=$a8 and cod_sub_espec=$a9 and cod_auxiliar=$a10 and ano=$a11 and ".$this->SQLCA(), null, null, null);
	$re=$data[0]['cfpd06']['cantidad_reemplazo'];
	$de=$data[0]['cfpd06']['cantidad_deficiencia'];
	$cu=$data[0]['cfpd06']['costo_unitario'];
	$te=$re+$de;
	$tm=$te*$cu;
	$data2 = $this->cfpd05->findAll("cod_sector=$a1 and cod_programa=$a2 and cod_sub_prog=$a3 and cod_proyecto=$a4 and cod_activ_obra=$a5 and cod_partida=$a6 and cod_generica=$a7 and cod_especifica=$a8 and cod_sub_espec=$a9 and cod_auxiliar=$a10 and ano=$a11 and ".$this->SQLCA(), null, null, null);
	$asignacion=$data2[0]['cfpd05']['asignacion_anual'];
		$asignacion = $asignacion - $tm;

	$upd05="update cfpd05 set asignacion_anual=$asignacion WHERE cod_sector=$a1 and cod_programa=$a2 and cod_sub_prog=$a3 and cod_proyecto=$a4 and cod_activ_obra=$a5 and cod_partida=$a6 and cod_generica=$a7 and cod_especifica=$a8 and cod_sub_espec=$a9 and cod_auxiliar=$a10 and ano=$a11 and ".$this->SQLCA();
	$sw2 = $this->cfpd05->execute($upd05);

	$sw1 = $this->cfpd06->execute($upd);
	$datos = $this->cfpd06->findAll("cod_sector=$a1 and cod_programa=$a2 and cod_sub_prog=$a3 and cod_proyecto=$a4 and cod_activ_obra=$a5 and cod_partida=$a6 and cod_generica=$a7 and cod_especifica=$a8 and cod_sub_espec=$a9 and cod_auxiliar=$a10 and ano=$a11 and ".$this->SQLCA());
	$this->set('datos',$datos);
	$this->set('Message_existe', 'Los datos fueron eliminados');
}

/*
function consulta($pagina=null){

$this->layout="ajax";

if(isset($pagina)){ $pagina=$pagina; }else{ $pagina=1; }
$campos = " ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar";

//$Tfilas=$this->cfpd06->findCount($this->condicion(),"DISTINCT ".$campos);
$Tfilas= count($this->cfpd06->findAll($this->condicion(),"DISTINCT ".$campos));
if($Tfilas!=0){
	$Tfilas=(int)ceil($Tfilas/1);
	$this->set('total_paginas',$Tfilas);
	$this->set('pagina_actual',$pagina);
	$this->set('pag_cant',$pagina.'/'.$Tfilas);
	$this->set('ultimo',$Tfilas);
    $datos_filas=$this->cfpd06->findAll($this->condicion(),"DISTINCT ".$campos, $campos." ASC",1,$pagina,null);
    $this->set("datos",$datos_filas);
    $this->set('siguiente',$pagina+1);
	$this->set('anterior',$pagina-1);
	$this->bt_nav($Tfilas,$pagina);
}else{
	$this->set("datos",'');
}

    $ano            = $datos_filas[0]["cfpd06"]["ano"];
    $cod_sector     = $datos_filas[0]["cfpd06"]["cod_sector"];
    $cod_programa   = $datos_filas[0]["cfpd06"]["cod_programa"];
    $cod_sub_prog   = $datos_filas[0]["cfpd06"]["cod_sub_prog"];
    $cod_proyecto   = $datos_filas[0]["cfpd06"]["cod_proyecto"];
    $cod_activ_obra = $datos_filas[0]["cfpd06"]["cod_activ_obra"];
    $cod_partida    = $datos_filas[0]["cfpd06"]["cod_partida"];
    $cod_generica   = $datos_filas[0]["cfpd06"]["cod_generica"];
    $cod_especifica = $datos_filas[0]["cfpd06"]["cod_especifica"];
    $cod_sub_espec  = $datos_filas[0]["cfpd06"]["cod_sub_espec"];
    $cod_auxiliar   = $datos_filas[0]["cfpd06"]["cod_auxiliar"];


 $this->set('cod_sector',     $cod_sector);
 $this->set('cod_programa',   $cod_programa);
 $this->set('cod_sub_prog',   $cod_sub_prog);
 $this->set('cod_proyecto',   $cod_proyecto);
 $this->set('cod_activ_obra', $cod_activ_obra);
 $this->set('cod_partida',    $cod_partida);
 $this->set('cod_generica',   $cod_generica);
 $this->set('cod_especifica', $cod_especifica);
 $this->set('cod_sub_espec',  $cod_sub_espec);
 $this->set('cod_auxiliar',   $cod_auxiliar);

$cpar  = (int)ceil (substr($cod_partida, -2));

$this->Session->write('ano', $ano);
$this->Session->write('sec', $cod_sector);
$this->Session->write('prog', $cod_programa);
$this->Session->write('subp', $cod_sub_prog);
$this->Session->write('proy', $cod_proyecto);
$this->Session->write('actv', $cod_activ_obra);
$this->Session->write('cpar', $cpar);
$this->Session->write('cgen', $cod_generica);
$this->Session->write('cesp', $cod_especifica);
$this->Session->write('dsubesp',  $cod_sub_espec);
$this->Session->write('aux',      $cod_auxiliar);

for($minCount = 2007; $minCount < 2030; $minCount++) {
    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
	$this->set('anos',$anos);
	$this->set('ano_seleccion',$ano);
}

  $ano  =  $this->Session->read('ano');
  $sec  =  $this->Session->read('sec');
  $prog =  $this->Session->read('prog');
  $subp =  $this->Session->read('subp');
  $proy =  $this->Session->read('proy');
  $actv =  $this->Session->read('actv');
  $cpar  = $this->Session->read('cpar');
  $cpar  = $cpar < 10 ? CE."0".$cpar : CE.$cpar;
  $cgen  = $this->Session->read('cgen');
  $cesp  = $this->Session->read('cesp');
  $c_sub = $this->Session->read('dsubesp');
  $aux   = $this->Session->read('aux');
  $cond  = $this->condicion()." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$actv;
  $cond2 = $cond."and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$c_sub." and cod_auxiliar=".$aux;
  $this->set('datos',   $this->cfpd06->findAll($cond2,null,"numero_linea ASC",null, null, null));

}//fin function

*/

function consulta($pagina=null){

$this->layout="ajax";

if(isset($pagina)){ $pagina=$pagina; }else{ $pagina=1; }
$campos = " ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar";

$Tfilas= count($this->cfpd06->findAll($this->condicion(),"DISTINCT ".$campos));
if($Tfilas!=0){
	$Tfilas=(int)ceil($Tfilas/1);
	$this->set('total_paginas',$Tfilas);
	$this->set('pagina_actual',$pagina);
	$this->set('pag_cant',$pagina.'/'.$Tfilas);
	$this->set('ultimo',$Tfilas);
    $datos_filas=$this->cfpd06->findAll($this->condicion(),"DISTINCT ".$campos, $campos." ASC",1,$pagina,null);
    $this->set("datos",$datos_filas);
    $this->set('siguiente',$pagina+1);
	$this->set('anterior',$pagina-1);
	$this->bt_nav($Tfilas,$pagina);


    $ano            = $datos_filas[0]["cfpd06"]["ano"];
    $cod_sector     = $datos_filas[0]["cfpd06"]["cod_sector"];
    $cod_programa   = $datos_filas[0]["cfpd06"]["cod_programa"];
    $cod_sub_prog   = $datos_filas[0]["cfpd06"]["cod_sub_prog"];
    $cod_proyecto   = $datos_filas[0]["cfpd06"]["cod_proyecto"];
    $cod_activ_obra = $datos_filas[0]["cfpd06"]["cod_activ_obra"];
    $cod_partida    = $datos_filas[0]["cfpd06"]["cod_partida"];
    $cod_generica   = $datos_filas[0]["cfpd06"]["cod_generica"];
    $cod_especifica = $datos_filas[0]["cfpd06"]["cod_especifica"];
    $cod_sub_espec  = $datos_filas[0]["cfpd06"]["cod_sub_espec"];
    $cod_auxiliar   = $datos_filas[0]["cfpd06"]["cod_auxiliar"];


 $this->set('cod_sector',     $cod_sector);
 $this->set('cod_programa',   $cod_programa);
 $this->set('cod_sub_prog',   $cod_sub_prog);
 $this->set('cod_proyecto',   $cod_proyecto);
 $this->set('cod_activ_obra', $cod_activ_obra);
 $this->set('cod_partida',    $cod_partida);
 $this->set('cod_generica',   $cod_generica);
 $this->set('cod_especifica', $cod_especifica);
 $this->set('cod_sub_espec',  $cod_sub_espec);
 $this->set('cod_auxiliar',   $cod_auxiliar);

$cpar  = (int)ceil (substr($cod_partida, -2));

$this->Session->write('ano', $ano);
$this->Session->write('sec', $cod_sector);
$this->Session->write('prog', $cod_programa);
$this->Session->write('subp', $cod_sub_prog);
$this->Session->write('proy', $cod_proyecto);
$this->Session->write('actv', $cod_activ_obra);
$this->Session->write('cpar', $cpar);
$this->Session->write('cgen', $cod_generica);
$this->Session->write('cesp', $cod_especifica);
$this->Session->write('dsubesp',  $cod_sub_espec);
$this->Session->write('aux',      $cod_auxiliar);

for($minCount = 2007; $minCount < 2030; $minCount++) {
    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
	$this->set('anos',$anos);
	$this->set('ano_seleccion',$ano);
}

  $ano  =  $this->Session->read('ano');
  $sec  =  $this->Session->read('sec');
  $prog =  $this->Session->read('prog');
  $subp =  $this->Session->read('subp');
  $proy =  $this->Session->read('proy');
  $actv =  $this->Session->read('actv');
  $cpar  = $this->Session->read('cpar');
  $cpar  = $cpar < 10 ? CE."0".$cpar : CE.$cpar;
  $cgen  = $this->Session->read('cgen');
  $cesp  = $this->Session->read('cesp');
  $c_sub = $this->Session->read('dsubesp');
  $aux   = $this->Session->read('aux');
  $cond  = $this->condicion()." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$actv;
  $cond2 = $cond."and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$c_sub." and cod_auxiliar=".$aux;
  $this->set('datos',   $this->cfpd06->findAll($cond2,null,"numero_linea ASC",null, null, null));

}else{
	$this->set("errorMessage",'NO EXISTEN DATOS');
	$this->index();
	$this->render("index");
}


}//fin function

 }//fin clase cfpp06Controller

 ?>