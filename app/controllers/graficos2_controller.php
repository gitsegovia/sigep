<?php
/*
 * Proyecto: SIGEP
 * Archivo: graficos2_controller.php
 * Fecha de creacion: 04/06/2008
 *
 * Creado por: Ing. Luis Alfredo Diaz Jaramillo
 * e-mail: ldiazjaramillo@gmail.com
 *
 */
 class Graficos2Controller extends AppController

{
    //var $uses = array('Usuario', 'cugd04_entrada_modulo' , 'reactualizar_solicitud');
    var $uses = array('Usuario', 'cugd04_entrada_modulo', 'cfpd07_plan_inversion', 'ccfd04_cierre_mes');
    var $helpers = array('Html', 'Javascript', 'Ajax', 'Fpdf', 'Sisap');

    //var $layout =  "index_modulos";

function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
}//fin checksession
function beforeFilter(){
					$this->checkSession();

}

function get_tipo_recurso($ano_recurso=null){
	$this->layout="ajax";
	$recursos = array('1'=>'ORDINARIO', '2'=>'COORDINADO', '3'=>'FCI', '4'=>'MPPS', '5'=>'INGRESOS EXTRAORDINARIOS', '6'=>'TODO');
	$this->set('recursos', $recursos);
	$this->set('ano_ejecucion', $ano_recurso);
    echo"<script>document.getElementById('principal_grafico_tipo_recurso').innerHTML = '';</script>";
}//fin function




function tipo_recurso(){
	$this->layout="ajax";
	$ano_ejecucion = $ano_recurso =$this->ano_ejecucion();
	$this->set('ano_ejecucion', $ano_ejecucion);
	$cod_presi = $this->Session->read('SScodpresi');
  	$cod_entidad = $this->Session->read('SScodentidad');
  	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
  	$cod_inst = $this->Session->read('SScodinst');
	$ListAnoRecurso = $this->cfpd07_plan_inversion->generateList($conditions = "cod_presi='$cod_presi' AND cod_entidad='$cod_entidad' AND cod_tipo_inst='$cod_tipo_inst' AND cod_inst='$cod_inst'", $order = 'ano_recurso', $limit = null, '{n}.cfpd07_plan_inversion.ano_recurso', '{n}.cfpd07_plan_inversion.ano_recurso');
	$this->set('ListAnoRecurso', $ListAnoRecurso);
	$recursos = array('1'=>'ORDINARIO', '2'=>'COORDINADO', '3'=>'FCI', '4'=>'MPPS', '5'=>'INGRESOS EXTRAORDINARIOS', '6'=>'TODO');
	$this->set('recursos', $recursos);

	$sql_1 = "SELECT a.ano_recurso, SUM(a.asignacion_total) as asignacion_total, SUM(a.monto_presupuestado) as monto_presupuestado, SUM((a.asignacion_total - a.monto_presupuestado)) as diferencia FROM cfpd07_plan_inversion a WHERE a.cod_presi='$cod_presi' AND a.cod_entidad='$cod_entidad' AND a.cod_tipo_inst='$cod_tipo_inst' AND a.cod_inst='$cod_inst' AND a.ano_recurso='$ano_recurso' GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano_recurso ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.ano_recurso";
	$datos_grap1 = $this->cfpd07_plan_inversion->execute($sql_1);
	$this->set('datos_grap1', $datos_grap1);

}




