<?php

 class Canp00GraficoController extends AppController {
   var $name = 'canp00_grafico';
   var $uses = array('v_cfpd05_tipo_gasto2', 'ccfd04_cierre_mes', 'arrd01', 'arrd02', 'arrd03', 'arrd04', 'arrd05', 'cfpd05',
                      'v_balance_ejecucion', 'cfpd07_plan_inversion');
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












function asignacion_tipo_gasto($var=null){

	$username = $this->Session->read('nom_usuario');
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

if($var==1){

	    $this->layout="ajax";


			$datos  = $this->v_cfpd05_tipo_gasto2->execute(" SELECT DISTINCT ano  FROM cfpd05 ");
			$datos2 = $this->arrd01->execute(" SELECT DISTINCT ano_arranque    FROM ccfd03_instalacion  ORDER BY ano_arranque DESC");

						if(count($datos)!=0){
							foreach($datos as $n){
								$cod[]  = $n[0]['ano'];
								$deno[] = $n[0]['ano'];
							}
							$lista=array_combine($cod, $deno);
						}else{
							$lista=array('0'=>'No se encuentra presente en ninguna nomina');
						}

					$this->set("ano_lista", $lista);


			$this->set("ano_ejecucion" , $datos2[0][0]["ano_arranque"]);

			        $lista =  $this->arrd01->generateList(null, 'denominacion ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.denominacion');
		            $this->set('vector_presi', $lista);
		            $this->set("cod_presi_seleccion", 1);



}else if($var==2){

	      $this->layout="ajax";
	      $sql = "";

	      $group_by = " GROUP BY ";
	      $tipo=0;
	      $deno_presi   = "";
	      $deno_entidad = "";
	      $deno_inst    = "";
	      $deno_tipo_inst = "";

	                                 if(!empty($this->data["datos"]["cod_presi"])){
									  	if($this->data["datos"]["cod_presi"]!="TODO"){
						                          $sql      .= " cod_presi = '".$this->data["datos"]["cod_presi"]."' ";
						                          $group_by .= " cod_presi";

						                          $lista_1    =  $this->arrd01->findAll("cod_presi=".$this->data["datos"]["cod_presi"]);
						                          $deno_presi = $lista_1[0]["arrd01"]["denominacion"];
									  	}else{
									  		     $sql .= " 1=1 ";

									  	}
									  }

                                      if(!empty($this->data["datos"]["cod_entidad"])){
									  	if($this->data["datos"]["cod_entidad"]!="TODO"){
						                          $sql .=" and cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";
						                          $group_by .= ", cod_entidad";
						                          $tipo = 1;

						                          $lista_2      =  $this->arrd02->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]);
						                          $deno_entidad = $lista_2[0]["arrd02"]["denominacion"];
									  	}
									  }

                                       if(!empty($this->data["datos"]["cod_tipo_inst"])){
									  	if($this->data["datos"]["cod_tipo_inst"]!="TODO"){
						                          $sql .=" and cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";
						                          $group_by .= ", cod_tipo_inst";
						                          $tipo = 2;

						                          $lista_3        =  $this->arrd03->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_tipo_inst=".$this->data["datos"]["cod_tipo_inst"]);
						                          $deno_tipo_inst = $lista_3[0]["arrd03"]["denominacion"];
									  	 }
                                       }

                                      if(!empty($this->data["datos"]["cod_inst"])){
									  	if($this->data["datos"]["cod_inst"]!="TODO"){
						                          $sql .=" and cod_inst = '".$this->data["datos"]["cod_inst"]."' ";
						                          $group_by .= ", cod_inst";
						                          $tipo = 3;

						                          $lista_4   =  $this->arrd04->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]." and cod_inst=".$this->data["datos"]["cod_inst"]);
						                          $deno_inst = $lista_4[0]["arrd04"]["denominacion"];
									  	}
									  }
									  if(!empty($this->data["datos"]["ano_consolidado"])){
									  	if($this->data["datos"]["ano_consolidado"]!="TODO"){
						                          $sql .=" and ano = '".$this->data["datos"]["ano_consolidado"]."' ";
						                          $group_by .= ", ano";
									  	}
									  }

                 $condicion  = $sql.$group_by;
				 $fields     = "SUM(gasto_inversion) as gasto_inversion, SUM(gasto_corriente) as gasto_corriente, SUM(total) as total";



			$datos = $this->v_cfpd05_tipo_gasto2->findAll($condicion, $fields, $order = null, $limit = null, $page = null, $recursive = null);


			$this->set('datos',    $datos);
			$this->set('tipo_top', $tipo);
			$this->set('year',     $this->data["datos"]["ano_consolidado"]);





			$this->set('DENO_ESTADO', $deno_entidad);
			$this->set('DENO_COD_TIPO_INST', $deno_tipo_inst);
			$this->set('DENO_INST', $deno_inst);
			$this->set('DENO_REPUBLICA', $deno_presi);

			echo"<script>document.getElementById('ir').disabled=false; </script>";





}else if($var==3){

                $this->layout="pdf";
				$username = $this->Session->read('nom_usuario');
				$this->set('user', $username);
				$gasto_inversion = $this->data['tipo_gastoPDF']['gasto_inversion'];
				$por_gasto_inversion = $this->data['tipo_gastoPDF']['por_gasto_inversion'];
				$gasto_corriente = $this->data['tipo_gastoPDF']['gasto_corriente'];
				$por_gasto_corriente = $this->data['tipo_gastoPDF']['por_gasto_corriente'];
				$total = $this->data['tipo_gastoPDF']['total'];
				$rdm = $this->data['tipo_gastoPDF']['rdm'];
				$year = $this->data["tipo_gastoPDF"]["year"];
				$tipo_top = $this->data["tipo_gastoPDF"]["tipo_top"];

				$this->set('gasto_inversion', $gasto_inversion);
				$this->set('gasto_corriente', $gasto_corriente);
				$this->set('por_gasto_inversion', $por_gasto_inversion);
				$this->set('por_gasto_corriente', $por_gasto_corriente);
				$this->set('total', $total);
				$this->set('rdm', $rdm);
				$this->set('year', $year);
				$this->set('tipo_top', $tipo_top);

				$this->set('DENO_ESTADO',        $this->data["tipo_gastoPDF"]["DENO_ESTADO"]);
			    $this->set('DENO_COD_TIPO_INST', $this->data["tipo_gastoPDF"]["DENO_COD_TIPO_INST"]);
			    $this->set('DENO_INST',          $this->data["tipo_gastoPDF"]["DENO_INST"]);
			    $this->set('DENO_REPUBLICA',     $this->data["tipo_gastoPDF"]["DENO_REPUBLICA"]);

}//fin else




$this->set('var', $var);



}//fin function












