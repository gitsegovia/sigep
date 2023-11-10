<?php
class Casp01SolicitudAyudasController extends AppController {
   var $name = 'casp01_solicitud_ayudas';
   var $uses = array('casd01_datos_personales','casd01_datos_familiares','cugd02_institucion','cugd02_dependencia',
   					'casd01_ayudas_cuerpo','casd01_tipo_ayuda','casd01_ayuda_detalles','casd01_evaluacion_ayuda','casd01_solicitud_ayuda',
   					'v_historia_solicitud_ayudas');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap');

function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
}//fin checksession

 function verifica_SS($i){
    	/**
    	 * Funcion que permite leer las varibles de session. la cual permite capturar los codigos del usuario
    	 * para ser insertados en todas las tablas.
    	 * */
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
				 $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
				 if($ano!=null){
					 $sql_re .= "cod_dep=".$this->verifica_SS(5)."  and  ";
						$sql_re .= "ano=".$ano."  ";
				 }else{
					 $sql_re .= "cod_dep=".$this->verifica_SS(5)." ";
				 }
				 return $sql_re;
		}//fin funcion SQLCA


 function beforeFilter(){
 	$this->checkSession();
	$paso=explode('/',$this->params['url']['url']);
 	if($_SESSION["ATS_autorizados"][$paso[0]]==2){
 		$this->Session->write('errorMessage', 'usted no esta autorizado para operar este programa');
 		$this->redirect('modulos/vacio');
 	}
 }


 function index(){
 	$this->layout ="ajax";

 $cedula=$this->Session->read('cedula_pestana_atencion');

$sql="select cedula_identidad,apellidos_nombres from casd01_datos_personales where cedula_identidad='$cedula'";
	$dato=$this->casd01_datos_personales->execute($sql);
	if($dato!=null){
		$this->set('dato',$dato);

		$sql1="select * from casd01_solicitud_ayuda where ".$this->SQLCA()." and cedula_identidad='$cedula' order by fecha_solicitud asc";
		$dato1=$this->casd01_solicitud_ayuda->execute($sql1);
		if($dato1!=null){
			$this->set('dato1',$dato1);
		}else{
			$this->set('dato1','');
		}


		echo "<script>";
			echo "document.getElementById('tipo_ayuda').disabled=false;";
			echo "document.getElementById('agregar').disabled=false;";
		echo "</script>";
	}else{
		$this->set('dato','');
		$this->set('errorMessage', 'no existe una persona con esta cedula');
		echo "<script>";
			echo "document.getElementById('tipo_ayuda').disabled='disabled';";
			echo "document.getElementById('agregar').disabled='disabled';";
		echo "</script>";

	}


	$tipo_ayuda=$this->casd01_tipo_ayuda->generateList(null,'denominacion ASC', null, '{n}.casd01_tipo_ayuda.cod_tipo_ayuda', '{n}.casd01_tipo_ayuda.denominacion');
	$this->set('tipo_ayuda',$tipo_ayuda);


	$this->set('ayu',$this->casd01_tipo_ayuda->FindAll());

 }// fin index



function busqueda_cedula($cedula){
	$this->layout="ajax";

	$sql="select cedula_identidad,apellidos_nombres from casd01_datos_personales where cedula_identidad='$cedula'";
	$dato=$this->casd01_datos_personales->execute($sql);
	if($dato!=null){
		$this->set('dato',$dato);

		$sql1="select * from casd01_solicitud_ayuda where ".$this->SQLCA()." and cedula_identidad='$cedula' order by fecha_solicitud asc";
		$dato1=$this->casd01_solicitud_ayuda->execute($sql1);
		if($dato1!=null){
			$this->set('dato1',$dato1);
		}else{
			$this->set('dato1','');
		}

		echo "<script>";
			echo "document.getElementById('tipo_ayuda').disabled=false;";
			echo "document.getElementById('agregar').disabled=false;";
		echo "</script>";
	}else{
		$this->set('dato','');
		$this->set('errorMessage', 'no existe una persona con esta cedula');
		echo "<script>";
			echo "document.getElementById('tipo_ayuda').disabled='disabled';";
			echo "document.getElementById('agregar').disabled='disabled';";
		echo "</script>";

	}


	$tipo_ayuda=$this->casd01_tipo_ayuda->generateList(null,'denominacion ASC', null, '{n}.casd01_tipo_ayuda.cod_tipo_ayuda', '{n}.casd01_tipo_ayuda.denominacion');
	$this->set('tipo_ayuda',$tipo_ayuda);


	$this->set('ayu',$this->casd01_tipo_ayuda->FindAll());

}//fin busqueda_cedula




