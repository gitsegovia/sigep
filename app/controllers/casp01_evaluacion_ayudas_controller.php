<?php
class Casp01EvaluacionAyudasController extends AppController {
   var $name = 'casp01_evaluacion_ayudas';
   var $uses = array('casd01_datos_personales','casd01_datos_familiares','cugd02_institucion','cugd02_dependencia',
   					'casd01_ayudas_cuerpo','casd01_ayuda_detalles','casd01_evaluacion_ayuda','casd01_solicitud_ayuda',
   					'v_historia_solicitud_ayudas','casd01_tipo_ayuda');
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


function denominacion1($ayuda){

	$v=$this->casd01_tipo_ayuda->execute("select denominacion from casd01_tipo_ayuda where cod_tipo_ayuda='$ayuda'");

return $v[0][0]['denominacion'];
}


function concatena11($vector1=null, $nomVar=null){

	if($vector1 != null){

		foreach($vector1 as $x => $y){

			$cod[$x] = $this->zero($x).' - '.$this->denominacion1($y);
		}

		$this->set($nomVar, $cod);

	}
}//fin concatena



 function index(){
 	$this->layout ="ajax";

	$cedula= $this->Session->read('cedula_pestana_atencion');
	$sql="select cedula_identidad,apellidos_nombres from casd01_datos_personales where cedula_identidad='$cedula'";
	$dato=$this->casd01_datos_personales->execute($sql);
	if($dato!=null){
		$this->set('dato',$dato);


		$num_ayuda=$this->casd01_solicitud_ayuda->generateList($this->SQLCA()." and cedula_identidad=".$cedula." and numero_documento_ayuda is null",'cod_tipo_ayuda ASC', null, '{n}.casd01_solicitud_ayuda.numero_ocacion', '{n}.casd01_solicitud_ayuda.cod_tipo_ayuda');
		if($num_ayuda!=null){
			$this->concatena11($num_ayuda,'ayudas');
		}else{
			$this->set('ayudas',array());
		}

		echo "<script>";
			echo "document.getElementById('tipo_ayuda').disabled=false;";
		echo "</script>";
	}else{
		$this->set('dato','');
		$this->set('errorMessage', 'no existe una persona con esta cedula');
		echo "<script>";
			echo "document.getElementById('tipo_ayuda').disabled='disabled';";
		echo "</script>";

	}


$this->set('ayu',$this->casd01_tipo_ayuda->FindAll());


 }// fin index



function busqueda_cedula($cedula){
	$this->layout="ajax";

	$sql="select cedula_identidad,apellidos_nombres from casd01_datos_personales where cedula_identidad='$cedula'";
	$dato=$this->casd01_datos_personales->execute($sql);
	if($dato!=null){
		$this->set('dato',$dato);

		$num_ayuda=$this->casd01_solicitud_ayuda->generateList($this->SQLCA()." and cedula_identidad=".$cedula." and numero_documento_ayuda is null",'cod_tipo_ayuda ASC', null, '{n}.casd01_solicitud_ayuda.numero_ocacion', '{n}.casd01_solicitud_ayuda.cod_tipo_ayuda');
		if($num_ayuda!=null){
			$this->concatena11($num_ayuda,'ayudas');
		}else{
			$this->set('ayudas',array());
		}

		echo "<script>";
			echo "document.getElementById('tipo_ayuda').disabled=false;";
		echo "</script>";
	}else{
		$this->set('dato','');
		$this->set('errorMessage', 'no existe una persona con esta cedula');
		echo "<script>";
			echo "document.getElementById('tipo_ayuda').disabled='disabled';";
		echo "</script>";

	}


	$this->set('ayu',$this->casd01_tipo_ayuda->FindAll());

}//fin busqueda_cedula


function carga_evaluacion($cedula=null,$ocacion=null){
	$this->layout="ajax";
	if($ocacion!=''){
	$sql1="select * from casd01_solicitud_ayuda where ".$this->SQLCA()." and cedula_identidad='$cedula' and numero_ocacion='$ocacion'";
		$dato1=$this->casd01_solicitud_ayuda->execute($sql1);
		if($dato1!=null){
			$sql2="select * from casd01_solicitud_ayuda where ".$this->SQLCA()." and cedula_identidad='$cedula' and numero_ocacion='$ocacion' and numero_documento_evaluacion is not null";
			$dato2=$this->casd01_solicitud_ayuda->execute($sql2);
			if($dato2!=null){
				$sql3="select * from casd01_evaluacion_ayuda where ".$this->SQLCA()." and cedula_identidad='$cedula' and numero_ocacion='$ocacion'";
				$dato3=$this->casd01_solicitud_ayuda->execute($sql3);
				$this->set('dato3',$dato3);
			}
				$this->set('dato1',$dato1);

			////////////////////aqui para generar el numero de evaluacion automatico///////////////////
			$ver=$this->casd01_evaluacion_ayuda->execute("select * from casd01_evaluacion_ayuda where ".$this->SQLCA()." and cedula_identidad=".$cedula." order by  numero_documento_evaluacion desc limit 1");
			if($ver!=null)
				$num_evaluacion=$ver[0][0]['numero_documento_evaluacion']+1;
			else
				$num_evaluacion=1;
			$this->set('num_eva',$num_evaluacion);

		}else{
			$this->set('dato1','');
		}

}else{
	$this->set('dato1','');
	$this->data=null;
}

$num_ayuda=$this->casd01_solicitud_ayuda->generateList($this->SQLCA()." and cedula_identidad=".$cedula." and numero_documento_ayuda is null",'cod_tipo_ayuda ASC', null, '{n}.casd01_solicitud_ayuda.numero_ocacion', '{n}.casd01_solicitud_ayuda.cod_tipo_ayuda');
if($num_ayuda!=null){
	$this->concatena11($num_ayuda,'ayudas');
}else{
	$this->set('ayudas',array());
}
$this->set('cedula',$cedula);

$this->set('ayu',$this->casd01_tipo_ayuda->FindAll());

}// carga_evaluacion


/*
function fecha($cedula=null,$ocacion=null){
	$this->layout="ajax";
if($ocacion!=''){
	$sql1="select * from casd01_solicitud_ayuda where cedula_identidad='$cedula' and numero_ocacion='$ocacion'";
		$dato1=$this->casd01_solicitud_ayuda->execute($sql1);
		if($dato1!=null){
			$this->set('dato1',$dato1);
		}else{
			$this->set('dato1','');
		}
}else{
	$this->set('dato1','');
}
}// fin fecha_concepto


function concepto($cedula=null,$ocacion=null){
	$this->layout="ajax";
if($ocacion!=''){
	$sql1="select * from casd01_solicitud_ayuda where cedula_identidad='$cedula' and numero_ocacion='$ocacion'";
		$dato1=$this->casd01_solicitud_ayuda->execute($sql1);
		if($dato1!=null){
			$this->set('dato1',$dato1);

		}else{
			$this->set('dato1','');
		}
}else{
	$this->set('dato1','');
}
}// fin fecha_concepto
*/


function veri($var=null){
	$this->layout="ajax";
	if($var==2){
		echo "<script>";
			echo "document.getElementById('monto_evaluacion').readOnly=true;";
			echo "document.getElementById('monto_evaluacion').value='".$this->Formato2(0)."';";
		echo "</script>";
	}else{
		echo "<script>";
			echo "document.getElementById('monto_evaluacion').readOnly=false;";
			echo "document.getElementById('monto_evaluacion').value='".$this->Formato2(0)."';";
		echo "</script>";
	}


}// veri



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


function consulta($pagina=null) {
	$this->layout="ajax";

	if(isset($pagina)){
		$Tfilas=$this->casd01_evaluacion_ayuda->findCount($this->SQLCA());
        if($Tfilas!=0){
        	$x=$this->casd01_evaluacion_ayuda->findAll($this->SQLCA(),null,"numero_documento_evaluacion ASC",1,$pagina,null);

            $this->set('DATA',$x);
            $this->set('pagina',$pagina);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
            $this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	 	       $this->index();
			   $this->render("index");
			   return;
        }

	}else{
		$pagina=1;
		$Tfilas=$this->casd01_evaluacion_ayuda->findCount($this->SQLCA());

        if($Tfilas!=0){
        	$x=$this->casd01_evaluacion_ayuda->findAll($this->SQLCA(),null,"numero_documento_evaluacion ASC",1,$pagina,null);
			$this->set('DATA',$x);
			$this->set('pagina',$pagina);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	$this->bt_nav($Tfilas,$pagina);
          	$this->set('numT',$Tfilas);
			$this->set('numP',$pagina);

        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	 	       $this->index();
			   $this->render("index");
			   return;
        }
	}

	$cedula= $x[0]["casd01_evaluacion_ayuda"]["cedula_identidad"];
	$ocacion= $x[0]["casd01_evaluacion_ayuda"]["numero_ocacion"];

	$this->set('cedula',$cedula);

	$sq="SELECT * from casd01_datos_personales where cedula_identidad='$cedula'";
	$result=$this->casd01_datos_personales->execute($sq);
	$this->set('dato',$result);

	$sql1="select * from casd01_solicitud_ayuda where ".$this->SQLCA()." and cedula_identidad='$cedula' and numero_ocacion='$ocacion'";
	$dato1=$this->casd01_solicitud_ayuda->execute($sql1);
	$sql3="select * from casd01_evaluacion_ayuda where ".$this->SQLCA()." and cedula_identidad='$cedula' and numero_ocacion='$ocacion'";
	$dato3=$this->casd01_solicitud_ayuda->execute($sql3);
	$this->set('dato3',$dato3);
	$this->set('dato1',$dato1);


$this->set('ayu',$this->casd01_tipo_ayuda->FindAll());


 }//consultar



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

