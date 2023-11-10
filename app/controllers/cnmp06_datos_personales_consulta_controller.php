<?php
/*
 * Creado el  30/10/2007 a las 12:03:17 PM
 *
 * Herramienta: EasyEclipse.
 *
 * Proyecto: SIGEP
 *
 */
 class Cnmp06DatosPersonalesConsultaController extends AppController {
   var $name = 'cnmp06_datos_personales_consulta';
   var $uses = array('cnmd06_datos_personales','cnmd06_fichas','cnmd05','cnmd02_empleados_puestos','cnmd02_obreros_puestos','cnmd02_varios_puestos',
                     'cnmd07_transacciones_actuales');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap');





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





function index(){
 	$this->layout ="ajax";

 	  if($this->Session->read('cedula_pestana_expediente')==""){
         	$id=0;
    }else{
    	    $id=$this->Session->read('cedula_pestana_expediente');
    }//fin else
	$this->set('cedula', "");
	$Tfilas=$this->cnmd06_datos_personales->findCount("cedula_identidad=".$id);
   if($Tfilas!=0){
      $this->consulta();
      $this->render("consulta");
   }else{
   	    $this->set('ci',"");
	    $this->set('pa',"");
	    $this->set('sa',"");
	    $this->set('pn',"");
	    $this->set('sn',"");
  }//fin else

 }//fin function





function consulta($pag_num = null){

    $this->layout ="ajax";

			  $cod_presi              =  0;
			  $cod_entidad            =  0;
			  $cod_tipo_inst          =  0;
			  $cod_inst               =  0;
			  $cod_dep                =  0;
			  $cod_tipo_nomina        =  0;
			  $cod_cargo              =  0;
			  $cod_ficha              =  0;


if(!isset($_SESSION["pag_num_expediente"])){
    $pag_num = 0;
    $this->Session->write('pag_num_expediente',  $pag_num);
}else{

    if($pag_num==null){
         $pag_num = $this->Session->read('pag_num_expediente');
    }else{
    	 $this->Session->write('pag_num_expediente',  $pag_num);
    }
}//fin else



$this->set("pag_num", $pag_num);




    if($this->Session->read('cedula_pestana_expediente')==""){
     	$id=0;
}else{
	    $id=$this->Session->read('cedula_pestana_expediente');
}//fin else




$datos = $this->cnmd06_datos_personales->execute("

			SELECT


					  a.cedula_identidad,
					  a.primer_apellido,
					  a.segundo_apellido,
					  a.primer_nombre,
					  a.segundo_nombre,


					  b.cod_presi,
					  b.cod_entidad,
					  b.cod_tipo_inst,
					  b.cod_inst,
					  b.cod_dep,
					  (SELECT x.denominacion  FROM arrd05 x WHERE
							  x.cod_presi           =     b.cod_presi       and
							  x.cod_entidad         =     b.cod_entidad     and
							  x.cod_tipo_inst       =     b.cod_tipo_inst   and
							  x.cod_inst            =     b.cod_inst        and
							  x.cod_dep             =     b.cod_dep
					  ) as denominacion_dependencia,
					  b.cod_tipo_nomina,
					  (SELECT x.denominacion  FROM cnmd01 x WHERE
							  x.cod_presi           =     b.cod_presi       and
							  x.cod_entidad         =     b.cod_entidad     and
							  x.cod_tipo_inst       =     b.cod_tipo_inst   and
							  x.cod_inst            =     b.cod_inst        and
							  x.cod_dep             =     b.cod_dep         and
							  x.cod_tipo_nomina     =     b.cod_tipo_nomina
					  ) as denominacion_nomina,
					  b.cod_cargo,
					  b.cod_ficha,
					  b.fecha_ingreso,
					  b.forma_pago,
					  b.cod_entidad_bancaria,
					  b.cod_sucursal,
					  b.cuenta_bancaria,
					  b.condicion_actividad,
					  b.funciones_realizar,
					  b.responsabilidad_administrativa,
					  b.horas_laborar,
					  b.porcentaje_jub_pension,
					  b.fecha_terminacion_contrato,
					  b.fecha_retiro,
					  b.motivo_retiro,
					  b.paso,
					  b.tipo_contrato,
					  b.situacion,
					  b.nivel,
					  b.categoria,


					  c.cod_puesto,
					  (select devolver_denominacion_puesto(
			               (select xy.clasificacion_personal from cnmd01 xy where
			                  xy.cod_presi           =     c.cod_presi       and
							  xy.cod_entidad         =     c.cod_entidad     and
							  xy.cod_tipo_inst       =     c.cod_tipo_inst   and
							  xy.cod_inst            =     c.cod_inst        and
							  xy.cod_dep             =     c.cod_dep         and
							  xy.cod_tipo_nomina     =     c.cod_tipo_nomina
			               ), c.cod_puesto )
			          ) as demonimacion_puesto,
			          (select devolver_grado_puesto(
			               (select xy.clasificacion_personal from cnmd01 xy where
			                  xy.cod_presi           =     c.cod_presi       and
							  xy.cod_entidad         =     c.cod_entidad     and
							  xy.cod_tipo_inst       =     c.cod_tipo_inst   and
							  xy.cod_inst            =     c.cod_inst        and
							  xy.cod_dep             =     c.cod_dep         and
							  xy.cod_tipo_nomina     =     c.cod_tipo_nomina
			               ), c.cod_puesto )
			          ) as grado_puesto,
					  c.sueldo_basico,
					  c.compensaciones,
					  c.primas,
					  c.bonos,
					  (c.compensaciones + c.primas + c.bonos) as otras_remuneraciones,
					  c.cod_dir_superior,
					  c.cod_coordinacion,
					  c.cod_secretaria,
					  c.cod_direccion,
					  c.cod_division,
					  c.cod_departamento,
					  c.cod_oficina,
						  (SELECT xa.denominacion FROM cugd02_direccionsuperior xa where xa.cod_tipo_institucion=c.cod_tipo_inst  and xa.cod_institucion=c.cod_inst and xa.cod_dependencia=c.cod_dep and xa.cod_dir_superior=c.cod_dir_superior                                                                                                                                                                                                                                        GROUP BY xa.denominacion) as  deno_cod_dir_superior,
						  (SELECT xb.denominacion FROM cugd02_coordinacion xb      where xb.cod_tipo_institucion=c.cod_tipo_inst  and xb.cod_institucion=c.cod_inst and xb.cod_dependencia=c.cod_dep and xb.cod_dir_superior=c.cod_dir_superior and xb.cod_coordinacion=c.cod_coordinacion                                                                                                                                                                                             GROUP BY xb.denominacion) as  deno_cod_coordinacion,
						  (SELECT xc.denominacion FROM cugd02_secretaria xc        where xc.cod_tipo_institucion=c.cod_tipo_inst  and xc.cod_institucion=c.cod_inst and xc.cod_dependencia=c.cod_dep and xc.cod_dir_superior=c.cod_dir_superior and xc.cod_coordinacion=c.cod_coordinacion and xc.cod_secretaria=c.cod_secretaria                                                                                                                                                      GROUP BY xc.denominacion) as  deno_cod_secretaria,
						  (SELECT xd.denominacion FROM cugd02_direccion xd         where xd.cod_tipo_institucion=c.cod_tipo_inst  and xd.cod_institucion=c.cod_inst and xd.cod_dependencia=c.cod_dep and xd.cod_dir_superior=c.cod_dir_superior and xd.cod_coordinacion=c.cod_coordinacion and xd.cod_secretaria=c.cod_secretaria and xd.cod_direccion=c.cod_direccion                                                                                                                 GROUP BY xd.denominacion) as  deno_cod_direccion,
						  (SELECT xe.denominacion FROM cugd02_division xe          where xe.cod_tipo_institucion=c.cod_tipo_inst  and xe.cod_institucion=c.cod_inst and xe.cod_dependencia=c.cod_dep and xe.cod_dir_superior=c.cod_dir_superior and xe.cod_coordinacion=c.cod_coordinacion and xe.cod_secretaria=c.cod_secretaria and xe.cod_direccion=c.cod_direccion  and xe.cod_division=c.cod_division                                                                             GROUP BY xe.denominacion) as  deno_cod_division,
						  (SELECT xf.denominacion FROM cugd02_departamento xf      where xf.cod_tipo_institucion=c.cod_tipo_inst  and xf.cod_institucion=c.cod_inst and xf.cod_dependencia=c.cod_dep and xf.cod_dir_superior=c.cod_dir_superior and xf.cod_coordinacion=c.cod_coordinacion and xf.cod_secretaria=c.cod_secretaria and xf.cod_direccion=c.cod_direccion  and xf.cod_division=c.cod_division and xf.cod_departamento=c.cod_departamento                                  GROUP BY xf.denominacion) as  deno_cod_departamento,
						  (SELECT xg.denominacion FROM cugd02_oficina xg           where xg.cod_tipo_institucion=c.cod_tipo_inst  and xg.cod_institucion=c.cod_inst and xg.cod_dependencia=c.cod_dep and xg.cod_dir_superior=c.cod_dir_superior and xg.cod_coordinacion=c.cod_coordinacion and xg.cod_secretaria=c.cod_secretaria and xg.cod_direccion=c.cod_direccion  and xg.cod_division=c.cod_division and xg.cod_departamento=c.cod_departamento and xg.cod_oficina=c.cod_oficina GROUP BY xg.denominacion) as  deno_cod_oficina,
					  c.cod_estado,
					  c.cod_municipio,
					  c.cod_parroquia,
					  c.cod_centro,
				          (SELECT xya.denominacion FROM cugd01_estados          xya where xya.cod_republica=c.cod_presi and xya.cod_estado=c.cod_estado                                                                                                                  GROUP BY xya.denominacion) as  deno_cod_estado,
						  (SELECT xyb.denominacion FROM cugd01_municipios       xyb where xyb.cod_republica=c.cod_presi and xyb.cod_estado=c.cod_estado  and xyb.cod_municipio=c.cod_municipio                                                                           GROUP BY xyb.denominacion) as  deno_cod_municipio,
						  (SELECT xyc.denominacion FROM cugd01_parroquias       xyc where xyc.cod_republica=c.cod_presi and xyc.cod_estado=c.cod_estado  and xyc.cod_municipio=c.cod_municipio and xyc.cod_parroquia = c.cod_parroquia                                   GROUP BY xyc.denominacion) as  deno_cod_parroquia,
						  (SELECT xyd.denominacion FROM cugd01_centros_poblados xyd where xyd.cod_republica=c.cod_presi and xyd.cod_estado=c.cod_estado  and xyd.cod_municipio=c.cod_municipio and xyd.cod_parroquia = c.cod_parroquia and xyd.cod_centro = c.cod_centro GROUP BY xyd.denominacion) as  deno_cod_centro,
						  (SELECT xyb.conocido     FROM cugd01_municipios       xyb where xyb.cod_republica=c.cod_presi and xyb.cod_estado=c.cod_estado  and xyb.cod_municipio=c.cod_municipio                                                                           GROUP BY xyb.conocido)     as  deno_ciudad,
					  c.condicion_actividad,
					  c.ano,
					  c.cod_sector,
					  c.cod_programa,
					  c.cod_sub_prog,
					  c.cod_proyecto,
					  c.cod_activ_obra,
					  c.cod_partida,
					  c.cod_generica,
					  c.cod_especifica,
					  c.cod_sub_espec,
					  c.cod_auxiliar,
					  c.cod_nivel_i,
					  c.cod_nivel_ii,
					  c.cod_ficha



			FROM


					 cnmd06_datos_personales         a,
					 cnmd06_fichas                   b,
					 cnmd05                          c


			WHERE
                    a.cedula_identidad = '".$id."'          and
                    b.cedula_identidad = a.cedula_identidad and
                    b.condicion_actividad  = 1              and
                    b.cod_presi        = '".$this->verifica_SS(1)."'         and
					b.cod_entidad      = '".$this->verifica_SS(2)."'         and
					b.cod_tipo_inst    = '".$this->verifica_SS(3)."'         and
					b.cod_inst         = '".$this->verifica_SS(4)."'         and
                    c.cod_presi        = b.cod_presi        and
					c.cod_entidad      = b.cod_entidad      and
					c.cod_tipo_inst    = b.cod_tipo_inst    and
					c.cod_inst         = b.cod_inst         and
					c.cod_dep          = b.cod_dep          and
					c.cod_tipo_nomina  = b.cod_tipo_nomina  and
					c.cod_cargo        = b.cod_cargo




;");



$totalPages_Recordset1 =  count($datos);
$totalPages_Recordset1--;

$mensaje = "Esta persona tiene más de un cargo";

if($totalPages_Recordset1>0 && $pag_num==0){

echo "<script>";
      echo "if (confirm('".$mensaje."')) {";

      echo " }";
echo "</script>";

}




$datos = $this->cnmd06_datos_personales->execute("

			SELECT


					  a.cedula_identidad,
					  a.primer_apellido,
					  a.segundo_apellido,
					  a.primer_nombre,
					  a.segundo_nombre,


					  b.cod_presi,
					  b.cod_entidad,
					  b.cod_tipo_inst,
					  b.cod_inst,
					  b.cod_dep,
					  (SELECT x.denominacion  FROM arrd05 x WHERE
							  x.cod_presi           =     b.cod_presi       and
							  x.cod_entidad         =     b.cod_entidad     and
							  x.cod_tipo_inst       =     b.cod_tipo_inst   and
							  x.cod_inst            =     b.cod_inst        and
							  x.cod_dep             =     b.cod_dep
					  ) as denominacion_dependencia,
					  b.cod_tipo_nomina,
					  (SELECT x.denominacion  FROM cnmd01 x WHERE
							  x.cod_presi           =     b.cod_presi       and
							  x.cod_entidad         =     b.cod_entidad     and
							  x.cod_tipo_inst       =     b.cod_tipo_inst   and
							  x.cod_inst            =     b.cod_inst        and
							  x.cod_dep             =     b.cod_dep         and
							  x.cod_tipo_nomina     =     b.cod_tipo_nomina
					  ) as denominacion_nomina,
					  b.cod_cargo,
					  b.cod_ficha,
					  b.fecha_ingreso,
					  b.forma_pago,
					  b.cod_entidad_bancaria,
					  b.cod_sucursal,
					  b.cuenta_bancaria,
					  b.condicion_actividad,
					  b.funciones_realizar,
					  b.responsabilidad_administrativa,
					  b.horas_laborar,
					  b.porcentaje_jub_pension,
					  b.fecha_terminacion_contrato,
					  b.fecha_retiro,
					  b.motivo_retiro,
					  b.paso,
					  b.tipo_contrato,
					  b.situacion,
					  b.nivel,
					  b.categoria,


					  c.cod_puesto,
					  (select devolver_denominacion_puesto(
			               (select xy.clasificacion_personal from cnmd01 xy where
			                  xy.cod_presi           =     c.cod_presi       and
							  xy.cod_entidad         =     c.cod_entidad     and
							  xy.cod_tipo_inst       =     c.cod_tipo_inst   and
							  xy.cod_inst            =     c.cod_inst        and
							  xy.cod_dep             =     c.cod_dep         and
							  xy.cod_tipo_nomina     =     c.cod_tipo_nomina
			               ), c.cod_puesto )
			          ) as demonimacion_puesto,
			          (select devolver_grado_puesto(
			               (select xy.clasificacion_personal from cnmd01 xy where
			                  xy.cod_presi           =     c.cod_presi       and
							  xy.cod_entidad         =     c.cod_entidad     and
							  xy.cod_tipo_inst       =     c.cod_tipo_inst   and
							  xy.cod_inst            =     c.cod_inst        and
							  xy.cod_dep             =     c.cod_dep         and
							  xy.cod_tipo_nomina     =     c.cod_tipo_nomina
			               ), c.cod_puesto )
			          ) as grado_puesto,
					  c.sueldo_basico,
					  c.compensaciones,
					  c.primas,
					  c.bonos,
					  (c.compensaciones + c.primas + c.bonos) as otras_remuneraciones,
					  c.cod_dir_superior,
					  c.cod_coordinacion,
					  c.cod_secretaria,
					  c.cod_direccion,
					  c.cod_division,
					  c.cod_departamento,
					  c.cod_oficina,
						  (SELECT xa.denominacion FROM cugd02_direccionsuperior xa where xa.cod_tipo_institucion=c.cod_tipo_inst  and xa.cod_institucion=c.cod_inst and xa.cod_dependencia=c.cod_dep and xa.cod_dir_superior=c.cod_dir_superior                                                                                                                                                                                                                                        GROUP BY xa.denominacion) as  deno_cod_dir_superior,
						  (SELECT xb.denominacion FROM cugd02_coordinacion xb      where xb.cod_tipo_institucion=c.cod_tipo_inst  and xb.cod_institucion=c.cod_inst and xb.cod_dependencia=c.cod_dep and xb.cod_dir_superior=c.cod_dir_superior and xb.cod_coordinacion=c.cod_coordinacion                                                                                                                                                                                             GROUP BY xb.denominacion) as  deno_cod_coordinacion,
						  (SELECT xc.denominacion FROM cugd02_secretaria xc        where xc.cod_tipo_institucion=c.cod_tipo_inst  and xc.cod_institucion=c.cod_inst and xc.cod_dependencia=c.cod_dep and xc.cod_dir_superior=c.cod_dir_superior and xc.cod_coordinacion=c.cod_coordinacion and xc.cod_secretaria=c.cod_secretaria                                                                                                                                                      GROUP BY xc.denominacion) as  deno_cod_secretaria,
						  (SELECT xd.denominacion FROM cugd02_direccion xd         where xd.cod_tipo_institucion=c.cod_tipo_inst  and xd.cod_institucion=c.cod_inst and xd.cod_dependencia=c.cod_dep and xd.cod_dir_superior=c.cod_dir_superior and xd.cod_coordinacion=c.cod_coordinacion and xd.cod_secretaria=c.cod_secretaria and xd.cod_direccion=c.cod_direccion                                                                                                                 GROUP BY xd.denominacion) as  deno_cod_direccion,
						  (SELECT xe.denominacion FROM cugd02_division xe          where xe.cod_tipo_institucion=c.cod_tipo_inst  and xe.cod_institucion=c.cod_inst and xe.cod_dependencia=c.cod_dep and xe.cod_dir_superior=c.cod_dir_superior and xe.cod_coordinacion=c.cod_coordinacion and xe.cod_secretaria=c.cod_secretaria and xe.cod_direccion=c.cod_direccion  and xe.cod_division=c.cod_division                                                                             GROUP BY xe.denominacion) as  deno_cod_division,
						  (SELECT xf.denominacion FROM cugd02_departamento xf      where xf.cod_tipo_institucion=c.cod_tipo_inst  and xf.cod_institucion=c.cod_inst and xf.cod_dependencia=c.cod_dep and xf.cod_dir_superior=c.cod_dir_superior and xf.cod_coordinacion=c.cod_coordinacion and xf.cod_secretaria=c.cod_secretaria and xf.cod_direccion=c.cod_direccion  and xf.cod_division=c.cod_division and xf.cod_departamento=c.cod_departamento                                  GROUP BY xf.denominacion) as  deno_cod_departamento,
						  (SELECT xg.denominacion FROM cugd02_oficina xg           where xg.cod_tipo_institucion=c.cod_tipo_inst  and xg.cod_institucion=c.cod_inst and xg.cod_dependencia=c.cod_dep and xg.cod_dir_superior=c.cod_dir_superior and xg.cod_coordinacion=c.cod_coordinacion and xg.cod_secretaria=c.cod_secretaria and xg.cod_direccion=c.cod_direccion  and xg.cod_division=c.cod_division and xg.cod_departamento=c.cod_departamento and xg.cod_oficina=c.cod_oficina GROUP BY xg.denominacion) as  deno_cod_oficina,
					  c.cod_estado,
					  c.cod_municipio,
					  c.cod_parroquia,
					  c.cod_centro,
				          (SELECT xya.denominacion FROM cugd01_estados          xya where xya.cod_republica=c.cod_presi and xya.cod_estado=c.cod_estado                                                                                                                  GROUP BY xya.denominacion) as  deno_cod_estado,
						  (SELECT xyb.denominacion FROM cugd01_municipios       xyb where xyb.cod_republica=c.cod_presi and xyb.cod_estado=c.cod_estado  and xyb.cod_municipio=c.cod_municipio                                                                           GROUP BY xyb.denominacion) as  deno_cod_municipio,
						  (SELECT xyc.denominacion FROM cugd01_parroquias       xyc where xyc.cod_republica=c.cod_presi and xyc.cod_estado=c.cod_estado  and xyc.cod_municipio=c.cod_municipio and xyc.cod_parroquia = c.cod_parroquia                                   GROUP BY xyc.denominacion) as  deno_cod_parroquia,
						  (SELECT xyd.denominacion FROM cugd01_centros_poblados xyd where xyd.cod_republica=c.cod_presi and xyd.cod_estado=c.cod_estado  and xyd.cod_municipio=c.cod_municipio and xyd.cod_parroquia = c.cod_parroquia and xyd.cod_centro = c.cod_centro GROUP BY xyd.denominacion) as  deno_cod_centro,
						  (SELECT xyb.conocido     FROM cugd01_municipios       xyb where xyb.cod_republica=c.cod_presi and xyb.cod_estado=c.cod_estado  and xyb.cod_municipio=c.cod_municipio                                                                           GROUP BY xyb.conocido)     as  deno_ciudad,
					  c.condicion_actividad,
					  c.ano,
					  c.cod_sector,
					  c.cod_programa,
					  c.cod_sub_prog,
					  c.cod_proyecto,
					  c.cod_activ_obra,
					  c.cod_partida,
					  c.cod_generica,
					  c.cod_especifica,
					  c.cod_sub_espec,
					  c.cod_auxiliar,
					  c.cod_nivel_i,
					  c.cod_nivel_ii,
					  c.cod_ficha



			FROM


					 cnmd06_datos_personales         a,
					 cnmd06_fichas                   b,
					 cnmd05                          c


			WHERE
                    a.cedula_identidad = '".$id."'          and
                    b.cedula_identidad = a.cedula_identidad and
                    b.condicion_actividad  = 1              and
                    b.cod_presi        = '".$this->verifica_SS(1)."'         and
					b.cod_entidad      = '".$this->verifica_SS(2)."'         and
					b.cod_tipo_inst    = '".$this->verifica_SS(3)."'         and
					b.cod_inst         = '".$this->verifica_SS(4)."'         and
                    c.cod_presi        = b.cod_presi        and
					c.cod_entidad      = b.cod_entidad      and
					c.cod_tipo_inst    = b.cod_tipo_inst    and
					c.cod_inst         = b.cod_inst         and
					c.cod_dep          = b.cod_dep          and
					c.cod_tipo_nomina  = b.cod_tipo_nomina  and
					c.cod_cargo        = b.cod_cargo


		    LIMIT 1 OFFSET ".$pag_num." * 1




;");






if(isset($datos[0][0]["cod_presi"])){
	  $cod_presi              =  $datos[0][0]["cod_presi"];
	  $cod_entidad            =  $datos[0][0]["cod_entidad"];
	  $cod_tipo_inst          =  $datos[0][0]["cod_tipo_inst"];
	  $cod_inst               =  $datos[0][0]["cod_inst"];
	  $cod_dep                =  $datos[0][0]["cod_dep"];
	  $cod_tipo_nomina        =  $datos[0][0]["cod_tipo_nomina"];
	  $cod_cargo              =  $datos[0][0]["cod_cargo"];
	  $cod_ficha              =  $datos[0][0]["cod_ficha"];
}else{

  $cod_tipo_nomina        =  0;
  $cod_cargo              =  0;
  $cod_ficha              =  0;

}//fin else

$datos_sueldo =   $this->cnmd05->findAll("cod_presi='".$cod_presi."' and cod_entidad='".$cod_entidad."' and cod_tipo_inst='".$cod_tipo_inst."' and cod_inst=".$cod_inst." and  cod_tipo_nomina='".$cod_tipo_nomina."' and cod_cargo=".$cod_cargo, null, null);
$this->set('dato_sueldo', $datos_sueldo);

$this->Session->write('cod_dep_expediente',         $cod_dep);
$this->Session->write('cod_tipo_nomina_expediente', $cod_tipo_nomina);
$this->Session->write('cod_cargo_expediente',       $cod_cargo);
$this->Session->write('cod_ficha_expediente',       $cod_ficha);


$datos2 = $this->cnmd06_datos_personales->execute("

			SELECT

                  a.cod_presi,
				  a.cod_entidad,
				  a.cod_tipo_inst,
				  a.cod_inst,
				  a.cod_dep,
				  a.cod_tipo_nomina,
				  a.cod_cargo,
				  a.cod_ficha,
				  a.cod_tipo_transaccion,
				  a.cod_transaccion,
				  a.fecha_transaccion,
				  a.monto_original,
				  a.numero_cuotas_descontar,
				  a.numero_cuotas_cancelar,
				  a.numero_cuotas_canceladas,
				  a.monto_cuota,
				  a.saldo,
				  a.marca_fin_descuento,
				  a.fecha_proceso,
				  a.username,
				  (Select devolver_denominacion_transaccion(a.cod_presi , a.cod_entidad , a.cod_tipo_inst , a.cod_inst , a.cod_dep , a.cod_tipo_nomina , a.cod_tipo_transaccion , a.cod_transaccion )) as denominacion_transaccion,
				  (SELECT x.denominacion  FROM cnmd03_transacciones x WHERE
							  x.cod_tipo_transaccion    =     a.cod_tipo_transaccion       and
							  x.cod_transaccion         =     a.cod_transaccion
					  ) as denominacion_transaccion_aux,
				   (SELECT x.uso_transaccion  FROM cnmd03_transacciones x WHERE
							  x.cod_tipo_transaccion    =     a.cod_tipo_transaccion       and
							  x.cod_transaccion         =     a.cod_transaccion
					  ) as uso_transaccion


			FROM

					 cnmd07_transacciones_actuales         a

			WHERE

  	                a.cod_presi        = '".$cod_presi."'        and
					a.cod_entidad      = '".$cod_entidad."'      and
					a.cod_tipo_inst    = '".$cod_tipo_inst."'    and
					a.cod_inst         = '".$cod_inst."'         and
					a.cod_dep          = '".$cod_dep."'          and
					a.cod_tipo_nomina  = '".$cod_tipo_nomina."'  and
					a.cod_cargo        = '".$cod_cargo."'        and
					a.cod_ficha        = '".$cod_ficha."'



;");




$datos3 = $this->cnmd06_datos_personales->execute("

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
				  	 (select  x.primer_apellido  from cnmd06_datos_personales x where x.cedula_identidad = a.cedula_identidad ) as primer_apellido,
					 (select  x.segundo_apellido from cnmd06_datos_personales x where x.cedula_identidad = a.cedula_identidad ) as segundo_apellido,
					 (select  x.primer_nombre    from cnmd06_datos_personales x where x.cedula_identidad = a.cedula_identidad ) as primer_nombre,
					 (select  x.segundo_nombre   from cnmd06_datos_personales x where x.cedula_identidad = a.cedula_identidad ) as segundo_nombre,
				  a.fecha_ingreso,
				  a.forma_pago,
				  a.cod_entidad_bancaria,
				  a.cod_sucursal,
				  a.cuenta_bancaria,
				  a.condicion_actividad,
				  a.funciones_realizar,
				  a.responsabilidad_administrativa,
				  a.horas_laborar,
				  a.porcentaje_jub_pension,
				  a.fecha_terminacion_contrato,
				  a.fecha_retiro,
				  a.motivo_retiro,
				  a.paso,
				  a.tipo_contrato,
				  a.situacion,
				  a.nivel,
				  a.categoria



			FROM


					 cnmd06_fichas      a


			WHERE

  	                a.cod_presi            = '".$cod_presi."'        and
					a.cod_entidad          = '".$cod_entidad."'      and
					a.cod_tipo_inst        = '".$cod_tipo_inst."'    and
					a.cod_inst             = '".$cod_inst."'         and
					a.cod_dep              = '".$cod_dep."'          and
					a.cod_tipo_nomina      = '".$cod_tipo_nomina."'  and
					a.cod_cargo            = '".$cod_cargo."'        and
					a.condicion_actividad  = 6



;");




    $this->set("totalPages_Recordset1", $totalPages_Recordset1);
    $this->set("datos", $datos);
    $this->set("datos2", $datos2);
    $this->set("datos3", $datos3);

}//fin function









}//fin de la clase controller
?>