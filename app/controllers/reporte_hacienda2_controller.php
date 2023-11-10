<?php

class ReporteHacienda2Controller extends AppController{


    var $name    = "reporte_hacienda2";
    var $uses    = array('v_relacion_coradores', 'shd002_cobradores', 'shd900_planillas_deuda_cobro_detalles', 'v_shd900_planillas_deuda_cobro_detalles_cobradores',
                         'v_shd900_planillas_deuda_cobro_detalles_cobradores_2', 'shd100_articulos', 'shd000_arranque', 'v_pantente_patente_constribuyente_cugd01_municipio',
                         'v_pantente_actividad_denominacion', 'cscd04_ordencompra_parametros', 'cfpd03', 'v_cfpd03_denominacion_partida',
                         'shd900_cobranza_acumulada', 'v_shd900_cobranza_acumulada_deno_part', 'v_shd001_registro_contribuyentes',
                         'shd001_registro_contribuyentes', 'cugd01_republica','cugd01_estados','cugd01_municipios','cugd01_parroquias','cugd01_centropoblados','cugd01_vialidad',
                         'cugd01_vereda', 'cnmd06_profesiones', 'v_decla_ingre_bruto_con_contrib', 'v_shd500_aseo_domiciliario_deuda_cobro_detalles',
                         'v_shd700_credito_vivienda_deuda_cobro_detalles', 'v_shd300_propaganda_deuda_cobro_detalles', 'v_relacion_zonageografica',
                         'cugd01_republica', 'cugd01_estados', 'cugd01_municipios', 'cugd01_parroquias', 'cugd01_centropoblados', 'shd100_declaracion_ingresos');


    var $helpers = array('Html', 'Javascript', 'Ajax', 'Fck', 'Sisap');


function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
}//fin checksession



function beforeFilter(){$this->checkSession();}






function ventana_cobradores_1($var1=null){

 $this->layout="ajax";


$url                  =  "/reporte_hacienda2/ventana_cobradores_2/1";
$width_aux            =  "750px";
$height_aux           =  "400px";
$title_aux            =  "Buscar";
$resizable_aux        =  false;
$maximizable_aux      =  false;
$minimizable_aux      =  false;
$closable_aux         =  false;

    if($var1==2){
	         echo"<script>";
	           echo  "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
	         echo"</script>";
    	}else{
              echo"<script>";
               echo  " Windows.close(document.getElementById('capa_ventana').value)";
              echo"</script>";

	}//fin else


}//fin function








function ventana_cobradores_2($var1=null, $pagina=null, $pista=null){

$this->layout="ajax";
$cod_presi                =       $this->Session->read('SScodpresi');
$cod_entidad              =       $this->Session->read('SScodentidad');
$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
$cod_inst                 =       $this->Session->read('SScodinst');
$cod_dep                  =       $this->Session->read('SScoddep');




       if($var1==1){

        $this->set("datos",'');


 }else if($var1==2){


    	            if(isset($pagina)){ $pagina=$pagina; }else{ $pagina=1; }

					if($pista!=null){
						  $this->Session->write('pista_buscar_cobrador_hacienda', $pista);
					}else{
					      $pista = $this->Session->read('pista_buscar_cobrador_hacienda');
					}//fin else


					$condicion = $this->condicion()." and (".$this->busca_separado(array("rif_ci","nombre_razon"), $pista).")  ";


									            $Tfilas=$this->shd002_cobradores->findCount($condicion);
											        if($Tfilas!=0){
											        	$Tfilas=(int)ceil($Tfilas/50);
											        	$this->set('total_paginas',$Tfilas);
														$this->set('pagina_actual',$pagina);
														$this->set('pag_cant',$pagina.'/'.$Tfilas);
														$this->set('ultimo',$Tfilas);
											     	    $datos_filas=$this->shd002_cobradores->findAll($condicion,null,"rif_ci, nombre_razon ASC",50,$pagina,null);
												        $this->set("datos",$datos_filas);
												        $this->set('siguiente',$pagina+1);
														$this->set('anterior',$pagina-1);
														$this->bt_nav($Tfilas,$pagina);
											        }else{
											        	$this->set("datos",'');
											        }

					$this->set("pista",$pista);

 }else  if($var1==3){

         $this->set("valor_seleccionado",$pagina);


 }//fin else

$this->set("opcion",$var1);

}//fin function





function responsabilidad_de_cobradores($var1=null){


       if($var1==1){ $this->layout="ajax";


}else if($var1==2){  $this->layout="pdf";


         $tpo_busqueda = $this->data['reporte_hacienda2']['tipo_busqueda'];


                 if($tpo_busqueda==1){
              $sql          = "";
           }else if($tpo_busqueda==2){
              $sql          = " and rif_ci='".$this->data['reporte_hacienda2']['rif_ci']."' ";
           }

        $sql.="  ORDER BY cod_presi,
						  cod_entidad,
						  cod_tipo_inst,
						  cod_inst ,
						  cod_dep,
						  rif_ci, tipo_ingreso ASC";

		$sql=("select * from v_relacion_coradores where ".$this->condicion()." ".$sql);
		$ejecuta=$this->v_relacion_coradores->execute($sql);

        if($ejecuta!=null){
			$this->set('datos',$ejecuta);
		}else{
			$this->set('datos',null);
		}


}//fin function



$this->set("opcion", $var1);


}//fin function























function recibos_por_cobrar($var1=null, $var2=null){



       if($var1==1){ $this->layout="ajax";


					$tipo_impuesto=array(1=>'PATENTE DE INDUSTRIA Y COMERCIO',2=>'VEHÍCULOS',3=>'PROPAGANDA COMERCIAL',4=>'INMUEBLES URBANOS',5=>'ASEO DOMICILIARIO',6=>'ARRENDAMIENTO DE TIERRAS',7=>'CRÉDITOS DE VIVIENDA');
                    $this->set('tipo_impuesto',  $tipo_impuesto);

}else if($var1==2){  $this->layout="ajax";

	                $datos  = $this->shd900_planillas_deuda_cobro_detalles->execute(" SELECT DISTINCT mes FROM shd900_planillas_deuda_cobro_detalles WHERE ".$this->condicion()." and ano='".$var2."' ORDER BY mes ASC");
					$mes= array(1=>'ENERO',2=>'FEBRERO',3=>'MARZO',4=>'ABRIL',5=>'MAYO',6=>'JUNIO',7=>'JULIO',8=>'AGOSTO',9=>'SEPTIEMBRE',10=>'OCTUBRE',11=>'NOVIEMBRE',12=>'DICIEMBRE');
						if(count($datos)!=0){
							foreach($datos as $n){
								$lista[$n[0]['mes']]=$mes[$n[0]['mes']];
						    }
						}else{
							$lista=array('0'=>'No existen datos');
						}

					$this->set("lista_mes", $lista);


}else if($var1==3){  $this->layout="ajax";

	                $tipo_impuesto=array(1=>'PATENTE DE INDUSTRIA Y COMERCIO',2=>'VEHÍCULOS',3=>'PROPAGANDA COMERCIAL',4=>'INMUEBLES URBANOS',5=>'ASEO DOMICILIARIO',6=>'ARRENDAMIENTO DE TIERRAS',7=>'CRÉDITOS DE VIVIENDA');
                    $this->set('tipo_impuesto',  $tipo_impuesto);
                    $this->set('opcion_impuesto',$var2);

}else if($var1==4){  $this->layout="ajax";

	                $datos  = $this->shd900_planillas_deuda_cobro_detalles->execute(" SELECT DISTINCT ano FROM shd900_planillas_deuda_cobro_detalles WHERE ".$this->condicion()." ORDER BY ano ASC");
						if(count($datos)!=0){
							foreach($datos as $n){
								$lista[$n[0]['ano']]=$n[0]['ano'];
						    }
						}else{
							$lista=array('0'=>'No existen datos');
						}
					$this->set("lista_ano", $lista);
					$this->set('opcion_year',$var2);


}else if($var1==5){  $this->layout="pdf";


         $tipo_busqueda = $this->data['reporte_hacienda2']['tipo_busqueda'];
         $tipo_year = $this->data['reporte_hacienda2']['tipo_year'];


                 if($tipo_busqueda==1){
              $sql          = "";
           }else if($tipo_busqueda==2){
           	if(!empty($this->data['reporte_hacienda2']['rif_ci'])){
              $sql          = " and rif_cobrador='".$this->data['reporte_hacienda2']['rif_ci']."' ";
           	}
           }
                 if($tipo_year==1){
              $sql          = "";
           }else if($tipo_year==2){
           	if(!empty($this->data['reporte_hacienda2']['ano'])){
              $sql          .= " and ano='".$this->data['reporte_hacienda2']['ano']."' ";
           	}
           }
            if(!empty($this->data['reporte_hacienda2']['impuesto'])){
              $sql          .= " and cod_ingreso='".$this->data['reporte_hacienda2']['impuesto']."' ";
           	}
           	if(!empty($this->data['reporte_hacienda2']['mes'])){
              $sql          .= " and mes='".$this->data['reporte_hacienda2']['mes']."' ";
           	}

        $sql.="  ORDER BY cod_presi,
						  cod_entidad,
						  cod_tipo_inst,
						  cod_inst ,
						  cod_dep,
						  rif_cobrador, cod_ingreso, ano, mes, numero_planilla ASC";

		$sql=("select * from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 where ".$this->condicion()." and cancelado=2 ".$sql);
		$ejecuta=$this->v_shd900_planillas_deuda_cobro_detalles_cobradores_2->execute($sql);

        if($ejecuta!=null){
			$this->set('datos',$ejecuta);
		}else{
			$this->set('datos',null);
		}


}//fin function



$this->set("opcion", $var1);


}//fin function









