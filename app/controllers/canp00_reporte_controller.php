<?php
class Canp00ReporteController extends AppController {
   var $name = 'canp00_reporte';
   var $uses = array("arrd01", "ccfd03_instalacion", "cscd01_catalogo", "cscd01_snc_tipo", "v_cfpd05_tipo_gasto2", "cfpd05",
                     "arrd02", "arrd01", "arrd03", "arrd04", "arrd05",  'v_relacion_orden_compra_infogobierno', 'v_relacion_obras_infogobierno',
                     'v_relacion_servicio_infogobierno', 'cpcd02', "cfpd03", "shd000_arranque", "v_cfpd03_denominacion_partida",'cugd02_dependencia','cscd05_consumo_productos_dep','cscd01_unidad_medida');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap','Infogob');


function checkSession(){

        if (!$this->Session->check('Usuario'))
        {
            $this->redirect('/salir');
            exit();
        }
}

 function beforeFilter(){
    $this->checkSession();

 }


function buscar_rif_cedula_1($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function

function buscar_rif_cedula_2($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
    $cod_presi = $this->Session->read('SScodpresi');
  	$cod_entidad = $this->Session->read('SScodentidad');
  	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
  	$cod_inst = $this->Session->read('SScodinst');
  	$cod_dep = $this->Session->read('SScoddep');
    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					$condicion = $this->busca_separado(array('rif', 'denominacion'), $var2);
					$Tfilas=$this->cpcd02->findCount($condicion);
			        if($Tfilas!=0){
			        	$pagina=1;
			        	$Tfilas=(int)ceil($Tfilas/50);
			        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
						$this->set('total_paginas',$Tfilas);
						$this->set('pagina_actual',$pagina);
						$this->set('ultimo',$Tfilas);
			     	    $datos_filas=$this->cpcd02->findAll($condicion,null,"rif ASC",50,1,null);
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
						$condicion = $this->busca_separado(array('rif', 'denominacion'), $var22);
						$Tfilas=$this->cpcd02->findCount($condicion);
				        if($Tfilas!=0){
				        	$pagina=$var3;
				        	$Tfilas=(int)ceil($Tfilas/50);
				        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
							$this->set('total_paginas',$Tfilas);
							$this->set('pagina_actual',$pagina);
							$this->set('ultimo',$Tfilas);
				     	    $datos_filas=$this->cpcd02->findAll($condicion,null,"rif ASC",50,$pagina,null);
					        $this->set("datosFILAS",$datos_filas);
					        $this->set('siguiente',$pagina+1);
							$this->set('anterior',$pagina-1);
							$this->bt_nav($Tfilas,$pagina);
				          }else{
				        	$this->set("datosFILAS",'');
				          }
                 }//fin else
$this->set("opcion1",$var1);
$this->set("opcion2",$var2);
$this->set("opcion3",$var3);
}//fin function





function buscar_rif_cedula_3($var1=null, $var2=null){

	$this->layout="ajax";


$_SESSION["canp00"]["rif_cedula"] = $var2;


      if($var1==1){


$this->consutal_orden_compra_rif(1);
$this->render("consutal_orden_compra_rif");

}else if($var1==2){

$this->consutal_obras_rif(1);
$this->render("consutal_obras_rif");

}else if($var1==3){

$this->consutal_servicio_rif(1);
$this->render("consutal_servicio_rif");

}//fin else




}//fin function




function consutal_orden_compra_rif($var=null, $year=null, $pagina_1=null, $pagina_2=null){


	$username      = $this->Session->read('nom_usuario');
	$cod_presi     = $this->Session->read('SScodpresi');
	$cod_entidad   = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst      = $this->Session->read('SScodinst');
	$cod_dep       = $this->Session->read('SScoddep');

    $datos_session = $this->Session->read('canp00');



        if($var=="si"){$this->layout="ajax";





 }else  if($var==1){$this->layout="ajax";

      	$rif_cedula = $datos_session["rif_cedula"];
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

	             $rif_cedula = $datos_session["rif_cedula"];

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

						$condicion = "cod_presi='".$cod_presi_2."' and cod_entidad='".$cod_entidad_2."' and cod_tipo_inst='".$cod_tipo_inst_2."' and cod_inst='".$cod_inst_2."' and rif='".$rif_cedula."' and ano_orden_compra='".$year."'  ";

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

	              $rif_cedula = $datos_session["rif_cedula"];


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
	$rif_cedula = $datos_session["rif_cedula"];
  	$datos_rif  = $this->cpcd02->findAll("upper(rif)=upper('".$rif_cedula."')  ");
  	$this->set("rif",          $datos_rif[0]["cpcd02"]["rif"]);
  	$this->set("denominacion", $datos_rif[0]["cpcd02"]["denominacion"]);
    $Tfilas_2  = $this->v_relacion_orden_compra_infogobierno->findAll(" ano_orden_compra='".$this->data["reporte"]["ano_documento"]."' and rif='".$datos_session["rif_cedula"]."' ", null,"cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_orden_compra, numero_orden_compra, numero_pago ASC" );
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

    $datos_session = $this->Session->read('canp00');



        if($var=="si"){$this->layout="ajax";


 }else  if($var==1){$this->layout="ajax";

      	$rif_cedula = $datos_session["rif_cedula"];
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

	             $rif_cedula = $datos_session["rif_cedula"];

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

						$condicion = "cod_presi='".$cod_presi_2."' and cod_entidad='".$cod_entidad_2."' and cod_tipo_inst='".$cod_tipo_inst_2."' and cod_inst='".$cod_inst_2."' and rif='".$rif_cedula."' and ano_contrato_obra='".$year."'  ";

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

	              $rif_cedula = $datos_session["rif_cedula"];


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

						$condicion = "cod_presi='".$cod_presi_2."' and cod_entidad='".$cod_entidad_2."' and cod_tipo_inst='".$cod_tipo_inst_2."' and cod_inst='".$cod_inst_2."' and rif='".$rif_cedula."' and ano_contrato_obra='".$year."'  " ;

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
	$rif_cedula = $datos_session["rif_cedula"];
  	$datos_rif  = $this->cpcd02->findAll("upper(rif)=upper('".$rif_cedula."')  ");
  	$this->set("rif",          $datos_rif[0]["cpcd02"]["rif"]);
  	$this->set("denominacion", $datos_rif[0]["cpcd02"]["denominacion"]);
    $Tfilas_2  = $this->v_relacion_obras_infogobierno->findAll(" ano_contrato_obra='".$this->data["reporte"]["ano_documento"]."' and rif='".$datos_session["rif_cedula"]."' ", null,"cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_contrato_obra, numero_contrato_obra, numero_valuacion ASC" );
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
    $datos_session = $this->Session->read('canp00');


        if($var=="si"){$this->layout="ajax";


 }else  if($var==1){$this->layout="ajax";

      	$rif_cedula = $datos_session["rif_cedula"];
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

	             $rif_cedula = $datos_session["rif_cedula"];

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

	              $rif_cedula = $datos_session["rif_cedula"];


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
    $rif_cedula = $datos_session["rif_cedula"];
  	$datos_rif  = $this->cpcd02->findAll("upper(rif)=upper('".$rif_cedula."')  ");
  	$this->set("rif",          $datos_rif[0]["cpcd02"]["rif"]);
  	$this->set("denominacion", $datos_rif[0]["cpcd02"]["denominacion"]);

    $Tfilas_2  = $this->v_relacion_servicio_infogobierno->findAll(" ano_contrato_servicio='".$this->data["reporte"]["ano_documento"]."' and rif='".$datos_session["rif_cedula"]."' ",null,"cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_contrato_servicio, numero_contrato_servicio, numero_valuacion ASC" );
    $this->set("datos", $Tfilas_2);
}


$this->set("var", $var);

}//fin function








function consulta_cumplimiento_metas($var1=null, $var2=null, $pagina=null){


	  if($var1==1){ $this->layout="ajax";
                    $datos  = $this->cfpd03->execute(" SELECT DISTINCT ano FROM cfpd03  ORDER BY ano ASC");
					if(count($datos)!=0){
						foreach($datos as $n){
							$lista[$n[0]['ano']]=$n[0]['ano'];
					    }
					}else{
						$lista=array('0'=>'No existen datos');
					}
					$this->set("ano_lista", $lista);

					$datos2 = $this->arrd01->execute(" SELECT DISTINCT ano_arranque  FROM ccfd03_instalacion  ORDER BY ano_arranque DESC");
		            $this->set("ano_ejecucion" , $datos2[0][0]["ano_arranque"]);

		            $lista =  $this->arrd01->generateList(null, 'denominacion ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.denominacion');
		            $this->set('vector_presi', $lista);
		            $this->set("cod_presi_seleccion", 1);



}else if($var1==2){  $this->layout="ajax";

    if($var2==null){

          $pagina = 1;
	      $campos    = "  ";
	      $sql          = " ";
          $sql2         = " ";
	      $tipo=0;
	      $deno_presi   = "";
	      $deno_entidad = "";
	      $deno_inst    = "";
	      $deno_tipo_inst = "";
	      $cod_partida_aux = "";

	                                 if(!empty($this->data["datos"]["cod_presi"])){
									  	if($this->data["datos"]["cod_presi"]!="TODO"){
						                          $sql       .= " cod_presi = '".$this->data["datos"]["cod_presi"]."' ";
						                          $campos    .= " cod_presi";

						                          $lista_1    =  $this->arrd01->findAll("cod_presi=".$this->data["datos"]["cod_presi"]);
						                          $deno_presi = $lista_1[0]["arrd01"]["denominacion"];
									  	}else{
									  		     $sql  .= " 1=1 ";
									  		     $sql2 .= " 1=1 ";

									  	}
									  }

                                      if(!empty($this->data["datos"]["cod_entidad"])){
									  	if($this->data["datos"]["cod_entidad"]!="TODO"){
						                          $sql       .=" and cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";
						                          $campos    .= ",   cod_entidad";
						                          $tipo = 1;

						                          $lista_2      =  $this->arrd02->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]);
						                          $deno_entidad = $lista_2[0]["arrd02"]["denominacion"];
									  	}
									  }

                                       if(!empty($this->data["datos"]["cod_tipo_inst"])){
									  	if($this->data["datos"]["cod_tipo_inst"]!="TODO"){
						                          $sql       .=" and cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";
						                          $campos    .= ",   cod_tipo_inst";
						                          $tipo = 2;

						                          $lista_3        =  $this->arrd03->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_tipo_inst=".$this->data["datos"]["cod_tipo_inst"]);
						                          $deno_tipo_inst = $lista_3[0]["arrd03"]["denominacion"];
									  	 }
                                       }

                                      if(!empty($this->data["datos"]["cod_inst"])){
									  	if($this->data["datos"]["cod_inst"]!="TODO"){
						                          $sql       .=" and cod_inst = '".$this->data["datos"]["cod_inst"]."' ";
						                          $campos    .= ",   cod_inst";
						                          $tipo = 3;

						                          $lista_4   =  $this->arrd04->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]." and cod_inst=".$this->data["datos"]["cod_inst"]);
						                          $deno_inst = $lista_4[0]["arrd04"]["denominacion"];
									  	}
									  }
									  if(!empty($this->data["datos"]["ano_consolidado"])){
									  	if($this->data["datos"]["ano_consolidado"]!="TODO"){
						                          $sql       .=" and ano = '".$this->data["datos"]["ano_consolidado"]."' ";
						                          $campos    .= ",   ano";
									  	}
									  }



                        $group_by  = " GROUP BY ".$campos." ,cod_partida
															,cod_generica
															,cod_especifica
															,cod_sub_espec
															,cod_auxiliar
															,deno_partida
															,deno_generica
															,deno_especifica
															,deno_sub_espe
															,deno_auxiliar";
                        $campos   .= "  ,cod_partida
										,cod_generica
										,cod_especifica
										,cod_sub_espec
										,cod_auxiliar
										,deno_partida
										,deno_generica
										,deno_especifica
										,deno_sub_espe
										,deno_auxiliar

										,SUM(estimacion_inicial)   as   estimacion_inicial
										,SUM(ingresos_adicionales) as   ingresos_adicionales
										,SUM(rebajas)              as   rebajas
										,SUM(monto_facturado)      as   monto_facturado
										,SUM(monto_cobrado)        as   monto_cobrado";



$_SESSION["sql_cumplimiento_meta"]      = $sql;
$_SESSION["group_by_cumplimiento_meta"] = $group_by;
$_SESSION["campos_cumplimiento_meta"]   = $campos;
$_SESSION["year_cumplimiento_meta"]     = $this->data["datos"]["ano_consolidado"];

$_SESSION["tipo_top_cumplimiento_meta"]              = $tipo;
$_SESSION["DENO_ESTADO_cumplimiento_meta"]           = $deno_entidad;
$_SESSION["DENO_COD_TIPO_INST_cumplimiento_meta"]    = $deno_tipo_inst;
$_SESSION["DENO_INST_cumplimiento_meta"]             = $deno_inst;
$_SESSION["DENO_REPUBLICA_cumplimiento_meta"]        = $deno_presi;


            $this->set('tipo_top', $tipo);
		    $this->set('DENO_ESTADO', $deno_entidad);
			$this->set('DENO_COD_TIPO_INST', $deno_tipo_inst);
			$this->set('DENO_INST', $deno_inst);
			$this->set('DENO_REPUBLICA', $deno_presi);


}else{

$tipo           = $_SESSION["tipo_top_cumplimiento_meta"];
$deno_entidad   = $_SESSION["DENO_ESTADO_cumplimiento_meta"];
$deno_tipo_inst = $_SESSION["DENO_COD_TIPO_INST_cumplimiento_meta"];
$deno_inst      = $_SESSION["DENO_INST_cumplimiento_meta"];
$deno_presi     = $_SESSION["DENO_REPUBLICA_cumplimiento_meta"];

$pagina   = $var2;
$sql      = $_SESSION["sql_cumplimiento_meta"];
$group_by = $_SESSION["group_by_cumplimiento_meta"];
$campos   = $_SESSION["campos_cumplimiento_meta"];



            $this->set('tipo_top', $tipo);
		    $this->set('DENO_ESTADO', $deno_entidad);
			$this->set('DENO_COD_TIPO_INST', $deno_tipo_inst);
			$this->set('DENO_INST', $deno_inst);
			$this->set('DENO_REPUBLICA', $deno_presi);

}//fin function


			            $Tfilas=count($this->v_cfpd03_denominacion_partida->findAll($sql."  ".$group_by, $campos));
				        if($Tfilas!=0){
				        	$Tfilas=(int)ceil($Tfilas/50);
				        	$this->set('total_paginas',$Tfilas);
							$this->set('pagina_actual',$pagina);
							$this->set('pag_cant',$pagina.'/'.$Tfilas);
							$this->set('ultimo',$Tfilas);
				     	    $datos_filas=$this->v_cfpd03_denominacion_partida->findAll($sql."  ".$group_by, $campos, "cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC",50,$pagina,null);
					        $this->set("datos2",$datos_filas);
					        $this->set('siguiente',$pagina+1);
							$this->set('anterior',$pagina-1);
							$this->bt_nav($Tfilas,$pagina);


							if($pagina==$Tfilas){

                                 $datos_filas2=$this->v_cfpd03_denominacion_partida->findAll($sql."  ".$group_by, $campos, "cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC",null,null,null);
                                 $total_a = 0;
							     $total_b = 0;
							     $total_c = 0;
							     $total_d = 0;

			                           foreach($datos_filas2 as $ve){

												  $estimacion_inicial    = $ve[0]['estimacion_inicial'];
												  $ingresos_adicionales  = $ve[0]['ingresos_adicionales'];
												  $rebajas               = $ve[0]['rebajas'];
												  $monto_facturado       = $ve[0]['monto_facturado'];
												  $monto_cobrado         = $ve[0]['monto_cobrado'];

												  $monto_estimado     = ($estimacion_inicial+$ingresos_adicionales)-$rebajas;
												  $monto_recaudado    = $monto_cobrado;

												        if($monto_estimado > $monto_recaudado){
												  	$monto_por_recaudar = $monto_estimado - $monto_recaudado;
												    $monto_supervati    = 0;
												  }else if ($monto_recaudado > $monto_estimado ){
												  	$monto_por_recaudar = 0;
												    $monto_supervati    = $monto_recaudado - $monto_estimado;
												  }else{
												  	$monto_por_recaudar = 0;
												    $monto_supervati    = 0;
												  }



												  $total_a += $monto_estimado;
												  $total_b += $monto_recaudado;
												  $total_c += $monto_por_recaudar;
												  $total_d += $monto_supervati;


			                           }//fin foreach

			                           $this->set('total_a_general',$total_a);
			                           $this->set('total_b_general',$total_b);
			                           $this->set('total_c_general',$total_c);
			                           $this->set('total_d_general',$total_d);

							}//fin if

				        }else{
				        	$this->set("datos2",'');
				        	$this->set('total_paginas',0);
							$this->set('pagina_actual',0);
							$this->set('pag_cant',0);
							$this->set('ultimo',0);
				     	    $this->set("datos2","");
					        $this->set('siguiente',0);
							$this->set('anterior',0);
				        }



}else if($var1==3){  $this->layout="pdf";

$sql      = $_SESSION["sql_cumplimiento_meta"];
$group_by = $_SESSION["group_by_cumplimiento_meta"];
$campos   = $_SESSION["campos_cumplimiento_meta"];
$year     = $_SESSION["year_cumplimiento_meta"];

$datos_filas2=$this->v_cfpd03_denominacion_partida->findAll($sql."  ".$group_by, $campos, "cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC",null,null,null);
$this->set("datos2",$datos_filas2);

if($year=="TODO"){
	$_SESSION["ano_report"] = "TODOS";
}else{
	$_SESSION["ano_report"] = $year;
}

                $this->set('tipo_top',           $this->data["canp00_reporte"]["tipo_top"]);
				$this->set('DENO_ESTADO',        $this->data["canp00_reporte"]["DENO_ESTADO"]);
			    $this->set('DENO_COD_TIPO_INST', $this->data["canp00_reporte"]["DENO_COD_TIPO_INST"]);
			    $this->set('DENO_INST',          $this->data["canp00_reporte"]["DENO_INST"]);
			    $this->set('DENO_REPUBLICA',     $this->data["canp00_reporte"]["DENO_REPUBLICA"]);

}//fin else



$this->set("opcion", $var1);

}//fin function








