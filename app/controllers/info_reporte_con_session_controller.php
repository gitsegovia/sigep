<?php
class InfoReporteConSessionController extends AppController {
   var $name = 'info_reporte_con_session';
   var $uses = array('arrd01', 'arrd02', 'cpcd02', 'v_relacion_orden_compra_infogobierno', 'v_relacion_obras_infogobierno',
                     'v_relacion_servicio_infogobierno');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap','Infogob');




function checkSession(){
				if (!$this->Session->check('infogobierno')){
						$this->redirect('/infogobierno/salir_todo');
						exit();
				}
}//fin checksession



 function beforeFilter(){
 	$this->checkSession();
 }


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



function consutal_orden_compra_rif($var=null, $year=null, $pagina_1=null, $pagina_2=null){


	$username      = $this->Session->read('nom_usuario');
	$cod_presi     = $this->Session->read('SScodpresi');
	$cod_entidad   = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst      = $this->Session->read('SScodinst');
	$cod_dep       = $this->Session->read('SScoddep');

    $datos_session = $this->Session->read('infogobierno');


      if($var==1){$this->layout="ajax";

      	$rif_cedula = $datos_session["cedula_identidad"];
      	$datos_rif  = $this->cpcd02->findAll("upper(rif)=upper('".$rif_cedula."')  ");

      	$this->set("rif",          $datos_rif[0]["cpcd02"]["rif"]);
      	$this->set("denominacion", $datos_rif[0]["cpcd02"]["denominacion"]);

      	$this->set('pag_cant', 0);

		$datos  = $this->v_relacion_orden_compra_infogobierno->execute(" SELECT DISTINCT ano_orden_compra FROM v_relacion_orden_compra_infogobierno WHERE condicion_actividad = 1 and  upper(rif)=upper('".$rif_cedula."') ORDER BY ano_orden_compra ASC");
		if(count($datos)!=0){
			foreach($datos as $n){
				$lista[$n[0]['ano_orden_compra']]=$n[0]['ano_orden_compra'];
		    }
		}else{
			$lista=array('0'=>'No existen datos');
		}
		$this->set("lista_ano", $lista);


}else if($var==2){$this->layout="ajax";
	             if($pagina_1==null){$pagina_1=1;}
	             if($pagina_2==null){$pagina_2=1;}

	             $rif_cedula = $datos_session["cedula_identidad"];

                    $Tfilas=$this->v_relacion_orden_compra_infogobierno->findAll("rif='".$rif_cedula."' and ano_orden_compra='".$year."'    GROUP BY   cod_presi,
										     	    		                                                                                           cod_entidad,
										     	    		                                                                                           cod_tipo_inst,
										     	    		                                                                                           cod_inst,
										     	    		                                                                                           deno_cod_presi,
										     	    		                                                                                           deno_cod_entidad,
										     	    		                                                                                           deno_cod_tipo_inst,
										     	    		                                                                                           deno_cod_inst  ",
							                                                                                                                          "cod_presi,
										     	    		                                                                                           cod_entidad,
										     	    		                                                                                           cod_tipo_inst,
										     	    		                                                                                           cod_inst,
										     	    		                                                                                           deno_cod_presi,
										     	    		                                                                                           deno_cod_entidad,
										     	    		                                                                                           deno_cod_tipo_inst,
										     	    		                                                                                           deno_cod_inst");

 $Tfilas = count($Tfilas);
			         if($Tfilas!=0){
			        	$Tfilas=(int)ceil($Tfilas/1);
			     	    $datos_filas=$this->v_relacion_orden_compra_infogobierno->findAll("rif='".$rif_cedula."'  and ano_orden_compra='".$year."'  GROUP BY   cod_presi,
												     	    		                                                                                           cod_entidad,
												     	    		                                                                                           cod_tipo_inst,
												     	    		                                                                                           cod_inst,
												     	    		                                                                                           deno_cod_presi,
												     	    		                                                                                           deno_cod_entidad,
												     	    		                                                                                           deno_cod_tipo_inst,
												     	    		                                                                                           deno_cod_inst  ",
									                                                                                                                          "cod_presi,
												     	    		                                                                                           cod_entidad,
												     	    		                                                                                           cod_tipo_inst,
												     	    		                                                                                           cod_inst,
												     	    		                                                                                           deno_cod_presi,
												     	    		                                                                                           deno_cod_entidad,
												     	    		                                                                                           deno_cod_tipo_inst,
												     	    		                                                                                           deno_cod_inst",       "cod_presi, cod_entidad, cod_tipo_inst, cod_inst ASC",1,$pagina_1,null);


                        $cod_presi_2     = $datos_filas[0]["v_relacion_orden_compra_infogobierno"]["cod_presi"];
						$cod_entidad_2   = $datos_filas[0]["v_relacion_orden_compra_infogobierno"]["cod_entidad"];
						$cod_tipo_inst_2 = $datos_filas[0]["v_relacion_orden_compra_infogobierno"]["cod_tipo_inst"];
						$cod_inst_2      = $datos_filas[0]["v_relacion_orden_compra_infogobierno"]["cod_inst"];

						$deno_cod_presi     = $datos_filas[0]["v_relacion_orden_compra_infogobierno"]["deno_cod_presi"];
						$deno_cod_entidad   = $datos_filas[0]["v_relacion_orden_compra_infogobierno"]["deno_cod_entidad"];
						$deno_cod_tipo_inst = $datos_filas[0]["v_relacion_orden_compra_infogobierno"]["deno_cod_tipo_inst"];
						$deno_cod_inst      = $datos_filas[0]["v_relacion_orden_compra_infogobierno"]["deno_cod_inst"];

						$this->set("deno_cod_presi",     $deno_cod_presi);
						$this->set("deno_cod_entidad",   $deno_cod_entidad);
						$this->set("deno_cod_tipo_inst", $deno_cod_tipo_inst);
						$this->set("deno_cod_inst",      $deno_cod_inst);

						$condicion = "cod_presi='".$cod_presi_2."' and cod_entidad='".$cod_entidad_2."' and cod_tipo_inst='".$cod_tipo_inst_2."' and cod_inst='".$cod_inst_2."' and rif='".$rif_cedula."' and ano_orden_compra='".$year."' ";

						                            $Tfilas_2=$this->v_relacion_orden_compra_infogobierno->findCount($condicion);
											        if($Tfilas_2!=0){
											     	    $datos_filas_2=$this->v_relacion_orden_compra_infogobierno->findAll($condicion,null,"ano_orden_compra, numero_orden_compra, numero_pago ASC",50,$pagina_2,null);
												        $Tfilas_2=(int)ceil($Tfilas_2/50);
											        	$this->set('total_paginas_2',$Tfilas_2);
														$this->set('pagina_actual_2',$pagina_2);
														$this->set('pag_cant_2',$pagina_2.'/'.$Tfilas_2);
														$this->set('ultimo_2',$Tfilas_2);
												        $this->set("datos",$datos_filas_2);
												        $this->set('siguiente_2',$pagina_2+1);
														$this->set('anterior_2',$pagina_2-1);
											        }else{
											        	$this->set("datos",'');
											        }
                        $this->set('pag_cant_1',$pagina_1.'/'.$Tfilas);
				        $this->set('ultimo_1',$Tfilas);
				        $this->set('siguiente_1',$pagina_1+1);
						$this->set('anterior_1',$pagina_1-1);
						$this->bt_nav($Tfilas,$pagina_1);
			        }else{
			        	$this->set("datos",'');
			        }
	             $this->set("year",     $year);
	             $this->set("pagina_1", $pagina_1);
	             $this->set("pagina_2", $pagina_2);


}else if($var==3){$this->layout="ajax";

	              $rif_cedula = $datos_session["cedula_identidad"];


                    $Tfilas=$this->v_relacion_orden_compra_infogobierno->findAll("rif='".$rif_cedula."'  and ano_orden_compra='".$year."'     GROUP BY     cod_presi,
											     	    		                                                                                           cod_entidad,
											     	    		                                                                                           cod_tipo_inst,
											     	    		                                                                                           cod_inst,
											     	    		                                                                                           deno_cod_presi,
											     	    		                                                                                           deno_cod_entidad,
											     	    		                                                                                           deno_cod_tipo_inst,
											     	    		                                                                                           deno_cod_inst  ",
								                                                                                                                          "cod_presi,
											     	    		                                                                                           cod_entidad,
											     	    		                                                                                           cod_tipo_inst,
											     	    		                                                                                           cod_inst,
											     	    		                                                                                           deno_cod_presi,
											     	    		                                                                                           deno_cod_entidad,
											     	    		                                                                                           deno_cod_tipo_inst,
											     	    		                                                                                           deno_cod_inst");
 $Tfilas = count($Tfilas);
			        if($Tfilas!=0){
			        	$Tfilas=(int)ceil($Tfilas/1);
			     	    $datos_filas=$this->v_relacion_orden_compra_infogobierno->findAll("rif='".$rif_cedula."'  and ano_orden_compra='".$year."'  GROUP BY   cod_presi,
												     	    		                                                                                           cod_entidad,
												     	    		                                                                                           cod_tipo_inst,
												     	    		                                                                                           cod_inst,
												     	    		                                                                                           deno_cod_presi,
												     	    		                                                                                           deno_cod_entidad,
												     	    		                                                                                           deno_cod_tipo_inst,
												     	    		                                                                                           deno_cod_inst  ",
									                                                                                                                          "cod_presi,
												     	    		                                                                                           cod_entidad,
												     	    		                                                                                           cod_tipo_inst,
												     	    		                                                                                           cod_inst,
												     	    		                                                                                           deno_cod_presi,
												     	    		                                                                                           deno_cod_entidad,
												     	    		                                                                                           deno_cod_tipo_inst,
												     	    		                                                                                           deno_cod_inst",       "cod_presi, cod_entidad, cod_tipo_inst, cod_inst ASC",1,$pagina_1,null);


                        $cod_presi_2     = $datos_filas[0]["v_relacion_orden_compra_infogobierno"]["cod_presi"];
						$cod_entidad_2   = $datos_filas[0]["v_relacion_orden_compra_infogobierno"]["cod_entidad"];
						$cod_tipo_inst_2 = $datos_filas[0]["v_relacion_orden_compra_infogobierno"]["cod_tipo_inst"];
						$cod_inst_2      = $datos_filas[0]["v_relacion_orden_compra_infogobierno"]["cod_inst"];

						$deno_cod_presi     = $datos_filas[0]["v_relacion_orden_compra_infogobierno"]["deno_cod_presi"];
						$deno_cod_entidad   = $datos_filas[0]["v_relacion_orden_compra_infogobierno"]["deno_cod_entidad"];
						$deno_cod_tipo_inst = $datos_filas[0]["v_relacion_orden_compra_infogobierno"]["deno_cod_tipo_inst"];
						$deno_cod_inst      = $datos_filas[0]["v_relacion_orden_compra_infogobierno"]["deno_cod_inst"];

						$this->set("deno_cod_presi",     $deno_cod_presi);
						$this->set("deno_cod_entidad",   $deno_cod_entidad);
						$this->set("deno_cod_tipo_inst", $deno_cod_tipo_inst);
						$this->set("deno_cod_inst",      $deno_cod_inst);

						$condicion = "cod_presi='".$cod_presi_2."' and cod_entidad='".$cod_entidad_2."' and cod_tipo_inst='".$cod_tipo_inst_2."' and cod_inst='".$cod_inst_2."' and rif='".$rif_cedula."' and ano_orden_compra='".$year."' " ;

						                            $Tfilas_2=$this->v_relacion_orden_compra_infogobierno->findCount($condicion);
											        if($Tfilas_2!=0){
											     	    $datos_filas_2=$this->v_relacion_orden_compra_infogobierno->findAll($condicion,null,"ano_orden_compra, numero_orden_compra, numero_pago ASC",50,$pagina_2,null);
												        $Tfilas_2=(int)ceil($Tfilas_2/50);
											        	$this->set('total_paginas_2',$Tfilas_2);
														$this->set('pagina_actual_2',$pagina_2);
														$this->set('pag_cant_2',$pagina_2.'/'.$Tfilas_2);
														$this->set('ultimo_2',$Tfilas_2);
												        $this->set("datos",$datos_filas_2);
												        $this->set('siguiente_2',$pagina_2+1);
														$this->set('anterior_2',$pagina_2-1);
											        }else{
											        	$this->set("datos",'');
											        }
                        $this->set('pag_cant_1',$pagina_1.'/'.$Tfilas);
				        $this->set('ultimo_1',$Tfilas);
				        $this->set('siguiente_1',$pagina_1+1);
						$this->set('anterior_1',$pagina_1-1);
						$this->bt_nav($Tfilas,$pagina_1);
			        }else{
			        	$this->set("datos",'');
			        }
	             $this->set("year",     $year);
	             $this->set("pagina_1", $pagina_1);
	             $this->set("pagina_2", $pagina_2);


}else if($var==4){$this->layout="pdf";
	$rif_cedula = $datos_session["cedula_identidad"];
  	$datos_rif  = $this->cpcd02->findAll("upper(rif)=upper('".$rif_cedula."')  ");
  	$this->set("rif",          $datos_rif[0]["cpcd02"]["rif"]);
  	$this->set("denominacion", $datos_rif[0]["cpcd02"]["denominacion"]);
    $Tfilas_2  = $this->v_relacion_orden_compra_infogobierno->findAll(" ano_orden_compra='".$this->data["reporte"]["ano_documento"]."' and rif='".$datos_session["cedula_identidad"]."' ", null,"cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_compra, numero_orden_compra, numero_pago ASC" );
    $this->set("datos", $Tfilas_2);

}


$this->set("var", $var);

}//fin function















function consutal_obras_rif($var=null, $year=null, $pagina_1=null, $pagina_2=null){


	$username      = $this->Session->read('nom_usuario');
	$cod_presi     = $this->Session->read('SScodpresi');
	$cod_entidad   = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst      = $this->Session->read('SScodinst');
	$cod_dep       = $this->Session->read('SScoddep');

    $datos_session = $this->Session->read('infogobierno');


      if($var==1){$this->layout="ajax";

      	$rif_cedula = $datos_session["cedula_identidad"];
      	$datos_rif  = $this->cpcd02->findAll("upper(rif)=upper('".$rif_cedula."')  ");

      	$this->set("rif",          $datos_rif[0]["cpcd02"]["rif"]);
      	$this->set("denominacion", $datos_rif[0]["cpcd02"]["denominacion"]);

      	$this->set('pag_cant', 0);

		$datos  = $this->v_relacion_obras_infogobierno->execute(" SELECT DISTINCT ano_contrato_obra FROM v_relacion_obras_infogobierno WHERE condicion_actividad = 1 and upper(rif)=upper('".$rif_cedula."') ORDER BY ano_contrato_obra ASC");
		if(count($datos)!=0){
			foreach($datos as $n){
				$lista[$n[0]['ano_contrato_obra']]=$n[0]['ano_contrato_obra'];
		    }
		}else{
			$lista=array('0'=>'No existen datos');
		}
		$this->set("lista_ano", $lista);


}else if($var==2){$this->layout="ajax";
	             if($pagina_1==null){$pagina_1=1;}
	             if($pagina_2==null){$pagina_2=1;}

	             $rif_cedula = $datos_session["cedula_identidad"];

                    $Tfilas=$this->v_relacion_obras_infogobierno->findAll("rif='".$rif_cedula."' and ano_contrato_obra='".$year."'    GROUP BY     cod_presi,
									     	    		                                                                                           cod_entidad,
									     	    		                                                                                           cod_tipo_inst,
									     	    		                                                                                           cod_inst,
									     	    		                                                                                           deno_cod_presi,
									     	    		                                                                                           deno_cod_entidad,
									     	    		                                                                                           deno_cod_tipo_inst,
									     	    		                                                                                           deno_cod_inst  ",
						                                                                                                                          "cod_presi,
									     	    		                                                                                           cod_entidad,
									     	    		                                                                                           cod_tipo_inst,
									     	    		                                                                                           cod_inst,
									     	    		                                                                                           deno_cod_presi,
									     	    		                                                                                           deno_cod_entidad,
									     	    		                                                                                           deno_cod_tipo_inst,
									     	    		                                                                                           deno_cod_inst");

 $Tfilas = count($Tfilas);
			         if($Tfilas!=0){
			        	$Tfilas=(int)ceil($Tfilas/1);
			     	    $datos_filas=$this->v_relacion_obras_infogobierno->findAll("rif='".$rif_cedula."'  and ano_contrato_obra='".$year."' GROUP BY  cod_presi,
										     	    		                                                                                           cod_entidad,
										     	    		                                                                                           cod_tipo_inst,
										     	    		                                                                                           cod_inst,
										     	    		                                                                                           deno_cod_presi,
										     	    		                                                                                           deno_cod_entidad,
										     	    		                                                                                           deno_cod_tipo_inst,
										     	    		                                                                                           deno_cod_inst  ",
							                                                                                                                          "cod_presi,
										     	    		                                                                                           cod_entidad,
										     	    		                                                                                           cod_tipo_inst,
										     	    		                                                                                           cod_inst,
										     	    		                                                                                           deno_cod_presi,
										     	    		                                                                                           deno_cod_entidad,
										     	    		                                                                                           deno_cod_tipo_inst,
										     	    		                                                                                           deno_cod_inst",       "cod_presi, cod_entidad, cod_tipo_inst, cod_inst ASC",1,$pagina_1,null);


                        $cod_presi_2     = $datos_filas[0]["v_relacion_obras_infogobierno"]["cod_presi"];
						$cod_entidad_2   = $datos_filas[0]["v_relacion_obras_infogobierno"]["cod_entidad"];
						$cod_tipo_inst_2 = $datos_filas[0]["v_relacion_obras_infogobierno"]["cod_tipo_inst"];
						$cod_inst_2      = $datos_filas[0]["v_relacion_obras_infogobierno"]["cod_inst"];

						$deno_cod_presi     = $datos_filas[0]["v_relacion_obras_infogobierno"]["deno_cod_presi"];
						$deno_cod_entidad   = $datos_filas[0]["v_relacion_obras_infogobierno"]["deno_cod_entidad"];
						$deno_cod_tipo_inst = $datos_filas[0]["v_relacion_obras_infogobierno"]["deno_cod_tipo_inst"];
						$deno_cod_inst      = $datos_filas[0]["v_relacion_obras_infogobierno"]["deno_cod_inst"];

						$this->set("deno_cod_presi",     $deno_cod_presi);
						$this->set("deno_cod_entidad",   $deno_cod_entidad);
						$this->set("deno_cod_tipo_inst", $deno_cod_tipo_inst);
						$this->set("deno_cod_inst",      $deno_cod_inst);

						$condicion = "cod_presi='".$cod_presi_2."' and cod_entidad='".$cod_entidad_2."' and cod_tipo_inst='".$cod_tipo_inst_2."' and cod_inst='".$cod_inst_2."' and rif='".$rif_cedula."' and ano_contrato_obra='".$year."' ";

						                            $Tfilas_2=$this->v_relacion_obras_infogobierno->findCount($condicion);
											        if($Tfilas_2!=0){
											     	    $datos_filas_2=$this->v_relacion_obras_infogobierno->findAll($condicion,null,"ano_contrato_obra, numero_contrato_obra, numero_valuacion ASC",50,$pagina_2,null);
												        $Tfilas_2=(int)ceil($Tfilas_2/50);
											        	$this->set('total_paginas_2',$Tfilas_2);
														$this->set('pagina_actual_2',$pagina_2);
														$this->set('pag_cant_2',$pagina_2.'/'.$Tfilas_2);
														$this->set('ultimo_2',$Tfilas_2);
												        $this->set("datos",$datos_filas_2);
												        $this->set('siguiente_2',$pagina_2+1);
														$this->set('anterior_2',$pagina_2-1);
											        }else{
											        	$this->set("datos",'');
											        }
                        $this->set('pag_cant_1',$pagina_1.'/'.$Tfilas);
				        $this->set('ultimo_1',$Tfilas);
				        $this->set('siguiente_1',$pagina_1+1);
						$this->set('anterior_1',$pagina_1-1);
						$this->bt_nav($Tfilas,$pagina_1);
			        }else{
			        	$this->set("datos",'');
			        }
	             $this->set("year",     $year);
	             $this->set("pagina_1", $pagina_1);
	             $this->set("pagina_2", $pagina_2);


}else if($var==3){$this->layout="ajax";

	              $rif_cedula = $datos_session["cedula_identidad"];


                    $Tfilas=$this->v_relacion_obras_infogobierno->findAll("rif='".$rif_cedula."'  and ano_contrato_obra='".$year."'   GROUP BY     cod_presi,
									     	    		                                                                                           cod_entidad,
									     	    		                                                                                           cod_tipo_inst,
									     	    		                                                                                           cod_inst,
									     	    		                                                                                           deno_cod_presi,
									     	    		                                                                                           deno_cod_entidad,
									     	    		                                                                                           deno_cod_tipo_inst,
									     	    		                                                                                           deno_cod_inst  ",
						                                                                                                                          "cod_presi,
									     	    		                                                                                           cod_entidad,
									     	    		                                                                                           cod_tipo_inst,
									     	    		                                                                                           cod_inst,
									     	    		                                                                                           deno_cod_presi,
									     	    		                                                                                           deno_cod_entidad,
									     	    		                                                                                           deno_cod_tipo_inst,
									     	    		                                                                                           deno_cod_inst");
 $Tfilas = count($Tfilas);
			        if($Tfilas!=0){
			        	$Tfilas=(int)ceil($Tfilas/1);
			     	    $datos_filas=$this->v_relacion_obras_infogobierno->findAll("rif='".$rif_cedula."' and ano_contrato_obra='".$year."' GROUP BY   cod_presi,
										     	    		                                                                                           cod_entidad,
										     	    		                                                                                           cod_tipo_inst,
										     	    		                                                                                           cod_inst,
										     	    		                                                                                           deno_cod_presi,
										     	    		                                                                                           deno_cod_entidad,
										     	    		                                                                                           deno_cod_tipo_inst,
										     	    		                                                                                           deno_cod_inst  ",
							                                                                                                                          "cod_presi,
										     	    		                                                                                           cod_entidad,
										     	    		                                                                                           cod_tipo_inst,
										     	    		                                                                                           cod_inst,
										     	    		                                                                                           deno_cod_presi,
										     	    		                                                                                           deno_cod_entidad,
										     	    		                                                                                           deno_cod_tipo_inst,
										     	    		                                                                                           deno_cod_inst",       "cod_presi, cod_entidad, cod_tipo_inst, cod_inst ASC",1,$pagina_1,null);


                        $cod_presi_2     = $datos_filas[0]["v_relacion_obras_infogobierno"]["cod_presi"];
						$cod_entidad_2   = $datos_filas[0]["v_relacion_obras_infogobierno"]["cod_entidad"];
						$cod_tipo_inst_2 = $datos_filas[0]["v_relacion_obras_infogobierno"]["cod_tipo_inst"];
						$cod_inst_2      = $datos_filas[0]["v_relacion_obras_infogobierno"]["cod_inst"];

						$deno_cod_presi     = $datos_filas[0]["v_relacion_obras_infogobierno"]["deno_cod_presi"];
						$deno_cod_entidad   = $datos_filas[0]["v_relacion_obras_infogobierno"]["deno_cod_entidad"];
						$deno_cod_tipo_inst = $datos_filas[0]["v_relacion_obras_infogobierno"]["deno_cod_tipo_inst"];
						$deno_cod_inst      = $datos_filas[0]["v_relacion_obras_infogobierno"]["deno_cod_inst"];

						$this->set("deno_cod_presi",     $deno_cod_presi);
						$this->set("deno_cod_entidad",   $deno_cod_entidad);
						$this->set("deno_cod_tipo_inst", $deno_cod_tipo_inst);
						$this->set("deno_cod_inst",      $deno_cod_inst);

						$condicion = "cod_presi='".$cod_presi_2."' and cod_entidad='".$cod_entidad_2."' and cod_tipo_inst='".$cod_tipo_inst_2."' and cod_inst='".$cod_inst_2."' and rif='".$rif_cedula."' and ano_contrato_obra='".$year."' " ;

						                            $Tfilas_2=$this->v_relacion_obras_infogobierno->findCount($condicion);
											        if($Tfilas_2!=0){
											     	    $datos_filas_2=$this->v_relacion_obras_infogobierno->findAll($condicion,null,"ano_contrato_obra, numero_contrato_obra, numero_valuacion ASC",50,$pagina_2,null);
												        $Tfilas_2=(int)ceil($Tfilas_2/50);
											        	$this->set('total_paginas_2',$Tfilas_2);
														$this->set('pagina_actual_2',$pagina_2);
														$this->set('pag_cant_2',$pagina_2.'/'.$Tfilas_2);
														$this->set('ultimo_2',$Tfilas_2);
												        $this->set("datos",$datos_filas_2);
												        $this->set('siguiente_2',$pagina_2+1);
														$this->set('anterior_2',$pagina_2-1);
											        }else{
											        	$this->set("datos",'');
											        }
                        $this->set('pag_cant_1',$pagina_1.'/'.$Tfilas);
				        $this->set('ultimo_1',$Tfilas);
				        $this->set('siguiente_1',$pagina_1+1);
						$this->set('anterior_1',$pagina_1-1);
						$this->bt_nav($Tfilas,$pagina_1);
			        }else{
			        	$this->set("datos",'');
			        }
	             $this->set("year",     $year);
	             $this->set("pagina_1", $pagina_1);
	             $this->set("pagina_2", $pagina_2);


}else if($var==4){$this->layout="pdf";
	$rif_cedula = $datos_session["cedula_identidad"];
  	$datos_rif  = $this->cpcd02->findAll("upper(rif)=upper('".$rif_cedula."')  ");
  	$this->set("rif",          $datos_rif[0]["cpcd02"]["rif"]);
  	$this->set("denominacion", $datos_rif[0]["cpcd02"]["denominacion"]);
    $Tfilas_2  = $this->v_relacion_obras_infogobierno->findAll(" ano_contrato_obra='".$this->data["reporte"]["ano_documento"]."' and rif='".$datos_session["cedula_identidad"]."' ",null,"cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_contrato_obra, numero_contrato_obra, numero_valuacion ASC" );
    $this->set("datos", $Tfilas_2);


}


$this->set("var", $var);

}//fin function















function consutal_servicio_rif($var=null, $year=null, $pagina_1=null, $pagina_2=null){


	$username      = $this->Session->read('nom_usuario');
	$cod_presi     = $this->Session->read('SScodpresi');
	$cod_entidad   = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst      = $this->Session->read('SScodinst');
	$cod_dep       = $this->Session->read('SScoddep');

    $datos_session = $this->Session->read('infogobierno');


      if($var==1){$this->layout="ajax";

      	$rif_cedula = $datos_session["cedula_identidad"];
      	$datos_rif  = $this->cpcd02->findAll("upper(rif)=upper('".$rif_cedula."')  ");

      	$this->set("rif",          $datos_rif[0]["cpcd02"]["rif"]);
      	$this->set("denominacion", $datos_rif[0]["cpcd02"]["denominacion"]);

      	$this->set('pag_cant', 0);

		$datos  = $this->v_relacion_obras_infogobierno->execute(" SELECT DISTINCT ano_contrato_servicio FROM v_relacion_servicio_infogobierno WHERE condicion_actividad = 1 and upper(rif)=upper('".$rif_cedula."') ORDER BY ano_contrato_servicio ASC");
		if(count($datos)!=0){
			foreach($datos as $n){
				$lista[$n[0]['ano_contrato_servicio']]=$n[0]['ano_contrato_servicio'];
		    }
		}else{
			$lista=array('0'=>'No existen datos');
		}
		$this->set("lista_ano", $lista);


}else if($var==2){$this->layout="ajax";
	             if($pagina_1==null){$pagina_1=1;}
	             if($pagina_2==null){$pagina_2=1;}

	             $rif_cedula = $datos_session["cedula_identidad"];

                    $Tfilas=$this->v_relacion_servicio_infogobierno->findAll("rif='".$rif_cedula."' and ano_contrato_servicio='".$year."'    GROUP BY     cod_presi,
									     	    		                                                                                           cod_entidad,
									     	    		                                                                                           cod_tipo_inst,
									     	    		                                                                                           cod_inst,
									     	    		                                                                                           deno_cod_presi,
									     	    		                                                                                           deno_cod_entidad,
									     	    		                                                                                           deno_cod_tipo_inst,
									     	    		                                                                                           deno_cod_inst  ",
						                                                                                                                          "cod_presi,
									     	    		                                                                                           cod_entidad,
									     	    		                                                                                           cod_tipo_inst,
									     	    		                                                                                           cod_inst,
									     	    		                                                                                           deno_cod_presi,
									     	    		                                                                                           deno_cod_entidad,
									     	    		                                                                                           deno_cod_tipo_inst,
									     	    		                                                                                           deno_cod_inst");

 $Tfilas = count($Tfilas);
			         if($Tfilas!=0){
			        	$Tfilas=(int)ceil($Tfilas/1);
			     	    $datos_filas=$this->v_relacion_servicio_infogobierno->findAll("rif='".$rif_cedula."'  and ano_contrato_servicio='".$year."' GROUP BY  cod_presi,
										     	    		                                                                                           cod_entidad,
										     	    		                                                                                           cod_tipo_inst,
										     	    		                                                                                           cod_inst,
										     	    		                                                                                           deno_cod_presi,
										     	    		                                                                                           deno_cod_entidad,
										     	    		                                                                                           deno_cod_tipo_inst,
										     	    		                                                                                           deno_cod_inst  ",
							                                                                                                                          "cod_presi,
										     	    		                                                                                           cod_entidad,
										     	    		                                                                                           cod_tipo_inst,
										     	    		                                                                                           cod_inst,
										     	    		                                                                                           deno_cod_presi,
										     	    		                                                                                           deno_cod_entidad,
										     	    		                                                                                           deno_cod_tipo_inst,
										     	    		                                                                                           deno_cod_inst",       "cod_presi, cod_entidad, cod_tipo_inst, cod_inst ASC",1,$pagina_1,null);


                        $cod_presi_2     = $datos_filas[0]["v_relacion_servicio_infogobierno"]["cod_presi"];
						$cod_entidad_2   = $datos_filas[0]["v_relacion_servicio_infogobierno"]["cod_entidad"];
						$cod_tipo_inst_2 = $datos_filas[0]["v_relacion_servicio_infogobierno"]["cod_tipo_inst"];
						$cod_inst_2      = $datos_filas[0]["v_relacion_servicio_infogobierno"]["cod_inst"];

						$deno_cod_presi     = $datos_filas[0]["v_relacion_servicio_infogobierno"]["deno_cod_presi"];
						$deno_cod_entidad   = $datos_filas[0]["v_relacion_servicio_infogobierno"]["deno_cod_entidad"];
						$deno_cod_tipo_inst = $datos_filas[0]["v_relacion_servicio_infogobierno"]["deno_cod_tipo_inst"];
						$deno_cod_inst      = $datos_filas[0]["v_relacion_servicio_infogobierno"]["deno_cod_inst"];

						$this->set("deno_cod_presi",     $deno_cod_presi);
						$this->set("deno_cod_entidad",   $deno_cod_entidad);
						$this->set("deno_cod_tipo_inst", $deno_cod_tipo_inst);
						$this->set("deno_cod_inst",      $deno_cod_inst);

						$condicion = "cod_presi='".$cod_presi_2."' and cod_entidad='".$cod_entidad_2."' and cod_tipo_inst='".$cod_tipo_inst_2."' and cod_inst='".$cod_inst_2."' and rif='".$rif_cedula."' and ano_contrato_servicio='".$year."' ";

						                            $Tfilas_2=$this->v_relacion_servicio_infogobierno->findCount($condicion);
											        if($Tfilas_2!=0){
											     	    $datos_filas_2=$this->v_relacion_servicio_infogobierno->findAll($condicion,null,"ano_contrato_servicio, numero_contrato_servicio, numero_valuacion ASC",50,$pagina_2,null);
												        $Tfilas_2=(int)ceil($Tfilas_2/50);
											        	$this->set('total_paginas_2',$Tfilas_2);
														$this->set('pagina_actual_2',$pagina_2);
														$this->set('pag_cant_2',$pagina_2.'/'.$Tfilas_2);
														$this->set('ultimo_2',$Tfilas_2);
												        $this->set("datos",$datos_filas_2);
												        $this->set('siguiente_2',$pagina_2+1);
														$this->set('anterior_2',$pagina_2-1);
											        }else{
											        	$this->set("datos",'');
											        }
                        $this->set('pag_cant_1',$pagina_1.'/'.$Tfilas);
				        $this->set('ultimo_1',$Tfilas);
				        $this->set('siguiente_1',$pagina_1+1);
						$this->set('anterior_1',$pagina_1-1);
						$this->bt_nav($Tfilas,$pagina_1);
			        }else{
			        	$this->set("datos",'');
			        }
	             $this->set("year",     $year);
	             $this->set("pagina_1", $pagina_1);
	             $this->set("pagina_2", $pagina_2);


}else if($var==3){$this->layout="ajax";

	              $rif_cedula = $datos_session["cedula_identidad"];


                    $Tfilas=$this->v_relacion_servicio_infogobierno->findAll("rif='".$rif_cedula."'  and ano_contrato_servicio='".$year."'   GROUP BY     cod_presi,
									     	    		                                                                                           cod_entidad,
									     	    		                                                                                           cod_tipo_inst,
									     	    		                                                                                           cod_inst,
									     	    		                                                                                           deno_cod_presi,
									     	    		                                                                                           deno_cod_entidad,
									     	    		                                                                                           deno_cod_tipo_inst,
									     	    		                                                                                           deno_cod_inst  ",
						                                                                                                                          "cod_presi,
									     	    		                                                                                           cod_entidad,
									     	    		                                                                                           cod_tipo_inst,
									     	    		                                                                                           cod_inst,
									     	    		                                                                                           deno_cod_presi,
									     	    		                                                                                           deno_cod_entidad,
									     	    		                                                                                           deno_cod_tipo_inst,
									     	    		                                                                                           deno_cod_inst");
 $Tfilas = count($Tfilas);
			        if($Tfilas!=0){
			        	$Tfilas=(int)ceil($Tfilas/1);
			     	    $datos_filas=$this->v_relacion_servicio_infogobierno->findAll("rif='".$rif_cedula."' and ano_contrato_servicio='".$year."' GROUP BY   cod_presi,
										     	    		                                                                                           cod_entidad,
										     	    		                                                                                           cod_tipo_inst,
										     	    		                                                                                           cod_inst,
										     	    		                                                                                           deno_cod_presi,
										     	    		                                                                                           deno_cod_entidad,
										     	    		                                                                                           deno_cod_tipo_inst,
										     	    		                                                                                           deno_cod_inst  ",
							                                                                                                                          "cod_presi,
										     	    		                                                                                           cod_entidad,
										     	    		                                                                                           cod_tipo_inst,
										     	    		                                                                                           cod_inst,
										     	    		                                                                                           deno_cod_presi,
										     	    		                                                                                           deno_cod_entidad,
										     	    		                                                                                           deno_cod_tipo_inst,
										     	    		                                                                                           deno_cod_inst",       "cod_presi, cod_entidad, cod_tipo_inst, cod_inst ASC",1,$pagina_1,null);


                        $cod_presi_2     = $datos_filas[0]["v_relacion_servicio_infogobierno"]["cod_presi"];
						$cod_entidad_2   = $datos_filas[0]["v_relacion_servicio_infogobierno"]["cod_entidad"];
						$cod_tipo_inst_2 = $datos_filas[0]["v_relacion_servicio_infogobierno"]["cod_tipo_inst"];
						$cod_inst_2      = $datos_filas[0]["v_relacion_servicio_infogobierno"]["cod_inst"];

						$deno_cod_presi     = $datos_filas[0]["v_relacion_servicio_infogobierno"]["deno_cod_presi"];
						$deno_cod_entidad   = $datos_filas[0]["v_relacion_servicio_infogobierno"]["deno_cod_entidad"];
						$deno_cod_tipo_inst = $datos_filas[0]["v_relacion_servicio_infogobierno"]["deno_cod_tipo_inst"];
						$deno_cod_inst      = $datos_filas[0]["v_relacion_servicio_infogobierno"]["deno_cod_inst"];

						$this->set("deno_cod_presi",     $deno_cod_presi);
						$this->set("deno_cod_entidad",   $deno_cod_entidad);
						$this->set("deno_cod_tipo_inst", $deno_cod_tipo_inst);
						$this->set("deno_cod_inst",      $deno_cod_inst);

						$condicion = "cod_presi='".$cod_presi_2."' and cod_entidad='".$cod_entidad_2."' and cod_tipo_inst='".$cod_tipo_inst_2."' and cod_inst='".$cod_inst_2."' and rif='".$rif_cedula."' and ano_contrato_servicio='".$year."' " ;

						                            $Tfilas_2=$this->v_relacion_servicio_infogobierno->findCount($condicion);
											        if($Tfilas_2!=0){
											     	    $datos_filas_2=$this->v_relacion_servicio_infogobierno->findAll($condicion,null,"ano_contrato_servicio, numero_contrato_servicio, numero_valuacion ASC",50,$pagina_2,null);
												        $Tfilas_2=(int)ceil($Tfilas_2/50);
											        	$this->set('total_paginas_2',$Tfilas_2);
														$this->set('pagina_actual_2',$pagina_2);
														$this->set('pag_cant_2',$pagina_2.'/'.$Tfilas_2);
														$this->set('ultimo_2',$Tfilas_2);
												        $this->set("datos",$datos_filas_2);
												        $this->set('siguiente_2',$pagina_2+1);
														$this->set('anterior_2',$pagina_2-1);
											        }else{
											        	$this->set("datos",'');
											        }
                        $this->set('pag_cant_1',$pagina_1.'/'.$Tfilas);
				        $this->set('ultimo_1',$Tfilas);
				        $this->set('siguiente_1',$pagina_1+1);
						$this->set('anterior_1',$pagina_1-1);
						$this->bt_nav($Tfilas,$pagina_1);
			        }else{
			        	$this->set("datos",'');
			        }
	             $this->set("year",     $year);
	             $this->set("pagina_1", $pagina_1);
	             $this->set("pagina_2", $pagina_2);


}else if($var==4){$this->layout="pdf";
	$rif_cedula = $datos_session["cedula_identidad"];
  	$datos_rif  = $this->cpcd02->findAll("upper(rif)=upper('".$rif_cedula."')  ");
  	$this->set("rif",          $datos_rif[0]["cpcd02"]["rif"]);
  	$this->set("denominacion", $datos_rif[0]["cpcd02"]["denominacion"]);
    $Tfilas_2  = $this->v_relacion_servicio_infogobierno->findAll(" ano_contrato_servicio='".$this->data["reporte"]["ano_documento"]."' and rif='".$datos_session["cedula_identidad"]."' ",null,"cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_contrato_servicio, numero_contrato_servicio, numero_valuacion ASC" );
    $this->set("datos", $Tfilas_2);


}


$this->set("var", $var);

}//fin function








}// fin class

?>