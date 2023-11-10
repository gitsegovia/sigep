<?php
 class Cpcp01Controller extends AppController{
	var $name = 'cpcp01';
	var $uses = array('cpcd01','cimd05_conservacion_tipo_reparacion');
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
	$denominacion = $this->cpcd01->generateList(null,'codigo ASC', null, '{n}.cpcd01.codigo', '{n}.cpcd01.denominacion');
	$denominacion = $denominacion != null ? $denominacion : array();
	$datos=$this->cpcd01->findAll(null,null,' upper(trim(denominacion)) ASC');
	$datos2=$this->cpcd01->findAll(null,null,'codigo DESC');
	 if($datos2==null){
     	$new_numero=1;
     }else{
     	$new_numero=$datos2[0]["cpcd01"]["codigo"]+1;
     }
	$this->set('new_numero',$new_numero);
	$this->concatena($denominacion, 'denominacion');
	$this->set('datos',$datos);
 }//index


 function mostrar1($select=null){
	$this->layout="ajax";
	if($select!=null){
		$dato=$this->cpcd01->findAll('codigo='.$select);
		$this->set('codigo',$dato[0]['cpcd01']['codigo']);
		$this->set('denominacion',$dato[0]['cpcd01']['denominacion']);
	}else{
		$this->set('codigo','');
		$this->set('denominacion','');
		$this->set('mensajeError','No ha seleccionado nigun grupo');
	}
 }//mostrar1


 function guardar(){
	$this->layout="ajax";
	if($this->data['cpcp01']['codigo'] !="" && $this->data['cpcp01']['denominacion'] !=""){
		if($this->cpcd01->findAll('codigo='.$this->data['cpcp01']['codigo'])){
			$this->set('mensajeError','LO SIENTO EL CÓDIGO('.$this->data['cpcp01']['codigo'].') YA SE ENCUENTRA REGISTRAD0 EN EL SISTEMA');
			$datos=$this->cpcd01->findAll(null,null,'codigo ASC');
		    $this->set('datos',$datos);
		}else{
		   $sql="INSERT INTO cpcd01 VALUES ('".$this->data['cpcp01']['codigo']."','".$this->data['cpcp01']['denominacion']."')";
		   if($this->cpcd01->execute($sql)>1){
		      $this->set('mensaje','EL TIPO FUE AGREGADO CORRECTAMENTE');
		      $datos=$this->cpcd01->findAll(null,null,' upper(trim(denominacion)) ASC');
		      $this->set('datos',$datos);
		   }else{
		      $this->set('mensajeError','LO SIENTO, EL TIPO NO PUDO SER AGREGADO');
		      $datos=$this->cpcd01->findAll(null,null,' upper(trim(denominacion)) ASC');
		   	  $this->set('datos',$datos);
		   }
		}//fin else consulta
	}else{
		$this->set('mensajeError','ATENCI&Oacute;N, DEBE INGRESAR EL CÓDIGO Y LA DENOMINACIÓN DEL RAMO COMERCIAL');
		$datos=$this->cpcd01->findAll(null,null,'codigo ASC');
		$this->set('datos',$datos);
	}
	echo "<script>document.getElementById('agregar').disabled='';</script>";
	$this->index();
	$this->render("index");

 }//guardar


 function guardar_modificar($codigo=null){
	$this->layout="ajax";
	if($this->data['cpcp01']['codigo'] !="" && $this->data['cpcp01']['denominacion'] !=""){
		$codigo=$this->data['cpcp01']['codigo'];
		$deno=$this->data['cpcp01']['denominacion'];
		$sql_update="UPDATE cpcd01 SET denominacion='".$this->data['cpcp01']['denominacion']."' WHERE codigo=$codigo";
		if($this->cpcd01->execute($sql_update)>0){
			$this->set('mensaje','EL REGISTRO FUE MODIFICADO CORRECTAMENTE');
			$this->index();
			$this->render("index");
		}else{
			$this->set('mensajeError','LO SIENTO, EL REGISTRO NO PUDO SER MODIFICADO');
			$this->index();
			$this->render("index");
		}
	}else{
		$this->set('mensajeError','ATENCI&Oacute;N, DEBE INGRESAR LA DENOMINACIÓN DEL RAMO COMERCIAL');
		$this->set('datos',null);
		$this->index();
		$this->render("index");
	}
 }//guardar modificar




 function eliminar($codigo=null){
	$this->layout="ajax";
    if($codigo!=null){
      /* if($this->cnmd06_datos_personales->findBycodigo($codigo)){
	   $this->set('mensajeError','LA PROFESION NO PUEDE SER ELIMINADA, YA SE ENCUENTRA PRESENTE EN LOS DATOS PERSONALES');
	   }else{*/
		   $sql="DELETE FROM cpcd01 WHERE codigo=".$codigo;
		   if($this->cpcd01->execute($sql)>1){
		   $this->set('mensaje','REGISTRO FUE ELIMINADO CORRECTAMENTE');
		   }else{
		   $this->set('mensajeError','LO SIENTO, TIPO NO PUDO SER ELIMINADO');
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
 			$datos=$this->cpcd01->findAll(null,null,' upper(trim(denominacion)) ASC');
			$this->set('datos',$datos);
 		}elseif($var==2){
 			$datos=$this->cpcd01->findAll(null,null,' upper(trim(denominacion)) ASC');
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
  $accion =  $this->cpcd01->findAll("codigo =".$cod_tipo, null, null);
 $denominacion = $accion[0]['cpcd01']['denominacion'];

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
  $accion =  $this->cpcd01->findAll(null,null,' upper(trim(denominacion)) ASC');
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
  $denominacion      =  $this->data['cpcp01']['denominacion_'.$var1];
    $sql = " UPDATE cpcd01 SET denominacion='".$denominacion."' where codigo =".$var1;
	$this->cpcd01->execute($sql);
    //echo $sql;
	$this->set('mensaje', 'Datos Actualizados Correctamente');
	$accion =  $this->cpcd01->findAll(null,null,' upper(trim(denominacion)) ASC');
	//$accion =  $this->cimd01_clasificacion_tipo->execute("select * from cimd01_clasificacion_tipo where cod_tipo =".$var1);
	$this->set('datos',$accion);
	$this->index();
	$this->render("index");

}//fin funtion



}
?>