function relacion_obras_proyectadas($var1=null){
set_time_limit(0);

		      if($var1==1){ $this->layout = "ajax";

			      	$datos  = $this->arrd01->execute(" SELECT DISTINCT ano_estimacion  FROM cfpd07_obras_cuerpo ");
			      	$datos2 = $this->arrd01->execute(" SELECT DISTINCT ano_arranque    FROM ccfd03_instalacion  ORDER BY ano_arranque DESC");

						if(count($datos)!=0){
							foreach($datos as $n){
								$cod[]  = $n[0]['ano_estimacion'];
								$deno[] = $n[0]['ano_estimacion'];
							}
							$lista=array_combine($cod, $deno);
						}else{
							$lista=array('0'=>'No se encuentra presente en ninguna nomina');
						}

					$this->set("ano_estimacion", $lista);
					$this->set("ano_ejecucion" , $datos2[0][0]["ano_arranque"]);

					$lista =  $this->arrd01->generateList(null, 'denominacion ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.denominacion');
		            $this->set('vector_presi', $lista);
		            $this->set("cod_presi_seleccion", 1);


		}else if($var1==2){ $this->layout = "pdf";


			$consolidado     = $this->data["datos"]["radio_nivel_consulta"];
			$ano_consolidado = $this->data["datos"]["ano_consolidado"];

			$sql = "";


									  if(!empty($this->data["datos"]["cod_presi"])){

									  	if($this->data["datos"]["cod_presi"]!="TODO"){

						                          $sql .=" cod_presi = '".$this->data["datos"]["cod_presi"]."' ";

									  	}else{
									  		     $sql .= " 1=1 ";

									  	}
									  }

									  if(!empty($this->data["datos"]["ano_consolidado"])){

									  	if($this->data["datos"]["ano_consolidado"]!="TODO"){

						                          $sql .="  and ano_estimacion = '".$this->data["datos"]["ano_consolidado"]."' ";

									  	}
									  }

                                      if(!empty($this->data["datos"]["cod_entidad"])){

									  	if($this->data["datos"]["cod_entidad"]!="TODO"){

						                          $sql .=" and cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";

									  	}
									  }

                                       if(!empty($this->data["datos"]["cod_tipo_inst"])){

									  	if($this->data["datos"]["cod_tipo_inst"]!="TODO"){

						                          $sql .=" and cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";

									  	 }
                                       }

                                      if(!empty($this->data["datos"]["cod_inst"])){

									  	if($this->data["datos"]["cod_inst"]!="TODO"){

						                          $sql .=" and cod_inst = '".$this->data["datos"]["cod_inst"]."' ";

									  	}
									  }


					     $ordenar = " ORDER BY
										       ano_estimacion,
										       cod_presi,
											   cod_entidad,
											   cod_tipo_inst,
											   cod_inst,
										       codigo_prod_serv,
											   cod_obra ";

					     $datos = $this->arrd01->execute(" SELECT * FROM v_cfpd07_cuerpo_vs_cobd01_cuerpo WHERE ".$sql." ".$ordenar);

					     $this->set("datos", $datos);


		}//fin else


$this->set("vista", $var1);



}//fin fucntion







