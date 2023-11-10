<?php

class ReporteHaciendaController extends AppController{


    var $name    = "reporte_hacienda";
    var $uses    = array('ccfd04_cierre_mes','cnmd06_profesiones','cugd01_estados','cugd01_municipios','v_cfpd05_denominaciones','v_shd100_declaracion_ingreso',
    					'cugd01_centropoblados','cugd01_parroquias','arrd05','shd001_registro_contribuyentes','v_shd100_patente','shd100_actividades',
    					'shd002_cobradores','v_shd001_contribuyentes_e_impuestos','v_shd900_cobranza_acumulada_ano_mes_dia','v_shd900_cobranza_acumulada_ano_mes',
						'v_shd900_cobranza_acumulada_ano','v_shd900_cobranza_acumulada','shd000_arranque','v_shd100_solicitud_actividades','v_shd100_solicitud','v_shd001_registro_contribuyentes',
						'shd950_solvencia','v_shd200_vehiculos','v_shd900_planillas_deuda_cobro_detalles','shd000_control_actualizacion','cfpd03','v_cfpd03_denominacion_partida','v_shd100_patente_actividades','cugd07_firmas_oficio_anulacion');



// agregar estos modelos para los reportes de hacienda	'shd100_patente','shd001_registro_contribuyentes','shd100_solicitud','v_shd100_declaracion_ingreso'

    var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap');

//,'v_balance_ejecucion_partidas_inst','v_balance_ejecucion_partidas_dep'




function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
}//fin checksession





function beforeFilter(){$this->checkSession();}

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



