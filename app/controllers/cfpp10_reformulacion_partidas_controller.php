<?php
 class Cfpp10ReformulacionPartidasController extends AppController{
	 var $name = 'cfpp10_reformulacion_partidas';
     var $uses = array('v_solicitud_cfpd05_p2','ccfd04_cierre_mes','ccfd03_instalacion','cfpd07_obras_partidas','cfpp05auxiliar','cugd02_dependencia','v_cfpd05_denominaciones','cfpd10_reformulacion_tipo','cfpd10_reformulacion_texto','v_deno_dependencia','cfpd01_formulacion','cfpd10_reformulacion_funcionarios','cfpd10_reformulacion_partidas','cfpd10_reformulacion_partidas_tmp','cugd03_acta_anulacion_numero','cugd03_acta_anulacion_cuerpo','v_cfpd05_disponibilidad','cugd04','cpcd02','cscd01_catalogo','cepd01_tipo_compromiso','cepd01_compromiso_cuerpo','cepd01_compromiso_numero','cepd01_compromiso_partidas','cfpd05','cfpd05_requerimiento','cfpd05_2032_tmp','cfpd05_auxiliar','cfpp05auxiliar','cfpd02_sector', 'cfpd02_programa', 'cfpd02_sub_prog', 'cfpd02_proyecto', 'cfpd02_activ_obra', 'arrd05','cfpd01_ano_grupo', 'cfpd01_ano_partida', 'cfpd01_ano_generica', 'cfpd01_ano_especifica', 'cfpd01_ano_sub_espec', 'cfpd01_ano_auxiliar', 'cfpd01_formulacion','cugd02_direccionsuperior', 'cugd02_coordinacion', 'cugd02_secretaria', 'cugd02_direccion');
     var $helpers = array('Html','Ajax','Javascript', 'Sisap');
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



function index($opc=null,$numero_ofi=null){
	$this->data['cfpp10_reformulacion_partidas']=null;
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$ano=$this->ano_ejecucion();
	$de=$this->Session->read('SScoddep');
	if($opc == 'si'){
		$condj=$this->SQLCA();
		$cond = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
		$veri="decretado != '1'";
		$oficio=$this->cfpd10_reformulacion_texto->generateList($condj." and ".$veri." and ano_reformulacion=".$ano,'numero_oficio ASC', null, '{n}.cfpd10_reformulacion_texto.numero_oficio', '{n}.cfpd10_reformulacion_texto.numero_oficio');
		$this->set('numero_oficio',$oficio);
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
		$this->set('ano',$ano);
		$this->set('ano_reformulacion',$ano);
		$this->set('opc','si');
	}else if($opc=='no'){///////////////////viene del oficio
		$this->set('Message_existe', 'Registro Agregado con exito.');
		$condj=$this->SQLCA();
		$cond = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
		$veri="decretado != '1'";
		$oficio=$this->cfpd10_reformulacion_texto->generateList($condj." and ".$veri." and ano_reformulacion=".$ano,'numero_oficio ASC', null, '{n}.cfpd10_reformulacion_texto.numero_oficio', '{n}.cfpd10_reformulacion_texto.numero_oficio');
		$this->set('numero_oficio',$oficio);
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
		$this->set('ano',$ano);
		$this->set('ano_reformulacion',$ano);
		$this->set('opc','no');
		$datos=$this->cfpd10_reformulacion_texto->findAll($condj." and ".$veri." and ano_reformulacion=".$ano." and numero_oficio='".$numero_ofi."'");
		$codx=$datos[0]['cfpd10_reformulacion_texto']['cod_tipo'];
		$b = $this->cfpd10_reformulacion_tipo->findAll("cod_tipo=".$codx,array('cod_tipo','denominacion'));
		$this->set("tipo",$b[0]['cfpd10_reformulacion_tipo']['denominacion']);
		$this->set('datos',$datos);
		$des=$this->Session->read('SScoddep');
		if($des==1){
			$conds=$this->SQLCX();
		}
		else $conds=$this->SQLCA();
		$rs=$this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_sector,deno_sector FROM v_cfpd05_denominaciones WHERE ". $conds." and ano=".$ano." ORDER BY cod_sector ASC");
		foreach($rs as $l){
			$v[]=$l[0]["cod_sector"];
			$d[]=$l[0]["deno_sector"];
		}
		if(isset($v) &&  is_array($v)){
			$sector = array_combine($v, $d);
		}else{
			$sector = array("0"=>"N/A");
		}
		$sector = $sector != null ? $sector : array();
		$this->concatena($sector, 'sector');
	}
}

function select3($select=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";
	// echo $this->Session->read('xxxx');
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
					//echo $vars;
					$ano2 =  $this->ano_ejecucion();
					/*utlizar aqui $vars para el codigo de dependencia para la validacion de la conexion de ingresos-egresos*/
////inicio contabilidad
/*
					$ctbl = $this->SQLCX().'and ano='.$ano2.' and cod_sector='.$sec.' and cod_programa='.$prog.' and cod_sub_prog='.$subp.' and cod_proyecto='.$proy.' and cod_activ_obra='.$actividad.' and cod_partida='.$cpar.' and cod_dep='.$vars;
					$cf5 = $this->cfpd05->findAll($ctbl);
					if($cf5 != null){
						$cod_ramo 		= $cf5[0]['cfpd05']['cod_ramo'];
						$cod_subramo 	= $cf5[0]['cfpd05']['cod_subramo'];
						$cod_esp 		= $cf5[0]['cfpd05']['cod_esp'];
						$cod_subesp 	= $cf5[0]['cfpd05']['cod_subesp'];
						$cod_aux 		= $cf5[0]['cfpd05']['cod_aux'];
						//echo $cod_ramo==0 and $cod_subramo==0 and $cod_esp==0 and $cod_subesp==0 and $cod_aux==0;
						if($cod_ramo==0 and $cod_subramo==0 and $cod_esp==0 and $cod_subesp==0 and $cod_aux==0){//echo 'si';
							$this->set('errorMessage', 'Este código presupuestario no se encuentra asociado a los ingresos');
							echo'<script>';
       						echo" document.getElementById('plus').disabled = 'true'; ";
							echo'</script>';
						}else{
							echo'<script>';
       						echo" document.getElementById('plus').disabled = ''; ";
							echo'</script>';
						}
					}
*/
////fin contabilidad
			  	}else{
			  		$this->concatena4($lista, 'vector');
////ic
		//	  		$this->set('ocultar','si');
////fc

			  	}
			}else{
				$this->set('SELECT','input');
				$this->set('codigo','dependencia');
				$this->set('seleccion',null);
				$this->set('n',11);


				$this->set('vector',array());
			}
			//echo $var;
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
            //echo $var;
			$this->Session->write('codigoP',$codigoP);
			$this->set('input', true);
			/*utlizar aqui $var para el codigo de dependencia para la validacion de la conexion de ingresos-egresos*/
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


function ver_cont($var){
	$this->layout="ajax";
	$ano =  $this->ano_ejecucion();
	$sec =  $this->Session->read('sec');
	$prog =  $this->Session->read('prog');
	$subp =  $this->Session->read('subp');
	$proy=$this->Session->read('proy');
	$actividad=$this->Session->read('actividad');
	$cpar =  $this->Session->read('cpar');
	$cpar=$cpar< 10 ? CE."0".$cpar : CE.$cpar;
	$ctbl = $this->SQLCX().'and ano='.$ano.' and cod_sector='.$sec.' and cod_programa='.$prog.' and cod_sub_prog='.$subp.' and cod_proyecto='.$proy.' and cod_activ_obra='.$actividad.' and cod_partida='.$cpar.' and cod_dep='.$var;
	//echo $ctbl;
	$cf5 = $this->cfpd05->findAll($ctbl);
	if($cf5 != null){
		$cod_ramo 		= $cf5[0]['cfpd05']['cod_ramo'];
		$cod_subramo 	= $cf5[0]['cfpd05']['cod_subramo'];
		$cod_esp 		= $cf5[0]['cfpd05']['cod_esp'];
		$cod_subesp 	= $cf5[0]['cfpd05']['cod_subesp'];
		$cod_aux 		= $cf5[0]['cfpd05']['cod_aux'];
		if($cod_ramo==0 and $cod_subramo==0 and $cod_esp==0 and $cod_subesp==0 and $cod_aux==0){//echo 'si';
			$this->set('errorMessage', 'Este código presupuestario no se encuentra asociado a los ingresos');
			echo'<script>';
       		echo" document.getElementById('plus').disabled = 'true'; ";
			echo'</script>';
			}else{
			echo'<script>';
       		echo" document.getElementById('plus').disabled = ''; ";
			echo'</script>';
			}
	}
}

