<?php
 class Cimp05ConservacionTipoRepuestosController extends AppController{
	var $name = 'cimp05_conservacion_tipo_repuestos';
	var $uses = array('cimd05_conservacion_tipo_repuestos');
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
	$denominacion = $this->cimd05_conservacion_tipo_repuestos->generateList(null,'cod_repuesto ASC', null, '{n}.cimd05_conservacion_tipo_repuestos.cod_repuesto', '{n}.cimd05_conservacion_tipo_repuestos.denominacion');
	$denominacion = $denominacion != null ? $denominacion : array();
	$datos=$this->cimd05_conservacion_tipo_repuestos->findAll(null,null,'cod_repuesto ASC');
		$datos2=$this->cimd05_conservacion_tipo_repuestos->findAll(null,null,'cod_repuesto DESC');
	 if($datos2==null){
     	$new_numero=1;
     }else{
     	$new_numero=$datos2[0]["cimd05_conservacion_tipo_repuestos"]["cod_repuesto"]+1;
     }
	$this->set('new_numero',$new_numero);
	$this->concatena($denominacion, 'denominacion');
	$this->set('datos',$datos);
 }//index


 function mostrar1($select=null){
	$this->layout="ajax";
	if($select!=null){
		$dato=$this->cimd05_conservacion_tipo_repuestos->findAll('cod_repuesto='.$select);
		$this->set('cod_repuesto',$dato[0]['cimd05_conservacion_tipo_repuestos']['cod_repuesto']);
		$this->set('denominacion',$dato[0]['cimd05_conservacion_tipo_repuestos']['denominacion']);
	}else{
		$this->set('cod_repuesto','');
		$this->set('denominacion','');
		$this->set('mensajeError','No ha seleccionado nigun tipo');
	}
 }//mostrar1


 function guardar(){
	$this->layout="ajax";
	if($this->data['cimp05_conservacion_tipo_repuestos']['cod_repuesto'] !="" && $this->data['cimp05_conservacion_tipo_repuestos']['denominacion'] !=""){
		if($this->cimd05_conservacion_tipo_repuestos->findAll('cod_repuesto='.$this->data['cimp05_conservacion_tipo_repuestos']['cod_repuesto'])){
			$this->set('mensajeError','LO SIENTO EL CODIGO('.$this->data['cimp05_conservacion_tipo_repuestos']['cod_repuesto'].') YA SE ENCUENTRA REGISTRAD0 EN EL SISTEMA');
			$datos=$this->cimd05_conservacion_tipo_repuestos->findAll(null,null,'cod_repuesto ASC');
		    $this->set('datos',$datos);
		}else{
		   $sql="INSERT INTO cimd05_conservacion_tipo_repuestos VALUES ('".$this->data['cimp05_conservacion_tipo_repuestos']['cod_repuesto']."','".$this->data['cimp05_conservacion_tipo_repuestos']['denominacion']."')";
		   if($this->cimd05_conservacion_tipo_repuestos->execute($sql)>1){
		      $this->set('mensaje','EL TIPO FUE AGREGADO CORRECTAMENTE');
		      $datos=$this->cimd05_conservacion_tipo_repuestos->findAll(null,null,'cod_repuesto ASC');
		      $this->set('datos',$datos);
		   }else{
		      $this->set('mensajeError','LO SIENTO, EL REGISTRO NO PUDO SER AGREGADO');
		      $datos=$this->cimd05_conservacion_tipo_repuestos->findAll(null,null,'cod_repuesto ASC');
		   	  $this->set('datos',$datos);
		   }
		}//fin else consulta
	}else{
		$this->set('mensajeError','ATENCI&Oacute;N, DEBE INGRESAR EL CÓDIGO Y LA DENOMINACIÓN DEL TIPO DE REPUESTO');
		$datos=$this->cimd05_conservacion_tipo_repuestos->findAll(null,null,'cod_repuesto ASC');
		$this->set('datos',$datos);
	}
	echo "<script>document.getElementById('agregar').disabled='';</script>";
	$this->index();
	$this->render("index");

 }//guardar


 function guardar_modificar($cod_repuesto=null){
	$this->layout="ajax";
	if($this->data['cimp05_conservacion_tipo_repuestos']['cod_repuesto'] !="" && $this->data['cimp05_conservacion_tipo_repuestos']['denominacion'] !=""){
		$cod_repuesto=$this->data['cimp05_conservacion_tipo_repuestos']['cod_repuesto'];
		$deno=$this->data['cimp05_conservacion_tipo_repuestos']['denominacion'];
		$sql_update="UPDATE cimd05_conservacion_tipo_repuestos SET denominacion='".$this->data['cimp05_conservacion_tipo_repuestos']['denominacion']."' WHERE cod_repuesto=$cod_repuesto";
		if($this->cimd05_conservacion_tipo_repuestos->execute($sql_update)>0){
			$this->set('mensaje','EL GRUPO FUE MODIFICADO CORRECTAMENTE');
			$this->index();
			$this->render("index");
		}else{
			$this->set('mensajeError','LO SIENTO, EL TIPO DE REPUESTO NO PUDO SER MODIFICADO');
			$this->index();
			$this->render("index");
		}
	}else{
		$this->set('mensajeError','ATENCI&Oacute;N, DEBE INGRESAR LA DENOMINACIÓN DEL TIPO DE REPUESTO');
		$this->set('datos',null);
		$this->index();
		$this->render("index");
	}
 }//guardar modificar




 function eliminar($cod_repuesto=null){
	$this->layout="ajax";
    if($cod_repuesto!=null){
      /* if($this->cnmd06_datos_personales->findBycod_repuesto($cod_repuesto)){
	   $this->set('mensajeError','LA PROFESION NO PUEDE SER ELIMINADA, YA SE ENCUENTRA PRESENTE EN LOS DATOS PERSONALES');
	   }else{*/
		   $sql="DELETE FROM cimd05_conservacion_tipo_repuestos WHERE cod_repuesto=".$cod_repuesto;
		   if($this->cimd05_conservacion_tipo_repuestos->execute($sql)>1){
		   $this->set('mensaje','TIPO DE REPUESTO FUE ELIMINADO CORRECTAMENTE');
		   }else{
		   $this->set('mensajeError','LO SIENTO, TIPO DE REPUESTO NO PUDO SER ELIMINADO');
		   }
     //  }
    }else{
        $this->set('mensajeError','LO SIENTO, LOS DATOS NO LLEGARON CORRECTAMENTE Y NO SE PUDO PROCESAR LA ELIMINACI&Oacute;N');
    }
    $this->index();
	$this->render("index");
 }//eliminar


 function mostrar_datos($var=null){
 	$this->layout="ajax";
 	if($var!=null){
 		if($var==1){
 			$datos=$this->cimd05_conservacion_tipo_repuestos->findAll(null,null,'cod_repuesto ASC');
			$this->set('datos',$datos);
 		}elseif($var==2){
 			$datos=$this->cimd05_conservacion_tipo_repuestos->findAll(null,null,'denominacion ASC');
			$this->set('datos',$datos);
 		}
 	}else{
 		$this->set('mensajeError','LO SIENTO, NO LLEGO INFORMACI&Oacute;N PARA PROCESAR');
 	}
 }//mostrar_datos

