<?php
/*
 * Created on 19/07/2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

 class Cfpp09Controller extends AppController{

     var $uses = array('cfpd09', 'cfpd02_sector', 'cfpd02_programa', 'cfpd05', 'cfpd05_auxiliar', 'cfpd02_sub_prog', 'cfpd02_proyecto', 'cfpd02_activ_obra', 'cfpd01_ano_grupo', 'cfpd01_ano_partida', 'cfpd01_ano_generica', 'cfpd01_ano_especifica', 'cfpd01_ano_sub_espec', 'cfpd01_ano_auxiliar', 'cfpd01_formulacion');
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







function consulta($pagina=null){

$this->layout="ajax";

if(isset($pagina)){ $pagina=$pagina; }else{ $pagina=1; }
$campos = " ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar";

$Tfilas= count($this->cfpd09->findAll($this->condicion(),"DISTINCT ".$campos));
if($Tfilas!=0){
	$Tfilas=(int)ceil($Tfilas/1);
	$this->set('total_paginas',$Tfilas);
	$this->set('pagina_actual',$pagina);
	$this->set('pag_cant',$pagina.'/'.$Tfilas);
	$this->set('ultimo',$Tfilas);
    $datos_filas=$this->cfpd09->findAll($this->condicion(),"DISTINCT ".$campos, $campos." ASC",1,$pagina,null);
    $this->set("datos",$datos_filas);
    $this->set('siguiente',$pagina+1);
	$this->set('anterior',$pagina-1);
	$this->bt_nav($Tfilas,$pagina);


    $ano            = $datos_filas[0]["cfpd09"]["ano"];
    $cod_sector     = $datos_filas[0]["cfpd09"]["cod_sector"];
    $cod_programa   = $datos_filas[0]["cfpd09"]["cod_programa"];
    $cod_sub_prog   = $datos_filas[0]["cfpd09"]["cod_sub_prog"];
    $cod_proyecto   = $datos_filas[0]["cfpd09"]["cod_proyecto"];
    $cod_activ_obra = $datos_filas[0]["cfpd09"]["cod_activ_obra"];
    $cod_partida    = $datos_filas[0]["cfpd09"]["cod_partida"];
    $cod_generica   = $datos_filas[0]["cfpd09"]["cod_generica"];
    $cod_especifica = $datos_filas[0]["cfpd09"]["cod_especifica"];
    $cod_sub_espec  = $datos_filas[0]["cfpd09"]["cod_sub_espec"];
    $cod_auxiliar   = $datos_filas[0]["cfpd09"]["cod_auxiliar"];


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
  $this->set('datos',   $this->cfpd09->findAll($cond2,null,"numero_linea ASC",null, null, null));

}else{
	$this->set("errorMessage",'NO EXISTEN DATOS');
	$this->index();
	$this->render("index");
}


}//fin function









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



    function index(){
       $this->layout = "ajax";
       //echo appController::saludar("hola esta funcion esta alojada en app_controller");
       //A partir de aqui esta el codiog para bajar el año presupuestario por defecto
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

	$year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
	$dato = null;
	foreach($year as $year){
		$dato = $year['cfpd01_formulacion']['ano_formular'];
	}

	if(!empty($dato)){

		$this->set('year', $dato);

	}else{
		$this->set('year', '');
	}

// fin del codigo

       $this->set('entidadFederal',$this->verifica_SS(6));
       for($minCount = 2007; $minCount < 2030; $minCount++) {
	    $anos[sprintf('%02d', $minCount)] = sprintf('%02d', $minCount);
	    $this->set('anos',$anos);
	    $this->set('ano_formulacion',$dato);
       }

	 }//fin index


function index2($ano=null){
    	$this->layout="ajax";
         if((isset($this->data['cfpp09']['ano']) && $this->data['cfpp09']['ano']!="") || isset($ano)){
         	$ano = isset($this->data['cfpp09']['ano']) ?  $this->data['cfpp09']['ano'] : $ano;
         	$this->Session->write('ano_r',$ano);
         	$listaSector=$this->cfpd02_sector->generateList($this->SQLCA($ano), 'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.denominacion');
	        $this->concatena($listaSector, 'vector');
	       // $this->set('partida', $this->cfpd01_ano_partida->generateList("where cod_grupo=4", 'cod_partida ASC', null, '{n}.cfpd01_ano_partida.cod_partida', '{n}.cfpd01_ano_partida.cod_partida'));
			 $this->Session->write('ano',$ano);
			 $this->set('ano',$ano);
         }

}//fin index2


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
                       	     echo "<script type='text/javascript'>ver_documento('/cfpp09/seleccion/','st_ubica_seleccion');</script>";
							$this->concatena_aux($lista, 'vector');
						}else{
                            echo "<script type='text/javascript'>ver_documento('/cfpp09/seleccion/si/0','st_ubica_seleccion');</script>";
							$this->set('vector',array('0'=>'0000'));
						}
		break;
		case 'seleccion':
		 $this->Session->write('aux',$var);
		 $this->seleccion("si");
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
		//echo 'llego al auxiliar';
		  $ano =  $this->Session->read('ano');
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
		  $cond2 .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subprog." and cod_proyecto=".$proy." and cod_activ_obra=".$activ." and cod_partida=".$dpar." and cod_generica=".$dgen." and cod_especifica=".$desp." and cod_sub_espec=".$dsubesp." and cod_auxiliar=".$var;
		  //echo $cond2;
		  $a=  $this->cfpd05_auxiliar->findAll($cond2);
          echo $a[0]['cfpd05_auxiliar']['denominacion'];
		break;
	}//fin wsitch
	}else{
		echo "";
	}
}//fin mostrar3 codigos presupuestarios

function consultar($ejercicio=null, $pag_num=null) {
 		$this->layout = "ajax";
 		$this->set('entidadFederal',$this->verifica_SS(6));


	  if($ejercicio!=null){
	  	    $this->set('ejercicio', $ejercicio);
		}else if($this->data['cfpp09']['ano']){
							$this->set('ejercicio', $this->data['cfpp09']['ano']);
							$ejercicio = $this->data['cfpp09']['ano'];
							$ano = $this->data['cfpp09']['ano'];
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
          	 $Tfilas=$this->cfpd09->findCount($this->SQLCA($Ano));
          	 //echo $Tfilas;
          	 if($Tfilas==0){
          	 	$this->index2($Ano);
          		$this->render("index2");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datacfpd09=$this->cfpd09->findAll($this->SQLCA($Ano),null,'numero_linea DESC',1,$pagina,null);
          	 foreach ($datacfpd09 as $YYY);
                 //$YYY['cfpd09']['cod_sector'];

          	   $this->set('sector',$this->cfpd02_sector->findAll($this->SQLCA().' and cod_sector='.$YYY['cfpd09']['cod_sector'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('programa',$this->cfpd02_programa->findAll($this->SQLCA().' and cod_programa='.$YYY['cfpd09']['cod_programa'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('subprograma',$this->cfpd02_sub_prog->findAll($this->SQLCA().' and cod_sub_prog='.$YYY['cfpd09']['cod_sub_prog'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('proyecto',$this->cfpd02_proyecto->findAll($this->SQLCA().' and cod_proyecto='.$YYY['cfpd09']['cod_proyecto'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('actividad',$this->cfpd02_activ_obra->findAll($this->SQLCA().' and cod_activ_obra='.$YYY['cfpd09']['cod_activ_obra'].' and ano='.$ejercicio,null,null,null,null,null));
			   //$this->set('grupo',$this->cfpd01_ano_grupo->findAll());
			   $this->set('partida',$this->cfpd01_ano_partida->findAll('cod_partida='.substr($YYY['cfpd09']['cod_partida'],-2).' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
			   $this->set('generica',$this->cfpd01_ano_generica->findAll('cod_generica='.$YYY['cfpd09']['cod_generica'].' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
			   $this->set('especifica',$this->cfpd01_ano_especifica->findAll('cod_especifica='.$YYY['cfpd09']['cod_especifica'].' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
			   $this->set('subespecifica',$this->cfpd01_ano_sub_espec->findAll('cod_sub_espec='.$YYY['cfpd09']['cod_sub_espec'].' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
               $cp= $YYY['cfpd09']['cod_partida'];
               //$cp = $cp < 10 ? str_replace("0","", $cp) : $cp;

               $this->set('auxiliar',$this->cfpd05_auxiliar->findAll($this->SQLCA($ejercicio).' and cod_sector='.$YYY['cfpd09']['cod_sector'].' and cod_programa='.$YYY['cfpd09']['cod_programa'].' and cod_sub_prog='.$YYY['cfpd09']['cod_sub_prog'].' and cod_proyecto='.$YYY['cfpd09']['cod_proyecto'].' and cod_activ_obra='.$YYY['cfpd09']['cod_activ_obra'].' and cod_partida='.$cp.' and cod_generica='.$YYY['cfpd09']['cod_generica'].' and cod_especifica='.$YYY['cfpd09']['cod_especifica'].' and cod_sub_espec='.$YYY['cfpd09']['cod_sub_espec'].' and cod_auxiliar='.$YYY['cfpd09']['cod_auxiliar'],null,null,null, null, null));

               // print_r($datacfpd09);
          	 $this->set('DATOS',$datacfpd09);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);

            $this->bt_nav($Tfilas,$pagina);
          	}else{
	 	       $this->set('DATAcfpd09','');
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos para el a&ntilde;o '.$Ano);
	 	       //echo "sssss";
	         }///fin else  del if-else que compara las Tfilas
          }else{
          	$pagina=1;
          	$Ano=$this->data['cfpp09']['ano'];
          	$ejercicio=$Ano;
          	$this->set('ejercicio', $Ano);
          	$this->set('ano',$Ano);
          	$Tfilas=$this->cfpd09->findCount($this->SQLCA($Ano));
          	if($Tfilas==0){
          	 	$this->index2($Ano);
          		$this->render("index2");
          	 }
             if($Tfilas!=0){
             	$tc=$this->cfpd09->findCount($this->SQLCA($ejercicio));

          	$this->set('pag_cant',$pagina.'/'.$Tfilas);
            $datacfpd09=$this->cfpd09->findAll($this->SQLCA($Ano),null,'numero_linea DESC',1,$pagina,null);

              foreach ($datacfpd09 as $YYY);
                  //$YYY['cfpd09']['cod_sector'];

               $this->set('sector',$this->cfpd02_sector->findAll($this->SQLCA().' and cod_sector='.$YYY['cfpd09']['cod_sector'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('programa',$this->cfpd02_programa->findAll($this->SQLCA().' and cod_programa='.$YYY['cfpd09']['cod_programa'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('subprograma',$this->cfpd02_sub_prog->findAll($this->SQLCA().' and cod_sub_prog='.$YYY['cfpd09']['cod_sub_prog'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('proyecto',$this->cfpd02_proyecto->findAll($this->SQLCA().' and cod_proyecto='.$YYY['cfpd09']['cod_proyecto'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('actividad',$this->cfpd02_activ_obra->findAll($this->SQLCA().' and cod_activ_obra='.$YYY['cfpd09']['cod_activ_obra'].' and ano='.$ejercicio,null,null,null,null,null));
			   //$this->set('grupo',$this->cfpd01_ano_grupo->findAll());
			   $this->set('partida',$this->cfpd01_ano_partida->findAll('cod_partida='.substr($YYY['cfpd09']['cod_partida'],-2).' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
			   $this->set('generica',$this->cfpd01_ano_generica->findAll('cod_generica='.$YYY['cfpd09']['cod_generica'].' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
			   $this->set('especifica',$this->cfpd01_ano_especifica->findAll('cod_especifica='.$YYY['cfpd09']['cod_especifica'].' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
			   $this->set('subespecifica',$this->cfpd01_ano_sub_espec->findAll('cod_sub_espec='.$YYY['cfpd09']['cod_sub_espec'].' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
               $cp= $YYY['cfpd09']['cod_partida'];
               //$cp = $cp < 10 ? str_replace("0","", $cp) : $cp;
              // echo $this->SQLCA($ejercicio).' and cod_sector='.$YYY['cfpd09']['cod_sector'].' and cod_programa='.$YYY['cfpd09']['cod_programa'].' and cod_sub_prog='.$YYY['cfpd09']['cod_sub_prog'].' and cod_proyecto='.$YYY['cfpd09']['cod_proyecto'].' and cod_activ_obra='.$YYY['cfpd09']['cod_activ_obra'].' and cod_partida='.$cp.' and cod_generica='.$YYY['cfpd09']['cod_generica'].' and cod_especifica='.$YYY['cfpd09']['cod_especifica'].' and cod_sub_espec='.$YYY['cfpd09']['cod_sub_espec'].' and cod_auxiliar='.$YYY['cfpd09']['cod_auxiliar'];
               $this->set('auxiliar',$this->cfpd05_auxiliar->findAll($this->SQLCA($ejercicio).' and cod_sector='.$YYY['cfpd09']['cod_sector'].' and cod_programa='.$YYY['cfpd09']['cod_programa'].' and cod_sub_prog='.$YYY['cfpd09']['cod_sub_prog'].' and cod_proyecto='.$YYY['cfpd09']['cod_proyecto'].' and cod_activ_obra='.$YYY['cfpd09']['cod_activ_obra'].' and cod_partida='.$cp.' and cod_generica='.$YYY['cfpd09']['cod_generica'].' and cod_especifica='.$YYY['cfpd09']['cod_especifica'].' and cod_sub_espec='.$YYY['cfpd09']['cod_sub_espec'].' and cod_auxiliar='.$YYY['cfpd09']['cod_auxiliar'],null,null,null, null, null));
              // echo $this->SQLCA($ejercicio).' and cod_sector='.$YYY['cfpd09']['cod_sector'].' and cod_programa='.$YYY['cfpd09']['cod_programa'].' and cod_sub_prog='.$YYY['cfpd09']['cod_sub_prog'].' and cod_proyecto='.$YYY['cfpd09']['cod_proyecto'].' and cod_activ_obra='.$YYY['cfpd09']['cod_activ_obra'].' and cod_partida='.$cp.' and cod_generica='.$YYY['cfpd09']['cod_generica'].' and cod_especifica='.$YYY['cfpd09']['cod_especifica'].' and cod_sub_espec='.$YYY['cfpd09']['cod_sub_espec'].' and cod_auxiliar='.$YYY['cfpd09']['cod_auxiliar'];
          	$this->set('DATOS',$datacfpd09);
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


if($op=="si" && $var!=null){
	$this->set('datos',$this->cfpd09->findAll($cond2,null,"numero_linea ASC",null, null, null));
}else{
	$this->set('datos',array());
}


}//fin function









function editar($id_fila=null, $numero_linea=null){


$this->layout = "ajax";

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
		  $cond2 = $cond."and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$c_sub." and cod_auxiliar=".$aux." and numero_linea=".$numero_linea;


	$this->set('datos',   $this->cfpd09->findAll($cond2,null,"numero_linea ASC",null, null, null));
    $this->set('id_fila', $id_fila);
    $this->set('numero_linea', $numero_linea);

}//fin function



function cancelar($id_fila=null, $numero_linea=null){


$this->layout = "ajax";

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
		  $cond2 = $cond."and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$c_sub." and cod_auxiliar=".$aux." and numero_linea=".$numero_linea;


	$this->set('datos',   $this->cfpd09->findAll($cond2,null,"numero_linea ASC",null, null, null));
    $this->set('id_fila', $id_fila);
    $this->set('numero_linea', $numero_linea);

}//fin function




function guardar(){
	$this->layout = "ajax";
	if(!empty($this->data)){
	 $a=$this->data['cfpp09']['cod_sector'];
	 $b=$this->data['cfpp09']['cod_programa'];
	 $c=$this->data['cfpp09']['cod_subprograma'];
	 $d=$this->data['cfpp09']['cod_proyecto'];
	 $e=$this->data['cfpp09']['cod_actividad'];
	 $f=$this->data['cfpp09']['cod_partida'];
	 $f = $f >10 ? CE.$f : CE."0".$f ;
	 $g=$this->data['cfpp09']['cod_generica'];
	 $h=$this->data['cfpp09']['cod_especifica'];
	 $i=$this->data['cfpp09']['cod_subespecifica'];
	 $j=isset($this->data['cfpp09']['cod_auxiliar']) ? $this->data['cfpp09']['cod_auxiliar'] : 0;
	 $l=$this->data['cfpp09']['denominacion'];
	 $m=$this->data['cfpp09']['unidad_medida'];
	 $o=$this->Formato1($this->data['cfpp09']['cantidad_estimada']);
	 $n=$this->Formato1($this->data['cfpp09']['costo_financiero']);
	 if($j == null){
	 	$j=0;
	 }

	 $aa[1]=$this->verifica_SS(1);
	 $aa[2]=$this->verifica_SS(2);
	 $aa[3]=$this->verifica_SS(3);
	 $aa[4]=$this->verifica_SS(4);
	 $aa[5]=$this->verifica_SS(5);
	 $k=$this->Session->read('ano_r');
	 $SQL_INSERT ="INSERT INTO cfpd09 (cod_presi, cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_partida,cod_activ_obra,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,denominacion,unidad_medida,costo_financiero,cantidad_estimada)";
	 $SQL_INSERT .=" VALUES (".$aa[1].",".$aa[2].",".$aa[3].",".$aa[4].",".$aa[5].",".$k.",".$a.",".$b.",".$c.",".$d.",".$f.",".$e.",".$g.",".$h.",".$i.",".$j.",'".$l."','".$m."',".$n.",".$o.")";
     $x=$this->cfpd09->execute($SQL_INSERT);





$sql_verificar  =" cod_presi=".$aa[1]." and cod_entidad=".$aa[2]." and cod_tipo_inst=".$aa[3]." and cod_inst=".$aa[4]." and cod_dep=".$aa[5]." and ano=".$k;
$sql_verificar .=" and cod_sector=".$a." and cod_programa=".$b." and cod_sub_prog=".$c." and cod_proyecto=".$d." and cod_activ_obra=".$e;
$sql_verificar .=" and cod_partida=".$f." and cod_generica=".$g." and cod_especifica=".$h." and cod_sub_espec=".$i." and cod_auxiliar=".$j."";
$sql_axu        = $sql_verificar;
$d31 = '1';
$n=$this->Formato1($this->data['cfpp09']['costo_financiero']);
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
$sql_verificar ="cod_presi=".$aa[1]." and cod_entidad=".$aa[2]." and cod_tipo_inst=".$aa[3]." and cod_inst=".$aa[4]." and cod_dep=".$aa[5]." and ano=".$k;
$sql_verificar .=" and cod_sector=".$a." and cod_programa=".$b." and cod_sub_prog=".$c." and cod_proyecto=".$d." and cod_activ_obra=".$e;
$sql_verificar .=" and cod_partida=".$f." and cod_generica=".$g." and cod_especifica=".$h." and cod_sub_espec=".$i." and cod_auxiliar=".$j."";
        $data = $this->cfpd05->findAll($sql_verificar, null, null, null);
        foreach($data as $datos){$asignacion_anual = $datos['cfpd05']['asignacion_anual'];}
        	  $asignacion_anual = $asignacion_anual+$n;
              $sql = "UPDATE cfpd05 SET asignacion_anual=".$asignacion_anual."  where ".$sql_verificar;
              $this->cfpd05->execute($sql);
}//fin else

         $this->set('datos',$this->cfpd09->findAll($sql_axu,null,null,null, null, null));
         $this->data=null;
         $this->set('Message_existe', 'Meta Agregada con exito.');


 }//fin guardar


     echo "<script>" .
		"document.getElementById('denominacion').value='';" .
		"document.getElementById('unidad_medida').value='';" .
		"document.getElementById('cantidad_estimada').value='';" .
		"document.getElementById('costo_financiero').value='';" .
		"</script>";


}//fin function






function eliminar($var11=null){

          $var12  =  $this->Session->read('ano');
		  $var1 =  $this->Session->read('sec');
		  $var2 =  $this->Session->read('prog');
		  $var3 =  $this->Session->read('subp');
		  $var4 =  $this->Session->read('proy');
		  $var5 =  $this->Session->read('actv');
		  $cpar  = $this->Session->read('cpar');
		  $var6  = $cpar < 10 ? CE."0".$cpar : CE.$cpar;
		  $var7  = $this->Session->read('cgen');
		  $var8  = $this->Session->read('cesp');
		  $var9 = $this->Session->read('dsubesp');
		  $var10   = $this->Session->read('aux');


    $sql=$this->SQLCA($var12)." and cod_sector=".$var1."  and cod_programa=".$var2." and cod_sub_prog=".$var3." and cod_proyecto=".$var4." and cod_activ_obra=".$var5." and cod_partida=".$var6."  and cod_generica=".$var7." and cod_especifica=".$var8." and cod_sub_espec=".$var9." and cod_auxiliar=".$var10." and numero_linea="."$var11";
	$x=$this->cfpd09->findCount($sql);
				if($x!=0){

					$sql1 ="DELETE  FROM  cfpd09 where ".$sql;

			$sql_re ="cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and ano=".$var12." ";
			$sql_re .="and cod_sector=".$var1."  and cod_programa=".$var2." and cod_sub_prog=".$var3." and cod_proyecto=".$var4." and cod_activ_obra=".$var5." ";
			$sql_re .=" and cod_partida = ".CE.$this->AddCeroR($var6)." and cod_generica = ".$var7." and cod_especifica = ".$var8." and cod_sub_espec = ".$var9." and cod_auxiliar=".$var10."";

			$sql_re_2 ="cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and ano=".$var12." ";
			$sql_re_2 .="and cod_sector=".$var1."  and cod_programa=".$var2." and cod_sub_prog=".$var3." and cod_proyecto=".$var4." and cod_activ_obra=".$var5." ";
			$sql_re_2 .=" and cod_partida = ".CE.$this->AddCeroR($var6)." and cod_generica = ".$var7." and cod_especifica = ".$var8." and cod_sub_espec = ".$var9." and cod_auxiliar=".$var10."  and numero_linea='".$var11."'   ";

			if($this->cfpd05->findCount($sql_re)!=0){
			$sql_execute = '';
			$asignacion_anual = '';
			$sql_re_3 ="DELETE  FROM  cfpd05 where cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and ano=".$var12." ";
			$sql_re_3 .="and cod_sector=".$var1."  and cod_programa=".$var2." and cod_sub_prog=".$var3." and cod_proyecto=".$var4." and cod_activ_obra=".$var5." ";
			$sql_re_3 .=" and cod_partida = ".CE.$this->AddCeroR($var6)." and cod_generica = ".$var7." and cod_especifica = ".$var8." and cod_sub_espec = ".$var9." and cod_auxiliar=".$var10."";
			$data = $this->cfpd05->findAll($sql_re, null, null, null);
			$data2 = $this->cfpd09->findAll($sql_re_2, null, null, null);
			        foreach($data as $datos){$asignacion_anual = $datos['cfpd05']['asignacion_anual'];}
			        foreach($data2 as $datos2){$estimado_presu = $datos2['cfpd09']['costo_financiero'];}
			$aux = $asignacion_anual - $estimado_presu;
			if($aux<=0){
			$sql_execute = $sql_re_3;
			}else{
			$sql_re_4 = "UPDATE cfpd05 SET asignacion_anual=".$aux."  where ".$sql_re;
			$sql_execute = $sql_re_4;
			}
			$this->cfpd05->execute($sql_execute);
			}//fin if



					$this->cfpd09->execute($sql1);
					$this->set('Message_existe', 'Meta Eliminada con exito.');

			        $sql2=$this->SQLCA($var12)." and cod_sector=".$var1."  and cod_programa=".$var2." and cod_sub_prog=".$var3." and cod_proyecto=".$var4." and cod_activ_obra=".$var5." and cod_partida=".$var6."  and cod_generica=".$var7." and cod_especifica=".$var8." and cod_sub_espec=".$var9." and cod_auxiliar=".$var10." ";
					$y=$this->cfpd09->findCount($sql2);
}


$this->seleccion("si");
$this->render("seleccion");

}//fin function




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
	   $a=  $this->cfpd09->findAll($cond);
	 foreach ($a as $YYY);
	    $this->set('sector',$this->cfpd02_sector->findAll($this->SQLCA().' and cod_sector='.$YYY['cfpd09']['cod_sector'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('programa',$this->cfpd02_programa->findAll($this->SQLCA().' and cod_programa='.$YYY['cfpd09']['cod_programa'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('subprograma',$this->cfpd02_sub_prog->findAll($this->SQLCA().' and cod_sub_prog='.$YYY['cfpd09']['cod_sub_prog'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('proyecto',$this->cfpd02_proyecto->findAll($this->SQLCA().' and cod_proyecto='.$YYY['cfpd09']['cod_proyecto'].' and ano='.$ejercicio,null,null,null,null,null));
			   $this->set('actividad',$this->cfpd02_activ_obra->findAll($this->SQLCA().' and cod_activ_obra='.$YYY['cfpd09']['cod_activ_obra'].' and ano='.$ejercicio,null,null,null,null,null));
			   //$this->set('grupo',$this->cfpd01_ano_grupo->findAll());
			   $this->set('partida',$this->cfpd01_ano_partida->findAll('cod_partida='.substr($YYY['cfpd09']['cod_partida'],-2).' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
			   $this->set('generica',$this->cfpd01_ano_generica->findAll('cod_generica='.$YYY['cfpd09']['cod_generica'].' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
			   $this->set('especifica',$this->cfpd01_ano_especifica->findAll('cod_especifica='.$YYY['cfpd09']['cod_especifica'].' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
			   $this->set('subespecifica',$this->cfpd01_ano_sub_espec->findAll('cod_sub_espec='.$YYY['cfpd09']['cod_sub_espec'].' and ejercicio='.$ejercicio.' and cod_grupo='.CE,null,null,null, null, null));
               $cp= $YYY['cfpd09']['cod_partida'];
               //$cp = $cp < 10 ? str_replace("0","", $cp) : $cp;
              // echo $this->SQLCA($ejercicio).' and cod_sector='.$YYY['cfpd09']['cod_sector'].' and cod_programa='.$YYY['cfpd09']['cod_programa'].' and cod_sub_prog='.$YYY['cfpd09']['cod_sub_prog'].' and cod_proyecto='.$YYY['cfpd09']['cod_proyecto'].' and cod_activ_obra='.$YYY['cfpd09']['cod_activ_obra'].' and cod_partida='.$cp.' and cod_generica='.$YYY['cfpd09']['cod_generica'].' and cod_especifica='.$YYY['cfpd09']['cod_especifica'].' and cod_sub_espec='.$YYY['cfpd09']['cod_sub_espec'].' and cod_auxiliar='.$YYY['cfpd09']['cod_auxiliar'];
               $this->set('auxiliar',$this->cfpd05_auxiliar->findAll($this->SQLCA($ejercicio).' and cod_sector='.$YYY['cfpd09']['cod_sector'].' and cod_programa='.$YYY['cfpd09']['cod_programa'].' and cod_sub_prog='.$YYY['cfpd09']['cod_sub_prog'].' and cod_proyecto='.$YYY['cfpd09']['cod_proyecto'].' and cod_activ_obra='.$YYY['cfpd09']['cod_activ_obra'].' and cod_partida='.$cp.' and cod_generica='.$YYY['cfpd09']['cod_generica'].' and cod_especifica='.$YYY['cfpd09']['cod_especifica'].' and cod_sub_espec='.$YYY['cfpd09']['cod_sub_espec'].' and cod_auxiliar='.$YYY['cfpd09']['cod_auxiliar'],null,null,null, null, null));
              // echo $this->SQLCA($ejercicio).' and cod_sector='.$YYY['cfpd09']['cod_sector'].' and cod_programa='.$YYY['cfpd09']['cod_programa'].' and cod_sub_prog='.$YYY['cfpd09']['cod_sub_prog'].' and cod_proyecto='.$YYY['cfpd09']['cod_proyecto'].' and cod_activ_obra='.$YYY['cfpd09']['cod_activ_obra'].' and cod_partida='.$cp.' and cod_generica='.$YYY['cfpd09']['cod_generica'].' and cod_especifica='.$YYY['cfpd09']['cod_especifica'].' and cod_sub_espec='.$YYY['cfpd09']['cod_sub_espec'].' and cod_auxiliar='.$YYY['cfpd09']['cod_auxiliar'];

	   $this->set('DATOS',$a);

 }



function guardar_modificar($id_fila=null, $numero_linea=null){
	$this->layout = "ajax";


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
		  $cond2 = $cond."and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$c_sub." and cod_auxiliar=".$aux." and numero_linea=".$numero_linea;


if( !empty($this->data['cfpp09']['denominacion_'.$id_fila])      &&
	!empty($this->data['cfpp09']['unidad_medida_'.$id_fila])     &&
	!empty($this->data['cfpp09']['cantidad_estimada_'.$id_fila]) &&
	!empty($this->data['cfpp09']['costo_financiero_'.$id_fila]) ){


	if(!empty($this->data)){

	 $a=$this->data['cfpp09']['denominacion_'.$id_fila];
	 $b=$this->data['cfpp09']['unidad_medida_'.$id_fila];
	 $c=$this->Formato1($this->data['cfpp09']['cantidad_estimada_'.$id_fila]);
	 $d=$this->Formato1($this->data['cfpp09']['costo_financiero_'.$id_fila]);
	 $e=$numero_linea;
	}





$sql3="update cfpd09 set denominacion='$a', unidad_medida='$b', cantidad_estimada=$c, costo_financiero=$d where ".$cond2;



$num = $e;
$year = $ano;







$data = $this->cfpd09->findAll($cond2, null, null, null);
foreach($data as $datos){
     $a=$datos['cfpd09']['cod_sector'];
	 $b=$datos['cfpd09']['cod_programa'];
	 $c=$datos['cfpd09']['cod_sub_prog'];
	 $d=$datos['cfpd09']['cod_proyecto'];
	 $e=$datos['cfpd09']['cod_activ_obra'];
	 $f=$datos['cfpd09']['cod_partida'];
	 $g=$datos['cfpd09']['cod_generica'];
	 $h=$datos['cfpd09']['cod_especifica'];
	 $i=$datos['cfpd09']['cod_sub_espec'];
	 $j=$datos['cfpd09']['cod_auxiliar'];
	 $l=$datos['cfpd09']['denominacion'];
	 $m=$datos['cfpd09']['unidad_medida'];
	 $n=$datos['cfpd09']['costo_financiero'];
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
$n=$this->Formato1($this->data['cfpp09']['costo_financiero_'.$id_fila]);
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
        $data2 = $this->cfpd09->findAll($sql_verificar1, null, null, null);
        foreach($data as $datos){$asignacion_anual = $datos['cfpd05']['asignacion_anual'];}
        foreach($data2 as $datos2){$inicio_estimado = $datos2['cfpd09']['costo_financiero'];}
        if($inicio_estimado!=$n){
        	 $asignacion_anual = $asignacion_anual - $inicio_estimado;
        	 $asignacion_anual = $asignacion_anual+$n;
        	}
$sql = "UPDATE cfpd05 SET asignacion_anual=".$asignacion_anual."  where ".$sql_verificar;
//echo $sql;
$this->cfpd05->execute($sql);

}//fin else


    $this->cfpd09->execute($sql3);
    $this->set('Message_existe', 'Los datos fueron modificados');

}else{
   $this->set('errorMessage', 'LOS DATOS NO FUERON MODIFICADOS');

}



	$this->set('datos',   $this->cfpd09->findAll($cond2,null,"numero_linea ASC",null, null, null));
	$this->set('id_fila', $id_fila);
    $this->set('numero_linea', $numero_linea);
}



}//fin clase cfpp09Controller

