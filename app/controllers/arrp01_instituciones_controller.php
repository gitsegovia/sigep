<?php
/*
 * Fecha: 06/07/2007
 *
 * Por Erisk Aragol
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */

 class Arrp01InstitucionesController extends AppController{

	var $name = 'arrp01_instituciones';
	var $uses = array('arrd01','arrd02','arrd03','arrd04','Usuario');
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


function select3($opcion=null,$var=null){
	$this->layout="ajax";
	if($var!=''){
		switch($opcion){
			case 'estado':
				$this->set('no','');
				$this->set('SELECT','tipo');
				$this->set('codigo','estado');
				$this->set('seleccion','');
				$this->set('n',2);
				$this->Session->write('presi',$var);
				$cond =" cod_presi=".$var;
				$lista=  $this->arrd02->generateList($cond, 'cod_entidad ASC', null, '{n}.arrd02.cod_entidad', '{n}.arrd02.denominacion');
				$this->concatena($lista, 'vector');
			break;
			case 'tipo':
				$this->set('no','no');
				$this->set('SELECT','tipo_inst');
				$this->set('codigo','tipo');
				$this->set('seleccion','');
				$this->set('n',3);
				$this->Session->write('entidad',$var);
				$cod_presi=$this->Session->read('presi');
				$cond =" cod_presi=".$cod_presi;
				$lista=  $this->arrd03->generateList($cond, 'cod_tipo_inst ASC', null, '{n}.arrd03.cod_tipo_inst', '{n}.arrd03.denominacion');
				$this->concatena($lista, 'vector');
			break;
		}//fin switch
	}
}//fin select3



function mostrar($opcion=null,$var=null){
	$this->layout="ajax";
	$cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	if($var!=''){
		switch($opcion){
			case 'republica':
				$this->set('si','si');
				$this->set('codigo','republica');
				$this->set('valor',$var);
			break;
			case 'estado':
				$this->set('si','si');
				$this->set('codigo','estado');
				$this->set('valor',$var);
			break;
			case 'tipo':
				$this->set('si','si');
				$this->set('codigo','tipo');
				$this->set('valor',$var);
			break;
			case 'deno_republica':
				$deno_estado = $this->arrd01->field('denominacion', $conditions = "cod_presi=".$var, $order ="cod_presi ASC");
				$this->set('denomi', $deno_estado);
				$this->set('denominacion',$opcion);
				 echo "<script>";
				 	echo "document.getElementById('cod_estadox').value='';";
					echo "document.getElementById('deno_estadox').value='';";
					echo "document.getElementById('cod_tipox').value='';";
					echo "document.getElementById('deno_tipox').value='';";
					echo "document.getElementById('codigo').value='';";
					echo "document.getElementById('denominacion').value='';";
				 echo "</script>";
			break;
			case 'deno_estado':
				$cod_presi=$this->Session->read('presi');
				$deno_estado = $this->arrd02->field('denominacion', $conditions = "cod_presi=".$cod_presi." and cod_entidad='$var'", $order ="cod_entidad ASC");
				$this->set('denomi', $deno_estado);
				$this->set('denominacion',$opcion);
				  echo "<script>";
					echo "document.getElementById('cod_tipox').value='';";
					echo "document.getElementById('deno_tipox').value='';";
					echo "document.getElementById('codigo').value='';";
					echo "document.getElementById('denominacion').value='';";
				 echo "</script>";
			break;
			case 'deno_tipo':
				$cod_presi=$this->Session->read('presi');
				$deno_municipio = $this->arrd03->field('denominacion', $conditions = "cod_presi=".$cod_presi." and cod_tipo_inst='$var'", $order ="cod_tipo_inst ASC");
				$this->set('denomi', $deno_municipio);
				$this->set('denominacion',$opcion);
				 echo "<script>";
					echo "document.getElementById('codigo').value='';";
					echo "document.getElementById('denominacion').value='';";
				 echo "</script>";
			break;
		}// fin switch
	}else{
		$this->set('si','no');
	}
}// fin mostrar


function vacio(){
	$this->layout="ajax";
}

function datos($cod_tipo_inst=null){
	$this->layout="ajax";

	if(isset($_SESSION['presi']) && isset($_SESSION['entidad']) && $cod_tipo_inst!=''){
		$cod_presi=$this->Session->read('presi');
		$entidad=$this->Session->read('entidad');
		$datos=$this->arrd04->execute("select * from arrd04 where cod_presi='$cod_presi' and cod_entidad='$entidad' and cod_tipo_inst='$cod_tipo_inst' order by cod_inst asc");
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



function datos2($cod_presi=null,$entidad=null,$cod_tipo_inst=null){
	$this->layout="ajax";

	if($cod_tipo_inst!=''){
		$datos=$this->arrd04->execute("select * from arrd04 where cod_presi='$cod_presi' and cod_entidad='$entidad' and cod_tipo_inst='$cod_tipo_inst' order by cod_inst asc");
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

	}else if(empty($this->data['arrp00']['cod_estado'])){
		$this->set('errorMessage', 'Debe seleccionar el estado');

	}else if(empty($this->data['arrp00']['cod_tipo'])){
		$this->set('errorMessage', 'Debe seleccionar el tipo de institución');

	}else if(empty($this->data['arrp00']['codigo'])){
		$this->set('errorMessage', 'Debe ingresar el código de la institución');

	}else if(empty($this->data['arrp00']['denominacion'])){
		$this->set('errorMessage', 'Debe ingresar la denominación de la institución');

	}else{
		$cod_presi=$this->data['arrp00']['cod_republica'];
		$cod_estado=$this->data['arrp00']['cod_estado'];
		$cod_tipo=$this->data['arrp00']['cod_tipo'];
		$codigo=$this->data['arrp00']['codigo'];
		$denominacion=$this->data['arrp00']['denominacion'];
		$datos=$this->arrd04->execute("select * from arrd04 where cod_presi='$cod_presi' and cod_entidad='$cod_estado' and cod_tipo_inst='$cod_tipo' and cod_inst='$codigo' order by cod_inst asc");
		if($datos==null){
			 $sql = "INSERT INTO arrd04 VALUES ('$cod_presi','$cod_estado','$cod_tipo','$codigo','$denominacion')";
		   	 $sw=$this->arrd04->execute($sql);
		   	 if($sw>1){
				$this->set('Message_existe', 'REGISTRO EXITOSO');
				echo" <script> ver_documento('/arrp01_instituciones/datos2/$cod_presi/$cod_estado/$cod_tipo','grilla'); </script>";
	   		}else{
	   			$this->set('errorMessage', 'INSERCI&Oacute;N FALLIDA DE LOS DATOS');
	   		}
		}else{
			$this->set('errorMessage', 'este dato ya existe registrado');
		}



	}
}




function modificar($cod_presi=null,$cod_estado=null,$cod_tipo=null,$cod_inst=null,$i=null){
	$this->layout="ajax";

	$datos=$this->arrd04->execute("select * from arrd04 where cod_presi='$cod_presi' and cod_entidad='$cod_estado' and cod_tipo_inst='$cod_tipo' and cod_inst='$cod_inst' order by cod_inst asc");
	$this->set('datos',$datos);

	$this->set('k',$i);


}



function guardar_modificar($cod_presi=null,$cod_estado=null,$cod_tipo=null,$cod_inst=null,$i=null){
	$this->layout="ajax";

	if(empty($this->data['arrp00']['denominacion'.$i])){
		$this->set('errorMessage', 'Debe ingresar la denominación de la institución');

	}else{
		$codigo=$this->data['arrp00']['codigo'.$i];
		$denominacion=$this->data['arrp00']['denominacion'.$i];

		 $sql = "update arrd04 set denominacion='".$denominacion."' where cod_presi='$cod_presi' and cod_entidad='$cod_estado' and cod_tipo_inst='$cod_tipo' and cod_inst='$cod_inst'";
	   	 $sw=$this->arrd04->execute($sql);
	   	 if($sw>1){
			$this->set('Message_existe', 'EL REGISTRO SE MODIFICO CON EXITO');
			echo" <script> ver_documento('/arrp01_instituciones/datos2/$cod_presi/$cod_estado/$cod_tipo','grilla'); </script>";
   		}else{
   			$this->set('errorMessage', 'EL DATO NO PUDO SER MODIFICADO');
   		}

	}

}



function eliminar($cod_presi=null,$cod_estado=null,$cod_tipo=null,$cod_inst=null){
	$this->layout="ajax";

	 $sql = "delete from arrd04 where cod_presi='$cod_presi' and cod_entidad='$cod_estado' and cod_tipo_inst='$cod_tipo' and cod_inst='$cod_inst'";
   	 $sw=$this->arrd03->execute($sql);
   	 if($sw>1){
		$this->set('Message_existe', 'EL REGISTRO SE ELIMINO CON EXITO');
		echo" <script> ver_documento('/arrp01_instituciones/datos2/$cod_presi/$cod_estado/$cod_tipo','grilla'); </script>";
	}else{
		$this->set('errorMessage', 'EL DATO NO PUDO SER ELIMINADO');
	}

}



function cancelar($cod_presi=null,$cod_estado=null,$cod_tipo=null){
	$this->layout="ajax";
	echo" <script> ver_documento('/arrp01_instituciones/datos2/$cod_presi/$cod_estado/$cod_tipo','grilla'); </script>";
}



}//FIN DEL CONTROLADOR
?>
