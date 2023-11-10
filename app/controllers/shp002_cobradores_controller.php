<?php
 class Shp002CobradoresController extends AppController{
	var $uses = array('shd700_credito_vivienda','shd600_aprobacion_arrendamiento','shd400_propiedad','shd300_propaganda','shd003_codigo_ingresos','shd002_cobradores','shd100_patente','shd200_vehiculos');
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
}//fin before filter




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


    function SQLCAX($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_republica=".$this->verifica_SS(1)."  and    ";
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

		$this->set($nomVar, $cod);

	}
}//fin concatena

function index($var=null){
	$this->layout = "ajax";
}//fin index

function guardar(){
	$this->layout = "ajax";
	$cod_presi=$this->verifica_SS(1);
  	$cod_entidad=$this->verifica_SS(2);
  	$cod_tipo_inst=$this->verifica_SS(3);
  	$cod_inst=$this->verifica_SS(4);
  	$cod_dep=$this->verifica_SS(5);
	$rif_cedula=$this->data['shp002_cobradores']['rif_cedula'];
	$personalidad=$this->data['shp002_cobradores']['personalidad'];
	$nombre_razon=$this->data['shp002_cobradores']['nombre_razon'];
	$recurso=$this->data['shp002_cobradores']['recurso'];
	$condicion=$this->data['shp002_cobradores']['condicion'];
	$fecha_ingreso=$this->data['shp002_cobradores']['fecha_ingreso'];
	$fecha_ingreso=$this->Cfecha($fecha_ingreso,'A-M-D');
	$SQL_INSERT ="INSERT INTO shd002_cobradores (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif_ci,
  				  personalidad, nombre_razon, fecha_ingreso, recurso_cobro, condicion_actividad)";
	$SQL_INSERT .=" VALUES ($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,'".$rif_cedula."',$personalidad,'".$nombre_razon."','".$fecha_ingreso."',$recurso,$condicion)";
    $resp=$this->shd002_cobradores->execute($SQL_INSERT);
	if($resp>1){
 		$this->set('Message_existe', 'Registro Agregado con exito.');
		$this->index();
		$this->render("index");
  	}else if ($resp <= 1){
  		$this->set('errorMessage', 'Disculpe, El Registro no fue creado.');
		$this->index();
		$this->render("index");
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

function consulta($pagina=null){
	$this->layout = "ajax";
    if($pagina!=null){
    	$pagina=$pagina;
        $Tfilas=$this->shd002_cobradores->findCount($this->SQLCA());
        if($Tfilas==0){
        	$this->set('errorMessage', 'No existen datos');
          	$this->index();
          	$this->render("index");
        }
        if($Tfilas!=0){
        	$this->set('pagina',$pagina);
          	$this->set('pag_cant',$pagina.'/'.$Tfilas);
          	$datacpcp01=$this->shd002_cobradores->findAll($this->SQLCA(),null,'rif_ci ASC',1,$pagina,null);
          	$this->set('datos',$datacpcp01);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
        }
 	}else{
 		$pagina=1;
        $Tfilas=$this->shd002_cobradores->findCount($this->SQLCA());
        if($Tfilas==0){
        	$this->set('errorMessage', 'No existen datos');
          	$this->index();
          	$this->render("index");
        }
        if($Tfilas!=0){
        	$this->set('pagina',$pagina);
          	$this->set('pag_cant',$pagina.'/'.$Tfilas);
          	$datacpcp01=$this->shd002_cobradores->findAll($this->SQLCA(),null,'rif_ci ASC',1,$pagina,null);
          	$this->set('datos',$datacpcp01);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
		}
	}
}//fin function consultar2

function modificar($rif_cedula=null,$pagina=null){
	$this->layout = "ajax";
	$this->set('pagina',$pagina);
    $cond ="rif_ci='".$rif_cedula."'";
    $datacpcp01=$this->shd002_cobradores->findAll($cond);
    $this->set('datos',$datacpcp01);
}

function guardar_modificar($rif_cedula=null,$pagina=null){
	$this->layout = "ajax";
	if(!empty($this->data)){
		$cod_presi=$this->verifica_SS(1);
  		$cod_entidad=$this->verifica_SS(2);
  		$cod_tipo_inst=$this->verifica_SS(3);
  		$cod_inst=$this->verifica_SS(4);
  		$cod_dep=$this->verifica_SS(5);
		$personalidad=$this->data['shp002_cobradores']['personalidad'];
		$nombre_razon=$this->data['shp002_cobradores']['nombre_razon'];
		$recurso=$this->data['shp002_cobradores']['recurso'];
		$condicion=$this->data['shp002_cobradores']['condicion'];
		$fecha_ingreso=$this->data['shp002_cobradores']['fecha_ingreso'];
		$fecha_ingreso=$this->Cfecha($fecha_ingreso,'A-M-D');
	 	$sql="update shd002_cobradores set personalidad=$personalidad,nombre_razon='".$nombre_razon."', condicion_actividad=$condicion,recurso_cobro=$recurso,  fecha_ingreso='".$fecha_ingreso."' where rif_ci='$rif_cedula'";
     	$vvv=$this->shd002_cobradores->execute($sql);
     	$this->set('Message_existe', 'Registro Modificado con exito.');
	 	$this->consulta($pagina);
	 	$this->render("consulta");
	}
}//fin guardar_modificar

function eliminar($rif_cedula=null,$pagina=null){
	$this->layout = "ajax";
	$sql =$this->SQLCA()." and rif_ci_cobrador='".$rif_cedula."'";
	$sql22 =$this->SQLCA()." and rif_ci='".$rif_cedula."'";
	$y1=$this->shd100_patente->findCount($sql);
	$no=0;
	if($y1 > 0){
		$no=1;
	}
	$y2=$this->shd200_vehiculos->findCount($sql);
	if($y2 > 0){
		$no=1;
	}
	$y3=$this->shd300_propaganda->findCount($sql);
	if($y3 > 0){
		$no=1;
	}
	$y4=$this->shd400_propiedad->findCount($sql);
	if($y4 > 0){
		$no=1;
	}
	$y5=$this->shd600_aprobacion_arrendamiento->findCount($sql);
	if($y5 > 0){
		$no=1;
	}
	$y6=$this->shd700_credito_vivienda->findCount($sql);
	if($y6 > 0){
		$no=1;
	}
	if($no == 1){
		$this->set('errorMessage', 'No se puede eliminar cobrador porque es responsable de un constribuyente.');
		$this->consulta($pagina);
      	$this->render("consulta");
	}else{
	$sql1 ="DELETE  FROM  shd002_cobradores where ".$sql22;
	$this->shd002_cobradores->execute($sql1);
	$this->set('Message_existe', 'Dato Eliminado con exito.');
	$y=$this->shd002_cobradores->findCount();
	if($pagina>$y){
 		$pagina=$pagina-1;
 	}
	if($y!=0){
		$this->consulta($pagina);
      	$this->render("consulta");
	}else if($y==0){
		$this->index();
      	$this->render("index");
	}//fin if
}
}

function buscar($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function

function buscar_por_pista2($var1=null, $var2=null, $var3=null){
$this->layout="ajax";

    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					$Tfilas=$this->shd002_cobradores->findCount("((rif_ci LIKE '%$var2%') or (nombre_razon LIKE '%$var2%')) and ".$this->SQLCA());
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->shd002_cobradores->findAll("((rif_ci LIKE '%$var2%') or (nombre_razon LIKE '%$var2%')) and ".$this->SQLCA(),null,"rif_ci ASC",50,1,null);
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
						$Tfilas=$this->shd002_cobradores->findCount("((rif_ci LIKE '%$var22%') or (nombre_razon LIKE '%$var22%')) and ".$this->SQLCA());
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->shd002_cobradores->findAll("((rif_ci LIKE '%$var22%') or (nombre_razon LIKE '%$var22%')) and ".$this->SQLCA(),null,"rif_ci ASC",50,$pagina,null);
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

function consulta2($numero=null){
 		$this->layout = "ajax";
 	  $cod_presi = $this->Session->read('SScodpresi');
	  $cod_entidad = $this->Session->read('SScodentidad');
	  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	  $cod_inst = $this->Session->read('SScodinst');
	  $cod_dep = $this->Session->read('SScoddep');
	  $modulo = $this->Session->read('Modulo');
	  $c = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep." and rif_ci='".$numero."'";
          	 $veri=$this->shd002_cobradores->findCount($c);
          	 if($veri > 0){
          	 $datacpcp01=$this->shd002_cobradores->findAll($c);
          	 $this->set('datos',$datacpcp01);
          	 }else{
				$this->index();
				$this->render("index");
          	 }
}//fin function consultar2

}
//fin class
?>