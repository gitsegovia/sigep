<?php
 class Cnmp17FideicomisoGenerarTxtController extends AppController{
	var $uses = array('cnmd17_fideicomiso_cuentas_bancarias', 'v_cnmp17_fideicomiso_tipo_nomina');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
 	var $name = "cnmp17_fideicomiso_generar_txt";


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



function index(){
$this->layout = "ajax";

/*
echo "<script>
	document.getElementById('Guardar').disabled = false;
</script>";
*/

/*
	$anoe = $this->ano_ejecucion();
	if($anoe!=null)
		$this->set('anoe', $anoe);
	else
		$this->set('anoe', date("Y"));
*/

   	$lista = $this->v_cnmp17_fideicomiso_tipo_nomina->generateList($this->SQLCA(), $order = 'cod_tipo_nomina', $limit = null, '{n}.v_cnmp17_fideicomiso_tipo_nomina.cod_tipo_nomina', '{n}.v_cnmp17_fideicomiso_tipo_nomina.denominacion');
	if(!empty($lista)){
		$this->concatenaN($lista, 'cod_tipo_nomina');
	}else{
		$this->set('cod_tipo_nomina', array());
	}

}//fin index




function select_ano_trimestre($co_nomi=null){
$this->layout ="ajax";
$cod_presi = $this->Session->read('SScodpresi');
$cod_entidad = $this->Session->read('SScodentidad');
$cod_tipo_inst = $this->Session->read('SScodtipoinst');
$cod_inst = $this->Session->read('SScodinst');
$cod_dep = $this->Session->read('SScoddep');
$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

$sql_ano_ejecucion = $this->cnmd17_fideicomiso_cuentas_bancarias->execute("SELECT ano_arranque FROM ccfd03_instalacion WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep");
 if (!empty($sql_ano_ejecucion)){
     $ano = $sql_ano_ejecucion[0][0]['ano_arranque'];
    }
	 $trimestre =1;

	$sql_control_permanente = $this->cnmd17_fideicomiso_cuentas_bancarias->execute("SELECT a.ano, a.trimestre from cnmd17_fideicomiso_trimestral_temporal a where ".$condicion.' and cod_tipo_nomina='.$co_nomi." order by ano DESC, trimestre DESC; ");
  	$ano_perma = $sql_control_permanente[0][0]["ano"];
  	$trimestre_perma = $sql_control_permanente[0][0]["trimestre"];

if ($ano_perma==0){

		echo "<script>
				document.getElementById('Guardar').disabled = false;
				document.getElementById('procesar_tx').disabled = false;
				document.getElementById('ano').readOnly = false;
				document.getElementById('trimestre').disabled = false;
				document.getElementById('ano').value = '".$ano."';
				document.getElementById('trimestre').options[$trimestre].selected = true;
				</script>";
}else{
	$ano=$ano_perma;
	$trimestre=$trimestre_perma;

				echo "<script>
				document.getElementById('Guardar').disabled = false;
				document.getElementById('procesar_tx').disabled = false;
				document.getElementById('ano').readOnly = true;
				document.getElementById('trimestre').disabled = true;
				document.getElementById('ano').value = '".$ano."';
				document.getElementById('trimestre').options[$trimestre].selected = true;
			</script>";

}
$this->Session->write('cod_tipo_nomina',$co_nomi);

}// FIN select_ano_trimestre



function generar_txt($cod_tipo_nomina = null, $ano = null, $trimestre = null){
	$this->layout = "txt";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
	$cod_tipo_nomina = $this->Session->read('cod_tipo_nomina');

	if($cod_tipo_nomina != null && $ano != null && $trimestre != null){

	}else{
		$cod_tipo_nomina=$this->data['cnmd17_fideicomiso_cuentas_bancarias']['cod_tipo_nomina'];
		$ano=$this->data['cnmd17_fideicomiso_cuentas_bancarias']['ano'];
		$trimestre=$this->data['cnmd17_fideicomiso_cuentas_bancarias']['trimestre'];
	}
	$sql_busca_temporal = "SELECT * from v_cnmp17_fideicomiso_trimestral_temporal WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and ano=$ano and trimestre=$trimestre";
    $sql_temporal = $this->cnmd17_fideicomiso_cuentas_bancarias->execute($sql_busca_temporal);
	$sql_temporal_veri=$sql_temporal;
		if ($sql_temporal==null){
	$sql_busca_permanente = "SELECT * from v_cnmp17_fideicomiso_trimestral_perma WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and ano=$ano and trimestre=$trimestre";
    $sql_temporal = $this->cnmd17_fideicomiso_cuentas_bancarias->execute($sql_busca_permanente);
    $sql_perma_veri=$sql_temporal;
		}
    		if ($sql_temporal_veri==null && $sql_perma_veri==null){

				$nombre_archivo = 'Fideicomiso_'.mascara($cod_dep,4).'_'.mascara($cod_tipo_nomina,3).'_'.date('d_m_Y_h:i:sa').'';
				$_SESSION["nombre_txt"] = $nombre_archivo.".txt";
				$filas_archivo = "";
				$this->wFile($nombre_archivo, $filas_archivo);
				$this->set('filas_archivo', "!!!NO PUEDE GENERAR EL ARCHIVO TXT!!! TRIMESTRE NO FUE PROCESADO");

    		}else{


	$nombre_archivo = 'Fideicomiso_'.mascara($cod_dep,4).'_'.mascara($cod_tipo_nomina,3).'_'.date('d_m_Y_h:i:sa').'';
	$_SESSION["nombre_txt"] = $nombre_archivo.".txt";
	$filas_archivo = "";

				foreach($sql_temporal as $temporal){
					$campo = array();
					$campo[] = $temporal[0]['nacionalidad'];
    				$campo[] = $temporal[0]['cedula_identidad'];
    				$campo[] = str_replace("\t",'',trim($temporal[0]['primer_nombre']));
    				$campo[] = str_replace("\t",'',trim($temporal[0]['segundo_nombre']));
    				$campo[] = str_replace("\t",'',trim($temporal[0]['primer_apellido']));
    				$campo[] = str_replace("\t",'',trim($temporal[0]['segundo_apellido']));
    				$campo[] = '2';
    				$campo[] = $temporal[0]['monto_fideicomiso'];
    				$campos = implode(',',$campo);
    				$filas_archivo .= $campos."\n";
    			}// FIN FOREACH


		$this->wFile($nombre_archivo, $filas_archivo);
		$this->set('filas_archivo', $filas_archivo);

    	}//CONDICION

}// FUNCION GENERAR_TXT




function generar_xls () {
	$this->layout = "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
	$cod_tipo_nomina = $this->Session->read('cod_tipo_nomina');

	$cod_tipo_nomina=$this->data['cnmd17_fideicomiso_cuentas_bancarias']['cod_tipo_nomina'];
	$ano=$this->data['cnmd17_fideicomiso_cuentas_bancarias']['ano'];
	$trimestre=$this->data['cnmd17_fideicomiso_cuentas_bancarias']['trimestre'];

	$sql_busca_temporal = "SELECT * from v_cnmp17_fideicomiso_trimestral_temporal WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and ano=$ano and trimestre=$trimestre";
    $sql_temporal = $this->cnmd17_fideicomiso_cuentas_bancarias->execute($sql_busca_temporal);
	$sql_temporal_veri=$sql_temporal;
	if ($sql_temporal==null){
		$sql_busca_permanente = "SELECT * from v_cnmp17_fideicomiso_trimestral_perma WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina and ano=$ano and trimestre=$trimestre";
    	$sql_temporal = $this->cnmd17_fideicomiso_cuentas_bancarias->execute($sql_busca_permanente);
    	$sql_perma_veri=$sql_temporal;
	}
    		if ($sql_temporal_veri==null && $sql_perma_veri==null){

				$this->set('msj',"!!!NO PUEDE GENERAR EL ARCHIVO EXCEL!!! TRIMESTRE NO FUE PROCESADO");

    		}else{

				$this->set('DATA',$sql_temporal);

    	}//CONDICION
}//fin funcion generar_xls

}// FIN DE LA CLASE