function relacion_obras_proyectadas_segun_snc($var1=null, $var2=null, $var3=null){


set_time_limit(0);


		      if($var1==1){ $this->layout = "ajax";

			      	$datos  = $this->arrd01->execute(" SELECT DISTINCT ano_estimacion  FROM cfpd07_obras_cuerpo ");
			      	$datos2 = $this->arrd01->execute(" SELECT DISTINCT ano_arranque    FROM ccfd03_instalacion  ORDER BY ano_arranque DESC");

						if(count($datos)!=0){
							foreach($datos as $n){
								$cod[]  = $n[0]['ano_estimacion'];
								$deno[] = $n[0]['ano_estimacion'];
							}
							$lista=array_combine($cod, $deno);
						}else{
							$lista=array('0'=>'No se encuentra presente ningun cod_snc');
						}

					$this->set("ano_estimacion", $lista);
					$this->set("ano_ejecucion" , $datos2[0][0]["ano_arranque"]);

					$lista =  $this->arrd01->generateList(null, 'denominacion ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.denominacion');
		            $this->set('vector_presi', $lista);
		            $this->set("cod_presi_seleccion", 1);


		 }else if($var1==2){

	    	$this->layout = "ajax";

	    	    if(strtoupper($var2)=="TODO"){
		    		$sql= "";
		    	}else{
		    	 	$sql= "and a.ano_estimacion='".$var2."'";
		    	}

	    	$lista  = $this->arrd01->execute(" SELECT
	    	                                   a.codigo_prod_serv,
	    	                                  (SELECT dd.denominacion FROM cscd01_catalogo dd WHERE dd.codigo_prod_serv=a.codigo_prod_serv ) as deno_codigo_prod_serv,
										      (SELECT ddd.denominacion FROM cscd01_snc_tipo ddd WHERE ddd.cod_tipo=(SELECT dddd.cod_snc FROM cscd01_catalogo dddd WHERE dddd.codigo_prod_serv=a.codigo_prod_serv)) as deno_cod_snc,
										      (SELECT dddd.cod_snc FROM cscd01_catalogo dddd WHERE dddd.codigo_prod_serv=a.codigo_prod_serv) as cod_snc

	    			                          FROM cfpd07_obras_cuerpo a WHERE (a.codigo_prod_serv IS NOT NULL and  a.codigo_prod_serv!=0) ".$sql." GROUP BY a.codigo_prod_serv ORDER BY deno_cod_snc;
	    			");




			          if(count($lista)!=0){
							foreach($lista as $n){
								if($n[0]['cod_snc']!=""){
									$cod[]  = $n[0]['cod_snc'];
									$deno[] = $n[0]['cod_snc']." - ".$n[0]['deno_cod_snc'];
								}
							}
							$lista=array_combine($cod, $deno);
						}else{
							$lista=array('0'=>'No se encuentra presente ningun cod_snc');
						}

			$this->set('vector', $lista);
			$this->set("opcion", $var3);


		}else if($var1==5){

	    	$this->layout = "ajax";
			$this->set("ano_ejecucion" , $var2);

			echo"<script>document.getElementById('capa_carga_2').innerHTML=''; </script>";


	   	}else if($var1==3){ $this->layout = "ajax";

	   		$cscd01_catalogo   =  $this->cscd01_snc_tipo->findAll("cod_tipo='".$var2."' ");

			foreach($cscd01_catalogo as $aux2){
				$cod_snc = $aux2['cscd01_snc_tipo']['cod_tipo'];
				$denominacion_snc = $aux2['cscd01_snc_tipo']['denominacion'];

			}//fin foreach


		echo'<script>';
		echo"   document.getElementById('cod_snc').value='".$cod_snc."';  ";
		echo"   document.getElementById('denominacion').value='".$denominacion_snc."';  ";
		echo'</script>';

		}else if($var1==4){ $this->layout = "pdf";


			$consolidado     = $this->data["datos"]["radio_nivel_consulta"];
			$ano_consolidado = $this->data["datos"]["ano_consolidado"];
			$sql = "";


									  if(!empty($this->data["datos"]["cod_presi"])){

									  	if($this->data["datos"]["cod_presi"]!="TODO"){

						                          $sql .=" cod_presi = '".$this->data["datos"]["cod_presi"]."' ";

									  	}else{
									  		     $sql .= " 1=1 ";

									  	}
									  }
									  if(!empty($this->data["datos"]["ano_consolidado"])){

									  	if($this->data["datos"]["ano_consolidado"]!="TODO"){

						                          $sql .=" and ano_estimacion = '".$this->data["datos"]["ano_consolidado"]."' ";

									  	}
									  }

                                      if(!empty($this->data["datos"]["cod_entidad"])){

									  	if($this->data["datos"]["cod_entidad"]!="TODO"){

						                          $sql .=" and cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";

									  	}
									  }

                                       if(!empty($this->data["datos"]["cod_tipo_inst"])){

									  	if($this->data["datos"]["cod_tipo_inst"]!="TODO"){

						                          $sql .=" and cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";

									  	 }
                                       }

                                      if(!empty($this->data["datos"]["cod_inst"])){

									  	if($this->data["datos"]["cod_inst"]!="TODO"){

						                          $sql .=" and cod_inst = '".$this->data["datos"]["cod_inst"]."' ";

									  	}
									  }


									  if($this->data["datos"]["tipo_consolidacion"]==2){

									  	if(!empty($this->data["datos"]["cod_snc_select"])){

                                              $sql .=" and cod_snc = '".$this->data["datos"]["cod_snc_select"]."' ";

									  	}
									  }


					     $ordenar = " ORDER BY
										       ano_estimacion,
										       cod_snc,
										       cod_presi,
											   cod_entidad,
											   cod_tipo_inst,
											   cod_inst,
										       cod_dep,
										       cod_dep_original,
											   cod_obra ";

					     $datos = $this->arrd01->execute(" SELECT * FROM v_cfpd07_cuerpo_vs_cobd01_cuerpo WHERE ".$sql." ".$ordenar);



					     $this->set("datos", $datos);


		}//fin else


$this->set("vista", $var1);



}//fin fucntion




















function cfpd05_situacion_credito_partida_1($var=null){



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

	      $this->layout="pdf";

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
											   	 $sql_aux   = " and a.tipo_presupuesto = ".$this->data["datos"]["tipo_presupuesto"];
											   }
									   }


                 $condicion        = $sql." and a.modelo_reporte=9 ".$sql_aux." GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_partida, a.denominacion ";


                 $datos = $this->cfpd05->execute("SELECT
                                                          a.cod_presi,
                                                          a.cod_entidad,
                                                          a.cod_tipo_inst,
                                                          a.cod_inst,
					                 		              a.cod_partida,
					                 		              a.denominacion,
					                 		              (SELECT aa.denominacion FROM arrd02 aa WHERE
													                                                  aa.cod_presi    =  a.cod_presi   and
													                                                  aa.cod_entidad  =  a.cod_entidad

													                                                 ) as deno_cod_entidad,

													      (SELECT bb.denominacion FROM arrd03 bb WHERE
													                                                  bb.cod_presi      =  a.cod_presi   and
													                                                  bb.cod_tipo_inst  =  a.cod_tipo_inst

													                                                 ) as deno_cod_tipo_inst,

													      (SELECT cc.denominacion FROM arrd04 cc WHERE
													                                                  cc.cod_presi      =  a.cod_presi     and
													                                                  cc.cod_entidad    =  a.cod_entidad   and
													                                                  cc.cod_tipo_inst  =  a.cod_tipo_inst and
													                                                  cc.cod_inst       =  a.cod_inst

													                                                 ) as deno_cod_inst,

							    						  SUM(a.asignacion_anual)      as asignacion_anual,
														  SUM(a.aumento)               as aumento,
														  SUM(a.disminucion)           as disminucion,
														  SUM(a.total_asignacion)      as total_asignacion,
														  SUM(a.compromiso)            as compromiso,
														  SUM(a.causado)               as causado,
														  SUM(a.pagado)                as pagado,
														  SUM(a.deuda)                 as deuda,
														  SUM(a.disponibilidad)        as disponibilidad

						 		      FROM v_credito_agrupado_inst2 a where ".$condicion."

						 		      ORDER BY
					                 		              a.cod_presi,
                                                          a.cod_entidad,
                                                          a.cod_tipo_inst,
                                                          a.cod_inst,
                                                          a.cod_partida ASC;   ");



            $this->set('tipo_presupuesto', $tipo_presupuesto_aux);
			$this->set('tipo_top', $tipo);
			$this->set('year',     $this->data["datos"]["ano_consolidado"]);

			$this->set('datos', $datos);


			$this->set('DENO_ESTADO', $deno_entidad);
			$this->set('DENO_COD_TIPO_INST', $deno_tipo_inst);
			$this->set('DENO_INST', $deno_inst);
			$this->set('DENO_REPUBLICA', $deno_presi);




}//fin function





