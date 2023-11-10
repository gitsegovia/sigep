<?php
/*
 * Creado el  30/10/2007 a las 12:03:17 PM
 *
 * Herramienta: EasyEclipse.
 *
 * Proyecto: SIGEP
 *
 */
 class Cimp05EquiposMantenimientoController extends AppController{
   var $name = 'cimp05_equipos_mantenimiento';
   var $uses = array('v_cimd05_equipos_mantenimiento_todo','cimd05_equipos_mantenimiento','cimd05_conservacion_tipo_repuestos','cimd05_conservacion_tipo_reparacion','v_cimd04_vehiculo_asegurado_todo','v_inventario_muebles_todo','v_buscar_muebles','cugd02_division','cimd03_inventario_muebles','cimd02_tipo_movimiento','cimd01_clasificacion_tipo','cimd01_clasificacion_seccion','cimd01_clasificacion_subgrupo','cimd01_clasificacion_grupo','cugd01_estados','cugd01_municipios','cugd01_parroquias','Cugd01_centropoblados','cugd02_dependencia','cugd02_coordinacion','cugd02_departamento','cugd02_direccion','cugd02_direccionsuperior','cugd02_division','cugd02_oficina','cugd02_institucion','cugd02_secretaria','cugd01_centropoblados');
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

  function zero($x=null){
	if($x != null){
		if($x<10){
			$x="0".$x;
		}else if($x>=10 && $x<=99){
			$x=$x;
		}
	}
	return $x;

}//fin zero



function concatena($vector1=null, $nomVar=null){

	if($vector1 != null){

		foreach($vector1 as $x => $y){

			$cod[$x] = $this->zero($x).' - '.$y;
		}
		//print_r($cod);

		$this->set($nomVar, $cod);

	}
}//fin concatena


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
     }