function asignacion_tipo_presupuesto($var=null){

	$username = $this->Session->read('nom_usuario');
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

if($var==1){

	    $this->layout="ajax";


			$datos  = $this->v_cfpd05_tipo_gasto2->execute(" SELECT DISTINCT ano  FROM cfpd05 ");
			$datos2 = $this->arrd01->execute(" SELECT DISTINCT ano_arranque    FROM ccfd03_instalacion  ORDER BY ano_arranque DESC");

						if(count($datos)!=0){
							foreach($datos as $n){
								$cod[]  = $n[0]['ano'];
								$deno[] = $n[0]['ano'];
							}
							$lista=array_combine($cod, $deno);
						}else{
							$lista=array('0'=>'No se encuentra presente en ninguna nomina');
						}

					$this->set("ano_lista", $lista);


			$this->set("ano_ejecucion" , $datos2[0][0]["ano_arranque"]);
			        $lista =  $this->arrd01->generateList(null, 'denominacion ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.denominacion');
		            $this->set('vector_presi', $lista);
		            $this->set("cod_presi_seleccion", 1);



}else if($var==2){

	      $this->layout="ajax";
	      $sql = "";

	      $group_by = " GROUP BY ";
	      $tipo=0;
	      $deno_presi   = "";
	      $deno_entidad = "";
	      $deno_inst    = "";
	      $deno_tipo_inst = "";

	      $sql_year_sub   = "";
	      $sql_year_sub_2 = "";
	      $sql_where_sub  = "";

	                                 if(!empty($this->data["datos"]["cod_presi"])){
									  	if($this->data["datos"]["cod_presi"]!="TODO"){
						                          $sql      .= " a.cod_presi = '".$this->data["datos"]["cod_presi"]."' ";
						                          $group_by .= " a.cod_presi";
						                          $sql_where_sub .= " x.cod_presi=a.cod_presi and ";

						                          $lista_1    =  $this->arrd01->findAll("cod_presi=".$this->data["datos"]["cod_presi"]);
						                          $deno_presi = $lista_1[0]["arrd01"]["denominacion"];
									  	}else{
									  		     $sql .= " 1=1 ";

									  	}
									  }

                                      if(!empty($this->data["datos"]["cod_entidad"])){
									  	if($this->data["datos"]["cod_entidad"]!="TODO"){
						                          $sql .=" and a.cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";
						                          $group_by .= ", a.cod_entidad";
						                          $tipo = 1;
						                          $sql_where_sub .= " x.cod_entidad=a.cod_entidad and ";

						                          $lista_2      =  $this->arrd02->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]);
						                          $deno_entidad = $lista_2[0]["arrd02"]["denominacion"];
									  	}
									  }

                                       if(!empty($this->data["datos"]["cod_tipo_inst"])){
									  	if($this->data["datos"]["cod_tipo_inst"]!="TODO"){
						                          $sql .=" and a.cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";
						                          $group_by .= ", a.cod_tipo_inst";
						                          $tipo = 2;
						                          $sql_where_sub .= " x.cod_tipo_inst=a.cod_tipo_inst and ";

						                          $lista_3        =  $this->arrd03->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_tipo_inst=".$this->data["datos"]["cod_tipo_inst"]);
						                          $deno_tipo_inst = $lista_3[0]["arrd03"]["denominacion"];
									  	 }
                                       }

                                      if(!empty($this->data["datos"]["cod_inst"])){
									  	if($this->data["datos"]["cod_inst"]!="TODO"){
						                          $sql .=" and a.cod_inst = '".$this->data["datos"]["cod_inst"]."' ";
						                          $group_by .= ", a.cod_inst";
						                          $tipo = 3;
						                          $sql_where_sub .= " x.cod_inst=a.cod_inst and ";

						                          $lista_4   =  $this->arrd04->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]." and cod_inst=".$this->data["datos"]["cod_inst"]);
						                          $deno_inst = $lista_4[0]["arrd04"]["denominacion"];
									  	}
									  }
									  if(!empty($this->data["datos"]["ano_consolidado"])){
									  	if($this->data["datos"]["ano_consolidado"]!="TODO"){
						                          $sql .=" and a.ano = '".$this->data["datos"]["ano_consolidado"]."' ";
						                          $group_by .= ", a.ano";
						                          $sql_year_sub_2 = "     x.ano= ".$this->data["datos"]["ano_consolidado"]." and ";
						                          $sql_year_sub   = " and x.ano= ".$this->data["datos"]["ano_consolidado"];
									  	}
									  }

                 if($sql_where_sub!="" || $sql_year_sub_2!=""){
                    $sql_asignacion_total = " WHERE ".$sql_where_sub." ".$sql_year_sub_2."  1=1 ";
                 }else{
                    $sql_asignacion_total = "";
                 }

                 $condicion        = $sql.$group_by;
				 $tipo_presupuesto = $this->cfpd05->execute("SELECT
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub."  x.tipo_presupuesto=1 ".$sql_year_sub.") as ordinario,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub."  x.tipo_presupuesto=2 ".$sql_year_sub.") as coordinado,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub."  x.tipo_presupuesto=3 ".$sql_year_sub.") as laee,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub."  x.tipo_presupuesto=4 ".$sql_year_sub.") as fides,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub."  x.tipo_presupuesto=5 ".$sql_year_sub.") as ingresos_extra,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x ".$sql_asignacion_total.") as asignacion_total
															FROM cfpd05 a

															WHERE ".$condicion.";
														   ");


			$this->set('tipo_presupuesto',$tipo_presupuesto);
			$this->set('tipo_top', $tipo);
			$this->set('year',     $this->data["datos"]["ano_consolidado"]);





			$this->set('DENO_ESTADO', $deno_entidad);
			$this->set('DENO_COD_TIPO_INST', $deno_tipo_inst);
			$this->set('DENO_INST', $deno_inst);
			$this->set('DENO_REPUBLICA', $deno_presi);

			echo"<script>document.getElementById('ir').disabled=false; </script>";





}else if($var==3){

	       $this->layout="pdf";

				$username = $this->Session->read('nom_usuario');
				$this->set('user', $username);
				$ordinario         = $this->data["tipo_presupuetoPDF"]["ordinario"];
				$coordinado        = $this->data["tipo_presupuetoPDF"]["coordinado"];
				$laee              = $this->data["tipo_presupuetoPDF"]["laee"];
				$fides             = $this->data["tipo_presupuetoPDF"]["fides"];
				$ingresos_extra    = $this->data["tipo_presupuetoPDF"]["ingresos_extra"];
				$total_presupuesto =  $this->data["tipo_presupuetoPDF"]["total_presupuesto"];
				$por_ordinario         = $this->data["tipo_presupuetoPDF"]["por_ordinario"];
				$por_coordinado        = $this->data["tipo_presupuetoPDF"]["por_coordinado"];
				$por_laee              = $this->data["tipo_presupuetoPDF"]["por_laee"];
				$por_fides             = $this->data["tipo_presupuetoPDF"]["por_fides"];
				$por_ingresos_extra    = $this->data["tipo_presupuetoPDF"]["por_ingresos_extra"];
				$tipo_top              = $this->data["tipo_presupuetoPDF"]["tipo_top"];
				$rdm = $this->data["tipo_presupuetoPDF"]["rdm"];
				$year = $this->data["tipo_presupuetoPDF"]["year"];
				$this->set('ordinario', $ordinario);
				$this->set('coordinado', $coordinado);
				$this->set('laee', $laee);
				$this->set('fides', $fides);
				$this->set('ingresos_extra', $ingresos_extra);
				$this->set('por_ordinario', $por_ordinario);
				$this->set('por_coordinado', $por_coordinado);
				$this->set('por_laee', $por_laee);
				$this->set('por_fides', $por_fides);
				$this->set('por_ingresos_extra', $por_ingresos_extra);
				$this->set('total_presupuesto', $total_presupuesto);
				$this->set('rdm', $rdm);
				$this->set('year', $year);
				$this->set('tipo_top', $tipo_top);

				$this->set('DENO_ESTADO',        $this->data["tipo_presupuetoPDF"]["DENO_ESTADO"]);
			    $this->set('DENO_COD_TIPO_INST', $this->data["tipo_presupuetoPDF"]["DENO_COD_TIPO_INST"]);
			    $this->set('DENO_INST',          $this->data["tipo_presupuetoPDF"]["DENO_INST"]);
			    $this->set('DENO_REPUBLICA',     $this->data["tipo_presupuetoPDF"]["DENO_REPUBLICA"]);


}//fin else




$this->set('var', $var);



}//fin function



















function asignacion_tipo_partida($var=null){

	$username = $this->Session->read('nom_usuario');
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

if($var==1){

	    $this->layout="ajax";


			$datos  = $this->v_cfpd05_tipo_gasto2->execute(" SELECT DISTINCT ano  FROM cfpd05 ");
			$datos2 = $this->arrd01->execute(" SELECT DISTINCT ano_arranque    FROM ccfd03_instalacion  ORDER BY ano_arranque DESC");

						if(count($datos)!=0){
							foreach($datos as $n){
								$cod[]  = $n[0]['ano'];
								$deno[] = $n[0]['ano'];
							}
							$lista=array_combine($cod, $deno);
						}else{
							$lista=array('0'=>'No se encuentra presente en ninguna nomina');
						}

					$this->set("ano_lista", $lista);


			$this->set("ano_ejecucion" , $datos2[0][0]["ano_arranque"]);

			        $lista =  $this->arrd01->generateList(null, 'denominacion ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.denominacion');
		            $this->set('vector_presi', $lista);
		            $this->set("cod_presi_seleccion", 1);



}else if($var==2){

	      $this->layout="ajax";
	      $sql = "";

	      $group_by = " GROUP BY ";
	      $tipo=0;
	      $deno_presi   = "";
	      $deno_entidad = "";
	      $deno_inst    = "";
	      $deno_tipo_inst = "";

	      $sql_year_sub   = "";
	      $sql_year_sub_2 = "";
	      $sql_where_sub  = "";

	                                 if(!empty($this->data["datos"]["cod_presi"])){
									  	if($this->data["datos"]["cod_presi"]!="TODO"){
						                          $sql      .= " a.cod_presi = '".$this->data["datos"]["cod_presi"]."' ";
						                          $group_by .= " a.cod_presi";
						                          $sql_where_sub .= " x.cod_presi=a.cod_presi and ";

						                          $lista_1    =  $this->arrd01->findAll("cod_presi=".$this->data["datos"]["cod_presi"]);
						                          $deno_presi = $lista_1[0]["arrd01"]["denominacion"];
									  	}else{
									  		     $sql .= " 1=1 ";

									  	}
									  }

                                      if(!empty($this->data["datos"]["cod_entidad"])){
									  	if($this->data["datos"]["cod_entidad"]!="TODO"){
						                          $sql .=" and a.cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";
						                          $group_by .= ", a.cod_entidad";
						                          $tipo = 1;
						                          $sql_where_sub .= " x.cod_entidad=a.cod_entidad and ";

						                          $lista_2      =  $this->arrd02->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]);
						                          $deno_entidad = $lista_2[0]["arrd02"]["denominacion"];
									  	}
									  }

                                       if(!empty($this->data["datos"]["cod_tipo_inst"])){
									  	if($this->data["datos"]["cod_tipo_inst"]!="TODO"){
						                          $sql .=" and a.cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";
						                          $group_by .= ", a.cod_tipo_inst";
						                          $tipo = 2;
						                          $sql_where_sub .= " x.cod_tipo_inst=a.cod_tipo_inst and ";

						                          $lista_3        =  $this->arrd03->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_tipo_inst=".$this->data["datos"]["cod_tipo_inst"]);
						                          $deno_tipo_inst = $lista_3[0]["arrd03"]["denominacion"];
									  	 }
                                       }

                                      if(!empty($this->data["datos"]["cod_inst"])){
									  	if($this->data["datos"]["cod_inst"]!="TODO"){
						                          $sql .=" and a.cod_inst = '".$this->data["datos"]["cod_inst"]."' ";
						                          $group_by .= ", a.cod_inst";
						                          $tipo = 3;
						                          $sql_where_sub .= " x.cod_inst=a.cod_inst and ";

						                          $lista_4   =  $this->arrd04->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]." and cod_inst=".$this->data["datos"]["cod_inst"]);
						                          $deno_inst = $lista_4[0]["arrd04"]["denominacion"];
									  	}
									  }
									  if(!empty($this->data["datos"]["ano_consolidado"])){
									  	if($this->data["datos"]["ano_consolidado"]!="TODO"){
						                          $sql .=" and a.ano = '".$this->data["datos"]["ano_consolidado"]."' ";
						                          $group_by .= ", a.ano";
						                          $sql_year_sub_2 = "     x.ano= ".$this->data["datos"]["ano_consolidado"]." and ";
						                          $sql_year_sub   = " and x.ano= ".$this->data["datos"]["ano_consolidado"];
									  	}
									  }

                 if($sql_where_sub!="" || $sql_year_sub_2!=""){
                    $sql_asignacion_total = " WHERE ".$sql_where_sub." ".$sql_year_sub_2."  1=1 ";
                 }else{
                    $sql_asignacion_total = "";
                 }

                 $condicion        = $sql.$group_by;
				 $tipo_partida     = $this->cfpd05->execute("SELECT

																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_partida=401 ".$sql_year_sub.") as partida_401,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_partida=402 ".$sql_year_sub.") as partida_402,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_partida=403 ".$sql_year_sub.") as partida_403,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_partida=404 ".$sql_year_sub.") as partida_404,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_partida=405 ".$sql_year_sub.") as partida_405,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_partida=406 ".$sql_year_sub.") as partida_406,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_partida=407 ".$sql_year_sub.") as partida_407,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_partida=408 ".$sql_year_sub.") as partida_408,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_partida=409 ".$sql_year_sub.") as partida_409,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_partida=410 ".$sql_year_sub.") as partida_410,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_partida=411 ".$sql_year_sub.") as partida_411,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_partida=412 ".$sql_year_sub.") as partida_412,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x WHERE ".$sql_where_sub." x.cod_partida=498 ".$sql_year_sub.") as partida_498,
																(SELECT SUM(x.asignacion_anual+((x.aumento_traslado_anual+x.credito_adicional_anual)-(x.disminucion_traslado_anual+x.rebaja_anual))) FROM cfpd05 x ".$sql_asignacion_total.") as asignacion_total
																FROM cfpd05 a

																WHERE ".$condicion.";");


        $_SESSION["CONDICIONPDF"] = $sql;
		$rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_partida, a.deno_partida FROM v_cfpd05_denominaciones a WHERE ".$sql." ");
		    foreach($rs as $l){
				$v[]=$l[0]["cod_partida"];
				$d[]=$l[0]["deno_partida"];
			}
			if(isset($v)){$PARTIDA = array_combine($v, $d); }else{ $PARTIDA = array();}
			$this->set("PARTIDA", $PARTIDA);


			$this->set('tipo_partida',$tipo_partida);
			$this->set('tipo_top', $tipo);
			$this->set('year',     $this->data["datos"]["ano_consolidado"]);





			$this->set('DENO_ESTADO', $deno_entidad);
			$this->set('DENO_COD_TIPO_INST', $deno_tipo_inst);
			$this->set('DENO_INST', $deno_inst);
			$this->set('DENO_REPUBLICA', $deno_presi);

			echo"<script>document.getElementById('ir').disabled=false; </script>";





}else if($var==3){

	       $this->layout="pdf";

				$username = $this->Session->read('nom_usuario');
				$this->set('user', $username);
				$kk=$_SESSION["vector_partidas"];
				$year = $this->data["tipo_presupuetoPDF"]["year"];
				$this->set("KK",$kk);
				for($i=0;$i<count($kk);$i++){
					$partida[$kk[$i]]         = $this->data["tipo_presupuetoPDF"]["partida_".$kk[$i]];
					$por_partida[$kk[$i]]     = $this->data["tipo_presupuetoPDF"]["por_partida_".$kk[$i]];
				}
				$total_presupuesto =  $this->data["tipo_presupuetoPDF"]["total_presupuesto_partida"];
				$rdm = $this->data["tipo_presupuetoPDF"]["rdm"];
				$con=$_SESSION["CONDICIONPDF"];
			    $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_partida,a.deno_partida FROM v_cfpd05_denominaciones a WHERE ". $con);
			    foreach($rs as $l){
					$v[]=$l[0]["cod_partida"];
					$d[]=$l[0]["deno_partida"];
				}
				if(isset($v)){$PARTIDA = array_combine($v, $d); }else{ $PARTIDA = array();}
				if(!isset($partida)){$partida = array();}
				if(!isset($por_partida)){$por_partida = array();}
				$this->set("PARTIDA", $PARTIDA);
				$this->set('partida', $partida);
				$this->set('por_partida', $por_partida);
				$this->set('total_presupuesto', $total_presupuesto);
				$this->set('rdm', $rdm);
				$this->set('year', $year);

				$this->set('tipo_top',           $this->data["tipo_presupuetoPDF"]["tipo_top"]);
				$this->set('DENO_ESTADO',        $this->data["tipo_presupuetoPDF"]["DENO_ESTADO"]);
			    $this->set('DENO_COD_TIPO_INST', $this->data["tipo_presupuetoPDF"]["DENO_COD_TIPO_INST"]);
			    $this->set('DENO_INST',          $this->data["tipo_presupuetoPDF"]["DENO_INST"]);
			    $this->set('DENO_REPUBLICA',     $this->data["tipo_presupuetoPDF"]["DENO_REPUBLICA"]);


}//fin else




