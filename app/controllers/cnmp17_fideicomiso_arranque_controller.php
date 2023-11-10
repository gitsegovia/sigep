<?php
 class Cnmp17FideicomisoArranqueController extends AppController{
	var $uses = array('cnmd17_fideicomiso_control_trimestre','v_cnmp17_fideicomiso_tipo_nomina');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
 	var $name = "cnmp17_fideicomiso_arranque";


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

echo "<script>
	document.getElementById('Guardar').disabled = true;
</script>";


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

$sql_ano_ejecucion = $this->cnmd17_fideicomiso_control_trimestre->execute("SELECT ano_arranque FROM ccfd03_instalacion WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_dep=$cod_dep");
 if (!empty($sql_ano_ejecucion)) {
     $ano = $sql_ano_ejecucion[0][0]['ano_arranque'];
    }
		$trimestre =1;

	$sql_control_permanente = $this->cnmd17_fideicomiso_control_trimestre->execute("select a.ano, a.trimestre from v_cnmp17_fideicomiso_control_perma a where ".$condicion.' and cod_tipo_nomina='.$co_nomi.' and ano='.$ano.' and trimestre='.$trimestre."; ");
  	$ano_perma = $sql_control_permanente[0][0]["ano"];
  	$trimestre_perma = $sql_control_permanente[0][0]["trimestre"];

if ($ano_perma==0){

		echo "<script>
				document.getElementById('ano').readOnly = false;
				document.getElementById('trimestre').disabled = false;
				document.getElementById('ano').value = '".$ano."';
				document.getElementById('trimestre').options[$trimestre].selected = true;
				document.getElementById('Guardar').disabled = false;
			</script>";
}else{

				echo "<script>
				document.getElementById('ano').readOnly = true;
				document.getElementById('trimestre').disabled = true;
				document.getElementById('ano').value = '".$ano."';
				document.getElementById('trimestre').options[$trimestre].selected = true;
				document.getElementById('Guardar').disabled = true;
			</script>";
				echo "<script> fun_msj('ESTA NÓMINA YA INICIO EL PROCESO DE CANCELACIÓN DE FIDEICOMISO');</script>";

}
$this->Session->write('cod_tipo_nomina',$co_nomi);

}// FIN select_ano_trimestre



function guardar(){
	$this->layout = "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
	$cod_tipo_nomina = $this->Session->read('cod_tipo_nomina');

	if(empty($this->data['cnmd17_fideicomiso_cuentas_bancarias']['ano']) && empty($this->data['cnmd17_fideicomiso_cuentas_bancarias']['trimestre']) ){
		$this->set('errorMessage', 'Debe seleccionar el año y trimestre');
	}else{
		$ano=$this->data['cnmd17_fideicomiso_cuentas_bancarias']['ano'];
		$trimestre=$this->data['cnmd17_fideicomiso_cuentas_bancarias']['trimestre'];

		$sql_control_permanente = $this->cnmd17_fideicomiso_control_trimestre->execute("select a.ano, a.trimestre from v_cnmp17_fideicomiso_control_perma a where ".$condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and ano='.$ano.' and trimestre='.$trimestre."; ");
  		$ano_perma = $sql_control_permanente[0][0]["ano"];
  		$trimestre_perma = $sql_control_permanente[0][0]["trimestre"];
	if ($ano_perma==0){

			$sql_cuenta_control_trimestre = $this->cnmd17_fideicomiso_control_trimestre->execute("select count(*) as cuenta from cnmd17_fideicomiso_control_trimestre a where ".$condicion.' and cod_tipo_nomina='.$cod_tipo_nomina."; ");
		if (!empty($sql_cuenta_control_trimestre)){
			$sql_delete_control_trimestre = $this->cnmd17_fideicomiso_control_trimestre->execute("DELETE from cnmd17_fideicomiso_control_trimestre a where ".$condicion.' and cod_tipo_nomina='.$cod_tipo_nomina."; ");
		}

			$insert="INSERT INTO cnmd17_fideicomiso_control_trimestre VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep','$cod_tipo_nomina', '$ano','$trimestre')";
			$sw=$this->cnmd17_fideicomiso_control_trimestre->execute($insert);
			if($sw>0){
				$this->cnmd17_fideicomiso_control_trimestre->execute('COMMIT');
				$this->set('Message_existe', 'registro exitoso');
				$this->set('guardado', 'si');
			}else{
				$this->cnmd17_fideicomiso_control_trimestre->execute('ROLLBACK');
				$this->set('errorMessage', 'no se pudo registrar');
				$this->set('guardado', 'no');
			}
		}else{
			$this->set('errorMessage', 'El año ya se encuentra registrado');

		}
	}// FIN EMPTY

}// FUNCION GUARDAR


}// FIN DE LA CLASE