$this->set('var', $var);


}//fin function













function cfpd05_situacion_credito_partida_2($var=null){



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

	      $this->layout="pdf";

          $sql = "";
	      $group_by = "";
	      $tipo=0;
	      $deno_presi   = "";
	      $deno_entidad = "";
	      $deno_inst    = "";
	      $deno_tipo_inst = "";

	                                 if(!empty($this->data["datos"]["cod_presi"])){
									  	if($this->data["datos"]["cod_presi"]!="TODO"){
						                          $sql        .= " a.cod_presi = '".$this->data["datos"]["cod_presi"]."' ";
						                          $group_by   .= " a.cod_presi, ";
						                          $lista_1    =  $this->arrd01->findAll("cod_presi=".$this->data["datos"]["cod_presi"]);
						                          $deno_presi = $lista_1[0]["arrd01"]["denominacion"];
									  	}else{
									  		     $sql .= " 1=1 ";

									  	}
									  }

                                      if(!empty($this->data["datos"]["cod_entidad"])){
									  	if($this->data["datos"]["cod_entidad"]!="TODO"){
						                          $sql        .=" and a.cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";
						                          $group_by   .= "a.cod_entidad, ";
						                          $tipo = 1;

						                          $lista_2      =  $this->arrd02->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]);
						                          $deno_entidad = $lista_2[0]["arrd02"]["denominacion"];
									  	}
									  }

                                       if(!empty($this->data["datos"]["cod_tipo_inst"])){
									  	if($this->data["datos"]["cod_tipo_inst"]!="TODO"){
						                          $sql        .=" and a.cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";
						                          $group_by   .= "a.cod_tipo_inst, ";
						                          $tipo = 2;

						                          $lista_3        =  $this->arrd03->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_tipo_inst=".$this->data["datos"]["cod_tipo_inst"]);
						                          $deno_tipo_inst = $lista_3[0]["arrd03"]["denominacion"];
									  	 }
                                       }

                                      if(!empty($this->data["datos"]["cod_inst"])){
									  	if($this->data["datos"]["cod_inst"]!="TODO"){
						                          $sql        .=" and a.cod_inst = '".$this->data["datos"]["cod_inst"]."' ";
						                          $group_by   .= "a.cod_inst, ";
						                          $tipo = 3;

						                          $lista_4   =  $this->arrd04->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]." and cod_inst=".$this->data["datos"]["cod_inst"]);
						                          $deno_inst = $lista_4[0]["arrd04"]["denominacion"];
									  	}
									  }
									  if(!empty($this->data["datos"]["ano_consolidado"])){
									  	if($this->data["datos"]["ano_consolidado"]!="TODO"){
						                          $sql         .=" and a.ano = '".$this->data["datos"]["ano_consolidado"]."' ";
						                          $group_by    .= "a.ano, ";
									  	}
									  }
									  if(!empty($this->data["datos"]["tipo_presupuesto"])){
									  	             $tipo_presupuesto_aux = $this->data["datos"]["tipo_presupuesto"];
							  	             if($this->data["datos"]["tipo_presupuesto"]==6){
											   	 $sql_aux = "";
											   }else{
											   	 $sql_aux   = " and a.tipo_presupuesto = ".$this->data["datos"]["tipo_presupuesto"];
											   }
									   }


                 $condicion        = $sql." and a.modelo_reporte=9 ".$sql_aux."  GROUP BY ".$group_by." a.cod_partida, a.denominacion ";


                 $datos = $this->cfpd05->execute("SELECT
                                                          ".$group_by."
					                 		              a.cod_partida,
					                 		              a.denominacion,
					                 		              SUM(a.asignacion_anual)      as asignacion_anual,
														  SUM(a.aumento)               as aumento,
														  SUM(a.disminucion)           as disminucion,
														  SUM(a.total_asignacion)      as total_asignacion,
														  SUM(a.compromiso)            as compromiso,
														  SUM(a.causado)               as causado,
														  SUM(a.pagado)                as pagado,
														  SUM(a.deuda)                 as deuda,
														  SUM(a.disponibilidad)        as disponibilidad

						 		      FROM v_credito_agrupado_inst2 a where ".$condicion."

						 		      ORDER BY  a.cod_partida ASC;   ");



            $this->set('tipo_presupuesto', $tipo_presupuesto_aux);
			$this->set('tipo_top', $tipo);
			$this->set('year',     $this->data["datos"]["ano_consolidado"]);

			$this->set('datos', $datos);


			$this->set('DENO_ESTADO', $deno_entidad);
			$this->set('DENO_COD_TIPO_INST', $deno_tipo_inst);
			$this->set('DENO_INST', $deno_inst);
			$this->set('DENO_REPUBLICA', $deno_presi);




}//fin function





