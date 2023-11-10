<?php
/*
 * Creado el 02/12/2007
 *
 * Miguelangel Cabrera
 * miguel4ngel@gmail.com
 *
 * 04:23:48 PM
 */
 class Cstp01sucursalesbancariasController extends AppController {
   var $name = 'cstp01_sucursales_bancarias';
   var $uses = array('cstd01_sucursales_bancarias','cstd01_entidades_bancarias','usuario');
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
 	$this->set('tipo_en',array());
 	$this->set('tipo_su',array());
 	$this->Session->delete('s_ent');
 	$this->concatena($this->cstd01_entidades_bancarias->generateList('',' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo_en');

	//$this->concatena($this->cstd01_sucursales_bancarias->generateList('',' cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion'), 'tipo_su');
	$this->set('enable', 'disabled');
 	}


 function select_entidad($id = null){
 	$this->layout ="ajax";

 	$this->set('tipo',Array());
 	$this->set('sel_en',$id);
 	$this->set('tipo_su',array());
 	$this->concatena($this->cstd01_entidades_bancarias->generateList(null,' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo_en');
	$this->concatena($this->cstd01_sucursales_bancarias->generateList('cod_entidad_bancaria='.$id,' cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion'), 'tipo_su');
 	$this->set('datos', $this->cstd01_entidades_bancarias->findAll('cod_entidad_bancaria='.$id));
 	$this->set('enable2', 'enabled');
 	$this->set('enable', 'disabled');
 	$this->set('read', 'readonly');
 	$this->Session->write('s_ent',$id);

 }

  function select_sucursal($id = null){
 	$this->layout ="ajax";
 	$this->set('tipo',Array());
 	$this->set('sel_en',$this->Session->read('s_ent'));
 	$this->set('sel_su',$id);
 	$this->set('tipo_su',array());
 	$this->concatena($this->cstd01_entidades_bancarias->generateList(null,' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo_en');
	$this->concatena($this->cstd01_sucursales_bancarias->generateList('cod_entidad_bancaria='.$this->Session->read('s_ent'),' cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion'), 'tipo_su');

 	if($id != 'otros'){
 			$this->set('otros', false);
 	 		$this->set('datos', $this->cstd01_entidades_bancarias->findAll('cod_entidad_bancaria='.$this->Session->read('s_ent')));
 	 		$this->set('datos_su', $this->cstd01_sucursales_bancarias->findAll('cod_entidad_bancaria='.$this->Session->read('s_ent').' and cod_sucursal='.$id));

 	 		$this->set('enable2', 'enabled');
 	 		$this->set('enable', 'disabled');
 	 		$this->set('read', 'readonly');

 	}else{
 		$this->set('datos', $this->cstd01_entidades_bancarias->findAll('cod_entidad_bancaria='.$this->Session->read('s_ent')));
 	 	$this->set('otros', true);
 		//$this->set('Message_existe', 'POR FAVOR INGRESE EL C&Oacute;DIGO');
 		$this->set('enable2', 'disabled');
 		$this->set('enable', 'enable');
 		$this->set('read', 'readonly');
 	}
 }

  function guardar(){
 	$this->layout ="ajax";
 	$this->set('enable2', 'disabled');
 	$this->set('enable', 'enable');
 	$this->set('enable', 'disabled');
	$cod_entidad = $this->data['cstp01_sucursales_bancarias']['codigo_entidad'];
	$cod_sucursal = $this->data['cstp01_sucursales_bancarias']['codigo_sucursal'];
	$denominacion = $this->data['cstp01_sucursales_bancarias']['denominacion_sucursal'];
	$consulta="select *from cstd01_sucursales_bancarias where cod_entidad_bancaria='$cod_entidad' and cod_sucursal='$cod_sucursal'";
	$sql="insert into cstd01_sucursales_bancarias values('$cod_entidad','$cod_sucursal','$denominacion')";
	//$sql="select *from cstd01_sucursales_bancarias";

	if($this->cstd01_sucursales_bancarias->execute($consulta)){
		$this->set('errorMessage','El c&oacute;digo de la entidad '.$cod_entidad.' ya existe');
		$this->concatena($this->cstd01_entidades_bancarias->generateList(null,' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo_en');
		$this->set('enable2', 'disabled');
 		$this->set('enable', 'enable');
 		$this->set('read', 'readonly');
	}else{
		if($this->cstd01_sucursales_bancarias->execute($sql)>1){
		$this->set('Message_existe','Los datos fueron guardados exitosamente');
		$this->concatena($this->cstd01_entidades_bancarias->generateList(null,' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo_en');
		$this->concatena($this->cstd01_sucursales_bancarias->generateList('cod_entidad_bancaria='.$this->Session->read('s_ent'),' cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion'), 'tipo_su');

		$this->set('read', 'readonly');
		$this->in();
	}else{
		$this->set('errorMessage','Los datos no fueron guardados');
	}//fin else insersion

	}//fin else consulta


	}//fin guardar

	 function modificar($su=null,$en=null){
 	$this->layout ="ajax";
 	$this->concatena($this->cstd01_entidades_bancarias->generateList('',' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo');
	$this->concatena($this->cstd01_sucursales_bancarias->generateList('cod_entidad_bancaria='.$en,' cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion'), 'tipo_su');

	$this->set('datos', $this->cstd01_entidades_bancarias->findAll('cod_entidad_bancaria='.$en));
	$this->set('datos_su', $this->cstd01_sucursales_bancarias->findAll('cod_entidad_bancaria='.$en.' and cod_sucursal='.$su));
 	$this->set('enable2', 'enabled');
 	$this->set('enable', 'disabled');
 	$this->set('read', 'readonly');
 	$this->set('sel', $en);
 	$this->set('sel_', $su);

 	}//fin modificar

 		 function modificar_consultar($su=null,$en=null){
 	$this->layout ="ajax";
 	$this->concatena($this->cstd01_entidades_bancarias->generateList('',' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo');
	$this->concatena($this->cstd01_sucursales_bancarias->generateList('cod_entidad_bancaria='.$en,' cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion'), 'tipo_su');

	$this->set('datos', $this->cstd01_entidades_bancarias->findAll('cod_entidad_bancaria='.$en));
	$this->set('datos_su', $this->cstd01_sucursales_bancarias->findAll('cod_entidad_bancaria='.$en.' and cod_sucursal='.$su));
 	$this->set('enable2', 'enabled');
 	$this->set('enable', 'disabled');
 	$this->set('read', 'readonly');
 	$this->set('sel', $en);
 	$this->set('sel_', $su);

 	}//fin modificar

 	 function guardar_modificar($entidad=null,$sucursal=null){
 	$this->layout ="ajax";

 	$this->set('enable2', 'enabled');
 	$this->set('enable', 'disabled');
 	$this->set('read', 'readonly');
 	$this->set('sel', $entidad);
 	$cod_entidad = $this->data['cstp01_sucursales_bancarias']['codigo_entidad'];
 	$cod_sucursal = $this->data['cstp01_sucursales_bancarias']['codigo_sucursal'];
	$denominacion = $this->data['cstp01_sucursales_bancarias']['denominacion_sucursal'];
	$sql="update cstd01_sucursales_bancarias set denominacion='$denominacion' where cod_entidad_bancaria='$entidad' and cod_sucursal='$cod_sucursal'";
	if($this->cstd01_sucursales_bancarias->execute($sql)>1){
		$this->set('Message_existe','Los datos fueron modificados exitosamente');
		$this->concatena($this->cstd01_entidades_bancarias->generateList('',' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo');
		$this->concatena($this->cstd01_sucursales_bancarias->generateList('cod_entidad_bancaria='.$cod_entidad,' cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion'), 'tipo_su');
		$this->set('datos', $this->cstd01_entidades_bancarias->findAll('cod_entidad_bancaria='.$cod_entidad));
		$this->set('sel', $cod_entidad);
		$this->set('read', 'readonly');
		$this->in();
	}else{
		$this->set('errorMessage','Los datos no fueron modificados');
	}//fin else actualizacion

 	}//fin guardar modificar

 		 function guardar_modificar_consultar($entidad=null,$sucursal=null){
 	$this->layout ="ajax";

 	$this->set('enable2', 'enabled');
 	$this->set('enable', 'disabled');
 	$this->set('read', 'readonly');
 	$this->set('sel', $entidad);
 	$cod_entidad = $this->data['cstp01_sucursales_bancarias']['codigo_entidad'];
 	$cod_sucursal = $this->data['cstp01_sucursales_bancarias']['codigo_sucursal'];
	$denominacion = $this->data['cstp01_sucursales_bancarias']['denominacion_sucursal'];
	$sql="update cstd01_sucursales_bancarias set denominacion='$denominacion' where cod_entidad_bancaria='$entidad' and cod_sucursal='$cod_sucursal'";
	if($this->cstd01_sucursales_bancarias->execute($sql)>1){
		$this->set('Message_existe','Los datos fueron modificados exitosamente');
		$this->concatena($this->cstd01_entidades_bancarias->generateList('',' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo');
		$this->concatena($this->cstd01_sucursales_bancarias->generateList('cod_entidad_bancaria='.$cod_entidad,' cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion'), 'tipo_su');
		$this->set('datos', $this->cstd01_entidades_bancarias->findAll('cod_entidad_bancaria='.$cod_entidad));
		$this->set('sel', $cod_entidad);
		$this->set('read', 'readonly');
		$this->in();
	}else{
		$this->set('errorMessage','Los datos no fueron modificados');
	}//fin else actualizacion
	$this->consultar();

 	}//fin guardar modificar


 	function eliminar($su=null, $en=null){
 	$this->layout ="ajax";
 	$this->set('enable2', 'enabled');
 	$this->set('enable', 'disabled');
 	$this->set('read', 'readonly');
 	$this->set('sel', $en);
 	$cod_entidad = $this->data['cstp01_sucursales_bancarias']['codigo_entidad'];
 	$cod_sucursal = $this->data['cstp01_sucursales_bancarias']['codigo_sucursal'];
	$denominacion = $this->data['cstp01_sucursales_bancarias']['denominacion_sucursal'];
	$sql="delete from cstd01_sucursales_bancarias where cod_entidad_bancaria='$en' and cod_sucursal='$su'";
	if($this->cstd01_sucursales_bancarias->execute($sql)>1){
		$this->set('Message_existe','Los datos fueron eliminados exitosamente');
		$this->concatena($this->cstd01_entidades_bancarias->generateList('',' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo');
		$this->concatena($this->cstd01_sucursales_bancarias->generateList('cod_entidad_bancaria='.$en,' cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion'), 'tipo_su');
		$this->set('datos', $this->cstd01_entidades_bancarias->findAll('cod_entidad_bancaria='.$en));
		$this->set('sel', $en);
		$this->set('read', 'readonly');
		$this->in();
	}else{
		$this->set('errorMessage','Los datos no fueron eliminados');
		$this->concatena($this->cstd01_entidades_bancarias->generateList('',' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo');

	}//fin else eliminar


 	}//fin eliminar

 	 	function eliminar_consultar($su=null, $en=null){
 	$this->layout ="ajax";
 	$this->set('enable2', 'enabled');
 	$this->set('enable', 'disabled');
 	$this->set('read', 'readonly');
 	$this->set('sel', $en);
 	$cod_entidad = $this->data['cstp01_sucursales_bancarias']['codigo_entidad'];
 	$cod_sucursal = $this->data['cstp01_sucursales_bancarias']['codigo_sucursal'];
	$denominacion = $this->data['cstp01_sucursales_bancarias']['denominacion_sucursal'];
	$sql="delete from cstd01_sucursales_bancarias where cod_entidad_bancaria='$en' and cod_sucursal='$su'";
	if($this->cstd01_sucursales_bancarias->execute($sql)>1){
		$this->set('Message_existe','Los datos fueron eliminados exitosamente');
		$this->concatena($this->cstd01_entidades_bancarias->generateList('',' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo');
		$this->concatena($this->cstd01_sucursales_bancarias->generateList('cod_entidad_bancaria='.$en,' cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion'), 'tipo_su');
		$this->set('datos', $this->cstd01_entidades_bancarias->findAll('cod_entidad_bancaria='.$en));
		$this->set('sel', $en);
		$this->set('read', 'readonly');
		$this->in();
	}else{
		$this->set('errorMessage','Los datos no fueron eliminados');
		$this->concatena($this->cstd01_entidades_bancarias->generateList('',' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo');

	}//fin else eliminar
$this->consultar();

 	}//fin eliminar




 function consultar ($pagina=null) {
	$this->layout="ajax";
	$this->set('enable2', 'enabled');
 	$this->set('enable', 'disabled');
 	$this->set('read', 'readonly');

	if(isset($pagina)){
		$Tfilas=$this->cstd01_sucursales_bancarias->findCount();
        if($Tfilas!=0){
        	$data=$this->cstd01_sucursales_bancarias->findAll(null,null,"cod_entidad_bancaria, cod_sucursal ASC",1,$pagina,null);
			$this->set('noExiste',false);
            $this->set('DATA',$data);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
            $this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
        }

	}else{
		$pagina=1;
		$Tfilas=$this->cstd01_sucursales_bancarias->findCount();

        if($Tfilas!=0){
        	$data=$this->cstd01_sucursales_bancarias->findAll(null,null,"cod_entidad_bancaria, cod_sucursal ASC",1,$pagina,null);
        	$this->set('noExiste',false);
			$this->set('DATA',$data);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	$this->bt_nav($Tfilas,$pagina);
          	$this->set('numT',$Tfilas);
			$this->set('numP',$pagina);

        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
        }
	}

		foreach($data as $datos){
		$cod_entidad=$datos['cstd01_sucursales_bancarias']['cod_entidad_bancaria'];
	    $this->set('cod_entidad', $cod_entidad);

	    $cod_sucursal=$datos['cstd01_sucursales_bancarias']['cod_sucursal'];
	    $this->set('cod_sucursal', $cod_sucursal);

	    $denominacion=$datos['cstd01_sucursales_bancarias']['denominacion'];
		$this->set('denominacion_sucursal', $denominacion);
	}

			$dataR=$this->cstd01_entidades_bancarias->findAll('cod_entidad_bancaria='.$cod_entidad);
			foreach($dataR as $datosR){
			$denominacion_entidad=  $datosR['cstd01_entidades_bancarias']['denominacion'];
	    	$this->set('denominacion_entidad',$denominacion_entidad);
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
 	$this->set('tipo_en',array());
 	$this->set('tipo_su',array());
 	$this->Session->delete('s_ent');
 	$this->concatena($this->cstd01_entidades_bancarias->generateList('',' cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion'), 'tipo_en');

	//$this->concatena($this->cstd01_sucursales_bancarias->generateList('',' cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion'), 'tipo_su');
	$this->set('enable', 'disabled');
 	}

 }
?>
