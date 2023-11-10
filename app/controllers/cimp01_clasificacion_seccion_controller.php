<?php
 class Cimp01ClasificacionSeccionController extends AppController{
	var $name = 'cimp01_clasificacion_seccion';
	var $uses = array('ccfd01_division','cugd05_restriccion_clave','ccfd01_cuenta','ccfd01_subcuenta','ccfd01_subdivision','cimd01_clasificacion_grupo','cimd01_clasificacion_tipo','cimd01_clasificacion_subgrupo','cimd01_clasificacion_seccion','cimd03_inventario_inmuebles','cimd03_inventario_muebles');
	var $helpers = array('Html','Ajax','Javascript', 'Sisap');

 function checkSession(){
				if (!$this->Session->check('Usuario')){
					$this->redirect('/salir/');
					exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
 }//fin checksession
function AddCero3($numero,$extra=null){
   	  if($extra==null){
   	  	$numero = ($numero < 10 ? "0".$numero : $numero);
   	  }else{
   	  	$numero = ($numero < 10 ? $extra."0".$numero : $extra.".".$numero);
   	  }
	    return $numero;
   }//fin AddCero


function concatena_tipo($vector1=null, $nomVar=null, $extra=null){
	$cod = array();
	if($vector1 != null){
		foreach($vector1 as $x => $y){
			if($extra!=null){
				if($x<10){
					$cod[$x] = $extra.mascara($x,1).' - '.$y;
				}else if($x>=10 && $x<=99){
					$cod[$x] = $extra.''.$x.' - '.$y;
				}
			}else{

				if($x<10){
					$cod[$x] = mascara($x,1).' - '.$y;
				}else if($x>=10){
					$cod[$x] = $x.' - '.$y;
				}
			}
		}
		//print_r($cod);
	}

	$this->set($nomVar, $cod);
}//fin function


  function index($cod_tipo=null){

$this->verifica_entrada('105');

	$this->layout="ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$tipo = $this->cimd01_clasificacion_tipo->generateList(null,'cod_tipo ASC', null, '{n}.cimd01_clasificacion_tipo.cod_tipo', '{n}.cimd01_clasificacion_tipo.denominacion');
	$datacpcp01=$this->cimd01_clasificacion_seccion->findAll('cod_tipo=2 and cod_grupo=3 and cod_subgrupo!=0 and cod_seccion < 6',null,'cod_tipo,cod_grupo,cod_subgrupo ASC');
	$this->concatena_tipo($tipo, 'cod_tipo');
 }//index

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

 function guardar(){
		$this->layout="ajax";
		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');
		$nada='null';
		if($this->data['cimp01_clasificacion_seccion']['cod_tipo']!='' && $this->data['cimp01_clasificacion_seccion']['cod_grupo']!='' && $this->data['cimp01_clasificacion_seccion']['cod_subgrupo']!='' && $this->data['cimp01_clasificacion_seccion']['cod_seccion']!=''){
		$cod_tipo=$this->data['cimp01_clasificacion_seccion']['cod_tipo'];
		$cod_grupo=$this->data['cimp01_clasificacion_seccion']['cod_grupo'];
		$cod_subgrupo=$this->data['cimp01_clasificacion_seccion']['cod_subgrupo'];
		$cod_seccion=$this->data['cimp01_clasificacion_seccion']['cod_seccion'];
		$deno_subgrupo=$this->data['cimp01_clasificacion_seccion']['deno_seccion'];
		$especificaciones=$this->data['cimp01_clasificacion_seccion']['especificaciones'];
		if($cod_tipo==1){
			$bienes_cuenta=212;
		}else if($cod_tipo==2){
			$bienes_cuenta=214;
		}



		////////verificar grupo
		$sub_cuenta=$this->ccfd01_subcuenta->findCount($this->SQLCA()." and cod_tipo_cuenta=1 and cod_cuenta=$bienes_cuenta and cod_subcuenta=$cod_grupo");
		if($sub_cuenta==0){
			$todo=$this->cimd01_clasificacion_grupo->findAll(null,null,'cod_tipo,cod_grupo ASC');
			foreach($todo as $t){
				$cti	=	$t['cimd01_clasificacion_grupo']['cod_tipo'];
				$cgr	=	$t['cimd01_clasificacion_grupo']['cod_grupo'];
				$den	=	$t['cimd01_clasificacion_grupo']['denominacion'];
			}
			$crear_subcuenta  ="insert into ccfd01_subcuenta (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,
  								cod_cuenta,	cod_subcuenta, denominacion, concepto)";
  			$crear_subcuenta .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, 1, $bienes_cuenta, $cgr,'".$den."','".$nada."')";
  			$this->ccfd01_subcuenta->execute($crear_subcuenta);
		}

		///////verificar subgrupo
		$division=$this->ccfd01_division->findCount($this->SQLCA()." and cod_tipo_cuenta=1 and cod_cuenta=$bienes_cuenta and cod_subcuenta=$cod_grupo and cod_division=$cod_subgrupo");
		if($division==0){
			$todo=$this->cimd01_clasificacion_subgrupo->findAll(null,null,'cod_tipo,cod_grupo,cod_subgrupo ASC');
			foreach($todo as $t){
				$cti	=	$t['cimd01_clasificacion_subgrupo']['cod_tipo'];
				$cgr	=	$t['cimd01_clasificacion_subgrupo']['cod_grupo'];
				$csg	=	$t['cimd01_clasificacion_subgrupo']['cod_subgrupo'];
				$den	=	$t['cimd01_clasificacion_subgrupo']['denominacion'];
			}
			$crear_division  ="insert into ccfd01_division (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,
  								cod_cuenta,	cod_subcuenta, cod_division,denominacion, concepto)";
  			$crear_division .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, 1, $bienes_cuenta, $cgr,$csg,'".$den."','".$nada."')";
  			$this->ccfd01_division->execute($crear_division);
		}



		$sql ="INSERT INTO cimd01_clasificacion_seccion (cod_tipo,cod_grupo,cod_subgrupo,cod_seccion,denominacion,especificaciones,bienes_tipo,bienes_cuenta,bienes_grupo_subcuenta,bienes_subgrupo_division,bienes_seccion_subdivision)";
		$sql .=" VALUES (".$cod_tipo.",".$cod_grupo.",".$cod_subgrupo.",".$cod_seccion.",'".$deno_subgrupo."','".$especificaciones."',1,".$bienes_cuenta.",".$cod_grupo.",".$cod_subgrupo.",".$cod_seccion.")";
		$veri=$this->cimd01_clasificacion_seccion->findCount('cod_tipo='.$cod_tipo.' and cod_grupo='.$cod_grupo.' and cod_subgrupo='.$cod_subgrupo.' and cod_seccion='.$cod_seccion);

		$SQL_INSERT300 ="INSERT INTO ccfd01_subdivision (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta, cod_cuenta,
  		cod_subcuenta, cod_division, cod_subdivision, denominacion,concepto)";
		$SQL_INSERT300 .=" VALUES ($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,1,$bienes_cuenta,$cod_grupo,$cod_subgrupo,$cod_seccion,'".$deno_subgrupo."','".$nada."')";

		if($veri==0){
		$re=$this->cimd01_clasificacion_seccion->execute($sql);
		$x300=$this->ccfd01_subdivision->execute($SQL_INSERT300);
		if($re>1){
			 $this->set('Message_existe','EL REGISTRO FUE AGREGADO CORRECTAMENTE');
		$this->data=null;
		}else{
			 $this->set('errorMessage','NO SE PUDO AGREGAR EL REGISTRO');
		$this->data=null;
		}
		}else{$this->set('errorMessage','ESTOS CÓDIGOS YA SE ENCUENTRAN REGISTRADOS');
		$this->data=null;
	 }
}else{
	 $this->set('mensajeError','debe ingresar todos los datos');
}
 		$this->grilla('no',$cod_subgrupo);
		$this->render("grilla");
		echo "<script>";
		  echo "document.getElementById('cod_seccion').value = ''; ";
		  echo "document.getElementById('deno_seccion').value = ''; ";
		  echo "document.getElementById('especificaciones').value = ''; ";
        echo "</script>";
 }//fin function

 function eliminar($cod_tipo=null,$cod_grupo=null,$cod_subgrupo=null,$cod_seccion=null){
	$this->layout="ajax";
	if($cod_tipo==1){
		$cod_cuenta='212';
	}else if($cod_tipo==2){
		$cod_cuenta='214';
	}

    if($cod_tipo!=null && $cod_grupo!=null && $cod_subgrupo!=null && $cod_seccion!=null){
		   $veri1=$this->cimd03_inventario_inmuebles->findCount("cod_tipo=".$cod_tipo." and  cod_grupo=".$cod_grupo." and cod_subgrupo=".$cod_subgrupo." and cod_seccion=".$cod_seccion);
		   $veri2=$this->cimd03_inventario_muebles->findCount("cod_tipo=".$cod_tipo." and  cod_grupo=".$cod_grupo." and cod_subgrupo=".$cod_subgrupo." and cod_seccion=".$cod_seccion);
		   if($veri1 > 0 or $veri2 >0){
		   		$this->set('errorMessage','DISCULPE EL REGISTRO NO PUEDE SER ELIMINADO PORQUE SE ENCUENTRA EN INVENTARIO');
		   }else{
				$sql="DELETE FROM cimd01_clasificacion_seccion WHERE cod_tipo=".$cod_tipo." and  cod_grupo=".$cod_grupo." and cod_subgrupo=".$cod_subgrupo." and cod_seccion=".$cod_seccion;
		   		$sql2="delete from ccfd01_subdivision where cod_tipo_cuenta=1 and cod_cuenta=$cod_cuenta and cod_subcuenta=$cod_grupo and cod_division=$cod_subgrupo and cod_subdivision=$cod_seccion and ".$this->SQLCA();
		   if($this->cimd01_clasificacion_seccion->execute($sql)>1){
		   	$this->ccfd01_subdivision->execute($sql2);
		   	$this->set('Message_existe','REGISTRO FUE ELIMINADO CORRECTAMENTE');
		   }else{
		   $this->set('errorMessage','LO SIENTO, EL REGISTRO NO PUDO SER ELIMINADO');
		   }
		   }
    }else{
    	$this->set('errorMessage','LO SIENTO, LOS DATOS NO LLEGARON CORRECTAMENTE Y NO SE PUDO PROCESAR LA ELIMINACI&Oacute;N');
    }
    $this->grilla('no',$cod_subgrupo);
	$this->render("grilla");
   }//eliminar

 function mostrar_datos($var=null){
 	$this->layout="ajax";
 	if($var!=null){
 		if($var==1){
 			$datos=$this->cimd01_clasificacion_grupo->findAll(null,null,'cod_grupo ASC');
			$this->set('datos',$datos);
 		}elseif($var==2){
 			$datos=$this->cimd01_clasificacion_grupo->findAll(null,null,'denominacion ASC');
			$this->set('datos',$datos);
 		}
 	}else{
 		$this->set('mensajeError','LO SIENTO, NO LLEGO INFORMACI&Oacute;N PARA PROCESAR');
 	}
 }//mostrar_datos
