<?php

 class Infocnmp06DatosAmonestacionesController extends AppController {
 	var $name = 'info_cnmp06_datos_amonestaciones';
 	var $uses = array ('v_cnmd06_fichas_datos_personales','cnmd06_datos_amonestaciones', 'cnmd06_datos_personales', 'cnmd06_datos_amonestaciones', 'cnmd06_amonestaciones', 'cnmd06_fichas');
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





function index(){
  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

 $this->data=null;

 $this->set('amonestacion',$this->cnmd06_amonestaciones->findAll(null, null, "cod_amonestacion ASC"));
 $this->concatena($this->cnmd06_amonestaciones->generateList(null, "cod_amonestacion ASC", null, '{n}.cnmd06_amonestaciones.cod_amonestacion', '{n}.cnmd06_amonestaciones.denominacion'), "lista_deno");


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






function selecion($var=null){

$this->layout = "ajax";

$datos =  $this->cnmd06_amonestaciones->findAll("cod_amonestacion=".$var);

   echo'<script>';
               echo" document.getElementById('guardar').disabled = false; ";
               echo" document.getElementById('nombre_apellido').readOnly = false; ";
               echo" document.getElementById('cargo_ocupado').readOnly = false; ";
               echo" document.getElementById('concepto').readOnly = false; ";

                echo" document.getElementById('nombre_apellido').value = ''; ";
                echo" document.getElementById('cargo_ocupado').value = ''; ";
                echo" document.getElementById('concepto').value = ''; ";

      echo'</script>';

      echo'<script>';
               echo" document.getElementById('cod_amonestacion').value = '".$this->AddCeroR($datos[0]["cnmd06_amonestaciones"]["cod_amonestacion"])."'; ";
               echo" document.getElementById('entidad_federal').value = '".$datos[0]["cnmd06_amonestaciones"]["denominacion"]."'; ";
      echo'</script>';


}//fin function




function index2($var=null){
  $this->layout = "ajax";
  $accion = "";


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

if($var1!=""){

       echo"<script>document.getElementById('guardar').disabled = false; </script>";
}else{
	   $this->set('errorMessage', "NO ESTA REGISTRADO EN PERSONAL"); $disabled = "true";
	   echo"<script>  document.getElementById('guardar').disabled = true; </script>";


	 }//fin else


                                        $cod_dep_expediente     =  $this->Session->read('cod_dep_expediente');
						  				$cod_tipo_nomina        =  $this->Session->read('cod_tipo_nomina_expediente');
							  			$cod_cargo              =  $this->Session->read('cod_cargo_expediente');
							  			$cod_ficha              =  $this->Session->read('cod_ficha_expediente');



 $this->set('amonestacion',$this->cnmd06_amonestaciones->findAll(null, null, "cod_amonestacion ASC"));
 $this->set('accion',$this->cnmd06_datos_amonestaciones->findAll("cedula = '".$var."'", null, "cod_amonestacion ASC"));


}//fin funtion






function guardar($var1=null, $var_n=null, $var3=null, $var4=null){
  $this->layout = "ajax";

$ide = $_SESSION['infogobierno']['cedula_identidad'];
$datos_p=$this->v_cnmd06_fichas_datos_personales->findAll('cedula_identidad='.$ide,    "cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_tipo_nomina,cod_cargo,cod_ficha");
//pr($datos_p);
foreach($datos_p as $da){
	$cod_presi  	= $da['v_cnmd06_fichas_datos_personales']['cod_presi'];
	$cod_entidad 	= $da['v_cnmd06_fichas_datos_personales']['cod_entidad'];
	$cod_tipo_inst	= $da['v_cnmd06_fichas_datos_personales']['cod_tipo_inst'];
	$cod_inst		= $da['v_cnmd06_fichas_datos_personales']['cod_inst'];
	$cod_dep 		= $da['v_cnmd06_fichas_datos_personales']['cod_dep'];
	$cod_tipo_nomina= $da['v_cnmd06_fichas_datos_personales']['cod_tipo_nomina'];
	$cod_cargo		= $da['v_cnmd06_fichas_datos_personales']['cod_cargo'];
	$cod_ficha		= $da['v_cnmd06_fichas_datos_personales']['cod_ficha'];
}

if(!empty($this->data['cnmp06_datos_amonestaciones']['cedula']) &&
   !empty($this->data['cnmp06_datos_amonestaciones']['cod_amonestacion']) &&
   !empty($this->data['cnmp06_datos_amonestaciones']['fecha_amonestacion']) &&
   !empty($this->data['cnmp06_datos_amonestaciones']['nombre_apellido']) &&
   !empty($this->data['cnmp06_datos_amonestaciones']['cargo_ocupado'])  &&
   !empty($this->data['cnmp06_datos_amonestaciones']['concepto'])
){

   $cedula              =  $this->data['cnmp06_datos_amonestaciones']['cedula'];
   $cod_amonestacion    =  $this->data['cnmp06_datos_amonestaciones']['cod_amonestacion'];
   $fecha_amonestacion  =  $this->data['cnmp06_datos_amonestaciones']['fecha_amonestacion'];
   $nombre_apellido     =  $this->data['cnmp06_datos_amonestaciones']['nombre_apellido'];
   $cargo_ocupado       =  $this->data['cnmp06_datos_amonestaciones']['cargo_ocupado'];
   $concepto            =  $this->data['cnmp06_datos_amonestaciones']['concepto'];




	       if($var_n==null){
									$sql="BEGIN; INSERT INTO cnmd06_datos_amonestaciones (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula, cod_amonestacion, fecha_amonestacion, nombre_amonestador, cargo_amonestador, observaciones)";
									$sql.="VALUES ('".$cod_presi."','".$cod_entidad."','".$cod_tipo_inst."','".$cod_inst."','".$cod_dep."','".$cod_tipo_nomina."','".$cod_cargo."','".$cod_ficha."','".$cedula."','".$cod_amonestacion."', '".$fecha_amonestacion."', '".$nombre_apellido."', '".$cargo_ocupado."', '".$concepto."'); ";
									$sw2 = $this->cnmd06_datos_amonestaciones->execute($sql);

												if($sw2>1){
									                $this->cnmd06_datos_amonestaciones->execute("COMMIT;");
											        $this->set('Message_existe', 'Los datos fueron guardados correctamente');
												}else{
													$this->cnmd06_datos_amonestaciones->execute("ROLLBACK;");
													$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
												}//fin else

												$this->index();

				     }else{
					                    $cod_dep_expediente     =  $this->Session->read('cod_dep_expediente');
						  				$cod_tipo_nomina        =  $this->Session->read('cod_tipo_nomina_expediente');
							  			$cod_cargo              =  $this->Session->read('cod_cargo_expediente');
							  			$cod_ficha              =  $this->Session->read('cod_ficha_expediente');

					                $sql="BEGIN;  UPDATE cnmd06_datos_amonestaciones SET  fecha_amonestacion='".$fecha_amonestacion."', cargo_amonestador='".$cargo_ocupado."', nombre_amonestador='".$nombre_apellido."' , observaciones='".$concepto."'  WHERE  cod_presi = '".$cod_presi."' and cod_entidad = '".$cod_entidad."'and cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."' and cod_dep = '".$cod_dep_expediente."' and cod_tipo_nomina = '".$cod_tipo_nomina."' and cod_cargo = '".$cod_cargo."' and cod_ficha = '".$cod_ficha."' and cedula='".$var1."'  and  cod_amonestacion='".$var_n."'  and consecutivo='".$var3."'" ;
									$sw2 = $this->cnmd06_datos_amonestaciones->execute($sql);
												if($sw2>1){
									                $this->cnmd06_datos_amonestaciones->execute("COMMIT;");
											        $this->set('Message_existe', 'Los datos fueron guardados correctamente');
												}else{
													$this->cnmd06_datos_amonestaciones->execute("ROLLBACK;");
													$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
												}//fin else

												$this->consulta();
				                                $this->render("consulta");

				      }//fin else





}else{

		  $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');

		  $this->index();


}//fin else


$this->data=null;

$this->concatena($this->cnmd06_amonestaciones->generateList(null, "cod_amonestacion ASC", null, '{n}.cnmd06_amonestaciones.cod_amonestacion', '{n}.cnmd06_amonestaciones.denominacion'), "lista_deno");


}//fin funtion















function editar($var1=null, $var2=null, $var3=null){

  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;




     echo'<script>';
               echo" document.getElementById('guardar').disabled = false; ";
               echo" document.getElementById('modificar').disabled = true; ";
               echo" document.getElementById('s_apellido').readOnly = false; ";
               echo" document.getElementById('nombre_apellido').readOnly = false; ";
               echo" document.getElementById('cargo_ocupado').readOnly = false; ";
               echo" document.getElementById('concepto').readOnly = false; ";
      echo'</script>';



  $this->render("funcion");

}//fin function













function ver($var1=null, $var2=null, $var3=null){

  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');

  $datos = $this->cnmd06_datos_amonestaciones->findAll("cedula=".$var1." and cod_amonestacion=".$var2." and consecutivo=".$var3, null, "consecutivo, cod_amonestacion ASC");

     echo'<script>';
               echo" document.getElementById('nombre_apellido').readOnly = true; ";
               echo" document.getElementById('cargo_ocupado').readOnly = true; ";
               echo" document.getElementById('concepto').readOnly = true; ";

                echo" document.getElementById('nombre_apellido').value = '".$datos[0]["cnmd06_datos_amonestaciones"]["nombre_amonestador"]."'; ";
                echo" document.getElementById('cargo_ocupado').value = '".$datos[0]["cnmd06_datos_amonestaciones"]["cargo_amonestador"]."'; ";
                echo" document.getElementById('concepto').value = '".$datos[0]["cnmd06_datos_amonestaciones"]["observaciones"]."'; ";

      echo'</script>';



  $this->render("funcion");

}//fin function










function eliminar($var1=null, $var2=null, $var3=null){
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


$sql="BEGIN;  DELETE FROM cnmd06_datos_amonestaciones  WHERE cod_presi = '".$cod_presi."' and cod_entidad = '".$cod_entidad."'and cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."' and cod_dep = '".$cod_dep_expediente."' and cod_tipo_nomina = '".$cod_tipo_nomina."' and cod_cargo = '".$cod_cargo."' and cod_ficha = '".$cod_ficha."' and cedula='".$var1."' and  cod_amonestacion='".$var2."' and consecutivo='".$var3."'  ";
$sw2 = $this->cnmd06_datos_amonestaciones->execute($sql);

			if($sw2>1){
                $this->cnmd06_datos_amonestaciones->execute("COMMIT;");
		        $this->set('Message_existe', 'LOS DATOS FUERON ELIMINDOS CORRECTAMENTE');
			}else{
				$this->cnmd06_datos_amonestaciones->execute("ROLLBACK;");
				$this->set('errorMessage', 'LOS DATOS NO FUERON ELIMINDOS - POR FAVOR INTENTE DE NUEVO');
			}//fin else




 $this->set('totalPages_Recordset1',$this->cnmd06_datos_amonestaciones->findCount("cod_presi = '".$cod_presi."' and cod_entidad = '".$cod_entidad."'and cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."' and cod_dep = '".$cod_dep_expediente."' and cod_tipo_nomina = '".$cod_tipo_nomina."' and cod_cargo = '".$cod_cargo."' and cod_ficha = '".$cod_ficha."' and cedula=".$var1));
 $this->set('datos',$this->cnmd06_datos_amonestaciones->findAll("cod_presi = '".$cod_presi."' and cod_entidad = '".$cod_entidad."'and cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."' and cod_dep = '".$cod_dep_expediente."' and cod_tipo_nomina = '".$cod_tipo_nomina."' and cod_cargo = '".$cod_cargo."' and cod_ficha = '".$cod_ficha."' and cedula=".$var1, null, "consecutivo, cod_amonestacion ASC"));


$this->set("pag_num", 0);

$cont = $this->cnmd06_datos_amonestaciones->findCount("cod_presi = '".$cod_presi."' and cod_entidad = '".$cod_entidad."'and cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."' and cod_dep = '".$cod_dep_expediente."' and cod_tipo_nomina = '".$cod_tipo_nomina."' and cod_cargo = '".$cod_cargo."' and cod_ficha = '".$cod_ficha."' and cedula=".$var1);




$cnmd06_datos_personales_aux = $this->cnmd06_datos_personales->findAll("cedula_identidad = '".$var1."'");

   $var1 = "";
   $var2 = "";
   $var3 = "";
   $var4 = "";

foreach($cnmd06_datos_personales_aux as $ve){
	$var1 = $ve["cnmd06_datos_personales"]["primer_apellido"];
	$var2 = $ve["cnmd06_datos_personales"]["segundo_apellido"];
	$var3 = $ve["cnmd06_datos_personales"]["primer_nombre"];
	$var4 = $ve["cnmd06_datos_personales"]["segundo_nombre"];
}//fin foreach


 $this->set('amonestacion',$this->cnmd06_amonestaciones->findAll(null, null, "cod_amonestacion ASC"));


       $this->set('cedula', $var1);
       $this->set('p_apellido', $var1);
       $this->set('s_apellido', $var2);
       $this->set('p_nombre', $var3);
       $this->set('s_nombre', $var4);


if($cont==0){

                  $this->consulta();
				  $this->render("consulta");

}//fin




}//fin funtion








function funcion(){$this->layout = "ajax";}




function consulta_index(){$this->layout = "ajax";}






function consulta($var=null, $pag_num=null){


	  $this->layout = "ajax";
	  $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";


if($this->Session->read('cedula_pestana_expediente')==""){
     	$id=0;
}else{
	    $id=$this->Session->read('cedula_pestana_expediente');
}//fin else
      $Tfilas=$this->cnmd06_datos_personales->findCount("cedula_identidad=".$id);
   if($Tfilas!=0){
       $cond2="cedula=".$id;
       $var = $id;
   }else{
   	    $cond2="";
   	    $var = 0;
   }//fin else


                                        $cod_dep_expediente     =  $this->Session->read('cod_dep_expediente');
						  				$cod_tipo_nomina        =  $this->Session->read('cod_tipo_nomina_expediente');
							  			$cod_cargo              =  $this->Session->read('cod_cargo_expediente');
							  			$cod_ficha              =  $this->Session->read('cod_ficha_expediente');

 $this->set('totalPages_Recordset1',$this->cnmd06_datos_amonestaciones->findCount("cod_presi = '".$cod_presi."' and cod_entidad = '".$cod_entidad."'and cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."' and cod_dep = '".$cod_dep_expediente."' and cod_tipo_nomina = '".$cod_tipo_nomina."' and cod_cargo = '".$cod_cargo."' and cod_ficha = '".$cod_ficha."' and cedula=".$var));
 $this->set('datos',$this->cnmd06_datos_amonestaciones->findAll("cod_presi = '".$cod_presi."' and cod_entidad = '".$cod_entidad."'and cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."' and cod_dep = '".$cod_dep_expediente."' and cod_tipo_nomina = '".$cod_tipo_nomina."' and cod_cargo = '".$cod_cargo."' and cod_ficha = '".$cod_ficha."' and cedula=".$var, null, "consecutivo, cod_amonestacion ASC"));


if($pag_num==null){$this->set("pag_num", 0);}else{$this->set("pag_num", $pag_num);}



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


 $this->set('amonestacion',$this->cnmd06_amonestaciones->findAll(null, null, "cod_amonestacion ASC"));


       $this->set('cedula', $var);
       $this->set('p_apellido', $var1);
       $this->set('s_apellido', $var2);
       $this->set('p_nombre', $var3);
       $this->set('s_nombre', $var4);


       $cont = $this->cnmd06_datos_amonestaciones->findCount("cod_presi = '".$cod_presi."' and cod_entidad = '".$cod_entidad."'and cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."' and cod_dep = '".$cod_dep_expediente."' and cod_tipo_nomina = '".$cod_tipo_nomina."' and cod_cargo = '".$cod_cargo."' and cod_ficha = '".$cod_ficha."' and cedula=".$var);

       if($cont==0){


       	               $this->set('errorMessage', "NO ESTA REGISTRADA NINGUNA AMONESTACIÓN");
					   $this->index();


       }else{

				if($var1!=""){


				}else{
					   $this->set('errorMessage', "NO ESTA REGISTRADO EN PERSONAL");
					   $this->index();


					 }//fin else

       }//fin else




}//fin function











function cancelar($var1=null, $var2=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";


$this->render("funcion");
}//fin function









 }//fin lcass

 ?>