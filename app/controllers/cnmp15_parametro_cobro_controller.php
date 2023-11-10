<?php

 class Cnmp15ParametroCobroController extends AppController{

    var $name = "cnmp15_parametro_cobro";
    var $uses = array('cnmd15_parametro_cobro','cnmd15_bono_vaca', 'cnmd15_aguinaldo', 'cnmd15_devengado', 'cnmd15_datos_personales','ccfd04_cierre_mes', 'Cnmd01', 'v_cnmd05', 'Cnmd01');
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
     $lista = "";







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
										        $cont_aux = $this->cnmd15_parametro_cobro->findAll($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$cod_cargo.' and cod_ficha='.$cod_ficha.' and cedula_identidad='.$cedula);
										        $ano2 = 0;
										       foreach($cont_aux as $ve){
										          $ano2 = $ve['cnmd15_parametro_cobro']['ano'];
										       }//fin f
										       if($ano2==0){
										              $lista2a = $this->cnmd15_datos_personales->findAll($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$cod_cargo.' and cod_ficha='.$cod_ficha.' and cedula_identidad='.$cedula );
												      $fecha_aux = $lista2a[0]['cnmd15_datos_personales']['fecha_ingreso'];
												      $ano2 = $fecha_aux[0].$fecha_aux[1].$fecha_aux[2].$fecha_aux[3];
													  $mes  = $fecha_aux[5].$fecha_aux[6];
													  $dia  = $fecha_aux[8].$fecha_aux[9];
													  $fecha_aux = $dia.'/'.$mes.'/'.$ano;

													  }else{$ano2++;}
										      $this->set('ano', $ano2);
											  $accion =  $this->cnmd15_parametro_cobro->findAll($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$cod_cargo.' and cod_ficha='.$cod_ficha.' and cedula_identidad='.$cedula, null, 'ano ASC');
										      $this->set('accion', $accion);


}//fin function






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
  $accion =  $this->cnmd15_parametro_cobro->findAll("cod_presi = '".$cod_presi."' and cod_entidad  = '".$cod_entidad."' and  cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."'  and  cod_dep  = '".$cod_dep."' and  cod_tipo_nomina= '".$var."'  ", null, null);
  foreach($accion as $ver){
      if($sql==""){$sql = "and ( cod_cargo =".$ver['cnmd15_parametro_cobro']['cod_cargo']." ";
      }else{       $sql .= "  or  cod_cargo =".$ver['cnmd15_parametro_cobro']['cod_cargo']." ";   }
  }//fin foreach
       if($sql!=""){$sql .= ")"; }
    //$lista2 = $this->v_cnmd05->generateList($conditions = $condicion.' and cod_tipo_nomina='.$var.$sql, $order = 'cod_tipo_nomina', $limit = null, '{n}.v_cnmd05.cod_cargo', '{n}.v_cnmd05.denominacion_clase');
	$lista2 = $this->cnmd15_datos_personales->generateList($conditions = $condicion.' and cod_tipo_nomina='.$var , $order = 'cod_tipo_nomina', $limit = null, '{n}.cnmd15_datos_personales.cod_cargo', '{n}.cnmd15_datos_personales.cod_cargo');
$this->concatena($lista2, 'nomina3');
	$this->set('cod_tipo_nomina', $var);

	echo "<script>";
	    //echo "document.getElementById('escala').value='';";
		//echo "document.getElementById('cargo_ocupado').value='';";
		echo "document.getElementById('primer_apellido').value='';";
		echo "document.getElementById('segundo_apellido').value='';";
		echo "document.getElementById('primer_nombre').value='';";
		echo "document.getElementById('segundo_nombre').value='';";
		echo "document.getElementById('institucion').value='';";
		echo "document.getElementById('dependencia').value='';";
		//echo "document.getElementById('sueldo_salario').readOnly=true;";
		//echo "document.getElementById('compensaciones').readOnly=true;";
		//echo "document.getElementById('sueldo_salario').value='0,00';";
		//echo "document.getElementById('compensaciones').value='0,00';";
		//echo "document.getElementById('fecha1').value='';";
		//echo "document.getElementById('fecha2').value='';";
  echo "</script>";


}//fin function