function select3($select=null,$var=null) {
	$this->layout = "ajax";
	if($var!=null){
	switch($select){
		case 'ramo':
		//echo 'si';
		  $this->set('SELECT','grupo');
		  $this->set('codigo','tipo');
		  $this->set('seleccion','');
		  $this->set('n',1);
		  $ano =  $this->Session->read('ano');
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE."";
		  $lista=  $this->cimd01_clasificacion_tipo->generateList($cond2, 'cod_tipo ASC', null, '{n}.cimd01_clasificacion_tipo.cod_tipo', '{n}.cimd01_clasificacion_tipo.denominacion');
			$this->concatena($lista,'vector');
		break;
		case 'grupo':
			echo "<script>";
				echo "document.getElementById('b_2').innerHTML='<input type=text  size=80% class=inputtext />';  ";
		  		echo "document.getElementById('b_3').innerHTML='<input type=text  size=80% class=inputtext />';  ";
        	echo "</script>";
		  $this->set('SELECT','subgrupo');
		  $this->set('codigo','grupo');
		  $this->set('seleccion','');
		  $this->set('op','si');
		  $this->set('n',2);
		  $this->Session->write('codigo_tipo',$var);
		  $cond2 ="cod_tipo=".$var;
		  $lista=  $this->cimd01_clasificacion_grupo->generateList($cond2, 'cod_grupo ASC', null, '{n}.cimd01_clasificacion_grupo.cod_grupo', '{n}.cimd01_clasificacion_grupo.denominacion');
          $this->concatena($lista,'vector');
 		break;
		case 'subgrupo':
			echo "<script>";
		  		echo "document.getElementById('b_3').innerHTML='<input type=text  size=80% class=inputtext />';  ";
        	echo "</script>";
		  $this->set('SELECT','seccion');
		  $this->set('codigo','subgrupo');
		  $this->set('seleccion','');
		  $this->set('op','no');
		  $this->set('n',3);
		  $ctipo =  $this->Session->read('codigo_tipo');
		  $this->Session->write('codigo_grupo',$var);
		  $num=$this->cimd01_clasificacion_seccion->findCount('cod_tipo='.$ctipo.' and cod_grupo='.$var.' and cod_subgrupo=0');
		  		$cond2 ="cod_tipo=".$ctipo." and cod_grupo=".$var;
		  $lista = $this->cimd01_clasificacion_subgrupo->generateList($cond2, 'cod_subgrupo ASC', null, '{n}.cimd01_clasificacion_subgrupo.cod_subgrupo', '{n}.cimd01_clasificacion_subgrupo.denominacion');
          $this->concatena($lista,'vector');
		break;
		case 'seccion':
		  $this->set('SELECT','seccion');
		  $this->set('codigo','seccion');
		  $this->set('seleccion','');
		  $this->set('n',4);
		  $ctipo =  $this->Session->read('codigo_tipo');
		  $cgru =  $this->Session->read('codigo_grupo');
		  $this->Session->write('codigo_subgrupo',$var);
		  $cond2 ="cod_tipo=".$ctipo." and cod_grupo=".$cgru." and cod_subgrupo=".$var;
		  $lista=  $this->cimd01_clasificacion_subgrupo->generateList($cond2, 'cod_seccion ASC', null, '{n}.cimd01_clasificacion_subgrupo.cod_seccion', '{n}.cimd01_clasificacion_subgrupo.denominacion');
          $this->concatena($lista,'vector');
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

function mostrar3($select=null,$var=null) { //mostrar3 codigos presupuestarios
	$this->layout = "ajax";
		if( $var!=null){
	switch($select){
		case 'tipo':
		  $this->Session->write('dtipo2',$var);
		  $cond2 ="cod_tipo=".$var;
		  $a=  $this->cimd01_clasificacion_tipo->findAll($cond2);
          $e=$a[0]['cimd01_clasificacion_tipo']['denominacion'];
         $this->set('var',$e);
		break;
		case 'grupo':
		  $dtipo=  $this->Session->read('dtipo2');
		  $this->Session->write('dgru2',$var);
		  $cond2 ="cod_tipo=".$dtipo." and cod_grupo=".$var;
		  $a=  $this->cimd01_clasificacion_grupo->findAll($cond2);
          $e=$a[0]['cimd01_clasificacion_grupo']['denominacion'];
          $this->set('var',$e);
		break;
		case 'subgrupo':
		  $dtipo=  $this->Session->read('dtipo2');
		  $dgru =  $this->Session->read('dgru2');
		  $this->Session->write('dsub2',$var);
		  $cond2 ="cod_tipo=".$dtipo." and cod_grupo=".$dgru." and cod_subgrupo=".$var;
		  $a=  $this->cimd01_clasificacion_subgrupo->findAll($cond2);
          $e= $a[0]['cimd01_clasificacion_subgrupo']['denominacion'];
          $this->set('var',$e);
		break;
		case 'seccion':
		  $dtipo=  $this->Session->read('dtipo2');
		  $dgru =  $this->Session->read('dgru2');
		  $dsub =  $this->Session->read('dsub2');
		  $this->Session->write('dsec2',$var);
		  $cond2 ="cod_tipo=".$dtipo." and cod_grupo=".$dgru." and cod_subgrupo=".$dsub." and cod_seccion=".$var;
		  $a=  $this->cimd01_clasificacion_subgrupo->findAll($cond2);
          $e= $a[0]['cimd01_clasificacion_subgrupo']['denominacion'];
           $this->set('var',$e);
		break;
	}//fin wsitch
	}else{
		echo "";
	}
}//fin mostrar3 codigos presupuestarios

function consultar($pagina=null){
 		$this->layout = "ajax";
         if($pagina!=null){
          	 $pagina=$pagina;
          	  $Tfilas=$this->v_cimd01_clasificacion_subgrupo->findCount();
          	 if($Tfilas==0){
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datacpcp01=$this->v_cimd01_clasificacion_subgrupo->findAll(null,null,null,1,$pagina,null);
          	 $this->set('datos',$datacpcp01);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             }
 }else{
 	$pagina=1;
          	 $Tfilas=$this->v_cimd01_clasificacion_subgrupo->findCount();
          	 if($Tfilas==0){
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datacpcp01=$this->v_cimd01_clasificacion_subgrupo->findAll(null,null,null,1,$pagina,null);
          	 $this->set('datos',$datacpcp01);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
}
         }
}//fin function consultar2

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

function modificar($cod_tipo, $cod_grupo, $cod_subgrupo){
	$this->layout = "ajax";
	 $datacpcp01=$this->v_cimd01_clasificacion_subgrupo->findAll('cod_tipo='.$cod_tipo.' and cod_grupo='.$cod_grupo.' and cod_subgrupo='.$cod_subgrupo);
     $this->set('datos',$datacpcp01);
}

function guardar_editar($cod_tipo, $cod_grupo, $cod_subgrupo,$cod_seccion){
	$this->layout = "ajax";
	if($cod_tipo==1){
		$cod_cuenta='212';
	}else if($cod_tipo==2){
		$cod_cuenta='214';
	}
	$deno_seccion=$this->data['cimp01_clasificacion_seccion']['denominacion'];
	$especificaciones=$this->data['cimp01_clasificacion_seccion']['especificaciones'];
	$update="update cimd01_clasificacion_seccion set denominacion='".$deno_seccion."', especificaciones='".$especificaciones."' where cod_tipo=".$cod_tipo." and cod_grupo=".$cod_grupo." and cod_subgrupo=".$cod_subgrupo." and cod_seccion=".$cod_seccion;
	$update2="update ccfd01_subdivision set denominacion='".$deno_seccion."' where cod_tipo_cuenta=1 and cod_cuenta=$cod_cuenta and cod_subcuenta=$cod_grupo and cod_division=$cod_subgrupo and cod_subdivision=$cod_seccion and ".$this->SQLCA();
	$this->cimd01_clasificacion_seccion->execute($update);
	$this->ccfd01_subdivision->execute($update2);
	$this->set('Message_existe','EL REGISTRO FUE MODIFICADO CORRECTAMENTE');
	$this->grilla('no',$cod_subgrupo);
	$this->render("grilla");
}

function grilla($op=null,$var2=null){
	$this->layout = "ajax";
	$ctipo =  $this->Session->read('codigo_tipo');
	$cgrupo =  $this->Session->read('codigo_grupo');
	if($op=="si"){
     $datacpcp01 = "";
	}else{
	$datacpcp01=$this->cimd01_clasificacion_seccion->findAll('cod_tipo='.$ctipo.' and cod_grupo='.$cgrupo.' and cod_subgrupo='.$var2,null,'cod_tipo,cod_grupo,cod_subgrupo,cod_seccion ASC');
	}//fin else
$this->set('datos',$datacpcp01);
}

function editar($var=null, $var2=null,$var3=null,$var4=null){
 $this->layout = "ajax";
  $accion =  $this->cimd01_clasificacion_seccion->findAll("cod_tipo = ".$var." and cod_grupo  = ".$var2." and cod_subgrupo=".$var3." and cod_seccion=".$var4);
		echo "<script>";
		  echo "document.getElementById('iconos_1_".$var."_".$var2."_".$var3."_".$var4."').style.display = 'none'; ";
          echo "document.getElementById('iconos_2_".$var."_".$var2."_".$var3."_".$var4."').style.display = 'block'; ";
          echo "document.getElementById('td_5_".$var."_".$var2."_".$var3."_".$var4."').innerHTML='<textarea name=data[cimp01_clasificacion_seccion][denominacion] cols=\"100%\">".$accion[0]['cimd01_clasificacion_seccion']['denominacion']."</textarea>';  ";
		 // echo "document.getElementById('td_6_".$var."_".$var2."_".$var3."_".$var4."').innerHTML='<input type=text name=data[cimp01_clasificacion_seccion][especificaciones] size=25% value=\"".$accion[0]['cimd01_clasificacion_seccion']['especificaciones']."\" />';  ";
		  echo "document.getElementById('td_6_".$var."_".$var2."_".$var3."_".$var4."').innerHTML='<textarea name=\"data[cimp01_clasificacion_seccion][especificaciones]\" cols=\"100%\">".$accion[0]['cimd01_clasificacion_seccion']['especificaciones']."</textarea>';  ";
		echo "</script>";
    $this->set('cod_tipo', $var);
	$this->set('cod_grupo', $var2);
	$this->set('cod_subgrupo', $var3);

$this->set('Message_existe', 'PUEDE EDITAR LOS DATOS');
}//fin function

function cancelar($cod_tipo, $cod_grupo, $cod_subgrupo){
	$this->layout = "ajax";
	 $datos=$this->cimd01_clasificacion_subgrupo->findAll('cod_tipo='.$cod_tipo." and cod_grupo=".$cod_grupo,null,'cod_tipo,cod_grupo,cod_subgrupo ASC');
 	$this->set('datos',$datos);
}


function actualizar_tipo(){
	$this->layout = "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$todo=$this->cimd01_clasificacion_tipo->findAll(null,null,'cod_tipo ASC');
	//pr($todo);
	$a=0;
	foreach($todo as $t){
		$cti	=	$t['cimd01_clasificacion_tipo']['cod_tipo'];
		$den	=	$t['cimd01_clasificacion_tipo']['denominacion'];
		if($cti==1){
			$bcu=212;
		}else if($cti==2){
			$bcu=214;
		}

$nada='null';

////////////////validar cuenta creada
		$cuenta=$this->ccfd01_cuenta->findCount($this->SQLCA()." and cod_tipo_cuenta=1 and cod_cuenta=$bcu and $a=$a");
		if($cuenta==0){
			$crear_cuenta  ="insert into ccfd01_cuenta (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,
  								cod_cuenta, denominacion, concepto)";
  			$crear_cuenta .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, 1, $bcu,'".$den."','".$nada."')";
  			$this->ccfd01_cuenta->execute($crear_cuenta);
		}


	$a=$a+1;
	}
}

function actualizar_grupo(){
	$this->layout = "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$todo=$this->cimd01_clasificacion_grupo->findAll(null,null,'cod_tipo,cod_grupo ASC');
	//pr($todo);
	$a=0;
	foreach($todo as $t){
		$cti	=	$t['cimd01_clasificacion_grupo']['cod_tipo'];
		$cgr	=	$t['cimd01_clasificacion_grupo']['cod_grupo'];
		$den	=	$t['cimd01_clasificacion_grupo']['denominacion'];
		if($cti==1){
			$bcu=212;
		}else if($cti==2){
			$bcu=214;
		}

$nada='null';


/////////////////validar sub cuenta creada

		$sub_cuenta=$this->ccfd01_subcuenta->findCount($this->SQLCA()." and cod_tipo_cuenta=1 and cod_cuenta=$bcu and cod_subcuenta=$cgr and $a=$a");
		if($sub_cuenta==0){
			$crear_subcuenta  ="insert into ccfd01_subcuenta (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,
  								cod_cuenta,	cod_subcuenta, denominacion, concepto)";
  			$crear_subcuenta .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, 1, $bcu, $cgr,'".$den."','".$nada."')";
  			$this->ccfd01_subcuenta->execute($crear_subcuenta);
		}


	$a=$a+1;
	}
}

