<?php
/*
 * Creado el 19/10/2007 a las 10:03:41 AM por migue
 * Para CakePHP, PostgresSQL
 */
 class Cfpp07clasificacionrecursoController extends AppController {
   var $name = 'cfpp07_clasificacion_recurso';
   var $uses = array('cfpd07_clasificacion_recurso','usuario', 'ccfd04_cierre_mes');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap');

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
 	 echo'				<script>
							 document.getElementById("valida_codigo").innerHTML = "";
							 document.getElementById("valida_codigo").style.display = "none";
							 if(document.getElementById("registro")){document.getElementById("registro").style.visibility = "hidden";}
                             if(document.getElementById("nomina")){document.getElementById("nomina").style.visibility = "hidden";}
                             if(document.getElementById("dependencia")){document.getElementById("dependencia").style.visibility = "hidden";}
                             if(document.getElementById("sistema_general")){document.getElementById("sistema_general").style.visibility = "hidden";}
                         </script>';

 }


 function concatena_superior($vector1=null, $nomVar=null, $extra=null){

	if($vector1 != null){

		foreach($vector1 as $x => $y){


			if($extra!=null){

             $cod[$x] = $this->zero($x).' - '.$y;

			}else{

             $cod[$x] = $this->zero($x).' - '.$y;

			}
		}
		$this->set($nomVar, $cod);
	}
}


 function index(){
 	$this->layout ="ajax";
 	$this->Session->delete('var');
 	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$this->set('opc', '1');
	$ano=$this->ano_ejecucion();
	$this->set('ANO',$ano);
 	$var1=$this->Session->read('SScodpresi');
 	$var2=$this->Session->read('SScodentidad');
 	$var3=$this->Session->read('SScodtipoinst');
 	$var4=$this->Session->read('SScodinst');
 	$this->set('tipo',Array());
 	$this->set('enable', 'disabled');

 }

 function selec_tipo($var = null){
 	$this->layout ="ajax";
 	$this->set('action', $var);
 	$this->set('var',$this->Session->read('var'));
	$ano=$this->ano_ejecucion();
	$this->set('ANO',$ano);
 	$var1=$this->Session->read('SScodpresi');
 	$var2=$this->Session->read('SScodentidad');
 	$var3=$this->Session->read('SScodtipoinst');
 	$var4=$this->Session->read('SScodinst');
 	$this->set('tipo',Array());
 	$v=$this->Session->read('var');
 	$this->concatena($this->cfpd07_clasificacion_recurso->generateList('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.' and tipo_recurso='.$v.'',' clasificacion_recurso ASC', null, '{n}.cfpd07_clasificacion_recurso.clasificacion_recurso', '{n}.cfpd07_clasificacion_recurso.denominacion'), 'tipo');

 	if($var != 'otros'){
 	 /* if($lista!=null){
 		$this->set('tipo',$lista);
 	}else{
 		$this->set('tipo',array(''=>''));
 	}*/

		$this->set('datos', $this->cfpd07_clasificacion_recurso->findAll('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.' and clasificacion_recurso = '.$var.' and tipo_recurso='.$v));

 	}else{

        $datos = $this->cfpd07_clasificacion_recurso->findAll('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.' and tipo_recurso='.$v, null, " clasificacion_recurso DESC ");
        $this->set('codigo', isset($datos[0]["cfpd07_clasificacion_recurso"]["clasificacion_recurso"])?$datos[0]["cfpd07_clasificacion_recurso"]["clasificacion_recurso"]+1:1);
 		$this->data['cfpp07_clasificacion_recurso'] = array();
// 		$this->set('mensaje', 'POR FAVOR INGRESE EL CÓDIGO');
 	}
	$this->set('enable', 'disabled');

 }

 function radio($var = null){
 	$this->layout ="ajax";
 	$this->set('action', $var);
 	$this->set('var', $var);
	$ano=$this->ano_ejecucion();
	$this->set('ANO',$ano);
 	$var1=$this->Session->read('SScodpresi');
 	$var2=$this->Session->read('SScodentidad');
 	$var3=$this->Session->read('SScodtipoinst');
 	$var4=$this->Session->read('SScodinst');
 	$this->set('mensaje', 'POR FAVOR INGRESE EL CODIGO');
	$this->set('datos', array());
	$this->set('tipo', array());

 	if($var==1){
 		$this->set('var','1');
 		$this->concatena_superior($this->cfpd07_clasificacion_recurso->generateList('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.' and tipo_recurso='.$var.'',' clasificacion_recurso ASC', null, '{n}.cfpd07_clasificacion_recurso.clasificacion_recurso', '{n}.cfpd07_clasificacion_recurso.denominacion'), 'tipo','1');
 		//$this->set('datos', $this->cfpd07_clasificacion_recurso->findAll('tipo_recurso = '.$var));
 	}
 	if($var==2){
 		$this->set('var','2');
 		$this->concatena_superior($this->cfpd07_clasificacion_recurso->generateList('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.' and tipo_recurso='.$var.'',' clasificacion_recurso ASC', null, '{n}.cfpd07_clasificacion_recurso.clasificacion_recurso', '{n}.cfpd07_clasificacion_recurso.denominacion'), 'tipo','2');
 		//$this->set('datos', $this->cfpd07_clasificacion_recurso->findAll('tipo_recurso = '.$var));
 	}
 	if($var==3){
 		$this->set('var','3');
 		$this->concatena_superior($this->cfpd07_clasificacion_recurso->generateList('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.' and tipo_recurso='.$var.'',' clasificacion_recurso ASC', null, '{n}.cfpd07_clasificacion_recurso.clasificacion_recurso', '{n}.cfpd07_clasificacion_recurso.denominacion'), 'tipo','3');
 		//$this->set('datos', $this->cfpd07_clasificacion_recurso->findAll('tipo_recurso = '.$var));
 	}
 	if($var==4){
 		$this->set('var','4');
 		$this->concatena_superior($this->cfpd07_clasificacion_recurso->generateList('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.' and tipo_recurso='.$var.'',' clasificacion_recurso ASC', null, '{n}.cfpd07_clasificacion_recurso.clasificacion_recurso', '{n}.cfpd07_clasificacion_recurso.denominacion'), 'tipo','4');
 		//$this->set('datos', $this->cfpd07_clasificacion_recurso->findAll('tipo_recurso = '.$var));
 	}
 	if($var==5){
 		$this->set('var','5');
 		$this->concatena_superior($this->cfpd07_clasificacion_recurso->generateList('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.' and tipo_recurso='.$var.'',' clasificacion_recurso ASC', null, '{n}.cfpd07_clasificacion_recurso.clasificacion_recurso', '{n}.cfpd07_clasificacion_recurso.denominacion'), 'tipo','5');
 		//$this->set('datos', $this->cfpd07_clasificacion_recurso->findAll('tipo_recurso = '.$var));
 	}
 	if($var==6){
 		$this->set('var','6');
 		$this->concatena_superior($this->cfpd07_clasificacion_recurso->generateList('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.' and tipo_recurso='.$var.'',' clasificacion_recurso ASC', null, '{n}.cfpd07_clasificacion_recurso.clasificacion_recurso', '{n}.cfpd07_clasificacion_recurso.denominacion'), 'tipo','6');
 		//$this->set('datos', $this->cfpd07_clasificacion_recurso->findAll('tipo_recurso = '.$var));
 	}
 	if($var==7){
 		$this->set('var','7');
 		$this->concatena_superior($this->cfpd07_clasificacion_recurso->generateList('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.' and tipo_recurso='.$var.'',' clasificacion_recurso ASC', null, '{n}.cfpd07_clasificacion_recurso.clasificacion_recurso', '{n}.cfpd07_clasificacion_recurso.denominacion'), 'tipo','7');
 		//$this->set('datos', $this->cfpd07_clasificacion_recurso->findAll('tipo_recurso = '.$var));
 	}
 	if($var==8){
 		$this->set('var','8');
 		$this->concatena_superior($this->cfpd07_clasificacion_recurso->generateList('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.' and tipo_recurso='.$var.'',' clasificacion_recurso ASC', null, '{n}.cfpd07_clasificacion_recurso.clasificacion_recurso', '{n}.cfpd07_clasificacion_recurso.denominacion'), 'tipo','8');
 		//$this->set('datos', $this->cfpd07_clasificacion_recurso->findAll('tipo_recurso = '.$var));
 	}
 	$this->Session->write('var',$var);
	$this->set('enable', 'disabled');
 }





function guardar_segunda_accion($var1_aux=null){

$this->layout ="ajax";

    $var1=$this->Session->read('SScodpresi');
 	$var2=$this->Session->read('SScodentidad');
 	$var3=$this->Session->read('SScodtipoinst');
 	$var4=$this->Session->read('SScodinst');
 	$this->concatena($this->cfpd07_clasificacion_recurso->generateList('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.' and tipo_recurso='.$var1_aux.'',' clasificacion_recurso ASC', null, '{n}.cfpd07_clasificacion_recurso.clasificacion_recurso', '{n}.cfpd07_clasificacion_recurso.denominacion'), 'tipo');

}//fin fucntion









 function guardar($var=null){

 	$this->layout ="ajax";
	$var=$var;
 	if(!empty($this->data['cfpp07_clasificacion_recurso'])){


		$tipo_recurso = $this->data['cfpp07_clasificacion_recurso']['tipo_recurso'];
		$clasificacion_recurso = $this->data['cfpp07_clasificacion_recurso']['clasificacion_recurso'];
		$denominacion = $this->data['cfpp07_clasificacion_recurso']['denominacion'];
		$var1=$this->Session->read('SScodpresi');
 		$var2=$this->Session->read('SScodentidad');
 		$var3=$this->Session->read('SScodtipoinst');
 		$var4=$this->Session->read('SScodinst');
		//echo "clasi ".$clasificacion_recurso;
		$sql = "INSERT INTO cfpd07_clasificacion_recurso VALUES('$var1','$var2','$var3','$var4','$tipo_recurso',$clasificacion_recurso, '$denominacion')";

		$consulta="select clasificacion_recurso from cfpd07_clasificacion_recurso where ".$this->condicionNDEP()." and tipo_recurso=$tipo_recurso and  clasificacion_recurso=$clasificacion_recurso";
		if($this->cfpd07_clasificacion_recurso->execute($consulta)){
			$this->set('mensajeError', 'el codigo de clasificacion '.$clasificacion_recurso.' ya existe, Introduzca uno diferente');
			//$this->concatena($this->cfpd07_clasificacion_recurso->generateList('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.'',' clasificacion_recurso ASC', null, '{n}.cfpd07_clasificacion_recurso.clasificacion_recurso', '{n}.cfpd07_clasificacion_recurso.denominacion'), 'tipo');
	if($tipo_recurso==1){
 	$this->set('var','1');
 	$this->concatena_superior($this->cfpd07_clasificacion_recurso->generateList('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.' and tipo_recurso='.$tipo_recurso.'',' clasificacion_recurso ASC', null, '{n}.cfpd07_clasificacion_recurso.clasificacion_recurso', '{n}.cfpd07_clasificacion_recurso.denominacion'), 'tipo','1');
 	//$this->set('datos', $this->cfpd07_clasificacion_recurso->findAll('tipo_recurso = '.$var));
 	}
 	if($tipo_recurso==2){
 		$this->set('var','2');
 		$this->concatena_superior($this->cfpd07_clasificacion_recurso->generateList('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.' and tipo_recurso='.$tipo_recurso.'',' clasificacion_recurso ASC', null, '{n}.cfpd07_clasificacion_recurso.clasificacion_recurso', '{n}.cfpd07_clasificacion_recurso.denominacion'), 'tipo','2');
 		//$this->set('datos', $this->cfpd07_clasificacion_recurso->findAll('tipo_recurso = '.$var));
 	}
 	if($tipo_recurso==3){
 		$this->set('var','3');
 		$this->concatena_superior($this->cfpd07_clasificacion_recurso->generateList('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.' and tipo_recurso='.$tipo_recurso.'',' clasificacion_recurso ASC', null, '{n}.cfpd07_clasificacion_recurso.clasificacion_recurso', '{n}.cfpd07_clasificacion_recurso.denominacion'), 'tipo','3');
 		//$this->set('datos', $this->cfpd07_clasificacion_recurso->findAll('tipo_recurso = '.$var));
 	}
 	if($tipo_recurso==4){
 		$this->set('var','4');
 		$this->concatena_superior($this->cfpd07_clasificacion_recurso->generateList('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.' and tipo_recurso='.$tipo_recurso.'',' clasificacion_recurso ASC', null, '{n}.cfpd07_clasificacion_recurso.clasificacion_recurso', '{n}.cfpd07_clasificacion_recurso.denominacion'), 'tipo','4');
 		//$this->set('datos', $this->cfpd07_clasificacion_recurso->findAll('tipo_recurso = '.$var));
 	}
 	if($tipo_recurso==5){
 		$this->set('var','5');
 		$this->concatena_superior($this->cfpd07_clasificacion_recurso->generateList('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.' and tipo_recurso='.$tipo_recurso.'',' clasificacion_recurso ASC', null, '{n}.cfpd07_clasificacion_recurso.clasificacion_recurso', '{n}.cfpd07_clasificacion_recurso.denominacion'), 'tipo','5');
 		//$this->set('datos', $this->cfpd07_clasificacion_recurso->findAll('tipo_recurso = '.$var));
 	}
 	if($tipo_recurso==6){
 		$this->set('var','6');
 		$this->concatena_superior($this->cfpd07_clasificacion_recurso->generateList('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.' and tipo_recurso='.$tipo_recurso.'',' clasificacion_recurso ASC', null, '{n}.cfpd07_clasificacion_recurso.clasificacion_recurso', '{n}.cfpd07_clasificacion_recurso.denominacion'), 'tipo','6');
 		//$this->set('datos', $this->cfpd07_clasificacion_recurso->findAll('tipo_recurso = '.$var));
 	}
			$this->set('existe',true);
		}else{
			if($this->cfpd07_clasificacion_recurso->execute($sql)>1){
			$this->set('datos', $this->cfpd07_clasificacion_recurso->findAll('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.' and tipo_recurso='.$tipo_recurso.' and clasificacion_recurso = '.$clasificacion_recurso));
			//$this->concatena($this->cfpd07_clasificacion_recurso->generateList('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.'',' clasificacion_recurso ASC', null, '{n}.cfpd07_clasificacion_recurso.clasificacion_recurso', '{n}.cfpd07_clasificacion_recurso.denominacion'), 'tipo');
			if($tipo_recurso==1){
 		$this->set('var','1');
 		$this->concatena_superior($this->cfpd07_clasificacion_recurso->generateList('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.' and tipo_recurso='.$tipo_recurso.'',' clasificacion_recurso ASC', null, '{n}.cfpd07_clasificacion_recurso.clasificacion_recurso', '{n}.cfpd07_clasificacion_recurso.denominacion'), 'tipo','1');
 		//$this->set('datos', $this->cfpd07_clasificacion_recurso->findAll('tipo_recurso = '.$var));
 	}
 	if($tipo_recurso==2){
 		$this->set('var','2');
 		$this->concatena_superior($this->cfpd07_clasificacion_recurso->generateList('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.' and tipo_recurso='.$tipo_recurso.'',' clasificacion_recurso ASC', null, '{n}.cfpd07_clasificacion_recurso.clasificacion_recurso', '{n}.cfpd07_clasificacion_recurso.denominacion'), 'tipo','2');
 		//$this->set('datos', $this->cfpd07_clasificacion_recurso->findAll('tipo_recurso = '.$var));
 	}
 	if($tipo_recurso==3){
 		$this->set('var','3');
 		$this->concatena_superior($this->cfpd07_clasificacion_recurso->generateList('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.' and tipo_recurso='.$tipo_recurso.'',' clasificacion_recurso ASC', null, '{n}.cfpd07_clasificacion_recurso.clasificacion_recurso', '{n}.cfpd07_clasificacion_recurso.denominacion'), 'tipo','3');
 		//$this->set('datos', $this->cfpd07_clasificacion_recurso->findAll('tipo_recurso = '.$var));
 	}
 	if($tipo_recurso==4){
 		$this->set('var','4');
 		$this->concatena_superior($this->cfpd07_clasificacion_recurso->generateList('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.' and tipo_recurso='.$tipo_recurso.'',' clasificacion_recurso ASC', null, '{n}.cfpd07_clasificacion_recurso.clasificacion_recurso', '{n}.cfpd07_clasificacion_recurso.denominacion'), 'tipo','4');
 		//$this->set('datos', $this->cfpd07_clasificacion_recurso->findAll('tipo_recurso = '.$var));
 	}
 	if($tipo_recurso==5){
 		$this->set('var','5');
 		$this->concatena_superior($this->cfpd07_clasificacion_recurso->generateList('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.' and tipo_recurso='.$tipo_recurso.'',' clasificacion_recurso ASC', null, '{n}.cfpd07_clasificacion_recurso.clasificacion_recurso', '{n}.cfpd07_clasificacion_recurso.denominacion'), 'tipo','5');
 		//$this->set('datos', $this->cfpd07_clasificacion_recurso->findAll('tipo_recurso = '.$var));
 	}
 	if($tipo_recurso==6){
 		$this->set('var','6');
 		$this->concatena_superior($this->cfpd07_clasificacion_recurso->generateList('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.' and tipo_recurso='.$tipo_recurso.'',' clasificacion_recurso ASC', null, '{n}.cfpd07_clasificacion_recurso.clasificacion_recurso', '{n}.cfpd07_clasificacion_recurso.denominacion'), 'tipo','6');
 		//$this->set('datos', $this->cfpd07_clasificacion_recurso->findAll('tipo_recurso = '.$var));
 	}
 	if($tipo_recurso==7){
 		$this->set('var','7');
 		$this->concatena_superior($this->cfpd07_clasificacion_recurso->generateList('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.' and tipo_recurso='.$tipo_recurso.'',' clasificacion_recurso ASC', null, '{n}.cfpd07_clasificacion_recurso.clasificacion_recurso', '{n}.cfpd07_clasificacion_recurso.denominacion'), 'tipo','7');
 		//$this->set('datos', $this->cfpd07_clasificacion_recurso->findAll('tipo_recurso = '.$var));
 	}
 	if($tipo_recurso==8){
 		$this->set('var','8');
 		$this->concatena_superior($this->cfpd07_clasificacion_recurso->generateList('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.' and tipo_recurso='.$tipo_recurso.'',' clasificacion_recurso ASC', null, '{n}.cfpd07_clasificacion_recurso.clasificacion_recurso', '{n}.cfpd07_clasificacion_recurso.denominacion'), 'tipo','8');
 		//$this->set('datos', $this->cfpd07_clasificacion_recurso->findAll('tipo_recurso = '.$var));
 	}
			$this->set('mensaje', 'EL DATO FUE GUARDADO CORRECTAMENTE');
			$this->set('clasificacion_recurso',$clasificacion_recurso);
			$this->set('denominacion',$denominacion);
			$this->set('tipo_recurso',$tipo_recurso);
			$datos = $this->cfpd07_clasificacion_recurso->findAll('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.' and tipo_recurso='.$tipo_recurso, null, " clasificacion_recurso DESC ");
			$this->set('codigo', isset($datos[0]["cfpd07_clasificacion_recurso"]["clasificacion_recurso"])?$datos[0]["cfpd07_clasificacion_recurso"]["clasificacion_recurso"]+1:1);
			$this->set('existe',false);
			//$this->render('index');
			$this->set('enable', 'disabled');
			}else{
 			$this->set('mensajeError', 'el dato no fue guardado');
 			}
		}


 }// fin if
 	}//fin fucntion

 function editar($tipo_recurso=null, $clasificacion_recurso = null){
 	$this->layout ="ajax";
	$ano=$this->ano_ejecucion();
	$this->set('ANO',$ano);
 	$var1=$this->Session->read('SScodpresi');
 	$var2=$this->Session->read('SScodentidad');
 	$var3=$this->Session->read('SScodtipoinst');
 	$var4=$this->Session->read('SScodinst');
 	$this->set('datos', $this->cfpd07_clasificacion_recurso->findAll($this->condicionNDEP().' and tipo_recurso='.$tipo_recurso.' and clasificacion_recurso = '.$clasificacion_recurso));
	$this->concatena($this->cfpd07_clasificacion_recurso->generateList('cod_presi='.$var1.' and tipo_recurso='.$tipo_recurso.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.'',' clasificacion_recurso ASC', null, '{n}.cfpd07_clasificacion_recurso.clasificacion_recurso', '{n}.cfpd07_clasificacion_recurso.denominacion'), 'tipo');
 	$this->cfpd07_clasificacion_recurso->findAll($this->condicionNDEP().' and tipo_recurso='.$tipo_recurso.' and clasificacion_recurso = '.$clasificacion_recurso);
 	$tipo_recurso = $this->data['cfpp07_clasificacion_recurso']['tipo_recurso'];
	$clasificacion_recurso = $this->data['cfpp07_clasificacion_recurso']['clasificacion_recurso'];
	$denominacion = $this->data['cfpp07_clasificacion_recurso']['denominacion'];
 	$this->set('clasificacion_recurso',$clasificacion_recurso);
	$this->set('denominacion',$denominacion);
	$this->set('tipo_recurso',$tipo_recurso);
 }




 function editar2($pagina=null, $tipo_recurso=null, $clasificacion_recurso = null){
 	$this->layout ="ajax";
	$ano=$this->ano_ejecucion();
	$this->set('ANO',$ano);
 	$var1=$this->Session->read('SScodpresi');
 	$var2=$this->Session->read('SScodentidad');
 	$var3=$this->Session->read('SScodtipoinst');
 	$var4=$this->Session->read('SScodinst');
 	$this->set('datos', $this->cfpd07_clasificacion_recurso->findAll($this->condicionNDEP().' and tipo_recurso='.$tipo_recurso.' and clasificacion_recurso = '.$clasificacion_recurso));
	$this->concatena($this->cfpd07_clasificacion_recurso->generateList('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.' and tipo_recurso='.$tipo_recurso.'',' clasificacion_recurso ASC', null, '{n}.cfpd07_clasificacion_recurso.clasificacion_recurso', '{n}.cfpd07_clasificacion_recurso.denominacion'), 'tipo');
 	$this->cfpd07_clasificacion_recurso->findAll($this->condicionNDEP().' and tipo_recurso='.$tipo_recurso.' and clasificacion_recurso = '.$clasificacion_recurso);
 	$tipo_recurso = $this->data['cfpp07_clasificacion_recurso']['tipo_recurso'];
	$clasificacion_recurso = $this->data['cfpp07_clasificacion_recurso']['clasificacion_recurso'];
	$denominacion = $this->data['cfpp07_clasificacion_recurso']['denominacion'];
 	$this->set('clasificacion_recurso',$clasificacion_recurso);
	$this->set('denominacion',$denominacion);
	$this->set('tipo_recurso',$tipo_recurso);
	$this->set('pagina',$pagina);
 }






 function guardarEditar($tipo_recurso=null, $clasificacion_recurso = null){
 	$this->layout ="ajax";
	$ano=$this->ano_ejecucion();
	$this->set('ANO',$ano);

 	if($clasificacion_recurso != null){
 		$tipo_recurso = $this->data['cfpp07_clasificacion_recurso']['tipo_recurso'];
 		$denominacion = $this->data['cfpp07_clasificacion_recurso']['denominacion'];
 		$sql = "UPDATE cfpd07_clasificacion_recurso SET denominacion = '".$denominacion."' WHERE ".$this->condicionNDEP()." and  tipo_recurso='".$tipo_recurso."' and clasificacion_recurso = ".$clasificacion_recurso;
 		if($this->cfpd07_clasificacion_recurso->execute($sql)>1){
 		//echo "Deno ".$denominacion.", tipo ".$tipo_recurso;
 		$this->set('datos', $this->cfpd07_clasificacion_recurso->findAll($this->condicionNDEP().' and tipo_recurso='.$tipo_recurso.' and clasificacion_recurso = '.$clasificacion_recurso));
		$var1=$this->Session->read('SScodpresi');
 		$var2=$this->Session->read('SScodentidad');
 		$var3=$this->Session->read('SScodtipoinst');
 		$var4=$this->Session->read('SScodinst');
		$this->concatena($this->cfpd07_clasificacion_recurso->generateList('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.' and tipo_recurso='.$tipo_recurso.' ',' clasificacion_recurso ASC', null, '{n}.cfpd07_clasificacion_recurso.clasificacion_recurso', '{n}.cfpd07_clasificacion_recurso.denominacion'), 'tipo');
		$this->set('mensaje', 'EL DATO FUE MODIFICADO EXITOSAMENTE');
 		}else{
 		$this->set('mensajeError', 'EL DATO no FUE MODIFICADO');
 		}

 	}

 }





 function guardarEditar2($pagina=null, $tipo_recurso=null, $clasificacion_recurso = null){
 	$this->layout ="ajax";
	$ano=$this->ano_ejecucion();
	$this->set('ANO',$ano);

 	if($clasificacion_recurso != null){
 		$denominacion = $this->data['cfpp07_clasificacion_recurso']['denominacion'];
 		$sql = "UPDATE cfpd07_clasificacion_recurso SET denominacion = '".$denominacion."' WHERE ".$this->condicionNDEP()." and  tipo_recurso='".$tipo_recurso."' and clasificacion_recurso = ".$clasificacion_recurso;
 		if($this->cfpd07_clasificacion_recurso->execute($sql)>1){
 		//echo "Deno ".$denominacion.", tipo ".$tipo_recurso;
 		$this->set('datos', $this->cfpd07_clasificacion_recurso->findAll($this->condicionNDEP().' and tipo_recurso='.$tipo_recurso.' and clasificacion_recurso = '.$clasificacion_recurso));
		$var1=$this->Session->read('SScodpresi');
 		$var2=$this->Session->read('SScodentidad');
 		$var3=$this->Session->read('SScodtipoinst');
 		$var4=$this->Session->read('SScodinst');
		$this->concatena($this->cfpd07_clasificacion_recurso->generateList('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.' and tipo_recurso='.$tipo_recurso.' ',' clasificacion_recurso ASC', null, '{n}.cfpd07_clasificacion_recurso.clasificacion_recurso', '{n}.cfpd07_clasificacion_recurso.denominacion'), 'tipo');
		$this->set('Message_existe', 'EL DATO FUE MODIFICADO EXITOSAMENTE');
 		}else{
 		$this->set('mensajeError', ' EL DATO no FUE MODIFICADO');
 		}

 		$this->consulta($pagina);
	    $this->render("consulta");

 	}

 }




 function eliminar($tipo_recurso=null, $clasificacion_recurso = null){

	$this->layout ="ajax";
	$ano=$this->ano_ejecucion();
	$this->set('ANO',$ano);

	if($clasificacion_recurso != null){
		$var1=$this->Session->read('SScodpresi');
 		$var2=$this->Session->read('SScodentidad');
 		$var3=$this->Session->read('SScodtipoinst');
 		$var4=$this->Session->read('SScodinst');

		$sql = "DELETE FROM cfpd07_clasificacion_recurso WHERE tipo_recurso=".$tipo_recurso." and clasificacion_recurso=".$clasificacion_recurso." and cod_presi=".$var1." and cod_entidad=".$var2." and cod_tipo_inst=".$var3." and cod_inst=$var4";
		$this->cfpd07_clasificacion_recurso->execute($sql);
		$this->concatena($this->cfpd07_clasificacion_recurso->generateList('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.' and tipo_recurso='.$tipo_recurso.' ',' clasificacion_recurso ASC', null, '{n}.cfpd07_clasificacion_recurso.clasificacion_recurso', '{n}.cfpd07_clasificacion_recurso.denominacion'), 'tipo');
		$this->radio($tipo_recurso);
		$this->set('errorMessage', 'EL REGISTRO FUE ELIMINADO');
		$this->set('enable', 'disabled');

	}

 }






function eliminar2($pagina=null, $tipo_recurso=null, $clasificacion_recurso = null){

	$this->layout ="ajax";
	$ano=$this->ano_ejecucion();
	$this->set('ANO',$ano);

	if($clasificacion_recurso != null){
		$var1=$this->Session->read('SScodpresi');
 		$var2=$this->Session->read('SScodentidad');
 		$var3=$this->Session->read('SScodtipoinst');
 		$var4=$this->Session->read('SScodinst');

		$sql = "DELETE FROM cfpd07_clasificacion_recurso WHERE tipo_recurso=".$tipo_recurso." and clasificacion_recurso=".$clasificacion_recurso." and cod_presi=".$var1." and cod_entidad=".$var2." and cod_tipo_inst=".$var3." and cod_inst=$var4";
		$this->cfpd07_clasificacion_recurso->execute($sql);
		$this->concatena($this->cfpd07_clasificacion_recurso->generateList('cod_presi='.$var1.' and cod_entidad='.$var2.' and cod_tipo_inst='.$var3.' and cod_inst='.$var4.' and tipo_recurso='.$tipo_recurso.' ',' clasificacion_recurso ASC', null, '{n}.cfpd07_clasificacion_recurso.clasificacion_recurso', '{n}.cfpd07_clasificacion_recurso.denominacion'), 'tipo');
		$this->radio($tipo_recurso);
		$this->set('errorMessage', 'EL REGISTRO FUE ELIMINADO');
		$this->set('enable', 'disabled');

		   $pagina--;
		if($pagina<0){$pagina=0;}

	}

	$this->consulta($pagina);
	$this->render("consulta");

}






 function consulta($pag_num=null){
 	$this->layout ="ajax";
	$ano=$this->ano_ejecucion();
	$this->set('ANO',$ano);
    $data = $this->cfpd07_clasificacion_recurso->findAll($this->condicionNDEP(), null, 'tipo_recurso, clasificacion_recurso ASC', null, null, null);
    $this->set('datos',$data);
    if($pag_num!=null){
    	$this->set('pagina_actual', $pag_num);
    }
 }//fin function





}//fin class
?>