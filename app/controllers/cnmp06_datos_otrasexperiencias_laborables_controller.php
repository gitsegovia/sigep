<?php

 class cnmp06DatosOtrasexperienciasLaborablesController extends AppController {
 	var $name = 'cnmp06_datos_otrasexperiencias_laborables';
 	var $uses = array ('cnmd06_datos_otrasexperiencias_laborables', 'cnmd06_datos_personales');
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
		  $accion =  $this->cnmd06_datos_otrasexperiencias_laborables->findAll('cedula='.$var, null, "fecha_ingreso ASC");
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
if(!empty($this->data['cnmp06_datos_otrasexperiencias_laborables']['empresa'.$var_n]) &&
   !empty($this->data['cnmp06_datos_otrasexperiencias_laborables']['cargo_desempenado'.$var_n]) &&
   !empty($this->data['cnmp06_datos_otrasexperiencias_laborables']['fecha_egreso'.$var_n]) &&
   !empty($this->data['cnmp06_datos_otrasexperiencias_laborables']['fecha_ingreso'.$var_n]) &&
   !empty($this->data['cnmp06_datos_otrasexperiencias_laborables']['motivo_retiro'.$var_n])
){
   $empresa   =  $this->data['cnmp06_datos_otrasexperiencias_laborables']['empresa'.$var_n];
   $cargo_desempenado =  $this->data['cnmp06_datos_otrasexperiencias_laborables']['cargo_desempenado'.$var_n];
   $fecha_egreso      =  cambiar_formato_fecha($this->data['cnmp06_datos_otrasexperiencias_laborables']['fecha_egreso'.$var_n]);
   $fecha_ingreso     =  cambiar_formato_fecha($this->data['cnmp06_datos_otrasexperiencias_laborables']['fecha_ingreso'.$var_n]);
   $motivo_retiro     =  $this->data['cnmp06_datos_otrasexperiencias_laborables']['motivo_retiro'.$var_n];
   $cod_institucion   =  $cod_inst;
   $cedula            =  $var1;
					if($var_n==null){
										$sql="BEGIN; INSERT INTO cnmd06_datos_otrasexperiencias_laborables (cedula, cargo_desempenado, empresa, fecha_ingreso, fecha_egreso, motivo_retiro)";
										$sql.="VALUES ('".$cedula."', '".$cargo_desempenado."', '".$empresa."', '".$fecha_ingreso."', '".$fecha_egreso."', '".$motivo_retiro."'); ";
										$sw2 = $this->cnmd06_datos_otrasexperiencias_laborables->execute($sql);

													if($sw2>1){
										                $this->cnmd06_datos_otrasexperiencias_laborables->execute("COMMIT;");
												        $this->set('Message_existe', 'Los datos fueron guardados correctamente');
													}else{
														$this->cnmd06_datos_otrasexperiencias_laborables->execute("ROLLBACK;");
														$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
													}//fin else

										   if($var1!=null){
												  $accion =  $this->cnmd06_datos_otrasexperiencias_laborables->findAll('cedula='.$var1, null, "fecha_ingreso ASC");
											 }//fin if
										  $this->set('accion', $accion);
										  $this->set('cedula', $var1);
					}else{
						                $sql="BEGIN;  UPDATE cnmd06_datos_otrasexperiencias_laborables SET empresa='".$empresa."', cargo_desempenado='".$cargo_desempenado."', fecha_ingreso='".$fecha_ingreso."', fecha_egreso='".$fecha_egreso."' , motivo_retiro='".$motivo_retiro."'  WHERE  cedula='".$var1."'  and consecutivo='".$var_n."'" ;
										$sw2 = $this->cnmd06_datos_otrasexperiencias_laborables->execute($sql);
													if($sw2>1){
										                $this->cnmd06_datos_otrasexperiencias_laborables->execute("COMMIT;");
												        $this->set('Message_existe', 'Los datos fueron guardados correctamente');
													}else{
														$this->cnmd06_datos_otrasexperiencias_laborables->execute("ROLLBACK;");
														$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
													}//fin else
                                         $var_datos = $this->cnmd06_datos_otrasexperiencias_laborables->findAll("cedula=".$var1." and consecutivo=".$var_n);
								         $var_a = $var_datos[0]['cnmd06_datos_otrasexperiencias_laborables']['empresa'];
								         $var_b = $var_datos[0]['cnmd06_datos_otrasexperiencias_laborables']['cargo_desempenado'];
								         $var_c = $var_datos[0]['cnmd06_datos_otrasexperiencias_laborables']['fecha_ingreso'];
								         $var_d = $var_datos[0]['cnmd06_datos_otrasexperiencias_laborables']['fecha_egreso'];
								         $var_e = $var_datos[0]['cnmd06_datos_otrasexperiencias_laborables']['motivo_retiro'];
								      echo'<script>';
								                   echo" document.getElementById('iconos_1_".$var_n."').style.display = 'block'; ";
								                   echo" document.getElementById('iconos_2_".$var_n."').style.display = 'none'; ";
								                   echo" document.getElementById('campo_a_".$var_n."').innerHTML = '".$var_a."'; ";
								                   echo" document.getElementById('campo_b_".$var_n."').innerHTML = '".$var_b."'; ";
								                   echo" document.getElementById('campo_c_".$var_n."').innerHTML = '".cambia_fecha($var_c)."'; ";
								                   echo" document.getElementById('campo_d_".$var_n."').innerHTML = '".cambia_fecha($var_d)."'; ";
								                   echo" document.getElementById('campo_e_".$var_n."').innerHTML = '".$var_e."'; ";
								     echo'</script>';
								$this->render("funcion");
					}//fin else
}else{


							if($var_n!=null){
		                                 $var_datos = $this->cnmd06_datos_otrasexperiencias_laborables->findAll("cedula=".$var1." and consecutivo=".$var_n);
								         $var_a = $var_datos[0]['cnmd06_datos_otrasexperiencias_laborables']['empresa'];
								         $var_b = $var_datos[0]['cnmd06_datos_otrasexperiencias_laborables']['cargo_desempenado'];
								         $var_c = $var_datos[0]['cnmd06_datos_otrasexperiencias_laborables']['fecha_ingreso'];
								         $var_d = $var_datos[0]['cnmd06_datos_otrasexperiencias_laborables']['fecha_egreso'];
								         $var_e = $var_datos[0]['cnmd06_datos_otrasexperiencias_laborables']['motivo_retiro'];
								      echo'<script>';
								                   echo" document.getElementById('iconos_1_".$var_n."').style.display = 'block'; ";
								                   echo" document.getElementById('iconos_2_".$var_n."').style.display = 'none'; ";
								                   echo" document.getElementById('campo_a_".$var_n."').innerHTML = '".$var_a."'; ";
								                   echo" document.getElementById('campo_b_".$var_n."').innerHTML = '".$var_b."'; ";
								                   echo" document.getElementById('campo_c_".$var_n."').innerHTML = '".cambia_fecha($var_c)."'; ";
								                   echo" document.getElementById('campo_d_".$var_n."').innerHTML = '".cambia_fecha($var_d)."'; ";
								                   echo" document.getElementById('campo_e_".$var_n."').innerHTML = '".$var_e."'; ";
								     echo'</script>';
							}//fin if
		  $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
		  $this->render("funcion");



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




$sql="BEGIN;  DELETE FROM cnmd06_datos_otrasexperiencias_laborables  WHERE cedula='".$var1."'  and consecutivo='".$var2."'  ";
$sw2 = $this->cnmd06_datos_otrasexperiencias_laborables->execute($sql);

			if($sw2>1){
                $this->cnmd06_datos_otrasexperiencias_laborables->execute("COMMIT;");
		        $this->set('Message_existe', 'LOS DATOS FUERON ELIMINDOS CORRECTAMENTE');
			}else{
				$this->cnmd06_datos_otrasexperiencias_laborables->execute("ROLLBACK;");
				$this->set('errorMessage', 'LOS DATOS NO FUERON ELIMINDOS - POR FAVOR INTENTE DE NUEVO');
			}//fin else



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
         $var_datos = $this->cnmd06_datos_otrasexperiencias_laborables->findAll("cedula=".$var1." and consecutivo=".$var2);
         $var_a = $var_datos[0]['cnmd06_datos_otrasexperiencias_laborables']['empresa'];
         $var_b = $var_datos[0]['cnmd06_datos_otrasexperiencias_laborables']['cargo_desempenado'];
         $var_c = $var_datos[0]['cnmd06_datos_otrasexperiencias_laborables']['fecha_ingreso'];
         $var_d = $var_datos[0]['cnmd06_datos_otrasexperiencias_laborables']['fecha_egreso'];
         $var_e = $var_datos[0]['cnmd06_datos_otrasexperiencias_laborables']['motivo_retiro'];
         $this->set('var2',$var2);
         $this->set('var_a',$var_a);
         $this->set('var_b',$var_b);
         $this->set('var_c',$var_c);
         $this->set('var_d',$var_d);
         $this->set('var_e',$var_e);
/*
$aux_a = "<input name=data[cnmp06_datos_otrasexperiencias_laborables][fecha_ingreso".$var2."] value=".cambia_fecha($var_c)." id=fecha_ingreso".$var2." style=text-align:right size=7 readOnly><img src=/img/date.png onclick=displayCalendar(document.forms[0].fecha_ingreso".$var2.",formarto,this) style=margin:0pt;padding:0pt alt=Calendario border=0 width=13%>";
$aux_b = "<input name=data[cnmp06_datos_otrasexperiencias_laborables][fecha_egreso".$var2."] value=".cambia_fecha($var_d)." id=fecha_egreso".$var2." style=text-align:right size=7 readOnly><img src=/img/date.png onclick=displayCalendar(document.forms[0].fecha_egreso".$var2.",formarto,this) style=margin:0pt;padding:0pt alt=Calendario border=0 width=13%>";

      echo'<script> formarto = "dd/mm/yyyy"; ';
           echo" document.getElementById('iconos_1_".$var2."').style.display = 'none'; ";
           echo" document.getElementById('iconos_2_".$var2."').style.display = 'block'; ";
           echo" document.getElementById('campo_a_".$var2."').innerHTML = '<input name=data[cnmp06_datos_otrasexperiencias_laborables][empresa".$var2."]              value=\'".$var_a."\'  id=empresa".$var2."              style=text-align:left class=inputtext>'; ";
           echo" document.getElementById('campo_b_".$var2."').innerHTML = '<input name=data[cnmp06_datos_otrasexperiencias_laborables][cargo_desempenado".$var2."]    value=\'".$var_b."\'  id=cargo_desempenado".$var2."    style=text-align:left class=inputtext>'; ";
           echo" document.getElementById('campo_c_".$var2."').innerHTML = '$aux_a'; ";
           echo" document.getElementById('campo_d_".$var2."').innerHTML = '$aux_b'; ";
           echo" document.getElementById('campo_e_".$var2."').innerHTML = '<input name=data[cnmp06_datos_otrasexperiencias_laborables][motivo_retiro".$var2."]        value=\'".$var_e."\'  id=motivo_retiro".$var2."    style=text-align:left class=inputtext>'; ";
     echo'</script>';*/

//$this->render("funcion");
}//fin function














function cancelar($var1=null, $var2=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
         $var_datos = $this->cnmd06_datos_otrasexperiencias_laborables->findAll("cedula=".$var1." and consecutivo=".$var2);
         $var_a = $var_datos[0]['cnmd06_datos_otrasexperiencias_laborables']['empresa'];
         $var_b = $var_datos[0]['cnmd06_datos_otrasexperiencias_laborables']['cargo_desempenado'];
         $var_c = $var_datos[0]['cnmd06_datos_otrasexperiencias_laborables']['fecha_ingreso'];
         $var_d = $var_datos[0]['cnmd06_datos_otrasexperiencias_laborables']['fecha_egreso'];
         $var_e = $var_datos[0]['cnmd06_datos_otrasexperiencias_laborables']['motivo_retiro'];

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