		$num_ayuda=$this->casd01_solicitud_ayuda->generateList($this->SQLCA()." and cedula_identidad=".$cedula." and numero_documento_ayuda is null",'cod_tipo_ayuda ASC', null, '{n}.casd01_solicitud_ayuda.numero_ocacion', '{n}.casd01_solicitud_ayuda.cod_tipo_ayuda');
		if($num_ayuda!=null){
			$this->concatena11($num_ayuda,'ayudas');
		}else{
			$this->set('ayudas',array());
		}

		echo "<script>";
			echo "document.getElementById('tipo_ayuda').disabled=false;";
		echo "</script>";
	}else{
		$this->set('dato','');
		$this->set('errorMessage', 'no existe una persona con esta cedula');
		echo "<script>";
			echo "document.getElementById('tipo_ayuda').disabled='disabled';";
		echo "</script>";

	}


$this->set('ayu',$this->casd01_tipo_ayuda->FindAll());

}// fin seleccion_busqueda




function guardar($cedula){
	$this->layout = "ajax";

	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst = $this->Session->read('SScodinst');
    $cod_dep = $this->Session->read('SScoddep');

	if(empty($this->data['casp01']['tipo_ayuda']) || empty($this->data['casp01']['concepto_evaluacion']) || empty($this->data['casp01']['num_evaluacion']) || empty($this->data['casp01']['aprobacion']) || empty($this->data['casp01']['monto_evaluacion'])){
		$this->set('errorMessage', 'Debe ingresar los datos requeridos');
		$this->carga_evaluacion($cedula,'');
		$this->render('carga_evaluacion');
	}else {
		$ocacion=$this->data['casp01']['tipo_ayuda'];
		$concepto=$this->data['casp01']['concepto_evaluacion'];
		$fecha_evaluacion=$this->data['casp01']['fecha_evaluacion'];
		$num_evaluacion=$this->data['casp01']['num_evaluacion'];
		$aprobacion=$this->data['casp01']['aprobacion'];
		if($aprobacion==1){
			$monto=$this->Formato1($this->data['casp01']['monto_evaluacion']);
		}else{
			$monto=0;
		}

		$ver=$this->casd01_solicitud_ayuda->execute("select * from casd01_solicitud_ayuda where ".$this->SQLCA()." and cedula_identidad='$cedula' and numero_ocacion='$ocacion'");
		$tipo_ayuda=$ver[0][0]['cod_tipo_ayuda'];
		$num_ayuda=null;

		////////////////////aqui para generar el numero de evaluacion automatico///////////////////
			$ver=$this->casd01_evaluacion_ayuda->execute("select * from casd01_evaluacion_ayuda where ".$this->SQLCA()." and cedula_identidad=".$cedula." order by  numero_documento_evaluacion desc limit 1");
			if($ver!=null)
				$num_evaluacion_sal=$ver[0][0]['numero_documento_evaluacion']+1;
			else
				$num_evaluacion_sal=1;

		if($num_evaluacion_sal==$num_evaluacion){
			$username=$this->Session->read('nom_usuario');
			$campos=$this->casd01_datos_personales->execute("select * from usuarios where username='$username'");
			if($campos[0][0]['cedula_identidad']!=null){
				$cedula_usuario=$campos[0][0]['cedula_identidad'];
			}else{
				$cedula_usuario=0;
			}
			$funcionario=$campos[0][0]['funcionario'];

			$sql_insert = "BEGIN;INSERT INTO casd01_evaluacion_ayuda (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cedula_identidad,cod_tipo_ayuda,numero_ocacion,numero_documento_evaluacion,evaluacion,aprobado,monto_aprobado,fecha_evaluacion,username,cedula_usuario,nombre_usuario) VALUES ('$cod_presi','$cod_entidad','$cod_tipo_inst','$cod_inst','$cod_dep','$cedula', '$tipo_ayuda','$ocacion','$num_evaluacion','$concepto','$aprobacion','$monto','$fecha_evaluacion','$username','$cedula_usuario','$funcionario')";
			$sw2 = $this->casd01_evaluacion_ayuda->execute($sql_insert);
			if($sw2>1){
				$update=$this->casd01_solicitud_ayuda->execute("update casd01_solicitud_ayuda set numero_documento_evaluacion='$num_evaluacion' where ".$this->SQLCA()." and cedula_identidad='$cedula' and numero_ocacion='$ocacion'");
				if($update>1){
					$this->casd01_evaluacion_ayuda->execute("COMMIT");
			 		$this->set('Message_existe', 'EVALUACIÓN REALIZADA CON EXITO');
				}
			 }else{
			 	$this->casd01_evaluacion_ayuda->execute("ROOLBACK");
			 	$this->set('errorMessage', 'INSERCI&Oacute;N FALLIDA DEL DATO A EVALUAR');
			 }
		}else{
			$this->set('errorMessage', 'INSERCI&Oacute;N FALLIDA DEL DATO A EVALUAR');
		}


		$this->carga_evaluacion($cedula,$ocacion);
		$this->render('carga_evaluacion');
	}



$this->set('ayu',$this->casd01_tipo_ayuda->FindAll());

}// fin guardar




