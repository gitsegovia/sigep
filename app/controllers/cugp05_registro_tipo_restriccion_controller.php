<?php
class Cugp05RegistroTipoRestriccionController extends AppController{


 	var $uses = array('cnmd03_transacciones','cugd05_restriccion_tipo','cugd05_restriccion_clave');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
	//var $layout =  "administradors";
function checkSession()
    {
        // If the session info hasn't been set...
        if (!$this->Session->check('Usuario'))
        {
            // Force the user to login
            $this->redirect('/usuarios/');
            exit();
        }
    }//checkSession



	function beforeFilter(){

		$this->checkSession();

}//beforeFilter






 function zero($x=null){
	if($x != null){
		if($x<10){
			$x="0".$x;
		}else if($x>=10 && $x<=99){
			$x=$x;
		}
	}
	return $x;

}//fin zero





function concatena($vector1=null, $nomVar=null){

	if($vector1 != null){

		foreach($vector1 as $x => $y){

			$cod[$x] = $this->zero($x).' - '.$y;
		}

		$this->set($nomVar, $cod);

	}
}//fin concatena





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
$this->Session->delete('nomina');
//$this->Session->delete('radio');
	$cond= $this->SQLCA();

	$ano='';
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'';
 $year = $this->ccfd03_instalacion->findAll($condicion, null, 'ano_arranque ASC', null);
 $ano = null;
 foreach($year as $year){$ano = $year['ccfd03_instalacion']['ano_arranque'];}
 $this->set('ano',$ano);
$cond2="cod_tipo_transaccion=1";
//$this->set('grupo', $this->cnmd01->generateList($cond, 'cod_tipo_nomina ASC', null, '{n}.cnmd01.cod_tipo_nomina', '{n}.cnmd01.cod_tipo_nomina'));
//$this->set('tipo', $this->cnmd03_transacciones->generateList($cond2, 'cod_transaccion ASC', null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.cod_transaccion'));

$lista= $this->Cnmd01->generateList($cond, 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
$this->concatena($lista, 'lista');

//$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	//$this->concatena($lista, 'nomina');


$tipo=$this->cnmd03_transacciones->generateList($cond2, 'cod_transaccion ASC', null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
$this->concatena($tipo, 'tipo');
    }//fin index





}///FIN CONTROLADOR

?>