<?php


 class cfpp18ContratoColectivoSindicatoController extends AppController{
	var $uses = array('cfpd18_contrato_colectivo_sindicato', 'ccfd04_cierre_mes');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
 	var $name = "cfpp18_contrato_colectivo_sindicato";

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

     $var_cont = $this->cfpd18_contrato_colectivo_sindicato->findAll($this->condicion(), null, 'cod_sindicato ASC');
     $datos    = $this->cfpd18_contrato_colectivo_sindicato->findAll($this->condicion(), null, 'cod_sindicato ASC');
     $var_cont2 = 0;
     foreach($var_cont as $ve1){$var_cont2 = $ve1['cfpd18_contrato_colectivo_sindicato']['cod_sindicato'];}
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



if($var_n==null){ $cod_sindicato   =   $this->data['cfpp18_contrato_colectivo_sindicato']['codigo'.$var_n];}else{ $cod_sindicato =  $var_n;}


if(!empty($this->data['cfpp18_contrato_colectivo_sindicato']['denominacion'.$var_n])){
   $denominacion            =       $this->data['cfpp18_contrato_colectivo_sindicato']['denominacion'.$var_n];

   $var_cont = $this->cfpd18_contrato_colectivo_sindicato->findCount($this->condicion()." and cod_sindicato=".$cod_sindicato);

			         if($var_cont==0){
                        $sw = $this->cfpd18_contrato_colectivo_sindicato->execute("BEGIN; INSERT INTO cfpd18_contrato_colectivo_sindicato (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_sindicato, denominacion) VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$cod_sindicato."', '".$denominacion."'); ");
			         }else{
                        $sw = $this->cfpd18_contrato_colectivo_sindicato->execute("BEGIN; UPDATE cfpd18_contrato_colectivo_sindicato SET denominacion='".$denominacion."' WHERE ".$condicion." and cod_sindicato='".$cod_sindicato."' ");
			         }//fin function
}else{$sw="a";}




          if($sw>1){

        $this->cfpd18_contrato_colectivo_sindicato->execute("COMMIT;");    $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');

     }elseif($sw=="a"){

     	$this->cfpd18_contrato_colectivo_sindicato->execute("ROLLBACK;");  $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - FALTAN DATOS');

     }else{

     	$this->cfpd18_contrato_colectivo_sindicato->execute("ROLLBACK;");  $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
     }//fin else




if($var_n==null){
    $this->index();
	$this->render("index");
}else{

    $var_datos = $this->cfpd18_contrato_colectivo_sindicato->findAll($this->condicion()." and cod_sindicato=".$cod_sindicato);
   echo'<script>';
                   echo" document.getElementById('iconos_2_".$var_n."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_1_".$var_n."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var_n."').innerHTML ='".$var_datos[0]['cfpd18_contrato_colectivo_sindicato']['denominacion']."' ;";
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
         $var_datos = $this->cfpd18_contrato_colectivo_sindicato->findAll($this->condicion()." and cod_sindicato=".$var1);
         $var2 = $var_datos[0]['cfpd18_contrato_colectivo_sindicato']['denominacion'];


      echo'<script>';
                   echo" document.getElementById('iconos_1_".$var1."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_2_".$var1."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var1."').innerHTML ='<input type=text name=data[cfpp18_contrato_colectivo_sindicato][denominacion".$var1."]    id=denominacion_".$var1."  value=\"$var2\"   class=inputtext  maxlength=100/>' ;";
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
      $sw = $this->cfpd18_contrato_colectivo_sindicato->execute("DELETE FROM cfpd18_contrato_colectivo_sindicato  WHERE ".$condicion." and  cod_sindicato='".$var1."' ");

     $var_cont = $this->cfpd18_contrato_colectivo_sindicato->findAll($this->condicion(), null, 'cod_sindicato ASC');
     $var_cont2 = 0;
     foreach($var_cont as $ve1){$var_cont2 = $ve1['cfpd18_contrato_colectivo_sindicato']['cod_sindicato'];}
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
      $var_datos = $this->cfpd18_contrato_colectivo_sindicato->findAll($this->condicion()." and cod_sindicato=".$var1);
      $var2 = $var_datos[0]['cfpd18_contrato_colectivo_sindicato']['denominacion'];
      echo'<script>';
                   echo" document.getElementById('iconos_2_".$var1."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_1_".$var1."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var1."').innerHTML ='".$var2."' ;";
     echo'</script>';
$this->render("funcion");
}//fin function






}//fin class
?>
