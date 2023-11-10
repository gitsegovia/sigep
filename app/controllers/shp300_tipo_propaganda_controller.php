<?php


 class Shp300TipoPropagandaController extends AppController{
	var $uses = array('shd300_tipo_propaganda', 'ccfd04_cierre_mes');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
 	var $name = "shp300_tipo_propaganda";

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



    function SQLCA($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
         if($ano!=null){
         	$sql_re .= "cod_dep=".$this->verifica_SS(5)."  and  ";
            $sql_re .= "ano=".$ano."  ";
         }else{
         	$sql_re .= "cod_dep=".$this->verifica_SS(5)." ";
         }
         return $sql_re;
    }//fin funcion SQLCA





function index($var=null, $var_cont=null){
	$this->layout = "ajax";

	  $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";

     $var_cont = $this->shd300_tipo_propaganda->findAll($this->SQLCA(), null, 'cod_tipo ASC');
     $datos    = $this->shd300_tipo_propaganda->findAll($this->SQLCA(), null, 'cod_tipo ASC');
     $var_cont2 = 0;
     foreach($var_cont as $ve1){$var_cont2 = $ve1['shd300_tipo_propaganda']['cod_tipo'];}
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



if($var_n==null){ $cod_tipo = $this->data['shp300_tipo_propaganda']['codigo'.$var_n];}else{ $cod_tipo =  $var_n;}

if(!empty($this->data['shp300_tipo_propaganda']['articulo'.$var_n]) &&
   !empty($this->data['shp300_tipo_propaganda']['denominacion'.$var_n]) &&
   !empty($this->data['shp300_tipo_propaganda']['monto'.$var_n]) &&
   !empty($this->data['shp300_tipo_propaganda']['tipo_unidad'.$var_n])){

   $denominacion            =       $this->data['shp300_tipo_propaganda']['denominacion'.$var_n];
   $articulo                =       $this->data['shp300_tipo_propaganda']['articulo'.$var_n];
   $monto                   =       $this->Formato1($this->data['shp300_tipo_propaganda']['monto'.$var_n]);
   $tipo_unidad             =       $this->data['shp300_tipo_propaganda']['tipo_unidad'.$var_n];

   $var_cont = $this->shd300_tipo_propaganda->findCount($this->condicion()." and cod_tipo=".$cod_tipo);

			         if($var_cont==0){
                        $sw = $this->shd300_tipo_propaganda->execute("BEGIN; INSERT INTO shd300_tipo_propaganda (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo, denominacion, articulo, monto, tipo_unidad) VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$cod_tipo."', '".$denominacion."', '".$articulo."', '".$monto."', '".$tipo_unidad."'); ");
			         }else{
                        $sw = $this->shd300_tipo_propaganda->execute("BEGIN; UPDATE shd300_tipo_propaganda SET denominacion='".$denominacion."', monto='".$monto."', tipo_unidad='".$tipo_unidad."', articulo='".$articulo."' WHERE ".$condicion." and cod_tipo='".$cod_tipo."' ");
			         }//fin function
}else{$sw="a";}




          if($sw>1){

        $this->shd300_tipo_propaganda->execute("COMMIT;");    $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');

     }elseif($sw=="a"){

     	$this->shd300_tipo_propaganda->execute("ROLLBACK;");  $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS -- FALTAN DATOS');

     }else{

     	$this->shd300_tipo_propaganda->execute("ROLLBACK;");  $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
     }//fin else




if($var_n==null){
    $this->index();
	$this->render("index");
}else{

    $var_datos = $this->shd300_tipo_propaganda->findAll($this->condicion()." and cod_tipo=".$cod_tipo);
   echo'<script>';
                   echo" document.getElementById('iconos_2_".$var_n."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_1_".$var_n."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var_n."').innerHTML ='".$var_datos[0]['shd300_tipo_propaganda']['denominacion']."' ;";
                   echo" document.getElementById('articulo_".$var_n."').innerHTML ='".$var_datos[0]['shd300_tipo_propaganda']['articulo']."' ;";
                   echo" document.getElementById('monto_".$var_n."').innerHTML ='".$this->Formato2($var_datos[0]['shd300_tipo_propaganda']['monto'])."' ;";
                   if($var_datos[0]['shd300_tipo_propaganda']['tipo_unidad']==1){
                   	  $var_datos[0]['shd300_tipo_propaganda']['tipo_unidad']="Unidad";
                }else{$var_datos[0]['shd300_tipo_propaganda']['tipo_unidad']="Metros";}
                   echo" document.getElementById('tipo_unidad_".$var_n."').innerHTML ='".$var_datos[0]['shd300_tipo_propaganda']['tipo_unidad']."' ;";
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
         $var_datos = $this->shd300_tipo_propaganda->findAll($this->condicion()." and cod_tipo=".$var1);
         $var2 = $var_datos[0]['shd300_tipo_propaganda']['denominacion'];
         $var3 = $var_datos[0]['shd300_tipo_propaganda']['articulo'];
         $var4 = $this->Formato2($var_datos[0]['shd300_tipo_propaganda']['monto']);
         $var5 = $var_datos[0]['shd300_tipo_propaganda']['tipo_unidad'];

         $blur_monto   = 'onblur=moneda("monto'.$var1.'"); ';
         $blur_tipo_unidad  = '';


      echo'<script>';
                   echo" document.getElementById('iconos_1_".$var1."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_2_".$var1."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var1."').innerHTML ='<input type=text name=data[shp300_tipo_propaganda][denominacion".$var1."]    id=denominacion_".$var1."  value=\"$var2\"   class=inputtext  maxlength=100 />' ;";
                   echo" document.getElementById('articulo_".$var1."').innerHTML ='<input type=text name=data[shp300_tipo_propaganda][articulo".$var1."]            id=articulo".$var1."      value=\"$var3\"   class=inputtext   maxlength=10  style=text-align:center;/>' ;";
                   echo" document.getElementById('monto_".$var1."').innerHTML ='<input type=text name=data[shp300_tipo_propaganda][monto".$var1."]                  id=monto".$var1."         value=\"$var4\"   class=inputtext  onKeyPress=return solonumeros_con_punto(event); \"$blur_monto\"  style=text-align:right;/>'  ;";
                   if($var5==1){$opcion_a="checked"; $opcion_b="";}else{$opcion_a=""; $opcion_b="checked";}
                   echo" document.getElementById('tipo_unidad_".$var1."').innerHTML ='<input type=radio name=data[shp300_tipo_propaganda][tipo_unidad".$var1."] id=unidad".$var1." value=1 ".$opcion_a." ><label for=unidad".$var1.">Unidad</label><input type=radio name=data[shp300_tipo_propaganda][tipo_unidad".$var1."] id=metros".$var1." value=2 ".$opcion_b."><label for=metros".$var1.">Metros</label>'; ";
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
      $sw = $this->shd300_tipo_propaganda->execute("DELETE FROM shd300_tipo_propaganda  WHERE ".$condicion." and  cod_tipo='".$var1."' ");
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
      $var_datos = $this->shd300_tipo_propaganda->findAll($this->condicion()." and cod_tipo=".$var1);
         $var2 = $var_datos[0]['shd300_tipo_propaganda']['denominacion'];
         $var3 = $var_datos[0]['shd300_tipo_propaganda']['articulo'];
         $var4 = $this->Formato2($var_datos[0]['shd300_tipo_propaganda']['monto']);
         $var5 = $var_datos[0]['shd300_tipo_propaganda']['tipo_unidad'];
      echo'<script>';
                   echo" document.getElementById('iconos_2_".$var1."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_1_".$var1."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var1."').innerHTML ='".$var2."' ;";
                   echo" document.getElementById('articulo_".$var1."').innerHTML ='".$var3."' ;";
                   echo" document.getElementById('monto_".$var1."').innerHTML ='".$this->Formato2($var4)."' ;";
                   if($var5==1){$var5="Unidad";}else{$var5="Metros";}
                   echo" document.getElementById('tipo_unidad_".$var1."').innerHTML ='".$var5."' ;";
     echo'</script>';
$this->render("funcion");
}//fin function






}//fin class
?>
