<?php

 class Cscd02SolicitudCierreController extends AppController {
   var $name="cscd02_solicitud_cierre";
   var $uses = array('cscd02_solicitud_encabezado','ccfd04_cierre_mes','Usuario');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap');
   var $estatus = array ('1'=>'Abierta','2'=>'Cerrada');

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
        $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and ";
        $sql_re .= "cod_dep=".$this->verifica_SS(5);
        return $sql_re;
}//fin funcion SQLCA


function datos(){
        $ano=$this->ano_ejecucion();
        $condi =$this->SQLCA($ano);
        $solicitud_encabezado=$this->cscd02_solicitud_encabezado->findAll($condi."and status >0",null, "numero_solicitud",null,null, null);
        //                                                 findAll($conditions = null, $fields = null, $order = null, $limit = null, $page = 1, $recursive = null)
        $this->set('datos',$solicitud_encabezado);
    
}

function solicitudes_cerradas(){
    $this->layout = "ajax";
    $this->datos();
}
function solicitudes_abiertas(){
    $this->layout = "ajax";
    $this->datos();
}

function index () {
 	$this->layout = "ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
        $this->datos();
 }


 function abrir($ano =null, $numero_solicitud = null){
     $this->layout = "ajax";
     $condi =$this->SQLCA($ano);
     
     $sql="UPDATE cscd02_solicitud_encabezado SET status=1 WHERE $condi and numero_solicitud=$numero_solicitud";
     $this->cscd02_solicitud_encabezado->execute($sql);
     
     $verifica=$solicitud_encabezado=$this->cscd02_solicitud_encabezado->findAll("ano_solicitud = $ano and numero_solicitud=$numero_solicitud",null, null,null,null, null);
     
     if ($verifica[0]['cscd02_solicitud_encabezado']['status'] = 1){
         //guardo
         $this->set('Message_existe', 'La Solicitud fue Abiera correctamente');
         
     }else {
        //no guardo
         $this->set('errorMessage', 'La Solicitud no fue Abierta');
     }
     
     $this->datos();
     
     $this->vacio();
     $this->render('vacio');
     
 }

 function cerrar($ano =null, $numero_solicitud = null){
     $this->layout = "ajax";
     $condi =$this->SQLCA($ano);
     
     $sql="UPDATE cscd02_solicitud_encabezado SET status=2 WHERE $condi and numero_solicitud=$numero_solicitud";
     $this->cscd02_solicitud_encabezado->execute($sql);
     
     $verifica=$solicitud_encabezado=$this->cscd02_solicitud_encabezado->findAll("ano_solicitud = $ano and numero_solicitud=$numero_solicitud",null, null,null,null, null);
     
     if ($verifica[0]['cscd02_solicitud_encabezado']['status'] = 2){
         //guardo
         $this->set('Message_existe', 'La Solicitud fue Cerrada correctamente');
         
     }else {
        //no guardo
         $this->set('errorMessage', 'La Solicitud no fue Cerrada');
     }
     $this->vacio();
     $this->render('vacio');
 }
 
function ofertas($ano=null,$numero_solicitud=null){
    $this->layout="ajax";
    $condi =$this->SQLCA($ano);
    
    $sql="SELECT * from v_cscd02_solicitud_encabezado_oferentes where $condi and numero_solicitud=$numero_solicitud";
    $ofertas=$this->cscd02_solicitud_encabezado->execute($sql);
    
    $this->set('solicitud',$numero_solicitud);
    $this->set('ofertas',$ofertas);
    
   
}

function detalles_oferta($ano, $numero_solicitud=null,$numero_cotizacion=null,$rif=null){
     $this->layout="ajax";
     $condi =$this->SQLCA($ano);
     
    $sql="SELECT * from v_cscd02_solicitud_cuerpo_oferentes where $condi and numero_solicitud=$numero_solicitud and numero_cotizacion=$numero_cotizacion and rif='$rif'";
    $detalle_oferta=$this->cscd02_solicitud_encabezado->execute($sql);
    
    $sql1="SELECT objeciones from cscd02_solicitud_encabezado_oferentes  where $condi and numero_solicitud=$numero_solicitud and numero_cotizacion=$numero_cotizacion and rif='$rif'";
    $objecion=$this->cscd02_solicitud_encabezado->execute($sql1);
    
    $this->set('objecion',$objecion['0']['0']['objeciones']);
    $this->set('solicitud',$numero_solicitud);
    $this->set('detalle_oferta',$detalle_oferta);
}
 
function vacio(){
   $this->layout="ajax";
}


}//fin class
?>