$this->set('var', $var);


}//fin function



function cfpd05_situacion_credito_sector_2($var=null){



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
	  $this->layout="pdf";
	  $sql = "";
	  $group_by = "";
	  $tipo=0;
	  $deno_presi   = "";
	  $deno_entidad = "";
	  $deno_inst    = "";
	  $deno_tipo_inst = "";
	  $cond_sector = "";

	     if(!empty($this->data["datos"]["cod_presi"])){
		  	if($this->data["datos"]["cod_presi"]!="TODO"){
	                  $sql        .= " a.cod_presi = '".$this->data["datos"]["cod_presi"]."' ";
	                  $group_by   .= " a.cod_presi, ";
	                  $lista_1    =  $this->arrd01->findAll("cod_presi=".$this->data["datos"]["cod_presi"]);
	                  $deno_presi = $lista_1[0]["arrd01"]["denominacion"];
	                  $cond_sector .=" x.cod_presi=a.cod_presi and ";
		  	}else{
		  		     $sql .= " 1=1 ";
		  	}
		  }

          if(!empty($this->data["datos"]["cod_entidad"])){
		  	if($this->data["datos"]["cod_entidad"]!="TODO"){
                      $sql        .=" and a.cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";
                      $group_by   .= "a.cod_entidad, ";
                      $tipo = 1;
                      $lista_2      =  $this->arrd02->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]);
                      $deno_entidad = $lista_2[0]["arrd02"]["denominacion"];
                      $cond_sector .=" x.cod_entidad=a.cod_entidad and ";
		  	}
		  }

	       if(!empty($this->data["datos"]["cod_tipo_inst"])){
		  	if($this->data["datos"]["cod_tipo_inst"]!="TODO"){
	                  $sql        .=" and a.cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";
	                  $group_by   .= "a.cod_tipo_inst, ";
	                  $tipo = 2;
	                  $lista_3        =  $this->arrd03->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_tipo_inst=".$this->data["datos"]["cod_tipo_inst"]);
	                  $deno_tipo_inst = $lista_3[0]["arrd03"]["denominacion"];
	                  $cond_sector .=" x.cod_tipo_inst=a.cod_tipo_inst and ";
		  	 }
	       }

          if(!empty($this->data["datos"]["cod_inst"])){
		  	if($this->data["datos"]["cod_inst"]!="TODO"){
                      $sql        .=" and a.cod_inst = '".$this->data["datos"]["cod_inst"]."' ";
                      $group_by   .= "a.cod_inst, ";
                      $tipo = 3;
                      $lista_4   =  $this->arrd04->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]." and cod_inst=".$this->data["datos"]["cod_inst"]);
                      $deno_inst = $lista_4[0]["arrd04"]["denominacion"];
                      $cond_sector .=" x.cod_inst=a.cod_inst and ";
		  	}
		  }
		  if(!empty($this->data["datos"]["ano_consolidado"])){
		  	if($this->data["datos"]["ano_consolidado"]!="TODO"){
                      $sql         .=" and a.ano = '".$this->data["datos"]["ano_consolidado"]."' ";
                      $group_by    .= "a.ano, ";
		  	}
		  }
		  if(!empty($this->data["datos"]["tipo_presupuesto"])){
		  	             $tipo_presupuesto_aux = $this->data["datos"]["tipo_presupuesto"];
  	             if($this->data["datos"]["tipo_presupuesto"]==6){
				   	 $sql_aux = "";
				   }else{
				   	 $sql_aux   = " and a.tipo_presupuesto = ".$this->data["datos"]["tipo_presupuesto"];
				   }
		   }
           $condicion        = $sql." and a.modelo_reporte=1 ".$sql_aux."  GROUP BY ".$group_by." a.cod_sector ";
           $datos = $this->cfpd05->execute("SELECT
                                                          ".$group_by."
					                 		              a.cod_sector,
					                 		              (SELECT x.denominacion from cfpd02_sector x WHERE ".$cond_sector."  x.cod_sector=a.cod_sector ORDER BY x.cod_sector ASC LIMIT 1) as denominacion,
					                 		              SUM(a.asignacion_anual)      as asignacion_anual,
														  SUM(a.aumento)               as aumento,
														  SUM(a.disminucion)           as disminucion,
														  SUM(a.total_asignacion)      as total_asignacion,
														  SUM(a.compromiso)            as compromiso,
														  SUM(a.causado)               as causado,
														  SUM(a.pagado)                as pagado,
														  SUM(a.deuda)                 as deuda,
														  SUM(a.disponibilidad)        as disponibilidad

						 		      FROM v_credito_agrupado_inst2 a where ".$condicion."" .

						 		      "ORDER BY  a.cod_sector ASC;   ");

            $this->set('tipo_presupuesto', $tipo_presupuesto_aux);
			$this->set('tipo_top', $tipo);
			$this->set('year',     $this->data["datos"]["ano_consolidado"]);
			$this->set('datos', $datos);
			$this->set('DENO_ESTADO', $deno_entidad);
			$this->set('DENO_COD_TIPO_INST', $deno_tipo_inst);
			$this->set('DENO_INST', $deno_inst);
			$this->set('DENO_REPUBLICA', $deno_presi);

}//

$this->set('var', $var);


}//fin sector


