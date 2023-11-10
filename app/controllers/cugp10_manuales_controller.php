<?php
class Cugp10ManualesController extends AppController{
	var $name = 'cugp10_manuales';
 	var $uses = array('ccfd04_cierre_mes','modulos_sistema','cugp10_manuales','cugp10_manuales_videos');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf','Form');

function checkSession(){
	if (!$this->Session->check('Usuario')){
			$this->redirect('/salir/');
			exit();
	}else{
		//$this->set('userTable', $this->requestAction('/cnmp03partidas/', array('return')));
		//echo "H".$this->requestAction('/usuarios/actualizar_user',array('return'));
		//$this->requestAction('/usuarios/actualizar_user');
	}
}

function beforeFilter(){
	$this->checkSession();
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
	}
}

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
}

function index ($url=null,$ancho=425,$alto=344) {
    $this->layout="ajax";
    if(isset($url)){
       $this->set("URL",$url);
       $this->set("ANCHO",$ancho);
       $this->set("ALTO",$alto);
    }
}

/** Ver manual del programa */
function ver_manual ($programa=null, $accion=null){
	$this->layout="ajax";
	if($accion==null){
		$carpeta  = $programa;
		$programa = strtoupper($programa);
		$datos_manual  = $this->cugp10_manuales->findAll("programa='$programa'");
		if(count($datos_manual)==0){
			$this->set('carpeta',$carpeta);
			$this->set('programa',$programa);
			$this->set('titulo_modulo','No se encontro manual para este programa..!');
			$this->set('cod_modulo','cugp00');
			$this->set('titulo_programa','');
			$this->set('descripcion_programa','');
			$this->set('observaciones_programa','');
			$this->set('videos',array());
			$this->set('manual_existe',0);
		}else{
			$videos_manual = $this->cugp10_manuales_videos->findAll("programa='$programa'", null, 'id ASC');
			$modulo = $this->modulos_sistema->findAll("cod_modulo='".$datos_manual[0]['cugp10_manuales']['cod_modulo']."'");
			$this->set('carpeta',$carpeta);
			$this->set('programa',$programa);
			$this->set('titulo_modulo',$modulo[0]['modulos_sistema']['denominacion']);
			$this->set('cod_modulo',strtolower($datos_manual[0]['cugp10_manuales']['cod_modulo']));
			$this->set('titulo_programa',$datos_manual[0]['cugp10_manuales']['titulo_programa']);
			$this->set('descripcion_programa',$datos_manual[0]['cugp10_manuales']['descripcion_programa']);
			$this->set('observaciones_programa',$datos_manual[0]['cugp10_manuales']['observaciones_programa']);
			$this->set('videos',$videos_manual);
			$this->set('manual_existe',1);
		}
	}
	$this->set('accion',$accion);
	$this->set("URL",'venezolanos_alegres.flv');
}

function video ($carpeta=null, $url=null) {
    $this->layout="ajax";
    $this->set("carpeta",$carpeta);
    $this->set("URL",$url);
}

function ver_video ($carpeta=null, $url=null) {
    $this->layout="ajax";
    $this->set("URL",$url);
    $this->redirect('/videos/'.$carpeta.'/'.$url);
    //if(isset($url)){
      // $this->set("URL",$url);
    //}
}

function descripcion_programa ($programa=null){
	$this->layout="ajax";
	$datos_manual  = $this->cugp10_manuales->findAll("programa='$programa'");
	$this->set('titulo_programa',$datos_manual[0]['cugp10_manuales']['titulo_programa']);
	$this->set('descripcion_programa',$datos_manual[0]['cugp10_manuales']['descripcion_programa']);
	$this->set('observaciones_programa',$datos_manual[0]['cugp10_manuales']['observaciones_programa']);
}

function observacion_programa ($programa=null){
	$this->layout="ajax";
	$datos_manual  = $this->cugp10_manuales->findAll("programa='$programa'");
	$this->set('titulo_programa',$datos_manual[0]['cugp10_manuales']['titulo_programa']);
	$this->set('descripcion_programa',$datos_manual[0]['cugp10_manuales']['descripcion_programa']);
	$this->set('observaciones_programa',$datos_manual[0]['cugp10_manuales']['observaciones_programa']);
}