function ver_disponibilidad($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar, $monto){
	$this->layout="ajax";
	$disponibilidad = $this->disponibilidad($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar);
	if($monto > $disponibilidad){
		$this->set('msg_error', 'la disponibilidad para esta menor al Monto');
	}
}



function agregar_partidas(){
	$this->layout = "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	if(!empty($this->data)){
		$di=1;
		$dispo_soli=0;
		$monto=0;
		$ano_reformulacion=$this->data['cfpp10_reformulacion_partidas']['ano_reformulacion'];
		$numero_oficio=$this->data['cfpp10_reformulacion_partidas']['numero_oficio'];
		$codi_dep=$this->data['cfpp10_reformulacion_partidas']['cod_dependencia'];
		$ano=$this->data['cfpp10_reformulacion_partidas']['ano_partidas'];
		$cod_sector=$this->data['cfpp10_reformulacion_partidas']['cod_sector'];
		$cod_programa=$this->data['cfpp10_reformulacion_partidas']['cod_programa'];
		$cod_sub_prog=$this->data['cfpp10_reformulacion_partidas']['cod_subprograma'];
		$cod_proyecto=$this->data['cfpp10_reformulacion_partidas']['cod_proyecto'];
		$cod_activ_obra=$this->data['cfpp10_reformulacion_partidas']['cod_actividad'];
		$cod_partida=$this->data['cfpp10_reformulacion_partidas']['cod_partida'];
		$cod_partida= $cod_partida< 10 ? CE."0".$cod_partida : CE.$cod_partida;
		$cod_generica=$this->data['cfpp10_reformulacion_partidas']['cod_generica'];
		$cod_especifica=$this->data['cfpp10_reformulacion_partidas']['cod_especifica'];
		$cod_sub_espec=$this->data['cfpp10_reformulacion_partidas']['cod_subespecifica'];
		$cod_auxiliar=$this->data['cfpp10_reformulacion_partidas']['cod_auxiliar'];
		$this->set('numero_oficio',$numero_oficio);
		if(isset ($this->data['cfpp10_reformulacion_partidas']['monto_deduccion'])){
			$monto_disminucion=$this->Formato1($this->data['cfpp10_reformulacion_partidas']['monto_deduccion']);
		}else if(!isset ($this->data['cfpp10_reformulacion_partidas']['monto_deduccion'])){
		$monto_disminucion=0;
		}
	    if(isset ($this->data['cfpp10_reformulacion_partidas']['monto_aumento'])){
		$monto_aumento=$this->Formato1($this->data['cfpp10_reformulacion_partidas']['monto_aumento']);
		}else if (!isset ($this->data['cfpp10_reformulacion_partidas']['monto_aumento'])){
		$monto_aumento=0;
		}
	    $aa[1]=$this->verifica_SS(1);
	  	$aa[2]=$this->verifica_SS(2);
	  	$aa[3]=$this->verifica_SS(3);
	  	$aa[4]=$this->verifica_SS(4);
	  	$aa[5]=$this->verifica_SS(5);
		$validar=$this->SQLCX()." and cod_dep=".$codi_dep." and ano =".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and  cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." and numero_oficio='".$numero_oficio."'";
		$valida_obra =$this->SQLCX()." and cod_dep=".$codi_dep." and ano_estimacion =".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and  cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar;
        $valida_obra2=$this->SQLCX()." and cod_dep=".$codi_dep." and ano =".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and  cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar;

        $validar_disminucion_formulacion=$this->SQLCX()." and codi_dep=".$codi_dep." and ano =".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and  cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar;

        $validar_disminucion_presupuesto=$this->SQLCX()." and ano =".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and  cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar;


		if($monto_aumento==0){
			$cont_obras=$this->cfpd07_obras_partidas->findCount($valida_obra);
		}else{
			$cont_obras=0;
		}

		if($cont_obras > 0){
			    $monto=0;
			    $disponibilidad2 = $this->disponibilidad_dep($ano, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar, $codi_dep);
				$datas           = $this->cfpd07_obras_partidas->findAll($valida_obra);
				$datascfpd05     = $this->cfpd05->findAll($valida_obra2);
				$precompromiso   = $datascfpd05[0]["cfpd05"]["precompromiso_obras"];
				$monto12             = 0;
			    $monto_contratado12  = 0;
			    $aumento_obras12     = 0;
			    $disminucion_obras12 = 0;
				foreach($datas as $data){
	                $monto12             += $data["cfpd07_obras_partidas"]["monto"];
				    $monto_contratado12  += $data["cfpd07_obras_partidas"]["monto_contratado"];
				    $aumento_obras12     += $data["cfpd07_obras_partidas"]["aumento_obras"];
				    $disminucion_obras12 += $data["cfpd07_obras_partidas"]["disminucion_obras"];
				}

				$numeroo = $this->cfpd07_obras_partidas->field('cod_obra',$valida_obra,null);
				if($monto_disminucion!=0){
					$montoajustado   = (($monto12+$aumento_obras12)-($monto_contratado12+$disminucion_obras12));
					$disponibilidad3 = ($disponibilidad2 -$montoajustado);

					$disponibilidad3 = $this->Formato2($disponibilidad3);
					$disponibilidad3 = $this->Formato1($disponibilidad3);

						if($monto_disminucion>$disponibilidad3){
                    		$monto=1;
							}
						if($disponibilidad2>=$monto_disminucion){
							$monto=0;
							}
				}
		}else{
			$monto=0;

		}
		if($monto == 1){
				$this->set('errorMessage', 'NO PUEDE DISMINUIR ESTA PARTIDA, TIENE RELACION DE OBRAS NRO. '.$numeroo.', PENDIENTE');
				$datos = $this->cfpd10_reformulacion_partidas_tmp->findAll($this->SQLCA()." and numero_oficio='".$numero_oficio."'",null,'codi_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
				$this->set("datos",$datos);

				echo '<script>';
        		echo " document.getElementById('monto_deduccion').value=''   ;";
        		echo " document.getElementById('monto_aumento').value=''   ;";
        		echo " document.getElementById('monto_deduccion').disabled=false; ";
        		echo " document.getElementById('monto_aumento').disabled=false; ";
        		echo " document.getElementById('porcentaje_ajust').value = '';";
           		echo " document.getElementById('seleccion_6').options[1].selected = true; ";
           		echo " document.getElementById('seleccion_7').innerHTML='<select></select>';  ";
           		echo " document.getElementById('seleccion_8').innerHTML='<select></select>';  ";
           		echo " document.getElementById('seleccion_9').innerHTML='<select></select>';  ";
           		echo " document.getElementById('seleccion_10').innerHTML='<select></select>';  ";
           		echo " document.getElementById('st_seleccion_11').innerHTML='<select id=seleccion_11 class=select100>';  ";
     			echo '</script>';
		}

		//VERIFICACION QUE PARTIDA QUE INTENTEN AUMENTAR NO TENGA UNA DISMINUCION REGISTRADA
		//ACTIVAR BLOQUE DE PARTIDA
		// para activar bloqueo de partidas descomentar la siguiente condicion
		/*if($monto_aumento!=0)
		{
			//SQL DE CONSULTA DE LA PARTIDA
			$partidaDism_reformulacion_tmp = $this->cfpd10_reformulacion_partidas_tmp->execute("SELECT monto_disminucion FROM cfpd10_reformulacion_partidas_tmp WHERE ".$validar_disminucion_formulacion);
			
			$partidaDism_reformulacion = $this->cfpd10_reformulacion_partidas_tmp->execute("SELECT monto_disminucion FROM cfpd10_reformulacion_partidas WHERE ".$validar_disminucion_formulacion);

			$partidaDism_presupuesto = $this->cfpd10_reformulacion_partidas_tmp->execute("SELECT disminucion_traslado_anual FROM cfpd05 WHERE ".$validar_disminucion_presupuesto);
			
			//VERIFICANDO DISMINUCION
			$disminucion_anteriror= $partidaDism_reformulacion_tmp[0][0]["monto_disminucion"] + $partidaDism_reformulacion[0][0]["monto_disminucion"] +  $partidaDism_presupuesto[0][0]["disminucion_traslado_anual"];

			if($disminucion_anteriror>0){
				
				$monto=1;

				$this->set('errorMessage', 'NO PUEDE AUMENTAR ESTA PARTIDA, TIENE UNA DISMINUCION REGISTRADA');

				$datos = $this->cfpd10_reformulacion_partidas_tmp->findAll($this->SQLCA()." and numero_oficio='".$numero_oficio."'",null,'codi_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
				$this->set("datos",$datos);

				echo '<script>';
        		echo " document.getElementById('monto_deduccion').value=''   ;";
        		echo " document.getElementById('monto_aumento').value=''   ;";
        		echo " document.getElementById('monto_deduccion').disabled=false; ";
        		echo " document.getElementById('monto_aumento').disabled=false; ";
        		echo " document.getElementById('porcentaje_ajust').value = '';";
           		echo " document.getElementById('seleccion_6').options[1].selected = true; ";
           		echo " document.getElementById('seleccion_7').innerHTML='<select></select>';  ";
           		echo " document.getElementById('seleccion_8').innerHTML='<select></select>';  ";
           		echo " document.getElementById('seleccion_9').innerHTML='<select></select>';  ";
           		echo " document.getElementById('seleccion_10').innerHTML='<select></select>';  ";
           		echo " document.getElementById('st_seleccion_11').innerHTML='<select id=seleccion_11 class=select100>';  ";
     			echo '</script>';
			}
		}
*/
		if($monto==0){
		if($monto_disminucion!=0){
			$disponibilidad = $this->disponibilidad_dep($ano_reformulacion, $cod_sector, $cod_programa, $cod_sub_prog, $cod_proyecto, $cod_activ_obra, $cod_partida, $cod_generica, $cod_especifica, $cod_sub_espec, $cod_auxiliar, $codi_dep);

					/*
						$veri=$this->v_solicitud_cfpd05_p2->findCount('ano='.$ano.' and cod_presi='.$cod_presi.' and cod_entidad='.$cod_entidad.' and cod_tipo_inst='.$cod_tipo_inst.' and cod_inst='.$cod_inst.' and cod_dep='.$codi_dep.' and cod_sector='.$cod_sector.' and cod_programa='.$cod_programa.' and cod_sub_prog='.$cod_sub_prog.' and cod_proyecto='.$cod_proyecto.' and cod_activ_obra='.$cod_activ_obra.' and cod_partida='.$cod_partida.' and cod_generica='.$cod_generica.' and cod_especifica='.$cod_especifica.' and cod_sub_espec='.$cod_sub_espec.' and cod_auxiliar='.$cod_auxiliar);
					if($veri==0){
						$dispo_soli=0;
						$si=0;
					}else{
						$si=1;
						$soli=$this->v_solicitud_cfpd05_p2->findAll('ano='.$ano.' and cod_presi='.$cod_presi.' and cod_entidad='.$cod_entidad.' and cod_tipo_inst='.$cod_tipo_inst.' and cod_inst='.$cod_inst.' and cod_dep='.$codi_dep.' and cod_sector='.$cod_sector.' and cod_programa='.$cod_programa.' and cod_sub_prog='.$cod_sub_prog.' and cod_proyecto='.$cod_proyecto.' and cod_activ_obra='.$cod_activ_obra.' and cod_partida='.$cod_partida.' and cod_generica='.$cod_generica.' and cod_especifica='.$cod_especifica.' and cod_sub_espec='.$cod_sub_espec.' and cod_auxiliar='.$cod_auxiliar);
				 		foreach($soli as $rows){
							$dispo_soli  = $rows['v_solicitud_cfpd05_p2']['asignacion_anual_actualizada'];
				 		}
				 }
					}
					if($dispo_soli< $monto_disminucion && $si==1){
						$this->set('errorMessage', 'No se puede rebajar dicho monto, esos recursos fuerón solicitados');
					$datos = $this->cfpd10_reformulacion_partidas_tmp->findAll("numero_oficio='".$numero_oficio."'",null,'codi_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
						$this->set("datos",$datos);
					    echo'<script>';
			        	echo "document.getElementById('monto_deduccion').value=''   ;";
			        	echo "document.getElementById('monto_aumento').value=''   ;";
			        	echo "document.getElementById('monto_deduccion').disabled=false; ";
			        	echo "document.getElementById('monto_aumento').disabled=false; ";
			        	echo'</script>';
					}else if(isset ($disponibilidad) && $disponibilidad < $monto_disminucion && $dispo_soli > $monto_disminucion){
			*/

		if($disponibilidad < $monto_disminucion){//reemplazr por la de arriba
			$di=0;
			$this->set('errorMessage', 'la disponibilidad para esta partida es menor al Monto a disminuir');
			$datos = $this->cfpd10_reformulacion_partidas_tmp->findAll($this->SQLCA()." and numero_oficio='".$numero_oficio."'",null,'codi_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
			$this->set("datos",$datos);
		    echo'<script>';
        	echo "document.getElementById('monto_deduccion').value=''   ;";
        	echo "document.getElementById('monto_aumento').value=''   ;";
        	echo "document.getElementById('monto_deduccion').disabled=false; ";
        	echo "document.getElementById('monto_aumento').disabled=false; ";
        	echo "document.getElementById('porcentaje_ajust').value = '';";
     		echo'</script>';
		}
		}
		if($di!=0 && $monto==0){
			$verificar=$this->cfpd10_reformulacion_partidas_tmp->findCount($validar);
			if($verificar==0){
     			$sql ="INSERT INTO cfpd10_reformulacion_partidas_tmp (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_reformulacion, numero_oficio,
     			codi_dep, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec,
     			cod_auxiliar, monto_disminucion, monto_aumento)";
	 			$sql .=" VALUES (".$aa[1].",".$aa[2].",".$aa[3].",".$aa[4].",".$aa[5].", ".$ano_reformulacion.",'".$numero_oficio."', ".$codi_dep.", ".$ano.",
     			".$cod_sector.", ".$cod_programa.", ".$cod_sub_prog.", ".$cod_proyecto.", ".$cod_activ_obra.", ".$cod_partida.", ".$cod_generica.",
     			".$cod_especifica.", ".$cod_sub_espec.", ".$cod_auxiliar.", ".$monto_disminucion.", ".$monto_aumento.")";
     			$resp=$this->cfpd10_reformulacion_partidas_tmp->execute($sql);
     		if($monto_disminucion != 0){
     			$validaxx="ano =".$ano." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto." and cod_activ_obra=".$cod_activ_obra." and  cod_partida=".$cod_partida." and cod_generica=".$cod_generica." and cod_especifica=".$cod_especifica." and cod_sub_espec=".$cod_sub_espec." and cod_auxiliar=".$cod_auxiliar." and cod_dep=".$codi_dep;
     			$ud05="update cfpd05 set precompromiso_congelado=precompromiso_congelado + $monto_disminucion where ".$validaxx;
     			$respxx=$this->cfpd05->execute($ud05);
     		}
     		$datos = $this->cfpd10_reformulacion_partidas_tmp->findAll($this->SQLCA()." and numero_oficio='".$numero_oficio."'",null,'codi_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
     		$this->set("datos",$datos);
     		echo'<script>';
        	echo "document.getElementById('monto_deduccion').value=''   ;";
        	echo "document.getElementById('monto_aumento').value=''   ;";
        	echo "document.getElementById('monto_deduccion').disabled=false; ";
        	echo "document.getElementById('monto_aumento').disabled=false; ";
        	echo "document.getElementById('porcentaje_ajust').value = '';";
           	echo"   document.getElementById('seleccion_6').options[1].selected = true; ";
           	echo"   document.getElementById('seleccion_7').innerHTML='<select></select>';  ";
           	echo"   document.getElementById('seleccion_8').innerHTML='<select></select>';  ";
           	echo"   document.getElementById('seleccion_9').innerHTML='<select></select>';  ";
           	echo"   document.getElementById('seleccion_10').innerHTML='<select></select>';  ";
           	echo"   document.getElementById('st_seleccion_11').innerHTML='<select id=seleccion_11 class=select100>';  ";
     		echo'</script>';
			}else {
				$datos = $this->cfpd10_reformulacion_partidas_tmp->findAll($this->SQLCA()." and numero_oficio='".$numero_oficio."'",null,'codi_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
				$this->set("datos",$datos);
				$this->set('errorMessage', 'Esta Partida ya fue Agregada a la Reformulacion');
			}
		}
			//}
		}
	}
}//fin funtion



function oficio($var){
	$this->layout = "ajax";
	$a = $this->cfpd10_reformulacion_texto->findAll($this->SQLCA()." and numero_oficio='".$var."'",array('numero_oficio'));
    $this->set("numero_oficio",$a[0]['cfpd10_reformulacion_texto']['numero_oficio']);

}//fin oficio



function fecha($var){
	$this->layout = "ajax";
	$ano=$this->ano_ejecucion();
	$a = $this->cfpd10_reformulacion_texto->findAll("ano_reformulacion=".$ano." and ".$this->SQLCA()." and numero_oficio='".$var."'",array('fecha_oficio'));
    $fec=$a[0]['cfpd10_reformulacion_texto']['fecha_oficio'];
    $this->set("fecha_oficio",$fec);
}//fin fecha


function tipo($var){
	$this->layout = "ajax";
	$ano=$this->ano_ejecucion();
	$a = $this->cfpd10_reformulacion_texto->findAll("ano_reformulacion=".$ano." and ".$this->SQLCA()." and numero_oficio='".$var."'",array('cod_tipo'));
    $cod=$a[0]['cfpd10_reformulacion_texto']['cod_tipo'];//echo $cod;
	$b = $this->cfpd10_reformulacion_tipo->findAll("cod_tipo=".$cod,array('cod_tipo','denominacion'));
    $this->set("tipo",$b[0]['cfpd10_reformulacion_tipo']['denominacion']);
    $this->set("codigo",$b[0]['cfpd10_reformulacion_tipo']['cod_tipo']);

}//fin tipo


function razon_monto($var){

	$this->layout = "ajax";
	$ano=$this->ano_ejecucion();
	$de=$this->Session->read('SScoddep');

	if($de==1){
		$cond=$this->SQLCX();
	}
	else $cond=$this->SQLCA();
	$a = $this->cfpd10_reformulacion_texto->findAll("ano_reformulacion=".$ano." and ".$this->SQLCA()." and numero_oficio='".$var."'",array('concepto','monto','cod_tipo'));
    $this->set("razon",$a[0]['cfpd10_reformulacion_texto']['concepto']);
    $this->set("monto",$a[0]['cfpd10_reformulacion_texto']['monto']);
    $this->set("codigo",$a[0]['cfpd10_reformulacion_texto']['cod_tipo']);
    $this->set('numero_oficio',$var);
	$datos = $this->cfpd10_reformulacion_partidas_tmp->findAll($this->SQLCA()." and numero_oficio='".$var."'",null,'codi_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
    $this->set("datos",$datos);
	$ano=$this->ano_ejecucion();
	$this->set('ano',$ano);
	$lista=  $this->cfpd02_sector->generateList($cond, 'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.denominacion');
	$this->concatena($lista, 'sector');

	//$rs=$this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_sector,deno_sector FROM v_cfpd05_denominaciones WHERE ". $cond." and ano=".$ano." ORDER BY cod_sector ASC");
    //	foreach($rs as $l){
	//		$v[]=$l[0]["cod_sector"];
	//		$d[]=$l[0]["deno_sector"];
	//	}
	//	if(isset($v) &&  is_array($v)){
	//		$sector = array_combine($v, $d);
	//	}else{
     //  		$sector = array("0"=>"N/A");
	//	}
	//$sector = $sector != null ? $sector : array();
	//$this->concatena($sector, 'sector');
	$aa5=$this->verifica_SS(5);
    if($aa5==1){
		$dep=1;
	}else $dep=2;
	$this->set('dep',$dep);
	$this->set('dependencia',$aa5);
}//fin razon_monto

function guardar(){
	$this->layout = "ajax";
	$numero_oficio=$this->data['cfpp10_reformulacion_partidas']['numero_oficio'];
	$de=$this->Session->read('SScoddep');
   	$sql10txt="update cfpd10_reformulacion_texto set  elaborado='1'
   	where numero_oficio='".$numero_oficio."' and cod_dep=".$de;
  	$limpiar=$this->cfpd10_reformulacion_texto->execute($sql10txt);
  	$this->set('Message_existe', 'Registro Agregado con exito.');
   	$this->index('si');
	$this->render("index");
}
function eliminar_items($numero_oficio,$varano,$varsec,$varpro,$varsub,$varproy,$varact,$varpar,$vargen,$varesp,$varsube,$varaux,$varp,$vare,$vart,$vari,$vard,$disminuir,$varde){
	$this->layout = "ajax";
	$aa1=$this->verifica_SS(1);
  	$aa2=$this->verifica_SS(2);
  	$aa3=$this->verifica_SS(3);
  	$aa4=$this->verifica_SS(4);
	$del=$this->SQLCX()." and numero_oficio='".$numero_oficio."'"." and ano_reformulacion=".$varano." and cod_sector=".$varsec." and cod_programa=".$varpro." and cod_sub_prog=".$varsub." and cod_proyecto=".$varproy." and cod_activ_obra=".$varact." and cod_partida=".$varpar." and cod_generica=".$vargen." and cod_especifica=".$varesp." and cod_sub_espec=".$varsube." and  cod_auxiliar=".$varaux." and codi_dep=".$vard;
	$el_revi="numero_oficio='".$numero_oficio."'";
	$ojo="numero_oficio='".$numero_oficio."' and ano_reformulacion=".$varano." and cod_dep=".$varde;
	$datostexto2=$this->cfpd10_reformulacion_texto->findAll($this->SQLCA()." and ".$ojo,array('ano_reformulacion','aprobado','decretado'));
	$aprob=$datostexto2[0]['cfpd10_reformulacion_texto']['aprobado'];
	$decre=$datostexto2[0]['cfpd10_reformulacion_texto']['decretado'];
	if($decre==1){
  		$this->set('Message_existe', 'Disculpe, El Registro No Puede ser Modificado.');
	}
	if($decre==0){

		$validar1="cod_presi=".$varp." and cod_entidad=".$vare." and cod_tipo_inst=".$vart." and cod_inst=".$vari." and cod_dep=".$vard;
		$validar2="ano=".$varano." and cod_sector=".$varsec." and cod_programa=".$varpro." and cod_sub_prog=".$varsub." and cod_proyecto=".$varproy." and cod_activ_obra=".$varact." and  cod_partida=".$varpar." and cod_generica=".$vargen." and cod_especifica=".$varesp." and cod_sub_espec=".$varsube." and cod_auxiliar=".$varaux;
		$sql="delete from cfpd10_reformulacion_partidas_tmp where ".$del;
		$resp2=$this->cfpd10_reformulacion_partidas_tmp->execute($sql);

		if($aa1 == 1 && $aa2 == 11 && $aa3 == 30 &&$aa4 == 11){
			$sql10txt="update cfpd10_reformulacion_texto set elaborado=0,
	  		aprobado=0,
	  		numero_aprobacion=0,
	  		fecha_aprobacion='1900/01/01',
	  		fecha_registro_oficio_consejo='1900-01-01',
	  		username_registro_oficio_consejo='0',
	  		fecha_registro_decreto='1900-01-01',
	  		username_registro_decreto='0',
	  		decretado=0 where numero_oficio='".$numero_oficio."' and ".$this->SQLCA();
		}else{
			$sql10txt="update cfpd10_reformulacion_texto set elaborado=1, revisado=2,numero_oficio_consejo_legis=0,
	    	por_enviar=1,
	  		enviado=2,
	  		por_remitir=1,
	  		remitido=2,
	  		por_aprobar=1,
	  		aprobado=0,
	  		numero_aprobacion=0,
	  		fecha_aprobacion='1900/01/01',
	  		fecha_registro_oficio_consejo='1900-01-01',
	  		username_registro_oficio_consejo='0',
	  		fecha_registro_decreto='1900-01-01',
	  		username_registro_decreto='0',
	  		decretado=0 where numero_oficio='".$numero_oficio."' and ".$this->SQLCA();
		}
  		$limpiar=$this->cfpd10_reformulacion_texto->execute($sql10txt);
		if($disminuir != 0){
     		$validar1="cod_presi=".$varp." and cod_entidad=".$vare." and cod_tipo_inst=".$vart." and cod_inst=".$vari." and cod_dep=".$vard;
			$validar2="ano=".$varano." and cod_sector=".$varsec." and cod_programa=".$varpro." and cod_sub_prog=".$varsub." and cod_proyecto=".$varproy." and cod_activ_obra=".$varact." and  cod_partida=".$varpar." and cod_generica=".$vargen." and cod_especifica=".$varesp." and cod_sub_espec=".$varsube." and cod_auxiliar=".$varaux;
     		$ud05="update cfpd05 set precompromiso_congelado=precompromiso_congelado - $disminuir where ".$validar1." and ".$validar2;
     		$respxx=$this->cfpd05->execute($ud05);
     	}
		$datos = $this->cfpd10_reformulacion_partidas_tmp->findAll($this->SQLCA()." and numero_oficio='".$numero_oficio."'",null,'codi_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');
		$this->set("datos",$datos);
		$this->set("numero_oficio",$numero_oficio);
		$this->agregar_partidas();
		$this->render("agregar_partidas");
	}
}


function calcular_porc($var_monto = 0){
	$this->layout = "ajax";
	$asignacion_ajustada = $this->Session->read('asignacion_ajustada');
	$var_monto = $this->Formato1($var_monto);
	$monto = ($var_monto/$asignacion_ajustada)*100;
	$monto = $this->Formato2($monto);
		echo'<script>';
        	echo "document.getElementById('porcentaje_ajust').value = '$monto';";
		echo'</script>';
}

function validar_dependencia($var){

	$this->Session->delete('asignacion_ajustada');
	$this->layout = "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$de=$this->Session->read('SScoddep');
	if($var!='otros'){
	if($de!=1){
		$a=$this->SQLCA();
	}else{
		$a=$this->SQLCX();
	}
	$ano =  $this->ano_ejecucion();
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
	$cond_aux=$a." and ano=".$ano." and cod_sector=".$sec." and ";
    $cond_aux .="cod_programa=".$prog." and ";
    $cond_aux .="cod_sub_prog=".$subp." and ";
    $cond_aux .="cod_proyecto=".$proy." and ";
    $cond_aux .="cod_activ_obra=".$actividad." and ";
    $cond_aux .="cod_partida=".$cpar." and ";
    $cond_aux .="cod_generica=".$cgen." and ";
    $cond_aux .="cod_especifica=".$cesp." and ";
    $cond_aux .="cod_sub_espec=".$csesp." and cod_auxiliar=".$var;

	$this->set('ano',$ano);
	$this->set('sector',$sec);
	$this->set('programa',$prog);
	$this->set('sub_programa',$subp);
	$this->set('proyecto',$proy);
	$this->set('actividad',$actividad);
	$this->set('partida',$cpar);
	$this->set('generica',$cgen);
	$this->set('especifica',$cesp);
	$this->set('sub_especifica',$csesp);

	if($var!='otros'){
		$condicion_cfpd05='cod_presi='.$cod_presi.' and cod_entidad='.$cod_entidad.' and cod_tipo_inst='.$cod_tipo_inst.' and cod_inst='.$cod_inst.' and ano='.$ano.' and cod_dep='.$de.' and cod_sector='.$sec.' and cod_programa='.$prog.' and cod_sub_prog='.$subp.' and cod_proyecto='.$proy.' and cod_activ_obra='.$actividad.' and cod_partida='.$cpar.' and cod_generica='.$cgen.' and cod_especifica='.$cesp.' and cod_sub_espec='.$csesp.' and cod_auxiliar='.$var;
		$asignacion_cfpd05  = $this->cfpd05->findAll($condicion_cfpd05);
		$asignacion_anual   = $asignacion_cfpd05[0]["cfpd05"]["asignacion_anual"];
		$aumento_traslado   = $asignacion_cfpd05[0]["cfpd05"]["aumento_traslado_anual"];
		$credito_adicional  = $asignacion_cfpd05[0]["cfpd05"]["credito_adicional_anual"];
		$asignacion_ajustada= $asignacion_anual+$aumento_traslado+$credito_adicional;
	}else{
		$asignacion_ajustada=0;
	}
	$this->Session->write('asignacion_ajustada', $asignacion_ajustada);



	if($var==0){
    	$nd=$this->cfpd05->findCount($cond_aux);
    	if($nd==0){
    	if($de==1){
	$lista=  $this->cugd02_dependencia->generateList('cod_tipo_institucion='.$cod_tipo_inst.' and cod_institucion='.$cod_inst, 'cod_dependencia ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
	$this->concatena_aux($lista, 'vector2');
	}else{
		$de2=$this->Session->read('SScoddep');
		$this->set('dep2',$de2);
	}
	$this->set('no_auxiliar',true);
	$this->set('auxiliar_sind',$var);
	}else{
	}
	}else if($var!=0 && $var!='otros'){
		$nd=$this->cfpd05->findCount($cond_aux);
	if($nd==0){
		$this->set('no_auxiliar',true);
		$this->set('auxiliar_sind',$var);
	if($de==1){
		$lista=  $this->cugd02_dependencia->generateList('cod_tipo_institucion='.$cod_tipo_inst.' and cod_institucion='.$cod_inst, 'cod_dependencia ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
		$this->concatena_aux($lista, 'vector2');
	}else{
		$de3=$this->Session->read('SScoddep');
		$this->set('dep2',$de3);
	}
	}else{
	}
	}
	}else
	if($var=='otros'){
		$ano =  $this->ano_ejecucion();
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
		$vali1='cod_presi='.$cod_presi.' and cod_entidad='.$cod_entidad.' and cod_tipo_inst='.$cod_tipo_inst.' and cod_inst='.$cod_inst.' and ano='.$ano.' and cod_dep='.$de.' and cod_sector='.$sec.' and cod_programa='.$prog.' and cod_sub_prog='.$subp.' and cod_proyecto='.$proy.' and cod_activ_obra='.$actividad.' and cod_partida='.$cpar.' and cod_generica='.$cgen.' and cod_especifica='.$cesp.' and cod_sub_espec='.$csesp.' and cod_auxiliar=0';
		$vali2='cod_presi='.$cod_presi.' and cod_entidad='.$cod_entidad.' and cod_tipo_inst='.$cod_tipo_inst.' and cod_inst='.$cod_inst.' and ano='.$ano.' and cod_dep=1 and cod_sector='.$sec.' and cod_programa='.$prog.' and cod_sub_prog='.$subp.' and cod_proyecto='.$proy.' and cod_activ_obra='.$actividad.' and cod_partida='.$cpar.' and cod_generica='.$cgen.' and cod_especifica='.$cesp.' and cod_sub_espec='.$csesp.' and cod_auxiliar=0';

		//echo $vali1;

		$cero=$this->cfpd05->findCount($vali1);
		$cero2=$this->cfpd05->findCount($vali2);

	//echo $cero.'-'.$cero2;

	if($cero!=0 or $cero2!=0){
		$this->set('si_auxiliar',false);
		$this->set('errorMessage', 'A ESTE PARTIDA NO PUEDE CREARLE AUXILIARES, YA QUE LA SUBESPECIFICA TIENE MONTO');
	}else{
		$this->set('si_auxiliar',true);
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
		$this->set('ano',$ano);
		$this->set('sector',$sec);
		$this->set('programa',$prog);
		$this->set('sub_programa',$subp);
		$this->set('proyecto',$proy);
		$this->set('actividad',$actividad);
		$this->set('partida',$cpar);
		$this->set('generica',$cgen);
		$this->set('especifica',$cesp);
		$this->set('sub_especifica',$csesp);


		if($de==1){
	$lista=  $this->cugd02_dependencia->generateList('cod_tipo_institucion='.$cod_tipo_inst.' and cod_institucion='.$cod_inst, 'cod_dependencia ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
	$this->concatena_aux($lista, 'vector');
	}else{
		$de4=$this->Session->read('SScoddep');
		$this->set('dep',$de4);
		$cond_aux="cod_dep=".$de4." and cod_sector=".$sec." and ";
     		$cond_aux .="cod_programa=".$prog." and ";
     		$cond_aux .="cod_sub_prog=".$subp." and ";
     		$cond_aux .="cod_proyecto=".$proy." and ";
     		$cond_aux .="cod_activ_obra=".$actividad." and ";
     		$cond_aux .="cod_partida=".$cpar." and ";
     		$cond_aux .="cod_generica=".$cgen." and ";
     		$cond_aux .="cod_especifica=".$cesp." and ";
     		$cond_aux .="cod_sub_espec=".$csesp."";
     		//print ($cond_aux);
     		$ss=$this->cfpp05auxiliar->findAll($cond_aux,array('cod_auxiliar'),'cod_auxiliar DESC',1,1,null);
     if($ss==null){
     	$new_codigo=1;
     }else{
     	$new_codigo=$ss[0]["cfpp05auxiliar"]["cod_auxiliar"]+1;
     }

     $this->set('new_codigo',$this->zero($new_codigo));
	}


		}

	}


}


function nuevo_auxiliar($se=null,$pr=null,$sp=null,$py=null,$ac=null,$cod_dependencia=null){
	$this->layout = "ajax";
	$validacion="cod_sector=".$se." and cod_programa=".$pr." and cod_sub_prog=".$sp." and cod_proyecto=".$py." and cod_activ_obra=".$ac." and cod_dep=".$cod_dependencia;
	$ojo=$this->cfpd02_activ_obra->findCount($validacion);
	if($ojo==0){
		echo'<script>';
        	echo "document.getElementById('guardar_distri').disabled=true; ";
		echo'</script>';
		$this->set('errorMessage', 'Esta Categoria Presupuestaria no se encuentra creada para esta Dependencia');
	}else{
  		echo'<script>';
        	echo "document.getElementById('guardar_distri').disabled=false; ";
		echo'</script>';
	}
			$ano =  $this->ano_ejecucion();
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
		 	$this->set('vector',array('0'=>'00'));
         	$cond_aux="cod_dep=".$cod_dependencia." and cod_sector=".$sec." and ";
     		$cond_aux .="cod_programa=".$prog." and ";
     		$cond_aux .="cod_sub_prog=".$subp." and ";
     		$cond_aux .="cod_proyecto=".$proy." and ";
     		$cond_aux .="cod_activ_obra=".$actividad." and ";
     		$cond_aux .="cod_partida=".$cpar." and ";
     		$cond_aux .="cod_generica=".$cgen." and ";
     		$cond_aux .="cod_especifica=".$cesp." and ";
     		$cond_aux .="cod_sub_espec=".$csesp."";
     		$ss=$this->cfpp05auxiliar->findAll($cond_aux,array('cod_auxiliar'),'cod_auxiliar DESC',1,1,null);
     if($ss==null){
     	$new_codigo=1;
     }else{
     	$new_codigo=$ss[0]["cfpp05auxiliar"]["cod_auxiliar"]+1;
     }
     $this->set('new_codigo',$this->zero($new_codigo));
	 $this->set('crear_auxiliar','si');
}

function guardar_auxiliar(){
 	$this->layout = "ajax";
 	//pr($this->data);
    if(!empty($this->data['cfpp10_reformulacion_partidas'])){
    	$d1 = $this->verifica_SS(1);
		$d2 = $this->verifica_SS(2);
	 	$d3 = $this->verifica_SS(3);
	 	$d4 = $this->verifica_SS(4);
	 	$d5 = $this->data['cfpp10_reformulacion_partidas']['numero_dependencias'];
    	$d6 = $this->data['cfpp10_reformulacion_partidas']['ano_reformulacion'];
    	$d7 = $this->data['cfpp10_reformulacion_partidas']['cod_sector'];
		$d8 = $this->data['cfpp10_reformulacion_partidas']['cod_programa'];
		$d9 = $this->data['cfpp10_reformulacion_partidas']['cod_subprograma'];
		$d10 = $this->data['cfpp10_reformulacion_partidas']['cod_proyecto'];
		$d11 = $this->data['cfpp10_reformulacion_partidas']['cod_actividad'];
		$d12 = $this->data['cfpp10_reformulacion_partidas']['cod_partida'];
		$d12 = $d12 >10 ? CE.$d12 : CE."0".$d12 ;
		$d13 = $this->data['cfpp10_reformulacion_partidas']['cod_generica'];
		$d14 = $this->data['cfpp10_reformulacion_partidas']['cod_especifica'];
		$d15 = $this->data['cfpp10_reformulacion_partidas']['cod_subespecifica'];
		$d17=$this->data['cfpp10_reformulacion_partidas']['concepto_auxiliar'];
		$cod_aux=$this->data['cfpp10_reformulacion_partidas']['crear_codigo_auxiliar'];
		$tg = $this->data['cfpp10_reformulacion_partidas']['tipo_gasto'];
		$tp = $this->data['cfpp10_reformulacion_partidas']['tipo_presupuesto'];


		$cod_ramo 		= 0;
		$cod_subramo 	= 0;
		$cod_esp 		= 0;
		$cod_subesp 	= 0;
		$cod_auxr 		= 0;

////ic
/*
		$ing=$this->cfpd05->findAll("cod_presi=".$d1." and cod_entidad=".$d2." and cod_tipo_inst=".$d3." and cod_inst=".$d4." and cod_dep=".$d5." and ano=".$d6." and cod_sector=".$d7." and cod_programa=".$d8." and cod_sub_prog=".$d9." and cod_proyecto=".$d10." and cod_activ_obra=".$d11." and cod_partida=".$d12."");
		$ayu=$ing[0]['cfpd05'];
		$cod_ramo 		= $ayu['cod_ramo'];
		$cod_subramo 	= $ayu['cod_subramo'];
		$cod_esp 		= $ayu['cod_esp'];
		$cod_subesp 	= $ayu['cod_subesp'];
		$cod_auxr 		= $ayu['cod_aux'];
*/
////fc
		$sql_verificar="cod_presi=".$d1." and cod_entidad=".$d2." and cod_tipo_inst=".$d3." and cod_inst=".$d4." and cod_dep=".$d5." and ano=".$d6;
		$sql_verificar .=" and cod_sector=".$d7." and cod_programa=".$d8." and cod_sub_prog=".$d9." and cod_proyecto=".$d10." and cod_activ_obra=".$d11;
		$sql_verificar .=" and cod_partida=".$d12." and cod_generica=".$d13." and cod_especifica=".$d14." and cod_sub_espec=".$d15." and cod_auxiliar=".$cod_aux;
        $campos="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,denominacion";
		$sql = "INSERT INTO  cfpd05_auxiliar ($campos)VALUES ($d1,$d2,$d3,$d4,$d5,$d6,$d7,$d8,$d9,$d10,$d11,$d12,$d13,$d14,$d15,$cod_aux,'$d17')";
		$sql_dep_uno = "INSERT INTO  cfpd05_auxiliar ($campos)VALUES ($d1,$d2,$d3,$d4,1,$d6,$d7,$d8,$d9,$d10,$d11,$d12,$d13,$d14,$d15,$cod_aux,'$d17')";
        $this->cfpp05auxiliar->findCount($sql_verificar);
        if($this->cfpp05auxiliar->findCount($sql_verificar)==0){
            $respuesta=$this->cfpp05auxiliar->execute($sql);
            if($d5!=1)$respuestad=$this->cfpp05auxiliar->execute($sql_dep_uno);
            $this->set('Message_existe', 'Los Datos Fueron Guardados ');
            $this->data['cfpp05auxiliar']=null;
            $this->set('cod_aux', $this->zero($cod_aux));
        }else{
            $this->set('errorMessage', 'Error: El codigo de la auxiliar ya existe');
            $this->set('cod_aux', '');
              $this->data['cfpp05auxiliar']=null;
        }
    }
	if($respuesta>1){
		$respuesta2=$SQLINSERT="INSERT INTO cfpd05 (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,cod_tipo_gasto,tipo_presupuesto,asignacion_anual,aumento_traslado_anual,
disminucion_traslado_anual,
credito_adicional_anual,
rebaja_anual,
compromiso_anual,
causado_anual,
pagado_anual,
asignacion_ene,
aumento_traslado_ene,
disminucion_traslado_ene,
credito_adicional_ene,
rebaja_ene,
compromiso_ene,
causado_ene,
pagado_ene,
asignacion_feb,
aumento_traslado_feb,
disminucion_traslado_feb,
credito_adicional_feb,
rebaja_feb,
compromiso_feb,
causado_feb,
pagado_feb,
asignacion_mar,
aumento_traslado_mar,
disminucion_traslado_mar,
credito_adicional_mar,
rebaja_mar,
compromiso_mar,
causado_mar,
pagado_mar,
asignacion_abr,
aumento_traslado_abr,
disminucion_traslado_abr,
credito_adicional_abr,
rebaja_abr,
compromiso_abr,
causado_abr,
pagado_abr,
asignacion_may,
aumento_traslado_may,
disminucion_traslado_may,
credito_adicional_may,
rebaja_may,
compromiso_may,
causado_may,
pagado_may,
asignacion_jun,
aumento_traslado_jun,
disminucion_traslado_jun,
credito_adicional_jun,
rebaja_jun,
compromiso_jun,
causado_jun,
pagado_jun,
asignacion_jul,
disminucion_traslado_jul,
credito_adicional_jul,
rebaja_jul,
compromiso_jul,
causado_jul,
pagado_jul,
asignacion_ago,
aumento_traslado_ago,
disminucion_traslado_ago,
credito_adicional_ago,
rebaja_ago,
compromiso_ago,
causado_ago,
pagado_ago,
asignacion_sep,
aumento_traslado_sep,
disminucion_traslado_sep,
credito_adicional_sep,
rebaja_sep,
compromiso_sep,
causado_sep,
pagado_sep,
asignacion_oct,
aumento_traslado_oct,
disminucion_traslado_oct,
credito_adicional_oct,
rebaja_oct,
compromiso_oct,
causado_oct,
pagado_oct,
asignacion_nov,
aumento_traslado_nov,
disminucion_traslado_nov,
credito_adicional_nov,
rebaja_nov,
compromiso_nov,
causado_nov,
pagado_nov,
asignacion_dic,
aumento_traslado_dic,
disminucion_traslado_dic,
credito_adicional_dic,
rebaja_dic,
compromiso_dic,
causado_dic,
pagado_dic,
precompromiso_congelado,
precompromiso_requisicion,
precompromiso_obras,
precompromiso_fondo_avance,
cod_ramo,
cod_subramo,
cod_esp,
cod_subesp,
cod_aux) VALUES";
        $SQLINSERT .="($d1,$d2,$d3,$d4,$d5,$d6,$d7,$d8,$d9,$d10,$d11,$d12,$d13,$d14,$d15,$cod_aux,$tg,$tp,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,$cod_ramo,$cod_subramo,$cod_esp,$cod_subesp,$cod_auxr)";
		$respuesta2=$this->cfpd05->execute($SQLINSERT);

	}

		if($respuesta2 > 1){
			echo'<script>';
        		echo " fun_msj2('Los Datos Fueron Guardados Correctamente');";
			echo'</script>';
		}else{
			echo'<script>';
        		echo " fun_msj('NO SE PUDO GUARDAR LOS DATOS');";
			echo'</script>';
		}

	echo'<script>';
        echo "document.getElementById('monto_deduccion').value=''   ;";
        echo "document.getElementById('monto_aumento').value=''   ;";
        echo "document.getElementById('monto_deduccion').disabled=false; ";
        echo "document.getElementById('monto_aumento').disabled=false; ";
        echo"   document.getElementById('seleccion_6').options[1].selected = true; ";
        echo"   document.getElementById('seleccion_7').innerHTML='<select></select>';  ";
        echo"   document.getElementById('seleccion_8').innerHTML='<select></select>';  ";
        echo"   document.getElementById('seleccion_9').innerHTML='<select></select>';  ";
        echo"   document.getElementById('seleccion_10').innerHTML='<select></select>';  ";
        echo"   document.getElementById('st_seleccion_11').innerHTML='<select id=seleccion_11 class=select100>';  ";
        echo"   document.getElementById('mostrar_crear_auxiliar').innerHTML='&nbsp;';  ";
 	echo'</script>';
	//$this->set('Message_existe', 'Los Datos Fueron Guardados Correctamente');
}//FIN FUNCTION

function guardar_datos(){
 	$this->layout = "ajax";
//pr($this->data);
    if(!empty($this->data['cfpp10_reformulacion_partidas'])){

    	$d1 = $this->verifica_SS(1);
		$d2 = $this->verifica_SS(2);
	 	$d3 = $this->verifica_SS(3);
	 	$d4 = $this->verifica_SS(4);
	 	$d5 = $this->data['cfpp10_reformulacion_partidas']['numero_dependencias'];
    	$d6 = $this->data['cfpp10_reformulacion_partidas']['ano_reformulacion'];
    	$d7 = $this->data['cfpp10_reformulacion_partidas']['cod_sector'];
		$d8 = $this->data['cfpp10_reformulacion_partidas']['cod_programa'];
		$d9 = $this->data['cfpp10_reformulacion_partidas']['cod_subprograma'];
		$d10 = $this->data['cfpp10_reformulacion_partidas']['cod_proyecto'];
		$d11 = $this->data['cfpp10_reformulacion_partidas']['cod_actividad'];
		$d12 = $this->data['cfpp10_reformulacion_partidas']['cod_partida'];
		$d12 = $d12 >10 ? CE.$d12 : CE."0".$d12 ;
		$d13 = $this->data['cfpp10_reformulacion_partidas']['cod_generica'];
		$d14 = $this->data['cfpp10_reformulacion_partidas']['cod_especifica'];
		$d15 = $this->data['cfpp10_reformulacion_partidas']['cod_subespecifica'];
		$d16 = $this->data['cfpp10_reformulacion_partidas']['cod_auxiliar'];
		$tg = $this->data['cfpp10_reformulacion_partidas']['tipo_gasto'];
		$tp = $this->data['cfpp10_reformulacion_partidas']['tipo_presupuesto'];
		if($tp == 5){
			$clasificacion_recurso_extra=$this->data['cfpp10_reformulacion_partidas']['clasificacion_recurso_extra'];
		}else{
			$clasificacion_recurso_extra=0;
		}

		$cod_ramo 		= 0;
		$cod_subramo 	= 0;
		$cod_esp 		= 0;
		$cod_subesp 	= 0;
		$cod_aux 		= 0;
////ic
/*
		$ing=$this->cfpd05->findAll("cod_presi=".$d1." and cod_entidad=".$d2." and cod_tipo_inst=".$d3." and cod_inst=".$d4." and cod_dep=".$d5." and ano=".$d6." and cod_sector=".$d7." and cod_programa=".$d8." and cod_sub_prog=".$d9." and cod_proyecto=".$d10." and cod_activ_obra=".$d11." and cod_partida=".$d12."");
			$ayu=$ing[0]['cfpd05'];
			$cod_ramo 		= $ayu['cod_ramo'];
			$cod_subramo 	= $ayu['cod_subramo'];
			$cod_esp 		= $ayu['cod_esp'];
			$cod_subesp 	= $ayu['cod_subesp'];
			$cod_aux 		= $ayu['cod_aux'];
*/
////fc
	        $SQLINSERT="INSERT INTO cfpd05 (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,cod_tipo_gasto,tipo_presupuesto,asignacion_anual,aumento_traslado_anual,
disminucion_traslado_anual,
credito_adicional_anual,
rebaja_anual,
compromiso_anual,
causado_anual,
pagado_anual,
asignacion_ene,
aumento_traslado_ene,
disminucion_traslado_ene,
credito_adicional_ene,
rebaja_ene,
compromiso_ene,
causado_ene,
pagado_ene,
asignacion_feb,
aumento_traslado_feb,
disminucion_traslado_feb,
credito_adicional_feb,
rebaja_feb,
compromiso_feb,
causado_feb,
pagado_feb,
asignacion_mar,
aumento_traslado_mar,
disminucion_traslado_mar,
credito_adicional_mar,
rebaja_mar,
compromiso_mar,
causado_mar,
pagado_mar,
asignacion_abr,
aumento_traslado_abr,
disminucion_traslado_abr,
credito_adicional_abr,
rebaja_abr,
compromiso_abr,
causado_abr,
pagado_abr,
asignacion_may,
aumento_traslado_may,
disminucion_traslado_may,
credito_adicional_may,
rebaja_may,
compromiso_may,
causado_may,
pagado_may,
asignacion_jun,
aumento_traslado_jun,
disminucion_traslado_jun,
credito_adicional_jun,
rebaja_jun,
compromiso_jun,
causado_jun,
pagado_jun,
asignacion_jul,
disminucion_traslado_jul,
credito_adicional_jul,
rebaja_jul,
compromiso_jul,
causado_jul,
pagado_jul,
asignacion_ago,
aumento_traslado_ago,
disminucion_traslado_ago,
credito_adicional_ago,
rebaja_ago,
compromiso_ago,
causado_ago,
pagado_ago,
asignacion_sep,
aumento_traslado_sep,
disminucion_traslado_sep,
credito_adicional_sep,
rebaja_sep,
compromiso_sep,
causado_sep,
pagado_sep,
asignacion_oct,
aumento_traslado_oct,
disminucion_traslado_oct,
credito_adicional_oct,
rebaja_oct,
compromiso_oct,
causado_oct,
pagado_oct,
asignacion_nov,
aumento_traslado_nov,
disminucion_traslado_nov,
credito_adicional_nov,
rebaja_nov,
compromiso_nov,
causado_nov,
pagado_nov,
asignacion_dic,
aumento_traslado_dic,
disminucion_traslado_dic,
credito_adicional_dic,
rebaja_dic,
compromiso_dic,
causado_dic,
pagado_dic,
precompromiso_congelado,
precompromiso_requisicion,
precompromiso_obras,
precompromiso_fondo_avance,
cod_ramo,
cod_subramo,
cod_esp,
cod_subesp,
cod_aux,clasificacion_recurso_extra) VALUES";
        $SQLINSERT .="($d1,$d2,$d3,$d4,$d5,$d6,$d7,$d8,$d9,$d10,$d11,$d12,$d13,$d14,$d15,$d16,$tg,$tp,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,$cod_ramo,$cod_subramo,$cod_esp,$cod_subesp,$cod_aux,$clasificacion_recurso_extra)";
		$respuesta2=$this->cfpd05->execute($SQLINSERT);

		if($respuesta2 > 1){
			echo'<script>';
        		echo " fun_msj2('LOS DATOS FUERON GUARDADOS EXITOSAMENTE');";
			echo'</script>';
		}else{
			echo'<script>';
        		echo " fun_msj('NO SE PUDO GUARDAR LOS DATOS');";
			echo'</script>';
		}

	echo'<script>';
        echo " document.getElementById('monto_deduccion').value=''   ;";
        echo " document.getElementById('monto_aumento').value=''   ;";
        echo " document.getElementById('monto_deduccion').disabled=false; ";
        echo " document.getElementById('monto_aumento').disabled=false; ";
        echo " document.getElementById('seleccion_6').options[1].selected = true; ";
        echo " document.getElementById('seleccion_7').innerHTML='<select></select>';  ";
        echo " document.getElementById('seleccion_8').innerHTML='<select></select>';  ";
        echo " document.getElementById('seleccion_9').innerHTML='<select></select>';  ";
        echo " document.getElementById('seleccion_10').innerHTML='<select></select>';  ";
        echo " document.getElementById('st_seleccion_11').innerHTML='<select id=seleccion_11 class=select100>';  ";
        echo " document.getElementById('mostrar_crear_auxiliar').innerHTML='&nbsp;';  ";
	echo'</script>';
	}
}//FIN FUNCTION


function ingresos_extraordinarios ($clasificacion_recurso_extra=null,$si=null) {
	 $this->layout="ajax";
     $Cpresi=$this->verifica_SS(1);
	 $Centidad=$this->verifica_SS(2);
	 $Ctipo_inst=$this->verifica_SS(3);
	 $Cinst=$this->verifica_SS(4);

	 $this->set('INGRESOS',$this->cfpd01_ano_partida->execute("SELECT * FROM cfpd05_tipo_ingreso_extra WHERE cod_presi=$Cpresi and cod_entidad=$Centidad and cod_tipo_inst=$Ctipo_inst and cod_inst=$Cinst ORDER BY cod_tipo_ingreso ASC;"));
     $this->set('clasificacion_recurso_extra',$clasificacion_recurso_extra);
     $this->set('si',((isset($si) && $si)=='si'?'si':'no'));

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

}//fin clase cfpp09Controller