function cfpd05_situacion_credito_sector_1($var=null){
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
	$this->layout="pdf";
	$sql = "";
	$group_by = " GROUP BY ";
	$tipo=0;
	$deno_presi   = "";
	$deno_entidad = "";
	$deno_inst    = "";
	$deno_tipo_inst = "";
	$cond_sector =" ";

     if(!empty($this->data["datos"]["cod_presi"])){
	  	if($this->data["datos"]["cod_presi"]!="TODO"){
                  $sql      .= " a.cod_presi = '".$this->data["datos"]["cod_presi"]."' ";
                  $group_by .= " a.cod_presi";
                  $lista_1    =  $this->arrd01->findAll("cod_presi=".$this->data["datos"]["cod_presi"]);
                  $deno_presi = $lista_1[0]["arrd01"]["denominacion"];
                  $cond_sector .=" x.cod_presi=a.cod_presi and ";
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
                  $cond_sector .=" x.cod_entidad=a.cod_entidad and ";
	  	}
	  }

       if(!empty($this->data["datos"]["cod_tipo_inst"])){
	  	if($this->data["datos"]["cod_tipo_inst"]!="TODO"){
                  $sql .=" and a.cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";
                  $group_by .= ", a.cod_tipo_inst";
                  $tipo = 2;
                  $lista_3        =  $this->arrd03->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_tipo_inst=".$this->data["datos"]["cod_tipo_inst"]);
                  $deno_tipo_inst = $lista_3[0]["arrd03"]["denominacion"];
                  $cond_sector .=" x.cod_tipo_inst=a.cod_tipo_inst and ";
	  	 }
       }

      if(!empty($this->data["datos"]["cod_inst"])){
	  	if($this->data["datos"]["cod_inst"]!="TODO"){
                  $sql .=" and a.cod_inst = '".$this->data["datos"]["cod_inst"]."' ";
                  $group_by .= ", a.cod_inst";
                  $tipo = 3;
                  $lista_4   =  $this->arrd04->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]." and cod_inst=".$this->data["datos"]["cod_inst"]);
                  $deno_inst = $lista_4[0]["arrd04"]["denominacion"];
                  $cond_sector .=" x.cod_inst=a.cod_inst and ";
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
			   	 $sql_aux   = " and a.tipo_presupuesto = ".$this->data["datos"]["tipo_presupuesto"];
			   }
	   }

	$condicion        = $sql." and a.modelo_reporte=1 ".$sql_aux." GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_sector, a.denominacion ";
	$datos = $this->cfpd05->execute("SELECT
                                                          a.cod_presi,
                                                          a.cod_entidad,
                                                          a.cod_tipo_inst,
                                                          a.cod_inst,
					                 		              a.cod_sector,
					                 		              (SELECT x.denominacion from cfpd02_sector x WHERE ".$cond_sector."  x.cod_sector=a.cod_sector ORDER BY x.cod_sector ASC LIMIT 1) as denominacion,
					                 		              (SELECT aa.denominacion FROM arrd02 aa WHERE
													                                                  aa.cod_presi    =  a.cod_presi   and
													                                                  aa.cod_entidad  =  a.cod_entidad

													                                                 ) as deno_cod_entidad,

													      (SELECT bb.denominacion FROM arrd03 bb WHERE
													                                                  bb.cod_presi      =  a.cod_presi   and
													                                                  bb.cod_tipo_inst  =  a.cod_tipo_inst

													                                                 ) as deno_cod_tipo_inst,

													      (SELECT cc.denominacion FROM arrd04 cc WHERE
													                                                  cc.cod_presi      =  a.cod_presi     and
													                                                  cc.cod_entidad    =  a.cod_entidad   and
													                                                  cc.cod_tipo_inst  =  a.cod_tipo_inst and
													                                                  cc.cod_inst       =  a.cod_inst

													                                                 ) as deno_cod_inst,

							    						  SUM(a.asignacion_anual)      as asignacion_anual,
														  SUM(a.aumento)               as aumento,
														  SUM(a.disminucion)           as disminucion,
														  SUM(a.total_asignacion)      as total_asignacion,
														  SUM(a.compromiso)            as compromiso,
														  SUM(a.causado)               as causado,
														  SUM(a.pagado)                as pagado,
														  SUM(a.deuda)                 as deuda,
														  SUM(a.disponibilidad)        as disponibilidad

						 		      FROM v_credito_agrupado_inst2 a where ".$condicion."

						 		      ORDER BY
					                 		              a.cod_presi,
                                                          a.cod_entidad,
                                                          a.cod_tipo_inst,
                                                          a.cod_inst,
                                                          a.cod_sector ASC;   ");



            $this->set('tipo_presupuesto', $tipo_presupuesto_aux);
			$this->set('tipo_top', $tipo);
			$this->set('year',     $this->data["datos"]["ano_consolidado"]);

			$this->set('datos', $datos);


			$this->set('DENO_ESTADO', $deno_entidad);
			$this->set('DENO_COD_TIPO_INST', $deno_tipo_inst);
			$this->set('DENO_INST', $deno_inst);
			$this->set('DENO_REPUBLICA', $deno_presi);




}//fin function





$this->set('var', $var);


}//fin function sector






function cfpd05_situacion_credito_sub_partida_1($var=null){



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

	      $this->layout="pdf";

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
											   	 $sql_aux   = " and a.tipo_presupuesto = ".$this->data["datos"]["tipo_presupuesto"];
											   }
									   }


                 $condicion        = $sql." and a.modelo_reporte=13 ".$sql_aux." GROUP BY a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.denominacion ";


                 $datos = $this->cfpd05->execute("SELECT
                                                          a.cod_presi,
                                                          a.cod_entidad,
                                                          a.cod_tipo_inst,
                                                          a.cod_inst,
					                 		              a.cod_partida,
					                 		              a.cod_generica,
					                 		              a.cod_especifica,
					                 		              a.cod_sub_espec,
					                 		              a.denominacion,
					                 		              (SELECT aa.denominacion FROM arrd02 aa WHERE
													                                                  aa.cod_presi    =  a.cod_presi   and
													                                                  aa.cod_entidad  =  a.cod_entidad

													                                                 ) as deno_cod_entidad,

													      (SELECT bb.denominacion FROM arrd03 bb WHERE
													                                                  bb.cod_presi      =  a.cod_presi   and
													                                                  bb.cod_tipo_inst  =  a.cod_tipo_inst

													                                                 ) as deno_cod_tipo_inst,

													      (SELECT cc.denominacion FROM arrd04 cc WHERE
													                                                  cc.cod_presi      =  a.cod_presi     and
													                                                  cc.cod_entidad    =  a.cod_entidad   and
													                                                  cc.cod_tipo_inst  =  a.cod_tipo_inst and
													                                                  cc.cod_inst       =  a.cod_inst

													                                                 ) as deno_cod_inst,

							    						  SUM(a.asignacion_anual)      as asignacion_anual,
														  SUM(a.aumento)               as aumento,
														  SUM(a.disminucion)           as disminucion,
														  SUM(a.total_asignacion)      as total_asignacion,
														  SUM(a.compromiso)            as compromiso,
														  SUM(a.causado)               as causado,
														  SUM(a.pagado)                as pagado,
														  SUM(a.deuda)                 as deuda,
														  SUM(a.disponibilidad)        as disponibilidad

						 		      FROM v_credito_agrupado_inst2 a where ".$condicion."

						 		      ORDER BY
					                 		              a.cod_presi,
                                                          a.cod_entidad,
                                                          a.cod_tipo_inst,
                                                          a.cod_inst,
                                                          a.cod_partida,
                                                          a.cod_generica,
                                                          a.cod_especifica,
                                                          a.cod_sub_espec ASC;   ");



            $this->set('tipo_presupuesto', $tipo_presupuesto_aux);
			$this->set('tipo_top', $tipo);
			$this->set('year',     $this->data["datos"]["ano_consolidado"]);

			$this->set('datos', $datos);


			$this->set('DENO_ESTADO', $deno_entidad);
			$this->set('DENO_COD_TIPO_INST', $deno_tipo_inst);
			$this->set('DENO_INST', $deno_inst);
			$this->set('DENO_REPUBLICA', $deno_presi);




}//fin function





$this->set('var', $var);


}//fin function


















