<?php
/*
 * Creado el 30/11/2007
 *
 * Miguelangel Cabrera
 * miguel4ngel@gmail.com
 *
 * 10:21:52 PM
 */

 class Cstp01entidadesbancariasController extends AppController {
   var $name = 'cstp01_entidades_bancarias';
   var $uses = array('cstd01_entidades_bancarias','usuario');
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

function zeros($x=null){
	if($x != null){
		if($x<10){
			$x="000".$x;
		}else if($x>=10 && $x<=99){
			$x="00".$x;
		}else if($x>=100 && $x<=999){
			$x="0".$x;
		}
	}
	return $x;

}

function concatena($vector1=null, $nomVar=null){

	if($vector1 != null){

		foreach($vector1 as $x => $y){

			$cod[$x] = $this->zeros($x).' - '.$y;

		}
		$this->set($nomVar, $cod);
	}
}
function in(){
	$this->concatena($this->cstd01_entidades_bancarias->generateList('',' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo_');
}


 function index(){
 	$this->layout ="ajax";
 	$x=$this->cstd01_entidades_bancarias->generateList('',' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion');
 	$x = $x==null ? '' : $x;
 	$this->concatena($x, 'tipo');
	$this->set('enable', 'disabled');

 }


 function select_entidad($id = null){
 	$this->layout ="ajax";

 	$this->set('tipo',Array());
 	$this->set('sel',$id);
 	$this->concatena($this->cstd01_entidades_bancarias->generateList(null,' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo');

 	if($id != 'otros'){
 			$this->set('guardar', false);
 	 		$this->set('datos', $this->cstd01_entidades_bancarias->findAll('cod_entidad_bancaria='.$id));
 	 		$this->set('enable2', 'enabled');
 	 		$this->set('enable', 'disabled');
 	 		$this->set('read', 'readonly');

 	}else{
 		$this->set('guardar', true);
 		//$this->set('Message_existe', 'POR FAVOR INGRESE EL C&Oacute;DIGO');
 		$this->set('enable2', 'disabled');
 		$this->set('enable', 'enable');
 		$this->set('read', '');
 	}
 }

  function guardar(){
 	$this->layout ="ajax";
 	$this->set('enable2', 'disabled');
 	$this->set('enable', 'enable');
 	$this->set('enable', 'disabled');
	$cod_entidad = $this->data['cstp01_entidades_bancarias']['codigo_entidad'];
	$denominacion = $this->data['cstp01_entidades_bancarias']['denominacion'];
	$consulta="select *from cstd01_entidades_bancarias where cod_entidad_bancaria='$cod_entidad'";
	$sql="insert into cstd01_entidades_bancarias values('$cod_entidad','$denominacion')";

	if($this->cstd01_entidades_bancarias->execute($consulta)){
		$this->set('errorMessage','El c&oacute;digo de la entidad '.$cod_entidad.' ya existe');
		$this->concatena($this->cstd01_entidades_bancarias->generateList(null,' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo');
		$this->set('enable2', 'disabled');
 		$this->set('enable', 'enable');
 		$this->set('read', 'readonly');
	}else{
		if($this->cstd01_entidades_bancarias->execute($sql)>1){
		$this->set('Message_existe','Los datos fueron guardados exitosamente');
		$this->concatena($this->cstd01_entidades_bancarias->generateList(null,' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo');

		$this->set('read', 'readonly');
		$this->in();
		}else{
		$this->set('errorMessage','Los datos no fueron guardados');
		}//fin else insersion

	}//fin else consulta

	}//fin guardar

	 function modificar($id=null){
 	$this->layout ="ajax";
 	$this->concatena($this->cstd01_entidades_bancarias->generateList('',' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo');
	$this->set('datos', $this->cstd01_entidades_bancarias->findAll('cod_entidad_bancaria='.$id));
 	$this->set('enable2', 'enabled');
 	$this->set('enable', 'disabled');
 	$this->set('read', 'readonly');
 	$this->set('sel', $id);

 	}//fin modificar

 	function modificar_consultar($id=null){
 	$this->layout ="ajax";
 	$this->concatena($this->cstd01_entidades_bancarias->generateList('',' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo');
	$this->set('datos', $this->cstd01_entidades_bancarias->findAll('cod_entidad_bancaria='.$id));
 	$this->set('enable2', 'enabled');
 	$this->set('enable', 'disabled');
 	$this->set('read', 'readonly');
 	$this->set('sel', $id);

 	}//fin modificar

 	function guardar_modificar($id=null){
 	$this->layout ="ajax";

 	$this->set('enable2', 'enabled');
 	$this->set('enable', 'disabled');
 	$this->set('read', 'readonly');
 	$this->set('sel', $id);
 	$cod_entidad = $this->data['cstp01_entidades_bancarias']['codigo_entidad'];
	$denominacion = $this->data['cstp01_entidades_bancarias']['denominacion'];
	$sql="update cstd01_entidades_bancarias set denominacion='$denominacion' where cod_entidad_bancaria='$cod_entidad'";
	if($this->cstd01_entidades_bancarias->execute($sql)>1){
		$this->set('Message_existe','Los datos fueron modificados exitosamente');
		$this->concatena($this->cstd01_entidades_bancarias->generateList(null,' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo');

		$this->set('read', 'readonly');
		$this->in();
	}else{
		$this->set('errorMessage','Los datos no fueron modificados');
	}//fin else actualizacion

 	}//fin guardar modificar

 	function guardar_modificar_consultar($id=null){
 	$this->layout ="ajax";

 	$this->set('enable2', 'enabled');
 	$this->set('enable', 'disabled');
 	$this->set('read', 'readonly');
 	$this->set('sel', $id);
 	$cod_entidad = $this->data['cstp01_entidades_bancarias']['codigo_entidad'];
	$denominacion = $this->data['cstp01_entidades_bancarias']['denominacion'];
	$sql="update cstd01_entidades_bancarias set denominacion='$denominacion' where cod_entidad_bancaria='$cod_entidad'";
	if($this->cstd01_entidades_bancarias->execute($sql)>1){
		$this->set('Message_existe','Los datos fueron modificados exitosamente');
		$this->concatena($this->cstd01_entidades_bancarias->generateList(null,' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo');

		$this->set('read', 'readonly');
		$this->in();

	}else{
		$this->set('errorMessage','Los datos no fueron modificados');
	}//fin else actualizacion
	$this->consultar();
 	}//fin guardar modificar


 	function eliminar($id=null){
 	$this->layout ="ajax";
 	$this->set('enable2', 'enabled');
 	$this->set('enable', 'disabled');
 	$this->set('read', 'readonly');
 	$this->set('sel', $id);
 	$cod_entidad = $this->data['cstp01_entidades_bancarias']['codigo_entidad'];
	$denominacion = $this->data['cstp01_entidades_bancarias']['denominacion'];
	$sql="delete from cstd01_entidades_bancarias where cod_entidad_bancaria='$id'";
	if($this->cstd01_entidades_bancarias->execute($sql)>1){
		$this->set('Message_existe','Los datos fueron eliminados exitosamente');
		$this->concatena($this->cstd01_entidades_bancarias->generateList(null,' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo');

		$this->set('read', 'readonly');
		$this->in();
	}else{
		$this->set('errorMessage','Los datos no fueron eliminados');
	}//fin else actualizacion

 	}//fin guardar modificar

 	function eliminar_consultar($id=null){
 	$this->layout ="ajax";
 	$this->set('enable2', 'enabled');
 	$this->set('enable', 'disabled');
 	$this->set('read', 'readonly');
 	$this->set('sel', $id);
 	$cod_entidad = $this->data['cstp01_entidades_bancarias']['codigo_entidad'];
	$denominacion = $this->data['cstp01_entidades_bancarias']['denominacion'];
	$sql="delete from cstd01_entidades_bancarias where cod_entidad_bancaria='$id'";
	if($this->cstd01_entidades_bancarias->execute($sql)>1){
		$this->set('Message_existe','Los datos fueron eliminados exitosamente');
		$this->concatena($this->cstd01_entidades_bancarias->generateList(null,' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo');

		$this->set('read', 'readonly');
		$this->in();
	}else{
		$this->set('errorMessage','Los datos no fueron eliminados');
	}//fin else actualizacion
	$this->consultar();
 	}//fin guardar modificar

 function consultar ($pagina=null) {
	$this->layout="ajax";
	$this->set('enable2', 'enabled');
 	$this->set('enable', 'disabled');
 	$this->set('read', 'readonly');
	if(isset($pagina)){
		$Tfilas=$this->cstd01_entidades_bancarias->findCount();
        if($Tfilas!=0){
        	$data=$this->cstd01_entidades_bancarias->findAll(null,null,"cod_entidad_bancaria ASC",1,$pagina,null);

            $this->set('DATA',$data);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
            $this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
			$this->Session->delete('pagina');
			$this->Session->write('pagina',$pagina);
        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
        }

	}else{
		$pagina=1;
		$Tfilas=$this->cstd01_entidades_bancarias->findCount();
        if($Tfilas!=0){
        	$data=$this->cstd01_entidades_bancarias->findAll(null,null,"cod_entidad_bancaria ASC",1,$pagina,null);
			$this->set('DATA',$data);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	$this->bt_nav($Tfilas,$pagina);
          	$this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
			$this->Session->delete('pagina');
			$this->Session->write('pagina',$pagina);
        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
        }
	}

		foreach($data as $datos){
	    $cod_entidad=$datos['cstd01_entidades_bancarias']['cod_entidad_bancaria'];
	    $this->set('cod_entidad', $cod_entidad);
		$denominacion=$datos['cstd01_entidades_bancarias']['denominacion'];
		$this->set('denominacion', $denominacion);


	}

}

function bt_nav($Tfilas,$pagina){
    if($Tfilas==1){
                $this->set('mostrarS',false);
                $this->set('mostrarA',false);
          	}else if($Tfilas==2){
          		if($pagina==2){
                   $this->set('mostrarS',false);
                   $this->set('mostrarA',true);
          		}else{
          		   $this->set('mostrarS',true);
                   $this->set('mostrarA',false);
          		}
          	}else if($Tfilas>=3){
          		if($pagina==$Tfilas){
                     $this->set('mostrarS',false);
                     $this->set('mostrarA',true);
          		}else if($pagina==1){
          			 $this->set('mostrarS',true);
                     $this->set('mostrarA',false);
          		}else{
          			 $this->set('mostrarS',true);
                     $this->set('mostrarA',true);
          		}
          	}
 }//fin navegacion

 function salir(){
 	$this->layout ="ajax";
 	$this->concatena($this->cstd01_entidades_bancarias->generateList('',' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo');
	$this->set('enable', 'disabled');
 	}

 }
?>