function AddCero3($numero,$extra=null){
   	  if($extra==null){
   	  	$numero = ($numero < 10 ? "0".$numero : $numero);
   	  }else{
   	  	$numero = ($numero < 10 ? $extra."0".$numero : $extra.".".$numero);
   	  }
	    return $numero;
   }//fin AddCero


 function index(){
 	$this->layout ="ajax";
 	$this->data=null;
 	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
  	$reparacion = $this->cimd05_conservacion_tipo_reparacion->generateList(null,'cod_reparacion ASC', null, '{n}.cimd05_conservacion_tipo_reparacion.cod_reparacion', '{n}.cimd05_conservacion_tipo_reparacion.denominacion');
	$this->concatena($reparacion, 'reparacion');
	$repuestos = $this->cimd05_conservacion_tipo_repuestos->generateList(null,'cod_repuesto ASC', null, '{n}.cimd05_conservacion_tipo_repuestos.cod_repuesto', '{n}.cimd05_conservacion_tipo_repuestos.denominacion');
	$this->concatena($repuestos, 'repuestos');
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

function buscar_pista ($pagina=null)
{
	$this->layout="ajax";
	if($pagina!=null){
		$pagina=$pagina;
	}else{
		$pagina=1;
	}
//print_r($this->data);
	if(isset($this->data['cimp05_equipos_mantenimiento']['pista']) && !empty($this->data["cimp05_equipos_mantenimiento2"]['pista'])){
         $this->data['cimp05_equipos_mantenimiento']['pista']=$this->data['cimp05_equipos_mantenimiento2']['pista'];
         $otra="si";
	}else{
	  $otra="no";
	}
	if((isset($this->data["cimp05_equipos_mantenimiento"]['pista']) && !empty($this->data['cimp05_equipos_mantenimiento']['pista'])) || $otra=="si"){
         $pista=strtoupper($this->data['cimp05_equipos_mantenimiento']['pista']);
         $cantidad_resultado=$this->v_inventario_muebles_todo->findCount("deno_seccion like '%".$pista."%'");
         $resultado=$this->v_inventario_muebles_todo->findAll("deno_seccion like '%".$pista."%'",null,null,1,$pagina);
         //pr($resultado);
         if($cantidad_resultado!=0){
           $this->set("cantidad_resultado",$cantidad_resultado);
           $this->set("resultado",$resultado);
           $this->set("pista",$pista);

           $this->set('siguiente',$pagina+1);
		   $this->set('actual',$pagina);
		   $this->bt_nav($cantidad_resultado,$pagina);
         }else{
           $this->set("cantidad_resultado",$cantidad_resultado);
           $this->set("resultado",array(0=>array("v_inventario_muebles_todo"=>array("cod_grupo"=>0,"cod_partida"=>0,"cod_generica"=>0,"cod_especifica"=>0,"cod_sub_espec"=>0,"cod_auxiliar"=>0,"concepto"=>"","denominacion"=>"No se encontraron datos para la pista indicada, ".$pista))));
           //$this->set("ano",$ano);
           $this->set("pista",$pista);
           $this->set('siguiente',$pagina+1);
		   $this->set('actual',$pagina);
           $this->bt_nav(1,1);
         }
         $this->set("MUESTRAME","");


	}else{
		if(!isset($this->data["cimp05_equipos_mantenimiento"]["pista"])){
			echo "<h4>Faltan Datos para las busqueda, por favor indique pista.</h4>";
		}
	}

}//fin buscar_pista

function reparaciones_anteriores ($pagina=null){
	$this->layout="ajax";
	//echo 'si llego a la funcion '.$pagina;
	if($pagina!=null){
		$pagina=$pagina;
	}else{
		$pagina=1;
	}
//print_r($this->data);
	if(isset($this->data['cimp05_equipos_mantenimiento']['pista'])){
         $pista=strtoupper($this->data['cimp05_equipos_mantenimiento']['pista']);
	}else if(isset($this->data['cimp05_equipos_mantenimiento5']['pista'])){
	  	 $pista=strtoupper($this->data['cimp05_equipos_mantenimiento5']['pista']);
	}
         //$pista=strtoupper($this->data['cimp05_equipos_mantenimiento']['pista']);
         //echo 'la pista es '.$pista;
         $cantidad_resultado=$this->v_inventario_muebles_todo->findCount("deno_seccion like '%".$pista."%'");
      //   echo 'la cantidad de resultado es '.$cantidad_resultado;
         $resultado=$this->v_inventario_muebles_todo->findAll("deno_seccion like '%".$pista."%'",null,null,1,$pagina);
      //   pr($resultado);
         if($cantidad_resultado!=0){
         //	echo 'si entro';

         	foreach($resultado as $row){
				$iden = $row['v_inventario_muebles_todo']['numero_identificacion'];
			//	echo $iden;
         	}
			$datos=$this->v_cimd05_equipos_mantenimiento_todo->findAll('numero_identificacion='.$iden);
	//		pr($datos);
			$this->set("datos",$datos);
           $this->set("cantidad_resultado",$cantidad_resultado);
           $this->set("resultado",$resultado);
           $this->set("pista",$pista);

           $this->set('siguiente',$pagina+1);
		   $this->set('actual',$pagina);
		   $this->bt_nav($cantidad_resultado,$pagina);
         }



}//fin buscar_pista


function guardar(){
$this->layout ="ajax";
//print_r($this->data);
		$cod_presi = $this->verifica_SS(1);
		$cod_entidad = $this->verifica_SS(2);
	 	$cod_tipo_inst = $this->verifica_SS(3);
	 	$cod_inst = $this->verifica_SS(4);
	 	$cod_dep = $this->verifica_SS(5);
	 	$cod_tipo = $this->Session->read('cod_tipo2');
		$cod_grupo = $this->Session->read('cod_grupo2');
		$cod_subgrupo = $this->Session->read('cod_subgrupo2');
		$cod_seccion = $this->Session->read('cod_seccion2');
		$numero_identificacion = $this->Session->read('numero_identificacion2');
	 //	$cod_tipo = $this->data['cimp05_equipos_mantenimiento']['cod_tipo'];
	 //	$cod_grupo = $this->data['cimp05_equipos_mantenimiento']['cod_grupo'];
	 //	$cod_subgrupo = $this->data['cimp05_equipos_mantenimiento']['cod_subgrupo'];
	 //	$cod_seccion = $this->data['cimp05_equipos_mantenimiento']['cod_seccion'];
	 //	$numero_identificacion = $this->data['cimp05_equipos_mantenimiento']['numero_identificacion'];
	 	$fe=$this->data['cimp05_equipos_mantenimiento']['fecha_reparacion'];
  		//$fe=$fe==""?"1900-01-01":$fe;
  		$dia=$fe[0].$fe[1];
  		$mes=$fe[3].$fe[4];
  		$ano=$fe[6].$fe[7].$fe[8].$fe[9];
  		$cod_reparacion=$this->data['cimp05_equipos_mantenimiento']['cod_reparacion'];
  		$cod_repuesto=$this->data['cimp05_equipos_mantenimiento']['cod_repuesto'];
  		$cantidad=$this->data['cimp05_equipos_mantenimiento']['cantidad'];
  		$costo_unitario=$this->Formato1($this->data['cimp05_equipos_mantenimiento']['costo_unitario']);
  		$tiempo_garantia=$this->data['cimp05_equipos_mantenimiento']['tiempo_garantia'];
  		$tienda_taller=$this->data['cimp05_equipos_mantenimiento']['tienda_taller'];
  		$tecnico_mecanico=$this->data['cimp05_equipos_mantenimiento']['tecnico_mecanico'];
  		$reparacion_efectuada=$this->data['cimp05_equipos_mantenimiento']['reparacion_efectuada'];

	 	//$fecha_reparacion = $this->data['cimp05_equipos_mantenimiento']['fecha_reparacion'];
		//echo 'ano'.$ano,'mes'.$mes,'disa'.$dia;
	 	$campos="cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo, cod_grupo, cod_subgrupo,
  				 cod_seccion, numero_identificacion, ano, mes, dia, cod_reparacion, cod_repuesto, cantidad,
  				 costo_unitario, tiempo_garantia, tienda_taller, tecnico_mecanico, reparacion_efecturada";
 		$sql    ="INSERT INTO  cimd05_equipos_mantenimiento ($campos)VALUES (
 				 $cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $cod_tipo, $cod_grupo, $cod_subgrupo,
  				 $cod_seccion, $numero_identificacion, $ano, $mes, $dia, $cod_reparacion, $cod_repuesto, $cantidad,
  				 $costo_unitario, '$tiempo_garantia', '$tienda_taller', '$tecnico_mecanico', '$reparacion_efectuada')";
		//echo $sql;
		$respuesta=$this->cimd05_equipos_mantenimiento->execute($sql);
		if($respuesta > 1){
		 $this->set('Message_existe', 'Registro creado con exito.');
		}else{
			$this->set('errorMessage', 'disculpe no se realizo la operaci&oacute;n');
		}
  $this->index();
  $this->render("index");//echo "no entro";
  $this->data=null;

}