function buscar_datos($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin buscar_ficha



function buscar_por_pista($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
    if($var3==null){
    	$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					$Tfilas=$this->casd01_datos_personales->findCount("cedula_identidad::text LIKE '%$var2%' or upper(apellidos_nombres::text) LIKE upper('%".$var2."%') ");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->casd01_datos_personales->findAll("cedula_identidad::text LIKE '%$var2%' or upper(apellidos_nombres::text) LIKE upper('%".$var2."%')",null,"cedula_identidad ASC",100,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);
						$Tfilas=$this->casd01_datos_personales->findCount("cedula_identidad::text LIKE '%$var22%' or upper(apellidos_nombres::text) LIKE upper('%".$var22."%')");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
									$datos_filas=$this->casd01_datos_personales->findAll("cedula_identidad::text LIKE '%$var2%' or upper(apellidos_nombres::text) LIKE upper('%".$var22."%')",null,"cedula_identidad ASC",100,1,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else


	$this->set("opcion",$var1);
}//fin function




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




function seleccion_busqueda($opcion=null,$cedula=null){
	$this->layout="ajax";

	$sql="select cedula_identidad,apellidos_nombres from casd01_datos_personales where cedula_identidad='$cedula'";
	$dato=$this->casd01_datos_personales->execute($sql);
	if($dato!=null){
		$this->set('dato',$dato);

		$sql1="select * from casd01_solicitud_ayuda where ".$this->SQLCA()." and cedula_identidad='$cedula' order by fecha_solicitud asc";
		$dato1=$this->casd01_solicitud_ayuda->execute($sql1);
		if($dato1!=null){
			$this->set('dato1',$dato1);
		}else{
			$this->set('dato1','');
		}

		echo "<script>";
			echo "document.getElementById('tipo_ayuda').disabled=false;";
			echo "document.getElementById('agregar').disabled=false;";
		echo "</script>";
	}else{
		$this->set('dato','');
		$this->set('errorMessage', 'no existe una persona con esta cedula');
		echo "<script>";
			echo "document.getElementById('tipo_ayuda').disabled='disabled';";
			echo "document.getElementById('agregar').disabled='disabled';";
		echo "</script>";

	}

	$tipo_ayuda=$this->casd01_tipo_ayuda->generateList(null,'denominacion ASC', null, '{n}.casd01_tipo_ayuda.cod_tipo_ayuda', '{n}.casd01_tipo_ayuda.denominacion');
	$this->set('tipo_ayuda',$tipo_ayuda);


	$this->set('ayu',$this->casd01_tipo_ayuda->FindAll());

}// fin seleccion_busqueda


function historia($cedula=null){
	$this->layout = "ajax";

	$sql2="BEGIN;select * from v_historia_solicitud_ayudas where cedula_identidad='$cedula' and 1=1 order by cod_presi_solicitud,cod_entidad_solicitud,cod_tipo_inst_solicitud,cod_inst_solicitud,cod_dep_solicitud,cedula_identidad,numero_ocacion asc";
	$dato2=$this->v_historia_solicitud_ayudas->execute($sql2);
	if($dato2!=null){
		$this->v_historia_solicitud_ayudas->execute("COMMIT");
		$this->set('dato2',$dato2);
	}else{
		$this->v_historia_solicitud_ayudas->execute("ROLLBACK");
		$this->set('dato2','');
	}
}
function guardar($cedula){
	$this->layout = "ajax";

	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst = $this->Session->read('SScodinst');
    $cod_dep = $this->Session->read('SScoddep');

	if(empty($this->data['casp01']['tipo_ayuda']) || empty($this->data['casp01']['concepto_ayuda'])){
		$this->set('errorMessage', 'Debe ingresar los datos requeridos');
	}else {
		$tipo_ayuda=$this->data['casp01']['tipo_ayuda'];
		$concepto=$this->data['casp01']['concepto_ayuda'];
		$fecha_solicitud=$this->data['casp01']['fecha_solicitud'];

		$ver=$this->casd01_solicitud_ayuda->execute("select * from casd01_solicitud_ayuda where ".$this->SQLCA()." and cedula_identidad='$cedula' order by  numero_ocacion desc limit 1");
		if($ver!=null)
			$ocacion=$ver[0][0]['numero_ocacion']+1;
		else
			$ocacion=1;

		$username=$this->Session->read('nom_usuario');
		$campos=$this->casd01_datos_personales->execute("select * from usuarios where username='$username'");
		if($campos[0][0]['cedula_identidad']!=null){
			$cedula_usuario=$campos[0][0]['cedula_identidad'];
		}else{
			$cedula_usuario=0;
		}
		$funcionario=$campos[0][0]['funcionario'];

		$sql_insert = "INSERT INTO casd01_solicitud_ayuda (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cedula_identidad,cod_tipo_ayuda,numero_ocacion,ayuda_solicitada,fecha_solicitud,username,cedula_usuario,nombre_usuario) VALUES ('$cod_presi','$cod_entidad','$cod_tipo_inst','$cod_inst','$cod_dep','$cedula', '$tipo_ayuda','$ocacion','$concepto', '$fecha_solicitud','$username','$cedula_usuario','$funcionario')";
		$sw2 = $this->casd01_solicitud_ayuda->execute($sql_insert);
		if($sw2>1){
		 	$this->set('Message_existe', 'SOLICITUD AGREGADA CON EXITO');
		 }else{
		 	$this->set('errorMessage', 'ERROR EN LA INSERCI&Oacute;N DEL DATO');
		 }

	}
		$this->set('cedula',$cedula);
		$sql1="select * from casd01_solicitud_ayuda where ".$this->SQLCA()." and cedula_identidad='$cedula' order by fecha_solicitud asc";
		$dato1=$this->casd01_solicitud_ayuda->execute($sql1);
		if($dato1!=null){
			$this->set('dato1',$dato1);
		}else{
			$this->set('dato1','');
		}

	$tipo_ayuda=$this->casd01_tipo_ayuda->generateList(null,'denominacion ASC', null, '{n}.casd01_tipo_ayuda.cod_tipo_ayuda', '{n}.casd01_tipo_ayuda.denominacion');
	$this->set('tipo_ayuda',$tipo_ayuda);


	$this->set('ayu',$this->casd01_tipo_ayuda->FindAll());

	$this->data=null;
	$this->data['casp01']['concepto_ayuda']=null;

}// fin guardar



function verifica($cedula=null,$tipo_ayuda=null){
	  $this->layout = "ajax";

		$dato1=$this->casd01_solicitud_ayuda->execute("select * from casd01_solicitud_ayuda where ".$this->SQLCA()." and cedula_identidad='$cedula' and cod_tipo_ayuda='$tipo_ayuda' and numero_documento_evaluacion is null and numero_documento_ayuda is null ");
		if($dato1!=null){
			$this->set('dato1',$dato1);
			$this->set('errorMessage', 'YA EXISTE UNA SOLICITUD SIMILAR EN PROCESO PARA ESTA PERSONA');
			echo "<script>";
				echo "document.getElementById('agregar').disabled='disabled';";
				echo "if(document.getElementById('b_modificar'))document.getElementById('b_modificar').disabled='disabled';";
			echo "</script>";
		}else{
			echo "<script>";
					echo "document.getElementById('agregar').disabled=false;";
					echo "if(document.getElementById('b_modificar'))document.getElementById('b_modificar').disabled=false;";
			echo "</script>";
		}

}//fin verifica



function eliminar($cedula=null,$tipo_ayuda=null,$ocacion=null){
	    $this->layout = "ajax";
		  $x = $this->casd01_solicitud_ayuda->execute("BEGIN;DELETE FROM casd01_solicitud_ayuda  WHERE ".$this->SQLCA()." and cedula_identidad='$cedula' and cod_tipo_ayuda='$tipo_ayuda' and numero_ocacion='$ocacion' and numero_documento_evaluacion is null and numero_documento_ayuda is null");
		  if($x>1){
		  	$this->casd01_solicitud_ayuda->execute("COMMIT");
		  	$this->set('Message_existe','registro eliminado con exito');
		  }else{
		  	$this->casd01_datos_familiares->execute("ROLLBACK");
		  	$this->set('errorMessage', 'EL DATO NO PUDO SER ELIMINADO');
		  }

		$tipo_ayuda=$this->casd01_tipo_ayuda->generateList(null,'denominacion ASC', null, '{n}.casd01_tipo_ayuda.cod_tipo_ayuda', '{n}.casd01_tipo_ayuda.denominacion');
		$this->set('tipo_ayuda',$tipo_ayuda);

		$sql1="select * from casd01_solicitud_ayuda where ".$this->SQLCA()." and cedula_identidad='$cedula' order by fecha_solicitud asc";
		$dato1=$this->casd01_solicitud_ayuda->execute($sql1);
		if($dato1!=null){
			$this->set('dato1',$dato1);
		}else{
			$this->set('dato1','');
		}

		$this->set('cedula',$cedula);

		$this->set('ayu',$this->casd01_tipo_ayuda->FindAll());

		$this->data=null;
		$this->data['casp01']['concepto_ayuda']=null;
}//fin function




 function modificar($cedula=null,$tipo_ayuda=null,$ocacion=null,$i=null){
 	 $this->layout = "ajax";
    $sql_1="SELECT * from casd01_solicitud_ayuda where ".$this->SQLCA()." and cedula_identidad='$cedula' and cod_tipo_ayuda='$tipo_ayuda' and numero_ocacion='$ocacion'";
	$result_1=$this->casd01_solicitud_ayuda->execute($sql_1);
	$this->set('dato',$result_1);

	$this->set('k',$i);

	$tipo_ayuda=$this->casd01_tipo_ayuda->generateList(null,'denominacion ASC', null, '{n}.casd01_tipo_ayuda.cod_tipo_ayuda', '{n}.casd01_tipo_ayuda.denominacion');
	$this->set('tipo_ayuda',$tipo_ayuda);


	$this->set('ayu',$this->casd01_tipo_ayuda->FindAll());

 }// fin modificar_items





function guardar_modificar($cedula=null,$tipo_ayuda1=null,$ocacion1=null,$i=null){
	$this->layout = "ajax";
	if(empty($this->data['casp01']['tipo_ayuda'.$i]) || empty($this->data['casp01']['concepto_ayuda'.$i])){
		$this->set('errorMessage', 'Debe ingresar los datos requeridos');
	}else {
		$tipo_ayuda=$this->data['casp01']['tipo_ayuda'.$i];
		$fecha_solicitud=$this->data['casp01']['fecha_solicitud'.$i];
		$concepto_ayuda=$this->data['casp01']['concepto_ayuda'.$i];

		$sql = "BEGIN;UPDATE casd01_solicitud_ayuda SET cod_tipo_ayuda='$tipo_ayuda',fecha_solicitud='$fecha_solicitud',ayuda_solicitada='$concepto_ayuda' where ".$this->SQLCA()." and cedula_identidad='$cedula' and cod_tipo_ayuda='$tipo_ayuda1' and numero_ocacion='$ocacion1'";
		$sw=$this->casd01_solicitud_ayuda->execute($sql);
		if($sw>1){
			$this->set('Message_existe','EL REGISTRO SE MODIFICO EXITOSAMENTE');
			$this->casd01_solicitud_ayuda->execute("COMMIT");
		}else{
			$this->set('errorMessage', 'LOS DATOS NO PUDIERON SER MODIFICADOS');
			$this->casd01_solicitud_ayuda->execute("ROLLBACK");
		}

	}


	$sql1="select * from casd01_solicitud_ayuda where ".$this->SQLCA()." and cedula_identidad='$cedula' order by fecha_solicitud asc";
		$dato1=$this->casd01_solicitud_ayuda->execute($sql1);
		if($dato1!=null){
			$this->set('dato1',$dato1);
		}else{
			$this->set('dato1','');
		}

	$tipo_ayuda=$this->casd01_tipo_ayuda->generateList(null,'denominacion ASC', null, '{n}.casd01_tipo_ayuda.cod_tipo_ayuda', '{n}.casd01_tipo_ayuda.denominacion');
	$this->set('tipo_ayuda',$tipo_ayuda);


	$this->set('ayu',$this->casd01_tipo_ayuda->FindAll());

	$this->data=null;
	$this->data['casp01']['concepto_ayuda']=null;

}//fin guardar_items_modificar



function cancelar($cedula=nulll){
    $this->layout = "ajax";

    $sql1="select * from casd01_solicitud_ayuda where ".$this->SQLCA()." and cedula_identidad='$cedula' order by fecha_solicitud asc";
		$dato1=$this->casd01_solicitud_ayuda->execute($sql1);
		if($dato1!=null){
			$this->set('dato1',$dato1);
		}else{
			$this->set('dato1','');
		}

	$tipo_ayuda=$this->casd01_tipo_ayuda->generateList(null,'denominacion ASC', null, '{n}.casd01_tipo_ayuda.cod_tipo_ayuda', '{n}.casd01_tipo_ayuda.denominacion');
	$this->set('tipo_ayuda',$tipo_ayuda);


	$this->set('ayu',$this->casd01_tipo_ayuda->FindAll());

	$this->data=null;
	$this->data['casp01']['concepto_ayuda']=null;

}//fin cancelar


 }//Fin de la clase controller
 ?>