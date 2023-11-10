<?php
/*
 * Created on 05/01/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class ReporteBienesController extends AppController{
	var $name = "reporte_bienes";
	var $uses = array('Usuario','v_movimiento_inmuebles','cugd07_firmas_oficio_anulacion','ccfd04_cierre_mes','cimd03_inventario_muebles','cimd03_inventario_inmuebles','cimd04_vehiculo_asegurado','cugd01_estados','cugd01_municipios','cugd01_parroquias','cugd01_centropoblados',
						'cugd02_institucion','cugd02_dependencia','cugd02_direccionsuperior','cugd02_coordinacion','cugd02_secretaria','cugd02_direccion','cugd02_division','cugd02_departamento','cugd02_oficina','v_buscar_muebles',
						'cugd02_direccionsuperior','v_inventario_muebles_todo','v_cimd01_escalera_codigos_bienes','cimd02_tipo_movimiento','v_inventario_inmuebles_todo');
    var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap', 'Fpdf');

 function checkSession(){
	if (!$this->Session->check('Usuario')){
		$this->redirect('/salir/');
		exit();
	}else{
		$this->requestAction('/usuarios/actualizar_user');
	}
 }//fin checksession

 function beforeFilter(){$this->checkSession();}

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

 function consolidacion_reporte_bienes($var=null){
	$this->layout='ajax';
	if($var!=null && $var==1){
	$this->Session->write('consolidacion_reporte_cimd03_bienes',1);
	}elseif($var!=null && $var==2){
	$this->Session->write('consolidacion_reporte_cimd03_bienes',2);
	}elseif($var!=null){
	$this->Session->write('consolidacion_reporte_cimd03_bienes',2);
	}
 }

 function sql_codigo_mueble($var=null, $psplit=null){
 	$sql_codigo = '';
 	if($var!=null){
 		$codigo=split('-', $var);
 		if($psplit!=null && $psplit==1){
 			$cond_numi = "";
 		}else{
 			$cond_numi = " AND numero_identificacion=".$codigo[4];
 		}
		$sql_codigo="cod_tipo=".$codigo[0]." AND cod_grupo=".$codigo[1]." AND cod_subgrupo=".$codigo[2]." AND cod_seccion=".$codigo[3].$cond_numi;
		return $sql_codigo;
 	}else{
		return false;
 	}
 }

 /* *
  * Function   : ReporteInventarioDeInmueble
  * Descripcion: Funcion donde se genera la vista del formulario y el PDF para generar el reporte de inventario de inmuebles,
  * 			 consolidado por institucion o por dependencia segun sea el caso, requiere de 4 funciones para su funcionamiento.
  * Funciones:   reporte_inventario_de_inmuebles, consolidacion_reporte_bienes, busqueda_inmueble_especifico, select_inmueble_especifico.
  */
 function reporte_inventario_de_inmuebles($var=null){
 	if($var!=null){
		if($var=='no'){
			$this->layout='ajax';
   			$_SESSION['SScoddep']==1 ? $this->Session->write('consolidacion_reporte_cimd03_bienes',1) : $this->Session->write('consolidacion_reporte_cimd03_bienes',2);;
			$this->set('var',$var);

	$cont = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA()." and tipo_documento='9995'");
	if($cont == 0){
		$this->set('mensaje','Por favor, ingrese los nombres de los firmantes');
		$this->set('nombre_primera_firma','');
		$this->set('cargo_primera_firma','');
		$this->set('nombre_segunda_firma','');
		$this->set('cargo_segunda_firma','');
		$this->set('nombre_tercera_firma','');
		$this->set('cargo_tercera_firma','');
		$this->set('nombre_cuarta_firma','N/A');
		$this->set('cargo_cuarta_firma','N/A');
		$this->set('tipo_doc_anul', 9995);
		$firma_existe = 'no';
		echo'<script>';
       		echo" document.getElementById('enviar').disabled = ''; ";
		echo'</script>';
	}else{
		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=9995");
		$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
		$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
		$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
		$this->set('tipo_doc_anul',9995);
		$firma_existe = 'si';
		echo'<script>';
       		echo" document.getElementById('enviar').disabled = ''; ";
		echo'</script>';
	}
		$this->set('firma_existe',$firma_existe);

		}elseif($var=='si'){
			$this->layout='pdf';
			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
			$consolidacion = $_SESSION['consolidacion_reporte_cimd03_bienes'];
			$radio_inmuebles = $this->data['inventario_inmueble']['radio_inmuebles'];

		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=9995");
		$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
		$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
		$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);

			if($cod_dep==1){
				if($consolidacion==1){
					if($radio_inmuebles==1){
					$sql = "SELECT cod_tipo, cod_grupo, numero_identificacion, denominacion_inmueble, fecha_incorporacion, avaluo_actual FROM cimd03_inventario_inmuebles WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' ORDER BY cod_tipo, cod_grupo, numero_identificacion, fecha_incorporacion";
					}else{
						$id_inmueble = $this->data['inventario_inmueble']['inmueble'];
						if(empty($id_inmueble)){
							echo'<script>history.back(1);</script>';
						}else{
							$sql = "SELECT cod_tipo, cod_grupo, numero_identificacion, denominacion_inmueble, fecha_incorporacion, avaluo_actual FROM cimd03_inventario_inmuebles WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND numero_identificacion='$id_inmueble' ORDER BY cod_tipo, cod_grupo, numero_identificacion, fecha_incorporacion";
						}
					}
				}else{
					if($radio_inmuebles==1){
					$sql = "SELECT cod_tipo, cod_grupo, numero_identificacion, denominacion_inmueble, fecha_incorporacion, avaluo_actual FROM cimd03_inventario_inmuebles WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' ORDER BY cod_tipo, cod_grupo, numero_identificacion, fecha_incorporacion";
					}else{
						$id_inmueble = $this->data['inventario_inmueble']['inmueble'];
						if(empty($id_inmueble)){
							echo'<script>history.back(1);</script>';
						}else{
							$sql = "SELECT cod_tipo, cod_grupo, numero_identificacion, denominacion_inmueble, fecha_incorporacion, avaluo_actual FROM cimd03_inventario_inmuebles WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND numero_identificacion='$id_inmueble' ORDER BY cod_tipo, cod_grupo, numero_identificacion, fecha_incorporacion";
						}
					}
				}
				$datos = $this->cimd03_inventario_inmuebles->execute($sql);

			}else{
				if($radio_inmuebles==1){
				$sql = "SELECT cod_tipo, cod_grupo, numero_identificacion, denominacion_inmueble, fecha_incorporacion, avaluo_actual FROM cimd03_inventario_inmuebles WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' ORDER BY cod_tipo, cod_grupo, numero_identificacion, fecha_incorporacion";
				}else{
					$id_inmueble = $this->data['inventario_inmueble']['inmueble'];
					if(empty($id_inmueble)){
						echo'<script>history.back(1);</script>';
					}else{
						$sql = "SELECT cod_tipo, cod_grupo, numero_identificacion, denominacion_inmueble, fecha_incorporacion, avaluo_actual FROM cimd03_inventario_inmuebles WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND numero_identificacion='$id_inmueble' ORDER BY cod_tipo, cod_grupo, numero_identificacion, fecha_incorporacion";
					}
				}
				$datos = $this->cimd03_inventario_inmuebles->execute($sql);
			}
			$this->set('datos',$datos);
			$this->set('var',$var);
		}
 	}else{
 		$this->set('var','no');
 	}
 }//reporte_inventario_de_inmuebles

 function busqueda_inmueble_especifico($var=null){
 	$this->layout='ajax';
 	$this->set('var',$var);
 }

 function select_inmueble_especifico($var=null){
 	$this->layout='ajax';
 	if($var!=null){
		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');

		$pista=strtoupper($var);
 		$consolidacion = $_SESSION['consolidacion_reporte_cimd03_bienes'];
 		if($consolidacion==1){
		   $sql = "SELECT numero_identificacion, denominacion_inmueble FROM cimd03_inventario_inmuebles WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND UPPER(denominacion_inmueble) like '%$pista%'";
 		}elseif($consolidacion==2){
		   $sql = "SELECT numero_identificacion, denominacion_inmueble FROM cimd03_inventario_inmuebles WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND UPPER(denominacion_inmueble) like '%$pista%'";
 		}
 		$rs = $this->cimd03_inventario_inmuebles->execute($sql);
 		if(count($rs)!=0){
		    foreach($rs as $l){
				$v[]=$l[0]["numero_identificacion"];
				$d[]=$l[0]["numero_identificacion"]." - ".$l[0]["denominacion_inmueble"];
			}
			$lista = array_combine($v, $d);
			$this->set('lista',$lista);
 		}else{
		   $this->set('lista',array('0'=>'No hay registros'));
		}
 	}else{
 		$this->set('lista',array('0'=>'No hay registros'));
 	}
 }

 function reporte_vehiculos_asegurados($var=null){
 	if($var!=null){
		if($var=='no'){
			$this->layout='ajax';
			$this->set('var',$var);
		}elseif($var=='si'){
			$this->layout='pdf';
			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
			$cod_dep == 1 ? $consolidacion = $this->data['vehiculos_asegurados']['consolidacion'] : $consolidacion = 2;
			$ordenacion = $this->data['vehiculos_asegurados']['ordenacion'];
			if($consolidacion==1){
				if($ordenacion==1){
					$orden= 'placa ASC';
				}else{
					$orden= 'numero_identificacion ASC';
				}
				$sql = "SELECT a.numero_identificacion,
			  	a.placa,
			  	(SELECT b.denominacion FROM cimd03_inventario_muebles b WHERE a.numero_identificacion=b.numero_identificacion) as descripcion_vehiculo,
			  	a.compania_aseguradora,
			  	a.numero_poliza,
			  	a.monto_cobertura,
			  	a.descripcion_cobertura,
			  	a.vehiculo_asignado
				FROM cimd04_vehiculo_asegurado a WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' ORDER BY ".$orden;
				$datos_vehiculos = $this->cimd04_vehiculo_asegurado->execute($sql);

			}else{
				$condicion = $this->SQLCA();
				if($ordenacion==1){
					$orden= 'placa ASC';
				}else{
					$orden= 'numero_identificacion ASC';
				}
				$sql = "SELECT a.numero_identificacion,
			  	a.placa,
			  	(SELECT b.denominacion FROM cimd03_inventario_muebles b WHERE a.numero_identificacion=b.numero_identificacion) as descripcion_vehiculo,
			  	a.compania_aseguradora,
			  	a.numero_poliza,
			  	a.monto_cobertura,
			  	a.descripcion_cobertura,
			  	a.vehiculo_asignado
				FROM cimd04_vehiculo_asegurado a WHERE ".$condicion." ORDER BY ".$orden;
				$datos_vehiculos = $this->cimd04_vehiculo_asegurado->execute($sql);
			}
			$this->set('datos_vehiculos',$datos_vehiculos);
			$this->set('var',$var);
		}

 	}else{
 		$this->set('var','no');
 	}
 }//reporte_vehiculos_asegurados





 function reporte_relacion_bienes_muebles($var=null){
 	if($var!=null){
		if($var=='no'){
			$this->layout='ajax';
   			$_SESSION['SScoddep']==1 ? $this->Session->write('consolidacion_reporte_cimd03_bienes',1) : $this->Session->write('consolidacion_reporte_cimd03_bienes',2);;
			$this->set('var',$var);

	$cont = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA()." and tipo_documento='10003'");
	if($cont == 0){
		$this->set('mensaje','Por favor, ingrese los nombres de los firmantes');
		$this->set('nombre_primera_firma','');
		$this->set('cargo_primera_firma','');
		$this->set('nombre_segunda_firma','');
		$this->set('cargo_segunda_firma','');
		$this->set('nombre_tercera_firma','N/A');
		$this->set('cargo_tercera_firma','N/A');
		$this->set('nombre_cuarta_firma','N/A');
		$this->set('cargo_cuarta_firma','N/A');
		$this->set('tipo_doc_anul', 10003);
		$firma_existe = 'no';
		echo'<script>';
       		echo" document.getElementById('enviar').disabled = 'true'; ";
		echo'</script>';
	}else{
		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=10003");
		$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
		$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
		$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
		$this->set('tipo_doc_anul',10003);
		$firma_existe = 'si';
		echo'<script>';
       		echo" document.getElementById('enviar').disabled = ''; ";
		echo'</script>';
	}
		$this->set('firma_existe',$firma_existe);

		}elseif($var=='si'){
			$this->layout='pdf';
			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');
			// $consolidacion = $_SESSION['consolidacion_reporte_cimd03_bienes'];
			$consolidacion = isset($this->data['inventario_inmueble']['consolidacion_reporte']) ? $this->data['inventario_inmueble']['consolidacion_reporte'] : 2;
			$radio_muebles = $this->data['inventario_inmueble']['radio_muebles'];

		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=10003");
		if(!empty($firmantes)){
			$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
			$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
			$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
			$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
			$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
			$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
			$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
			$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
		}else{
			$this->set('nombre_primera_firma','');
			$this->set('cargo_primera_firma','');
			$this->set('nombre_segunda_firma','');
			$this->set('cargo_segunda_firma','');
			$this->set('nombre_tercera_firma','');
			$this->set('cargo_tercera_firma','');
			$this->set('nombre_cuarta_firma','');
			$this->set('cargo_cuarta_firma','');
		}

			if($cod_dep==1){
				if($consolidacion==1){
					if($radio_muebles==1){
					$sql = "SELECT a.cod_tipo, a.cod_grupo, a.cod_subgrupo, a.cod_seccion,
							(SELECT b.denominacion FROM cimd01_clasificacion_seccion b WHERE b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion) AS clasificacion_funcional,
							a.numero_identificacion, a.denominacion, a.cantidad, a.valor_unitario FROM cimd03_inventario_muebles a WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' ORDER BY a.cod_tipo, a.cod_grupo, a.cod_subgrupo, a.cod_seccion, a.numero_identificacion";
					}else{
						$id_mueble = $this->data['inventario_inmueble']['mueble'];
						$codigo_mueble = $this->sql_codigo_mueble($id_mueble, 1); // quitar el 2do param = 1, para buscar uno en particular acomodando en la vista dicha opcion y reacomodar la condicion con if opc 3 para la clasificacion
						if(empty($id_mueble)){
							echo'<script>history.back(1);</script>';
						}else{
							$sql = "SELECT a.cod_tipo, a.cod_grupo, a.cod_subgrupo, a.cod_seccion,
									(SELECT b.denominacion FROM cimd01_clasificacion_seccion b WHERE b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion) AS clasificacion_funcional,
									a.numero_identificacion, a.denominacion, a.cantidad, a.valor_unitario FROM cimd03_inventario_muebles a WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND $codigo_mueble ORDER BY a.cod_tipo, a.cod_grupo, a.cod_subgrupo, a.cod_seccion, a.numero_identificacion";
						}
					}
				}else{
					$cod_dependencia = $this->cod_dep_consolidado();
					if($radio_muebles==1){
					$sql = "SELECT a.cod_tipo, a.cod_grupo, a.cod_subgrupo, a.cod_seccion,
							(SELECT b.denominacion FROM cimd01_clasificacion_seccion b WHERE b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion) AS clasificacion_funcional,
							a.numero_identificacion, a.denominacion, a.cantidad, a.valor_unitario FROM cimd03_inventario_muebles a WHERE  cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dependencia' ORDER BY a.cod_tipo, a.cod_grupo, a.cod_subgrupo, a.cod_seccion, a.numero_identificacion";
					}else{
						$id_mueble = $this->data['inventario_inmueble']['mueble'];
						$codigo_mueble = $this->sql_codigo_mueble($id_mueble, 1); // quitar el 2do param = 1, para buscar uno en particular acomodando en la vista dicha opcion y reacomodar la condicion con if opc 3 para la clasificacion
						if(empty($id_mueble)){
							echo'<script>history.back(1);</script>';
						}else{
							$sql = "SELECT a.cod_tipo, a.cod_grupo, a.cod_subgrupo, a.cod_seccion,
									(SELECT b.denominacion FROM cimd01_clasificacion_seccion b WHERE b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion) AS clasificacion_funcional,
									a.numero_identificacion, a.denominacion, a.cantidad, a.valor_unitario FROM cimd03_inventario_muebles a WHERE  cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dependencia' AND $codigo_mueble ORDER BY a.cod_tipo, a.cod_grupo, a.cod_subgrupo, a.cod_seccion, a.numero_identificacion";
						}
					}
				}
				$datos = $this->cimd03_inventario_muebles->execute($sql);

			}else{
				if($radio_muebles==1){
					$sql = "SELECT a.cod_tipo, a.cod_grupo, a.cod_subgrupo, a.cod_seccion,
							(SELECT b.denominacion FROM cimd01_clasificacion_seccion b WHERE b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion) AS clasificacion_funcional,
							a.numero_identificacion, a.denominacion, a.cantidad, a.valor_unitario FROM cimd03_inventario_muebles a WHERE  cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' ORDER BY a.cod_tipo, a.cod_grupo, a.cod_subgrupo, a.cod_seccion, a.numero_identificacion";
				}else{
					$id_mueble = $this->data['inventario_inmueble']['mueble'];
					$codigo_mueble = $this->sql_codigo_mueble($id_mueble, 1); // quitar el 2do param = 1, para buscar uno en particular acomodando en la vista dicha opcion y reacomodar la condicion con if opc 3 para la clasificacion
					if(empty($id_mueble)){
						echo'<script>history.back(1);</script>';
					}else{
						$sql = "SELECT a.cod_tipo, a.cod_grupo, a.cod_subgrupo, a.cod_seccion,
									(SELECT b.denominacion FROM cimd01_clasificacion_seccion b WHERE b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion) AS clasificacion_funcional,
									a.numero_identificacion, a.denominacion, a.cantidad, a.valor_unitario FROM cimd03_inventario_muebles a WHERE  cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND $codigo_mueble ORDER BY a.cod_tipo, a.cod_grupo, a.cod_subgrupo, a.cod_seccion, a.numero_identificacion";
					}
				}
				$datos = $this->cimd03_inventario_muebles->execute($sql);
			}
			$this->set('consolidadop',isset($consolidacion)?$consolidacion:2);
			$this->set('datos',$datos);
			$this->set('var',$var);
		}
 	}else{
 		$this->set('var','no');
 	}
 }