function recibos_cobrados($var1=null, $var2=null){



       if($var1==1){ $this->layout="ajax";


					$tipo_impuesto=array(1=>'PATENTE DE INDUSTRIA Y COMERCIO',2=>'VEHÍCULOS',3=>'PROPAGANDA COMERCIAL',4=>'INMUEBLES URBANOS',5=>'ASEO DOMICILIARIO',6=>'ARRENDAMIENTO DE TIERRAS',7=>'CRÉDITOS DE VIVIENDA');
                    $this->set('tipo_impuesto',  $tipo_impuesto);

}else if($var1==2){  $this->layout="ajax";

	                $datos  = $this->shd900_planillas_deuda_cobro_detalles->execute(" SELECT DISTINCT mes FROM shd900_planillas_deuda_cobro_detalles WHERE ".$this->condicion()." and ano='".$var2."' ORDER BY mes ASC");
					$mes= array(1=>'ENERO',2=>'FEBRERO',3=>'MARZO',4=>'ABRIL',5=>'MAYO',6=>'JUNIO',7=>'JULIO',8=>'AGOSTO',9=>'SEPTIEMBRE',10=>'OCTUBRE',11=>'NOVIEMBRE',12=>'DICIEMBRE');
						if(count($datos)!=0){
							foreach($datos as $n){
								$lista[$n[0]['mes']]=$mes[$n[0]['mes']];
						    }
						}else{
							$lista=array('0'=>'No existen datos');
						}


					$this->set("lista_mes", $lista);


}else if($var1==3){  $this->layout="ajax";

	                $tipo_impuesto=array(1=>'PATENTE DE INDUSTRIA Y COMERCIO',2=>'VEHÍCULOS',3=>'PROPAGANDA COMERCIAL',4=>'INMUEBLES URBANOS',5=>'ASEO DOMICILIARIO',6=>'ARRENDAMIENTO DE TIERRAS',7=>'CRÉDITOS DE VIVIENDA');
                    $this->set('tipo_impuesto',  $tipo_impuesto);
                    $this->set('opcion_impuesto',$var2);

}else if($var1==4){  $this->layout="ajax";
                    $datos  = $this->shd900_planillas_deuda_cobro_detalles->execute(" SELECT DISTINCT ano FROM shd900_planillas_deuda_cobro_detalles WHERE ".$this->condicion()." ORDER BY ano ASC");
					if(count($datos)!=0){
						foreach($datos as $n){
							$lista[$n[0]['ano']]=$n[0]['ano'];
					    }
					}else{
						$lista=array('0'=>'No existen datos');
					}
					$this->set("lista_ano", $lista);
					$this->set('opcion_year',$var2);


}else if($var1==5){  $this->layout="pdf";


         $tipo_busqueda = $this->data['reporte_hacienda2']['tipo_busqueda'];
         $tipo_year = $this->data['reporte_hacienda2']['tipo_year'];


                 if($tipo_busqueda==1){
              $sql          = "";
           }else if($tipo_busqueda==2){
           	if(!empty($this->data['reporte_hacienda2']['rif_ci'])){
              $sql          = " and rif_cobrador='".$this->data['reporte_hacienda2']['rif_ci']."' ";
           	}
           }
                 if($tipo_year==1){
              $sql          = "";
           }else if($tipo_year==2){
           	if(!empty($this->data['reporte_hacienda2']['ano'])){
              $sql          .= " and ano='".$this->data['reporte_hacienda2']['ano']."' ";
           	}
           }
            if(!empty($this->data['reporte_hacienda2']['impuesto'])){
              $sql          .= " and cod_ingreso='".$this->data['reporte_hacienda2']['impuesto']."' ";
           	}
           	if(!empty($this->data['reporte_hacienda2']['mes'])){
              $sql          .= " and mes='".$this->data['reporte_hacienda2']['mes']."' ";
           	}

        $sql.="  ORDER BY cod_presi,
						  cod_entidad,
						  cod_tipo_inst,
						  cod_inst ,
						  cod_dep,
						  rif_cobrador, cod_ingreso, ano, mes, numero_planilla ASC";

		$sql=("select * from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 where ".$this->condicion()." and cancelado=1 ".$sql);
		$ejecuta=$this->v_shd900_planillas_deuda_cobro_detalles_cobradores_2->execute($sql);

        if($ejecuta!=null){
			$this->set('datos',$ejecuta);
		}else{
			$this->set('datos',null);
		}


}//fin function



$this->set("opcion", $var1);


}//fin function