function codigo_ficha($var=null, $var2=null){
  $this->layout = "ajax";
 // echo 'si entro a codigo ficha';
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
      //  echo "document.getElementById('escala').value='';";
	//	echo "document.getElementById('cargo_ocupado').value='';";
		echo "document.getElementById('primer_apellido').value='';";
		echo "document.getElementById('segundo_apellido').value='';";
		echo "document.getElementById('primer_nombre').value='';";
		echo "document.getElementById('segundo_nombre').value='';";
		echo "document.getElementById('institucion').value='';";
		echo "document.getElementById('dependencia').value='';";
	//	echo "document.getElementById('sueldo_salario').readOnly=true;";
	//	echo "document.getElementById('compensaciones').readOnly=true;";
	//	echo "document.getElementById('sueldo_salario').value='0,00';";
	//	echo "document.getElementById('compensaciones').value='0,00';";
	//	echo "document.getElementById('fecha1').value='';";
	//	echo "document.getElementById('fecha2').value='';";
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


  if($var6==null){
				   $cod_tipo_nomina     =  $this->data['cnmp15_parametro_cobro']['cod_nomina'];
				  $codigo_cargo        =  $this->data['cnmp15_parametro_cobro']['codigo_cargo'];
				  $codigo_ficha        =  $this->data['cnmp15_parametro_cobro']['codigo_ficha'];
				  $cedula              =  $this->data['cnmp15_parametro_cobro']['cedula'];
				  $escala              =  $this->data['cnmp15_parametro_cobro']['ano'];
				  $fecha_desde         =  $this->Cfecha($this->data['cnmp15_parametro_cobro']['fecha_desde'], 'A-M-D');
				  $fecha_hasta         =  $this->Cfecha($this->data['cnmp15_parametro_cobro']['fecha_hasta'], 'A-M-D');
				  $sueldo_salario      =  $this->Formato1($this->data['cnmp15_parametro_cobro']['sueldo_salario']);
				  $opcion = 1;
  }else{
				  $cod_tipo_nomina     =  $var;
				  $codigo_cargo        =  $var2;
				  $codigo_ficha        =  $var3;
				  $cedula              =  $var4;
				  $escala              =  $var5;
				  $accion =  $this->cnmd15_parametro_cobro->findAll("cod_presi = '".$cod_presi."' and cod_entidad  = '".$cod_entidad."' and  cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."'  and  cod_dep  = '".$cod_dep."' and  cod_tipo_nomina= '".$cod_tipo_nomina."' and cod_cargo= '".$codigo_cargo."' and cod_ficha= '".$codigo_ficha."' and cedula_identidad= '".$cedula."' and ano= '".$escala."' ", null, null);
				  $fecha_desde = $accion[0]['cnmd15_parametro_cobro']['fecha_desde'];
				  $fecha_hasta = $accion[0]['cnmd15_parametro_cobro']['fecha_hasta'];
				  $sueldo_salario      =  $this->Formato1($var6);
				  $opcion = 2;
  }//fin else



  $dias_year           =  0;
  $compensaciones      =  $sueldo_salario;
  $dias_aginaldo       = 0;
  $dias_antiguedad     = 0;
  $aux_a               =  0;
  $aux_b               =  0;
  $aux_c               =  0;
  $diferencia          =  0;
  $fecha_desde_aux = $fecha_desde;
  $ano_desde = $fecha_desde_aux[0].$fecha_desde_aux[1].$fecha_desde_aux[2].$fecha_desde_aux[3];
  $mes_desde = $fecha_desde_aux[5].$fecha_desde_aux[6];
  $dia_desde = $fecha_desde_aux[8].$fecha_desde_aux[9];
  $fecha_hasta_aux = $fecha_hasta;
  $ano_hasta = $fecha_hasta_aux[0].$fecha_hasta_aux[1].$fecha_hasta_aux[2].$fecha_hasta_aux[3];
  $mes_hasta = $fecha_hasta_aux[5].$fecha_hasta_aux[6];
  $dia_hasta = $fecha_hasta_aux[8].$fecha_hasta_aux[9];
  //$cont_aux                =   $this->Cnmd01->findAll($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina);
  //$clasificacion_personal  =   $cont_aux[0]['Cnmd01']['clasificacion_personal'];

  $diferencia =  $ano_hasta - $ano_desde;
  $cont_aux_2              =   $this->cnmd15_bono_vaca->findAll($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and (desde_antiguedad<='.$diferencia.' and hasta_antiguedad>='.$diferencia.' )');
  foreach($cont_aux_2 as $a){$dias_antiguedad    =   $a['cnmd15_bono_vaca']['dias'];}
  $cont_aux_3              =   $this->cnmd15_aguinaldo->findAll($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and (desde_antiguedad<='.$diferencia.' and hasta_antiguedad>='.$diferencia.' )');
  foreach($cont_aux_3 as $b){$dias_aginaldo      =   $cont_aux_3['cnmd15_aguinaldo']['dias'];}




    $aux_a = $sueldo_salario/30;
    $dias_antiguedad = ($dias_antiguedad/12);
    $dias_aginaldo   = ($dias_aginaldo/12);
    $aux_b = $aux_a * $dias_antiguedad;
    $aux_c = $aux_a * $dias_aginaldo;




/*        if($clasificacion_personal==2){$dias_year = 365;
               $aux_a = ($sueldo_salario * 12) / $dias_year;
               $dias_antiguedad = ($dias_antiguedad/$dias_year) * 12;
               $dias_aginaldo = ($dias_aginaldo/$dias_year) * 12;
               $aux_b = $aux_a * $dias_antiguedad;
               $aux_c = $aux_a * $dias_aginaldo;
  }else if($clasificacion_personal==1){$dias_year = 360;
               $aux_a = $sueldo_salario/ 30;
               $dias_antiguedad = ($dias_antiguedad/$dias_year) * 12;
               $dias_aginaldo   = ($dias_aginaldo/$dias_year) * 12;
               $aux_b = $aux_a * $dias_antiguedad;
               $aux_c = $aux_a * $dias_aginaldo;
  }//fin else

*/


$compensaciones += ($aux_b + $aux_c);
			if($opcion ==1){
				          echo "<script>";
							  	echo "document.getElementById('compensaciones').value='".$this->Formato2($compensaciones)."';";
						  echo "</script>";
			}else{
						  echo "<script>";
							  	echo "document.getElementById('compensaciones_".$escala."').value='".$this->Formato2($compensaciones)."';";
						  echo "</script>";
			}//fin else
}//fin function





function calcular($var=null, $var2=null, $var3=null, $var4=null){

  $this->layout = "ajax";

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
	  //  echo "document.getElementById('escala').value='';";
	///	echo "document.getElementById('cargo_ocupado').value='';";
		echo "document.getElementById('primer_apellido').value='';";
		echo "document.getElementById('segundo_apellido').value='';";
		echo "document.getElementById('primer_nombre').value='';";
		echo "document.getElementById('segundo_nombre').value='';";
		echo "document.getElementById('institucion').value='';";
		echo "document.getElementById('dependencia').value='';";
	//	echo "document.getElementById('sueldo_salario').readOnly=true;";
	//	echo "document.getElementById('compensaciones').readOnly=true;";
	//	echo "document.getElementById('sueldo_salario').value='0,00';";
	//	echo "document.getElementById('compensaciones').value='0,00';";
	//	echo "document.getElementById('fecha1').value='';";
	//	echo "document.getElementById('fecha2').value='';";
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
  $lista2 = $this->cnmd15_datos_personales->findAll(   $condicion.' and cod_tipo_nomina='.$var.' and cod_cargo='.$var2.' and cod_ficha='.$var3.' and cedula_identidad='.$var4 );
  $cont     = $this->cnmd15_parametro_cobro->findCount($condicion.' and cod_tipo_nomina='.$var.' and cod_cargo='.$var2.' and cod_ficha='.$var3.' and cedula_identidad='.$var4 );
  $cont_aux = $this->cnmd15_parametro_cobro->findAll(  $condicion.' and cod_tipo_nomina='.$var.' and cod_cargo='.$var2.' and cod_ficha='.$var3.' and cedula_identidad='.$var4);
  $cont ++;
  $ano2 = 0;
       foreach($cont_aux as $ve){
          $ano2 = $ve['cnmd15_parametro_cobro']['ano'];
       }//fin f
       if($ano2==0){
              $fecha_aux = $lista2[0]['cnmd15_datos_personales']['fecha_ingreso'];
		      $ano2 = $fecha_aux[0].$fecha_aux[1].$fecha_aux[2].$fecha_aux[3];
			  $mes  = $fecha_aux[5].$fecha_aux[6];
			  $dia  = $fecha_aux[8].$fecha_aux[9];
			  $fecha_aux = $dia.'/'.$mes.'/'.$ano;

			  }else{$ano2++;}


  echo "<script>";
        //echo "document.getElementById('escala').value='".$this->AddCeroR2($cont)."';";
		echo "document.getElementById('cargo_ocupado').value='".$lista2[0]['cnmd15_datos_personales']['denominacion_cargo']."';";
		echo "document.getElementById('primer_apellido').value='".$lista2[0]['cnmd15_datos_personales']['primer_apellido']."';";
		echo "document.getElementById('segundo_apellido').value='".$lista2[0]['cnmd15_datos_personales']['segundo_apellido']."';";
		echo "document.getElementById('primer_nombre').value='".$lista2[0]['cnmd15_datos_personales']['primer_nombre']."';";
		echo "document.getElementById('segundo_nombre').value='".$lista2[0]['cnmd15_datos_personales']['segundo_nombre']."';";
		echo "document.getElementById('institucion').value='".$lista2[0]['cnmd15_datos_personales']['institucion']."';";
		echo "document.getElementById('dependencia').value='".$lista2[0]['cnmd15_datos_personales']['dependencia']."';";
		echo "document.getElementById('ano').value='".$ano2."';";
		//echo "document.getElementById('sueldo_salario').readOnly=false;";
		//echo "document.getElementById('compensaciones').readOnly=false;";
	//	echo "document.getElementById('fecha1').value='".$fecha_hasta."';";
	  //  echo "document.getElementById('fecha2').value='';";
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
	  $accion =  $this->cnmd15_parametro_cobro->findAll($condicion.' and cod_tipo_nomina='.$var.' and cod_cargo='.$var2.' and cod_ficha='.$var3.' and cedula_identidad='.$var4, null, 'ano ASC');
	  $this->set('accion', $accion);
  }//fin


}//fin function






function guardar($var1=null){
  $this->layout = "ajax";
 // print_r($this->data);
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
  $cod_tipo_nomina     =  $this->data['cnmp15_parametro_cobro']['cod_nomina'];
  $codigo_cargo        =  $this->data['cnmp15_parametro_cobro']['codigo_cargo'];
  $codigo_ficha        =  $this->data['cnmp15_parametro_cobro']['codigo_ficha'];
  $cedula              =  $this->data['cnmp15_parametro_cobro']['cedula'];
  $ano              =  $this->data['cnmp15_parametro_cobro']['ano'];
  $cobroaguinaldo              =  $this->data['cnmp15_parametro_cobro']['cobroaguinaldos'];
  $cobrovacaciones              =  $this->data['cnmp15_parametro_cobro']['cobrovacaciones'];
  $disfrutovacaciones              =  $this->data['cnmp15_parametro_cobro']['disfrutovacaciones'];
  $cobrorulalidad              =  $this->data['cnmp15_parametro_cobro']['cobroruralidad'];

  //$fecha_desde         =  $this->Cfecha($this->data['cnmp15_parametro_cobro']['fecha_desde'], 'A-M-D');
 // $fecha_hasta         =  $this->Cfecha($this->data['cnmp15_parametro_cobro']['fecha_hasta'], 'A-M-D');
  //$sueldo_salario      =  $this->Formato1($this->data['cnmp15_parametro_cobro']['sueldo_salario']);
  //$compensaciones      =  $this->Formato1($this->data['cnmp15_parametro_cobro']['compensaciones']);



  //$ano = $fecha_hasta[0].$fecha_hasta[1].$fecha_hasta[2].$fecha_hasta[3];
  //$mes = $fecha_hasta[5].$fecha_hasta[6];
  //$dia = $fecha_hasta[8].$fecha_hasta[9];



$sw2  = $this->cnmd15_parametro_cobro->execute('BEGIN; ');
//$cont = $this->cnmd15_parametro_cobro->findCount($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$codigo_cargo.' and cod_ficha='.$codigo_ficha.' and cedula_identidad='.$cedula.' and escala='.$ano);
$opcion = 'si';
//if($cont==0){
		$sql =" INSERT INTO cnmd15_parametro_cobro (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, ano, cobro_aguinaldo, cobro_bono_vacacional, disfruto_vacaciones,  cobro_ruralidad)";
		$sql.="VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$cod_tipo_nomina."', '".$codigo_cargo."', '".$codigo_ficha."', '".$cedula."',".$ano.", '".$cobroaguinaldo."', '".$cobrovacaciones."', '".$disfrutovacaciones."', '".$cobrorulalidad."'); ";
//}else{
  //      $sql = " UPDATE cnmd15_parametro_cobro SET fecha_desde='".$fecha_desde."', fecha_hasta='".$fecha_hasta."' where cod_presi = '".$cod_presi."' and cod_entidad  = '".$cod_entidad."' and  cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."'  and  cod_dep  = '".$cod_dep."' and  cod_tipo_nomina= '".$cod_tipo_nomina."' and cod_cargo= '".$codigo_cargo."' and cod_ficha= '".$codigo_ficha."' and cedula_identidad= '".$cedula."' and escala= '".$escala."' ";
//}//fin else


if($opcion=='si'){
		$sw2 = $this->cnmd15_parametro_cobro->execute($sql);

					if($sw2>1){
		                $this->cnmd15_parametro_cobro->execute("COMMIT;");
		                $cont = 0;
                        $cont = $this->cnmd15_parametro_cobro->findCount($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$codigo_cargo.' and cod_ficha='.$codigo_ficha.' and cedula_identidad='.$cedula);
                        $cont++;
				        $this->set('Message_existe', 'Los datos fueron guardados correctamente');
				      //  $fecha_hasta = $this->dateAdd(1,$dia,$mes,$ano);
					}else{
						$this->cnmd15_parametro_cobro->execute("ROLLBACK;");
						$cont = 0;
                        $cont = $this->cnmd15_parametro_cobro->findCount($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$codigo_cargo.' and cod_ficha='.$codigo_ficha.' and cedula_identidad='.$cedula);
				        $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
						$fecha_hasta = "";
					}//fin else
}else{

						$this->cnmd15_parametro_cobro->execute("ROLLBACK;");
						$cont = 0;
                        $cont = $this->cnmd15_parametro_cobro->findCount($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$codigo_cargo.' and cod_ficha='.$codigo_ficha.' and cedula_identidad='.$cedula);
						$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
						$fecha_hasta = "";

}//fin else
 //$accion =  $this->cnmd15_parametro_cobro->findAll($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$codigo_cargo.' and cod_ficha='.$codigo_ficha.' and cedula_identidad='.$cedula, null, 'ano DESC');
  //$this->set('accion', $accion);

/*
  $this->set('cedula', $var1);
        echo "<script>";
            echo "document.getElementById('ano').value=eval(document.getElementById('ano').value) + eval(1);";
			//echo "document.getElementById('sueldo_salario').readOnly=true;";
			//echo "document.getElementById('compensaciones').readOnly=true;";
			//echo "document.getElementById('sueldo_salario').value='0,00';";
			//echo "document.getElementById('compensaciones').value='0,00';";
			//echo "document.getElementById('fecha1').value='".$fecha_hasta."';";
			//echo "document.getElementById('fecha2').value='';";
		echo "</script>";

*/

   //$this->set('userTable', $this->requestAction('/cnmp15_anticipos/', array('return')));


	$this->index();
	$this->render('index');

}//fin funtion














function guardar_editar($var1,$var2,$var3,$var4,$var5){
  $this->layout = "ajax";
 // pr($this->data);
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
  $cod_tipo_nomina     =  $var2;
  $codigo_cargo        =  $var3;
  $codigo_ficha        =  $var4;
  $cedula              =  $var5;
  $escala              =  $var1;
  $cobroaguinaldo      =  $this->data['cnmp15_parametro_cobro']['cobroaguinaldos_'.$var1];
  $cobrovacaciones     =  $this->data['cnmp15_parametro_cobro']['cobrovacaciones_'.$var1];
  $disfrutovacaciones  =  $this->data['cnmp15_parametro_cobro']['disfruto_vacaciones_'.$var1];
  $cobroruralidad      =  $this->data['cnmp15_parametro_cobro']['cobroruralidad_'.$var1];







$sw2  = $this->cnmd15_parametro_cobro->execute('BEGIN; ');
$cont = $this->cnmd15_parametro_cobro->findCount($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$codigo_cargo.' and cod_ficha='.$codigo_ficha.' and cedula_identidad='.$cedula.' and ano='.$escala);
$opcion = 'si';
if($cont==0){
		$sql =" INSERT INTO cnmd15_parametro_cobro (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, ano, cobro_aguinaldo, cobro_bono_vacacional, disfruto_vacaciones,  cobro_ruralidad)";
		$sql.="VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$cod_tipo_nomina."', '".$codigo_cargo."', '".$codigo_ficha."', '".$cedula."', '".$escala."', '".$cobroaguinaldo."', '".$cobrovacaciones."', '".$disfrutovacaciones."', '".$cobroruralidad."'); ";
}else{
        $sql = " UPDATE cnmd15_parametro_cobro SET cobro_aguinaldo='".$cobroaguinaldo."', cobro_bono_vacacional='".$cobrovacaciones."', disfruto_vacaciones='".$disfrutovacaciones."', cobro_ruralidad='".$cobroruralidad."' where cod_presi = '".$cod_presi."' and cod_entidad  = '".$cod_entidad."' and  cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."'  and  cod_dep  = '".$cod_dep."' and  cod_tipo_nomina= '".$cod_tipo_nomina."' and cod_cargo= '".$codigo_cargo."' and cod_ficha= '".$codigo_ficha."' and cedula_identidad= '".$cedula."' and ano= '".$escala."' ";
}//fin else

//$cont = 0;
//$cont = $this->cnmd15_parametro_cobro->findCount($condicion.' and cod_tipo_nomina='.$cod_tipo_nomina.' and cod_cargo='.$codigo_cargo.' and cod_ficha='.$codigo_ficha.' and cedula_identidad='.$cedula);

          $accion =  $this->cnmd15_parametro_cobro->findAll("cod_presi = '".$cod_presi."' and cod_entidad  = '".$cod_entidad."' and  cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."'  and  cod_dep  = '".$cod_dep."' and  cod_tipo_nomina= '".$cod_tipo_nomina."' and cod_cargo= '".$codigo_cargo."' and cod_ficha= '".$codigo_ficha."' and cedula_identidad= '".$cedula."' and ano= '".$var1."' ", null, null);
	//	  $fecha_desde = $accion[0]['cnmd15_parametro_cobro']['fecha_desde'];
		//  $fecha_hasta = $accion[0]['cnmd15_parametro_cobro']['fecha_hasta'];
		//  $fecha_desde_aux  = $fecha_desde[8].$fecha_desde[9].'/'.$fecha_desde[5].$fecha_desde[6].'/'.$fecha_desde[0].$fecha_desde[1].$fecha_desde[2].$fecha_desde[3];
		//  $fecha_hasta_aux  = $fecha_hasta[8].$fecha_hasta[9].'/'.$fecha_hasta[5].$fecha_hasta[6].'/'.$fecha_hasta[0].$fecha_hasta[1].$fecha_hasta[2].$fecha_hasta[3];
        //  $ano = $fecha_hasta[0].$fecha_hasta[1].$fecha_hasta[2].$fecha_hasta[3];
		//  $mes = $fecha_hasta[5].$fecha_hasta[6];
		//  $dia = $fecha_hasta[8].$fecha_hasta[9];

if($opcion=='si'){
		$sw2 = $this->cnmd15_parametro_cobro->execute($sql);

					if($sw2>1){
		                $this->cnmd15_parametro_cobro->execute("COMMIT;"); $cont++;
				        $this->set('Message_existe', 'Los datos fueron guardados correctamente');
				        $this->consulta($cod_tipo_nomina, $codigo_cargo, $codigo_ficha, $cedula);
						$this->render('consulta');
				        //$fecha_hasta_aux = $this->dateAdd(1,$dia,$mes,$ano);
					}else{
						$this->cnmd15_parametro_cobro->execute("ROLLBACK;");
						$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
						$fecha_hasta_aux = "";
					}//fin else
}else{

						$this->cnmd15_parametro_cobro->execute("ROLLBACK;");
						$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
						$fecha_hasta_aux = "";

}//fin else
/*
        echo "<script>";
            echo "document.getElementById('escala').value='".$this->AddCeroR2($cont)."';";
			echo "document.getElementById('sueldo_salario').readOnly=false;";
			echo "document.getElementById('compensaciones').readOnly=true;";
			echo "document.getElementById('sueldo_salario').value='0,00';";
			echo "document.getElementById('compensaciones').value='0,00';";
			echo "document.getElementById('fecha1').value='".$fecha_hasta_aux."';";
			echo "document.getElementById('fecha2').value='';";
		echo "</script>";
	*/
	//	    $accion =  $this->cnmd15_parametro_cobro->findAll("cod_presi = '".$cod_presi."' and cod_entidad  = '".$cod_entidad."' and  cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."'  and  cod_dep  = '".$cod_dep."' and  cod_tipo_nomina= '".$cod_tipo_nomina."' and cod_cargo= '".$codigo_cargo."' and cod_ficha= '".$codigo_ficha."' and cedula_identidad= '".$cedula."' and ano= '".$escala."' ", null, null);
		 // $fecha_desde2 = $accion[0]['cnmd15_parametro_cobro']['fecha_desde'];
		  //$fecha_hasta2 = $accion[0]['cnmd15_parametro_cobro']['fecha_hasta'];
		  //$fecha_desde_aux = $fecha_desde2[8].$fecha_desde2[9].'/'.$fecha_desde2[5].$fecha_desde2[6].'/'.$fecha_desde2[0].$fecha_desde2[1].$fecha_desde2[2].$fecha_desde2[3];
		  //$fecha_hasta_aux = $fecha_hasta2[8].$fecha_hasta2[9].'/'.$fecha_hasta2[5].$fecha_hasta2[6].'/'.$fecha_hasta2[0].$fecha_hasta2[1].$fecha_hasta2[2].$fecha_hasta2[3];
		 /*echo "<script>";
            echo "document.getElementById('td_1_".$var1."').innerHTML='".$this->AddCeroR2($accion[0]['cnmd15_parametro_cobro']['ano'])."';";
			echo "document.getElementById('td_2_".$var1."').innerHTML='".$accion[0]['cnmd15_parametro_cobro']['cobro_aguinaldo']."';";
			echo "document.getElementById('td_3_".$var1."').innerHTML='".$accion[0]['cnmd15_parametro_cobro']['cobro_bono_vacacional']."';";
			echo "document.getElementById('td_4_".$var1."').innerHTML='".$accion[0]['cnmd15_parametro_cobro']['disfruto_vacaciones']."';";
			echo "document.getElementById('td_5_".$var1."').innerHTML='".$accion[0]['cnmd15_parametro_cobro']['cobro_ruralidad']."';";
			echo "document.getElementById('iconos_1_".$var1."').style.display = 'block'; ";
            echo "document.getElementById('iconos_2_".$var1."').style.display = 'none'; ";
		echo "</script>";
*/

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
  $accion =  $this->cnmd15_parametro_cobro->findAll("cod_presi = '".$cod_presi."' and cod_entidad  = '".$cod_entidad."' and  cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."'  and  cod_dep  = '".$cod_dep."' and  cod_tipo_nomina= '".$var."' and cod_cargo= '".$var2."' and cod_ficha= '".$var3."' and cedula_identidad= '".$var4."' and ano= '".$var5."' ", null, null);
  //$fecha_desde = $accion[0]['cnmd15_parametro_cobro']['fecha_desde'];
  //$fecha_hasta = $accion[0]['cnmd15_parametro_cobro']['fecha_hasta'];
 // $fecha_desde = $fecha_desde[8].$fecha_desde[9].'/'.$fecha_desde[5].$fecha_desde[6].'/'.$fecha_desde[0].$fecha_desde[1].$fecha_desde[2].$fecha_desde[3];
  //$fecha_hasta = $fecha_hasta[8].$fecha_hasta[9].'/'.$fecha_hasta[5].$fecha_hasta[6].'/'.$fecha_hasta[0].$fecha_hasta[1].$fecha_hasta[2].$fecha_hasta[3];
        echo "<script>";
          //  echo "document.getElementById('ano_".$var5."').innerHTML='".$accion[0]['cnmd15_parametro_cobro']['ano']."';";
			echo "document.getElementById('cobroaguinaldos_".$var5."_1').innerHTML='".$accion[0]['cnmd15_parametro_cobro']['cobro_aguinaldo']."';";
			echo "document.getElementById('cobrovacaciones_".$var5."_1').innerHTML='".$accion[0]['cnmd15_parametro_cobro']['cobro_bono_vacacional']."';";
			echo "document.getElementById('disfruto_vacaciones_".$var5."_1').innerHTML='".$accion[0]['cnmd15_parametro_cobro']['disfruto_vacaciones']."';";
			echo "document.getElementById('cobroruralidad_".$var5."_1').innerHTML='".$accion[0]['cnmd15_parametro_cobro']['cobro_ruralidad']."';";
			echo "document.getElementById('cobroaguinaldos_".$var5."_1').disabled = true; ";
          	echo "document.getElementById('cobroaguinaldos_".$var5."_2').disabled = true; ";
          	echo "document.getElementById('cobrovacaciones_".$var5."_1').disabled = true; ";
          	echo "document.getElementById('cobrovacaciones_".$var5."_2').disabled = true; ";
          	echo "document.getElementById('disfruto_vacaciones_".$var5."_1').disabled = true; ";
          	echo "document.getElementById('disfruto_vacaciones_".$var5."_2').disabled = true; ";
          	echo "document.getElementById('cobroruralidad_".$var5."_1').disabled = true; ";
          	echo "document.getElementById('cobroruralidad_".$var5."_2').disabled = true; ";
			echo "document.getElementById('iconos_1_".$var5."').style.display = 'block'; ";
            echo "document.getElementById('iconos_2_".$var5."').style.display = 'none'; ";
		echo "</script>";


		//echo $accion[0]['cnmd15_parametro_cobro']['ano'];
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
  $accion =  $this->cnmd15_parametro_cobro->findAll("cod_presi = '".$cod_presi."' and cod_entidad  = '".$cod_entidad."' and  cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."'  and  cod_dep  = '".$cod_dep."' and  cod_tipo_nomina= '".$var."' and cod_cargo= '".$var2."' and cod_ficha= '".$var3."' and cedula_identidad= '".$var4."' and ano= '".$var5."' ", null, null);
  $cobroaguinaldo = $accion[0]['cnmd15_parametro_cobro']['cobro_aguinaldo'];
  $cobrovacaciones = $accion[0]['cnmd15_parametro_cobro']['cobro_bono_vacacional'];
  $disfrutovacaciones = $accion[0]['cnmd15_parametro_cobro']['disfruto_vacaciones'];
  $cobroruralidad = $accion[0]['cnmd15_parametro_cobro']['cobro_ruralidad'];
  //$fecha_desde = $fecha_desde[8].$fecha_desde[9].'/'.$fecha_desde[5].$fecha_desde[6].'/'.$fecha_desde[0].$fecha_desde[1].$fecha_desde[2].$fecha_desde[3];
  //$fecha_hasta = $fecha_hasta[8].$fecha_hasta[9].'/'.$fecha_hasta[5].$fecha_hasta[6].'/'.$fecha_hasta[0].$fecha_hasta[1].$fecha_hasta[2].$fecha_hasta[3];



		echo "<script>";
		  echo "document.getElementById('iconos_1_".$var5."').style.display = 'none'; ";
          echo "document.getElementById('iconos_2_".$var5."').style.display = 'block'; ";
          echo "document.getElementById('cobroaguinaldos_".$var5."_1').disabled = false; ";
          echo "document.getElementById('cobroaguinaldos_".$var5."_2').disabled = false; ";
          echo "document.getElementById('cobrovacaciones_".$var5."_1').disabled = false; ";
          echo "document.getElementById('cobrovacaciones_".$var5."_2').disabled = false; ";
          echo "document.getElementById('disfruto_vacaciones_".$var5."_1').disabled = false; ";
          echo "document.getElementById('disfruto_vacaciones_".$var5."_2').disabled = false; ";
          echo "document.getElementById('cobroruralidad_".$var5."_1').disabled = false; ";
          echo "document.getElementById('cobroruralidad_".$var5."_2').disabled = false; ";
          //echo "document.getElementById('td_1_".$var5."').innerHTML='<input type=hidden name=data[cnmp15_parametro_cobro][cod_nomina_".$var5."]  value=".$var." />" .
            //                                                        "<input type=hidden name=data[cnmp15_parametro_cobro][codigo_ficha_".$var5."]  value=".$var2." />" .
          		//                                                    "<input type=hidden name=data[cnmp15_parametro_cobro][codigo_cargo_".$var5."]  value=".$var3." />" .
		          //                                                  "<input type=hidden name=data[cnmp15_parametro_cobro][cedula_".$var5."]  value=".$var4." />" .
		            //                                                "<input type=hidden name=data[cnmp15_parametro_cobro][escala_".$var5."]  value=".$var5." />".$this->AddCeroR2($accion[0]['cnmd15_parametro_cobro']['escala'])."';";
		  //echo "document.getElementById('td_4_".$var5."').innerHTML='<input type=text name=data[cnmp15_parametro_cobro][sueldo_salario_$var5]  value=".$this->Formato2($accion[0]['cnmd15_parametro_cobro']['sueldo_basico'])." />';  ";
		  //echo "document.getElementById('td_5_".$var5."').innerHTML='<input style=text-align:right; type=text name=data[cnmp15_parametro_cobro][compensaciones_$var5] id=compensaciones_".$var5." readonly  value=".$this->Formato2($accion[0]['cnmd15_parametro_cobro']['compensaciones'])." />'; ";
	      //echo "document.getElementById('td_2_".$var5."').innerHTML='<input type=hidden name=data[cnmp15_parametro_cobro][fecha_desde_$var5]  value=$fecha_desde />$fecha_desde'; ";
		  //echo "document.getElementById('td_3_".$var5."').innerHTML='<input type=hidden name=data[cnmp15_parametro_cobro][fecha_hasta_$var5]  value=$fecha_hasta />$fecha_hasta'; ";
		echo "</script>";


    $this->set('cod_tipo_nomina', $var);
	$this->set('cod_cargo', $var2);
	$this->set('cod_ficha', $var3);
	$this->set('cedula', $var4);
	$this->set('ano', $var5);
	$this->set('cobroaguinaldo', $cobroaguinaldo);
	$this->set('cobrovacaciones', $cobrovacaciones);
	$this->set('disfrutovacaciones', $disfrutovacaciones);
	$this->set('cobroruralidad', $cobroruralidad);
	//$this->set('sueldo', $this->Formato2($accion[0]['cnmd15_parametro_cobro']['sueldo_basico']));


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
  $sql="BEGIN;  DELETE FROM cnmd15_parametro_cobro  WHERE cod_presi = '".$cod_presi."' and cod_entidad  = '".$cod_entidad."' and  cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."'  and  cod_dep  = '".$cod_dep."' and  cod_tipo_nomina= '".$var."' and cod_cargo= '".$var2."' and cod_ficha= '".$var3."' and cedula_identidad= '".$var4."' and ano= '".$var5."' ";
  $sw2 = $this->cnmd15_parametro_cobro->execute($sql);
			if($sw2>1){
                $this->cnmd15_parametro_cobro->execute("COMMIT;");
		        $this->set('Message_existe', 'LOS DATOS FUERON ELIMINADOS CORRECTAMENTE');
			}else{
				$this->cnmd15_parametro_cobro->execute("ROLLBACK;");
				$this->set('errorMessage', 'LOS DATOS NO FUERON ELIMINADOS - POR FAVOR INTENTE DE NUEVO');
			}//fin else

  $cont = 0;
  $cont     = $this->cnmd15_parametro_cobro->findCount($condicion.' and cod_tipo_nomina='.$var.' and cod_cargo='.$var2.' and cod_ficha='.$var3.' and cedula_identidad='.$var4 );
  $cont_aux = $this->cnmd15_parametro_cobro->findAll($condicion.' and cod_tipo_nomina='.$var.' and cod_cargo='.$var2.' and cod_ficha='.$var3.' and cedula_identidad='.$var4.' and ano='.$cont);
  //$fecha_hasta = $cont_aux[0]['cnmd15_parametro_cobro']['fecha_hasta'];
  //$ano = $fecha_hasta[0].$fecha_hasta[1].$fecha_hasta[2].$fecha_hasta[3];
  //$mes = $fecha_hasta[5].$fecha_hasta[6];
  //$dia = $fecha_hasta[8].$fecha_hasta[9];
  //$fecha_hasta = $this->dateAdd(1,$dia,$mes,$ano);
  $cont++;
  /*echo "<script>";
            echo "document.getElementById('ano').value='".$this->AddCeroR2($cont)."';";
			echo "document.getElementById('sueldo_salario').readOnly=true;";
			echo "document.getElementById('compensaciones').readOnly=true;";
			echo "document.getElementById('sueldo_salario').value='0,00';";
			echo "document.getElementById('compensaciones').value='0,00';";
			echo "document.getElementById('fecha1').value='".$fecha_hasta."';";
			echo "document.getElementById('fecha2').value='';";
 echo "</script>";

*/

			$this->consulta($var, $var2, $var3, $var4);
			$this->render('consulta');

}//fin funtion

















 }//fin class

?>