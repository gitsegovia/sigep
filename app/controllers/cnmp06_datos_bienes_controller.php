<?php

 class cnmp06DatosBienesController extends AppController {
 	var $name = 'cnmp06_datos_bienes';
 	var $uses = array ('cnmd06_datos_bienes', 'cnmd06_bienes', "cnmd06_datos_personales");
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

  $listabienes=  $this->cnmd06_bienes->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_bienes.cod_bien', '{n}.cnmd06_bienes.denominacion');
  $this->concatena($listabienes, 'lista');

if($this->Session->read('cedula_pestana_expediente')==""){
         	$id=0;
    }else{
    	    $id=$this->Session->read('cedula_pestana_expediente');
    	 }//fin else

    	$this->set('cedula', "");
    	$Tfilas=$this->cnmd06_datos_personales->findCount("cedula_identidad=".$id);
       if($Tfilas!=0){
			$a = $this->cnmd06_datos_personales->findAll("cedula_identidad=".$id);
		    $pa=$a[0]['cnmd06_datos_personales']['primer_apellido'];
		    $sa=$a[0]['cnmd06_datos_personales']['segundo_apellido'];
		    $pn=$a[0]['cnmd06_datos_personales']['primer_nombre'];
		    $sn=$a[0]['cnmd06_datos_personales']['segundo_nombre'];
		    $this->set('ci',$id);
		    $this->set('pa',$pa);
		    $this->set('sa',$sa);
		    $this->set('pn',$pn);
		    $this->set('sn',$sn);
       }else{
       	    $this->set('ci',"");
		    $this->set('pa',"");
		    $this->set('sa',"");
		    $this->set('pn',"");
		    $this->set('sn',"");
      }//fin else


	$dato   =  $this->cnmd06_bienes->findAll(null, null, null);
	$accion =  $this->cnmd06_datos_bienes->findAll('cedula_identidad='.$id, null, null);

	$this->set('dato',  $dato);
	$this->set('accion',$accion);

}//fin funtion







function seleccion_cod_bien($id=null){
  $this->layout = "ajax";

	if($id==null){
		 echo'<script>';
	                   echo" document.getElementById('cod_bien').value = ''; ";
	                   echo" document.getElementById('deno_bien').value = ''; ";
	     echo'</script>';
	}else{
		$datos = $this->cnmd06_bienes->findAll("cod_bien = ".$id);
         echo'<script>';
	                   echo" document.getElementById('cod_bien').value = '".$this->AddCeroR($datos[0]["cnmd06_bienes"]["cod_bien"])."'; ";
	                   echo" document.getElementById('deno_bien').value = '".$datos[0]["cnmd06_bienes"]["denominacion"]."'; ";
	     echo'</script>';
	}
  $this->funcion();
  $this->render("funcion");

}//fin function







