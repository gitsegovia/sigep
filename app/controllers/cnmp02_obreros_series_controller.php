<?php
 class Cnmp02ObrerosSeriesController extends AppController{
	var $name = 'cnmp02_obreros_series';
	var $uses = array('cnmd02_obreros_grupos','cnmd02_obreros_ramos','cnmd02_obreros_series','cimd01_clasificacion_seccion');
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
	$tipo = $this->cnmd02_obreros_ramos->generateList(null,'cod_ramo ASC', null, '{n}.cnmd02_obreros_ramos.cod_ramo', '{n}.cnmd02_obreros_ramos.denominacion');
	$this->concatena($tipo, 'cod_ramo');
	if($cod_ramo!=null){
	 $datacpcp01=$this->cnmd02_obreros_series->findAll('cod_ramo='.$cod_ramo,null,'cod_ramo,cod_grupo,cod_serie ASC');
     $this->set('datos',$datacpcp01);
	 }



 }//index


 function guardar(){
	$this->layout="ajax";

if(!empty($this->data['cnmp02_obreros_series']['cod_ramo']) && !empty($this->data['cnmp02_obreros_series']['cod_grupo']) && !empty($this->data['cnmp02_obreros_series']['cod_serie']) && !empty($this->data['cnmp02_obreros_series']['deno_serie'])){

		$cod_ramo=$this->data['cnmp02_obreros_series']['cod_ramo'];
		$cod_grupo=$this->data['cnmp02_obreros_series']['cod_grupo'];
		$cod_serie=$this->data['cnmp02_obreros_series']['cod_serie'];
		$deno_subgrupo=$this->data['cnmp02_obreros_series']['deno_serie'];

		$sql ="INSERT INTO cnmd02_obreros_series (cod_ramo,cod_grupo,cod_serie,denominacion)";
		$sql .=" VALUES (".$cod_ramo.",".$cod_grupo.",".$cod_serie.",'".$deno_subgrupo."')";

		$veri=$this->cnmd02_obreros_series->findCount('cod_ramo='.$cod_ramo.' and cod_grupo='.$cod_grupo.' and cod_serie='.$cod_serie);


		if($veri==0){

		$re=$this->cnmd02_obreros_series->execute($sql);
		if($re>1){
			 $this->set('mensaje','EL REGISTRO FUE AGREGADO CORRECTAMENTE');

		}else{
			 $this->set('mensajeError','NO SE PUDO AGREGAR EL REGISTRO');
		}
		}else{$this->set('mensajeError','ESTOS CODIGOS YA SE ENCUENTRAN REGISTRADOS');
	 }

}else{
	 $this->set('mensajeError','debe ingresar todos los datos');
}

	if(!empty($this->data['cnmp02_obreros_series']['cod_ramo']) && !empty($this->data['cnmp02_obreros_series']['cod_grupo'])){
		 echo "<script>";
	 		echo "document.getElementById('guardar').disabled=false;";
		 echo "</script>";
		 $cod_ramo=$this->data['cnmp02_obreros_series']['cod_ramo'];
		 $cod_grupo=$this->data['cnmp02_obreros_series']['cod_grupo'];
		 $datos=$this->cnmd02_obreros_series->findAll('cod_ramo='.$cod_ramo." and cod_grupo=".$cod_grupo,null,'cod_ramo,cod_grupo,cod_serie ASC');
		 if($datos!=null){
		 	 $this->set('datos',$datos);
		 }else{
		 	 $this->set('datos',null);
		 }

	    $ultimo=$this->cnmd02_obreros_series->findCount('cod_ramo='.$cod_ramo." and cod_grupo=".$cod_grupo);
	    if($ultimo!=0){
	    	$datacpcp01_ultimo=$this->cnmd02_obreros_series->findAll('cod_ramo='.$cod_ramo." and cod_grupo=".$cod_grupo,null,'cod_serie DESC');
	    	$this->set('ultimo_codigo',$datacpcp01_ultimo[0]['Cnmd02_obreros_series']['cod_serie']+1);
	    }else{
	        $this->set('ultimo_codigo',1);
	    }
	}else{
		 $this->set('datos',null);
	}
$this->data=null;




 }//fin function


 function eliminar($cod_ramo,$cod_grupo,$cod_serie){
	$this->layout="ajax";
    if($cod_serie!=null){
      /* if($this->cnmd06_datos_personales->findBycod_grupo($cod_grupo)){
	   $this->set('mensajeError','LA PROFESION NO PUEDE SER ELIMINADA, YA SE ENCUENTRA PRESENTE EN LOS DATOS PERSONALES');
	   }else{*/

				 $sql="DELETE FROM cnmd02_obreros_series WHERE cod_ramo=".$cod_ramo." and  cod_grupo=".$cod_grupo." and cod_serie=".$cod_serie;
		   if($this->cnmd02_obreros_series->execute($sql)>1){
		   $this->set('Message_existe','REGISTRO FUE ELIMINADO CORRECTAMENTE');
		   }else{
		   $this->set('errorMessage','LO SIENTO, SECCION NO PUDO SER ELIMINADO');
		   }

    }
   // $this->index();
	//$this->render("index");
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
function select3($select=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";
	//echo "si llego";
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
		  $lista=  $this->cnmd02_obreros_ramos->generateList($cond2, 'cod_ramo ASC', null, '{n}.cnmd02_obreros_ramos.cod_ramo', '{n}.cnmd02_obreros_ramos.denominacion');
			$this->concatena($lista,'vector');
		break;
		case 'grupo':
//		echo $var;
		//echo "si generica";
		  $this->set('SELECT','subgrupo');
		  $this->set('codigo','grupo');
		  $this->set('seleccion','');
		  $this->set('n',2);
		 // $ano =  $this->Session->read('ano');
		  $this->Session->write('ctipo',$var);
		  $cond2 ="cod_ramo=".$var;
		  $lista=  $this->cnmd02_obreros_grupos->generateList($cond2, 'cod_grupo ASC', null, '{n}.cnmd02_obreros_grupos.cod_grupo', '{n}.cnmd02_obreros_grupos.denominacion');
          $this->concatena($lista,'vector');


          echo "<script>";
          echo "document.getElementById('cod_serie').value = ''; ";
		   echo "document.getElementById('deno_serie').value = ''; ";
		   echo "document.getElementById('b_2').innerHTML = '<input type=text  class=campoText />'; ";
         echo "</script>";



 		break;
		case 'serie':
		//echo"si especifica";
		  $this->set('SELECT','seccion');
		  $this->set('codigo','subgrupo');
		  $this->set('seleccion','');
		  $this->set('n',3);
		//  $ano =  $this->Session->read('ano');
		  $ctipo =  $this->Session->read('ctipo');
		  $this->Session->write('cgru',$var);
		  $cond2 ="cod_ramo=".$ctipo." and cod_grupo=".$var;
		 //echo $cond2;
		  $lista = $this->cnmd02_obreros_series->generateList($cond2, 'cod_serie ASC', null, '{n}.cnmd02_obreros_series.cod_serie', '{n}.cnmd02_obreros_series.denominacion');
          $this->concatena($lista,'vector');
		break;
		case 'seccion':
		  //echo 'si';
		  $this->set('SELECT','seccion');
		  $this->set('codigo','seccion');
		  $this->set('seleccion','');
		  $this->set('n',4);
		//  $ano =  $this->Session->read('ano');
		  $ctipo =  $this->Session->read('ctipo');
		  $cgru =  $this->Session->read('cgru');
		  $this->Session->write('csub',$var);
		  $cond2 ="cod_ramo=".$ctipo." and cod_grupo=".$cgru." and cod_serie=".$var;
		  $lista=  $this->cnmd02_obreros_series->generateList($cond2, 'cod_seccion ASC', null, '{n}.cnmd02_obreros_series.cod_seccion', '{n}.cnmd02_obreros_series.denominacion');
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
	//echo "mostrar";
		if( $var!=null){
  //  $cond = $this->SQLCA();
    //$cond2 = $this->SQLCA();
	switch($select){
		case 'tipo':
		//echo '1';
		  //$ano =  $this->Session->read('ano');
		  $this->Session->write('dtipo',$var);
		  $cond2 ="cod_ramo=".$var;
		  $a=  $this->cnmd02_obreros_ramos->findAll($cond2);
          $e=$a[0]['cnmd02_obreros_ramos']['denominacion'];
         $this->set('var',$e);
		break;
		case 'grupo':
		  //$ano =  $this->Session->read('ano');
		//echo '2';
		  $dtipo=  $this->Session->read('dtipo');
		  $this->Session->write('dgru',$var);
		  $cond2 ="cod_ramo=".$dtipo." and cod_grupo=".$var;
		  $a=  $this->cnmd02_obreros_grupos->findAll($cond2);
          $e=$a[0]['cnmd02_obreros_grupos']['denominacion'];
          $this->set('var',$e);

		break;
		case 'subgrupo':
		  // $ano =  $this->Session->read('ano');
		  //echo '3';
		  $dtipo=  $this->Session->read('dtipo');
		  $dgru =  $this->Session->read('dgru');
		  $this->Session->write('dsub',$var);
		  $cond2 ="cod_ramo=".$dtipo." and cod_grupo=".$dgru." and cod_serie=".$var;
		  //echo $cond2;
		  $a=  $this->cnmd02_obreros_series->findAll($cond2);
		  //print_r($a);
          $e= $a[0]['cnmd02_obreros_series']['denominacion'];
          $this->set('var',$e);
		break;
		case 'seccion':
		  //$ano =  $this->Session->read('ano');
	//	  echo '4';
		  $dtipo=  $this->Session->read('dtipo');
		  $dgru =  $this->Session->read('dgru');
		  $dsub =  $this->Session->read('dsub');
		  $this->Session->write('dsec',$var);
		  $cond2 ="cod_ramo=".$dtipo." and cod_grupo=".$dgru." and cod_serie=".$dsub." and cod_seccion=".$var;
		  $a=  $this->cnmd02_obreros_series->findAll($cond2);
          $e= $a[0]['cnmd02_obreros_series']['denominacion'];
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
          	  $Tfilas=$this->v_cnmd02_obreros_series->findCount();
          	 if($Tfilas==0){
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datacpcp01=$this->v_cnmd02_obreros_series->findAll(null,null,null,1,$pagina,null);
          	 $this->set('datos',$datacpcp01);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             }
 }else{
 	$pagina=1;
          	 $Tfilas=$this->v_cnmd02_obreros_series->findCount();
          	 if($Tfilas==0){
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datacpcp01=$this->v_cnmd02_obreros_series->findAll(null,null,null,1,$pagina,null);
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

function modificar($cod_ramo, $cod_grupo, $cod_serie){
	$this->layout = "ajax";
	 $datacpcp01=$this->v_cnmd02_obreros_series->findAll('cod_ramo='.$cod_ramo.' and cod_grupo='.$cod_grupo.' and cod_serie='.$cod_serie);
     $this->set('datos',$datacpcp01);

}

function guardar_editar($cod_ramo, $cod_grupo, $cod_serie){
	$this->layout = "ajax";
	$deno_subgrupo=$this->data['cnmp02_obreros_series']['denominacion'];

	$update="update cnmd02_obreros_series set denominacion='".$deno_subgrupo."' where cod_ramo=".$cod_ramo." and cod_grupo=".$cod_grupo." and cod_serie=".$cod_serie;
	$this->cnmd02_obreros_series->execute($update);
	 $datos=$this->cnmd02_obreros_series->findAll('cod_ramo='.$cod_ramo." and cod_grupo=".$cod_grupo,null,'cod_ramo,cod_grupo,cod_serie ASC');
 	$this->set('datos',$datos);
		$this->set('Message_existe','EL REGISTRO FUE MODIFICADO CORRECTAMENTE');


}

function grilla($cod_ramo=null, $var2=null){
	$this->layout = "ajax";
	$ctipo =  $this->Session->read('ctipo');
	if($cod_ramo=="si"){
     $datacpcp01 = "";
     $this->set('ultimo_codigo',0);
	}else{
		if($cod_ramo!=null){
             $datacpcp01=$this->cnmd02_obreros_series->findAll('cod_ramo='.$ctipo." and cod_grupo=".$cod_ramo,null,'cod_ramo,cod_grupo,cod_serie ASC');
		     $this->set('datos',$datacpcp01);
			 $ultimo=$this->cnmd02_obreros_series->findCount('cod_ramo='.$ctipo." and cod_grupo=".$cod_ramo);
			 if($ultimo!=0){
				$datacpcp01_ultimo=$this->cnmd02_obreros_series->findAll('cod_ramo='.$ctipo." and cod_grupo=".$cod_ramo,null,'cod_serie DESC');
				$this->set('ultimo_codigo',$datacpcp01_ultimo[0]['Cnmd02_obreros_series']['cod_serie']+1);
			 }else{
			    $this->set('ultimo_codigo',1);
			 }
		}else{
            $this->set('ultimo_codigo',0);
		}


	}//fin else


}

function editar($var=null, $var2=null,$var3=null){
 $this->layout = "ajax";
  $accion =  $this->cnmd02_obreros_series->findAll("cod_ramo = ".$var." and cod_grupo  = ".$var2." and cod_serie=".$var3);
  //echo 'lo que devuelve esto es '.$accion[0]['cnmd02_obreros_grupos']['denominacion'];
  /*$cobroaguinaldo = $accion[0]['cnmd15_parametro_cobro']['cobro_aguinaldo'];
  $cobrovacaciones = $accion[0]['cnmd15_parametro_cobro']['cobro_bono_vacacional'];
  $disfrutovacaciones = $accion[0]['cnmd15_parametro_cobro']['disfruto_vacaciones'];
  $cobroruralidad = $accion[0]['cnmd15_parametro_cobro']['cobro_ruralidad'];
  //$fecha_desde = $fecha_desde[8].$fecha_desde[9].'/'.$fecha_desde[5].$fecha_desde[6].'/'.$fecha_desde[0].$fecha_desde[1].$fecha_desde[2].$fecha_desde[3];
  //$fecha_hasta = $fecha_hasta[8].$fecha_hasta[9].'/'.$fecha_hasta[5].$fecha_hasta[6].'/'.$fecha_hasta[0].$fecha_hasta[1].$fecha_hasta[2].$fecha_hasta[3];

*/

		echo "<script>";
		  echo "document.getElementById('iconos_1_".$var."_".$var2."_".$var3."').style.display = 'none'; ";
          echo "document.getElementById('iconos_2_".$var."_".$var2."_".$var3."').style.display = 'block'; ";
          echo "document.getElementById('td_4_".$var."_".$var2."_".$var3."').innerHTML='<input type=text name=data[cnmp02_obreros_series][denominacion] class=\"campoText\" value=\"".$accion[0]['Cnmd02_obreros_series']['denominacion']."\" />';  ";
		echo "</script>";


    $this->set('cod_ramo', $var);
	$this->set('cod_grupo', $var2);
	$this->set('cod_serie', $var3);

$this->set('Message_existe', 'PUEDE EDITAR LOS DATOS');
}//fin function



function cancelar($cod_ramo, $cod_grupo, $cod_serie){
	$this->layout = "ajax";
	 $datos=$this->cnmd02_obreros_series->findAll('cod_ramo='.$cod_ramo." and cod_grupo=".$cod_grupo,null,'cod_ramo,cod_grupo,cod_serie ASC');
 	$this->set('datos',$datos);
}


}
?>