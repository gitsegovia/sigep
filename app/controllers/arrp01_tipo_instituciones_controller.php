<?php
/*
 * Fecha: 06/07/2007
 *
 * Por Erisk Aragol
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */

 class Arrp01TipoInstitucionesController extends AppController{

	var $name = 'arrp01_tipo_instituciones';
	var $uses = array('arrd01','arrd03','Usuario');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');

function checkSession(){

				if (!$this->Session->check('Root_session')){
						$this->redirect('/root/salir/');
						exit();
				}else{
					if($this->Session->read('Root_session')!="VISION_INTEGRAL"){
						$this->redirect('/root/salir/');
						 exit();
					}
				}
}//fin checksession




function beforeFilter(){
    $this->checkSession();

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
}//fin funcion SQLCA




function index() {

   	$this->layout = "ajax";

	$republica=$this->arrd01->generateList(null,'cod_presi ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.denominacion');
	if($republica!=null){
		$this->concatena($republica,'republica');
	}else{
		$this->set('republica',array());
	}

}



function mostrar($opcion=null,$var=null){
	$this->layout="ajax";
	if($var!=''){
		if($opcion=='codigo'){
			$variable=$var;
		}else if($opcion=='deno'){
			$datos=$this->arrd01->execute("select * from arrd01 where cod_presi='$var' order by cod_presi asc");
			$variable=$datos[0][0]['denominacion'];
		}

		$this->set('variable',$variable);
		$this->set('opcion',$opcion);
	}else{
		$this->set('variable','');
		$this->set('opcion',$opcion);
	}

}



function datos($cod_presi=null){
	$this->layout="ajax";
	if($cod_presi!=''){
		$datos=$this->arrd03->execute("select * from arrd03 where cod_presi='$cod_presi' order by cod_tipo_inst asc");
		if($datos!=null){
			$this->set('datos',$datos);
		}else{
			$this->set('datos',null);
		}

		echo "<script>document.getElementById('codigo').value='';</script>";
		echo "<script>document.getElementById('denominacion').value='';</script>";
	}else{
		$this->set('datos',null);
	}
}



function guardar(){
	$this->layout="ajax";

	if(empty($this->data['arrp00']['cod_republica'])){
		$this->set('errorMessage', 'Debe seleccionar la república');

	}else if(empty($this->data['arrp00']['codigo'])){
		$this->set('errorMessage', 'Debe ingresar el código del Tipo de institución');

	}else if(empty($this->data['arrp00']['denominacion'])){
		$this->set('errorMessage', 'Debe ingresar la denominación del Tipo de institución');

	}else{
		$cod_presi=$this->data['arrp00']['cod_republica'];
		$codigo=$this->data['arrp00']['codigo'];
		$denominacion=$this->data['arrp00']['denominacion'];
		$datos=$this->arrd03->execute("select * from arrd03 where cod_presi='$cod_presi' and cod_tipo_inst='$codigo' order by cod_tipo_inst asc");
		if($datos==null){
			 $sql = "INSERT INTO arrd03 VALUES ('$cod_presi','$codigo','$denominacion')";
		   	 $sw=$this->arrd03->execute($sql);
		   	 if($sw>1){
				$this->set('Message_existe', 'REGISTRO EXITOSO');
				echo" <script> ver_documento('/arrp01_tipo_instituciones/datos/$cod_presi','grilla'); </script>";
	   		}else{
	   			$this->set('errorMessage', 'INSERCI&Oacute;N FALLIDA DE LOS DATOS');
	   		}
		}else{
			$this->set('errorMessage', 'este dato ya existe registrado');
		}



	}
}




function modificar($cod_presi=null,$cod_tipo_inst=null,$i=null){
	$this->layout="ajax";

	$datos=$this->arrd03->execute("select * from arrd03 where cod_presi='$cod_presi' and cod_tipo_inst='$cod_tipo_inst' order by cod_tipo_inst asc");
	$this->set('datos',$datos);

	$this->set('k',$i);


}



function guardar_modificar($cod_presi=null,$cod_tipo_inst=null,$i=null){
	$this->layout="ajax";

	if(empty($this->data['arrp00']['denominacion'.$i])){
		$this->set('errorMessage', 'Debe ingresar la denominación del tipo de institución');

	}else{
		$codigo=$this->data['arrp00']['codigo'.$i];
		$denominacion=$this->data['arrp00']['denominacion'.$i];

		 $sql = "update arrd03 set denominacion='".$denominacion."' where cod_presi='$cod_presi' and cod_tipo_inst='$cod_tipo_inst'";
	   	 $sw=$this->arrd03->execute($sql);
	   	 if($sw>1){
			$this->set('Message_existe', 'EL REGISTRO SE MODIFICO CON EXITO');
			echo" <script> ver_documento('/arrp01_tipo_instituciones/datos/$cod_presi','grilla'); </script>";
   		}else{
   			$this->set('errorMessage', 'EL DATO NO PUDO SER MODIFICADO');
   		}

	}

}



function eliminar($cod_presi=null,$cod_tipo_inst=null){
	$this->layout="ajax";

	 $sql = "delete from  arrd03 where cod_presi='$cod_presi' and cod_tipo_inst='$cod_tipo_inst'";
   	 $sw=$this->arrd03->execute($sql);
   	 if($sw>1){
		$this->set('Message_existe', 'EL REGISTRO SE ELIMINO CON EXITO');
		echo" <script> ver_documento('/arrp01_tipo_instituciones/datos/$cod_presi','grilla'); </script>";
	}else{
		$this->set('errorMessage', 'EL DATO NO PUDO SER ELIMINADO');
	}

}



function cancelar($cod_presi=null){
	$this->layout="ajax";
	echo" <script> ver_documento('/arrp01_tipo_instituciones/datos/$cod_presi','grilla'); </script>";
}



}//FIN DEL CONTROLADOR
?>