function consultar($pagina=null){
 		$this->layout = "ajax";
         if($pagina!=null){
          	 $pagina=$pagina;
          	 $this->set('pagina',$pagina);
          	  $Tfilas=$this->v_cimd05_equipos_mantenimiento_todo->findCount();
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'No se encontrar&oacute;n datos');
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datacpcp01=$this->v_cimd05_equipos_mantenimiento_todo->findAll(null,null,'numero_identificacion,cod_reparacion,cod_repuesto ASC',1,$pagina,null);
          //		pr($datacpcp01);
          		$ni=$datacpcp01[0]['v_cimd05_equipos_mantenimiento_todo']['numero_identificacion'];
          		$datos2=$this->v_cimd05_equipos_mantenimiento_todo->findAll('numero_identificacion='.$ni);
				$this->set('datos2',$datos2);
          	// $cd01=$this->cpcd01->findAll();
        //  	echo $ni;
          	 $this->set('datos',$datacpcp01);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             }
 }else{
 	$pagina=1;
 	 $this->set('pagina',$pagina);
          	 $Tfilas=$this->v_cimd05_equipos_mantenimiento_todo->findCount();
          	 if($Tfilas==0){
          	 	$this->set('errorMessage', 'No se encontrar&oacute;n datos');
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datacpcp01=$this->v_cimd05_equipos_mantenimiento_todo->findAll(null,null,'numero_identificacion,cod_reparacion,cod_repuesto ASC',1,$pagina,null);
          	 $ni=$datacpcp01[0]['v_cimd05_equipos_mantenimiento_todo']['numero_identificacion'];
          		$datos2=$this->v_cimd05_equipos_mantenimiento_todo->findAll('numero_identificacion='.$ni);
				$this->set('datos2',$datos2);
          	 $this->set('datos',$datacpcp01);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
			 }
        }
}//fin function consultar2