$this->set('var', $var);



}//fin function












function ejecucion_tipo_gasto($var=null){

	$username = $this->Session->read('nom_usuario');
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

if($var==1){

	    $this->layout="ajax";


			$datos  = $this->v_cfpd05_tipo_gasto2->execute(" SELECT DISTINCT ano  FROM cfpd05 ");
			$datos2 = $this->arrd01->execute(" SELECT DISTINCT ano_arranque    FROM ccfd03_instalacion  ORDER BY ano_arranque DESC");

						if(count($datos)!=0){
							foreach($datos as $n){
								$cod[]  = $n[0]['ano'];
								$deno[] = $n[0]['ano'];
							}
							$lista=array_combine($cod, $deno);
						}else{
							$lista=array('0'=>'No se encuentra presente en ninguna nomina');
						}

					$this->set("ano_lista", $lista);


			$this->set("ano_ejecucion" , $datos2[0][0]["ano_arranque"]);

			        $lista =  $this->arrd01->generateList(null, 'denominacion ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.denominacion');
		            $this->set('vector_presi', $lista);
		            $this->set("cod_presi_seleccion", 1);



}else if($var==2){

	      $this->layout="ajax";
	      $sql = "";

	      $group_by = " GROUP BY ";
	      $tipo=0;
	      $deno_presi   = "";
	      $deno_entidad = "";
	      $deno_inst    = "";
	      $deno_tipo_inst = "";

	                                 if(!empty($this->data["datos"]["cod_presi"])){
									  	if($this->data["datos"]["cod_presi"]!="TODO"){
						                          $sql      .= " a.cod_presi = '".$this->data["datos"]["cod_presi"]."' ";
						                          $group_by .= " a.cod_presi";

						                          $lista_1    =  $this->arrd01->findAll("cod_presi=".$this->data["datos"]["cod_presi"]);
						                          $deno_presi = $lista_1[0]["arrd01"]["denominacion"];
									  	}else{
									  		     $sql .= " 1=1 ";

									  	}
									  }

                                      if(!empty($this->data["datos"]["cod_entidad"])){
									  	if($this->data["datos"]["cod_entidad"]!="TODO"){
						                          $sql .=" and a.cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";
						                          $group_by .= ", a.cod_entidad";
						                          $tipo = 1;

						                          $lista_2      =  $this->arrd02->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]);
						                          $deno_entidad = $lista_2[0]["arrd02"]["denominacion"];
									  	}
									  }

                                       if(!empty($this->data["datos"]["cod_tipo_inst"])){
									  	if($this->data["datos"]["cod_tipo_inst"]!="TODO"){
						                          $sql .=" and a.cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";
						                          $group_by .= ", a.cod_tipo_inst";
						                          $tipo = 2;

						                          $lista_3        =  $this->arrd03->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_tipo_inst=".$this->data["datos"]["cod_tipo_inst"]);
						                          $deno_tipo_inst = $lista_3[0]["arrd03"]["denominacion"];
									  	 }
                                       }

                                      if(!empty($this->data["datos"]["cod_inst"])){
									  	if($this->data["datos"]["cod_inst"]!="TODO"){
						                          $sql .=" and a.cod_inst = '".$this->data["datos"]["cod_inst"]."' ";
						                          $group_by .= ", a.cod_inst";
						                          $tipo = 3;

						                          $lista_4   =  $this->arrd04->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]." and cod_inst=".$this->data["datos"]["cod_inst"]);
						                          $deno_inst = $lista_4[0]["arrd04"]["denominacion"];
									  	}
									  }
									  if(!empty($this->data["datos"]["ano_consolidado"])){
									  	if($this->data["datos"]["ano_consolidado"]!="TODO"){
						                          $sql .=" and a.ano = '".$this->data["datos"]["ano_consolidado"]."' ";
						                          $group_by .= ", a.ano";
									  	}
									  }
									  if(!empty($this->data["datos"]["tipo_gasto"])){
									  	             $tipo_gasto_aux = $this->data["datos"]["tipo_gasto"];
                                                     if($this->data["datos"]["tipo_gasto"]==1){
											         $tipo_gasto = " and cod_tipo_gasto = 2 ";
											   }else if($this->data["datos"]["tipo_gasto"]==3){
											         $tipo_gasto = " ";
											   }else{
											         $tipo_gasto = "  and cod_tipo_gasto != 2 ";
											   }//fin if
									   }




                 $condicion        = $sql.$tipo_gasto.$group_by;


            $datos = $this->cfpd05->execute("SELECT

		    						  SUM(a.asignacion_anual)           as asignacion_anual,
									  SUM(a.aumento_traslado_anual)     as aumento_traslado_anual,
									  SUM(a.disminucion_traslado_anual) as disminucion_traslado_anual,
									  SUM(a.credito_adicional_anual)    as credito_adicional_anual,
									  SUM(a.rebaja_anual)               as rebaja_anual,
									  SUM(a.compromiso_anual)           as compromiso_anual,
									  SUM(a.causado_anual)              as causado_anual,
									  SUM(a.pagado_anual)               as pagado_anual

						 		      FROM cfpd05 a where ".$condicion.";");

                    $_SESSION["CONDICIONPDF"] =  $condicion;

                    if(!isset($datos[0][0]['asignacion_anual'])){
		                             $datos[0][0]['asignacion_anual'] = 0;
		                             $datos[0][0]['aumento_traslado_anual'] = 0;
		                             $datos[0][0]['credito_adicional_anual'] = 0;
		                             $datos[0][0]['disminucion_traslado_anual'] = 0;
		                             $datos[0][0]['rebaja_anual'] = 0;
		                             $datos[0][0]['compromiso_anual'] = 0;
		                             $datos[0][0]['causado_anual'] = 0;
		                             $datos[0][0]['pagado_anual'] = 0;
                    }//fin if


					$asignacion_anual            =  $datos[0][0]['asignacion_anual'];
					$aumento_traslado_anual      =  $datos[0][0]['aumento_traslado_anual'];
					$credito_adicional_anual     =  $datos[0][0]['credito_adicional_anual'];
					$disminucion_traslado_anual  =  $datos[0][0]['disminucion_traslado_anual'];
					$rebaja_anual                =  $datos[0][0]['rebaja_anual'];
					$compromiso_anual    =  $datos[0][0]['compromiso_anual'];
					$causado_anual       =  $datos[0][0]['causado_anual'];
					$pagado_anual        =  $datos[0][0]['pagado_anual'];
					$asignacion_ajustada =  (($asignacion_anual+$aumento_traslado_anual+$credito_adicional_anual)-($disminucion_traslado_anual+$rebaja_anual));
					$deuda = ($causado_anual-$pagado_anual);
					$disponibilidad =  ($asignacion_ajustada-$compromiso_anual);



					if($asignacion_ajustada==0){
						$asignacion_anual_por           = 0;
						$aumento_traslado_anual_por     = 0;
						$credito_adicional_anual_por    = 0;
						$disminucion_traslado_anual_por = 0;
						$rebaja_anual_por               = 0;
						$compromiso_anual_por           = 0;
						$causado_anual_por              = 0;
						$pagado_anual_por               = 0;
						$asignacion_ajustada_por        = 0;
						$deuda_por                      = 0;
						$disponibilidad_por             = 0;
					       }else{
						$asignacion_anual_por           = ($asignacion_anual * 100) / $asignacion_ajustada;
						$aumento_traslado_anual_por     = ($aumento_traslado_anual* 100) / $asignacion_ajustada;
						$credito_adicional_anual_por    = ($credito_adicional_anual* 100) / $asignacion_ajustada;
						$disminucion_traslado_anual_por = ($disminucion_traslado_anual* 100) / $asignacion_ajustada;
						$rebaja_anual_por               = ($rebaja_anual* 100) / $asignacion_ajustada;
						$compromiso_anual_por           = ($compromiso_anual* 100) / $asignacion_ajustada;
						$causado_anual_por              = ($causado_anual* 100) / $asignacion_ajustada;
						$pagado_anual_por               = ($pagado_anual * 100) / $asignacion_ajustada;
						$asignacion_ajustada_por        = ($asignacion_ajustada * 100) / $asignacion_ajustada;
						$deuda_por                      = ($deuda* 100) / $asignacion_ajustada;
						$disponibilidad_por             = ($disponibilidad * 100) / $asignacion_ajustada;
					}//fin else



                   $this->set('asignacion_anual',           $asignacion_anual);
                   $this->set('aumento_traslado_anual',     $aumento_traslado_anual);
                   $this->set('credito_adicional_anual',    $credito_adicional_anual);
                   $this->set('disminucion_traslado_anual', $disminucion_traslado_anual);
                   $this->set('rebaja_anual',               $rebaja_anual);
                   $this->set('compromiso_anual',           $compromiso_anual);
                   $this->set('causado_anual',              $causado_anual);
                   $this->set('pagado_anual',               $pagado_anual);
                   $this->set('asignacion_ajustada',        $asignacion_ajustada);
                   $this->set('deuda',                      $deuda);
                   $this->set('disponibilidad',             $disponibilidad);


                   $this->set('asignacion_anual_por',           $asignacion_anual_por);
                   $this->set('aumento_traslado_anual_por',     $aumento_traslado_anual_por);
                   $this->set('credito_adicional_anual_por',    $credito_adicional_anual_por);
                   $this->set('disminucion_traslado_anual_por', $disminucion_traslado_anual_por);
                   $this->set('rebaja_anual_por',               $rebaja_anual_por);
                   $this->set('compromiso_anual_por',           $compromiso_anual_por);
                   $this->set('causado_anual_por',              $causado_anual_por);
                   $this->set('pagado_anual_por',               $pagado_anual_por);
                   $this->set('asignacion_ajustada_por',        $asignacion_ajustada_por);
                   $this->set('deuda_por',                      $deuda_por);
                   $this->set('disponibilidad_por',             $disponibilidad_por);


            $this->set('tipo_gasto', $tipo_gasto_aux);
			$this->set('tipo_top', $tipo);
			$this->set('year',     $this->data["datos"]["ano_consolidado"]);


			$this->set('DENO_ESTADO', $deno_entidad);
			$this->set('DENO_COD_TIPO_INST', $deno_tipo_inst);
			$this->set('DENO_INST', $deno_inst);
			$this->set('DENO_REPUBLICA', $deno_presi);

			echo"<script>document.getElementById('ir').disabled=false; </script>";





}else if($var==3){

	       $this->layout="pdf";



	        $this->set('consolidacion',               $this->data["graficos1"]["consolidacion"]);
            $this->set('tipo_gasto',                  $this->data["graficos1"]["tipo_gasto"]);
            $this->set('year',                        $this->data["graficos1"]["year"]);
			$this->set('asignacion_anual',            $this->data["graficos1"]["asignacion_anual"]);
			$this->set('aumento_traslado_anual',      $this->data["graficos1"]["aumento_traslado_anual"]);
			$this->set('credito_adicional_anual',     $this->data["graficos1"]["credito_adicional_anual"]);
			$this->set('disminucion_traslado_anual',  $this->data["graficos1"]["disminucion_traslado_anual"]);
			$this->set('rebaja_anual',                $this->data["graficos1"]["rebaja_anual"]);
			$this->set('compromiso_anual',            $this->data["graficos1"]["compromiso_anual"]);
			$this->set('causado_anual',               $this->data["graficos1"]["causado_anual"]);
			$this->set('asignacion_ajustada',         $this->data["graficos1"]["asignacion_ajustada"]);
			$this->set('pagado_anual',                $this->data["graficos1"]["pagado_anual"]);
			$this->set('deuda',                       $this->data["graficos1"]["deuda"]);
			$this->set('disponibilidad',              $this->data["graficos1"]["disponibilidad"]);
            $this->set('rdm',                         $this->data["graficos1"]["rdm"]);
			$this->set('asignacion_anual_por',        $this->data["graficos1"]["asignacion_anual_por"]);
			$this->set('aumento_traslado_anual_por',  $this->data["graficos1"]["aumento_traslado_anual_por"]);
			$this->set('credito_adicional_anual_por',    $this->data["graficos1"]["credito_adicional_anual_por"]);
			$this->set('disminucion_traslado_anual_por', $this->data["graficos1"]["disminucion_traslado_anual_por"]);
			$this->set('rebaja_anual_por',               $this->data["graficos1"]["rebaja_anual_por"]);
			$this->set('compromiso_anual_por',           $this->data["graficos1"]["compromiso_anual_por"]);
			$this->set('causado_anual_por',              $this->data["graficos1"]["causado_anual_por"]);
			$this->set('asignacion_ajustada_por',        $this->data["graficos1"]["asignacion_ajustada_por"]);
			$this->set('pagado_anual_por',              $this->data["graficos1"]["pagado_anual_por"]);
			$this->set('deuda_por',                     $this->data["graficos1"]["deuda_por"]);
			$this->set('disponibilidad_por',            $this->data["graficos1"]["disponibilidad_por"]);




				$this->set('tipo_top',           $this->data["graficos1"]["tipo_top"]);
				$this->set('DENO_ESTADO',        $this->data["graficos1"]["DENO_ESTADO"]);
			    $this->set('DENO_COD_TIPO_INST', $this->data["graficos1"]["DENO_COD_TIPO_INST"]);
			    $this->set('DENO_INST',          $this->data["graficos1"]["DENO_INST"]);
			    $this->set('DENO_REPUBLICA',     $this->data["graficos1"]["DENO_REPUBLICA"]);
                $this->set('user',               $this->Session->read('nom_usuario'));


}//fin else