function busqueda_mueble_especifico($var=null){
 	$this->layout='ajax';
 	$this->set('var',$var);
	if(isset($var) && $var==3){
				$url                  =  "/reporte_bienes/buscar_mueble/$var";
				$width_aux            =  "750px";
				$height_aux           =  "450px";
				$title_aux            =  "Buscar Bienes Muebles";
				$resizable_aux        =  false;
				$maximizable_aux      =  false;
				$minimizable_aux      =  false;
				$closable_aux         =  false;

			 echo "<script>";
	           echo "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
	         echo "</script>";
	}
 }

 function select_mueble_especifico($var=null){
 	$this->layout='ajax';
 	if($var!=null){
		$cod_presi = $this->Session->read('SScodpresi');
		$cod_entidad = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst = $this->Session->read('SScodinst');
		$cod_dep = $this->Session->read('SScoddep');

		$pista=strtoupper($var);
 		$consolidacion = $_SESSION['consolidacion_reporte_cimd03_bienes'];
 		if($consolidacion==1){
		   $sql = "SELECT cod_tipo, cod_grupo, cod_subgrupo, cod_seccion, numero_identificacion, denominacion FROM cimd03_inventario_muebles WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND UPPER(denominacion) like '%$pista%'";
 		}elseif($consolidacion==2){
		   $sql = "SELECT cod_tipo, cod_grupo, cod_subgrupo, cod_seccion, numero_identificacion, denominacion FROM cimd03_inventario_muebles WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND UPPER(denominacion) like '%$pista%'";
 		}
 		$rs = $this->cimd03_inventario_muebles->execute($sql);
 		if(count($rs)!=0){
		    foreach($rs as $l){
				$v[]=$l[0]["cod_tipo"].'-'.$l[0]["cod_grupo"].'-'.$l[0]["cod_subgrupo"].'-'.$l[0]["cod_seccion"].'-'.$l[0]["numero_identificacion"];
				$d[]=$this->mascara_ocho($l[0]["numero_identificacion"])." - ".$l[0]["denominacion"];
			}
			$lista = array_combine($v, $d);
			$this->set('lista',$lista);
 		}else{
		   $this->set('lista',array('0'=>'No hay registros'));
		}
 	}else{
 		$this->set('lista',array('0'=>'No hay registros'));
 	}
 }

 function reporte_movimiento_bienes_muebles($var=null){
 	if($var!=null){
	$cod_presi     = $this->Session->read('SScodpresi');
	$cod_entidad   = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst      = $this->Session->read('SScodinst');
	$cod_dep       = $this->Session->read('SScoddep');
	$SScoddeporig  = $this->Session->read('SScoddeporig');
	$SScoddep      = $this->Session->read('SScoddep');

    $this->set('cod_presi',$cod_presi);
    $this->set('cod_entidad',$cod_entidad);
    $this->set('cod_tipo_inst',$cod_tipo_inst);
    $this->set('cod_inst',$cod_inst);
    $this->set('cod_dep',$cod_dep);

		if($var=='no'){
			$this->layout='ajax';
			$ano = $this->ano_ejecucion();
			if(isset($ano) && !empty($ano)){
				$ano_inicial = $ano - 20;
				$ano_final = $ano + 20;
				for($i=$ano_inicial; $i<=$ano_final; $i++){
					$array_ano[$i]=$i;
				}
			}else{
				$ano=date('Y');
				$ano_inicial = $ano - 20;
				$ano_final = $ano + 20;
				for($i=$ano_inicial; $i<=$ano_final; $i++){
					$array_ano[$i]=$i;
				}
			}
			$cod_presi = $this->Session->read('SScodpresi');
			$lista =  $this->cugd01_estados->generateList('cod_republica='.$cod_presi, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
			$lista_institucion =  $this->cugd02_institucion->generateList(null, 'cod_institucion ASC', null, '{n}.cugd02_institucion.cod_institucion', '{n}.cugd02_institucion.denominacion');
			$this->concatena($lista, 'lista');
			$this->concatena($lista_institucion, 'lista_institucion');
			$this->set('ano',$ano);
			$this->set('array_ano',$array_ano);
			$this->set('var',$var);

	$cont = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA()." and tipo_documento='9999'");
	if($cont == 0){
		$this->set('mensaje','Por favor, ingrese los nombres de los firmantes');
		$this->set('nombre_primera_firma','');
		$this->set('cargo_primera_firma','');
		$this->set('nombre_segunda_firma','');
		$this->set('cargo_segunda_firma','');
		$this->set('nombre_tercera_firma','N/A');
		$this->set('cargo_tercera_firma','N/A');
		$this->set('nombre_cuarta_firma','N/A');
		$this->set('cargo_cuarta_firma','N/A');
		$this->set('tipo_doc_anul', 9999);
		$firma_existe = 'no';
		echo'<script>';
       		echo" document.getElementById('enviar').disabled = 'true'; ";
		echo'</script>';
	}else{
		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=9999");
		$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
		$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
		$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
		$this->set('tipo_doc_anul',9999);
		$firma_existe = 'si';
		echo'<script>';
       		echo" document.getElementById('enviar').disabled = ''; ";
		echo'</script>';
	}
		$this->set('firma_existe',$firma_existe);

		}elseif($var=='si'){
			$this->layout='pdf';
			$cp = $this->Session->read('SScodpresi');
			$ce = $this->Session->read('SScodentidad');
			$cti = $this->Session->read('SScodtipoinst');
			$ci = $this->Session->read('SScodinst');
			$cd = $this->Session->read('SScoddep');
			$condicion  = "";

		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=9999");
		if(!empty($firmantes)){
			$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
			$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
			$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
			$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
			$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
			$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
			$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
			$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
		}else{
			$this->set('nombre_primera_firma','');
			$this->set('cargo_primera_firma','');
			$this->set('nombre_segunda_firma','');
			$this->set('cargo_segunda_firma','');
			$this->set('nombre_tercera_firma','');
			$this->set('cargo_tercera_firma','');
			$this->set('nombre_cuarta_firma','');
			$this->set('cargo_cuarta_firma','');
		}

		if($cd==1){
			$consolidacion=$this->data['movimiento_mueble']['consolidacion'];
			if($consolidacion==1){
				$condicion .= "cod_presi='$cp' and cod_entidad='$ce' and cod_tipo_inst='$cti' and cod_inst='$ci'";
				$condic22 = "a.cod_presi='$cp' AND a.cod_entidad='$ce' AND a.cod_tipo_inst='$cti' AND a.cod_inst='$ci'";
			}elseif($consolidacion==2){
				$cod_dependencia = $this->cod_dep_consolidado();
				$condicion .= "cod_presi='$cp' and cod_entidad='$ce' and cod_tipo_inst='$cti' and cod_inst='$ci' and cod_dep='$cod_dependencia'";
				$condic22 = "a.cod_presi='$cp' AND a.cod_entidad='$ce' AND a.cod_tipo_inst='$cti' AND a.cod_inst='$ci' AND a.cod_dep='$cod_dependencia'";
			}
		}else{
				$condicion .= "cod_presi='$cp' and cod_entidad='$ce' and cod_tipo_inst='$cti' and cod_inst='$ci' and cod_dep='$cd'";
				$condic22 = "a.cod_presi='$cp' AND a.cod_entidad='$ce' AND a.cod_tipo_inst='$cti' AND a.cod_inst='$ci' AND a.cod_dep='$cd'";
		}

			$ano = $this->data['movimiento_mueble']['ano'];
			// $por_ano = $this->data['movimiento_mueble']['por_ano']; // al descomentar esta linea recordar descomentar tambien de la vista el radio de todo el ano o 1 mes especifico
			$por_ano = 2;
			if($por_ano==1){
				/* $ano_final=$ano-1;
				$fecha_anterior_final = "31-12-".$ano_final;
				$fecha_actual_inicial = date("Y-m-d", mktime(0, 0, 0, 1, 1, $ano));
				$fecha_actual_final   = date("Y-m-d", mktime(0, 0, 0, 12, 31, $ano)); */
				$condicion .=" AND ((substr(fecha_incorporacion::text, 0, 5)::integer = '$ano') OR (substr(fecha_desincorporacion::text, 0, 5)::integer = '$ano'))";
			}else{
				$mes = $this->data['movimiento_mueble']['selectmes'];
				if($mes==''){
					echo'<script>history.back(1);</script>';
				}else{
					/* $ano_final=$ano-1;
					$fecha_anterior_final = "31-12-".$ano_final;
					$fecha_actual_inicial = date("Y-m-d", mktime(0, 0, 0, $mes, 1, $ano));
					$fecha_actual_final   = date("Y-m-d", mktime(0, 0, 0, $mes+1, 0, $ano)); */
					$anoc_mesc = $ano."-".$this->zero($mes);
					// $anoc_mesc22 = $ano."-".($this->zero($mes)-1)."-01";
					$anoc_mesc22 = $ano."-".$this->zero($mes)."-01";
					// $condic22 = $condicion." AND ((fecha_incorporacion < '$anoc_mesc22' AND fecha_incorporacion != '1900-01-01') OR (fecha_desincorporacion < '$anoc_mesc22' AND fecha_desincorporacion != '1900-01-01'))";
					$condicion .=" AND ((substr(fecha_incorporacion::text, 0, 8)::text = '$anoc_mesc') OR (substr(fecha_desincorporacion::text, 0, 8)::text = '$anoc_mesc'))";
				}
			}

			// $condicion .=" AND ((fecha_incorporacion BETWEEN '$fecha_actual_inicial' AND '$fecha_actual_final') OR (fecha_desincorporacion BETWEEN '$fecha_actual_inicial' AND '$fecha_actual_final'))";

			$select_ubicaciones = $this->data['movimiento_mueble']['select_ubicaciones'];
			if($select_ubicaciones==1){
				$order1="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_republica,cod_estado,cod_municipio,conocido,cod_parroquia,cod_centro,cod_institucion,cod_dependencia,cod_dir_superior,cod_coordinacion,cod_secretaria,cod_direccion,cod_division,cod_departamento,cod_oficina,cod_tipo,cod_grupo,cod_subgrupo,cod_seccion,cantidad,numero_identificacion";
				$sql = "SELECT cod_estado, deno_estado, cod_municipio, deno_municipio, conocido, cod_parroquia, deno_parroquia, cod_centro, deno_centro, cod_institucion, deno_institucion, cod_dependencia, deno_dependencia, cod_dir_superior, deno_dir_superior, cod_coordinacion, deno_coordinacion, cod_secretaria, deno_secretaria, cod_direccion, deno_direccion, cod_division, deno_division, cod_departamento, deno_departamento, cod_oficina, deno_oficina,
							cod_tipo, deno_tipo, cod_grupo, deno_grupo, cod_subgrupo, deno_subgrupo, cod_seccion, deno_seccion, cod_tipo_incorporacion, deno_incorporacion, cod_tipo_desincorporacion, deno_desincorporacion, cantidad, valor_unitario, numero_identificacion, denominacion, fecha_incorporacion, fecha_desincorporacion FROM v_inventario_muebles_todo
						WHERE ".$condicion. " ORDER BY ".$order1." ASC;";

				// $sql22 = "SELECT cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_institucion, cod_dependencia, cod_dir_superior, cod_coordinacion, cod_secretaria, cod_direccion, cod_division, cod_departamento, cod_oficina, cod_tipo_desincorporacion, cantidad, valor_unitario FROM v_inventario_muebles_todo WHERE ".$condic22;

			}elseif($select_ubicaciones==2){

				if(isset($this->data['movimiento_mueble']['estado']) && $this->data['movimiento_mueble']['estado']!=''){
					$estado = $this->data['movimiento_mueble']['estado'];
					$condicion .= " AND cod_estado='$estado'";
					$condic22 .= " AND cod_estado='$estado'";
					if(isset($this->data['movimiento_mueble']['municipio']) && $this->data['movimiento_mueble']['municipio']!=''){
						$municipio = $this->data['movimiento_mueble']['municipio'];
						$condicion .= " AND cod_municipio='$municipio'";
						$condic22 .= " AND cod_municipio='$municipio'";
						if(isset($this->data['movimiento_mueble']['parroquia']) && $this->data['movimiento_mueble']['parroquia']!=''){
							$parroquia = $this->data['movimiento_mueble']['parroquia'];
							$condicion .= " AND cod_parroquia='$parroquia'";
							$condic22 .= " AND cod_parroquia='$parroquia'";
							if(isset($this->data['movimiento_mueble']['centropoblado']) && $this->data['movimiento_mueble']['centropoblado']!=''){
								$centropoblado = $this->data['movimiento_mueble']['centropoblado'];
								$condicion .= " AND cod_centro='$centropoblado'";
								$condic22 .= " AND cod_centro='$centropoblado'";
							}
						}
					}
				}

				if(isset($this->data['movimiento_mueble']['institucion']) && $this->data['movimiento_mueble']['institucion']!=''){
					$institucion = $this->data['movimiento_mueble']['institucion'];
					$condicion .= " AND cod_institucion='$institucion'";
					$condic22 .= " AND cod_institucion='$institucion'";
					if(isset($this->data['movimiento_mueble']['dependencia']) && $this->data['movimiento_mueble']['dependencia']!=''){
						$dependencia = $this->data['movimiento_mueble']['dependencia'];
						$condicion .= " AND cod_dependencia='$dependencia'";
						$condic22 .= " AND cod_dependencia='$dependencia'";
						if(isset($this->data['movimiento_mueble']['dirsuperior']) && $this->data['movimiento_mueble']['dirsuperior']!=''){
							$dirsuperior = $this->data['movimiento_mueble']['dirsuperior'];
							$condicion .= " AND cod_dir_superior='$dirsuperior'";
							$condic22 .= " AND cod_dir_superior='$dirsuperior'";
							if(isset($this->data['movimiento_mueble']['coordinacion']) && $this->data['movimiento_mueble']['coordinacion']!=''){
								$coordinacion = $this->data['movimiento_mueble']['coordinacion'];
								$condicion .= " AND cod_coordinacion='$coordinacion'";
								$condic22 .= " AND cod_coordinacion='$coordinacion'";
								if(isset($this->data['movimiento_mueble']['secretaria']) && $this->data['movimiento_mueble']['secretaria']!=''){
									$secretaria = $this->data['movimiento_mueble']['secretaria'];
									$condicion .= " AND cod_secretaria='$secretaria'";
									$condic22 .= " AND cod_secretaria='$secretaria'";
									if(isset($this->data['movimiento_mueble']['direccion']) && $this->data['movimiento_mueble']['direccion']!=''){
										$direccion = $this->data['movimiento_mueble']['direccion'];
										$condicion .= " AND cod_direccion='$direccion'";
										$condic22 .= " AND cod_direccion='$direccion'";
										if(isset($this->data['movimiento_mueble']['division']) && $this->data['movimiento_mueble']['division']!=''){
											$division = $this->data['movimiento_mueble']['division'];
											$condicion .= " AND cod_division='$division'";
											$condic22 .= " AND cod_division='$division'";
											if(isset($this->data['movimiento_mueble']['departamento']) && $this->data['movimiento_mueble']['departamento']!=''){
												$departamento = $this->data['movimiento_mueble']['departamento'];
												$condicion .= " AND cod_departamento='$departamento'";
												$condic22 .= " AND cod_departamento='$departamento'";
												if(isset($this->data['movimiento_mueble']['oficina']) && $this->data['movimiento_mueble']['oficina']!=''){
													$oficina = $this->data['movimiento_mueble']['oficina'];
													$condicion .= " AND cod_oficina='$oficina'";
													$condic22 .= " AND cod_oficina='$oficina'";
												}
											}
										}
									}
								}
							}
						}
					}
				}

/* $group = "cod_estado, deno_estado, cod_municipio, deno_municipio, conocido, cod_parroquia, deno_parroquia, cod_centro, deno_centro, cod_institucion, deno_institucion, cod_dependencia, deno_dependencia, cod_dir_superior, deno_dir_superior, cod_coordinacion, deno_coordinacion, cod_secretaria, deno_secretaria, cod_direccion, deno_direccion, cod_division, deno_division, cod_departamento, deno_departamento, cod_oficina, deno_oficina,
							cod_tipo, deno_tipo, cod_grupo, deno_grupo, cod_subgrupo, deno_subgrupo, cod_seccion, deno_seccion, cod_tipo_incorporacion, deno_incorporacion, cod_tipo_desincorporacion, deno_desincorporacion, cantidad, valor_unitario, numero_identificacion, denominacion, fecha_incorporacion, fecha_desincorporacion";
$order1= "cod_estado,cod_municipio,conocido,cod_parroquia,cod_centro,cod_institucion,cod_dependencia,cod_dir_superior,cod_coordinacion,cod_secretaria,cod_direccion,cod_division,cod_departamento,cod_oficina,cod_tipo,cod_grupo,cod_subgrupo,cod_seccion,cantidad,numero_identificacion";

	$sql = "SELECT $group FROM v_inventario_muebles_todo WHERE ".$condicion." group by ".$group." order by ".$order1." asc";
*/

				$order1="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_republica,cod_estado,cod_municipio,conocido,cod_parroquia,cod_centro,cod_institucion,cod_dependencia,cod_dir_superior,cod_coordinacion,cod_secretaria,cod_direccion,cod_division,cod_departamento,cod_oficina,cod_tipo,cod_grupo,cod_subgrupo,cod_seccion,cantidad,numero_identificacion";
				$sql = "SELECT cod_estado, deno_estado, cod_municipio, deno_municipio, conocido, cod_parroquia, deno_parroquia, cod_centro, deno_centro, cod_institucion, deno_institucion, cod_dependencia, deno_dependencia, cod_dir_superior, deno_dir_superior, cod_coordinacion, deno_coordinacion, cod_secretaria, deno_secretaria, cod_direccion, deno_direccion, cod_division, deno_division, cod_departamento, deno_departamento, cod_oficina, deno_oficina,
							cod_tipo, deno_tipo, cod_grupo, deno_grupo, cod_subgrupo, deno_subgrupo, cod_seccion, deno_seccion, cod_tipo_incorporacion, deno_incorporacion, cod_tipo_desincorporacion, deno_desincorporacion, cantidad, valor_unitario, numero_identificacion, denominacion, fecha_incorporacion, fecha_desincorporacion FROM v_inventario_muebles_todo
						WHERE ".$condicion. " ORDER BY ".$order1." ASC;";

				// $sql22 = "SELECT cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_institucion, cod_dependencia, cod_dir_superior, cod_coordinacion, cod_secretaria, cod_direccion, cod_division, cod_departamento, cod_oficina, cod_tipo_desincorporacion, cantidad, valor_unitario FROM v_inventario_muebles_todo WHERE ".$condic22;
			}


			if(isset($consolidacion) && $consolidacion==1){
				$campos = 'a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, cod_republica, cod_estado, deno_estado, cod_municipio, deno_municipio, conocido, cod_parroquia, deno_parroquia, cod_centro, deno_centro, cod_institucion, deno_institucion, cod_dependencia, deno_dependencia, cod_dir_superior, deno_dir_superior, cod_coordinacion, deno_coordinacion, cod_secretaria, deno_secretaria, cod_direccion, deno_direccion, cod_division, deno_division, cod_departamento, deno_departamento, cod_oficina, deno_oficina,';
				$group_by = 'a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, cod_republica, cod_estado, deno_estado, cod_municipio, deno_municipio, conocido, cod_parroquia, deno_parroquia, cod_centro, deno_centro, cod_institucion, deno_institucion, cod_dependencia, deno_dependencia, cod_dir_superior, deno_dir_superior, cod_coordinacion, deno_coordinacion, cod_secretaria, deno_secretaria, cod_direccion, deno_direccion, cod_division, deno_division, cod_departamento, deno_departamento, cod_oficina, deno_oficina;';
				$sql_datos = "SELECT ".$campos."
				count(cantidad) as cantidad,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_municipio=a.cod_municipio AND v.cod_centro=a.cod_centro AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_direccion=a.cod_direccion AND v.cod_division=a.cod_division AND v.cod_departamento=a.cod_departamento AND v.cod_oficina=a.cod_oficina AND v.cod_tipo_incorporacion != '0' AND v.fecha_incorporacion <= '$anoc_mesc22') as incorporacion_anterior,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_municipio=a.cod_municipio AND v.cod_centro=a.cod_centro AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_direccion=a.cod_direccion AND v.cod_division=a.cod_division AND v.cod_departamento=a.cod_departamento AND v.cod_oficina=a.cod_oficina AND v.cod_tipo_desincorporacion != '0' AND v.fecha_desincorporacion <= '$anoc_mesc22') as desincorporacion_anterior
				FROM v_inventario_muebles_todo a
				WHERE ".$condic22."
				GROUP BY ".$group_by;
			}else{
				$campos = 'a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, cod_republica, cod_estado, deno_estado, cod_municipio, deno_municipio, conocido, cod_parroquia, deno_parroquia, cod_centro, deno_centro, cod_institucion, deno_institucion, cod_dependencia, deno_dependencia, cod_dir_superior, deno_dir_superior, cod_coordinacion, deno_coordinacion, cod_secretaria, deno_secretaria, cod_direccion, deno_direccion, cod_division, deno_division, cod_departamento, deno_departamento, cod_oficina, deno_oficina,';
				$group_by = 'a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, cod_republica, cod_estado, deno_estado, cod_municipio, deno_municipio, conocido, cod_parroquia, deno_parroquia, cod_centro, deno_centro, cod_institucion, deno_institucion, cod_dependencia, deno_dependencia, cod_dir_superior, deno_dir_superior, cod_coordinacion, deno_coordinacion, cod_secretaria, deno_secretaria, cod_direccion, deno_direccion, cod_division, deno_division, cod_departamento, deno_departamento, cod_oficina, deno_oficina;';
				$sql_datos = "SELECT ".$campos."
				count(cantidad) as cantidad,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_dep=a.cod_dep AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_municipio=a.cod_municipio AND v.cod_centro=a.cod_centro AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_direccion=a.cod_direccion AND v.cod_division=a.cod_division AND v.cod_departamento=a.cod_departamento AND v.cod_oficina=a.cod_oficina AND v.cod_tipo_incorporacion != '0' AND v.fecha_incorporacion <= '$anoc_mesc22') as incorporacion_anterior,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_dep=a.cod_dep AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_municipio=a.cod_municipio AND v.cod_centro=a.cod_centro AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_direccion=a.cod_direccion AND v.cod_division=a.cod_division AND v.cod_departamento=a.cod_departamento AND v.cod_oficina=a.cod_oficina AND v.cod_tipo_desincorporacion !='0' AND v.fecha_desincorporacion <= '$anoc_mesc22') as desincorporacion_anterior
				FROM v_inventario_muebles_todo a
				WHERE ".$condic22."
				GROUP BY ".$group_by;
			}

			$datos = $this->v_inventario_muebles_todo->execute($sql);
			$datos22 = $this->v_inventario_muebles_todo->execute($sql_datos);
			//pr($datos);
			$this->set('consolidadop',isset($consolidacion)?$consolidacion:2);
			$this->set('datos',$datos);
			$this->set('datos22',$datos22);
			$this->set('ano',$ano);
			$this->set('mes',isset($mes)?$mes:0);
			// $this->set('fecha_inicial',$fecha_actual_inicial);
			// $this->set('fecha_final',$fecha_actual_final);
			$this->set('var',$var);
		}
 	}
 }



 function relacion_bienes_muebles_faltantes($var=null){
 	if($var!=null){
	$cod_presi     = $this->Session->read('SScodpresi');
	$cod_entidad   = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst      = $this->Session->read('SScodinst');
	$cod_dep       = $this->Session->read('SScoddep');
	$SScoddeporig  = $this->Session->read('SScoddeporig');
	$SScoddep      = $this->Session->read('SScoddep');

    $this->set('cod_presi',$cod_presi);
    $this->set('cod_entidad',$cod_entidad);
    $this->set('cod_tipo_inst',$cod_tipo_inst);
    $this->set('cod_inst',$cod_inst);
    $this->set('cod_dep',$cod_dep);

		if($var=='no'){
			$this->layout='ajax';
			$ano = $this->ano_ejecucion();
			if(isset($ano) && !empty($ano)){
				$ano_inicial = $ano - 20;
				$ano_final = $ano + 20;
				for($i=$ano_inicial; $i<=$ano_final; $i++){
					$array_ano[$i]=$i;
				}
			}else{
				$ano=date('Y');
				$ano_inicial = $ano - 20;
				$ano_final = $ano + 20;
				for($i=$ano_inicial; $i<=$ano_final; $i++){
					$array_ano[$i]=$i;
				}
			}
			$cod_presi = $this->Session->read('SScodpresi');
			$lista =  $this->cugd01_estados->generateList('cod_republica='.$cod_presi, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
			$lista_institucion =  $this->cugd02_institucion->generateList(null, 'cod_institucion ASC', null, '{n}.cugd02_institucion.cod_institucion', '{n}.cugd02_institucion.denominacion');
			$this->concatena($lista, 'lista');
			$this->concatena($lista_institucion, 'lista_institucion');
			$this->set('ano',$ano);
			$this->set('array_ano',$array_ano);
			$this->set('var',$var);

	$cont = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA()." and tipo_documento='10000'");
	if($cont == 0){
		$this->set('mensaje','Por favor, ingrese los nombres de los firmantes');
		$this->set('nombre_primera_firma','');
		$this->set('cargo_primera_firma','');
		$this->set('nombre_segunda_firma','');
		$this->set('cargo_segunda_firma','');
		$this->set('nombre_tercera_firma','N/A');
		$this->set('cargo_tercera_firma','N/A');
		$this->set('nombre_cuarta_firma','N/A');
		$this->set('cargo_cuarta_firma','N/A');
		$this->set('tipo_doc_anul', 10000);
		$firma_existe = 'no';
		echo'<script>';
       		echo" document.getElementById('enviar').disabled = 'true'; ";
		echo'</script>';
	}else{
		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=10000");
		$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
		$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
		$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
		$this->set('tipo_doc_anul',10000);
		$firma_existe = 'si';
		echo'<script>';
       		echo" document.getElementById('enviar').disabled = ''; ";
		echo'</script>';
	}
		$this->set('firma_existe',$firma_existe);

		}elseif($var=='si'){
			$this->layout='pdf';
			$cp = $this->Session->read('SScodpresi');
			$ce = $this->Session->read('SScodentidad');
			$cti = $this->Session->read('SScodtipoinst');
			$ci = $this->Session->read('SScodinst');
			$cd = $this->Session->read('SScoddep');
			$condicion  = "";

		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=10000");
		if(!empty($firmantes)){
			$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
			$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
			$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
			$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
			$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
			$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
			$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
			$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
		}else{
			$this->set('nombre_primera_firma','');
			$this->set('cargo_primera_firma','');
			$this->set('nombre_segunda_firma','');
			$this->set('cargo_segunda_firma','');
			$this->set('nombre_tercera_firma','');
			$this->set('cargo_tercera_firma','');
			$this->set('nombre_cuarta_firma','');
			$this->set('cargo_cuarta_firma','');
		}

		if($cd==1){
			$consolidacion=$this->data['movimiento_mueble']['consolidacion'];
			if($consolidacion==1){
				$condicion .= "cod_presi='$cp' and cod_entidad='$ce' and cod_tipo_inst='$cti' and cod_inst='$ci'";
			}elseif($consolidacion==2){
				$cod_dependencia = $this->cod_dep_consolidado();
				$condicion .= "cod_presi='$cp' and cod_entidad='$ce' and cod_tipo_inst='$cti' and cod_inst='$ci' and cod_dep='$cod_dependencia'";
			}
		}else{
			$condicion .= "cod_presi='$cp' and cod_entidad='$ce' and cod_tipo_inst='$cti' and cod_inst='$ci' and cod_dep='$cd'";
		}

			$ano = $this->data['movimiento_mueble']['ano'];
			// $por_ano = $this->data['movimiento_mueble']['por_ano']; // al descomentar esta linea recordar descomentar tambien de la vista el radio de todo el ano o 1 mes especifico
			$por_ano = 2;
			$code_desinc = 60;
			$condicion .=" AND cod_tipo_desincorporacion = 60";
			if($por_ano==1){
				/* $ano_final=$ano-1;
				$fecha_anterior_final = "31-12-".$ano_final;
				$fecha_actual_inicial = date("Y-m-d", mktime(0, 0, 0, 1, 1, $ano));
				$fecha_actual_final   = date("Y-m-d", mktime(0, 0, 0, 12, 31, $ano)); */
				$condicion .=" AND ((substr(fecha_incorporacion::text, 0, 5)::integer = '$ano') OR (substr(fecha_desincorporacion::text, 0, 5)::integer = '$ano'))";
			}else{
				$mes = $this->data['movimiento_mueble']['selectmes'];
				if($mes==''){
					echo'<script>history.back(1);</script>';
				}else{
					/* $ano_final=$ano-1;
					$fecha_anterior_final = "31-12-".$ano_final;
					$fecha_actual_inicial = date("Y-m-d", mktime(0, 0, 0, $mes, 1, $ano));
					$fecha_actual_final   = date("Y-m-d", mktime(0, 0, 0, $mes+1, 0, $ano)); */
					$anoc_mesc = $ano."-".$this->zero($mes);
					$condicion .=" AND ((substr(fecha_incorporacion::text, 0, 8)::text = '$anoc_mesc') OR (substr(fecha_desincorporacion::text, 0, 8)::text = '$anoc_mesc'))";
				}
			}

			// $condicion .=" AND ((fecha_incorporacion BETWEEN '$fecha_actual_inicial' AND '$fecha_actual_final') OR (fecha_desincorporacion BETWEEN '$fecha_actual_inicial' AND '$fecha_actual_final'))";

			$select_ubicaciones = $this->data['movimiento_mueble']['select_ubicaciones'];
			if($select_ubicaciones==1){
				$sql = "SELECT cod_estado, deno_estado, cod_municipio, deno_municipio, conocido, cod_parroquia, deno_parroquia, cod_centro, deno_centro, cod_institucion, deno_institucion, cod_dependencia, deno_dependencia, cod_dir_superior, deno_dir_superior, cod_coordinacion, deno_coordinacion, cod_secretaria, deno_secretaria, cod_direccion, deno_direccion, cod_division, deno_division, cod_departamento, deno_departamento, cod_oficina, deno_oficina,
							cod_tipo, deno_tipo, cod_grupo, deno_grupo, cod_subgrupo, deno_subgrupo, cod_seccion, deno_seccion, cod_tipo_incorporacion, deno_incorporacion, cod_tipo_desincorporacion, deno_desincorporacion, cantidad, valor_unitario, numero_identificacion, denominacion, fecha_incorporacion, fecha_desincorporacion, numero_asiento_registro, fecha_proceso_desincorporacion FROM v_inventario_muebles_todo
						WHERE ".$condicion;
			}elseif($select_ubicaciones==2){

				if(isset($this->data['movimiento_mueble']['estado']) && $this->data['movimiento_mueble']['estado']!=''){
					$estado = $this->data['movimiento_mueble']['estado'];
					$condicion .= " AND cod_estado='$estado'";
					if(isset($this->data['movimiento_mueble']['municipio']) && $this->data['movimiento_mueble']['municipio']!=''){
						$municipio = $this->data['movimiento_mueble']['municipio'];
						$condicion .= " AND cod_municipio='$municipio'";
						if(isset($this->data['movimiento_mueble']['parroquia']) && $this->data['movimiento_mueble']['parroquia']!=''){
							$parroquia = $this->data['movimiento_mueble']['parroquia'];
							$condicion .= " AND cod_parroquia='$parroquia'";
							if(isset($this->data['movimiento_mueble']['centropoblado']) && $this->data['movimiento_mueble']['centropoblado']!=''){
								$centropoblado = $this->data['movimiento_mueble']['centropoblado'];
								$condicion .= " AND cod_centro='$centropoblado'";
							}
						}
					}
				}

				if(isset($this->data['movimiento_mueble']['institucion']) && $this->data['movimiento_mueble']['institucion']!=''){
					$institucion = $this->data['movimiento_mueble']['institucion'];
					$condicion .= " AND cod_institucion='$institucion'";
					if(isset($this->data['movimiento_mueble']['dependencia']) && $this->data['movimiento_mueble']['dependencia']!=''){
						$dependencia = $this->data['movimiento_mueble']['dependencia'];
						$condicion .= " AND cod_dependencia='$dependencia'";
						if(isset($this->data['movimiento_mueble']['dirsuperior']) && $this->data['movimiento_mueble']['dirsuperior']!=''){
							$dirsuperior = $this->data['movimiento_mueble']['dirsuperior'];
							$condicion .= " AND cod_dir_superior='$dirsuperior'";
							if(isset($this->data['movimiento_mueble']['coordinacion']) && $this->data['movimiento_mueble']['coordinacion']!=''){
								$coordinacion = $this->data['movimiento_mueble']['coordinacion'];
								$condicion .= " AND cod_coordinacion='$coordinacion'";
								if(isset($this->data['movimiento_mueble']['secretaria']) && $this->data['movimiento_mueble']['secretaria']!=''){
									$secretaria = $this->data['movimiento_mueble']['secretaria'];
									$condicion .= " AND cod_secretaria='$secretaria'";
									if(isset($this->data['movimiento_mueble']['direccion']) && $this->data['movimiento_mueble']['direccion']!=''){
										$direccion = $this->data['movimiento_mueble']['direccion'];
										$condicion .= " AND cod_direccion='$direccion'";
										if(isset($this->data['movimiento_mueble']['division']) && $this->data['movimiento_mueble']['division']!=''){
											$division = $this->data['movimiento_mueble']['division'];
											$condicion .= " AND cod_division='$division'";
											if(isset($this->data['movimiento_mueble']['departamento']) && $this->data['movimiento_mueble']['departamento']!=''){
												$departamento = $this->data['movimiento_mueble']['departamento'];
												$condicion .= " AND cod_departamento='$departamento'";
												if(isset($this->data['movimiento_mueble']['oficina']) && $this->data['movimiento_mueble']['oficina']!=''){
													$oficina = $this->data['movimiento_mueble']['oficina'];
													$condicion .= " AND cod_oficina='$oficina'";
												}
											}
										}
									}
								}
							}
						}
					}
				}

				$order1="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_republica,cod_estado,cod_municipio,conocido,cod_parroquia,cod_centro,cod_institucion,cod_dependencia,cod_dir_superior,cod_coordinacion,cod_secretaria,cod_direccion,cod_division,cod_departamento,cod_oficina,cod_tipo,cod_grupo,cod_subgrupo,cod_seccion,cantidad,numero_identificacion";
				$sql = "SELECT cod_estado, deno_estado, cod_municipio, deno_municipio, conocido, cod_parroquia, deno_parroquia, cod_centro, deno_centro, cod_institucion, deno_institucion, cod_dependencia, deno_dependencia, cod_dir_superior, deno_dir_superior, cod_coordinacion, deno_coordinacion, cod_secretaria, deno_secretaria, cod_direccion, deno_direccion, cod_division, deno_division, cod_departamento, deno_departamento, cod_oficina, deno_oficina,
							cod_tipo, deno_tipo, cod_grupo, deno_grupo, cod_subgrupo, deno_subgrupo, cod_seccion, deno_seccion, cod_tipo_incorporacion, deno_incorporacion, cod_tipo_desincorporacion, deno_desincorporacion, cantidad, valor_unitario, numero_identificacion, denominacion, fecha_incorporacion, fecha_desincorporacion, numero_asiento_registro, fecha_proceso_desincorporacion FROM v_inventario_muebles_todo
						WHERE ".$condicion. " ORDER BY ".$order1." ASC;";
			}

			$datos = $this->v_inventario_muebles_todo->execute($sql);
			$this->set('consolidadop',isset($consolidacion)?$consolidacion:2);
			$this->set('datos',$datos);
			$this->set('ano',$ano);
			$this->set('mes',isset($mes)?$mes:0);
			$this->set('code_desinc',$code_desinc);
			$this->set('var',$var);
		}
 	}
 }

 function inventario_bienes_muebles_faltantes($var=null){
 	if($var!=null){
		$cod_presi     = $this->Session->read('SScodpresi');
		$cod_entidad   = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst      = $this->Session->read('SScodinst');
		$cod_dep       = $this->Session->read('SScoddep');
		$SScoddeporig  = $this->Session->read('SScoddeporig');
		$SScoddep      = $this->Session->read('SScoddep');

    $this->set('cod_presi',$cod_presi);
    $this->set('cod_entidad',$cod_entidad);
    $this->set('cod_tipo_inst',$cod_tipo_inst);
    $this->set('cod_inst',$cod_inst);
    $this->set('cod_dep',$cod_dep);

		if($var=='no'){
			$this->layout='ajax';
			$ano = $this->ano_ejecucion();
			if(isset($ano) && !empty($ano)){
				$ano_inicial = $ano - 20;
				$ano_final = $ano + 20;
				for($i=$ano_inicial; $i<=$ano_final; $i++){
					$array_ano[$i]=$i;
				}
			}else{
				$ano=date('Y');
				$ano_inicial = $ano - 20;
				$ano_final = $ano + 20;
				for($i=$ano_inicial; $i<=$ano_final; $i++){
					$array_ano[$i]=$i;
				}
			}
			$cod_presi = $this->Session->read('SScodpresi');
			$lista =  $this->cugd01_estados->generateList('cod_republica='.$cod_presi, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
			$lista_institucion =  $this->cugd02_institucion->generateList(null, 'cod_institucion ASC', null, '{n}.cugd02_institucion.cod_institucion', '{n}.cugd02_institucion.denominacion');
			$this->concatena($lista, 'lista');
			$this->concatena($lista_institucion, 'lista_institucion');
			$this->set('ano',$ano);
			$this->set('array_ano',$array_ano);
			$this->set('var',$var);

			$cont = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA()." and tipo_documento='10000'");
			if($cont == 0){
				$this->set('mensaje','Por favor, ingrese los nombres de los firmantes');
				$this->set('nombre_primera_firma','');
				$this->set('cargo_primera_firma','');
				$this->set('nombre_segunda_firma','');
				$this->set('cargo_segunda_firma','');
				$this->set('nombre_tercera_firma','N/A');
				$this->set('cargo_tercera_firma','N/A');
				$this->set('nombre_cuarta_firma','N/A');
				$this->set('cargo_cuarta_firma','N/A');
				$this->set('tipo_doc_anul', 10000);
				$firma_existe = 'no';
				echo'<script>';
		       		echo" document.getElementById('enviar').disabled = 'true'; ";
				echo'</script>';
			}else{
				$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=10000");
				$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
				$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
				$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
				$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
				$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
				$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
				$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
				$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
				$this->set('tipo_doc_anul',10000);
				$firma_existe = 'si';
				echo'<script>';
		       		echo" document.getElementById('enviar').disabled = ''; ";
				echo'</script>';
			}
			$this->set('firma_existe',$firma_existe);

		}elseif($var=='si'){
			$this->layout='pdf';
			$cp = $this->Session->read('SScodpresi');
			$ce = $this->Session->read('SScodentidad');
			$cti = $this->Session->read('SScodtipoinst');
			$ci = $this->Session->read('SScodinst');
			$cd = $this->Session->read('SScoddep');
			$condicion  = "";

			$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=10000");
			if(!empty($firmantes)){
				$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
				$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
				$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
				$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
				$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
				$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
				$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
				$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
			}else{
				$this->set('nombre_primera_firma','');
				$this->set('cargo_primera_firma','');
				$this->set('nombre_segunda_firma','');
				$this->set('cargo_segunda_firma','');
				$this->set('nombre_tercera_firma','');
				$this->set('cargo_tercera_firma','');
				$this->set('nombre_cuarta_firma','');
				$this->set('cargo_cuarta_firma','');
			}

			if($cd==1){
				$consolidacion=$this->data['movimiento_mueble']['consolidacion'];
				if($consolidacion==1){
					$condicion .= "cod_presi='$cp' and cod_entidad='$ce' and cod_tipo_inst='$cti' and cod_inst='$ci'";
				}elseif($consolidacion==2){
					$cod_dependencia = $this->cod_dep_consolidado();
					$condicion .= "cod_presi='$cp' and cod_entidad='$ce' and cod_tipo_inst='$cti' and cod_inst='$ci' and cod_dep='$cod_dependencia'";
				}
			}else{
				$condicion .= "cod_presi='$cp' and cod_entidad='$ce' and cod_tipo_inst='$cti' and cod_inst='$ci' and cod_dep='$cd'";
			}

			//$ano = $this->data['movimiento_mueble']['ano'];
			// $por_ano = $this->data['movimiento_mueble']['por_ano']; // al descomentar esta linea recordar descomentar tambien de la vista el radio de todo el ano o 1 mes especifico
			//$por_ano = 2;
			$code_desinc = 60;
			//if($por_ano==1){
				/* $ano_final=$ano-1;
				$fecha_anterior_final = "31-12-".$ano_final;
				*/
				//$fecha_actual_inicial = date("Y-m-d", mktime(0, 0, 0, 1, 1, $ano));
				//$fecha_actual_final   = date("Y-m-d", mktime(0, 0, 0, 12, 31, $ano)); 
				$condicion .=" AND cod_tipo_desincorporacion = '$code_desinc' AND fecha_desincorporacion > '1900-01-01'";
			/*}else{
				$mes = $this->data['movimiento_mueble']['selectmes'];
				if($mes==''){
					echo'<script>history.back(1);</script>';
				}else{
					/* $ano_final=$ano-1;
					$fecha_anterior_final = "31-12-".$ano_final;
					$fecha_actual_inicial = date("Y-m-d", mktime(0, 0, 0, $mes, 1, $ano));
					$fecha_actual_final   = date("Y-m-d", mktime(0, 0, 0, $mes+1, 0, $ano)); * /
					$anoc_mesc = $ano."-".$this->zero($mes);
					$condicion .=" AND cod_tipo_desincorporacion = '$code_desinc' AND ((substr(fecha_incorporacion::text, 0, 8)::text = '$anoc_mesc') OR (substr(fecha_desincorporacion::text, 0, 8)::text = '$anoc_mesc'))";
				}
			}*/

			// $condicion .=" AND ((fecha_incorporacion BETWEEN '$fecha_actual_inicial' AND '$fecha_actual_final') OR (fecha_desincorporacion BETWEEN '$fecha_actual_inicial' AND '$fecha_actual_final'))";

			$select_ubicaciones = $this->data['movimiento_mueble']['select_ubicaciones'];
			if($select_ubicaciones==1){
				$sql = "SELECT cod_estado, deno_estado, cod_municipio, deno_municipio, conocido, cod_parroquia, deno_parroquia, cod_centro, deno_centro, cod_institucion, deno_institucion, cod_dependencia, deno_dependencia, cod_dir_superior, deno_dir_superior, cod_coordinacion, deno_coordinacion, cod_secretaria, deno_secretaria, cod_direccion, deno_direccion, cod_division, deno_division, cod_departamento, deno_departamento, cod_oficina, deno_oficina,
							cod_tipo, deno_tipo, cod_grupo, deno_grupo, cod_subgrupo, deno_subgrupo, cod_seccion, deno_seccion, cod_tipo_incorporacion, deno_incorporacion, cod_tipo_desincorporacion, deno_desincorporacion, cantidad, valor_unitario, numero_identificacion, denominacion, fecha_incorporacion, fecha_desincorporacion, numero_asiento_registro, fecha_proceso_desincorporacion FROM v_inventario_muebles_todo
						WHERE ".$condicion;
			}elseif($select_ubicaciones==2){

				if(isset($this->data['movimiento_mueble']['estado']) && $this->data['movimiento_mueble']['estado']!=''){
					$estado = $this->data['movimiento_mueble']['estado'];
					$condicion .= " AND cod_estado='$estado'";
					if(isset($this->data['movimiento_mueble']['municipio']) && $this->data['movimiento_mueble']['municipio']!=''){
						$municipio = $this->data['movimiento_mueble']['municipio'];
						$condicion .= " AND cod_municipio='$municipio'";
						if(isset($this->data['movimiento_mueble']['parroquia']) && $this->data['movimiento_mueble']['parroquia']!=''){
							$parroquia = $this->data['movimiento_mueble']['parroquia'];
							$condicion .= " AND cod_parroquia='$parroquia'";
							if(isset($this->data['movimiento_mueble']['centropoblado']) && $this->data['movimiento_mueble']['centropoblado']!=''){
								$centropoblado = $this->data['movimiento_mueble']['centropoblado'];
								$condicion .= " AND cod_centro='$centropoblado'";
							}
						}
					}
				}

				if(isset($this->data['movimiento_mueble']['institucion']) && $this->data['movimiento_mueble']['institucion']!=''){
					$institucion = $this->data['movimiento_mueble']['institucion'];
					$condicion .= " AND cod_institucion='$institucion'";
					if(isset($this->data['movimiento_mueble']['dependencia']) && $this->data['movimiento_mueble']['dependencia']!=''){
						$dependencia = $this->data['movimiento_mueble']['dependencia'];
						$condicion .= " AND cod_dependencia='$dependencia'";
						if(isset($this->data['movimiento_mueble']['dirsuperior']) && $this->data['movimiento_mueble']['dirsuperior']!=''){
							$dirsuperior = $this->data['movimiento_mueble']['dirsuperior'];
							$condicion .= " AND cod_dir_superior='$dirsuperior'";
							if(isset($this->data['movimiento_mueble']['coordinacion']) && $this->data['movimiento_mueble']['coordinacion']!=''){
								$coordinacion = $this->data['movimiento_mueble']['coordinacion'];
								$condicion .= " AND cod_coordinacion='$coordinacion'";
								if(isset($this->data['movimiento_mueble']['secretaria']) && $this->data['movimiento_mueble']['secretaria']!=''){
									$secretaria = $this->data['movimiento_mueble']['secretaria'];
									$condicion .= " AND cod_secretaria='$secretaria'";
									if(isset($this->data['movimiento_mueble']['direccion']) && $this->data['movimiento_mueble']['direccion']!=''){
										$direccion = $this->data['movimiento_mueble']['direccion'];
										$condicion .= " AND cod_direccion='$direccion'";
										if(isset($this->data['movimiento_mueble']['division']) && $this->data['movimiento_mueble']['division']!=''){
											$division = $this->data['movimiento_mueble']['division'];
											$condicion .= " AND cod_division='$division'";
											if(isset($this->data['movimiento_mueble']['departamento']) && $this->data['movimiento_mueble']['departamento']!=''){
												$departamento = $this->data['movimiento_mueble']['departamento'];
												$condicion .= " AND cod_departamento='$departamento'";
												if(isset($this->data['movimiento_mueble']['oficina']) && $this->data['movimiento_mueble']['oficina']!=''){
													$oficina = $this->data['movimiento_mueble']['oficina'];
													$condicion .= " AND cod_oficina='$oficina'";
												}
											}
										}
									}
								}
							}
						}
					}
				}

				$order1="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_republica,cod_estado,cod_municipio,conocido,cod_parroquia,cod_centro,cod_institucion,cod_dependencia,cod_dir_superior,cod_coordinacion,cod_secretaria,cod_direccion,cod_division,cod_departamento,cod_oficina,cod_tipo,cod_grupo,cod_subgrupo,cod_seccion,cantidad,numero_identificacion";
				$sql = "SELECT cod_estado, deno_estado, cod_municipio, deno_municipio, conocido, cod_parroquia, deno_parroquia, cod_centro, deno_centro, cod_institucion, deno_institucion, cod_dependencia, deno_dependencia, cod_dir_superior, deno_dir_superior, cod_coordinacion, deno_coordinacion, cod_secretaria, deno_secretaria, cod_direccion, deno_direccion, cod_division, deno_division, cod_departamento, deno_departamento, cod_oficina, deno_oficina,
							cod_tipo, deno_tipo, cod_grupo, deno_grupo, cod_subgrupo, deno_subgrupo, cod_seccion, deno_seccion, cod_tipo_incorporacion, deno_incorporacion, cod_tipo_desincorporacion, deno_desincorporacion, cantidad, valor_unitario, numero_identificacion, denominacion, fecha_incorporacion, fecha_desincorporacion, numero_asiento_registro, fecha_proceso_desincorporacion FROM v_inventario_muebles_todo
						WHERE ".$condicion. " ORDER BY ".$order1." ASC;";
			}
			
			$datos = $this->v_inventario_muebles_todo->execute($sql);
			$this->set('consolidadop',isset($consolidacion)?$consolidacion:2);
			$this->set('datos',$datos);
			//$this->set('ano',$ano);
			//$this->set('mes',isset($mes)?$mes:0);
			$this->set('code_desinc',$code_desinc);
			$this->set('var',$var);
		}
 	}
 }




