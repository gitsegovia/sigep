<?php

 class cnmp06DatosPermisosController extends AppController {
 	var $name = 'cnmp06_datos_permisos';
 	var $uses = array ('cnmd06_datos_permisos', 'cnmd06_datos_personales', 'cnmd06_permisos', 'cnmd06_fichas');
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


 $this->set('permisos',$this->cnmd06_permisos->findAll(null, null, "denominacion ASC"));
 $this->concatena($this->cnmd06_permisos->generateList(null, "denominacion ASC", null, '{n}.cnmd06_permisos.cod_permiso', '{n}.cnmd06_permisos.denominacion'), "lista_deno");


if($this->Session->read('cedula_pestana_expediente')==""){
         	$id=0;
    }else{
    	    $id=$this->Session->read('cedula_pestana_expediente');
			// $var_datos_ficha = $this->cnmd06_fichas->findAll("cod_presi = '".$cod_presi."' and cod_entidad = '".$cod_entidad."'and cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."' and cod_dep = '".$cod_dep."' and cedula_identidad=".$id);
			// $cond_activ = $var_datos_ficha[0]['cnmd06_fichas']['condicion_actividad']!=null ? $var_datos_ficha[0]['cnmd06_fichas']['condicion_actividad'] : '';
    }//fin else
	$this->set('cedula', "");
	$Tfilas=$this->cnmd06_datos_personales->findCount("cedula_identidad=".$id);
   if($Tfilas!=0){
      $this->index2($this->Session->read('cedula_pestana_expediente'));
      $this->render("index2");
   }else{
   		// $this->set('cond_activ', isset($cond_activ)?$cond_activ:'');
   		$this->set('cond_activ', '');
   	    $this->set('ci',"");
	    $this->set('pa',"");
	    $this->set('sa',"");
	    $this->set('pn',"");
	    $this->set('sn',"");
  }//fin else



}//fin funtion






