<?php

 class Cnmp15DevengadoController extends AppController{

    var $name = "cnmp15_devengado";
    var $uses = array('cnmd15_bono_vaca', 'cnmd15_aguinaldo', 'cnmd15_devengado', 'cnmd06_fichas', 'cnmd15_datos_personales','ccfd04_cierre_mes', 'Cnmd01', 'v_cnmd05');
    var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf','Form');


function checkSession(){
                if (!$this->Session->check('Usuario')){
                        $this->redirect('/salir/');
                        exit();
                }else{

$this->requestAction('/usuarios/actualizar_user');
                }//fin else
}//fin checksession


    function beforeFilter(){
                    $this->checkSession();

}






function index(){

     $this->layout = "ajax";
      $cod_presi = $this->Session->read('SScodpresi');
	  $cod_entidad = $this->Session->read('SScodentidad');
	  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	  $cod_inst = $this->Session->read('SScodinst');
	  $cod_dep = $this->Session->read('SScoddep');
	  $modulo = $this->Session->read('Modulo');
	  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
	  $ano = $this->ano_ejecucion();


	                                        $cod_dep_expediente     =  $this->Session->read('cod_dep_prestaciones');
							  				$cod_tipo_nomina        =  $this->Session->read('cod_tipo_nomina_prestaciones');
								  			$cod_cargo              =  $this->Session->read('cod_cargo_prestaciones');
								  			$cod_ficha              =  $this->Session->read('cod_ficha_prestaciones');
										    $cedula                 =  $this->Session->read('cedula_pestana_prestaciones');
											$this->set('cod_tipo_nomina',    $cod_tipo_nomina );
											$this->set('cod_cargo',          $cod_cargo );
											$this->set('cod_ficha',          $cod_ficha );
											$this->set('cedula',             $cedula );
											$deno_nomina = "";
											$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and Cnmd01.cod_tipo_nomina='$cod_tipo_nomina'", $order ="cod_tipo_nomina ASC");
										    $this->set('deno_nomina', $deno_nomina);
											  $cont = 0;
											  $lista2a = $this->cnmd15_datos_personales->findAll($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$cod_cargo.' and cod_ficha='.$cod_ficha.' and cedula_identidad='.$cedula );
													$primer_apellido       =     $lista2a[0]['cnmd15_datos_personales']['primer_apellido'];
													$segundo_apellido      =     $lista2a[0]['cnmd15_datos_personales']['segundo_apellido'];
													$primer_nombre         =     $lista2a[0]['cnmd15_datos_personales']['primer_nombre'];
													$segundo_nombre        =     $lista2a[0]['cnmd15_datos_personales']['segundo_nombre'];
													$institucion           =     $lista2a[0]['cnmd15_datos_personales']['institucion'];
													$dependencia           =     $lista2a[0]['cnmd15_datos_personales']['dependencia'];
													$cargo                 =     $lista2a[0]['cnmd15_datos_personales']['denominacion_cargo'];
													$this->set('primer_apellido',    $primer_apellido );
													$this->set('segundo_apellido',   $segundo_apellido );
													$this->set('primer_nombre',      $primer_nombre );
													$this->set('segundo_nombre',     $segundo_nombre );
													$this->set('institucion',        $institucion );
													$this->set('dependencia',        $dependencia );
													$this->set('cargo',              $cargo );

									// 	CALCULA ANOS DE EXPERIENCIA DEL TRABAJADOR:

											$execute_anos_exp = $this->cnmd15_datos_personales->execute("select calculos_incidencia('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$cod_tipo_nomina."', '".$cod_cargo."', '".$cod_ficha."', '".$cedula."');");
											$anos_experie = $execute_anos_exp!=null ? $execute_anos_exp[0][0]["calculos_incidencia"] : 0;

  											if($anos_experie!=null && $anos_experie!=0){
  												$this->set('anos_experiencia_adm', $anos_experie);
  											}else{
  												$this->set('anos_experiencia_adm', '');
  											}

											  $cont     = $this->cnmd15_devengado->findCount($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$cod_cargo.' and cod_ficha='.$cod_ficha.' and cedula_identidad='.$cedula );
											  $cont ++;
											  $cont_aux = $this->cnmd15_devengado->findAll($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$cod_cargo.' and cod_ficha='.$cod_ficha.' and cedula_identidad='.$cedula, null, "escala ASC");
                                              $fecha_aux = "";
										      $fecha_hasta = "";
												      if(isset($cont_aux[0]['cnmd15_devengado']['fecha_hasta'])){
														   foreach($cont_aux as $ve){
			                                                   $fecha_hasta = $ve['cnmd15_devengado']['fecha_hasta'];
														   }//fin
												      }//fin if
											  if($fecha_hasta!=""){
											  $ano = $fecha_hasta[0].$fecha_hasta[1].$fecha_hasta[2].$fecha_hasta[3];
											  $mes = $fecha_hasta[5].$fecha_hasta[6];
											  $dia = $fecha_hasta[8].$fecha_hasta[9];
											  $fecha_aux = $this->dateAdd(1,$dia,$mes,$ano);
											  }else{
											      $fecha_aux = $lista2a[0]['cnmd15_datos_personales']['fecha_ingreso'];
											      $ano = $fecha_aux[0].$fecha_aux[1].$fecha_aux[2].$fecha_aux[3];
												  $mes = $fecha_aux[5].$fecha_aux[6];
												  $dia = $fecha_aux[8].$fecha_aux[9];
												  $fecha_aux = $dia.'/'.$mes.'/'.$ano;
											  }//fin else
											  $this->set('fecha_aux',  $fecha_aux );
											  $this->Session->write('fecha_session_desde',$fecha_aux);
											  $this->set('escala',     $this->AddCeroR2($cont));
											  $accion =  $this->cnmd15_devengado->findAll($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$cod_cargo.' and cod_ficha='.$cod_ficha.' and cedula_identidad='.$cedula, null, 'escala DESC');
										      $this->set('accion', $accion);

}//fin function


function valor_sueldo_basico($var1=null){
  $this->layout = "ajax";
  $this->Session->write('sueldo_basico_calculo', $var1);
}


function guardar($var1=null){
  $this->layout = "ajax";
  $cod_presi     =  $this->Session->read('SScodpresi');
  $cod_entidad   =  $this->Session->read('SScodentidad');
  $cod_tipo_inst =  $this->Session->read('SScodtipoinst');
  $cod_inst      =  $this->Session->read('SScodinst');
  $cod_dep       =  $this->Session->read('SScoddep');
  $modulo        =  $this->Session->read('Modulo');
  $condicion     =  "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  $ano           =  $this->ano_ejecucion();
  $this->set('ano', $ano);
  $cont = 0;
  $cod_tipo_nomina     =  $this->data['cnmp15_devengado']['cod_nomina'];
  $codigo_cargo        =  $this->data['cnmp15_devengado']['codigo_cargo'];
  $codigo_ficha        =  $this->data['cnmp15_devengado']['codigo_ficha'];
  $cedula              =  $this->data['cnmp15_devengado']['cedula'];
  $escala              =  $this->data['cnmp15_devengado']['escala'];
  $fecha_desde         =  $this->Cfecha($this->data['cnmp15_devengado']['fecha_desde'], 'A-M-D');
  $fecha_hasta         =  $this->Cfecha($this->data['cnmp15_devengado']['fecha_hasta'], 'A-M-D');
  $sueldo_salario      =  $this->Formato1($this->data['cnmp15_devengado']['sueldo_salario']);
  $sueldo_total        =  $this->Formato1($this->data['cnmp15_devengado']['sueldo_total']);
  $sueldo_basico        =  $this->Formato1($this->data['cnmp15_devengado']['sueldo_basico']);

  $ano_antiguedad               =  $this->data['cnmp15_devengado']['ano_antiguedad'];
  $dias_mes_aguinaldo           =  $this->data['cnmp15_devengado']['dias_mes_aguinaldo'];
  $dias_mes_bonova              =  $this->data['cnmp15_devengado']['dias_mes_bonova'];
  $dias_mes_semana_adicional    =  $this->data['cnmp15_devengado']['dias_mes_semana_adicional'];

  $dias_mes_aguinaldo        = 0;
  $dias_mes_bonova           = 0;
  $dias_mes_semana_adicional = 0;

  $dias_escala_aguinaldo     =  $this->data['cnmp15_devengado']['dias_escala_aguinaldo'];
  $dias_escala_bonova        =  $this->data['cnmp15_devengado']['dias_escala_bonova'];
  $dias_semana_adicional     =  $this->data['cnmp15_devengado']['dias_semana_adicional'];

  $monto_mensual_aguinaldo          =  $this->Formato1($this->data['cnmp15_devengado']['monto_mensual_aguinaldo']);
  $monto_mensual_bonova             =  $this->Formato1($this->data['cnmp15_devengado']['monto_mensual_bonova']);
  $monto_mensual_semana_adicional   =  $this->Formato1($this->data['cnmp15_devengado']['monto_mensual_semana_adicional']);


  $ano = $fecha_hasta[0].$fecha_hasta[1].$fecha_hasta[2].$fecha_hasta[3];
  $mes = $fecha_hasta[5].$fecha_hasta[6];
  $dia = $fecha_hasta[8].$fecha_hasta[9];



$sw2  = $this->cnmd15_devengado->execute('BEGIN; ');
$cont = $this->cnmd15_devengado->findCount($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$codigo_cargo.' and cod_ficha='.$codigo_ficha.' and cedula_identidad='.$cedula.' and escala='.$escala);
$opcion = 'si';
if($cont==0){$op=1;
		$sql =" INSERT INTO cnmd15_devengado (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, escala, fecha_desde, fecha_hasta, sueldo_integral, sueldo_total, ano_antiguedad, dias_escala_aguinaldo, dias_mes_aguinaldo, monto_mensual_aguinaldo, dias_escala_bonova, dias_mes_bonova,monto_mensual_bonova, dias_semana_adicional, dias_mes_semana_adicional, monto_mensual_semana_adicional, sueldo_basico)";
		$sql.=" VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$cod_tipo_nomina."', '".$codigo_cargo."', '".$codigo_ficha."', '".$cedula."', '".$escala."', '".$fecha_desde."', '".$fecha_hasta."', '".$sueldo_salario."', '".$sueldo_total."', '".$ano_antiguedad."', '".$dias_escala_aguinaldo."', '".$dias_mes_aguinaldo."', '".$monto_mensual_aguinaldo."', '".$dias_escala_bonova."', '".$dias_mes_bonova."', '".$monto_mensual_bonova."', '".$dias_semana_adicional."', '".$dias_mes_semana_adicional."', '".$monto_mensual_semana_adicional."', '".$sueldo_basico."'); ";
}else{  $op=2;
        $sql = " UPDATE cnmd15_devengado SET fecha_desde='".$fecha_desde."', fecha_hasta='".$fecha_hasta."' where cod_presi = '".$cod_presi."' and cod_entidad  = '".$cod_entidad."' and  cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."'  and  cod_dep  = '".$cod_dep."' and  cod_tipo_nomina= '".$cod_tipo_nomina."' and cod_cargo= '".$codigo_cargo."' and cod_ficha= '".$codigo_ficha."' and cedula_identidad= '".$cedula."' and escala= '".$escala."' ";
}//fin else


if($opcion=='si'){
		$sw2 = $this->cnmd15_devengado->execute($sql);

					if($sw2>1){
		                $this->cnmd15_devengado->execute("COMMIT;");
		                $cont = 0;
                        $cont = $this->cnmd15_devengado->findCount($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$codigo_cargo.' and cod_ficha='.$codigo_ficha.' and cedula_identidad='.$cedula);
                        $cont++;
				        $this->set('Message_existe', 'Los datos fueron guardados correctamente');
				        $fecha_desde = $this->dateAdd(1,$dia,$mes,$ano);
					}else{
						$this->cnmd15_devengado->execute("ROLLBACK;");
						$cont = 0;
                        $cont = $this->cnmd15_devengado->findCount($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$codigo_cargo.' and cod_ficha='.$codigo_ficha.' and cedula_identidad='.$cedula);
				        $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
						$fecha_desde = "";
					}//fin else
}else{

						$this->cnmd15_devengado->execute("ROLLBACK;");
						$cont = 0;
                        $cont = $this->cnmd15_devengado->findCount($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$codigo_cargo.' and cod_ficha='.$codigo_ficha.' and cedula_identidad='.$cedula);
						$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
						$fecha_desde = "";

}//fin else

  //$accion =  $this->cnmd15_devengado->findAll($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$codigo_cargo.' and cod_ficha='.$codigo_ficha.' and cedula_identidad='.$cedula, null, 'ano DESC');
  //$this->set('accion', $accion);

  if($op==2){
        $this->set('cedula', $var1);
        $this->Session->write('fecha_session_desde',$fecha_desde);
        echo "<script>";
            echo "document.getElementById('escala').value='".$this->AddCeroR2($cont)."';";
			//echo "document.getElementById('sueldo_salario').readOnly=true;";
			echo "document.getElementById('compensaciones').readOnly=true;";
			echo "document.getElementById('id_sueldo_basico').value='0,00';";
			echo "document.getElementById('sueldo_salario').value='0,00';";
			echo "document.getElementById('compensaciones').value='0,00';";
			echo "document.getElementById('fecha_desde').value='".$fecha_desde."';";
			echo "document.getElementById('fecha_hasta').value='';";
		echo "</script>";

  }else{
  	     	   //$this->set('userTable', $this->requestAction('/cnmp15_parametro_cobro/', array('return')));
  	   	  	   $this->index();
		       $this->render('index');
  	   }//fin else



}//fin funtion





function cod_nomina2($cod_nomina=null){
	$this->layout="ajax";
	if($cod_nomina!=null){
		$deno_nomina = "";
		$this->set('cod_nomina', $cod_nomina);
		$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and Cnmd01.cod_tipo_nomina='$cod_nomina'", $order ="cod_tipo_nomina ASC");
		$this->set('deno_nomina', $deno_nomina);
	}//fin if
	echo "<script>";
		echo "document.getElementById('cod_nomina').value='".$this->AddCeroR2($cod_nomina)."';";
		echo "document.getElementById('deno_nomina').value='".$deno_nomina."';";
	echo "</script>";

}//fin function





function codigo_cargo($var=null){
  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  $ano = $this->ano_ejecucion();
  $sql="";
  $accion =  $this->cnmd15_devengado->findAll("cod_presi = '".$cod_presi."' and cod_entidad  = '".$cod_entidad."' and  cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."'  and  cod_dep  = '".$cod_dep."' and  cod_tipo_nomina= '".$var."'  ", null, null);
  foreach($accion as $ver){
      if($sql==""){$sql = "and ( cod_cargo =".$ver['cnmd15_devengado']['cod_cargo']." ";
      }else{       $sql .= "  or  cod_cargo =".$ver['cnmd15_devengado']['cod_cargo']." ";   }
  }//fin foreach
       if($sql!=""){$sql .= ")"; }
    //$lista2 = $this->v_cnmd05->generateList($conditions = $condicion.' and cod_tipo_nomina='.$var.$sql, $order = 'cod_tipo_nomina', $limit = null, '{n}.v_cnmd05.cod_cargo', '{n}.v_cnmd05.denominacion_clase');
	$lista2 = $this->cnmd15_datos_personales->generateList($conditions = $condicion.' and cod_tipo_nomina='.$var , $order = 'cod_tipo_nomina', $limit = null, '{n}.cnmd15_datos_personales.cod_cargo', '{n}.cnmd15_datos_personales.cod_cargo');

	$this->concatena($lista2, 'nomina3');
	$this->set('cod_tipo_nomina', $var);

	echo "<script>";
	    echo "document.getElementById('escala').value='';";
		echo "document.getElementById('cargo_ocupado').value='';";
		echo "document.getElementById('primer_apellido').value='';";
		echo "document.getElementById('segundo_apellido').value='';";
		echo "document.getElementById('primer_nombre').value='';";
		echo "document.getElementById('segundo_nombre').value='';";
		echo "document.getElementById('institucion').value='';";
		echo "document.getElementById('dependencia').value='';";
		echo "document.getElementById('sueldo_salario').readOnly=true;";
		echo "document.getElementById('compensaciones').readOnly=true;";
		echo "document.getElementById('sueldo_salario').value='0,00';";
		echo "document.getElementById('compensaciones').value='0,00';";
		echo "document.getElementById('fecha_desde').value='';";
		echo "document.getElementById('fecha_hasta').value='';";
  echo "</script>";


}//fin function





function codigo_ficha($var=null, $var2=null){
  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  $ano = $this->ano_ejecucion();


    $lista2 = $this->cnmd15_datos_personales->generateList($conditions = $condicion.' and cod_tipo_nomina='.$var.' and cod_cargo='.$var2 , $order = 'cod_tipo_nomina', $limit = null, '{n}.cnmd15_datos_personales.cod_ficha', '{n}.cnmd15_datos_personales.cod_ficha');
	$this->set('nomina5', $lista2);
	$this->set('cod_tipo_nomina', $var);
	$this->set('cod_cargo', $var2);


 echo "<script>";
        echo "document.getElementById('escala').value='';";
		echo "document.getElementById('cargo_ocupado').value='';";
		echo "document.getElementById('primer_apellido').value='';";
		echo "document.getElementById('segundo_apellido').value='';";
		echo "document.getElementById('primer_nombre').value='';";
		echo "document.getElementById('segundo_nombre').value='';";
		echo "document.getElementById('institucion').value='';";
		echo "document.getElementById('dependencia').value='';";
		echo "document.getElementById('sueldo_salario').readOnly=true;";
		echo "document.getElementById('compensaciones').readOnly=true;";
		echo "document.getElementById('sueldo_salario').value='0,00';";
		echo "document.getElementById('compensaciones').value='0,00';";
		echo "document.getElementById('fecha_desde').value='';";
		echo "document.getElementById('fecha_hasta').value='';";
  echo "</script>";


}//fin function




function calcular_compensaciones($var=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null){
  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  $ano = $this->ano_ejecucion();
  $opcion = 0;


//  $sueldo_basico_calculo        =  $this->Formato1($this->data['cnmp15_devengado']['sueldo_basico']);

  if($var6==null){
				  $cod_dep_expediente     =  $this->Session->read('cod_dep_prestaciones');
  				  $cod_tipo_nomina        =  $this->Session->read('cod_tipo_nomina_prestaciones');
	  			  $codigo_cargo           =  $this->Session->read('cod_cargo_prestaciones');
	  			  $codigo_ficha           =  $this->Session->read('cod_ficha_prestaciones');
			      $cedula                 =  $this->Session->read('cedula_pestana_prestaciones');
			      $sueldo_basico          =  $this->Formato1($this->Session->read('sueldo_basico_calculo'));
				  $escala                 =  $this->data['cnmp15_devengado']['escala'];
				  $fecha_desde            =  $this->Cfecha($this->Session->read('fecha_session_desde'), 'A-M-D');
				  $fecha_hasta            =  $this->Cfecha($this->Session->read('fecha_session_hasta'), 'A-M-D');
				  $sueldo_salario         =  $this->Formato1($var);
				  $opcion = 1;
  }else{
				  $cod_dep_expediente     =  $this->Session->read('cod_dep_prestaciones');
  				  $cod_tipo_nomina        =  $this->Session->read('cod_tipo_nomina_prestaciones');
	  			  $codigo_cargo           =  $this->Session->read('cod_cargo_prestaciones');
	  			  $codigo_ficha           =  $this->Session->read('cod_ficha_prestaciones');
			      $cedula                 =  $this->Session->read('cedula_pestana_prestaciones');
			      $sueldo_basico          =  $this->Formato1($this->Session->read('sueldo_basico_calculo'));
				  $escala                 =  $var5;
				  $accion =  $this->cnmd15_devengado->findAll("cod_presi = '".$cod_presi."' and cod_entidad  = '".$cod_entidad."' and  cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."'  and  cod_dep  = '".$cod_dep."' and  cod_tipo_nomina= '".$cod_tipo_nomina."' and cod_cargo= '".$codigo_cargo."' and cod_ficha= '".$codigo_ficha."' and cedula_identidad= '".$cedula."' and escala= '".$escala."' ", null, null);
				  $fecha_desde = $accion[0]['cnmd15_devengado']['fecha_desde'];
				  $fecha_hasta = $accion[0]['cnmd15_devengado']['fecha_hasta'];
				  $sueldo_salario      =  $this->Formato1($var6);
				  $opcion = 2;
  }//fin else


  $dias_year           =  0;
  $compensaciones      =  $sueldo_salario;
  $dias_aginaldo       =  0;
  $dias_antiguedad     =  0;
  $aux_a               =  0;
  $aux_b               =  0;
  $aux_c               =  0;
  $diferencia          =  0;

  $fecha_desde_aux = $fecha_desde;
  $ano_desde       = $fecha_desde_aux[0].$fecha_desde_aux[1].$fecha_desde_aux[2].$fecha_desde_aux[3];
  $mes_desde       = $fecha_desde_aux[5].$fecha_desde_aux[6];
  $dia_desde       = $fecha_desde_aux[8].$fecha_desde_aux[9];

  $fecha_hasta_aux = $fecha_hasta;
  $ano_hasta       = $fecha_hasta_aux[0].$fecha_hasta_aux[1].$fecha_hasta_aux[2].$fecha_hasta_aux[3];
  $mes_hasta       = $fecha_hasta_aux[5].$fecha_hasta_aux[6];
  $dia_hasta       = $fecha_hasta_aux[8].$fecha_hasta_aux[9];

$cont_aux2               =   $this->Cnmd01->findAll($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina);
$clasificacion_personal  =   $cont_aux2[0]['Cnmd01']['clasificacion_personal'];

  $cont_aux      = $this->cnmd15_datos_personales->findAll($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$codigo_cargo.' and cod_ficha='.$codigo_ficha.' and cedula_identidad='.$cedula);
  $fecha_ingreso = $cont_aux[0]["cnmd15_datos_personales"]["fecha_ingreso"];
  $execute_1     = $this->cnmd15_datos_personales->execute("select devolver_edad('".$fecha_hasta."', '".$fecha_ingreso."', 'ANO');");
  $execute_m     = $this->cnmd15_datos_personales->execute("select devolver_edad('".$fecha_hasta."', '".$fecha_ingreso."', 'MES');");

  $antiguedad = $execute_1[0][0]["devolver_edad"];
  $mes_a = $execute_m[0][0]["devolver_edad"];

  if ($antiguedad>=1 && $mes_a>=1){$antiguedad+1;}

  $execute_2     = $this->cnmd15_datos_personales->execute("select calculos_incidencia('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$cod_tipo_nomina."', '".$codigo_cargo."', '".$codigo_ficha."', '".$cedula."');");

  if($execute_2!=null){
  	  	$antiguedad    = $antiguedad + $execute_2[0][0]["calculos_incidencia"];
  }

  $execute_3       = $this->cnmd15_datos_personales->execute("select a.dias, a.basico_bono_vac from v_cnmd15_bono_vaca a where ".$condicion.' and cod_tipo_nomina='.$cod_tipo_nomina." and ('$fecha_hasta' >= a.fecha_desde_bono_vaca::date and '$fecha_hasta' <= a.fecha_hasta_bono_vaca::date) and ('$antiguedad' >= a.desde_antiguedad and '$antiguedad' <= a.hasta_antiguedad)  order BY dias ASC limit 1; ");
  $dias_antiguedad = isset($execute_3[0][0]["dias"])?$execute_3[0][0]["dias"]:0;
  $basico_bono_vac = $execute_3[0][0]["basico_bono_vac"];

  $execute_4     = $this->cnmd15_datos_personales->execute("select a.dias, a.basico_agui from v_cnmd15_aguinaldo a where ".$condicion.' and cod_tipo_nomina='.$cod_tipo_nomina." and ('$fecha_hasta' >= a.fecha_desde_aguinaldo::date and '$fecha_hasta' <= a.fecha_hasta_aguinaldo::date) and ('$antiguedad' >= a.desde_antiguedad and '$antiguedad' <= a.hasta_antiguedad)  order BY dias ASC limit 1; ");
  $dias_aginaldo = isset($execute_4[0][0]["dias"])?$execute_4[0][0]["dias"]:0;
  $basico_agui = $execute_4[0][0]["basico_agui"];

  $execute_5       = $this->cnmd15_datos_personales->execute("select a.dias, a.basico_sem_salario from v_cnmd15_semana_salarial a where ".$condicion.' and cod_tipo_nomina='.$cod_tipo_nomina." and ('$fecha_hasta' >= a.fecha_desde_bono_vaca::date and '$fecha_hasta' <= a.fecha_hasta_bono_vaca::date) and ('$antiguedad' >= a.desde_antiguedad and '$antiguedad' <= a.hasta_antiguedad)  order BY dias ASC limit 1; ");
  $dias_semana_adicional = isset($execute_5[0][0]["dias"])?$execute_5[0][0]["dias"]:0;
  $basico_sem_salario = $execute_5[0][0]["basico_sem_salario"];

//echo "<script>alert('Fecha hasta  ".$fecha_hasta."')</script>";
  
  if ($fecha_hasta>='2012-05-08'){
	if($basico_agui==1){
		$aux_a = $sueldo_basico;
	}else{
		$aux_a = $sueldo_salario;
	}

	$aux_c = (($aux_a/30) * (($dias_aginaldo+$dias_antiguedad+$dias_semana_adicional)/12));

	if($basico_bono_vac==1){
		$aux_a = $sueldo_basico;
	}else{
		$aux_a = $sueldo_salario;
	}

    $aux_b = (($aux_a/30) * ($dias_antiguedad/12));

	if($basico_sem_salario==1){
		$aux_a = $sueldo_basico;
	}else{
		$aux_a = $sueldo_salario;
	}

    $monto_mensual_semana_adicional = (($aux_a/30) * ($dias_semana_adicional/12));

	$dias_aginaldo2   = ($dias_aginaldo/12);
	$dias_antiguedad2 = ($dias_antiguedad/12);
	$dias_mes_semana_adicional = ($dias_semana_adicional/12);

$compensaciones += ($aux_b + $aux_c+$monto_mensual_semana_adicional);
  }else {
      	
      $aux_c=0;
      $aux_b=0;
      $antiguedad=0;
      $dias_antiguedad=0;
      $dias_antiguedad2=0;
      $dias_aginaldo=0;
      $dias_aginaldo2=0;
      $dias_mes_semana_adicional=0;
      $dias_semana_adicional=0;
      $monto_mensual_semana_adicional=0;
      $compensaciones = $sueldo_salario;
  }
			if($opcion ==1){
				          echo "<script>moneda('sueldo_salario');";

				          		echo "redondeocampo_sueldo_basico('id_sueldo_basico');";

				                echo "document.getElementById('calculo_1').value='".$antiguedad."';";
				                echo "document.getElementById('calculo_2').value='".$dias_aginaldo2."';";
				                echo "document.getElementById('calculo_3').value='".$dias_antiguedad2."';";
				                echo "document.getElementById('calculo_4').value='".$dias_mes_semana_adicional."';";

				                echo "document.getElementById('calculo_5').value='".$dias_aginaldo."';";
				                echo "document.getElementById('calculo_6').value='".$this->Formato2($aux_c)."';";

				                echo "document.getElementById('calculo_7').value='".$dias_antiguedad."';";
				                echo "document.getElementById('calculo_8').value='".$this->Formato2($aux_b)."';";

				                echo "document.getElementById('calculo_9').value='".$dias_semana_adicional."';";
				                echo "document.getElementById('calculo_10').value='".$this->Formato2($monto_mensual_semana_adicional)."';";

							  	echo "document.getElementById('compensaciones').value='".$this->Formato2($compensaciones)."';";
						  echo "</script>";
			}else{

						   echo "<script>moneda('sueldo_salario_".$escala."');";

						   		echo "redondeocampo_sueldo_basico('id_sueldo_basico');";

				                echo "document.getElementById('calculo_1_".$escala."').value='".$antiguedad."';";
				                echo "document.getElementById('calculo_2_".$escala."').value='".$dias_aginaldo2."';";
				                echo "document.getElementById('calculo_3_".$escala."').value='".$dias_antiguedad2."';";
				                echo "document.getElementById('calculo_4_".$escala."').value='".$dias_mes_semana_adicional."';";

				                echo "document.getElementById('calculo_5_".$escala."').value='".$dias_aginaldo."';";
				                echo "document.getElementById('calculo_6_".$escala."').value='".$this->Formato2($aux_c)."';";
				                echo "document.getElementById('calculo_7_".$escala."').value='".$dias_antiguedad."';";
				                echo "document.getElementById('calculo_8_".$escala."').value='".$this->Formato2($aux_b)."';";

				                echo "document.getElementById('calculo_9_".$escala."').value='".$dias_semana_adicional."';";
				                echo "document.getElementById('calculo_10_".$escala."').value='".$this->Formato2($monto_mensual_semana_adicional)."';";

							  	echo "document.getElementById('compensaciones_".$escala."').value='".$this->Formato2($compensaciones)."';";
						  echo "</script>";
			}//fin else
}//fin function

/*

CREATE OR REPLACE VIEW v_cnmd15_aguinaldo AS
select
		  a.cod_presi,
		  a.cod_entidad,
		  a.cod_tipo_inst,
		  a.cod_inst,
		  a.cod_dep,
		  a.cod_tipo_nomina,
		  a.escala,
			  a.desde_dia,
			  a.desde_mes,
			  a.desde_ano,
			  	(a.desde_ano || '-' || a.desde_mes || '-' || a.desde_dia) as fecha_desde_aguinaldo,
			  a.hasta_dia,
			  a.hasta_mes,
			  a.hasta_ano,
			  	(a.hasta_ano || '-' || a.hasta_mes || '-' || a.hasta_dia) as fecha_hasta_aguinaldo,
		  a.desde_antiguedad,
		  a.hasta_antiguedad,
          a.dias
  from cnmd15_aguinaldo a;
ALTER TABLE v_cnmd15_aguinaldo OWNER TO sisap;



CREATE OR REPLACE VIEW v_cnmd15_bono_vaca AS
select
		  a.cod_presi,
		  a.cod_entidad,
		  a.cod_tipo_inst,
		  a.cod_inst,
		  a.cod_dep,
		  a.cod_tipo_nomina,
		  a.escala,
			  a.desde_dia,
			  a.desde_mes,
			  a.desde_ano,
			  	(a.desde_ano || '-' || a.desde_mes || '-' || a.desde_dia) as fecha_desde_bono_vaca,
			  a.hasta_dia,
			  a.hasta_mes,
			  a.hasta_ano,
			  	(a.hasta_ano || '-' || a.hasta_mes || '-' || a.hasta_dia) as fecha_hasta_bono_vaca,
		  a.desde_antiguedad,
		  a.hasta_antiguedad,
          a.dias
  from cnmd15_bono_vaca a;
ALTER TABLE v_cnmd15_bono_vaca OWNER TO sisap;

--
-- Name: devolver_anos_experiencia(integer, integer, integer, integer, integer, integer, integer, integer); Type: FUNCTION; Schema: public; Owner: sisap
--

CREATE FUNCTION devolver_anos_experiencia(integer, integer, integer, integer, integer, integer, integer, integer) RETURNS integer
    LANGUAGE plpgsql
    AS $_$
DECLARE
   Pcod_presi            alias for $1;
   Pcod_entidad          alias for $2;
   Pcod_tipo_inst        alias for $3;
   Pcod_inst             alias for $4;
   Pcod_dep              alias for $5;
   Pcod_tipo_nomina      alias for $6;
   Pcod_cargo            alias for $7;
   Pcod_ficha            alias for $8;
   anos_experiencia  integer = 0;

BEGIN

 anos_experiencia=(SELECT sum(devolver_edad(x.fecha_egreso,x.fecha_ingreso,'ano')) FROM cnmd06_datos_experiencia_administrativa x where x.cedula=(SELECT z.cedula_identidad FROM cnmd06_fichas z WHERE z.cod_presi=Pcod_presi AND z.cod_entidad=Pcod_entidad AND z.cod_tipo_inst=Pcod_tipo_inst AND z.cod_inst=Pcod_inst AND z.cod_dep=Pcod_dep AND z.cod_tipo_nomina=Pcod_tipo_nomina AND z.cod_cargo=Pcod_cargo AND z.cod_ficha=Pcod_ficha));
 if anos_experiencia is null then
	anos_experiencia = 0;
 end if;
RETURN anos_experiencia;
END;
$_$;


ALTER FUNCTION public.devolver_anos_experiencia(integer, integer, integer, integer, integer, integer, integer, integer) OWNER TO sisap;


*/

function calcular($var=null, $var2=null, $var3=null, $var4=null){

  $this->layout = "ajax";

}//fin function




function funcion($var=null, $var2=null, $var3=null, $var4=null){

  $this->layout = "ajax";

}//fin function




function fecha_session_hasta($var=null, $var2=null, $var3=null, $var4=null){

  $this->layout = "ajax";
  $this->Session->write('fecha_session_hasta', $var."/".$var2."/".$var3);





  $this->render('funcion');
}//fin function





function fecha_session_desde($var=null, $var2=null, $var3=null, $var4=null){

  $this->layout = "ajax";
  $this->Session->write('fecha_session_desde', $var."/".$var2."/".$var3);
  $this->render('funcion');

}//fin function





function sueldo_input($var=null, $var2=null, $var3=null, $var4=null){

  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  $ano = $this->ano_ejecucion();

    $this->set('cod_tipo_nomina', $var);
	$this->set('cod_cargo', $var2);
	$this->set('cod_ficha', $var3);
	$this->set('cedula', $var4);


}//fin function







function cedula($var=null, $var2=null, $var3=null){
  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  $ano = $this->ano_ejecucion();

    $lista2 = $this->cnmd15_datos_personales->generateList($conditions = $condicion.' and cod_tipo_nomina='.$var.' and cod_cargo='.$var2.' and cod_ficha='.$var3 , $order = 'cod_tipo_nomina', $limit = null, '{n}.cnmd15_datos_personales.cedula_identidad', '{n}.cnmd15_datos_personales.cedula_identidad');
	$this->set('nomina5', $lista2);
	$this->set('cod_tipo_nomina', $var);
	$this->set('cod_cargo', $var2);
	$this->set('cod_ficha', $var3);


	echo "<script>";
	    echo "document.getElementById('escala').value='';";
		echo "document.getElementById('cargo_ocupado').value='';";
		echo "document.getElementById('primer_apellido').value='';";
		echo "document.getElementById('segundo_apellido').value='';";
		echo "document.getElementById('primer_nombre').value='';";
		echo "document.getElementById('segundo_nombre').value='';";
		echo "document.getElementById('institucion').value='';";
		echo "document.getElementById('dependencia').value='';";
		echo "document.getElementById('sueldo_salario').readOnly=true;";
		echo "document.getElementById('compensaciones').readOnly=true;";
		echo "document.getElementById('sueldo_salario').value='0,00';";
		echo "document.getElementById('compensaciones').value='0,00';";
		echo "document.getElementById('fecha_desde').value='';";
		echo "document.getElementById('fecha_hasta').value='';";
  echo "</script>";

}//fin function










function datos_personales($var=null, $var2=null, $var3=null, $var4=null){

  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  $ano = $this->ano_ejecucion();
  $cont = 0;
  $lista2 = $this->cnmd15_datos_personales->findAll($condicion.' and cod_tipo_nomina='.$var.' and cod_cargo='.$var2.' and cod_ficha='.$var3.' and cedula_identidad='.$var4 );
  $cont     = $this->cnmd15_devengado->findCount($condicion.' and cod_tipo_nomina='.$var.' and cod_cargo='.$var2.' and cod_ficha='.$var3.' and cedula_identidad='.$var4 );
  $cont_aux = $this->cnmd15_devengado->findAll($condicion.' and cod_tipo_nomina='.$var.' and cod_cargo='.$var2.' and cod_ficha='.$var3.' and cedula_identidad='.$var4.' and escala='.$cont);
  $cont ++;
  $fecha_aux = "";
  $fecha_hasta = $cont_aux[0]['cnmd15_devengado']['fecha_hasta'];
  if($fecha_hasta!=""){
  $ano = $fecha_hasta[0].$fecha_hasta[1].$fecha_hasta[2].$fecha_hasta[3];
  $mes = $fecha_hasta[5].$fecha_hasta[6];
  $dia = $fecha_hasta[8].$fecha_hasta[9];
  $fecha_aux = $this->dateAdd(1,$dia,$mes,$ano);

  }else{
    $fecha_aux = $lista2[0]['cnmd15_datos_personales']['fecha_ingreso'];
      $ano = $fecha_aux[0].$fecha_aux[1].$fecha_aux[2].$fecha_aux[3];
	  $mes = $fecha_aux[5].$fecha_aux[6];
	  $dia = $fecha_aux[8].$fecha_aux[9];
	  $fecha_aux = $dia.'/'.$mes.'/'.$ano;
  }//fin else

  echo "<script>";
        echo "document.getElementById('escala').value='".$this->AddCeroR2($cont)."';";
		echo "document.getElementById('cargo_ocupado').value='".$lista2[0]['cnmd15_datos_personales']['denominacion_cargo']."';";
		echo "document.getElementById('primer_apellido').value='".$lista2[0]['cnmd15_datos_personales']['primer_apellido']."';";
		echo "document.getElementById('segundo_apellido').value='".$lista2[0]['cnmd15_datos_personales']['segundo_apellido']."';";
		echo "document.getElementById('primer_nombre').value='".$lista2[0]['cnmd15_datos_personales']['primer_nombre']."';";
		echo "document.getElementById('segundo_nombre').value='".$lista2[0]['cnmd15_datos_personales']['segundo_nombre']."';";
		echo "document.getElementById('institucion').value='".$lista2[0]['cnmd15_datos_personales']['institucion']."';";
		echo "document.getElementById('dependencia').value='".$lista2[0]['cnmd15_datos_personales']['dependencia']."';";
		echo "document.getElementById('sueldo_salario').readOnly=false;";
		//echo "document.getElementById('compensaciones').readOnly=false;";
		echo "document.getElementById('fecha_desde').value='".$fecha_aux."';";
	    echo "document.getElementById('fecha_hasta').value='';";
  echo "</script>";



}//fin function





function consulta($var=null, $var2=null, $var3=null, $var4=null){

  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  $ano = $this->ano_ejecucion();

  if($var4!=null){
	  $accion =  $this->cnmd15_devengado->findAll($condicion.' and cod_tipo_nomina='.$var.' and cod_cargo='.$var2.' and cod_ficha='.$var3.' and cedula_identidad='.$var4, null, 'escala DESC');
	  $this->set('accion', $accion);
  }//fin


}//fin function





















function guardar_editar($var1=null){
  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  $ano = $this->ano_ejecucion();
  $this->set('ano', $ano);
  $cont = 0;
  $cod_tipo_nomina     =  $this->data['cnmp15_devengado']['cod_nomina_'.$var1];
  $codigo_cargo        =  $this->data['cnmp15_devengado']['codigo_cargo_'.$var1];
  $codigo_ficha        =  $this->data['cnmp15_devengado']['codigo_ficha_'.$var1];
  $cedula              =  $this->data['cnmp15_devengado']['cedula_'.$var1];
  $escala              =  $this->data['cnmp15_devengado']['escala_'.$var1];
  $fecha_desde         =  $this->Cfecha($this->data['cnmp15_devengado']['fecha_desde_'.$var1], 'A-M-D');
  $fecha_hasta         =  $this->Cfecha($this->data['cnmp15_devengado']['fecha_hasta_'.$var1], 'A-M-D');
  $sueldo_salario      =  $this->Formato1($this->data['cnmp15_devengado']['sueldo_salario_'.$var1]);
  $compensaciones      =  $this->Formato1($this->data['cnmp15_devengado']['compensaciones_'.$var1]);

  $ano_antiguedad               =  $this->data['cnmp15_devengado']['ano_antiguedad_'.$var1];
  $dias_mes_aguinaldo           =  $this->data['cnmp15_devengado']['dias_mes_aguinaldo_'.$var1];
  $dias_mes_bonova              =  $this->data['cnmp15_devengado']['dias_mes_bonova_'.$var1];
  $dias_mes_semana_adicional    =  $this->data['cnmp15_devengado']['dias_mes_semana_adicional_'.$var1];

  $dias_mes_aguinaldo        = 0;
  $dias_mes_bonova           = 0;
  $dias_mes_semana_adicional = 0;

  $dias_escala_aguinaldo     =  $this->data['cnmp15_devengado']['dias_escala_aguinaldo_'.$var1];
  $dias_escala_bonova        =  $this->data['cnmp15_devengado']['dias_escala_bonova_'.$var1];
  $dias_semana_adicional     =  $this->data['cnmp15_devengado']['dias_semana_adicional_'.$var1];

  $monto_mensual_aguinaldo          =  $this->Formato1($this->data['cnmp15_devengado']['monto_mensual_aguinaldo_'.$var1]);
  $monto_mensual_bonova             =  $this->Formato1($this->data['cnmp15_devengado']['monto_mensual_bonova_'.$var1]);
  $monto_mensual_semana_adicional   =  $this->Formato1($this->data['cnmp15_devengado']['monto_mensual_semana_adicional_'.$var1]);







$sw2  = $this->cnmd15_devengado->execute('BEGIN; ');
$cont = $this->cnmd15_devengado->findCount($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$codigo_cargo.' and cod_ficha='.$codigo_ficha.' and cedula_identidad='.$cedula.' and escala='.$escala);
$opcion = 'si';
if($cont==0){
		$sql =" INSERT INTO cnmd15_devengado (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, escala, fecha_desde, fecha_hasta, sueldo_basico,  compensaciones)";
		$sql.="VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$cod_tipo_nomina."', '".$codigo_cargo."', '".$codigo_ficha."', '".$cedula."', '".$escala."', '".$fecha_desde."', '".$fecha_hasta."', '".$sueldo_salario."', '".$compensaciones."'); ";
}else{
        $sql = " UPDATE cnmd15_devengado SET ano_antiguedad='".$ano_antiguedad."', dias_mes_aguinaldo='".$dias_mes_aguinaldo."', dias_mes_bonova='".$dias_mes_bonova."', dias_mes_semana_adicional='".$dias_mes_semana_adicional."', dias_escala_aguinaldo='".$dias_escala_aguinaldo."', dias_escala_bonova='".$dias_escala_bonova."', dias_semana_adicional='".$dias_semana_adicional."', monto_mensual_aguinaldo='".$monto_mensual_aguinaldo."', monto_mensual_bonova='".$monto_mensual_bonova."', monto_mensual_semana_adicional='".$monto_mensual_semana_adicional."', sueldo_integral='".$sueldo_salario."', sueldo_total='".$compensaciones."' where cod_presi = '".$cod_presi."' and cod_entidad  = '".$cod_entidad."' and  cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."'  and  cod_dep  = '".$cod_dep."' and  cod_tipo_nomina= '".$cod_tipo_nomina."' and cod_cargo= '".$codigo_cargo."' and cod_ficha= '".$codigo_ficha."' and cedula_identidad= '".$cedula."' and escala= '".$escala."' ";
}//fin else

$cont = 0;
$cont = $this->cnmd15_devengado->findCount($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$codigo_cargo.' and cod_ficha='.$codigo_ficha.' and cedula_identidad='.$cedula);

		  $fecha_desde_aux  = $fecha_desde[8].$fecha_desde[9].'/'.$fecha_desde[5].$fecha_desde[6].'/'.$fecha_desde[0].$fecha_desde[1].$fecha_desde[2].$fecha_desde[3];
		  $fecha_hasta_aux  = $fecha_hasta[8].$fecha_hasta[9].'/'.$fecha_hasta[5].$fecha_hasta[6].'/'.$fecha_hasta[0].$fecha_hasta[1].$fecha_hasta[2].$fecha_hasta[3];
          $ano = $fecha_hasta[0].$fecha_hasta[1].$fecha_hasta[2].$fecha_hasta[3];
		  $mes = $fecha_hasta[5].$fecha_hasta[6];
		  $dia = $fecha_hasta[8].$fecha_hasta[9];

if($opcion=='si'){
		$sw2 = $this->cnmd15_devengado->execute($sql);

					if($sw2>1){
		                $this->cnmd15_devengado->execute("COMMIT;"); $cont++;
				        $this->set('Message_existe', 'Los datos fueron guardados correctamente');
				        $fecha_hasta_aux = $this->dateAdd(1,$dia,$mes,$ano);
					}else{
						$this->cnmd15_devengado->execute("ROLLBACK;");
						$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
						$fecha_hasta_aux = "";
					}//fin else
}else{

						$this->cnmd15_devengado->execute("ROLLBACK;");
						$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
						$fecha_hasta_aux = "";

}//fin else

        echo "<script>";
            echo "document.getElementById('escala').value='".$this->AddCeroR2($cont)."';";
			echo "document.getElementById('sueldo_salario').readOnly=false;";
			echo "document.getElementById('compensaciones').readOnly=true;";
			echo "document.getElementById('sueldo_salario').value='0,00';";
			echo "document.getElementById('compensaciones').value='0,00';";
			echo "document.getElementById('fecha_desde').value='".$fecha_hasta_aux."';";
			echo "document.getElementById('fecha_hasta').value='';";
		echo "</script>";
		  $accion =  $this->cnmd15_devengado->findAll("cod_presi = '".$cod_presi."' and cod_entidad  = '".$cod_entidad."' and  cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."'  and  cod_dep  = '".$cod_dep."' and  cod_tipo_nomina= '".$cod_tipo_nomina."' and cod_cargo= '".$codigo_cargo."' and cod_ficha= '".$codigo_ficha."' and cedula_identidad= '".$cedula."' and escala= '".$escala."' ", null, null);
		  $fecha_desde2 = $accion[0]['cnmd15_devengado']['fecha_desde'];
		  $fecha_hasta2 = $accion[0]['cnmd15_devengado']['fecha_hasta'];
		  $fecha_desde_aux = $fecha_desde2[8].$fecha_desde2[9].'/'.$fecha_desde2[5].$fecha_desde2[6].'/'.$fecha_desde2[0].$fecha_desde2[1].$fecha_desde2[2].$fecha_desde2[3];
		  $fecha_hasta_aux = $fecha_hasta2[8].$fecha_hasta2[9].'/'.$fecha_hasta2[5].$fecha_hasta2[6].'/'.$fecha_hasta2[0].$fecha_hasta2[1].$fecha_hasta2[2].$fecha_hasta2[3];
		 echo "<script>";
            echo "document.getElementById('td_1_".$var1."').innerHTML='".$this->AddCeroR2($accion[0]['cnmd15_devengado']['escala'])."';";
			echo "document.getElementById('td_4_".$var1."').innerHTML='".$this->Formato2($accion[0]['cnmd15_devengado']['sueldo_integral'])."';";
			echo "document.getElementById('td_11_".$var1."').innerHTML='".$this->Formato2($accion[0]['cnmd15_devengado']['sueldo_total'])."';";
			echo "document.getElementById('td_2_".$var1."').innerHTML='".$fecha_desde_aux."';";
			echo "document.getElementById('td_3_".$var1."').innerHTML='".$fecha_hasta_aux."';";
			echo "document.getElementById('iconos_1_".$var1."').style.display = 'block'; ";
            echo "document.getElementById('iconos_2_".$var1."').style.display = 'none'; ";


  $ano_antiguedad            = $accion[0]['cnmd15_devengado']['ano_antiguedad'];
  $dias_mes_aguinaldo        = $accion[0]['cnmd15_devengado']['dias_mes_aguinaldo'];
  $dias_mes_bonova           = $accion[0]['cnmd15_devengado']['dias_mes_bonova'];
  $dias_mes_semana_adicional = $accion[0]['cnmd15_devengado']['dias_mes_semana_adicional'];


  $dias_escala_aguinaldo   = isset($accion[0]['cnmd15_devengado']['dias_escala_aguinaldo'])?$accion[0]['cnmd15_devengado']['dias_escala_aguinaldo']:0;
  $monto_mensual_aguinaldo = isset($accion[0]['cnmd15_devengado']['monto_mensual_aguinaldo'])?$accion[0]['cnmd15_devengado']['monto_mensual_aguinaldo']:0;

  $dias_escala_bonova   = isset($accion[0]['cnmd15_devengado']['dias_escala_bonova'])?$accion[0]['cnmd15_devengado']['dias_escala_bonova']:0;
  $monto_mensual_bonova = isset($accion[0]['cnmd15_devengado']['monto_mensual_bonova'])?$accion[0]['cnmd15_devengado']['monto_mensual_bonova']:0;

  $dias_semana_adicional          = isset($accion[0]['cnmd15_devengado']['dias_semana_adicional'])?$accion[0]['cnmd15_devengado']['dias_semana_adicional']:0;
  $monto_mensual_semana_adicional = isset($accion[0]['cnmd15_devengado']['monto_mensual_semana_adicional'])?$accion[0]['cnmd15_devengado']['monto_mensual_semana_adicional']:0;

          echo "document.getElementById('td_5_".$var1."').innerHTML=' ".$dias_escala_aguinaldo."'; ";
          echo "document.getElementById('td_6_".$var1."').innerHTML=' ".$this->Formato2($monto_mensual_aguinaldo)."'; ";

          echo "document.getElementById('td_7_".$var1."').innerHTML=' ".$dias_escala_bonova."'; ";
          echo "document.getElementById('td_8_".$var1."').innerHTML=' ".$this->Formato2($monto_mensual_bonova)."'; ";

          echo "document.getElementById('td_9_".$var1."').innerHTML=' ".$dias_semana_adicional."'; ";
          echo "document.getElementById('td_10_".$var1."').innerHTML='".$this->Formato2($monto_mensual_semana_adicional)."'; ";

		echo "</script>";


}//fin funtion






function cancelar($var=null, $var2=null, $var3=null, $var4=null, $var5=null){
$this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  $accion =  $this->cnmd15_devengado->findAll("cod_presi = '".$cod_presi."' and cod_entidad  = '".$cod_entidad."' and  cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."'  and  cod_dep  = '".$cod_dep."' and  cod_tipo_nomina= '".$var."' and cod_cargo= '".$var2."' and cod_ficha= '".$var3."' and cedula_identidad= '".$var4."' and escala= '".$var5."' ", null, null);
  $fecha_desde = $accion[0]['cnmd15_devengado']['fecha_desde'];
  $fecha_hasta = $accion[0]['cnmd15_devengado']['fecha_hasta'];
  $fecha_desde = $fecha_desde[8].$fecha_desde[9].'/'.$fecha_desde[5].$fecha_desde[6].'/'.$fecha_desde[0].$fecha_desde[1].$fecha_desde[2].$fecha_desde[3];
  $fecha_hasta = $fecha_hasta[8].$fecha_hasta[9].'/'.$fecha_hasta[5].$fecha_hasta[6].'/'.$fecha_hasta[0].$fecha_hasta[1].$fecha_hasta[2].$fecha_hasta[3];
        echo "<script>";
            echo "document.getElementById('td_1_".$var5."').innerHTML='".$this->AddCeroR2($accion[0]['cnmd15_devengado']['escala'])."';";
			echo "document.getElementById('td_4_".$var5."').innerHTML='".$this->Formato2($accion[0]['cnmd15_devengado']['sueldo_integral'])."';";
			echo "document.getElementById('td_11_".$var5."').innerHTML='".$this->Formato2($accion[0]['cnmd15_devengado']['sueldo_total'])."';";
			echo "document.getElementById('td_2_".$var5."').innerHTML='".$fecha_desde."';";
			echo "document.getElementById('td_3_".$var5."').innerHTML='".$fecha_hasta."';";

  $ano_antiguedad            = $accion[0]['cnmd15_devengado']['ano_antiguedad'];
  $dias_mes_aguinaldo        = $accion[0]['cnmd15_devengado']['dias_mes_aguinaldo'];
  $dias_mes_bonova           = $accion[0]['cnmd15_devengado']['dias_mes_bonova'];
  $dias_mes_semana_adicional = $accion[0]['cnmd15_devengado']['dias_mes_semana_adicional'];


  $dias_escala_aguinaldo   = isset($accion[0]['cnmd15_devengado']['dias_escala_aguinaldo'])?$accion[0]['cnmd15_devengado']['dias_escala_aguinaldo']:0;
  $monto_mensual_aguinaldo = isset($accion[0]['cnmd15_devengado']['monto_mensual_aguinaldo'])?$accion[0]['cnmd15_devengado']['monto_mensual_aguinaldo']:0;

  $dias_escala_bonova   = isset($accion[0]['cnmd15_devengado']['dias_escala_bonova'])?$accion[0]['cnmd15_devengado']['dias_escala_bonova']:0;
  $monto_mensual_bonova = isset($accion[0]['cnmd15_devengado']['monto_mensual_bonova'])?$accion[0]['cnmd15_devengado']['monto_mensual_bonova']:0;

  $dias_semana_adicional          = isset($accion[0]['cnmd15_devengado']['dias_semana_adicional'])?$accion[0]['cnmd15_devengado']['dias_semana_adicional']:0;
  $monto_mensual_semana_adicional = isset($accion[0]['cnmd15_devengado']['monto_mensual_semana_adicional'])?$accion[0]['cnmd15_devengado']['monto_mensual_semana_adicional']:0;

          echo "document.getElementById('td_5_".$var5."').innerHTML=' ".$dias_escala_aguinaldo."'; ";
          echo "document.getElementById('td_6_".$var5."').innerHTML=' ".$this->Formato2($monto_mensual_aguinaldo)."'; ";

          echo "document.getElementById('td_7_".$var5."').innerHTML=' ".$dias_escala_bonova."'; ";
          echo "document.getElementById('td_8_".$var5."').innerHTML=' ".$this->Formato2($monto_mensual_bonova)."'; ";

          echo "document.getElementById('td_9_".$var5."').innerHTML=' ".$dias_semana_adicional."'; ";
          echo "document.getElementById('td_10_".$var5."').innerHTML='".$this->Formato2($monto_mensual_semana_adicional)."'; ";




			echo "document.getElementById('iconos_1_".$var5."').style.display = 'block'; ";
            echo "document.getElementById('iconos_2_".$var5."').style.display = 'none'; ";
		echo "</script>";
}//fin function






function editar($var=null, $var2=null, $var3=null, $var4=null, $var5=null){
 $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  $accion =  $this->cnmd15_devengado->findAll("cod_presi = '".$cod_presi."' and cod_entidad  = '".$cod_entidad."' and  cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."'  and  cod_dep  = '".$cod_dep."' and  cod_tipo_nomina= '".$var."' and cod_cargo= '".$var2."' and cod_ficha= '".$var3."' and cedula_identidad= '".$var4."' and escala= '".$var5."' ", null, null);
  $fecha_desde = $accion[0]['cnmd15_devengado']['fecha_desde'];
  $fecha_hasta = $accion[0]['cnmd15_devengado']['fecha_hasta'];

  $ano_antiguedad            = $accion[0]['cnmd15_devengado']['ano_antiguedad'];
  $dias_mes_aguinaldo        = $accion[0]['cnmd15_devengado']['dias_mes_aguinaldo'];
  $dias_mes_bonova           = $accion[0]['cnmd15_devengado']['dias_mes_bonova'];
  $dias_mes_semana_adicional = $accion[0]['cnmd15_devengado']['dias_mes_semana_adicional'];


  $dias_escala_aguinaldo   = isset($accion[0]['cnmd15_devengado']['dias_escala_aguinaldo'])?$accion[0]['cnmd15_devengado']['dias_escala_aguinaldo']:0;
  $monto_mensual_aguinaldo = isset($accion[0]['cnmd15_devengado']['monto_mensual_aguinaldo'])?$accion[0]['cnmd15_devengado']['monto_mensual_aguinaldo']:0;

  $dias_escala_bonova   = isset($accion[0]['cnmd15_devengado']['dias_escala_bonova'])?$accion[0]['cnmd15_devengado']['dias_escala_bonova']:0;
  $monto_mensual_bonova = isset($accion[0]['cnmd15_devengado']['monto_mensual_bonova'])?$accion[0]['cnmd15_devengado']['monto_mensual_bonova']:0;

  $dias_semana_adicional          = isset($accion[0]['cnmd15_devengado']['dias_semana_adicional'])?$accion[0]['cnmd15_devengado']['dias_semana_adicional']:0;
  $monto_mensual_semana_adicional = isset($accion[0]['cnmd15_devengado']['monto_mensual_semana_adicional'])?$accion[0]['cnmd15_devengado']['monto_mensual_semana_adicional']:0;




  $fecha_desde = $fecha_desde[8].$fecha_desde[9].'/'.$fecha_desde[5].$fecha_desde[6].'/'.$fecha_desde[0].$fecha_desde[1].$fecha_desde[2].$fecha_desde[3];
  $fecha_hasta = $fecha_hasta[8].$fecha_hasta[9].'/'.$fecha_hasta[5].$fecha_hasta[6].'/'.$fecha_hasta[0].$fecha_hasta[1].$fecha_hasta[2].$fecha_hasta[3];


	if($cod_presi==1 && $cod_entidad==12 && $cod_tipo_inst==50 && $cod_inst==2){
		$vreadonly="readonly";
	}else{
		$vreadonly="";
	}


		echo "<script>";
		  echo "document.getElementById('iconos_1_".$var5."').style.display = 'none'; ";
          echo "document.getElementById('iconos_2_".$var5."').style.display = 'block'; ";
          echo "document.getElementById('td_1_".$var5."').innerHTML='<input type=hidden name=data[cnmp15_devengado][cod_nomina_".$var5."]  value=".$var." />" .
                                                                    "<input type=hidden name=data[cnmp15_devengado][codigo_ficha_".$var5."]  value=".$var3." />" .
          		                                                    "<input type=hidden name=data[cnmp15_devengado][codigo_cargo_".$var5."]  value=".$var2." />" .
		                                                            "<input type=hidden name=data[cnmp15_devengado][cedula_".$var5."]  value=".$var4." />" .

		                                                            "<input type=hidden name=data[cnmp15_devengado][ano_antiguedad_".$var5."]             id=calculo_1_".$var5." value=".$ano_antiguedad." />" .
		                                                            "<input type=hidden name=data[cnmp15_devengado][dias_mes_aguinaldo_".$var5."]         id=calculo_2_".$var5." value=".$dias_mes_aguinaldo." />" .
		                                                            "<input type=hidden name=data[cnmp15_devengado][dias_mes_bonova_".$var5."]            id=calculo_3_".$var5." value=".$dias_mes_bonova." />" .
		                                                            "<input type=hidden name=data[cnmp15_devengado][dias_mes_semana_adicional_".$var5."]  id=calculo_4_".$var5." value=".$dias_mes_semana_adicional." />" .

		                                                            "<input type=hidden name=data[cnmp15_devengado][escala_".$var5."]  value=".$var5." />".$this->AddCeroR2($accion[0]['cnmd15_devengado']['escala'])."';";
		 // echo "document.getElementById('td_4_".$var5."').innerHTML='<input type=text name=data[cnmp15_devengado][sueldo_salario_$var5]  value=".$this->Formato2($accion[0]['cnmd15_devengado']['sueldo_basico'])." />';  ";

	      echo "document.getElementById('td_2_".$var5."').innerHTML='<input type=hidden name=data[cnmp15_devengado][fecha_desde_$var5]  value=$fecha_desde />$fecha_desde'; ";
		  echo "document.getElementById('td_3_".$var5."').innerHTML='<input type=hidden name=data[cnmp15_devengado][fecha_hasta_$var5]  value=$fecha_hasta />$fecha_hasta'; ";





          echo "document.getElementById('td_5_".$var5."').innerHTML=' <input style=text-align:center; type=text name=data[cnmp15_devengado][dias_escala_aguinaldo_$var5]     id=calculo_5_".$var5." readonly  value=".$dias_escala_aguinaldo." class=inputtext />'; ";
          echo "document.getElementById('td_6_".$var5."').innerHTML=' <input style=text-align:right; type=text name=data[cnmp15_devengado][monto_mensual_aguinaldo_$var5]   id=calculo_6_".$var5." readonly  value=".$this->Formato2($monto_mensual_aguinaldo)." class=inputtext />'; ";

          echo "document.getElementById('td_7_".$var5."').innerHTML=' <input style=text-align:center; type=text name=data[cnmp15_devengado][dias_escala_bonova_$var5]   id=calculo_7_".$var5." readonly  value=".$dias_escala_bonova." class=inputtext />'; ";
          echo "document.getElementById('td_8_".$var5."').innerHTML=' <input style=text-align:right; type=text name=data[cnmp15_devengado][monto_mensual_bonova_$var5] id=calculo_8_".$var5." readonly  value=".$this->Formato2($monto_mensual_bonova)." class=inputtext />'; ";

          echo "document.getElementById('td_9_".$var5."').innerHTML=' <input style=text-align:center; type=text name=data[cnmp15_devengado][dias_semana_adicional_$var5]          id=calculo_9_".$var5."  $vreadonly  value=".$dias_semana_adicional." class=inputtext onBlur=calcular_semana_adicional(\"$var5\"); />'; ";
          echo "document.getElementById('td_10_".$var5."').innerHTML='<input style=text-align:right; type=text name=data[cnmp15_devengado][monto_mensual_semana_adicional_$var5]  id=calculo_10_".$var5." readonly  value=".$this->Formato2($monto_mensual_semana_adicional)." class=inputtext />'; ";

		  echo "document.getElementById('td_11_".$var5."').innerHTML='<input style=text-align:right; type=text name=data[cnmp15_devengado][compensaciones_$var5] id=compensaciones_".$var5." readonly  value=".$this->Formato2($accion[0]['cnmd15_devengado']['sueldo_total'])." class=inputtext />'; ";
		echo "</script>";


    $this->set('cod_tipo_nomina', $var);
	$this->set('cod_cargo', $var2);
	$this->set('cod_ficha', $var3);
	$this->set('cedula', $var4);
	$this->set('escala', $var5);
	$this->set('sueldo', $this->Formato2($accion[0]['cnmd15_devengado']['sueldo_integral']));


$this->set('Message_existe', 'PUEDE EDITAR LOS DATOS');
}//fin function















function eliminar($var=null, $var2=null, $var3=null, $var4=null, $var5=null){
  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  $sql="BEGIN;  DELETE FROM cnmd15_devengado  WHERE cod_presi = '".$cod_presi."' and cod_entidad  = '".$cod_entidad."' and  cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."'  and  cod_dep  = '".$cod_dep."' and  cod_tipo_nomina= '".$var."' and cod_cargo= '".$var2."' and cod_ficha= '".$var3."' and cedula_identidad= '".$var4."' and escala>= '".$var5."' ";
  $sw2 = $this->cnmd15_devengado->execute($sql);
			if($sw2>1){
                $this->cnmd15_devengado->execute("COMMIT;");
		        $this->set('Message_existe', 'LOS DATOS FUERON ELIMINADOS CORRECTAMENTE');
			}else{
				$this->cnmd15_devengado->execute("ROLLBACK;");
				$this->set('errorMessage', 'LOS DATOS NO FUERON ELIMINADOS - POR FAVOR INTENTE DE NUEVO');
			}//fin else

  $cont = 0;
  $cont     = $this->cnmd15_devengado->findCount($condicion.'    and cod_tipo_nomina='.$var.' and cod_cargo='.$var2.' and cod_ficha='.$var3.' and cedula_identidad='.$var4 );
  $cont_aux = $this->cnmd15_devengado->findAll($condicion.'      and cod_tipo_nomina='.$var.' and cod_cargo='.$var2.' and cod_ficha='.$var3.' and cedula_identidad='.$var4.' and escala='.$cont);
  $lista2 = $this->cnmd15_datos_personales->findAll($condicion.' and cod_tipo_nomina='.$var.' and cod_cargo='.$var2.' and cod_ficha='.$var3.' and cedula_identidad='.$var4 );
  $fecha_aux = "";
  $fecha_hasta = isset($cont_aux[0]['cnmd15_devengado']['fecha_hasta'])?$cont_aux[0]['cnmd15_devengado']['fecha_hasta']:"";
  if($fecha_hasta!=""){
  $ano = $fecha_hasta[0].$fecha_hasta[1].$fecha_hasta[2].$fecha_hasta[3];
  $mes = $fecha_hasta[5].$fecha_hasta[6];
  $dia = $fecha_hasta[8].$fecha_hasta[9];
  $fecha_aux = $this->dateAdd(1,$dia,$mes,$ano);

  }else{
    $fecha_aux = $lista2[0]['cnmd15_datos_personales']['fecha_ingreso'];
      $ano = $fecha_aux[0].$fecha_aux[1].$fecha_aux[2].$fecha_aux[3];
	  $mes = $fecha_aux[5].$fecha_aux[6];
	  $dia = $fecha_aux[8].$fecha_aux[9];
	  $fecha_aux = $dia.'/'.$mes.'/'.$ano;
  }//fin else


  $cont++;
  echo "<script>";
            echo "document.getElementById('escala').value='".$this->AddCeroR2($cont)."';";
			echo "document.getElementById('sueldo_salario').readOnly=false;";
			echo "document.getElementById('compensaciones').readOnly=true;";
			echo "document.getElementById('sueldo_salario').value='0,00';";
			echo "document.getElementById('compensaciones').value='0,00';";
			echo "document.getElementById('fecha_desde').value='".$fecha_aux."';";
			echo "document.getElementById('fecha_hasta').value='';";
 echo "</script>";



//			$this->consulta($var, $var2, $var3, $var4);
//			$this->render('consulta');

               $this->index();
		       $this->render('index');

}//fin funtion

















 }//fin class

?>