function select_busqueda($var1=null){

$this->layout="ajax";

$lista_republicas =  $this->cugd01_republica->generateList(null, 'denominacion ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
$this->set('lista_1',         $lista_republicas);
$this->set("lista_1_selectd", $this->Session->read('SScodpresi'));


$estado_var = $this->cugd01_estados->generateList("cod_republica='".$this->Session->read('SScodpresi')."'", 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
$this->set('lista_2',         $estado_var);



$this->set("opcion", $var1);


}//fin funtion








function select_busqueda_1($var1=null, $var2=null, $var3=null, $var4=null){

$this->layout="ajax";

                 if($var4!=null){$this->set("opcion", 4);

                 	$sql="cod_republica=".$var1." and cod_estado=".$var2." and cod_municipio=".$var3." and cod_parroquia=".$var4."";

                 	$consulta = $this->cugd01_centropoblados->generateList($sql, 'denominacion ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
	   		        $this->set('listado', $consulta);

           }else if($var3!=null){$this->set("opcion", 3);

           	        $sql="cod_republica=".$var1." and cod_estado=".$var2." and cod_municipio=".$var3." ";
           	        $consulta = $this->cugd01_parroquias->generateList($sql, 'denominacion ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
	   		        $this->set('listado', $consulta);

           }else if($var2!=null){$this->set("opcion", 2);

           	        $sql="cod_republica=".$var1." and cod_estado=".$var2." ";
           	        $consulta = $this->cugd01_municipios->generateList($sql, 'denominacion ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
	   		        $this->set('listado', $consulta);

           }else if($var1!=null){$this->set("opcion", 1);

                    $sql="cod_republica=".$var1." ";
                    $consulta = $this->cugd01_estados->generateList($sql, 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
	   		        $this->set('listado', $consulta);

           }//fin else

$this->set("opcion1", $var1);
$this->set("opcion2", $var2);
$this->set("opcion3", $var3);
$this->set("opcion4", $var4);


}//fin funtion










function boletin_de_notificacion_patente($var1=null){

            $cp  = $this->Session->read('SScodpresi');
			$ce  = $this->Session->read('SScodentidad');
			$cti = $this->Session->read('SScodtipoinst');
			$ci  = $this->Session->read('SScodinst');
			$cd  = $this->Session->read('SScoddep');

	  if($var1==1){ $this->layout="ajax";

		     $datos     =$this->shd100_articulos->findAll($this->condicion());
		     if(isset($datos[0]["shd100_articulos"]["articulos_patente"])){
		     	$articulos = $datos[0]["shd100_articulos"]["articulos_patente"];
		     }else{
		     	$articulos = "";
		     }
		     $this->set("articulos", $articulos);
             $this->set("ano_arranque",$this->shd000_arranque->ano($this->condicion()));


}else if($var1==2){  $this->layout="ajax";




}else if($var1==21){  $this->layout="ajax";



			if(!empty($this->params['form']['contenido_FCK'])){
              $contenido_FCK   = $this->params['form']['contenido_FCK'];

                if($this->shd100_articulos->findCount($this->condicion())==0){
				    $sql="INSERT INTO shd100_articulos VALUES ('$cp', '$ce', '$cti', '$ci', '$cd', '".$contenido_FCK."')";
				    $rs =$this->shd100_articulos->execute($sql);
	            }else {
	                $sql="UPDATE shd100_articulos set articulos_patente='".$contenido_FCK."' WHERE ".$this->condicion();
					$rs =$this->shd100_articulos->execute($sql);
	            }
           	}else{
           		$rs = 0;
           	}


			if($rs>1){
				$this->set('Message_existe', 'Los datos fueron registrados');
			}else{
				$this->set('errorMessage', 'Los datos no fueron registrados');
			}

}else if($var1==3){  $this->layout="ajax";

}else if($var1==4){  $this->layout="pdf";


	         $datos     =$this->shd100_articulos->findAll($this->condicion());
		     if(isset($datos[0]["shd100_articulos"]["articulos_patente"])){
		     	$articulos = $datos[0]["shd100_articulos"]["articulos_patente"];
		     }else{
		     	$articulos = "";
		     }
             $this->set("articulos", $articulos);
		     $this->set("year", $this->data['reporte_hacienda2']['ano']);


     $xx="     SELECT
					  a.cod_presi,
					  a.cod_entidad,
					  a.cod_tipo_inst,
					  a.cod_inst,
					  a.cod_dep,
					  a.rif_cedula,
					  a.numero_solicitud,
					  a.numero_patente,
					  e.numero_declaracion,
					  a.frecuencia_pago,
					  a.monto_mensual,
					  a.pago_todo,
					  a.suspendido,
					  a.rif_ci_cobrador,
					  a.ultimo_ano_facturado,
					  a.ultimo_mes_facturado,
					  a.fecha_ultima_decla,
					  a.ingresos_declarados,
					  a.ultimo_ejercicio_decla,
					  a.periodo_desde,
					  a.periodo_hasta,
					  a.fecha_patente,
					  a.numero_expediente,
					  c.personalidad_juridica,
					  c.razon_social_nombres,
					  c.fecha_inscripcion,
					  c.nacionalidad,
					  c.estado_civil,
					  c.profesion,
					  c.cod_pais,
					  c.cod_estado,
					  c.cod_municipio,
					  c.cod_parroquia,
					  c.cod_centro_poblado,
					  c.cod_calle_avenida,
					  c.cod_vereda_edificio,
					  c.numero_vivienda_local,
					  c.telefonos_fijos,
					  c.telefonos_celulares,
					  c.correo_electronico,
					  (SELECT xya.denominacion FROM cugd01_estados          xya where xya.cod_republica=c.cod_pais and xya.cod_estado=c.cod_estado                                                                                                                           											   												 GROUP BY xya.denominacion) as  deno_cod_estado,
					  (SELECT xyb.denominacion FROM cugd01_municipios       xyb where xyb.cod_republica=c.cod_pais and xyb.cod_estado=c.cod_estado  and xyb.cod_municipio=c.cod_municipio                                                                                   										       											     GROUP BY xyb.denominacion) as  deno_cod_municipio,
					  (SELECT xyb.conocido     FROM cugd01_municipios       xyb where xyb.cod_republica=c.cod_pais and xyb.cod_estado=c.cod_estado  and xyb.cod_municipio=c.cod_municipio                                                                                   										       											     GROUP BY xyb.conocido) as  conocido,
					  (SELECT xyc.denominacion FROM cugd01_parroquias       xyc where xyc.cod_republica=c.cod_pais and xyc.cod_estado=c.cod_estado  and xyc.cod_municipio=c.cod_municipio and xyc.cod_parroquia = c.cod_parroquia                                                                                        											     GROUP BY xyc.denominacion) as  deno_cod_parroquia,
					  (SELECT xyd.denominacion FROM cugd01_centros_poblados xyd where xyd.cod_republica=c.cod_pais and xyd.cod_estado=c.cod_estado  and xyd.cod_municipio=c.cod_municipio and xyd.cod_parroquia = c.cod_parroquia and xyd.cod_centro = c.cod_centro_poblado                                               								                 GROUP BY xyd.denominacion) as  deno_cod_centro,
					  (SELECT xyd.denominacion FROM cugd01_vialidad         xyd where xyd.cod_republica=c.cod_pais and xyd.cod_estado=c.cod_estado  and xyd.cod_municipio=c.cod_municipio and xyd.cod_parroquia = c.cod_parroquia and xyd.cod_centro = c.cod_centro_poblado and  xyd.cod_vialidad  = c.cod_calle_avenida   											 GROUP BY xyd.denominacion) as  deno_cod_calle_avd,
					  (SELECT xyd.denominacion FROM cugd01_vereda           xyd where xyd.cod_republica=c.cod_pais and xyd.cod_estado=c.cod_estado  and xyd.cod_municipio=c.cod_municipio and xyd.cod_parroquia = c.cod_parroquia and xyd.cod_centro = c.cod_centro_poblado and  xyd.cod_vialidad  = c.cod_calle_avenida  and  xyd.cod_vereda  = c.cod_vereda_edificio  GROUP BY xyd.denominacion) as  deno_cod_verenda


			FROM shd100_patente a, shd001_registro_contribuyentes c, cugd01_municipios d, shd100_declaracion_ingresos e

			WHERE     a.cod_presi      = '".$cp."'    and
					  a.cod_entidad    = '".$ce."'    and
					  a.cod_tipo_inst  = '".$cti."'   and
					  a.cod_inst       = '".$ci."'    and
					  a.cod_dep        = '".$cp."'    and
					  SUBSTR(a.fecha_ultima_decla::text, 0, 5)='".$this->data['reporte_hacienda2']['ano']."' and

					  e.cod_presi      = '".$cp."'    and
					  e.cod_entidad    = '".$ce."'    and
					  e.cod_tipo_inst  = '".$cti."'   and
					  e.cod_inst       = '".$ci."'    and
					  e.cod_dep        = '".$cd."'    and
					  e.rif_cedula     = a.rif_cedula    and
					  e.fecha_declaracion = a.fecha_ultima_decla and

					  a.suspendido       = 2             and
					  a.pago_todo        = 2             and
			          c.rif_cedula     = a.rif_cedula    and
					  d.cod_republica  = c.cod_pais      and
					  d.cod_estado     = c.cod_estado    and
					  d.cod_municipio  = c.cod_municipio


			ORDER BY      a.cod_presi,
						  a.cod_entidad,
						  a.cod_tipo_inst,
						  a.cod_inst ,
						  a.cod_dep,
						  a.rif_cedula ASC; ";

		      $this->set("datos_1", $this->v_pantente_patente_constribuyente_cugd01_municipio->execute($xx));


	$xx="	      SELECT
						  a.cod_presi,
						  a.cod_entidad,
						  a.cod_tipo_inst,
						  a.cod_inst,
						  a.cod_dep,
						  a.rif_cedula,
						  a.numero_declaracion,
						  a.cod_actividad,
						  a.monto_ingresos,
						  a.monto_impuesto,
						  a.alicuota_aplicada,
                          b.denominacion_actividad,
						  b.alicuota,
						  b.unidades_tributarias,
						  b.minimo_tributable

				FROM shd100_declaracion_actividades a, shd100_actividades b

				WHERE  a.cod_presi      = '".$cp."'    and
					   a.cod_entidad    = '".$ce."'    and
					   a.cod_tipo_inst  = '".$cti."'   and
					   a.cod_inst       = '".$ci."'    and
					   a.cod_dep        = '".$cp."'    and
				       b.cod_actividad  =  a.cod_actividad

		         ORDER BY     a.cod_presi,
							  a.cod_entidad,
							  a.cod_tipo_inst,
							  a.cod_inst ,
							  a.cod_dep,
							  a.rif_cedula, a.numero_declaracion ASC; ";

		     $this->set("datos_2", $this->v_pantente_actividad_denominacion->execute($xx));

		    $datos2=$this->cscd04_ordencompra_parametros->findAll($this->condicion());
			$valor_unidad = $datos2[0]['cscd04_ordencompra_parametros']['unidad_tributaria'];
			$this->set('valor_unidad',$valor_unidad);




}//fin if


$this->set("opcion", $var1);

}//fin function










function consulta_cumplimiento_metas($var1=null, $var2=null, $pagina=null){



	  if($var1==1){ $this->layout="ajax";
                    $datos  = $this->cfpd03->execute(" SELECT DISTINCT ano FROM cfpd03 WHERE ".$this->condicion()." ORDER BY ano ASC");
					if(count($datos)!=0){
						foreach($datos as $n){
							$lista[$n[0]['ano']]=$n[0]['ano'];
					    }
					}else{
						$lista=array('0'=>'No existen datos');
					}
					$this->set("lista_ano", $lista);
					$ano_arranque = $this->shd000_arranque->ano($this->condicion());

		            $this->set("ano_arranque",$ano_arranque);

		                $pagina = 1;
			            $Tfilas=$this->v_cfpd03_denominacion_partida->findCount($this->condicion()." and ano='".$ano_arranque."'  ");
				        if($Tfilas!=0){
//				        	$Tfilas=(int)ceil($Tfilas/50);
//				        	$this->set('total_paginas',$Tfilas);
//							$this->set('pagina_actual',$pagina);
//							$this->set('pag_cant',$pagina.'/'.$Tfilas);
//							$this->set('ultimo',$Tfilas);
				     	    $datos_filas=$this->v_cfpd03_denominacion_partida->findAll($this->condicion()." and ano='".$ano_arranque."' ",null,"cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC",null,$pagina,null);
					        $this->set("datos2",$datos_filas);
//					        $this->set('siguiente',$pagina+1);
//							$this->set('anterior',$pagina-1);
//							$this->bt_nav($Tfilas,$pagina);
				        	$this->set('total_paginas',0);
							$this->set('pagina_actual',0);
							$this->set('pag_cant',0);
							$this->set('ultimo',0);
					        $this->set('siguiente',0);
							$this->set('anterior',0);


				        }else{
				        	$this->set("datos2",'');
				        	$this->set('total_paginas',0);
							$this->set('pagina_actual',0);
							$this->set('pag_cant',0);
							$this->set('ultimo',0);
					        $this->set('siguiente',0);
							$this->set('anterior',0);
				        }



}else if($var1==2){  $this->layout="ajax";

			            if($var2!=null){
			            	$year = $var2;
			            }else{
			                $year = $this->shd000_arranque->ano($this->condicion());
			            }

                        if($pagina==null){$pagina = 1;}
			            $Tfilas=$this->v_cfpd03_denominacion_partida->findCount($this->condicion()." and ano='".$year."' ");
				        if($Tfilas!=0){
				        	$Tfilas=(int)ceil($Tfilas/50);
				        	$this->set('total_paginas',$Tfilas);
							$this->set('pagina_actual',$pagina);
							$this->set('pag_cant',$pagina.'/'.$Tfilas);
							$this->set('ultimo',$Tfilas);
				     	    $datos_filas=$this->v_cfpd03_denominacion_partida->findAll($this->condicion()." and ano='".$year."'  ",null,"cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC",50,$pagina,null);
					        $this->set("datos2",$datos_filas);
					        $this->set('siguiente',$pagina+1);
							$this->set('anterior',$pagina-1);
							$this->bt_nav($Tfilas,$pagina);

							if($pagina==$Tfilas){

                                 $datos_filas2=$this->v_cfpd03_denominacion_partida->findAll($this->condicion()." and ano='".$year."'  ", null,"cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC",null,null,null);
                                 $total_a = 0;
							     $total_b = 0;
							     $total_c = 0;
							     $total_d = 0;

			                           foreach($datos_filas2 as $ve){

												  $estimacion_inicial    = $ve["v_cfpd03_denominacion_partida"]['estimacion_inicial'];
												  $ingresos_adicionales  = $ve["v_cfpd03_denominacion_partida"]['ingresos_adicionales'];
												  $rebajas               = $ve["v_cfpd03_denominacion_partida"]['rebajas'];
												  $monto_facturado       = $ve["v_cfpd03_denominacion_partida"]['monto_facturado'];
												  $monto_cobrado         = $ve["v_cfpd03_denominacion_partida"]['monto_cobrado'];

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

				        $this->set("year",$year);


}else if($var1==3){  $this->layout="ajax";



}//fin else



$this->set("opcion", $var1);

}//fin function











function grafico_facturacion($var1=null, $var2=null){



       if($var1==1){ $this->layout="ajax";

					$tipo_impuesto=array(1=>'PATENTE DE INDUSTRIA Y COMERCIO',2=>'VEHÍCULOS',3=>'PROPAGANDA COMERCIAL',4=>'INMUEBLES URBANOS',5=>'ASEO DOMICILIARIO',6=>'ARRENDAMIENTO DE TIERRAS',7=>'CRÉDITOS DE VIVIENDA');
                    $this->set('tipo_impuesto',  $tipo_impuesto);

                    $datos  = $this->shd900_planillas_deuda_cobro_detalles->execute(" SELECT DISTINCT ano FROM shd900_planillas_deuda_cobro_detalles WHERE ".$this->condicion()." ORDER BY ano ASC");
					if(count($datos)!=0){
						foreach($datos as $n){
							$lista[$n[0]['ano']]=$n[0]['ano'];
					    }
					}else{
						$lista=array('0'=>'No existen datos');
					}
					$this->set("lista_ano", $lista);

					$ano_arranque = $this->shd000_arranque->ano($this->condicion());

		            $this->set("ano_arranque",$ano_arranque);





}else if($var1==2){  $this->layout="ajax";

                    $tipo_impuesto=array(1=>'PATENTE DE INDUSTRIA Y COMERCIO',2=>'VEHÍCULOS',3=>'PROPAGANDA COMERCIAL',4=>'INMUEBLES URBANOS',5=>'ASEO DOMICILIARIO',6=>'ARRENDAMIENTO DE TIERRAS',7=>'CRÉDITOS DE VIVIENDA');
                    $this->set('tipo_impuesto',  $tipo_impuesto);
                    $this->set('opcion_impuesto',$var2);

}else if($var1==3){  $this->layout="ajax";
              $sql          = "";
              $sql2         = "";

                  if($this->data['reporte_hacienda2']['tipo_impuesto']==1){
              $sql          = "";
              $sql2         = "";
              $this->set('tipo_impuesto', 0);
            }else if($this->data['reporte_hacienda2']['tipo_impuesto']==2){
           	 if(!empty($this->data['reporte_hacienda2']['impuesto'])){
              $sql          .= " and a.cod_ingreso='".$this->data['reporte_hacienda2']['impuesto']."' ";
              $sql2         .= " and b.cod_ingreso='".$this->data['reporte_hacienda2']['impuesto']."' ";
              $this->set('tipo_impuesto', $this->data['reporte_hacienda2']['impuesto']);
           	 }else{
           	  $this->set('tipo_impuesto', 0);
           	 }
            }
            if(!empty($this->data['reporte_hacienda2']['ano'])){
              $sql          .= " and a.ano='".$this->data['reporte_hacienda2']['ano']."' ";
              $sql2         .= " and b.ano='".$this->data['reporte_hacienda2']['ano']."' ";
           	}
            if(!empty($this->data['reporte_hacienda2']['impuesto'])){
              $sql          .= " and a.cod_ingreso='".$this->data['reporte_hacienda2']['impuesto']."' ";
              $sql2         .= " and b.cod_ingreso='".$this->data['reporte_hacienda2']['impuesto']."' ";
           	}
        $condicion  = 'a.cod_presi='.$this->Session->read('SScodpresi').'  and  a.cod_entidad='.$this->Session->read('SScodentidad').' and a.cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  a.cod_inst='.$this->Session->read('SScodinst').' and a.cod_dep='.$this->Session->read('SScoddep').' ';
        $condicion2 = 'b.cod_presi='.$this->Session->read('SScodpresi').'  and  b.cod_entidad='.$this->Session->read('SScodentidad').' and b.cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  b.cod_inst='.$this->Session->read('SScodinst').' and b.cod_dep='.$this->Session->read('SScoddep').' ';

        $group_by  ="  GROUP BY a.cod_presi,
							   a.cod_entidad,
							   a.cod_tipo_inst,
							   a.cod_inst ,
							   a.cod_dep";
		$group_by2 =" GROUP BY b.cod_presi,
							   b.cod_entidad,
							   b.cod_tipo_inst,
							   b.cod_inst ,
							   b.cod_dep";

        $sql1 ="SELECT
        		 a.cod_presi,
				 a.cod_entidad,
				 a.cod_tipo_inst,
				 a.cod_inst ,
				 a.cod_dep,";
		    $sql1.=" SUM((a.deuda_vigente+a.monto_recargo+a.monto_multa+a.monto_intereses)-a.monto_descuento) as monto_total, ";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." and b.mes=1 ".$group_by2.")  as mes_1,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." and b.mes=2 ".$group_by2.")  as mes_2,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." and b.mes=3 ".$group_by2.")  as mes_3,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." and b.mes=4 ".$group_by2.")  as mes_4,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." and b.mes=5 ".$group_by2.")  as mes_5,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." and b.mes=6 ".$group_by2.")  as mes_6,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." and b.mes=7 ".$group_by2.")  as mes_7,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." and b.mes=8 ".$group_by2.")  as mes_8,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." and b.mes=9 ".$group_by2.")  as mes_9,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." and b.mes=10 ".$group_by2.") as mes_10,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." and b.mes=11 ".$group_by2.") as mes_11,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." and b.mes=12 ".$group_by2.") as mes_12";
         $sql1 .= " FROM v_shd900_planillas_deuda_cobro_detalles_cobradores_2 a WHERE ".$condicion." ".$sql." ".$group_by." ";

		$ejecuta=$this->v_shd900_planillas_deuda_cobro_detalles_cobradores_2->execute($sql1);


        if($ejecuta!=null){
			$this->set('datos',$ejecuta);
		}else{
			$this->set('datos',null);
		}

}else if($var1==4){  $this->layout="pdf";

            $this->set('user',                      $_SESSION['nom_usuario']);
            $this->set('tipo_impuesto',             $this->data["reporte_hacienda2"]["tipo_impuesto"]);
            $this->set('rdm',                       $this->data["reporte_hacienda2"]["rdm"]);
            $this->set('por_total',                 $this->data["reporte_hacienda2"]["por_total"]);
            $this->set('monto_total',               $this->data["reporte_hacienda2"]["monto_total"]);
            $this->set('por_mes_1',                 $this->data["reporte_hacienda2"]["por_mes_1"]);
            $this->set('monto_mes_1',               $this->data["reporte_hacienda2"]["monto_mes_1"]);
            $this->set('por_mes_2',                 $this->data["reporte_hacienda2"]["por_mes_2"]);
            $this->set('monto_mes_2',               $this->data["reporte_hacienda2"]["monto_mes_2"]);
            $this->set('por_mes_3',                 $this->data["reporte_hacienda2"]["por_mes_3"]);
            $this->set('monto_mes_3',               $this->data["reporte_hacienda2"]["monto_mes_3"]);
            $this->set('por_mes_4',                 $this->data["reporte_hacienda2"]["por_mes_4"]);
            $this->set('monto_mes_4',               $this->data["reporte_hacienda2"]["monto_mes_4"]);
            $this->set('por_mes_5',                 $this->data["reporte_hacienda2"]["por_mes_5"]);
            $this->set('monto_mes_5',               $this->data["reporte_hacienda2"]["monto_mes_5"]);
            $this->set('por_mes_6',                 $this->data["reporte_hacienda2"]["por_mes_6"]);
            $this->set('monto_mes_6',               $this->data["reporte_hacienda2"]["monto_mes_6"]);
            $this->set('por_mes_7',                 $this->data["reporte_hacienda2"]["por_mes_7"]);
            $this->set('monto_mes_7',               $this->data["reporte_hacienda2"]["monto_mes_7"]);
            $this->set('por_mes_8',                 $this->data["reporte_hacienda2"]["por_mes_8"]);
            $this->set('monto_mes_8',               $this->data["reporte_hacienda2"]["monto_mes_8"]);
            $this->set('por_mes_9',                 $this->data["reporte_hacienda2"]["por_mes_9"]);
            $this->set('monto_mes_9',               $this->data["reporte_hacienda2"]["monto_mes_9"]);
            $this->set('por_mes_10',                $this->data["reporte_hacienda2"]["por_mes_10"]);
            $this->set('monto_mes_10',              $this->data["reporte_hacienda2"]["monto_mes_10"]);
            $this->set('por_mes_11',                $this->data["reporte_hacienda2"]["por_mes_11"]);
            $this->set('monto_mes_11',              $this->data["reporte_hacienda2"]["monto_mes_11"]);
            $this->set('por_mes_12',                $this->data["reporte_hacienda2"]["por_mes_12"]);
            $this->set('monto_mes_12',              $this->data["reporte_hacienda2"]["monto_mes_12"]);


}//fin function



$this->set("opcion", $var1);


}//fin function
















function grafico_cobranza($var1=null, $var2=null){



       if($var1==1){ $this->layout="ajax";

					$tipo_impuesto=array(1=>'PATENTE DE INDUSTRIA Y COMERCIO',2=>'VEHÍCULOS',3=>'PROPAGANDA COMERCIAL',4=>'INMUEBLES URBANOS',5=>'ASEO DOMICILIARIO',6=>'ARRENDAMIENTO DE TIERRAS',7=>'CRÉDITOS DE VIVIENDA');
                    $this->set('tipo_impuesto',  $tipo_impuesto);

                    $datos  = $this->v_shd900_cobranza_acumulada_deno_part->execute(" SELECT DISTINCT ano FROM v_shd900_cobranza_acumulada_denominacion_partida WHERE ".$this->condicion()." ORDER BY ano ASC");
					if(count($datos)!=0){
						foreach($datos as $n){
							$lista[$n[0]['ano']]=$n[0]['ano'];
					    }
					}else{
						$lista=array('0'=>'No existen datos');
					}
					$this->set("lista_ano", $lista);

					$ano_arranque = $this->shd000_arranque->ano($this->condicion());

		            $this->set("ano_arranque",$ano_arranque);





}else if($var1==2){  $this->layout="ajax";

                    $tipo_impuesto=array(1=>'PATENTE DE INDUSTRIA Y COMERCIO',2=>'VEHÍCULOS',3=>'PROPAGANDA COMERCIAL',4=>'INMUEBLES URBANOS',5=>'ASEO DOMICILIARIO',6=>'ARRENDAMIENTO DE TIERRAS',7=>'CRÉDITOS DE VIVIENDA');
//                    $this->set('tipo_impuesto',  $tipo_impuesto);
                    $this->set('opcion_impuesto',$var2);

                    $datos  = $this->v_shd900_cobranza_acumulada_deno_part->execute(" SELECT DISTINCT cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, deno_partida, deno_generica, deno_especifica, deno_sub_espe, deno_auxiliar FROM v_shd900_cobranza_acumulada_denominacion_partida WHERE ".$this->condicion()." ORDER BY cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC");
					if(count($datos)!=0){
						foreach($datos as $n){


							              $deno_partida    = $n[0]['deno_partida'];
										  $deno_generica   = $n[0]['deno_generica'];
										  $deno_especifica = $n[0]['deno_especifica'];
										  $deno_sub_espe   = $n[0]['deno_sub_espe'];
										  $deno_auxiliar   = $n[0]['deno_auxiliar'];

										  $concatena = $n[0]['cod_partida'].'-'.$n[0]['cod_generica'].'-'.$n[0]['cod_especifica'].'-'.$n[0]['cod_sub_espec'].'-'.$n[0]['cod_auxiliar'];



										  if($deno_auxiliar==null || $deno_auxiliar==""){
										  	 if($deno_sub_espe==null || $deno_sub_espe==""){
										  	 	if($deno_especifica==null || $deno_especifica==""){
										  	 		if($deno_generica==null || $deno_generica==""){
										  	 			if($deno_partida==null || $deno_partida==""){
								                               $denominacion_impuesto = "";
														  }else{
														  	 $denominacion_impuesto = $n[0]['deno_partida'];
														  }
													  }else{
													  	 $denominacion_impuesto = $n[0]['deno_generica'];
													  }
												  }else{
												  	 $denominacion_impuesto = $n[0]['deno_especifica'];
												  }
											  }else{
											  	 $denominacion_impuesto = $n[0]['deno_sub_espe'];
											  }
										  }else{
										  	 $denominacion_impuesto = $n[0]['deno_auxiliar'];
										  }

							$lista[$n[0]['cod_partida'].'-'.$n[0]['cod_generica'].'-'.$n[0]['cod_especifica'].'-'.$n[0]['cod_sub_espec'].'-'.$n[0]['cod_auxiliar']]=$denominacion_impuesto;
					    }
					}else{
						$lista=array('0'=>'No existen datos');
					}
					$this->set("tipo_impuesto", $lista);

}else if($var1==3){  $this->layout="ajax";
              $sql          = " ";
              $sql2         = " ";
                  if($this->data['reporte_hacienda2']['tipo_impuesto']==1){
              $sql          .= "";
              $sql2         .= "";
              $this->set('tipo_impuesto', 0);
              $denominacion_impuesto = "";
            }else if($this->data['reporte_hacienda2']['tipo_impuesto']==2){
           	 if(!empty($this->data['reporte_hacienda2']['impuesto'])){
           	  $impuesto = split("-",$this->data['reporte_hacienda2']['impuesto']);
              $sql          .= " and a.cod_partida='".$impuesto[0]."' and a.cod_generica='".$impuesto[1]."' and a.cod_especifica='".$impuesto[2]."' and a.cod_sub_espec='".$impuesto[3]."' and a.cod_auxiliar='".$impuesto[4]."'";
              $sql2         .= " and b.cod_partida='".$impuesto[0]."' and b.cod_generica='".$impuesto[1]."' and b.cod_especifica='".$impuesto[2]."' and b.cod_sub_espec='".$impuesto[3]."' and b.cod_auxiliar='".$impuesto[4]."'";
              $sql2_aux      = " and   cod_partida='".$impuesto[0]."' and   cod_generica='".$impuesto[1]."' and   cod_especifica='".$impuesto[2]."' and   cod_sub_espec='".$impuesto[3]."' and   cod_auxiliar='".$impuesto[4]."'";
              $this->set('tipo_impuesto', $this->data['reporte_hacienda2']['impuesto']);
              $datos_aux  = $this->v_shd900_cobranza_acumulada_deno_part->execute(" SELECT DISTINCT cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, deno_partida, deno_generica, deno_especifica, deno_sub_espe, deno_auxiliar FROM v_shd900_cobranza_acumulada_denominacion_partida WHERE ".$this->condicion()." ".$sql2_aux." ORDER BY cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC");
			  $deno_partida    = $datos_aux[0][0]['deno_partida'];
			  $deno_generica   = $datos_aux[0][0]['deno_generica'];
			  $deno_especifica = $datos_aux[0][0]['deno_especifica'];
			  $deno_sub_espe   = $datos_aux[0][0]['deno_sub_espe'];
			  $deno_auxiliar   = $datos_aux[0][0]['deno_auxiliar'];
										  if($deno_auxiliar==null || $deno_auxiliar==""){
										  	 if($deno_sub_espe==null || $deno_sub_espe==""){
										  	 	if($deno_especifica==null || $deno_especifica==""){
										  	 		if($deno_generica==null || $deno_generica==""){
										  	 			if($deno_partida==null || $deno_partida==""){
								                               $denominacion_impuesto = "";
														  }else{
														  	 $denominacion_impuesto = $datos_aux[0][0]['deno_partida'];
														  }
													  }else{
													  	 $denominacion_impuesto = $datos_aux[0][0]['deno_generica'];
													  }
												  }else{
												  	 $denominacion_impuesto = $datos_aux[0][0]['deno_especifica'];
												  }
											  }else{
											  	 $denominacion_impuesto = $datos_aux[0][0]['deno_sub_espe'];
											  }
										  }else{
										  	 $denominacion_impuesto = $datos_aux[0][0]['deno_auxiliar'];
										  }
           	 }else{
           	  $denominacion_impuesto = "";
           	  $this->set('tipo_impuesto', 0);
           	 }
            }
            if(!empty($this->data['reporte_hacienda2']['ano'])){
              $sql          .= " and a.ano='".$this->data['reporte_hacienda2']['ano']."' ";
              $sql2         .= " and b.ano='".$this->data['reporte_hacienda2']['ano']."' ";
           	}
        $condicion  = 'a.cod_presi='.$this->Session->read('SScodpresi').'  and  a.cod_entidad='.$this->Session->read('SScodentidad').' and a.cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  a.cod_inst='.$this->Session->read('SScodinst').' and a.cod_dep='.$this->Session->read('SScoddep').' ';
        $condicion2 = 'b.cod_presi='.$this->Session->read('SScodpresi').'  and  b.cod_entidad='.$this->Session->read('SScodentidad').' and b.cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  b.cod_inst='.$this->Session->read('SScodinst').' and b.cod_dep='.$this->Session->read('SScoddep').' ';

        $group_by  ="  GROUP BY a.cod_presi,
							   a.cod_entidad,
							   a.cod_tipo_inst,
							   a.cod_inst ,
							   a.cod_dep";
		$group_by2 =" GROUP BY b.cod_presi,
							   b.cod_entidad,
							   b.cod_tipo_inst,
							   b.cod_inst ,
							   b.cod_dep";

        $sql1 ="SELECT
        		 a.cod_presi,
				 a.cod_entidad,
				 a.cod_tipo_inst,
				 a.cod_inst ,
				 a.cod_dep,";
		    $sql1.=" SUM((a.deuda_vigente+a.monto_recargo+a.monto_multa+a.monto_intereses)-a.monto_descuento) as monto_total, ";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where ".$condicion2." ".$sql2." and b.mes=1 ".$group_by2.")  as mes_1,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where ".$condicion2." ".$sql2." and b.mes=2 ".$group_by2.")  as mes_2,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where ".$condicion2." ".$sql2." and b.mes=3 ".$group_by2.")  as mes_3,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where ".$condicion2." ".$sql2." and b.mes=4 ".$group_by2.")  as mes_4,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where ".$condicion2." ".$sql2." and b.mes=5 ".$group_by2.")  as mes_5,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where ".$condicion2." ".$sql2." and b.mes=6 ".$group_by2.")  as mes_6,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where ".$condicion2." ".$sql2." and b.mes=7 ".$group_by2.")  as mes_7,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where ".$condicion2." ".$sql2." and b.mes=8 ".$group_by2.")  as mes_8,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where ".$condicion2." ".$sql2." and b.mes=9 ".$group_by2.")  as mes_9,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where ".$condicion2." ".$sql2." and b.mes=10 ".$group_by2.") as mes_10,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where ".$condicion2." ".$sql2." and b.mes=11 ".$group_by2.") as mes_11,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_cobranza_acumulada_denominacion_partida b where ".$condicion2." ".$sql2." and b.mes=12 ".$group_by2.") as mes_12";
         $sql1 .= " FROM v_shd900_cobranza_acumulada_denominacion_partida a WHERE ".$condicion." ".$sql." ".$group_by." ";

		$ejecuta=$this->v_shd900_cobranza_acumulada_deno_part->execute($sql1);


		$this->set('deno',$denominacion_impuesto);
        if($ejecuta!=null){
			$this->set('datos',$ejecuta);
		}else{
			$this->set('datos',null);
		}

}else if($var1==4){  $this->layout="pdf";

            $this->set('user',                      $_SESSION['nom_usuario']);
            $this->set('tipo_impuesto',             $this->data["reporte_hacienda2"]["tipo_impuesto"]);
            $this->set('rdm',                       $this->data["reporte_hacienda2"]["rdm"]);
            $this->set('deno',                      $this->data["reporte_hacienda2"]["deno"]);
            $this->set('por_total',                 $this->data["reporte_hacienda2"]["por_total"]);
            $this->set('monto_total',               $this->data["reporte_hacienda2"]["monto_total"]);
            $this->set('por_mes_1',                 $this->data["reporte_hacienda2"]["por_mes_1"]);
            $this->set('monto_mes_1',               $this->data["reporte_hacienda2"]["monto_mes_1"]);
            $this->set('por_mes_2',                 $this->data["reporte_hacienda2"]["por_mes_2"]);
            $this->set('monto_mes_2',               $this->data["reporte_hacienda2"]["monto_mes_2"]);
            $this->set('por_mes_3',                 $this->data["reporte_hacienda2"]["por_mes_3"]);
            $this->set('monto_mes_3',               $this->data["reporte_hacienda2"]["monto_mes_3"]);
            $this->set('por_mes_4',                 $this->data["reporte_hacienda2"]["por_mes_4"]);
            $this->set('monto_mes_4',               $this->data["reporte_hacienda2"]["monto_mes_4"]);
            $this->set('por_mes_5',                 $this->data["reporte_hacienda2"]["por_mes_5"]);
            $this->set('monto_mes_5',               $this->data["reporte_hacienda2"]["monto_mes_5"]);
            $this->set('por_mes_6',                 $this->data["reporte_hacienda2"]["por_mes_6"]);
            $this->set('monto_mes_6',               $this->data["reporte_hacienda2"]["monto_mes_6"]);
            $this->set('por_mes_7',                 $this->data["reporte_hacienda2"]["por_mes_7"]);
            $this->set('monto_mes_7',               $this->data["reporte_hacienda2"]["monto_mes_7"]);
            $this->set('por_mes_8',                 $this->data["reporte_hacienda2"]["por_mes_8"]);
            $this->set('monto_mes_8',               $this->data["reporte_hacienda2"]["monto_mes_8"]);
            $this->set('por_mes_9',                 $this->data["reporte_hacienda2"]["por_mes_9"]);
            $this->set('monto_mes_9',               $this->data["reporte_hacienda2"]["monto_mes_9"]);
            $this->set('por_mes_10',                $this->data["reporte_hacienda2"]["por_mes_10"]);
            $this->set('monto_mes_10',              $this->data["reporte_hacienda2"]["monto_mes_10"]);
            $this->set('por_mes_11',                $this->data["reporte_hacienda2"]["por_mes_11"]);
            $this->set('monto_mes_11',              $this->data["reporte_hacienda2"]["monto_mes_11"]);
            $this->set('por_mes_12',                $this->data["reporte_hacienda2"]["por_mes_12"]);
            $this->set('monto_mes_12',              $this->data["reporte_hacienda2"]["monto_mes_12"]);


}//fin function



