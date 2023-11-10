<?php
/*
 * Fecha: 19/07/2007
 *
 * Por Luis Alfredo Diaz Jaramillo
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */
class Cnmp01Controller extends AppController {
   var $name = 'Cnmp01';
   var $uses = array('Cnmd01','Cnmd01_tipo', 'cfpd02_programa', 'cfpd02_sub_prog', 'cfpd02_proyecto', 'cfpd02_activ_obra', 'cnmd06_fichas');


   var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf','Form');

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

 }


function condicion(){
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  return $condicion;
}



function AddCero($nomVar,$vector=object,$extra=null){
   	  if($vector != null){
			if($extra==null){
			foreach($vector as $x){
				if($x<10){
				$Var[$x]="0".$x;
				}else{
				$Var[$x]=$x;
				}
			}//fin each
		}else{
			foreach($vector as $x){
				if($x<10){
				$Var[$x]=$extra.".0".$x;
				}else{
				$Var[$x]=$extra.".".$x;
				}
			}//fin each
		}
		$this->set($nomVar,$Var);
   	  }else{
   	  	$this->set($nomVar,'');
   	  }
   }//fin AddCero

   function zero($x=null){
	if($x != null){
		if($x<10){
			$x="0".$x;
		}else if($x>=10 && $x<=99){
			$x=$x;
		}
	}
	return $x;

}

function concatena($vector1=null, $nomVar=null){

	if($vector1 != null){

		foreach($vector1 as $x => $y){

			$cod[$x] = $this->zero($x).' - '.$y;
		}
		//print_r($cod);

		$this->set($nomVar, $cod);

	}
}


