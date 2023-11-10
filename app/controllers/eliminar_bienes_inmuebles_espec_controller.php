<?php
/*
 * Creado el  30/10/2007 a las 12:03:17 PM
 *
 * Herramienta: EasyEclipse.
 *
 * Proyecto: SIGEP
 *
 */
 class EliminarBienesInmueblesEspecController extends AppController {
   var $name = 'eliminar_bienes_inmuebles_espec';
   var $uses = array('ccfd04_cuentas_enlace','ccfd02','ccfd10_descripcion','ccfd10_detalles','cimd03_inventario_numero','ccfd05_numero_asiento','arrd05','ccfd04_cierre_mes','v_inventario_inmuebles_todo','v_buscar_inmuebles','cimd03_inventario_inmuebles','cimd01_clasificacion_seccion');
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
function SQLCA_noDEP(){//sql para busqueda de codigos de arranque con y sin año
				 $sql_re = "cod_presi=1  and    ";
				 $sql_re .= "cod_entidad=11  and  ";
				 $sql_re .= "cod_tipo_inst=30  and ";
				 $sql_re .= "cod_inst=11 ";

				 return $sql_re;
		}//fin funcion SQLCA

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

        function SQLCX($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)." ";
		 return $sql_re;


    }//fin funcion SQLX

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


 function index($numero=null){
 	$this->layout ="ajax";
 	$this->data=null;
 	$nom = $this->arrd05->generateList($this->condicionNDEP(), 'cod_dep ASC', null, '{n}.arrd05.cod_dep', '{n}.arrd05.denominacion');
    $this->concatena($nom, 'arr05');
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


function guardar(){
$this->layout ="ajax";
 		$cod_presi      = $this->Session->read('SScodpresi');
  		$cod_entidad    = $this->Session->read('SScodentidad');
  		$cod_tipo_inst  = $this->Session->read('SScodtipoinst');
  		$cod_inst       = $this->Session->read('SScodinst');
 		$ano_ejecucion  = $this->Session->read('ano_ejecucion');
    	$cod_dep   		=  $this->data['datos']['cod_dep'];
    	$cod_grupo   	=  $this->data['datos']['cod_grupo'];
    	$cod_subgrupo   =  $this->data['datos']['cod_subgrupo'];
    	$cod_seccion   	=  $this->data['datos']['cod_seccion'];
    	$num_ide   		=  $this->data['datos']['numero_identificacion'];
        $condicion  = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;
		$condicion .= " and cod_tipo=1 and cod_grupo=".$cod_grupo." and cod_subgrupo=".$cod_subgrupo." and cod_seccion=".$cod_seccion." and numero_identificacion=".$num_ide;
        $datos=$this->v_inventario_inmuebles_todo->findAll($condicion);


        foreach($datos as $row){


			  $cod_tipo               = $row['v_inventario_inmuebles_todo']['cod_tipo'];
			  $deno_tipo              = $row['v_inventario_inmuebles_todo']['deno_tipo'];
			  $cod_grupo              = $row['v_inventario_inmuebles_todo']['cod_grupo'];
			  $deno_grupo             = $row['v_inventario_inmuebles_todo']['deno_grupo'];
			  $cod_subgrupo           = $row['v_inventario_inmuebles_todo']['cod_subgrupo'];
			  $cod_seccion            = $row['v_inventario_inmuebles_todo']['cod_seccion'];
			  $numero_identificacion  = $row['v_inventario_inmuebles_todo']['numero_identificacion'];
			  $denominacion           = $row['v_inventario_inmuebles_todo']['denominacion_inmueble'];
			  $avaluo_actual          = $row['v_inventario_inmuebles_todo']['avaluo_actual'];
			  $fecha_incorporacion    = cambiar_formato_fecha($row['v_inventario_inmuebles_todo']['fecha_incorporacion']);


					        $aux_datos = $this->cimd01_clasificacion_seccion->findAll(" cod_tipo='".$cod_tipo."' and cod_grupo='".$cod_grupo."' and cod_subgrupo='".$cod_subgrupo."' and cod_seccion='".$cod_seccion."'");

					        $parametro_bienes_aux["denominacion"]            = $denominacion;
					        $parametro_bienes_aux["numero_identificacion"]   = $numero_identificacion;
					        $parametro_bienes_aux["fecha_identificacion"]    = $fecha_incorporacion;
					        $parametro_bienes_aux["concepto"]                = "ELIMINACIÒN";
							$parametro_bienes_aux["monto"]                   = $avaluo_actual;

					        $parametro_bienes_aux["cod_tipo_cuenta"]         = 1;
					        $parametro_bienes_aux["cod_cuenta"]              = 212;
					        $parametro_bienes_aux["cod_subcuenta"]           = $cod_grupo;
					        $parametro_bienes_aux["cod_division"]            = 0;
					        $parametro_bienes_aux["cod_subdivision"]         = 0;

					        $parametro_bienes_aux["cod_tipo"]              = $cod_tipo;
					        $parametro_bienes_aux["cod_grupo"]             = $cod_grupo;
					        $parametro_bienes_aux["cod_subgrupo"]          = $cod_subgrupo;
					        $parametro_bienes_aux["cod_seccion"]           = $cod_seccion;


												             $valor_motor_contabilidad = $this->motor_contabilidad_fiscal(
																													      $to      = 2,
																													      $td      = 17,
																													      $rif_doc = null,
																													      $ano_dc  = $this->ano_ejecucion(),
																													      $n_dc    = $numero_identificacion,
																													      $f_dc    = date('d/m/Y'),
																													      $cpt_dc  = null,
																													      $ben_dc  = null,
																													      $mon_dc  = array(),

																													      $ano_op   = null,
																													      $n_op     = null,
																													      $f_op     = null,

																													      $a_adj_op = null,
																													      $n_adj_op = null,
																													      $f_adj_op = null,
																													      $tp_op    = null,

																													      $deno_ban_pago  = null,
																													      $ano_movimiento = null,
																													      $cod_ent_pago   = null,
																													      $cod_suc_pago   = null,
																													      $cod_cta_pago   = null,

																													      $num_che_o_debi  = null,
																													      $fec_che_o_debi  = null,
																													      $clas_che_o_debi = null,
																													      $tipo_che_o_debi = null,

																													      $ano_dc_array_pago    = array(),
																													      $n_dc_array_pago      = array(),
																													      $n_dc_adj_array_pago  = array(),
																													      $f_dc_array_pago      = array(),

																													      $ano_op_array_pago  = array(),
																													      $n_op_array_pago    = array(),
																													      $f_op_array_pago    = array(),
																													      $tipo_op_array_pago = array(),
																													      $tipo_modificacion  = null,
																													      $f_dc_adj_array_pago= array(),
																													      $parametro_bienes   = $parametro_bienes_aux
																													  );

															    if($valor_motor_contabilidad==true){
															    	    $cond=" numero_identificacion =".$numero_identificacion." and ".$condicion;
                                                                 	    $this->cimd03_inventario_inmuebles->execute("DELETE FROM cimd03_inventario_inmuebles  WHERE ".$cond." and cod_tipo=".$cod_tipo." and cod_grupo=".$cod_grupo." and cod_subgrupo=".$cod_subgrupo." and cod_seccion=".$cod_seccion);
																	    $this->cimd03_inventario_numero->execute("COMMIT;");
																}else{
																	    $this->cimd03_inventario_numero->execute("ROLLBACK;");
																}//fin else

        }//fin foreach


$this->set('Message_existe', 'Registro eliminado con exito.');

  $this->index();
  $this->render("index");
  $this->data=null;


}//fin function

function funcion_dep($var=null){
$this->layout ="ajax";

	$r = $this->arrd05->findAll($this->condicionNDEP().'and cod_dep='.$var);
	$c = $r[0]['arrd05']['denominacion'];
	$this->Session->delete('depe_eli_b');
	$this->Session->write('depe_eli_b', $var);

	echo "<script>";
		echo "document.getElementById('a').value='".mascara($var,4)."';   ";
		echo "document.getElementById('b').value='".$c."';   ";
		echo "document.getElementById('primera_ventana').disabled=false;";
	echo "</script>";



}//fin function


function buscar($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function

function buscar_por_pista($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
$depe = $this->Session->read('depe_eli_b');
    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					$Tfilas=$this->v_inventario_inmuebles_todo->findCount(" (buscar LIKE '%$var2%') and ".$this->condicionNDEP().' and cod_dep='.$depe.'and cod_tipo_desincorporacion=0');
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
								//echo $Tfilas;
					     	    $datos_filas=$this->v_inventario_inmuebles_todo->findAll(" (buscar LIKE '%$var2%') and ".$this->condicionNDEP().' and cod_dep='.$depe.'and cod_tipo_desincorporacion=0',null,"numero_identificacion ASC",100,1,null);
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
						$Tfilas=$this->v_inventario_inmuebles_todo->findCount(" (buscar LIKE '%$var22%') and ".$this->condicionNDEP().' and cod_dep='.$depe.'and cod_tipo_desincorporacion=0');
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_inventario_inmuebles_todo->findAll(" (buscar LIKE '%$var22%') and ".$this->condicionNDEP().' and cod_dep='.$depe.'and cod_tipo_desincorporacion=0',null,"numero_identificacion ASC",100,$pagina,null);
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

function seleccion_busqueda_venta($var5=null,$var1=null, $var2=null, $var3=null,$var4=null){
$this->layout="ajax";
$resultado = $this->v_buscar_inmuebles->findAll('cod_tipo='.$var1.' and cod_grupo='.$var2.' and cod_subgrupo='.$var3.' and cod_seccion='.$var4);
$a = $this->AddCeroR($resultado[0]['v_buscar_inmuebles']['cod_tipo']);
$b = $this->AddCeroR($resultado[0]['v_buscar_inmuebles']['cod_grupo']);
$c = $this->AddCeroR($resultado[0]['v_buscar_inmuebles']['cod_subgrupo']);
$d = mascara_tres($resultado[0]['v_buscar_inmuebles']['cod_seccion']);

					echo "<script>";
					    echo "document.getElementById('cod_tipo').value='".$a."';   ";
					    echo "document.getElementById('deno_tipo').value='".$resultado[0]['v_buscar_inmuebles']['deno_tipo']."';   ";
					    echo "document.getElementById('cod_grupo').value='".$b."';   ";
					    echo "document.getElementById('deno_grupo').value='".$resultado[0]['v_buscar_inmuebles']['deno_grupo']."';   ";
					    echo "document.getElementById('cod_subgrupo').value='".$c."';   ";
					    echo "document.getElementById('deno_subgrupo').value='".$resultado[0]['v_buscar_inmuebles']['deno_grupo']."';   ";
					    echo "document.getElementById('cod_seccion').value='".$d."';   ";
					    echo "document.getElementById('deno_seccion').value='".$resultado[0]['v_buscar_inmuebles']['deno_grupo']."';   ";
					    echo "document.getElementById('especificaciones').value='N/A';   ";
					    echo "document.getElementById('numero_identificacion').value='".mascara($var5,8)."';   ";
					echo "</script>";
$this->funcion();
$this->render("funcion");



}//fin function
function funcion($var1=null, $var2=null, $var3=null){

$this->layout="ajax";


}//fin function
 }
?>