function eliminar($cedula=null,$ocacion=null,$num_evaluacion=null,$pagina=null){
	  $this->layout = "ajax";
		  $x = $this->casd01_evaluacion_ayuda->execute("BEGIN;DELETE FROM casd01_evaluacion_ayuda  WHERE ".$this->SQLCA()." and cedula_identidad='$cedula' and numero_ocacion='$ocacion' and numero_documento_evaluacion='$num_evaluacion'");
		  if($x>1){
		  	$update=$this->casd01_solicitud_ayuda->execute("update casd01_solicitud_ayuda set numero_documento_evaluacion=null where ".$this->SQLCA()." and cedula_identidad='$cedula' and numero_ocacion='$ocacion' and numero_documento_evaluacion='$num_evaluacion'");
			if($update>1){
				$this->casd01_evaluacion_ayuda->execute("COMMIT");
		 		$this->set('Message_existe', 'EVALUACIÓN ELIMINADA CON EXITO');
			}

		  }else{
		  	$this->casd01_evaluacion_ayuda->execute("ROLLBACK");
		  	$this->set('errorMessage', 'EL DATO NO PUDO SER ELIMINADO');
		  }

		if(isset($pagina) && $pagina!=null){
			$Tfilas=$this->casd01_evaluacion_ayuda->findCount($this->SQLCA());
			if($Tfilas==0){
				$this->index();
				$this->render('index');
			}else{
				$this->consulta($pagina);
				$this->render('consulta');
			}
		}else{
			$this->carga_evaluacion($cedula,$ocacion);
			$this->render('carga_evaluacion');
		}


$this->set('ayu',$this->casd01_tipo_ayuda->FindAll());


}//fin function




 function modificar($cedula=null,$ocacion=null,$num_evaluacion=null,$pagina=null){
 	 $this->layout = "ajax";

 	$sql2="select * from casd01_solicitud_ayuda where ".$this->SQLCA()." and cedula_identidad=".$cedula." and numero_ocacion=".$ocacion." and numero_documento_evaluacion=".$num_evaluacion."";
	$dato1=$this->casd01_solicitud_ayuda->execute($sql2);
	$this->set('dato1',$dato1);
    $sql_1="SELECT * from casd01_evaluacion_ayuda where ".$this->SQLCA()." and cedula_identidad='$cedula' and numero_ocacion='$ocacion' and numero_documento_evaluacion='$num_evaluacion'";
	$result_1=$this->casd01_evaluacion_ayuda->execute($sql_1);
	$this->set('dato3',$result_1);

	$num_ayuda=$this->casd01_solicitud_ayuda->generateList($this->SQLCA()." and cedula_identidad=".$cedula." and numero_documento_ayuda is null",'cod_tipo_ayuda ASC', null, '{n}.casd01_solicitud_ayuda.numero_ocacion', '{n}.casd01_solicitud_ayuda.cod_tipo_ayuda');
	if($num_ayuda!=null){
		$this->concatena11($num_ayuda,'ayudas');
	}else{
		$this->set('ayudas',array());
	}
	$this->set('cedula',$cedula);

	$this->set('Message_existe', 'PROCEDA A MODIFICAR LOS DATOS');

	if(isset($pagina)){
		$this->set('pagina',$pagina);
	}else{
		$this->set('pagina',null);
	}


$this->set('ayu',$this->casd01_tipo_ayuda->FindAll());

 }// fin modificar_items





