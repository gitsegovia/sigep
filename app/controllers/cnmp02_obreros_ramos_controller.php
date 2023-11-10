<?php
 class Cnmp02ObrerosRamosController extends AppController{
	var $name = 'cnmp02_obreros_ramos';
	var $uses = array('cnmd02_obreros_ramos','cnmd02_obreros_grupos');
	var $helpers = array('Html','Ajax','Javascript', 'Sisap');



 function checkSession(){
				if (!$this->Session->check('Usuario')){
					$this->redirect('/salir/');
					exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
 }//fin checksession

 function index(){
	$this->layout="ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$denominacion = $this->cnmd02_obreros_ramos->generateList(null,'cod_ramo ASC', null, '{n}.cnmd02_obreros_ramos.cod_ramo', '{n}.cnmd02_obreros_ramos.denominacion');
	$denominacion = $denominacion != null ? $denominacion : array();
	$datos=$this->cnmd02_obreros_ramos->findAll(null,null,'cod_ramo ASC');
	$this->concatena($denominacion, 'denominacion');
	$this->set('datos',$datos);
 }//index


 function mostrar1($select=null){
	$this->layout="ajax";
	if($select!=null){
		$dato=$this->cnmd02_obreros_ramos->findAll('cod_ramo='.$select);
		$this->set('cod_ramo',$dato[0]['cnmd02_obreros_ramos']['cod_ramo']);
		$this->set('denominacion',$dato[0]['cnmd02_obreros_ramos']['denominacion']);
	}else{
		$this->set('cod_ramo','');
		$this->set('denominacion','');
		$this->set('mensajeError','No ha seleccionado nigun grupo');
	}
 }//mostrar1


 function guardar(){
	$this->layout="ajax";
	if($this->data['cnmp02_obreros_ramos']['cod_ramo'] !="" && $this->data['cnmp02_obreros_ramos']['denominacion'] !=""){
		if($this->cnmd02_obreros_ramos->findAll('cod_ramo='.$this->data['cnmp02_obreros_ramos']['cod_ramo'])){
			$this->set('mensajeError','LO SIENTO EL CODIGO('.$this->data['cnmp02_obreros_ramos']['cod_ramo'].') YA SE ENCUENTRA REGISTRADO EN EL SISTEMA');
			$datos=$this->cnmd02_obreros_ramos->findAll(null,null,'cod_ramo ASC');
		    $this->set('datos',$datos);
		}else{
		   $sql="INSERT INTO cnmd02_obreros_ramos VALUES ('".$this->data['cnmp02_obreros_ramos']['cod_ramo']."','".$this->data['cnmp02_obreros_ramos']['denominacion']."')";
		   if($this->cnmd02_obreros_ramos->execute($sql)>1){
		      $this->set('mensaje','EL TIPO FUE AGREGADO CORRECTAMENTE');
		      $datos=$this->cnmd02_obreros_ramos->findAll(null,null,'cod_ramo ASC');
		      $this->set('datos',$datos);
		   }else{
		      $this->set('mensajeError','LO SIENTO, EL TIPO NO PUDO SER AGREGADO');
		      $datos=$this->cnmd02_obreros_ramos->findAll(null,null,'cod_ramo ASC');
		   	  $this->set('datos',$datos);
		   }
		}//fin else consulta
	}else{
		$this->set('mensajeError','ATENCI&Oacute;N, DEBE INGRESAR EL CODIGO Y LA DENOMINACION DEL TIPO');
		$datos=$this->cnmd02_obreros_ramos->findAll(null,null,'cod_ramo ASC');
		$this->set('datos',$datos);
	}
	echo "<script>document.getElementById('agregar').disabled='';</script>";
		$this->index();
		$this->render("index");
 }//guardar


 function guardar_modificar($cod_ramo=null){
	$this->layout="ajax";
	if($this->data['cnmp02_obreros_ramos']['cod_ramo'] !="" && $this->data['cnmp02_obreros_ramos']['denominacion'] !=""){
		$cod_ramo=$this->data['cnmp02_obreros_ramos']['cod_ramo'];
		$deno=$this->data['cnmp02_obreros_ramos']['denominacion'];
		$sql_update="UPDATE cnmd02_obreros_ramos SET denominacion='".$this->data['cnmp02_obreros_ramos']['denominacion']."' WHERE cod_ramo=$cod_ramo";
		if($this->cnmd02_obreros_ramos->execute($sql_update)>0){
			$this->set('mensaje','EL GRUPO FUE MODIFICADO CORRECTAMENTE');
			$this->index();
			$this->render("index");
		}else{
			$this->set('mensajeError','LO SIENTO, EL GRUPO NO PUDO SER MODIFICADO');
			$this->index();
			$this->render("index");
		}
	}else{
		$this->set('mensajeError','ATENCI&Oacute;N, DEBE INGRESAR LA DENOMINACION DEL TIPO');
		$this->set('datos',null);
		$this->index();
		$this->render("index");
	}
 }//guardar modificar




 function eliminar($cod_ramo=null){
	$this->layout="ajax";
    if($cod_ramo!=null){
      /* if($this->cnmd06_datos_personales->findBycod_ramo($cod_ramo)){
	   $this->set('mensajeError','LA PROFESION NO PUEDE SER ELIMINADA, YA SE ENCUENTRA PRESENTE EN LOS DATOS PERSONALES');
	   }else{*/

	   	  $veri=$this->cnmd02_obreros_grupos->findCount("cod_ramo=".$cod_ramo);

			if($veri > 0){
				$this->set('mensajeError','LO SIENTO, TIPO NO PUEDE SER ELIMINADO ESTA PRESENTE EL LA CLASIFICACION DE BIENES - GRUPO');
				//$this->index();
				//$this->render("index");
			}else{
				$sql="DELETE FROM cnmd02_obreros_ramos WHERE cod_ramo=".$cod_ramo;
		   if($this->cnmd02_obreros_ramos->execute($sql)>1){
		   $this->set('mensaje','TIPO FUE ELIMINADO CORRECTAMENTE');
		   }else{
		   $this->set('mensajeError','LO SIENTO, TIPO NO PUDO SER ELIMINADO');
		   }
      }
    }else{
    	 $this->set('mensajeError','LO SIENTO, LOS DATOS NO LLEGARON CORRECTAMENTE Y NO SE PUDO PROCESAR LA ELIMINACI&Oacute;N');
    }
    $this->index();
	$this->render("index");
			}


 function mostrar_datos($var=null){
 	$this->layout="ajax";
 	if($var!=null){
 		if($var==1){
 			$datos=$this->cnmd02_obreros_ramos->findAll(null,null,'cod_ramo ASC');
			$this->set('datos',$datos);
 		}elseif($var==2){
 			$datos=$this->cnmd02_obreros_ramos->findAll(null,null,'denominacion ASC');
			$this->set('datos',$datos);
 		}
 	}else{
 		$this->set('mensajeError','LO SIENTO, NO LLEGO INFORMACI&Oacute;N PARA PROCESAR');
 	}
 }//mostrar_datos


function editar($cod_ramo=null){
 $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_ramo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_ramo_inst = ".$cod_ramo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  $accion =  $this->cnmd02_obreros_ramos->findAll("cod_ramo =".$cod_ramo, null, null);
 $denominacion = $accion[0]['cnmd02_obreros_ramos']['denominacion'];

$this->set('cod_ramo',$cod_ramo);
$this->set('denominacion',$denominacion);


		echo "<script>";
		  echo "document.getElementById('iconos_1_".$cod_ramo."').style.display = 'none'; ";
          echo "document.getElementById('iconos_2_".$cod_ramo."').style.display = 'block'; ";
		 // echo "document.getElementById('td_1_".$cod_ramo."').innerHTML='<input style=text-align:right; type=text name=data[cnmp02_obreros_ramos][cod_ramo_$cod_ramo] id=compensaciones_".$cod_ramo." readonly  value=".$this->Formato2($cod_ramo)." />'; ";
		echo "</script>";


$this->set('Message_existe', 'PUEDE EDITAR LOS DATOS');
}//fin function

function cancelar($cod_ramo=null){
$this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_ramo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
 // $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_ramo_inst = ".$cod_ramo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  $accion =  $this->cnmd02_obreros_ramos->findAll();
 /*
    //    echo "<script>";
            echo "document.getElementById('td_1_".$cod_ramo."').innerHTML='".$this->AddCeroR2($accion[0]['cnmd02_obreros_ramos']['cod_ramo'])."';";
			echo "document.getElementById('td_2_".$cod_ramo."').innerHTML='".$this->$accion[0]['cnmd02_obreros_ramos']['denominacion']."';";
			echo "document.getElementById('iconos_1_".$cod_ramo."').style.display = 'block'; ";
            echo "document.getElementById('iconos_2_".$cod_ramo."').style.display = 'none'; ";
	//	echo "</script>";
*/
	$this->set('datos',$accion);
	$this->index();
	$this->render("index");


}//fin function

function guardar_editar($var1){
  $this->layout = "ajax";
  $denominacion      =  $this->data['cnmp02_obreros_ramos']['denominacion_'.$var1];
    $sql = " UPDATE cnmd02_obreros_ramos SET denominacion='".$denominacion."' where cod_ramo =".$var1;
	$this->cnmd02_obreros_ramos->execute($sql);
    //echo $sql;
	$this->set('mensaje', 'Datos Actualizados Correctamente');
	$accion =  $this->cnmd02_obreros_ramos->findAll();
	//$accion =  $this->cnmd02_obreros_ramos->execute("select * from cnmd02_obreros_ramos where cod_ramo =".$var1);
	$this->set('datos',$accion);
	$this->index();
	$this->render("index");

}//fin funtion

function agregar(){
	$this->layout = "ajax";
}



}
?>