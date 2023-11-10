<?php

class SelectVentasDependenciaController extends AppController
{
	var $name = "select_ventas_dependencia";
    var $uses = array('Usuario', 'cugd04_entrada_modulo','modulos', "cugd02_dependencia", "cugd02_institucion", "arrd05");
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








function index($var1=null, $var2=null){

$this->layout="ajax";
    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');




$url                  =  "/select_ventas_dependencia/cargar_sesion_1";
$width_aux            =  "750px";
$height_aux           =  "400px";
$title_aux            =  "Buscar";
$resizable_aux        =  false;
$maximizable_aux      =  false;
$minimizable_aux      =  false;
$closable_aux         =  false;

$this->Session->write('cod_dep_reporte_consolidado', $cod_dep);
$this->Session->write('consolidado_select_ventas_dependencia', $var1);
	
	
		if($var1==2 || $var1==3 && $var2 == 'reporte_balance_ejecucion'){
	         echo"<script>";
	           echo  "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
	         echo"</script>";
    	}else{
              echo"<script>";
               echo  " Windows.close(document.getElementById('capa_ventana').value)";
              echo"</script>";
		}//fin else


}//fin functions




function cargar_sesion_1($pagina=null){

    $this->layout="ajax";


    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');

    $this->set("datos",'');


}//fin functions







function cargar_sesion_2($pagina=null, $pista=null){


	$this->layout="ajax";

	            if(isset($pagina)){
					$pagina=$pagina;
				}else{
						 $pagina=1;
				}//fin else

    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');


if($pista!=null){
	  $this->Session->write('pista', $pista);
}else{
      $pista = $this->Session->read('pista');
}//fin else





$condicion = "cod_tipo_institucion='".$cod_tipo_inst."' and cod_institucion ='".$cod_inst."' and  upper(denominacion) LIKE upper('%".$pista."%') ";


				            $Tfilas=$this->cugd02_dependencia->findCount($condicion);
						        if($Tfilas!=0){
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->cugd02_dependencia->findAll($condicion,null,"cod_dependencia ASC",50,$pagina,null);
							        $this->set("datos",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						        }else{
						        	$this->set("datos",'');
						        }

$this->set("pista",$pista);

}//fin functions









function cargar_sesion($var=null){

	 $this->layout="ajax";

	$cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');


     $this->Session->write('cod_dep_reporte_consolidado', $var);



                    $a =" and cod_tipo_inst=".$cod_tipo_inst;
   	             	$a.=" and cod_inst=".$cod_inst;
   	             	if($this->cugd02_institucion->findCount("cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cod_inst."") != 0){
                		$v=$this->cugd02_institucion->findAll("cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cod_inst."",'denominacion','cod_tipo_institucion ASC',1,1,null);
                		$v[0]['cugd02_institucion']['denominacion']!=null && $v[0]['cugd02_institucion']['denominacion']!='' ? $this->Session->write('entidad_federal_reporte_consolidado', $v[0]['cugd02_institucion']['denominacion']) : $this->Session->write('entidad_federal_reporte_consolidado', '');
                	}//fin if

                	$this->arrd05->recursive=0;
                	 if($this->arrd05->findCount("arrd05.cod_presi=".$cod_presi." and arrd05.cod_entidad=".$cod_entidad." and arrd05.cod_tipo_inst=".$cod_tipo_inst." and arrd05.cod_inst=".$cod_inst." and arrd05.cod_dep=".$var."") != 0){
 	                	$v=$this->arrd05->findAll("arrd05.cod_presi=".$cod_presi." and arrd05.cod_entidad=".$cod_entidad." and arrd05.cod_tipo_inst=".$cod_tipo_inst." and arrd05.cod_inst=".$cod_inst." and arrd05.cod_dep=".$var."",'denominacion','cod_dep ASC',1,1,null);
	                	$v[0]['arrd05']['denominacion']!=null && $v[0]['arrd05']['denominacion']!='' ? $this->Session->write('dependencia_reporte_consolidado', $v[0]['arrd05']['denominacion']) : $this->Session->write('dependencia_reporte_consolidado', '');
                	 }//fin if




}//fin function








}//fin class

?>