$this->set('var', $var);



}//fin function






















function ejecucion_tipo_presupuesto($var=null){



	$username = $this->Session->read('nom_usuario');
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

if($var==1){

	    $this->layout="ajax";


			$datos  = $this->v_cfpd05_tipo_gasto2->execute(" SELECT DISTINCT ano  FROM cfpd05 ");
			$datos2 = $this->arrd01->execute(" SELECT DISTINCT ano_arranque    FROM ccfd03_instalacion  ORDER BY ano_arranque DESC");

						if(count($datos)!=0){
							foreach($datos as $n){
								$cod[]  = $n[0]['ano'];
								$deno[] = $n[0]['ano'];
							}
							$lista=array_combine($cod, $deno);
						}else{
							$lista=array('0'=>'No se encuentra presente en ninguna nomina');
						}

					$this->set("ano_lista", $lista);


			$this->set("ano_ejecucion" , $datos2[0][0]["ano_arranque"]);
			        $lista =  $this->arrd01->generateList(null, 'denominacion ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.denominacion');
		            $this->set('vector_presi', $lista);
		            $this->set("cod_presi_seleccion", 1);



}else if($var==2){

	      $this->layout="ajax";
	      $sql = "";

	      $group_by = " GROUP BY ";
	      $tipo=0;
	      $deno_presi   = "";
	      $deno_entidad = "";
	      $deno_inst    = "";
	      $deno_tipo_inst = "";

	                                 if(!empty($this->data["datos"]["cod_presi"])){
									  	if($this->data["datos"]["cod_presi"]!="TODO"){
						                          $sql      .= " a.cod_presi = '".$this->data["datos"]["cod_presi"]."' ";
						                          $group_by .= " a.cod_presi";

						                          $lista_1    =  $this->arrd01->findAll("cod_presi=".$this->data["datos"]["cod_presi"]);
						                          $deno_presi = $lista_1[0]["arrd01"]["denominacion"];
									  	}else{
									  		     $sql .= " 1=1 ";

									  	}
									  }

                                      if(!empty($this->data["datos"]["cod_entidad"])){
									  	if($this->data["datos"]["cod_entidad"]!="TODO"){
						                          $sql .=" and a.cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";
						                          $group_by .= ", a.cod_entidad";
						                          $tipo = 1;

						                          $lista_2      =  $this->arrd02->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]);
						                          $deno_entidad = $lista_2[0]["arrd02"]["denominacion"];
									  	}
									  }

                                       if(!empty($this->data["datos"]["cod_tipo_inst"])){
									  	if($this->data["datos"]["cod_tipo_inst"]!="TODO"){
						                          $sql .=" and a.cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";
						                          $group_by .= ", a.cod_tipo_inst";
						                          $tipo = 2;

						                          $lista_3        =  $this->arrd03->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_tipo_inst=".$this->data["datos"]["cod_tipo_inst"]);
						                          $deno_tipo_inst = $lista_3[0]["arrd03"]["denominacion"];
									  	 }
                                       }

                                      if(!empty($this->data["datos"]["cod_inst"])){
									  	if($this->data["datos"]["cod_inst"]!="TODO"){
						                          $sql .=" and a.cod_inst = '".$this->data["datos"]["cod_inst"]."' ";
						                          $group_by .= ", a.cod_inst";
						                          $tipo = 3;

						                          $lista_4   =  $this->arrd04->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]." and cod_inst=".$this->data["datos"]["cod_inst"]);
						                          $deno_inst = $lista_4[0]["arrd04"]["denominacion"];
									  	}
									  }
									  if(!empty($this->data["datos"]["ano_consolidado"])){
									  	if($this->data["datos"]["ano_consolidado"]!="TODO"){
						                          $sql .=" and a.ano = '".$this->data["datos"]["ano_consolidado"]."' ";
						                          $group_by .= ", a.ano";
									  	}
									  }
									  if(!empty($this->data["datos"]["tipo_presupuesto"])){
									  	             $tipo_presupuesto_aux = $this->data["datos"]["tipo_presupuesto"];
							  	             if($this->data["datos"]["tipo_presupuesto"]==6){
											   	 $sql_aux = "";
											   }else{
											   	 $sql_aux = " and tipo_presupuesto = ".$this->data["datos"]["tipo_presupuesto"];
											   }
									   }




                 $condicion        = $sql.$sql_aux.$group_by;


            $datos = $this->cfpd05->execute("SELECT

		    						  SUM(a.asignacion_anual)           as asignacion_anual,
									  SUM(a.aumento_traslado_anual)     as aumento_traslado_anual,
									  SUM(a.disminucion_traslado_anual) as disminucion_traslado_anual,
									  SUM(a.credito_adicional_anual)    as credito_adicional_anual,
									  SUM(a.rebaja_anual)               as rebaja_anual,
									  SUM(a.compromiso_anual)           as compromiso_anual,
									  SUM(a.causado_anual)              as causado_anual,
									  SUM(a.pagado_anual)               as pagado_anual

						 		      FROM cfpd05 a where ".$condicion.";");

                    $_SESSION["CONDICIONPDF"] =  $condicion;

                    if(!isset($datos[0][0]['asignacion_anual'])){
		                             $datos[0][0]['asignacion_anual'] = 0;
		                             $datos[0][0]['aumento_traslado_anual'] = 0;
		                             $datos[0][0]['credito_adicional_anual'] = 0;
		                             $datos[0][0]['disminucion_traslado_anual'] = 0;
		                             $datos[0][0]['rebaja_anual'] = 0;
		                             $datos[0][0]['compromiso_anual'] = 0;
		                             $datos[0][0]['causado_anual'] = 0;
		                             $datos[0][0]['pagado_anual'] = 0;
                    }//fin if


					$asignacion_anual            =  $datos[0][0]['asignacion_anual'];
					$aumento_traslado_anual      =  $datos[0][0]['aumento_traslado_anual'];
					$credito_adicional_anual     =  $datos[0][0]['credito_adicional_anual'];
					$disminucion_traslado_anual  =  $datos[0][0]['disminucion_traslado_anual'];
					$rebaja_anual                =  $datos[0][0]['rebaja_anual'];
					$compromiso_anual    =  $datos[0][0]['compromiso_anual'];
					$causado_anual       =  $datos[0][0]['causado_anual'];
					$pagado_anual        =  $datos[0][0]['pagado_anual'];
					$asignacion_ajustada =  (($asignacion_anual+$aumento_traslado_anual+$credito_adicional_anual)-($disminucion_traslado_anual+$rebaja_anual));
					$deuda = ($causado_anual-$pagado_anual);
					$disponibilidad =  ($asignacion_ajustada-$compromiso_anual);



					if($asignacion_ajustada==0){
						$asignacion_anual_por           = 0;
						$aumento_traslado_anual_por     = 0;
						$credito_adicional_anual_por    = 0;
						$disminucion_traslado_anual_por = 0;
						$rebaja_anual_por               = 0;
						$compromiso_anual_por           = 0;
						$causado_anual_por              = 0;
						$pagado_anual_por               = 0;
						$asignacion_ajustada_por        = 0;
						$deuda_por                      = 0;
						$disponibilidad_por             = 0;
					       }else{
						$asignacion_anual_por           = ($asignacion_anual * 100) / $asignacion_ajustada;
						$aumento_traslado_anual_por     = ($aumento_traslado_anual* 100) / $asignacion_ajustada;
						$credito_adicional_anual_por    = ($credito_adicional_anual* 100) / $asignacion_ajustada;
						$disminucion_traslado_anual_por = ($disminucion_traslado_anual* 100) / $asignacion_ajustada;
						$rebaja_anual_por               = ($rebaja_anual* 100) / $asignacion_ajustada;
						$compromiso_anual_por           = ($compromiso_anual* 100) / $asignacion_ajustada;
						$causado_anual_por              = ($causado_anual* 100) / $asignacion_ajustada;
						$pagado_anual_por               = ($pagado_anual * 100) / $asignacion_ajustada;
						$asignacion_ajustada_por        = ($asignacion_ajustada * 100) / $asignacion_ajustada;
						$deuda_por                      = ($deuda* 100) / $asignacion_ajustada;
						$disponibilidad_por             = ($disponibilidad * 100) / $asignacion_ajustada;
					}//fin else



                   $this->set('asignacion_anual',           $asignacion_anual);
                   $this->set('aumento_traslado_anual',     $aumento_traslado_anual);
                   $this->set('credito_adicional_anual',    $credito_adicional_anual);
                   $this->set('disminucion_traslado_anual', $disminucion_traslado_anual);
                   $this->set('rebaja_anual',               $rebaja_anual);
                   $this->set('compromiso_anual',           $compromiso_anual);
                   $this->set('causado_anual',              $causado_anual);
                   $this->set('pagado_anual',               $pagado_anual);
                   $this->set('asignacion_ajustada',        $asignacion_ajustada);
                   $this->set('deuda',                      $deuda);
                   $this->set('disponibilidad',             $disponibilidad);


                   $this->set('asignacion_anual_por',           $asignacion_anual_por);
                   $this->set('aumento_traslado_anual_por',     $aumento_traslado_anual_por);
                   $this->set('credito_adicional_anual_por',    $credito_adicional_anual_por);
                   $this->set('disminucion_traslado_anual_por', $disminucion_traslado_anual_por);
                   $this->set('rebaja_anual_por',               $rebaja_anual_por);
                   $this->set('compromiso_anual_por',           $compromiso_anual_por);
                   $this->set('causado_anual_por',              $causado_anual_por);
                   $this->set('pagado_anual_por',               $pagado_anual_por);
                   $this->set('asignacion_ajustada_por',        $asignacion_ajustada_por);
                   $this->set('deuda_por',                      $deuda_por);
                   $this->set('disponibilidad_por',             $disponibilidad_por);


            $this->set('tipo_presupuesto', $tipo_presupuesto_aux);
			$this->set('tipo_top', $tipo);
			$this->set('year',     $this->data["datos"]["ano_consolidado"]);


			$this->set('DENO_ESTADO', $deno_entidad);
			$this->set('DENO_COD_TIPO_INST', $deno_tipo_inst);
			$this->set('DENO_INST', $deno_inst);
			$this->set('DENO_REPUBLICA', $deno_presi);

			echo"<script>document.getElementById('ir').disabled=false; </script>";





}else if($var==3){

	       $this->layout="pdf";

	        $this->set('consolidacion',               $this->data["graficos1"]["consolidacion"]);
            $this->set('tipo_presupuesto',            $this->data["graficos1"]["tipo_presupuesto"]);
            $this->set('year',                        $this->data["graficos1"]["year"]);
			$this->set('asignacion_anual',            $this->data["graficos1"]["asignacion_anual"]);
			$this->set('aumento_traslado_anual',      $this->data["graficos1"]["aumento_traslado_anual"]);
			$this->set('credito_adicional_anual',     $this->data["graficos1"]["credito_adicional_anual"]);
			$this->set('disminucion_traslado_anual',  $this->data["graficos1"]["disminucion_traslado_anual"]);
			$this->set('rebaja_anual',                $this->data["graficos1"]["rebaja_anual"]);
			$this->set('compromiso_anual',            $this->data["graficos1"]["compromiso_anual"]);
			$this->set('causado_anual',               $this->data["graficos1"]["causado_anual"]);
			$this->set('asignacion_ajustada',         $this->data["graficos1"]["asignacion_ajustada"]);
			$this->set('pagado_anual',                $this->data["graficos1"]["pagado_anual"]);
			$this->set('deuda',                       $this->data["graficos1"]["deuda"]);
			$this->set('disponibilidad',              $this->data["graficos1"]["disponibilidad"]);
            $this->set('rdm',                         $this->data["graficos1"]["rdm"]);
			$this->set('asignacion_anual_por',        $this->data["graficos1"]["asignacion_anual_por"]);
			$this->set('aumento_traslado_anual_por',  $this->data["graficos1"]["aumento_traslado_anual_por"]);
			$this->set('credito_adicional_anual_por',    $this->data["graficos1"]["credito_adicional_anual_por"]);
			$this->set('disminucion_traslado_anual_por', $this->data["graficos1"]["disminucion_traslado_anual_por"]);
			$this->set('rebaja_anual_por',               $this->data["graficos1"]["rebaja_anual_por"]);
			$this->set('compromiso_anual_por',           $this->data["graficos1"]["compromiso_anual_por"]);
			$this->set('causado_anual_por',              $this->data["graficos1"]["causado_anual_por"]);
			$this->set('asignacion_ajustada_por',        $this->data["graficos1"]["asignacion_ajustada_por"]);
			$this->set('pagado_anual_por',              $this->data["graficos1"]["pagado_anual_por"]);
			$this->set('deuda_por',                     $this->data["graficos1"]["deuda_por"]);
			$this->set('disponibilidad_por',            $this->data["graficos1"]["disponibilidad_por"]);




				$this->set('tipo_top',           $this->data["graficos1"]["tipo_top"]);
				$this->set('DENO_ESTADO',        $this->data["graficos1"]["DENO_ESTADO"]);
			    $this->set('DENO_COD_TIPO_INST', $this->data["graficos1"]["DENO_COD_TIPO_INST"]);
			    $this->set('DENO_INST',          $this->data["graficos1"]["DENO_INST"]);
			    $this->set('DENO_REPUBLICA',     $this->data["graficos1"]["DENO_REPUBLICA"]);
                $this->set('user',               $this->Session->read('nom_usuario'));



}//fin else