function guardar_modificar($cedula=null,$ocacion1=null,$numero_eva=null,$pagina=null){
	$this->layout = "ajax";
	if(empty($this->data['casp01']['concepto_evaluacion']) || empty($this->data['casp01']['num_evaluacion']) || empty($this->data['casp01']['aprobacion']) || empty($this->data['casp01']['monto_evaluacion'])){
		$this->set('errorMessage', 'Debe ingresar los datos requeridos');
	}else {
		$num_evaluacion=$this->data['casp01']['num_evaluacion'];
		$fecha_evaluacion=$this->data['casp01']['fecha_evaluacion'];
		$concepto_evaluacion=$this->data['casp01']['concepto_evaluacion'];
		$aprobacion=$this->data['casp01']['aprobacion'];
		$monto=$this->Formato1($this->data['casp01']['monto_evaluacion']);

		$sql = "BEGIN;UPDATE casd01_evaluacion_ayuda SET numero_documento_evaluacion='$num_evaluacion',fecha_evaluacion='$fecha_evaluacion',evaluacion='$concepto_evaluacion',aprobado='$aprobacion',monto_aprobado='$monto' where ".$this->SQLCA()." and cedula_identidad=".$cedula." and numero_ocacion=".$ocacion1." and numero_documento_evaluacion=".$numero_eva."";
		$sw=$this->casd01_evaluacion_ayuda->execute($sql);
		if($sw>1){
		    $sql2 = "UPDATE casd01_solicitud_ayuda SET numero_documento_evaluacion='$num_evaluacion' where ".$this->SQLCA()." and cedula_identidad=".$cedula." and numero_ocacion=".$ocacion1." and numero_documento_evaluacion=".$numero_eva."";
			$sw2=$this->casd01_solicitud_ayuda->execute($sql2);
			if($sw2>1){
				$this->set('Message_existe','EL REGISTRO SE MODIFICO EXITOSAMENTE');
				$this->casd01_solicitud_ayuda->execute("COMMIT");
			}else{
				$this->set('errorMessage', 'LOS DATOS NO PUDIERON SER MODIFICADOS');
				$this->casd01_solicitud_ayuda->execute("ROLLBACK");
			}
		}else{
			$this->set('errorMessage', 'LOS DATOS NO PUDIERON SER MODIFICADOS');
			$this->casd01_solicitud_ayuda->execute("ROLLBACK");
		}

	}


	$sql2="select * from casd01_solicitud_ayuda where ".$this->SQLCA()." and cedula_identidad=".$cedula." and numero_ocacion=".$ocacion1."";
	$dato1=$this->casd01_solicitud_ayuda->execute($sql2);
	$this->set('dato1',$dato1);

    $sql_1="SELECT * from casd01_evaluacion_ayuda where ".$this->SQLCA()." and cedula_identidad='$cedula' and numero_ocacion='$ocacion1'";
	$result_1=$this->casd01_evaluacion_ayuda->execute($sql_1);
	$this->set('dato3',$result_1);

	$num_ayuda=$this->casd01_solicitud_ayuda->generateList($this->SQLCA()." and cedula_identidad=".$cedula." and numero_documento_ayuda is null",'cod_tipo_ayuda ASC', null, '{n}.casd01_solicitud_ayuda.numero_ocacion', '{n}.casd01_solicitud_ayuda.cod_tipo_ayuda');
	if($num_ayuda!=null){
		$this->concatena11($num_ayuda,'ayudas');
	}else{
		$this->set('ayudas',array());
	}
	$this->set('cedula',$cedula);

	if(isset($pagina) && $pagina!=null){
		$this->consulta($pagina);
		$this->render('consulta');
	}


$this->set('ayu',$this->casd01_tipo_ayuda->FindAll());


}//fin guardar_items_modificar



