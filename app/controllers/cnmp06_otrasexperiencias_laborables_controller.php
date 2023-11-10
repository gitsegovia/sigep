<?php

 class cnmp06ExperienciaAdministrativaController extends AppController {
 	var $name = 'cnmp06_experiencia_administrativa';
 	var $uses = array ('cnmd06_experiencia_administrativa', 'cnmd06_datos_personales');
 	var $helpers = array ('Html','Ajax','Javascript','Sisap');







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





function index(){
  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;






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
		  $accion =  $this->cnmd06_experiencia_administrativa->findAll('cedula='.$var.' and cod_institucion='.$cod_inst, null, null);
	 }//fin if





   $var1 = "";
   $var2 = "";
   $var3 = "";
   $var4 = "";
$cnmd06_datos_personales_aux = $this->cnmd06_datos_personales->findAll("cedula_identidad = '".$var."'");
foreach($cnmd06_datos_personales_aux as $ve){
	$var1 = $ve["cnmd06_datos_personales"]["primer_apellido"];
	$var2 = $ve["cnmd06_datos_personales"]["segundo_apellido"];
	$var3 = $ve["cnmd06_datos_personales"]["primer_nombre"];
	$var4 = $ve["cnmd06_datos_personales"]["segundo_nombre"];
}//fin foreach
       echo'<script>';
               echo" document.getElementById('p_apellido').value = '".$var1."'; ";
               echo" document.getElementById('s_apellido').value = '".$var2."'; ";
               echo" document.getElementById('p_nombre').value   = '".$var3."'; ";
               echo" document.getElementById('s_nombre').value   = '".$var4."'; ";
      echo'</script>';
if($var1!=""){ $disabled = "false";}else{
	 $accion = "";
	 $this->set('errorMessage', "NO ESTA REGISTRADO EN PERSONAL"); $disabled = "true";}




 $this->set('disabled', $disabled);
 $this->set('accion', $accion);
 $this->set('cedula', $var);

}//fin funtion






function guardar($var1=null){
  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;


   $entidad_federal   =  $this->data['cnmp06_experiencia_administrativa']['entidad_federal'];
   $cargo_desempenado =  $this->data['cnmp06_experiencia_administrativa']['cargo_desempenado'];
   $fecha_egreso      =  $this->data['cnmp06_experiencia_administrativa']['fecha_egreso'];
   $motivo_salida     =  $this->data['cnmp06_experiencia_administrativa']['motivo_salida'];
   $cod_institucion   =  $cod_inst;
   $cedula            =  $var1;
   $fecha_ingreso     =  $this->data['cnmp06_experiencia_administrativa']['fecha_ingreso'];





$sql="BEGIN; INSERT INTO cnmd06_experiencia_administrativa (cedula, cod_institucion, cargo_desempenado, entidad_federal, fecha_ingreso, fecha_egreso, motivo_salida)";
$sql.="VALUES ('".$cedula."', '".$cod_institucion."', '".$cargo_desempenado."', '".$entidad_federal."', '".$fecha_ingreso."', '".$fecha_egreso."', '".$motivo_salida."'); ";
$sw2 = $this->cnmd06_experiencia_administrativa->execute($sql);

			if($sw2>1){
                $this->cnmd06_experiencia_administrativa->execute("COMMIT;");
		        $this->set('Message_existe', 'Los datos fueron guardados correctamente');
			}else{
				$this->cnmd06_experiencia_administrativa->execute("ROLLBACK;");
				$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
			}//fin else

   if($var1!=null){
		  $accion =  $this->cnmd06_experiencia_administrativa->findAll('cedula='.$var1.' and cod_institucion='.$cod_inst, null, null);
	 }//fin if

  $this->set('accion', $accion);
  $this->set('cedula', $var1);

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




$sql="BEGIN;  DELETE FROM cnmd06_experiencia_administrativa  WHERE cedula='".$var1."' and cod_institucion='".$cod_inst."' and consecutivo='".$var2."'  ";
$sw2 = $this->cnmd06_experiencia_administrativa->execute($sql);

			if($sw2>1){
                $this->cnmd06_experiencia_administrativa->execute("COMMIT;");
		        $this->set('Message_existe', 'LOS DATOS FUERON ELIMINDOS CORRECTAMENTE');
			}else{
				$this->cnmd06_experiencia_administrativa->execute("ROLLBACK;");
				$this->set('errorMessage', 'LOS DATOS NO FUERON ELIMINDOS - POR FAVOR INTENTE DE NUEVO');
			}//fin else



}//fin funtion





 }//fin lcass

 ?>