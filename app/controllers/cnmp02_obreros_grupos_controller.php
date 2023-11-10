<?php
 class Cnmp02ObrerosGruposController extends AppController{
	var $name = 'cnmp02_obreros_grupos';
	var $uses = array('cnmd02_obreros_grupos','cnmd02_obreros_ramos','cnmd02_obreros_series','cimd01_clasificacion_tipo');
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


 function index($cod_ramo=null){
	$this->layout="ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	//$tipo = $this->cnmd02_obreros_grupos->generateList(null,'cod_grupo ASC', null, '{n}.cnmd02_obreros_grupos.cod_grupo', '{n}.cnmd02_obreros_grupos.denominacion');
	$tipo = $this->cnmd02_obreros_ramos->generateList(null,'cod_ramo ASC', null, '{n}.cnmd02_obreros_ramos.cod_ramo', '{n}.cnmd02_obreros_ramos.denominacion');
	//$this->set('cod_ramo',$tipo);
	$this->concatena($tipo,'cod_ramo');
	 if($cod_ramo!=null){
	 	 $this->set('ramo',$cod_ramo);
		 $datacpcp01=$this->cnmd02_obreros_grupos->findAll('cod_ramo='.$cod_ramo,null,'cod_ramo,cod_grupo ASC');
	     $this->set('datos',$datacpcp01);
	     $datacpcp01_ultimo=$this->cnmd02_obreros_grupos->findAll('cod_ramo='.$cod_ramo,null,'cod_grupo DESC');
	     $this->set('ultimo_codigo',$datacpcp01_ultimo[0]['cnmd02_obreros_grupos']['cod_grupo']+1);
	     $datacpcp01_ultimo=$this->cnmd02_obreros_ramos->findAll('cod_ramo='.$cod_ramo,null,null);
	      $this->set('deno',$datacpcp01_ultimo[0]['cnmd02_obreros_ramos']['denominacion']);
	 }else{
	 	 $this->set('ramo','');
	 	 $this->set('deno','');
	 	 $this->set('datos',null);
	 }
//print_r($tipo);
// $ojo=$this->cnmd02_obreros_grupos->findAll();
// print_r($ojo);
 }//index

 function guardar(){
	$this->layout="ajax";

		$cod_ramo=$this->data['cnmp02_obreros_grupos']['cod_ramo'];
		$cod_grupo=$this->data['cnmp02_obreros_grupos']['cod_grupo'];
		$deno_grupo=$this->data['cnmp02_obreros_grupos']['deno_grupo'];
		$sql ="INSERT INTO cnmd02_obreros_grupos (cod_ramo,cod_grupo,denominacion)";
		$sql .=" VALUES (".$cod_ramo.",".$cod_grupo.",'".$deno_grupo."')";

		$veri=$this->cnmd02_obreros_grupos->findCount('cod_ramo='.$cod_ramo.' and cod_grupo='.$cod_grupo);

		if($veri==0){

		$re=$this->cnmd02_obreros_grupos->execute($sql);
		if($re>1){
			 $this->set('Message_existe','EL REGISTRO FUE AGREGADO CORRECTAMENTE');
		$this->data=null;
		}else{
			 $this->set('errorMessage','NO SE PUDO AGREGAR EL REGISTRO');
		$this->data=null;
		}
		}else{$this->set('errorMessage','ESTOS CODIGOS YA SE ENCUENTRAN REGISTRADOS');
		$this->data=null;
 }
		$this->grilla($cod_ramo);
		$this->render("grilla");

 }//fin function


 function eliminar($cod_ramo,$cod_grupo){
	$this->layout="ajax";
    if($cod_grupo!=null){
		   $veri=$this->cnmd02_obreros_series->findCount("cod_ramo=".$cod_ramo." and  cod_grupo=".$cod_grupo);
		   if($veri > 0){
		   		$this->set('errorMessage','DISCULPE EL GRUPO NO PUEDE SER ELIMINADO PORQUE SE ENCUENTRA EN LA CLASIFICACION DE BIENES - SUBGRUPO');
		   }else{
				$sql="DELETE FROM cnmd02_obreros_grupos WHERE cod_ramo=".$cod_ramo." and  cod_grupo=".$cod_grupo;
		   if($this->cnmd02_obreros_grupos->execute($sql)>1){
		   $this->set('Message_existe','REGISTRO FUE ELIMINADO CORRECTAMENTE');
		   }else{
		   $this->set('errorMessage','LO SIENTO, EL GRUPO NO PUDO SER ELIMINADO');
		   }
		   }
    }else{
    	$this->set('errorMessage','LO SIENTO, LOS DATOS NO LLEGARON CORRECTAMENTE Y NO SE PUDO PROCESAR LA ELIMINACI&Oacute;N');
    }
   }//eliminar


 function mostrar_datos($var=null){
 	$this->layout="ajax";
 	if($var!=null){
 		if($var==1){
 			$datos=$this->cnmd02_obreros_grupos->findAll(null,null,'cod_grupo ASC');
			$this->set('datos',$datos);
 		}elseif($var==2){
 			$datos=$this->cnmd02_obreros_grupos->findAll(null,null,'denominacion ASC');
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
		case 'tipo':
		  $this->set('SELECT','grupo');
		  $this->set('codigo','tipo');
		  $this->set('seleccion','');
		  $this->set('n',1);
		  $ano =  $this->Session->read('ano');
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE."";
		  $lista=  $this->cnmd02_obreros_ramos->generateList($cond2, 'cod_ramo ASC', null, '{n}.cnmd02_obreros_ramos.cod_ramo', '{n}.cnmd02_obreros_ramos.denominacion');
			$this->concatena($lista,'vector');
		break;
		case 'grupo':
		  $this->set('SELECT','subgrupo');
		  $this->set('codigo','grupo');
		  $this->set('seleccion','');
		  $this->set('n',2);
		  $this->Session->write('ctipo',$var);
		  $cond2 ="cod_ramo=".$var;
		  $lista=  $this->cnmd02_obreros_grupos->generateList($cond2, 'cod_grupo ASC', null, '{n}.cnmd02_obreros_grupos.cod_grupo', '{n}.cnmd02_obreros_grupos.denominacion');
          $this->concatena($lista,'vector');
 		break;
		case 'subgrupo':
		  $this->set('SELECT','seccion');
		  $this->set('codigo','subgrupo');
		  $this->set('seleccion','');
		  $this->set('n',3);
		  $ctipo =  $this->Session->read('ctipo');
		  $this->Session->write('cgru',$var);
		  $cond2 ="cod_ramo=".$ctipo." and cod_grupo=".$var;
		  $lista = $this->cnmd02_obreros_grupos->generateList($cond2, 'cod_subgrupo ASC', null, '{n}.cnmd02_obreros_grupos.cod_subgrupo', '{n}.cnmd02_obreros_grupos.denominacion');
          $this->concatena($lista,'vector');
		break;
		case 'seccion':
		  $this->set('SELECT','seccion');
		  $this->set('codigo','seccion');
		  $this->set('seleccion','');
		  $this->set('n',4);
		  $ctipo =  $this->Session->read('ctipo');
		  $cgru =  $this->Session->read('cgru');
		  $this->Session->write('csub',$var);
		  $cond2 ="cod_ramo=".$ctipo." and cod_grupo=".$cgru." and cod_subgrupo=".$var;
		  $lista=  $this->cnmd02_obreros_grupos->generateList($cond2, 'cod_seccion ASC', null, '{n}.cnmd02_obreros_grupos.cod_seccion', '{n}.cnmd02_obreros_grupos.denominacion');
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
		  $this->Session->write('dtipo',$var);
		  $cond2 ="cod_ramo=".$var;
		  $a=  $this->cnmd02_obreros_ramos->findAll($cond2);
          $e=$a[0]['cnmd02_obreros_ramos']['denominacion'];
         $this->set('var',$e);
		break;
		case 'grupo':
		  $dtipo=  $this->Session->read('dtipo');
		  $this->Session->write('dgru',$var);
		  $cond2 ="cod_ramo=".$dtipo." and cod_grupo=".$var;
		  $a=  $this->cnmd02_obreros_grupos->findAll($cond2);
          $e=$a[0]['cnmd02_obreros_grupos']['denominacion'];
          $this->set('var',$e);
		break;
		case 'subgrupo':
		  $dtipo=  $this->Session->read('dtipo');
		  $dgru =  $this->Session->read('dgru');
		  $this->Session->write('dsub',$var);
		  $cond2 ="cod_ramo=".$dtipo." and cod_grupo=".$dgru." and cod_subgrupo=".$var;
		  $a=  $this->cnmd02_obreros_grupos->findAll($cond2);
          $e= $a[0]['cnmd02_obreros_grupos']['denominacion'];
          $this->set('var',$e);
		break;
		case 'seccion':
		  $dtipo=  $this->Session->read('dtipo');
		  $dgru =  $this->Session->read('dgru');
		  $dsub =  $this->Session->read('dsub');
		  $this->Session->write('dsec',$var);
		  $cond2 ="cod_ramo=".$dtipo." and cod_grupo=".$dgru." and cod_subgrupo=".$dsub." and cod_seccion=".$var;
		  $a=  $this->cnmd02_obreros_grupos->findAll($cond2);
          $e= $a[0]['cnmd02_obreros_grupos']['denominacion'];
           $this->set('var',$e);
		break;
	}//fin wsitch
	}else{
		 $this->set('var','');
	}
}//fin mostrar3 codigos presupuestarios


function consultar($pagina=null){
 		$this->layout = "ajax";
         if($pagina!=null){
          	 $pagina=$pagina;
          	  $Tfilas=$this->v_cnmd02_obreros_grupos->findCount();
          	 if($Tfilas==0){
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datacpcp01=$this->v_cnmd02_obreros_grupos->findAll(null,null,null,1,$pagina,null);
          	 $this->set('datos',$datacpcp01);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             }
 }else{
 	$pagina=1;
          	 $Tfilas=$this->v_cnmd02_obreros_grupos->findCount();
          	 if($Tfilas==0){
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datacpcp01=$this->v_cnmd02_obreros_grupos->findAll(null,null,null,1,$pagina,null);
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

function modificar($cod_ramo, $cod_grupo){
	$this->layout = "ajax";
	 $datacpcp01=$this->v_cnmd02_obreros_grupos->findAll('cod_ramo='.$cod_ramo.' and cod_grupo='.$cod_grupo);
     $this->set('datos',$datacpcp01);

}

function guardar_editar($cod_ramo=null, $cod_grupo=null){
	$this->layout = "ajax";
	$deno_grupo=$this->data['cnmp02_obreros_grupos']['denominacion'];

	$update="update cnmd02_obreros_grupos set denominacion='".$deno_grupo."' where cod_ramo=".$cod_ramo." and cod_grupo=".$cod_grupo;
	$this->cnmd02_obreros_grupos->execute($update);
	$this->set('Message_existe','EL REGISTRO FUE MODIFICADO CORRECTAMENTE');

	$this->grilla($cod_ramo);
    $this->render("grilla");

}

function grilla($cod_ramo=null){
	$this->layout = "ajax";
	if($cod_ramo!=''){
		$datacpcp01=$this->cnmd02_obreros_grupos->findAll('cod_ramo='.$cod_ramo,null,'cod_ramo,cod_grupo ASC');
	    $this->set('datos',$datacpcp01);
	    $ultimo=$this->cnmd02_obreros_grupos->findCount('cod_ramo='.$cod_ramo);
	    if($ultimo!=0){
	    	$datacpcp01_ultimo=$this->cnmd02_obreros_grupos->findAll('cod_ramo='.$cod_ramo,null,'cod_grupo DESC');
	    	$this->set('ultimo_codigo',$datacpcp01_ultimo[0]['cnmd02_obreros_grupos']['cod_grupo']+1);
	    }else{
	        $this->set('ultimo_codigo',1);
	    }

	}else{
		 $this->set('datos',null);
	}

}




function editar($var=null, $var2=null){
 $this->layout = "ajax";
 //echo $var,$var2;
  $accion =  $this->cnmd02_obreros_grupos->findAll("cod_ramo = ".$var." and cod_grupo  = ".$var2);
  //echo 'lo que devuelve esto es '.$accion[0]['cnmd02_obreros_grupos']['denominacion'];
  /*$cobroaguinaldo = $accion[0]['cnmd15_parametro_cobro']['cobro_aguinaldo'];
  $cobrovacaciones = $accion[0]['cnmd15_parametro_cobro']['cobro_bono_vacacional'];
  $disfrutovacaciones = $accion[0]['cnmd15_parametro_cobro']['disfruto_vacaciones'];
  $cobroruralidad = $accion[0]['cnmd15_parametro_cobro']['cobro_ruralidad'];
  //$fecha_desde = $fecha_desde[8].$fecha_desde[9].'/'.$fecha_desde[5].$fecha_desde[6].'/'.$fecha_desde[0].$fecha_desde[1].$fecha_desde[2].$fecha_desde[3];
  //$fecha_hasta = $fecha_hasta[8].$fecha_hasta[9].'/'.$fecha_hasta[5].$fecha_hasta[6].'/'.$fecha_hasta[0].$fecha_hasta[1].$fecha_hasta[2].$fecha_hasta[3];

*/

		echo "<script>";
		  echo "document.getElementById('iconos_1_".$var."_".$var2."').style.display = 'none'; ";
          echo "document.getElementById('iconos_2_".$var."_".$var2."').style.display = 'block'; ";
          echo "document.getElementById('td_3_".$var."_".$var2."').innerHTML='<input type=text name=data[cnmp02_obreros_grupos][denominacion] size=75% value=\"".$accion[0]['cnmd02_obreros_grupos']['denominacion']."\" />';  ";
		echo "</script>";


  //  $this->set('var1', $var);
	//$this->set('var2', $var2);

$this->set('Message_existe', 'PUEDE EDITAR LOS DATOS');
}//fin function


}
?>