function query($var=null){
	$this->layout="ajax";
	$this->set('tipo', $var);
}

function datos($tipo = null, $pista=null){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

	$pista2=strtoupper($pista);
	if($tipo=='numero_identificacion'){
		$busq="numero_identificacion=".$pista2;
	}else if($tipo=='denominacion'){
		$busq="upper($tipo) LIKE '%$pista2%'";
	}


	if($tipo!=null && $pista!=null){
				$exe="select * from v_inventario_muebles_todo where ".$busq;
				$datos=$this->v_inventario_muebles_todo->execute($exe);
				$this->set('datos', $datos);

	}

}

function preconsulta(){
	$this->layout="ajax";
	$opciones = array('numero_identificacion'=>'Numero de Identificacion', 'denominacion'=>'Denominacion del Bien');
	$this->set('opcion', $opciones);
}
function buscar_rif() {
	$this->layout="ajax";


}
function lista_encontrados($rif){
 		$this->layout = "ajax";
 		 $cond ="numero_identificacion='".$rif."'";//echo $cond;
 		 $num=$this->v_inventario_muebles_todo->findCount($cond);
 		 if($num==1){

          	 $datacpcp01=$this->v_inventario_muebles_todo->findAll($cond,null,'numero_identificacion ASC');
          	 $this->set('datos',$datacpcp01);
          	 print_r($datacpcp01);
 		 }else{//echo "no hay dato";
 		 	$this->set('errorMessage', 'No se encontrar&oacute;n datos');
 		 	$this->preconsulta();
 		 	$this->render("preconsulta");

	    	  // }
      }//fin function consultar2



}

function modificar($numero_identificacion=null,$cod_reparacion=null,$cod_repuesto=null,$pagina=null){
	$this->layout = "ajax";
	$data=$this->v_cimd05_equipos_mantenimiento_todo->findAll('numero_identificacion='.$numero_identificacion.' and cod_reparacion='.$cod_reparacion.' and cod_repuesto='.$cod_repuesto);
    $this->set('datos',$data);
    $reparacion = $this->cimd05_conservacion_tipo_reparacion->generateList(null,'cod_reparacion ASC', null, '{n}.cimd05_conservacion_tipo_reparacion.cod_reparacion', '{n}.cimd05_conservacion_tipo_reparacion.denominacion');
	$this->concatena($reparacion, 'reparacion');
	$repuestos = $this->cimd05_conservacion_tipo_repuestos->generateList(null,'cod_repuesto ASC', null, '{n}.cimd05_conservacion_tipo_repuestos.cod_repuesto', '{n}.cimd05_conservacion_tipo_repuestos.denominacion');
	$this->concatena($repuestos, 'repuestos');
	$this->set('pagina',$pagina);
				//$ni=$datacpcp01[0]['v_cimd05_equipos_mantenimiento_todo']['numero_identificacion'];
    $datos2=$this->v_cimd05_equipos_mantenimiento_todo->findAll('numero_identificacion='.$numero_identificacion);
	$this->set('datos2',$datos2);

}

