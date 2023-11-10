<?php


 class Shp300RecargosController extends AppController{
	var $uses = array('shd300_recargos', 'ccfd04_cierre_mes');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
 	var $name = "shp300_recargos";

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

     $var_cont = $this->shd300_recargos->findAll($this->condicion(), null, 'cod_recargo ASC');
     $datos    = $this->shd300_recargos->findAll($this->condicion(), null, 'cod_recargo ASC');
     $var_cont2 = 0;
     foreach($var_cont as $ve1){$var_cont2 = $ve1['shd300_recargos']['cod_recargo'];}
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



if($var_n==null){ $cod_recargo   =   $this->data['shp300_recargos']['codigo'.$var_n];}else{ $cod_recargo =  $var_n;}


if(!empty($this->data['shp300_recargos']['denominacion'.$var_n]) && !empty($this->data['shp300_recargos']['porcentaje'.$var_n])){
   $denominacion            =       $this->data['shp300_recargos']['denominacion'.$var_n];
   $porcentaje              =       $this->Formato1($this->data['shp300_recargos']['porcentaje'.$var_n]);

   $var_cont = $this->shd300_recargos->findCount($this->condicion()." and cod_recargo=".$cod_recargo);

			         if($var_cont==0){
                        $sw = $this->shd300_recargos->execute("BEGIN; INSERT INTO shd300_recargos (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_recargo, denominacion, porcentaje) VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$cod_recargo."', '".$denominacion."', '".$porcentaje."'); ");
			         }else{
                        $sw = $this->shd300_recargos->execute("BEGIN; UPDATE shd300_recargos SET denominacion='".$denominacion."', porcentaje='".$porcentaje."' WHERE ".$condicion." and cod_recargo='".$cod_recargo."' ");
			         }//fin function
}else{$sw="a";}




          if($sw>1){

        $this->shd300_recargos->execute("COMMIT;");    $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');

     }elseif($sw=="a"){

     	$this->shd300_recargos->execute("ROLLBACK;");  $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - FALTAN DATOS');

     }else{

     	$this->shd300_recargos->execute("ROLLBACK;");  $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
     }//fin else




if($var_n==null){
    $this->index();
	$this->render("index");
}else{

    $var_datos = $this->shd300_recargos->findAll($this->condicion()." and cod_recargo=".$cod_recargo);
   echo'<script>';
                   echo" document.getElementById('iconos_2_".$var_n."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_1_".$var_n."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var_n."').innerHTML ='".$var_datos[0]['shd300_recargos']['denominacion']."' ;";
                   echo" document.getElementById('porcentaje_".$var_n."').innerHTML ='".$this->Formato2($var_datos[0]['shd300_recargos']['porcentaje'])."' ;";
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
         $var_datos = $this->shd300_recargos->findAll($this->condicion()." and cod_recargo=".$var1);
         $var2 = $var_datos[0]['shd300_recargos']['denominacion'];
         $var3 = $this->Formato2($var_datos[0]['shd300_recargos']['porcentaje']);

         $blur_porcentaje  = 'onblur=moneda("porcentaje'.$var1.'"); ';

      echo'<script>';
                   echo" document.getElementById('iconos_1_".$var1."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_2_".$var1."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var1."').innerHTML ='<input type=text name=data[shp300_recargos][denominacion".$var1."]    id=denominacion_".$var1."  value=\"$var2\"   class=inputtext  maxlength=100/>' ;";
                   echo" document.getElementById('porcentaje_".$var1."').innerHTML ='<input type=text name=data[shp300_recargos][porcentaje".$var1."]        id=porcentaje".$var1."    value=\"$var3\"   class=inputtext  onKeyPress=return solonumeros_con_punto(event); \"$blur_porcentaje\" style=text-align:center;/>';";
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
      $sw = $this->shd300_recargos->execute("DELETE FROM shd300_recargos  WHERE ".$condicion." and  cod_recargo='".$var1."' ");
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
      $var_datos = $this->shd300_recargos->findAll($this->condicion()." and cod_recargo=".$var1);
      $var2 = $var_datos[0]['shd300_recargos']['denominacion'];
      $var3 = $var_datos[0]['shd300_recargos']['porcentaje'];
      echo'<script>';
                   echo" document.getElementById('iconos_2_".$var1."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_1_".$var1."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var1."').innerHTML ='".$var2."' ;";
                   echo" document.getElementById('porcentaje_".$var1."').innerHTML ='".$this->Formato2($var3)."' ;";
     echo'</script>';
$this->render("funcion");
}//fin function






}//fin class
?>
