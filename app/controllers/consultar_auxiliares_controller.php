<?php
/*
 * Created on 19/07/2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

 class ConsultarAuxiliaresController extends AppController{
	  var $name = 'consultar_auxiliares';
     var $uses = array('cfpd20','cfpd10_reformulacion_partidas_tmp','v_solicitud_cfpd05_p2','ccfd04_cierre_mes','ccfd03_instalacion','cfpd07_obras_partidas','cfpp05auxiliar','cugd02_dependencia','v_cfpd05_denominaciones','cfpd10_reformulacion_tipo','cfpd10_reformulacion_texto','v_deno_dependencia','cfpd01_formulacion','cfpd10_reformulacion_funcionarios','cfpd10_reformulacion_partidas','cfpd10_reformulacion_partidas_tmp','cugd03_acta_anulacion_numero','cugd03_acta_anulacion_cuerpo','v_cfpd05_disponibilidad','cugd04','cpcd02','cscd01_catalogo','cepd01_tipo_compromiso','cepd01_compromiso_cuerpo','cepd01_compromiso_numero','cepd01_compromiso_partidas','cfpd05','cfpd05_requerimiento','cfpd05_2032_tmp','cfpd05_auxiliar','cfpp05auxiliar','cfpd02_sector', 'cfpd02_programa', 'cfpd02_sub_prog', 'cfpd02_proyecto', 'cfpd02_activ_obra', 'arrd05','cfpd01_ano_grupo', 'cfpd01_ano_partida', 'cfpd01_ano_generica', 'cfpd01_ano_especifica', 'cfpd01_ano_sub_espec', 'cfpd01_ano_auxiliar', 'cfpd01_formulacion','cugd02_direccionsuperior', 'cugd02_coordinacion', 'cugd02_secretaria', 'cugd02_direccion');
     var $helpers = array('Html','Ajax','Javascript', 'Sisap');
    // var $paginate = array('limit' => 3, 'page' => 1);
function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					//$this->set('userTable', $this->requestAction('/cnmp03partidas/', array('return')));
					//echo "H".$this->requestAction('/usuarios/actualizar_user',array('return'));
					 if($this->verifica_SS(5)!=1){
			echo "<h4>USTED NO ESTA AUTORIZADO PARA ENTRAR A ESTA CONSULTA</h4>";
			exit();
					 }
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

        function SQLCX($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)." ";
		 return $sql_re;


    }//fin funcion SQLX

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
		//print_r($cod);
	}

	$this->set($nomVar, $cod);
}//fin function


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



    function index(){
    	$this->data['consultar_auxiliares']=null;
    	$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$de=$this->Session->read('SScoddep');
		//echo $de;
		//if($de==1){
		$condj=$this->SQLCX();
		//}
		//else $condj=$this->SQLCA();
		$cond = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
      	$this->Session->delete("ano");
      	$this->Session->delete("sec");
      	$this->Session->delete("prog");
      	$this->Session->delete("subp");
      	$this->Session->delete("proy");
      	$this->Session->delete("actividad");
      	$this->Session->delete("cpar");
      	$this->Session->delete("cgen");
      	$this->Session->delete("cesp");
      	$this->Session->delete("csesp");
      	$this->Session->delete("auxiliar");
    	$this->layout = "ajax";
    	$ano = $this->ano_ejecucion();
    	//$datos=$this->cfpd01_formulacion->findAll($cond);
    	//foreach($datos as $row){
 	//		$i = 0;
    //		$var[$i]['ano_formular'] = $row['cfpd01_formulacion']['ano_formular'];
    //   	}
      // 	$this->set('ano_reformulacion',$var[$i]['ano_formular']);
     //  	$this->set('ano',$var[$i]['ano_formular']);
		$this->set('ano',$ano);
		//$sector=$this->v_cfpd05_denominaciones->generateList($cond, 'cod_sector ASC', null, '{n}.v_cfpd05_denominaciones.cod_sector', '{n}.v_cfpd05_denominaciones.deno_sector');
		$sector=  $this->cfpd02_sector->generateList($cond.' and ano='.$ano, 'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.denominacion');
		$sector = $sector != null ? $sector : array();
	  	$this->concatena($sector, 'sector');
	  	$lista=  $this->cugd02_dependencia->generateList('cod_tipo_institucion='.$cod_tipo_inst.' and cod_institucion='.$cod_inst, 'cod_dependencia ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
		$this->concatena_aux($lista, 'dep');
    }

/*
function select3($select=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";
	$de=$this->Session->read('SScoddep');
if($select!=null && $var!=null){
		if($de==1){
			$cond=$this->SQLCX();
		}
		else $cond=$this->SQLCA();

	switch($select){
		case 'sector'://echo "1";
		 $this->set('crear_auxiliar','no');
			$this->set('SELECT','programa');
			$this->set('codigo','sector');
			$this->set('seleccion','');
			$this->set('n',1);
			$this->Session->write('ano',$var);
			$cond .=" and ano=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_sector ASC', null, '{n}.v_cfpd05_denominaciones.cod_sector', '{n}.v_cfpd05_denominaciones.deno_sector');
					$this->concatena($lista, 'vector');
		break;
		case 'programa':
//echo "1";
 $this->set('crear_auxiliar','no');
			$this->set('SELECT','subprograma');
			$this->set('codigo','programa');
			$this->set('seleccion','');
			$this->set('n',2);
			$year_pago=$this->Session->read('year_pago')-date("Y");
			if($this->Session->read('year_pago')>date("Y")){
								$ano= 1+date("Y");
			}else{
							$ano=date("Y");
			}
			$this->Session->write('ano',$ano);
			$this->Session->write('sec',$var);
			$cond .=" and ano=".$ano." and cod_sector=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_programa ASC', null, '{n}.v_cfpd05_denominaciones.cod_programa', '{n}.v_cfpd05_denominaciones.deno_programa');
					$this->concatena($lista, 'vector');//print_r($lista);
		break;
		case 'subprograma'://echo "1";
		 $this->set('crear_auxiliar','no');
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
		case 'proyecto'://echo "1";
		 $this->set('crear_auxiliar','no');
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
		case 'actividad'://echo "1";
		 $this->set('crear_auxiliar','no');
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
		case 'partida'://echo "1";
		 $this->set('crear_auxiliar','no');
			$this->set('SELECT','generica');
			$this->set('codigo','partida');
			$this->set('seleccion','');
			$this->set('n',6);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy =  $this->Session->read('proy');
			$this->Session->write('acti',$var);
			$cond2 ="ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$var;
			//echo $cond2;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_partida ASC', null, '{n}.v_cfpd05_denominaciones.cod_partida', '{n}.v_cfpd05_denominaciones.deno_partida');

					$this->concatena($lista, 'vector');

		break;
		case 'generica'://echo "1";
		 $this->set('crear_auxiliar','no');
			$this->set('SELECT','especifica');
			$this->set('codigo','generica');
			$this->set('seleccion','');
			$this->set('n',7);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy =  $this->Session->read('proy');
			$acti =  $this->Session->read('acti');
			$this->Session->write('part',$var);
			$cond2 ="ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy."and cod_activ_obra=".$acti." and cod_partida=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_generica ASC', null, '{n}.v_cfpd05_denominaciones.cod_generica', '{n}.v_cfpd05_denominaciones.deno_generica');
					$this->concatena($lista, 'vector');
 		break;
		case 'especifica'://echo "1";
		 $this->set('crear_auxiliar','no');
			$this->set('SELECT','subespecifica');
			$this->set('codigo','especifica');
			$this->set('seleccion','');
			$this->set('n',8);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy =  $this->Session->read('proy');
			$acti =  $this->Session->read('acti');
			$part =  $this->Session->read('part');
			$this->Session->write('gene',$var);
			$cond2 ="ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy."and cod_activ_obra=".$acti." and cod_partida=".$part." and cod_generica=".$var;
			$lista = $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_especifica ASC', null, '{n}.v_cfpd05_denominaciones.cod_especifica', '{n}.v_cfpd05_denominaciones.deno_especifica');
					$this->concatena($lista, 'vector');
		break;
		case 'subespecifica'://echo "1";
		 	$this->set('crear_auxiliar','no');
			$this->set('SELECT','auxiliar');
			$this->set('codigo','subespecifica');
			$this->set('seleccion','');
			$this->set('n',9);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy =  $this->Session->read('proy');
			$acti =  $this->Session->read('acti');
			$part =  $this->Session->read('part');
			$gene =  $this->Session->read('gene');
			$this->Session->write('espe',$var);
			$cond2="ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy."and cod_activ_obra=".$acti." and cod_partida=".$part." and cod_generica=".$gene." and cod_especifica=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_sub_espec ASC', null, '{n}.v_cfpd05_denominaciones.cod_sub_espec', '{n}.v_cfpd05_denominaciones.deno_sub_espec');
					$this->concatena($lista, 'vector');
		break;
		case 'auxiliar'://echo "1";
			$this->set('SELECT','dependencia');
			$this->set('codigo','auxiliar');
			$this->set('seleccion',null);
			$this->set('n',10);
			//$this->set('auxiliar','no');
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy =  $this->Session->read('proy');
			$acti =  $this->Session->read('acti');
			$part =  $this->Session->read('part');
			$gene =  $this->Session->read('gene');
			$espe =  $this->Session->read('espe');
			$this->Session->write('sube',$var);
			$cond2="ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy."and cod_activ_obra=".$acti." and cod_partida=".$part." and cod_generica=".$gene." and cod_especifica=".$espe." and cod_sub_espec=".$var;
			//echo $cond." and ".$cond2;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond." and ".$cond2, 'cod_auxiliar ASC', null, '{n}.v_cfpd05_denominaciones.cod_auxiliar', '{n}.v_cfpd05_denominaciones.deno_auxiliar');
						if($lista!=null){
							$this->concatena($lista, 'vector');
						}else{
							$this->set('vector',array('0'=>'00'));
						}

		break;
	}
	}

	else{
			$this->set('SELECT','');
			$this->set('codigo','');
			$this->set('seleccion','');
			$this->set('n',14);
			$this->set('no','no');
			//echo "dddddd";
		 $this->set('vector',array('0'=>'00'));
	}
}//fin select codigos presupuestarios
*/


