<?php


 class Shp500AseoClasificacionController extends AppController{
	var $uses = array('shd500_aseo_clasificacion', 'ccfd04_cierre_mes', 'cscd04_ordencompra_parametros');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
 	var $name = "shp500_aseo_clasificacion";

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


function Formato_4_out($price) {
    $price = preg_replace("/[^0-9\.]/", "", str_replace(',','.',$price));
    if (substr($price,-5,1)=='.') {
        $sents = '.'.substr($price,-4);
        $price = substr($price,0,strlen($price)-5);
    } elseif (substr($price,-4,1)=='.') {
        $sents = '.'.substr($price,-1);
        $price = substr($price,0,strlen($price)-4);
    } else {
        $sents = '.0000';
    }

   if($sents==".0000"){
   	   	return number_format($price,4,',','.');
   }else{
     $price = preg_replace("/[^0-9]/", "", $price);
     return number_format($price.$sents,4,',','.');
   }//fin else
}//fin function

function Formato_4_in($monto) {
    $monto = preg_replace("/[^0-9\.]/", "", str_replace(',','.',$monto));
    if (substr($monto,-5,1)=='.') {
        $sents = '.'.substr($monto,-4);
        $monto = substr($monto,0,strlen($monto)-5);
    } elseif (substr($monto,-4,1)=='.') {
        $sents = '.'.substr($monto,-1);
        $monto = substr($monto,0,strlen($monto)-5);
    } else {
    	   $sents = '.0000';

    }
    $monto = preg_replace("/[^0-9]/", "", $monto);
    return number_format($monto.$sents,4,'.','');
}



function index($var=null, $var_cont=null){
	$this->layout = "ajax";

	  $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";

     $var_cont = $this->shd500_aseo_clasificacion->findAll($condicion, null, 'cod_clasificacion ASC');
     $datos    = $this->shd500_aseo_clasificacion->findAll($condicion, null, 'cod_clasificacion ASC');
     $vut = $this->cscd04_ordencompra_parametros->findAll($condicion, "unidad_tributaria", null, 1);
     $var_cont2 = 0;
     foreach($var_cont as $ve1){$var_cont2 = $ve1['shd500_aseo_clasificacion']['cod_clasificacion'];}
     $var_cont2++;

     $this->set('codigo', $var_cont2);
     $this->set('datos', $datos);
     $this->set('vut', $vut!=null ? $vut[0]['cscd04_ordencompra_parametros']['unidad_tributaria'] : 0);
}//fin index



function funcion(){$this->layout = "ajax";}


function guardar($var_n=null, $var2=null){

	$this->layout = "ajax";

      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";

if($var_n==null){ $cod_clasificacion   =   $this->data['shp500_aseo_clasificacion']['codigo'.$var_n];}else{ $cod_clasificacion =  $var_n;}

if(!empty($this->data['shp500_aseo_clasificacion']['denominacion'.$var_n]) && !empty($this->data['shp500_aseo_clasificacion']['monto_mensual'.$var_n]) && !empty($this->data['shp500_aseo_clasificacion']['valor_ut'.$var_n]) && !empty($this->data['shp500_aseo_clasificacion']['cantidad_ut'.$var_n])){
   $denominacion   =  $this->data['shp500_aseo_clasificacion']['denominacion'.$var_n];
   $valor_ut       =  $this->Formato1($this->data['shp500_aseo_clasificacion']['valor_ut'.$var_n]);
   $cantidad_ut    =  $this->Formato1($this->data['shp500_aseo_clasificacion']['cantidad_ut'.$var_n]);
   $monto_mensual  =  $this->Formato1($this->data['shp500_aseo_clasificacion']['monto_mensual'.$var_n]);

   $var_cont = $this->shd500_aseo_clasificacion->findCount($condicion." and cod_clasificacion=".$cod_clasificacion);

			         if($var_cont==0){
                        $sw = $this->shd500_aseo_clasificacion->execute("BEGIN; INSERT INTO shd500_aseo_clasificacion (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_clasificacion, denominacion, monto_mensual, valor_ut, cantidad_ut) VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$cod_clasificacion."', '".$denominacion."', '".$monto_mensual."', '".$valor_ut."', '".$cantidad_ut."'); ");
			         }else{
                        $sw = $this->shd500_aseo_clasificacion->execute("BEGIN; UPDATE shd500_aseo_clasificacion SET denominacion='".$denominacion."', monto_mensual='".$monto_mensual."', valor_ut='".$valor_ut."', cantidad_ut='".$cantidad_ut."' WHERE ".$condicion." and cod_clasificacion='".$cod_clasificacion."' ");
			         }//fin function
}else{$sw="a";}

	 if($sw>1){

        $this->shd500_aseo_clasificacion->execute("COMMIT;");    $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');

     }elseif($sw=="a"){

     	$this->shd500_aseo_clasificacion->execute("ROLLBACK;");  $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - FALTAN DATOS');

     }else{

     	$this->shd500_aseo_clasificacion->execute("ROLLBACK;");  $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
     }//fin else




if($var_n==null){
    $this->index();
	$this->render("index");
}else{

	$var_datos = $this->shd500_aseo_clasificacion->findAll($condicion." and cod_clasificacion=".$cod_clasificacion);
      if(!empty($var_datos)){
      	$this->set('ve', $var_datos);
      }else{
		$this->set('ve', array());
      }

	/*
   echo'<script>';
                   echo" document.getElementById('iconos_2_".$var_n."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_1_".$var_n."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var_n."').innerHTML ='".$var_datos[0]['shd500_aseo_clasificacion']['denominacion']."' ;";
                   echo" document.getElementById('monto_mensual_".$var_n."').innerHTML ='".$this->Formato2($var_datos[0]['shd500_aseo_clasificacion']['monto_mensual'])."' ;";
     echo'</script>';
     */

   $this->set('i', $var2);
   $this->render("guardar_editar");
}//fin else

}//fin function


function guardar_editar(){
	$this->layout = "ajax";
}


function editar($var1=null, $var2=null, $var3=null, $var4=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
         $var_datos = $this->shd500_aseo_clasificacion->findAll("cod_clasificacion=".$var1);
         $var2 = $var_datos[0]['shd500_aseo_clasificacion']['denominacion'];
         $var3 = $this->Formato2($var_datos[0]['shd500_aseo_clasificacion']['monto_mensual']);

         $blur_monto_mensual  = 'onblur=moneda("monto_mensual'.$var1.'"); ';

      echo'<script>';
                   echo" document.getElementById('iconos_1_".$var1."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_2_".$var1."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var1."').innerHTML ='<input type=text name=data[shp500_aseo_clasificacion][denominacion".$var1."]    id=denominacion".$var1."  value=\"$var2\"   class=inputtext  />' ;";
                   echo" document.getElementById('monto_mensual_".$var1."').innerHTML ='<input type=text name=data[shp500_aseo_clasificacion][monto_mensual".$var1."]        id=monto_mensual".$var1."    value=\"$var3\"   class=inputtext  onKeyPress=return solonumeros_con_punto(event); \"$blur_monto_mensual\" />';";
      echo'</script>';

$this->render("funcion");
}//fin function


function editar2($var1=null, $var2=null){
	$this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";

      $this->set('var1', $var1);
      $this->set('i', $var2);
      $var_datos = $this->shd500_aseo_clasificacion->findAll($condicion." and cod_clasificacion=".$var1);
      if(!empty($var_datos)){
      	$this->set('ve', $var_datos);
      }else{
		$this->set('ve', array());
      }
}


function eliminar($var1=null, $var2=null, $var3=null, $var4=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
      $sw = $this->shd500_aseo_clasificacion->execute("DELETE FROM shd500_aseo_clasificacion  WHERE ".$condicion." and  cod_clasificacion='".$var1."' ");

     $datos = $this->shd500_aseo_clasificacion->findAll($condicion, null, 'cod_clasificacion ASC');
     $var_cont2 = 0;
     foreach($datos as $ve1){$var_cont2 = $ve1['shd500_aseo_clasificacion']['cod_clasificacion'];}
     $var_cont2++;

     echo "<script>
                   document.getElementById('codigo').value = '".mascara($var_cont2,2)."';
     	</script>";

	$this->set('datos', $datos);

	// $this->render("funcion");
}//fin function



function cancelar($var1=null, $var2=null, $var3=null, $var4=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
      $var_datos = $this->shd500_aseo_clasificacion->findAll("cod_clasificacion=".$var1);
      $var2 = $var_datos[0]['shd500_aseo_clasificacion']['denominacion'];
      $var3 = $var_datos[0]['shd500_aseo_clasificacion']['monto_mensual'];
      echo'<script>';
                   echo" document.getElementById('iconos_2_".$var1."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_1_".$var1."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var1."').innerHTML ='".$var2."' ;";
                   echo" document.getElementById('monto_mensual_".$var1."').innerHTML ='".$this->Formato2($var3)."' ;";
     echo'</script>';
$this->render("funcion");
}//fin function


function cancelar2($var1=null, $var2=null){
	$this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
      $this->set('var1', $var1);
      $this->set('i', $var2);
      $var_datos = $this->shd500_aseo_clasificacion->findAll($condicion." and cod_clasificacion=".$var1);
      if(!empty($var_datos)){
      	$this->set('ve', $var_datos);
      }else{
		$this->set('ve', array());
      }
}


}//fin class
?>
