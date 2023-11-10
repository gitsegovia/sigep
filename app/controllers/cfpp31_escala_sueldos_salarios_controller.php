<?php

 class Cfpp31EscalaSueldosSalariosController extends AppController{
 	var $name="cfpp31_escala_sueldos_salarios";
	var $uses = array('cfpd31_escala_sueldos_salarios', 'cfpd01_formulacion');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap', 'Fpdf');

function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
}//fin checksession

function beforeFilter(){
    $this->checkSession();

}//fin before filter



function index($var=null, $var_cont=null){
	$this->layout = "ajax";

	  $cod_presi     = $this->Session->read('SScodpresi');
	  $cod_entidad   = $this->Session->read('SScodentidad');
	  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	  $cod_inst      = $this->Session->read('SScodinst');
	  $cod_dep       = $this->Session->read('SScoddep');
	  $condicion     = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst');
      $datos         = $this->cfpd31_escala_sueldos_salarios->findAll($condicion, null, ' ejercicio_fiscal DESC, numero_escala ASC');
      $this->set('datos', $datos);
      $condicion_formulacion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' ';
	  $year2 = $this->cfpd01_formulacion->findAll($condicion_formulacion, null, 'ano_formular ASC', null);
	  $ano_formulacion = null;
      foreach($year2 as $year2){$ano = $year2['cfpd01_formulacion']['ano_formular'];}
	  $ano = $year2['cfpd01_formulacion']['ano_formular'];
      $this->set('year', $ano);
}//fin index

function funcion(){$this->layout = "ajax";}

