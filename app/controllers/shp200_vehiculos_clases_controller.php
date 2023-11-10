<?php


 class Shp200VehiculosClasesController extends AppController{
	var $uses = array('shd200_vehiculos_clases', 'ccfd04_cierre_mes');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
 	var $name = "shp200_vehiculos_clases";

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
}//fin before filter






function index($var=null, $var_cont=null){
	$this->layout = "ajax";

	  $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";

     $var_cont = $this->shd200_vehiculos_clases->findAll(null, null, 'codigo_clase ASC');
     $datos    = $this->shd200_vehiculos_clases->findAll(null, null, 'codigo_clase ASC');
     $var_cont2 = 0;
     foreach($var_cont as $ve1){$var_cont2 = $ve1['shd200_vehiculos_clases']['codigo_clase'];}
     $var_cont2++;

     $this->set('codigo', $var_cont2);
     $this->set('datos', $datos);

}//fin index






function funcion(){$this->layout = "ajax";}






function guardar($var_n=null){

	$this->layout = "ajax";

      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";



if($var_n==null){ $codigo_clase   =   $this->data['shp200_vehiculos_clases']['codigo'.$var_n];}else{ $codigo_clase =  $var_n;}


if(!empty($this->data['shp200_vehiculos_clases']['denominacion'.$var_n])){
   $denominacion            =       $this->data['shp200_vehiculos_clases']['denominacion'.$var_n];
   $var_cont = $this->shd200_vehiculos_clases->findCount("codigo_clase=".$codigo_clase);

			         if($var_cont==0){
                        $sw = $this->shd200_vehiculos_clases->execute("BEGIN; INSERT INTO shd200_vehiculos_clases (codigo_clase, denominacion) VALUES ('".$codigo_clase."', '".$denominacion."'); ");
			         }else{
                        $sw = $this->shd200_vehiculos_clases->execute("BEGIN; UPDATE shd200_vehiculos_clases SET denominacion='".$denominacion."' WHERE codigo_clase='".$codigo_clase."' ");
			         }//fin function
}else{$sw="a";}

          if($sw>1){

        $this->shd200_vehiculos_clases->execute("COMMIT;");    $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');

     }elseif($sw=="a"){

     	$this->shd200_vehiculos_clases->execute("ROLLBACK;");  $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - FALTAN DATOS');

     }else{

     	$this->shd200_vehiculos_clases->execute("ROLLBACK;");  $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
     }//fin else




if($var_n==null){
    $this->index();
	$this->render("index");
}else{

    $var_datos = $this->shd200_vehiculos_clases->findAll("codigo_clase=".$codigo_clase);
   echo'<script>';
                   echo" document.getElementById('iconos_2_".$var_n."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_1_".$var_n."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var_n."').innerHTML ='".$var_datos[0]['shd200_vehiculos_clases']['denominacion']."' ;";
     echo'</script>';
   $this->render("funcion");
}//fin else

}//fin function










function editar($var1=null, $var2=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
         $var_datos = $this->shd200_vehiculos_clases->findAll("codigo_clase=".$var1);
         $var2 = $var_datos[0]['shd200_vehiculos_clases']['denominacion'];
      echo'<script>';
                   echo" document.getElementById('iconos_1_".$var1."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_2_".$var1."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var1."').innerHTML ='<input type=text name=data[shp200_vehiculos_clases][denominacion".$var1."]    id=denominacion_".$var1."  value=\"$var2\"   class=inputtext  />' ;";
     echo'</script>';

$this->render("funcion");
}//fin function





function eliminar($var1=null, $var2=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
      $sw = $this->shd200_vehiculos_clases->execute("DELETE FROM shd200_vehiculos_clases  WHERE codigo_clase='".$var1."' ");
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
      $var_datos = $this->shd200_vehiculos_clases->findAll("codigo_clase=".$var1);
      $var2 = $var_datos[0]['shd200_vehiculos_clases']['denominacion'];
      echo'<script>';
                   echo" document.getElementById('iconos_2_".$var1."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_1_".$var1."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var1."').innerHTML ='".$var2."' ;";
     echo'</script>';
$this->render("funcion");
}//fin function






}//fin class
?>
