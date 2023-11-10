<?php

 class Cnmp16VacacionesController extends AppController{

	var $name = 'cnmp16_vacaciones';
	var $uses = array('cnmd07_transacciones_actuales', 'cnmd06_fichas', 'Cnmd01', 'cnmd16_jornada_extra', 'cnmd16_vacaciones_bonos_perma', 'cnmd16_vacaciones_bonos_temporal', 'v_cnmp16_vacaciones_bonos_permanente', 'v_cnmp16_vacaciones_bonos_temporal', 'cnmd06_datos_personales', 'cnmd03_transacciones','cnmd09_frecuencia',
                      'v_cnmd05', 'cnmd16_identif_transa', 'cnmd15_firmas_informes', 'cugd01_estados', 'ccfd03_instalacion', 'ccfd04_cierre_mes', "datos_personales_super_busqueda", "cnmd05", "v_cnmd06_fichas_datos_personales",'v_cnmd07_transacciones_actuales_frecuencias2');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');




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





function SQLCA($ano=null){ //sql para busqueda de codigos de arranque con y sin año
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



  function zero($x=null){
	if($x != null){
		if($x<10){
			$x="0".$x;
		}else if($x>=10 && $x<=99){
			$x=$x;
		}
	}
	return $x;
}//fin zero



function concatena($vector1=null, $nomVar=null){
	if($vector1 != null){
		foreach($vector1 as $x => $y){$cod[$x] = $this->zero($x).' - '.$y;}
		$this->set($nomVar, $cod);
	}//fin if
}//fin concatena


function llenar_pista_opcion($var1=null){

    $this->layout="ajax";
	$this->Session->write('pista_opcion', $var1);

}//fin fucntion


function buscar_persona($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
	$this->Session->write('pista_opcion', 2);
	echo "<script>$('select_obra_cod_obra').focus();</script>";
}//fin function


function buscar_por_pista2($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
$sql_like = "";
$tipo_nomina = $this->Session->read('tipo_nomina');
                    $cod_presi      =  $this->Session->read('SScodpresi');
					$cod_entidad    =  $this->Session->read('SScodentidad');
					$cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
					$cod_inst       =  $this->Session->read('SScodinst');
					$cod_dep        =  $this->Session->read('SScoddep');
	    if($var3==null){
	    	            $var2 = strtoupper($var2);
						$this->Session->write('pista', $var2);
						$var_like = $var2;
						$sql_like = $this->SQLCA(). " and cod_tipo_nomina='$tipo_nomina' and ".$this->busca_separado(array('cedula_identidad', 'primer_apellido', 'segundo_apellido', 'primer_nombre', 'segundo_nombre'), $var_like);
						$Tfilas=$this->v_cnmp16_vacaciones_bonos_permanente->findCount($sql_like);
						        if($Tfilas!=0){
						        	$pagina=1;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_cnmp16_vacaciones_bonos_permanente->findAll($sql_like,null,"primer_nombre, primer_apellido, ano, numero ASC",100,1,null);
                                    $sql = "";

                                    /*pr($datos_filas);


						                                    foreach($datos_filas as $ve){
						                                         if($sql==""){
						                                         	$sql .= "    a.cedula_identidad = '".$ve["v_cnmd06_fichas_datos_personales"]["cedula_identidad"]."' ";
						                                         }else{
						                                         	$sql .= " or a.cedula_identidad = '".$ve["v_cnmd06_fichas_datos_personales"]["cedula_identidad"]."'  ";
						                                         }
						                                    }//fin foreach
						                                    $dato_a =   $this->datos_personales_super_busqueda->execute("
						                                    SELECT
						                                          a.cod_presi,
																  a.cod_entidad,
																  a.cod_tipo_inst,
																  a.cod_inst,
																  a.cod_dep,
																  a.cod_tipo_nomina,
																  a.cod_cargo,
																  a.cod_ficha,
																  a.cedula_identidad,
																  a.condicion_actividad
														     FROM
														           cnmd06_fichas a,
														           cnmd05        b
														     WHERE
														     	  a.cod_presi         =  '".$cod_presi."'      and
																  a.cod_entidad       =  '".$cod_entidad."'    and
																  a.cod_tipo_inst     =  '".$cod_tipo_inst."'  and
																  a.cod_inst          =  '".$cod_inst."'       and
																  a.cod_dep           =  '".$cod_dep."'        and
																  a.cod_tipo_nomina   =  '".$tipo_nomina."'    and
														          b.cod_presi         =  a.cod_presi           and
																  b.cod_entidad       =  a.cod_entidad         and
																  b.cod_tipo_inst     =  a.cod_tipo_inst       and
																  b.cod_inst          =  a.cod_inst            and
																  b.cod_dep           =  a.cod_dep             and
																  b.cod_tipo_nomina   =  a.cod_tipo_nomina     and
																  b.cod_cargo         =  a.cod_cargo           and ( ".$sql." ) ");

                                                    */

                                    $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
						          //$this->set("dato_a",$dato_a);
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);
						$var_like = $var22;
						$sql_like = $this->SQLCA(). " and cod_tipo_nomina='$tipo_nomina' and ".$this->busca_separado(array('cedula_identidad', 'primer_apellido', 'segundo_apellido', 'primer_nombre', 'segundo_nombre'), $var_like);
						// $sql_like = $this->SQLCA(). " and (".$this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion')).")   ";
						$Tfilas=$this->v_cnmp16_vacaciones_bonos_permanente->findCount($sql_like);
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_cnmp16_vacaciones_bonos_permanente->findAll($sql_like,null,"primer_nombre, primer_apellido, ano, numero ASC",100,$pagina,null);
							        $sql = "";
                                               /*


						                                   foreach($datos_filas as $ve){
						                                         if($sql==""){
						                                         	$sql .= "    a.cedula_identidad = '".$ve["v_cnmd06_fichas_datos_personales"]["cedula_identidad"]."' ";
						                                         }else{
						                                         	$sql .= " or a.cedula_identidad = '".$ve["v_cnmd06_fichas_datos_personales"]["cedula_identidad"]."'  ";
						                                         }
						                                    }//fin foreach
											                $dato_a =   $this->datos_personales_super_busqueda->execute("
											                                    SELECT
											                                          a.cod_presi,
																					  a.cod_entidad,
																					  a.cod_tipo_inst,
																					  a.cod_inst,
																					  a.cod_dep,
																					  a.cod_tipo_nomina,
																					  a.cod_cargo,
																					  a.cod_ficha,
																					  a.cedula_identidad,
																					  a.condicion_actividad
																			     FROM
																			           cnmd06_fichas a,
																			           cnmd05        b
																			     WHERE
																			     	  a.cod_presi         =  '".$cod_presi."'      and
																					  a.cod_entidad       =  '".$cod_entidad."'    and
																					  a.cod_tipo_inst     =  '".$cod_tipo_inst."'  and
																					  a.cod_inst          =  '".$cod_inst."'       and
																					  a.cod_dep           =  '".$cod_dep."'        and
																					  a.cod_tipo_nomina   =  '".$tipo_nomina."'    and
																			          b.cod_presi         =  a.cod_presi           and
																					  b.cod_entidad       =  a.cod_entidad         and
																					  b.cod_tipo_inst     =  a.cod_tipo_inst       and
																					  b.cod_inst          =  a.cod_inst            and
																					  b.cod_dep           =  a.cod_dep             and
																					  b.cod_tipo_nomina   =  a.cod_tipo_nomina     and
																					  b.cod_cargo         =  a.cod_cargo           and ( ".$sql." ) ");

                                                   */



					                $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
									//$this->set("dato_a",$dato_a);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else
$this->set("opcion",$var1);
}//fin function


function llenar_pista_opcion3($var1=null){

    $this->layout="ajax";
	$this->Session->write('pista_opcion', $var1);

}//fin fucntion


function buscar_persona3($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
	$this->Session->write('pista_opcion', 2);
	echo "<script>$('select_obra_cod_obra').focus();</script>";
}//fin function


function buscar_por_pista3($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
$sql_like = "";
$tipo_nomina = $this->Session->read('codigo_tipo_nomina_b');
                    $cod_presi      =  $this->Session->read('SScodpresi');
					$cod_entidad    =  $this->Session->read('SScodentidad');
					$cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
					$cod_inst       =  $this->Session->read('SScodinst');
					$cod_dep        =  $this->Session->read('SScoddep');
	    if($var3==null){
	    	            $var2 = strtoupper($var2);
						$this->Session->write('pista', $var2);
						$var_like = $var2;
						$sql_like = $this->SQLCA(). " and cod_tipo_nomina='$tipo_nomina' and ".$this->busca_separado(array('cedula_identidad', 'primer_apellido', 'segundo_apellido', 'primer_nombre', 'segundo_nombre'), $var_like);
						$Tfilas=$this->v_cnmp16_vacaciones_bonos_permanente->findCount($sql_like);
						        if($Tfilas!=0){
						        	$pagina=1;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_cnmp16_vacaciones_bonos_permanente->findAll($sql_like,null,"primer_nombre, primer_apellido, ano, numero ASC",100,1,null);
                                    $sql = "";
                                    $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
						          //$this->set("dato_a",$dato_a);
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);
						$var_like = $var22;
						$sql_like = $this->SQLCA(). " and cod_tipo_nomina='$tipo_nomina' and ".$this->busca_separado(array('cedula_identidad', 'primer_apellido', 'segundo_apellido', 'primer_nombre', 'segundo_nombre'), $var_like);
						// $sql_like = $this->SQLCA(). " and (".$this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion')).")   ";
						$Tfilas=$this->v_cnmp16_vacaciones_bonos_permanente->findCount($sql_like);
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_cnmp16_vacaciones_bonos_permanente->findAll($sql_like,null,"primer_nombre, primer_apellido, ano, numero ASC",100,$pagina,null);
							        $sql = "";
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


function buscar_persona_p($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
	$this->Session->write('pista_opcion', 2);
	echo "<script>$('select_obra_cod_obra').focus();</script>";
}//fin function


function buscar_por_pista2_p($var1=null, $var2=null, $var3=null){

$this->layout="ajax";
$sql_like = "";
$tipo_nomina=   $this->Session->read('tipo_nomina');
                    $cod_presi      =  $this->Session->read('SScodpresi');
					$cod_entidad    =  $this->Session->read('SScodentidad');
					$cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
					$cod_inst       =  $this->Session->read('SScodinst');
					$cod_dep        =  $this->Session->read('SScoddep');
	    if($var3==null){
	    	            $var2 = strtoupper($var2);
						$this->Session->write('pista', $var2);
						$var_like = $var2;
						$sql_like = $this->SQLCA(). " and (".$this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion')).") and cod_tipo_nomina   =  '".$tipo_nomina."' and condicion_actividad='1'   ";
						$Tfilas=$this->v_cnmd06_fichas_datos_personales->findCount($sql_like);
						        if($Tfilas!=0){
						        	$pagina=1;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_cnmd06_fichas_datos_personales->findAll($sql_like,null,"primer_nombre, primer_apellido ASC",100,1,null);
                                    $sql = "";
                                    $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
						          //$this->set("dato_a",$dato_a);
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);
						$var_like = $var22;
						$sql_like = $this->SQLCA(). " and (".$this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion')).") and cod_tipo_nomina   =  '".$tipo_nomina."' and condicion_actividad='1'   ";
						$Tfilas=$this->v_cnmd06_fichas_datos_personales->findCount($sql_like);
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_cnmd06_fichas_datos_personales->findAll($sql_like,null,"primer_nombre, primer_apellido ASC",100,$pagina,null);
							        $sql = "";
					                $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
									//$this->set("dato_a",$dato_a);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else
$this->set("opcion",$var1);
}//fin function


function llenar_pista_opcion_p($var1=null){

    $this->layout="ajax";
	$this->Session->write('pista_opcion', $var1);

}//fin fucntion


function codigo_nomina($codigo=null){
	$this->layout = "ajax";
	$a = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$codigo,array('cod_tipo_nomina','denominacion', 'horas_laborables'));
    $this->set("a",$a[0]['Cnmd01']['cod_tipo_nomina']);
    $this->Session->write('tipo_nomina',$codigo);

	if($codigo!=null){

    				echo "<script>";
					    echo "document.getElementById('segunda_ventana').disabled=false;";
					echo "</script>";
	}else{
    				echo "<script>";
					    echo "document.getElementById('segunda_ventana').disabled=true;";
					echo "</script>";
	}
}//fin codigo_nomina

function denominacion_nomina($codigo){
	$this->layout = "ajax";
	$b = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$codigo,array('cod_tipo_nomina','denominacion'));
	$this->set("b",$b[0]['Cnmd01']['denominacion']);

}//fin denominacion_nomina


function Cfecha_ord($fecha,$tipo_return){
      if($tipo_return=="A-M-D"){
           $paso = explode('/', $fecha);
           $fecha_aux[] = $paso[2];
           $fecha_aux[] = $paso[1];
           $fecha_aux[] = $paso[0];
           $fecha_return=implode('-', $fecha_aux);
      }else if($tipo_return=="D/M/A"){
           $paso = explode('-', $fecha);
           $fecha_aux[] = $paso[2];
           $fecha_aux[] = $paso[1];
           $fecha_aux[] = $paso[0];
           $fecha_return=implode('/', $fecha_aux);
     }
     return $fecha_return;
}


function compara_fechas_completa($fecha1,$fecha2){

// La funcion usa expresiones regulares para que admita fechas tanto en formato: dd-mm-aaaa como con formato: dd/mm/aaaa

      if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha1))


              list($dia1,$mes1,$año1)=split("/",$fecha1);


      if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha1))


              list($dia1,$mes1,$año1)=split("-",$fecha1);
        if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha2))


              list($dia2,$mes2,$año2)=split("/",$fecha2);


      if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha2))


              list($dia2,$mes2,$año2)=split("-",$fecha2);
        $dif = mktime(0,0,0,$mes1,$dia1,$año1) - mktime(0,0,0, $mes2,$dia2,$año2);
        return ($dif);



/* **** EJEMPLO DEL FUNCIONAMIRNTO DE LA FUNCION ***

$f1="30/01/1993";

$f2="30-01-1992";

if (compara_fechas_completa($f1,$f2) <0)
      echo "$f1 es menor que $f2 <br>";

if (compara_fechas_completa($f1,$f2) >0)

      echo "$f1 es mayor que $f2 <br>";

if (compara_fechas_completa($f1,$f2) ==0)

      echo "$f1 es igual  que $f2 <br>";
*/

}


function get_edad($fecha_inicio, $fecha_fin, $modelo='cnmd06_fichas'){
	$age = $this->$modelo->execute("SELECT age('$fecha_inicio', '$fecha_fin')");
	$edad = $age[0][0]['age'];
	return $edad;

}


function fecha_session_desde($var=null, $var2=null, $var3=null, $var4=null){

  	$this->layout = "ajax";
	$var_fecha_hasta = '';
	$var_fecha_desde = $var."/".$var2."/".$var3;
	$this->Session->write('fecha_session_desde_vac', $var_fecha_desde);
	$var_fecha_hasta = $this->Session->read('fecha_session_hasta_vac');
  	if($var_fecha_hasta != ''){
		if($this->compara_fechas_completa($var_fecha_desde,$var_fecha_hasta) > 0){
			$this->set('errorMessage','La fecha inicio desde no puede ser mayor que la fecha finaliza hasta');
		}
  	}

	$this->render('funcion');

}//fin function


function fecha_session_hasta($var=null, $var2=null, $var3=null, $var4=null){

	$this->layout = "ajax";
	$var_fecha_desde = '';
	$var_fecha_hasta = $var."/".$var2."/".$var3;
	$this->Session->write('fecha_session_hasta_vac', $var_fecha_hasta);
	$var_fecha_desde = $this->Session->read('fecha_session_desde_vac');
  	if($var_fecha_desde != ''){
		if($this->compara_fechas_completa($var_fecha_hasta,$var_fecha_desde) < 0){
			$this->set('errorMessage','La fecha finaliza hasta no puede ser menor que la fecha inicio desde');
		}
  	}

       echo "<script>
                    document.getElementById('id_periodo_desde').value= '".($var3-1)."';
				    document.getElementById('id_periodo_hasta').value= '".$var3."';
          </script>";

  	$this->render('funcion');
}//fin function


function fecha_session_calculo($var=null, $var2=null, $var3=null, $var4=null){

  	$this->layout = "ajax";
	$var_fecha_calc = $var."/".$var2."/".$var3;
	$this->Session->write('fecha_session_calculo_vac', $var_fecha_calc);
	$anios_exp = $this->Session->read('anios_experiencia_vac');
	$fecha_de_ingreso = split("-", $this->Session->read('fecha_session_ingreso_vac'));
	$fecha_de_ingreso = $fecha_de_ingreso[2]."/".$fecha_de_ingreso[1]."/".$fecha_de_ingreso[0];

	if($this->compara_fechas_completa($var_fecha_calc,$fecha_de_ingreso) < 0){
		$this->set('errorMessage','Error Calculando Tiempo de Servicio, La fecha de c&aacute;lculo no puede ser menor que la fecha de ingreso');
       echo "<script>
                    document.getElementById('id_tdias').value= '';
				    document.getElementById('id_tmeses').value= '';
					document.getElementById('id_tanios').value= '';
					document.getElementById('id_anos_anteriores').value= '';
					document.getElementById('id_anos_antiguedad').value= '';
          </script>";
	}else{
        $tiempo_servicio  = $this->get_edad($var_fecha_calc, $this->Session->read('fecha_session_ingreso_vac'));
	    $tiempo_servicio  = explode(' ',$tiempo_servicio);
        $c_array = count($tiempo_servicio);

    if($c_array!=0){
  	    $dias = 0;
		$meses = 0;
		$anios = 0;
          for($t=0; $t<$c_array; $t++){
              if($tiempo_servicio[$t]=="mons" || $tiempo_servicio[$t]=="mon"){   $meses  = $tiempo_servicio[$t-1]; }
              if($tiempo_servicio[$t]=="day"  || $tiempo_servicio[$t]=="days"){  $dias   = $tiempo_servicio[$t-1]; }
              if($tiempo_servicio[$t]=="year" || $tiempo_servicio[$t]=="years"){ $anios  = $tiempo_servicio[$t-1]; }
          }
  	}else{
  	    $dias = 0;
		$meses = 0;
		$anios = 0;
	}

	if(isset($anios_exp) && $anios_exp!='' && $anios_exp!=0){ $anios_antig = $anios+$anios_exp; }else{ $anios_antig = $anios; $anios_exp = 0; }

       echo "<script>
                    document.getElementById('id_tdias').value= '".$dias."';
				    document.getElementById('id_tmeses').value= '".$meses."';
					document.getElementById('id_tanios').value= '".$anios."';
					document.getElementById('id_anos_anteriores').value= '".$anios_exp."';
					document.getElementById('id_anos_antiguedad').value= '".$anios_antig."';
          </script>";
	}

	$this->render('funcion');

}//fin function