function tipo_recurso_proy_presu($ano_recurso=null, $tipo_recurso=null){
	$this->layout="ajax";

	$cod_presi = $this->Session->read('SScodpresi');
  	$cod_entidad = $this->Session->read('SScodentidad');
  	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
  	$cod_inst = $this->Session->read('SScodinst');
  	$username = $this->Session->read('nom_usuario');
  	$this->set('tipo_recurso', $tipo_recurso);
  	$script_borrar = "rm -f /var/www/sigep/app/tmp/*".strtoupper($username)."*.png";
  	//echo $script_borrar;
  	shell_exec($script_borrar);
  	$this->set('usr', $username);
  	//$ano_recurso = $this->data['graficos2']['ano_recurso'];
  	$this->set('ano_recurso', $ano_recurso);

  	if($tipo_recurso==6){
  		$sql_1 = "SELECT a.ano_recurso, SUM(a.asignacion_total) as asignacion_total, SUM(a.monto_presupuestado) as monto_presupuestado, SUM((a.asignacion_total - a.monto_presupuestado)) as diferencia FROM cfpd07_plan_inversion a WHERE a.cod_presi='$cod_presi' AND a.cod_entidad='$cod_entidad' AND a.cod_tipo_inst='$cod_tipo_inst' AND a.cod_inst='$cod_inst' AND a.ano_recurso='$ano_recurso' GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano_recurso ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.ano_recurso";
  		$datos_grap1 = $this->cfpd07_plan_inversion->execute($sql_1);
  	}else{
		$sql4_inversion_individual = "SELECT a.tipo_recurso, SUM(a.asignacion_total) as asignacion_total, SUM(a.monto_presupuestado) as monto_presupuestado, SUM((a.asignacion_total - a.monto_presupuestado)) as diferencia FROM cfpd07_plan_inversion a WHERE a.cod_presi='$cod_presi' AND a.cod_entidad='$cod_entidad' AND a.cod_tipo_inst='$cod_tipo_inst' AND a.cod_inst='$cod_inst' AND a.ano_recurso='$ano_recurso' AND a.tipo_recurso='$tipo_recurso' GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano_recurso, a.tipo_recurso ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.ano_recurso, a.tipo_recurso";
		$datos_grap1 = $this->cfpd07_plan_inversion->execute($sql4_inversion_individual);
  	}



	$sql_2 = "SELECT SUM(ordinario)::numeric(22,2) as ordinario, SUM(coordinado)::numeric(22,2) as coordinado, SUM(laee)::numeric(22,2) as laee, SUM(fides)::numeric(22,2) as fides, SUM(ingreso_extraordinario)::numeric(22,2) as ingreso_extraordinario FROM cfpd07_tipo_recurso_proyectado a  WHERE a.cod_presi='$cod_presi' AND a.cod_entidad='$cod_entidad' AND a.cod_tipo_inst='$cod_tipo_inst' AND a.cod_inst='$cod_inst' AND a.ano_recurso='$ano_recurso' GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano_recurso";
	$sql_2_asignado = "SELECT SUM(ordinario_asignado)::numeric(22,2) as ordinario, SUM(coordinado_asignado)::numeric(22,2) as coordinado, SUM(laee_asignado)::numeric(22,2) as laee, SUM(fides_asignado)::numeric(22,2) as fides, SUM(ingreso_extraordinario_asignado)::numeric(22,2) as ingreso_extraordinario FROM cfpd07_tipo_recurso_proyectado a  WHERE a.cod_presi='$cod_presi' AND a.cod_entidad='$cod_entidad' AND a.cod_tipo_inst='$cod_tipo_inst' AND a.cod_inst='$cod_inst' AND a.ano_recurso='$ano_recurso' GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano_recurso";
	$sql_3 = "SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano_recurso, a.tipo_recurso, a.clasificacion_recurso, b.denominacion, a.monto_presupuestado FROM cfpd07_plan_inversion a, cfpd07_clasificacion_recurso b WHERE a.cod_presi='$cod_presi' AND a.cod_entidad='$cod_entidad' AND a.cod_tipo_inst='$cod_tipo_inst' AND a.cod_inst='$cod_inst' AND a.ano_recurso='$ano_recurso' AND a.cod_presi=b.cod_presi AND a.cod_entidad=b.cod_entidad AND a.cod_tipo_inst=b.cod_tipo_inst AND a.cod_inst=b.cod_inst AND a.tipo_recurso=b.tipo_recurso AND a.clasificacion_recurso=b.clasificacion_recurso AND a.tipo_recurso=5";


	//pr($datos_grap1);
	//$datos_grap2_presupuestado = $this->cfpd07_plan_inversion->execute($sql_2);
	//$datos_grap2_asignado = $this->cfpd07_plan_inversion->execute($sql_2_asignado);
	//$datos_grap3 = $this->cfpd07_plan_inversion->execute($sql_3);
	//$datos_inversion_indv = $this->cfpd07_plan_inversion->execute($sql4_inversion_individual);

	$this->set('datos_grap1', $datos_grap1);
	//$this->set('datos_grap2', $datos_grap2_presupuestado);
	//$this->set('datos_grap2_asignado', $datos_grap2_asignado);
	//$this->set('datos_grap3', $datos_grap3);
	//$this->set('datos_inversion_indv', $datos_inversion_indv);
	//pr($datos_grap2);

}

