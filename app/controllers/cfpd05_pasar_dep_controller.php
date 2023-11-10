<?php

 class Cfpd05PasarDepController extends AppController{


 	var $uses = array('ccfd04_cierre_mes','v_cfpd05_denominaciones','v_cfpd05_disponibilidad','cfpd05','cfpd05_requerimiento',
                       'cfpd05_auxiliar','cugd02_dependencia', 'cfpd21',
                      'cfpp05auxiliar','arrd05','cugd04','cugd05_restriccion_clave');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf','Form');


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
						if($this->ano_ejecucion()!=""){
							return;
						}else{
							echo "<h3>Por Favor, Registre el Año de Ejecuci&oacute;n de Presupuesto<br>Ingrese al M&oacute;dulo de Uso General</h3>";
							exit();
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

		function SQLCA_S($ano=null){//sql para busqueda de codigos de arranque con y sin año
						 $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
						 $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
						 $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
						 $sql_re .= "cod_inst=".$this->verifica_SS(4)."   ";
						 return $sql_re;
				}//fin funcion SQLCA

		function SQLCAIN($ano=null){//sql para busqueda de codigos de arranque con y sin año
				 $sql_re = $this->verifica_SS(1).",";
				 $sql_re .= $this->verifica_SS(2).",";
				 $sql_re .=  $this->verifica_SS(3).",";
				 $sql_re .= $this->verifica_SS(4).",";
				 if($ano!=null){
					 $sql_re .= $this->verifica_SS(5).",";
						$sql_re .= $ano."";
				 }else{
					 $sql_re .=  $this->verifica_SS(5)."";
				 }
				 return $sql_re;
		}//fin funcion SQLCAIN
		function SQLCA_admin($ano=null){//sql para busqueda de codigos de arranque con y sin año
				 $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
				 $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
				 $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
				 $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
				 if($ano!=null){
					if($this->verifica_SS(5)!=1){
					 $sql_re .= "cod_dep=".$this->verifica_SS(5)."  and  ";
								}
								$sql_re .= "ano=".$ano."  ";
				 }else{
					 if($this->verifica_SS(5)!=1){
					 $sql_re .= "cod_dep=".$this->verifica_SS(5)."  ";
								}
				 }
				 return $sql_re;
		}//fin funcion SQLCA
		function SQLCA_reque($ano=null){//sql para busqueda de codigos de arranque con y sin año
				 $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
				 $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
				 $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
				 $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
				 if($ano!=null){
					 $sql_re .= "ano=".$ano."  ";
				 }else{

				 }
				 return $sql_re;
		}//fin funcion SQLCA

		function SQLCA_report($pre=null){
				 $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
				 $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
				 $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
				 if($pre!=null && $pre==1){
				 $sql_re .= "cod_inst=".$this->verifica_SS(4)." ";
				 //$sql_re .= "cod_dep=0";
				 }else{
					 $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
						$sql_re .= "cod_dep=".$this->verifica_SS(5)." ";
				 }

				 return $sql_re;
		}//fin funcion SQLCA

		function SQLCA_report_in($pre=null){
				 $sql_re = $this->verifica_SS(1).",";
				 $sql_re .= $this->verifica_SS(2).",";
				 $sql_re .= $this->verifica_SS(3).",";
				 if($pre!=null && $pre==1){
				 $sql_re .= $this->verifica_SS(4).",";
				 $sql_re .= 0;
				 }else{
					 $sql_re .= $this->verifica_SS(4).",";
						$sql_re .= $this->verifica_SS(5)." ";
				 }

				 return $sql_re;
		}//fin funcion SQLCA
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
		}
		function AddCerPartida($vector=object){
					 if($vector!=null){
							foreach($vector as $x){
							if($x<10){
								 $Var[$x]="4.0".$x;
							}else{
								 $Var[$x]="4.".$x;
							}
					}//fin each
							return $Var;
					 }else{
							return "";
					 }
			 }//fin AddCero
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
		 return $Var;
			 }else{
					 //return $Var;
			 }
	 }//fin AddCero
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
					}else if($x>=10 && $x<=99){
						$cod[$x] = $x.' - '.$y;
					}
				}
			}
			//print_r($cod);
		}

		$this->set($nomVar, $cod);
	}//fin function

	function concatena($vector1=null, $nomVar=null, $extra=null){
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
						$cod[$x] = '0'.$x.' - '.$y;
					}else if($x>=10){
						$y=($y!="")?$y:"N/A";
						$cod[$x] = $x.' - '.$y;
					}
				}
			}
			//print_r($cod);
		}

		$this->set($nomVar, $cod);
	}//fin function

	function borrar_cugd04(){
		$condicion = $this->condicion();
		$username = $this->Session->read('nom_usuario');
		$username = strtoupper($username);

		$c = $this->cugd04->findCount($condicion." and username='$username'");

		if ($c!=0){
			$this->cugd04->execute("DELETE FROM cugd04 WHERE ".$condicion." and username='$username'");
		}
	}

	function index($var=null){///////////////<<--INDEX

$this->verifica_entrada('35');

		 $this->layout = "ajax";
		 $ano=$this->ano_ejecucion();
		 $this->index2();
	 	 $this->render('index2');

	}//fin index

function index2($var=null){///////////////<<--INDEX 2
    $this->layout = "ajax";
             $this->limpiar_lista();
			 $this->borrar_cugd04();
			 $ano=$this->ano_ejecucion();
			 $this->set('ano',$ano);
}//fin index