function selecion($var=null){

$this->layout = "ajax";

$datos =  $this->cnmd06_permisos->findAll("cod_permiso=".$var);

      echo'<script>';
               echo" document.getElementById('cod_permiso').value = '".$this->AddCeroR($datos[0]["cnmd06_permisos"]["cod_permiso"])."'; ";
               echo" document.getElementById('entidad_federal').value = '".$datos[0]["cnmd06_permisos"]["denominacion"]."'; ";
      echo'</script>';

}//fin function




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
                                        $cod_dep_expediente     =  $this->Session->read('cod_dep_expediente');
		  							    $cod_tipo_nomina        =  $this->Session->read('cod_tipo_nomina_expediente');
							  			$cod_cargo              =  $this->Session->read('cod_cargo_expediente');
							  			$cod_ficha              =  $this->Session->read('cod_ficha_expediente');

	 	  $var_datos_ficha = $this->cnmd06_fichas->findAll("cod_presi = '".$cod_presi."' and cod_entidad = '".$cod_entidad."'and cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."' and cod_dep = '".$cod_dep."' and cedula_identidad=".$var." and cod_tipo_nomina = '".$cod_tipo_nomina."' and cod_cargo = '".$cod_cargo."' and cod_ficha = ".$cod_ficha);
		  //$cod_tipo_nomina = $var_datos_ficha[0]['cnmd06_fichas']['cod_tipo_nomina'];
		  //$cod_cargo       = $var_datos_ficha[0]['cnmd06_fichas']['cod_cargo'];
		  //$cod_ficha       = $var_datos_ficha[0]['cnmd06_fichas']['cod_ficha'];

		  $cond_activ = $var_datos_ficha[0]['cnmd06_fichas']['condicion_actividad']!=null ? $var_datos_ficha[0]['cnmd06_fichas']['condicion_actividad'] : '';

		  $accion =  $this->cnmd06_datos_permisos->findAll("cod_presi = '".$cod_presi."' and cod_entidad = '".$cod_entidad."' and cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."' and cod_dep = '".$cod_dep_expediente."' and cod_tipo_nomina = '".$cod_tipo_nomina."' and cod_cargo = '".$cod_cargo."' and cod_ficha = '".$cod_ficha."' and cedula=".$var, null, null);
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


            $a = $this->cnmd06_datos_personales->findAll("cedula_identidad = '".$var."'");
		    $pa=$a[0]['cnmd06_datos_personales']['primer_apellido'];
		    $sa=$a[0]['cnmd06_datos_personales']['segundo_apellido'];
		    $pn=$a[0]['cnmd06_datos_personales']['primer_nombre'];
		    $sn=$a[0]['cnmd06_datos_personales']['segundo_nombre'];
		    $this->set('ci',$var);
		    $this->set('pa',$pa);
		    $this->set('sa',$sa);
		    $this->set('pn',$pn);
		    $this->set('sn',$sn);

if($var1!=""){ $disabled = "false";}else{
	 $accion = "";
	 $this->set('errorMessage', "NO ESTA REGISTRADO EN PERSONAL"); $disabled = "true";}



 $this->set('cond_activ', isset($cond_activ)?$cond_activ:'');
 $this->set('disabled', $disabled);
 $this->set('accion', $accion);
 $this->set('cedula', $var);

 $this->set('permisos',$this->cnmd06_permisos->findAll(null, null, "denominacion ASC"));
 $this->concatena($this->cnmd06_permisos->generateList(null, "denominacion ASC", null, '{n}.cnmd06_permisos.cod_permiso', '{n}.cnmd06_permisos.denominacion'), "lista_deno");




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


if(!empty($this->data['cnmp06_datos_permisos']['cod_permiso'.$var_n]) &&
   !empty($this->data['cnmp06_datos_permisos']['fecha_reintegro'.$var_n]) &&
   !empty($this->data['cnmp06_datos_permisos']['fecha_salida'.$var_n]) &&
   !empty($this->data['cnmp06_datos_permisos']['observaciones'.$var_n])
){
   $cod_permiso   =  $this->data['cnmp06_datos_permisos']['cod_permiso'.$var_n];
   $fecha_reintegro      =  $this->data['cnmp06_datos_permisos']['fecha_reintegro'.$var_n];
   $fecha_salida     =  $this->data['cnmp06_datos_permisos']['fecha_salida'.$var_n];
   $observaciones     =  $this->data['cnmp06_datos_permisos']['observaciones'.$var_n];
   $cod_institucion   =  $cod_inst;
   $cedula            =  $var1;
					if($var_n==null){

						                $cod_dep_expediente     =  $this->Session->read('cod_dep_expediente');
						  				$cod_tipo_nomina        =  $this->Session->read('cod_tipo_nomina_expediente');
							  			$cod_cargo              =  $this->Session->read('cod_cargo_expediente');
							  			$cod_ficha              =  $this->Session->read('cod_ficha_expediente');


										$sql="BEGIN; INSERT INTO cnmd06_datos_permisos (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula, cod_permiso, fecha_salida, fecha_reintegro, observaciones)";
										$sql.="VALUES ('".$cod_presi."','".$cod_entidad."','".$cod_tipo_inst."','".$cod_inst."','".$cod_dep_expediente."','".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula."','".$cod_permiso."', '".$fecha_salida."', '".$fecha_reintegro."', '".$observaciones."'); ";
										$sw2 = $this->cnmd06_datos_permisos->execute($sql);

													if($sw2>1){
										                $this->cnmd06_datos_permisos->execute("COMMIT;");
												        $this->set('Message_existe', 'Los datos fueron guardados correctamente');
													}else{
														$this->cnmd06_datos_permisos->execute("ROLLBACK;");
														$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
													}//fin else

										   if($var1!=null){
												  $accion =  $this->cnmd06_datos_permisos->findAll("cod_presi = '".$cod_presi."' and cod_entidad = '".$cod_entidad."'and cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."' and cod_dep = '".$cod_dep_expediente."' and cod_tipo_nomina = '".$cod_tipo_nomina."' and cod_cargo = '".$cod_cargo."' and cod_ficha = '".$cod_ficha."' and cedula=".$var1, null, null);
											 }//fin if
										  $this->set('accion', $accion);
										  $this->set('cedula', $var1);
					}else{
						                $cod_dep_expediente     =  $this->Session->read('cod_dep_expediente');
						  				$cod_tipo_nomina        =  $this->Session->read('cod_tipo_nomina_expediente');
							  			$cod_cargo              =  $this->Session->read('cod_cargo_expediente');
							  			$cod_ficha              =  $this->Session->read('cod_ficha_expediente');


						                $sql="BEGIN;  UPDATE cnmd06_datos_permisos SET  fecha_salida='".$fecha_salida."', fecha_reintegro='".$fecha_reintegro."' , observaciones='".$observaciones."'  WHERE  cod_presi = '".$cod_presi."' and cod_entidad = '".$cod_entidad."'and cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."' and cod_dep = '".$cod_dep_expediente."' and cod_tipo_nomina = '".$cod_tipo_nomina."' and cod_cargo = '".$cod_cargo."' and cod_ficha = '".$cod_ficha."' and  cedula='".$var1."' and consecutivo='".$var_n."'" ;
										$sw2 = $this->cnmd06_datos_permisos->execute($sql);
													if($sw2>1){
										                $this->cnmd06_datos_permisos->execute("COMMIT;");
												        $this->set('Message_existe', 'Los datos fueron guardados correctamente');
													}else{
														$this->cnmd06_datos_permisos->execute("ROLLBACK;");
														$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
													}//fin else
                                         $var_datos = $this->cnmd06_datos_permisos->findAll("cod_presi = '".$cod_presi."' and cod_entidad = '".$cod_entidad."'and cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."' and cod_dep = '".$cod_dep_expediente."' and cod_tipo_nomina = '".$cod_tipo_nomina."' and cod_cargo = '".$cod_cargo."' and cod_ficha = '".$cod_ficha."' and cedula=".$var1." and consecutivo=".$var_n);
								         $var_a = $var_datos[0]['cnmd06_datos_permisos']['cod_permiso'];
								         $var_c = $var_datos[0]['cnmd06_datos_permisos']['fecha_salida'];
								         $var_d = $var_datos[0]['cnmd06_datos_permisos']['fecha_reintegro'];
								         $var_e = $var_datos[0]['cnmd06_datos_permisos']['observaciones'];
								      echo'<script>';
								                   echo" document.getElementById('iconos_1_".$var_n."').style.display = 'block'; ";
								                   echo" document.getElementById('iconos_2_".$var_n."').style.display = 'none'; ";
								                   echo" document.getElementById('campo_c_".$var_n."').innerHTML = '".cambia_fecha($var_c)."'; ";
								                   echo" document.getElementById('campo_d_".$var_n."').innerHTML = '".cambia_fecha($var_d)."'; ";
								                   echo" document.getElementById('campo_e_".$var_n."').innerHTML = '".$var_e."'; ";
								     echo'</script>';
								$this->render("funcion");
					}//fin else
}else{
	      echo "<script>";
	      echo" document.getElementById('cod_permiso').value = '';";
		  echo" document.getElementById('fecha_ingreso').value = '';";
		  echo" document.getElementById('fecha_egreso').value = '';";
		  echo" document.getElementById('motivo_salida').value = '';";
		  echo "</script>";

							if($var_n!=null){

								        $cod_dep_expediente     =  $this->Session->read('cod_dep_expediente');
						  				$cod_tipo_nomina        =  $this->Session->read('cod_tipo_nomina_expediente');
							  			$cod_cargo              =  $this->Session->read('cod_cargo_expediente');
							  			$cod_ficha              =  $this->Session->read('cod_ficha_expediente');


		                                 $var_datos = $this->cnmd06_datos_permisos->findAll("cod_presi = '".$cod_presi."' and cod_entidad = '".$cod_entidad."'and cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."' and cod_dep = '".$cod_dep_expediente."' and cod_tipo_nomina = '".$cod_tipo_nomina."' and cod_cargo = '".$cod_cargo."' and cod_ficha = '".$cod_ficha."' and cedula=".$var1." and consecutivo=".$var_n);
								         $var_a = $var_datos[0]['cnmd06_datos_permisos']['cod_permiso'];
								         $var_c = $var_datos[0]['cnmd06_datos_permisos']['fecha_salida'];
								         $var_d = $var_datos[0]['cnmd06_datos_permisos']['fecha_reintegro'];
								         $var_e = $var_datos[0]['cnmd06_datos_permisos']['observaciones'];
								      echo'<script>';
								                   echo" document.getElementById('iconos_1_".$var_n."').style.display = 'block'; ";
								                   echo" document.getElementById('iconos_2_".$var_n."').style.display = 'none'; ";
								                   echo" document.getElementById('campo_c_".$var_n."').innerHTML = '".cambia_fecha($var_c)."'; ";
								                   echo" document.getElementById('campo_d_".$var_n."').innerHTML = '".cambia_fecha($var_d)."'; ";
								                   echo" document.getElementById('campo_e_".$var_n."').innerHTML = '".$var_e."'; ";
								     echo'</script>';
							}//fin if
		  $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
		  $this->render("funcion");



}//fin else




 $this->set('permisos',$this->cnmd06_permisos->findAll(null, null, "denominacion ASC"));
 $this->concatena($this->cnmd06_permisos->generateList(null, "denominacion ASC", null, '{n}.cnmd06_permisos.cod_permiso', '{n}.cnmd06_permisos.denominacion'), "lista_deno");




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

                                        $cod_dep_expediente     =  $this->Session->read('cod_dep_expediente');
						  				$cod_tipo_nomina        =  $this->Session->read('cod_tipo_nomina_expediente');
							  			$cod_cargo              =  $this->Session->read('cod_cargo_expediente');
							  			$cod_ficha              =  $this->Session->read('cod_ficha_expediente');



$sql="BEGIN;  DELETE FROM cnmd06_datos_permisos  WHERE cod_presi = '".$cod_presi."' and cod_entidad = '".$cod_entidad."'and cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."' and cod_dep = '".$cod_dep_expediente."' and cod_tipo_nomina = '".$cod_tipo_nomina."' and cod_cargo = '".$cod_cargo."' and cod_ficha = '".$cod_ficha."' and cedula='".$var1."'  and consecutivo='".$var2."'  ";
$sw2 = $this->cnmd06_datos_permisos->execute($sql);

			if($sw2>1){
                $this->cnmd06_datos_permisos->execute("COMMIT;");
		        $this->set('Message_existe', 'LOS DATOS FUERON ELIMINDOS CORRECTAMENTE');
			}else{
				$this->cnmd06_datos_permisos->execute("ROLLBACK;");
				$this->set('errorMessage', 'LOS DATOS NO FUERON ELIMINDOS - POR FAVOR INTENTE DE NUEVO');
			}//fin else

 $this->set('permisos',$this->cnmd06_permisos->findAll(null, null, "denominacion ASC"));
 $this->concatena($this->cnmd06_permisos->generateList(null, "denominacion ASC", null, '{n}.cnmd06_permisos.cod_permiso', '{n}.cnmd06_permisos.denominacion'), "lista_deno");



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

                                        $cod_dep_expediente     =  $this->Session->read('cod_dep_expediente');
						  				$cod_tipo_nomina        =  $this->Session->read('cod_tipo_nomina_expediente');
							  			$cod_cargo              =  $this->Session->read('cod_cargo_expediente');
							  			$cod_ficha              =  $this->Session->read('cod_ficha_expediente');


         $var_datos = $this->cnmd06_datos_permisos->findAll("cod_presi = '".$cod_presi."' and cod_entidad = '".$cod_entidad."'and cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."' and cod_dep = '".$cod_dep_expediente."' and cod_tipo_nomina = '".$cod_tipo_nomina."' and cod_cargo = '".$cod_cargo."' and cod_ficha = '".$cod_ficha."' and  cedula=".$var1." and consecutivo=".$var2);
         $var_a = $var_datos[0]['cnmd06_datos_permisos']['cod_permiso'];
         $var_c = $var_datos[0]['cnmd06_datos_permisos']['fecha_salida'];
         $var_d = $var_datos[0]['cnmd06_datos_permisos']['fecha_reintegro'];
         $var_e = $var_datos[0]['cnmd06_datos_permisos']['observaciones'];

$aux_a = "<input name=data[cnmp06_datos_permisos][fecha_salida".$var2."] value=".cambia_fecha($var_c)." id=fecha_salida".$var2." style=text-align:right size=7 readOnly><img src=/img/date.png onclick=displayCalendar(document.forms[0].fecha_salida".$var2.",formarto,this) style=margin:0pt;padding:0pt alt=Calendario border=0 width=10%>";
$aux_b = "<input name=data[cnmp06_datos_permisos][fecha_reintegro".$var2."] value=".cambia_fecha($var_d)." id=fecha_reintegro".$var2." style=text-align:right size=7 readOnly><img src=/img/date.png onclick=displayCalendar(document.forms[0].fecha_reintegro".$var2.",formarto,this) style=margin:0pt;padding:0pt alt=Calendario border=0 width=10%>";

      echo'<script> formarto = "dd/mm/yyyy"; ';
           echo" document.getElementById('iconos_1_".$var2."').style.display = 'none'; ";
           echo" document.getElementById('iconos_2_".$var2."').style.display = 'block'; ";
           echo" document.getElementById('campo_c_".$var2."').innerHTML = '$aux_a'; ";
           echo" document.getElementById('campo_d_".$var2."').innerHTML = '$aux_b'; ";
           echo" document.getElementById('campo_e_".$var2."').innerHTML = '<input name=data[cnmp06_datos_permisos][observaciones".$var2."]        value=".$var_e."  id=observaciones".$var2."    style=text-align:left class=inputtext>'; ";
     echo'</script>';

 $this->set('permisos',$this->cnmd06_permisos->findAll(null, null, "denominacion ASC"));
 $this->concatena($this->cnmd06_permisos->generateList(null, "denominacion ASC", null, '{n}.cnmd06_permisos.cod_permiso', '{n}.cnmd06_permisos.denominacion'), "lista_deno");



$this->render("funcion");
}//fin function














function cancelar($var1=null, $var2=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";


                                        $cod_dep_expediente     =  $this->Session->read('cod_dep_expediente');
						  				$cod_tipo_nomina        =  $this->Session->read('cod_tipo_nomina_expediente');
							  			$cod_cargo              =  $this->Session->read('cod_cargo_expediente');
							  			$cod_ficha              =  $this->Session->read('cod_ficha_expediente');



         $var_datos = $this->cnmd06_datos_permisos->findAll("cod_presi = '".$cod_presi."' and cod_entidad = '".$cod_entidad."'and cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."' and cod_dep = '".$cod_dep_expediente."' and cod_tipo_nomina = '".$cod_tipo_nomina."' and cod_cargo = '".$cod_cargo."' and cod_ficha = '".$cod_ficha."' and cedula=".$var1." and consecutivo=".$var2);
         $var_a = $var_datos[0]['cnmd06_datos_permisos']['cod_permiso'];
         $var_c = $var_datos[0]['cnmd06_datos_permisos']['fecha_salida'];
         $var_d = $var_datos[0]['cnmd06_datos_permisos']['fecha_reintegro'];
         $var_e = $var_datos[0]['cnmd06_datos_permisos']['observaciones'];

      echo'<script>';
                   echo" document.getElementById('iconos_1_".$var2."').style.display = 'block'; ";
                   echo" document.getElementById('iconos_2_".$var2."').style.display = 'none'; ";
                   echo" document.getElementById('campo_c_".$var2."').innerHTML = '".cambia_fecha($var_c)."'; ";
                   echo" document.getElementById('campo_d_".$var2."').innerHTML = '".cambia_fecha($var_d)."'; ";
                   echo" document.getElementById('campo_e_".$var2."').innerHTML = '".$var_e."'; ";

     echo'</script>';


 $this->set('permisos',$this->cnmd06_permisos->findAll(null, null, "denominacion ASC"));
 $this->concatena($this->cnmd06_permisos->generateList(null, "denominacion ASC", null, '{n}.cnmd06_permisos.cod_permiso', '{n}.cnmd06_permisos.denominacion'), "lista_deno");



$this->render("funcion");
}//fin function









 }//fin lcass

 ?>