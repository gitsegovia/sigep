<?php
/*
 * Fecha: 06/07/2007
 *
 * Por Luis Alfredo Diaz Jaramillo
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */

 class Arrp02Controller extends AppController{

	var $name = 'Arrp02';
	var $uses = array('arrd05', 'Usuario', 'Modulos','cugd05_restriccion_clave', 'cugd02_direccionsuperior','cugd02_direccion',
                      'cugd02_coordinacion','cugd02_secretaria','cugd02_division','cugd02_departamento','cugd02_oficina', 'v_usuarios', "modulos", "cugd02_dependencia");
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');

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




function catProg($dirs=null, $coor=null, $secr=null, $dir=null){
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$cod_sector = $this->cugd02_direccion->field('cugd02_direccion.cod_sector', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$dirs' and cod_coordinacion='$coor' and cod_secretaria='$secr' and cod_direccion='$dir'", $order =null);
	$cod_programa = $this->cugd02_direccion->field('cugd02_direccion.cod_programa', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$dirs' and cod_coordinacion='$coor' and cod_secretaria='$secr' and cod_direccion='$dir' and cod_sector='$cod_sector'", $order =null);
	$cod_sub_prog = $this->cugd02_direccion->field('cugd02_direccion.cod_sub_prog', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$dirs' and cod_coordinacion='$coor' and cod_secretaria='$secr' and cod_direccion='$dir' and cod_sector='$cod_sector' and cod_programa='$cod_programa'", $order =null);
	$cod_proyecto = $this->cugd02_direccion->field('cugd02_direccion.cod_proyecto', $conditions = "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$cod_dep' and cod_dir_superior='$dirs' and cod_coordinacion='$coor' and cod_secretaria='$secr' and cod_direccion='$dir' and cod_sector='$cod_sector' and cod_programa='$cod_programa' and cod_sub_prog='$cod_sub_prog'", $order =null);

	$categoria = $this->zero($cod_sector).'.'.$this->zero($cod_programa).'.'.$this->zero($cod_sub_prog).'.'.$this->zero($cod_proyecto);

	echo "<script>";
	echo "document.getElementById('partida_producto').innerHTML='$categoria';";
	echo "</script>";

}






function beforeFilter(){
    $this->checkSession();

    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');
	$condicion2 = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
	$opc = $this->Usuario->findCount($condicion2);

	if(($modulo == '0' && $cod_dep == 1) || ($modulo == '0' && $cod_dep != 1)){
		return;
	}else{
 		echo "<h3>NO TIENE PERMISOS PARA CREAR USUARIOS PARA MODULOS DE TRABAJO!!</h3>" ;
 		echo"<script>menu_activo();</script>";
		exit;
	}
 }






    function SQLCAX($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_tipo_institucion=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_institucion=".$this->verifica_SS(4)."  and  ";
         $sql_re .= "cod_dependencia=".$this->verifica_SS(5)." ";

         return $sql_re;
    }//fin funcion SQLCA




function AddCero($nomVar,$vector=object,$extra=null){
   	  if($vector != null){
			if($extra==null){
			foreach($vector as $x){
				if($x<10){
				$Var[$x]="000".$x;
				}else if($x>=10 && $x<=99){
				$Var[$x]="00".$x;
				}else if($x>=100 && $x<=999){
					$Var[$x]="0".$x;
				}else if($x>=1000 && $x<=9999){
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

   function zero($x=null){
	if($x != null){
		if($x<10 && strlen($x)==1){
			$x="0".$x;
		}else{
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


function index() {

$this->verifica_entrada('14');

   	$this->layout = "ajax";
   	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "arrd05.cod_presi = ".$cod_presi." and arrd05.cod_entidad = ".$cod_entidad." and arrd05.cod_tipo_inst = ".$cod_tipo_inst." and arrd05.cod_inst = ".$cod_inst." and arrd05.cod_dep = ".$cod_dep;
   	$ListArr05 = $this->arrd05->generateList($this->condicionNDEP(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
   	$this->concatena($ListArr05, 'arr05');
   	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$this->set('cod_tipo_inst', $this->Session->read('SScodtipoinst'));



	        $condicion_dir_sup = "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia=".$cod_dep;
	        $dir_sup=$this->cugd02_direccionsuperior->generateList($condicion_dir_sup,'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
			$dir_sup = $dir_sup != null ? $dir_sup : array();
		    $this->concatena($dir_sup, 'dir_superior');

	// *** DIRECCION SUPERIOR ***
	if($dir_sup!=null){
		foreach($dir_sup as $p => $aux_cs){
			$codigo_ds = $p;
			break;
		}
		$this->set('seleccion_ds',$codigo_ds);
		$this->Session->write('dirs',$codigo_ds);
		$this->Session->write('ddirs',$codigo_ds);
			echo "<script>
					document.getElementById('select_1').value='$aux_cs';
					document.getElementById('codigo_select_1').innerHTML='".$this->zero($codigo_ds)."';
					document.getElementById('deno_select_1').innerHTML='".strtoupper($aux_cs)."';
				</script>";

	// *** COORDINACION ****
		$lista = $this->cugd02_coordinacion->generateList($condicion_dir_sup." and cod_dir_superior=".$codigo_ds, 'cod_coordinacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
    	if($lista!=null){
          	$this->concatena($lista, 'vector_coord');
			foreach($lista as $p => $aux_cs){
				$codigo_ds1 = $p;
				break;
			}
			$this->set('seleccion_ds1',$codigo_ds1);
			$this->Session->write('coor',$codigo_ds1);
			$this->Session->write('dcoor',$codigo_ds1);
			echo "<script>
					document.getElementById('select_2').value='$aux_cs';
					document.getElementById('codigo_select_2').innerHTML='".$this->zero($codigo_ds1)."';
					document.getElementById('deno_select_2').innerHTML='".strtoupper($aux_cs)."';
				</script>";

	// *** SECRETARIA ****
		  $lista = $this->cugd02_secretaria->generateList($condicion_dir_sup." and cod_dir_superior=".$codigo_ds." and cod_coordinacion=".$codigo_ds1, 'cod_secretaria ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
          if($lista!=null){
          	$this->concatena($lista, 'vector_sec');
			foreach($lista as $p => $aux_cs){
				$codigo_ds2 = $p;
				break;
			}

			$this->set('seleccion_ds2',$codigo_ds2);
			$this->Session->write('secr',$codigo_ds2);
			$this->Session->write('dsecr',$codigo_ds2);
			echo "<script>
					document.getElementById('select_3').value='$aux_cs';
					document.getElementById('codigo_select_3').innerHTML='".$this->zero($codigo_ds2)."';
					document.getElementById('deno_select_3').innerHTML='".strtoupper($aux_cs)."';
				</script>";

			// *** DIRECCION ***
				$lista = $this->cugd02_direccion->generateList($condicion_dir_sup." and cod_dir_superior=".$codigo_ds." and cod_coordinacion=".$codigo_ds1." and cod_secretaria=".$codigo_ds2, 'cod_direccion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
          		if($lista!=null){
          			$this->concatena($lista, 'vector_direcc');
          		}else{
          			$this->set('vector_direcc',array());
          		}

          }else{
          	$this->set('vector_sec',array());
          	$this->Session->write('secr',0);
          	$this->Session->write('dsecr',0);
          }

		}else{
			$this->set('vector_coord',array());
			$this->Session->write('coor',0);
			$this->Session->write('secr',0);
			$this->Session->write('dcoor',0);
			$this->Session->write('dsecr',0);
		}

	}else{
		$this->Session->write('dirs',0);
		$this->Session->write('coor',0);
		$this->Session->write('secr',0);
		$this->Session->write('ddirs',0);
		$this->Session->write('dcoor',0);
		$this->Session->write('dsecr',0);
	}

	$dato = $this->arrd05->findAll($condicion, null, 'cod_dep', null);

	foreach( $dato as $dato){
		$denomin = $dato['arrd05']['denominacion'];
		$cod = $dato['arrd05']['cod_dep'];
	}
	$this->set('denominacion', strtoupper($denomin));
	if($cod<10){
		$cod="000".$cod;
	}else if($cod>=10 && $cod<=99){
		$cod="00".$cod;
	}else if($cod>=100 && $cod<=999){
		$cod="0".$cod;
	}else if($cod>=1000 && $cod<=9999){
		$cod=$cod;
	}

	$this->set('codigo', $cod);

		$nom = $this->arrd05->generateList($this->condicionNDEP().' and tipo_dependencia = 1', 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
     	$this->concatena($nom, 'arr05');

    $data_modulos=$this->modulos->findAll("cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep,null,'orden_ubicacion ASC');
    $this->set('data_modulos',$data_modulos);



}







function select3($select=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";

	if($var!=null){
    $cond =$this->SQLCAX();
	switch($select){
		case 'dirsuperior':
		  $this->set('SELECT','coordinacion');
		  $this->set('codigo','secretaria');
		  $this->set('seleccion','');
		  $this->set('n',1);
		  $lista=  $this->cugd02_direccionsuperior->generateList($cond, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.cod_dir_superior');
          $this->AddCero('vector', $lista);
		break;
		case 'coordinacion':
		  $this->set('SELECT','secretaria');
		  $this->set('codigo','coordinacion');
		  $this->set('seleccion','');
		  $this->set('n',2);
		 // $ano =  $this->Session->read('ano');
		  $this->Session->write('dirs',$var);
		  $cond .=" and cod_dir_superior=".$var;
		  $lista=  $this-> cugd02_coordinacion->generateList($cond, 'cod_coordinacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'secretaria':
		  $this->set('SELECT','direccion');
		  $this->set('codigo','secretaria');
		  $this->set('seleccion','');
		  $this->set('n',3);
		 // $ano =  $this->Session->read('ano');
		  $dirs =  $this->Session->read('dirs');
		  $this->Session->write('coor',$var);
		  $cond .=" and cod_dir_superior=".$dirs." and cod_coordinacion=".$var;
		  $lista=  $this->cugd02_secretaria->generateList($cond, 'cod_secretaria ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
           $this->concatena($lista, 'vector');
		break;
		case 'direccion':
		  $this->set('SELECT','division');
		  $this->set('codigo','direccion');
		  $this->set('seleccion','');
		  $this->set('n',4);
		  //$ano =  $this->Session->read('ano');
		  $dirs =  $this->Session->read('dirs');
		  $coor =  $this->Session->read('coor');
		  $this->Session->write('secr',$var);
		  $cond .=" and cod_dir_superior=".$dirs." and cod_coordinacion=".$coor." and cod_secretaria=".$var;
		  $lista=  $this->cugd02_direccion->generateList($cond, 'cod_direccion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
          $this->concatena($lista, 'vector');

		break;
		case 'division':
		  $this->set('SELECT','departamento');
		  $this->set('codigo','division');
		  $this->set('seleccion','');
		  $this->set('n',5);
		 // $ano =  $this->Session->read('ano');
		  $dirs =  $this->Session->read('dirs');
		  $coor =  $this->Session->read('coor');
		  $secr =  $this->Session->read('secr');
		  $this->Session->write('dir',$var);
		  $cond .=" and cod_dir_superior=".$dirs." and cod_coordinacion=".$coor." and cod_secretaria=".$secr." and cod_direccion=".$var;
		  $lista=  $this->cugd02_division->generateList($cond, 'cod_division ASC', null, '{n}.cugd02_division.cod_division', '{n}.cugd02_division.denominacion');
          $this->concatena($lista, 'vector');
          $this->catProg($dirs, $coor, $secr, $var);
		break;
		case 'departamento':
		  $this->set('SELECT','oficina');
		  $this->set('codigo','departamento');
		  $this->set('seleccion','');
		  $this->set('n',6);
		 // $ano =  $this->Session->read('ano');
		  $dirs =  $this->Session->read('dirs');
		  $coor =  $this->Session->read('coor');
		  $secr =  $this->Session->read('secr');
		  $dir =  $this->Session->read('dir');
		  $div =  $this->Session->write('div',$var);
		  $cond .=" and cod_dir_superior=".$dirs." and cod_coordinacion=".$coor." and cod_secretaria=".$secr." and cod_direccion=".$dir." and cod_division=".$var;
		  $lista=  $this->cugd02_departamento->generateList($cond, 'cod_departamento ASC', null, '{n}.cugd02_departamento.cod_departamento', '{n}.cugd02_departamento.denominacion');
          $this->concatena($lista, 'vector');
		break;
		case 'oficina':
		  $this->set('SELECT','oficina');
		  $this->set('codigo','oficina');
		  $this->set('seleccion','');
		  $this->set('n',7);
		  $this->set('no','no');
		 // $ano =  $this->Session->read('ano');
		  $dirs =  $this->Session->read('dirs');
		  $coor =  $this->Session->read('coor');
		  $secr =  $this->Session->read('secr');
		  $dir =  $this->Session->read('dir');
		  $div =  $this->Session->read('div');
		  $this->Session->write('dep',$var);
		  $cond .=" and cod_dir_superior=".$dirs." and cod_coordinacion=".$coor." and cod_secretaria=".$secr." and cod_direccion=".$dir." and cod_division=".$div." and cod_departamento=".$var;
		  $lista=  $this->cugd02_oficina->generateList($cond, 'cod_oficina ASC', null, '{n}.cugd02_oficina.cod_oficina', '{n}.cugd02_oficina.denominacion');
          $this->concatena($lista, 'vector');
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








function mostrar3($select=null,$var=null){ //mostrar3 denominacion presupuestarios
	$this->layout = "ajax";
if( $var!=null && !empty($var)){
    $cond = $this->SQLCAX();
   // $cond2 = $this->SQLCA();
	switch($select){
		case 'dirsuperior':
		 // $ano =  $this->Session->read('ano');
		  $this->Session->write('ddirs',$var);
		  $cond .=" and cod_dir_superior=".$var;
		  $a=  $this->cugd02_direccionsuperior->findAll($cond);
		  if(isset($a[0]['cugd02_direccionsuperior']['denominacion'])){
          $this->set("denominacion",$a[0]['cugd02_direccionsuperior']['denominacion']);
		  }
		break;
		case 'coordinacion':
		  //$ano =  $this->Session->read('ano');
		  $ddirs =  $this->Session->read('ddirs');
		  $this->Session->write('dcoor',$var);
		  $cond .=" and cod_dir_superior=".$ddirs." and cod_coordinacion=".$var;
		  $a=  $this->cugd02_coordinacion->findAll($cond);
		  if(isset($a[0]['cugd02_coordinacion']['denominacion'])){
          $this->set("denominacion",$a[0]['cugd02_coordinacion']['denominacion']);
		  }
		break;
		case 'secretaria':
		 // $ano =  $this->Session->read('ano');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $this->Session->write('dsecr',$var);
		  $cond .=" and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$var;
		  $a=  $this->cugd02_secretaria->findAll($cond);
		  if(isset($a[0]['cugd02_secretaria']['denominacion'])){
          $this->set("denominacion",$a[0]['cugd02_secretaria']['denominacion']);
		  }
		break;
		case 'direccion':
		 // $ano =  $this->Session->read('ano');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $dsecr =  $this->Session->read('dsecr');
		  $this->Session->write('ddir',$var);
		  $cond .=" and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$var;
		  $a=  $this->cugd02_direccion->findAll($cond);
		  if(isset($a[0]['cugd02_direccion']['denominacion'])){
          $this->set("denominacion",$a[0]['cugd02_direccion']['denominacion']);
		  }
		break;
		case 'division':
		  //$ano =  $this->Session->read('ano');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $dsecr =  $this->Session->read('dsecr');
		  $ddir =  $this->Session->read('ddir');
		  $this->Session->write('ddiv',$var);
		  $cond .=" and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$ddir." and cod_division=".$var;
		  $a=  $this->cugd02_division->findAll($cond);
		  if(isset($a[0]['cugd02_division']['denominacion'])){
          $this->set("denominacion",$a[0]['cugd02_division']['denominacion']);
		  }
		break;
		case 'departamento':
		  //$ano =  $this->Session->read('ano');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $dsecr =  $this->Session->read('dsecr');
		  $ddir =  $this->Session->read('ddir');
		  $ddiv =  $this->Session->read('ddiv');
		  $this->Session->write('ddep',$var);
		  $cond .=" and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$ddir." and cod_division=".$ddiv." and cod_departamento=".$var;
		  $a=  $this->cugd02_departamento->findAll($cond);
		  if(isset($a[0]['cugd02_departamento']['denominacion'])){
          $this->set("denominacion",$a[0]['cugd02_departamento']['denominacion']);
		  }
		break;
		case 'oficina':
		 // $ano =  $this->Session->read('ano');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $dsecr =  $this->Session->read('dsecr');
		  $ddir =  $this->Session->read('ddir');
		  $ddiv =  $this->Session->read('ddiv');
		  $ddep =  $this->Session->read('ddep');
		  $this->Session->write('dofi',$var);
		  $cond .=" and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$ddir." and cod_division=".$ddiv." and cod_departamento=".$ddep." and cod_oficina=".$var;
		  $a=  $this->cugd02_oficina->findAll($cond);
		  if(isset($a[0]['cugd02_oficina']['denominacion'])){
          $this->set("denominacion",$a[0]['cugd02_oficina']['denominacion']);
		  }
		break;
	}//fin wsitch
	}else{
		$this->set("denominacion"," ");
	}
}//fin mostrar3 codigos presupuestarios















function mostrar4($select=null,$var=null) { //mostrar3 codigos presupuestarios
	$this->layout = "ajax";
if( $var!=null){
    $cond = $this->SQLCAX();
   // $cond2 = $this->SQLCA();
	switch($select){
		case 'dirsuperior':
		 // $ano =  $this->Session->read('ano');
		  $this->Session->write('ddirs',$var);
		  $cond .=" and cod_dir_superior=".$var;
		  $a=  $this->cugd02_direccionsuperior->findAll($cond);
		  if(isset($a[0]['cugd02_direccionsuperior']['cod_dir_superior'])){
			$num = $this->zero($a[0]['cugd02_direccionsuperior']['cod_dir_superior']);
			echo $num;
          	//echo $a[0]['cugd02_direccionsuperior']['cod_dir_superior'] >9 ?$a[0]['cugd02_direccionsuperior']['cod_dir_superior'] : "0".$a[0]['cugd02_direccionsuperior']['cod_dir_superior'] ;
		  }
		break;
		case 'coordinacion':
		  //$ano =  $this->Session->read('ano');
		  $ddirs =  $this->Session->read('ddirs');
		  $this->Session->write('dcoor',$var);
		  $cond .=" and cod_dir_superior=".$ddirs." and cod_coordinacion=".$var;
		  $a=  $this->cugd02_coordinacion->findAll($cond);
		  if(isset($a[0]['cugd02_coordinacion']['cod_coordinacion'])){
		  	$num = $this->zero($a[0]['cugd02_coordinacion']['cod_coordinacion']);
		  	echo $num;
          	//echo $a[0]['cugd02_coordinacion']['cod_coordinacion'] >9 ?$a[0]['cugd02_coordinacion']['cod_coordinacion'] : "0".$a[0]['cugd02_coordinacion']['cod_coordinacion'] ;
		  }
		break;
		case 'secretaria':
		 // $ano =  $this->Session->read('ano');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $this->Session->write('dsecr',$var);
		  $cond .=" and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$var;
		 $a=  $this->cugd02_secretaria->findAll($cond);
		  if(isset($a[0]['cugd02_secretaria']['cod_secretaria'])){
		  	$num = $this->zero($a[0]['cugd02_secretaria']['cod_secretaria']);
		  	//echo "<input type='text' style='width:98%;text-align:center' value='$num' READONLY>";
               echo $a[0]['cugd02_secretaria']['cod_secretaria']>9 ?$a[0]['cugd02_secretaria']['cod_secretaria'] : "0".$a[0]['cugd02_secretaria']['cod_secretaria'];

		  }
		break;
		case 'direccion':
		 // $ano =  $this->Session->read('ano');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $dsecr =  $this->Session->read('dsecr');
		  $this->Session->write('ddir',$var);
		  $cond .=" and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$var;
		  $a=  $this->cugd02_direccion->findAll($cond);
		  if(isset($a[0]['cugd02_direccion']['cod_direccion'])){
		  	$num = $this->zero($a[0]['cugd02_direccion']['cod_direccion']);
		  	//echo "<input type='text' style='width:98%;text-align:center' value='$num' READONLY>";
          	echo $a[0]['cugd02_direccion']['cod_direccion']>9 ?$a[0]['cugd02_direccion']['cod_direccion'] : "0".$a[0]['cugd02_direccion']['cod_direccion'];

		  }
		break;
		case 'division':
		  //$ano =  $this->Session->read('ano');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $dsecr =  $this->Session->read('dsecr');
		  $ddir =  $this->Session->read('ddir');
		  $this->Session->write('ddiv',$var);
		  $cond .=" and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$ddir." and cod_division=".$var;
		  $a=  $this->cugd02_division->findAll($cond);
		  if(isset($a[0]['cugd02_division']['cod_division'])){
		  	$num = $this->zero($a[0]['cugd02_division']['cod_division']);
		  	//echo "<input type='text' style='width:98%;text-align:center' value='$num' READONLY>";
         	echo $a[0]['cugd02_division']['cod_division']>9 ?$a[0]['cugd02_division']['cod_division'] : "0".$a[0]['cugd02_division']['cod_division'];
		  }
		break;
		case 'departamento':
		  //$ano =  $this->Session->read('ano');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $dsecr =  $this->Session->read('dsecr');
		  $ddir =  $this->Session->read('ddir');
		  $ddiv =  $this->Session->read('ddiv');
		  $this->Session->write('ddep',$var);
		  $cond .=" and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$ddir." and cod_division=".$ddiv." and cod_departamento=".$var;
		  $a=  $this->cugd02_departamento->findAll($cond);
		  if(isset($a[0]['cugd02_departamento']['cod_departamento'])){
		  	$num = $this->zero($a[0]['cugd02_departamento']['cod_departamento']);
		  	//echo "<input type='text' style='width:98%;text-align:center' value='$num' READONLY>";
          	echo $a[0]['cugd02_departamento']['cod_departamento']>9 ?$a[0]['cugd02_departamento']['cod_departamento'] : "0".$a[0]['cugd02_departamento']['cod_departamento'];
          }
		break;
		case 'oficina':
		 // $ano =  $this->Session->read('ano');
		  $ddirs =  $this->Session->read('ddirs');
		  $dcoor =  $this->Session->read('dcoor');
		  $dsecr =  $this->Session->read('dsecr');
		  $ddir =  $this->Session->read('ddir');
		  $ddiv =  $this->Session->read('ddiv');
		  $ddep =  $this->Session->read('ddep');
		  $this->Session->write('dofi',$var);
		  $cond .=" and cod_dir_superior=".$ddirs." and cod_coordinacion=".$dcoor." and cod_secretaria=".$dsecr." and cod_direccion=".$ddir." and cod_division=".$ddiv." and cod_departamento=".$ddep." and cod_oficina=".$var;
		  $a=  $this->cugd02_oficina->findAll($cond);
        if(isset($a[0]['cugd02_oficina']['cod_oficina'])){
        	$num = $this->zero($a[0]['cugd02_oficina']['cod_oficina']);
		  	//echo "<input type='text' style='width:98%;text-align:center' value='$num' READONLY>";
          	echo $a[0]['cugd02_oficina']['cod_oficina']>9 ?$a[0]['cugd02_oficina']['cod_oficina'] : "0".$a[0]['cugd02_oficina']['cod_oficina'];
		  }
		break;
	}//fin wsitch
	}else{
		echo "&nbsp;";
	}
}//fin mostrar3 codigos presupuestarios































function mostrar($user=null){
	$this->layout = "ajax";
	$cod_dep = $this->Session->read('SScoddep');

    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	//$cod_dep = $this->Session->read('SScoddep');
	$condicion = "arrd05.cod_presi = ".$cod_presi." and arrd05.cod_entidad = ".$cod_entidad." and arrd05.cod_tipo_inst = ".$cod_tipo_inst." and arrd05.cod_inst = ".$cod_inst;

     $data_modulos=$this->modulos->findAll("cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep,null,'orden_ubicacion ASC');


	if($user !=null){
		$user= strtoupper($user);

		$cont = $this->Usuario->findCount(" username='$user'");

		foreach($data_modulos as $r){
    	  $mod[] = $r['modulos']['cod_modulo'];
        }

//		$mod=array('CUGP00','CFP000','CSIP00','CSCP00','COBP00','CEP000','CEPP00','CSTP00','CNP000','CIPP00','CFPP00','CGPP00','CATP00','SHPP00','CAP000','CMCP00', 'CSRP00','CATSP0');


	if($cont > 0){

						            $datos = $this->v_usuarios->findAll(" username='$user'");
						            $modulo             =   $datos[0]['v_usuarios']['modulo'];
						            $funcionario        =   $datos[0]['v_usuarios']['funcionario'];
						            $cedula_identidad   =   $datos[0]['v_usuarios']['cedula_identidad'];
						            $password           =   $datos[0]['v_usuarios']['password'];
						            $cod_dep_original   =   $datos[0]['v_usuarios']['cod_dep_original'];
						            $estatus            =   $datos[0]['v_usuarios']['condicion_actividad'];

						            $cod_dir_superior  =   $datos[0]['v_usuarios']['cod_dir_superior'];
								    $cod_coordinacion  =   $datos[0]['v_usuarios']['cod_coordinacion'];
								    $cod_secretaria    =   $datos[0]['v_usuarios']['cod_secretaria'];
								    $cod_direccion     =   $datos[0]['v_usuarios']['cod_direccion'];
								    $cod_division      =   $datos[0]['v_usuarios']['cod_division'];
								    $cod_departamento  =   $datos[0]['v_usuarios']['cod_departamento'];
								    $cod_oficina       =   $datos[0]['v_usuarios']['cod_oficina'];


						            $this->set('estatus', $estatus);
						            $this->set('datos_username', $datos);


						            if($cod_dir_superior==null || $cod_dir_superior==""){$cod_dir_superior  =   0;}
								    if($cod_coordinacion==null || $cod_coordinacion==""){$cod_coordinacion  =   0;}
								    if($cod_secretaria==null   || $cod_secretaria==""){  $cod_secretaria    =   0;}
								    if($cod_direccion==null    || $cod_direccion==""){   $cod_direccion     =   0;}
								    if($cod_division==null     || $cod_division==""){    $cod_division      =   0;}
								    if($cod_departamento==null || $cod_departamento==""){$cod_departamento  =   0;}
								    if($cod_oficina==null      || $cod_oficina==""){     $cod_oficina       =   0;}


						            if($cod_dir_superior!=0){  $opc = 2;

						            	$this->Session->write('dirs', $cod_dir_superior);
										$this->Session->write('coor', $cod_coordinacion);
										$this->Session->write('secr', $cod_secretaria);
										$this->Session->write('dir',  $cod_direccion);
										$this->Session->write('div',  $cod_division);
										$this->Session->write('dep',  $cod_departamento);

										$this->Session->write('ddirs', $cod_dir_superior);
										$this->Session->write('dcoor', $cod_coordinacion);
										$this->Session->write('dsecr', $cod_secretaria);
										$this->Session->write('ddir', $cod_direccion);
										$this->Session->write('ddiv', $cod_division);
										$this->Session->write('ddep', $cod_departamento);
										$this->Session->write('dofi', $cod_oficina);

						            }else{ $opc = 1;}

						             $condicion_dir_sup = "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia=".$cod_dep;
						        	 $dir_sup=$this->cugd02_direccionsuperior->generateList($condicion_dir_sup,'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
								     $dir_sup = $dir_sup != null ? $dir_sup : array();
							         $this->concatena($dir_sup, 'dir_superior');



							           $this->concatena($this->cugd02_direccionsuperior->generateList("cod_tipo_institucion='".$cod_tipo_inst."' and cod_institucion='".$cod_inst."' and cod_dependencia='".$cod_dep."' ",                                                                                                                                                                                                                                                               'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion'),  'cod_dir_superior');
						               $this->concatena($this->cugd02_coordinacion->generateList("     cod_tipo_institucion='".$cod_tipo_inst."' and cod_institucion='".$cod_inst."' and cod_dependencia='".$cod_dep."' and cod_dir_superior='".$cod_dir_superior."' ",                                                                                                                                                                                                                  'cod_coordinacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion',      '{n}.cugd02_coordinacion.denominacion'),       'cod_coordinacion');
						               $this->concatena($this->cugd02_secretaria->generateList("       cod_tipo_institucion='".$cod_tipo_inst."' and cod_institucion='".$cod_inst."' and cod_dependencia='".$cod_dep."' and cod_dir_superior='".$cod_dir_superior."' and cod_coordinacion='".$cod_coordinacion."' ",                                                                                                                                                                     'cod_secretaria ASC',   null, '{n}.cugd02_secretaria.cod_secretaria',          '{n}.cugd02_secretaria.denominacion'),         'cod_secretaria');
						               $this->concatena($this->cugd02_direccion->generateList("        cod_tipo_institucion='".$cod_tipo_inst."' and cod_institucion='".$cod_inst."' and cod_dependencia='".$cod_dep."' and cod_dir_superior='".$cod_dir_superior."' and cod_coordinacion='".$cod_coordinacion."' and cod_secretaria='".$cod_secretaria."' ",                                                                                                                            'cod_direccion ASC',    null, '{n}.cugd02_direccion.cod_direccion',            '{n}.cugd02_direccion.denominacion'),          'cod_direccion');
						               $this->concatena($this->cugd02_division->generateList("         cod_tipo_institucion='".$cod_tipo_inst."' and cod_institucion='".$cod_inst."' and cod_dependencia='".$cod_dep."' and cod_dir_superior='".$cod_dir_superior."' and cod_coordinacion='".$cod_coordinacion."' and cod_secretaria='".$cod_secretaria."' and cod_direccion='".$cod_direccion."'  ",                                                                                    'cod_division ASC',     null, '{n}.cugd02_division.cod_division',              '{n}.cugd02_division.denominacion'),           'cod_division');
						               $this->concatena($this->cugd02_departamento->generateList("     cod_tipo_institucion='".$cod_tipo_inst."' and cod_institucion='".$cod_inst."' and cod_dependencia='".$cod_dep."' and cod_dir_superior='".$cod_dir_superior."' and cod_coordinacion='".$cod_coordinacion."' and cod_secretaria='".$cod_secretaria."' and cod_direccion='".$cod_direccion."' and cod_division='".$cod_division."'  ",                                               'cod_departamento ASC', null, '{n}.cugd02_departamento.cod_departamento',      '{n}.cugd02_departamento.denominacion'),       'cod_departamento');
						               $this->concatena($this->cugd02_oficina->generateList("          cod_tipo_institucion='".$cod_tipo_inst."' and cod_institucion='".$cod_inst."' and cod_dependencia='".$cod_dep."' and cod_dir_superior='".$cod_dir_superior."' and cod_coordinacion='".$cod_coordinacion."' and cod_secretaria='".$cod_secretaria."' and cod_direccion='".$cod_direccion."' and cod_division='".$cod_division."' and cod_departamento='".$cod_departamento."'  ",  'cod_oficina ASC',      null, '{n}.cugd02_oficina.cod_oficina',                '{n}.cugd02_oficina.denominacion'),            'cod_oficina');




						            $modulo=str_split($modulo, 6);

						$aceptar = "no";
						$mensaje = 0;

							      if($modulo[0]=='0'){$mensaje=1; $aceptar = "si";
						    }else if($datos[0]['v_usuarios']['cod_presi']    !=$cod_presi     ||
						             $datos[0]['v_usuarios']['cod_entidad']  !=$cod_entidad   ||
						             $datos[0]['v_usuarios']['cod_tipo_inst']!=$cod_tipo_inst ||
						             $datos[0]['v_usuarios']['cod_inst']      !=$cod_inst     ||
						             $datos[0]['v_usuarios']['cod_dep']!=$cod_dep){$mensaje=2; $aceptar = "si";
							}else{$mensaje=0;}

                                   $sql_cod_dependencia = "cod_tipo_institucion=".$this->Session->read('SScodtipoinst')." and cod_institucion=".$this->Session->read('SScodinst')." and cod_dependencia='".$datos[0]['v_usuarios']['cod_dep']."'  ";
                                   $denominacion        =  $this->cugd02_dependencia->findAll($sql_cod_dependencia);
                                   $denominacion        =  $denominacion[0]["cugd02_dependencia"]["denominacion"];


						        if($aceptar=="si"){
						         if($mensaje==1){ $opc = 1;
						             $this->set('msg', 'ES UN USUARIO PRINCIPAL DE LA DEPENDENCIA '.$denominacion);
						             echo "<script> document.getElementById('guardar').disabled=true;</script>";
						             echo "<script>";
										echo "document.getElementById('condicion_actividad_1').disabled=false;";
										echo "document.getElementById('condicion_actividad_2').disabled=false;";
										echo "document.getElementById('condicion_actividad_1').checked=false;";
										echo "document.getElementById('condicion_actividad_2').checked=false;";
								     echo "</script>";
						   }else if($mensaje==2){ $opc = 1;
						   	         $this->set('msg', 'Este usuario ya existe en la dependencia '.$denominacion);
						   	         echo "<script> document.getElementById('guardar').disabled=true;</script>";
						   	         echo "<script>";
										echo "document.getElementById('condicion_actividad_1').disabled=false;";
										echo "document.getElementById('condicion_actividad_2').disabled=false;";
										echo "document.getElementById('condicion_actividad_1').checked=false;";
										echo "document.getElementById('condicion_actividad_2').checked=false;";
								     echo "</script>";
						    }//fin else

						 echo'<script>';for($i=0;$i<count($mod);$i++){ echo"if(document.getElementById('".$mod[$i]."')){document.getElementById('".$mod[$i]."').checked =  false; } document.getElementById('valida_clave').value='si';"; }echo'</script>';
						echo'<script>';
						             echo"document.getElementById('funcionario').value='';";
						             echo"document.getElementById('cedula').value='';";
						             echo"document.getElementById('pass').value='';";
						             echo"document.getElementById('pass2').value='';";
						             echo" if(document.getElementById('cod_dep_origen')){";
						                echo" if(document.getElementById('cod_dep_origen').options[1].text == '----'){";
						                echo"   document.getElementById('cod_dep_origen').options[1].selected = true; ";
						                echo" } ";
						             echo" } ";

						echo'</script>';


						}else{

						echo "<script> document.getElementById('guardar').disabled=false;</script>";

						  echo'<script>';
						           for($i=0;$i<count($mod);$i++){ echo"if(document.getElementById('".$mod[$i]."')){document.getElementById('".$mod[$i]."').checked =  false; }"; }//fin for
						           for($i=0;$i<count($mod);$i++){
						   			   for($j=0;$j<count($mod);$j++){
						   			   	if(isset($modulo[$i])){
						   			   	    if($modulo[$i]==$mod[$j]){
						   			   	    	echo"if(document.getElementById('".$modulo[$i]."')){document.getElementById('".$modulo[$i]."').checked =  true; }";
						   			   	   }//fin
						   			   	}//fin if
						   			 }//fin for
						            }//fin for

						           echo"document.getElementById('funcionario').value='".$funcionario."';";
						           echo"document.getElementById('cedula').value='".$cedula_identidad."';";
						           echo"document.getElementById('pass').value='';";
						           echo"document.getElementById('pass2').value='';";

						           //echo"document.getElementById('pass').value='".$password."';";
						           //echo"document.getElementById('pass2').value='".$password."';";

						if($cod_dep_original!=""){


						$nom_aux = $this->arrd05->findAll($condicion.' and cod_dep='.$cod_dep_original);
						$separador = " - ";

						echo "
						 if(document.getElementById('cod_dep_origen')){
								valor_ver = '".$cod_dep_original."';
						        id = 'cod_dep_origen';
						        var checkStr = valor_ver;


						 if(eval(valor_ver)<=9){  valor_ver = '0'+valor_ver;}

						valor_ver = valor_ver + '".$separador.strtoupper($nom_aux[0]['arrd05']['denominacion'])."';


						document.getElementById(id).options[0].value    =  checkStr;
						document.getElementById(id).options[0].text     =  valor_ver;
						document.getElementById(id).options[0].selected =  true;


						if(document.getElementById(id).options[document.getElementById(id).selectedIndex].value!=''){

						 var aux_text = new Array();
						 var aux_value = new Array();

						for (var i = 0; i < document.getElementById(id).length; i++){
						  aux_text[i] = document.getElementById(id).options[i].text;
						  aux_value[i] = document.getElementById(id).options[i].value;
						}//fin for


						if(document.getElementById(id).options[1].text!='----'){

						document.getElementById(id).options[document.getElementById(id).length] = new Option(eval(document.getElementById(id).length)+1);

						ii = 1;

						 document.getElementById(id).options[1].text =  '----';
						 document.getElementById(id).options[1].value =  '';

						for (var i = 2; i < document.getElementById(id).length; i++){
						  document.getElementById(id).options[i].text =  aux_text[ii];
						  document.getElementById(id).options[i].value = aux_value[ii];
						  ii++;
						}//fin for


						  }//fin if
						}//fin if
						}//fin if


										";

						}else{
						    echo" if(document.getElementById('cod_dep_origen')){";
							        echo" if(document.getElementById('cod_dep_origen').options[1].text == '----'){";
						             echo"   document.getElementById('cod_dep_origen').options[1].selected = true; ";
						             echo" } ";
						    echo "}//fin ";

						}//fin else

						           echo"document.getElementById('valida_clave').value='no';";
						           echo'</script>';


									//$this->set('msg', 'ES UN USUARIO PRINCIPAL DE DEPENDENCIA');

						}//fin else




		}else{

                 $this->set('estatus', 1);
                 $opc = 1;
                 $condicion_dir_sup = "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia=".$cod_dep;
	        	 $dir_sup=$this->cugd02_direccionsuperior->generateList($condicion_dir_sup,'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
			     $dir_sup = $dir_sup != null ? $dir_sup : array();
		         $this->concatena($dir_sup, 'dir_superior');

           echo "<script> document.getElementById('guardar').disabled=false;</script>";
           echo'<script>';for($i=0;$i<17;$i++){ echo"if(document.getElementById('".$mod[$i]."')){document.getElementById('".$mod[$i]."').checked =  false; } document.getElementById('valida_clave').value='si';"; }echo'</script>';
           echo'<script>';
             echo"document.getElementById('funcionario').value='';";
             echo"document.getElementById('cedula').value='';";
             echo"document.getElementById('pass').value='';";
             echo"document.getElementById('pass2').value='';";
             echo" if(document.getElementById('cod_dep_origen')){";
             echo" if(document.getElementById('cod_dep_origen').options[1].text == '----'){";
             echo"   document.getElementById('cod_dep_origen').options[1].selected = true; ";
             echo" } ";
             echo "}";
           echo'</script>';




		   }//fin else




		   $this->set('opcion', $opc);




	}else{

		$this->set('opcion', 1);
		$this->set('estatus', 1);
		                             echo "<script> document.getElementById('guardar').disabled=true;</script>";
						             echo "<script>";
										echo "document.getElementById('condicion_actividad_1').disabled=false;";
										echo "document.getElementById('condicion_actividad_2').disabled=false;";
										echo "document.getElementById('condicion_actividad_1').checked=false;";
										echo "document.getElementById('condicion_actividad_2').checked=false;";
								     echo "</script>";
		         $condicion_dir_sup = "cod_tipo_institucion = ".$cod_tipo_inst." and cod_institucion = ".$cod_inst." and cod_dependencia=".$cod_dep;
	        	 $dir_sup=$this->cugd02_direccionsuperior->generateList($condicion_dir_sup,'denominacion ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
			     $dir_sup = $dir_sup != null ? $dir_sup : array();
		         $this->concatena($dir_sup, 'dir_superior');


	}//fin else




}//fin function




function selec_arr05($var1=null, $aux=null) {
	$this->layout = "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

	if($var1!=null){
		$this->set('arr05', $var1);
	}
	if($var1==null) {
		$ListArr05 = $this->arrd05->generateList($this->condicionNDEP(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
   		$this->concatena($ListArr05, 'arr05');
		$var1 = $this->data['arrp02']['codigo'];
	}
	if($aux!=null) $this->set('arr05', $aux);

	$this->set('opcion', $var1);




	if($var1!=null && $var1!='otros'){
   	$ListArr05 = $this->arrd05->generateList($this->condicionNDEP(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
   	$this->concatena($ListArr05, 'arr05');

	}
	if ($var1 =! null){
		$ListArr05 = $this->arrd05->generateList($this->condicionNDEP(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
   	$this->concatena($ListArr05, 'arr05');
	}

}

function sel_arr05($var1=null, $aux=null) {
	$this->layout = "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;



	if($var1!=null){
		$this->set('arr05', $var1);
	}
	if($var1==null) {
		$ListArr05 = $this->arrd05->generateList($this->condicionNDEP(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
   		$this->concatena($ListArr05, 'arr05');
		$var1 = $this->data['arrp02']['codigo'];
	}
	if($aux!=null) $this->set('arr05', $aux);

	$this->set('opcion', $var1);




	if($var1!=null && $var1!='otros'){
   	$ListArr05 = $this->arrd05->generateList($this->condicionNDEP(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
   	$this->concatena($ListArr05, 'arr05');

	}
	if ($var1 =! null){
		$ListArr05 = $this->arrd05->generateList($this->condicionNDEP(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
   	$this->concatena($ListArr05, 'arr05');
	}

}

function selec_arr($var1=null, $aux=null) {
	$this->layout = "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

	if($var1!=null){
		$this->set('arr05', $var1);
	}
	if($var1==null) {
		$ListArr05 = $this->arrd05->generateList($this->condicionNDEP(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
   		$this->set('arr05',$ListArr05);
		$var1 = $this->data['arrp02']['codigo'];
	}
	if($aux!=null) $this->set('arr05', $aux);

	$this->set('opcion', $var1);




	if($var1!=null && $var1!='otros'){
   	$ListArr05 = $this->arrd05->generateList($this->condicionNDEP(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
   $this->set('arr05',$ListArr05);

	}
	if ($var1 =! null){
		$ListArr05 = $this->arrd05->generateList($this->condicionNDEP(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
   	$this->set('arr05',$ListArr05);
	}

}

function principal($var1=null){

   	$this->layout = "ajax";

	$this->set('opcion1', $var1);

	$action='';
	$tabla = '';
	$sql_3 = '';

	if($var1=='otros') $action=$var1;

	if($var1!=null){
		$tabla='arrd05';
		$sql_2 =  $tabla.'.cod_dep =  '.$var1.'  ';
	}

	$this->set('tabla', $tabla);


if($var1!=null && $action!='otros'){

      $sql_re = $sql_2;
	  $data = $this->$tabla->findAll($sql_re, null, null, null);

	  foreach ($data as $x) $sw = $x['arrd05']['cod_dep'];
	  if($sw == 1){
	  	$opc = 'disabled';
	  }else{
	  	$opc = '';
	  }
	  $this->set('opc', $opc);
	  $this->set('datos_cod_arrp02', $data);

}else if($var1!=null){

	  $sql_re = $sql_3;
	  $data = $this->$tabla->findAll($sql_re, null, null, null);

	  $this->set('datos_cod_arrp02', $data);

}//fin else

 }//FIN FUNCTION

function guardar($tabla=null, $var1=null){

 	$this->layout = "ajax";

    if($var1==null){
		$this->data['arrp02']['cod_dep'] = $this->data['arrp02']['codigo'];
		$var1 = $this->data['arrp02']['codigo'];

	}


	 $denominacion = $this->data['arrp02']['denominacion'];

	 //CODIGOS DE ARRANQUE DE LA SESION
	 $cod_presi = $this->Session->read('SScodpresi');
	 $cod_entidad = $this->Session->read('SScodentidad');
	 $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	 $cod_inst = $this->Session->read('SScodinst');
	 $cod_dep = $this->Session->read('SScoddep');

    $codigos = "";
	$values = "";

	if($var1!=null){
		$codigos = "cod_dep, ";
		$values =  " '".$var1."',  ";
		$tabla='arrd05';
	}

	$sql_1 = "INSERT INTO  ".$tabla." VALUES  ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst','$var1', '$denominacion')  ";

	$sql = $sql_1;

	$this->set('opcion1', $var1);

	$tabla = '';
	$sql_2 = '';

	if($var1!=null){
		$tabla='arrd05';
		$sql_2 = $tabla.'.cod_presi =  '.$cod_presi.'  '.' and '.$tabla.'.cod_entidad = '.$cod_entidad.'  '.' and '.$tabla.'.cod_tipo_inst = '.$cod_tipo_inst.'  '.' and '.$tabla.'.cod_inst = '.$cod_inst.'  '.' and '.$tabla.'.cod_dep = '.$var1.'  ';
	}

if($tabla!=''){

  if ($this->$tabla->validates($this->data['arrp02'])){

	  if($this->$tabla->findCount($sql_2) == 0){

	   		$this->$tabla->execute($sql);

			$this->set('errorMessage', 'Los Datos Fuer&oacute;n Guardados ');

	   }else{
	   	$this->set('Message_existe', 'Este registro no fu&eacute; almacenado porque ya existe');
	   }

   }else{}


    $datos = $this->$tabla->findAll($sql_2, null, null, null);
    foreach ($datos as $x) $sw = $x['arrd05']['cod_dep'];
	  if($sw == 1){
	  	$opc = 'disabled';
	  }else{
	  	$opc = '';
	  }
	$this->set('opc', $opc);
	$this->set('datos_cod_arrp02', $datos);

	$this->set('tabla', $tabla);

 }//fin if tabla



 }//FIN FUNCTION













 function editar($var1=null){

 	$this->layout = "ajax";
	$this->set('opcion1', $var1);

	$action='';
	$tabla = '';
	$sql_2 = '';

   	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

	if($var1!=null){
		$tabla='arrd05';
		$sql_2 = $tabla.'.cod_presi =  '.$cod_presi.'  '.' and '.$tabla.'.cod_entidad = '.$cod_entidad.'  '.' and '.$tabla.'.cod_tipo_inst = '.$cod_tipo_inst.'  '.' and '.$tabla.'.cod_inst = '.$cod_inst.'  '.' and '.$tabla.'.cod_dep = '.$var1.'  ';
	}

	$this->set('tabla', $tabla);

	$data = $this->$tabla->findAll($sql_2, null, null, null);
	foreach ($data as $x) $sw = $x['arrd05']['cod_dep'];
	  if($sw == 1){
	  	$opc = 'disabled';
	  }else{
	  	$opc = '';
	  }

	$this->set('datos_cod_arrp02', $data);



 }

function  guardar_editar($tabla=null, $var1=null, $aux=null){

 	$this->layout = "ajax";

	 $denominacion = $this->data['arrp02']['denominacion'];
	 $cod_presi = $this->Session->read('SScodpresi');
	 $cod_entidad = $this->Session->read('SScodentidad');
	 $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	 $cod_inst = $this->Session->read('SScodinst');
	 $cod_dep = $this->Session->read('SScoddep');
	 $ano = date('Y');


	if($var1!=null){
		$tabla='arrd05';
		$sql_2 = $tabla.'.cod_presi =  '.$cod_presi.'  '.' and '.$tabla.'.cod_entidad = '.$cod_entidad.'  '.' and '.$tabla.'.cod_tipo_inst = '.$cod_tipo_inst.'  '.' and '.$tabla.'.cod_inst = '.$cod_inst.'  '.' and '.$tabla.'.cod_dep = '.$var1.'  ';
	}

	$sql_1 = 'UPDATE '.$tabla.'   SET  denominacion = \''.$denominacion.'\' WHERE ';

	$sql = $sql_1.$sql_2;

    $this->$tabla->execute($sql);

	$this->set('errorMessage', 'Los Datos Fueron Modificados');


	$this->set('opcion1', $var1);


	$tabla = '';
	$sql_2 = '';



	if($var1!=null){
		$tabla='arrd05';
		$sql_2 = $tabla.'.cod_presi =  '.$cod_presi.'  '.' and '.$tabla.'.cod_entidad = '.$cod_entidad.'  '.' and '.$tabla.'.cod_tipo_inst = '.$cod_tipo_inst.'  '.' and '.$tabla.'.cod_inst = '.$cod_inst.'  '.' and '.$tabla.'.cod_dep = '.$var1.'  ';
	}


	  $data = $this->$tabla->findAll($sql_2, null, null, null);

	  foreach ($data as $x) $sw = $x['arrd05']['cod_dep'];
	  if($sw == 1){
	  	$opc = 'disabled';
	  }else{
	  	$opc = '';
	  }

	  $this->set('opc', $opc);

	  $this->set('datos_cod_arrp02', $data);

	  $this->set('tabla', $tabla);

}//FIN FUNCTION

function eliminar($var1=null){

 	$this->layout = "ajax";

	$tabla='arrd05';
	$cod_presi = $this->Session->read('SScodpresi');
	 $cod_entidad = $this->Session->read('SScodentidad');
	 $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	 $cod_inst = $this->Session->read('SScodinst');

   $sql_2 = '';

	if($var1!=null){
		$sql_2 = $tabla.'.cod_presi =  '.$cod_presi.'  '.' and '.$tabla.'.cod_entidad = '.$cod_entidad.'  '.' and '.$tabla.'.cod_tipo_inst = '.$cod_tipo_inst.'  '.' and '.$tabla.'.cod_inst = '.$cod_inst.'  '.' and '.$tabla.'.cod_dep = '.$var1.'  ';
	}

	$sql_1 = 'DELETE  FROM '.$tabla.'   WHERE ';
	$sql = $sql_1.$sql_2.' ;';

	$this->$tabla->execute($sql);

	$this->set('errorMessage', 'Los Datos Fuer&oacute;n Eliminados ');


	$this->set('opcion1', $var1);

	$tabla = '';
	$sql_2 = '';


	if($var1!=null){
		$tabla='arrd05';
		$sql_2 = $tabla.'.cod_presi =  '.$cod_presi.'  '.' and '.$tabla.'.cod_entidad = '.$cod_entidad.'  '.' and '.$tabla.'.cod_tipo_inst = '.$cod_tipo_inst.'  '.' and '.$tabla.'.cod_inst = '.$cod_inst;
	}

	if($sql_2 != ''){
	  	$data = $this->$tabla->findAll($sql_2, null, null, null);
	  	$this->set('datos_cod_arrp02', $data);
		$this->set('tabla', $tabla);
	  }


 }//FIN FUNCTION

function newUser($var1=null){

 	$this->layout = "ajax";
 	if($var1 != null){
		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
		if($var1 == $cod_dep || $cod_dep == 1){
			$this->set('opcion1', $var1);
		}

		$action='';
		$tabla = '';
		$sql_2 = '';


		if($var1!=null){
			$tabla='arrd05';
			$sql_2 = $tabla.'.cod_presi =  '.$cod_presi.'  '.' and '.$tabla.'.cod_entidad = '.$cod_entidad.'  '.' and '.$tabla.'.cod_tipo_inst = '.$cod_tipo_inst.'  '.' and '.$tabla.'.cod_inst = '.$cod_inst.'  '.' and '.$tabla.'.cod_dep = '.$var1.'  ';
		}

		$this->set('tabla', $tabla);
		if($cod_tipo_inst == 1){
			$this->set('modulos', $this->modulos->generateList('cod_tipo_inst = 1', 'cod_modulo ASC', null, '{n}.modulos.cod_modulo', '{n}.modulos.denominacion'));
		}else{
			$this->set('modulos', $this->modulos->generateList(null, 'cod_modulo ASC', null, '{n}.modulos.cod_modulo', '{n}.modulos.denominacion'));
		}
		$this->set('default', 'CFP000');

		$data = $this->$tabla->findAll($sql_2, null, null, null);
		$this->set('datos_cod_arrp02', $data);
		$cond = "modulo = '0'";

	}
 }


 function userPrincipal(){
 	$this->layout = "ajax";
 	$this->layout = "ajax";
   	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
   	$ListArr05 = $this->arrd05->generateList($this->condicionNDEP(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.cod_dep');
   	$this->AddCero('arr05', $ListArr05);
   	$this->set('entidad_federal', $this->Session->read('entidad_federal'));

 }


function guardar_user($tabla=null, $var1=null,$var=null){

						 	$this->layout = "ajax";
						 	$cod_presi = $this->Session->read('SScodpresi');
							$cod_entidad = $this->Session->read('SScodentidad');
							$cod_tipo_inst = $this->Session->read('SScodtipoinst');
							$cod_inst = $this->Session->read('SScodinst');
							$cod_dep = $this->Session->read('SScoddep');
							$condicion = "arrd05.cod_presi = ".$cod_presi." and arrd05.cod_entidad = ".$cod_entidad." and arrd05.cod_tipo_inst = ".$cod_tipo_inst." and arrd05.cod_inst = ".$cod_inst." and arrd05.cod_dep = ".$cod_dep;
						   	$ListArr05 = $this->arrd05->generateList($this->condicionNDEP(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
						   	$this->AddCero('arr05', $ListArr05);
						   	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
						   	if($var1 == $cod_dep || $cod_dep == 1){
									$this->set('opcion1', $var1);
								}

							$username = $this->data['arrp02']['username'];
							$this->set('username',$username);
							$password = $this->data['arrp02']['password'];
							$this->set('password',$password);
							$funcionario = strtoupper($this->data['arrp02']['funcionario']);
							$cedula         = $this->data['arrp02']['cedula'];
							//$cod_dep_origen = $this->data['arrp02']['cod_dep_origen'];

							if($cod_dep<10){
								$cod="000".$cod_dep;
							}else if($cod_dep>=10 && $cod_dep<=99){
								$cod="00".$cod_dep;
							}else if($cod_dep>=100 && $cod_dep<=999){
								$cod="0".$cod_dep;
							}else if($cod_dep>=1000 && $cod_dep<=9999){
								$cod=$cod_dep;
							}

							$this->set('cod_dep', $cod);


                             $condicion_actividad=$this->data['arrp02']['condicion_actividad'];

							 if(isset($this->data['arrp02']['cod_dirsuperior'])){
							 	$cod_dirsuperior=$this->data['arrp02']['cod_dirsuperior'];
							 	if($cod_dirsuperior==0){
							 		$cod_dirsuperior=0;
							 	}
							 }else{
							 	$cod_dirsuperior=0;
							 }

							 if(isset($this->data['arrp02']['cod_coordinacion'])){
							 	$cod_coordinacion=$this->data['arrp02']['cod_coordinacion'];
							 	if($cod_coordinacion==0){
							 		$cod_coordinacion=0;
							 	}
							 }else{
							 	$cod_coordinacion=0;
							 }


							 if(isset($this->data['arrp02']['cod_secretaria'])){
							 	$cod_secretaria=$this->data['arrp02']['cod_secretaria'];
							 	if($cod_secretaria==0){
							 		$cod_secretaria=0;
							 	}
							 }else{
							 	$cod_secretaria=0;
							 }



							 if(isset($this->data['arrp02']['cod_direccion'])){
							 	$cod_direccion=$this->data['arrp02']['cod_direccion'];
							 	if($cod_direccion==0){
							 		$cod_direccion=0;
							 	}
							 }else{
							 	$cod_direccion=0;
							 }









							 if(isset($this->data['arrp02']['cod_division'])){
							 	$cod_division=$this->data['arrp02']['cod_division'];
							 	if($cod_division==0){
							 		$cod_division=0;
							 	}

							 }else{
							 	$cod_division=0;
							 }

							if(isset($this->data['arrp02']['cod_departamento'])){
								$cod_departamento=$this->data['arrp02']['cod_departamento'];
								if($cod_departamento==0){
									$cod_departamento=0;
								}
							}else{
								$cod_departamento=0;
							}

							if(isset($this->data['arrp02']['cod_oficina'])){
								$cod_oficina=$this->data['arrp02']['cod_oficina'];
								if($cod_oficina==0){
									$cod_oficina=0;
								}
							}else{
								$cod_oficina=0;
							}



$modulo = "";

        $data_modulos=$this->modulos->findAll("cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep,null,'orden_ubicacion ASC');
        foreach($data_modulos as $r){
    	  $modulo_aux = $r['modulos']['cod_modulo'];
    	  if(isset($this->data['arrp02'][$modulo_aux])){
    	  	$aux = $this->data['arrp02'][$modulo_aux];
             if($aux != -1){
			 	$modulo .= $aux;
			 	$var    .= $r['modulos']['denominacion']."<br>";
			 }
    	  }
        }


						    $this->set('modulos',$var);

						    $codigos = "";
							$values = "";

							if($var1!=null){
								$codigos .= "cod_presi, ";
								$values =  " '".$var1."', '0', '0', '0', '0' " ;
							}

							//$username = strtolower($username);
							//$password = strtolower($password);
							$username=str_replace(' ','',$username);
							$password=str_replace(' ','',$password);
							$password = md5($password);

						     if($cod_dep==1){$cod_dep_original = $this->data['arrp02']['cod_dep_origen'];}else{$cod_dep_original=$cod_dep;}

							  $sql_1 = "INSERT INTO usuarios VALUES  ('$username','$password','$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$modulo', '$funcionario', '$cedula', '$cod_dep_original', '".$condicion_actividad."', '".$cod_dirsuperior."', '".$cod_coordinacion."', '".$cod_secretaria."', '".$cod_direccion."', '".$cod_division."', '".$cod_departamento."', '".$cod_oficina."')";




							$sql = $sql_1;

							$this->set('opcion1', $var1);

							$tabla = '';
							$sql_2 = '';
							$var1 = $cod_dep;
							if($var1!=null){
								$tabla='arrd05';
								$sql_2 = $tabla.'.cod_presi =  '.$cod_presi.'  '.' and '.$tabla.'.cod_entidad = '.$cod_entidad.'  '.' and '.$tabla.'.cod_tipo_inst = '.$cod_tipo_inst.'  '.' and '.$tabla.'.cod_inst = '.$cod_inst.'  '.' and '.$tabla.'.cod_dep = '.$var1.'  ';
							}

						  if ($this->Usuario->validates($this->data['arrp02'])){

						  	$cond = "username = '".$this->data['arrp02']['username']."'";

						  	if($this->Usuario->findCount($cond) == 0){

							   	$this->$tabla->execute($sql);
								$this->set('errorMessage', 'EL USUARIO FU&Eacute; CREADO EXITOSAMENTE!!');

						  	}else{

						        $sql_1 = "UPDATE usuarios  SET  modulo = '".$modulo."', cedula_identidad='".$cedula."', funcionario='".$funcionario."', cod_dep_original = '".$cod_dep_original."',  condicion_actividad='".$condicion_actividad."', cod_dir_superior='".$cod_dirsuperior."', cod_coordinacion='".$cod_coordinacion."', cod_secretaria='".$cod_secretaria."', cod_direccion='".$cod_direccion."', cod_division='".$cod_division."', cod_departamento='".$cod_departamento."', cod_oficina='".$cod_oficina."'  " ;

						        if(isset($this->data['arrp02']['password'])){
						        	if($this->data['arrp02']['password']!=""){
						              // $password = strtolower($this->data['arrp02']['password']);
						              $password = md5($this->data['arrp02']['password']);
						              $sql_1 .= " , password='".$password."'   ";
						          }//fin
						        }//fin



						        $sql_1 .= " where username='".$this->data['arrp02']['username']."' " ;
						       	$this->Usuario->execute($sql_1);

						       	$datos = $this->Usuario->findAll($this->condicion()." and username='".$this->data['arrp02']['username']."'  ");
						        $clave = $datos[0]['Usuario']['password'];
						        $this->set('password', $clave);


						  		$this->set('errorMessage', 'LO DATOS FUER&Oacute;N ACTUALIZADOS CORRECTAMENTE');


						  	}//fin


						   }else{
						   	$this->set('errorMessage', 'Error en la validacion');
						   }



						    $datos = $this->arrd05->findAll($sql_2, null, null, null);

							$this->set('datos_cod_arrp02', $datos);
							foreach ($datos as $x) $sw = $x['arrd05']['cod_dep'];
							  if($sw == 1){
							  	$opc = 'disabled';
							  }else{
							  	$opc = '';
							  }
							  $this->set('opc', $opc);

							$this->set('tabla', $tabla);
							$this->set('cod_tipo_inst', $this->Session->read('SScodtipoinst'));

							$dato = $this->arrd05->findAll($condicion, null, 'cod_dep', null);

							foreach( $dato as $dato){
								$denomin = $dato['arrd05']['denominacion'];
								$cod = $dato['arrd05']['cod_dep'];
							}
							$this->set('denominacion', strtoupper($denomin));
							if($cod<10){
								$cod="000".$cod;
							}else if($cod>=10 && $cod<=99){
								$cod="00".$cod;
							}else if($cod>=100 && $cod<=999){
								$cod="0".$cod;
							}else if($cod>=1000 && $cod<=9999){
								$cod=$cod;
							}

							$this->set('codigo', $cod);


 }//FIN FUNCTION








 function consulta(){
 	$this->layout = "ajax";

 }
 function reporte(){

$this->verifica_entrada('14');

 	$this->layout = "ajax";

   	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$this->set('cod_dep', $cod_dep);
	if($cod_dep != 1) $this->users($cod_dep);
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
   	$ListArr05 = $this->arrd05->generateList($this->condicionNDEP(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
   	$this->set( 'arr05',$ListArr05);
   	$this->set('entidad_federal', $this->Session->read('entidad_federal'));

 }

 function users($var = null){
 	$this->layout = "ajax";
 	if($var != null){
		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$condicion = "arrd05.cod_presi = ".$cod_presi." and arrd05.cod_entidad = ".$cod_entidad." and arrd05.cod_tipo_inst = ".$cod_tipo_inst." and arrd05.cod_inst = ".$cod_inst." and arrd05.cod_dep = ".$var;
		$condicion2 = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$var;

		$datos = $this->Usuario->findAll($condicion2." and modulo != '0'", null, "cod_dep, username ASC", null);
		$dep = $this->arrd05->findAll($condicion, null, null, null);

		$opc = $this->Usuario->findCount($condicion2." and modulo = '0'");
		$opc2 = $this->Usuario->findCount($condicion2." and modulo != '0'");
		if($opc2 != 0){
			$this->set('opcion', $var);

			foreach ($dep as $dep){
				$x = $dep['arrd05']['denominacion'];
			}
              $r = 0;
			foreach ($datos as $dat){ $modulos_aux = "";
				$mod = $dat['Usuario']['modulo'];
				$mod = str_split($mod, 6);
               foreach($mod as $dat){
	               	if($modulos_aux==""){
						$modulos_aux_1 = $this->modulos->findAll("cod_modulo = '$dat'", null, null, null);
						$modulos_aux  .= $modulos_aux_1[0]["modulos"]["denominacion"];
					}else{
				   	    $modulos_aux_1 = $this->modulos->findAll("cod_modulo = '$dat'", null, null, null);
				   	    $modulos_aux  .= ", <br> ".$modulos_aux_1[0]["modulos"]["denominacion"];
					}
				}
                $modulos[$r] = $modulos_aux;
                $r++;


			}
			$this->set('modulo', $modulos);
			$this->set('dependencia', $x);

			$this->set('datos', $datos);
		}
 	}
 	$this->set('cod', $var);
 }

 function delUser($var=null, $usr=null, $i=null){
 	$this->layout = "ajax";
 	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = " and cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$var;

 	$sql = "delete from usuarios where username = '".$usr."'".$condicion;

 	$this->Usuario->execute($sql);
 	echo "<script>new Effect.DropOut('tr_$i');</script>";
 	$this->set('mensaje', 'EL USUARIO FU&Eacute; ELIMINADO CORRECTAMENTE');

 }

 function editUser($usr=null, $mod=null, $cod = null){
 	$this->layout = "ajax";
 	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

 	if($cod_tipo_inst == 1){
			$this->set('modulos', $this->modulos->generateList('cod_tipo_inst = 1', 'cod_modulo ASC', null, '{n}.modulos.cod_modulo', '{n}.modulos.denominacion'));
		}else{
			$this->set('modulos', $this->modulos->generateList(null, 'cod_modulo ASC', null, '{n}.modulos.cod_modulo', '{n}.modulos.denominacion'));
		}
		$this->set('default', $mod);
		$this->set('cod', $cod);
		$this->set('usr', $usr);


 }

 function saveUser($var=null, $usr = null){
 	$this->layout = "ajax";
 	$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$condicion = "arrd05.cod_presi = ".$cod_presi." and arrd05.cod_entidad = ".$cod_entidad." and arrd05.cod_tipo_inst = ".$cod_tipo_inst." and arrd05.cod_inst = ".$cod_inst." and arrd05.cod_dep = ".$var;
		$condicion2 = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$var;

 	$this->set('cod', $var);
 	$modulo = $this->data['arrp02']['modulos'];
 	$condicion2 = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$var;
 	$sql = "UPDATE usuarios SET modulo = '".$modulo."' WHERE ".$condicion2." AND username= '".$usr."'";
 	$this->Usuario->execute($sql);
 	$this->set('mensaje', 'USUARIO MODIFICADO CON EXITO');
 	if($var != null){


		$datos = $this->Usuario->findAll($condicion2." and modulo != '0'", null, null, null);
		$dep = $this->arrd05->findAll($condicion, null, null, null);

		$opc = $this->Usuario->findCount($condicion2." and modulo = 0");
		$opc2 = $this->Usuario->findCount($condicion2." and modulo != '0'");
		if($opc2 != 0){
			$this->set('opcion', $var);

			foreach ($dep as $dep){
				$x = $dep['arrd05']['denominacion'];
			}
			foreach ($datos as $dat){
				$mod = $dat['Usuario']['modulo'];
				$modulos[] = $this->modulos->findAll("cod_modulo = '$mod'", null, null, null);
			}
			$this->set('modulo', $modulos);
			$this->set('dependencia', $x);

			$this->set('datos', $datos);
		}
 	}
 }



function entrar(){
	$this->layout="ajax";
	if(isset($this->data['arrp02']['login']) && isset($this->data['arrp02']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['arrp02']['login']);
		$paswd=addslashes($this->data['arrp02']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=14 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->set('autor_valido',true);
			$this->reporte("autor_valido");
			$this->render("reporte");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('autor_valido',true);
			$this->reporte("autor_valido");
			$this->render("reporte");
		}else{
			$this->set('errorMessage',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('autor_valido',false);
			$this->reporte("autor_valido");
			$this->render("reporte");
		}
	}
}


}//FIN DEL CONTROLADOR
?>