function tipo_recurso_proy_presu_pdf(){
	$this->layout = "pdf";
	$cod_presi = $this->Session->read('SScodpresi');
  	$cod_entidad = $this->Session->read('SScodentidad');
  	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
  	$cod_inst = $this->Session->read('SScodinst');
  	$username = $this->Session->read('nom_usuario');
	$ano_recurso = $this->data['graficos2']['ano_recurso'];

	$this->set('ano_recurso', $ano_recurso);
	$username = $this->Session->read('nom_usuario');
  	$this->set('user', $username);


  	$monto_presupuestado = 0;
  	$monto_nopresupuestado = 0;
  	$asignacion_total    = 0;
  	$recurso             = array();
  	$monto_recurso       = array();


  	$monto_presupuestado = $this->data['graficos2']['monto_presupuestado'];
  	$monto_nopresupuestado = $this->data['graficos2']['monto_nopresupuestado'];
  	$asignacion_total = $this->data['graficos2']['asignacion_total'];
  	$sql_3 = "SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano_recurso, a.tipo_recurso, a.clasificacion_recurso, b.denominacion, a.monto_presupuestado FROM cfpd07_plan_inversion a, cfpd07_clasificacion_recurso b WHERE a.cod_presi='$cod_presi' AND a.cod_entidad='$cod_entidad' AND a.cod_tipo_inst='$cod_tipo_inst' AND a.cod_inst='$cod_inst' AND a.ano_recurso='$ano_recurso' AND a.cod_presi=b.cod_presi AND a.cod_entidad=b.cod_entidad AND a.cod_tipo_inst=b.cod_tipo_inst AND a.cod_inst=b.cod_inst AND a.tipo_recurso=b.tipo_recurso AND a.clasificacion_recurso=b.clasificacion_recurso AND a.tipo_recurso=5";
	$datos_grap3 = $this->cfpd07_plan_inversion->execute($sql_3);
  	$i = 0;
	foreach($datos_grap3 as $row3){
		$recurso[$i] = $row3[0]['denominacion'];
		$monto_recurso[$i] = $row3[0]['monto_presupuestado'];
		$i++;
	}

	$this->set('recurso', $recurso);
	$this->set('monto_recurso', $monto_recurso);

  	//pr($this->data);

  	$this->set('monto_presupuestado', $monto_presupuestado);
  	$this->set('monto_nopresupuestado', $monto_nopresupuestado);
  	$this->set('asignacion_total', $asignacion_total);

}
















function plan_inversion($year_recurso=null){
	$this->layout = "ajax";

	$cod_presi     = $this->Session->read('SScodpresi');
  	$cod_entidad   = $this->Session->read('SScodentidad');
  	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
  	$cod_inst      = $this->Session->read('SScodinst');
  	$username      = $this->Session->read('nom_usuario');
  	$this->set('usr', $username);

  	if($year_recurso!=null){
        $ano_ejecucion = $year_recurso;
  		$ano_recurso   = $year_recurso;
  		$opcion_year   = 1;
  	}else{
  		$ano_ejecucion = $this->ano_ejecucion();
  		$ano_recurso   = $this->ano_ejecucion();
  		$opcion_year   = 2;
  	}//fin else





	$this->set('ano_ejecucion', $ano_ejecucion);
	$this->set('ano_recurso',   $ano_recurso);
	$this->set('opcion_year',   $opcion_year);


  	$sql_2 = "SELECT SUM(ordinario)::numeric(22,2) as ordinario, SUM(coordinado)::numeric(22,2) as coordinado, SUM(laee)::numeric(22,2) as laee, SUM(fides)::numeric(22,2) as fides, SUM(ingreso_extraordinario)::numeric(22,2) as ingreso_extraordinario FROM cfpd07_tipo_recurso_proyectado a  WHERE a.cod_presi='$cod_presi' AND a.cod_entidad='$cod_entidad' AND a.cod_tipo_inst='$cod_tipo_inst' AND a.cod_inst='$cod_inst' AND a.ano_recurso='$ano_recurso' GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano_recurso";
	$sql_2_asignado = "SELECT SUM(ordinario_asignado)::numeric(22,2) as ordinario, SUM(coordinado_asignado)::numeric(22,2) as coordinado, SUM(laee_asignado)::numeric(22,2) as laee, SUM(fides_asignado)::numeric(22,2) as fides, SUM(ingreso_extraordinario_asignado)::numeric(22,2) as ingreso_extraordinario FROM cfpd07_tipo_recurso_proyectado a  WHERE a.cod_presi='$cod_presi' AND a.cod_entidad='$cod_entidad' AND a.cod_tipo_inst='$cod_tipo_inst' AND a.cod_inst='$cod_inst' AND a.ano_recurso='$ano_recurso' GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano_recurso";
	$sql_3 = "SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano_recurso, a.tipo_recurso, a.clasificacion_recurso, b.denominacion, a.monto_presupuestado FROM cfpd07_plan_inversion a, cfpd07_clasificacion_recurso b WHERE a.cod_presi='$cod_presi' AND a.cod_entidad='$cod_entidad' AND a.cod_tipo_inst='$cod_tipo_inst' AND a.cod_inst='$cod_inst' AND a.ano_recurso='$ano_recurso' AND a.cod_presi=b.cod_presi AND a.cod_entidad=b.cod_entidad AND a.cod_tipo_inst=b.cod_tipo_inst AND a.cod_inst=b.cod_inst AND a.tipo_recurso=b.tipo_recurso AND a.clasificacion_recurso=b.clasificacion_recurso AND a.tipo_recurso=5";
	$sql_1 = "SELECT a.ano_recurso, SUM(a.asignacion_total) as asignacion_total, SUM(a.monto_presupuestado) as monto_presupuestado, SUM((a.asignacion_total - a.monto_presupuestado)) as diferencia FROM cfpd07_plan_inversion a WHERE a.cod_presi='$cod_presi' AND a.cod_entidad='$cod_entidad' AND a.cod_tipo_inst='$cod_tipo_inst' AND a.cod_inst='$cod_inst' AND a.ano_recurso='$ano_recurso' GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano_recurso ORDER BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.ano_recurso";
  	$datos_grap1 = $this->cfpd07_plan_inversion->execute($sql_1);

	$datos_grap2_presupuestado = $this->cfpd07_plan_inversion->execute($sql_2);
	$datos_grap2_asignado = $this->cfpd07_plan_inversion->execute($sql_2_asignado);
	$datos_grap3 = $this->cfpd07_plan_inversion->execute($sql_3);
	//$datos_inversion_indv = $this->cfpd07_plan_inversion->execute($sql4_inversion_individual);

	$this->set('datos_grap1', $datos_grap1);
	$this->set('datos_grap2', $datos_grap2_presupuestado);
	$this->set('datos_grap2_asignado', $datos_grap2_asignado);
	$this->set('datos_grap3', $datos_grap3);
	//$this->set('datos_inversion_indv', $datos_inversion_indv);

}//fin function