$this->set("opcion", $var1);


}//fin function













function grafico_deuda($var1=null, $var2=null){



       if($var1==1){ $this->layout="ajax";

					$tipo_impuesto=array(1=>'PATENTE DE INDUSTRIA Y COMERCIO',2=>'VEHÍCULOS',3=>'PROPAGANDA COMERCIAL',4=>'INMUEBLES URBANOS',5=>'ASEO DOMICILIARIO',6=>'ARRENDAMIENTO DE TIERRAS',7=>'CRÉDITOS DE VIVIENDA');
                    $this->set('tipo_impuesto',  $tipo_impuesto);

                    $datos  = $this->shd900_planillas_deuda_cobro_detalles->execute(" SELECT DISTINCT ano FROM shd900_planillas_deuda_cobro_detalles WHERE ".$this->condicion()." ORDER BY ano ASC");
					if(count($datos)!=0){
						foreach($datos as $n){
							$lista[$n[0]['ano']]=$n[0]['ano'];
					    }
					}else{
						$lista=array('0'=>'No existen datos');
					}
					$this->set("lista_ano", $lista);

					$ano_arranque = $this->shd000_arranque->ano($this->condicion());

		            $this->set("ano_arranque",$ano_arranque);





}else if($var1==2){  $this->layout="ajax";

                    $tipo_impuesto=array(1=>'PATENTE DE INDUSTRIA Y COMERCIO',2=>'VEHÍCULOS',3=>'PROPAGANDA COMERCIAL',4=>'INMUEBLES URBANOS',5=>'ASEO DOMICILIARIO',6=>'ARRENDAMIENTO DE TIERRAS',7=>'CRÉDITOS DE VIVIENDA');
                    $this->set('tipo_impuesto',  $tipo_impuesto);
                    $this->set('opcion_impuesto',$var2);

}else if($var1==3){  $this->layout="ajax";
              $sql          = " ";
              $sql2         = " ";

                  if($this->data['reporte_hacienda2']['tipo_impuesto']==1){
              $sql          .= "";
              $sql2         .= "";
              $this->set('tipo_impuesto', 0);
            }else if($this->data['reporte_hacienda2']['tipo_impuesto']==2){
           	 if(!empty($this->data['reporte_hacienda2']['impuesto'])){
              $sql          .= " and a.cod_ingreso='".$this->data['reporte_hacienda2']['impuesto']."' ";
              $sql2         .= " and b.cod_ingreso='".$this->data['reporte_hacienda2']['impuesto']."' ";
              $this->set('tipo_impuesto', $this->data['reporte_hacienda2']['impuesto']);
           	 }else{
           	  $this->set('tipo_impuesto', 0);
           	 }
            }
            if(!empty($this->data['reporte_hacienda2']['ano'])){
              $sql          .= " and a.ano='".$this->data['reporte_hacienda2']['ano']."' ";
              $sql2         .= " and b.ano='".$this->data['reporte_hacienda2']['ano']."' ";
           	}
            if(!empty($this->data['reporte_hacienda2']['impuesto'])){
              $sql          .= " and a.cod_ingreso='".$this->data['reporte_hacienda2']['impuesto']."' ";
              $sql2         .= " and b.cod_ingreso='".$this->data['reporte_hacienda2']['impuesto']."' ";
           	}
        $condicion  = 'a.cod_presi='.$this->Session->read('SScodpresi').'  and  a.cod_entidad='.$this->Session->read('SScodentidad').' and a.cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  a.cod_inst='.$this->Session->read('SScodinst').' and a.cod_dep='.$this->Session->read('SScoddep').' ';
        $condicion2 = 'b.cod_presi='.$this->Session->read('SScodpresi').'  and  b.cod_entidad='.$this->Session->read('SScodentidad').' and b.cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  b.cod_inst='.$this->Session->read('SScodinst').' and b.cod_dep='.$this->Session->read('SScoddep').' ';

        $group_by  ="  GROUP BY a.cod_presi,
							   a.cod_entidad,
							   a.cod_tipo_inst,
							   a.cod_inst ,
							   a.cod_dep";
		$group_by2 =" GROUP BY b.cod_presi,
							   b.cod_entidad,
							   b.cod_tipo_inst,
							   b.cod_inst ,
							   b.cod_dep";

            $sql1 ="SELECT
	        		 a.cod_presi,
					 a.cod_entidad,
					 a.cod_tipo_inst,
					 a.cod_inst ,
					 a.cod_dep,";

			$sql_cancelado          = " and a.cancelado=1 ";
            $sql2_cancelado         = " and b.cancelado=1 ";
		    $sql1.=" SUM((a.deuda_vigente+a.monto_recargo+a.monto_multa+a.monto_intereses)-a.monto_descuento) as monto_total, ";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." ".$sql2_cancelado." and b.mes=1 ".$group_by2.")  as mes_1,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." ".$sql2_cancelado." and b.mes=2 ".$group_by2.")  as mes_2,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." ".$sql2_cancelado." and b.mes=3 ".$group_by2.")  as mes_3,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." ".$sql2_cancelado." and b.mes=4 ".$group_by2.")  as mes_4,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." ".$sql2_cancelado." and b.mes=5 ".$group_by2.")  as mes_5,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." ".$sql2_cancelado." and b.mes=6 ".$group_by2.")  as mes_6,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." ".$sql2_cancelado." and b.mes=7 ".$group_by2.")  as mes_7,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." ".$sql2_cancelado." and b.mes=8 ".$group_by2.")  as mes_8,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." ".$sql2_cancelado." and b.mes=9 ".$group_by2.")  as mes_9,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." ".$sql2_cancelado." and b.mes=10 ".$group_by2.") as mes_10,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." ".$sql2_cancelado." and b.mes=11 ".$group_by2.") as mes_11,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." ".$sql2_cancelado." and b.mes=12 ".$group_by2.") as mes_12";
            $sql1 .= " FROM v_shd900_planillas_deuda_cobro_detalles_cobradores_2 a WHERE ".$condicion." ".$sql." ".$sql_cancelado."  ".$group_by." ";
		    $ejecuta=$this->v_shd900_planillas_deuda_cobro_detalles_cobradores_2->execute($sql1);
	        if($ejecuta!=null){
				$this->set('datos',$ejecuta);
			}else{
				$this->set('datos',null);
			}

            $sql1 ="SELECT
	        		 a.cod_presi,
					 a.cod_entidad,
					 a.cod_tipo_inst,
					 a.cod_inst ,
					 a.cod_dep,";
			$sql_cancelado          = " and a.cancelado=2 ";
            $sql2_cancelado         = " and b.cancelado=2 ";
		    $sql1.=" SUM((a.deuda_vigente+a.monto_recargo+a.monto_multa+a.monto_intereses)-a.monto_descuento) as monto_total, ";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." ".$sql2_cancelado." and b.mes=1 ".$group_by2.")  as mes_1,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." ".$sql2_cancelado." and b.mes=2 ".$group_by2.")  as mes_2,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." ".$sql2_cancelado." and b.mes=3 ".$group_by2.")  as mes_3,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." ".$sql2_cancelado." and b.mes=4 ".$group_by2.")  as mes_4,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." ".$sql2_cancelado." and b.mes=5 ".$group_by2.")  as mes_5,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." ".$sql2_cancelado." and b.mes=6 ".$group_by2.")  as mes_6,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." ".$sql2_cancelado." and b.mes=7 ".$group_by2.")  as mes_7,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." ".$sql2_cancelado." and b.mes=8 ".$group_by2.")  as mes_8,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." ".$sql2_cancelado." and b.mes=9 ".$group_by2.")  as mes_9,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." ".$sql2_cancelado." and b.mes=10 ".$group_by2.") as mes_10,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." ".$sql2_cancelado." and b.mes=11 ".$group_by2.") as mes_11,";
			$sql1.="(select SUM((b.deuda_vigente+b.monto_recargo+b.monto_multa+b.monto_intereses)-b.monto_descuento) from v_shd900_planillas_deuda_cobro_detalles_cobradores_2 b where ".$condicion2." ".$sql2." ".$sql2_cancelado." and b.mes=12 ".$group_by2.") as mes_12";
            $sql1 .= " FROM v_shd900_planillas_deuda_cobro_detalles_cobradores_2 a WHERE ".$condicion." ".$sql." ".$sql_cancelado."  ".$group_by." ";
		    $ejecuta=$this->v_shd900_planillas_deuda_cobro_detalles_cobradores_2->execute($sql1);
	        if($ejecuta!=null){
				$this->set('datos2',$ejecuta);
			}else{
				$this->set('datos2',null);
			}




}else if($var1==4){  $this->layout="pdf";

            $this->set('user',                      $_SESSION['nom_usuario']);
            $this->set('tipo_impuesto',             $this->data["reporte_hacienda2"]["tipo_impuesto"]);
            $this->set('rdm',                       $this->data["reporte_hacienda2"]["rdm"]);
            $this->set('por_total',                 $this->data["reporte_hacienda2"]["por_total"]);
            $this->set('monto_total',               $this->data["reporte_hacienda2"]["monto_total"]);
            $this->set('por_mes_1',                 $this->data["reporte_hacienda2"]["por_mes_1"]);
            $this->set('monto_mes_1',               $this->data["reporte_hacienda2"]["monto_mes_1"]);
            $this->set('por_mes_2',                 $this->data["reporte_hacienda2"]["por_mes_2"]);
            $this->set('monto_mes_2',               $this->data["reporte_hacienda2"]["monto_mes_2"]);
            $this->set('por_mes_3',                 $this->data["reporte_hacienda2"]["por_mes_3"]);
            $this->set('monto_mes_3',               $this->data["reporte_hacienda2"]["monto_mes_3"]);
            $this->set('por_mes_4',                 $this->data["reporte_hacienda2"]["por_mes_4"]);
            $this->set('monto_mes_4',               $this->data["reporte_hacienda2"]["monto_mes_4"]);
            $this->set('por_mes_5',                 $this->data["reporte_hacienda2"]["por_mes_5"]);
            $this->set('monto_mes_5',               $this->data["reporte_hacienda2"]["monto_mes_5"]);
            $this->set('por_mes_6',                 $this->data["reporte_hacienda2"]["por_mes_6"]);
            $this->set('monto_mes_6',               $this->data["reporte_hacienda2"]["monto_mes_6"]);
            $this->set('por_mes_7',                 $this->data["reporte_hacienda2"]["por_mes_7"]);
            $this->set('monto_mes_7',               $this->data["reporte_hacienda2"]["monto_mes_7"]);
            $this->set('por_mes_8',                 $this->data["reporte_hacienda2"]["por_mes_8"]);
            $this->set('monto_mes_8',               $this->data["reporte_hacienda2"]["monto_mes_8"]);
            $this->set('por_mes_9',                 $this->data["reporte_hacienda2"]["por_mes_9"]);
            $this->set('monto_mes_9',               $this->data["reporte_hacienda2"]["monto_mes_9"]);
            $this->set('por_mes_10',                $this->data["reporte_hacienda2"]["por_mes_10"]);
            $this->set('monto_mes_10',              $this->data["reporte_hacienda2"]["monto_mes_10"]);
            $this->set('por_mes_11',                $this->data["reporte_hacienda2"]["por_mes_11"]);
            $this->set('monto_mes_11',              $this->data["reporte_hacienda2"]["monto_mes_11"]);
            $this->set('por_mes_12',                $this->data["reporte_hacienda2"]["por_mes_12"]);
            $this->set('monto_mes_12',              $this->data["reporte_hacienda2"]["monto_mes_12"]);


}//fin function