function guardar_modificar($numero_identificacion=null,$cod_reparacion2=null,$cod_repuesto2=null,$pagina=null){
	$this->layout = "ajax";
	//$numero_identificacion = $this->data['cimp05_equipos_mantenimiento']['numero_identificacion'];
	 	$fe=$this->data['cimp05_equipos_mantenimiento']['fecha_reparacion'];
  		//$fe=$fe==""?"1900-01-01":$fe;
  		$dia=$fe[0].$fe[1];
  		$mes=$fe[3].$fe[4];
  		$ano=$fe[6].$fe[7].$fe[8].$fe[9];
  		$cod_reparacion=$this->data['cimp05_equipos_mantenimiento']['cod_reparacion'];
  		$cod_repuesto=$this->data['cimp05_equipos_mantenimiento']['cod_repuesto'];
  		$cantidad=$this->data['cimp05_equipos_mantenimiento']['cantidad'];
  		$costo_unitario=$this->Formato1($this->data['cimp05_equipos_mantenimiento']['costo_unitario']);
  		$tiempo_garantia=$this->data['cimp05_equipos_mantenimiento']['tiempo_garantia'];
  		$tienda_taller=$this->data['cimp05_equipos_mantenimiento']['tienda_taller'];
  		$tecnico_mecanico=$this->data['cimp05_equipos_mantenimiento']['tecnico_mecanico'];
  		$reparacion_efectuada=$this->data['cimp05_equipos_mantenimiento']['reparacion_efectuada'];



	 $sql="update cimd05_equipos_mantenimiento set cod_reparacion=".$cod_reparacion.",cod_repuesto=".$cod_repuesto.", ano=".$ano.", mes=".$mes.", dia=".$dia.", cantidad=".$cantidad.", costo_unitario=".$costo_unitario.", tiempo_garantia='".$tiempo_garantia."', tecnico_mecanico='".$tecnico_mecanico."', tienda_taller='".$tienda_taller."', reparacion_efecturada='".$reparacion_efectuada."' where numero_identificacion=".$numero_identificacion." and cod_reparacion=".$cod_reparacion2." and cod_repuesto=".$cod_repuesto2."";
     $vvv=$this->cimd05_equipos_mantenimiento->execute($sql);
     $this->data=null;
     $this->set('Message_existe', 'Registro Modificado con exito.');
	 $this->consultar($pagina);
	 $this->render("consultar");
}

 function eliminar($identificacion=null,$cod_reparacion=null,$cod_repuesto=null,$pagina=null){
 	$this->layout = "ajax";
       if(isset($identificacion)){
 	$cond=" numero_identificacion =".$identificacion." and cod_reparacion=".$cod_reparacion." and cod_repuesto=".$cod_repuesto." and ".$this->SQLCA();
 	//echo $cond;
 	$this->cimd05_equipos_mantenimiento->execute("DELETE FROM cimd05_equipos_mantenimiento  WHERE ".$cond);
 	$y=$this->cimd05_equipos_mantenimiento->findCount($this->SQLCA());
 	if($pagina>$y){
 		$pagina=$pagina-1;
 	}

	  if($y!=0){
	  	 $this->set('Message_existe', 'Registro Eliminado con exito.');
      	 $this->consultar($pagina);//si es el primero solamente
      $this->render("consultar");

		}else if($y==0){
			$this->set('Message_existe', 'Registro Eliminado con exito.');
			$this->index();
      		$this->render("index");
		}//fin if
 }

 }

function funcion($var1=null, $var2=null, $var3=null){

$this->layout="ajax";


}//fin function

function codi_repa($codigo){
	$this->layout = "ajax";
	$a = $this->cimd05_conservacion_tipo_reparacion->findAll("cod_reparacion=".$codigo,array('cod_reparacion','denominacion'));
    $this->set("a",$a[0]['cimd05_conservacion_tipo_reparacion']['cod_reparacion']);
}//fin cpcp02_codigo

function deno_repa($codigo){
	$this->layout = "ajax";
	$b = $this->cimd05_conservacion_tipo_reparacion->findAll("cod_reparacion=".$codigo,array('cod_reparacion','denominacion'));
	$this->set("b",$b[0]['cimd05_conservacion_tipo_reparacion']['denominacion']);


}//fin cpcp02_denominacion

function codi_repu($codigo){
	$this->layout = "ajax";
	$a = $this->cimd05_conservacion_tipo_repuestos->findAll("cod_repuesto=".$codigo,array('cod_repuesto','denominacion'));
    $this->set("a",$a[0]['cimd05_conservacion_tipo_repuestos']['cod_repuesto']);
}//fin cpcp02_codigo

function deno_repu($codigo){
	$this->layout = "ajax";
	$b = $this->cimd05_conservacion_tipo_repuestos->findAll("cod_repuesto=".$codigo,array('cod_repuesto','denominacion'));
	$this->set("b",$b[0]['cimd05_conservacion_tipo_repuestos']['denominacion']);


}//fin cpcp02_denominacion

