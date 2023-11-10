<?php

class VentanaInfoContabilidadController extends AppController
{
	var $name = "ventana_info_contabilidad";
    var $uses = array('Usuario', 'cugd04_entrada_modulo','modulos', "cugd02_dependencia", "cugd02_institucion", "arrd05",'ccfd10_descripcion','ccfd10_detalles');
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

	function index($dia=null, $mes=null, $ano=null, $numero=null){
	    $this->layout="ajax";
	    $cod_presi                =       $this->Session->read('SScodpresi');
		$cod_entidad              =       $this->Session->read('SScodentidad');
		$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
		$cod_inst                 =       $this->Session->read('SScodinst');
		$cod_dep                  =       $this->Session->read('SScoddep');
        $Modulo                   =       $this->Session->read('Modulo');
        $this->set('dia',$dia);
        $this->set('mes',$mes);
        $this->set('ano',$ano);
        $this->set('numero',$numero);
	    $this->set('entidad_federal', $this->Session->read('entidad_federal'));
        $this->set('cod_dep_session', $cod_dep);

        $consulta1 = "cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_asiento=".$ano." and mes_asiento=".$mes." and dia_asiento=".$dia." and numero_asiento=".$numero;
        $this->set('dep_sele', $cod_dep);
		$pagina=1;
		$Tfilas=$this->ccfd10_descripcion->findCount($consulta1);
        if($Tfilas!=0){
        	$data=$this->ccfd10_descripcion->findAll($consulta1,null,"cod_dep, ano_asiento, mes_asiento, dia_asiento, numero_asiento ASC",1,$pagina,null);
			$this->set('DATA',$data);
        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
        }
		$this->set('ano', $data[0]['ccfd10_descripcion']['ano_asiento']);
		$this->set('dia', $data[0]['ccfd10_descripcion']['dia_asiento']);
		$this->set('mes', $data[0]['ccfd10_descripcion']['mes_asiento']);
	    $this->set('numero_asiento', $data[0]['ccfd10_descripcion']['numero_asiento']);
	    $this->set('concepto', $data[0]['ccfd10_descripcion']['concepto']);
		$this->set('tipo_documento', $data[0]['ccfd10_descripcion']['tipo_documento']);
	    $this->set('numero_documento', $data[0]['ccfd10_descripcion']['numero_documento']);
	    $this->set('fecha', $data[0]['ccfd10_descripcion']['fecha_documento']);
        //$datos=$this->ccfd10_detalles->findAll($this->condicionNDEP()."  and cod_dep='".$data[0]['ccfd10_descripcion']['cod_dep']."' and ano_asiento=".$data[0]['ccfd10_descripcion']['ano_asiento']." and dia_asiento=".$data[0]['ccfd10_descripcion']['dia_asiento']." and mes_asiento=".$data[0]['ccfd10_descripcion']['mes_asiento']." and numero_asiento=".$data[0]['ccfd10_descripcion']['numero_asiento']." ",null,"cod_dep, ano_asiento, mes_asiento, dia_asiento, numero_asiento ASC",null,null,null);
        $datos=$this->ccfd10_detalles->findAll($consulta1,null,"cod_dep, ano_asiento, mes_asiento, dia_asiento, numero_asiento ASC",null,null,null);
        $this->set('datos', $datos);

	}//fin functions index






function info_motor_contabilidad($var_1=null, $var_2=null){

 $this->layout="ajax";

 $this->set('info_error', $var_1);
 $this->set('opcion',     $var_2);

}//fin function






}//class

?>