$this->set("opcion", $var1);


}//fin function










function consulta_ingresos($var1=null, $var2=null, $var3=null, $var4=null){


       if($var1==1){ $this->layout="ajax";

					$tipo_impuesto=array(1=>'PATENTE DE INDUSTRIA Y COMERCIO',2=>'VEHÍCULOS',3=>'PROPAGANDA COMERCIAL',4=>'INMUEBLES URBANOS',5=>'ASEO DOMICILIARIO',6=>'ARRENDAMIENTO DE TIERRAS',7=>'CRÉDITOS DE VIVIENDA');
                    $this->set('tipo_impuesto',  $tipo_impuesto);

                    $datos  = $this->shd900_cobranza_acumulada->execute(" SELECT DISTINCT ano FROM shd900_cobranza_acumulada WHERE ".$this->condicion()." ORDER BY ano ASC");
					if(count($datos)!=0){
						foreach($datos as $n){
							$lista[$n[0]['ano']]=$n[0]['ano'];
					    }
					}else{
						$lista=array('0'=>'No existen datos');
					}
					$this->set("lista_ano", $lista);

					$ano_arranque = $this->shd000_arranque->ano($this->condicion());

		            $this->set("ano_arranque",$ano_arranque);

		            $datos  = $this->shd900_cobranza_acumulada->execute(" SELECT DISTINCT mes FROM shd900_cobranza_acumulada WHERE ".$this->condicion()." and ano='".$ano_arranque."' ORDER BY mes ASC");
					$mes= array(1=>'ENERO',2=>'FEBRERO',3=>'MARZO',4=>'ABRIL',5=>'MAYO',6=>'JUNIO',7=>'JULIO',8=>'AGOSTO',9=>'SEPTIEMBRE',10=>'OCTUBRE',11=>'NOVIEMBRE',12=>'DICIEMBRE');
					if(count($datos)!=0){
						foreach($datos as $n){
							$lista_mes[$n[0]['mes']]=$mes[$n[0]['mes']];
					    }
					}else{
						    $lista_mes=array('0'=>'No existen datos');
					}
					$this->set("lista_mes", $lista_mes);
                    $this->set("year", $ano_arranque);

                          $sql   =  $this->condicion()." and ano='".$ano_arranque."'  ";
			             $campos =" cod_presi,
								    cod_entidad,
								    cod_tipo_inst,
								    cod_inst,
								    cod_dep,
			                        SUM(deuda_vigente)           as       deuda_vigente,
								    SUM(deuda_anterior)          as       deuda_anterior,
								    SUM(monto_recargo)           as       monto_recargo,
								    SUM(monto_multa)             as       monto_multa,
								    SUM(monto_intereses)         as       monto_intereses,
								    SUM(monto_descuento)         as       monto_descuento,
								    SUM(cantidad_depositos)      as       cantidad_depositos,
								    SUM(monto_depositos)         as       monto_depositos,
								    SUM(cantidad_notas_credito)  as       cantidad_notas_credito,
								    SUM(monto_notas_credito)     as       monto_notas_credito,
								    SUM(cantidad_cheques)        as       cantidad_cheques,
								    SUM(monto_cheques)           as       monto_cheques,
								    SUM(cantidad_descuento)      as       cantidad_descuento,
								    SUM(cantidad_pagos_efectivo) as       cantidad_pagos_efectivo,
								    SUM(monto_pagos_efectivo)    as       monto_pagos_efectivo,
								    cod_partida,
			                        cod_generica,
			                        cod_especifica,
			                        cod_sub_espec,
			                        cod_auxiliar,
			                        deno_partida,
									deno_generica,
								    deno_especifica,
								    deno_sub_espe,
									deno_auxiliar ";

			             $group_by =" GROUP  BY   cod_presi,
											      cod_entidad,
											      cod_tipo_inst,
											      cod_inst,
											      cod_dep,
											      cod_partida,
						                          cod_generica,
						                          cod_especifica,
						                          cod_sub_espec,
						                          cod_auxiliar,
						                          deno_partida,
												  deno_generica,
												  deno_especifica,
												  deno_sub_espe,
											      deno_auxiliar ";


			             $datos_filas=$this->v_shd900_cobranza_acumulada_deno_part->findAll($sql." ".$group_by,$campos,"cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC");
						 $this->set("datos2",$datos_filas);



}else if($var1==2){  $this->layout="ajax";

	                if($var2==null){$var2=0;}

                    $datos  = $this->shd900_cobranza_acumulada->execute(" SELECT DISTINCT mes FROM shd900_cobranza_acumulada WHERE ".$this->condicion()." and ano='".$var2."' ORDER BY mes ASC");
					$mes= array(1=>'ENERO',2=>'FEBRERO',3=>'MARZO',4=>'ABRIL',5=>'MAYO',6=>'JUNIO',7=>'JULIO',8=>'AGOSTO',9=>'SEPTIEMBRE',10=>'OCTUBRE',11=>'NOVIEMBRE',12=>'DICIEMBRE');
					if(count($datos)!=0){
						foreach($datos as $n){
							$lista[$n[0]['mes']]=$mes[$n[0]['mes']];
					    }
					}else{
						$lista=array('0'=>'No existen datos');
					}
					$this->set("lista_mes", $lista);
                    $this->set("year", $var2);

}else if($var1==3){  $this->layout="ajax";

                     if($var2==null){$var2=0;}
                     if($var3==null){$var3=0;}
	                 $datos  = $this->shd900_cobranza_acumulada->execute(" SELECT DISTINCT dia FROM shd900_cobranza_acumulada WHERE ".$this->condicion()." and ano='".$var2."' and mes='".$var3."' ORDER BY dia ASC");
					if(count($datos)!=0){
						foreach($datos as $n){
							$lista[$n[0]['dia']]=mascara2($n[0]['dia']);
					    }
					}else{
						$lista=array('0'=>'No existen datos');
					}
					$this->set("lista_dia", $lista);
                    $this->set("year", $var2);
                    $this->set("mes", $var3);


}else if($var1==4){  $this->layout="ajax";
	                         $sql =  $this->condicion();
             if($var2!=null){$sql.=" and ano='".$var2."'  ";}else{$sql.=" and ano='0'  ";}
             if($var3!=null){$sql.=" and mes='".$var3."'  ";}
             if($var4!=null){$sql.=" and dia='".$var4."'  ";}


             $campos =" cod_presi,
					    cod_entidad,
					    cod_tipo_inst,
					    cod_inst,
					    cod_dep,
                        SUM(deuda_vigente)           as       deuda_vigente,
					    SUM(deuda_anterior)          as       deuda_anterior,
					    SUM(monto_recargo)           as       monto_recargo,
					    SUM(monto_multa)             as       monto_multa,
					    SUM(monto_intereses)         as       monto_intereses,
					    SUM(monto_descuento)         as       monto_descuento,
					    SUM(cantidad_depositos)      as       cantidad_depositos,
					    SUM(monto_depositos)         as       monto_depositos,
					    SUM(cantidad_notas_credito)  as       cantidad_notas_credito,
					    SUM(monto_notas_credito)     as       monto_notas_credito,
					    SUM(cantidad_cheques)        as       cantidad_cheques,
					    SUM(monto_cheques)           as       monto_cheques,
					    SUM(cantidad_descuento)      as       cantidad_descuento,
					    SUM(cantidad_pagos_efectivo) as       cantidad_pagos_efectivo,
					    SUM(monto_pagos_efectivo)    as       monto_pagos_efectivo,
					    cod_partida,
                        cod_generica,
                        cod_especifica,
                        cod_sub_espec,
                        cod_auxiliar,
                        deno_partida,
						deno_generica,
					    deno_especifica,
					    deno_sub_espe,
						deno_auxiliar ";

             $group_by =" GROUP  BY   cod_presi,
								      cod_entidad,
								      cod_tipo_inst,
								      cod_inst,
								      cod_dep,
								      cod_partida,
			                          cod_generica,
			                          cod_especifica,
			                          cod_sub_espec,
			                          cod_auxiliar,
			                          deno_partida,
									  deno_generica,
									  deno_especifica,
									  deno_sub_espe,
								      deno_auxiliar ";


             $datos_filas=$this->v_shd900_cobranza_acumulada_deno_part->findAll($sql." ".$group_by,$campos,"cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC");
			 $this->set("datos2",$datos_filas);
}


$this->set("opcion", $var1);
}//fin funtion