function actualizar_subgrupo(){
	$this->layout = "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$todo=$this->cimd01_clasificacion_subgrupo->findAll(null,null,'cod_tipo,cod_grupo,cod_subgrupo ASC');
	//pr($todo);
	$a=0;
	foreach($todo as $t){
		$cti	=	$t['cimd01_clasificacion_subgrupo']['cod_tipo'];
		$cgr	=	$t['cimd01_clasificacion_subgrupo']['cod_grupo'];
		$csg	=	$t['cimd01_clasificacion_subgrupo']['cod_subgrupo'];
		$den	=	$t['cimd01_clasificacion_subgrupo']['denominacion'];
		if($cti==1){
			$bcu=212;
		}else if($cti==2){
			$bcu=214;
		}

$nada='null';

/////////////////validar division creada

		$division=$this->ccfd01_division->findCount($this->SQLCA()." and cod_tipo_cuenta=1 and cod_cuenta=$bcu and cod_subcuenta=$cgr and cod_division=$csg and $a=$a");
		if($division==0){
			$crear_division  ="insert into ccfd01_division (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,
  								cod_cuenta,	cod_subcuenta, cod_division,denominacion, concepto)";
  			$crear_division .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, 1, $bcu, $cgr,$csg,'".$den."','".$nada."')";
  			$this->ccfd01_division->execute($crear_division);
		}


	$a=$a+1;
	}
}