function cfpd05_situacion_credito_sub_partida_2($var=null){



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

	      $this->layout="pdf";

          $sql = "";
	      $group_by = "";
	      $tipo=0;
	      $deno_presi   = "";
	      $deno_entidad = "";
	      $deno_inst    = "";
	      $deno_tipo_inst = "";

	                                 if(!empty($this->data["datos"]["cod_presi"])){
									  	if($this->data["datos"]["cod_presi"]!="TODO"){
						                          $sql        .= " a.cod_presi = '".$this->data["datos"]["cod_presi"]."' ";
						                          $group_by   .= " a.cod_presi, ";
						                          $lista_1    =  $this->arrd01->findAll("cod_presi=".$this->data["datos"]["cod_presi"]);
						                          $deno_presi = $lista_1[0]["arrd01"]["denominacion"];
									  	}else{
									  		     $sql .= " 1=1 ";

									  	}
									  }

                                      if(!empty($this->data["datos"]["cod_entidad"])){
									  	if($this->data["datos"]["cod_entidad"]!="TODO"){
						                          $sql        .=" and a.cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";
						                          $group_by   .= "a.cod_entidad, ";
						                          $tipo = 1;

						                          $lista_2      =  $this->arrd02->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]);
						                          $deno_entidad = $lista_2[0]["arrd02"]["denominacion"];
									  	}
									  }

                                       if(!empty($this->data["datos"]["cod_tipo_inst"])){
									  	if($this->data["datos"]["cod_tipo_inst"]!="TODO"){
						                          $sql        .=" and a.cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";
						                          $group_by   .= "a.cod_tipo_inst, ";
						                          $tipo = 2;

						                          $lista_3        =  $this->arrd03->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_tipo_inst=".$this->data["datos"]["cod_tipo_inst"]);
						                          $deno_tipo_inst = $lista_3[0]["arrd03"]["denominacion"];
									  	 }
                                       }

                                      if(!empty($this->data["datos"]["cod_inst"])){
									  	if($this->data["datos"]["cod_inst"]!="TODO"){
						                          $sql        .=" and a.cod_inst = '".$this->data["datos"]["cod_inst"]."' ";
						                          $group_by   .= "a.cod_inst, ";
						                          $tipo = 3;

						                          $lista_4   =  $this->arrd04->findAll("cod_presi=".$this->data["datos"]["cod_presi"]." and cod_entidad=".$this->data["datos"]["cod_entidad"]." and cod_inst=".$this->data["datos"]["cod_inst"]);
						                          $deno_inst = $lista_4[0]["arrd04"]["denominacion"];
									  	}
									  }
									  if(!empty($this->data["datos"]["ano_consolidado"])){
									  	if($this->data["datos"]["ano_consolidado"]!="TODO"){
						                          $sql         .=" and a.ano = '".$this->data["datos"]["ano_consolidado"]."' ";
						                          $group_by    .= "a.ano, ";
									  	}
									  }
									  if(!empty($this->data["datos"]["tipo_presupuesto"])){
									  	             $tipo_presupuesto_aux = $this->data["datos"]["tipo_presupuesto"];
							  	             if($this->data["datos"]["tipo_presupuesto"]==6){
											   	 $sql_aux = "";
											   }else{
											   	 $sql_aux   = " and a.tipo_presupuesto = ".$this->data["datos"]["tipo_presupuesto"];
											   }
									   }


                 $condicion        = $sql." and a.modelo_reporte=13 ".$sql_aux."  GROUP BY ".$group_by." a.cod_partida, a.cod_generica, a.cod_especifica, a.cod_sub_espec, a.denominacion ";


                 $datos = $this->cfpd05->execute("SELECT
                                                          ".$group_by."
					                 		              a.cod_partida,
					                 		              a.cod_generica,
			                                              a.cod_especifica,
			                                              a.cod_sub_espec,
					                 		              a.denominacion,
					                 		              SUM(a.asignacion_anual)      as asignacion_anual,
														  SUM(a.aumento)               as aumento,
														  SUM(a.disminucion)           as disminucion,
														  SUM(a.total_asignacion)      as total_asignacion,
														  SUM(a.compromiso)            as compromiso,
														  SUM(a.causado)               as causado,
														  SUM(a.pagado)                as pagado,
														  SUM(a.deuda)                 as deuda,
														  SUM(a.disponibilidad)        as disponibilidad

						 		      FROM v_credito_agrupado_inst2 a where ".$condicion."

						 		      ORDER BY  a.cod_partida,
						 		      		    a.cod_generica,
                                                a.cod_especifica,
                                                a.cod_sub_espec ASC;   ");



            $this->set('tipo_presupuesto', $tipo_presupuesto_aux);
			$this->set('tipo_top', $tipo);
			$this->set('year',     $this->data["datos"]["ano_consolidado"]);

			$this->set('datos', $datos);


			$this->set('DENO_ESTADO', $deno_entidad);
			$this->set('DENO_COD_TIPO_INST', $deno_tipo_inst);
			$this->set('DENO_INST', $deno_inst);
			$this->set('DENO_REPUBLICA', $deno_presi);




}//fin function





$this->set('var', $var);


}//fin function




