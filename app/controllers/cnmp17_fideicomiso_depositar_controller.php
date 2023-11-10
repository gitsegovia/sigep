<?php
 class Cnmp17FideicomisoDepositarController extends AppController{
	var $uses = array('cnmd17_fideicomiso_cuentas_bancarias','v_cnmp17_fideicomiso_tipo_nomina');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
 	var $name = "cnmp17_fideicomiso_depositar";

 	function checkSession(){
 	if (!$this->Session->check('Usuario')){
 		$this->redirect('/salir/');
		exit();
	}else{
		$this->requestAction('/usuarios/actualizar_user');
	}

 }//fin checkSession



 function beforeFilter(){
 	$this->checkSession();
 }//fin before filter

 function verifica_SS($i){
    	/**
    	 * Funcion que permite leer las varibles de session. la cual permite capturar los codigos del usuario
    	 * para ser insertados en todas las tablas.
    	 * */
    	switch ($i){
    		case 1:return $this->Session->read('SScodpresi');break;
    		case 2:return $this->Session->read('SScodentidad');break;
    		case 3:return $this->Session->read('SScodtipoinst');break;
    		case 4:return $this->Session->read('SScodinst');break;
    		case 5:return $this->Session->read('SScoddep');break;
    		case 6:return $this->Session->read('entidad_federal');break;
    		default:
    		   return "NULO";


    	}//fin switch
    }//fin verifica_SS

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



function codigo_nomina2($codigo=null){
	$this->layout = "ajax";

if($codigo!=null){
	$a = $this->v_cnmp17_fideicomiso_tipo_nomina->findAll($this->SQLCA()." and cod_tipo_nomina=".$codigo,array('cod_tipo_nomina','denominacion'));
        $this->Session->write('cod_tipo_nomina',$codigo);

	if($a!=null){
		echo "<script>
				document.getElementById('codigo_tipo_nom').value='".mascara($a[0]['v_cnmp17_fideicomiso_tipo_nomina']['cod_tipo_nomina'], 3)."';
				document.getElementById('denominacion_deno_nom').value='".$a[0]['v_cnmp17_fideicomiso_tipo_nomina']['denominacion']."';
                                $('Generar').disabled = false;
			</script>";
	}else{
		echo   "<script>
				document.getElementById('codigo_tipo_nom').value='';
				document.getElementById('denominacion_deno_nom').value='';
                                $('Generar').disabled = true;
			</script>";
	}
}else{
	$this->set('mensajeError','No llego el c&oacute;digo del tipo de n&oacute;mina para procesar - Seleccione N&oacute;mina');
	echo "<script>
			document.getElementById('codigo_tipo_nom').value='';
			document.getElementById('denominacion_deno_nom').value='';
                        $('Generar').disabled = true;
		</script>";
}
}//fin codigo_nomina2





function index_detallado(){
$this->layout ="ajax";
$cod_presi = $this->Session->read('SScodpresi');
$cod_entidad = $this->Session->read('SScodentidad');
$cod_tipo_inst = $this->Session->read('SScodtipoinst');
$cod_inst = $this->Session->read('SScodinst');
$cod_dep = $this->Session->read('SScoddep');
$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

   	$lista = $this->v_cnmp17_fideicomiso_tipo_nomina->generateList($this->SQLCA(), $order = 'cod_tipo_nomina', $limit = null, '{n}.v_cnmp17_fideicomiso_tipo_nomina.cod_tipo_nomina', '{n}.v_cnmp17_fideicomiso_tipo_nomina.denominacion');
	if(!empty($lista)){
		$this->concatenaN($lista, 'cod_tipo_nomina');
	}else{
		$this->set('lista_nomina', $lista);
	}
}//fin index detallado



function depositar_detallado(){
	$this->layout ="pdf";
	set_time_limit(0);
	ini_set("memory_limit","2048M");
$cod_presi = $this->Session->read('SScodpresi');
$cod_entidad = $this->Session->read('SScodentidad');
$cod_tipo_inst = $this->Session->read('SScodtipoinst');
$cod_inst = $this->Session->read('SScodinst');
$cod_dep = $this->Session->read('SScoddep');
$cod_tipo_nomina = $this->Session->read('cod_tipo_nomina');
$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

    $sql_busca_temporal = "SELECT * from v_cnmp17_fideicomiso_trimestral_temporal WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina";
    $sql_temporal = $this->cnmd17_fideicomiso_cuentas_bancarias->execute($sql_busca_temporal);
    if(!empty($sql_temporal)){
        $this->set('depositar_detallado', $sql_temporal);
        $this->set('titulo_reporte', 'FIDEICOMISO A DEPOSITAR DETALLADO');
    }else{
        $this->set('depositar_detallado', array());
        $this->set('titulo_reporte', 'FIDEICOMISO A DEPOSITAR DETALLADO');
    }
}//fin depositar_detallado


function index_resumido(){
$this->layout ="ajax";
$cod_presi = $this->Session->read('SScodpresi');
$cod_entidad = $this->Session->read('SScodentidad');
$cod_tipo_inst = $this->Session->read('SScodtipoinst');
$cod_inst = $this->Session->read('SScodinst');
$cod_dep = $this->Session->read('SScoddep');
$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

   	$lista = $this->v_cnmp17_fideicomiso_tipo_nomina->generateList($this->SQLCA(), $order = 'cod_tipo_nomina', $limit = null, '{n}.v_cnmp17_fideicomiso_tipo_nomina.cod_tipo_nomina', '{n}.v_cnmp17_fideicomiso_tipo_nomina.denominacion');
	if(!empty($lista)){
		$this->concatenaN($lista, 'cod_tipo_nomina');
	}else{
		$this->set('cod_tipo_nomina', $lista);
	}
}//fin index resumido



function depositar_resumido(){
$this->layout ="pdf";
	set_time_limit(0);
	ini_set("memory_limit","2048M");
$cod_presi = $this->Session->read('SScodpresi');
$cod_entidad = $this->Session->read('SScodentidad');
$cod_tipo_inst = $this->Session->read('SScodtipoinst');
$cod_inst = $this->Session->read('SScodinst');
$cod_dep = $this->Session->read('SScoddep');
$cod_tipo_nomina = $this->Session->read('cod_tipo_nomina');
$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

	$sql_busca_temporal = "SELECT * from v_cnmp17_fideicomiso_trimestral_temporal WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina";
    $sql_busca_temporal = $this->cnmd17_fideicomiso_cuentas_bancarias->execute($sql_busca_temporal);
    if(!empty($sql_busca_temporal)){
        $this->set('depositar_resumido', $sql_busca_temporal);
        $this->set('titulo_reporte', 'FIDEICOMISO A DEPOSITAR RESUMIDO');

    }else{
        $this->set('depositar_resumido', array());
        $this->set('titulo_reporte', 'FIDEICOMISO A DEPOSITAR RESUMIDO');
    }


}//fin depositar_resumido

}// FIN DE LA CLASE