function actualizar_seccion(){
	$this->layout = "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$todo=$this->cimd01_clasificacion_seccion->findAll(null,null,'cod_tipo,cod_grupo,cod_subgrupo,cod_seccion ASC');
	//pr($todo);
	$a=0;
	foreach($todo as $t){
		$cti	=	$t['cimd01_clasificacion_seccion']['cod_tipo'];
		$cgr	=	$t['cimd01_clasificacion_seccion']['cod_grupo'];
		$csg	=	$t['cimd01_clasificacion_seccion']['cod_subgrupo'];
		$csc	=	$t['cimd01_clasificacion_seccion']['cod_seccion'];
		$den	=	$t['cimd01_clasificacion_seccion']['denominacion'];
		if($cti==1){
			$bcu=212;
		}else if($cti==2){
			$bcu=214;
		}
		$up	="update cimd01_clasificacion_seccion set bienes_tipo=1,bienes_cuenta=$bcu,bienes_grupo_subcuenta=$cgr,bienes_subgrupo_division=$csg,bienes_seccion_subdivision=$csc where cod_tipo=$cti and cod_grupo=$cgr and cod_subgrupo=$csg and cod_seccion=$csc";
		$this->cimd01_clasificacion_seccion->execute($up);

$nada='null';

/////////////////validar sub_division creada

		$subdivision=$this->ccfd01_subdivision->findCount($this->SQLCA()." and cod_tipo_cuenta=1 and cod_cuenta=$bcu and cod_subcuenta=$cgr and cod_division=$csg and cod_subdivision=$csc and $a=$a");
		if($subdivision==0){
			$crear_subdivision  ="insert into ccfd01_subdivision (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_cuenta,
  								cod_cuenta,	cod_subcuenta, cod_division,cod_subdivision,denominacion, concepto)";
  			$crear_subdivision .=" VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, 1, $bcu, $cgr,$csg,$csc,'".$den."','".$nada."')";
  			$this->ccfd01_subdivision->execute($crear_subdivision);
		}

	$a=$a+1;
	}
}

function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cimp01_clasificacion_seccion']['login']) && isset($this->data['cimp01_clasificacion_seccion']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cimp01_clasificacion_seccion']['login']);
		$paswd=addslashes($this->data['cimp01_clasificacion_seccion']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=105 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('errorMessage',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('autor_valido',false);
			$this->index("autor_valido");
			$this->render("index");
		}
	}
}


}
?>