/**
 * @Nombreform1=null,
 * @CampoPrevio1=null,
 * @SelectValue1=null
 */
 function select_geografico($Nombreform1=null, $CampoPrevio1=null, $SelectValue1=null){
	$this->layout="ajax";
	$Nombreform = strtolower($Nombreform1);
	$CampoPrevio = strtolower($CampoPrevio1);
	$SelectValue = strtolower($SelectValue1);

	if($SelectValue1==''){
		$this->set('lista',array('0'=>''));
		$this->set('pase',0);
	}else{
		switch($CampoPrevio){
			case 'estado':
						$cod_presi = $this->Session->read('SScodpresi');
						//Se escribe el codigo del estado en la sesion.
						$this->Session->write('reporte_bienes_estado',$SelectValue);
						//El campo a cargar va a ser municipio.
						$CampoActual='municipio';
						//El select siguiente a cargar va a ser el de la parroquia.
						$CapaSiguiente='td-select-parroquia';
						//Se busca en la tabla de municipios.
						$condicion="cod_republica='$cod_presi' and cod_estado=".$SelectValue;
						$datos = $this->cugd01_estados->findAll($condicion,'cod_estado, denominacion',null);
						$codigo = $this->AddCeroR2($datos[0]['cugd01_estados']['cod_estado']);
						$denominacion = $datos[0]['cugd01_estados']['denominacion'];
						$lista = $this->cugd01_municipios->generateList($condicion, 'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
				break;

			case 'municipio':
						$cod_presi = $this->Session->read('SScodpresi');
						$estado = $this->Session->read('reporte_bienes_estado');
						$this->Session->write('reporte_bienes_municipio',$SelectValue);
						$CampoActual='parroquia';
						$CapaSiguiente='td-select-centropoblado';
						$condicion="cod_republica='$cod_presi' and cod_estado='$estado' and cod_municipio=".$SelectValue;
						$datos = $this->cugd01_municipios->findAll($condicion,'cod_municipio, denominacion',null);
						$codigo = $this->AddCeroR2($datos[0]['cugd01_municipios']['cod_municipio']);
						$denominacion = $datos[0]['cugd01_municipios']['denominacion'];
						$lista = $this->cugd01_parroquias->generateList($condicion, 'cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');

				break;

			case 'parroquia':
						$cod_presi = $this->Session->read('SScodpresi');
						$estado = $this->Session->read('reporte_bienes_estado');
						$municipio = $this->Session->read('reporte_bienes_municipio');
						$this->Session->write('reporte_bienes_parroquia',$SelectValue);
						$CampoActual='centropoblado';
						$CapaSiguiente='td-vacio';
						$condicion="cod_republica='$cod_presi' and cod_estado='$estado' and cod_municipio='$municipio' and cod_parroquia=".$SelectValue;
						$datos = $this->cugd01_parroquias->findAll($condicion,'cod_parroquia, denominacion',null);
						$codigo = $this->AddCeroR2($datos[0]['cugd01_parroquias']['cod_parroquia']);
						$denominacion = $datos[0]['cugd01_parroquias']['denominacion'];
						$lista = $this->cugd01_centropoblados->generateList($condicion, 'cod_centro ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
				break;

			case 'centropoblado':
						$cod_presi = $this->Session->read('SScodpresi');
						$estado = $this->Session->read('reporte_bienes_estado');
						$municipio = $this->Session->read('reporte_bienes_municipio');
						$parroquia = $this->Session->read('reporte_bienes_parroquia');
						$this->Session->write('reporte_bienes_centropoblado',$SelectValue);
						$condicion="cod_republica='$cod_presi' and cod_estado='$estado' and cod_municipio='$municipio' and cod_parroquia='$parroquia' and cod_centro=".$SelectValue;
						$lista = $this->cugd01_centropoblados->findAll($condicion,'cod_centro, denominacion',null);
						$codigo = $this->AddCeroR2($lista[0]['cugd01_centropoblados']['cod_centro']);
						$denominacion = $lista[0]['cugd01_centropoblados']['denominacion'];
						$CampoActual='ultimo';
						$CapaSiguiente='td-vacio';
				break;

			default : echo 'error en el switch, cayo en default';
				break;
		}//fin switch

		if(count($lista)!=0){
			$this->concatena($lista, 'lista');
			$this->set('codigo',$codigo);
			$this->set('denominacion',$denominacion);
			$this->set('SelectValue',$SelectValue);
			$this->set('Nombreform',$Nombreform);
			$this->set('CampoPrevio',$CampoPrevio);
			$this->set('CampoActual',$CampoActual);
			$this->set('CapaSiguiente',$CapaSiguiente);
			$this->set('pase',1);

		}else{
			$this->set('lista',array('0'=>''));
			$this->set('pase',0);
		}

	}//fin SelectValue1 == vacio
 }//fin select_geografico



/*
 * @Nombreform1=null,
 * @CampoPrevio1=null,
 * @SelectValue1=null
 */
 function select_administrativo($Nombreform1=null, $CampoPrevio1=null, $SelectValue1=null){
	$this->layout="ajax";
	$Nombreform = strtolower($Nombreform1);
	$CampoPrevio = strtolower($CampoPrevio1);
	$SelectValue = strtolower($SelectValue1);

	if($SelectValue1==''){
		$this->set('lista',array('0'=>''));
		$this->set('pase',0);
	}else{
		switch($CampoPrevio){
			case 'institucion':
						//Se escribe el codigo del estado en la sesion.
						$this->Session->write('reporte_bienes_institucion',$SelectValue);
						//El campo a cargar va a ser municipio.
						$CampoActual='dependencia';
						//El select siguiente a cargar va a ser el de la parroquia.
						$CapaSiguiente='td-select-dirsuperior';
						//Se busca en la tabla de municipios.
						$condicion="cod_institucion=".$SelectValue;
						$datos = $this->cugd02_institucion->findAll($condicion,'cod_institucion, denominacion',null);
						$codigo = $this->AddCeroR2($datos[0]['cugd02_institucion']['cod_institucion']);
						$denominacion = $datos[0]['cugd02_institucion']['denominacion'];
						$lista = $this->cugd02_dependencia->generateList($condicion, 'cod_dependencia ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
				break;

			case 'dependencia':
						$institucion = $this->Session->read('reporte_bienes_institucion');
						$this->Session->write('reporte_bienes_dependencia',$SelectValue);
						$CampoActual='dirsuperior';
						$CapaSiguiente='td-select-coordinacion';
						$condicion="cod_institucion='$institucion' and cod_dependencia=".$SelectValue;
						$datos = $this->cugd02_dependencia->findAll($condicion,'cod_dependencia, denominacion',null);
						$codigo = $this->AddCeroR2($datos[0]['cugd02_dependencia']['cod_dependencia']);
						$denominacion = $datos[0]['cugd02_dependencia']['denominacion'];
						$lista = $this->cugd02_direccionsuperior->generateList($condicion, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');

				break;

			case 'dirsuperior':
						$institucion = $this->Session->read('reporte_bienes_institucion');
						$dependencia = $this->Session->read('reporte_bienes_dependencia');
						$this->Session->write('reporte_bienes_dirsuperior',$SelectValue);
						$CampoActual='coordinacion';
						$CapaSiguiente='td-select-secretaria';
						$condicion="cod_institucion='$institucion' and cod_dependencia='$dependencia' and cod_dir_superior=".$SelectValue;
						$datos = $this->cugd02_direccionsuperior->findAll($condicion,'cod_dir_superior, denominacion',null);
						$codigo = $this->AddCeroR2($datos[0]['cugd02_direccionsuperior']['cod_dir_superior']);
						$denominacion = $datos[0]['cugd02_direccionsuperior']['denominacion'];
						$lista = $this->cugd02_coordinacion->generateList($condicion, 'cod_coordinacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
				break;

			case 'coordinacion':
						$institucion = $this->Session->read('reporte_bienes_institucion');
						$dependencia = $this->Session->read('reporte_bienes_dependencia');
						$dirsuperior = $this->Session->read('reporte_bienes_dirsuperior');
						$this->Session->write('reporte_bienes_coordinacion',$SelectValue);
						$CampoActual='secretaria';
						$CapaSiguiente='td-select-direccion';
						$condicion="cod_institucion='$institucion' and cod_dependencia='$dependencia' and cod_dir_superior='$dirsuperior' and cod_coordinacion=".$SelectValue;
						$datos = $this->cugd02_coordinacion->findAll($condicion,'cod_coordinacion, denominacion',null);
						$codigo = $this->AddCeroR2($datos[0]['cugd02_coordinacion']['cod_coordinacion']);
						$denominacion = $datos[0]['cugd02_coordinacion']['denominacion'];
						$lista = $this->cugd02_secretaria->generateList($condicion, 'cod_secretaria ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
				break;

			case 'secretaria':
						$institucion = $this->Session->read('reporte_bienes_institucion');
						$dependencia = $this->Session->read('reporte_bienes_dependencia');
						$dirsuperior = $this->Session->read('reporte_bienes_dirsuperior');
						$coordinacion = $this->Session->read('reporte_bienes_coordinacion');
						$this->Session->write('reporte_bienes_secretaria',$SelectValue);
						$CampoActual='direccion';
						$CapaSiguiente='td-select-division';
						$condicion="cod_institucion='$institucion' and cod_dependencia='$dependencia' and cod_dir_superior='$dirsuperior' and cod_coordinacion='$coordinacion' and cod_secretaria=".$SelectValue;
						$datos = $this->cugd02_secretaria->findAll($condicion,'cod_secretaria, denominacion',null);
						$codigo = $this->AddCeroR2($datos[0]['cugd02_secretaria']['cod_secretaria']);
						$denominacion = $datos[0]['cugd02_secretaria']['denominacion'];
						$lista = $this->cugd02_direccion->generateList($condicion, 'cod_direccion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
				break;

			case 'direccion':
						$institucion = $this->Session->read('reporte_bienes_institucion');
						$dependencia = $this->Session->read('reporte_bienes_dependencia');
						$dirsuperior = $this->Session->read('reporte_bienes_dirsuperior');
						$coordinacion = $this->Session->read('reporte_bienes_coordinacion');
						$secretaria = $this->Session->read('reporte_bienes_secretaria');
						$this->Session->write('reporte_bienes_direccion',$SelectValue);
						$CampoActual='division';
						$CapaSiguiente='td-select-departamento';
						$condicion="cod_institucion='$institucion' and cod_dependencia='$dependencia' and cod_dir_superior='$dirsuperior' and cod_coordinacion='$coordinacion' and cod_secretaria='$secretaria' and cod_direccion=".$SelectValue;
						$datos = $this->cugd02_direccion->findAll($condicion,'cod_direccion, denominacion',null);
						$codigo = $this->AddCeroR2($datos[0]['cugd02_direccion']['cod_direccion']);
						$denominacion = $datos[0]['cugd02_direccion']['denominacion'];
						$lista = $this->cugd02_division->generateList($condicion, 'cod_division ASC', null, '{n}.cugd02_division.cod_division', '{n}.cugd02_division.denominacion');
				break;

			case 'division':
						$institucion = $this->Session->read('reporte_bienes_institucion');
						$dependencia = $this->Session->read('reporte_bienes_dependencia');
						$dirsuperior = $this->Session->read('reporte_bienes_dirsuperior');
						$coordinacion = $this->Session->read('reporte_bienes_coordinacion');
						$secretaria = $this->Session->read('reporte_bienes_secretaria');
						$direccion = $this->Session->read('reporte_bienes_direccion');
						$this->Session->write('reporte_bienes_division',$SelectValue);
						$CampoActual='departamento';
						$CapaSiguiente='td-select-oficina';
						$condicion="cod_institucion='$institucion' and cod_dependencia='$dependencia' and cod_dir_superior='$dirsuperior' and cod_coordinacion='$coordinacion' and cod_secretaria='$secretaria' and cod_direccion='$direccion' and cod_division=".$SelectValue;
						$datos = $this->cugd02_division->findAll($condicion,'cod_division, denominacion',null);
						$codigo = $this->AddCeroR2($datos[0]['cugd02_division']['cod_division']);
						$denominacion = $datos[0]['cugd02_division']['denominacion'];
						$lista = $this->cugd02_departamento->generateList($condicion, 'cod_departamento ASC', null, '{n}.cugd02_departamento.cod_departamento', '{n}.cugd02_departamento.denominacion');
				break;

			case 'departamento':
						$institucion = $this->Session->read('reporte_bienes_institucion');
						$dependencia = $this->Session->read('reporte_bienes_dependencia');
						$dirsuperior = $this->Session->read('reporte_bienes_dirsuperior');
						$coordinacion = $this->Session->read('reporte_bienes_coordinacion');
						$secretaria = $this->Session->read('reporte_bienes_secretaria');
						$direccion = $this->Session->read('reporte_bienes_direccion');
						$division = $this->Session->read('reporte_bienes_division');
						$this->Session->write('reporte_bienes_departamento',$SelectValue);
						$CampoActual='oficina';
						$CapaSiguiente='td-vacio';
						$condicion="cod_institucion='$institucion' and cod_dependencia='$dependencia' and cod_dir_superior='$dirsuperior' and cod_coordinacion='$coordinacion' and cod_secretaria='$secretaria' and cod_direccion='$direccion' and cod_division='$division' and cod_departamento=".$SelectValue;
						$datos = $this->cugd02_departamento->findAll($condicion,'cod_departamento, denominacion',null);
						$codigo = $this->AddCeroR2($datos[0]['cugd02_departamento']['cod_departamento']);
						$denominacion = $datos[0]['cugd02_departamento']['denominacion'];
						$lista = $this->cugd02_oficina->generateList($condicion, 'cod_oficina ASC', null, '{n}.cugd02_oficina.cod_oficina', '{n}.cugd02_oficina.denominacion');
				break;

			case 'oficina':
						$institucion = $this->Session->read('reporte_bienes_institucion');
						$dependencia = $this->Session->read('reporte_bienes_dependencia');
						$dirsuperior = $this->Session->read('reporte_bienes_dirsuperior');
						$coordinacion = $this->Session->read('reporte_bienes_coordinacion');
						$secretaria = $this->Session->read('reporte_bienes_secretaria');
						$direccion = $this->Session->read('reporte_bienes_direccion');
						$division = $this->Session->read('reporte_bienes_division');
						$departamento = $this->Session->read('reporte_bienes_departamento');
						$condicion="cod_institucion='$institucion' and cod_dependencia='$dependencia' and cod_dir_superior='$dirsuperior' and cod_coordinacion='$coordinacion' and cod_secretaria='$secretaria' and cod_direccion='$direccion' and cod_division='$division' and cod_departamento='$departamento' and cod_oficina=".$SelectValue;
						$lista = $this->cugd02_oficina->findAll($condicion,'cod_oficina, denominacion',null);
						$codigo = $this->AddCeroR2($lista[0]['cugd02_oficina']['cod_oficina']);
						$denominacion = $lista[0]['cugd02_oficina']['denominacion'];
						$CampoActual='ultimo';
						$CapaSiguiente='td-vacio';
				break;

			default : echo 'error en el switch, cayo en default';
				break;
		}//fin switch

		if(count($lista)!=0){
			$this->concatena($lista, 'lista');
			$this->set('codigo',$codigo);
			$this->set('denominacion',$denominacion);
			$this->set('SelectValue',$SelectValue);
			$this->set('Nombreform',$Nombreform);
			$this->set('CampoPrevio',$CampoPrevio);
			$this->set('CampoActual',$CampoActual);
			$this->set('CapaSiguiente',$CapaSiguiente);
			$this->set('pase',1);

		}else{
			$this->set('lista',array('0'=>''));
			$this->set('pase',0);
		}

	}//fin SelectValue1 == vacio
 }//fin select_administrativo



 function reporte_resumen_movimiento_bienes_muebles($var=null){
 	if($var!=null){
		if($var=='no'){
			$this->layout='ajax';
			$ano = $this->ano_ejecucion();
			if(isset($ano) && !empty($ano)){
				$ano_inicial = $ano - 20;
				$ano_final = $ano + 20;
				for($i=$ano_inicial; $i<=$ano_final; $i++){
					$array_ano[$i]=$i;
				}
			}else{
				$ano = date('Y');
				$ano_inicial = $ano - 20;
				$ano_final = $ano + 20;
				for($i=$ano_inicial; $i<=$ano_final; $i++){
					$array_ano[$i]=$i;
				}
			}
			$this->set('array_ano',$array_ano);
			$this->set('ano',$ano);
			$this->set('var',$var);

	$cont = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA()." and tipo_documento='10004'");
	if($cont == 0){
		$this->set('mensaje','Por favor, ingrese los nombres de los firmantes');
		$this->set('nombre_primera_firma','');
		$this->set('cargo_primera_firma','');
		$this->set('nombre_segunda_firma','');
		$this->set('cargo_segunda_firma','');
		$this->set('nombre_tercera_firma','N/A');
		$this->set('cargo_tercera_firma','N/A');
		$this->set('nombre_cuarta_firma','N/A');
		$this->set('cargo_cuarta_firma','N/A');
		$this->set('tipo_doc_anul', 10004);
		$firma_existe = 'no';
		echo'<script>';
       		echo" document.getElementById('enviar').disabled = 'true'; ";
		echo'</script>';
	}else{
		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=10004");
		$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
		$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
		$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
		$this->set('tipo_doc_anul',10004);
		$firma_existe = 'si';
		echo'<script>';
       		echo" document.getElementById('enviar').disabled = ''; ";
		echo'</script>';
	}
		$this->set('firma_existe',$firma_existe);

		}elseif($var=='si'){
			$this->layout='pdf';
			$cp = $this->Session->read('SScodpresi');
			$ce = $this->Session->read('SScodentidad');
			$cti = $this->Session->read('SScodtipoinst');
			$ci = $this->Session->read('SScodinst');
			$cd = $this->Session->read('SScoddep');

		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=10004");
		if(!empty($firmantes)){
			$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
			$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
			$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
			$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
			$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
			$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
			$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
			$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
		}else{
			$this->set('nombre_primera_firma','');
			$this->set('cargo_primera_firma','');
			$this->set('nombre_segunda_firma','');
			$this->set('cargo_segunda_firma','');
			$this->set('nombre_tercera_firma','');
			$this->set('cargo_tercera_firma','');
			$this->set('nombre_cuarta_firma','');
			$this->set('cargo_cuarta_firma','');
		}

		if($cd==1){
			$consolidacion=$this->data['movimiento_mueble']['consolidacion'];
			if($consolidacion==1){
				$cond_consolidacion = "a.cod_presi='$cp' AND a.cod_entidad='$ce' AND a.cod_tipo_inst='$cti' AND a.cod_inst='$ci'";
			}elseif($consolidacion==2){
				$cod_dependencia = $this->cod_dep_consolidado();
				$cond_consolidacion = "a.cod_presi='$cp' AND a.cod_entidad='$ce' AND a.cod_tipo_inst='$cti' AND a.cod_inst='$ci' AND a.cod_dep='$cod_dependencia'";
			}
		}else{
			$cond_consolidacion = "a.cod_presi='$cp' AND a.cod_entidad='$ce' AND a.cod_tipo_inst='$cti' AND a.cod_inst='$ci' AND a.cod_dep='$cd'";
		}

			$ano = $this->data['movimiento_mueble']['ano'];

			$por_ano = $this->data['movimiento_mueble']['por_ano'];
			if($por_ano==1){
				$ano_final=$ano-1;
				$fecha_anterior_final = "31-12-".$ano_final;
				$fecha_actual_inicial = date("Y-m-d", mktime(0, 0, 0, 1, 1, $ano));
				$fecha_actual_final   = date("Y-m-d", mktime(0, 0, 0, 12, 31, $ano));
			}else{
				$mes = $this->data['movimiento_mueble']['selectmes'];
				if($mes==''){
					echo'<script>history.back(1);</script>';
				}else{
					$fecha_anterior_final = date("Y-m-d", mktime(0, 0, 0, $mes, 0, $ano));
					$fecha_actual_inicial = date("Y-m-d", mktime(0, 0, 0, $mes, 1, $ano));
					$fecha_actual_final   = date("Y-m-d", mktime(0, 0, 0, $mes+1, 0, $ano));
				}
			}

			if(isset($consolidacion) && $consolidacion==1){
				$campos = 'a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_republica, a.cod_estado, a.cod_institucion, a.cod_dependencia, a.deno_dependencia, a.cod_dir_superior, a.cod_coordinacion, a.cod_secretaria, a.deno_secretaria,';
				$group_by = 'a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_republica, a.cod_estado, a.cod_institucion, a.cod_dependencia, a.deno_dependencia, a.cod_dir_superior, a.cod_coordinacion, a.cod_secretaria, a.deno_secretaria';
				$sql_datos = "SELECT ".$campos."
				count(cantidad) as cantidad,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_tipo_incorporacion != '0' AND v.fecha_incorporacion <= '$fecha_anterior_final') as incorporacion_anterior,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_tipo_desincorporacion != '0' AND v.fecha_desincorporacion <= '$fecha_anterior_final') as desincorporacion_anterior,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where  v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_tipo_incorporacion != '0' and v.fecha_incorporacion BETWEEN '$fecha_actual_inicial' AND '$fecha_actual_final') as incorporacion_actual,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where  v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_tipo_desincorporacion != '0' and v.fecha_desincorporacion BETWEEN '$fecha_actual_inicial' AND '$fecha_actual_final') as desincorporacion_actual
				FROM v_inventario_muebles_todo a
				WHERE ".$cond_consolidacion."
				GROUP BY ".$group_by. " ORDER BY ".$group_by." ASC;";
			}else{
				$campos = 'a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_republica, a.cod_estado, a.cod_institucion, a.cod_dependencia, a.deno_dependencia, a.cod_dir_superior, a.cod_coordinacion, a.cod_secretaria, a.deno_secretaria,';
				$group_by = 'a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_republica, a.cod_estado, a.cod_institucion, a.cod_dependencia, a.deno_dependencia, a.cod_dir_superior, a.cod_coordinacion, a.cod_secretaria, a.deno_secretaria';
				$sql_datos = "SELECT ".$campos."
				count(cantidad) as cantidad,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_dep=a.cod_dep AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_tipo_incorporacion != '0' AND v.fecha_incorporacion <= '$fecha_anterior_final') as incorporacion_anterior,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_dep=a.cod_dep AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_tipo_desincorporacion != '0' AND v.fecha_desincorporacion <= '$fecha_anterior_final') as desincorporacion_anterior,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where  v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_dep=a.cod_dep AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_tipo_incorporacion != '0' and v.fecha_incorporacion BETWEEN '$fecha_actual_inicial' AND '$fecha_actual_final') as incorporacion_actual,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where  v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_dep=a.cod_dep AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_tipo_desincorporacion != '0' and v.fecha_desincorporacion BETWEEN '$fecha_actual_inicial' AND '$fecha_actual_final') as desincorporacion_actual
				FROM v_inventario_muebles_todo a
				WHERE ".$cond_consolidacion."
				GROUP BY ".$group_by. " ORDER BY ".$group_by." ASC;";
			}

			$datos = $this->cimd03_inventario_muebles->execute($sql_datos);
			$this->set('consolidadop',isset($consolidacion)?$consolidacion:2);
			$this->set('datos',$datos);
			$this->set('var',$var);
		}
 	}
 }//reporte_resumen_movimiento_bienes_muebles



 function resumen_bienes_muebles($var=null){
 	//resumen de bienes muebles debajjo en el menu de bm-4
 	if($var!=null){
		if($var=='no'){
			$this->layout='ajax';
			$ano = $this->ano_ejecucion();
			if(isset($ano) && !empty($ano)){
				$ano_inicial = $ano - 20;
				$ano_final = $ano + 20;
				for($i=$ano_inicial; $i<=$ano_final; $i++){
					$array_ano[$i]=$i;
				}
			}else{
				$ano = date('Y');
				$ano_inicial = $ano - 20;
				$ano_final = $ano + 20;
				for($i=$ano_inicial; $i<=$ano_final; $i++){
					$array_ano[$i]=$i;
				}
			}
			$this->set('array_ano',$array_ano);
			$this->set('ano',$ano);
			$this->set('var',$var);

	$cont = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA()." and tipo_documento='10005'");
	if($cont == 0){
		$this->set('mensaje','Por favor, ingrese los nombres de los firmantes');
		$this->set('nombre_primera_firma','');
		$this->set('cargo_primera_firma','');
		$this->set('nombre_segunda_firma','');
		$this->set('cargo_segunda_firma','');
		$this->set('nombre_tercera_firma','');
		$this->set('cargo_tercera_firma','');
		$this->set('nombre_cuarta_firma','N/A');
		$this->set('cargo_cuarta_firma','N/A');
		$this->set('tipo_doc_anul', 10005);
		$firma_existe = 'no';
		echo'<script>';
       		echo" document.getElementById('enviar').disabled = 'true'; ";
		echo'</script>';
	}else{
		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=10005");
		$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
		$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
		$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
		$this->set('tipo_doc_anul',10005);
		$firma_existe = 'si';
		echo'<script>';
       		echo" document.getElementById('enviar').disabled = ''; ";
		echo'</script>';
	}
		$this->set('firma_existe',$firma_existe);

		}elseif($var=='si'){
			$this->layout='pdf';
			$cp = $this->Session->read('SScodpresi');
			$ce = $this->Session->read('SScodentidad');
			$cti = $this->Session->read('SScodtipoinst');
			$ci = $this->Session->read('SScodinst');
			$cd = $this->Session->read('SScoddep');

		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=10005");
		if(!empty($firmantes)){
			$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
			$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
			$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
			$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
			$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
			$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
			$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
			$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
		}else{
			$this->set('nombre_primera_firma','');
			$this->set('cargo_primera_firma','');
			$this->set('nombre_segunda_firma','');
			$this->set('cargo_segunda_firma','');
			$this->set('nombre_tercera_firma','');
			$this->set('cargo_tercera_firma','');
			$this->set('nombre_cuarta_firma','');
			$this->set('cargo_cuarta_firma','');
		}

		if($cd==1){
			$consolidacion=$this->data['movimiento_mueble']['consolidacion'];
			if($consolidacion==1){
				$cond_consolidacion = "a.cod_presi='$cp' AND a.cod_entidad='$ce' AND a.cod_tipo_inst='$cti' AND a.cod_inst='$ci'";
			}elseif($consolidacion==2){
				$cod_dependencia = $this->cod_dep_consolidado();
				$cond_consolidacion = "a.cod_presi='$cp' AND a.cod_entidad='$ce' AND a.cod_tipo_inst='$cti' AND a.cod_inst='$ci' AND a.cod_dep='$cod_dependencia'";
			}
		}else{
			$cond_consolidacion = "a.cod_presi='$cp' AND a.cod_entidad='$ce' AND a.cod_tipo_inst='$cti' AND a.cod_inst='$ci' AND a.cod_dep='$cd'";
		}

			$ano = $this->data['movimiento_mueble']['ano'];

			$por_ano = $this->data['movimiento_mueble']['por_ano'];
			if($por_ano==1){
				$ano_final=$ano-1;
				$fecha_anterior_final = "31-12-".$ano_final;
				$fecha_actual_inicial = date("Y-m-d", mktime(0, 0, 0, 1, 1, $ano));
				$fecha_actual_final   = date("Y-m-d", mktime(0, 0, 0, 12, 31, $ano));
			}else{
				$mes = $this->data['movimiento_mueble']['selectmes'];
				if($mes==''){
					echo'<script>history.back(1);</script>';
				}else{
					$fecha_anterior_final = date("Y-m-d", mktime(0, 0, 0, $mes, 0, $ano));
					$fecha_actual_inicial = date("Y-m-d", mktime(0, 0, 0, $mes, 1, $ano));
					$fecha_actual_final   = date("Y-m-d", mktime(0, 0, 0, $mes+1, 0, $ano));
				}
			}

			if(isset($consolidacion) && $consolidacion==1){
				$campos = 'a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_republica, a.cod_estado, a.deno_estado, a.cod_municipio, a.deno_municipio, a.conocido, a.cod_parroquia, a.deno_parroquia, a.cod_centro, a.deno_centro, a.cod_institucion, a.deno_institucion, a.cod_dependencia, a.deno_dependencia, a.cod_dir_superior, a.deno_dir_superior, a.cod_coordinacion, a.deno_coordinacion, a.cod_secretaria, a.deno_secretaria,';
				$group_by = 'a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_republica, a.cod_estado, a.deno_estado, a.cod_municipio, a.deno_municipio, a.conocido, a.cod_parroquia, a.deno_parroquia, a.cod_centro, a.deno_centro, a.cod_institucion, a.deno_institucion, a.cod_dependencia, a.deno_dependencia, a.cod_dir_superior, a.deno_dir_superior, a.cod_coordinacion, a.deno_coordinacion, a.cod_secretaria, a.deno_secretaria';
				$sql_datos = "SELECT ".$campos."
				count(cantidad) as cantidad,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_municipio=a.cod_municipio AND v.cod_centro=a.cod_centro AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_tipo_incorporacion != '0' AND v.fecha_incorporacion <= '$fecha_anterior_final') as incorporacion_anterior,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_municipio=a.cod_municipio AND v.cod_centro=a.cod_centro AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_tipo_desincorporacion != '0' AND v.fecha_desincorporacion <= '$fecha_anterior_final') as desincorporacion_anterior,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where  v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_municipio=a.cod_municipio AND v.cod_centro=a.cod_centro AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_tipo_incorporacion != '0' and v.fecha_incorporacion BETWEEN '$fecha_actual_inicial' AND '$fecha_actual_final') as incorporacion_actual,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where  v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_municipio=a.cod_municipio AND v.cod_centro=a.cod_centro AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_tipo_desincorporacion != '0' and v.fecha_desincorporacion BETWEEN '$fecha_actual_inicial' AND '$fecha_actual_final') as desincorporacion_actual
				FROM v_inventario_muebles_todo a
				WHERE ".$cond_consolidacion."
				GROUP BY ".$group_by. " ORDER BY ".$group_by." ASC;";
			}else{
				$campos = 'a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_republica, a.cod_estado, a.deno_estado, a.cod_municipio, a.deno_municipio, a.conocido, a.cod_parroquia, a.deno_parroquia, a.cod_centro, a.deno_centro, a.cod_institucion, a.deno_institucion, a.cod_dependencia, a.deno_dependencia, a.cod_dir_superior, a.deno_dir_superior, a.cod_coordinacion, a.deno_coordinacion, a.cod_secretaria, a.deno_secretaria,';
				$group_by = 'a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_republica, a.cod_estado, a.deno_estado, a.cod_municipio, a.deno_municipio, a.conocido, a.cod_parroquia, a.deno_parroquia, a.cod_centro, a.deno_centro, a.cod_institucion, a.deno_institucion, a.cod_dependencia, a.deno_dependencia, a.cod_dir_superior, a.deno_dir_superior, a.cod_coordinacion, a.deno_coordinacion, a.cod_secretaria, a.deno_secretaria';
				$sql_datos = "SELECT ".$campos."
				count(cantidad) as cantidad,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_dep=a.cod_dep AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_municipio=a.cod_municipio AND v.cod_centro=a.cod_centro AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_tipo_incorporacion != '0' AND v.fecha_incorporacion <= '$fecha_anterior_final') as incorporacion_anterior,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_dep=a.cod_dep AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_municipio=a.cod_municipio AND v.cod_centro=a.cod_centro AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_tipo_desincorporacion != '0' AND v.fecha_desincorporacion <= '$fecha_anterior_final') as desincorporacion_anterior,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where  v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_dep=a.cod_dep AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_municipio=a.cod_municipio AND v.cod_centro=a.cod_centro AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_tipo_incorporacion != '0' and v.fecha_incorporacion BETWEEN '$fecha_actual_inicial' AND '$fecha_actual_final') as incorporacion_actual,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where  v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_dep=a.cod_dep AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_municipio=a.cod_municipio AND v.cod_centro=a.cod_centro AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_tipo_desincorporacion != '0' and v.fecha_desincorporacion BETWEEN '$fecha_actual_inicial' AND '$fecha_actual_final') as desincorporacion_actual
				FROM v_inventario_muebles_todo a
				WHERE ".$cond_consolidacion."
				GROUP BY ".$group_by. " ORDER BY ".$group_by." ASC;";
			}

			$datos = $this->cimd03_inventario_muebles->execute($sql_datos);
			$this->set('consolidadop',isset($consolidacion)?$consolidacion:2);
			$this->set('datos',$datos);
			$this->set('fecha_actual_inicial',$fecha_actual_inicial);
			$this->set('var',$var);
		}
 	}
 }//resumen_bienes_muebles




 function reporte_ficha_resumen_propiedad_inmueble($var=null){
 	if($var!=null){
		if($var=='no'){
			$this->layout='ajax';
			$_SESSION['SScoddep']==1 ? $this->Session->write('consolidacion_reporte_cimd03_bienes',1) : $this->Session->write('consolidacion_reporte_cimd03_bienes',2);;
			$this->set('var',$var);

	$cont = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA()." and tipo_documento='9996'");
	if($cont == 0){
		$this->set('mensaje','Por favor, ingrese los nombres de los firmantes');
		$this->set('nombre_primera_firma','');
		$this->set('cargo_primera_firma','');
		$this->set('nombre_segunda_firma','');
		$this->set('cargo_segunda_firma','');
		$this->set('nombre_tercera_firma','');
		$this->set('cargo_tercera_firma','');
		$this->set('nombre_cuarta_firma','N/A');
		$this->set('cargo_cuarta_firma','N/A');
		$this->set('tipo_doc_anul', 9996);
		$firma_existe = 'no';
		echo'<script>';
       		echo" document.getElementById('enviar').disabled = ''; ";
		echo'</script>';
	}else{
		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=9996");
		$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
		$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
		$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
		$this->set('tipo_doc_anul',9996);
		$firma_existe = 'si';
		echo'<script>';
       		echo" document.getElementById('enviar').disabled = ''; ";
		echo'</script>';
	}
		$this->set('firma_existe',$firma_existe);

		}elseif($var=='si'){
			$this->layout='pdf';
			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');

		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=9996");
		$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
		$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
		$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);

			$cod_dep == 1 ? $consolidacion = $this->data['inventario_inmueble']['consolidacion'] : $consolidacion = 2;
			$radio_inmuebles = $this->data['inventario_inmueble']['radio_inmuebles'];

			if($cod_dep==1){
				if($consolidacion==1){
					if($radio_inmuebles==1){
						$sql = "SELECT a.numero_identificacion, a.cod_tipo, a.cod_grupo, a.cod_subgrupo, a.cod_seccion, a.deno_seccion, a.deno_republica, a.cod_estado, a.deno_estado, a.deno_municipio, a.deno_parroquia, a.deno_centro, a.denominacion_inmueble, a.area_total_terreno, a.area_cubierta, a.area_construccion, a.area_otras_instalaciones, a.area_total_construida, a.descripcion_inmueble, a.linderos, a.estudio_legal_propiedad, a.avaluo_actual, a.avaluo_comision, a.fecha_incorporacion, a.cod_tipo_incorporacion
								FROM v_inventario_inmuebles_todo a WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' ORDER BY cod_tipo, cod_grupo, fecha_incorporacion";
					}else{
						$id_inmueble = $this->data['inventario_inmueble']['inmueble'];
						if(empty($id_inmueble)){
							echo'<script>history.back(1);</script>';
						}else{
							$sql = "SELECT a.numero_identificacion, a.cod_tipo, a.cod_grupo, a.cod_subgrupo, a.cod_seccion, a.deno_seccion, a.deno_republica, a.cod_estado, a.deno_estado, a.deno_municipio, a.deno_parroquia, a.deno_centro, a.denominacion_inmueble, a.area_total_terreno, a.area_cubierta, a.area_construccion, a.area_otras_instalaciones, a.area_total_construida, a.descripcion_inmueble, a.linderos, a.estudio_legal_propiedad, a.avaluo_actual, a.avaluo_comision, a.fecha_incorporacion, a.cod_tipo_incorporacion
									FROM v_inventario_inmuebles_todo a WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND numero_identificacion='$id_inmueble' ORDER BY cod_tipo, cod_grupo, fecha_incorporacion";
						}
					}
				}else{
					if($radio_inmuebles==1){
						$sql = "SELECT a.numero_identificacion, a.cod_tipo, a.cod_grupo, a.cod_subgrupo, a.cod_seccion, a.deno_seccion, a.deno_republica, a.cod_estado, a.deno_estado, a.deno_municipio, a.deno_parroquia, a.deno_centro, a.denominacion_inmueble, a.area_total_terreno, a.area_cubierta, a.area_construccion, a.area_otras_instalaciones, a.area_total_construida, a.descripcion_inmueble, a.linderos, a.estudio_legal_propiedad, a.avaluo_actual, a.avaluo_comision, a.fecha_incorporacion, a.cod_tipo_incorporacion
								FROM v_inventario_inmuebles_todo a WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' ORDER BY cod_tipo, cod_grupo, fecha_incorporacion";
					}else{
						$id_inmueble = $this->data['inventario_inmueble']['inmueble'];
						if(empty($id_inmueble)){
							echo'<script>history.back(1);</script>';
						}else{
							$sql = "SELECT a.numero_identificacion, a.cod_tipo, a.cod_grupo, a.cod_subgrupo, a.cod_seccion, a.deno_seccion, a.deno_republica, a.cod_estado, a.deno_estado, a.deno_municipio, a.deno_parroquia, a.deno_centro, a.denominacion_inmueble, a.area_total_terreno, a.area_cubierta, a.area_construccion, a.area_otras_instalaciones, a.area_total_construida, a.descripcion_inmueble, a.linderos, a.estudio_legal_propiedad, a.avaluo_actual, a.avaluo_comision, a.fecha_incorporacion, a.cod_tipo_incorporacion
									FROM v_inventario_inmuebles_todo a WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND numero_identificacion='$id_inmueble'  ORDER BY cod_tipo, cod_grupo, fecha_incorporacion";
						}
					}
				}
				$datos = $this->cimd03_inventario_inmuebles->execute($sql);
			}else{
				if($radio_inmuebles==1){
					$sql = "SELECT a.numero_identificacion, a.cod_tipo, a.cod_grupo, a.cod_subgrupo, a.cod_seccion, a.deno_seccion, a.deno_republica, a.cod_estado, a.deno_estado, a.deno_municipio, a.deno_parroquia, a.deno_centro, a.denominacion_inmueble, a.area_total_terreno, a.area_cubierta, a.area_construccion, a.area_otras_instalaciones, a.area_total_construida, a.descripcion_inmueble, a.linderos, a.estudio_legal_propiedad, a.avaluo_actual, a.avaluo_comision, a.fecha_incorporacion, a.cod_tipo_incorporacion
							FROM v_inventario_inmuebles_todo a WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' ORDER BY cod_tipo, cod_grupo, fecha_incorporacion";
				}else{
					$id_inmueble = $this->data['inventario_inmueble']['inmueble'];
					if(empty($id_inmueble)){
							echo'<script>history.back(1);</script>';
					}else{
						$sql = "SELECT a.numero_identificacion, a.cod_tipo, a.cod_grupo, a.cod_subgrupo, a.cod_seccion, a.deno_seccion, a.deno_republica, a.cod_estado, a.deno_estado, a.deno_municipio, a.deno_parroquia, a.deno_centro, a.denominacion_inmueble, a.area_total_terreno, a.area_cubierta, a.area_construccion, a.area_otras_instalaciones, a.area_total_construida, a.descripcion_inmueble, a.linderos, a.estudio_legal_propiedad, a.avaluo_actual, a.avaluo_comision, a.fecha_incorporacion, a.cod_tipo_incorporacion
								FROM v_inventario_inmuebles_todo a WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND numero_identificacion='$id_inmueble'  ORDER BY cod_tipo, cod_grupo, fecha_incorporacion";
					}
				}
				$datos = $this->cimd03_inventario_inmuebles->execute($sql);
			}
			$this->set('datos',$datos);
			$this->set('var',$var);
		}
 	}else{
 		$this->set('var','no');
 	}
 }//reporte_ficha_resumen_propiedad_inmueble