function consulta_deudas_contribuyente($var1=null, $var2=null, $var3=null, $var4=null){

$this->layout="ajax";
$tipo_impuesto_array = array(1=>'PATENTE DE INDUSTRIA Y COMERCIO',
                             2=>'VEHÍCULOS',
                             3=>'PROPAGANDA COMERCIAL',
                             4=>'INMUEBLES URBANOS',
                             5=>'ASEO DOMICILIARIO',
                             6=>'ARRENDAMIENTO DE TIERRAS',
                             7=>'CRÉDITOS DE VIVIENDA');

$mes= array(1=>'ENERO',2=>'FEBRERO',3=>'MARZO',4=>'ABRIL',5=>'MAYO',6=>'JUNIO',7=>'JULIO',8=>'AGOSTO',9=>'SEPTIEMBRE',10=>'OCTUBRE',11=>'NOVIEMBRE',12=>'DICIEMBRE');

         if($var1==1){





   }else if($var1==2){

			    $this->Session->delete('pista');

   }else if($var1==3){


           if($var4==null){
           	        $var3 = strtoupper_sisap($var3);
					$this->Session->write('pista', $var3);
					$Tfilas=$this->v_shd001_registro_contribuyentes->findCount("((rif_cedula LIKE '%$var3%') or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var3%')))");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_shd001_registro_contribuyentes->findAll("((rif_cedula LIKE '%$var3%') or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var3%')))   ",null,"rif_cedula ASC",50,1,null);
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
						$Tfilas=$this->v_shd001_registro_contribuyentes->findCount("((rif_cedula LIKE '%$var22%') or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var22%')))");
						        if($Tfilas!=0){
						        	$pagina=$var4;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_shd001_registro_contribuyentes->findAll("((rif_cedula LIKE '%$var22%') or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var22%')))  ",null,"rif_cedula ASC",50,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else



   }else if($var1==4){


						    $datos=$this->shd001_registro_contribuyentes->findAll("rif_cedula='".$var3."'");
							$cod_profesion=$datos[0]["shd001_registro_contribuyentes"]["profesion"];
							$cod_pais=$datos[0]["shd001_registro_contribuyentes"]["cod_pais"];
							$cod_estado=$datos[0]["shd001_registro_contribuyentes"]["cod_estado"];
							$cod_municipio=$datos[0]["shd001_registro_contribuyentes"]["cod_municipio"];
							$cod_parroquia=$datos[0]["shd001_registro_contribuyentes"]["cod_parroquia"];
							$cod_centro_poblado=$datos[0]["shd001_registro_contribuyentes"]["cod_centro_poblado"];
							$cod_calle_avenida=$datos[0]["shd001_registro_contribuyentes"]["cod_calle_avenida"];
							$cod_vereda_edificio=$datos[0]["shd001_registro_contribuyentes"]["cod_vereda_edificio"];
							$pais=$this->cugd01_republica->findAll('cod_republica='.$cod_pais);
							$estados=$this->cugd01_estados->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado);
							$municipios=$this->cugd01_municipios->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio);
							$parroquias=$this->cugd01_parroquias->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio.' and cod_parroquia='.$cod_parroquia);
							$centros=$this->cugd01_centropoblados->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio.' and cod_parroquia='.$cod_parroquia.' and cod_centro='.$cod_centro_poblado);
							$vialidad=$this->cugd01_vialidad->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio.' and cod_parroquia='.$cod_parroquia.' and cod_centro='.$cod_centro_poblado.' and cod_vialidad='.$cod_calle_avenida);
							$vereda=$this->cugd01_vereda->findAll('cod_republica='.$cod_pais.' and cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio.' and cod_parroquia='.$cod_parroquia.' and cod_centro='.$cod_centro_poblado.' and cod_vialidad='.$cod_calle_avenida.' and cod_vereda='.$cod_vereda_edificio);
							$profesiones=$this->cnmd06_profesiones->findAll('cod_profesion='.$cod_profesion);
							$this->set('profesion',$profesiones);
							$this->set('pais',$pais);
							$this->set('estados',$estados);
							$this->set('municipios',$municipios);
							$this->set('parroquias',$parroquias);
							$this->set('centros',$centros);
							$this->set('vialidad',$vialidad);
							$this->set('vereda',$vereda);
							$this->set('datos',$datos);

                            $datos_1  = $this->shd900_planillas_deuda_cobro_detalles->execute(" SELECT * FROM v_shd900_planillas_deuda_cobro_detalles_cobradores_2 WHERE ".$this->condicion()." and rif_cedula='".$var3."' and cod_ingreso='1' and cancelado='2' ORDER BY ano, mes, numero_planilla ASC");
                            $this->set('datos_1',$datos_1);

                            $datos_1_2  = $this->shd100_declaracion_ingresos->execute(" SELECT * FROM shd100_declaracion_ingresos WHERE ".$this->condicion()." and rif_cedula='".$var3."' and condicion_actividad='1' and cancelado='2' ORDER BY ano_declaracion, numero_declaracion ASC");
                            $this->set('datos_1_2',$datos_1_2);

                            $datos_2  = $this->shd900_planillas_deuda_cobro_detalles->execute(" SELECT * FROM v_shd900_planillas_deuda_cobro_detalles_cobradores_2 WHERE ".$this->condicion()." and rif_cedula='".$var3."' and cod_ingreso='2' and cancelado='2' ORDER BY ano, mes, numero_planilla ASC");
                            $this->set('datos_2',$datos_2);

                            $datos_3  = $this->shd900_planillas_deuda_cobro_detalles->execute(" SELECT * FROM v_shd900_planillas_deuda_cobro_detalles_cobradores_2 WHERE ".$this->condicion()." and rif_cedula='".$var3."' and cod_ingreso='3' and cancelado='2' ORDER BY ano, mes, numero_planilla ASC");
                            $this->set('datos_3',$datos_3);

                            $datos_4  = $this->shd900_planillas_deuda_cobro_detalles->execute(" SELECT * FROM v_shd900_planillas_deuda_cobro_detalles_cobradores_2 WHERE ".$this->condicion()." and rif_cedula='".$var3."' and cod_ingreso='4' and cancelado='2' ORDER BY ano, mes, numero_planilla ASC");
                            $this->set('datos_4',$datos_4);

                            $datos_5  = $this->shd900_planillas_deuda_cobro_detalles->execute(" SELECT * FROM v_shd900_planillas_deuda_cobro_detalles_cobradores_2 WHERE ".$this->condicion()." and rif_cedula='".$var3."' and cod_ingreso='5' and cancelado='2' ORDER BY ano, mes, numero_planilla ASC");
                            $this->set('datos_5',$datos_5);

                            $datos_6  = $this->shd900_planillas_deuda_cobro_detalles->execute(" SELECT * FROM v_shd900_planillas_deuda_cobro_detalles_cobradores_2 WHERE ".$this->condicion()." and rif_cedula='".$var3."' and cod_ingreso='6' and cancelado='2' ORDER BY ano, mes, numero_planilla ASC");
                            $this->set('datos_6',$datos_6);

                            $datos_7  = $this->shd900_planillas_deuda_cobro_detalles->execute(" SELECT * FROM v_shd900_planillas_deuda_cobro_detalles_cobradores_2 WHERE ".$this->condicion()." and rif_cedula='".$var3."' and cod_ingreso='7' and cancelado='2' ORDER BY ano, mes, numero_planilla ASC");
                            $this->set('datos_7',$datos_7);



   }///fin else


