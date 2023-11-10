<?php


class ReporteJuanNominaController extends AppController{

    var $name = "reporte_juan_nomina";
    var $uses = array("ccfd04_cierre_mes", "Cnmd01", "cnmd03_transaccion", "v_cnmd07_transacciones_actuales", "v_cnmd07_transacciones_actuales_con",
                      "v_distribucion_asignacion_deduccion", "costo_presupuestario_p2", "v_distribucion_disponibilidad_2", "v_cnmd08_historia_trans_con",
                      "v_cnmd08_historia_trabajador", "v_cnmd08_historia_trabajador_vision", "cnmd01","v_distribucion_disponibilidad_2_historico", "v_cnmd08_historia_transacciones","cnmd09_deducciones_conectada_asignaciones");

	var $helpers = array('Html','Ajax','Javascript', 'Sisap', 'Fpdf');


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


function SQLCA_report($pre=null){
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         if($pre!=null && $pre==1){
         $sql_re .= "cod_inst=".$this->verifica_SS(4)." ";
         //$sql_re .= "cod_dep=0";
         }else{
         	$sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
            $sql_re .= "cod_dep=".$this->verifica_SS(5)." ";
         }

         return $sql_re;
    }//fin funcion SQLCA




    function SQLCA_report_a($pre=null){
         $sql_re = "a.cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "a.cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "a.cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         if($pre!=null && $pre==1){
         $sql_re .= "a.cod_inst=".$this->verifica_SS(4)." ";
         //$sql_re .= "cod_dep=0";
         }else{
         	$sql_re .= "a.cod_inst=".$this->verifica_SS(4)."  and  ";
            $sql_re .= "a.cod_dep=".$this->verifica_SS(5)." ";
         }

         return $sql_re;
    }//fin funcion SQLCA
    function SQLCA_report_in($pre=null){
         $sql_re = $this->verifica_SS(1).",";
         $sql_re .= $this->verifica_SS(2).",";
         $sql_re .= $this->verifica_SS(3).",";
         if($pre!=null && $pre==1){
         $sql_re .= $this->verifica_SS(4).",";
         $sql_re .= 0;
         }else{
         	$sql_re .= $this->verifica_SS(4).",";
            $sql_re .= $this->verifica_SS(5)." ";
         }

         return $sql_re;
    }//fin funcion SQLCA




function beforeFilter(){
					$this->checkSession();

}//fin function










function show_cod_nomina($cod_tipo_nomina=null){
	$this->layout="ajax";
	if($cod_tipo_nomina != null){
		$this->Session->write('tipo_nomina', $cod_tipo_nomina);
		$denominacion = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and cod_tipo_nomina='$cod_tipo_nomina'", $order ="cod_tipo_nomina ASC");
		$this->set('denominacion', $denominacion);
	}

	  echo"<script>";
			  echo"document.getElementById('cod_nomina').value='".mascara_tres($cod_tipo_nomina)."';";
	  echo"</script>";

}//fin function


















