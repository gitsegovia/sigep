<?php
/*
 * Creado el 15/10/2007 a las 11:13:06 PM por miguelangel
 * Para CakePHP, PostgresSQL
 */
 class Cscp01UnidadMedidaController extends AppController {
   var $name="cscp01_unidad_medida";
   var $uses = array('cscd01_unidad_medida','cscd02_solicitud_cuerpo','Usuario');
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

    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');
	$condicion2 = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
	$opc = $this->Usuario->findCount($condicion2);

	if($cod_dep == '01'){
		return;
	}else{
 		echo "LO SIENTO - UD. NO TIENE PERMISOS PARA ESTE PROCESO!!";
		exit;
	}
 }

function verifica_SS($i){
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
        $sql_re .= "cod_inst=".$this->verifica_SS(4);
        return $sql_re;
}//fin funcion SQLCA

function index($id=null){
    $this->layout = "ajax";
	//$this->render('index');
}



function index2 () {
 	$this->layout = "ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));

	if((isset($this->data['cscd01_unidad_medida']['cod_medida']) && $this->data['cscd01_unidad_medida']['cod_medida']!="") || isset($cod_medida)){
	$cod_medida = isset($this->data['cscd01_unidad_medida']['cod_medida']) ?  $this->data['cscd01_unidad_medida']['cod_medida'] : $cod_medida;
	$this->Session->write('cod_medida_r',$cod_medida);
	$this->set('partida', $this->cfpd01_ano_partida->generateList("where cod_grupo=4", 'cod_partida ASC', null, '{n}.cfpd01_ano_partida.cod_partida', '{n}.cfpd01_ano_partida.cod_partida'));
 	$this->Session->write('cod_medida',$cod_medida);
	$this->set('cod_medida',$cod_medida);
	}
}



function consultar($pag_num=null) {
 		$this->layout = "ajax";
        $this->cscd01_unidad_medida->findAll(null,null,'cod_medida ASC');
        $lista =  $this->cscd01_unidad_medida->findAll();
		$this->set('lista',$lista);
		if($pag_num!=null){$this->set('pagina_actual', $pag_num); }
 }//fin function consultar2



function guardar(){
    $this->layout = "ajax";
	$expresion=$this->data['cscp01_unidad_medida']['expresion'];
	$denominacion=$this->data['cscp01_unidad_medida']['denominacion'];
	$sql="insert into cscd01_unidad_medida (expresion,denominacion )values ('$expresion','$denominacion')";
	if($this->cscd01_unidad_medida->execute($sql)>1){
		$this->set('mensaje', 'La Unidad de medida fue almacenada correctamente');
	   }else{
	   	$this->set('mensajeError', 'Lo siento, la Unidad de medida no fue Almacenada');
	 }
	 echo'<script>';
	 echo"document.getElementById('agregar').disabled=false;";
	 echo"document.getElementById('denominacion').value='';";
	 echo'</script>';
	 $this->mostrar_datos();
	 $this->render("mostrar_datos");
}



function modificar ($var=null) {
 	$this->layout = "ajax";
 	$var=$var;
	$lista =  $this->cscd01_unidad_medida->findAll('cod_medida='.$var.'');
	$this->set('lista',$lista);
}



function guardar_modificar ($var=null) {
	$this->layout = "ajax";

	$var=$var;//codigo medida
	$expresion=$this->data['cscp01_unidad_medida']['expresion'];
	$denominacion=$this->data['cscp01_unidad_medida']['denominacion'];
	$sql="update cscd01_unidad_medida set expresion='$expresion', denominacion='$denominacion' where cod_medida='$var'";
	if($this->cscd01_unidad_medida->execute($sql)>1){
		$this->set('mensaje', 'La Unidad de medida fue actualizada correctamente');
	}else{
		$this->set('mensajeError', 'Lo siento, La unidad de medida no fue Actualizada');
	}
	//$datos=$this->cscd01_unidad_medida->findAll(null,null,'expresion ASC');
	//$this->set('datos',$datos);
	$this->mostrar_datos();
	$this->render("mostrar_datos");
}



function eliminar($var=null){
	$this->layout = "ajax";

	$var=$var;
	if($this->cscd02_solicitud_cuerpo->findBycod_medida($var)){
		$this->set('mensajeError', 'Lo siento, La unidad de medida ya se encuentra en uso, no puede ser eliminada');
		//$lista =  $this->cscd01_unidad_medida->findAll();
		//$this->set('lista',$lista);
	}else{
		if($this->cscd01_unidad_medida->execute("delete from cscd01_unidad_medida where cod_medida='$var'")>1){
			$this->set('mensaje', 'La Unidad de medida fue eliminada correctamente');
		}else{
	   		$this->set('mensajeError', 'Lo siento, La unidad de medida no fue eliminada');
	   	}
		//$lista =  $this->cscd01_unidad_medida->findAll();
		//$this->set('lista',$lista);
	}
	$this->mostrar_datos();
	$this->render("mostrar_datos");
}



//------------Nuevas Funciones (index, mostrar1, mostrar_datos)-----------//



function index3 () {
 	$this->layout = "ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));

	$list = $this->cscd01_unidad_medida->generateList(null,'cod_medida ASC', null, '{n}.cscd01_unidad_medida.cod_medida', '{n}.cscd01_unidad_medida.expresion');
	$list = $list != null ? $list : array();
	$datos=$this->cscd01_unidad_medida->findAll(null,null,'expresion ASC');
	$this->concatena($list, 'list');
	$this->set('datos',$datos);
 }



function mostrar1($select=null){
	$this->layout="ajax";
	if($select!=null){
		if($select=='otros'){
			$this->set('ir','no');
			$this->set('expresion','');
			$this->set('denominacion','');
			$this->set('mensaje','Puede agregar la nueva Unidad de Medida');
		}else{
			$dato=$this->cscd01_unidad_medida->findAll('cod_medida='.$select);
			$this->set('ir','si');
			$this->set('cod_medida',$dato[0]['cscd01_unidad_medida']['cod_medida']);
			$this->set('expresion',$dato[0]['cscd01_unidad_medida']['expresion']);
			$this->set('denominacion',$dato[0]['cscd01_unidad_medida']['denominacion']);
		}
	}else{
		$this->set('ir','no');
		$this->set('expresion','');
		$this->set('denominacion','');
		$this->set('mensajeError','No ha seleccionado niguna Unidad de Medida');
	}
}//mostrar1



function mostrar_datos($var=null){
	$this->layout="ajax";

	if($var==null){
	$datos=$this->cscd01_unidad_medida->findAll(null,null,'expresion ASC');
	}elseif($var==1){
		$datos=$this->cscd01_unidad_medida->findAll(null,null,'cod_medida ASC');
	}elseif($var==2){
		$datos=$this->cscd01_unidad_medida->findAll(null,null,'expresion ASC');
	}elseif($var==3){
		$datos=$this->cscd01_unidad_medida->findAll(null,null,'denominacion ASC');
	}
	$this->set('datos',$datos);
}

}//fin class
?>