function buscar_dep ($Var=null,$pista=null) {
     $this->layout = "ajax";
     $this->set('opcion',$Var);
     $cond ="cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and upper(denominacion) like upper('%".$pista."%') ";
     $lista=  $this->cugd02_dependencia->generateList($cond, 'cod_dependencia ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
	 $this->concatena($lista, 'vector');
}

function cargar_codigo_dep ($var=null,$cod_dep=null) {
	 $this->layout = "ajax";
	 $ano=$this->ano_ejecucion();
	 $this->set('opcion',$var);
	 if($var=='origen'){
          $cond ="cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$cod_dep."";
          $rs=  $this->cugd02_dependencia->findAll($cond, array('denominacion'));
          $this->Session->write('cod_dep_origen',$cod_dep);
          $this->set('cod_dep',$cod_dep);
          $this->set('denominacion',$rs[0]['cugd02_dependencia']['denominacion']);
		  $cond2 =" cod_presi=".$this->verifica_SS(1)."  and cod_entidad=".$this->verifica_SS(2)." and cod_tipo_inst=".$this->verifica_SS(3)." and cod_inst=".$this->verifica_SS(4)." and cod_dep=".$cod_dep." and ano=".$ano;
		  $lista=  $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_sector ASC', null, '{n}.v_cfpd05_denominaciones.cod_sector', '{n}.v_cfpd05_denominaciones.deno_sector');
		  $this->concatena($lista, 'vector');

	 }else if($var=='destino'){
          $cond ="cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$cod_dep."";
          $rs=  $this->cugd02_dependencia->findAll($cond, array('denominacion'));
          $this->Session->write('cod_dep_destino',$cod_dep);
          $this->set('cod_dep',$cod_dep);
          $this->set('denominacion',$rs[0]['cugd02_dependencia']['denominacion']);
	 }


}




function select($select=null,$var=null) { //select de ubicacion administrativa
	$this->layout = "ajax";
	/**
	 * cod_1 : direccion superior
	 * cod_2 : coordinacion
	 * cod_3 : secretaria
	 * cod_4 : direccion
	 * cod_5 : division
	 * cod_6 : departamento
	 */
if(isset($var) && !empty($var) && $var!=''){
		$cond ="cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$this->verifica_SS(5);
	switch($select){
		case 'coordinacion':
			$this->set('SELECT','secretaria');
			$this->set('codigo','coordinacion');
			$this->set('seleccion','');
			$this->set('n',2);
			$this->Session->write('cod_1',$var);
			$cond .=" and cod_dir_superior=".$var;
			$lista=  $this->cugd02_coordinacion->generateList($cond, 'cod_coordinacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
					$this->concatena($lista, 'vector');
		break;
		case 'secretaria':
			$this->set('SELECT','direccion');
			$this->set('codigo','secretaria');
			$this->set('seleccion','');
			$this->set('n',3);
			$cod_1 =  $this->Session->read('cod_1');
			$this->Session->write('cod_2',$var);
			$cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$var;
			$lista=  $this->cugd02_secretaria->generateList($cond, 'cod_secretaria ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
					$this->concatena($lista, 'vector');
		break;
		case 'direccion':
			$this->set('SELECT','catalogo');
			$this->set('codigo','direccion');
			$this->set('seleccion','');
			 $this->set('n',4);
			 //$this->set('no','no');
			$cod_1 =  $this->Session->read('cod_1');
			$cod_2 =  $this->Session->read('cod_2');
			$this->Session->write('cod_3',$var);
			$cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$var;
			$lista=  $this->cugd02_direccion->generateList($cond, 'cod_direccion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
					$this->concatena($lista, 'vector');
		break;
		case 'catalogo':
			$cod_1 =  $this->Session->read('cod_1');//dir sup
			$cod_2 =  $this->Session->read('cod_2');//coor
			$cod_3 =  $this->Session->read('cod_3');//secre
			$cond .=" and cod_dir_superior=".$cod_1." and cod_coordinacion=".$cod_2." and  cod_secretaria=".$cod_3." and cod_direccion=".$var;
			$resultado=$this->cugd02_direccion->findAll($cond,array('cod_sector','cod_programa','cod_sub_prog','cod_proyecto'));
			//print_r($resultado);
			$this->Session->write('CodigosDireccion',$resultado);
			$this->set('SELECT','catalogo');
			$this->set('codigo','catalogo');
			$this->set('seleccion','');
			$this->set('n',5);
			$this->set('no','no');
			$this->set('otro','otro');
		//	$lista=  $this->cscd01_catalogo->generateList(null, 'codigo_prod_serv ASC', null, '{n}.cscd01_catalogo.codigo_prod_serv', '{n}.cscd01_catalogo.denominacion');
		   // $this->concatena($lista, 'vector');
    $codigos_direccion=$this->Session->read('CodigosDireccion');
 	$cod_sector=$codigos_direccion[0]["cugd02_direccion"]["cod_sector"];
 	$cod_programa=$codigos_direccion[0]["cugd02_direccion"]["cod_programa"];
 	$cod_sub_prog=$codigos_direccion[0]["cugd02_direccion"]["cod_sub_prog"];
 	$cod_proyecto=$codigos_direccion[0]["cugd02_direccion"]["cod_proyecto"];
 	$catalogo= $this->v_cscd01_catalogo_deno_und->generateList($this->condicion()." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_sub_prog=".$cod_sub_prog." and cod_proyecto=".$cod_proyecto, 'cod_snc ASC', null, '{n}.v_cscd01_catalogo_deno_und.codigo_prod_serv', '{n}.v_cscd01_catalogo_deno_und.denominacion');
 		$this->concatena($catalogo, 'vector');


		break;
	}
	}else{
		echo "";
	}
}//fin select ubicacion administrativa

function mostrar($select=null,$var=null) {
		$this->layout = "ajax";
if(isset($var) && !empty($var) && $var!=''){
	//$dirsup =  $this->Session->read('dirsup');
	$cond = "cod_tipo_institucion=".$this->verifica_SS(3)." and cod_institucion=".$this->verifica_SS(4)." and cod_dependencia=".$this->verifica_SS(5);
		switch($select){
			case 'dirsuperior':
			$this->Session->write('dirsup',$var);
			$cond .=" and cod_dir_superior=".$var;
			$a=  $this->cugd02_direccionsuperior->findAll($cond);
				$x= $a[0]['cugd02_direccionsuperior']['denominacion'];
				$this->set("deno",$x);
 		break;
		case 'coordinacion':
			$dirsup= $this->Session->read('dirsup');
			$this->Session->write('coor',$var);
			$cond .=" and cod_dir_superior=".$dirsup." and cod_coordinacion=".$var;
			$a=  $this->cugd02_coordinacion->findAll($cond);
				$x= $a[0]['cugd02_coordinacion']['denominacion'];
				$this->set("deno",$x);
 		break;
		case 'secretaria':
			$dirsup= $this->Session->read('dirsup');
			$coor =  $this->Session->read('coor');
			$this->Session->write('secr',$var);
			$cond .=" and cod_dir_superior=".$dirsup." and cod_coordinacion=".$coor." and cod_secretaria=".$var;
			$a=  $this->cugd02_secretaria->findAll($cond);
				$x= $a[0]['cugd02_secretaria']['denominacion'];
				$this->set("deno",$x);
 		break;
		case 'direccion':
			$dirsup= $this->Session->read('dirsup');
			$coor =  $this->Session->read('coor');
			$secr =  $this->Session->read('secr');
			$this->Session->write('dir',$var);
			$cond .=" and cod_dir_superior=".$dirsup." and cod_coordinacion=".$coor." and  cod_secretaria=".$secr." and cod_direccion=".$var;
			$a=  $this->cugd02_direccion->findAll($cond);
				$x= $a[0]['cugd02_direccion']['denominacion'];
				$this->set("deno",$x);
			break;
			case 'catalogo':
		        $a=  $this->cscd01_catalogo->findAll("codigo_prod_serv=".$var,array('denominacion'));
				$x= $a[0]['cscd01_catalogo']['denominacion'];
				$this->set("deno",$x);
			break;
	}//fin switch
		}else{
			echo "";
			$this->set("deno","");
		}
//$oart=$var<9?CE."0".$var:CE.$var;
}//fin mostrar cod dir superior

function mostrarcodigo($select=null,$var=null) {
		$this->layout = "ajax";
if(isset($var) && !empty($var) && $var!=''){
			switch($select){
			case 'dirsuperior':
					$this->set("codigo",$var);
 		break;
		case 'coordinacion':
			 $this->set("codigo",$var);
 		break;
		case 'secretaria':
			 $this->set("codigo",$var);
 		break;
		case 'direccion':
			 $this->set("codigo",$var);
			break;
			case 'catalogo':
			     $a=  $this->cscd01_catalogo->findAll("codigo_prod_serv=".$var);
				 $x= $a[0]['cscd01_catalogo']['cod_snc'];
				 $this->set("codigo",$x);
				 $this->set("catalogo","si");
				 break;
	}//fin switch
		}else{
			echo "";
			 $this->set("codigo","");
		}

}//fin mostrar los codigos de los select



function select3($select=null,$cod_dep=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";
	$this->set('cod_dep',$cod_dep);
if($select!=null && $var!=null){
	//$cond =$this->SQLCA();
	$cond =" cod_presi=".$this->verifica_SS(1)."  and cod_entidad=".$this->verifica_SS(2)." and cod_tipo_inst=".$this->verifica_SS(3)." and cod_inst=".$this->verifica_SS(4)." and cod_dep=".$cod_dep."";
	switch($select){
		/*case 'sector':
			$this->set('SELECT','programa');
			$this->set('codigo','sector');
			$this->set('seleccion','');
			$this->set('n',1);
			$this->Session->write('ano',$var);
			$cond .=" and ano=".$var;
			//$lista=  $this->cfpd02_sector->generateList($cond, 'cod_sector ASC', null, '{n}.cfpd02_sector.cod_sector', '{n}.cfpd02_sector.denominacion');
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_sector ASC', null, '{n}.v_cfpd05_denominaciones.cod_sector', '{n}.v_cfpd05_denominaciones.deno_sector');
			$this->concatena($lista, 'vector');
		break;*/
		case 'programa':

			$this->set('SELECT','subprograma');
			$this->set('codigo','programa');
			$this->set('seleccion','');
			$this->set('n',2);
			$ano=$this->ano_ejecucion();
			$this->Session->write('ano',$ano);
			$this->Session->write('sec',$var);
			$cond .=" and ano=".$ano." and cod_sector=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_programa ASC', null, '{n}.v_cfpd05_denominaciones.cod_programa', '{n}.v_cfpd05_denominaciones.deno_programa');
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
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_sub_prog ASC', null, '{n}.v_cfpd05_denominaciones.cod_sub_prog', '{n}.v_cfpd05_denominaciones.deno_sub_prog');
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
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_proyecto ASC', null, '{n}.v_cfpd05_denominaciones.cod_proyecto', '{n}.v_cfpd05_denominaciones.deno_proyecto');
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
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_activ_obra ASC', null, '{n}.v_cfpd05_denominaciones.cod_activ_obra', '{n}.v_cfpd05_denominaciones.deno_activ_obra');
			$this->concatena($lista, 'vector');
		break;
		case 'partida':
			$this->set('SELECT','generica');
			$this->set('codigo','partida');
			$this->set('seleccion','');
			$this->set('n',6);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy =  $this->Session->read('proy');
			$this->Session->write('actividad',$var);
			$cond2 =$cond." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_partida ASC', null, '{n}.v_cfpd05_denominaciones.cod_partida', '{n}.v_cfpd05_denominaciones.deno_partida');
			$this->concatena($lista, 'vector');

		break;
		case 'generica':
			$this->set('SELECT','especifica');
			$this->set('codigo','generica');
			$this->set('seleccion','');
			$this->set('n',7);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy =  $this->Session->read('proy');
			$activ=$this->Session->read('actividad');
			$this->Session->write('cpar',$var);
			$cond2 =$cond." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$activ." and cod_partida=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_generica ASC', null, '{n}.v_cfpd05_denominaciones.cod_generica', '{n}.v_cfpd05_denominaciones.deno_generica');
					$this->concatena($lista, 'vector');
 		break;
		case 'especifica':
			$this->set('SELECT','subespecifica');
			$this->set('codigo','especifica');
			$this->set('seleccion','');
			$this->set('n',8);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy =  $this->Session->read('proy');
			$activ=$this->Session->read('actividad');
			$cpar =  $this->Session->read('cpar');
			$this->Session->write('cgen',$var);
			$cond2 =$cond." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$activ." and cod_partida=".$cpar." and cod_generica=".$var;
			$lista = $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_especifica ASC', null, '{n}.v_cfpd05_denominaciones.cod_especifica', '{n}.v_cfpd05_denominaciones.deno_especifica');
					$this->concatena($lista, 'vector');
		break;
		case 'subespecifica':
			$this->set('SELECT','auxiliar');
			$this->set('codigo','subespecifica');
			$this->set('seleccion','');
			$this->set('n',9);
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy =  $this->Session->read('proy');
			$activ=$this->Session->read('actividad');
			$cpar =  $this->Session->read('cpar');
			$cgen =  $this->Session->read('cgen');
			$this->Session->write('cesp',$var);
			$cond2 =$cond." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$activ." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$var;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_sub_espec ASC', null, '{n}.v_cfpd05_denominaciones.cod_sub_espec', '{n}.v_cfpd05_denominaciones.deno_sub_espec');
		    $this->concatena($lista, 'vector');
		break;
		case 'auxiliar':

			$this->set('SELECT','escribir_aux');
			$this->set('codigo','auxiliar');
			$this->set('seleccion','');
			$this->set('n',10);
			//$this->set('no','no');
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy =  $this->Session->read('proy');
			$activ=$this->Session->read('actividad');
			$cpar =  $this->Session->read('cpar');
			$cgen =  $this->Session->read('cgen');
			$cesp =  $this->Session->read('cesp');
			$this->Session->write('csesp',$var);
			//$cpar=$cpar<9 ? "40".$cpar  : "4".$cpar;
			//$cond2 ="ano=".$ano." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$var;
			//echo "AUX1".$cond2;
			$cond2 =$cond." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$activ." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$var;
			//echo $cond2;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond." and ".$cond2, 'cod_auxiliar ASC', null, '{n}.v_cfpd05_denominaciones.cod_auxiliar', '{n}.v_cfpd05_denominaciones.deno_auxiliar');
						if($lista!=null){
							$this->concatena($lista, 'vector');
						}else{
							$this->set('vector',array('0'=>'00'));

						}
//         echo "muestra";
		break;
		case 'auxiliar2':
		 //echo "hola auxiliar 2";
			$this->set('SELECT','escribir_aux');
			$this->set('codigo','auxiliar');
			$this->set('seleccion','');
			$this->set('n',10);
			//$this->set('no','no');
			$ano =  $this->Session->read('ano');
			$sec =  $this->Session->read('sec');
			$prog =  $this->Session->read('prog');
			$subp =  $this->Session->read('subp');
			$proy =  $this->Session->read('proy');
			//$activ=$this->Session->read('actividad');
			$cpar =  $this->Session->read('cpar');
			$cgen =  $this->Session->read('cgen');
			$cesp =  $this->Session->read('cesp');
			$this->Session->write('actividad',$var);
			$f=$this->Session->read('CodigosDireccion');
			$p=$this->Session->read('partidas');
			 //print_r($p);
			/*$part= $p[0]['cscd01_catalogo']['cod_partida']<9 ? "40".$p[0]['cscd01_catalogo']['cod_partida']:$p[0]['cscd01_catalogo']['cod_partida'];
					$part= $part <400 ? "4".$part : $part;
					if($this->Session->read("year_pago")>date("Y")){
								$ano= 1+date("Y");
			}else{
							$ano=date("Y");
			}
			$cond2 =" cod_sector=".$f[0]["cugd02_direccion"]["cod_sector"]." and cod_programa=".$f[0]["cugd02_direccion"]["cod_programa"]." and cod_sub_prog=".$f[0]["cugd02_direccion"]["cod_sub_prog"]." and cod_proyecto=".$f[0]["cugd02_direccion"]["cod_proyecto"]." and cod_activ_obra=".$var." and ano=".$ano." and cod_partida=".$part." and cod_generica=".$p[0]["cscd01_catalogo"]["cod_generica"]." and cod_especifica=".$p[0]["cscd01_catalogo"]["cod_especifica"]." and cod_sub_espec=".$p[0]["cscd01_catalogo"]["cod_sub_espec"];
			//echo "AUX2".$cond2;*/
			$cond2 =$cond." and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_proyecto=".$proy." and cod_activ_obra=".$var." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$p[0]["cscd01_catalogo"]["cod_sub_espec"];
			//echo $cond2;
			$lista=  $this->v_cfpd05_denominaciones->generateList($cond2, 'cod_auxiliar ASC', null, '{n}.v_cfpd05_denominaciones.cod_auxiliar', '{n}.v_cfpd05_denominaciones.deno_auxiliar');
			/**			if($lista!=null){
							$this->AddCero('vector',$lista);
						}else{
							$this->set('vector',array('0'=>'00'));
						}*/
						if($lista!=null){
							$this->concatena($lista, 'vector');
							//echo count($lista);
						}else{
							$this->set('vector',array('0'=>'00'));
							//echo "cero";
							$disponibilidad = $this->disponibilidad($_SESSION["ano"], $_SESSION["sec"], $_SESSION["prog"], $_SESSION["subp"], $_SESSION["proy"], $_SESSION["actividad"], $_SESSION["cpar"], $_SESSION["cgen"], $_SESSION["cesp"], $_SESSION["csesp"], 0);

				       echo "<script>" .
				 		"document.getElementById('td_disponibilidad').innerHTML='".$this->Formato2($disponibilidad)."'; " .
				 		"</script>";
						}
//		echo "sddsf";
		break;
		case 'escribir_aux':
       /// echo "saaaaaaaaaaa";
            $ano=$this->ano_ejecucion();
            $cod_sector =  $this->Session->read('sec');
			$cod_programa =  $this->Session->read('prog');
			$cod_sub_prog =  $this->Session->read('subp');
			$cod_proyecto =  $this->Session->read('proy');
			$cod_activ_obra=$this->Session->read('actividad');
			$cod_partida =  $this->Session->read('cpar');
			$cod_generica =  $this->Session->read('cgen');
			$cod_especifica =  $this->Session->read('cesp');
			$cod_sub_espec=$this->Session->read('csesp');
			$cod_auxiliar=$var;
			$para=$this->verifica_SS(1).",".$this->verifica_SS(2).",".$this->verifica_SS(3).",".$this->verifica_SS(4).",".$cod_dep.",".$ano.",".$cod_sector.",".$cod_programa.",".$cod_sub_prog.",".$cod_proyecto.",".$cod_activ_obra.",".$cod_partida.",".$cod_generica.",".$cod_especifica.",".$cod_sub_espec.",".$cod_auxiliar."";
            $tiene_movimiento = $this->cfpd05->execute("select tiene_movimiento_cfpd05($para);");
//            echo "select tiene_movimiento_cfpd05($para);<br>";



                    $sql="  cod_presi      = '".$this->verifica_SS(1)."' and
	                		cod_entidad    = '".$this->verifica_SS(2)."' and
							cod_tipo_inst  = '".$this->verifica_SS(3)."' and
							cod_inst       = '".$this->verifica_SS(4)."' and
							cod_dep        = '".$cod_dep."'       and
							ano            = '".$ano."'                  and
							cod_sector     = '".$cod_sector."'           and
							cod_programa   = '".$cod_programa."'         and
							cod_sub_prog   = '".$cod_sub_prog."'         and
							cod_proyecto   = '".$cod_proyecto."'         and
							cod_activ_obra = '".$cod_activ_obra."'       and
							cod_partida    = '".$cod_partida."'          and
							cod_generica   = '".$cod_generica."'         and
							cod_especifica = '".$cod_especifica."'       and
							cod_sub_espec  = '".$cod_sub_espec."'        and
							cod_auxiliar   = '".$cod_auxiliar."'";

					            $activa = $this->cfpd21->findCount($sql);
					            $da     = $this->cfpd05->findAll($sql);

					            $aumento_traslado_anual     = $da[0]["cfpd05"]["aumento_traslado_anual"];
					            $disminucion_traslado_anual = $da[0]["cfpd05"]["disminucion_traslado_anual"];
					            $credito_adicional_anual    = $da[0]["cfpd05"]["credito_adicional_anual"];
					            $rebaja_anual               = $da[0]["cfpd05"]["rebaja_anual"];
					            $compromiso_anual           = $da[0]["cfpd05"]["compromiso_anual"];
					            $causado_anual              = $da[0]["cfpd05"]["causado_anual"];
					            $pagado_anual               = $da[0]["cfpd05"]["pagado_anual"];

					            if($aumento_traslado_anual!=0){    $msj[]="Aumento traslado ";}
					            if($disminucion_traslado_anual!=0){$msj[]="Disminución traslado ";}
					            if($credito_adicional_anual!=0){   $msj[]="Credito adicional ";}
					            if($rebaja_anual!=0){              $msj[]="Rebaja";}
					            if($compromiso_anual!=0){          $msj[]="Comprimiso ";}
					            if($causado_anual!=0){             $msj[]="Causado ";}
					            if($pagado_anual!=0){              $msj[]="Pagado ";}



                                if($activa!=0){

	                                   $this->set('errorMessage', 'PARTIDA NO PUEDE SER TRANSFERIDA, TIENE MOVIMIENTOS.');
	                                   $this->set('disabled','disabled');

                                }else if($tiene_movimiento[0][0]['tiene_movimiento_cfpd05']!=0){


					                    //$this->set('errorMessage', 'PARTIDA TIENE MOVIMIENTO DE '.implode(',',$msj));
					                    $this->set('errorMessage', 'PARTIDA NO PUEDE SER TRANSFERIDA, TIENE MOVIMIENTOS.');
					                    $this->set('disabled','disabled');

						        }else{

						                $this->set('disabled','enabled');

						        }//fin else

            //print_r($tiene_movimiento);
			$this->set("ocultar",true);
			//echo "sddsfsdfsdvcbcvb";
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


function procesar_cambio_dep(){
	$this->layout="ajax";
	$cod_dep_origen=$this->Session->read('cod_dep_origen');
	$cod_dep_destino=$this->Session->read('cod_dep_destino');
 	$guardar[]=0;
 	$codigos[]=0;
 	$ano=$this->ano_ejecucion();
 	$i=0;

 	$this->cfpd05->execute("BEGIN;");

		if(!empty($this->data["cfpd05_pasar_dep"]["cod_sector"])){
							    $sql  = " and cod_sector=".$this->data["cfpd05_pasar_dep"]["cod_sector"]." ";
							if(!empty($this->data["cepp01_compromiso_partidas"]["cod_programa"])){
								$sql .= " and cod_programa=".$this->data["cepp01_compromiso_partidas"]["cod_programa"]." ";
							}
							if(!empty($this->data["cepp01_compromiso_partidas"]["cod_subprograma"])){
								$sql .= " and cod_sub_prog=".$this->data["cepp01_compromiso_partidas"]["cod_subprograma"]." ";
							}
							if(!empty($this->data["cepp01_compromiso_partidas"]["cod_proyecto"])){
								$sql .= " and cod_proyecto=".$this->data["cepp01_compromiso_partidas"]["cod_proyecto"]." ";
							}
							if(!empty($this->data["cepp01_compromiso_partidas"]["cod_proyecto"])){
								$sql .= " and cod_proyecto=".$this->data["cepp01_compromiso_partidas"]["cod_proyecto"]." ";
							}
							if(!empty($this->data["cepp01_compromiso_partidas"]["cod_actividad"])){
								$sql .= " and cod_activ_obra=".$this->data["cepp01_compromiso_partidas"]["cod_actividad"]." ";
							}
							if(!empty($this->data["cepp01_compromiso_partidas"]["cod_partida"])){
								$sql .= " and cod_partida=".$this->data["cepp01_compromiso_partidas"]["cod_partida"]." ";
							}
							if(!empty($this->data["cepp01_compromiso_partidas"]["cod_generica"])){
								$sql .= " and cod_generica=".$this->data["cepp01_compromiso_partidas"]["cod_generica"]." ";
							}
							if(!empty($this->data["cepp01_compromiso_partidas"]["cod_especifica"])){
								$sql .= " and cod_especifica=".$this->data["cepp01_compromiso_partidas"]["cod_especifica"]." ";
							}
							if(!empty($this->data["cepp01_compromiso_partidas"]["cod_subespecifica"])){
								$sql .= " and cod_sub_espec=".$this->data["cepp01_compromiso_partidas"]["cod_subespecifica"]." ";
							}
							if(!empty($this->data["cepp01_compromiso_partidas"]["cod_auxiliar"])){
								$sql .= " and cod_auxiliar=".$this->data["cepp01_compromiso_partidas"]["cod_auxiliar"]." ";
							}
							$sw  = $this->cfpd05->execute("       update      cfpd05 set cod_dep=".$cod_dep_destino." where ".$this->condicionNDEP()." and cod_dep=".$cod_dep_origen." and ano=".$ano." ".$sql."; ");
							if($sw > 1){

								   $sql2 =" cod_presi      = '".$this->verifica_SS(1)."' and
					                		cod_entidad    = '".$this->verifica_SS(2)."' and
											cod_tipo_inst  = '".$this->verifica_SS(3)."' and
											cod_inst       = '".$this->verifica_SS(4)."' and
											cod_dep        = '".$cod_dep_origen."'       and
											ano            = '".$ano."'                      ".$sql;
								   $da     = $this->cfpd05->findAll($sql2);
								   foreach($da as $ve){
									   	$guardar[0]  = $ve["cfpd05"]["ano"];
									   	$guardar[1]  = $ve["cfpd05"]["cod_sector"];
									   	$guardar[2]  = $ve["cfpd05"]["cod_programa"];
									   	$guardar[3]  = $ve["cfpd05"]["cod_sub_prog"];
									   	$guardar[4]  = $ve["cfpd05"]["cod_proyecto"];
									   	$guardar[5]  = $ve["cfpd05"]["cod_activ_obra"];
									   	$guardar[6]  = $ve["cfpd05"]["cod_partida"];
									   	$guardar[7]  = $ve["cfpd05"]["cod_generica"];
									   	$guardar[8]  = $ve["cfpd05"]["cod_especifica"];
									   	$guardar[9]  = $ve["cfpd05"]["cod_sub_espec"];
									   	$guardar[10] = $ve["cfpd05"]["cod_auxiliar"];
								      	$sw2 = $this->cfpd05->execute("select cambia_codigos_categoria_aux(".$this->verifica_SS(1).",".$this->verifica_SS(2).",".$this->verifica_SS(3).",".$this->verifica_SS(4).", ".$cod_dep_origen.", ".$guardar[0].",".$guardar[1].",".$guardar[2].",".$guardar[3].",".$guardar[4].",".$guardar[5].",".$guardar[6].",".$guardar[7].",".$guardar[8].",".$guardar[9].",".$guardar[10].",".$cod_dep_destino.");");
								    }
									if($sw2 > 1){
										$this->set('Message_existe', 'Cambio de código presupuestario exitoso');
										$sw1 = $this->cfpd05->execute("COMMIT");
									}else{
										$this->cfpd05->execute("ROLLBACK");
										$this->set('errorMessage', 'POR FAVOR INTENTE EL PROCESO NUEVAMENTE');
									}
							}else{
								$this->cfpd05->execute("ROLLBACK");
								$this->set('errorMessage', 'POR FAVOR INTENTE EL PROCESO NUEVAMENTE');
							}
		}else{
							$this->cfpd05->execute("ROLLBACK");
							$this->set('errorMessage', 'POR FAVOR INTENTE EL PROCESO NUEVAMENTE');
		}




	 	$this->index();
	 	$this->render('index');

}// fin procesar_cambio_dep

function agregar_partidas($var=null) {
	$this->layout="ajax";
	if(isset($_SESSION["contador"])){
        $_SESSION["contador"]=$_SESSION["contador"]+1;
	}else{
		$_SESSION["contador"]=1;
	}
//
	$ano=$this->ano_ejecucion();
    $cod_sector =  $this->Session->read('sec');
	$cod_programa =  $this->Session->read('prog');
	$cod_sub_prog =  $this->Session->read('subp');
	$cod_proyecto =  $this->Session->read('proy');
	$cod_activ_obra=$this->Session->read('actividad');
	$cod_partida =  $this->Session->read('cpar');
	$cod_generica =  $this->Session->read('cgen');
	$cod_especifica =  $this->Session->read('cesp');
	$cod_sub_espec=$this->Session->read('csesp');
	//pr($this->data);
	$cod_auxiliar=$this->data['cepp01_compromiso_partidas']['cod_auxiliar'];
	$cod_dep=$this->Session->read('cod_dep_destino');
	$cod_dep_origen=$this->Session->read('cod_dep_origen');
//	pr($this->data);
	if($this->data['cepp01_compromiso_partidas']['cod_auxiliar']==''){
        $this->set('errorMessage', 'Debe seleccionar el auxilar');
		return;
    }else if(!isset($_SESSION['cod_dep_destino'])){
    	 $this->set('errorMessage', 'Debe seleccionar la dependencia destino');
		 return;
    }
	$para=$this->verifica_SS(1).",".$this->verifica_SS(2).",".$this->verifica_SS(3).",".$this->verifica_SS(4).",".$cod_dep.",".$ano.",".$cod_sector.",".$cod_programa.",".$cod_sub_prog.",".$cod_proyecto.",".$cod_activ_obra.",".$cod_partida.",".$cod_generica.",".$cod_especifica.",".$cod_sub_espec.",".$cod_auxiliar."";
    $tiene_movimiento = $this->cfpd05->execute("select cantidad_fila_cfpd05($para);");
//            echo "select tiene_movimiento_cfpd05($para);<br>";
    if($tiene_movimiento[0][0]['cantidad_fila_cfpd05']!=0){
        $this->set('errorMessage', 'Los códigos presupuestarios seleccionados ya existen en la dependencia destino');
//        $this->set('disabled','disabled');
			return;
    }else{
//    	 $this->set('Message_existe', 'Los codigos seleccionados ya existen en la lista');
//    	 $this->set('disabled','enabled');

                $activa = 0;

	                $sql="  cod_presi      = '".$this->verifica_SS(1)."' and
	                		cod_entidad    = '".$this->verifica_SS(2)."' and
							cod_tipo_inst  = '".$this->verifica_SS(3)."' and
							cod_inst       = '".$this->verifica_SS(4)."' and
							cod_dep        = '".$cod_dep_origen."'       and
							ano            = '".$ano."'                  and
							cod_sector     = '".$cod_sector."'           and
							cod_programa   = '".$cod_programa."'         and
							cod_sub_prog   = '".$cod_sub_prog."'         and
							cod_proyecto   = '".$cod_proyecto."'         and
							cod_activ_obra = '".$cod_activ_obra."'       and
							cod_partida    = '".$cod_partida."'          and
							cod_generica   = '".$cod_generica."'         and
							cod_especifica = '".$cod_especifica."'       and
							cod_sub_espec  = '".$cod_sub_espec."'        and
							cod_auxiliar   = '".$cod_auxiliar."'";

					            $activa=$this->cfpd21->findCount($sql);

                                if($activa!=0){

                                   $this->set('errorMessage', 'PARTIDA NO PUEDE SER TRANSFERIDA, TIENE MOVIMIENTOS.');
                                   return;

                                }//fin else
    }//fin else

//	pr($this->data);
	if(isset($var) && !empty($var)){
            $cod[0]=$ano;
			$cod[1]=$cod_sector;
			$cod[2]=$cod_programa;
			$cod[3]=$cod_sub_prog;
			$cod[4]=$cod_proyecto;
			$cod[5]=$cod_activ_obra;
			$cod[6]=$cod_partida;
			if($cod[6]<9){
				$cod[6]="40".$cod[6];
			}else if($cod[6]<100){
				$cod[6]="4".$cod[6];
			}else{
				$cod[6]=$cod[6];
			}

			$cod[7]=$cod_generica;
			$cod[8]=$cod_especifica;
			$cod[9]=$cod_sub_espec;
			$cod[10]=$cod_auxiliar;//
			$cod[10]=$cod[10]<9?str_replace("0","",$cod[10]):$cod[10];
			$cod[10]=$cod[10]<9?"0".$cod[10]:$cod[10];
//			$cod[11]=$this->data["cepp01_compromiso_partidas"]["monto_partidas"];
		    if(isset($_SESSION["i"])){
			$i=$this->Session->read("i")+1;
			$this->Session->write("i",$i);
	    }else{
		   $this->Session->write("i",0);
			$i=0;
		}
        switch($var){
        	case 'normal':
					 $vec[$i][0]=$ano;
					 $vec[$i][1]=$cod_sector;
					 $vec[$i][2]=$cod_programa;
					 $vec[$i][3]=$cod_sub_prog;
					 $vec[$i][4]=$cod_proyecto;
					 $vec[$i][5]=$cod_activ_obra;
					 $vec[$i][6]=$cod_partida;//<9 ? "4.0".$this->data["cepp01_compromiso_partidas"]["cod_partida"] : "4.".$this->data["cepp01_compromiso_partidas"]["cod_partida"];
					 $vec[$i][7]=$cod_generica;
					 $vec[$i][8]=$cod_especifica;
					 $vec[$i][9]=$cod_sub_espec;
					 $vec[$i][10]=$cod_auxiliar;
//					 $vec[$i][11]=$this->data["cepp01_compromiso_partidas"]["monto_partidas"];
					 $vec[$i]["id"]=$i;
//					         $disponible_partida = $this->disponibilidad($vec[$i][0], $vec[$i][1], $vec[$i][2], $vec[$i][3], $vec[$i][4], $vec[$i][5], $vec[$i][6],$vec[$i][7], $vec[$i][8], $vec[$i][9],$vec[$i][10]);
//					         $monto_partida_array = $this->Formato1($vec[$i][11]);
						 if(isset($_SESSION["items"])){
							foreach($_SESSION["items"] as $codi){
								//echo $codi[0].$cod[0].$codi[1].$cod[1].$codi[2].$cod[2].$codi[3].$cod[3].$codi[4].$cod[4].$codi[5].$cod[5].$codi[6].$cod[6].$codi[7].$cod[7]. $codi[8].$cod[8].$codi[9].$cod[9].$codi[10].$cod[10];
	            	           if($codi[0]==$cod[0] && $codi[1]==$cod[1] && $codi[2]==$cod[2] && $codi[3]==$cod[3] && $codi[4]==$cod[4] && $codi[5]==$cod[5] && $codi[6]==$cod[6] && $codi[7]==$cod[7] && $codi[8]==$cod[8] && $codi[9]==$cod[9] && $codi[10]==$cod[10]){
	                              $est=true;
	                              break;
	            	          }else{
	            	          	 $est=false;
	            	          }
	                        }//fin foreach
	                        if($est==true){
	                           	//echo "entro agregar";
	                            $_SESSION["contador"]=$_SESSION["contador"]-1;
	            	          	$i=$this->Session->read("i")-1;
					            $this->Session->write("i",$i);
					            $this->set('errorMessage', 'Los códigos presupuestarios seleccionados ya existen en la lista');
                                //pr($_SESSION["items"]);
	                        	//$_SESSION["items"]=$_SESSION["items"]+$vec;

	                        }else{
	                        	//echo "hola";
	                        	$_SESSION["items"]=$_SESSION["items"]+$vec;
	                        }
						 }else{
							$_SESSION["items"]=$vec;
						 }

// pr($_SESSION["items"]);
        	break;
        	case 'nuevos':
                     $vec[$i][0]=$cod[0];
					 $vec[$i][1]=$this->AddCeroR($cod[1]);
					 $vec[$i][2]=$this->AddCeroR($cod[2]);
					 $vec[$i][3]=$this->AddCeroR($cod[3]);
					 $vec[$i][4]=$this->AddCeroR($cod[4]);
					 $vec[$i][5]=$this->AddCeroR($cod[5]);
					 $vec[$i][6]=$cod[6];
					 $vec[$i][7]=$this->AddCeroR($cod[7]);
					 $vec[$i][8]=$this->AddCeroR($cod[8]);
					 $vec[$i][9]=$this->AddCeroR($cod[9]);
					 $vec[$i][10]=$this->mascara_cuatro($cod[10]);
//					 $vec[$i][11]=$cod[11];
					 $vec[$i]["id"]=$i;
					 $disponible_partida = $this->disponibilidad($vec[$i][0], $vec[$i][1], $vec[$i][2], $vec[$i][3], $vec[$i][4], $vec[$i][5], $vec[$i][6],$vec[$i][7], $vec[$i][8], $vec[$i][9],$vec[$i][10]);
					 $monto_partida_array = $this->Formato1($vec[$i][11]);
					 if(isset($_SESSION["items"])){
						foreach($_SESSION["items"] as $codi){
							//echo $codi[0].$cod[0].$codi[1].$cod[1].$codi[2].$cod[2].$codi[3].$cod[3].$codi[4].$cod[4].$codi[5].$cod[5].$codi[6].$cod[6].$codi[7].$cod[7]. $codi[8].$cod[8].$codi[9].$cod[9].$codi[10].$cod[10];
            	           if($codi[0]==$cod[0] && $codi[1]==$cod[1] && $codi[2]==$cod[2] && $codi[3]==$cod[3] && $codi[4]==$cod[4] && $codi[5]==$cod[5] && $codi[6]==$cod[6] && $codi[7]==$cod[7] && $codi[8]==$cod[8] && $codi[9]==$cod[9] && $codi[10]==$cod[10]){
                              $est=true;
                              break;
            	          }else{
            	          	 $est=false;
            	          }
                        }//fin foreach
                        if($est==true){
                           //	echo "no";
            	          	$i=$this->Session->read("i")-1;
				            $this->Session->write("i",$i);
				            $this->set('errorMessage', 'Los códigos presupuestarios seleccionados ya existen en la lista');
                        }else{
                        	$_SESSION["items"]=$_SESSION["items"]+$vec;
                          //  echo "si";
                        }
					 }else{
						$_SESSION["items"]=$vec;
					 }

        	break;

        }//fin switch
		}//

}//fin funcu¡ions

function eliminar_items ($id) {
	$this->layout = "ajax";
	$_SESSION["items"][$id]=null;
	$monto_total=0;
	/*foreach($_SESSION ["items"] as $codigos){
       $monto_total=$monto_total+$this->Formato1($codigos[11]);
	}*/
	$this->set('total_partidas_rc',$monto_total);
    $_SESSION["contador"]=$_SESSION["contador"]-1;
}

function limpiar_lista () {
	$this->layout = "ajax";
	$this->Session->delete("items");
	$this->Session->delete("i");
	$this->Session->delete("contador");
}

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



function condicion(){
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

  return $condicion;

}

 function salir ($var=null) {
	$this->layout="ajax";
	echo"<script>menu_activo();</script>";

}

function salir_compromiso($num_rc=null){
	$this->layout="ajax";
	$ano=$this->ano_ejecucion();
	$resultado=$this->cepd01_compromiso_numero->execute("UPDATE  cepd01_compromiso_numero SET situacion=1 WHERE ".$this->SQLCA()." and numero_compromiso=".$num_rc." and ano_compromiso=".$ano);

     echo"<script>menu_activo();</script>";
}

}//fin class
?>