function guardar($var1=null, $var_n=null){
  $this->layout = "ajax";


$cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;



if(!empty($this->data['cnmp06_datos_bienes']['cedula'.$var_n]) &&
   !empty($this->data['cnmp06_datos_bienes']['cod_bien'.$var_n]) &&
   !empty($this->data['cnmp06_datos_bienes']['ano_compra'.$var_n]) &&
   !empty($this->data['cnmp06_datos_bienes']['costo'.$var_n]) &&
   !empty($this->data['cnmp06_datos_bienes']['cancelado'.$var_n])
){
   $cedula            =  $this->data['cnmp06_datos_bienes']['cedula'.$var_n];
   $cod_bien          =  $this->data['cnmp06_datos_bienes']['cod_bien'.$var_n];
   $ano_compra        =  $this->data['cnmp06_datos_bienes']['ano_compra'.$var_n];
   $costo             =  $this->Formato1($this->data['cnmp06_datos_bienes']['costo'.$var_n]);
   $cancelado         =  $this->data['cnmp06_datos_bienes']['cancelado'.$var_n];
   $cod_institucion   =  $cod_inst;






					if($var_n==null){
										$sql="BEGIN; INSERT INTO cnmd06_datos_bienes (cedula_identidad, cod_bien, ano_compra, costo, cancelado)";
										$sql.="VALUES ('".$cedula."', '".$cod_bien."', '".$ano_compra."', '".$costo."', '".$cancelado."'); ";
										$sw2 = $this->cnmd06_datos_bienes->execute($sql);

													if($sw2>1){
										                $this->cnmd06_datos_bienes->execute("COMMIT;");
												        $this->set('Message_existe', 'Los datos fueron guardados correctamente');
													}else{
														$this->cnmd06_datos_bienes->execute("ROLLBACK;");
														$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
													}//fin else


										   	      $dato   =  $this->cnmd06_bienes->findAll(null, null, null);
												  $accion =  $this->cnmd06_datos_bienes->findAll('cedula_identidad='.$cedula, null, null);

										  $this->set('accion', $accion);
										  $this->set('cedula', $var1);
										  $this->set('dato', $dato);
					}else{
						                $sql="BEGIN;  UPDATE cnmd06_datos_bienes SET ano_compra='".$ano_compra."', costo='".$costo."', cancelado='".$cancelado."'  WHERE  cedula_identidad='".$var1."'  and consecutivo='".$var_n."'" ;
										$sw2 = $this->cnmd06_datos_bienes->execute($sql);
													if($sw2>1){
										                $this->cnmd06_datos_bienes->execute("COMMIT;");
												        $this->set('Message_existe', 'Los datos fueron guardados correctamente');
													}else{
														$this->cnmd06_datos_bienes->execute("ROLLBACK;");
														$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
													}//fin else
                                         $var_datos = $this->cnmd06_datos_bienes->findAll("cedula_identidad=".$var1." and consecutivo=".$var_n);
								         $var_a = $var_datos[0]['cnmd06_datos_bienes']['ano_compra'];
								         $var_b = $var_datos[0]['cnmd06_datos_bienes']['costo'];
								         $var_c = $var_datos[0]['cnmd06_datos_bienes']['cancelado'];
								         $var_d = $var_datos[0]['cnmd06_datos_bienes']['consecutivo'];

								      echo'<script>';
								                   echo" document.getElementById('iconos_1_".$var_n."').style.display = 'block'; ";
								                   echo" document.getElementById('iconos_2_".$var_n."').style.display = 'none'; ";
								                   echo" document.getElementById('campo_a_".$var_n."').innerHTML = '".$var_a."'; ";
								                   echo" document.getElementById('campo_b_".$var_n."').innerHTML = '".$this->Formato2($var_b)."'; ";
								                   echo" document.getElementById('cancelado".$var_n."_1').disabled = true; ";
												   echo" document.getElementById('cancelado".$var_n."_2').disabled = true; ";
								     echo'</script>';
								$this->render("funcion");
					}//fin else
}else{


							if($var_n!=null){
		                                 $var_datos = $this->cnmd06_datos_bienes->findAll("cedula_identidad=".$var1." and consecutivo=".$var_n);
								         $var_a = $var_datos[0]['cnmd06_datos_bienes']['ano_compra'];
								         $var_b = $var_datos[0]['cnmd06_datos_bienes']['costo'];
								         $var_c = $var_datos[0]['cnmd06_datos_bienes']['cancelado'];
								         $var_d = $var_datos[0]['cnmd06_datos_bienes']['consecutivo'];

								      echo'<script>';
								                   echo" document.getElementById('iconos_1_".$var_n."').style.display = 'block'; ";
								                   echo" document.getElementById('iconos_2_".$var_n."').style.display = 'none'; ";
								                   echo" document.getElementById('campo_a_".$var_n."').innerHTML = '".$var_a."'; ";
								                   echo" document.getElementById('campo_b_".$var_n."').innerHTML = '".$this->Formato2($var_b)."'; ";
								                   echo" document.getElementById('cancelado".$var_n."_1').disabled = true; ";
												   echo" document.getElementById('cancelado".$var_n."_2').disabled = true; ";
								     echo'</script>';
							}//fin if
		  $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
		  $this->render("funcion");



}//fin else




                                     echo'<script>';
								                   echo" document.getElementById('cod_bien').value = ''; ";
								                   echo" document.getElementById('deno_bien').value = ''; ";
								                   echo" document.getElementById('ano_compra').value = ''; ";
								                   echo" document.getElementById('costo').value = ''; ";
								                   echo" document.getElementById('cancelado_1').checked = true; ";
								                   echo" document.getElementById('cancelado_2').checked = false; ";
                                                   echo" document.getElementById('select_cod_bien').options[0].selected = true; ";
								     echo'</script>';




}//fin function