function reporte_consumo_productos($var1=null){
set_time_limit(0);

  if($var1==1){ $this->layout = "ajax";

      	$datos  = $this->arrd01->execute(" SELECT DISTINCT ano_estimacion  FROM cfpd07_obras_cuerpo ");
      	$datos2 = $this->arrd01->execute(" SELECT DISTINCT ano_arranque    FROM ccfd03_instalacion  ORDER BY ano_arranque DESC");

		if(count($datos)!=0){
			foreach($datos as $n){
				$cod[]  = $n[0]['ano_estimacion'];
				$deno[] = $n[0]['ano_estimacion'];
			}
			$lista=array_combine($cod, $deno);
		}else{
			$lista=array('0'=>'No se encuentra presente en ninguna nomina');
		}

		$_SESSION["year_productoespecifico"] = $datos2[0][0]["ano_arranque"];
		$this->set("ano_estimacion", $lista);
		$this->set("ano_ejecucion" , $datos2[0][0]["ano_arranque"]);

		$lista =  $this->arrd01->generateList(null, 'denominacion ASC', null, '{n}.arrd01.cod_presi', '{n}.arrd01.denominacion');
        $this->set('vector_presi', $lista);
        $this->set("cod_presi_seleccion", 1);


	}else if($var1==2){
		$this->layout = "pdf";

		$consolidado     = $this->data["datos"]["radio_nivel_consulta"];
		$ano_consolidado = $this->data["datos"]["ano_consolidado"];

		$sql = "";
		$sql2= "";

		//pr($this->data);

		if(!empty($this->data["datos"]["cod_presi"])){
		 	if($this->data["datos"]["cod_presi"]!="TODO"){
				$sql .=" a.cod_presi = '".$this->data["datos"]["cod_presi"]."' ";
				$sql2 =" a.cod_presi = '".$this->data["datos"]["cod_presi"]."' ";
				$vista_sql = "cscd05_consumo_productos_presi a";
				$entidades="a.cod_presi ";
				$render = "reporte_consumo_productos_presi";
		 	}else{
				$sql .= " 1=1 ";
		 	}
		}

          if(!empty($this->data["datos"]["cod_entidad"])){
		  	if($this->data["datos"]["cod_entidad"]!="TODO"){
				$sql .=" and a.cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";
				$sql2  =" a.cod_presi = '".$this->data["datos"]["cod_presi"]."' and a.cod_entidad = '".$this->data["datos"]["cod_entidad"]."' ";
				$vista_sql = "cscd05_consumo_productos_entidad a";
				$entidades="a.cod_presi, a.cod_entidad ";
				$render = "reporte_consumo_productos_entidad";
		  	}else{
		  		$sql2  =" a.cod_presi = '".$this->data["datos"]["cod_presi"]."'";
				$vista_sql = "cscd05_consumo_productos_entidad a";
				$entidades="a.cod_presi, a.cod_entidad ";
				$render = "reporte_consumo_productos_entidad";
		  	}
		  }

           if(!empty($this->data["datos"]["cod_tipo_inst"])){
		  	if($this->data["datos"]["cod_tipo_inst"]!="TODO"){
				$sql .=" and a.cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";
				$sql2 =" a.cod_presi = '".$this->data["datos"]["cod_presi"]."'  and a.cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' ";
				$vista_sql = "cscd05_consumo_productos_tipo_inst a";
				$entidades="a.cod_presi, a.cod_tipo_inst ";
				$render = "reporte_consumo_productos_tipo_inst";
		  	 }else{
		  	 	$sql2 =" a.cod_presi = '".$this->data["datos"]["cod_presi"]."'";
		  	 	$vista_sql = "cscd05_consumo_productos_tipo_inst a";
				$entidades="a.cod_presi, a.cod_tipo_inst ";
				$render = "reporte_consumo_productos_tipo_inst";
		  	 }
           }

          if(!empty($this->data["datos"]["cod_inst"])){
		  	if($this->data["datos"]["cod_inst"]!="TODO"){
				$sql .=" and a.cod_inst = '".$this->data["datos"]["cod_inst"]."' ";
				$sql2 =" a.cod_presi = '".$this->data["datos"]["cod_presi"]."' and a.cod_entidad = '".$this->data["datos"]["cod_entidad"]."' and a.cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."' and a.cod_inst = '".$this->data["datos"]["cod_inst"]."' ";
				$vista_sql = "cscd05_consumo_productos_inst_2 a";
				$entidades="a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst ";
				$render = "reporte_consumo_productos_inst";
		  	}else{
		  		$sql2 =" a.cod_presi = '".$this->data["datos"]["cod_presi"]."' and a.cod_entidad = '".$this->data["datos"]["cod_entidad"]."' and a.cod_tipo_inst = '".$this->data["datos"]["cod_tipo_inst"]."'";
		  		$vista_sql = "cscd05_consumo_productos_inst_2 a";
				$entidades="a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst ";
				$render = "reporte_consumo_productos_inst";
		  	}
		  }


		  if(!empty($this->data["cscp04_ordencompra"]["year"])){
			if($this->data["cscp04_ordencompra"]["year"]!="TODO"){
        		$sql  .="  and a.ano_nota_entrega = '".$this->data["cscp04_ordencompra"]["year"]."' ";
        		$sql2 .="  and a.ano_nota_entrega = '".$this->data["cscp04_ordencompra"]["year"]."' ";
        		$ano_nota_entrega=$this->data["cscp04_ordencompra"]["year"];
        		if($ano_nota_entrega==''){
        			$ano_nota_entrega=$this->ano_ejecucion();
        			$sql2 .="  and a.ano_nota_entrega = '".$ano_nota_entrega."' ";
        		}
			}
		  }

	     $ordenar = " ORDER BY
				       ano_estimacion,
				       cod_presi,
					   cod_entidad,
					   cod_tipo_inst,
					   cod_inst,
				       codigo_prod_serv,
					   cod_obra ";

		//$datos = $this->arrd01->execute(" SELECT * FROM v_cfpd07_cuerpo_vs_cobd01_cuerpo WHERE ".$sql." ".$ordenar);
	    //$this->set("datos", $datos);


		//$cod_presi = $this->Session->read('SScodpresi');
		//$cod_entidad = $this->Session->read('SScodentidad');
		//$cod_tipo_inst = $this->Session->read('SScodtipoinst');
		//$cod_inst = $this->Session->read('SScodinst');


			if(isset($this->data['reporte']['tipo_snc_grupo_tipo'])){
            		if($this->data['reporte']['tipo_snc_grupo_tipo']==1){
                    	$sql_tipo  = " and a.cod_grupo_3='".$this->data['reporte']['seleccion_snc_grupo_tipo']."'  ";
                    	$sql_tipo2 = " and   cod_grupo_3='".$this->data['reporte']['seleccion_snc_grupo_tipo']."'  ";
						$productoespecifico = 1;

					}else if($this->data['reporte']['tipo_snc_grupo_tipo']==2){
						$sql_tipo  = " and a.cod_grupo_5='".$this->data['reporte']['seleccion_snc_grupo_tipo']."'  ";
						$sql_tipo2 = " and   cod_grupo_5='".$this->data['reporte']['seleccion_snc_grupo_tipo']."'  ";
						$productoespecifico = 1;

					}else if($this->data['reporte']['tipo_snc_grupo_tipo']==4){
						$productoespecifico = 2;
						$sql_tipo = "";

					}else if($this->data['reporte']['tipo_snc_grupo_tipo']==5){
						$productoespecifico = 1;
						$sql_tipo = "";

					}//fin else

			}else{
				$sql_tipo = ""; $productoespecifico = 1;
			}//fin else



			if($productoespecifico==1){
				/*
				$sql_consulta2 = "SELECT a.cod_dep, a.ano_nota_entrega, a.codigo_prod_serv, a.cod_snc, a.cantidad_promedio, a.precio_promedio, a.total_consumo, a.expresion,
								(SELECT b.denominacion FROM cscd01_catalogo b WHERE b.codigo_prod_serv=a.codigo_prod_serv) AS denominacion_producto
								 FROM cscd05_consumo_productos_dep a WHERE a.ano_nota_entrega='$ano_nota' and a.cod_presi='$cod_presi' and a.cod_entidad='$cod_entidad' and a.cod_tipo_inst='$cod_tipo_inst' and a.cod_inst='$cod_inst' ".$sql_tipo." ORDER BY cod_snc, codigo_prod_serv, a.precio_promedio, a.cod_dep ASC;";
				*/

				$sql_consulta2 = "SELECT ".$entidades.", a.denominacion_entidad, a.ano_nota_entrega, a.codigo_prod_serv, a.cod_snc, a.cantidad_promedio, a.precio_promedio, a.total_consumo, a.expresion,
								(SELECT b.denominacion FROM cscd01_catalogo b WHERE b.codigo_prod_serv=a.codigo_prod_serv) AS denominacion_producto
								 FROM ".$vista_sql." WHERE ".$sql2." ".$sql_tipo." ORDER BY cod_snc, codigo_prod_serv, a.precio_promedio, ".$entidades." ASC;";


 				$consulta = $this->cscd05_consumo_productos_dep->execute($sql_consulta2);
 				$find_dep = $this->cugd02_dependencia->findAll(null,array('cod_dependencia','denominacion'));
				foreach($find_dep as $d){
				$cod_depe[] =  $d['cugd02_dependencia']['cod_dependencia'];
				$deno_dep[] = $d['cugd02_dependencia']['denominacion'];
				}
				$dependencias = array_combine($cod_depe, $deno_dep);

				$this->set('cant_registros', count($consulta));
				$this->set('datos',$consulta);
				$this->set('denominacion','vacio');
				$this->set('cod_snc','vacio');
				$this->set('codigo_prod_serv','vacio');
				$this->set('expresion','vacio');
				$this->set('dependencias',$dependencias);
				$this->set('opcion',2);

			}else{
				$cod_prod_serv = $this->data['cscp04_ordencompra']['codigo_prod_serv'];
				//$sql_consulta = "SELECT DISTINCT cod_dep, cantidad, precio_unitario FROM cscd03_cotizacion_cuerpo WHERE codigo_prod_serv='$cod_prod_serv' AND cantidad_entregada !=0 ";
				$sql_consulta2 = "SELECT DISTINCT a.cod_snc, a.codigo_prod_serv, a.ano_nota_entrega, ".$entidades.", a.denominacion_entidad, a.cantidad_promedio, a.precio_promedio, a.total_consumo FROM ".$vista_sql." WHERE ".$sql2." and a.codigo_prod_serv='$cod_prod_serv'"." ".$sql_tipo." ORDER BY a.cod_snc, a.codigo_prod_serv, a.precio_promedio, ".$entidades." ASC;";
				$consulta = $this->cscd05_consumo_productos_dep->execute($sql_consulta2);
				$buscar_deno = $this->cscd01_catalogo->findAll("codigo_prod_serv=".$cod_prod_serv,array('denominacion','cod_snc','codigo_prod_serv','cod_medida'));
				$expresion_medida = $this->cscd01_unidad_medida->findAll('cod_medida='.$buscar_deno[0]['cscd01_catalogo']['cod_medida'], array('expresion'));

				$find_dep = $this->cugd02_dependencia->findAll(null,array('cod_dependencia','denominacion'));
				foreach($find_dep as $d){
				$cod_depe[] =  $d['cugd02_dependencia']['cod_dependencia'];
				$deno_dep[] = $d['cugd02_dependencia']['denominacion'];
				}
				$dependencias = array_combine($cod_depe, $deno_dep);

				$this->set('cant_registros', count($consulta));
				$this->set('datos',$consulta);
				$this->set('denominacion',$buscar_deno[0]['cscd01_catalogo']['denominacion']);
				$this->set('cod_snc',$buscar_deno[0]['cscd01_catalogo']['cod_snc']);
				$this->set('codigo_prod_serv',$buscar_deno[0]['cscd01_catalogo']['codigo_prod_serv']);
				$this->set('expresion',$expresion_medida[0]['cscd01_unidad_medida']['expresion']);
				$this->set('dependencias',$dependencias);
				$this->set('opcion',1);
			}
			//pr($consulta);
			$this->render($render);
	}//fin else


$this->set("vista", $var1);
}//fin function


function reporte_consumo_productos_inst(){
	$this->layout = "pdf";
}

function reporte_consumo_productos_tipo_inst(){
	$this->layout = "pdf";
}

function reporte_consumo_productos_entidad(){
	$this->layout = "pdf";
}

function reporte_consumo_productos_presi(){
	$this->layout = "pdf";
}








}// fin class

?>