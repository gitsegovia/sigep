<?php
/*
 * Creado el 15/10/2007 a las 11:13:06 PM por miguelangel
 * Para CakePHP, PostgresSQL
 */
 class Cscd02SolicitudCriterioGarantiaController extends AppController {
   var $name="cscd02_solicitud_criterio_garantia";
   var $uses = array('cscd02_solicitud_criterio_garantia','Usuario');
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



function guardar(){
    $this->layout = "ajax";

        $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');


	$parametro=$this->data['cscd02_solicitud_criterio_garantia']['parametro'];
	$porcentaje=$this->data['cscd02_solicitud_criterio_garantia']['porcentaje'];

       	$sql="insert into cscd02_solicitud_criterio_garantia (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,parametro,porcentaje) values ('$cod_presi','$cod_entidad','$cod_tipo_inst','$cod_inst','$parametro','$porcentaje')";
	if($this->cscd02_solicitud_criterio_garantia->execute($sql)>1){
		$this->set('mensaje', 'EL Parametro fue almacenado correctamente');
	   }else{
	   	$this->set('mensajeError', 'Lo siento, El Parametro no fue Almacenado');
	 }
	 echo'<script>';
	 echo"document.getElementById('parametro').value='';";
	 echo"document.getElementById('porcentaje').value='';";
	 echo'</script>';	 $this->mostrar_datos();
	 $this->render("mostrar_datos");
}




function guardar_modificar ($cod_garantia=null) {
	$this->layout = "ajax";
	$parametro=$this->data['cscd02_solicitud_criterio_garantia']['parametro'];
	$porcentaje=$this->data['cscd02_solicitud_criterio_garantia']['porcentaje'];

        $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
        $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." ";

	$sql="update cscd02_solicitud_criterio_garantia set parametro='$parametro', porcentaje='$porcentaje' where $condicion and cod_garantia='$cod_garantia'";
	if($this->cscd02_solicitud_criterio_garantia->execute($sql)>1){
		$this->set('mensaje', 'El Parametro fue actualizado correctamente');
	}else{
		$this->set('mensajeError', 'Lo siento, El parametro no fue Actualizado');
	}
	$this->mostrar_datos();
	$this->render("mostrar_datos");
}



function eliminar($codigo_tiempo=null, $id=null){

          $this->layout = "ajax";
          $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');

	  $cont                     =       0;
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." ";
          $sql="DELETE FROM cscd02_solicitud_criterio_garantia  WHERE $condicion and cod_garantia='".$codigo_tiempo."'";

         $cont=$this->cscd02_solicitud_criterio_garantia->execute($sql);
         if(true){
        	    echo"<script> new Effect.DropOut('".$id."'); </script>";
        	    $this->set('errorMessage', 'LOS DATOS FUERON ELIMINADOS');
         }else{
         	    $this->set('errorMessage', 'LOS DATOS NO FUERON ELIMINADOS, YA ESTA SIENDO USADO');
         }//fin else
$this->vacio();
$this->render("vacio");
}//fin function



function index () {
 	$this->layout = "ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));

	$list = $this->cscd02_solicitud_criterio_garantia->generateList(null,'cod_garantia ASC', null, '{n}.cscd02_solicitud_criterio_garantia.cod_garantia', '{n}.cscd02_solicitud_criterio_garantia.parametro');
	$list = $list != null ? $list : array();
	$datos=$this->cscd02_solicitud_criterio_garantia->findAll(null,null,'cod_garantia ASC');
	$this->concatena($list, 'list');
	$this->set('datos',$datos);
 }


function vacio(){
   $this->layout="ajax";
}


function mostrar1($cod_garantia){
	$this->layout="ajax";
	if($cod_garantia!=null){

                        $dato=$this->cscd02_solicitud_criterio_garantia->findAll('cod_garantia='.$cod_garantia);
			$this->set('ir','si');
			$this->set('cod_garantia',$dato[0]['cscd02_solicitud_criterio_garantia']['cod_garantia']);
			$this->set('parametro',$dato[0]['cscd02_solicitud_criterio_garantia']['parametro']);
			$this->set('porcentaje',$dato[0]['cscd02_solicitud_criterio_garantia']['porcentaje']);
		}
	else{
		$this->set('ir','no');
		$this->set('expresion','');
		$this->set('denominacion','');
		$this->set('mensajeError','No ha seleccionado nigun Parametro');
	}
}



function mostrar_datos($var=null){
	$this->layout="ajax";

	if($var==null){
	$datos=$this->cscd02_solicitud_criterio_garantia->findAll(null,null,'cod_garantia ASC');
	}elseif($var==1){
		$datos=$this->cscd02_solicitud_criterio_garantia->findAll(null,null,'cod_garantia ASC');
	}elseif($var==2){
		$datos=$this->cscd02_solicitud_criterio_garantia->findAll(null,null,'cod_garantia ASC');
	}elseif($var==3){
		$datos=$this->cscd02_solicitud_criterio_garantia->findAll(null,null,'cod_garantia ASC');
	}
	$this->set('datos',$datos);
}

}//fin class
?>
