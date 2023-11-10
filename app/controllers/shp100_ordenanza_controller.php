<?php

 class Shp100OrdenanzaController extends AppController{
	var $uses = array('shd100_ordenanza', 'ccfd04_cierre_mes', 'shd003_codigo_ingresos');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');

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


function index($var=null, $var_cont=null, $cod_ingreso=999){
	$this->layout = "ajax";

	$cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and cod_ingreso=".$cod_ingreso;

	$tipo_impuesto=array('1'=>'PATENTE DE INDUSTRIA Y COMERCIO','2'=>'VEHÍCULOS','3'=>'PROPAGANDA COMERCIAL','4'=>'INMUEBLES URBANOS','5'=>'ASEO DOMICILIARIO','6'=>'ARRENDAMIENTO DE TIERRAS','7'=>'CRÉDITOS DE VIVIENDA');
	$this->concatena($tipo_impuesto,'tipo_impuesto');

	if($var==null){

		if($var_cont==null){$var_cont = $this->shd100_ordenanza->findCount($condicion);}else{$var_cont=1;}

			if($var_cont==0){
					$this->set("cod_ingreso", "");
	                 $this->set("porcentaje_descuento", "");
	                 $this->set("porcentaje_multa",     "");
	                 $this->set("porcentaje_recargo",   "");
	                 $this->set("porcentaje_interes",   "");
	                 $this->set("cod_ingreso", "");
	                 $this->set("impuesto",'');

		             echo'<script>';
		                   echo'document.getElementById("modificar").disabled = true;';
		                   echo'document.getElementById("guardar").disabled = false;';
		                   echo'document.getElementById("descuento").readOnly = false;';
		                   echo'document.getElementById("multa").readOnly = false;';
		                   echo'document.getElementById("recargo").readOnly = false;';
		                   echo'document.getElementById("interes").readOnly = false;';
		              echo'</script>';
			}else{
					echo'<script>';
						echo'document.getElementById("modificar").disabled = false;';
						echo'document.getElementById("guardar").disabled = true;';
						echo'document.getElementById("descuento").readOnly = true;';
						echo'document.getElementById("multa").readOnly = true;';
						echo'document.getElementById("recargo").readOnly = true;';
						echo'document.getElementById("interes").readOnly = true;';
					echo'</script>';
					$var_datos= $this->shd100_ordenanza->findAll($condicion);
					$this->set("cod_ingreso", $var_datos[0]['shd100_ordenanza']['cod_ingreso']);
					$this->set("porcentaje_descuento", $var_datos[0]['shd100_ordenanza']['porcentaje_descuento']);
					$this->set("porcentaje_multa",     $var_datos[0]['shd100_ordenanza']['porcentaje_multa']);
					$this->set("porcentaje_recargo",   $var_datos[0]['shd100_ordenanza']['porcentaje_recargo']);
					$this->set("porcentaje_interes",   $var_datos[0]['shd100_ordenanza']['porcentaje_interes']);
					$this->set("impuesto",$tipo_impuesto[$var_datos[0]['shd100_ordenanza']['cod_ingreso']]);
			}//fin

	 }else{

			echo'<script>';
				echo'document.getElementById("modificar").disabled = true;';
				echo'document.getElementById("guardar").disabled = false;';
				echo'document.getElementById("descuento").readOnly = false;';
				echo'document.getElementById("multa").readOnly = false;';
				echo'document.getElementById("recargo").readOnly = false;';
				echo'document.getElementById("interes").readOnly = false;';
			echo'</script>';
			$this->render("funcion");
	}//fin else

}//fin index


function funcion(){$this->layout = "ajax";}


function guardar(){
	$this->layout = "ajax";

	$cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$cod_ingreso            	 =       $this->data['shp100_ordenanza']['cod_ingreso'];

	$condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and cod_ingreso=".$cod_ingreso;

	$porcentaje_descuento        =       $this->Formato1($this->data['shp100_ordenanza']['descuento']);
	$porcentaje_multa            =       $this->Formato1($this->data['shp100_ordenanza']['multa']);
	$porcentaje_recargo          =       $this->Formato1($this->data['shp100_ordenanza']['recargo']);
	$porcentaje_interes          =       $this->Formato1($this->data['shp100_ordenanza']['interes']);

    if($cod_ingreso!=''){// Es distinto de vacio.
		$var_cont = $this->shd100_ordenanza->findCount($condicion);
        if($var_cont==0){
            $sw = $this->shd100_ordenanza->execute("BEGIN; INSERT INTO shd000_ordenanzas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_ingreso, porcentaje_descuento, porcentaje_multa, porcentaje_recargo, porcentaje_interes) VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$cod_ingreso."', '".$porcentaje_descuento."', '".$porcentaje_multa."', '".$porcentaje_recargo."', '".$porcentaje_interes."'); ");
        }else{
            $sw = $this->shd100_ordenanza->execute("BEGIN; UPDATE shd000_ordenanzas SET porcentaje_descuento='".$porcentaje_descuento."',  porcentaje_multa='".$porcentaje_multa."', porcentaje_recargo='".$porcentaje_recargo."', porcentaje_interes='".$porcentaje_interes."'  WHERE  ".$condicion);
        }

	    if($sw > 1){
	        $this->shd100_ordenanza->execute("COMMIT;");    $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
	    }else{
	     	$this->shd100_ordenanza->execute("ROLLBACK;"); $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
	    }
	    $this->index(null, 1, $cod_ingreso);
		$this->render("index");

    }else{// Es igual a vacio, se redirecciona a la vista principal con el valor 0.
	    $this->set('errorMessage', 'Atenci&oacute;n: debe seleccionar el c&oacute;digo de ingreso');
	    $this->index(null, 0);
		$this->render("index");
   }

}//fin function



function vista_codigo_ingreso($var=null){
	$this->layout = "ajax";

	$cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." and cod_ingreso=".$var;

	$tipo_impuesto=array('1'=>'PATENTE DE INDUSTRIA Y COMERCIO','2'=>'VEHÍCULOS','3'=>'PROPAGANDA COMERCIAL','4'=>'INMUEBLES URBANOS','5'=>'ASEO DOMICILIARIO','6'=>'ARRENDAMIENTO DE TIERRAS','7'=>'CRÉDITOS DE VIVIENDA');
	$this->concatena($tipo_impuesto,'tipo_impuesto');

	$var_cont = $this->shd100_ordenanza->findCount($condicion);

     if($var_cont==0){
     		 $this->set("cod_ingreso", "");
             $this->set("porcentaje_descuento", "");
             $this->set("porcentaje_multa",     "");
             $this->set("porcentaje_recargo",   "");
             $this->set("porcentaje_interes",   "");
             $this->set("cod_ingreso", "");
             $this->set("impuesto",'');

             $this->set("cod_ingreso", $var);
		 	 $this->set("impuesto",$tipo_impuesto[$var]);

             echo'<script>';
                   echo'document.getElementById("modificar").disabled = true;';
                   echo'document.getElementById("guardar").disabled = false;';
                   echo'document.getElementById("descuento").readOnly = false;';
                   echo'document.getElementById("multa").readOnly = false;';
                   echo'document.getElementById("recargo").readOnly = false;';
                   echo'document.getElementById("interes").readOnly = false;';
              echo'</script>';

          }else{

             echo'<script>';
                   echo'document.getElementById("modificar").disabled = false;';
                   echo'document.getElementById("guardar").disabled = true;';
                   echo'document.getElementById("descuento").readOnly = true;';
                   echo'document.getElementById("multa").readOnly = true;';
                   echo'document.getElementById("recargo").readOnly = true;';
                   echo'document.getElementById("interes").readOnly = true;';
              echo'</script>';

	         $var_datos= $this->shd100_ordenanza->findAll($condicion);
	         $this->set("cod_ingreso", $var_datos[0]['shd100_ordenanza']['cod_ingreso']);
             $this->set("porcentaje_descuento", $var_datos[0]['shd100_ordenanza']['porcentaje_descuento']);
             $this->set("porcentaje_multa",     $var_datos[0]['shd100_ordenanza']['porcentaje_multa']);
             $this->set("porcentaje_recargo",   $var_datos[0]['shd100_ordenanza']['porcentaje_recargo']);
             $this->set("porcentaje_interes",   $var_datos[0]['shd100_ordenanza']['porcentaje_interes']);
		 	 $this->set("impuesto",$tipo_impuesto[$var_datos[0]['shd100_ordenanza']['cod_ingreso']]);
           }//fin else

}



}//fin class
?>
