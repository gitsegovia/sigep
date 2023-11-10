<?php
/*
 * Created on 05/01/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class ReporteBienesAdaptadoController extends AppController{
	var $name = "reporte_bienes_adaptado";
	var $uses = array('ccfd04_cierre_mes','cimd03_inventario_muebles','cimd03_inventario_inmuebles','cimd04_vehiculo_asegurado','cugd01_estados','cugd01_municipios','cugd01_parroquias','cugd01_centropoblados',
						'cugd02_institucion','cugd02_dependencia','cugd02_direccionsuperior','cugd02_coordinacion','cugd02_secretaria','cugd02_direccion','cugd02_division','cugd02_departamento','cugd02_oficina',
						'cugd02_direccionsuperior','v_inventario_muebles_todo','v_cimd01_escalera_codigos_bienes','cimd02_tipo_movimiento');
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

 function sql_codigo_mueble($var=null){
 	$sql_codigo = '';
 	if($var!=null){
 		$codigo=split('-', $var);
		$sql_codigo="cod_tipo=".$codigo[0]." AND cod_grupo=".$codigo[1]." AND cod_subgrupo=".$codigo[2]." AND cod_seccion=".$codigo[3]." AND numero_identificacion=".$codigo[4];
		return $sql_codigo;
 	}else{
		return false;
 	}
 }



 function cimp01_inventario_muebles_bienes($ir=null,$var=null){
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

	}else if($ir=='vista_2'){
		if($var==2){
			$this->set('ir','ubicacion');
		}else{
			$this->set('ir','ubicaciones');
		}
	}
 }//cimp01_inventario_muebles_bienes



 function cimp01_inventario_muebles_bienes_pdf(){
	$this->layout = "pdf";
	$cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	//$ano = $this->ano_ejecucion();

		if($this->data['cimp01']['radio_ver']!='' && $this->data['cimp01']['todos_ver']!=''){
			$inst_dep=$this->data['cimp01']['inst_dep'];
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

				/////////////////////////////////////////////////

				if($radio_ver==2){
					if($this->data['cimp01']['cod_mueble']!=''){
						$mueble=$this->data['cimp01']['cod_mueble'];
						$cond.=" and ".$this->sql_codigo_mueble($mueble);
					}else{
						echo'<script>history.back(1);</script>';
					}
				}

				/////////////////////////////////////////////////

				if($this->data['cimp01']['todos_ver']!=''){
					$todos_ver=$this->data['cimp01']['todos_ver'];
					if($todos_ver==2){
							/////////////aqui son todos los muebles de una ubicacion en particular/////////////////
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
	$order="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_republica,cod_estado,cod_municipio,conocido,cod_parroquia,cod_centro,cod_institucion,cod_dependencia,cod_dir_superior,cod_coordinacion,cod_secretaria,cod_direccion,cod_division,cod_departamento,cod_oficina";
	$order1="cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_republica,cod_estado,cod_municipio,conocido,cod_parroquia,cod_centro,cod_institucion,cod_dependencia,cod_dir_superior,cod_coordinacion,cod_secretaria,cod_direccion,cod_division,cod_departamento,cod_oficina,cod_tipo,cod_grupo,cod_subgrupo,cod_seccion,cantidad,numero_identificacion";
	$sql1="SELECT ".$group." FROM v_inventario_muebles_todo where ".$cond." and cod_tipo_desincorporacion=0 group by ".$group." order by ".$order1." asc";
	$datos2=$this->v_inventario_muebles_todo->execute($sql1);
	$this->set('datos',$datos2);
	$this->data['cimp01']['inst_dep'];
	$this->data['cimp01']['inst_dep'];
}//cimp01_inventario_muebles_bienes_pdf



 function reporte_movimiento_bienes_muebles($var=null){
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
			$cod_presi = $this->Session->read('SScodpresi');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$lista =  $this->cugd01_estados->generateList('cod_republica='.$cod_presi, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
			$lista_institucion =  $this->cugd02_institucion->generateList('cod_tipo_institucion='.$cod_tipo_inst, 'cod_institucion ASC', null, '{n}.cugd02_institucion.cod_institucion', '{n}.cugd02_institucion.denominacion');
			$this->concatena($lista, 'lista');
			$this->concatena($lista_institucion, 'lista_institucion');
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
			$condicion  = "";

			$cd==1 ? $consolidacion=$this->data['movimiento_mueble']['consolidacion'] : $consolidacion=2;
			if($consolidacion==1){
				$condicion .= "cod_presi='$cp' and cod_entidad='$ce' and cod_tipo_inst='$cti' and cod_inst='$ci'";
			}elseif($consolidacion==2){
				$condicion .= "cod_presi='$cp' and cod_entidad='$ce' and cod_tipo_inst='$cti' and cod_inst='$ci' and cod_dep='$cd'";
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
					$ano_final=$ano-1;
					$fecha_anterior_final = "31-12-".$ano_final;
					$fecha_actual_inicial = date("Y-m-d", mktime(0, 0, 0, $mes, 1, $ano));
					$fecha_actual_final   = date("Y-m-d", mktime(0, 0, 0, $mes+1, 0, $ano));
				}
			}

			$condicion .=" AND ((fecha_incorporacion BETWEEN '$fecha_actual_inicial' AND '$fecha_actual_final') OR (fecha_desincorporacion BETWEEN '$fecha_actual_inicial' AND '$fecha_actual_final'))";

			$select_ubicaciones = $this->data['movimiento_mueble']['select_ubicaciones'];
			if($select_ubicaciones==1){
				$sql = "SELECT cod_estado, deno_estado, cod_municipio, deno_municipio, conocido, cod_parroquia, deno_parroquia, cod_centro, deno_centro, cod_institucion, deno_institucion, cod_dependencia, deno_dependencia, cod_dir_superior, deno_dir_superior, cod_coordinacion, deno_coordinacion, cod_secretaria, deno_secretaria, cod_direccion, deno_direccion, cod_division, deno_division, cod_departamento, deno_departamento, cod_oficina, deno_oficina,
							cod_tipo, deno_tipo, cod_grupo, deno_grupo, cod_subgrupo, deno_subgrupo, cod_seccion, deno_seccion, cod_tipo_incorporacion, deno_incorporacion, cod_tipo_desincorporacion, deno_desincorporacion, cantidad, valor_unitario, numero_identificacion, denominacion FROM v_inventario_muebles_todo
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
				$sql = "SELECT cod_estado, deno_estado, cod_municipio, deno_municipio, conocido, cod_parroquia, deno_parroquia, cod_centro, deno_centro, cod_institucion, deno_institucion, cod_dependencia, deno_dependencia, cod_dir_superior, deno_dir_superior, cod_coordinacion, deno_coordinacion, cod_secretaria, deno_secretaria, cod_direccion, deno_direccion, cod_division, deno_division, cod_departamento, deno_departamento, cod_oficina, deno_oficina,
							cod_tipo, deno_tipo, cod_grupo, deno_grupo, cod_subgrupo, deno_subgrupo, cod_seccion, deno_seccion, cod_tipo_incorporacion, deno_incorporacion, cod_tipo_desincorporacion, deno_desincorporacion, cantidad, valor_unitario, numero_identificacion, denominacion FROM v_inventario_muebles_todo
						WHERE ".$condicion;
			}
			$datos = $this->v_inventario_muebles_todo->execute($sql);
			$this->set('datos',$datos);
			$this->set('fecha_inicial',$fecha_actual_inicial);
			$this->set('fecha_final',$fecha_actual_final);
			$this->set('var',$var);
		}
 	}
 }//reporte_movimiento_bienes_muebles



 function resumen_cuenta_bienes_unidad_trabajo($var=null){
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
		}elseif($var=='si'){
			$this->layout='pdf';
			$cp = $this->Session->read('SScodpresi');
			$ce = $this->Session->read('SScodentidad');
			$cti = $this->Session->read('SScodtipoinst');
			$ci = $this->Session->read('SScodinst');
			$cd = $this->Session->read('SScoddep');

			$cd == 1 ? $consolidacion = $this->data['movimiento_mueble']['consolidacion'] : $consolidacion = 2;
			$ano = $this->data['movimiento_mueble']['ano'];

			$por_ano = $this->data['movimiento_mueble']['por_ano'];
			if($por_ano==1){
				$ano_final=$ano-1;
				$fecha_anterior_final = "31-12-".$ano_final;
				$fecha_actual_inicial = date("Y-m-d", mktime(0, 0, 0, 1, 1, $ano));
				$fecha_actual_final   = date("Y-m-d", mktime(0, 0, 0, 12, 31, $ano));
				$mes_letras = "ENERO-DICIEMBRE";
			}else{
				$ano_final=$ano-1;
				$mes = $this->data['movimiento_mueble']['selectmes'];
				if($mes==''){
					echo'<script>history.back(1);</script>';
				}else{
					$fecha_anterior_final = date("Y-m-d", mktime(0, 0, 0, $mes, 0, $ano));
					$fecha_actual_inicial = date("Y-m-d", mktime(0, 0, 0, $mes, 1, $ano));
					$fecha_actual_final   = date("Y-m-d", mktime(0, 0, 0, $mes+1, 0, $ano));
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

			if($consolidacion==1){
				$cond_consolidacion = "a.cod_presi='$cp' AND a.cod_entidad='$ce' AND a.cod_tipo_inst='$cti' AND a.cod_inst='$ci'";
				$campos = 'a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, cod_republica, cod_estado, deno_estado, cod_municipio, deno_municipio, conocido, cod_parroquia, deno_parroquia, cod_centro, deno_centro, cod_institucion, deno_institucion, cod_dependencia, deno_dependencia, cod_dir_superior, deno_dir_superior, cod_coordinacion, deno_coordinacion, cod_secretaria, deno_secretaria, cod_direccion, deno_direccion, cod_division, deno_division, cod_departamento, deno_departamento, cod_oficina, deno_oficina,';
				$group_by = 'a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, cod_republica, cod_estado, deno_estado, cod_municipio, deno_municipio, conocido, cod_parroquia, deno_parroquia, cod_centro, deno_centro, cod_institucion, deno_institucion, cod_dependencia, deno_dependencia, cod_dir_superior, deno_dir_superior, cod_coordinacion, deno_coordinacion, cod_secretaria, deno_secretaria, cod_direccion, deno_direccion, cod_division, deno_division, cod_departamento, deno_departamento, cod_oficina, deno_oficina;';
				$sql_datos = "SELECT ".$campos."
				count(cantidad) as cantidad,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_municipio=a.cod_municipio AND v.cod_centro=a.cod_centro AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_direccion=a.cod_direccion AND v.cod_division=a.cod_division AND v.cod_departamento=a.cod_departamento AND v.cod_oficina=a.cod_oficina AND v.cod_tipo_incorporacion != '0' AND v.fecha_incorporacion <= '$fecha_anterior_final') as incorporacion_anterior,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_municipio=a.cod_municipio AND v.cod_centro=a.cod_centro AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_direccion=a.cod_direccion AND v.cod_division=a.cod_division AND v.cod_departamento=a.cod_departamento AND v.cod_oficina=a.cod_oficina AND v.cod_tipo_desincorporacion != '0' AND v.fecha_desincorporacion <= '$fecha_anterior_final') as desincorporacion_anterior,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_municipio=a.cod_municipio AND v.cod_centro=a.cod_centro AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_direccion=a.cod_direccion AND v.cod_division=a.cod_division AND v.cod_departamento=a.cod_departamento AND v.cod_oficina=a.cod_oficina AND v.cod_tipo_incorporacion != '0' and v.fecha_incorporacion BETWEEN '$fecha_actual_inicial' AND '$fecha_actual_final') as incorporacion_actual,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_municipio=a.cod_municipio AND v.cod_centro=a.cod_centro AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_direccion=a.cod_direccion AND v.cod_division=a.cod_division AND v.cod_departamento=a.cod_departamento AND v.cod_oficina=a.cod_oficina AND v.cod_tipo_desincorporacion != '0' AND v.cod_tipo_desincorporacion != '60' and v.fecha_desincorporacion BETWEEN '$fecha_actual_inicial' AND '$fecha_actual_final') as desincorporacion_actual_sin_60,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_municipio=a.cod_municipio AND v.cod_centro=a.cod_centro AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_direccion=a.cod_direccion AND v.cod_division=a.cod_division AND v.cod_departamento=a.cod_departamento AND v.cod_oficina=a.cod_oficina AND v.cod_tipo_desincorporacion != '0' AND v.cod_tipo_desincorporacion = '60' and v.fecha_desincorporacion BETWEEN '$fecha_actual_inicial' AND '$fecha_actual_final') as desincorporacion_actual_con_60
				FROM v_inventario_muebles_todo a
				WHERE ".$cond_consolidacion."
				GROUP BY ".$group_by;
			}else{
				$cod_dependencia = $this->cod_dep_consolidado();
				$cond_consolidacion = "a.cod_presi='$cp' AND a.cod_entidad='$ce' AND a.cod_tipo_inst='$cti' AND a.cod_inst='$ci' AND a.cod_dep='$cod_dependencia'";
				$campos = 'a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, cod_republica, cod_estado, deno_estado, cod_municipio, deno_municipio, conocido, cod_parroquia, deno_parroquia, cod_centro, deno_centro, cod_institucion, deno_institucion, cod_dependencia, deno_dependencia, cod_dir_superior, deno_dir_superior, cod_coordinacion, deno_coordinacion, cod_secretaria, deno_secretaria, cod_direccion, deno_direccion, cod_division, deno_division, cod_departamento, deno_departamento, cod_oficina, deno_oficina,';
				$group_by = 'a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, cod_republica, cod_estado, deno_estado, cod_municipio, deno_municipio, conocido, cod_parroquia, deno_parroquia, cod_centro, deno_centro, cod_institucion, deno_institucion, cod_dependencia, deno_dependencia, cod_dir_superior, deno_dir_superior, cod_coordinacion, deno_coordinacion, cod_secretaria, deno_secretaria, cod_direccion, deno_direccion, cod_division, deno_division, cod_departamento, deno_departamento, cod_oficina, deno_oficina;';
				$sql_datos = "SELECT ".$campos."
				count(cantidad) as cantidad,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_dep=a.cod_dep AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_municipio=a.cod_municipio AND v.cod_centro=a.cod_centro AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_direccion=a.cod_direccion AND v.cod_division=a.cod_division AND v.cod_departamento=a.cod_departamento AND v.cod_oficina=a.cod_oficina AND v.cod_tipo_incorporacion != '0' AND v.fecha_incorporacion <= '$fecha_anterior_final') as incorporacion_anterior,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_dep=a.cod_dep AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_municipio=a.cod_municipio AND v.cod_centro=a.cod_centro AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_direccion=a.cod_direccion AND v.cod_division=a.cod_division AND v.cod_departamento=a.cod_departamento AND v.cod_oficina=a.cod_oficina AND v.cod_tipo_desincorporacion !='0' AND v.fecha_desincorporacion <= '$fecha_anterior_final') as desincorporacion_anterior,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_dep=a.cod_dep AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_municipio=a.cod_municipio AND v.cod_centro=a.cod_centro AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_direccion=a.cod_direccion AND v.cod_division=a.cod_division AND v.cod_departamento=a.cod_departamento AND v.cod_oficina=a.cod_oficina AND v.cod_tipo_incorporacion != '0' and v.fecha_incorporacion BETWEEN '$fecha_actual_inicial' AND '$fecha_actual_final') as incorporacion_actual,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_dep=a.cod_dep AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_municipio=a.cod_municipio AND v.cod_centro=a.cod_centro AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_direccion=a.cod_direccion AND v.cod_division=a.cod_division AND v.cod_departamento=a.cod_departamento AND v.cod_oficina=a.cod_oficina AND v.cod_tipo_desincorporacion != '0' AND v.cod_tipo_desincorporacion != '60' and v.fecha_desincorporacion BETWEEN '$fecha_actual_inicial' AND '$fecha_actual_final') as desincorporacion_actual_sin_60,
				(select sum(v.cantidad * v.valor_unitario) from v_inventario_muebles_todo v where v.cod_presi=a.cod_presi AND v.cod_entidad=a.cod_entidad AND v.cod_tipo_inst=a.cod_tipo_inst AND v.cod_inst=a.cod_inst AND v.cod_dep=a.cod_dep AND v.cod_republica=a.cod_republica AND v.cod_estado=a.cod_estado AND v.cod_municipio=a.cod_municipio AND v.cod_centro=a.cod_centro AND v.cod_institucion=a.cod_institucion AND v.cod_dependencia=a.cod_dependencia AND v.cod_dir_superior=a.cod_dir_superior AND v.cod_coordinacion=a.cod_coordinacion AND v.cod_secretaria=a.cod_secretaria AND v.cod_direccion=a.cod_direccion AND v.cod_division=a.cod_division AND v.cod_departamento=a.cod_departamento AND v.cod_oficina=a.cod_oficina AND v.cod_tipo_desincorporacion != '0' AND v.cod_tipo_desincorporacion = '60' and v.fecha_desincorporacion BETWEEN '$fecha_actual_inicial' AND '$fecha_actual_final') as desincorporacion_actual_con_60
				FROM v_inventario_muebles_todo a
				WHERE ".$cond_consolidacion."
				GROUP BY ".$group_by;
			}
			$datos = $this->cimd03_inventario_muebles->execute($sql_datos);
			$this->set('datos',$datos);
			$this->set('mes_letras',$mes_letras);
			$this->set('ano',$ano);
			$this->set('var',$var);
		}
 	}
 }//resumen_cuenta_bienes_unidad_trabajo



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



 function reporte_etiquetas($var=null){
 	if($var!=null){
		if($var=='no'){
			$this->layout='ajax';
			$_SESSION['SScoddep']==1 ? $this->Session->write('consolidacion_reporte_cimd03_bienes',1) : $this->Session->write('consolidacion_reporte_cimd03_bienes',2);;
			$cod_presi = $this->Session->read('SScodpresi');
			$cod_tipo_inst = $this->Session->read('SScodtipoinst');
			$lista =  $this->cugd01_estados->generateList('cod_republica='.$cod_presi, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
			$lista_institucion =  $this->cugd02_institucion->generateList("cod_tipo_institucion=".$cod_tipo_inst, 'cod_institucion ASC', null, '{n}.cugd02_institucion.cod_institucion', '{n}.cugd02_institucion.denominacion');
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
				$condicion .= "cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst'";
			}else{
				$condicion .= "cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst' AND cod_dep='$cod_dep'";
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
 }//reporte_etiquetas_triples

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


 }//fin class
?>
