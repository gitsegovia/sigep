<?php


 class scriptFuncionCorreccionesController extends AppController{
	var $uses = array('cfpd07_obras_cuerpo', 'cfpd07_obras_partidas', 'ccfd04_cierre_mes', 'cfpd05');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
 	var $name = "script_funcion_correcciones";

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
}//fin before filter




function cfpp07_correcion_cantaura_partida(){

   $this->layout="ajax";

   	$cod_presi     = $this->Session->read('SScodpresi');
    $cod_entidad   = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst      = $this->Session->read('SScodinst');
    $cod_dep       = $this->Session->read('SScoddep');
//    $ano           = $this->ano_ejecucion();
    $ano           = 2010;

$sql = "
		 select
				  a.cod_presi,
				  a.cod_entidad,
				  a.cod_tipo_inst,
				  a.cod_inst,
				  a.cod_dep,
				  a.ano_estimacion,
				  a.cod_obra,
				  a.cod_sector,
				  a.cod_programa,
				  a.cod_sub_prog,
				  a.cod_proyecto,
				  a.cod_activ_obra,
				  a.cod_partida,
				  a.cod_generica,
				  a.cod_especifica,
				  a.cod_sub_espec,
				  a.cod_auxiliar,
				  a.monto,
				  a.monto_contratado,
				  a.aumento_obras,
				  a.disminucion_obras,

				  b.tipo_recurso,
                  b.clasificacion_recurso

		 FROM  cfpd07_obras_partidas a, cfpd07_obras_cuerpo b

		 where  a.cod_presi       = ".$cod_presi."     and
			    a.cod_entidad     = ".$cod_entidad."   and
			    a.cod_tipo_inst   = ".$cod_tipo_inst." and
			    a.cod_inst        = ".$cod_inst."      and
			    a.cod_dep         = ".$cod_dep."       and
			    a.ano_estimacion  = ".$ano."           and

			    b.cod_presi       = a.cod_presi        and
			    b.cod_entidad     = a.cod_entidad      and
			    b.cod_tipo_inst   = a.cod_tipo_inst    and
			    b.cod_inst        = a.cod_inst         and
			    b.cod_dep         = a.cod_dep          and
			    b.ano_estimacion  = a.ano_estimacion   and
                b.cod_obra        = a.cod_obra
";


         	$cod_obra_dato=$this->cfpd07_obras_cuerpo->execute($sql);


				foreach($cod_obra_dato as $ve){

				                  $cod_presi             = $ve[0]["cod_presi"];
								  $cod_entidad           = $ve[0]["cod_entidad"];
								  $cod_tipo_inst         = $ve[0]["cod_tipo_inst"];
								  $cod_inst              = $ve[0]["cod_inst"];
								  $cod_dep               = $ve[0]["cod_dep"];
								  $ano_estimacion        = $ve[0]["ano_estimacion"];
								  $cod_obra              = $ve[0]["cod_obra"];
								  $cod_sector            = $ve[0]["cod_sector"];
								  $cod_programa          = $ve[0]["cod_programa"];
								  $cod_sub_prog          = $ve[0]["cod_sub_prog"];
								  $cod_proyecto          = $ve[0]["cod_proyecto"];
								  $cod_activ_obra        = $ve[0]["cod_activ_obra"];
								  $cod_partida           = $ve[0]["cod_partida"];
								  $cod_generica          = $ve[0]["cod_generica"];
								  $cod_especifica        = $ve[0]["cod_especifica"];
								  $cod_sub_espec         = $ve[0]["cod_sub_espec"];
								  $cod_auxiliar          = $ve[0]["cod_auxiliar"];
								  $monto                 = $ve[0]["monto"];
								  $monto_contratado      = $ve[0]["monto_contratado"];
								  $aumento_obras         = $ve[0]["aumento_obras"];
								  $disminucion_obras     = $ve[0]["disminucion_obras"];
								  $tipo_recurso          = $ve[0]["tipo_recurso"];
				                  $clasificacion_recurso = $ve[0]["clasificacion_recurso"];


				                  		    $d7  =  $cod_sector;
											$d8  =  $cod_programa;
											$d9  =  $cod_sub_prog;
											$d10 =  $cod_proyecto;
											$d11 =  $cod_activ_obra;
											$d12 =  $cod_partida;
											$d13 =  $cod_generica;
											$d14 =  $cod_especifica;
											$d15 =  $cod_sub_espec;
											$d17 =  $cod_auxiliar;

							        $sql_verificar1  ="cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano=".$ano_estimacion." and cod_sector=".$d7." and cod_programa=".$d8." and cod_sub_prog=".$d9." and cod_proyecto=".$d10." and cod_activ_obra=".$d11." and cod_partida=".$d12." and cod_generica=".$d13." and cod_especifica=".$d14." and cod_sub_espec=".$d15." and cod_auxiliar=".$d17." ";
									if($this->cfpd05->findCount($sql_verificar1)!=0){
									    $sql_re_delete  = "DELETE  FROM  cfpd05  where ".$sql_verificar1;
									    $sw = $this->cfpd07_obras_cuerpo->execute($sql_re_delete);
									}//fin if
								       	   if($tipo_recurso==1){//Ordinario
								       	   $cod_activ_obra_aux = 56;
									}else  if($tipo_recurso==3){//Laee
									       $cod_activ_obra_aux = 58;
                                    }else  if($tipo_recurso==4){//Fides
                                           $cod_activ_obra_aux = 57;
									}
									$sql_verificar1  ="cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano=".$ano_estimacion." and cod_sector=".$d7." and cod_programa=".$d8." and cod_sub_prog=".$d9." and cod_proyecto=".$d10." and cod_activ_obra=".$cod_activ_obra_aux." and cod_partida=".$d12." and cod_generica=".$d13." and cod_especifica=".$d14." and cod_sub_espec=".$d15." and cod_auxiliar=".$d17." ";
									if($this->cfpd05->findCount($sql_verificar1)!=0){
									    $sql_re  = "UPDATE cfpd05 SET asignacion_anual=asignacion_anual+".$monto." where ".$sql_verificar1;
									    $sw = $this->cfpd07_obras_cuerpo->execute($sql_re);
									}//fin if

									$sql_verificar1  ="cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano_estimacion=".$ano_estimacion." and cod_obra='".$cod_obra."' and cod_sector=".$d7." and cod_programa=".$d8." and cod_sub_prog=".$d9." and cod_proyecto=".$d10." and cod_activ_obra=".$d11." and cod_partida=".$d12." and cod_generica=".$d13." and cod_especifica=".$d14." and cod_sub_espec=".$d15." and cod_auxiliar=".$d17." ";
									if($this->cfpd07_obras_partidas->findCount($sql_verificar1)!=0){
									    $sql_re  = "UPDATE cfpd07_obras_partidas SET cod_activ_obra=".$cod_activ_obra_aux." where ".$sql_verificar1;
									    $sw = $this->cfpd07_obras_partidas->execute($sql_re);
									}//fin if


				}



}//fin function





