$this->set("mes_array", $mes);
$this->set('tipo_impuesto_array',  $tipo_impuesto_array);
$this->set("opcion1", $var1);
$this->set("opcion2", $var2);

}//fin function













function declaracion_ingresos_brutos($var1=null, $var2=null, $var3=null){



$tipo_impuesto_array = array(1=>'PATENTE DE INDUSTRIA Y COMERCIO',
                             2=>'VEHÍCULOS',
                             3=>'PROPAGANDA COMERCIAL',
                             4=>'INMUEBLES URBANOS',
                             5=>'ASEO DOMICILIARIO',
                             6=>'ARRENDAMIENTO DE TIERRAS',
                             7=>'CRÉDITOS DE VIVIENDA');

$mes_array =  array(1=>'ENERO',
		            2=>'FEBRERO',
		            3=>'MARZO',
		            4=>'ABRIL',
		            5=>'MAYO',
		            6=>'JUNIO',
		            7=>'JULIO',
		            8=>'AGOSTO',
		            9=>'SEPTIEMBRE',
		            10=>'OCTUBRE',
		            11=>'NOVIEMBRE',
		            12=>'DICIEMBRE');


         if($var1==1){$this->layout="ajax";


   }else if($var1==2){$this->layout="ajax";

   	        $ver=$this->v_decla_ingre_bruto_con_contrib->execute("select * from shd000_arranque where ".$this->condicion());
			if($ver!=null){
				$this->set('ano1',$ver[0][0]['ano_arranque']);
				$this->set('mes',$ver[0][0]['mes_arranque']);
			}else{
				$this->set('ano1','');
				$this->set('mes','');
			}

   	        $datos  = $this->v_decla_ingre_bruto_con_contrib->execute(" SELECT DISTINCT ano_declaracion FROM v_declaracion_ingreso_bruto_con_contribuyente WHERE ".$this->condicion()." ORDER BY ano_declaracion ASC");
			if(count($datos)!=0){
				foreach($datos as $n){
					$lista[$n[0]['ano_declaracion']]=$n[0]['ano_declaracion'];
			    }
			}else{
				$lista=array('0'=>'No existen datos');
			}
			$this->set("lista_ano", $lista);


   }else if($var1==3){$this->layout="pdf";


   	           if($this->data['reporte_hacienda2']['tipo_year']==1){
              $sql           = "";
            }else if($this->data['reporte_hacienda2']['tipo_year']==2){
           	 if(!empty($this->data['reporte_hacienda2']['tipo_year'])){
              $sql           = " and ano_declaracion='".$this->data['reporte_hacienda2']['ano']."' ";
           	 }
            }
                    $ordenado = $this->data['reporte_hacienda2']['ordenado'];
                 if($ordenado==2){
			//ALFABETICO
           	 $sql .= " ORDER BY razon_social_nombres, numero_declaracion,ano_declaracion, fecha_declaracion ASC";
           }else if($ordenado==1){
//			RIF
              $sql .= " ORDER BY rif_cedula, numero_declaracion,ano_declaracion, fecha_declaracion ASC";
           }

		$sql1=("select * from v_declaracion_ingreso_bruto_con_contribuyente where ".$this->condicion()." and condicion_actividad=1 ".$sql);
		$ejecuta=$this->v_decla_ingre_bruto_con_contrib->execute($sql1);

        $this->set("datos", $ejecuta);
   }//fin else



$this->set("mes_array", $mes_array);
$this->set('tipo_impuesto_array',  $tipo_impuesto_array);
$this->set("opcion1", $var1);
$this->set("opcion2", $var2);
$this->set("opcion3", $var3);

}//fin function











