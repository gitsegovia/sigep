<?php


 class cfpp17InversionCoordinadaOrganismoController extends AppController{
	var $uses = array('cfpd17_inversion_coordinada_organismo', 'ccfd04_cierre_mes');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
 	var $name = "cfpp17_inversion_coordinada_organismo";

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

     $var_cont = $this->cfpd17_inversion_coordinada_organismo->findAll($this->condicion(), null, 'cod_organismo ASC');
     $datos    = $this->cfpd17_inversion_coordinada_organismo->findAll($this->condicion(), null, 'cod_organismo ASC');
     $var_cont2 = 0;
     foreach($var_cont as $ve1){$var_cont2 = $ve1['cfpd17_inversion_coordinada_organismo']['cod_organismo'];}
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



if($var_n==null){ $cod_organismo   =   $this->data['cfpp17_inversion_coordinada_organismo']['codigo'.$var_n];}else{ $cod_organismo =  $var_n;}


if(!empty($this->data['cfpp17_inversion_coordinada_organismo']['denominacion'.$var_n])){
   $denominacion            =       $this->data['cfpp17_inversion_coordinada_organismo']['denominacion'.$var_n];

   $var_cont = $this->cfpd17_inversion_coordinada_organismo->findCount($this->condicion()." and cod_organismo=".$cod_organismo);

			         if($var_cont==0){
                        $sw = $this->cfpd17_inversion_coordinada_organismo->execute("BEGIN; INSERT INTO cfpd17_inversion_coordinada_organismo (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_organismo, denominacion) VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$cod_organismo."', '".$denominacion."'); ");
			         }else{
                        $sw = $this->cfpd17_inversion_coordinada_organismo->execute("BEGIN; UPDATE cfpd17_inversion_coordinada_organismo SET denominacion='".$denominacion."' WHERE ".$condicion." and cod_organismo='".$cod_organismo."' ");
			         }//fin function
}else{$sw="a";}




          if($sw>1){

        $this->cfpd17_inversion_coordinada_organismo->execute("COMMIT;");    $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');

     }elseif($sw=="a"){

     	$this->cfpd17_inversion_coordinada_organismo->execute("ROLLBACK;");  $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - FALTAN DATOS');

     }else{

     	$this->cfpd17_inversion_coordinada_organismo->execute("ROLLBACK;");  $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
     }//fin else




if($var_n==null){
    $this->index();
	$this->render("index");
}else{

    $var_datos = $this->cfpd17_inversion_coordinada_organismo->findAll($this->condicion()." and cod_organismo=".$cod_organismo);
   echo'<script>';
                   echo" document.getElementById('iconos_2_".$var_n."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_1_".$var_n."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var_n."').innerHTML ='".$var_datos[0]['cfpd17_inversion_coordinada_organismo']['denominacion']."' ;";
     echo'</script>';
   $this->render("funcion");
}//fin else

}//fin function










function editar($var1=null, $var2=null, $var3=null, $var4=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
         $var_datos = $this->cfpd17_inversion_coordinada_organismo->findAll($this->condicion()." and cod_organismo=".$var1);
         $var2 = $var_datos[0]['cfpd17_inversion_coordinada_organismo']['denominacion'];


      echo'<script>';
                   echo" document.getElementById('iconos_1_".$var1."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_2_".$var1."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var1."').innerHTML ='<input type=text name=data[cfpp17_inversion_coordinada_organismo][denominacion".$var1."]    id=denominacion_".$var1."  value=\"$var2\"   class=inputtext  maxlength=100/>' ;";
      echo'</script>';

$this->render("funcion");
}//fin function





function eliminar($var1=null, $var2=null, $var3=null, $var4=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
      $sw = $this->cfpd17_inversion_coordinada_organismo->execute("DELETE FROM cfpd17_inversion_coordinada_organismo  WHERE ".$condicion." and  cod_organismo='".$var1."' ");
     $var_cont = $this->cfpd17_inversion_coordinada_organismo->findAll($this->condicion(), null, 'cod_organismo ASC');
     $var_cont2 = 0;
     foreach($var_cont as $ve1){$var_cont2 = $ve1['cfpd17_inversion_coordinada_organismo']['cod_organismo'];}
     $var_cont2++;
      echo'<script>';
                   echo" document.getElementById('codigo').value = '".mascara($var_cont2,2)."'; ";
      echo'</script>';
$this->render("funcion");
}//fin function



function cancelar($var1=null, $var2=null, $var3=null, $var4=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
      $var_datos = $this->cfpd17_inversion_coordinada_organismo->findAll($this->condicion()." and cod_organismo=".$var1);
      $var2 = $var_datos[0]['cfpd17_inversion_coordinada_organismo']['denominacion'];
      echo'<script>';
                   echo" document.getElementById('iconos_2_".$var1."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_1_".$var1."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var1."').innerHTML ='".$var2."' ;";
     echo'</script>';
$this->render("funcion");
}//fin function






}//fin class
?>
