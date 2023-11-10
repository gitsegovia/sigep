<?php
 class InfoRecibosPagoController extends AppController{
	var $name = "info_recibos_pago";
	var $uses = array('ccfd04_cierre_mes','v_cnmd06_fichas','cnmd06_datos_personales','datos_personales_super_busqueda','Cnmd01','v_cnmd07_transacciones_actuales',
					  'cnmd08_historia_trabajador','v_cnmd08_historia_trabajador', 'cugd02_institucion','cugd90_municipio_defecto');
    var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap', 'Fpdf', 'infogob');


 function checkSession(){
	if (!$this->Session->check('infogobierno')){
		$this->redirect('/infogobierno/');
		exit();
	}else{
		$c1=substr_count (strtoupper($_SESSION['infogobierno']['cedula_identidad']), 'J');
					$c2=substr_count (strtoupper($_SESSION['infogobierno']['cedula_identidad']), 'G');
					if($c1!=0 || $c2!=0){
						echo "<script type=\"text/javascript\" language=\"javascript\">error('Por favor registrese cómo persona natural para acceder a la información');</script>";
                        exit();
					}
	}
 }


 function beforeFilter(){
 	$this->checkSession();
 }

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

 function index(){
	$this->layout="ajax";
	$cedula = $_SESSION['infogobierno']['cedula_identidad'];
	$nominas = $this->v_cnmd06_fichas->findAll("cedula_identidad=$cedula",'denominacion_dependencia, cod_tipo_nomina, cod_cargo, cod_ficha, denominacion_nomina, primer_apellido, primer_nombre');
	if(count($nominas)!=0){
		foreach($nominas as $n){
			$cod[] = $n['v_cnmd06_fichas']['cod_tipo_nomina'].'-'.$n['v_cnmd06_fichas']['cod_cargo'].'-'.$n['v_cnmd06_fichas']['cod_ficha'];
			$deno[] = mascara_tres($n['v_cnmd06_fichas']['cod_tipo_nomina']).' - '.strtoupper($n['v_cnmd06_fichas']['denominacion_dependencia']).' - '.strtoupper($n['v_cnmd06_fichas']['denominacion_nomina']);
		}
		$lista=array_combine($cod, $deno);
	}else{
		$lista=array('0'=>'No se encuentra presente en ninguna nomina');
	}
	$this->set('lista',$lista);
	$this->set('cedula',$cedula);
	$this->set('nominas_trabajador',$nominas);
 }

 function index_old(){
  $this->layout="ajax";
  $cedula = $_SESSION['infogobierno']['cedula_identidad'];
 
  $this->set('cedula',$cedula);
 }


 function lista_nominas_trabajador($cedula=null, $nomina=null){
 	$this->layout="ajax";
 	if($cedula!=null && $nomina!=null){
		$ficha = $this->v_cnmd06_fichas->findAll("cedula_identidad=$cedula",'denominacion_dependencia, cod_tipo_nomina, cod_cargo, cod_ficha, denominacion_nomina, primer_apellido, primer_nombre');
		$datos_nomina = split('-', $nomina);
		$condicion = "cod_tipo_nomina='".$datos_nomina[0]."' and cod_cargo='".$datos_nomina[1]."' and cod_ficha='".$datos_nomina[2]."' and cedula_identidad=$cedula";

		$datos_nomina = $this->v_cnmd08_historia_trabajador->findAll($condicion,null,"ano DESC, numero_recibo DESC");
		$this->set('nombre',$ficha[0]['v_cnmd06_fichas']['primer_nombre']);
		$this->set('apellido',$ficha[0]['v_cnmd06_fichas']['primer_apellido']);
		$this->set('cedula',$cedula);
		$this->set('deno_nomina',$ficha[0]['v_cnmd06_fichas']['denominacion_nomina']);
		$this->set('datos_nomina',$datos_nomina);
	 }else{
		$this->set('msj', array('Debe seleccionar una opción correcta por favor.','error'));
	 }
 }



 function recibo_pago_empleado_($cod_nomina=null, $cod_cargo=null, $cod_ficha=null, $num_recibo=null){
 	$this->layout="pdf";
 	$tipo_nomina   = $this->data['reporte_personal']['select_tiponomina'];
	//$orden_reporte = $this->data['reporte_personal']['ordenado'];
	//$rango_recibos = $this->data['reporte_personal']['rango_recibos'];
	//$condicion = $this->SQLCA()." AND cod_tipo_nomina='$tipo_nomina'";
	$condicion = "a.cod_tipo_nomina='$cod_nomina' AND a.cod_cargo='$cod_cargo' AND a.cod_ficha='$cod_ficha' AND a.numero_recibo='$num_recibo'";

	//if($tipo_nomina=='' || $orden_reporte=='' || $rango_recibos==''){
	//	echo '<script>history.back(1)</script>';
	//}

/*
	if($rango_recibos==2){
		$recibos_desde = $this->data['reporte_personal']['recibo_desde'];
		$recibos_hasta = $this->data['reporte_personal']['recibo_hasta'];
		if(empty($recibos_desde) || empty($recibos_hasta)){
			echo '<script>history.back(1)</script>';
		}else{
			$condicion .= " AND a.ultimo_recibo >= '$recibos_desde' AND a.ultimo_recibo <= '$recibos_hasta'";
		}
	}
*/

/*
	switch($orden_reporte){
		case '1': $order_by= " a.ultimo_recibo, a.cedula_identidad"; break;
		case '2': $order_by= " a.cod_dir_superior, a.cod_coordinacion, a.cod_secretaria, a.cod_direccion, a.cod_division, a.cod_departamento, a.cod_oficina, a.ultimo_recibo, a.cedula_identidad"; break;
		case '3': $order_by= " a.cod_estado, a.cod_municipio, a.cod_parroquia, a.cod_centro, a.ultimo_recibo, a.cedula_identidad"; break;
		case '4': $order_by= " a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.ultimo_recibo, a.cedula_identidad"; break;
		default : $order_by= " a.ultimo_recibo, a.cedula_identidad"; break;
	}
*/
	$order_by= " a.cod_estado, a.cod_municipio, a.cod_parroquia, a.cod_centro, a.ultimo_recibo, a.cedula_identidad";

	$sql2 = "SELECT a.cod_tipo_nomina, a.denominacion_nomina, a.cod_coordinacion, a.deno_cod_coordinacion, a.cod_secretaria, a.deno_cod_secretaria,
	a.cod_direccion, a.deno_cod_direccion, a.cod_division, a.deno_cod_division, a.cod_departamento, a.deno_cod_departamento, a.cod_oficina, a.deno_cod_oficina,
	a.cedula_identidad, a.primer_apellido, a.segundo_apellido, a.primer_nombre, a.segundo_nombre, a.cod_puesto, a.denominacion_puesto, a.cod_cargo, a.cod_ficha, a.sueldo_basico,
	a.fecha_ingreso, a.numero_nomina, a.correspondiente, a.periodo_desde, a.periodo_hasta,
	a.cod_tipo_transaccion, a.cod_transaccion, a.deno_transaccion, a.dias_horas, a.frecuencia_cobro, a.monto_cuota, a.saldo, a.ultimo_recibo,
	a.cod_entidad_bancaria, a.deno_entidades_bancarias, a.cod_sucursal, a.cuenta_bancaria
	FROM v_cnmd07_transacciones_actuales a
	WHERE ".$condicion." ORDER BY ".$order_by;

	$sql = "
SELECT a.cod_presi,
a.cod_entidad,
a.cod_tipo_inst,
a.cod_inst,
a.cod_dep,
a.cod_tipo_nomina,
a.cod_cargo,
a.cod_ficha,
a.cod_tipo_transaccion,
a.cod_transaccion,
a.fecha_transaccion,
a.monto_original,
a.numero_cuotas_descontar,
a.numero_cuotas_canceladas,
a.monto_cuota,
a.saldo,
a.dias_horas,
a.numero_recibo,
a.numero_recibo as ultimo_recibo,
( SELECT b.denominacion  FROM cugd02_dependencias b WHERE b.cod_tipo_institucion = a.cod_tipo_inst AND b.cod_institucion = a.cod_inst AND b.cod_dependencia = a.cod_dep) AS denominacion_dep,
( SELECT b.correspondiente  FROM cnmd01 b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.cod_tipo_nomina = a.cod_tipo_nomina) AS correspondiente,
( SELECT b.denominacion  FROM cnmd01 b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.cod_tipo_nomina = a.cod_tipo_nomina) AS denominacion_nomina,
( SELECT b.numero_nomina  FROM cnmd01 b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.cod_tipo_nomina = a.cod_tipo_nomina) AS numero_nomina,
( SELECT b.periodo_desde  FROM cnmd01 b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.cod_tipo_nomina = a.cod_tipo_nomina) AS periodo_desde,
( SELECT b.periodo_hasta  FROM cnmd01 b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.cod_tipo_nomina = a.cod_tipo_nomina) AS periodo_hasta,
( SELECT b.frecuencia_cobro  FROM cnmd01 b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.cod_tipo_nomina = a.cod_tipo_nomina) AS frecuencia_cobro,
( SELECT b.clasificacion_personal  FROM cnmd01 b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.cod_tipo_nomina = a.cod_tipo_nomina) AS clasificacion_personal,
( SELECT b.frecuencia_pago  FROM cnmd01 b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.cod_tipo_nomina = a.cod_tipo_nomina) AS frecuencia_pago,
( SELECT b.cedula_identidad  FROM cnmd06_fichas b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.cod_tipo_nomina = a.cod_tipo_nomina AND b.cod_cargo = a.cod_cargo AND b.cod_ficha = a.cod_ficha) AS cedula_identidad,
( SELECT b.fecha_ingreso  FROM cnmd06_fichas b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.cod_tipo_nomina = a.cod_tipo_nomina AND b.cod_cargo = a.cod_cargo AND b.cod_ficha = a.cod_ficha) AS fecha_ingreso,
( SELECT b.ultimo_recibo  FROM cnmd06_fichas b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.cod_tipo_nomina = a.cod_tipo_nomina AND b.cod_cargo = a.cod_cargo AND b.cod_ficha = a.cod_ficha) AS ultimo_recibo,
( SELECT b.cod_entidad_bancaria  FROM cnmd06_fichas b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.cod_tipo_nomina = a.cod_tipo_nomina AND b.cod_cargo = a.cod_cargo AND b.cod_ficha = a.cod_ficha) AS cod_entidad_bancaria,
( SELECT x.denominacion  FROM cstd01_entidades_bancarias x WHERE x.cod_entidad_bancaria = (( SELECT b.cod_entidad_bancaria          FROM cnmd06_fichas b         WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.cod_tipo_nomina = a.cod_tipo_nomina AND b.cod_cargo = a.cod_cargo AND b.cod_ficha = a.cod_ficha))) AS deno_entidades_bancarias,
( SELECT b.cod_sucursal  FROM cnmd06_fichas b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.cod_tipo_nomina = a.cod_tipo_nomina AND b.cod_cargo = a.cod_cargo AND b.cod_ficha = a.cod_ficha) AS cod_sucursal,
( SELECT x.denominacion  FROM cstd01_sucursales_bancarias x WHERE x.cod_entidad_bancaria = (( SELECT b.cod_entidad_bancaria          FROM cnmd06_fichas b         WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.cod_tipo_nomina = a.cod_tipo_nomina AND b.cod_cargo = a.cod_cargo AND b.cod_ficha = a.cod_ficha)) AND x.cod_sucursal = (( SELECT b.cod_sucursal          FROM cnmd06_fichas b         WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.cod_tipo_nomina = a.cod_tipo_nomina AND b.cod_cargo = a.cod_cargo AND b.cod_ficha = a.cod_ficha))) AS deno_sucursales_bancarias,
( SELECT b.cuenta_bancaria  FROM cnmd06_fichas b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.cod_tipo_nomina = a.cod_tipo_nomina AND b.cod_cargo = a.cod_cargo AND b.cod_ficha = a.cod_ficha) AS cuenta_bancaria,
( SELECT e.primer_apellido  FROM cnmd06_datos_personales e WHERE e.cedula_identidad = (( SELECT d.cedula_identidad          FROM cnmd06_fichas d         WHERE d.cod_presi = a.cod_presi AND d.cod_entidad = a.cod_entidad AND d.cod_tipo_inst = a.cod_tipo_inst AND d.cod_inst = a.cod_inst AND d.cod_dep = a.cod_dep AND d.cod_tipo_nomina = a.cod_tipo_nomina AND d.cod_cargo = a.cod_cargo AND d.cod_ficha = a.cod_ficha))) AS primer_apellido,
( SELECT e.segundo_apellido  FROM cnmd06_datos_personales e WHERE e.cedula_identidad = (( SELECT d.cedula_identidad          FROM cnmd06_fichas d         WHERE d.cod_presi = a.cod_presi AND d.cod_entidad = a.cod_entidad AND d.cod_tipo_inst = a.cod_tipo_inst AND d.cod_inst = a.cod_inst AND d.cod_dep = a.cod_dep AND d.cod_tipo_nomina = a.cod_tipo_nomina AND d.cod_cargo = a.cod_cargo AND d.cod_ficha = a.cod_ficha))) AS segundo_apellido,
( SELECT e.primer_nombre  FROM cnmd06_datos_personales e WHERE e.cedula_identidad = (( SELECT d.cedula_identidad          FROM cnmd06_fichas d         WHERE d.cod_presi = a.cod_presi AND d.cod_entidad = a.cod_entidad AND d.cod_tipo_inst = a.cod_tipo_inst AND d.cod_inst = a.cod_inst AND d.cod_dep = a.cod_dep AND d.cod_tipo_nomina = a.cod_tipo_nomina AND d.cod_cargo = a.cod_cargo AND d.cod_ficha = a.cod_ficha))) AS primer_nombre,
( SELECT e.segundo_nombre  FROM cnmd06_datos_personales e WHERE e.cedula_identidad = (( SELECT d.cedula_identidad          FROM cnmd06_fichas d         WHERE d.cod_presi = a.cod_presi AND d.cod_entidad = a.cod_entidad AND d.cod_tipo_inst = a.cod_tipo_inst AND d.cod_inst = a.cod_inst AND d.cod_dep = a.cod_dep AND d.cod_tipo_nomina = a.cod_tipo_nomina AND d.cod_cargo = a.cod_cargo AND d.cod_ficha = a.cod_ficha))) AS segundo_nombre,
( SELECT devolver_denominacion_puesto(( SELECT xy.clasificacion_personal          FROM cnmd01 xy         WHERE xy.cod_presi = a.cod_presi AND xy.cod_entidad = a.cod_entidad AND xy.cod_tipo_inst = a.cod_tipo_inst AND xy.cod_inst = a.cod_inst AND xy.cod_dep = a.cod_dep AND xy.cod_tipo_nomina = a.cod_tipo_nomina),
( SELECT c.cod_puesto          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) AS devolver_denominacion_puesto) AS denominacion_puesto,
( SELECT devolver_denominacion_transaccion(a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.cod_tipo_nomina,a.cod_tipo_transaccion,a.cod_transaccion) AS devolver_denominacion_transaccion) AS deno_transaccion,
( SELECT c.bonos  FROM cnmd05 c WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo) AS bonos,
( SELECT c.primas  FROM cnmd05 c WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo) AS primas,
( SELECT c.compensaciones  FROM cnmd05 c WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo) AS compensaciones,
( SELECT c.sueldo_basico  FROM cnmd05 c WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo) AS sueldo_basico,
( SELECT c.cod_puesto  FROM cnmd05 c WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo) AS cod_puesto,
( SELECT c.cod_dir_superior  FROM cnmd05 c WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo) AS cod_dir_superior,
( SELECT xa.denominacion  FROM cugd02_direccionsuperior xa WHERE xa.cod_tipo_institucion = a.cod_tipo_inst AND xa.cod_institucion = a.cod_inst AND xa.cod_dependencia = a.cod_dep AND xa.cod_dir_superior = (( SELECT c.cod_dir_superior          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) GROUP BY xa.denominacion) AS deno_cod_dir_superior,
( SELECT c.cod_coordinacion  FROM cnmd05 c WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo) AS cod_coordinacion,
( SELECT xb.denominacion  FROM cugd02_coordinacion xb WHERE xb.cod_tipo_institucion = a.cod_tipo_inst AND xb.cod_institucion = a.cod_inst AND xb.cod_dependencia = a.cod_dep AND xb.cod_dir_superior = (( SELECT c.cod_dir_superior          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) AND xb.cod_coordinacion = (( SELECT c.cod_coordinacion          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) GROUP BY xb.denominacion) AS deno_cod_coordinacion,
( SELECT c.cod_secretaria  FROM cnmd05 c WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo) AS cod_secretaria,
( SELECT xc.denominacion  FROM cugd02_secretaria xc WHERE xc.cod_tipo_institucion = a.cod_tipo_inst AND xc.cod_institucion = a.cod_inst AND xc.cod_dependencia = a.cod_dep AND xc.cod_dir_superior = (( SELECT c.cod_dir_superior          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) AND xc.cod_coordinacion = (( SELECT c.cod_coordinacion          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) AND xc.cod_secretaria = (( SELECT c.cod_secretaria          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) GROUP BY xc.denominacion) AS deno_cod_secretaria,
( SELECT c.cod_direccion  FROM cnmd05 c WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo) AS cod_direccion,
( SELECT xc.denominacion  FROM cugd02_direccion xc WHERE xc.cod_tipo_institucion = a.cod_tipo_inst AND xc.cod_institucion = a.cod_inst AND xc.cod_dependencia = a.cod_dep AND xc.cod_dir_superior = (( SELECT c.cod_dir_superior          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) AND xc.cod_coordinacion = (( SELECT c.cod_coordinacion          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) AND xc.cod_secretaria = (( SELECT c.cod_secretaria          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) AND xc.cod_direccion = (( SELECT c.cod_direccion          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) GROUP BY xc.denominacion) AS deno_cod_direccion,
( SELECT c.cod_division  FROM cnmd05 c WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo) AS cod_division,
( SELECT xc.denominacion  FROM cugd02_division xc WHERE xc.cod_tipo_institucion = a.cod_tipo_inst AND xc.cod_institucion = a.cod_inst AND xc.cod_dependencia = a.cod_dep AND xc.cod_dir_superior = (( SELECT c.cod_dir_superior          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) AND xc.cod_coordinacion = (( SELECT c.cod_coordinacion          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) AND xc.cod_secretaria = (( SELECT c.cod_secretaria          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) AND xc.cod_direccion = (( SELECT c.cod_direccion          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) AND xc.cod_division = (( SELECT c.cod_division          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) GROUP BY xc.denominacion) AS deno_cod_division,
( SELECT c.cod_departamento  FROM cnmd05 c WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo) AS cod_departamento,
( SELECT xc.denominacion  FROM cugd02_departamento xc WHERE xc.cod_tipo_institucion = a.cod_tipo_inst AND xc.cod_institucion = a.cod_inst AND xc.cod_dependencia = a.cod_dep AND xc.cod_dir_superior = (( SELECT c.cod_dir_superior          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) AND xc.cod_coordinacion = (( SELECT c.cod_coordinacion          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) AND xc.cod_secretaria = (( SELECT c.cod_secretaria          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) AND xc.cod_direccion = (( SELECT c.cod_direccion          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) AND xc.cod_division = (( SELECT c.cod_division          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) AND xc.cod_departamento = (( SELECT c.cod_departamento          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) GROUP BY xc.denominacion) AS deno_cod_departamento,
( SELECT c.cod_oficina  FROM cnmd05 c WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo) AS cod_oficina,
( SELECT xc.denominacion  FROM cugd02_oficina xc WHERE xc.cod_tipo_institucion = a.cod_tipo_inst AND xc.cod_institucion = a.cod_inst AND xc.cod_dependencia = a.cod_dep AND xc.cod_dir_superior = (( SELECT c.cod_dir_superior          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) AND xc.cod_coordinacion = (( SELECT c.cod_coordinacion          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) AND xc.cod_secretaria = (( SELECT c.cod_secretaria          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) AND xc.cod_direccion = (( SELECT c.cod_direccion          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) AND xc.cod_division = (( SELECT c.cod_division          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) AND xc.cod_departamento = (( SELECT c.cod_departamento          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) AND xc.cod_oficina = (( SELECT c.cod_oficina          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) GROUP BY xc.denominacion) AS deno_cod_oficina,
( SELECT c.cod_estado  FROM cnmd05 c WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo) AS cod_estado,
( SELECT xya.denominacion  FROM cugd01_estados xya WHERE xya.cod_republica = a.cod_presi AND xya.cod_estado = (( SELECT c.cod_estado          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) GROUP BY xya.denominacion) AS deno_cod_estado,
( SELECT c.cod_municipio  FROM cnmd05 c WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo) AS cod_municipio,
( SELECT xya.denominacion  FROM cugd01_municipios xya WHERE xya.cod_republica = a.cod_presi AND xya.cod_estado = (( SELECT c.cod_estado          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) AND xya.cod_municipio = (( SELECT c.cod_municipio          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) GROUP BY xya.denominacion) AS deno_cod_municipio,
( SELECT c.cod_parroquia  FROM cnmd05 c WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo) AS cod_parroquia,
( SELECT xya.denominacion  FROM cugd01_parroquias xya WHERE xya.cod_republica = a.cod_presi AND xya.cod_estado = (( SELECT c.cod_estado          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) AND xya.cod_municipio = (( SELECT c.cod_municipio          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) AND xya.cod_parroquia = (( SELECT c.cod_parroquia          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) GROUP BY xya.denominacion) AS deno_cod_parroquia,
( SELECT c.cod_centro  FROM cnmd05 c WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo) AS cod_centro,
( SELECT xya.denominacion  FROM cugd01_centros_poblados xya WHERE xya.cod_republica = a.cod_presi AND xya.cod_estado = (( SELECT c.cod_estado          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) AND xya.cod_municipio = (( SELECT c.cod_municipio          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) AND xya.cod_parroquia = (( SELECT c.cod_parroquia          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) AND xya.cod_centro = (( SELECT c.cod_centro          FROM cnmd05 c         WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo)) GROUP BY xya.denominacion) AS deno_cod_centro,
( SELECT c.condicion_actividad  FROM cnmd05 c WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo) AS condicion_actividad,
( SELECT c.ano  FROM cnmd05 c WHERE c.cod_presi = a.cod_presi AND c.cod_entidad = a.cod_entidad AND c.cod_tipo_inst = a.cod_tipo_inst AND c.cod_inst = a.cod_inst AND c.cod_dep = a.cod_dep AND c.cod_tipo_nomina = a.cod_tipo_nomina AND c.cod_cargo = a.cod_cargo) AS ano,
( SELECT b.dias_cobro  FROM cnmd01 b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.cod_tipo_nomina = a.cod_tipo_nomina) AS dias_cobro_cnmd01,
( SELECT b.dias  FROM cnmd09_dias_trabajados_falta b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.cod_tipo_nomina = a.cod_tipo_nomina AND b.cod_cargo = a.cod_cargo AND b.cod_ficha = a.cod_ficha) AS dias_cnmd09_dias_trabajados_falta,
( SELECT b.dias  FROM cnmd09_dias_trabajados_ingreso_egreso b WHERE b.cod_presi = a.cod_presi AND b.cod_entidad = a.cod_entidad AND b.cod_tipo_inst = a.cod_tipo_inst AND b.cod_inst = a.cod_inst AND b.cod_dep = a.cod_dep AND b.cod_tipo_nomina = a.cod_tipo_nomina AND b.cod_cargo = a.cod_cargo AND b.cod_ficha = a.cod_ficha) AS dias_cnmd09_dias_trabajados_ingreso_egreso,
( SELECT x.uso_transaccion  FROM cnmd03_transacciones x WHERE x.cod_tipo_transaccion = a.cod_tipo_transaccion AND x.cod_transaccion = a.cod_transaccion) AS uso_transaccion

   FROM v_cnmd08_transacciones_trabajador_historia a


	WHERE ".$condicion;
    //echo $sql;
	$datos = $this->v_cnmd07_transacciones_actuales->execute($sql);

	$this->set('datos',$datos);
	$this->set('var','si');
	$deno_inst = $this->cugd02_institucion->findAll("cod_tipo_institucion='$cod_tipo_inst' AND cod_institucion='$cod_inst'",'denominacion');
	$_SESSION['ins_deno']=$deno_inst[0]['cugd02_institucion']['denominacion'];

 }

function recibo_pago_empleado($cod_tipo_nomina=null,$ano=null,$cod_cargo=null,$cod_ficha=null,$cedula=null,$num_nomina=null,$num_recibo=null){
		$this->layout="pdf";
		$cod_presi 		= $this->verifica_SS(1);
	  	$cod_entidad 	= $this->verifica_SS(2);
	  	$cod_tipo_inst 	= $this->verifica_SS(3);
	  	$cod_inst 		= $this->verifica_SS(4);
	  	$cod_dep 		= $this->verifica_SS(5);

		$cantidad_pago = $this->Cnmd01->execute("SELECT cantidad_pagos FROM cnmd08_historia_nomina  WHERE cod_tipo_nomina='".$cod_tipo_nomina."' and ano='".$ano."' and numero_nomina='".$num_nomina."'");
		$cant = $cantidad_pago[0][0]['cantidad_pagos'];
		$this->set('cant_pagos',$cant);

		$condicion = "a.cod_tipo_nomina='$cod_tipo_nomina' AND a.cedula_identidad='$cedula' AND a.numero_nomina='$num_nomina' AND a.numero_recibo='$num_recibo' and a.cod_cargo=$cod_cargo and a.cod_ficha=$cod_ficha";

		$sql = "SELECT a.cod_tipo_nomina, a.denominacion_nomina, a.cod_coordinacion, a.deno_cod_coordinacion, a.cod_secretaria, a.deno_cod_secretaria,
		a.cod_direccion, a.deno_cod_direccion, a.cod_division, a.deno_cod_division, a.cod_departamento, a.deno_cod_departamento, a.cod_oficina, a.deno_cod_oficina,
		a.cod_municipio, a.deno_cod_municipio, a.cod_parroquia, a.deno_cod_parroquia, a.cod_centro, a.deno_cod_centro,
		a.cedula_identidad, a.primer_apellido, a.segundo_apellido, a.primer_nombre, a.segundo_nombre, a.cod_puesto, a.denominacion_puesto, a.cod_cargo, a.cod_ficha, a.sueldo_basico,
		a.fecha_ingreso, a.numero_nomina, a.correspondiente, a.periodo_desde, a.periodo_hasta,
		a.cod_tipo_transaccion, a.cod_transaccion, a.deno_transaccion, a.dias_horas, a.frecuencia_cobro, a.monto_cuota, a.saldo, a.ultimo_recibo,
		a.cod_entidad_bancaria, a.deno_entidades_bancarias, a.cod_sucursal, a.cuenta_bancaria, a.uso_transaccion,a.nombre_representado, a.cedula_representado,
		(SELECT b.denominacion_devengado FROM cnmd01 b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo_nomina=a.cod_tipo_nomina) as devengado,
		a.numero_recibo
		FROM v_cnmd08_historia_transacciones a
		WHERE ".$condicion." AND a.uso_transaccion!=6 AND a.uso_transaccion!=9 ORDER BY cedula_identidad, numero_recibo";// AND a.ultimo_recibo!=0

		$codigos_inst = $this->cugd90_municipio_defecto->findAll('1=1');

		$deno_inst = $this->cugd02_institucion->findAll("cod_tipo_institucion=".$codigos_inst[0]['cugd90_municipio_defecto']['cod_tipo_inst']." AND cod_institucion=".$codigos_inst[0]['cugd90_municipio_defecto']['cod_inst'],'denominacion');
		$_SESSION['ins_deno']=$deno_inst[0]['cugd02_institucion']['denominacion'];

		$datos = $this->v_cnmd07_transacciones_actuales->execute($sql);
		$this->set('datos',$datos);
		$this->set('var','si');
		//echo $sql;


}//emision_recibos_pago_historico

 }//fin class



?>