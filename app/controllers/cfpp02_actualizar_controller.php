<?php
/*
 * Created on 30/10/2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

 class Cfpp02ActualizarController extends AppController {
   var $name = 'Cfpp02_actualizar';
   var $uses = array('cugd05_restriccion_clave','cfpd05_requerimiento','cfpd05_auxiliar','cfpd05','cugd02_dependencia','cfpd07_obras_partidas','cfpd02_sector', 'cfpd02_programa', 'cfpd02_sub_prog', 'cfpd02_proyecto', 'cfpd02_activ_obra', 'cfpd01_formulacion', 'arrd05');
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
function seguridad(){
	$this->layout="ajax";
	$this->set('mensaje', 'lo siento no tiene acceso a este programa');
}

 function beforeFilter(){
 	$this->checkSession();
	/*echo'<script>' .
	'document.getElementById("valida_codigo").innerHTML = "";' .
	'document.getElementById("valida_codigo").style.display = "none";' .
	'</script>';*/
	$cod_dep = $this->Session->read('SScoddep');
	if($cod_dep != 1){
		$this->seguridad();
		$entidad_federal = $this->Session->read('entidad');
   		$this->set('entidad_federal', $entidad_federal);
		$this->set('mensaje', 'lo siento no tiene acceso a este programa');
		$this->render('seguridad');
		exit;
	}else{
		return;
	}



 }

function zero($x=null){
	if($x != null){
		if($x<10){
			$x="000".$x;
		}else if($x>=10 && $x<=99){
			$x="00".$x;
		}else if($x>=100 && $x<=999){
			$x="0".$x;
		}else if($x>=1000 && $x<=9999){
			$x=$x;
		}
	}
	return $x;

}

 function concatena($vector1=null, $nomVar=null){

	if($vector1 != null){

		foreach($vector1 as $x => $y){

			$cod[$x] = $this->zero($x).' - '.$y;
		}
		$this->set($nomVar, $cod);
	}
}

function AddCero($nomVar,$vector=object,$extra=null){
   	  if($vector != null){
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

function index(){

$this->verifica_entrada('19');

   	$this->layout="ajax";
   	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

   	$entidad_federal = $this->Session->read('entidad');
   	$this->set('entidad_federal', $entidad_federal);
	$ano = $this->cfpd01_formulacion->field('cfpd01_formulacion.ano_formular', $conditions = $condicion, $order ="ano_formular ASC");
   	$this->set('ejercicio', $ano);
	$lista=  $this->cugd02_dependencia->generateList('cod_tipo_institucion='.$cod_tipo_inst.' and cod_institucion='.$cod_inst.' and cod_dependencia!=1', 'cod_dependencia ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
	$this->concatena($lista, 'dependencia');


}

function categorias($cod_dependencia=null){
	$this->layout="ajax";
	if($cod_dependencia==null){
				$this->set('imprime',null);
				$this->set('errorMessage', 'Por favor seleccione una dependencia');
	}else{
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
	$year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
	$ano=$year[0]['cfpd01_formulacion']['ano_formular'];
   	$actividad = $this->cfpd02_activ_obra->findAll($conditions = $condicion." and ano=$ano"." and cod_dep=".$cod_dependencia, $fields = 'cod_dep, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra', $order = 'cod_sector, cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra ASC', $limit = null, $page = null, $recursive = null);
   	$this->set('actividad', $actividad);
   	if($actividad!=null){
   	$i=0;
   	foreach($actividad as $row){
		$imprime[$i] = $row['cfpd02_activ_obra']['cod_dep'];
		$i++;
   	}
   	$this->set('imprime', $imprime);
   	}
   $this->set('cod_dependencia',$cod_dependencia);
   $dependencia = $this->arrd05->findAll($conditions = $condicion." and cod_dep != 1", $fields = 'cod_dep, denominacion', $order = 'denominacion', $limit = null, $page = null, $recursive = null);
   	$this->set('dependencia', $dependencia);
	}

}

function eliminar ($cod_dep=null, $cod_sector=null, $cod_programa=null, $cod_sub_prog=null, $cod_proyecto=null, $cod_activ_obra=null){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$c         = $this->condicionNDEP().' and cod_dep='.$cod_dep.' and cod_sector='.$cod_sector.' and cod_programa='.$cod_programa.' and cod_sub_prog='.$cod_sub_prog.' and cod_proyecto='.$cod_proyecto.' and cod_activ_obra='.$cod_activ_obra;
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;


	$ano = $this->cfpd01_formulacion->field('cfpd01_formulacion.ano_formular', $conditions = $condicion, $order ="ano_formular ASC");



	if($cod_dep!=null && $cod_sector!=null && $cod_programa!=null && $cod_sub_prog!=null && $cod_proyecto!=null && $cod_activ_obra!=null){


				$cfpd05=$this->cfpd05->findCount($c." and ano='".$ano."'  " );


				if($cfpd05!=0){
				$sacar='select sum(compromiso_anual) as suma from cfpd05 where '.$condicion.' and  cod_dep='.$cod_dep.' and ano='.$ano.'  and cod_sector='.$cod_sector.' and cod_programa='.$cod_programa.' and cod_sub_prog='.$cod_sub_prog.' and cod_proyecto='.$cod_proyecto.' and cod_activ_obra='.$cod_activ_obra.' group by cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_cod_dep, ano, cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra';
				$compro=$this->cfpd02_activ_obra->execute($sacar);
				$monto=$compro[0][0]['suma'];
				$obras=$this->cfpd07_obras_partidas->findCount($c." and ano_estimacion='".$ano."'  " );
				}else{
					$monto=0;
					$obras=0;
				}


							if($monto!=0 || $obras!=0){
								$this->set('errorMessage', 'Esta categoria programatica no puede ser eliminada');
						    	$this->set('validado',true);
						    	$this->index();
							    $this->render("index");
							}else{
								$req='delete from cfpd05_requerimiento where '.$c.' and ano='.$ano;
								$c05='delete from cfpd05 where '.$c.' and ano='.$ano;
								$cau='delete from cfpd05_auxiliar where '.$c.' and ano='.$ano;
								$ccp='delete from cfpd02_activ_obra where '.$c.' and ano='.$ano;
								$this->cfpd05_requerimiento->execute($req);
								$this->cfpd05->execute($c05);
								$this->cfpd05_auxiliar->execute($cau);
								$this->cfpd02_activ_obra->execute($ccp);
								$this->set('Message_existe', 'La categoria programatica fue eliminada');
								//$this->set('validado',true);
								$this->categorias($cod_dep);
							    $this->render("categorias");
							}



	}//fin if


}// fin function


function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cfpp02_revision']['login']) && isset($this->data['cfpp02_revision']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cfpp02_revision']['login']);
		$paswd=addslashes($this->data['cfpp02_revision']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=19 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->set('validado',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('validado',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('errorMessage',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('validado',false);
			$this->index("autor_valido");
			$this->render("index");
		}
	}
}


}//fin de la clase cfpp02_actualizar
?>