function editar($cod_tipo=null){
 $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  $accion =  $this->cimd05_conservacion_tipo_repuestos->findAll("cod_repuesto =".$cod_tipo, null, null);
 $denominacion = $accion[0]['cimd05_conservacion_tipo_repuestos']['denominacion'];

$this->set('cod_tipo',$cod_tipo);
$this->set('denominacion',$denominacion);


		echo "<script>";
		  echo "document.getElementById('iconos_1_".$cod_tipo."').style.display = 'none'; ";
          echo "document.getElementById('iconos_2_".$cod_tipo."').style.display = 'block'; ";
		 // echo "document.getElementById('td_1_".$cod_tipo."').innerHTML='<input style=text-align:right; type=text name=data[cimp01_clasificacion_tipo][cod_tipo_$cod_tipo] id=compensaciones_".$cod_tipo." readonly  value=".$this->Formato2($cod_tipo)." />'; ";
		echo "</script>";


$this->set('Message_existe', 'PUEDE EDITAR LOS DATOS');
}//fin function

function cancelar($cod_tipo=null){
$this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
 // $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  $accion =  $this->cimd05_conservacion_tipo_repuestos->findAll();
 /*
    //    echo "<script>";
            echo "document.getElementById('td_1_".$cod_tipo."').innerHTML='".$this->AddCeroR2($accion[0]['cimd01_clasificacion_tipo']['cod_tipo'])."';";
			echo "document.getElementById('td_2_".$cod_tipo."').innerHTML='".$this->$accion[0]['cimd01_clasificacion_tipo']['denominacion']."';";
			echo "document.getElementById('iconos_1_".$cod_tipo."').style.display = 'block'; ";
            echo "document.getElementById('iconos_2_".$cod_tipo."').style.display = 'none'; ";
	//	echo "</script>";
*/
	$this->set('datos',$accion);
	$this->index();
	$this->render("index");


}//fin function

function guardar_editar($var1){
  $this->layout = "ajax";
  $denominacion      =  $this->data['cimp05_conservacion_tipo_repuestos']['denominacion_'.$var1];
    $sql = " UPDATE cimd05_conservacion_tipo_repuestos SET denominacion='".$denominacion."' where cod_repuesto =".$var1;
	$this->cimd05_conservacion_tipo_repuestos->execute($sql);
    //echo $sql;
	$this->set('mensaje', 'Datos Actualizados Correctamente');
	$accion =  $this->cimd05_conservacion_tipo_repuestos->findAll();
	//$accion =  $this->cimd01_clasificacion_tipo->execute("select * from cimd01_clasificacion_tipo where cod_tipo =".$var1);
	$this->set('datos',$accion);
	$this->index();
	$this->render("index");

}//fin funtion

}
?>