function reporte_etiquetas_sencillas($var=null){
 	 	if($var!=null){
		if($var=='no'){
			$this->layout='ajax';
			$_SESSION['SScoddep']==1 ? $this->Session->write('consolidacion_reporte_cimd03_bienes',1) : $this->Session->write('consolidacion_reporte_cimd03_bienes',2);;
			$cod_presi = $this->Session->read('SScodpresi');
			$lista =  $this->cugd01_estados->generateList('cod_republica='.$cod_presi, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
			$lista_institucion =  $this->cugd02_institucion->generateList(null, 'cod_institucion ASC', null, '{n}.cugd02_institucion.cod_institucion', '{n}.cugd02_institucion.denominacion');
			$this->concatena($lista, 'lista');
			$this->concatena($lista_institucion, 'lista_institucion');
			$this->set('var',$var);

		}elseif($var=='si'){
			$this->layout='pdf';
			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');

			$consolidacion=2;
			$cod_dep == 1 ? $consolidacion = $this->data['movimiento_mueble']['consolidacion'] : $consolidacion = 2;
			$todas_etiquetas = $this->data['movimiento_mueble']['todas_etiquetas'];

			$datos=0;
			$condicion="";
			if($consolidacion==1){
				$condicion .= "cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND condicion_actividad = 1 ";
			}else{
				$condicion .= "cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND condicion_actividad = 1 ";
			}

			if($todas_etiquetas!=1){
				$mes=date('m');
				$ano=date('Y');
				$primerdia = date("Y-m-d", mktime(0, 0, 0, $mes, 1, $ano));
				$ultimodia = date("Y-m-d", mktime(0, 0, 0, $mes+1, 0, $ano));
				$condicion .= " AND fecha_incorporacion BETWEEN '$primerdia' AND '$ultimodia'";
			}


			$select_ubicaciones = $this->data['movimiento_mueble']['select_ubicaciones'];
			if($select_ubicaciones!=1){

				if(isset($this->data['movimiento_mueble']['estado']) && $this->data['movimiento_mueble']['estado']!=''){
					$estado = $this->data['movimiento_mueble']['estado'];
					$condicion .= " AND cod_estado='$estado'";
					if(isset($this->data['movimiento_mueble']['municipio']) && $this->data['movimiento_mueble']['municipio']!=''){
						$municipio = $this->data['movimiento_mueble']['municipio'];
						$condicion .= " AND cod_municipio='$municipio'";
						if(isset($this->data['movimiento_mueble']['parroquia']) && $this->data['movimiento_mueble']['parroquia']!=''){
							$parroquia = $this->data['movimiento_mueble']['parroquia'];
							$condicion .= " AND cod_parroquia='$parroquia'";
							if(isset($this->data['movimiento_mueble']['centropoblado']) && $this->data['movimiento_mueble']['centropoblado']!=''){
								$centropoblado = $this->data['movimiento_mueble']['centropoblado'];
								$condicion .= " AND cod_centro='$centropoblado'";
							}
						}
					}
				}

				if(isset($this->data['movimiento_mueble']['institucion']) && $this->data['movimiento_mueble']['institucion']!=''){
					$institucion = $this->data['movimiento_mueble']['institucion'];
					$condicion .= " AND cod_institucion='$institucion'";
					if(isset($this->data['movimiento_mueble']['dependencia']) && $this->data['movimiento_mueble']['dependencia']!=''){
						$dependencia = $this->data['movimiento_mueble']['dependencia'];
						$condicion .= " AND cod_dependencia='$dependencia'";
						if(isset($this->data['movimiento_mueble']['dirsuperior']) && $this->data['movimiento_mueble']['dirsuperior']!=''){
							$dirsuperior = $this->data['movimiento_mueble']['dirsuperior'];
							$condicion .= " AND cod_dir_superior='$dirsuperior'";
							if(isset($this->data['movimiento_mueble']['coordinacion']) && $this->data['movimiento_mueble']['coordinacion']!=''){
								$coordinacion = $this->data['movimiento_mueble']['coordinacion'];
								$condicion .= " AND cod_coordinacion='$coordinacion'";
								if(isset($this->data['movimiento_mueble']['secretaria']) && $this->data['movimiento_mueble']['secretaria']!=''){
									$secretaria = $this->data['movimiento_mueble']['secretaria'];
									$condicion .= " AND cod_secretaria='$secretaria'";
									if(isset($this->data['movimiento_mueble']['direccion']) && $this->data['movimiento_mueble']['direccion']!=''){
										$direccion = $this->data['movimiento_mueble']['direccion'];
										$condicion .= " AND cod_direccion='$direccion'";
										if(isset($this->data['movimiento_mueble']['division']) && $this->data['movimiento_mueble']['division']!=''){
											$division = $this->data['movimiento_mueble']['division'];
											$condicion .= " AND cod_division='$division'";
											if(isset($this->data['movimiento_mueble']['departamento']) && $this->data['movimiento_mueble']['departamento']!=''){
												$departamento = $this->data['movimiento_mueble']['departamento'];
												$condicion .= " AND cod_departamento='$departamento'";
												if(isset($this->data['movimiento_mueble']['oficina']) && $this->data['movimiento_mueble']['oficina']!=''){
													$oficina = $this->data['movimiento_mueble']['oficina'];
													$condicion .= " AND cod_oficina='$oficina'";
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}

			$sql = "SELECT a.deno_estado, a.deno_municipio, a.deno_parroquia, a.deno_institucion, a.deno_secretaria, a.deno_direccion, a.deno_dir_superior, a.deno_division, a.deno_departamento, a.deno_oficina, a.cod_tipo, a.cod_grupo, a.cod_subgrupo, a.cod_seccion, a.denominacion, a.numero_identificacion
					    FROM v_inventario_muebles_todo a WHERE ".$condicion."ORDER BY a.cod_tipo, a.cod_grupo, a.cod_subgrupo, a.cod_seccion, a.numero_identificacion, a.cod_republica, a.cod_estado, a.cod_municipio, a.cod_centro, a.cod_institucion, a.cod_dependencia, a.cod_direccion, a.cod_coordinacion, a.cod_secretaria, a.cod_direccion, a.cod_division, a.cod_departamento, a.cod_oficina, a.cod_parroquia;";
			$datos = $this->cimd03_inventario_muebles->execute($sql);
			$this->set('datos',$datos);
			$this->set('var',$var);
		}
 	}else{
 		$this->set('var','no');
 	}
 }//reporte_etiquetas_sencillas


 
function reporte_etiquetas_individual($cod_dep=null,$cod_tipo=null,$cod_grupo=null,$cod_subgrupo,$cod_seccion,$numero_identificacion){
 	 	
			if ($numero_identificacion !=null){
    
                        $this->layout='pdf';
			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			                        			
			$condicion .= "cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND condicion_actividad = 1 "
                                . "   AND cod_dep=".$cod_dep." AND cod_tipo=".$cod_tipo." AND cod_grupo=".$cod_grupo." AND cod_subgrupo=".$cod_subgrupo." AND cod_seccion=".$cod_seccion." AND numero_identificacion=".$numero_identificacion;
       		
			$sql = "SELECT a.deno_estado, a.deno_municipio, a.deno_parroquia, a.deno_institucion, a.deno_secretaria, a.deno_direccion, a.deno_dir_superior, a.deno_division, a.deno_departamento, a.deno_oficina, a.cod_tipo, a.cod_grupo, a.cod_subgrupo, a.cod_seccion, a.denominacion, a.numero_identificacion
                                FROM v_inventario_muebles_todo a WHERE ".$condicion."ORDER BY a.cod_tipo, a.cod_grupo, a.cod_subgrupo, a.cod_seccion, a.numero_identificacion, a.cod_republica, a.cod_estado, a.cod_municipio, a.cod_centro, a.cod_institucion, a.cod_dependencia, a.cod_direccion, a.cod_coordinacion, a.cod_secretaria, a.cod_direccion, a.cod_division, a.cod_departamento, a.cod_oficina, a.cod_parroquia;";
			$datos = $this->cimd03_inventario_muebles->execute($sql);
			$this->set('datos',$datos);
                        $this->set('var','si');
                        
                        }
                        
                        
                        
 }//reporte_etiquetas_sencillas
 
 
 
 function reporte_etiquetas($var=null){
 	 	if($var!=null){
		if($var=='no'){
			$this->layout='ajax';
			$_SESSION['SScoddep']==1 ? $this->Session->write('consolidacion_reporte_cimd03_bienes',1) : $this->Session->write('consolidacion_reporte_cimd03_bienes',2);;
			$cod_presi = $this->Session->read('SScodpresi');
			$lista =  $this->cugd01_estados->generateList('cod_republica='.$cod_presi, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
			$lista_institucion =  $this->cugd02_institucion->generateList(null, 'cod_institucion ASC', null, '{n}.cugd02_institucion.cod_institucion', '{n}.cugd02_institucion.denominacion');
			$this->concatena($lista, 'lista');
			$this->concatena($lista_institucion, 'lista_institucion');
			$this->set('var',$var);

		}elseif($var=='si'){
			$this->layout='pdf';
			$cod_presi = $this->Session->read('SScodpresi');
			$cod_entidad = $this->Session->read('SScodentidad');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$cod_inst = $this->Session->read('SScodinst');
			$cod_dep = $this->Session->read('SScoddep');

			$consolidacion=2;
			$cod_dep == 1 ? $consolidacion = $this->data['movimiento_mueble']['consolidacion'] : $consolidacion = 2;
			$todas_etiquetas = $this->data['movimiento_mueble']['todas_etiquetas'];

			$datos=0;
			$condicion="";
			if($consolidacion==1){
				$condicion .= "cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND condicion_actividad = 1 ";
			}else{
				$condicion .= "cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND condicion_actividad = 1 ";
			}

			if($todas_etiquetas!=1){
				$mes=date('m');
				$ano=date('Y');
				$primerdia = date("Y-m-d", mktime(0, 0, 0, $mes, 1, $ano));
				$ultimodia = date("Y-m-d", mktime(0, 0, 0, $mes+1, 0, $ano));
				$condicion .= "AND fecha_incorporacion BETWEEN '$primerdia' AND '$ultimodia'";
			}

			$select_ubicaciones = $this->data['movimiento_mueble']['select_ubicaciones'];
			if($select_ubicaciones!=1){

				if(isset($this->data['movimiento_mueble']['estado']) && $this->data['movimiento_mueble']['estado']!=''){
					$estado = $this->data['movimiento_mueble']['estado'];
					$condicion .= " AND cod_estado='$estado'";
					if(isset($this->data['movimiento_mueble']['municipio']) && $this->data['movimiento_mueble']['municipio']!=''){
						$municipio = $this->data['movimiento_mueble']['municipio'];
						$condicion .= " AND cod_municipio='$municipio'";
						if(isset($this->data['movimiento_mueble']['parroquia']) && $this->data['movimiento_mueble']['parroquia']!=''){
							$parroquia = $this->data['movimiento_mueble']['parroquia'];
							$condicion .= " AND cod_parroquia='$parroquia'";
							if(isset($this->data['movimiento_mueble']['centropoblado']) && $this->data['movimiento_mueble']['centropoblado']!=''){
								$centropoblado = $this->data['movimiento_mueble']['centropoblado'];
								$condicion .= " AND cod_centro='$centropoblado'";
							}
						}
					}
				}

				if(isset($this->data['movimiento_mueble']['institucion']) && $this->data['movimiento_mueble']['institucion']!=''){
					$institucion = $this->data['movimiento_mueble']['institucion'];
					$condicion .= " AND cod_institucion='$institucion'";
					if(isset($this->data['movimiento_mueble']['dependencia']) && $this->data['movimiento_mueble']['dependencia']!=''){
						$dependencia = $this->data['movimiento_mueble']['dependencia'];
						$condicion .= " AND cod_dependencia='$dependencia'";
						if(isset($this->data['movimiento_mueble']['dirsuperior']) && $this->data['movimiento_mueble']['dirsuperior']!=''){
							$dirsuperior = $this->data['movimiento_mueble']['dirsuperior'];
							$condicion .= " AND cod_dir_superior='$dirsuperior'";
							if(isset($this->data['movimiento_mueble']['coordinacion']) && $this->data['movimiento_mueble']['coordinacion']!=''){
								$coordinacion = $this->data['movimiento_mueble']['coordinacion'];
								$condicion .= " AND cod_coordinacion='$coordinacion'";
								if(isset($this->data['movimiento_mueble']['secretaria']) && $this->data['movimiento_mueble']['secretaria']!=''){
									$secretaria = $this->data['movimiento_mueble']['secretaria'];
									$condicion .= " AND cod_secretaria='$secretaria'";
									if(isset($this->data['movimiento_mueble']['direccion']) && $this->data['movimiento_mueble']['direccion']!=''){
										$direccion = $this->data['movimiento_mueble']['direccion'];
										$condicion .= " AND cod_direccion='$direccion'";
										if(isset($this->data['movimiento_mueble']['division']) && $this->data['movimiento_mueble']['division']!=''){
											$division = $this->data['movimiento_mueble']['division'];
											$condicion .= " AND cod_division='$division'";
											if(isset($this->data['movimiento_mueble']['departamento']) && $this->data['movimiento_mueble']['departamento']!=''){
												$departamento = $this->data['movimiento_mueble']['departamento'];
												$condicion .= " AND cod_departamento='$departamento'";
												if(isset($this->data['movimiento_mueble']['oficina']) && $this->data['movimiento_mueble']['oficina']!=''){
													$oficina = $this->data['movimiento_mueble']['oficina'];
													$condicion .= " AND cod_oficina='$oficina'";
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}

			$sql = "SELECT a.deno_estado, a.deno_municipio, a.deno_parroquia, a.deno_institucion, a.deno_secretaria, a.deno_direccion, a.deno_dir_superior, a.deno_division, a.deno_departamento, a.deno_oficina, a.cod_tipo, a.cod_grupo, a.cod_subgrupo, a.cod_seccion, a.denominacion, a.numero_identificacion
					    FROM v_inventario_muebles_todo a WHERE ".$condicion."ORDER BY a.cod_tipo, a.cod_grupo, a.cod_subgrupo, a.cod_seccion, a.numero_identificacion, a.cod_republica, a.cod_estado, a.cod_municipio, a.cod_centro, a.cod_institucion, a.cod_dependencia, a.cod_direccion, a.cod_coordinacion, a.cod_secretaria, a.cod_direccion, a.cod_division, a.cod_departamento, a.cod_oficina, a.cod_parroquia;";
			$datos = $this->cimd03_inventario_muebles->execute($sql);
			$this->set('datos',$datos);
			$this->set('var',$var);
		}
 	}else{
 		$this->set('var','no');
 	}
 }//reporte_etiquetas_triples


function buscar_mueble($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function

function buscar_por_pista($var1=null, $var2=null, $var3=null){
	$this->layout="ajax";
    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					$Tfilas=$this->v_buscar_muebles->findCount(" (deno_seccion LIKE '%$var2%')");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_buscar_muebles->findAll(" (deno_seccion LIKE '%$var2%')   ",null,"cod_tipo,cod_grupo,cod_subgrupo,cod_seccion ASC",100,1,null);
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
						$Tfilas=$this->v_buscar_muebles->findCount(" (deno_seccion LIKE '%$var22%')");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_buscar_muebles->findAll(" (deno_seccion LIKE '%$var22%')  ",null,"cod_tipo,cod_grupo,cod_subgrupo,cod_seccion ASC",100,$pagina,null);
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


function seleccion_mueble($codcm1=null, $codcm2=null, $codcm3=null, $codcm4=null, $denomcm=null){
	$this->layout = "ajax";
	$cods_clasific = $codcm1."-".$codcm2."-".$codcm3."-".$codcm4;
	echo "<script>
			document.getElementById('cods_clasific_part').value='$cods_clasific';
			document.getElementById('opciones').innerHTML='<br><b><font size=4 color=#840000>$denomcm</font></b><br><br>';
  		</script>";
}


function cimp01_inventario_muebles_bienes($ir=null,$var=null){
	//cimd03_inventario_muebles
	$this->layout = "ajax";
	$cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');

	$this->set('depe',$SScoddep);

	$ano = $this->ano_ejecucion();

	$cond =" cod_republica=".$cod_presi;
//	$this->Session->write('cod1',$cod_entidad);
	$lista=  $this->cugd01_estados->generateList($cond, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
	$this->concatena($lista, 'estado');

	$lista1=  $this->cugd02_institucion->generateList("cod_tipo_institucion=".$cod_tipo_inst, 'cod_institucion ASC', null, '{n}.cugd02_institucion.cod_institucion', '{n}.cugd02_institucion.denominacion');
	$this->concatena($lista1, 'institucion');

	$deno_estado = $this->cugd01_estados->field('denominacion', $conditions = "cod_republica=".$cod_presi." and cod_estado='$cod_entidad'", $order ="cod_estado ASC");
	$this->set('cod_estado', $cod_entidad);
	$this->set('deno_estado', $deno_estado);

	$cond =" cod_republica=".$cod_presi." and cod_estado=".$cod_entidad;
	$lista2=  $this->cugd01_municipios->generateList($cond, 'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
	$this->concatena($lista2, 'municipio');

	$cont = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA()." and tipo_documento='9998'");
	if($cont == 0){
		$this->set('mensaje','Por favor, ingrese los nombres de los firmantes');
		$this->set('nombre_primera_firma','');
		$this->set('cargo_primera_firma','');
		$this->set('nombre_segunda_firma','');
		$this->set('cargo_segunda_firma','');
		$this->set('nombre_tercera_firma','N/A');
		$this->set('cargo_tercera_firma','N/A');
		$this->set('nombre_cuarta_firma','N/A');
		$this->set('cargo_cuarta_firma','N/A');
		$this->set('tipo_doc_anul', 9998);
		$firma_existe = 'no';
		echo'<script>';
       		echo" document.getElementById('enviar').disabled = 'true'; ";
		echo'</script>';
	}else{
		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=9998");
		$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
		$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
		$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
		$this->set('tipo_doc_anul',9998);
		$firma_existe = 'si';
		echo'<script>';
       		echo" document.getElementById('enviar').disabled = ''; ";
		echo'</script>';
	}
		$this->set('firma_existe',$firma_existe);

	if($ir=='si'){
		$this->set('ir','si');
		$this->set('year',$ano);

	}else if($ir=='vista_1'){
		if($var==2){
			$this->set('ir','particular');
//			$this->set('ir','todos');
		}else if($var==3){
				$url                  =  "/reporte_bienes/buscar_mueble/$var";
				$width_aux            =  "750px";
				$height_aux           =  "450px";
				$title_aux            =  "Buscar Bienes Muebles";
				$resizable_aux        =  false;
				$maximizable_aux      =  false;
				$minimizable_aux      =  false;
				$closable_aux         =  false;

			 echo "<script>";
	           echo "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
	         echo "</script>";
		}else{
			$this->set('ir','todos');
		}
	}else if($ir=='select'){
		$this->set('ir','select');
		$cod2 = strtoupper($var);//cimd03_inventario_muebles

		//////////////////////////////////////////////////////////////////////

		$sql = "SELECT cod_tipo, cod_grupo, cod_subgrupo, cod_seccion, numero_identificacion, denominacion FROM cimd03_inventario_muebles WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND UPPER(denominacion) like '%$cod2%'";
 		$rs = $this->cimd03_inventario_muebles->execute($sql);
 		if(count($rs)!=0){
		    foreach($rs as $l){
				$v[]=$l[0]["cod_tipo"].'-'.$l[0]["cod_grupo"].'-'.$l[0]["cod_subgrupo"].'-'.$l[0]["cod_seccion"].'-'.$l[0]["numero_identificacion"];
				$d[]=$l[0]["numero_identificacion"]." - ".$l[0]["denominacion"];
			}
			$lista = array_combine($v, $d);
			$this->set('select',$lista);
 		}else{
		   $this->set('select',array('0'=>'No hay registros'));
		}
		////////////////////////////////////////////////////////////////////

		/*$catalogo= $this->v_inventario_muebles_todo->generateList($this->SQLCA()." and upper(denominacion) LIKE '%$cod2%' and cod_tipo_desincorporacion=0", 'numero_identificacion ASC', null, '{n}.v_inventario_muebles_todo.numero_identificacion', '{n}.v_inventario_muebles_todo.denominacion');
 			if(!empty($catalogo)){
		 		$this->concatena($catalogo,'select');
		 	}else{
		 		$this->concatena(array(),'select');
		 	}*/
	}else if($ir=='vista_2'){
		if($var==2){
			$this->set('ir','ubicacion');

		}else{
			$this->set('ir','ubicaciones');
		}
	}//fin ir no


}//fin cimp01_inventario_muebles_bienes




function cimp01_inventario_muebles_bienes_pdf(){
	$this->layout = "pdf";
	$cod_presi     = $this->Session->read('SScodpresi');
	$cod_entidad   = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst      = $this->Session->read('SScodinst');
	$cod_dep       = $this->Session->read('SScoddep');
	$SScoddeporig  = $this->Session->read('SScoddeporig');
	$SScoddep      = $this->Session->read('SScoddep');

    $this->set('cod_presi',$cod_presi);
    $this->set('cod_entidad',$cod_entidad);
    $this->set('cod_tipo_inst',$cod_tipo_inst);
    $this->set('cod_inst',$cod_inst);
    $this->set('cod_dep',$cod_dep);


		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=9998");
		if(!empty($firmantes)){
			$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
			$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
			$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
			$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
			$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
			$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
			$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
			$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
		}else{
			$this->set('nombre_primera_firma','');
			$this->set('cargo_primera_firma','');
			$this->set('nombre_segunda_firma','');
			$this->set('cargo_segunda_firma','');
			$this->set('nombre_tercera_firma','');
			$this->set('cargo_tercera_firma','');
			$this->set('nombre_cuarta_firma','');
			$this->set('cargo_cuarta_firma','');
		}


		if($this->data['cimp01']['radio_ver']!='' && $this->data['cimp01']['todos_ver']!=''){
			$inst_dep=$this->data['cimp01']['inst_dep'];
//			echo "<br>".$ano=$this->data['cimp01']['ano'];
			$radio_ver=$this->data['cimp01']['radio_ver'];
			$todos_ver=$this->data['cimp01']['todos_ver'];

			if(!isset($this->data['cimp01']['inst_dep'])){
				$inst_dep = 2;
			}

			if($cod_dep==1){
				if($inst_dep==1){
					$cond=$this->condicionNDEP();
					$group="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_republica,deno_republica,cod_estado,deno_estado,cod_municipio,deno_municipio,conocido,cod_parroquia,deno_parroquia,cod_centro,deno_centro,cod_institucion,deno_institucion,cod_dependencia,deno_dependencia,cod_dir_superior,deno_dir_superior,cod_coordinacion,deno_coordinacion,cod_secretaria,deno_secretaria,cod_direccion,deno_direccion,cod_division,deno_division,cod_departamento,deno_departamento,cod_oficina,deno_oficina,cod_tipo,cod_grupo,cod_subgrupo,cod_seccion,cantidad,numero_identificacion,denominacion,valor_unitario,fecha_incorporacion";
					$group1="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_republica,deno_republica,cod_estado,deno_estado,cod_municipio,deno_municipio,conocido,cod_parroquia,deno_parroquia,cod_centro,deno_centro,cod_institucion,deno_institucion,cod_dependencia,deno_dependencia,cod_dir_superior,deno_dir_superior,cod_coordinacion,deno_coordinacion,cod_secretaria,deno_secretaria,cod_direccion,deno_direccion,cod_division,deno_division,cod_departamento,deno_departamento,cod_oficina,deno_oficina";
				}else{
					$cond=$this->SQLCA_consolidado($inst_dep, "a");
					$group="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_republica,deno_republica,cod_estado,deno_estado,cod_municipio,deno_municipio,conocido,cod_parroquia,deno_parroquia,cod_centro,deno_centro,cod_institucion,deno_institucion,cod_dependencia,deno_dependencia,cod_dir_superior,deno_dir_superior,cod_coordinacion,deno_coordinacion,cod_secretaria,deno_secretaria,cod_direccion,deno_direccion,cod_division,deno_division,cod_departamento,deno_departamento,cod_oficina,deno_oficina,cod_tipo,cod_grupo,cod_subgrupo,cod_seccion,cantidad,numero_identificacion,denominacion,valor_unitario,fecha_incorporacion";
					$group1="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_republica,deno_republica,cod_estado,deno_estado,cod_municipio,deno_municipio,conocido,cod_parroquia,deno_parroquia,cod_centro,deno_centro,cod_institucion,deno_institucion,cod_dependencia,deno_dependencia,cod_dir_superior,deno_dir_superior,cod_coordinacion,deno_coordinacion,cod_secretaria,deno_secretaria,cod_direccion,deno_direccion,cod_division,deno_division,cod_departamento,deno_departamento,cod_oficina,deno_oficina";
				}
			}else{
				$cond=$this->SQLCA();
					$group="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_republica,deno_republica,cod_estado,deno_estado,cod_municipio,deno_municipio,conocido,cod_parroquia,deno_parroquia,cod_centro,deno_centro,cod_institucion,deno_institucion,cod_dependencia,deno_dependencia,cod_dir_superior,deno_dir_superior,cod_coordinacion,deno_coordinacion,cod_secretaria,deno_secretaria,cod_direccion,deno_direccion,cod_division,deno_division,cod_departamento,deno_departamento,cod_oficina,deno_oficina,cod_tipo,cod_grupo,cod_subgrupo,cod_seccion,cantidad,numero_identificacion,denominacion,valor_unitario,fecha_incorporacion";
					$group1="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_republica,deno_republica,cod_estado,deno_estado,cod_municipio,deno_municipio,conocido,cod_parroquia,deno_parroquia,cod_centro,deno_centro,cod_institucion,deno_institucion,cod_dependencia,deno_dependencia,cod_dir_superior,deno_dir_superior,cod_coordinacion,deno_coordinacion,cod_secretaria,deno_secretaria,cod_direccion,deno_direccion,cod_division,deno_division,cod_departamento,deno_departamento,cod_oficina,deno_oficina";
			}

//////////////////////////////////////////////////////////////////////////////////////////////////

				if($radio_ver==2){
					if($this->data['cimp01']['cod_mueble']!=''){
						$mueble=$this->data['cimp01']['cod_mueble'];
						$cond.=" and ".$this->sql_codigo_mueble($mueble);
					}else{
						echo'<script>history.back(1);</script>';
					}
				}else if($radio_ver==3){
					if($this->data['cimp01']['cods_clasific_part']!=''){
						$cods_clasif=$this->data['cimp01']['cods_clasific_part'];
						$cond.=" and ".$this->sql_codigo_mueble($cods_clasif, 1);
					}else{
						echo'<script>history.back(1);</script>';
					}
				}

/////////////////////////////////////////////////////////////////////////////////////////////////

				if($this->data['cimp01']['todos_ver']!=''){
					$todos_ver=$this->data['cimp01']['todos_ver'];
					if($todos_ver==2){
						/////////////aqui son todos los muebles de una ubicacion en particular/////////////////
//						echo "<br>hola";
//						pr($this->data);
							if(!empty($this->data['cnmp09']['cod_estado']) && empty($this->data['cnmp09']['cod_municipio']) && empty($this->data['cnmp09']['cod_parroquia']) && empty($this->data['cnmp09']['cod_centro_poblado'])){
								$estado=$this->data['cnmp09']['cod_estado'];
								$cond.=" and cod_republica=".$cod_presi." and cod_estado=".$estado;
							}else if(!empty($this->data['cnmp09']['cod_estado']) && !empty($this->data['cnmp09']['cod_municipio']) && empty($this->data['cnmp09']['cod_parroquia']) && empty($this->data['cnmp09']['cod_centro_poblado'])){
								$estado=$this->data['cnmp09']['cod_estado'];
								$municipio=$this->data['cnmp09']['cod_municipio'];
								$cond.=" and cod_republica=".$cod_presi." and cod_estado=".$estado." and cod_municipio=".$municipio;
							}else if(!empty($this->data['cnmp09']['cod_estado']) && !empty($this->data['cnmp09']['cod_municipio']) && !empty($this->data['cnmp09']['cod_parroquia']) && empty($this->data['cnmp09']['cod_centro_poblado'])){
								$estado=$this->data['cnmp09']['cod_estado'];
								$municipio=$this->data['cnmp09']['cod_municipio'];
								$parroquia=$this->data['cnmp09']['cod_parroquia'];
								$cond.=" and cod_republica=".$cod_presi." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia;
							}else if(!empty($this->data['cnmp09']['cod_estado']) && !empty($this->data['cnmp09']['cod_municipio']) && !empty($this->data['cnmp09']['cod_parroquia']) && !empty($this->data['cnmp09']['cod_centro_poblado'])){
								$estado=$this->data['cnmp09']['cod_estado'];
								$municipio=$this->data['cnmp09']['cod_municipio'];
								$parroquia=$this->data['cnmp09']['cod_parroquia'];
								$centro=$this->data['cnmp09']['cod_centro_poblado'];
								$cond.=" and cod_republica=".$cod_presi." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia." and cod_centro=".$centro;
							}/*else{
								echo'<script>history.back(1);</script>';
							}*/

							if(!empty($this->data['cnmp09']['cod_institucion']) && empty($this->data['cnmp09']['cod_dependencia']) && empty($this->data['cnmp09']['cod_superior']) && empty($this->data['cnmp09']['cod_coordinacion']) && empty($this->data['cnmp09']['cod_secretaria']) && empty($this->data['cnmp09']['cod_direccion']) && empty($this->data['cnmp09']['cod_division']) && empty($this->data['cnmp09']['cod_departamento']) && empty($this->data['cnmp09']['cod_oficina'])){
								$institucion=$this->data['cnmp09']['cod_institucion'];
								$cond.=" and cod_institucion=".$institucion;
							}else if(!empty($this->data['cnmp09']['cod_institucion']) && !empty($this->data['cnmp09']['cod_dependencia']) && empty($this->data['cnmp09']['cod_superior']) && empty($this->data['cnmp09']['cod_coordinacion']) && empty($this->data['cnmp09']['cod_secretaria']) && empty($this->data['cnmp09']['cod_direccion']) && empty($this->data['cnmp09']['cod_division']) && empty($this->data['cnmp09']['cod_departamento']) && empty($this->data['cnmp09']['cod_oficina'])){
								$institucion=$this->data['cnmp09']['cod_institucion'];
								$dependencia=$this->data['cnmp09']['cod_dependencia'];
								$cond.=" and cod_institucion=".$institucion." and cod_dependencia=".$dependencia;
							}else if(!empty($this->data['cnmp09']['cod_institucion']) && !empty($this->data['cnmp09']['cod_dependencia']) && !empty($this->data['cnmp09']['cod_superior']) && empty($this->data['cnmp09']['cod_coordinacion']) && empty($this->data['cnmp09']['cod_secretaria']) && empty($this->data['cnmp09']['cod_direccion']) && empty($this->data['cnmp09']['cod_division']) && empty($this->data['cnmp09']['cod_departamento']) && empty($this->data['cnmp09']['cod_oficina'])){
								$institucion=$this->data['cnmp09']['cod_institucion'];
								$dependencia=$this->data['cnmp09']['cod_dependencia'];
								$superior=$this->data['cnmp09']['cod_superior'];
								$cond.=" and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$superior;
							}else if(!empty($this->data['cnmp09']['cod_institucion']) && !empty($this->data['cnmp09']['cod_dependencia']) && !empty($this->data['cnmp09']['cod_superior']) && !empty($this->data['cnmp09']['cod_coordinacion']) && empty($this->data['cnmp09']['cod_secretaria']) && empty($this->data['cnmp09']['cod_direccion']) && empty($this->data['cnmp09']['cod_division']) && empty($this->data['cnmp09']['cod_departamento']) && empty($this->data['cnmp09']['cod_oficina'])){
								$institucion=$this->data['cnmp09']['cod_institucion'];
								$dependencia=$this->data['cnmp09']['cod_dependencia'];
								$superior=$this->data['cnmp09']['cod_superior'];
								$coordinacion=$this->data['cnmp09']['cod_coordinacion'];
								$cond.=" and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$superior." and cod_coordinacion=".$coordinacion;
							}else if(!empty($this->data['cnmp09']['cod_institucion']) && !empty($this->data['cnmp09']['cod_dependencia']) && !empty($this->data['cnmp09']['cod_superior']) && !empty($this->data['cnmp09']['cod_coordinacion']) && !empty($this->data['cnmp09']['cod_secretaria']) && empty($this->data['cnmp09']['cod_direccion']) && empty($this->data['cnmp09']['cod_division']) && empty($this->data['cnmp09']['cod_departamento']) && empty($this->data['cnmp09']['cod_oficina'])){
								$institucion=$this->data['cnmp09']['cod_institucion'];
								$dependencia=$this->data['cnmp09']['cod_dependencia'];
								$superior=$this->data['cnmp09']['cod_superior'];
								$coordinacion=$this->data['cnmp09']['cod_coordinacion'];
								$secretaria=$this->data['cnmp09']['cod_secretaria'];
								$cond.=" and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$superior." and cod_coordinacion=".$coordinacion." and cod_secretaria=".$secretaria;
							}else if(!empty($this->data['cnmp09']['cod_institucion']) && !empty($this->data['cnmp09']['cod_dependencia']) && !empty($this->data['cnmp09']['cod_superior']) && !empty($this->data['cnmp09']['cod_coordinacion']) && !empty($this->data['cnmp09']['cod_secretaria']) && !empty($this->data['cnmp09']['cod_direccion']) && empty($this->data['cnmp09']['cod_division']) && empty($this->data['cnmp09']['cod_departamento']) && empty($this->data['cnmp09']['cod_oficina'])){
								$institucion=$this->data['cnmp09']['cod_institucion'];
								$dependencia=$this->data['cnmp09']['cod_dependencia'];
								$superior=$this->data['cnmp09']['cod_superior'];
								$coordinacion=$this->data['cnmp09']['cod_coordinacion'];
								$secretaria=$this->data['cnmp09']['cod_secretaria'];
								$direccion=$this->data['cnmp09']['cod_direccion'];
								$cond.=" and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$superior." and cod_coordinacion=".$coordinacion." and cod_secretaria=".$secretaria." and cod_direccion=".$direccion;
							}else if(!empty($this->data['cnmp09']['cod_institucion']) && !empty($this->data['cnmp09']['cod_dependencia']) && !empty($this->data['cnmp09']['cod_superior']) && !empty($this->data['cnmp09']['cod_coordinacion']) && !empty($this->data['cnmp09']['cod_secretaria']) && !empty($this->data['cnmp09']['cod_direccion']) && !empty($this->data['cnmp09']['cod_division']) && empty($this->data['cnmp09']['cod_departamento']) && empty($this->data['cnmp09']['cod_oficina'])){
								$institucion=$this->data['cnmp09']['cod_institucion'];
								$dependencia=$this->data['cnmp09']['cod_dependencia'];
								$superior=$this->data['cnmp09']['cod_superior'];
								$coordinacion=$this->data['cnmp09']['cod_coordinacion'];
								$secretaria=$this->data['cnmp09']['cod_secretaria'];
								$direccion=$this->data['cnmp09']['cod_direccion'];
								$division=$this->data['cnmp09']['cod_division'];
								$cond.=" and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$superior." and cod_coordinacion=".$coordinacion." and cod_secretaria=".$secretaria." and cod_direccion=".$direccion." and cod_division=".$division;
							}else if(!empty($this->data['cnmp09']['cod_institucion']) && !empty($this->data['cnmp09']['cod_dependencia']) && !empty($this->data['cnmp09']['cod_superior']) && !empty($this->data['cnmp09']['cod_coordinacion']) && !empty($this->data['cnmp09']['cod_secretaria']) && !empty($this->data['cnmp09']['cod_direccion']) && !empty($this->data['cnmp09']['cod_division']) && !empty($this->data['cnmp09']['cod_departamento']) && empty($this->data['cnmp09']['cod_oficina'])){
								$institucion=$this->data['cnmp09']['cod_institucion'];
								$dependencia=$this->data['cnmp09']['cod_dependencia'];
								$superior=$this->data['cnmp09']['cod_superior'];
								$coordinacion=$this->data['cnmp09']['cod_coordinacion'];
								$secretaria=$this->data['cnmp09']['cod_secretaria'];
								$direccion=$this->data['cnmp09']['cod_direccion'];
								$division=$this->data['cnmp09']['cod_division'];
								$departamento=$this->data['cnmp09']['cod_departamento'];
								$cond.=" and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$superior." and cod_coordinacion=".$coordinacion." and cod_secretaria=".$secretaria." and cod_direccion=".$direccion." and cod_division=".$division." and cod_departamento=".$departamento;
							}else if(!empty($this->data['cnmp09']['cod_institucion']) && !empty($this->data['cnmp09']['cod_dependencia']) && !empty($this->data['cnmp09']['cod_superior']) && !empty($this->data['cnmp09']['cod_coordinacion']) && !empty($this->data['cnmp09']['cod_secretaria']) && !empty($this->data['cnmp09']['cod_direccion']) && !empty($this->data['cnmp09']['cod_division']) && !empty($this->data['cnmp09']['cod_departamento']) && !empty($this->data['cnmp09']['cod_oficina'])){
								$institucion=$this->data['cnmp09']['cod_institucion'];
								$dependencia=$this->data['cnmp09']['cod_dependencia'];
								$superior=$this->data['cnmp09']['cod_superior'];
								$coordinacion=$this->data['cnmp09']['cod_coordinacion'];
								$secretaria=$this->data['cnmp09']['cod_secretaria'];
								$direccion=$this->data['cnmp09']['cod_direccion'];
								$division=$this->data['cnmp09']['cod_division'];
								$departamento=$this->data['cnmp09']['cod_departamento'];
								$oficina=$this->data['cnmp09']['cod_oficina'];
								$cond.=" and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$superior." and cod_coordinacion=".$coordinacion." and cod_secretaria=".$secretaria." and cod_direccion=".$direccion." and cod_division=".$division." and cod_departamento=".$departamento." and cod_oficina=".$oficina;
							}/*else{
								echo'<script>history.back(1);</script>';
							}*/

					}
				}else{
					echo'<script>history.back(1);</script>';
				}
		}else{

		}
//echo "<br> Filtro: ".$cond;
	$order="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_republica,cod_estado,cod_municipio,conocido,cod_parroquia,cod_centro,cod_institucion,cod_dependencia,cod_dir_superior,cod_coordinacion,cod_secretaria,cod_direccion,cod_division,cod_departamento,cod_oficina";
	$order1="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_republica,cod_estado,cod_municipio,conocido,cod_parroquia,cod_centro,cod_institucion,cod_dependencia,cod_dir_superior,cod_coordinacion,cod_secretaria,cod_direccion,cod_division,cod_departamento,cod_oficina,cod_tipo,cod_grupo,cod_subgrupo,cod_seccion,cantidad,numero_identificacion";
	$sql1="SELECT ".$group." FROM v_inventario_muebles_todo where ".$cond." and cod_tipo_desincorporacion=0 group by ".$group." order by ".$order1." asc";

	$this->set('consolidadop',$inst_dep);
	$datos2=$this->v_inventario_muebles_todo->execute($sql1);

	$this->set('datos',$datos2);
	$this->data['cimp01']['inst_dep'];
	$this->data['cimp01']['inst_dep'];

}//fin cimp01_inventario_muebles_bienes_pdf



function cimp01_inventario_muebles_bienes_2($ir=null,$var=null){
	//cimd03_inventario_muebles
	$this->layout = "ajax";
	$cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$ano = $this->ano_ejecucion();

	$cond =" cod_republica=".$cod_presi;
//	$this->Session->write('cod1',$cod_entidad);
	$lista=  $this->cugd01_estados->generateList($cond, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
	$this->concatena($lista, 'estado');

	$lista1=  $this->cugd02_institucion->generateList("cod_tipo_institucion=".$cod_tipo_inst, 'cod_institucion ASC', null, '{n}.cugd02_institucion.cod_institucion', '{n}.cugd02_institucion.denominacion');
	$this->concatena($lista1, 'institucion');

	$deno_estado = $this->cugd01_estados->field('denominacion', $conditions = "cod_republica=".$cod_presi." and cod_estado='$cod_entidad'", $order ="cod_estado ASC");
	$this->set('cod_estado', $cod_entidad);
	$this->set('deno_estado', $deno_estado);

	$cond =" cod_republica=".$cod_presi." and cod_estado=".$cod_entidad;
	$lista2=  $this->cugd01_municipios->generateList($cond, 'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
	$this->concatena($lista2, 'municipio');

	if($ir=='si'){
		$this->set('ir','si');
		$this->set('year',$ano);

	}else if($ir=='vista_1'){
		if($var==2){
			$this->set('ir','particular');
//			$this->set('ir','todos');
		}else{
			$this->set('ir','todos');
		}
	}else if($ir=='select'){
		$this->set('ir','select');
		$cod2 = strtoupper($var);//cimd03_inventario_muebles

		//////////////////////////////////////////////////////////////////////

		$sql = "SELECT cod_tipo, cod_grupo, cod_subgrupo, cod_seccion, numero_identificacion, denominacion FROM cimd03_inventario_muebles WHERE cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep' AND UPPER(denominacion) like '%$cod2%'";
 		$rs = $this->cimd03_inventario_muebles->execute($sql);
 		if(count($rs)!=0){
		    foreach($rs as $l){
				$v[]=$l[0]["cod_tipo"].'-'.$l[0]["cod_grupo"].'-'.$l[0]["cod_subgrupo"].'-'.$l[0]["cod_seccion"].'-'.$l[0]["numero_identificacion"];
				$d[]=$l[0]["numero_identificacion"]." - ".$l[0]["denominacion"];
			}
			$lista = array_combine($v, $d);
			$this->set('select',$lista);
 		}else{
		   $this->set('select',array('0'=>'No hay registros'));
		}
		////////////////////////////////////////////////////////////////////

		/*$catalogo= $this->v_inventario_muebles_todo->generateList($this->SQLCA()." and upper(denominacion) LIKE '%$cod2%' and cod_tipo_desincorporacion=0", 'numero_identificacion ASC', null, '{n}.v_inventario_muebles_todo.numero_identificacion', '{n}.v_inventario_muebles_todo.denominacion');
 			if(!empty($catalogo)){
		 		$this->concatena($catalogo,'select');
		 	}else{
		 		$this->concatena(array(),'select');
		 	}*/
	}else if($ir=='vista_2'){
		if($var==2){
			$this->set('ir','ubicacion');

		}else{
			$this->set('ir','ubicaciones');
		}
	}//fin ir no


}//fin cimp01_inventario_muebles_bienes_2




function cimp01_inventario_muebles_bienes_pdf_2(){
	//cimd03_inventario_muebles
	$this->layout = "pdf";
	$cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	//$ano = $this->ano_ejecucion();

	isset($this->data['cimp01']['fecha_inventario']) ? $this->set('fecha_inventario',$this->data['cimp01']['fecha_inventario']) : $this->set('fecha_inventario','');


		if($this->data['cimp01']['radio_ver']!='' && $this->data['cimp01']['todos_ver']!=''){
			$inst_dep=$this->data['cimp01']['inst_dep'];
			//echo "<br>".$ano=$this->data['cimp01']['ano'];
			$radio_ver=$this->data['cimp01']['radio_ver'];
			$todos_ver=$this->data['cimp01']['todos_ver'];

				if($inst_dep==1){
					$cond=$this->condicionNDEP();
					$group="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_republica,deno_republica,cod_estado,deno_estado,cod_municipio,deno_municipio,conocido,cod_parroquia,deno_parroquia,cod_centro,deno_centro,cod_institucion,deno_institucion,cod_dependencia,deno_dependencia,cod_dir_superior,deno_dir_superior,cod_coordinacion,deno_coordinacion,cod_secretaria,deno_secretaria,cod_direccion,deno_direccion,cod_division,deno_division,cod_departamento,deno_departamento,cod_oficina,deno_oficina,cod_tipo,cod_grupo,cod_subgrupo,cod_seccion,cantidad,numero_identificacion,denominacion,valor_unitario,fecha_incorporacion";
					$group1="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_republica,deno_republica,cod_estado,deno_estado,cod_municipio,deno_municipio,conocido,cod_parroquia,deno_parroquia,cod_centro,deno_centro,cod_institucion,deno_institucion,cod_dependencia,deno_dependencia,cod_dir_superior,deno_dir_superior,cod_coordinacion,deno_coordinacion,cod_secretaria,deno_secretaria,cod_direccion,deno_direccion,cod_division,deno_division,cod_departamento,deno_departamento,cod_oficina,deno_oficina";
				}else{
					$cond=$this->SQLCA();
					$group="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_republica,deno_republica,cod_estado,deno_estado,cod_municipio,deno_municipio,conocido,cod_parroquia,deno_parroquia,cod_centro,deno_centro,cod_institucion,deno_institucion,cod_dependencia,deno_dependencia,cod_dir_superior,deno_dir_superior,cod_coordinacion,deno_coordinacion,cod_secretaria,deno_secretaria,cod_direccion,deno_direccion,cod_division,deno_division,cod_departamento,deno_departamento,cod_oficina,deno_oficina,cod_tipo,cod_grupo,cod_subgrupo,cod_seccion,cantidad,numero_identificacion,denominacion,valor_unitario,fecha_incorporacion";
					$group1="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_republica,deno_republica,cod_estado,deno_estado,cod_municipio,deno_municipio,conocido,cod_parroquia,deno_parroquia,cod_centro,deno_centro,cod_institucion,deno_institucion,cod_dependencia,deno_dependencia,cod_dir_superior,deno_dir_superior,cod_coordinacion,deno_coordinacion,cod_secretaria,deno_secretaria,cod_direccion,deno_direccion,cod_division,deno_division,cod_departamento,deno_departamento,cod_oficina,deno_oficina";
				}

//////////////////////////////////////////////////////////////////////////////////////////////////

				if($radio_ver==2){
					if($this->data['cimp01']['cod_mueble']!=''){
						$mueble=$this->data['cimp01']['cod_mueble'];
						$cond.=" and ".$this->sql_codigo_mueble($mueble);
					}else{
						echo'<script>history.back(1);</script>';
					}
				}

/////////////////////////////////////////////////////////////////////////////////////////////////

				if($this->data['cimp01']['todos_ver']!=''){
					$todos_ver=$this->data['cimp01']['todos_ver'];
					if($todos_ver==2){
						/////////////aqui son todos los muebles de una ubicacion en particular/////////////////
//						echo "<br>hola";
//						pr($this->data);
							if(!empty($this->data['cnmp09']['cod_estado']) && empty($this->data['cnmp09']['cod_municipio']) && empty($this->data['cnmp09']['cod_parroquia']) && empty($this->data['cnmp09']['cod_centro_poblado'])){
								$estado=$this->data['cnmp09']['cod_estado'];
								$cond.=" and cod_republica=".$cod_presi." and cod_estado=".$estado;
							}else if(!empty($this->data['cnmp09']['cod_estado']) && !empty($this->data['cnmp09']['cod_municipio']) && empty($this->data['cnmp09']['cod_parroquia']) && empty($this->data['cnmp09']['cod_centro_poblado'])){
								$estado=$this->data['cnmp09']['cod_estado'];
								$municipio=$this->data['cnmp09']['cod_municipio'];
								$cond.=" and cod_republica=".$cod_presi." and cod_estado=".$estado." and cod_municipio=".$municipio;
							}else if(!empty($this->data['cnmp09']['cod_estado']) && !empty($this->data['cnmp09']['cod_municipio']) && !empty($this->data['cnmp09']['cod_parroquia']) && empty($this->data['cnmp09']['cod_centro_poblado'])){
								$estado=$this->data['cnmp09']['cod_estado'];
								$municipio=$this->data['cnmp09']['cod_municipio'];
								$parroquia=$this->data['cnmp09']['cod_parroquia'];
								$cond.=" and cod_republica=".$cod_presi." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia;
							}else if(!empty($this->data['cnmp09']['cod_estado']) && !empty($this->data['cnmp09']['cod_municipio']) && !empty($this->data['cnmp09']['cod_parroquia']) && !empty($this->data['cnmp09']['cod_centro_poblado'])){
								$estado=$this->data['cnmp09']['cod_estado'];
								$municipio=$this->data['cnmp09']['cod_municipio'];
								$parroquia=$this->data['cnmp09']['cod_parroquia'];
								$centro=$this->data['cnmp09']['cod_centro_poblado'];
								$cond.=" and cod_republica=".$cod_presi." and cod_estado=".$estado." and cod_municipio=".$municipio." and cod_parroquia=".$parroquia." and cod_centro=".$centro;
							}/*else{
								echo'<script>history.back(1);</script>';
							}*/

							if(!empty($this->data['cnmp09']['cod_institucion']) && empty($this->data['cnmp09']['cod_dependencia']) && empty($this->data['cnmp09']['cod_superior']) && empty($this->data['cnmp09']['cod_coordinacion']) && empty($this->data['cnmp09']['cod_secretaria']) && empty($this->data['cnmp09']['cod_direccion']) && empty($this->data['cnmp09']['cod_division']) && empty($this->data['cnmp09']['cod_departamento']) && empty($this->data['cnmp09']['cod_oficina'])){
								$institucion=$this->data['cnmp09']['cod_institucion'];
								$cond.=" and cod_institucion=".$institucion;
							}else if(!empty($this->data['cnmp09']['cod_institucion']) && !empty($this->data['cnmp09']['cod_dependencia']) && empty($this->data['cnmp09']['cod_superior']) && empty($this->data['cnmp09']['cod_coordinacion']) && empty($this->data['cnmp09']['cod_secretaria']) && empty($this->data['cnmp09']['cod_direccion']) && empty($this->data['cnmp09']['cod_division']) && empty($this->data['cnmp09']['cod_departamento']) && empty($this->data['cnmp09']['cod_oficina'])){
								$institucion=$this->data['cnmp09']['cod_institucion'];
								$dependencia=$this->data['cnmp09']['cod_dependencia'];
								$cond.=" and cod_institucion=".$institucion." and cod_dependencia=".$dependencia;
							}else if(!empty($this->data['cnmp09']['cod_institucion']) && !empty($this->data['cnmp09']['cod_dependencia']) && !empty($this->data['cnmp09']['cod_superior']) && empty($this->data['cnmp09']['cod_coordinacion']) && empty($this->data['cnmp09']['cod_secretaria']) && empty($this->data['cnmp09']['cod_direccion']) && empty($this->data['cnmp09']['cod_division']) && empty($this->data['cnmp09']['cod_departamento']) && empty($this->data['cnmp09']['cod_oficina'])){
								$institucion=$this->data['cnmp09']['cod_institucion'];
								$dependencia=$this->data['cnmp09']['cod_dependencia'];
								$superior=$this->data['cnmp09']['cod_superior'];
								$cond.=" and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$superior;
							}else if(!empty($this->data['cnmp09']['cod_institucion']) && !empty($this->data['cnmp09']['cod_dependencia']) && !empty($this->data['cnmp09']['cod_superior']) && !empty($this->data['cnmp09']['cod_coordinacion']) && empty($this->data['cnmp09']['cod_secretaria']) && empty($this->data['cnmp09']['cod_direccion']) && empty($this->data['cnmp09']['cod_division']) && empty($this->data['cnmp09']['cod_departamento']) && empty($this->data['cnmp09']['cod_oficina'])){
								$institucion=$this->data['cnmp09']['cod_institucion'];
								$dependencia=$this->data['cnmp09']['cod_dependencia'];
								$superior=$this->data['cnmp09']['cod_superior'];
								$coordinacion=$this->data['cnmp09']['cod_coordinacion'];
								$cond.=" and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$superior." and cod_coordinacion=".$coordinacion;
							}else if(!empty($this->data['cnmp09']['cod_institucion']) && !empty($this->data['cnmp09']['cod_dependencia']) && !empty($this->data['cnmp09']['cod_superior']) && !empty($this->data['cnmp09']['cod_coordinacion']) && !empty($this->data['cnmp09']['cod_secretaria']) && empty($this->data['cnmp09']['cod_direccion']) && empty($this->data['cnmp09']['cod_division']) && empty($this->data['cnmp09']['cod_departamento']) && empty($this->data['cnmp09']['cod_oficina'])){
								$institucion=$this->data['cnmp09']['cod_institucion'];
								$dependencia=$this->data['cnmp09']['cod_dependencia'];
								$superior=$this->data['cnmp09']['cod_superior'];
								$coordinacion=$this->data['cnmp09']['cod_coordinacion'];
								$secretaria=$this->data['cnmp09']['cod_secretaria'];
								$cond.=" and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$superior." and cod_coordinacion=".$coordinacion." and cod_secretaria=".$secretaria;
							}else if(!empty($this->data['cnmp09']['cod_institucion']) && !empty($this->data['cnmp09']['cod_dependencia']) && !empty($this->data['cnmp09']['cod_superior']) && !empty($this->data['cnmp09']['cod_coordinacion']) && !empty($this->data['cnmp09']['cod_secretaria']) && !empty($this->data['cnmp09']['cod_direccion']) && empty($this->data['cnmp09']['cod_division']) && empty($this->data['cnmp09']['cod_departamento']) && empty($this->data['cnmp09']['cod_oficina'])){
								$institucion=$this->data['cnmp09']['cod_institucion'];
								$dependencia=$this->data['cnmp09']['cod_dependencia'];
								$superior=$this->data['cnmp09']['cod_superior'];
								$coordinacion=$this->data['cnmp09']['cod_coordinacion'];
								$secretaria=$this->data['cnmp09']['cod_secretaria'];
								$direccion=$this->data['cnmp09']['cod_direccion'];
								$cond.=" and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$superior." and cod_coordinacion=".$coordinacion." and cod_secretaria=".$secretaria." and cod_direccion=".$direccion;
							}else if(!empty($this->data['cnmp09']['cod_institucion']) && !empty($this->data['cnmp09']['cod_dependencia']) && !empty($this->data['cnmp09']['cod_superior']) && !empty($this->data['cnmp09']['cod_coordinacion']) && !empty($this->data['cnmp09']['cod_secretaria']) && !empty($this->data['cnmp09']['cod_direccion']) && !empty($this->data['cnmp09']['cod_division']) && empty($this->data['cnmp09']['cod_departamento']) && empty($this->data['cnmp09']['cod_oficina'])){
								$institucion=$this->data['cnmp09']['cod_institucion'];
								$dependencia=$this->data['cnmp09']['cod_dependencia'];
								$superior=$this->data['cnmp09']['cod_superior'];
								$coordinacion=$this->data['cnmp09']['cod_coordinacion'];
								$secretaria=$this->data['cnmp09']['cod_secretaria'];
								$direccion=$this->data['cnmp09']['cod_direccion'];
								$division=$this->data['cnmp09']['cod_division'];
								$cond.=" and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$superior." and cod_coordinacion=".$coordinacion." and cod_secretaria=".$secretaria." and cod_direccion=".$direccion." and cod_division=".$division;
							}else if(!empty($this->data['cnmp09']['cod_institucion']) && !empty($this->data['cnmp09']['cod_dependencia']) && !empty($this->data['cnmp09']['cod_superior']) && !empty($this->data['cnmp09']['cod_coordinacion']) && !empty($this->data['cnmp09']['cod_secretaria']) && !empty($this->data['cnmp09']['cod_direccion']) && !empty($this->data['cnmp09']['cod_division']) && !empty($this->data['cnmp09']['cod_departamento']) && empty($this->data['cnmp09']['cod_oficina'])){
								$institucion=$this->data['cnmp09']['cod_institucion'];
								$dependencia=$this->data['cnmp09']['cod_dependencia'];
								$superior=$this->data['cnmp09']['cod_superior'];
								$coordinacion=$this->data['cnmp09']['cod_coordinacion'];
								$secretaria=$this->data['cnmp09']['cod_secretaria'];
								$direccion=$this->data['cnmp09']['cod_direccion'];
								$division=$this->data['cnmp09']['cod_division'];
								$departamento=$this->data['cnmp09']['cod_departamento'];
								$cond.=" and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$superior." and cod_coordinacion=".$coordinacion." and cod_secretaria=".$secretaria." and cod_direccion=".$direccion." and cod_division=".$division." and cod_departamento=".$departamento;
							}else if(!empty($this->data['cnmp09']['cod_institucion']) && !empty($this->data['cnmp09']['cod_dependencia']) && !empty($this->data['cnmp09']['cod_superior']) && !empty($this->data['cnmp09']['cod_coordinacion']) && !empty($this->data['cnmp09']['cod_secretaria']) && !empty($this->data['cnmp09']['cod_direccion']) && !empty($this->data['cnmp09']['cod_division']) && !empty($this->data['cnmp09']['cod_departamento']) && !empty($this->data['cnmp09']['cod_oficina'])){
								$institucion=$this->data['cnmp09']['cod_institucion'];
								$dependencia=$this->data['cnmp09']['cod_dependencia'];
								$superior=$this->data['cnmp09']['cod_superior'];
								$coordinacion=$this->data['cnmp09']['cod_coordinacion'];
								$secretaria=$this->data['cnmp09']['cod_secretaria'];
								$direccion=$this->data['cnmp09']['cod_direccion'];
								$division=$this->data['cnmp09']['cod_division'];
								$departamento=$this->data['cnmp09']['cod_departamento'];
								$oficina=$this->data['cnmp09']['cod_oficina'];
								$cond.=" and cod_institucion=".$institucion." and cod_dependencia=".$dependencia." and cod_dir_superior=".$superior." and cod_coordinacion=".$coordinacion." and cod_secretaria=".$secretaria." and cod_direccion=".$direccion." and cod_division=".$division." and cod_departamento=".$departamento." and cod_oficina=".$oficina;
							}/*else{
								echo'<script>history.back(1);</script>';
							}*/

					}
				}else{
					echo'<script>history.back(1);</script>';
				}
		}else{

		}
//echo "<br> Filtro: ".$cond;

	if($_SESSION['SScodpresi']==1 && $_SESSION['SScodentidad']==12 && $_SESSION['SScodtipoinst']==50 && $_SESSION['SScodinst']==143){
		/*** COLOCAR AQUI ORDENAMIENTO DEL REPORTE PARA LA ALCALDIA JUAN GERMAN ROSCIO */
		$order="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_republica,cod_estado,cod_municipio,conocido,cod_parroquia,cod_centro,cod_institucion,cod_dependencia,cod_dir_superior,cod_coordinacion,cod_secretaria,cod_direccion,cod_division,cod_departamento,cod_oficina";
		$order1="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_republica,cod_estado,cod_municipio,conocido,cod_parroquia,cod_centro,cod_institucion,cod_dependencia,cod_dir_superior,cod_coordinacion,cod_secretaria,cod_direccion,cod_division,cod_departamento,cod_oficina,numero_identificacion,cod_tipo,cod_grupo,cod_subgrupo,cod_seccion,cantidad";

	}else{
		/*** COLOCAR AQUI ORDENAMIENTO DEL REPORTE PARA OTRAS ALCALDIAS Y GOBERNACIONES */
		$order="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_republica,cod_estado,cod_municipio,conocido,cod_parroquia,cod_centro,cod_institucion,cod_dependencia,cod_dir_superior,cod_coordinacion,cod_secretaria,cod_direccion,cod_division,cod_departamento,cod_oficina";
		$order1="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_republica,cod_estado,cod_municipio,conocido,cod_parroquia,cod_centro,cod_institucion,cod_dependencia,cod_dir_superior,cod_coordinacion,cod_secretaria,cod_direccion,cod_division,cod_departamento,cod_oficina,cod_tipo,cod_grupo,cod_subgrupo,cod_seccion,cantidad,numero_identificacion";
	}
	$sql1="SELECT ".$group." FROM v_inventario_muebles_todo where ".$cond." and cod_tipo_desincorporacion=0 group by ".$group." order by ".$order1." asc";
	$datos2=$this->v_inventario_muebles_todo->execute($sql1);

	$this->set('datos',$datos2);
	$this->data['cimp01']['inst_dep'];
	$this->data['cimp01']['inst_dep'];

}//fin cimp01_inventario_muebles_bienes_pdf_2



function select3($opcion=null,$var=null){
	$this->layout="ajax";
	$cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	if($var!=''){
		switch($opcion){
			case 'estado':
				$this->Session->delete('tipo');
				$this->Session->write('tipo',$var);
				$this->set('no','');
				$this->set('SELECT','municipio');
				$this->set('codigo','estado');
				$this->set('seleccion','');
				$this->set('n',2);
				$this->Session->write('estado',$var);
				$cond =" cod_republica=".$cod_presi;
				$lista=  $this->cugd01_estados->generateList($cond, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
				$this->concatena($lista, 'vector');
				if($var==1){
					$this->set('anula','disabled');
					$this->set('Message_existe','proceda a seleccionar el banco');
				}else{
					$this->set('anula','otros');
				}
			break;
			case 'municipio':
				$this->set('no','');
				$this->set('SELECT','parroquia');
				$this->set('codigo','municipio');
				$this->set('seleccion','');
				$this->set('n',3);
				$this->Session->write('cod1',$var);
				$cond =" cod_republica=".$cod_presi." and cod_estado=".$var;
				$lista=  $this->cugd01_municipios->generateList($cond, 'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
				$this->concatena($lista, 'vector');
				$this->set('anula','otros');
			break;
			case 'parroquia':
				$this->set('no','');
				$this->set('SELECT','centro_poblado');
				$this->set('codigo','parroquia');
				$this->set('seleccion','');
				$this->set('n',4);
				$this->Session->write('cod2',$var);
				$cod1=$this->Session->read('cod1');
				$cond =" cod_republica=".$cod_presi." and cod_estado=".$cod1." and cod_municipio=".$var;
				$lista=  $this->cugd01_parroquias->generateList($cond, 'cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
				$this->concatena($lista, 'vector');
				$this->set('anula','otros');
			break;
			case 'centro_poblado':
				$this->set('anula','otros');
				$this->set('no','');
				$this->set('SELECT','institucion');
				$this->set('codigo','centro_poblado');
				$this->set('seleccion','');
				$this->set('n',5);
				$this->Session->write('cod3',$var);
				$cod1=$this->Session->read('cod1');
				$cod2=$this->Session->read('cod2');
				$cond =" cod_republica=".$cod_presi." and cod_estado=".$cod1." and cod_municipio=".$cod2." and cod_parroquia=".$var;
				$lista=  $this->cugd01_centropoblados->generateList($cond, 'cod_centro ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
				$this->concatena($lista, 'vector');
			break;
		}//fin switch
	}
}//fin select3


function select2($opcion=null,$var=null){
	$this->layout="ajax";
	$cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	if($var!=''){
		switch($opcion){
			case 'dependencia':
				$this->Session->delete('tipo');
				$this->Session->write('tipo',$var);
				$this->set('no','');
				$this->set('SELECT','superior');
				$this->set('codigo','dependencia');
				$this->set('seleccion','');
				$this->set('n',2);
				$this->Session->write('inst',$var);
				$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$var;
				$lista=  $this->cugd02_dependencia->generateList($cond, 'cod_dependencia ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
				$this->concatena($lista, 'vector');
				/*if($var==1){
					$this->set('anula','disabled');
					$this->set('Message_existe','proceda a seleccionar el banco');
				}else{
					$this->set('anula','otros');
				}*/
			break;
			case 'superior':
				$this->set('no','');
				$this->set('SELECT','coordinacion');
				$this->set('codigo','superior');
				$this->set('seleccion','');
				$this->set('n',3);
				$this->Session->write('dep',$var);
				$inst=$this->Session->read('inst');
				$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$inst." and cod_dependencia=".$var;
				$lista=  $this->cugd02_direccionsuperior->generateList($cond, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
				$this->concatena($lista, 'vector');
				$this->set('anula','otros');
			break;
			case 'coordinacion':
				$this->set('no','');
				$this->set('SELECT','secretaria');
				$this->set('codigo','coordinacion');
				$this->set('seleccion','');
				$this->set('n',4);
				$this->Session->write('dir_sup',$var);
				$inst=$this->Session->read('inst');
				$dep=$this->Session->read('dep');
				$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$inst." and cod_dependencia=".$dep." and cod_dir_superior=".$var;
				$lista=  $this->cugd02_coordinacion->generateList($cond, 'cod_coordinacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
				$this->concatena($lista, 'vector');
				$this->set('anula','otros');
			break;
			case 'secretaria':
				$this->set('anula','otros');
				$this->set('no','');
				$this->set('SELECT','direccion');
				$this->set('codigo','secretaria');
				$this->set('seleccion','');
				$this->set('n',5);
				$this->Session->write('coor',$var);
				$inst=$this->Session->read('inst');
				$dep=$this->Session->read('dep');
				$dir_sur=$this->Session->read('dir_sup');
				$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$inst." and cod_dependencia=".$dep." and cod_dir_superior=".$dir_sur." and cod_coordinacion=".$var;
				$lista=  $this->cugd02_secretaria->generateList($cond, 'cod_secretaria ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
				$this->concatena($lista, 'vector');
			break;
			case 'direccion':
				$this->set('no','');
				$this->set('SELECT','division');
				$this->set('codigo','direccion');
				$this->set('seleccion','');
				$this->set('n',6);
				$this->Session->write('secre',$var);
				$inst=$this->Session->read('inst');
				$dep=$this->Session->read('dep');
				$dir_sur=$this->Session->read('dir_sup');
				$coor=$this->Session->read('coor');
				$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$inst." and cod_dependencia=".$dep." and cod_dir_superior=".$dir_sur." and cod_coordinacion=".$coor." and cod_secretaria=".$var;
				$lista=  $this->cugd02_direccion->generateList($cond, 'cod_direccion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
				$this->concatena($lista, 'vector');
				$this->set('anula','otros');
			break;
			case 'division':
				$this->set('no','');
				$this->set('SELECT','departamento');
				$this->set('codigo','division');
				$this->set('seleccion','');
				$this->set('n',7);
				$this->Session->write('direc',$var);
				$inst=$this->Session->read('inst');
				$dep=$this->Session->read('dep');
				$dir_sur=$this->Session->read('dir_sup');
				$coor=$this->Session->read('coor');
				$secre=$this->Session->read('secre');
				$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$inst." and cod_dependencia=".$dep." and cod_dir_superior=".$dir_sur." and cod_coordinacion=".$coor." and cod_secretaria=".$secre." and cod_direccion=".$var;
				$lista=  $this->cugd02_division->generateList($cond, 'cod_division ASC', null, '{n}.cugd02_division.cod_division', '{n}.cugd02_division.denominacion');
				$this->concatena($lista, 'vector');
				$this->set('anula','otros');
			break;
			case 'departamento':
				$this->set('no','');
				$this->set('SELECT','oficina');
				$this->set('codigo','departamento');
				$this->set('seleccion','');
				$this->set('n',8);
				$this->Session->write('div',$var);
				$inst=$this->Session->read('inst');
				$dep=$this->Session->read('dep');
				$dir_sur=$this->Session->read('dir_sup');
				$coor=$this->Session->read('coor');
				$secre=$this->Session->read('secre');
				$direc=$this->Session->read('direc');
				$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$inst." and cod_dependencia=".$dep." and cod_dir_superior=".$dir_sur." and cod_coordinacion=".$coor." and cod_secretaria=".$secre." and cod_direccion=".$direc." and cod_division=".$var;
				$lista=  $this->cugd02_departamento->generateList($cond, 'cod_departamento ASC', null, '{n}.cugd02_departamento.cod_departamento', '{n}.cugd02_departamento.denominacion');
				$this->concatena($lista, 'vector');
				$this->set('anula','otros');
			break;
			case 'oficina':
				$this->set('no','no');
				$this->set('SELECT','oficina');
				$this->set('codigo','oficina');
				$this->set('seleccion','');
				$this->set('n',9);
				$this->Session->write('depar',$var);
				$inst=$this->Session->read('inst');
				$dep=$this->Session->read('dep');
				$dir_sur=$this->Session->read('dir_sup');
				$coor=$this->Session->read('coor');
				$secre=$this->Session->read('secre');
				$direc=$this->Session->read('direc');
				$div=$this->Session->read('div');
				$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$inst." and cod_dependencia=".$dep." and cod_dir_superior=".$dir_sur." and cod_coordinacion=".$coor." and cod_secretaria=".$secre." and cod_direccion=".$direc." and cod_division=".$div." and cod_departamento=".$var;
				$lista=  $this->cugd02_oficina->generateList($cond, 'cod_oficina ASC', null, '{n}.cugd02_oficina.cod_oficina', '{n}.cugd02_oficina.denominacion');
				$this->concatena($lista, 'vector');
				$this->set('anula','otros');
			break;
		}//fin switch
	}


}//fin select3


function mostrar2($opcion=null,$var=null){
	$this->layout="ajax";
	$cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	if($var!=''){
		switch($opcion){
			case 'deno_institucion'://echo $opcion;
				$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$var;
				$deno_estado = $this->cugd02_institucion->field('denominacion', $conditions = $cond, $order ="cod_institucion ASC");
				$this->set('denomi', $deno_estado);
				$this->set('denominacion',$opcion);
				echo "<script>";
					echo "document.getElementById('deno_dependencia1').value='';";
					echo "document.getElementById('deno_superior1').value='';";
					echo "document.getElementById('deno_coordinacion1').value='';";
					echo "document.getElementById('deno_secretaria1').value='';";
					echo "document.getElementById('deno_direccion1').value='';";
					echo "document.getElementById('deno_division1').value='';";
					echo "document.getElementById('deno_departamento1').value='';";
					echo "document.getElementById('deno_oficina1').value='';";
				echo "</script>";
			break;
			case 'deno_dependencia'://echo $opcion;
				$inst=$this->Session->read('inst');
				$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$inst." and cod_dependencia=".$var;
				$deno_municipio = $this->cugd02_dependencia->field('denominacion', $conditions = $cond, $order ="cod_dependencia ASC");
				$this->set('denomi', $deno_municipio);
				$this->set('denominacion',$opcion);
				echo "<script>";
					echo "document.getElementById('deno_superior1').value='';";
					echo "document.getElementById('deno_coordinacion1').value='';";
					echo "document.getElementById('deno_secretaria1').value='';";
					echo "document.getElementById('deno_direccion1').value='';";
					echo "document.getElementById('deno_division1').value='';";
					echo "document.getElementById('deno_departamento1').value='';";
					echo "document.getElementById('deno_oficina1').value='';";
				echo "</script>";
			break;
			case 'deno_superior'://echo $opcion;
				$inst=$this->Session->read('inst');
				$dep=$this->Session->read('dep');
				$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$inst." and cod_dependencia=".$dep." and cod_dir_superior=".$var;
				$deno_parroquia = $this->cugd02_direccionsuperior->field('denominacion', $conditions = $cond, $order ="cod_dir_superior ASC");
				$this->set('denomi', $deno_parroquia);
				$this->set('denominacion',$opcion);
				echo "<script>";
					echo "document.getElementById('deno_coordinacion1').value='';";
					echo "document.getElementById('deno_secretaria1').value='';";
					echo "document.getElementById('deno_direccion1').value='';";
					echo "document.getElementById('deno_division1').value='';";
					echo "document.getElementById('deno_departamento1').value='';";
					echo "document.getElementById('deno_oficina1').value='';";
				echo "</script>";
			break;
			case 'deno_coordinacion':
				$inst=$this->Session->read('inst');
				$dep=$this->Session->read('dep');
				$dir_sup=$this->Session->read('dir_sup');
				$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$inst." and cod_dependencia=".$dep." and cod_dir_superior=".$dir_sup." and cod_coordinacion=".$var;
				$deno_banco = $this->cugd02_coordinacion->field('denominacion', $cond, $order ="cod_coordinacion ASC");
				$this->set('denomi', $deno_banco);
				$this->set('denominacion',$opcion);
				echo "<script>";
					echo "document.getElementById('deno_secretaria1').value='';";
					echo "document.getElementById('deno_direccion1').value='';";
					echo "document.getElementById('deno_division1').value='';";
					echo "document.getElementById('deno_departamento1').value='';";
					echo "document.getElementById('deno_oficina1').value='';";
				echo "</script>";
			break;
			case 'deno_secretaria':
				$inst=$this->Session->read('inst');
				$dep=$this->Session->read('dep');
				$dir_sup=$this->Session->read('dir_sup');
				$coor=$this->Session->read('coor');
				$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$inst." and cod_dependencia=".$dep." and cod_dir_superior=".$dir_sup." and cod_coordinacion=".$coor." and cod_secretaria=".$var;
				$deno_banco = $this->cugd02_secretaria->field('denominacion', $cond, $order ="cod_secretaria ASC");
				$this->set('denomi', $deno_banco);
				$this->set('denominacion',$opcion);
				echo "<script>";
					echo "document.getElementById('deno_direccion1').value='';";
					echo "document.getElementById('deno_division1').value='';";
					echo "document.getElementById('deno_departamento1').value='';";
					echo "document.getElementById('deno_oficina1').value='';";
				echo "</script>";
			break;
			case 'deno_direccion':
				$inst=$this->Session->read('inst');
				$dep=$this->Session->read('dep');
				$dir_sup=$this->Session->read('dir_sup');
				$coor=$this->Session->read('coor');
				$secre=$this->Session->read('secre');
				$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$inst." and cod_dependencia=".$dep." and cod_dir_superior=".$dir_sup." and cod_coordinacion=".$coor." and cod_secretaria=".$secre." and cod_direccion=".$var;
				$deno_banco = $this->cugd02_direccion->field('denominacion', $cond, $order ="cod_direccion ASC");
				$this->set('denomi', $deno_banco);
				$this->set('denominacion',$opcion);
				echo "<script>";
					echo "document.getElementById('deno_division1').value='';";
					echo "document.getElementById('deno_departamento1').value='';";
					echo "document.getElementById('deno_oficina1').value='';";
				echo "</script>";
			break;
			case 'deno_division':
				$inst=$this->Session->read('inst');
				$dep=$this->Session->read('dep');
				$dir_sup=$this->Session->read('dir_sup');
				$coor=$this->Session->read('coor');
				$secre=$this->Session->read('secre');
				$dir=$this->Session->read('direc');
				$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$inst." and cod_dependencia=".$dep." and cod_dir_superior=".$dir_sup." and cod_coordinacion=".$coor." and cod_secretaria=".$secre." and cod_direccion=".$dir." and cod_division=".$var;
				$deno_banco = $this->cugd02_division->field('denominacion', $cond, $order ="cod_division ASC");
				$this->set('denomi', $deno_banco);
				$this->set('denominacion',$opcion);
				echo "<script>";
					echo "document.getElementById('deno_departamento1').value='';";
					echo "document.getElementById('deno_oficina1').value='';";
				echo "</script>";
			break;
			case 'deno_departamento':
				$inst=$this->Session->read('inst');
				$dep=$this->Session->read('dep');
				$dir_sup=$this->Session->read('dir_sup');
				$coor=$this->Session->read('coor');
				$secre=$this->Session->read('secre');
				$dir=$this->Session->read('direc');
				$div=$this->Session->read('div');
				$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$inst." and cod_dependencia=".$dep." and cod_dir_superior=".$dir_sup." and cod_coordinacion=".$coor." and cod_secretaria=".$secre." and cod_direccion=".$dir." and cod_division=".$div." and cod_departamento=".$var;
				$deno_banco = $this->cugd02_departamento->field('denominacion', $cond, $order ="cod_departamento ASC");
				$this->set('denomi', $deno_banco);
				$this->set('denominacion',$opcion);
				echo "<script>";
					echo "document.getElementById('deno_oficina1').value='';";
				echo "</script>";
			break;
			case 'deno_oficina':
				$inst=$this->Session->read('inst');
				$dep=$this->Session->read('dep');
				$dir_sup=$this->Session->read('dir_sup');
				$coor=$this->Session->read('coor');
				$secre=$this->Session->read('secre');
				$dir=$this->Session->read('direc');
				$div=$this->Session->read('div');
				$depar=$this->Session->read('depar');
				$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$inst." and cod_dependencia=".$dep." and cod_dir_superior=".$dir_sup." and cod_coordinacion=".$coor." and cod_secretaria=".$secre." and cod_direccion=".$dir." and cod_division=".$div." and cod_departamento=".$depar." and cod_oficina=".$var;
				$deno_banco = $this->cugd02_oficina->field('denominacion', $cond, $order ="cod_oficina ASC");
				$this->set('denomi', $deno_banco);
				$this->set('denominacion',$opcion);
			break;
		}// fin switch
	}else{
		$this->set('si','no');
	}
}// fin mostrar




function mostrar($opcion=null,$var=null){
	$this->layout="ajax";
	$cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	if($var!=''){
		switch($opcion){
			case 'deno_estado':
				$deno_estado = $this->cugd01_estados->field('denominacion', $conditions = "cod_republica=".$cod_presi." and cod_estado='$var'", $order ="cod_estado ASC");
				$this->set('denomi', $deno_estado);
				$this->set('denominacion',$opcion);
				 echo "<script>";
					echo "document.getElementById('deno_municipiox').value='';";
					echo "document.getElementById('deno_parroquiax').value='';";
					echo "document.getElementById('deno_centro_pobladox').value='';";
				 echo "</script>";
			break;
			case 'deno_municipio':
				$cod1=$this->Session->read('cod1');
				$deno_municipio = $this->cugd01_municipios->field('denominacion', $conditions = "cod_republica=".$cod_presi." and cod_estado='$cod1' and cod_municipio='$var'", $order ="cod_municipio ASC");
				$this->set('denomi', $deno_municipio);
				$this->set('denominacion',$opcion);
				echo "<script>";
					echo "document.getElementById('deno_parroquiax').value='';";
					echo "document.getElementById('deno_centro_pobladox').value='';";
				 echo "</script>";
			break;
			case 'deno_parroquia':
				$cod1=$this->Session->read('cod1');
				$cod2=$this->Session->read('cod2');
				$deno_parroquia = $this->cugd01_parroquias->field('denominacion', $conditions = "cod_republica=".$cod_presi." and cod_estado='$cod1' and cod_municipio='$cod2' and cod_parroquia='$var'", $order ="cod_parroquia ASC");
				$this->set('denomi', $deno_parroquia);
				$this->set('denominacion',$opcion);
				echo "<script>";
					echo "document.getElementById('deno_centro_pobladox').value='';";
				 echo "</script>";
			break;
			case 'deno_centro_poblado':
				$estado=$this->Session->read('estado');
				$cod1=$this->Session->read('cod1');
				$cod2=$this->Session->read('cod2');
				$cod3=$this->Session->read('cod3');
				$cond =" cod_republica=".$cod_presi." and cod_estado=".$cod1." and cod_municipio=".$cod2." and cod_parroquia=".$cod3." and cod_centro=".$var;
				$deno_banco = $this->cugd01_centropoblados->field('denominacion', $cond, $order ="cod_centro ASC");
				$this->set('denomi', $deno_banco);
				$this->set('denominacion',$opcion);
			break;
		}// fin switch
	}else{
		$this->set('si','no');
	}
}// fin mostrar



function cimp01_clasificacion_funcional_bienes($ir=null){
	if($ir=='si'){
		$this->layout = "ajax";
		$this->set('ir','si');
	}else if($ir=='no'){
		$this->layout = "pdf";
		$datos=$this->v_cimd01_escalera_codigos_bienes->findAll(null,null,'cod_tipo,cod_grupo,cod_subgrupo,cod_seccion ASC');
	//	pr($datos);
		if($datos!=null){
			$this->set('datos',$datos);
		}else{
			$this->set('datos',null);
		}
		$this->set('ir','no');
	}//fin ir no


}//fin cimp01_clasificacion_funcional_bienes




function cimp01_denominacion_movimientos_bienes($ir=null){
	if($ir=='si'){
		$this->layout = "ajax";
		$this->set('ir','si');
	}else if($ir=='no'){
		$this->layout = "pdf";
		$n1=$this->cimd02_tipo_movimiento->findCount('cod_tipo_mov=1');
		$n2=$this->cimd02_tipo_movimiento->findCount('cod_tipo_mov=2');
		$datos=$this->cimd02_tipo_movimiento->findAll(null,null,'cod_tipo_mov,cod_mov ASC');

	//	pr($datos);
		if($datos!=null){

			$i=0;
			if($n1>=$n2){
				foreach($datos as $x){
					if($x['cimd02_tipo_movimiento']['cod_tipo_mov']==1){
						$vec[$i]['cod_mov_in']=$x['cimd02_tipo_movimiento']['cod_mov'];
						$vec[$i]['denominacion_in']=$x['cimd02_tipo_movimiento']['denominacion'];
						$vec[$i]['cod_mov_des']='';
						$vec[$i]['denominacion_des']='';
					}
					$i++;
				}//fin foreach
				$i=0;
				foreach($datos as $x){
					if($x['cimd02_tipo_movimiento']['cod_tipo_mov']==2){
						$vec[$i]['cod_mov_in']=$vec[$i]['cod_mov_in'];
						$vec[$i]['denominacion_in']=$vec[$i]['denominacion_in'];
						$vec[$i]['cod_mov_des']=$x['cimd02_tipo_movimiento']['cod_mov'];
						$vec[$i]['denominacion_des']=$x['cimd02_tipo_movimiento']['denominacion'];
						$i++;
					}

				}//fin foreach
			}else{
				$i=0;
				foreach($datos as $x){
					if($x['cimd02_tipo_movimiento']['cod_tipo_mov']==2){
						$vec[$i]['cod_mov_in']='';
						$vec[$i]['denominacion_in']='';
						$vec[$i]['cod_mov_des']=$x['cimd02_tipo_movimiento']['cod_mov'];
						$vec[$i]['denominacion_des']=$x['cimd02_tipo_movimiento']['denominacion'];
						$i++;
					}


				}//fin foreach
					$i=0;
				foreach($datos as $x){
					if($x['cimd02_tipo_movimiento']['cod_tipo_mov']==1){
						$vec[$i]['cod_mov_in']=$x['cimd02_tipo_movimiento']['cod_mov'];
						$vec[$i]['denominacion_in']=$x['cimd02_tipo_movimiento']['denominacion'];
						$vec[$i]['cod_mov_des']=$vec[$i]['cod_mov_des'];
						$vec[$i]['denominacion_des']=$vec[$i]['denominacion_des'];
//						echo "<br>".$i;
						$i++;
					}

				}//fin foreach
			}

//		pr($vec);
		$this->set('datos',$datos);
		$this->set('vec',$vec);
		}else{
			$this->set('datos',null);
		}
		$this->set('ir','no');
	}//fin ir no


}//fin cimp01_denominacion_movimientos_bienes



 function reporte_equipos_y_costo_conservacion($var=null){
 	if($var!=null){
		if($var=='no'){
			$this->layout='ajax';
			$ano = $this->ano_ejecucion();
			$_SESSION['SScoddep']==1 ? $this->Session->write('consolidacion_reporte_cimd03_bienes',1) : $this->Session->write('consolidacion_reporte_cimd03_bienes',2);;
			if(isset($ano) && !empty($ano)){
				$ano_inicial = $ano - 20;
				$ano_final = $ano + 20;
				for($i=$ano_inicial; $i<=$ano_final; $i++){
					$array_ano[$i]=$i;
				}
			}else{
				$ano=date('Y');
				$ano_inicial = $ano - 20;
				$ano_final = $ano + 20;
				for($i=$ano_inicial; $i<=$ano_final; $i++){
					$array_ano[$i]=$i;
				}
			}
			$this->set('array_ano',$array_ano);
			$this->set('ano',$ano);
			$this->set('var',$var);

		}elseif($var=='si'){
			$this->layout='pdf';
			$cp = $this->Session->read('SScodpresi');
			$ce = $this->Session->read('SScodentidad');
			$cti = $this->Session->read('SScodtipoinst');
			$ci = $this->Session->read('SScodinst');
			$cd = $this->Session->read('SScoddep');
			$condicion="";

			$cd == 1 ? $consolidacion = $this->data['inventario_inmueble']['consolidacion_reporte'] : $consolidacion = 2;
			$ano = $this->data['inventario_inmueble']['ano'];
			$radio_muebles = $this->data['inventario_inmueble']['radio_muebles'];
			$condicion .= $this->SQLCA_consolidado($consolidacion)." and ano='$ano'";

			if($radio_muebles==2){
				$id_mueble = $this->data['inventario_inmueble']['mueble'];
				if(empty($id_mueble)){
					echo'<script>history.back(1);</script>';
				}else{
					$codigo_mueble = $this->sql_codigo_mueble($id_mueble);
					$condicion .= " and ".$codigo_mueble;
				}
			}

			$sql = "SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo,
		       deno_tipo, cod_grupo, deno_grupo, cod_subgrupo, deno_subgrupo,
		       cod_seccion, deno_seccion, especificaciones, numero_identificacion,
		       denominacion, cantidad, costo_unitario, tiempo_garantia, tienda_taller,
		       tecnico_mecanico, ano, mes, dia, reparacion_efecturada, cod_reparacion,
		       deno_reparacion, cod_repuesto, deno_repuesto,
		       (SELECT b.cod_republica FROM v_inventario_muebles_todo b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion AND b.numero_identificacion=a.numero_identificacion) AS cod_republica,
		       (SELECT b.deno_republica FROM v_inventario_muebles_todo b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion AND b.numero_identificacion=a.numero_identificacion) AS deno_republica,
		       (SELECT b.cod_estado FROM v_inventario_muebles_todo b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion AND b.numero_identificacion=a.numero_identificacion) AS cod_estado,
		       (SELECT b.deno_estado FROM v_inventario_muebles_todo b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion AND b.numero_identificacion=a.numero_identificacion) AS deno_estado,
		       (SELECT b.cod_municipio FROM v_inventario_muebles_todo b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion AND b.numero_identificacion=a.numero_identificacion) AS cod_municipio,
		       (SELECT b.deno_municipio FROM v_inventario_muebles_todo b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion AND b.numero_identificacion=a.numero_identificacion) AS deno_municipio,
		       (SELECT b.conocido FROM v_inventario_muebles_todo b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion AND b.numero_identificacion=a.numero_identificacion) AS conocido,
		       (SELECT b.cod_parroquia FROM v_inventario_muebles_todo b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion AND b.numero_identificacion=a.numero_identificacion) AS cod_parroquia,
		       (SELECT b.deno_parroquia FROM v_inventario_muebles_todo b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion AND b.numero_identificacion=a.numero_identificacion) AS deno_parroquia,
		       (SELECT b.cod_centro FROM v_inventario_muebles_todo b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion AND b.numero_identificacion=a.numero_identificacion) AS cod_centro,
		       (SELECT b.deno_centro FROM v_inventario_muebles_todo b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion AND b.numero_identificacion=a.numero_identificacion) AS deno_centro,
		       (SELECT b.cod_institucion FROM v_inventario_muebles_todo b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion AND b.numero_identificacion=a.numero_identificacion) AS cod_institucion,
		       (SELECT b.deno_institucion FROM v_inventario_muebles_todo b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion AND b.numero_identificacion=a.numero_identificacion) AS deno_institucion,
		       (SELECT b.cod_dependencia FROM v_inventario_muebles_todo b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion AND b.numero_identificacion=a.numero_identificacion) AS cod_dependencia,
		       (SELECT b.deno_dependencia FROM v_inventario_muebles_todo b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion AND b.numero_identificacion=a.numero_identificacion) AS deno_dependencia,
		       (SELECT b.cod_dir_superior FROM v_inventario_muebles_todo b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion AND b.numero_identificacion=a.numero_identificacion) AS cod_dir_superior,
		       (SELECT b.deno_dir_superior FROM v_inventario_muebles_todo b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion AND b.numero_identificacion=a.numero_identificacion) AS deno_dir_superior,
		       (SELECT b.cod_coordinacion FROM v_inventario_muebles_todo b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion AND b.numero_identificacion=a.numero_identificacion) AS cod_coordinacion,
		       (SELECT b.deno_coordinacion FROM v_inventario_muebles_todo b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion AND b.numero_identificacion=a.numero_identificacion) AS deno_coordinacion,
		       (SELECT b.cod_secretaria FROM v_inventario_muebles_todo b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion AND b.numero_identificacion=a.numero_identificacion) AS cod_secretaria,
		       (SELECT b.deno_secretaria FROM v_inventario_muebles_todo b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion AND b.numero_identificacion=a.numero_identificacion) AS deno_secretaria,
		       (SELECT b.cod_direccion FROM v_inventario_muebles_todo b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion AND b.numero_identificacion=a.numero_identificacion) AS cod_direccion,
		       (SELECT b.deno_direccion FROM v_inventario_muebles_todo b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion AND b.numero_identificacion=a.numero_identificacion) AS deno_direccion,
		       (SELECT b.cod_division FROM v_inventario_muebles_todo b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion AND b.numero_identificacion=a.numero_identificacion) AS cod_division,
		       (SELECT b.deno_division FROM v_inventario_muebles_todo b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion AND b.numero_identificacion=a.numero_identificacion) AS deno_division,
		       (SELECT b.cod_departamento FROM v_inventario_muebles_todo b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion AND b.numero_identificacion=a.numero_identificacion) AS cod_departamento,
		       (SELECT b.deno_departamento FROM v_inventario_muebles_todo b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion AND b.numero_identificacion=a.numero_identificacion) AS deno_departamento,
		       (SELECT b.cod_oficina FROM v_inventario_muebles_todo b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion AND b.numero_identificacion=a.numero_identificacion) AS cod_oficina,
		       (SELECT b.deno_oficina FROM v_inventario_muebles_todo b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo AND b.cod_subgrupo=a.cod_subgrupo AND b.cod_seccion=a.cod_seccion AND b.numero_identificacion=a.numero_identificacion) AS deno_oficina
		  FROM v_cimd05_equipos_mantenimiento_todo a WHERE ".$condicion." ORDER BY numero_identificacion, dia, mes, ano;";

		  $datos = $this->cimd03_inventario_muebles->execute($sql);
		  $this->set('datos',$datos);
		  $this->set('var',$var);
		}
 	}
 }//reporte_equipos_y_costo_conservacion


 function resumen_cuenta_bienes_unidad_trabajo($var=null){
 	if($var!=null){
 	$cod_presi     = $this->Session->read('SScodpresi');
	$cod_entidad   = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst      = $this->Session->read('SScodinst');
	$cod_dep       = $this->Session->read('SScoddep');
	$SScoddeporig  = $this->Session->read('SScoddeporig');
	$SScoddep      = $this->Session->read('SScoddep');

    $this->set('cod_presi',$cod_presi);
    $this->set('cod_entidad',$cod_entidad);
    $this->set('cod_tipo_inst',$cod_tipo_inst);
    $this->set('cod_inst',$cod_inst);
    $this->set('cod_dep',$cod_dep);

		if($var=='no'){
			$this->layout='ajax';
			$ano = $this->ano_ejecucion();
			if(isset($ano) && !empty($ano)){
				$ano_inicial = $ano - 20;
				$ano_final = $ano + 20;
				for($i=$ano_inicial; $i<=$ano_final; $i++){
					$array_ano[$i]=$i;
				}
			}else{
				$ano=date('Y');
				$ano_inicial = $ano - 20;
				$ano_final = $ano + 20;
				for($i=$ano_inicial; $i<=$ano_final; $i++){
					$array_ano[$i]=$i;
				}
			}
			$this->set('ano',$ano);
			$this->set('array_ano',$array_ano);
			$this->set('var',$var);

	$cont = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA()." and tipo_documento='10001'");
	if($cont == 0){
		$this->set('mensaje','Por favor, ingrese los nombres de los firmantes');
		$this->set('nombre_primera_firma','');
		$this->set('cargo_primera_firma','');
		$this->set('nombre_segunda_firma','');
		$this->set('cargo_segunda_firma','');
		$this->set('nombre_tercera_firma','N/A');
		$this->set('cargo_tercera_firma','N/A');
		$this->set('nombre_cuarta_firma','N/A');
		$this->set('cargo_cuarta_firma','N/A');
		$this->set('tipo_doc_anul', 10001);
		$firma_existe = 'no';
		echo'<script>';
       		echo" document.getElementById('enviar').disabled = 'true'; ";
		echo'</script>';
	}else{
		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=10001");
		$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
		$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
		$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
		$this->set('tipo_doc_anul',10001);
		$firma_existe = 'si';
		echo'<script>';
       		echo" document.getElementById('enviar').disabled = ''; ";
		echo'</script>';
	}
		$this->set('firma_existe',$firma_existe);

		}elseif($var=='si'){
			$this->layout='pdf';
			$cp = $this->Session->read('SScodpresi');
			$ce = $this->Session->read('SScodentidad');
			$cti = $this->Session->read('SScodtipoinst');
			$ci = $this->Session->read('SScodinst');
			$cd = $this->Session->read('SScoddep');

		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=10001");
		if(!empty($firmantes)){
			$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
			$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
			$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
			$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
			$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
			$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
			$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
			$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
		}else{
			$this->set('nombre_primera_firma','');
			$this->set('cargo_primera_firma','');
			$this->set('nombre_segunda_firma','');
			$this->set('cargo_segunda_firma','');
			$this->set('nombre_tercera_firma','');
			$this->set('cargo_tercera_firma','');
			$this->set('nombre_cuarta_firma','');
			$this->set('cargo_cuarta_firma','');
		}

		if($cd==1){
			$consolidacion=$this->data['movimiento_mueble']['consolidacion'];
			if($consolidacion==1){
				$cond_consolidacion = "a.cod_presi='$cp' AND a.cod_entidad='$ce' AND a.cod_tipo_inst='$cti' AND a.cod_inst='$ci'";
			}elseif($consolidacion==2){
				$cod_dependencia = $this->cod_dep_consolidado();
				$cond_consolidacion = "a.cod_presi='$cp' AND a.cod_entidad='$ce' AND a.cod_tipo_inst='$cti' AND a.cod_inst='$ci' AND a.cod_dep='$cod_dependencia'";
			}
		}else{
			$cond_consolidacion = "a.cod_presi='$cp' AND a.cod_entidad='$ce' AND a.cod_tipo_inst='$cti' AND a.cod_inst='$ci' AND a.cod_dep='$cd'";
		}

			$ano = $this->data['movimiento_mueble']['ano'];
			$condicion = "";

			// $por_ano = $this->data['movimiento_mueble']['por_ano']; // al descomentar esta linea recordar descomentar tambien de la vista el radio de todo el ano o 1 mes especifico
			$por_ano = 2;
			if($por_ano==1){
				/* $ano_final=$ano-1;
				$fecha_anterior_final = "31-12-".$ano_final;
				$fecha_actual_inicial = date("Y-m-d", mktime(0, 0, 0, 1, 1, $ano));
				$fecha_actual_final   = date("Y-m-d", mktime(0, 0, 0, 12, 31, $ano)); */
				$mes_letras = "ENERO-DICIEMBRE";
				$condicion .=" AND ((substr(fecha_incorporacion::text, 0, 5)::integer = '$ano') OR (substr(fecha_desincorporacion::text, 0, 5)::integer = '$ano'))";
			}else{
				// $ano_final=$ano-1;
				$mes = $this->data['movimiento_mueble']['selectmes'];
				if($mes==''){
					echo'<script>history.back(1);</script>';
				}else{
					/* $fecha_anterior_final = date("Y-m-d", mktime(0, 0, 0, $mes, 0, $ano));
					$fecha_actual_inicial = date("Y-m-d", mktime(0, 0, 0, $mes, 1, $ano));
					$fecha_actual_final   = date("Y-m-d", mktime(0, 0, 0, $mes+1, 0, $ano)); */

					$anoc_mesc = $ano."-".$this->zero($mes);
					// $anoc_mesc22 = $ano."-".($this->zero($mes)-1)."-01"; // validar con if: mes es 1
					$anoc_mesc22 = $ano."-".$this->zero($mes)."-01";
					// $condic22 = $condicion." AND ((fecha_incorporacion < '$anoc_mesc22' AND fecha_incorporacion != '1900-01-01') OR (fecha_desincorporacion < '$anoc_mesc22' AND fecha_desincorporacion != '1900-01-01'))";
					$condicion .=" AND ((substr(fecha_incorporacion::text, 0, 8)::text = '$anoc_mesc') OR (substr(fecha_desincorporacion::text, 0, 8)::text = '$anoc_mesc'))";
				}

				switch($mes){
					case '1': $mes_letras = "ENERO"; break;
					case '2': $mes_letras = "FEBRERO"; break;
					case '3': $mes_letras = "MARZO"; break;
					case '4': $mes_letras = "ABRIL"; break;
					case '5': $mes_letras = "MAYO"; break;
					case '6': $mes_letras = "JUNIO"; break;
					case '7': $mes_letras = "JULIO"; break;
					case '8': $mes_letras = "AGOSTO"; break;
					case '9': $mes_letras = "SEPTIEMBRE"; break;
					case '10': $mes_letras = "OCTUBRE"; break;
					case '11': $mes_letras = "NOVIEMBRE"; break;
					case '12': $mes_letras = "DICIEMBRE"; break;
				}
			}


			if(isset($consolidacion) && $consolidacion==1){
				$campos = 'a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, cod_republica, cod_estado, deno_estado, cod_municipio, deno_municipio, conocido, cod_parroquia, deno_parroquia, cod_centro, deno_centro, cod_institucion, deno_institucion, cod_dependencia, deno_dependencia, cod_dir_superior, deno_dir_superior, cod_coordinacion, deno_coordinacion, cod_secretaria, deno_secretaria, cod_direccion, deno_direccion, cod_division, deno_division, cod_departamento, deno_departamento, cod_oficina, deno_oficina,';
				$group_by = 'a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, cod_republica, cod_estado, deno_estado, cod_municipio, deno_municipio, conocido, cod_parroquia, deno_parroquia, cod_centro, deno_centro, cod_institucion, deno_institucion, cod_dependencia, deno_dependencia, cod_dir_superior, deno_dir_superior, cod_coordinacion, deno_coordinacion, cod_secretaria, deno_secretaria, cod_direccion, deno_direccion, cod_division, deno_division, cod_departamento, deno_departamento, cod_oficina, deno_oficina';
				$order2re = 'a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, cod_republica, cod_estado, deno_estado, cod_municipio, conocido, cod_parroquia, cod_centro, cod_institucion, cod_dependencia, cod_dir_superior, cod_coordinacion, cod_secretaria, cod_direccion, cod_division, cod_departamento, cod_oficina';
				$sql_datos = "SELECT ".$campos."
				count(cantidad) as cantidad,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_municipio=a.cod_municipio AND v.cod_centro=a.cod_centro AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_direccion=a.cod_direccion AND v.cod_division=a.cod_division AND v.cod_departamento=a.cod_departamento AND v.cod_oficina=a.cod_oficina AND v.cod_tipo_incorporacion != '0' AND v.fecha_incorporacion < '$anoc_mesc22') as incorporacion_anterior,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_municipio=a.cod_municipio AND v.cod_centro=a.cod_centro AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_direccion=a.cod_direccion AND v.cod_division=a.cod_division AND v.cod_departamento=a.cod_departamento AND v.cod_oficina=a.cod_oficina AND v.cod_tipo_desincorporacion != '0' AND v.fecha_desincorporacion < '$anoc_mesc22') as desincorporacion_anterior,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_municipio=a.cod_municipio AND v.cod_centro=a.cod_centro AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_direccion=a.cod_direccion AND v.cod_division=a.cod_division AND v.cod_departamento=a.cod_departamento AND v.cod_oficina=a.cod_oficina AND v.cod_tipo_incorporacion != '0' $condicion) as incorporacion_actual,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_municipio=a.cod_municipio AND v.cod_centro=a.cod_centro AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_direccion=a.cod_direccion AND v.cod_division=a.cod_division AND v.cod_departamento=a.cod_departamento AND v.cod_oficina=a.cod_oficina AND v.cod_tipo_desincorporacion != '0' AND v.cod_tipo_desincorporacion != '60' $condicion) as desincorporacion_actual_sin_60,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_municipio=a.cod_municipio AND v.cod_centro=a.cod_centro AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_direccion=a.cod_direccion AND v.cod_division=a.cod_division AND v.cod_departamento=a.cod_departamento AND v.cod_oficina=a.cod_oficina AND v.cod_tipo_desincorporacion != '0' AND v.cod_tipo_desincorporacion = '60' $condicion) as desincorporacion_actual_con_60
				FROM v_inventario_muebles_todo a
				WHERE ".$cond_consolidacion."
				GROUP BY ".$group_by. " ORDER BY ".$order2re." ASC;";
			}else{
				$campos = 'a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, cod_republica, cod_estado, deno_estado, cod_municipio, deno_municipio, conocido, cod_parroquia, deno_parroquia, cod_centro, deno_centro, cod_institucion, deno_institucion, cod_dependencia, deno_dependencia, cod_dir_superior, deno_dir_superior, cod_coordinacion, deno_coordinacion, cod_secretaria, deno_secretaria, cod_direccion, deno_direccion, cod_division, deno_division, cod_departamento, deno_departamento, cod_oficina, deno_oficina,';
				$group_by = 'a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, cod_republica, cod_estado, deno_estado, cod_municipio, deno_municipio, conocido, cod_parroquia, deno_parroquia, cod_centro, deno_centro, cod_institucion, deno_institucion, cod_dependencia, deno_dependencia, cod_dir_superior, deno_dir_superior, cod_coordinacion, deno_coordinacion, cod_secretaria, deno_secretaria, cod_direccion, deno_direccion, cod_division, deno_division, cod_departamento, deno_departamento, cod_oficina, deno_oficina';
				$order2re = 'a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, cod_republica, cod_estado, deno_estado, cod_municipio, conocido, cod_parroquia, cod_centro, cod_institucion, cod_dependencia, cod_dir_superior, cod_coordinacion, cod_secretaria, cod_direccion, cod_division, cod_departamento, cod_oficina';
				$sql_datos = "SELECT ".$campos."
				count(cantidad) as cantidad,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_dep=a.cod_dep AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_municipio=a.cod_municipio AND v.cod_centro=a.cod_centro AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_direccion=a.cod_direccion AND v.cod_division=a.cod_division AND v.cod_departamento=a.cod_departamento AND v.cod_oficina=a.cod_oficina AND v.cod_tipo_incorporacion != '0' AND v.fecha_incorporacion < '$anoc_mesc22') as incorporacion_anterior,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_dep=a.cod_dep AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_municipio=a.cod_municipio AND v.cod_centro=a.cod_centro AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_direccion=a.cod_direccion AND v.cod_division=a.cod_division AND v.cod_departamento=a.cod_departamento AND v.cod_oficina=a.cod_oficina AND v.cod_tipo_desincorporacion !='0' AND v.fecha_desincorporacion < '$anoc_mesc22') as desincorporacion_anterior,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_dep=a.cod_dep AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_municipio=a.cod_municipio AND v.cod_centro=a.cod_centro AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_direccion=a.cod_direccion AND v.cod_division=a.cod_division AND v.cod_departamento=a.cod_departamento AND v.cod_oficina=a.cod_oficina AND v.cod_tipo_incorporacion != '0' $condicion) as incorporacion_actual,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_dep=a.cod_dep AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_municipio=a.cod_municipio AND v.cod_centro=a.cod_centro AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_direccion=a.cod_direccion AND v.cod_division=a.cod_division AND v.cod_departamento=a.cod_departamento AND v.cod_oficina=a.cod_oficina AND v.cod_tipo_desincorporacion != '0' AND v.cod_tipo_desincorporacion != '60' $condicion) as desincorporacion_actual_sin_60,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_dep=a.cod_dep AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_municipio=a.cod_municipio AND v.cod_centro=a.cod_centro AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_direccion=a.cod_direccion AND v.cod_division=a.cod_division AND v.cod_departamento=a.cod_departamento AND v.cod_oficina=a.cod_oficina AND v.cod_tipo_desincorporacion != '0' AND v.cod_tipo_desincorporacion = '60' $condicion) as desincorporacion_actual_con_60
				FROM v_inventario_muebles_todo a
				WHERE ".$cond_consolidacion."
				GROUP BY ".$group_by. " ORDER BY ".$order2re." ASC;";
			}

			$datos = $this->cimd03_inventario_muebles->execute($sql_datos);
			$this->set('consolidadop',isset($consolidacion)?$consolidacion:2);
			$this->set('datos',$datos);
			$this->set('mes_letras',$mes_letras);
			$this->set('ano',$ano);
			$this->set('var',$var);
		}
 	}

 }


 function relacion_consolidada_bienes_muebles($var=null){
 	if($var!=null){
		if($var=='no'){
			$this->layout='ajax';
			$ano = $this->ano_ejecucion();
			if(isset($ano) && !empty($ano)){
				$ano_inicial = $ano - 20;
				$ano_final = $ano + 20;
				for($i=$ano_inicial; $i<=$ano_final; $i++){
					$array_ano[$i]=$i;
				}
			}else{
				$ano=date('Y');
				$ano_inicial = $ano - 20;
				$ano_final = $ano + 20;
				for($i=$ano_inicial; $i<=$ano_final; $i++){
					$array_ano[$i]=$i;
				}
			}
			$this->set('ano',$ano);
			$this->set('array_ano',$array_ano);
			$this->set('var',$var);

	$cont = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA()." and tipo_documento='10002'");
	if($cont == 0){
		$this->set('mensaje','Por favor, ingrese los nombres de los firmantes');
		$this->set('nombre_primera_firma','');
		$this->set('cargo_primera_firma','');
		$this->set('nombre_segunda_firma','');
		$this->set('cargo_segunda_firma','');
		$this->set('nombre_tercera_firma','N/A');
		$this->set('cargo_tercera_firma','N/A');
		$this->set('nombre_cuarta_firma','N/A');
		$this->set('cargo_cuarta_firma','N/A');
		$this->set('tipo_doc_anul', 10002);
		$firma_existe = 'no';
		echo'<script>';
       		echo" document.getElementById('enviar').disabled = 'true'; ";
		echo'</script>';
	}else{
		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=10002");
		$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
		$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
		$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
		$this->set('tipo_doc_anul',10002);
		$firma_existe = 'si';
		echo'<script>';
       		echo" document.getElementById('enviar').disabled = ''; ";
		echo'</script>';
	}
		$this->set('firma_existe',$firma_existe);

		}elseif($var=='si'){
			$this->layout='pdf';
			$cp = $this->Session->read('SScodpresi');
			$ce = $this->Session->read('SScodentidad');
			$cti = $this->Session->read('SScodtipoinst');
			$ci = $this->Session->read('SScodinst');
			$cd = $this->Session->read('SScoddep');

		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=10002");
		if(!empty($firmantes)){
			$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
			$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
			$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
			$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
			$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
			$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
			$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
			$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
		}else{
			$this->set('nombre_primera_firma','');
			$this->set('cargo_primera_firma','');
			$this->set('nombre_segunda_firma','');
			$this->set('cargo_segunda_firma','');
			$this->set('nombre_tercera_firma','');
			$this->set('cargo_tercera_firma','');
			$this->set('nombre_cuarta_firma','');
			$this->set('cargo_cuarta_firma','');
		}

			// $ano = $this->data['movimiento_mueble']['ano']; // al descomentar aqui, descomentar de la vista tambien para tomar el ano

		if($cd==1){
			$consolidacion=$this->data['movimiento_mueble']['consolidacion'];
			if($consolidacion==1){
				$cond_consolidacion = $this->SQLCA_consolidado($consolidacion, "a");
			}elseif($consolidacion==2){
				$cond_consolidacion = $this->SQLCA_consolidado($consolidacion, "a");
			}
		}else{
			$cond_consolidacion = "cod_presi='$cp' AND cod_entidad='$ce' AND cod_tipo_inst='$cti' AND cod_inst='$ci' AND cod_dep='$cd'";
		}

			if($cd==1){
				$group_and_order = "a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, ";
			}else{
				$group_and_order = "a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, ";
			}

			$sql_tipo = "SELECT ".$group_and_order." a.cod_tipo, (SELECT b.denominacion FROM cimd01_clasificacion_tipo b WHERE b.cod_tipo=a.cod_tipo) AS deno_tipo, SUM(a.cantidad * a.valor_unitario) AS monto FROM cimd03_inventario_muebles a WHERE ".$cond_consolidacion." AND a.cod_tipo='2' GROUP BY ".$group_and_order." a.cod_tipo ORDER BY ".$group_and_order." a.cod_tipo;";
			$sql_grupo = "SELECT ".$group_and_order." a.cod_tipo, a.cod_grupo, SUM(a.cantidad * a.valor_unitario) AS monto FROM cimd03_inventario_muebles a WHERE ".$cond_consolidacion." AND a.cod_tipo='2' GROUP BY ".$group_and_order." a.cod_tipo, a.cod_grupo ORDER BY ".$group_and_order." a.cod_tipo, a.cod_grupo;";
			$sql_subgrupo = "SELECT ".$group_and_order." a.cod_tipo, a.cod_grupo, a.cod_subgrupo, SUM(a.cantidad * a.valor_unitario) AS monto FROM cimd03_inventario_muebles a WHERE ".$cond_consolidacion." AND a.cod_tipo='2' GROUP BY ".$group_and_order." a.cod_tipo, a.cod_grupo, a.cod_subgrupo ORDER BY ".$group_and_order." a.cod_tipo, a.cod_grupo, a.cod_subgrupo;";
			$sql_seccion = "SELECT ".$group_and_order." a.cod_tipo, a.cod_grupo, a.cod_subgrupo, a.cod_seccion,
							(SELECT b.denominacion FROM cimd01_clasificacion_grupo b WHERE b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo) AS deno_grupo,
							(SELECT c.denominacion FROM cimd01_clasificacion_subgrupo c WHERE c.cod_tipo=a.cod_tipo AND c.cod_grupo=a.cod_grupo AND c.cod_subgrupo=a.cod_subgrupo) AS deno_subgrupo,
							(SELECT d.denominacion FROM cimd01_clasificacion_seccion d WHERE d.cod_tipo=a.cod_tipo AND d.cod_grupo=a.cod_grupo AND d.cod_subgrupo=a.cod_subgrupo AND d.cod_seccion=a.cod_seccion) AS deno_seccion,
							SUM(a.cantidad * a.valor_unitario) AS monto FROM cimd03_inventario_muebles a WHERE ".$cond_consolidacion." AND a.cod_tipo='2' GROUP BY ".$group_and_order." a.cod_tipo, a.cod_grupo, a.cod_subgrupo, a.cod_seccion ORDER BY ".$group_and_order." a.cod_tipo, a.cod_grupo, a.cod_subgrupo, a.cod_seccion;";

			$datos_tipo     = $this->cimd03_inventario_muebles->execute($sql_tipo);
			$datos_grupo    = $this->cimd03_inventario_muebles->execute($sql_grupo);
			$datos_subgrupo = $this->cimd03_inventario_muebles->execute($sql_subgrupo);
			$datos_seccion  = $this->cimd03_inventario_muebles->execute($sql_seccion);

			$vector_grupo = array();
			$vector_subgrupo = array();
			for($i=0; $i<count($datos_grupo); $i++){
				$vector_grupo[$datos_grupo[$i][0]['cod_grupo']] = $datos_grupo[$i][0]['monto'].",";
			}
			for($i=0; $i<count($datos_subgrupo); $i++){
				$vector_subgrupo[$datos_subgrupo[$i][0]['cod_grupo']][$datos_subgrupo[$i][0]['cod_subgrupo']] = $datos_subgrupo[$i][0]['monto'].",";
			}

			$this->set('consolidadop',$consolidacion);
			$this->set('dtipo',$datos_tipo);
			$this->set('grupo',$vector_grupo);
			$this->set('subgrupo',$vector_subgrupo);
			$this->set('seccion',$datos_seccion);
			$this->set('var',$var);
		}
 	}
 }//relacion_consolidada_bienes_muebles


function relacion_consolidada_bienes_inmuebles($var=null){
	if($var!=null){
		if($var=='no'){
			$this->layout='ajax';
			$ano = $this->ano_ejecucion();
			if(isset($ano) && !empty($ano)){
				$ano_inicial = $ano - 20;
				$ano_final = $ano + 20;
				for($i=$ano_inicial; $i<=$ano_final; $i++){
					$array_ano[$i]=$i;
				}
			}else{
				$ano_inicial = 2003;
				$ano_final = 2050;
				for($i=$ano_inicial; $i<=$ano_final; $i++){
					$array_ano[$i]=$i;
				}
			}
			$this->set('ano',$ano);
			$this->set('array_ano',$array_ano);
			$this->set('var',$var);

		}elseif($var=='si'){
			$this->layout='pdf';
			$cp = $this->Session->read('SScodpresi');
			$ce = $this->Session->read('SScodentidad');
			$cti = $this->Session->read('SScodtipoinst');
			$ci = $this->Session->read('SScodinst');
			$cd = $this->Session->read('SScoddep');

			$cd == 1 ? $consolidacion = $this->data['movimiento_mueble']['consolidacion'] : $consolidacion = 2;
			$ano = $this->data['movimiento_mueble']['ano'];
			$cond_consolidacion = $this->SQLCA_consolidado($consolidacion, "a");

			if($cd==1){
				$group_and_order = "a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, ";
			}else{
				$group_and_order = "a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, ";

			}

			$sql_grupo = "SELECT ".$group_and_order." a.cod_tipo, a.cod_grupo, SUM(a.avaluo_actual) AS monto FROM cimd03_inventario_inmuebles a WHERE ".$cond_consolidacion." AND a.cod_tipo='1' GROUP BY ".$group_and_order." a.cod_tipo, a.cod_grupo ORDER BY ".$group_and_order." a.cod_tipo, a.cod_grupo;";
			$sql_subgrupo = "SELECT ".$group_and_order." a.cod_tipo, a.cod_grupo, a.cod_subgrupo, SUM(a.avaluo_actual) AS monto FROM cimd03_inventario_inmuebles a WHERE ".$cond_consolidacion." AND a.cod_tipo='1' GROUP BY ".$group_and_order." a.cod_tipo, a.cod_grupo, a.cod_subgrupo ORDER BY ".$group_and_order." a.cod_tipo, a.cod_grupo, a.cod_subgrupo;";
			$sql_seccion = "SELECT ".$group_and_order." a.cod_tipo, a.cod_grupo, a.cod_subgrupo, a.cod_seccion,
							(SELECT b.denominacion FROM cimd01_clasificacion_grupo b WHERE b.cod_tipo=a.cod_tipo AND b.cod_grupo=a.cod_grupo) AS deno_grupo,
							(SELECT c.denominacion FROM cimd01_clasificacion_subgrupo c WHERE c.cod_tipo=a.cod_tipo AND c.cod_grupo=a.cod_grupo AND c.cod_subgrupo=a.cod_subgrupo) AS deno_subgrupo,
							(SELECT d.denominacion FROM cimd01_clasificacion_seccion d WHERE d.cod_tipo=a.cod_tipo AND d.cod_grupo=a.cod_grupo AND d.cod_subgrupo=a.cod_subgrupo AND d.cod_seccion=a.cod_seccion) AS deno_seccion,
							SUM(a.avaluo_actual) AS monto FROM cimd03_inventario_inmuebles a WHERE ".$cond_consolidacion." AND a.cod_tipo='1' GROUP BY ".$group_and_order." a.cod_tipo, a.cod_grupo, a.cod_subgrupo, a.cod_seccion ORDER BY ".$group_and_order." a.cod_tipo, a.cod_grupo, a.cod_subgrupo, a.cod_seccion;";

			$datos_grupo    = $this->cimd03_inventario_inmuebles->execute($sql_grupo);
			$datos_subgrupo = $this->cimd03_inventario_inmuebles->execute($sql_subgrupo);
			$datos_seccion  = $this->cimd03_inventario_inmuebles->execute($sql_seccion);

			$vector_grupo = array();
			$vector_subgrupo = array();
			for($i=0; $i<count($datos_grupo); $i++){
				$vector_grupo[$datos_grupo[$i][0]['cod_grupo']] = $datos_grupo[$i][0]['monto'].",";
			}
			for($i=0; $i<count($datos_subgrupo); $i++){
				$vector_subgrupo[$datos_subgrupo[$i][0]['cod_grupo']][$datos_subgrupo[$i][0]['cod_subgrupo']] = $datos_subgrupo[$i][0]['monto'].",";
			}

			$this->set('grupo',$vector_grupo);
			$this->set('subgrupo',$vector_subgrupo);
			$this->set('seccion',$datos_seccion);
			$this->set('var',$var);
		}
 	}
}


function resumen_cuenta_inmueble_municipio($var=null) {
	if($var == 'no'){
	$this->layout="ajax";
	$cti = $this->Session->read('SScodtipoinst');
	$ci = $this->Session->read('SScodinst');
	$SScoddep                 =       $this->Session->read('SScoddep');
    $Modulo                   =       $this->Session->read('Modulo');
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$ano = $this->ano_ejecucion();
	$this->set('ano', $ano);
	$this->Session->write('ano_reporte',$ano);

	$cont = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA()." and tipo_documento='9991'");
	if($cont == 0){
		$this->set('mensaje','Por favor, ingrese los nombres de los firmantes');
		$this->set('nombre_primera_firma','');
		$this->set('cargo_primera_firma','');
		$this->set('nombre_segunda_firma','');
		$this->set('cargo_segunda_firma','');
		$this->set('nombre_tercera_firma','N/A');
		$this->set('cargo_tercera_firma','N/A');
		$this->set('nombre_cuarta_firma','N/A');
		$this->set('cargo_cuarta_firma','N/A');
		$this->set('tipo_doc_anul', 9991);
		$firma_existe = 'no';
		echo'<script>';
       		echo" document.getElementById('plus').disabled = 'true'; ";
		echo'</script>';
	}else{
		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=9991");
		$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
		$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
		$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
		$this->set('tipo_doc_anul',9991);
		$firma_existe = 'si';
		echo'<script>';
       		echo" document.getElementById('plus').disabled = ''; ";
		echo'</script>';
	}
		$this->set('firma_existe',$firma_existe);
	}elseif($var == 'si'){
		$this->layout='pdf';
		$cod_presi                =       $this->Session->read('SScodpresi');
		$cod_entidad              =       $this->Session->read('SScodentidad');
		$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
		$cod_inst                 =       $this->Session->read('SScodinst');
		$cod_dep                  =       $this->Session->read('SScoddep');
		$this->set('entidad_federal', $this->Session->read('entidad_federal'));
		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=9991");
		$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
		$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
		$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
		$exec = "select a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep,cod_estado,deno_estado,cod_municipio,deno_municipio,sum(avaluo_actual) as monto, count(a.*) as num_bienes from v_inventario_inmuebles_todo a where cod_tipo_desincorporacion=0 and a.cod_presi = $cod_presi and a.cod_entidad = $cod_entidad and a.cod_tipo_inst = $cod_tipo_inst and a.cod_inst = $cod_inst and a.cod_dep = $cod_dep group by a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep,cod_estado,a.deno_estado,cod_municipio,a.deno_municipio order by a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep,deno_estado,deno_municipio";
		$data = $this->v_inventario_inmuebles_todo->execute($exec);
		$this->set('data',$data);
		$con = "username = '".$_SESSION['nom_usuario']."' and ".$this->SQLCA();
		$ubi = $this->Usuario->findAll($con);
		$cod_dir_superior 	= $ubi[0]['Usuario']['cod_dir_superior'];
  		$cod_coordinacion 	= $ubi[0]['Usuario']['cod_coordinacion'];
  		$cod_secretaria 	= $ubi[0]['Usuario']['cod_secretaria'];
  		$cod_direccion 		= $ubi[0]['Usuario']['cod_direccion'];
  		$cod_division 		= $ubi[0]['Usuario']['cod_division'];
  		$cod_departamento 	= $ubi[0]['Usuario']['cod_departamento'];
  		$cod_oficina 		= $ubi[0]['Usuario']['cod_oficina'];
  		$modulo		 		= $ubi[0]['Usuario']['modulo'];
		$cod_tipo_inst = $this->verifica_SS(3);
		$cod_inst = $this->verifica_SS(4);
		$cod_dep = $this->verifica_SS(5);
		if($modulo != '0'){
		$skr  = "cod_tipo_institucion = $cod_tipo_inst";
  		$skr .= " and cod_institucion = $cod_inst";
  		$skr .= " and cod_dependencia = $cod_dep";
  		$skr .= " and cod_dir_superior = $cod_dir_superior";
  		$skr .= " and cod_coordinacion = $cod_coordinacion";
  		$skr .= " and cod_secretaria = $cod_secretaria";
  		$skr .= " and cod_direccion = $cod_direccion";
  		$skr .= " and cod_division = $cod_division";
  		$skr .= " and cod_departamento = $cod_departamento";
  		$skr .= " and cod_oficina = $cod_oficina";

  		$ofi = $this->cugd02_oficina->findAll($skr);
		//$this->set('oficina',$skr);
		$this->set('oficina',$ofi[0]['cugd02_oficina']['denominacion']);
		}elseif($modulo == '0'){
			$this->set('oficina','');
		}
	}
	$this->set('var',$var);


}

function resumen_cuenta_inmueble_grupo($var=null) {
	if($var == 'no'){
	$this->layout="ajax";
	$SScoddep                 =       $this->Session->read('SScoddep');
    $Modulo                   =       $this->Session->read('Modulo');
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$ano = $this->ano_ejecucion();
	$this->set('ano', $ano);
	$this->Session->write('ano_reporte',$ano);
	$cont = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA()." and tipo_documento='9992'");
	if($cont == 0){
		$this->set('mensaje','Por favor, ingrese los nombres de los firmantes');
		$this->set('nombre_primera_firma','');
		$this->set('cargo_primera_firma','');
		$this->set('nombre_segunda_firma','');
		$this->set('cargo_segunda_firma','');
		$this->set('nombre_tercera_firma','N/A');
		$this->set('cargo_tercera_firma','N/A');
		$this->set('nombre_cuarta_firma','N/A');
		$this->set('cargo_cuarta_firma','N/A');
		$this->set('tipo_doc_anul', 9992);
		$firma_existe = 'no';
		echo'<script>';
       		echo" document.getElementById('plus').disabled = 'true'; ";
		echo'</script>';
	}else{
		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=9992");
		$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
		$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
		$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
		$this->set('tipo_doc_anul',9992);
		$firma_existe = 'si';
		echo'<script>';
       		echo" document.getElementById('plus').disabled = ''; ";
		echo'</script>';
	}
		$this->set('firma_existe',$firma_existe);
	}elseif($var == 'si'){
		$this->layout='pdf';
		$cod_presi                =       $this->Session->read('SScodpresi');
		$cod_entidad              =       $this->Session->read('SScodentidad');
		$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
		$cod_inst                 =       $this->Session->read('SScodinst');
		$cod_dep                  =       $this->Session->read('SScoddep');
		$this->set('entidad_federal', $this->Session->read('entidad_federal'));
		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=9992");
		$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
		$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
		$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
		$exec = "select
					a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, cod_tipo, cod_grupo, deno_grupo, sum(avaluo_actual) as monto, count(a.*) as num_bienes from v_inventario_inmuebles_todo a where cod_tipo_desincorporacion=0 and  a.cod_presi = $cod_presi and a.cod_entidad = $cod_entidad and a.cod_tipo_inst = $cod_tipo_inst and a.cod_inst = $cod_inst and a.cod_dep = $cod_dep
				group by
					a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, cod_tipo, cod_grupo, deno_grupo
				order by
					a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, cod_tipo, cod_grupo, deno_grupo";
		$data = $this->v_inventario_inmuebles_todo->execute($exec);
		$this->set('data',$data);
		//pr($data);
		$con = "username = '".$_SESSION['nom_usuario']."' and ".$this->SQLCA();
		$ubi = $this->Usuario->findAll($con);
		$cod_dir_superior 	= $ubi[0]['Usuario']['cod_dir_superior'];
  		$cod_coordinacion 	= $ubi[0]['Usuario']['cod_coordinacion'];
  		$cod_secretaria 	= $ubi[0]['Usuario']['cod_secretaria'];
  		$cod_direccion 		= $ubi[0]['Usuario']['cod_direccion'];
  		$cod_division 		= $ubi[0]['Usuario']['cod_division'];
  		$cod_departamento 	= $ubi[0]['Usuario']['cod_departamento'];
  		$cod_oficina 		= $ubi[0]['Usuario']['cod_oficina'];
  		$modulo		 		= $ubi[0]['Usuario']['modulo'];
		$cod_tipo_inst = $this->verifica_SS(3);
		$cod_inst = $this->verifica_SS(4);
		$cod_dep = $this->verifica_SS(5);
		if($modulo != '0'){
		$skr  = "cod_tipo_institucion = $cod_tipo_inst";
  		$skr .= " and cod_institucion = $cod_inst";
  		$skr .= " and cod_dependencia = $cod_dep";
  		$skr .= " and cod_dir_superior = $cod_dir_superior";
  		$skr .= " and cod_coordinacion = $cod_coordinacion";
  		$skr .= " and cod_secretaria = $cod_secretaria";
  		$skr .= " and cod_direccion = $cod_direccion";
  		$skr .= " and cod_division = $cod_division";
  		$skr .= " and cod_departamento = $cod_departamento";
  		$skr .= " and cod_oficina = $cod_oficina";

  		$ofi = $this->cugd02_oficina->findAll($skr);
		//$this->set('oficina',$con);
		$this->set('oficina',$ofi[0]['cugd02_oficina']['denominacion']);
		}elseif($modulo == '0'){
			$this->set('oficina','');
		}
	}
	$this->set('var',$var);


}


function acta_desincorporacion_bm($var=null,$numero_identificacion=null,$cod_tipo=null,$cod_grupo=null,$cod_subgrupo=null,$cod_seccion=null) {
	if($var == 'si'){
		$this->layout='pdf';
		$cod_presi                =       $this->Session->read('SScodpresi');
		$cod_entidad              =       $this->Session->read('SScodentidad');
		$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
		$cod_inst                 =       $this->Session->read('SScodinst');
		$cod_dep                  =       $this->Session->read('SScoddep');
		$this->set('entidad_federal', $this->Session->read('entidad_federal'));
		$execu = "select a.fecha_desincorporacion, a.ano_acta, a.numero_acta, a.funcionario_primero, a.cedula_primero, a.cargo_primero, a.funcionario_segundo, a.cedula_segundo, a.cargo_segundo, a.funcionario_tercero, a.cedula_tercero, a.cargo_tercero, a.funcionario_cuarto, a.cedula_cuarto, a.cargo_cuarto, a.observaciones_desincorporacion, a.numero_identificacion, a.denominacion, a.valor_unitario, a.cod_tipo_desincorporacion, a.deno_desincorporacion, a.cod_dependencia, a.deno_dependencia, a.cod_secretaria, a.deno_secretaria, a.cod_direccion, a.deno_direccion, a.cod_oficina, a.deno_oficina, a.fecha_proceso_desincorporacion from v_inventario_muebles_todo a where a.cod_presi = $cod_presi and a.cod_entidad = $cod_entidad and a.cod_tipo_inst = $cod_tipo_inst and a.cod_inst = $cod_inst and a.cod_dep = $cod_dep and numero_identificacion=$numero_identificacion and cod_tipo_desincorporacion!=0 and cod_tipo=$cod_tipo and cod_grupo=$cod_grupo and cod_subgrupo=$cod_subgrupo and cod_seccion=$cod_seccion";
		$data_acta = $this->v_inventario_muebles_todo->execute($execu);


		$this->set('data_acta',$data_acta);
	}
	$this->set('var',$var);
}


function inventario_inmueble_bi1($var=null) {
	if($var == 'no'){
	$this->layout="ajax";
	$SScoddep                 =       $this->Session->read('SScoddep');
    $Modulo                   =       $this->Session->read('Modulo');
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$ano = $this->ano_ejecucion();
	$this->set('ano', $ano);
	$this->Session->write('ano_reporte',$ano);
	$cont = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA()." and tipo_documento='9993'");
	if($cont == 0){
		$this->set('mensaje','Por favor, ingrese los nombres de los firmantes');
		$this->set('nombre_primera_firma','');
		$this->set('cargo_primera_firma','');
		$this->set('nombre_segunda_firma','');
		$this->set('cargo_segunda_firma','');
		$this->set('nombre_tercera_firma','N/A');
		$this->set('cargo_tercera_firma','N/A');
		$this->set('nombre_cuarta_firma','N/A');
		$this->set('cargo_cuarta_firma','N/A');
		$this->set('tipo_doc_anul', 9993);
		$firma_existe = 'no';
		echo'<script>';
       		echo" document.getElementById('plus').disabled = 'true'; ";
		echo'</script>';
	}else{
		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=9993");
		$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
		$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
		$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
		$this->set('tipo_doc_anul',9993);
		$firma_existe = 'si';
		echo'<script>';
       		echo" document.getElementById('plus').disabled = ''; ";
		echo'</script>';
	}
		$this->set('firma_existe',$firma_existe);
		}elseif($var == 'si'){
		$this->layout='pdf';
		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=9993");
		$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
		$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
		$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
		$data = $this->v_inventario_inmuebles_todo->findAll($this->SQLCA().' and cod_tipo_desincorporacion=0');
		$this->set('data',$data);
		// $datagrupo = $this->v_inventario_inmuebles_todo->findAll('cod_tipo_desincorporacion=0 and '.$this->SQLCA().' group by cod_tipo,cod_grupo,numero_identificacion,cod_estado,cod_municipio,cod_parroquia,cod_centro,cod_vialidad,deno_estado,deno_municipio,deno_parroquia,deno_centro,deno_calle','cod_estado,cod_municipio,cod_parroquia,cod_centro,cod_vialidad,deno_estado,deno_municipio,deno_parroquia,deno_centro,deno_calle','cod_tipo,cod_grupo,numero_identificacion,deno_estado,deno_municipio,deno_parroquia,deno_centro,deno_calle ASC');
		// $this->set('datagrupo',$datagrupo);
	}
		$this->set('var',$var);
}


 function inventario_inmueble_bi2($var=null){
 	if($var!=null){
		if($var=='no'){
			$this->layout='ajax';
			$ano = $this->ano_ejecucion();
			if(isset($ano) && !empty($ano)){
				$ano_inicial = $ano - 20;
				$ano_final = $ano + 20;
				for($i=$ano_inicial; $i<=$ano_final; $i++){
					$array_ano[$i]=$i;
				}
			}else{
				$ano=date('Y');
				$ano_inicial = $ano - 20;
				$ano_final = $ano + 20;
				for($i=$ano_inicial; $i<=$ano_final; $i++){
					$array_ano[$i]=$i;
				}
			}

			$this->set('ano',$ano);
			$this->set('array_ano',$array_ano);
			$this->set('var',$var);

	$cont = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA()." and tipo_documento='9994'");
	if($cont == 0){
		$this->set('mensaje','Por favor, ingrese los nombres de los firmantes');
		$this->set('nombre_primera_firma','');
		$this->set('cargo_primera_firma','');
		$this->set('nombre_segunda_firma','');
		$this->set('cargo_segunda_firma','');
		$this->set('nombre_tercera_firma','N/A');
		$this->set('cargo_tercera_firma','N/A');
		$this->set('nombre_cuarta_firma','N/A');
		$this->set('cargo_cuarta_firma','N/A');
		$this->set('tipo_doc_anul', 9994);
		$firma_existe = 'no';
		echo'<script>';
       		echo" document.getElementById('plus').disabled = 'true'; ";
		echo'</script>';
	}else{
		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=9994");
		$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
		$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
		$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
		$this->set('tipo_doc_anul',9994);
		$firma_existe = 'si';
		echo'<script>';
       		echo" document.getElementById('plus').disabled = ''; ";
		echo'</script>';
	}
		$this->set('firma_existe',$firma_existe);

		}elseif($var=='si'){
			$this->layout='pdf';
			$cp = $this->Session->read('SScodpresi');
			$ce = $this->Session->read('SScodentidad');
			$cti = $this->Session->read('SScodtipoinst');
			$ci = $this->Session->read('SScodinst');
			$cd = $this->Session->read('SScoddep');
			$condicion = "";

		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=9994");
		if(!empty($firmantes)){
			$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
			$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
			$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
			$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
			$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
			$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
			$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
			$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
		}else{
			$this->set('nombre_primera_firma','');
			$this->set('cargo_primera_firma','');
			$this->set('nombre_segunda_firma','');
			$this->set('cargo_segunda_firma','');
			$this->set('nombre_tercera_firma','');
			$this->set('cargo_tercera_firma','');
			$this->set('nombre_cuarta_firma','');
			$this->set('cargo_cuarta_firma','');
		}


		if($cd==1){
			$consolidacion=$this->data['movimiento_mueble']['consolidacion'];
			if($consolidacion==1){
				$condicion .= "cod_presi='$cp' and cod_entidad='$ce' and cod_tipo_inst='$cti' and cod_inst='$ci'";
			}elseif($consolidacion==2){
				$cod_dependencia = $this->cod_dep_consolidado();
				$condicion .= "cod_presi='$cp' and cod_entidad='$ce' and cod_tipo_inst='$cti' and cod_inst='$ci' and cod_dep='$cod_dependencia'";
			}
		}else{
				$condicion .= "cod_presi='$cp' and cod_entidad='$ce' and cod_tipo_inst='$cti' and cod_inst='$ci' and cod_dep='$cd'";
		}

		$ano = (int) $this->data['movimiento_mueble']['ano'];
		$mes = (int) $this->zero($this->data['movimiento_mueble']['selectmes']);

		if($mes==1){
			$ano_m1 = $ano-1;
			$mes_m1 = 12;
		}else{
			$ano_m1 = $ano;
			$mes_m1 = $mes-1;
		}

		$data_m1 = $this->v_movimiento_inmuebles->execute("SELECT SUM(monto) FROM v_movimiento_inmuebles WHERE ".$condicion." and ano::integer <= $ano_m1 and mes::integer <= $mes_m1");
		$data = $this->v_movimiento_inmuebles->findAll($condicion." and ano::integer = $ano and mes::integer = $mes",null,'cod_grupo,cod_subgrupo,fecha,tipo ASC');
		$this->set('data_m1',$data_m1);
		$this->set('data',$data);
		$datagrupo = $this->v_movimiento_inmuebles->findAll($condicion." and ano::integer = $ano and mes::integer = $mes".' group by ano,mes,cod_estado,cod_municipio,deno_estado,deno_municipio','ano,mes,cod_estado,cod_municipio,deno_estado,deno_municipio','ano,mes,deno_estado,deno_municipio ASC');
		$this->set('datagrupo',$datagrupo);
		$this->set('ano',$ano);
		$this->set('mes',isset($mes)?$mes:0);
		$this->set('var',$var);

		}
 	}
 }


function inventario_inmueble_bi2_ant($var=null) {
	if($var == 'no'){
	$this->layout="ajax";
	$SScoddep                 =       $this->Session->read('SScoddep');
    $Modulo                   =       $this->Session->read('Modulo');


	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$ano = $this->ano_ejecucion();
	$this->set('ano', $ano);
	$this->Session->write('ano_reporte',$ano);
	$cont = $this->cugd07_firmas_oficio_anulacion->findCount($this->SQLCA()." and tipo_documento='9994'");
	if($cont == 0){
		$this->set('mensaje','Por favor, ingrese los nombres de los firmantes');
		$this->set('nombre_primera_firma','');
		$this->set('cargo_primera_firma','');
		$this->set('nombre_segunda_firma','');
		$this->set('cargo_segunda_firma','');
		$this->set('nombre_tercera_firma','N/A');
		$this->set('cargo_tercera_firma','N/A');
		$this->set('nombre_cuarta_firma','N/A');
		$this->set('cargo_cuarta_firma','N/A');
		$this->set('tipo_doc_anul', 9994);
		$firma_existe = 'no';
		echo'<script>';
       		echo" document.getElementById('plus').disabled = 'true'; ";
		echo'</script>';

	}else{
		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=9994");
		$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
		$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
		$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
		$this->set('tipo_doc_anul',9994);
		$firma_existe = 'si';
		echo'<script>';
       		echo" document.getElementById('plus').disabled = ''; ";
		echo'</script>';
	}
		$this->set('firma_existe',$firma_existe);
		}elseif($var == 'si'){
		$this->layout='pdf';
		$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=9994");
		$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
		$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
		$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
		$data = $this->v_movimiento_inmuebles->findAll($this->SQLCA(),null,'cod_grupo,cod_subgrupo,fecha,tipo ASC');
		$this->set('data',$data);
		$datagrupo = $this->v_movimiento_inmuebles->findAll($this->SQLCA().' group by ano,mes,cod_estado,cod_municipio,deno_estado,deno_municipio','ano,mes,cod_estado,cod_municipio,deno_estado,deno_municipio','ano,mes,deno_estado,deno_municipio ASC');
		$this->set('datagrupo',$datagrupo);
		//pr($datagrupo);
	}
		$this->set('var',$var);


}


function firmas_balance_ejecucion_mes(){
	$this->layout="ajax";

	$cp  = $this->Session->read('SScodpresi');
	$ce  = $this->Session->read('SScodentidad');
	$cti = $this->Session->read('SScodtipoinst');
	$ci  = $this->Session->read('SScodinst');
	$cd  = $this->Session->read('SScoddep');

	$tipo_doc_anul = $this->data['cugp03_acta_anulacion']['tipo_doc_anul'];
	$nombre_primera_firma = $this->data['cugp03_acta_anulacion']['nombre_primera_firma'];
	$cargo_primera_firma  = $this->data['cugp03_acta_anulacion']['cargo_primera_firma'];
	$nombre_segunda_firma = $this->data['cugp03_acta_anulacion']['nombre_segunda_firma'];
	$cargo_segunda_firma  = $this->data['cugp03_acta_anulacion']['cargo_segunda_firma'];
	$nombre_tercera_firma = $this->data['cugp03_acta_anulacion']['nombre_tercera_firma'];
	$cargo_tercera_firma  = $this->data['cugp03_acta_anulacion']['cargo_tercera_firma'];
	$nombre_cuarta_firma = $this->data['cugp03_acta_anulacion']['nombre_cuarta_firma'];
	$cargo_cuarta_firma  = $this->data['cugp03_acta_anulacion']['cargo_cuarta_firma'];

	$primera_cc = isset($this->data['cugp03_acta_anulacion']['primera_copia'])?$this->data['cugp03_acta_anulacion']['primera_copia']:'N/A';
	$segunda_cc = isset($this->data['cugp03_acta_anulacion']['segunda_copia'])?$this->data['cugp03_acta_anulacion']['segunda_copia']:'N/A';
	$tercera_cc = isset($this->data['cugp03_acta_anulacion']['tercera_copia'])?$this->data['cugp03_acta_anulacion']['tercera_copia']:'N/A';
	$cuarta_cc  = isset($this->data['cugp03_acta_anulacion']['cuarta_copia'])?$this->data['cugp03_acta_anulacion']['cuarta_copia']:'N/A';
	$quinta_cc  = isset($this->data['cugp03_acta_anulacion']['quinta_copia'])?$this->data['cugp03_acta_anulacion']['quinta_copia']:'N/A';
	$sexta_cc   = isset($this->data['cugp03_acta_anulacion']['sexta_copia'])?$this->data['cugp03_acta_anulacion']['sexta_copia']:'N/A';
	$septima_cc = isset($this->data['cugp03_acta_anulacion']['septima_copia'])?$this->data['cugp03_acta_anulacion']['septima_copia']:'N/A';
	$octava_cc  = isset($this->data['cugp03_acta_anulacion']['octava_copia'])?$this->data['cugp03_acta_anulacion']['octava_copia']:'N/A';

	$insert = "INSERT INTO cugd07_firmas_oficio_anulacion VALUES ($cp, $ce, $cti, $ci, $cd, $tipo_doc_anul,'$nombre_primera_firma', '$cargo_primera_firma', '$nombre_segunda_firma', '$cargo_segunda_firma', '$nombre_tercera_firma', '$cargo_tercera_firma', '$primera_cc', '$segunda_cc', '$tercera_cc', '$cuarta_cc', '$quinta_cc', '$sexta_cc', '$septima_cc', '$octava_cc', '$nombre_cuarta_firma', '$cargo_cuarta_firma')";
	$this->cugd07_firmas_oficio_anulacion->execute($insert);
	echo '<script>';
       		echo "if(document.getElementById('plus')){document.getElementById('plus').disabled = '';}";
       		echo "if(document.getElementById('enviar')){document.getElementById('enviar').disabled = '';}";
	echo '</script>';

	$this->set('mensaje','Las firmas fuer&oacute;n registradas correctamente');
	$this->firmantes_acta_anulacion($tipo_doc_anul);
	$this->render('firmantes_acta_anulacion');
}

function modificar_firmas_balance_mes(){
	$this->layout="ajax";

	$tipo_doc_anul = $this->data['cugp03_acta_anulacion']['tipo_doc_anul'];
	$nombre_primera_firma = $this->data['cugp03_acta_anulacion']['nombre_primera_firma'];
	$cargo_primera_firma  = $this->data['cugp03_acta_anulacion']['cargo_primera_firma'];
	$nombre_segunda_firma = $this->data['cugp03_acta_anulacion']['nombre_segunda_firma'];
	$cargo_segunda_firma  = $this->data['cugp03_acta_anulacion']['cargo_segunda_firma'];
	$nombre_tercera_firma = $this->data['cugp03_acta_anulacion']['nombre_tercera_firma'];
	$cargo_tercera_firma  = $this->data['cugp03_acta_anulacion']['cargo_tercera_firma'];
	$nombre_cuarta_firma = $this->data['cugp03_acta_anulacion']['nombre_cuarta_firma'];
	$cargo_cuarta_firma  = $this->data['cugp03_acta_anulacion']['cargo_cuarta_firma'];

	$primera_cc = isset($this->data['cugp03_acta_anulacion']['primera_copia'])?$this->data['cugp03_acta_anulacion']['primera_copia']:'A';
	$segunda_cc = isset($this->data['cugp03_acta_anulacion']['segunda_copia'])?$this->data['cugp03_acta_anulacion']['segunda_copia']:'A';
	$tercera_cc = isset($this->data['cugp03_acta_anulacion']['tercera_copia'])?$this->data['cugp03_acta_anulacion']['tercera_copia']:'A';
	$cuarta_cc  = isset($this->data['cugp03_acta_anulacion']['cuarta_copia'])?$this->data['cugp03_acta_anulacion']['cuarta_copia']:'A';
	$quinta_cc  = isset($this->data['cugp03_acta_anulacion']['quinta_copia'])?$this->data['cugp03_acta_anulacion']['quinta_copia']:'A';
	$sexta_cc   = isset($this->data['cugp03_acta_anulacion']['sexta_copia'])?$this->data['cugp03_acta_anulacion']['sexta_copia']:'A';
	$septima_cc = isset($this->data['cugp03_acta_anulacion']['septima_copia'])?$this->data['cugp03_acta_anulacion']['septima_copia']:'A';
	$octava_cc  = isset($this->data['cugp03_acta_anulacion']['octava_copia'])?$this->data['cugp03_acta_anulacion']['octava_copia']:'A';

	$update = "UPDATE cugd07_firmas_oficio_anulacion SET nombre_primera_firma='$nombre_primera_firma', cargo_primera_firma='$cargo_primera_firma', nombre_segunda_firma='$nombre_segunda_firma', cargo_segunda_firma='$cargo_segunda_firma', nombre_tercera_firma='$nombre_tercera_firma', cargo_tercera_firma='$cargo_tercera_firma', primera_copia='$primera_cc', segunda_copia='$segunda_cc', tercera_copia='$tercera_cc', cuarta_copia='$cuarta_cc', quinta_copia='$quinta_cc', sexta_copia='$sexta_cc', septima_copia='$septima_cc', octava_copia='$octava_cc', nombre_cuarta_firma='$nombre_cuarta_firma', cargo_cuarta_firma='$cargo_cuarta_firma' WHERE ".$this->SQLCA()." and tipo_documento=".$tipo_doc_anul;
	$this->cugd07_firmas_oficio_anulacion->execute($update);

	$this->set('mensaje','Las firmas fuer&oacute;n modificadas correctamente');
	$this->firmantes_acta_anulacion($tipo_doc_anul);
	$this->render('firmantes_acta_anulacion');
}

function firmantes_acta_anulacion($var=null){
	$this->layout="ajax";

	$cp  = $this->Session->read('SScodpresi');
	$ce  = $this->Session->read('SScodentidad');
	$cti = $this->Session->read('SScodtipoinst');
	$ci  = $this->Session->read('SScodinst');
	$cd  = $this->Session->read('SScoddep');
	$this->set('tipo_doc_anul',$var);
  switch($var){
            case '9991':
				$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=9991");
            	break;
            case '9992':
				$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=9992");
				break;
			case '9993':
				$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=9993");
            	break;
            case '9994':
				$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=9994");
            	break;
            case '9995':
				$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=9995");
            	break;
            case '9996':
				$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=9996");
            	break;
            case '9998':
				$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=9998");
            	break;
            case '9999':
				$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=9999");
            	break;
            case '10000':
				$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=10000");
            	break;
            case '10001':
				$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=10001");
            	break;
            case '10002':
				$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=10002");
            	break;
            case '10003':
				$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=10003");
            	break;
            case '10004':
				$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=10004");
            	break;
            case '10005':
				$firmantes= $this->cugd07_firmas_oficio_anulacion->findAll($this->SQLCA()." and tipo_documento=10005");
            	break;
			 default: echo "Lo siento no contamos con informacion para este registro"; break;
	}//Fin switch

	if($firmantes!=null){
		$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
		$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
		$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
		$this->set('firma_existe','si');
	}else{
		$this->set('mensaje','Por favor, ingrese los nombres firmantes');
		$this->set('nombre_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_tercera_firma']);
		$this->set('nombre_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['nombre_cuarta_firma']);
		$this->set('cargo_cuarta_firma',$firmantes[0]['cugd07_firmas_oficio_anulacion']['cargo_cuarta_firma']);
		$this->set('firma_existe','no');
	}

}//firmantes_acta_anulacion

public function ficha_bienes_muebles_pdf($cod_tipo = null, $cod_grupo = null, $cod_subgrupo = null, $cod_seccion = null, $num_ide = null){
	$this->layout='pdf';
	$cd  = $this->Session->read('SScoddep');
	$data = $this->v_inventario_muebles_todo->findAll(
										$this->SQLCA().' and cod_tipo='.$cod_tipo.' and cod_grupo='.$cod_grupo.
										' and cod_subgrupo='.$cod_subgrupo.' and cod_seccion='.$cod_seccion.
										' and numero_identificacion='.$num_ide);
	
	if($data[0]['v_inventario_muebles_todo']['cod_tipo_incorporacion'] == 3){

 		$sql = "SELECT b.denominacion 
 				FROM 
 					cscd04_ordencompra_encabezado AS a 
 				INNER join
 					cpcd02 AS b ON
 					a.rif = b.rif 
 				WHERE 
	 				a.cod_dep = $cd AND
	 				a.numero_orden_compra = ".$data[0]['v_inventario_muebles_todo']['numero_orden_compra']." AND
	 				a.ano_orden_compra = ".$data[0]['v_inventario_muebles_todo']['ano_orden_compra'];

	 	$proveedor = $this->v_inventario_muebles_todo->execute($sql);
	 	
	 	$this->set('proveedor', $proveedor[0][0]['denominacion']);
 	}

	$this->set('data', $data);
}

public function ficha_bienes_inmuebles_pdf($num_ide=null){
	$this->layout='pdf';
	$data = $this->v_inventario_inmuebles_todo->findAll(
										$this->SQLCA().' and numero_identificacion='.$num_ide);

	if($data[0]['v_inventario_inmuebles_todo']['cod_tipo_incorporacion'] == 3){

 		$sql = "SELECT b.denominacion 
 				FROM 
 					cscd04_ordencompra_encabezado AS a 
 				INNER join
 					cpcd02 AS b ON
 					a.rif = b.rif 
 				WHERE 
	 				a.cod_dep = $cd AND
	 				a.numero_orden_compra = ".$data[0]['v_inventario_inmuebles_todo']['numero_orden_compra']." AND
	 				a.ano_orden_compra = ".$data[0]['v_inventario_inmuebles_todo']['ano_orden_compra'];

	 	$proveedor = $this->v_inventario_muebles_todo->execute($sql);
	 	
	 	$this->set('proveedor', $proveedor[0][0]['denominacion']);
 	}

	$this->set('data', $data);
}

}
?>
