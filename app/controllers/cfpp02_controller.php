<?php
/*
 * Fecha: 19/07/2007
 *
 * Por Luis Alfredo Diaz Jaramillo
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */
class Cfpp02Controller extends AppController {
   var $name = 'Cfpp02';
   var $uses = array('cugd02_dependencia','cfpd05','cfpd09_metas_proyecto','cfpd09_metas_actividad','cfpd09_metas_subprog','cfpd09_metas_programa','cfpd09_metas_sector','cfpd02_sector', 'cfpd02_programa', 'cfpd02_sub_prog', 'cfpd02_proyecto', 'cfpd02_activ_obra', 'cfpd01_formulacion', 'arrd05','cprogramatica');
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
	/*echo'<script>' .
							'document.getElementById("valida_codigo").innerHTML = "";' .
							'document.getElementById("valida_codigo").style.display = "none";' .
							'</script>';*/
	$cod_dep = $this->Session->read('SScoddep');



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
/*
 function concatena($vector1=null, $nomVar=null){

	if($vector1 != null){

		foreach($vector1 as $x => $y){

			$cod[$x] = $this->zero($x).' - '.$y;
		}
		$this->set($nomVar, $cod);
	}
}*/

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


$this->verifica_entrada('107');

    $this->layout = "ajax";
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
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$this->set('dependencia', $this->Session->read('SScoddep'));

 }

function categoria($ejercicio=null) {
	$this->layout = "ajax";
	$this->set('entidadFederal', $this->Session->read('entidad_federal'));
	if($ejercicio==null){
		$ejercicio="";
		$this->set('ejercicio', $this->data['cfpp02']['ano']);
		$ejercicio = $this->data['cfpp02']['ano'];

	}else{
		$this->set('ejercicio', $ejercicio);
	}

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep." and ano = ".$ejercicio;
   	$listaSector = $this->cfpd02_sector->generateList($condicion, 'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.denominacion');
   	//pr($listaSector);
   	$this->concatena($listaSector,'sector');
   	$this->set('cod_presi', $this->Session->read('SScodpresi'));
	}

function selec_sector($ejercicio=null, $var=null){

	$this->layout = "ajax";
	$this->set('ejercicio', $ejercicio);
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep." and ano = ".$ejercicio;

   if($this->data['cfpp02']['codigo']){
   	$var = $this->data['cfpp02']['codigo'];
   	$this->set('opcion', $var);

   }else{
   	$this->set('opcion', $var);
   }


   $listaSector = $this->cfpd02_sector->generateList($condicion, 'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.cod_sector');

	$this->AddCero('sector',$listaSector);


}

function selec_programa($ejercicio=null, $var=null, $aux=null){
	$this->layout = "ajax";
	$this->set('ejercicio', $ejercicio);
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst."and cod_dep = ".$cod_dep." and ano = ".$ejercicio;

if($this->data['cfpp02']['codigo'] &&  $var!=null){
	$this->set('selecion', $this->data['cfpp02']['codigo']);
}
if($var==null){
	$var = $this->data['cfpp02']['codigo'];
}
if($aux!=null){
	$this->set('selecion', $aux);
}


$this->set('opcion1', $var);

if($var!=null && $var!='otros'){

	$listaPrograma = $this->cfpd02_programa->generateList($condicion.' and cod_sector =  '.$var.' ', ' cod_programa ASC', null, '{n}.cfpd02_programa.cod_programa', '{n}.cfpd02_programa.denominacion');
	$this->concatena($listaPrograma,'programa');

	}else{
		$this->set('programa', '');
	}

}

function selec_sub_prog($ejercicio=null, $var1=null, $var2=null , $aux=null){
    $this->layout = "ajax";
    $this->set('ejercicio', $ejercicio);
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst."and cod_dep = ".$cod_dep." and ano = ".$ejercicio;



	if($this->data['cfpp02']['codigo']  &&  $var2!=null){
		$this->set('selecion', $this->data['cfpp02']['codigo']);
	}
    if($var2==null){
		$var2 = $this->data['cfpp02']['codigo'];
	}
	if($aux!=null){
		$this->set('selecion', $aux);
	}

	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);

	if($var2!=null && $var2!='otros'){

	$ListaSub_prog = $this->cfpd02_sub_prog->generateList($condicion.' and cod_sector =  '.$var1.'  and cod_programa = '.$var2.'', ' cod_sub_prog ASC', null, '{n}.cfpd02_sub_prog.cod_sub_prog', '{n}.cfpd02_sub_prog.denominacion');
	//$this->AddCero('sub_prog',$ListaSub_prog);
	$this->concatena($ListaSub_prog,'sub_prog');
	$ano = $this->cfpd02_programa->execute("SELECT ano from cfpd02_programa where cod_sector =  ".$var1."  and cod_programa = ".$var2);
	$this->set('year', $ano);

	}else{
		$this->set('sub_prog', '');
	}

}

function selec_proyecto($ejercicio=null, $var1=null, $var2=null, $var3=null , $aux=null){
	$this->layout = "ajax";
	$this->set('ejercicio', $ejercicio);
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst."and cod_dep = ".$cod_dep." and ano = ".$ejercicio;

if($this->data['cfpp02']['codigo']  &&  $var3!=null){
	$this->set('selecion', $this->data['cfpp02']['codigo']);
}
if($var3==null){
	$var3 = $this->data['cfpp02']['codigo'];
}
if($aux!=null){
	$this->set('selecion', $aux);
}


	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);

	if($var3!=null && $var3!='otros'){

    $ListaProyecto = $this->cfpd02_proyecto->generateList($condicion.' and cod_sector =  '.$var1.'  and cod_programa = '.$var2.' and cod_sub_prog = '.$var3.'', ' cod_proyecto ASC', null, '{n}.cfpd02_proyecto.cod_proyecto', '{n}.cfpd02_proyecto.denominacion');
    $this->concatena($ListaProyecto,'proyecto');
    //$this->AddCero('proyecto',$ListaProyecto);

	}else{   $this->set('proyecto', ''); }

}

function selec_activ_obra($ejercicio=null, $var1=null, $var2=null, $var3=null, $var4=null, $aux=null) {
	$this->layout = "ajax";
	$this->set('ejercicio', $ejercicio);
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst."and cod_dep = ".$cod_dep." and ano = ".$ejercicio;

	if($this->data['cfpp02']['codigo']  &&  $var4!=null) $this->set('selecion', $this->data['cfpp02']['codigo']);
	if($var4==null) $var4 = $this->data['cfpp02']['codigo'];
	if($aux!=null)  $this->set('selecion', $aux);


	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);

	if($var4!=null && $var4!='otros'){

		$ListActiv_obra = $this->cfpd02_activ_obra->generateList($condicion.' and cod_sector =  '.$var1.'  and cod_programa = '.$var2.' and cod_sub_prog = '.$var3.' and cod_proyecto = '.$var4.'', ' cod_activ_obra ASC', null, '{n}.cfpd02_activ_obra.cod_activ_obra', '{n}.cfpd02_activ_obra.denominacion');
		$this->concatena($ListActiv_obra,'activ_obra');
		//$this->AddCero('activ_obra',$ListActiv_obra);

	}else{   $this->set('activ_obra', ''); }
}