function plan_inversion_pdf(){
	$this->layout = "pdf";
	$cod_presi = $this->Session->read('SScodpresi');
  	$cod_entidad = $this->Session->read('SScodentidad');
  	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
  	$cod_inst = $this->Session->read('SScodinst');
  	$username = $this->Session->read('nom_usuario');
	$ano_recurso = $this->data['graficos2']['ano_recurso']= $this->ano_ejecucion();

	$this->set('ano_recurso', $ano_recurso);
	$username = $this->Session->read('nom_usuario');
  	$this->set('user', $username);
  	$monto_presupuestado = $this->data['graficos2']['monto_presupuestado'];
  	//$monto_nopresupuestado = $this->data['graficos2']['monto_nopresupuestado'];
  	//$asignacion_total = $this->data['graficos2']['asignacion_total'];
  	$sql_3 = "SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano_recurso, a.tipo_recurso, a.clasificacion_recurso, b.denominacion, a.monto_presupuestado FROM cfpd07_plan_inversion a, cfpd07_clasificacion_recurso b WHERE a.cod_presi='$cod_presi' AND a.cod_entidad='$cod_entidad' AND a.cod_tipo_inst='$cod_tipo_inst' AND a.cod_inst='$cod_inst' AND a.ano_recurso='$ano_recurso' AND a.cod_presi=b.cod_presi AND a.cod_entidad=b.cod_entidad AND a.cod_tipo_inst=b.cod_tipo_inst AND a.cod_inst=b.cod_inst AND a.tipo_recurso=b.tipo_recurso AND a.clasificacion_recurso=b.clasificacion_recurso AND a.tipo_recurso=5";
	$datos_grap3 = $this->cfpd07_plan_inversion->execute($sql_3);
  	$i = 0;
  	$recurso             = array();
  	$monto_recurso       = array();
	foreach($datos_grap3 as $row3){
		$recurso[$i] = $row3[0]['denominacion'];
		$monto_recurso[$i] = $row3[0]['monto_presupuestado'];
		$i++;
	}

	$this->set('recurso', $recurso);
	$this->set('monto_recurso', $monto_recurso);

  	//pr($this->data);

  	$this->set('monto_presupuestado', $monto_presupuestado);
  	//$this->set('monto_nopresupuestado', $monto_nopresupuestado);
  	//$this->set('asignacion_total', $asignacion_total);
}//fin function




}//FIN DE LA CLASE
?>