function cfpp07_correcion_cantaura_partida_2(){

   $this->layout="ajax";

   	$cod_presi     = $this->Session->read('SScodpresi');
    $cod_entidad   = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst      = $this->Session->read('SScodinst');
    $cod_dep       = $this->Session->read('SScoddep');
//    $ano           = $this->ano_ejecucion();
    $ano           = 2010;

$sql = "
		 select
				  a.cod_presi,
				  a.cod_entidad,
				  a.cod_tipo_inst,
				  a.cod_inst,
				  a.cod_dep,
				  a.ano_estimacion,
				  a.cod_obra,
				  a.cod_sector,
				  a.cod_programa,
				  a.cod_sub_prog,
				  a.cod_proyecto,
				  a.cod_activ_obra,
				  a.cod_partida,
				  a.cod_generica,
				  a.cod_especifica,
				  a.cod_sub_espec,
				  a.cod_auxiliar,
				  a.monto,
				  a.monto_contratado,
				  a.aumento_obras,
				  a.disminucion_obras,

				  b.tipo_recurso,
                  b.clasificacion_recurso,

                  (select  count(x.cod_dep) from cfpd05 x   where   x.cod_presi       = a.cod_presi        and
																    x.cod_entidad     = a.cod_entidad      and
																    x.cod_tipo_inst   = a.cod_tipo_inst    and
																    x.cod_inst        = a.cod_inst         and
																    x.cod_dep         = a.cod_dep          and
																    x.ano             = a.ano_estimacion   and
																    x.cod_sector      = a.cod_sector       and
																	x.cod_programa    = a.cod_programa     and
																	x.cod_sub_prog    = a.cod_sub_prog     and
																	x.cod_proyecto    = a.cod_proyecto     and
															        x.cod_activ_obra  = a.cod_activ_obra   and
																	x.cod_partida     = a.cod_partida      and
																	x.cod_generica    = a.cod_generica     and
																	x.cod_especifica  = a.cod_especifica   and
																	x.cod_sub_espec   = a.cod_sub_espec    and
																	x.cod_auxiliar    = a.cod_auxiliar     ) as contar

		 FROM  cfpd07_obras_partidas a, cfpd07_obras_cuerpo b

		 where  a.cod_presi       = ".$cod_presi."     and
			    a.cod_entidad     = ".$cod_entidad."   and
			    a.cod_tipo_inst   = ".$cod_tipo_inst." and
			    a.cod_inst        = ".$cod_inst."      and
			    a.cod_dep         = ".$cod_dep."       and
			    a.ano_estimacion  = ".$ano."           and

			    b.cod_presi       = a.cod_presi        and
			    b.cod_entidad     = a.cod_entidad      and
			    b.cod_tipo_inst   = a.cod_tipo_inst    and
			    b.cod_inst        = a.cod_inst         and
			    b.cod_dep         = a.cod_dep          and
			    b.ano_estimacion  = a.ano_estimacion   and
                b.cod_obra        = a.cod_obra
";


         	$cod_obra_dato=$this->cfpd07_obras_cuerpo->execute($sql);

				foreach($cod_obra_dato as $ve){

					if($ve[0]["contar"]==0){

				                  $cod_presi             = $ve[0]["cod_presi"];
								  $cod_entidad           = $ve[0]["cod_entidad"];
								  $cod_tipo_inst         = $ve[0]["cod_tipo_inst"];
								  $cod_inst              = $ve[0]["cod_inst"];
								  $cod_dep               = $ve[0]["cod_dep"];
								  $ano_estimacion        = $ve[0]["ano_estimacion"];
								  $cod_obra              = $ve[0]["cod_obra"];
								  $cod_sector            = $ve[0]["cod_sector"];
								  $cod_programa          = $ve[0]["cod_programa"];
								  $cod_sub_prog          = $ve[0]["cod_sub_prog"];
								  $cod_proyecto          = $ve[0]["cod_proyecto"];
								  $cod_activ_obra        = $ve[0]["cod_activ_obra"];
								  $cod_partida           = $ve[0]["cod_partida"];
								  $cod_generica          = $ve[0]["cod_generica"];
								  $cod_especifica        = $ve[0]["cod_especifica"];
								  $cod_sub_espec         = $ve[0]["cod_sub_espec"];
								  $cod_auxiliar          = $ve[0]["cod_auxiliar"];
								  $monto                 = $ve[0]["monto"];
								  $monto_contratado      = $ve[0]["monto_contratado"];
								  $aumento_obras         = $ve[0]["aumento_obras"];
								  $disminucion_obras     = $ve[0]["disminucion_obras"];
								  $tipo_recurso          = $ve[0]["tipo_recurso"];
				                  $clasificacion_recurso = $ve[0]["clasificacion_recurso"];

                                            $d1  = $this->verifica_SS(1);
											$d2  = $this->verifica_SS(2);
										 	$d3  = $this->verifica_SS(3);
										 	$d4  = $this->verifica_SS(4);
										 	$d5  = $this->verifica_SS(5);

										 	$d6  = $ano;

				                  		    $d7  =  $cod_sector;
											$d8  =  $cod_programa;
											$d9  =  $cod_sub_prog;
											$d10 =  $cod_proyecto;
											$d11 =  $cod_activ_obra;
											$d12 =  $cod_partida;
											$d13 =  $cod_generica;
											$d14 =  $cod_especifica;
											$d15 =  $cod_sub_espec;
											$d17 =  $cod_auxiliar;

									$ranmd = rand();

							        $sql_verificar1  = $ranmd."=".$ranmd." and cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and ano=".$ano_estimacion." and cod_sector=".$d7." and cod_programa=".$d8." and cod_sub_prog=".$d9." and cod_proyecto=".$d10." and cod_activ_obra=".$d11." and cod_partida=".$d12." and cod_generica=".$d13." and cod_especifica=".$d14." and cod_sub_espec=".$d15." and cod_auxiliar=".$d17." ";
									if($this->cfpd05->findCount($sql_verificar1)!=0){
									    $sql_re  = "UPDATE cfpd05 SET asignacion_anual=asignacion_anual+".$monto." where ".$sql_verificar1;
									    $sw = $this->cfpd07_obras_cuerpo->execute($sql_re);
									}else{
										if($d12=='404'){$cod_tipo_gasto='2';}else if($d12=='407'){$cod_tipo_gasto='4';}else{$cod_tipo_gasto='1';}
										$d31  = $tipo_recurso;
										$SQLINSERT="INSERT INTO cfpd05 (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,ano,cod_sector,cod_programa,cod_sub_prog,cod_proyecto,cod_activ_obra,cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar,
														cod_tipo_gasto, tipo_presupuesto,asignacion_anual,aumento_traslado_anual,disminucion_traslado_anual,credito_adicional_anual,rebaja_anual,
														compromiso_anual,causado_anual,pagado_anual,asignacion_ene,aumento_traslado_ene,disminucion_traslado_ene,credito_adicional_ene,rebaja_ene,
														compromiso_ene,causado_ene,pagado_ene,asignacion_feb,aumento_traslado_feb,disminucion_traslado_feb,credito_adicional_feb,rebaja_feb,
														compromiso_feb,causado_feb,pagado_feb,asignacion_mar,aumento_traslado_mar,disminucion_traslado_mar,credito_adicional_mar,rebaja_mar,
														compromiso_mar,causado_mar,pagado_mar,asignacion_abr,aumento_traslado_abr,disminucion_traslado_abr,credito_adicional_abr,rebaja_abr,
														compromiso_abr,causado_abr,pagado_abr,asignacion_may,aumento_traslado_may,disminucion_traslado_may,credito_adicional_may,rebaja_may,
														compromiso_may,causado_may,pagado_may,asignacion_jun,aumento_traslado_jun,disminucion_traslado_jun,credito_adicional_jun,rebaja_jun,
														compromiso_jun,causado_jun,pagado_jun,asignacion_jul,disminucion_traslado_jul,credito_adicional_jul,rebaja_jul,compromiso_jul,causado_jul,
														pagado_jul,asignacion_ago,aumento_traslado_ago,disminucion_traslado_ago,credito_adicional_ago,rebaja_ago,compromiso_ago,causado_ago,
														pagado_ago,asignacion_sep,aumento_traslado_sep,disminucion_traslado_sep,credito_adicional_sep,rebaja_sep,compromiso_sep,causado_sep,
														pagado_sep,asignacion_oct,aumento_traslado_oct,disminucion_traslado_oct,credito_adicional_oct,rebaja_oct, compromiso_oct, causado_oct, pagado_oct, asignacion_nov, aumento_traslado_nov, disminucion_traslado_nov, credito_adicional_nov, rebaja_nov, compromiso_nov, causado_nov, pagado_nov, asignacion_dic, aumento_traslado_dic, disminucion_traslado_dic, credito_adicional_dic, rebaja_dic, compromiso_dic, causado_dic, pagado_dic, precompromiso_congelado, precompromiso_requisicion, precompromiso_obras, precompromiso_fondo_avance, aumento_traslado_jul) VALUES";
								        $SQLINSERT .="($d1,$d2,$d3,$d4,$d5,$d6,$d7,$d8,$d9,$d10,$d11,".$d12.",$d13,$d14,$d15,$d17,$cod_tipo_gasto,$d31,".$monto.",0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)";
								        $this->cfpd05->execute($SQLINSERT);

									}

			      	}
				}



$this->render("cfpp07_correcion_cantaura_partida");


}//fin function



























}//fin class
?>