function cancelar($cedula=nulll){
    $this->layout = "ajax";

    $sql1="select * from casd01_solicitud_ayuda where cedula_identidad='$cedula' and ".$this->SQLCA()." order by cod_tipo_ayuda asc";
		$dato1=$this->casd01_solicitud_ayuda->execute($sql1);
		if($dato1!=null){
			$this->set('dato1',$dato1);
		}else{
			$this->set('dato1','');
		}

		$tipo_ayuda= array('1'=>'EFECTIVO','2'=>'MEDICAMENTOS','3'=>'ALIMENTOS Y BEBIDAS','4'=>'TRASLADOS MÉDICOS','5'=>'PASAJES',
						'6'=>'HOSPITALIZACIÓN Y CIRUGIA','7'=>'GASTOS FUNERARIOS','8'=>'SILLAS DE RUEDAS','9'=>'COMPETENCIAS DEPORTIVAS',
						'10'=>'ÚTILES ESCOLARES','11'=>'ÚTILES DEPORTIVOS','12'=>'CRÉDITOS','13'=>'EMPLEOS','14'=>'SERVICIOS PÚBLICOS');
	$this->set('tipo_ayuda',$tipo_ayuda);

$this->set('ayu',$this->casd01_tipo_ayuda->FindAll());


	$this->data=null;
	$this->data['casp01']['concepto_ayuda']=null;

}//fin cancelar


 }//Fin de la clase controller
 ?>