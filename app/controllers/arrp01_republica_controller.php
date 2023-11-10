<?php
/*
 * Fecha: 06/07/2007
 *
 * Por Erisk Aragol
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */

 class Arrp01RepublicaController extends AppController{

	var $name = 'arrp01_republica';
	var $uses = array('arrd01','Usuario');
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
   	/*$ver=$this->arrd01->execute("select * from arrd01 order by cod_presi desc limit 1");
	if($ver!=null)
		$cod_presi=$ver[0][0]['cod_presi']+1;
	else
		$cod_presi=1;
	$this->set('cod_presi',$cod_presi);*/

	$datos=$this->arrd01->execute("select * from arrd01 order by cod_presi asc");
	$this->set('datos',$datos);

	echo "<script>document.getElementById('codigo').focus();</script>";

}



function guardar(){
	$this->layout="ajax";

	if(empty($this->data['arrp00']['codigo'])){
		$this->set('errorMessage', 'Debe ingresar el código');

	}else if(empty($this->data['arrp00']['denominacion'])){
		$this->set('errorMessage', 'Debe ingresar la denominación de la república');

	}else{
		$codigo=$this->data['arrp00']['codigo'];
		$denominacion=$this->data['arrp00']['denominacion'];
		$datos=$this->arrd01->execute("select * from arrd01 where cod_presi='$codigo' order by cod_presi asc");
		if($datos==null){
			 $sql = "INSERT INTO arrd01 VALUES ('$codigo', '$denominacion')";
		   	 $sw=$this->arrd01->execute($sql);
		   	 if($sw>1){
				$this->set('Message_existe', 'REGISTRO EXITOSO');
				echo" <script> ver_documento('/arrp01_republica','principal'); </script>";
	   		}else{
	   			$this->set('errorMessage', 'INSERCI&Oacute;N FALLIDA DE LOS DATOS');
	   		}
		}else{
			$this->set('errorMessage', 'este dato ya existe registrado');
		}

	}
}




function modificar($cod_presi=null,$i=null){
	$this->layout="ajax";

	$datos=$this->arrd01->execute("select * from arrd01 where cod_presi='$cod_presi' order by cod_presi asc");
	$this->set('datos',$datos);

	$this->set('k',$i);


}



function guardar_modificar($cod_presi=null,$i=null){
	$this->layout="ajax";

	if(empty($this->data['arrp00']['denominacion'.$i])){
		$this->set('errorMessage', 'Debe ingresar la denominación de la república');

	}else{
		$codigo=$this->data['arrp00']['codigo'.$i];
		$denominacion=$this->data['arrp00']['denominacion'.$i];

		 $sql = "update arrd01 set denominacion='".$denominacion."' where cod_presi='$cod_presi'";
	   	 $sw=$this->arrd01->execute($sql);
	   	 if($sw>1){
			$this->set('Message_existe', 'EL REGISTRO SE MODIFICO CON EXITO');
			echo" <script> ver_documento('/arrp01_republica','principal'); </script>";
   		}else{
   			$this->set('errorMessage', 'EL DATO NO PUDO SER MODIFICADO');
   		}

	}

}



function eliminar($cod_presi=null){
	$this->layout="ajax";

	 $sql = "delete from  arrd01 where cod_presi='$cod_presi'";
   	 $sw=$this->arrd01->execute($sql);
   	 if($sw>1){
		$this->set('Message_existe', 'EL REGISTRO SE ELIMINO CON EXITO');
		echo" <script> ver_documento('/arrp01_republica','principal'); </script>";
	}else{
		$this->set('errorMessage', 'EL DATO NO PUDO SER ELIMINADO');
	}

}



function cancelar($cod_presi=null){
	$this->layout="ajax";
	$this->index();
	$this->render('index');
}



}//FIN DEL CONTROLADOR
?>