$this->set('var', $var);









}//fin function

























function ejecucion_tipo_partida($var=null){



	$username = $this->Session->read('nom_usuario');
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

if($var==1){

	    $this->layout="ajax";


			$datos  = $this->v_cfpd05_tipo_gasto2->execute(" SELECT DISTINCT ano  FROM cfpd05 ");
			$datos2 = $this->arrd01->execute(" SELECT DISTINCT ano_arranque    FROM ccfd03_instalacion  ORDER BY ano_arranque DESC");

						if(count($datos)!=0){
							foreach($datos as $n){
								$cod[]  = $n[0]['ano'];
								$deno[] = $n[0]['ano'];
							}
							$lista=array_combine($cod, $deno);
						}else{
							$lista=array('0'=>'No se encuentra presente en ninguna nomina');
						}

					$this->set("ano_lista", $lista);


			$this->set("ano_ejecucion" , $datos2[0][0]["ano_arranque"]);

			        $lista =  $this->arrd01->generateList(null, 'denominacion ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.denominacion');
		            $this->set('vector_presi', $lista);
		            $this->set("cod_presi_seleccion", 1);







						    $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_grupo, a.cod_partida, a.descripcion FROM cfpd01_partida a where a.cod_grupo=4 ");
						    foreach($rs as $l){
						    	if(!isset($seleccion)){$seleccion = $l[0]["cod_grupo"].mascara2($l[0]["cod_partida"]);}
								$v[]=$l[0]["cod_grupo"].mascara2($l[0]["cod_partida"]);
								$d[]=$l[0]["cod_grupo"].".".mascara2($l[0]["cod_partida"])." - ".$l[0]["descripcion"];
							}
							$partida = array_combine($v, $d);
					        $this->set('lista_numero', $partida);
					        $this->set("seleccion" , $seleccion);







}else if($var==2){

	      $this->layout="ajax";
	      $sql = "";

	      $group_by = " GROUP BY ";
	      $tipo=0;
	      $deno_presi   = "";
	      $deno_entidad = "";
	      $deno_inst    = "";
	      $deno_tipo_inst = "";
	      $cod_partida_aux = "";

	                                 if(!empty($this->data["datos"]["cod_presi"])){
									  	if($this->data["datos"]["cod_presi"]!="TODO"){
						                          $sql      .= " a.cod_presi = '".$this->data["datos"]["cod_presi"]."' ";
						                          $group_by .= " a.cod_presi";

						                          $lista_1    =  $this->arrd01->findAll("cod_presi=".$this->data["datos"]["cod_presi"]);
						                          $deno_presi = $lista_1[0]["arrd01"]["denominacion"];
									  	}else{
									  		     $sql .= " 1=1 ";

									  	}
									  }

                                      if(!empty($this->data["datos"]["cod_entidad"])){
									  	if($this->data["datos"]["cod_entidad"]!="TODO"){
						                          $sql .=" and a.cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";
						                          $group_by .= ", a.cod_entidad";
						                          $tipo = 1;

						                          $lista_2      =  $this->arrd02->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]);
						                          $deno_entidad = $lista_2[0]["arrd02"]["denominacion"];
									  	}
									  }

                                       if(!empty($this->data["datos"]["cod_tipo_inst"])){
									  	if($this->data["datos"]["cod_tipo_inst"]!="TODO"){
						                          $sql .=" and a.cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";
						                          $group_by .= ", a.cod_tipo_inst";
						                          $tipo = 2;

						                          $lista_3        =  $this->arrd03->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_tipo_inst=".$this->data["datos"]["cod_tipo_inst"]);
						                          $deno_tipo_inst = $lista_3[0]["arrd03"]["denominacion"];
									  	 }
                                       }

                                      if(!empty($this->data["datos"]["cod_inst"])){
									  	if($this->data["datos"]["cod_inst"]!="TODO"){
						                          $sql .=" and a.cod_inst = '".$this->data["datos"]["cod_inst"]."' ";
						                          $group_by .= ", a.cod_inst";
						                          $tipo = 3;

						                          $lista_4   =  $this->arrd04->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]." and cod_inst=".$this->data["datos"]["cod_inst"]);
						                          $deno_inst = $lista_4[0]["arrd04"]["denominacion"];
									  	}
									  }
									  if(!empty($this->data["datos"]["ano_consolidado"])){
									  	if($this->data["datos"]["ano_consolidado"]!="TODO"){
						                          $sql .=" and a.ano = '".$this->data["datos"]["ano_consolidado"]."' ";
						                          $group_by .= ", a.ano";
									  	}
									  }
									  if(!empty($this->data["datos"]["cod_partida"])){
									  	             $cod_partida_aux = $this->data["datos"]["cod_partida"];
									  	             $sql .=" and a.cod_partida = '".$this->data["datos"]["cod_partida"]."' ";

									   }




                 $condicion        = $sql.$group_by;


            $datos = $this->cfpd05->execute("SELECT

		    						  SUM(a.asignacion_anual)           as asignacion_anual,
									  SUM(a.aumento_traslado_anual)     as aumento_traslado_anual,
									  SUM(a.disminucion_traslado_anual) as disminucion_traslado_anual,
									  SUM(a.credito_adicional_anual)    as credito_adicional_anual,
									  SUM(a.rebaja_anual)               as rebaja_anual,
									  SUM(a.compromiso_anual)           as compromiso_anual,
									  SUM(a.causado_anual)              as causado_anual,
									  SUM(a.pagado_anual)               as pagado_anual

						 		      FROM cfpd05 a where ".$condicion.";");

                    if(!isset($datos[0][0]['asignacion_anual'])){
		                             $datos[0][0]['asignacion_anual'] = 0;
		                             $datos[0][0]['aumento_traslado_anual'] = 0;
		                             $datos[0][0]['credito_adicional_anual'] = 0;
		                             $datos[0][0]['disminucion_traslado_anual'] = 0;
		                             $datos[0][0]['rebaja_anual'] = 0;
		                             $datos[0][0]['compromiso_anual'] = 0;
		                             $datos[0][0]['causado_anual'] = 0;
		                             $datos[0][0]['pagado_anual'] = 0;
                    }//fin if


					$asignacion_anual            =  $datos[0][0]['asignacion_anual'];
					$aumento_traslado_anual      =  $datos[0][0]['aumento_traslado_anual'];
					$credito_adicional_anual     =  $datos[0][0]['credito_adicional_anual'];
					$disminucion_traslado_anual  =  $datos[0][0]['disminucion_traslado_anual'];
					$rebaja_anual                =  $datos[0][0]['rebaja_anual'];
					$compromiso_anual    =  $datos[0][0]['compromiso_anual'];
					$causado_anual       =  $datos[0][0]['causado_anual'];
					$pagado_anual        =  $datos[0][0]['pagado_anual'];
					$asignacion_ajustada =  (($asignacion_anual+$aumento_traslado_anual+$credito_adicional_anual)-($disminucion_traslado_anual+$rebaja_anual));
					$deuda = ($causado_anual-$pagado_anual);
					$disponibilidad =  ($asignacion_ajustada-$compromiso_anual);



					if($asignacion_ajustada==0){
						$asignacion_anual_por           = 0;
						$aumento_traslado_anual_por     = 0;
						$credito_adicional_anual_por    = 0;
						$disminucion_traslado_anual_por = 0;
						$rebaja_anual_por               = 0;
						$compromiso_anual_por           = 0;
						$causado_anual_por              = 0;
						$pagado_anual_por               = 0;
						$asignacion_ajustada_por        = 0;
						$deuda_por                      = 0;
						$disponibilidad_por             = 0;
					       }else{
						$asignacion_anual_por           = ($asignacion_anual * 100) / $asignacion_ajustada;
						$aumento_traslado_anual_por     = ($aumento_traslado_anual* 100) / $asignacion_ajustada;
						$credito_adicional_anual_por    = ($credito_adicional_anual* 100) / $asignacion_ajustada;
						$disminucion_traslado_anual_por = ($disminucion_traslado_anual* 100) / $asignacion_ajustada;
						$rebaja_anual_por               = ($rebaja_anual* 100) / $asignacion_ajustada;
						$compromiso_anual_por           = ($compromiso_anual* 100) / $asignacion_ajustada;
						$causado_anual_por              = ($causado_anual* 100) / $asignacion_ajustada;
						$pagado_anual_por               = ($pagado_anual * 100) / $asignacion_ajustada;
						$asignacion_ajustada_por        = ($asignacion_ajustada * 100) / $asignacion_ajustada;
						$deuda_por                      = ($deuda* 100) / $asignacion_ajustada;
						$disponibilidad_por             = ($disponibilidad * 100) / $asignacion_ajustada;
					}//fin else



                   $this->set('asignacion_anual',           $asignacion_anual);
                   $this->set('aumento_traslado_anual',     $aumento_traslado_anual);
                   $this->set('credito_adicional_anual',    $credito_adicional_anual);
                   $this->set('disminucion_traslado_anual', $disminucion_traslado_anual);
                   $this->set('rebaja_anual',               $rebaja_anual);
                   $this->set('compromiso_anual',           $compromiso_anual);
                   $this->set('causado_anual',              $causado_anual);
                   $this->set('pagado_anual',               $pagado_anual);
                   $this->set('asignacion_ajustada',        $asignacion_ajustada);
                   $this->set('deuda',                      $deuda);
                   $this->set('disponibilidad',             $disponibilidad);


                   $this->set('asignacion_anual_por',           $asignacion_anual_por);
                   $this->set('aumento_traslado_anual_por',     $aumento_traslado_anual_por);
                   $this->set('credito_adicional_anual_por',    $credito_adicional_anual_por);
                   $this->set('disminucion_traslado_anual_por', $disminucion_traslado_anual_por);
                   $this->set('rebaja_anual_por',               $rebaja_anual_por);
                   $this->set('compromiso_anual_por',           $compromiso_anual_por);
                   $this->set('causado_anual_por',              $causado_anual_por);
                   $this->set('pagado_anual_por',               $pagado_anual_por);
                   $this->set('asignacion_ajustada_por',        $asignacion_ajustada_por);
                   $this->set('deuda_por',                      $deuda_por);
                   $this->set('disponibilidad_por',             $disponibilidad_por);


            $rs=$this->v_balance_ejecucion->execute("SELECT DISTINCT a.cod_partida,a.deno_partida FROM v_cfpd05_denominaciones a WHERE  ".$sql." ");

            if(!isset($rs[0][0]['deno_partida'])){$rs[0][0]['deno_partida']="";}

            $this->set('deno_partida',            $rs[0][0]['deno_partida']);


            $this->set('cod_partida', $cod_partida_aux);
			$this->set('tipo_top', $tipo);
			$this->set('year',     $this->data["datos"]["ano_consolidado"]);


			$this->set('DENO_ESTADO', $deno_entidad);
			$this->set('DENO_COD_TIPO_INST', $deno_tipo_inst);
			$this->set('DENO_INST', $deno_inst);
			$this->set('DENO_REPUBLICA', $deno_presi);

			echo"<script>document.getElementById('ir').disabled=false; </script>";





}else if($var==3){

	       $this->layout="pdf";

	        $this->set('consolidacion',               $this->data["graficos1"]["consolidacion"]);
            $this->set('cod_partida',                 $this->data["graficos1"]["cod_partida"]);
            $this->set('year',                        $this->data["graficos1"]["year"]);
			$this->set('asignacion_anual',            $this->data["graficos1"]["asignacion_anual"]);
			$this->set('aumento_traslado_anual',      $this->data["graficos1"]["aumento_traslado_anual"]);
			$this->set('credito_adicional_anual',     $this->data["graficos1"]["credito_adicional_anual"]);
			$this->set('disminucion_traslado_anual',  $this->data["graficos1"]["disminucion_traslado_anual"]);
			$this->set('rebaja_anual',                $this->data["graficos1"]["rebaja_anual"]);
			$this->set('compromiso_anual',            $this->data["graficos1"]["compromiso_anual"]);
			$this->set('causado_anual',               $this->data["graficos1"]["causado_anual"]);
			$this->set('asignacion_ajustada',         $this->data["graficos1"]["asignacion_ajustada"]);
			$this->set('pagado_anual',                $this->data["graficos1"]["pagado_anual"]);
			$this->set('deuda',                       $this->data["graficos1"]["deuda"]);
			$this->set('disponibilidad',              $this->data["graficos1"]["disponibilidad"]);
            $this->set('rdm',                         $this->data["graficos1"]["rdm"]);
			$this->set('asignacion_anual_por',        $this->data["graficos1"]["asignacion_anual_por"]);
			$this->set('aumento_traslado_anual_por',  $this->data["graficos1"]["aumento_traslado_anual_por"]);
			$this->set('credito_adicional_anual_por',    $this->data["graficos1"]["credito_adicional_anual_por"]);
			$this->set('disminucion_traslado_anual_por', $this->data["graficos1"]["disminucion_traslado_anual_por"]);
			$this->set('rebaja_anual_por',               $this->data["graficos1"]["rebaja_anual_por"]);
			$this->set('compromiso_anual_por',           $this->data["graficos1"]["compromiso_anual_por"]);
			$this->set('causado_anual_por',              $this->data["graficos1"]["causado_anual_por"]);
			$this->set('asignacion_ajustada_por',        $this->data["graficos1"]["asignacion_ajustada_por"]);
			$this->set('pagado_anual_por',               $this->data["graficos1"]["pagado_anual_por"]);
			$this->set('deuda_por',                      $this->data["graficos1"]["deuda_por"]);
			$this->set('disponibilidad_por',             $this->data["graficos1"]["disponibilidad_por"]);
			$this->set('deno_partida',                   $this->data["graficos1"]["deno_partida"]);




				$this->set('tipo_top',           $this->data["graficos1"]["tipo_top"]);
				$this->set('DENO_ESTADO',        $this->data["graficos1"]["DENO_ESTADO"]);
			    $this->set('DENO_COD_TIPO_INST', $this->data["graficos1"]["DENO_COD_TIPO_INST"]);
			    $this->set('DENO_INST',          $this->data["graficos1"]["DENO_INST"]);
			    $this->set('DENO_REPUBLICA',     $this->data["graficos1"]["DENO_REPUBLICA"]);
                $this->set('user',               $this->Session->read('nom_usuario'));



}//fin else