function index($var=null){

	$this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
	$condicion_trans = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

	$verifica_transac = $this->cnmd16_identif_transa->findCount($condicion_trans);
	if($verifica_transac==0){
		$this->set('no_existe_transac', true);
		$this->set('errorMessage', 'DEBE REGISTRAR LA IDENTIFICACI&Oacute;N DE TRANSACCIONES PRIMERO');
	}else{

  $this->Session->delete('tipo_nomina');
  $this->Session->delete('fecha_session_desde_vac');
  $this->Session->delete('fecha_session_hasta_vac');
  $this->Session->delete('fecha_session_ingreso_vac');
  $this->Session->delete('anios_experiencia_vac');
  $this->Session->delete('valor_radio_vacacion');

       echo "<script>
          	document.getElementById('vi_observaciones').value= '';
			document.getElementById('vacacion_1').disabled = true;
			document.getElementById('vacacion_2').disabled = true;
			document.getElementById('vacacion_3').disabled = true;
			document.getElementById('vacacion_1').checked = false;
			document.getElementById('vacacion_2').checked = false;
			document.getElementById('vacacion_3').checked = false;
			document.getElementById('procesar').disabled = true;
          </script>";

if($var != null && $var=='1' || $var == '0'){
		$this->set('opc', $var);
		if($var == 0){
			$this->set('enabled','READONLY');
		}else{
			$this->set('enabled','');
		}

	}else{
		$this->set('opc', '1');
		$this->set('enabled','');
	}

   	$lista = $this->Cnmd01->generateList($this->SQLCA()." AND status_nomina IN (0,1)", $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	$this->concatenaN($lista, 'cod_tipo_nomina');

   	$this->set('cod_puesto','');

	}

} // fin function



function mostrar_busqueda($var_nom=null, $var1=null, $var2=null, $var3=null, $pag_num=null, $var_read=null){
	$this->layout="ajax";
  	$this->Session->delete('fecha_session_desde_vac');
  	$this->Session->delete('fecha_session_hasta_vac');
  	$this->Session->delete('fecha_session_ingreso_vac');
  	$this->Session->delete('anios_experiencia_vac');
  	$this->Session->delete('valor_radio_vacacion');
	$var2 = strtoupper($var2);
	$var_min = strtolower($var2);
	$var_wrap = ucfirst($var_min);
	$this->set('var2', $var2);
	$ano_ejecuc = $this->ano_ejecucion();
	$this->set('anio_ejec', $ano_ejecuc);
	$var = $var_nom;
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
	$condi_transac = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

    $cod_cargo[1]     = "";
    $cod_ficha[1]     = "";
    $fichas        = "";
    $datos_cnmd05  = "";
    $datos_peronal = "";
    $acepta        = "no";
    $cedu          = "";

if($pag_num==null){$pag_num = 1;}

	if($var!=null){
	$c_nomina = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$var, array('cod_tipo_nomina','denominacion'));
	$deno_nomi = $c_nomina!=null ? $c_nomina[0]['Cnmd01']['denominacion'] : "";

    echo "<script>
    		document.getElementById('cod_tipo_nomina').disabled = true;
    		document.getElementById('codigo_tipo_nom').value = '".mascara($var, 3)."';
			document.getElementById('denominacion_deno_nom').value = '".$deno_nomi."';
     	</script>";

      $fichas_aux    =   $this->cnmd06_fichas->findAll($condicion.' and cod_tipo_nomina='.$var.' and cedula_identidad='.$var1.'  '.' and cod_cargo='.$var2);

$i = 0;

      foreach($fichas_aux as $aux){ $i++;
             $cod_cargo[$i] =  $aux['cnmd06_fichas']['cod_cargo'];
             $cod_ficha[$i] =  $aux['cnmd06_fichas']['cod_ficha'];
      }//fin foreach

if($cod_cargo[$pag_num]!=""){
	 $fichas        =   $this->cnmd06_fichas->findAll($condicion.' and cod_tipo_nomina='.$var.' and cod_cargo='.$cod_cargo[$pag_num].' and cod_ficha='.$cod_ficha[$pag_num].' and cedula_identidad='.$var1.'  ');
	 $datos_peronal =   $this->cnmd06_datos_personales->findAll(' cedula_identidad='.$var1.'  ');
	 $datos_cnmd05  =   $this->v_cnmd05->findAll($condicion.' and cod_tipo_nomina='.$var.' and cod_cargo='.$cod_cargo[$pag_num].' ');
	 $cont_dato_vac =   $this->cnmd16_vacaciones_bonos_perma->findAll($condicion.' and cod_tipo_nomina='.$var.' and cod_cargo='.$cod_cargo[$pag_num].' and cod_ficha='.$cod_ficha[$pag_num].' and cedula_identidad='.$var1, 'numero', 'ano, numero DESC');
	 $cont_vac      =   $cont_dato_vac != null ? $cont_dato_vac[0]['cnmd16_vacaciones_bonos_perma']['numero'] : 0;
	 $this->set('numero_vac', $cont_vac+1);
   if($i>=2 && $pag_num!=$i){$acepta="si"; $cedu = $var2; $this->set('pag_num', $pag_num); }
	$this->set('ficha', $fichas);
	$this->set('datos_cnmd05', $datos_cnmd05);
    $this->set('datos_personales', $datos_peronal);
    $this->set('aceptacion', $acepta);
    $this->set('var_2', $var);
	$this->set('cedula', $cedu);

	$this->Session->write('fecha_session_ingreso_vac', $fichas[0]['cnmd06_fichas']['fecha_ingreso']);

	// 	CALCULA ANOS DE EXPERIENCIA DEL TRABAJADOR:

	$execute_anos_exp = $this->cnmd06_datos_personales->execute("select calculos_incidencia('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$var."', '".$cod_cargo[$pag_num]."', '".$cod_ficha[$pag_num]."', '".$var1."');");
	$anos_experie = $execute_anos_exp!=null ? $execute_anos_exp[0][0]["calculos_incidencia"] : 0;
	$this->Session->write('anios_experiencia_vac', $anos_experie);

	if($anos_experie!=null && $anos_experie!=0){
		$this->set('anos_experiencia_adm', $anos_experie);
	}else{
		$this->set('anos_experiencia_adm', '');
	}

	$identif_transacciones = $this->cnmd16_identif_transa->findAll($condi_transac, 'cod_asig_vaca');

	if ($identif_transacciones!=null){
		$cod_vaca = $identif_transacciones[0]['cnmd16_identif_transa']['cod_asig_vaca'];
	}

	$sql_salario = $this->cnmd06_fichas->execute("SELECT f_cnmp16_devolver_salario_asig_vision(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, 1, $cod_vaca) AS salario FROM cnmd06_fichas WHERE $condicion and cod_tipo_nomina='$var' and cod_cargo='".$cod_cargo[$pag_num]."' and cod_ficha='".$cod_ficha[$pag_num]."' and cedula_identidad='$var1'");
	if(!empty($sql_salario)){
		$salario = $sql_salario[0][0]['salario'];
	}else{
		$salario = 0;
		}

	$sueldo_diario = ($salario/30);

	$this->set('sueldo_mensual', $salario);
	$this->set('sueldo_diario', $sueldo_diario);

	$datos_resulta = $this->v_cnmp16_vacaciones_bonos_permanente->findAll($condicion.' and cod_tipo_nomina='.$var.' and cod_cargo='.$cod_cargo[$pag_num].' and cod_ficha='.$cod_ficha[$pag_num].' and cedula_identidad='.$var1, null, 'ano, numero ASC');
	$this->set('datos_griya_vaca', $datos_resulta);

}else{
   $this->set('mensajeError', 'La cedula de identidad no existe');
   $this->set('var_2', $var);
   $this->set('cedula', '');
   $this->set('pag_num', $pag_num);
   $this->set('aceptacion', $acepta);
   $this->set('sueldo_mensual', 0);
   $this->set('sueldo_diario', 0);
   $this->set('datos_griya_vaca', array());

}//fin else

   }else{

        echo'<script>';
			echo'document.getElementById("cod_cargo").value = ""; ';
  			echo'document.getElementById("cod_puesto").value = "";';
  			echo'document.getElementById("deno_puesto").value = "";';
		echo'</script>';

	}//fin else

}//fin function


function mostrar_busqueda_p($var_nom=null, $var1=null, $var2=null, $var3=null, $ano_p=null, $numero_p=null, $pag_num=null, $var_read=null){
	$this->layout="ajax";
	// $var = $this->Session->read('tipo_nomina');
	$var = $var_nom;
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
	$condi_transac = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

	$busa_pers = $this->v_cnmp16_vacaciones_bonos_permanente->findCount($condicion." and cod_tipo_nomina='$var' and cod_cargo='$var2' and cod_ficha='$var3' and cedula_identidad='$var1'");

	if($busa_pers==0){
		$this->set('persona_verif', true);

  	$this->Session->delete('fecha_session_desde_vac');
  	$this->Session->delete('fecha_session_hasta_vac');
  	$this->Session->delete('fecha_session_ingreso_vac');
  	$this->Session->delete('anios_experiencia_vac');
  	$this->Session->delete('valor_radio_vacacion');
	$var2 = strtoupper($var2);
	$var_min = strtolower($var2);
	$var_wrap = ucfirst($var_min);
	$this->set('var2', $var2);
	$ano_ejecuc = $this->ano_ejecucion();
	$this->set('anio_ejec', $ano_ejecuc);

    $cod_cargo[1]     = "";
    $cod_ficha[1]     = "";
    $fichas        = "";
    $datos_cnmd05  = "";
    $datos_peronal = "";
    $acepta        = "no";
    $cedu          = "";

if($pag_num==null){$pag_num = 1;}

	if($var != null){

      $fichas_aux    =   $this->cnmd06_fichas->findAll($condicion.' and cod_tipo_nomina='.$var.' and cedula_identidad='.$var1.'  '.' and cod_cargo='.$var2);

$i = 0;

      foreach($fichas_aux as $aux){ $i++;
             $cod_cargo[$i] =  $aux['cnmd06_fichas']['cod_cargo'];
             $cod_ficha[$i] =  $aux['cnmd06_fichas']['cod_ficha'];
      }//fin foreach

if($cod_cargo[$pag_num]!=""){

	 $fichas        =   $this->cnmd06_fichas->findAll($condicion.' and cod_tipo_nomina='.$var.' and cod_cargo='.$cod_cargo[$pag_num].' and cod_ficha='.$cod_ficha[$pag_num].' and cedula_identidad='.$var1.'  ');
	 $datos_peronal =   $this->cnmd06_datos_personales->findAll(' cedula_identidad='.$var1.'  ');
	 $datos_cnmd05  =   $this->v_cnmd05->findAll($condicion.' and cod_tipo_nomina='.$var.' and cod_cargo='.$cod_cargo[$pag_num].' ');
	 $cont_dato_vac =   $this->cnmd16_vacaciones_bonos_perma->findAll($condicion.' and cod_tipo_nomina='.$var.' and cod_cargo='.$cod_cargo[$pag_num].' and cod_ficha='.$cod_ficha[$pag_num].' and cedula_identidad='.$var1, 'numero', 'ano, numero DESC');
	 $cont_vac      =   $cont_dato_vac != null ? $cont_dato_vac[0]['cnmd16_vacaciones_bonos_perma']['numero'] : 0;
	 $this->set('numero_vac', $cont_vac+1);
   if($i>=2 && $pag_num!=$i){$acepta="si"; $cedu = $var2; $this->set('pag_num', $pag_num); }
	$this->set('ficha', $fichas);
	$this->set('datos_cnmd05', $datos_cnmd05);
    $this->set('datos_personales', $datos_peronal);
    $this->set('aceptacion', $acepta);
    $this->set('var_2', $var);
	$this->set('cedula', $cedu);

	$this->Session->write('fecha_session_ingreso_vac', $fichas[0]['cnmd06_fichas']['fecha_ingreso']);

	// 	CALCULA ANOS DE EXPERIENCIA DEL TRABAJADOR:

	$execute_anos_exp = $this->cnmd06_datos_personales->execute("select calculos_incidencia('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$var."', '".$cod_cargo[$pag_num]."', '".$cod_ficha[$pag_num]."', '".$var1."');");
	$anos_experie = $execute_anos_exp!=null ? $execute_anos_exp[0][0]["calculos_incidencia"] : 0;
	$this->Session->write('anios_experiencia_vac', $anos_experie);

	if($anos_experie!=null && $anos_experie!=0){
		$this->set('anos_experiencia_adm', $anos_experie);
	}else{
		$this->set('anos_experiencia_adm', '');
	}

	$identif_transacciones = $this->cnmd16_identif_transa->findAll($condi_transac, 'cod_asig_vaca');

	if ($identif_transacciones != null){
		$cod_vaca = $identif_transacciones[0]['cnmd16_identif_transa']['cod_asig_vaca'];
	}

	$sql_salario = $this->cnmd06_fichas->execute("SELECT f_cnmp16_devolver_salario_asig_vision(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, 1, $cod_vaca) AS salario FROM cnmd06_fichas WHERE $condicion and cod_tipo_nomina='$var' and cod_cargo='".$cod_cargo[$pag_num]."' and cod_ficha='".$cod_ficha[$pag_num]."' and cedula_identidad='$var1'");
	if(!empty($sql_salario)){
		$salario = $sql_salario[0][0]['salario'];
	}else{
		$salario = 0;
		}

	$sueldo_diario = ($salario/30);

	$this->set('sueldo_mensual', $salario);
	$this->set('sueldo_diario', $sueldo_diario);

	$datos_resulta = $this->v_cnmp16_vacaciones_bonos_permanente->findAll($condicion.' and cod_tipo_nomina='.$var.' and cod_cargo='.$cod_cargo[$pag_num].' and cod_ficha='.$cod_ficha[$pag_num].' and cedula_identidad='.$var1, null, 'ano, numero ASC');
	$this->set('datos_griya_vaca', $datos_resulta);

}else{
   $this->set('mensajeError', 'La cedula de identidad no existe');
   $this->set('var_2', $var);
   $this->set('cedula', '');
   $this->set('pag_num', $pag_num);
   $this->set('aceptacion', $acepta);

}//fin else

   }else{

        echo'<script>';
			echo'document.getElementById("cod_cargo").value = ""; ';
  			echo'document.getElementById("cod_puesto").value = "";';
  			echo'document.getElementById("deno_puesto").value = "";';
		echo'</script>';

	}//fin else






	}else{

	$c_nomina = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$var, array('cod_tipo_nomina','denominacion'));
	$deno_nomi = $c_nomina!=null ? $c_nomina[0]['Cnmd01']['denominacion'] : "";

    echo "<script>
    		document.getElementById('cod_tipo_nomina').disabled = true;
    		document.getElementById('codigo_tipo_nom').value = '".mascara($var, 3)."';
			document.getElementById('denominacion_deno_nom').value = '".$deno_nomi."';
     	</script>";

	isset($var_read) ? $this->set('readonly', 'readonly') : $this->set('readonly', 'readonly');
	$pagina=isset($pagina)?$pagina:1;

  	$this->Session->delete('fecha_session_desde_vac');
  	$this->Session->delete('fecha_session_hasta_vac');
  	$this->Session->delete('fecha_session_ingreso_vac');
  	$this->Session->delete('anios_experiencia_vac');
  	$this->Session->delete('valor_radio_vacacion');

	$Tfilas = $this->v_cnmp16_vacaciones_bonos_permanente->findCount($condicion);

if($Tfilas!=0){
		$this->set('pag_cant',$pagina.'/'.$Tfilas);
 		$this->set('ultimo',$Tfilas);
		$datos_result = $this->v_cnmp16_vacaciones_bonos_permanente->findAll($condicion." and cod_tipo_nomina='$var' and cod_cargo='$var2' and cod_ficha='$var3' and cedula_identidad='$var1' and ano='$ano_p' and numero='$numero_p'", null, null, 1, $pagina, null);
		$this->set('datos_vacaciones', $datos_result);
		$var           = $datos_result[0]['v_cnmp16_vacaciones_bonos_permanente']['cod_tipo_nomina'];
		$cod_cargo     = $datos_result[0]['v_cnmp16_vacaciones_bonos_permanente']['cod_cargo'];
		$var1          = $datos_result[0]['v_cnmp16_vacaciones_bonos_permanente']['cedula_identidad'];
		$cod_ficha     = $datos_result[0]['v_cnmp16_vacaciones_bonos_permanente']['cod_ficha'];
		$fecha_calculo = $datos_result[0]['v_cnmp16_vacaciones_bonos_permanente']['fecha_calculo'];
		$fecha_ingreso = $datos_result[0]['v_cnmp16_vacaciones_bonos_permanente']['fecha_ingreso'];

		$this->set('codigo_tipo_nomina', $var);
		$denominacion = $this->Cnmd01->field('denominacion', $conditions = $condicion." and cod_tipo_nomina='$var'", $order ="cod_tipo_nomina ASC");
		$this->set('denominacion_nomina', $denominacion);

		$datos_cnmd05 = $this->v_cnmd05->findAll($condicion.' and cod_tipo_nomina='.$var.' and cod_cargo='.$cod_cargo.' ');
		$this->set('datos_cnmd05', $datos_cnmd05);

		$datos_peronal =   $this->cnmd06_datos_personales->findAll(' cedula_identidad='.$var1.'  ');
		$this->set('datos_personales', $datos_peronal);

	// 	CALCULA ANOS DE EXPERIENCIA DEL TRABAJADOR:

	$execute_anos_exp = $this->cnmd06_datos_personales->execute("select calculos_incidencia('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$var."', '".$cod_cargo."', '".$cod_ficha."', '".$var1."');");
	$anos_experie = $execute_anos_exp!=null ? $execute_anos_exp[0][0]["calculos_incidencia"] : 0;

	if($anos_experie!=null && $anos_experie!=0){
		$this->set('anos_experiencia_adm', $anos_experie);
	}else{
		$this->set('anos_experiencia_adm', '');
	}

	$datos_resulta = $this->v_cnmp16_vacaciones_bonos_permanente->findAll($condicion." and cod_tipo_nomina='$var' and cod_cargo='$var2' and cod_ficha='$var3' and cedula_identidad='$var1'", null, 'ano, numero ASC');
	$this->set('datos_griya_vaca', $datos_resulta);

	$this->Session->write('anios_experiencia_vac', $anos_experie);
	$this->Session->write('fecha_session_desde_vac', $this->Cfecha_ord($datos_result[0]['v_cnmp16_vacaciones_bonos_permanente']['fecha_inicio'], "D/M/A"));
	$this->Session->write('fecha_session_hasta_vac', $this->Cfecha_ord($datos_result[0]['v_cnmp16_vacaciones_bonos_permanente']['fecha_termina'], "D/M/A"));
	$this->Session->write('fecha_session_ingreso_vac', $this->Cfecha_ord($fecha_ingreso, "D/M/A"));
	$this->Session->write('valor_radio_vacacion', $datos_result[0]['v_cnmp16_vacaciones_bonos_permanente']['tipo_operacion']);

        $tiempo_servicio  = $this->get_edad($fecha_calculo, $fecha_ingreso);
	    $tiempo_servicio  = explode(' ',$tiempo_servicio);
        $c_array = count($tiempo_servicio);

    if($c_array!=0){
  	    $dias = 0;
		$meses = 0;
		$anios = 0;
          for($t=0; $t<$c_array; $t++){
              if($tiempo_servicio[$t]=="mons" || $tiempo_servicio[$t]=="mon"){   $meses  = $tiempo_servicio[$t-1]; }
              if($tiempo_servicio[$t]=="day"  || $tiempo_servicio[$t]=="days"){  $dias   = $tiempo_servicio[$t-1]; }
              if($tiempo_servicio[$t]=="year" || $tiempo_servicio[$t]=="years"){ $anios  = $tiempo_servicio[$t-1]; }
          }
  	}else{
  	    $dias = 0;
		$meses = 0;
		$anios = 0;
	}

	if(isset($anos_experie) && $anos_experie!='' && $anos_experie!=0){ $anios_antig = $anios+$anos_experie; }else{ $anios_antig = $anios; $anos_experie = 0; }

	$this->set('c_dias', $dias);
	$this->set('c_meses', $meses);
	$this->set('c_anios', $anios);
	$this->set('c_anos_experie', $anos_experie);
	$this->set('c_anios_antig', $anios_antig);

$this->set('pag_num', $pagina);
$this->set('pagina', $pagina);

$this->set('siguiente',$pagina+1);
$this->set('anterior',$pagina-1);
$this->set('total_paginas',$Tfilas);

$this->bt_nav($Tfilas,$pagina);

}else{

	$this->set('pag_num', 0);
 	$this->set('totalPages_Recordset1', '');
 	$this->set('mensajeError', 'No existen datos');

}//fin else


	}

}//fin function



function consulta($pagina=null,$var_read=null){

	$this->layout = "ajax";
	$cod_presi     = $this->Session->read('SScodpresi');
	$cod_entidad   = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst      = $this->Session->read('SScodinst');
	$cod_dep       = $this->Session->read('SScoddep');
	$condicion     = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

	isset($var_read) ? $this->set('readonly', 'readonly') : $this->set('readonly', '');
	$pagina=isset($pagina)?$pagina:1;

  	$this->Session->delete('fecha_session_desde_vac');
  	$this->Session->delete('fecha_session_hasta_vac');
  	$this->Session->delete('fecha_session_ingreso_vac');
  	$this->Session->delete('anios_experiencia_vac');
  	$this->Session->delete('valor_radio_vacacion');

	$Tfilas = $this->v_cnmp16_vacaciones_bonos_permanente->findCount($condicion);

if($Tfilas!=0){
		$this->set('pag_cant',$pagina.'/'.$Tfilas);
 		$this->set('ultimo',$Tfilas);
		$datos_result = $this->v_cnmp16_vacaciones_bonos_permanente->findAll($condicion, null, 'cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, ano, numero ASC',1,$pagina, null);
		$this->set('datos_vacaciones', $datos_result);
		$var           = $datos_result[0]['v_cnmp16_vacaciones_bonos_permanente']['cod_tipo_nomina'];
		$cod_cargo     = $datos_result[0]['v_cnmp16_vacaciones_bonos_permanente']['cod_cargo'];
		$cod_ficha     = $datos_result[0]['v_cnmp16_vacaciones_bonos_permanente']['cod_ficha'];
		$var1          = $datos_result[0]['v_cnmp16_vacaciones_bonos_permanente']['cedula_identidad'];
		$fecha_calculo = $datos_result[0]['v_cnmp16_vacaciones_bonos_permanente']['fecha_calculo'];
		$fecha_ingreso = $datos_result[0]['v_cnmp16_vacaciones_bonos_permanente']['fecha_ingreso'];

		$this->set('codigo_tipo_nomina', $var);
		$denominacion = $this->Cnmd01->field('denominacion', $conditions = $condicion." and cod_tipo_nomina='$var'", $order ="cod_tipo_nomina ASC");
		$this->set('denominacion_nomina', $denominacion);

		$datos_cnmd05 = $this->v_cnmd05->findAll($condicion.' and cod_tipo_nomina='.$var.' and cod_cargo='.$cod_cargo.' ');
		$this->set('datos_cnmd05', $datos_cnmd05);

		$datos_peronal =   $this->cnmd06_datos_personales->findAll(' cedula_identidad='.$var1.'  ');
		$this->set('datos_personales', $datos_peronal);

	// 	CALCULA ANOS DE EXPERIENCIA DEL TRABAJADOR:

	$execute_anos_exp = $this->cnmd06_datos_personales->execute("select calculos_incidencia('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$var."', '".$cod_cargo."', '".$cod_ficha."', '".$var1."');");
	$anos_experie = $execute_anos_exp!=null ? $execute_anos_exp[0][0]["calculos_incidencia"] : 0;

	if($anos_experie!=null && $anos_experie!=0){
		$this->set('anos_experiencia_adm', $anos_experie);
	}else{
		$this->set('anos_experiencia_adm', '');
	}

	$datos_resulta = $this->v_cnmp16_vacaciones_bonos_permanente->findAll($condicion." and cod_tipo_nomina='$var' and cod_cargo='$cod_cargo' and cod_ficha='$cod_ficha' and cedula_identidad='$var1'", null, 'ano, numero ASC');
	$this->set('datos_griya_vaca', $datos_resulta);

	$this->Session->write('anios_experiencia_vac', $anos_experie);
	$this->Session->write('fecha_session_desde_vac', $this->Cfecha_ord($datos_result[0]['v_cnmp16_vacaciones_bonos_permanente']['fecha_inicio'], "D/M/A"));
	$this->Session->write('fecha_session_hasta_vac', $this->Cfecha_ord($datos_result[0]['v_cnmp16_vacaciones_bonos_permanente']['fecha_termina'], "D/M/A"));
	$this->Session->write('fecha_session_ingreso_vac', $this->Cfecha_ord($fecha_ingreso, "D/M/A"));
	$this->Session->write('valor_radio_vacacion', $datos_result[0]['v_cnmp16_vacaciones_bonos_permanente']['tipo_operacion']);

        $tiempo_servicio  = $this->get_edad($fecha_calculo, $fecha_ingreso);
	    $tiempo_servicio  = explode(' ',$tiempo_servicio);
        $c_array = count($tiempo_servicio);

    if($c_array!=0){
  	    $dias = 0;
		$meses = 0;
		$anios = 0;
          for($t=0; $t<$c_array; $t++){
              if($tiempo_servicio[$t]=="mons" || $tiempo_servicio[$t]=="mon"){   $meses  = $tiempo_servicio[$t-1]; }
              if($tiempo_servicio[$t]=="day"  || $tiempo_servicio[$t]=="days"){  $dias   = $tiempo_servicio[$t-1]; }
              if($tiempo_servicio[$t]=="year" || $tiempo_servicio[$t]=="years"){ $anios  = $tiempo_servicio[$t-1]; }
          }
  	}else{
  	    $dias = 0;
		$meses = 0;
		$anios = 0;
	}

	if(isset($anos_experie) && $anos_experie!='' && $anos_experie!=0){ $anios_antig = $anios+$anos_experie; }else{ $anios_antig = $anios; $anos_experie = 0; }

	$this->set('c_dias', $dias);
	$this->set('c_meses', $meses);
	$this->set('c_anios', $anios);
	$this->set('c_anos_experie', $anos_experie);
	$this->set('c_anios_antig', $anios_antig);

$this->set('pag_num', $pagina);
$this->set('pagina', $pagina);

$this->set('siguiente',$pagina+1);
$this->set('anterior',$pagina-1);
$this->set('total_paginas',$Tfilas);

$this->bt_nav($Tfilas,$pagina);

}else{

	$this->set('errorMessage', 'No existen datos');
	$this->index();
	$this->render('index');


}//fin else

}//fin consulta



function seleccion_radio_vac($var_radio=null){
	$this->layout = "ajax";
	$this->Session->delete('valor_radio_vacacion');
	$this->Session->write('valor_radio_vacacion', $var_radio);
}


function modificar_consulta($paginam=null){

	$this->layout = "ajax";
	$this->set('paginam', $paginam);
		echo "<script>
			document.getElementById('id_periodo_desde').readOnly = false;
			document.getElementById('id_periodo_hasta').readOnly = false;
			document.getElementById('id_cantidad_vacaciones').readOnly = false;
			document.getElementById('id_dias_inhabiles').readOnly = false;
			document.getElementById('id_numero_lunes').readOnly = false;
			document.getElementById('vi_observaciones').readOnly = false;
			document.getElementById('vacacion_1').disabled = false;
			document.getElementById('vacacion_2').disabled = false;
			document.getElementById('vacacion_3').disabled = false;
			document.getElementById('procesar').disabled = false;
			</script>";

	$this->set('mensaje','Puede modificar los datos');

}


function concatena_tres_digitos($vector1=null, $nomVar=null, $extra=null){
	$cod = array();
	if($vector1 != null){
		foreach($vector1 as $x => $y){
			if($extra!=null){
						      if($x<99 && $x>9){
						$cod[$x] = $extra.'0'.$x.' - '.$y;
				    }else if($x<=9){
						$cod[$x] = $extra.'00'.$x.' - '.$y;
					}else{
						$cod[$x] = $extra.''.$x.' - '.$y;
					}

			}else{
				      if($x<99 && $x>9){
					$cod[$x] = '0'.$x.' - '.$y;
			    }else if($x<=9){
					$cod[$x] = '00'.$x.' - '.$y;
				}else{
					$cod[$x] = ''.$x.' - '.$y;
				}
			}
		}
		//print_r($cod);
	}

	$this->set($nomVar, $cod);
}//fin function





function puesto_busqueda($var=null){
	$this->layout = "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
	$this->Session->delete('fecha_session_ingreso_vac');
	$this->Session->delete('anios_experiencia_vac');

	echo "<script>
    		document.getElementById('ve_regresarid').disabled=false;
		    document.getElementById('tercera_ventana').disabled=false;
		    document.getElementById('ve_consultarid').disabled=true;
		</script>";

	if($var!=null){
		$this->set('var', $var);

		echo'<script>';
			echo'document.getElementById("cod_cargo").value = ""; ';
  			echo'document.getElementById("cod_puesto").value = "";';
  			echo'document.getElementById("deno_puesto").value = "";';

			echo'document.getElementById("codigo_ficha2").value = ""; ';
  			echo'document.getElementById("cedula_identidad2").value = "";';
  			echo'document.getElementById("primer_apellido2").value = "";';
  			echo'document.getElementById("segundo_apellido2").value = "";';
  			echo'document.getElementById("primer_nombre2").value = "";';
  			echo'document.getElementById("segundo_nombre2").value = "";';
  			echo'document.getElementById("id_fecha_ingreso").value = "";';

		echo "document.getElementById('id_ano').value = '';
			document.getElementById('id_numero').value = '';
			document.getElementById('fecha_desde').value = '';
			document.getElementById('fecha_hasta').value = '';
			document.getElementById('id_periodo_desde').value = '';
			document.getElementById('id_periodo_hasta').value = '';
			document.getElementById('fecha_calculo').value = '';
			document.getElementById('id_cantidad_vacaciones').value = '';
			document.getElementById('id_dias_inhabiles').value = '';
			document.getElementById('id_numero_lunes').value = '';
			document.getElementById('vi_observaciones').value = '';
			document.getElementById('id_tdias').value = '';
			document.getElementById('id_tmeses').value = '';
			document.getElementById('id_tanios').value = '';
			document.getElementById('id_anos_anteriores').value = '';
			document.getElementById('id_anos_antiguedad').value = '';
			document.getElementById('id_salario_mensual').value = '';
			document.getElementById('id_salario_diario').value = '';
			document.getElementById('vacacion_1').disabled = true;
			document.getElementById('vacacion_2').disabled = true;
			document.getElementById('vacacion_3').disabled = true;
			document.getElementById('vacacion_1').checked = false;
			document.getElementById('vacacion_2').checked = false;
			document.getElementById('vacacion_3').checked = false;
			document.getElementById('procesar').disabled = true;";
		echo'</script>';

	}else{
		$this->set('var2', null);
		$this->set('var', null);
		$this->set('cod_puesto', array());


		echo'<script>';
			echo'document.getElementById("cod_cargo").value = ""; ';
  			echo'document.getElementById("cod_puesto").value = "";';
  			echo'document.getElementById("deno_puesto").value = "";';

  			// echo'document.getElementById("buscar_cedula_input").disabled = true;';

			echo'document.getElementById("codigo_ficha2").value = ""; ';
  			echo'document.getElementById("cedula_identidad2").value = "";';
  			echo'document.getElementById("primer_apellido2").value = "";';
  			echo'document.getElementById("segundo_apellido2").value = "";';
  			echo'document.getElementById("primer_nombre2").value = "";';
  			echo'document.getElementById("segundo_nombre2").value = "";';
  			echo'document.getElementById("id_fecha_ingreso").value = "";';

		echo "document.getElementById('id_ano').value = '';
			document.getElementById('id_numero').value = '';
			document.getElementById('fecha_desde').value = '';
			document.getElementById('fecha_hasta').value = '';
			document.getElementById('id_periodo_desde').value = '';
			document.getElementById('id_periodo_hasta').value = '';
			document.getElementById('fecha_calculo').value = '';
			document.getElementById('id_cantidad_vacaciones').value = '';
			document.getElementById('id_dias_inhabiles').value = '';
			document.getElementById('id_numero_lunes').value = '';
			document.getElementById('vi_observaciones').value = '';
			document.getElementById('id_tdias').value = '';
			document.getElementById('id_tmeses').value = '';
			document.getElementById('id_tanios').value = '';
			document.getElementById('id_anos_anteriores').value = '';
			document.getElementById('id_anos_antiguedad').value = '';
			document.getElementById('id_salario_mensual').value = '';
			document.getElementById('id_salario_diario').value = '';
			document.getElementById('vacacion_1').disabled = true;
			document.getElementById('vacacion_2').disabled = true;
			document.getElementById('vacacion_3').disabled = true;
			document.getElementById('vacacion_1').checked = false;
			document.getElementById('vacacion_2').checked = false;
			document.getElementById('vacacion_3').checked = false;
			document.getElementById('procesar').disabled = true;";
		echo'</script>';

	}

}//fin function




function calcular_vacacion(){
	$this->layout = "ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
	$condi_transac = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;

	if(!empty($this->data['cnmp16_vacaciones'])){
  		$cod_tipo_nomina     = (int) $this->data['cnmp16_vacaciones']['codigo_tipo_nomina'];
  		$cod_cargo           = (int) $this->data['cnmp16_vacaciones']['cod_cargo'];
  		$cod_ficha           = (int) $this->data['cnmp16_vacaciones']['codigo_ficha2'];
  		$cedula_identidad    = (int) $this->data['cnmp16_vacaciones']['cedula_identidad2'];
  		$fecha_ingreso       = $this->data['cnmp16_vacaciones']['fecha_ingreso2'];
  		$ano_ejecucion       = (int) $this->data['cnmp16_vacaciones']['ano'];
  		$numero              = (int) $this->data['cnmp16_vacaciones']['numero'];
  		$fecha_desde         = $this->data['cnmp16_vacaciones']['fecha_desde'];
  		$fecha_hasta         = $this->data['cnmp16_vacaciones']['fecha_hasta'];
  		$periodo_desde       = (int) $this->data['cnmp16_vacaciones']['periodo_desde'];
  		$periodo_hasta       = (int) $this->data['cnmp16_vacaciones']['periodo_hasta'];
  		$fecha_calculo       = $this->data['cnmp16_vacaciones']['fecha_calculo'];
  		$cantidad_vacaciones = (int) $this->data['cnmp16_vacaciones']['cantidad_vacaciones'];
  		$dias_inhabiles      = $this->Formato2($this->data['cnmp16_vacaciones']['dias_inhabiles']);
  		$dias_inhabiles      = $this->Formato1($dias_inhabiles);
  		$numero_lunes        = (int) $this->data['cnmp16_vacaciones']['numero_lunes'];
  		$observaciones       = $this->data['cnmp16_vacaciones']['observaciones'];
		$observaciones       = str_replace("\t"," ",$observaciones);
		$observaciones       = str_replace("\n"," ",$observaciones);
  		$dias                = (int) $this->data['cnmp16_vacaciones']['t_dias'];
  		$meses               = (int) $this->data['cnmp16_vacaciones']['t_meses'];
  		$anos                = (int) $this->data['cnmp16_vacaciones']['t_anios'];
  		$anos_anteriores     = (int) $this->data['cnmp16_vacaciones']['anos_anteriores'];
  		$anos_antiguedad     = (int) $this->data['cnmp16_vacaciones']['anos_antiguedad'];
  		$sueldo_mensual      = $this->Formato1($this->data['cnmp16_vacaciones']['salario_mensual']);
  		$salario             = $this->Formato1($this->data['cnmp16_vacaciones']['salario_diario']);
  		$tipo_operacion      = (int) $this->data['cnmp16_vacaciones']['vacacion'];

	$_SESSION['datos_guardar_vac'] = array();

	$datos_vac['cod_tipo_nomina'] = $cod_tipo_nomina;
	$datos_vac['cod_cargo'] = $cod_cargo;
	$datos_vac['cod_ficha'] = $cod_ficha;
	$datos_vac['cedula_identidad'] = $cedula_identidad;
	$datos_vac['cedula_identidad'] = $cedula_identidad;
	$datos_vac['ano'] = $ano_ejecucion;
	$datos_vac['numero'] = $numero;
	$datos_vac['fecha_inicio'] = $fecha_desde;
	$datos_vac['fecha_termina'] = $fecha_hasta;
	$datos_vac['periodo_desde'] = $periodo_desde;
	$datos_vac['periodo_hasta'] = $periodo_hasta;
	$datos_vac['fecha_calculo'] = $fecha_calculo;
	$datos_vac['cantidad_vacaciones'] = $cantidad_vacaciones;
	$datos_vac['dias_inhabiles'] = $dias_inhabiles;
	$datos_vac['numero_lunes'] = $numero_lunes;
	$datos_vac['observaciones'] = $observaciones;
	$datos_vac['salario_mensual'] = $sueldo_mensual;
	$datos_vac['salario_diario'] = $salario;
	$datos_vac['tipo_operacion'] = $tipo_operacion;


	/** ******* VARIABLES DEL CALCULO ******* */

$monto_extra_diario=0;
$dias_vaca=0;
$salario_diario_vaca=0;
$monto_vaca=0;
$dias_adicio_vacaciones=0;
$salario_diario_adicio_vaca=0;
$monto_adicional_vaca=0;
$dias_bonificacion=0;
$sueldo_diario_bonifica=0;
$monto_bonificacion=0;
$dias_bono_vacacional=0;
$salario_diario_bono_vac=0;
$monto_bono_vaca=0;
$dias_adicio_bono_vaca=0;
$salario_diario_adicio_bono_vaca=0;
$monto_adicional_bono_vaca=0;
$dias_sab_dom_fer=0;
$salario_diario_sab_dom_fer=0;
$monto_sab_dom_fer=0;
$porc_seguro_social=0;
$monto_seguro_social=0;
$porc_paro_forzoso=0;
$monto_paro_forzoso=0;
$porc_fondo_ahorro=0;
$monto_fondo_ahorro=0;
$porc_fondo_jub=0;
$monto_fondo_jub=0;
$porc_cuota_sindical=0;
$cuota_sindical=0;
$cuota_sindical_diario=0;
$monto_cuota_sindical=0;
$porc_caja_ahorro=0;
$monto_caja_ahorro=0;
$cuota_prestamo=0;
$cuota_prestamo_diario=0;
$monto_prestamo=0;
$monto_prestamo_caja=0;
$cuota_vivienda=0;
$cuota_vivienda_diario=0;
$monto_credito_vivienda=0;
$porc_aporte_seguro_social=0;
$monto_aporte_seguro_social=0;
$porc_aporte_paro_forzoso=0;
$monto_aporte_paro_forzoso=0;
$porc_aporte_fondo_ahorro=0;
$monto_aporte_fondo_ahorro=0;
$porc_aporte_fondo_jub=0;
$monto_aporte_fondo_jub=0;
$porc_aporte_ahorro=0;
$monto_aporte_ahorro=0;


				// ******** IDENTIFICACION DE TRANSACCIONES  *********

	$identif_transacciones = $this->cnmd16_identif_transa->findAll($condi_transac);

	if ($identif_transacciones != null){
		$cod_vaca                 = $identif_transacciones[0]['cnmd16_identif_transa']['cod_asig_vaca'];
		$cod_bonificacion         = $identif_transacciones[0]['cnmd16_identif_transa']['cod_asig_bonificacion'];
		$cod_bono_vaca            = $identif_transacciones[0]['cnmd16_identif_transa']['cod_asig_bono_vaca'];
		$cod_seguro_social        = $identif_transacciones[0]['cnmd16_identif_transa']['cod_ded_seguro_social'];
		$cod_paro_forzoso         = $identif_transacciones[0]['cnmd16_identif_transa']['cod_ded_paro_forzoso'];
		$cod_faov                 = $identif_transacciones[0]['cnmd16_identif_transa']['cod_ded_fondo_vivienda'];
		$cod_fondo_jub            = $identif_transacciones[0]['cnmd16_identif_transa']['cod_ded_fondo_jub'];
		$cod_ahorro               = $identif_transacciones[0]['cnmd16_identif_transa']['cod_ded_ahorro'];
		$cod_prestamo             = $identif_transacciones[0]['cnmd16_identif_transa']['cod_ded_prestamo'];
		$cod_sindicato            = $identif_transacciones[0]['cnmd16_identif_transa']['cod_ded_sindicato'];
		$cod_vivienda             = $identif_transacciones[0]['cnmd16_identif_transa']['cod_ded_cred_vivienda'];
		$cod_aporte_seguro_social = $identif_transacciones[0]['cnmd16_identif_transa']['aporte_seguro_social'];
		$cod_aporte_paro_forzoso  = $identif_transacciones[0]['cnmd16_identif_transa']['aporte_paro_forzoso'];
		$cod_aporte_faov          = $identif_transacciones[0]['cnmd16_identif_transa']['aporte_fondo_vivienda'];
		$cod_aporte_fondo_jub     = $identif_transacciones[0]['cnmd16_identif_transa']['aporte_fondo_jub'];
		$cod_aporte_ahorro        = $identif_transacciones[0]['cnmd16_identif_transa']['aporte_ahorro'];
	}


	$sql_frecuencia = $this->Cnmd01->findAll($condicion." and cod_tipo_nomina = $cod_tipo_nomina", 'frecuencia_cobro');
	$frecuencia = (int) $sql_frecuencia[0]['Cnmd01']['frecuencia_cobro'];



	$sql_salario = $this->cnmd06_fichas->execute("SELECT f_cnmp16_devolver_salario_asig_vision(a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_cargo, a.cod_ficha, 1, $cod_vaca) AS salario FROM cnmd06_fichas a WHERE $condicion and cod_tipo_nomina='$cod_tipo_nomina' and cod_cargo='".$cod_cargo."' and cod_ficha='".$cod_ficha."' and cedula_identidad='$cedula_identidad'");
	if(!empty($sql_salario)){
		$salario = $sql_salario[0][0]['salario'];
	}else{
		$salario = 0;
		}

	$sueldo_diario = ($salario/30);



	/** ***** JORNADAS EXTRAORDINARIAS ***** */

	$jornada_extraordinaria = $this->cnmd16_jornada_extra->findAll($condicion." AND cod_tipo_nomina='$cod_tipo_nomina'");
	if ($jornada_extraordinaria != null){

			$d1 = substr($fecha_calculo, 0, 2);
			$m1 = substr($fecha_calculo, 3, 2);
			$a1 = substr($fecha_calculo, 6, 4);

			$fecha_calculo = $a1."-".$m1."-".$d1;

		foreach($jornada_extraordinaria as $jornada_extra){
			$cod_tipo_transa = $jornada_extra['cnmd16_jornada_extra']['cod_tipo_transaccion'];
			$cod_transa      = $jornada_extra['cnmd16_jornada_extra']['cod_transaccion'];
			$dias_nomina     = ceil($jornada_extra['cnmd16_jornada_extra']['dias_mensual_nomina']);
			$dias_historia   = ceil($jornada_extra['cnmd16_jornada_extra']['dias_buscar_historia']);

			$fecha_desde="";
			$a=$a1;
			$m=$m1;
			$d=$d1;

		for ($k=1;$k<=$dias_historia;$k++){
			$d=($d-1);
			if ($d==0){
				$m=($m-1);
				$d=$dias_nomina;
			}
			if ($m==0){
				$m=12;
				$a=($a-1);
			}
		}

	$fecha_desde = $a."-".$m."-".$d;

	$nro_nomina = $this->Cnmd01->execute("SELECT ano, numero_nomina, cantidad_pagos, periodo_desde, periodo_hasta FROM cnmd08_historia_nomina WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND (periodo_desde>='$fecha_desde' AND periodo_hasta<='$fecha_calculo');");

	if(!empty($nro_nomina)){
		foreach($nro_nomina as $n_nomina){
			$ano_nomina     = $n_nomina[0]['ano'];
			$numero_nomina  = $n_nomina[0]['numero_nomina'];
			$cantidad_pagos = $n_nomina[0]['cantidad_pagos'];
			$fecha_d        = $n_nomina[0]['periodo_desde'];
			$fecha_h        = $n_nomina[0]['periodo_hasta'];

			$cuota_monto = $this->Cnmd01->execute ("SELECT ano, numero_nomina, cod_cargo, cod_ficha, cod_transaccion, monto_cuota FROM cnmd08_historia_transacciones WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND ano='$ano_nomina' AND numero_nomina='$numero_nomina' AND cod_cargo='$cod_cargo' AND cod_ficha='$cod_ficha' AND cod_tipo_transaccion='$cod_tipo_transa' AND cod_transaccion='$cod_transa'");

			if ($cuota_monto != null){
				$ano_t             = $cuota_monto[0][0]['ano'];
				$numero_nomina_t   = $cuota_monto[0][0]['numero_nomina'];
				$cod_cargot        = $cuota_monto[0][0]['cod_cargo'];
				$cod_ficha         = $cuota_monto[0][0]['cod_ficha'];
				$cod_transaccion_t = $cuota_monto[0][0]['cod_transaccion'];
				$monto_cuota_t     = $cuota_monto[0][0]['monto_cuota'];

				$monto_cuota = ($monto_cuota_t / $cantidad_pagos);
				$monto_extra_diario += ($monto_cuota/$dias_nomina);

			} // cuota_monto

		} // condicion numero nomina

	} // busqueda numero nomina

  } // condicion jornada extraordinaria

				$monto_extra_diario = $this->redondeo($monto_extra_diario);

} // busqueda jornada extraordinaria


					/** **** OPERACIONES **** */

		// ************* AMBAS - VACACIONES ***********

if ($tipo_operacion == 1 || $tipo_operacion == 2){


	// *** VACACIONES ***


	if ($cod_vaca!=0){
	$sql_salario_mensual_vac = $this->Cnmd01->execute("SELECT f_cnmp16_devolver_salario_asig_vision(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, 1, $cod_vaca) AS salario_vaca FROM cnmd06_fichas WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_cargo='$cod_cargo' AND cod_ficha='$cod_ficha' AND cedula_identidad='$cedula_identidad'");
	$salario_mensual_vacaciones = $sql_salario_mensual_vac[0][0]['salario_vaca'];

		if  ($frecuencia==1) {
			$dias_ano=364;
			$dias_cobro=1;
		} else
		if  ($frecuencia==2) {
			$dias_ano=364;
			$dias_cobro=7;
		} else
		if  ($frecuencia==3) {
			$dias_ano=360;
			$dias_cobro=15;
		} else
		if  ($frecuencia==4) {
			$dias_ano=360;
			$dias_cobro=30;
		} else
		if  ($frecuencia==5) {
			$dias_ano=360;
			$dias_cobro=60;
		} else
		if  ($frecuencia == 6 ){
			$dias_ano=360;
			$dias_cobro=90;
		}

	$salario_diario_vaca = (($salario_mensual_vacaciones/30) + $monto_extra_diario);

	$sql_dias_vaca = $this->Cnmd01->execute("select dias from v_cnmd15_disfrute_vaca_vacaciones where ".$condicion." and cod_tipo_nomina=".$cod_tipo_nomina." and '$fecha_calculo' >= fecha_desde_vaca::date and '$fecha_calculo' <= fecha_hasta_vaca::date and '$anos_antiguedad' >= desde_antiguedad and '$anos_antiguedad' <= hasta_antiguedad limit 1;");
	$dias_vaca = $sql_dias_vaca!=null ? $sql_dias_vaca[0][0]['dias'] : 0;

		if ($dias_vaca!=0){
			$dias_vaca = ($dias_vaca * $cantidad_vacaciones);
			$monto_vaca = $this->redondeo($dias_vaca * $salario_diario_vaca);



	// *** DIAS ADICIONALES DE VACACIONES ***

	$sql_dias_adic_vac = $this->Cnmd01->execute("select dias from v_cnmd15_disfrute_vaca_adicional where ".$condicion." and cod_tipo_nomina=".$cod_tipo_nomina." and '$fecha_calculo' >= fecha_desde_vaca::date and '$fecha_calculo' <= fecha_hasta_vaca::date and '$anos_antiguedad' >= desde_antiguedad and '$anos_antiguedad' <= hasta_antiguedad limit 1;");
	$dias_adicio_vacaciones = $sql_dias_adic_vac!=null ? $sql_dias_adic_vac[0][0]['dias'] : 0;

			if ($dias_adicio_vacaciones!=0){
				$dias_adicio_vacaciones = ($dias_adicio_vacaciones * $cantidad_vacaciones);
				$salario_diario_adicio_vaca = $salario_diario_vaca;
				$monto_adicional_vaca = $this->redondeo($dias_adicio_vacaciones * $salario_diario_adicio_vaca);
			}

	// *** SABADOS, DOMINGOS Y DIAS FERIADOS (DIAS INHABILES) ***

				$dias_sab_dom_fer = $dias_inhabiles;
				$salario_diario_sab_dom_fer = $salario_diario_vaca;
				$monto_sab_dom_fer = $this->redondeo($dias_sab_dom_fer * $salario_diario_sab_dom_fer);

		} // fin if dias de vaca

	} // fin if cod_vaca



	// *** SEGURO SOCIAL OBLIGATORIO ***

	if ($cod_seguro_social){

		$busca_paga_seguro = $this->Cnmd01->execute("SELECT monto_cuota FROM cnmd07_transacciones_actuales WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_cargo='$cod_cargo' AND cod_ficha='$cod_ficha' AND cod_tipo_transaccion='2' AND cod_transaccion='$cod_seguro_social'");
			if ($busca_paga_seguro == null){
				$busca_paga_seguro = $this->Cnmd01->execute("SELECT monto_cuota FROM cnmd07_transacciones_prenomina WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_cargo='$cod_cargo' AND cod_ficha='$cod_ficha' AND cod_tipo_transaccion='2' AND cod_transaccion='$cod_seguro_social'");
				}
		if ($busca_paga_seguro != null){

			$escenario_seguro = $this->Cnmd01->execute("SELECT porcentaje FROM cnmd10_comunes_52semanas_porcentaje_ded WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_tipo_transaccion='2' AND cod_transaccion='$cod_seguro_social'");
			if ($escenario_seguro != null){
				$porc_seguro_social = $escenario_seguro[0][0]['porcentaje'];

				$sql_salario_diario_seguro = $this->Cnmd01->execute("SELECT f_cnmp16_devolver_salario_asig_ded_vision(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, 2, $cod_seguro_social) AS salario_seguro FROM cnmd06_fichas WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_cargo='$cod_cargo' AND cod_ficha='$cod_ficha' AND cedula_identidad='$cedula_identidad'");
			    $salario_diario_seguro = $sql_salario_diario_seguro[0][0]['salario_seguro'];

				$monto_seguro_social = $this->redondeo(((($dias_ano * $salario_diario_seguro)/52) * ($porc_seguro_social/100)) * $numero_lunes);

				$escenario_aporte_seguro = $this->Cnmd01->execute("SELECT porcentaje_patrono FROM cnmd10_aportes_patronales WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_tipo_transaccion='2' AND cod_transaccion='$cod_seguro_social' AND cod_tipo_transa_patrono='2' AND cod_transa_patrono='$cod_aporte_seguro_social'");
				if ($escenario_aporte_seguro != null){
					$porc_aporte_seguro_social = $escenario_aporte_seguro[0][0]['porcentaje_patrono'];
					$monto_aporte_seguro_social = $this->redondeo(((($dias_ano * $salario_diario_seguro)/52) * ($porc_aporte_seguro_social/100)) * $numero_lunes);

				} // escenario aporte seguro social
			} // escenario de seguro social
		} // Paga seguro social
	} // Codigo seguro social




	// *** PARO FORZOSO ***

	if ($cod_paro_forzoso){

		$busca_paga_paro_forzoso = $this->Cnmd01->execute("SELECT monto_cuota FROM cnmd07_transacciones_actuales WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_cargo='$cod_cargo' AND cod_ficha='$cod_ficha' AND cod_tipo_transaccion='2' AND cod_transaccion='$cod_paro_forzoso'");
			if ($busca_paga_paro_forzoso == null){
			$busca_paga_paro_forzoso = $this->Cnmd01->execute("SELECT monto_cuota FROM cnmd07_transacciones_prenomina WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_cargo='$cod_cargo' AND cod_ficha='$cod_ficha' AND cod_tipo_transaccion='2' AND cod_transaccion='$cod_paro_forzoso'");
				}
		if ($busca_paga_paro_forzoso != null){

			$escenario_paro_forzoso = $this->Cnmd01->execute("SELECT porcentaje FROM cnmd10_comunes_52semanas_porcentaje_ded WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_tipo_transaccion='2' AND cod_transaccion='$cod_paro_forzoso'");
				if ($escenario_paro_forzoso != null){
					$porc_paro_forzoso = $escenario_paro_forzoso[0][0]['porcentaje'];

					$sql_salario_diario_paro = $this->Cnmd01->execute("SELECT f_cnmp16_devolver_salario_asig_ded_vision(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, 2, $cod_paro_forzoso) AS salario_paro FROM cnmd06_fichas WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_cargo='$cod_cargo' AND cod_ficha='$cod_ficha' AND cedula_identidad='$cedula_identidad'");
			    	$salario_dirio_paro = $sql_salario_diario_paro[0][0]['salario_paro'];

					$monto_paro_forzoso = $this->redondeo(((($dias_ano * $salario_dirio_paro)/52) * ($porc_paro_forzoso/100)) * $numero_lunes);

					$escenario_aporte_paro_forzoso = $this->Cnmd01->execute("SELECT porcentaje_patrono FROM cnmd10_aportes_patronales WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_tipo_transaccion='2' AND cod_transaccion='$cod_paro_forzoso' AND cod_tipo_transa_patrono='2' AND cod_transa_patrono='$cod_aporte_paro_forzoso'");
	     			if ($escenario_aporte_paro_forzoso != null){
						$porc_aporte_paro_forzoso = $escenario_aporte_paro_forzoso[0][0]['porcentaje_patrono'];
						$monto_aporte_paro_forzoso = $this->redondeo(((($dias_ano * $salario_dirio_paro)/52) * ($porc_aporte_paro_forzoso/100)) * $numero_lunes);

	     			} // escenario aporte paro forzoso
				} // escenario paro forzoso
		} // Paga paro forzoso
	} // Codigo paro forzoso



	// *** POLITICA HABITACIONAL ***

	if ($cod_faov){

		$busca_paga_faov = $this->Cnmd01->execute("SELECT monto_cuota FROM cnmd07_transacciones_actuales WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_cargo='$cod_cargo' AND cod_ficha='$cod_ficha' AND cod_tipo_transaccion='2' AND cod_transaccion='$cod_faov'");
			if ($busca_paga_faov == null){
				$busca_paga_faov = $this->Cnmd01->execute("SELECT monto_cuota FROM cnmd07_transacciones_prenomina WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_cargo='$cod_cargo' AND cod_ficha='$cod_ficha' AND cod_tipo_transaccion='2' AND cod_transaccion='$cod_faov'");
				}
		if ($busca_paga_faov != null){

			$escenario_faov = $this->Cnmd01->execute("SELECT porcentaje FROM cnmd10_comunes_porcentaje_deduccion WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_tipo_transaccion='2' AND cod_transaccion='$cod_faov'");
			if ($escenario_faov != null){
				$porc_fondo_ahorro = $escenario_faov[0][0]['porcentaje'];

				$sql_salario_diario_faov = $this->Cnmd01->execute("SELECT f_cnmp16_devolver_salario_asig_ded_vision(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, 2, $cod_faov) AS salario_faov FROM cnmd06_fichas WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_cargo='$cod_cargo' AND cod_ficha='$cod_ficha' AND cedula_identidad='$cedula_identidad'");
			    $salario_diario_faov = $sql_salario_diario_faov[0][0]['salario_faov'];

			    $monto_fondo_ahorro = $this->redondeo((($dias_vaca + $dias_sab_dom_fer) * $salario_diario_faov) * ($porc_fondo_ahorro/100));

				$escenario_aporte_faov = $this->Cnmd01->execute("SELECT porcentaje_patrono FROM cnmd10_aportes_patronales WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_tipo_transaccion='2' AND cod_transaccion='$cod_faov' AND cod_tipo_transa_patrono='2' AND cod_transa_patrono='$cod_aporte_faov'");
	     		if ($escenario_aporte_faov!=null){

					$porc_aporte_fondo_ahorro = $escenario_aporte_faov[0][0]['porcentaje_patrono'];

					$monto_aporte_fondo_ahorro = $this->redondeo((($dias_vaca + $dias_sab_dom_fer) * $salario_diario_faov) * ($porc_aporte_fondo_ahorro/100));

	     		} // escenario aporte faov
			} // escenario faov
		} // Paga faov
	} // Código faov



	// *** FONDO DE JUBILACION ***


	if ($cod_fondo_jub){

		$busca_paga_fondo_jub = $this->Cnmd01->execute("SELECT monto_cuota FROM cnmd07_transacciones_actuales WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_cargo='$cod_cargo' AND cod_ficha='$cod_ficha' AND cod_tipo_transaccion='2' AND cod_transaccion='$cod_fondo_jub'");
			if ($busca_paga_fondo_jub == null){
				$busca_paga_fondo_jub = $this->Cnmd01->execute("SELECT monto_cuota FROM cnmd07_transacciones_prenomina WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_cargo='$cod_cargo' AND cod_ficha='$cod_ficha' AND cod_tipo_transaccion='2' AND cod_transaccion='$cod_fondo_jub'");
				}
		if ($busca_paga_fondo_jub != null){

			$escenario_fondo_jub = $this->Cnmd01->execute("SELECT porcentaje FROM cnmd10_comunes_porcentaje_deduccion WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_tipo_transaccion='2' AND cod_transaccion='$cod_fondo_jub'");
			if ($escenario_fondo_jub != null){
				$porc_fondo_jub = $escenario_fondo_jub[0][0]['porcentaje'];

				$sql_salario_diario_fondo = $this->Cnmd01->execute("SELECT f_cnmp16_devolver_salario_asig_ded_vision(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, 2, $cod_fondo_jub) AS salario_fondo FROM cnmd06_fichas WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_cargo='$cod_cargo' AND cod_ficha='$cod_ficha' AND cedula_identidad='$cedula_identidad'");
			    $salario_diario_fondo = $sql_salario_diario_fondo[0][0]['salario_fondo'];

				$monto_fondo_jub = $this->redondeo((($dias_vaca + $dias_sab_dom_fer) * $salario_diario_fondo) * ($porc_fondo_jub/100));

				$escenario_aporte_fondo_jub = $this->Cnmd01->execute("SELECT porcentaje_patrono FROM cnmd10_aportes_patronales WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_tipo_transaccion='2' AND cod_transaccion='$cod_fondo_jub' AND cod_tipo_transa_patrono='2' AND cod_transa_patrono='$cod_aporte_fondo_jub'");
				if ($escenario_aporte_fondo_jub != null){
					$porc_aporte_fondo_jub = $escenario_aporte_fondo_jub[0][0]['porcentaje_patrono'];

					$monto_aporte_fondo_jub = $this->redondeo((($dias_vaca + $dias_sab_dom_fer) * $salario_diario_fondo) * ($porc_aporte_fondo_jub/100));

	     		} // escenario aporte fondo de jubilacion
			} // escenario fondo de jubilacion
		} // Paga fondo de jubilacion
	} // Codigo fondo jubilacion



	// *** CAJA DE AHORROS ***


	if ($cod_ahorro){

		$busca_paga_ahorro = $this->Cnmd01->execute("SELECT monto_cuota FROM cnmd07_transacciones_actuales WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_cargo='$cod_cargo' AND cod_ficha='$cod_ficha' AND cod_tipo_transaccion='2' AND cod_transaccion='$cod_ahorro'");
			if ($busca_paga_ahorro == null){
				$busca_paga_ahorro = $this->Cnmd01->execute("SELECT monto_cuota FROM cnmd07_transacciones_prenomina WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_cargo='$cod_cargo' AND cod_ficha='$cod_ficha' AND cod_tipo_transaccion='2' AND cod_transaccion='$cod_ahorro'");
				}
		if ($busca_paga_ahorro != null){

			$escenario_ahorro = $this->Cnmd01->execute("SELECT porcentaje FROM cnmd10_comunes_porcentaje_deduccion WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_tipo_transaccion='2' AND cod_transaccion='$cod_ahorro'");
			if ($escenario_ahorro != null){
				$porc_caja_ahorro = $escenario_ahorro[0][0]['porcentaje'];

				$sql_salario_diario_caja = $this->Cnmd01->execute("SELECT f_cnmp16_devolver_salario_asig_ded_vision(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, 2, $cod_ahorro) AS salario_caja FROM cnmd06_fichas WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_cargo='$cod_cargo' AND cod_ficha='$cod_ficha' AND cedula_identidad='$cedula_identidad'");
			    $salario_diario_caja = $sql_salario_diario_caja[0][0]['salario_caja'];

			    $monto_caja_ahorro = $this->redondeo((($dias_vaca + $dias_sab_dom_fer) * $salario_diario_caja) * ($porc_caja_ahorro/100));

				$escenario_aporte_ahorro = $this->Cnmd01->execute("SELECT porcentaje_patrono FROM cnmd10_aportes_patronales WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_tipo_transaccion='2' AND cod_transaccion='$cod_ahorro' AND cod_tipo_transa_patrono='2' AND cod_transa_patrono='$cod_aporte_ahorro'");
	     		if ($escenario_aporte_ahorro!=null){
					$porc_aporte_ahorro = $escenario_aporte_ahorro[0][0]['porcentaje_patrono'];

					$monto_aporte_ahorro = $this->redondeo((($dias_vaca + $dias_sab_dom_fer) * $salario_diario_caja) * ($porc_aporte_ahorro/100));

	     		} // escenario aporte caja de ahorro
			} // escenario caja de ahorro
		} // Paga caja de ahorro
	} // Codigo caja de ahorro



	// *** SINDICATO ***


	if ($cod_sindicato){

		$busca_paga_sindicato = $this->Cnmd01->execute("SELECT monto_cuota FROM cnmd07_transacciones_actuales WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_cargo='$cod_cargo' AND cod_ficha='$cod_ficha' AND cod_tipo_transaccion='2' AND cod_transaccion='$cod_sindicato'");
			if ($busca_paga_sindicato == null){
				$busca_paga_sindicato = $this->Cnmd01->execute("SELECT monto_cuota FROM cnmd07_transacciones_prenomina WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_cargo='$cod_cargo' AND cod_ficha='$cod_ficha' AND cod_tipo_transaccion='2' AND cod_transaccion='$cod_sindicato'");
				}
		if ($busca_paga_sindicato != null){
			$cuota_sindical = $busca_paga_sindicato[0][0]['monto_cuota'];

			$escenario_sindicato = $this->Cnmd01->execute("SELECT porcentaje FROM cnmd10_comunes_porcentaje_deduccion WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_tipo_transaccion='2' AND cod_transaccion='$cod_sindicato'");
			if ($escenario_sindicato != null){
				$porc_cuota_sindical = $escenario_sindicato[0][0]['porcentaje'];

				$sql_salario_diario_sindicato = $this->Cnmd01->execute("SELECT f_cnmp16_devolver_salario_asig_ded_vision(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, 2, $cod_sindicato) AS salario_sindicato FROM cnmd06_fichas WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_cargo='$cod_cargo' AND cod_ficha='$cod_ficha' AND cedula_identidad='$cedula_identidad'");
			    $salario_diario_sindicato = $sql_salario_diario_sindicato[0][0]['salario_sindicato'];

				$monto_cuota_sindical = $this->redondeo((($dias_vaca + $dias_sab_dom_fer) * $salario_diario_sindicato) * ($porc_cuota_sindical/100));

			} else {
			$sql_cuota_sindicato_diario = $this->cnmd06_fichas->execute("SELECT f_cnmp16_devolver_salario_ded_vision(a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_cargo, a.cod_ficha, 2, $cod_sindicato) AS salario FROM cnmd06_fichas a WHERE $condicion and cod_tipo_nomina='$cod_tipo_nomina' and cod_cargo='".$cod_cargo."' and cod_ficha='".$cod_ficha."' and cedula_identidad='$cedula_identidad'");
			$cuota_sindical_diario = $sql_cuota_sindicato_diario[0][0]['salario'];
			$monto_cuota_sindical = $this->redondeo($cuota_sindical_diario * ($dias_vaca+$dias_sab_dom_fer));
				}

			// escenario sindicato
       } // Paga sindicato
	} // Código sindicato



	// *** PRESTAMO CAJA DE AHORROS ***


	if ($cod_prestamo){

		$busca_paga_prestamo = $this->Cnmd01->execute("SELECT monto_cuota, saldo FROM cnmd07_transacciones_actuales WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_cargo='$cod_cargo' AND cod_ficha='$cod_ficha' AND cod_tipo_transaccion='2' AND cod_transaccion='$cod_prestamo'");
			if ($busca_paga_prestamo == null){
				$busca_paga_prestamo = $this->Cnmd01->execute("SELECT monto_cuota, saldo FROM cnmd07_transacciones_prenomina WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_cargo='$cod_cargo' AND cod_ficha='$cod_ficha' AND cod_tipo_transaccion='2' AND cod_transaccion='$cod_prestamo'");
				}
		if ($busca_paga_prestamo != null){

			$cuota_prestamo = $busca_paga_prestamo[0][0]['monto_cuota'];
			$saldo = $busca_paga_prestamo[0][0]['saldo'];
			$sql_cuota_prestamo_diario = $this->cnmd06_fichas->execute("SELECT f_cnmp16_devolver_salario_ded_vision(a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_cargo, a.cod_ficha,2, $cod_prestamo) AS salario FROM cnmd06_fichas a WHERE $condicion and cod_tipo_nomina='$cod_tipo_nomina' and cod_cargo='".$cod_cargo."' and cod_ficha='".$cod_ficha."' and cedula_identidad='$cedula_identidad'");
			$cuota_prestamo_diario = $sql_cuota_prestamo_diario[0][0]['salario'];
			$monto_prestamo = $this->redondeo($cuota_prestamo_diario * ($dias_vaca+$dias_sab_dom_fer));

			if ($saldo<$monto_prestamo){
				$monto_prestamo=$saldo;
			}

       } // Paga prestamo
} // Codigo prestamo caja de ahorros


	// *** VIVIENDAS ***


	if ($cod_vivienda){

		$busca_paga_vivienda = $this->Cnmd01->execute("SELECT monto_cuota FROM cnmd07_transacciones_actuales WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_cargo='$cod_cargo' AND cod_ficha='$cod_ficha' AND cod_tipo_transaccion='2' AND cod_transaccion='$cod_vivienda'");
			if ($busca_paga_vivienda == null){
				$busca_paga_vivienda = $this->Cnmd01->execute("SELECT monto_cuota FROM cnmd07_transacciones_prenomina WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_cargo='$cod_cargo' AND cod_ficha='$cod_ficha' AND cod_tipo_transaccion='2' AND cod_transaccion='$cod_vivienda'");
				}
		if ($busca_paga_vivienda != null){
			$cuota_vivienda = $busca_paga_vivienda[0][0]['monto_cuota'];
			$sql_cuota_vivienda_diario = $this->cnmd06_fichas->execute("SELECT f_cnmp16_devolver_salario_ded_vision(a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.cod_tipo_nomina, a.cod_cargo, a.cod_ficha,2, $cod_vivienda) AS salario FROM cnmd06_fichas a WHERE $condicion and cod_tipo_nomina='$cod_tipo_nomina' and cod_cargo='".$cod_cargo."' and cod_ficha='".$cod_ficha."' and cedula_identidad='$cedula_identidad'");
			$cuota_vivienda_diario = $sql_cuota_vivienda_diario[0][0]['salario'];
			$monto_credito_vivienda = $this->redondeo($cuota_vivienda_diario * ($dias_vaca+$dias_sab_dom_fer));

		} // Paga vivienda
	} // Codigo vivienda


}  // fin tipo_operacion=1 o tipo_operacion=2



					/** **** OPERACIONES **** */

		// ************* AMBAS - BONO VACACIONAL ***********


if ($tipo_operacion == 1 || $tipo_operacion == 3){



	// *** BONO VACACIONAL ***


	if ($cod_bono_vaca!=0){
		$sql_salario_mensual_bono_vac = $this->Cnmd01->execute("SELECT f_cnmp16_devolver_salario_asig_vision(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, 1, $cod_bono_vaca) AS salario_bono_vaca FROM cnmd06_fichas WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_cargo='$cod_cargo' AND cod_ficha='$cod_ficha' AND cedula_identidad='$cedula_identidad'");
		$salario_mensual_bono_vac = $sql_salario_mensual_bono_vac[0][0]['salario_bono_vaca'];


		$salario_diario_bono_vac = (($salario_mensual_bono_vac/30) + $monto_extra_diario);


		$sql_dias_bono_vacaci = $this->Cnmd01->execute("select dias from v_cnmd15_bono_vaca_vacaciones where ".$condicion." and cod_tipo_nomina=".$cod_tipo_nomina." and '$fecha_calculo' >= fecha_desde_bono_vaca::date and '$fecha_calculo' <= fecha_hasta_bono_vaca::date and '$anos_antiguedad' >= desde_antiguedad and '$anos_antiguedad' <= hasta_antiguedad limit 1;");
		$dias_bono_vacacional = $sql_dias_bono_vacaci!=null ? $sql_dias_bono_vacaci[0][0]['dias'] : 0;

		if ($dias_bono_vacacional!=0){
			$monto_bono_vaca = $this->redondeo($dias_bono_vacacional * $salario_diario_bono_vac);



	// *** DIAS ADICIONALES BONO VACACIONAL ***

	$sql_dias_adicio_bono_vacaci = $this->Cnmd01->execute("select dias from v_cnmd15_bono_vaca_adicional where ".$condicion." and cod_tipo_nomina=".$cod_tipo_nomina." and '$fecha_calculo' >= fecha_desde_vaca::date and '$fecha_calculo' <= fecha_hasta_vaca::date and '$anos_antiguedad' >= desde_antiguedad and '$anos_antiguedad' <= hasta_antiguedad limit 1;");
	$dias_adicio_bono_vaca = $sql_dias_adicio_bono_vacaci!=null ? $sql_dias_adicio_bono_vacaci[0][0]['dias'] : 0;

			if ($dias_adicio_bono_vaca!=0){
				$salario_diario_adicio_bono_vaca = $salario_diario_bono_vac;
				$monto_adicional_bono_vaca = $this->redondeo($dias_adicio_bono_vaca * $salario_diario_adicio_bono_vaca);
			}
		} // fin dias bono vaca

	} // fin cod_bono_vaca



	// *** BONIFICACIÓN POR VACACIONES ***


	if ($cod_bonificacion!=0){
		$sql_salario_mensual_boni = $this->Cnmd01->execute("SELECT f_cnmp16_devolver_salario_asig_vision(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, 1, $cod_bonificacion) AS salario_bonificacion FROM cnmd06_fichas WHERE ".$condicion." AND cod_tipo_nomina='$cod_tipo_nomina' AND cod_cargo='$cod_cargo' AND cod_ficha='$cod_ficha' AND cedula_identidad='$cedula_identidad'");
		$salario_mensual_bonificacion = $sql_salario_mensual_boni[0][0]['salario_bonificacion'];

		$sueldo_diario_bonifica = (($salario_mensual_bonificacion/30) + $monto_extra_diario);

		$sql_dias_bonificacion = $this->Cnmd01->execute("select dias from v_cnmd15_bonificacion where ".$condicion." and cod_tipo_nomina=".$cod_tipo_nomina." and '$fecha_calculo' >= fecha_desde_vaca::date and '$fecha_calculo' <= fecha_hasta_vaca::date and '$anos_antiguedad' >= desde_antiguedad and '$anos_antiguedad' <= hasta_antiguedad limit 1;");
		$dias_bonificacion = $sql_dias_bonificacion!=null ? $sql_dias_bonificacion[0][0]['dias'] : 0;

		if ($dias_bonificacion!=0){
			$monto_bonificacion = $this->redondeo($dias_bonificacion * $sueldo_diario_bonifica);
		}
	} // fin bonificacion

} // fin tipo_operacion=1 o tipo_operacion=3


	$total_vacaciones_dias  = ($dias_vaca + $dias_adicio_vacaciones + $dias_sab_dom_fer);
	$total_vacaciones_monto = ($monto_vaca + $monto_adicional_vaca + $monto_sab_dom_fer);
	$total_bono_vaca_dias   = ($dias_bono_vacacional + $dias_adicio_bono_vaca + $dias_bonificacion);
	$total_bono_vaca_monto  = ($monto_bono_vaca + $monto_adicional_bono_vaca + $monto_bonificacion);

	$total_recibir      = ($total_vacaciones_monto + $total_bono_vaca_monto);

	$total_deducciones  = ($monto_seguro_social + $monto_paro_forzoso + $monto_fondo_ahorro + $monto_fondo_jub + $monto_cuota_sindical + $monto_caja_ahorro + $monto_prestamo + $monto_credito_vivienda);

	$total_recibir_neto = ($total_recibir - $total_deducciones);

	// *************** RESULTSET DATA

	$datos_vac['dias_vacaciones'] = $dias_vaca;
	$datos_vac['salario_diario_vaca'] = $salario_diario_vaca;
	$datos_vac['dias_adicio_vacaciones'] = $dias_adicio_vacaciones;
	$datos_vac['dias_bonificacion'] = $dias_bonificacion;
	$datos_vac['sueldo_diario_bonifica'] = $sueldo_diario_bonifica;
	$datos_vac['dias_bono_vacacional'] = $dias_bono_vacacional;
	$datos_vac['salario_diario_bono_vac'] = $salario_diario_bono_vac;
	$datos_vac['dias_adicio_bono_vaca'] = $dias_adicio_bono_vaca;
	$datos_vac['dias_sab_dom_fer'] = $dias_sab_dom_fer;
	$datos_vac['porc_seguro_social'] = $porc_seguro_social;
	$datos_vac['monto_seguro_social'] = $monto_seguro_social;
	$datos_vac['porc_paro_forzoso'] = $porc_paro_forzoso;
	$datos_vac['monto_paro_forzoso'] = $monto_paro_forzoso;
	$datos_vac['porc_fondo_ahorro'] = $porc_fondo_ahorro;
	$datos_vac['monto_fondo_ahorro'] = $monto_fondo_ahorro;
	$datos_vac['porc_fondo_jub'] = $porc_fondo_jub;
	$datos_vac['monto_fondo_jub'] = $monto_fondo_jub;
	$datos_vac['porc_cuota_sindical'] = $porc_cuota_sindical;
	$datos_vac['cuota_sindical'] = $cuota_sindical;
	$datos_vac['monto_cuota_sindical'] = $monto_cuota_sindical;
	$datos_vac['porc_caja_ahorro'] = $porc_caja_ahorro;
	$datos_vac['monto_caja_ahorro'] = $monto_caja_ahorro;
	$datos_vac['cuota_prestamo'] = $cuota_prestamo;
	$datos_vac['monto_prestamo_caja'] = $monto_prestamo;
	$datos_vac['cuota_vivienda'] = $cuota_vivienda;
	$datos_vac['monto_credito_vivienda'] = $monto_credito_vivienda;
	$datos_vac['porc_aporte_seguro_social'] = $porc_aporte_seguro_social;
	$datos_vac['monto_aporte_seguro_social'] = $monto_aporte_seguro_social;
	$datos_vac['porc_aporte_paro_forzoso'] = $porc_aporte_paro_forzoso;
	$datos_vac['monto_aporte_paro_forzoso'] = $monto_aporte_paro_forzoso;
	$datos_vac['porc_aporte_fondo_ahorro'] = $porc_aporte_fondo_ahorro;
	$datos_vac['monto_aporte_fondo_ahorro'] = $monto_aporte_fondo_ahorro;
	$datos_vac['porc_aporte_fondo_jub'] = $porc_aporte_fondo_jub;
	$datos_vac['monto_aporte_fondo_jub'] = $monto_aporte_fondo_jub;
	$datos_vac['porc_aporte_ahorro'] = $porc_aporte_ahorro;
	$datos_vac['monto_aporte_ahorro'] = $monto_aporte_ahorro;

	$_SESSION['datos_guardar_vac'] = $datos_vac;


		/** ******* CARGANDO ****** */
				// RESULTADOS

	$dias_vaca = $dias_vaca!=0?$this->Formato2($dias_vaca):'';
	$salario_diario_vaca = $salario_diario_vaca!=0?$salario_diario_vaca:'';
	$monto_vaca = $monto_vaca!=0?$this->Formato2($monto_vaca):'';

	$dias_adicio_vacaciones = $dias_adicio_vacaciones!=0?$this->Formato2($dias_adicio_vacaciones):'';
	$salario_diario_adicio_vaca = $salario_diario_adicio_vaca!=0?$salario_diario_adicio_vaca:'';
	$monto_adicional_vaca = $monto_adicional_vaca!=0?$this->Formato2($monto_adicional_vaca):'';

	$dias_sab_dom_fer = $dias_sab_dom_fer!=0?$this->Formato2($dias_sab_dom_fer):'';
	$salario_diario_sab_dom_fer = $salario_diario_sab_dom_fer!=0?$salario_diario_sab_dom_fer:'';
	$monto_sab_dom_fer = $monto_sab_dom_fer!=0?$this->Formato2($monto_sab_dom_fer):'';

	$dias_bonificacion = $dias_bonificacion!=0?$this->Formato2($dias_bonificacion):'';
	$sueldo_diario_bonifica = $sueldo_diario_bonifica!=0?$sueldo_diario_bonifica:'';
	$monto_bonificacion = $monto_bonificacion!=0?$this->Formato2($monto_bonificacion):'';

	$dias_bono_vacacional = $dias_bono_vacacional!=0?$this->Formato2($dias_bono_vacacional):'';
	$salario_diario_bono_vac = $salario_diario_bono_vac!=0?$salario_diario_bono_vac:'';
	$monto_bono_vaca = $monto_bono_vaca!=0?$this->Formato2($monto_bono_vaca):'';

	$dias_adicio_bono_vaca = $dias_adicio_bono_vaca!=0?$this->Formato2($dias_adicio_bono_vaca):'';
	$salario_diario_adicio_bono_vaca = $salario_diario_adicio_bono_vaca!=0?$salario_diario_adicio_bono_vaca:'';
	$monto_adicional_bono_vaca = $monto_adicional_bono_vaca!=0?$this->Formato2($monto_adicional_bono_vaca):'';

	$total_vacaciones_dias = $total_vacaciones_dias!=0?$this->Formato2($total_vacaciones_dias):'';
	$total_vacaciones_monto = $total_vacaciones_monto!=0?$this->Formato2($total_vacaciones_monto):'';
	$total_bono_vaca_dias = $total_bono_vaca_dias!=0?$this->Formato2($total_bono_vaca_dias):'';

	$total_bono_vaca_monto = $total_bono_vaca_monto!=0?$this->Formato2($total_bono_vaca_monto):'';
	$total_bono_vaca_dias = $total_bono_vaca_dias!=0?$this->Formato2($total_bono_vaca_dias):'';
	$total_recibir = $total_recibir!=0?$this->Formato2($total_recibir):'';

	$porc_seguro_social = $porc_seguro_social!=0?$this->Formato2($porc_seguro_social).' %':'';
	$monto_seguro_social = $monto_seguro_social!=0?$this->Formato2($monto_seguro_social):'';
	$porc_paro_forzoso = $porc_paro_forzoso!=0?$this->Formato2($porc_paro_forzoso).' %':'';

	$monto_paro_forzoso = $monto_paro_forzoso!=0?$this->Formato2($monto_paro_forzoso):'';
	$porc_fondo_ahorro = $porc_fondo_ahorro!=0?$this->Formato2($porc_fondo_ahorro).' %':'';
	$monto_fondo_ahorro = $monto_fondo_ahorro!=0?$this->Formato2($monto_fondo_ahorro):'';

	$porc_fondo_jub = $porc_fondo_jub!=0?$this->Formato2($porc_fondo_jub).' %':'';
	$monto_fondo_jub = $monto_fondo_jub!=0?$this->Formato2($monto_fondo_jub):'';
	$porc_caja_ahorro = $porc_caja_ahorro!=0?$this->Formato2($porc_caja_ahorro).' %':'';

	$monto_caja_ahorro = $monto_caja_ahorro!=0?$this->Formato2($monto_caja_ahorro):'';
	$cuota_prestamo = $cuota_prestamo!=0?$this->Formato2($cuota_prestamo):'';
	$monto_prestamo = $monto_prestamo!=0?$this->Formato2($monto_prestamo):'';

	$porc_cuota_sindical = $porc_cuota_sindical!=0?$this->Formato2($porc_cuota_sindical).' %':'';
	$monto_cuota_sindical = $monto_cuota_sindical!=0?$this->Formato2($monto_cuota_sindical):'';

	$cuota_vivienda = $cuota_vivienda!=0?$this->Formato2($cuota_vivienda):'';
	$monto_credito_vivienda = $monto_credito_vivienda!=0?$this->Formato2($monto_credito_vivienda):'';
	$total_deducciones = $total_deducciones!=0?$this->Formato2($total_deducciones):'';
	$total_recibir_neto = $total_recibir_neto!=0?$this->Formato2($total_recibir_neto):'';

        echo "<script>
        		document.getElementById('td_dato_1').innerHTML = '<b>".$dias_vaca."</b>';
        		document.getElementById('td_dato_2').innerHTML = '<b>".$salario_diario_vaca."</b>';
        		document.getElementById('td_dato_3').innerHTML = '<b>".$monto_vaca."</b>';

        		document.getElementById('td_dato_4').innerHTML = '<b>".$dias_adicio_vacaciones."</b>';
        		document.getElementById('td_dato_5').innerHTML = '<b>".$salario_diario_adicio_vaca."</b>';
        		document.getElementById('td_dato_6').innerHTML = '<b>".$monto_adicional_vaca."</b>';

        		document.getElementById('td_dato_7').innerHTML = '<b>".$dias_sab_dom_fer."</b>';
        		document.getElementById('td_dato_8').innerHTML = '<b>".$salario_diario_sab_dom_fer."</b>';
        		document.getElementById('td_dato_9').innerHTML = '<b>".$monto_sab_dom_fer."</b>';

        		document.getElementById('td_dato_10').innerHTML = '<b>".$dias_bonificacion."</b>';
        		document.getElementById('td_dato_11').innerHTML = '<b>".$sueldo_diario_bonifica."</b>';
        		document.getElementById('td_dato_12').innerHTML = '<b>".$monto_bonificacion."</b>';

        		document.getElementById('td_dato_13').innerHTML = '<b>".$dias_bono_vacacional."</b>';
        		document.getElementById('td_dato_14').innerHTML = '<b>".$salario_diario_bono_vac."</b>';
        		document.getElementById('td_dato_15').innerHTML = '<b>".$monto_bono_vaca."</b>';

        		document.getElementById('td_dato_16').innerHTML = '<b>".$dias_adicio_bono_vaca."</b>';
        		document.getElementById('td_dato_17').innerHTML = '<b>".$salario_diario_adicio_bono_vaca."</b>';
        		document.getElementById('td_dato_18').innerHTML = '<b>".$monto_adicional_bono_vaca."</b>';

				document.getElementById('td_dato_19').style.color='#940000';
        		document.getElementById('td_dato_19').innerHTML = '<b>".$total_vacaciones_dias."</b>';
        		document.getElementById('td_dato_20').innerHTML = '';
				document.getElementById('td_dato_21').style.color='#940000';
        		document.getElementById('td_dato_21').innerHTML = '<b>".$total_vacaciones_monto."</b>';

				document.getElementById('td_dato_22').style.color='#940000';
        		document.getElementById('td_dato_22').innerHTML = '<b>".$total_bono_vaca_dias."</b>';
        		document.getElementById('td_dato_23').innerHTML = '';
				document.getElementById('td_dato_24').style.color='#940000';
        		document.getElementById('td_dato_24').innerHTML = '<b>".$total_bono_vaca_monto."</b>';

        		document.getElementById('td_dato_25').innerHTML = '';
        		document.getElementById('td_dato_26').innerHTML = '';
				document.getElementById('td_dato_27').style.color='#940000';
        		document.getElementById('td_dato_27').innerHTML = '<b>".$total_recibir."</b>';

        		document.getElementById('td_dato_28').innerHTML = '<b>".$porc_seguro_social."</b>';
        		document.getElementById('td_dato_29').innerHTML = '';
        		document.getElementById('td_dato_30').innerHTML = '<b>".$monto_seguro_social."</b>';

        		document.getElementById('td_dato_31').innerHTML = '<b>".$porc_paro_forzoso."</b>';
        		document.getElementById('td_dato_32').innerHTML = '';
        		document.getElementById('td_dato_33').innerHTML = '<b>".$monto_paro_forzoso."</b>';

        		document.getElementById('td_dato_34').innerHTML = '<b>".$porc_fondo_ahorro."</b>';
        		document.getElementById('td_dato_35').innerHTML = '';
        		document.getElementById('td_dato_36').innerHTML = '<b>".$monto_fondo_ahorro."</b>';

        		document.getElementById('td_dato_37').innerHTML = '<b>".$porc_fondo_jub."</b>';
        		document.getElementById('td_dato_38').innerHTML = '';
        		document.getElementById('td_dato_39').innerHTML = '<b>".$monto_fondo_jub."</b>';

        		document.getElementById('td_dato_40').innerHTML = '<b>".$porc_caja_ahorro."</b>';
        		document.getElementById('td_dato_41').innerHTML = '';
        		document.getElementById('td_dato_42').innerHTML = '<b>".$monto_caja_ahorro."</b>';

        		document.getElementById('td_dato_43').innerHTML = '<b>".$cuota_prestamo."</b>';
        		document.getElementById('td_dato_44').innerHTML = '';
        		document.getElementById('td_dato_45').innerHTML = '<b>".$monto_prestamo."</b>';

        		document.getElementById('td_dato_46').innerHTML = '<b>".$porc_cuota_sindical."</b>';
        		document.getElementById('td_dato_47').innerHTML = '';
        		document.getElementById('td_dato_48').innerHTML = '<b>".$monto_cuota_sindical."</b>';

        		document.getElementById('td_dato_49').innerHTML = '<b>".$cuota_vivienda."</b>';
        		document.getElementById('td_dato_50').innerHTML = '';
        		document.getElementById('td_dato_51').innerHTML = '<b>".$monto_credito_vivienda."</b>';

        		document.getElementById('td_dato_52').innerHTML = '';
        		document.getElementById('td_dato_53').innerHTML = '';
				document.getElementById('td_dato_54').style.color='#940000';
        		document.getElementById('td_dato_54').innerHTML = '<b>".$total_deducciones."</b>';

        		document.getElementById('td_dato_55').innerHTML = '';
        		document.getElementById('td_dato_56').innerHTML = '';
				document.getElementById('td_dato_57').style.color='#940000';
        		document.getElementById('td_dato_57').innerHTML = '<b>".$total_recibir_neto."</b>';
				document.getElementById('guardar').disabled = false;
				document.getElementById('procesar').disabled = false;
			</script>";

		unset($datos_vac);

	}else{
		$this->set('errorMessage','No lleg&oacute; informaci&oacute;n completa para procesar - Intente Nuevamente');
		echo "<script>
				document.getElementById('guardar').disabled = true;
				document.getElementById('procesar').disabled = false;
			</script>";
	}

} // fin funcion calcular_vacacion



	function guardar_datos_vacaciones($paginac=null, $redire=null){
    	$this->layout="ajax";
		$cod_presi     = $this->Session->read('SScodpresi');
		$cod_entidad   = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst      = $this->Session->read('SScodinst');
		$cod_dep       = $this->Session->read('SScoddep');
		$condicion     = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

    	if(!empty($_SESSION['datos_guardar_vac'])){

    		extract($_SESSION['datos_guardar_vac']);

			$sql_in = "('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$cod_tipo_nomina', '$cod_cargo', '$cod_ficha', '$cedula_identidad', '$ano', '$numero', '$fecha_inicio', '$fecha_termina', '$periodo_desde', '$periodo_hasta', '$dias_inhabiles', '$numero_lunes', '$fecha_calculo', '$cantidad_vacaciones', '$salario_mensual', '$salario_diario', '$dias_vacaciones', '$salario_diario_vaca', '$dias_adicio_vacaciones', '$dias_bonificacion', '$sueldo_diario_bonifica', '$dias_bono_vacacional', '$salario_diario_bono_vac', '$dias_adicio_bono_vaca', '$dias_sab_dom_fer', '$porc_seguro_social', '$monto_seguro_social', '$porc_paro_forzoso', '$monto_paro_forzoso', '$porc_fondo_ahorro', '$monto_fondo_ahorro', '$porc_fondo_jub', '$monto_fondo_jub', '$porc_cuota_sindical', '$cuota_sindical', '$monto_cuota_sindical', '$porc_caja_ahorro', '$monto_caja_ahorro', '$cuota_prestamo', '$monto_prestamo_caja', '$cuota_vivienda', '$monto_credito_vivienda', '$porc_aporte_seguro_social', '$monto_aporte_seguro_social', '$porc_aporte_paro_forzoso', '$monto_aporte_paro_forzoso', '$porc_aporte_fondo_ahorro', '$monto_aporte_fondo_ahorro', '$porc_aporte_fondo_jub', '$monto_aporte_fondo_jub', '$porc_aporte_ahorro', '$monto_aporte_ahorro', '$observaciones', '$tipo_operacion'); ";
			$sql_up = "fecha_inicio = '$fecha_inicio', fecha_termina = '$fecha_termina', periodo_desde = '$periodo_desde', periodo_hasta = '$periodo_hasta', dias_inhabiles = '$dias_inhabiles', numero_lunes = '$numero_lunes', fecha_calculo = '$fecha_calculo', cantidad_vacaciones = '$cantidad_vacaciones', salario_mensual = '$salario_mensual', salario_diario = '$salario_diario', dias_vacaciones = '$dias_vacaciones', salario_diario_vaca = '$salario_diario_vaca', dias_adicio_vacaciones = '$dias_adicio_vacaciones', dias_bonificacion = '$dias_bonificacion', sueldo_diario_bonifica = '$sueldo_diario_bonifica', dias_bono_vacacional = '$dias_bono_vacacional', salario_diario_bono_vac = '$salario_diario_bono_vac', dias_adicio_bono_vaca = '$dias_adicio_bono_vaca', dias_sab_dom_fer = '$dias_sab_dom_fer', porc_seguro_social = '$porc_seguro_social', monto_seguro_social = '$monto_seguro_social', porc_paro_forzoso = '$porc_paro_forzoso', monto_paro_forzoso = '$monto_paro_forzoso', porc_fondo_ahorro = '$porc_fondo_ahorro', monto_fondo_ahorro = '$monto_fondo_ahorro', porc_fondo_jub = '$porc_fondo_jub', monto_fondo_jub = '$monto_fondo_jub', porc_cuota_sindical = '$porc_cuota_sindical', cuota_sindical = '$cuota_sindical', monto_cuota_sindical = '$monto_cuota_sindical', porc_caja_ahorro = '$porc_caja_ahorro', monto_caja_ahorro = '$monto_caja_ahorro', cuota_prestamo = '$cuota_prestamo', monto_prestamo_caja = '$monto_prestamo_caja', cuota_vivienda = '$cuota_vivienda', monto_credito_vivienda = '$monto_credito_vivienda', porc_aporte_seguro_social = '$porc_aporte_seguro_social', monto_aporte_seguro_social = '$monto_aporte_seguro_social', porc_aporte_paro_forzoso = '$porc_aporte_paro_forzoso', monto_aporte_paro_forzoso = '$monto_aporte_paro_forzoso', porc_aporte_fondo_ahorro = '$porc_aporte_fondo_ahorro', monto_aporte_fondo_ahorro = '$monto_aporte_fondo_ahorro', porc_aporte_fondo_jub = '$porc_aporte_fondo_jub', monto_aporte_fondo_jub = '$monto_aporte_fondo_jub', porc_aporte_ahorro = '$porc_aporte_ahorro', monto_aporte_ahorro = '$monto_aporte_ahorro', observaciones = '$observaciones', tipo_operacion = '$tipo_operacion' WHERE ".$condicion." and cod_tipo_nomina='$cod_tipo_nomina' and cod_cargo='$cod_cargo' and cod_ficha='$cod_ficha' and cedula_identidad='$cedula_identidad' and ano='$ano' and numero='$numero';";

			$busca_perma = $this->v_cnmp16_vacaciones_bonos_permanente->findCount($condicion." and cod_tipo_nomina='$cod_tipo_nomina' and cod_cargo='$cod_cargo' and cod_ficha='$cod_ficha' and cedula_identidad='$cedula_identidad' and ano='$ano' and numero='$numero'");
			if($busca_perma==0){
				$sql_tabla1 = "INSERT INTO cnmd16_vacaciones_bonos_perma VALUES ";
				$sql_tabla1 .= $sql_in;
			}else{
				$sql_tabla1 = "UPDATE cnmd16_vacaciones_bonos_perma SET ";
				$sql_tabla1 .= $sql_up;
			}

			$busca_temp = $this->v_cnmp16_vacaciones_bonos_temporal->findCount($condicion." and cod_tipo_nomina='$cod_tipo_nomina' and cod_cargo='$cod_cargo' and cod_ficha='$cod_ficha' and cedula_identidad='$cedula_identidad' and ano='$ano' and numero='$numero'");
			if($busca_temp==0){
				$sql_tabla2 = "INSERT INTO cnmd16_vacaciones_bonos_temporal VALUES ";
				$sql_tabla2 .= $sql_in;
			}else{
				$sql_tabla2 = "UPDATE cnmd16_vacaciones_bonos_temporal SET ";
				$sql_tabla2 .= $sql_up;
			}

			$exec_data_sql = $this->cnmd16_vacaciones_bonos_perma->execute($sql_tabla1." ".$sql_tabla2);
			if($exec_data_sql > 1){
				// $this->set('mensaje','Los datos fueron guardados exitosamente');
				echo "<script>fun_msj2('Los datos fueron guardados exitosamente');</script>";
				if(isset($paginac) && $paginac!=null && $paginac!='a'){
					$this->consulta($paginac,'read_consulta');
					$this->render('consulta');
				}else{
					if(isset($redire) && $redire=='mostrar'){
						$this->mostrar_busqueda($cod_tipo_nomina,$cedula_identidad,$cod_cargo,$cod_ficha);
						$this->render('mostrar_busqueda');
					}else{
						$this->mostrar_busqueda_p($cod_tipo_nomina,$cedula_identidad,$cod_cargo,$cod_ficha,$ano,$numero);
						$this->render('mostrar_busqueda_p');
					}
				}
			}else{
				// $this->set('mensajeError','Los datos no fueron guardados - Intente Nuevamente');
				echo "<script>fun_msj('Los datos no fueron guardados - Intente Nuevamente');</script>";
			}
    	}else{
			// $this->set('mensajeError','No se pudo extraer los datos - Intente Nuevamente');
			echo "<script>fun_msj('No se pudo extraer los datos - Intente Nuevamente');</script>";
    	}

	} // fin funcion guardar_datos_vacaciones



function eliminar($cod_nomina=null, $cod_ca=null, $cod_fi=null, $cedula=null, $anio=null, $nume=null, $page=null, $var_redic=null){
	$this->layout="ajax";
	$cod_presi     = $this->Session->read('SScodpresi');
	$cod_entidad   = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst      = $this->Session->read('SScodinst');
	$cod_dep       = $this->Session->read('SScoddep');
	$condicion     = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

	$sql_delete = "DELETE FROM cnmd16_vacaciones_bonos_perma WHERE ".$condicion." and cod_tipo_nomina='$cod_nomina' and cod_cargo='$cod_ca' and cod_ficha='$cod_fi' and cedula_identidad='$cedula' and ano='$anio' and numero='$nume'";
	$sql_delete2 = "DELETE FROM cnmd16_vacaciones_bonos_temporal WHERE ".$condicion." and cod_tipo_nomina='$cod_nomina' and cod_cargo='$cod_ca' and cod_ficha='$cod_fi' and cedula_identidad='$cedula' and ano='$anio' and numero='$nume'";

	$sw = $this->cnmd16_vacaciones_bonos_perma->execute($sql_delete."; ".$sql_delete2);
	if($sw>1){
		if($var_redic=='mod'){
			$this->set('Message_existe', 'La eliminacion fue realizada con exito');
			$this->index();
			$this->render('index');
		}else {
			$this->set('mensaje', 'La eliminacion fue realizada con exito');
			$this->consulta($page,'read_consulta');
			$this->render('consulta');
		}

	}else{
		if($var_redic=='mod'){
			$this->set('errorMessage', 'No se logro eliminar el dato - por favor intente de nuevo');
			$this->index();
			$this->render('index');
		}else {
			$this->set('mensajeError', 'No se logro eliminar el dato - por favor intente de nuevo');
			$this->consulta($page+1,'read_consulta');
			$this->render('consulta');
		}
	}

} // fin funcion eliminar


function form_firmas_vacaciones(){
    $this->layout="ajax";
    $this->Session->delete('codigo_tipo_nomina_b');
    $lista = $this->Cnmd01->generateList($this->SQLCA(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if(!empty($lista)){
		$this->concatenaN($lista, 'tipo_nomina');
	}else{
		$this->set('tipo_nomina', array());
	}

	$firmantes = $this->cnmd15_firmas_informes->findAll($this->SQLCA()." and tipo_documento=24100");

	if($firmantes!=null){
		$this->set('firma_existe','si');
		$this->set('b_readonly','readonly');
		$this->set('tipo_documento',$firmantes[0]['cnmd15_firmas_informes']['tipo_documento']);
		$this->set('nombre_primera_firma',$firmantes[0]['cnmd15_firmas_informes']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cnmd15_firmas_informes']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cnmd15_firmas_informes']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cnmd15_firmas_informes']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cnmd15_firmas_informes']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cnmd15_firmas_informes']['cargo_tercera_firma']);
		$this->set('nombre_cuarta_firma',$firmantes[0]['cnmd15_firmas_informes']['nombre_cuarta_firma']);
		$this->set('cargo_cuarta_firma',$firmantes[0]['cnmd15_firmas_informes']['cargo_cuarta_firma']);
		$this->set('nombre_quinta_firma',$firmantes[0]['cnmd15_firmas_informes']['nombre_quinta_firma']);
		$this->set('cargo_quinta_firma',$firmantes[0]['cnmd15_firmas_informes']['cargo_quinta_firma']);
		$this->set('nombre_sexta_firma',$firmantes[0]['cnmd15_firmas_informes']['nombre_sexta_firma']);
		$this->set('cargo_sexta_firma',$firmantes[0]['cnmd15_firmas_informes']['cargo_sexta_firma']);
		$this->set('nombre_septima_firma',$firmantes[0]['cnmd15_firmas_informes']['nombre_septima_firma']);
		$this->set('cargo_septima_firma',$firmantes[0]['cnmd15_firmas_informes']['cargo_septima_firma']);
		$this->set('nombre_octava_firma',$firmantes[0]['cnmd15_firmas_informes']['nombre_octava_firma']);
		$this->set('cargo_octava_firma',$firmantes[0]['cnmd15_firmas_informes']['cargo_octava_firma']);
		$this->set('primera_copia',$firmantes[0]['cnmd15_firmas_informes']['primera_copia']);
		$this->set('segunda_copia',$firmantes[0]['cnmd15_firmas_informes']['segunda_copia']);
		$this->set('tercera_copia',$firmantes[0]['cnmd15_firmas_informes']['tercera_copia']);
		$this->set('cuarta_copia',$firmantes[0]['cnmd15_firmas_informes']['cuarta_copia']);
		$this->set('quinta_copia',$firmantes[0]['cnmd15_firmas_informes']['quinta_copia']);
		$this->set('sexta_copia',$firmantes[0]['cnmd15_firmas_informes']['sexta_copia']);
		$this->set('septima_copia',$firmantes[0]['cnmd15_firmas_informes']['septima_copia']);
		$this->set('octava_copia',$firmantes[0]['cnmd15_firmas_informes']['octava_copia']);
		$pie_pagina_doc = str_replace("\t"," ",$firmantes[0]['cnmd15_firmas_informes']['pie_pagina']);
		$pie_pagina_doc = str_replace("\n"," ",$pie_pagina_doc);
		$this->set('pie_pagina',$pie_pagina_doc);
	}else{
		$this->set('Message_existe','POR FAVOR, INGRESE LOS NOMBRES Y CARGO DE LOS FIRMANTES');
		$this->set('firma_existe','no');
		$this->set('b_readonly','');
		$this->set('tipo_documento','24100');
		$this->set('nombre_primera_firma','');
		$this->set('cargo_primera_firma','');
		$this->set('nombre_segunda_firma','');
		$this->set('cargo_segunda_firma','');
		$this->set('nombre_tercera_firma','');
		$this->set('cargo_tercera_firma','');
		$this->set('nombre_cuarta_firma','');
		$this->set('cargo_cuarta_firma','');
		$this->set('nombre_quinta_firma','');
		$this->set('cargo_quinta_firma','');
		$this->set('nombre_sexta_firma','');
		$this->set('cargo_sexta_firma','');
		$this->set('nombre_septima_firma','');
		$this->set('cargo_septima_firma','');
		$this->set('nombre_octava_firma','');
		$this->set('cargo_octava_firma','');
		$this->set('primera_copia','');
		$this->set('segunda_copia','');
		$this->set('tercera_copia','');
		$this->set('cuarta_copia','');
		$this->set('quinta_copia','');
		$this->set('sexta_copia','');
		$this->set('septima_copia','');
		$this->set('octava_copia','');
		$this->set('pie_pagina','');
	}

}


function form_firmas_vacaciones_temporal(){
    $this->layout="ajax";
    $cod_tipo_nomina = $this->Session->read('codigo_tipo_nomina_b');
    $lista = $this->Cnmd01->generateList($this->SQLCA()." AND status_nomina IN (0,1)", $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	if(!empty($lista)){
		$this->concatenaN($lista, 'tipo_nomina');
	}else{
		$this->set('tipo_nomina', array());
	}

	$firmantes = $this->cnmd15_firmas_informes->findAll($this->SQLCA()." and tipo_documento=24100");

	if($firmantes!=null){
		$this->set('firma_existe','si');
		$this->set('b_readonly','readonly');
		$this->set('tipo_documento',$firmantes[0]['cnmd15_firmas_informes']['tipo_documento']);
		$this->set('nombre_primera_firma',$firmantes[0]['cnmd15_firmas_informes']['nombre_primera_firma']);
		$this->set('cargo_primera_firma',$firmantes[0]['cnmd15_firmas_informes']['cargo_primera_firma']);
		$this->set('nombre_segunda_firma',$firmantes[0]['cnmd15_firmas_informes']['nombre_segunda_firma']);
		$this->set('cargo_segunda_firma',$firmantes[0]['cnmd15_firmas_informes']['cargo_segunda_firma']);
		$this->set('nombre_tercera_firma',$firmantes[0]['cnmd15_firmas_informes']['nombre_tercera_firma']);
		$this->set('cargo_tercera_firma',$firmantes[0]['cnmd15_firmas_informes']['cargo_tercera_firma']);
		$this->set('nombre_cuarta_firma',$firmantes[0]['cnmd15_firmas_informes']['nombre_cuarta_firma']);
		$this->set('cargo_cuarta_firma',$firmantes[0]['cnmd15_firmas_informes']['cargo_cuarta_firma']);
		$this->set('nombre_quinta_firma',$firmantes[0]['cnmd15_firmas_informes']['nombre_quinta_firma']);
		$this->set('cargo_quinta_firma',$firmantes[0]['cnmd15_firmas_informes']['cargo_quinta_firma']);
		$this->set('nombre_sexta_firma',$firmantes[0]['cnmd15_firmas_informes']['nombre_sexta_firma']);
		$this->set('cargo_sexta_firma',$firmantes[0]['cnmd15_firmas_informes']['cargo_sexta_firma']);
		$this->set('nombre_septima_firma',$firmantes[0]['cnmd15_firmas_informes']['nombre_septima_firma']);
		$this->set('cargo_septima_firma',$firmantes[0]['cnmd15_firmas_informes']['cargo_septima_firma']);
		$this->set('nombre_octava_firma',$firmantes[0]['cnmd15_firmas_informes']['nombre_octava_firma']);
		$this->set('cargo_octava_firma',$firmantes[0]['cnmd15_firmas_informes']['cargo_octava_firma']);
		$this->set('primera_copia',$firmantes[0]['cnmd15_firmas_informes']['primera_copia']);
		$this->set('segunda_copia',$firmantes[0]['cnmd15_firmas_informes']['segunda_copia']);
		$this->set('tercera_copia',$firmantes[0]['cnmd15_firmas_informes']['tercera_copia']);
		$this->set('cuarta_copia',$firmantes[0]['cnmd15_firmas_informes']['cuarta_copia']);
		$this->set('quinta_copia',$firmantes[0]['cnmd15_firmas_informes']['quinta_copia']);
		$this->set('sexta_copia',$firmantes[0]['cnmd15_firmas_informes']['sexta_copia']);
		$this->set('septima_copia',$firmantes[0]['cnmd15_firmas_informes']['septima_copia']);
		$this->set('octava_copia',$firmantes[0]['cnmd15_firmas_informes']['octava_copia']);
		$pie_pagina_doc = str_replace("\t"," ",$firmantes[0]['cnmd15_firmas_informes']['pie_pagina']);
		$pie_pagina_doc = str_replace("\n"," ",$pie_pagina_doc);
		$this->set('pie_pagina',$pie_pagina_doc);
	}else{
		$this->set('Message_existe','POR FAVOR, INGRESE LOS NOMBRES Y CARGO DE LOS FIRMANTES');
		$this->set('firma_existe','no');
		$this->set('b_readonly','');
		$this->set('tipo_documento','24100');
		$this->set('nombre_primera_firma','');
		$this->set('cargo_primera_firma','');
		$this->set('nombre_segunda_firma','');
		$this->set('cargo_segunda_firma','');
		$this->set('nombre_tercera_firma','');
		$this->set('cargo_tercera_firma','');
		$this->set('nombre_cuarta_firma','');
		$this->set('cargo_cuarta_firma','');
		$this->set('nombre_quinta_firma','');
		$this->set('cargo_quinta_firma','');
		$this->set('nombre_sexta_firma','');
		$this->set('cargo_sexta_firma','');
		$this->set('nombre_septima_firma','');
		$this->set('cargo_septima_firma','');
		$this->set('nombre_octava_firma','');
		$this->set('cargo_octava_firma','');
		$this->set('primera_copia','');
		$this->set('segunda_copia','');
		$this->set('tercera_copia','');
		$this->set('cuarta_copia','');
		$this->set('quinta_copia','');
		$this->set('sexta_copia','');
		$this->set('septima_copia','');
		$this->set('octava_copia','');
		$this->set('pie_pagina','');
	}

}



function guardar_firmas_vacaciones(){
	$this->layout="ajax";

	$cp  = $this->Session->read('SScodpresi');
	$ce  = $this->Session->read('SScodentidad');
	$cti = $this->Session->read('SScodtipoinst');
	$ci  = $this->Session->read('SScodinst');
	$cd  = $this->Session->read('SScoddep');

	$tipo_doc = $this->data['cnmd15_firmas_informes']['tipo_documento'];
	$nombre_primera_firma = $this->data['cnmd15_firmas_informes']['nombre_primera_firma'];
	$cargo_primera_firma  = $this->data['cnmd15_firmas_informes']['cargo_primera_firma'];
	$nombre_segunda_firma = $this->data['cnmd15_firmas_informes']['nombre_segunda_firma'];
	$cargo_segunda_firma  = $this->data['cnmd15_firmas_informes']['cargo_segunda_firma'];

	$nombre_tercera_firma = isset($this->data['cnmd15_firmas_informes']['nombre_tercera_firma']) ? $this->data['cnmd15_firmas_informes']['nombre_tercera_firma'] : '';
	$cargo_tercera_firma  = isset($this->data['cnmd15_firmas_informes']['cargo_tercera_firma']) ? $this->data['cnmd15_firmas_informes']['cargo_tercera_firma'] : '';
	$nombre_cuarta_firma = isset($this->data['cnmd15_firmas_informes']['nombre_cuarta_firma']) ? $this->data['cnmd15_firmas_informes']['nombre_cuarta_firma'] : '';
	$cargo_cuarta_firma  = isset($this->data['cnmd15_firmas_informes']['cargo_cuarta_firma']) ? $this->data['cnmd15_firmas_informes']['cargo_cuarta_firma'] : '';
	$nombre_quinta_firma = isset($this->data['cnmd15_firmas_informes']['nombre_quinta_firma']) ? $this->data['cnmd15_firmas_informes']['nombre_quinta_firma'] : '';
	$cargo_quinta_firma  = isset($this->data['cnmd15_firmas_informes']['cargo_quinta_firma']) ? $this->data['cnmd15_firmas_informes']['cargo_quinta_firma'] : '';
	$nombre_sexta_firma = isset($this->data['cnmd15_firmas_informes']['nombre_sexta_firma']) ? $this->data['cnmd15_firmas_informes']['nombre_sexta_firma'] : '';
	$cargo_sexta_firma  = isset($this->data['cnmd15_firmas_informes']['cargo_sexta_firma']) ? $this->data['cnmd15_firmas_informes']['cargo_sexta_firma'] : '';
	$nombre_septima_firma = isset($this->data['cnmd15_firmas_informes']['nombre_septima_firma']) ? $this->data['cnmd15_firmas_informes']['nombre_septima_firma'] : '';
	$cargo_septima_firma  = isset($this->data['cnmd15_firmas_informes']['cargo_septima_firma']) ? $this->data['cnmd15_firmas_informes']['cargo_septima_firma'] : '';
	$nombre_octava_firma = isset($this->data['cnmd15_firmas_informes']['nombre_octava_firma']) ? $this->data['cnmd15_firmas_informes']['nombre_octava_firma'] : '';
	$cargo_octava_firma  = isset($this->data['cnmd15_firmas_informes']['cargo_octava_firma']) ? $this->data['cnmd15_firmas_informes']['cargo_octava_firma'] :'';

	$primera_cc = isset($this->data['cnmd15_firmas_informes']['primera_copia'])? $this->data['cnmd15_firmas_informes']['primera_copia'] : '';
	$segunda_cc = isset($this->data['cnmd15_firmas_informes']['segunda_copia'])? $this->data['cnmd15_firmas_informes']['segunda_copia'] : '';
	$tercera_cc = isset($this->data['cnmd15_firmas_informes']['tercera_copia'])? $this->data['cnmd15_firmas_informes']['tercera_copia'] : '';
	$cuarta_cc  = isset($this->data['cnmd15_firmas_informes']['cuarta_copia'])? $this->data['cnmd15_firmas_informes']['cuarta_copia'] : '';
	$quinta_cc  = isset($this->data['cnmd15_firmas_informes']['quinta_copia'])? $this->data['cnmd15_firmas_informes']['quinta_copia'] : '';
	$sexta_cc   = isset($this->data['cnmd15_firmas_informes']['sexta_copia'])? $this->data['cnmd15_firmas_informes']['sexta_copia'] : '';
	$septima_cc = isset($this->data['cnmd15_firmas_informes']['septima_copia'])? $this->data['cnmd15_firmas_informes']['septima_copia'] : '';
	$octava_cc  = isset($this->data['cnmd15_firmas_informes']['octava_copia'])? $this->data['cnmd15_firmas_informes']['octava_copia'] : '';

	$pie_pagina = isset($this->data['cnmd15_firmas_informes']['pie_pagina']) ? $this->data['cnmd15_firmas_informes']['pie_pagina'] : '';
	$pie_pagina = str_replace("\t"," ",$pie_pagina);
	$pie_pagina = str_replace("\n"," ",$pie_pagina);

	$enc_td_firma = $this->cnmd15_firmas_informes->findCount($this->SQLCA()." and tipo_documento=$tipo_doc");

	if($enc_td_firma==0){
		$muestr_accion = 'Registradas';
		$sql_ejecutar = "INSERT INTO cnmd15_firmas_informes VALUES ($cp, $ce, $cti, $ci, $cd, $tipo_doc,'$nombre_primera_firma', '$cargo_primera_firma', '$nombre_segunda_firma', '$cargo_segunda_firma', '$nombre_tercera_firma', '$cargo_tercera_firma', '$nombre_cuarta_firma', '$cargo_cuarta_firma', '$nombre_quinta_firma', '$cargo_quinta_firma', '$nombre_sexta_firma', '$cargo_sexta_firma', '$nombre_septima_firma', '$cargo_septima_firma', '$nombre_octava_firma', '$cargo_octava_firma', '$primera_cc', '$segunda_cc', '$tercera_cc', '$cuarta_cc', '$quinta_cc', '$sexta_cc', '$septima_cc', '$octava_cc', '$pie_pagina');";
	}else{
		$muestr_accion = 'Modificadas';
		$sql_ejecutar = "UPDATE cnmd15_firmas_informes SET nombre_primera_firma='$nombre_primera_firma', cargo_primera_firma='$cargo_primera_firma', nombre_segunda_firma='$nombre_segunda_firma', cargo_segunda_firma='$cargo_segunda_firma', nombre_tercera_firma='$nombre_tercera_firma', cargo_tercera_firma='$cargo_tercera_firma',              nombre_cuarta_firma='$nombre_cuarta_firma', cargo_cuarta_firma='$cargo_cuarta_firma', nombre_quinta_firma='$nombre_quinta_firma', cargo_quinta_firma='$cargo_quinta_firma', nombre_sexta_firma='$nombre_sexta_firma', cargo_sexta_firma='$cargo_sexta_firma', nombre_septima_firma='$nombre_septima_firma', cargo_septima_firma='$cargo_septima_firma', nombre_octava_firma='$nombre_octava_firma', cargo_octava_firma='$cargo_octava_firma', primera_copia='$primera_cc', segunda_copia='$segunda_cc', tercera_copia='$tercera_cc', cuarta_copia='$cuarta_cc', quinta_copia='$quinta_cc', sexta_copia='$sexta_cc', septima_copia='$septima_cc', octava_copia='$octava_cc', pie_pagina='$pie_pagina' WHERE ".$this->SQLCA()." and tipo_documento=".$tipo_doc;
	}

	$swi = $this->cnmd15_firmas_informes->execute($sql_ejecutar);

	if($swi>1){
		$this->set('Message_existe','Las firmas fuer&oacute;n '.$muestr_accion.' correctamente');
	}else{
		$this->set('errorMessage','Las firmas no fuer&oacute;n '.$muestr_accion.'');
	}

	$this->form_firmas_vacaciones();
	$this->render('form_firmas_vacaciones');

}


function guardar_firmas_vacaciones_temporal(){
	$this->layout="ajax";

	$cp  = $this->Session->read('SScodpresi');
	$ce  = $this->Session->read('SScodentidad');
	$cti = $this->Session->read('SScodtipoinst');
	$ci  = $this->Session->read('SScodinst');
	$cd  = $this->Session->read('SScoddep');

	$tipo_doc = $this->data['cnmd15_firmas_informes']['tipo_documento'];
	$nombre_primera_firma = $this->data['cnmd15_firmas_informes']['nombre_primera_firma'];
	$cargo_primera_firma  = $this->data['cnmd15_firmas_informes']['cargo_primera_firma'];
	$nombre_segunda_firma = $this->data['cnmd15_firmas_informes']['nombre_segunda_firma'];
	$cargo_segunda_firma  = $this->data['cnmd15_firmas_informes']['cargo_segunda_firma'];

	$nombre_tercera_firma = isset($this->data['cnmd15_firmas_informes']['nombre_tercera_firma']) ? $this->data['cnmd15_firmas_informes']['nombre_tercera_firma'] : '';
	$cargo_tercera_firma  = isset($this->data['cnmd15_firmas_informes']['cargo_tercera_firma']) ? $this->data['cnmd15_firmas_informes']['cargo_tercera_firma'] : '';
	$nombre_cuarta_firma = isset($this->data['cnmd15_firmas_informes']['nombre_cuarta_firma']) ? $this->data['cnmd15_firmas_informes']['nombre_cuarta_firma'] : '';
	$cargo_cuarta_firma  = isset($this->data['cnmd15_firmas_informes']['cargo_cuarta_firma']) ? $this->data['cnmd15_firmas_informes']['cargo_cuarta_firma'] : '';
	$nombre_quinta_firma = isset($this->data['cnmd15_firmas_informes']['nombre_quinta_firma']) ? $this->data['cnmd15_firmas_informes']['nombre_quinta_firma'] : '';
	$cargo_quinta_firma  = isset($this->data['cnmd15_firmas_informes']['cargo_quinta_firma']) ? $this->data['cnmd15_firmas_informes']['cargo_quinta_firma'] : '';
	$nombre_sexta_firma = isset($this->data['cnmd15_firmas_informes']['nombre_sexta_firma']) ? $this->data['cnmd15_firmas_informes']['nombre_sexta_firma'] : '';
	$cargo_sexta_firma  = isset($this->data['cnmd15_firmas_informes']['cargo_sexta_firma']) ? $this->data['cnmd15_firmas_informes']['cargo_sexta_firma'] : '';
	$nombre_septima_firma = isset($this->data['cnmd15_firmas_informes']['nombre_septima_firma']) ? $this->data['cnmd15_firmas_informes']['nombre_septima_firma'] : '';
	$cargo_septima_firma  = isset($this->data['cnmd15_firmas_informes']['cargo_septima_firma']) ? $this->data['cnmd15_firmas_informes']['cargo_septima_firma'] : '';
	$nombre_octava_firma = isset($this->data['cnmd15_firmas_informes']['nombre_octava_firma']) ? $this->data['cnmd15_firmas_informes']['nombre_octava_firma'] : '';
	$cargo_octava_firma  = isset($this->data['cnmd15_firmas_informes']['cargo_octava_firma']) ? $this->data['cnmd15_firmas_informes']['cargo_octava_firma'] :'';

	$primera_cc = isset($this->data['cnmd15_firmas_informes']['primera_copia'])? $this->data['cnmd15_firmas_informes']['primera_copia'] : '';
	$segunda_cc = isset($this->data['cnmd15_firmas_informes']['segunda_copia'])? $this->data['cnmd15_firmas_informes']['segunda_copia'] : '';
	$tercera_cc = isset($this->data['cnmd15_firmas_informes']['tercera_copia'])? $this->data['cnmd15_firmas_informes']['tercera_copia'] : '';
	$cuarta_cc  = isset($this->data['cnmd15_firmas_informes']['cuarta_copia'])? $this->data['cnmd15_firmas_informes']['cuarta_copia'] : '';
	$quinta_cc  = isset($this->data['cnmd15_firmas_informes']['quinta_copia'])? $this->data['cnmd15_firmas_informes']['quinta_copia'] : '';
	$sexta_cc   = isset($this->data['cnmd15_firmas_informes']['sexta_copia'])? $this->data['cnmd15_firmas_informes']['sexta_copia'] : '';
	$septima_cc = isset($this->data['cnmd15_firmas_informes']['septima_copia'])? $this->data['cnmd15_firmas_informes']['septima_copia'] : '';
	$octava_cc  = isset($this->data['cnmd15_firmas_informes']['octava_copia'])? $this->data['cnmd15_firmas_informes']['octava_copia'] : '';

	$pie_pagina = isset($this->data['cnmd15_firmas_informes']['pie_pagina']) ? $this->data['cnmd15_firmas_informes']['pie_pagina'] : '';
	$pie_pagina = str_replace("\t"," ",$pie_pagina);
	$pie_pagina = str_replace("\n"," ",$pie_pagina);

	$enc_td_firma = $this->cnmd15_firmas_informes->findCount($this->SQLCA()." and tipo_documento=$tipo_doc");

	if($enc_td_firma==0){
		$muestr_accion = 'Registradas';
		$sql_ejecutar = "INSERT INTO cnmd15_firmas_informes VALUES ($cp, $ce, $cti, $ci, $cd, $tipo_doc,'$nombre_primera_firma', '$cargo_primera_firma', '$nombre_segunda_firma', '$cargo_segunda_firma', '$nombre_tercera_firma', '$cargo_tercera_firma', '$nombre_cuarta_firma', '$cargo_cuarta_firma', '$nombre_quinta_firma', '$cargo_quinta_firma', '$nombre_sexta_firma', '$cargo_sexta_firma', '$nombre_septima_firma', '$cargo_septima_firma', '$nombre_octava_firma', '$cargo_octava_firma', '$primera_cc', '$segunda_cc', '$tercera_cc', '$cuarta_cc', '$quinta_cc', '$sexta_cc', '$septima_cc', '$octava_cc', '$pie_pagina');";
	}else{
		$muestr_accion = 'Modificadas';
		$sql_ejecutar = "UPDATE cnmd15_firmas_informes SET nombre_primera_firma='$nombre_primera_firma', cargo_primera_firma='$cargo_primera_firma', nombre_segunda_firma='$nombre_segunda_firma', cargo_segunda_firma='$cargo_segunda_firma', nombre_tercera_firma='$nombre_tercera_firma', cargo_tercera_firma='$cargo_tercera_firma',              nombre_cuarta_firma='$nombre_cuarta_firma', cargo_cuarta_firma='$cargo_cuarta_firma', nombre_quinta_firma='$nombre_quinta_firma', cargo_quinta_firma='$cargo_quinta_firma', nombre_sexta_firma='$nombre_sexta_firma', cargo_sexta_firma='$cargo_sexta_firma', nombre_septima_firma='$nombre_septima_firma', cargo_septima_firma='$cargo_septima_firma', nombre_octava_firma='$nombre_octava_firma', cargo_octava_firma='$cargo_octava_firma', primera_copia='$primera_cc', segunda_copia='$segunda_cc', tercera_copia='$tercera_cc', cuarta_copia='$cuarta_cc', quinta_copia='$quinta_cc', sexta_copia='$sexta_cc', septima_copia='$septima_cc', octava_copia='$octava_cc', pie_pagina='$pie_pagina' WHERE ".$this->SQLCA()." and tipo_documento=".$tipo_doc;
	}

	$swi = $this->cnmd15_firmas_informes->execute($sql_ejecutar);

	if($swi>1){
		$this->set('Message_existe','Las firmas fuer&oacute;n '.$muestr_accion.' correctamente');
	}else{
		$this->set('errorMessage','Las firmas no fuer&oacute;n '.$muestr_accion.'');
	}

	$this->form_firmas_vacaciones_temporal();
	$this->render('form_firmas_vacaciones_temporal');

}




function modificar_firmas_vacaciones(){
	$this->layout="ajax";
	$this->set('Message_existe','Puede modificar los nombres y cargos de los firmantes');
}




function modificar_firmas_vacaciones_temporal(){
	$this->layout="ajax";
	$this->set('Message_existe','Puede modificar los nombres y cargos de los firmantes');
}




function deno_nomina_rep ($cod_tipo_nomina=null){
     $this->layout="ajax";
     if (isset($cod_tipo_nomina)) {
     	$this->Session->write('codigo_tipo_nomina_b', $cod_tipo_nomina);
         $lista2 = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina,'cod_tipo_nomina, denominacion');
		if(!empty($lista2)){
			echo "<script>
    				document.getElementById('in_cod_tipo_nomina').value='".mascara_tres($lista2[0]['Cnmd01']['cod_tipo_nomina'])."';
				</script>";
			$this->set('tipo_nomina',$lista2);
		}else{
			$this->set('tipo_nomina', array());
		}
	if($this->v_cnmp16_vacaciones_bonos_permanente->findCount($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina)==0){
		echo "<script> fun_msj('NO SE ENCONTRAR&Oacute;N DATOS PARA PROCESAR');</script>";
	}else{

		$url                  =  "/cnmp16_vacaciones/buscar_persona3/1";
		$width_aux            =  "750px";
		$height_aux           =  "450px";
		$title_aux            =  "Buscar";
		$resizable_aux        =  false;
		$maximizable_aux      =  false;
		$minimizable_aux      =  false;
		$closable_aux         =  false;

			 echo "<script>";
	           echo "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
	         echo "</script>";
	}
	}
}


function deno_nomina_rep_temporal ($cod_tipo_nomina=null){
     $this->layout="ajax";
     if (isset($cod_tipo_nomina)) {
     	$this->Session->write('codigo_tipo_nomina_b', $cod_tipo_nomina);
         $lista2 = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina,'cod_tipo_nomina, denominacion');
		if(!empty($lista2)){
			echo "<script>
    				document.getElementById('in_cod_tipo_nomina').value='".mascara_tres($lista2[0]['Cnmd01']['cod_tipo_nomina'])."';
				</script>";
			$this->set('tipo_nomina',$lista2);
		}else{
			$this->set('tipo_nomina', array());
		}
	if($this->v_cnmp16_vacaciones_bonos_temporal->findCount($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina)==0){
		echo "<script> fun_msj('NO SE ENCONTRAR&Oacute;N DATOS PARA PROCESAR');</script>";

		echo'<script>';
        	echo'document.getElementById("generar").disabled = true; ';
        echo'</script>';

	}else{

		echo'<script>';
        	echo'document.getElementById("generar").disabled = false; ';
        echo'</script>';
		}
	}
}


function reporte_vacaciones($var_nom=null, $var1=null, $var2=null, $var3=null, $ano_p=null, $numero_p=null){
	$this->layout = "pdf";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

	$estado = $this->cugd01_estados->execute("SELECT denominacion FROM cugd01_estados WHERE cod_republica='".$cod_presi."' and cod_estado='".$cod_entidad."';");
	$_SESSION['estado'] = $estado[0][0]['denominacion'];
	$institucion = $this->cugd01_estados->execute("SELECT denominacion FROM cugd02_institucion WHERE cod_tipo_institucion='".$cod_tipo_inst."' and cod_institucion='".$cod_inst."';");
	$_SESSION['institucion'] = $institucion[0][0]['denominacion'];
	$dependencia = $this->cugd01_estados->execute("SELECT denominacion FROM cugd02_dependencias WHERE cod_tipo_institucion='".$cod_tipo_inst."' and cod_institucion='".$cod_inst."' and cod_dependencia='".$cod_dep."';");
	$_SESSION['dependencia'] = $dependencia[0][0]['denominacion'];

	$datos_resulta = $this->v_cnmp16_vacaciones_bonos_permanente->findAll($condicion." and cod_tipo_nomina='$var_nom' and cod_cargo='$var1' and cod_ficha='$var2' and cedula_identidad='$var3' and ano='$ano_p' and numero='$numero_p'", null, null, 1, 1, null);
	$this->set('datos_vacaciones', $datos_resulta);

	$dato_cnmd05 = $this->v_cnmd05->findAll($condicion.' and cod_tipo_nomina='.$var_nom.' and cod_cargo='.$var1.' ', 'cod_puesto, denominacion_clase');
	$this->set('denominacion_cargo', $dato_cnmd05[0]['v_cnmd05']['denominacion_clase']);

	$execute_anos_exp = $this->cnmd06_datos_personales->execute("select calculos_incidencia('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$datos_resulta[0]['v_cnmp16_vacaciones_bonos_permanente']['cod_tipo_nomina']."', '".$datos_resulta[0]['v_cnmp16_vacaciones_bonos_permanente']['cod_cargo']."', '".$datos_resulta[0]['v_cnmp16_vacaciones_bonos_permanente']['cod_ficha']."', '".$datos_resulta[0]['v_cnmp16_vacaciones_bonos_permanente']['cedula_identidad']."');");
	$anos_experie = $execute_anos_exp!=null ? $execute_anos_exp[0][0]["calculos_incidencia"] : 0;

		$fecha_calculo = $datos_resulta[0]['v_cnmp16_vacaciones_bonos_permanente']['fecha_calculo'];
		$fecha_ingreso = $datos_resulta[0]['v_cnmp16_vacaciones_bonos_permanente']['fecha_ingreso'];

        $tiempo_servicio  = $this->get_edad($fecha_calculo, $fecha_ingreso);
	    $tiempo_servicio  = explode(' ',$tiempo_servicio);
        $c_array = count($tiempo_servicio);

    if($c_array!=0){
  	    $dias = 0;
		$meses = 0;
		$anios = 0;
          for($t=0; $t<$c_array; $t++){
              if($tiempo_servicio[$t]=="mons" || $tiempo_servicio[$t]=="mon"){   $meses  = $tiempo_servicio[$t-1]; }
              if($tiempo_servicio[$t]=="day"  || $tiempo_servicio[$t]=="days"){  $dias   = $tiempo_servicio[$t-1]; }
              if($tiempo_servicio[$t]=="year" || $tiempo_servicio[$t]=="years"){ $anios  = $tiempo_servicio[$t-1]; }
          }
  	}else{
  	    $dias = 0;
		$meses = 0;
		$anios = 0;
	}

	if(isset($anos_experie) && $anos_experie!='' && $anos_experie!=0){ $anios_antig = $anios+$anos_experie; }else{ $anios_antig = $anios; $anos_experie = 0; }

	$this->set('c_dias', $dias);
	$this->set('c_meses', $meses);
	$this->set('c_anios', $anios);
	$this->set('c_anos_experie', $anos_experie);
	$this->set('c_anios_antig', $anios_antig);

	$d_firmantes = $this->cnmd15_firmas_informes->findAll($this->SQLCA()." and tipo_documento=24100");
	$this->set('datos_firmantes', $d_firmantes);
}


function reporte_vacaciones_temporal(){
	$this->layout = "pdf";
	$cod_tipo_nomina = $this->Session->read('codigo_tipo_nomina_b');
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

	$estado = $this->cugd01_estados->execute("SELECT denominacion FROM cugd01_estados WHERE cod_republica='".$cod_presi."' and cod_estado='".$cod_entidad."';");
	$_SESSION['estado'] = $estado[0][0]['denominacion'];
	$institucion = $this->cugd01_estados->execute("SELECT denominacion FROM cugd02_institucion WHERE cod_tipo_institucion='".$cod_tipo_inst."' and cod_institucion='".$cod_inst."';");
	$_SESSION['institucion'] = $institucion[0][0]['denominacion'];
	$dependencia = $this->cugd01_estados->execute("SELECT denominacion FROM cugd02_dependencias WHERE cod_tipo_institucion='".$cod_tipo_inst."' and cod_institucion='".$cod_inst."' and cod_dependencia='".$cod_dep."';");
	$_SESSION['dependencia'] = $dependencia[0][0]['denominacion'];


	//$datos_resulta = $this->v_cnmp16_vacaciones_bonos_temporal->findAll($condicion." and cod_tipo_nomina='$var_nom'", null, null, 1, 1, null);

	$sql_datos_resulta = "SELECT * FROM v_cnmp16_vacaciones_bonos_temporal WHERE cod_presi=$cod_presi and cod_entidad=$cod_entidad and cod_tipo_inst=$cod_tipo_inst and cod_inst=$cod_inst and cod_dep=$cod_dep and cod_tipo_nomina=$cod_tipo_nomina;";
	$datos_resulta = $this->Cnmd01->execute($sql_datos_resulta);
	$this->set('datos_vacaciones', $datos_resulta);

	$d_firmantes = $this->cnmd15_firmas_informes->findAll($this->SQLCA()." and tipo_documento=24100");
	$this->set('datos_firmantes', $d_firmantes);
}


}//fin class



?>