function registrar_manual(){
	$this->layout="ajax";
	$modulo = $this->modulos_sistema->findAll();
	for($i=0; $i<count($modulo); $i++){
		$cod[]  = $modulo[$i]['modulos_sistema']['cod_modulo'];
		$deno[] = $modulo[$i]['modulos_sistema']['denominacion'];
	}
	$array_modulos = array_combine($cod, $deno);
	$this->set("array_modulos",$array_modulos);
}

function guardar_manual(){
	$this->layout="ajax";
	if($this->data['cugp10_manuales']['programa']=='' || $this->data['cugp10_manuales']['titulo_programa']=='' || $this->data['cugp10_manuales']['cod_modulo']==''){
		$this->set('mensajeError', 'La informaci&oacute;n no fu&eacute; registrada, revise existen datos vacios');
	}else{
		$this->cugp10_manuales->save($this->data);
		$this->set('mensaje', 'La informaci&oacute;n fu&eacute; registrada correctamente');
	}
}

function editar_manual(){
	$this->layout="ajax";
	$modulo = $this->modulos_sistema->findAll();
	for($i=0; $i<count($modulo); $i++){
		$cod[]  = $modulo[$i]['modulos_sistema']['cod_modulo'];
		$deno[] = $modulo[$i]['modulos_sistema']['denominacion'];
	}
	$array_modulos = array_combine($cod, $deno);
	$this->set("array_modulos",$array_modulos);
}

function select_programas($cod_modulo=null){
	$this->layout="ajax";
	$datos_manual  = $this->cugp10_manuales->findAll("cod_modulo='$cod_modulo'");
	for($i=0; $i<count($datos_manual); $i++){
		$cod[]  = $datos_manual[$i]['cugp10_manuales']['programa'];
		$deno[] = $datos_manual[$i]['cugp10_manuales']['titulo_programa'];
	}
	$array_programas = array_combine($cod, $deno);
	$this->set("array_programas",$array_programas);
}

function cargar_programa($programa=null){
	$this->layout="ajax";
	$datos_manual  = $this->cugp10_manuales->findAll("programa='$programa'");
	$modulo = $this->modulos_sistema->findAll("cod_modulo='".$datos_manual[0]['cugp10_manuales']['cod_modulo']."'");

	$id = $datos_manual[0]['cugp10_manuales']['id'];
	$videos_manual = $this->cugp10_manuales_videos->findAll("programa_id='$id'", null, 'id ASC');

	$modulo = $this->modulos_sistema->findAll();
	for($i=0; $i<count($modulo); $i++){
		$cod[]  = $modulo[$i]['modulos_sistema']['cod_modulo'];
		$deno[] = $modulo[$i]['modulos_sistema']['denominacion'];
	}
	$array_modulos = array_combine($cod, $deno);
	$this->set("array_modulos",$array_modulos);

	$this->set('programa',$programa);
	$this->set('titulo_modulo',$modulo[0]['modulos_sistema']['denominacion']);
	$this->set('id',$datos_manual[0]['cugp10_manuales']['id']);
	$this->set('cod_modulo',$datos_manual[0]['cugp10_manuales']['cod_modulo']);
	$this->set('titulo_programa',$datos_manual[0]['cugp10_manuales']['titulo_programa']);
	$this->set('descripcion_programa',$datos_manual[0]['cugp10_manuales']['descripcion_programa']);
	$this->set('observaciones_programa',$datos_manual[0]['cugp10_manuales']['observaciones_programa']);
	$this->set('videos',$videos_manual);
}


function guardar_modificacion_manual(){
	$this->layout="ajax";

	//pr($this->data);

	if($this->data['cugp10_manuales']['programa']=='' || $this->data['cugp10_manuales']['titulo_programa']=='' || $this->data['cugp10_manuales']['cod_modulo']==''){
		$this->set('mensajeError', 'La informaci&oacute;n no fu&eacute; registrada, revise existen datos vacios');
	}else{
		$this->cugp10_manuales->save($this->data);
		$this->set('mensaje', 'La informaci&oacute;n fu&eacute; registrada correctamente');
	}

}



}
?>