function guardar($var_n=null, $var_n2=null){

	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst');
	  $escala = 0;


if($var_n==null){ $ejercicio_fiscal = $this->data['cfpp31_escala_sueldos_salarios']['ejercicio_fiscal'.$var_n];}else{ $ejercicio_fiscal =  $var_n;}
if($var_n2==null){ $grupo           = $this->data['cfpp31_escala_sueldos_salarios']['grupo'.$var_n2];}else{ $grupo =  $var_n2;}

	$grupo = strtoupper(trim($grupo));

    if ($grupo=="I"){ $escala=1; }
    else if ($grupo=="II"){ $escala=2; }
    else if ($grupo=="III"){ $escala=3; }
    else if ($grupo=="IV"){ $escala=4; }
    else if ($grupo=="V"){ $escala=5; }
    else if ($grupo=="VI"){ $escala=6; }
    else if ($grupo=="VII"){ $escala=7; }
    else if ($grupo=="VIII"){ $escala=8; }
    else if ($grupo=="IX"){ $escala=9; }
    else if ($grupo=="X"){ $escala=10; }
    else if ($grupo=="XI"){ $escala=11; }
    else if ($grupo=="XII"){ $escala=12; }
    else if ($grupo=="XIII"){ $escala=13; }
    else if ($grupo=="XIV"){ $escala=14; }
    else if ($grupo=="XV"){ $escala=15; }
    else if ($grupo=="XVI"){ $escala=16; }
    else if ($grupo=="XVII"){ $escala=17; }
    else if ($grupo=="XVIII"){ $escala=18; }

	if($escala!=0){

if(!empty($this->data['cfpp31_escala_sueldos_salarios']['monto_desde'.$var_n2])){
   $monto_desde      = Formato1($this->data['cfpp31_escala_sueldos_salarios']['monto_desde'.$var_n2]);
   $monto_hasta      = Formato1($this->data['cfpp31_escala_sueldos_salarios']['monto_hasta'.$var_n2]);
   $var_cont         = $this->cfpd31_escala_sueldos_salarios->findCount($condicion." and ejercicio_fiscal='".$ejercicio_fiscal."' and grupo='".$grupo."'");
     if($var_cont==0){
        $sw = $this->cfpd31_escala_sueldos_salarios->execute("BEGIN; INSERT INTO cfpd31_escala_sueldos_salarios (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, ejercicio_fiscal, grupo, monto_desde, monto_hasta, numero_escala) VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$ejercicio_fiscal."', '".$grupo."', '".$monto_desde."', '".$monto_hasta."', '".$escala."'); ");
     }else{
        $sw = $this->cfpd31_escala_sueldos_salarios->execute("BEGIN; UPDATE cfpd31_escala_sueldos_salarios SET monto_desde='".$monto_desde."', monto_hasta='".$monto_hasta."' WHERE ".$condicion." and ejercicio_fiscal='".$ejercicio_fiscal."' and grupo='".$grupo."' ");
     }//fin function
}else{$sw="a";}
          if($sw>1){
        $this->cfpd31_escala_sueldos_salarios->execute("COMMIT;");    $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
     }elseif($sw=="a"){
     	$this->cfpd31_escala_sueldos_salarios->execute("ROLLBACK;");  $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS -- FALTAN DATOS');
     }else{
     	$this->cfpd31_escala_sueldos_salarios->execute("ROLLBACK;");  $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
     }//fin else

} // fin if de la escala != 0

else{
	$this->set('errorMessage', 'DATOS NO GUARDADOS, GRUPO NO V&Aacute;LIDO');
}

	if($var_n==null){
		$this->set('autor_valido',true);
	    $this->index();
		$this->render("index");
	}else{
	    $var_datos = $this->cfpd31_escala_sueldos_salarios->findAll($condicion." and ejercicio_fiscal='".$ejercicio_fiscal."' and grupo='".$grupo."' ");
	    echo'<script>';
           echo" document.getElementById('iconos_2_".$var_n2."').style.display = 'none'; ";
           echo" document.getElementById('iconos_1_".$var_n2."').style.display = 'block'; ";
           echo" document.getElementById('monto_desde_".$var_n2."').innerHTML ='".Formato2($var_datos[0]['cfpd31_escala_sueldos_salarios']['monto_desde'])."' ;";
           echo" document.getElementById('monto_hasta_".$var_n2."').innerHTML ='".Formato2($var_datos[0]['cfpd31_escala_sueldos_salarios']['monto_hasta'])."' ;";
	    echo'</script>';
	    $this->render("funcion");
	}//fin else
}//fin function

function editar($var1=null, $var2=null){
	  $this->layout = "ajax";
      $cod_presi     = $this->Session->read('SScodpresi');
	  $cod_entidad   = $this->Session->read('SScodentidad');
	  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	  $cod_inst      = $this->Session->read('SScodinst');
	  $cod_dep       = $this->Session->read('SScoddep');
	  $condicion     = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and ejercicio_fiscal='".$var1."' and grupo='".$var2."' ";
	  $var_datos     = $this->cfpd31_escala_sueldos_salarios->findAll($condicion);
      $monto_desde   = Formato2($var_datos[0]['cfpd31_escala_sueldos_salarios']['monto_desde']);
      $monto_hasta   = Formato2($var_datos[0]['cfpd31_escala_sueldos_salarios']['monto_hasta']);
      echo'<script>';
       echo" document.getElementById('iconos_1_".$var2."').style.display = 'none'; ";
       echo" document.getElementById('iconos_2_".$var2."').style.display = 'block'; ";
       echo" document.getElementById('monto_desde_".$var2."').innerHTML ='<input type=text name=data[cfpp31_escala_sueldos_salarios][monto_desde".$var2."]  id=monto_desde".$var2."  value=\"$monto_desde\"  class=campoText  onChange=moneda(\"monto_desde$var2\"); style=\"text-align:right;\"/>'; ";
       echo" document.getElementById('monto_hasta_".$var2."').innerHTML ='<input type=text name=data[cfpp31_escala_sueldos_salarios][monto_hasta".$var2."]  id=monto_hasta".$var2."  value=\"$monto_hasta\"  class=campoText  onChange=moneda(\"monto_hasta$var2\"); style=\"text-align:right;\"/>'; ";
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
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and ejercicio_fiscal='".$var1."' and grupo='".$var2."' ";
	  $sw = $this->cfpd31_escala_sueldos_salarios->execute("DELETE FROM cfpd31_escala_sueldos_salarios WHERE ".$condicion);
	  $this->set('mensaje','EL REGISTRO FUE ELIMINADO');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst');
 	  $datos    = $this->cfpd31_escala_sueldos_salarios->findAll($condicion, null, ' ejercicio_fiscal DESC, numero_escala ASC');
      $this->set('datos', $datos);
}//fin function

function cancelar($var1=null, $var2=null){
	  $this->layout = "ajax";
	  $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and ejercicio_fiscal='".$var1."' and grupo='".$var2."' ";
      $var_datos = $this->cfpd31_escala_sueldos_salarios->findAll($condicion);
	    echo'<script>';
           echo" document.getElementById('iconos_2_".$var2."').style.display = 'none'; ";
           echo" document.getElementById('iconos_1_".$var2."').style.display = 'block'; ";
           echo" document.getElementById('monto_desde_".$var2."').innerHTML ='".Formato2($var_datos[0]['cfpd31_escala_sueldos_salarios']['monto_desde'])."' ;";
           echo" document.getElementById('monto_hasta_".$var2."').innerHTML ='".Formato2($var_datos[0]['cfpd31_escala_sueldos_salarios']['monto_hasta'])."' ;";
	    echo'</script>';
$this->render("funcion");
}//fin function


function pasar($var1=null, $var2=null){
	  $this->layout = "ajax";
	  $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');

				  $ano_desde = $this->data['cfpp31_escala_sueldos_salarios']['ano_desde'];
				  $ano_hasta = $this->data['cfpp31_escala_sueldos_salarios']['ano_hasta'];

	 $srt_desde = strlen($ano_desde);
	 $srt_hasta = strlen($ano_hasta);

	 if($srt_desde<4 || $srt_hasta<4){

	 	$this->set('errorMessage', "Verifique que los años tengan un formato de 4 digitos");

	 }else{
				  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and ejercicio_fiscal='".$ano_desde."' ";
			      $var_datos = $this->cfpd31_escala_sueldos_salarios->findAll($condicion);

			      foreach($var_datos as $aux){

					  $cod_presi         =  $aux["cfpd31_escala_sueldos_salarios"]["cod_presi"];
					  $cod_entidad       =  $aux["cfpd31_escala_sueldos_salarios"]["cod_entidad"];
					  $cod_tipo_inst     =  $aux["cfpd31_escala_sueldos_salarios"]["cod_tipo_inst"];
					  $cod_inst          =  $aux["cfpd31_escala_sueldos_salarios"]["cod_inst"];
					  $ejercicio_fiscal  =  $ano_hasta;
					  $grupo             =  $aux["cfpd31_escala_sueldos_salarios"]["grupo"];
					  $monto_desde       =  $aux["cfpd31_escala_sueldos_salarios"]["monto_desde"];
					  $monto_hasta       =  $aux["cfpd31_escala_sueldos_salarios"]["monto_hasta"];
					  $numero_escala       =  $aux["cfpd31_escala_sueldos_salarios"]["numero_escala"];
			          $condicion = "cod_presi=".$cod_presi."  and  cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and  cod_inst=".$cod_inst." and ejercicio_fiscal='".$ejercicio_fiscal."' ";
					  $var_cont         = $this->cfpd31_escala_sueldos_salarios->findCount($condicion);
				      if($var_cont==0){
				        $sw = $this->cfpd31_escala_sueldos_salarios->execute("INSERT INTO cfpd31_escala_sueldos_salarios (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, ejercicio_fiscal, grupo, monto_desde, monto_hasta, numero_escala) VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$ejercicio_fiscal."', '".$grupo."', '".$monto_desde."', '".$monto_hasta."', '".$numero_escala."'); ");
				      }//fin
			      }//fin foreach

			      $this->set('Message_existe', "LOS DATOS FUERON GUARDADOS");
	 }

$this->index();
$this->render("index");
}//fin function


}//fin class
?>