function index(){
	$this->layout = "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$clasif = array('1'=>'Empleados', '2'=>'Obreros', '3'=>'Militares Profesionales', '4'=>'Militares No Profesionales', '5'=>'Contratados Empleados', '16'=>'Contratados Obreros', '6'=>'Suplencias Empleados', '15'=>'Suplencias Obreros', '7'=>'Jubilados Empleados', '8'=>'Jubilados Obreros', '9'=>'Pensionados Empleados', '10'=>'Pensionados Obreros', '11'=>'Dietas', '12'=>'Comisión de Servicios', '13'=>'Becas', '14'=>'Ayuda','17'=>'Altos Funcionarios','18'=>'Elección Popular');

	$this->set('clasificacion', $clasif);
	$frec = array('1'=>'Diario', '2'=>'Semanal', '3'=>'Quincenal', '4'=>'Mensual', '5'=>'Bimensual', '6'=>'Trimestral');
	$this->set('frecuencia', $frec);
	$frec_pago = array('1'=>'1ra Semana', '2'=>'2da Semana', '3'=>'3ra Semana', '4'=>'4ta Semana', '5'=>'5ta Semana');
	$frec_pago2 = array('1ra Quincena', '2da Quincena', 'Ambas', 'Mes Completo', 'Pago Unico');
	$status = array('Pre-n&oacute;mina', 'Corrida Definitiva', 'Emisi&oacute;n de Recibos', 'Cierre');
	$this->set('frecuencia_pago', $frec_pago);
	$this->set('frecuencia_pago2', $frec_pago2);
	$this->set('status', $status);
	$this->set('enable', 'disabled');
	$lista = $this->Cnmd01_tipo->generateList($this->condicion(), 'cod_tipo_nomina ASC', null, '{n}.Cnmd01_tipo.cod_tipo_nomina', '{n}.Cnmd01_tipo.denominacion');

	if($this->Cnmd01_tipo->findCount($this->condicion())!=0){
		$this->concatenaN($lista, 'nomina');
	}else{
		$this->set('nomina', array());
	}
	$meses= array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
	$this->set('meses',$meses);

 }// fin fuction index





 function principal ($var=null) {
 	$this->layout = "ajax";
 	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$lista = $this->Cnmd01->generateList($this->condicion(), 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');

	if($this->Cnmd01->findCount($this->condicion())!=0){
		$this->concatenaN($lista, 'nomina');
	}else{
		$this->set('nomina', array());
	}
	$clasif = array('1'=>'Empleados', '2'=>'Obreros', '3'=>'Militares Profesionales', '4'=>'Militares No Profesionales', '5'=>'Contratados Empleados', '16'=>'Contratados Obreros', '6'=>'Suplencias Empleados', '15'=>'Suplencias Obreros', '7'=>'Jubilados Empleados', '8'=>'Jubilados Obreros', '9'=>'Pensionados Empleados', '10'=>'Pensionados Obreros', '11'=>'Dietas', '12'=>'Comisión de Servicios', '13'=>'Becas', '14'=>'Ayuda','17'=>'Altos Funcionarios','18'=>'Elección Popular');
	$this->set('clasificacion', $clasif);
	$frec = array('1'=>'Diario', '2'=>'Semanal', '3'=>'Quincenal', '4'=>'Mensual', '5'=>'Bimensual', '6'=>'Trimestral');
	$this->set('frecuencia', $frec);
	$frec_pago = array('1'=>'1ra Semana', '2'=>'2da Semana', '3'=>'3ra Semana', '4'=>'4ta Semana', '5'=>'5ta Semana');
	$frec_pago2 = array('1ra Quincena', '2da Quincena', 'Ambas', 'Mes Completo', 'Pago Unico');
	$status = array('Pre-n&oacute;mina', 'Corrida Definitiva', 'Emisi&oacute;n de Recibos', 'Cierre');
	$this->set('frecuencia_pago', $frec_pago);
	$this->set('frecuencia_pago2', $frec_pago2);
	$this->set('status', $status);
	$this->set('enable', 'disabled');
	if($var!= null){
		if($var=='otros'){

			$this->set('agregar', true);
		}else{
			$this->set('mostrar', true);
			$datos = $this->Cnmd01->findAll($this->condicion().' and cod_tipo_nomina = '.$var, null, null, null);
			$this->set('datos', $datos);

			if($this->cnmd06_fichas->findCount($this->condicion().' and cod_tipo_nomina = '.$var)!=0){
				$this->set('enable2', 'disabled');
			}else{
				$this->set('enable2', '');
			}
		}
	}

	$meses= array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
	$this->set('meses',$meses);
}



 function guardar(){
 	$this->layout = "ajax";
 	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');


 	$clasif = array('1'=>'Empleados', '2'=>'Obreros', '3'=>'Militares Profesionales', '4'=>'Militares No Profesionales', '5'=>'Contratados Empleados', '16'=>'Contratados Obreros', '6'=>'Suplencias Empleados', '15'=>'Suplencias Obreros', '7'=>'Jubilados Empleados', '8'=>'Jubilados Obreros', '9'=>'Pensionados Empleados', '10'=>'Pensionados Obreros', '11'=>'Dietas', '12'=>'Comisión de Servicios', '13'=>'Becas', '14'=>'Ayuda','17'=>'Altos Funcionarios','18'=>'Elección Popular');
	$this->set('clasificacion', $clasif);
	$frec = array('1'=>'Diario', '2'=>'Semanal', '3'=>'Quincenal', '4'=>'Mensual', '5'=>'Bimensual', '6'=>'Trimestral');
	$this->set('frecuencia', $frec);
	$frec_pago = array('1'=>'1ra Semana', '2'=>'2da Semana', '3'=>'3ra Semana', '4'=>'4ta Semana', '5'=>'5ta Semana');
	$frec_pago2 = array('1ra Quincena', '2da Quincena', 'Ambas', 'Mes Completo', 'Pago Unico');
	$status = array('Pre-n&oacute;mina', 'Corrida Definitiva', 'Emisi&oacute;n de Recibos', 'Cierre');
	$this->set('frecuencia_pago', $frec_pago);
	$this->set('frecuencia_pago2', $frec_pago2);
	$this->set('status', $status);
	$this->set('enable', '');
//pr($this->data);
 	if(!empty($this->data['cnmp01'])){
 		if($this->data['cnmp01']['vacaciones_colectivas']==1 && (empty($this->data['cnmp01']['dia_day'])||empty($this->data['cnmp01']['mes'])) ){
 			$this->set('errorMessage', 'Asegurese de seleccionar el dia y el mes');
 		}else{
 			$cod_tipo_nomina = $this->data['cnmp01']['cod_tipo_nomina'];
	 		$denominacion = $this->data['cnmp01']['denominacion'];
	 		$denominacion_devengado = $this->data['cnmp01']['denominacion_devengado'];
	 		$clasificacion_personal = $this->data['cnmp01']['clasificacion_personal'];
	 		$frecuencia_cobro = $this->data['cnmp01']['frecuencia_cobro'];
	 		$dias_laborables =  $this->Formato1($this->data['cnmp01']['dias_laborables']);
	 		$Horas_laborables = $this->Formato1($this->data['cnmp01']['Horas_laborables']);
	 		$descuentos_ley = $this->data['cnmp01']['descuentos_ley'];
	 		$mensajes_colectivos = $this->data['cnmp01']['mensajes_colectivos'];
	 		$vacaciones_colectivas = $this->data['cnmp01']['vacaciones_colectivas'];

 			if($this->data['cnmp01']['vacaciones_colectivas']==1){
 				$dia = $this->data['cnmp01']['dia_day'];
	 			$mes = $this->data['cnmp01']['mes'];
 			}else{
 				$dia = 0;
	 			$mes = 0;
 			}


	 		$this->set('cod_tipo_nomina', $cod_tipo_nomina);
	 		$this->set('denominacion', $denominacion);
	 		$this->set('denominacion_devengado', $denominacion_devengado);
	 		$this->set('clasificacion_personal', $clasificacion_personal);
	 		$this->set('frecuencia_cobro', $frecuencia_cobro);
	 		$this->set('dias_laborables', $dias_laborables);
	 		$this->set('Horas_laborables', $Horas_laborables);
	 		$this->set('descuentos_ley', $descuentos_ley);
	 		$this->set('mensajes_colectivos', $mensajes_colectivos);
	 		$this->set('vacaciones_colectivas', $vacaciones_colectivas);
	 		$this->set('dia', $dia);
	 		$this->set('mes', $mes);


	 		switch($frecuencia_cobro){
				case 1:
					$dias_cobro=1;
				break;
				case 2:
					$dias_cobro=7;
				break;
				case 3:
					$dias_cobro=15;
				break;
				case 4:
					$dias_cobro=30;
				break;
				case 5:
					$dias_cobro=60;
				break;
				case 6:
					$dias_cobro=90;
				break;

			}

	 		$lista = $this->Cnmd01->generateList($this->condicion(), 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
			if($this->Cnmd01->findCount($this->condicion())!=0){
				$this->concatenaN($lista, 'nomina');
				$this->set('cod', $cod_tipo_nomina);
			}else{
				$this->set('nomina', array());
			}

	 		if($this->Cnmd01->findCount($this->condicion()." and cod_tipo_nomina = ".$cod_tipo_nomina) == 0){

				$sql = "INSERT INTO Cnmd01 VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_tipo_nomina', '$denominacion', '$denominacion_devengado', '$clasificacion_personal', '$frecuencia_cobro', '$dias_laborables', '$Horas_laborables', '$descuentos_ley', '$mensajes_colectivos', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0','$dias_cobro',null,null,0,'$vacaciones_colectivas','$dia','$mes')";
				$this->Cnmd01->execute($sql);

				$sql_update = "update cnmd06_fichas set horas_laborar='".$Horas_laborables."' where ".$this->condicion()." and cod_tipo_nomina = ".$cod_tipo_nomina;
	            $this->Cnmd01->execute($sql_update);

				$this->set('Message_existe', 'LOS DATOS FUER&Oacute;N GUARDADOS CORRECTAMENTE');

				$this->set('guardado','si');
	 		}else{
	 			$this->set('errorMessage', 'YA EXISTE UN TIPO DE N&Oacute;MINA CON ESTE C&Oacute;DIGO');
	 		}
 		}

 	}else{
 		$this->set('errorMessage', 'Los datos no pudier&oacute;n ser registrados, verifique completar los datos requeridos');
 	}


 }

 function editar($var1 = null, $pagina=null){
	$this->layout = "ajax";
 	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$this->set('pagina',$pagina);

 	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$clasif = array('1'=>'Empleados', '2'=>'Obreros', '3'=>'Militares Profesionales', '4'=>'Militares No Profesionales', '5'=>'Contratados Empleados', '16'=>'Contratados Obreros', '6'=>'Suplencias Empleados', '15'=>'Suplencias Obreros', '7'=>'Jubilados Empleados', '8'=>'Jubilados Obreros', '9'=>'Pensionados Empleados', '10'=>'Pensionados Obreros', '11'=>'Dietas', '12'=>'Comisión de Servicios', '13'=>'Becas', '14'=>'Ayuda','17'=>'Altos Funcionarios','18'=>'Elección Popular' );
	$this->set('clasificacion', $clasif);
	$frec = array('1'=>'Diario', '2'=>'Semanal', '3'=>'Quincenal', '4'=>'Mensual', '5'=>'Bimensual', '6'=>'Trimestral');
	$this->set('frecuencia', $frec);
	$frec_pago = array('1'=>'1ra Semana', '2'=>'2da Semana', '3'=>'3ra Semana', '4'=>'4ta Semana', '5'=>'5ta Semana');
	$frec_pago2 = array('1ra Quincena', '2da Quincena', 'Ambas', 'Mes Completo', 'Pago Unico');
	$status = array('Pre-n&oacute;mina', 'Corrida Definitiva', 'Emisi&oacute;n de Recibos', 'Cierre');
	$this->set('frecuencia_pago', $frec_pago);
	$this->set('frecuencia_pago2', $frec_pago2);
	$this->set('status', $status);
	$this->set('enable', 'disabled');

	$meses= array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
	$this->set('meses',$meses);


   $datos = $this->Cnmd01->findAll($this->condicion().' and cod_tipo_nomina = '.$var1, null, null, null);

	$this->set('cod_tipo_nomina',        $datos[0]["Cnmd01"]["cod_tipo_nomina"]);
 	$this->set('denominacion',           $datos[0]["Cnmd01"]["denominacion"]);
 	$this->set('denominacion_devengado', $datos[0]["Cnmd01"]["denominacion_devengado"]);
 	$this->set('clasificacion_personal', $datos[0]["Cnmd01"]["clasificacion_personal"]);
 	$this->set('frecuencia_cobro',       $datos[0]["Cnmd01"]["frecuencia_cobro"]);
 	$this->set('dias_laborables',        $datos[0]["Cnmd01"]["dias_laborables"]);
 	$this->set('Horas_laborables',       $datos[0]["Cnmd01"]["horas_laborables"]);
 	$this->set('descuentos_ley',         $datos[0]["Cnmd01"]["descuentos_ley"]);
 	$this->set('mensajes_colectivos',    $datos[0]["Cnmd01"]["mensajes_colectivos"]);
 	$this->set('status1',    $datos[0]["Cnmd01"]["status_nomina"]);
 	$this->set('vacaciones_colectivas',    $datos[0]["Cnmd01"]["vacaciones_colectivas"]);
 	$this->set('dia',    $datos[0]["Cnmd01"]["dia_vaca"]);
 	$this->set('mes',    $datos[0]["Cnmd01"]["mes_vaca"]);

	$lista = $this->Cnmd01->generateList($this->condicion(), 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($this->Cnmd01->findCount($this->condicion())!=0){
		$this->concatenaN($lista, 'nomina');
	}else{
		$this->set('nomina', array());
	}


 }

 function guardarEditar($cod_tipo_nomina=null,$pagina=null){
 	$this->layout = "ajax";
 	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

	$lista = $this->Cnmd01->generateList($this->condicion(), 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($this->Cnmd01->findCount($this->condicion())!=0){
		$this->concatenaN($lista, 'nomina');
	}else{
		$this->set('nomina', array());
	}
//	pr($this->data);
 	if(!empty($this->data['cnmp01'])){
 		if($this->data['cnmp01']['vacaciones_colectivas']==1 && (empty($this->data['cnmp01']['dia_day'])||empty($this->data['cnmp01']['mes'])) ){
 			$this->set('errorMessage', 'Asegurese de seleccionar el dia y el mes');
 		}else{
	 			$denominacion = $this->data['cnmp01']['denominacion'];
		 		$denominacion_devengado = $this->data['cnmp01']['denominacion_devengado'];
		 		$clasificacion_personal = $this->data['cnmp01']['clasificacion_personal'];
		 		$frecuencia_cobro = $this->data['cnmp01']['frecuencia_cobro'];
		 		$dias_laborables =  $this->Formato1($this->data['cnmp01']['dias_laborables']);
		 		$Horas_laborables = $this->Formato1($this->data['cnmp01']['Horas_laborables']);
		 		$descuentos_ley = $this->data['cnmp01']['descuentos_ley'];
		 		$mensajes_colectivos = $this->data['cnmp01']['mensajes_colectivos'];
		 		$vacaciones_colectivas = $this->data['cnmp01']['vacaciones_colectivas'];
		 		if($this->data['cnmp01']['vacaciones_colectivas']==1){
	 				$dia = $this->data['cnmp01']['dia_day'];
		 			$mes = $this->data['cnmp01']['mes'];
	 			}else{
	 				$dia = 0;
		 			$mes = 0;
	 			}


		 		$this->set('cod_tipo_nomina', $cod_tipo_nomina);
		 		$this->set('denominacion', $denominacion);
		 		$this->set('denominacion_devengado', $denominacion_devengado);
		 		$this->set('clasificacion_personal', $clasificacion_personal);
		 		$this->set('frecuencia_cobro', $frecuencia_cobro);
		 		$this->set('dias_laborables', $dias_laborables);
		 		$this->set('Horas_laborables', $Horas_laborables);
		 		$this->set('descuentos_ley', $descuentos_ley);
		 		$this->set('mensajes_colectivos', $mensajes_colectivos);
		 		$this->set('vacaciones_colectivas',$vacaciones_colectivas);

				switch($frecuencia_cobro){
					case 1:
						$dias_cobro=1;
					break;
					case 2:
						$dias_cobro=7;
					break;
					case 3:
						$dias_cobro=15;
					break;
					case 4:
						$dias_cobro=30;
					break;
					case 5:
						$dias_cobro=60;
					break;
					case 6:
						$dias_cobro=90;
					break;

				}

				$sql = "UPDATE Cnmd01 set denominacion = '".$denominacion."', denominacion_devengado = '".$denominacion_devengado."', clasificacion_personal = '".$clasificacion_personal."', frecuencia_cobro = '".$frecuencia_cobro."', dias_laborables = '".$dias_laborables."', horas_laborables = '".$Horas_laborables."', descuentos_ley = '".$descuentos_ley."', mensajes_colectivos = '".$mensajes_colectivos."',dias_cobro='".$dias_cobro."',vacaciones_colectivas='".$vacaciones_colectivas."',dia_vaca='".$dia."',mes_vaca='".$mes."' WHERE ".$this->condicion()." and cod_tipo_nomina = ".$cod_tipo_nomina;
				$this->Cnmd01->execute($sql);

				$sql_update = "update cnmd06_fichas set horas_laborar='".$Horas_laborables."' where ".$this->condicion()." and cod_tipo_nomina = ".$cod_tipo_nomina;
		        $this->Cnmd01->execute($sql_update);

				$this->set('Message_existe', 'LOS DATOS FUER&Oacute;N MODIFICADOS CORRECTAMENTE');

 		}

 	}else{
 		$this->set('errorMessage',"Los datos no pudier&oacute;n ser modificados");
 	}

 	if(isset($pagina)){
		$this->consulta($pagina);
		$this->render('consulta');
 	}else{
		$this->principal($cod_tipo_nomina);
		$this->render('principal');
 	}

 }



 function eliminar($var1 = null,$pagina=null){
 	$this->layout = "ajax";
 	$clasif = array('1'=>'Empleados', '2'=>'Obreros', '3'=>'Militares Profesionales', '4'=>'Militares No Profesionales', '5'=>'Contratados Empleados', '16'=>'Contratados Obreros', '6'=>'Suplencias Empleados', '15'=>'Suplencias Obreros', '7'=>'Jubilados Empleados', '8'=>'Jubilados Obreros', '9'=>'Pensionados Empleados', '10'=>'Pensionados Obreros', '11'=>'Dietas', '12'=>'Comisión de Servicios', '13'=>'Becas', '14'=>'Ayuda','17'=>'Altos Funcionarios','18'=>'Elección Popular');
	$this->set('clasificacion', $clasif);
	$frec = array('1'=>'Diario', '2'=>'Semanal', '3'=>'Quincenal', '4'=>'Mensual', '5'=>'Bimensual', '6'=>'Trimestral');
	$this->set('frecuencia', $frec);
	$frec_pago = array('1'=>'1ra Semana', '2'=>'2da Semana', '3'=>'3ra Semana', '4'=>'4ta Semana', '5'=>'5ta Semana');
	$frec_pago2 = array('1ra Quincena', '2da Quincena', 'Ambas', 'Mes Completo', 'Pago Unico');
	$status = array('Pre-n&oacute;mina', 'Corrida Definitiva', 'Emisi&oacute;n de Recibos', 'Cierre');
	$this->set('frecuencia_pago', $frec_pago);
	$this->set('frecuencia_pago2', $frec_pago2);
	$this->set('status', $status);
	$this->set('enable', 'disabled');

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$datos = $this->Cnmd01->findAll($this->condicion().' and cod_tipo_nomina = '.$var1, null, null, null);
	$this->set('datos', $datos);
	$lista = $this->Cnmd01->generateList($this->condicion(), 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if($this->Cnmd01->findCount($this->condicion())!=0){
		$this->concatenaN($lista, 'nomina');
	}else{
		$this->set('nomina', array());
	}
 	if($var1 != null){
 		$sql = "DELETE FROM Cnmd01 WHERE ".$this->condicion()." and cod_tipo_nomina = ".$var1;
 		$this->Cnmd01->execute($sql);
 		$this->set('Message_existe', 'EL REGISTRO FU&Eacute; ELIMINADO EXITOSAMENTE');
 	}

	if(isset($pagina)){
		$this->consulta($pagina);
		$this->render('consulta');
 	}else{
		$this->index();
		$this->render('index');
 	}

 }



function consulta($pagina=null) {
	$this->layout="ajax";

	if(isset($pagina)){
		$Tfilas=$this->Cnmd01->findCount($this->condicion());
        if($Tfilas!=0){
        	$x=$this->Cnmd01->findAll($this->condicion(),null,"cod_tipo_nomina ASC",1,$pagina,null);

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
		$Tfilas=$this->Cnmd01->findCount($this->condicion());

        if($Tfilas!=0){
        	$x=$this->Cnmd01->findAll($this->condicion(),null,"cod_tipo_nomina ASC",1,$pagina,null);
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

	$sql2="select * from cnmd01 where ".$this->condicion()." and cod_tipo_nomina=".$x[0]["Cnmd01"]["cod_tipo_nomina"];
	$datos=$this->Cnmd01->execute($sql2);
	$this->set('datos',$datos);

	$clasificacion = array('1'=>'Empleados', '2'=>'Obreros', '3'=>'Militares Profesionales', '4'=>'Militares No Profesionales', '5'=>'Contratados Empleados', '16'=>'Contratados Obreros', '6'=>'Suplencias Empleados', '15'=>'Suplencias Obreros', '7'=>'Jubilados Empleados', '8'=>'Jubilados Obreros', '9'=>'Pensionados Empleados', '10'=>'Pensionados Obreros', '11'=>'Dietas', '12'=>'Comisión de Servicios', '13'=>'Becas', '14'=>'Ayuda','17'=>'Altos Funcionarios','18'=>'Elección Popular');
	$this->set('clasificacion', $clasificacion);

	$frecuencia = array('1'=>'Diario', '2'=>'Semanal', '3'=>'Quincenal', '4'=>'Mensual', '5'=>'Bimensual', '6'=>'Trimestral');
	$this->set('frecuencia', $frecuencia);

	$frec_pago = array('1'=>'1ra Semana', '2'=>'2da Semana', '3'=>'3ra Semana', '4'=>'4ta Semana', '5'=>'5ta Semana');
	$frec_pago2 = array('6'=>'1ra Quincena', '7'=>'2da Quincena', '8'=>'Ambas', '9'=>'Mes Completo', '10'=>'Pago Unico');
	$status = array('1'=>'Pre-n&oacute;mina','2'=> 'Corrida Definitiva','3'=> 'Emisi&oacute;n de Recibos', '0'=>'Cierre');

	$this->set('frecuencia_pago', $frec_pago);
	$this->set('frecuencia_pago2', $frec_pago2);
	$this->set('status', $status);

	if($this->cnmd06_fichas->findCount($this->condicion().' and cod_tipo_nomina = '.$x[0]["Cnmd01"]["cod_tipo_nomina"])!=0){
		$this->set('enable2', 'disabled');
	}else{
		$this->set('enable2', '');
	}

	$meses= array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
	$this->set('meses',$meses);

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


 function entrar_apertura(){
	$this->layout="ajax";
	if(isset($this->data['cugp05_restriccion_clave']['login']) && isset($this->data['cugp05_restriccion_clave']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cugp05_restriccion_clave']['login']);
		$paswd=addslashes($this->data['cugp05_restriccion_clave']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=113 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('mensajeError',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('autor_valido',false);
			$this->index("autor_valido");
			$this->render("index");
		}
	}
}

 function entrar_escenarios(){
	$this->layout="ajax";
	if(isset($this->data['cugp05_restriccion_clave']['login']) && isset($this->data['cugp05_restriccion_clave']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cugp05_restriccion_clave']['login']);
		$paswd=addslashes($this->data['cugp05_restriccion_clave']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=114 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('mensajeError',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('autor_valido',false);
			$this->index("autor_valido");
			$this->render("index");
		}
	}
}

 function entrar_expedientes(){
	$this->layout="ajax";
	if(isset($this->data['cugp05_restriccion_clave']['login']) && isset($this->data['cugp05_restriccion_clave']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cugp05_restriccion_clave']['login']);
		$paswd=addslashes($this->data['cugp05_restriccion_clave']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=115 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('mensajeError',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('autor_valido',false);
			$this->index("autor_valido");
			$this->render("index");
		}
	}
}

 function entrar_nominas(){
	$this->layout="ajax";
	if(isset($this->data['cugp05_restriccion_clave']['login']) && isset($this->data['cugp05_restriccion_clave']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cugp05_restriccion_clave']['login']);
		$paswd=addslashes($this->data['cugp05_restriccion_clave']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=116 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('mensajeError',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('autor_valido',false);
			$this->index("autor_valido");
			$this->render("index");
		}
	}
}

 function entrar_vacaciones(){
	$this->layout="ajax";
	if(isset($this->data['cugp05_restriccion_clave']['login']) && isset($this->data['cugp05_restriccion_clave']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cugp05_restriccion_clave']['login']);
		$paswd=addslashes($this->data['cugp05_restriccion_clave']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=117 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('mensajeError',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('autor_valido',false);
			$this->index("autor_valido");
			$this->render("index");
		}
	}
}

 function entrar_fideicomiso(){
	$this->layout="ajax";
	if(isset($this->data['cugp05_restriccion_clave']['login']) && isset($this->data['cugp05_restriccion_clave']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cugp05_restriccion_clave']['login']);
		$paswd=addslashes($this->data['cugp05_restriccion_clave']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=118 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('mensajeError',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('autor_valido',false);
			$this->index("autor_valido");
			$this->render("index");
		}
	}
}

 function entrar_utilitys(){
	$this->layout="ajax";
	if(isset($this->data['cugp05_restriccion_clave']['login']) && isset($this->data['cugp05_restriccion_clave']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cugp05_restriccion_clave']['login']);
		$paswd=addslashes($this->data['cugp05_restriccion_clave']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=119 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('mensajeError',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('autor_valido',false);
			$this->index("autor_valido");
			$this->render("index");
		}
	}
}


 }

 ?>