function infomacion_faltante($var1=null, $var2=null){

$this->layout = "ajax";

$var3 = "";

		switch($var1){
                case "bienes":{  $this->set('userTable', $this->requestAction('/cnmp06_bienes/', array('return')));  }break;
		 }//fin

$this->set('opcion',     $var1);
$this->set('capa',       $var2);
$this->set('controlador',$var3);

}//fin function






function select_cambio($var1=null, $var2=null, $var3=null){

$this->layout = "ajax";

	switch($var1){
                case "bienes":{
                	$listabienes=  $this->cnmd06_bienes->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_bienes.cod_bien', '{n}.cnmd06_bienes.denominacion');
	                $this->concatena($listabienes, 'lista');
	                $this->set("name", "seleccion_cod_bien");
                }break;

		 }//fin

}//fin function





function eliminar($var1=null, $var2=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";


      $var_datos = $this->cnmd06_datos_bienes->execute("delete from cnmd06_datos_bienes WHERE cedula_identidad=".$var1." and consecutivo=".$var2);




$this->render("funcion");


}//fin function





function editar($var1=null, $var2=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
         $var_datos = $this->cnmd06_datos_bienes->findAll("cedula_identidad=".$var1." and consecutivo=".$var2);
         $var_a = $var_datos[0]['cnmd06_datos_bienes']['ano_compra'];
         $var_b = $this->Formato2($var_datos[0]['cnmd06_datos_bienes']['costo']);
         $var_c = $var_datos[0]['cnmd06_datos_bienes']['cancelado'];
         $var_d = $var_datos[0]['cnmd06_datos_bienes']['consecutivo'];


      echo'<script>';
           echo" document.getElementById('iconos_1_".$var2."').style.display = 'none'; ";
           echo" document.getElementById('iconos_2_".$var2."').style.display = 'block'; ";
           echo" document.getElementById('campo_a_".$var2."').innerHTML = '<input name=data[cnmp06_datos_bienes][ano_compra".$var2."]      value=\'".$var_a."\'  id=ano_compra".$var2."     style=text-align:left class=inputtext                                       >'; ";
           echo" document.getElementById('campo_b_".$var2."').innerHTML = '<input name=data[cnmp06_datos_bienes][costo".$var2."]           value=\'".$var_b."\'  id=costo".$var2."          style=text-align:left class=inputtext   onblur=moneda(\'costo".$var2."\')     >'; ";
           echo" document.getElementById('cancelado".$var2."_1').disabled = false; ";
		   echo" document.getElementById('cancelado".$var2."_2').disabled = false; ";

     echo'</script>';

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
         $var_datos = $this->cnmd06_datos_bienes->findAll("cedula_identidad=".$var1." and consecutivo=".$var2);
         $var_a = $var_datos[0]['cnmd06_datos_bienes']['ano_compra'];
         $var_b = $var_datos[0]['cnmd06_datos_bienes']['costo'];
         $var_c = $var_datos[0]['cnmd06_datos_bienes']['cancelado'];
         $var_d = $var_datos[0]['cnmd06_datos_bienes']['consecutivo'];

      echo'<script>';
                   echo" document.getElementById('iconos_1_".$var2."').style.display = 'block'; ";
                   echo" document.getElementById('iconos_2_".$var2."').style.display = 'none'; ";
                   echo" document.getElementById('campo_a_".$var2."').innerHTML = '".$var_a."'; ";
                   echo" document.getElementById('campo_b_".$var2."').innerHTML = '".$this->Formato2($var_b)."'; ";
                   echo" document.getElementById('cancelado".$var2."_1').disabled = true; ";
				   echo" document.getElementById('cancelado".$var2."_2').disabled = true; ";
     echo'</script>';

$this->render("funcion");
}//fin function







function funcion($id=null){
  $this->layout = "ajax";
}//fin function






 }//fin lcass

 ?>