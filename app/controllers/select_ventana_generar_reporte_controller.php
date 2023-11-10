<?php

class SelectVentanaGenerarReporteController extends AppController
{
	var $name = "select_ventana_generar_reporte";
    var $uses = array('Usuario', 'cugd04_entrada_modulo','modulos', "cugd02_dependencia", "cugd02_institucion", "arrd05",
                      "v_cscd04_ordencompra", "ccfd04_cierre_mes", "cepd01_compromiso_cuerpo", "cepd03_ordenpago_cuerpo");
    var $helpers = array('Html', 'Javascript', 'Ajax','Sisap');

function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
}//fin checksession



function beforeFilter(){$this->checkSession();}







function v_reporte_emision_compra_bienes_1(){

    $this->layout="ajax";

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');

	 $ano = $this->ano_ejecucion();
	 $this->set('year', $ano);
	 $this->Session->write('year_v_reporte_emision_compra_bienes_1', $ano);

}//fin function


function v_reporte_emision_compra_bienes_2($pagina=null, $pista=null){


	$this->layout="ajax";

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');

	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;

if($pista!=null){
	  $this->Session->write('pista', $pista);
}else{
      $pista = $this->Session->read('pista');
}//fin else

$condicion .=" and ano_orden_compra='". $this->Session->read('year_v_reporte_emision_compra_bienes_1')."' and tipo_orden=1  and  condicion_actividad=1 and  (mayus_acentos(razon_social) LIKE mayus_acentos('%".$pista."%') or mayus_acentos(numero_orden_compra::text) LIKE mayus_acentos('%".$pista."%')) ";

				            $Tfilas=$this->v_cscd04_ordencompra->findCount($condicion);
						        if($Tfilas!=0){
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_cscd04_ordencompra->findAll($condicion,null,"numero_orden_compra ASC",50,$pagina,null);
							        $this->set("datos",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						        }else{
						        	$this->set("datos",'');
						        }

$this->set("pista",$pista);



}//fin function


function v_reporte_emision_compra_bienes_3($var1=null){

    $this->layout="ajax";
    $this->Session->write('year_v_reporte_emision_compra_bienes_1', $var1);

}//fin function

function v_reporte_emision_compra_ambas_1(){

    $this->layout="ajax";

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');

	 $ano = $this->ano_ejecucion();
	 $this->set('year', $ano);
	 $this->Session->write('year_v_reporte_emision_compra_ambas_1', $ano);

}//fin function


function v_reporte_emision_compra_ambas_2($pagina=null, $pista=null){


	$this->layout="ajax";

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');

	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;

	if($pista!=null){
		  $this->Session->write('pista', $pista);
	}else{
	      $pista = $this->Session->read('pista');
	}//fin else

	$condicion .=" and ano_orden_compra='". $this->Session->read('year_v_reporte_emision_compra_ambas_1')."' and tipo_orden=3  and  condicion_actividad=1 and  (mayus_acentos(razon_social) LIKE mayus_acentos('%".$pista."%') or mayus_acentos(numero_orden_compra::text) LIKE mayus_acentos('%".$pista."%')) ";

    $Tfilas=$this->v_cscd04_ordencompra->findCount($condicion);
    if($Tfilas!=0){
    	$Tfilas=(int)ceil($Tfilas/50);
    	$this->set('total_paginas',$Tfilas);
		$this->set('pagina_actual',$pagina);
		$this->set('pag_cant',$pagina.'/'.$Tfilas);
		$this->set('ultimo',$Tfilas);
 	    $datos_filas=$this->v_cscd04_ordencompra->findAll($condicion,null,"numero_orden_compra ASC",50,$pagina,null);
        $this->set("datos",$datos_filas);
        $this->set('siguiente',$pagina+1);
		$this->set('anterior',$pagina-1);
		$this->bt_nav($Tfilas,$pagina);
    }else{
    	$this->set("datos",'');
    }

	$this->set("pista",$pista);

}//fin function


function v_reporte_emision_compra_ambas_3($var1=null){

    $this->layout="ajax";
    $this->Session->write('year_v_reporte_emision_compra_ambas_1', $var1);

}//fin function






//****************************************************************************
// Para los formatos preimpresos actuales para la orden de compra de bienes //
//****************************************************************************
function v_reporte_emision_compra_bienes_1_actual(){

    $this->layout="ajax";

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');

	 $ano = $this->ano_ejecucion();
	 $this->set('year', $ano);
	 $this->Session->write('year_v_reporte_emision_compra_bienes_1', $ano);

}//fin function


function v_reporte_emision_compra_bienes_2_actual($pagina=null, $pista=null){


	$this->layout="ajax";

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');

	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;

if($pista!=null){
	  $this->Session->write('pista', $pista);
}else{
      $pista = $this->Session->read('pista');
}//fin else

$condicion .=" and ano_orden_compra='". $this->Session->read('year_v_reporte_emision_compra_bienes_1')."' and tipo_orden=1  and  condicion_actividad=1 and  (mayus_acentos(razon_social) LIKE mayus_acentos('%".$pista."%') or mayus_acentos(numero_orden_compra::text) LIKE mayus_acentos('%".$pista."%')) ";

				            $Tfilas=$this->v_cscd04_ordencompra->findCount($condicion);
						        if($Tfilas!=0){
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_cscd04_ordencompra->findAll($condicion,null,"numero_orden_compra ASC",50,$pagina,null);
							        $this->set("datos",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						        }else{
						        	$this->set("datos",'');
						        }

$this->set("pista",$pista);



}//fin function


function v_reporte_emision_compra_bienes_3_actual($var1=null){

    $this->layout="ajax";
    $this->Session->write('year_v_reporte_emision_compra_bienes_1', $var1);

}//fin function






















function v_reporte_emision_compra_servicio_1(){

    $this->layout="ajax";

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');

	 $ano = $this->ano_ejecucion();
	 $this->set('year', $ano);
	 $this->Session->write('year_v_reporte_emision_compra_servicio_1', $ano);

}//fin function


function v_reporte_emision_compra_servicio_2($pagina=null, $pista=null){


	$this->layout="ajax";

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');

	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;

if($pista!=null){
	  $this->Session->write('pista', $pista);
}else{
      $pista = $this->Session->read('pista');
}//fin else

$condicion .=" and ano_orden_compra='". $this->Session->read('year_v_reporte_emision_compra_servicio_1')."' and tipo_orden=2  and  condicion_actividad=1 and  (mayus_acentos(razon_social) LIKE mayus_acentos('%".$pista."%') or mayus_acentos(numero_orden_compra::text) LIKE mayus_acentos('%".$pista."%')) ";

				            $Tfilas=$this->v_cscd04_ordencompra->findCount($condicion);
						        if($Tfilas!=0){
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_cscd04_ordencompra->findAll($condicion,null,"numero_orden_compra ASC",50,$pagina,null);
							        $this->set("datos",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						        }else{
						        	$this->set("datos",'');
						        }

$this->set("pista",$pista);



}//fin function


function v_reporte_emision_compra_servicio_3($var1=null){

    $this->layout="ajax";
    $this->Session->write('year_v_reporte_emision_compra_servicio_1', $var1);

}//fin function









//****************************************************************************
// Para los formatos preimpresos actuales para la orden de compra de bienes //
//****************************************************************************
function v_reporte_emision_compra_servicio_1_actual(){

    $this->layout="ajax";

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');

	 $ano = $this->ano_ejecucion();
	 $this->set('year', $ano);
	 $this->Session->write('year_v_reporte_emision_compra_servicio_1', $ano);

}//fin function


function v_reporte_emision_compra_servicio_2_actual($pagina=null, $pista=null){


	$this->layout="ajax";

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');

	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;

if($pista!=null){
	  $this->Session->write('pista', $pista);
}else{
      $pista = $this->Session->read('pista');
}//fin else

$condicion .=" and ano_orden_compra='". $this->Session->read('year_v_reporte_emision_compra_servicio_1')."' and tipo_orden=2  and  condicion_actividad=1 and  (mayus_acentos(razon_social) LIKE mayus_acentos('%".$pista."%') or mayus_acentos(numero_orden_compra::text) LIKE mayus_acentos('%".$pista."%')) ";

				            $Tfilas=$this->v_cscd04_ordencompra->findCount($condicion);
						        if($Tfilas!=0){
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_cscd04_ordencompra->findAll($condicion,null,"numero_orden_compra ASC",50,$pagina,null);
							        $this->set("datos",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						        }else{
						        	$this->set("datos",'');
						        }

$this->set("pista",$pista);



}//fin function


function v_reporte_emision_compra_servicio_3_actual($var1=null){

    $this->layout="ajax";
    $this->Session->write('year_v_reporte_emision_compra_servicio_1', $var1);

}//fin function














function v_reporte_emision_rc_generico_1(){

    $this->layout="ajax";

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');

	 $ano = $this->ano_ejecucion();
	 $this->set('year', $ano);
	 $this->Session->write('year_v_reporte_emision_rc_generico_1', $ano);

}//fin function


function v_reporte_emision_rc_generico_2($pagina=null, $pista=null){


	$this->layout="ajax";

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');

	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;

if($pista!=null){
	  $this->Session->write('pista', $pista);
}else{
      $pista = $this->Session->read('pista');
}//fin else

$condicion .=" and ano_documento='". $this->Session->read('year_v_reporte_emision_rc_generico_1')."' and  condicion_actividad=1 and  (mayus_acentos(beneficiario) LIKE mayus_acentos('%".$pista."%') or mayus_acentos(numero_documento::text) LIKE mayus_acentos('%".$pista."%')) ";

				            $Tfilas=$this->cepd01_compromiso_cuerpo->findCount($condicion);
						        if($Tfilas!=0){
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->cepd01_compromiso_cuerpo->findAll($condicion,null,"numero_documento ASC",50,$pagina,null);
							        $this->set("datos",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						        }else{
						        	$this->set("datos",'');
						        }

$this->set("pista",$pista);



}//fin function


function v_reporte_emision_rc_generico_3($var1=null){

    $this->layout="ajax";
    $this->Session->write('year_v_reporte_emision_rc_generico_1', $var1);

}//fin function


















function v_reporte_emision_rc_pre_imp_1(){

    $this->layout="ajax";

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');

	 $ano = $this->ano_ejecucion();
	 $this->set('year', $ano);
	 $this->Session->write('year_v_reporte_emision_rc_pre_imp_1', $ano);

}//fin function


function v_reporte_emision_rc_pre_imp_2($pagina=null, $pista=null){


	$this->layout="ajax";

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');

	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;

if($pista!=null){
	  $this->Session->write('pista', $pista);
}else{
      $pista = $this->Session->read('pista');
}//fin else

$condicion .=" and ano_documento='". $this->Session->read('year_v_reporte_emision_rc_pre_imp_1')."' and  condicion_actividad=1 and  (mayus_acentos(beneficiario) LIKE mayus_acentos('%".$pista."%') or mayus_acentos(numero_documento::text) LIKE mayus_acentos('%".$pista."%')) ";

				            $Tfilas=$this->cepd01_compromiso_cuerpo->findCount($condicion);
						        if($Tfilas!=0){
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->cepd01_compromiso_cuerpo->findAll($condicion,null,"numero_documento ASC",50,$pagina,null);
							        $this->set("datos",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						        }else{
						        	$this->set("datos",'');
						        }

$this->set("pista",$pista);



}//fin function


function v_reporte_emision_rc_pre_imp_3($var1=null){

    $this->layout="ajax";
    $this->Session->write('year_v_reporte_emision_rc_pre_imp_1', $var1);

}//fin function



















function v_reporte_emision_op_libre_1(){

    $this->layout="ajax";

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');

	 $ano = $this->ano_ejecucion();
	 $this->set('year', $ano);
	 $this->Session->write('year_v_reporte_emision_op_libre_1', $ano);

}//fin function


function v_reporte_emision_op_libre_2($pagina=null, $pista=null){


	$this->layout="ajax";

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');

	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;

	if($pista!=null){
		  $this->Session->write('pista', $pista);
	}else{
	      $pista = $this->Session->read('pista');
	}//fin else

	if(is_numeric($pista)){ 
		$condicion .=" and ano_orden_pago='". $this->Session->read('year_v_reporte_emision_op_libre_1')."' and  condicion_actividad=1 and ((numero_orden_pago = ".$pista." and numero_orden_pago_secuencia = '".$pista."') OR (numero_orden_pago = ".$pista." and numero_orden_pago_secuencia = '0'))"; 
	}else{
		/*$condicion .=" and ano_orden_pago='". $this->Session->read('year_v_reporte_emision_op_libre_1')."' and  condicion_actividad=1 and  (mayus_acentos(beneficiario) LIKE mayus_acentos('%".$pista."%') or mayus_acentos(numero_orden_pago::text) LIKE mayus_acentos('%".$pista."%'))";*/
		$condicion .=" and ano_orden_pago='". $this->Session->read('year_v_reporte_emision_op_libre_1')."' and  condicion_actividad=1 and  mayus_acentos(beneficiario) LIKE mayus_acentos('%".$pista."%') and ((numero_orden_pago::TEXT=numero_orden_pago_secuencia::TEXT) OR (numero_orden_pago_secuencia='0'))";
	}
	
				            $Tfilas=$this->cepd03_ordenpago_cuerpo->findCount($condicion);
						        if($Tfilas!=0){
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('ultimo',$Tfilas);

						     	    $datos_filas=$this->cepd03_ordenpago_cuerpo->findAll($condicion,null,"numero_orden_pago ASC",50,$pagina,null);
							        $this->set("datos",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						        }else{
						        	$this->set("datos",'');
						        }

$this->set("pista",$pista);



}//fin function


function v_reporte_emision_op_libre_3($var1=null){

    $this->layout="ajax";
    $this->Session->write('year_v_reporte_emision_op_libre_1', $var1);

}//fin function





//********************************************
// Para las OP con el formato actual
//********************************************
function v_reporte_emision_op_libre_1_actual(){

    $this->layout="ajax";

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');

	 $ano = $this->ano_ejecucion();
	 $this->set('year', $ano);
	 $this->Session->write('year_v_reporte_emision_op_libre_1', $ano);

}//fin function


function v_reporte_emision_op_libre_2_actual($pagina=null, $pista=null){


	$this->layout="ajax";

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');

	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;

if($pista!=null){
	  $this->Session->write('pista', $pista);
}else{
      $pista = $this->Session->read('pista');
}//fin else

$condicion .=" and ano_orden_pago='". $this->Session->read('year_v_reporte_emision_op_libre_1')."' and  condicion_actividad=1 and  (mayus_acentos(beneficiario) LIKE mayus_acentos('%".$pista."%') or mayus_acentos(numero_orden_pago::text) LIKE mayus_acentos('%".$pista."%')) ";

				            $Tfilas=$this->cepd03_ordenpago_cuerpo->findCount($condicion);
						        if($Tfilas!=0){
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->cepd03_ordenpago_cuerpo->findAll($condicion,null,"numero_orden_pago ASC",50,$pagina,null);
							        $this->set("datos",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						        }else{
						        	$this->set("datos",'');
						        }

$this->set("pista",$pista);



}//fin function


function v_reporte_emision_op_libre_3_actual($var1=null){

    $this->layout="ajax";
    $this->Session->write('year_v_reporte_emision_op_libre_1', $var1);

}//fin function

















function v_reporte_emision_op_generico_1(){

    $this->layout="ajax";

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');

	 $ano = $this->ano_ejecucion();
	 $this->set('year', $ano);
	 $this->Session->write('year_v_reporte_emision_op_generico_1', $ano);

}//fin function


function v_reporte_emision_op_generico_2($pagina=null, $pista=null){


	$this->layout="ajax";

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');

	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep;

if($pista!=null){
	  $this->Session->write('pista', $pista);
}else{
      $pista = $this->Session->read('pista');
}//fin else

$condicion .=" and ano_orden_pago='". $this->Session->read('year_v_reporte_emision_op_generico_1')."' and  condicion_actividad=1 and  (mayus_acentos(beneficiario) LIKE mayus_acentos('%".$pista."%') or mayus_acentos(numero_orden_pago::text) LIKE mayus_acentos('%".$pista."%')) ";

				            $Tfilas=$this->cepd03_ordenpago_cuerpo->findCount($condicion);
						        if($Tfilas!=0){
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->cepd03_ordenpago_cuerpo->findAll($condicion,null,"numero_orden_pago ASC",50,$pagina,null);
							        $this->set("datos",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						        }else{
						        	$this->set("datos",'');
						        }

$this->set("pista",$pista);



}//fin function


function v_reporte_emision_op_generico_3($var1=null){

    $this->layout="ajax";
    $this->Session->write('year_v_reporte_emision_op_generico_1', $var1);

}//fin function






















}//fin class

?>