function resumen_general_por_concepto($var1=null, $var2=null, $var3=null){

       if($var1==1){
           $this->layout = "ajax";

	           $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		       $this->concatena($lista, 'lista_nomina');
		       $this->Session->delete('tipo_nomina');

		       $consulta_c_t = $this->cnmd03_transaccion->generateList("cod_tipo_transaccion=1", 'cod_transaccion ASC', null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.denominacion');
	   	       $this->concatena($consulta_c_t, "lista");



  }else if($var1==2){
           $this->layout = "pdf";

           set_time_limit(0);
	       ini_set("memory_limit","2000M");

                $cod_nomina      = $this->data["reporte_juan_nomina"]["cod_nomina"];
                $opcion_busqueda = $this->data["reporte_juan_nomina"]["opcion_busqueda"];

                $sql  = $this->condicion()." and cod_tipo_nomina='".$cod_nomina."'   ";
                $sql2 = $this->condicion()." and cod_tipo_nomina='".$cod_nomina."'   ";

	                if($opcion_busqueda==4){
	                       $tipo_transaccion  = $this->data["reporte_juan_nomina"]["tipo_transaccion"];
	                       $select_tra        = $this->data["reporte_juan_nomina"]["select_tra"];
	                       $sql              .= " and cod_tipo_transaccion='".$tipo_transaccion."' and cod_transaccion='".$select_tra."'  ";
	                }//fin if



                    $datos        = $this->v_cnmd07_transacciones_actuales_con->findAll($sql." and (condicion_actividad_ficha=1) ", null, "cod_tipo_transaccion, cod_transaccion, cedula_identidad, cod_cargo, cod_ficha ASC ");
                    $datos_nomina = $this->Cnmd01->findAll($sql2, null, null);

                    $this->set("datos",        $datos);
                    $this->set("datos_nomina", $datos_nomina);
                    $this->set("opcion_busqueda", $opcion_busqueda);




 }else if($var1==3){
           $this->layout = "ajax";

              echo'<script>';
		         echo" document.getElementById('capa_1').style.display = 'none'; ";
		         echo" document.getElementById('capa_2').style.display = 'block'; ";
              echo'</script>';

              echo'<script>';
		         echo" document.getElementById('datos_a').value = ''; ";
		         echo" document.getElementById('datos_b').value = ''; ";
		         echo" document.getElementById('select_tra').options[0].value = ''; ";
		         echo" document.getElementById('select_tra').options[0].text = ''; ";
		     echo'</script>';

 }else if($var1==4){
           $this->layout = "ajax";


               echo'<script>';
		           echo" document.getElementById('capa_1').style.display = 'block'; ";
		           echo" document.getElementById('capa_2').style.display = 'none'; ";
              echo'</script>';



 }//fin else




$this->set('opcion', $var1);



}//fin function




function txt_resumen_general_por_concepto($var1=null, $cod_nomina=null, $opcion_busqueda=null, $tipo_transaccion=null, $select_tra=null){

  if($var1==1){

	$this->layout = "ajax";

/*
           $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	       $this->concatena($lista, 'lista_nomina');
	       $this->Session->delete('tipo_nomina');

	       $consulta_c_t = $this->cnmd03_transaccion->generateList("cod_tipo_transaccion=1", 'cod_transaccion ASC', null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.denominacion');
   	       $this->concatena($consulta_c_t, "lista");
*/

  }else if($var1==2){

  	set_time_limit(0);
	ini_set("memory_limit","2000M");

	$this->layout  = "txt";

	if($cod_nomina != null){
				$cod_dep = $this->Session->read('SScoddep');
                //$cod_nomina      = $this->data["reporte_juan_nomina"]["cod_nomina"];
                //$opcion_busqueda = $this->data["reporte_juan_nomina"]["opcion_busqueda"];

                $sql = $this->condicion()." and cod_tipo_nomina='".$cod_nomina."'   ";

	                if($opcion_busqueda==4){

	                       //$tipo_transaccion  = $this->data["reporte_juan_nomina"]["tipo_transaccion"];
	                       //$select_tra        = $this->data["reporte_juan_nomina"]["select_tra"];

	                       $sql .= " and cod_tipo_transaccion='".$tipo_transaccion."' and cod_transaccion='".$select_tra."'  ";
	                }//fin if

                    $datos = $this->v_cnmd07_transacciones_actuales_con->findAll($sql." and (condicion_actividad_ficha=1) ", "cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, cod_tipo_transaccion, cod_transaccion, monto_cuota, fecha_proceso", "cod_tipo_transaccion, cod_transaccion, cedula_identidad, cod_cargo, cod_ficha ASC ");
                    $this->set("datos", $datos);

if(!empty($datos)){

	$nombre_archivo = 'Resumen_Concepto_'.mascara($cod_dep,4).'_'.mascara($cod_nomina,3).'_'.date('d_m_Y_h:i:sa').'';
	$_SESSION["nombre_txt"] = $nombre_archivo.".txt";
	$filas_archivo = "";

		   foreach($datos as $rsdata){
		   	   extract($rsdata['v_cnmd07_transacciones_actuales_con']);
               $campo = array();
		   	   $campo[] = mascara($cod_tipo_nomina,3);
		   	   $campo[] = mascara($cod_cargo,8);
		   	   $campo[] = mascara($cod_ficha,8);
		   	   $campo[] = mascara($cedula_identidad,12);
		   	   $campo[] = $cod_tipo_transaccion;
		   	   $campo[] = mascara($cod_transaccion,4);
		   	   $campo[] = $monto_cuota;
		   	   $campo[] = str_replace('/','',cambia_fecha($fecha_proceso));
		   	   $campo[] = '';
		   	   $campos = implode(';',$campo);
               $filas_archivo .= $campos."\n";
			}

		$this->wFile($nombre_archivo, $filas_archivo);
		$this->set('filas_archivo', $filas_archivo);
}

	}else{
		$this->set('filas_archivo', "");
	}
  } //fin else if var1=2
	$this->set('opcion', $var1); // patron para ir a la vista::1    o    al txt::2

}//fin function txt_resumen_general_por_concepto










function resumen_general_por_concepto_historico($var1=null, $var2=null, $var3=null){

       if($var1==1){
           $this->layout = "ajax";

	           $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		       $this->concatena($lista, 'lista_nomina');
		       $this->Session->delete('tipo_nomina');

		       $consulta_c_t = $this->cnmd03_transaccion->generateList("cod_tipo_transaccion=1", 'cod_transaccion ASC', null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.denominacion');
	   	       $this->concatena($consulta_c_t, "lista");



  }else if($var1==2){
           $this->layout = "pdf";

           set_time_limit(0);
	       ini_set("memory_limit","1000M");

                $cod_nomina        = $this->data["cnmp06_diskett_historico"]["cod_nomina"];
                $ano_nomina        = $this->data["cnmp06_diskett_historico"]["ano_nomina"];
                $numero_nomina     = $this->data["cnmp06_diskett_historico"]["numero_nomina"];
                $opcion_busqueda   = $this->data["reporte_juan_nomina"]["opcion_busqueda"];
                $sql  = $this->condicion()." and cod_tipo_nomina='".$cod_nomina."'   ";
                $sql2 = $this->condicion()." and cod_tipo_nomina='".$cod_nomina."'   ";

	                if($opcion_busqueda==4){
	                       $tipo_transaccion  = $this->data["reporte_juan_nomina"]["tipo_transaccion"];
	                       $select_tra        = $this->data["reporte_juan_nomina"]["select_tra"];
	                       $sql              .= " and cod_tipo_transaccion='".$tipo_transaccion."' and cod_transaccion='".$select_tra."'  ";
	                }//fin if



                    $datos        = $this->v_cnmd08_historia_trans_con->findAll($sql."  and ano='".$ano_nomina."' and numero_nomina='".$numero_nomina."'   ", null, "cod_tipo_transaccion, cod_transaccion, cedula_identidad, cod_cargo, cod_ficha ASC ");
                    $datos_nomina = $this->Cnmd01->findAll($sql2, null, null);

                    $this->set("datos",        $datos);
                    $this->set("datos_nomina", $datos_nomina);
                    $this->set("opcion_busqueda", $opcion_busqueda);




 }else if($var1==3){
           $this->layout = "ajax";

              echo'<script>';
		         echo" document.getElementById('capa_1').style.display = 'none'; ";
		         echo" document.getElementById('capa_2').style.display = 'block'; ";
              echo'</script>';

              echo'<script>';
		         echo" document.getElementById('datos_a').value = ''; ";
		         echo" document.getElementById('datos_b').value = ''; ";
		         echo" document.getElementById('select_tra').options[0].value = ''; ";
		         echo" document.getElementById('select_tra').options[0].text = ''; ";
		     echo'</script>';

 }else if($var1==4){
           $this->layout = "ajax";


               echo'<script>';
		           echo" document.getElementById('capa_1').style.display = 'block'; ";
		           echo" document.getElementById('capa_2').style.display = 'none'; ";
              echo'</script>';



 }//fin else




$this->set('opcion', $var1);



}//fin function







function txt_resumen_general_por_concepto_historico($var1=null, $cod_nomina=null, $ano_nomina=null, $numero_nomina=null, $opcion_busqueda=null, $tipo_transaccion=null, $select_tra=null){

  if($var1==1){

	$this->layout = "ajax";

/*
	           $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		       $this->concatena($lista, 'lista_nomina');
		       $this->Session->delete('tipo_nomina');

		       $consulta_c_t = $this->cnmd03_transaccion->generateList("cod_tipo_transaccion=1", 'cod_transaccion ASC', null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.denominacion');
	   	       $this->concatena($consulta_c_t, "lista");
*/

  }else if($var1==2){

  	set_time_limit(0);
	ini_set("memory_limit","2000M");

	$this->layout  = "txt";

	if($cod_nomina != null){
				$cod_dep = $this->Session->read('SScoddep');

                // $cod_nomina        = $this->data["cnmp06_diskett_historico"]["cod_nomina"];
                // $ano_nomina        = $this->data["cnmp06_diskett_historico"]["ano_nomina"];
                // $numero_nomina     = $this->data["cnmp06_diskett_historico"]["numero_nomina"];
                // $opcion_busqueda   = $this->data["reporte_juan_nomina"]["opcion_busqueda"];

                $sql = $this->condicion()." and cod_tipo_nomina='".$cod_nomina."'   ";

	                if($opcion_busqueda==4){
	                       // $tipo_transaccion  = $this->data["reporte_juan_nomina"]["tipo_transaccion"];
	                       // $select_tra        = $this->data["reporte_juan_nomina"]["select_tra"];
	                       $sql .= " and cod_tipo_transaccion='".$tipo_transaccion."' and cod_transaccion='".$select_tra."'  ";
	                }//fin if

                    $datos = $this->v_cnmd08_historia_trans_con->findAll($sql."  and ano='".$ano_nomina."' and numero_nomina='".$numero_nomina."'   ", "cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad, cod_tipo_transaccion, cod_transaccion, monto_cuota, fecha_transaccion, deno_transaccion, correspondiente", "cod_tipo_transaccion, cod_transaccion, cedula_identidad, cod_cargo, cod_ficha ASC ");
                    $this->set("datos", $datos);

if(!empty($datos)){

	$nombre_archivo = $datos[0]['v_cnmd08_historia_trans_con']['deno_transaccion'].'_'.$datos[0]['v_cnmd08_historia_trans_con']['correspondiente'];
	$_SESSION["nombre_txt"] = $nombre_archivo.".txt";
	$filas_archivo = "";

		   foreach($datos as $rsdata){
		   	   extract($rsdata['v_cnmd08_historia_trans_con']);
               $campo = array();
		   	   $campo[] = mascara($cod_tipo_nomina,3);
		   	   $campo[] = mascara($cod_cargo,8);
		   	   $campo[] = mascara($cod_ficha,8);
		   	   $campo[] = mascara($cedula_identidad,12);
		   	   $campo[] = $cod_tipo_transaccion;
		   	   $campo[] = mascara($cod_transaccion,4);
		   	   $campo[] = $monto_cuota;
		   	   $campo[] = str_replace('/','',cambia_fecha($fecha_transaccion));
		   	   $campo[] = '';
		   	   $campos = implode(';',$campo);
               $filas_archivo .= $campos."\n";
			}

		$this->wFile($nombre_archivo, $filas_archivo);
		$this->set('filas_archivo', $filas_archivo);
}

	}else{
		$this->set('filas_archivo', "");
	}
  } //fin else if var1=2
	$this->set('opcion', $var1); // patron para ir a la vista::1    o    al txt::2

}//fin function txt_resumen_general_por_concepto








function select_tran($var1=null, $var2=null){

$this->layout = "ajax";

if($var1==1){
  $sql = "cod_tipo_transaccion=".$var1;
}else{
  $sql = "cod_tipo_transaccion=".$var1." ";
}//fin else

          $consulta_c_t = $this->cnmd03_transaccion->generateList($sql, 'cod_transaccion ASC', null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.denominacion');
	   	  $this->concatena($consulta_c_t, "lista");

	   	  $this->set("opcion1", $var1);

	 echo'<script>';
         echo" document.getElementById('datos_a').value = ''; ";
         echo" document.getElementById('datos_b').value = ''; ";
     echo'</script>';

}//fin function







function seleccion_tran($var1=null, $var2=null){

$this->layout = "ajax";

if($var1==1){
  $sql = "cod_tipo_transaccion=".$var1." and cod_transaccion=".$var2;
}else{
  $sql = "cod_tipo_transaccion=".$var1."  and cod_transaccion=".$var2;
}//fin else

          $consulta_c_t = $this->cnmd03_transaccion->generateList($sql, 'cod_transaccion ASC', null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.denominacion');
	   	  $this->concatena($consulta_c_t, "lista");

	   	  $this->set("opcion1", $var1);
	   	  $this->set("opcion2", $var2);

	   	    $data2 =  $this->cnmd03_transaccion->findAll($sql, null, null, null);
            $this->set('denominacion', $data2[0]["cnmd03_transaccion"]["denominacion"]);

	 echo'<script>';
         echo" document.getElementById('datos_a').value = '".mascara2($var2)."'; ";
     echo'</script>';


}//fin function







function distribucion_disponibilidad_nomina($var1=null,$var2=null, $var3=null){

	      if($var1==1){ $this->layout = "ajax";

			    $condicion = $this->SQLCA();
				$lista = $this->Cnmd01->generateList($condicion, 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
				if($lista!=null){
					$this->concatena($lista, 'lista');
				}else{
					$this->set('lista',array('no'=>'no hay registros'));
				}


	}else if($var1==2){ $this->layout = "pdf";

		$tipo_nomina = $this->data['reporte_personal']['select_tiponomina'];
		$datos_tipo_nomina = $this->Cnmd01->execute("SELECT cod_tipo_nomina, denominacion, correspondiente, numero_nomina, periodo_desde, periodo_hasta FROM cnmd01 WHERE ".$this->SQLCA()." and cod_tipo_nomina='$tipo_nomina'");
		$this->set('datos_tipo_nomina',$datos_tipo_nomina);

        $data = $this->v_distribucion_disponibilidad_2->findAll($this->SQLCA()." and cod_tipo_nomina=".$tipo_nomina,null, "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_tipo_transaccion, tipo_orden, cod_transaccion, denominacion");
        $this->set("data", $data);
        $this->set("opcion", $var1);

         $ano=$this->ano_ejecucion();

        $datos_tipo_nomina_cnmd09_deducciones = $this->Cnmd01->execute("SELECT * FROM v_cnmd09_deducciones_conectada_asignaciones WHERE ".$this->SQLCA()." and cod_tipo_nomina='$tipo_nomina' and ano='".$ano."'  and cod_tipo_transaccion_ded=2  ");
		$this->set('datos_tipo_nomina_cnmd09_deducciones',$datos_tipo_nomina_cnmd09_deducciones);



	}//fin else
$this->set("opcion", $var1);
}//fin function





















function diario_nomina($var1=null, $var2=null, $var3=null){

	set_time_limit(0);
	ini_set("memory_limit","2000M");

	if($var1==1){
		$this->layout = "ajax";
		$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		$this->concatena($lista, 'lista_nomina');
		$this->Session->delete('tipo_nomina');

	}else if($var1==2){
		$this->layout = "ajax";

		$cod_nomina       = $this->data["reporte_juan_nomina"]["cod_nomina"];
		$opcion_ordenar   = $this->data["reporte_juan_nomina"]["opcion_ordenar"];

		$sql = $this->condicion()." and cod_tipo_nomina=".$cod_nomina;

		// Ordenado por ubicacion administrativa
		      if($opcion_ordenar == 1){
            $order_by = "cod_dir_superior, cod_coordinacion, cod_secretaria, cod_direccion, cod_division, cod_departamento, cod_oficina,  cedula_identidad, cod_cargo, cod_ficha, cod_tipo_transaccion, cod_transaccion ASC";
		// Ordenado por ubicacion geografica
		}else if ($opcion_ordenar == 2){
			$order_by = "cod_estado, cod_municipio, cod_parroquia, cod_centro,  cedula_identidad, cod_cargo, cod_ficha, cod_tipo_transaccion, cod_transaccion ASC";
		// Ordenado por ubicacion administrativa y geografica
		}else if ($opcion_ordenar == 3){
			$order_by = "cod_dir_superior, cod_coordinacion, cod_secretaria, cod_direccion, cod_division, cod_departamento, cod_oficina,  cod_estado, cod_municipio, cod_parroquia, cod_centro, cedula_identidad, cod_cargo, cod_ficha, cod_tipo_transaccion, cod_transaccion ASC";
		// Ordenado por categoria programatica
		}else if ($opcion_ordenar == 4){
			$order_by = "ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar,  cedula_identidad, cod_cargo, cod_ficha, cod_tipo_transaccion, cod_transaccion ASC";
        // Ordenado por categoria programatica y administrativa
        }else if ($opcion_ordenar == 5){
			$order_by = "ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar,  cod_dir_superior, cod_coordinacion, cod_secretaria, cod_direccion, cod_division, cod_departamento, cod_oficina, cedula_identidad, cod_cargo, cod_ficha, cod_tipo_transaccion, cod_transaccion ASC";


		}


		/*

		if($opcion_ordenar==3){
			$order_by = "cod_dir_superior, cod_coordinacion, cod_secretaria, cod_direccion, cod_division, cod_departamento, cod_oficina,  cedula_identidad, cod_tipo_transaccion, cod_transaccion ASC";

		}else if($opcion_ordenar==4){
			$order_by = "cod_estado, cod_municipio, cod_parroquia, cod_centro,  cedula_identidad, cod_tipo_transaccion, cod_transaccion ASC";

		}else if($opcion_ordenar==5){
			$order_by = "ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar,  cedula_identidad, cod_tipo_transaccion, cod_transaccion ASC";

		}//fin else

		*/

		$campos = ' cod_presi ,
					cod_entidad,
					cod_tipo_inst,
					cod_inst,
					cod_dep,
					cod_tipo_transaccion,
					cod_transaccion,
					deno_transaccion,
					uso_transaccion,
					SUM(monto_cuota) as monto_cuota';

		$agrupar = 'GROUP BY    cod_presi,
								cod_entidad,
								cod_tipo_inst,
								cod_inst,
								cod_dep ,
								cod_tipo_transaccion ,
								cod_transaccion,
								deno_transaccion,
								uso_transaccion';

		$sql2= $sql." and (condicion_actividad_ficha=1) ".$agrupar;

		$DATOS_agrupa       = $this->v_cnmd07_transacciones_actuales_con->findAll($sql2, $campos, ' cod_tipo_transaccion, cod_transaccion ASC',  null, null);
		$datos              = $this->v_cnmd07_transacciones_actuales_con->findAll($sql." and (condicion_actividad_ficha=1) ", null, $order_by);

		$datos_nomina       = $this->Cnmd01->findAll($sql, null, null);
		$DATOS_agrupa_saldo = $this->v_cnmd07_transacciones_actuales_con->findAll($sql2, $campos, ' cod_tipo_transaccion, cod_transaccion ASC',  null, null);
		

//		pr($datos);

    $this->set("DATOS_agrupa_saldo", $DATOS_agrupa_saldo);
		$this->set("DATOS_agrupa",$DATOS_agrupa);
		$this->set("datos",$datos);
		$this->set("datos_nomina", $datos_nomina);
		$this->set("opcion_ordenar", $opcion_ordenar);

		$this->set('opcion', $var1);

		
	}else{
		$this->layout = "ajax";
	}//fin else

 $this->set('opcion', $var1);

}//fin function










function diario_nomina_historico($var1=null, $var2=null, $var3=null){

	set_time_limit(0);
	ini_set("memory_limit","2304M");

	if($var1==1){
		$this->layout = "ajax";
		$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		$this->concatena($lista, 'lista_nomina');
		$this->Session->delete('tipo_nomina');

	}else if($var1==2){
		$this->layout = "ajax";

		$cod_nomina       = $this->data['cnmp06_diskett_historico']['cod_nomina'];
		$ano              = $this->data['cnmp06_diskett_historico']['ano_nomina'];
		$num_nomina       = $this->data['cnmp06_diskett_historico']['numero_nomina'];
		$opcion_ordenar   = $this->data["reporte_juan_nomina"]["opcion_ordenar"];
        //pr($this->data);
		$sql = $this->condicion()." and cod_tipo_nomina=".$cod_nomina." ";

		// Ordenado por ubicacion administrativa
		      if($opcion_ordenar == 1){
            $order_by = "cod_dir_superior, cod_coordinacion, cod_secretaria, cod_direccion, cod_division, cod_departamento, cod_oficina,  cedula_identidad, cod_cargo, cod_ficha, cod_tipo_transaccion, cod_transaccion ASC";
		// Ordenado por ubicacion geografica
		}else if ($opcion_ordenar == 2){
			$order_by = "cod_estado, cod_municipio, cod_parroquia, cod_centro,  cedula_identidad, cod_cargo, cod_ficha, cod_tipo_transaccion, cod_transaccion ASC";
		// Ordenado por ubicacion administrativa y geografica
		}else if ($opcion_ordenar == 3){
			$order_by = "cod_dir_superior, cod_coordinacion, cod_secretaria, cod_direccion, cod_division, cod_departamento, cod_oficina,  cod_estado, cod_municipio, cod_parroquia, cod_centro, cedula_identidad, cod_cargo, cod_ficha, cod_tipo_transaccion, cod_transaccion ASC";
		// Ordenado por categoria programatica
		}else if ($opcion_ordenar == 4){
			$order_by = "ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar,  cedula_identidad, cod_cargo, cod_ficha, cod_tipo_transaccion, cod_transaccion ASC";
        // Ordenado por categoria programatica y administrativa
        }else if ($opcion_ordenar == 5){
			$order_by = "ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar,  cod_dir_superior, cod_coordinacion, cod_secretaria, cod_direccion, cod_division, cod_departamento, cod_oficina, cedula_identidad, cod_cargo, cod_ficha, cod_tipo_transaccion, cod_transaccion ASC";


		}


		/*

		if($opcion_ordenar==3){
			$order_by = "cod_dir_superior, cod_coordinacion, cod_secretaria, cod_direccion, cod_division, cod_departamento, cod_oficina,  cedula_identidad, cod_tipo_transaccion, cod_transaccion ASC";

		}else if($opcion_ordenar==4){
			$order_by = "cod_estado, cod_municipio, cod_parroquia, cod_centro,  cedula_identidad, cod_tipo_transaccion, cod_transaccion ASC";

		}else if($opcion_ordenar==5){
			$order_by = "ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar,  cedula_identidad, cod_tipo_transaccion, cod_transaccion ASC";

		}//fin else

		*/

		$campos = ' cod_presi ,
					cod_entidad,
					cod_tipo_inst,
					cod_inst,
					cod_dep,
					cod_tipo_transaccion,
					cod_transaccion,
					deno_transaccion,
					uso_transaccion,
					SUM(monto_cuota) as monto_cuota';

		$agrupar = 'GROUP BY    cod_presi,
								cod_entidad,
								cod_tipo_inst,
								cod_inst,
								cod_dep ,
								cod_tipo_transaccion ,
								cod_transaccion,
								deno_transaccion,
								uso_transaccion';

		$sql2= $sql." and ano='".$ano."' and numero_nomina='".$num_nomina."' ".$agrupar;

		$DATOS_agrupa       = $this->v_cnmd08_historia_transacciones->findAll($sql2."  ", $campos, ' cod_tipo_transaccion, cod_transaccion ASC',  null, null);
		$datos              = $this->v_cnmd08_historia_transacciones->findAll($sql."  and ano='".$ano."' and numero_nomina='".$num_nomina."'  ", null, $order_by);
		$datos_nomina       = $this->v_cnmd08_historia_trans_con->findAll($sql."  and ano='".$ano."' and numero_nomina='".$num_nomina."'  ", null, null);
		$DATOS_agrupa_saldo = $this->v_cnmd08_historia_transacciones->findAll($sql2."  ", $campos, ' cod_tipo_transaccion, cod_transaccion ASC',  null, null);
        //echo "<br>\n".$sql."  and ano='".$ano."' and numero_nomina='".$num_nomina."'  ";
        //echo $sql2;

        //pr($DATOS_agrupa);
        //pr($datos);
        //pr($datos_nomina);
        $this->set("DATOS_agrupa_saldo", $DATOS_agrupa_saldo);
		$this->set("DATOS_agrupa",$DATOS_agrupa);
		$this->set("datos",$datos);
		$this->set("datos_nomina", $datos_nomina);
		$this->set("opcion_ordenar", $opcion_ordenar);

		$this->set('opcion', $var1);


	}else{
		$this->layout = "ajax";
	}//fin else

 $this->set('opcion', $var1);

}//fin function










function cnmd08_transacciones_historico_consulta($var1=null, $var2=null, $var3=null){

       if($var1==1){
           $this->layout = "ajax";
           $this->set('opcion', $var1);

	           $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		       $this->concatenaN($lista, 'lista_nomina');
		       $this->Session->delete('tipo_nomina');

		       if($var2==1){
                $this->render("cnmd08_transacciones_historico_consulta");
		       }else{
                $this->render("cnmd08_transacciones_historico_consulta_2");
		       }


  }else if($var1==2){
           $this->layout = "pdf";
           $this->set('opcion', $var1);


  }



}//fin function






function cnmd08_transacciones_historico_consulta_2($var1=null, $var2=null, $var3=null){

       if($var1==1){
           $this->layout = "ajax";

	           $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		       $this->concatenaN($lista, 'lista_nomina');
		       $this->Session->delete('tipo_nomina');


  }else if($var1==2){
           $this->layout = "pdf";


  }

 $this->set('opcion', $var1);

}//fin function














function show_cod_nomina_2($cod_tipo_nomina=null){
	$this->layout="ajax";
	if($cod_tipo_nomina != null){
		$this->Session->write('tipo_nomina', $cod_tipo_nomina);
		$denominacion = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and cod_tipo_nomina='$cod_tipo_nomina'", $order ="cod_tipo_nomina ASC");
		$this->set('denominacion', $denominacion);
	}
	  echo"<script>";
			  echo"document.getElementById('cod_nomina').value='".mascara_tres($cod_tipo_nomina)."';";
	  echo"</script>";
	  echo"<script>";
			  echo"document.getElementById('segunda_ventana').disabled=true;";
	  echo"</script>";
}//fin function

function show_ano_nomina_2($cod_tipo_nomina=null){
	$this->layout="ajax";
	$rsp=$this->Cnmd01->execute("SELECT DISTINCT ano  FROM cnmd08_historia_trabajador  WHERE ". $this->condicion()." and cod_tipo_nomina='".$cod_tipo_nomina."'  ORDER BY ano ASC");
	    foreach($rsp as $lp){
			$vp[]=$lp[0]["ano"];
			$dp[]=$lp[0]["ano"];
		}
		if(isset($vp)){$ano = array_combine($vp, $dp);}else{$ano = array(); }
		$this->set('lista', $ano);
	$this->set('opcion1', $cod_tipo_nomina);
	echo"<script>";
			  echo"document.getElementById('segunda_ventana').disabled=true;";
	echo"</script>";
}

function show_numero_nomina_2($cod_tipo_nomina=null, $ano=null){
	$this->layout="ajax";
	$opcion_buscar_historico   = $this->Session->read('opcion_buscar_historico');
	if($opcion_buscar_historico==1){
	        $rsp=$this->Cnmd01->execute("SELECT DISTINCT numero_nomina, concepto  FROM cnmd08_historia_nomina  WHERE ". $this->condicion()." and cod_tipo_nomina='".$cod_tipo_nomina."' and ano='".$ano."' ORDER BY numero_nomina ASC");
		    $this->Session->write('ano_nomina', $ano);
		    $ano = array();
			$this->set('lista', $ano);
			echo"<script>";
				  echo"document.getElementById('segunda_ventana').disabled=false;";
		    echo"</script>";
	}else{
			$rsp=$this->Cnmd01->execute("SELECT DISTINCT numero_nomina, concepto  FROM cnmd08_historia_nomina  WHERE ". $this->condicion()." and cod_tipo_nomina='".$cod_tipo_nomina."' and ano='".$ano."' ORDER BY numero_nomina ASC");
		    $this->Session->write('ano_nomina', $ano);
		    foreach($rsp as $lp){
				$vp[]=$lp[0]["numero_nomina"];
				$dp[]=mascara($lp[0]["numero_nomina"],8)." - ".$lp[0]["concepto"];
			}
			if(isset($vp)){$ano = array_combine($vp, $dp);}else{$ano = array(); }
			$this->set('lista', $ano);
			echo"<script>";
				  echo"document.getElementById('segunda_ventana').disabled=true;";
		    echo"</script>";
	}
	$this->set('opcion_buscar_historico', $opcion_buscar_historico);
}//fin function


function show_numero_nomina_3($numero=null){
	$this->layout="ajax";
	$this->Session->write('numero_nomina', $numero);
		echo"<script>";
			  echo"document.getElementById('segunda_ventana').disabled=false;";
	    echo"</script>";
}









function buscar_persona_historico($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
	$this->Session->write('pista_opcion', 2);
}//fin function


function buscar_persona_historico_2($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
$sql_like = "";
$tipo_nomina               = $this->Session->read('tipo_nomina');
$ano_nomina                = $this->Session->read('ano_nomina');
$numero_nomina             = $this->Session->read('numero_nomina');
$opcion_buscar_historico   = $this->Session->read('opcion_buscar_historico');

                    $cod_presi      =  $this->Session->read('SScodpresi');
					$cod_entidad    =  $this->Session->read('SScodentidad');
					$cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
					$cod_inst       =  $this->Session->read('SScodinst');
					$cod_dep        =  $this->Session->read('SScoddep');

	    if($var3==null){
	    	            $var2 = strtoupper($var2);
						$this->Session->write('pista', $var2);
						$var_like = $var2;
						if($opcion_buscar_historico==1){
						   $sql_like = $this->SQLCA(). " and (".$this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion')).") and cod_tipo_nomina   =  '".$tipo_nomina."'  and ano='".$ano_nomina."'  ";
						}else{
						   $sql_like = $this->SQLCA(). " and (".$this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion')).") and cod_tipo_nomina   =  '".$tipo_nomina."'  and ano='".$ano_nomina."' and numero_nomina='".$numero_nomina."' ";
						}
						$Tfilas=$this->v_cnmd08_historia_trabajador->findCount($sql_like);
						        if($Tfilas!=0){
						        	$pagina=1;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_cnmd08_historia_trabajador->findAll($sql_like,"  DISTINCT   cedula_identidad, cod_cargo, cod_ficha, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido ","primer_nombre,primer_apellido ASC",100,1,null);
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
						if($opcion_buscar_historico==1){
						   $sql_like = $this->SQLCA(). " and (".$this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion')).") and cod_tipo_nomina   =  '".$tipo_nomina."'  and ano='".$ano_nomina."'  ";
						}else{
						   $sql_like = $this->SQLCA(). " and (".$this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion')).") and cod_tipo_nomina   =  '".$tipo_nomina."'  and ano='".$ano_nomina."' and numero_nomina='".$numero_nomina."' ";
						}
						$Tfilas=$this->v_cnmd08_historia_trabajador->findCount($sql_like);
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/100);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_cnmd08_historia_trabajador->findAll($sql_like,"  DISTINCT   cedula_identidad, cod_cargo, cod_ficha, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido ","primer_nombre,primer_apellido ASC",100,$pagina,null);
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






function buscar_persona_historico_3($var1=null, $var2=null, $pag_num=null){
$this->layout     = "ajax";

$tipo_nomina      = $this->Session->read('tipo_nomina');
$ano_nomina       = $this->Session->read('ano_nomina');
$numero_nomina    = $this->Session->read('numero_nomina');
$cedula_identidad = $var1;
$cod_cargo        = $var2;

$opcion_buscar_historico   = $this->Session->read('opcion_buscar_historico');

                        if($opcion_buscar_historico==1){
						   $sql_like         = $this->SQLCA(). " and cod_tipo_nomina   =  '".$tipo_nomina."'  and ano='".$ano_nomina."' and cedula_identidad='".$cedula_identidad."' and cod_cargo='".$cod_cargo."'  ";
						}else{
						   $sql_like         = $this->SQLCA(). " and cod_tipo_nomina   =  '".$tipo_nomina."'  and ano='".$ano_nomina."' and numero_nomina='".$numero_nomina."' and cedula_identidad='".$cedula_identidad."' and cod_cargo='".$cod_cargo."'  ";
						}



$datos_cnmd07_transacciones_actuales = $this->v_cnmd08_historia_trans_con->findAll($sql_like, null, ' numero_nomina, cod_tipo_transaccion,  cod_transaccion  ASC');
$this->set("datos_cnmd07_transacciones_actuales",$datos_cnmd07_transacciones_actuales);



$datos_distin = $this->v_cnmd08_historia_trans_con->findAll($sql_like, "  DISTINCT   numero_nomina, frecuencia_pago, periodo_desde, periodo_hasta", ' numero_nomina ASC');
$this->set("datos_distin", $datos_distin);


}//fin function


function buscar_persona_historico_4($var1=null){
$this->layout     = "ajax";
$opcion_buscar_historico      = $this->Session->write('opcion_buscar_historico', $var1);
}//fin function






function cnmd08_recibos_pago_historico($var1=null, $var2=null, $var3=null){
	if($var1==1){
		$this->layout = "ajax";
		$this->set('opcion', $var1);
		$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		$this->concatena($lista, 'lista_nomina');
		$this->Session->delete('tipo_nomina');
		if($var2==1){
			$this->render("cnmd08_recibos_pago_historico");
		}else{
			$this->render("cnmd08_recibos_pago_historico_2");
		}
	}else if($var1==2){
		$this->layout = "pdf";
		$this->set('opcion', $var1);
	}
}//fin cnmd08_recibos_pago_historico


function cnmd08_recibos_pago_historico_2($var1=null, $var2=null, $var3=null){
	if($var1==1){
		$this->layout = "ajax";
		$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		$this->concatena($lista, 'lista_nomina');
		$this->Session->delete('tipo_nomina');
	}else if($var1==2){
		$this->layout = "pdf";
	}
	$this->set('opcion', $var1);
}//fin cnmd08_recibos_pago_historico_2


function show_ano_nomina_2_recibos($cod_tipo_nomina=null){
	$this->layout="ajax";
	$this->Session->delete('codig_tipo_nomina');
	$this->Session->write('codig_tipo_nomina', $cod_tipo_nomina);
	$rsp=$this->Cnmd01->execute("SELECT DISTINCT ano  FROM cnmd08_historia_trabajador  WHERE ". $this->condicion()." and cod_tipo_nomina='".$cod_tipo_nomina."'  ORDER BY ano ASC");
	    foreach($rsp as $lp){
			$vp[]=$lp[0]["ano"];
			$dp[]=$lp[0]["ano"];
		}
		if(isset($vp)){$ano = array_combine($vp, $dp);}else{$ano = array(); }
		$this->set('lista', $ano);
	$this->set('opcion1', $cod_tipo_nomina);
	echo"<script>";
			  echo"document.getElementById('segunda_ventana').disabled=true;";
	echo"</script>";
}

function show_numero_nomina_2_recibos($cod_tipo_nomina=null, $ano=null){
	$this->layout="ajax";
	$opcion_buscar_historico   = $this->Session->read('opcion_buscar_historico');
	if($opcion_buscar_historico==1){
	        $rsp=$this->Cnmd01->execute("SELECT DISTINCT numero_nomina, concepto  FROM cnmd08_historia_nomina  WHERE ". $this->condicion()." and cod_tipo_nomina='".$cod_tipo_nomina."' and ano='".$ano."' ORDER BY numero_nomina ASC");
		    $this->Session->write('ano_nomina', $ano);
		    $ano = array();
			$this->set('lista', $ano);
			echo"<script>";
				  echo"document.getElementById('segunda_ventana').disabled=false;";
		    echo"</script>";
	}else{
			$rsp=$this->Cnmd01->execute("SELECT DISTINCT numero_nomina, concepto  FROM cnmd08_historia_nomina  WHERE ". $this->condicion()." and cod_tipo_nomina='".$cod_tipo_nomina."' and ano='".$ano."' ORDER BY numero_nomina ASC");
		    $this->Session->write('ano_nomina', $ano);
		    foreach($rsp as $lp){
				$vp[]=$lp[0]["numero_nomina"];
				$dp[]=mascara($lp[0]["numero_nomina"],8)." - ".$lp[0]["concepto"];
			}
			if(isset($vp)){$ano = array_combine($vp, $dp);}else{$ano = array(); }
			$this->set('lista', $ano);
			echo"<script>";
				  echo"document.getElementById('segunda_ventana').disabled=true;";
		    echo"</script>";
	}
	$this->set('opcion_buscar_historico', $opcion_buscar_historico);
}//fin function


function show_numero_nomina_3_recibos($numero=null){
	$this->layout="ajax";
	$this->Session->delete('cantidad_de_pagos');
	$ano = $this->Session->read('ano_nomina');
	$cod_tipo_nomina = $this->Session->read('codig_tipo_nomina');
	$cantidad_pago = $this->Cnmd01->execute("SELECT cantidad_pagos FROM cnmd08_historia_nomina  WHERE ". $this->condicion()." and cod_tipo_nomina='".$cod_tipo_nomina."' and ano='".$ano."' and numero_nomina='".$numero."'");
	$cant = $cantidad_pago[0][0]['cantidad_pagos'];
	$this->Session->write('numero_nomina', $numero);
	$this->Session->write('cantidad_de_pagos', $cant);
		echo"<script>";
			  echo"document.getElementById('segunda_ventana').disabled=false;";
	    echo"</script>";
}


function buscar_persona_historico_recibos($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
	$this->Session->write('pista_opcion', 2);
}//fin function


function buscar_persona_historico_2_recibos($var1=null, $var2=null, $var3=null){
	$this->layout="ajax";
	$sql_like = "";
	$tipo_nomina               = $this->Session->read('tipo_nomina');
	$ano_nomina                = $this->Session->read('ano_nomina');
	$numero_nomina             = $this->Session->read('numero_nomina');
	$opcion_buscar_historico   = $this->Session->read('opcion_buscar_historico');

	$cod_presi      =  $this->Session->read('SScodpresi');
	$cod_entidad    =  $this->Session->read('SScodentidad');
	$cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
	$cod_inst       =  $this->Session->read('SScodinst');
	$cod_dep        =  $this->Session->read('SScoddep');

	if($var3==null){
		$var2 = strtoupper($var2);
		$this->Session->write('pista', $var2);
		$var_like = $var2;
		if($opcion_buscar_historico==1){
		   $sql_like = $this->SQLCA(). " and (".$this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion')).") and cod_tipo_nomina   =  '".$tipo_nomina."'  and ano='".$ano_nomina."'  ";
		}else{
		   $sql_like = $this->SQLCA(). " and (".$this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion')).") and cod_tipo_nomina   =  '".$tipo_nomina."'  and ano='".$ano_nomina."' ";
		}
		$Tfilas=$this->v_cnmd08_historia_trabajador_vision->findCount($sql_like); // v_cnmd08_historia_trabajador
		        if($Tfilas!=0){
		        	$pagina=1;
		        	$Tfilas=(int)ceil($Tfilas/100);
		        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
					$this->set('total_paginas',$Tfilas);
					$this->set('pagina_actual',$pagina);
					$this->set('ultimo',$Tfilas);
		     	    $datos_filas=$this->v_cnmd08_historia_trabajador_vision->findAll($sql_like,"  DISTINCT   cedula_identidad, cod_cargo, cod_ficha, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido ","primer_nombre,primer_apellido ASC",100,1,null);
	                                    $sql = "";

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
		$var_like = $var22;
		if($opcion_buscar_historico==1){
		   $sql_like = $this->SQLCA(). " and (".$this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion')).") and cod_tipo_nomina   =  '".$tipo_nomina."'  and ano='".$ano_nomina."'  ";
		}else{
		   $sql_like = $this->SQLCA(). " and (".$this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion')).") and cod_tipo_nomina   =  '".$tipo_nomina."'  and ano='".$ano_nomina."' ";
		}
		$Tfilas=$this->v_cnmd08_historia_trabajador_vision->findCount($sql_like);
		        if($Tfilas!=0){
		        	$pagina=$var3;
		        	$Tfilas=(int)ceil($Tfilas/100);
		        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
					$this->set('total_paginas',$Tfilas);
					$this->set('pagina_actual',$pagina);
					$this->set('ultimo',$Tfilas);
		     	    $datos_filas=$this->v_cnmd08_historia_trabajador_vision->findAll($sql_like,"  DISTINCT   cedula_identidad, cod_cargo, cod_ficha, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido ","primer_nombre,primer_apellido ASC",100,$pagina,null);
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
}//fin buscar_persona_historico_2_recibos


function buscar_persona_historico_3_recibos($var1=null, $var2=null, $pag_num=null){
	$this->layout     = "ajax";
	$tipo_nomina      = $this->Session->read('tipo_nomina');
	$ano_nomina       = $this->Session->read('ano_nomina');
	$numero_nomina    = $this->Session->read('numero_nomina');
	$cedula_identidad = $var1;
	$cod_cargo        = $var2;

	$opcion_buscar_historico   = $this->Session->read('opcion_buscar_historico');

	if($opcion_buscar_historico==1){
		$sql_like = $this->SQLCA(). " and cod_tipo_nomina   =  '".$tipo_nomina."'  and ano='".$ano_nomina."' and cedula_identidad='".$cedula_identidad."' and cod_cargo='".$cod_cargo."'  ";
	}else{
		$sql_like = $this->SQLCA(). " and cod_tipo_nomina   =  '".$tipo_nomina."'  and ano='".$ano_nomina."' and numero_nomina='".$numero_nomina."' and cedula_identidad='".$cedula_identidad."' and cod_cargo='".$cod_cargo."'  ";
	}

	$datos_cnmd07_transacciones_actuales = $this->v_cnmd08_historia_trans_con->findAll($sql_like, null, ' numero_nomina, cod_tipo_transaccion,  cod_transaccion  ASC');
	$this->set("datos_cnmd07_transacciones_actuales",$datos_cnmd07_transacciones_actuales);

	$datos_distin = $this->v_cnmd08_historia_trans_con->findAll($sql_like, "  DISTINCT   numero_nomina, frecuencia_pago, periodo_desde, periodo_hasta", ' numero_nomina ASC');
	$this->set("datos_distin", $datos_distin);

	$datos_cnmd08_historia_trab = $this->v_cnmd08_historia_trabajador->findAll($sql_like, null, ' numero_nomina ASC');
	$this->set("datos_cnmd08_historia_trab", $datos_cnmd08_historia_trab);

	$denominacion_nomina = $this->cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina='$tipo_nomina'", 'denominacion', ' numero_nomina ASC');
	$this->set("denominacion_nomina", $denominacion_nomina[0]['Cnmd01']['denominacion']);
}//fin buscar_persona_historico_3_recibos


function emision_recibos_pago_historico($cod_tipo_nomina=null,$ano=null,$cod_cargo=null,$cod_ficha=null,$cedula=null,$num_nomina=null,$num_recibo=null){
		$this->layout="pdf";
		$cant_pago = $this->Session->read('cantidad_de_pagos');
		$this->set('cant_pagos', $cant_pago);
		$condicion = $this->SQLCA()." AND a.cod_tipo_nomina='$cod_tipo_nomina' AND a.cedula_identidad='$cedula' AND a.numero_recibo='$num_recibo'";

		$sql = "SELECT a.cod_tipo_nomina, a.denominacion_nomina, a.cod_coordinacion, a.deno_cod_coordinacion, a.cod_secretaria, a.deno_cod_secretaria,
		a.cod_direccion, a.deno_cod_direccion, a.cod_division, a.deno_cod_division, a.cod_departamento, a.deno_cod_departamento, a.cod_oficina, a.deno_cod_oficina,
		a.cod_municipio, a.deno_cod_municipio, a.cod_parroquia, a.deno_cod_parroquia, a.cod_centro, a.deno_cod_centro,
		a.cedula_identidad, a.primer_apellido, a.segundo_apellido, a.primer_nombre, a.segundo_nombre, a.cod_puesto, a.denominacion_puesto, a.cod_cargo, a.cod_ficha, a.sueldo_basico,
		a.fecha_ingreso, a.numero_nomina, a.correspondiente, a.periodo_desde, a.periodo_hasta,
		a.cod_tipo_transaccion, a.cod_transaccion, a.deno_transaccion, a.dias_horas, a.frecuencia_cobro, a.monto_cuota, a.saldo, a.ultimo_recibo,
		a.cod_entidad_bancaria, a.deno_entidades_bancarias, a.cod_sucursal, a.cuenta_bancaria, a.uso_transaccion, a.nombre_representado, a.cedula_representado,
		(SELECT b.denominacion_devengado FROM cnmd01 b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo_nomina=a.cod_tipo_nomina) as devengado,
		a.numero_recibo
		FROM v_cnmd08_historia_transacciones a
		WHERE ".$condicion." AND a.uso_transaccion!=6 AND a.uso_transaccion!=9 ORDER BY cedula_identidad, numero_recibo";// AND a.ultimo_recibo!=0

		$datos = $this->v_cnmd07_transacciones_actuales->execute($sql);
    
//print_r($datos);
		$this->set('datos',$datos);
}//emision_recibos_pago_historico




/*****  FUNCIONES PARA EL REPORTE DE RECIBOS DE NOMINAS HISTORICOS - POR NOMINAS  ********/

function cnmd08_recibos_pago_historico_nominas($var1=null, $var2=null, $var3=null){
	if($var1==1){
		$this->layout = "ajax";
		$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		$this->concatena($lista, 'lista_nomina');
		$this->Session->delete('tipo_nomina');
	}else if($var1==2){
		$this->layout = "pdf";
	}
	$this->set('opcion', $var1);
}//cnmd08_recibos_pago_historico_nominas


function ver_cod_nomina_recibos($cod_tipo_nomina=null){
	$this->layout="ajax";
	if($cod_tipo_nomina != null){
		$this->Session->write('tipo_nomina', $cod_tipo_nomina);
		$this->Session->write('opcion_buscar_historico', 2);
		$denominacion = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and cod_tipo_nomina='$cod_tipo_nomina'", $order ="cod_tipo_nomina ASC");
		$this->set('denominacion', $denominacion);
	}
	  echo"<script>";
			  echo"document.getElementById('cod_nomina').value='".mascara_tres($cod_tipo_nomina)."';";
	  echo"</script>";
	  /*echo"<script>";
			  echo"document.getElementById('segunda_ventana').disabled=true;";
	  echo"</script>";*/
}//fin function


function ver_ano_nomina_recibos($cod_tipo_nomina=null){
	$this->layout="ajax";
	$rsp=$this->Cnmd01->execute("SELECT DISTINCT ano FROM cnmd08_historia_trabajador  WHERE ". $this->condicion()." and cod_tipo_nomina='".$cod_tipo_nomina."'  ORDER BY ano ASC");
	    foreach($rsp as $lp){
			$vp[]=$lp[0]["ano"];
			$dp[]=$lp[0]["ano"];
		}
		if(isset($vp)){$ano = array_combine($vp, $dp);}else{$ano = array(); }
		$this->set('lista', $ano);
		$this->set('opcion1', $cod_tipo_nomina);
	/*echo"<script>";
			  echo"document.getElementById('segunda_ventana').disabled=true;";
	echo"</script>";*/
}


function ver_numero_nomina_recibos($cod_tipo_nomina=null, $ano=null){
	$this->layout="ajax";
	$opcion_buscar_historico   = $this->Session->read('opcion_buscar_historico');
	if($opcion_buscar_historico==1){
	        $rsp=$this->Cnmd01->execute("SELECT DISTINCT numero_nomina, concepto  FROM cnmd08_historia_nomina  WHERE ". $this->condicion()." and cod_tipo_nomina='".$cod_tipo_nomina."' and ano='".$ano."' ORDER BY numero_nomina ASC");
		    $this->Session->write('ano_nomina', $ano);
		    $ano = array();
			$this->set('lista', $ano);
			/*echo"<script>";
				  echo"document.getElementById('segunda_ventana').disabled=false;";
		    echo"</script>";*/
	}else{
			$rsp=$this->Cnmd01->execute("SELECT DISTINCT numero_nomina, concepto  FROM cnmd08_historia_nomina  WHERE ". $this->condicion()." and cod_tipo_nomina='".$cod_tipo_nomina."' and ano='".$ano."' ORDER BY numero_nomina ASC");
		    $this->Session->write('ano_nomina', $ano);
		    foreach($rsp as $lp){
				$vp[]=$lp[0]["numero_nomina"];
				$dp[]=mascara($lp[0]["numero_nomina"],8)." - ".$lp[0]["concepto"];
			}
			if(isset($vp)){$ano = array_combine($vp, $dp);}else{$ano = array(); }
			$this->set('lista', $ano);
			/*echo"<script>";
				  echo"document.getElementById('segunda_ventana').disabled=true;";
		    echo"</script>";*/
	}
	$this->set('opcion_buscar_historico', $opcion_buscar_historico);

}//fin function


function emision_recibos_pago_historico_nominas(){

		$this->layout="pdf";

		$cod_tipo_nomina = $this->data['cnmp06_diskett_historico']['cod_nomina'];
		$ano = $this->data['cnmp06_diskett_historico']['ano_nomina'];
		$num_nomina = $this->data['cnmp06_diskett_historico']['numero_nomina'];
		$orden_reporte = $this->data['cnmp06_diskett_historico']['ordenado'];

		$cantidad_pago = $this->Cnmd01->execute("SELECT cantidad_pagos FROM cnmd08_historia_nomina  WHERE ". $this->condicion()." and cod_tipo_nomina='".$cod_tipo_nomina."' and ano='".$ano."' and numero_nomina='".$num_nomina."'");
		$cant = $cantidad_pago[0][0]['cantidad_pagos'];
		$this->set('cant_pagos',$cant);

		switch($orden_reporte){
			case '1': $order_by= " a.numero_recibo, a.cedula_identidad"; break;
			case '2': $order_by= " a.cod_dir_superior, a.cod_coordinacion, a.cod_secretaria, a.cod_direccion, a.cod_division, a.cod_departamento, a.cod_oficina, a.cedula_identidad"; break;
			case '3': $order_by= " a.cod_estado, a.cod_municipio, a.cod_parroquia, a.cod_centro, a.cedula_identidad"; break;
			case '4': $order_by= " a.cod_sector, a.cod_programa, a.cod_sub_prog, a.cod_proyecto, a.cod_activ_obra, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.cod_auxiliar, a.cedula_identidad"; break;
			default : $order_by= " a.numero_recibo, a.cedula_identidad"; break;
		}

		$condicion = $this->SQLCA()." AND a.cod_tipo_nomina='$cod_tipo_nomina' AND a.ano='$ano' AND a.numero_nomina='$num_nomina'";

		$sql = "SELECT a.cod_tipo_nomina, a.denominacion_nomina, a.cod_coordinacion, a.deno_cod_coordinacion, a.cod_secretaria, a.deno_cod_secretaria,
		a.cod_direccion, a.deno_cod_direccion, a.cod_division, a.deno_cod_division, a.cod_departamento, a.deno_cod_departamento, a.cod_oficina, a.deno_cod_oficina,
		a.cod_municipio, a.deno_cod_municipio, a.cod_parroquia, a.deno_cod_parroquia, a.cod_centro, a.deno_cod_centro,
		a.cedula_identidad, a.primer_apellido, a.segundo_apellido, a.primer_nombre, a.segundo_nombre, a.cod_puesto, a.denominacion_puesto, a.cod_cargo, a.cod_ficha, a.sueldo_basico,
		a.fecha_ingreso, a.numero_nomina, a.correspondiente, a.periodo_desde, a.periodo_hasta,
		a.cod_tipo_transaccion, a.cod_transaccion, a.deno_transaccion, a.dias_horas, a.frecuencia_cobro, a.monto_cuota, a.saldo, a.ultimo_recibo,
		a.cod_entidad_bancaria, a.deno_entidades_bancarias, a.cod_sucursal, a.cuenta_bancaria, a.uso_transaccion,
		(SELECT b.denominacion_devengado FROM cnmd01 b WHERE b.cod_presi=a.cod_presi AND b.cod_entidad=a.cod_entidad AND b.cod_tipo_inst=a.cod_tipo_inst AND b.cod_inst=a.cod_inst AND b.cod_dep=a.cod_dep AND b.cod_tipo_nomina=a.cod_tipo_nomina) as devengado,
		a.numero_recibo
		FROM v_cnmd08_historia_transacciones a
		WHERE ".$condicion." AND a.uso_transaccion!=6 AND a.uso_transaccion!=9 ORDER BY ".$order_by;// AND a.ultimo_recibo!=0

		$datos = $this->v_cnmd07_transacciones_actuales->execute($sql);
		$this->set('datos',$datos);
}//emision_recibos_pago_historico_nominas




function distribucion_disponibilidad_nomina_historico($var1=null,$var2=null, $var3=null){

	      if($var1==1){ $this->layout = "ajax";
				$lista = $this->Cnmd01->generateList($this->SQLCA()." and status_nomina!=0", 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
				if($this->Cnmd01->findCount($this->SQLCA())!=0){
					$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
					$this->concatena($lista, 'lista_nomina');
					$this->Session->delete('tipo_nomina');
				}else{
					$this->set('tipo_nomina', array());
				}
			    /*
				$condicion = $this->SQLCA();
				$lista = $this->Cnmd01->generateList($condicion, 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
				if($lista!=null){
					$this->concatena($lista, 'lista');
				}else{
					$this->set('lista',array('no'=>'no hay registros'));
				}
				*/

	}else if($var1==2){ $this->layout = "pdf";

		//$tipo_nomina = $this->data['reporte_personal']['select_tiponomina'];
		$tipo_nomina = $this->data["cnmp06_diskett_historico"]["cod_nomina"];
		$ano_nomina = $this->data["cnmp06_diskett_historico"]["ano_nomina"];
		$numero_nomina = $this->data["cnmp06_diskett_historico"]["numero_nomina"];
		$condicion = $this->SQLCA()." and cod_tipo_nomina='$tipo_nomina' and ano='$ano_nomina' and numero_nomina='$numero_nomina'";

		//$datos_tipo_nomina = $this->Cnmd01->execute("SELECT cod_tipo_nomina, denominacion, correspondiente, numero_nomina, periodo_desde, periodo_hasta FROM cnmd01 WHERE ".$this->SQLCA()." and cod_tipo_nomina='$tipo_nomina'");
		//$this->set('datos_tipo_nomina',$datos_tipo_nomina);
		$nomina = $this->v_cnmd08_historia_trans_con->findAll($condicion," DISTINCT numero_nomina, frecuencia_pago, periodo_desde, periodo_hasta, correspondiente", ' numero_nomina ASC');
		$this->set("datos_tipo_nomina", $nomina);

        $data = $this->v_distribucion_disponibilidad_2_historico->findAll($condicion,null, "cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_tipo_transaccion, tipo_orden, cod_transaccion, denominacion");
        $this->set("data", $data);

        $denominacion_nomina = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina='$tipo_nomina'", 'cod_tipo_nomina, denominacion', ' cod_tipo_nomina ASC');
		$this->set("cod_tipo_nomina_reporte", $denominacion_nomina[0]['Cnmd01']['cod_tipo_nomina']);
		$this->set("denominacion_nomina_reporte", $denominacion_nomina[0]['Cnmd01']['denominacion']);

		$this->set("opcion", $var1);


	}//fin else
$this->set("opcion", $var1);
}//fin function


function reporte_aportes_patronales($var1=null, $var2=null, $var3=null){
	if($var1==1){
		$this->layout = "ajax";
		$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		$this->concatena($lista, 'lista_nomina');
		$this->Session->delete('tipo_nomina');
		$consulta_c_t = $this->cnmd03_transaccion->generateList("cod_tipo_transaccion=2 and uso_transaccion=6", 'cod_transaccion ASC', null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.denominacion');
		$this->concatena($consulta_c_t, "lista");

	}else if($var1==2){
		$this->layout = "pdf";
		set_time_limit(0);
		ini_set("memory_limit","2000M");

	    $cod_nomina      = $this->data["reporte_juan_nomina"]["cod_nomina"];
	    $opcion_busqueda = $this->data["reporte_juan_nomina"]["opcion_busqueda"];

		$sql  = $this->condicion()." and cod_tipo_nomina='".$cod_nomina."'   ";
		$sql2 = $this->condicion()." and cod_tipo_nomina='".$cod_nomina."'   ";

		if($opcion_busqueda==4){
			$tipo_transaccion  = 2;//$this->data["reporte_juan_nomina"]["tipo_transaccion"];
			$select_tra        = $this->data["reporte_juan_nomina"]["select_tra"];
			$sql              .= " and cod_tipo_transaccion='".$tipo_transaccion."' and cod_transaccion='".$select_tra."'  ";
		}

		$sql_aportes = "SELECT
					a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep,
					a.cod_tipo_nomina,
					a.denominacion_nomina,
					a.correspondiente,
					a.numero_nomina,
					a.periodo_desde,
					a.periodo_hasta,
					a.cod_cargo,
					a.cod_ficha,
					a.cedula_identidad,
					a.primer_apellido,
					a.segundo_apellido,
					a.primer_nombre,
					a.segundo_nombre,
					a.fecha_transaccion,
					a.cod_tipo_transaccion,
					a.cod_transaccion,
					a.deno_transaccion,
					a.uso_transaccion,
					a.monto_original,
					a.dias_horas,
					a.monto_cuota,
					(select x.monto_cuota from v_cnmd07_transacciones_actuales_condicion_vision_3 x  where x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_inst=a.cod_inst and x.cod_dep=a.cod_dep and x.cod_tipo_nomina=a.cod_tipo_nomina and x.numero_nomina=a.numero_nomina and x.cod_cargo=a.cod_cargo and x.cod_ficha=a.cod_ficha and x.cod_tipo_transaccion=2 and x.cod_transaccion=(select z.cod_transaccion_padre from cnmd03_transacciones z where z.cod_tipo_transaccion=2 and z.cod_transaccion=a.cod_transaccion)) as monto_cuota_trabajador,
					a.saldo,
					a.numero_cuotas_cancelar,
					a.username
				FROM

				v_cnmd07_transacciones_actuales_condicion_vision_3 a where ".$sql." AND a.uso_transaccion=6 ORDER BY cod_tipo_transaccion, cod_transaccion, cedula_identidad, cod_cargo, cod_ficha ASC";

		//$datos      = $this->v_cnmd07_transacciones_actuales_con->findAll($sql." and (condicion_actividad_ficha=1) ", null, "cod_tipo_transaccion, cod_transaccion, cedula_identidad, cod_cargo, cod_ficha ASC ");
		$datos        = $this->v_cnmd07_transacciones_actuales_con->execute($sql_aportes);
		$datos_nomina = $this->Cnmd01->findAll($sql2, null, null);
		$this->set("datos",        $datos);
		$this->set("datos_nomina", $datos_nomina);
		$this->set("opcion_busqueda", $opcion_busqueda);

	}else if($var1==3){
		$this->layout = "ajax";
		echo'<script>';
			echo" document.getElementById('capa_1').style.display = 'none'; ";
			echo" document.getElementById('capa_2').style.display = 'block'; ";
		echo'</script>';
		echo'<script>';
			echo" document.getElementById('datos_a').value = ''; ";
			echo" document.getElementById('datos_b').value = ''; ";
			echo" document.getElementById('select_tra').options[0].value = ''; ";
			echo" document.getElementById('select_tra').options[0].text = ''; ";
		echo'</script>';
	}else if($var1==4){
		$this->layout = "ajax";
		echo'<script>';
			echo" document.getElementById('capa_1').style.display = 'block'; ";
			echo" document.getElementById('capa_2').style.display = 'none'; ";
		echo'</script>';
	}
	$this->set('opcion', $var1);

}// reporte_aportes_patronales


function select_aportes_patronales(){
	$this->layout="ajax";
	//$consulta_c_t = $this->cnmd03_transaccion->generateList($sql, 'cod_transaccion ASC', null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.denominacion');
	//$this->concatena($consulta_c_t, "lista");
}

function reporte_aportes_patronales_historico($var1=null, $var2=null, $var3=null){
	if($var1==1){
		$this->layout = "ajax";
		$lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		$this->concatena($lista, 'lista_nomina');
		$this->Session->delete('tipo_nomina');
		$consulta_c_t = $this->cnmd03_transaccion->generateList("cod_tipo_transaccion=2 and uso_transaccion=6", 'cod_transaccion ASC', null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.denominacion');
		$this->concatena($consulta_c_t, "lista");

	}else if($var1==2){
		$this->layout = "pdf";
		set_time_limit(0);
		ini_set("memory_limit","2000M");

		$cod_nomina       = $this->data['cnmp06_diskett_historico']['cod_nomina'];
		$ano              = $this->data['cnmp06_diskett_historico']['ano_nomina'];
		$num_nomina       = $this->data['cnmp06_diskett_historico']['numero_nomina'];
	    $opcion_busqueda  = $this->data["reporte_juan_nomina"]["opcion_busqueda"];

		if($cod_nomina!='' || $cod_nomina!='' || $cod_nomina!='' || $cod_nomina!=''){

			$sql  = $this->condicion()." and cod_tipo_nomina='".$cod_nomina."' and ano='$ano' and numero_nomina='$num_nomina' ";
			$sql2 = $this->condicion()." and cod_tipo_nomina='".$cod_nomina."' and ano='$ano' and numero_nomina='$num_nomina' ";

			if($opcion_busqueda==4){
				$tipo_transaccion  = 2;// 2.-Deduccion. Son transacciones deductivas.
				$select_tra        = $this->data["reporte_juan_nomina"]["select_tra"];
				$sql              .= " and cod_tipo_transaccion='".$tipo_transaccion."' and cod_transaccion='".$select_tra."'  ";
			}

			$sql_aportes = "SELECT
						a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep,
						a.cod_tipo_nomina,
						a.denominacion_nomina,
						a.correspondiente,
						a.numero_nomina,
						a.periodo_desde,
						a.periodo_hasta,
						a.cod_cargo,
						a.cod_ficha,
						a.cedula_identidad,
						a.primer_apellido,
						a.segundo_apellido,
						a.primer_nombre,
						a.segundo_nombre,
						a.fecha_transaccion,
						a.cod_tipo_transaccion,
						a.cod_transaccion,
						a.deno_transaccion,
						a.uso_transaccion,
						a.monto_original,
						a.dias_horas,
						a.monto_cuota,
						(select x.monto_cuota from v_cnmd08_historia_transacciones_condicion x  where x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_inst=a.cod_inst and x.cod_dep=a.cod_dep and x.cod_tipo_nomina=a.cod_tipo_nomina and x.numero_nomina=a.numero_nomina and x.cod_cargo=a.cod_cargo and x.cod_ficha=a.cod_ficha and x.cod_tipo_transaccion=2 and x.cod_transaccion=(select z.cod_transaccion_padre from cnmd03_transacciones z where z.cod_tipo_transaccion=2 and z.cod_transaccion=a.cod_transaccion)) as monto_cuota_trabajador,
						a.saldo,
						a.numero_cuotas_canceladas
					FROM

					v_cnmd08_historia_transacciones_condicion a where ".$sql." AND a.uso_transaccion=6 ORDER BY cod_tipo_transaccion, cod_transaccion, cedula_identidad, cod_cargo, cod_ficha ASC";

			$datos        = $this->v_cnmd08_historia_trans_con->execute($sql_aportes);
			$this->set("datos",        $datos);
			$this->set("opcion_busqueda", $opcion_busqueda);
			//$datos_nomina = $this->Cnmd01->findAll($sql2, null, null);
			//$this->set("datos_nomina", $datos_nomina);
		}else{
			echo "<script>history.back(-1)</script>";
		}

	}else if($var1==3){
		$this->layout = "ajax";
		echo'<script>';
			echo" document.getElementById('capa_1').style.display = 'none'; ";
			echo" document.getElementById('capa_2').style.display = 'block'; ";
		echo'</script>';
		echo'<script>';
			echo" document.getElementById('datos_a').value = ''; ";
			echo" document.getElementById('datos_b').value = ''; ";
			echo" document.getElementById('select_tra').options[0].value = ''; ";
			echo" document.getElementById('select_tra').options[0].text = ''; ";
		echo'</script>';
	}else if($var1==4){
		$this->layout = "ajax";
		echo'<script>';
			echo" document.getElementById('capa_1').style.display = 'block'; ";
			echo" document.getElementById('capa_2').style.display = 'none'; ";
		echo'</script>';
	}
	$this->set('opcion', $var1);

}// reporte_aportes_patronales_historico


}//fin class
?>