$this->set('var', $var);









}//fin function












function cobp00_contratado_vs_contratar($var=null){



	$username = $this->Session->read('nom_usuario');
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

if($var==1){

	    $this->layout="ajax";


			$datos  = $this->v_cfpd05_tipo_gasto2->execute(" SELECT DISTINCT ano  FROM cfpd05 ");
			$datos2 = $this->arrd01->execute(" SELECT DISTINCT ano_arranque    FROM ccfd03_instalacion  ORDER BY ano_arranque DESC");

						if(count($datos)!=0){
							foreach($datos as $n){
								$cod[]  = $n[0]['ano'];
								$deno[] = $n[0]['ano'];
							}
							$lista=array_combine($cod, $deno);
						}else{
							$lista=array('0'=>'No se encuentra presente en ninguna nomina');
						}

					$this->set("ano_lista", $lista);


			$this->set("ano_ejecucion" , $datos2[0][0]["ano_arranque"]);

			        $lista =  $this->arrd01->generateList(null, 'denominacion ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.denominacion');
		            $this->set('vector_presi', $lista);
		            $this->set("cod_presi_seleccion", 1);






}else if($var==2){


                    $this->layout="ajax";
	      $sql = "";

	      $group_by = " GROUP BY ";
	      $tipo=0;
	      $deno_presi   = "";
	      $deno_entidad = "";
	      $deno_inst    = "";
	      $deno_tipo_inst = "";

	                                 if(!empty($this->data["datos"]["cod_presi"])){
									  	if($this->data["datos"]["cod_presi"]!="TODO"){
						                          $sql      .= " a.cod_presi = '".$this->data["datos"]["cod_presi"]."' ";
						                          $group_by .= " a.cod_presi";

						                          $lista_1    =  $this->arrd01->findAll("cod_presi=".$this->data["datos"]["cod_presi"]);
						                          $deno_presi = $lista_1[0]["arrd01"]["denominacion"];
									  	}else{
									  		     $sql .= " 1=1 ";

									  	}
									  }

                                      if(!empty($this->data["datos"]["cod_entidad"])){
									  	if($this->data["datos"]["cod_entidad"]!="TODO"){
						                          $sql .=" and a.cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";
						                          $group_by .= ", a.cod_entidad";
						                          $tipo = 1;

						                          $lista_2      =  $this->arrd02->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]);
						                          $deno_entidad = $lista_2[0]["arrd02"]["denominacion"];
									  	}
									  }

                                       if(!empty($this->data["datos"]["cod_tipo_inst"])){
									  	if($this->data["datos"]["cod_tipo_inst"]!="TODO"){
						                          $sql .=" and a.cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";
						                          $group_by .= ", a.cod_tipo_inst";
						                          $tipo = 2;

						                          $lista_3        =  $this->arrd03->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_tipo_inst=".$this->data["datos"]["cod_tipo_inst"]);
						                          $deno_tipo_inst = $lista_3[0]["arrd03"]["denominacion"];
									  	 }
                                       }

                                      if(!empty($this->data["datos"]["cod_inst"])){
									  	if($this->data["datos"]["cod_inst"]!="TODO"){
						                          $sql .=" and a.cod_inst = '".$this->data["datos"]["cod_inst"]."' ";
						                          $group_by .= ", a.cod_inst";
						                          $tipo = 3;

						                          $lista_4   =  $this->arrd04->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]." and cod_inst=".$this->data["datos"]["cod_inst"]);
						                          $deno_inst = $lista_4[0]["arrd04"]["denominacion"];
									  	}
									  }
									  if(!empty($this->data["datos"]["ano_consolidado"])){
									  	if($this->data["datos"]["ano_consolidado"]!="TODO"){
						                          $sql .=" and a.ano_estimacion = '".$this->data["datos"]["ano_consolidado"]."' ";
						                          $group_by .= ", a.ano_estimacion";
									  	}
									  }
									  if(!empty($this->data["datos"]["tipo_presupuesto"])){
									  	             $tipo_presupuesto_aux = $this->data["datos"]["tipo_presupuesto"];
							  	             if($this->data["datos"]["tipo_presupuesto"]==6){
											   	 $sql_aux = "";
											   }else{
											   	 $sql_aux = " and tipo_recurso = ".$this->data["datos"]["tipo_presupuesto"];
											   }
									   }




                 $condicion        = $sql.$sql_aux.$group_by;


                 $this->layout="ajax";


    				$datos = $this->cfpd07_plan_inversion->execute("SELECT
    					 SUM(a.estimado_presu)    as  asignacion_total,
				 	     SUM(a.monto_contratado)  as  monto_presupuestado,
				 	     SUM(a.aumento_obras)     as  aumento_obras,
				 	     SUM(a.disminucion_obras) as  disminucion_obras

				 		      FROM cfpd07_obras_cuerpo a where ".$condicion." ;");


              if(isset($datos[0][0]['asignacion_total'])){

              	 $total_presupuestado = ($datos[0][0]['asignacion_total'] + $datos[0][0]['aumento_obras']) -  $datos[0][0]['disminucion_obras'];
                 $monto_contratado    = ($datos[0][0]['monto_presupuestado'] + $datos[0][0]['aumento_obras']) -  $datos[0][0]['disminucion_obras'];
                 $diferencia          =  $total_presupuestado - $monto_contratado;

                 $this->set('total_presupuestado',  $total_presupuestado);
                 $this->set('monto_contratado',     $monto_contratado);
                 $this->set('diferencia',           $diferencia);

              }else{

               	$this->set('total_presupuestado',  0);
                 $this->set('monto_contratado',    0);
                 $this->set('diferencia',          0);



              }//fin else


                  $this->set('tipo_top', $tipo);
			$this->set('year',             $this->data["datos"]["ano_consolidado"]);
			$this->set('tipo_recurso',     $tipo_presupuesto_aux);





			$this->set('DENO_ESTADO', $deno_entidad);
			$this->set('DENO_COD_TIPO_INST', $deno_tipo_inst);
			$this->set('DENO_INST', $deno_inst);
			$this->set('DENO_REPUBLICA', $deno_presi);

			echo"<script>document.getElementById('ir').disabled=false; </script>";






}else if($var==3){


$this->layout = "pdf";

						    $username = $this->Session->read('nom_usuario');
							$this->set('user', $username);
							$total_presupuestado       =   $this->data["graficos1"]["total_presupuestado"];
							$monto_contratado          =   $this->data["graficos1"]["monto_contratado"];
							$diferencia                =   $this->data["graficos1"]["diferencia"];
							$por_monto_contratado      =   $this->data["graficos1"]["por_monto_contratado"];
							$por_total_presupuestado   =   $this->data["graficos1"]["por_total_presupuestado"];
							$por_diferencia            =   $this->data["graficos1"]["por_diferencia"];
							$rdm                       =   $this->data["graficos1"]["rdm"];
							$year                      =   $this->data["graficos1"]["year"];
							$tipo_recurso              =   $this->data["graficos1"]["tipo_recurso"];
							$clasificacion_recurso     =   $this->data["graficos1"]["clasificacion_recurso"];



				            $this->set('year', $year);

				            $datos[0][0]['denominacion']="";


				            $tipo_recurso++;
				            $opcion1_aux = "";
				         if($tipo_recurso==1){  $opcion1_aux = "";
				   }else if($tipo_recurso==2){  $opcion1_aux = "(Ordinario)";
				   }else if($tipo_recurso==3){  $opcion1_aux = "(Coordinado)";
				   }else if($tipo_recurso==4){  $opcion1_aux = "(Laee)";
				   }else if($tipo_recurso==5){  $opcion1_aux = "(Fides)";
				   	}else if($tipo_recurso==6){ $opcion1_aux = "(Ingreso Extraordinario)";
				   }else if($tipo_recurso==9){  $opcion1_aux = ""; }//fin else

				            $this->set('opcion1_aux', $opcion1_aux);
				            $this->set('opcion2_aux', $datos[0][0]['denominacion']);
				            $this->set('tipo_recurso', $tipo_recurso);
				            $this->set('clasificacion_recurso', $clasificacion_recurso);
							$this->set('total_presupuestado', $total_presupuestado);
							$this->set('monto_contratado', $monto_contratado);
							$this->set('diferencia', $diferencia);
							$this->set('por_monto_contratado', $por_monto_contratado);
							$this->set('por_total_presupuestado', $por_total_presupuestado);
							$this->set('por_diferencia', $por_diferencia);
							$this->set('rdm', $rdm);

							$this->set('tipo_top',           $this->data["graficos1"]["tipo_top"]);
							$this->set('DENO_ESTADO',        $this->data["graficos1"]["DENO_ESTADO"]);
						    $this->set('DENO_COD_TIPO_INST', $this->data["graficos1"]["DENO_COD_TIPO_INST"]);
						    $this->set('DENO_INST',          $this->data["graficos1"]["DENO_INST"]);
						    $this->set('DENO_REPUBLICA',     $this->data["graficos1"]["DENO_REPUBLICA"]);
			                $this->set('user',               $this->Session->read('nom_usuario'));






}//fin else





