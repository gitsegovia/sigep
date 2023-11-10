<?php


class Cnmp06DiskettHistoricoController extends AppController{

    var $name = "cnmp06_diskett_historico";
    var $uses = array("ccfd04_cierre_mes", "Cnmd01", "cnmd03_transaccion", "v_cnmd07_transacciones_actuales", "v_cnmd07_transacciones_actuales_con",
                      "v_distribucion_asignacion_deduccion", "costo_presupuestario_p2", "v_distribucion_disponibilidad_2", "cnmd08_historia",
                      "cnmd08_historia_sso", "cnmd08_historia_pf", "cnmd08_historia_lph", "cugd02_institucion", "cnmd08_historia_fpj",
                      "cnmd08_historia_pen", "cnmd08_historia_pen_ac", "cnmd08_historia_fpj_ajuste", "cnmd08_historia_lph_ajuste",
                      "cnmd08_historia_sso_ajuste", "cnmd08_historia_pf_ajuste", "cugd02_dependencia", "v_cnmd08_fdj_historia", "cnmd08_historia_fpj_registro");

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




function beforeFilter(){
					$this->checkSession();

}//fin function



 function select_tipo($opcion=null,$var = null){
 	$this->layout ="ajax";
 	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');

 		switch($opcion){
 			case 'codigo':
 				if($var!='agregar'){
 					$this->set('codigo',$var);
 				}else{
 					$this->set('codigo',false);
 				}
 			break;
 			case 'deno':
 				if($var!='agregar'){
	 				$deno = $this->cugd02_dependencia->field('denominacion',"cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$var'", $order ="cod_dependencia ASC");
					$this->set('deno', $deno);
 				}else{
 					$this->set('deno', false);
 				}
 			break;
 		}//fin switch

 }//fin select_tipo


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





function show_ano_nomina($cod_tipo_nomina=null){

	$this->layout="ajax";
	$rsp=$this->Cnmd01->execute("SELECT DISTINCT ano  FROM cnmd08_historia_trabajador  WHERE ". $this->condicion()." and cod_tipo_nomina='".$cod_tipo_nomina."'  ORDER BY ano ASC");
	    foreach($rsp as $lp){
			$vp[]=$lp[0]["ano"];
			$dp[]=$lp[0]["ano"];
		}
		if(isset($vp)){$ano = array_combine($vp, $dp);}else{$ano = array(); }
		$this->set('lista', $ano);
	$this->set('opcion1', $cod_tipo_nomina);

}

function show_ano_nomina_mes($cod_tipo_nomina=null){

	$this->layout="ajax";
	$rsp=$this->Cnmd01->execute("SELECT DISTINCT ano  FROM ztxt_archivo_faov_historico_mensual2  WHERE ". $this->condicion()." and cod_tipo_nomina='".$cod_tipo_nomina."'  ORDER BY ano ASC");
	    foreach($rsp as $lp){
			$vp[]=$lp[0]["ano"];
			$dp[]=$lp[0]["ano"];
		}
		if(isset($vp)){$ano = array_combine($vp, $dp);}else{$ano = array(); }
		$this->set('lista', $ano);
	$this->set('opcion1', $cod_tipo_nomina);

}


function show_numero_nomina($cod_tipo_nomina=null, $ano=null){
	$this->layout="ajax";
	$rsp=$this->Cnmd01->execute("SELECT DISTINCT numero_nomina, concepto  FROM cnmd08_historia_nomina  WHERE ". $this->condicion()." and cod_tipo_nomina='".$cod_tipo_nomina."' and ano='".$ano."' ORDER BY numero_nomina ASC");
	    foreach($rsp as $lp){
			$vp[]=$lp[0]["numero_nomina"];
			$dp[]=mascara($lp[0]["numero_nomina"],8)." - ".$lp[0]["concepto"];
		}
		if(isset($vp)){$ano = array_combine($vp, $dp);}else{$ano = array(); }
		$this->set('lista', $ano);
}

function show_meses_faov($cod_tipo_nomina=null, $ano=null){
	$this->layout="ajax";
	$rsp=$this->Cnmd01->execute("SELECT DISTINCT mes  FROM ztxt_archivo_faov_historico_mensual2  WHERE ". $this->condicion()." and cod_tipo_nomina='".$cod_tipo_nomina."' and ano='".$ano."' ORDER BY mes ASC");
	 $mes[1] = 'Enero';
	 $mes[2] = 'Febrero';
	 $mes[3] = 'Marzo';
	 $mes[4] = 'Abril';
	 $mes[5] = 'Mayo';
	 $mes[6] = 'Junio';
	 $mes[7] = 'Julio';
	 $mes[8] = 'Agosto';
	 $mes[9] = 'Septiembre';
	 $mes[10] = 'Octubre';
	 $mes[11] = 'Noviembre';
	 $mes[12] = 'Diciembre';
	    foreach($rsp as $lp){
	    	$x_mes = (int)$lp[0]["mes"];
			$vp[]=$lp[0]["mes"];
			$dp[]=mascara($lp[0]["mes"],2)." - ".$mes[$x_mes];
		}
		if(isset($vp)){$ano = array_combine($vp, $dp);}else{$ano = array(); }
		$this->set('lista', $ano);
}







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














function diskett_1($var1=null, $var2=null, $var3=null){

       if($var1==1){
           $this->layout = "ajax";

	           $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		       $this->concatena($lista, 'lista_nomina');
		       $this->Session->delete('tipo_nomina');

		       $consulta_c_t = $this->cnmd03_transaccion->generateList("cod_tipo_transaccion=1", 'cod_transaccion ASC', null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.denominacion');
	   	       $this->concatena($consulta_c_t, "lista");



  }else if($var1==2){

  	set_time_limit(0);
	ini_set("memory_limit","2000M");

           $this->layout  = "txt";
           $cod_presi     = $this->Session->read('SScodpresi');
		   $cod_entidad   = $this->Session->read('SScodentidad');
		   $cod_tipo_inst = $this->Session->read('SScodtipoinst');
		   $cod_inst      = $this->Session->read('SScodinst');
		   $cod_dep       = $this->Session->read('SScoddep');
                $cod_nomina        = $this->data["cnmp06_diskett_historico"]["cod_nomina"];

                $ano_nomina        = $this->data["cnmp06_diskett_historico"]["ano_nomina"];
                $numero_nomina     = $this->data["cnmp06_diskett_historico"]["numero_nomina"];

                $tipo_transaccion  = $this->data["cnmp06_diskett_historico"]["tipo_transaccion"];
	            $select_tra        = $this->data["cnmp06_diskett_historico"]["select_tra"];
                $sql               = $this->condicion()." and cod_tipo_nomina='".$cod_nomina."'  and cod_tipo_transaccion='".$tipo_transaccion."' and cod_transaccion='".$select_tra."'  ";
	       $nombre_archivo         = 'Nomina_'.mascara($cod_dep,4).'_'.mascara($cod_nomina,3).'_'.date('d_m_Y_h:i:sa').'';
	       $_SESSION["nombre_txt"] = $nombre_archivo.".txt";
	       $data3          = $this->cnmd08_historia->findAll($sql." and ano='".$ano_nomina."' and numero_nomina='".$numero_nomina."'  ",null, "cedula_identidad ASC");
	       $filas_archivo  = "";
		   foreach($data3 as $rsdata){

              $cod_tipo_nomina  = mascara($rsdata["cnmd08_historia"]["cod_tipo_nomina"], 3);
              $cedula_identidad = mascara($rsdata["cnmd08_historia"]["cedula_identidad"],9);

              $nombre_completo   = $rsdata["cnmd08_historia"]["primer_nombre"]." ".$rsdata["cnmd08_historia"]["segundo_nombre"]." ";
              $nombre_completo  .= $rsdata["cnmd08_historia"]["primer_apellido"]." ".$rsdata["cnmd08_historia"]["segundo_apellido"];
              $nombre_completo   = str_replace('  ',' ',trim($nombre_completo));
			  $nombre_completo   = str_replace("\t",' ',$nombre_completo);
			  $nombre_completo   = str_replace("Ñ",'@',$nombre_completo);
			  $nombre            = cortar_cadena_diskette(elimina_acentos($nombre_completo), 30);
			  $nombre            = str_replace("@",'Ñ',$nombre);

			    $neto_aux = explode('.',$rsdata["cnmd08_historia"]["monto_cuota"]);
				if(isset($neto_aux[1])){
					$decimal=$neto_aux[1];
				}else{
					$decimal=0;
				}
				$decimal             = str_pad($decimal, 2 , "0", STR_PAD_RIGHT).'';
				$neto_cobrar_1       = $neto_aux[0].''.$decimal;
				$monto_cuota         = mascara($neto_cobrar_1,9);
				$ceros_constantes_9  = mascara(0,9);
                $fecha_transaccion   = cambia_fecha($rsdata["cnmd08_historia"]["fecha_transaccion"]);
//                $fecha_transaccion   = str_replace('/','',cambia_fecha($rsdata["cnmd08_historia"]["fecha_transaccion"]));
				$ceros_constantes_10 = mascara(0,10);

                $filas_archivo .= $cod_tipo_nomina.$cedula_identidad.$nombre.$monto_cuota.$ceros_constantes_9.$fecha_transaccion.$ceros_constantes_10."\n";

			}
		$this->wFile($nombre_archivo, $filas_archivo);
		$this->set('filas_archivo', $filas_archivo);





 }//fin else




$this->set('opcion', $var1);



}//fin function




























function diskett_2($var1=null, $var2=null, $var3=null){

       if($var1==1){
           $this->layout = "ajax";

	           $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		       $this->concatena($lista, 'lista_nomina');
		       $this->Session->delete('tipo_nomina');

		       $consulta_c_t = $this->cnmd03_transaccion->generateList("cod_tipo_transaccion=1", 'cod_transaccion ASC', null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.denominacion');
	   	       $this->concatena($consulta_c_t, "lista");



  }else if($var1==2){

  	set_time_limit(0);
	ini_set("memory_limit","2000M");

           $this->layout  = "txt";
           $cod_presi     = $this->Session->read('SScodpresi');
		   $cod_entidad   = $this->Session->read('SScodentidad');
		   $cod_tipo_inst = $this->Session->read('SScodtipoinst');
		   $cod_inst      = $this->Session->read('SScodinst');
		   $cod_dep       = $this->Session->read('SScoddep');
                $cod_nomina        = $this->data["cnmp06_diskett_historico"]["cod_nomina"];
                $ano_nomina        = $this->data["cnmp06_diskett_historico"]["ano_nomina"];
                $numero_nomina     = $this->data["cnmp06_diskett_historico"]["numero_nomina"];
                $sql               = $this->condicion()." and cod_tipo_nomina='".$cod_nomina."'  ";

                if($this->data["cnmp06_diskett_historico"]["tipo_deduccion"]==1){
                	$modelo = "cnmd08_historia_sso";
                }else{
                	$modelo = "cnmd08_historia_sso_ajuste";
                }

	       $nombre_archivo         = 'Nomina_'.mascara($cod_dep,4).'_'.mascara($cod_nomina,3).'_'.date('d_m_Y_h:i:sa').'';
	       $_SESSION["nombre_txt"] = $nombre_archivo.".txt";
	       $data3          = $this->$modelo->findAll($sql." and ano='".$ano_nomina."' and numero_nomina='".$numero_nomina."' and monto_aporte_persona!=0 and monto_aporte_patrono!=0  ",null, "cedula_identidad ASC");
	       $filas_archivo  = "";
		   foreach($data3 as $rsdata){

              $nacionalidad     = strtoupper_sisap($rsdata[$modelo]["nacionalidad"]);
              $cedula_identidad = mascara($rsdata[$modelo]["cedula_identidad"],10);

              $nombre_completo   = $rsdata[$modelo]["primer_nombre"]." ".$rsdata[$modelo]["segundo_nombre"]." ";
              $nombre_completo  .= $rsdata[$modelo]["primer_apellido"]." ".$rsdata[$modelo]["segundo_apellido"];
              $nombre_completo   = str_replace('  ',' ',trim($nombre_completo));
			  $nombre_completo   = str_replace("\t",' ',$nombre_completo);
			  $nombre_completo   = str_replace("Ñ",'@',$nombre_completo);
			  $nombre            = cortar_cadena_diskette(elimina_acentos($nombre_completo), 31);
			  $nombre            = str_replace("@",'Ñ',$nombre);

			    $neto_aux = explode('.',$rsdata[$modelo]["monto_aporte_patrono"]);
				if(isset($neto_aux[1])){
					$decimal=$neto_aux[1];
				}else{
					$decimal=0;
				}
				$decimal               = str_pad($decimal, 2 , "0", STR_PAD_RIGHT).'';
				$neto_cobrar_1         = $neto_aux[0].'.'.$decimal;
				$monto_aporte_patrono  = mascara($neto_cobrar_1,10);

				$neto_aux = explode('.',$rsdata[$modelo]["monto_aporte_persona"]);
				if(isset($neto_aux[1])){
					$decimal=$neto_aux[1];
				}else{
					$decimal=0;
				}
				$decimal               = str_pad($decimal, 2 , "0", STR_PAD_RIGHT).'';
				$neto_cobrar_1         = $neto_aux[0].'.'.$decimal;
				$monto_aporte_persona  = mascara($neto_cobrar_1,10);

              $filas_archivo .= $nacionalidad.$cedula_identidad.$nombre.$monto_aporte_patrono." ".$monto_aporte_persona."\n";

			}
		$this->wFile($nombre_archivo, $filas_archivo);
		$this->set('filas_archivo', $filas_archivo);





 }//fin else




$this->set('opcion', $var1);



}//fin function













function diskett_3($var1=null, $var2=null, $var3=null){

       if($var1==1){
           $this->layout = "ajax";

	           $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		       $this->concatena($lista, 'lista_nomina');
		       $this->Session->delete('tipo_nomina');

		       $consulta_c_t = $this->cnmd03_transaccion->generateList("cod_tipo_transaccion=1", 'cod_transaccion ASC', null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.denominacion');
	   	       $this->concatena($consulta_c_t, "lista");



  }else if($var1==2){

  	set_time_limit(0);
	ini_set("memory_limit","2000M");

           $this->layout  = "txt";
           $cod_presi     = $this->Session->read('SScodpresi');
		   $cod_entidad   = $this->Session->read('SScodentidad');
		   $cod_tipo_inst = $this->Session->read('SScodtipoinst');
		   $cod_inst      = $this->Session->read('SScodinst');
		   $cod_dep       = $this->Session->read('SScoddep');
                $cod_nomina        = $this->data["cnmp06_diskett_historico"]["cod_nomina"];
                $ano_nomina        = $this->data["cnmp06_diskett_historico"]["ano_nomina"];
                $numero_nomina     = $this->data["cnmp06_diskett_historico"]["numero_nomina"];
                $sql               = $this->condicion()." and cod_tipo_nomina='".$cod_nomina."'  ";

                if($this->data["cnmp06_diskett_historico"]["tipo_deduccion"]==1){
                	$modelo = "cnmd08_historia_pf";
                }else{
                	$modelo = "cnmd08_historia_pf_ajuste";
                }

	       $nombre_archivo         = 'Nomina_'.mascara($cod_dep,4).'_'.mascara($cod_nomina,3).'_'.date('d_m_Y_h:i:sa').'';
	       $_SESSION["nombre_txt"] = $nombre_archivo.".txt";
	       $data3          = $this->$modelo->findAll($sql." and ano='".$ano_nomina."' and numero_nomina='".$numero_nomina."' and monto_aporte_persona!=0 and monto_aporte_patrono!=0  ",null, "cedula_identidad ASC");
	       $filas_archivo  = "";
		   foreach($data3 as $rsdata){

              $nacionalidad     = strtoupper_sisap($rsdata[$modelo]["nacionalidad"]);
              $cedula_identidad = mascara($rsdata[$modelo]["cedula_identidad"],10);

              $nombre_completo   = $rsdata[$modelo]["primer_nombre"]." ".$rsdata[$modelo]["segundo_nombre"]." ";
              $nombre_completo  .= $rsdata[$modelo]["primer_apellido"]." ".$rsdata[$modelo]["segundo_apellido"];
              $nombre_completo   = str_replace('  ',' ',trim($nombre_completo));
			  $nombre_completo   = str_replace("\t",' ',$nombre_completo);
			  $nombre_completo   = str_replace("Ñ",'@',$nombre_completo);
			  $nombre            = cortar_cadena_diskette(elimina_acentos($nombre_completo), 31);
			  $nombre            = str_replace("@",'Ñ',$nombre);

			    $neto_aux = explode('.',$rsdata[$modelo]["monto_aporte_patrono"]);
				if(isset($neto_aux[1])){
					$decimal=$neto_aux[1];
				}else{
					$decimal=0;
				}
				$decimal               = str_pad($decimal, 2 , "0", STR_PAD_RIGHT).'';
				$neto_cobrar_1         = $neto_aux[0].'.'.$decimal;
				$monto_aporte_patrono  = mascara($neto_cobrar_1,10);

				$neto_aux = explode('.',$rsdata[$modelo]["monto_aporte_persona"]);
				if(isset($neto_aux[1])){
					$decimal=$neto_aux[1];
				}else{
					$decimal=0;
				}
				$decimal               = str_pad($decimal, 2 , "0", STR_PAD_RIGHT).'';
				$neto_cobrar_1         = $neto_aux[0].'.'.$decimal;
				$monto_aporte_persona  = mascara($neto_cobrar_1,10);

              $filas_archivo .= $nacionalidad.$cedula_identidad.$nombre.$monto_aporte_patrono." ".$monto_aporte_persona."\n";

			}
		$this->wFile($nombre_archivo, $filas_archivo);
		$this->set('filas_archivo', $filas_archivo);





 }//fin else




$this->set('opcion', $var1);



}//fin function












function diskett_4($var1=null, $var2=null, $var3=null){

       if($var1==1){
           $this->layout = "ajax";

	           $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		       $this->concatena($lista, 'lista_nomina');
		       $this->Session->delete('tipo_nomina');

		       $consulta_c_t = $this->cnmd03_transaccion->generateList("cod_tipo_transaccion=1", 'cod_transaccion ASC', null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.denominacion');
	   	       $this->concatena($consulta_c_t, "lista");



  }else if($var1==2){

  	set_time_limit(0);
	ini_set("memory_limit","2000M");

           $this->layout  = "txt";
           $cod_presi     = $this->Session->read('SScodpresi');
		   $cod_entidad   = $this->Session->read('SScodentidad');
		   $cod_tipo_inst = $this->Session->read('SScodtipoinst');
		   $cod_inst      = $this->Session->read('SScodinst');
		   $cod_dep       = $this->Session->read('SScoddep');
                $cod_nomina        = $this->data["cnmp06_diskett_historico"]["cod_nomina"];
                $ano_nomina        = $this->data["cnmp06_diskett_historico"]["ano_nomina"];
                $numero_nomina     = $this->data["cnmp06_diskett_historico"]["numero_nomina"];
                $sql               = $this->condicion()." and cod_tipo_nomina='".$cod_nomina."'  ";

                if($this->data["cnmp06_diskett_historico"]["tipo_deduccion"]==1){
                	$modelo = "cnmd08_historia_lph";
                }else{
                	$modelo = "cnmd08_historia_lph_ajuste";
                }

	       $nombre_archivo         = 'Nomina_'.mascara($cod_dep,4).'_'.mascara($cod_nomina,3).'_'.date('d_m_Y_h:i:sa').'';
	       $_SESSION["nombre_txt"] = $nombre_archivo.".txt";
	       $data3                  = $this->$modelo->findAll($sql." and ano='".$ano_nomina."' and numero_nomina='".$numero_nomina."'  and monto_aporte_persona!=0 and monto_aporte_patrono!=0 ",null, "cedula_identidad ASC");
	       $data_institucion       = $this->cugd02_institucion->findAll("cod_tipo_institucion='".$cod_tipo_inst."' and cod_institucion='".$cod_inst."'  ");
	       $data_institucion_rif   = $data_institucion[0]["cugd02_institucion"]["rif"];

	       $filas_archivo   = "";
	       $filas_archivo2  = "000900015510409".$data_institucion_rif;
	       $filas_archivo2  =  str_replace('-','',$filas_archivo2);
	       $cantidad_p      = 0;
	       $total_aporte_p  = 0;
	       $total_aporte_t  = 0;
		   foreach($data3 as $rsdata){
              $nacionalidad      = strtoupper_sisap($rsdata[$modelo]["nacionalidad"]);
              $cedula_identidad  = mascara($rsdata[$modelo]["cedula_identidad"],8);
              $nombre_completo   = $rsdata[$modelo]["primer_nombre"]." ".$rsdata[$modelo]["segundo_nombre"]." ";
              $nombre_completo  .= $rsdata[$modelo]["primer_apellido"]." ".$rsdata[$modelo]["segundo_apellido"];
              $nombre_completo   = str_replace('  ',' ',trim($nombre_completo));
			  $nombre_completo   = str_replace("\t",' ',$nombre_completo);
			  $nombre_completo   = str_replace("Ñ",'@',$nombre_completo);
			  $nombre            = cortar_cadena_diskette(elimina_acentos($nombre_completo), 40);
			  $nombre            = str_replace("@",'Ñ',$nombre);
			    $neto_aux = explode('.',$rsdata[$modelo]["monto_aporte_patrono"]);
				if(isset($neto_aux[1])){
					$decimal=$neto_aux[1];
				}else{
					$decimal=0;
				}
				$decimal               = str_pad($decimal, 2 , "0", STR_PAD_RIGHT).'';
				$neto_cobrar_1         = $neto_aux[0].''.$decimal;
				$monto_aporte_patrono  = mascara($neto_cobrar_1,9);
				$neto_aux = explode('.',$rsdata[$modelo]["monto_aporte_persona"]);
				if(isset($neto_aux[1])){
					$decimal=$neto_aux[1];
				}else{
					$decimal=0;
				}
				$decimal               =  str_pad($decimal, 2 , "0", STR_PAD_RIGHT).'';
				$neto_cobrar_1         =  $neto_aux[0].''.$decimal;
				$monto_aporte_persona  =  mascara($neto_cobrar_1,9);
				$sexo                  =  strtoupper_sisap($rsdata[$modelo]["sexo"]);
//				$fecha_nacimiento      =  str_replace('/','',cambia_fecha($rsdata[$modelo]["fecha_nacimiento"]));
//				$fecha_ingreso         =  str_replace('/','',cambia_fecha($rsdata[$modelo]["fecha_ingreso"]));
				$fecha_nacimiento      =  cambia_fecha($rsdata[$modelo]["fecha_nacimiento"]);
				$fecha_ingreso         =  cambia_fecha($rsdata[$modelo]["fecha_ingreso"]);

                $cantidad_p++;
		        $total_aporte_p   += $rsdata[$modelo]["monto_aporte_patrono"];
		        $total_aporte_t   += $rsdata[$modelo]["monto_aporte_persona"];
		        $fecha_transaccion = str_replace('-','',$rsdata[$modelo]["periodo_hasta"]);

                $filas_archivo        .= "01".$nacionalidad.$cedula_identidad.$monto_aporte_patrono.$monto_aporte_persona."I".$nombre.$sexo.$fecha_nacimiento.$fecha_ingreso."\n";
			}


			    $neto_aux = explode('.',$total_aporte_p);
				if(isset($neto_aux[1])){
					$decimal=$neto_aux[1];
				}else{
					$decimal=0;
				}
				$decimal             = str_pad($decimal, 2 , "0", STR_PAD_RIGHT).'';
				$neto_cobrar_1       = $neto_aux[0].''.$decimal;
				$total_aporte_p      = mascara($neto_cobrar_1,13);

				$neto_aux = explode('.',$total_aporte_t);
				if(isset($neto_aux[1])){
					$decimal=$neto_aux[1];
				}else{
					$decimal=0;
				}
				$decimal               =  str_pad($decimal, 2 , "0", STR_PAD_RIGHT).'';
				$neto_cobrar_1         =  $neto_aux[0].''.$decimal;
				$total_aporte_t        =  mascara($neto_cobrar_1,13);

				$fecha_transaccion_2   = $fecha_transaccion[0].$fecha_transaccion[1].$fecha_transaccion[2].$fecha_transaccion[3].$fecha_transaccion[4].$fecha_transaccion[5];



	       /*
			        000900015510409 G200001542 200912 0000017 0000000292104 0000000146060P
					000900015510409 constante
					G200001542 rif institucion
					200912 año y mes
					0000017 cantidad de personas
					0000000292104 monto total del aprte patronal (13)
					0000000146060 monto total del aprte del trabajador (13)
					P constante
	       */

	       $filas_archivo2  .= $fecha_transaccion_2.mascara($cantidad_p,7).$total_aporte_p.$total_aporte_t."P\n";
           $filas_archivo    = $filas_archivo2.$filas_archivo;

		$this->wFile($nombre_archivo, $filas_archivo);
		$this->set('filas_archivo', $filas_archivo);





 }//fin else




$this->set('opcion', $var1);



}//fin function















function diskett_5($var1=null, $var2=null, $var3=null){

       if($var1==1){
           $this->layout = "ajax";

	           $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		       $this->concatena($lista, 'lista_nomina');
		       $this->Session->delete('tipo_nomina');

		       $consulta_c_t = $this->cnmd03_transaccion->generateList("cod_tipo_transaccion=1", 'cod_transaccion ASC', null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.denominacion');
	   	       $this->concatena($consulta_c_t, "lista");



  }else if($var1==2){

  	set_time_limit(0);
	ini_set("memory_limit","2000M");

           $this->layout  = "txt";
           $cod_presi     = $this->Session->read('SScodpresi');
		   $cod_entidad   = $this->Session->read('SScodentidad');
		   $cod_tipo_inst = $this->Session->read('SScodtipoinst');
		   $cod_inst      = $this->Session->read('SScodinst');
		   $cod_dep       = $this->Session->read('SScoddep');
                $cod_nomina        = $this->data["cnmp06_diskett_historico"]["cod_nomina"];
                $ano_nomina        = $this->data["cnmp06_diskett_historico"]["ano_nomina"];
                $numero_nomina     = $this->data["cnmp06_diskett_historico"]["numero_nomina"];
                $sql               = $this->condicion()." and cod_tipo_nomina='".$cod_nomina."'  ";

                if($this->data["cnmp06_diskett_historico"]["tipo_deduccion"]==1){
                	$modelo = "cnmd08_historia_fpj";
                }else{
                	$modelo = "cnmd08_historia_fpj_ajuste";
                }
 
	       $nombre_archivo         = 'O'.date("d") . date("m") .  date("Y").'0';
	       $_SESSION["nombre_txt"] = $nombre_archivo.".txt";
	       $data3                  = $this->$modelo->findAll($sql." and ano='".$ano_nomina."' and numero_nomina='".$numero_nomina."' and monto_aporte_persona!=0 and monto_aporte_patrono!=0 ",null, "cedula_identidad ASC");
	       $filas_archivo   = "";

		   foreach($data3 as $rsdata){
              $cedula_identidad  = $rsdata[$modelo]["cedula_identidad"]; // mascara($rsdata[$modelo]["cedula_identidad"],10);
              $nombre_completo   = $rsdata[$modelo]["primer_nombre"]." ".$rsdata[$modelo]["segundo_nombre"]." ";
              $nombre_completo  .= $rsdata[$modelo]["primer_apellido"]." ".$rsdata[$modelo]["segundo_apellido"];
              $nombre_completo   = str_replace('  ',' ',trim($nombre_completo));
			  $nombre_completo   = str_replace("\t",' ',$nombre_completo);
			  $nombre_completo   = str_replace("Ñ",'@',$nombre_completo);
			  $nombre            = cortar_cadena_diskette(elimina_acentos($nombre_completo), 30);
			  $nombre            = str_replace("@",'Ñ',$nombre);

			    $neto_aux = explode('.',$rsdata[$modelo]["monto_aporte_patrono"]);
				if(isset($neto_aux[1])){
					$decimal=$neto_aux[1];
				}else{
					$decimal=0;
				}
				$decimal               = str_pad($decimal, 2 , "0", STR_PAD_RIGHT).'';
				$neto_cobrar_1         = $neto_aux[0].','.$decimal;
				$monto_aporte_patrono  = $neto_cobrar_1; // mascara($neto_cobrar_1,10);

				$neto_aux = explode('.',$rsdata[$modelo]["monto_aporte_persona"]);
				if(isset($neto_aux[1])){
					$decimal=$neto_aux[1];
				}else{
					$decimal=0;
				}
				$decimal               =  str_pad($decimal, 2 , "0", STR_PAD_RIGHT).'';
				$neto_cobrar_1         =  $neto_aux[0].','.$decimal;
				$monto_aporte_persona  =  $neto_cobrar_1; // mascara($neto_cobrar_1,10);


				$neto_aux = explode('.',$rsdata[$modelo]["sueldo_basico"]);
				if(isset($neto_aux[1])){
					$decimal=$neto_aux[1];
				}else{
					$decimal=0;
				}

				$decimal               =  str_pad($decimal, 2 , "0", STR_PAD_RIGHT).'';
				$neto_cobrar_1         =  $neto_aux[0].','.$decimal;
				$sueldo_basico         =  $neto_cobrar_1; // mascara($neto_cobrar_1,10);

				$periodo_hasta         =  str_replace('/','',cambia_fecha($rsdata[$modelo]["periodo_hasta"]));
				//$periodo_hasta         = cambia_fecha($rsdata[$modelo]["periodo_hasta"]);

		   if ($cod_presi==1 && $cod_entidad==11 && $cod_tipo_inst==30 && $cod_inst==11 && $cod_dep==50){
				$filas_archivo         .=  $cedula_identidad.'|'.$periodo_hasta.'|'.$nombre.'|11142401|'.$sueldo_basico.'|'.$monto_aporte_patrono.'|'.$monto_aporte_persona."\n";
		   }else{
		   		$filas_archivo         .=  $cedula_identidad.'|'.$periodo_hasta.'|'.$nombre.'|11141601|'.$sueldo_basico.'|'.$monto_aporte_patrono.'|'.$monto_aporte_persona."\n";
		   }

			}


		$this->wFile("juan", $filas_archivo);
		$this->set('filas_archivo', $filas_archivo);





 }//fin else




$this->set('opcion', $var1);



}//fin function


function diskett_5_2($var1=null, $var2=null, $var3=null){
	if($var1==1){
        $this->layout = "ajax";
        $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		       $this->concatena($lista, 'lista_nomina');
		       $this->Session->delete('tipo_nomina');

		$consulta_c_t = $this->cnmd03_transaccion->generateList("cod_tipo_transaccion=1", 'cod_transaccion ASC', null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.denominacion');
	   	       $this->concatena($consulta_c_t, "lista");
	}else if($var1==2){
	  	set_time_limit(0);
		ini_set("memory_limit","2000M");

        $this->layout  = "txt";
        $cod_presi     = $this->Session->read('SScodpresi');
	    $cod_entidad   = $this->Session->read('SScodentidad');
		$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		$cod_inst      = $this->Session->read('SScodinst');
		$cod_dep       = $this->Session->read('SScoddep');
        $cod_nomina        = $this->data["cnmp06_diskett_historico"]["cod_nomina"];
        $ano_nomina        = $this->data["cnmp06_diskett_historico"]["ano_nomina"];
        $numero_nomina     = $this->data["cnmp06_diskett_historico"]["numero_nomina"];
        $sql               = $this->condicion()." and cod_tipo_nomina='".$cod_nomina."'  ";

        if($this->data["cnmp06_diskett_historico"]["tipo_deduccion"]==1){
        	$modelo = "cnmd08_historia_fpj_registro";
        }else{
        	$modelo = "cnmd08_historia_fpj_registro";
        }
 
		$nombre_archivo         = 'O'.date("d") . date("m") .  date("Y").'0';
		$_SESSION["nombre_txt"] = $nombre_archivo.".txt";
		$data3                  = $this->$modelo->findAll($sql." and ano='".$ano_nomina."' and numero_nomina='".$numero_nomina."' and monto_aporte_persona!=0 and monto_aporte_patrono!=0 ",null, "cedula_identidad ASC");
		$filas_archivo   = "";
		foreach($data3 as $rsdata){
			
			$nacionalidad      = $rsdata[$modelo]["nacionalidad"];
			$cedula_identidad  = $rsdata[$modelo]["cedula_identidad"]; // mascara($rsdata[$modelo]["cedula_identidad"],10);
			$primer_nombre     = str_replace("|", "", limpiar_datosPersonales($rsdata[$modelo]["primer_nombre"]));
			$primer_apellido   = str_replace("|", "", limpiar_datosPersonales($rsdata[$modelo]["primer_apellido"]));
			$segundo_nombre    = str_replace("|", "", limpiar_datosPersonales($rsdata[$modelo]["segundo_nombre"]));
			$segundo_apellido  = str_replace("|", "", limpiar_datosPersonales($rsdata[$modelo]["segundo_apellido"]));
			$genero            = $rsdata[$modelo]["sexo"];
			$fecha_nacimiento  = cambia_fecha($rsdata[$modelo]["fecha_nacimiento"]);
//			$fecha_nacimiento  = '';
			if($rsdata[$modelo]["estado_civil"]=='O'){
				$estado_civil      = 'S';
			}else{
				$estado_civil      = $rsdata[$modelo]["estado_civil"];
			}
			$corre_inst        = ''; // opcional
			if ($rsdata[$modelo]["correo_electronico"]=='0'){
				$corre_personal    = '';
			}else{
				$corre_personal    = str_replace("|", "", $rsdata[$modelo]["correo_electronico"]);
			}
			
			$estado            = '11'; //tabla del formato
			$municipio         = '134'; //tabla del formato
			$parroquia         = '432'; //tabla del formato
			if($rsdata[$modelo]["direccion_habitacion"]=='')
			{
				$direccion     = 'Av. Romulo Gallegos Diagonal Secretaria de Educacion, San Juan de los Morros Estado Guárico';
			}else{
				$direccion     = str_replace("|", "", $rsdata[$modelo]["direccion_habitacion"]);
			}
			$tlf_principal     = str_replace("-", "", $rsdata[$modelo]["telefonos_habitacion"]);
			$tlf_movil         = '';
			$tlf_movil         = '02464316110'; // TELEFONO DE RECURSOS HUMANOS


			switch ($rsdata[$modelo]["cod_tipo_nomina"]) {
				case 1:
					$tipo_trabajador   = '50'; //tabla del formato
					$categoria         = '301'; //tabla del formato
					break;
				case 2:
					$tipo_trabajador   = '50'; //tabla del formato
					$categoria         = '302'; //tabla del formato
					break;
				case 3:
					$tipo_trabajador   = '50'; //tabla del formato
					$categoria         = '303'; //tabla del formato
					break;
				case 4:
					$tipo_trabajador   = '50'; //tabla del formato
					$categoria         = '303'; //tabla del formato
					break;
				case 5:
					$tipo_trabajador   = '50'; //tabla del formato
					$categoria         = '303'; //tabla del formato
					break;
				case 6:
					$tipo_trabajador   = '50'; //tabla del formato
					$categoria         = '305'; //tabla del formato
					break;
				case 8:
					$tipo_trabajador   = '50'; //tabla del formato
					$categoria         = '302'; //tabla del formato
					break;
				case 9:
					$tipo_trabajador   = '51'; //tabla del formato
					$categoria         = '311'; //tabla del formato
					break;
				case 21:
					$tipo_trabajador   = '51'; //tabla del formato
					$categoria         = '312'; //tabla del formato
					break;
				case 22:
					$tipo_trabajador   = '51'; //tabla del formato
					$categoria         = '311'; //tabla del formato
					break;
				case 23:
					$tipo_trabajador   = '51'; //tabla del formato
					$categoria         = '311'; //tabla del formato
					break;
				case 24:
					$tipo_trabajador   = '51'; //tabla del formato
					$categoria         = '312'; //tabla del formato
					break;
				case 26:
					$tipo_trabajador   = '51'; //tabla del formato
					$categoria         = '312'; //tabla del formato
					break;
				case 27:
					$tipo_trabajador   = '51'; //tabla del formato
					$categoria         = '311'; //tabla del formato
					break;
				case 34:
					$tipo_trabajador   = '50'; //tabla del formato
					$categoria         = '303'; //tabla del formato
					break;
				case 41:
					$tipo_trabajador   = '50'; //tabla del formato
					$categoria         = '301'; //tabla del formato
					break;
				default:
					$tipo_trabajador   = ''; //tabla del formato
					$categoria         = ''; //tabla del formato
					break;
			}

			$cargo             = str_replace("|", "", $rsdata[$modelo]["cargo"]);
			$fecha_ingreso_adm = cambia_fecha($rsdata[$modelo]["fecha_ingreso"]);
			$fecha_ingreso_ent = cambia_fecha($rsdata[$modelo]["fecha_ingreso"]);

			$neto_aux = explode('.',$rsdata[$modelo]["sueldo_basico"]);
			if(isset($neto_aux[1])){
				$decimal=$neto_aux[1];
			}else{
				$decimal=0;
			}

			$decimal               =  str_pad($decimal, 2 , "0", STR_PAD_RIGHT).'';
			$neto_cobrar_1         =  $neto_aux[0].','.$decimal;
			$sueldo_mensual        =  $neto_cobrar_1;
			$sueldo_normal         =  $neto_cobrar_1;
			$prima_antiguedad      =  "0,00";
			$prima_eficiencia      =  "0,00";

			$filas_archivo        .=  $nacionalidad."|".$cedula_identidad."|".$primer_nombre."|".$primer_apellido."|".$segundo_nombre."|".$segundo_apellido."|".$genero."|".$fecha_nacimiento."|".$estado_civil."|".$corre_inst."|".$corre_personal."|".$estado."|".$municipio."|".$parroquia."|".$direccion."|".$tlf_principal."|".$tlf_movil."|".$tlf_movil."|".$tipo_trabajador."|".$categoria."|".$cargo."|".$fecha_ingreso_adm."|".$fecha_ingreso_ent."|".$sueldo_mensual."|".$sueldo_normal."|".$prima_antiguedad."|".$prima_eficiencia."\n";
/*
			$filas_archivo        .=  $nacionalidad."|".$cedula_identidad."|".$primer_nombre."|".$segundo_nombre."|".$primer_apellido."|".$segundo_apellido."|".$genero."|".$fecha_nacimiento."|".$estado_civil."|".$corre_inst."|".$corre_personal."|".$estado."|".$municipio."|".$parroquia."|".$direccion."|".$tlf_principal."|".$tlf_movil."|".$tlf_movil."\n";
*/

		}
		$this->wFile("juan", $filas_archivo);
		$this->set('filas_archivo', $filas_archivo);

	}//fin else

	$this->set('opcion', $var1);
}//fin function
























function diskett_6($var1=null, $var2=null, $var3=null){

       if($var1==1){
           $this->layout = "ajax";

	           $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		       $this->concatena($lista, 'lista_nomina');
		       $this->Session->delete('tipo_nomina');

		       $consulta_c_t = $this->cnmd03_transaccion->generateList("cod_tipo_transaccion=1", 'cod_transaccion ASC', null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.denominacion');
	   	       $this->concatena($consulta_c_t, "lista");



  }else if($var1==2){

  	set_time_limit(0);
	ini_set("memory_limit","2000M");

           $this->layout  = "txt";
           $cod_presi     = $this->Session->read('SScodpresi');
		   $cod_entidad   = $this->Session->read('SScodentidad');
		   $cod_tipo_inst = $this->Session->read('SScodtipoinst');
		   $cod_inst      = $this->Session->read('SScodinst');
		   $cod_dep       = $this->Session->read('SScoddep');
                $cod_nomina                    = $this->data["cnmp06_diskett_historico"]["cod_nomina"];
                $ano_nomina                    = $this->data["cnmp06_diskett_historico"]["ano_nomina"];
                $numero_nomina                 = $this->data["cnmp06_diskett_historico"]["numero_nomina"];
                $condicion_actividad_ficha     = $this->data["cnmp06_diskett_historico"]["condicion_actividad_ficha"];

                $sql               = $this->condicion()." and cod_tipo_nomina='".$cod_nomina."'  ";
	       $nombre_archivo         = 'Nomina_'.mascara($cod_dep,4).'_'.mascara($cod_nomina,3).'_'.date('d_m_Y_h:i:sa').'';
	       $_SESSION["nombre_txt"] = $nombre_archivo.".txt";
	       $data3                  = $this->cnmd08_historia_pen->findAll($sql." and ano='".$ano_nomina."' and numero_nomina='".$numero_nomina."'  and condicion_actividad_ficha='".$condicion_actividad_ficha."'  ",null, "cedula_identidad ASC");
	       $filas_archivo   = "";
//pr($data3);
		   foreach($data3 as $rsdata){
		   	  $estado_civil  = $rsdata["cnmd08_historia_pen"]["estado_civil"];
		   	  $nacionalidad  = $rsdata["cnmd08_historia_pen"]["nacionalidad"];

              $cedula_identidad  = mascara($rsdata["cnmd08_historia_pen"]["cedula_identidad"],10);
              /*
              $nombre_completo   = $rsdata["cnmd08_historia_pen"]["primer_nombre"]." ".$rsdata["cnmd08_historia_pen"]["segundo_nombre"]." ";
              $nombre_completo  .= $rsdata["cnmd08_historia_pen"]["primer_apellido"]." ".$rsdata["cnmd08_historia_pen"]["segundo_apellido"];
              $nombre_completo   = str_replace('  ',' ',trim($nombre_completo));
			  $nombre_completo   = str_replace("\t",' ',$nombre_completo);
			  $nombre_completo   = str_replace("Ñ",'@',$nombre_completo);
			  $nombre            = cortar_cadena_diskette(elimina_acentos($nombre_completo), 30);
			  $nombre            = str_replace("@",'Ñ',$nombre);
			  */



			  $primer_apellido  = $rsdata["cnmd08_historia_pen"]["primer_apellido"];
              $primer_apellido   = str_replace('  ',' ',trim($primer_apellido));
			  $primer_apellido   = str_replace("\t",' ',$primer_apellido);
			  $primer_apellido   = str_replace("Ñ",'@',$primer_apellido);
			  $primer_apellido   = cortar_cadena_diskette(elimina_acentos($primer_apellido), 60);
			  $primer_apellido   = str_replace("@",'Ñ',$primer_apellido);


              $segundo_apellido   = $rsdata["cnmd08_historia_pen"]["segundo_apellido"];
              $segundo_apellido   = str_replace('  ',' ',trim($segundo_apellido));
			  $segundo_apellido   = str_replace("\t",' ',$segundo_apellido);
			  $segundo_apellido   = str_replace("Ñ",'@',$segundo_apellido);
			  $segundo_apellido   = cortar_cadena_diskette(elimina_acentos($segundo_apellido), 60);
			  $segundo_apellido   = str_replace("@",'Ñ',$segundo_apellido);


              $primer_nombre   = $rsdata["cnmd08_historia_pen"]["primer_nombre"];
              $primer_nombre   = str_replace('  ',' ',trim($primer_nombre));
			  $primer_nombre   = str_replace("\t",' ',$primer_nombre);
			  $primer_nombre   = str_replace("Ñ",'@',$primer_nombre);
			  $primer_nombre   = cortar_cadena_diskette(elimina_acentos($primer_nombre), 60);
			  $primer_nombre   = str_replace("@",'Ñ',$primer_nombre);


              $segundo_nombre   = $rsdata["cnmd08_historia_pen"]["segundo_nombre"];
              $segundo_nombre   = str_replace('  ',' ',trim($segundo_nombre));
			  $segundo_nombre   = str_replace("\t",' ',$segundo_nombre);
			  $segundo_nombre   = str_replace("Ñ",'@',$segundo_nombre);
			  $segundo_nombre   = cortar_cadena_diskette(elimina_acentos($segundo_nombre), 60);
			  $segundo_nombre   = str_replace("@",'Ñ',$segundo_nombre);

			  $nombre            = $primer_apellido.'|'.$segundo_apellido.'|'.$primer_nombre.'|'.$segundo_nombre;


			  $cod_cargo  = mascara($rsdata["cnmd08_historia_pen"]["cod_cargo"],8);

			  $cod_puesto = mascara($rsdata["cnmd08_historia_pen"]["cod_puesto"],8);

			  $grado  = mascara($rsdata["cnmd08_historia_pen"]["grado"],2);

			  $direccion_habitacion   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen"]["direccion_habitacion"]));
			  $direccion_habitacion   = str_replace("\t",' ',$direccion_habitacion);
			  $direccion_habitacion   = str_replace("Ñ",'@',$direccion_habitacion);
			  $direccion_habitacion   = str_replace("º",' ',$direccion_habitacion);
			  $direccion_habitacion   = str_replace("°",' ',$direccion_habitacion);
			  $direccion_habitacion   = cortar_cadena_diskette(elimina_acentos($direccion_habitacion), 80);
			  $direccion_habitacion   = str_replace("@",'Ñ',$direccion_habitacion);

			  $telefonos_habitacion   = str_replace(' ','',trim($rsdata["cnmd08_historia_pen"]["telefonos_habitacion"]));
			  $telefonos_habitacion   = str_replace('-','',$telefonos_habitacion);

			  $cuenta_bancaria   = str_replace(' ','',trim($rsdata["cnmd08_historia_pen"]["cuenta_bancaria"]));


			  $cuenta_bancaria        = cortar_cadena_diskette(elimina_acentos($cuenta_bancaria), 20);
			  $telefonos_habitacion   = cortar_cadena_diskette(elimina_acentos($telefonos_habitacion), 30);




			  $sexo       = $rsdata["cnmd08_historia_pen"]["sexo"];



			  $cod_tipo_nomina       = mascara($rsdata["cnmd08_historia_pen"]["cod_tipo_nomina"],3);
			  $denominacion_nomina   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen"]["denominacion_nomina"]));
			  $denominacion_nomina   = str_replace("\t",' ',$denominacion_nomina);
			  $denominacion_nomina   = str_replace("Ñ",'@',$denominacion_nomina);
			  $denominacion_nomina   = cortar_cadena_diskette(elimina_acentos($denominacion_nomina), 60);
			  $denominacion_nomina   = str_replace("@",'Ñ',$denominacion_nomina);





			              $deno_prefesion   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen"]["deno_prefesion"]));
						  $deno_prefesion   = str_replace("\t",' ',$deno_prefesion);
						  $deno_prefesion   = str_replace("Ñ",'@',$deno_prefesion);
						  $deno_prefesion   = cortar_cadena_diskette(elimina_acentos($deno_prefesion), 60);
						  $deno_prefesion   = str_replace("@",'Ñ',$deno_prefesion);


						  $edad             = mascara(edad_basic($rsdata["cnmd08_historia_pen"]["fecha_nacimiento"]), 3);






						  $deno_cod_estado   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen"]["deno_cod_estado"]));
						  $deno_cod_estado   = str_replace("\t",' ',$deno_cod_estado);
						  $deno_cod_estado   = str_replace("Ñ",'@',$deno_cod_estado);
						  $deno_cod_estado   = cortar_cadena_diskette(elimina_acentos($deno_cod_estado), 60);
						  $deno_cod_estado   = str_replace("@",'Ñ',$deno_cod_estado);


						  $deno_cod_municipio   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen"]["deno_cod_municipio"]));
						  $deno_cod_municipio   = str_replace("\t",' ',$deno_cod_municipio);
						  $deno_cod_municipio   = str_replace("Ñ",'@',$deno_cod_municipio);
						  $deno_cod_municipio   = cortar_cadena_diskette(elimina_acentos($deno_cod_municipio), 60);
						  $deno_cod_municipio   = str_replace("@",'Ñ',$deno_cod_municipio);


						  $deno_cod_parroquia   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen"]["deno_cod_parroquia"]));
						  $deno_cod_parroquia   = str_replace("\t",' ',$deno_cod_parroquia);
						  $deno_cod_parroquia   = str_replace("Ñ",'@',$deno_cod_parroquia);
						  $deno_cod_parroquia   = cortar_cadena_diskette(elimina_acentos($deno_cod_parroquia), 60);
						  $deno_cod_parroquia   = str_replace("@",'Ñ',$deno_cod_parroquia);


						  $deno_cod_centro   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen"]["deno_cod_centro"]));
						  $deno_cod_centro   = str_replace("\t",' ',$deno_cod_centro);
						  $deno_cod_centro   = str_replace("Ñ",'@',$deno_cod_centro);
						  $deno_cod_centro   = cortar_cadena_diskette(elimina_acentos($deno_cod_centro), 60);
						  $deno_cod_centro   = str_replace("@",'Ñ',$deno_cod_centro);


                $deno_cod_dir_superior   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen"]["deno_cod_dir_superior"]));
			    $deno_cod_dir_superior   = str_replace("\t",' ',$deno_cod_dir_superior);
			    $deno_cod_dir_superior   = str_replace("Ñ",'@',$deno_cod_dir_superior);
			    $deno_cod_dir_superior   = cortar_cadena_diskette(elimina_acentos($deno_cod_dir_superior), 60);
			    $deno_cod_dir_superior   = str_replace("@",'Ñ',$deno_cod_dir_superior);

			    $deno_cod_coordinacion   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen"]["deno_cod_coordinacion"]));
			    $deno_cod_coordinacion   = str_replace("\t",' ',$deno_cod_coordinacion);
			    $deno_cod_coordinacion   = str_replace("Ñ",'@',$deno_cod_coordinacion);
			    $deno_cod_coordinacion   = cortar_cadena_diskette(elimina_acentos($deno_cod_coordinacion), 60);
			    $deno_cod_coordinacion   = str_replace("@",'Ñ',$deno_cod_coordinacion);


			    $deno_cod_secretaria   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen"]["deno_cod_secretaria"]));
			    $deno_cod_secretaria   = str_replace("\t",' ',$deno_cod_secretaria);
			    $deno_cod_secretaria   = str_replace("Ñ",'@',$deno_cod_secretaria);
			    $deno_cod_secretaria   = cortar_cadena_diskette(elimina_acentos($deno_cod_secretaria), 60);
			    $deno_cod_secretaria   = str_replace("@",'Ñ',$deno_cod_secretaria);


			    $deno_cod_direccion   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen"]["deno_cod_direccion"]));
			    $deno_cod_direccion   = str_replace("\t",' ',$deno_cod_direccion);
			    $deno_cod_direccion   = str_replace("Ñ",'@',$deno_cod_direccion);
			    $deno_cod_direccion   = cortar_cadena_diskette(elimina_acentos($deno_cod_direccion), 60);
			    $deno_cod_direccion   = str_replace("@",'Ñ',$deno_cod_direccion);


			    $deno_cod_division   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen"]["deno_cod_division"]));
			    $deno_cod_division   = str_replace("\t",' ',$deno_cod_division);
			    $deno_cod_division   = str_replace("Ñ",'@',$deno_cod_division);
			    $deno_cod_division   = cortar_cadena_diskette(elimina_acentos($deno_cod_division), 60);
			    $deno_cod_division   = str_replace("@",'Ñ',$deno_cod_division);

			    $deno_cod_departamento   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen"]["deno_cod_departamento"]));
			    $deno_cod_departamento   = str_replace("\t",' ',$deno_cod_departamento);
			    $deno_cod_departamento   = str_replace("Ñ",'@',$deno_cod_departamento);
			    $deno_cod_departamento   = cortar_cadena_diskette(elimina_acentos($deno_cod_departamento), 60);
			    $deno_cod_departamento   = str_replace("@",'Ñ',$deno_cod_departamento);

			    $deno_cod_oficina   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen"]["deno_cod_oficina"]));
			    $deno_cod_oficina   = str_replace("\t",' ',$deno_cod_oficina);
			    $deno_cod_oficina   = str_replace("Ñ",'@',$deno_cod_oficina);
			    $deno_cod_oficina   = cortar_cadena_diskette(elimina_acentos($deno_cod_oficina), 60);
			    $deno_cod_oficina   = str_replace("@",'Ñ',$deno_cod_oficina);


               if(!isset($rsdata["cnmd08_historia_pen"]["devolver_denominacion_puesto"])){$rsdata["cnmd08_historia_pen"]["devolver_denominacion_puesto"]="";}


                $devolver_denominacion_puesto   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen"]["denominacion_puesto"]));
			    $devolver_denominacion_puesto   = str_replace("\t",' ',$devolver_denominacion_puesto);
			    $devolver_denominacion_puesto   = str_replace("Ñ",'@',$devolver_denominacion_puesto);
			    $devolver_denominacion_puesto   = cortar_cadena_diskette(elimina_acentos($devolver_denominacion_puesto), 60);
			    $devolver_denominacion_puesto   = str_replace("@",'Ñ',$devolver_denominacion_puesto);




				$neto_aux = explode('.',$rsdata["cnmd08_historia_pen"]["sueldo_basico"]);
				if(isset($neto_aux[1])){
					$decimal=$neto_aux[1];
				}else{
					$decimal=0;
				}
				$decimal               =  str_pad($decimal, 2 , "0", STR_PAD_RIGHT).'';
				$neto_cobrar_1         =  $neto_aux[0].'.'.$decimal;
				$sueldo_basico         =  mascara($neto_cobrar_1,10);

				if(!isset($rsdata["cnmd08_historia_pen"]["periodo_hasta"])){$rsdata["cnmd08_historia_pen"]["periodo_hasta"]="";}

//				$periodo_hasta         =  str_replace('/','',cambia_fecha($rsdata["cnmd08_historia_pen"]["periodo_hasta"]));
//				$fecha_nacimiento      =  str_replace('/','',cambia_fecha($rsdata["cnmd08_historia_pen"]["fecha_nacimiento"]));
//				$fecha_ingreso         =  str_replace('/','',cambia_fecha($rsdata["cnmd08_historia_pen"]["fecha_ingreso"]));
				$periodo_hasta         =  cambia_fecha($rsdata["cnmd08_historia_pen"]["periodo_hasta"]);
				$fecha_nacimiento      =  cambia_fecha($rsdata["cnmd08_historia_pen"]["fecha_nacimiento"]);
				$fecha_ingreso         =  cambia_fecha($rsdata["cnmd08_historia_pen"]["fecha_ingreso"]);




				$filas_archivo        .=  $cod_tipo_nomina.$denominacion_nomina.'|'.$deno_cod_estado.'|'.$deno_cod_municipio.'|'.$deno_cod_parroquia.'|'.$deno_cod_centro.'|'.$deno_cod_dir_superior.'|'.$deno_cod_coordinacion.'|'.$deno_cod_secretaria.'|'.$deno_cod_direccion.'|'.$deno_cod_division.'|'.$deno_cod_departamento.'|'.$deno_cod_oficina;
                $filas_archivo        .=  '|'.$cod_puesto.'|'.$cod_cargo.'|'.$grado.'|'.$sexo.'|'.$nombre.'|'.$nacionalidad.'|'.$cedula_identidad.'|'.$estado_civil.'|'.$devolver_denominacion_puesto;
			    $filas_archivo        .=  '|'.$fecha_nacimiento.'|'.$fecha_ingreso.'|'.$sueldo_basico.'|'.$direccion_habitacion.'|'.$telefonos_habitacion.'|'.$cuenta_bancaria.'|'.$deno_prefesion.'|'.$edad;

			    $filas_archivo         = strtoupper_sisap($filas_archivo)."\n";
			}


		$this->wFile($nombre_archivo, $filas_archivo);
		$this->set('filas_archivo', $filas_archivo);





 }//fin else




$this->set('opcion', $var1);



}//fin function















function diskett_7($var1=null, $var2=null, $var3=null){

       if($var1==1){
           $this->layout = "ajax";

	           $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		       $this->concatena($lista, 'lista_nomina');
		       $this->Session->delete('tipo_nomina');

		       $consulta_c_t = $this->cnmd03_transaccion->generateList("cod_tipo_transaccion=1", 'cod_transaccion ASC', null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.denominacion');
	   	       $this->concatena($consulta_c_t, "lista");



  }else if($var1==2){

  	set_time_limit(0);
	ini_set("memory_limit","2000M");

           $this->layout  = "txt";
           $cod_presi     = $this->Session->read('SScodpresi');
		   $cod_entidad   = $this->Session->read('SScodentidad');
		   $cod_tipo_inst = $this->Session->read('SScodtipoinst');
		   $cod_inst      = $this->Session->read('SScodinst');
		   $cod_dep       = $this->Session->read('SScoddep');
                $cod_nomina                    = $this->data["cnmp06_diskett_historico"]["cod_nomina"];
                $condicion_actividad_ficha     = $this->data["cnmp06_diskett_historico"]["condicion_actividad_ficha"];

                $sql               = $this->condicion()." and cod_tipo_nomina='".$cod_nomina."'  ";
	       $nombre_archivo         = 'Nomina_'.mascara($cod_dep,4).'_'.mascara($cod_nomina,3).'_'.date('d_m_Y_h:i:sa').'';
	       $_SESSION["nombre_txt"] = $nombre_archivo.".txt";
	       $data3                  = $this->cnmd08_historia_pen_ac->findAll($sql." and condicion_actividad_ficha='".$condicion_actividad_ficha."'  ",null, "cedula_identidad ASC");
	       $filas_archivo   = "";
//           pr($data3);
		   foreach($data3 as $rsdata){
		   	  $estado_civil  = $rsdata["cnmd08_historia_pen_ac"]["estado_civil"];
		   	  $nacionalidad  = $rsdata["cnmd08_historia_pen_ac"]["nacionalidad"];

              $cedula_identidad  = mascara($rsdata["cnmd08_historia_pen_ac"]["cedula_identidad"],10);
              $primer_apellido  = $rsdata["cnmd08_historia_pen_ac"]["primer_apellido"];
              $primer_apellido   = str_replace('  ',' ',trim($primer_apellido));
			  $primer_apellido   = str_replace("\t",' ',$primer_apellido);
			  $primer_apellido   = str_replace("Ñ",'@',$primer_apellido);
			  $primer_apellido   = cortar_cadena_diskette(elimina_acentos($primer_apellido), 60);
			  $primer_apellido   = str_replace("@",'Ñ',$primer_apellido);


              $segundo_apellido = $rsdata["cnmd08_historia_pen_ac"]["segundo_apellido"];
              $segundo_apellido   = str_replace('  ',' ',trim($segundo_apellido));
			  $segundo_apellido   = str_replace("\t",' ',$segundo_apellido);
			  $segundo_apellido   = str_replace("Ñ",'@',$segundo_apellido);
			  $segundo_apellido   = cortar_cadena_diskette(elimina_acentos($segundo_apellido), 60);
			  $segundo_apellido   = str_replace("@",'Ñ',$segundo_apellido);


              $primer_nombre    = $rsdata["cnmd08_historia_pen_ac"]["primer_nombre"];
              $primer_nombre   = str_replace('  ',' ',trim($primer_nombre));
			  $primer_nombre   = str_replace("\t",' ',$primer_nombre);
			  $primer_nombre   = str_replace("Ñ",'@',$primer_nombre);
			  $primer_nombre   = cortar_cadena_diskette(elimina_acentos($primer_nombre), 60);
			  $primer_nombre   = str_replace("@",'Ñ',$primer_nombre);


              $segundo_nombre   = $rsdata["cnmd08_historia_pen_ac"]["segundo_nombre"];
              $segundo_nombre   = str_replace('  ',' ',trim($segundo_nombre));
			  $segundo_nombre   = str_replace("\t",' ',$segundo_nombre);
			  $segundo_nombre   = str_replace("Ñ",'@',$segundo_nombre);
			  $segundo_nombre   = cortar_cadena_diskette(elimina_acentos($segundo_nombre), 60);
			  $segundo_nombre   = str_replace("@",'Ñ',$segundo_nombre);

			  $nombre            = $primer_apellido.'|'.$segundo_apellido.'|'.$primer_nombre.'|'.$segundo_nombre;

			  $cod_cargo  = mascara($rsdata["cnmd08_historia_pen_ac"]["cod_cargo"],8);

			  $grado  = mascara($rsdata["cnmd08_historia_pen_ac"]["grado"],2);

			  $cod_puesto = mascara($rsdata["cnmd08_historia_pen_ac"]["cod_puesto"],8);

			  $direccion_habitacion   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen_ac"]["direccion_habitacion"]));
			  $direccion_habitacion   = str_replace("\t",' ',$direccion_habitacion);
			  $direccion_habitacion   = str_replace("Ñ",'@',$direccion_habitacion);
			  $direccion_habitacion   = str_replace("º",' ',$direccion_habitacion);
			  $direccion_habitacion   = str_replace("°",' ',$direccion_habitacion);
			  $direccion_habitacion   = cortar_cadena_diskette(elimina_acentos($direccion_habitacion), 80);
			  $direccion_habitacion   = str_replace("@",'Ñ',$direccion_habitacion);



			  $telefonos_habitacion   = str_replace(' ','',trim($rsdata["cnmd08_historia_pen_ac"]["telefonos_habitacion"]));
			  $telefonos_habitacion   = str_replace('-','',$telefonos_habitacion);

			  $cuenta_bancaria   = str_replace(' ','',trim($rsdata["cnmd08_historia_pen_ac"]["cuenta_bancaria"]));



			  $cuenta_bancaria        = cortar_cadena_diskette(elimina_acentos($cuenta_bancaria), 20);
			  $telefonos_habitacion   = cortar_cadena_diskette(elimina_acentos($telefonos_habitacion), 30);




			  $sexo       = $rsdata["cnmd08_historia_pen_ac"]["sexo"];



			  $cod_tipo_nomina       = mascara($rsdata["cnmd08_historia_pen_ac"]["cod_tipo_nomina"],3);
			  $denominacion_nomina   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen_ac"]["denominacion_nomina"]));
			  $denominacion_nomina   = str_replace("\t",' ',$denominacion_nomina);
			  $denominacion_nomina   = str_replace("Ñ",'@',$denominacion_nomina);
			  $denominacion_nomina   = cortar_cadena_diskette(elimina_acentos($denominacion_nomina), 60);
			  $denominacion_nomina   = str_replace("@",'Ñ',$denominacion_nomina);


			              $deno_prefesion   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen_ac"]["deno_prefesion"]));
						  $deno_prefesion   = str_replace("\t",' ',$deno_prefesion);
						  $deno_prefesion   = str_replace("Ñ",'@',$deno_prefesion);
						  $deno_prefesion   = cortar_cadena_diskette(elimina_acentos($deno_prefesion), 60);
						  $deno_prefesion   = str_replace("@",'Ñ',$deno_prefesion);

						  $edad             = mascara(edad_basic($rsdata["cnmd08_historia_pen_ac"]["fecha_nacimiento"]), 3);

						  $deno_cod_estado   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen_ac"]["deno_cod_estado"]));
						  $deno_cod_estado   = str_replace("\t",' ',$deno_cod_estado);
						  $deno_cod_estado   = str_replace("Ñ",'@',$deno_cod_estado);
						  $deno_cod_estado   = cortar_cadena_diskette(elimina_acentos($deno_cod_estado), 60);
						  $deno_cod_estado   = str_replace("@",'Ñ',$deno_cod_estado);


						  $deno_cod_municipio   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen_ac"]["deno_cod_municipio"]));
						  $deno_cod_municipio   = str_replace("\t",' ',$deno_cod_municipio);
						  $deno_cod_municipio   = str_replace("Ñ",'@',$deno_cod_municipio);
						  $deno_cod_municipio   = cortar_cadena_diskette(elimina_acentos($deno_cod_municipio), 60);
						  $deno_cod_municipio   = str_replace("@",'Ñ',$deno_cod_municipio);


						  $deno_cod_parroquia   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen_ac"]["deno_cod_parroquia"]));
						  $deno_cod_parroquia   = str_replace("\t",' ',$deno_cod_parroquia);
						  $deno_cod_parroquia   = str_replace("Ñ",'@',$deno_cod_parroquia);
						  $deno_cod_parroquia   = cortar_cadena_diskette(elimina_acentos($deno_cod_parroquia), 60);
						  $deno_cod_parroquia   = str_replace("@",'Ñ',$deno_cod_parroquia);


						  $deno_cod_centro   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen_ac"]["deno_cod_centro"]));
						  $deno_cod_centro   = str_replace("\t",' ',$deno_cod_centro);
						  $deno_cod_centro   = str_replace("Ñ",'@',$deno_cod_centro);
						  $deno_cod_centro   = cortar_cadena_diskette(elimina_acentos($deno_cod_centro), 60);
						  $deno_cod_centro   = str_replace("@",'Ñ',$deno_cod_centro);


                $deno_cod_dir_superior   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen_ac"]["deno_cod_dir_superior"]));
			    $deno_cod_dir_superior   = str_replace("\t",' ',$deno_cod_dir_superior);
			    $deno_cod_dir_superior   = str_replace("Ñ",'@',$deno_cod_dir_superior);
			    $deno_cod_dir_superior   = cortar_cadena_diskette(elimina_acentos($deno_cod_dir_superior), 60);
			    $deno_cod_dir_superior   = str_replace("@",'Ñ',$deno_cod_dir_superior);

			    $deno_cod_coordinacion   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen_ac"]["deno_cod_coordinacion"]));
			    $deno_cod_coordinacion   = str_replace("\t",' ',$deno_cod_coordinacion);
			    $deno_cod_coordinacion   = str_replace("Ñ",'@',$deno_cod_coordinacion);
			    $deno_cod_coordinacion   = cortar_cadena_diskette(elimina_acentos($deno_cod_coordinacion), 60);
			    $deno_cod_coordinacion   = str_replace("@",'Ñ',$deno_cod_coordinacion);


			    $deno_cod_secretaria   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen_ac"]["deno_cod_secretaria"]));
			    $deno_cod_secretaria   = str_replace("\t",' ',$deno_cod_secretaria);
			    $deno_cod_secretaria   = str_replace("Ñ",'@',$deno_cod_secretaria);
			    $deno_cod_secretaria   = cortar_cadena_diskette(elimina_acentos($deno_cod_secretaria), 60);
			    $deno_cod_secretaria   = str_replace("@",'Ñ',$deno_cod_secretaria);


			    $deno_cod_direccion   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen_ac"]["deno_cod_direccion"]));
			    $deno_cod_direccion   = str_replace("\t",' ',$deno_cod_direccion);
			    $deno_cod_direccion   = str_replace("Ñ",'@',$deno_cod_direccion);
			    $deno_cod_direccion   = cortar_cadena_diskette(elimina_acentos($deno_cod_direccion), 60);
			    $deno_cod_direccion   = str_replace("@",'Ñ',$deno_cod_direccion);


			    $deno_cod_division   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen_ac"]["deno_cod_division"]));
			    $deno_cod_division   = str_replace("\t",' ',$deno_cod_division);
			    $deno_cod_division   = str_replace("Ñ",'@',$deno_cod_division);
			    $deno_cod_division   = cortar_cadena_diskette(elimina_acentos($deno_cod_division), 60);
			    $deno_cod_division   = str_replace("@",'Ñ',$deno_cod_division);

			    $deno_cod_departamento   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen_ac"]["deno_cod_departamento"]));
			    $deno_cod_departamento   = str_replace("\t",' ',$deno_cod_departamento);
			    $deno_cod_departamento   = str_replace("Ñ",'@',$deno_cod_departamento);
			    $deno_cod_departamento   = cortar_cadena_diskette(elimina_acentos($deno_cod_departamento), 60);
			    $deno_cod_departamento   = str_replace("@",'Ñ',$deno_cod_departamento);

			    $deno_cod_oficina   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen_ac"]["deno_cod_oficina"]));
			    $deno_cod_oficina   = str_replace("\t",' ',$deno_cod_oficina);
			    $deno_cod_oficina   = str_replace("Ñ",'@',$deno_cod_oficina);
			    $deno_cod_oficina   = cortar_cadena_diskette(elimina_acentos($deno_cod_oficina), 60);
			    $deno_cod_oficina   = str_replace("@",'Ñ',$deno_cod_oficina);


               if(!isset($rsdata["cnmd08_historia_pen_ac"]["devolver_denominacion_puesto"])){$rsdata["cnmd08_historia_pen_ac"]["devolver_denominacion_puesto"]="";}


                $devolver_denominacion_puesto   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen_ac"]["denominacion_puesto"]));
			    $devolver_denominacion_puesto   = str_replace("\t",' ',$devolver_denominacion_puesto);
			    $devolver_denominacion_puesto   = str_replace("Ñ",'@',$devolver_denominacion_puesto);
			    $devolver_denominacion_puesto   = cortar_cadena_diskette(elimina_acentos($devolver_denominacion_puesto), 60);
			    $devolver_denominacion_puesto   = str_replace("@",'Ñ',$devolver_denominacion_puesto);




				$neto_aux = explode('.',$rsdata["cnmd08_historia_pen_ac"]["sueldo_basico"]);
				if(isset($neto_aux[1])){
					$decimal=$neto_aux[1];
				}else{
					$decimal=0;
				}
				$decimal               =  str_pad($decimal, 2 , "0", STR_PAD_RIGHT).'';
				$neto_cobrar_1         =  $neto_aux[0].'.'.$decimal;
				$sueldo_basico         =  mascara($neto_cobrar_1,10);

				if(!isset($rsdata["cnmd08_historia_pen_ac"]["periodo_hasta"])){$rsdata["cnmd08_historia_pen_ac"]["periodo_hasta"]="";}


//				$periodo_hasta         =  str_replace('/','',cambia_fecha($rsdata["cnmd08_historia_pen_ac"]["periodo_hasta"]));
//				$fecha_nacimiento      =  str_replace('/','',cambia_fecha($rsdata["cnmd08_historia_pen_ac"]["fecha_nacimiento"]));
//				$fecha_ingreso         =  str_replace('/','',cambia_fecha($rsdata["cnmd08_historia_pen_ac"]["fecha_ingreso"]));
				$periodo_hasta         =  cambia_fecha($rsdata["cnmd08_historia_pen_ac"]["periodo_hasta"]);
				$fecha_nacimiento      =  cambia_fecha($rsdata["cnmd08_historia_pen_ac"]["fecha_nacimiento"]);
				$fecha_ingreso         =  cambia_fecha($rsdata["cnmd08_historia_pen_ac"]["fecha_ingreso"]);




				$filas_archivo        .=  $cod_tipo_nomina.$denominacion_nomina.'|'.$deno_cod_estado.'|'.$deno_cod_municipio.'|'.$deno_cod_parroquia.'|'.$deno_cod_centro.'|'.$deno_cod_dir_superior.'|'.$deno_cod_coordinacion.'|'.$deno_cod_secretaria.'|'.$deno_cod_direccion.'|'.$deno_cod_division.'|'.$deno_cod_departamento.'|'.$deno_cod_oficina;
                $filas_archivo        .=  '|'.$cod_puesto.'|'.$cod_cargo.'|'.$grado.'|'.$sexo.'|'.$nombre.'|'.$nacionalidad.'|'.$cedula_identidad.'|'.$estado_civil.'|'.$devolver_denominacion_puesto;
			    $filas_archivo        .=  '|'.$fecha_nacimiento.'|'.$fecha_ingreso.'|'.$sueldo_basico.'|'.$direccion_habitacion.'|'.$telefonos_habitacion.'|'.$cuenta_bancaria.'|'.$deno_prefesion.'|'.$edad;

			    $filas_archivo         = strtoupper_sisap($filas_archivo)."\n";
			}


		$this->wFile($nombre_archivo, $filas_archivo);
		$this->set('filas_archivo', $filas_archivo);





 }//fin else




$this->set('opcion', $var1);



}//fin function


function diskett_8($var1=null, $var2=null, $var3=null){

       if($var1==1){
           $this->layout = "ajax";
           $cod_presi     = $this->Session->read('SScodpresi');
		   $cod_entidad   = $this->Session->read('SScodentidad');
		   $cod_tipo_inst = $this->Session->read('SScodtipoinst');
		   $cod_inst      = $this->Session->read('SScodinst');
		   $cod_dep       = $this->Session->read('SScoddep');

              $lista = $this->cugd02_dependencia->generateList("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst'", $order = 'cod_dependencia', $limit = null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
	          $this->concatena($lista, 'tipo');

	           $lista = $this->Cnmd01->generateListTodos($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		       $this->concatena($lista, 'lista_nomina');
		       $this->Session->delete('tipo_nomina');

		       $consulta_c_t = $this->cnmd03_transaccion->generateList("cod_tipo_transaccion=1", 'cod_transaccion ASC', null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.denominacion');
	   	       $this->concatena($consulta_c_t, "lista");



  }else if($var1==2){

  	set_time_limit(0);
	ini_set("memory_limit","2000M");

           $this->layout  = "txt";
           $cod_presi     = $this->Session->read('SScodpresi');
		   $cod_entidad   = $this->Session->read('SScodentidad');
		   $cod_tipo_inst = $this->Session->read('SScodtipoinst');
		   $cod_inst      = $this->Session->read('SScodinst');
		   $cod_dep       = $this->Session->read('SScoddep');

                $condicion_actividad_ficha     = $this->data["cnmp06_diskett_historico"]["condicion_actividad_ficha"];
                $CONSOLIDACION = $this->data["cnmp06_diskett_historico"]["condicion_actividad_ficha2"];

                 $cod_nomina                 = $this->data["cnmp06_diskett_historico"]["cod_nomina"];
                 $cod_dep                    = $this->data["cnmp06_diskett_historico"]["cod_dep"];
                if($CONSOLIDACION==1 || $CONSOLIDACION=='1'){
                	$sql               = $this->condicionNDEP()." and cod_dep='".$cod_dep."' and cod_tipo_nomina='".$cod_nomina."'  ";
                }else{
                	$sql               = $this->condicionNDEP()."  ";
                }



	       $nombre_archivo         = 'Nomina_todo_personal_activo_'.date('d_m_Y_h:i:sa').'';
	       $_SESSION["nombre_txt"] = $nombre_archivo.".txt";
	       $data3                  = $this->cnmd08_historia_pen_ac->findAll($sql." and condicion_actividad_ficha='".$condicion_actividad_ficha."'  ",null, "cod_dep, cod_tipo_nomina, cedula_identidad ASC");
	       $filas_archivo   = "";

	       $lista_dep = $this->cugd02_dependencia->findAll("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='".$cod_dep."' ");
	       $deno_dep  = $lista_dep[0]["cugd02_dependencia"]["denominacion"];

		   foreach($data3 as $rsdata){
		   	  $estado_civil  = $rsdata["cnmd08_historia_pen_ac"]["estado_civil"];
		   	  $nacionalidad  = $rsdata["cnmd08_historia_pen_ac"]["nacionalidad"];
              $cedula_identidad  = mascara($rsdata["cnmd08_historia_pen_ac"]["cedula_identidad"],10);
              /*
              $nombre_completo   = $rsdata["cnmd08_historia_pen_ac"]["primer_nombre"]." ".$rsdata["cnmd08_historia_pen_ac"]["segundo_nombre"]." ";
              $nombre_completo  .= $rsdata["cnmd08_historia_pen_ac"]["primer_apellido"]." ".$rsdata["cnmd08_historia_pen_ac"]["segundo_apellido"];
              $nombre_completo   = str_replace('  ',' ',trim($nombre_completo));
			  $nombre_completo   = str_replace("\t",' ',$nombre_completo);
			  $nombre_completo   = str_replace("Ñ",'@',$nombre_completo);
			  $nombre            = cortar_cadena_diskette(elimina_acentos($nombre_completo), 30);
			  $nombre            = str_replace("@",'Ñ',$nombre);

			  */
			  $primer_apellido  = $rsdata["cnmd08_historia_pen_ac"]["primer_apellido"];
              $primer_apellido   = str_replace('  ',' ',trim($primer_apellido));
			  $primer_apellido   = str_replace("\t",' ',$primer_apellido);
			  $primer_apellido   = str_replace("Ñ",'@',$primer_apellido);
			  $primer_apellido   = cortar_cadena_diskette(elimina_acentos($primer_apellido), 60);
			  $primer_apellido   = str_replace("@",'Ñ',$primer_apellido);

              $segundo_apellido   = $rsdata["cnmd08_historia_pen_ac"]["segundo_apellido"];
              $segundo_apellido   = str_replace('  ',' ',trim($segundo_apellido));
			  $segundo_apellido   = str_replace("\t",' ',$segundo_apellido);
			  $segundo_apellido   = str_replace("Ñ",'@',$segundo_apellido);
			  $segundo_apellido   = cortar_cadena_diskette(elimina_acentos($segundo_apellido), 60);
			  $segundo_apellido   = str_replace("@",'Ñ',$segundo_apellido);

              $primer_nombre   = $rsdata["cnmd08_historia_pen_ac"]["primer_nombre"];
              $primer_nombre   = str_replace('  ',' ',trim($primer_nombre));
			  $primer_nombre   = str_replace("\t",' ',$primer_nombre);
			  $primer_nombre   = str_replace("Ñ",'@',$primer_nombre);
			  $primer_nombre   = cortar_cadena_diskette(elimina_acentos($primer_nombre), 60);
			  $primer_nombre   = str_replace("@",'Ñ',$primer_nombre);

              $segundo_nombre   = $rsdata["cnmd08_historia_pen_ac"]["segundo_nombre"];
              $segundo_nombre   = str_replace('  ',' ',trim($segundo_nombre));
			  $segundo_nombre   = str_replace("\t",' ',$segundo_nombre);
			  $segundo_nombre   = str_replace("Ñ",'@',$segundo_nombre);
			  $segundo_nombre   = cortar_cadena_diskette(elimina_acentos($segundo_nombre), 60);
			  $segundo_nombre   = str_replace("@",'Ñ',$segundo_nombre);

			  $nombre            = $primer_apellido.'|'.$segundo_apellido.'|'.$primer_nombre.'|'.$segundo_nombre;


			  $cod_cargo  = mascara($rsdata["cnmd08_historia_pen_ac"]["cod_cargo"],8);


			  if(!isset($rsdata["cnmd08_historia_pen_ac"]["grado"])){$rsdata["cnmd08_historia_pen_ac"]["grado"]="";}
			  $grado  = mascara($rsdata["cnmd08_historia_pen_ac"]["grado"],2);

			  $cod_puesto = mascara($rsdata["cnmd08_historia_pen_ac"]["cod_puesto"],8);

			  $direccion_habitacion   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen_ac"]["direccion_habitacion"]));
			  $direccion_habitacion   = str_replace("\t",' ',$direccion_habitacion);
			  $direccion_habitacion   = str_replace("Ñ",'@',$direccion_habitacion);
			  $direccion_habitacion   = str_replace("º",' ',$direccion_habitacion);
			  $direccion_habitacion   = str_replace("°",' ',$direccion_habitacion);
			  $direccion_habitacion   = cortar_cadena_diskette(elimina_acentos($direccion_habitacion), 80);
			  $direccion_habitacion   = str_replace("@",'Ñ',$direccion_habitacion);



			  $telefonos_habitacion   = str_replace(' ','',trim($rsdata["cnmd08_historia_pen_ac"]["telefonos_habitacion"]));
			  $telefonos_habitacion   = str_replace('-','',$telefonos_habitacion);

			  $cuenta_bancaria   = str_replace(' ','',trim($rsdata["cnmd08_historia_pen_ac"]["cuenta_bancaria"]));



			  $cuenta_bancaria        = cortar_cadena_diskette(elimina_acentos($cuenta_bancaria), 20);
			  $telefonos_habitacion   = cortar_cadena_diskette(elimina_acentos($telefonos_habitacion), 30);




			  $sexo       = $rsdata["cnmd08_historia_pen_ac"]["sexo"];

			  $cod_dep    = mascara($cod_dep,3);
			  $deno_dep   = str_replace('  ',' ',trim($deno_dep));
			  $deno_dep   = str_replace("\t",' ',$deno_dep);
			  $deno_dep   = str_replace("Ñ",'@',$deno_dep);
			  $deno_dep   = cortar_cadena_diskette(elimina_acentos($deno_dep), 60);
			  $deno_dep   = str_replace("@",'Ñ',$deno_dep);



			  $cod_tipo_nomina       = mascara($rsdata["cnmd08_historia_pen_ac"]["cod_tipo_nomina"],3);
			  $denominacion_nomina   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen_ac"]["denominacion_nomina"]));
			  $denominacion_nomina   = str_replace("\t",' ',$denominacion_nomina);
			  $denominacion_nomina   = str_replace("Ñ",'@',$denominacion_nomina);
			  $denominacion_nomina   = cortar_cadena_diskette(elimina_acentos($denominacion_nomina), 60);
			  $denominacion_nomina   = str_replace("@",'Ñ',$denominacion_nomina);


			              $deno_prefesion   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen_ac"]["deno_prefesion"]));
						  $deno_prefesion   = str_replace("\t",' ',$deno_prefesion);
						  $deno_prefesion   = str_replace("Ñ",'@',$deno_prefesion);
						  $deno_prefesion   = cortar_cadena_diskette(elimina_acentos($deno_prefesion), 60);
						  $deno_prefesion   = str_replace("@",'Ñ',$deno_prefesion);

						  $edad             = mascara(edad_basic($rsdata["cnmd08_historia_pen_ac"]["fecha_nacimiento"]), 3);

						  $deno_cod_estado   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen_ac"]["deno_cod_estado"]));
						  $deno_cod_estado   = str_replace("\t",' ',$deno_cod_estado);
						  $deno_cod_estado   = str_replace("Ñ",'@',$deno_cod_estado);
						  $deno_cod_estado   = cortar_cadena_diskette(elimina_acentos($deno_cod_estado), 60);
						  $deno_cod_estado   = str_replace("@",'Ñ',$deno_cod_estado);


						  $deno_cod_municipio   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen_ac"]["deno_cod_municipio"]));
						  $deno_cod_municipio   = str_replace("\t",' ',$deno_cod_municipio);
						  $deno_cod_municipio   = str_replace("Ñ",'@',$deno_cod_municipio);
						  $deno_cod_municipio   = cortar_cadena_diskette(elimina_acentos($deno_cod_municipio), 60);
						  $deno_cod_municipio   = str_replace("@",'Ñ',$deno_cod_municipio);


						  $deno_cod_parroquia   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen_ac"]["deno_cod_parroquia"]));
						  $deno_cod_parroquia   = str_replace("\t",' ',$deno_cod_parroquia);
						  $deno_cod_parroquia   = str_replace("Ñ",'@',$deno_cod_parroquia);
						  $deno_cod_parroquia   = cortar_cadena_diskette(elimina_acentos($deno_cod_parroquia), 60);
						  $deno_cod_parroquia   = str_replace("@",'Ñ',$deno_cod_parroquia);


						  $deno_cod_centro   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen_ac"]["deno_cod_centro"]));
						  $deno_cod_centro   = str_replace("\t",' ',$deno_cod_centro);
						  $deno_cod_centro   = str_replace("Ñ",'@',$deno_cod_centro);
						  $deno_cod_centro   = cortar_cadena_diskette(elimina_acentos($deno_cod_centro), 60);
						  $deno_cod_centro   = str_replace("@",'Ñ',$deno_cod_centro);


                $deno_cod_dir_superior   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen_ac"]["deno_cod_dir_superior"]));
			    $deno_cod_dir_superior   = str_replace("\t",' ',$deno_cod_dir_superior);
			    $deno_cod_dir_superior   = str_replace("Ñ",'@',$deno_cod_dir_superior);
			    $deno_cod_dir_superior   = cortar_cadena_diskette(elimina_acentos($deno_cod_dir_superior), 60);
			    $deno_cod_dir_superior   = str_replace("@",'Ñ',$deno_cod_dir_superior);

			    $deno_cod_coordinacion   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen_ac"]["deno_cod_coordinacion"]));
			    $deno_cod_coordinacion   = str_replace("\t",' ',$deno_cod_coordinacion);
			    $deno_cod_coordinacion   = str_replace("Ñ",'@',$deno_cod_coordinacion);
			    $deno_cod_coordinacion   = cortar_cadena_diskette(elimina_acentos($deno_cod_coordinacion), 60);
			    $deno_cod_coordinacion   = str_replace("@",'Ñ',$deno_cod_coordinacion);


			    $deno_cod_secretaria   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen_ac"]["deno_cod_secretaria"]));
			    $deno_cod_secretaria   = str_replace("\t",' ',$deno_cod_secretaria);
			    $deno_cod_secretaria   = str_replace("Ñ",'@',$deno_cod_secretaria);
			    $deno_cod_secretaria   = cortar_cadena_diskette(elimina_acentos($deno_cod_secretaria), 60);
			    $deno_cod_secretaria   = str_replace("@",'Ñ',$deno_cod_secretaria);


			    $deno_cod_direccion   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen_ac"]["deno_cod_direccion"]));
			    $deno_cod_direccion   = str_replace("\t",' ',$deno_cod_direccion);
			    $deno_cod_direccion   = str_replace("Ñ",'@',$deno_cod_direccion);
			    $deno_cod_direccion   = cortar_cadena_diskette(elimina_acentos($deno_cod_direccion), 60);
			    $deno_cod_direccion   = str_replace("@",'Ñ',$deno_cod_direccion);


			    $deno_cod_division   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen_ac"]["deno_cod_division"]));
			    $deno_cod_division   = str_replace("\t",' ',$deno_cod_division);
			    $deno_cod_division   = str_replace("Ñ",'@',$deno_cod_division);
			    $deno_cod_division   = cortar_cadena_diskette(elimina_acentos($deno_cod_division), 60);
			    $deno_cod_division   = str_replace("@",'Ñ',$deno_cod_division);

			    $deno_cod_departamento   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen_ac"]["deno_cod_departamento"]));
			    $deno_cod_departamento   = str_replace("\t",' ',$deno_cod_departamento);
			    $deno_cod_departamento   = str_replace("Ñ",'@',$deno_cod_departamento);
			    $deno_cod_departamento   = cortar_cadena_diskette(elimina_acentos($deno_cod_departamento), 60);
			    $deno_cod_departamento   = str_replace("@",'Ñ',$deno_cod_departamento);

			    $deno_cod_oficina   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen_ac"]["deno_cod_oficina"]));
			    $deno_cod_oficina   = str_replace("\t",' ',$deno_cod_oficina);
			    $deno_cod_oficina   = str_replace("Ñ",'@',$deno_cod_oficina);
			    $deno_cod_oficina   = cortar_cadena_diskette(elimina_acentos($deno_cod_oficina), 60);
			    $deno_cod_oficina   = str_replace("@",'Ñ',$deno_cod_oficina);


               if(!isset($rsdata["cnmd08_historia_pen_ac"]["devolver_denominacion_puesto"])){$rsdata["cnmd08_historia_pen_ac"]["devolver_denominacion_puesto"]="";}


                $devolver_denominacion_puesto   = str_replace('  ',' ',trim($rsdata["cnmd08_historia_pen_ac"]["denominacion_puesto"]));
			    $devolver_denominacion_puesto   = str_replace("\t",' ',$devolver_denominacion_puesto);
			    $devolver_denominacion_puesto   = str_replace("Ñ",'@',$devolver_denominacion_puesto);
			    $devolver_denominacion_puesto   = cortar_cadena_diskette(elimina_acentos($devolver_denominacion_puesto), 60);
			    $devolver_denominacion_puesto   = str_replace("@",'Ñ',$devolver_denominacion_puesto);




				$neto_aux = explode('.',$rsdata["cnmd08_historia_pen_ac"]["sueldo_basico"]);
				if(isset($neto_aux[1])){
					$decimal=$neto_aux[1];
				}else{
					$decimal=0;
				}
				$decimal               =  str_pad($decimal, 2 , "0", STR_PAD_RIGHT).'';
				$neto_cobrar_1         =  $neto_aux[0].'.'.$decimal;
				$sueldo_basico         =  mascara($neto_cobrar_1,10);

				if(!isset($rsdata["cnmd08_historia_pen_ac"]["periodo_hasta"])){$rsdata["cnmd08_historia_pen_ac"]["periodo_hasta"]="";}


//				$periodo_hasta         =  str_replace('/','',cambia_fecha($rsdata["cnmd08_historia_pen_ac"]["periodo_hasta"]));
//				$fecha_nacimiento      =  str_replace('/','',cambia_fecha($rsdata["cnmd08_historia_pen_ac"]["fecha_nacimiento"]));
//				$fecha_ingreso         =  str_replace('/','',cambia_fecha($rsdata["cnmd08_historia_pen_ac"]["fecha_ingreso"]));

				$periodo_hasta         =  cambia_fecha($rsdata["cnmd08_historia_pen_ac"]["periodo_hasta"]);
				$fecha_nacimiento      =  cambia_fecha($rsdata["cnmd08_historia_pen_ac"]["fecha_nacimiento"]);
				$fecha_ingreso         =  cambia_fecha($rsdata["cnmd08_historia_pen_ac"]["fecha_ingreso"]);



				$filas_archivo        .=  $cod_dep.'|'.$deno_dep.'|'.$cod_tipo_nomina.$denominacion_nomina.'|'.$deno_cod_estado.'|'.$deno_cod_municipio.'|'.$deno_cod_parroquia.'|'.$deno_cod_centro.'|'.$deno_cod_dir_superior.'|'.$deno_cod_coordinacion.'|'.$deno_cod_secretaria.'|'.$deno_cod_direccion.'|'.$deno_cod_division.'|'.$deno_cod_departamento.'|'.$deno_cod_oficina;
                $filas_archivo        .=  '|'.$cod_puesto.'|'.$cod_cargo.'|'.$grado.'|'.$sexo.'|'.$nombre.'|'.$nacionalidad.'|'.$cedula_identidad.'|'.$estado_civil.'|'.$devolver_denominacion_puesto;
			    $filas_archivo        .=  '|'.$fecha_nacimiento.'|'.$fecha_ingreso.'|'.$sueldo_basico.'|'.$direccion_habitacion.'|'.$telefonos_habitacion.'|'.$cuenta_bancaria.'|'.$deno_prefesion.'|'.$edad;

			    $filas_archivo         = strtoupper_sisap($filas_archivo)."\n";
			}


		$this->wFile($nombre_archivo, $filas_archivo);
		$this->set('filas_archivo', $filas_archivo);





 }//fin else




$this->set('opcion', $var1);



}//fin function




function diskett_9($var1=null, $var2=null, $var3=null){

       if($var1==1){
           $this->layout = "ajax";

	           $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		       $this->concatena($lista, 'lista_nomina');
		       $this->Session->delete('tipo_nomina');

		       $consulta_c_t = $this->cnmd03_transaccion->generateList("cod_tipo_transaccion=1", 'cod_transaccion ASC', null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.denominacion');
	   	       $this->concatena($consulta_c_t, "lista");



  }else if($var1==2){

  	set_time_limit(0);
	ini_set("memory_limit","2000M");

           $this->layout  = "txt";
           $cod_presi     = $this->Session->read('SScodpresi');
		   $cod_entidad   = $this->Session->read('SScodentidad');
		   $cod_tipo_inst = $this->Session->read('SScodtipoinst');
		   $cod_inst      = $this->Session->read('SScodinst');
		   $cod_dep       = $this->Session->read('SScoddep');
           $cod_nomina                    = $this->data["cnmp06_diskett_historico"]["cod_nomina"];
           $sql               = $this->condicion()." and cod_tipo_nomina='".$cod_nomina."'  ";

           if($this->data["cnmp06_diskett_historico"]["tipo_deduccion"]==1){
                	$modelo = "ztxt_archivo_faov";
                }else{
                	$modelo = "ztxt_archivo_faov_ajuste";
                }

	       $p_1 = mascara($cod_dep,4).'_'.mascara($cod_nomina,3);
	       $nombre_archivo = 'Nomina_'.mascara($cod_dep,4).'_'.mascara($cod_nomina,3).'_'.date('d_m_Y_h:i:sa').'';
	       $_SESSION["nombre_txt"] = $nombre_archivo.".txt";
	       $data3                  = $this->cnmd08_historia_pen_ac->execute("SELECT * FROM $modelo WHERE ".$sql." ORDER BY cedula_identidad ASC");
	       $filas_archivo  = "";
	       /*var_dump($_SESSION);
			var_dump($this->data["cnmp06_diskett_historico"]);
			var_dump('cod_presi='.$cod_presi);
			var_dump('cod_entidad='.$cod_entidad);
			var_dump('cod_tipo_inst='.$cod_tipo_inst);
			var_dump('cod_inst='.$cod_inst);
			var_dump('cod_dep='.$cod_dep);
			var_dump('cod_nomina='.$cod_nomina);
			die();
			exit();*/
	       //$campo = array();
		   foreach($data3 as $rsdata){
		   	   extract($rsdata[0]);
               $campo = array();
		   	   $campo[] = $nacionalidad;
		   	   $campo[] = $cedula_identidad;
		   	   $campo[] = str_replace("\t",'',trim($primer_nombre));
		   	   $campo[] = str_replace("\t",'',trim($segundo_nombre));
		   	   $campo[] = str_replace("\t",'',trim($primer_apellido));
		   	   $campo[] = str_replace("\t",'',trim($segundo_apellido));
		   	   $campo[] = str_replace('.','',$sueldo_integral);
		   	   $campo[] = str_replace('/','',cambia_fecha($fecha_ingreso));
		   	   $campo[] = $fecha_egreso=='1900-01-01'?'':str_replace('/','',cambia_fecha($fecha_egreso));
		   	   $campos = implode(',',$campo);
               $filas_archivo .= $campos."\n";

			}
		$this->wFile($nombre_archivo, $filas_archivo);
		$this->set('filas_archivo', $filas_archivo);





 }//fin else




$this->set('opcion', $var1);



}//fin function diskett 9




function diskett_10($var1=null, $var2=null, $var3=null){

	//FAVO HISTORICO

       if($var1==1){
           $this->layout = "ajax";

	           $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		       $this->concatena($lista, 'lista_nomina');
		       $this->Session->delete('tipo_nomina');

		       $consulta_c_t = $this->cnmd03_transaccion->generateList("cod_tipo_transaccion=1", 'cod_transaccion ASC', null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.denominacion');
	   	       $this->concatena($consulta_c_t, "lista");



  }else if($var1==2){

  	set_time_limit(0);
	ini_set("memory_limit","2000M");

           $this->layout  = "txt";
           $cod_presi     = $this->Session->read('SScodpresi');
		   $cod_entidad   = $this->Session->read('SScodentidad');
		   $cod_tipo_inst = $this->Session->read('SScodtipoinst');
		   $cod_inst      = $this->Session->read('SScodinst');
		   $cod_dep       = $this->Session->read('SScoddep');
           $cod_nomina        = $this->data["cnmp06_diskett_historico"]["cod_nomina"];
           $ano_nomina        = $this->data["cnmp06_diskett_historico"]["ano_nomina"];
           $numero_nomina     = $this->data["cnmp06_diskett_historico"]["numero_nomina"];
//                $sql               = $this->condicion()." and cod_tipo_nomina='".$cod_nomina."'  ";
/**/
                if($this->data["cnmp06_diskett_historico"]["tipo_deduccion"]==1){
                	$modelo = "ztxt_archivo_faov_historico";
                }else{
                	$modelo = "ztxt_archivo_faov_historico_ajuste";
                }/**/

	       $sql               = $this->condicion()." and cod_tipo_nomina='".$cod_nomina."' and ano=$ano_nomina and numero_nomina=$numero_nomina ";
	       $nombre_archivo         = 'Nomina_'.mascara($cod_dep,4).'_'.mascara($cod_nomina,3).'_'.$ano_nomina.'_'.$numero_nomina.'_'.date('d_m_Y_h:i:sa').'';
	       $_SESSION["nombre_txt"] = $nombre_archivo.".txt";
	       $data3                  = $this->cnmd08_historia_pen_ac->execute("SELECT * FROM $modelo WHERE ".$sql." ORDER BY cedula_identidad ASC");
	       $filas_archivo  = "";
	       //$campo = array();
		   foreach($data3 as $rsdata){
		   	   extract($rsdata[0]);
               $campo = array();
		   	   $campo[] = $nacionalidad;
		   	   $campo[] = $cedula_identidad;
		   	   $campo[] = str_replace("\t",'',trim($primer_nombre));
		   	   $campo[] = str_replace("\t",'',trim($segundo_nombre));
		   	   $campo[] = str_replace("\t",'',trim($primer_apellido));
		   	   $campo[] = str_replace("\t",'',trim($segundo_apellido));
		   	   $campo[] = str_replace('.','',$sueldo_integral);
		   	   $campo[] = str_replace('/','',cambia_fecha($fecha_ingreso));
		   	   $campo[] = $fecha_egreso=='1900-01-01'?'':str_replace('/','',cambia_fecha($fecha_egreso));
		   	   $campos = implode(',',$campo);
               $filas_archivo .= $campos."\n";
			}
		$this->wFile($nombre_archivo, $filas_archivo);
		$this->set('filas_archivo', $filas_archivo);





 }//fin else




$this->set('opcion', $var1);
 $this->render('diskett_10_mensual');


}//fin function 10


function diskett_10_faov_deuda($var1=null, $var2=null, $var3=null){

	//FAVO HISTORICO

       if($var1==1){
           $this->layout = "ajax";

	           $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		       $this->concatena($lista, 'lista_nomina');
		       $this->Session->delete('tipo_nomina');

		       $consulta_c_t = $this->cnmd03_transaccion->generateList("cod_tipo_transaccion=1", 'cod_transaccion ASC', null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.denominacion');
	   	       $this->concatena($consulta_c_t, "lista");



  }else if($var1==2){

  	set_time_limit(0);
	ini_set("memory_limit","2000M");

           $this->layout  = "txt";
           $cod_presi     = $this->Session->read('SScodpresi');
		   $cod_entidad   = $this->Session->read('SScodentidad');
		   $cod_tipo_inst = $this->Session->read('SScodtipoinst');
		   $cod_inst      = $this->Session->read('SScodinst');
		   $cod_dep       = $this->Session->read('SScoddep');
           $cod_nomina        = $this->data["cnmp06_diskett_historico"]["cod_nomina"];
           $ano_nomina        = $this->data["cnmp06_diskett_historico"]["ano_nomina"];
           $numero_nomina     = $this->data["cnmp06_diskett_historico"]["numero_nomina"];
//                $sql               = $this->condicion()." and cod_tipo_nomina='".$cod_nomina."'  ";
/**/
                if($this->data["cnmp06_diskett_historico"]["tipo_deduccion"]==1){
                	$modelo = "ztxt_archivo_faov_h_deuda";
                }else{
                	$modelo = "ztxt_archivo_faov_h_deuda_ajus";
                }/**/

	       $sql               = $this->condicion()." and cod_tipo_nomina='".$cod_nomina."' and ano=$ano_nomina and numero_nomina=$numero_nomina ";
	       $nombre_archivo         = 'Nomina_'.mascara($cod_dep,4).'_'.mascara($cod_nomina,3).'_'.$ano_nomina.'_'.$numero_nomina.'_'.date('d_m_Y_h:i:sa').'';
	       $_SESSION["nombre_txt"] = $nombre_archivo.".txt";
	       $data3                  = $this->cnmd08_historia_pen_ac->execute("SELECT * FROM $modelo WHERE ".$sql." ORDER BY cedula_identidad ASC");
	       $filas_archivo  = "";
	       //$campo = array();
		   foreach($data3 as $rsdata){
		   	   extract($rsdata[0]);
               $campo = array();
		   	   $campo[] = $nacionalidad;
		   	   $campo[] = $cedula_identidad;
		   	   $campo[] = str_replace("\t",'',trim($primer_nombre));
		   	   $campo[] = str_replace("\t",'',trim($segundo_nombre));
		   	   $campo[] = str_replace("\t",'',trim($primer_apellido));
		   	   $campo[] = str_replace("\t",'',trim($segundo_apellido));
		   	   $campo[] = str_replace('.','',$deuda);
		   	   $campo[] = str_replace('/','',cambia_fecha($fecha_ingreso));
		   	   $campo[] = $fecha_egreso=='1900-01-01'?'':str_replace('/','',cambia_fecha($fecha_egreso));
		   	   $campos = implode(',',$campo);
               $filas_archivo .= $campos."\n";
			}
		$this->wFile($nombre_archivo, $filas_archivo);
		$this->set('filas_archivo', $filas_archivo);





 }//fin else




$this->set('opcion', $var1);
 $this->render('diskett_10_faov_deuda');


}//fin diskett_10_faov_deuda


function diskett_10_mensual($var1=null, $var2=null, $var3=null){

	//FAVO HISTORICO

       if($var1==1){
           $this->layout = "ajax";

	           $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		       $this->concatena($lista, 'lista_nomina');
		       $this->Session->delete('tipo_nomina');

		       $consulta_c_t = $this->cnmd03_transaccion->generateList("cod_tipo_transaccion=1", 'cod_transaccion ASC', null, '{n}.cnmd03_transaccion.cod_transaccion', '{n}.cnmd03_transaccion.denominacion');
	   	       $this->concatena($consulta_c_t, "lista");



  }else if($var1==2){

  	set_time_limit(0);
	ini_set("memory_limit","2000M");

           $this->layout  = "txt";
           $cod_presi     = $this->Session->read('SScodpresi');
		   $cod_entidad   = $this->Session->read('SScodentidad');
		   $cod_tipo_inst = $this->Session->read('SScodtipoinst');
		   $cod_inst      = $this->Session->read('SScodinst');
		   $cod_dep       = $this->Session->read('SScoddep');
           $cod_nomina        = $this->data["cnmp06_diskett_historico"]["cod_nomina"];
           $ano_nomina        = $this->data["cnmp06_diskett_historico"]["ano_nomina"];
           $mes_nomina     = $this->data["cnmp06_diskett_historico"]["mes_nomina"];
//                $sql               = $this->condicion()." and cod_tipo_nomina='".$cod_nomina."'  ";
/**/
                if($this->data["cnmp06_diskett_historico"]["tipo_deduccion"]==1){
                	$modelo = "ztxt_archivo_faov_historico_mensual2";
                }else{
                	$modelo = "ztxt_archivo_faov_historico_ajuste";
                }/**/

	       $sql               = $this->condicion()." and cod_tipo_nomina='".$cod_nomina."' and ano=$ano_nomina and mes='$mes_nomina' ";
	       $nombre_archivo         = 'Nomina_'.mascara($cod_dep,4).'_'.mascara($cod_nomina,3).'_'.$ano_nomina.'_'.$mes_nomina.'_'.date('d_m_Y_h:i:sa').'';
	       $_SESSION["nombre_txt"] = $nombre_archivo.".txt";
	       $data3                  = $this->cnmd08_historia_pen_ac->execute("SELECT * FROM $modelo WHERE ".$sql." ORDER BY cedula_identidad ASC");
	       $filas_archivo  = "";
	       //$campo = array();
		   foreach($data3 as $rsdata){
		   	   extract($rsdata[0]);
               $campo = array();
		   	   $campo[] = $nacionalidad;
		   	   $campo[] = $cedula_identidad;
		   	   $campo[] = str_replace("\t",'',trim($primer_nombre));
		   	   $campo[] = str_replace("\t",'',trim($segundo_nombre));
		   	   $campo[] = str_replace("\t",'',trim($primer_apellido));
		   	   $campo[] = str_replace("\t",'',trim($segundo_apellido));
		   	   $campo[] = str_replace('.','',$sueldo_integral);
		   	   $campo[] = str_replace('/','',cambia_fecha($fecha_ingreso));
		   	   $campo[] = $fecha_egreso=='1900-01-01'?'':str_replace('/','',cambia_fecha($fecha_egreso));
		   	   $campos = implode(',',$campo);
               $filas_archivo .= $campos."\n";
			}
		$this->wFile($nombre_archivo, $filas_archivo);
		$this->set('filas_archivo', $filas_archivo);





 }//fin else




$this->set('opcion', $var1);



}//fin function 10











function seleccion_dep_nomina($var=null){

$this->layout = "ajax";

               $lista = $this->Cnmd01->generateListTodos($conditions = $this->condicionNDEP()." and cod_dep='".$var."'  ", $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
		       $this->concatena($lista, 'lista_nomina');
		       $this->Session->delete('tipo_nomina');

}


function diskett_fdj($var1=null, $formato=null){

  if($var1==1){

	$this->layout = "ajax";


  }else if($var1==2){


	if((int)$formato==1){

		// *****  VA   AL   .TXT  *****

  	set_time_limit(0);
	ini_set("memory_limit","2000M");

	$this->layout  = "txt";
	$cod_dep = $this->Session->read('SScoddep');

		$datos=$this->data["cnmd17_fideicomiso"];
		$tipo_personal = $datos["tipo_personal"];
		$ano_nomina = $datos["ano_nomina"];
		$mes_nomina = $datos["mes_nomina"];

if($tipo_personal!=null && $ano_nomina!=null && $mes_nomina!=null){
	$nombre_archivo = 'FondoJubilacion'.mascara($cod_dep,4).'_'.mascara($tipo_personal,2).'_'.date('d_m_Y_h:i:sa').'';
	$_SESSION["nombre_txt"] = $nombre_archivo.".txt";
	$filas_archivo = "";
	$datos_fdj = $this->v_cnmd08_fdj_historia->findAll($this->condicion()." AND tipo_personal='$tipo_personal' AND ano='$ano_nomina' AND mes='$mes_nomina'", null, null);
		   foreach($datos_fdj as $rsdata){
		   	   extract($rsdata['v_cnmd08_fdj_historia']);
		   	   $campo = array();
		   	   $campo[] = $cedula_identidad;
		   	   $campo[] = $periodo_cancelar;
		   	   $campo[] = str_replace("\t",'',trim($nombre));
		   	   $campo[] = $codigo_asignado;
		   	   $campo[] = $sueldo;
		   	   $campo[] = $aporte;
		   	   $campo[] = $cotizacion;
		   	   $campos = implode('|',$campo);
               $filas_archivo .= $campos."\n";

			}

		$this->wFile($nombre_archivo, $filas_archivo);
		$this->set('filas_archivo', $filas_archivo);
}




  }else if((int)$formato==2){

		// *****  VA   AL   .PDF  *****

  	set_time_limit(0);
	ini_set("memory_limit","2000M");

	$this->layout  = "pdf";
	$cod_dep = $this->Session->read('SScoddep');

		$datos=$this->data["cnmd17_fideicomiso"];
		$tipo_personal = $datos["tipo_personal"];
		$ano_nomina = $datos["ano_nomina"];
		$mes_nomina = $datos["mes_nomina"];
		$nombre_archivo = 'FondoJubilacion'.mascara($cod_dep,4).'_'.mascara($tipo_personal,2).'_'.date('d_m_Y_h:i:sa').'';

		if($tipo_personal!=null && $ano_nomina!=null && $mes_nomina!=null){
			$datos_fdj = $this->v_cnmd08_fdj_historia->findAll($this->condicion()." AND tipo_personal='$tipo_personal' AND ano='$ano_nomina' AND mes='$mes_nomina'", null, null);
			$this->set('datos_fdj', $datos_fdj);
		}else{
			$this->set('datos_fdj', array());
		}
		$this->set('tipo_personal', $tipo_personal);
		$this->set('nombre_archivo', $nombre_archivo);
	}

}//fin else if var1=2

	$this->set('opcion', $var1); // var patron para ir a la   vista::1  o  a  Impresion::2
	$this->set('formato', $formato); // var patron para ir a    txt::1  o  al       pdf::2

}//fin function archivo_pfide


function anos_nomina_fdj($vtipo_personal=null){
	$this->layout = "ajax";
  	if($vtipo_personal!=null){
		$anos_fdj = $this->v_cnmd08_fdj_historia->generateList($this->condicion()." AND tipo_personal='$vtipo_personal'", 'ano ASC', null, '{n}.v_cnmd08_fdj_historia.ano', '{n}.v_cnmd08_fdj_historia.ano');
		if(!empty($anos_fdj)){
			$this->set('anos_fdj', $anos_fdj);
		}else{
			$this->set('anos_fdj', array());
		}
		$this->set('vtipo_personal', $vtipo_personal);
		$tipo = $vtipo_personal=='1'?'EMPLEADOS':'OBREROS';
		echo "<script>
					document.getElementById('stipo_personal').innerHTML='$tipo';
					document.getElementById('fcrear_archivo').disabled=true;
				</script>";
  	}else{
  		$this->set('anos_fdj', array());
  		echo "<script>
  					document.getElementById('stipo_personal').innerHTML='';
  					document.getElementById('fcrear_archivo').disabled=true;
  				</script>";
  	}
}


function mes_nomina_fdj($vtipo_personal=null, $vano_nomina=null){
	$this->layout = "ajax";
  	if($vano_nomina!=null){
		$meses_fdj = $this->v_cnmd08_fdj_historia->generateList($this->condicion()." AND tipo_personal='$vtipo_personal' AND ano='$vano_nomina'", 'mes ASC', null, '{n}.v_cnmd08_fdj_historia.mes', '{n}.v_cnmd08_fdj_historia.deno_mes');
		if(!empty($meses_fdj)){
			$this->concatena($meses_fdj, "meses_fdj");
		}else{
			$this->set('meses_fdj', array());
		}
  	}else{
  		$this->set('meses_fdj', array());
  	}
  		echo "<script>
  					document.getElementById('fcrear_archivo').disabled=false;
  				</script>";
}

}//fin class


function limpiar_datosPersonales($string_datos){
 		
    $nombre_completo   = $string_datos;
	$nombre_completo   = str_replace('  ',' ',trim($nombre_completo));
	$nombre_completo   = str_replace("\t",' ',$nombre_completo);
	$nombre_completo   = str_replace("Ñ",'@',$nombre_completo);
	$nombre            = elimina_acentos($nombre_completo);
	$nombre            = str_replace("@",'Ñ',$nombre);

	return $nombre;
}

?>