function principal($ejercicio=null, $var1=null, $var2=null, $var3=null, $var4=null, $var5=null){

   	$this->layout = "ajax";

   	$this->set('ejercicio', $ejercicio);
   	//echo $ejercicio;
   	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst."and cod_dep = ".$cod_dep." and ano = ".$ejercicio;

	if(!empty($this->data['arrp01']['cod_dep'])){
   		$dependencia = $this->data['arrp01']['cod_dep'];
		$cond1 = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$dependencia." and ano=".$ejercicio." and cod_sector= ".$var1." and cod_programa=".$var2." and cod_sub_prog=".$var3." and cod_proyecto=".$var4." and cod_activ_obra=".$var5;
		$cond2 = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$dependencia;
		$cond_sector = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$dependencia." and cod_sector= ".$var1." and ano=".$ejercicio;
		$cond_sector1 = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep." and cod_sector= ".$var1;
		$cond_programa = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$dependencia." and cod_sector= ".$var1." and cod_programa=".$var2." and ano=".$ejercicio;
		$cond_programa1 = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep." and cod_sector= ".$var1." and cod_programa=".$var2;
		$cond_sub_programa = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$dependencia." and cod_sector= ".$var1." and cod_programa=".$var2." and cod_sub_prog=".$var3." and ano=".$ejercicio;
		$cond_sub_programa1 = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep." and cod_sector= ".$var1." and cod_programa=".$var2." and cod_sub_prog=".$var3;
		$cond_proyecto = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$dependencia." and cod_sector= ".$var1." and cod_programa=".$var2." and cod_sub_prog=".$var3." and cod_proyecto=".$var4." and ano=".$ejercicio;
		$cond_proyecto1 = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep." and cod_sector= ".$var1." and cod_programa=".$var2." and cod_sub_prog=".$var3." and cod_proyecto=".$var4;
		$cond_act1 = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep." and cod_sector= ".$var1." and cod_programa=".$var2." and cod_sub_prog=".$var3." and cod_proyecto=".$var4." and cod_activ_obra=".$var5;
		$sector = $this->cfpd02_sector->findAll($cond_sector1,null, 'cod_sector ASC', null, null);
		foreach($sector as $sector){
			$denomin_sector = $sector['cfpd02_sector']['denominacion'];
			$unidad_sector = $sector['cfpd02_sector']['unidad_ejecutora'];
			$objetivo_sector = $sector['cfpd02_sector']['objetivo'];
			$funcionario_sector = $sector['cfpd02_sector']['funcionario_responsable'];

		}

		$programa = $this->cfpd02_programa->findAll($cond_programa1, null, 'cod_programa ASC', null, null);
		foreach($programa as $programa){
			$denomin_programa = $programa['cfpd02_programa']['denominacion'];
			$unidad_programa = $programa['cfpd02_programa']['unidad_ejecutora'];
			$objetivo_programa = $programa['cfpd02_programa']['objetivo'];
			$funcionario_programa = $programa['cfpd02_programa']['funcionario_responsable'];
		}

		$sub_prog = $this->cfpd02_sub_prog->findAll($cond_sub_programa1, null, 'cod_sub_prog ASC', null, null);
		foreach($sub_prog as $sub_prog){
			$denomin_sub_prog = $sub_prog['cfpd02_sub_prog']['denominacion'];
			$unidad_sub_prog = $sub_prog['cfpd02_sub_prog']['unidad_ejecutora'];
			$objetivo_sub_prog = $sub_prog['cfpd02_sub_prog']['objetivo'];
			$funcionario_sub_prog = $sub_prog['cfpd02_sub_prog']['funcionario_responsable'];
		}

		$proyecto = $this->cfpd02_proyecto->findAll($cond_proyecto1, null, 'cod_proyecto ASC', null, null);
		foreach($proyecto as $proyecto){
			$denomin_proyecto = $proyecto['cfpd02_proyecto']['denominacion'];
			$unidad_proyecto = $proyecto['cfpd02_proyecto']['unidad_ejecutora'];
			$objetivo_proyecto = $proyecto['cfpd02_proyecto']['objetivo'];
			$funcionario_proyecto = $proyecto['cfpd02_proyecto']['funcionario_responsable'];
		}

		$actividad = $this->cfpd02_activ_obra->findAll($cond_act1, null, 'cod_activ_obra ASC', null, null);
		foreach($actividad as $actividad){
			$denomin_actividad = $actividad['cfpd02_activ_obra']['denominacion'];
			$unidad_activ = $actividad['cfpd02_activ_obra']['unidad_ejecutora'];
			$objetivo_activ = $actividad['cfpd02_activ_obra']['objetivo'];
			$funcionario_activ = $actividad['cfpd02_activ_obra']['funcionario_responsable'];
			$titulo_activ = $actividad['cfpd02_activ_obra']['titulo'];
		}

		//echo $cond1;
		if($this->cfpd02_activ_obra->findCount($cond1) == 0){
		//	echo 'si llego';
			//echo $ejercicio;
			$cod_sector = $var1;
			$cod_programa = $var2;
			$cod_sub_prog = $var3;
			$cod_proyecto = $var4;
			$cod_activ_obra = $var5;
			//echo "el cod_sector es: ".$cod_sector." - el cod_programa es: ".$cod_programa." - el cod_sub_prog es: ".$cod_sub_prog." - el cod_proyecto es: ".$cod_proyecto." - el cod_activ es: ".$cod_activ_obra;
			$sql_sector = "INSERT INTO cfpd02_sector VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$dependencia', '$ejercicio', '$cod_sector', '$denomin_sector', '$unidad_sector', '$objetivo_sector', '$funcionario_sector')";
			$sql_programa = "INSERT INTO cfpd02_programa VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$dependencia', '$ejercicio', '$cod_sector', '$cod_programa', '$denomin_programa', '$unidad_programa', '$objetivo_programa', '$funcionario_programa')";
			$sql_sub_programa = "INSERT INTO cfpd02_sub_prog VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$dependencia', '$ejercicio', '$cod_sector', '$cod_programa', '$cod_sub_prog', '$denomin_sub_prog', '$unidad_sub_prog', '$objetivo_sub_prog', '$funcionario_sub_prog')";
			$sql_proyecto = "INSERT INTO cfpd02_proyecto VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$dependencia', '$ejercicio', '$cod_sector', '$cod_programa', '$cod_sub_prog', '$cod_proyecto', '$denomin_proyecto', '$unidad_proyecto', '$objetivo_proyecto', '$funcionario_proyecto')";
			//echo $sql_proyecto;
			$sql_actividad = "INSERT INTO cfpd02_activ_obra VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$dependencia', '$ejercicio', '$cod_sector', '$cod_programa', '$cod_sub_prog', '$cod_proyecto', '$cod_activ_obra', '$denomin_actividad', '$unidad_activ', '$objetivo_activ', '$titulo_activ', '$funcionario_activ')";

			if($this->cfpd02_sector->findCount($cond_sector) == 0){
//				echo 's';
				$this->cfpd02_sector->execute($sql_sector);
			}

			if($this->cfpd02_programa->findCount($cond_programa) == 0){
	//			echo 'p';
				$this->cfpd02_programa->execute($sql_programa);
			}

			if($this->cfpd02_sub_prog->findCount($cond_sub_programa) == 0){
		//		echo 'sp';
				$this->cfpd02_sub_prog->execute($sql_sub_programa);
			}

			if($this->cfpd02_proyecto->findCount($cond_proyecto) == 0){
			//	echo 'p';
				$this->cfpd02_proyecto->execute($sql_proyecto);

			}
			if($this->cfpd02_activ_obra->findCount($cond1) == 0){
				//echo 'ac';
				//echo $sql_actividad;
				$this->cfpd02_activ_obra->execute($sql_actividad);
				$this->set('traslado', "LA OPERACIÓN FUE REALIZADA EXITOSAMENTE");
			}


			$this->set('traslado', "LA OPERACIÓN FUE REALIZADA EXITOSAMENTE");
		}else{
			$this->set('existe', "LO SIENTO YA EXISTE UNA ACTIVIDAD U OBRA CREADA EN ESA ENTIDAD");
		}

   	}
	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
	$this->set('opcion5', $var5);

	$action='';
	$tabla = '';
	$sql_3 = '';
	if($var1=='otros') $action=$var1;
	if($var2=='otros') $action=$var2;
	if($var3=='otros') $action=$var3;
	if($var4=='otros') $action=$var4;
	if($var5=='otros') $action=$var5;


if($var1!=null){
		$sql_2 =  $condicion.' and cod_sector =  '.$var1.'  ';
		$tabla='cfpd02_sector';
	}
if($var2!=null){
		$sql_2 .= 'and cod_programa = '.$var2.'  ';
		$tabla='cfpd02_programa';
		$sql_3 =  $condicion.' and cod_sector =  '.$var1.'  ';

	}

if($var3!=null){
		if($var3 == 0 && $var3 != 'otros'){
			$this->set('opcion3', 'cero');
			$sql_2 .= 'and cod_sub_prog = '.$var3.'  ';
			$tabla='cfpd02_sub_prog';
			$sql_3 .= 'and cod_programa = '.$var2.'  ';

		}else{
			$sql_2 .= 'and cod_sub_prog = '.$var3.'  ';
			$tabla='cfpd02_sub_prog';
			$sql_3 .= 'and cod_programa = '.$var2.'  ';

		}
	}

if($var4!=null){
	if($var4 == 0 && $var4 != 'otros'){
		$this->set('opcion4', 'cero');
	}
		$sql_2 .= 'and cod_proyecto = '.$var4.'  ';
		$tabla='cfpd02_proyecto';
		$sql_3 .= 'and cod_sub_prog = '.$var3.'  ';
		$this->set('opcion1', $var1);

	}

	if($var5!=null){
		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$condicion = "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst;

		if($var3 == 0) $this->set('opcion3', 'cero');
		if($var4 == 0) $this->set('opcion4', 'cero');
		$sql_2.= 'and cod_activ_obra = '.$var5.'  ';
		$tabla='cfpd02_activ_obra';
		$sql_3 .= 'and cod_proyecto = '.$var4.'  ';
		$nom = $this->cugd02_dependencia->generateList($condicion, 'cod_dependencia ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
   		$this->concatena($nom, 'arr05');



	}

	$this->set('tabla', $tabla);

if($var1!=null && $action!='otros'){

       $sql_re = $sql_2;
	  $data = $this->$tabla->findAll($sql_re, null, null, null);

	  $this->set('datos_cod_cfpp02', $data);

}else if($var1!=null){

	  $sql_re = $sql_3;
	  $data = $this->$tabla->findAll($sql_re, null, null, null);

	  $this->set('datos_cod_cfpp02', $data);

}//fin else






 }//FIN FUNCTION

 function guardar($ejercicio=null, $tabla=null, $var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null){

 	$this->layout = "ajax";
 	$this->set('ejercicio', $ejercicio);
	$tabla = '';
	$sql_2 = '';
	$sql_3 = '';
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep." and ano = ".$ejercicio;

    if($var1==null){
    	$this->data['cfpp02']['cod_sector'] = $this->data['cfpp02']['codigo'];
    	$var1 = $this->data['cfpp02']['codigo'];
	}else if($var2==null){
		$this->data['cfpp02']['cod_programa'] =$this->data['cfpp02']['codigo'];
		$var2 = $this->data['cfpp02']['codigo'];
	} else if($var3==null){
		$this->data['cfpp02']['cod_sub_prog'] = $this->data['cfpp02']['codigo'];
		$var3 = $this->data['cfpp02']['codigo'];
	}else if($var4==null){
		$this->data['cfpp02']['cod_proyecto'] = $this-> data['cfpp02']['codigo'];
		$var4 = $this->data['cfpp02']['codigo'];
	}else if($var5==null){
		$this->data['cfpp02']['cod_activ_obra'] = $this->data['cfpp02']['codigo'];
		$var5 = $this->data['cfpp02']['codigo'];
	}else if($var6==null){
		$this->data['cfpp02']['cod_auxiliar'] = $this->data['cfpp02']['codigo'];
		$var6 = $this->data['cfpp02']['codigo'];
	}



	$denominacion = $this->data['cfpp02']['denominacion'];
	$ano = $ejercicio;
	 $unidad_ejecutora = $this->data['cfpp02']['unidad_ejecutora'];
	 $denominacion = $this->data['cfpp02']['denominacion'];
	 $unidad_ejecutora = $this->data['cfpp02']['unidad_ejecutora'];
	 $objetivo = $this->data['cfpp02']['objetivo'];
	 $titulo = $this->data['cfpp02']['titulo'];
	 $funcionario_responsable = $this->data['cfpp02']['funcionario_responsable'];


    $codigos = "";
	$values = "";



	if($var1!=null){
		        $codigos .= "cod_sector, ";
				$values .=  " '".$var1."',  " ;
				$tabla='cfpd02_sector';
	}

	if($var2!=null){
		$codigos .= "cod_programa, ";
		$values .=  " '".$var2."',  ";
		$tabla='cfpd02_programa';
	}

	if($var3!=null){
		$codigos .= "cod_sub_prog, ";
		$values .=  " '".$var3."',  ";
		$tabla='cfpd02_sub_prog';
	}
	if($var4!=null){
		$codigos .= "cod_proyecto, ";
		$values .=  " '".$var4."',  ";
		$tabla='cfpd02_proyecto';
	}
	if($var5!=null){
		$codigos .= "cod_activ_obra, ";
		$values .=  " '".$var5."',  ";
		$tabla='cfpd02_activ_obra';
	}

	if($var5!=null){
		$sql_1 = "INSERT INTO  ".$tabla." VALUES  ('$cod_presi','$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano' ,".$values."   '$denominacion', '$unidad_ejecutora',  '$objetivo', '$titulo', '$funcionario_responsable');   ";
	}else{
		$sql_1 = "INSERT INTO  ".$tabla." VALUES  ('$cod_presi','$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano' ,".$values."   '$denominacion', '$unidad_ejecutora',  '$objetivo', '$funcionario_responsable');  ";
	}

	$sql_prog1 = "INSERT INTO cfpd02_sub_prog VALUES  ('$cod_presi','$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano' , '$var1', '$var2', '0', 'N/A', 'N/A',  'N/A', 'N/A'); ";
	$sql_prog2 = "INSERT INTO cfpd02_proyecto VALUES  ('$cod_presi','$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano' , '$var1', '$var2', '0', '0', 'N/A', 'N/A',  'N/A', 'N/A'); ";

	$sql_sub_prog1 = "INSERT INTO cfpd02_proyecto VALUES  ('$cod_presi','$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano' , '$var1', '$var2', '$var3', '0', 'N/A', 'N/A',  'N/A', 'N/A');";


	$sql = $sql_1;

	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
	$this->set('opcion5', $var5);


	if($var1!=null){
		$sql_2 =  $condicion.' and cod_sector =  '.$var1.'  ';
		$tabla='cfpd02_sector';
	}
	if($var2!=null){
		$sql_2 .= 'and cod_programa = '.$var2.'  ';
		$tabla='cfpd02_programa';
	}
	if($var3!=null){
		$sql_2 .= 'and cod_sub_prog = '.$var3.'  ';
		$tabla='cfpd02_sub_prog';
	}
	if($var4!=null){
		$sql_2 .= 'and cod_proyecto = '.$var4.'  ';
		$tabla='cfpd02_proyecto';
	}
	if($var5!=null){
		$sql_2.= 'and cod_activ_obra = '.$var5.'  ';
		$tabla='cfpd02_activ_obra';
	}



if($tabla!=''){

  if ($this->$tabla->validates($this->data['cfpp02'])){

	  if($this->$tabla->findCount($sql_2) == 0){

	 	$this->$tabla->execute($sql);
	 	if($tabla == 'cfpd02_programa'){
	 		$this->cfpd02_sub_prog->execute($sql_prog1);
			$this->cfpd02_proyecto->execute($sql_prog2);
	 	}
		if($tabla == 'cfpd02_sub_prog'){
	 		$this->cfpd02_sub_prog->execute($sql_sub_prog1);
	 	}

			$this->set('errorMessage', 'LOS DATOS FUERON GUARDADOS CORRECTAMENTE');

	   }else{ $this->set('Message_existe', 'ESTE REGISTRO NO FUE ALMACENADO PORQUE YA EXISTE');}

   }else{}


    $datos = $this->$tabla->findAll($sql_2, null, null, null);

	  $this->set('datos_cod_cfpp02', $datos);

	  $this->set('tabla', $tabla);

	  if($var5!=null){
		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$condicion = "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst;

		if($var3 == 0) $this->set('opcion3', 'cero');
		if($var4 == 0) $this->set('opcion4', 'cero');
		$sql_2.= 'and cod_activ_obra = '.$var5.'  ';
		$tabla='cfpd02_activ_obra';
		$sql_3 .= 'and cod_proyecto = '.$var4.'  ';
		//$nom = $this->arrd05->generateList($condicion, 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
   		//$this->concatena($nom, 'arr05');

   		$nom = $this->cugd02_dependencia->generateList($condicion, 'cod_dependencia ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
   		$this->concatena($nom, 'arr05');



	}




 }//fin if tabla



 }//FIN FUNCTION

function editar($ejercicio=null, $var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null){

 	$this->layout = "ajax";
	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
	$this->set('opcion5', $var5);
    $this->set('opcion6', $var6);
    $this->set('ejercicio', $ejercicio);

    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep." and ano = ".$ejercicio;

	$action='';
	$tabla = '';
	$sql_2 = '';



	if($var1!=null){
		$sql_2 =  $condicion.' and cod_sector =  '.$var1.'  ';
		$tabla='cfpd02_sector';
	}
	if($var2!=null){
		$sql_2 .= 'and cod_programa = '.$var2.'  ';
		$tabla='cfpd02_programa';
	}
	if($var3!=null){
		if($var3 == 0) $this->set('opcion3', 'cero');
		$sql_2 .= 'and cod_sub_prog = '.$var3.'  ';
		$tabla='cfpd02_sub_prog';
	}
	if($var4!=null){
		if($var4 == 0) $this->set('opcion4', 'cero');
		$sql_2 .= 'and cod_proyecto = '.$var4.'  ';
		$tabla='cfpd02_proyecto';
	}
	if($var5!=null){
		if($var3 == 0) $this->set('opcion3', 'cero');
		if($var4 == 0) $this->set('opcion4', 'cero');
		$sql_2.= 'and cod_activ_obra = '.$var5.'  ';
		$tabla='cfpd02_activ_obra';
	}


	$this->set('tabla', $tabla);

	  $data = $this->$tabla->findAll($sql_2, null, null, null);
	  $this->set('datos_cod_cfpp02', $data);




 }














 function editar2($ejercicio=null, $pagina=null, $var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null){

 	$this->layout = "ajax";
	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
	$this->set('opcion5', $var5);
    $this->set('opcion6', $var6);
    $this->set('ejercicio', $ejercicio);

    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep." and ano = ".$ejercicio;

	$action='';
	$tabla = '';
	$sql_2 = '';



	if($var1!=null){
		$sql_2 =  $condicion.' and cod_sector =  '.$var1.'  ';
		$tabla='cfpd02_sector';
	}
	if($var2!=null){
		$sql_2 .= 'and cod_programa = '.$var2.'  ';
		$tabla='cfpd02_programa';
	}
	if($var3!=null){
		if($var3 == 0) $this->set('opcion3', 'cero');
		$sql_2 .= 'and cod_sub_prog = '.$var3.'  ';
		$tabla='cfpd02_sub_prog';
	}
	if($var4!=null){
		if($var4 == 0) $this->set('opcion4', 'cero');
		$sql_2 .= 'and cod_proyecto = '.$var4.'  ';
		$tabla='cfpd02_proyecto';
	}
	if($var5!=null){
		if($var3 == 0) $this->set('opcion3', 'cero');
		if($var4 == 0) $this->set('opcion4', 'cero');
		$sql_2.= 'and cod_activ_obra = '.$var5.'  ';
		$tabla='cfpd02_activ_obra';
	}


	$this->set('tabla', $tabla);

	  $data = $this->$tabla->findAll($sql_2, null, null, null);
	  $this->set('datos_cod_cfpp02', $data);

	  $this->set('pagina', $pagina);




 }










function  guardar_editar($ejercicio=null, $tabla=null, $var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null, $aux=null){

 	$this->layout = "ajax";
 	$this->set('ejercicio', $ejercicio);


	 $ano = $this->data['cfpp02']['ano'];
	 $denominacion = $this->data['cfpp02']['denominacion'];
	 $unidad_ejecutora = $this->data['cfpp02']['unidad_ejecutora'];
	 $objetivo = $this->data['cfpp02']['objetivo'];
	 if($var5!=null){
	 $titulo = $this->data['cfpp02']['titulo'];
	 }
	 $funcionario_responsable = $this->data['cfpp02']['funcionario_responsable'];

	 $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep." and ano = ".$ejercicio;
	$condicion2 = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and ano = ".$ejercicio;


	if($var5!=null){
		$sql_1 = 'UPDATE '.$tabla.'  SET  titulo = \''.$titulo.'\', ano = \''.$ano.'\', denominacion = \''.$denominacion.'\', unidad_ejecutora = \''.$unidad_ejecutora.'\', objetivo = \''.$objetivo.'\', funcionario_responsable = \''.$funcionario_responsable.'\' WHERE '.$condicion2;

	}else{
		$sql_1 = 'UPDATE '.$tabla.'  SET  ano = \''.$ano.'\', denominacion = \''.$denominacion.'\', unidad_ejecutora = \''.$unidad_ejecutora.'\', objetivo = \''.$objetivo.'\', funcionario_responsable = \''.$funcionario_responsable.'\' WHERE '.$condicion2;
	}
	if($var1!=null){
		$sql_2 =  ' and cod_sector =  '.$var1.'  ';
        $tabla='cfpd02_sector';
	}
	if($var2!=null){
		$sql_2 .= 'and cod_programa = '.$var2.'  ';
		$tabla='cfpd02_programa';
	}
	if($var3!=null){
		if($var3 == 0) $this->set('opcion3', 'cero');
		$sql_2 .= 'and cod_sub_prog = '.$var3.'  ';
		$tabla='cfpd02_sub_prog';
	}
	if($var4!=null){
	if($var4 == 0) $this->set('opcion4', 'cero');
		$sql_2 .= 'and cod_proyecto = '.$var4.'  ';
		$tabla='cfpd02_proyecto';
	}
	if($var5!=null){
		if($var3 == 0) $this->set('opcion3', 'cero');
		if($var4 == 0) $this->set('opcion4', 'cero');
		$sql_2.= 'and cod_activ_obra = '.$var5.'  ';
		$tabla='cfpd02_activ_obra';
	}


	$sql = $sql_1.$sql_2;

    $this->$tabla->execute($sql);

	$this->set('errorMessage', 'Los Datos Fueron Modificados');


	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
	$this->set('opcion5', $var5);
    $this->set('opcion6', $var6);


	$tabla = '';
	$sql_2 = '';



	if($var1!=null){
		$sql_2 =  ' cod_sector =  '.$var1.'  ';
		$tabla='cfpd02_sector';
	}
	if($var2!=null){
		$sql_2 .= 'and cod_programa = '.$var2.'  ';
		$tabla='cfpd02_programa';
	}
	if($var3!=null){
		$sql_2 .= 'and cod_sub_prog = '.$var3.'  ';
		$tabla='cfpd02_sub_prog';
	}
	if($var4!=null){
		$sql_2 .= 'and cod_proyecto = '.$var4.'  ';
		$tabla='cfpd02_proyecto';
	}
	if($var5!=null){
		$sql_2.= 'and cod_activ_obra = '.$var5.'  ';
		$tabla='cfpd02_activ_obra';
	}

	  $data = $this->$tabla->findAll($sql_2." and ".$condicion, null, null, null);

	  $this->set('datos_cod_cfpp02', $data);

	  $this->set('tabla', $tabla);

	 if($var5!=null){
		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$condicion = "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst;

		if($var3 == 0) $this->set('opcion3', 'cero');
		if($var4 == 0) $this->set('opcion4', 'cero');
		$sql_2.= 'and cod_activ_obra = '.$var5.'  ';
		$tabla='cfpd02_activ_obra';

		$nom = $this->cugd02_dependencia->generateList($condicion, 'cod_dependencia ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
   		$this->concatena($nom, 'arr05');



	}

}//FIN FUNCTION

















function  guardar_editar2($ejercicio=null, $pagina=null, $tabla=null, $var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null, $aux=null){

 	$this->layout = "ajax";
 	$this->set('ejercicio', $ejercicio);


	 $ano = $this->data['cfpp02']['ano'];
	 $denominacion = $this->data['cfpp02']['denominacion'];
	 $unidad_ejecutora = $this->data['cfpp02']['unidad_ejecutora'];
	 $objetivo = $this->data['cfpp02']['objetivo'];
	 if($var5!=null){
	 $titulo = $this->data['cfpp02']['titulo'];
	 }
	 $funcionario_responsable = $this->data['cfpp02']['funcionario_responsable'];

	 $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep." and ano = ".$ejercicio;
	$condicion2 = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and ano = ".$ejercicio;


	if($var5!=null){
		$sql_1 = 'UPDATE '.$tabla.'  SET  titulo = \''.$titulo.'\', ano = \''.$ano.'\', denominacion = \''.$denominacion.'\', unidad_ejecutora = \''.$unidad_ejecutora.'\', objetivo = \''.$objetivo.'\', funcionario_responsable = \''.$funcionario_responsable.'\' WHERE '.$condicion2;

	}else{
		$sql_1 = 'UPDATE '.$tabla.'  SET  ano = \''.$ano.'\', denominacion = \''.$denominacion.'\', unidad_ejecutora = \''.$unidad_ejecutora.'\', objetivo = \''.$objetivo.'\', funcionario_responsable = \''.$funcionario_responsable.'\' WHERE '.$condicion2;
	}
	if($var1!=null){
		$sql_2 =  ' and cod_sector =  '.$var1.'  ';
        $tabla='cfpd02_sector';
	}
	if($var2!=null){
		$sql_2 .= 'and cod_programa = '.$var2.'  ';
		$tabla='cfpd02_programa';
	}
	if($var3!=null){
		if($var3 == 0) $this->set('opcion3', 'cero');
		$sql_2 .= 'and cod_sub_prog = '.$var3.'  ';
		$tabla='cfpd02_sub_prog';
	}
	if($var4!=null){
	if($var4 == 0) $this->set('opcion4', 'cero');
		$sql_2 .= 'and cod_proyecto = '.$var4.'  ';
		$tabla='cfpd02_proyecto';
	}
	if($var5!=null){
		if($var3 == 0) $this->set('opcion3', 'cero');
		if($var4 == 0) $this->set('opcion4', 'cero');
		$sql_2.= 'and cod_activ_obra = '.$var5.'  ';
		$tabla='cfpd02_activ_obra';
	}


	$sql = $sql_1.$sql_2;

    $this->$tabla->execute($sql);

	$this->set('errorMessage', 'Los Datos Fueron Modificados');


	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
	$this->set('opcion5', $var5);
    $this->set('opcion6', $var6);


	$tabla = '';
	$sql_2 = '';



	if($var1!=null){
		$sql_2 =  ' cod_sector =  '.$var1.'  ';
		$tabla='cfpd02_sector';
	}
	if($var2!=null){
		$sql_2 .= 'and cod_programa = '.$var2.'  ';
		$tabla='cfpd02_programa';
	}
	if($var3!=null){
		$sql_2 .= 'and cod_sub_prog = '.$var3.'  ';
		$tabla='cfpd02_sub_prog';
	}
	if($var4!=null){
		$sql_2 .= 'and cod_proyecto = '.$var4.'  ';
		$tabla='cfpd02_proyecto';
	}
	if($var5!=null){
		$sql_2.= 'and cod_activ_obra = '.$var5.'  ';
		$tabla='cfpd02_activ_obra';
	}

	  $data = $this->$tabla->findAll($sql_2." and ".$condicion, null, null, null);

	  $this->set('datos_cod_cfpp02', $data);

	  $this->set('tabla', $tabla);

	 if($var5!=null){
		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$condicion = "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst;

		if($var3 == 0) $this->set('opcion3', 'cero');
		if($var4 == 0) $this->set('opcion4', 'cero');
		$sql_2.= 'and cod_activ_obra = '.$var5.'  ';
		$tabla='cfpd02_activ_obra';

		$nom = $this->cugd02_dependencia->generateList($condicion, 'cod_dependencia ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
   		$this->concatena($nom, 'arr05');



	}



	$this->consulta_cprogramatica($ano,$pagina);
	$this->render("consulta_cprogramatica");







}//FIN FUNCTION















function eliminar($ejercicio=null, $var1=null, $var2=null, $var3=null, $var4=null, $var5=null){//echo 'fue aqui';
	///pr($this->data);

 	$this->layout = "ajax";

	 $this->set('ejercicio', $ejercicio);


	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and ano = ".$ejercicio;

		//$cod_presi = $this->Session->read('SScodpresi');
	//	$cod_entidad = $this->Session->read('SScodentidad');
//		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	//	$cod_inst = $this->Session->read('SScodinst');
		//$cod_dep = $this->Session->read('SScoddep');
		$condicion2 = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
		$year = $this->cfpd01_formulacion->findAll($condicion2, null, 'ano_formular ASC', null);
		$ano=$year[0]['cfpd01_formulacion']['ano_formular'];

     $sql_2 = $condicion.' and ';

	if($var1!=null){
		$sacar='select sum(compromiso_anual) as suma from cfpd05 where ano='.$ano.' and cod_sector='.$var1.' group by cod_inst,cod_sector';
		$sql_2 .=  ' cod_sector =  '.$var1.'  ';
		$tabla='cfpd02_sector';
		$this->set('opcion1', $var1);
		$this->set('opcion2', null);
		$this->set('opcion3', null);
		$this->set('opcion4', null);
		$this->set('opcion5', null);
	}
	if($var2!=null){
		$sacar='select sum(compromiso_anual) as suma from cfpd05 where ano='.$ano.' and cod_sector='.$var1.' and cod_programa='.$var2.' group by cod_inst,cod_sector,cod_programa';
		$sql_2 .= ' and cod_programa = '.$var2.'  ';
		$tabla='cfpd02_programa';
		$this->set('opcion1', $var1);
		$this->set('opcion2', null);
		$this->set('opcion3', null);
		$this->set('opcion4', null);
		$this->set('opcion5', null);

	}
	if($var3!=null){
		$sacar='select sum(compromiso_anual) as suma from cfpd05 where ano='.$ano.' and cod_sector='.$var1.' and cod_programa='.$var2.' and cod_sub_prog='.$var3.' group by cod_inst,cod_sector,cod_programa,cod_sub_prog';
		if($var3 == 0) $this->set('opcion3', 'cero');
		$sql_2 .= ' and cod_sub_prog = '.$var3.'  ';
		$tabla='cfpd02_sub_prog';
		$this->set('opcion1', $var1);
		$this->set('opcion2', $var2);
		$this->set('opcion3', null);
		$this->set('opcion4', null);
		$this->set('opcion5', null);
	}
	if($var4!=null){
		$sacar='select sum(compromiso_anual) as suma from cfpd05 where ano='.$ano.' and cod_sector='.$var1.' and cod_programa='.$var2.' and cod_sub_prog='.$var3.' and cod_proyecto='.$var4.' group by cod_inst,cod_sector,cod_programa,cod_sub_prog,cod_proyecto';
		if($var4 == 0) $this->set('opcion4', 'cero');
		$sql_2 .= ' and cod_proyecto = '.$var4.'  ';
		$tabla='cfpd02_proyecto';
		$this->set('opcion1', $var1);
		$this->set('opcion2', $var2);
		$this->set('opcion3', $var3);
		$this->set('opcion4', null);
		$this->set('opcion5', null);

	}
	if($var5!=null){
		$sacar='select sum(compromiso_anual) as suma from cfpd05 where ano='.$ano.' and cod_sector='.$var1.' and cod_programa='.$var2.' and cod_sub_prog='.$var3.' and cod_proyecto='.$var4.' and cod_activ_obra='.$var5.' group by cod_inst,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra';
		if($var3 == 0) $this->set('opcion3', 'cero');
		if($var4 == 0) $this->set('opcion4', 'cero');
		$sql_2  .= ' and cod_activ_obra = '.$var5.'  ';
		$tabla='cfpd02_activ_obra';
		$this->set('opcion1', $var1);
		$this->set('opcion2', $var2);
		$this->set('opcion3', $var3);
		$this->set('opcion4', $var4);
		$this->set('opcion5', null);

	}
	//echo $sacar.' <br>';
	$cant=$this->cfpd05->findCount($sql_2);
	if($cant!=0){
	$compro=$this->cfpd02_activ_obra->execute($sacar);
//	print_r($compro);
	$monto=$compro[0][0]['suma'];
	//echo $monto;
	}

	if(isset($monto) &&$monto !=0){
		$this->set('errorMessage', 'Esta información no puede ser eliminada');
	}else{
	$sql_1 = 'DELETE  FROM '.$tabla.' WHERE ';
	$sql = $sql_1.$sql_2.' ;';
	//echo $sql.' <br>';
	$c05='delete from cfpd05 where '.$sql_2;
	//echo $c05;
	$this->$tabla->execute($sql);
	$this->cfpd05->execute($c05);
	$this->set('errorMessage', 'Los Datos Fueron Eliminados ');

	$tabla = '';
	$sql_2 = '';
	}


	if($var2!=null){
		$sql_2 =  $condicion.' and cod_sector =  '.$var1.'  ';
		$tabla='cfpd02_sector';
	}
	if($var3!=null){
		$sql_2 .= 'and cod_programa = '.$var2.'  ';
		$tabla='cfpd02_programa';
	}
	if($var4!=null){
		$sql_2 .= 'and cod_sub_prog = '.$var3.'  ';
		$tabla='cfpd02_sub_prog';
	}
	if($var5!=null){
		$sql_2 .= 'and cod_proyecto = '.$var4.'  ';
		$tabla='cfpd02_proyecto';
	}

	  if($sql_2 != ''){
	  	$data = $this->$tabla->findAll($sql_2, null, null, null);
	  	$this->set('datos_cod_cfpp02', $data);
	  	$this->set('tabla', $tabla);
	  }


 }//FIN FUNCTION


function add_c_c($var= null){
	if($var <=9 && strlen($var)==1){
		$codigo = '0'.$var;
	}else{
		$codigo = $var;
	}

	return $codigo;
}





 function consulta ($ejercicio=null, $pag_num=null) {set_time_limit(0);

 	$this->layout = "ajax";

	if($ejercicio!=null){
		$this->set('ejercicio', $ejercicio);

	}else if($this->data['cfpp02']['ano'])
	{
		$this->set('ejercicio', $this->data['cfpp02']['ano']);
		$ejercicio = $this->data['cfpp02']['ano'];
	}

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst."
and cod_dep = ".$cod_dep." and ano = ".$ejercicio;

	/*$sector = $this->cfpd02_sector->findAll($condicion, null, 'cod_sector ASC', null, null, null);
	$programa = $this->cfpd02_programa->findAll($condicion, null, 'cod_sector, cod_programa ASC', null, null, null);
	$sub_prog = $this->cfpd02_sub_prog->findAll($condicion, null, 'cod_sector, cod_programa, cod_sub_prog ASC', null, null, null);
	$proyecto = $this->cfpd02_proyecto->findAll($condicion, null, 'cod_sector, cod_programa, cod_sub_prog, cod_proyecto ASC', null, null, null);
	$activ_obra = $this->cfpd02_activ_obra->findAll($condicion, null, 'cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra ASC', null, null, null);


	$sector_ver = '';
	$programa_ver = '';
	$sub_prog_ver = '';
	$proyecto_ver = '';
	$activ_obra_ver = '';


	$sector_ver_aux = '';
	$programa_ver_aux = '';
	$sub_prog_ver_aux = '';
	$proyecto_ver_aux = '';
	$activ_obra_ver_aux = '';


	$consulta = '';
	$index = 0;


	$i = 0;
	$j = 0;
	$k = 0;
	$l = 0;
	$n = 0;



 foreach($sector as $row){

 	$i++;
 	$sector_ver[$i]  = $this->add_c_c($row['cfpd02_sector']['cod_sector']);
	$sector_ver_aux[$i] = $this->add_c_c($row['cfpd02_sector']['cod_sector']);
	$sector_denominacion[$i] = $row['cfpd02_sector']['denominacion'];
	$sector_objetivo[$i] =  $row['cfpd02_sector']['objetivo'];
	$sector_ano[$i] =  $row['cfpd02_sector']['ano'];
	$sector_unidad_ejecutora[$i] =  $row['cfpd02_sector']['unidad_ejecutora'];
	$sector_funcionario_responsable[$i] =  $row['cfpd02_sector']['funcionario_responsable'];

}



 foreach($programa as $row){

 $j++;
 $programa_ver[$j] = $this->add_c_c($row['cfpd02_programa']['cod_sector']).".".$this->add_c_c($row['cfpd02_programa']['cod_programa']);
 $programa_ver_aux[$j] = $this->add_c_c($row['cfpd02_programa']['cod_programa']);
 $programa_denominacion[$j]=$row['cfpd02_programa']['denominacion'];
 $programa_objetivo[$j] =  $row['cfpd02_programa']['objetivo'];
 $programa_ano[$j] =  $row['cfpd02_programa']['ano'];
 $programa_unidad_ejecutora[$j] =  $row['cfpd02_programa']['unidad_ejecutora'];
 $programa_funcionario_responsable[$j] =  $row['cfpd02_programa']['funcionario_responsable'];

 }

 foreach($sub_prog as $row){
	$k++;
	$sub_prog_ver[$k] = $this->add_c_c($row['cfpd02_sub_prog']['cod_sector']).".".$this->add_c_c($row['cfpd02_sub_prog']['cod_programa']).".".$this->add_c_c($row['cfpd02_sub_prog']['cod_sub_prog']);
	$sub_prog_ver_aux[$k] = $this->add_c_c($row['cfpd02_sub_prog']['cod_sub_prog']);
	$sub_prog_denominacion[$k] =$row['cfpd02_sub_prog']['denominacion'];
	$sub_prog_objetivo[$k] =  $row['cfpd02_sub_prog']['objetivo'] ;
	$sub_prog_ano[$k] =  $row['cfpd02_sub_prog']['ano'] ;
	$sub_prog_unidad_ejecutora[$k] =  $row['cfpd02_sub_prog']['unidad_ejecutora'] ;
	$sub_prog_funcionario_responsable[$k] =  $row['cfpd02_sub_prog']['funcionario_responsable'] ;
 }


foreach($proyecto as $row){
	$l++;
	$proyecto_ver [$l]=	$this->add_c_c($row['cfpd02_proyecto']['cod_sector']).".".$this->add_c_c($row['cfpd02_proyecto']['cod_programa']).".".$this->add_c_c($row['cfpd02_proyecto']['cod_sub_prog']).".".$this->add_c_c($row['cfpd02_proyecto']['cod_proyecto']);
 	$proyecto_ver_aux[$l] = $this->add_c_c($row['cfpd02_proyecto']['cod_proyecto']);
 	$proyecto_denominacion[$l] = $row['cfpd02_proyecto']['denominacion'];
 	$proyecto_objetivo[$l] = $row['cfpd02_proyecto']['objetivo'];
 	$proyecto_ano[$l] = $row['cfpd02_proyecto']['ano'];
 	$proyecto_unidad_ejecutora[$l] = $row['cfpd02_proyecto']['unidad_ejecutora'];
 	$proyecto_funcionario_responsable[$l] = $row['cfpd02_proyecto']['funcionario_responsable'];

 }


 foreach($activ_obra as $row){

 	$n++;
	$activ_obra_ver[$n] = $this->add_c_c($row['cfpd02_activ_obra']['cod_sector']).".".$this->add_c_c($row['cfpd02_activ_obra']['cod_programa']).".".$this->add_c_c($row['cfpd02_activ_obra']['cod_sub_prog']).".".$this->add_c_c($row['cfpd02_activ_obra']['cod_proyecto']).".".$this->add_c_c($row['cfpd02_activ_obra']['cod_activ_obra']);

	$activ_obra_ver_aux[$n] = $row['cfpd02_activ_obra']['cod_activ_obra'];
 	$activ_obra_denominacion[$n] = $this->add_c_c($row['cfpd02_activ_obra']['denominacion']);
 	$activ_obra_objetivo[$n] =$row['cfpd02_activ_obra']['objetivo'];
 	$activ_obra_ano[$n] =$row['cfpd02_activ_obra']['ano'];
 	$activ_obra_unidad_ejecutora[$n] =$row['cfpd02_activ_obra']['unidad_ejecutora'];
 	$activ_obra_funcionario_responsable[$n] =$row['cfpd02_activ_obra']['funcionario_responsable'];
 	$activ_obra_titulo[$n] =$row['cfpd02_activ_obra']['titulo'];

 }
















for($a=1; $a<=$i; $a++){
		$index++;
		$consulta[$index]['codigo'] =  $sector_ver[$a].'.00.00.00.00';
		$consulta[$index]['denominacion'] =  $sector_denominacion[$a];
        $consulta[$index]['objetivo'] =   $sector_objetivo[$a];
        $consulta[$index]['unidad_ejecutora'] =   $sector_unidad_ejecutora[$a];
        $consulta[$index]['funcionario_responsable'] =   $sector_funcionario_responsable[$a];
        $consulta[$index]['ano'] = $sector_ano[$a];

}//fin for


for($b=1; $b<=$j; $b++){
  if($programa_ver[$b]!=''){
		$index++;
		$consulta[$index]['codigo'] =   $programa_ver[$b].'.00.00.00' ;
		$consulta[$index]['denominacion'] =  $programa_denominacion[$b];
		$consulta[$index]['objetivo'] =   $programa_objetivo[$b];
		$consulta[$index]['ano'] =   $programa_ano[$b];
		$consulta[$index]['unidad_ejecutora'] =   $programa_unidad_ejecutora[$b];
		$consulta[$index]['funcionario_responsable'] =   $programa_funcionario_responsable[$b];
	}//fin if
}//fin for


for($c=1; $c<=$k; $c++){
   if($sub_prog_ver[$c]!=''){
			$index++;
			$consulta[$index]['codigo'] =   $sub_prog_ver[$c].'.00.00' ;
			$consulta[$index]['denominacion'] =  $sub_prog_denominacion[$c];
            $consulta[$index]['objetivo'] =   $sub_prog_objetivo[$c];
            $consulta[$index]['ano'] =   $sub_prog_ano[$c];
            $consulta[$index]['unidad_ejecutora'] =   $sub_prog_unidad_ejecutora[$c];
            $consulta[$index]['funcionario_responsable'] =   $sub_prog_funcionario_responsable[$c];
		}//fin if
}//fin for


for($d=1; $d<=$l; $d++){
   if($proyecto_ver[$d]!=''){
			$index++;
			$consulta[$index]['codigo'] =   $proyecto_ver[$d].'.00';
			$consulta[$index]['denominacion'] =  $proyecto_denominacion[$d];
            $consulta[$index]['objetivo'] =   $proyecto_objetivo[$d];
            $consulta[$index]['ano'] =   $proyecto_ano[$d];
            $consulta[$index]['unidad_ejecutora'] =   $proyecto_unidad_ejecutora[$d];
            $consulta[$index]['funcionario_responsable'] =   $proyecto_funcionario_responsable[$d];
		}//fin if
 }//fin for


for($e=1; $e<=$n; $e++){
	if($activ_obra_ver[$e]!= ''){
			$index++;
			$consulta[$index]['codigo'] =   $activ_obra_ver[$e];
			$consulta[$index]['denominacion'] =  $activ_obra_denominacion[$e];
            $consulta[$index]['objetivo'] =   $activ_obra_objetivo[$e];
            $consulta[$index]['ano'] =   $activ_obra_ano[$e];
            $consulta[$index]['unidad_ejecutora'] =   $activ_obra_unidad_ejecutora[$e];
            $consulta[$index]['funcionario_responsable'] =   $activ_obra_funcionario_responsable[$e];
            $consulta[$index]['titulo'] =   $activ_obra_titulo[$e];
		}//fin if
 }//fin for
*/






$index = 0;

$sector=$this->cfpd02_sector->findAll($condicion,null,'cod_sector ASC');
	$c_sector=$this->cfpd02_sector->findCount($condicion);
	if($c_sector!=0){
    	foreach($sector as $sec){$index++;
	    	$consulta[$index]['codigo'] =   $sec['cfpd02_sector']['cod_sector'].'.00.00.00.00';
	    	$consulta[$index]['tabla']  =   "cfpd02_sector";
			$consulta[$index]['denominacion'] =  $sec['cfpd02_sector']['denominacion'];
            $consulta[$index]['objetivo'] =   $sec['cfpd02_sector']['objetivo'];
            $consulta[$index]['ano'] =   $sec['cfpd02_sector']['ano'];
            $consulta[$index]['unidad_ejecutora'] =   $sec['cfpd02_sector']['unidad_ejecutora'];
            $consulta[$index]['funcionario_responsable'] =   $sec['cfpd02_sector']['funcionario_responsable'];
            //$consulta[$index]['titulo'] =   $sec['cfpd02_sector']['titulo'];
	    	$programa=$this->cfpd02_programa->findAll($condicion." and cod_sector=".$sec['cfpd02_sector']['cod_sector'],null,'cod_programa ASC');
		    $c_programa=$this->cfpd02_programa->findCount($condicion." and cod_sector=".$sec['cfpd02_sector']['cod_sector']);
		    if($c_programa!=0){
		    	foreach($programa as $pro){$index++;
		    $consulta[$index]['codigo'] =   $sec['cfpd02_sector']['cod_sector'].'.'.$this->add_c_c($pro['cfpd02_programa']['cod_programa']).'.00.00.00';
		    $consulta[$index]['tabla']  =   "cfpd02_programa";
			$consulta[$index]['denominacion'] =  $pro['cfpd02_programa']['denominacion'];
            $consulta[$index]['objetivo'] =   $pro['cfpd02_programa']['objetivo'];
            $consulta[$index]['ano'] =   $pro['cfpd02_programa']['ano'];
            $consulta[$index]['unidad_ejecutora'] =   $pro['cfpd02_programa']['unidad_ejecutora'];
            $consulta[$index]['funcionario_responsable'] =   $pro['cfpd02_programa']['funcionario_responsable'];
            //$consulta[$index]['titulo'] =   $pro['cfpd02_programa']['titulo'];
                    $subprograma=$this->cfpd02_sub_prog->findAll($condicion." and cod_sector=".$sec['cfpd02_sector']['cod_sector']." and cod_programa=".$pro['cfpd02_programa']['cod_programa'],null,'cod_sub_prog ASC');
			        $c_subprograma=$this->cfpd02_sub_prog->findCount($condicion." and cod_sector=".$sec['cfpd02_sector']['cod_sector']." and cod_programa=".$pro['cfpd02_programa']['cod_programa']);//200.84.68.85
                    if($c_subprograma!=0){
                    	foreach($subprograma as $sub){$index++;
            $consulta[$index]['codigo'] =   $sec['cfpd02_sector']['cod_sector'].'.'.$this->add_c_c($pro['cfpd02_programa']['cod_programa']).'.'.$this->add_c_c($sub['cfpd02_sub_prog']['cod_sub_prog']).'.00.00';
            $consulta[$index]['tabla']  =   "cfpd02_sub_prog";
			$consulta[$index]['denominacion'] =  $sub['cfpd02_sub_prog']['denominacion'];
            $consulta[$index]['objetivo'] =   $sub['cfpd02_sub_prog']['objetivo'];
            $consulta[$index]['ano'] =   $sub['cfpd02_sub_prog']['ano'];
            $consulta[$index]['unidad_ejecutora'] =   $sub['cfpd02_sub_prog']['unidad_ejecutora'];
            $consulta[$index]['funcionario_responsable'] =   $sub['cfpd02_sub_prog']['funcionario_responsable'];
            //$consulta[$index]['titulo'] =   $sub['cfpd02_sub_prog']['titulo'];
                            $proyecto=$this->cfpd02_proyecto->findAll($condicion." and cod_sector=".$sec['cfpd02_sector']['cod_sector']." and cod_programa=".$pro['cfpd02_programa']['cod_programa']." and cod_sub_prog=".$sub['cfpd02_sub_prog']['cod_sub_prog'],null,'cod_proyecto ASC');
			                $c_proyecto=$this->cfpd02_proyecto->findCount($condicion." and cod_sector=".$sec['cfpd02_sector']['cod_sector']." and cod_programa=".$pro['cfpd02_programa']['cod_programa']." and cod_sub_prog=".$sub['cfpd02_sub_prog']['cod_sub_prog']);
			                if($c_proyecto!=0){
                                foreach($proyecto as $proy){$index++;
            $consulta[$index]['codigo'] =   $sec['cfpd02_sector']['cod_sector'].'.'.$this->add_c_c($pro['cfpd02_programa']['cod_programa']).'.'.$this->add_c_c($sub['cfpd02_sub_prog']['cod_sub_prog']).'.'.$this->add_c_c($proy['cfpd02_proyecto']['cod_proyecto']).'.00';
			$consulta[$index]['tabla']  =   "cfpd02_proyecto";
			$consulta[$index]['denominacion'] =  $proy['cfpd02_proyecto']['denominacion'];
            $consulta[$index]['objetivo'] =   $proy['cfpd02_proyecto']['objetivo'];
            $consulta[$index]['ano'] =   $proy['cfpd02_proyecto']['ano'];
            $consulta[$index]['unidad_ejecutora'] =   $proy['cfpd02_proyecto']['unidad_ejecutora'];
            $consulta[$index]['funcionario_responsable'] =   $proy['cfpd02_proyecto']['funcionario_responsable'];
            //$consulta[$index]['titulo'] =   $proy['cfpd02_proyecto']['titulo'];

                                	$actividad=$this->cfpd02_activ_obra->findAll($condicion." and cod_sector=".$sec['cfpd02_sector']['cod_sector']." and cod_programa=".$pro['cfpd02_programa']['cod_programa']." and cod_sub_prog=".$sub['cfpd02_sub_prog']['cod_sub_prog']." and cod_proyecto=".$proy['cfpd02_proyecto']['cod_proyecto'],null,'cod_activ_obra ASC');
			                        $c_actividad=$this->cfpd02_activ_obra->findCount($condicion." and cod_sector=".$sec['cfpd02_sector']['cod_sector']." and cod_programa=".$pro['cfpd02_programa']['cod_programa']." and cod_sub_prog=".$sub['cfpd02_sub_prog']['cod_sub_prog']." and cod_proyecto=".$proy['cfpd02_proyecto']['cod_proyecto']);
                                    if($c_actividad!=0){
                                    	foreach($actividad as $act){$index++;
            $consulta[$index]['codigo'] =   $sec['cfpd02_sector']['cod_sector'].'.'.$this->add_c_c($pro['cfpd02_programa']['cod_programa']).'.'.$this->add_c_c($sub['cfpd02_sub_prog']['cod_sub_prog']).'.'.$this->add_c_c($proy['cfpd02_proyecto']['cod_proyecto']).'.'.$this->add_c_c($act['cfpd02_activ_obra']['cod_activ_obra']);
			$consulta[$index]['tabla']  =   "cfpd02_activ_obra";
			$consulta[$index]['denominacion'] =  $act['cfpd02_activ_obra']['denominacion'];
            $consulta[$index]['objetivo'] =   $act['cfpd02_activ_obra']['objetivo'];
            $consulta[$index]['ano'] =   $act['cfpd02_activ_obra']['ano'];
            $consulta[$index]['unidad_ejecutora'] =   $act['cfpd02_activ_obra']['unidad_ejecutora'];
            $consulta[$index]['funcionario_responsable'] =   $act['cfpd02_activ_obra']['funcionario_responsable'];
            $consulta[$index]['titulo'] =   $act['cfpd02_activ_obra']['titulo'];

                                    	}
                                    }//act
                                }
			                }//proy
                    	}
                    }//sub prog
		    	}
		    }//prog
    	}
    }//sector






$_SESSION['consulta'] = $consulta;
$this->set('consulta', $consulta);


//print_r($consulta);


if($pag_num!=null){
	$this->set('pagina_actual', $pag_num);
}



}//fin function






function consulta_continuacion($ejercicio=null, $pag_num=null){

$this->layout = "ajax";
$consulta = $_SESSION['consulta'];
$this->set('consulta', $consulta);
$this->set('ejercicio', $ejercicio);


//print_r($consulta);


if($pag_num!=null){$this->set('pagina_actual', $pag_num);}


}//fin function


 function traspaso($ejercicio=null){

    $this->layout = "ajax";
	$this->set('ejercicio', $ejercicio);


	//$ejercicio = $this->data['cfpp01']['ano'];

	$codigos = "";
	$values = "";


	$sector = $this->cfpd02_sector->findAll(null, null, 'cod_sector ASC', null, null, null);


	foreach($sector as $datos){

		$var1 = $datos['cfpd02_sector']['cod_sector'];
		$concepto = $datos['cfpd02_sector']['concepto'];
		$descripcion = $datos['cfpd02_sector']['descripcion'];

		$codigos = "cod_sector, ";
		$values =  " '".$var1."',  " ;
		$tabla='cfpd02_sector';

		$sql_aux = "ejercicio = ".$ejercicio." and cod_sector='".$var1."'  ";
		if($this->cfpd02_sector->findCount($sql_aux) == 0){
			$sql_1 = "INSERT INTO  cfpd02_1_sector  (ejercicio,  ".$codigos."  concepto, denominacion)   VALUES  ( '".$ejercicio."',  ".$values."   '$concepto', '$descripcion' )  ";
			$this->$tabla->execute($sql_1);
		}

	}//fin foreach


	$programa = $this->cfpd02_programa->findAll(null, null, 'cod_programa ASC', null, null, null);

	foreach($programa as $datos){

		$var1 = $datos['cfpd02_programa']['cod_sector'];
		$var2 = $datos['cfpd02_programa']['cod_programa'];
		$concepto = $datos['cfpd02_programa']['concepto'];
		$descripcion = $datos['cfpd02_programa']['descripcion'];


		$codigos = "cod_sector, cod_programa, ";
		$values =  " '".$var1."', '".$var2."',  ";
		$tabla='cfpd02_programa';

		$sql_aux = "ejercicio = ".$ejercicio." and cod_sector='".$var1."' and  cod_programa='".$var2."'  ";
		if($this->cfpd02_programa->findCount($sql_aux) == 0){
			$sql_1 = "INSERT INTO  cfpd02_2_programa   (ejercicio,  ".$codigos."  concepto, denominacion)   VALUES  ( '".$ejercicio."',  ".$values."   '$concepto', '$descripcion' )  ";
			$this->$tabla->execute($sql_1);
		}

	}//fin foreach



	$sub_prog = $this->cfpd02_sub_prog->findAll(null, null, 'cod_sub_prog ASC', null, null, null);

	foreach($sub_prog as $datos){

		$var1 = $datos['cfpd02_sub_prog']['cod_sector'];
		$var2 = $datos['cfpd02_sub_prog']['cod_programa'];
		$var3 = $datos['cfpd02_sub_prog']['cod_sub_prog'];
		$concepto = $datos['cfpd02_sub_prog']['concepto'];
		$descripcion = $datos['cfpd02_sub_prog']['descripcion'];

		$codigos = "cod_sector,  cod_programa,  cod_sub_prog, ";
		$values =  " '".$var1."', '".$var2."', '".$var3."', ";
		$tabla='cfpd02_sub_prog';

		$sql_aux = "ejercicio = ".$ejercicio." and cod_sector='".$var1."' and  cod_programa='".$var2."' and  cod_sub_prog='".$var3."' ";
		if($this->cfpd02_sub_prog->findCount($sql_aux) == 0){
			$sql_1 = "INSERT INTO  cfpd02_3_sub_prog   (ejercicio,  ".$codigos."  concepto, denominacion)   VALUES  ( '".$ejercicio."',  ".$values."   '$concepto', '$descripcion' )  ";
			$this->$tabla->execute($sql_1);
		}

	}//fin foreach



	$proyecto = $this->cfpd02_proyecto->findAll(null, null, 'cod_proyecto ASC', null, null, null);

	foreach($proyecto as $datos){

		$var1 = $datos['cfpd02_proyecto']['cod_sector'];
		$var2 = $datos['cfpd02_proyecto']['cod_programa'];
		$var3 = $datos['cfpd02_proyecto']['cod_sub_prog'];
		$var4 = $datos['cfpd02_proyecto']['cod_proyecto'];
		$concepto = $datos['cfpd02_proyecto']['concepto'];
		$descripcion = $datos['cfpd02_proyecto']['descripcion'];

		$codigos = "cod_sector, cod_programa, cod_sub_prog, cod_proyecto,";
		$values =  " '".$var1."',  '".$var2."',    '".$var3."', '".$var4."', ";
	    $tabla='cfpd02_proyecto';

		$sql_aux = "ejercicio = ".$ejercicio." and cod_sector='".$var1."' and  cod_programa='".$var2."' and  cod_sub_prog='".$var3."' and  cod_proyecto='".$var4."'  ";
		if($this->cfpd02_proyecto->findCount($sql_aux) == 0){
			$sql_1 = "INSERT INTO  cfpd02_4_proyecto   (ejercicio,  ".$codigos."  concepto, denominacion)   VALUES  ( '".$ejercicio."',  ".$values."   '$concepto', '$descripcion' )  ";
 			$this->$tabla->execute($sql_1);
 		}

		$sql_aux = "ejercicio = ".$ejercicio." and cod_sector='".$var1."' and  cod_programa='".$var2."' and  cod_sub_prog='".$var3."' and  cod_proyecto='".$var4."' and cod_activ_obra='0'  ";
		if($this->cfpd02_activ_obra->findCount($sql_aux) == 0){
			$codigos_aux = "cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra,";
			$values_aux =  " '".$var1."',  '".$var2."',   '".$var3."', '".$var4."', '0', ";
 			$sql_2_aux = "INSERT INTO  cfpd02_5_activ_obra   (ejercicio,  ".$codigos_aux."  concepto, denominacion)   VALUES  ( '".$ejercicio."',  ".$values_aux."   '$concepto', '$descripcion' )  ";
			$this->cfpd02_activ_obra->execute($sql_2_aux);
		}

	}//fin foreach







	$activ_obra = $this->cfpd02_activ_obra->findAll(null, null, 'cod_activ_obra ASC', null, null, null);

	foreach($activ_obra as $datos){

		$var1 = $datos['cfpd02_activ_obra']['cod_sector'];
		$var2 = $datos['cfpd02_activ_obra']['cod_programa'];
		$var3 = $datos['cfpd02_activ_obra']['cod_sub_prog'];
		$var4 = $datos['cfpd02_activ_obra']['cod_proyecto'];
		$var5 = $datos['cfpd02_activ_obra']['cod_activ_obra'];
		$concepto = $datos['cfpd02_activ_obra']['concepto'];
		$descripcion = $datos['cfpd02_activ_obra']['descripcion'];

		$codigos = "cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra,";
		$values =  " '".$var1."',  '".$var2."',    '".$var3."', '".$var4."', '".$var5."', ";
		$tabla='cfpd02_activ_obra';

		$sql_aux = "ejercicio = ".$ejercicio." and cod_sector='".$var1."' and  cod_programa='".$var2."' and  cod_sub_prog='".$var3."' and  cod_proyecto='".$var4."' and cod_activ_obra='".$var5."' ";
		if($this->cfpd02_activ_obra->findCount($sql_aux) == 0){
 			$sql_1 = "INSERT INTO  cfpd02_5_activ_obra   (ejercicio,  ".$codigos."  concepto, denominacion)   VALUES  ( '".$ejercicio."',  ".$values."   '$concepto', '$descripcion' )  ";
			$this->$tabla->execute($sql_1);
		}

   }//fin foreach







	$auxiliar = $this->cfpd02_auxiliar->findAll(null, null, 'cod_auxiliar ASC', null, null, null);

	foreach($auxiliar as $datos){

		$var1 = $datos['cfpd02_auxiliar']['cod_sector'];
		$var2 = $datos['cfpd02_auxiliar']['cod_programa'];
		$var3 = $datos['cfpd02_auxiliar']['cod_sub_prog'];
		$var4 = $datos['cfpd02_auxiliar']['cod_proyecto'];
		$var5 = $datos['cfpd02_auxiliar']['cod_activ_obra'];
		$var6 = $datos['cfpd02_auxiliar']['cod_auxiliar'];
		$concepto = $datos['cfpd02_auxiliar']['concepto'];
		$descripcion = $datos['cfpd02_auxiliar']['descripcion'];

		$codigos = "cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_auxiliar,";
		$values =  " '".$var1."',  '".$var2."',    '".$var3."', '".$var4."', '".$var5."', '".$var6."', ";
		$tabla='cfpd02_auxiliar';

		$sql_aux = "ejercicio = ".$ejercicio." and cod_sector='".$var1."' and  cod_programa='".$var2."' and  cod_sub_prog='".$var3."' and  cod_proyecto='".$var4."' and cod_activ_obra='".$var5."'  and cod_auxiliar='".$var6."'";
		if($this->cfpd02_auxiliar->findCount($sql_aux) == 0){
			$sql_1 = "INSERT INTO cfpd02_6_auxiliar   (ejercicio,  ".$codigos."  concepto, denominacion)   VALUES  ( '".$ejercicio."',  ".$values."   '$concepto', '$descripcion' )  ";
			$this->$tabla->execute($sql_1);
		}

	}//fin foreach



    $this->layout = "ajax";
	$sector="";

	$sector = $this->cfpd02_sector->generateList('ejercicio ='.$ejercicio.'', 'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.cod_sector');
   	$this->set('sector', $sector);

    $this->set('Message', 'Fue traspasado del clasificador nacional para el del ejercicio actual');

   if($sector==""){$this->set('traspaso', 'si');}else{$this->set('traspaso', 'no');}


}//fin function




 function traspaso_a_otros($ejercicio_desde=null, $ejercicio_hasta=null){

    $this->layout = "ajax";
   // echo 'si';
	//$this->set('ejercicio', $ejercicio);
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');


	//$ejercicio = $this->data['cfpp01']['ano'];

	$codigos = "";
	$values = "";
    if(isset($ejercicio_desde) && $ejercicio_desde!=null && isset($ejercicio_hasta) && $ejercicio_hasta!=null){
        $ejercicio_aux = $ejercicio_hasta;
	    $ejercicio = $ejercicio_desde;
    }else{
    	$ejercicio_aux = $this->data['cfpd02']['al'];
	    $ejercicio = $this->data['cfpd02']['de'];
    }


	//echo $ejercicio_aux.$ejercicio;
	$this->set('ejercicio', $ejercicio_aux);
	$condicion1 = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst."  and ano = ".$ejercicio;
	$condicion2 = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst."  and ano = ".$ejercicio_aux;

	$sector = $this->cfpd02_sector->findAll($condicion1, null, 'cod_dep,cod_sector ASC', null, null, null);
	//pr($sector);


	foreach($sector as $datos){

		$cp= $datos['cfpd02_sector']['cod_presi'];
		$ce= $datos['cfpd02_sector']['cod_entidad'];
		$ct= $datos['cfpd02_sector']['cod_tipo_inst'];
		$ci= $datos['cfpd02_sector']['cod_inst'];
		$cd= $datos['cfpd02_sector']['cod_dep'];
		$var1 = $datos['cfpd02_sector']['cod_sector'];
		$denominacion = $datos['cfpd02_sector']['denominacion'];
		$unidad_ejecutora = $datos['cfpd02_sector']['unidad_ejecutora'];
		$objetivo = $datos['cfpd02_sector']['objetivo'];
		$funcionario_responsable = $datos['cfpd02_sector']['funcionario_responsable'];

		$codigos = "cod_sector, ";
		$values =  " '".$var1."',  " ;
		$tabla='cfpd02_sector';

		$sql_aux = $condicion2." and cod_dep =".$cd." and cod_sector='".$var1."' ";
		if($this->cfpd02_sector->findCount($sql_aux) == 0){
			//echo 'sector';
			$sql_1 = "INSERT INTO  cfpd02_sector VALUES  ( '$cp', '$ce', '$ct', '$ci', '$cd', '$ejercicio_aux', '$var1', '$denominacion', '$unidad_ejecutora', '$objetivo', '$funcionario_responsable')  ";
		//echo $sql_1;
			$this->$tabla->execute($sql_1);
		}

		}//fin foreach


		$sectorm = $this->cfpd09_metas_sector->findAll($condicion1, null, 'cod_dep,cod_sector ASC', null, null, null);
	foreach($sectorm as $datos){

		$cp= $datos['cfpd09_metas_sector']['cod_presi'];
		$ce= $datos['cfpd09_metas_sector']['cod_entidad'];
		$ct= $datos['cfpd09_metas_sector']['cod_tipo_inst'];
		$ci= $datos['cfpd09_metas_sector']['cod_inst'];
		$cd= $datos['cfpd09_metas_sector']['cod_dep'];
		$sector = $datos['cfpd09_metas_sector']['cod_sector'];
		$meta = $datos['cfpd09_metas_sector']['metas'];
		$unidad_medida = $datos['cfpd09_metas_sector']['unidad_medida'];

//		$codigos = "cod_sector, ";
	//	$values =  " '".$var1."',  " ;
		//$tabla='cfpd02_sector';

		$sql_aux = $condicion2." and cod_dep =".$cd." and cod_sector=".$sector;
		if($this->cfpd09_metas_sector->findCount($sql_aux) == 0){
			$sql_1 = "INSERT INTO  cfpd09_metas_sector VALUES  ( $cp, $ce, $ct, $ci, $cd, $ejercicio_aux, $sector, '".$meta."', '".$unidad_medida."')  ";
			$this->$tabla->execute($sql_1);
		}

		}//fin foreach





	$programa = $this->cfpd02_programa->findAll($condicion1, null, 'cod_dep,cod_sector,cod_programa ASC', null, null, null);

	foreach($programa as $datos){
		$cp= $datos['cfpd02_programa']['cod_presi'];
		$ce= $datos['cfpd02_programa']['cod_entidad'];
		$ct= $datos['cfpd02_programa']['cod_tipo_inst'];
		$ci= $datos['cfpd02_programa']['cod_inst'];
		$cd= $datos['cfpd02_programa']['cod_dep'];
		$var1 = $datos['cfpd02_programa']['cod_sector'];
		$var2 = $datos['cfpd02_programa']['cod_programa'];
		$denominacion = $datos['cfpd02_programa']['denominacion'];
		$unidad_ejecutora = $datos['cfpd02_programa']['unidad_ejecutora'];
		$objetivo = $datos['cfpd02_programa']['objetivo'];
		$funcionario_responsable = $datos['cfpd02_programa']['funcionario_responsable'];


		$codigos = "cod_sector, cod_programa, ";
		$values =  " '".$var1."', '".$var2."',  ";
		$tabla='cfpd02_programa';

		$sql_aux = $condicion2." and cod_dep =".$cd." and cod_sector='".$var1."' and cod_programa = '".$var2."' ";
 		if($this->cfpd02_programa->findCount($sql_aux) == 0){
		//echo 'programa';
			$sql_1 = "INSERT INTO  cfpd02_programa VALUES  ( '$cp', '$ce', '$ct', '$ci', '$cd', '$ejercicio_aux', '$var1', '$var2', '$denominacion', '$unidad_ejecutora', '$objetivo', '$funcionario_responsable')  ";
		//echo $sql_1;
			$this->$tabla->execute($sql_1);
		}

	}//fin foreach

	$programam = $this->cfpd09_metas_programa->findAll($condicion1, null, 'cod_dep,cod_sector,cod_programa ASC', null, null, null);

	foreach($programam as $datos){
		$cp= $datos['cfpd09_metas_programa']['cod_presi'];
		$ce= $datos['cfpd09_metas_programa']['cod_entidad'];
		$ct= $datos['cfpd09_metas_programa']['cod_tipo_inst'];
		$ci= $datos['cfpd09_metas_programa']['cod_inst'];
		$cd= $datos['cfpd09_metas_programa']['cod_dep'];
		$sector = $datos['cfpd09_metas_programa']['cod_sector'];
		$programa = $datos['cfpd09_metas_programa']['cod_programa'];
		$meta = $datos['cfpd09_metas_programa']['metas'];
		$unidad_medida = $datos['cfpd09_metas_programa']['unidad_medida'];


	//	$codigos = "cod_sector, cod_programa, ";
		//$values =  " '".$var1."', '".$var2."',  ";
	//	$tabla='cfpd02_programa';

		$sql_aux = $condicion2." and cod_dep =".$cd." and cod_sector='".$sector."' and cod_programa = '".$programa."' ";
 		if($this->cfpd09_metas_programa->findCount($sql_aux) == 0){
		//echo 'programa';
			$sql_1 = "INSERT INTO  cfpd09_metas_programa VALUES  ($cp, $ce, $ct, $ci, $cd, $ejercicio_aux, $sector,$programa, '".$meta."', '".$unidad_medida."')  ";
		//echo $sql_1;
			$this->$tabla->execute($sql_1);
		}

	}//fin foreach





	$sub_prog = $this->cfpd02_sub_prog->findAll($condicion1, null, 'cod_dep,cod_sector,cod_programa,cod_sub_prog ASC', null, null, null);

	foreach($sub_prog as $datos){
		$cp= $datos['cfpd02_sub_prog']['cod_presi'];
		$ce= $datos['cfpd02_sub_prog']['cod_entidad'];
		$ct= $datos['cfpd02_sub_prog']['cod_tipo_inst'];
		$ci= $datos['cfpd02_sub_prog']['cod_inst'];
		$cd= $datos['cfpd02_sub_prog']['cod_dep'];
		$var1 = $datos['cfpd02_sub_prog']['cod_sector'];
		$var2 = $datos['cfpd02_sub_prog']['cod_programa'];
		$var3 = $datos['cfpd02_sub_prog']['cod_sub_prog'];
		$denominacion = $datos['cfpd02_sub_prog']['denominacion'];
		$unidad_ejecutora = $datos['cfpd02_sub_prog']['unidad_ejecutora'];
		$objetivo = $datos['cfpd02_sub_prog']['objetivo'];
		$funcionario_responsable = $datos['cfpd02_sub_prog']['funcionario_responsable'];

		$codigos = "cod_sector,  cod_programa,  cod_sub_prog, ";
		$values =  " '".$var1."', '".$var2."', '".$var3."', ";
		$tabla='cfpd02_sub_prog';

		$sql_aux = $condicion2." and cod_dep =".$cd." and cod_sector='".$var1."' and cod_programa = '".$var2."' and  cod_sub_prog='".$var3."'  ";
		if($this->cfpd02_sub_prog->findCount($sql_aux) == 0){
 			//echo 'sub_programa';
 			$sql_1 = "INSERT INTO  cfpd02_sub_prog VALUES  ( '$cp', '$ce', '$ct', '$ci', '$cd', '$ejercicio_aux', '$var1', '$var2', '$var3', '$denominacion', '$unidad_ejecutora', '$objetivo', '$funcionario_responsable')  ";
 			//echo $sql_1;
 			$this->$tabla->execute($sql_1);
 		}


	}//fin foreach


$sub_progm = $this->cfpd09_metas_subprog->findAll($condicion1, null, 'cod_dep,cod_sector,cod_programa,cod_sub_prog ASC', null, null, null);

	foreach($sub_progm as $datos){
		$cp= $datos['cfpd09_metas_subprog']['cod_presi'];
		$ce= $datos['cfpd09_metas_subprog']['cod_entidad'];
		$ct= $datos['cfpd09_metas_subprog']['cod_tipo_inst'];
		$ci= $datos['cfpd09_metas_subprog']['cod_inst'];
		$cd= $datos['cfpd09_metas_subprog']['cod_dep'];
		$sector = $datos['cfpd09_metas_subprog']['cod_sector'];
		$programa = $datos['cfpd09_metas_subprog']['cod_programa'];
		$subprograma = $datos['cfpd09_metas_subprog']['cod_sub_prog'];
		$meta = $datos['cfpd09_metas_subprog']['metas'];
		$unidad_medida = $datos['cfpd09_metas_subprog']['unidad_medida'];

		//$codigos = "cod_sector,  cod_programa,  cod_sub_prog, ";
	//	$values =  " '".$var1."', '".$var2."', '".$var3."', ";
//		$tabla='cfpd02_sub_prog';

		$sql_aux = $condicion2." and cod_dep =".$cd." and cod_sector='".$sector."' and cod_programa = '".$programa."' and  cod_sub_prog='".$subprograma."'  ";
		if($this->cfpd09_metas_subprog->findCount($sql_aux) == 0){
 			//echo 'sub_programa';
 			$sql_1 = "INSERT INTO  cfpd09_metas_subprog VALUES  ($cp, $ce, $ct, $ci, $cd, $ejercicio_aux, $sector,$programa,$subprograma,'".$meta."', '".$unidad_medida."')  ";
 			//echo $sql_1;
 			$this->$tabla->execute($sql_1);
 		}


	}//fin foreach



	$proyecto = $this->cfpd02_proyecto->findAll($condicion1, null, 'cod_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto ASC', null, null, null);

	foreach($proyecto as $datos){
		$cp= $datos['cfpd02_proyecto']['cod_presi'];
		$ce= $datos['cfpd02_proyecto']['cod_entidad'];
		$ct= $datos['cfpd02_proyecto']['cod_tipo_inst'];
		$ci= $datos['cfpd02_proyecto']['cod_inst'];
		$cd= $datos['cfpd02_proyecto']['cod_dep'];
		$var1 = $datos['cfpd02_proyecto']['cod_sector'];
		$var2 = $datos['cfpd02_proyecto']['cod_programa'];
		$var3 = $datos['cfpd02_proyecto']['cod_sub_prog'];
		$var4 = $datos['cfpd02_proyecto']['cod_proyecto'];
		$denominacion = $datos['cfpd02_proyecto']['denominacion'];
		$unidad_ejecutora = $datos['cfpd02_proyecto']['unidad_ejecutora'];
		$objetivo = $datos['cfpd02_proyecto']['objetivo'];
		$funcionario_responsable = $datos['cfpd02_proyecto']['funcionario_responsable'];

		$codigos = "cod_sector, cod_programa, cod_sub_prog, cod_proyecto,";
		$values =  " '".$var1."',  '".$var2."',    '".$var3."', '".$var4."', ";
	    $tabla='cfpd02_proyecto';

		$sql_aux = $condicion2." and cod_dep =".$cd." and cod_sector='".$var1."' and cod_programa = '".$var2."' and  cod_sub_prog='".$var3."' and  cod_proyecto='".$var4."'   ";
 		if($this->cfpd02_proyecto->findCount($sql_aux) == 0){
 			//echo 'proyecto';
 			$sql_1 = "INSERT INTO  cfpd02_proyecto VALUES  ( '$cp', '$ce', '$ct', '$ci', '$cd', '$ejercicio_aux', '$var1', '$var2', '$var3', '$var4', '$denominacion', '$unidad_ejecutora', '$objetivo', '$funcionario_responsable')  ";
		//	echo $sql_1;
			$this->$tabla->execute($sql_1);
		}

 	}//fin foreach




	$proyectom = $this->cfpd09_metas_proyecto->findAll($condicion1, null, 'cod_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto ASC', null, null, null);

	foreach($proyectom as $datos){
		$cp= $datos['cfpd09_metas_proyecto']['cod_presi'];
		$ce= $datos['cfpd09_metas_proyecto']['cod_entidad'];
		$ct= $datos['cfpd09_metas_proyecto']['cod_tipo_inst'];
		$ci= $datos['cfpd09_metas_proyecto']['cod_inst'];
		$cd= $datos['cfpd09_metas_proyecto']['cod_dep'];
		$sector = $datos['cfpd09_metas_proyecto']['cod_sector'];
		$programa = $datos['cfpd09_metas_proyecto']['cod_programa'];
		$subprograma = $datos['cfpd09_metas_proyecto']['cod_sub_prog'];
		$proyecto = $datos['cfpd09_metas_proyecto']['cod_proyecto'];
		$meta = $datos['cfpd09_metas_proyecto']['metas'];
		$unidad_medida = $datos['cfpd09_metas_proyecto']['unidad_medida'];

		//$codigos = "cod_sector, cod_programa, cod_sub_prog, cod_proyecto,";
		//$values =  " '".$var1."',  '".$var2."',    '".$var3."', '".$var4."', ";
	    //$tabla='cfpd02_proyecto';

		$sql_aux = $condicion2." and cod_dep =".$cd." and cod_sector='".$sector."' and cod_programa = '".$programa."' and  cod_sub_prog='".$subprograma."' and  cod_proyecto='".$proyecto."'   ";
 		if($this->cfpd09_metas_proyecto->findCount($sql_aux) == 0){
 			//echo 'proyecto';
 			$sql_1 = "INSERT INTO  cfpd09_metas_proyecto VALUES  ($cp, $ce, $ct, $ci, $cd, $ejercicio_aux, $sector,$programa,$subprograma,$proyecto,'".$meta."', '".$unidad_medida."')  ";
		//	echo $sql_1;
			$this->$tabla->execute($sql_1);
		}

 	}//fin foreach






	$activ_obra = $this->cfpd02_activ_obra->findAll($condicion1, null, 'cod_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra ASC', null, null, null);

	foreach($activ_obra as $datos){
		$cp= $datos['cfpd02_activ_obra']['cod_presi'];
		$ce= $datos['cfpd02_activ_obra']['cod_entidad'];
		$ct= $datos['cfpd02_activ_obra']['cod_tipo_inst'];
		$ci= $datos['cfpd02_activ_obra']['cod_inst'];
		$cd= $datos['cfpd02_activ_obra']['cod_dep'];
		$var1 = $datos['cfpd02_activ_obra']['cod_sector'];
		$var2 = $datos['cfpd02_activ_obra']['cod_programa'];
		$var3 = $datos['cfpd02_activ_obra']['cod_sub_prog'];
		$var4 = $datos['cfpd02_activ_obra']['cod_proyecto'];
		$var5 = $datos['cfpd02_activ_obra']['cod_activ_obra'];
		$denominacion = $datos['cfpd02_activ_obra']['denominacion'];
		$unidad_ejecutora = $datos['cfpd02_activ_obra']['unidad_ejecutora'];
		$objetivo = $datos['cfpd02_activ_obra']['objetivo'];
		$titulo = $datos['cfpd02_activ_obra']['titulo'];
		$funcionario_responsable = $datos['cfpd02_activ_obra']['funcionario_responsable'];

		$codigos = "cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra,";
		$values =  " '".$var1."',  '".$var2."',    '".$var3."', '".$var4."', '".$var5."', ";
		$tabla='cfpd02_activ_obra';


		$sql_aux = $condicion2." and cod_dep =".$cd." and cod_sector='".$var1."' and cod_programa = '".$var2."' and  cod_sub_prog='".$var3."' and  cod_proyecto='".$var4."' and cod_activ_obra='".$var5."'  ";
		//echo $sql_aux.'<br>';
		if($this->cfpd02_activ_obra->findCount($sql_aux) == 0){
			//echo 'actividad';
			$sql_1 = "INSERT INTO  cfpd02_activ_obra VALUES  ( '$cp', '$ce', '$ct', '$ci', '$cd', '$ejercicio_aux', '$var1', '$var2', '$var3', '$var4', '$var5', '$denominacion', '$unidad_ejecutora', '$objetivo', '$titulo', '$funcionario_responsable')  ";
		//	echo $sql_1;
			$this->$tabla->execute($sql_1);
		}

 	}//fin foreach

	$activ_obram = $this->cfpd09_metas_actividad->findAll($condicion1, null, 'cod_dep,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra ASC', null, null, null);

	foreach($activ_obram as $datos){
		$cp= $datos['cfpd09_metas_actividad']['cod_presi'];
		$ce= $datos['cfpd09_metas_actividad']['cod_entidad'];
		$ct= $datos['cfpd09_metas_actividad']['cod_tipo_inst'];
		$ci= $datos['cfpd09_metas_actividad']['cod_inst'];
		$cd= $datos['cfpd09_metas_actividad']['cod_dep'];
		$sector = $datos['cfpd09_metas_actividad']['cod_sector'];
		$programa = $datos['cfpd09_metas_actividad']['cod_programa'];
		$subprograma = $datos['cfpd09_metas_actividad']['cod_sub_prog'];
		$proyecto = $datos['cfpd09_metas_actividad']['cod_proyecto'];
		$actividad = $datos['cfpd09_metas_actividad']['cod_activ_obra'];
		$meta = $datos['cfpd09_metas_actividad']['metas'];
		$unidad_medida = $datos['cfpd09_metas_actividad']['unidad_medida'];

		//$codigos = "cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra,";
		//$values =  " '".$var1."',  '".$var2."',    '".$var3."', '".$var4."', '".$var5."', ";
		//$tabla='cfpd02_activ_obra';


		$sql_aux = $condicion2." and cod_dep =".$cd." and cod_sector='".$sector."' and cod_programa = '".$programa."' and  cod_sub_prog='".$subprograma."' and  cod_proyecto='".$proyecto."' and cod_activ_obra='".$actividad."'  ";
		//echo $sql_aux.'<br>';
		if($this->cfpd09_metas_actividad->findCount($sql_aux) == 0){
			//echo 'actividad';
			$sql_1 = "INSERT INTO  cfpd09_metas_actividad VALUES  ($cp, $ce, $ct, $ci, $cd, $ejercicio_aux, $sector,$programa,$subprograma,$proyecto,$actividad,'".$meta."', '".$unidad_medida."')  ";
		//	echo $sql_1;
			$this->$tabla->execute($sql_1);
		}

 	}//fin foreach

    $this->layout = "ajax";
	$sector="";

	$sector = $this->cfpd02_sector->generateList($condicion2, 'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.cod_sector');
   	$this->set('sector', $sector);

    $this->set('errorMessage', 'Fue traspasado el clasificador del ejercicio '.$ejercicio.'  al ejercicio  '.$ejercicio_aux.' ');

   if($sector==""){
   	$this->set('traspaso', 'si');
   }else{
   	$this->set('traspaso', 'no');
   }

}//fin function

function vaciar(){
    $this->layout = "ajax";
}


function solicitud_traspaso(){
    $this->layout = "ajax";
}
function consulta_cprogramatica ($ano=null,$pagina=null) {
	$this->layout="ajax";
	if(isset($pagina) && isset($ano)){
		$Tfilas=$this->cprogramatica->findCount($this->SQLCA($ano));
        if($Tfilas!=0){
        	$data=$this->cprogramatica->findAll($this->SQLCA($ano),null,"cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra,tabla ASC",1,$pagina,null);
            $this->set('DATA',$data);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
            $this->set("pagina",$pagina);
            $this->set("ano",$ano);
        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->index();
	 	       $this->render("index");
        }

	}else{
		$ano=$this->data['cfpp02']['ano'];
		$pagina=1;
		$Tfilas=$this->cprogramatica->findCount($this->SQLCA($ano));
        if($Tfilas!=0){
        	$data=$this->cprogramatica->findAll($this->SQLCA($ano),null,"cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra,tabla ASC",1,$pagina,null);
            $this->set('DATA',$data);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	$this->bt_nav($Tfilas,$pagina);
          	$this->set("ano",$ano);
          	$this->set("pagina",$pagina);
        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->index();
	 	       $this->render("index");
        }
	}
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

function llamame () {
     echo "hola he sido llamada desde otro controller";
}

}//fin class
?>