function shp001_registro_contribuyentes($var1=null, $var2=null){





    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

	set_time_limit(0);



     if($var1==1){

           $this->layout = "ajax";
           $opcion = 1;


     }else{

           $this->layout = "pdf";
           $opcion = 2;

           $ordenado = $this->data['reporte3']['ordenado'];


           if($ordenado==1){

           	  $sql = "ORDER BY a.rif_cedula ASC";


           }else if($ordenado==2){
           		 $sql = "ORDER BY a.razon_social_nombres ASC";


           }else{

              $sql = "ORDER BY
              		      a.cod_estado,
						  a.cod_municipio,
						  a.cod_parroquia,
						  a.cod_centro_poblado,
						  a.rif_cedula ASC";

           }//fin else

$filtro=$this->SQLCA();
$rs=$this->v_cfpd05_denominaciones->execute("
                      SELECT
                          a.rif_cedula,
						  a.personalidad_juridica,
						  a.razon_social_nombres,
						  a.fecha_inscripcion,
						  a.nacionalidad,
						  a.estado_civil,
						  a.profesion,
						  a.cod_pais ,
						  a.cod_estado,
						  a.cod_municipio,
						  a.cod_parroquia,
						  a.cod_centro_poblado,
						  a.cod_calle_avenida,
						  a.cod_vereda_edificio,
						  a.numero_vivienda_local,
						  a.telefonos_fijos,
						  a.telefonos_celulares,
						  a.correo_electronico,
						  (SELECT ff.monto_mensual FROM v_shd001_contribuyentes_e_impuestos ff where ".$filtro." and ff.rif_cedula=a.rif_cedula and pertenece_tabla=1::character varying) as monto_mensual,
                         (SELECT xya.denominacion FROM cugd01_estados          xya where xya.cod_republica=a.cod_pais and xya.cod_estado=a.cod_estado                                                                                                                          GROUP BY xya.denominacion) as  deno_cod_estado,
						 (SELECT xyb.denominacion FROM cugd01_municipios       xyb where xyb.cod_republica=a.cod_pais and xyb.cod_estado=a.cod_estado  and xyb.cod_municipio=a.cod_municipio                                                                                   GROUP BY xyb.denominacion) as  deno_cod_municipio,
						 (SELECT xyc.denominacion FROM cugd01_parroquias       xyc where xyc.cod_republica=a.cod_pais and xyc.cod_estado=a.cod_estado  and xyc.cod_municipio=a.cod_municipio and xyc.cod_parroquia = a.cod_parroquia                                           GROUP BY xyc.denominacion) as  deno_cod_parroquia,
						 (SELECT xyd.denominacion FROM cugd01_centros_poblados xyd where xyd.cod_republica=a.cod_pais and xyd.cod_estado=a.cod_estado  and xyd.cod_municipio=a.cod_municipio and xyd.cod_parroquia = a.cod_parroquia and xyd.cod_centro = a.cod_centro_poblado GROUP BY xyd.denominacion) as  deno_cod_centro,
						 (SELECT xyf.denominacion FROM cugd01_vialidad xyf where xyf.cod_republica=a.cod_pais and xyf.cod_estado=a.cod_estado  and xyf.cod_municipio=a.cod_municipio and xyf.cod_parroquia = a.cod_parroquia and xyf.cod_centro = a.cod_centro_poblado and xyf.cod_vialidad=a.cod_calle_avenida GROUP BY xyf.denominacion) as  deno_cod_vialidad,
						 (SELECT xyg.denominacion FROM cugd01_vereda xyg where xyg.cod_republica=a.cod_pais and xyg.cod_estado=a.cod_estado  and xyg.cod_municipio=a.cod_municipio and xyg.cod_parroquia = a.cod_parroquia and xyg.cod_centro = a.cod_centro_poblado and xyg.cod_vialidad=a.cod_calle_avenida and xyg.cod_vereda=a.cod_vereda_edificio GROUP BY xyg.denominacion) as  deno_cod_vereda

                       FROM
                          shd001_registro_contribuyentes a

                       ".$sql."



");


 $this->set("rs", $rs);



     }//fin function


$this->set("opcion", $opcion);


}//fin fucntion





function shp100_solicitud_patente_industria_comercio($ir=null){
	if($ir=='si'){
		$this->layout="ajax";
		$this->set('ir','si');
	}else if($ir=='no'){
		$this->layout="pdf";
		$this->set('ir','no');
		$radio1=$this->data['cimp01']['radio_1'];
		$radio2=$this->data['cimp01']['radio_2'];
//		pr($this->data);
		if($radio1==1){
			if($radio2==1){
				$sql=("select * from v_shd100_declaracion_ingreso where ".$this->SQLCA()." order by  numero_solicitud asc");
				$ejecuta=$this->v_shd100_declaracion_ingreso->execute($sql);
			}else if($radio2==2){;
				$sql=("select * from v_shd100_declaracion_ingreso where ".$this->SQLCA()." and numero_patente!='0' order by numero_solicitud asc");
				$ejecuta=$this->v_shd100_declaracion_ingreso->execute($sql);

			}else if($radio2==3){
				$sql=("select * from v_shd100_declaracion_ingreso where ".$this->SQLCA()." and numero_patente='0' order by numero_solicitud asc");
				$ejecuta=$this->v_shd100_declaracion_ingreso->execute($sql);

			}
		}else if($radio1==2){
			if($radio2==1){
				$sql=("select * from v_shd100_declaracion_ingreso where ".$this->SQLCA()." order by rif_cedula asc");
				$ejecuta=$this->v_shd100_declaracion_ingreso->execute($sql);
			}else if($radio2==2){
				$sql=("select * from v_shd100_declaracion_ingreso where ".$this->SQLCA()." and numero_patente!='0' order by rif_cedula asc");
				$ejecuta=$this->v_shd100_declaracion_ingreso->execute($sql);

			}else if($radio2==3){
				$sql=("select * from v_shd100_declaracion_ingreso where ".$this->SQLCA()." and numero_patente='0' order by rif_cedula asc");
				$ejecuta=$this->v_shd100_declaracion_ingreso->execute($sql);

			}
		}else if($radio1==3){
			if($radio2==1){
				$sql=("select * from v_shd100_declaracion_ingreso where ".$this->SQLCA()." order by razon_social_nombres asc");
				$ejecuta=$this->v_shd100_declaracion_ingreso->execute($sql);
			}else if($radio2==2){
				$sql=("select * from v_shd100_declaracion_ingreso where ".$this->SQLCA()." and numero_patente!='0' order by razon_social_nombres asc");
				$ejecuta=$this->v_shd100_declaracion_ingreso->execute($sql);

			}else if($radio2==3){
				$sql=("select * from v_shd100_declaracion_ingreso where ".$this->SQLCA()." and numero_patente='0' order by razon_social_nombres asc");
				$ejecuta=$this->v_shd100_declaracion_ingreso->execute($sql);

			}
		}
//		pr($ejecuta);
		$this->set('datos',$ejecuta);
	}
//shd100_patente  shd001_registro_contribuyentes    shd100_solicitud
}





function shp100_patente_horizontal($var1=null, $var2=null){



    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');

	set_time_limit(0);



     if($var1==1){

           $this->layout = "ajax";
           $opcion = 1;


     }else{

           $this->layout = "pdf";
           $opcion = 2;


           $ordenado = $this->data['reporte3']['ordenado'];


           if($ordenado==1){

           	  $sql = "ORDER BY a.rif_cedula ASC";
           	  $sql = "ORDER BY
              		      a.cod_estado,
						  a.cod_municipio,
						  a.cod_parroquia,
						  a.cod_centro_poblado,
						  a.rif_cedula ASC";

           }else{

              $sql = "ORDER BY
              		      a.cod_estado,
						  a.cod_municipio,
						  a.cod_parroquia,
						  a.cod_centro_poblado,
						  a.razon_social_nombres ASC";

           }//fin else


$rs=$this->v_cfpd05_denominaciones->execute(" SELECT
                  a.rif_cedula,
				  a.personalidad_juridica,
				  a.razon_social_nombres,
				  a.fecha_inscripcion,
				  a.nacionalidad,
				  a.estado_civil,
				  a.profesion,
				  a.cod_pais,
				  a.cod_estado,
				  a.cod_municipio,
				  a.cod_parroquia,
				  a.cod_centro_poblado,
				  a.cod_calle_avenida,
				  a.cod_vereda_edificio,
				  a.numero_vivienda_local,
				  a.telefonos_fijos,
				  a.telefonos_celulares,
				  a.correo_electronico,
				  b.cod_presi,
				  b.cod_entidad,
				  b.cod_tipo_inst,
				  b.cod_inst,
				  b.cod_dep,
				  b.numero_solicitud,
				  b.numero_patente,
				  b.frecuencia_pago,
				  b.monto_mensual,
				  b.pago_todo,
				  b.suspendido,
				  b.rif_ci_cobrador,
				  b.ultimo_ano_facturado,
				  b.ultimo_mes_facturado,
				  b.fecha_ultima_decla,
				  b.ingresos_declarados,
				  b.ultimo_ejercicio_decla,
				  b.periodo_desde,
				  b.periodo_hasta,
				  b.fecha_patente,
				  c.cod_presi,
				  c.cod_entidad,
				  c.cod_tipo_inst,
				  c.cod_inst,
				  c.cod_dep,
				  c.numero_solicitud,
				  c.fecha_solicitud,
				  c.rif_cedula,
				  c.numero_ficha_catastral,
				  c.capital,
				  c.horario_trab_desde,
				  c.horario_trab_hasta,
				  c.tipo_establecimiento,
				  c.tipo_local,
				  c.nacionalidad,
				  c.cedula_identidad,
				  c.nombres_apellidos,
				  c.cod_pais,
				  c.cod_estado,
				  c.cod_municipio,
				  c.cod_parroquia,
				  c.cod_centro,
				  c.cod_vialidad,
				  c.cod_vereda,
				  c.numero_casa_local,
				  c.telefonos_fijos,
				  c.telefonos_celulares,
				  c.correo_electronico,
				  c.fecha_inicio_const,
				  c.fecha_cierre_const,
				  c.fecha_inicio_econo,
				  c.fecha_cierre_economico,
				  c.registro_mercantil,
				  c.tiene_sucursal,
				  c.es_fabricante,
				  c.numero_empleado,
				  c.numero_obreros,
				  c.distancia_bar,
				  c.distancia_hospital,
				  c.distancia_educativo,
				  c.distancia_funeraria,
				  c.distancia_estacion,
				  c.distancia_gubernam,
				  c.tilde_reg_mercantil,
				  c.tilde_fotoco_ci,
				  c.tilde_acta_const,
				  c.tilde_uso_conforme,
				  c.tilde_croquis,
				  c.tilde_bomberos,
				  c.tilde_rif,
				  c.tilde_solvencia,
				  c.tilde_concejo,
				  c.tilde_recibo,
				  c.tilde_planilla,
				  c.tilde_permiso,
				  c.numero_patente

				FROM

				      shd001_registro_contribuyentes a, shd100_patente b, shd100_solicitud c

				WHERE
	                   c.cod_presi            =  ".$cod_presi."          and
					   c.cod_entidad          =  ".$cod_entidad."        and
					   c.cod_tipo_inst        =  ".$cod_tipo_inst."      and
					   c.cod_inst             =  ".$cod_inst."           and
					   c.cod_dep              =  ".$cod_dep."            and
					   b.cod_presi            =  c.cod_presi             and
					   b.cod_entidad          =  c.cod_entidad           and
					   b.cod_tipo_inst        =  c.cod_tipo_inst         and
					   b.cod_inst             =  c.cod_inst              and
					   b.cod_dep              =  c.cod_dep               and
					   a.rif_cedula           =  c.rif_cedula            and
					   b.numero_solicitud     =  c.numero_solicitud      and
	                   b.numero_patente       =  c.numero_patente        and
	                   c.numero_patente      != '0'  ".$sql.";
");




$this->set("rs", $rs);



     }//fin function



$this->set("opcion", $opcion);


}//fin function






function shd100_relacion_contribuyente_detallado($var1=null, $var2=null){
    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$SScoddeporig             =       $this->Session->read('SScoddeporig');
	$SScoddep                 =       $this->Session->read('SScoddep');
	$Modulo                   =       $this->Session->read('Modulo');
	set_time_limit(0);

     if($var1==1){
           $this->layout = "ajax";
           $opcion = 1;
     }else{
           $this->layout = "pdf";
           $opcion = 2;
           $ordenado = $this->data['reporte3']['ordenado'];

           if($ordenado==1){
			//ALFABETICO
           	 $sql = "ORDER BY a.razon_social_nombres ASC";

           }else if($ordenado==2){
//			RIF
              $sql = "ORDER BY a.rif_cedula ASC";

           }else{
//           	RAZON SOCIAL
           		 $sql = "ORDER BY
              		      a.cod_estado,
						  a.cod_municipio,
						  a.cod_parroquia,
						  a.cod_centro_poblado,
						  a.razon_social_nombres ASC";


           }//fin else


$rs=$this->shd001_registro_contribuyentes->execute("
                      SELECT
                          a.rif_cedula,
						  a.personalidad_juridica,
						  a.razon_social_nombres,
						  a.fecha_inscripcion,
						  a.nacionalidad,
						  a.estado_civil,
						  a.profesion,
						  a.cod_pais ,
						  a.cod_estado,
						  a.cod_municipio,
						  a.cod_parroquia,
						  a.cod_centro_poblado,
						  a.cod_calle_avenida,
						  a.cod_vereda_edificio,
						  a.numero_vivienda_local,
						  a.telefonos_fijos,
						  a.telefonos_celulares,
						  a.correo_electronico,
						 (SELECT xye.denominacion FROM cugd01_republica  xye where xye.cod_republica=a.cod_pais GROUP BY xye.denominacion) as  deno_cod_republica,
                         (SELECT xya.denominacion FROM cugd01_estados  xya where xya.cod_republica=a.cod_pais and xya.cod_estado=a.cod_estado GROUP BY xya.denominacion) as  deno_cod_estado,
                         (SELECT xyb.denominacion FROM cugd01_municipios  xyb where xyb.cod_republica=a.cod_pais and xyb.cod_estado=a.cod_estado  and xyb.cod_municipio=a.cod_municipio GROUP BY xyb.denominacion) as  deno_cod_municipio,
						 (SELECT xyc.denominacion FROM cugd01_parroquias   xyc where xyc.cod_republica=a.cod_pais and xyc.cod_estado=a.cod_estado  and xyc.cod_municipio=a.cod_municipio and xyc.cod_parroquia = a.cod_parroquia GROUP BY xyc.denominacion) as  deno_cod_parroquia,
						 (SELECT xyd.denominacion FROM cugd01_centros_poblados xyd where xyd.cod_republica=a.cod_pais and xyd.cod_estado=a.cod_estado  and xyd.cod_municipio=a.cod_municipio and xyd.cod_parroquia = a.cod_parroquia and xyd.cod_centro = a.cod_centro_poblado GROUP BY xyd.denominacion) as  deno_cod_centro,
						 (SELECT xyf.denominacion FROM cugd01_vialidad xyf where xyf.cod_republica=a.cod_pais and xyf.cod_estado=a.cod_estado  and xyf.cod_municipio=a.cod_municipio and xyf.cod_parroquia = a.cod_parroquia and xyf.cod_centro = a.cod_centro_poblado and xyf.cod_vialidad=a.cod_calle_avenida GROUP BY xyf.denominacion) as  deno_cod_vialidad,
						 (SELECT xyg.denominacion FROM cugd01_vereda xyg where xyg.cod_republica=a.cod_pais and xyg.cod_estado=a.cod_estado  and xyg.cod_municipio=a.cod_municipio and xyg.cod_parroquia = a.cod_parroquia and xyg.cod_centro = a.cod_centro_poblado and xyg.cod_vialidad=a.cod_calle_avenida and xyg.cod_vereda=a.cod_vereda_edificio GROUP BY xyg.denominacion) as  deno_cod_vereda

                       FROM
                          shd001_registro_contribuyentes a

                       ".$sql."



");
if($rs!=null){
	$paren=$this->cnmd06_profesiones->findAll();
	$this->set('paren',$paren);
	 $this->set("datos", $rs);
}else{
	 $this->set("datos",null);
}

     }//fin function


$this->set("opcion", $opcion);

}//fin fucntion


function shd100_relacion_contribuyente_resumido($var1=null, $var2=null){
	 if($var1==1){
		$this->layout="ajax";
		 $opcion = 1;
	}else{
		$this->layout="pdf";
		$opcion = 2;
		$radio1=$this->data['reporte3']['radio_1'];
		$radio2=$this->data['reporte3']['radio_2'];
//		pr($this->data);
		if($radio1==1){
			if($radio2==1){
				$sql=("select * from v_shd100_patente where ".$this->SQLCA()." order by  deno_razon asc");
				$ejecuta=$this->v_shd100_patente->execute($sql);
			}else if($radio2==2){;
				$sql=("select * from v_shd100_patente where ".$this->SQLCA()." and numero_patente!='0' order by deno_razon asc");
				$ejecuta=$this->v_shd100_patente->execute($sql);

			}else if($radio2==3){
				$sql=("select * from v_shd100_patente where ".$this->SQLCA()." and numero_patente='0' order by deno_razon asc");
				$ejecuta=$this->v_shd100_patente->execute($sql);

			}
		}else if($radio1==2){
			if($radio2==1){
				$sql=("select * from v_shd100_patente where ".$this->SQLCA()." order by rif_cedula asc");
				$ejecuta=$this->v_shd100_patente->execute($sql);
			}else if($radio2==2){
				$sql=("select * from v_shd100_patente where ".$this->SQLCA()." and numero_patente!='0' order by rif_cedula asc");
				$ejecuta=$this->v_shd100_patente->execute($sql);

			}else if($radio2==3){
				$sql=("select * from v_shd100_patente where ".$this->SQLCA()." and numero_patente='0' order by rif_cedula asc");
				$ejecuta=$this->v_shd100_patente->execute($sql);

			}
		}else if($radio1==3){
			if($radio2==1){
				$sql=("select * from v_shd100_patente where ".$this->SQLCA()." order by fecha_ultima_decla asc");
				$ejecuta=$this->v_shd100_patente->execute($sql);
			}else if($radio2==2){
				$sql=("select * from v_shd100_patente where ".$this->SQLCA()." and numero_patente!='0' order by fecha_ultima_decla asc");
				$ejecuta=$this->v_shd100_patente->execute($sql);

			}else if($radio2==3){
				$sql=("select * from v_shd100_patente where ".$this->SQLCA()." and numero_patente='0' order by fecha_ultima_decla asc");
				$ejecuta=$this->v_shd100_patente->execute($sql);

			}
		}
//		pr($ejecuta);
		if($ejecuta!=null){
			$this->set('datos',$ejecuta);
		}else{
			$this->set('datos',null);
		}
	}

	$this->set("opcion", $opcion);
}



function shd100_actividades_economicas($var1=null, $var2=null){
	 if($var1==1){
		$this->layout="ajax";
		 $opcion = 1;
	}else{
		$this->layout="pdf";
		$opcion = 2;

		$sql=("select * from shd100_actividades where ".$this->SQLCA()." order by cod_actividad asc");
		$ejecuta=$this->shd100_actividades->execute($sql);

//		pr($ejecuta);
		if($ejecuta!=null){
			$this->set('datos',$ejecuta);
		}else{
			$this->set('datos',null);
		}
	}

	$this->set("opcion", $opcion);
}



function shd200_relacion_cobradores($var1=null, $var2=null){
	 if($var1==1){
		$this->layout="ajax";
		 $opcion = 1;
	}else{
		$this->layout="pdf";
		$opcion = 2;

		$ordenado = $this->data['reporte3']['ordenado'];

           if($ordenado==1){
			//ALFABETICO
           	 $sql = "ORDER BY nombre_razon ASC";

           }else if($ordenado==2){
//			RIF
              $sql = "ORDER BY rif_ci ASC";

           }

		$sql=("select * from shd002_cobradores where ".$this->SQLCA()." ".$sql);
		$ejecuta=$this->shd002_cobradores->execute($sql);

//		pr($ejecuta);
		if($ejecuta!=null){
			$this->set('datos',$ejecuta);
		}else{
			$this->set('datos',null);
		}
	}

	$this->set("opcion", $opcion);
}

function shd001_contribuyentes_impuestos($var1=null, $var2=null){
	 if($var1==1){
		$this->layout="ajax";
		 $opcion = 1;
	}else{
		$this->layout="pdf";
		$opcion = 2;

		$ordenado = $this->data['reporte3']['ordenado'];

           if($ordenado==1){
			//ALFABETICO
			 $sql3 = "ORDER BY razon_social_nombres ASC";
           	 $sql = "ORDER BY razon_social_nombres,pertenece_tabla ASC";

           }else if($ordenado==2){
//			RIF
 			  $sql3 = "ORDER BY rif_cedula ASC";
              $sql = "ORDER BY rif_cedula,pertenece_tabla ASC";

           }

		$sql1=("select * from v_shd001_contribuyentes_e_impuestos where ".$this->SQLCA()." ".$sql);
		$ejecuta=$this->v_shd001_contribuyentes_e_impuestos->execute($sql1);

		$sql2=("select * from shd001_registro_contribuyentes ".$sql3);
		$ejecuta2=$this->shd001_registro_contribuyentes->execute($sql2);

//		pr($ejecuta);
		if($ejecuta!=null){
			$this->set('datos', $ejecuta);
			$this->set('datos2',$ejecuta2);
		}else{
			$this->set('datos',null);
			$this->set('datos2',null);
		}
	}

	$this->set("opcion", $opcion);
}








function select_ano_mes_dia ($tipo,$ano=null,$mes=null) {
   $this->layout="ajax";
   $this->set('tipo',$tipo);

   if($tipo=='mes'){
       if(isset($ano) && $ano!=null){
       	   $this->set('ano',$ano);
           $this->set('vmes',$this->v_shd900_cobranza_acumulada_ano_mes_dia->mes($this->SQLCA()." and ano=".$ano));
       }
   }
   if($tipo=='dia'){
       if(isset($mes) && $mes!=null){
       	   $this->set('ano',$ano);
       	   $this->set('mes',$mes);
           $this->set('vdia',$this->v_shd900_cobranza_acumulada_ano_mes_dia->dia($this->SQLCA()." and ano=".$ano." and mes=".$mes));
       }
   }
   if($tipo=='none'){
   	   $this->set('listo',true);
   }

}//fin funcion select_ano_mes_dia





function shd999_relacion_ingresos_resumido($var1=null, $var2=null){
	 if($var1==1){
		$this->layout="ajax";
		 $opcion = 1;

	 	$meses= array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
		$this->concatena($meses, 'meses');
	}else{
		$this->layout="pdf";
		$opcion = 2;

		$filtro=$this->SQLCA();
		if(!empty($this->data['reporte3']['ano'])){
			$filtro.=" and ano=".$this->data['reporte3']['ano'];
			$tabla="v_shd900_cobranza_acumulada_ano";
//			$group="a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.ano,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar,";
//			$group2="a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.ano,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar";
		}

		if(!empty($this->data['reporte3']['mes'])){
			$filtro.=" and mes=".$this->data['reporte3']['mes'];
			$tabla="v_shd900_cobranza_acumulada_ano_mes";
//			$group="a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.ano,a.mes,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar,";
//			$group2="a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.ano,a.mes,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar";
		}




		if(!empty($this->data['reporte3']['dia_day'])){
			$filtro.=" and dia=".$this->data['reporte3']['dia_day'];
			$tabla="v_shd900_cobranza_acumulada_ano_mes_dia";
//			$group="a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.ano,a.mes,a.dia,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar,";
//			$group2="a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.ano,a.mes,a.dia,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar";
		}

			if(empty($this->data['reporte3']['mes']) && empty($this->data['reporte3']['dia_day'])){
				$this->Session->write('tipo',1);
				$this->Session->write('ano_report',$this->data['reporte3']['ano']);
			}else if(!empty($this->data['reporte3']['mes']) && empty($this->data['reporte3']['dia_day'])){
				$this->Session->write('tipo',2);
				$this->Session->write('ano_report',$this->data['reporte3']['ano']);
				$this->Session->write('mes_report',$this->data['reporte3']['mes']);
			}else if(!empty($this->data['reporte3']['mes']) && !empty($this->data['reporte3']['dia_day'])){
				$this->Session->write('tipo',3);
				$this->Session->write('ano_report',$this->data['reporte3']['ano']);
				$this->Session->write('mes_report',$this->data['reporte3']['mes']);
				$this->Session->write('dia_report',mascara($this->data['reporte3']['dia_day'],2));
			}



/*
		$sql=("select
				".$group."
				sum(a.deuda_vigente)::numeric(26,2) as deuda_vigente,
				sum(a.deuda_anterior)::numeric(26,2) as deuda_anterior,
				sum(a.monto_recargo)::numeric(26,2) as monto_recargo,
				sum(a.monto_multa)::numeric(26,2) as monto_multa,
				sum(a.monto_intereses)::numeric(26,2) as monto_intereses,
				sum(a.monto_descuento)::numeric(26,2) as monto_descuento,
				sum(a.cantidad_depositos)::numeric(26,2) as cantidad_depositos,
				sum(a.monto_depositos)::numeric(26,2) as monto_depositos,
				sum(a.cantidad_notas_credito)::numeric(26,2) as cantidad_notas_credito,
				sum(a.monto_notas_credito)::numeric(26,2) as monto_notas_credito,
				sum(a.cantidad_cheques)::numeric(26,2) as cantidad_cheques,
				sum(a.monto_cheques)::numeric(26,2) as monto_cheques,
				sum(a.cantidad_descuento)::numeric(26,2) as cantidad_descuento,
				sum(a.cantidad_pagos_efectivo)::numeric(26,2) as cantidad_pagos_efectivo,
				sum(a.monto_pagos_efectivo)::numeric(26,2) as monto_pagos_efectivo,
				(select b.denominacion from shd003_codigo_ingresos b where b.cod_partida=a.cod_partida and b.cod_generica=a.cod_generica and b.cod_especifica=a.cod_especifica and b.cod_subespec=a.cod_sub_espec and b.cod_auxiliar=a.cod_auxiliar) as denominacion_ingreso
				FROM shd900_cobranza_acumulada a WHERE ".$filtro."
				GROUP BY
				".$group2."
				ORDER BY
				".$group2."" );
*/
		$sql=("select * from ".$tabla." WHERE ".$filtro." order by cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar asc");
		$ejecuta=$this->v_shd001_contribuyentes_e_impuestos->execute($sql);

//		pr($ejecuta);
		if($ejecuta!=null){
			$this->set('datos',$ejecuta);
		}else{
			$this->set('datos',null);
		}
	}

	$this->set("opcion", $opcion);
}

















function shd999_relacion_ingresos_detallado($var1=null, $var2=null){
	 if($var1==1){
		$this->layout="ajax";
		 $opcion = 1;

	 	$meses= array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
		$this->concatena($meses, 'meses');
		$ano_arranque = $this->shd000_arranque->ano($this->condicion());
        $this->set("ano_arranque",$ano_arranque);

         $this->set('ano',$this->v_shd900_cobranza_acumulada_ano_mes_dia->ano($this->SQLCA()));
         $this->set('mes',$this->v_shd900_cobranza_acumulada_ano_mes_dia->mes($this->SQLCA()." and ano=".$ano_arranque));

	}else{
		$this->layout="pdf";
		$opcion = 2;

		$filtro=$this->SQLCA();


		/*
		if(!empty($this->data['reporte_hacienda']['ano'])){
			$filtro.=" and ano=".$this->data['reporte_hacienda']['ano'];
			$tabla="v_shd900_cobranza_acumulada_ano";
//			$group="a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.ano,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar,";
//			$group2="a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.ano,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar";
		}

		if(!empty($this->data['reporte_hacienda']['mes'])){
			$filtro.=" and mes=".$this->data['reporte_hacienda']['mes'];
			$tabla="v_shd900_cobranza_acumulada_ano_mes";
//			$group="a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.ano,a.mes,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar,";
//			$group2="a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.ano,a.mes,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar";
		}

		if(!empty($this->data['reporte_hacienda']['dia'])){
			$filtro.=" and dia=".$this->data['reporte_hacienda']['dia'];
			$tabla="v_shd900_cobranza_acumulada_ano_mes_dia";
//			$group="a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.ano,a.mes,a.dia,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar,";
//			$group2="a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.ano,a.mes,a.dia,a.cod_partida,a.cod_generica,a.cod_especifica,a.cod_sub_espec,a.cod_auxiliar";
		}

			if(empty($this->data['reporte_hacienda']['mes']) && empty($this->data['reporte_hacienda']['dia'])){
				$this->Session->write('tipo',1);
				$this->Session->write('ano_report',$this->data['reporte_hacienda']['ano']);
			}else if(!empty($this->data['reporte_hacienda']['mes']) && empty($this->data['reporte_hacienda']['dia'])){
				$this->Session->write('tipo',2);
				$this->Session->write('ano_report',$this->data['reporte_hacienda']['ano']);
				$this->Session->write('mes_report',$this->data['reporte_hacienda']['mes']);
			}else if(!empty($this->data['reporte_hacienda']['mes']) && !empty($this->data['reporte_hacienda']['dia'])){
				$this->Session->write('tipo',3);
				$this->Session->write('ano_report',$this->data['reporte_hacienda']['ano']);
				$this->Session->write('mes_report',$this->data['reporte_hacienda']['mes']);
				$this->Session->write('dia_report',mascara($this->data['reporte_hacienda']['dia'],2));
			}

			*/

		if(!empty($this->data['reporte_hacienda']['fecha_desde']) && !empty($this->data['reporte_hacienda']['fecha_hasta'])){
       	   $tabla      = "v_shd900_cobranza_acumulada_ano_mes_dia";
       	   $filtro     = $this->SQLCA()." and fecha_documento between '".$this->data['reporte_hacienda']['fecha_desde']."' and '".$this->data['reporte_hacienda']['fecha_hasta']."'  ";
       	   $this->Session->write('fecha_reporte_desde',$this->data['reporte_hacienda']['fecha_desde']);
       	   $this->Session->write('tipo',4);
       	   $this->Session->write('fecha_reporte_hasta',$this->data['reporte_hacienda']['fecha_hasta']);
      	}

/*
		$sql=("select
				".$group."
				sum(a.deuda_vigente)::numeric(26,2) as deuda_vigente,
				sum(a.deuda_anterior)::numeric(26,2) as deuda_anterior,
				sum(a.monto_recargo)::numeric(26,2) as monto_recargo,
				sum(a.monto_multa)::numeric(26,2) as monto_multa,
				sum(a.monto_intereses)::numeric(26,2) as monto_intereses,
				sum(a.monto_descuento)::numeric(26,2) as monto_descuento,
				sum(a.cantidad_depositos)::numeric(26,2) as cantidad_depositos,
				sum(a.monto_depositos)::numeric(26,2) as monto_depositos,
				sum(a.cantidad_notas_credito)::numeric(26,2) as cantidad_notas_credito,
				sum(a.monto_notas_credito)::numeric(26,2) as monto_notas_credito,
				sum(a.cantidad_cheques)::numeric(26,2) as cantidad_cheques,
				sum(a.monto_cheques)::numeric(26,2) as monto_cheques,
				sum(a.cantidad_descuento)::numeric(26,2) as cantidad_descuento,
				sum(a.cantidad_pagos_efectivo)::numeric(26,2) as cantidad_pagos_efectivo,
				sum(a.monto_pagos_efectivo)::numeric(26,2) as monto_pagos_efectivo,
				(select b.denominacion from shd003_codigo_ingresos b where b.cod_partida=a.cod_partida and b.cod_generica=a.cod_generica and b.cod_especifica=a.cod_especifica and b.cod_subespec=a.cod_sub_espec and b.cod_auxiliar=a.cod_auxiliar) as denominacion_ingreso
				FROM shd900_cobranza_acumulada a WHERE ".$filtro."
				GROUP BY
				".$group2."
				ORDER BY
				".$group2."" );
*/
		$sql=("select   cod_presi,
				        cod_entidad,
				        cod_tipo_inst,
				        cod_inst,
				        cod_dep,
				        cod_partida,
				        cod_generica,
				        cod_especifica,
				        cod_sub_espec,
				        cod_auxiliar,
				        sum(deuda_vigente) AS deuda_vigente,
				        sum(deuda_anterior) AS deuda_anterior,
				        sum(monto_recargo) AS monto_recargo,
				        sum(monto_multa) AS monto_multa,
				        sum(monto_intereses) AS monto_intereses,
				        sum(monto_descuento) AS monto_descuento,
				        sum(cantidad_depositos) AS cantidad_depositos,
				        sum(monto_depositos) AS monto_depositos,
				        sum(cantidad_notas_credito) AS cantidad_notas_credito,
				        sum(monto_notas_credito) AS monto_notas_credito,
				        sum(cantidad_cheques) AS cantidad_cheques,
				        sum(monto_cheques) AS monto_cheques,
				        sum(cantidad_descuento) AS cantidad_descuento,
				        sum(cantidad_pagos_efectivo) AS cantidad_pagos_efectivo,
				        sum(monto_pagos_efectivo) AS monto_pagos_efectivo,
				        denominacion_ingreso

         from ".$tabla."  WHERE ".$filtro."

              GROUP BY cod_presi,
			           cod_entidad,
			           cod_tipo_inst,
			           cod_inst,
			           cod_dep,
			           cod_partida,
			           cod_generica,
			           cod_especifica,
			           cod_sub_espec,
			           cod_auxiliar,
			           denominacion_ingreso    order by cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar asc ");

		$ejecuta=$this->v_shd001_contribuyentes_e_impuestos->execute($sql);

//		pr($ejecuta);
		if($ejecuta!=null){
			$this->set('datos',$ejecuta);
		}else{
			$this->set('datos',null);
		}
	}

	$this->set("opcion", $opcion);
}







function relacion_ingresos_resumido ($tipo) {
   $this->layout="ajax";
   $this->set('ano',$this->v_shd900_cobranza_acumulada_ano_mes_dia->ano($this->SQLCA()));
   $this->set('mes',$this->v_shd900_cobranza_acumulada_ano_mes_dia->mes($this->SQLCA()." and ano=".$this->shd000_arranque->ano($this->condicion())) );
   $this->set('tipo',$tipo);
   $this->set("ano_arranque", $this->shd000_arranque->ano($this->condicion()));
   if($tipo=='pdf'){
   	$ano  = "";
   	$mes  = "";
   	$dia  = "";
       extract($this->data['reporte_hacienda']);
       $this->set('data',$this->data['reporte_hacienda']);
       if($ano!='' && $mes=='' && $dia==''){
           $modelo ="v_shd900_cobranza_acumulada_ano";
           $this->set('modelo',$modelo);
           $condicion = $this->SQLCA()." and ano = ".$ano;
           $this->set('opcion',1);
       }else if($ano!='' && $mes!='' && $dia==''){
           $modelo ="v_shd900_cobranza_acumulada_ano_mes";
           $this->set('modelo',$modelo);
           $condicion = $this->SQLCA()." and ano = ".$ano." and mes = ".$mes;
           $this->set('opcion',2);
       }else if($ano!='' && $mes!='' && $dia!=''){
           $modelo ="v_shd900_cobranza_acumulada_ano_mes_dia";
           $this->set('modelo',$modelo);
           $condicion = $this->SQLCA()." and ano = ".$ano." and mes = ".$mes." and dia=".$dia;
           $this->set('opcion',3);
       }



       	if(!empty($this->data['reporte_hacienda']['fecha_desde']) && !empty($this->data['reporte_hacienda']['fecha_hasta'])){
       	   $modelo ="v_shd900_cobranza_acumulada_ano_mes_dia";
           $this->set('modelo',$modelo);
           $condicion = $this->SQLCA();
           $this->set('opcion',3);
           $condicion .= " and fecha_documento between '".$this->data['reporte_hacienda']['fecha_desde']."' and '".$this->data['reporte_hacienda']['fecha_hasta']."'  ";
      	   $this->set('opcion',4);
      	   $this->Session->write('fecha_reporte_desde',$this->data['reporte_hacienda']['fecha_desde']);
       	   $this->Session->write('fecha_reporte_hasta',$this->data['reporte_hacienda']['fecha_hasta']);
      	}



		$sql=("select   cod_presi,
				        cod_entidad,
				        cod_tipo_inst,
				        cod_inst,
				        cod_dep,
				        cod_partida,
				        cod_generica,
				        cod_especifica,
				        cod_sub_espec,
				        cod_auxiliar,
				        sum(deuda_vigente) AS deuda_vigente,
				        sum(deuda_anterior) AS deuda_anterior,
				        sum(monto_recargo) AS monto_recargo,
				        sum(monto_multa) AS monto_multa,
				        sum(monto_intereses) AS monto_intereses,
				        sum(monto_descuento) AS monto_descuento,
				        sum(cantidad_depositos) AS cantidad_depositos,
				        sum(monto_depositos) AS monto_depositos,
				        sum(cantidad_notas_credito) AS cantidad_notas_credito,
				        sum(monto_notas_credito) AS monto_notas_credito,
				        sum(cantidad_cheques) AS cantidad_cheques,
				        sum(monto_cheques) AS monto_cheques,
				        sum(cantidad_descuento) AS cantidad_descuento,
				        sum(cantidad_pagos_efectivo) AS cantidad_pagos_efectivo,
				        sum(monto_pagos_efectivo) AS monto_pagos_efectivo,
				        denominacion_ingreso

         from ".$modelo."  WHERE ".$condicion."

              GROUP BY cod_presi,
			           cod_entidad,
			           cod_tipo_inst,
			           cod_inst,
			           cod_dep,
			           cod_partida,
			           cod_generica,
			           cod_especifica,
			           cod_sub_espec,
			           cod_auxiliar,
			           denominacion_ingreso    order by cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar asc ");

		$datos=$this->v_shd001_contribuyentes_e_impuestos->execute($sql);

//       $datos = $this->$modelo->findAll($condicion,null,'cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC');

        $this->set('datos',$datos);
   }

}//fin funcion relacion_ingresos_resumido











function relacion_ingresos_diarios($tipo){


   $this->layout="ajax";
   $this->set('ano',$this->v_shd900_cobranza_acumulada_ano_mes_dia->ano($this->SQLCA()));
   $this->set('mes',$this->v_shd900_cobranza_acumulada_ano_mes_dia->mes($this->SQLCA()." and ano=".$this->shd000_arranque->ano($this->condicion())) );
   $this->set('tipo',$tipo);
   $this->set("ano_arranque", $this->shd000_arranque->ano($this->condicion()));
   if($tipo=='pdf'){

   	$where = "";

   	if($this->data['reporte_hacienda']['condicion_actividad']!="3"){
       $where .= " and condicion_documento='".$this->data['reporte_hacienda']['condicion_actividad']."'  ";
   	}


   	if(!empty($this->data['reporte_hacienda']['fecha_desde']) && !empty($this->data['reporte_hacienda']['fecha_hasta'])){
       $where .= " and fecha_comprobante between '".$this->data['reporte_hacienda']['fecha_desde']."' and '".$this->data['reporte_hacienda']['fecha_hasta']."'  ";
   	}





   	    $sql     = "select * from v_shd900_cobranza_diaria_aux WHERE ".$this->SQLCA().$where." order by cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar, ano_comprobante, numero_comprobante asc";
		$ejecuta = $this->v_shd001_contribuyentes_e_impuestos->execute($sql);
		$this->set('datos',$ejecuta);

   }


}//fin funtion














function ventana_2($var1=null, $pagina=null, $pista=null){

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


					$condicion =" (".$this->busca_separado(array("rif_cedula","razon_social_nombres"), $pista).")  ";


									            $Tfilas=$this->shd001_registro_contribuyentes->findCount($condicion);
											        if($Tfilas!=0){
											        	$Tfilas=(int)ceil($Tfilas/50);
											        	$this->set('total_paginas',$Tfilas);
														$this->set('pagina_actual',$pagina);
														$this->set('pag_cant',$pagina.'/'.$Tfilas);
														$this->set('ultimo',$Tfilas);
											     	    $datos_filas=$this->shd001_registro_contribuyentes->findAll($condicion,null,"rif_cedula, razon_social_nombres ASC",50,$pagina,null);
												        $this->set("datos",$datos_filas);
												        $this->set('siguiente',$pagina+1);
														$this->set('anterior',$pagina-1);
														$this->bt_nav($Tfilas,$pagina);
											        }else{
											        	$this->set("datos",'');
											        }

					$this->set("pista",$pista);

 }else  if($var1==3){
         $datos_filas=$this->shd001_registro_contribuyentes->findAll("rif_cedula='".$pagina."'");
         $this->set("valor_seleccionado",$pagina);
         $this->set("nombre_rif",$datos_filas[0]["shd001_registro_contribuyentes"]["razon_social_nombres"]);


 }//fin else

$this->set("opcion",$var1);

}//fin function





function shd900_estado_cuenta($var1=null){


       if($var1==1){ $this->layout="ajax";
  		 $tipo_impuesto=array('1'=>'PATENTE DE INDUSTRIA Y COMERCIO','2'=>'VEHÍCULOS','3'=>'PROPAGANDA COMERCIAL','4'=>'INMUEBLES URBANOS','5'=>'ASEO DOMICILIARIO','6'=>'ARRENDAMIENTO DE TIERRAS','7'=>'CRÉDITOS DE VIVIENDA');
		$this->set('tipo_impuesto',$tipo_impuesto);

		$ver=$this->shd000_arranque->execute("select * from shd000_arranque where ".$this->SQLCA());
		if($ver!=null){
			$this->set('ano1',$ver[0][0]['ano_arranque']);
		}else{
			$this->set('ano1','');
		}


}else if($var1==2){  $this->layout="pdf";

		 $sql='';

		 if(!empty($this->data['reporte_hacienda']['ano']) && !empty($this->data['reporte_hacienda']['rif_ci']) && !empty($this->data['reporte_hacienda']['codigo_impuesto'])){
	         $ano = $this->data['reporte_hacienda']['ano'];
	         $rif_ci = $this->data['reporte_hacienda']['rif_ci'];
	         $codigo_impuesto = $this->data['reporte_hacienda']['codigo_impuesto'];

	         $sql.=" and ano='$ano' and rif_cedula='".$rif_ci."' and cod_ingreso='$codigo_impuesto'";
	         $order=" ORDER BY rif_cedula,ano,mes ASC";


			$sql1=("select * from v_shd900_estado_cuentas where ".$this->SQLCA().$sql.$order);
			$ejecuta=$this->v_shd001_contribuyentes_e_impuestos->execute($sql1);

			$this->set('codigo_impuesto_1',$codigo_impuesto);

	        if($ejecuta!=null){
				$this->set('datos',$ejecuta);
			}else{
				$this->set('datos',null);
			}
		}else{
			$this->set('datos',null);
		}

}//fin function



$this->set("opcion", $var1);


}//fin function






function shd900_planilla_liquidacion_vehiculo($var1=null){


       if($var1==1){ $this->layout="ajax";

  		 $tipo_impuesto=array('1'=>'PATENTE DE INDUSTRIA Y COMERCIO','2'=>'VEHÍCULOS','3'=>'PROPAGANDA COMERCIAL','4'=>'INMUEBLES URBANOS','5'=>'ASEO DOMICILIARIO','6'=>'ARRENDAMIENTO DE TIERRAS','7'=>'CRÉDITOS DE VIVIENDA');
		$this->set('tipo_impuesto',$tipo_impuesto);

		$ver=$this->shd000_arranque->execute("select * from shd000_arranque where ".$this->SQLCA());
		if($ver!=null){
			$this->set('ano1',$ver[0][0]['ano_arranque']);
			$this->set('mes',$ver[0][0]['mes_arranque']);
		}else{
			$this->set('ano1','');
			$this->set('mes','');
		}


}else if($var1==2){  $this->layout="pdf";
		 $this->Session->delete('mes_report');
       	 $this->Session->delete('ano_report');

		 $sql='';

		 if(!empty($this->data['reporte_hacienda']['ano'])){
	         $ano = $this->data['reporte_hacienda']['ano'];
	        $tipo_busqueda = $this->data['reporte_hacienda']['tipo_busqueda'];

			  $sql=" and ano='$ano'";

			 if(!empty($this->data['reporte_hacienda']['mes'])){
			 	 $mes = $this->data['reporte_hacienda']['mes'];
			 	  $sql.=" and mes='".$mes."'";
			 	  $this->Session->write('mes_report',$mes);
			 }

	         if($tipo_busqueda==1){
	         	$tipo=2;
	         }else{
				$tipo=1;
	         }
 			 $sql.=" and cancelado='$tipo'";
	         $this->Session->write('ano_report',$ano);
	         $this->Session->write('tipo',$tipo_busqueda);

	         $order=" ORDER BY ano,mes,numero_planilla,rif_cedula,placa_vehiculo ASC";
			$filtro=$this->SQLCA();
//			a.cod_presi=b.cod_presi and a.cod_entidad=b.cod_entidad and a.cod_tipo_inst=b.cod_tipo_inst and a.cod_inst=b.cod_inst and a.cod_dep=b.cod_dep
			$sql1="select * from v_shd900_planilla_liquidacion_previa_vehiculo where ".$this->SQLCA()." and fecha_emision!='1900-01-01' and cancelado=".$tipo.$sql;

			$sql2=($sql1);
			$ejecuta=$this->v_shd001_contribuyentes_e_impuestos->execute($sql2);

	        if($ejecuta!=null){
				$this->set('datos',$ejecuta);
			}else{
				$this->set('datos',null);
			}
		}else{
			$this->set('datos',null);
		}

}//fin function



$this->set("opcion", $var1);


}//fin function




function shd900_planilla_liquidacion_previa_propiedad($var1=null){


       if($var1==1){ $this->layout="ajax";
       	 $this->Session->delete('mes_report');
       	 $this->Session->delete('ano_report');
  		 $tipo_impuesto=array('1'=>'PATENTE DE INDUSTRIA Y COMERCIO','2'=>'VEHÍCULOS','3'=>'PROPAGANDA COMERCIAL','4'=>'INMUEBLES URBANOS','5'=>'ASEO DOMICILIARIO','6'=>'ARRENDAMIENTO DE TIERRAS','7'=>'CRÉDITOS DE VIVIENDA');
		$this->set('tipo_impuesto',$tipo_impuesto);

		$ver=$this->shd000_arranque->execute("select * from shd000_arranque where ".$this->SQLCA());
		if($ver!=null){
			$this->set('ano1',$ver[0][0]['ano_arranque']);
			$this->set('mes',$ver[0][0]['mes_arranque']);
		}else{
			$this->set('ano1','');
			$this->set('mes','');
		}


}else if($var1==2){  $this->layout="pdf";
		 $this->Session->delete('mes_report');
       	 $this->Session->delete('ano_report');
		 $sql='';

		 if(!empty($this->data['reporte_hacienda']['ano'])){
	         $ano = $this->data['reporte_hacienda']['ano'];

	         $tipo_busqueda = $this->data['reporte_hacienda']['tipo_busqueda'];

			  $sql=" and ano='$ano'";

			 if(!empty($this->data['reporte_hacienda']['mes'])){
			 	 $mes = $this->data['reporte_hacienda']['mes'];
			 	  $sql.=" and mes='".$mes."'";
			 	  $this->Session->write('mes_report',$mes);
			 }

	         if($tipo_busqueda==1){
	         	$tipo=2;
	         }else{
				$tipo=1;
	         }
 			 $sql.=" and cancelado='$tipo'";
	         $this->Session->write('ano_report',$ano);
	         $this->Session->write('tipo',$tipo_busqueda);

	         $order=" ORDER BY ano,mes,numero_planilla,rif_cedula ASC";
//			a.cod_presi=b.cod_presi and a.cod_entidad=b.cod_entidad and a.cod_tipo_inst=b.cod_tipo_inst and a.cod_inst=b.cod_inst and a.cod_dep=b.cod_dep
			$sql1="select * from v_shd900_planilla_liquidacion_previa_propiedad where ".$this->SQLCA()." and fecha_emision!='1900-01-01' and cancelado=".$tipo.$sql;

			$sql2=($sql1);
			$ejecuta=$this->v_shd001_contribuyentes_e_impuestos->execute($sql2);

	        if($ejecuta!=null){
				$this->set('datos',$ejecuta);
			}else{
				$this->set('datos',null);
			}
		}else{
			$this->set('datos',null);
		}

}//fin function



$this->set("opcion", $var1);


}//fin function





function shd900_planilla_liquidacion_previa_arrendamiento($var1=null){


       if($var1==1){ $this->layout="ajax";
       	 $this->Session->delete('mes_report');
       	 $this->Session->delete('ano_report');
  		 $tipo_impuesto=array('1'=>'PATENTE DE INDUSTRIA Y COMERCIO','2'=>'VEHÍCULOS','3'=>'PROPAGANDA COMERCIAL','4'=>'INMUEBLES URBANOS','5'=>'ASEO DOMICILIARIO','6'=>'ARRENDAMIENTO DE TIERRAS','7'=>'CRÉDITOS DE VIVIENDA');
		$this->set('tipo_impuesto',$tipo_impuesto);

		$ver=$this->shd000_arranque->execute("select * from shd000_arranque where ".$this->SQLCA());
		if($ver!=null){
			$this->set('ano1',$ver[0][0]['ano_arranque']);
			$this->set('mes',$ver[0][0]['mes_arranque']);
		}else{
			$this->set('ano1','');
			$this->set('mes','');
		}


}else if($var1==2){  $this->layout="pdf";
		 $this->Session->delete('mes_report');
       	 $this->Session->delete('ano_report');
		 $sql='';

		 if(!empty($this->data['reporte_hacienda']['ano'])){
	         $ano = $this->data['reporte_hacienda']['ano'];

	         $tipo_busqueda = $this->data['reporte_hacienda']['tipo_busqueda'];

			  $sql=" and ano='$ano'";

			 if(!empty($this->data['reporte_hacienda']['mes'])){
			 	 $mes = $this->data['reporte_hacienda']['mes'];
			 	  $sql.=" and mes='".$mes."'";
			 	  $this->Session->write('mes_report',$mes);
			 }

	         if($tipo_busqueda==1){
	         	$tipo=2;
	         }else{
				$tipo=1;
	         }
 			 $sql.=" and cancelado='$tipo'";
	         $this->Session->write('ano_report',$ano);
	         $this->Session->write('tipo',$tipo_busqueda);

	         $order=" ORDER BY ano,mes,numero_planilla,rif_cedula ASC";
//			a.cod_presi=b.cod_presi and a.cod_entidad=b.cod_entidad and a.cod_tipo_inst=b.cod_tipo_inst and a.cod_inst=b.cod_inst and a.cod_dep=b.cod_dep
			$sql1="select * from v_shd900_planilla_liquidacion_previa_arrendamiento where ".$this->SQLCA()." and fecha_emision!='1900-01-01' and cancelado=".$tipo.$sql;

			$sql2=($sql1);
			$ejecuta=$this->v_shd001_contribuyentes_e_impuestos->execute($sql2);

	        if($ejecuta!=null){
				$this->set('datos',$ejecuta);
			}else{
				$this->set('datos',null);
			}
		}else{
			$this->set('datos',null);
		}

}//fin function



$this->set("opcion", $var1);


}//fin function




function shd900_planilla_liquidacion_previa_credito_vivienda($var1=null){


       if($var1==1){ $this->layout="ajax";
       	 $this->Session->delete('mes_report');
       	 $this->Session->delete('ano_report');
  		 $tipo_impuesto=array('1'=>'PATENTE DE INDUSTRIA Y COMERCIO','2'=>'VEHÍCULOS','3'=>'PROPAGANDA COMERCIAL','4'=>'INMUEBLES URBANOS','5'=>'ASEO DOMICILIARIO','6'=>'ARRENDAMIENTO DE TIERRAS','7'=>'CRÉDITOS DE VIVIENDA');
		$this->set('tipo_impuesto',$tipo_impuesto);

		$ver=$this->shd000_arranque->execute("select * from shd000_arranque where ".$this->SQLCA());
		if($ver!=null){
			$this->set('ano1',$ver[0][0]['ano_arranque']);
			$this->set('mes',$ver[0][0]['mes_arranque']);
		}else{
			$this->set('ano1','');
			$this->set('mes','');
		}


}else if($var1==2){  $this->layout="pdf";
		 $this->Session->delete('mes_report');
       	 $this->Session->delete('ano_report');
		 $sql='';

		 if(!empty($this->data['reporte_hacienda']['ano'])){
	         $ano = $this->data['reporte_hacienda']['ano'];

	         $tipo_busqueda = $this->data['reporte_hacienda']['tipo_busqueda'];

			  $sql=" and ano='$ano'";

			 if(!empty($this->data['reporte_hacienda']['mes'])){
			 	 $mes = $this->data['reporte_hacienda']['mes'];
			 	  $sql.=" and mes='".$mes."'";
			 	  $this->Session->write('mes_report',$mes);
			 }

	         if($tipo_busqueda==1){
	         	$tipo=2;
	         }else{
				$tipo=1;
	         }
 			 $sql.=" and cancelado='$tipo'";
	         $this->Session->write('ano_report',$ano);
	         $this->Session->write('tipo',$tipo_busqueda);

	         $order=" ORDER BY ano,mes,numero_planilla,rif_cedula ASC";
//			a.cod_presi=b.cod_presi and a.cod_entidad=b.cod_entidad and a.cod_tipo_inst=b.cod_tipo_inst and a.cod_inst=b.cod_inst and a.cod_dep=b.cod_dep
			$sql1="select * from v_shd900_planilla_liquidacion_previa_credito_vivienda where ".$this->SQLCA()." and fecha_emision!='1900-01-01' and cancelado=".$tipo.$sql;

			$sql2=($sql1);
			$ejecuta=$this->v_shd001_contribuyentes_e_impuestos->execute($sql2);

	        if($ejecuta!=null){
				$this->set('datos',$ejecuta);
			}else{
				$this->set('datos',null);
			}
		}else{
			$this->set('datos',null);
		}

}//fin function



$this->set("opcion", $var1);


}//fin function



function shd900_planilla_liquidacion_previa_patente($var1=null){


       if($var1==1){ $this->layout="ajax";
       	 $this->Session->delete('mes_report');
       	 $this->Session->delete('ano_report');
  		 $tipo_impuesto=array('1'=>'PATENTE DE INDUSTRIA Y COMERCIO','2'=>'VEHÍCULOS','3'=>'PROPAGANDA COMERCIAL','4'=>'INMUEBLES URBANOS','5'=>'ASEO DOMICILIARIO','6'=>'ARRENDAMIENTO DE TIERRAS','7'=>'CRÉDITOS DE VIVIENDA');
		$this->set('tipo_impuesto',$tipo_impuesto);

		$ver=$this->shd000_arranque->execute("select * from shd000_arranque where ".$this->SQLCA());
		if($ver!=null){
			$this->set('ano1',$ver[0][0]['ano_arranque']);
			$this->set('mes',$ver[0][0]['mes_arranque']);
		}else{
			$this->set('ano1','');
			$this->set('mes','');
		}


}else if($var1==2){  $this->layout="pdf";
		 $this->Session->delete('mes_report');
       	 $this->Session->delete('ano_report');
		 $sql='';

		 if(!empty($this->data['reporte_hacienda']['ano'])){
	         $ano = $this->data['reporte_hacienda']['ano'];

	         $tipo_busqueda = $this->data['reporte_hacienda']['tipo_busqueda'];

			  $sql=" and ano='$ano'";

			 if(!empty($this->data['reporte_hacienda']['mes'])){
			 	 $mes = $this->data['reporte_hacienda']['mes'];
			 	  $sql.=" and mes='".$mes."'";
			 	  $this->Session->write('mes_report',$mes);
			 }

	         if($tipo_busqueda==1){
	         	$tipo=2;
	         }else{
				$tipo=1;
	         }
 			 $sql.=" and cancelado='$tipo'";
	         $this->Session->write('ano_report',$ano);
	         $this->Session->write('tipo',$tipo_busqueda);

	         $order=" ORDER BY ano,mes,numero_planilla,rif_cedula ASC";
//			a.cod_presi=b.cod_presi and a.cod_entidad=b.cod_entidad and a.cod_tipo_inst=b.cod_tipo_inst and a.cod_inst=b.cod_inst and a.cod_dep=b.cod_dep
			$sql1="select * from v_shd900_planilla_liquidacion_previa_patente where ".$this->SQLCA()." and fecha_emision!='1900-01-01' and cancelado=".$tipo.$sql;

			$sql2=($sql1);
			$ejecuta=$this->v_shd001_contribuyentes_e_impuestos->execute($sql2);

	        if($ejecuta!=null){
				$this->set('datos',$ejecuta);
			}else{
				$this->set('datos',null);
			}
		}else{
			$this->set('datos',null);
		}

}//fin function



$this->set("opcion", $var1);


}//fin function




function shd900_planilla_liquidacion_previa_aseo($var1=null){


       if($var1==1){ $this->layout="ajax";
       	 $this->Session->delete('mes_report');
       	 $this->Session->delete('ano_report');
  		 $tipo_impuesto=array('1'=>'PATENTE DE PATENTE DE INDUSTRIA Y COMERCIO','2'=>'VEHÍCULOS','3'=>'PROPAGANDA COMERCIAL','4'=>'INMUEBLES URBANOS','5'=>'ASEO DOMICILIARIO','6'=>'ARRENDAMIENTO DE TIERRAS','7'=>'CRÉDITOS DE VIVIENDA');
		$this->set('tipo_impuesto',$tipo_impuesto);

		$ver=$this->shd000_arranque->execute("select * from shd000_arranque where ".$this->SQLCA());
		if($ver!=null){
			$this->set('ano1',$ver[0][0]['ano_arranque']);
			$this->set('mes',$ver[0][0]['mes_arranque']);
		}else{
			$this->set('ano1','');
			$this->set('mes','');
		}


}else if($var1==2){  $this->layout="pdf";
		 $this->Session->delete('mes_report');
       	 $this->Session->delete('ano_report');
		 $sql='';

		 if(!empty($this->data['reporte_hacienda']['ano'])){
	         $ano = $this->data['reporte_hacienda']['ano'];

	         $tipo_busqueda = $this->data['reporte_hacienda']['tipo_busqueda'];

			  $sql=" and ano='$ano'";

			 if(!empty($this->data['reporte_hacienda']['mes'])){
			 	 $mes = $this->data['reporte_hacienda']['mes'];
			 	  $sql.=" and mes='".$mes."'";
			 	  $this->Session->write('mes_report',$mes);
			 }

	         if($tipo_busqueda==1){
	         	$tipo=2;
	         }else{
				$tipo=1;
	         }
 			 $sql.=" and cancelado='$tipo'";
	         $this->Session->write('ano_report',$ano);
	         $this->Session->write('tipo',$tipo_busqueda);

	         $order=" ORDER BY ano,mes,numero_planilla,rif_cedula ASC";
//			a.cod_presi=b.cod_presi and a.cod_entidad=b.cod_entidad and a.cod_tipo_inst=b.cod_tipo_inst and a.cod_inst=b.cod_inst and a.cod_dep=b.cod_dep
			$sql1="select * from v_shd900_planilla_liquidacion_previa_aseo where ".$this->SQLCA()." and fecha_emision!='1900-01-01' and cancelado=".$tipo.$sql;

			$sql2=($sql1);
			$ejecuta=$this->v_shd001_contribuyentes_e_impuestos->execute($sql2);

	        if($ejecuta!=null){
				$this->set('datos',$ejecuta);
			}else{
				$this->set('datos',null);
			}
		}else{
			$this->set('datos',null);
		}

}//fin function



$this->set("opcion", $var1);


}//fin function




function shd900_planilla_liquidacion_previa_propaganda($var1=null){


       if($var1==1){ $this->layout="ajax";
       	 $this->Session->delete('mes_report');
       	 $this->Session->delete('ano_report');
  		 $tipo_impuesto=array('1'=>'PATENTE DE INDUSTRIA Y COMERCIO','2'=>'VEHÍCULOS','3'=>'PROPAGANDA COMERCIAL','4'=>'INMUEBLES URBANOS','5'=>'ASEO DOMICILIARIO','6'=>'ARRENDAMIENTO DE TIERRAS','7'=>'CRÉDITOS DE VIVIENDA');
		$this->set('tipo_impuesto',$tipo_impuesto);

		$ver=$this->shd000_arranque->execute("select * from shd000_arranque where ".$this->SQLCA());
		if($ver!=null){
			$this->set('ano1',$ver[0][0]['ano_arranque']);
			$this->set('mes',$ver[0][0]['mes_arranque']);
		}else{
			$this->set('ano1','');
			$this->set('mes','');
		}


}else if($var1==2){  $this->layout="pdf";
		 $this->Session->delete('mes_report');
       	 $this->Session->delete('ano_report');
		 $sql='';

		 if(!empty($this->data['reporte_hacienda']['ano'])){
	         $ano = $this->data['reporte_hacienda']['ano'];

	         $tipo_busqueda = $this->data['reporte_hacienda']['tipo_busqueda'];

			  $sql=" and ano='$ano'";

			 if(!empty($this->data['reporte_hacienda']['mes'])){
			 	 $mes = $this->data['reporte_hacienda']['mes'];
			 	  $sql.=" and mes='".$mes."'";
			 	  $this->Session->write('mes_report',$mes);
			 }

	         if($tipo_busqueda==1){
	         	$tipo=2;
	         }else{
				$tipo=1;
	         }
 			 $sql.=" and cancelado='$tipo'";
	         $this->Session->write('ano_report',$ano);
	         $this->Session->write('tipo',$tipo_busqueda);

	         $order=" ORDER BY ano,mes,numero_planilla,rif_cedula ASC";
//			a.cod_presi=b.cod_presi and a.cod_entidad=b.cod_entidad and a.cod_tipo_inst=b.cod_tipo_inst and a.cod_inst=b.cod_inst and a.cod_dep=b.cod_dep
			$sql1="select * from v_shd900_planilla_liquidacion_previa_propaganda where ".$this->SQLCA()." and fecha_emision!='1900-01-01' and cancelado=".$tipo.$sql;

			$sql2=($sql1);
			$ejecuta=$this->v_shd001_contribuyentes_e_impuestos->execute($sql2);

	        if($ejecuta!=null){
				$this->set('datos',$ejecuta);
			}else{
				$this->set('datos',null);
			}
		}else{
			$this->set('datos',null);
		}

}//fin function



$this->set("opcion", $var1);


}//fin function










function shp100_solicitud_patente_industria_comercio_detallado($ir=null){
	if($ir=='si'){
		$this->layout="ajax";
		$this->set('ir','si');
	}else if($ir=='no'){
		$this->layout="pdf";
		$this->set('ir','no');
		if($this->data['reporte_hacienda']['tipo_busqueda']==2){
			if($this->data['reporte_hacienda']['rif_cedula']==''){
				echo"<script>";
				echo  "history.back(-1)";
				echo"</script>";
			}else{
				$condicion=$this->SQLCA()." and rif_cedula='".$this->data['reporte_hacienda']['rif_cedula']."'";
			}
		}elseif($this->data['reporte_hacienda']['tipo_busqueda']==1){
			$condicion=$this->SQLCA();
		}

		$datos=$this->v_shd100_solicitud->findAll($condicion,null,'numero_solicitud ASC');
		$datos_actividades=$this->v_shd100_solicitud_actividades->findAll($this->SQLCA());
		$this->set('datos_solicitud',$datos);
		$this->set('datos_actividades_solicitud',$datos_actividades);
	}
}


function ventana_reporte_patente_3($var1=null){
	$this->layout="ajax";
	//$url           =  "/reporte_hacienda/ventana_reporte_patente_2/1";
	$url             =  "/reporte_hacienda/buscar_constribuyente/1";
	$width_aux       =  "750px";
	$height_aux      =  "400px";
	$title_aux       =  "Buscar";
	$resizable_aux   =  false;
	$maximizable_aux =  false;
	$minimizable_aux =  false;
	$closable_aux    =  false;
    if($var1==2){
		echo"<script>";
		echo  "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
		echo"</script>";
	}else{
		echo"<script>";
		echo  " Windows.close(document.getElementById('capa_ventana').value)";
		echo"</script>";
	}
}


function ventana_reporte_patente_1($var1=null){
	$this->layout="ajax";
	//$url           =  "/reporte_hacienda/ventana_reporte_patente_2/1";
	$url             =  "/reporte_hacienda/buscar/1";
	$width_aux       =  "750px";
	$height_aux      =  "400px";
	$title_aux       =  "Buscar";
	$resizable_aux   =  false;
	$maximizable_aux =  false;
	$minimizable_aux =  false;
	$closable_aux    =  false;
    if($var1==2){
		echo"<script>";
		echo  "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
		echo"</script>";
	}else{
		echo"<script>";
		echo  " Windows.close(document.getElementById('capa_ventana').value)";
		echo"</script>";
	}
}


function ventana_reporte_patente_2($var1=null, $pagina=null, $pista=null){
	$this->layout="ajax";
	$cod_presi     = $this->Session->read('SScodpresi');
	$cod_entidad   = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst      = $this->Session->read('SScodinst');
	$cod_dep       = $this->Session->read('SScoddep');

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

	}else if($var1==3){

         $this->set("valor_seleccionado",$pagina);

	}
	$this->set("opcion",$var1);
}//fin function


function buscar($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function

function buscar_por_pista2($var1=null, $var2=null, $var3=null){
	$this->layout="ajax";
    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);//echo "(rif_cedula LIKE '%$var2%' or razon_social_nombres LIKE '%$var2%')";
					$Tfilas=$this->v_shd001_registro_contribuyentes->findCount("(rif_cedula LIKE '%$var2%' or razon_social_nombres LIKE '%$var2%')");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_shd001_registro_contribuyentes->findAll("(rif_cedula LIKE '%$var2%' or razon_social_nombres LIKE '%$var2%')",null,"rif_cedula ASC",50,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);//echo "(rif_cedula LIKE '%$var22%' or razon_social_nombres LIKE '%$var22%')";
						$Tfilas=$this->v_shd001_registro_contribuyentes->findCount("(rif_cedula LIKE '%$var22%' or razon_social_nombres LIKE '%$var22%')");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_shd001_registro_contribuyentes->findAll("(rif_cedula LIKE '%$var22%' or razon_social_nombres LIKE '%$var22%')",null,"rif_cedula ASC",50,$pagina,null);
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




function buscar_constribuyente($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function

function buscar_por_pista($var1=null, $var2=null, $var3=null){
$this->layout="ajax";

    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);
					//if(is_int($var2)){$sql   = " ((rif_cedula LIKE '%$var2%') or (razon_social LIKE '%$var2%'))  or   ";}else{ $sql = "";}
					$Tfilas=$this->v_shd100_solicitud->findCount($this->SQLCA()." and (numero_patente='0' or numero_patente is null) and  ((quitar_acentos(rif_cedula) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var2%')))");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->v_shd100_solicitud->findAll($this->SQLCA()." and (numero_patente='0' or numero_patente is null) and  ((quitar_acentos(rif_cedula) LIKE quitar_acentos('%$var2%')) or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var2%')))",null,"rif_cedula ASC",50,1,null);
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
						$Tfilas=$this->v_shd100_solicitud->findCount($this->SQLCA()." and (numero_patente='0' or numero_patente is null) and  ((quitar_acentos(rif_cedula) LIKE quitar_acentos('%$var22%')) or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var22%')))");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->v_shd100_solicitud->findAll($this->SQLCA()." and (numero_patente='0' or numero_patente is null) and  ((quitar_acentos(rif_cedula) LIKE quitar_acentos('%$var22%')) or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var22%')))",null,"rif_cedula ASC",50,$pagina,null);
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


function consulta_contribuyente($numero=null){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');
	$c = "rif_cedula='".$numero."'";
    $veri=$this->v_shd001_registro_contribuyentes->findCount($c);
    if($veri > 0){
      	$datacpcp01=$this->v_shd001_registro_contribuyentes->findAll($c);
      	$this->set('datos',$datacpcp01);
      	$this->set('rif_cedula',$datacpcp01[0]['v_shd001_registro_contribuyentes']['rif_cedula']);
      	//$datos2=$this->v_grilla_constribuyentes->findAll($this->SQLCA()." and rif_cedula='".$numero."'",null,'rif_cedula,tipo ASC',null,null,null);
    	//$this->set('datos2',$datos2);
    }else{
		$this->set('datos',null);
      	$this->set('rif_cedula','');
    }
}



function consulta_persona($numero=null){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');
	$c = "rif_cedula='".$numero."'";
    $veri=$this->v_shd100_solicitud->findCount($c);
    if($veri > 0){
      	$datacpcp01=$this->v_shd100_solicitud->findAll($c);
      	$this->set('datos',$datacpcp01);
      	$this->set('rif_cedula',$datacpcp01[0]['v_shd100_solicitud']['rif_cedula']);
      	//$datos2=$this->v_grilla_constribuyentes->findAll($this->SQLCA()." and rif_cedula='".$numero."'",null,'rif_cedula,tipo ASC',null,null,null);
    	//$this->set('datos2',$datos2);
    }else{
		$this->set('datos',null);
      	$this->set('rif_cedula','');
    }
}



function shp950_relacion_solvencia($var1=null){


       if($var1==1){ $this->layout="ajax";
       	 $this->Session->delete('ano_report');

		$ver=$this->shd000_arranque->execute("select * from shd000_arranque where ".$this->SQLCA());
		if($ver!=null){
			$this->set('ano1',$ver[0][0]['ano_arranque']);
			$this->set('mes',$ver[0][0]['mes_arranque']);
		}else{
			$this->set('ano1','');
			$this->set('mes','');
		}

 $datos  = $this->shd950_solvencia->execute(" SELECT DISTINCT ano FROM shd950_solvencia WHERE ".$this->condicion()." ORDER BY ano ASC");
					if(count($datos)!=0){
						foreach($datos as $n){
							$lista[$n[0]['ano']]=$n[0]['ano'];
					    }
					}else{
						$lista=array('0'=>'No existen datos');
					}
					$this->set("lista_ano", $lista);




}else if($var1==2){  $this->layout="pdf";
       	 $this->Session->delete('ano_report');
		 $sql='';

		 if(!empty($this->data['reporte_hacienda']['ano'])){

	         $ano = $this->data['reporte_hacienda']['ano'];
	         $this->Session->write('ano_report',$ano);

	         $tipo_busqueda = $this->data['reporte_hacienda']['tipo_busqueda'];
	         if($tipo_busqueda==1){
	         	$order=" ORDER BY numero_solvencia ASC";
	         }elseif($tipo_busqueda==2){
				$order=" ORDER BY razon_social_nombres ASC";
	         }else{
	         	$order=" ORDER BY fecha_expedicion ASC";
	         }

			 $sql=$this->SQLCA()." and ano='$ano' ";

   			 $sql1="select * from v_shd950_solvencias where ".$sql.$order;

			$ejecuta=$this->v_shd001_contribuyentes_e_impuestos->execute($sql1);

	        if($ejecuta!=null){
				$this->set('datos',$ejecuta);
			}else{
				$this->set('datos',null);
			}
		}else{
			$this->set('datos',null);
		}

}//fin function

$this->set("opcion", $var1);

}//fin function



function shd300_relacion_contribuyentes_propaganda_comercial($ir=null){
	$this->layout="ajax";
	if($ir=='si'){
		$this->layout="ajax";
		$this->set('ir','si');
	}else if($ir=='no'){
		$this->layout="pdf";
		$this->set('ir','no');

		//echo "Contribuyentes Propaganda Comercial... ".$this->data['reporte_hacienda']['ordenamiento'];

	}

}


function shd200_relacion_contribuyente_vehiculos($ir=null){
	$this->layout="ajax";
	if($ir=='si'){
		$this->layout="ajax";
		$this->set('ir','si');
	}else if($ir=='no'){
		$this->layout="pdf";
		$this->set('ir','no');

		if($this->data['reporte_hacienda']['ordenamiento']==1){
			$order = "nombre_razon ASC";
		}elseif($this->data['reporte_hacienda']['ordenamiento']==2){
			$order = "rif_cedula ASC";
		}else{
			$order = "nombre_razon, rif_cedula ASC";
		}
		// Codigo ingreso para vehiculos = cod_ingreso=2
		$sql="SELECT a.*, b.deuda_vigente FROM v_shd200_vehiculos a,v_shd900_planillas_deuda_cobro_detalles b
where
b.cod_presi=a.cod_presi and
b.cod_entidad=a.cod_entidad and
b.cod_tipo_inst=a.cod_tipo_inst and
b.cod_dep=a.cod_dep and
b.rif_cedula=a.rif_cedula and
b.cod_numero_catastral_placas=a.placa_vehiculo and b.cod_ingreso=2 and cancelado=2
			  ORDER BY ".$order;

		$datos_vehiculos=$this->v_shd200_vehiculos->execute($sql);
		$cant_registros=count($datos_vehiculos);
		$this->set('datos',$datos_vehiculos);
		$this->set('cant_registros',$cant_registros);
	}

}






function shd950_contribuyentes_morosos($var1=null){


       if($var1==1){ $this->layout="ajax";
       	if($_SESSION['utiliza_planillas_liquidacion_previa']==2){
       		$tipo_impuesto=array('2'=>'VEHÍCULOS','3'=>'PROPAGANDA COMERCIAL','4'=>'INMUEBLES URBANOS','5'=>'ASEO DOMICILIARIO','6'=>'ARRENDAMIENTO DE TIERRAS','7'=>'CRÉDITOS DE VIVIENDA');
       	}else{
       		$tipo_impuesto=array('1'=>'PATENTE DE INDUSTRIA Y COMERCIO','2'=>'VEHÍCULOS','3'=>'PROPAGANDA COMERCIAL','4'=>'INMUEBLES URBANOS','5'=>'ASEO DOMICILIARIO','6'=>'ARRENDAMIENTO DE TIERRAS','7'=>'CRÉDITOS DE VIVIENDA');
       	}

		$this->set('tipo_impuesto',$tipo_impuesto);

		$ver=$this->shd000_arranque->execute("select * from shd000_arranque where ".$this->SQLCA());
		if($ver!=null){
			$this->set('ano1',$ver[0][0]['ano_arranque']);
		}else{
			$this->set('ano1','');
		}


}else if($var1==2){  $this->layout="pdf";
			$this->Session->delete('ano_report');



		 $sql='';

		 if(!empty($this->data['reporte_hacienda']['ano']) && !empty($this->data['reporte_hacienda']['codigo_impuesto'])){
	         $ano = $this->data['reporte_hacienda']['ano'];
	         $codigo_impuesto = $this->data['reporte_hacienda']['codigo_impuesto'];
	         $this->set('codigo_impuesto_1',$codigo_impuesto);$tipo_impuesto=array('1'=>'PATENTE DE INDUSTRIA Y COMERCIO','2'=>'VEHÍCULOS','3'=>'PROPAGANDA COMERCIAL','4'=>'INMUEBLES URBANOS','5'=>'ASEO DOMICILIARIO','6'=>'ARRENDAMIENTO DE TIERRAS','7'=>'CRÉDITOS DE VIVIENDA');
	         $tipo_impuesto=array('1'=>'PATENTE DE INDUSTRIA Y COMERCIO','2'=>'VEHÍCULOS','3'=>'PROPAGANDA COMERCIAL','4'=>'INMUEBLES URBANOS','5'=>'ASEO DOMICILIARIO','6'=>'ARRENDAMIENTO DE TIERRAS','7'=>'CRÉDITOS DE VIVIENDA');

	         $this->Session->write('ano_report',$ano);
	         $this->Session->write('titulo_impuesto',$tipo_impuesto[$codigo_impuesto]);

	         $sql.=" and ano='$ano' and cod_ingreso='$codigo_impuesto' and cancelado=2";
	         $order=" ORDER BY ano,mes,rif_cedula ASC";


			$sql1=("select * from v_shd900_estado_cuentas where ".$this->SQLCA().$sql.$order);
			$ejecuta=$this->v_shd001_contribuyentes_e_impuestos->execute($sql1);

	        if($ejecuta!=null){
				$this->set('datos',$ejecuta);
			}else{
				$this->set('datos',null);
			}
		}else{
			$this->set('datos',null);
		}

}//fin function



$this->set("opcion", $var1);


}//fin function





function shp200_relacion_contribuyentes_propiedad($var1=null){


if($var1==1){ $this->layout="ajax";

}else if($var1==2){  $this->layout="pdf";
		 $sql='';


	         $tipo_busqueda = $this->data['reporte_hacienda']['tipo_busqueda'];
	         if($tipo_busqueda==1){
	         	$order=" ORDER BY nombre_razon ASC";
	         }else{
				$order=" ORDER BY rif_cedula ASC";
	         }
//catd02_ficha_datos
			 $filtro=$this->SQLCA();
   			 $sql1="select
					a.cod_presi,
					a.cod_entidad,
					a.cod_tipo_inst,
					a.cod_inst,
					a.cod_dep,
					a.cod_ficha,
					a.rif_cedula,
					a.nombre_razon,
					a.frecuencia_pago,
					a.monto_mensual,
					a.deno_municipio,
					a.deno_parroquia,
					a.deno_centro,
					a.deno_calle,
					a.deno_vereda,
					a.numero_casa,
					(select b.registro_area_terreno from catd02_ficha_datos b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_ficha::character varying=a.cod_ficha) as area_terreno,
					(select b.registro_area_construccion from catd02_ficha_datos b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_ficha::character varying=a.cod_ficha) as area_construccion,
					(select b.terreno_valor_total from catd02_ficha_datos b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_ficha::character varying=a.cod_ficha) as valor_inmueble,
					(select b.nombre_inmueble from catd02_ficha_datos b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_ficha::character varying=a.cod_ficha) as nombre_inmueble
					FROM v_shd400_propiedad a where ".$filtro.$order."";
//(select b.nombre from catd02_ficha_datos b where b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.cod_ficha::character varying=a.cod_ficha) as nombre,
			$ejecuta=$this->v_shd001_contribuyentes_e_impuestos->execute($sql1);

	        if($ejecuta!=null){
				$this->set('datos',$ejecuta);
			}else{
				$this->set('datos',null);
			}


}//fin function



$this->set("opcion", $var1);


}//fin function




function shp200_relacion_contribuyentes_arrendamiento($var1=null){


if($var1==1){ $this->layout="ajax";

}else if($var1==2){  $this->layout="pdf";
		 $sql='';


	         $tipo_busqueda = $this->data['reporte_hacienda']['tipo_busqueda'];
	         if($tipo_busqueda==1){
	         	$order=" ORDER BY nombre_razon ASC";
	         }else{
				$order=" ORDER BY rif_cedula ASC";
	         }


   			 $sql1="select * from v_shd600_contribuyentes_arrendamiento where ".$this->SQLCA().$order;

			$ejecuta=$this->v_shd001_contribuyentes_e_impuestos->execute($sql1);

	        if($ejecuta!=null){
				$this->set('datos',$ejecuta);
			}else{
				$this->set('datos',null);
			}


}//fin function



$this->set("opcion", $var1);


}//fin function






function shd200_parametros_vehiculos($var1=null,$url=null){


if($var1==1){ $this->layout="ajax";

	if($url==1){
		$name='MARCAS DE VEHÍCULOS';
	}else if($url==2){
		$name='MODELOS DE VEHÍCULOS';
	}else if($url==3){
		$name='COLORES DE VEHÍCULOS';
	}else if($url==4){
		$name='CLASES DE VEHÍCULOS';
	}else if($url==5){
		$name='TIPOS DE VEHÍCULOS';
	}else if($url==6){
		$name='USOS DE VEHÍCULOS';
	}

	$this->set('url',$url);
	$this->set('nombre_reporte',$name);
}else if($var1==2){  $this->layout="pdf";
		 $sql='';

		 if($url==1){
			$name='MARCAS DE VEHÍCULOS';
			$campo='codigo_marca';
			$tabla='shd200_vehiculos_marcas';
			$name1='marcas_vehiculo';
		}else if($url==2){
			$name='MODELOS DE VEHÍCULOS';
			$campo='codigo_modelo';
			$tabla='shd200_vehiculos_modelos';
			$name1='modelos_vehiculo';
		}else if($url==3){
			$name='COLORES DE VEHÍCULOS';
			$campo='codigo_color';
			$tabla='shd200_vehiculos_colores';
			$name1='colores_vehiculo';
		}else if($url==4){
			$name='CLASES DE VEHÍCULOS';
			$campo='codigo_clase';
			$tabla='shd200_vehiculos_clases';
			$name1='clases_vehiculo';
		}else if($url==5){
			$name='TIPOS DE VEHÍCULOS';
			$campo='codigo_tipo';
			$tabla='shd200_vehiculos_tipos';
			$name1='tipos_vehiculo';
		}else if($url==6){
			$name='USOS DE VEHÍCULOS';
			$campo='codigo_uso';
			$tabla='shd200_vehiculos_usos';
			$name1='usos_vehiculo';
		}
		$this->Session->write('top_nombre',$name);
		$this->set('campo',$campo);
		$this->set('nombre_reporte',$name1);

			$sql="select * from ".$tabla." order by ".$campo." asc";
			$ejecuta=$this->v_shd001_contribuyentes_e_impuestos->execute($sql);

	        if($ejecuta!=null){
				$this->set('datos',$ejecuta);
			}else{
				$this->set('datos',null);
			}


}//fin function



$this->set("opcion", $var1);


}//fin function






function shd200_vehiculos_clasificacion($var1=null){


if($var1==1){ $this->layout="ajax";

}else if($var1==2){  $this->layout="pdf";
		 $sql='';


   			 $sql1="select * from shd200_vehiculos_clasificacion where ".$this->SQLCA()." order by cod_clasificacion";

			$ejecuta=$this->v_shd001_contribuyentes_e_impuestos->execute($sql1);

	        if($ejecuta!=null){
				$this->set('datos',$ejecuta);
			}else{
				$this->set('datos',null);
			}


}//fin function



$this->set("opcion", $var1);


}//fin function






function shp300_recargos_adicionales($var1=null){


if($var1==1){ $this->layout="ajax";

}else if($var1==2){  $this->layout="pdf";
		 $sql='';


   			 $sql1="select * from shd300_recargos where ".$this->SQLCA()." order by cod_recargo";

			$ejecuta=$this->v_shd001_contribuyentes_e_impuestos->execute($sql1);

	        if($ejecuta!=null){
				$this->set('datos',$ejecuta);
			}else{
				$this->set('datos',null);
			}


}//fin function



$this->set("opcion", $var1);


}//fin function






function shp300_tipo_propaganda($var1=null){


if($var1==1){ $this->layout="ajax";

}else if($var1==2){  $this->layout="pdf";
		 $sql='';


   			 $sql1="select * from shd300_tipo_propaganda where ".$this->SQLCA()." order by cod_tipo";

			$ejecuta=$this->v_shd001_contribuyentes_e_impuestos->execute($sql1);

	        if($ejecuta!=null){
				$this->set('datos',$ejecuta);
			}else{
				$this->set('datos',null);
			}


}//fin function



$this->set("opcion", $var1);


}//fin function








function shp500_clasificacion_servicio_aseo($var1=null){


if($var1==1){ $this->layout="ajax";

}else if($var1==2){  $this->layout="pdf";
		 $sql='';


   			 $sql1="select * from shd500_aseo_clasificacion where ".$this->SQLCA()." order by cod_clasificacion";

			$ejecuta=$this->v_shd001_contribuyentes_e_impuestos->execute($sql1);

	        if($ejecuta!=null){
				$this->set('datos',$ejecuta);
			}else{
				$this->set('datos',null);
			}


}//fin function



$this->set("opcion", $var1);


}//fin function








function shp600_solicitudes_arrendamiento($var1=null){


if($var1==1){ $this->layout="ajax";

}else if($var1==2){  $this->layout="pdf";
		 $sql=$this->SQLCA();

		$tipo_busqueda=$this->data['reporte3']['tipo_busqueda'];
		$orden=$this->data['reporte3']['ordenado'];
		$status=$this->data['reporte3']['status'];
		if($tipo_busqueda==2){
			if(!empty($this->data['reporte3']['ano'])){
				$ano=$this->data['reporte3']['ano'];
				$sql.=" and substr(fecha_solicitud::text,0,5)::integer=".$ano;
			}

		}

		if($orden==1){
			$order=" order by numero_solicitud";
		}else{
			$order=" order by (select b.razon_social_nombres from shd001_registro_contribuyentes b WHERE b.rif_cedula = a.rif_cedula )";
		}

		if($status==1){
			$sql.=" and terreno_vendido is not null ";
		}else{
			$sql.=" and terreno_vendido is null ";
		}


   			 $sql1="SELECT * FROM v_shd600_solicitud_apobacion_arrendamiento WHERE ".$sql.$order;

			$ejecuta=$this->v_shd001_contribuyentes_e_impuestos->execute($sql1);

	        if($ejecuta!=null){
				$this->set('datos',$ejecuta);
			}else{
				$this->set('datos',null);
			}


}//fin function



$this->set("opcion", $var1);


}//fin function



function select_ano($var=null){
	$this->layout="ajax";

	if($var==2){

		$ver=$this->shd000_arranque->execute("select * from shd000_arranque where ".$this->SQLCA());
		if($ver!=null){
			$this->set('ano1',$ver[0][0]['ano_arranque']);
		}else{
			$this->set('ano1','');
		}


		$datos=$this->shd950_solvencia->execute(" SELECT DISTINCT substr(fecha_solicitud::text,0,5)::integer as ano FROM shd600_solicitud_arrendamiento WHERE ".$this->condicion()." ORDER BY substr(fecha_solicitud::text,0,5)::integer ASC");
		if(count($datos)!=0){
			foreach($datos as $n){
				$lista[$n[0]['ano']]=$n[0]['ano'];
		    }
		}else{
			$lista=array('0'=>'No existen datos');
		}
		$this->set("lista_ano", $lista);



	}


	$this->set('ver',$var);


}



function impuesto_planilla_liquidacion_previa($ir=null){
	$this->layout="ajax";
	if($ir=='si'){
		$this->layout="ajax";
		$this->set('ir','si');
		$arranque=$this->shd000_arranque->findAll($this->SQLCA());
		if(count($arranque)>0){
			$ano_arranque=$arranque[0]['shd000_arranque']['ano_arranque'];
			$mes_arranque=$arranque[0]['shd000_arranque']['mes_arranque'];
		}else{
			$ano_arranque=date('Y');
			$mes_arranque=date('m');
		}
		for($i=1950; $i<=2100; $i++){
			$value[]=$i;
			$conten[]=$i;
		}
		$ano=array_combine($value,$conten);
		$mes = array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
		$this->set('ano',$ano);
		$this->set('mes',$mes);
		$this->set('ano_arranque',$ano_arranque);
		$this->set('mes_arranque',$mes_arranque);

	}else if($ir=='no'){
		$this->layout="pdf";
		$this->set('ir','no');
		$array_mes = array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
		$ano=$this->data['reporte_hacienda']['ano'];
		$mes=$this->data['reporte_hacienda']['mes'];
		$tipo_busqueda=$this->data['reporte_hacienda']['tipo_busqueda'];
		$control=0;// Para verificar en la tabla shd000_control_actualizacion si se puede emitir la planilla. Si el campo "condicion=1";

		if($tipo_busqueda==1){
			$condicion_cancelado = " AND a.cancelado = '2'";
			$condicion_rif= "";
			$condicion_actualizacion=$this->SQLCA()." and cod_ingreso='1' and ano_actualizado='$ano' and mes_actualizado='$mes'";
			$control_act=$this->shd000_control_actualizacion->findAll($condicion_actualizacion);
			if(isset($control_act[0]['shd000_control_actualizacion']['condicion'])){
				$control = $control_act[0]['shd000_control_actualizacion']['condicion'];
			}else{
				$control = 888;// Indica que no se encontro ningun registro para esa dependencia, en ese mes y en ese ano.
			}
		}else{
			$rif_cedula = $this->data['reporte_hacienda']['rif_cedula'];
			if($rif_cedula==''){
				echo"<script>";
				echo  "history.back(-1)";
				echo"</script>";
			}else{
				$condicion_cancelado = "";
				$condicion_rif = " AND a.rif_cedula='$rif_cedula'";
				$control=999;// Indica que la plantilla se va a imprimir por rif, por lo que se coloca un numero alto para que no entre a actualizar mas abajo.
			}
		}

		if($control==1 || $control==999){
			$sql_planilla_actual ="SELECT
										a.*,
										b.*
									FROM v_shd900_planillas_deuda_cobro_detalles a, v_shd100_patente b
									WHERE
									a.rif_cedula 	= b.rif_cedula 	AND
									a.cod_ingreso	= '1'	AND
									a.ano		= '$ano'	AND
									a.mes		= '$mes'
									".$condicion_cancelado." ".$condicion_rif." ORDER BY numero_planilla ASC";

			$sql_planilla_anterior ="SELECT
										a.*,
										b.*
									FROM v_shd900_planillas_deuda_cobro_detalles a, v_shd100_patente b
									WHERE
									a.rif_cedula 	= b.rif_cedula 		AND
									a.cod_ingreso	= '1'	AND
									((a.ano = '$ano'  AND a.mes < '$mes') OR a.ano < '$ano')
									".$condicion_cancelado." ".$condicion_rif." ORDER BY numero_planilla ASC";

			$planilla_actual=$this->v_shd900_planillas_deuda_cobro_detalles->execute($sql_planilla_actual);
			//pr($planilla_actual);
			$planilla_anterior=$this->v_shd900_planillas_deuda_cobro_detalles->execute($sql_planilla_anterior);

			$cant_registros=count($planilla_actual);
			if($cant_registros==0){
				$this->set('cant_registros',0);
				$this->set('mensaje','PARA EL MES DE '.$array_mes[$mes].' DEL AÑO '.$ano);// No se encotraron datos.
			}elseif($cant_registros>0){
				if($tipo_busqueda==1 && $control==1){
					$update="UPDATE shd000_control_actualizacion SET condicion=2 WHERE ".$condicion_actualizacion;
					$control=$this->shd000_control_actualizacion->execute($update);
				}
				$this->set('cant_registros',$cant_registros);
				$this->set('planilla_actual',$planilla_actual);
				$this->set('planilla_anterior',$planilla_anterior);
			}

		}else{
			$this->set('cant_registros',0);
			$this->set('control',0);
			if($control==888){
				$this->set('mensaje','No existe número de actualización para el mes de '.$array_mes[$mes].' del año '.$ano);
			}else{
				$this->set('mensaje','El mes de '.$array_mes[$mes].' ya fué emitido no puede volverlo a emitir');
			}
		}
		$this->set('tipo_impuesto',1);// Patente Industria y comercio
	}
}


function vehiculos_planilla_liquidacion_previa($ir=null){
	$this->layout="ajax";
	if($ir=='si'){
		$this->layout="ajax";
		$this->set('ir','si');
		$arranque=$this->shd000_arranque->findAll($this->SQLCA());
		if(count($arranque)>0){
			$ano_arranque=$arranque[0]['shd000_arranque']['ano_arranque'];
			$mes_arranque=$arranque[0]['shd000_arranque']['mes_arranque'];
		}else{
			$ano_arranque=date('Y');
			$mes_arranque=date('m');
		}
		for($i=1950; $i<=2100; $i++){
			$value[]=$i;
			$conten[]=$i;
		}
		$ano=array_combine($value,$conten);
		$mes = array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
		$this->set('ano',$ano);
		$this->set('mes',$mes);
		$this->set('ano_arranque',$ano_arranque);
		$this->set('mes_arranque',$mes_arranque);

	}else if($ir=='no'){
		$this->layout="pdf";
		$this->set('ir','no');
		$array_mes = array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
		$ano=$this->data['reporte_hacienda']['ano'];
		$mes=$this->data['reporte_hacienda']['mes'];
		$tipo_busqueda=$this->data['reporte_hacienda']['tipo_busqueda'];
		$control=0;// Para verificar en la tabla shd000_control_actualizacion si se puede emitir la planilla. Si el campo "condicion=1";

		if($tipo_busqueda==1){
			$condicion_cancelado = " AND a.cancelado = '2'";
			$condicion_rif= "";
			$condicion_actualizacion=$this->SQLCA()." and cod_ingreso='2' and ano_actualizado='$ano' and mes_actualizado='$mes'";
			$control_act=$this->shd000_control_actualizacion->findAll($condicion_actualizacion);
			if(isset($control_act[0]['shd000_control_actualizacion']['condicion'])){
				$control = $control_act[0]['shd000_control_actualizacion']['condicion'];
			}else{
				$control = 888;// Indica que no se encontro ningun registro para esa dependencia, en ese mes y en ese ano.
			}
		}else{
			$rif_cedula = $this->data['reporte_hacienda']['rif_cedula'];
			if($rif_cedula==''){
				echo"<script>";
				echo  "history.back(-1)";
				echo"</script>";
			}else{
				$condicion_cancelado = "";
				$condicion_rif = " AND a.rif_cedula='$rif_cedula'";
				$control=999;// Indica que la plantilla se va a imprimir por rif, por lo que se coloca un numero alto para que no entre a actualizar mas abajo.
			}
		}

		if($control==1 || $control==999){
			$sql_planilla_actual ="SELECT
										a.*,
										b.*,
										(SELECT c.frecuencia_pago FROM v_shd200_vehiculos c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.placa_vehiculo=a.cod_numero_catastral_placas) AS frecuencia_pago,
										(SELECT c.deno_cobrador FROM v_shd200_vehiculos c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.placa_vehiculo=a.cod_numero_catastral_placas) AS deno_cobrador,
										(SELECT c.deno_marca FROM v_shd200_vehiculos c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.placa_vehiculo=a.cod_numero_catastral_placas) AS marca,
										(SELECT c.deno_modelo FROM v_shd200_vehiculos c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.placa_vehiculo=a.cod_numero_catastral_placas) AS modelo,
										(SELECT c.ano_adquisicion FROM v_shd200_vehiculos c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.placa_vehiculo=a.cod_numero_catastral_placas) AS ano_adquisicion,
										(SELECT c.placa_vehiculo FROM v_shd200_vehiculos c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.placa_vehiculo=a.cod_numero_catastral_placas) AS placa_vehiculo
									FROM v_shd900_planillas_deuda_cobro_detalles a, v_shd001_registro_contribuyentes b
									WHERE
									a.rif_cedula	= b.rif_cedula AND
									a.cod_ingreso	= '2'	AND
									a.ano		= '$ano'	AND
									a.mes		= '$mes'
									".$condicion_cancelado." ".$condicion_rif." ORDER BY numero_planilla ASC";

			$sql_planilla_anterior ="SELECT
										a.*,
										b.*,
										(SELECT c.frecuencia_pago FROM v_shd200_vehiculos c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.placa_vehiculo=a.cod_numero_catastral_placas) AS frecuencia_pago,
										(SELECT c.deno_cobrador FROM v_shd200_vehiculos c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.placa_vehiculo=a.cod_numero_catastral_placas) AS deno_cobrador,
										(SELECT c.deno_marca FROM v_shd200_vehiculos c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.placa_vehiculo=a.cod_numero_catastral_placas) AS marca,
										(SELECT c.deno_modelo FROM v_shd200_vehiculos c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.placa_vehiculo=a.cod_numero_catastral_placas) AS modelo,
										(SELECT c.ano_adquisicion FROM v_shd200_vehiculos c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.placa_vehiculo=a.cod_numero_catastral_placas) AS ano_adquisicion,
										(SELECT c.placa_vehiculo FROM v_shd200_vehiculos c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.placa_vehiculo=a.cod_numero_catastral_placas) AS placa_vehiculo
									FROM v_shd900_planillas_deuda_cobro_detalles a, v_shd001_registro_contribuyentes b
									WHERE
									a.rif_cedula	= b.rif_cedula AND
									a.cod_ingreso	= '2'	AND
									((a.ano = '$ano'  AND a.mes < '$mes') OR a.ano < '$ano')
									AND a.cancelado = '2'".$condicion_rif." ORDER BY numero_planilla ASC";

			$planilla_actual=$this->v_shd900_planillas_deuda_cobro_detalles->execute($sql_planilla_actual);
			//pr($planilla_actual);
			$planilla_anterior=$this->v_shd900_planillas_deuda_cobro_detalles->execute($sql_planilla_anterior);

			$cant_registros=count($planilla_actual);
			if($cant_registros==0){
				$this->set('cant_registros',0);
				$this->set('mensaje','PARA EL MES DE '.$array_mes[$mes].' DEL AÑO '.$ano);// No se encotraron datos.
			}elseif($cant_registros>0){
				if($tipo_busqueda==1 && $control==1){
					$update="UPDATE shd000_control_actualizacion SET condicion=2 WHERE ".$condicion_actualizacion;
					$control=$this->shd000_control_actualizacion->execute($update);
				}
				$this->set('cant_registros',$cant_registros);
				$this->set('planilla_actual',$planilla_actual);
				$this->set('planilla_anterior',$planilla_anterior);
			}

		}else{
			$this->set('cant_registros',0);
			$this->set('control',0);
			if($control==888){
				$this->set('mensaje','No existe número de actualización para el mes de '.$array_mes[$mes].' del año '.$ano);
			}else{
				$this->set('mensaje','El mes de '.$array_mes[$mes].' ya fué emitido no puede volverlo a emitir');
			}
		}
		$this->set('tipo_impuesto',2);// Vehiculos
	}
}


function propaganda_comercial_planilla_liquidacion_previa($ir=null){
	$this->layout="ajax";
	if($ir=='si'){
		$this->layout="ajax";
		$this->set('ir','si');
		$arranque=$this->shd000_arranque->findAll($this->SQLCA());
		if(count($arranque)>0){
			$ano_arranque=$arranque[0]['shd000_arranque']['ano_arranque'];
			$mes_arranque=$arranque[0]['shd000_arranque']['mes_arranque'];
		}else{
			$ano_arranque=date('Y');
			$mes_arranque=date('m');
		}
		for($i=1950; $i<=2100; $i++){
			$value[]=$i;
			$conten[]=$i;
		}
		$ano=array_combine($value,$conten);
		$mes = array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
		$this->set('ano',$ano);
		$this->set('mes',$mes);
		$this->set('ano_arranque',$ano_arranque);
		$this->set('mes_arranque',$mes_arranque);

	}else if($ir=='no'){
		$this->layout="pdf";
		$this->set('ir','no');
		$array_mes = array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
		$ano=$this->data['reporte_hacienda']['ano'];
		$mes=$this->data['reporte_hacienda']['mes'];
		$tipo_busqueda=$this->data['reporte_hacienda']['tipo_busqueda'];
		$control=0;// Para verificar en la tabla shd000_control_actualizacion si se puede emitir la planilla. Si el campo "condicion=1";

		if($tipo_busqueda==1){
			$condicion_cancelado = " AND a.cancelado = '2'";
			$condicion_rif= "";
			$condicion_actualizacion=$this->SQLCA()." and cod_ingreso='3' and ano_actualizado='$ano' and mes_actualizado='$mes'";
			$control_act=$this->shd000_control_actualizacion->findAll($condicion_actualizacion);
			if(isset($control_act[0]['shd000_control_actualizacion']['condicion'])){
				$control = $control_act[0]['shd000_control_actualizacion']['condicion'];
			}else{
				$control = 888;// Indica que no se encontro ningun registro para esa dependencia, en ese mes y en ese ano.
			}
		}else{
			$rif_cedula = $this->data['reporte_hacienda']['rif_cedula'];
			if($rif_cedula==''){
				echo"<script>";
				echo  "history.back(-1)";
				echo"</script>";
			}else{
				$condicion_cancelado = "";
				$condicion_rif = " AND a.rif_cedula='$rif_cedula'";
				$control=999;// Indica que la plantilla se va a imprimir por rif, por lo que se coloca un numero alto para que no entre a actualizar mas abajo.
			}
		}

		if($control==1 || $control==999){
			$sql_planilla_actual ="SELECT
										a.*,
										b.*,
										(SELECT c.frecuencia_pago FROM v_shd300_propaganda c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.rif_cedula=a.rif_cedula) AS frecuencia_pago,
										(SELECT c.deno_cobrador FROM v_shd300_propaganda c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.rif_cedula=a.rif_cedula) AS deno_cobrador
									FROM v_shd900_planillas_deuda_cobro_detalles a, v_shd001_registro_contribuyentes b
									WHERE
									a.rif_cedula 	= b.rif_cedula 	AND
									a.cod_ingreso	= '3'	AND
									a.ano		= '$ano'	AND
									a.mes		= '$mes'
									".$condicion_cancelado." ".$condicion_rif." ORDER BY numero_planilla ASC";

			$sql_planilla_anterior ="SELECT
										a.*,
										b.*,
										(SELECT c.frecuencia_pago FROM v_shd300_propaganda c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.rif_cedula=a.rif_cedula) AS frecuencia_pago,
										(SELECT c.deno_cobrador FROM v_shd300_propaganda c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.rif_cedula=a.rif_cedula) AS deno_cobrador
									FROM v_shd900_planillas_deuda_cobro_detalles a, v_shd001_registro_contribuyentes b
									WHERE
									a.rif_cedula 	= b.rif_cedula 		AND
									a.cod_ingreso	= '3'	AND
									((a.ano = '$ano'  AND a.mes < '$mes') OR a.ano < '$ano')
									 AND a.cancelado = '2'".$condicion_rif." ORDER BY numero_planilla ASC";

			$planilla_actual=$this->v_shd900_planillas_deuda_cobro_detalles->execute($sql_planilla_actual);
			//pr($planilla_actual);
			$planilla_anterior=$this->v_shd900_planillas_deuda_cobro_detalles->execute($sql_planilla_anterior);

			$cant_registros=count($planilla_actual);
			if($cant_registros==0){
				$this->set('cant_registros',0);
				$this->set('mensaje','PARA EL MES DE '.$array_mes[$mes].' DEL AÑO '.$ano);// No se encotraron datos.
			}elseif($cant_registros>0){
				if($tipo_busqueda==1 && $control==1){
					$update="UPDATE shd000_control_actualizacion SET condicion=2 WHERE ".$condicion_actualizacion;
					$control=$this->shd000_control_actualizacion->execute($update);
				}
				$this->set('cant_registros',$cant_registros);
				$this->set('planilla_actual',$planilla_actual);
				$this->set('planilla_anterior',$planilla_anterior);
			}

		}else{
			$this->set('cant_registros',0);
			$this->set('control',0);
			if($control==888){
				$this->set('mensaje','No existe número de actualización para el mes de '.$array_mes[$mes].' del año '.$ano);
			}else{
				$this->set('mensaje','El mes de '.$array_mes[$mes].' ya fué emitido no puede volverlo a emitir');
			}
		}
		$this->set('tipo_impuesto',3);// Propaganda Comercial
	}
}


function inmuebles_urbanos_planilla_liquidacion_previa($ir=null){
	$this->layout="ajax";
	if($ir=='si'){
		$this->layout="ajax";
		$this->set('ir','si');
		$arranque=$this->shd000_arranque->findAll($this->SQLCA());
		if(count($arranque)>0){
			$ano_arranque=$arranque[0]['shd000_arranque']['ano_arranque'];
			$mes_arranque=$arranque[0]['shd000_arranque']['mes_arranque'];
		}else{
			$ano_arranque=date('Y');
			$mes_arranque=date('m');
		}
		for($i=1950; $i<=2100; $i++){
			$value[]=$i;
			$conten[]=$i;
		}
		$ano=array_combine($value,$conten);
		$mes = array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
		$this->set('ano',$ano);
		$this->set('mes',$mes);
		$this->set('ano_arranque',$ano_arranque);
		$this->set('mes_arranque',$mes_arranque);

	}else if($ir=='no'){
		$this->layout="pdf";
		$this->set('ir','no');
		$array_mes = array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
		$ano=$this->data['reporte_hacienda']['ano'];
		$mes=$this->data['reporte_hacienda']['mes'];
		$tipo_busqueda=$this->data['reporte_hacienda']['tipo_busqueda'];
		$control=0;// Para verificar en la tabla shd000_control_actualizacion si se puede emitir la planilla. Si el campo "condicion=1";

		if($tipo_busqueda==1){
			$condicion_cancelado = " AND a.cancelado = '2'";
			$condicion_rif= "";
			$condicion_actualizacion=$this->SQLCA()." and cod_ingreso='4' and ano_actualizado='$ano' and mes_actualizado='$mes'";
			$control_act=$this->shd000_control_actualizacion->findAll($condicion_actualizacion);
			if(isset($control_act[0]['shd000_control_actualizacion']['condicion'])){
				$control = $control_act[0]['shd000_control_actualizacion']['condicion'];
			}else{
				$control = 888;// Indica que no se encontro ningun registro para esa dependencia, en ese mes y en ese ano.
			}
		}else{
			$rif_cedula = $this->data['reporte_hacienda']['rif_cedula'];
			if($rif_cedula==''){
				echo"<script>";
				echo  "history.back(-1)";
				echo"</script>";
			}else{
				$condicion_cancelado = "";
				$condicion_rif = " AND a.rif_cedula='$rif_cedula'";
				$control=999;// Indica que la plantilla se va a imprimir por rif, por lo que se coloca un numero alto para que no entre a actualizar mas abajo.
			}
		}

		if($control==1 || $control==999){
			$sql_planilla_actual ="SELECT
										a.*,
										b.*,
										(SELECT d.frecuencia_pago FROM shd400_propiedad d WHERE d.cod_presi=a.cod_presi and d.cod_entidad=a.cod_entidad and d.cod_tipo_inst=a.cod_tipo_inst and d.cod_inst=a.cod_inst and d.cod_dep=a.cod_dep and d.rif_cedula=a.rif_cedula and d.cod_ficha=a.cod_numero_catastral_placas) AS frecuencia_pago,
										(SELECT d.deno_cobrador FROM shd400_propiedad d WHERE d.cod_presi=a.cod_presi and d.cod_entidad=a.cod_entidad and d.cod_tipo_inst=a.cod_tipo_inst and d.cod_inst=a.cod_inst and d.cod_dep=a.cod_dep and d.rif_cedula=a.rif_cedula and d.cod_ficha=a.cod_numero_catastral_placas) AS deno_cobrador,
										(SELECT c.nombre_inmueble FROM catd02_ficha_datos c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep
											and c.cod_ficha=(SELECT d.cod_ficha FROM shd400_propiedad d WHERE d.cod_presi=a.cod_presi and d.cod_entidad=a.cod_entidad and d.cod_tipo_inst=a.cod_tipo_inst and d.cod_inst=a.cod_inst and d.cod_dep=a.cod_dep and d.rif_cedula=a.rif_cedula and d.cod_ficha=a.cod_ficha)) AS nombre_inmueble
									FROM v_shd900_planillas_deuda_cobro_detalles a, v_shd001_registro_contribuyentes b
									WHERE
									a.rif_cedula 	= b.rif_cedula 		AND
									a.cod_ingreso	= '4'	AND
									a.ano		= '$ano'	AND
									a.mes		= '$mes'
									".$condicion_cancelado." ".$condicion_rif." ORDER BY numero_planilla ASC";

			$sql_planilla_anterior ="SELECT
										a.*,
										b.*,
										(SELECT d.frecuencia_pago FROM shd400_propiedad d WHERE d.cod_presi=a.cod_presi and d.cod_entidad=a.cod_entidad and d.cod_tipo_inst=a.cod_tipo_inst and d.cod_inst=a.cod_inst and d.cod_dep=a.cod_dep and d.rif_cedula=a.rif_cedula and d.cod_ficha=a.cod_numero_catastral_placas) AS frecuencia_pago,
										(SELECT d.deno_cobrador FROM shd400_propiedad d WHERE d.cod_presi=a.cod_presi and d.cod_entidad=a.cod_entidad and d.cod_tipo_inst=a.cod_tipo_inst and d.cod_inst=a.cod_inst and d.cod_dep=a.cod_dep and d.rif_cedula=a.rif_cedula and d.cod_ficha=a.cod_numero_catastral_placas) AS deno_cobrador,
										(SELECT c.nombre_inmueble FROM catd02_ficha_datos c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep
											and c.cod_ficha=(SELECT d.cod_ficha FROM shd400_propiedad d WHERE d.cod_presi=a.cod_presi and d.cod_entidad=a.cod_entidad and d.cod_tipo_inst=a.cod_tipo_inst and d.cod_inst=a.cod_inst and d.cod_dep=a.cod_dep and d.rif_cedula=a.rif_cedula and d.cod_ficha=a.cod_numero_catastral_placas)) AS nombre_inmueble
									FROM v_shd900_planillas_deuda_cobro_detalles a, v_shd001_registro_contribuyentes b
									WHERE
									a.rif_cedula 	= b.rif_cedula 		AND
									a.cod_ingreso	= '4'	AND
									((a.ano = '$ano'  AND a.mes < '$mes') OR a.ano < '$ano')
									".$condicion_cancelado." ".$condicion_rif." ORDER BY numero_planilla ASC";

			$planilla_actual=$this->v_shd900_planillas_deuda_cobro_detalles->execute($sql_planilla_actual);
			$planilla_anterior=$this->v_shd900_planillas_deuda_cobro_detalles->execute($sql_planilla_anterior);

			$cant_registros=count($planilla_actual);
			if($cant_registros==0){
				$this->set('cant_registros',0);
				$this->set('mensaje','PARA EL MES DE '.$array_mes[$mes].' DEL AÑO '.$ano);// No se encotraron datos.
			}elseif($cant_registros>0){
				if($tipo_busqueda==1 && $control==1){
					$update="UPDATE shd000_control_actualizacion SET condicion=2 WHERE ".$condicion_actualizacion;
					$control=$this->shd000_control_actualizacion->execute($update);
				}
				$this->set('cant_registros',$cant_registros);
				$this->set('planilla_actual',$planilla_actual);
				$this->set('planilla_anterior',$planilla_anterior);
			}

		}else{
			$this->set('cant_registros',0);
			$this->set('control',0);
			if($control==888){
				$this->set('mensaje','No existe número de actualización para el mes de '.$array_mes[$mes].' del año '.$ano);
			}else{
				$this->set('mensaje','El mes de '.$array_mes[$mes].' ya fué emitido no puede volverlo a emitir');
			}
		}
		$this->set('tipo_impuesto',4);// Inmuebles Urbanos
	}
}


function aseo_domiciliario_planilla_liquidacion_previa($ir=null){
	$this->layout="ajax";
	if($ir=='si'){
		$this->layout="ajax";
		$this->set('ir','si');
		$arranque=$this->shd000_arranque->findAll($this->SQLCA());
		if(count($arranque)>0){
			$ano_arranque=$arranque[0]['shd000_arranque']['ano_arranque'];
			$mes_arranque=$arranque[0]['shd000_arranque']['mes_arranque'];
		}else{
			$ano_arranque=date('Y');
			$mes_arranque=date('m');
		}
		for($i=1950; $i<=2100; $i++){
			$value[]=$i;
			$conten[]=$i;
		}
		$ano=array_combine($value,$conten);
		$mes = array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
		$this->set('ano',$ano);
		$this->set('mes',$mes);
		$this->set('ano_arranque',$ano_arranque);
		$this->set('mes_arranque',$mes_arranque);

	}else if($ir=='no'){
		$this->layout="pdf";
		$this->set('ir','no');
		$array_mes = array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
		$ano=$this->data['reporte_hacienda']['ano'];
		$mes=$this->data['reporte_hacienda']['mes'];
		$tipo_busqueda=$this->data['reporte_hacienda']['tipo_busqueda'];
		$control=0;// Para verificar en la tabla shd000_control_actualizacion si se puede emitir la planilla. Si el campo "condicion=1";

		if($tipo_busqueda==1){
			$condicion_cancelado = " AND a.cancelado = '2'";
			$condicion_rif= "";
			$condicion_actualizacion=$this->SQLCA()." and cod_ingreso='5' and ano_actualizado='$ano' and mes_actualizado='$mes'";
			$control_act=$this->shd000_control_actualizacion->findAll($condicion_actualizacion);
			if(isset($control_act[0]['shd000_control_actualizacion']['condicion'])){
				$control = $control_act[0]['shd000_control_actualizacion']['condicion'];
			}else{
				$control = 888;// Indica que no se encontro ningun registro para esa dependencia, en ese mes y en ese ano.
			}
		}else{
			$rif_cedula = $this->data['reporte_hacienda']['rif_cedula'];
			if($rif_cedula==''){
				echo"<script>";
				echo  "history.back(-1)";
				echo"</script>";
			}else{
				$condicion_cancelado = "";
				$condicion_rif = " AND a.rif_cedula='$rif_cedula'";
				$control=999;// Indica que la plantilla se va a imprimir por rif, por lo que se coloca un numero alto para que no entre a actualizar mas abajo.
			}
		}

		if($control==1 || $control==999){
			$sql_planilla_actual ="SELECT
										a.*,
										b.*,
										(SELECT c.deno_cobrador FROM v_shd500_aseo_domiciliario c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.rif_cedula=a.rif_cedula) AS deno_cobrador,
										(SELECT c.frecuencia_pago FROM v_shd500_aseo_domiciliario c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.rif_cedula=a.rif_cedula) AS frecuencia_pago,
										(SELECT c.deno_clasificacion FROM v_shd500_aseo_domiciliario c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.rif_cedula=a.rif_cedula) AS clasificacion_servicio
									FROM v_shd900_planillas_deuda_cobro_detalles a, v_shd001_registro_contribuyentes b
									WHERE
									a.rif_cedula	= b.rif_cedula AND
									a.cod_ingreso	= '5'	AND
									a.ano		= '$ano'	AND
									a.mes		= '$mes'
									".$condicion_cancelado." ".$condicion_rif." ORDER BY numero_planilla ASC";

			$sql_planilla_anterior ="SELECT
										a.*,
										b.*,
										(SELECT c.deno_cobrador FROM v_shd500_aseo_domiciliario c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.rif_cedula=a.rif_cedula) AS deno_cobrador,
										(SELECT c.frecuencia_pago FROM v_shd500_aseo_domiciliario c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.rif_cedula=a.rif_cedula) AS frecuencia_pago,
										(SELECT c.deno_clasificacion FROM v_shd500_aseo_domiciliario c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.rif_cedula=a.rif_cedula) AS clasificacion_servicio
									FROM v_shd900_planillas_deuda_cobro_detalles a, v_shd001_registro_contribuyentes b
									WHERE
									a.rif_cedula	= b.rif_cedula AND
									a.cod_ingreso	= '5'	AND
									((a.ano = '$ano'  AND a.mes < '$mes') OR a.ano < '$ano')
									 AND a.cancelado = '2'".$condicion_rif." ORDER BY numero_planilla ASC";

			$planilla_actual=$this->v_shd900_planillas_deuda_cobro_detalles->execute($sql_planilla_actual);
			//pr($planilla_actual);
			$planilla_anterior=$this->v_shd900_planillas_deuda_cobro_detalles->execute($sql_planilla_anterior);
			//pr($planilla_anterior);

			$cant_registros=count($planilla_actual);
			if($cant_registros==0){
				$this->set('cant_registros',0);
				$this->set('mensaje','PARA EL MES DE '.$array_mes[$mes].' DEL AÑO '.$ano);// No se encotraron datos.
			}elseif($cant_registros>0){
				if($tipo_busqueda==1 && $control==1){
					$update="UPDATE shd000_control_actualizacion SET condicion=2 WHERE ".$condicion_actualizacion;
					$control=$this->shd000_control_actualizacion->execute($update);
				}
				$this->set('cant_registros',$cant_registros);
				$this->set('planilla_actual',$planilla_actual);
				$this->set('planilla_anterior',$planilla_anterior);
			}

		}else{
			$this->set('cant_registros',0);
			$this->set('control',0);
			if($control==888){
				$this->set('mensaje','No existe número de actualización para el mes de '.$array_mes[$mes].' del año '.$ano);
			}else{
				$this->set('mensaje','El mes de '.$array_mes[$mes].' ya fué emitido no puede volverlo a emitir');
			}
		}
		$this->set('tipo_impuesto',5);// Aseo domiciliario
	}
}


function arrendamiento_tierra_planilla_liquidacion_previa($ir=null){
	$this->layout="ajax";
	if($ir=='si'){
		$this->layout="ajax";
		$this->set('ir','si');
		$arranque=$this->shd000_arranque->findAll($this->SQLCA());
		if(count($arranque)>0){
			$ano_arranque=$arranque[0]['shd000_arranque']['ano_arranque'];
			$mes_arranque=$arranque[0]['shd000_arranque']['mes_arranque'];
		}else{
			$ano_arranque=date('Y');
			$mes_arranque=date('m');
		}
		for($i=1950; $i<=2100; $i++){
			$value[]=$i;
			$conten[]=$i;
		}
		$ano=array_combine($value,$conten);
		$mes = array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
		$this->set('ano',$ano);
		$this->set('mes',$mes);
		$this->set('ano_arranque',$ano_arranque);
		$this->set('mes_arranque',$mes_arranque);

	}else if($ir=='no'){
		$this->layout="pdf";
		$this->set('ir','no');
		$array_mes = array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
		$ano=$this->data['reporte_hacienda']['ano'];
		$mes=$this->data['reporte_hacienda']['mes'];
		$tipo_busqueda=$this->data['reporte_hacienda']['tipo_busqueda'];
		$control=0;// Para verificar en la tabla shd000_control_actualizacion si se puede emitir la planilla. Si el campo "condicion=1";

		if($tipo_busqueda==1){
			$condicion_cancelado = " AND a.cancelado = '2'";
			$condicion_rif= "";
			$condicion_actualizacion=$this->SQLCA()." and cod_ingreso='6' and ano_actualizado='$ano' and mes_actualizado='$mes'";
			$control_act=$this->shd000_control_actualizacion->findAll($condicion_actualizacion);
			if(isset($control_act[0]['shd000_control_actualizacion']['condicion'])){
				$control = $control_act[0]['shd000_control_actualizacion']['condicion'];
			}else{
				$control = 888;// Indica que no se encontro ningun registro para esa dependencia, en ese mes y en ese ano.
			}
		}else{
			$rif_cedula = $this->data['reporte_hacienda']['rif_cedula'];
			if($rif_cedula==''){
				echo"<script>";
				echo  "history.back(-1)";
				echo"</script>";
			}else{
				$condicion_cancelado = "";
				$condicion_rif = " AND a.rif_cedula='$rif_cedula'";
				$control=999;// Indica que la plantilla se va a imprimir por rif, por lo que se coloca un numero alto para que no entre a actualizar mas abajo.
			}
		}

		if($control==1 || $control==999){
			$sql_planilla_actual ="SELECT
										a.*,
										b.*,
										(SELECT c.frecuencia_pago FROM shd600_aprobacion_arrendamiento c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.rif_cedula=a.rif_cedula and c.numero_solicitud=a.cod_numero_catastral_placas) AS frecuencia_pago,
										(SELECT c.deno_cobrador FROM shd600_aprobacion_arrendamiento c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.rif_cedula=a.rif_cedula and c.numero_solicitud=a.cod_numero_catastral_placas) AS deno_cobrador,
										(SELECT c.datos_registro_arrendamiento FROM shd600_aprobacion_arrendamiento c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.rif_cedula=a.rif_cedula and c.numero_solicitud=a.cod_numero_catastral_placas) AS datos_registro_arrendamiento
									FROM v_shd900_planillas_deuda_cobro_detalles a, v_shd001_registro_contribuyentes b
									WHERE
									a.rif_cedula	= b.rif_cedula AND
									a.cod_ingreso	= '6'	AND
									a.ano		= '$ano'	AND
									a.mes		= '$mes'
									".$condicion_cancelado." ".$condicion_rif." ORDER BY numero_planilla ASC";

			$sql_planilla_anterior ="SELECT
										a.*,
										b.*,
										(SELECT c.frecuencia_pago FROM shd600_aprobacion_arrendamiento c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.rif_cedula=a.rif_cedula and c.numero_solicitud=a.cod_numero_catastral_placas) AS frecuencia_pago,
										(SELECT c.deno_cobrador FROM shd600_aprobacion_arrendamiento c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.rif_cedula=a.rif_cedula and c.numero_solicitud=a.cod_numero_catastral_placas) AS deno_cobrador,
										(SELECT c.datos_registro_arrendamiento FROM shd600_aprobacion_arrendamiento c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.rif_cedula=a.rif_cedula and c.cod_numero_catastral_placas=a.cod_numero_catastral_placas) AS datos_registro_arrendamiento
									FROM v_shd900_planillas_deuda_cobro_detalles a, v_shd001_registro_contribuyentes b
									WHERE
									a.rif_cedula	= b.rif_cedula AND
									a.cod_ingreso	= '6'	AND
									((a.ano = '$ano'  AND a.mes < '$mes') OR a.ano < '$ano')
									AND a.cancelado = '2'".$condicion_rif." ORDER BY numero_planilla ASC";

			$planilla_actual=$this->v_shd900_planillas_deuda_cobro_detalles->execute($sql_planilla_actual);
			//pr($planilla_actual);
			$planilla_anterior=$this->v_shd900_planillas_deuda_cobro_detalles->execute($sql_planilla_anterior);

			$cant_registros=count($planilla_actual);
			if($cant_registros==0){
				$this->set('cant_registros',0);
				$this->set('mensaje','PARA EL MES DE '.$array_mes[$mes].' DEL AÑO '.$ano);// No se encotraron datos.
			}elseif($cant_registros>0){
				if($tipo_busqueda==1 && $control==1){
					$update="UPDATE shd000_control_actualizacion SET condicion=2 WHERE ".$condicion_actualizacion;
					$control=$this->shd000_control_actualizacion->execute($update);
				}
				$this->set('cant_registros',$cant_registros);
				$this->set('planilla_actual',$planilla_actual);
				$this->set('planilla_anterior',$planilla_anterior);
			}

		}else{
			$this->set('cant_registros',0);
			$this->set('control',0);
			if($control==888){
				$this->set('mensaje','No existe número de actualización para el mes de '.$array_mes[$mes].' del año '.$ano);
			}else{
				$this->set('mensaje','El mes de '.$array_mes[$mes].' ya fué emitido no puede volverlo a emitir');
			}
		}
		$this->set('tipo_impuesto',6);// Arrendamientos de tierras
	}
}


function credito_vivienda_planilla_liquidacion_previa($ir=null){
	$this->layout="ajax";
	if($ir=='si'){
		$this->layout="ajax";
		$this->set('ir','si');
		$arranque=$this->shd000_arranque->findAll($this->SQLCA());
		if(count($arranque)>0){
			$ano_arranque=$arranque[0]['shd000_arranque']['ano_arranque'];
			$mes_arranque=$arranque[0]['shd000_arranque']['mes_arranque'];
		}else{
			$ano_arranque=date('Y');
			$mes_arranque=date('m');
		}
		for($i=1950; $i<=2100; $i++){
			$value[]=$i;
			$conten[]=$i;
		}
		$ano=array_combine($value,$conten);
		$mes = array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
		$this->set('ano',$ano);
		$this->set('mes',$mes);
		$this->set('ano_arranque',$ano_arranque);
		$this->set('mes_arranque',$mes_arranque);

	}else if($ir=='no'){
		$this->layout="pdf";
		$this->set('ir','no');
		$array_mes = array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
		$ano=$this->data['reporte_hacienda']['ano'];
		$mes=$this->data['reporte_hacienda']['mes'];
		$tipo_busqueda=$this->data['reporte_hacienda']['tipo_busqueda'];
		$control=0;// Para verificar en la tabla shd000_control_actualizacion si se puede emitir la planilla. Si el campo "condicion=1";

		if($tipo_busqueda==1){
			$condicion_cancelado = " AND a.cancelado = '2'";
			$condicion_rif= "";
			$condicion_actualizacion=$this->SQLCA()." and cod_ingreso='7' and ano_actualizado='$ano' and mes_actualizado='$mes'";
			$control_act=$this->shd000_control_actualizacion->findAll($condicion_actualizacion);
			if(isset($control_act[0]['shd000_control_actualizacion']['condicion'])){
				$control = $control_act[0]['shd000_control_actualizacion']['condicion'];
			}else{
				$control = 888;// Indica que no se encontro ningun registro para esa dependencia, en ese mes y en ese ano.
			}
		}else{
			$rif_cedula = $this->data['reporte_hacienda']['rif_cedula'];
			if($rif_cedula==''){
				echo"<script>";
				echo  "history.back(-1)";
				echo"</script>";
			}else{
				$condicion_cancelado = "";
				$condicion_rif = " AND a.rif_cedula='$rif_cedula'";
				$control=999;// Indica que la plantilla se va a imprimir por rif, por lo que se coloca un numero alto para que no entre a actualizar mas abajo.
			}
		}

		if($control==1 || $control==999){
			$sql_planilla_actual ="SELECT
										a.*,
										b.*,
										(SELECT c.frecuencia_pago FROM shd700_credito_vivienda c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.rif_cedula=a.rif_cedula and c.numero_solicitud=a.cod_numero_catastral_placas) AS frecuencia_pago,
										(SELECT c.deno_cobrador FROM shd700_credito_vivienda c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.rif_cedula=a.rif_cedula and c.numero_solicitud=a.cod_numero_catastral_placas) AS deno_cobrador,
										(SELECT c.direccion_vivienda_credito FROM shd700_credito_vivienda c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.rif_cedula=a.rif_cedula and c.numero_solicitud=a.cod_numero_catastral_placas) AS direccion_vivienda
									FROM v_shd900_planillas_deuda_cobro_detalles a, v_shd001_registro_contribuyentes b
									WHERE
									a.rif_cedula 	= b.rif_cedula 	AND
									a.cod_ingreso	= '7'	AND
									a.ano		= '$ano'	AND
									a.mes		= '$mes'
									".$condicion_cancelado." ".$condicion_rif." ORDER BY numero_planilla ASC";

			$sql_planilla_anterior ="SELECT
										a.*,
										b.*,
										(SELECT c.frecuencia_pago FROM shd700_credito_vivienda c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.rif_cedula=a.rif_cedula and c.numero_solicitud=a.cod_numero_catastral_placas) AS frecuencia_pago,
										(SELECT c.deno_cobrador FROM shd700_credito_vivienda c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.rif_cedula=a.rif_cedula and c.numero_solicitud=a.cod_numero_catastral_placas) AS deno_cobrador,
										(SELECT c.direccion_vivienda_credito FROM shd700_credito_vivienda c WHERE c.cod_presi=a.cod_presi and c.cod_entidad=a.cod_entidad and c.cod_tipo_inst=a.cod_tipo_inst and c.cod_inst=a.cod_inst and c.cod_dep=a.cod_dep and c.rif_cedula=a.rif_cedula and c.numero_solicitud=a.cod_numero_catastral_placas) AS direccion_vivienda
									FROM v_shd900_planillas_deuda_cobro_detalles a, v_shd001_registro_contribuyentes b
									WHERE
									a.rif_cedula 	= b.rif_cedula 		AND
									a.cod_ingreso	= '7'	AND
									((a.ano = '$ano'  AND a.mes < '$mes') OR a.ano < '$ano')
									 AND a.cancelado = '2'".$condicion_rif." ORDER BY numero_planilla ASC";

			$planilla_actual=$this->v_shd900_planillas_deuda_cobro_detalles->execute($sql_planilla_actual);
			//pr($planilla_actual);
			$planilla_anterior=$this->v_shd900_planillas_deuda_cobro_detalles->execute($sql_planilla_anterior);

			$cant_registros=count($planilla_actual);
			if($cant_registros==0){
				$this->set('cant_registros',0);
				$this->set('mensaje','PARA EL MES DE '.$array_mes[$mes].' DEL AÑO '.$ano);// No se encotraron datos.
			}elseif($cant_registros>0){
				if($tipo_busqueda==1 && $control==1){
					$update="UPDATE shd000_control_actualizacion SET condicion=2 WHERE ".$condicion_actualizacion;
					$control=$this->shd000_control_actualizacion->execute($update);
				}
				$this->set('cant_registros',$cant_registros);
				$this->set('planilla_actual',$planilla_actual);
				$this->set('planilla_anterior',$planilla_anterior);
			}

		}else{
			$this->set('cant_registros',0);
			$this->set('control',0);
			if($control==888){
				$this->set('mensaje','No existe número de actualización para el mes de '.$array_mes[$mes].' del año '.$ano);
			}else{
				$this->set('mensaje','El mes de '.$array_mes[$mes].' ya fué emitido no puede volverlo a emitir');
			}
		}
		$this->set('tipo_impuesto',7);// Credito Vivienda
	}
}


function planilla_liquidacion_previa($ir=null, $tipo_impuesto=null){
	$this->layout="ajax";
	if($ir=='si'){
		$this->layout="ajax";
		$this->set('ir','si');
		$arranque=$this->shd000_arranque->findAll($this->SQLCA());
		if(count($arranque)>0){
			$ano_arranque=$arranque[0]['shd000_arranque']['ano_arranque'];
			$mes_arranque=$arranque[0]['shd000_arranque']['mes_arranque'];
		}else{
			$ano_arranque=date('Y');
			$mes_arranque=date('m');
		}
		for($i=1950; $i<=2100; $i++){
			$value[]=$i;
			$conten[]=$i;
		}
		$ano=array_combine($value,$conten);
		$mes = array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
		$this->set('ano',$ano);
		$this->set('mes',$mes);
		$this->set('ano_arranque',$ano_arranque);
		$this->set('mes_arranque',$mes_arranque);
		$this->set('tipo_impuesto',$tipo_impuesto);

	}else if($ir=='no'){
		$this->layout="pdf";
		$this->set('ir','no');
		$array_mes = array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
		$ano=$this->data['reporte_hacienda']['ano'];
		$mes=$this->data['reporte_hacienda']['mes'];
		$tipo_busqueda=$this->data['reporte_hacienda']['tipo_busqueda'];
		$control=0;// Para verificar en la tabla shd000_control_actualizacion si se puede emitir la planilla. Si el campo "condicion=1";

		if($tipo_busqueda==1){
			$condicion_cancelado = " AND a.cancelado = '2'";
			$condicion_rif= "";
			$condicion_actualizacion=$this->SQLCA()." and cod_ingreso='$tipo_impuesto' and ano_actualizado='$ano' and mes_actualizado='$mes'";
			$control_act=$this->shd000_control_actualizacion->findAll($condicion_actualizacion);
			if(isset($control_act[0]['shd000_control_actualizacion']['condicion'])){
				$control = $control_act[0]['shd000_control_actualizacion']['condicion'];
			}else{
				$control = 888;// Indica que no se encontro ningun registro para esa dependencia, en ese mes y en ese ano.
			}
		}else{
			$rif_cedula = $this->data['reporte_hacienda']['rif_cedula'];
			if($rif_cedula==''){
				echo"<script>";
				echo  "history.back(-1)";
				echo"</script>";
			}else{
				$condicion_cancelado = " AND a.cancelado = '2'";
				$condicion_rif = " AND a.rif_cedula='$rif_cedula'";
				//$condicion = "a.rif_cedula='$rif_cedula'";
				$control=999;// Indica que la plantilla se va a imprimir por rif, por lo que se coloca un numero alto para que no entre a actualizar mas abajo.
			}
		}

		if($control==1 || $control==999){
			$sql_planilla_actual ="SELECT
										a.*,
										b.*
									FROM v_shd900_planillas_deuda_cobro_detalles a, v_shd100_patente b
									WHERE
									a.rif_cedula 	= b.rif_cedula 	AND
									a.cod_ingreso	= '$tipo_impuesto'	AND
									a.ano		= '$ano'	AND
									a.mes		= '$mes'
									".$condicion_cancelado." ".$condicion_rif;

			$sql_planilla_anterior ="SELECT
										a.*,
										b.*
									FROM v_shd900_planillas_deuda_cobro_detalles a, v_shd100_patente b
									WHERE
									a.rif_cedula 	= b.rif_cedula 		AND
									a.cod_ingreso	= '$tipo_impuesto'	AND
									((a.ano = '$ano'  AND a.mes < '$mes') OR a.ano < '$ano')
									".$condicion_cancelado." ".$condicion_rif;

			$planilla_actual=$this->v_shd900_planillas_deuda_cobro_detalles->execute($sql_planilla_actual);
			$planilla_anterior=$this->v_shd900_planillas_deuda_cobro_detalles->execute($sql_planilla_anterior);

			$cant_registros=count($planilla_actual);
			if($cant_registros==0){
				$this->set('cant_registros',0);
				$this->set('mensaje','PARA EL MES DE '.$array_mes[$mes].' DEL AÑO '.$ano);// No se encotraron datos.
			}elseif($cant_registros>0){
				if($tipo_busqueda==1 && $control==1){
					$update="UPDATE shd000_control_actualizacion SET condicion=2 WHERE ".$condicion_actualizacion;
					$control=$this->shd000_control_actualizacion->execute($update);
				}
				$this->set('cant_registros',$cant_registros);
				$this->set('planilla_actual',$planilla_actual);
				$this->set('planilla_anterior',$planilla_anterior);
			}

		}else{
			$this->set('cant_registros',0);
			$this->set('control',0);
			if($control==888){
				$this->set('mensaje','No existe número de actualización para el mes de '.$array_mes[$mes].' del año '.$ano);
			}else{
				$this->set('mensaje','El mes de '.$array_mes[$mes].' ya fué emitido no puede volverlo a emitir');
			}
		}
		$this->set('tipo_impuesto',$tipo_impuesto);
	}
}


function vacio($var=null){
	$this->layout="ajax";
}


function reporte_cumplimiento_metas($ir=null){
	$this->layout="ajax";
	if($ir=='si'){
		$this->layout="ajax";
		$this->set('ir','si');
		$this->Session->delete('ano_report');
		for($i=1950; $i<=2100; $i++){
			$value[]=$i;
			$conten[]=$i;
		}
		$ano=array_combine($value,$conten);
		$mes = array('1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril','5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto','9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
		$this->set('ano',$ano);
		$this->set('mes',$mes);

	}else if($ir=='no'){
		$this->layout="pdf";
		$this->set('ir','no');
		$ano_arranque = $this->data['reporte_hacienda']['ano'];
		$datos_filas=$this->v_cfpd03_denominacion_partida->findAll($this->condicion()." and ano='".$ano_arranque."' ",null,"cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar ASC");
		$this->set("datos2",$datos_filas);
		$this->Session->write('ano_report',$ano_arranque);
	}
}//fin function



function shd100_contribuyentes_por_declarar_ingresos_brutos($ir=null){
	$this->layout="ajax";
	if($ir=='si'){
		$this->layout="ajax";
		$this->set('ir','si');

	}else if($ir=='no'){
		$this->layout="pdf";
		$this->set('ir','no');

		$filtro=$this->SQLCA();

		$sql="SELECT
			a.cod_presi,
			a.cod_entidad,
			a.cod_tipo_inst,
			a.cod_inst,
			a.cod_dep,
			a.rif_cedula,
			(select b.razon_social_nombres from shd001_registro_contribuyentes b where b.rif_cedula=a.rif_cedula) as nombre_razon,
			a.numero_solicitud,
			a.numero_patente,
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
			a.ultimo_numero_declaracion,
			devolver_edad('".date('Y-m-d')."', a.fecha_patente, 'ANO') as ano_diferencia_patente,
			devolver_edad('".date('Y-m-d')."', a.fecha_patente, 'MES') as mes_diferencia_patente,
			devolver_edad('".date('Y-m-d')."', a.fecha_patente, 'DIA') as dia_diferencia_patente,
			devolver_edad('".date('Y-m-d')."', a.fecha_ultima_decla, 'ANO') as ano_diferencia_decla,
			devolver_edad('".date('Y-m-d')."', a.fecha_ultima_decla, 'MES') as mes_diferencia_decla,
			devolver_edad('".date('Y-m-d')."', a.fecha_ultima_decla, 'DIA') as dia_diferencia_decla,
			devolver_edad('".date('Y-m-d')."', a.periodo_hasta, 'ANO') as ano_diferencia_hasta,
			devolver_edad('".date('Y-m-d')."', a.periodo_hasta, 'MES') as mes_diferencia_hasta,
			devolver_edad('".date('Y-m-d')."', a.periodo_hasta, 'DIA') as dia_diferencia_hasta,
			(select b.capital from shd100_solicitud b where  b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.rif_cedula=a.rif_cedula) as capital,
			(select b.numero_empleado from shd100_solicitud b where  b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.rif_cedula=a.rif_cedula) as numero_empleados,
			(select b.numero_obreros from shd100_solicitud b where  b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_inst=a.cod_inst and b.cod_dep=a.cod_dep and b.rif_cedula=a.rif_cedula) as numero_obreros
			  FROM shd100_patente a where ".$filtro." order by rif_cedula ";

			$ejecuta=$this->shd000_control_actualizacion->execute($sql);

		 	if($ejecuta!=null){
				$this->set('datos',$ejecuta);
			}else{
				$this->set('datos',null);
			}

	}
}


function licencia_patente_industria_comercio($num_patente=null, $rif_contribuyente=null, $num_solicitud=null){
	$this->layout="pdf";

	$cod_presi=$this->Session->read('SScodpresi');
	$cod_entidad=$this->Session->read('SScodentidad');
	$deno_estado = $this->cugd01_estados->findAll("cod_republica='$cod_presi' AND cod_estado='$cod_entidad'");
	$this->set('deno_estado', $deno_estado[0]['cugd01_estados']['denominacion']);

	$condicion = $this->SQLCA();
	$cond_solicitud = $condicion." AND rif_cedula='$rif_contribuyente' AND numero_solicitud='$num_solicitud'";
	$cond_patente = $condicion." AND rif_cedula='$rif_contribuyente' AND numero_patente='$num_patente' AND numero_solicitud='$num_solicitud'";

	$datos_solicitud = $this->v_shd100_solicitud->findAll($cond_solicitud);
	$datos_pantente = $this->v_shd100_patente->findAll($cond_patente);
	$actividades_patente = $this->v_shd100_patente_actividades->findAll("rif_cedula='$rif_contribuyente'");

    $count_firmas = $this->cugd07_firmas_oficio_anulacion->findCount($condicion.' AND tipo_documento=1001');
    if($count_firmas>0){
		$firmas = $this->cugd07_firmas_oficio_anulacion->findAll($condicion.' AND tipo_documento=1001');
		$this->set('nombre_firmante', $firmas[0]['cugd07_firmas_oficio_anulacion']['nombre_primera_firma']);
		$this->set('cargo_firmante', $firmas[0]['cugd07_firmas_oficio_anulacion']['cargo_primera_firma']);
		$this->set('resolucion', $firmas[0]['cugd07_firmas_oficio_anulacion']['nombre_segunda_firma']);
    }else{
    	$this->set('nombre_firmante', '');
		$this->set('cargo_firmante', '');
		$this->set('resolucion', '');
    }

	//pr($datos_solicitud);
	//pr($datos_pantente);
	//pr($actividades_patente);

	$this->set('datos_solicitud', $datos_solicitud);
	$this->set('datos_pantente', $datos_pantente);
	$this->set('actividades_patente', $actividades_patente);
}


function shd100_relacion_contribuyente_detallado_v2($var1=null){
	if($var1==1){
		$this->layout="ajax";
		$ano = $this->ano_ejecucion() != '' ? $this->ano_ejecucion() : d('Y');
		$this->set('ano',$ano);
		$opcion = 1;
	}else{
		$this->layout="pdf";
		$opcion = 2;
		$condicion=$this->SQLCA();

		$ano = $this->data['reporte_hacienda']['ano'];
		$ordenado = $this->data['reporte_hacienda']['ordenado'];
		if($ordenado==1){//ALFABETICO
			$order = " rc.razon_social_nombres ASC";
		}else if($ordenado==2){//RIF
			$order = " a.rif_cedula ASC";
		}

		$fecha_inicial_ano = "01-01-".$ano;
		$fecha_final_ano = "31-12-".$ano;

		$sql = "SELECT
				  a.cod_presi,
				  a.cod_entidad,
				  a.cod_tipo_inst,
				  a.cod_inst,
				  a.cod_dep,
				  a.rif_cedula,
				  rc.razon_social_nombres,
				  rc.numero_vivienda_local,
				  (SELECT v.cedula_identidad FROM v_shd100_solicitud v WHERE v.cod_presi = a.cod_presi AND v.cod_entidad = a.cod_entidad AND v.cod_tipo_inst = a.cod_tipo_inst AND v.cod_inst = a.cod_inst AND v.cod_dep = a.cod_dep AND v.numero_solicitud=b.numero_solicitud AND v.numero_patente=b.numero_patente AND v.rif_cedula=b.rif_cedula) AS cedula_representante,
				  (SELECT v.nombres_apellidos FROM v_shd100_solicitud v WHERE v.cod_presi = a.cod_presi AND v.cod_entidad = a.cod_entidad AND v.cod_tipo_inst = a.cod_tipo_inst AND v.cod_inst = a.cod_inst AND v.cod_dep = a.cod_dep AND v.numero_solicitud=b.numero_solicitud AND v.numero_patente=b.numero_patente AND v.rif_cedula=b.rif_cedula) AS nombre_representante,
				  b.numero_solicitud,
				  b.numero_patente,
				  b.fecha_patente,
				  b.ingresos_declarados,
				  b.suspendido,
				  a.cod_actividad,
				  a.deno_actividad,
				  a.alicuota,
				  substr(b.fecha_patente::text, 1, 4)::integer AS ano_patente

				FROM
					v_shd100_patente_actividades a, v_shd100_patente b, v_shd001_registro_contribuyentes rc
				WHERE

					b.cod_presi = a.cod_presi AND
					b.cod_entidad = a.cod_entidad AND
					b.cod_tipo_inst = a.cod_tipo_inst AND
					b.cod_inst = a.cod_inst AND
					b.cod_dep = a.cod_dep AND
					b.rif_cedula=a.rif_cedula AND
					rc.rif_cedula=a.rif_cedula AND
					b.fecha_patente BETWEEN '$fecha_inicial_ano' AND '$fecha_final_ano'

				ORDER BY $order;";

		//$datos = $this->v_shd100_patente->findAll($condicion,null,$order);
		$datos = $this->v_shd100_patente->execute($sql);
		$this->set("datos", $datos);
		$this->set("ano", $ano);
	}
	$this->set("opcion", $opcion);
}


function shp950_relacion_solvencia_juridica($var1=null){


       if($var1==1){ $this->layout="ajax";
       	 $this->Session->delete('ano_report');

		$ver=$this->shd000_arranque->execute("select * from shd000_arranque where ".$this->SQLCA());
		if($ver!=null){
			$this->set('ano1',$ver[0][0]['ano_arranque']);
			$this->set('mes',$ver[0][0]['mes_arranque']);
		}else{
			$this->set('ano1','');
			$this->set('mes','');
		}

 $datos  = $this->shd950_solvencia->execute(" SELECT DISTINCT ano FROM shd950_solvencia WHERE ".$this->condicion()." ORDER BY ano ASC");
					if(count($datos)!=0){
						foreach($datos as $n){
							$lista[$n[0]['ano']]=$n[0]['ano'];
					    }
					}else{
						$lista=array('0'=>'No existen datos');
					}
					$this->set("lista_ano", $lista);




}else if($var1==2){  $this->layout="pdf";
       	 $this->Session->delete('ano_report');
		 $sql='';

		 if(!empty($this->data['reporte_hacienda']['ano'])){

	         $ano = $this->data['reporte_hacienda']['ano'];
	         $this->Session->write('ano_report',$ano);

	         $tipo_busqueda = $this->data['reporte_hacienda']['tipo_busqueda'];
	         if($tipo_busqueda==1){
	         	$order=" ORDER BY numero_solvencia ASC";
	         }elseif($tipo_busqueda==2){
				$order=" ORDER BY razon_social_nombres ASC";
	         }else{
	         	$order=" ORDER BY fecha_expedicion ASC";
	         }

			 $sql=$this->SQLCA()." and ano='$ano' and personalidad_juridica=2 ";

   			 $sql1="select * from v_shd950_solvencias where ".$sql.$order;

			$ejecuta=$this->v_shd001_contribuyentes_e_impuestos->execute($sql1);

	        if($ejecuta!=null){
				$this->set('datos',$ejecuta);
			}else{
				$this->set('datos',null);
			}
		}else{
			$this->set('datos',null);
		}

}//fin function

$this->set("opcion", $var1);

}//fin function





function shp950_relacion_solvencia_natural($var1=null){


       if($var1==1){ $this->layout="ajax";
       	 $this->Session->delete('ano_report');

		$ver=$this->shd000_arranque->execute("select * from shd000_arranque where ".$this->SQLCA());
		if($ver!=null){
			$this->set('ano1',$ver[0][0]['ano_arranque']);
			$this->set('mes',$ver[0][0]['mes_arranque']);
		}else{
			$this->set('ano1','');
			$this->set('mes','');
		}

 $datos  = $this->shd950_solvencia->execute(" SELECT DISTINCT ano FROM shd950_solvencia WHERE ".$this->condicion()." ORDER BY ano ASC");
					if(count($datos)!=0){
						foreach($datos as $n){
							$lista[$n[0]['ano']]=$n[0]['ano'];
					    }
					}else{
						$lista=array('0'=>'No existen datos');
					}
					$this->set("lista_ano", $lista);




}else if($var1==2){  $this->layout="pdf";
       	 $this->Session->delete('ano_report');
		 $sql='';

		 if(!empty($this->data['reporte_hacienda']['ano'])){

	         $ano = $this->data['reporte_hacienda']['ano'];
	         $this->Session->write('ano_report',$ano);

	         $tipo_busqueda = $this->data['reporte_hacienda']['tipo_busqueda'];
	         if($tipo_busqueda==1){
	         	$order=" ORDER BY numero_solvencia ASC";
	         }elseif($tipo_busqueda==2){
				$order=" ORDER BY razon_social_nombres ASC";
	         }else{
	         	$order=" ORDER BY fecha_expedicion ASC";
	         }

			 $sql=$this->SQLCA()." and ano='$ano' and personalidad_juridica=1 ";

   			 $sql1="select * from v_shd950_solvencias where ".$sql.$order;

			$ejecuta=$this->v_shd001_contribuyentes_e_impuestos->execute($sql1);

	        if($ejecuta!=null){
				$this->set('datos',$ejecuta);
			}else{
				$this->set('datos',null);
			}
		}else{
			$this->set('datos',null);
		}

}//fin function

$this->set("opcion", $var1);

}//fin function


 function shp100_solicitud_patente_industria_comercio_ficha($num_solicitud=null, $rif_cedula=null){
	$this->layout="pdf";
	$condicion=$this->SQLCA();
	$datos=$this->v_shd100_solicitud->findAll($condicion." AND numero_solicitud='$num_solicitud' AND rif_cedula='$rif_cedula'",null,'numero_solicitud ASC');
	$datos_actividades=$this->v_shd100_solicitud_actividades->findAll($condicion." AND numero_solicitud='$num_solicitud'");
	$this->set('datos_solicitud',$datos);
	$this->set('datos_actividades_solicitud',$datos_actividades);
 }







function shd100_relacion_actividades_economicas($var1=null, $var2=null){
	 if($var1==1){
		$this->layout="ajax";

		$ver=$this->shd000_arranque->execute("select * from shd000_arranque where ".$this->SQLCA());
		if($ver!=null){
			$this->set('ano1',$ver[0][0]['ano_arranque']);
			$this->set('mes',$ver[0][0]['mes_arranque']);
		}else{
			$this->set('ano1','');
			$this->set('mes','');
		}



		$datos  = $this->shd950_solvencia->execute(" SELECT DISTINCT substr(fecha_patente::text,0,5)::integer as ano FROM v_shd100_relacion_actividades_economicas WHERE ".$this->condicion()." ORDER BY substr(fecha_patente::text,0,5)::integer ASC");
		if(count($datos)!=0){
			foreach($datos as $n){
				$lista[$n[0]['ano']]=$n[0]['ano'];
		    }
		}else{
			$lista=array('0'=>'No existen datos');
		}
		$this->set("lista_ano", $lista);


		$opcion = 1;
	}else{
		$this->layout="pdf";
		$opcion = 2;
		if(!empty($this->data['reporte3']['ano'])){
			$ano=$this->data['reporte3']['ano'];
			$_SESSION['top_relacion_ano']=$ano;
			$radio2=$this->data['reporte3']['radio_1'];
			if($radio2==1){
				$sql=("select * from v_shd100_relacion_actividades_economicas where ".$this->SQLCA()." and substr(fecha_patente::text,0,5)::integer=".$ano." order by  rif_cedula asc");
				$ejecuta=$this->v_shd100_patente->execute($sql);
			}else if($radio2==2){;
				$sql=("select * from v_shd100_relacion_actividades_economicas where ".$this->SQLCA()." and substr(fecha_patente::text,0,5)::integer=".$ano." order by razon_social_nombres asc");
				$ejecuta=$this->v_shd100_patente->execute($sql);

			}else if($radio2==3){
				$sql=("select * from v_shd100_relacion_actividades_economicas where ".$this->SQLCA()." and substr(fecha_patente::text,0,5)::integer=".$ano." order by numero_patente asc");
				$ejecuta=$this->v_shd100_patente->execute($sql);

			}
			if($ejecuta!=null){
				$this->set('datos',$ejecuta);
			}else{
				$this->set('datos',null);
			}

		}else{
			$this->set('datos',null);


		}
	 }
	$this->set("opcion", $opcion);
}



}//fin class
?>