$this->set('var', $var);


}//fin function










function cobp00_contratado_vs_pagado($var=null){



	$username = $this->Session->read('nom_usuario');
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

if($var==1){

	    $this->layout="ajax";


			$datos  = $this->v_cfpd05_tipo_gasto2->execute(" SELECT DISTINCT ano  FROM cfpd05 ");
			$datos2 = $this->arrd01->execute(" SELECT DISTINCT ano_arranque    FROM ccfd03_instalacion  ORDER BY ano_arranque DESC");

						if(count($datos)!=0){
							foreach($datos as $n){
								$cod[]  = $n[0]['ano'];
								$deno[] = $n[0]['ano'];
							}
							$lista=array_combine($cod, $deno);
						}else{
							$lista=array('0'=>'No se encuentra presente en ninguna nomina');
						}

					$this->set("ano_lista", $lista);


			$this->set("ano_ejecucion" , $datos2[0][0]["ano_arranque"]);

			        $lista =  $this->arrd01->generateList(null, 'denominacion ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.denominacion');
		            $this->set('vector_presi', $lista);
		            $this->set("cod_presi_seleccion", 1);






}else if($var==2){


                    $this->layout="ajax";
	      $sql = "";

	      $group_by = " GROUP BY ";
	      $tipo=0;
	      $deno_presi   = "";
	      $deno_entidad = "";
	      $deno_inst    = "";
	      $deno_tipo_inst = "";

	                                 if(!empty($this->data["datos"]["cod_presi"])){
									  	if($this->data["datos"]["cod_presi"]!="TODO"){
						                          $sql      .= " a.cod_presi = '".$this->data["datos"]["cod_presi"]."' ";
						                          $group_by .= " a.cod_presi";

						                          $lista_1    =  $this->arrd01->findAll("cod_presi=".$this->data["datos"]["cod_presi"]);
						                          $deno_presi = $lista_1[0]["arrd01"]["denominacion"];
									  	}else{
									  		     $sql .= " 1=1 ";

									  	}
									  }

                                      if(!empty($this->data["datos"]["cod_entidad"])){
									  	if($this->data["datos"]["cod_entidad"]!="TODO"){
						                          $sql .=" and a.cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";
						                          $group_by .= ", a.cod_entidad";
						                          $tipo = 1;

						                          $lista_2      =  $this->arrd02->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]);
						                          $deno_entidad = $lista_2[0]["arrd02"]["denominacion"];
									  	}
									  }

                                       if(!empty($this->data["datos"]["cod_tipo_inst"])){
									  	if($this->data["datos"]["cod_tipo_inst"]!="TODO"){
						                          $sql .=" and a.cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";
						                          $group_by .= ", a.cod_tipo_inst";
						                          $tipo = 2;

						                          $lista_3        =  $this->arrd03->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_tipo_inst=".$this->data["datos"]["cod_tipo_inst"]);
						                          $deno_tipo_inst = $lista_3[0]["arrd03"]["denominacion"];
									  	 }
                                       }

                                      if(!empty($this->data["datos"]["cod_inst"])){
									  	if($this->data["datos"]["cod_inst"]!="TODO"){
						                          $sql .=" and a.cod_inst = '".$this->data["datos"]["cod_inst"]."' ";
						                          $group_by .= ", a.cod_inst";
						                          $tipo = 3;

						                          $lista_4   =  $this->arrd04->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]." and cod_inst=".$this->data["datos"]["cod_inst"]);
						                          $deno_inst = $lista_4[0]["arrd04"]["denominacion"];
									  	}
									  }
									  if(!empty($this->data["datos"]["ano_consolidado"])){
									  	if($this->data["datos"]["ano_consolidado"]!="TODO"){
						                          $sql .=" and a.ano_estimacion = '".$this->data["datos"]["ano_consolidado"]."' ";
						                          $group_by .= ", a.ano_estimacion";
									  	}
									  }
									  if(!empty($this->data["datos"]["tipo_presupuesto"])){
									  	             $tipo_presupuesto_aux = $this->data["datos"]["tipo_presupuesto"];
							  	             if($this->data["datos"]["tipo_presupuesto"]==6){
											   	 $sql_aux = "";
											   }else{
											   	 $sql_aux = " and tipo_recurso = ".$this->data["datos"]["tipo_presupuesto"];
											   }
									   }




                 $condicion        = $sql.$sql_aux." GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.ano_estimacion, a.cod_obra";


                 $this->layout="ajax";


    				$datos = $this->cfpd07_plan_inversion->execute("SELECT
    					 a.cod_presi,
    					 a.cod_entidad,
    					 a.cod_tipo_inst,
    					 a.cod_inst,
    					 a.ano_estimacion,
    					 a.cod_obra,
				 	     SUM(a.estimado_presu)    as  asignacion_total,
				 	     SUM(a.monto_contratado)  as  monto_presupuestado,
				 	     SUM(a.aumento_obras)     as  aumento_obras,
				 	     SUM(a.disminucion_obras) as  disminucion_obras,
                         (SELECT SUM(monto_anticipo)     FROM  cobd01_contratoobras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.ano_estimacion=a.ano_estimacion  and x.condicion_actividad=1 and x.cod_obra=a.cod_obra) as monto_anticipo,
	                     (SELECT SUM(monto_cancelado)    FROM  cobd01_contratoobras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.ano_estimacion=a.ano_estimacion  and x.condicion_actividad=1 and x.cod_obra=a.cod_obra) as monto_cancelado,
	                     (SELECT SUM(monto_amortizacion) FROM  cobd01_contratoobras_cuerpo x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.ano_estimacion=a.ano_estimacion  and x.condicion_actividad=1 and x.cod_obra=a.cod_obra) as monto_amortizacion,
                         (SELECT SUM(monto_contratado)   FROM  cfpd07_obras_cuerpo   x WHERE x.cod_presi=a.cod_presi and x.cod_entidad=a.cod_entidad and x.cod_tipo_inst=a.cod_tipo_inst and x.cod_obra=a.cod_obra and x.ano_estimacion=a.ano_estimacion) as monto_contratado

				 		      FROM cfpd07_obras_cuerpo a where ".$condicion."; ");



                             $datoss[0][0]['monto_contratado']    = 0;
                             $datoss[0][0]['monto_anticipo']      = 0;
                             $datoss[0][0]['monto_cancelado']     = 0;
                             $datoss[0][0]['monto_amortizacion']  = 0;

    			  foreach($datos as $vea){
                             $datoss[0][0]['monto_contratado'] += $vea[0]['monto_contratado'];
                             $datoss[0][0]['monto_anticipo'] += $vea[0]['monto_anticipo'];
                             $datoss[0][0]['monto_cancelado'] += $vea[0]['monto_cancelado'];
                             $datoss[0][0]['monto_amortizacion'] += $vea[0]['monto_amortizacion'];

    			  }//fin foreach


                             $datos[0][0]['monto_contratado']   = $datoss[0][0]['monto_contratado'];
                             $datos[0][0]['monto_anticipo']     = $datoss[0][0]['monto_anticipo'];
                             $datos[0][0]['monto_cancelado']    = $datoss[0][0]['monto_cancelado'];
                             $datos[0][0]['monto_amortizacion'] = $datoss[0][0]['monto_amortizacion'];

				 $vara = ($datos[0][0]['monto_contratado']);
				 $varb = ($datos[0][0]['monto_anticipo']    + $datos[0][0]['monto_cancelado']) -  $datos[0][0]['monto_amortizacion'];
                 $this->set('total_presupuestado',   ($datos[0][0]['monto_contratado']));
                 $this->set('monto_contratado',      ($datos[0][0]['monto_anticipo']    + $datos[0][0]['monto_cancelado']) -  $datos[0][0]['monto_amortizacion']);
                 $this->set('diferencia',             $vara - $varb);










            $this->set('tipo_top', $tipo);
			$this->set('year',             $this->data["datos"]["ano_consolidado"]);
			$this->set('tipo_recurso',     $tipo_presupuesto_aux);
			$this->set('DENO_ESTADO', $deno_entidad);
			$this->set('DENO_COD_TIPO_INST', $deno_tipo_inst);
			$this->set('DENO_INST', $deno_inst);
			$this->set('DENO_REPUBLICA', $deno_presi);

			echo"<script>document.getElementById('ir').disabled=false; </script>";






}else if($var==3){


$this->layout = "pdf";

						    $username = $this->Session->read('nom_usuario');
							$this->set('user', $username);
							$total_presupuestado       =   $this->data["graficos1"]["total_presupuestado"];
							$monto_contratado          =   $this->data["graficos1"]["monto_contratado"];
							$diferencia                =   $this->data["graficos1"]["diferencia"];
							$por_monto_contratado      =   $this->data["graficos1"]["por_monto_contratado"];
							$por_total_presupuestado   =   $this->data["graficos1"]["por_total_presupuestado"];
							$por_diferencia            =   $this->data["graficos1"]["por_diferencia"];
							$rdm                       =   $this->data["graficos1"]["rdm"];
							$year                      =   $this->data["graficos1"]["year"];
							$tipo_recurso              =   $this->data["graficos1"]["tipo_recurso"];
							$clasificacion_recurso     =   $this->data["graficos1"]["clasificacion_recurso"];



				            $this->set('year', $year);

				            $datos[0][0]['denominacion']="";


				            $tipo_recurso++;
				            $opcion1_aux = "";
				         if($tipo_recurso==1){  $opcion1_aux = "";
				   }else if($tipo_recurso==2){  $opcion1_aux = "(Ordinario)";
				   }else if($tipo_recurso==3){  $opcion1_aux = "(Coordinado)";
				   }else if($tipo_recurso==4){  $opcion1_aux = "(Laee)";
				   }else if($tipo_recurso==5){  $opcion1_aux = "(Fides)";
				   	}else if($tipo_recurso==6){ $opcion1_aux = "(Ingreso Extraordinario)";
				   }else if($tipo_recurso==9){  $opcion1_aux = ""; }//fin else

				            $this->set('opcion1_aux', $opcion1_aux);
				            $this->set('opcion2_aux', $datos[0][0]['denominacion']);
				            $this->set('tipo_recurso', $tipo_recurso);
				            $this->set('clasificacion_recurso', $clasificacion_recurso);
							$this->set('total_presupuestado', $total_presupuestado);
							$this->set('monto_contratado', $monto_contratado);
							$this->set('diferencia', $diferencia);
							$this->set('por_monto_contratado', $por_monto_contratado);
							$this->set('por_total_presupuestado', $por_total_presupuestado);
							$this->set('por_diferencia', $por_diferencia);
							$this->set('rdm', $rdm);

							$this->set('tipo_top',           $this->data["graficos1"]["tipo_top"]);
							$this->set('DENO_ESTADO',        $this->data["graficos1"]["DENO_ESTADO"]);
						    $this->set('DENO_COD_TIPO_INST', $this->data["graficos1"]["DENO_COD_TIPO_INST"]);
						    $this->set('DENO_INST',          $this->data["graficos1"]["DENO_INST"]);
						    $this->set('DENO_REPUBLICA',     $this->data["graficos1"]["DENO_REPUBLICA"]);
			                $this->set('user',               $this->Session->read('nom_usuario'));






}//fin else





$this->set('var', $var);






}//fin function








 }//Fin class
?>