function select3($select=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";
	$de=$this->Session->read('SScoddep');
	if($select!=null && $var!=null){
		if($de==1){
			$cond=$this->SQLCX();
		}
		else $cond=$this->SQLCA();

	switch($select){
		case 'sector'://echo "1";
			$this->set('crear_auxiliar','no');
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
			$this->set('crear_auxiliar','no');
			$this->set('SELECT','subprograma');
			$this->set('codigo','programa');
			$this->set('seleccion','');
			$this->set('n',2);
			$ano=$this->ano_ejecucion();
			$this->Session->write('sec',$var);
			$cond .=" and ano=".$ano." and cod_sector=".$var;
			$lista=  $this->cfpd02_programa->generateList($cond, 'cod_programa ASC', null, '{n}.cfpd02_programa.cod_programa', '{n}.cfpd02_programa.denominacion');
			$this->concatena($lista, 'vector');
		break;
		case 'subprograma':
		 	$this->set('crear_auxiliar','no');
			$this->set('SELECT','proyecto');
			$this->set('codigo','subprograma');
			$this->set('seleccion','');
			$this->set('n',3);
			$sec =  $this->Session->read('sec');
			$this->Session->write('prog',$var);
			$ano=$this->ano_ejecucion();
			$cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$var;
			$lista=  $this->cfpd02_sub_prog->generateList($cond, 'cod_sub_prog ASC', null, '{n}.cfpd02_sub_prog.cod_sub_prog', '{n}.cfpd02_sub_prog.denominacion');
			$this->concatena($lista, 'vector');
		break;
		case 'proyecto':
		 	$this->set('crear_auxiliar','no');
			$this->set('SELECT','actividad');
			$this->set('codigo','proyecto');
			$this->set('seleccion','');
			$this->set('n',4);
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$this->Session->write('subp',$var);
			$ano=$this->ano_ejecucion();
			$cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$var;
			$lista=  $this->cfpd02_proyecto->generateList($cond, 'cod_proyecto ASC', null, '{n}.cfpd02_proyecto.cod_proyecto', '{n}.cfpd02_proyecto.denominacion');
			$this->concatena($lista, 'vector');
		break;
		case 'actividad':
		 	$this->set('crear_auxiliar','no');
			$this->set('SELECT','partida');
			$this->set('codigo','actividad');
			$this->set('seleccion','');
			$this->set('n',5);
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$this->Session->write('proy',$var);
			$ano=$this->ano_ejecucion();
			$cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$var;
			$lista=  $this->cfpd02_activ_obra->generateList($cond, 'cod_activ_obra ASC', null, '{n}.cfpd02_activ_obra.cod_activ_obra', '{n}.cfpd02_activ_obra.denominacion');
			$this->concatena($lista, 'vector');
		break;
		case 'partida':
		 	$this->set('crear_auxiliar','no');
			$this->set('SELECT','generica');
			$this->set('codigo','partida');
			$this->set('seleccion','');
			$this->set('n',6);
			$this->Session->write('actividad',$var);
			$ano=$this->ano_ejecucion();
			$cond2 ="ejercicio=".$ano." and cod_grupo=".CE."";
			$lista=  $this->cfpd01_ano_partida->generateList($cond2, 'cod_partida ASC', null, '{n}.cfpd01_ano_partida.cod_partida', '{n}.cfpd01_ano_partida.denominacion');
			$this->concatena($lista, 'vector',4);
		break;
		case 'generica':
		 	$this->set('crear_auxiliar','no');
			$this->set('SELECT','especifica');
			$this->set('codigo','generica');
			$this->set('seleccion','');
			$this->set('n',7);
			$ano=$this->ano_ejecucion();
			$this->Session->write('cpar',$var);
			$sec  =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy =  $this->Session->read('proy');
			$act  =  $this->Session->read('actividad');
			$cpar =  $var;
			$cpar=$cpar<10 ? "40".$cpar  : "4".$cpar;
			//echo $this->SQLCX().' and cod_sector='.$sec.' and cod_programa='.$prog.' and cod_sub_prog='.$subp.' and cod_proyecto='.$proy.' and cod_activ_obra='.$act.' and cod_partida='.$cpar;
			//$cf5 = $this->cfpd05->findAll($this->SQLCX().' and cod_sector='.$sec.' and cod_programa='.$prog.' and cod_sub_prog='.$subp.' and cod_proyecto='.$proy.' and cod_activ_obra='.$act.' and cod_partida='.$cpar);
			//pr($cf5);
			//if($cf5 != null){
			//	$cod_ramo 		= $cf5[0]['cfpd05']['cod_ramo'];
			//	$cod_subramo 	= $cf5[0]['cfpd05']['cod_subramo'];
			//	$cod_esp 		= $cf5[0]['cfpd05']['cod_esp'];
			//	$cod_subesp 	= $cf5[0]['cfpd05']['cod_subesp'];
			//	$cod_aux 		= $cf5[0]['cfpd05']['cod_aux'];
				//echo $cod_ramo==0 and $cod_subramo==0 and $cod_esp==0 and $cod_subesp==0 and $cod_aux==0;

			//	if($cod_ramo==0 and $cod_subramo==0 and $cod_esp==0 and $cod_subesp==0 and $cod_aux==0){//echo 'si';
			//		$this->set('errorMessage', 'Este código presupuestario no se encuentra asociado a los ingresos');
			//		echo'<script>';
       		//			echo" document.getElementById('plus').disabled = 'true'; ";
			//		echo'</script>';
			//	}else{
				//	echo'<script>';
       				//	echo" document.getElementById('plus').disabled = ''; ";
					//echo'</script>';
				//}
			//}
			$cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$var;
			$lista=  $this->cfpd01_ano_generica->generateList($cond2, 'cod_generica ASC', null, '{n}.cfpd01_ano_generica.cod_generica', '{n}.cfpd01_ano_generica.denominacion');
			$this->concatena($lista, 'vector');
 		break;
		case 'especifica':
		 	$this->set('crear_auxiliar','no');
			$this->set('SELECT','subespecifica');
			$this->set('codigo','especifica');
			$this->set('seleccion','');
			$this->set('n',8);
			$ano=$this->ano_ejecucion();
			$cpar =  $this->Session->read('cpar');
			$this->Session->write('cgen',$var);
			$cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$cpar." and cod_generica=".$var;
			$lista = $this->cfpd01_ano_especifica->generateList($cond2, 'cod_especifica ASC', null, '{n}.cfpd01_ano_especifica.cod_especifica', '{n}.cfpd01_ano_especifica.denominacion');
			$this->concatena($lista, 'vector');
		break;
		case 'subespecifica':
		 	$this->set('crear_auxiliar','no');
			$this->set('SELECT','auxiliar');
			$this->set('codigo','subespecifica');
			$this->set('seleccion','');
			$this->set('n',9);
			$ano=$this->ano_ejecucion();
			$cpar =  $this->Session->read('cpar');
			$cgen =  $this->Session->read('cgen');
			$this->Session->write('cesp',$var);
			$cond2 ="ejercicio=".$ano." and cod_grupo=".CE." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$var;
			$lista=  $this->cfpd01_ano_sub_espec->generateList($cond2, 'cod_sub_espec ASC', null, '{n}.cfpd01_ano_sub_espec.cod_sub_espec', '{n}.cfpd01_ano_sub_espec.denominacion');
			$this->concatena($lista, 'vector');
		break;
		case 'auxiliar':
			$this->set('SELECT','dependencia');
			$this->set('codigo','auxiliar');
			$this->set('seleccion',null);
			$this->set('n',10);
			$this->set('auxiliar','si');
			$ano=$this->ano_ejecucion();
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy =  $this->Session->read('proy');
			$act  =  $this->Session->read('actividad');
			$subp =  $this->Session->read('subp');
			$cpar =  $this->Session->read('cpar');
			$cgen =  $this->Session->read('cgen');
			$cesp =  $this->Session->read('cesp');
			$this->Session->write('csesp',$var);
			$cpar=$cpar<10 ? "40".$cpar  : "4".$cpar;
			$cond2 ="ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$act." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$var;
			//echo $cond." and ".$cond2;
			$lista=  $this->cfpd05_auxiliar->generateList($cond." and ".$cond2, 'cod_auxiliar ASC', null, '{n}.cfpd05_auxiliar.cod_auxiliar', '{n}.cfpd05_auxiliar.denominacion');
			if($lista!=null){
				$this->concatena_aux($lista, 'vector');
			}else{
				$this->set('vector',array('0'=>'0000'));
			}
		break;
		case 'auxiliar2':
			$this->set('auxiliar','si');
			$this->set('SELECT','escribir_aux');
			$this->set('codigo','auxiliar');
			$this->set('seleccion','');
			$this->set('n',10);
			$this->Session->write('actividad',$var);
			$f=$this->Session->read('CodigosDireccion');
			$p=$this->Session->read('partidas');
			$part= $p[0]['cscd01_catalogo']['cod_partida']<10 ? "40".$p[0]['cscd01_catalogo']['cod_partida']:$p[0]['cscd01_catalogo']['cod_partida'];
			$part= $part <400 ? "4".$part : $part;
			if($this->Session->read("year_pago")>date("Y")){
				$ano= 1+date("Y");
			}else{
				$ano=date("Y");
			}
			$cond2 =" cod_sector=".$f[0]["cugd02_direccion"]["cod_sector"]." and cod_programa=".$f[0]["cugd02_direccion"]["cod_programa"]." and cod_sub_prog=".$f[0]["cugd02_direccion"]["cod_sub_prog"]." and cod_proyecto=".$f[0]["cugd02_direccion"]["cod_proyecto"]." and cod_activ_obra=".$var." and ano=".$ano." and cod_partida=".$part." and cod_generica=".$p[0]["cscd01_catalogo"]["cod_generica"]." and cod_especifica=".$p[0]["cscd01_catalogo"]["cod_especifica"]." and cod_sub_espec=".$p[0]["cscd01_catalogo"]["cod_sub_espec"];
			$lista=  $this->cfpd05->generateList($cond2, 'cod_auxiliar ASC', null, '{n}.cfpd05.cod_auxiliar', '{n}.cfpd05.cod_auxiliar');
			if($lista!=null){
				$this->AddCero('vector',$lista);
			}else{
				$this->set('vector',array('0'=>'00'));
			}
		break;
		case 'escribir_aux':
			$this->Session->write('auxiliar',$var);
			$this->set("ocultar",true);
		break;
		case 'dependencia':
			if($var!='otros'){
		   		$this->set('crear_auxiliar','no');
		    	$this->set('SELECT','input');
				$this->set('codigo','dependencia');
				$this->set('seleccion','');
				$this->set('n',11);
				$sec =  $this->Session->read('sec');
				$prog =  $this->Session->read('prog');
				$subp =  $this->Session->read('subp');
				$proy=$this->Session->read('proy');
				$actividad=$this->Session->read('actividad');
				$cpar =  $this->Session->read('cpar');
				$cpar=$cpar< 10 ? CE."0".$cpar : CE.$cpar;
				$cgen =  $this->Session->read('cgen');
				$cesp =  $this->Session->read('cesp');
				$csesp=$this->Session->read('csesp');
				$this->Session->write('auxiliar',$var);
				$ano =  $this->ano_ejecucion();
				$condx="ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$actividad." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$csesp." and cod_auxiliar=".$var;
				$todo= $cond." and ".$condx;
				//echo $todo;
				$aa[5]=$this->verifica_SS(5);
				$lista = "";
				$lista =  $this->v_deno_dependencia->generateList($todo, 'cod_dep ASC', null, '{n}.v_deno_dependencia.cod_dep', '{n}.v_deno_dependencia.deno_dependencia');
            	$vars = "";
            	$vare = "";
            	if(count($lista)=="1" && !empty($lista)){
            		foreach($lista as $vars=>$vare){}
					$this->set('solo', 'si');
					$this->set('var', $vars);
					$this->set('deno_var', $vare);
			  	}else{
			  		$this->concatena4($lista, 'vector');
			  	}
			}else{
				$this->set('SELECT','input');
				$this->set('codigo','dependencia');
				$this->set('seleccion',null);
				$this->set('n',11);


				$this->set('vector',array());
			}
		break;
		case 'input':
			$this->set('n',12);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy=$this->Session->read('proy');
			$actividad=$this->Session->read('actividad');
			$cpar =  $this->Session->read('cpar');
			$cpar=$cpar< 10 ? CE."0".$cpar : CE.$cpar;
			$cgen =  $this->Session->read('cgen');
			$cesp =  $this->Session->read('cesp');
			$csesp=$this->Session->read('csesp');
			$aux = $this->Session->read('auxiliar');
			$codigoP = $ano.'/'.$sec.'/'.$prog.'/'.$subp.'/'.$proy.'/'.$actividad.'/'.$cpar.'/'.$cgen.'/'.$cesp.'/'.$csesp.'/'.$aux;

			$this->Session->write('codigoP',$codigoP);
			$this->set('input', true);
		break;
	}
	}
	else{
			$this->set('SELECT','');
			$this->set('codigo','');
			$this->set('seleccion','');
			$this->set('n',14);
			$this->set('no','no');
		 $this->set('vector',array('0'=>'00'));
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

function guardar(){
	$this->layout='ajax';
	//pr($this->data);
	$cod_presi 			= 	$this->verifica_SS(1);
	$cod_entidad 		= 	$this->verifica_SS(2);
	$cod_tipo_inst 		= 	$this->verifica_SS(3);
	$cod_inst 			= 	$this->verifica_SS(4);
	$ano 				= 	$this->data['consultar_auxiliares']['ano_partidas'];
    $cod_sector 		= 	$this->data['consultar_auxiliares']['cod_sector'];
	$cod_programa 		= 	$this->data['consultar_auxiliares']['cod_programa'];
	$cod_subprograma 	= 	$this->data['consultar_auxiliares']['cod_subprograma'];
	$cod_proyecto 		= 	$this->data['consultar_auxiliares']['cod_proyecto'];
	$cod_actividad 		= 	$this->data['consultar_auxiliares']['cod_actividad'];
	$d12 				= 	$this->data['consultar_auxiliares']['cod_partida'];
	$cod_partida 		= 	$d12 >10 ? CE.$d12 : CE."0".$d12 ;
	$cod_generica 		= 	$this->data['consultar_auxiliares']['cod_generica'];
	$cod_especifica 	= 	$this->data['consultar_auxiliares']['cod_especifica'];
	$cod_subespecifica 	= 	$this->data['consultar_auxiliares']['cod_subespecifica'];
	$cod_auxiliar		=	$this->data['consultar_auxiliares']['cod_auxiliar'];
	$cod_dependencia	=	$this->data['consultar_auxiliares']['cod_dependencia'];
	$dep_dos			=	$this->data['consultar_auxiliares']['dep_dos'];

	$ver1 = 'cod_presi='.$cod_presi.' and cod_entidad='.$cod_entidad.' and cod_tipo_inst='.$cod_tipo_inst.' and cod_inst='.$cod_inst.' and cod_dep='.$cod_dependencia.' and ano='.$ano.' and cod_sector='.$cod_sector.' and cod_programa='.$cod_programa;
	$ver1.=' and cod_sub_prog='.$cod_subprograma.' and cod_proyecto='.$cod_proyecto.' and cod_activ_obra='.$cod_actividad.' and cod_partida='.$cod_partida.' and cod_generica='.$cod_generica.' and cod_especifica='.$cod_especifica.' and cod_sub_espec='.$cod_subespecifica.' and cod_auxiliar='.$cod_auxiliar;

	$ver2 = 'cod_presi='.$cod_presi.' and cod_entidad='.$cod_entidad.' and cod_tipo_inst='.$cod_tipo_inst.' and cod_inst='.$cod_inst.' and cod_dep='.$dep_dos.' and ano='.$ano.' and cod_sector='.$cod_sector.' and cod_programa='.$cod_programa;
	$ver2.=' and cod_sub_prog='.$cod_subprograma.' and cod_proyecto='.$cod_proyecto.' and cod_activ_obra='.$cod_actividad.' and cod_partida='.$cod_partida.' and cod_generica='.$cod_generica.' and cod_especifica='.$cod_especifica.' and cod_sub_espec='.$cod_subespecifica.' and cod_auxiliar='.$cod_auxiliar;

	$ver3 = 'cod_presi='.$cod_presi.' and cod_entidad='.$cod_entidad.' and cod_tipo_inst='.$cod_tipo_inst.' and cod_inst='.$cod_inst.' and cod_dep='.$dep_dos.' and ano='.$ano.' and cod_sector='.$cod_sector.' and cod_programa='.$cod_programa.' and cod_sub_prog='.$cod_subprograma.' and cod_proyecto='.$cod_proyecto.' and cod_activ_obra='.$cod_actividad;

	$ver4 = 'cod_presi='.$cod_presi.' and cod_entidad='.$cod_entidad.' and cod_tipo_inst='.$cod_tipo_inst.' and cod_inst='.$cod_inst.' and codi_dep='.$cod_dependencia.' and ano='.$ano.' and cod_sector='.$cod_sector.' and cod_programa='.$cod_programa;
	$ver4.=' and cod_sub_prog='.$cod_subprograma.' and cod_proyecto='.$cod_proyecto.' and cod_activ_obra='.$cod_actividad.' and cod_partida='.$cod_partida.' and cod_generica='.$cod_generica.' and cod_especifica='.$cod_especifica.' and cod_sub_espec='.$cod_subespecifica.' and cod_auxiliar='.$cod_auxiliar;

	$sel1 = ('select compromiso_anual from cfpd05 where '.$ver1);
	$comp = $this->cfpd05->execute($sel1);
	$compr= $comp[0][0]['compromiso_anual'];/////compromiso de la partida que se va a cambiar
	if($compr != 0){
		$this->set('errorMessage', 'ESTE C&Oacute;DIGO PRESUPUESTARIO TIENE MOVIMIENTOS');
	}


	$exist= $this->cfpd02_activ_obra->findCount($ver3);///////verficar si la categoria programatica existe en la nueva dep
	if($exist == 0){
		$this->set('errorMessage', 'La categot&iacute;a programatica no esta creada para la dependencia donde se quiere cambiar');
	}


	if($compr==0 && $exist==1){
		$upd_reform1 = 'update cfpd10_reformulacion_partidas set codi_dep='.$dep_dos.' where '.$ver4;
		$upd_reform2 = 'update cfpd10_reformulacion_partidas_tmp set codi_dep='.$dep_dos.' where '.$ver4;
		$upd_cfpd05  = 'update cfpd05 set cod_dep='.$dep_dos.' where '.$ver1;
		$upd_cfpd20  = 'update cfpd20 set cod_dep='.$dep_dos.' where '.$ver1;
		$this->cfpd10_reformulacion_partidas->execute($upd_reform1);
		$this->cfpd10_reformulacion_partidas_tmp->execute($upd_reform2);
		$this->cfpd05->execute($upd_cfpd05);
		$this->cfpd20->execute($upd_cfpd20);
		$this->set('Message_existe', 'EL C&Oacute;DIGO PRESUPUESTARIO FUE CAMBIADO CON EXITO');
	}


	//e($exist);
	//e($ver1);
	//e($ver2);
	$this->index();
	$this->render('index');

}

}//fin clase cfpp09Controller