function relacion_contribuyente_aseo_domiciliario($var1=null, $var2=null){



$tipo_impuesto_array = array(1=>'PATENTE DE INDUSTRIA Y COMERCIO',
                             2=>'VEHÍCULOS',
                             3=>'PROPAGANDA COMERCIAL',
                             4=>'INMUEBLES URBANOS',
                             5=>'ASEO DOMICILIARIO',
                             6=>'ARRENDAMIENTO DE TIERRAS',
                             7=>'CRÉDITOS DE VIVIENDA');

$mes_array =  array(1=>'ENERO',
		            2=>'FEBRERO',
		            3=>'MARZO',
		            4=>'ABRIL',
		            5=>'MAYO',
		            6=>'JUNIO',
		            7=>'JULIO',
		            8=>'AGOSTO',
		            9=>'SEPTIEMBRE',
		            10=>'OCTUBRE',
		            11=>'NOVIEMBRE',
		            12=>'DICIEMBRE');


         if($var1==1){$this->layout="ajax";


   }else if($var1==2){$this->layout="pdf";


                    $ordenado = $this->data['reporte_hacienda2']['ordenado'];
                 if($ordenado==1){
           	 $sql  = " ORDER BY razon_social_nombres ASC";
           }else if($ordenado==2){
              $sql = " ORDER BY rif_cedula ASC";
           }

		$sql1=("select * from v_shd500_aseo_domiciliario_deuda_cobro_detalles where ".$this->condicion()." ".$sql);
		$ejecuta=$this->v_shd500_aseo_domiciliario_deuda_cobro_detalles->execute($sql1);
        $this->set("datos", $ejecuta);
   }//fin else



$this->set("opcion1", $var1);
$this->set("opcion2", $var2);


}//fin function









function relacion_contribuyente_credito_vivienda($var1=null, $var2=null){



$tipo_impuesto_array = array(1=>'PATENTE DE INDUSTRIA Y COMERCIO',
                             2=>'VEHÍCULOS',
                             3=>'PROPAGANDA COMERCIAL',
                             4=>'INMUEBLES URBANOS',
                             5=>'ASEO DOMICILIARIO',
                             6=>'ARRENDAMIENTO DE TIERRAS',
                             7=>'CRÉDITOS DE VIVIENDA');

$mes_array =  array(1=>'ENERO',
		            2=>'FEBRERO',
		            3=>'MARZO',
		            4=>'ABRIL',
		            5=>'MAYO',
		            6=>'JUNIO',
		            7=>'JULIO',
		            8=>'AGOSTO',
		            9=>'SEPTIEMBRE',
		            10=>'OCTUBRE',
		            11=>'NOVIEMBRE',
		            12=>'DICIEMBRE');


         if($var1==1){$this->layout="ajax";


   }else if($var1==2){$this->layout="pdf";


                    $ordenado = $this->data['reporte_hacienda2']['ordenado'];
                 if($ordenado==1){
           	 $sql  = " ORDER BY razon_social_nombres, numero_solicitud ASC";
           }else if($ordenado==2){
              $sql = " ORDER BY rif_cedula, numero_solicitud ASC";
           }

		$sql1=("select * from v_shd700_credito_vivienda_deuda_cobro_detalles where ".$this->condicion()." ".$sql);
		$ejecuta=$this->v_shd700_credito_vivienda_deuda_cobro_detalles->execute($sql1);
        $this->set("datos", $ejecuta);
   }//fin else



$this->set("opcion1", $var1);
$this->set("opcion2", $var2);


}//fin function








function relacion_contribuyente_propaganda($var1=null, $var2=null){



$tipo_impuesto_array = array(1=>'PATENTE DE INDUSTRIA Y COMERCIO',
                             2=>'VEHÍCULOS',
                             3=>'PROPAGANDA COMERCIAL',
                             4=>'INMUEBLES URBANOS',
                             5=>'ASEO DOMICILIARIO',
                             6=>'ARRENDAMIENTO DE TIERRAS',
                             7=>'CRÉDITOS DE VIVIENDA');

$mes_array =  array(1=>'ENERO',
		            2=>'FEBRERO',
		            3=>'MARZO',
		            4=>'ABRIL',
		            5=>'MAYO',
		            6=>'JUNIO',
		            7=>'JULIO',
		            8=>'AGOSTO',
		            9=>'SEPTIEMBRE',
		            10=>'OCTUBRE',
		            11=>'NOVIEMBRE',
		            12=>'DICIEMBRE');


         if($var1==1){$this->layout="ajax";


   }else if($var1==2){$this->layout="pdf";


                    $ordenado = $this->data['reporte_hacienda2']['ordenado'];
                 if($ordenado==1){
           	 $sql  = " ORDER BY razon_social_nombres ASC";
           }else if($ordenado==2){
              $sql = " ORDER BY rif_cedula ASC";
           }

		$sql1=("select * from v_shd300_propaganda_deuda_cobro_detalles where ".$this->condicion()." ".$sql);
		$ejecuta=$this->v_shd300_propaganda_deuda_cobro_detalles->execute($sql1);
        $this->set("datos", $ejecuta);
   }//fin else



$this->set("opcion1", $var1);
$this->set("opcion2", $var2);


}//fin function










function ubicacion_geografica($var1=null){

set_time_limit(0);

         if($var1==1){$this->layout="ajax";

   }else if($var1==2){$this->layout="pdf";

   	    $sql_1 = "";
   	    $sql_2 = "";

		     if(!empty($this->data['reporte_hacienda2']['pais'])){
		     	$sql_1 .= "   cod_republica='".$this->data['reporte_hacienda2']['pais']."' ";
		     	$sql_2 .= "  (cod_republica='".$this->data['reporte_hacienda2']['pais']."' and cod_estado=0 and cod_municipio=0 and cod_parroquia=0 and cod_centro=0 and cod_vialidad=0 and cod_vereda=0) ";
		     }
		     if(!empty($this->data['reporte_hacienda2']['estado'])){
		     	$sql_1 .= " and cod_estado='".$this->data['reporte_hacienda2']['estado']."' ";
		     	$sql_2 .= " or (cod_republica='".$this->data['reporte_hacienda2']['pais']."' and cod_estado='".$this->data['reporte_hacienda2']['estado']."' and cod_municipio=0 and cod_parroquia=0 and cod_centro=0 and cod_vialidad=0 and cod_vereda=0)";
		     }
		     if(!empty($this->data['reporte_hacienda2']['municipio'])){
		     	$sql_1 .= " and  cod_municipio='".$this->data['reporte_hacienda2']['municipio']."' ";
		     	$sql_2 .= " or (cod_republica='".$this->data['reporte_hacienda2']['pais']."' and cod_estado='".$this->data['reporte_hacienda2']['estado']."'  and  cod_municipio='".$this->data['reporte_hacienda2']['municipio']."' and cod_parroquia=0 and cod_centro=0 and cod_vialidad=0 and cod_vereda=0)";
		     }
		     if(!empty($this->data['reporte_hacienda2']['parroquia'])){
		     	$sql_1 .= " and cod_parroquia='".$this->data['reporte_hacienda2']['parroquia']."' ";
		     	$sql_2 .= " or (cod_republica='".$this->data['reporte_hacienda2']['pais']."' and cod_estado='".$this->data['reporte_hacienda2']['estado']."'  and  cod_municipio='".$this->data['reporte_hacienda2']['municipio']."' and cod_parroquia='".$this->data['reporte_hacienda2']['parroquia']."' and cod_centro=0 and cod_vialidad=0 and cod_vereda=0)";
		     }
		     if(!empty($this->data['reporte_hacienda2']['centro_poblado'])){
		     	$sql_1 .= " and  cod_centro='".$this->data['reporte_hacienda2']['centro_poblado']."' ";
		     	$sql_2 .= " or (cod_republica='".$this->data['reporte_hacienda2']['pais']."' and cod_estado='".$this->data['reporte_hacienda2']['estado']."'  and  cod_municipio='".$this->data['reporte_hacienda2']['municipio']."' and cod_parroquia='".$this->data['reporte_hacienda2']['parroquia']."' and cod_centro='".$this->data['reporte_hacienda2']['centro_poblado']."' and cod_vialidad=0 and cod_vereda=0)";
		     }

        if($sql_1!=""){ $sql_1 = " WHERE ".$sql_1." or (".$sql_2.") ";}

		$sql1=("select * from v_relacion_zonageografica  ".$sql_1."   ORDER BY cod_republica,  cod_estado, cod_municipio, cod_parroquia,  cod_centro,  cod_vialidad, cod_vereda ASC " );
		$ejecuta=$this->v_relacion_zonageografica->execute($sql1);
        $this->set("datos", $ejecuta);
   }//fin else


$this->set("opcion1", $var1);

}//fin function










}//fin class

?>