function buscar_mueble($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function
function buscar_por_pista($var1=null, $var2=null, $var3=null){
$this->layout="ajax";

    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					if(is_int($var2)){$sql   = " (denominacion LIKE '%$var2%')  or   ";}else{ $sql = "";}
					$Tfilas=$this->v_inventario_muebles_todo->findCount($sql." (denominacion LIKE '%$var2%')");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_inventario_muebles_todo->findAll($sql." (denominacion LIKE '%$var2%')   ",null,"numero_identificacion ASC",100,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);
						if(is_int($var22)){$sql   = " (denominacion LIKE '%$var22%')  or   ";}else{ $sql = "";}
						$Tfilas=$this->v_inventario_muebles_todo->findCount($sql." (denominacion LIKE '%$var22%')");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_inventario_muebles_todo->findAll($sql." (denominacion LIKE '%$var22%')  ",null,"numero_identificacion ASC",100,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else
$this->set("opcion",$var1);
}//fin function

function seleccion_busqueda_venta($cod_tipo=null, $cod_grupo=null, $cod_subgrupo=null, $cod_seccion=null, $var1=null){
$this->layout="ajax";

//$this->Session->write('cod_tipo1',$var1);
//$this->Session->write('cod_grupo1',$var2);
//$this->Session->write('cod_subgrupo1',$var3);
//$this->Session->write('cod_seccion1',$var4);
// $cod_seccion = $this->Session->read('cod_seccion1');

$resultado=$this->v_inventario_muebles_todo->findAll("cod_tipo=$cod_tipo and cod_grupo=$cod_grupo and cod_subgrupo=$cod_subgrupo and cod_seccion='$cod_seccion' and numero_identificacion=".$var1);

$a=$resultado[0]['v_inventario_muebles_todo']['cod_tipo'];
$b=$this->AddCeroR($resultado[0]['v_inventario_muebles_todo']['cod_grupo']);
$c=$this->AddCeroR($resultado[0]['v_inventario_muebles_todo']['cod_subgrupo']);
$d=mascara_tres($resultado[0]['v_inventario_muebles_todo']['cod_seccion']);

$this->Session->write('cod_tipo2',$a);
$this->Session->write('cod_grupo2',$b);
$this->Session->write('cod_subgrupo2',$c);
$this->Session->write('cod_seccion2',$d);
$this->Session->write('numero_identificacion2',$resultado[0]['v_inventario_muebles_todo']['numero_identificacion']);

					echo "<script>";
					    echo "document.getElementById('cod_tipo').value='".$a."';   ";
					    echo "document.getElementById('deno_tipo').value='".$resultado[0]['v_inventario_muebles_todo']['deno_tipo']."';   ";
					    echo "document.getElementById('cod_grupo').value='".$b."';   ";
					    echo "document.getElementById('deno_grupo').value='".$resultado[0]['v_inventario_muebles_todo']['deno_grupo']."';   ";
					    echo "document.getElementById('cod_subgrupo').value='".$c."';   ";
					    echo "document.getElementById('deno_subgrupo').value='".$resultado[0]['v_inventario_muebles_todo']['deno_subgrupo']."';   ";
					    echo "document.getElementById('cod_seccion').value='".$d."';   ";
					    echo "document.getElementById('deno_seccion').value='".$resultado[0]['v_inventario_muebles_todo']['deno_seccion']."';   ";
					    //echo "document.getElementById('especificaciones').value='".$resultado[0]['v_inventario_muebles_todo']['especificaciones']."';" ;
					  	echo "document.getElementById('denominacion').value='".$resultado[0]['v_inventario_muebles_todo']['denominacion']."';   ";
					    echo "document.getElementById('numero_identificacion').value='".$this->mascara_ocho($resultado[0]['v_inventario_muebles_todo']['numero_identificacion'])."';   ";
					    echo "document.getElementById('costo_equipo').value='".$this->Formato2($resultado[0]['v_inventario_muebles_todo']['valor_unitario'])."';   ";
					    //echo "document.getElementById('segunda_ventana').disabled=false";
					echo "</script>";
//$this->funcion();
//$this->render("funcion");
$datos=$this->v_cimd05_equipos_mantenimiento_todo->findAll("cod_tipo=$cod_tipo and cod_grupo=$cod_grupo and cod_subgrupo=$cod_subgrupo and cod_seccion=$cod_seccion and numero_identificacion=".$var1);
$this->set('datos',$datos);
//echo 'si llego';
$this->set('numero',$var1);

}//fin function

function buscar($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function

function buscar_por_pista2($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
//$tipo_nomina=   $this->Session->read('tipo_nomina');
    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					if(is_numeric($var2)){$sql   = " (numero_identificacion::text LIKE '%$var2%') or ";}else{ $sql = "";}
					$Tfilas=$this->v_cimd05_equipos_mantenimiento_todo->findCount($sql."(denominacion LIKE '%$var2%')");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_cimd05_equipos_mantenimiento_todo->findAll($sql."(denominacion LIKE '%$var2%')",null,"numero_identificacion,cod_reparacion,cod_repuesto ASC",100,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);
						if(is_numeric($var22)){$sql   = " (numero_identificacion::text LIKE '%$var2%') or ";}else{ $sql = "";}
						$Tfilas=$this->v_cimd05_equipos_mantenimiento_todo->findCount($sql."(denominacion LIKE '%$var2%')");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_cimd05_equipos_mantenimiento_todo->findAll($sql."(denominacion LIKE '%$var2%')",null,"numero_identificacion,cod_reparacion,cod_repuesto ASC",100,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else
$this->set("opcion",$var1);
}//fin function
function consulta2($numero=null,$reparacion=null,$repuesto=null){
 		$this->layout = "ajax";
 	  $cod_presi = $this->Session->read('SScodpresi');
	  $cod_entidad = $this->Session->read('SScodentidad');
	  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	  $cod_inst = $this->Session->read('SScodinst');
	  $cod_dep = $this->Session->read('SScoddep');
	  $modulo = $this->Session->read('Modulo');
	  $c = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep." and numero_identificacion=".$numero." and cod_reparacion=".$reparacion." and cod_repuesto=".$repuesto;
          	 $veri=$this->v_cimd05_equipos_mantenimiento_todo->findCount($c);
          	 if($veri > 0){
          	 $datacpcp01=$this->v_cimd05_equipos_mantenimiento_todo->findAll($c);
          	 $this->set('datos',$datacpcp01);
          	 $datos2=$this->v_cimd05_equipos_mantenimiento_todo->findAll('numero_identificacion='.$numero);
			 $this->set('datos2',$datos2);
          	 }else{
				$this->index();
				$this->render("index");
				//$this->set('numero',$numero);
          	 }
}//fin function sr2




function infomacion_faltante($var1=null, $var2=null){

$this->layout = "ajax";

$var3 = "";

		switch($var1){
                case "reparacion":{        $this->set('userTable', $this->requestAction('/cimp05_conservacion_tipo_reparacion/', array('return')));  }break;
                case "repuesto":{       $this->set('userTable', $this->requestAction('/cimp05_conservacion_tipo_repuestos/', array('return')));  }break;
		 }//fin

$this->set('opcion',     $var1);
$this->set('capa',       $var2);
$this->set('controlador',$var3);

}//fin function








function select_cambio($var1=null, $var2=null, $var3=null){

$this->layout = "ajax";

	switch($var1){
                case "reparacion":{
                	$reparacion = $this->cimd05_conservacion_tipo_reparacion->generateList(null,'cod_reparacion ASC', null, '{n}.cimd05_conservacion_tipo_reparacion.cod_reparacion', '{n}.cimd05_conservacion_tipo_reparacion.denominacion');
					$this->concatena($reparacion, 'lista');
					$this->set("name", "sel_reparacion");
                }break;

                case "repuesto":{
                	 $repuestos = $this->cimd05_conservacion_tipo_repuestos->generateList(null,'cod_repuesto ASC', null, '{n}.cimd05_conservacion_tipo_repuestos.cod_repuesto', '{n}.cimd05_conservacion_tipo_repuestos.denominacion');
					 $this->concatena($repuestos, 'lista');
	                 $this->set("name", "sel_repuesto");
                }break;
$this->set('opcion',     $var1);

	}
}//fin function


function imagen($numero){
	$this->layout = "ajax";
	$this->set('numero_identificacion',$numero);
}
}//fin de la clase controller
?>