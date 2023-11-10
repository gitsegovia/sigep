<?php

 class Infocnmp06ExperienciaAdministrativaController extends AppController {
 	var $name = 'info_cnmp06_experiencia_administrativa';
 	var $uses = array ('cnmd06_experiencia_administrativa', 'cnmd06_datos_personales');
 	var $helpers = array ('Html','Ajax','Javascript','Sisap','Infogob');

function checkSession(){
				if (!$this->Session->check('infogobierno')){
						$this->redirect('/infogobierno/salir_todo');
						exit();
				}
}//fin checksession

function beforeFilter(){
 	$this->checkSession();
 }





function index($id=null){
  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;


if($this->Session->read('cedula_pestana_expediente')==""){
         	$id=0;
    }else{
    	    $id=$this->Session->read('cedula_pestana_expediente');
    	 }//fin else

    	$this->set('cedula', "");
    	$Tfilas=$this->cnmd06_datos_personales->findCount("cedula_identidad=".$id);
       if($Tfilas!=0){
          $this->index2($this->Session->read('cedula_pestana_expediente'));
          $this->render("index2");
       }else{
       	    $this->set('ci',"");
		    $this->set('pa',"");
		    $this->set('sa',"");
		    $this->set('pn',"");
		    $this->set('sn',"");
      }//fin else



}//fin funtion





function index2($var=null){
  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  $accion = "";

	 if($var!=null){
		  $accion =  $this->cnmd06_experiencia_administrativa->findAll('cedula='.$var, null, null);
	 }//fin if


$cond ="cedula_identidad=".$var;

$a = $this->cnmd06_datos_personales->findAll($cond);
		    $pa=$a[0]['cnmd06_datos_personales']['primer_apellido'];
		    $sa=$a[0]['cnmd06_datos_personales']['segundo_apellido'];
		    $pn=$a[0]['cnmd06_datos_personales']['primer_nombre'];
		    $sn=$a[0]['cnmd06_datos_personales']['segundo_nombre'];
		    $this->set('ci',$var);
		    $this->set('pa',$pa);
		    $this->set('sa',$sa);
		    $this->set('pn',$pn);
		    $this->set('sn',$sn);

$disabled = "false";
$this->set('disabled', $disabled);
$this->set('accion', $accion);

}//fin funtion






function guardar($var1=null, $var_n=null){
  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
//pr($this->data);
if($var1==null){
 		$var1 = $_SESSION['infogobierno']['cedula_identidad'];
	}
if(!empty($this->data['cnmp06_experiencia_administrativa']['entidad_federal'.$var_n]) &&
   !empty($this->data['cnmp06_experiencia_administrativa']['cargo_desempenado'.$var_n]) &&
   !empty($this->data['cnmp06_experiencia_administrativa']['fecha_egreso'.$var_n]) &&
   !empty($this->data['cnmp06_experiencia_administrativa']['fecha_ingreso'.$var_n]) &&
   !empty($this->data['cnmp06_experiencia_administrativa']['motivo_salida'.$var_n])
){
   $entidad_federal   =  $this->data['cnmp06_experiencia_administrativa']['entidad_federal'.$var_n];
   $cargo_desempenado =  $this->data['cnmp06_experiencia_administrativa']['cargo_desempenado'.$var_n];
   $fecha_egreso      =  $this->data['cnmp06_experiencia_administrativa']['fecha_egreso'.$var_n];
   $fecha_ingreso     =  $this->data['cnmp06_experiencia_administrativa']['fecha_ingreso'.$var_n];
   $motivo_salida     =  $this->data['cnmp06_experiencia_administrativa']['motivo_salida'.$var_n];
   $cod_institucion   =  $cod_inst;
   $cedula            =  $var1;
					if($var_n==null){
										$sql="BEGIN; INSERT INTO cnmd06_datos_experiencia_administrativa (cedula, cargo_desempenado, entidad_federal, fecha_ingreso, fecha_egreso, motivo_salida)";
										$sql.="VALUES ('".$cedula."', '".$cargo_desempenado."', '".$entidad_federal."', '".$fecha_ingreso."', '".$fecha_egreso."', '".$motivo_salida."'); ";
										$sw2 = $this->cnmd06_experiencia_administrativa->execute($sql);

													if($sw2>1){
										                $this->cnmd06_experiencia_administrativa->execute("COMMIT;");
												        $this->set('msj', array('Los datos fueron guardados correctamente','exito'));
													}else{
														$this->cnmd06_experiencia_administrativa->execute("ROLLBACK;");
														$this->set('msj', array('LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO','error'));
													}//fin else

										   if($var1!=null){
												  $accion =  $this->cnmd06_experiencia_administrativa->findAll('cedula='.$var1, null, null);
											 }//fin if
										  $this->set('accion', $accion);
										  $this->set('cedula', $var1);
					}else{
						                $sql="BEGIN;  UPDATE cnmd06_datos_experiencia_administrativa SET entidad_federal='".$entidad_federal."', cargo_desempenado='".$cargo_desempenado."', fecha_ingreso='".$fecha_ingreso."', fecha_egreso='".$fecha_egreso."' , motivo_salida='".$motivo_salida."'  WHERE  cedula='".$var1."'  and consecutivo='".$var_n."'" ;
										$sw2 = $this->cnmd06_experiencia_administrativa->execute($sql);
													if($sw2>1){
										                $this->cnmd06_experiencia_administrativa->execute("COMMIT;");
												        $this->set('msj', array('Los datos fueron guardados correctamente','exito'));
													}else{
														$this->cnmd06_experiencia_administrativa->execute("ROLLBACK;");
														$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
														$this->set('msj', array('Los datos fueron guardados correctamente','exito'));
													}//fin else
					}//fin else
					$this->index2($var1);
					$this->render("index2");
}else{

		  $this->set('msj', array('LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO','error'));
		  $this->index2($var1);
		  $this->render("index2");



}//fin else



          echo "<script>";
		      echo" document.getElementById('entidad_federal').value = '';";
			  echo" document.getElementById('cargo_desempenado').value = '';";
			  echo" document.getElementById('fecha_egreso').value = '';";
			  echo" document.getElementById('fecha_ingreso').value = '';";
			  echo" document.getElementById('motivo_salida').value = '';";
		  echo "</script>";




}//fin funtion





function eliminar($var1=null, $var2=null){
  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;




$sql="BEGIN;  DELETE FROM cnmd06_datos_experiencia_administrativa  WHERE cedula='".$var1."'  and consecutivo='".$var2."'  ";
$sw2 = $this->cnmd06_experiencia_administrativa->execute($sql);

			if($sw2>1){
                $this->cnmd06_experiencia_administrativa->execute("COMMIT;");
		        $this->set('msj', array('LOS DATOS FUERON ELIMINDOS CORRECTAMENTE','exito'));
			}else{
				$this->cnmd06_experiencia_administrativa->execute("ROLLBACK;");
				$this->set('msj', array('LOS DATOS NO FUERON ELIMINDOS - POR FAVOR INTENTE DE NUEVO','error'));
			}//fin else

			$this->index2($var1);
		    $this->render("index2");



}//fin funtion








function funcion(){$this->layout = "ajax";}








function editar($var1=null, $var2=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
         $var_datos = $this->cnmd06_experiencia_administrativa->findAll("cedula=".$var1." and consecutivo=".$var2);
         $var_a = $var_datos[0]['cnmd06_experiencia_administrativa']['entidad_federal'];
         $var_b = $var_datos[0]['cnmd06_experiencia_administrativa']['cargo_desempenado'];
         $var_c = $var_datos[0]['cnmd06_experiencia_administrativa']['fecha_ingreso'];
         $var_d = $var_datos[0]['cnmd06_experiencia_administrativa']['fecha_egreso'];
         $var_e = $var_datos[0]['cnmd06_experiencia_administrativa']['motivo_salida'];
		 $this->set('var_1',$var1);
		 $this->set('var_2',$var2);
		 $this->set('var_a',$var_a);
		 $this->set('var_b',$var_b);
		 $this->set('var_c',$var_c);
		 $this->set('var_d',$var_d);
		 $this->set('var_e',$var_e);
}//fin function














function cancelar($var1=null, $var2=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
         $var_datos = $this->cnmd06_experiencia_administrativa->findAll("cedula=".$var1." and consecutivo=".$var2);
         $var_a = $var_datos[0]['cnmd06_experiencia_administrativa']['entidad_federal'];
         $var_b = $var_datos[0]['cnmd06_experiencia_administrativa']['cargo_desempenado'];
         $var_c = $var_datos[0]['cnmd06_experiencia_administrativa']['fecha_ingreso'];
         $var_d = $var_datos[0]['cnmd06_experiencia_administrativa']['fecha_egreso'];
         $var_e = $var_datos[0]['cnmd06_experiencia_administrativa']['motivo_salida'];

      echo'<script>';
                   echo" document.getElementById('iconos_1_".$var2."').style.display = 'block'; ";
                   echo" document.getElementById('iconos_2_".$var2."').style.display = 'none'; ";
                   echo" document.getElementById('campo_a_".$var2."').innerHTML = '".$var_a."'; ";
                   echo" document.getElementById('campo_b_".$var2."').innerHTML = '".$var_b."'; ";
                   echo" document.getElementById('campo_c_".$var2."').innerHTML = '".cambia_fecha($var_c)."'; ";
                   echo" document.getElementById('campo_d_".$var2."').innerHTML = '".cambia_fecha($var_d)."'; ";
                   echo" document.getElementById('campo_e_".$var2."').innerHTML = '".$var_e."'; ";

     echo'</script>';

$this->render("funcion");
}//fin function









 }//fin lcass

 ?>